<?php

namespace App\Http\Controllers\Registrasi;

use App\Datatrans\PasienDaftar;
use App\Http\Controllers\Controller;
use App\Http\Resources\Dashboard\Register\PasienDaftarResource;
use App\Models\Master\Pasien;
use App\Models\Master\SettingDataFixed;
use App\Models\Standar\LoginUser;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;

class RegistrasiPasienCtrl extends Controller
{
    public function getPembatalanPasien(Request $request)
    {
        $filter = $request->all();
        $data = DB::table('pasiendaftar_t as pd')
            ->join('pasien_m as ps', 'ps.id', '=', 'pd.nocmfk')
            ->leftjoin('pegawai_m as pg', 'pg.id', '=', 'pd.objectpegawaibatalfk')
            ->join('ruangan_m as r', 'r.id', '=', 'pd.objectruanganlastfk')
            // ->join('antrianpasiendiperiksa_t as apd', 'apd.noregistrasifk', '=', 'pd.norec')
            ->orderBy('pd.tanggalpembatalan', 'DESC')
            ->select(
                'pd.norec as norec_pd',
                'pd.tanggalpembatalan',
                'pd.alasanpembatalan',
                'ps.nocm',
                'ps.namapasien',
                'pd.noregistrasi',
                'pg.namalengkap',
                'r.namaruangan',
                // 'apd.noantrian',
                'pd.statusschedule',
                'r.kdinternal',
            )
            ->where('pd.tanggalpembatalan', '!=', null);

        $startDate = Carbon::parse($filter['tglAwal'])->format('Y-m-d');
        $endDate = Carbon::parse($filter['tglAkhir'])->format('Y-m-d');

        if (isset($startDate) && $startDate != "" && $startDate != "undefined") {
            $data = $data->where('pd.tanggalpembatalan', '>=', $startDate . ' 00:00');
        }
        if (isset($endDate) && $endDate != "" && $endDate != "undefined") {
            $data = $data->where('pd.tanggalpembatalan', '<=', $endDate . ' 23:59');
        }
        if (isset($filter['noReg']) && $filter['noReg'] != "" && $filter['noReg'] != "undefined") {
            $data = $data->where('pd.noregistrasi', 'ilike', '%' . $filter['noReg'] . '%');
        }
        if (isset($filter['noCm']) && $filter['noCm'] != "" && $filter['noCm'] != "undefined") {
            $data = $data->where('ps.nocm', 'ilike', '%' . $filter['noCm'] . '%');
        }
        if (isset($filter['namaPasien']) && $filter['namaPasien'] != "" && $filter['namaPasien'] != "undefined") {
            $data = $data->where('ps.namapasien', 'ilike', '%' . $filter['namaPasien'] . '%');
        }
        $data = $data->groupBy(
            'pd.norec',
            'pd.tanggalpembatalan',
            'pd.alasanpembatalan',
            'ps.nocm',
            'ps.namapasien',
            'pd.noregistrasi',
            'pg.namalengkap',
            'r.namaruangan',
            // 'apd.noantrian',
            'pd.statusschedule',
            'r.kdinternal'
        );
        $data = $data->get();
        $result = array(
            'data' => $data,
            'message' => 'inhuman',
        );
        return $this->respond($result);
    }

