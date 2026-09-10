<?php

namespace App\Http\Controllers\Farmasi;

use App\Http\Controllers\Controller;
use App\Models\Master\KelompokPasien;
use App\Models\Master\Ruangan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DaftarPasienFarmasiCtrl extends Controller
{
    public function __construct()
    {
        parent::__construct($is_encrypt = true);
    }
    public function getDataGrid(Request $r)
    {
        // DB::connection()->enableQueryLog();
        $data = DB::table('pasiendaftar_t as pd')
            ->JOIN('ruangan_m as ru', 'ru.id', '=', 'pd.objectruanganlastfk')
            ->JOIN('pasien_m as ps', 'ps.id', '=', 'pd.nocmfk')
            ->leftJoin('jeniskelamin_m as jk', 'jk.id', '=', 'ps.objectjeniskelaminfk')
            ->JOIN('kelompokpasien_m as kp', 'kp.id', '=', 'pd.objectkelompokpasienlastfk')
            ->leftJoin('rekanan_m as rek', 'rek.id', '=', 'pd.objectrekananfk')
            ->JOIN('kelas_m as kl', 'kl.id', '=', 'pd.objectkelasfk')
            ->select(
                'ru.namaruangan',
                'pd.noregistrasi',
                'ps.nocm',
                'ps.norec as norec_pasien',
                'ps.namapasien',
                'jk.jeniskelamin',
                'kp.id as kpid',
                'kp.kelompokpasien',
                'kl.namakelas',
                'kl.id as klid',
                'pd.tglregistrasi',
                'pd.tglpulang',
                'ps.tgllahir',
                'pd.nostruklastfk',
                'pd.nocmfk',
                'pd.norec as norec_pd',
                'rek.namarekanan',
                'ps.nobpjs'
            )
            ->where('pd.kdprofile', $this->kdProfile)
            ->where('pd.statusenabled', true)
            ->where('ps.statusenabled', true);
        // ->orderByDesc('pd.noregistrasi');
        if (isset($r['dari']) && $r['dari'] != "" && $r['dari'] != "undefined") {
            $data = $data->where('pd.tglregistrasi', '>=', $r['dari'] . ' 00:00');
        }
        if (isset($r['sampai']) && $r['sampai'] != "" && $r['sampai'] != "undefined") {
            $data = $data->where('pd.tglregistrasi', '<=', $r['sampai'] . ' 23:59');
        }
        if (isset($r['ruid']) && $r['ruid'] != "" && $r['ruid'] != "undefined") {
            $data = $data->where('ru.id', $r['ruid']);
        }
        if (isset($r['dpid']) && $r['dpid'] != "" && $r['dpid'] != "undefined") {
            $data = $data->where('dp.id', $r['dpid']);
        }
        if (isset($r['kpid']) && $r['kpid'] != "" && $r['kpid'] != "undefined") {
            $data = $data->where('kp.id', $r['kpid']);
        }
        if (isset($r['noreg']) && $r['noreg'] != "" && $r['noreg'] != "undefined") {
            $data = $data->where('pd.noregistrasi', 'ilike', '%' . $r['noreg'] . '%');
        }
        if (isset($r['nocm']) && $r['nocm'] != "" && $r['nocm'] != "undefined") {
            $data = $data->where('ps.nocm', 'ilike', '%' . $r['nocm'] . '%');
        }
        if (isset($r['namapasien']) && $r['namapasien'] != "" && $r['namapasien'] != "undefined") {
            $data = $data->where('ps.namapasien', 'ilike', '%' . $r['namapasien'] . '%');
        }
        $total = $data->count();
        if (isset($r['offset']) && $r['offset'] != '') {
            $data = $data->offset($r['offset']);
        }
        if (isset($r['limit']) && $r['limit'] != '') {
            $data = $data->limit($r['limit']);
        }

        $data = $data->get();

        foreach ($data as $d) {
            $d->umur = $this->getAge($d->tgllahir, $d->tglregistrasi);
        }

        // dd(DB::getQueryLog());
        $result = [
            'data' => $data,
            'total' => $total
        ];
        return $this->respond($result);
    }

    public function getDataPermohonan(Request $r)
    {
        $user = DB::table('loginuser_s as lu')
                ->join('maploginusertoruangan_s as mlu', 'mlu.objectloginuserfk', '=', 'lu.id')
                ->select('lu.id', 'mlu.objectruanganfk')
                ->where('lu.id', $this->getUserId())
                ->where('mlu.statusenabled', true)
                ->get();


        $data = DB::table('pasiendaftar_t as pd')
            ->JOIN('ruangan_m as ru', 'ru.id', '=', 'pd.objectruanganlastfk')
            ->JOIN('pasien_m as ps', 'ps.id', '=', 'pd.nocmfk')
            ->leftJoin('jeniskelamin_m as jk', 'jk.id', '=', 'ps.objectjeniskelaminfk')
            ->JOIN('kelompokpasien_m as kp', 'kp.id', '=', 'pd.objectkelompokpasienlastfk')
            ->leftJoin('rekanan_m as rek', 'rek.id', '=', 'pd.objectrekananfk')
            ->JOIN('kelas_m as kl', 'kl.id', '=', 'pd.objectkelasfk')
            ->JOIN('permohonanalat_t as pt', 'pt.nopasiendaftarfk', '=', 'pd.norec')
            ->JOIN('antrianpasiendiperiksa_t as apd', 'apd.norec', '=', 'pt.noregistrasifk')
            ->JOIN('ruangan_m as ru2', 'ru2.id', '=', 'apd.objectruanganfk')
            ->select(
                'ru.namaruangan',
                'pd.noregistrasi',
                'ps.nocm',
                'ps.norec as norec_pasien',
                'ps.namapasien',
                'apd.norec as norec_apd',
                'jk.jeniskelamin',
                'kp.id as kpid',
                'kp.kelompokpasien',
                'kl.namakelas',
                'kl.id as klid',
                'pd.tglregistrasi',
                'pd.tglpulang',
                'ps.tgllahir',
                'pd.nostruklastfk',
                'pd.nocmfk',
                'pd.norec as norec_pd',
                'rek.namarekanan',
                'ps.nobpjs',
                'ru2.namaruangan',
                'pt.norec_emr'
            )
            ->where('pd.kdprofile', $this->kdProfile)
            ->where('pd.statusenabled', true)
            ->where('pd.statusenabled', true);
            // ->where('pt.satelitfk', $user[0]->objectruanganfk);
     
        if (isset($r['ruid']) && $r['ruid'] != "" && $r['ruid'] != "undefined") {
            $data = $data->where('ru.id', $r['ruid']);
        }
        if (isset($r['dpid']) && $r['dpid'] != "" && $r['dpid'] != "undefined") {
            $data = $data->where('dp.id', $r['dpid']);
        }
        if (isset($r['kpid']) && $r['kpid'] != "" && $r['kpid'] != "undefined") {
            $data = $data->where('kp.id', $r['kpid']);
        }
        if (isset($r['noreg']) && $r['noreg'] != "" && $r['noreg'] != "undefined") {
            $data = $data->where('pd.noregistrasi', 'ilike', '%' . $r['noreg'] . '%');
        }
        if (isset($r['nocm']) && $r['nocm'] != "" && $r['nocm'] != "undefined") {
            $data = $data->where('ps.nocm', 'ilike', '%' . $r['nocm'] . '%');
        }
        if (isset($r['namapasien']) && $r['namapasien'] != "" && $r['namapasien'] != "undefined") {
            $data = $data->where('ps.namapasien', 'ilike', '%' . $r['namapasien'] . '%');
        }

        if (isset($r['statuscetak']) && $r['statuscetak'] != "" && $r['statuscetak'] != "undefined" && $r['statuscetak'] == 't' ) {
            
        }
        else
        {
        
            $data = $data->whereRaw('(pt.statuscetak IS NULL OR pt.statuscetak != true)');
        }


        $total = $data->count();
        if (isset($r['offset']) && $r['offset'] != '') {
            $data = $data->offset($r['offset']);
        }
        if (isset($r['limit']) && $r['limit'] != '') {
            $data = $data->limit($r['limit']);
        }

        $data = $data->get();

        foreach ($data as $d) {
            $d->umur = $this->getAge($d->tgllahir, $d->tglregistrasi);
        }

        // dd(DB::getQueryLog());
        $result = [
            'data' => $data,
            'total' => $total
        ];
        return $this->respond($result);
    }

    public function getDataRanap(Request $r)
    {
        $data  = DB::table('pasiendaftar_t as pd')
            ->join('kelas_m as kls', 'kls.id', 'pd.objectkelasfk')
            ->join('kamar_m as kmr', 'kmr.objectkelasfk', 'kls.id')
            ->join('antrianpasiendiperiksa_t as apd', 'pd.norec', '=', 'apd.noregistrasifk')
            ->join('ruangan_m as ru', 'ru.id', '=', 'pd.objectruanganlastfk')
            ->join('tempattidur_m as tt', 'tt.id', 'apd.nobed')
            ->leftjoin('pegawai_m as pg', 'pd.objectpegawaifk', '=', 'pg.id')
            ->leftjoin('pegawai_m as pg2', 'pd.objectpegawairawatbersamafk', '=', 'pg2.id')
            ->leftjoin('pasien_m as ps', 'pd.nocmfk', '=', 'ps.id')
            ->leftjoin('alamat_m as alm', 'alm.nocmfk', '=', 'ps.id')
            ->leftjoin('kotakabupaten_m as kbp', 'alm.objectkotakabupatenfk', '=', 'kbp.id')
            ->leftjoin('kecamatan_m as km', 'alm.objectkecamatanfk', '=', 'km.id')
            ->leftjoin('desakelurahan_m as ds', 'alm.objectdesakelurahanfk', '=', 'ds.id')
            ->leftjoin('kelompokpasien_m as kp', 'pd.objectkelompokpasienlastfk', '=', 'kp.id')
            ->select(
                DB::raw("
                DISTINCT on (apd.noregistrasifk, pd.tglregistrasi)
                ru.id,
                kp.kelompokpasien,
                ru.statusenabled,
                ru.namaruangan,
                kls.namakelas,
                kmr.namakamar,
                pd.norec as norec_pd,
                pd.objectruanganasalfk,
                pd.nocmfk,
                pd.tglpulang,
                ru.objectdepartemenfk,
                tt.reportdisplay,
                pd.objectruanganlastfk,
                pd.objectpegawaifk,
                pd.objectpegawairawatbersamafk,
                pd.noregistrasi,
                pg.namalengkap,
                pg2.namalengkap as nama,
                ps.namapasien,
                ps.tgllahir,
                ps.nocm,
                ps.alamatrmh,
                ps.nobpjs,
                ds.namadesakelurahan,
                km.namakecamatan,
                kbp.namakotakabupaten,
                ps.noidentitas,
                apd.norec as norec_apd,
                pd.tglregistrasi
                ")
            )
            ->where('pd.kdprofile', $this->kdProfile)
            ->where('ru.objectdepartemenfk',  $this->settingFix('idDepRawatInap'))
            ->where('pd.statusenabled', true)
            ->where('apd.statusenabled', true)
            ->whereNull('apd.tglkeluar')
            ->where('apd.kdprofile', $this->kdProfile)
            ->whereNull('pd.tglpulang')
            ->orderBy('pd.tglregistrasi', 'DESC');

        if (isset($r['dari']) && $r['dari'] != "" && $r['dari'] != "undefined") {
            $data = $data->where('pd.tglregistrasi', '>=', $r['dari'] . ' 00:00');
        }
        if (isset($r['sampai']) && $r['sampai'] != "" && $r['sampai'] != "undefined") {
            $data = $data->where('pd.tglregistrasi', '<=', $r['sampai'] . ' 23:59');
        }
        if (isset($r['ruid']) && $r['ruid'] != "" && $r['ruid'] != "undefined") {
            $data = $data->where('ru.id', $r['ruid']);
        }
        if (isset($r['dpid']) && $r['dpid'] != "" && $r['dpid'] != "undefined") {
            $data = $data->where('dp.id', $r['dpid']);
        }
        if (isset($r['kpid']) && $r['kpid'] != "" && $r['kpid'] != "undefined") {
            $data = $data->where('kp.id', $r['kpid']);
        }
        if (isset($r['noreg']) && $r['noreg'] != "" && $r['noreg'] != "undefined") {
            $data = $data->where('pd.noregistrasi', 'ilike', '%' . $r['noreg'] . '%');
        }
        if (isset($r['nocm']) && $r['nocm'] != "" && $r['nocm'] != "undefined") {
            $data = $data->where('ps.nocm', 'ilike', '%' . $r['nocm'] . '%');
        }
        if (isset($r['namapasien']) && $r['namapasien'] != "" && $r['namapasien'] != "undefined") {
            $data = $data->where('ps.namapasien', 'ilike', '%' . $r['namapasien'] . '%');
        }
        $total = $data->count();
        if (isset($r['offset']) && $r['offset'] != '') {
            $data = $data->offset($r['offset']);
        }
        if (isset($r['limit']) && $r['limit'] != '') {
            $data = $data->limit($r['limit']);
        }

        $data = $data->get();

        foreach ($data as $d) {
            $d->umur = $this->getAge($d->tgllahir, $d->tglregistrasi);
        }
        $result = [
            'data' => $data,
            'total' => $total
        ];
        return $this->respond($result);
    }
    public function getCombo(Request $r)
    {
        $res['kelompokpasien'] =  KelompokPasien::mine()->get();
        return $this->respond($res);
    }

    public function getDaftarResep(Request $request)
    {
        $rangeDate = [$request->tglAwal, $request->tglAkhir];
        $dataLogin = $request->all();
        $data = DB::table('strukresep_t as sr')
            ->JOIN('antrianpasiendiperiksa_t as apd', 'apd.norec', '=', 'sr.pasienfk')
            ->JOIN('pasiendaftar_t as pd', 'pd.norec', '=', 'apd.noregistrasifk')
            ->JOIN('pasien_m as ps', 'ps.id', '=', 'pd.nocmfk')
            ->leftJoin('pegawai_m as pg', 'pg.id', '=', 'sr.penulisresepfk')
            ->JOIN('ruangan_m as ru', 'ru.id', '=', 'apd.objectruanganfk')
            ->JOIN('departemen_m as dp', 'dp.id', '=', 'ru.objectdepartemenfk')
            ->JOIN('ruangan_m as ru2', 'ru2.id', '=', 'sr.ruanganfk')
            ->leftJoin('jeniskelamin_m as jk', 'jk.id', '=', 'ps.objectjeniskelaminfk')
            ->JOIN('kelompokpasien_m as kp', 'kp.id', '=', 'pd.objectkelompokpasienlastfk')
            ->leftJoin('rekanan_m as rk', 'rk.id', '=', 'pd.objectrekananfk')
            ->JOIN('kelas_m as kl', 'kl.id', '=', 'pd.objectkelasfk')
            ->JOIN('ruangan_m as ru1', 'ru1.id', '=', 'sr.ruanganfk')
            ->select(
                'sr.norec',
                'sr.statusenabled',
                'sr.noresep',
                'sr.tglresep',
                'pd.noregistrasi',
                'pd.norec as norec_pd',
                'pd.statusbayar',
                'ps.nocm',
                'ps.namapasien',
                'ru.id as ruid',
                'ru.namaruangan',
                'pg.id as pgid',
                'pg.namalengkap as dokter',
                'ru2.id as ruapotikid',
                'ru2.namaruangan as namaruanganapotik',
                'jk.jeniskelamin',
                'ps.tgllahir',
                'kl.id as klid',
                'kl.namakelas',
                'pd.tglregistrasi',
                'apd.norec as norec_apd',
                'kp.kelompokpasien'
            )
            ->where('sr.kdprofile', $this->kdProfile)
            ->whereBetween(DB::raw("CAST(sr.tglresep as DATE)"), $rangeDate)
            ->where('pd.statusenabled', true);

        if (isset($request['noregis']) && $request['noregis'] != "" && $request['noregis'] != "undefined") {
            $data = $data->where('pd.noregistrasi', 'ilike', '%' . $request['noregis']);
        }
        if (isset($request['qnocm']) && $request['qnocm'] != "" && $request['qnocm'] != "undefined") {
            $data = $data->where('ps.nocm', 'ilike', '%' . $request['qnocm']);
        }
        if (isset($request['ruanganfk']) && $request['ruanganfk'] != "" && $request['ruanganfk'] != "undefined") {
            $data = $data->where('ru2.id', $request['ruanganfk']);
        }
        if (isset($request['qnama']) && $request['qnama'] != "" && $request['qnama'] != "undefined") {
            $data = $data->where('ps.namapasien', 'ilike', '%' . $request['qnama'] . '%');
        }
        if (isset($request['statusbayar']) && $request['statusbayar'] != "" && $request['statusbayar'] != "undefined") {
            $data = $data->where('pd.statusbayar', $request['statusbayar']);
        }
        if (isset($request['qnocm']) && $request['qnocm'] != "" && $request['qnocm'] != "undefined") {
            $data = $data->where('ps.nocm', 'ilike', '%' . $request['qnocm']);
        }
        $data = $data->orderby('sr.noresep');
        $data = $data->get();

        $result = array(
            'daftar' => $data,
            'datalogin' => $dataLogin,
            'message' => 'as@epic',
        );

        return $this->respond($result);
    }

    public function listRuangan(Request $r)
    {
        $result['ruangan'] = Ruangan::mine()
            ->search($r['name'])
            ->limit($r['limit'])
            ->orderBy('namaruangan')
            ->get();
        return $this->respond($result);
    }
    public function getRuanganDepo(Request $r)
    {
        $depIds = explode(',', $this->settingFix('idInstalasiFarmasi'));
        $result['test'] = $depIds;
        $result['ruangan'] = Ruangan::mine()
            ->search($r['name'])
            ->limit($r['limit'])
            ->whereIn('objectdepartemenfk', $depIds)
            ->orderBy('namaruangan')
            ->get();
        return $this->respond($result);
    }
}
