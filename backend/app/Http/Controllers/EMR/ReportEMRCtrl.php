<?php

namespace App\Http\Controllers\EMR;

use App\Http\Controllers\Controller;
use App\Models\Master\Diagnosa;
use App\Models\Master\Pegawai;
use App\Models\Master\Pasien;
use App\Models\Transaksi\EmrDokumen;
use App\Models\Transaksi\EMRPasien;
use App\Models\Transaksi\EMRPasienForm;
use App\Models\Transaksi\PasienDaftar;
use App\Traits\Valet;
use DateTime;
use Exception;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\App;
use Ramsey\Uuid\Uuid;
use App\Models\Master\Profile;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelLow;
use App\Http\Controllers\Bridging\BridgingBPJSCtrl;
use Endroid\QrCode\QrCode as Png;
use Endroid\QrCode\Writer\PngWriter;
use Illuminate\Support\Facades\File;
use Webklex\PDFMerger\Facades\PDFMergerFacade as PDFMerger;


class ReportEMRCtrl extends Controller
{
    use Valet;
    protected $bridgingBPJSCtrl;
    public function __construct(BridgingBPJSCtrl $bridgingBPJSCtrl)
    {
        parent::__construct($is_encrypt = true);
        $this->bridgingBPJSCtrl = $bridgingBPJSCtrl;
    }