    public function getPasienMeninggal(Request $request)
    {
        $filter = $request->all();
        $data = DB::table('pasiendaftar_t as pd')
            ->join('pasien_m as ps', 'ps.id', '=', 'pd.nocmfk')
            ->join('jeniskelamin_m as jk', 'jk.id', '=', 'ps.objectjeniskelaminfk')
            ->leftjoin('statuskeluar_m as sk', 'sk.id', '=', 'pd.objectstatuskeluarfk')
            ->leftjoin('statuspulang_m as sp', 'sp.id', '=', 'pd.objectstatuspulangfk')
            ->leftjoin('penyebabkematian_m as pk', 'pk.id', '=', 'pd.objectpenyebabkematianfk')
            ->leftjoin('ruangan_m as ru', 'ru.id', '=', 'pd.objectruanganlastfk')
            ->select(DB::raw("pd.tglregistrasi,pd.noregistrasi,ps.nocm,ps.namapasien,jk.jeniskelamin,ps.tgllahir,
                     sk.statuskeluar,sp.statuspulang,pd.namalengkapambilpasien,
                     case when pd.objectpenyebabkematianfk = 4 then pd.keteranganpenyebabkematian else pk.penyebabkematian end as penyebabkematian,
                     pd.tglmeninggal, ru.namaruangan"))
            ->where('pd.tglmeninggal', '!=', null);
        if (isset($filter['tglAwal']) && $filter['tglAwal'] != "" && $filter['tglAwal'] != "undefined") {
            // Gunakan '>= DATE' untuk memfilter catatan yang dimulai dari awal tanggal yang ditentukan.
            $data = $data->where('pd.tglmeninggal', '>=', $filter['tglAwal']);
        }
        if (isset($filter['tglAkhir']) && $filter['tglAkhir'] != "" && $filter['tglAkhir'] != "undefined") {
            // Gunakan '< DATE + 1' untuk memfilter catatan sampai akhir tanggal yang ditentukan.
            $data = $data->where('pd.tglmeninggal', '<', date('Y-m-d', strtotime($filter['tglAkhir'] . ' + 1 day')));
        }
        if (isset($filter['noReg']) && $filter['noReg'] != "" && $filter['noReg'] != "undefined") {
            $data = $data->where('pd.noregistrasi', 'ilike', '%' . $filter['noReg'] . '%');
        }
        if (isset($filter['noCm']) && $filter['noCm'] != "" && $filter['noCm'] != "undefined") {
            $data = $data->where('ps.nocm', 'ilike', '%' . $filter['noCm'] . '%');
        }
        if (isset($filter['namaPasien']) && $filter['namaPasien'] != "" && $filter['namaPasien'] != "undefined") {
            $data = $data->where('ps.namapasien', 'ilike', '%' . $filter['namaPasien'] . '%');
        }
        $data = $data->get();
        $result = array(
            'data' => $data,
            'message' => 'er@epic',
        );
        return $this->respond($result);
    }

    public function getLaporanPasienDaftar(Request $request)
    {
        $kdProfile = $this->getDataKdProfile($request);
        $idProfile = (int) $kdProfile;
        $tglAwal = Carbon::parse($request['tglAwal'])->format('Y-m-d') ?? date('Y-m-d');
        $tglAkhir = Carbon::parse($request['tglAkhir'])->format('Y-m-d') ?? date('Y-m-d');
        $departement = $request['departement'] == "undefined" ? "" : $request['departement'];
        $ruangan = $request['ruangan'] == "undefined" ? "" : $request['ruangan'];
        $kelompokpasien = $request['kelompokpasien'] == "undefined" ? "" : $request['kelompokpasien'];
        $rangeDate = [$tglAwal, $tglAkhir];
        $test = DB::table('pasiendaftar_t AS pd')
            ->join('antrianpasiendiperiksa_t AS apd', 'apd.noregistrasifk', 'pd.norec')
            ->join('pasien_m AS pm', 'pm.id', 'pd.nocmfk')
            ->join('jeniskelamin_m AS jk', 'jk.id', 'pm.objectjeniskelaminfk')
            ->join('ruangan_m AS ru', 'ru.id', 'apd.objectruanganfk')
            ->leftjoin('kelas_m AS kl', 'ru.id', 'pd.objectkelasfk')
            ->leftjoin('pegawai_m AS pg', 'pg.id', 'pd.objectpegawaifk')
            ->leftjoin('rekanan_m AS rk', 'rk.id', 'pd.objectrekananfk')
            ->leftjoin('kelompokpasien_m AS klp', 'klp.id', 'pd.objectkelompokpasienlastfk')
            ->leftjoin('alamat_m AS alm', 'alm.nocmfk', 'pm.id')
            ->leftjoin('agama_m AS ag', 'ag.id', 'pm.objectagamafk')
            ->leftjoin('statusperkawinan_m AS sp', 'sp.id', 'pm.objectstatusperkawinanfk')
            ->leftjoin('pendidikan_m AS pend', 'pend.id', 'pm.objectpendidikanfk')
            ->leftJoin('pekerjaan_m as peker', 'peker.id', 'pm.objectpekerjaanfk')
            ->leftJoin('detaildiagnosapasien_t AS ddp1', 'ddp1.noregistrasifk', 'apd.norec')
            ->leftJoin('diagnosa_m as dg', 'dg.id', 'pm.objectpekerjaanfk')
            ->leftJoin('pemakaianasuransi_t as pa', 'pa.noregistrasifk', 'pd.norec')
            // ->leftJoin('diagnosapasien_t dt ', 'dt.noregistrasifk', 'apd.norec')
            ->select(
                'pd.statuspasien',
                'apd.tglmasuk',
                'pd.noregistrasi',
                'pa.nosep',
                'pm.namapasien',
                'pm.tgllahir',
                'pm.nocm',
                'jk.reportdisplay as jk',
                'kl.namakelas',
                'pg.namalengkap as dokterpj',
                DB::raw("TO_CHAR(pd.tglregistrasi, 'DD-MM-YY') AS tglregistrasi"),
                'pd.tglregistrasi as tgljamregistrasi',
                'pd.tglpulang',
                'pd.petugas',
                'rk.namarekanan',
                'ru.namaruangan AS ruangandaftar',
                'ru.id AS idruangandaftar',
                'ru.objectdepartemenfk AS iddepartementdaftar',
                'klp.namaexternal AS kelompokpasien',
                'apd.statuskunjungan AS  statuskunjungan',
                'alm.alamatlengkap',
                'sp.statusperkawinan',
                'ag.agama',
                'pend.pendidikan',
                'peker.pekerjaan',
                'apd.norec',
                'alm.namadesakelurahan as kelurahan',
                'alm.namakecamatan as kecamatan',
                'alm.namakotakabupaten as kabupaten',
                'dg.namadiagnosa AS diagnosamasuk',
            )
            ->distinct()
            ->when($departement, function ($query) use ($departement) {
                return $query->where('ru.objectdepartemenfk', $departement);
            })
            ->when($ruangan, function ($query) use ($ruangan) {
                return $query->where('ru.id', $ruangan);
            })
            ->when($kelompokpasien, function ($query) use ($kelompokpasien) {
                return $query->where('klp.id', $kelompokpasien);
            })
            
            // ->orderBy('apd.tglmasuk', 'DESC')
            ->where('pd.kdprofile', $kdProfile)
            ->where('pd.statusenabled', true)
            ->whereBetween(DB::raw("CAST(pd.tglregistrasi AS DATE)"), $rangeDate)
            ->orderBy('apd.norec', 'DESC')
            ->get();
        $data =  PasienDaftarResource::collection(new Collection($test));
        $data = [
            'data' => $data,
            'listkelompok' => $this->getKelompok(),
            'listdepartemen' => $this->getDepartemen()
        ];
        return $this->respond($data);
    }

    protected function getKelompok()
    {
        return DB::table('kelompokpasien_m as kp')
            ->select('id', 'reportdisplay as nama')
            ->get();
    }
    protected function getDepartemen()
    {
        return DB::table('departemen_m as dp')
            ->select('id', 'namadepartemen as nama')
            ->where('statusenabled',true)
            ->get();
    }

    public function getTopTenDiagnosa(Request $request)
    {

        $kdProfile      = $this->getDataKdProfile($request);
        $tglAwal        = $request->tglAwal;
        $tglAkhir       = $request->tglakhir;
        $departement    = $request['departement'] == "undefined" ? "" : $request['departement'];
        $ruangan        = $request['ruangan'] == "undefined" ? "" : $request['ruangan'];
        $idProfile      = (int) $kdProfile;
        $data = DB::table('antrianpasiendiperiksa_t as app')
            ->join('diagnosapasien_t as dp', 'dp.noregistrasifk', '=', 'app.norec')
            ->join('detaildiagnosapasien_t as ddp', 'ddp.objectdiagnosapasienfk', '=', 'dp.norec')
            ->join('diagnosa_m as dm', 'ddp.objectdiagnosafk', '=', 'dm.id')
            ->join('pasiendaftar_t as pd', 'pd.norec', '=', 'app.noregistrasifk')
            ->join('pasien_m as ps', 'ps.id', '=', 'pd.nocmfk')
            ->join('jeniskelamin_m as jk', 'jk.id', '=', 'ps.objectjeniskelaminfk')
            ->leftJoin('alamat_m as alm', 'alm.nocmfk', '=', 'ps.id')
            ->leftJoin('kecamatan_m as kec', 'kec.id', '=', 'alm.objectkecamatanfk')
            ->leftJoin('kotakabupaten_m as kot', 'kot.id', '=', 'alm.objectkotakabupatenfk')
            ->leftJoin('propinsi_m as pro', 'pro.id', '=', 'alm.objectpropinsifk')
            ->leftJoin('ruangan_m as ru', 'ru.id', '=', 'pd.objectruanganlastfk')
            ->select(DB::raw('COUNT(dm.kddiagnosa) as jumlah, dm.kddiagnosa, dm.namadiagnosa,
                SUM(CASE WHEN dp.iskasusbaru = true AND jk.jeniskelamin = \'Pria\' THEN 1 ELSE 0 END) as kasusbarulk,
                SUM(CASE WHEN dp.iskasusbaru = true AND jk.jeniskelamin = \'Perempuan\' THEN 1 ELSE 0 END) as kasusbarup,
                SUM(CASE WHEN dp.iskasusbaru = true AND jk.jeniskelamin = \'Pria\' THEN 1 ELSE 0 END) +
                SUM(CASE WHEN dp.iskasusbaru = true AND jk.jeniskelamin = \'Perempuan\' THEN 1 ELSE 0 END) as total_kasus
            '))
            ->where('app.kdprofile',  $idProfile)
            ->where('dm.kddiagnosa', '<>', '-')
            ->when($tglAwal && $tglAkhir, function ($query) use ($tglAwal, $tglAkhir) {
                return $query->whereBetween('pd.tglregistrasi', [$tglAwal, $tglAkhir]);
            })
            ->when($departement, function ($query) use ($departement) {
                return $query->whereBetween('ru.objectdepartemenfk', $departement);
            })
            ->when($ruangan, function ($query) use ($ruangan) {
                return $query->where('ru.id', $ruangan);
            })
            ->groupBy('dm.namadiagnosa', 'dm.kddiagnosa')
            ->orderByDesc('jumlah')
            ->get();
        return $this->respond($data);
    }
    public function getLaporanTracer(Request $request)
    {
        $kdProfile      = (int)$this->getDataKdProfile($request);
        $tglAwal        = $request->tglAwal;
        $tglAkhir       = $request->tglAkhir;
        $namaPasien     = $request->namapasien;
        $nocm           = $request->nocm;
        $unitAsal       = $request->rungan;
        $rangeDate      = [$tglAwal, $tglAkhir];

        $data = DB::table('pasiendaftar_t AS pd')
            ->join('pasien_m AS ps', 'ps.id', 'pd.nocmfk')
            ->join('kendalidokumen_t AS kd', 'kd.noregistrasifk', 'pd.norec')
            ->LeftJoin('ruangan_m AS ru', 'ru.id', 'pd.objectruanganlastfk')
            ->where('pd.kdprofile', $kdProfile)
            ->where('kd.tglkembali', '!=', null)
            ->select(
                'pd.noregistrasi',
                'pd.tglregistrasi',
                'ps.nocm',
                'ps.namapasien',
                'kd.tglkembali as tglkeluar',
                'ru.namaruangan as unitasal'
            )
            ->when($rangeDate, function ($query) use ($rangeDate) {
                return $query->whereBetween(DB::raw("CAST(pd.tglregistrasi AS DATE)"), $rangeDate);
            })
            ->when($namaPasien, function ($query) use ($namaPasien) {
                return $query->where('ps.namapasien', 'like', '%' . $namaPasien . '%');
            })
            ->when($nocm, function ($query) use ($nocm) {
                return $query->where('ps.nocm', 'like', '%' . $nocm . '%');
            })
            ->when($unitAsal, function ($query) use ($unitAsal) {
                return $query->where('ru.id', $unitAsal);
            })
            ->get();
        $dataFix = [];
        foreach ($data as $item) {
            $dataFix[] = array(
                'selisih' => $this->getSelisih($item->tglregistrasi, $item->tglkeluar),
                'noregistrasi' => $item->noregistrasi,
                'tglregistrasi' => Carbon::parse($item->tglregistrasi)->format('d-m-Y H:i:s'),
                'tglkeluar' => Carbon::parse($item->tglkeluar)->format('d-m-Y H:i:s'),
                'nocm' => $item->nocm,
                'namapasien' => $item->namapasien,
                'unitasal' => $item->unitasal,
            );
        }
        $data = array(
            'data' => $dataFix,
            'message' => 'Inhuman'
        );
        return $this->respond($data);
    }

    public function getSudahPeriksa(Request $request)
    {
        $kdProfile      = (int)$this->getDataKdProfile($request);
        $norec_pd       = $request->norec_pd;

        $dataemr = DB::table('pasiendaftar_t AS pd')
            ->join('emrpasien_t as emrp', 'pd.norec', '=', 'emrp.noregistrasifk')
            ->where('pd.norec', $request->norec_pd)
            ->where('emrp.statusenabled', true)
            ->select(
                'pd.noregistrasi',
                'emrp.noemr'
            )->get();

        $datatindakan = DB::table('pasiendaftar_t AS pd')
            ->join('antrianpasiendiperiksa_t as apd', 'apd.noregistrasifk', 'pd.norec')
            ->join('pelayananpasien_t as pp', 'pp.noregistrasifk', 'apd.norec')
            ->where('pd.norec', $request->norec_pd)
            ->where('pp.statusenabled', true)
            ->whereNotIn('pp.produkfk', [4167, 4168])
            ->select(
                'pd.noregistrasi',
                'pp.norec'
            )->get();

        $data = array(
            'dataemr' => $dataemr,
            'datatindakan' => $datatindakan,
            'message' => 'Inhuman'
        );
        return $this->respond($data);
    }

    public function getSelisih($dateAwal, $dateAkhir)
    {
        $datetime = new \DateTime(date($dateAwal));
        return $datetime->diff(new \DateTime(date($dateAkhir)))
            ->format('%dhr %hjam %imnt');
    }
    public function getDataKdProfile(Request $request)
    {
        $dataLogin = $request->all();
        $idUser = $dataLogin['userData']['id'];
        $data = LoginUser::where('id', $idUser)->first();
        if (!empty($data)) {
            $idKdProfile = (int)$data->kdprofile;
            $Query = DB::table('profile_m')
                ->where('id', '=', $idKdProfile)
                ->first();
            $Profile = $Query;
            return (int)$Profile->id;
        } else {
            $data = Pasien::where('id', $idUser)->first();
            if (!empty($data)) {
                $idKdProfile = (int)$data->kdprofile;
                $Query = DB::table('profile_m')
                    ->where('id', '=', $idKdProfile)
                    ->first();
                $Profile = $Query;
                return (int)$Profile->id;
            } else {
                return null;
            }
        }
    }

    public function getLaporanDemoRIKelompok(Request $request)
    {
        $kdProfile = $this->kdProfile;
        $idProfile = (int) $kdProfile;
        $tglAwal = $request['tglAwal'];
        $tglAkhir = $request['tglAkhir'];
        $ruanganId = $request['ruanganId'];
        $kelompokId = $request['kelompokPasien'];

        $paramRuangan = ' ';
        if (isset($ruanganId) && $ruanganId != "" && $ruanganId != "undefined") {
            $paramRuangan = ' and pd.objectruanganlastfk = ' . $ruanganId;
        }

        $paramKelompok = ' ';
        if (isset($kelompokId) && $kelompokId != "" && $kelompokId != "undefined") {
            $paramKelompok = ' and pd.objectkelompokpasienlastfk = ' . $kelompokId;
        }

        // $deptRawatJalan = $this->settingDataFixed('kdDepartemenRawatJalanFix',$idProfile);

        $data = DB::table('antrianpasiendiperiksa_t as apd')
            ->join('pasiendaftar_t as pd', 'pd.norec', '=', 'apd.noregistrasifk')
            ->join('ruangan_m as ru', 'ru.id', '=', 'apd.objectruanganfk')
            ->join('kelompokpasien_m as kp', 'kp.id', '=', 'pd.objectkelompokpasienlastfk')
            ->join('pasien_m as ps', 'ps.id', '=', 'pd.nocmfk')
            ->select(
                'kp.kelompokpasien',
                DB::raw('COUNT(kp.kelompokpasien) as jumlah')
            )
            ->where('apd.kdprofile', $idProfile)
            ->whereBetween('apd.tglregistrasi', [$tglAwal, $tglAkhir])
            ->where('ru.objectdepartemenfk', 16)
            ->groupBy('kp.kelompokpasien')
            ->orderBy('kp.kelompokpasien', 'asc')
            ->get();

        $result = array(
            'data' => $data,
            'message' => 'ea@epic',
        );

        return $this->respond($result);
    }

    public function getLaporanDemoRIPendidikan(Request $request)
    {
        $kdProfile = (int) $this->kdProfile;
        $data = [];
        $tglAwal = $request['tglAwal'];
        $tglAkhir = $request['tglAkhir'];
        $ruanganId = $request['ruanganId'];
        $kelompokId = $request['kelompokPasien'];

        $paramRuangan = ' ';
        if (isset($ruanganId) && $ruanganId != "" && $ruanganId != "undefined") {
            $paramRuangan = ' and pd.objectruanganlastfk = ' . $ruanganId;
        }

        $paramKelompok = ' ';
        if (isset($kelompokId) && $kelompokId != "" && $kelompokId != "undefined") {
            $paramKelompok = ' and pd.objectkelompokpasienlastfk = ' . $kelompokId;
        }

        $data = DB::table('antrianpasiendiperiksa_t as apd')
            ->join('pasiendaftar_t as pd', 'pd.norec', '=', 'apd.noregistrasifk')
            ->join('ruangan_m as ru', 'ru.id', '=', 'apd.objectruanganfk')
            ->join('kelompokpasien_m as kp', 'kp.id', '=', 'pd.objectkelompokpasienlastfk')
            ->join('pasien_m as ps', 'ps.id', '=', 'pd.nocmfk')
            ->join('pendidikan_m as pe', 'pe.id', '=', 'ps.objectpendidikanfk')
            ->where('apd.kdprofile', '=', $kdProfile)
            ->whereBetween('apd.tglregistrasi', [$tglAwal, $tglAkhir]) // Corrected line
            ->where('ru.objectdepartemenfk', '=', 16)
            ->groupBy('pe.pendidikan')
            ->select('pe.pendidikan', DB::raw('COUNT(pe.pendidikan) as jumlah'))
            ->orderBy('pe.pendidikan', 'asc')
            ->get();

        $result = array(
            'data' => $data,
            'message' => 'ea@epic',
        );

        return $this->respond($result);
    }

    public function getLaporanDemoRIDaerah(Request $request)
    {
        $kdProfile = $this->kdProfile;
        $idProfile = (int) $kdProfile;
        $data = [];
        $tglAwal = $request['tglAwal'];
        $tglAkhir = $request['tglAkhir'];
        $ruanganId = $request['ruanganId'];
        $kelompokId = $request['kelompokPasien'];

        $paramRuangan = ' ';
        if (isset($ruanganId) && $ruanganId != "" && $ruanganId != "undefined") {
            $paramRuangan = ' and pd.objectruanganlastfk = ' . $ruanganId;
        }

        $paramKelompok = ' ';
        if (isset($kelompokId) && $kelompokId != "" && $kelompokId != "undefined") {
            $paramKelompok = ' and pd.objectkelompokpasienlastfk = ' . $kelompokId;
        }

        $data = DB::select(
            DB::raw(
                "select
            kb.namakotakabupaten,
            COUNT(kb.namakotakabupaten) as jumlah

            from antrianpasiendiperiksa_t as apd
            INNER JOIN pasiendaftar_t as pd ON pd.norec=apd.noregistrasifk
            INNER JOIN ruangan_m as ru On ru.id=apd.objectruanganfk
            INNER JOIN kelompokpasien_m as kp on kp.id=pd.objectkelompokpasienlastfk
            INNER JOIN pasien_m as ps on ps.id=pd.nocmfk
            INNER JOIN alamat_m as al on al.nocmfk=ps.id
            INNER JOIN kotakabupaten_m as kb on kb.id=al.objectkotakabupatenfk
            where apd.kdprofile = $idProfile and apd.tglregistrasi BETWEEN '$tglAwal' and '$tglAkhir' AND ru.objectdepartemenfk = 16
            GROUP BY
            kb.namakotakabupaten

            ORDER BY kb.namakotakabupaten asc "
            )
        );

        $result = array(
            'data' => $data,
            'message' => 'ea@epic',
        );

        return $this->respond($result);
    }

    public function getLaporanDemoRIPekerjaan(Request $request)
    {
        $kdProfile = $this->kdProfile;
        $idProfile = (int) $kdProfile;
        $data = [];
        $tglAwal = $request['tglAwal'];
        $tglAkhir = $request['tglAkhir'];
        $ruanganId = $request['ruanganId'];
        $kelompokId = $request['kelompokPasien'];

        $paramRuangan = ' ';
        if (isset($ruanganId) && $ruanganId != "" && $ruanganId != "undefined") {
            $paramRuangan = ' and pd.objectruanganlastfk = ' . $ruanganId;
        }

        $paramKelompok = ' ';
        if (isset($kelompokId) && $kelompokId != "" && $kelompokId != "undefined") {
            $paramKelompok = ' and pd.objectkelompokpasienlastfk = ' . $kelompokId;
        }

        $data = DB::select(
            DB::raw(
                "select
            pek.pekerjaan,
            COUNT(pek.pekerjaan) as jumlah

            from antrianpasiendiperiksa_t as apd
            INNER JOIN pasiendaftar_t as pd ON pd.norec=apd.noregistrasifk
            INNER JOIN ruangan_m as ru On ru.id=apd.objectruanganfk
            INNER JOIN kelompokpasien_m as kp on kp.id=pd.objectkelompokpasienlastfk
            INNER JOIN pasien_m as ps on ps.id=pd.nocmfk
            INNER JOIN pekerjaan_m as pek on pek.id=ps.objectpekerjaanfk
            where apd.kdprofile = $idProfile and apd.tglregistrasi BETWEEN '$tglAwal' and '$tglAkhir' AND ru.objectdepartemenfk = 16
            GROUP BY
            pek.pekerjaan

            ORDER BY pek.pekerjaan asc "
            )
        );

        $result = array(
            'data' => $data,
            'message' => 'ea@epic',
        );

        return $this->respond($result);
    }

    public function getLaporanDemoRIUsia(Request $request)
    {
        $kdProfile = $this->getDataKdProfile($request);
        $idProfile = (int) $kdProfile;
        $data = [];
        $tglAwal = $request['tglAwal'];
        $tglAkhir = $request['tglAkhir'];
        $ruanganId = $request['ruanganId'];
        $kelompokId = $request['kelompokPasien'];

        $paramRuangan = ' ';
        if (isset($ruanganId) && $ruanganId != "" && $ruanganId != "undefined") {
            $paramRuangan = ' and pd.objectruanganlastfk = ' . $ruanganId;
        }

        $paramKelompok = ' ';
        if (isset($kelompokId) && $kelompokId != "" && $kelompokId != "undefined") {
            $paramKelompok = ' and pd.objectkelompokpasienlastfk = ' . $kelompokId;
        }

        // $deptRawatJalan = $this->settingDataFixed('kdDepartemenRawatJalanFix',$idProfile);

        // CAST (
        //     ROUND(
        //         DATEDIFF(HOUR, pg.tgllahir, GETDATE()) / 8766.0,
        //         0
        //     ) AS INTEGER
        // ) AS umur
        $data = DB::select(
            DB::raw(
                "SELECT
                z.usia, count(z.usia) as jml
            FROM
                (
                    SELECT
                    CASE
                    WHEN x.umur >= 1		AND x.umur <= 14 THEN 		'1 - 14 TAHUN'
                    WHEN x.umur >= 15		AND x.umur <= 24 THEN			'15 - 24 TAHUN'
                WHEN x.umur >= 25		AND x.umur <= 44 THEN			'25 - 44 TAHUN'
                    WHEN x.umur >= 45		AND x.umur <= 64 THEN			'45 - 64 TAHUN'
                    WHEN x.umur >= 65 	THEN '65 TAHUN KE ATAS'
                    END AS usia
                    FROM
                        (
                            SELECT
                                pg.namapasien,
                                pg.tgllahir,
                                EXTRACT(YEAR FROM AGE(pd.tglregistrasi, pg.tgllahir))::integer as umur
                            FROM antrianpasiendiperiksa_t as apd
            INNER JOIN pasiendaftar_t as pd ON pd.norec=apd.noregistrasifk
            INNER JOIN ruangan_m as ru On ru.id=apd.objectruanganfk
            INNER JOIN kelompokpasien_m as kp on kp.id=pd.objectkelompokpasienlastfk
            INNER JOIN pasien_m as pg on pg.id=pd.nocmfk
            where apd.kdprofile = $idProfile and apd.tglregistrasi BETWEEN '$tglAwal' and '$tglAkhir' AND ru.objectdepartemenfk = 16
                        ) AS x
                )
            as z GROUP BY z.usia
            ORDER BY z.usia ASC "
            )
        );

        $result = array(
            'data' => $data,
            'message' => 'ea@epic',
        );

        return $this->respond($result);
    }

    public function getLaporanDemoRIAgama(Request $request)
    {
        $kdProfile = $this->kdProfile;
        $idProfile = (int) $kdProfile;
        $data = [];
        $tglAwal = $request['tglAwal'];
        $tglAkhir = $request['tglAkhir'];
        $ruanganId = $request['ruanganId'];
        $kelompokId = $request['kelompokPasien'];

        $paramRuangan = ' ';
        if (isset($ruanganId) && $ruanganId != "" && $ruanganId != "undefined") {
            $paramRuangan = ' and pd.objectruanganlastfk = ' . $ruanganId;
        }

        $paramKelompok = ' ';
        if (isset($kelompokId) && $kelompokId != "" && $kelompokId != "undefined") {
            $paramKelompok = ' and pd.objectkelompokpasienlastfk = ' . $kelompokId;
        }

        $data = DB::select(
            DB::raw(
                "select
            ag.agama,
            COUNT(ag.agama) as jumlah

            from antrianpasiendiperiksa_t as apd
            INNER JOIN pasiendaftar_t as pd ON pd.norec=apd.noregistrasifk
            INNER JOIN ruangan_m as ru On ru.id=apd.objectruanganfk
            INNER JOIN kelompokpasien_m as kp on kp.id=pd.objectkelompokpasienlastfk
            INNER JOIN pasien_m as ps on ps.id=pd.nocmfk
            INNER JOIN agama_m as ag on ag.id = ps.objectagamafk
            where apd.kdprofile = $idProfile and apd.tglregistrasi BETWEEN '$tglAwal' and '$tglAkhir' AND ru.objectdepartemenfk = 16
            GROUP BY
            ag.agama

            ORDER BY ag.agama asc "
            )
        );

        $result = array(
            'data' => $data,
            'message' => 'ea@epic',
        );

        return $this->respond($result);
    }

    public function getLaporanDemoRIItem(Request $request)
    {
        $kdProfile = $this->getDataKdProfile($request);
        $idProfile = (int) $kdProfile;
        $data = [];
        $tglAwal = $request['tglAwal'];
        $tglAkhir = $request['tglAkhir'];
        $ruanganId = $request['ruanganId'];
        $kelompokId = $request['kelompokPasien'];
        // $jumlahTT = (float) $this->settingDataFixed('jmlTempatTidur',$idProfile);

        $paramRuangan = ' ';
        if (isset($ruanganId) && $ruanganId != "" && $ruanganId != "undefined") {
            $paramRuangan = ' and pd.objectruanganlastfk = ' . $ruanganId;
        }

        $paramKelompok = ' ';
        if (isset($kelompokId) && $kelompokId != "" && $kelompokId != "undefined") {
            $paramKelompok = ' and pd.objectkelompokpasienlastfk = ' . $kelompokId;
        }

        // $deptRawatJalan = $this->settingDataFixed('kdDepartemenRawatJalanFix',$idProfile);

        $data = DB::select(
            DB::raw("
        SELECT
            COUNT(ps.noregistrasi) as jumlah,'Masuk' as ket
            FROM pasiendaftar_t as ps
            INNER JOIN ruangan_m as ru on ru.id = ps.objectruanganlastfk
            WHERE tglregistrasi BETWEEN '$tglAwal' and '$tglAkhir' AND objectdepartemenfk = 16
            union ALL
            SELECT
            COUNT(ps.noregistrasi) as jumlah,'Keluar' as ket
            FROM pasiendaftar_t as ps
            INNER JOIN ruangan_m as ru on ru.id = ps.objectruanganlastfk
            WHERE tglpulang BETWEEN '$tglAwal' and '$tglAkhir' AND objectdepartemenfk = 16
            union ALL
            SELECT
            COUNT(ps.noregistrasi) as jumlah,'Hidup' as ket
            FROM pasiendaftar_t as ps
            INNER JOIN ruangan_m as ru on ru.id = ps.objectruanganlastfk
            WHERE tglpulang BETWEEN '$tglAwal' and '$tglAkhir' AND objectdepartemenfk = 16
            and ps.objectkondisipasienfk not in (5,6)
            union ALL
            SELECT
            COUNT(ps.noregistrasi) as jumlah,'Meninggal < 24 jam' as ket
            FROM pasiendaftar_t as ps
            INNER JOIN ruangan_m as ru on ru.id = ps.objectruanganlastfk
            WHERE tglpulang BETWEEN '$tglAwal' and '$tglAkhir' AND objectdepartemenfk = 16
            and ps.objectkondisipasienfk = 5
            union ALL
            SELECT
            COUNT(ps.noregistrasi) as jumlah,'Meninggal > 24 jam' as ket
            FROM pasiendaftar_t as ps
            INNER JOIN ruangan_m as ru on ru.id = ps.objectruanganlastfk
            WHERE tglpulang BETWEEN '$tglAwal' and '$tglAkhir' AND objectdepartemenfk = 16
            and ps.objectkondisipasienfk = 6

            UNION ALL
            SELECT COUNT (x.noregistrasi) AS jumlah, 'HARI PERAWATAN' as ket
            FROM
            (
            SELECT
            pd.noregistrasi,
            pd.tglpulang,
            EXTRACT(MONTH FROM pd.tglregistrasi) AS bulanregis
            FROM
            pasiendaftar_t AS pd
            INNER JOIN ruangan_m AS ru ON ru.ID = pd.objectruanganlastfk
            WHERE
            ru.objectdepartemenfk = 16
            AND pd.tglpulang IS NULL
            ) AS x

            UNION ALL
            select sum(x.hari) as jumlah, 'LAMA DIRAWAT' as ket
            from (
            SELECT
            DATE_PART('day', pd.tglpulang - pd.tglregistrasi) as hari ,pd.noregistrasi
            FROM
            pasiendaftar_t AS pd
            INNER JOIN ruangan_m AS ru ON ru. ID = pd.objectruanganlastfk
            WHERE pd.kdprofile = $idProfile and
            pd.tglpulang BETWEEN '$tglAwal'
            AND '$tglAkhir' and
            ru.objectdepartemenfk = 16
            and pd.tglpulang is not null
            GROUP BY pd.noregistrasi,pd.tglpulang,pd.tglregistrasi
            ) as x
        ")
        );

        $hariPerawatan = DB::select(DB::raw(
            "
        SELECT   COUNT (x.noregistrasi) AS jumlahhariperawatan
                                FROM
                                    (
                                        SELECT
                                            pd.noregistrasi,
                                            pd.tglpulang,
                                            EXTRACT(MONTH FROM pd.tglregistrasi) AS bulanregis
                                        FROM
                                            pasiendaftar_t AS pd
                                        INNER JOIN ruangan_m AS ru ON ru.ID = pd.objectruanganlastfk
                                        WHERE pd.kdprofile = $idProfile and
                                            ru.objectdepartemenfk = 16
                                        AND pd.tglpulang IS NULL
                                        --AND pd.tglregistrasi NOT BETWEEN '$tglAwal'
                                       --AND '$tglAkhir'
                                    ) AS x"
        ));

        $result = array(
            'data' => $data,
            'message' => 'ea@epic',
        );

        return $this->respond($result);
    }

    public function cetakTracer(Request $r)
    {
        $data = DB::table('pasiendaftar_t as pd')
            ->join('pasien_m as ps', 'pd.nocmfk', 'ps.id')
            ->leftJoin('ruangan_m as ru', 'ru.id', 'pd.objectruanganlastfk')
            ->leftJoin('pegawai_m as p', 'p.id', 'pd.objectpegawaifk')
            ->leftJoin('kelompokpasien_m as km', 'km.id', 'pd.objectkelompokpasienlastfk')
            ->where('pd.noregistrasi', $r->noregistrasi)
            ->select(
                'ps.id as id',
                'ps.namapasien',
                'pd.tglregistrasi as tglpendaftaran',
                'ps.tgldaftar as tglregistrasi',
                'ps.nocm as nocm',
                'ru.namaruangan as ruangan',
                'p.namalengkap as pegawai',
                'pd.noregistrasi as noregistrasi',
                'km.kelompokpasien'
            )
            ->first();
        $pageWidth     = 50;
        $height        = 600;
        $blade         = 'report.pendaftaran.cetak-tracer';
        $pdf           = App::make('dompdf.wrapper');
        $res['pdf']    = isset($request['pdf']) ? $request['pdf'] : true;
        if (false) {
            $pdf = App::make('dompdf.wrapper');
            $pdf->loadView(
                $blade,
                array(
                    'res' => $res,
                    'pageWidth' => $pageWidth,
                    'data' => $data
                )
            );
            $pdf->setPaper('A4', 'portrait');
            return $pdf->stream();
        } else {
        }

        return view(
            $blade,
            compact('res', 'pageWidth', 'data')
        );
    }
    public function simpanUpdateRekananPD(Request $request)
    {
        DB::beginTransaction();
        $transStatus = 'true';
        try {
            $data = DB::table('pasiendaftar_t')->where('norec', $request['norec_pd'])->update([
                'objectrekananfk' => $request->objectrekananfk,
                'objectkelompokpasienlastfk' => $request->objectkelompokpasienlastfk
            ]);
            $transMessage = "Update Rekanan berhasil!";
        } catch (\Exception $e) {
            $transStatus = 'false';
            $transMessage =$e->getMessage();
        }

        if ($transStatus != 'false') {
            DB::commit();
            $result = array(
                'data' =>$data,
                "status" => 200,
                "message" => $transMessage,
            );
        } else {
            DB::rollBack();
            $result = array(
                'data' =>null,
                "status" => 400,
                "message" => $transMessage,
            );
        }

        return $this->respond($result['data'], $result['status'], $result['message']);
    }

}
