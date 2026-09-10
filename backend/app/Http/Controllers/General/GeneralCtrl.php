<?php

namespace App\Http\Controllers\General;

use App\Http\Controllers\Controller;
use App\Models\Master\Pegawai;
use App\Models\Transaksi\AntrianApotik;
use App\Models\Transaksi\KartuStok;
use App\Models\Transaksi\PelayananPasien;
use App\Models\Transaksi\PelayananPasienDetail;
use App\Models\Transaksi\PelayananPasienPetugas;
use App\Models\Transaksi\StokProdukDetail;
use App\Models\Transaksi\StrukOrder;
use App\Models\Transaksi\StrukResep;
use App\Traits\Valet;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Bridging\BridgingBPJSCtrl;
use App\Models\Master\ListNotif;
use App\Models\Master\Pasien;
use App\Models\Master\Printer;
use App\Models\Standar\LoginUser;
use App\Models\Transaksi\PasienDaftar;
use App\Models\Transaksi\PostingJurnal;
use App\Models\Transaksi\PostingJurnalTransaksi;
use App\Models\Transaksi\PostingJurnalTransaksiD;
use App\Models\Transaksi\SuratKeterangan;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;

class GeneralCtrl extends Controller
{
    use Valet;

    protected $bridgingBPJSCtrl;
    public function __construct(BridgingBPJSCtrl $bridgingBPJSCtrl)
    {
        parent::__construct($is_encrypt = true);
        $this->bridgingBPJSCtrl = $bridgingBPJSCtrl;
    }
    public function pasienRegistrasiSearching(Request $r)
    {
        $dari = date('Y-m-d 00:00:00');
        $sampai = date('Y-m-d 23:59:59');
        $data = DB::table(function ($query) use ($r) {
            $query->from('pasiendaftar_t as pd')
                ->join('pasien_m as ps', 'ps.id', '=', 'pd.nocmfk')
                ->join('ruangan_m as ru', 'ru.id', '=', 'pd.objectruanganlastfk')
                ->select(
                    'ps.namapasien',
                    'ps.nocm',
                    'ru.namaruangan',
                    'pd.norec',
                    'pd.tglregistrasi',
                    'ps.id',
                    DB::raw('ROW_NUMBER() OVER(PARTITION BY ps.namapasien ORDER BY pd.tglregistrasi DESC) as row_num')
                )
                ->where('pd.kdprofile', $this->kdProfile)
                ->where('pd.statusenabled', true)
                ->where('ps.statusenabled', true);

            if (isset($r['query']) && $r['query'] != '') {
                $searchTerm = '%' . $r['query'] . '%';
                $query->where(function ($innerQuery) use ($searchTerm) {
                    $innerQuery->where('ps.namapasien', 'ILIKE', $searchTerm)
                        ->orWhere('ps.nocm', 'ILIKE', $searchTerm);
                });
            }
        }, 'latest_data')
            ->where('row_num', 1)
            ->get();
        return $this->respond($data);
    }
    public function listDokterPaging(Request $r)
    {
        $result['idJenisPegawaiDokter'] = explode(',', $this->settingFix('idJenisPegawaiDokter'));
        if ($r['jenis'] !== null) {
            $result['idJenisPegawaiDokter'] = $r['jenis'];
        }
        $result['dokter'] =
            Pegawai::mine()
            ->where('objectjenispegawaifk', $result['idJenisPegawaiDokter'])
            ->search($r['name'])
            ->paging($r['limit'])
            ->get();
        return $this->respond($result);
    }
    public function jenisoperasi(Request $request)
    {
        $data = DB::table('ppra_jenisoperasi')
            ->select('id', 'namaoperasi')
            ->when($request->has('name'), function ($query) use ($request) {
                $query->where('namaoperasi', 'ilike', '%' . $request->name . '%');
            })
            ->limit($request->get('limit', 10))
            ->get();
        $result['data'] = $data;

        return $this->respond($result);
    }

    public function jenisksm(Request $request)
    {
        $data = DB::table('ppra_divisi')
            ->select('id', 'divisi')
            ->when($request->has('name'), function ($query) use ($request) {
                $query->where('divisi', 'ilike', '%' . $request->name . '%');
            })
            ->where('statusantibiotik', 2)
            ->limit($request->get('limit', 10))
            ->get();
        $result['data'] = $data;

        return $this->respond($result);
    }
    public function jenistindakan(Request $request)
    {
        $data = DB::table('ppra_tindakan')
            ->select('id', 'namatindakan')
            ->when($request->has('name'), function ($query) use ($request) {
                $query->where('namatindakan', 'ilike', '%' . $request->name . '%');
            })
            ->where('objectppradivisifk', $request->ksm)
            // ->limit($request->get('limit', 10))
            ->get();
        $resut['data'] = $data;
        return $this->respond($resut);
    }
    public function operasiDetail(Request $request)
    {
        $data = DB::table('ppra_jenisoperasi as pj')
            ->select('pj.id', 'pd.divisi', 'pa.antibiotik')
            ->join('ppra_divisi as pd', 'pj.objectdivisifk', '=', 'pd.id')
            ->join('ppra_antibiotik as pa', 'pj.objectantibiotikfk', '=', 'pa.id')
            ->when($request->filled('id'), function ($query) use ($request) {
                $query->where('pj.id', $request->id);
            })
            ->first();

        if (!$data) {
            return response()->json([
                'metaData' => ['code' => 404, 'message' => 'Data not found'],
                'response' => null
            ], 404);
        }

        return response()->json([
            'metaData' => ['code' => 200, 'message' => 'Success'],
            'response' => $data
        ]);
    }

    public function headerPasien(Request $r)
    {
        $data = DB::table('pasiendaftar_t as pd')
            ->JOIN('ruangan_m as ru', 'ru.id', '=', 'pd.objectruanganlastfk')
            ->JOIN('departemen_m as dp', 'dp.id', '=', 'ru.objectdepartemenfk')
            ->LEFTJOIN('antrianapotik_t as aa', 'aa.noregistrasi', '=', 'pd.noregistrasi')
            ->JOIN('kelompokpasien_m as kp', 'kp.id', '=', 'pd.objectkelompokpasienlastfk')
            ->JOIN('pasien_m as ps', 'ps.id', '=', 'pd.nocmfk')
            ->JOIN('alamat_m as alm', 'alm.nocmfk', '=', 'ps.id')
            ->JOIN('jeniskelamin_m as jk', 'jk.id', '=', 'ps.objectjeniskelaminfk')
            ->LEFTJOIN('kebangsaan_m as kb', 'kb.id', '=', 'ps.objectkebangsaanfk')
            ->select(
                'ps.nocm',
                'aa.jenis',
                'aa.noantri',
                'ps.id as nocmfk',
                'ps.namapasien',
                'ps.tgllahir',
                'pd.isclosing',
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
                'ps.objectkebangsaanfk as idkebangsaan',
                'ps.namaibu',
                'dp.id as iddepartemen',
                'kb.name as kebangsaan',
                'pd.noregistrasi',
                'pd.tglpulang',
                'ps.email'
            )
            ->where('ps.kdprofile', (int) $this->kdProfile)
            ->where('ps.statusenabled', true);
        if (isset($r['nocmfk']) && $r['nocmfk'] != '') {
            $data = $data->where('ps.id', $r['nocmfk']);
        }

        if (isset($r['norec_pd'])) {
            $data = $data->where('pd.norec', $r['norec_pd']);
        }


        // else {

        // }
        $data = $data->first();

        //var_dump($data);
        if (!empty($data)) {
            $data->umur = $this->getAge($data->tgllahir, date('Y-m-d H:i:s'));
        }
        $registrasi = [];
        if (isset($r['nocmfk']) && $r['nocmfk'] != '' && isset($r['norec_pd']) && $r['norec_pd'] != '') {
            $registrasi = DB::table('pasiendaftar_t as pd')
                ->JOIN('antrianpasiendiperiksa_t as apd', 'apd.noregistrasifk', '=', 'pd.norec')
                ->JOIN('ruangan_m as ru', 'ru.id', '=', 'apd.objectruanganfk')
                ->JOIN('departemen_m as dp', 'dp.id', '=', 'ru.objectdepartemenfk')
                ->JOIN('kelompokpasien_m as kp', 'kp.id', '=', 'pd.objectkelompokpasienlastfk')
                ->JOIN('kelas_m as kl', 'kl.id', '=', 'apd.objectkelasfk')
                ->LEFTJOIN('pemakaianasuransi_t as pa', 'pa.noregistrasifk', '=', 'pd.norec')
                ->LEFTJOIN('asuransipasien_m as asu', 'asu.id', '=', 'pa.objectasuransipasienfk')
                ->LEFTJOIN('kelas_m as kls', 'kls.id', '=', 'asu.objectkelasdijaminfk')
                ->LEFTJOIN('rekanan_m as rk', 'rk.id', '=', 'pd.objectrekananfk')
                ->LEFTJOIN('pegawai_m as pg', 'pg.id', '=', 'pd.objectpegawaifk')
                ->LEFTJOIN('kelas_m as kl2', 'kl2.id', '=', 'apd.kelasrawatfk')
                ->select(
                    'pd.noregistrasi',
                    'pd.norec as norec_pd',
                    'pd.tglregistrasi',
                    'pd.tglpulang',
                    'pd.isclosing',
                    'dp.namadepartemen',
                    'kp.kelompokpasien',
                    'apd.norec as norec_apd',
                    'pd.objectruanganlastfk',
                    'apd.objectruanganfk',
                    'ru.namaruangan',
                    'apd.objectkelasfk',
                    'apd.kelasrawatfk',
                    'kl2.namakelas as naikkelas',
                    'kl.namakelas',
                    'pd.nocmfk',
                    'apd.tglmasuk',
                    'apd.tglkeluar',
                    'pd.objectkelompokpasienlastfk',
                    'pd.objectrekananfk',
                    'pd.jenispelayanan as jenispelayananfk',
                    'pd.statusbayar',
                    'rk.namarekanan',
                    'pg.namalengkap as dokter',
                    'pd.objectruanganlastfk',
                    'pd.inacbg_totalgrouper',
                    'kls.namakelas as kelasditanggung',
                    'asu.nmprovider',
                    'pa.nosep as nosep',
                    'kl.kodebpjs as kelas_rawat',
                    'kls.kodebpjs as kelas_dijamin',
                    'pa.klsrawathak_kode',
                    'pd.iskelastitip',
                    'pd.isnaikkelas',
                )
                ->where('pd.kdprofile', (int) $this->kdProfile)
                ->where('pd.statusenabled', true)
                ->where('pd.nocmfk', $r['nocmfk'])
                ->where('pd.norec', $r['norec_pd'])
                ->get();
        }

        $last = array();
        $tgl = date('2000-01-01 00:00');
        foreach ($registrasi as $d) {
            if ($d->statusbayar == null) {
                $d->statusbayar = 'Belum Verifikasi';
            }
            if ($d->objectruanganlastfk == $d->objectruanganfk && $tgl < $d->tglmasuk) {
                $tgl = $d->tglmasuk;
                $last = $d;
            }
        }
        $result['pasien'] = $data;
        $result['registrasi'] = $registrasi;
        $result['last_registrasi'] = $last;
        $result['isFilterProdukLab'] = $this->settingFix('isFilterProdukLab');
        $result['nominal'] = $r['nominal'] ? $this->terbilang($r['nominal']) : null;
        $result['as'] = '@epic';

        return $this->respond($result);
    }
    public function headerPasienFirst(Request $r)
    {

        $registrasi = DB::table('pasiendaftar_t as pd')
            ->JOIN('pasien_m as ps', 'ps.id', '=', 'pd.nocmfk')
            ->JOIN('ruangan_m as ru', 'ru.id', '=', 'pd.objectruanganlastfk')
            ->JOIN('departemen_m as dp', 'dp.id', '=', 'ru.objectdepartemenfk')
            ->JOIN('kelompokpasien_m as kp', 'kp.id', '=', 'pd.objectkelompokpasienlastfk')
            ->LEFTJOIN('rekanan_m as rek', 'rek.id', '=', 'pd.objectrekananfk')
            ->JOIN('kelas_m as kl', 'kl.id', '=', 'pd.objectkelasfk')
            ->LEFTJOIN('kelas_m as kl2', 'kl2.id', '=', 'pd.objectkelasrawatfk')
            ->JOIN('alamat_m as alm', 'alm.nocmfk', '=', 'ps.id')
            ->JOIN('jeniskelamin_m as jk', 'jk.id', '=', 'ps.objectjeniskelaminfk')
            ->select(
                'pd.noregistrasi',
                'pd.norec as norec_pd',
                'pd.tglregistrasi',
                'pd.tglpulang',
                'dp.namadepartemen',
                'kp.kelompokpasien',
                'pd.objectruanganlastfk',
                'ru.namaruangan',
                'pd.objectkelasfk',
                'kl.namakelas',
                'kl2.namakelas as kelasrawat',
                'pd.isclosing',
                'pd.nocmfk',
                'pd.objectkelompokpasienlastfk',
                'pd.objectrekananfk',
                'pd.jenispelayanan as jenispelayananfk',
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
                'rek.namarekanan',
                'pd.iskelastitip',
                'pd.isnaikkelas',
                'pd.objectkelasrawatfk',

            )
            ->where('pd.kdprofile', (int) $this->kdProfile)
            ->where('pd.statusenabled', true)
            ->where('pd.norec', $r['norec_pd'])
            ->first();
        if (!empty($registrasi)) {
            $registrasi->umur = $this->getAge($registrasi->tgllahir, date('Y-m-d H:i:s'));
        }
        $result['registrasi'] = $registrasi;
        $result['as'] = '@epic';

        return $this->respond($result);
    }
    public function showFileGeneral(Request $r)
    {
        $arr_path = explode('/',$r['path']);
        if($arr_path[0] == 'dokumen_klaim') {
            $noreg = isset($arr_path) && $arr_path[1] ? $arr_path[1] : '';
            $fileName = isset($arr_path) && $arr_path[2] ? $arr_path[2] : '';
            $filePath = storage_path('app/public/'.$r['path']);
            $getBundle = DB::table('bundleklaim_t')->where('noregistrasi', $noreg)
            ->where('filename', $fileName)
            ->first();
            $rawData = null;
            if($getBundle) {
                $rawData = base64_decode($getBundle->data);
            }else {
                if(file_exists($filePath)) {
                    $rawData = base64_encode(file_get_contents($filePath));
                    DB::table('bundleklaim_t')->insert([
                        'norec'         => \Ramsey\Uuid\Uuid::uuid4()->toString(),
                        'noregistrasi'  => $noreg,
                        'filename'      => $fileName,
                        'data'          => $rawData,
                    ]);
                    $rawData = base64_decode($rawData);
                }
            }
            
            if($rawData != null) {
                return response($rawData, 200)
                        ->header('Content-Type', 'application/pdf')
                        ->header('Content-Disposition', 'inline; filename="'.$fileName.'"');
            }
        }
        return response()->fShow($r['path'], 'public');
    }
    public function saveLoggingAll(Request $r)
    {
        try {
            $this->LOGGING(
                $r['jenislog'],
                $r['noreff'],
                $r['referensi'],
                $r['keterangan'],
                $r['data'] ?? null,
                $r['response'] ?? null,
            );

            $transMessage = "Sukses";
            $result = array(
                "status" => 200,
                "result" => array(
                    "as" => '@epic',
                ),
            );
        } catch (Exception $e) {
            $transMessage = "Simpan Gagal";
            DB::rollBack();
            $result = array(
                "status" => 400,
                "result" => $e->getMessage()

            );
        }

        return $this->respond($result['result'], $result['status'], $transMessage);
    }
    public function settingPPKBPJS(Request $r)
    {
        return $this->respond($this->bridgingBPJSCtrl->getSetting());
    }
    public function getTemplateExpertice(Request $request)
    {

        $data = DB::table('templateexpertiseecho_m')
            ->where('statusenabled', true)
            ->where('kdprofile', $this->kdProfile)
            ->get();

        $result = array(
            'data' => $data,
            'as' => '@epic'
        );

        return $this->respond($result);
    }
    public function apiTOOLS(Request $request)
    {
        $dataJsonSend = null;
        $url = $request['url']; //str_replace('http://app.rsjpparamarta.com', 'http://10.20.30.40', $request['url']);
        if ($request['data'] != null) {
            $dataJsonSend = $request['data'];
        }
        if (empty($dataJsonSend)) {
            $response = Http::withHeaders($request['headers'])
                ->{$request['method']}($url);
        } else {
            $response = Http::withHeaders($request['headers'])
                ->{$request['method']}($url, $dataJsonSend);
        }

        $res = null;
        if ($response->ok()) {
            $res = $response->json();
        }
        return $this->respond($res);
    }

    public function SaveSuratKeteranganKematian(Request $request)
    {
        DB::beginTransaction();
        $kdProfile = (int) $this->getDataKdProfile($request);
        $kdJenisSurat = (int) $this->settingDataFixed('SuratKematian', $kdProfile);
        $tglAyeuna = date('Y-m-d H:i:s');
        $dataLogin = $request->all();
        $dataPegawai = DB::table('loginuser_s as lu')
            ->select('lu.objectpegawaifk')
            ->where('lu.id', $dataLogin['userData']['id'])
            ->first();
        $jenisSurat = DB::table('jenissurat_m')
            ->select('*')
            ->where('id', $kdJenisSurat)
            ->first();
        $kodeSurat = '';
        if (!empty($jenisSurat)) {
            $kodeSurat = $jenisSurat->namaexternal;
        }
        try {

            if ($request['norec'] == '') {
                $genSurat = $this->genSurat(new SuratKeterangan(), 'nosint', $kodeSurat, 4);
                $SKM = new SuratKeterangan();
                $norecNew = $SKM->generateNewId();
                $SKM->kdprofile = $kdProfile;
                $SKM->norec = $norecNew;
                $SKM->nosurat = $genSurat['nosurat'];
                $SKM->nosint = $genSurat['noint'];
                $SKM->statusenabled = true;
                $SKM->jenissuratfk = $kdJenisSurat;
            } else {
                $SKM = SuratKeterangan::where('norec', $request['norec'])->where('kdprofile', $kdProfile)->first();
            }
            $SKM->keterangan = $request['keterangan'];
            $SKM->tglawal = $request['tglmeninggal'];
            $SKM->tglakhir = $request['tglmeninggal'];
            $SKM->pegawaifk = $dataPegawai->objectpegawaifk;
            $SKM->tglsurat = $tglAyeuna;
            $SKM->pasiendaftarfk = $request['norec_pd'];
            $SKM->dokterfk = $request['dokterfk'];
            $SKM->save();
            $norecSKM = $SKM->norec;

            $transStatus = 'true';
        } catch (\Exception $e) {
            $transStatus = 'false';
        }

        $transMessage = "";
        if ($transStatus == 'true') {
            $transMessage = $transMessage . "Simpan Berhasil";
            DB::commit();
            $result = array(
                "status" => 200,
                "data" => $norecSKM,
                "by" => 'ea@epic',
            );
        } else {
            $transMessage = $transMessage . "Simpan Gagal!!";
            DB::rollBack();
            $result = array(
                "status" => 400,
                "data" => $norecSKM,
                "by" => 'ea@epic',
            );
        }
        return $this->setStatusCode($result['status'])->respond($result);
    }


    public function SaveSuratKeteranganMeninggal(Request $request)
    {
        DB::beginTransaction();
        $kdProfile = (int) $this->getDataKdProfile($request);
        $kdJenisSurat = (int) $this->settingDataFixed('SuratMeninggal', $kdProfile);
        $tglAyeuna = date('Y-m-d H:i:s');
        $dataLogin = $request->all();
        $dataPegawai = DB::table('loginuser_s as lu')
            ->select('lu.objectpegawaifk')
            ->where('lu.id', $dataLogin['userData']['id'])
            ->first();
        $jenisSurat = DB::table('jenissurat_m')
            ->select('*')
            ->where('id', $kdJenisSurat)
            ->first();
        $kodeSurat = '';
        if (!empty($jenisSurat)) {
            $kodeSurat = $jenisSurat->namaexternal;
        }
        try {

            if ($request['norec'] == '') {
                $genSurat = $this->genSurat(new SuratKeterangan(), 'nosint', $kodeSurat, 4);
                $SKM = new SuratKeterangan();
                $norecNew = $SKM->generateNewId();
                $SKM->kdprofile = $kdProfile;
                $SKM->norec = $norecNew;
                $SKM->nosurat = $genSurat['nosurat'];
                $SKM->nosint = $genSurat['noint'];
                $SKM->statusenabled = true;
                $SKM->jenissuratfk = $kdJenisSurat;
            } else {
                $SKM = SuratKeterangan::where('norec', $request['norec'])->where('kdprofile', $kdProfile)->first();
            }
            $SKM->keterangan = $request['keterangan'];
            $SKM->tglawal = $request['tglmeninggal'];
            $SKM->tglakhir = $request['tglmeninggal'];
            $SKM->pegawaifk = $dataPegawai->objectpegawaifk;
            $SKM->tglsurat = $tglAyeuna;
            $SKM->pasiendaftarfk = $request['norec_pd'];
            $SKM->dokterfk = $request['dokterfk'];
            $SKM->save();
            $norecSKM = $SKM->norec;

            $transStatus = 'true';
        } catch (\Exception $e) {
            $transStatus = 'false';
        }

        $transMessage = "";
        if ($transStatus == 'true') {
            $transMessage = $transMessage . "Simpan Berhasil";
            DB::commit();
            $result = array(
                "status" => 200,
                "data" => $norecSKM,
                "by" => 'ea@epic',
            );
        } else {
            $transMessage = $transMessage . "Simpan Gagal!!";
            DB::rollBack();
            $result = array(
                "status" => 400,
                "data" => $norecSKM,
                "by" => 'ea@epic',
            );
        }
        return $this->setStatusCode($result['status'])->respond($result);
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
    public function settingFixData($setting)
    {
        $set = $this->settingFix($setting);
        return $this->respondV2($set);
    }
    public function masterPrinter(Request $r)
    {
        $data = DB::table('printer_m')
            ->select(
                '*'

            )
            ->where('kdprofile', $this->kdProfile)
            ->where('statusenabled', true);
        if (isset($r['id']) && $r['id'] != '') {
            $data = $data->where('id', '=', $r['id']);
        }
        if (isset($r['namaexternal']) && $r['namaexternal'] != '') {
            $data = $data->where('namaexternal', '=', $r['namaexternal']);
        }
        if (isset($r['printerdefault']) && $r['printerdefault'] != '') {
            $data = $data->where('printerdefault', 'ilike', '%' . $r['printerdefault'] . '%');
        }
        if (isset($r['device']) && $r['device'] != '') {
            $data = $data->where('devicename', '=', $r['device']);
        }

        $data = $data->orderByDesc('id', 'desc');
        $data = $data->get();
        if (count($data) > 0 && isset($r['qz']) && $r['qz'] == 'true') {
            $arr = [];
            foreach ($data as $d) {
                if (gethostname() == $d->devicename) {
                    $arr[] = $d;
                    break;
                }
            }
            if (count($arr) > 0) {
                $data = $arr;
            }
        }

        $res['data'] = $data;
        return $this->respond($res);
    }

    public function savePrinter(Request $r)
    {
        DB::beginTransaction();
        try {

            if ($r['id'] == '') {
                $id = $this->SEQUENCE_MASTER(new Printer(), 'id', $this->kdProfile);
                $dataPS = new Printer();
                $dataPS->id = $id;
                $dataPS->kdprofile = (int) $this->kdProfile;
                $dataPS->statusenabled = true;
            } else {
                $dataPS = Printer::where('id', $r['id'])->first();
                $id = $dataPS->id;
            }
            $dataPS->namaexternal = $r['namaexternal'];
            $dataPS->printerdefault = $r['printerdefault'];
            $dataPS->orientation = $r['orientation'];
            $dataPS->keterangan = $r['keterangan'] ? $r['keterangan'] : null;
            $dataPS->height = $r['height'];
            $dataPS->width = $r['width'];
            $dataPS->devicename = isset($r['devicename']) ? $r['devicename'] : gethostname();
            $dataPS->save();


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
                    "data" => $dataPS,
                    "as" => '@epic',
                ),
            );
        } else {
            $transMessage = "Simpan Gagal";
            DB::rollBack();

            $result = array(
                "status" => 400,
                "result" => $e->getMessage()

            );
        }
        return $this->respond($result['result'], $result['status'], $transMessage);
    }
    public function deletePrinter(Request $r)
    {
        DB::beginTransaction();
        try {

            $dataPS = Printer::where('id', $r['id'])
                ->update([
                    'statusenabled' => false
                ]);

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
                    "data" => $dataPS,
                    "as" => '@epic',
                ),
            );
        } else {
            $transMessage = "Hapus Gagal";
            DB::rollBack();

            $result = array(
                "status" => 400,
                "result" => null

            );
        }
        return $this->respond($result['result'], $result['status'], $transMessage);
    }
    public function getLogUser(Request $r)
    {
        $kdProfile = (int) $this->getDataKdProfile($r);
        $tglAwal = $r->tglAwal;
        $tglAkhir = $r->tglAkhir;
        $rangeDate = [$tglAwal, $tglAkhir];
        $nama = $r->nama;
        $keterangan = $r->keterangan;
        $kelompok = $r->kelompok;
        $rows = $r->rows;
        $limit = $r->limit;
        $offset = $r->offset;
        $data = DB::table('logginguser_t as lo')
            ->where('lo.kdprofile', $kdProfile)
            ->when($rangeDate, function ($query) use ($rangeDate) {
                return $query->whereBetween(DB::raw("CAST(lo.tanggal AS DATE)"), $rangeDate);
            })
            ->select(
                'lo.norec',
                'lo.namauser as username',
                'lo.namapegawai as namalengkap',
                'lo.tanggal as tanggal',
                'lo.jenislog as jenis',
                'lo.keterangan as keterangan',
            )
            ->when($nama, function ($query) use ($nama) {
                return $query->where('lo.namauser', 'ilike', '%' . $nama . '%');
            })
            ->when($keterangan, function ($query) use ($keterangan) {
                return $query->where('lo.keterangan', 'ilike', '%' . $keterangan . '%');
            })
            ->when($limit, function ($query) use ($limit) {
                return $query->limit($limit);
            })
            ->when($offset, function ($query) use ($offset) {
                return $query->offset($offset);
            })
            ->where('lo.statusenabled', true)
            ->orderBy('lo.tanggal', 'DESC');
        $total = $data->count();
        $data = $data->get();
        $result = [
            'data' => $data,
            'total' => $total,
            'message' => 'success',
        ];
        return $this->respond($result);
    }
    public function storeNotif(Request $request)
    {
        DB::beginTransaction();
        date_default_timezone_set('Asia/Jakarta');
        try {
            $cek = null;
            if ($request['method'] == 'save') {
                // $cek = ListNotif::where('norec_trans',$request['norec'])->first();
                // if(empty($cek)){
                $gl = $request['tgl'];
                $da = new ListNotif();

                $da->norec = $this->Uuid4();
                $da->norec_trans = $request['norec'];
                $da->judul = $request['judul'];
                $da->jenis = $request['jenis'];
                $da->kelompokuser = $request['kelompokUser'];
                $da->kelompokuserfk = $request['idKelompokUser'];
                $da->ruangantujuanfk = $request['idRuanganTujuan'];
                $da->ruangantujuan = $request['ruanganTujuan'];
                $da->ruanganasalfk = $request['idRuanganAsal'];
                $da->ruanganasal = $request['ruanganAsal'];
                $da->pegawaifk = $request['idPegawai'];
                $da->keterangan = $request['pesanNotifikasi'];
                $da->tgl = $gl;
                $da->tgl_string = $request['tgl_string'];
                $da->urlform = isset($request['urlForm']) ? $request['urlForm'] : null;
                $da->params = isset($request['params']) ? json_encode($request['params']) : null;
                $da->dataarray = isset($request['dataArray']) ? json_encode($request['dataArray']) : null;
                $da->statusenabled = true;
                $da->group = $request['group'];
                $da->namapegawai = $request['namapegawai'];

                $da->save();
                $cek = $da;
                // }

            }
            if ($request['method'] == 'delete') {
                $cek = ListNotif::where('norec_trans', $request['norec'])->delete();
            }
            if ($request['method'] == 'get') {
                $cek = ListNotif::orderBy('tgl', 'desc')->limit(1000)->get();
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
                    "data" => $cek,
                    "as" => '@epic',
                ),
            );
        } else {
            $transMessage = "Simpan Gagal";
            DB::rollBack();
            $result = array(
                "status" => 400,
                "result" => $e->getMessage() . ' ' . $e->getline()
            );
        }
        return $this->respond($result['result'], $result['status'], $transMessage);
    }
    public function PostingJurnal_pelayananpasien_t(Request $request)
    {
        $kdProfile = (int) $this->kdProfile;
        DB::beginTransaction();
        $isAktif = $this->settingFix('PostingJurnal_pelayananpasien_t_isaktif', $kdProfile);
        if (!empty($isAktif) && $isAktif == 'false') {

            return "PostingJurnal_pelayananpasien_t belum aktif";
        }
        $dataLogin = $request->all();
        //        ini_set('max_execution_time', 1000); //6 minutes
        try {
            // TODO : Jurnal Pelayanan

            // $dataCoa = \DB::table('chartofaccountmapjurnal_t as cmap')
            //         ->join('chartofaccount_m as coad','coad.id','=','cmap.objectcoadebetfk')
            //     ->join('chartofaccount_m as coak','coak.id','=','cmap.objectcoakreditfk')
            //         ->leftjoin('departemen_m as dp','dp.id','=','cmap.objectdepartemenfk')
            //         ->leftjoin('ruangan_m as ru','ru.id','=','cmap.objectruanganfk')
            //         ->leftjoin('kelompokpasien_m as kp','kp.id','=','cmap.objectkelompokpasienfk')
            //         ->leftjoin('detailjenisproduk_m as djp','djp.id','=','cmap.objectdetailjenisprodukfk')
            //         ->leftjoin('jenisproduk_m as jp','jp.id','=','cmap.objectjenisprodukfk')
            //         ->select('cmap.norec',
            //             'coad.id as jurnaldid','coad.noaccount as jurnaldno','coad.namaaccount as jurnaldnm',
            //             'coak.id as jurnalkid','coak.noaccount as jurnalkno','coak.namaaccount as jurnalknm','cmap.*',
            //             'dp.namadepartemen','ru.namaruangan','kp.kelompokpasien','djp.detailjenisproduk','jp.jenisproduk')
            //         ->where('cmap.kdprofile', $kdProfile)
            //         ->where('cmap.statusenabled', true)
            //         ->where('objectjenistrxfk','=', 1)
            //         ->orderBy('coad.noaccount')
            //         ->orderBy('coak.noaccount')
            //         ->get();
            $aingMacan = [];
            $deptId = null;
            $ruId = null;
            $kelPasien = null;
            $detJenisPr = null;
            $raw = null;
            // foreach ($dataCoa as $map){
            // $deptId = $map->objectdepartemenfk == null ? "" : "and x.objectdepartemenfk = " . $map->objectdepartemenfk;
            // $ruId = $map->objectruanganfk == null ? "" : "and x.objectruanganfk = " . $map->objectruanganfk;
            // $kelPasien = $map->objectkelompokpasienfk == null ? "" : "and x.objectkelompokpasienlastfk = " . $map->objectkelompokpasienfk;
            // $detJenisPr = $map->objectdetailjenisprodukfk == null ? "" : "and x.objectdetailjenisprodukfk = " . $map->objectdetailjenisprodukfk;
            // $raw =  $map->raw;
            // $produk = $map->objectprodukfk == null ? "" : "and x.objectprodukfk = " . $map->objectprodukfk;
            $aingMacan = DB::select(
                DB::raw("
                    select * from (
                    select
                    pp.tglpelayanan,pr.namaproduk,pp.hargajual,pp.jumlah,pp.harganetto,
                    pd.objectkelompokpasienlastfk,pp.produkfk as objectprodukfk,
                    case when pp.aturanpakai is null then 'XObat' when pp.aturanpakai ='-' then 'XObat' else 'Obat' end as Obat,pp.produkfk,pr.objectdetailjenisprodukfk,pr.objectkelompokprodukbpjsfk,
                    (((case when pp.hargajual is null then 0 else pp.hargajual end)-case when pp.hargadiscount is null then 0 else pp.hargadiscount end)*pp.jumlah) + case when pp.jasa is null then 0 else pp.jasa end as harga,
                    pr.id as prid,
                    to_char(pp.tglpelayanan, 'YYYY-MM-DD') as tgl,
                    pp.norec as norec_pp
                    ,case when sr.norec is null then ru.objectdepartemenfk else ru2.objectdepartemenfk end as objectdepartemenfk,apd.objectruanganfk,pr.objectdetailjenisprodukfk,
                    cmap.objectcoadebetfk,cmap.objectcoakreditfk,pd.noregistrasi,ps.namapasien,ru3.jenis
                    from pelayananpasien_t as pp
                    inner join antrianpasiendiperiksa_t as apd on apd.norec=pp.noregistrasifk
                    inner join pasiendaftar_t as pd on pd.norec=apd.noregistrasifk
                    inner join pasien_m as ps on ps.id = pd.nocmfk
                    inner join ruangan_m as ru3 on ru3.id=pd.objectruanganlastfk
                    inner join ruangan_m as ru on ru.id=apd.objectruanganfk
                    inner join produk_m as pr on pr.id=pp.produkfk
                    left JOIN strukresep_t sr on sr.norec=pp.strukresepfk
                    left JOIN ruangan_m ru2 on ru2.id=sr.ruanganfk
                    inner join chartofaccountmapjurnal_t as cmap on
                        cmap.objectprodukfk=pp.produkfk
                        --and cmap.objectruanganfk=case when sr.norec is null then ru.id else ru2.id end
                        --cmap.objectdepartemenfk=case when sr.norec is null then ru.objectdepartemenfk else ru2.objectdepartemenfk end
                    left JOIN postingjurnaltransaksi_t as pjt on pjt.norecrelated=pp.norec and pjt.deskripsiproduktransaksi = 'pelayananpasien_t'
                    where pp.kdprofile in ($kdProfile)
                    and pp.tglpelayanan between :tglAwal and :tglAkhir
                    --and pd.noregistrasi = :noregistrasi
                    and pjt.norec is  null
                    and pp.strukresepfk is null
                    and pp.produkfk not in (402611)
                    and cmap.objectjenistrxfk=1
                    union all
                    ---//Pelayanan Obat
                    select
                    pp.tglpelayanan,pr.namaproduk,pp.hargajual,pp.jumlah,pp.harganetto,
                    pd.objectkelompokpasienlastfk,pp.produkfk as objectprodukfk,
                    case when pp.aturanpakai is null then 'XObat' when pp.aturanpakai ='-' then 'XObat' else 'Obat' end as Obat,pp.produkfk,pr.objectdetailjenisprodukfk,pr.objectkelompokprodukbpjsfk,
                    (((case when pp.hargajual is null then 0 else pp.hargajual end)-case when pp.hargadiscount is null then 0 else pp.hargadiscount end)*pp.jumlah) + case when pp.jasa is null then 0 else pp.jasa end as harga,
                    pr.id as prid,
                    to_char(pp.tglpelayanan, 'YYYY-MM-DD') as tgl,
                    pp.norec as norec_pp
                    ,case when sr.norec is null then ru.objectdepartemenfk else ru2.objectdepartemenfk end as objectdepartemenfk,apd.objectruanganfk,pr.objectdetailjenisprodukfk,
                    cmap.objectcoadebetfk,cmap.objectcoakreditfk,
                    pd.noregistrasi,ps.namapasien,ru3.jenis
                    from strukresep_t sr
                    inner join pelayananpasien_t as pp on pp.strukresepfk=sr.norec
                    inner join antrianpasiendiperiksa_t as apd ON apd.norec = sr.pasienfk
                    inner join pasiendaftar_t as pd on pd.norec=apd.noregistrasifk
                    inner join pasien_m as ps on ps.id = pd.nocmfk
                    inner join ruangan_m as ru3 on ru3.id=pd.objectruanganlastfk
                    inner join ruangan_m as ru on ru.id=apd.objectruanganfk
                    inner join produk_m as pr on pr.id=pp.produkfk
                    left JOIN ruangan_m ru2 on ru2.id=sr.ruanganfk
                    inner join chartofaccountmapjurnal_t as cmap on
                        cmap.objectprodukfk=pp.produkfk
                    left JOIN postingjurnaltransaksi_t as pjt on pjt.norecrelated=pp.norec and pjt.deskripsiproduktransaksi = 'pelayananpasien_t'
                    where pp.kdprofile in ($kdProfile)
                    and pp.tglpelayanan between :tglAwal and :tglAkhir
                    --and pd.noregistrasi = :noregistrasi
                    AND pd.statusenabled = true
                    and pjt.norec is  null
                    and pp.strukresepfk is not null
                    and pp.produkfk not in (402611)
                    and cmap.objectjenistrxfk=1
                    union all
                    ---//Pelayanan Obat kronis
                    select
                    pps.tglpelayanan,pr.namaproduk,pps.hargajual,pp.jumlah,pps.harganetto,
                    pd.objectkelompokpasienlastfk,pp.produkfk as objectprodukfk,
                    case when pps.aturanpakai is null then 'XObat' when pps.aturanpakai ='-' then 'XObat' else 'Obat' end as Obat,
                    pp.produkfk,pr.objectdetailjenisprodukfk,pr.objectkelompokprodukbpjsfk,
                    ((CASE WHEN pps.hargajual IS NULL THEN 0 ELSE pps.hargajual END - CASE
                    WHEN pps.hargadiscount IS NULL THEN 0 ELSE pps.hargadiscount END) * pp.jumlah) + CASE
                    WHEN pps.jasa IS NULL THEN 0 ELSE pps.jasa END as harga,
                    pr.id as prid,
                    to_char(pps.tglpelayanan, 'YYYY-MM-DD') as tgl,
                    pp.norec as norec_pp
                    ,case when sr.norec is null then ru.objectdepartemenfk else ru2.objectdepartemenfk end as objectdepartemenfk,apd.objectruanganfk,pr.objectdetailjenisprodukfk,
                    cmap.objectcoadebetfk,cmap.objectcoakreditfk,
                    pd.noregistrasi,ps.namapasien,ru3.jenis
                    from strukresep_t sr
                    INNER JOIN pelayananpasienobatkronis_t AS pp ON pp.strukresepfk = sr.norec
                    inner join pelayananpasien_t as pps on pps.strukresepfk = sr.norec AND pps.produkfk = pp.produkfk
                    inner join antrianpasiendiperiksa_t as apd on apd.norec = sr.pasienfk
                    inner join pasiendaftar_t as pd on pd.norec=apd.noregistrasifk
                    inner join pasien_m as ps on ps.id = pd.nocmfk
                    inner join ruangan_m as ru3 on ru3.id=pd.objectruanganlastfk
                    inner join ruangan_m as ru on ru.id=apd.objectruanganfk
                    inner join produk_m as pr on pr.id=pp.produkfk
                    left JOIN ruangan_m ru2 on ru2.id=sr.ruanganfk
                    inner join chartofaccountmapjurnal_t as cmap on
                        cmap.objectprodukfk=pp.produkfk
                    left JOIN postingjurnaltransaksi_t as pjt on pjt.norecrelated=pp.norec and pjt.deskripsiproduktransaksi = 'pelayananpasien_t'
                    where pp.kdprofile in ($kdProfile)
                    and pps.tglpelayanan between :tglAwal and :tglAkhir
                    --and pd.noregistrasi = :noregistrasi
                    AND pd.statusenabled = true
                    and pjt.norec is  null
                    and pps.strukresepfk is not null
                    and pp.produkfk not in (402611)
                    and cmap.objectjenistrxfk=1
                   ) as x  order by x.norec_pp  limit 1000
                "),
                array(
                    'tglAwal' => $request['tglAwal'],
                    'tglAkhir' => $request['tglAkhir']
                    // 'noregistrasi' => $request['noregistrasi']
                )
            );
            // return $aingMacan;
            // dd(count($aingMacan)+count($aingMacan33)+count($aingMacan333));
            foreach ($aingMacan as $item) {
                $coaAdministrasi = 'a';
                $coaKonsultasi = 'b';
                $coaVisite = 'c';
                $coaAkomodasi = 'd';
                $coaTindakan = 'e';
                $coaAlatCanggih = 'f';


                $totalRp = $item->harga; //($item->hargajual) * ($item->jumlah);
                $totalNettoRp = ($item->harganetto) * ($item->jumlah);

                $debetId = $item->objectcoadebetfk;
                $kreditId = $item->objectcoakreditfk;


                // $noBuktiTransaksi =$item->prid;
                $noBuktiTransaksi = $item->noregistrasi;

                $noJurnalIntern = '';
                $noPosting = '-';
                $noJurnalIntern = Carbon::parse($item->tglpelayanan)->format('ym') . 'PN' . Carbon::parse($item->tglpelayanan)->format('d') . '00001';
                $cekSudahPosting = PostingJurnal::where('norecrelated', $noJurnalIntern)->where('kdprofile', $kdProfile)->get();

                if ($this->getCountArray($cekSudahPosting) == 0) {
                    $postingJurnalTransaksi = new PostingJurnalTransaksi;
                    $norecHead = $postingJurnalTransaksi->generateNewId();
                    $postingJurnalTransaksi->norec = $norecHead;
                    $postingJurnalTransaksi->kdprofile = $kdProfile;
                    $postingJurnalTransaksi->noposting = $noPosting;
                    $postingJurnalTransaksi->nojurnal = 0;
                    $postingJurnalTransaksi->nojurnal_intern = $noJurnalIntern;
                    $postingJurnalTransaksi->objectjenisjurnalfk = 1;
                    $postingJurnalTransaksi->nobuktitransaksi = $noBuktiTransaksi;
                    $postingJurnalTransaksi->tglbuktitransaksi = $item->tglpelayanan;
                    $postingJurnalTransaksi->kdproduk = $item->prid;
                    $postingJurnalTransaksi->namaproduktransaksi = $item->namaproduk . ' a.n ' . $item->namapasien . ' (' . $item->noregistrasi . ')'; //. ' ' . $item->obat;
                    $postingJurnalTransaksi->deskripsiproduktransaksi = 'pelayananpasien_t';
                    $postingJurnalTransaksi->keteranganlainnya = 'PelayananPasien tgl. ' . $item->tgl;
                    $postingJurnalTransaksi->statusenabled = 1;
                    $postingJurnalTransaksi->norecrelated = $item->norec_pp;
                    $postingJurnalTransaksi->jenis = $item->jenis;
                    $postingJurnalTransaksi->save();

                    $norec_pj = $postingJurnalTransaksi->norec;

                    //debet
                    $postingJurnalTransaksiD = new PostingJurnalTransaksiD;
                    $postingJurnalTransaksiD->norec = $postingJurnalTransaksiD->generateNewId();
                    $postingJurnalTransaksiD->kdprofile = $kdProfile;
                    $postingJurnalTransaksiD->nojurnal = 0;
                    $postingJurnalTransaksiD->noposting = $noPosting;
                    $postingJurnalTransaksiD->objectaccountfk = $debetId;
                    $postingJurnalTransaksiD->hargasatuand = $totalRp;
                    $postingJurnalTransaksiD->hargasatuank = 0;
                    $postingJurnalTransaksiD->statusenabled = 1;
                    $postingJurnalTransaksiD->norecrelated = $norec_pj;
                    $postingJurnalTransaksiD->save();

                    //kredit
                    $postingJurnalTransaksiD = new PostingJurnalTransaksiD;
                    $postingJurnalTransaksiD->norec = $postingJurnalTransaksiD->generateNewId();
                    $postingJurnalTransaksiD->kdprofile = $kdProfile;
                    $postingJurnalTransaksiD->nojurnal = 0;
                    $postingJurnalTransaksiD->noposting = $noPosting;
                    $postingJurnalTransaksiD->objectaccountfk = $kreditId;
                    $postingJurnalTransaksiD->hargasatuand = 0;
                    $postingJurnalTransaksiD->hargasatuank = $totalRp;
                    $postingJurnalTransaksiD->statusenabled = 1;
                    $postingJurnalTransaksiD->norecrelated = $norec_pj;
                    $postingJurnalTransaksiD->save();
                } else {
                    $noJurnalIntern = Carbon::parse($item->tglpelayanan)->format('ym') . 'AJ' . Carbon::parse($item->tglpelayanan)->format('d') . '00001';
                    $postingJurnalTransaksi = new PostingJurnalTransaksi;
                    $norecHead = $postingJurnalTransaksi->generateNewId();
                    $postingJurnalTransaksi->norec = $norecHead;
                    $postingJurnalTransaksi->kdprofile = $kdProfile;
                    $postingJurnalTransaksi->noposting = $noPosting;
                    $postingJurnalTransaksi->nojurnal = 0;
                    $postingJurnalTransaksi->nojurnal_intern = $noJurnalIntern;
                    $postingJurnalTransaksi->objectjenisjurnalfk = 1;
                    $postingJurnalTransaksi->nobuktitransaksi = $noBuktiTransaksi;
                    $postingJurnalTransaksi->tglbuktitransaksi = date('Y-m-t', strtotime($item->tglpelayanan)); //$item->tglpelayanan;
                    $postingJurnalTransaksi->kdproduk = $item->prid;
                    $postingJurnalTransaksi->namaproduktransaksi = $item->namaproduk . ' a.n ' . $item->namapasien . ' (' . $item->noregistrasi . ')';
                    $postingJurnalTransaksi->deskripsiproduktransaksi = 'pelayananpasien_t';
                    $postingJurnalTransaksi->keteranganlainnya = 'Adjustment PelayananPasien tgl. ' . $item->tgl;
                    $postingJurnalTransaksi->statusenabled = true;
                    $postingJurnalTransaksi->norecrelated = $item->norec_pp;
                    $postingJurnalTransaksi->jenis = $item->jenis;
                    $postingJurnalTransaksi->save();


                    $norec_pj = $postingJurnalTransaksi->norec;

                    //debet
                    $postingJurnalTransaksiD = new PostingJurnalTransaksiD;
                    $postingJurnalTransaksiD->norec = $postingJurnalTransaksiD->generateNewId();
                    $postingJurnalTransaksiD->kdprofile = $kdProfile;
                    $postingJurnalTransaksiD->nojurnal = 0;
                    $postingJurnalTransaksiD->noposting = $noPosting;
                    $postingJurnalTransaksiD->objectaccountfk = $debetId;
                    $postingJurnalTransaksiD->hargasatuand = $totalRp;
                    $postingJurnalTransaksiD->hargasatuank = 0;
                    $postingJurnalTransaksiD->statusenabled = 1;
                    $postingJurnalTransaksiD->norecrelated = $norec_pj;
                    $postingJurnalTransaksiD->save();

                    //kredit
                    $postingJurnalTransaksiD = new PostingJurnalTransaksiD;
                    $postingJurnalTransaksiD->norec = $postingJurnalTransaksiD->generateNewId();
                    $postingJurnalTransaksiD->kdprofile = $kdProfile;
                    $postingJurnalTransaksiD->nojurnal = 0;
                    $postingJurnalTransaksiD->noposting = $noPosting;
                    $postingJurnalTransaksiD->objectaccountfk = $kreditId;
                    $postingJurnalTransaksiD->hargasatuand = 0;
                    $postingJurnalTransaksiD->hargasatuank = $totalRp;
                    $postingJurnalTransaksiD->statusenabled = 1;
                    $postingJurnalTransaksiD->norecrelated = $norec_pj;
                    $postingJurnalTransaksiD->save();
                }
            }
            // }

            //ADJ Perbedaan Harga
            // TODO : Jurnal Adj Perbedaan Harga
            //            $aingMacan2 = DB::select(DB::raw("
            //                        select
            //                        pp.tglpelayanan,pr.namaproduk,ru.namaruangan,ru_pd.namaruangan as namaruangan_pd,pp.hargajual,pp.jumlah,pp.harganetto,
            //                        case when pp.aturanpakai is null then 'XObat' when pp.aturanpakai ='-' then 'XObat' else 'Obat' end as Obat,pp.produkfk,pr.objectdetailjenisprodukfk,pr.objectkelompokprodukbpjsfk,
            //                        --(case when pp.hargajual is null then 0 else pp.hargajual end-(case when pp.hargadiscount is null then 0 else pp.hargadiscount end))*pp.jumlah as harga,
            //                        ((case when pp.hargajual is null then 0 else pp.hargajual end)*pp.jumlah) + case when jasa is null then 0 else jasa end as harga,
            //                        case when pjd.hargasatuand =0 then  pjd.hargasatuank else pjd.hargasatuand end as jrl,
            //                        case when pp.hargadiscount is null then 0 else pp.hargadiscount end as diskon,
            //                        djp.objectjenisprodukfk,ru.id as ruid,ru_pd.id as ruid_pd,pr.id as prid,pr.namaproduk,
            //                        to_char(pp.tglpelayanan, 'YYYY-MM-DD') as tgl,pp.norec as norec_pp,
            //                        ru.objectdepartemenfk as dept_apd,ru_pd.objectdepartemenfk as dept_pd
            //                        from postingjurnaltransaksi_t as pj
            //                        INNER JOIN postingjurnaltransaksid_t as pjd on pj.norec=pjd.norecrelated
            //                        INNER JOIN pelayananpasien_t as pp on pp.norec=pj.norecrelated and pj.deskripsiproduktransaksi='pelayananpasien_t'
            //                        INNER JOIN antrianpasiendiperiksa_t as apd on apd.norec=pp.noregistrasifk
            //                        INNER JOIN pasiendaftar_t as pd on pd.norec=apd.noregistrasifk
            //                        INNER JOIN pasien_m as ps on ps.id=pd.nocmfk
            //                        INNER JOIN ruangan_m as ru on ru.id=apd.objectruanganfk
            //                        INNER JOIN ruangan_m as ru_pd on ru_pd.id=pd.objectruanganlastfk
            //                        INNER JOIN produk_m as pr on pr.id=pp.produkfk
            //                        inner join detailjenisproduk_m as djp on djp.id=pr.objectdetailjenisprodukfk
            //                        INNER JOIN chartofaccount_m as coa on coa.id=pjd.objectaccountfk
            //                        left JOIN postingjurnaltransaksi_t as pj2 on pj2.norecrelated=pp.norec and pj2.deskripsiproduktransaksi='adjpelayananpasien_t'
            //                        where pj.tglbuktitransaksi between :tglAwal and :tglAkhir
            //                        and pjd.hargasatuand >0
            //                        and case when pjd.hargasatuand =0 then  pjd.hargasatuank else pjd.hargasatuand end  <>
            //                        (((case when pp.hargajual is null then 0 else pp.hargajual end)*pp.jumlah) + case when jasa is null then 0 else jasa end)
            //                         and pj2.norec is null
            //                        limit 100 ;"),
            //                array(
            //                    'tglAwal' => $request['tglAwal'],
            //                    'tglAkhir' => $request['tglAkhir'],
            //                )
            //            );
            //            foreach ($aingMacan2 as $item){
            //                $coaAdministrasi='a';
            //                $coaKonsultasi='b';
            //                $coaVisite='c';
            //                $coaAkomodasi='d';
            //                $coaTindakan='e';
            //                $coaAlatCanggih='f';
            //
            //                $ruanganId =$item->ruid;
            ////                $deptId =$item->dept_apd;
            //                $deptId =$item->dept_pd;
            //                if ($item->produkfk == 10011571){
            //                    $ruanganId =$item->ruid_pd;
            //                    $deptId =$item->dept_pd;
            //                }
            //                if ($item->produkfk == 10011572){
            //                    $ruanganId =$item->ruid_pd;
            //                    $deptId =$item->dept_pd;
            //                }
            //                foreach ($dataCoa as $coa){
            //                    if ($coa->ruid == $ruanganId){
            //                        if (strpos($coa->namaaccount , 'Administrasi')!= false){
            //                            $coaAdministrasi=$coa->coaid;
            //                        }
            //                        if (strpos($coa->namaaccount , 'Konsul')!= false){
            //                            $coaKonsultasi=$coa->coaid;
            //                        }
            //                        if (strpos($coa->namaaccount , 'Visite')!= false){
            //                            $coaVisite=$coa->coaid;
            //                        }
            //                        if (strpos($coa->namaaccount , 'Akomodasi')!= false){
            //                            $coaAkomodasi=$coa->coaid;
            //                        }
            //                        if (strpos($coa->namaaccount , 'Tindakan')!= false){
            //                            $coaTindakan=$coa->coaid;
            //                        }
            //                        if (strpos($coa->namaaccount , 'Alat Canggih')!= false){
            //                            $coaAlatCanggih=$coa->coaid;
            //                        }
            //                    }
            //                }
            //
            //                $totalRp = (float)$item->jrl - (float)$item->harga;//($item->hargajual) * ($item->jumlah);
            //
            //                $debet = 'Piutang Pasien dalam Perawatan ';
            //                $debetId = 1778;
            //                if ($item->objectjenisprodukfk != 97) {
            //                    if ($item->produkfk == 395 ) {//administrasi
            //                        $kreditId = $coaAdministrasi;
            //                    }elseif ($item->produkfk == 10011572 ) {//administrasi
            //                        $kreditId = $coaAdministrasi;
            //                    }elseif ( $item->produkfk == 10011571) {//administrasi
            //                        $kreditId = $coaAdministrasi;
            //                    }else{
            //                        if ($item->objectjenisprodukfk == 101){//visite
            //                            $kreditId = $coaVisite;
            //                        }elseif ( $item->objectjenisprodukfk == 100){//konsultasi
            //                            $kreditId = $coaKonsultasi;
            //                        }elseif ($item->objectjenisprodukfk == 99 ){//akomodasi
            //                            $kreditId = $coaAkomodasi;
            //                        }elseif ($item->objectjenisprodukfk == 27666 ){//alat canggih
            //                            $kreditId = $coaAlatCanggih;
            //                        }else{//Tindakan
            //                            $kreditId = $coaTindakan;
            //                        }
            //                    };
            //                } else {//OBAT
            //                    $kredit = 'Pendat. Tindakan Ka Instalasi Farmasi';
            //                    $kreditId = 2195;
            //                };
            //                $coacoa[] =array(
            //                    'namaProduk' => $item->namaproduk . '  ' . $item->namaruangan,
            //                    'kreditID' => $kreditId,
            //                    'coaAdministrasi' => $coaAdministrasi,
            //                    'coaKonsultasi' => $coaKonsultasi,
            //                    'coaVisite' => $coaVisite,
            //                    'coaAkomodasi' => $coaAkomodasi,
            //                    'coaTindakan' => $coaTindakan,
            //                    'coaAlatCanggih' => $coaAlatCanggih,
            //                    'data' => $item,
            //                );
            //                $noBuktiTransaksi =$item->prid;
            //                $noPosting = '-';
            //                $ddt = '';
            //                if ((float)$item->jrl > (float)$item->harga){
            //                    $totalRp = (float)$item->jrl - (float)$item->harga - (float)$item->diskon;
            //                }else{
            //                    $totalRp = (float)$item->harga - (float)$item->jrl;
            //                    $ddt = $debetId;
            //                    $debetId = $kreditId ;
            //                    $kreditId = $ddt;
            //                }
            //                if ($totalRp > 0){
            //                    $noJurnalIntern = Carbon::parse($item->tglpelayanan)->format('ym').'AJ'.Carbon::parse($item->tglpelayanan)->format('d').'00003';
            //                    $nojurnal = $this->getSequence('postingjurnaltransaksi_t_nojurnal_seq');
            //
            //                    $postingJurnalTransaksi = new PostingJurnalTransaksi;
            //                    $norecHead = $postingJurnalTransaksi->generateNewId();
            //                    $postingJurnalTransaksi->norec = $norecHead;
            //                    $postingJurnalTransaksi->kdprofile = 1;
            //                    $postingJurnalTransaksi->noposting =  $noPosting;
            //                    $postingJurnalTransaksi->nojurnal = $nojurnal;
            //                    $postingJurnalTransaksi->nojurnal_intern = $noJurnalIntern;
            //                    $postingJurnalTransaksi->objectjenisjurnalfk = 1;
            //                    $postingJurnalTransaksi->nobuktitransaksi = $noBuktiTransaksi;
            //                    $postingJurnalTransaksi->tglbuktitransaksi = date('Y-m-t', strtotime($item->tglpelayanan));//$item->tglpelayanan;
            //                    $postingJurnalTransaksi->kdproduk = $item->prid;
            //                    $postingJurnalTransaksi->namaproduktransaksi = $item->namaproduk;
            //                    $postingJurnalTransaksi->deskripsiproduktransaksi = 'adjpelayananpasien_t';
            //                    $postingJurnalTransaksi->keteranganlainnya = 'Adjustment Perubahan Harga tgl. ' . $item->tgl;
            //
            //                    $postingJurnalTransaksi->statusenabled = 1;
            //                    $postingJurnalTransaksi->norecrelated = $item->norec_pp;
            //                    $postingJurnalTransaksi->save();
            //
            //                    //debet
            //                    $postingJurnalTransaksiD = new PostingJurnalTransaksiD;
            //                    $postingJurnalTransaksiD->norec = $postingJurnalTransaksiD->generateNewId();
            //                    $postingJurnalTransaksiD->kdprofile = 1;
            //                    $postingJurnalTransaksiD->nojurnal = $nojurnal;
            //                    $postingJurnalTransaksiD->noposting = $noPosting;
            //                    $postingJurnalTransaksiD->objectaccountfk = $debetId;
            //                    $postingJurnalTransaksiD->hargasatuand = $totalRp;
            //                    $postingJurnalTransaksiD->hargasatuank = 0;
            //                    $postingJurnalTransaksiD->statusenabled = 1;
            //                    $postingJurnalTransaksiD->norecrelated = $norecHead;
            //                    $postingJurnalTransaksiD->save();
            //
            //                    //kredit
            //                    $postingJurnalTransaksiD = new PostingJurnalTransaksiD;
            //                    $postingJurnalTransaksiD->norec = $postingJurnalTransaksiD->generateNewId();
            //                    $postingJurnalTransaksiD->kdprofile = 1;
            //                    $postingJurnalTransaksiD->nojurnal = $nojurnal;
            //                    $postingJurnalTransaksiD->noposting = $noPosting;
            //                    $postingJurnalTransaksiD->objectaccountfk = $kreditId;
            //                    $postingJurnalTransaksiD->hargasatuand = 0;
            //                    $postingJurnalTransaksiD->hargasatuank = $totalRp;
            //                    $postingJurnalTransaksiD->statusenabled = 1;
            //                    $postingJurnalTransaksiD->norecrelated = $norecHead;
            //                    $postingJurnalTransaksiD->save();
            //                }
            //            }
            //end Perbedaan Harga

            // if ( 1==0){
            //Posting Obat Bebas
            // TODO : Jurnal Obat Bebas
            //DELETE JIKA DI TABEL TRANSAKSI SUDAH TIDAK ADA
            //            $delMacan = DB::select(DB::raw("
            //                        delete from postingjurnaltransaksid_t where norecrelated in (select pjt.norec from postingjurnaltransaksi_t as pjt
            //                        left JOIN strukpelayanandetail_t as pp on pp.norec=pjt.norecrelated and pp.tglpelayanan >'2019-01-01 00:00'
            //                        left JOIN postingjurnal_t as posted on pjt.nojurnal_intern=posted.norecrelated
            //                        where pjt.deskripsiproduktransaksi='pelayananpasien_tob' and pp.norec is null and posted.nojurnal_intern is null
            //                        and pjt.tglbuktitransaksi  >'2019-01-01 00:00')")
            //            );
            //            $delMacanHead = DB::select(DB::raw("
            //                        delete from postingjurnaltransaksi_t where norec in (select pjt.norec from postingjurnaltransaksi_t as pjt
            //                        left JOIN strukpelayanandetail_t as pp on pp.norec=pjt.norecrelated and pp.tglpelayanan >'2019-01-01 00:00'
            //                        left JOIN postingjurnal_t as posted on pjt.nojurnal_intern=posted.norecrelated
            //                        where pjt.deskripsiproduktransaksi='pelayananpasien_tob' and pp.norec is null and posted.nojurnal_intern is null
            //                        and pjt.tglbuktitransaksi  >'2019-01-01 00:00');")
            //            );
            $aingMacan33 = DB::select(
                DB::raw("

                    select pjt.norec as pjtnorec,sp.nostruk AS noregistrasi,sp.nostruk_intern AS nocm,sp.namapasien_klien AS namapasien,
                    sp.tglstruk as  tglpelayanan,pr.namaproduk,(spd.hargasatuan + spd.hargatambahan) as  hargajual,spd.qtyproduk as jumlah,spd.harganetto,
                    'Obat'  as Obat,spd.objectprodukfk as produkfk,pr.objectdetailjenisprodukfk,pr.objectkelompokprodukbpjsfk,
                    ((spd.hargasatuan  )*spd.qtyproduk)+ spd.hargatambahan as harga,
                    pr.id as prid,pr.namaproduk,
                    to_char(sp.tglstruk, 'YYYY-MM-DD') as tgl,spd.norec as norec_pp,
                    cmap.objectcoadebetfk,cmap.objectcoakreditfk,sp.objectruanganfk,ru.jenis
                    from strukpelayanandetail_t as spd
                    inner join strukpelayanan_t as sp on sp.norec=spd.nostrukfk
                    inner join ruangan_m as ru on ru.id=sp.objectruanganfk
                    inner join produk_m as pr on pr.id=spd.objectprodukfk
                    inner join chartofaccountmapjurnal_t as cmap on cmap.objectprodukfk=spd.objectprodukfk --and cmap.objectruanganfk=ru.id
                    left JOIN postingjurnaltransaksi_t as pjt on pjt.norecrelated=spd.norec and pjt.deskripsiproduktransaksi = 'pelayananpasien_tob'
                    where spd.kdprofile = $kdProfile and sp.tglstruk between :tglAwal and :tglAkhir and pjt.norec is  null
                    and substring(sp.nostruk,1,3)='OB/' and cmap.objectjenistrxfk = 1 and sp.objectkelompoktransaksifk=2
                    limit 100
                    "),
                array(
                    'tglAwal' => $request['tglAwal'],
                    'tglAkhir' => $request['tglAkhir'],
                )
            );

            foreach ($aingMacan33 as $item) {
                $coaAdministrasi = 'a';
                $coaKonsultasi = 'b';
                $coaVisite = 'c';
                $coaAkomodasi = 'd';
                $coaTindakan = 'e';
                $coaAlatCanggih = 'f';

                //                $ruanganId =$item->ruid;
                //                $deptId =$item->dept_apd;
                //                $deptId =$item->dept_pd;
                //                if ($item->produkfk == 10011571){
                //                    $ruanganId =$item->ruid_pd;
                //                    $deptId =$item->dept_pd;
                //                }
                //                if ($item->produkfk == 10011572){
                //                    $ruanganId =$item->ruid_pd;
                //                    $deptId =$item->dept_pd;
                //                }
                //                foreach ($dataCoa as $coa){
                //                    if ($coa->ruid == $ruanganId){
                //                        if (strpos($coa->namaaccount , 'Administrasi')!= false){
                //                            $coaAdministrasi=$coa->coaid;
                //                        }
                //                        if (strpos($coa->namaaccount , 'Konsul')!= false){
                //                            $coaKonsultasi=$coa->coaid;
                //                        }
                //                        if (strpos($coa->namaaccount , 'Visite')!= false){
                //                            $coaVisite=$coa->coaid;
                //                        }
                //                        if (strpos($coa->namaaccount , 'Akomodasi')!= false){
                //                            $coaAkomodasi=$coa->coaid;
                //                        }
                //                        if (strpos($coa->namaaccount , 'Tindakan')!= false){
                //                            $coaTindakan=$coa->coaid;
                //                        }
                //                        if (strpos($coa->namaaccount , 'Alat Canggih')!= false){
                //                            $coaAlatCanggih=$coa->coaid;
                //                        }
                //                    }
                //                }

                $totalRp = $item->harga; //($item->hargajual) * ($item->jumlah);
                $totalNettoRp = ($item->harganetto) * ($item->jumlah);

                //                $debet = 'Piutang Pasien dalam Perawatan ';
                //                $debetId = 1778;
                //                    $kredit = 'Pendat. Tindakan Ka Instalasi Farmasi';
                //                    $kreditId = 2195;

                $debetId = 65; //untuk piutang umum //$item->objectcoadebetfk;//1778;//10896;
                $kreditId = $item->objectcoakreditfk; //12211;
                //                };
                //                $coacoa[] =array(
                //                    'namaProduk' => $item->namaproduk . '  ' . $item->namaruangan,
                //                    'kreditID' => $kreditId,
                //                    'coaAdministrasi' => $coaAdministrasi,
                //                    'coaKonsultasi' => $coaKonsultasi,
                //                    'coaVisite' => $coaVisite,
                //                    'coaAkomodasi' => $coaAkomodasi,
                //                    'coaTindakan' => $coaTindakan,
                //                    'coaAlatCanggih' => $coaAlatCanggih,
                //                    'data' => $item,
                //                );
                // $noBuktiTransaksi =$item->prid;
                $noBuktiTransaksi = $item->noregistrasi;
                //                $namaPasien = $item->namapasien;
                //                $namaTindakan = $item->namaproduk;

                $noJurnalIntern = '';
                //                if ($item->objectjenisprodukfk != 97) {
                //                if ($deptId == 16) {
                //                    $noPosting = '-';//$this->generateCode(new PostingJurnalTransaksi, 'noposting', 14, 'RI-' . $this->getDateTime()->format('ym'));
                //                    $noJurnalIntern = Carbon::parse($item->tglpelayanan)->format('ym').'PN'.Carbon::parse($item->tglpelayanan)->format('d').'00002';
                //                }else{
                $noPosting = '-'; //$this->generateCode(new PostingJurnalTransaksi, 'noposting', 14, 'RJ-' . $this->getDateTime()->format('ym'));
                $noJurnalIntern = Carbon::parse($item->tglpelayanan)->format('ym') . 'PN' . Carbon::parse($item->tglpelayanan)->format('d') . '00001';
                //                }
                $cekSudahPosting = PostingJurnal::where('norecrelated', $noJurnalIntern)->where('kdprofile', $kdProfile)->get();

                if ($this->getCountArray($cekSudahPosting) == 0) {
                    //                    $nojurnal = $this->getSequence('postingjurnaltransaksi_t_nojurnal_seq');

                    $postingJurnalTransaksi = new PostingJurnalTransaksi;
                    $norecHead = $postingJurnalTransaksi->generateNewId();
                    $postingJurnalTransaksi->norec = $norecHead;
                    $postingJurnalTransaksi->kdprofile = $kdProfile;
                    $postingJurnalTransaksi->noposting = $noPosting;
                    $postingJurnalTransaksi->nojurnal = 0;
                    $postingJurnalTransaksi->nojurnal_intern = $noJurnalIntern;
                    $postingJurnalTransaksi->objectjenisjurnalfk = 1;
                    $postingJurnalTransaksi->nobuktitransaksi = $noBuktiTransaksi;
                    $postingJurnalTransaksi->tglbuktitransaksi = $item->tglpelayanan;
                    $postingJurnalTransaksi->kdproduk = $item->prid;
                    $postingJurnalTransaksi->namaproduktransaksi = $item->namaproduk . ' a.n ' . $item->namapasien . ' (' . $item->noregistrasi . ')';
                    $postingJurnalTransaksi->deskripsiproduktransaksi = 'pelayananpasien_tob';
                    //                    if ($deptId == 16){
                    //                        $postingJurnalTransaksi->keteranganlainnya = 'Pendapatan RI tgl. ' . $item->tgl;
                    //                    }else{
                    $postingJurnalTransaksi->keteranganlainnya = 'PelayananPasien tgl. ' . $item->tgl;
                    //                    }

                    $postingJurnalTransaksi->statusenabled = 1;
                    $postingJurnalTransaksi->norecrelated = $item->norec_pp;
                    $postingJurnalTransaksi->jenis = $item->jenis;
                    $postingJurnalTransaksi->save();

                    $norec_pj = $postingJurnalTransaksi->norec;

                    //debet
                    $postingJurnalTransaksiD = new PostingJurnalTransaksiD;
                    $postingJurnalTransaksiD->norec = $postingJurnalTransaksiD->generateNewId();
                    $postingJurnalTransaksiD->kdprofile = $kdProfile;
                    $postingJurnalTransaksiD->nojurnal = 0;
                    $postingJurnalTransaksiD->noposting = $noPosting;
                    $postingJurnalTransaksiD->objectaccountfk = $debetId;
                    $postingJurnalTransaksiD->hargasatuand = $totalRp;
                    $postingJurnalTransaksiD->hargasatuank = 0;
                    $postingJurnalTransaksiD->statusenabled = 1;
                    $postingJurnalTransaksiD->norecrelated = $norec_pj;
                    $postingJurnalTransaksiD->save();

                    //kredit
                    $postingJurnalTransaksiD = new PostingJurnalTransaksiD;
                    $postingJurnalTransaksiD->norec = $postingJurnalTransaksiD->generateNewId();
                    $postingJurnalTransaksiD->kdprofile = $kdProfile;
                    $postingJurnalTransaksiD->nojurnal = 0;
                    $postingJurnalTransaksiD->noposting = $noPosting;
                    $postingJurnalTransaksiD->objectaccountfk = $kreditId;
                    $postingJurnalTransaksiD->hargasatuand = 0;
                    $postingJurnalTransaksiD->hargasatuank = $totalRp;
                    $postingJurnalTransaksiD->statusenabled = 1;
                    $postingJurnalTransaksiD->norecrelated = $norec_pj;
                    $postingJurnalTransaksiD->save();
                } else {
                    $noJurnalIntern = Carbon::parse($item->tglpelayanan)->format('ym') . 'AJ' . Carbon::parse($item->tglpelayanan)->format('d') . '00001';
                    //                    $nojurnal = $this->getSequence('postingjurnaltransaksi_t_nojurnal_seq');

                    $postingJurnalTransaksi = new PostingJurnalTransaksi;
                    $norecHead = $postingJurnalTransaksi->generateNewId();
                    $postingJurnalTransaksi->norec = $norecHead;
                    $postingJurnalTransaksi->kdprofile = $kdProfile;
                    $postingJurnalTransaksi->noposting = $noPosting;
                    $postingJurnalTransaksi->nojurnal = 0;
                    $postingJurnalTransaksi->nojurnal_intern = $noJurnalIntern;
                    $postingJurnalTransaksi->objectjenisjurnalfk = 1;
                    $postingJurnalTransaksi->nobuktitransaksi = $noBuktiTransaksi;
                    $postingJurnalTransaksi->tglbuktitransaksi = date('Y-m-t', strtotime($item->tglpelayanan)); //$item->tglpelayanan;
                    $postingJurnalTransaksi->kdproduk = $item->prid;
                    $postingJurnalTransaksi->namaproduktransaksi = $item->namaproduk . ' a.n ' . $item->namapasien . ' (' . $item->noregistrasi . ')';
                    $postingJurnalTransaksi->deskripsiproduktransaksi = 'pelayananpasien_tob';
                    //                    if ($deptId == 16){
                    //                        $postingJurnalTransaksi->keteranganlainnya = 'Adjustment Pendapatan RI tgl. ' . $item->tgl;
                    //                    }else{
                    $postingJurnalTransaksi->keteranganlainnya = 'Adjustment PelayananPasien tgl. ' . $item->tgl;
                    //                    }

                    $postingJurnalTransaksi->statusenabled = 1;
                    $postingJurnalTransaksi->norecrelated = $item->norec_pp;
                    $postingJurnalTransaksi->jenis = $item->jenis;
                    $postingJurnalTransaksi->save();
                    $norec_pj = $postingJurnalTransaksi->norec;

                    //debet
                    $postingJurnalTransaksiD = new PostingJurnalTransaksiD;
                    $postingJurnalTransaksiD->norec = $postingJurnalTransaksiD->generateNewId();
                    $postingJurnalTransaksiD->kdprofile = $kdProfile;
                    $postingJurnalTransaksiD->nojurnal = 0;
                    $postingJurnalTransaksiD->noposting = $noPosting;
                    $postingJurnalTransaksiD->objectaccountfk = $debetId;
                    $postingJurnalTransaksiD->hargasatuand = $totalRp;
                    $postingJurnalTransaksiD->hargasatuank = 0;
                    $postingJurnalTransaksiD->statusenabled = 1;
                    $postingJurnalTransaksiD->norecrelated = $norec_pj;
                    $postingJurnalTransaksiD->save();

                    //kredit
                    $postingJurnalTransaksiD = new PostingJurnalTransaksiD;
                    $postingJurnalTransaksiD->norec = $postingJurnalTransaksiD->generateNewId();
                    $postingJurnalTransaksiD->kdprofile = $kdProfile;
                    $postingJurnalTransaksiD->nojurnal = 0;
                    $postingJurnalTransaksiD->noposting = $noPosting;
                    $postingJurnalTransaksiD->objectaccountfk = $kreditId;
                    $postingJurnalTransaksiD->hargasatuand = 0;
                    $postingJurnalTransaksiD->hargasatuank = $totalRp;
                    $postingJurnalTransaksiD->statusenabled = 1;
                    $postingJurnalTransaksiD->norecrelated = $norec_pj;
                    $postingJurnalTransaksiD->save();
                }
            }
            //end Posting Obat Bebas

            $aingMacan333 = DB::select(
                DB::raw("

                    select pjt.norec as pjtnorec,sp.nostruk AS noregistrasi,sp.nostruk_intern AS nocm,sp.namapasien_klien AS namapasien,
                    sp.tglstruk as  tglpelayanan,pr.namaproduk,(spd.hargasatuan + spd.hargatambahan) as  hargajual,
                    spd.qtyproduk as jumlah,spd.harganetto,
                    'Non Layanan'  as Obat,spd.objectprodukfk as produkfk,pr.objectdetailjenisprodukfk,
                    pr.objectkelompokprodukbpjsfk,
                    spd.qtyproduk *(spd.hargasatuan - CASE WHEN spd.hargadiscount IS NULL THEN 0 ELSE spd.hargadiscount END)
                    + CASE WHEN spd.hargatambahan IS NULL THEN 0 ELSE spd.hargatambahan END as harga,
                    pr.id as prid,pr.namaproduk,
                    to_char(sp.tglstruk, 'YYYY-MM-DD') as tgl,spd.norec as norec_pp,
                    cmap.objectcoadebetfk,cmap.objectcoakreditfk,sp.objectruanganfk,case when ru.jenis is null then 'RJ' else ru.jenis end as  jenis
                    from strukpelayanandetail_t as spd
                    inner join strukpelayanan_t as sp on sp.norec=spd.nostrukfk
                    left join ruangan_m as ru on ru.id=sp.objectruanganfk
                    inner join produk_m as pr on pr.id=spd.objectprodukfk
                    inner JOIN strukbuktipenerimaan_t AS sbk ON sbk.norec = sp.nosbmlastfk
                    inner join chartofaccountmapjurnal_t as cmap on cmap.objectprodukfk=spd.objectprodukfk --and cmap.objectruanganfk=ru.id
                    left JOIN postingjurnaltransaksi_t as pjt on pjt.norecrelated=spd.norec and pjt.deskripsiproduktransaksi = 'pelayananpasien_tob'
                    where spd.kdprofile = $kdProfile and sp.tglstruk between :tglAwal and :tglAkhir and pjt.norec is  null
                    and substring(sp.nostruk,1,2)='NL' AND sp.statusenabled = true and spd.objectprodukfk not in (402611)
                    and cmap.objectjenistrxfk=1
                    limit 100

             "),
                array(
                    'tglAwal' => $request['tglAwal'],
                    'tglAkhir' => $request['tglAkhir'],
                )
            );

            foreach ($aingMacan333 as $item) {
                $coaAdministrasi = 'a';
                $coaKonsultasi = 'b';
                $coaVisite = 'c';
                $coaAkomodasi = 'd';
                $coaTindakan = 'e';
                $coaAlatCanggih = 'f';

                $totalRp = $item->harga; //($item->hargajual) * ($item->jumlah);
                $totalNettoRp = ($item->harganetto) * ($item->jumlah);


                $debetId = 65; //untuk piutang umum //$item->objectcoadebetfk;//1778;//10896;
                $kreditId = $item->objectcoakreditfk; //12211;

                // $noBuktiTransaksi =$item->prid;
                $noBuktiTransaksi = $item->noregistrasi;


                $noJurnalIntern = '';
                //                if ($item->objectjenisprodukfk != 97) {
                //                if ($deptId == 16) {
                //                    $noPosting = '-';//$this->generateCode(new PostingJurnalTransaksi, 'noposting', 14, 'RI-' . $this->getDateTime()->format('ym'));
                //                    $noJurnalIntern = Carbon::parse($item->tglpelayanan)->format('ym').'PN'.Carbon::parse($item->tglpelayanan)->format('d').'00002';
                //                }else{
                $noPosting = '-'; //$this->generateCode(new PostingJurnalTransaksi, 'noposting', 14, 'RJ-' . $this->getDateTime()->format('ym'));
                $noJurnalIntern = Carbon::parse($item->tglpelayanan)->format('ym') . 'PN' . Carbon::parse($item->tglpelayanan)->format('d') . '00001';
                //                }
                $cekSudahPosting = PostingJurnal::where('norecrelated', $noJurnalIntern)->where('kdprofile', $kdProfile)->get();

                if ($this->getCountArray($cekSudahPosting) == 0) {
                    //                    $nojurnal = $this->getSequence('postingjurnaltransaksi_t_nojurnal_seq');

                    $postingJurnalTransaksi = new PostingJurnalTransaksi;
                    $norecHead = $postingJurnalTransaksi->generateNewId();
                    $postingJurnalTransaksi->norec = $norecHead;
                    $postingJurnalTransaksi->kdprofile = $kdProfile;
                    $postingJurnalTransaksi->noposting = $noPosting;
                    $postingJurnalTransaksi->nojurnal = 0;
                    $postingJurnalTransaksi->nojurnal_intern = $noJurnalIntern;
                    $postingJurnalTransaksi->objectjenisjurnalfk = 1;
                    $postingJurnalTransaksi->nobuktitransaksi = $noBuktiTransaksi;
                    $postingJurnalTransaksi->tglbuktitransaksi = $item->tglpelayanan;
                    $postingJurnalTransaksi->kdproduk = $item->prid;
                    $postingJurnalTransaksi->namaproduktransaksi = $item->namaproduk . ' a.n ' . $item->namapasien . ' (' . $item->noregistrasi . ')';
                    $postingJurnalTransaksi->deskripsiproduktransaksi = 'pelayananpasien_tob';
                    //                    if ($deptId == 16){
                    //                        $postingJurnalTransaksi->keteranganlainnya = 'Pendapatan RI tgl. ' . $item->tgl;
                    //                    }else{
                    $postingJurnalTransaksi->keteranganlainnya = 'PelayananPasien tgl. ' . $item->tgl;
                    //                    }

                    $postingJurnalTransaksi->statusenabled = 1;
                    $postingJurnalTransaksi->norecrelated = $item->norec_pp;
                    $postingJurnalTransaksi->jenis = $item->jenis;
                    $postingJurnalTransaksi->save();

                    $norec_pj = $postingJurnalTransaksi->norec;

                    //debet
                    $postingJurnalTransaksiD = new PostingJurnalTransaksiD;
                    $postingJurnalTransaksiD->norec = $postingJurnalTransaksiD->generateNewId();
                    $postingJurnalTransaksiD->kdprofile = $kdProfile;
                    $postingJurnalTransaksiD->nojurnal = 0;
                    $postingJurnalTransaksiD->noposting = $noPosting;
                    $postingJurnalTransaksiD->objectaccountfk = $debetId;
                    $postingJurnalTransaksiD->hargasatuand = $totalRp;
                    $postingJurnalTransaksiD->hargasatuank = 0;
                    $postingJurnalTransaksiD->statusenabled = 1;
                    $postingJurnalTransaksiD->norecrelated = $norec_pj;
                    $postingJurnalTransaksiD->save();

                    //kredit
                    $postingJurnalTransaksiD = new PostingJurnalTransaksiD;
                    $postingJurnalTransaksiD->norec = $postingJurnalTransaksiD->generateNewId();
                    $postingJurnalTransaksiD->kdprofile = $kdProfile;
                    $postingJurnalTransaksiD->nojurnal = 0;
                    $postingJurnalTransaksiD->noposting = $noPosting;
                    $postingJurnalTransaksiD->objectaccountfk = $kreditId;
                    $postingJurnalTransaksiD->hargasatuand = 0;
                    $postingJurnalTransaksiD->hargasatuank = $totalRp;
                    $postingJurnalTransaksiD->statusenabled = 1;
                    $postingJurnalTransaksiD->norecrelated = $norec_pj;
                    $postingJurnalTransaksiD->save();
                } else {
                    $noJurnalIntern = Carbon::parse($item->tglpelayanan)->format('ym') . 'AJ' . Carbon::parse($item->tglpelayanan)->format('d') . '00001';
                    //                    $nojurnal = $this->getSequence('postingjurnaltransaksi_t_nojurnal_seq');

                    $postingJurnalTransaksi = new PostingJurnalTransaksi;
                    $norecHead = $postingJurnalTransaksi->generateNewId();
                    $postingJurnalTransaksi->norec = $norecHead;
                    $postingJurnalTransaksi->kdprofile = $kdProfile;
                    $postingJurnalTransaksi->noposting = $noPosting;
                    $postingJurnalTransaksi->nojurnal = 0;
                    $postingJurnalTransaksi->nojurnal_intern = $noJurnalIntern;
                    $postingJurnalTransaksi->objectjenisjurnalfk = 1;
                    $postingJurnalTransaksi->nobuktitransaksi = $noBuktiTransaksi;
                    $postingJurnalTransaksi->tglbuktitransaksi = date('Y-m-t', strtotime($item->tglpelayanan)); //$item->tglpelayanan;
                    $postingJurnalTransaksi->kdproduk = $item->prid;
                    $postingJurnalTransaksi->namaproduktransaksi = $item->namaproduk . ' a.n ' . $item->namapasien . ' (' . $item->noregistrasi . ')';
                    $postingJurnalTransaksi->deskripsiproduktransaksi = 'pelayananpasien_tob';
                    //                    if ($deptId == 16){
                    //                        $postingJurnalTransaksi->keteranganlainnya = 'Adjustment Pendapatan RI tgl. ' . $item->tgl;
                    //                    }else{
                    $postingJurnalTransaksi->keteranganlainnya = 'Adjustment PelayananPasien tgl. ' . $item->tgl;
                    //                    }

                    $postingJurnalTransaksi->statusenabled = 1;
                    $postingJurnalTransaksi->norecrelated = $item->norec_pp;
                    $postingJurnalTransaksi->jenis = $item->jenis;
                    $postingJurnalTransaksi->save();
                    $norec_pj = $postingJurnalTransaksi->norec;

                    //debet
                    $postingJurnalTransaksiD = new PostingJurnalTransaksiD;
                    $postingJurnalTransaksiD->norec = $postingJurnalTransaksiD->generateNewId();
                    $postingJurnalTransaksiD->kdprofile = $kdProfile;
                    $postingJurnalTransaksiD->nojurnal = 0;
                    $postingJurnalTransaksiD->noposting = $noPosting;
                    $postingJurnalTransaksiD->objectaccountfk = $debetId;
                    $postingJurnalTransaksiD->hargasatuand = $totalRp;
                    $postingJurnalTransaksiD->hargasatuank = 0;
                    $postingJurnalTransaksiD->statusenabled = 1;
                    $postingJurnalTransaksiD->norecrelated = $norec_pj;
                    $postingJurnalTransaksiD->save();

                    //kredit
                    $postingJurnalTransaksiD = new PostingJurnalTransaksiD;
                    $postingJurnalTransaksiD->norec = $postingJurnalTransaksiD->generateNewId();
                    $postingJurnalTransaksiD->kdprofile = $kdProfile;
                    $postingJurnalTransaksiD->nojurnal = 0;
                    $postingJurnalTransaksiD->noposting = $noPosting;
                    $postingJurnalTransaksiD->objectaccountfk = $kreditId;
                    $postingJurnalTransaksiD->hargasatuand = 0;
                    $postingJurnalTransaksiD->hargasatuank = $totalRp;
                    $postingJurnalTransaksiD->statusenabled = 1;
                    $postingJurnalTransaksiD->norecrelated = $norec_pj;
                    $postingJurnalTransaksiD->save();
                }
            }
            //  dd(count($aingMacan)+count($aingMacan33)+count($aingMacan333));
            // $jmlrecort=count($aingMacan)+count($aingMacan33)+count($aingMacan333)
            // }

            $transStatus = 'true';
        } catch (\Exception $e) {
            $transStatus = 'false';
        }
        $transMessage = 'Posting';
        $req = $request->all();
        if ($transStatus == 'true') {
            $transMessage = $transMessage . " Jurnal Berhasil";
            DB::commit();
            $result = array(
                "status" => 200,
                "result" => array(
                    "data" => $aingMacan,
                    "count" => count($aingMacan), //+count($aingMacan33)+count($aingMacan333)
                    "countob" => count($aingMacan33),
                    "countnl" => count($aingMacan333),
                    //                "posting" => $cekSudahPosting,
                    // "data" => $aingMacan33 ,
                    "req" => $req,
                    "as" => '@epic',
                )
            );
        } else {
            $transMessage = $transMessage . " Jurnal Gagal!!";
            DB::rollBack();
            $result = array(
                "status" => 400,
                "result" => array(
                    "count" => count($aingMacan),
                    "data" => $aingMacan, //$noResep,
                    //                "coa" => $coacoa,
                    //                "dataCoa" => $dataCoa,
                    "as" => '@epic',
                )
            );
        }

        return $this->respond($result['result'], $result['status'], $transMessage);
    }


    public function PostingJurnal_pembayaran_tagihan(Request $request)
    {

        $kdProfile = (int) $this->kdProfile;




        $dataLogin = $request->all();
        //        ini_set('max_execution_time', 1000); //6 minutes
        DB::beginTransaction();
        try {

            $cekDataPelayanan = [];
            $cekDataPelayanan = DB::select(
                DB::raw("

                select sbm.norec
                from strukbuktipenerimaancarabayar_t as sbmc
                INNER JOIN strukbuktipenerimaan_t as sbm on sbm.norec=sbmc.nosbmfk
                left JOIN postingjurnaltransaksi_t as pjt on pjt.norecrelated=sbmc.norec
                where sbmc.kdprofile = $kdProfile and sbm.tglsbm BETWEEN :tglAwal and :tglAkhir and pjt.norec is null and sbm.statusenabled =true and (sbmc.totaldibayar is not null or sbmc.totaldibayar > 0)
                and sbm.keteranganlainnya in ('Pembayaran Tagihan','Pembayaran Non Layanan')
                limit 1
            "),
                array(
                    'tglAwal' => $request['tglAwal'],
                    'tglAkhir' => $request['tglAkhir'],
                )
            );
            // }
            // $dataCoa = \DB::table('chartofaccountmapjurnal_t as cmap')
            //         ->join('chartofaccount_m as coad','coad.id','=','cmap.objectcoadebetfk')
            //     ->join('chartofaccount_m as coak','coak.id','=','cmap.objectcoakreditfk')
            //         ->leftjoin('kelompokpasien_m as kp','kp.id','=','cmap.objectkelompokpasienfk')
            //         ->select('cmap.norec',
            //             'coad.id as jurnaldid','coad.noaccount as jurnaldno','coad.namaaccount as jurnaldnm',
            //             'coak.id as jurnalkid','coak.noaccount as jurnalkno','coak.namaaccount as jurnalknm','cmap.*',
            //             'kp.kelompokpasien')
            //         ->where('cmap.kdprofile', $kdProfile)
            //         ->where('cmap.statusenabled', true)
            //         ->where('objectjenistrxfk','=',3)
            //         ->orderBy('coad.noaccount')
            //         ->orderBy('coak.noaccount')
            //         ->get();

            $deptId = null;
            $ruId = null;
            $kelPasien = null;
            $detJenisPr = null;
            $raw = null;
            $tglAwal = $request['tglAwal'];
            $glAkhir = $request['tglAkhir'];
            $aingMacan = [];
            // return ($tglAwal);
            // return $dataCoa;
            // foreach ($dataCoa as $map){
            //         $kelPasien = $map->objectkelompokpasienfk == null ? "" : "and x.objectkelompokpasienlastfk = " . $map->objectkelompokpasienfk;
            //         $raw =  $map->raw;
            $isAktif = $this->settingDataFixed('PostingJurnal_pembayaran_tagihan_isaktif', $kdProfile);
            if ($isAktif == 1) {

                $aingMacan = DB::select(
                    DB::raw("select * from (
                    select case when pd.noregistrasi is null then sp.nostruk else pd.noregistrasi end as noregistrasi,
					case when ps.namapasien is null then sp.namapasien_klien else ps.namapasien end as namapasien,
					sbm.nosbm,case when sbmc.totaldibayar is null then sbm.totaldibayar else sbmc.totaldibayar end as totaldibayar,
					sbm.tglsbm,sbmc.objectcarabayarfk ,sbm.keteranganlainnya,
                    sbmc.norec as norec_smbc,sbm.keteranganlainnya,to_char(sbm.tglsbm, 'YYYY-MM-DD') as tgl,
					case when pd.objectkelompokpasienlastfk is not null then pd.objectkelompokpasienlastfk else 1 end as  objectkelompokpasienlastfk
                    ,coad.id as jurnaldid,coad.noaccount as jurnaldno,coad.namaaccount as jurnaldnm,
                    coak.id as jurnalkid,coak.noaccount as jurnalkno,coak.namaaccount as jurnalknm,pjt.norec
                    from strukbuktipenerimaancarabayar_t as sbmc
                    INNER JOIN strukbuktipenerimaan_t as sbm on sbm.norec=sbmc.nosbmfk
                    INNER JOIN strukpelayanan_t as sp on sp.nosbmlastfk=sbm.norec
                    left JOIN pasien_m as ps on ps.id=sp.nocmfk
                    left JOIN pasiendaftar_t as pd on pd.norec=sp.noregistrasifk
                    inner join chartofaccountmapjurnal_t as cmap on
                     cmap.objectcarabayarfk=sbmc.objectcarabayarfk and
                     cmap.objectkelompokpasienfk=(case when pd.objectkelompokpasienlastfk is not null then pd.objectkelompokpasienlastfk else 1 end)
                    INNER JOIN chartofaccount_m as coak on coak.id=cmap.objectcoakreditfk
					INNER JOIN chartofaccount_m as coad on coad.id=cmap.objectcoadebetfk
					left JOIN postingjurnaltransaksi_t as pjt on pjt.norecrelated=sbmc.norec
                    and pjt.deskripsiproduktransaksi = 'penerimaan_kas'
                    where sbmc.kdprofile = $kdProfile and sbm.tglsbm BETWEEN '$tglAwal' and '$glAkhir'
                    --and pjt.norec is null
                    and sbm.statusenabled =true
                    and cmap.objectjenistrxfk=3
                    --and (sbmc.totaldibayar is not null or sbmc.totaldibayar > 0)
                    and sbm.keteranganlainnya in ('Pembayaran Tagihan','Pembayaran Non Layanan')
                    limit 200) as x where x.norec is null and  x.totaldibayar>0
                    --

                ")
                    // , $kelPasien  $raw
                    // array(
                    //     'tglAwal' => $request['tglAwal'],
                    //     'tglAkhir' => $request['tglAkhir']
                    // )
                );
                // return $aingMacan;
                foreach ($aingMacan as $item) {
                    $nocm = $item->noregistrasi;
                    $nama = $item->namapasien;
                    $totalRp = $item->totaldibayar;

                    $noBuktiTransaksi = $item->nosbm;
                    $noPosting = '-'; //$this->generateCode(new PostingJurnalTransaksi, 'noposting', 14, 'KS-' . $this->getDateTime()->format('ym'));
                    $norec_smbc = $item->norec_smbc;

                    $newPJT = new PostingJurnalTransaksi;
                    $norecHead = $newPJT->generateNewId();
                    $newPJT->norec = $norecHead;
                    //                }
                    $noJurnalIntern = Carbon::parse($item->tglsbm)->format('ym') . 'PN' . Carbon::parse($item->tglsbm)->format('d') . '00003';
                    $cekSudahPosting = PostingJurnal::where('norecrelated', $noJurnalIntern)->where('kdprofile', $kdProfile)->get();

                    if (count($cekSudahPosting) == 0) {
                        $newPJT->kdprofile = $kdProfile;
                        $newPJT->noposting = $noPosting;
                        $newPJT->nojurnal = 0; //$nojurnal;
                        $newPJT->nojurnal_intern = $noJurnalIntern;
                        $newPJT->objectjenisjurnalfk = 1;
                        $newPJT->nobuktitransaksi = $noBuktiTransaksi;
                        $newPJT->tglbuktitransaksi = $item->tglsbm; // $this->getDateTime()->format('Y-m-d H:i:s');
                        $newPJT->kdproduk = null;
                        $newPJT->namaproduktransaksi = 'Pembayaran tagihan a.n ' . $nama . ' (' . $nocm . '), No.SBM(' . $noBuktiTransaksi . ')';
                        $newPJT->deskripsiproduktransaksi = 'penerimaan_kas';
                        $newPJT->keteranganlainnya = 'Penerimaan Kas ' . $item->tgl;;
                        $newPJT->statusenabled = true;
                        $newPJT->norecrelated = $item->norec_smbc;
                        $newPJT->save();


                        $debetId = $item->jurnaldid;
                        $kreditId = $item->jurnalkid;

                        //debet
                        $postingJurnalTransaksiD = new PostingJurnalTransaksiD;
                        $postingJurnalTransaksiD->norec = $postingJurnalTransaksiD->generateNewId();
                        $postingJurnalTransaksiD->kdprofile = $kdProfile;
                        $postingJurnalTransaksiD->nojurnal = 0; //$nojurnal;
                        $postingJurnalTransaksiD->noposting = $noPosting;
                        $postingJurnalTransaksiD->objectaccountfk = $debetId;
                        $postingJurnalTransaksiD->hargasatuand = $totalRp;
                        $postingJurnalTransaksiD->hargasatuank = 0;
                        $postingJurnalTransaksiD->statusenabled = true;
                        $postingJurnalTransaksiD->norecrelated = $norecHead;
                        $postingJurnalTransaksiD->save();
                        //kredit
                        $postingJurnalTransaksiD = new PostingJurnalTransaksiD;
                        $postingJurnalTransaksiD->norec = $postingJurnalTransaksiD->generateNewId();
                        $postingJurnalTransaksiD->kdprofile = $kdProfile;
                        $postingJurnalTransaksiD->nojurnal = 0; //$nojurnal;
                        $postingJurnalTransaksiD->noposting = $noPosting;
                        $postingJurnalTransaksiD->objectaccountfk = $kreditId;
                        $postingJurnalTransaksiD->hargasatuand = 0;
                        $postingJurnalTransaksiD->hargasatuank = $totalRp;
                        $postingJurnalTransaksiD->statusenabled = true;
                        $postingJurnalTransaksiD->norecrelated = $norecHead;
                        $postingJurnalTransaksiD->save();
                    }
                }
                // return "non aktif";
            }


            $aingMacanDeposit = [];

            $isAktif1 = $this->settingDataFixed('PostingJurnal_penerimaan_Deposit_isaktif', $kdProfile);

            if ($isAktif1 == 1) {

                $aingMacanDeposit = DB::select(
                    DB::raw("select * from (
                            select  case when pd.noregistrasi is null then sp.nostruk else pd.noregistrasi end as noregistrasi,
                            case when ps.namapasien is null then sp.namapasien_klien else ps.namapasien end as namapasien,
                            sbm.nosbm,case when sbmc.totaldibayar is null then sbm.totaldibayar else sbmc.totaldibayar end as totaldibayar,
                            sbm.tglsbm,sbmc.objectcarabayarfk ,sbm.keteranganlainnya,
                            sbmc.norec as norec_smbc,sbm.keteranganlainnya,to_char(sbm.tglsbm, 'YYYY-MM-DD') as tgl,
                            case when pd.objectkelompokpasienlastfk is not null then pd.objectkelompokpasienlastfk else 1 end as  objectkelompokpasienlastfk
                            ,coad.id as jurnaldid,coad.noaccount as jurnaldno,coad.namaaccount as jurnaldnm,
                            coak.id as jurnalkid,coak.noaccount as jurnalkno,coak.namaaccount as jurnalknm,pjt.norec
                            from strukbuktipenerimaancarabayar_t as sbmc
                            INNER JOIN strukbuktipenerimaan_t as sbm on sbm.norec=sbmc.nosbmfk
                            INNER JOIN strukpelayanan_t as sp on sp.norec=sbm.nostrukfk
                            left JOIN pasien_m as ps on ps.id=sp.nocmfk
                            left JOIN pasiendaftar_t as pd on pd.norec=sp.noregistrasifk
                            left join kelompokpasien_m as kp on kp.id=pd.objectkelompokpasienlastfk
                            inner join chartofaccountmapjurnal_t as cmap on
                            cmap.objectcarabayarfk=sbmc.objectcarabayarfk
                            --and cmap.objectkelompokpasienfk=pd.objectkelompokpasienlastfk
                            INNER JOIN chartofaccount_m as coak on coak.id=cmap.objectcoakreditfk
                            INNER JOIN chartofaccount_m as coad on coad.id=cmap.objectcoadebetfk
                            left JOIN postingjurnaltransaksi_t as pjt on pjt.norecrelated=sbmc.norec
                            and pjt.deskripsiproduktransaksi = 'penerimaan_Deposit'
                            where sbmc.kdprofile = $kdProfile and sbm.tglsbm BETWEEN '$tglAwal' and '$glAkhir'
                            and pjt.norec is null
                            and sbm.statusenabled =true
                            and cmap.objectjenistrxfk=8
                            and sbm.keteranganlainnya in ('Pembayaran Deposit Pasien')
                            limit 50) as x where x.norec is null and  x.totaldibayar>0

                        ")
                );
                // dd  ($aingMacanDeposit);
                foreach ($aingMacanDeposit as $item) {
                    // $nocm = $item->nocm;
                    $nocm = $item->noregistrasi;
                    $nama = $item->namapasien;
                    $totalRp = $item->totaldibayar;

                    $noBuktiTransaksi = $item->nosbm;
                    $noPosting = '-'; //$this->generateCode(new PostingJurnalTransaksi, 'noposting', 14, 'KS-' . $this->getDateTime()->format('ym'));
                    $norec_smbc = $item->norec_smbc;

                    $newPJT = new PostingJurnalTransaksi;
                    $norecHead = $newPJT->generateNewId();
                    $newPJT->norec = $norecHead;
                    //                }
                    $noJurnalIntern = Carbon::parse($item->tglsbm)->format('ym') . 'PN' . Carbon::parse($item->tglsbm)->format('d') . '00008';
                    $cekSudahPosting = PostingJurnal::where('norecrelated', $noJurnalIntern)->where('kdprofile', $kdProfile)->get();

                    if (count($cekSudahPosting) == 0) {
                        $newPJT->kdprofile = $kdProfile;
                        $newPJT->noposting = $noPosting;
                        $newPJT->nojurnal = 0; //$nojurnal;
                        $newPJT->nojurnal_intern = $noJurnalIntern;
                        $newPJT->objectjenisjurnalfk = 1;
                        $newPJT->nobuktitransaksi = $noBuktiTransaksi;
                        $newPJT->tglbuktitransaksi = $item->tglsbm; // $this->getDateTime()->format('Y-m-d H:i:s');
                        $newPJT->kdproduk = null;
                        $newPJT->namaproduktransaksi = 'Pembayaran Deposit Pasien a.n ' . $nama . ' ' . $nocm . '/' . $noBuktiTransaksi;
                        $newPJT->deskripsiproduktransaksi = 'penerimaan_Deposit';
                        $newPJT->keteranganlainnya = 'Penerimaan Deposit ' . $item->tgl;
                        $newPJT->statusenabled = true;
                        $newPJT->norecrelated = $item->norec_smbc;
                        $newPJT->save();


                        $debetId = $item->jurnaldid;
                        $kreditId = $item->jurnalkid;

                        //debet
                        $postingJurnalTransaksiD = new PostingJurnalTransaksiD;
                        $postingJurnalTransaksiD->norec = $postingJurnalTransaksiD->generateNewId();
                        $postingJurnalTransaksiD->kdprofile = $kdProfile;
                        $postingJurnalTransaksiD->nojurnal = 0; //$nojurnal;
                        $postingJurnalTransaksiD->noposting = $noPosting;
                        $postingJurnalTransaksiD->objectaccountfk = $debetId;
                        $postingJurnalTransaksiD->hargasatuand = $totalRp;
                        $postingJurnalTransaksiD->hargasatuank = 0;
                        $postingJurnalTransaksiD->statusenabled = true;
                        $postingJurnalTransaksiD->norecrelated = $norecHead;
                        $postingJurnalTransaksiD->save();
                        //kredit
                        $postingJurnalTransaksiD = new PostingJurnalTransaksiD;
                        $postingJurnalTransaksiD->norec = $postingJurnalTransaksiD->generateNewId();
                        $postingJurnalTransaksiD->kdprofile = $kdProfile;
                        $postingJurnalTransaksiD->nojurnal = 0; //$nojurnal;
                        $postingJurnalTransaksiD->noposting = $noPosting;
                        $postingJurnalTransaksiD->objectaccountfk = $kreditId;
                        $postingJurnalTransaksiD->hargasatuand = 0;
                        $postingJurnalTransaksiD->hargasatuank = $totalRp;
                        $postingJurnalTransaksiD->statusenabled = true;
                        $postingJurnalTransaksiD->norecrelated = $norecHead;
                        $postingJurnalTransaksiD->save();
                    }
                }
            }

            // }
            $transStatus = 'true';
        } catch (\Exception $e) {
            $transStatus = 'false';
        }

        $transMessage = "Posting";

        if ($transStatus == 'true') {
            $transMessage = $transMessage . "";
            DB::commit();
            $result = array(
                "status" => 200,
                "result" => array(
                    //                "message" => $transMessage,
                    //    "data" => $dataCoa,
                    "data2" => $aingMacan,
                    "count" => count($aingMacan),
                    "countDeposit" => count($aingMacanDeposit),
                    "as" => '@epic',
                )
            );
        } else {
            $transMessage = $transMessage . " Gagal!!";
            DB::rollBack();
            $result = array(
                "status" => 400,
                "result" => array(
                    "message" => $e->getMessage() . ' ' . $e->getLine(),
                    "as" => '@epic',
                )
            );
        }
        return $this->respond($result['result'], $result['status'], $transMessage);
    }
    public function PostingJurnal_strukpelayanan_t_verifikasi_tarek(Request $request)
    {
        $kdProfile = (int) $this->kdProfile;
        DB::beginTransaction();
        $isAktif = $this->settingFix('PostingJurnal_strukpelayanan_t_verifikasi_tarek_isaktif');
        // return $isAktif;
        if (!empty($isAktif) && $isAktif == 'false') {
            return $isAktif;
        }
        $dataLogin = $request->all();
        //        ini_set('max_execution_time', 1000); //6 minutes

        try {
            //
            $cekDataPelayanan = [];
            $cekDataPelayanan = DB::select(
                DB::raw("

                    select sbm.norec
                    from strukbuktipenerimaancarabayar_t as sbmc
                    INNER JOIN strukbuktipenerimaan_t as sbm on sbm.norec=sbmc.nosbmfk
                    left JOIN postingjurnaltransaksi_t as pjt on pjt.norecrelated=sbmc.norec
                    where sbmc.kdprofile = $kdProfile and sbm.tglsbm BETWEEN :tglAwal and :tglAkhir and pjt.norec is null and sbm.statusenabled =true and (sbmc.totaldibayar is not null or sbmc.totaldibayar > 0)
                    and sbm.keteranganlainnya in ('Pembayaran Tagihan','Pembayaran Non Layanan')
                    limit 1
                "),
                array(
                    'tglAwal' => $request['tglAwal'],
                    'tglAkhir' => $request['tglAkhir'],
                )
            );

            // $aingMacan=[];
            // $kelPasien = $map->objectkelompokpasienfk == null ? "" : "and x.objectkelompokpasienlastfk = " . $map->objectkelompokpasienfk;

            // $raw =  $map->raw;

            // select x.* from (
            //     select DISTINCT sp.norec as norec_sp,sp.nostruk,pd.noregistrasi,ps.namapasien,pd.tglpulang as tglstruk,sp.totalharusdibayar,
            //     case when sp.totalprekanan is null then 0 else sp.totalprekanan end as totalprekanan,
            //     kp.id as kpid,ru.objectdepartemenfk,ru.objectdepartemenfk as dept_pd,to_char(pd.tglpulang, 'YYYY-MM-DD') as tgl,pd.objectruanganlastfk,pd.objectrekananfk,
            //     ru.namaruangan,rkn.namarekanan,pd.objectkelompokpasienlastfk,ru.jenis,pjt.norec
            //     --,coad.id as jurnaldid,coad.noaccount as jurnaldno,coad.namaaccount as jurnaldnm,
            //     --coak.id as jurnalkid,coak.noaccount as jurnalkno,coak.namaaccount as jurnalknm
            //     from strukpelayanan_t as sp
            //     INNER JOIN  pelayananpasien_t as pp on pp.strukfk=sp.norec
            //     INNER JOIN antrianpasiendiperiksa_t as apd on apd.norec=pp.noregistrasifk
            //     INNER JOIN pasiendaftar_t as pd on pd.norec=apd.noregistrasifk
            //     INNER JOIN ruangan_m as ru on ru.id=pd.objectruanganlastfk
            //     INNER JOIN pasien_m as ps on ps.id=pd.nocmfk
            //     INNER JOIN kelompokpasien_m as kp on kp.id=pd.objectkelompokpasienlastfk
            //     left JOIN rekanan_m as rkn on rkn.id=pd.objectrekananfk
            //     --inner join chartofaccountmapjurnal_t as cmap on
            //         --cmap.objectrekananfk= (case when pd.objectrekananfk = 1 then null else pd.objectrekananfk end)
            //         --and cmap.objectkelompokpasienfk=pd.objectkelompokpasienlastfk
            //         --INNER JOIN chartofaccount_m as coak on coak.id=cmap.objectcoakreditfk
            //         --INNER JOIN chartofaccount_m as coad on coad.id=cmap.objectcoadebetfk
            //     left JOIN postingjurnaltransaksi_t as pjt on pjt.norecrelated=sp.norec and pjt.deskripsiproduktransaksi = 'verifikasi_tarek'
            //     where pp.kdprofile = $kdProfile and pd.tglpulang BETWEEN :tglAwal and :tglAkhir
            //     and pjt.norec is null
            //     --and cmap.objectjenistrxfk=2
            //     --and pd.noregistrasi='2308000415'
            //     and sp.statusenabled=true) as x  --and	cmap.objectjenistrxfk=2
            //     where x.norec is null LIMIT 1000


            $aingMacan = [];

            $aingMacan = DB::select(
                DB::raw("
                SELECT x.*,pjt.norec from (
                    select DISTINCT spp.norec as norec_sp,sp.nostruk, pd.noregistrasi,ps.namapasien,pd.tglpulang as tglstruk ,spp.totalppenjamin as totalprekanan, spp.kdrekananpenjamin as objectrekananfk,
                    rkp.namarekanan,ru.namaruangan,pd.objectkelompokpasienlastfk,ru.jenis,
                    to_char(pd.tglpulang, 'YYYY-MM-DD') as tgl
                    from strukpelayanan_t as sp
                    right join  strukpelayananpenjamin_t as spp on spp.nostrukfk=sp.norec
                    inner join pelayananpasien_t as pp on pp.strukfk = sp.norec
                    inner join antrianpasiendiperiksa_t as apd on apd.norec=pp.noregistrasifk
                    inner join pasiendaftar_t as pd on pd.norec=apd.noregistrasifk
                    left join pasien_m as ps on ps.id=pd.nocmfk
                    left join rekanan_m as rk on rk.id=pd.objectrekananfk
                    left join kelompokpasien_m as kp on kp.id=objectkelompokpasienlastfk
                    left join rekanan_m as rkp on rkp.id=spp.kdrekananpenjamin
                    left join ruangan_m as ru on ru.id=pd.objectruanganlastfk
                    where pp.kdprofile = $kdProfile and pd.tglpulang BETWEEN :tglAwal and :tglAkhir
                    and sp.statusenabled=true
                    union all
                    select DISTINCT sp.norec as norec_sp,sp.nostruk, pd.noregistrasi,ps.namapasien,pd.tglpulang as tglstruk ,sp.totalharusdibayar as totalprekanan,
                    pd.objectrekananfk,
                    rkn.namarekanan,ru.namaruangan,pd.objectkelompokpasienlastfk,ru.jenis,
                    to_char(pd.tglpulang, 'YYYY-MM-DD') as tgl
                    from strukpelayanan_t as sp
                    INNER JOIN  pelayananpasien_t as pp on pp.strukfk=sp.norec
                    INNER JOIN antrianpasiendiperiksa_t as apd on apd.norec=pp.noregistrasifk
                    INNER JOIN pasiendaftar_t as pd on pd.norec=apd.noregistrasifk
                    INNER JOIN ruangan_m as ru on ru.id=pd.objectruanganlastfk
                    INNER JOIN pasien_m as ps on ps.id=pd.nocmfk
                    INNER JOIN kelompokpasien_m as kp on kp.id=pd.objectkelompokpasienlastfk
                    left JOIN rekanan_m as rkn on rkn.id=pd.objectrekananfk
                    where pp.kdprofile = $kdProfile and pd.tglpulang BETWEEN :tglAwal and :tglAkhir
                    and sp.statusenabled=true
                    ) as x
                    left JOIN postingjurnaltransaksi_t as pjt on pjt.norecrelated=x.norec_sp and pjt.deskripsiproduktransaksi = 'verifikasi_tarek'
                    where x.totalprekanan>0 and pjt.norec is null  LIMIT 1000

                        "),
                array(
                    'tglAwal' => $request['tglAwal'],
                    'tglAkhir' => $request['tglAkhir'],
                )
            );


            // $kelPasien  $raw

            $coaruangandiskon = [];
            foreach ($aingMacan as $item) {
                $map = [];
                $rekanan = $item->objectrekananfk == null ? "and cmap.objectrekananfk is null" : "and cmap.objectrekananfk = " . $item->objectrekananfk;

                $cmap = DB::select(DB::raw("
                                select cmap.objectjenistrxfk, cmap.objectcoadebetfk as jurnaldid,coad.namaaccount as coadebet,
                                cmap.objectcoakreditfk as jurnalkid,coak.namaaccount as coakredit
                                from chartofaccountmapjurnal_t as cmap
                                INNER JOIN chartofaccount_m as coad on coad.id=cmap.objectcoadebetfk
                                INNER JOIN chartofaccount_m as coak on coak.id=cmap.objectcoakreditfk
                                where cmap.objectjenistrxfk=2  $rekanan
                                --limit 1

                    "));


                if (!empty($cmap)) {

                    //  dd($cmap[0]->jurnaldid);
                    $debetId = $cmap[0]->jurnaldid;
                    $kreditId = $cmap[0]->jurnalkid;

                    $noReg = $item->noregistrasi;
                    $namaPasien = $item->namapasien;
                    $noBuktiTransaksi = $item->nostruk;
                    $noPosting = '-';

                    // $debetId = $item->jurnaldid;
                    // $kreditId = $item->jurnalkid;

                    $noJurnalIntern = Carbon::parse($item->tglstruk)->format('ym') . 'PN' . Carbon::parse($item->tglstruk)->format('d') . '00002';

                    $cekSudahPosting = PostingJurnal::where('norecrelated', $noJurnalIntern)->where('kdprofile', $kdProfile)->get();
                    if ($this->getCountArray($cekSudahPosting) == 0) {
                        $postingJurnalTransaksi = new PostingJurnalTransaksi;
                        $norecHead = $postingJurnalTransaksi->generateNewId();
                        $postingJurnalTransaksi->norec = $norecHead;
                        $postingJurnalTransaksi->kdprofile = $kdProfile;
                        $postingJurnalTransaksi->noposting = $noPosting;
                        $postingJurnalTransaksi->nojurnal = 0;
                        $postingJurnalTransaksi->objectjenisjurnalfk = 1;
                        $postingJurnalTransaksi->nobuktitransaksi = $noBuktiTransaksi;
                        $postingJurnalTransaksi->tglbuktitransaksi = $item->tglstruk; /// $this->getDateTime()->format('Y-m-d H:i:s');
                        $postingJurnalTransaksi->kdproduk = null;
                        $postingJurnalTransaksi->namaproduktransaksi = 'Verifikasi tagihan ' . $noReg . ', ' . $namaPasien . ' di TataRekening';
                        $postingJurnalTransaksi->deskripsiproduktransaksi = 'verifikasi_tarek';
                        $postingJurnalTransaksi->keteranganlainnya = 'Verifikasi Tagihan Tgl. ' . $item->tgl;
                        $postingJurnalTransaksi->nojurnal_intern = $noJurnalIntern;
                        $postingJurnalTransaksi->statusenabled = 1;
                        $postingJurnalTransaksi->norecrelated = $item->norec_sp;
                        $postingJurnalTransaksi->jenis = $item->jenis;
                        $postingJurnalTransaksi->save();

                        $norec_pj = $postingJurnalTransaksi->norec;

                        // $totalRp = $item->totalharusdibayar;
                        $totalRekananRp = $item->totalprekanan;

                        if ($totalRekananRp > 0) {
                            //     $kreditId = 340;
                            //     $debetId = 14;
                            // if ($item->kpid == 2) {//BPJS
                            //     $debetId = 16;//PIUTANG BPJS
                            // }
                            //debet
                            $postingJurnalTransaksiD = new PostingJurnalTransaksiD;
                            $postingJurnalTransaksiD->norec = $postingJurnalTransaksiD->generateNewId();
                            $postingJurnalTransaksiD->kdprofile = $kdProfile;
                            $postingJurnalTransaksiD->nojurnal = 0;
                            $postingJurnalTransaksiD->noposting = $noPosting;
                            $postingJurnalTransaksiD->objectaccountfk = $debetId;
                            $postingJurnalTransaksiD->hargasatuand = $totalRekananRp;
                            $postingJurnalTransaksiD->hargasatuank = 0;
                            $postingJurnalTransaksiD->statusenabled = 1;
                            $postingJurnalTransaksiD->norecrelated = $norec_pj;
                            $postingJurnalTransaksiD->save();

                            //kredit
                            $postingJurnalTransaksiD = new PostingJurnalTransaksiD;
                            $postingJurnalTransaksiD->norec = $postingJurnalTransaksiD->generateNewId();
                            $postingJurnalTransaksiD->kdprofile = $kdProfile;
                            $postingJurnalTransaksiD->nojurnal = 0;
                            $postingJurnalTransaksiD->noposting = $noPosting;
                            $postingJurnalTransaksiD->objectaccountfk = $kreditId;
                            $postingJurnalTransaksiD->hargasatuand = 0;
                            $postingJurnalTransaksiD->hargasatuank = $totalRekananRp;
                            $postingJurnalTransaksiD->statusenabled = 1;
                            $postingJurnalTransaksiD->norecrelated = $norec_pj;
                            $postingJurnalTransaksiD->save();
                        }
                        // if ($totalRp > 0) {
                        // //     $kreditId2 = 340;
                        // //     $debetId2 = 14;
                        // // if ($item->kpid == 2) {//BPJS
                        // //     $debetId2 = 16;//PIUTANG BPJS
                        // // }

                        //     //debet
                        //     $postingJurnalTransaksiD = new PostingJurnalTransaksiD;
                        //     $postingJurnalTransaksiD->norec = $postingJurnalTransaksiD->generateNewId();
                        //     $postingJurnalTransaksiD->kdprofile = $kdProfile;
                        //     $postingJurnalTransaksiD->nojurnal = 0;
                        //     $postingJurnalTransaksiD->noposting = $noPosting;
                        //     $postingJurnalTransaksiD->objectaccountfk = $debetId;
                        //     $postingJurnalTransaksiD->hargasatuand = $totalRp;
                        //     $postingJurnalTransaksiD->hargasatuank = 0;
                        //     $postingJurnalTransaksiD->statusenabled = 1;
                        //     $postingJurnalTransaksiD->norecrelated = $norec_pj;
                        //     $postingJurnalTransaksiD->save();

                        //     //kredit
                        //     $postingJurnalTransaksiD = new PostingJurnalTransaksiD;
                        //     $postingJurnalTransaksiD->norec = $postingJurnalTransaksiD->generateNewId();
                        //     $postingJurnalTransaksiD->kdprofile = $kdProfile;
                        //     $postingJurnalTransaksiD->nojurnal = 0;
                        //     $postingJurnalTransaksiD->noposting = $noPosting;
                        //     $postingJurnalTransaksiD->objectaccountfk = $kreditId;
                        //     $postingJurnalTransaksiD->hargasatuand = 0;
                        //     $postingJurnalTransaksiD->hargasatuank = $totalRp;
                        //     $postingJurnalTransaksiD->statusenabled = 1;
                        //     $postingJurnalTransaksiD->norecrelated = $norec_pj;
                        //     $postingJurnalTransaksiD->save();
                        // }
                    } else {
                        $noJurnalIntern = Carbon::parse($item->tglstruk)->format('ym') . 'AJ' . Carbon::parse($item->tglstruk)->format('d') . '00002';
                        $postingJurnalTransaksi = new PostingJurnalTransaksi;
                        $norecHead = $postingJurnalTransaksi->generateNewId();
                        $postingJurnalTransaksi->norec = $norecHead;
                        $postingJurnalTransaksi->kdprofile = $kdProfile;
                        $postingJurnalTransaksi->noposting = $noPosting;
                        $postingJurnalTransaksi->nojurnal = 0;
                        $postingJurnalTransaksi->objectjenisjurnalfk = 1;
                        $postingJurnalTransaksi->nobuktitransaksi = $noBuktiTransaksi;
                        $postingJurnalTransaksi->tglbuktitransaksi = date('Y-m-t', strtotime($item->tglstruk)); //$item->tglstruk;/// $this->getDateTime()->format('Y-m-d H:i:s');
                        $postingJurnalTransaksi->kdproduk = null;
                        $postingJurnalTransaksi->namaproduktransaksi = 'Adjustment Verifikasi tagihan ' . $noReg . ', ' . $namaPasien . ' di TataRekening';
                        $postingJurnalTransaksi->deskripsiproduktransaksi = 'verifikasi_tarek';
                        $postingJurnalTransaksi->keteranganlainnya = 'Adjustment Verifikasi Tagihan Tgl. ' . $item->tgl;
                        $postingJurnalTransaksi->nojurnal_intern = $noJurnalIntern;
                        $postingJurnalTransaksi->statusenabled = 1;
                        $postingJurnalTransaksi->norecrelated = $item->norec_sp;
                        $postingJurnalTransaksi->jenis = $item->jenis;
                        $postingJurnalTransaksi->save();

                        $norec_pj = $postingJurnalTransaksi->norec;

                        // $totalRp = $item->totalharusdibayar;
                        $totalRekananRp = $item->totalprekanan;

                        if ($totalRekananRp > 0) {


                            //debet
                            $postingJurnalTransaksiD = new PostingJurnalTransaksiD;
                            $postingJurnalTransaksiD->norec = $postingJurnalTransaksiD->generateNewId();
                            $postingJurnalTransaksiD->kdprofile = $kdProfile;
                            $postingJurnalTransaksiD->nojurnal = 0;
                            $postingJurnalTransaksiD->noposting = $noPosting;
                            $postingJurnalTransaksiD->objectaccountfk = $debetId;
                            $postingJurnalTransaksiD->hargasatuand = $totalRekananRp;
                            $postingJurnalTransaksiD->hargasatuank = 0;
                            $postingJurnalTransaksiD->statusenabled = 1;
                            $postingJurnalTransaksiD->norecrelated = $norec_pj;
                            $postingJurnalTransaksiD->save();

                            //kredit
                            $postingJurnalTransaksiD = new PostingJurnalTransaksiD;
                            $postingJurnalTransaksiD->norec = $postingJurnalTransaksiD->generateNewId();
                            $postingJurnalTransaksiD->kdprofile = $kdProfile;
                            $postingJurnalTransaksiD->nojurnal = 0;
                            $postingJurnalTransaksiD->noposting = $noPosting;
                            $postingJurnalTransaksiD->objectaccountfk = $kreditId;
                            $postingJurnalTransaksiD->hargasatuand = 0;
                            $postingJurnalTransaksiD->hargasatuank = $totalRekananRp;
                            $postingJurnalTransaksiD->statusenabled = 1;
                            $postingJurnalTransaksiD->norecrelated = $norec_pj;
                            $postingJurnalTransaksiD->save();
                        }
                        // if ($totalRp > 0) {
                        // //     $kreditId = 340;
                        // //     $debetId = 14;
                        // // if ($item->kpid == 2) {//BPJS
                        // //     $debetId = 16;//PIUTANG BPJS
                        // // }

                        //     //debet
                        //     $postingJurnalTransaksiD = new PostingJurnalTransaksiD;
                        //     $postingJurnalTransaksiD->norec = $postingJurnalTransaksiD->generateNewId();
                        //     $postingJurnalTransaksiD->kdprofile = $kdProfile;
                        //     $postingJurnalTransaksiD->nojurnal = 0;
                        //     $postingJurnalTransaksiD->noposting = $noPosting;
                        //     $postingJurnalTransaksiD->objectaccountfk = $debetId;
                        //     $postingJurnalTransaksiD->hargasatuand = $totalRp;
                        //     $postingJurnalTransaksiD->hargasatuank = 0;
                        //     $postingJurnalTransaksiD->statusenabled = 1;
                        //     $postingJurnalTransaksiD->norecrelated = $norec_pj;
                        //     $postingJurnalTransaksiD->save();

                        //     //kredit
                        //     $postingJurnalTransaksiD = new PostingJurnalTransaksiD;
                        //     $postingJurnalTransaksiD->norec = $postingJurnalTransaksiD->generateNewId();
                        //     $postingJurnalTransaksiD->kdprofile = $kdProfile;
                        //     $postingJurnalTransaksiD->nojurnal = 0;
                        //     $postingJurnalTransaksiD->noposting = $noPosting;
                        //     $postingJurnalTransaksiD->objectaccountfk = $kreditId;
                        //     $postingJurnalTransaksiD->hargasatuand = 0;
                        //     $postingJurnalTransaksiD->hargasatuank = $totalRp;
                        //     $postingJurnalTransaksiD->statusenabled = 1;
                        //     $postingJurnalTransaksiD->norecrelated = $norec_pj;
                        //     $postingJurnalTransaksiD->save();
                        // }
                    }
                }
            }
            //  }

            //############################################################################################################################################
            //##################################################### DEPOSIT ##############################################################################
            //############################################################################################################################################
            // TODO : Jurnal Deposit
            //DELETE JIKA DI TABEL TRANSAKSI SUDAH TIDAK ADA
            $aingMaung = [];
            if (1 == 0) {
                //                $delMacan = DB::select(DB::raw("
                //                    delete from postingjurnaltransaksid_t where norecrelated in (select pjt.norec from postingjurnaltransaksi_t as pjt
                //                    INNER JOIN  strukbuktipenerimaan_t as pp on pp.norec=pjt.norecrelated and pp.tglsbm >'2019-01-01 00:00'
                //                    left JOIN postingjurnal_t as posted on pjt.nojurnal_intern=posted.norecrelated
                //                    where pjt.deskripsiproduktransaksi='penerimaan_deposit' and pp.norec is null and posted.nojurnal_intern is null
                //                    and pjt.tglbuktitransaksi  >'2019-01-01 00:00');")
                //                );
                //                $delMacanHead = DB::select(DB::raw("
                //                    delete from postingjurnaltransaksi_t where norec in (select pjt.norec from postingjurnaltransaksi_t as pjt
                //                    INNER JOIN  strukbuktipenerimaan_t as pp on pp.norec=pjt.norecrelated and pp.tglsbm >'2019-01-01 00:00'
                //                    left JOIN postingjurnal_t as posted on pjt.nojurnal_intern=posted.norecrelated
                //                    where pjt.deskripsiproduktransaksi='penerimaan_deposit' and pp.norec is null and posted.nojurnal_intern is null
                //                    and pjt.tglbuktitransaksi  >'2019-01-01 00:00');")
                //                );

                $aingMaung = DB::select(
                    DB::raw("select ps.nocm,ps.namapasien,sbm.nosbm,sbmc.totaldibayar,sbm.tglsbm,sbmc.objectcarabayarfk ,
                    sbmc.norec as norec_smbc,to_char(sbm.tglsbm, 'YYYY-MM-DD') as tgl
                    ,coad.id as jurnaldid,coad.noaccount as jurnaldno,coad.namaaccount as jurnaldnm,
                    coak.id as jurnalkid,coak.noaccount as jurnalkno,coak.namaaccount as jurnalknm
                    from strukbuktipenerimaancarabayar_t as sbmc
                    INNER JOIN strukbuktipenerimaan_t as sbm on sbm.norec=sbmc.nosbmfk
                    left JOIN strukpelayanan_t as sp on sp.norec=sbm.nostrukfk
                    left JOIN pasien_m as ps on ps.id=sp.nocmfk
                    INNER JOIN pasiendaftar_t as pd on pd.norec=sp.noregistrasifk
                    inner join chartofaccountmapjurnal_t as cmap on
                            cmap.objectruanganfk=pd.objectruanganlastfk and
                            cmap.objectkelompokpasienfk=pd.objectkelompokpasienlastfk
                            INNER JOIN chartofaccount_m as coak on coak.id=cmap.objectcoakreditfk
                            INNER JOIN chartofaccount_m as coad on coad.id=cmap.objectcoadebetfk
                    left JOIN postingjurnaltransaksi_t as pjt on pjt.norecrelated=sbmc.norec
                    where sbmc.kdprofile = $kdProfile and sbm.tglsbm BETWEEN :tglAwal and :tglAkhir and pjt.norec is null
                    and sbm.keteranganlainnya = 'Pembayaran Deposit Pasien'"),
                    array(
                        'tglAwal' => $request['tglAwal'],
                        'tglAkhir' => $request['tglAkhir'],
                    )
                );


                foreach ($aingMaung as $item) {
                    $nocm = $item->nocm;
                    $nama = $item->namapasien;
                    $noBuktiTransaksi = $item->nosbm;
                    $totalRp = $item->totaldibayar;
                    $noPosting = '-'; //$this->generateCode(new PostingJurnalTransaksi, 'noposting', 14, 'NT-' . $this->getDateTime()->format('ym'));
                    //                    $nojurnal = $this->getSequence('postingjurnaltransaksi_t_nojurnal_seq');

                    $noJurnalIntern = Carbon::parse($item->tglsbm)->format('ym') . 'PN' . Carbon::parse($item->tglsbm)->format('d') . '00003';
                    $cekSudahPosting = PostingJurnal::where('norecrelated', $noJurnalIntern)->where('kdprofile', $kdProfile)->get();

                    if ($this->getCountArray($cekSudahPosting) == 0) {

                        $newPJT = new PostingJurnalTransaksi;
                        $norecHead = $newPJT->generateNewId();
                        $newPJT->norec = $norecHead;
                        $newPJT->kdprofile = $kdProfile;
                        $newPJT->noposting = $noPosting;
                        $newPJT->nojurnal = 0;
                        $newPJT->nojurnal_intern = $noJurnalIntern;
                        $newPJT->objectjenisjurnalfk = 1;
                        $newPJT->nobuktitransaksi = $noBuktiTransaksi;
                        $newPJT->tglbuktitransaksi = $item->tglsbm; // $this->getDateTime()->format('Y-m-d H:i:s');
                        $newPJT->kdproduk = null;
                        $newPJT->namaproduktransaksi = 'Penerimaan Deposit dari ' . $nocm . ' ' . $nama;
                        $newPJT->deskripsiproduktransaksi = 'penerimaan_deposit';
                        $newPJT->keteranganlainnya = 'Penerimaan Deposit Tgl. ' . $item->tgl;

                        //                $newPJT->keteranganlainnya = 'Penerimaan Deposit dari ' . $nocm . ' ' . $nama;
                        $newPJT->statusenabled = true;
                        $newPJT->norecrelated = $item->norec_smbc;
                        $newPJT->save();

                        $norec_pj = $postingJurnalTransaksi->norec;

                        $debetId = 11153; //Uang Muka Layanan
                        $kreditId = 1778; //Piutang Pasien dalam perawatan
                        //     $debetId = $item->jurnaldid;
                        //     $kreditId = $item->jurnalkid;
                        //debet
                        $postingJurnalTransaksiD = new PostingJurnalTransaksiD;
                        $postingJurnalTransaksiD->norec = $postingJurnalTransaksiD->generateNewId();
                        $postingJurnalTransaksiD->kdprofile = $kdProfile;
                        $postingJurnalTransaksiD->nojurnal = 0;
                        $postingJurnalTransaksiD->noposting = $noPosting;
                        $postingJurnalTransaksiD->objectaccountfk = $debetId;
                        $postingJurnalTransaksiD->hargasatuand = $totalRp;
                        $postingJurnalTransaksiD->hargasatuank = 0;
                        $postingJurnalTransaksiD->statusenabled = 1;
                        $postingJurnalTransaksiD->norecrelated = $norec_pj;
                        $postingJurnalTransaksiD->save();
                        //kredit
                        $postingJurnalTransaksiD = new PostingJurnalTransaksiD;
                        $postingJurnalTransaksiD->norec = $postingJurnalTransaksiD->generateNewId();
                        $postingJurnalTransaksiD->kdprofile = $kdProfile;
                        $postingJurnalTransaksiD->nojurnal = 0;
                        $postingJurnalTransaksiD->noposting = $noPosting;
                        $postingJurnalTransaksiD->objectaccountfk = $kreditId;
                        $postingJurnalTransaksiD->hargasatuand = 0;
                        $postingJurnalTransaksiD->hargasatuank = $totalRp;
                        $postingJurnalTransaksiD->statusenabled = 1;
                        $postingJurnalTransaksiD->norecrelated = $norec_pj;
                        $postingJurnalTransaksiD->save();
                    } else {
                        $noJurnalIntern = Carbon::parse($item->tglsbm)->format('ym') . 'AJ' . Carbon::parse($item->tglsbm)->format('d') . '00003';
                        $newPJT = new PostingJurnalTransaksi;
                        $norecHead = $newPJT->generateNewId();
                        $newPJT->norec = $norecHead;
                        $newPJT->kdprofile = $kdProfile;
                        $newPJT->noposting = $noPosting;
                        $newPJT->nojurnal = 0;
                        $newPJT->nojurnal_intern = $noJurnalIntern;
                        $newPJT->objectjenisjurnalfk = 1;
                        $newPJT->nobuktitransaksi = $noBuktiTransaksi;
                        $newPJT->tglbuktitransaksi = date('Y-m-t', strtotime($item->tglsbm)); //$item->tglsbm;// $this->getDateTime()->format('Y-m-d H:i:s');
                        $newPJT->kdproduk = null;
                        $newPJT->namaproduktransaksi = 'Adjustment Penerimaan Deposit dari ' . $nocm . ' ' . $nama;
                        $newPJT->deskripsiproduktransaksi = 'penerimaan_deposit';
                        $newPJT->keteranganlainnya = 'Adjustment Penerimaan Deposit Tgl. ' . $item->tgl;

                        //                $newPJT->keteranganlainnya = 'Penerimaan Deposit dari ' . $nocm . ' ' . $nama;
                        $newPJT->statusenabled = 1;
                        $newPJT->norecrelated = $item->norec_smbc;
                        $newPJT->save();

                        $norec_pj = $postingJurnalTransaksi->norec;

                        $debetId = 11153; //Uang Muka Layanan
                        $kreditId = 1778; //Piutang Pasien dalam perawatan

                        //     $debetId = $item->jurnaldid;
                        //     $kreditId = $item->jurnalkid;

                        //debet
                        $postingJurnalTransaksiD = new PostingJurnalTransaksiD;
                        $postingJurnalTransaksiD->norec = $postingJurnalTransaksiD->generateNewId();
                        $postingJurnalTransaksiD->kdprofile = $kdProfile;
                        $postingJurnalTransaksiD->nojurnal = 0;
                        $postingJurnalTransaksiD->noposting = $noPosting;
                        $postingJurnalTransaksiD->objectaccountfk = $debetId;
                        $postingJurnalTransaksiD->hargasatuand = $totalRp;
                        $postingJurnalTransaksiD->hargasatuank = 0;
                        $postingJurnalTransaksiD->statusenabled = 1;
                        $postingJurnalTransaksiD->norecrelated = $norec_pj;
                        $postingJurnalTransaksiD->save();
                        //kredit
                        $postingJurnalTransaksiD = new PostingJurnalTransaksiD;
                        $postingJurnalTransaksiD->norec = $postingJurnalTransaksiD->generateNewId();
                        $postingJurnalTransaksiD->kdprofile = $kdProfile;
                        $postingJurnalTransaksiD->nojurnal = 0;
                        $postingJurnalTransaksiD->noposting = $noPosting;
                        $postingJurnalTransaksiD->objectaccountfk = $kreditId;
                        $postingJurnalTransaksiD->hargasatuand = 0;
                        $postingJurnalTransaksiD->hargasatuank = $totalRp;
                        $postingJurnalTransaksiD->statusenabled = 1;
                        $postingJurnalTransaksiD->norecrelated = $norec_pj;
                        $postingJurnalTransaksiD->save();
                    }
                }
            }
            $aingDiskon = [];
            if (1 == 1) {

                //############################################################################################################################################
                //##################################################### DISKON/Biaya ##############################################################################
                //############################################################################################################################################
                // TODO : Jurnal Diskon/Biaya
                //            $dataCoaDiskon = DB::select(DB::raw("select ru.id as ruid, ru.namaruangan,coa.namaaccount,coa.kdaccount,coa.id as coaid
                //                    from chartofaccount_m as coa
                //                    left JOIN ruangan_m as ru on coa.namaaccount like '%' || ru.namaruangan || ''
                //                    where coa.namaexternal='2018-03-01' and coa.namaaccount like 'Biaya  Subsidi Fasilitas %' and ru.namaruangan <>'-';")
                //            );
                //DELETE JIKA DI TABEL TRANSAKSI SUDAH TIDAK ADA
                //            $delMacan = DB::select(DB::raw("
                //                    delete from postingjurnaltransaksid_t where norecrelated in (select pjt.norec from postingjurnaltransaksi_t as pjt
                //                    INNER JOIN  pelayananpasien_t as pp on pp.norec =pjt.norecrelated and pp.tglpelayanan >'2019-01-01 00:00'
                //                    left JOIN postingjurnal_t as posted on pjt.nojurnal_intern=posted.norecrelated
                //                    where pjt.deskripsiproduktransaksi='diskon' and pp.norec is null and posted.nojurnal_intern is null
                //                    and pjt.tglbuktitransaksi  >'2019-01-01 00:00');")
                //            );
                //            $delMacanHead = DB::select(DB::raw("
                //                    delete from postingjurnaltransaksi_t where norec in (select pjt.norec from postingjurnaltransaksi_t as pjt
                //                    INNER JOIN  pelayananpasien_t as pp on pp.norec =pjt.norecrelated and pp.tglpelayanan >'2019-01-01 00:00'
                //                    left JOIN postingjurnal_t as posted on pjt.nojurnal_intern=posted.norecrelated
                //                    where pjt.deskripsiproduktransaksi='diskon' and pp.norec is null and posted.nojurnal_intern is null
                //                    and pjt.tglbuktitransaksi  >'2019-01-01 00:00');")
                //            );

                $aingDiskon = DB::select(
                    DB::raw("
                        select ps.nocm,ps.namapasien,pd.noregistrasi,case when ((pp.hargadiscount * pp.jumlah) is null) then (0) else (pp.hargadiscount * pp.jumlah) end as total,
                        pp.tglpelayanan,adp.objectruanganfk,pr.namaproduk,pp.produkfk,
                        pp.norec as norec_pp,to_char(pp.tglpelayanan, 'YYYY-MM-DD') as tgl,pjt.norec
                        ,coad.id as jurnaldid,coad.noaccount as jurnaldno,coad.namaaccount as jurnaldnm,
                        coak.id as jurnalkid,coak.noaccount as jurnalkno,coak.namaaccount as jurnalknm
                        from pelayananpasien_t pp
                        inner join antrianpasiendiperiksa_t as adp on  adp.norec=pp.noregistrasifk
                        left join pasiendaftar_t as pd  on pd.norec = adp.noregistrasifk
                        inner join pasien_m as ps on ps.id = pd.nocmfk
                        inner join produk_m as pr on pr.id=pp.produkfk
                        inner join chartofaccountmapjurnal_t as cmap on
                        cmap.objectprodukfk=pp.produkfk
                            INNER JOIN chartofaccount_m as coak on coak.id=cmap.objectcoakreditfk
                            INNER JOIN chartofaccount_m as coad on coad.id=cmap.objectcoadebetfk
                        left JOIN postingjurnaltransaksi_t as pjt on pjt.norecrelated  = pp.norec and pjt.deskripsiproduktransaksi='diskon'
                        where pd.kdprofile = $kdProfile and pp.tglpelayanan BETWEEN :tglAwal and :tglAkhir
                        and cmap.objectjenistrxfk=10
                        and pjt.norec is null
                        and pp.hargadiscount is not null and pp.hargadiscount > 0"),
                    array(
                        'tglAwal' => $request['tglAwal'],
                        'tglAkhir' => $request['tglAkhir'],
                    )
                );
                // return $aingDiskon;
                $coaruangandiskon = [];
                foreach ($aingDiskon as $item) {
                    $nocm = $item->nocm;
                    $nama = $item->namapasien;
                    $noBuktiTransaksi = $item->noregistrasi;
                    $totalRp = $item->total;
                    $noPosting = '-'; //$this->generateCode(new PostingJurnalTransaksi, 'noposting', 14, 'NT-' . $this->getDateTime()->format('ym'));
                    //                $nojurnal = $this->getSequence('postingjurnaltransaksi_t_nojurnal_seq');

                    //                $noJurnalIntern = Carbon::parse($item->tglsbm)->format('ym') . 'PN' . Carbon::parse($item->tglsbm)->format('d') . '00004';
                    //                if ($item->objectdepartemenfk == 16) {
                    //                    $noJurnalIntern = Carbon::parse($item->tglpelayanan)->format('ym') . 'PN' . Carbon::parse($item->tglpelayanan)->format('d') . '00004';
                    //                } else {
                    $noJurnalIntern = Carbon::parse($item->tglpelayanan)->format('ym') . 'PN' . Carbon::parse($item->tglpelayanan)->format('d') . '00010';
                    //                }
                    $cekSudahPosting = PostingJurnal::where('norecrelated', $noJurnalIntern)->get();

                    if ($this->getCountArray($cekSudahPosting) == 0) {

                        $newPJT = new PostingJurnalTransaksi;
                        $norecHead = $newPJT->generateNewId();
                        $newPJT->norec = $norecHead;
                        $newPJT->kdprofile = $kdProfile;
                        $newPJT->noposting = $noPosting;
                        $newPJT->nojurnal = 0;
                        $newPJT->nojurnal_intern = $noJurnalIntern;
                        $newPJT->objectjenisjurnalfk = 1;
                        $newPJT->nobuktitransaksi = $noBuktiTransaksi;
                        $newPJT->tglbuktitransaksi = $item->tglpelayanan; // $this->getDateTime()->format('Y-m-d H:i:s');
                        $newPJT->kdproduk = $item->produkfk;
                        $newPJT->namaproduktransaksi = 'Diskon pelayanan ' . $item->namaproduk . ' ' . $nama . ' / ' . $item->noregistrasi;
                        $newPJT->deskripsiproduktransaksi = 'diskon';

                        $newPJT->keteranganlainnya = 'Diskon pelayanan Tgl. ' . $item->tgl;

                        $newPJT->statusenabled = 1;
                        $newPJT->norecrelated = $item->norec_pp;
                        $newPJT->save();

                        $norec_pj = $newPJT->norec;

                        // $debetId = 11216;//Biaya Jasa Pelayanan - Medis
                        // $kreditId = 1778;//Piutang Pasien dalam perawatan

                        $debetId = $item->jurnaldid;
                        $kreditId = $item->jurnalkid;
                        //debet
                        $postingJurnalTransaksiD = new PostingJurnalTransaksiD;
                        $postingJurnalTransaksiD->norec = $postingJurnalTransaksiD->generateNewId();
                        $postingJurnalTransaksiD->kdprofile = $kdProfile;
                        $postingJurnalTransaksiD->nojurnal = 0;
                        $postingJurnalTransaksiD->noposting = $noPosting;
                        $postingJurnalTransaksiD->objectaccountfk = $debetId;
                        $postingJurnalTransaksiD->hargasatuand = $totalRp;
                        $postingJurnalTransaksiD->hargasatuank = 0;
                        $postingJurnalTransaksiD->statusenabled = 1;
                        $postingJurnalTransaksiD->norecrelated = $norec_pj;
                        $postingJurnalTransaksiD->save();
                        //kredit
                        $postingJurnalTransaksiD = new PostingJurnalTransaksiD;
                        $postingJurnalTransaksiD->norec = $postingJurnalTransaksiD->generateNewId();
                        $postingJurnalTransaksiD->kdprofile = $kdProfile;
                        $postingJurnalTransaksiD->nojurnal = 0;
                        $postingJurnalTransaksiD->noposting = $noPosting;
                        $postingJurnalTransaksiD->objectaccountfk = $kreditId;
                        $postingJurnalTransaksiD->hargasatuand = 0;
                        $postingJurnalTransaksiD->hargasatuank = $totalRp;
                        $postingJurnalTransaksiD->statusenabled = 1;
                        $postingJurnalTransaksiD->norecrelated = $norec_pj;
                        $postingJurnalTransaksiD->save();
                    } else {
                        $noJurnalIntern = Carbon::parse($item->tglpelayanan)->format('ym') . 'AJ' . Carbon::parse($item->tglpelayanan)->format('d') . '00010';
                        $newPJT = new PostingJurnalTransaksi;
                        $norecHead = $newPJT->generateNewId();
                        $newPJT->norec = $norecHead;
                        $newPJT->kdprofile = $kdProfile;
                        $newPJT->noposting = $noPosting;
                        $newPJT->nojurnal = 0;
                        $newPJT->nojurnal_intern = $noJurnalIntern;
                        $newPJT->objectjenisjurnalfk = 1;
                        $newPJT->nobuktitransaksi = $noBuktiTransaksi;
                        $newPJT->tglbuktitransaksi = date('Y-m-t', strtotime($item->tglpelayanan)); //$item->tglpelayanan;
                        $newPJT->kdproduk = null;
                        $newPJT->namaproduktransaksi = 'Adjustment Diskon pelayanan ' . $item->namaproduk . ' ' . $nama . ' / ' . $item->noregistrasi;
                        $newPJT->deskripsiproduktransaksi = 'diskon';
                        $newPJT->keteranganlainnya = 'Adjustment Diskon pelayanan Tgl. ' . $item->tgl;
                        $newPJT->statusenabled = 1;
                        $newPJT->norecrelated = $item->norec_pp;
                        $newPJT->save();

                        $norec_pj = $postingJurnalTransaksi->norec;

                        // $debetId = 11216;//Biaya Jasa Pelayanan - Medis
                        // $kreditId = 1778;//Piutang Pasien dalam perawatan

                        $debetId = $item->jurnaldid;
                        $kreditId = $item->jurnalkid;
                        //debet
                        $postingJurnalTransaksiD = new PostingJurnalTransaksiD;
                        $postingJurnalTransaksiD->norec = $postingJurnalTransaksiD->generateNewId();
                        $postingJurnalTransaksiD->kdprofile = $kdProfile;
                        $postingJurnalTransaksiD->nojurnal = 0;
                        $postingJurnalTransaksiD->noposting = $noPosting;
                        $postingJurnalTransaksiD->objectaccountfk = $debetId;
                        $postingJurnalTransaksiD->hargasatuand = $totalRp;
                        $postingJurnalTransaksiD->hargasatuank = 0;
                        $postingJurnalTransaksiD->statusenabled = 1;
                        $postingJurnalTransaksiD->norecrelated = $norec_pj;
                        $postingJurnalTransaksiD->save();
                        //kredit
                        $postingJurnalTransaksiD = new PostingJurnalTransaksiD;
                        $postingJurnalTransaksiD->norec = $postingJurnalTransaksiD->generateNewId();
                        $postingJurnalTransaksiD->kdprofile = $kdProfile;
                        $postingJurnalTransaksiD->nojurnal = 0;
                        $postingJurnalTransaksiD->noposting = $noPosting;
                        $postingJurnalTransaksiD->objectaccountfk = $kreditId;
                        $postingJurnalTransaksiD->hargasatuand = 0;
                        $postingJurnalTransaksiD->hargasatuank = $totalRp;
                        $postingJurnalTransaksiD->statusenabled = 1;
                        $postingJurnalTransaksiD->norecrelated = $norec_pj;
                        $postingJurnalTransaksiD->save();
                    }
                }
            }
            //  dd($aingDiskon);
            // return $postingJurnalTransaksiD;
            $transStatus = 'true';
        } catch (\Exception $e) {
            $transStatus = 'false';
        }

        $transMessage = "Posting";

        if ($transStatus == 'true') {
            $transMessage = $transMessage . " Jurnal Berhasil!!";
            DB::commit();
            $result = array(
                "status" => 200,
                "result" => array(
                    "count" => count($aingMacan),
                    "countDeposit" => count($aingMaung),
                    "countDiskon" => count($aingDiskon),

                    "as" => '@epic'
                )
            );
        } else {
            $transMessage = $transMessage . "Gagal!!";
            DB::rollBack();
            $result = array(
                "status" => 400,
                "result" => array(
                    "count" => count($aingMacan),

                    "as" => '@epic',
                )
            );
            //"Don't Stop When You're Tired, Stop When You're Done"
        }
        return $this->respond($result['result'], $result['status'], $transMessage);
    }
    public function PostingJurnal_terimabarang(Request $request)
    {
        $kdProfile = (int) $this->kdProfile;
        DB::beginTransaction();
        $isAktif = $this->settingFix('PostingJurnal_terimabarang_isaktif');

        if ($isAktif != '1') {
            return;
        }

        $dataLogin = $request->all();
        //        ini_set('max_execution_time', 1000); //6 minutes
        try {
            // TODO : Jurnal Penerimaa Barang Supplier
            //            $delMacan = DB::select(DB::raw("
            //                delete from postingjurnaltransaksid_t where norecrelated in (select pjt.norec from postingjurnaltransaksi_t as pjt
            //                INNER JOIN strukpelayanan_t as sp on sp.norec=pjt.norecrelated and sp.tglstruk >'2019-01-01 00:00'
            //                left JOIN postingjurnal_t as posted on pjt.nojurnal_intern=posted.norecrelated
            //                where pjt.deskripsiproduktransaksi='penerimaan_barang' and sp.norec is null  and posted.nojurnal_intern is null
            //                and pjt.tglbuktitransaksi  >'2019-01-01 00:00') ")
            //            );
            //            $delMacanHead = DB::select(DB::raw("
            //                    delete from postingjurnaltransaksi_t where norec in (select pjt.norec from postingjurnaltransaksi_t as pjt
            //                INNER JOIN strukpelayanan_t as sp on sp.norec=pjt.norecrelated and sp.tglstruk >'2019-01-01 00:00'
            //                left JOIN postingjurnal_t as posted on pjt.nojurnal_intern=posted.norecrelated
            //                where pjt.deskripsiproduktransaksi='penerimaan_barang'  and sp.norec is null  and posted.nojurnal_intern is null
            //                and pjt.tglbuktitransaksi  >'2019-01-01 00:00')")
            //            );

            // select sp.norec, sp.tglstruk, sp.nostruk,rkn.namarekanan,
            // ru.namaruangan,sp.nofaktur,sp.totalharusdibayar,to_char(sp.tglstruk, 'YYYY-MM-DD') as tgl
            // from strukpelayanan_t as sp
            // INNER JOIN rekanan_m as rkn on rkn.id=sp.objectrekananfk
            // INNER JOIN ruangan_m as ru on ru.id=sp.objectruanganfk
            // left JOIN postingjurnaltransaksi_t as pjt on pjt.norecrelated=sp.norec
            // where sp.kdprofile = $kdProfile and sp.tglstruk between :tglAwal and :tglAkhir and
            // sp.objectkelompoktransaksifk=35 and pjt.norec is null and sp.statusenabled <> 'f';
            $kel = $this->kelompokTransaksi('PENERIMAAN BARANG SUPPLIER'); // $reque
            $aingMacan = DB::select(
                DB::raw("
                        select spd.norec, sp.tglstruk, sp.nostruk,rkn.namarekanan,sp.nokontrak,
                        ru.namaruangan,sp.nofaktur,sp.totalharusdibayar,to_char(sp.tglstruk, 'YYYY-MM-DD') as tgl,
                        pr.namaproduk,spd.qtyproduk*spd.hargasatuan as totalharga,spd.hargappn,
                        spd.hargadiscount*spd.qtyproduk as hargadiscount,
                        CAST(((spd.qtyproduk*spd.hargasatuan)-(((spd.persendiscount*spd.hargasatuan)/100)*spd.qtyproduk))+(spd.persenppn*((spd.qtyproduk*spd.hargasatuan)-(((spd.persendiscount*spd.hargasatuan)/100)*spd.qtyproduk))/100) AS FLOAT) AS total,
                        cmap.objectcoadebetfk,cmap.objectcoakreditfk,cmap.objectjenistrxfk
                        from strukpelayanan_t as sp
                        INNER JOIN strukpelayanandetail_t as spd on spd.nostrukfk=sp.norec
                        left JOIN produk_m as pr on pr.id=spd.objectprodukfk
                        INNER JOIN rekanan_m as rkn on rkn.id=sp.objectrekananfk
                        INNER JOIN ruangan_m as ru on ru.id=sp.objectruanganfk
                        inner join chartofaccountmapjurnal_t as cmap on
                        cmap.objectprodukfk=spd.objectprodukfk
                        left JOIN postingjurnaltransaksi_t as pjt on pjt.norecrelated=spd.norec and pjt.deskripsiproduktransaksi = 'penerimaan_barang'
                        where sp.kdprofile = $kdProfile and sp.tglstruk between :tglAwal and :tglAkhir
                        and  sp.objectkelompoktransaksifk=$kel and pjt.norec is null and sp.statusenabled <> 'f'
                        and cmap.objectjenistrxfk in (4,6)
                    "),
                array(
                    'tglAwal' => $request['tglAwal'],
                    'tglAkhir' => $request['tglAkhir'],
                )
            );
            foreach ($aingMacan as $item) {
                //                $nocm = $item->nocm;
                //                $nama = $item->namapasien;
                // $totalRp = $item->totalharusdibayar;
                $totalRp = 0;
                if ($item->objectjenistrxfk == 4) {
                    $totalRp = $item->totalharga - $item->hargadiscount;

                    $debetId = $item->objectcoadebetfk;  //1853;
                    $kreditId = $item->objectcoakreditfk; //1791;
                } else if ($item->objectjenistrxfk == 6) {
                    //Nilai Diskon Penerimaan
                    $totalRp = $item->hargadiscount;

                    $debetId = $item->objectcoadebetfk;  //1853;
                    $kreditId = $item->objectcoakreditfk; //1791;

                }



                $noBuktiTransaksi = $item->nostruk;
                $noPosting = '-'; //$this->generateCode(new PostingJurnalTransaksi, 'noposting', 14, 'KS-' . $this->getDateTime()->format('ym'));
                //                $norec_smbc = $item->norec;

                //                $nojurnal = $this->getSequence('postingjurnaltransaksi_t_nojurnal_seq');

                $newPJT = new PostingJurnalTransaksi;
                $norecHead = $newPJT->generateNewId();
                $newPJT->norec = $norecHead;

                $noJurnalIntern = Carbon::parse($item->tglstruk)->format('ym') . 'PN' . Carbon::parse($item->tglstruk)->format('d') . '00004';
                $cekSudahPosting = PostingJurnal::where('norecrelated', $noJurnalIntern)->where('kdprofile', $kdProfile)->get();

                if (count($cekSudahPosting) == 0) {
                    if ($totalRp != 0) {
                        $newPJT->kdprofile = $kdProfile;

                        $newPJT->noposting = $noPosting;
                        $newPJT->nojurnal = 0; //$nojurnal;
                        $newPJT->nojurnal_intern = $noJurnalIntern;
                        $newPJT->objectjenisjurnalfk = 1;
                        $newPJT->nobuktitransaksi = $noBuktiTransaksi;
                        $newPJT->tglbuktitransaksi = $item->tglstruk; // $this->getDateTime()->format('Y-m-d H:i:s');
                        $newPJT->kdproduk = null;
                        $newPJT->namaproduktransaksi = 'Penerimaan Barang ' . ' - ' . $item->namaproduk . ' dari ' . $item->namarekanan . '->' . $item->nostruk . '/' . $item->nofaktur;
                        $newPJT->deskripsiproduktransaksi = 'penerimaan_barang';
                        $newPJT->keteranganlainnya = 'Penerimaan Barang ' . $item->tgl;;
                        $newPJT->statusenabled = true;
                        $newPJT->norecrelated = $item->norec;
                        $newPJT->save();

                        //debet
                        $postingJurnalTransaksiD = new PostingJurnalTransaksiD;
                        $postingJurnalTransaksiD->norec = $postingJurnalTransaksiD->generateNewId();
                        $postingJurnalTransaksiD->kdprofile = $kdProfile;
                        $postingJurnalTransaksiD->nojurnal = 0; //$nojurnal;
                        $postingJurnalTransaksiD->noposting = $noPosting;
                        $postingJurnalTransaksiD->objectaccountfk = $debetId;
                        $postingJurnalTransaksiD->hargasatuand = $totalRp;
                        $postingJurnalTransaksiD->hargasatuank = 0;
                        $postingJurnalTransaksiD->statusenabled = true;
                        $postingJurnalTransaksiD->norecrelated = $norecHead;
                        $postingJurnalTransaksiD->save();

                        //kredit
                        $postingJurnalTransaksiD = new PostingJurnalTransaksiD;
                        $postingJurnalTransaksiD->norec = $postingJurnalTransaksiD->generateNewId();
                        $postingJurnalTransaksiD->kdprofile = $kdProfile;
                        $postingJurnalTransaksiD->nojurnal = 0; //$nojurnal;
                        $postingJurnalTransaksiD->noposting = $noPosting;
                        $postingJurnalTransaksiD->objectaccountfk = $kreditId;
                        $postingJurnalTransaksiD->hargasatuand = 0;
                        $postingJurnalTransaksiD->hargasatuank = $totalRp;
                        $postingJurnalTransaksiD->statusenabled = true;
                        $postingJurnalTransaksiD->norecrelated = $norecHead;
                        $postingJurnalTransaksiD->save();
                    }
                }
            }

            $aingMacanppn = [];

            $aingMacanppn = DB::select(
                DB::raw("
                        select spd.norec, sp.tglstruk, sp.nostruk,rkn.namarekanan,sp.nokontrak,
                        ru.namaruangan,sp.nofaktur,sp.totalharusdibayar,to_char(sp.tglstruk, 'YYYY-MM-DD') as tgl,
                        pr.namaproduk,spd.qtyproduk*spd.hargasatuan as totalharga,spd.hargappn,
                        spd.hargadiscount*spd.qtyproduk as hargadiscount,
                        CAST(((spd.qtyproduk*spd.hargasatuan)-(((spd.persendiscount*spd.hargasatuan)/100)*spd.qtyproduk))+(spd.persenppn*((spd.qtyproduk*spd.hargasatuan)-(((spd.persendiscount*spd.hargasatuan)/100)*spd.qtyproduk))/100) AS FLOAT) AS total,
                        cmap.objectcoadebetfk,cmap.objectcoakreditfk,cmap.objectjenistrxfk
                        from strukpelayanan_t as sp
                        INNER JOIN strukpelayanandetail_t as spd on spd.nostrukfk=sp.norec
                        left JOIN produk_m as pr on pr.id=spd.objectprodukfk
                        INNER JOIN rekanan_m as rkn on rkn.id=sp.objectrekananfk
                        INNER JOIN ruangan_m as ru on ru.id=sp.objectruanganfk
                        inner join chartofaccountmapjurnal_t as cmap on
                        cmap.objectprodukfk=spd.objectprodukfk
                        left JOIN postingjurnaltransaksi_t as pjt on pjt.norecrelated=spd.norec and pjt.deskripsiproduktransaksi = 'penerimaan_barang_ppn'
                        where sp.kdprofile = $kdProfile and sp.tglstruk between :tglAwal and :tglAkhir
                        and  sp.objectkelompoktransaksifk=315 and pjt.norec is null and sp.statusenabled <> 'f'
                        and cmap.objectjenistrxfk =7
                    "),
                array(
                    'tglAwal' => $request['tglAwal'],
                    'tglAkhir' => $request['tglAkhir'],
                )
            );
            // return $aingMacan;
            foreach ($aingMacanppn as $item) {
                //                $nocm = $item->nocm;
                //                $nama = $item->namapasien;
                // $totalRp = $item->totalharusdibayar;
                $totalRp = 0;

                //Nilai PPN Penerimaan Barang

                $totalRp = $item->hargappn;

                $debetId = $item->objectcoadebetfk;  //1853;
                $kreditId = $item->objectcoakreditfk; //1791;




                $noBuktiTransaksi = $item->nostruk;
                $noPosting = '-'; //$this->generateCode(new PostingJurnalTransaksi, 'noposting', 14, 'KS-' . $this->getDateTime()->format('ym'));
                //                $norec_smbc = $item->norec;

                //                $nojurnal = $this->getSequence('postingjurnaltransaksi_t_nojurnal_seq');

                $newPJT = new PostingJurnalTransaksi;
                $norecHead = $newPJT->generateNewId();
                $newPJT->norec = $norecHead;

                $noJurnalIntern = Carbon::parse($item->tglstruk)->format('ym') . 'PN' . Carbon::parse($item->tglstruk)->format('d') . '00007';
                $cekSudahPosting = PostingJurnal::where('norecrelated', $noJurnalIntern)->where('kdprofile', $kdProfile)->get();

                if (count($cekSudahPosting) == 0) {
                    if ($totalRp != 0) {
                        $newPJT->kdprofile = $kdProfile;

                        $newPJT->noposting = $noPosting;
                        $newPJT->nojurnal = 0; //$nojurnal;
                        $newPJT->nojurnal_intern = $noJurnalIntern;
                        $newPJT->objectjenisjurnalfk = 1;
                        $newPJT->nobuktitransaksi = $noBuktiTransaksi;
                        $newPJT->tglbuktitransaksi = $item->tglstruk; // $this->getDateTime()->format('Y-m-d H:i:s');
                        $newPJT->kdproduk = null;
                        $newPJT->namaproduktransaksi = 'PPN Penerimaan Barang ' . ' - ' . $item->namaproduk . ' dari ' . $item->namarekanan . '->' . $item->nostruk . '/' . $item->nofaktur;
                        $newPJT->deskripsiproduktransaksi = 'penerimaan_barang_ppn';
                        $newPJT->keteranganlainnya = 'Penerimaan Barang PPN ' . $item->tgl;;
                        $newPJT->statusenabled = true;
                        $newPJT->norecrelated = $item->norec;
                        $newPJT->save();

                        //debet
                        $postingJurnalTransaksiD = new PostingJurnalTransaksiD;
                        $postingJurnalTransaksiD->norec = $postingJurnalTransaksiD->generateNewId();
                        $postingJurnalTransaksiD->kdprofile = $kdProfile;
                        $postingJurnalTransaksiD->nojurnal = 0; //$nojurnal;
                        $postingJurnalTransaksiD->noposting = $noPosting;
                        $postingJurnalTransaksiD->objectaccountfk = $debetId;
                        $postingJurnalTransaksiD->hargasatuand = $totalRp;
                        $postingJurnalTransaksiD->hargasatuank = 0;
                        $postingJurnalTransaksiD->statusenabled = true;
                        $postingJurnalTransaksiD->norecrelated = $norecHead;
                        $postingJurnalTransaksiD->save();

                        //kredit
                        $postingJurnalTransaksiD = new PostingJurnalTransaksiD;
                        $postingJurnalTransaksiD->norec = $postingJurnalTransaksiD->generateNewId();
                        $postingJurnalTransaksiD->kdprofile = $kdProfile;
                        $postingJurnalTransaksiD->nojurnal = 0; //$nojurnal;
                        $postingJurnalTransaksiD->noposting = $noPosting;
                        $postingJurnalTransaksiD->objectaccountfk = $kreditId;
                        $postingJurnalTransaksiD->hargasatuand = 0;
                        $postingJurnalTransaksiD->hargasatuank = $totalRp;
                        $postingJurnalTransaksiD->statusenabled = true;
                        $postingJurnalTransaksiD->norecrelated = $norecHead;
                        $postingJurnalTransaksiD->save();
                    }
                }
            }

            $transStatus = 'true';
        } catch (\Exception $e) {
            $transStatus = 'false';
        }

        $transMessage = "Posting";

        if ($transStatus == 'true') {
            $transMessage = $transMessage . "";
            DB::commit();
            $result = array(
                "status" => 200,
                "result" => array(
                    //                "message" => $transMessage,
                    //                "data" => $aingMacan,
                    "count" => count($aingMacan),
                    "countppn" => count($aingMacan),
                    "as" => '@epic',
                )
            );
        } else {
            $transMessage = $transMessage . " Gagal!!";
            DB::rollBack();
            $result = array(
                "status" => 400,
                "result" => array(
                    //                "message"  => $transMessage,
                    // "data" => $aingMacan,
                    "as" => '@epic',
                )
            );
        }
        return $this->respond($result['result'], $result['status'], $transMessage);
    }
    public function PostingJurnal_bebanpelayananpasien(Request $request)
    {
        $kdProfile = (int) $this->kdProfile;

        $dataLogin = $request->all();


        DB::beginTransaction();
        try {

            $aingMacan = [];
            $aingMacanppn = [];
            $persenhitungppn = $this->settingFix('hitungPPN');
            $persenppn = $this->settingFix('persenPPN');

            $isAktif = $this->settingFix('PostingJurnal_bebanPelayanan_isaktif');
            $cekDataPelayanan = [];
            if (!empty($isAktif) && $isAktif == 'false') {
                return null;
            }
            // ini_set('max_execution_time', 1000); //6 minutes

            // TODO : Jurnal Pelayanan



            $aingMacan = DB::select(
                DB::raw("
                    select * from (
                    select
                    pp.tglpelayanan,pr.namaproduk,pp.hargajual,pp.jumlah,--pp.harganetto,
                    (case when ho.hargajual is null then pp.harganetto else ho.hargajual end) as harganetto,
                    pd.objectkelompokpasienlastfk,pp.produkfk as objectprodukfk,
                    case when pp.aturanpakai is null then 'XObat' when pp.aturanpakai ='-' then 'XObat' else 'Obat' end as Obat,pp.produkfk,pr.objectdetailjenisprodukfk,pr.objectkelompokprodukbpjsfk,
                    (((ho.hargajual/$persenhitungppn)*100)*pp.jumlah) as harga,
                    pr.id as prid,
                    to_char(pp.tglpelayanan, 'YYYY-MM-DD') as tgl,
                    pp.norec as norec_pp
                    ,case when sr.norec is null then ru.objectdepartemenfk else ru2.objectdepartemenfk end as objectdepartemenfk,apd.objectruanganfk,pr.objectdetailjenisprodukfk,
                    cmap.objectcoadebetfk,cmap.objectcoakreditfk,pd.noregistrasi,ps.namapasien,ru3.jenis
                    from pelayananpasien_t as pp
                    inner join antrianpasiendiperiksa_t as apd on apd.norec=pp.noregistrasifk
                    inner join pasiendaftar_t as pd on pd.norec=apd.noregistrasifk
                    inner join pasien_m as ps on ps.id = pd.nocmfk
                    inner join ruangan_m as ru3 on ru3.id=pd.objectruanganlastfk
                    inner join ruangan_m as ru on ru.id=apd.objectruanganfk
                    inner join produk_m as pr on pr.id=pp.produkfk
                    INNER JOIN hargaobat_t as ho on ho.produkfk=pp.produkfk
                    left JOIN strukresep_t sr on sr.norec=pp.strukresepfk
                    left JOIN ruangan_m ru2 on ru2.id=sr.ruanganfk
                    inner join chartofaccountmapjurnal_t as cmap on
                        cmap.objectprodukfk=pp.produkfk
                    left JOIN postingjurnaltransaksi_t as pjt on pjt.norecrelated=pp.norec and pjt.deskripsiproduktransaksi = 'bebanpelayananpasien_t'
                    where pp.kdprofile in ($kdProfile)
                    and pp.tglpelayanan between :tglAwal and :tglAkhir
                    --and pd.noregistrasi = :noregistrasi
                    and pjt.norec is  null
                    and pp.strukresepfk is null
                    and pp.produkfk not in (402611)
                    and cmap.objectjenistrxfk=5
                    union all
                    ---//Pelayanan Obat
                    select
                    pp.tglpelayanan,pr.namaproduk,pp.hargajual,pp.jumlah,pp.harganetto,
                    pd.objectkelompokpasienlastfk,pp.produkfk as objectprodukfk,
                    case when pp.aturanpakai is null then 'XObat' when pp.aturanpakai ='-' then 'XObat' else 'Obat' end as Obat,pp.produkfk,pr.objectdetailjenisprodukfk,pr.objectkelompokprodukbpjsfk,
                    (((ho.hargajual/$persenhitungppn)*100)*pp.jumlah) as harga,
                    pr.id as prid,
                    to_char(pp.tglpelayanan, 'YYYY-MM-DD') as tgl,
                    pp.norec as norec_pp
                    ,case when sr.norec is null then ru.objectdepartemenfk else ru2.objectdepartemenfk end as objectdepartemenfk,apd.objectruanganfk,pr.objectdetailjenisprodukfk,
                    cmap.objectcoadebetfk,cmap.objectcoakreditfk,
                    pd.noregistrasi,ps.namapasien,ru3.jenis
                    from strukresep_t sr
                    inner join pelayananpasien_t as pp on pp.strukresepfk=sr.norec
                    inner join antrianpasiendiperiksa_t as apd ON apd.norec = sr.pasienfk
                    inner join pasiendaftar_t as pd on pd.norec=apd.noregistrasifk
                    inner join pasien_m as ps on ps.id = pd.nocmfk
                    inner join ruangan_m as ru3 on ru3.id=pd.objectruanganlastfk
                    inner join ruangan_m as ru on ru.id=apd.objectruanganfk
                    inner join produk_m as pr on pr.id=pp.produkfk
                    INNER JOIN hargaobat_t as ho on ho.produkfk=pp.produkfk
                    left JOIN ruangan_m ru2 on ru2.id=sr.ruanganfk
                    inner join chartofaccountmapjurnal_t as cmap on
                        cmap.objectprodukfk=pp.produkfk
                    left JOIN postingjurnaltransaksi_t as pjt on pjt.norecrelated=pp.norec and pjt.deskripsiproduktransaksi = 'bebanpelayananpasien_t'
                    where pp.kdprofile in ($kdProfile)
                    and pp.tglpelayanan between :tglAwal and :tglAkhir
                    --and pd.noregistrasi = :noregistrasi
                    AND pd.statusenabled = true
                    and pjt.norec is  null
                    and pp.strukresepfk is not null
                    and pp.produkfk not in (402611)
                    and cmap.objectjenistrxfk=5
                    union all
                    ---//Pelayanan Obat kronis
                    select
                    pps.tglpelayanan,pr.namaproduk,pps.hargajual,pp.jumlah,pps.harganetto,
                    pd.objectkelompokpasienlastfk,pp.produkfk as objectprodukfk,
                    case when pps.aturanpakai is null then 'XObat' when pps.aturanpakai ='-' then 'XObat' else 'Obat' end as Obat,
                    pp.produkfk,pr.objectdetailjenisprodukfk,pr.objectkelompokprodukbpjsfk,
                    (((ho.hargajual/$persenhitungppn)*100)*pp.jumlah) as harga,
                    pr.id as prid,
                    to_char(pps.tglpelayanan, 'YYYY-MM-DD') as tgl,
                    pp.norec as norec_pp
                    ,case when sr.norec is null then ru.objectdepartemenfk else ru2.objectdepartemenfk end as objectdepartemenfk,apd.objectruanganfk,pr.objectdetailjenisprodukfk,
                    cmap.objectcoadebetfk,cmap.objectcoakreditfk,
                    pd.noregistrasi,ps.namapasien,ru3.jenis
                    from strukresep_t sr
                    INNER JOIN pelayananpasienobatkronis_t AS pp ON pp.strukresepfk = sr.norec
                    inner join pelayananpasien_t as pps on pps.strukresepfk = sr.norec AND pps.produkfk = pp.produkfk
                    inner join antrianpasiendiperiksa_t as apd on apd.norec = sr.pasienfk
                    inner join pasiendaftar_t as pd on pd.norec=apd.noregistrasifk
                    inner join pasien_m as ps on ps.id = pd.nocmfk
                    inner join ruangan_m as ru3 on ru3.id=pd.objectruanganlastfk
                    inner join ruangan_m as ru on ru.id=apd.objectruanganfk
                    inner join produk_m as pr on pr.id=pp.produkfk
                    INNER JOIN hargaobat_t as ho on ho.produkfk=pp.produkfk
                    left JOIN ruangan_m ru2 on ru2.id=sr.ruanganfk
                    inner join chartofaccountmapjurnal_t as cmap on
                        cmap.objectprodukfk=pp.produkfk
                    left JOIN postingjurnaltransaksi_t as pjt on pjt.norecrelated=pp.norec and pjt.deskripsiproduktransaksi = 'bebanpelayananpasien_t'
                    where pp.kdprofile in ($kdProfile)
                    and pps.tglpelayanan between :tglAwal and :tglAkhir
                    --and pd.noregistrasi = :noregistrasi
                    AND pd.statusenabled = true
                    and pjt.norec is  null
                    and pps.strukresepfk is not null
                    and pp.produkfk not in (402611)
                    and cmap.objectjenistrxfk=5
                ) as x where x.harga>0  order by x.norec_pp  limit 1000
                "),
                array(
                    'tglAwal' => $request['tglAwal'],
                    'tglAkhir' => $request['tglAkhir']
                    // 'noregistrasi' => $request['noregistrasi']
                )
            );
            // return $aingMacan;

            foreach ($aingMacan as $item) {
                $coaAdministrasi = 'a';
                $coaKonsultasi = 'b';
                $coaVisite = 'c';
                $coaAkomodasi = 'd';
                $coaTindakan = 'e';
                $coaAlatCanggih = 'f';



                // $totalNettoRp = (($item->harganetto/$persenhitungppn) * 100) * ($item->jumlah); //Harga netto sebelum PPN
                $totalRp = $item->harga; //($item->hargajual) * ($item->jumlah);
                // $totalRp=$totalNettoRp;

                // $debetId = $map->jurnaldid;
                // $kreditId = $map->jurnalkid;
                $debetId = $item->objectcoadebetfk;
                $kreditId = $item->objectcoakreditfk;

                $noBuktiTransaksi = $item->prid;

                $noJurnalIntern = '';
                $noPosting = '-';
                $noJurnalIntern = Carbon::parse($item->tglpelayanan)->format('ym') . 'PN' . Carbon::parse($item->tglpelayanan)->format('d') . '00005';
                $cekSudahPosting = PostingJurnal::where('norecrelated', $noJurnalIntern)->where('kdprofile', $kdProfile)->get();

                if ($this->getCountArray($cekSudahPosting) == 0) {
                    $postingJurnalTransaksi = new PostingJurnalTransaksi;
                    $norecHead = $postingJurnalTransaksi->generateNewId();
                    $postingJurnalTransaksi->norec = $norecHead;
                    $postingJurnalTransaksi->kdprofile = $kdProfile;
                    $postingJurnalTransaksi->noposting = $noPosting;
                    $postingJurnalTransaksi->nojurnal = 0;
                    $postingJurnalTransaksi->nojurnal_intern = $noJurnalIntern;
                    $postingJurnalTransaksi->objectjenisjurnalfk = 1;
                    $postingJurnalTransaksi->nobuktitransaksi = $noBuktiTransaksi;
                    $postingJurnalTransaksi->tglbuktitransaksi = $item->tglpelayanan;
                    $postingJurnalTransaksi->kdproduk = $item->prid;
                    $postingJurnalTransaksi->namaproduktransaksi = 'Persediaan ' . $item->namaproduk . ' a.n ' . $item->namapasien . ' (' . $item->noregistrasi . ')'; //. ' ' . $item->obat;
                    $postingJurnalTransaksi->deskripsiproduktransaksi = 'bebanpelayananpasien_t';
                    $postingJurnalTransaksi->keteranganlainnya = 'bebanpelayananpasien tgl. ' . $item->tgl;
                    $postingJurnalTransaksi->statusenabled = 1;
                    $postingJurnalTransaksi->norecrelated = $item->norec_pp;
                    $postingJurnalTransaksi->jenis = $item->jenis;
                    $postingJurnalTransaksi->save();

                    $norec_pj = $postingJurnalTransaksi->norec;

                    //debet
                    $postingJurnalTransaksiD = new PostingJurnalTransaksiD;
                    $postingJurnalTransaksiD->norec = $postingJurnalTransaksiD->generateNewId();
                    $postingJurnalTransaksiD->kdprofile = $kdProfile;
                    $postingJurnalTransaksiD->nojurnal = 0;
                    $postingJurnalTransaksiD->noposting = $noPosting;
                    $postingJurnalTransaksiD->objectaccountfk = $debetId;
                    $postingJurnalTransaksiD->hargasatuand = $totalRp;
                    $postingJurnalTransaksiD->hargasatuank = 0;
                    $postingJurnalTransaksiD->statusenabled = 1;
                    $postingJurnalTransaksiD->norecrelated = $norec_pj;
                    $postingJurnalTransaksiD->save();

                    //kredit
                    $postingJurnalTransaksiD = new PostingJurnalTransaksiD;
                    $postingJurnalTransaksiD->norec = $postingJurnalTransaksiD->generateNewId();
                    $postingJurnalTransaksiD->kdprofile = $kdProfile;
                    $postingJurnalTransaksiD->nojurnal = 0;
                    $postingJurnalTransaksiD->noposting = $noPosting;
                    $postingJurnalTransaksiD->objectaccountfk = $kreditId;
                    $postingJurnalTransaksiD->hargasatuand = 0;
                    $postingJurnalTransaksiD->hargasatuank = $totalRp;
                    $postingJurnalTransaksiD->statusenabled = 1;
                    $postingJurnalTransaksiD->norecrelated = $norec_pj;
                    $postingJurnalTransaksiD->save();
                } else {
                    $noJurnalIntern = Carbon::parse($item->tglpelayanan)->format('ym') . 'AJ' . Carbon::parse($item->tglpelayanan)->format('d') . '00005';
                    $postingJurnalTransaksi = new PostingJurnalTransaksi;
                    $norecHead = $postingJurnalTransaksi->generateNewId();
                    $postingJurnalTransaksi->norec = $norecHead;
                    $postingJurnalTransaksi->kdprofile = $kdProfile;
                    $postingJurnalTransaksi->noposting = $noPosting;
                    $postingJurnalTransaksi->nojurnal = 0;
                    $postingJurnalTransaksi->nojurnal_intern = $noJurnalIntern;
                    $postingJurnalTransaksi->objectjenisjurnalfk = 1;
                    $postingJurnalTransaksi->nobuktitransaksi = $noBuktiTransaksi;
                    $postingJurnalTransaksi->tglbuktitransaksi = date('Y-m-t', strtotime($item->tglpelayanan)); //$item->tglpelayanan;
                    $postingJurnalTransaksi->kdproduk = $item->prid;
                    $postingJurnalTransaksi->namaproduktransaksi = 'Persediaan ' . $item->namaproduk . ' a.n ' . $item->namapasien . ' ( ' . $item->noregistrasi . ' )';
                    $postingJurnalTransaksi->deskripsiproduktransaksi = 'bebanpelayananpasien_t';
                    $postingJurnalTransaksi->keteranganlainnya = 'Adjustment bebanpelayananpasien tgl. ' . $item->tgl;
                    $postingJurnalTransaksi->statusenabled = true;
                    $postingJurnalTransaksi->norecrelated = $item->norec_pp;
                    $postingJurnalTransaksi->jenis = $item->jenis;
                    $postingJurnalTransaksi->save();


                    $norec_pj = $postingJurnalTransaksi->norec;

                    //debet
                    $postingJurnalTransaksiD = new PostingJurnalTransaksiD;
                    $postingJurnalTransaksiD->norec = $postingJurnalTransaksiD->generateNewId();
                    $postingJurnalTransaksiD->kdprofile = $kdProfile;
                    $postingJurnalTransaksiD->nojurnal = 0;
                    $postingJurnalTransaksiD->noposting = $noPosting;
                    $postingJurnalTransaksiD->objectaccountfk = $debetId;
                    $postingJurnalTransaksiD->hargasatuand = $totalRp;
                    $postingJurnalTransaksiD->hargasatuank = 0;
                    $postingJurnalTransaksiD->statusenabled = 1;
                    $postingJurnalTransaksiD->norecrelated = $norec_pj;
                    $postingJurnalTransaksiD->save();

                    //kredit
                    $postingJurnalTransaksiD = new PostingJurnalTransaksiD;
                    $postingJurnalTransaksiD->norec = $postingJurnalTransaksiD->generateNewId();
                    $postingJurnalTransaksiD->kdprofile = $kdProfile;
                    $postingJurnalTransaksiD->nojurnal = 0;
                    $postingJurnalTransaksiD->noposting = $noPosting;
                    $postingJurnalTransaksiD->objectaccountfk = $kreditId;
                    $postingJurnalTransaksiD->hargasatuand = 0;
                    $postingJurnalTransaksiD->hargasatuank = $totalRp;
                    $postingJurnalTransaksiD->statusenabled = 1;
                    $postingJurnalTransaksiD->norecrelated = $norec_pj;
                    $postingJurnalTransaksiD->save();
                }
                // dd($postingJurnalTransaksi);
            }

            $isAktif = $this->settingFix('PostingJurnal_ppnPelayanan_isaktif');
            if (!empty($isAktif) && $isAktif == 'false') {
                return null;
            }
            // ini_set('max_execution_time', 1000); //6 minutes

            // TODO : Jurnal Pelayanan

            $cekDataPelayanan = [];

            $aingMacanppn = DB::select(
                DB::raw("
                    select * from (
                    select
                    pp.tglpelayanan,pr.namaproduk,pp.hargajual,pp.jumlah,pp.harganetto,
                    pd.objectkelompokpasienlastfk,pp.produkfk as objectprodukfk,
                    case when pp.aturanpakai is null then 'XObat' when pp.aturanpakai ='-' then 'XObat' else 'Obat' end as Obat,pp.produkfk,pr.objectdetailjenisprodukfk,pr.objectkelompokprodukbpjsfk,
                    ((case when ho.hargahpp is null then 0 else ho.hargahpp end)*pp.jumlah) as harga,
                    pr.id as prid,
                    to_char(pp.tglpelayanan, 'YYYY-MM-DD') as tgl,
                    pp.norec as norec_pp
                    ,case when sr.norec is null then ru.objectdepartemenfk else ru2.objectdepartemenfk end as objectdepartemenfk,apd.objectruanganfk,pr.objectdetailjenisprodukfk,
                    cmap.objectcoadebetfk,cmap.objectcoakreditfk,pd.noregistrasi,ps.namapasien,ru3.jenis
                    from pelayananpasien_t as pp
                    inner join antrianpasiendiperiksa_t as apd on apd.norec=pp.noregistrasifk
                    inner join pasiendaftar_t as pd on pd.norec=apd.noregistrasifk
                    inner join pasien_m as ps on ps.id = pd.nocmfk
                    inner join ruangan_m as ru3 on ru3.id=pd.objectruanganlastfk
                    inner join ruangan_m as ru on ru.id=apd.objectruanganfk
                    inner join produk_m as pr on pr.id=pp.produkfk
                    INNER JOIN hargaobat_t as ho on ho.produkfk=pp.produkfk
                    left JOIN strukresep_t sr on sr.norec=pp.strukresepfk
                    left JOIN ruangan_m ru2 on ru2.id=sr.ruanganfk
                    inner join chartofaccountmapjurnal_t as cmap on
                        cmap.objectprodukfk=pp.produkfk
                    left JOIN postingjurnaltransaksi_t as pjt on pjt.norecrelated=pp.norec and pjt.deskripsiproduktransaksi = 'ppnpelayananpasien_t'
                    where pp.kdprofile in ($kdProfile)
                    and pp.tglpelayanan between :tglAwal and :tglAkhir
                    --and pd.noregistrasi = :noregistrasi
                    and pjt.norec is  null
                    and pp.strukresepfk is null
                    and pp.produkfk not in (402611)
                    and cmap.objectjenistrxfk=9
                    and ru2.jenis='RJ'
                    union all
                    ---//Pelayanan Obat
                    select
                    pp.tglpelayanan,pr.namaproduk,pp.hargajual,pp.jumlah,pp.harganetto,
                    pd.objectkelompokpasienlastfk,pp.produkfk as objectprodukfk,
                    case when pp.aturanpakai is null then 'XObat' when pp.aturanpakai ='-' then 'XObat' else 'Obat' end as Obat,pp.produkfk,pr.objectdetailjenisprodukfk,pr.objectkelompokprodukbpjsfk,
                    ((case when ho.hargahpp is null then 0 else ho.hargahpp end)*pp.jumlah) as harga,
                    pr.id as prid,
                    to_char(pp.tglpelayanan, 'YYYY-MM-DD') as tgl,
                    pp.norec as norec_pp
                    ,case when sr.norec is null then ru.objectdepartemenfk else ru2.objectdepartemenfk end as objectdepartemenfk,apd.objectruanganfk,pr.objectdetailjenisprodukfk,
                    cmap.objectcoadebetfk,cmap.objectcoakreditfk,
                    pd.noregistrasi,ps.namapasien,ru3.jenis
                    from strukresep_t sr
                    inner join pelayananpasien_t as pp on pp.strukresepfk=sr.norec
                    inner join antrianpasiendiperiksa_t as apd ON apd.norec = sr.pasienfk
                    inner join pasiendaftar_t as pd on pd.norec=apd.noregistrasifk
                    inner join pasien_m as ps on ps.id = pd.nocmfk
                    inner join ruangan_m as ru3 on ru3.id=pd.objectruanganlastfk
                    inner join ruangan_m as ru on ru.id=apd.objectruanganfk
                    inner join produk_m as pr on pr.id=pp.produkfk
                    INNER JOIN hargaobat_t as ho on ho.produkfk=pp.produkfk
                    left JOIN ruangan_m ru2 on ru2.id=sr.ruanganfk
                    inner join chartofaccountmapjurnal_t as cmap on
                        cmap.objectprodukfk=pp.produkfk
                    left JOIN postingjurnaltransaksi_t as pjt on pjt.norecrelated=pp.norec and pjt.deskripsiproduktransaksi = 'ppnpelayananpasien_t'
                    where pp.kdprofile in ($kdProfile)
                    and pp.tglpelayanan between :tglAwal and :tglAkhir
                    --and pd.noregistrasi = :noregistrasi
                    AND pd.statusenabled = true
                    and pjt.norec is  null
                    and pp.strukresepfk is not null
                    and pp.produkfk not in (402611)
                    and cmap.objectjenistrxfk=9
                    and ru2.jenis='RJ'
                    union all
                    ---//Pelayanan Obat kronis
                    select
                    pps.tglpelayanan,pr.namaproduk,pps.hargajual,pp.jumlah,pps.harganetto,
                    pd.objectkelompokpasienlastfk,pp.produkfk as objectprodukfk,
                    case when pps.aturanpakai is null then 'XObat' when pps.aturanpakai ='-' then 'XObat' else 'Obat' end as Obat,
                    pp.produkfk,pr.objectdetailjenisprodukfk,pr.objectkelompokprodukbpjsfk,
                    ((case when ho.hargahpp is null then 0 else ho.hargahpp end)*pp.jumlah) as harga,
                    pr.id as prid,
                    to_char(pps.tglpelayanan, 'YYYY-MM-DD') as tgl,
                    pp.norec as norec_pp
                    ,case when sr.norec is null then ru.objectdepartemenfk else ru2.objectdepartemenfk end as objectdepartemenfk,apd.objectruanganfk,pr.objectdetailjenisprodukfk,
                    cmap.objectcoadebetfk,cmap.objectcoakreditfk,
                    pd.noregistrasi,ps.namapasien,ru3.jenis
                    from strukresep_t sr
                    INNER JOIN pelayananpasienobatkronis_t AS pp ON pp.strukresepfk = sr.norec
                    inner join pelayananpasien_t as pps on pps.strukresepfk = sr.norec AND pps.produkfk = pp.produkfk
                    inner join antrianpasiendiperiksa_t as apd on apd.norec = sr.pasienfk
                    inner join pasiendaftar_t as pd on pd.norec=apd.noregistrasifk
                    inner join pasien_m as ps on ps.id = pd.nocmfk
                    inner join ruangan_m as ru3 on ru3.id=pd.objectruanganlastfk
                    inner join ruangan_m as ru on ru.id=apd.objectruanganfk
                    inner join produk_m as pr on pr.id=pp.produkfk
                    INNER JOIN hargaobat_t as ho on ho.produkfk=pp.produkfk
                    left JOIN ruangan_m ru2 on ru2.id=sr.ruanganfk
                    inner join chartofaccountmapjurnal_t as cmap on
                        cmap.objectprodukfk=pp.produkfk
                    left JOIN postingjurnaltransaksi_t as pjt on pjt.norecrelated=pp.norec and pjt.deskripsiproduktransaksi = 'ppnpelayananpasien_t'
                    where pp.kdprofile in ($kdProfile)
                    and pps.tglpelayanan between :tglAwal and :tglAkhir
                    --and pd.noregistrasi = :noregistrasi
                    AND pd.statusenabled = true
                    and pjt.norec is  null
                    and pps.strukresepfk is not null
                    and pp.produkfk not in (402611)
                    and cmap.objectjenistrxfk=9
                    and ru2.jenis='RJ'
                ) as x  order by x.norec_pp  limit 1000
                "),
                array(
                    'tglAwal' => $request['tglAwal'],
                    'tglAkhir' => $request['tglAkhir']
                    // 'noregistrasi' => $request['noregistrasi']
                )
            );
            // return $aingMacan;

            foreach ($aingMacanppn as $item) {
                $coaAdministrasi = 'a';
                $coaKonsultasi = 'b';
                $coaVisite = 'c';
                $coaAkomodasi = 'd';
                $coaTindakan = 'e';
                $coaAlatCanggih = 'f';



                $totalNettoRp = (($item->hargajual / $persenhitungppn) * 100); //Harga netto sebelum PPN
                // $totalRp = $item->harga;//($item->hargajual) * ($item->jumlah);
                $totalppn = ($totalNettoRp * $persenppn) / 100;
                $totalRp = $totalppn * ($item->jumlah);

                // $debetId = $map->jurnaldid;
                // $kreditId = $map->jurnalkid;
                $debetId = $item->objectcoadebetfk;
                $kreditId = $item->objectcoakreditfk;

                $noBuktiTransaksi = $item->prid;

                $noJurnalIntern = '';
                $noPosting = '-';
                $noJurnalIntern = Carbon::parse($item->tglpelayanan)->format('ym') . 'PN' . Carbon::parse($item->tglpelayanan)->format('d') . '00009';
                $cekSudahPosting = PostingJurnal::where('norecrelated', $noJurnalIntern)->where('kdprofile', $kdProfile)->get();

                if ($this->getCountArray($cekSudahPosting) == 0) {
                    $postingJurnalTransaksi = new PostingJurnalTransaksi;
                    $norecHead = $postingJurnalTransaksi->generateNewId();
                    $postingJurnalTransaksi->norec = $norecHead;
                    $postingJurnalTransaksi->kdprofile = $kdProfile;
                    $postingJurnalTransaksi->noposting = $noPosting;
                    $postingJurnalTransaksi->nojurnal = 0;
                    $postingJurnalTransaksi->nojurnal_intern = $noJurnalIntern;
                    $postingJurnalTransaksi->objectjenisjurnalfk = 1;
                    $postingJurnalTransaksi->nobuktitransaksi = $noBuktiTransaksi;
                    $postingJurnalTransaksi->tglbuktitransaksi = $item->tglpelayanan;
                    $postingJurnalTransaksi->kdproduk = $item->prid;
                    $postingJurnalTransaksi->namaproduktransaksi = 'PPN Jual Persediaan ' . $item->namaproduk . ' a.n ' . $item->namapasien . ' (' . $item->noregistrasi . ')'; //. ' ' . $item->obat;
                    $postingJurnalTransaksi->deskripsiproduktransaksi = 'ppnpelayananpasien_t';
                    $postingJurnalTransaksi->keteranganlainnya = 'ppnpelayananpasien tgl. ' . $item->tgl;
                    $postingJurnalTransaksi->statusenabled = 1;
                    $postingJurnalTransaksi->norecrelated = $item->norec_pp;
                    $postingJurnalTransaksi->jenis = $item->jenis;
                    $postingJurnalTransaksi->save();

                    $norec_pj = $postingJurnalTransaksi->norec;

                    //debet
                    $postingJurnalTransaksiD = new PostingJurnalTransaksiD;
                    $postingJurnalTransaksiD->norec = $postingJurnalTransaksiD->generateNewId();
                    $postingJurnalTransaksiD->kdprofile = $kdProfile;
                    $postingJurnalTransaksiD->nojurnal = 0;
                    $postingJurnalTransaksiD->noposting = $noPosting;
                    $postingJurnalTransaksiD->objectaccountfk = $debetId;
                    $postingJurnalTransaksiD->hargasatuand = $totalRp;
                    $postingJurnalTransaksiD->hargasatuank = 0;
                    $postingJurnalTransaksiD->statusenabled = 1;
                    $postingJurnalTransaksiD->norecrelated = $norec_pj;
                    $postingJurnalTransaksiD->save();

                    //kredit
                    $postingJurnalTransaksiD = new PostingJurnalTransaksiD;
                    $postingJurnalTransaksiD->norec = $postingJurnalTransaksiD->generateNewId();
                    $postingJurnalTransaksiD->kdprofile = $kdProfile;
                    $postingJurnalTransaksiD->nojurnal = 0;
                    $postingJurnalTransaksiD->noposting = $noPosting;
                    $postingJurnalTransaksiD->objectaccountfk = $kreditId;
                    $postingJurnalTransaksiD->hargasatuand = 0;
                    $postingJurnalTransaksiD->hargasatuank = $totalRp;
                    $postingJurnalTransaksiD->statusenabled = 1;
                    $postingJurnalTransaksiD->norecrelated = $norec_pj;
                    $postingJurnalTransaksiD->save();
                } else {
                    $noJurnalIntern = Carbon::parse($item->tglpelayanan)->format('ym') . 'AJ' . Carbon::parse($item->tglpelayanan)->format('d') . '00009';
                    $postingJurnalTransaksi = new PostingJurnalTransaksi;
                    $norecHead = $postingJurnalTransaksi->generateNewId();
                    $postingJurnalTransaksi->norec = $norecHead;
                    $postingJurnalTransaksi->kdprofile = $kdProfile;
                    $postingJurnalTransaksi->noposting = $noPosting;
                    $postingJurnalTransaksi->nojurnal = 0;
                    $postingJurnalTransaksi->nojurnal_intern = $noJurnalIntern;
                    $postingJurnalTransaksi->objectjenisjurnalfk = 1;
                    $postingJurnalTransaksi->nobuktitransaksi = $noBuktiTransaksi;
                    $postingJurnalTransaksi->tglbuktitransaksi = date('Y-m-t', strtotime($item->tglpelayanan)); //$item->tglpelayanan;
                    $postingJurnalTransaksi->kdproduk = $item->prid;
                    $postingJurnalTransaksi->namaproduktransaksi = 'PPN Jual Persediaan ' . $item->namaproduk . ' a.n ' . $item->namapasien . ' ( ' . $item->noregistrasi . ' )';
                    $postingJurnalTransaksi->deskripsiproduktransaksi = 'ppnpelayananpasien_t';
                    $postingJurnalTransaksi->keteranganlainnya = 'Adjustment ppnpelayananpasien tgl. ' . $item->tgl;
                    $postingJurnalTransaksi->statusenabled = true;
                    $postingJurnalTransaksi->norecrelated = $item->norec_pp;
                    $postingJurnalTransaksi->jenis = $item->jenis;
                    $postingJurnalTransaksi->save();


                    $norec_pj = $postingJurnalTransaksi->norec;

                    //debet
                    $postingJurnalTransaksiD = new PostingJurnalTransaksiD;
                    $postingJurnalTransaksiD->norec = $postingJurnalTransaksiD->generateNewId();
                    $postingJurnalTransaksiD->kdprofile = $kdProfile;
                    $postingJurnalTransaksiD->nojurnal = 0;
                    $postingJurnalTransaksiD->noposting = $noPosting;
                    $postingJurnalTransaksiD->objectaccountfk = $debetId;
                    $postingJurnalTransaksiD->hargasatuand = $totalRp;
                    $postingJurnalTransaksiD->hargasatuank = 0;
                    $postingJurnalTransaksiD->statusenabled = 1;
                    $postingJurnalTransaksiD->norecrelated = $norec_pj;
                    $postingJurnalTransaksiD->save();

                    //kredit
                    $postingJurnalTransaksiD = new PostingJurnalTransaksiD;
                    $postingJurnalTransaksiD->norec = $postingJurnalTransaksiD->generateNewId();
                    $postingJurnalTransaksiD->kdprofile = $kdProfile;
                    $postingJurnalTransaksiD->nojurnal = 0;
                    $postingJurnalTransaksiD->noposting = $noPosting;
                    $postingJurnalTransaksiD->objectaccountfk = $kreditId;
                    $postingJurnalTransaksiD->hargasatuand = 0;
                    $postingJurnalTransaksiD->hargasatuank = $totalRp;
                    $postingJurnalTransaksiD->statusenabled = 1;
                    $postingJurnalTransaksiD->norecrelated = $norec_pj;
                    $postingJurnalTransaksiD->save();
                }
                // dd($postingJurnalTransaksi);
            }


            $aingMacanbebanOB = [];
            if (1 == 1) {


                $aingMacanbebanOB = DB::select(
                    DB::raw("

                    select pjt.norec as pjtnorec,sp.nostruk AS noregistrasi,sp.nostruk_intern AS nocm,sp.namapasien_klien AS namapasien,
                    sp.tglstruk as  tglpelayanan,pr.namaproduk,(spd.hargasatuan + spd.hargatambahan) as  hargajual,spd.qtyproduk as jumlah,spd.harganetto,
                    'Obat'  as Obat,spd.objectprodukfk as produkfk,pr.objectdetailjenisprodukfk,pr.objectkelompokprodukbpjsfk,
                    (((ho.hargajual/$persenhitungppn)*100)*spd.qtyproduk) as harga,
                    pr.id as prid,pr.namaproduk,
                    to_char(sp.tglstruk, 'YYYY-MM-DD') as tgl,spd.norec as norec_pp,
                    cmap.objectcoadebetfk,cmap.objectcoakreditfk,sp.objectruanganfk,ru.jenis
                    from strukpelayanandetail_t as spd
                    inner join strukpelayanan_t as sp on sp.norec=spd.nostrukfk
                    inner join ruangan_m as ru on ru.id=sp.objectruanganfk
                    inner join produk_m as pr on pr.id=spd.objectprodukfk
                    INNER JOIN hargaobat_t as ho on ho.produkfk=spd.objectprodukfk
                    inner join chartofaccountmapjurnal_t as cmap on cmap.objectprodukfk=spd.objectprodukfk --and cmap.objectruanganfk=ru.id
                    left JOIN postingjurnaltransaksi_t as pjt on pjt.norecrelated=spd.norec and pjt.deskripsiproduktransaksi = 'bebanpelayananpasien_tob'
                    where spd.kdprofile = $kdProfile and sp.tglstruk between :tglAwal and :tglAkhir and pjt.norec is  null
                    and substring(sp.nostruk,1,3)='OB/' and cmap.objectjenistrxfk = 5 and sp.objectkelompoktransaksifk=2
                    limit 100
                    "),
                    array(
                        'tglAwal' => $request['tglAwal'],
                        'tglAkhir' => $request['tglAkhir'],
                    )
                );

                foreach ($aingMacanbebanOB as $item) {
                    $coaAdministrasi = 'a';
                    $coaKonsultasi = 'b';
                    $coaVisite = 'c';
                    $coaAkomodasi = 'd';
                    $coaTindakan = 'e';
                    $coaAlatCanggih = 'f';



                    $totalRp = $item->harga; //($item->hargajual) * ($item->jumlah);
                    $totalNettoRp = ($item->harganetto) * ($item->jumlah);


                    $debetId = $item->objectcoadebetfk; //untuk piutang umum //$item->objectcoadebetfk;//1778;//10896;
                    $kreditId = $item->objectcoakreditfk; //12211;
                    $noBuktiTransaksi = $item->noregistrasi;

                    $noJurnalIntern = '';
                    $noPosting = '-'; //$this->generateCode(new PostingJurnalTransaksi, 'noposting', 14, 'RJ-' . $this->getDateTime()->format('ym'));
                    $noJurnalIntern = Carbon::parse($item->tglpelayanan)->format('ym') . 'PN' . Carbon::parse($item->tglpelayanan)->format('d') . '00005';
                    $cekSudahPosting = PostingJurnal::where('norecrelated', $noJurnalIntern)->where('kdprofile', $kdProfile)->get();

                    if ($this->getCountArray($cekSudahPosting) == 0) {

                        $postingJurnalTransaksi = new PostingJurnalTransaksi;
                        $norecHead = $postingJurnalTransaksi->generateNewId();
                        $postingJurnalTransaksi->norec = $norecHead;
                        $postingJurnalTransaksi->kdprofile = $kdProfile;
                        $postingJurnalTransaksi->noposting = $noPosting;
                        $postingJurnalTransaksi->nojurnal = 0;
                        $postingJurnalTransaksi->nojurnal_intern = $noJurnalIntern;
                        $postingJurnalTransaksi->objectjenisjurnalfk = 1;
                        $postingJurnalTransaksi->nobuktitransaksi = $noBuktiTransaksi;
                        $postingJurnalTransaksi->tglbuktitransaksi = $item->tglpelayanan;
                        $postingJurnalTransaksi->kdproduk = $item->prid;
                        $postingJurnalTransaksi->namaproduktransaksi = 'Persediaan obat Bebas ' . $item->namaproduk . ' a.n ' . $item->namapasien . ' (' . $item->noregistrasi . ')';
                        $postingJurnalTransaksi->deskripsiproduktransaksi = 'bebanpelayananpasien_tob';

                        $postingJurnalTransaksi->keteranganlainnya = 'bebanpelayananpasien tgl. ' . $item->tgl;
                        //                    }

                        $postingJurnalTransaksi->statusenabled = 1;
                        $postingJurnalTransaksi->norecrelated = $item->norec_pp;
                        $postingJurnalTransaksi->jenis = $item->jenis;
                        $postingJurnalTransaksi->save();

                        $norec_pj = $postingJurnalTransaksi->norec;

                        //debet
                        $postingJurnalTransaksiD = new PostingJurnalTransaksiD;
                        $postingJurnalTransaksiD->norec = $postingJurnalTransaksiD->generateNewId();
                        $postingJurnalTransaksiD->kdprofile = $kdProfile;
                        $postingJurnalTransaksiD->nojurnal = 0;
                        $postingJurnalTransaksiD->noposting = $noPosting;
                        $postingJurnalTransaksiD->objectaccountfk = $debetId;
                        $postingJurnalTransaksiD->hargasatuand = $totalRp;
                        $postingJurnalTransaksiD->hargasatuank = 0;
                        $postingJurnalTransaksiD->statusenabled = 1;
                        $postingJurnalTransaksiD->norecrelated = $norec_pj;
                        $postingJurnalTransaksiD->save();

                        //kredit
                        $postingJurnalTransaksiD = new PostingJurnalTransaksiD;
                        $postingJurnalTransaksiD->norec = $postingJurnalTransaksiD->generateNewId();
                        $postingJurnalTransaksiD->kdprofile = $kdProfile;
                        $postingJurnalTransaksiD->nojurnal = 0;
                        $postingJurnalTransaksiD->noposting = $noPosting;
                        $postingJurnalTransaksiD->objectaccountfk = $kreditId;
                        $postingJurnalTransaksiD->hargasatuand = 0;
                        $postingJurnalTransaksiD->hargasatuank = $totalRp;
                        $postingJurnalTransaksiD->statusenabled = 1;
                        $postingJurnalTransaksiD->norecrelated = $norec_pj;
                        $postingJurnalTransaksiD->save();
                    } else {
                        $noJurnalIntern = Carbon::parse($item->tglpelayanan)->format('ym') . 'AJ' . Carbon::parse($item->tglpelayanan)->format('d') . '00005';
                        //                    $nojurnal = $this->getSequence('postingjurnaltransaksi_t_nojurnal_seq');

                        $postingJurnalTransaksi = new PostingJurnalTransaksi;
                        $norecHead = $postingJurnalTransaksi->generateNewId();
                        $postingJurnalTransaksi->norec = $norecHead;
                        $postingJurnalTransaksi->kdprofile = $kdProfile;
                        $postingJurnalTransaksi->noposting = $noPosting;
                        $postingJurnalTransaksi->nojurnal = 0;
                        $postingJurnalTransaksi->nojurnal_intern = $noJurnalIntern;
                        $postingJurnalTransaksi->objectjenisjurnalfk = 1;
                        $postingJurnalTransaksi->nobuktitransaksi = $noBuktiTransaksi;
                        $postingJurnalTransaksi->tglbuktitransaksi = date('Y-m-t', strtotime($item->tglpelayanan)); //$item->tglpelayanan;
                        $postingJurnalTransaksi->kdproduk = $item->prid;
                        $postingJurnalTransaksi->namaproduktransaksi = 'Persediaan obat Bebas ' . $item->namaproduk . ' a.n ' . $item->namapasien . ' (' . $item->noregistrasi . ')';
                        $postingJurnalTransaksi->deskripsiproduktransaksi = 'bebanpelayananpasien_tob';
                        $postingJurnalTransaksi->keteranganlainnya = 'Adjustment bebanpelayananpasien tgl. ' . $item->tgl;
                        //                    }

                        $postingJurnalTransaksi->statusenabled = 1;
                        $postingJurnalTransaksi->norecrelated = $item->norec_pp;
                        $postingJurnalTransaksi->jenis = $item->jenis;
                        $postingJurnalTransaksi->save();
                        $norec_pj = $postingJurnalTransaksi->norec;

                        //debet
                        $postingJurnalTransaksiD = new PostingJurnalTransaksiD;
                        $postingJurnalTransaksiD->norec = $postingJurnalTransaksiD->generateNewId();
                        $postingJurnalTransaksiD->kdprofile = $kdProfile;
                        $postingJurnalTransaksiD->nojurnal = 0;
                        $postingJurnalTransaksiD->noposting = $noPosting;
                        $postingJurnalTransaksiD->objectaccountfk = $debetId;
                        $postingJurnalTransaksiD->hargasatuand = $totalRp;
                        $postingJurnalTransaksiD->hargasatuank = 0;
                        $postingJurnalTransaksiD->statusenabled = 1;
                        $postingJurnalTransaksiD->norecrelated = $norec_pj;
                        $postingJurnalTransaksiD->save();

                        //kredit
                        $postingJurnalTransaksiD = new PostingJurnalTransaksiD;
                        $postingJurnalTransaksiD->norec = $postingJurnalTransaksiD->generateNewId();
                        $postingJurnalTransaksiD->kdprofile = $kdProfile;
                        $postingJurnalTransaksiD->nojurnal = 0;
                        $postingJurnalTransaksiD->noposting = $noPosting;
                        $postingJurnalTransaksiD->objectaccountfk = $kreditId;
                        $postingJurnalTransaksiD->hargasatuand = 0;
                        $postingJurnalTransaksiD->hargasatuank = $totalRp;
                        $postingJurnalTransaksiD->statusenabled = 1;
                        $postingJurnalTransaksiD->norecrelated = $norec_pj;
                        $postingJurnalTransaksiD->save();
                    }
                }
            }

            //pemakaian beban ruangan
            $aingMacanPemakaianruangan = [];
            if (1 == 1) {


                $aingMacanPemakaianruangan = DB::select(
                    DB::raw("
                    select spdp.norec as norec_pp, sc.noclosing,to_char(tglclosing, 'YYYY-MM-DD') as tgl,sc.tglclosing,pr.namaproduk,spdp.objectprodukfk as prid, ru.namaruangan,
                    spdp.qtyprodukpemakaian,spdp.harganetto2, ho.hargainventori,
                    (((ho.hargajual/$persenhitungppn)*100)*spdp.qtyprodukpemakaian) as harga,
                    cmap.objectcoadebetfk,cmap.objectcoakreditfk,ru.jenis
                    from stokprodukdetailpemakaian_t as spdp
                    inner join strukclosing_t as sc on sc.norec=spdp.noclosingfk
                    INNER JOIN ruangan_m as ru on ru.id=spdp.objectruanganfk
                    INNER JOIN produk_m as pr on pr.id=spdp.objectprodukfk
                    INNER JOIN hargaobat_t as ho on ho.produkfk=spdp.objectprodukfk
                    inner join chartofaccountmapjurnal_t as cmap on
                    cmap.objectprodukfk=spdp.objectprodukfk
                    left JOIN postingjurnaltransaksi_t as pjt on pjt.norecrelated=spdp.norec and pjt.deskripsiproduktransaksi = 'pemakaianruangan_t'
                    where sc.kdprofile = $kdProfile and sc.tglclosing  BETWEEN :tglAwal and :tglAkhir
                    and cmap.objectjenistrxfk=5
                    and pjt.norec is  null
                    limit 300
                    "),
                    array(
                        'tglAwal' => $request['tglAwal'],
                        'tglAkhir' => $request['tglAkhir'],
                    )
                );

                foreach ($aingMacanPemakaianruangan as $item) {
                    $coaAdministrasi = 'a';
                    $coaKonsultasi = 'b';
                    $coaVisite = 'c';
                    $coaAkomodasi = 'd';
                    $coaTindakan = 'e';
                    $coaAlatCanggih = 'f';



                    $totalRp = $item->harga; //($item->hargajual) * ($item->jumlah);
                    $totalNettoRp = ($item->harganetto) * ($item->jumlah);


                    $debetId = $item->objectcoadebetfk; //untuk piutang umum //$item->objectcoadebetfk;//1778;//10896;
                    $kreditId = $item->objectcoakreditfk; //12211;
                    $noBuktiTransaksi = $item->noclosing;

                    $noJurnalIntern = '';
                    $noPosting = '-'; //$this->generateCode(new PostingJurnalTransaksi, 'noposting', 14, 'RJ-' . $this->getDateTime()->format('ym'));
                    $noJurnalIntern = Carbon::parse($item->tglclosing)->format('ym') . 'PN' . Carbon::parse($item->tglclosing)->format('d') . '00005';
                    $cekSudahPosting = PostingJurnal::where('norecrelated', $noJurnalIntern)->where('kdprofile', $kdProfile)->get();

                    if (count($cekSudahPosting) == 0) {

                        $postingJurnalTransaksi = new PostingJurnalTransaksi;
                        $norecHead = $postingJurnalTransaksi->generateNewId();
                        $postingJurnalTransaksi->norec = $norecHead;
                        $postingJurnalTransaksi->kdprofile = $kdProfile;
                        $postingJurnalTransaksi->noposting = $noPosting;
                        $postingJurnalTransaksi->nojurnal = 0;
                        $postingJurnalTransaksi->nojurnal_intern = $noJurnalIntern;
                        $postingJurnalTransaksi->objectjenisjurnalfk = 1;
                        $postingJurnalTransaksi->nobuktitransaksi = $noBuktiTransaksi;
                        $postingJurnalTransaksi->tglbuktitransaksi = $item->tglclosing;
                        $postingJurnalTransaksi->kdproduk = $item->prid;
                        $postingJurnalTransaksi->namaproduktransaksi = 'Pemakaian Stok Ruangan di ' . $item->namaruangan . ' : ' . $item->namaproduk;
                        $postingJurnalTransaksi->deskripsiproduktransaksi = 'pemakaianruangan_t';

                        $postingJurnalTransaksi->keteranganlainnya = 'bebanpelayananpasien tgl. ' . $item->tgl; //PemakaianStokRuangan
                        //                    }

                        $postingJurnalTransaksi->statusenabled = 1;
                        $postingJurnalTransaksi->norecrelated = $item->norec_pp;
                        $postingJurnalTransaksi->jenis = $item->jenis;
                        $postingJurnalTransaksi->save();

                        $norec_pj = $postingJurnalTransaksi->norec;

                        //debet
                        $postingJurnalTransaksiD = new PostingJurnalTransaksiD;
                        $postingJurnalTransaksiD->norec = $postingJurnalTransaksiD->generateNewId();
                        $postingJurnalTransaksiD->kdprofile = $kdProfile;
                        $postingJurnalTransaksiD->nojurnal = 0;
                        $postingJurnalTransaksiD->noposting = $noPosting;
                        $postingJurnalTransaksiD->objectaccountfk = $debetId;
                        $postingJurnalTransaksiD->hargasatuand = $totalRp;
                        $postingJurnalTransaksiD->hargasatuank = 0;
                        $postingJurnalTransaksiD->statusenabled = 1;
                        $postingJurnalTransaksiD->norecrelated = $norec_pj;
                        $postingJurnalTransaksiD->save();

                        //kredit
                        $postingJurnalTransaksiD = new PostingJurnalTransaksiD;
                        $postingJurnalTransaksiD->norec = $postingJurnalTransaksiD->generateNewId();
                        $postingJurnalTransaksiD->kdprofile = $kdProfile;
                        $postingJurnalTransaksiD->nojurnal = 0;
                        $postingJurnalTransaksiD->noposting = $noPosting;
                        $postingJurnalTransaksiD->objectaccountfk = $kreditId;
                        $postingJurnalTransaksiD->hargasatuand = 0;
                        $postingJurnalTransaksiD->hargasatuank = $totalRp;
                        $postingJurnalTransaksiD->statusenabled = 1;
                        $postingJurnalTransaksiD->norecrelated = $norec_pj;
                        $postingJurnalTransaksiD->save();
                    } else {
                        $noJurnalIntern = Carbon::parse($item->tglclosing)->format('ym') . 'AJ' . Carbon::parse($item->tglclosing)->format('d') . '00005';
                        //                    $nojurnal = $this->getSequence('postingjurnaltransaksi_t_nojurnal_seq');

                        $postingJurnalTransaksi = new PostingJurnalTransaksi;
                        $norecHead = $postingJurnalTransaksi->generateNewId();
                        $postingJurnalTransaksi->norec = $norecHead;
                        $postingJurnalTransaksi->kdprofile = $kdProfile;
                        $postingJurnalTransaksi->noposting = $noPosting;
                        $postingJurnalTransaksi->nojurnal = 0;
                        $postingJurnalTransaksi->nojurnal_intern = $noJurnalIntern;
                        $postingJurnalTransaksi->objectjenisjurnalfk = 1;
                        $postingJurnalTransaksi->nobuktitransaksi = $noBuktiTransaksi;
                        $postingJurnalTransaksi->tglbuktitransaksi = date('Y-m-t', strtotime($item->tglclosing)); //$item->tglpelayanan;
                        $postingJurnalTransaksi->kdproduk = $item->prid;
                        $postingJurnalTransaksi->namaproduktransaksi = 'Pemakaian Stok Ruangan di ' . $item->namaruangan . ' : ' . $item->namaproduk;
                        $postingJurnalTransaksi->deskripsiproduktransaksi = 'pemakaianruangan_t';
                        $postingJurnalTransaksi->keteranganlainnya = 'Adjustment bebanpelayananpasien tgl. ' . $item->tgl;
                        $postingJurnalTransaksi->jenis = $item->jenis;
                        //                    }

                        $postingJurnalTransaksi->statusenabled = 1;
                        $postingJurnalTransaksi->norecrelated = $item->norec_pp;
                        $postingJurnalTransaksi->jenis = $item->jenis;
                        $postingJurnalTransaksi->save();
                        $norec_pj = $postingJurnalTransaksi->norec;

                        //debet
                        $postingJurnalTransaksiD = new PostingJurnalTransaksiD;
                        $postingJurnalTransaksiD->norec = $postingJurnalTransaksiD->generateNewId();
                        $postingJurnalTransaksiD->kdprofile = $kdProfile;
                        $postingJurnalTransaksiD->nojurnal = 0;
                        $postingJurnalTransaksiD->noposting = $noPosting;
                        $postingJurnalTransaksiD->objectaccountfk = $debetId;
                        $postingJurnalTransaksiD->hargasatuand = $totalRp;
                        $postingJurnalTransaksiD->hargasatuank = 0;
                        $postingJurnalTransaksiD->statusenabled = 1;
                        $postingJurnalTransaksiD->norecrelated = $norec_pj;
                        $postingJurnalTransaksiD->save();

                        //kredit
                        $postingJurnalTransaksiD = new PostingJurnalTransaksiD;
                        $postingJurnalTransaksiD->norec = $postingJurnalTransaksiD->generateNewId();
                        $postingJurnalTransaksiD->kdprofile = $kdProfile;
                        $postingJurnalTransaksiD->nojurnal = 0;
                        $postingJurnalTransaksiD->noposting = $noPosting;
                        $postingJurnalTransaksiD->objectaccountfk = $kreditId;
                        $postingJurnalTransaksiD->hargasatuand = 0;
                        $postingJurnalTransaksiD->hargasatuank = $totalRp;
                        $postingJurnalTransaksiD->statusenabled = 1;
                        $postingJurnalTransaksiD->norecrelated = $norec_pj;
                        $postingJurnalTransaksiD->save();
                    }
                }
            }
            //

            $transStatus = 'true';
        } catch (\Exception $e) {
            $transStatus = 'false';
        }
        $transMessage = 'Posting';
        $req = $request->all();
        if ($transStatus == 'true') {
            $transMessage = $transMessage . " Jurnal Berhasil";
            DB::commit();
            $result = array(
                "status" => 200,
                "result" => array(
                    "data" => $aingMacan,
                    "count" => count($aingMacan),
                    "countppn" => count($aingMacanppn),
                    "countbebanOB" => count($aingMacanbebanOB),
                    "countbebanPSR" => count($aingMacanPemakaianruangan),
                    //                "posting" => $cekSudahPosting,g
                    // "data" => $aingMacan33 ,
                    "req" => $req,
                    "as" => 'as@epic',
                )
            );
        } else {
            $transMessage = $transMessage . " Jurnal Gagal!!";
            DB::rollBack();
            $result = array(
                "status" => 400,
                "result" => array(
                    "count" => count($cekDataPelayanan),
                    "data" => $cekDataPelayanan, //$noResep,
                    //                "coa" => $coacoa,
                    "err" => $e->getMessage() . ' ' . $e->getLine(),
                    "as" => 'as@epic',
                )
            );
        }

        return $this->respond($result['result'], $result['status'], $transMessage);
    }
    public function PostingJurnal_pelayananpasien_tob(Request $request)
    {
        $kdProfile = (int) $this->kdProfile;
        $dataLogin = $request->all();
        DB::beginTransaction();
        try {
            $list = $this->settingFix('kelompokTransaksiNonPelayanan', $kdProfile);
            $nostruk = '';
            if (isset($request['nostruk']) && $request['nostruk'] != '') {
                $nostruk = " and sp.nostruk ='$request[nostruk]'";
            }
            $persenhitungppn = $this->settingFix('hitungPPN', $kdProfile);
            $persenppn = $this->settingFix('persenPPN', $kdProfile);

            $aingMacan33 = [];
            $isAktif = $this->settingFix('PostingJurnal_pelayananpasien_tob_isaktif', $kdProfile);

            if (!empty($isAktif) && $isAktif == 1) {
                // return 'tester';

                $aingMacan33 = DB::select(
                    DB::raw("

                        select pjt.norec as pjtnorec,sp.nostruk AS noregistrasi,sp.nostruk_intern AS nocm,sp.namapasien_klien AS namapasien,
                        sp.tglstruk as  tglpelayanan,pr.namaproduk,(spd.hargasatuan + spd.hargatambahan) as  hargajual,spd.qtyproduk as jumlah,spd.harganetto,
                        'Obat'  as Obat,spd.objectprodukfk as produkfk,pr.objectdetailjenisprodukfk,pr.objectkelompokprodukbpjsfk,
                        ((spd.hargasatuan  )*spd.qtyproduk)+ spd.hargatambahan as harga,
                        pr.id as prid,pr.namaproduk,
                        to_char(sp.tglstruk, 'YYYY-MM-DD') as tgl,spd.norec as norec_pp,
                        cmap.objectcoadebetfk,cmap.objectcoakreditfk,sp.objectruanganfk,ru.jenis
                        from strukpelayanandetail_t as spd
                        inner join strukpelayanan_t as sp on sp.norec=spd.nostrukfk
                        inner join ruangan_m as ru on ru.id=sp.objectruanganfk
                        inner join produk_m as pr on pr.id=spd.objectprodukfk
                        inner join chartofaccountmapjurnal_t as cmap on cmap.objectprodukfk=spd.objectprodukfk --and cmap.objectruanganfk=ru.id
                        left JOIN postingjurnaltransaksi_t as pjt on pjt.norecrelated=spd.norec and pjt.deskripsiproduktransaksi = 'pelayananpasien_tob'
                        where spd.kdprofile = $kdProfile and sp.tglstruk between :tglAwal and :tglAkhir and pjt.norec is  null
                        and substring(sp.nostruk,1,3)='OB/' and cmap.objectjenistrxfk = 1 and sp.objectkelompoktransaksifk in ($list)
                        $nostruk
                        limit 100
                        "),
                    array(
                        'tglAwal' => $request['tglAwal'],
                        'tglAkhir' => $request['tglAkhir'],
                    )
                );

                foreach ($aingMacan33 as $item) {
                    $coaAdministrasi = 'a';
                    $coaKonsultasi = 'b';
                    $coaVisite = 'c';
                    $coaAkomodasi = 'd';
                    $coaTindakan = 'e';
                    $coaAlatCanggih = 'f';



                    $totalRp = $item->harga; //($item->hargajual) * ($item->jumlah);
                    $totalNettoRp = ($item->harganetto) * ($item->jumlah);



                    $debetId = 65; //untuk piutang umum //$item->objectcoadebetfk;//1778;//10896;
                    $kreditId = $item->objectcoakreditfk; //12211;

                    // $noBuktiTransaksi =$item->prid;
                    $noBuktiTransaksi = $item->noregistrasi;
                    //                $namaPasien = $item->namapasien;
                    //                $namaTindakan = $item->namaproduk;

                    $noJurnalIntern = '';
                    //                if ($item->objectjenisprodukfk != 97) {
                    //                if ($deptId == 16) {
                    //                    $noPosting = '-';//$this->generateCode(new PostingJurnalTransaksi, 'noposting', 14, 'RI-' . $this->getDateTime()->format('ym'));
                    //                    $noJurnalIntern = Carbon::parse($item->tglpelayanan)->format('ym').'PN'.Carbon::parse($item->tglpelayanan)->format('d').'00002';
                    //                }else{
                    $noPosting = '-'; //$this->generateCode(new PostingJurnalTransaksi, 'noposting', 14, 'RJ-' . $this->getDateTime()->format('ym'));
                    $noJurnalIntern = Carbon::parse($item->tglpelayanan)->format('ym') . 'PN' . Carbon::parse($item->tglpelayanan)->format('d') . '00001';
                    //                }
                    $cekSudahPosting = PostingJurnal::where('norecrelated', $noJurnalIntern)->where('kdprofile', $kdProfile)->get();

                    if ($this->getCountArray($cekSudahPosting) == 0) {
                        //                    $nojurnal = $this->getSequence('postingjurnaltransaksi_t_nojurnal_seq');

                        $postingJurnalTransaksi = new PostingJurnalTransaksi;
                        $norecHead = $postingJurnalTransaksi->generateNewId();
                        $postingJurnalTransaksi->norec = $norecHead;
                        $postingJurnalTransaksi->kdprofile = $kdProfile;
                        $postingJurnalTransaksi->noposting = $noPosting;
                        $postingJurnalTransaksi->nojurnal = 0;
                        $postingJurnalTransaksi->nojurnal_intern = $noJurnalIntern;
                        $postingJurnalTransaksi->objectjenisjurnalfk = 1;
                        $postingJurnalTransaksi->nobuktitransaksi = $noBuktiTransaksi;
                        $postingJurnalTransaksi->tglbuktitransaksi = $item->tglpelayanan;
                        $postingJurnalTransaksi->kdproduk = $item->prid;
                        $postingJurnalTransaksi->namaproduktransaksi = $item->namaproduk . ' a.n ' . $item->namapasien . ' (' . $item->noregistrasi . ')';
                        $postingJurnalTransaksi->deskripsiproduktransaksi = 'pelayananpasien_tob';
                        //                    if ($deptId == 16){
                        //                        $postingJurnalTransaksi->keteranganlainnya = 'Pendapatan RI tgl. ' . $item->tgl;
                        //                    }else{
                        $postingJurnalTransaksi->keteranganlainnya = 'PelayananPasien tgl. ' . $item->tgl;
                        //                    }

                        $postingJurnalTransaksi->statusenabled = 1;
                        $postingJurnalTransaksi->norecrelated = $item->norec_pp;
                        $postingJurnalTransaksi->jenis = $item->jenis;
                        $postingJurnalTransaksi->save();

                        $norec_pj = $postingJurnalTransaksi->norec;

                        //debet
                        $postingJurnalTransaksiD = new PostingJurnalTransaksiD;
                        $postingJurnalTransaksiD->norec = $postingJurnalTransaksiD->generateNewId();
                        $postingJurnalTransaksiD->kdprofile = $kdProfile;
                        $postingJurnalTransaksiD->nojurnal = 0;
                        $postingJurnalTransaksiD->noposting = $noPosting;
                        $postingJurnalTransaksiD->objectaccountfk = $debetId;
                        $postingJurnalTransaksiD->hargasatuand = $totalRp;
                        $postingJurnalTransaksiD->hargasatuank = 0;
                        $postingJurnalTransaksiD->statusenabled = 1;
                        $postingJurnalTransaksiD->norecrelated = $norec_pj;
                        $postingJurnalTransaksiD->save();

                        //kredit
                        $postingJurnalTransaksiD = new PostingJurnalTransaksiD;
                        $postingJurnalTransaksiD->norec = $postingJurnalTransaksiD->generateNewId();
                        $postingJurnalTransaksiD->kdprofile = $kdProfile;
                        $postingJurnalTransaksiD->nojurnal = 0;
                        $postingJurnalTransaksiD->noposting = $noPosting;
                        $postingJurnalTransaksiD->objectaccountfk = $kreditId;
                        $postingJurnalTransaksiD->hargasatuand = 0;
                        $postingJurnalTransaksiD->hargasatuank = $totalRp;
                        $postingJurnalTransaksiD->statusenabled = 1;
                        $postingJurnalTransaksiD->norecrelated = $norec_pj;
                        $postingJurnalTransaksiD->save();
                    } else {
                        $noJurnalIntern = Carbon::parse($item->tglpelayanan)->format('ym') . 'AJ' . Carbon::parse($item->tglpelayanan)->format('d') . '00001';
                        //                    $nojurnal = $this->getSequence('postingjurnaltransaksi_t_nojurnal_seq');

                        $postingJurnalTransaksi = new PostingJurnalTransaksi;
                        $norecHead = $postingJurnalTransaksi->generateNewId();
                        $postingJurnalTransaksi->norec = $norecHead;
                        $postingJurnalTransaksi->kdprofile = $kdProfile;
                        $postingJurnalTransaksi->noposting = $noPosting;
                        $postingJurnalTransaksi->nojurnal = 0;
                        $postingJurnalTransaksi->nojurnal_intern = $noJurnalIntern;
                        $postingJurnalTransaksi->objectjenisjurnalfk = 1;
                        $postingJurnalTransaksi->nobuktitransaksi = $noBuktiTransaksi;
                        $postingJurnalTransaksi->tglbuktitransaksi = date('Y-m-t', strtotime($item->tglpelayanan)); //$item->tglpelayanan;
                        $postingJurnalTransaksi->kdproduk = $item->prid;
                        $postingJurnalTransaksi->namaproduktransaksi = $item->namaproduk . ' a.n ' . $item->namapasien . ' (' . $item->noregistrasi . ')';
                        $postingJurnalTransaksi->deskripsiproduktransaksi = 'pelayananpasien_tob';
                        //                    if ($deptId == 16){
                        //                        $postingJurnalTransaksi->keteranganlainnya = 'Adjustment Pendapatan RI tgl. ' . $item->tgl;
                        //                    }else{
                        $postingJurnalTransaksi->keteranganlainnya = 'Adjustment PelayananPasien tgl. ' . $item->tgl;
                        //                    }

                        $postingJurnalTransaksi->statusenabled = 1;
                        $postingJurnalTransaksi->norecrelated = $item->norec_pp;
                        $postingJurnalTransaksi->jenis = $item->jenis;
                        $postingJurnalTransaksi->save();
                        $norec_pj = $postingJurnalTransaksi->norec;

                        //debet
                        $postingJurnalTransaksiD = new PostingJurnalTransaksiD;
                        $postingJurnalTransaksiD->norec = $postingJurnalTransaksiD->generateNewId();
                        $postingJurnalTransaksiD->kdprofile = $kdProfile;
                        $postingJurnalTransaksiD->nojurnal = 0;
                        $postingJurnalTransaksiD->noposting = $noPosting;
                        $postingJurnalTransaksiD->objectaccountfk = $debetId;
                        $postingJurnalTransaksiD->hargasatuand = $totalRp;
                        $postingJurnalTransaksiD->hargasatuank = 0;
                        $postingJurnalTransaksiD->statusenabled = 1;
                        $postingJurnalTransaksiD->norecrelated = $norec_pj;
                        $postingJurnalTransaksiD->save();

                        //kredit
                        $postingJurnalTransaksiD = new PostingJurnalTransaksiD;
                        $postingJurnalTransaksiD->norec = $postingJurnalTransaksiD->generateNewId();
                        $postingJurnalTransaksiD->kdprofile = $kdProfile;
                        $postingJurnalTransaksiD->nojurnal = 0;
                        $postingJurnalTransaksiD->noposting = $noPosting;
                        $postingJurnalTransaksiD->objectaccountfk = $kreditId;
                        $postingJurnalTransaksiD->hargasatuand = 0;
                        $postingJurnalTransaksiD->hargasatuank = $totalRp;
                        $postingJurnalTransaksiD->statusenabled = 1;
                        $postingJurnalTransaksiD->norecrelated = $norec_pj;
                        $postingJurnalTransaksiD->save();
                    }
                }
                //end Posting Obat Bebas


            }

            $aingMacanbebanOB = [];
            $isAktif = $this->settingDataFixed('PostingJurnal_bebanpelayananpasien_tob_isaktif', $kdProfile);

            if (!empty($isAktif) && $isAktif == 1) {

                $aingMacanbebanOB = DB::select(
                    DB::raw("

                            select pjt.norec as pjtnorec,sp.nostruk AS noregistrasi,sp.nostruk_intern AS nocm,sp.namapasien_klien AS namapasien,
                            sp.tglstruk as  tglpelayanan,pr.namaproduk,(spd.hargasatuan + spd.hargatambahan) as  hargajual,spd.qtyproduk as jumlah,spd.harganetto,
                            'Obat'  as Obat,spd.objectprodukfk as produkfk,pr.objectdetailjenisprodukfk,pr.objectkelompokprodukbpjsfk,
                            (((ho.hargajual/$persenhitungppn)*100)*spd.qtyproduk) as harga,
                            pr.id as prid,pr.namaproduk,
                            to_char(sp.tglstruk, 'YYYY-MM-DD') as tgl,spd.norec as norec_pp,
                            cmap.objectcoadebetfk,cmap.objectcoakreditfk,sp.objectruanganfk,ru.jenis
                            from strukpelayanandetail_t as spd
                            inner join strukpelayanan_t as sp on sp.norec=spd.nostrukfk
                            inner join ruangan_m as ru on ru.id=sp.objectruanganfk
                            inner join produk_m as pr on pr.id=spd.objectprodukfk
                            INNER JOIN hargaobat_t as ho on ho.produkfk=spd.objectprodukfk
                            inner join chartofaccountmapjurnal_t as cmap on cmap.objectprodukfk=spd.objectprodukfk --and cmap.objectruanganfk=ru.id
                            left JOIN postingjurnaltransaksi_t as pjt on pjt.norecrelated=spd.norec and pjt.deskripsiproduktransaksi = 'bebanpelayananpasien_tob'
                            where spd.kdprofile = $kdProfile and sp.tglstruk between :tglAwal and :tglAkhir
                            and pjt.norec is null
                            and substring(sp.nostruk,1,3)='OB/' and cmap.objectjenistrxfk = 5 and sp.objectkelompoktransaksifk in ($list)
                            $nostruk
                            limit 100
                            "),
                    array(
                        'tglAwal' => $request['tglAwal'],
                        'tglAkhir' => $request['tglAkhir'],
                    )
                );
                // return 'test';
                // return $aingMacanbebanOB;
                foreach ($aingMacanbebanOB as $item) {
                    $coaAdministrasi = 'a';
                    $coaKonsultasi = 'b';
                    $coaVisite = 'c';
                    $coaAkomodasi = 'd';
                    $coaTindakan = 'e';
                    $coaAlatCanggih = 'f';

                    $totalRp = $item->harga; //($item->hargajual) * ($item->jumlah);
                    $totalNettoRp = ($item->harganetto) * ($item->jumlah);


                    $debetId = $item->objectcoadebetfk; //untuk piutang umum //$item->objectcoadebetfk;//1778;//10896;
                    $kreditId = $item->objectcoakreditfk; //12211;
                    $noBuktiTransaksi = $item->noregistrasi;

                    $noJurnalIntern = '';
                    $noPosting = '-'; //$this->generateCode(new PostingJurnalTransaksi, 'noposting', 14, 'RJ-' . $this->getDateTime()->format('ym'));
                    $noJurnalIntern = Carbon::parse($item->tglpelayanan)->format('ym') . 'PN' . Carbon::parse($item->tglpelayanan)->format('d') . '00005';
                    $cekSudahPosting = PostingJurnal::where('norecrelated', $noJurnalIntern)->where('kdprofile', $kdProfile)->get();

                    if ($this->getCountArray($cekSudahPosting) == 0) {

                        $postingJurnalTransaksi = new PostingJurnalTransaksi;
                        $norecHead = $postingJurnalTransaksi->generateNewId();
                        $postingJurnalTransaksi->norec = $norecHead;
                        $postingJurnalTransaksi->kdprofile = $kdProfile;
                        $postingJurnalTransaksi->noposting = $noPosting;
                        $postingJurnalTransaksi->nojurnal = 0;
                        $postingJurnalTransaksi->nojurnal_intern = $noJurnalIntern;
                        $postingJurnalTransaksi->objectjenisjurnalfk = 1;
                        $postingJurnalTransaksi->nobuktitransaksi = $noBuktiTransaksi;
                        $postingJurnalTransaksi->tglbuktitransaksi = $item->tglpelayanan;
                        $postingJurnalTransaksi->kdproduk = $item->prid;
                        $postingJurnalTransaksi->namaproduktransaksi = 'Persediaan obat Bebas ' . $item->namaproduk . ' a.n ' . $item->namapasien . ' (' . $item->noregistrasi . ')';
                        $postingJurnalTransaksi->deskripsiproduktransaksi = 'bebanpelayananpasien_tob';

                        $postingJurnalTransaksi->keteranganlainnya = 'bebanpelayananpasien tgl. ' . $item->tgl;
                        //                    }

                        $postingJurnalTransaksi->statusenabled = 1;
                        $postingJurnalTransaksi->norecrelated = $item->norec_pp;
                        $postingJurnalTransaksi->jenis = $item->jenis;
                        $postingJurnalTransaksi->save();

                        $norec_pj = $postingJurnalTransaksi->norec;

                        //debet
                        $postingJurnalTransaksiD = new PostingJurnalTransaksiD;
                        $postingJurnalTransaksiD->norec = $postingJurnalTransaksiD->generateNewId();
                        $postingJurnalTransaksiD->kdprofile = $kdProfile;
                        $postingJurnalTransaksiD->nojurnal = 0;
                        $postingJurnalTransaksiD->noposting = $noPosting;
                        $postingJurnalTransaksiD->objectaccountfk = $debetId;
                        $postingJurnalTransaksiD->hargasatuand = $totalRp;
                        $postingJurnalTransaksiD->hargasatuank = 0;
                        $postingJurnalTransaksiD->statusenabled = 1;
                        $postingJurnalTransaksiD->norecrelated = $norec_pj;
                        $postingJurnalTransaksiD->save();

                        //kredit
                        $postingJurnalTransaksiD = new PostingJurnalTransaksiD;
                        $postingJurnalTransaksiD->norec = $postingJurnalTransaksiD->generateNewId();
                        $postingJurnalTransaksiD->kdprofile = $kdProfile;
                        $postingJurnalTransaksiD->nojurnal = 0;
                        $postingJurnalTransaksiD->noposting = $noPosting;
                        $postingJurnalTransaksiD->objectaccountfk = $kreditId;
                        $postingJurnalTransaksiD->hargasatuand = 0;
                        $postingJurnalTransaksiD->hargasatuank = $totalRp;
                        $postingJurnalTransaksiD->statusenabled = 1;
                        $postingJurnalTransaksiD->norecrelated = $norec_pj;
                        $postingJurnalTransaksiD->save();
                    } else {
                        $noJurnalIntern = Carbon::parse($item->tglpelayanan)->format('ym') . 'AJ' . Carbon::parse($item->tglpelayanan)->format('d') . '00005';
                        //                    $nojurnal = $this->getSequence('postingjurnaltransaksi_t_nojurnal_seq');

                        $postingJurnalTransaksi = new PostingJurnalTransaksi;
                        $norecHead = $postingJurnalTransaksi->generateNewId();
                        $postingJurnalTransaksi->norec = $norecHead;
                        $postingJurnalTransaksi->kdprofile = $kdProfile;
                        $postingJurnalTransaksi->noposting = $noPosting;
                        $postingJurnalTransaksi->nojurnal = 0;
                        $postingJurnalTransaksi->nojurnal_intern = $noJurnalIntern;
                        $postingJurnalTransaksi->objectjenisjurnalfk = 1;
                        $postingJurnalTransaksi->nobuktitransaksi = $noBuktiTransaksi;
                        $postingJurnalTransaksi->tglbuktitransaksi = date('Y-m-t', strtotime($item->tglpelayanan)); //$item->tglpelayanan;
                        $postingJurnalTransaksi->kdproduk = $item->prid;
                        $postingJurnalTransaksi->namaproduktransaksi = 'Persediaan obat Bebas ' . $item->namaproduk . ' a.n ' . $item->namapasien . ' (' . $item->noregistrasi . ')';
                        $postingJurnalTransaksi->deskripsiproduktransaksi = 'bebanpelayananpasien_tob';
                        $postingJurnalTransaksi->keteranganlainnya = 'Adjustment bebanpelayananpasien tgl. ' . $item->tgl;
                        //                    }

                        $postingJurnalTransaksi->statusenabled = 1;
                        $postingJurnalTransaksi->norecrelated = $item->norec_pp;
                        $postingJurnalTransaksi->jenis = $item->jenis;
                        $postingJurnalTransaksi->save();
                        $norec_pj = $postingJurnalTransaksi->norec;

                        //debet
                        $postingJurnalTransaksiD = new PostingJurnalTransaksiD;
                        $postingJurnalTransaksiD->norec = $postingJurnalTransaksiD->generateNewId();
                        $postingJurnalTransaksiD->kdprofile = $kdProfile;
                        $postingJurnalTransaksiD->nojurnal = 0;
                        $postingJurnalTransaksiD->noposting = $noPosting;
                        $postingJurnalTransaksiD->objectaccountfk = $debetId;
                        $postingJurnalTransaksiD->hargasatuand = $totalRp;
                        $postingJurnalTransaksiD->hargasatuank = 0;
                        $postingJurnalTransaksiD->statusenabled = 1;
                        $postingJurnalTransaksiD->norecrelated = $norec_pj;
                        $postingJurnalTransaksiD->save();

                        //kredit
                        $postingJurnalTransaksiD = new PostingJurnalTransaksiD;
                        $postingJurnalTransaksiD->norec = $postingJurnalTransaksiD->generateNewId();
                        $postingJurnalTransaksiD->kdprofile = $kdProfile;
                        $postingJurnalTransaksiD->nojurnal = 0;
                        $postingJurnalTransaksiD->noposting = $noPosting;
                        $postingJurnalTransaksiD->objectaccountfk = $kreditId;
                        $postingJurnalTransaksiD->hargasatuand = 0;
                        $postingJurnalTransaksiD->hargasatuank = $totalRp;
                        $postingJurnalTransaksiD->statusenabled = 1;
                        $postingJurnalTransaksiD->norecrelated = $norec_pj;
                        $postingJurnalTransaksiD->save();
                    }
                }
                //end Posting Obat Bebas

            }

            $aingMacanppnOB = [];
            $isAktif = $this->settingFix('PostingJurnal_ppnjual_tob_isaktif', $kdProfile);
            if (!empty($isAktif) && $isAktif == 1) {
                $aingMacanppnOB = DB::select(
                    DB::raw("

                    select pjt.norec as pjtnorec,sp.nostruk AS noregistrasi,sp.nostruk_intern AS nocm,sp.namapasien_klien AS namapasien,
                    sp.tglstruk as  tglpelayanan,pr.namaproduk,(spd.hargasatuan + spd.hargatambahan) as  hargajual,spd.qtyproduk as jumlah,spd.harganetto,
                    'Obat'  as Obat,spd.objectprodukfk as produkfk,pr.objectdetailjenisprodukfk,pr.objectkelompokprodukbpjsfk,
                    (((spd.harganetto/$persenhitungppn)*100)*spd.qtyproduk) as harga,
                    pr.id as prid,pr.namaproduk,
                    to_char(sp.tglstruk, 'YYYY-MM-DD') as tgl,spd.norec as norec_pp,
                    cmap.objectcoadebetfk,cmap.objectcoakreditfk,sp.objectruanganfk,ru.jenis
                    from strukpelayanandetail_t as spd
                    inner join strukpelayanan_t as sp on sp.norec=spd.nostrukfk
                    inner join ruangan_m as ru on ru.id=sp.objectruanganfk
                    inner join produk_m as pr on pr.id=spd.objectprodukfk
                    INNER JOIN hargaobat_t as ho on ho.produkfk=spd.objectprodukfk
                    inner join chartofaccountmapjurnal_t as cmap on cmap.objectprodukfk=spd.objectprodukfk --and cmap.objectruanganfk=ru.id
                    left JOIN postingjurnaltransaksi_t as pjt on pjt.norecrelated=spd.norec and pjt.deskripsiproduktransaksi = 'ppnpelayananpasien_tob'
                    where spd.kdprofile = $kdProfile and sp.tglstruk between :tglAwal and :tglAkhir and pjt.norec is  null
                    and substring(sp.nostruk,1,3)='OB/' and cmap.objectjenistrxfk = 9 and sp.objectkelompoktransaksifk in ($list)
                    $nostruk
                    limit 100
                    "),
                    array(
                        'tglAwal' => $request['tglAwal'],
                        'tglAkhir' => $request['tglAkhir'],
                    )
                );

                foreach ($aingMacanppnOB as $item) {
                    $coaAdministrasi = 'a';
                    $coaKonsultasi = 'b';
                    $coaVisite = 'c';
                    $coaAkomodasi = 'd';
                    $coaTindakan = 'e';
                    $coaAlatCanggih = 'f';



                    // $totalRp = $item->harga;//($item->hargajual) * ($item->jumlah);
                    // $totalNettoRp = ($item->harganetto) * ($item->jumlah);

                    // $totalNettoRp = (($item->harganetto/$persenhitungppn) * 100); //Harga netto sebelum PPN
                    // $totalRp = $item->harga;//($item->hargajual) * ($item->jumlah);
                    $totalRp = ($item->harga * $persenppn) / 100;
                    // $totalRp=$totalppn * ($item->jumlah);


                    $debetId = $item->objectcoadebetfk; //untuk piutang umum //$item->objectcoadebetfk;//1778;//10896;
                    $kreditId = $item->objectcoakreditfk; //12211;
                    $noBuktiTransaksi = $item->noregistrasi;

                    $noJurnalIntern = '';
                    $noPosting = '-'; //$this->generateCode(new PostingJurnalTransaksi, 'noposting', 14, 'RJ-' . $this->getDateTime()->format('ym'));
                    $noJurnalIntern = Carbon::parse($item->tglpelayanan)->format('ym') . 'PN' . Carbon::parse($item->tglpelayanan)->format('d') . '00009';
                    $cekSudahPosting = PostingJurnal::where('norecrelated', $noJurnalIntern)->where('kdprofile', $kdProfile)->get();

                    if ($this->getCountArray($cekSudahPosting) == 0) {

                        $postingJurnalTransaksi = new PostingJurnalTransaksi;
                        $norecHead = $postingJurnalTransaksi->generateNewId();
                        $postingJurnalTransaksi->norec = $norecHead;
                        $postingJurnalTransaksi->kdprofile = $kdProfile;
                        $postingJurnalTransaksi->noposting = $noPosting;
                        $postingJurnalTransaksi->nojurnal = 0;
                        $postingJurnalTransaksi->nojurnal_intern = $noJurnalIntern;
                        $postingJurnalTransaksi->objectjenisjurnalfk = 1;
                        $postingJurnalTransaksi->nobuktitransaksi = $noBuktiTransaksi;
                        $postingJurnalTransaksi->tglbuktitransaksi = $item->tglpelayanan;
                        $postingJurnalTransaksi->kdproduk = $item->prid;
                        $postingJurnalTransaksi->namaproduktransaksi = 'PPN Keluar obat Bebas ' . $item->namaproduk . ' a.n ' . $item->namapasien . ' (' . $item->noregistrasi . ')';
                        $postingJurnalTransaksi->deskripsiproduktransaksi = 'ppnpelayananpasien_tob';

                        $postingJurnalTransaksi->keteranganlainnya = 'ppnpelayananpasien tgl. ' . $item->tgl;
                        //                    }

                        $postingJurnalTransaksi->statusenabled = 1;
                        $postingJurnalTransaksi->norecrelated = $item->norec_pp;
                        $postingJurnalTransaksi->jenis = $item->jenis;
                        $postingJurnalTransaksi->save();

                        $norec_pj = $postingJurnalTransaksi->norec;

                        //debet
                        $postingJurnalTransaksiD = new PostingJurnalTransaksiD;
                        $postingJurnalTransaksiD->norec = $postingJurnalTransaksiD->generateNewId();
                        $postingJurnalTransaksiD->kdprofile = $kdProfile;
                        $postingJurnalTransaksiD->nojurnal = 0;
                        $postingJurnalTransaksiD->noposting = $noPosting;
                        $postingJurnalTransaksiD->objectaccountfk = $debetId;
                        $postingJurnalTransaksiD->hargasatuand = $totalRp;
                        $postingJurnalTransaksiD->hargasatuank = 0;
                        $postingJurnalTransaksiD->statusenabled = 1;
                        $postingJurnalTransaksiD->norecrelated = $norec_pj;
                        $postingJurnalTransaksiD->save();

                        //kredit
                        $postingJurnalTransaksiD = new PostingJurnalTransaksiD;
                        $postingJurnalTransaksiD->norec = $postingJurnalTransaksiD->generateNewId();
                        $postingJurnalTransaksiD->kdprofile = $kdProfile;
                        $postingJurnalTransaksiD->nojurnal = 0;
                        $postingJurnalTransaksiD->noposting = $noPosting;
                        $postingJurnalTransaksiD->objectaccountfk = $kreditId;
                        $postingJurnalTransaksiD->hargasatuand = 0;
                        $postingJurnalTransaksiD->hargasatuank = $totalRp;
                        $postingJurnalTransaksiD->statusenabled = 1;
                        $postingJurnalTransaksiD->norecrelated = $norec_pj;
                        $postingJurnalTransaksiD->save();
                    } else {
                        $noJurnalIntern = Carbon::parse($item->tglpelayanan)->format('ym') . 'AJ' . Carbon::parse($item->tglpelayanan)->format('d') . '00009';
                        //                    $nojurnal = $this->getSequence('postingjurnaltransaksi_t_nojurnal_seq');

                        $postingJurnalTransaksi = new PostingJurnalTransaksi;
                        $norecHead = $postingJurnalTransaksi->generateNewId();
                        $postingJurnalTransaksi->norec = $norecHead;
                        $postingJurnalTransaksi->kdprofile = $kdProfile;
                        $postingJurnalTransaksi->noposting = $noPosting;
                        $postingJurnalTransaksi->nojurnal = 0;
                        $postingJurnalTransaksi->nojurnal_intern = $noJurnalIntern;
                        $postingJurnalTransaksi->objectjenisjurnalfk = 1;
                        $postingJurnalTransaksi->nobuktitransaksi = $noBuktiTransaksi;
                        $postingJurnalTransaksi->tglbuktitransaksi = date('Y-m-t', strtotime($item->tglpelayanan)); //$item->tglpelayanan;
                        $postingJurnalTransaksi->kdproduk = $item->prid;
                        $postingJurnalTransaksi->namaproduktransaksi = 'PPN Keluar obat Bebas ' . $item->namaproduk . ' a.n ' . $item->namapasien . ' (' . $item->noregistrasi . ')';
                        $postingJurnalTransaksi->deskripsiproduktransaksi = 'ppnpelayananpasien_tob';
                        $postingJurnalTransaksi->keteranganlainnya = 'Adjustment ppnpelayananpasien tgl. ' . $item->tgl;
                        //                    }

                        $postingJurnalTransaksi->statusenabled = 1;
                        $postingJurnalTransaksi->norecrelated = $item->norec_pp;
                        $postingJurnalTransaksi->jenis = $item->jenis;
                        $postingJurnalTransaksi->save();
                        $norec_pj = $postingJurnalTransaksi->norec;

                        //debet
                        $postingJurnalTransaksiD = new PostingJurnalTransaksiD;
                        $postingJurnalTransaksiD->norec = $postingJurnalTransaksiD->generateNewId();
                        $postingJurnalTransaksiD->kdprofile = $kdProfile;
                        $postingJurnalTransaksiD->nojurnal = 0;
                        $postingJurnalTransaksiD->noposting = $noPosting;
                        $postingJurnalTransaksiD->objectaccountfk = $debetId;
                        $postingJurnalTransaksiD->hargasatuand = $totalRp;
                        $postingJurnalTransaksiD->hargasatuank = 0;
                        $postingJurnalTransaksiD->statusenabled = 1;
                        $postingJurnalTransaksiD->norecrelated = $norec_pj;
                        $postingJurnalTransaksiD->save();

                        //kredit
                        $postingJurnalTransaksiD = new PostingJurnalTransaksiD;
                        $postingJurnalTransaksiD->norec = $postingJurnalTransaksiD->generateNewId();
                        $postingJurnalTransaksiD->kdprofile = $kdProfile;
                        $postingJurnalTransaksiD->nojurnal = 0;
                        $postingJurnalTransaksiD->noposting = $noPosting;
                        $postingJurnalTransaksiD->objectaccountfk = $kreditId;
                        $postingJurnalTransaksiD->hargasatuand = 0;
                        $postingJurnalTransaksiD->hargasatuank = $totalRp;
                        $postingJurnalTransaksiD->statusenabled = 1;
                        $postingJurnalTransaksiD->norecrelated = $norec_pj;
                        $postingJurnalTransaksiD->save();
                    }
                }
                //end Posting Obat Bebas

            }


            $transStatus = 'true';
        } catch (\Exception $e) {
            $transStatus = 'false';
        }
        $transMessage = 'Posting';
        $req = $request->all();
        if ($transStatus == 'true') {
            $transMessage = $transMessage . " Jurnal Berhasil";
            DB::commit();
            $result = array(
                "status" => 200,
                "result" => array(
                    "data" => $aingMacan33,
                    "count" => count($aingMacan33), //+count($aingMacan33)+count($aingMacan333)
                    "countppnbebanob" => count($aingMacanbebanOB),
                    "countppnjualob" => count($aingMacanppnOB),
                    //                "posting" => $cekSudahPosting,
                    // "data" => $aingMacan33 ,
                    "req" => $req,
                    "as" => '@epic',
                )
            );
        } else {
            $transMessage = $transMessage . " Jurnal Gagal!!";
            DB::rollBack();
            $result = array(
                "status" => 400,
                "result" => array(
                    "count" => count($aingMacan33),
                    "data" => $aingMacan33, //$noResep,
                    //                "coa" => $coacoa,
                    //                "dataCoa" => $dataCoa,
                    "as" => '@epic',
                )
            );
        }

        return $this->respond($result['result'], $result['status'], $transMessage);
    }
    public function PostingJurnal_penerimaan_piutang(Request $request)
    {

        $kdProfile = (int) $this->kdProfile;

        $dataLogin = $request->all();
        $aingMacan = [];
        $cekDataPelayanan = [];

        $tglAwal = $request['tglAwal'];
        $glAkhir = $request['tglAkhir'];
        // TODO : Jurnal Pengeluaran Kas

        DB::beginTransaction();
        try {
            $isAktif = $this->settingDataFixed('PostingJurnal_penerimaan_piutang_isaktif', $kdProfile);

            if ($isAktif == true) {


                // $cekDataPelayanan = [];
                // $cekDataPelayanan = DB::select(DB::raw("

                //         select sbm.norec
                //         from strukbuktipenerimaancarabayar_t as sbmc
                //         INNER JOIN strukbuktipenerimaan_t as sbm on sbm.norec=sbmc.nosbmfk
                //         left JOIN postingjurnaltransaksi_t as pjt on pjt.norecrelated=sbmc.norec
                //         where sbmc.kdprofile = $kdProfile and sbm.tglsbm BETWEEN :tglAwal and :tglAkhir and pjt.norec is null and sbm.statusenabled =true and (sbmc.totaldibayar is not null or sbmc.totaldibayar > 0)
                //         and sbm.keteranganlainnya in ('Pembayaran Tagihan','Pembayaran Non Layanan')
                //         limit 1
                //     "),
                //         array(
                //             'tglAwal' => $request['tglAwal'],
                //             'tglAkhir' => $request['tglAkhir'],
                //         )
                //     );


                $deptId = null;
                $ruId = null;
                $kelPasien = null;
                $detJenisPr = null;
                $raw = null;


                $aingMacan = DB::select(
                    DB::raw("
                            select sbmc.norec as norec_sbm,sbm.nosbm,to_char( sbm.tglsbm, 'YYYY-MM-DD') as tgl, sbm.tglsbm, php.noposting,rk.id as idRekanan,rk.namarekanan,
                            php.statusenabled,sbm.keteranganlainnya,sbm.totaldibayar,
                            coad.id as jurnaldid,coad.noaccount as jurnaldno,coad.namaaccount as jurnaldnm,
                            coak.id as jurnalkid,coak.noaccount as jurnalkno,coak.namaaccount as jurnalknm
                            from postinghutangpiutang_t as php
                            INNER JOIN strukpelayananpenjamin_t as spp on spp.norec=php.nostrukfk
                            INNER JOIN strukbuktipenerimaan_t as sbm on sbm.nostrukfk=spp.nostrukfk
                            INNER JOIN strukpelayanan_t as sp on sp.norec = spp.nostrukfk
                            INNER JOIN pasiendaftar_t as pd on pd.norec = sp.noregistrasifk
                            --INNER JOIN rekanan_m as rk on rk.id = pd.objectrekananfk
                            INNER JOIN rekanan_m as rk on rk.id = spp.kdrekananpenjamin
                            INNER JOIN strukposting_t as spo on spo.noposting=php.noposting
                            INNER JOIN strukbuktipenerimaancarabayar_t as sbmc on sbmc.nosbmfk=sbm.norec
                            inner join chartofaccountmapjurnal_t as cmap on
                            cmap.objectcarabayarfk=sbmc.objectcarabayarfk
                            --and cmap.objectrekananfk=pd.objectrekananfk
                            and cmap.objectrekananfk=spp.kdrekananpenjamin
                            INNER JOIN chartofaccount_m as coak on coak.id=cmap.objectcoakreditfk
                                INNER JOIN chartofaccount_m as coad on coad.id=cmap.objectcoadebetfk
                            left JOIN postingjurnaltransaksi_t as pjt on pjt.norecrelated=sbmc.norec
                            and pjt.deskripsiproduktransaksi = 'Penerimaan_kas_piutang'
                            where sbmc.kdprofile = $kdProfile
                            --spo.noposting='1610-23-PI000001'
                            and sbm.tglsbm between '$tglAwal' and '$glAkhir'
                            and cmap.objectjenistrxfk=15
                            and pjt.norec is null
                            and sbm.statusenabled =true

                    ")
                    // , $kelPasien  $raw
                    // array(
                    //     'tglAwal' => $request['tglAwal'],
                    //     'tglAkhir' => $request['tglAkhir']
                    // )
                );

                foreach ($aingMacan as $item) {
                    // $nocm = $item->nocm;
                    $nocolekting = $item->noposting;
                    $nama = $item->namarekanan;
                    $totalRp = $item->totaldibayar;

                    $noBuktiTransaksi = $item->nosbm;
                    $noPosting = '-';
                    $norec_sbmc = $item->norec_sbm;

                    $newPJT = new PostingJurnalTransaksi;
                    $norecHead = $newPJT->generateNewId();
                    $newPJT->norec = $norecHead;

                    $noJurnalIntern = Carbon::parse($item->tglsbm)->format('ym') . 'PN' . Carbon::parse($item->tglsbm)->format('d') . '00015';
                    $cekSudahPosting = PostingJurnal::where('norecrelated', $noJurnalIntern)->where('kdprofile', $kdProfile)->get();

                    if (count($cekSudahPosting) == 0) {
                        $newPJT->kdprofile = $kdProfile;
                        $newPJT->noposting = $noPosting;
                        $newPJT->nojurnal = 0; //$nojurnal;
                        $newPJT->nojurnal_intern = $noJurnalIntern;
                        $newPJT->objectjenisjurnalfk = 1;
                        $newPJT->nobuktitransaksi = $noBuktiTransaksi;
                        $newPJT->tglbuktitransaksi = $item->tglsbm; // $this->getDateTime()->format('Y-m-d H:i:s');
                        $newPJT->kdproduk = null;
                        $newPJT->namaproduktransaksi = 'Penerimaan piutang dari  ' . $nama . ' No Colekting : ' . $nocolekting . '';
                        $newPJT->deskripsiproduktransaksi = 'Penerimaan_kas_piutang';
                        $newPJT->keteranganlainnya = 'Penerimaan Piutang ' . $item->tgl;;
                        $newPJT->statusenabled = true;
                        $newPJT->norecrelated = $norec_sbmc;
                        $newPJT->save();


                        $debetId = $item->jurnaldid;
                        $kreditId = $item->jurnalkid;

                        //debet
                        $postingJurnalTransaksiD = new PostingJurnalTransaksiD;
                        $postingJurnalTransaksiD->norec = $postingJurnalTransaksiD->generateNewId();
                        $postingJurnalTransaksiD->kdprofile = $kdProfile;
                        $postingJurnalTransaksiD->nojurnal = 0; //$nojurnal;
                        $postingJurnalTransaksiD->noposting = $noPosting;
                        $postingJurnalTransaksiD->objectaccountfk = $debetId;
                        $postingJurnalTransaksiD->hargasatuand = $totalRp;
                        $postingJurnalTransaksiD->hargasatuank = 0;
                        $postingJurnalTransaksiD->statusenabled = true;
                        $postingJurnalTransaksiD->norecrelated = $norecHead;
                        $postingJurnalTransaksiD->save();
                        //kredit
                        $postingJurnalTransaksiD = new PostingJurnalTransaksiD;
                        $postingJurnalTransaksiD->norec = $postingJurnalTransaksiD->generateNewId();
                        $postingJurnalTransaksiD->kdprofile = $kdProfile;
                        $postingJurnalTransaksiD->nojurnal = 0; //$nojurnal;
                        $postingJurnalTransaksiD->noposting = $noPosting;
                        $postingJurnalTransaksiD->objectaccountfk = $kreditId;
                        $postingJurnalTransaksiD->hargasatuand = 0;
                        $postingJurnalTransaksiD->hargasatuank = $totalRp;
                        $postingJurnalTransaksiD->statusenabled = true;
                        $postingJurnalTransaksiD->norecrelated = $norecHead;
                        $postingJurnalTransaksiD->save();
                    }
                    // return $newPJT;
                }
            }
            $transStatus = 'true';
        } catch (\Exception $e) {
            $transStatus = 'false';
        }

        $transMessage = "Posting";

        if ($transStatus == 'true') {
            $transMessage = $transMessage . "";
            DB::commit();
            $result = array(
                "status" => 200,
                "result" => array(
                    "data2" => $aingMacan,
                    "count" => count($aingMacan),
                    "as" => '@epic',
                )
            );
        } else {
            $transMessage = $transMessage . " Gagal!!";
            DB::rollBack();
            $result = array(
                "status" => 400,
                "result" => array(
                    "message" => $e,
                    "data" => $cekDataPelayanan,
                    "as" => '@epic',
                )
            );
        }
        return $this->respond($result['result'], $result['status'], $transMessage);
    }
    public function PostingJurnal_pelayananpasien_t_NoRegistrasi(Request $request)
    {
        $kdProfile = (int) $this->kdProfile;
        $dataLogin = $request->all();
        $deptId = null;
        $ruId = null;
        $kelPasien = null;
        $detJenisPr = null;
        $raw = null;
        DB::beginTransaction();
        try {

            $aingMacan = [];
            $isAktif = $this->settingDataFixed('PostingJurnal_pelayananpasien_t_isaktif', $kdProfile);

            if ($isAktif == 'true') {
                $aingMacan = DB::select(
                    DB::raw("
                select * from (
                select
                pp.tglpelayanan,pr.namaproduk,pp.hargajual,pp.jumlah,pp.harganetto,
                pd.objectkelompokpasienlastfk,pp.produkfk as objectprodukfk,
                case when pp.strukresepfk is null then 'XObat' else 'Obat' end as Obat,pp.produkfk,pr.objectdetailjenisprodukfk,pr.objectkelompokprodukbpjsfk,
                (((case when pp.hargajual is null then 0 else pp.hargajual end)-case when pp.hargadiscount is null then 0 else pp.hargadiscount end)*pp.jumlah) + case when pp.jasa is null then 0 else pp.jasa end as harga,
                pr.id as prid,
                to_char(pp.tglpelayanan, 'YYYY-MM-DD') as tgl,
                pp.norec as norec_pp
                ,case when sr.norec is null then ru.objectdepartemenfk else ru2.objectdepartemenfk end as objectdepartemenfk,apd.objectruanganfk,pr.objectdetailjenisprodukfk,
                cmap.objectcoadebetfk,cmap.objectcoakreditfk,pd.noregistrasi,ps.namapasien
                from pelayananpasien_t as pp
                inner join antrianpasiendiperiksa_t as apd on apd.norec=pp.noregistrasifk
                inner join pasiendaftar_t as pd on pd.norec=apd.noregistrasifk
                inner join pasien_m as ps on ps.id = pd.nocmfk
                --inner join ruangan_m as ru3 on ru3.id=pd.objectruanganlastfk
                inner join ruangan_m as ru on ru.id=apd.objectruanganfk
                inner join produk_m as pr on pr.id=pp.produkfk
                left JOIN strukresep_t sr on sr.norec=pp.strukresepfk
                left JOIN ruangan_m ru2 on ru2.id=sr.ruanganfk
                inner join chartofaccountmapjurnal_t as cmap on
                    cmap.objectprodukfk=pp.produkfk and
                    cmap.objectruanganfk=case when sr.norec is null then ru.id else ru2.id end
                    --cmap.objectdepartemenfk=case when sr.norec is null then ru.objectdepartemenfk else ru2.objectdepartemenfk end
                left JOIN postingjurnaltransaksi_t as pjt on pjt.norecrelated=pp.norec and pjt.deskripsiproduktransaksi = 'pelayananpasien_t'
                where pp.kdprofile in ($kdProfile,0)
                --and pp.tglpelayanan between :tglAwal and :tglAkhir
                and pd.noregistrasi = :noregistrasi
                and pjt.norec is  null
                and pp.produkfk not in (402611)
                and cmap.objectjenistrxfk=1
                union all
                ---)bat kronis
                select
                pp.tglpelayanan,pr.namaproduk,pp.hargajual,pp.jumlah,pp.harganetto,
                pd.objectkelompokpasienlastfk,pp.produkfk as objectprodukfk,
                case when pp.strukresepfk is null then 'XObat' else 'Obat' end as Obat,pp.produkfk,pr.objectdetailjenisprodukfk,pr.objectkelompokprodukbpjsfk,
                (((case when pps.hargajual is null then 0 else pps.hargajual end)-case when pps.hargadiscount is null then 0 else pps.hargadiscount end)*pp.jumlah) + case when pps.jasa is null then 0 else pps.jasa end as harga,
                pr.id as prid,
                to_char(pp.tglpelayanan, 'YYYY-MM-DD') as tgl,
                pp.norec as norec_pp
                ,case when sr.norec is null then ru.objectdepartemenfk else ru2.objectdepartemenfk end as objectdepartemenfk,apd.objectruanganfk,pr.objectdetailjenisprodukfk,
                cmap.objectcoadebetfk,cmap.objectcoakreditfk,pd.noregistrasi,ps.namapasien
                from pelayananpasienobatkronis_t as pp
                INNER JOIN strukresep_t AS sr ON sr.norec=pp.strukresepfk
                inner join pelayananpasien_t as pps on pps.strukresepfk = sr.norec AND pps.produkfk = pp.produkfk
                inner join antrianpasiendiperiksa_t as apd on apd.norec=pp.noregistrasifk
                inner join pasiendaftar_t as pd on pd.norec=apd.noregistrasifk
                inner join pasien_m as ps on ps.id = pd.nocmfk
                --inner join ruangan_m as ru3 on ru3.id=pd.objectruanganlastfk
                inner join ruangan_m as ru on ru.id=apd.objectruanganfk
                inner join produk_m as pr on pr.id=pp.produkfk
                left JOIN ruangan_m ru2 on ru2.id=sr.ruanganfk
                inner join chartofaccountmapjurnal_t as cmap on
                cmap.objectprodukfk=pp.produkfk and
                cmap.objectruanganfk=case when sr.norec is null then ru.id else ru2.id end
                left JOIN postingjurnaltransaksi_t as pjt on pjt.norecrelated=pp.norec and pjt.deskripsiproduktransaksi = 'pelayananpasien_t'
                where pp.kdprofile in ($kdProfile,0)
                --and pp.tglpelayanan between :tglAwal and :tglAkhir
                and pd.noregistrasi = :noregistrasi
                and pjt.norec is  null
                and pp.produkfk not in (402611)
                and cmap.objectjenistrxfk=1
                limit 800) as x where x.tglpelayanan is not null
            "),
                    array(
                        // 'tglAwal' => $request['tglAwal'],
                        // 'tglAkhir' => $request['tglAkhir']
                        'noregistrasi' => $request['noregistrasi']
                    )
                );
                // return $aingMacan;

                foreach ($aingMacan as $item) {
                    $coaAdministrasi = 'a';
                    $coaKonsultasi = 'b';
                    $coaVisite = 'c';
                    $coaAkomodasi = 'd';
                    $coaTindakan = 'e';
                    $coaAlatCanggih = 'f';


                    $totalRp = $item->harga; //($item->hargajual) * ($item->jumlah);
                    $totalNettoRp = ($item->harganetto) * ($item->jumlah);

                    $debetId = $item->objectcoadebetfk;
                    $kreditId = $item->objectcoakreditfk;


                    $noBuktiTransaksi = $item->prid;

                    $noJurnalIntern = '';
                    $noPosting = '-';
                    $noJurnalIntern = Carbon::parse($item->tglpelayanan)->format('ym') . 'PN' . Carbon::parse($item->tglpelayanan)->format('d') . '00001';
                    $cekSudahPosting = PostingJurnal::where('norecrelated', $noJurnalIntern)->where('kdprofile', $kdProfile)->get();

                    if ($this->getCountArray($cekSudahPosting) == 0) {
                        $postingJurnalTransaksi = new PostingJurnalTransaksi;
                        $norecHead = $postingJurnalTransaksi->generateNewId();
                        $postingJurnalTransaksi->norec = $norecHead;
                        $postingJurnalTransaksi->kdprofile = $kdProfile;
                        $postingJurnalTransaksi->noposting = $noPosting;
                        $postingJurnalTransaksi->nojurnal = 0;
                        $postingJurnalTransaksi->nojurnal_intern = $noJurnalIntern;
                        $postingJurnalTransaksi->objectjenisjurnalfk = 1;
                        $postingJurnalTransaksi->nobuktitransaksi = $noBuktiTransaksi;
                        $postingJurnalTransaksi->tglbuktitransaksi = $item->tglpelayanan;
                        $postingJurnalTransaksi->kdproduk = $item->prid;
                        $postingJurnalTransaksi->namaproduktransaksi = $item->namaproduk . ', ' . $item->noregistrasi . ', ' . $item->namapasien; //. ' ' . $item->obat;
                        $postingJurnalTransaksi->deskripsiproduktransaksi = 'pelayananpasien_t';
                        $postingJurnalTransaksi->keteranganlainnya = 'Pendapatan tgl. ' . $item->tgl;
                        $postingJurnalTransaksi->statusenabled = 1;
                        $postingJurnalTransaksi->norecrelated = $item->norec_pp;
                        $postingJurnalTransaksi->save();

                        $norec_pj = $postingJurnalTransaksi->norec;

                        //debet
                        $postingJurnalTransaksiD = new PostingJurnalTransaksiD;
                        $postingJurnalTransaksiD->norec = $postingJurnalTransaksiD->generateNewId();
                        $postingJurnalTransaksiD->kdprofile = $kdProfile;
                        $postingJurnalTransaksiD->nojurnal = 0;
                        $postingJurnalTransaksiD->noposting = $noPosting;
                        $postingJurnalTransaksiD->objectaccountfk = $debetId;
                        $postingJurnalTransaksiD->hargasatuand = $totalRp;
                        $postingJurnalTransaksiD->hargasatuank = 0;
                        $postingJurnalTransaksiD->statusenabled = 1;
                        $postingJurnalTransaksiD->norecrelated = $norec_pj;
                        $postingJurnalTransaksiD->save();

                        //kredit
                        $postingJurnalTransaksiD = new PostingJurnalTransaksiD;
                        $postingJurnalTransaksiD->norec = $postingJurnalTransaksiD->generateNewId();
                        $postingJurnalTransaksiD->kdprofile = $kdProfile;
                        $postingJurnalTransaksiD->nojurnal = 0;
                        $postingJurnalTransaksiD->noposting = $noPosting;
                        $postingJurnalTransaksiD->objectaccountfk = $kreditId;
                        $postingJurnalTransaksiD->hargasatuand = 0;
                        $postingJurnalTransaksiD->hargasatuank = $totalRp;
                        $postingJurnalTransaksiD->statusenabled = 1;
                        $postingJurnalTransaksiD->norecrelated = $norec_pj;
                        $postingJurnalTransaksiD->save();
                    } else {
                        $noJurnalIntern = Carbon::parse($item->tglpelayanan)->format('ym') . 'AJ' . Carbon::parse($item->tglpelayanan)->format('d') . '00001';
                        $postingJurnalTransaksi = new PostingJurnalTransaksi;
                        $norecHead = $postingJurnalTransaksi->generateNewId();
                        $postingJurnalTransaksi->norec = $norecHead;
                        $postingJurnalTransaksi->kdprofile = $kdProfile;
                        $postingJurnalTransaksi->noposting = $noPosting;
                        $postingJurnalTransaksi->nojurnal = 0;
                        $postingJurnalTransaksi->nojurnal_intern = $noJurnalIntern;
                        $postingJurnalTransaksi->objectjenisjurnalfk = 1;
                        $postingJurnalTransaksi->nobuktitransaksi = $noBuktiTransaksi;
                        $postingJurnalTransaksi->tglbuktitransaksi = date('Y-m-t', strtotime($item->tglpelayanan)); //$item->tglpelayanan;
                        $postingJurnalTransaksi->kdproduk = $item->prid;
                        $postingJurnalTransaksi->namaproduktransaksi = $item->namaproduk;
                        $postingJurnalTransaksi->deskripsiproduktransaksi = 'pelayananpasien_t';
                        $postingJurnalTransaksi->keteranganlainnya = 'Adjustment Pendapatan tgl. ' . $item->tgl;
                        $postingJurnalTransaksi->statusenabled = true;
                        $postingJurnalTransaksi->norecrelated = $item->norec_pp;
                        $postingJurnalTransaksi->save();


                        $norec_pj = $postingJurnalTransaksi->norec;

                        //debet
                        $postingJurnalTransaksiD = new PostingJurnalTransaksiD;
                        $postingJurnalTransaksiD->norec = $postingJurnalTransaksiD->generateNewId();
                        $postingJurnalTransaksiD->kdprofile = $kdProfile;
                        $postingJurnalTransaksiD->nojurnal = 0;
                        $postingJurnalTransaksiD->noposting = $noPosting;
                        $postingJurnalTransaksiD->objectaccountfk = $debetId;
                        $postingJurnalTransaksiD->hargasatuand = $totalRp;
                        $postingJurnalTransaksiD->hargasatuank = 0;
                        $postingJurnalTransaksiD->statusenabled = 1;
                        $postingJurnalTransaksiD->norecrelated = $norec_pj;
                        $postingJurnalTransaksiD->save();

                        //kredit
                        $postingJurnalTransaksiD = new PostingJurnalTransaksiD;
                        $postingJurnalTransaksiD->norec = $postingJurnalTransaksiD->generateNewId();
                        $postingJurnalTransaksiD->kdprofile = $kdProfile;
                        $postingJurnalTransaksiD->nojurnal = 0;
                        $postingJurnalTransaksiD->noposting = $noPosting;
                        $postingJurnalTransaksiD->objectaccountfk = $kreditId;
                        $postingJurnalTransaksiD->hargasatuand = 0;
                        $postingJurnalTransaksiD->hargasatuank = $totalRp;
                        $postingJurnalTransaksiD->statusenabled = 1;
                        $postingJurnalTransaksiD->norecrelated = $norec_pj;
                        $postingJurnalTransaksiD->save();
                    }
                }
            }

            $aingMacanbeban = [];
            $isAktif = $this->settingDataFixed('PostingJurnal_bebanPelayanan_isaktif', $kdProfile);
            if ($isAktif == 1) {
                $aingMacanbeban = DB::select(
                    DB::raw("
            select * from (
                select
                pp.tglpelayanan,pr.namaproduk,pp.hargajual,ROUND(CAST((pp.hargajual / 111) * 100 AS numeric), 2) AS hargasatuan,pp.jumlah,pp.harganetto,
                pd.objectkelompokpasienlastfk,pp.produkfk as objectprodukfk,
                case when pp.strukresepfk is null then 'XObat' else 'Obat' end as Obat,pp.produkfk,pr.objectdetailjenisprodukfk,pr.objectkelompokprodukbpjsfk,
                (pp.harganetto*pp.jumlah) as harga,
                pr.id as prid,
                to_char(pp.tglpelayanan, 'YYYY-MM-DD') as tgl,
                pp.norec as norec_pp
                ,case when sr.norec is null then ru.objectdepartemenfk else ru2.objectdepartemenfk end as objectdepartemenfk,apd.objectruanganfk,pr.objectdetailjenisprodukfk,
                cmap.objectcoadebetfk,cmap.objectcoakreditfk,pd.noregistrasi,ps.namapasien
                from pelayananpasien_t as pp
                inner join antrianpasiendiperiksa_t as apd on apd.norec=pp.noregistrasifk
                inner join pasiendaftar_t as pd on pd.norec=apd.noregistrasifk
                inner join pasien_m as ps on ps.id = pd.nocmfk
                --inner join ruangan_m as ru3 on ru3.id=pd.objectruanganlastfk
                inner join ruangan_m as ru on ru.id=apd.objectruanganfk
                inner join produk_m as pr on pr.id=pp.produkfk
                left JOIN strukresep_t sr on sr.norec=pp.strukresepfk
                left JOIN ruangan_m ru2 on ru2.id=sr.ruanganfk
                inner join chartofaccountmapjurnal_t as cmap on
                    cmap.objectprodukfk=pp.produkfk --and
                    --cmap.objectruanganfk=case when sr.norec is null then ru.id else ru2.id end
                left JOIN postingjurnaltransaksi_t as pjt on pjt.norecrelated=pp.norec and pjt.deskripsiproduktransaksi = 'bebanpelayananpasien_t'
                where pp.kdprofile in ($kdProfile,0)
                --and pp.tglpelayanan between :tglAwal and :tglAkhir
                and pd.noregistrasi = :noregistrasi
                and pjt.norec is  null
                and pp.produkfk not in (402611)
                and cmap.objectjenistrxfk=5
                union all
                ---)bat kronis
                select
                pp.tglpelayanan,pr.namaproduk,pp.hargajual,ROUND(CAST((pp.hargajual / 111) * 100 AS numeric), 2) AS hargasatuan,pp.jumlah,pp.harganetto,
                pd.objectkelompokpasienlastfk,pp.produkfk as objectprodukfk,
                case when pp.strukresepfk is null then 'XObat' else 'Obat' end as Obat,pp.produkfk,pr.objectdetailjenisprodukfk,pr.objectkelompokprodukbpjsfk,
                (pp.harganetto*pp.jumlah) as harga,
                pr.id as prid,
                to_char(pp.tglpelayanan, 'YYYY-MM-DD') as tgl,
                pp.norec as norec_pp
                ,case when sr.norec is null then ru.objectdepartemenfk else ru2.objectdepartemenfk end as objectdepartemenfk,apd.objectruanganfk,pr.objectdetailjenisprodukfk,
                cmap.objectcoadebetfk,cmap.objectcoakreditfk,pd.noregistrasi,ps.namapasien
                from pelayananpasienobatkronis_t as pp
                INNER JOIN strukresep_t AS sr ON sr.norec=pp.strukresepfk
                inner join pelayananpasien_t as pps on pps.strukresepfk = sr.norec AND pps.produkfk = pp.produkfk
                inner join antrianpasiendiperiksa_t as apd on apd.norec=pp.noregistrasifk
                inner join pasiendaftar_t as pd on pd.norec=apd.noregistrasifk
                inner join pasien_m as ps on ps.id = pd.nocmfk
                --inner join ruangan_m as ru3 on ru3.id=pd.objectruanganlastfk
                inner join ruangan_m as ru on ru.id=apd.objectruanganfk
                inner join produk_m as pr on pr.id=pp.produkfk
                left JOIN ruangan_m ru2 on ru2.id=sr.ruanganfk
                inner join chartofaccountmapjurnal_t as cmap on
                    cmap.objectprodukfk=pp.produkfk --and
                    --cmap.objectruanganfk=case when sr.norec is null then ru.id else ru2.id end
                left JOIN postingjurnaltransaksi_t as pjt on pjt.norecrelated=pp.norec and pjt.deskripsiproduktransaksi = 'bebanpelayananpasien_t'
                where pp.kdprofile in ($kdProfile,0)
                --and pp.tglpelayanan between :tglAwal and :tglAkhir
                and pd.noregistrasi = :noregistrasi
                and pjt.norec is  null
                and pp.produkfk not in (402611)
                and cmap.objectjenistrxfk=5

                limit 500) as x where x.tglpelayanan is not null
            "),
                    array(
                        // 'tglAwal' => $request['tglAwal'],
                        // 'tglAkhir' => $request['tglAkhir']
                        'noregistrasi' => $request['noregistrasi']
                    )
                );
                // return $aingMacan;

                foreach ($aingMacanbeban as $item) {
                    $coaAdministrasi = 'a';
                    $coaKonsultasi = 'b';
                    $coaVisite = 'c';
                    $coaAkomodasi = 'd';
                    $coaTindakan = 'e';
                    $coaAlatCanggih = 'f';


                    $totalRp = round(($item->hargasatuan) * ($item->jumlah), 2); //($item->hargajual) * ($item->jumlah);
                    $totalNettoRp = ($item->harganetto) * ($item->jumlah);

                    $debetId = $item->objectcoadebetfk;
                    $kreditId = $item->objectcoakreditfk;


                    $noBuktiTransaksi = $item->prid;

                    $noJurnalIntern = '';
                    $noPosting = '-';
                    $noJurnalIntern = Carbon::parse($item->tglpelayanan)->format('ym') . 'PN' . Carbon::parse($item->tglpelayanan)->format('d') . '00005';
                    $cekSudahPosting = PostingJurnal::where('norecrelated', $noJurnalIntern)->where('kdprofile', $kdProfile)->get();

                    if ($this->getCountArray($cekSudahPosting) == 0) {
                        $postingJurnalTransaksi = new PostingJurnalTransaksi;
                        $norecHead = $postingJurnalTransaksi->generateNewId();
                        $postingJurnalTransaksi->norec = $norecHead;
                        $postingJurnalTransaksi->kdprofile = $kdProfile;
                        $postingJurnalTransaksi->noposting = $noPosting;
                        $postingJurnalTransaksi->nojurnal = 0;
                        $postingJurnalTransaksi->nojurnal_intern = $noJurnalIntern;
                        $postingJurnalTransaksi->objectjenisjurnalfk = 1;
                        $postingJurnalTransaksi->nobuktitransaksi = $noBuktiTransaksi;
                        $postingJurnalTransaksi->tglbuktitransaksi = $item->tglpelayanan;
                        $postingJurnalTransaksi->kdproduk = $item->prid;
                        $postingJurnalTransaksi->namaproduktransaksi = 'persediaan ' . $item->namaproduk . ', ' . $item->noregistrasi . ', ' . $item->namapasien; //. ' ' . $item->obat;
                        $postingJurnalTransaksi->deskripsiproduktransaksi = 'bebanpelayananpasien_t';
                        $postingJurnalTransaksi->keteranganlainnya = 'bebanpelayananpasien tgl. ' . $item->tgl;
                        $postingJurnalTransaksi->statusenabled = 1;
                        $postingJurnalTransaksi->norecrelated = $item->norec_pp;
                        $postingJurnalTransaksi->save();

                        $norec_pj = $postingJurnalTransaksi->norec;

                        //debet
                        $postingJurnalTransaksiD = new PostingJurnalTransaksiD;
                        $postingJurnalTransaksiD->norec = $postingJurnalTransaksiD->generateNewId();
                        $postingJurnalTransaksiD->kdprofile = $kdProfile;
                        $postingJurnalTransaksiD->nojurnal = 0;
                        $postingJurnalTransaksiD->noposting = $noPosting;
                        $postingJurnalTransaksiD->objectaccountfk = $debetId;
                        $postingJurnalTransaksiD->hargasatuand = $totalRp;
                        $postingJurnalTransaksiD->hargasatuank = 0;
                        $postingJurnalTransaksiD->statusenabled = 1;
                        $postingJurnalTransaksiD->norecrelated = $norec_pj;
                        $postingJurnalTransaksiD->save();

                        //kredit
                        $postingJurnalTransaksiD = new PostingJurnalTransaksiD;
                        $postingJurnalTransaksiD->norec = $postingJurnalTransaksiD->generateNewId();
                        $postingJurnalTransaksiD->kdprofile = $kdProfile;
                        $postingJurnalTransaksiD->nojurnal = 0;
                        $postingJurnalTransaksiD->noposting = $noPosting;
                        $postingJurnalTransaksiD->objectaccountfk = $kreditId;
                        $postingJurnalTransaksiD->hargasatuand = 0;
                        $postingJurnalTransaksiD->hargasatuank = $totalRp;
                        $postingJurnalTransaksiD->statusenabled = 1;
                        $postingJurnalTransaksiD->norecrelated = $norec_pj;
                        $postingJurnalTransaksiD->save();
                    } else {
                        $noJurnalIntern = Carbon::parse($item->tglpelayanan)->format('ym') . 'AJ' . Carbon::parse($item->tglpelayanan)->format('d') . '00005';
                        $postingJurnalTransaksi = new PostingJurnalTransaksi;
                        $norecHead = $postingJurnalTransaksi->generateNewId();
                        $postingJurnalTransaksi->norec = $norecHead;
                        $postingJurnalTransaksi->kdprofile = $kdProfile;
                        $postingJurnalTransaksi->noposting = $noPosting;
                        $postingJurnalTransaksi->nojurnal = 0;
                        $postingJurnalTransaksi->nojurnal_intern = $noJurnalIntern;
                        $postingJurnalTransaksi->objectjenisjurnalfk = 1;
                        $postingJurnalTransaksi->nobuktitransaksi = $noBuktiTransaksi;
                        $postingJurnalTransaksi->tglbuktitransaksi = date('Y-m-t', strtotime($item->tglpelayanan)); //$item->tglpelayanan;
                        $postingJurnalTransaksi->kdproduk = $item->prid;
                        $postingJurnalTransaksi->namaproduktransaksi = 'persediaan ' . $item->namaproduk . ', ' . $item->noregistrasi . ', ' . $item->namapasien;
                        $postingJurnalTransaksi->deskripsiproduktransaksi = 'bebanpelayananpasien_t';
                        $postingJurnalTransaksi->keteranganlainnya = 'Adjustment bebanpelayananpasien tgl. ' . $item->tgl;
                        $postingJurnalTransaksi->statusenabled = true;
                        $postingJurnalTransaksi->norecrelated = $item->norec_pp;
                        $postingJurnalTransaksi->save();


                        $norec_pj = $postingJurnalTransaksi->norec;

                        //debet
                        $postingJurnalTransaksiD = new PostingJurnalTransaksiD;
                        $postingJurnalTransaksiD->norec = $postingJurnalTransaksiD->generateNewId();
                        $postingJurnalTransaksiD->kdprofile = $kdProfile;
                        $postingJurnalTransaksiD->nojurnal = 0;
                        $postingJurnalTransaksiD->noposting = $noPosting;
                        $postingJurnalTransaksiD->objectaccountfk = $debetId;
                        $postingJurnalTransaksiD->hargasatuand = $totalRp;
                        $postingJurnalTransaksiD->hargasatuank = 0;
                        $postingJurnalTransaksiD->statusenabled = 1;
                        $postingJurnalTransaksiD->norecrelated = $norec_pj;
                        $postingJurnalTransaksiD->save();

                        //kredit
                        $postingJurnalTransaksiD = new PostingJurnalTransaksiD;
                        $postingJurnalTransaksiD->norec = $postingJurnalTransaksiD->generateNewId();
                        $postingJurnalTransaksiD->kdprofile = $kdProfile;
                        $postingJurnalTransaksiD->nojurnal = 0;
                        $postingJurnalTransaksiD->noposting = $noPosting;
                        $postingJurnalTransaksiD->objectaccountfk = $kreditId;
                        $postingJurnalTransaksiD->hargasatuand = 0;
                        $postingJurnalTransaksiD->hargasatuank = $totalRp;
                        $postingJurnalTransaksiD->statusenabled = 1;
                        $postingJurnalTransaksiD->norecrelated = $norec_pj;
                        $postingJurnalTransaksiD->save();
                    }
                }
            }


            $aingMacanppn = [];
            $isAktif = $this->settingDataFixed('PostingJurnal_PpnJualPelayananPasien_isaktif', $kdProfile);
            if ($isAktif == 1) {

                //        ini_set('max_execution_time', 1000); //6 minutes
                $persenhitungppn = $this->settingDataFixed('hitungPPN', $kdProfile);
                $persenppn = $this->settingDataFixed('persenPPN', $kdProfile);
                // TODO : Jurnal Pelayanan

                $aingMacanppn = DB::select(
                    DB::raw("
                        select * from (
                            select
                            pp.tglpelayanan,pr.namaproduk,pp.hargajual,pp.jumlah,pp.harganetto,pp.hargadpp,
                            pd.objectkelompokpasienlastfk,pp.produkfk as objectprodukfk,
                            case when pp.strukresepfk is null then 'XObat' else 'Obat' end as Obat,pp.produkfk,pr.objectdetailjenisprodukfk,pr.objectkelompokprodukbpjsfk,
                            (((case when pp.hargadpp is null then 0 else pp.hargadpp end) * $persenppn)/100 * pp.jumlah ) as harga,
                            pr.id as prid,
                            to_char(pp.tglpelayanan, 'YYYY-MM-DD') as tgl,
                            pp.norec as norec_pp
                            ,case when sr.norec is null then ru.objectdepartemenfk else ru2.objectdepartemenfk end as objectdepartemenfk,apd.objectruanganfk,pr.objectdetailjenisprodukfk,
                            cmap.objectcoadebetfk,cmap.objectcoakreditfk,pd.noregistrasi,ps.namapasien
                            from pelayananpasien_t as pp
                            inner join antrianpasiendiperiksa_t as apd on apd.norec=pp.noregistrasifk
                            inner join pasiendaftar_t as pd on pd.norec=apd.noregistrasifk
                            inner join pasien_m as ps on ps.id = pd.nocmfk
                            --inner join ruangan_m as ru3 on ru3.id=pd.objectruanganlastfk
                            inner join ruangan_m as ru on ru.id=apd.objectruanganfk
                            inner join produk_m as pr on pr.id=pp.produkfk
                            left JOIN strukresep_t sr on sr.norec=pp.strukresepfk
                            left JOIN ruangan_m ru2 on ru2.id=sr.ruanganfk
                            inner join chartofaccountmapjurnal_t as cmap on
                                cmap.objectprodukfk=pp.produkfk and
                                -cmap.objectruanganfk=case when sr.norec is null then ru.id else ru2.id end
                            left JOIN postingjurnaltransaksi_t as pjt on pjt.norecrelated=pp.norec and pjt.deskripsiproduktransaksi = 'ppnjual'
                            where pp.kdprofile in ($kdProfile,0)
                            --and pp.tglpelayanan between :tglAwal and :tglAkhir
                            and pd.noregistrasi = :noregistrasi
                            and pjt.norec is  null
                            and pp.produkfk not in (402611)
                            and cmap.objectjenistrxfk=9
                            union all
                            ---)bat kronis
                            select
                            pp.tglpelayanan,pr.namaproduk,pp.hargajual,pp.jumlah,pp.harganetto,pps.hargadpp,
                            pd.objectkelompokpasienlastfk,pp.produkfk as objectprodukfk,
                            case when pp.strukresepfk is null then 'XObat' else 'Obat' end as Obat,pp.produkfk,pr.objectdetailjenisprodukfk,pr.objectkelompokprodukbpjsfk,
                            (((case when pps.hargadpp is null then 0 else pps.hargadpp end) * $persenppn)/100 * pp.jumlah ) as harga,
                            pr.id as prid,
                            to_char(pp.tglpelayanan, 'YYYY-MM-DD') as tgl,
                            pp.norec as norec_pp
                            ,case when sr.norec is null then ru.objectdepartemenfk else ru2.objectdepartemenfk end as objectdepartemenfk,apd.objectruanganfk,pr.objectdetailjenisprodukfk,
                            cmap.objectcoadebetfk,cmap.objectcoakreditfk,pd.noregistrasi,ps.namapasien
                            from pelayananpasienobatkronis_t as pp
                            INNER JOIN strukresep_t AS sr ON sr.norec=pp.strukresepfk
                            inner join pelayananpasien_t as pps on pps.strukresepfk = sr.norec AND pps.produkfk = pp.produkfk
                            inner join antrianpasiendiperiksa_t as apd on apd.norec=pp.noregistrasifk
                            inner join pasiendaftar_t as pd on pd.norec=apd.noregistrasifk
                            inner join pasien_m as ps on ps.id = pd.nocmfk
                            --inner join ruangan_m as ru3 on ru3.id=pd.objectruanganlastfk
                            inner join ruangan_m as ru on ru.id=apd.objectruanganfk
                            inner join produk_m as pr on pr.id=pp.produkfk
                            left JOIN ruangan_m ru2 on ru2.id=sr.ruanganfk
                            inner join chartofaccountmapjurnal_t as cmap on
                                cmap.objectprodukfk=pp.produkfk and
                                cmap.objectruanganfk=case when sr.norec is null then ru.id else ru2.id end
                            left JOIN postingjurnaltransaksi_t as pjt on pjt.norecrelated=pp.norec and pjt.deskripsiproduktransaksi = 'ppnjual'
                            where pp.kdprofile in ($kdProfile,0)
                            --and pp.tglpelayanan between :tglAwal and :tglAkhir
                            and pd.noregistrasi = :noregistrasi
                            and pjt.norec is  null
                            and pp.produkfk not in (402611)
                            and cmap.objectjenistrxfk=9

                    limit 500) as x where x.harga>0
                "),
                    array(
                        // 'tglAwal' => $request['tglAwal'],
                        // 'tglAkhir' => $request['tglAkhir']
                        'noregistrasi' => $request['noregistrasi']
                    )
                );
                // return $aingMacan;

                foreach ($aingMacanppn as $item) {
                    $coaAdministrasi = 'a';
                    $coaKonsultasi = 'b';
                    $coaVisite = 'c';
                    $coaAkomodasi = 'd';
                    $coaTindakan = 'e';
                    $coaAlatCanggih = 'f';


                    $totalRp = $item->harga; //($item->hargajual) * ($item->jumlah);
                    $totalNettoRp = ($item->harganetto) * ($item->jumlah);

                    $debetId = $item->objectcoadebetfk;
                    $kreditId = $item->objectcoakreditfk;


                    $noBuktiTransaksi = $item->prid;

                    $noJurnalIntern = '';
                    $noPosting = '-';
                    $noJurnalIntern = Carbon::parse($item->tglpelayanan)->format('ym') . 'PN' . Carbon::parse($item->tglpelayanan)->format('d') . '00009';
                    $cekSudahPosting = PostingJurnal::where('norecrelated', $noJurnalIntern)->where('kdprofile', $kdProfile)->get();

                    if ($this->getCountArray($cekSudahPosting) == 0) {
                        $postingJurnalTransaksi = new PostingJurnalTransaksi;
                        $norecHead = $postingJurnalTransaksi->generateNewId();
                        $postingJurnalTransaksi->norec = $norecHead;
                        $postingJurnalTransaksi->kdprofile = $kdProfile;
                        $postingJurnalTransaksi->noposting = $noPosting;
                        $postingJurnalTransaksi->nojurnal = 0;
                        $postingJurnalTransaksi->nojurnal_intern = $noJurnalIntern;
                        $postingJurnalTransaksi->objectjenisjurnalfk = 1;
                        $postingJurnalTransaksi->nobuktitransaksi = $noBuktiTransaksi;
                        $postingJurnalTransaksi->tglbuktitransaksi = $item->tglpelayanan;
                        $postingJurnalTransaksi->kdproduk = $item->prid;
                        $postingJurnalTransaksi->namaproduktransaksi = 'persediaan ppnjual ' . $item->namaproduk . ', ' . $item->noregistrasi . ', ' . $item->namapasien; //. ' ' . $item->obat;
                        $postingJurnalTransaksi->deskripsiproduktransaksi = 'ppnjual';
                        $postingJurnalTransaksi->keteranganlainnya = 'ppnjual tgl. ' . $item->tgl;
                        $postingJurnalTransaksi->statusenabled = 1;
                        $postingJurnalTransaksi->norecrelated = $item->norec_pp;
                        $postingJurnalTransaksi->save();

                        $norec_pj = $postingJurnalTransaksi->norec;

                        //debet
                        $postingJurnalTransaksiD = new PostingJurnalTransaksiD;
                        $postingJurnalTransaksiD->norec = $postingJurnalTransaksiD->generateNewId();
                        $postingJurnalTransaksiD->kdprofile = $kdProfile;
                        $postingJurnalTransaksiD->nojurnal = 0;
                        $postingJurnalTransaksiD->noposting = $noPosting;
                        $postingJurnalTransaksiD->objectaccountfk = $debetId;
                        $postingJurnalTransaksiD->hargasatuand = $totalRp;
                        $postingJurnalTransaksiD->hargasatuank = 0;
                        $postingJurnalTransaksiD->statusenabled = 1;
                        $postingJurnalTransaksiD->norecrelated = $norec_pj;
                        $postingJurnalTransaksiD->save();

                        //kredit
                        $postingJurnalTransaksiD = new PostingJurnalTransaksiD;
                        $postingJurnalTransaksiD->norec = $postingJurnalTransaksiD->generateNewId();
                        $postingJurnalTransaksiD->kdprofile = $kdProfile;
                        $postingJurnalTransaksiD->nojurnal = 0;
                        $postingJurnalTransaksiD->noposting = $noPosting;
                        $postingJurnalTransaksiD->objectaccountfk = $kreditId;
                        $postingJurnalTransaksiD->hargasatuand = 0;
                        $postingJurnalTransaksiD->hargasatuank = $totalRp;
                        $postingJurnalTransaksiD->statusenabled = 1;
                        $postingJurnalTransaksiD->norecrelated = $norec_pj;
                        $postingJurnalTransaksiD->save();
                    } else {
                        $noJurnalIntern = Carbon::parse($item->tglpelayanan)->format('ym') . 'AJ' . Carbon::parse($item->tglpelayanan)->format('d') . '00009';
                        $postingJurnalTransaksi = new PostingJurnalTransaksi;
                        $norecHead = $postingJurnalTransaksi->generateNewId();
                        $postingJurnalTransaksi->norec = $norecHead;
                        $postingJurnalTransaksi->kdprofile = $kdProfile;
                        $postingJurnalTransaksi->noposting = $noPosting;
                        $postingJurnalTransaksi->nojurnal = 0;
                        $postingJurnalTransaksi->nojurnal_intern = $noJurnalIntern;
                        $postingJurnalTransaksi->objectjenisjurnalfk = 1;
                        $postingJurnalTransaksi->nobuktitransaksi = $noBuktiTransaksi;
                        $postingJurnalTransaksi->tglbuktitransaksi = date('Y-m-t', strtotime($item->tglpelayanan)); //$item->tglpelayanan;
                        $postingJurnalTransaksi->kdproduk = $item->prid;
                        $postingJurnalTransaksi->namaproduktransaksi = 'persediaan ppnjual ' . $item->namaproduk . ', ' . $item->noregistrasi . ', ' . $item->namapasien;
                        $postingJurnalTransaksi->deskripsiproduktransaksi = 'ppnjual';
                        $postingJurnalTransaksi->keteranganlainnya = 'Adjustment ppnjual tgl. ' . $item->tgl;
                        $postingJurnalTransaksi->statusenabled = true;
                        $postingJurnalTransaksi->norecrelated = $item->norec_pp;
                        $postingJurnalTransaksi->save();


                        $norec_pj = $postingJurnalTransaksi->norec;

                        //debet
                        $postingJurnalTransaksiD = new PostingJurnalTransaksiD;
                        $postingJurnalTransaksiD->norec = $postingJurnalTransaksiD->generateNewId();
                        $postingJurnalTransaksiD->kdprofile = $kdProfile;
                        $postingJurnalTransaksiD->nojurnal = 0;
                        $postingJurnalTransaksiD->noposting = $noPosting;
                        $postingJurnalTransaksiD->objectaccountfk = $debetId;
                        $postingJurnalTransaksiD->hargasatuand = $totalRp;
                        $postingJurnalTransaksiD->hargasatuank = 0;
                        $postingJurnalTransaksiD->statusenabled = 1;
                        $postingJurnalTransaksiD->norecrelated = $norec_pj;
                        $postingJurnalTransaksiD->save();

                        //kredit
                        $postingJurnalTransaksiD = new PostingJurnalTransaksiD;
                        $postingJurnalTransaksiD->norec = $postingJurnalTransaksiD->generateNewId();
                        $postingJurnalTransaksiD->kdprofile = $kdProfile;
                        $postingJurnalTransaksiD->nojurnal = 0;
                        $postingJurnalTransaksiD->noposting = $noPosting;
                        $postingJurnalTransaksiD->objectaccountfk = $kreditId;
                        $postingJurnalTransaksiD->hargasatuand = 0;
                        $postingJurnalTransaksiD->hargasatuank = $totalRp;
                        $postingJurnalTransaksiD->statusenabled = 1;
                        $postingJurnalTransaksiD->norecrelated = $norec_pj;
                        $postingJurnalTransaksiD->save();
                    }
                }
                // }
                // return $isAktif;


            }

            $transStatus = 'true';
        } catch (\Exception $e) {
            $transStatus = 'false';
        }
        $transMessage = 'Posting';
        $req = $request->all();
        if ($transStatus == 'true') {
            $transMessage = $transMessage . " Jurnal Berhasil";
            DB::commit();
            $result = array(
                "status" => 200,
                "result" => array(
                    // "data" => $aingMacan,
                    "count" => count($aingMacan),
                    "countbeban" => count($aingMacanbeban),
                    "countppn" => count($aingMacanppn),
                    //                "posting" => $cekSudahPosting,
                    // "data" => $aingMacan33 ,
                    "req" => $req,
                    "as" => '@epic',
                )
            );
        } else {
            $transMessage = $transMessage . " Jurnal Gagal!!";
            DB::rollBack();
            $result = array(
                "status" => 400,
                "result" => array(
                    "count" => count($aingMacan),
                    "data" => $aingMacan, //$noResep,
                    //                "coa" => $coacoa,
                    //                "dataCoa" => $dataCoa,
                    "as" => '@epic',
                )
            );
        }

        return $this->respond($result['result'], $result['status'], $transMessage);
    }
    public function PostingJurnal_Pemakaiandeposit(Request $request)
    {
        $kdProfile = (int) $this->kdProfile;


        DB::beginTransaction();
        try {
            $cekDataPelayanan = [];
            $aingMacan = [];
            // TODO : Jurnal Pelayanan
            $isAktif = $this->settingDataFixed('PostingJurnal_pakaideposit_isaktif', $kdProfile);
            if ($isAktif == 1) {

                $aingMacan = DB::select(
                    DB::raw("
                        SELECT pd.norec,pd.noregistrasi, sum(sbm.totaldibayar) as totaldeposit from strukpelayanan_t as sp
                        INNER JOIN strukbuktipenerimaan_t as sbm on sbm.nostrukfk=sp.norec
                        INNER JOIN pasiendaftar_t as pd on pd.norec=sp.noregistrasifk
                        LEFT JOIN postingjurnaltransaksi_t AS pjt ON pjt.norecrelated = pd.norec
                        AND pjt.deskripsiproduktransaksi in ('pelayanan_deposit','pengembalian_deposit')
                             --AND pjt.deskripsiproduktransaksi = 'pelayanan_deposit'
                        where  pd.kdprofile IN ( $kdProfile,0 )
                        and sbm.keteranganlainnya='Pembayaran Deposit'
                        AND  pd.tglpulang BETWEEN :tglAwal and :tglAkhir
                        --and pd.noregistrasi = :noregistrasi
                        and pjt.norec is null
                        --sp.noregistrasifk='58694950-4539-11ee-9ad4-932e70e9'
                        GROUP BY  pd.norec,pd.noregistrasi limit 200

                    "),
                    array(
                        'tglAwal' => $request['tglAwal'],
                        'tglAkhir' => $request['tglAkhir']
                        // 'noregistrasi' => $request['noregistrasi']
                    )
                );



                $aingMacantagihan = DB::select(
                    DB::raw("
                            select x.norec_sp, x.noregistrasi,x.namapasien,x.objectkelompokpasienlastfk,x.tgl,x.tglstruk, sum(x.harga) as harga from (
                            SELECT pd.norec as norec_sp, pd.noregistrasi,ps.namapasien,pd.objectkelompokpasienlastfk, to_char(sp.tglstruk, 'YYYY-MM-DD') as tgl,
                            sp.tglstruk as tglstruk,
                            ((( CASE WHEN pp.hargajual IS NULL THEN 0 ELSE pp.hargajual END ) -
                            CASE WHEN pp.hargadiscount IS NULL THEN
                                    0 ELSE pp.hargadiscount END ) * pp.jumlah ) +
                            CASE	WHEN pp.jasa IS NULL THEN 0 ELSE pp.jasa
                            END AS harga
                        FROM
                            pelayananpasien_t AS pp
                            INNER JOIN antrianpasiendiperiksa_t AS apd ON apd.norec = pp.noregistrasifk
                            INNER JOIN pasiendaftar_t AS pd ON pd.norec = apd.noregistrasifk
                            INNER JOIN pasien_m AS ps ON ps.id = pd.nocmfk
                            INNER JOIN kelompokpasien_m as kp on kp.id=pd.objectkelompokpasienlastfk
                            INNER JOIN ruangan_m AS ru ON ru.id = apd.objectruanganfk
                            INNER JOIN produk_m AS pr ON pr.id = pp.produkfk
                            INNER JOIN strukpelayanan_t as sp on sp.norec=pp.strukfk
                            LEFT JOIN postingjurnaltransaksi_t AS pjt ON pjt.norecrelated = pd.norec
                            AND pjt.deskripsiproduktransaksi in ('pelayanan_deposit','pengembalian_deposit')
                            WHERE pd.kdprofile IN ( $kdProfile,0 )
                            AND pp.produkfk NOT IN ( 402611 )
                            and pjt.norec is null
                            AND pd.tglpulang BETWEEN :tglAwal and :tglAkhir
                            --and pd.noregistrasi = :noregistrasi

                        ) as x GROUP BY  x.norec_sp, x.noregistrasi,x.namapasien,x.objectkelompokpasienlastfk,x.tgl,x.tglstruk


                            "),
                    array(
                        'tglAwal' => $request['tglAwal'],
                        'tglAkhir' => $request['tglAkhir']
                        // 'noregistrasi' => $request['noregistrasi']
                    )

                );
                // and pd.norec = '$norec_registrasi'


                $norec_registrasi = '';
                $totdeposit = 0;
                foreach ($aingMacan as $itemdp) {

                    $norec_registrasi = $itemdp->noregistrasi;
                    $totdeposit = $itemdp->totaldeposit;
                    $totalTagihan = 0;
                    $kelompokpasien = '';



                    foreach ($aingMacantagihan as $itemtg) {
                        if ($norec_registrasi == $itemtg->noregistrasi) {
                            $totalTagihan = $itemtg->harga;
                            // return $norec_registrasi. '/' .$itemtg->noregistrasi ;

                            $kelompokpasien = $itemtg->objectkelompokpasienlastfk;
                            if ($totdeposit < $totalTagihan) {

                                $dataCoadp = \DB::table('chartofaccountmapjurnal_t as cmap')
                                    ->join('chartofaccount_m as coad', 'coad.id', '=', 'cmap.objectcoadebetfk')
                                    ->join('chartofaccount_m as coak', 'coak.id', '=', 'cmap.objectcoakreditfk')
                                    ->leftjoin('kelompokpasien_m as kp', 'kp.id', '=', 'cmap.objectkelompokpasienfk')
                                    ->select(
                                        'cmap.norec',
                                        'coad.id as objectcoadebetfk',
                                        'coad.noaccount as jurnaldno',
                                        'coad.namaaccount as jurnaldnm',
                                        'coak.id as objectcoakreditfk',
                                        'coak.noaccount as jurnalkno',
                                        'coak.namaaccount as jurnalknm',
                                        'cmap.objectkelompokpasienfk',
                                        'kp.kelompokpasien'
                                    )
                                    ->where('cmap.kdprofile', $kdProfile)
                                    ->where('cmap.statusenabled', true)
                                    ->where('objectjenistrxfk', '=', 16)
                                    ->where('cmap.objectkelompokpasienfk', '=', $kelompokpasien)
                                    ->orderBy('coad.noaccount')
                                    ->orderBy('coak.noaccount')
                                    ->get();

                                $totalRp = $totdeposit;

                                $debetId = $dataCoadp[0]->objectcoadebetfk;
                                $kreditId = $dataCoadp[0]->objectcoakreditfk;

                                $noBuktiTransaksi = $itemtg->noregistrasi;

                                $noJurnalIntern = '';
                                $noPosting = '-';
                                $noJurnalIntern = Carbon::parse($itemtg->tglstruk)->format('ym') . 'PN' . Carbon::parse($itemtg->tglstruk)->format('d') . '00016';
                                $cekSudahPosting = PostingJurnal::where('norecrelated', $noJurnalIntern)->where('kdprofile', $kdProfile)->get();

                                if ($this->getCountArray($cekSudahPosting) == 0) {
                                    $postingJurnalTransaksi = new PostingJurnalTransaksi;
                                    $norecHead = $postingJurnalTransaksi->generateNewId();
                                    $postingJurnalTransaksi->norec = $norecHead;
                                    $postingJurnalTransaksi->kdprofile = $kdProfile;
                                    $postingJurnalTransaksi->noposting = $noPosting;
                                    $postingJurnalTransaksi->nojurnal = 0;
                                    $postingJurnalTransaksi->nojurnal_intern = $noJurnalIntern;
                                    $postingJurnalTransaksi->objectjenisjurnalfk = 1;
                                    $postingJurnalTransaksi->nobuktitransaksi = $noBuktiTransaksi;
                                    $postingJurnalTransaksi->tglbuktitransaksi = $itemtg->tglstruk;
                                    $postingJurnalTransaksi->kdproduk = '';
                                    $postingJurnalTransaksi->namaproduktransaksi = 'Deposit pelayanan ' . $itemtg->namapasien . ' (' . $itemtg->noregistrasi . ')'; //. ' ' . $item->obat;
                                    $postingJurnalTransaksi->deskripsiproduktransaksi = 'pelayanan_deposit';
                                    $postingJurnalTransaksi->keteranganlainnya = 'Pelayanan Deposit tgl. ' . $itemtg->tgl;
                                    $postingJurnalTransaksi->statusenabled = 1;
                                    $postingJurnalTransaksi->norecrelated = $itemtg->norec_sp;
                                    $postingJurnalTransaksi->save();

                                    $norec_pj = $postingJurnalTransaksi->norec;

                                    //debet
                                    $postingJurnalTransaksiD = new PostingJurnalTransaksiD;
                                    $postingJurnalTransaksiD->norec = $postingJurnalTransaksiD->generateNewId();
                                    $postingJurnalTransaksiD->kdprofile = $kdProfile;
                                    $postingJurnalTransaksiD->nojurnal = 0;
                                    $postingJurnalTransaksiD->noposting = $noPosting;
                                    $postingJurnalTransaksiD->objectaccountfk = $debetId;
                                    $postingJurnalTransaksiD->hargasatuand = $totalRp;
                                    $postingJurnalTransaksiD->hargasatuank = 0;
                                    $postingJurnalTransaksiD->statusenabled = 1;
                                    $postingJurnalTransaksiD->norecrelated = $norec_pj;
                                    $postingJurnalTransaksiD->save();

                                    //kredit
                                    $postingJurnalTransaksiD = new PostingJurnalTransaksiD;
                                    $postingJurnalTransaksiD->norec = $postingJurnalTransaksiD->generateNewId();
                                    $postingJurnalTransaksiD->kdprofile = $kdProfile;
                                    $postingJurnalTransaksiD->nojurnal = 0;
                                    $postingJurnalTransaksiD->noposting = $noPosting;
                                    $postingJurnalTransaksiD->objectaccountfk = $kreditId;
                                    $postingJurnalTransaksiD->hargasatuand = 0;
                                    $postingJurnalTransaksiD->hargasatuank = $totalRp;
                                    $postingJurnalTransaksiD->statusenabled = 1;
                                    $postingJurnalTransaksiD->norecrelated = $norec_pj;
                                    $postingJurnalTransaksiD->save();
                                } else {

                                    $noJurnalIntern = Carbon::parse($itemtg->tglstruk)->format('ym') . 'AJ' . Carbon::parse($itemtg->tglstruk)->format('d') . '00016';
                                    $postingJurnalTransaksi = new PostingJurnalTransaksi;
                                    $norecHead = $postingJurnalTransaksi->generateNewId();
                                    $postingJurnalTransaksi->norec = $norecHead;
                                    $postingJurnalTransaksi->kdprofile = $kdProfile;
                                    $postingJurnalTransaksi->noposting = $noPosting;
                                    $postingJurnalTransaksi->nojurnal = 0;
                                    $postingJurnalTransaksi->nojurnal_intern = $noJurnalIntern;
                                    $postingJurnalTransaksi->objectjenisjurnalfk = 1;
                                    $postingJurnalTransaksi->nobuktitransaksi = $noBuktiTransaksi;
                                    $postingJurnalTransaksi->tglbuktitransaksi = date('Y-m-t', strtotime($itemtg->tglstruk)); //$item->tglpelayanan;
                                    $postingJurnalTransaksi->kdproduk = '';
                                    $postingJurnalTransaksi->namaproduktransaksi = 'Deposit pelayanan ' . $itemtg->namapasien . ' (' . $itemtg->noregistrasi . ')';
                                    $postingJurnalTransaksi->deskripsiproduktransaksi = 'pelayanan_deposit';
                                    $postingJurnalTransaksi->keteranganlainnya = 'Adjustment Pelayanan Deposit tgl. ' . $itemtg->tgl;
                                    $postingJurnalTransaksi->statusenabled = true;
                                    $postingJurnalTransaksi->norecrelated = $itemtg->norec_sp;
                                    $postingJurnalTransaksi->save();


                                    $norec_pj = $postingJurnalTransaksi->norec;

                                    //debet
                                    $postingJurnalTransaksiD = new PostingJurnalTransaksiD;
                                    $postingJurnalTransaksiD->norec = $postingJurnalTransaksiD->generateNewId();
                                    $postingJurnalTransaksiD->kdprofile = $kdProfile;
                                    $postingJurnalTransaksiD->nojurnal = 0;
                                    $postingJurnalTransaksiD->noposting = $noPosting;
                                    $postingJurnalTransaksiD->objectaccountfk = $debetId;
                                    $postingJurnalTransaksiD->hargasatuand = $totalRp;
                                    $postingJurnalTransaksiD->hargasatuank = 0;
                                    $postingJurnalTransaksiD->statusenabled = 1;
                                    $postingJurnalTransaksiD->norecrelated = $norec_pj;
                                    $postingJurnalTransaksiD->save();

                                    //kredit
                                    $postingJurnalTransaksiD = new PostingJurnalTransaksiD;
                                    $postingJurnalTransaksiD->norec = $postingJurnalTransaksiD->generateNewId();
                                    $postingJurnalTransaksiD->kdprofile = $kdProfile;
                                    $postingJurnalTransaksiD->nojurnal = 0;
                                    $postingJurnalTransaksiD->noposting = $noPosting;
                                    $postingJurnalTransaksiD->objectaccountfk = $kreditId;
                                    $postingJurnalTransaksiD->hargasatuand = 0;
                                    $postingJurnalTransaksiD->hargasatuank = $totalRp;
                                    $postingJurnalTransaksiD->statusenabled = 1;
                                    $postingJurnalTransaksiD->norecrelated = $norec_pj;
                                    $postingJurnalTransaksiD->save();
                                }
                            } else {

                                $dataCoadp = \DB::table('chartofaccountmapjurnal_t as cmap')
                                    ->join('chartofaccount_m as coad', 'coad.id', '=', 'cmap.objectcoadebetfk')
                                    ->join('chartofaccount_m as coak', 'coak.id', '=', 'cmap.objectcoakreditfk')
                                    ->leftjoin('kelompokpasien_m as kp', 'kp.id', '=', 'cmap.objectkelompokpasienfk')
                                    ->select(
                                        'cmap.norec',
                                        'coad.id as objectcoadebetfk',
                                        'coad.noaccount as jurnaldno',
                                        'coad.namaaccount as jurnaldnm',
                                        'coak.id as objectcoakreditfk',
                                        'coak.noaccount as jurnalkno',
                                        'coak.namaaccount as jurnalknm',
                                        'cmap.objectkelompokpasienfk',
                                        'kp.kelompokpasien'
                                    )
                                    ->where('cmap.kdprofile', $kdProfile)
                                    ->where('cmap.statusenabled', true)
                                    ->where('objectjenistrxfk', '=', 16)
                                    ->where('cmap.objectkelompokpasienfk', $kelompokpasien)
                                    ->orderBy('coad.noaccount')
                                    ->orderBy('coak.noaccount')
                                    ->get();

                                $totalRp = $totalTagihan;


                                $debetId = $dataCoadp[0]->objectcoadebetfk;
                                $kreditId = $dataCoadp[0]->objectcoakreditfk;

                                $noBuktiTransaksi = $itemtg->noregistrasi;

                                $noJurnalIntern = '';
                                $noPosting = '-';
                                $noJurnalIntern = Carbon::parse($itemtg->tglstruk)->format('ym') . 'PN' . Carbon::parse($itemtg->tglstruk)->format('d') . '00016';
                                $cekSudahPosting = PostingJurnal::where('norecrelated', $noJurnalIntern)->where('kdprofile', $kdProfile)->get();

                                if ($this->getCountArray($cekSudahPosting) == 0) {
                                    $postingJurnalTransaksi = new PostingJurnalTransaksi;
                                    $norecHead = $postingJurnalTransaksi->generateNewId();
                                    $postingJurnalTransaksi->norec = $norecHead;
                                    $postingJurnalTransaksi->kdprofile = $kdProfile;
                                    $postingJurnalTransaksi->noposting = $noPosting;
                                    $postingJurnalTransaksi->nojurnal = 0;
                                    $postingJurnalTransaksi->nojurnal_intern = $noJurnalIntern;
                                    $postingJurnalTransaksi->objectjenisjurnalfk = 1;
                                    $postingJurnalTransaksi->nobuktitransaksi = $noBuktiTransaksi;
                                    $postingJurnalTransaksi->tglbuktitransaksi = $itemtg->tglstruk;
                                    $postingJurnalTransaksi->kdproduk = '';
                                    $postingJurnalTransaksi->namaproduktransaksi = 'Deposit pelayanan ' . $itemtg->namapasien . ' (' . $itemtg->noregistrasi . ')'; //. ' ' . $item->obat;
                                    $postingJurnalTransaksi->deskripsiproduktransaksi = 'pelayanan_deposit';
                                    $postingJurnalTransaksi->keteranganlainnya = 'Pelayanan Deposit tgl. ' . $itemtg->tgl;
                                    $postingJurnalTransaksi->statusenabled = 1;
                                    $postingJurnalTransaksi->norecrelated = $itemtg->norec_sp;
                                    $postingJurnalTransaksi->save();

                                    $norec_pj = $postingJurnalTransaksi->norec;

                                    //debet
                                    $postingJurnalTransaksiD = new PostingJurnalTransaksiD;
                                    $postingJurnalTransaksiD->norec = $postingJurnalTransaksiD->generateNewId();
                                    $postingJurnalTransaksiD->kdprofile = $kdProfile;
                                    $postingJurnalTransaksiD->nojurnal = 0;
                                    $postingJurnalTransaksiD->noposting = $noPosting;
                                    $postingJurnalTransaksiD->objectaccountfk = $debetId;
                                    $postingJurnalTransaksiD->hargasatuand = $totalRp;
                                    $postingJurnalTransaksiD->hargasatuank = 0;
                                    $postingJurnalTransaksiD->statusenabled = 1;
                                    $postingJurnalTransaksiD->norecrelated = $norec_pj;
                                    $postingJurnalTransaksiD->save();

                                    //kredit
                                    $postingJurnalTransaksiD = new PostingJurnalTransaksiD;
                                    $postingJurnalTransaksiD->norec = $postingJurnalTransaksiD->generateNewId();
                                    $postingJurnalTransaksiD->kdprofile = $kdProfile;
                                    $postingJurnalTransaksiD->nojurnal = 0;
                                    $postingJurnalTransaksiD->noposting = $noPosting;
                                    $postingJurnalTransaksiD->objectaccountfk = $kreditId;
                                    $postingJurnalTransaksiD->hargasatuand = 0;
                                    $postingJurnalTransaksiD->hargasatuank = $totalRp;
                                    $postingJurnalTransaksiD->statusenabled = 1;
                                    $postingJurnalTransaksiD->norecrelated = $norec_pj;
                                    $postingJurnalTransaksiD->save();
                                } else {

                                    $noJurnalIntern = Carbon::parse($itemtg->tglstruk)->format('ym') . 'AJ' . Carbon::parse($itemtg->tglstruk)->format('d') . '00016';
                                    $postingJurnalTransaksi = new PostingJurnalTransaksi;
                                    $norecHead = $postingJurnalTransaksi->generateNewId();
                                    $postingJurnalTransaksi->norec = $norecHead;
                                    $postingJurnalTransaksi->kdprofile = $kdProfile;
                                    $postingJurnalTransaksi->noposting = $noPosting;
                                    $postingJurnalTransaksi->nojurnal = 0;
                                    $postingJurnalTransaksi->nojurnal_intern = $noJurnalIntern;
                                    $postingJurnalTransaksi->objectjenisjurnalfk = 1;
                                    $postingJurnalTransaksi->nobuktitransaksi = $noBuktiTransaksi;
                                    $postingJurnalTransaksi->tglbuktitransaksi = date('Y-m-t', strtotime($itemtg->tglstruk)); //$item->tglpelayanan;
                                    $postingJurnalTransaksi->kdproduk = '';
                                    $postingJurnalTransaksi->namaproduktransaksi = 'Deposit pelayanan ' . $itemtg->namapasien . ' (' . $itemtg->noregistrasi . ')';
                                    $postingJurnalTransaksi->deskripsiproduktransaksi = 'pelayanan_deposit';
                                    $postingJurnalTransaksi->keteranganlainnya = 'Adjustment Pelayanan Deposit tgl. ' . $itemtg->tgl;
                                    $postingJurnalTransaksi->statusenabled = true;
                                    $postingJurnalTransaksi->norecrelated = $itemtg->norec_sp;
                                    $postingJurnalTransaksi->save();


                                    $norec_pj = $postingJurnalTransaksi->norec;

                                    //debet
                                    $postingJurnalTransaksiD = new PostingJurnalTransaksiD;
                                    $postingJurnalTransaksiD->norec = $postingJurnalTransaksiD->generateNewId();
                                    $postingJurnalTransaksiD->kdprofile = $kdProfile;
                                    $postingJurnalTransaksiD->nojurnal = 0;
                                    $postingJurnalTransaksiD->noposting = $noPosting;
                                    $postingJurnalTransaksiD->objectaccountfk = $debetId;
                                    $postingJurnalTransaksiD->hargasatuand = $totalRp;
                                    $postingJurnalTransaksiD->hargasatuank = 0;
                                    $postingJurnalTransaksiD->statusenabled = 1;
                                    $postingJurnalTransaksiD->norecrelated = $norec_pj;
                                    $postingJurnalTransaksiD->save();

                                    //kredit
                                    $postingJurnalTransaksiD = new PostingJurnalTransaksiD;
                                    $postingJurnalTransaksiD->norec = $postingJurnalTransaksiD->generateNewId();
                                    $postingJurnalTransaksiD->kdprofile = $kdProfile;
                                    $postingJurnalTransaksiD->nojurnal = 0;
                                    $postingJurnalTransaksiD->noposting = $noPosting;
                                    $postingJurnalTransaksiD->objectaccountfk = $kreditId;
                                    $postingJurnalTransaksiD->hargasatuand = 0;
                                    $postingJurnalTransaksiD->hargasatuank = $totalRp;
                                    $postingJurnalTransaksiD->statusenabled = 1;
                                    $postingJurnalTransaksiD->norecrelated = $norec_pj;
                                    $postingJurnalTransaksiD->save();
                                }

                                $dataCoadpt = \DB::table('chartofaccountmapjurnal_t as cmap')
                                    ->join('chartofaccount_m as coad', 'coad.id', '=', 'cmap.objectcoadebetfk')
                                    ->join('chartofaccount_m as coak', 'coak.id', '=', 'cmap.objectcoakreditfk')
                                    ->leftjoin('kelompokpasien_m as kp', 'kp.id', '=', 'cmap.objectkelompokpasienfk')
                                    ->select(
                                        'cmap.norec',
                                        'coad.id as objectcoadebetfk',
                                        'coad.noaccount as jurnaldno',
                                        'coad.namaaccount as jurnaldnm',
                                        'coak.id as objectcoakreditfk',
                                        'coak.noaccount as jurnalkno',
                                        'coak.namaaccount as jurnalknm',
                                        'cmap.objectkelompokpasienfk',
                                        'kp.kelompokpasien'
                                    )
                                    ->where('cmap.kdprofile', $kdProfile)
                                    ->where('cmap.statusenabled', true)
                                    ->where('objectjenistrxfk', '=', 17)
                                    ->where('cmap.objectkelompokpasienfk', $kelompokpasien)
                                    ->orderBy('coad.noaccount')
                                    ->orderBy('coak.noaccount')
                                    ->get();

                                $totalRp = (float) $totdeposit - $totalTagihan;


                                $debetId = $dataCoadpt[0]->objectcoadebetfk;
                                $kreditId = $dataCoadpt[0]->objectcoakreditfk;

                                $noBuktiTransaksi = $itemtg->noregistrasi;

                                $noJurnalIntern = '';
                                $noPosting = '-';
                                $noJurnalIntern = Carbon::parse($itemtg->tglstruk)->format('ym') . 'PN' . Carbon::parse($itemtg->tglstruk)->format('d') . '00017';
                                $cekSudahPosting = PostingJurnal::where('norecrelated', $noJurnalIntern)->where('kdprofile', $kdProfile)->get();

                                if ($this->getCountArray($cekSudahPosting) == 0) {
                                    $postingJurnalTransaksi = new PostingJurnalTransaksi;
                                    $norecHead = $postingJurnalTransaksi->generateNewId();
                                    $postingJurnalTransaksi->norec = $norecHead;
                                    $postingJurnalTransaksi->kdprofile = $kdProfile;
                                    $postingJurnalTransaksi->noposting = $noPosting;
                                    $postingJurnalTransaksi->nojurnal = 0;
                                    $postingJurnalTransaksi->nojurnal_intern = $noJurnalIntern;
                                    $postingJurnalTransaksi->objectjenisjurnalfk = 1;
                                    $postingJurnalTransaksi->nobuktitransaksi = $noBuktiTransaksi;
                                    $postingJurnalTransaksi->tglbuktitransaksi = $itemtg->tglstruk;
                                    $postingJurnalTransaksi->kdproduk = '';
                                    $postingJurnalTransaksi->namaproduktransaksi = 'Pengembalian Sisa Deposit ' . $itemtg->namapasien . ' (' . $itemtg->noregistrasi . ')'; //. ' ' . $item->obat;
                                    $postingJurnalTransaksi->deskripsiproduktransaksi = 'pengembalian_deposit';
                                    $postingJurnalTransaksi->keteranganlainnya = 'Pengembalian Sisa Deposit tgl. ' . $itemtg->tgl;
                                    $postingJurnalTransaksi->statusenabled = 1;
                                    $postingJurnalTransaksi->norecrelated = $itemtg->norec_sp;
                                    $postingJurnalTransaksi->save();

                                    $norec_pj = $postingJurnalTransaksi->norec;

                                    //debet
                                    $postingJurnalTransaksiD = new PostingJurnalTransaksiD;
                                    $postingJurnalTransaksiD->norec = $postingJurnalTransaksiD->generateNewId();
                                    $postingJurnalTransaksiD->kdprofile = $kdProfile;
                                    $postingJurnalTransaksiD->nojurnal = 0;
                                    $postingJurnalTransaksiD->noposting = $noPosting;
                                    $postingJurnalTransaksiD->objectaccountfk = $debetId;
                                    $postingJurnalTransaksiD->hargasatuand = $totalRp;
                                    $postingJurnalTransaksiD->hargasatuank = 0;
                                    $postingJurnalTransaksiD->statusenabled = 1;
                                    $postingJurnalTransaksiD->norecrelated = $norec_pj;
                                    $postingJurnalTransaksiD->save();

                                    //kredit
                                    $postingJurnalTransaksiD = new PostingJurnalTransaksiD;
                                    $postingJurnalTransaksiD->norec = $postingJurnalTransaksiD->generateNewId();
                                    $postingJurnalTransaksiD->kdprofile = $kdProfile;
                                    $postingJurnalTransaksiD->nojurnal = 0;
                                    $postingJurnalTransaksiD->noposting = $noPosting;
                                    $postingJurnalTransaksiD->objectaccountfk = $kreditId;
                                    $postingJurnalTransaksiD->hargasatuand = 0;
                                    $postingJurnalTransaksiD->hargasatuank = $totalRp;
                                    $postingJurnalTransaksiD->statusenabled = 1;
                                    $postingJurnalTransaksiD->norecrelated = $norec_pj;
                                    $postingJurnalTransaksiD->save();
                                } else {

                                    $noJurnalIntern = Carbon::parse($itemtg->tglstruk)->format('ym') . 'AJ' . Carbon::parse($itemtg->tglstruk)->format('d') . '00017';
                                    $postingJurnalTransaksi = new PostingJurnalTransaksi;
                                    $norecHead = $postingJurnalTransaksi->generateNewId();
                                    $postingJurnalTransaksi->norec = $norecHead;
                                    $postingJurnalTransaksi->kdprofile = $kdProfile;
                                    $postingJurnalTransaksi->noposting = $noPosting;
                                    $postingJurnalTransaksi->nojurnal = 0;
                                    $postingJurnalTransaksi->nojurnal_intern = $noJurnalIntern;
                                    $postingJurnalTransaksi->objectjenisjurnalfk = 1;
                                    $postingJurnalTransaksi->nobuktitransaksi = $noBuktiTransaksi;
                                    $postingJurnalTransaksi->tglbuktitransaksi = date('Y-m-t', strtotime($itemtg->tglstruk)); //$item->tglpelayanan;
                                    $postingJurnalTransaksi->kdproduk = '';
                                    $postingJurnalTransaksi->namaproduktransaksi = 'Pengembalian Sisa Deposit ' . $itemtg->namapasien . ' (' . $itemtg->noregistrasi . ')';
                                    $postingJurnalTransaksi->deskripsiproduktransaksi = 'pengembalian_deposit';
                                    $postingJurnalTransaksi->keteranganlainnya = 'Adjustment Pengembalian Sisa Deposit tgl. ' . $itemtg->tgl;
                                    $postingJurnalTransaksi->statusenabled = true;
                                    $postingJurnalTransaksi->norecrelated = $itemtg->norec_sp;
                                    $postingJurnalTransaksi->save();


                                    $norec_pj = $postingJurnalTransaksi->norec;

                                    //debet
                                    $postingJurnalTransaksiD = new PostingJurnalTransaksiD;
                                    $postingJurnalTransaksiD->norec = $postingJurnalTransaksiD->generateNewId();
                                    $postingJurnalTransaksiD->kdprofile = $kdProfile;
                                    $postingJurnalTransaksiD->nojurnal = 0;
                                    $postingJurnalTransaksiD->noposting = $noPosting;
                                    $postingJurnalTransaksiD->objectaccountfk = $debetId;
                                    $postingJurnalTransaksiD->hargasatuand = $totalRp;
                                    $postingJurnalTransaksiD->hargasatuank = 0;
                                    $postingJurnalTransaksiD->statusenabled = 1;
                                    $postingJurnalTransaksiD->norecrelated = $norec_pj;
                                    $postingJurnalTransaksiD->save();

                                    //kredit
                                    $postingJurnalTransaksiD = new PostingJurnalTransaksiD;
                                    $postingJurnalTransaksiD->norec = $postingJurnalTransaksiD->generateNewId();
                                    $postingJurnalTransaksiD->kdprofile = $kdProfile;
                                    $postingJurnalTransaksiD->nojurnal = 0;
                                    $postingJurnalTransaksiD->noposting = $noPosting;
                                    $postingJurnalTransaksiD->objectaccountfk = $kreditId;
                                    $postingJurnalTransaksiD->hargasatuand = 0;
                                    $postingJurnalTransaksiD->hargasatuank = $totalRp;
                                    $postingJurnalTransaksiD->statusenabled = 1;
                                    $postingJurnalTransaksiD->norecrelated = $norec_pj;
                                    $postingJurnalTransaksiD->save();
                                }
                            }
                        }
                    }
                }
            }

            $transStatus = 'true';
        } catch (\Exception $e) {
            $transStatus = 'false';
        }
        $transMessage = 'Posting';
        $req = $request->all();
        if ($transStatus == 'true') {
            $transMessage = $transMessage . " Jurnal Berhasil";
            DB::commit();
            $result = array(
                "status" => 200,
                'result' => array(
                    "data" => $aingMacan,
                    "count" => count($aingMacan),
                    "count2" => 0, //count($aingMacan2),
                    "req" => $req,
                    "as" => 'as@epic',
                )
            );
        } else {
            $transMessage = $transMessage . " Jurnal Gagal!!";
            DB::rollBack();
            $result = array(
                "status" => 400,
                'result' => array(
                    "count" => count($aingMacan),
                    "data" => $aingMacan, //$noResep,
                    //                "coa" => $coacoa,
                    //                "dataCoa" => $dataCoa,
                    "as" => 'as@epic',
                )
            );
        }

        return $this->respond($result['result'], $result['status'], $transMessage);
    }
    public function PostingJurnal_amprahanForDaftar(Request $request)
    {
        $kdProfile = (int) $this->getDataKdProfile($request);
        DB::beginTransaction();
        $isAktif = $this->settingDataFixed('PostingJurnal_amprahanForDaftar_isaktif', $kdProfile);
        if ($isAktif == 0) {
            return;
        }
        $dataLogin = $request->all();
        try {
            return;
            // TODO : Jurnal Amprahan Ruangan Dri Daftar
            //            $delMacan = DB::select(DB::raw("
            //                    delete from postingjurnaltransaksid_t where norecrelated in (select pjt.norec from postingjurnaltransaksi_t as pjt
            //                INNER JOIN strukkirim_t as sp on sp.norec=pjt.norecrelated  and sp.tglkirim >'2019-01-01 00:00'
            //                left JOIN postingjurnal_t as posted on pjt.nojurnal_intern=posted.norecrelated
            //                where pjt.deskripsiproduktransaksi='amprahan_barang_ruangan' and sp.norec is null  and posted.nojurnal_intern is null
            //                and pjt.tglbuktitransaksi  >'2019-01-01 00:00') ")
            //            );
            //            $delMacanHead = DB::select(DB::raw("
            //                    delete from postingjurnaltransaksi_t where norec in (select pjt.norec from postingjurnaltransaksi_t as pjt
            //                INNER JOIN strukkirim_t as sp on sp.norec=pjt.norecrelated  and sp.tglkirim >'2019-01-01 00:00'
            //                left JOIN postingjurnal_t as posted on pjt.nojurnal_intern=posted.norecrelated
            //                where pjt.deskripsiproduktransaksi='amprahan_barang_ruangan'  and sp.norec is null  and posted.nojurnal_intern is null
            //                and pjt.tglbuktitransaksi  >'2019-01-01 00:00')")
            //            );

            $aingMacan = DB::select(
                DB::raw("select xx.norec,xx.tglkirim,xx.nokirim,xx.ruanganid,xx.ruanganasal,xx.ruangantujuanid,xx.ruangantujuan,xx.jenispermintaanfk,xx.tgl,SUM(xx.total) as total from
                    (select x.norec,x.tglkirim,x.nokirim,x.ruanganid,x.ruanganasal,x.ruangantujuanid,x.ruangantujuan,x.tgl,x.jenispermintaanfk,
                     x.objectprodukfk,x.qtyproduk*x.hargasatuan as total from
                    (SELECT sk.norec,sk.tglkirim,sk.nokirim,ru.id as ruanganid,ru.namaruangan as ruanganasal,ru1.id as ruangantujuanid,
                     ru1.namaruangan as ruangantujuan,format(sk.tglkirim, 'YYYY-MM-DD') as tgl,kp.objectprodukfk,kp.qtyproduk,kp.hargasatuan,
                     sk.jenispermintaanfk
                    FROM strukkirim_t as sk
                    INNER JOIN kirimproduk_t as kp on kp.nokirimfk = sk.norec
                    INNER JOIN ruangan_m as ru on ru.id = sk.objectruanganasalfk
                    INNER JOIN ruangan_m as ru1 on ru1.id = sk.objectruangantujuanfk
                    left JOIN postingjurnaltransaksi_t as pjt on pjt.norecrelated=sk.norec
                    where sk.kdprofile = $kdProfile and sk.tglkirim between :tglAwal and :tglAkhir and
                    sk.objectkelompoktransaksifk=34 and pjt.norec is null and sk.statusenabled <> 0
                    and sk.jenispermintaanfk =1
                    GROUP BY sk.norec,sk.tglkirim,sk.nokirim,ru.id,ru.namaruangan,ru1.id,ru1.namaruangan,kp.qtyproduk,kp.hargasatuan,kp.objectprodukfk,sk.jenispermintaanfk) as x) as xx
                    GROUP BY xx.norec,xx.tglkirim,xx.nokirim,xx.ruanganid,xx.ruanganasal,xx.ruangantujuanid,xx.ruangantujuan,xx.tgl,xx.jenispermintaanfk;"),
                array(
                    'tglAwal' => $request['tglAwal'],
                    'tglAkhir' => $request['tglAkhir'],
                )
            );

            $dataCoa = DB::select(
                DB::raw("select ru.id as ruid, ru.namaruangan,coa.namaaccount,coa.kdaccount,coa.id as coaid
                            from chartofaccount_m as coa
                            left JOIN ruangan_m as ru on coa.namaaccount ilike '%' + ru.namaruangan + ''
                            where coa.kdprofile = $kdProfile and coa.namaexternal='2018-03-01' and coa.namaaccount ilike 'Biaya Obat%' and ru.namaruangan <>'-' and ru.statusenabled=1;")
            );

            $debetId = ''; //1791;//1853;
            $kreditId = 1791;
            foreach ($aingMacan as $item) {
                $ruanganId = $item->ruangantujuanid;
                //                return $this->respond($dataCoa) ;
                $debetId = '';
                foreach ($dataCoa as $coa) {
                    if ($coa->ruid == $item->ruangantujuanid) {
                        $debetId = $coa->coaid;
                        break;
                    } else {
                        $debetId = 2363;
                    }
                }

                $totalRp = $item->total;

                $noBuktiTransaksi = $item->nokirim;
                $noPosting = '-'; //$this->generateCode(new PostingJurnalTransaksi, 'noposting', 14, 'KS-' . $this->getDateTime()->format('ym'));
                //                $nojurnal = $this->getSequence('postingjurnaltransaksi_t_nojurnal_seq');

                $newPJT = new PostingJurnalTransaksi;
                $norecHead = $newPJT->generateNewId();
                $newPJT->norec = $norecHead;
                $noJurnalIntern = Carbon::parse($item->tglkirim)->format('ym') . 'AMP-' . Carbon::parse($item->tglkirim)->format('d') . '00001';
                $namaproduktransaksi = 'Amprahan barang ruangan dengan nokirim ' . $item->nokirim . '  ,dari ruangan ' . $item->ruanganasal . ' ,keruangan ' . $item->ruangantujuan;
                $deskripsiproduktransaksi = 'amprahan_barang_ruangan';
                $keteranganlainnya = 'Amprahan barang ruangan ' . $item->tgl;

                $cekSudahPosting = PostingJurnal::where('norecrelated', $noJurnalIntern)->get();
                //                    if ($item->jenispermintaanfk == 1){
                if (count($cekSudahPosting) == 0) {
                    $newPJT->kdprofile = $kdProfile;
                    $newPJT->noposting = $noPosting;
                    $newPJT->nojurnal = 1; //$nojurnal;
                    $newPJT->nojurnal_intern = $noJurnalIntern;
                    $newPJT->objectjenisjurnalfk = 1;
                    $newPJT->nobuktitransaksi = $noBuktiTransaksi;
                    $newPJT->tglbuktitransaksi = $item->tglkirim; // $this->getDateTime()->format('Y-m-d H:i:s');
                    $newPJT->kdproduk = null;
                    $newPJT->namaproduktransaksi = $namaproduktransaksi; //'Amprahan barang ruangan dengan nokirim ' . $item->nokirim . '  ,dari ruangan ' . $item->ruanganasal .' ,keruangan '.$item->ruangantujuan ;
                    $newPJT->deskripsiproduktransaksi = $deskripsiproduktransaksi; // 'amprahan_barang_ruangan';
                    $newPJT->keteranganlainnya = $keteranganlainnya; //'Amprahan barang ruangan ' . $item->tgl;
                    $newPJT->statusenabled = 1;
                    $newPJT->norecrelated = $item->norec;
                    $newPJT->save();

                    //debet
                    $postingJurnalTransaksiD = new PostingJurnalTransaksiD;
                    $postingJurnalTransaksiD->norec = $postingJurnalTransaksiD->generateNewId();
                    $postingJurnalTransaksiD->kdprofile = $kdProfile;
                    $postingJurnalTransaksiD->nojurnal = 0; //$nojurnal;
                    $postingJurnalTransaksiD->noposting = $noPosting;
                    $postingJurnalTransaksiD->objectaccountfk = $debetId;
                    $postingJurnalTransaksiD->hargasatuand = $totalRp;
                    $postingJurnalTransaksiD->hargasatuank = 0;
                    $postingJurnalTransaksiD->statusenabled = 1;
                    $postingJurnalTransaksiD->norecrelated = $norecHead;
                    $postingJurnalTransaksiD->save();
                    //kredit
                    $postingJurnalTransaksiD = new PostingJurnalTransaksiD;
                    $postingJurnalTransaksiD->norec = $postingJurnalTransaksiD->generateNewId();
                    $postingJurnalTransaksiD->kdprofile = $kdProfile;
                    $postingJurnalTransaksiD->nojurnal = 0; //$nojurnal;
                    $postingJurnalTransaksiD->noposting = $noPosting;
                    $postingJurnalTransaksiD->objectaccountfk = $kreditId;
                    $postingJurnalTransaksiD->hargasatuand = 0;
                    $postingJurnalTransaksiD->hargasatuank = $totalRp;
                    $postingJurnalTransaksiD->statusenabled = 1;
                    $postingJurnalTransaksiD->norecrelated = $norecHead;
                    $postingJurnalTransaksiD->save();
                }
                //                    }
            }
            $transStatus = 'true';
        } catch (\Exception $e) {
            $transStatus = 'false';
        }

        $transMessage = "Posting";

        if ($transStatus == 'true') {
            $transMessage = $transMessage . "";
            DB::commit();
            $result = array(
                "status" => 200,
                "result" => array(
                    "data" => $aingMacan,
                    "count" => count($aingMacan),
                    "as" => '@epic',
                )
            );
        } else {
            $transMessage = $transMessage . " Gagal!!";
            DB::rollBack();
            $result = array(
                "status" => 400,
                "result" => array(
                    //                "data" => $aingMacan,
                    "as" => '@epic',
                )
            );
        }
        return $this->respond($result['result'], $result['status'], $transMessage);
    }

    public function PostingHapusJurnal_BatalKirim(Request $request)
    {
        $kdProfile = (int) $this->getDataKdProfile($request);
        DB::beginTransaction();
        $isAktif = $this->settingDataFixed('PostingHapusJurnal_BatalKirim_isaktif', $kdProfile);
        if ($isAktif == 0) {
            return;
        }
        $dataReq = $request->all();
        $nojurnal = $dataReq['strukkirim']['nokrim'];
        try {
            if ($dataReq['strukkirim'] != '-') {
                $delDetail = DB::select(
                    DB::raw("
                    delete from postingjurnaltransaksid_t
                    where kdprofile = $kdProfile and norecrelated in (select norec from postingjurnaltransaksi_t where nobuktitransaksi='$nojurnal');
                  ")
                );
                $delHead = DB::select(
                    DB::raw("
                    delete from postingjurnaltransaksi_t where kdprofile = $kdProfile and nobuktitransaksi='$nojurnal'
                  ")
                );
            }

            $transStatus = 'true';
        } catch (\Exception $e) {
            $transStatus = 'false';
        }
        $transMessage = 'Hapus';

        if ($transStatus == 'true') {
            $transMessage = $transMessage . " Berhasil";
            DB::commit();
            $result = array(
                "status" => 200,
                "result" => array(
                    "data" => $delHead,
                    "as" => '@epic',
                )
            );
        } else {
            $transMessage = $transMessage . " Gagal!!";
            DB::rollBack();
            $result = array(
                "status" => 400,
                "result" => array(
                    "data" => $delHead,
                    "as" => '@epic',
                )
            );
        }

        return $this->respond($result['result'], $result['status'], $transMessage);
    }

    public function UpdatePostingJurnal_BatalKirimPerItem(Request $request)
    {
        $kdProfile = (int) $this->getDataKdProfile($request);
        DB::beginTransaction();
        $dataReq = $request->all();
        $nojurnal = $dataReq['strukkirim']['noreckirim'];
        $aingMacan = DB::select(
            DB::raw("select xx.norecJurnal,xx.norec,xx.tglkirim,xx.nokirim,xx.ruanganid,xx.ruanganasal,xx.ruangantujuanid,xx.ruangantujuan,xx.tgl,SUM(xx.total) as total from
                        (select x.norecJurnal,x.norec,x.tglkirim,x.nokirim,x.ruanganid,x.ruanganasal,x.ruangantujuanid,x.ruangantujuan,x.tgl,
                         x.objectprodukfk,x.qtyproduk*x.hargasatuan as total from
                        (SELECT pjt.norec as norecJurnal,sk.norec,sk.tglkirim,sk.nokirim,ru.id as ruanganid,ru.namaruangan as ruanganasal,ru1.id as ruangantujuanid,
                         ru1.namaruangan as ruangantujuan,to_char(sk.tglkirim, 'YYYY-MM-DD') as tgl,kp.objectprodukfk,kp.qtyproduk,kp.hargasatuan
                        FROM strukkirim_t as sk
                        INNER JOIN kirimproduk_t as kp on kp.nokirimfk = sk.norec
                        INNER JOIN ruangan_m as ru on ru.id = sk.objectruanganasalfk
                        INNER JOIN ruangan_m as ru1 on ru1.id = sk.objectruangantujuanfk
                        left JOIN postingjurnaltransaksi_t as pjt on pjt.norecrelated=sk.norec
                        where sk.kdprofile = $kdProfile and
                        --sk.tglkirim between :tglAwal and :tglAkhir and
                        sk.objectkelompoktransaksifk=34 and sk.statusenabled <> 'f' and sk.norec = :norec
                        --sk.jenispermintaanfk =1
                        GROUP BY pjt.norec,sk.norec,sk.tglkirim,sk.nokirim,ru.id,ru.namaruangan,ru1.id,ru1.namaruangan,kp.qtyproduk,kp.hargasatuan,kp.objectprodukfk) as x) as xx
                        GROUP BY xx.norecJurnal,xx.norec,xx.tglkirim,xx.nokirim,xx.ruanganid,xx.ruanganasal,xx.ruangantujuanid,xx.ruangantujuan,xx.tgl;"),
            array(
                //                        'tglAwal' => $request['tglAwal'],
                //                        'tglAkhir' => $request['tglAkhir'],
                'norec' => $nojurnal
            )
        );
        try {
            foreach ($aingMacan as $item) {
                $norecrelated = $item->norec;
                if ($item != null) {
                    $updateDebet = DB::select(
                        DB::raw("UPDATE postingjurnaltransaksid_t SET
                                         hargasatuand = $item->total
                                         where kdprofile = $kdProfile and norecrelated in (select norec from postingjurnaltransaksi_t where norecrelated='$norecrelated')
                                         and hargasatuank=0")
                    );

                    $updateKredit = DB::select(
                        DB::raw("UPDATE postingjurnaltransaksid_t SET
                                         hargasatuank = $item->total
                                         where kdprofile = $kdProfile and norecrelated in (select norec from postingjurnaltransaksi_t where kdprofile = $kdProfile and norecrelated='$norecrelated')
                                         and hargasatuand=0")
                    );
                }
            }
            $transStatus = 'true';
        } catch (\Exception $e) {
            $transStatus = 'false';
        }
        $transMessage = 'Update';

        if ($transStatus == 'true') {
            $transMessage = $transMessage . " Berhasil";
            DB::commit();
            $result = array(
                "status" => 200,
                "result" => array(
                    "data" => $aingMacan,
                    "as" => '@epic',
                )
            );
        } else {
            $transMessage = $transMessage . " Gagal!!";
            DB::rollBack();
            $result = array(
                "status" => 400,
                "result" => array(
                    "data" => $aingMacan,
                    "as" => '@epic',
                )
            );
        }

        return $this->respond($result['result'], $result['status'], $transMessage);
    }
    public function PostingJurnal_cashflow_statement(Request $request)
    {
        $kdProfile = (int) $this->kdProfile;
        DB::beginTransaction();
        $isAktif = $this->settingFix('PostingJurnal_cashflow_statement');

        if (!empty($isAktif) && $isAktif == 'false') {
            return $isAktif;
        }

        try {


            $aingMacan = [];

            $aingMacan = DB::select(
                DB::raw("
                SELECT x.*,pjt.norec from (

                    SELECT spp.norec, spp.tglsbm,spp.keteranganlainnya,kt.kelompoktransaksi,spp.nostrukfk,
                    sc.objectkelompoktransaksifk,sc.norec AS norec_sc,sc.noclosing,sh.nonhistori,sh.tglsetortarikdeposit as tgl,
                    sh.ketlainya,sh.norec AS norec_sp,sh.nobukti,sh.tglsetortarikdeposit as tglstruk,sh.totalsetortarikdeposit as totalprekanan, null as jenis,
                    CASE
                    WHEN sc.objectkelompoktransaksifk IN (SELECT bku.kelompoktransaksifk FROM mapbkutokelompoktransaksi_m AS bku
                    WHERE idbku IN ( 1 ) ) THEN	COALESCE ( ( spp.totaldibayar ), 0 )
                    END AS debit,kt.coadebetfk, kt.coakreditfk,
                    CASE
                        WHEN sc.objectkelompoktransaksifk IN (SELECT	bku.kelompoktransaksifk FROM
                            mapbkutokelompoktransaksi_m AS bku WHERE	idbku IN ( 4 ) 		) THEN	COALESCE ( ( sbk.totaldibayar ), 0 ) END AS kredit,
                    CASE
                            WHEN spp.nosbm IS NULL THEN	sbk.nosbk ELSE spp.nosbm END AS notransaksi,CAST ( sh.tglsetortarikdeposit AS DATE ) AS tglsetortarikdeposit
                    FROM
                        strukhistori_t AS sh
                        INNER JOIN strukclosing_t AS sc ON sc.norec = sh.noclosing
                        LEFT JOIN strukbuktipenerimaan_t AS spp ON spp.noclosingfk = sc.norec
                        LEFT JOIN strukbuktipengeluaran_t AS sbk ON sbk.noclosingfk = sc.norec
                        INNER JOIN kelompoktransaksi_m AS kt ON kt.id = sc.objectkelompoktransaksifk
                    WHERE
                        sh.kdprofile = 1
                        AND sh.statusenabled = true
                        AND sh.objectkelompoktransaksifk IN (
                        SELECT
                            bku.kelompoktransaksifk
                        FROM
                            mapbkutokelompoktransaksi_m AS bku
                        WHERE
                            idbku IN ( 1, 4 )
                        )
                        AND sh.tglsetortarikdeposit >= :tglAwal
                        AND sh.tglsetortarikdeposit <= :tglAkhir
                        and kt.coadebetfk is not null and kt.coakreditfk is not null
                    ) as x
                    left JOIN postingjurnaltransaksi_t as pjt on pjt.norecrelated=x.norec_sp and pjt.deskripsiproduktransaksi = 'cash_flow_statement'
                    where pjt.norec is null  LIMIT 1000
                        "),
                array(
                    'tglAwal' => $request['tglAwal'],
                    'tglAkhir' => $request['tglAkhir'],
                )
            );


            $coaruangandiskon = [];
            foreach ($aingMacan as $item) {
                $map = [];


                $cmap = DB::select(DB::raw("
                                select 2 as objectjenistrxfk, cmap.coadebetfk as jurnaldid,coad.namaaccount as coadebet,
                                cmap.coakreditfk as jurnalkid,coak.namaaccount as coakredit
                                from kelompoktransaksi_m as cmap
                                INNER JOIN chartofaccount_m as coad on coad.id=cmap.coadebetfk
                                INNER JOIN chartofaccount_m as coak on coak.id=cmap.coakreditfk

                                --limit 1

                    "));


                if (!empty($cmap)) {


                    $debetId = $cmap[0]->jurnaldid;
                    $kreditId = $cmap[0]->jurnalkid;


                    $noBuktiTransaksi = $item->nonhistori;
                    $noPosting = '-';

                    $noJurnalIntern = Carbon::parse($item->tglstruk)->format('ym') . 'PN' . Carbon::parse($item->tglstruk)->format('d') . '00008';

                    $cekSudahPosting = PostingJurnal::where('norecrelated', $noJurnalIntern)->where('kdprofile', $kdProfile)->get();
                    if ($this->getCountArray($cekSudahPosting) == 0) {
                        $postingJurnalTransaksi = new PostingJurnalTransaksi;
                        $norecHead = $postingJurnalTransaksi->generateNewId();
                        $postingJurnalTransaksi->norec = $norecHead;
                        $postingJurnalTransaksi->kdprofile = $kdProfile;
                        $postingJurnalTransaksi->noposting = $noPosting;
                        $postingJurnalTransaksi->nojurnal = 0;
                        $postingJurnalTransaksi->objectjenisjurnalfk = 1;
                        $postingJurnalTransaksi->nobuktitransaksi = $noBuktiTransaksi;
                        $postingJurnalTransaksi->tglbuktitransaksi = $item->tglstruk; /// $this->getDateTime()->format('Y-m-d H:i:s');
                        $postingJurnalTransaksi->kdproduk = null;
                        $postingJurnalTransaksi->namaproduktransaksi = $item->kelompoktransaksi; //'Verifikasi tagihan ' . $noReg . ', ' . $namaPasien . ' di TataRekening';
                        $postingJurnalTransaksi->deskripsiproduktransaksi = 'cash_flow_statement';
                        $postingJurnalTransaksi->keteranganlainnya = 'MKKO Tgl. ' . date('Y-m-d', strtotime($item->tgl));
                        $postingJurnalTransaksi->nojurnal_intern = $noJurnalIntern;
                        $postingJurnalTransaksi->statusenabled = 1;
                        $postingJurnalTransaksi->norecrelated = $item->norec_sp;
                        $postingJurnalTransaksi->jenis = $item->jenis;
                        $postingJurnalTransaksi->save();

                        $norec_pj = $postingJurnalTransaksi->norec;

                        // $totalRp = $item->totalharusdibayar;
                        $totalRekananRp = $item->totalprekanan;

                        if ($totalRekananRp > 0) {

                            $postingJurnalTransaksiD = new PostingJurnalTransaksiD;
                            $postingJurnalTransaksiD->norec = $postingJurnalTransaksiD->generateNewId();
                            $postingJurnalTransaksiD->kdprofile = $kdProfile;
                            $postingJurnalTransaksiD->nojurnal = 0;
                            $postingJurnalTransaksiD->noposting = $noPosting;
                            $postingJurnalTransaksiD->objectaccountfk = $debetId;
                            $postingJurnalTransaksiD->hargasatuand = $totalRekananRp;
                            $postingJurnalTransaksiD->hargasatuank = 0;
                            $postingJurnalTransaksiD->statusenabled = 1;
                            $postingJurnalTransaksiD->norecrelated = $norec_pj;
                            $postingJurnalTransaksiD->save();

                            //kredit
                            $postingJurnalTransaksiD = new PostingJurnalTransaksiD;
                            $postingJurnalTransaksiD->norec = $postingJurnalTransaksiD->generateNewId();
                            $postingJurnalTransaksiD->kdprofile = $kdProfile;
                            $postingJurnalTransaksiD->nojurnal = 0;
                            $postingJurnalTransaksiD->noposting = $noPosting;
                            $postingJurnalTransaksiD->objectaccountfk = $kreditId;
                            $postingJurnalTransaksiD->hargasatuand = 0;
                            $postingJurnalTransaksiD->hargasatuank = $totalRekananRp;
                            $postingJurnalTransaksiD->statusenabled = 1;
                            $postingJurnalTransaksiD->norecrelated = $norec_pj;
                            $postingJurnalTransaksiD->save();
                        }
                    } else {
                        // $noJurnalIntern = Carbon::parse($item->tglstruk)->format('ym') . 'AJ' . Carbon::parse($item->tglstruk)->format('d') . '00002';
                        // $postingJurnalTransaksi = new PostingJurnalTransaksi;
                        // $norecHead = $postingJurnalTransaksi->generateNewId();
                        // $postingJurnalTransaksi->norec = $norecHead;
                        // $postingJurnalTransaksi->kdprofile = $kdProfile;
                        // $postingJurnalTransaksi->noposting = $noPosting;
                        // $postingJurnalTransaksi->nojurnal = 0;
                        // $postingJurnalTransaksi->objectjenisjurnalfk = 1;
                        // $postingJurnalTransaksi->nobuktitransaksi = $noBuktiTransaksi;
                        // $postingJurnalTransaksi->tglbuktitransaksi = date('Y-m-t', strtotime($item->tglstruk)); //$item->tglstruk;/// $this->getDateTime()->format('Y-m-d H:i:s');
                        // $postingJurnalTransaksi->kdproduk = null;
                        // $postingJurnalTransaksi->namaproduktransaksi = 'Adjustment Verifikasi tagihan ' . $noReg . ', ' . $namaPasien . ' di TataRekening';
                        // $postingJurnalTransaksi->deskripsiproduktransaksi = 'verifikasi_tarek';
                        // $postingJurnalTransaksi->keteranganlainnya = 'Adjustment Verifikasi Tagihan Tgl. ' .date('Y-m-d', strtotime());
                        // $postingJurnalTransaksi->nojurnal_intern = $noJurnalIntern;
                        // $postingJurnalTransaksi->statusenabled = 1;
                        // $postingJurnalTransaksi->norecrelated = $item->norec_sp;
                        // $postingJurnalTransaksi->jenis = $item->jenis;
                        // $postingJurnalTransaksi->save();

                        // $norec_pj = $postingJurnalTransaksi->norec;

                        // // $totalRp = $item->totalharusdibayar;
                        // $totalRekananRp = $item->totalprekanan;

                        // if ($totalRekananRp > 0) {


                        //     //debet
                        //     $postingJurnalTransaksiD = new PostingJurnalTransaksiD;
                        //     $postingJurnalTransaksiD->norec = $postingJurnalTransaksiD->generateNewId();
                        //     $postingJurnalTransaksiD->kdprofile = $kdProfile;
                        //     $postingJurnalTransaksiD->nojurnal = 0;
                        //     $postingJurnalTransaksiD->noposting = $noPosting;
                        //     $postingJurnalTransaksiD->objectaccountfk = $debetId;
                        //     $postingJurnalTransaksiD->hargasatuand = $totalRekananRp;
                        //     $postingJurnalTransaksiD->hargasatuank = 0;
                        //     $postingJurnalTransaksiD->statusenabled = 1;
                        //     $postingJurnalTransaksiD->norecrelated = $norec_pj;
                        //     $postingJurnalTransaksiD->save();

                        //     //kredit
                        //     $postingJurnalTransaksiD = new PostingJurnalTransaksiD;
                        //     $postingJurnalTransaksiD->norec = $postingJurnalTransaksiD->generateNewId();
                        //     $postingJurnalTransaksiD->kdprofile = $kdProfile;
                        //     $postingJurnalTransaksiD->nojurnal = 0;
                        //     $postingJurnalTransaksiD->noposting = $noPosting;
                        //     $postingJurnalTransaksiD->objectaccountfk = $kreditId;
                        //     $postingJurnalTransaksiD->hargasatuand = 0;
                        //     $postingJurnalTransaksiD->hargasatuank = $totalRekananRp;
                        //     $postingJurnalTransaksiD->statusenabled = 1;
                        //     $postingJurnalTransaksiD->norecrelated = $norec_pj;
                        //     $postingJurnalTransaksiD->save();
                        // }

                    }
                }
            }
            //  }

            //############################################################################################################################################
            //##################################################### DEPOSIT ##############################################################################
            //############################################################################################################################################
            // TODO : Jurnal Deposit
            //DELETE JIKA DI TABEL TRANSAKSI SUDAH TIDAK ADA

            //  dd($aingDiskon);
            // return $postingJurnalTransaksiD;
            $transStatus = 'true';
        } catch (\Exception $e) {
            $transStatus = 'false';
        }

        $transMessage = "Posting";

        if ($transStatus == 'true') {
            $transMessage = $transMessage . " Jurnal Berhasil!!";
            DB::commit();
            $result = array(
                "status" => 200,
                "result" => array(
                    "count" => count($aingMacan),


                    "as" => '@epic'
                )
            );
        } else {
            $transMessage = $transMessage . "Gagal!!";
            DB::rollBack();
            $result = array(
                "status" => 400,
                "result" => array(
                    "count" => count($aingMacan),

                    "as" => '@epic',
                    "e" => $e->getMessage() . '' . $e->getLine()
                )
            );
            //"Don't Stop When You're Tired, Stop When You're Done"
        }
        return $this->respond($result['result'], $result['status'], $transMessage);
    }
    public function getStatusClosePeriksa(Request $request)
    {
        $kdProfile = (int) $this->kdProfile;
        $key = $request->key;
        $data = PasienDaftar::where('noregistrasi', $key)->orWhere('norec', $key)->where('kdprofile', $kdProfile)->first();
        $status = false;
        $tgl = null;
        $verifikasiTagihan = false;
        if (!empty($data) && $data->isclosing != null) {
            $status = $data->isclosing;
            $tgl = $data->tglclosing;
        }
        if (!empty($data) && $data->verifikasitagihanfk != null) {
            $verifikasiTagihan = true;
        }
        $result = [
            'status' => $status,
            'tglclosing' => $tgl,
            'isverifkeuangan' => $verifikasiTagihan,
            'message' => 'success@epic',
        ];
        return $this->respond($result);
    }
    public function getPenjunjangClosing(Request $request)
    {
        $data = DB::table('strukorder_t as so')
            ->leftjoin('ruangan_m as ruAs', 'so.objectruanganfk', 'ruAs.id')
            ->leftjoin('ruangan_m as ruTu', 'so.objectruangantujuanfk', 'ruTu.id')
            ->leftjoin('pasiendaftar_t as pd', 'pd.norec', 'so.noregistrasifk')
            ->leftjoin('pasien_m as pas', 'pd.nocmfk', 'pas.id')
            ->where('so.noregistrasifk', $request->noregistrasi)
            ->where('so.statusorder', '!=', 1)
            ->select('so.norec as so_norec', 'pas.namapasien', 'so.noorder', 'ruTu.namaruangan as namaruangan')
            ->get();
        return $this->respond($data);
    }
}