    function dateLocalID($format, $time = false)
    { // "Asia/Tokyo"
        $day = array('Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min');
        $days = array('Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu');
        $month = array('', 'Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des');
        $months = array('', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember');

        if (!is_a($time, 'DateTime')) {
            if (is_int($time)) {
                $time = new DateTime(date('Y-m-d H:i:s.u', $time));
            } elseif (is_string($time)) {
                try {
                    $time = new DateTime($time);
                } catch (Exception $e) {
                    $time = new DateTime();
                }
            } else {
                $time = new DateTime();
            }
        }
        $ret = '';
        for ($i = 0; $i < strlen($format); $i++) {
            switch ($format[$i]) {
                case 'D':
                    $ret .= $day[$time->format('w')];
                    break;
                case 'l':
                    $ret .= $days[$time->format('w')];
                    break;
                case 'M':
                    $ret .= $month[$time->format('n')];
                    break;
                case 'F':
                    $ret .= $months[$time->format('n')];
                    break;
                case '\\':
                    $ret .= $format[$i + 1];
                    $i++;
                    break;
                default:
                    $ret .= $time->format($format[$i]);
                    break;
            }
        }
        return $ret;
    }

    public function init(Request $r)
    {

        $res = DB::connection('mongodb')
            ->table($r['collection'])
            ->where('registrasi.norec_pd', $r['norec_pd'])
            ->where('pasien.nocmfk', $r['nocmfk'])
            ->where('profile.kdprofile', $this->kdProfile)
            ->where('statusenabled', true);
        if (isset($r['emrpasienfk']) && $r['emrpasienfk'] != '') {
            $res = $res->where('emrpasienfk', $r['emrpasienfk']);
            $res = $res->where('emrpasienfk', $r['emrpasienfk']);
        }
        $res = $res->orderByDesc('created_at');
        $res = $res->get()->toArray();
        $data = $res[0];
        $pasien = [
            "nocm" => $data['pasien']['nocm'],
            "namapasien" => $data['pasien']['namapasien'],
            "jeniskelamin" => $data['pasien']['jeniskelamin'],
            "tgllahir" => $data['pasien']['tgllahir'],
            "namaruangan" => $data['registrasi']['namaruangan'],
        ];
        $result = [
            "pasien" => $pasien,
            "datas" => $data,
        ];

        return $result;
    }

    function generateDataQR($data, $collection, $routeName = 'dokumen.qrcode', $additionalParams = [], $overrides = [], $margin = 5, $size = 100)
    {
        // Default data
        $dataQR = [
            "nocm" => isset($data['pasien']['nocm']) ? $data['pasien']['nocm'] : '',
            "namaruangan" => $data['registrasi']['namaruangan'],
            "dpjp" => $data['registrasi']['dokter'],
            "tglberkunjung" => date("Y-m-d", strtotime($data['registrasi']['tglregistrasi'])),
            "tglpulang" => date("Y-m-d", strtotime($data['registrasi']['tglpulang'])),
            "created_at" => date("Y-m-d", strtotime($data['created_at'])),
            "type" => $collection,
        ];

        // Override the default values with provided values
        $dataQR = array_merge($dataQR, $overrides);

        // Build the string for the QR code (only include values that exist)
        $stringQR = $collection . ';' . $data['_id']; // collection and id are always required

        // Add dpjp if it exists
        if (isset($dataQR['dpjp'])) {
            $stringQR .= ';' . $dataQR['dpjp'];
        }

        // Encode the string for the QR code
        $encryptQR = base64_encode($stringQR);

        // Add additional parameters to the URL (e.g., signature URL and other params)
        $urlParams = http_build_query($additionalParams);
        $dataQR['qrcode'] = route($routeName) . '?key=' . $encryptQR . '&' . $urlParams;

        // Generate QR code from the URL
        $canvasPNG = new Png($dataQR['qrcode']);
        $canvasPNG->setMargin($margin); // Set dynamic margin
        $canvasPNG->setSize($size); // Set dynamic size

        // Set error correction level
        $canvasPNG->setErrorCorrectionLevel(new ErrorCorrectionLevelLow);

        // Write the QR code to PNG format
        $writer = new PngWriter();
        $resultBarcode = $writer->write($canvasPNG);

        // Encode to base64 for display
        $qrcode = base64_encode($resultBarcode->getString());
        $dataQR['qrcode_base64'] = $qrcode; // Add base64 encoded QR code to the data

        return $dataQR;
    }

    public function getDiagnosaPasienICD9($nocm)
    {

        $data = DB::table('pasiendaftar_t as pd')
            ->join('pasien_m as ps', 'ps.id', '=', 'pd.nocmfk')
            ->join('antrianpasiendiperiksa_t as apd', 'apd.noregistrasifk', '=', 'pd.norec')
            ->join('ruangan_m as ru', 'ru.id', '=', 'apd.objectruanganfk')
            ->join('diagnosatindakanpasien_t as dtp', 'dtp.objectpasienfk', '=', 'apd.norec')
            ->join('detaildiagnosatindakanpasien_t as ddt', 'ddt.objectdiagnosatindakanpasienfk', '=', 'dtp.norec')
            ->join('diagnosatindakan_m as dt', 'dt.id', '=', 'ddt.objectdiagnosatindakanfk')
            ->leftjoin('pegawai_m as pg', 'pg.id', '=', 'ddt.objectpegawaifk')
            ->select(
                'pd.noregistrasi',
                'pd.tglregistrasi',
                'ru.namaruangan',
                'ddt.objectdiagnosatindakanfk',
                'dt.kddiagnosatindakan',
                'dt.namadiagnosatindakan',
                'ddt.keterangantindakan',
                'pg.namalengkap',
                DB::raw("CAST(ddt.tglinputdiagnosa as DATE)")
            )
            ->where('pd.kdprofile', $this->kdProfile)
            ->where('ps.nocm', '=', $nocm)
            ->orderby('ddt.tglinputdiagnosa', 'desc')
            ->get();

        return $data;
    }

    public function getDiagnosaPasienICD10($nocm)
    {

        $data = DB::table('pasiendaftar_t as pd')
            ->select(
                'pd.noregistrasi',
                'pd.tglregistrasi',
                'apd.objectruanganfk',
                'ru.namaruangan',
                'ddp.objectdiagnosafk',
                'dg.kddiagnosa',
                'dg.namadiagnosa',
                'ddp.objectjenisdiagnosafk',
                'jd.jenisdiagnosa',
                'ddp.tglinputdiagnosa',
                DB::raw("CAST(ddp.tglinputdiagnosa as DATE)"),
                'pg.namalengkap',
                'dp.ketdiagnosis',
                'ddp.keterangan',
                'dp.iskasusbaru',
                'dp.iskasuslama'
            )
            ->join('pasien_m as ps', 'ps.id', '=', 'pd.nocmfk')
            ->join('antrianpasiendiperiksa_t as apd', 'apd.noregistrasifk', '=', 'pd.norec')
            ->join('ruangan_m as ru', 'ru.id', '=', 'apd.objectruanganfk')
            ->join('diagnosapasien_t as dp', 'dp.noregistrasifk', '=', 'apd.norec')
            ->join('detaildiagnosapasien_t as ddp', 'ddp.objectdiagnosapasienfk', '=', 'dp.norec')
            ->join('diagnosa_m as dg', 'dg.id', '=', 'ddp.objectdiagnosafk')
            ->join('jenisdiagnosa_m as jd', 'jd.id', '=', 'ddp.objectjenisdiagnosafk')
            ->leftjoin('pegawai_m as pg', 'pg.id', '=', 'ddp.objectpegawaifk')
            ->where('pd.kdprofile', $this->kdProfile)
            ->where('ps.nocm', $nocm)
            ->orderby('ddp.tglinputdiagnosa', 'desc')
            ->get();

        return $data;
    }

    public function cetakAsesmenMedisRI(Request $request)
    {

        $data = $this->init($request);

        $pasien = $data['pasien'];
        $data = $data['datas'];
        $profile = $this->profile();
        $icd9 = $this->getDiagnosaPasienICD9($pasien['nocm']);
        $icd10 = $this->getDiagnosaPasienICD10($pasien['nocm']);

        return view('report.emr.asesmen-medis-ri', compact('profile', 'pasien', 'data', 'icd9', 'icd10'));
    }

    public function cetakAsesmenGiziAwal(Request $request)
    {

        $data = $this->init($request);

        $pasien = $data['pasien'];
        $data = $data['datas'];
        $profile = $this->profile();

        return view('report.emr.asesmen-awal-gizi', compact('profile', 'pasien', 'data'));
    }

    public function cetakAsesmenAwalKeperawatanRI(Request $request)
    {
        $datas = $this->init($request);
        $data = $datas['datas'];
        $pasien = $datas['pasien'];
        $profile = $this->profile();

        $tgl = explode('T', $data[' '])[0];
        $waktu = explode('T', $data['tgldanJam'])[1];
        $jam = explode('.', $waktu)[0];
        return view('report.emr.asesmen-awal-keperawatan-ri', compact('profile', 'data', 'pasien', 'tgl', 'jam'));
    }

    public function cetakTriase(Request $request)
    {
        $datas = $this->init($request);
        $data = $datas['datas'];
        $pasien = $datas['pasien'];
        $profile = $this->profile();
        return view('report.emr.triase', compact('profile', 'data', 'pasien'));
    }

    public function cetakResumeRJ(Request $request)
    {
        $datas = $this->init($request);
        $data = $datas['datas'];
        $pasien = $datas['pasien'];
        $profile = $this->profile();
        $tgl = explode(' ', $data['created_at'])[0];
        $waktu = explode(' ', $data['created_at'])[1];
        return view('report.emr.resume-medis-rj', compact('profile', 'data', 'pasien', 'tgl', 'waktu'));
    }

    public function cetakAsesmenAwalKeperRJ(Request $request)
    {
        $datas = $this->init($request);
        $data = $datas['datas'];
        $pasien = $datas['pasien'];
        $profile = $this->profile();
        return view('report.emr.asesmen-awal-keperawata-rj', compact('profile', 'data', 'pasien'));
    }

    public function cetakAsesmenMedisRJ(Request $request)
    {
        $datas = $this->init($request);
        $data = $datas['datas'];
        $pasien = $datas['pasien'];
        $icd9 = $this->getDiagnosaPasienICD9($pasien['nocm']);
        $icd10 = $this->getDiagnosaPasienICD10($pasien['nocm']);
        $profile = $this->profile();

        return view('report.emr.asesmen-medis-rj', compact('profile', 'data', 'pasien', 'icd9', 'icd10'));
    }

    public function cetakHasilPemeriksaanMCU(Request $request)
    {
        $datas = $this->init($request);
        $data = $datas['datas'];
        $pasien = $datas['pasien'];
        $profile = $this->profile();

        return view('report.emr.hasil-pemeriksaan-mcu', compact('profile', 'data', 'pasien'));
    }

    public function cetakPolaNafasTidakEfektif(Request $request)
    {
        $datas = $this->init($request);
        $data = $datas['datas'];
        $pasien = $datas['pasien'];
        $profile = $this->profile();

        return view('report.emr.pola-nafas-tidak-efektif', compact('profile', 'data', 'pasien'));
    }
    public function cetakPengkajianDokterRi(Request $request)
    {
        $datas = $this->init($request);
        $data = $datas['datas'];
        $pasien = $datas['pasien'];
        $profile = $this->profile();

        return view('report.emr.pengkajian-dokter-ri', compact('profile', 'data', 'pasien'));
    }

    public function cetakFormulirSkriningIGD(Request $request)
    {
        $datas = $this->init($request);
        $data = $datas['datas'];
        $pasien = $datas['pasien'];
        $profile = $this->profile();

        return view('report.emr.formulir-skrining-igd', compact('profile', 'data', 'pasien'));
    }

    // public function cetakResumeMedis(Request $request)
    // {
    //     $datas = $this->init($request);
    //     $data =  $datas['datas'];
    //     $pasien = $datas['pasien'];
    //     $profile = $this->profile();

    //     return view('report.emr.resume-medis-lk',compact('profile','data','pasien'));
    // }

    public function cetakFormulirPermintaanKonselingGizi(Request $request)
    {
        $datas = $this->init($request);
        $data = $datas['datas'];
        $pasien = $datas['pasien'];
        $profile = $this->profile();

        return view('report.emr.formulir-permintaan-konseling-gizi', compact('profile', 'data', 'pasien'));
    }

    public function cetakAsesmenKeperawatanIGD(Request $request)
    {
        $datas = $this->init($request);
        $data = $datas['datas'];
        $pasien = $datas['pasien'];
        $profile = $this->profile();
        $hari = $this->dateLocalID('l', $data['waktuPemeriksaan']) . ' / ' . date('d-m-Y', strtotime($data['waktuPemeriksaan']));
        // $tidakanIGD = $data['details']
        return view('report.emr.asesmen-keperawatan-igd', compact('profile', 'data', 'pasien', 'hari'));
    }
    public function cetakLembarBantuPengamatanMenyusui(Request $request)
    {
        $datas = $this->init($request);
        $data = $datas['datas'];
        $pasien = $datas['pasien'];
        $profile = $this->profile();

        return view('report.emr.lembar-bantu-pengamatan-menyusui', compact('profile', 'data', 'pasien'));
    }


    public function cetakFormulirKriteriaMasukICU(Request $request)
    {
        $datas = $this->init($request);
        $data = $datas['datas'];
        $pasien = $datas['pasien'];
        $profile = $this->profile();
        $collection = $request['collection'];

        $dataQR = $this->generateDataQR(
            $data,
            $collection,
            'dokumen.signature',
            ['a' => '0'],
            ['dpjp' => isset($data['dokterBertugas']['label']) ? $data['dokterBertugas']['label'] : ''],
            5,
            130
        );

        // return response()->json($data);

        $pdf = App::make('dompdf.wrapper');
        $pdf->setPaper('A4', 'portrait');
        $pdf->loadView('report.emr.formulir-kriteria-masuk-icu', [
            'profile' => $profile,
            'identitas' => $pasien,
            'pasien' => $pasien,
            'qrcode' => $dataQR['qrcode_base64'],
            'registrasi' => $data['registrasi'],
            'data' => $data,
        ]);
        return $pdf->stream();

    }

    public function cetakFormulirKriteriaMasukHCU(Request $request)
    {
        $datas = $this->init($request);
        $data = $datas['datas'];
        $pasien = $datas['pasien'];
        $profile = $this->profile();
        $collection = $request['collection'];

        $dataQR = $this->generateDataQR(
            $data,
            $collection,
            'dokumen.signature',
            ['a' => '0'],
            ['dpjp' => isset($data['pegawai']['label']) ? $data['pegawai']['label'] : ''],
            5,
            130
        );

        // return response()->json($data);

        $pdf = App::make('dompdf.wrapper');
        $pdf->setPaper('A4', 'portrait');
        $pdf->loadView('report.emr.formulir-kriteria-masuk-hcu', [
            'profile' => $profile,
            'identitas' => $pasien,
            'pasien' => $pasien,
            'qrcode' => $dataQR['qrcode_base64'],
            'registrasi' => $data['registrasi'],
            'data' => $data,
        ]);
        return $pdf->stream();

    }

    public function cetakFormulirKriteriaMasukICCU(Request $request)
    {
        $datas = $this->init($request);
        $data = $datas['datas'];
        $pasien = $datas['pasien'];
        $profile = $this->profile();
        $collection = $request['collection'];

        $dataQR = $this->generateDataQR(
            $data,
            $collection,
            'dokumen.signature',
            ['a' => '0'],
            ['dpjp' => isset($data['dokter']['label']) ? $data['dokter']['label'] : ''],
            5,
            130
        );

        // return response()->json($data);

        $pdf = App::make('dompdf.wrapper');
        $pdf->setPaper('A4', 'portrait');
        $pdf->loadView('report.emr.formulir-kriteria-masuk-iccu', [
            'profile' => $profile,
            'identitas' => $pasien,
            'pasien' => $pasien,
            'qrcode' => $dataQR['qrcode_base64'],
            'registrasi' => $data['registrasi'],
            'data' => $data,
        ]);
        return $pdf->stream();
    }

    public function cetakPengkajianTingkatKeparahanStroke(Request $request)
    {
        $datas = $this->init($request);
        $data = $datas['datas'];
        $pasien = $datas['pasien'];
        $profile = $this->profile();
        $collection = $request['collection'];

        $dataQR = $this->generateDataQR(
            $data,
            $collection,
            'dokumen.signature',
            ['a' => '0'],
            ['dpjp' => isset($data['pegawaiPemberiSkorMasukRs']['label']) ? $data['pegawaiPemberiSkorMasukRs']['label'] : ''],
            5,
            110
        );

        $dataQR2 = $this->generateDataQR(
            $data,
            $collection,
            'dokumen.signature',
            ['a' => '0'],
            ['dpjp' => isset($data['pegawaiPemberiSkorPerubahanKondisi']['label']) ? $data['pegawaiPemberiSkorPerubahanKondisi']['label'] : ''],
            5,
            110
        );

        $dataQR3 = $this->generateDataQR(
            $data,
            $collection,
            'dokumen.signature',
            ['a' => '0'],
            ['dpjp' => isset($data['pegawaiPemberiSkorDischarge']['label']) ? $data['pegawaiPemberiSkorDischarge']['label'] : ''],
            5,
            110
        );

        // return response()->json($data);

        $pdf = App::make('dompdf.wrapper');
        $pdf->setPaper('A4', 'portrait');
        $pdf->loadView('report.emr.PengkajianTingkatKeparahanStroke', [
            'profile' => $profile,
            'identitas' => $pasien,
            'pasien' => $pasien,
            'qrcode' => $dataQR['qrcode_base64'],
            'qrcode2' => $dataQR2['qrcode_base64'],
            'qrcode3' => $dataQR3['qrcode_base64'],
            'registrasi' => $data['registrasi'],
            'data' => $data,
        ]);
        return $pdf->stream();

    }

    public function cetakCPPT(Request $r)
    {
        // Get data CPPT
        $cppt = DB::connection('mongodb')
            ->table('CPPTDetail')
            ->where('kdprofile', $this->kdProfile)
            ->where('statusenabled', true);

        if (isset($r['from']) && $r['from'] == 'CPPT') {
            $data = DB::connection('mongodb')
                ->table('CatatanPerkembanganPasienTerintegrasi')
                ->where('profile.kdprofile', $this->kdProfile)
                ->where('statusenabled', true)
                ->where('pasien.nocmfk', $r['nocmfk'])
                ->first();

            if (isset($r['emrpasienfk']) && $r['emrpasienfk'] != '' && isset($r['allPeriode']) && $r['allPeriode'] == 'false') {
                $cppt = $cppt->where('emrpasienfk', '=', $r['emrpasienfk']);
            }
            if (isset($r['norec_pd']) && $r['norec_pd'] != '' && isset($r['allPeriode']) && $r['allPeriode'] == 'false') {
                $cppt = $cppt->where('norec_pd', '=', $r['norec_pd']);
            }
            if (isset($r['nocmfk']) && $r['nocmfk'] != '' && isset($r['allPeriode']) && $r['allPeriode'] == 'true') {
                $cppt = $cppt->where('nocmfk', '=', $r['nocmfk']);
            }
        } else {
            if (isset($r['emrpasienfk']) && $r['emrpasienfk'] != '') {
                $cppt = $cppt->where('emrpasienfk', '=', $r['emrpasienfk']);
            }
            if (isset($r['norec_pd']) && $r['norec_pd'] != '') {
                $cppt = $cppt->where('norec_pd', '=', $r['norec_pd']);
            }
        }
        if (isset($r['flag']) && $r['flag'] != '') {
            $flag = '';
            switch ($r['flag']) {
                case 'dokter':
                    $flag = 'dokter';
                    break;
                case 'perawat':
                    $flag = 'perawat';
                    break;
                case 'fisiotherapy':
                    $flag = 'perawat';
                    break;
                case 'kebidanan':
                    $flag = 'perawat';
                    break;
                default:
                    $flag = 'semua';
                    break;
            }
            if ($flag != 'semua') {
                $cppt = $cppt->where('flag', '=', $flag);
            }
        }
        $cppt = $cppt->get();
        $data['details'] = $cppt;
        if ($data['details']->isEmpty()) {
            echo '
                <script language="javascript">
                    window.alert("Tidak ada data.");
                    window.close()
                </script>';
            die;
        }
        $dataawal = DB::connection('mongodb')
            ->table('CatatanPerkembanganPasienTerintegrasi')
            ->where('profile.kdprofile', $this->kdProfile)
            ->where('statusenabled', true)
            ->where('pasien.nocmfk', $r['nocmfk'])
            ->first();

        $header_use = "head-emr";
        $registrasi = PasienDaftar::where('noregistrasi', '=', $dataawal['registrasi']['noregistrasi'])->first();
        $profile = $this->profile();
        // $pasien = Pasien::where('id', '=', $registrasi->nocmfk)->first();
        $pasien = $dataawal['pasien'];
        $registrasi = $dataawal['registrasi'];
        $cekWargaNegaraWNA = ($r !== null && isset($r['wna'])) ? $r['wna'] : false;
        $pdf = App::make('dompdf.wrapper');
        $pdf->setPaper('A4', 'portrait');
        $pdf->loadView('report.emr.CatatanPerkembanganPasienTerintegrasi', [
            'cekWargaNegaraWNA' => $cekWargaNegaraWNA,
            'profile' => $profile,
            'identitas' => $pasien,
            'pasien' => $pasien,
            'registrasi' => $registrasi,
            'data' => $data,
            'header_use' => $header_use,
        ]);
        return view('report.emr.CatatanPerkembanganPasienTerintegrasi', [
            'cekWargaNegaraWNA' => $cekWargaNegaraWNA,
            'profile' => $profile,
            'identitas' => $pasien,
            'pasien' => $pasien,
            'registrasi' => $registrasi,
            'data' => $data,
            'header_use' => $header_use,
        ]);
    }

    public function cetakCPPTKlaim(Request $r)
    {
        // Get data CPPT
        $exp = explode(',', $r['arr']);
        $expx = array_slice($exp, 0, -1);
        // var_dump($expx);
        $cppt = DB::connection('mongodb')
            ->table('CPPTDetail')
            ->where('kdprofile', $this->kdProfile)
            ->where('statusenabled', true)
            ->whereIn('uuid', $expx)
            ->get();

        $data = DB::connection('mongodb')
            ->table('CatatanPerkembanganPasienTerintegrasi')
            ->where('profile.kdprofile', $this->kdProfile)
            ->where('statusenabled', true)
            ->where('pasien.nocmfk', $r['nocmfk'])
            ->where('registrasi.norec_pd', $r['norec_pd'])
            ->first();

        $data['details'] = $cppt;
        if ($data['details']->isEmpty()) {
            echo '
                <script language="javascript">
                    window.alert("Tidak ada data.");
                    window.close()
                </script>';
            die;
        }

        $registrasi = PasienDaftar::where('noregistrasi', '=', $data['registrasi']['noregistrasi'])->first();
        $profile = $this->profile();
        $pasien = DB::table('pasien_m as ps')
            ->join('jeniskelamin_m as jk', 'jk.id', '=', 'ps.objectjeniskelaminfk')
            ->join('pasiendaftar_t as pd', 'pd.nocmfk', '=', 'ps.id')
            ->join('ruangan_m as ru', 'pd.objectruanganlastfk', '=', 'ru.id')
            ->where('ps.id', '=', $registrasi->nocmfk)
            ->where('pd.norec', '=', $registrasi->norec)
            ->select('ps.*', 'jk.jeniskelamin', 'ru.namaruangan')
            ->first();
        $pdf = App::make('dompdf.wrapper');
        $pdf->setPaper('A4', 'portrait');
        $pdf->loadView('report.emr.CatatanPerkembanganPasienTerintegrasiKlaim', [
            'profile' => $profile,
            'pasien' => $pasien,
            'data' => $data,
        ]);

        $fileName = 'cppt_' . $data['registrasi']['noregistrasi'] . '.pdf';

        $cek = DB::table('monitoringdokklaim_t')->where('filename', '=', $fileName)->first();

        if (empty($cek)) {
            $dataInsert = array(
                "norec" => Uuid::uuid4(),
                "kdprofile" => $this->kdProfile,
                "statusenabled" => true,
                "filename" => $fileName,
                "filepath" => 'dokumen_klaim/' . $data['registrasi']['noregistrasi'] . "/" . $fileName,
                "nocmfk" => $registrasi->nocmfk,
                "tglregistrasi" => $registrasi->tglregistrasi,
                "noregistrasifk" => $registrasi->norec,
                "documentklaimfk" => 8,
            );


            DB::table('monitoringdokklaim_t')->insert($dataInsert);
        }

        $zipDirectory = storage_path('app/public/dokumen_klaim/' . $data['registrasi']['noregistrasi']);
        if (!file_exists($zipDirectory)) {
            mkdir($zipDirectory, 0755, true);
        }
        $zipFilePath = $zipDirectory . '/' . $fileName;
        $pdf->save($zipFilePath);

        if (file_exists($zipFilePath)) {
            echo '
                    <script language="javascript">
                        window.alert("Berhasil Disimpan.");
                        window.close()
                    </script>';

            die;
        } else {
            echo '
                    <script language="javascript">
                        window.alert("Gagal Disimpan.");
                        window.close()
                    </script>';

            die;
        }

        // return view('report.emr.CatatanPerkembanganPasienTerintegrasiKlaim', [
        //     'profile' => $profile,
        //     'pasien' => $pasien,
        //     'data' => $data,
        // ]);

    }

    public function cetakEMR($collection, Request $r)
    {
        // start generate parameter kebutuhan save dokumen

        if (isset($r['noregistrasi']) && $r['noregistrasi'] != "") {
            $registrasi = PasienDaftar::where('noregistrasi', '=', $r['noregistrasi'])->first();
        } else {
            $dataawal = DB::connection('mongodb')
                ->table($collection)
                ->where('profile.kdprofile', $this->kdProfile)
                ->where('statusenabled', true)
                ->where('emrpasienfk', $r['emrpasienfk'])
                ->first();

            $registrasi = PasienDaftar::where('noregistrasi', '=', $dataawal['registrasi']['noregistrasi'])->first();
            $r['noregistrasi'] = $registrasi->noregistrasi;
        }

        // List collection dengan tampilan landscape
        $collection_landscape = [
            'FormulirCatataPemberianObatKemoterapi',
        ];

        $qrcode2 = '';
        $qrcode3 = '';
        $qrcode4 = '';
        $tte = '';
        $tte2 = '';
        $tte3 = '';
        $dataQR2 = '';
        $qrcodeAdmission = '';
        if ($collection == 'FormulirBuktiPelayananCanggih' || $collection == 'FormulirKedokteranFisikDanRehabilitasi') {
            $pasien = Pasien::where('id', '=', $registrasi->nocmfk)->first();
            if ($pasien && !empty($pasien->nobpjs)) {
                $qrcode2 = base64_encode(QrCode::format('svg')->size(45)->generate((string) $pasien->nobpjs));
                // $qrcode2 = QrCode::format('svg')->size(45)->generate((string) $pasien->nobpjs);

                // return $qrcode2;
            } elseif ($pasien && !empty($pasien->nocm)) {
                $qrcode2 = base64_encode(QrCode::format('svg')->size(45)->generate((string) $pasien->nocm));
            } else {
                $qrcode2 = '';
            }
        } else {
            $qrcode2 = '';
        }

        $dataEMR2 = '';

        if ($collection == 'RingkasanKeluar') {
            if (isset($r['noregistrasi'])) {
                $data['dpjpcadangan'] = '';
                $asuransi = DB::table('pasiendaftar_t as pd')
                    ->leftjoin('pemakaianasuransi_t as pa', 'pa.noregistrasifk', '=', 'pd.norec')
                    ->select(
                        'pd.norec as norec_pd',
                        'pa.nosep',
                    )
                    ->where('pd.noregistrasi', '=', $r['noregistrasi'])
                    ->first();

                // if(!empty($asuransi->nosep != null)){
                //     $objetoRequest = new \Illuminate\Http\Request();
                //     $objetoRequest['url'] = "SEP/" . $asuransi->nosep;
                //     $objetoRequest['method'] = "GET";
                //     $objetoRequest['data'] = null;
                //     $cariSEP =  $this->bridgingBPJSCtrl->bpjsTools($objetoRequest, true);
                //     $responseSEPVc = json_decode(json_encode($cariSEP, false));
                //     $responseSEP = $responseSEPVc->response;
                //     $data['dpjpcadangan'] = $responseSEP->dpjp->nmDPJP;
                // }



                $dataEMR2 = DB::connection('mongodb')
                    ->table('AsesmenFisioterapi')
                    ->where('profile.kdprofile', $this->kdProfile)
                    ->where('statusenabled', true)
                    ->where('registrasi.noregistrasi', $r['noregistrasi']);

                // if ($collection == 'JadwalKunjunganRehabDanFisio') {
                $dataEMR2 = $dataEMR2->orderBy('created_at', 'desc');
                // }

                $dataEMR2 = $dataEMR2->first();

                if (!empty($dataEMR2))
                    $r['emrpasienfk'] = $dataEMR2['emrpasienfk'];
                else
                    $r['emrpasienfk'] = null;
            }
            // start generate parameter kebutuhan save dokumen

            // dd($dataEMR2);
            if ($dataEMR2 != null && ($registrasi->objectruanganlastfk == 215 || $registrasi->objectruanganlastfk == 411)) {
                $data = DB::connection('mongodb')
                    ->table('AsesmenFisioterapi')
                    ->where('profile.kdprofile', $this->kdProfile)
                    ->where('statusenabled', true)
                    ->where('emrpasienfk', $r['emrpasienfk'])
                    ->first();


                if (isset($r['id']) && $r['id'] != '' && $r['id'] != null && $r['id'] != 'null') {
                    $data = $data->where('id', $r['id']);
                }

                if (!isset($data['keluhanUtama']) || empty($data['keluhanUtama'])) {
                    $fallbackData = DB::connection('mongodb')
                        ->table('RingkasanKeluar')
                        ->where('profile.kdprofile', $this->kdProfile)
                        ->where('statusenabled', true)
                        ->where('registrasi.noregistrasi', $r['noregistrasi'])
                        ->first();

                    // Jika data RingkasanKeluar ditemukan dan memiliki TAKondisiSaatMasuk
                    if ($fallbackData !== null && isset($fallbackData['TAKondisiSaatMasuk'])) {
                        // Gabungkan data: pertahankan semua dari AsesmenFisioterapi, tapi ganti keluhanUtama
                        $data['keluhanUtama'] = $fallbackData['TAKondisiSaatMasuk'];
                    }
                }
                // dd($data);

                // $dataEMR3 = DB::connection('mongodb')
                //     ->table('AsesmenAwalKeperawatanPasienRawatJalanNurse')
                //     ->where('profile.kdprofile', $this->kdProfile)
                //     ->where('statusenabled', true)
                //     ->where('registrasi.noregistrasi', $r['noregistrasi']);

                // // if ($collection == 'JadwalKunjunganRehabDanFisio') {
                // $dataEMR3 = $dataEMR3->orderBy('created_at', 'desc');
                // // }

                // // $dataEMR3 = $dataEMR3->first();
                // if($dataEMR3 == null){
                //     // $dataEMR3 = DB::connection('mongodb')
                //     $dataEMR3 = DB::connection('mongodb')
                //         ->table('RingkasanKeluar')
                //         ->where('profile.kdprofile', $this->kdProfile)
                //         ->where('statusenabled', true)
                //         ->where('registrasi.noregistrasi', $r['noregistrasi']);
                // }
                // $dataEMR3 = $dataEMR3->first();
                $dataEMR3 = DB::connection('mongodb')
                    ->table('AsesmenAwalKeperawatanPasienRawatJalanNurse')
                    ->where('profile.kdprofile', $this->kdProfile)
                    ->where('statusenabled', true)
                    ->where('registrasi.noregistrasi', $r['noregistrasi'])
                    ->orderBy('created_at', 'desc')
                    ->first();

                if ($dataEMR3 === null) {
                    $dataEMR3 = DB::connection('mongodb')
                        ->table('RingkasanKeluar')
                        ->where('profile.kdprofile', $this->kdProfile)
                        ->where('statusenabled', true)
                        ->where('registrasi.noregistrasi', $r['noregistrasi'])
                        ->first();
                }

                $dataN = DB::connection('mongodb')
                    ->table('AsesmenAwalKeperawatanPasienRawatJalanNurse')
                    ->where('profile.kdprofile', $this->kdProfile)
                    ->where('statusenabled', true)
                    ->where('emrpasienfk', $dataEMR3['emrpasienfk'])
                    ->first();
                if ($dataN === null) {
                    $dataN = DB::connection('mongodb')
                        ->table('RingkasanKeluar')
                        ->where('profile.kdprofile', $this->kdProfile)
                        ->where('statusenabled', true)
                        ->where('registrasi.noregistrasi', $r['noregistrasi'])
                        ->first();
                }


                $dataEMR4 = DB::connection('mongodb')
                    ->table('CatatanPerkembanganPasienTerintegrasi')
                    ->where('profile.kdprofile', $this->kdProfile)
                    ->where('statusenabled', true)
                    ->where('registrasi.noregistrasi', $r['noregistrasi']);

                // if ($collection == 'JadwalKunjunganRehabDanFisio') {
                $dataEMR4 = $dataEMR4->orderBy('created_at', 'desc');
                // }

                $dataEMR4 = $dataEMR4->first();

                if ($dataEMR4 != null) {
                    $dataY = DB::connection('mongodb')
                        ->table('CPPTDetail')
                        // ->where('profile.kdprofile', $this->kdProfile)
                        // ->where('statusenabled', true)
                        ->where('flag', 'perawat')
                        // ->where('ruangan', 'POLI FISIOTERAPI')
                        ->where('emrpasienfk', $dataEMR4['emrpasienfk']);

                    $dataY = $dataY->first();
                }
                // dd($data);
                $data['riwayatkeluar'] = null;
                $data['TADiagnosisPrimer'] = null;
                $data['TAKondisiSaatMasuk'] = $data['keluhanUtama'];
                // $data['TAKondisiSaatMasuk'] = isset($data['TAKondisiSaatMasuk']) ? $data['TAKondisiSaatMasuk'] : $data['keluhanUtama'];
                // if($data['TAKondisiSaatMasuk'] != null){
                //     $data['TAKondisiSaatMasuk'] = $data['TAKondisiSaatMasuk'];
                // }else{
                //     $data['TAKondisiSaatMasuk'] = $data['keluhanUtama'];
                // }
                // $data['anamnesis'] = $dataEMR4 != null ? $dataY['S'] : null ;
                $data['anamnesis'] = $data['keluhanUtama'];
                $data['tekananDarah'] = isset($dataN['tekananDarahObgyn']) ? $dataN['tekananDarahObgyn'] : $dataN['tekananDarah'];
                $data['nadi'] = isset($dataN['nadiObgyn']) ? $dataN['nadiObgyn'] : $dataN['nadi'];
                $data['nafas'] = isset($dataN['nafasObgyn']) ? $dataN['nafasObgyn'] : $dataN['nafas'];
                $data['celcius'] = isset($dataN['celciusObgyn']) ? $dataN['celciusObgyn'] : $dataN['celcius'];
                $data['gcse'] = $dataN['gcse'];
                $data['gcsv'] = $dataN['gcsv'];
                $data['gcsm'] = $dataN['gcsm'];
                $data['riwayatkeluar'] = 'Membaik';
                $data['registrasi']['dokter'] = 'dr. COKORDA GDE BAYU BASKARA PUTRA, Sp.KFR';
                $data['intruksi'] = !empty($data['intervensi']) ? $data['intervensi'] : (!empty($dataY) ? $dataY['P'] : '-');
                $data['pemeriksaanfisik'] = !empty($data['pemeriksaanFisik']) ? $data['pemeriksaanFisik'] : (!empty($dataY) ? $dataY['O'] : '-');
                $data['diagnosisFisioterapi'] = !empty($data['diagnosisFisioterapi']) ? $data['diagnosisFisioterapi'] : (!empty($dataY) ? $dataY['A'] : '-');

                if (isset($responseSEP)) {
                    $data['dpjpcadangan'] = $responseSEP->dpjp->nmDPJP;
                } else {
                    $data['dpjpcadangan'] = '-';
                }
                $data['kesanUmum'] = 1;

            } else {
                // dd('Halo di sini');

                if ($registrasi->objectruanganlastfk != 322) {
                    $dataEMR = DB::connection('mongodb')
                        ->table($collection)
                        ->where('profile.kdprofile', $this->kdProfile)
                        ->where('statusenabled', true)
                        ->where('registrasi.noregistrasi', $r['noregistrasi'])
                        ->whereNotNull('dpjpUtama');
                } else {
                    $dataEMR = DB::connection('mongodb')
                        ->table($collection)
                        ->where('profile.kdprofile', $this->kdProfile)
                        ->where('statusenabled', true)
                        ->where('registrasi.noregistrasi', $r['noregistrasi']);
                }

                // ->where('registrasi.objecruanganfk', $registrasi->objectruanganlastfk);

                $dataEMR = $dataEMR->orderBy('registrasi.tglregistrasi', 'asc');

                $dataEMR = $dataEMR->first();

                if (!empty($dataEMR))
                    $r['emrpasienfk'] = $dataEMR['emrpasienfk'];

                $data = DB::connection('mongodb')
                    ->table($collection)
                    ->where('emrpasienfk', $r['emrpasienfk']);


                if (isset($r['id']) && $r['id'] != '' && $r['id'] != null && $r['id'] != 'null') {
                    $data = $data->where('id', $r['id']);
                }

                $data = $data->first();

                if ($data != null) {

                    if ($data['TADiagnosisPrimer'] == '') {
                        $dataEMRx = DB::connection('mongodb')
                            ->table('AsesmenMedisRawatJalan')
                            ->where('profile.kdprofile', $this->kdProfile)
                            ->where('statusenabled', true)
                            ->where('registrasi.noregistrasi', $r['noregistrasi']);

                        $dataEMRx = $dataEMRx->orderBy('created_at', 'asc');

                        $dataEMRx = $dataEMRx->first();

                        if (!empty($dataEMRx))
                            $r['emrpasienfk'] = $dataEMRx['emrpasienfk'];

                        $datay = DB::connection('mongodb')
                            ->table('AsesmenMedisRawatJalan')
                            ->where('emrpasienfk', $r['emrpasienfk']);


                        if (isset($r['id']) && $r['id'] != '' && $r['id'] != null && $r['id'] != 'null') {
                            $datay = $datay->where('id', $r['id']);
                        }

                        $datay = $datay->first();

                        if ($datay != null) {
                            // $data['TADiagnosisPrimer'] = $datay['diagnosaIcd10'];
                            $data['TADiagnosisPrimer'] = $datay['TADiagnosa'];
                        } else {
                            $dataEMRd = DB::connection('mongodb')
                                ->table('CPPTDetail')
                                ->where('nocmfk', $registrasi->nocmfk)
                                ->where('created_at', '>=', date('Y-m-d', strtotime($registrasi->tglregistrasi)) . ' 00:00:00')
                                ->where('created_at', '<=', date('Y-m-d', strtotime($registrasi->tglregistrasi)) . ' 23:59:59')
                                ->where('flag', 'dokter')
                                ->where('statusenabled', true);

                            $dataEMRd = $dataEMRd->orderBy('created_at', 'asc');

                            $dataEMRd = $dataEMRd->first();


                            if (!empty($dataEMRd))
                                $r['emrpasienfk'] = $dataEMRd['emrpasienfk'];

                            $datae = DB::connection('mongodb')
                                ->table('CPPTDetail')
                                ->where('emrpasienfk', $r['emrpasienfk'])
                                ->where('flag', 'dokter');


                            if (isset($r['id']) && $r['id'] != '' && $r['id'] != null && $r['id'] != 'null') {
                                $datae = $datae->where('id', $r['id']);
                            }

                            $datae = $datae->first();

                            if ($datae != null) {
                                $data['TADiagnosisPrimer'] = $datae['A'];
                                $data['intruksi'] = $datae['P'];
                            }
                        }
                    }

                    if ($registrasi->objectruanganlastfk == 245) {
                        $dataEMRa = DB::connection('mongodb')
                            ->table('CatatanKegiatanRadioterapi')
                            ->where('profile.kdprofile', $this->kdProfile)
                            ->where('statusenabled', true)
                            ->where('registrasi.noregistrasi', $r['noregistrasi'])
                            ->whereNotNull('telkananDarah');

                        $dataEMRa = $dataEMRa->orderBy('created_at', 'asc');

                        $dataEMRa = $dataEMRa->first();

                        if (!empty($dataEMRa))
                            $r['emrpasienfk'] = $dataEMRa['emrpasienfk'];

                        $datab = DB::connection('mongodb')
                            ->table('CatatanKegiatanRadioterapi')
                            ->where('emrpasienfk', $r['emrpasienfk']);


                        if (isset($r['id']) && $r['id'] != '' && $r['id'] != null && $r['id'] != 'null') {
                            $datab = $datab->where('id', $r['id']);
                        }

                        $datab = $datab->first();


                        $dataEMRb = DB::connection('mongodb')
                            ->table('CatatanKegiatanRadioterapi')
                            ->where('profile.kdprofile', $this->kdProfile)
                            ->where('statusenabled', true)
                            ->where('registrasi.noregistrasi', $r['noregistrasi']);

                        $dataEMRb = $dataEMRb->orderBy('created_at', 'desc');

                        $dataEMRb = $dataEMRb->first();

                        if (!empty($dataEMRb))
                            $r['emrpasienfk'] = $dataEMRb['emrpasienfk'];

                        $datac = DB::connection('mongodb')
                            ->table('CatatanKegiatanRadioterapi')
                            ->where('emrpasienfk', $r['emrpasienfk']);


                        if (isset($r['id']) && $r['id'] != '' && $r['id'] != null && $r['id'] != 'null') {
                            $datac = $datac->where('id', $r['id']);
                        }

                        $datac = $datac->first();

                        $data['tekananDarah'] = !empty($data['tekananDarah']) && $data['tekananDarah'] != '-' ? $data['tekananDarah'] : (!empty($datab['tekananDarah']) ? $datab['tekananDarah'] : '-');
                        $data['nadi'] = !empty($data['nadi']) && $data['nadi'] != '-' ? $data['nadi'] : (!empty($datab['pr']) ? $datab['pr'] : (!empty($datab['nadi']) ? $datab['nadi'] : '-'));
                        $data['nafas'] = !empty($data['nafas']) && $data['nafas'] != '-' ? $data['nafas'] : (!empty($datab['rr']) ? $datab['rr'] : (!empty($datab['nafas']) ? $datab['nafas'] : '-'));
                        $data['celcius'] = !empty($data['celcius']) && $data['celcius'] != '-' ? $data['celcius'] : (!empty($datab['suhu']) ? $datab['suhu'] : (!empty($datab['celcius']) ? $datab['celcius'] : '-'));
                        $data['gcse'] = !empty($data['gcse']) && $data['gcse'] != '-' ? $data['gcse'] : (!empty($datab['gcse']) ? $datab['gcse'] : '-');
                        $data['gcsv'] = !empty($data['gcsv']) && $data['gcsv'] != '-' ? $data['gcsv'] : (!empty($datab['gcsv']) ? $datab['gcsv'] : '-');
                        $data['gcsm'] = !empty($data['gcsm']) && $data['gcsm'] != '-' ? $data['gcsm'] : (!empty($datab['gcsm']) ? $datab['gcsm'] : '-');
                        $data['intruksi'] = !empty($data['intruksi']) && $data['intruksi'] != '-' ? $data['intruksi'] : (!empty($data['intruksi']) ? ($data['intruksi'] != '-' ? $data['intruksi'] : $datac['details'][0]['tindakan']) : $datac['details'][0]['tindakan']);
                    }
                }

                $data['diagnosisFisioterapi'] = null;
                $data['keluhanUtama'] = !empty($data['TAKondisiSaatMasuk']) ? $data['TAKondisiSaatMasuk'] : null;
                if (isset($responseSEP)) {
                    $data['dpjpcadangan'] = $responseSEP->dpjp->nmDPJP;
                } else {
                    $data['dpjpcadangan'] = '-';
                }


                if ($dataEMR == null) {
                    $dataEMR = DB::connection('mongodb')
                        ->table('CPPTDetail')
                        ->where('nocmfk', $registrasi->nocmfk)
                        ->where('created_at', '>=', date('Y-m-d', strtotime($registrasi->tglregistrasi)) . ' 00:00:00')
                        ->where('created_at', '<=', date('Y-m-d', strtotime($registrasi->tglregistrasi)) . ' 23:59:59')
                        ->where('statusenabled', true);

                    if ($registrasi->objectruanganlastfk == 215 || $registrasi->objectruanganlastfk == 411) {
                        $dataEMR = $dataEMR->where('flag', 'perawat');
                    } else {
                        $dataEMR = $dataEMR->where('flag', 'dokter');
                    }

                    $dataEMR = $dataEMR->orderBy('created_at', 'asc');

                    $dataEMR = $dataEMR->first();


                    if (!empty($dataEMR))
                        $r['emrpasienfk'] = $dataEMR['emrpasienfk'];

                    $data = DB::connection('mongodb')
                        ->table('CPPTDetail')
                        ->where('emrpasienfk', $r['emrpasienfk']);

                    if ($registrasi->objectruanganlastfk == 215 || $registrasi->objectruanganlastfk == 411) {
                        $data = $data->where('flag', 'perawat');
                    } else {
                        $data = $data->where('flag', 'dokter');
                    }

                    if (isset($r['id']) && $r['id'] != '' && $r['id'] != null && $r['id'] != 'null') {
                        $data = $data->where('id', $r['id']);
                    }

                    $data = $data->first();

                    $dataN = DB::connection('mongodb')
                        ->table('CatatanPerkembanganPasienTerintegrasi')
                        ->where('profile.kdprofile', $this->kdProfile)
                        ->where('statusenabled', true)
                        ->where('emrpasienfk', $data['emrpasienfk']);

                    $dataN = $dataN->first();

                    // dd($dataN);

                    $pasien = Pasien::where('id', '=', $registrasi->nocmfk)->first();
                    $dokter = DB::table('pasiendaftar_t as pd')
                        ->join('pegawai_m as pg', 'pg.id', '=', 'pd.objectpegawaifk')
                        ->where('noregistrasi', '=', $registrasi->noregistrasi)
                        ->first();


                    $data['diagnosisFisioterapi'] = null;
                    $data['keluhanUtama'] = null;
                    $data['TADiagnosisPrimer'] = $data['A'];
                    $data['registrasi']['namaruangan'] = '-';
                    $data['registrasi']['dokter'] = $dokter->namalengkap;
                    $data['registrasi']['tglregistrasi'] = $registrasi->tglregistrasi;
                    $data['registrasi']['tglpulang'] = $registrasi->tglpulang;
                    $data['pasien']['tgllahir'] = $pasien->tgllahir;
                    if ($pasien->objectjeniskelaminfk == 1) {
                        $data['pasien']['jeniskelamin'] = 'Laki-Laki';
                    } else {
                        $data['pasien']['jeniskelamin'] = 'Perempuan';
                    }
                    $data['pasien']['namapasien'] = $pasien->namapasien;
                    $data['pasien']['nocm'] = $pasien->nocm;
                    $data['riwayatkeluar'] = 'Membaik';
                    $data['anamnesis'] = $data['S'];
                    $data['pemeriksaanfisik'] = $data['O'];
                    $data['intruksi'] = $data['P'];
                    $data['tekananDarah'] = $dataN['tekananDarah'];
                    $data['nadi'] = $dataN['nadi'];
                    $data['nafas'] = $dataN['nafas'];
                    $data['celcius'] = $dataN['celcius'];
                    $data['gcse'] = !empty($dataN['gcse']) ? $dataN['gcse'] : '4';
                    $data['gcsv'] = !empty($dataN['gcsv']) ? $dataN['gcsv'] : '5';
                    $data['gcsm'] = !empty($dataN['gcsm']) ? $dataN['gcsm'] : '6';


                    if (isset($responseSEP)) {
                        $data['dpjpcadangan'] = $responseSEP->dpjp->nmDPJP;
                    } else {
                        $data['dpjpcadangan'] = '-';
                    }


                }
                $dokter = DB::table('pasiendaftar_t as pd')
                    ->join('pegawai_m as pg', 'pg.id', '=', 'pd.objectpegawaifk')
                    ->where('noregistrasi', '=', $registrasi->noregistrasi)
                    ->first();
                if (!empty($dokter)) {
                    $data['registrasi']['dokter'] = $dokter->namalengkap;
                }
                $data['kesanUmum'] = 1;

            }
        } else if ($collection == 'JadwalKunjunganRehabDanFisio') {
            if (isset($r['noregistrasi'])) {
                $dataEMR = DB::connection('mongodb')
                    ->table($collection)
                    ->where('profile.kdprofile', $this->kdProfile)
                    ->where('statusenabled', true)
                    ->where('registrasi.tglregistrasi', '<=', date('Y-m-d', strtotime($registrasi->tglregistrasi)) . ' 23:59:59')
                    ->where('pasien.nocmfk', $registrasi->nocmfk);

                // if ($collection == 'JadwalKunjunganRehabDanFisio') {
                $dataEMR = $dataEMR->orderBy('created_at', 'desc');
                // }

                $dataEMR = $dataEMR->first();

                if (!empty($dataEMR)) {
                    $r['emrpasienfk'] = $dataEMR['emrpasienfk'];
                    $r['id'] = $dataEMR['id'];
                } else {
                    $r['emrpasienfk'] = null;
                }

                if ($dataEMR == null) {
                    $dataEMR = DB::connection('mongodb')
                        ->table($collection)
                        ->where('profile.kdprofile', $this->kdProfile)
                        ->where('statusenabled', true)
                        ->where('registrasi.tglregistrasi', '>', date('Y-m-d', strtotime($registrasi->tglregistrasi)) . ' 23:59:59')
                        ->where('pasien.nocmfk', $registrasi->nocmfk);

                    // if ($collection == 'JadwalKunjunganRehabDanFisio') {
                    $dataEMR = $dataEMR->orderBy('created_at', 'desc');
                    // }

                    $dataEMR = $dataEMR->first();

                    if (!empty($dataEMR)) {
                        $r['emrpasienfk'] = $dataEMR['emrpasienfk'];
                        $r['id'] = $dataEMR['id'];
                    } else {
                        $r['emrpasienfk'] = null;
                    }
                }



                $data = DB::connection('mongodb')
                    ->table($collection)
                    ->where('profile.kdprofile', $this->kdProfile)
                    ->where('statusenabled', true)
                    ->where('emrpasienfk', $r['emrpasienfk']);


                if (isset($r['id']) && $r['id'] != '' && $r['id'] != null && $r['id'] != 'null') {
                    $data = $data->where('id', $r['id']);
                }

                $data = $data->first();


                $data['registrasi']['tglregistrasi'] = $registrasi->tglregistrasi;


                // dd($registrasi);

            }
        } else if ($collection == 'PenjadwalanRadioterapi') {
            if (isset($r['noregistrasi'])) {

                if (date('Y-m-d', strtotime($registrasi->tglregistrasi)) >= '2025-01-23') {
                    $dataEMR = DB::connection('mongodb')
                        ->table($collection)
                        ->where('profile.kdprofile', $this->kdProfile)
                        ->where('statusenabled', true)
                        // ->where('registrasi.tglregistrasi', '<=', date('Y-m-d', strtotime($registrasi->tglregistrasi)).' 23:59:59')
                        ->where('pasien.nocmfk', $registrasi->nocmfk)
                        ->where('created_at', '<=', date('Y-m-d', strtotime($registrasi->tglregistrasi)) . ' 23:59:59');

                    // if ($collection == 'JadwalKunjunganRehabDanFisio') {
                    $dataEMR = $dataEMR->orderBy('created_at', 'desc');
                    // }

                    $dataEMR = $dataEMR->first();

                    // dd($dataEMR);

                    if (!empty($dataEMR)) {
                        $r['emrpasienfk'] = $dataEMR['emrpasienfk'];
                        $r['id'] = $dataEMR['id'];
                    } else {
                        $r['emrpasienfk'] = null;
                    }

                    $data = DB::connection('mongodb')
                        ->table($collection)
                        ->where('profile.kdprofile', $this->kdProfile)
                        ->where('statusenabled', true)
                        ->where('emrpasienfk', $r['emrpasienfk']);


                    if (isset($r['id']) && $r['id'] != '' && $r['id'] != null && $r['id'] != 'null') {
                        $data = $data->where('id', $r['id']);
                    }

                    $data = $data->first();

                    $data['registrasi']['tglregistrasi'] = $registrasi->tglregistrasi;
                }

                // dd($registrasi);

            }
        } else if ($collection == 'LembarHasilTindakanUjiFungsiProsedurKFR') {
            if (isset($r['noregistrasi'])) {
                $dataEMR = DB::connection('mongodb')
                    ->table($collection)
                    ->where('profile.kdprofile', $this->kdProfile)
                    ->where('statusenabled', true)
                    ->where('registrasi.tglregistrasi', '<=', date('Y-m-d', strtotime($registrasi->tglregistrasi)) . ' 23:59:59')
                    ->where('pasien.nocmfk', $registrasi->nocmfk);

                // if ($collection == 'JadwalKunjunganRehabDanFisio') {
                $dataEMR = $dataEMR->orderBy('created_at', 'desc');
                // }

                $dataEMR = $dataEMR->first();

                if (!empty($dataEMR)) {
                    $r['emrpasienfk'] = $dataEMR['emrpasienfk'];
                    $r['id'] = $dataEMR['id'];
                } else {
                    $r['emrpasienfk'] = null;
                }
            }

            // dd($r['emrpasienfk']);

            $data = DB::connection('mongodb')
                ->table($collection)
                ->where('profile.kdprofile', $this->kdProfile)
                ->where('statusenabled', true)
                ->where('emrpasienfk', $r['emrpasienfk']);


            if (isset($r['id']) && $r['id'] != '' && $r['id'] != null && $r['id'] != 'null') {
                $data = $data->where('id', $r['id']);
            }

            $data = $data->first();
        } else if ($collection == 'LembarHasilTindakanUjiFungsiRehabilitasiMedik') {
            if (isset($r['noregistrasi'])) {

                $dataEMR = DB::connection('mongodb')
                    ->table('LembarHasilTindakanUjiFungsiProsedurKFR')
                    ->where('profile.kdprofile', $this->kdProfile)
                    ->where('statusenabled', true)
                    ->where('registrasi.tglregistrasi', '<=', date('Y-m-d', strtotime($registrasi->tglregistrasi)) . ' 23:59:59')
                    ->where('pasien.nocmfk', $registrasi->nocmfk);

                // if ($collection == 'JadwalKunjunganRehabDanFisio') {
                $dataEMR = $dataEMR->orderBy('created_at', 'desc');
                // }

                $dataEMR = $dataEMR->first();

                if ($dataEMR == null) {
                    $dataEMR = DB::connection('mongodb')
                        ->table($collection)
                        ->where('profile.kdprofile', $this->kdProfile)
                        ->where('statusenabled', true)
                        ->where('registrasi.tglregistrasi', '<=', date('Y-m-d', strtotime($registrasi->tglregistrasi)) . ' 23:59:59')
                        ->where('pasien.nocmfk', $registrasi->nocmfk);

                    // if ($collection == 'JadwalKunjunganRehabDanFisio') {
                    $dataEMR = $dataEMR->orderBy('created_at', 'desc');
                    // }

                    $dataEMR = $dataEMR->first();

                    if (!empty($dataEMR)) {
                        $r['emrpasienfk'] = $dataEMR['emrpasienfk'];
                        $r['id'] = $dataEMR['id'];
                    } else {
                        $r['emrpasienfk'] = null;
                    }

                    $data = DB::connection('mongodb')
                        ->table($collection)
                        ->where('profile.kdprofile', $this->kdProfile)
                        ->where('statusenabled', true)
                        ->where('emrpasienfk', $r['emrpasienfk']);


                    if (isset($r['id']) && $r['id'] != '' && $r['id'] != null && $r['id'] != 'null') {
                        $data = $data->where('id', $r['id']);
                    }

                    $data = $data->first();
                }


            }

            // dd($r['emrpasienfk']);
        } else if ($collection == 'FormulirKedokteranFisikDanRehabilitasi') {
            if (isset($r['noregistrasi'])) {
                $dataEMR = DB::connection('mongodb')
                    ->table($collection)
                    ->where('profile.kdprofile', $this->kdProfile)
                    ->where('statusenabled', true)
                    ->where('registrasi.tglregistrasi', '<=', date('Y-m-d', strtotime($registrasi->tglregistrasi)) . ' 23:59:59')
                    ->where('pasien.nocmfk', $registrasi->nocmfk);

                // if ($collection == 'JadwalKunjunganRehabDanFisio') {
                $dataEMR = $dataEMR->orderBy('created_at', 'desc');
                // }

                $dataEMR = $dataEMR->first();

                // dd($dataEMR);


                if (!empty($dataEMR)) {
                    $r['emrpasienfk'] = $dataEMR['emrpasienfk'];
                    $r['id'] = $dataEMR['id'];
                } else {
                    $r['emrpasienfk'] = null;
                }
            }

            // dd($r['emrpasienfk']);

            $data = DB::connection('mongodb')
                ->table($collection)
                ->where('profile.kdprofile', $this->kdProfile)
                ->where('statusenabled', true)
                ->where('emrpasienfk', $r['emrpasienfk']);


            if (isset($r['id']) && $r['id'] != '' && $r['id'] != null && $r['id'] != 'null') {
                $data = $data->where('id', $r['id']);
            }

            $data = $data->first();
        } else if ($collection == 'laporanOperasi') {
            if (isset($r['noregistrasi'])) {
                $data = DB::connection('mongodb')
                    ->table($collection)
                    ->where('profile.kdprofile', $this->kdProfile)
                    ->where('statusenabled', true)
                    ->whereNull('namatemplate')
                    ->where('registrasi.noregistrasi', $r['noregistrasi'])
                    ->orderBy('created_at', 'asc')
                    ->get();

                foreach ($data as $key => $dat) {
                    $halaman = $key + 1;

                    $dat['fotopendukung'] = EmrDokumen::select('file', 'objectberkaspasien', 'nama')
                        ->where('objectberkaspasien', 50)
                        ->where('noregistrasi', $r['noregistrasi'])
                        ->where('halaman', $halaman)
                        ->get();

                    $data[$key] = $dat;
                }

            } else {
                $data = DB::connection('mongodb')
                    ->table($collection)
                    ->where('profile.kdprofile', $this->kdProfile)
                    ->where('statusenabled', true)
                    ->whereNull('namatemplate')
                    ->where('emrpasienfk', $r['emrpasienfk'])
                    ->orderBy('created_at', 'asc')
                    ->get();
                foreach ($data as $key => $dt) {
                    $halaman = $key + 1;

                    $dt['fotopendukung'] = EmrDokumen::select('file', 'objectberkaspasien', 'nama')
                        ->where('objectberkaspasien', 50)
                        ->where('noregistrasi', $data[0]['registrasi']['noregistrasi'])
                        ->where('halaman', $halaman)
                        ->get();

                    $data[$key] = $dt;
                }
            }

            if (count($data) == 0) {
                echo '
                    <script language="javascript">
                        window.alert("Tidak ada data.");
                        window.close()
                    </script>';
                die;
            }

            $dataQR2 = $this->generateDataQR($data[0], $collection, 'dokumen.signature', ['a' => '0'], ['dpjp' => isset($data['dokterBedah']['label']) ? $data['dokterBedah']['label'] : ''], 5, 130);
            $profile = $this->profile();
            $blade = 'report.emr.' . $collection;
            $pdf = App::make('dompdf.wrapper');
            $pdf->setPaper('A4', 'portrait');
            $pdf->loadView($blade, [
                'profile' => $profile,
                'pasien' => $data[0]['pasien'],
                'qrcode' => $dataQR2['qrcode_base64'],
                'registrasi' => $data[0]['registrasi'],
                'data' => $data
            ]);
            return $pdf;
        } else {
            if (isset($r['noregistrasi'])) {
                $dataEMR = DB::connection('mongodb')
                    ->table($collection)
                    ->where('profile.kdprofile', $this->kdProfile)
                    ->where('statusenabled', true)
                    ->where('registrasi.noregistrasi', $r['noregistrasi']);

                // if ($collection == 'JadwalKunjunganRehabDanFisio') {
                $dataEMR = $dataEMR->orderBy('created_at', 'desc');
                // }

                $dataEMR = $dataEMR->first();

                if (!empty($dataEMR))
                    $r['emrpasienfk'] = $dataEMR['emrpasienfk'];
                else
                    $r['emrpasienfk'] = null;
            }

            // dd($r['emrpasienfk']);

            if ($r['emrpasienfk'] != null) {
                $data = DB::connection('mongodb')
                    ->table($collection)
                    ->where('profile.kdprofile', $this->kdProfile)
                    ->where('statusenabled', true)
                    ->where('emrpasienfk', $r['emrpasienfk']);


                if (isset($r['id']) && $r['id'] != '' && $r['id'] != null && $r['id'] != 'null') {
                    $data = $data->where('id', $r['id']);
                }

                $data = $data->first();

                if (isset($r['emrid']) && $r['emrid'] != 'null' && $r['emrid'] != null) {
                    $data = DB::connection('mongodb')
                        ->table($collection)
                        ->where('profile.kdprofile', $this->kdProfile)
                        ->where('statusenabled', true)
                        ->where('id', $r['emrid']);

                    $data = $data->first();
                }
            } else {
                $data = null;
            }
        }
        if ($collection == 'AsesmenAwalMedisGawatDarurat') {
            $data = DB::connection('mongodb')
                ->table('AsesmenAwalMedisGawatDarurat')
                ->where('profile.kdprofile', $this->kdProfile)
                ->where('statusenabled', true)
                ->where('registrasi.noregistrasi', $r['noregistrasi'])
                ->first();
            $dataDOOD = DB::table('antrianpasiendiperiksa_t as apd')
                ->join('pasiendaftar_t as pd', 'apd.noregistrasifk', '=', 'pd.norec')
                ->leftjoin('pegawai_m as dod', 'pd.objectpegawaifk_dod', '=', 'dod.id')
                ->select(
                    'dod.namalengkap as dod',
                )
                ->where('pd.noregistrasi', '=', $r['noregistrasi'])
                ->first();

            // $data['registrasi']['dokter'] = $dataDOOD['dod'];
            $data['registrasi']['dokter'] = $dataDOOD->dod;
        }

        if ($collection == 'CatatanPerkembanganPasienTerintegrasi') {
            $cppt = DB::connection('mongodb')
                ->table('CPPTDetail')
                // ->where('norec_pd', $r['norec_pd'])
                // ->where('nocmfk', $nocmfk)
                ->where('kdprofile', $this->kdProfile)
                ->where('statusenabled', true);
            if (isset($r['from']) && $r['from'] == 'CPPT') {
                $data = DB::connection('mongodb')
                    ->table($collection)
                    ->where('profile.kdprofile', $this->kdProfile)
                    ->where('statusenabled', true)
                    ->where('pasien.nocmfk', $r['nocmfk'])
                    ->first();

                if (isset($r['emrpasienfk']) && $r['emrpasienfk'] != '' && isset($r['allPeriode']) && $r['allPeriode'] == 'false') {
                    $cppt = $cppt->where('emrpasienfk', '=', $r['emrpasienfk']);
                }
                if (isset($r['norec_pd']) && $r['norec_pd'] != '' && isset($r['allPeriode']) && $r['allPeriode'] == 'false') {
                    $cppt = $cppt->where('norec_pd', '=', $r['norec_pd']);
                }
                if (isset($r['nocmfk']) && $r['nocmfk'] != '' && isset($r['allPeriode']) && $r['allPeriode'] == 'true') {
                    $cppt = $cppt->where('nocmfk', '=', $r['nocmfk']);
                }
            } else {
                if (isset($r['emrpasienfk']) && $r['emrpasienfk'] != '') {
                    $cppt = $cppt->where('emrpasienfk', '=', $r['emrpasienfk']);
                }
                if (isset($r['norec_pd']) && $r['norec_pd'] != '') {
                    $cppt = $cppt->where('norec_pd', '=', $r['norec_pd']);
                }
            }
            if (isset($r['flag']) && $r['flag'] != '') {
                $flag = '';
                switch ($r['flag']) {
                    case 'dokter':
                        $flag = 'dokter';
                        break;
                    case 'perawat':
                        $flag = 'perawat';
                        break;
                    case 'fisiotherapy':
                        $flag = 'perawat';
                        break;
                    case 'kebidanan':
                        $flag = 'perawat';
                        break;
                    default:
                        $flag = 'semua';
                        break;
                }
                if ($flag != 'semua') {
                    $cppt = $cppt->where('flag', '=', $flag);
                }
            }
            $cppt = $cppt->get();
            $data['details'] = $cppt;
            if ($data['details']->isEmpty()) {
                echo '
                    <script language="javascript">
                        window.alert("Tidak ada data.");
                        window.close()
                    </script>';
                die;
            }
        }

        if($collection == 'SuratKeteranganGawatDarurat'){
            $data = DB::connection('mongodb')
                ->table($collection)
                ->where('profile.kdprofile', $this->kdProfile)
                ->where('statusenabled', true)
                ->where('registrasi.noregistrasi', $r['noregistrasi'])
                ->first();

            $SEP_DATE = DB::table('pemakaianasuransi_t as pa')
                ->select('pa.tglsep')
                ->where('pa.noregistrasifk', $data['registrasi']['norec_pd'])
                ->first();
            $data['tanggal'] = $SEP_DATE->tglsep;

        }
        if($collection == 'SuratPermintaanDirawat'){
            $data = DB::connection('mongodb')
                ->table($collection)
                ->where('profile.kdprofile', $this->kdProfile)
                ->where('statusenabled', true)
                ->where('registrasi.noregistrasi', $r['noregistrasi'])
                ->first();
            // dd($data);

            // $SEP_DATE = DB::table('pemakaianasuransi_t as pa')
            //     ->select('pa.tglsep')
            //     ->where('pa.noregistrasifk', $data['registrasi']['norec_pd'])
            //     ->first();
            // $data['tanggalRawatInap'] = $SEP_DATE->tglsep;
            // $data['tanggal'] = $SEP_DATE->tglsep;
            // $data['tanggalAdmission'] = $SEP_DATE->tglsep;
            if($data['registrasi']['kelompokpasien'] !== 'UMUM/PRIBADI'){
                $SEP_DATE = DB::table('pemakaianasuransi_t as pa')
                    ->select('pa.tglsep')
                    ->where('pa.noregistrasifk', $data['registrasi']['norec_pd'])
                    ->first();
                $data['tanggalRawatInap'] = $SEP_DATE->tglsep;
                $data['tanggal'] = $SEP_DATE->tglsep;
                $data['tanggalAdmission'] = $SEP_DATE->tglsep;
            }

        }
        if (empty($data)) {
            echo '
            <script language="javascript">
                window.alert("Tidak ada data.");
                window.close()
            </script>';
            die;
        }
        // if($collection = 'AsesmenAwalMedisGawatDarurat'){
        //     $dataAsmedIGD = DB::connection('mongodb')
        //         ->table('AsesmenAwalMedisGawatDarurat')
        //         ->where('profile.kdprofile', $this->kdProfile)
        //         ->where('statusenabled', true)
        //         ->where('registrasi.noregistrasi', $r['noregistrasi'])
        //         ->first();
        //     $dataDOOD = DB::table('antrianpasiendiperiksa_t as apd')
        //     ->join('pasiendaftar_t as pd', 'apd.noregistrasifk', '=', 'pd.norec')
        //     ->leftjoin('pegawai_m as dod', 'pd.objectpegawaifk_dod', '=', 'dod.id')
        //     ->select(
        //         'dod.namalengkap as dod',
        //     )
        //     ->where('pd.noregistrasi', '=', $r['noregistrasi'])
        //     ->first();

        //     // $data['registrasi']['dokter'] = $dataDOOD['dod'];
        //     $data['registrasi']['dokter'] = $dataDOOD->dod;
        // }

        // BEST PRACTICE nya masuk DB, shorted hasil ErrorCorrection buat QRCode nya,
        // Simple IO ga support 1000 char

        $dataQR = [
            "nocm" => isset($data['pasien']['nocm']) ? $data['pasien']['nocm'] : '',
            // "namaruangan" => isset($data['registrasi']['namaruangan']) ? $data['registrasi']['namaruangan'] : '',
            "namaruangan" => $data['registrasi']['namaruangan'],
            "dpjp" => $data['registrasi']['dokter'],
            // "tglberkunjung" => isset(date($data['registrasi']['tglregistrasi'])) ? date("Y-m-d", strtotime($data['registrasi']['tglregistrasi'])) : '',
            "tglberkunjung" => date("Y-m-d", strtotime($data['registrasi']['tglregistrasi'])),
            "tglpulang" => date("Y-m-d", strtotime($data['registrasi']['tglpulang'])),
            // "tglpulang" => isset(($data['registrasi']['tglpulang'])) ? date("Y-m-d", strtotime($data['registrasi']['tglpulang'])) : '',
            "created_at" => date("Y-m-d", strtotime($data['created_at'])),
            // "created_at" => isset(($data['created_at'])) ? date("Y-m-d", strtotime($data['created_at'])) : '',
            "type" => $collection,
        ];
        // $dataQR = [
        //     "nocm" => isset($data['pasien']['nocm']) ? $data['pasien']['nocm'] : '',
        //     "namaruangan" => isset($data['registrasi']['namaruangan']) ? $data['registrasi']['namaruangan'] : '',
        //     "dpjp" => $data['registrasi']['dokter'],
        //     "tglberkunjung" => isset($data['registrasi']['tglregistrasi']) ? date("Y-m-d", strtotime($data['registrasi']['tglregistrasi'])) : '',
        //     "tglpulang" => isset($data['registrasi']['tglpulang']) ? date("Y-m-d", strtotime($data['registrasi']['tglpulang'])) : '',
        //     "created_at" => isset($data['created_at']) ? date("Y-m-d", strtotime($data['created_at'])) : '',
        //     "type" => $collection,
        // ];

        $stringQR = $collection . ';' . $data['_id'];
        // $stringQR = $collection . ';' . (isset($data['_id']) ? $data['_id'] : '');


        $encryptQR = base64_encode($stringQR);
        // return $encryptQR;

        // Prevent link to encoded
        $iswna = ($r !== null && isset($r['wna'])) ? $r['wna'] : false;
        $pasien = $dataQR;
        $pasien['tte'] = route('dokumen.signature') . '?key=' . $encryptQR . '&a=' . $iswna;

        $pasien['tglkontrol'] = isset($data['tglperjanjian']) ? date('d', strtotime($data['tglperjanjian'])) . ' ' . $this->dateLocalID('F ', $data['tglperjanjian']) . ' ' . date('Y', strtotime($data['tglperjanjian'])) : '';
        $pasien['dateNow'] = date('d') . ' ' . $this->dateLocalID('F ', date('Y-m-d')) . ' ' . date('Y');
        $pasien["tgllahir"] = $data['pasien']['tgllahir'];
        // $pasien["tgllahir"] = isset($data['pasien']['tgllahir']) ? $data['pasien']['tgllahir'] : '';
        $pasien["jeniskelamin"] = $data['pasien']['jeniskelamin'];
        // $pasien["jeniskelamin"] = isset($data['pasien']['jeniskelamin']) ? $data['pasien']['jeniskelamin'] : '';
        $pasien['namapasien'] = $data['pasien']['namapasien'];
        // $pasien['namapasien'] = isset($data['pasien']['namapasien']) ? $data['pasien']['namapasien'] : '';
        // return $pasien;
        $sonograph = [];
        $qrcodeSono = null;

        if (isset($data['sonographer']) && is_array($data['sonographer']) && array_key_exists('label', $data['sonographer'])) {
            $sonograph = [
                'ttdSono' => $data['sonographer']['label'],
            ];
            $qrcodeSono = base64_encode(QrCode::format('svg')->size(75)->generate($sonograph['ttdSono']));
        } else {
            $qrcodeSono = null;
        }
        $canvasPNG = new Png($pasien['tte']);
        $canvasPNG->setMargin(5);
        $canvasPNG->setSize(130);

        $canvasPNG->setErrorCorrectionLevel(new ErrorCorrectionLevelLow);
        $writer = new PngWriter();
        $resultBarcode = $writer->write($canvasPNG);

        // Encode to base64 for display
        $qrcode = base64_encode($resultBarcode->getString());
        // return $base64QrCode;
        // return str_replace('=','',$pasien['tte']);
        // return QrCode::format('png')->generate("http://rsudbali.local/ejasdgjkadhjasgdasgjdjkasajskdhkasjdhjaskhdjkahkjadhasdkjashdasjkhdkjashdkjashdjkashdkjashdjkashdkjahsjdkhaskjdhasjkdhaskjdhasjkhdkjashdjaskhdasgdasgas");
        // $qrcode = base64_encode(QrCode::format('svg')->size(75)->generate($pasien['tte']));
        // return $pasien;
        $identitas = $pasien;
        $profile = $this->profile();
        $header_use = "head-emr";
        if (\View::exists('report.emr.' . $collection)) {
            $blade = 'report.emr.' . $collection;
        } else {
            $blade = 'report.emr.FormulirDokterPenanggungJawabPelayananDef';
        }
        $listEMR_Get_ExternalData = [
            'SuratKeteranganSakit',
            'SuratKeteranganSehat',
            'SuratKeteranganDiagnosa',
            'SuratKeteranganSehatJiwa',
            'SuratKeteranganNarkoba',
            'SuratKeteranganHasilPemeriksaanMata',
            'HasilPemeriksaanMCU',
            'MedicalReport',
            'HasilPemeriksaanSpirometri',
            'SuratKeteranganRekomendasiPsikologi',
            'SuratKeteranganDisabilitas',
            'HasilPemeriksaanBodyplethy',
            'HasilPemeriksaanDLCOBodyplethy',
            'SuratKeteranganDalamMasaPerawatan',
            'SuratKeteranganGawatDarurat',
            'SuratPermintaanDirawat',
            'SuratKeteranganDirawat',
            'TransThoracaEchoBayi',
            'OnBoardMeeting',
            'LembarHasilTindakanUjiFungsiProsedurKFR',
            'SuratKeteranganSehatParu',
            'FormulirBuktiPelayananCanggih',
            'FormulirCatataPemberianObatKemoterapi'
        ];

        $cekWargaNegaraWNA = ($r !== null && isset($r['wna'])) ? $r['wna'] : false;
        if ($r['pdf'] == 'true') {

            if ($collection == "CatatanInformasidanEdukasiTerintegrasi") {
                $data['resmetode'] = $this->getMetodeEdukasi();
            } else if (in_array($collection, $listEMR_Get_ExternalData)) {
                $reportDisplay = $this->legalityCheck($data, 1);
                $reportDisplay2 = $this->legalityCheck($data, 2);

                if (isset($reportDisplay)) {
                    $data['nip'] = DB::table('pegawai_m')
                        ->select('nip')
                        ->where('namaexternal', 'DOKTER')
                        ->where('namalengkap', $reportDisplay)
                        ->first();

                    $data['nip2'] = DB::table('pegawai_m')
                        ->select('nip')
                        ->where('namaexternal', 'DOKTER')
                        ->where('namalengkap', $reportDisplay2)
                        ->first();

                    $data['nosip'] = DB::table('pegawai_m')
                        ->select('nosip')
                        ->where('namaexternal', 'DOKTER')
                        ->where('namalengkap', $reportDisplay)
                        ->first();

                    $data['noskp'] = DB::table('pegawai_m')
                        ->select('noskp')
                        ->where('namaexternal', 'DOKTER')
                        ->where('namalengkap', $reportDisplay)
                        ->first();

                    $data['nosippk'] = DB::table('pegawai_m')
                        ->select('nosippk')
                        ->where('namaexternal', 'PSIKOLOG KLINIS AHLI PERTAMA')
                        ->where('namalengkap', $reportDisplay)
                        ->first();

                    $data['nipppk'] = DB::table('pegawai_m')
                        ->select('nipppk')
                        ->where('namaexternal', 'DOKTER')
                        ->where('namalengkap', $reportDisplay)
                        ->first();
                } else {
                    $data['nip'] = null;
                    $data['nip2'] = null;
                    $data['nosip'] = null;
                    $data['noskp'] = null;
                    $data['nosippk'] = null;
                    $data['nipppk'] = null;
                }
            }

            if (isset($r['r_penunjang'])) {
                $data['r_penunjang'] = $r['r_penunjang'];
            }

            if (isset($data) && !isset($data['TAKondisiSaatMasuk']) || $data['TAKondisiSaatMasuk'] == "") {
                $data['TAKondisiSaatMasuk'] = $data['anamnesis'] ?? '';
            }

            if ($collection == 'PencampuranSediaanKemoterapi') {
                $pdf = App::make('dompdf.wrapper');
                $pdf->setPaper([0, 0, 700, 1000], 'landscape');
                $pdf->loadView($blade, [
                    'cekWargaNegaraWNA' => $cekWargaNegaraWNA,
                    'profile' => $profile,
                    'identitas' => $pasien,
                    'pasien' => $pasien,
                    'tte' => $qrcode,
                    'registrasi' => $data['registrasi'],
                    'data' => $data,
                    'header_use' => $header_use,
                    'ttdSono' => $qrcodeSono
                ]);
                return $pdf->stream();
            } else if ($collection == 'SuratPermintaanDirawat') {
                $qrcodeAdmission = $this->generateDataQR(
                    $data,
                    $collection,
                    'dokumen.signature',
                    ['a' => '0'],
                    ['admission' => isset($data['user_input']) ? $data['user_input']['namalengkap'] : ''],
                    5,
                    130
                );

                $data['tgllahir'] = DB::table('pasien_m')
                    ->select('tgllahir')
                    ->where('nocm', $data['pasien']['nocm'])
                    ->first();

                // $data['nosep'] = DB::table('pemakaianasuransi_t')
                // ->select('nosep')
                // ->where('noregistrasifk',  $data['registrasi']['norec_pd'])
                // ->first();

                $pdf = App::make('dompdf.wrapper');
                $pdf->setPaper('A4', 'portrait');
                $pdf->loadView($blade, [
                    'cekWargaNegaraWNA' => $cekWargaNegaraWNA,
                    'profile' => $profile,
                    'identitas' => $pasien,
                    'pasien' => $pasien,
                    'tte' => $qrcode,
                    'admission' => $qrcodeAdmission['qrcode_base64'],
                    'registrasi' => $data['registrasi'],
                    'data' => $data,
                    'header_use' => $header_use,
                    'ttdSono' => $qrcodeSono
                ]);
                return $pdf->stream();
            } else if ($collection == 'JadwalKunjunganRehabDanFisio' || $collection == 'PenjadwalanRadioterapi') {
                foreach ($data['details'] as $key => $d) {
                    $stringQR1 = $d['parafDokter']['label'];
                    $stringQR2 = $d['parafPegawai']['label'];
                    $canvasPNG = new Png($stringQR1);
                    $canvasPNG->setSize(120);
                    $canvasPNG->setErrorCorrectionLevel(new ErrorCorrectionLevelLow);
                    $canvasPNG2 = new Png($stringQR2);
                    $canvasPNG2->setSize(120);
                    $canvasPNG2->setErrorCorrectionLevel(new ErrorCorrectionLevelLow);
                    $writer = new PngWriter();
                    $resultBarcode = $writer->write($canvasPNG);
                    $qrcode1 = base64_encode($resultBarcode->getString());
                    $resultBarcode2 = $writer->write($canvasPNG2);
                    $qrcode2 = base64_encode($resultBarcode2->getString());
                    $data['details'][$key]['QR_ParafDokter'] = $qrcode1;
                    $data['details'][$key]['QR_ParafPegawai'] = $qrcode2;
                }

                $pdf = App::make('dompdf.wrapper');
                $pdf->setPaper('A4', 'portrait');
                $pdf->loadView($blade, [
                    'cekWargaNegaraWNA' => $cekWargaNegaraWNA,
                    'profile' => $profile,
                    'identitas' => $pasien,
                    'pasien' => $pasien,
                    'registrasi' => $data['registrasi'],
                    'data' => $data,
                    'header_use' => $header_use,
                ]);
                return $pdf->stream();
            } else if ($collection == 'FormulirKriteriaTrombolisis') {
                $dataQR2 = $this->generateDataQR(
                    $data,
                    'FormulirKriteriaTrombolisis',
                    'dokumen.signature',
                    ['a' => '0'],
                    ['dpjp' => isset($data['dokterPelaksana']['label']) ? $data['dokterPelaksana']['label'] : ''],
                    5,
                    130
                );

                $pdf = App::make('dompdf.wrapper');
                $pdf->setPaper('A4', 'portrait');
                $pdf->loadView($blade, [
                    'cekWargaNegaraWNA' => $cekWargaNegaraWNA,
                    'profile' => $profile,
                    'identitas' => $pasien,
                    'pasien' => $pasien,
                    'tte' => $dataQR2['qrcode_base64'],
                    'registrasi' => $data['registrasi'],
                    'data' => $data,
                    'header_use' => $header_use,
                    'ttdSono' => $qrcodeSono
                ]);
                return $pdf->stream();
            } else if ($collection == 'FormulirKriteriaMasukStrokeCorner') {
                $dataQR2 = $this->generateDataQR(
                    $data,
                    $collection,
                    'dokumen.signature',
                    ['a' => '0'],
                    ['dpjp' => isset($data['dokterPemeriksa']['label']) ? $data['dokterPemeriksa']['label'] : ''],
                    5,
                    130
                );

                $dataQR3 = $this->generateDataQR(
                    $data,
                    $collection,
                    'dokumen.signature',
                    ['a' => '0'],
                    ['dpjp' => isset($data['dokterDPJP']['label']) ? $data['dokterDPJP']['label'] : ''],
                    5,
                    130
                );

                $pdf = App::make('dompdf.wrapper');
                $pdf->setPaper('A4', 'portrait');
                $pdf->loadView($blade, [
                    'cekWargaNegaraWNA' => $cekWargaNegaraWNA,
                    'profile' => $profile,
                    'identitas' => $pasien,
                    'pasien' => $pasien,
                    'qrcode' => $dataQR2['qrcode_base64'],
                    'qrcode2' => $dataQR3['qrcode_base64'],
                    'registrasi' => $data['registrasi'],
                    'data' => $data,
                    'header_use' => $header_use,
                    'ttdSono' => $qrcodeSono
                ]);
                return $pdf->stream();
            } else if ($collection == 'RujukanPasien') {
                // dd($data);
                $dataQR2 = $this->generateDataQR(
                    $data,
                    $collection,
                    'dokumen.signature',
                    ['a' => '0'],
                    ['dpjp' => isset($data['dokterYangMerawat']['label']) ? $data['dokterYangMerawat']['label'] : ''],
                    5,
                    130
                );

                $pdf = App::make('dompdf.wrapper');
                $pdf->setPaper('A4', 'portrait');
                $pdf->loadView($blade, [
                    'cekWargaNegaraWNA' => $cekWargaNegaraWNA,
                    'profile' => $profile,
                    'identitas' => $pasien,
                    'pasien' => $pasien,
                    'qrcode' => $dataQR2['qrcode_base64'],
                    'registrasi' => $data['registrasi'],
                    'data' => $data,
                    'header_use' => $header_use,
                    'ttdSono' => $qrcodeSono
                ]);
                return $pdf->stream();
            } else if ($collection == 'SuratPemakaianAmbulance') {
                // dd($data);
                $dataQR2 = $this->generateDataQR(
                    $data,
                    $collection,
                    'dokumen.signature-petugas',
                    ['a' => '0'],
                    ['dpjp' => isset($data['namaPetugasPengirim']['label']) ? $data['namaPetugasPengirim']['label'] : $data['registrasi']['dokter']],
                    5,
                    130
                );
                $dataQR3 = $this->generateDataQR(
                    $data,
                    $collection,
                    'dokumen.signature-petugas',
                    ['a' => '0'],
                    ['dpjp' => isset($data['namaPetugasPenerima']['label']) ? $data['namaPetugasPenerima']['label'] : $data['registrasi']['dokter']],
                    5,
                    130
                );
                if (isset($data['KeluargaPasien']) && $data['KeluargaPasien'] != '') {
                    $qrcode3 = base64_encode(QrCode::format('svg')->size(80)->generate($data['KeluargaPasien']));
                }
                if (isset($data['SopirAmbulance']) && $data['SopirAmbulance'] != '') {
                    $qrcode4 = base64_encode(QrCode::format('svg')->size(80)->generate($data['SopirAmbulance']));
                }

                $pdf = App::make('dompdf.wrapper');
                $pdf->setPaper('A4', 'portrait');
                $pdf->loadView($blade, [
                    'cekWargaNegaraWNA' => $cekWargaNegaraWNA,
                    'profile' => $profile,
                    'identitas' => $pasien,
                    'pasien' => $pasien,
                    'qrcode' => $dataQR2['qrcode_base64'],
                    'qrcode2' => $dataQR3['qrcode_base64'],
                    'qrcode3' => $qrcode3 ?? null,
                    'qrcode4' => $qrcode4 ?? null,
                    'registrasi' => $data['registrasi'],
                    'data' => $data,
                ]);
                // dd($data);
                return $pdf->stream();
            } else if ($collection == 'AsesmenAwalMedisGawatDarurat') {
                // dd($data);
                // if (isset($data['user_input'])){
                //     $qrcode = base64_encode(QrCode::format('svg')->size(80)->generate($data['user_input']['namalengkap']));
                // }
                if (isset($data['registrasi']['dokter'])) {
                    $qrcode = base64_encode(QrCode::format('svg')->size(80)->generate($data['registrasi']['dokter']));
                }
                // dd($data);
                $pdf = App::make('dompdf.wrapper');
                $pdf->setPaper('A4', 'portrait');
                $pdf->loadView($blade, [
                    'cekWargaNegaraWNA' => $cekWargaNegaraWNA,
                    'profile' => $profile,
                    'identitas' => $pasien,
                    'pasien' => $pasien,
                    'qrcode' => $qrcode ?? null,
                    'registrasi' => $data['registrasi'],
                    'data' => $data,
                ]);
                // dd($data);
                return $pdf->stream();
            } else if ($collection == 'FormulirTransferPasienIntraRS') {
                // // dd($data);
                $dataQR2 = $this->generateDataQR(
                    $data,
                    $collection,
                    'dokumen.signature-petugas',
                    ['a' => '0'],
                    ['dpjp' => isset($data['disetujuiPerawat']['label']) ? $data['disetujuiPerawat']['label'] : $data['registrasi']['dokter']],
                    5,
                    130
                );
                if (isset($data['disetujui']) && $data['disetujui'] != '') {
                    $qrcode2 = base64_encode(QrCode::format('svg')->margin(5)->size(120)->generate($data['disetujui'])) ?? '';
                }
                if (isset($data['diterimaPerawat']['label']) && $data['diterimaPerawat']['label'] != '') {
                    $qrcode3 = base64_encode(QrCode::format('svg')->margin(5)->size(120)->generate($data['diterimaPerawat']['label'])) ?? '';
                }
                if (isset($data['petugasRegistrasi']) && $data['petugasRegistrasi']['label'] != '') {
                    $qrcode4 = base64_encode(QrCode::format('svg')->margin(5)->size(120)->generate($data['petugasRegistrasi']['label'])) ?? '';
                }
                $data2 = DB::connection('mongodb')
                    ->table($collection)
                    ->where('profile.kdprofile', $this->kdProfile)
                    ->where('statusenabled', true)
                    ->where('emrpasienfk', $r['emrpasienfk'])
                    ->orderBy('index_tabs')
                    ->get();

                $pdf = App::make('dompdf.wrapper');
                $pdf->setPaper('A4', 'portrait');
                $pdf->loadView($blade, [
                    'cekWargaNegaraWNA' => $cekWargaNegaraWNA,
                    'profile' => $profile,
                    'identitas' => $pasien,
                    'pasien' => $pasien,
                    'qrcode' => $dataQR2['qrcode_base64'],
                    'qrcode2' => $qrcode2 ?? null,
                    'qrcode3' => $qrcode3 ?? null,
                    'qrcode4' => $qrcode4 ?? null,
                    'registrasi' => $data['registrasi'],
                    'datas' => $data2,
                ]);
                // // dd($data);
                // return $pdf->stream();
                // $data2 = DB::connection('mongodb')
                //             ->table($collection)
                //             ->where('profile.kdprofile', $this->kdProfile)
                //             ->where('statusenabled', true)
                //             ->where('emrpasienfk', $r['emrpasienfk'])
                //             ->orderBy('index_tabs')
                //             ->get();

                // // Create an array to store all processed data
                // $allData = [];

                // foreach ($data2 as $data) {
                //     $dataQR2 = $this->generateDataQR(
                //         $data,
                //         $collection,
                //         'dokumen.signature-petugas',
                //         ['a' => '0'],
                //         ['dpjp' => isset($data['disetujuiPerawat']['label']) ? $data['disetujuiPerawat']['label'] : $data['registrasi']['dokter']],
                //         5,
                //         130
                //     );

                //     $qrcode2 = null;
                //     $qrcode3 = null;
                //     $qrcode4 = null;

                //     if (isset($data['disetujui']) && $data['disetujui'] != '') {
                //         $qrcode2 = base64_encode(QrCode::format('svg')->margin(5)->size(120)->generate($data['disetujui'])) ?? '';
                //     }
                //     if (isset($data['diterimaPerawat']['label']) && $data['diterimaPerawat']['label'] != '') {
                //         $qrcode3 = base64_encode(QrCode::format('svg')->margin(5)->size(120)->generate($data['diterimaPerawat']['label'])) ?? '';
                //     }
                //     if (isset($data['petugasRegistrasi']) && $data['petugasRegistrasi']['label'] != '') {
                //         $qrcode4 = base64_encode(QrCode::format('svg')->margin(5)->size(120)->generate($data['petugasRegistrasi']['label'])) ?? '';
                //     }

                //     // Store processed data for this record
                //     $allData[] = [
                //         'datas' => $data,
                //         'qrcode' => $dataQR2['qrcode_base64'],
                //         'qrcode2' => $qrcode2,
                //         'qrcode3' => $qrcode3,
                //         'qrcode4' => $qrcode4
                //     ];
                // }

                // // Now you can either:
                // // 1. Generate a single PDF with all records (if your view supports it)
                // // 2. Generate separate PDFs for each record
                // // 3. Return the processed data for other uses

                // // Example for option 1 (single PDF with all data):
                // $pdf = App::make('dompdf.wrapper');
                // $pdf->setPaper('A4', 'portrait');
                // $pdf->loadView($blade, [
                //     'cekWargaNegaraWNA' => $cekWargaNegaraWNA,
                //     'profile' => $profile,
                //     'identitas' => $pasien,
                //     'pasien' => $pasien,
                //     'datas' => $allData, // Pass all processed data to the view
                //     'registrasi' => $data2->first()['registrasi'] ?? null // Or you might want to handle this differently
                // ]);

                return $pdf->stream();
            } else if ($collection == 'CatatanPemindahanPasienAntarRS') {
                // dd($data);
                $dataQR2 = $this->generateDataQR(
                    $data,
                    $collection,
                    'dokumen.signature',
                    ['a' => '0'],
                    ['dpjp' => isset($data['dokterYangMerawat']['label']) ? $data['dokterYangMerawat']['label'] : $data['registrasi']['dokter']],
                    5,
                    130
                );
                if (isset($data['namaPJ']) && $data['namaPJ'] != '') {
                    $qrcode2 = base64_encode(QrCode::format('svg')->margin(5)->size(120)->generate($data['namaPJ'])) ?? '';
                }
                if (isset($data['perawatDiserahkan']['label']) && $data['perawatDiserahkan']['label'] != '') {
                    $qrcode3 = base64_encode(QrCode::format('svg')->margin(5)->size(120)->generate($data['perawatDiserahkan']['label'])) ?? '';
                }
                if (isset($data['perawatDiterima']['label']) && $data['perawatDiterima']['label'] != '') {
                    $qrcode4 = base64_encode(QrCode::format('svg')->margin(5)->size(120)->generate($data['perawatDiterima']['label'])) ?? '';
                }
                if (isset($data['wardClerk_dibukukan']['label']) && $data['wardClerk_dibukukan']['label'] != '') {
                    $qrcode5 = base64_encode(QrCode::format('svg')->margin(5)->size(120)->generate($data['wardClerk_dibukukan']['label'])) ?? '';
                }

                $pdf = App::make('dompdf.wrapper');
                $pdf->setPaper('A4', 'portrait');
                $pdf->loadView($blade, [
                    'cekWargaNegaraWNA' => $cekWargaNegaraWNA,
                    'profile' => $profile,
                    'identitas' => $pasien,
                    'pasien' => $pasien,
                    'qrcode' => $dataQR2['qrcode_base64'],
                    'qrcode2' => $qrcode2,
                    'qrcode3' => $qrcode3 ?? '',
                    'qrcode4' => $qrcode4 ?? '',
                    'qrcode5' => $qrcode5 ?? '',
                    'registrasi' => $data['registrasi'],
                    'data' => $data,
                ]);
                return $pdf->stream();
            } else if ($collection == 'SuratKeteranganHamil') {
                // dd($data);
                $dataQR2 = $this->generateDataQR(
                    $data,
                    $collection,
                    'dokumen.signature',
                    ['a' => '0'],
                    ['dpjp' => isset($data['DDDokter']['label']) ? $data['DDDokter']['label'] : $data['registrasi']['dokter']],
                    2,
                    100
                );

                $data['nip'] = DB::table('pegawai_m')
                    ->select('nip')
                    ->where('namaexternal', 'DOKTER')
                    ->where('reportdisplay', $data['DDDokter']['label'])
                    ->first();

                $pdf = App::make('dompdf.wrapper');
                $pdf->setPaper('F4', 'portrait');
                $pdf->loadView($blade, [
                    'cekWargaNegaraWNA' => $cekWargaNegaraWNA,
                    'profile' => $profile,
                    'identitas' => $pasien,
                    'pasien' => $pasien,
                    'qrcode' => $dataQR2['qrcode_base64'],
                    'registrasi' => $data['registrasi'],
                    'data' => $data,
                ]);
                return $pdf->stream();
            } else if ($collection == 'SuratKeteranganGawatDarurat') {


                $data['tgllahir'] = DB::table('pasien_m')
                    ->select('tgllahir')
                    ->where('nocm', $data['pasien']['nocm'])
                    ->first();

                $pdf = App::make('dompdf.wrapper');
                $pdf->setPaper('A4', 'portrait');
                $pdf->loadView($blade, [
                    'cekWargaNegaraWNA' => $cekWargaNegaraWNA,
                    'profile' => $profile,
                    'identitas' => $pasien,
                    'pasien' => $pasien,
                    'tte' => $qrcode,
                    'registrasi' => $data['registrasi'],
                    'data' => $data,
                ]);
                return $pdf->stream();
            } else if ($collection == 'PenunjangKhususObgyn') {
                if (isset($r['noregistrasi'])) {
                    $data = DB::connection('mongodb')
                        ->table($collection)
                        ->where('profile.kdprofile', $this->kdProfile)
                        ->where('statusenabled', true)
                        ->where('registrasi.noregistrasi', $r['noregistrasi'])
                        ->orderBy('created_at', 'asc')
                        ->first();

                    $dataQR2 = $this->generateDataQR(
                        $data,
                        $collection,
                        'dokumen.signature',
                        ['a' => '0'],
                        ['dpjp' => isset($data['DDDokter']['label']) ? $data['DDDokter']['label'] : $data['registrasi']['dokter']],
                        2,
                        100
                    );

                    $data['fotopendukung'] = EmrDokumen::select('file', 'objectberkaspasien', 'nama')
                        ->where('objectberkaspasien', 53)
                        ->where('noregistrasi', $data['registrasi']['noregistrasi'])
                        ->get();

                    // dd($data);
                    $pdf = App::make('dompdf.wrapper');
                    $pdf->setPaper('A4', 'portrait');
                    $pdf->loadView($blade, [
                        'cekWargaNegaraWNA' => $cekWargaNegaraWNA,
                        'profile' => $profile,
                        'identitas' => $pasien,
                        'pasien' => $pasien,
                        'qrcode' => $dataQR2['qrcode_base64'],
                        'registrasi' => $data['registrasi'],
                        'data' => $data,
                        'fotopendukung' => $data['fotopendukung'],
                    ]);
                    return $pdf->stream();
                } else {
                    $data = DB::connection('mongodb')
                        ->table($collection)
                        ->where('profile.kdprofile', $this->kdProfile)
                        ->where('statusenabled', true)
                        ->where('emrpasienfk', $r['emrpasienfk'])
                        ->orderBy('created_at', 'asc')
                        ->get();

                    $data['fotopendukung'] = EmrDokumen::select('file', 'objectberkaspasien', 'nama')
                        ->where('objectberkaspasien', 53)
                        ->where('noregistrasi', $data['registrasi']['noregistrasi'])
                        ->get();

                }
                dd($data);
                // dd($blade);

                $pdf = App::make('dompdf.wrapper');
                $pdf->setPaper('A4', 'portrait');
                $pdf->loadView($blade, [
                    'cekWargaNegaraWNA' => $cekWargaNegaraWNA,
                    'profile' => $profile,
                    'identitas' => $pasien,
                    'pasien' => $pasien,
                    // 'qrcode' => $dataQR2['qrcode_base64'],
                    'registrasi' => $data['registrasi'],
                    'data' => $data,
                ]);
                return $pdf->stream();
            } else if ($collection == 'FormMonitoringTranfusiDarah') {
                // dd($data);
                // dd($blade);

                $pdf = App::make('dompdf.wrapper');
                $pdf->setPaper('A4', 'portrait');
                $pdf->loadView($blade, [
                    'cekWargaNegaraWNA' => $cekWargaNegaraWNA,
                    'profile' => $profile,
                    'identitas' => $pasien,
                    'pasien' => $pasien,
                    // 'qrcode' => $dataQR2['qrcode_base64'],
                    'registrasi' => $data['registrasi'],
                    'data' => $data,
                ]);
                return $pdf->stream();
            } else if ($collection == 'IntervensiDanAsesmenUlangNyeri') {
                // dd($data);
                // dd($blade);

                $pdf = App::make('dompdf.wrapper');
                $pdf->setPaper([0, 0, 1000, 1500], 'landscape');
                $pdf->loadView($blade, [
                    'cekWargaNegaraWNA' => $cekWargaNegaraWNA,
                    'profile' => $profile,
                    'identitas' => $pasien,
                    'pasien' => $pasien,
                    // 'qrcode' => $dataQR2['qrcode_base64'],
                    'registrasi' => $data['registrasi'],
                    'data' => $data,
                ]);
                return $pdf->stream();
            } else if ($collection == 'KomitePencegahanPengendalianInfeksi') {
                if (isset($r['noregistrasi'])) {
                    $data = DB::connection('mongodb')
                        ->table($collection)
                        ->where('profile.kdprofile', $this->kdProfile)
                        ->where('statusenabled', true)
                        ->where('registrasi.noregistrasi', $r['noregistrasi'])
                        ->orderBy('created_at', 'asc')
                        ->first();


                    $data['fotopendukung'] = EmrDokumen::select('file', 'objectberkaspasien', 'nama')
                        ->where('objectberkaspasien', 52)
                        ->where('noregistrasi', $data['registrasi']['noregistrasi'])
                        ->get();

                    // dd($data);

                    $pdf = App::make('dompdf.wrapper');
                    $pdf->setPaper([0, 0, 1000, 1500], 'landscape');
                    $pdf->loadView($blade, [
                        'cekWargaNegaraWNA' => $cekWargaNegaraWNA,
                        'profile' => $profile,
                        'identitas' => $pasien,
                        'pasien' => $pasien,
                        // 'qrcode' => $dataQR2['qrcode_base64'],
                        'registrasi' => $data['registrasi'],
                        'data' => $data,
                        'fotopendukung' => $data['fotopendukung'],
                    ]);
                    return $pdf->stream();


                } else {
                    $data = DB::connection('mongodb')
                        ->table($collection)
                        ->where('profile.kdprofile', $this->kdProfile)
                        ->where('statusenabled', true)
                        ->where('emrpasienfk', $r['emrpasienfk'])
                        ->orderBy('created_at', 'asc')
                        ->get();

                    $data['fotopendukung'] = EmrDokumen::select('file', 'objectberkaspasien', 'nama')
                        ->where('objectberkaspasien', 52)
                        ->where('noregistrasi', $data['registrasi']['noregistrasi'])
                        ->get();

                }
                // dd($data);
                // dd($blade);

                $pdf = App::make('dompdf.wrapper');
                $pdf->setPaper([0, 0, 1000, 1500], 'landscape');
                $pdf->loadView($blade, [
                    'cekWargaNegaraWNA' => $cekWargaNegaraWNA,
                    'profile' => $profile,
                    'identitas' => $pasien,
                    'pasien' => $pasien,
                    // 'qrcode' => $dataQR2['qrcode_base64'],
                    'registrasi' => $data['registrasi'],
                    'data' => $data,
                ]);
                return $pdf->stream();
            } else if ($collection == 'PerkiraanBiaya') {
                // dd($data);

                $dataQR2 = $this->generateDataQR(
                    $data,
                    $collection,
                    'dokumen.signature',
                    ['a' => '0'],
                    ['dpjp' => isset($data['DokterMeminta']['label']) ? $data['DokterMeminta']['label'] : $data['registrasi']['dokter']],
                    2,
                    80
                );
                $pdf = App::make('dompdf.wrapper');
                $pdf->setPaper('A4', 'portrait');
                $pdf->loadView($blade, [
                    'cekWargaNegaraWNA' => $cekWargaNegaraWNA,
                    'profile' => $profile,
                    'identitas' => $pasien,
                    'pasien' => $pasien,
                    'qrcode' => $dataQR2['qrcode_base64'],
                    // 'qrcode' => $dataQR2['qrcode_base64'],
                    'registrasi' => $data['registrasi'],
                    'data' => $data,
                ]);
                return $pdf->stream();
            } else if ($collection == 'PersetujuanTindakanKedokteran' || $collection == 'SuratPenggunaanObatKhususKronis' || $collection == 'FormulirAsuhanKeperawatanDanObservasiPasienHemodialisa' || $collection == 'PersetujuanTindakanKedokteranAnestesi' || $collection == 'PersetujuanTindakanKedokteranMasukRuangIntensif' || $collection == 'PersetujuanTindakanKedokteranPembedahanUmum' || $collection == 'PersetujuanTindakanKedokteranTransfusiDarah' || $collection == 'FormulirTransferPasienIntraRS') {
                $data = DB::connection('mongodb')
                    ->table($collection)
                    ->where('profile.kdprofile', $this->kdProfile)
                    ->where('statusenabled', true)
                    ->whereNull('namatemplate')
                    ->where('emrpasienfk', $r['emrpasienfk'])
                    ->orderBy('index_tabs')
                    ->get();

                $pdf = App::make('dompdf.wrapper');
                $pdf->setPaper('A4', 'portrait');
                $pdf->loadView($blade, [
                    'cekWargaNegaraWNA' => $cekWargaNegaraWNA,
                    'profile' => $profile,
                    'pasien' => $pasien,
                    'data' => $data,
                ]);
                return $pdf->stream();
            } else if ($collection == 'PenolakanTindakanKedokteran') {
                $data = DB::connection('mongodb')
                    ->table($collection)
                    ->where('profile.kdprofile', $this->kdProfile)
                    ->where('statusenabled', true)
                    ->where('emrpasienfk', $r['emrpasienfk'])
                    ->get();

                $pdf = App::make('dompdf.wrapper');
                $pdf->setPaper('A4', 'portrait');
                $pdf->loadView($blade, [
                    'cekWargaNegaraWNA' => $cekWargaNegaraWNA,
                    'profile' => $profile,
                    'pasien' => $pasien,
                    'data' => $data,
                ]);
                return $pdf->stream();
            } else if ($collection == 'PeresepanHemodialisisRI') {

                $dataQR2 = $this->generateDataQR(
                    $data,
                    $collection,
                    'dokumen.signature',
                    ['a' => '0'],
                    ['dpjp' => isset($data['dokterYM']['label']) ? $data['dokterYM']['label'] : $data['registrasi']['dokter']],
                    2,
                    100
                );

                $pdf = App::make('dompdf.wrapper');
                $pdf->setPaper('A4', 'portrait');
                $pdf->loadView($blade, [
                    'cekWargaNegaraWNA' => $cekWargaNegaraWNA,
                    'profile' => $profile,
                    'pasien' => $pasien,
                    'qrcode' => $dataQR2['qrcode_base64'],
                    'data' => $data,
                ]);
                return $pdf->stream();
            } else if ($collection == 'PeresepanHemodialisisRJ') {

                $dataQR2 = $this->generateDataQR(
                    $data,
                    $collection,
                    'dokumen.signature',
                    ['a' => '0'],
                    ['dpjp' => isset($data['dokterParaf']['label']) ? $data['dokterParaf']['label'] : $data['registrasi']['dokter']],
                    1,
                    80
                );

                $pdf = App::make('dompdf.wrapper');
                $pdf->setPaper('A4', 'portrait');
                $pdf->loadView($blade, [
                    'cekWargaNegaraWNA' => $cekWargaNegaraWNA,
                    'profile' => $profile,
                    'pasien' => $pasien,
                    'qrcode' => $dataQR2['qrcode_base64'],
                    'data' => $data,
                ]);
                return $pdf->stream();
            } else if ($collection == 'SuratKeteranganDirawatOpname') {

                $dataQR2 = $this->generateDataQR(
                    $data,
                    $collection,
                    'dokumen.signature',
                    ['a' => '0'],
                    ['dpjp' => isset($data['DDDokter']['label']) ? $data['DDDokter']['label'] : $data['registrasi']['dokter']],
                    1,
                    150
                );

                $pdf = App::make('dompdf.wrapper');
                $pdf->setPaper('A4', 'portrait');
                $pdf->loadView($blade, [
                    'cekWargaNegaraWNA' => $cekWargaNegaraWNA,
                    'profile' => $profile,
                    'pasien' => $pasien,
                    'qrcode' => $dataQR2['qrcode_base64'],
                    'data' => $data,
                ]);
                return $pdf->stream();
            } else if ($collection == 'RingkasanPulang') {
                // dd($data);
                $dataQR2 = $this->generateDataQR(
                    $data,
                    $collection,
                    'dokumen.signature',
                    ['a' => '0'],
                    ['dpjp' => isset($data['DokterPenanggungJawab']['label']) ? $data['DokterPenanggungJawab']['label'] : $data['registrasi']['dokter']],
                    2,
                    100
                );

                // $data['nip'] = DB::table('pegawai_m')
                //     ->select('nip')
                //     ->where('namaexternal', 'DOKTER')
                //     ->where('reportdisplay', $data['DokterPenanggungJawab']['label'])
                //     ->first();
                $reportDisplay = is_array($data['DokterPenanggungJawab']) && isset($data['DokterPenanggungJawab']['label'])
                    ? $data['DokterPenanggungJawab']['label']
                    : $data['DokterPenanggungJawab'];

                $data['nip'] = DB::table('pegawai_m')
                    ->select('nip')
                    ->where('namaexternal', 'DOKTER')
                    ->where('reportdisplay', $reportDisplay)
                    ->first();

                $reg = DB::table('pasiendaftar_t')
                    ->where('noregistrasi', '=', $r['noregistrasi'])
                    ->first();

                $pdf = App::make('dompdf.wrapper');
                $pdf->setPaper('A4', 'portrait');
                $pdf->loadView($blade, [
                    'cekWargaNegaraWNA' => $cekWargaNegaraWNA,
                    'profile' => $profile,
                    'identitas' => $pasien,
                    'pasien' => $pasien,
                    'qrcode' => $dataQR2['qrcode_base64'],
                    'registrasi' => $data['registrasi'],
                    'data' => $data,
                    'reg' => $reg,
                ]);
                return $pdf->stream();
            } else if ($collection == 'SuratPermintaanPenggunaanObatKhususKemoterapi') {
                // dd($data);
                $dataQR2 = $this->generateDataQR(
                    $data,
                    $collection,
                    'dokumen.signature',
                    ['a' => '0'],
                    ['dpjp' => isset($data['wakilDirektur']['label']) ? $data['wakilDirektur']['label'] : $data['registrasi']['dokter']],
                    5,
                    130
                );

                $dataQR3 = $this->generateDataQR(
                    $data,
                    $collection,
                    'dokumen.signature',
                    ['a' => '0'],
                    ['dpjp' => isset($data['konsultan']['label']) ? $data['konsultan']['label'] : $data['registrasi']['dokter']],
                    5,
                    130
                );

                $qrcode2 = $dataQR2['qrcode_base64'];
                $qrcode3 = $dataQR3['qrcode_base64'];

                $pdf = App::make('dompdf.wrapper');
                $pdf->setPaper('A4', 'portrait');
                $pdf->loadView($blade, [
                    'cekWargaNegaraWNA' => $cekWargaNegaraWNA,
                    'profile' => $profile,
                    'identitas' => $pasien,
                    'pasien' => $pasien,
                    'tte' => $qrcode,
                    'qrcode2' => $qrcode2,
                    'qrcode3' => $qrcode3,
                    'registrasi' => $data['registrasi'],
                    'data' => $data,
                ]);
                return $pdf->stream();
            } else if ($collection == 'FormulirAsuhanDanKeperawatanPasienKemoterapiRawatJalan') {
                // Jangan
                // Initialize PDFMerger
                $pdfAsli = PDFMerger::init();

                // Generate First PDF (Portrait)
                $pdf = App::make('dompdf.wrapper');
                $pdf->setPaper('A4', 'portrait');
                $pdfContent = $pdf->loadView($blade, [
                    'cekWargaNegaraWNA' => $cekWargaNegaraWNA,
                    'profile' => $profile,
                    'identitas' => $pasien,
                    'pasien' => $pasien,
                    'registrasi' => $data['registrasi'],
                    'data' => $data,
                    'header_use' => $header_use,
                    'ttdSono' => $qrcodeSono,
                ])->output();

                // Generate Second PDF (Landscape)
                $pdf2 = App::make('dompdf.wrapper');
                $pdf2->setPaper([0, 0, 700, 1000], 'landscape');
                $pdf2Content = $pdf2->loadView($blade . '2', [
                    'cekWargaNegaraWNA' => $cekWargaNegaraWNA,
                    'profile' => $profile,
                    'identitas' => $pasien,
                    'pasien' => $pasien,
                    'registrasi' => $data['registrasi'],
                    'data' => $data,
                    'header_use' => $header_use,
                    'ttdSono' => $qrcodeSono,
                ])->output();

                // Generate Third PDF (Potrait)
                $pdf3 = App::make('dompdf.wrapper');
                $pdf3->setPaper('A4', 'portrait');
                $pdf3Content = $pdf3->loadView($blade . '3', [
                    'cekWargaNegaraWNA' => $cekWargaNegaraWNA,
                    'profile' => $profile,
                    'identitas' => $pasien,
                    'pasien' => $pasien,
                    'registrasi' => $data['registrasi'],
                    'data' => $data,
                    'header_use' => $header_use,
                    'ttdSono' => $qrcodeSono,
                ])->output();


                // Ensure PDF content is not empty
                if (empty($pdfContent) || empty($pdf2Content) || empty($pdf3Content)) {
                    return response()->json(['error' => 'Failed to generate PDFs.'], 500);
                }

                // Create temporary files
                $pdf1Path = tempnam(sys_get_temp_dir(), 'pdf1_') . '.pdf';
                $pdf2Path = tempnam(sys_get_temp_dir(), 'pdf2_') . '.pdf';
                $pdf3Path = tempnam(sys_get_temp_dir(), 'pdf3_') . '.pdf';

                // Write PDFs to temporary files
                File::put($pdf1Path, $pdfContent);
                File::put($pdf2Path, $pdf2Content);
                File::put($pdf3Path, $pdf3Content);

                // Ensure files exist and are valid before merging
                if (!File::exists($pdf1Path) || !File::exists($pdf2Path) || !File::exists($pdf3Path) || File::size($pdf1Path) === 0 || File::size($pdf2Path) === 0 || File::size($pdf3Path) === 0) {
                    return response()->json(['error' => 'PDF files were not saved correctly.'], 500);
                }

                // Merge PDFs
                $pdfAsli->addPDF($pdf1Path, 'all');
                $pdfAsli->addPDF($pdf2Path, 'all');
                $pdfAsli->addPDF($pdf3Path, 'all');
                $pdfAsli->merge();

                // Delete temporary files after merging
                File::delete($pdf1Path);
                File::delete($pdf2Path);
                File::delete($pdf3Path);

                // Return the merged PDF as a response
                return response($pdfAsli->stream());
            } else if ($collection == 'AsesmenMedisRawatJalan') {
                $data2 = DB::connection('mongodb')
                    ->table('#ResumeEMR')
                    ->where('noemr', $data['noemr'])
                    ->where('table', $collection)
                    ->first();

                $ruangan = '';
                if (isset($r['ruangan'])) {
                    $ruangan = $r['ruangan'];
                } else {
                    // $ruangan = $data['registrasi']['namaruangan'];
                    $ruangan = $data2['ruangan']; // Pengisian berdasarkan ruangan penginputan asmed
                }

                $pdf = App::make('dompdf.wrapper');
                $pdf->setPaper('A4', 'portrait');
                $pdf->loadView($blade, [
                    'cekWargaNegaraWNA' => $cekWargaNegaraWNA,
                    'profile' => $profile,
                    'identitas' => $pasien,
                    'pasien' => $pasien,
                    'tte' => $qrcode,
                    'registrasi' => $data['registrasi'],
                    'data' => $data,
                    'data2' => $data2,
                    'ruangan' => $ruangan,
                    'header_use' => $header_use,
                    'ttdSono' => $qrcodeSono,
                    'qrcode2' => $qrcode2,
                ]);
                return $pdf->stream();
            } else if ($collection == 'AsesmenMedisRawatInap') {
                $ruangan = '';
                if (isset($r['noregistrasi'])) {
                    $data = DB::connection('mongodb')
                        ->table($collection)
                        ->where('profile.kdprofile', $this->kdProfile)
                        ->where('statusenabled', true)
                        ->where('registrasi.noregistrasi', $r['noregistrasi'])
                        ->orderBy('created_at', 'asc');
                } else {
                    $data = DB::connection('mongodb')
                        ->table($collection)
                        ->where('profile.kdprofile', $this->kdProfile)
                        ->where('statusenabled', true)
                        ->where('emrpasienfk', $r['emrpasienfk'])
                        ->orderBy('created_at', 'asc');
                }

                if (isset($r['emrid']) && $r['emrid'] != 'undefined' && $r['emrid'] != 'null') {
                    $data = $data->where('id', $r['emrid']);
                }

                if (isset($r['ruangan'])) {
                    $ruangan = $r['ruangan'];
                }

                $data = $data->get();
                $pdf = App::make('dompdf.wrapper');
                $pdf->setPaper('A4', 'portrait');
                $pdf->loadView($blade, [
                    'cekWargaNegaraWNA' => $cekWargaNegaraWNA,
                    'profile' => $profile,
                    'identitas' => $pasien,
                    'pasien' => $pasien,
                    'tte' => $qrcode,
                    'registrasi' => $data[0]['registrasi'],
                    'data' => $data,
                    // 'data2' => $data2,
                    'ruangan' => $ruangan,
                    'header_use' => $header_use,
                    'ttdSono' => $qrcodeSono,
                    'qrcode2' => $qrcode2,
                ]);
                return $pdf->stream();
            } else {
                // return $qrcode2;
                $pdf = App::make('dompdf.wrapper');

                // Set paper size
                if ($collection == 'FormulirCatataPemberianObatKemoterapi') {
                    $pdf->setPaper('A2', 'landscape');
                } else if (in_array($collection, $collection_landscape)) {
                    $pdf->setPaper('A4', 'landscape');
                } else {
                    $pdf->setPaper('A4', 'portrait');
                }

                $pdf->loadView($blade, [
                    'cekWargaNegaraWNA' => $cekWargaNegaraWNA,
                    'profile' => $profile,
                    'identitas' => $pasien,
                    'pasien' => $pasien,
                    'tte' => $qrcode,
                    'registrasi' => $data['registrasi'],
                    'data' => $data,
                    'header_use' => $header_use,
                    'ttdSono' => $qrcodeSono,
                    'qrcode2' => $qrcode2 ?? null,
                ]);

                if ($collection == 'CatatanPerkembanganPasienTerintegrasi') {
                    return view($blade, [
                        'cekWargaNegaraWNA' => $cekWargaNegaraWNA,
                        'profile' => $profile,
                        'identitas' => $pasien,
                        'pasien' => $pasien,
                        'tte' => $qrcode,
                        'registrasi' => $data['registrasi'],
                        'data' => $data,
                        'header_use' => $header_use,
                        'ttdSono' => $qrcodeSono,
                        'qrcode2' => $qrcode2,
                    ]);
                }

                return $pdf->stream();
            }
        }


        $res['storage'] = false;
        if ($res['storage'] == false) {
            $data['tgllahir'] = DB::table('pasien_m')->select('tgllahir')
                ->where('nocm', $data['pasien']['nocm'])
                ->first();
            $reg = DB::table('pasiendaftar_t')
                ->where('noregistrasi', '=', $r['noregistrasi'])
                ->first();


            $res['storage'] = true;
            $pdf = App::make('dompdf.wrapper');
            $pdf->setPaper('A4', 'portrait');
            $pdf->loadView($blade, [
                'cekWargaNegaraWNA' => $cekWargaNegaraWNA,
                'profile' => $profile,
                'identitas' => $pasien,
                'pasien' => $pasien,
                'tte' => $qrcode,
                'registrasi' => $data['registrasi'],
                'reg' => $reg,
                'data' => $data,
                'qrcode2' => $qrcode2,
                'qrcode3' => $qrcode3,
                'qrcode4' => $qrcode4,
                'qrcode' => $qrcode,
                'dataQR2' => $dataQR2,
                'header_use' => $header_use,
                'ttdSono' => $qrcodeSono,
                'admission' => $qrcodeAdmission
            ]);
            return $pdf;
        }

        $registrasi = $data['registrasi'];
        return view($blade, compact('profile', 'data', 'pasien', 'identitas', 'registrasi', 'header_use', 'qrcode','qrcode2','tte', 'qrcodeSono'));
    }

    public function cetakEMR2($collection, Request $r)
    {
        // start generate parameter kebutuhan save dokumen

        if (isset($r['noregistrasi']) && $r['noregistrasi'] != "") {
            $registrasi = PasienDaftar::where('noregistrasi', '=', $r['noregistrasi'])->first();
        } else {
            $dataawal = DB::connection('mongodb')
                ->table($collection)
                ->where('profile.kdprofile', $this->kdProfile)
                ->where('statusenabled', true)
                ->where('emrpasienfk', $r['emrpasienfk'])
                ->first();

            $registrasi = PasienDaftar::where('noregistrasi', '=', $dataawal['registrasi']['noregistrasi'])->first();
            $r['noregistrasi'] = $registrasi->noregistrasi;
        }

        $pasien = Pasien::where('id', '=', $registrasi->nocmfk)->first();

        // List collection dengan tampilan landscape
        $collection_landscape = [
            'FormulirCatataPemberianObatKemoterapi',
        ];

        $qrcode2 = '';
        $qrcode3 = '';
        $qrcode4 = '';
        $tte2 = '';
        $tte3 = '';
        $dataQR2 = '';
        $qrcodeAdmission = '';
        if ($collection == 'FormulirBuktiPelayananCanggih' || $collection == 'FormulirKedokteranFisikDanRehabilitasi') {
            $pasien = Pasien::where('id', '=', $registrasi->nocmfk)->first();
            if ($pasien && !empty($pasien->nobpjs)) {
                // $qrcode2 = base64_encode(QrCode::format('svg')->size(45)->generate((string) $pasien->nobpjs));
                $qrcode2 = base64_encode(QrCode::format('svg')->size(45)->generate((string) $pasien->nobpjs));
            } elseif ($pasien && !empty($pasien->nocm)) {
                $qrcode2 = base64_encode(QrCode::format('svg')->size(45)->generate((string) $pasien->nocm));
            } else {
                $qrcode2 = '';
            }
        } else {
            $qrcode2 = '';
        }

        $dataEMR2 = '';

        if ($collection == 'RingkasanKeluar') {
            if (isset($r['noregistrasi'])) {



                $dataEMR = DB::connection('mongodb')
                    ->table($collection)
                    ->where('profile.kdprofile', $this->kdProfile)
                    ->where('statusenabled', true)
                    ->where('registrasi.noregistrasi', $r['noregistrasi']);

                // if ($collection == 'JadwalKunjunganRehabDanFisio') {
                $dataEMR = $dataEMR->orderBy('created_at', 'desc');
                // }

                $dataEMR = $dataEMR->get();

                // dd(count($dataEMR));

                if (!empty($dataEMR)){
                    for($i = 0; $i < count($dataEMR); $i++){
                            if ($dataEMR[$i]['registrasi']['objectruanganfk'] == 215 || $dataEMR[$i]['registrasi']['objectruanganfk'] == 411) {

                                $dataEMR1 = DB::connection('mongodb')
                                    ->table('AsesmenFisioterapi')
                                    ->where('profile.kdprofile', $this->kdProfile)
                                    ->where('statusenabled', true)
                                    ->where('registrasi.noregistrasi', $r['noregistrasi']);

                                // if ($collection == 'JadwalKunjunganRehabDanFisio') {
                                $dataEMR1 = $dataEMR1->orderBy('created_at', 'desc');
                                // }

                                $dataEMR1 = $dataEMR1->first();

                                $data = DB::connection('mongodb')
                                    ->table('AsesmenFisioterapi')
                                    ->where('profile.kdprofile', $this->kdProfile)
                                    ->where('statusenabled', true)
                                    ->where('emrpasienfk', $dataEMR1['emrpasienfk'])
                                    ->first();

                                if (!isset($data['keluhanUtama']) || empty($data['keluhanUtama'])) {
                                    $fallbackData = DB::connection('mongodb')
                                        ->table('RingkasanKeluar')
                                        ->where('profile.kdprofile', $this->kdProfile)
                                        ->where('statusenabled', true)
                                        ->where('registrasi.noregistrasi', $r['noregistrasi'])
                                        ->first();

                                    // Jika data RingkasanKeluar ditemukan dan memiliki TAKondisiSaatMasuk
                                    if ($fallbackData !== null && isset($fallbackData['TAKondisiSaatMasuk'])) {
                                        // Gabungkan data: pertahankan semua dari AsesmenFisioterapi, tapi ganti keluhanUtama
                                        $data['keluhanUtama'] = $fallbackData['TAKondisiSaatMasuk'];
                                    }
                                }
                                // dd($data);

                                $dataEMR3 = DB::connection('mongodb')
                                    ->table('AsesmenAwalKeperawatanPasienRawatJalanNurse')
                                    ->where('profile.kdprofile', $this->kdProfile)
                                    ->where('statusenabled', true)
                                    ->where('registrasi.noregistrasi', $r['noregistrasi'])
                                    ->orderBy('created_at', 'desc')
                                    ->first();

                                if ($dataEMR3 === null) {
                                    $dataEMR3 = DB::connection('mongodb')
                                        ->table('RingkasanKeluar')
                                        ->where('profile.kdprofile', $this->kdProfile)
                                        ->where('statusenabled', true)
                                        ->where('registrasi.noregistrasi', $r['noregistrasi'])
                                        ->first();
                                }

                                $dataN = DB::connection('mongodb')
                                    ->table('AsesmenAwalKeperawatanPasienRawatJalanNurse')
                                    ->where('profile.kdprofile', $this->kdProfile)
                                    ->where('statusenabled', true)
                                    ->where('emrpasienfk', $dataEMR3['emrpasienfk'])
                                    ->first();
                                if ($dataN === null) {
                                    $dataN = DB::connection('mongodb')
                                        ->table('RingkasanKeluar')
                                        ->where('profile.kdprofile', $this->kdProfile)
                                        ->where('statusenabled', true)
                                        ->where('registrasi.noregistrasi', $r['noregistrasi'])
                                        ->first();
                                }


                                $dataEMR4 = DB::connection('mongodb')
                                    ->table('CatatanPerkembanganPasienTerintegrasi')
                                    ->where('profile.kdprofile', $this->kdProfile)
                                    ->where('statusenabled', true)
                                    ->where('registrasi.noregistrasi', $r['noregistrasi']);

                                // if ($collection == 'JadwalKunjunganRehabDanFisio') {
                                $dataEMR4 = $dataEMR4->orderBy('created_at', 'desc');
                                // }

                                $dataEMR4 = $dataEMR4->first();

                                if ($dataEMR4 != null) {
                                    $dataY = DB::connection('mongodb')
                                        ->table('CPPTDetail')
                                        // ->where('profile.kdprofile', $this->kdProfile)
                                        // ->where('statusenabled', true)
                                        ->where('flag', 'perawat')
                                        // ->where('ruangan', 'POLI FISIOTERAPI')
                                        ->where('emrpasienfk', $dataEMR4['emrpasienfk']);

                                    $dataY = $dataY->first();
                                }
                                // dd($data);
                                $data['riwayatkeluar'] = null;
                                $data['TADiagnosisPrimer'] = null;
                                $data['TAKondisiSaatMasuk'] = $data['keluhanUtama'];
                                // $data['TAKondisiSaatMasuk'] = isset($data['TAKondisiSaatMasuk']) ? $data['TAKondisiSaatMasuk'] : $data['keluhanUtama'];
                                // if($data['TAKondisiSaatMasuk'] != null){
                                //     $data['TAKondisiSaatMasuk'] = $data['TAKondisiSaatMasuk'];
                                // }else{
                                //     $data['TAKondisiSaatMasuk'] = $data['keluhanUtama'];
                                // }
                                // $data['anamnesis'] = $dataEMR4 != null ? $dataY['S'] : null ;
                                $data['anamnesis'] = $data['keluhanUtama'];
                                $data['tekananDarah'] = isset($dataN['tekananDarahObgyn']) ? $dataN['tekananDarahObgyn'] : $dataN['tekananDarah'];
                                $data['nadi'] = isset($dataN['nadiObgyn']) ? $dataN['nadiObgyn'] : $dataN['nadi'];
                                $data['nafas'] = isset($dataN['nafasObgyn']) ? $dataN['nafasObgyn'] : $dataN['nafas'];
                                $data['celcius'] = isset($dataN['celciusObgyn']) ? $dataN['celciusObgyn'] : $dataN['celcius'];
                                $data['gcse'] = $dataN['gcse'];
                                $data['gcsv'] = $dataN['gcsv'];
                                $data['gcsm'] = $dataN['gcsm'];
                                $data['riwayatkeluar'] = 'Membaik';
                                $data['registrasi']['dokter'] = 'dr. COKORDA GDE BAYU BASKARA PUTRA, Sp.KFR';
                                $data['intruksi'] = !empty($data['intervensi']) ? $data['intervensi'] : (!empty($dataY) ? $dataY['P'] : '-');
                                $data['pemeriksaanfisik'] = !empty($data['pemeriksaanFisik']) ? $data['pemeriksaanFisik'] : (!empty($dataY) ? $dataY['O'] : '-');
                                $data['diagnosisFisioterapi'] = !empty($data['diagnosisFisioterapi']) ? $data['diagnosisFisioterapi'] : (!empty($dataY) ? $dataY['A'] : '-');

                                if (isset($responseSEP)) {
                                    $data['dpjpcadangan'] = $responseSEP->dpjp->nmDPJP;
                                } else {
                                    $data['dpjpcadangan'] = '-';
                                }
                                $data['kesanUmum'] = 1;

                                $datas[] = $data;

                        } else{
                            $data = DB::connection('mongodb')
                            ->table($collection)
                            ->where('profile.kdprofile', $this->kdProfile)
                            ->where('statusenabled', true)
                            ->where('emrpasienfk', $dataEMR[$i]['emrpasienfk'])
                            ->first();

                            $datas[] = $data;
                        }
                    }
                }

                // dd($datas);
            }




        }

        if (empty($data)) {
            echo '
            <script language="javascript">
                window.alert("Tidak ada data.");
                window.close()
            </script>';
            die;
        }


        $cekWargaNegaraWNA = ($r !== null && isset($r['wna'])) ? $r['wna'] : false;

        $stringQR = $collection . ';' . $dataEMR[0]['_id'];

        // $encryptQR = base64_encode($stringQR);
        // $stringQR = $dataQR['nocm'] . ';' . $dataQR['inisial'] . ';' . $dataQR['namaruangan'] . ';' .
        // $dataQR['dpjp'] . ';' . $dataQR['tglberkunjung'] . ';' . $dataQR['tglpulang'] . ';' . $dataQR['type'];

        $encryptQR = base64_encode($stringQR);

        $iswna = ($r !== null && isset($r['wna'])) ? $r['wna'] : false;
        $tte = route('dokumen.signature') . '?key=' . $encryptQR . '&a=' . $iswna;

        $profile = $this->profile();

        $canvasPNG = new Png($tte);
        $canvasPNG->setMargin(5);
        $canvasPNG->setSize(130);

        $canvasPNG->setErrorCorrectionLevel(new ErrorCorrectionLevelLow);
        $writer = new PngWriter();
        $resultBarcode = $writer->write($canvasPNG);

        // Encode to base64 for display
        $qrcode = base64_encode($resultBarcode->getString());


        // $canvasPNG = new Png($pasien['tte']);
        // $canvasPNG->setMargin(5);
        // $canvasPNG->setSize(130);

        // $canvasPNG->setErrorCorrectionLevel(new ErrorCorrectionLevelLow);
        // $writer = new PngWriter();
        // $resultBarcode = $writer->write($canvasPNG);

        // $qrcode = base64_encode($resultBarcode->getString());

        $identitas = $pasien;
        $profile = $this->profile();
        $header_use = "head-emr";
        $blade = 'report.emr.' . $collection.'2';

        // dd($datas);
        $res['storage'] = false;
        if ($res['storage'] == false) {
            $data['tgllahir'] = DB::table('pasien_m')->select('tgllahir')
                ->where('nocm', $data['pasien']['nocm'])
                ->first();
            $reg = DB::table('pasiendaftar_t')
                ->where('noregistrasi', '=', $r['noregistrasi'])
                ->first();


            $res['storage'] = true;
            $pdf = App::make('dompdf.wrapper');
            $pdf->setPaper('A4', 'portrait');
            $pdf->loadView($blade, [
                'cekWargaNegaraWNA' => $cekWargaNegaraWNA,
                'profile' => $profile,
                'identitas' => $pasien,
                'pasien' => $pasien,
                'tte' => $qrcode,
                'registrasi' => $registrasi,
                'reg' => $reg,
                'data' => $datas,
                'dataQR2' => $dataQR2,
                'header_use' => $header_use,
            ]);
            return $pdf;
        }
    }

    public function cetakEMRDetail($collection, Request $r)
    {
        // start generate parameter kebutuhan save dokumen
        if (isset($r['noregistrasi'])) {
            $dataEMR = DB::connection('mongodb')
                ->table($collection)
                ->where('profile.kdprofile', $this->kdProfile)
                ->where('statusenabled', true)
                ->where('registrasi.noregistrasi', $r['noregistrasi']);

            if (isset($r['objectpegawaifk']) && $r['objectpegawaifk'] != 'undefined' && $r['objectpegawaifk'] != 'null') {
                $dataEMR = $dataEMR->where('registrasi.objectpegawaifk', $r['objectpegawaifk']);
            }
            //
            $dataEMR = $dataEMR->get();

            if (!empty($dataEMR)) {
                $r['id'] = $dataEMR[0]['id'];
            } else {
                $r['id'] = null;
            }
        }
        // start generate parameter kebutuhan save dokumen
        $data = DB::connection('mongodb')
            ->table($collection)
            ->where('profile.kdprofile', $this->kdProfile)
            ->where('statusenabled', true)
            ->where('id', $r['id'])
            ->first();
        // dd($data['registrasi']);

        if (empty($data)) {
            echo '
            <script language="javascript">
                window.alert("Tidak ada data.");
                window.close()
            </script>';
            die;
        }

        if (!$data['registrasi']['objectpegawaifk']) {
            return $this->respond('null', 400, 'DPJP belum diketahui, Silahkan simpan ulang');
        }

        $exp = explode(' ', $data['pasien']['namapasien']);
        $alfa = '';

        $dataQR = [
            "nocm" => $data['pasien']['nocm'],
            "namaruangan" => $data['registrasi']['namaruangan'],
            "dpjp" => $data['registrasi']['dokter'],
            "tglberkunjung" => date("Y-m-d", strtotime($data['registrasi']['tglregistrasi'])),
            "tglpulang" => date("Y-m-d", strtotime($data['registrasi']['tglpulang'])),
            "type" => $collection,
        ];
        $stringQR = $collection . ';' . $data['_id'];

        // $encryptQR = base64_encode($stringQR);
        // $stringQR = $dataQR['nocm'] . ';' . $dataQR['inisial'] . ';' . $dataQR['namaruangan'] . ';' .
        // $dataQR['dpjp'] . ';' . $dataQR['tglberkunjung'] . ';' . $dataQR['tglpulang'] . ';' . $dataQR['type'];

        $encryptQR = base64_encode($stringQR);

        $iswna = ($r !== null && isset($r['wna'])) ? $r['wna'] : false;
        $pasien = $dataQR;
        $pasien['tte'] = route('dokumen.signature') . '?key=' . $encryptQR . '&a=' . $iswna;

        $pasien['tglkontrol'] = isset($data['tglperjanjian']) ? date('d', strtotime($data['tglperjanjian'])) . ' ' . $this->dateLocalID('F ', $data['tglperjanjian']) . ' ' . date('Y', strtotime($data['tglperjanjian'])) : '';
        $pasien['dateNow'] = date('d') . ' ' . $this->dateLocalID('F ', date('Y-m-d')) . ' ' . date('Y');
        $pasien["tgllahir"] = $data['pasien']['tgllahir'];
        $pasien["jeniskelamin"] = $data['pasien']['jeniskelamin'];
        $pasien['namapasien'] = $data['pasien']['namapasien'];
        $sonograph = [];
        $qrcodeSono = null;

        if (isset($data['sonographer']) && is_array($data['sonographer']) && array_key_exists('label', $data['sonographer'])) {
            $sonograph = [
                'ttdSono' => $data['sonographer']['label'],
            ];
            $qrcodeSono = base64_encode(QrCode::format('svg')->size(75)->generate($sonograph['ttdSono']));
        } else {
            $qrcodeSono = null;
        }
        $canvasPNG = new Png($pasien['tte']);
        $canvasPNG->setMargin(5);
        $canvasPNG->setSize(130);

        $canvasPNG->setErrorCorrectionLevel(new ErrorCorrectionLevelLow);
        $writer = new PngWriter();
        $resultBarcode = $writer->write($canvasPNG);

        // Encode to base64 for display
        $qrcode = base64_encode($resultBarcode->getString());

        $identitas = $pasien;
        $profile = $this->profile();
        $header_use = "head-emr";
        $blade = 'report.emr.' . $collection;

        // List emr untuk menambahkan index NIP, SIP, dll untuk pegawai/dokter
        $listEMR_Get_ExternalData = [
            'SuratKeteranganSakit',
            'SuratKeteranganSehat',
            'SuratKeteranganDiagnosa',
            'SuratKeteranganSehatJiwa',
            'SuratKeteranganNarkoba',
            'SuratKeteranganHasilPemeriksaanMata',
            'HasilPemeriksaanMCU',
            'MedicalReport',
            'HasilPemeriksaanSpirometri',
            'SuratKeteranganRekomendasiPsikologi',
            'SuratKeteranganDisabilitas',
            'HasilPemeriksaanBodyplethy',
            'HasilPemeriksaanDLCOBodyplethy',
            'TransThoracaEchoBayi',
            'TransThoracaEchoDewasa',
            'LowerExtermityDuplexUltrasoundUSGDoppler',
            'CarotidDuplexUltrasound',
            'FormulirHasilPemeriksaanEkg',
            'PemeriksaanTHT',
            'PemeriksaanKardiotokografi',
            'PemeriksaanObstetri',
            'PemeriksaanGynekologi',
            'PemeriksaanFetal',
            'PemeriksaanEkgMcu',
            'PemeriksaanEkgInterna',
            'PemeriksaanEkgParu',
            'FormulirCatataPemberianObatKemoterapi'
        ];

        $cekWargaNegaraWNA = ($r !== null && isset($r['wna'])) ? $r['wna'] : false;
        if ($r['pdf'] == 'true') {
            if ($collection == "CatatanInformasidanEdukasiTerintegrasi") {
                $data['resmetode'] = $this->getMetodeEdukasi();
            } else if (in_array($collection, $listEMR_Get_ExternalData)) {
                $reportDisplay = $this->legalityCheck($data, '');

                $data['nip'] = DB::table('pegawai_m')
                    ->select('nip')
                    ->where('namaexternal', 'DOKTER')
                    ->where('reportdisplay', $reportDisplay)
                    ->first();

                $data['nosip'] = DB::table('pegawai_m')
                    ->select('nosip')
                    ->where('namaexternal', 'DOKTER')
                    ->where('reportdisplay', $reportDisplay)
                    ->first();
            }

            $pdf = App::make('dompdf.wrapper');
            $pdf->setPaper('A4', 'portrait');
            $pdf->loadView($blade, [
                'cekWargaNegaraWNA' => $cekWargaNegaraWNA,
                'profile' => $profile,
                'identitas' => $pasien,
                'pasien' => $pasien,
                'tte' => $qrcode,
                'registrasi' => $data['registrasi'],
                'data' => $data,
                'header_use' => $header_use,
                'ttdSono' => $qrcodeSono
            ]);
            return $pdf->stream();
        }

        if (isset($r['storage']) && $r['storage']) {
            $pdf = App::make('dompdf.wrapper');
            $pdf->loadView(
                'report.emr.' . $collection,
                array(
                    'cekWargaNegaraWNA' => $cekWargaNegaraWNA,
                    'profile' => $profile,
                    'data' => $data,
                    'pasien' => $pasien,
                    'identitas' => $pasien,
                    'registrasi' => $data['registrasi'],
                    'tte' => $qrcode,
                    'header_use' => $header_use . "-dom",
                    'qrcodeSono' => $qrcodeSono,
                    'ttdSono' => $qrcodeSono
                )
            );
            return $pdf;
        }

        $registrasi = $data['registrasi'];
        return view('report.emr.' . $collection, compact('profile', 'data', 'pasien', 'identitas', 'registrasi', 'header_use', 'qrcode', 'qrcodeSono'));
    }
    public function cetakRencanaKontrol(Request $r)
    {

        $profile = $this->profile();
        $kdProfile = $this->kdProfile;
        $print = false;
        $pageWidth = 950;
        $norec_pd = $r['norec_pd'];
        $noregistrasi = $r['noregistrasi'];
        $nokontrol = '';
        $tglkontrol = '';
        $res['pdf'] = true;
        // start generate parameter kebutuhan save dokumen

        $registrasi = PasienDaftar::where('noregistrasi', '=', $noregistrasi)->first();

        $query = DB::table('antrianpasienregistrasi_t as apr')
            ->select(
                'rk.*',
                'pg.namalengkap as namadokter',
                'pasien.nocm',
                'ruang.reportdisplay as poli',
                DB::raw("coalesce(pasien.namapasien, apr.namapasien) as namapasien"),
                DB::raw("ru.namaruangan as ruangankontrol"),
                'apr.noantrianpoli',
                'apr.norec as norec_apr',
                'pasien.tgllahir',
                'jk.jeniskelamin',
                'ruang.namaruangan',
                'dokter.namalengkap',
                'apr.noreservasi',
                'apr.tanggalreservasi',
                'apr.type',
                'apr.tipepasien',
                'apr.jenis',
                'apr.tglinput',
                'kp.kelompokpasien',
                'kbs.name as kebangsaan',
                'apr.jam as jamreservasi',
                'pasien.nobpjs',
                'pa.nosurat',
                'pa.nosep',
                'pa.diagawal_nama',
                'pd.tglregistrasi',
                'pd.nocmfk',
                'pd.norec as norec_pd',
                DB::raw("pd.tglregistrasi - INTERVAL '1 DAY' as tgldef"),
                DB::raw("to_char(pd.tglregistrasi,'yyyy-mm-dd') as tglkontrol"),
                DB::raw("TO_CHAR(age(pasien.tgllahir), 'YY Thn MM Bln DD Hr') as umur")
            )
            ->leftJoin('riwayatkontrol_t as rk', 'apr.norec', 'rk.antrianpasienregistrasifk')
            ->leftJoin('pasiendaftar_t as pd', 'pd.nocmfk', 'apr.nocmfk')
            ->leftJoin('pasiendaftar_t as pd1', 'pd1.antrianpasienregistrasifk', 'apr.norec')
            ->leftJoin('pasien_m as pasien', 'pasien.id', 'apr.nocmfk')
            ->leftJoin('pegawai_m as dokter', 'dokter.id', 'apr.objectpegawaifk')
            ->leftJoin('pegawai_m as pg', 'pg.id', 'pd.objectpegawaifk')
            ->join('ruangan_m as ruang', 'ruang.id', 'pd.objectruanganlastfk')
            ->join('ruangan_m as ru', 'ru.id', 'rk.objectruanganfk')
            ->leftJoin('jeniskelamin_m as jk', 'jk.id', 'pasien.objectjeniskelaminfk')
            ->leftJoin('kebangsaan_m as kbs', 'kbs.id', 'pasien.objectkebangsaanfk')
            ->leftJoin('kelompokpasien_m as kp', 'kp.id', 'apr.objectkelompokpasienfk')
            ->leftJoin('pemakaianasuransi_t as pa', function ($join) {
                $join->on('pa.noregistrasifk', '=', 'pd.norec');
            })
            ->where('apr.kdprofile', $this->kdProfile)
            ->where('apr.statusenabled', true)
            ->where('pd.noregistrasi', $noregistrasi)
            ->groupBy(
                'rk.norec',
                'rk.nocmfk',
                'rk.objectpegawaifk',
                'rk.objectruanganfk',
                'rk.kdprofile',
                'rk.nosurat',
                'rk.typekontrol',
                'rk.tglkontrol',
                'rk.tglentry',
                'rk.nosepasal',
                'rk.isterbitsep',
                'rk.nokartu',
                'rk.jenispelayanan',
                'rk.terapi',
                'rk.catatan',
                'rk.diagnosaakhir',
                'rk.indikasikontrol',
                'rk.updated_at',
                'rk.nobukti',
                'rk.created_at',
                'kp.kelompokpasien',
                'pg.namalengkap',
                'ruang.reportdisplay',
                'pasien.namapasien',
                'apr.jam',
                'pasien.tgllahir',
                'jk.jeniskelamin',
                'ruang.namaruangan',
                'dokter.namalengkap',
                'apr.noantrianpoli',
                'apr.norec',
                'kbs.name',
                'apr.noreservasi',
                'apr.tanggalreservasi',
                'apr.type',
                'apr.tipepasien',
                'apr.jenis',
                'apr.namapasien',
                'pasien.nocm',
                'ru.namaruangan',
                'apr.tglinput',
                'pasien.nobpjs',
                'pa.diagawal_nama',
                'pa.nosurat',
                'pa.nosep',
                'pd.tglregistrasi',
                'pd.nocmfk',
                'pd.norec',
                DB::raw("pd.tglregistrasi - INTERVAL '1 DAY'"),
                DB::raw("to_char(pd.tglregistrasi,'yyyy-mm-dd')"),
                DB::raw("TO_CHAR(age(pasien.tgllahir), 'YY thn MM Bulan DD Hari')")
            )
            ->orderBy('rk.tglentry', 'desc')
            ->orderBy('apr.tanggalreservasi', 'desc');

        $query = $query->get();

        $objetoRequests = new \Illuminate\Http\Request();
        $objetoRequests['url'] = "SEP/" . $query[0]->nosep;
        $objetoRequests['method'] = "GET";
        $objetoRequests['data'] = null;
        $cariSEP = $this->bridgingBPJSCtrl->bpjsTools($objetoRequests, true);
        $responseSEPVc = json_decode(json_encode($cariSEP, false));

        $res['diagnosa'] = $responseSEPVc->response->diagnosa;
        $res['dpjp'] = $responseSEPVc->response->dpjp->nmDPJP;


        // if($tglentry){
        //     $tglkontrol = $tglentry[0]->tglregistrasi;
        // } else{
        if ($query[0]->nobpjs != null) {

            $objetoRequest = new \Illuminate\Http\Request();
            $objetoRequest['url'] = "RencanaKontrol/ListRencanaKontrol/Bulan/" . date('m', strtotime($query[0]->tglentry)) . "/Tahun/" . date('Y', strtotime($query[0]->tglentry)) . "/Nokartu/" . $query[0]->nobpjs . "/filter/2";

            $objetoRequest['method'] = "GET";
            $objetoRequest['data'] = null;
            $cariSPRI = $this->bridgingBPJSCtrl->bpjsTools($objetoRequest, true);
            $responseSPRIVc = json_decode(json_encode($cariSPRI, false));

            // dd($responseSPRIVc);

            if (isset($responseSPRIVc->metaData)) {
                // dd('Halo');
                for ($q = 0; $q < count($responseSPRIVc->response->list); $q++) {
                    $element = $responseSPRIVc->response->list[$q];
                    if ($element->tglRencanaKontrol == $query[0]->tglkontrol) {
                        $nokontrol = $element->noSuratKontrol;
                        $tglkontrol = $element->tglTerbitKontrol;
                        // dd($tglkontrol);
                    }
                }
            }

        }

        if ($tglkontrol == '') {
            $nocmfk = $query[0]->nocmfk;
            $tglregistrasi = $query[0]->tglregistrasi;

            $tglentry = DB::select(DB::raw("select to_char(tglregistrasi,'yyyy-mm-dd') tglregistrasi from pasiendaftar_t
                where nocmfk = '$nocmfk'
                and statusenabled = true
                and tglregistrasi::date < '$tglregistrasi'::date
                order by tglregistrasi desc limit 1"));

            if ($tglentry) {
                $tglkontrol = $tglentry[0]->tglregistrasi;
            }
        }
        // }



        $qrcode = base64_encode(QrCode::format('svg')->size(200)->errorCorrection('H')->generate("https://simrsbm-test.baliprov.go.id/service/cetak-rencana-kontrol-klaim-poli?norec_pd=" . $query[0]->norec_pd));

        // var_dump($query);

        $res['data'] = $query;
        $res['nokontrol'] = $nokontrol;
        $res['tglkontrol'] = $tglkontrol;
        // dd($res['tglkontrol']);

        $res['qrcode'] = $qrcode;
        $blade = 'report.registrasi.rencana-kontrol';
        // $qrcode = base64_encode(QrCode::format('svg')->size(45)->generate($res['sep']->peserta->noKartu));
        if ($res['pdf'] == 'true') {
            $pdf = App::make('dompdf.wrapper');
            $pdf->setpaper('A4', 'landscape');
            $pdf->loadView(
                $blade,
                array(
                    'profile' => $profile,
                    'pageWidth' => $pageWidth,
                    'res' => $res,
                    'pdf' => true,
                )
            );
            return $pdf;
        }
        if (isset($r['storage'])) {
            $res['storage'] = true;
            $pdf = App::make('dompdf.wrapper');
            $pdf->setpaper('A4', 'landscape');
            // $pdf->setpaper([0, 0, 841.89, 423.15]);
            $pdf->loadView(
                $blade,
                array(
                    'profile' => $profile,
                    'pageWidth' => $pageWidth,
                    'res' => $res,
                    'pdf' => true
                )
            );
            return $pdf;
        }
        $pdf = false;
        return view($blade, compact('profile', 'pageWidth', 'res', 'pdf'));
    }
    public function cetakRencanaKontrolPoli(Request $r)
    {

        $profile = $this->profile();
        $kdProfile = $this->kdProfile;
        $print = false;
        $pageWidth = 950;
        $norec_pd = $r['norec_pd'];
        $nobukti = $r['nobukti'];
        $nokontrol = '';
        $tglkontrol = '';
        $res['pdf'] = true;
        // start generate parameter kebutuhan save dokumen

        $registrasi = PasienDaftar::where('norec', '=', $norec_pd)->first();

        $query = DB::table('antrianpasienregistrasi_t as apr')
            ->select(
                'rk.*',
                'pg.namalengkap',
                'pasien.nocm',
                'ruang.reportdisplay as poli',
                DB::raw("coalesce(pasien.namapasien, apr.namapasien) as namapasien"),
                DB::raw("ru.namaruangan as ruangankontrol"),
                'apr.noantrianpoli',
                'apr.norec as norec_apr',
                'pasien.tgllahir',
                'jk.jeniskelamin',
                'ruang.namaruangan',
                'dokter.namalengkap as namadokter',
                'apr.noreservasi',
                DB::raw("to_char(apr.tanggalreservasi,'yyyy-mm-dd') as tanggalreservasi"),
                'apr.type',
                'apr.tipepasien',
                'apr.jenis',
                'apr.tglinput',
                'kp.kelompokpasien',
                'kbs.name as kebangsaan',
                'apr.jam as jamreservasi',
                'pasien.nobpjs',
                'pa.nosurat',
                'pa.nosep',
                'pa.diagawal_nama',
                'pd.tglregistrasi',
                'pd.objectkelompokpasienlastfk',
                'pd.nocmfk',
                'pd.norec as norec_pd',
                DB::raw("pd.tglregistrasi - INTERVAL '1 DAY' as tgldef"),
                DB::raw("to_char(pd.tglregistrasi,'yyyy-mm-dd') as tglkontrol"),
                DB::raw("TO_CHAR(age(pasien.tgllahir), 'YY Thn MM Bln DD Hr') as umur")
            )
            ->leftJoin('riwayatkontrol_t as rk', 'apr.norec', 'rk.antrianpasienregistrasifk')
            ->leftJoin('pasiendaftar_t as pd', 'pd.nocmfk', 'apr.nocmfk')
            ->leftJoin('pasiendaftar_t as pd1', 'pd1.antrianpasienregistrasifk', 'apr.norec')
            ->leftJoin('pasien_m as pasien', 'pasien.id', 'apr.nocmfk')
            ->leftJoin('pegawai_m as dokter', 'dokter.id', 'apr.objectpegawaifk')
            ->leftJoin('pegawai_m as pg', 'pg.id', 'pd.objectpegawaifk')
            ->join('ruangan_m as ruang', 'ruang.id', 'pd.objectruanganlastfk')
            ->join('ruangan_m as ru', 'ru.id', 'rk.objectruanganfk')
            ->leftJoin('jeniskelamin_m as jk', 'jk.id', 'pasien.objectjeniskelaminfk')
            ->leftJoin('kebangsaan_m as kbs', 'kbs.id', 'pasien.objectkebangsaanfk')
            ->leftJoin('kelompokpasien_m as kp', 'kp.id', 'apr.objectkelompokpasienfk')
            ->leftJoin('pemakaianasuransi_t as pa', function ($join) {
                $join->on('pa.nosep', '=', 'rk.nosepasal');
            })
            ->where('apr.kdprofile', $this->kdProfile)
            ->where('apr.statusenabled', true)
            ->where('rk.nobukti', $nobukti)
            ->groupBy(
                'rk.norec',
                'rk.nocmfk',
                'rk.objectpegawaifk',
                'rk.objectruanganfk',
                'rk.kdprofile',
                'rk.nosurat',
                'rk.typekontrol',
                'rk.tglkontrol',
                'rk.tglentry',
                'rk.nosepasal',
                'rk.isterbitsep',
                'rk.nokartu',
                'rk.jenispelayanan',
                'rk.terapi',
                'rk.catatan',
                'rk.diagnosaakhir',
                'rk.indikasikontrol',
                'rk.updated_at',
                'rk.nobukti',
                'rk.created_at',
                'kp.kelompokpasien',
                'pg.namalengkap',
                'ruang.reportdisplay',
                'pasien.namapasien',
                'apr.jam',
                'pasien.tgllahir',
                'jk.jeniskelamin',
                'ruang.namaruangan',
                'dokter.namalengkap',
                'apr.noantrianpoli',
                'apr.norec',
                'kbs.name',
                'apr.noreservasi',
                'apr.tanggalreservasi',
                'apr.type',
                'apr.tipepasien',
                'apr.jenis',
                'apr.namapasien',
                'pasien.nocm',
                'ru.namaruangan',
                'apr.tglinput',
                'pasien.nobpjs',
                'pa.diagawal_nama',
                'pa.nosurat',
                'pa.nosep',
                'pd.tglregistrasi',
                'pd.objectkelompokpasienlastfk',
                'pd.nocmfk',
                'pd.norec',
                DB::raw("pd.tglregistrasi - INTERVAL '1 DAY'"),
                DB::raw("to_char(pd.tglregistrasi,'yyyy-mm-dd')"),
                DB::raw("TO_CHAR(age(pasien.tgllahir), 'YY thn MM Bulan DD Hari')")
            )
            ->orderBy('rk.tglentry', 'desc')
            ->orderBy('apr.tanggalreservasi', 'desc');

        $query = $query->get();

        // dd($query[0]);

        if ($query[0]->objectkelompokpasienlastfk == 2) {
            $objetoRequests = new \Illuminate\Http\Request();
            $objetoRequests['url'] = "SEP/" . $query[0]->nosep;
            $objetoRequests['method'] = "GET";
            $objetoRequests['data'] = null;
            $cariSEP = $this->bridgingBPJSCtrl->bpjsTools($objetoRequests, true);
            $responseSEPVc = json_decode(json_encode($cariSEP, false));
            // dd($responseSEPVc);
            if (empty($responseSEPVc->response)) {
                echo '
                <script language="javascript">
                    window.alert("Terjadi kesalahan");
                    window.close()
                </script>';
                die;
            }

            $res['diagnosa'] = $responseSEPVc->response->diagnosa;
            $res['dpjp'] = $responseSEPVc->response->dpjp->nmDPJP;
        }


        // if($tglentry){
        //     $tglkontrol = $tglentry[0]->tglregistrasi;
        // } else{
        if ($query[0]->nobpjs != null) {

            $objetoRequest = new \Illuminate\Http\Request();
            $objetoRequest['url'] = "RencanaKontrol/ListRencanaKontrol/Bulan/" . date('m', strtotime($query[0]->tglentry)) . "/Tahun/" . date('Y', strtotime($query[0]->tglentry)) . "/Nokartu/" . $query[0]->nobpjs . "/filter/2";

            $objetoRequest['method'] = "GET";
            $objetoRequest['data'] = null;
            $cariSPRI = $this->bridgingBPJSCtrl->bpjsTools($objetoRequest, true);
            $responseSPRIVc = json_decode(json_encode($cariSPRI, false));

            // dd($responseSPRIVc);

            if (isset($responseSPRIVc->metaData)) {
                // dd('Halo');
                for ($q = 0; $q < count($responseSPRIVc->response->list); $q++) {
                    $element = $responseSPRIVc->response->list[$q];
                    if ($element->tglRencanaKontrol == $query[0]->tglkontrol) {
                        $nokontrol = $element->noSuratKontrol;
                        $tglkontrol = $element->tglTerbitKontrol;
                        // dd($tglkontrol);
                    }
                }
            }

        }

        if ($tglkontrol == '') {
            $nocmfk = $query[0]->nocmfk;
            $tglregistrasi = $query[0]->tglregistrasi;

            $tglentry = DB::select(DB::raw("select to_char(tglregistrasi,'yyyy-mm-dd') tglregistrasi from pasiendaftar_t
                where nocmfk = '$nocmfk'
                and statusenabled = true
                and tglregistrasi::date < '$tglregistrasi'::date
                order by tglregistrasi desc limit 1"));

            if ($tglentry) {
                $tglkontrol = $tglentry[0]->tglregistrasi;
            }
        }
        // }



        $qrcode = base64_encode(QrCode::format('svg')->size(200)->errorCorrection('H')->generate("https://simrsbm-test.baliprov.go.id/service/cetak-rencana-kontrol-klaim-poli?norec_pd=" . $query[0]->norec_pd));

        // var_dump($query);

        $res['data'] = $query;
        $res['nokontrol'] = $nokontrol;
        $res['tglkontrol'] = $tglkontrol;
        // dd($res['tglkontrol']);

        $res['qrcode'] = $qrcode;
        $blade = 'report.registrasi.rencana-kontrol-poli';
        // $qrcode = base64_encode(QrCode::format('svg')->size(45)->generate($res['sep']->peserta->noKartu));
        if ($res['pdf'] == 'true') {
            $pdf = App::make('dompdf.wrapper');
            $pdf->setpaper('A4', 'landscape');
            $pdf->loadView(
                $blade,
                array(
                    'profile' => $profile,
                    'pageWidth' => $pageWidth,
                    'res' => $res,
                    'pdf' => true,
                )
            );
            return $pdf->stream();
        }
        if (isset($r['storage'])) {
            $res['storage'] = true;
            $pdf = App::make('dompdf.wrapper');
            $pdf->setpaper('A4', 'landscape');
            // $pdf->setpaper([0, 0, 841.89, 423.15]);
            $pdf->loadView(
                $blade,
                array(
                    'profile' => $profile,
                    'pageWidth' => $pageWidth,
                    'res' => $res,
                    'pdf' => true
                )
            );
            return $pdf->stream();
        }
        $pdf = false;
        return view($blade, compact('profile', 'pageWidth', 'res', 'pdf'));
    }
    public function cetakSuratKontrol(Request $request)
    {
        $noreg = $request['noregistrasi'];
        $norec = $request['norec'];
        $profile = DB::table('profile_m')->where('kdprofile', $this->kdProfile)->first();
        $kdJenisSurat = (int) $this->settingFix('SuratKeteranganKontrol', $profile->id);

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
            pg.nosip
            FROM suratketerangan_t sk
            INNER JOIN pasiendaftar_t pd on pd.norec = sk.pasiendaftarfk
            INNER JOIN pasien_m pm on pm.id = pd.nocmfk
            LEFT JOIN jeniskelamin_m jk on pm.objectjeniskelaminfk = jk.id
            LEFT JOIN pekerjaan_m pj on pj.id = pm.objectpekerjaanfk
            LEFT JOIN alamat_m al on pm.id = al.nocmfk
            LEFT JOIN pegawai_m pg on pg.id = sk.dokterfk
            LEFT JOIN pegawai_m pg2 on pg2.id = sk.pegawaifk
            LEFT JOIN jenispegawai_m jp on jp.id = pg2.objectjenispegawaifk
            WHERE pd.noregistrasi = '$noreg'
            AND sk.norec = '$norec'
            AND jenissuratfk = '$kdJenisSurat'
            order by sk.tglsurat desc
        "))->first();

        $dataImg = DB::connection('mongodb')
            ->table('TandaTangan')
            ->where('pegawaifk', (int) $identitas->dokterfk)
            ->where('statusenabled', true)
            ->where('kdprofile', (int) $this->kdProfile)
            ->first();

        $img = null;
        if (!empty($dataImg)) {
            $img = $dataImg['ttd'];
        }

        $dataReport = array(
            'namaprofile' => $profile->namalengkap,
            'alamat' => $profile->alamatlengkap,
            'identitas' => $identitas,
            'ttdimg' => $img
        );
        if ($request['pdf']) {
            $pdf = App::make('dompdf.wrapper');
            $pdf->loadView(
                'report.emr.suratketerangankontrol',
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
        }
        return view(
            'report.emr.suratketerangankontrol',
            compact('dataReport', 'pageWidth', 'profile')
        );
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

    public function cetakSPRI(Request $request)
    {
        $req = $request->all();

        $profile = collect(DB::select("
            select * from profile_m where id = $this->kdProfile limit 1
            "))->first();

        $dataImg = DB::connection('mongodb')
            ->table('TandaTangan')
            ->where('pegawaifk', (int) $req['iddok'])
            ->where('statusenabled', true)
            ->where('kdprofile', (int) $this->kdProfile)
            ->first();

        $img = null;
        if (!empty($dataImg)) {
            $img = $dataImg['ttd'];
        }

        $pageWidth = 950;
        $dataReport = array(
            'datas' => $req,
            'ttdimg' => $img
        );
        $pdf = App::make('dompdf.wrapper');

        $pdf->loadView(
            'report.registrasi.cetak-skdp',
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
        // return view('report.registrasi.cetak-skdp',
        //     compact('dataReport', 'pageWidth','profile'));
    }

    public function cetakResumeMedis(Request $request)
    {

        $profile = DB::table('profile_m')->where('kdprofile', $this->kdProfile)->first();

        $pageWidth = 950;

        $dataReport = array(
            'namaprofile' => $profile->namalengkap,
            'alamat' => $profile->alamatlengkap,
        );

        if ($request['pdf']) {
            $pdf = App::make('dompdf.wrapper');
            $pdf->setPaper('A4', 'portrait');
            $pdf->loadView(
                'report.emr.resumemedis',
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
        }
        return view(
            'report.emr.resumemedis',
            compact('dataReport', 'pageWidth', 'profile')
        );
    }

    public function cetakRujukan(Request $request)
    {
        $req = $request->all();

        $profile = collect(DB::select("
            select * from profile_m where id = $this->kdProfile limit 1
            "))->first();



        $pageWidth = 950;
        $dataReport = array(
            'datas' => $req,
        );
        $dataReport['datas']['namappkRumahSakit'] = $this->settingFix('BPJS_namaPPKRujukan');

        $pdf = App::make('dompdf.wrapper');

        $pdf->loadView(
            'report.registrasi.cetak-rujukan',
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
    }


    public function getResep(Request $request)
    {
        $tindakanResep = DB::table('pelayananpasien_t as pp')
            ->join('antrianpasiendiperiksa_t as apd', 'apd.norec', '=', 'pp.noregistrasifk')
            ->join('produk_m as prd', 'prd.id', '=', 'pp.produkfk')
            ->join('ruangan_m as ru', 'ru.id', '=', 'apd.objectruanganfk')
            ->join('strukresep_t as sr', function ($join) {
                $join->on('sr.norec', '=', 'pp.strukresepfk')
                    ->whereNull('sr.orderfk');
            })
            ->select(
                'prd.namaproduk',
                'pp.tglpelayanan',
                'ru.namaruangan',
                'pp.strukresepfk',
                'pp.jumlah',
                DB::raw("
                (
                    (pp.hargasatuan  - case when pp.hargadiscount is null then 0 else pp.hargadiscount end)
                     * pp.jumlah)
                + (case when pp.jasa is not null then pp.jasa else 0 end)

                 as total
                 ,'Selesai' as status")
            )
            ->distinct()
            ->whereNull('pp.strukorderfk')
            ->where('pp.statusenabled', true)
            ->where('pp.kdprofile', $this->kdProfile)
            ->where('apd.noregistrasifk', $request['norec_pd'])
            ->orderByDesc('pp.tglpelayanan')
            ->get();

        $set = json_decode($this->settingFix('kelompokTransaksiOrderResep'));
        $orderResep = DB::table('orderpelayanan_t as op')
            ->join('strukorder_t as so', 'so.norec', '=', 'op.strukorderfk')
            ->join('produk_m as prd', 'prd.id', '=', 'op.objectprodukfk')
            ->join('ruangan_m as ru', 'ru.id', '=', 'op.objectruanganfk')
            ->select(
                'prd.namaproduk',
                'so.tglorder as tglpelayanan',
                'ru.namaruangan',
                'so.norec as strukresepfk',
                'op.jumlah',
                DB::raw("

                    (
                        (op.hargasatuan  - case when op.hargadiscount is null then 0 else op.hargadiscount end)
                         * op.jumlah)

                     as total
                     ,'Pending' as status")
            )
            ->distinct()
            ->where('so.statusenabled', true)
            ->where('so.kdprofile', $this->kdProfile)
            ->where('so.noregistrasifk', $request['norec_pd'])
            ->where('so.objectkelompoktransaksifk', $set->objectkelompoktransaksifk)
            ->where('so.statusorder', $this->settingFix('statusMenungguApotik'))
            ->orderByDesc('so.tglorder')
            ->get();

        $resep = [];
        foreach ($tindakanResep as $items) {
            if ($items->strukresepfk == null) {
                $tindakan[] = $items;
            } else {
                $resep[] = $items;
            }
        }
        foreach ($orderResep as $items) {
            $resep[] = $items;
        }

        return $this->respond($resep);
    }

    public function getMetodeEdukasi()
    {
        $data["metode"] = [
            "Diskusi",
            "Audio Visual",
            "Demonstrasi",
            "Lembar Balik",
            "Ceramah",
            "Booklet",
            "Praktek Langsung",
            "Leaflet"
        ];
        $data["response"] = [
            "Tidak respon sama sekali (tidak ada antusiasme dan keinginan belajar)",
            "Tidak paham (ingin belajar tapi kesulitan mengerti)",
            "Paham hal yang di ajarkan, tapi tidak bisa menjelaskan sendiri",
            "Dapat menjelaskan apa yang telah diajarkan, tapi harus dibantu edukator",
            "Dapat menjelaskan apa yang di ajarkan tanpa dibantu"
        ];
        return $data;
    }

    public function cetakFormulirTranstorachaEchoBayi(Request $request)
    {
        $datas = $this->init($request);
        $data = $datas['datas'];
        $pasien = $datas['pasien'];
        // $sonograph = $datas['sonographer'];
        $profile = $this->profile();

        // dd(compact('profile', 'data', 'pasien'));

        return view('report.emr.formulir-transtoracha-echo-bayi', compact('profile', 'data', 'pasien'));
    }

    public function cetakFormulirTranstorachaEchoDewasa(Request $request)
    {
        $datas = $this->init($request);
        $data = $datas['datas'];
        $pasien = $datas['pasien'];
        // $sonograph = $datas['sonographer'];
        $profile = $this->profile();

        // dd(compact('profile', 'data', 'pasien'));

        return view('report.emr.formulir-transtoracha-echo-dewasa', compact('profile', 'data', 'pasien'));
    }

    public function cetakFormulirLowerExtermityDuplexUltrasoundUSGDoppler(Request $request)
    {
        $datas = $this->init($request);
        $data = $datas['datas'];
        $pasien = $datas['pasien'];
        // $sonograph = $datas['sonographer'];
        $profile = $this->profile();

        // dd(compact('profile', 'data', 'pasien'));

        return view('report.emr.formulir-LowerExtermityDuplexUltrasoundUSGDoppler', compact('profile', 'data', 'pasien'));
    }

    public function cetakFormulirCarotidDuplexUltrasound(Request $request)
    {
        $datas = $this->init($request);
        $data = $datas['datas'];
        $pasien = $datas['pasien'];
        $profile = $this->profile();

        // dd(compact('profile', 'data', 'pasien'));

        return view('report.emr.formulir-carotid-duplex-ultrasound', compact('profile', 'data', 'pasien'));
    }

    public function cetakFormulirHasilPemeriksaanEkg(Request $request)
    {
        $datas = $this->init($request);
        $data = $datas['datas'];
        $pasien = $datas['pasien'];
        $profile = $this->profile();

        // dd(compact('profile', 'data', 'pasien'));

        return view('report.emr.formulir-hasil-pemeriksaan-ekg', compact('profile', 'data', 'pasien'));
    }

    public function cektakFormulirBuktiPelayananCanggih(Request $request)
    {
        $datas = $this->init($request);
        $data = $datas['datas'];
        $pasien = $datas['pasien'];
        $collection = $request['collection'];

        $dataQR = [
            "nocm" => isset($data['pasien']['nocm']) ? $data['pasien']['nocm'] : '',
            "namaruangan" => $data['registrasi']['namaruangan'],
            "dpjp" => $data['registrasi']['dokter'],
            "tglberkunjung" => date("Y-m-d", strtotime($data['jamKunjungan'])),
            "tglpulang" => date("Y-m-d", strtotime($data['jamSelesai'])),
            "created_at" => date("Y-m-d", strtotime($data['created_at'])),
            "type" => $collection,
        ];
        $stringQR = $collection . ';' . $data['_id'];
        $encryptQR = base64_encode($stringQR);

        // Prevent link to encoded
        $iswna = ($request !== null && isset($request['wna'])) ? $request['wna'] : false;
        $pasien = $dataQR;
        $pasien['tte'] = route('dokumen.signature') . '?key=' . $encryptQR . '&a=' . $iswna;

        $profile = $this->profile();

        $canvasPNG = new Png($pasien['tte']);
        $canvasPNG->setMargin(5);
        $canvasPNG->setSize(130);

        $canvasPNG->setErrorCorrectionLevel(new ErrorCorrectionLevelLow);
        $writer = new PngWriter();
        $resultBarcode = $writer->write($canvasPNG);

        // Encode to base64 for display
        $qrcode = base64_encode($resultBarcode->getString());

        return view('report.emr.formulir-bukti-pelayanan-canggih', compact('profile', 'data', 'pasien', 'qrcode'));
    }

    public function generateAllRingkasan(Request $req)
    {
        ini_set('max_execution_time', 10000);
        $tglDef1 = '2025-01-05';
        $tglDef2 = '2025-01-08';
        if (isset($req['tglAwal']) && $req['tglAwal'] != '') {
            $tglDef1 = $req['tglAwal'];
        }

        if (isset($req['tglAkhir']) && $req['tglAkhir'] != '') {
            $tglDef2 = $req['tglAkhir'];
        }

        $filterDepart = explode(',', $this->settingFix('kdDepartemenRawatJalanFix'));
        // return $filterDepart;
        $dataPasiens = DB::table('pasiendaftar_t as pd')
            ->select('pd.norec', 'ru.namaruangan', 'apd.iskonsul', 'pd.nocmfk', 'apd.norec as norecapd', 'pd.tglregistrasi', 'pd.noregistrasi')
            ->leftJoin('antrianpasiendiperiksa_t as apd', 'apd.noregistrasifk', 'pd.norec')
            ->leftJoin('ruangan_m as ru', 'ru.id', 'apd.objectruanganfk')
            // ->where('pd.statusenabled', true)
            // ->where('apd.statusenabled', true)
            ->whereIn('ru.objectdepartemenfk', [18, 9])
            ->whereIn('ru.id', [281, 366, 207, 407])
            ->whereBetween('pd.tglregistrasi', [$tglDef1, $tglDef2])
            // ->whereDate('pd.tglregistrasi', '<', $tglDef2) // <-- one Day
            // ->whereDate('pd.tglregistrasi', '>', $tglDef1)
            // ->whereDate('pd.tglregistrasi', '<', $tglDef1) // <-- Full Generate
            ->get();

        // return $dataPasiens;
        $ringkasans = DB::connection('mongodb')
            ->table('RingkasanKeluar')
            ->select('registrasi')
            ->whereIn('registrasi.objectruanganfk', [281, 366, 207, 407])
            ->whereBetween('registrasi.tglregistrasi', [$tglDef1 . " 00:00:00", $tglDef2 . " 23:59:59"])
            ->get()
            ->pluck('registrasi')
            ->pluck('norec_pd')
            ->toArray();

        // return count($dataPasiens) - count($ringkasans);

        // $cppt = DB::connection('mongodb')
        // ->table('CatatanPerkembanganPasienTerintegrasi')
        // ->where('pasien.nocmfk', $dtpasien->nocmfk)
        // ->where('profile.kdprofile', $this->kdProfile)
        // ->where('registrasi.norec_pd', '=', $dtpasien->norec)
        // ->where('statusenabled', true)
        // ->orderBy('created_at', 'DESC')
        // ->get();

        // $cpptDetails = DB::connection('mongodb')
        // ->table('CPPTDetail')
        // ->where('nocmfk', $dtpasien->nocmfk)
        // ->where('kdprofile', $this->kdProfile)
        // ->where('statusenabled', true)
        // ->where('norec_pd', '=', $dtpasien->norec)
        // ->where('flag', '=', 'dokter')
        // ->orderBy('created_at', 'DESC')
        // ->get();

        // if(count($cppt) > 0) {
        //     $cppt = $cppt->toArray();
        //     $cpptDetails = $cpptDetails->toArray();
        //     foreach ($cppt as $k => $c) {
        //         $cppt[$k]['details'] = [];
        //         foreach ($cpptDetails as $z => $x) {
        //             if ($c['emrpasienfk'] == $cpptDetails[$z]['emrpasienfk']) {
        //                 $cppt[$k]['details'][] = $cpptDetails[$z];
        //             }
        //         }
        //     }
        //     // return $cppt;
        //     if(count($cppt[0]['details']) > 0) {
        //         $used = $cppt[0];
        //     }else {
        //         foreach($cppt as $nk => $nd) {
        //             if(count($nd['details']) > 0) {
        //                 $used = $nd;
        //                 break;
        //             }
        //         }
        //     }
        // }

        // ->pluck('noregistrasi');
        // return $ringkasans;
        // return count($dataPasiens) - count($ringkasans);
        $l = 0;
        return $dataPasiens;
        foreach ($dataPasiens as $dtpasien) {
            // if($dtpasien->norec == "04e8ef44-6991-4946-9146-8270bb2cf580") return $dtpasien;
            // return "gaada";
            if (!in_array($dtpasien->norec, $ringkasans)) {

                $cppt = DB::connection('mongodb')
                    ->table('CatatanPerkembanganPasienTerintegrasi')
                    ->where('registrasi.norec_pd', '=', $dtpasien->norec)
                    ->get();

                $asmed = DB::connection('mongodb')
                    ->table('AsesmenMedisRawatJalan')
                    ->where('pasien.nocmfk', $dtpasien->nocmfk)
                    ->where('profile.kdprofile', $this->kdProfile)
                    ->where('statusenabled', true)
                    ->where('registrasi.norec_pd', '=', $dtpasien->norec)
                    ->whereNull('namatemplate')
                    ->orderByDesc('created_at')
                    ->get();
                return $asmed;
                $l++;
            }
        }
        return $l;
        $rawBackup = [];
        $lastNorecPD = null;
        DB::beginTransaction();
        // try {
        //     foreach($dataPasiens as $kdtp => $dtpasien) {
        //         // continue;
        //         // if($lastNorecPD != $dtpasien->norec) {
        //         $notCPPT = [
        //             "KEMOTERAPI",
        //             "POLI KEDOKTERAN NUKLIR",
        //             "POLI FISIOTERAPI",
        //             "HEMODIALISIS"
        //         ];
        //         $collection = null;
        //         // TAKE DATA FROM CPPT AND ASSESMENT MEDIS
        //         $used = null;
        //         if(!in_array(strtoupper($dtpasien->namaruangan), $notCPPT)) {
        //             $getRingkasan = DB::connection('mongodb')
        //             ->table('RingkasanKeluar')
        //             ->where('registrasi.norec_pd', $dtpasien->norec)
        //             ->where('registrasi.norec_apd', $dtpasien->norecapd)
        //             ->where('profile.kdprofile', $this->kdProfile)
        //             ->latest()
        //             ->first();
        //             // return $getRingkasan;

        //             if(!isset($getRingkasan)) {
        //                 $check = DB::table('pasiendaftar_t')
        //                 ->where('nocmfk', $dtpasien->nocmfk)
        //                 ->limit(2)
        //                 ->count();

        //                 $cppt = DB::connection('mongodb')
        //                 ->table('CatatanPerkembanganPasienTerintegrasi')
        //                 ->where('pasien.nocmfk', $dtpasien->nocmfk)
        //                 ->where('profile.kdprofile', $this->kdProfile)
        //                 ->where('statusenabled', true)
        //                 ->where('registrasi.norec_pd', '=', $dtpasien->norec)
        //                 ->orderBy('created_at', 'DESC')
        //                 ->get();

        //                 $cpptDetails = DB::connection('mongodb')
        //                 ->table('CPPTDetail')
        //                 ->where('nocmfk', $dtpasien->nocmfk)
        //                 ->where('kdprofile', $this->kdProfile)
        //                 ->where('statusenabled', true)
        //                 ->where('norec_pd', '=', $dtpasien->norec)
        //                 ->where('flag', '=', 'dokter')
        //                 ->orderBy('created_at', 'DESC')
        //                 ->get();

        //                 if(count($cppt) > 0) {
        //                     $cppt = $cppt->toArray();
        //                     $cpptDetails = $cpptDetails->toArray();
        //                     foreach ($cppt as $k => $c) {
        //                         $cppt[$k]['details'] = [];
        //                         foreach ($cpptDetails as $z => $x) {
        //                             if ($c['emrpasienfk'] == $cpptDetails[$z]['emrpasienfk']) {
        //                                 $cppt[$k]['details'][] = $cpptDetails[$z];
        //                             }
        //                         }
        //                     }
        //                     // return $cppt;
        //                     if(count($cppt[0]['details']) > 0) {
        //                         $used = $cppt[0];
        //                     }else {
        //                         foreach($cppt as $nk => $nd) {
        //                             if(count($nd['details']) > 0) {
        //                                 $used = $nd;
        //                                 break;
        //                             }
        //                         }
        //                     }
        //                 }

        //                 // if > 1 = CPPT else Assesment ( kunj 1 ) but still take CPPT if asmed and cppt
        //                 if($check > 1) {
        //                     if( count($cppt) > 0 && count($cppt[0]['details']) > 0 ) {
        //                         // SEND DATA
        //                         $tgl = date('Y-m-d H:i:s', strtotime($used['details'][0]['tgl']));
        //                         $object = [
        //                             "detailDS" => [
        //                                 [
        //                                     "no" => 1,
        //                                     "TADiagnosaSekunder" => ""
        //                                 ]
        //                             ],
        //                             "detailDT" => [
        //                                 [
        //                                     "no" => 1,
        //                                     "TADeskripsiTindakan" => ""
        //                                 ]
        //                             ],
        //                             "waktuTataLaksana" => $tgl,
        //                             "waktuKontrol" => $tgl,
        //                             "jamKedatangan" => $tgl,
        //                             "jamAsesmenAwal" => $tgl,
        //                             "riwayatkeluar" => $used['riwayatkeluar'] ?? null,
        //                             "statuskeluar" => $used['statuskeluar'] ?? null,
        //                             "perlukontrol" => $used['perlukontrol'] ?? null,
        //                             "tanggalKedatangan" => $tgl,
        //                             "dpjpUtama" => $used['details'][0]['dokterDPJP'],
        //                             "TAKondisiSaatMasuk" => '',
        //                             "TADiagnosisPrimer" => $used['details'][0]['A'],
        //                             "gcse" => $used['gcse'] ?? null,
        //                             "gcsv" => $used['gcsv'] ?? null,
        //                             "gcsm" => $used['gcsm'] ?? null,
        //                             "kesanUmum" => $used['keadaanumumobgyn'] ?? null,
        //                             "nadi" => $used['nadi'] ?? null,
        //                             "nafas" => $used['nafas'] ?? null,
        //                             "celcius" => $used['celcius'] ?? null,
        //                             "tekananDarah" => $used['tekananDarah'] ?? null,
        //                             "anamnesis" => $used['details'][0]['S'] ?? null,
        //                             "pemeriksaanfisik" => $used['details'][0]['O'] ?? null,
        //                             "intruksi" => $used['details'][0]['P'] ?? null,
        //                             "hasilpemeriksaanpenunjang" => ''
        //                         ];

        //                         $object['nocm'] = $used['pasien']['nocm'];
        //                         $object['pasien'] = $used['pasien'];
        //                         $object['registrasi'] = $used['registrasi'];
        //                         $object['user_input'] = $used['user_input'];
        //                         $object['profile'] = $used['profile'];

        //                         $sendData = [
        //                             'id' => '',
        //                             'norec_emr' => '',
        //                             'collection' => 'RingkasanKeluar',
        //                             'url_form' => 'module-emr-profile-pasien-page-emr-ringkasan-keluar',
        //                             'name_form' => 'Ringkasan Keluar',
        //                             'jenis_emr' => 'asesmen_medis',
        //                             'data' => $object
        //                         ];

        //                         $res = $this->saveForRingkasan($sendData);
        //                     }
        //                 }else {
        //                     $asmed = DB::connection('mongodb')
        //                     ->table('AsesmenMedisRawatJalan')
        //                     ->where('pasien.nocmfk', $dtpasien->nocmfk)
        //                     ->where('profile.kdprofile', $this->kdProfile)
        //                     ->where('statusenabled', true)
        //                     ->where('registrasi.norec_pd', '=', $dtpasien->norec)
        //                     ->whereNull('namatemplate')
        //                     ->orderByDesc('created_at')
        //                     ->get();

        //                     // DATA ASMED
        //                     if(count($asmed) > 0 && (count($cppt) > 0 && count($cppt[0]['details']) == 0) ) {
        //                         $used = $asmed[0]; //terbaru / latest / paling atas / everything
        //                         $tgl = date('Y-m-d H:i:s', strtotime($used['tanggalKedatangan']));
        //                         $dpjp = [
        //                             "value" => $used['user_input']['pegawaifk'],
        //                             "label" => $used['user_input']['namalengkap']
        //                         ];
        //                         $fisik = '';
        //                         $fisik .= !empty($used['celcius']) ? "Suhu : {$used['celcius']} °C\n" : "Suhu : -\n";
        //                         $fisik .= !empty($used['nadi']) ? "Nadi : {$used['nadi']} x/mnt\n" : "Nadi : -\n";
        //                         $fisik .= !empty($used['nafas']) ? "Pernafasan : {$used['nafas']} x/mnt\n" : "Pernafasan : -\n";
        //                         $fisik .= !empty($used['tekananDarah']) ? "Tekanan Darah : {$used['tekananDarah']} mmHg\n" : "Tekanan Darah : -\n";
        //                         $fisik .= !empty($used['tinggiBadan']) ? "Tinggi Badan : {$used['tinggiBadan']} Cm\n" : "Tinggi Badan : -\n";
        //                         $fisik .= !empty($used['beratBadan']) ? "Berat Badan : {$used['beratBadan']} Kg\n" : "Berat Badan : -\n";
        //                         $fisik .= !empty($used['spo2']) ? "SPO2 : {$used['spo2']} %\n" : '';

        //                         $object = [
        //                             "detailDS" => [
        //                                 [
        //                                     "no" => 1,
        //                                     "TADiagnosaSekunder" => ""
        //                                 ]
        //                             ],
        //                             "detailDT" => [
        //                                 [
        //                                     "no" => 1,
        //                                     "TADeskripsiTindakan" => ""
        //                                 ]
        //                             ],
        //                             "waktuTataLaksana" => $tgl,
        //                             "waktuKontrol" => $tgl,
        //                             "jamKedatangan" => $tgl,
        //                             "jamAsesmenAwal" => $tgl,
        //                             "riwayatkeluar" => $used['riwayatkeluar'] ?? null,
        //                             "statuskeluar" => $used['statuskeluar'] ?? null,
        //                             "perlukontrol" => $used['perlukontrol'] ?? null,
        //                             "tanggalKedatangan" => $tgl,
        //                             "dpjpUtama" => $dpjp,
        //                             "TAKondisiSaatMasuk" => '',
        //                             "TADiagnosisPrimer" => $used['TADiagnosa'],
        //                             "gcse" => $used['gcse'] ?? null,
        //                             "gcsv" => $used['gcsv'] ?? null,
        //                             "gcsm" => $used['gcsm'] ?? null,
        //                             "kesanUmum" => $used['keadaanumum'] ?? null,
        //                             "nadi" => $used['nadi'] ?? null,
        //                             "nafas" => $used['nafas'] ?? null,
        //                             "celcius" => $used['celcius'] ?? null,
        //                             "tekananDarah" => $used['tekananDarah'] ?? null,
        //                             "anamnesis" => $used['anamnesis'] ?? null,
        //                             "pemeriksaanfisik" => $fisik,
        //                             "intruksi" => $used['instruksiAsesmen'],
        //                             "hasilpemeriksaanpenunjang" => isset($used['hasilpemeriksaanpenunjang']) ? $used['hasilpemeriksaanpenunjang'] : ''
        //                         ];

        //                         $object['nocm'] = $used['pasien']['nocm'];
        //                         $object['pasien'] = $used['pasien'];
        //                         $object['registrasi'] = $used['registrasi'];
        //                         $object['user_input'] = $used['user_input'];
        //                         $object['profile'] = $used['profile'];

        //                         $sendData = [
        //                             'id' => '',
        //                             'norec_emr' => '',
        //                             'collection' => 'RingkasanKeluar',
        //                             'url_form' => 'module-emr-profile-pasien-page-emr-ringkasan-keluar',
        //                             'name_form' => 'Ringkasan Keluar',
        //                             'jenis_emr' => 'asesmen_medis',
        //                             'data' => $object
        //                         ];

        //                         $res = $this->saveForRingkasan($sendData);
        //                     }elseif(count($asmed) > 0 && (count($cppt) > 0 && count($cppt[0]['details']) > 0)) {
        //                         // DATA CPPT
        //                         $tgl = date('Y-m-d H:i:s', strtotime($used['details'][0]['tgl']));
        //                         $object = [
        //                             "detailDS" => [
        //                                 [
        //                                     "no" => 1,
        //                                     "TADiagnosaSekunder" => ""
        //                                 ]
        //                             ],
        //                             "detailDT" => [
        //                                 [
        //                                     "no" => 1,
        //                                     "TADeskripsiTindakan" => ""
        //                                 ]
        //                             ],
        //                             "waktuTataLaksana" => $tgl,
        //                             "waktuKontrol" => $tgl,
        //                             "jamKedatangan" => $tgl,
        //                             "jamAsesmenAwal" => $tgl,
        //                             "riwayatkeluar" => $used['riwayatkeluar'] ?? null,
        //                             "statuskeluar" => $used['statuskeluar'] ?? null,
        //                             "perlukontrol" => $used['perlukontrol'] ?? null,
        //                             "tanggalKedatangan" => $tgl,
        //                             "dpjpUtama" => $used['details'][0]['dokterDPJP'],
        //                             "TAKondisiSaatMasuk" => '',
        //                             "TADiagnosisPrimer" => $used['details'][0]['A'],
        //                             "gcse" => $used['gcse'] ?? null,
        //                             "gcsv" => $used['gcsv'] ?? null,
        //                             "gcsm" => $used['gcsm'] ?? null,
        //                             "kesanUmum" => $used['keadaanumumobgyn'] ?? null,
        //                             "nadi" => $used['nadi'] ?? null,
        //                             "nafas" => $used['nafas'] ?? null,
        //                             "celcius" => $used['celcius'] ?? null,
        //                             "tekananDarah" => $used['tekananDarah'] ?? null,
        //                             "anamnesis" => $used['details'][0]['S'] ?? null,
        //                             "pemeriksaanfisik" => $used['details'][0]['O'] ?? null,
        //                             "intruksi" => $used['details'][0]['P'] ?? null,
        //                             "hasilpemeriksaanpenunjang" => ''
        //                         ];

        //                         $object['nocm'] = $used['pasien']['nocm'];
        //                         $object['pasien'] = $used['pasien'];
        //                         $object['registrasi'] = $used['registrasi'];
        //                         $object['user_input'] = $used['user_input'];
        //                         $object['profile'] = $used['profile'];

        //                         $sendData = [
        //                             'id' => '',
        //                             'norec_emr' => '',
        //                             'collection' => 'RingkasanKeluar',
        //                             'url_form' => 'module-emr-profile-pasien-page-emr-ringkasan-keluar',
        //                             'name_form' => 'Ringkasan Keluar',
        //                             'jenis_emr' => 'asesmen_medis',
        //                             'data' => $object
        //                         ];

        //                         $res = $this->saveForRingkasan($sendData);
        //                     }else {
        //                         // DATA CPPT
        //                         if(count($cppt) > 0 && count($cppt[0]['details']) > 0) {
        //                             $tgl = date('Y-m-d H:i:s', strtotime($used['details'][0]['tgl']));
        //                             $object = [
        //                                 "detailDS" => [
        //                                     [
        //                                         "no" => 1,
        //                                         "TADiagnosaSekunder" => ""
        //                                     ]
        //                                 ],
        //                                 "detailDT" => [
        //                                     [
        //                                         "no" => 1,
        //                                         "TADeskripsiTindakan" => ""
        //                                     ]
        //                                 ],
        //                                 "waktuTataLaksana" => $tgl,
        //                                 "waktuKontrol" => $tgl,
        //                                 "jamKedatangan" => $tgl,
        //                                 "jamAsesmenAwal" => $tgl,
        //                                 "riwayatkeluar" => $used['riwayatkeluar'] ?? null,
        //                                 "statuskeluar" => $used['statuskeluar'] ?? null,
        //                                 "perlukontrol" => $used['perlukontrol'] ?? null,
        //                                 "tanggalKedatangan" => $tgl,
        //                                 "dpjpUtama" => $used['details'][0]['dokterDPJP'],
        //                                 "TAKondisiSaatMasuk" => '',
        //                                 "TADiagnosisPrimer" => $used['details'][0]['A'],
        //                                 "gcse" => $used['gcse'] ?? null,
        //                                 "gcsv" => $used['gcsv'] ?? null,
        //                                 "gcsm" => $used['gcsm'] ?? null,
        //                                 "kesanUmum" => $used['keadaanumumobgyn'] ?? null,
        //                                 "nadi" => $used['nadi'] ?? null,
        //                                 "nafas" => $used['nafas'] ?? null,
        //                                 "celcius" => $used['celcius'] ?? null,
        //                                 "tekananDarah" => $used['tekananDarah'] ?? null,
        //                                 "anamnesis" => $used['details'][0]['S'] ?? null,
        //                                 "pemeriksaanfisik" => $used['details'][0]['O'] ?? null,
        //                                 "intruksi" => $used['details'][0]['P'] ?? null,
        //                                 "hasilpemeriksaanpenunjang" => ''
        //                             ];

        //                             $object['nocm'] = $used['pasien']['nocm'];
        //                             $object['pasien'] = $used['pasien'];
        //                             $object['registrasi'] = $used['registrasi'];
        //                             $object['user_input'] = $used['user_input'];
        //                             $object['profile'] = $used['profile'];

        //                             $sendData = [
        //                                 'id' => '',
        //                                 'norec_emr' => '',
        //                                 'collection' => 'RingkasanKeluar',
        //                                 'url_form' => 'module-emr-profile-pasien-page-emr-ringkasan-keluar',
        //                                 'name_form' => 'Ringkasan Keluar',
        //                                 'jenis_emr' => 'asesmen_medis',
        //                                 'data' => $object
        //                             ];

        //                             $res = $this->saveForRingkasan($sendData);
        //                         }
        //                     }
        //                 }
        //             }
        //         }else {
        //             // HEMODIALISIS
        //             if(strtoupper($dtpasien->namaruangan) == 'HEMODIALISIS') {
        //                 // Because hemo will always make Asmed Hemo ( cenah )
        //                 // soo take one
        //                 $getRingkasan = DB::connection('mongodb')
        //                 ->table('RingkasanKeluar')
        //                 ->where('registrasi.norec_pd', $dtpasien->norec)
        //                 ->where('registrasi.norec_apd', $dtpasien->norecapd)
        //                 ->where('profile.kdprofile', $this->kdProfile)
        //                 ->latest()
        //                 ->first();

        //                 if(!isset($getRingkasan)) {
        //                     $used = DB::connection('mongodb')
        //                     ->table('AsesmenAwalMedisHemodialisa')
        //                     ->where('registrasi.norec_pd', $dtpasien->norec)
        //                     ->where('pasien.nocmfk', $dtpasien->nocmfk)
        //                     ->orderBy('created_at', 'desc')
        //                     ->first();

        //                     if(isset($used)) {
        //                         $dpjp = [
        //                             "value" => $used['user_input']['pegawaifk'],
        //                             "label" => $used['user_input']['namalengkap']
        //                         ];
        //                         $fisik = '';
        //                         $fisik .= !empty($used['TBcelciusTTV']) ? "Suhu : {$used['TBcelciusTTV']} °C\n" : "Suhu : -\n";
        //                         $fisik .= !empty($used['TBprTTV']) ? "Nadi : {$used['TBprTTV']} x/mnt\n" : "Nadi : -\n";
        //                         $fisik .= !empty($used['TBrrTTV']) ? "Pernafasan : {$used['TBrrTTV']} x/mnt\n" : "Pernafasan : -\n";
        //                         $fisik .= !empty($used['TBtekananDarahTTV']) ? "Tekanan Darah : {$used['TBtekananDarahTTV']} mmHg\n" : "Tekanan Darah : -\n";
        //                         $fisik .= !empty($used['TBSTinggiBadanSN']) ? "Tinggi Badan : {$used['TBSTinggiBadanSN']} Cm\n" : "Tinggi Badan : -\n";
        //                         $fisik .= !empty($used['TBSBeratBadanSaatIni']) ? "Berat Badan : {$used['TBSBeratBadanSaatIni']} Kg\n" : "Berat Badan : -\n";
        //                         $fisik .= !empty($used['TBnsao2TTV']) ? "SPO2 : {$used['TBnsao2TTV']} %\n" : '';
        //                         // SEND DATA
        //                         $tgl = date('Y-m-d H:i:s', strtotime($used['HjamAW']));
        //                         $object = [
        //                             "detailDS" => [
        //                                 [
        //                                     "no" => 1,
        //                                     "TADiagnosaSekunder" => ""
        //                                 ]
        //                             ],
        //                             "detailDT" => [
        //                                 [
        //                                     "no" => 1,
        //                                     "TADeskripsiTindakan" => ""
        //                                 ]
        //                             ],
        //                             "waktuTataLaksana" => $tgl,
        //                             "waktuKontrol" => $tgl,
        //                             "jamKedatangan" => $tgl,
        //                             "jamAsesmenAwal" => $tgl,
        //                             "riwayatkeluar" => null,
        //                             "statuskeluar" => null,
        //                             "perlukontrol" => null,
        //                             "tanggalKedatangan" => $tgl,
        //                             "dpjpUtama" => $dpjp,
        //                             "TAKondisiSaatMasuk" => '',
        //                             "TADiagnosisPrimer" => $used['TADiagnosis'] ?? '',
        //                             "gcse" => $used['TBeGCS'] ?? '',
        //                             "gcsv" => $used['TBvGCS'] ?? '',
        //                             "gcsm" => $used['TBmGCS'] ?? '',
        //                             "kesanUmum" => $used['CBKU'] ?? '',
        //                             "nadi" => $used['TBprTTV'] ?? '',
        //                             "nafas" => $used['TBrrTTV'] ?? '',
        //                             "celcius" => $used['TBcelciusTTV'] ?? '',
        //                             "tekananDarah" => $used['TBtekananDarahTTV'] ?? '',
        //                             "anamnesis" => $used['TAAnamnesis'] ?? '',
        //                             "pemeriksaanfisik" => $fisik,
        //                             "intruksi" => $used['TAInstruksi'] ?? '',
        //                             "hasilpemeriksaanpenunjang" => $used['TAhpp'] ?? ''
        //                         ];

        //                         $object['nocm'] = $used['pasien']['nocm'];
        //                         $object['pasien'] = $used['pasien'];
        //                         $object['registrasi'] = $used['registrasi'];
        //                         $object['user_input'] = $used['user_input'];
        //                         $object['profile'] = $used['profile'];

        //                         $sendData = [
        //                             'id' => '',
        //                             'norec_emr' => '',
        //                             'collection' => 'RingkasanKeluar',
        //                             'url_form' => 'module-emr-profile-pasien-page-emr-ringkasan-keluar',
        //                             'name_form' => 'Ringkasan Keluar',
        //                             'jenis_emr' => 'asesmen_medis',
        //                             'data' => $object
        //                         ];

        //                         $res = $this->saveForRingkasan($sendData);
        //                         // return $res;
        //                     }
        //                 }
        //             }else if(strtoupper($dtpasien->namaruangan) == 'POLI KEDOKTERAN NUKLIR') {
        //                 // FIELD NYA BELUM TAU
        //             }else if (strtoupper($dtpasien->namaruangan) == 'KEMOTERAPI') {
        //                 // Belum tau ambil form yg mana
        //             }
        //         }

        //         if(isset($res)) {
        //             $res = json_decode($res, true);
        //         }
        //         $lastNorecPD = $dtpasien->norec;
        //         $rawBackup[$kdtp]['data_pasien'] = $dtpasien;
        //         $rawBackup[$kdtp]['id_collection'] = isset($res) ? $res['response']['id'] : (isset($getRingkasan) ? $getRingkasan['id'] : '');
        //         $rawBackup[$kdtp]['note'] = "IF ID COLLECTION WAS NULL OR '' WHICH MEAN THE MAIN FORM LIKE CPPT, ASMED ETC WASNT CREATED YET!";
        //         // if(isset($used)) {

        //         // }
        //     }
        //     DB::commit();
        //     return $rawBackup;
        // } catch (\Exception $e) {
        //     DB::rollback();
        //     return $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine();
        //     // Throw new \Exception();
        // }

        // }

        // return $dataPasiens;
    }

    public function saveForRingkasan($r)
    {
        DB::beginTransaction();
        try {
            $data = $r['data'];
            $invalidDates = $this->findInvalidDates($data);
            if ($invalidDates > 0) {
                $transMessage = "Simpan Gagal Format Tanggal Tidak Sesuai !";
                DB::rollBack();
                $result = array(
                    "status" => 400,
                    "result" => array(
                        'invalid_date_count' => $invalidDates
                    )
                );
                return $this->respond($result['result'], $result['status'], $transMessage);
            }
            $now = $data['waktuTataLaksana'];
            $registrasi = $data['registrasi'];
            $pasien = $data['pasien'];
            // return $r;
            if ($r['norec_emr'] == '') {
                $noemr = $this->SEQUENCE(new EMRPasien(), 'noemr', 15, 'MR' . date('ym') . '/', $this->kdProfile);
                $EMR = new EMRPasien();
                $norec = $EMR->generateNewId();
                $EMR->norec = $norec;
                $EMR->kdprofile = $this->kdProfile;
                $EMR->statusenabled = true;
                $EMR->noregistrasifk = $registrasi['norec_pd'];
                $EMR->noregistrasi = $registrasi['noregistrasi'];
            } else {
                // return $r->all();
                $EMR = EMRPasien::where('norec', $r['norec_emr'])
                    // ->where('nocm', $pasien['nocm'])
                    ->where('kdprofile', $this->kdProfile)
                    ->first();
                // return $EMR;
                $noemr = $EMR['noemr'];
                $norec = $EMR['norec'];
            }

            $EMR->noemr = $noemr;
            $EMR->emrfk = 0;
            $EMR->nocm = $pasien['nocm'];
            $EMR->nocmfk = $pasien['nocmfk'];
            $EMR->namapasien = $pasien['namapasien'];
            $EMR->jeniskelamin = $pasien['jeniskelamin'];
            $EMR->umur = $pasien['umur'];
            $EMR->tgllahir = $pasien['tgllahir'];
            $EMR->notelepon = $pasien['nohp'];
            $EMR->alamat = $pasien['alamatlengkap'];
            $EMR->kelompokpasien = $registrasi['kelompokpasien'];
            $EMR->tglregistrasi = $registrasi['tglregistrasi'];
            $EMR->norec_apd = $registrasi['norec_apd'];
            $EMR->namakelas = $registrasi['namakelas'];
            $EMR->namaruangan = $registrasi['namaruangan'];
            $EMR->jenisemr = $r['jenis_emr'];
            $EMR->pegawaifk = $data['user_input']['pegawaifk'];
            $EMR->tglemr = $now;
            $EMR->save();
            // MONGO
            $data = $r['data'];
            if (isset($data['nocm'])) {
                unset($data['nocm']);
            }

            // $data['user_input'] = $data;
            // $data['profile'] = array(
            //     'kdprofile' => $this->kdProfile,
            //     'namaprofile' => $this->getProfile()->namalengkap,
            // );
            if (isset($r['userBy'])) {
                $data['userBy'] = $r['userBy'];
            }
            $data['statusenabled'] = true;
            $data['noemr'] = $EMR->noemr;
            $data['emrpasienfk'] = $norec;

            if ($r['id'] == '') {

                $data['id'] = $this->Uuid4();
                $data['created_at'] = $now;
                $data['updated_at'] = null;
                DB::connection('mongodb')
                    ->table($r['collection'])
                    ->insert($data);
            } else {
                $data['updated_at'] = $now;
                $update = DB::connection('mongodb')
                    ->table($r['collection'])
                    ->where('id', $r['id'])
                    ->update($data);
            }

            $formExist = DB::connection('mongodb')
                ->table('#ResumeEMR')
                ->where('emrpasienfk', $norec)
                ->where('table', $r['collection'])
                ->where('noregistrasifk', $registrasi['norec_pd'])
                ->first();

            if (empty($formExist)) {
                $resume = array(
                    'id' => $this->Uuid4(),
                    'kdprofile' => $this->kdProfile,
                    'statusenabled' => true,
                    'noregistrasifk' => $registrasi['norec_pd'],
                    'nocmfk' => $pasien['nocmfk'],
                    'emrpasienfk' => $norec,
                    'table' => $r['collection'],
                    'last_update' => $now,
                    'author' => $data['user_input']['namalengkap'],
                    'url_form' => $r['url_form'],
                    'namaemr' => $r['name_form'],
                    'noemr' => $EMR->noemr,
                    'icon' => isset($r['icon']) ? $r['icon'] : null,
                );

                DB::connection('mongodb')
                    ->table('#ResumeEMR')
                    ->insert($resume);
            } else {
                $resume = array(
                    'kdprofile' => $this->kdProfile,
                    'statusenabled' => true,
                    'noregistrasifk' => $registrasi['norec_pd'],
                    'nocmfk' => $pasien['nocmfk'],
                    'emrpasienfk' => $norec,
                    'table' => $r['collection'],
                    'last_update' => $now,
                    'author' => $data['user_input']['namalengkap'],
                    'url_form' => $r['url_form'],
                    'namaemr' => $r['name_form'],
                    'noemr' => $EMR->noemr,
                    'icon' => isset($formExist['icon']) ? $formExist['icon'] : null,
                );
                DB::connection('mongodb')
                    ->table('#ResumeEMR')
                    ->where('id', $formExist['id'])
                    ->update($resume);
            }

            if ($r['collection'] == 'VitalSign') {
                $pd = PasienDaftar::where('norec', $registrasi['norec_pd'])
                    ->where('kdprofile', $this->kdProfile)
                    ->first();
                $pd->tinggibadan = isset($data['tinggiBadan']) ? (float) str_replace(',', '.', $data['tinggiBadan']) : null;
                $pd->beratbadan = isset($data['beratBadan']) ? (float) str_replace(',', '.', $data['beratBadan']) : null;

                $pd->suhu = isset($data['suhu']) ? (float) str_replace(',', '.', $data['suhu']) : null;
                $pd->nadi = isset($data['nadi']) ? (float) str_replace(',', '.', $data['nadi']) : null;
                $pd->pernafasan = isset($data['pernapasan']) ? (float) str_replace(',', '.', $data['pernapasan']) : null;
                $pd->tekanandarah = isset($data['tekananDarah']) ? (float) str_replace(',', '.', $data['tekananDarah']) : null;
                $pd->spo2 = isset($data['SPO2']) ? (float) str_replace(',', '.', $data['SPO2']) : null;
                $pd->save();
            }

            $apd_flag = [];
            if ($r['collection'] == 'AsesmenMedisRawatJalan' || $r['collection'] == 'AsesmenAwalMedisHemodialisa' || $r['collection'] == 'AsesmenMedisKedokteranNuklir') {
                PasienDaftar::where('norec', $registrasi['norec_pd'])
                    ->where('kdprofile', $this->kdProfile)
                    ->update(['isasmed' => true]);
            }
            // if ($r['collection'] == 'AsesmenAwalMedisHemodialisa') {
            //     PasienDaftar::where('norec', $registrasi['norec_pd'])
            //         ->where('kdprofile', $this->kdProfile)
            //         ->update(['isasmedhd' => true]);
            // }
            // if ($r['collection'] == 'CatatanPerkembanganPasienTerintegrasi' && $r['data']['registrasi']->objectruanganfk == 332) {
            //     PasienDaftar::where('norec', $registrasi['norec_pd'])
            //         ->where('kdprofile', $this->kdProfile)
            //         ->update(['isasmedhd' => true]);
            // }
            if ($r['collection'] == 'AsesmenAwalMedisGawatDarurat' && isset($r['data']['Parameter_GawatDarurat'])) {
                if ($r['data']['Parameter_GawatDarurat'] == 'Gawat Darurat') {
                    PasienDaftar::where('norec', $registrasi['norec_pd'])
                        ->where('kdprofile', $this->kdProfile)
                        ->update(['isgadar' => true]);
                } else {
                    PasienDaftar::where('norec', $registrasi['norec_pd'])
                        ->where('kdprofile', $this->kdProfile)
                        ->update(['isgadar' => false]);
                }
            }
            $collections = [
                'TransThoracaEchoBayi' => ['isPenunjangKhusus' => true],
                'TransThoracaEchoDewasa' => ['isPenunjangKhusus' => true],
                'LowerExtermityDuplexUltrasoundUSGDoppler' => ['isPenunjangKhusus' => true],
                'CarotidDuplexUltrasound' => ['isPenunjangKhusus' => true],
                'FormulirHasilPemeriksaanEkg' => ['isPenunjangKhusus' => true],
                'PemeriksaanKardiotokografi' => ['isPenunjangKhusus' => true],
                'PemeriksaanObstetri' => ['isPenunjangKhusus' => true],
                'PemeriksaanTHT' => ['isPenunjangKhusus' => true],
                'PemeriksaanGynekologi' => ['isPenunjangKhusus' => true],
                'PemeriksaanFetal' => ['isPenunjangKhusus' => true],
                'PemeriksaanEkgMcu' => ['isPenunjangKhusus' => true],
                'PemeriksaanUrologi' => ['isPenunjangKhusus' => true],
                'HasilPemeriksaanBodyplethy' => ['isPenunjangKhusus' => true],
                'HasilPemeriksaanDLCOBodyplethy' => ['isPenunjangKhusus' => true],
                'HasilPemeriksaanSpirometri' => ['isPenunjangKhusus' => true],
                'PemeriksaanEkgInterna' => ['isPenunjangKhusus' => true],
                'PemeriksaanEkgParu' => ['isPenunjangKhusus' => true],
            ];
            $selectedCollection = $r['collection'];

            if (array_key_exists($selectedCollection, $collections)) {
                PasienDaftar::where('norec', $registrasi['norec_pd'])
                    ->where('kdprofile', $this->kdProfile)
                    ->update($collections[$selectedCollection]);
            }

            //untuk flag status isi asesmen awal kep RJ di dashboard RJ
            //jika true maka pasien muncul di dokter
            //kecuali IGD Hemo Kemo
            if ($r['collection'] == 'AsesmenAwalKeperawatanPasienRawatJalanNurse' || $r['collection'] == 'AsesmenAwalKebidananRawatJalanNurse') {
                $apd_flag = ['isaskepnurse' => true, 'updated_at' => $now];
            }

            // flag khusus perawat
            if ($r['collection'] == 'AsesmenAwalKeperawatanPasienRawatJalan' || $r['collection'] == 'AsesmenAwalKebidananRawatJalan' || $r['collection'] == 'CatatanKegiatanRadioterapi') {
                // $apd_flag = ['isaskeprj' => true, 'updated_at' => $now];
                PasienDaftar::where('norec', $registrasi['norec_pd'])
                    ->where('kdprofile', $this->kdProfile)
                    ->update(['isaskeprj' => true]);
            }

            if (!empty($apd_flag)) {
                AntrianPasienDiperiksa::where('norec', $registrasi['norec_apd'])
                    ->where('kdprofile', $this->kdProfile)
                    ->update($apd_flag);
            }

            $this->LOGGING(
                $r['collection'],
                $norec,
                'EMR',
                'input EMR ' . $r['name_form'] . ' dari pasien dengan no registrasi ' . $registrasi['noregistrasi'] . ' oleh ' . $data['user_input']['namalengkap'] . ' id :' . $data['id'] . ' di ruangan ' . $registrasi['namaruangan']
            );
            $transMessage = "Sukses";
            DB::commit();
            $ihs = null;
            if ($r['collection'] == 'VitalSign') {
                $objetoRequest = new \Illuminate\Http\Request();
                $objetoRequest['noregistrasi'] = $registrasi['noregistrasi'];
                $ihs = app('App\Http\Controllers\Bridging\SATUSEHATCtrl')->Observation($objetoRequest, true);
            }
            $result = array(
                "status" => 200,
                "result" => array(
                    "norec_emr" => $EMR->norec,
                    "noemr" => $EMR->noemr,
                    "id" => $data['id'],
                    "Observation" => $ihs,
                    "as" => '@epic',
                ),
            );
        } catch (\Exception $e) {

            $transMessage = "Simpan Gagal";
            DB::rollBack();
            $result = array(
                "status" => 400,
                "result" => $e->getMessage() . ' ' . $e->getLine()

            );
        }
        return $this->respond($result['result'], $result['status'], $transMessage);
    }
    private function findInvalidDates($data)
    {
        $invalidDatesCount = 0;
        $arr = [];
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $count = $this->findInvalidDates($value);
                $invalidDatesCount += $count;
                $arr = array_merge($arr, $value);
            } else {
                if ($value === 'Invalid date') {
                    $invalidDatesCount++;
                    array_push($arr, $value);
                }
            }
        }

        return $invalidDatesCount;
    }

    private function legalityCheck($r, $index)
    {
        // Function khusus check legalitas dokter seperti NIP, SIP, dll.
        $petugas = null;

        if (isset($r['DDDokter']['label'])) {
            $petugas = $r['DDDokter']['label'];
        } else if (isset($r['DDDokter'])) {
            $petugas = $r['DDDokter'];
        } else if (isset($r['dokterPemeriksa']['label'])) {
            $petugas = $r['dokterPemeriksa']['label'];
        } else if (isset($r['dokterPemeriksa'])) {
            $petugas = $r['dokterPemeriksa'];
        } else if (isset($r['CBDokter']['label'])) {
            $petugas = $r['CBDokter']['label'];
        } else if (isset($r['CBDokter'])) {
            $petugas = $r['CBDokter'];
        } else if (isset($r['registrasi']['dokter'])) {
            $petugas = $r['registrasi']['dokter'];
        }

        //* FormulirCatataPemberianObatKemoterapi
        if (isset($r['dokterDPJPPremadikasi']['label']) && $index == 1) {
            $petugas = $r['dokterDPJPPremadikasi']['label'];
        } else if (isset($r['dokterDPJPPremadikasi']) && $index == 1) {
            $petugas = $r['dokterDPJPPremadikasi'];
        }

        if (isset($r['dokterDPJPKemoterapi']['label']) && $index == 2) {
            $petugas = $r['dokterDPJPKemoterapi']['label'];
        } else if (isset($r['dokterDPJPKemoterapi']) && $index == 2) {
            $petugas = $r['dokterDPJPKemoterapi'];
        }

        return $petugas;
    }

    private function getTTDPasien($nocmfk, $collection)
    {
        if (empty($collection)) {
            return null;
        }

        $field = '';
        switch ($collection) {
            case 'JadwalKunjunganRehabDanFisio':
                $field = 'parafPasien_0';
                break;
            default:
                break;
        }

        $imgDefault = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAJYAAACWCAYAAAA8AXHiAAAAAXNSR0IArs4c6QAAAqFJREFUeF7t0jENAAAMw7CVP+mhyOcC6BF5ZwoEBRZ8ulTgwIIgKQBWktUpWAwkBcBKsjoFi4GkAFhJVqdgMZAUACvJ6hQsBpICYCVZnYLFQFIArCSrU7AYSAqAlWR1ChYDSQGwkqxOwWIgKQBWktUpWAwkBcBKsjoFi4GkAFhJVqdgMZAUACvJ6hQsBpICYCVZnYLFQFIArCSrU7AYSAqAlWR1ChYDSQGwkqxOwWIgKQBWktUpWAwkBcBKsjoFi4GkAFhJVqdgMZAUACvJ6hQsBpICYCVZnYLFQFIArCSrU7AYSAqAlWR1ChYDSQGwkqxOwWIgKQBWktUpWAwkBcBKsjoFi4GkAFhJVqdgMZAUACvJ6hQsBpICYCVZnYLFQFIArCSrU7AYSAqAlWR1ChYDSQGwkqxOwWIgKQBWktUpWAwkBcBKsjoFi4GkAFhJVqdgMZAUACvJ6hQsBpICYCVZnYLFQFIArCSrU7AYSAqAlWR1ChYDSQGwkqxOwWIgKQBWktUpWAwkBcBKsjoFi4GkAFhJVqdgMZAUACvJ6hQsBpICYCVZnYLFQFIArCSrU7AYSAqAlWR1ChYDSQGwkqxOwWIgKQBWktUpWAwkBcBKsjoFi4GkAFhJVqdgMZAUACvJ6hQsBpICYCVZnYLFQFIArCSrU7AYSAqAlWR1ChYDSQGwkqxOwWIgKQBWktUpWAwkBcBKsjoFi4GkAFhJVqdgMZAUACvJ6hQsBpICYCVZnYLFQFIArCSrU7AYSAqAlWR1ChYDSQGwkqxOwWIgKQBWktUpWAwkBcBKsjoFi4GkAFhJVqdgMZAUACvJ6hQsBpICYCVZnYLFQFIArCSrU7AYSAqAlWR1ChYDSQGwkqxOwWIgKQBWktUpWAwkBcBKsjp9QYIAl6bSsVAAAAAASUVORK5CYII=';

        $data = DB::connection('mongodb')
            ->table($collection)
            ->select($field)
            ->where($field, '!=', $imgDefault)
            ->where('statusenabled', true)
            ->where('pasien.nocmfk', $nocmfk)
            ->whereNull('namatemplate')
            ->first();

        return $data[$field];
    }
}
