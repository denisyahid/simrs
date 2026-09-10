<?php

namespace App\Http\Controllers\Registrasi;


use App\Http\Controllers\Controller;
use App\Models\Master\Agama;
use App\Models\Master\AsalRujukan;
use App\Models\Master\Departemen;
use App\Models\Master\GolonganDarah;
use App\Models\Master\JenisKelamin;
use App\Models\Master\JenisPegawai;
use App\Models\Master\JenisPelayanan;
use App\Models\Master\Kebangsaan;
use App\Models\Master\Kelas;
use App\Models\Master\KelompokPasien;
use App\Models\Master\Negara;
use App\Models\Master\Pasien;
use App\Models\Master\Pegawai;
use App\Models\Master\Pekerjaan;
use App\Models\Master\Pendidikan;
use App\Models\Master\Ruangan;
use App\Models\Master\StatusPerkawinan;
use App\Models\Master\Suku;
use App\Models\Master\TempatTidur;
use App\Models\Transaksi\AksesEMR;
use App\Models\Transaksi\OrderPelayanan;
use App\Models\Transaksi\StrukOrder;
use App\Models\Transaksi\AntrianPasienDiperiksa;
use App\Models\Transaksi\AntrianPasienRegistrasi;
use App\Models\Transaksi\BatalRegistrasi;
use App\Models\Transaksi\PasienDaftar;
use App\Models\Transaksi\PelayananPasienDetail;
use App\Models\Transaksi\PelayananPasienPetugas;
use App\Models\Transaksi\PelayananPasien;
use App\Models\Transaksi\StrukResep;
use App\Traits\Valet;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
class RegistrasiRuanganCtrl extends Controller
{
    use Valet;
    public function __construct()
    {
        parent::__construct($is_encrypt = true);
    }
    public function pasienRegistrasi(Request $r)
    {
        $data = DB::table('pasien_m as ps')
            ->join('alamat_m as alm', 'alm.nocmfk', '=', 'ps.id')
            ->join('jeniskelamin_m as jk', 'jk.id', '=', 'ps.objectjeniskelaminfk')
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
                'ps.objectkebangsaanfk'
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
        if (isset($r['norec_pd']) && $r['norec_pd'] != '' && isset($r['norec_apd']) && $r['norec_apd'] != '') {
            $registrasi = DB::table('pasiendaftar_t as pd')
                ->join('antrianpasiendiperiksa_t as apd', 'apd.noregistrasifk', '=', 'pd.norec')
                ->join('ruangan_m as ru', 'ru.id', '=', 'apd.objectruanganfk')
                ->leftjoin('kelas_m as kls', 'kls.id', '=', 'apd.objectkelasfk')
                ->leftjoin('kamar_m as kmr', 'kmr.id', '=', 'apd.objectkamarfk')
                ->leftjoin('tempattidur_m as tt', 'tt.id', '=', 'apd.nobed')
                ->join('asalrujukan_m as ar', 'ar.id', '=', 'pd.asalrujukanfk')
                ->join('kelompokpasien_m as kps', 'kps.id', '=', 'pd.objectkelompokpasienlastfk')
                ->leftjoin('rekanan_m as rk', 'rk.id', '=', 'pd.objectrekananfk')
                ->leftjoin('pegawai_m as pg', 'pg.id', '=', 'apd.objectpegawaifk')
                ->join('jenispelayanan_m as jpl', 'jpl.id', '=', 'pd.jenispelayanan')
                ->select(
                    'pd.norec as norec_pd',
                    'pd.noregistrasi',
                    'pd.tglregistrasi',
                    'pd.objectruanganlastfk',
                    'pd.nocmfk',
                    'ru.namaruangan',
                    'pd.objectkelasfk',
                    'pd.objectkelasrawatfk',
                    'kls.namakelas',
                    'apd.objectkamarfk',
                    'kmr.namakamar',
                    'apd.nobed',
                    'pd.asalrujukanfk',
                    'ar.asalrujukan',
                    'pd.objectkelompokpasienlastfk',
                    'kps.kelompokpasien',
                    'pd.objectrekananfk',
                    'rk.namarekanan',
                    'pd.jenispelayanan as jenispelayananfk',
                    'jpl.jenispelayanan',
                    'pd.objectpegawaifk',
                    'pd.objectruanganlastfk',
                    'pg.namalengkap as dokter',
                    'ru.objectdepartemenfk',
                    'tt.reportdisplay as tempattidur',
                    'pd.catatan',
                    'pd.statuspasien',
                    'pd.iskelastitip',
                    'tt.nomorbed',
                    'apd.israwatgabung'
                )
                ->where('pd.norec', $r['norec_pd'])
                ->where('pd.statusenabled', true)
                ->where('apd.statusenabled', true)
                ->where('apd.norec', $r['norec_apd'])
                ->where('pd.kdprofile', (int) $this->kdProfile)
                ->first();
        }
        $result['pasien'] = $data;
        $result['registrasi'] = $registrasi;
        $result['idDepartemenRI'] = explode(',', $this->settingFix('kdDepartemenRanapFix'));
        $result['idDepartemenIGD'] = explode(',', $this->settingFix('idDepartemenIGD'));
        $result['idDepartemenRJ'] = explode(',', $this->settingFix('kdDepartemenRawatJalanFix'));
        $result['idKelompokPasienBPJS'] = explode(',', $this->settingFix('idKelompokPasienBPJS'));
        $result['idKelompokPasienUMUM'] = $this->settingFix('idKelompokPasienUMUM');

        $ru =  Ruangan::mine()->select(
            '*', 'namaruangan as nama')->get();
        $result['ruangan_RI'] = [];
        $result['ruangan_RJ'] = [];
        foreach ($ru as $item) {
            foreach ($result['idDepartemenRI']  as $ri) {
                if ($item->objectdepartemenfk == (int)$ri) {
                    $result['ruangan_RI'][]  = $item;
                }
            }
            foreach ($result['idDepartemenRJ']  as $rj) {
                if ($item->objectdepartemenfk == (int)$rj) {
                    $result['ruangan_RJ'][]  = $item;
                }
            }
            // foreach ($result['idDepartemenIGD']  as $igd) {
            //     if ($item->objectdepartemenfk == (int)$igd) {
            //         $result['ruangan_RJ'][]  = $item;
            //     }
            // }
        }

        if ($registrasi != null) {
            foreach ($result['idDepartemenRI']  as $ri) {
                $registrasi->israwatinap = false;
                if ($registrasi->objectdepartemenfk == $ri) {
                    $registrasi->israwatinap = true;
                    break;
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

    public function pasienRegistrasiRev(Request $r)
    {
        $data = DB::table('pasien_m as ps')
        ->join('alamat_m as alm', 'alm.nocmfk', '=', 'ps.id')
        ->join('jeniskelamin_m as jk', 'jk.id', '=', 'ps.objectjeniskelaminfk')
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
            'ps.objectkebangsaanfk',
            'ps.pekerjaanpenangggungjawab',
            'ps.umurpenanggungjawab',
            'ps.jeniskelaminpenanggungjawab',
            'ps.telponpenanggungjawab',
            'ps.hubungankeluargapj',
            'ps.penanggungjawab',
            'ps.alamatrmh',
            'ps.nocmpj',
            'ps.jeniskelaminpenanggungjawab'
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
        if (isset($r['norec_pd']) && $r['norec_pd'] != '' && isset($r['norec_apd']) && $r['norec_apd'] != '') {
            $registrasi = DB::table('pasiendaftar_t as pd')
                ->join('antrianpasiendiperiksa_t as apd', 'apd.noregistrasifk', '=', 'pd.norec')
                ->join('ruangan_m as ru', 'ru.id', '=', 'apd.objectruanganfk')
                ->leftjoin('kelas_m as kls', 'kls.id', '=', 'apd.objectkelasfk')
                ->leftjoin('kamar_m as kmr', 'kmr.id', '=', 'apd.objectkamarfk')
                ->leftjoin('tempattidur_m as tt', 'tt.id', '=', 'apd.nobed')
                ->join('asalrujukan_m as ar', 'ar.id', '=', 'pd.asalrujukanfk')
                ->join('kelompokpasien_m as kps', 'kps.id', '=', 'pd.objectkelompokpasienlastfk')
                ->leftjoin('rekanan_m as rk', 'rk.id', '=', 'pd.objectrekananfk')
                ->leftjoin('pegawai_m as pg', 'pg.id', '=', 'apd.objectpegawaifk')
                ->join('jenispelayanan_m as jpl', 'jpl.id', '=', 'pd.jenispelayanan')
                ->select(
                    'pd.norec as norec_pd',
                    'pd.noregistrasi',
                    'pd.tglregistrasi',
                    'pd.objectruanganlastfk',
                    'pd.nocmfk',
                    'pd.objectkelasfk',
                    'pd.asalrujukanfk',
                    'pd.objectkelompokpasienlastfk',
                    'pd.objectrekananfk',
                    'pd.jenispelayanan as jenispelayananfk',
                    'pd.objectpegawaifk',
                    'pd.catatan',
                    'pd.statuspasien',

                    'apd.objectkamarfk',
                    'apd.nobed',
                    'apd.israwatgabung',
                    'apd.kelasrawatfk',

                    'ru.namaruangan',
                    'kls.namakelas',
                    'kmr.namakamar',
                    'ar.asalrujukan',
                    'kps.kelompokpasien',
                    'rk.namarekanan',
                    'jpl.jenispelayanan',
                    'pg.namalengkap as dokter',
                    'ru.objectdepartemenfk',
                    'tt.reportdisplay as tempattidur',
                    'tt.nomorbed'
                )
                ->where('pd.norec', $r['norec_pd'])
                ->where('apd.norec', $r['norec_apd'])
                ->where('pd.statusenabled', true)
                ->where('apd.statusenabled', true)
                ->where('pd.kdprofile', (int) $this->kdProfile)
                ->first();
        }

        $result['pasien'] = $data;
        $result['registrasi'] = $registrasi;
        return $this->respond($result);
    }

    public function listRuanganRJRI(Request $r)
    {
        try {
            $result['idDepartemenRI'] = explode(',', $this->settingFix('kdDepartemenRanapFix'));
            $result['idDepartemenIGD'] = explode(',', $this->settingFix('idDepartemenIGD'));
            $result['idDepartemenRJ'] = explode(',', $this->settingFix('kdDepartemenRawatJalanFix'));
            $ru =  Ruangan::mine()->select(
                '*', 'namaruangan as nama')
                ->get();
            $result['ruangan_RI'] = [];
            $result['ruangan_RJ'] = [];

            foreach ($ru as $item) {
                foreach ($result['idDepartemenRI']  as $ri) {
                    if ($item->objectdepartemenfk == (int)$ri) {
                        $result['ruangan_RI'][]  = $item;
                    }
                }
                foreach ($result['idDepartemenRJ']  as $rj) {
                    if ($item->objectdepartemenfk == (int)$rj) {
                        $result['ruangan_RJ'][]  = $item;
                    }
                }
            }
        }catch(\Exception $e) {
            $result['code'] = 500;
            $result['message'] = $e->getMessage(). ' ' . $e->getLine();
            $result['data'] = null;
        }

        return $this->respond($result);
    }

    public function listKelompokPasienAll()
    {
        $result['idKelompokPasienBPJS'] = explode(',', $this->settingFix('idKelompokPasienBPJS'));
        $result['idKelompokPasienUMUM'] = $this->settingFix('idKelompokPasienUMUM');
        $result['kelompokpasien'] = KelompokPasien::mine()->get();
        $result['kelas'] = Kelas::mine()->get();
        return $this->respond($result);
    }

    public function asalRujukanPasien()
    {
        $result['jenispelayanan'] = JenisPelayanan::mine()->get();
        $result['asalrujukan'] = AsalRujukan::mine()->get();
        return $this->respond($result);
    }

    public function listRuanganRJ(Request $r)
    {

        try {
            $result['idDepartemenRJ'] = explode(',', $this->settingFix('kdDepartemenRawatJalanFix'));
            $ru = Ruangan::mine()->select(
                '*', 'namaruangan as nama')
                ->when(!empty($r['query']), function($q) use($r) {
                    return $q->where('namaruangan', 'ilike', '%'.$r['query'].'%');
                })
                ->limit(25)
                ->get();
            if(isset($r['iskontrol']) && $r['iskontrol'] == 'true') {
                foreach ($ru as $item) {
                    foreach ($result['idDepartemenRJ']  as $rj) {
                        if ($item->objectdepartemenfk == (int)$rj) {
                            $result['ruangan_RJ'][]  = $item;
                        }
                    }
                }
            }else {
                $result['ruangan_RJ'] = $ru;
            }
        }catch(\Exception $e) {
            $result['code'] = 500;
            $result['message'] = $e->getMessage(). ' ' . $e->getLine();
            $result['data'] = null;
        }

        return $this->respond($result);
    }

    public function listRuanganRJSemua(Request $r)
    {

        try {
            $result['idDepartemenRJ'] = explode(',', $this->settingFix('kdDepartemenRawatJalanFix'));
            $ru = Ruangan::mine()->select(
                '*', 'namaruangan as nama')
                ->when(!empty($r['query']), function($q) use($r) {
                    return $q->where('namaruangan', 'ilike', '%'.$r['query'].'%');
                })
                ->get();
            $result['ruangan_RJ'] = $ru;
            // foreach ($ru as $item) {
            //     foreach ($result['idDepartemenRJ']  as $rj) {
            //         if ($item->objectdepartemenfk == (int)$rj) {
            //             $result['ruangan_RJ'][]  = $item;
            //         }
            //     }
            // }
        }catch(\Exception $e) {
            $result['code'] = 500;
            $result['message'] = $e->getMessage(). ' ' . $e->getLine();
            $result['data'] = null;
        }

        return $this->respond($result);
    }

    public function listDokterPagingKontrol(Request $r)
    {
        $result['kdPsikologDokter'] = explode(',', $this->settingFix('kdPsikologDokter'));
        $result['dokter'] =
            Pegawai::mine()
            ->whereIn('objectjenispegawaifk', $result['kdPsikologDokter'])
            ->select(
                '*', 'namalengkap as nama')
            ->search($r['name'])
            ->paging($r['limit'])
            ->get();
        return $this->respond($result);
    }

    public function listDokterPagingWeb(Request $r)
    {
        $result['kdPsikologDokter'] = explode(',', $this->settingFix('kdPsikologDokter'));
        $result['dokter'] =  DB::table('pegawai_m as pg')
            ->join('mappegawaitoruangan_m as mpr', 'pg.id', '=', 'mpr.objectpegawaifk')
            ->whereIn('pg.objectjenispegawaifk', $result['kdPsikologDokter'])
            ->select(
                'pg.*', 'pg.namalengkap as nama')
            ->limit($r['limit']);

        if(isset($r['poli']) && $r['poli'] != null && $r['poli'] != "undefined" ){
            $result['dokter'] = $result['dokter']->where('mpr.objectruanganfk', '=', $r['poli']);
        }

        if(isset($r['search']) && $r['search'] != null && $r['search'] != "undefined") {
            $result['dokter'] = $result['dokter']->where('pg.namalengkap', 'ilike', '%'.$r['search'].'%');
        }

        $result['dokter'] = $result['dokter']->get();
        return $this->respond($result);
    }

    public function listKelasByRuangan(Request $r)
    {
        $result = DB::table('mapruangantokelas_m as mrk')
            ->join('kelas_m as kl', 'kl.id', '=', 'mrk.objectkelasfk')
            ->select('kl.id', 'kl.namakelas')
            ->where('mrk.objectruanganfk', $r['id'])
            ->where('mrk.kdprofile',  $this->kdProfile)
            ->where('mrk.statusenabled', true)
            ->where('kl.statusenabled', true)
            ->orderBy('kl.namakelas')
            ->get();

        return $this->respond($result);
    }

    public function listKamarByKelas(Request $r)
    {
        $result['kamar'] = DB::table('kamar_m as kmr')
            ->join('ruangan_m as ru', 'ru.id', '=', 'kmr.objectruanganfk')
            ->join('kelas_m as kl', 'kl.id', '=', 'kmr.objectkelasfk')
            ->select(
                'kmr.id',
                'kmr.namakamar',
                'kl.id as id_kelas',
                'kl.namakelas',
                'ru.id as id_ruangan',
                'ru.namaruangan',
                'kmr.jumlakamarisi',
                'kmr.qtybed'
            )
            ->where('kmr.objectruanganfk', $r['idRuangan'])
            ->where('kmr.objectkelasfk', $r['id'])
            ->where('kmr.statusenabled', true)
            ->where('kmr.kdprofile', $this->kdProfile)
            ->orderBy('kmr.namakamar')
            ->get();

        $result['idStatusBedKosong'] = explode(',', $this->settingFix('idStatusBedKosong'));
        $tt = DB::table('tempattidur_m')
            ->select('id', 'reportdisplay', 'nomorbed', 'objectkamarfk')
            ->where('kdprofile', $this->kdProfile)
            ->where('statusenabled', true)
            ->where('objectstatusbedfk', $result['idStatusBedKosong'])
            ->orderBy('reportdisplay')
            ->get();

        $kamar = [];
        if (isset($r['isRG']) && $r['isRG'] != 'undefined' && $r['isRG'] == 'false') {
            for ($i = count($result['kamar']) - 1; $i >= 0; $i--) {
                $id =   $result['kamar'][$i]->id;
                $result['kamar'][$i]->details = [];
                foreach ($tt as $itemTT) {
                    if ($itemTT->objectkamarfk == $id) {
                        $result['kamar'][$i]->details[] = $itemTT;
                    }
                }
                foreach ($tt as $itemTT) {
                    if ($itemTT->objectkamarfk == $id) {
                        $kamar[] =  $result['kamar'][$i];
                        break;
                    }
                }
            }
        } else {
            for ($i = count($result['kamar']) - 1; $i >= 0; $i--) {
                // $id =   $result['kamar'][$i]->id;
                // $result['kamar'][$i]->details = [];
                // foreach ($tt as $itemTT) {
                //     if ($itemTT->objectkamarfk == $id) {
                //         $result['kamar'][$i]->details[] = $itemTT;
                //     }
                // }
                 $kamar[] = $result['kamar'][$i];

            }
        }
        return $this->respond($kamar);
    }

    public function listKelasDetailRegis(Request $r)
    {
        $result['kelasKamar'] = DB::table('kamar_m as kmr')
            ->join('ruangan_m as ru', 'ru.id', '=', 'kmr.objectruanganfk')
            ->join('kelas_m as kl', 'kl.id', '=', 'kmr.objectkelasfk')
            ->select('kl.id', 'kl.namakelas')
            ->where('kmr.id', $r['kamarfk'])
            ->where('kmr.objectkelasfk', $r['kelasfk'])
            ->where('kmr.statusenabled', true)
            ->where('kmr.kdprofile', $this->kdProfile)
            ->orderBy('kmr.namakamar')
            ->first();

        $result['listKelasKamar'] = DB::table('mapruangantokelas_m as mrk')
            ->join('kelas_m as kl', 'kl.id', '=', 'mrk.objectkelasfk')
            ->select('kl.id', 'kl.namakelas')
            ->where('mrk.objectruanganfk', $r['ruanganfk'])
            ->where('mrk.kdprofile',  $this->kdProfile)
            ->where('mrk.statusenabled', true)
            ->where('kl.statusenabled', true)
            ->orderBy('kl.namakelas')
            ->get();

        return $this->respond($result);
    }

    public function listPenjaminByKelompokPasien(Request $r)
    {
        $result = DB::table('mapkelompokpasientopenjamin_m as mkp')
            ->join('rekanan_m as rk', 'rk.id', '=', 'mkp.kdpenjaminpasien')
            ->select('rk.id', 'rk.namarekanan')
            ->where('mkp.objectkelompokpasienfk', $r['id'])
            ->where('mkp.statusenabled', true)
            ->where('rk.statusenabled', true)
            ->distinct()
            ->where('mkp.kdprofile', (int)$this->kdProfile)
            ->orderBy('rk.namarekanan')
            ->get();
        return $this->respond($result);
    }

    public function getAsalRujukanID(Request $r) {
        $asalRujukan = DB::table('asalrujukan_m')
            ->where('asalrujukan', $r->input('asalrujukan'))
            ->value('id'); // Retrieve only the ID
    
            $result = array(
                'data' => $asalRujukan,
                'message' => 'test',
            );

            return $this->respond($result);
    }

    public function saveRegistrasi(Request $r)
    {
        if ((isset($r['pasiendaftar']['isjkn']) && $r['pasiendaftar']['isjkn'] == true)) {
        } else {
        DB::beginTransaction();
        }
        try {

            //region Save
            $kdProfile = $this->kdProfile;
            $PD = $r['pasiendaftar'];
            $APD = $r['antrianpasiendiperiksa'];
            $tglAyeuna = date('Y-m-d H:i:s');

            $data = DB::table('pasiendaftar_t as pd')
            ->join('ruangan_m as ru','pd.objectruanganlastfk','ru.id')
            ->leftjoin('pasien_m as ps', 'ps.id', '=', 'pd.nocmfk')
            ->select('ru.namaruangan', 'ru.id', 'pd.objectkelompokpasienlastfk', 'pd.tglclosing')
            ->where('pd.statusenabled', true)
            ->where('pd.nocmfk', $PD['nocmfk'])
            ->where('pd.kdprofile', $this->kdProfile)
            ->whereIn('ru.objectdepartemenfk', [16,9,18])
            ->whereDate('pd.tglregistrasi', Carbon::today())
            ->first();

            // if (!empty($data) && $PD['norec'] == '') {
            //     $transMessage = "Pasien sudah terregistrasi hari ini ke " . $data->namaruangan;
            //     DB::rollBack();
            //     $result = array("status" => 400, "result"  => $data);
            //     return $this->respond($result['result'], $result['status'], $transMessage);
            // }

            try {
                $datareservasi = AntrianPasienRegistrasi::where('objectruanganfk', $PD['objectruanganlastfk'])
                ->where('nocmfk', $PD['nocmfk'])
                ->where('tanggalreservasi', '>=', date('Y-m-d', strtotime($tglAyeuna)) . ' 00:00')
                ->where('tanggalreservasi', '<=', date('Y-m-d', strtotime($tglAyeuna)) . ' 23:59')
                ->where('statusenabled', true)
                ->get();
            } catch(\Exception $err) {
                $datareservasi = [];
            }

            $ruangan = Ruangan::where('id', '=', $PD['objectruanganlastfk'])->first();
            $pegawai = DB::table('pegawai_m')->where('id', $PD['objectpegawaifk'])->first();

            $cekDepartemen = Ruangan::where('statusenabled',true)->where('kdprofile',$this->kdProfile)
                                    ->where('id',$PD['objectruanganlastfk'])
                                    ->first();
            $isIGD = $cekDepartemen->objectdepartemenfk == $this->settingFix('idDepartemenIGD') ? 'true' : 'false';

            $cekRI = PasienDaftar::where('nocmfk', $PD['nocmfk'])
                ->whereNull('tglpulang')
                ->where('statusenabled', true)
                ->first();
            if (!empty($cekRI) && $PD['norec'] == '' && $PD['objectruanganlastfk'] != 751) {
                DB::rollBack();
                $transMessage = 'Pasien belum dipulangkan dg No. Registrasi : '
                    . $cekRI->noregistrasi . ' (' . $cekRI->tglregistrasi . ')';
                $result = array("status" => 400, "result"  => $cekRI);
                return $this->respond($result['result'], $result['status'], $transMessage);
            }
            $cekStatus = PasienDaftar::where('nocmfk', $PD['nocmfk'])
                ->whereNotNull('tglmeninggal')
                ->where('statusenabled', true)
                ->first();
            // if (!empty($cekStatus) && $PD['norec'] == '') {
            //     DB::rollBack();
            //     $transMessage = 'Pasien sudah Meninggal : '
            //         . $cekStatus->noregistrasi . ' (' . $cekStatus->tglmeninggal . ')';
            //     $result = array("status" => 400, "result"  => $cekStatus);
            //     return $this->respond($result['result'], $result['status'], $transMessage);
            // }
            $SET['idNonKelas'] = (int) $this->settingFix('idNonKelas');
            $SET['idJenisPelayananEksek'] = (int) $this->settingFix('idJenisPelayananEksek');
            $SET['idKelasIPKKU']= (int) $this->settingFix('idKelasIPKKU');


            $penjamin = null;
            if($PD['objectrekananfk'] == null){
                if($PD['objectkelompokpasienlastfk'] == 1){
                    $penjamin = 0;
                } else{
                    $rekanan = DB::select(DB::raw("select * from mapkelompokpasientopenjamin_m where objectkelompokpasienfk = ".$request['kelompokpasienfk']));
                    $penjamin = $rekanan[0]->kdpenjaminpasien;
                }
            } else{
                $penjamin = $PD['objectrekananfk'];
            }

            if ($PD['norec'] == '') {
                $noregistrasi = $this->SEQUENCE(new PasienDaftar, 'noregistrasi', 10, date('ymd'), $kdProfile);
                $noAntrian = 0;
                if ($noregistrasi == '') {
                    abort(400, 'SEQ ERROR');
                }
                $model_PD = new PasienDaftar();
                $model_PD->norec = $model_PD->generateNewId();
                $model_PD->kdprofile = $kdProfile;
                $model_PD->statusenabled = true;
                $model_PD->objectruanganasalfk = $PD['objectruanganlastfk'];
                $model_PD->statuspasien = $PD['statuspasien'];
                $namaLog = 'Tambah Registrasi ke Ruang ' . $ruangan->namaruangan . ' ';
                if ($PD['israwatinap'] == 'true') {
                    $model_PD->tglpulang = null;
                }
            } else {
                $model_PD =  PasienDaftar::where('norec', $PD['norec'])->first();
                $noregistrasi = $model_PD->noregistrasi;
                $namaLog = 'Edit Registrasi ke Ruang ' . $ruangan->namaruangan . ' ';
            }

            // Penjadwalan Kemoterapi
            if ($PD['objectruanganlastfk'] == 237) {
                $cek_jk = \DB::table('pasiendaftar_t as pd')
                    ->join('penjadwalan_t as pj', 'pj.nocmfk', '=', 'pd.nocmfk')
                    ->where('pd.statusenabled', true)
                    ->where('pd.nocmfk', $PD['nocmfk'])
                    ->where('pj.verif_farmasi', true)
                    ->where('pj.verif_kemoterapi', true)
                    ->whereDate('pj.tglpenjadwalan', date('Y-m-d'))
                    ->orderBy('pj.tglpenjadwalan', 'desc')
                    ->select('pj.*')
                    ->first();
            } else {
                $cek_jk = null;
            }

            $model_PD->objectruanganlastfk = $PD['objectruanganlastfk'];
            $model_PD->objectpegawaifk =  $PD['objectpegawaifk'];
            $model_PD->objectpegawairawatbersamafk =  isset($PD['objectpegawairawatbersamafk'])?$PD['objectpegawairawatbersamafk']:null;
            // $model_PD->jenispelayananfk =   $PD['jenispelayananfk'];
            $model_PD->jenispelayanan =   $PD['jenispelayananfk'];
            if ($PD['israwatinap'] == 'true') {
                $model_PD->objectkelasfk = $PD['objectkelasfk'];
                $model_PD->objectkelasrawatfk = $PD['objectkelasrawatfk'];
                $model_PD->tglpulang = null;
            } else {

                if($PD['jenispelayananfk'] == $SET['idJenisPelayananEksek'] ){
                    $model_PD->objectkelasfk =  $SET['idKelasIPKKU'];
                }else{
                    $model_PD->objectkelasfk =  $SET['idNonKelas'];
                }
                // $model_PD->tglpulang =  $isIGD == 'true' ? null : $PD['tglregistrasi'];
            }
            $model_PD->tglpulang = null;
            $model_PD->objectkelompokpasienlastfk = $PD['objectkelompokpasienlastfk'];
            $model_PD->nocmfk = $PD['nocmfk'];
            $model_PD->objectrekananfk = $penjamin;
            // $model_PD->statuspasien = $isLama == 0 ? 'BARU' : 'LAMA';
            $model_PD->ismobilejkn = isset($PD['isjkn']) ? $PD['isjkn'] : null;
            $model_PD->antrianpasienregistrasifk = isset($PD['antrianpasienregistrasifk']) ? $PD['antrianpasienregistrasifk'] : null;
            $model_PD->noreservasi = isset($PD['noreservasi']) ? $PD['noreservasi'] : null;
            $model_PD->tglregistrasi =  $PD['tglregistrasi'];
            $model_PD->asalrujukanfk =  $PD['asalrujukanfk'];
            $model_PD->keteranganasalrujukan = $PD['asalrujukanfk'] == 5 ? null : (isset($PD['keteranganasalrujukan'])?$PD['keteranganasalrujukan']:null);
            $model_PD->noregistrasi = $noregistrasi;
            $model_PD->petugas = $this->getNamaPegawai();
            $model_PD->iskiosk = isset($PD['iskiosk']) ? $PD['iskiosk'] : null;
            $model_PD->iskelastitip = isset($PD['iskelastitip']) ? $PD['iskelastitip'] : null;
            $model_PD->ispenjadwalankemoterapi = isset($cek_jk) ? true : null;
            if($PD['objectruangantujuanfk'] == 322 && isset($PD['objectpegawaifk'])) {
                $model_PD->objectpegawaifk_dod = $PD['objectpegawaifk'];
            }

            if(isset($PD['antrianpasienregistrasifk']) &&( isset($PD['statusschedule']) && $PD['statusschedule'] !='Kios-K')){
                $reserv = AntrianPasienRegistrasi::where('noreservasi', $PD['statusschedule'])->first();
                if(!empty($reserv)){
                    $model_PD->antrianpasienregistrasifk = $reserv->norec;
                }
            }

            $model_PD->save();

            if ($model_PD->antrianpasienregistrasifk != null) {
                AntrianPasienRegistrasi::where('norec', $model_PD->antrianpasienregistrasifk)
                    ->update(['isconfirm' => true, 'pasiendaftarfk' => $model_PD->norec]);
            }
            if ($PD['israwatinap'] == 'true') {
                $SET['idStatusBedKosong'] = $this->settingFix('idStatusBedKosong');
                $SET['idStatusBedIsi'] = $this->settingFix('idStatusBedIsi');
            }

            if ($APD['norec'] == '') {

                //var_dump('Halo masuk sini');

                try {
                    $maxnoantriansimrslama = AntrianPasienRegistrasi::where('objectruanganfk', $PD['objectruanganlastfk'])
                    ->where('tanggalreservasi', '>=', date('Y-m-d', strtotime($tglAyeuna)) . ' 00:00')
                    ->where('tanggalreservasi', '<=', date('Y-m-d', strtotime($tglAyeuna)) . ' 23:59')
                    ->where('statusenabled', true)
                    ->max('noantrianpoli');
                } catch(\Exception $errAntri) {
                    $maxnoantriansimrslama = null;
                }

                $max = AntrianPasienDiperiksa::where('objectruanganfk', $PD['objectruanganlastfk'])
                    // ->where('tglregistrasi', '>=', date('2024-10-07 00:00'))
                    // ->where('tglregistrasi', '<=', date('2024-10-07 23:59'))
                    ->where('tglregistrasi', '>=', date('Y-m-d', strtotime($PD['tglregistrasi'])) . ' 00:00')
                    ->where('tglregistrasi', '<=', date('Y-m-d', strtotime($PD['tglregistrasi'])) . ' 23:59')
                    ->where('statusenabled', true)
                    ->max('noantrian');

                    //$noAntrian = $max + 1;

                    if(count($datareservasi) != 0){
                        $noAntrian = $datareservasi[0]->noantrianpoli;
                    } else{
                        // var_dump('Halo masuk situ');
                        if($maxnoantriansimrslama != null){
                            if($maxnoantriansimrslama > $max){
                                $noAntrian = $maxnoantriansimrslama + 1;
                            } else{
                                $noAntrian = $max + 1;
                            }
                        } else{
                            $noAntrian = $max + 1;
                        }
                    }

                    // var_dump($maxnoantriansimrslama);
                    // var_dump($max);
                    // var_dump($noAntrian);

                $model_APD = new AntrianPasienDiperiksa;
                $model_APD->norec = $model_APD->generateNewId();
                $model_APD->kdprofile = (int)$kdProfile;
                $model_APD->statusenabled = true;
                $model_APD->noantrian = $noAntrian;
            } else {
                $model_APD =  AntrianPasienDiperiksa::where('norec', $APD['norec'])->first();
                if ($PD['objectruanganlastfk'] != $model_APD->objectruanganfk) {

                    $maxnoantriansimrslama = AntrianPasienRegistrasi::where('objectruanganfk', $PD['objectruanganlastfk'])
                    ->where('tanggalreservasi', '>=', date('Y-m-d', strtotime($tglAyeuna)) . ' 00:00')
                    ->where('tanggalreservasi', '<=', date('Y-m-d', strtotime($tglAyeuna)) . ' 23:59')
                    ->where('statusenabled', true)
                    ->max('noantrianpoli');

                $max = AntrianPasienDiperiksa::where('objectruanganfk', $PD['objectruanganlastfk'])
                    // ->where('tglregistrasi', '>=', date('2024-10-07 00:00'))
                    // ->where('tglregistrasi', '<=', date('2024-10-07 23:59'))
                    ->where('tglregistrasi', '>=', date('Y-m-d', strtotime($PD['tglregistrasi'])) . ' 00:00')
                    ->where('tglregistrasi', '<=', date('Y-m-d', strtotime($PD['tglregistrasi'])) . ' 23:59')
                    ->where('statusenabled', true)
                    ->max('noantrian');

                    //$noAntrian = $max + 1;

                    if(count($datareservasi) != 0){
                        $noAntrian = $datareservasi[0]->noantrianpoli;
                    } else{
                        if($maxnoantriansimrslama != null){
                            if($maxnoantriansimrslama > $max){
                                $noAntrian = $maxnoantriansimrslama + 1;
                            } else{
                                $noAntrian = $max + 1;
                            }
                        } else{
                            $noAntrian = $max + 1;
                        }
                    }

                    // var_dump($maxnoantriansimrslama);
                    // var_dump($max);
                    // var_dump($noAntrian);

                    $model_APD->noantrian = $noAntrian;
                }
                if ($PD['israwatinap'] == 'true') {
                    DB::table('tempattidur_m')
                        ->where('id', $model_APD->nobed)
                        ->lockForUpdate()
                        ->update(['objectstatusbedfk' =>  $SET['idStatusBedKosong']]);
                    // TempatTidur::where('id', $model_APD->nobed)->update();
                }
            }

            $model_APD->objectasalrujukanfk =  $PD['asalrujukanfk'];
            $model_APD->objectkamarfk = $APD['objectkamarfk'];
            $model_APD->objectruanganfk = $PD['objectruanganlastfk'];
            if ($PD['israwatinap'] == 'true') {
                $model_APD->objectkelasfk = $PD['objectkelasfk'];
                $model_APD->kelasrawatfk = $PD['objectkelasrawatfk'];
                $model_APD->tglkeluar = null;
            } else {

                if($PD['jenispelayananfk'] == $SET['idJenisPelayananEksek'] ){
                    $model_APD->objectkelasfk =  $SET['idKelasIPKKU'];
                }else{
                    $model_APD->objectkelasfk =  $SET['idNonKelas'];
                }
                $model_APD->tglkeluar = $isIGD == 'true' ? $PD['tglregistrasi'] : $PD['tglregistrasi'];
            }
            $model_APD->nobed = $APD['nobed'];
            $model_APD->noregistrasifk = $model_PD->norec;
            $model_APD->objectpegawaifk =  $PD['objectpegawaifk'];
            $model_APD->statusantrian = 0;
            $model_APD->status = "Belum Dipanggil";
            $model_APD->statuskunjungan = $PD['statuspasien'];
            $model_APD->tglregistrasi =  $PD['tglregistrasi'];
            $model_APD->tglmasuk = $PD['tglregistrasi'];
            $model_APD->israwatgabung = isset($APD['israwatgabung']) ? $APD['israwatgabung'] : false;
            $model_APD->nojkn = isset($PD['isjkn']) ? $PD['nojkn'] : null;
            $model_APD->noregistrasi = $noregistrasi;
            $model_APD->save();

            $AksesEMRExsist = AksesEMR::where('statusenabled',true)->where('kdprofile',$this->kdProfile)->where('pasienfk', $PD['nocmfk'])->first();
            $nextDay = $PD['israwatinap'] || $isIGD == 'true' ? null : date('Y-m-d', strtotime('+1 day', strtotime(date('Y-m-d'))));

            if($AksesEMRExsist){
                 AksesEMR::where('statusenabled',true)->where('kdprofile',$this->kdProfile)->where('pasienfk', $PD['nocmfk'])
                        ->update([
                            'pegawaipenerimafk' =>is_int($this->getPegawaiId()) ?$this->getPegawaiId() : 1,
                            'tglmulai' =>  date('Y-m-d'),
                            'tglberakhir' =>  $nextDay,
                            'deskripsi' => 'Akses EMR Dibuka dari Registrasi Pasien',
                            'objectkelompokuserfk' => '',
                            'pegawaipemohonfk' => null,
                        ]);
            }else{
                $aksesEMR = new AksesEMR();
                $aksesEMR->norec = $aksesEMR->generateNewId();
                $aksesEMR->statusenabled = true;
                $aksesEMR->kdprofile = $this->kdProfile;
                $aksesEMR->pegawaipenerimafk =is_int($this->getPegawaiId()) ?$this->getPegawaiId() : 1;
                $aksesEMR->pasienfk = $PD['nocmfk'];
                $aksesEMR->tglmulai = date('Y-m-d');
                $aksesEMR->objectkelompokuserfk = '';
                $aksesEMR->tglberakhir = $nextDay;
                $aksesEMR->deskripsi = 'Akses EMR Dibuka dari Registrasi Pasien';
                $aksesEMR->save();
            }


            if ($PD['israwatinap'] == 'true') {
                $cek = DB::table('tempattidur_m')
                ->where('kdprofile', $this->kdProfile)
                ->where('statusenabled', true)
                ->where('id',  $APD['nobed'])
                ->first();

                if (!empty($cek) && $cek->objectstatusbedfk == $SET['idStatusBedIsi'] ) {
                    DB::rollBack();
                    $transMessage = 'Bed Sudah Terisi, Silakan Pilih Bed Lain';
                    $result = array("status" => 400 ,'message' => $transMessage, "result"  => $cek);
                    return $this->respond($result, $result['status'], $transMessage);
                }

                DB::table('tempattidur_m')
                    ->where('kdprofile', $this->kdProfile)
                    ->where('statusenabled', true)
                    ->where('id',  $APD['nobed'])
                    ->lockForUpdate()
                    ->update(['objectstatusbedfk' =>  $SET['idStatusBedIsi']]);

                $this->historyBED([
                    "tempattidurfk" =>  $APD['nobed'],
                    "statusbedfk" => $SET['idStatusBedIsi'],
                    "ruanganfk" => $PD['objectruanganlastfk'],
                    "kamarfk" => $APD['objectkamarfk'],
                ]);
            }

            //endregion
            $ps = Pasien::where('id', $PD['nocmfk'])->first();
            $ps->statusemr = null;
            $ps->save();

            // Pasien::where('id',$PD['nocmfk'])->where('kdprofile',$this->kdProfile)->where('statusenabled',true)->update(['statusemr' => null]);

            $this->LOGGING(
                'Registrasi Pasien',
                $model_PD->norec,
                'pasiendaftar_t',
                $namaLog . ' pada Pasien ' .  $ps->namapasien . ' (' . $ps->nocm . ') - ' . $noregistrasi
            );

            $transStatus = 'true';
        } catch (\Exception $e) {
            $transStatus = 'false';
        }

        if ($transStatus == 'true') {
            $responseTelem = null;
            $transMessage = "Sukses";
            if ((isset($r['pasiendaftar']['isjkn']) && $r['pasiendaftar']['isjkn'] == true)) {
            } else {
            DB::commit();
            }
            $aplicare = null;
            if ($PD['israwatinap'] == 'true' || $PD['israwatinap'] == true) {
                $objetoRequest = new \Illuminate\Http\Request();
                $objetoRequest['idruangan'] = $model_APD->objectruanganfk;
                $objetoRequest['idkelas'] = $model_APD->objectkelasfk;
                $aplicare = app('App\Http\Controllers\Bridging\BridgingBPJSCtrl')->updateAplicaresBedAfter($objetoRequest);
            }
            // if ($PD['norec'] == '' && $PD['israwatinap'] == false) {
            //     $ruangan = Ruangan::where('id', $model_PD->objectruanganlastfk)->first();
            //     $dataJsonSend = array(
            //         'nocm' => $ps->nocm,
            //         'namapasien' => $ps->namapasien,
            //         'namaruangan' => $ruangan->namaruangan,
            //         'idruangan' => (string) $model_PD->objectruanganlastfk,
            //         'noantrian' =>(string) $model_APD->noantrian
            //     );
            //     $url = $this->settingFix('urlTelemedicine') . "my-queues";

            //     $headers['Content-Type']  = 'application/json';

            //     $resTel = Http::withHeaders($headers)
            //     ->withoutVerifying()
            //     ->withOptions(["verify" => false])
            //     ->post($url, $dataJsonSend);
            //     $responseTelem = $resTel->json();
            // }

            $ihs = null;
            $objetoRequest = new \Illuminate\Http\Request();
            $objetoRequest['noregistrasi'] = $model_PD->noregistrasi;
            $ihs = app('App\Http\Controllers\Bridging\SATUSEHATCtrl')->Encounter($objetoRequest, true);
            $result = array(
                "status" => 200,
                "result" => array(
                    "dataPD" => $model_PD,
                    "dataAPD" => $model_APD,
                    "registrasi"  => array(
                        "pd" => $model_PD,
                        "apd" => $model_APD,#
                        "nocm" => $ps->nocm,
                    ),
                    'Encounter' => $ihs,
                    'Aplicare' => $aplicare,
                    // "queue_telemedicine" => $responseTelem,
                    "as" => '@epic',
                ),
            );
        } else {
            $transMessage = "Simpan Gagal";
            if ((isset($r['pasiendaftar']['isjkn']) && $r['pasiendaftar']['isjkn'] == true)) {
                Log::info("Simpan JKN gagal ". $e->getMessage(). " ". $e->getLine() );
            } else {
            DB::rollBack();
            }
            $result = array(
                "status" => 400,
                "result"  => $e->getMessage() . ' '.$e->getline()
            );
        }
        return $this->respond($result['result'], $result['status'], $transMessage);
    }

    public function editJenisPembayaran(Request $request)
    {
        DB::beginTransaction();
        try {
            $PD = $request['pasiendaftar'];
            $penjamin = null;

            if($PD['objectrekananfk'] == null){
                if($PD['objectkelompokpasienlastfk'] == 1){
                    $penjamin = 0;
                } else{
                    $rekanan = DB::select(DB::raw("select * from mapkelompokpasientopenjamin_m where objectkelompokpasienfk = ".$request['kelompokpasienfk']));
                    $penjamin = $rekanan[0]->kdpenjaminpasien;
                }
            } else{
                $penjamin = $PD['objectrekananfk'];
            }

            PasienDaftar::where('norec', $PD['norec'])->update([
                'objectkelompokpasienlastfk' => $PD['objectkelompokpasienlastfk'],
                'objectrekananfk' => $penjamin
            ]);

            $dataPasien = DB::table('pasiendaftar_t as pd')
                ->join('pasien_m as ps', 'ps.id', 'pd.nocmfk')
                ->join('kelompokpasien_m as kp', 'kp.id', 'pd.objectkelompokpasienlastfk')
                ->select('ps.namapasien', 'pd.noregistrasi', 'ps.nocm', 'kp.reportdisplay as kelompokpasien')
                ->where('pd.kdprofile', $this->kdProfile)
                ->where('pd.statusenabled', true)
                ->where('pd.norec', $PD['norec'])
                ->first();

            $this->LOGGING(
                'Edit Jenis Pembayaran Pasien',
                $PD['norec'],
                'pasiendaftar_t',
                'Edit Jenis Pembayaran pasien atas nama ' . $dataPasien->namapasien . ' (' .  $dataPasien->nocm  . ') ' .
                ' menjadi ' . $dataPasien->kelompokpasien . ' - '. $dataPasien->noregistrasi
            );

            DB::commit();
            $result = array(
                "status" => 200,
                "message" => 'Berhasil',
                "result" => $dataPasien
            );
        } catch (Exception $e) {
            DB::rollBack();
            $result = [
                "status" => 400,
                "message" => "Something Went Wrong",
                "result"  => $e->getMessage() . $e->getLine()
            ];
        }
        return $this->respond($result['result'], $result['status'], $result['message']);
    }

    public function saveRegistrasiNuklir(Request $r)
    {
        if ((isset($r['pasiendaftar']['isjkn']) && $r['pasiendaftar']['isjkn'] == true)) {
        } else {
        DB::beginTransaction();
        }
        try {
            //region Save
            $kdProfile = $this->kdProfile;
            $idProfile = $this->kdProfile;
            $PD = $r['pasiendaftar'];
            $APD = $r['antrianpasiendiperiksa'];
            $SO = $r['objSave'];

            $cekDepartemen = Ruangan::where('statusenabled',true)->where('kdprofile',$this->kdProfile)
                                    ->where('id',$PD['objectruanganlastfk'])
                                    ->first();
            $isIGD = $cekDepartemen->objectdepartemenfk == $this->settingFix('idDepartemenIGD') ? 'true' : 'false';

            $cekRI = PasienDaftar::where('nocmfk', $PD['nocmfk'])
                ->whereNull('tglpulang')
                ->where('statusenabled', true)
                ->first();
            // if (!empty($cekRI) && $PD['norec'] == '' && $PD['objectruanganlastfk'] != 751) {
            //     DB::rollBack();
            //     $transMessage = 'Pasien belum dipulangkan dg No. Registrasi : '
            //         . $cekRI->noregistrasi . ' (' . $cekRI->tglregistrasi . ')';
            //     $result = array("status" => 400, "result"  => $cekRI);
            //     return $this->respond($result['result'], $result['status'], $transMessage);
            // }
            $cekStatus = PasienDaftar::where('nocmfk', $PD['nocmfk'])
                ->whereNotNull('tglmeninggal')
                ->where('statusenabled', true)
                ->first();

            $SET['idNonKelas'] = (int) $this->settingFix('idNonKelas');
            $SET['idJenisPelayananEksek'] = (int) $this->settingFix('idJenisPelayananEksek');
            $SET['idKelasIPKKU']= (int) $this->settingFix('idKelasIPKKU');


            $penjamin = null;
            if($PD['objectrekananfk'] == null){
                if($PD['objectkelompokpasienlastfk'] == 1){
                    $penjamin = 0;
                } else{
                    $rekanan = DB::select(DB::raw("select * from mapkelompokpasientopenjamin_m where objectkelompokpasienfk = ".$request['kelompokpasienfk']));
                    $penjamin = $rekanan[0]->kdpenjaminpasien;
                }
            } else{
                $penjamin = $PD['objectrekananfk'];
            }


            if ($PD['norec'] == '') {
                $noregistrasi = $this->SEQUENCE(new PasienDaftar, 'noregistrasi', 10, date('ymd'), $kdProfile);
                $noAntrian = 0;
                if ($noregistrasi == '') {
                    abort(400, 'SEQ ERROR');
                }
                $model_PD = new PasienDaftar();
                $model_PD->norec = $model_PD->generateNewId();
                $model_PD->kdprofile = $kdProfile;
                $model_PD->statusenabled = true;
                $model_PD->objectruanganasalfk = $PD['objectruanganlastfk'];
                $model_PD->statuspasien = $PD['statuspasien'];
                $namaLog = 'Tambah Registrasi ke Ruang ' . Ruangan::mine()->where('id', $PD['objectruanganlastfk'])->first()->namaruangan . ' ';
                if ($PD['israwatinap'] == 'true') {
                    $model_PD->tglpulang = null;
                }
            } else {
                $model_PD =  PasienDaftar::where('norec', $PD['norec'])->first();
                $noregistrasi = $model_PD->noregistrasi;
                $namaLog = 'Edit Registrasi ke Ruang ' . Ruangan::mine()->where('id', $PD['objectruanganlastfk'])->first()->namaruangan . ' ';
            }
            $model_PD->objectruanganlastfk = $PD['objectruanganlastfk'];
            $model_PD->objectpegawaifk =  $PD['objectpegawaifk'];
            $model_PD->objectpegawairawatbersamafk =  isset($PD['objectpegawairawatbersamafk'])?$PD['objectpegawairawatbersamafk']:null;
            $model_PD->jenispelayanan =   $PD['jenispelayananfk'];
            if ($PD['israwatinap'] == 'true') {
                $model_PD->objectkelasfk = $PD['objectkelasfk'];
                $model_PD->objectkelasrawatfk = $PD['objectkelasrawatfk'];
                $model_PD->tglpulang = null;
            } else {

                if($PD['jenispelayananfk'] == $SET['idJenisPelayananEksek'] ){
                    $model_PD->objectkelasfk =  $SET['idKelasIPKKU'];
                }else{
                    $model_PD->objectkelasfk =  $SET['idNonKelas'];
                }
                $model_PD->tglpulang =  $isIGD == 'true' ? null : $PD['tglregistrasi'];
            }
            $model_PD->objectkelompokpasienlastfk = $PD['objectkelompokpasienlastfk'];
            $model_PD->nocmfk = $PD['nocmfk'];
            $model_PD->objectrekananfk = $penjamin;
            $model_PD->ismobilejkn = isset($PD['isjkn']) ? $PD['isjkn'] : null;
            $model_PD->antrianpasienregistrasifk = isset($PD['antrianpasienregistrasifk']) ? $PD['antrianpasienregistrasifk'] : null;
            $model_PD->noreservasi = isset($PD['noreservasi']) ? $PD['noreservasi'] : null;
            $model_PD->tglregistrasi =  $PD['tglregistrasi'];
            $model_PD->asalrujukanfk =  $PD['asalrujukanfk'];
            $model_PD->keteranganasalrujukan = $PD['asalrujukanfk'] == 5 ? null : (isset($PD['keteranganasalrujukan'])?$PD['keteranganasalrujukan']:null);
            $model_PD->noregistrasi = $noregistrasi;
            $model_PD->petugas = $this->getNamaPegawai();
            $model_PD->iskiosk = isset($PD['iskiosk']) ? $PD['iskiosk'] : null;

            if(isset($PD['antrianpasienregistrasifk']) &&( isset($PD['statusschedule']) && $PD['statusschedule'] !='Kios-K')){
                $reserv = AntrianPasienRegistrasi::where('noreservasi', $PD['statusschedule'])->first();
                if(!empty($reserv)){
                    $model_PD->antrianpasienregistrasifk = $reserv->norec;
                }
            }

            $model_PD->save();

            if ($model_PD->antrianpasienregistrasifk != null) {
                AntrianPasienRegistrasi::where('norec', $model_PD->antrianpasienregistrasifk)
                    ->update(['isconfirm' => true, 'pasiendaftarfk' => $model_PD->norec]);
            }
            if ($PD['israwatinap'] == 'true') {
                $SET['idStatusBedKosong'] = $this->settingFix('idStatusBedKosong');
                $SET['idStatusBedIsi'] = $this->settingFix('idStatusBedIsi');
            }

            if ($APD['norec'] == '') {
                $max = AntrianPasienDiperiksa::where('objectruanganfk', $PD['objectruanganlastfk'])
                    ->where('tglregistrasi', '>=', date('Y-m-d', strtotime($PD['tglregistrasi'])) . ' 00:00')
                    ->where('tglregistrasi', '<=', date('Y-m-d', strtotime($PD['tglregistrasi'])) . ' 23:59')
                    ->where('statusenabled', true)
                    ->max('noantrian');
                $noAntrian = $max + 1;

                $model_APD = new AntrianPasienDiperiksa;
                $model_APD->norec = $model_APD->generateNewId();
                $model_APD->kdprofile = (int)$kdProfile;
                $model_APD->statusenabled = true;
                $model_APD->noantrian = $noAntrian;
            } else {
                $model_APD =  AntrianPasienDiperiksa::where('norec', $APD['norec'])->first();
                if ($PD['objectruanganlastfk'] != $model_APD->objectruanganfk) {
                    $max = AntrianPasienDiperiksa::where('objectruanganfk', $PD['objectruanganlastfk'])
                        ->where('tglregistrasi', '>=', date('Y-m-d', strtotime($PD['tglregistrasi'])) . ' 00:00')
                        ->where('tglregistrasi', '<=', date('Y-m-d', strtotime($PD['tglregistrasi'])) . ' 23:59')
                        ->where('statusenabled', true)
                        ->max('noantrian');
                    $noAntrian = $max + 1;
                    $model_APD->noantrian = $noAntrian;
                }
                if ($PD['israwatinap'] == 'true') {
                    DB::table('tempattidur_m')
                        ->where('id', $model_APD->nobed)
                        ->lockForUpdate()
                        ->update(['objectstatusbedfk' =>  $SET['idStatusBedKosong']]);
                }
            }

            $model_APD->objectasalrujukanfk =  $PD['asalrujukanfk'];
            $model_APD->objectkamarfk = $APD['objectkamarfk'];
            $model_APD->objectruanganfk = $PD['objectruanganlastfk'];
            if ($PD['israwatinap'] == 'true') {
                $model_APD->objectkelasfk = $PD['objectkelasfk'];
                $model_APD->kelasrawatfk = $PD['objectkelasrawatfk'];
                $model_APD->tglkeluar = null;
            } else {

                if($PD['jenispelayananfk'] == $SET['idJenisPelayananEksek'] ){
                    $model_APD->objectkelasfk =  $SET['idKelasIPKKU'];
                }else{
                    $model_APD->objectkelasfk =  $SET['idNonKelas'];
                }
                $model_APD->tglkeluar = $isIGD == 'true' ? null : $PD['tglregistrasi'];
            }
            $model_APD->nobed = $APD['nobed'];
            $model_APD->noregistrasifk = $model_PD->norec;
            $model_APD->objectpegawaifk =  $PD['objectpegawaifk'];
            $model_APD->statusantrian = 0;
            $model_APD->status = "Belum Dipanggil";
            $model_APD->statuskunjungan = $PD['statuspasien'];
            $model_APD->tglregistrasi =  $PD['tglregistrasi'];
            $model_APD->tglmasuk = $PD['tglregistrasi'];
            $model_APD->israwatgabung = isset($APD['israwatgabung']) ? $APD['israwatgabung'] : false;
            $model_APD->nojkn = isset($PD['isjkn']) ? $PD['nojkn'] : null;
            $model_APD->noregistrasi = $noregistrasi;
            $model_APD->save();

            $AksesEMRExsist = AksesEMR::where('statusenabled',true)->where('kdprofile',$this->kdProfile)->where('pasienfk', $PD['nocmfk'])->first();
            $nextDay = $PD['israwatinap'] || $isIGD == 'true' ? null : date('Y-m-d', strtotime('+1 day', strtotime(date('Y-m-d'))));

            if($AksesEMRExsist){
                 AksesEMR::where('statusenabled',true)->where('kdprofile',$this->kdProfile)->where('pasienfk', $PD['nocmfk'])
                        ->update([
                            'pegawaipenerimafk' =>is_int($this->getPegawaiId()) ?$this->getPegawaiId() : 1,
                            'tglmulai' =>  date('Y-m-d'),
                            'tglberakhir' =>  $nextDay,
                            'deskripsi' => 'Akses EMR Dibuka dari Registrasi Pasien',
                            'objectkelompokuserfk' => '',
                            'pegawaipemohonfk' => null,
                        ]);
            }else{
                $aksesEMR = new AksesEMR();
                $aksesEMR->norec = $aksesEMR->generateNewId();
                $aksesEMR->statusenabled = true;
                $aksesEMR->kdprofile = $this->kdProfile;
                $aksesEMR->pegawaipenerimafk =is_int($this->getPegawaiId()) ?$this->getPegawaiId() : 1;
                $aksesEMR->pasienfk = $PD['nocmfk'];
                $aksesEMR->tglmulai = date('Y-m-d');
                $aksesEMR->objectkelompokuserfk = '';
                $aksesEMR->tglberakhir = $nextDay;
                $aksesEMR->deskripsi = 'Akses EMR Dibuka dari Registrasi Pasien';
                $aksesEMR->save();
            }


            if ($PD['israwatinap'] == 'true') {
                $cek = DB::table('tempattidur_m')
                ->where('kdprofile', $this->kdProfile)
                ->where('statusenabled', true)
                ->where('id',  $APD['nobed'])
                ->first();

                if (!empty($cek) && $cek->objectstatusbedfk == $SET['idStatusBedIsi'] ) {
                    DB::rollBack();
                    $transMessage = 'Bed Sudah Terisi, Silakan Pilih Bed Lain';
                    $result = array("status" => 400 ,'message' => $transMessage, "result"  => $cek);
                    return $this->respond($result, $result['status'], $transMessage);
                }

                DB::table('tempattidur_m')
                    ->where('kdprofile', $this->kdProfile)
                    ->where('statusenabled', true)
                    ->where('id',  $APD['nobed'])
                    ->lockForUpdate()
                    ->update(['objectstatusbedfk' =>  $SET['idStatusBedIsi']]);

                $this->historyBED([
                    "tempattidurfk" =>  $APD['nobed'],
                    "statusbedfk" => $SET['idStatusBedIsi'],
                    "ruanganfk" => $PD['objectruanganlastfk'],
                    "kamarfk" => $APD['objectkamarfk'],
                ]);
            }

            //endregion
            $ps = Pasien::where('id', $PD['nocmfk'])->first();

            Pasien::where('id',$PD['nocmfk'])->where('kdprofile',$this->kdProfile)->where('statusenabled',true)->update(['statusemr' => null]);

            $this->LOGGING(
                'Registrasi Pasien',
                $model_PD->norec,
                'pasiendaftar_t',
                $namaLog . ' pada Pasien ' .  $ps->namapasien . ' (' . $ps->nocm . ') - ' . $noregistrasi
            );

            $cekDepartemen2 = Ruangan::where('statusenabled',true)->where('kdprofile',$this->kdProfile)
                                    ->where('id',$SO['objectruangantujuanfk'])
                                    ->first();

            $setting = json_decode($this->settingFix('settingSeqNoOrder'));
            $noOrder = '';
            $ket = '';
            $kelompokTransaksi = null;

            foreach ($setting as $set) {
                if ($cekDepartemen2->objectdepartemenfk  == $set->objectdepartemenfk) {
                    $noOrder = $this->SEQUENCE(new StrukOrder(), $set->seqname, 11, $SO['prefix'] . date('ym'), $idProfile);
                    $ket = $set->desc;
                    $kelompokTransaksi = $set->kelompoktransfk;
                    break;
                }
            }
            if ($noOrder == '') {
                $transMessage = "Gagal mengumpukan data, Coba lagi.!";
                DB::rollBack();
                $result = array("status" => 400, "result"  => null);
                return $this->respond($result['result'], $result['status'], $transMessage);
            }


            $noOrder = '';
            $noOrder = $this->SEQUENCE(new StrukOrder(), $set->seqname, 11, $SO['prefix'] . date('ym'), $idProfile);

            $dataSO = new StrukOrder;
            $dataSO->norec = $dataSO->generateNewId();
            $dataSO->kdprofile = $idProfile;
            $dataSO->statusenabled = true;
            $dataSO->nocmfk = $model_PD->nocmfk;
            $typeLog = "Tambah";

            $dataSO->isdelivered = 1;
            $dataSO->noorder = $noOrder;
            $dataSO->noorderintern = $noOrder;
            $dataSO->noregistrasifk = $model_PD->norec;
            $dataSO->objectpegawaiorderfk = $SO['pegawaiorderfk'];
            $dataSO->jenisradionuklidafk = $SO['radionuklidafk'] ? $SO['radionuklidafk'] : null;
            $dataSO->jenisfarmakafk = $SO['farmakafk'] ? $SO['farmakafk'] : null;
            $dataSO->qtyjenisproduk = 1;
            $dataSO->qtyproduk = $SO['qtyproduk'];
            $dataSO->objectruanganfk = $model_PD->objectruanganlastfk;
            $dataSO->objectruangantujuanfk = $SO['objectruangantujuanfk'];
            $dataSO->keteranganorder = $SO['keterangan'];
            $dataSO->objectkelompoktransaksifk = $kelompokTransaksi;
            $dataSO->tglpelayananakhir = $SO['tanggal'];
            $dataSO->tglpelayananawal = $SO['tanggal'];
            $dataSO->tglorder = $SO['tanggal'];
            $dataSO->bb = $SO['bb'];
            $dataSO->tb = $SO['tb'];
            $dataSO->tglrencanaterapi = $SO['tglrencanaterapi'] ? $SO['tglrencanaterapi'] : null;
            $dataSO->totalbeamaterai = 0;
            $dataSO->statusorder = 0;
            $dataSO->totalbiayakirim = 0;
            $dataSO->totalbiayatambahan = 0;
            $dataSO->totaldiscount = 0;
            $dataSO->totalhargasatuan = 0;
            $dataSO->totalharusdibayar = 0;
            $dataSO->totalpph = 0;
            $dataSO->totalppn = 0;
            $dataSO->cito = $SO['iscito'];
            $dataSO->catatanklinis = $SO['catatanKlinis'];
            $dataSO->catatanterapiradioaktif = $SO['catatanterapi'] ? $SO['catatanterapi'] : null;
            $dataSO->terapiiodium = $SO['terapiIodium'] ? $SO['terapiIodium'] : null;
            $dataSO->terapiradiofarmaka = $SO['terapiRadiofarmaka'] ? $SO['terapiRadiofarmaka'] : null;
            $dataSO->terapiradioaktif = $SO['kesimpulanterapi'] ? $SO['kesimpulanterapi'] : null;
            $dataSO->catatanfarmaka = $SO['catatanfarmaka'] ? $SO['catatanfarmaka'] : null;
            $dataSO->noregistrasi = $model_PD->noregistrasi;
            $dataSO->norec_apd = $model_APD->norec;

            $dataSO->save();

            $dataSOnorec = $dataSO->norec;

            foreach ($SO['details'] as $item) {
                $dataOP = new OrderPelayanan();
                $dataOP->norec = $dataOP->generateNewId();
                $dataOP->kdprofile = $idProfile;
                $dataOP->statusenabled = true;
                $dataOP->iscito = $SO['iscito'];
                $dataOP->noorderfk = $dataSOnorec;
                $dataOP->objectprodukfk = $item['produkfk'];
                $dataOP->qtyproduk = $item['qtyproduk'];
                $dataOP->objectkelasfk = 2;
                $dataOP->qtyprodukretur = 0;
                $dataOP->objectruanganfk = $model_PD->objectruanganlastfk;
                $dataOP->objectruangantujuanfk = $SO['objectruangantujuanfk'];
                $dataOP->strukorderfk = $dataSOnorec;
                $dataOP->tglpelayanan = $model_PD->tglregistrasi;
                $dataOP->nourut = $item['nourut'];
                $dataOP->noregistrasi = $model_PD->noregistrasi;
                $dataOP->save();
            }



            $transStatus = 'true';
        } catch (\Exception $e) {
            $transStatus = 'false';
        }

        if ($transStatus == 'true') {
            $responseTelem = null;
            $transMessage = "Sukses";
            if ((isset($r['pasiendaftar']['isjkn']) && $r['pasiendaftar']['isjkn'] == true)) {
            } else {
            DB::commit();
            }
            $aplicare = null;
            if ($PD['israwatinap'] == 'true' || $PD['israwatinap'] == true) {
                $objetoRequest = new \Illuminate\Http\Request();
                $objetoRequest['idruangan'] = $model_APD->objectruanganfk;
                $objetoRequest['idkelas'] = $model_APD->objectkelasfk;
                $aplicare = app('App\Http\Controllers\Bridging\BridgingBPJSCtrl')->updateAplicaresBedAfter($objetoRequest);
            }

            $ihs = null;
            $objetoRequest = new \Illuminate\Http\Request();
            $objetoRequest['noregistrasi'] = $model_PD->noregistrasi;
            $ihs = app('App\Http\Controllers\Bridging\SATUSEHATCtrl')->Encounter($objetoRequest, true);
            $result = array(
                "status" => 200,
                "result" => array(
                    "dataPD" => $model_PD,
                    "dataAPD" => $model_APD,
                    "registrasi"  => array(
                        "pd" => $model_PD,
                        "apd" => $model_APD,#
                        "nocm" => $ps->nocm,
                    ),
                    'Encounter' => $ihs,
                    'Aplicare' => $aplicare,
                    "as" => '@epic',
                ),
            );
        } else {
            $transMessage = "Simpan Gagal";
            if ((isset($r['pasiendaftar']['isjkn']) && $r['pasiendaftar']['isjkn'] == true)) {
                Log::info("Simpan JKN gagal ". $e->getMessage(). " ". $e->getLine() );
            } else {
            DB::rollBack();
            }
            $result = array(
                "status" => 400,
                "result"  => $e->getMessage() . ' '.$e->getline()
            );
        }
        return $this->respond($result['result'], $result['status'], $transMessage);
    }

    public function saveOrderPenunjangNuklir (Request $r){

        $kdProfile = $this->kdProfile;
        $idProfile = $this->kdProfile;
        $PD = $r['pasiendaftar'];
        $APD = $r['antrianpasiendiperiksa'];
        $SO = $r['objSave'];
        $SET['idNonKelas'] = (int) $this->settingFix('idNonKelas');
        $SET['idJenisPelayananEksek'] = (int) $this->settingFix('idJenisPelayananEksek');
        $SET['idKelasIPKKU']= (int) $this->settingFix('idKelasIPKKU');
        $PD_VALUE=DB::table('pasiendaftar_t')->where('norec',$PD['norec_pd'])->where('statusenabled',true)->first();
        $ps = Pasien::where('id', $PD['nocmfk'])->where('statusenabled',true)->first();
        $cekDepartemen = Ruangan::where('statusenabled',true)->where('kdprofile',$this->kdProfile)
                                ->where('id',$PD_VALUE->objectruanganlastfk)
                                ->first();
        $isIGD = $cekDepartemen->objectdepartemenfk == $this->settingFix('idDepartemenIGD') ? 'true' : 'false';

        try{
            if ($APD['norec'] == '') {
                $max = AntrianPasienDiperiksa::where('objectruanganfk', $PD_VALUE->objectruanganlastfk)
                    ->where('tglregistrasi', '>=', date('Y-m-d', strtotime($PD_VALUE->tglregistrasi)) . ' 00:00')
                    ->where('tglregistrasi', '<=', date('Y-m-d', strtotime($PD_VALUE->tglregistrasi)) . ' 23:59')
                    ->where('statusenabled', true)
                    ->max('noantrian');
                $noAntrian = $max + 1;

                $model_APD = new AntrianPasienDiperiksa;
                $model_APD->norec = $model_APD->generateNewId();
                $model_APD->kdprofile = (int)$kdProfile;
                $model_APD->statusenabled = true;
                $model_APD->noantrian = $noAntrian;
            } else {
                $model_APD =  AntrianPasienDiperiksa::where('norec', $APD['norec'])->first();
                if ($PD_VALUE->objectruanganlastfk != $model_APD->objectruanganfk) {
                    $max = AntrianPasienDiperiksa::where('objectruanganfk', $PD_VALUE->objectruanganlastfk)
                        ->where('tglregistrasi', '>=', date('Y-m-d', strtotime($PD_VALUE->tglregistrasi)) . ' 00:00')
                        ->where('tglregistrasi', '<=', date('Y-m-d', strtotime($PD_VALUE->tglregistrasi)) . ' 23:59')
                        ->where('statusenabled', true)
                        ->max('noantrian');
                    $noAntrian = $max + 1;
                    $model_APD->noantrian = $noAntrian;
                }
            }

            $model_APD->objectasalrujukanfk =  $PD_VALUE->asalrujukanfk;
            $model_APD->objectkamarfk = null;
            $model_APD->objectruanganfk = $APD['objectruanganfk'];
            // if ($PD['israwatinap'] == 'true') {
            //     $model_APD->objectkelasfk = $PD['objectkelasfk'];
            //     $model_APD->kelasrawatfk = $PD['objectkelasrawatfk'];
            //     $model_APD->tglkeluar = null;
            // } else {

            //     if($PD['jenispelayananfk'] == $SET['idJenisPelayananEksek'] ){
            //         $model_APD->objectkelasfk =  $SET['idKelasIPKKU'];
            //     }else{
            //         $model_APD->objectkelasfk =  $SET['idNonKelas'];
            //     }
            //     $model_APD->tglkeluar = $isIGD == 'true' ? null : $PD['tglregistrasi'];
            // }
            if($PD_VALUE->jenispelayananfk == $SET['idJenisPelayananEksek'] ){
                $model_APD->objectkelasfk =  $SET['idKelasIPKKU'];
            }else{
                $model_APD->objectkelasfk =  $SET['idNonKelas'];
            }
            $model_APD->tglkeluar = $isIGD == 'true' ? null : $PD['tglregistrasi'];
            $model_APD->nobed = null;
            $model_APD->noregistrasifk = $PD_VALUE->norec;
            $model_APD->objectpegawaifk =  $PD['objectpegawaifk'];
            $model_APD->statusantrian = 0;
            $model_APD->status = "Belum Dipanggil";
            $model_APD->statuskunjungan = $PD_VALUE->statuspasien;
            $model_APD->tglregistrasi =  $PD_VALUE->tglregistrasi;
            $model_APD->tglmasuk = $PD['tglregistrasi'];
            $model_APD->israwatgabung = isset($APD['israwatgabung']) ? $APD['israwatgabung'] : false;
            $model_APD->nojkn = isset($PD_VALUE->isjkn) ? $PD['nojkn'] : null;
            $model_APD->noregistrasi = $PD_VALUE->noregistrasi;
            $model_APD->save();

            $cekDepartemen2 = Ruangan::where('statusenabled',true)->where('kdprofile',$this->kdProfile)
            ->where('id',$SO['objectruangantujuanfk'])
            ->first();

            $setting = json_decode($this->settingFix('settingSeqNoOrder'));
            $noOrder = '';

            foreach ($setting as $set) {
                if ($cekDepartemen2->objectdepartemenfk  == $set->objectdepartemenfk) {
                    $noOrder = $this->SEQUENCE(new StrukOrder(), $set->seqname, 11, $SO['prefix'] . date('ym'), $idProfile);
                    $ket = $set->desc;
                    $kelompokTransaksi = $set->kelompoktransfk;
                    break;
                }
            }
            if ($noOrder == '') {
                $transMessage = "Gagal mengumpukan data, Coba lagi.!";
                DB::rollBack();
                $result = array("status" => 400, "result"  => null);
                return $this->respond($result['result'], $result['status'], $transMessage);
            }


            $noOrder = '';
            $noOrder = $this->SEQUENCE(new StrukOrder(), $set->seqname, 11, $SO['prefix'] . date('ym'), $idProfile);

            $dataSO = new StrukOrder;
            $dataSO->norec = $dataSO->generateNewId();
            $dataSO->kdprofile = $idProfile;
            $dataSO->statusenabled = true;
            $dataSO->nocmfk = $PD_VALUE->nocmfk;
            $typeLog = "Tambah";

            $dataSO->isdelivered = 1;
            $dataSO->noorder = $noOrder;
            $dataSO->noorderintern = $noOrder;
            $dataSO->noregistrasifk = $PD_VALUE->norec;
            $dataSO->objectpegawaiorderfk = $SO['pegawaiorderfk'];
            $dataSO->jenisradionuklidafk = $SO['radionuklidafk'] ? $SO['radionuklidafk'] : null;
            $dataSO->jenisfarmakafk = $SO['farmakafk'] ? $SO['farmakafk'] : null;
            $dataSO->qtyjenisproduk = 1;
            $dataSO->qtyproduk = $SO['qtyproduk'];
            $dataSO->objectruanganfk = $PD_VALUE->objectruanganlastfk;
            $dataSO->objectruangantujuanfk = $SO['objectruangantujuanfk'];
            $dataSO->keteranganorder = $SO['keterangan'];
            $dataSO->objectkelompoktransaksifk = $kelompokTransaksi;
            $dataSO->tglpelayananakhir = $SO['tanggal'];
            $dataSO->tglpelayananawal = $SO['tanggal'];
            $dataSO->tglorder = $SO['tanggal'];
            $dataSO->bb = $SO['bb'];
            $dataSO->tb = $SO['tb'];
            $dataSO->tglrencanaterapi = $SO['tglrencanaterapi'] ? $SO['tglrencanaterapi'] : null;
            $dataSO->totalbeamaterai = 0;
            $dataSO->statusorder = 0;
            $dataSO->totalbiayakirim = 0;
            $dataSO->totalbiayatambahan = 0;
            $dataSO->totaldiscount = 0;
            $dataSO->totalhargasatuan = 0;
            $dataSO->totalharusdibayar = 0;
            $dataSO->totalpph = 0;
            $dataSO->totalppn = 0;
            $dataSO->cito = $SO['iscito'];
            $dataSO->catatanklinis = $SO['catatanKlinis'];
            $dataSO->catatanterapiradioaktif = $SO['catatanterapi'] ? $SO['catatanterapi'] : null;
            $dataSO->terapiiodium = $SO['terapiIodium'] ? $SO['terapiIodium'] : null;
            $dataSO->terapiradiofarmaka = $SO['terapiRadiofarmaka'] ? $SO['terapiRadiofarmaka'] : null;
            $dataSO->terapiradioaktif = $SO['kesimpulanterapi'] ? $SO['kesimpulanterapi'] : null;
            $dataSO->catatanfarmaka = $SO['catatanfarmaka'] ? $SO['catatanfarmaka'] : null;
            $dataSO->noregistrasi = $PD_VALUE->noregistrasi;
            $dataSO->norec_apd = $model_APD->norec;

            $dataSO->save();

            $dataSOnorec = $dataSO->norec;

            foreach ($SO['details'] as $item) {
                $dataOP = new OrderPelayanan();
                $dataOP->norec = $dataOP->generateNewId();
                $dataOP->kdprofile = $idProfile;
                $dataOP->statusenabled = true;
                $dataOP->iscito = $SO['iscito'];
                $dataOP->noorderfk = $dataSOnorec;
                $dataOP->objectprodukfk = $item['produkfk'];
                $dataOP->qtyproduk = $item['qtyproduk'];
                $dataOP->objectkelasfk = 2;
                $dataOP->qtyprodukretur = 0;
                $dataOP->objectruanganfk = $PD_VALUE->objectruanganlastfk;
                $dataOP->objectruangantujuanfk = $SO['objectruangantujuanfk'];
                $dataOP->strukorderfk = $dataSOnorec;
                $dataOP->tglpelayanan = $PD_VALUE->tglregistrasi;
                $dataOP->nourut = $item['nourut'];
                $dataOP->noregistrasi = $PD_VALUE->noregistrasi;
                $dataOP->save();
            }
            $transStatus = 'true';
        }
        catch(Exception $e){
            $transStatus = 'false';
        }

        if ($transStatus == 'true') {
            $responseTelem = null;
            $transMessage = "Sukses";
            if ((isset($r['pasiendaftar']['isjkn']) && $r['pasiendaftar']['isjkn'] == true)) {
            } else {
            DB::commit();
            }
            $aplicare = null;
            if ($PD['israwatinap'] == 'true' || $PD['israwatinap'] == true) {
                $objetoRequest = new \Illuminate\Http\Request();
                $objetoRequest['idruangan'] = $model_APD->objectruanganfk;
                $objetoRequest['idkelas'] = $model_APD->objectkelasfk;
                $aplicare = app('App\Http\Controllers\Bridging\BridgingBPJSCtrl')->updateAplicaresBedAfter($objetoRequest);
            }

            $ihs = null;
            $objetoRequest = new \Illuminate\Http\Request();
            $objetoRequest['noregistrasi'] = $PD_VALUE->noregistrasi;
            $ihs = app('App\Http\Controllers\Bridging\SATUSEHATCtrl')->Encounter($objetoRequest, true);
            $result = array(
                "status" => 200,
                "result" => array(
                    // "dataPD" => $PD_VALUE,
                    "dataAPD" => $model_APD,
                    "registrasi"  => array(
                        // "pd" => $model_PD,
                        "apd" => $model_APD,#
                        "nocm" => $ps->nocm,
                        "so" => $dataSO,
                    ),
                    'Encounter' => $ihs,
                    'Aplicare' => $aplicare,
                    "as" => '@epic',
                ),
            );
        } else {
            $transMessage = "Simpan Gagal";
            if ((isset($r['pasiendaftar']['isjkn']) && $r['pasiendaftar']['isjkn'] == true)) {
                Log::info("Simpan JKN gagal ". $e->getMessage(). " ". $e->getLine() );
            } else {
            DB::rollBack();
            }
            $result = array(
                "status" => 400,
                "result"  => $e->getMessage() . ' '.$e->getline()
            );
        }
        return $this->respond($result['result'], $result['status'], $transMessage);
    }

    public function listRuanganByLoginUser(Request $r)
    {
        $result = DB::table('maploginusertoruangan_s as mkr')
            ->join('loginuser_s as lg', 'lg.id', '=', 'mkr.objectloginuserfk')
            ->join('ruangan_m as rk', 'rk.id', '=', 'mkr.objectruanganfk')
            ->select('rk.id', 'rk.namaruangan')
            ->where('lg.id', $this->getUserId())
            ->where('mkr.statusenabled', true)
            ->where('rk.statusenabled', true)
            ->distinct()
            ->where('mkr.kdprofile', $this->kdProfile)
            ->orderBy('rk.namaruangan')
            ->get();
        return $this->respond($result);
    }

    public function checkIsExsist(Request $request)
    {
        $data = DB::table('pasiendaftar_t as pd')
            ->join('ruangan_m as ru','pd.objectruanganlastfk','ru.id')
            ->leftjoin('pasien_m as ps', 'ps.id', '=', 'pd.nocmfk')
            ->select('ru.namaruangan', 'ru.id', 'pd.objectkelompokpasienlastfk', 'pd.tglclosing')
            ->where('pd.statusenabled', true)
            ->whereIn('ru.objectdepartemenfk', [16,9,18])
            // ->where('pd.objectkelompokpasienlastfk', 2)
            ->where('pd.kdprofile', $this->kdProfile)
            ->whereDate('pd.tglregistrasi', Carbon::today());

        if(isset($request['nocmfk']) && $request['nocmfk'] != null){
            $data = $data->where('pd.nocmfk', $request['nocmfk']);
        }

        if(isset($request['nocm']) && $request['nocm'] != null){
            $data = $data->where('ps.nocm', $request['nocm']);
        }

        $data = $data->orderByDesc('pd.tglregistrasi')
            // ->latest();
            ->first();
            // $data = null;
        return $this->respond($data);
    }
    public function checkIsExsistReservasi(Request $request)
    {
        $data = DB::table('pasien_m as ps')
            ->leftJOIN('pasiendaftar_t as pd', function ($join) {
                $join->on('ps.id', '=', 'pd.nocmfk')
                ->where('pd.statusenabled', true)
                ->where('pd.kdprofile', $this->kdProfile)
                ->whereDate('pd.tglregistrasi', Carbon::today());
            })
            ->leftjoin('ruangan_m as ru','pd.objectruanganlastfk','ru.id')
            ->select('ru.namaruangan', 'ps.id as nocmfk', 'pd.norec as norec_pd');


        if(isset($request['nocmfk']) && $request['nocmfk'] != null){
            $data = $data->where('pd.nocmfk', $request['nocmfk']);
        }

        if(isset($request['nocm']) && $request['nocm'] != null){
            $data = $data->where('ps.nocm', $request['nocm']);
        }

        $data = $data->orderByDesc('pd.tglregistrasi')
            // ->latest();
            ->first();

        return $this->respond($data);
    }
    public function saveAdministrasi(Request $request){

        DB::beginTransaction();
        try {
            $kdProfile = $this->kdProfile;
            $kebangsaan = $request['objectkebangsaanfk'];
            $hasil = [];
            $PelPasien = null;

             AntrianPasienDiperiksa::where('norec',$request['norec_apd'])
                ->update([
                    'ispelayananpasien' => true
                ]);
                $pasiendaftar = PasienDaftar::where('norec',$request['norec'])->first();
                $pasiendaftar->ispelayananpasien = true;
                $pasiendaftar->save();

                $pasien = Pasien::where('id',$pasiendaftar->nocmfk)->first();
                $data = DB::select(DB::raw("select pp.tglpelayanan,pd.objectkelasfk,
                    pd.objectruanganlastfk
                    from pasiendaftar_t as pd
                    INNER JOIN antrianpasiendiperiksa_t as apd on apd.noregistrasifk=pd.norec
                    INNER JOIN pelayananpasien_t as pp on pp.noregistrasifk=apd.norec
                    INNER JOIN produk_m as pr on pr.id=pp.produkfk
                    INNER JOIN ruangan_m as ru_pd on ru_pd.id=pd.objectruanganlastfk
                    where  pd.norec='$request[norec]'
                    and pd.kdprofile=$kdProfile
                    and pp.produkfk in (
                        select
                        objectprodukfk
                        from mapruangantoadministrasi_t
                        where objectruanganfk=pd.objectruanganlastfk
                        and statusenabled=true
                    )
                "));

                if (count($data) == 0) {
                    $sirahMacan = [];
                    $status = "";
                    if ($pasiendaftar->statuspasien != null) {
                        $status = $pasiendaftar->statuspasien;
                    }
                    $idpenjamin = "-1";//$pasiendaftar->objectrekananfk == null ? '-1' : $pasiendaftar->objectrekananfk;
                    if ($idpenjamin != "-1") {
                            $sirahMacan = DB::select(DB::raw("
                                    select hett.* from mapruangantoadministrasi_t as map
                                    INNER JOIN harganettoprodukbykelas_m as hett on hett.objectprodukfk=map.objectprodukfk
                                    and hett.objectjenispelayananfk =map.jenispelayananfk
                                    where map.objectruanganfk=:ruanganid and hett.objectkelasfk=:kelasid
                                    and map.jenispelayananfk=:jenispelayanan
                                    and map.kdprofile=:kdprofile
                                    and map.statusenabled=true
                                    and map.jenis=:statuspasien
                                    and hett.statusenabled=true
                                    and hett.objectpenjaminfk = $idpenjamin
                                    and hett.objectkebangsaanfk = $kebangsaan
                            "),array(
                                'ruanganid' => $pasiendaftar->objectruanganlastfk,
                                'kelasid' => $pasiendaftar->objectkelasfk,
                                'jenispelayanan' => $pasiendaftar->jenispelayanan,
                                'kdprofile' =>$kdProfile,
                                'statuspasien' => $status,
                            )
                        );
                    }else{
                        $sirahMacan = [];
                    }

                    if(count($sirahMacan) == 0){
                        $sirahMacan = DB::select(DB::raw("
                                    select hett.* from mapruangantoadministrasi_t as map
                                    INNER JOIN harganettoprodukbykelas_m as hett on hett.objectprodukfk=map.objectprodukfk
                                    and hett.objectjenispelayananfk =map.jenispelayananfk
                                    where map.objectruanganfk=:ruanganid and hett.objectkelasfk=:kelasid
                                    and map.jenispelayananfk=:jenispelayanan
                                    and map.kdprofile=:kdprofile
                                    and map.statusenabled=true
                                    and map.jenis=:statuspasien
                                    and hett.statusenabled=true
                                    and hett.objectpenjaminfk IS NULL
                                    and hett.objectkebangsaanfk = $kebangsaan
                            "),array(
                                'ruanganid' => $pasiendaftar->objectruanganlastfk,
                                'kelasid' => $pasiendaftar->objectkelasfk,
                                'jenispelayanan' => $pasiendaftar->jenispelayanan,
                                'kdprofile' =>$kdProfile,
                                'statuspasien' => $status,
                            )
                        );
                    }

                    //var_dump($sirahMacan);

                    $buntutMacan = [];
                    if ($idpenjamin != "-1") {
                        $buntutMacan = DB::select(DB::raw("
                                select hett.* from mapruangantoadministrasi_t as map
                                INNER JOIN harganettoprodukbykelasd_m as hett on hett.objectprodukfk=map.objectprodukfk
                                and hett.objectjenispelayananfk =map.jenispelayananfk
                                where map.objectruanganfk=:ruanganid and hett.objectkelasfk=:kelasid
                                and map.jenispelayananfk=:jenispelayanan
                                and map.kdprofile=:kdprofile
                                and map.statusenabled=true
                                and map.jenis=:statuspasien
                                and hett.statusenabled=true
                                and hett.objectpenjaminfk = $idpenjamin
                                and hett.objectkebangsaanfk = $kebangsaan
                                "),
                            array(
                                'ruanganid' => $pasiendaftar->objectruanganlastfk,
                                'kelasid' => $pasiendaftar->objectkelasfk,
                                'jenispelayanan' => $pasiendaftar->jenispelayanan,
                                'kdprofile' =>$kdProfile,
                                'statuspasien' => $status,
                            )
                        );
                    }else{
                        $buntutMacan = [];
                    }

                    if(count($buntutMacan) == 0){
                        $buntutMacan = DB::select(DB::raw("
                                select hett.* from mapruangantoadministrasi_t as map
                                INNER JOIN harganettoprodukbykelasd_m as hett on hett.objectprodukfk=map.objectprodukfk
                                and hett.objectjenispelayananfk =map.jenispelayananfk
                                where map.objectruanganfk=:ruanganid and hett.objectkelasfk=:kelasid
                                and map.jenispelayananfk=:jenispelayanan
                                and map.kdprofile=:kdprofile
                                and map.statusenabled=true
                                and map.jenis=:statuspasien
                                and hett.statusenabled = true
                                and hett.objectpenjaminfk IS NULL
                                and hett.objectkebangsaanfk = $kebangsaan
                                "),
                            array(
                                'ruanganid' => $pasiendaftar->objectruanganlastfk,
                                'kelasid' => $pasiendaftar->objectkelasfk,
                                'jenispelayanan' => $pasiendaftar->jenispelayanan,
                                'kdprofile' =>$kdProfile,
                                'statuspasien' => $status,
                            )
                        );
                    }
                    foreach ($sirahMacan as $k) {
                        $PelPasien = new PelayananPasien();
                        $PelPasien->norec = $PelPasien->generateNewId();
                        $PelPasien->kdprofile = $kdProfile;
                        $PelPasien->statusenabled = true;
                        $PelPasien->noregistrasifk = $request['norec_apd'];//$dataDong[0]->norec_apd;
                        $PelPasien->tglregistrasi = $pasiendaftar->tglregistrasi;
                        $PelPasien->hargadiscount = 0;//0;
                        $PelPasien->hargajual = $k->hargasatuan;
                        $PelPasien->hargasatuan = $k->hargasatuan;
                        $PelPasien->jumlah = 1;
                        $PelPasien->kelasfk = $pasiendaftar->objectkelasfk;
                        $PelPasien->kdkelompoktransaksi = 1;
                        $PelPasien->piutangpenjamin = 0;
                        $PelPasien->piutangrumahsakit = 0;
                        $PelPasien->produkfk = $k->objectprodukfk;
                        $PelPasien->stock = 1;
                        $PelPasien->tglpelayanan = date('Y-m-d H:i:s');
                        $PelPasien->harganetto = $k->harganetto1;
                        $PelPasien->isadministrasi = true;
                        $PelPasien->keteranganlain = 'administrasi otomatis';
                        $PelPasien->noregistrasi = $pasiendaftar->noregistrasi;
                        $PelPasien->objecthargaprodukfk = $k->id;
                        $PelPasien->save();
                        $PPnorec = $PelPasien->norec;
                        foreach ($buntutMacan as $index => $itemKomponen) {
                            if($itemKomponen->objectprodukfk == $k->objectprodukfk) {
                                $PelPasienDetail = new PelayananPasienDetail();
                                $PelPasienDetail->norec = $PelPasienDetail->generateNewId();
                                $PelPasienDetail->kdprofile = $kdProfile;
                                $PelPasienDetail->statusenabled = true;
                                $PelPasienDetail->noregistrasifk = $request['norec_apd'];
                                $PelPasienDetail->aturanpakai = '-';
                                $PelPasienDetail->hargadiscount = 0;
                                $PelPasienDetail->hargajual = $itemKomponen->hargasatuan;
                                $PelPasienDetail->hargasatuan = $itemKomponen->hargasatuan;
                                $PelPasienDetail->jumlah = 1;
                                $PelPasienDetail->keteranganlain = 'admin otomatis';
                                $PelPasienDetail->keteranganpakai2 = '-';
                                $PelPasienDetail->komponenhargafk = $itemKomponen->objectkomponenhargafk;
                                $PelPasienDetail->pelayananpasien = $PPnorec;
                                $PelPasienDetail->piutangpenjamin = 0;
                                $PelPasienDetail->piutangrumahsakit = 0;
                                $PelPasienDetail->produkfk = $itemKomponen->objectprodukfk;
                                $PelPasienDetail->stock = 1;
                                $PelPasienDetail->tglpelayanan = date('Y-m-d H:i:s');
                                $PelPasienDetail->harganetto = $itemKomponen->harganetto1;
                                $PelPasienDetail->noregistrasi = $pasiendaftar->noregistrasi;
                                $PelPasienDetail->save();

                                if ($index == 0) {
                                    $data1 = new PelayananPasienPetugas();
                                    $data1->norec = $data1->generateNewId();
                                    $data1->kdprofile = $kdProfile;
                                    $data1->statusenabled = true;
                                    $data1->nomasukfk = $request['norec_apd'];
                                    $data1->objectjenispetugaspefk = 2;
                                    $data1->pelayananpasien = $PPnorec;
                                    $data1->objectpegawaifk = $this->userLogin()['pegawai']['id'];
                                    $data1->noregistrasi = $request['noregistrasi'];
                                    $data1->save();
                                }
                            }

                        }
                    }

                    $hasil = $PelPasien;

                }

                if(!empty($pasien)){
                    $this->LOGGING(
                        'Administrasi Otomatis',
                        $pasiendaftar->norec,
                        'pasiendaftar_t',
                        'Administrasi Otomatis pada Pasien ' .  $pasien->namapasien . ' (' . $pasien->nocm . ') - ' . $pasiendaftar->noregistrasi
                    );
                } else{
                    if(!empty($pasien)){
                        $this->LOGGING(
                            'Administrasi Otomatis',
                            $pasiendaftar->norec,
                            'pasiendaftar_t',
                            'Administrasi Otomatis pada Pasien Baru - ' . $pasiendaftar->noregistrasi
                        );
                    }
                }



            $transMessage = "Administrasi Otomatis";
            DB::commit();
            $result = array(
                "status" => 200,
                "result" => array(
                    "data" => $hasil,
                    "as" => '@epic',
                ),
            );
        } catch (Exception $e) {
            $transMessage = "Simpan Gagal";
            DB::rollBack();
            $result = array(
                "status" => 400,
                "result"  => $e->getMessage()
            );
        }

        return $this->respond($result['result'], $result['status'], $transMessage);
    }
}
