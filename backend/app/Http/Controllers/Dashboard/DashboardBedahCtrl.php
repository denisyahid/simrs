<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Master\Pegawai;
use App\Models\Master\Ruangan;
use App\Models\Transaksi\AntrianPasienDiperiksa;
use App\Models\Transaksi\PasienDaftar;
use App\Models\Transaksi\PelayananPasien;
use App\Models\Transaksi\PelayananPasienDetail;
use App\Models\Transaksi\PelayananPasienPetugas;
use App\Models\Transaksi\StrukOrder;
use Exception;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Mockery\Exception\InvalidOrderException;
use PhpParser\Node\Stmt\TryCatch;

class DashboardBedahCtrl extends Controller
{

    public function __construct()
    {
        parent::__construct($is_encrypt = true);
    }
    public function listBedah(Request $r)
    {
        $set = explode(',', $this->settingFix('idDepartemenBedah'));
        $res['ruangan'] = Ruangan::mine()->whereIn('objectdepartemenfk', $set)->get();
        $res['idJenisPegawaiDokter'] = explode(',', $this->settingFix('idJenisPegawaiDokter'));
        $res['namalengkap'] =
            Pegawai::mine()
                ->where('objectjenispegawaifk', $res['idJenisPegawaiDokter'])
                ->search($r['label'])
                ->paging($r['limit'])
                ->get();
        $res['kamaroperasi'] = DB::table('kamaroperasi_m')
            ->select('id', 'namakamarok')
            ->where('kdprofile', $this->kdProfile)
            ->where('statusenabled', true)
            ->get();
        return $this->respond($res);
    }

    public function getOrderBedah(Request $request)
    {
        $dataOrder = DB::table('strukorder_t as so')
            ->join('pasiendaftar_t as pd', 'pd.norec', 'so.noregistrasifk')
            ->leftJoin('jenisoperasi_m as jp', 'jp.id', 'so.jenisoperasifk')
            // ->join('antrianpasiendiperiksa_t as apd', 'apd.noregistrasifk','pd.norec')
            ->join('pasien_m as pas', 'pd.nocmfk', 'pas.id')
            ->leftjoin('ruangan_m as ruAs', 'so.objectruanganfk', 'ruAs.id')
            ->join('ruangan_m as ruTu', 'so.objectruangantujuanfk', 'ruTu.id')
            ->join('pegawai_m as peg', 'peg.id', 'so.objectpegawaiorderfk')
            ->join('jeniskelamin_m as jk', 'jk.id', 'pas.objectjeniskelaminfk')
            ->join('kelompokpasien_m as kp', 'kp.id', 'pd.objectkelompokpasienlastfk')
            ->join('departemen_m as dep', 'dep.id', 'ruAs.objectdepartemenfk')
            ->join('departemen_m as dep2', 'dep2.id', 'ruTu.objectdepartemenfk')
            ->join('kelas_m as kls', 'kls.id', 'pd.objectkelasfk')
            ->leftjoin('pemakaianasuransi_t as pa', 'pa.noregistrasifk', '=', 'pd.norec')
            ->leftjoin('kamaroperasi_m as kam', 'kam.id', '=', 'so.objectkamaroperasifk')
            ->select(
                'so.norec',
                'pd.noregistrasi',
                'pd.norec as pd_norec',
                'so.noorder',
                'so.statusorder',
                'pas.objectkebangsaanfk',
                'so.nohp',
                'so.nohpkel',
                'so.norec_apd',
                'so.diagnosis',
                'jp.jenisoperasi',
                'pd.jenispelayanan as jenispelayananfk',
                'pd.tglregistrasi',
                'so.tglpelayananawal as tgloperasi',
                'pas.namapasien',
                'pas.tgllahir',
                'pas.nocm',
                'pd.nocmfk',
                'so.estimasiwaktuoperasi as estimasiwaktuoperasi',
                'pas.objectjeniskelaminfk',
                'so.statusoperasi',
                DB::raw("to_char(so.tglorder, 'DD-MM-YYYY HH:mm:ss') as tanggalorder"),
                DB::raw("to_char(so.tgloperasi, 'DD-MM-YYYY HH:mm:ss') as tanggaloperasi"),
                DB::raw("CAST(so.tglorder AS DATE)"),
                'pas.noidentitas',
                'jk.jeniskelamin',
                'kp.kelompokpasien',
                'kls.namakelas',
                'pd.objectkelasfk',
                'pd.objectrekananfk',
                'dep.namadepartemen as asldepartemen',
                'ruAs.namaruangan as asalruangan',
                'ruTu.namaruangan as ruangantujuan',
                'so.objectruangantujuanfk',
                'so.objectpegawaiorderfk',
                'so.cito',
                'so.iselektif',
                'so.isurgent',
                'so.tb',
                'so.bb',
                'so.alat',
                'dep2.namadepartemen as departementujuan',
                'peg.namalengkap',
                'pas.noidentitas',
                'pas.nobpjs',
                'pa.nosep',
                'pd.objectkelasfk',
                'so.objectkamaroperasifk',
                'kam.namakamarok',
                'so.isanastesi',
                'so.anastesitambahanfk',
                'so.operatorhelperfk',
                'so.keteranganlainnya',
                'so.riwayatswab'
            )
            // ->where(DB::raw("CAST(so.tglorder AS DATE)"), Date("Y-m-d"))
            ->where('so.kdprofile', $this->kdProfile)
            // ->where('so.objectruangantujuanfk', $this->settingFix('idDepartemenLab'))
            ->whereIn('dep2.id', explode(',', $this->settingFix('idDepartemenBedah')))
            ->where('so.statusenabled', true);

        if (isset($request['statusorder']) && $request['statusorder'] != '') {
            $dataOrder = $dataOrder->where('so.statusorder', '=', $request['statusorder']);
        }
        if (isset($request['opsi']) && $request['opsi'] != '' && $request['opsi'] == 'tglorder') {
            if (isset($request['dari']) && $request['dari'] != '') {
                $dataOrder = $dataOrder->where(DB::raw("so.tglorder::date"), '>=', $request->dari);
            }
            if (isset($request['sampai']) && $request['sampai'] != '') {
                $dataOrder = $dataOrder->where(DB::raw("so.tglorder::date"), '<=', $request->sampai);
            }
        } else if (isset($request['opsi']) && $request['opsi'] != '' && $request['opsi'] == 'tgloperasi') {
            if (isset($request['dari']) && $request['dari'] != '') {
                $dataOrder = $dataOrder->where(DB::raw("so.tgloperasi::date"), '>=', $request->dari);
            }
            if (isset($request['sampai']) && $request['sampai'] != '') {
                $dataOrder = $dataOrder->where(DB::raw("so.tgloperasi::date"), '<=', $request->sampai);
            }
        }
        if (isset($request['search']) && $request['search'] != '') {
            $searchTerm = '%' . $request['search'] . '%';
            $dataOrder = $dataOrder->where(function ($query) use ($searchTerm) {
                $query->where('pas.namapasien', 'ilike', $searchTerm)
                    ->orWhere('pd.noregistrasi', 'ilike', $searchTerm)
                    ->orWhere('pas.nocm', 'ilike', $searchTerm)
                    ->orWhere('pas.nobpjs', 'ilike', $searchTerm)
                    ->orWhere('so.noorder', 'ilike', $searchTerm)
                    ->orWhere('pas.noidentitas', 'ilike', $searchTerm);
            });
        }
        if (isset($request['ruanganid']) && $request['ruanganid'] != '') {
            $dataOrder = $dataOrder->where('ruTu.id', '=', $request['ruanganid']);
        }
        if (isset($request['noorder']) && $request['noorder'] != '') {
            $dataOrder = $dataOrder->where('so.noorder', '=', $request['noorder']);
        }
        if (isset($request['qnamapasien']) && $request['qnamapasien'] != '') {
            $dataOrder = $dataOrder->where('pas.namapasien', '=', $request['qnamapasien']);
        }
        if (isset($request['qnoregistrasi']) && $request['qnoregistrasi'] != '') {
            $dataOrder = $dataOrder->where('pd.noregistrasi', '=', $request['qnoregistrasi']);
        }
        if (isset($request['qnocm']) && $request['qnocm'] != '') {
            $dataOrder = $dataOrder->where('pas.nocm', '=', $request['qnocm']);
        }
        if (isset($request['id_dokter']) && $request['id_dokter'] != '') {
            $dataOrder = $dataOrder->where(function ($query) use ($request) {
                $query->where('so.dokteroperatorfk', $request['id_dokter'])
                    ->orWhereJsonContains('so.operatorhelperfk', (int) $request['id_dokter']);
            });
        }

        $dataOrder = $dataOrder->get();

        $result = [];
        foreach ($dataOrder as $datas) {

            // $detail = DB::table('detaildiagnosapasien_t as ddp')
            //     ->join('diagnosa_m as dg', 'dg.id', '=', 'ddp.objectdiagnosafk')
            //     ->Join('jenisdiagnosa_m as jd', 'jd.id', '=', 'ddp.objectjenisdiagnosafk')
            //     ->select('dg.kddiagnosa', 'dg.namadiagnosa', 'jd.jenisdiagnosa')
            //     // ->where('ddp.objectdiagnosapasienfk', $datas->dp_norec)
            //     ->where('ddp.kdprofile', $this->kdProfile)
            //     ->get();

            $result[] = [
                'namapasien' => $datas->namapasien,
                'noidentitas' => $datas->noidentitas,
                'noregistrasi' => $datas->noregistrasi,
                'so_norec' => $datas->norec,
                'jenisoperasi' => $datas->jenisoperasi,
                'pd_norec' => $datas->pd_norec,
                'nocmfk' => $datas->nocmfk,
                'iscito' => $datas->cito,
                'jenispelayananfk' => $datas->jenispelayananfk,
                'noorder' => $datas->noorder,
                'tglregistrasi' => $datas->tglregistrasi,
                'tgloperasi' => $datas->tgloperasi,
                'nocm' => $datas->nocm,
                'statusorder' => $datas->statusorder,
                'norec_apd' => $datas->norec_apd,
                'jeniskelamin' => $datas->jeniskelamin,
                'id_jenisK' => $datas->objectjeniskelaminfk,
                'kelompokpasien' => $datas->kelompokpasien,
                'namakelas' => $datas->namakelas,
                'objectrekananfk' => $datas->objectrekananfk,
                'objectkelasfk' => $datas->objectkelasfk,
                'asal_departemen' => $datas->asldepartemen,
                'asal_ruangan' => $datas->asalruangan,
                'ruangantujuan' => $datas->ruangantujuan,
                'objectruangantujuanfk' => $datas->objectruangantujuanfk,
                'departementujuan' => $datas->departementujuan,
                'nama_pegawai' => $datas->namalengkap,
                'tglorder' => $datas->tglorder,
                'tanggalorder' => $datas->tanggalorder,
                'tanggaloperasi' => $datas->tanggaloperasi,
                'objectpegawaiorderfk' => $datas->objectpegawaiorderfk,
                'estimasiwaktuoperasi' => $datas->estimasiwaktuoperasi,
                'umur' => $this->getAge($datas->tgllahir, $datas->tglorder),
                'nosep' => $datas->nosep,
                'objectkelasfk' => $datas->objectkelasfk,
                'statusoperasi' => $datas->statusoperasi,
                'keteranganlainnya' => $datas->keteranganlainnya,
                'riwayatswab' => $datas->riwayatswab,
                'objectkamaroperasifk' => $datas->objectkamaroperasifk,
                'namakamarok' => $datas->namakamarok,
                'objectkebangsaanfk' => $datas->objectkebangsaanfk,
                'nohp' => $datas->nohp,
                'tinggibadan' => $datas->tb,
                'beratbadan' => $datas->bb,
                'iselektif' => $datas->iselektif,
                'isurgent' => $datas->isurgent,
                'diagnosis' => $datas->diagnosis,
                'alat' => $datas->alat,
                'isanastesi' => $datas->isanastesi,
                'anastesitambahanfk' => $datas->anastesitambahanfk,
                'operatorhelperfk' => $datas->operatorhelperfk
                // 'detailDiagnosa' => $detail
            ];
        }
        return $this->respond($result);
    }

    public function getOperasiPasien(Request $request)
    {
        $data = DB::table('antrianpasiendiperiksa_t  as apd')
            ->join('pasiendaftar_t as pd', 'apd.noregistrasifk', '=', 'pd.norec')
            ->join('ruangan_m as ru', 'apd.objectruanganfk', '=', 'ru.id')
            ->leftjoin('pegawai_m as pg', 'apd.objectpegawaifk', '=', 'pg.id')
            ->leftjoin('pegawai_m as per', 'pd.perawatfk', '=', 'per.id')
            ->leftjoin('pasien_m as ps', 'pd.nocmfk', '=', 'ps.id')
            ->leftjoin('aksesemr_t as emr', 'emr.pasienfk', '=', 'ps.id')
            ->leftjoin('alamat_m as alm', 'alm.nocmfk', '=', 'ps.id')
            ->leftjoin('kotakabupaten_m as kbp', 'alm.objectkotakabupatenfk', '=', 'kbp.id')
            ->leftjoin('kecamatan_m as km', 'alm.objectkecamatanfk', '=', 'km.id')
            ->leftjoin('desakelurahan_m as ds', 'alm.objectdesakelurahanfk', '=', 'ds.id')
            ->leftjoin('kelompokpasien_m as kp', 'pd.objectkelompokpasienlastfk', '=', 'kp.id')
            ->leftjoin('jeniskelamin_m as jk', 'jk.id', '=', 'ps.objectjeniskelaminfk')
            ->leftjoin('kelas_m as kl', 'kl.id', '=', 'apd.objectkelasfk')
            ->leftjoin('pemakaianasuransi_t as pa', 'pa.noregistrasifk', '=', 'pd.norec')
            ->leftjoin('kendalidokumen_t as kd', 'kd.noregistrasifk', '=', 'pd.norec')
            ->leftjoin('kebangsaan_m as bg', 'ps.objectkebangsaanfk', '=', 'bg.id')
            ->select(
                'apd.iskonsul as konsul',
                'ru.namaruangan',
                'ru.kdinternal as kdpoli',
                'pd.norec as norec_pd',
                'pd.tglclosing',
                'pd.norec as pd_norec',
                'pd.nocmfk',
                'ps.nocm',
                'ps.nobpjs as nobpjs',
                'ds.namadesakelurahan',
                'km.namakecamatan',
                'kbp.namakotakabupaten',
                'ps.alamatrmh',
                'ps.nobpjs',
                'ps.noidentitas',
                'ps.tgllahir',
                'pd.objectpegawaifk',
                'pd.noregistrasi',
                'pg.namalengkap',
                'pg.id as pgid',
                'ps.namapasien',
                'jk.jeniskelamin',
                'emr.tglberakhir',
                'emr.tglmulai',
                'emr.objectkelompokuserfk',
                'emr.pegawaipemohonfk',
                'apd.noantrian',
                'per.namalengkap as perawat',
                'apd.status',
                'apd.norec as norec_apd',
                'kp.kelompokpasien',
                'pa.nosep',
                'pd.ismobilejkn',
                'pd.ischeckin',
                'pd.perawatfk',
                'pd.isasmed',
                'pd.iscppt',
                'kl.namakelas',
                'kd.isdikirim',
                'apd.objectstrukorderfk',
                'apd.tglkeluar',
                'ps.tglmeninggal',
                'apd.objectruanganfk',
                'ru.objectdepartemenfk',
                'ru.id as norecpoli',
                'apd.tglregistrasi',
                'bg.name as kebangsaan',
                DB::raw("CAST(pd.tglregistrasi AS DATE),ps.objectjeniskelaminfk"),
                'apd.tglmasuk'
            )
            ->where('ps.statusenabled', true)
            ->where('pd.statusenabled', true)
            ->where('pd.kdprofile', $this->kdProfile)
            ->where('apd.iskonsul', true)
            ->where('apd.statusenabled', true)
            // ->where('apd.objectruanganfk', $this->settingFix('idRuanganBedah'))
            ->whereIn('ru.objectdepartemenfk', explode(',', $this->settingFix('kdDepartementBedahFix')))
            ->whereNotNull('apd.tglkeluar');

        $filter = false;

        if (isset($request['search']) && $request['search'] != '') {
            $searchTerm = '%' . $request['search'] . '%';
            $data = $data->where(function ($query) use ($searchTerm) {
                $query->where('ps.namapasien', 'ilike', $searchTerm)
                    ->orWhere('pd.noregistrasi', 'ilike', $searchTerm)
                    ->orWhere('ps.nocm', 'ilike', $searchTerm);
            });
        }
        if (isset($request['dari']) && $request['dari'] != '') {
            $data = $data->where(DB::raw("apd.tglmasuk::date"), '>=', $request->dari);
        }
        if (isset($request['sampai']) && $request['sampai'] != '') {
            $data = $data->where(DB::raw("apd.tglmasuk::date"), '<=', $request->sampai);
        }
        if (isset($request['qnoregistrasi']) && $request['qnoregistrasi'] != '') {
            $filter = true;
            $data = $data->where('pd.noregistrasi', '=', $request['qnoregistrasi']);
        }
        if (isset($request['qnocm']) && $request['qnocm'] != '') {
            $filter = true;
            $data = $data->where('ps.nocm', '=', $request['qnocm']);
        }
        if (isset($request['qnamapasien']) && $request['qnamapasien'] != '') {
            $filter = true;
            $data = $data->where('ps.namapasien', 'ilike', '%' . $request['qnamapasien'] . '%');
        }

        $page = 1;
        if (isset($request['page']) && $request['page'] != '') {
            $page = $request['page'];
        }
        if (isset($request['statusclosing']) && $request['statusclosing'] != '') {
            $filter = true;
            $data = $data->where('pd.tglclosing', '!=', null);
        } else {
            $filter = true;
            $data = $data->where('pd.tglclosing', '=', null);
        }

        $data = $data->get();

        // Flag untuk memeriksa apakah pasien tersebut sudah mendapatkan tindakan operasi atau belum
        $norec_apd_pasien = $data->pluck('norec_apd');
        $check_pp = DB::table('pelayananpasien_t as pp')
            ->select('pp.noregistrasifk')
            ->where('pp.statusenabled', true)
            ->whereIn('pp.noregistrasifk', $norec_apd_pasien)
            ->pluck('noregistrasifk')
            ->toArray();

        foreach ($data as $dt) {
            $dt->pelayananpasien = in_array($dt->norec_apd, $check_pp);
        }

        if (isset($request['id_dokter']) && $request['id_dokter'] != '') {
            $nocmfk_pasien = $data->pluck('nocmfk');

            // Mengambil orderan bedah terakhir
            $subquery = DB::table('strukorder_t')
                ->select('nocmfk', DB::raw('MAX(tglorder) as latest_tglorder'))
                ->where('statusenabled', true)
                ->where('objectkelompoktransaksifk', 22) // Orderan bedah
                ->whereIn('nocmfk', $nocmfk_pasien)
                ->groupBy('nocmfk');

            // Filter
            $riwayatOrderBedah = DB::table('strukorder_t as so')
                ->joinSub($subquery, 'latest_orders', function ($join) {
                    $join->on('so.nocmfk', '=', 'latest_orders.nocmfk')
                        ->on('so.tglorder', '=', 'latest_orders.latest_tglorder');
                })
                ->select('so.nocmfk', 'so.norec', 'so.tglorder', 'so.dokteroperatorfk', 'so.operatorhelperfk')
                ->where('so.statusenabled', true)
                ->where('so.objectkelompoktransaksifk', 22)
                ->where(function ($query) use ($request) {
                    $query->where('so.dokteroperatorfk', $request['id_dokter'])
                        ->orWhereJsonContains('so.operatorhelperfk', (int) $request['id_dokter']);
                })
                ->get();

            $validNocmfk = $riwayatOrderBedah->pluck('nocmfk')->unique();
            $data = $data->filter(function ($item) use ($validNocmfk) {
                return $validNocmfk->contains($item->nocmfk);
            })->values();
        }

        $result = array(
            "data" => $data
        );

        return $this->respond($result);
    }

    public function getOperasiCathlab(Request $request)
    {
        $data = DB::table('antrianpasiendiperiksa_t  as apd')
            ->join('pasiendaftar_t as pd', 'apd.noregistrasifk', '=', 'pd.norec')
            ->join('ruangan_m as ru', 'apd.objectruanganfk', '=', 'ru.id')
            ->leftjoin('pegawai_m as pg', 'apd.objectpegawaifk', '=', 'pg.id')
            ->leftjoin('pegawai_m as per', 'pd.perawatfk', '=', 'per.id')
            ->leftjoin('pasien_m as ps', 'pd.nocmfk', '=', 'ps.id')
            ->leftjoin('aksesemr_t as emr', 'emr.pasienfk', '=', 'ps.id')
            ->leftjoin('alamat_m as alm', 'alm.nocmfk', '=', 'ps.id')
            ->leftjoin('kotakabupaten_m as kbp', 'alm.objectkotakabupatenfk', '=', 'kbp.id')
            ->leftjoin('kecamatan_m as km', 'alm.objectkecamatanfk', '=', 'km.id')
            ->leftjoin('desakelurahan_m as ds', 'alm.objectdesakelurahanfk', '=', 'ds.id')
            ->leftjoin('kelompokpasien_m as kp', 'pd.objectkelompokpasienlastfk', '=', 'kp.id')
            ->leftjoin('jeniskelamin_m as jk', 'jk.id', '=', 'ps.objectjeniskelaminfk')
            ->leftjoin('kelas_m as kl', 'kl.id', '=', 'apd.objectkelasfk')
            ->leftjoin('pemakaianasuransi_t as pa', 'pa.noregistrasifk', '=', 'pd.norec')
            ->leftjoin('kendalidokumen_t as kd', 'kd.noregistrasifk', '=', 'pd.norec')
            ->leftjoin('kebangsaan_m as bg', 'ps.objectkebangsaanfk', '=', 'bg.id')
            ->select(
                'apd.iskonsul as konsul',
                'ru.namaruangan',
                'ru.kdinternal as kdpoli',
                'pd.norec as norec_pd',
                'pd.tglclosing',
                'pd.norec as pd_norec',
                'pd.nocmfk',
                'ps.nocm',
                'ps.nobpjs as nobpjs',
                'ds.namadesakelurahan',
                'km.namakecamatan',
                'kbp.namakotakabupaten',
                'ps.alamatrmh',
                'ps.nobpjs',
                'ps.noidentitas',
                'ps.tgllahir',
                'pd.objectpegawaifk',
                'pd.noregistrasi',
                'pg.namalengkap',
                'pg.id as pgid',
                'ps.namapasien',
                'jk.jeniskelamin',
                'emr.tglberakhir',
                'emr.tglmulai',
                'emr.objectkelompokuserfk',
                'emr.pegawaipemohonfk',
                'apd.noantrian',
                'per.namalengkap as perawat',
                'apd.status',
                'apd.norec as norec_apd',
                'kp.kelompokpasien',
                'pa.nosep',
                'pd.ismobilejkn',
                'pd.ischeckin',
                'pd.perawatfk',
                'pd.isasmed',
                'pd.iscppt',
                'kl.namakelas',
                'kd.isdikirim',
                'apd.objectstrukorderfk',
                'apd.tglkeluar',
                'ps.tglmeninggal',
                'apd.objectruanganfk',
                'ru.objectdepartemenfk',
                'ru.id as norecpoli',
                'apd.tglregistrasi',
                'bg.name as kebangsaan',
                DB::raw("CAST(pd.tglregistrasi AS DATE),ps.objectjeniskelaminfk"),
                'apd.tglmasuk'
            )
            ->where('apd.iskonsul', true)
            ->where('pd.kdprofile', $this->kdProfile)
            ->whereIn('ru.objectdepartemenfk', explode(',', $this->settingFix('KdDepartemenCathlab')))
            ->whereNotNull('apd.tglkeluar')
            ->where('ps.statusenabled', true)
            ->where('pd.statusenabled', true)
            ->where('apd.statusenabled', true)
            ->whereNull('pd.tglclosing');
        $filter = false;

        if (isset($request['search']) && $request['search'] != '') {
            $searchTerm = '%' . $request['search'] . '%';
            $data = $data->where(function ($query) use ($searchTerm) {
                $query->where('ps.namapasien', 'ilike', $searchTerm)
                    ->orWhere('pd.noregistrasi', 'ilike', $searchTerm)
                    ->orWhere('ps.nocm', 'ilike', $searchTerm);
            });
        }
        if (isset($request['dari']) && $request['dari'] != '') {
            $data = $data->where(DB::raw("apd.tglmasuk::date"), '>=', $request->dari);
        }
        if (isset($request['sampai']) && $request['sampai'] != '') {
            $data = $data->where(DB::raw("apd.tglmasuk::date"), '<=', $request->sampai);
        }

        if (isset($request['qnoregistrasi']) && $request['qnoregistrasi'] != '') {
            $filter = true;
            $data = $data->where('pd.noregistrasi', '=', $request['qnoregistrasi']);
        }
        if (isset($request['qnocm']) && $request['qnocm'] != '') {
            $filter = true;
            $data = $data->where('ps.nocm', '=', $request['qnocm']);
        }

        if (isset($request['qnamapasien']) && $request['qnamapasien'] != '') {
            $filter = true;
            $data = $data->where('ps.namapasien', 'ilike', '%' . $request['qnamapasien'] . '%');
        }

        $page = 1;
        if (isset($request['page']) && $request['page'] != '') {
            $page = $request['page'];
        }
        // $data = $data->orderBy('apd.noantrian');
        $data = $data->orderBy('apd.tglregistrasi', 'DESC');
        // $data = $data->get();
        $data = $data->paginate(isset($request['limit']) ? $reques['limit'] : 50, ['*'], 'page', $page);

        return $this->respond($data);
    }


    public function getJadwalOperasi(Request $request)
    {
        $dataOrder = DB::table('strukorder_t as so')
            ->join('orderpelayanan_t as op', 'so.norec', '=', 'op.strukorderfk')
            ->join('pasiendaftar_t as pd', 'pd.norec', 'so.noregistrasifk')
            ->leftjoin('produk_m as pr', 'pr.id', 'op.objectprodukfk')
            ->join('pasien_m as pas', 'pd.nocmfk', 'pas.id')
            ->leftjoin('antrianpasiendiperiksa_t as apd', 'so.norec', '=', 'apd.objectstrukorderfk')
            ->leftjoin('ruangan_m as ruAs', 'so.objectruanganfk', 'ruAs.id')
            ->join('ruangan_m as ruTu', 'so.objectruangantujuanfk', 'ruTu.id')
            ->leftjoin('pegawai_m as peg', 'peg.id', 'apd.objectpegawaifk')
            ->join('jeniskelamin_m as jk', 'jk.id', 'pas.objectjeniskelaminfk')
            ->join('kelompokpasien_m as kp', 'kp.id', 'pd.objectkelompokpasienlastfk')

            ->join('kelas_m as kls', 'kls.id', 'pd.objectkelasfk')

            ->select(
                'so.norec',
                'pd.noregistrasi',
                'pd.norec as pd_norec',
                'so.noorder',
                'so.statusorder',
                'pd.jenispelayanan as jenispelayananfk',
                'pd.tglregistrasi',
                'pas.namapasien',
                'pas.tgllahir',
                'pas.nocm',
                'so.tglpelayananawal',
                'pas.noidentitas',
                'jk.jeniskelamin',
                'kp.kelompokpasien',
                'kls.namakelas',
                'ruAs.namaruangan as asalruangan',
                'ruTu.namaruangan as ruangantujuan',
                'peg.namalengkap',
                'pr.namaproduk',
            )
            ->where(DB::raw("CAST(so.tglpelayananawal AS DATE)"), $request['tgl'])
            ->where('so.kdprofile', $this->kdProfile)
            ->where('so.statusorder', '1')
            ->where('ruTu.objectdepartemenfk', $this->settingFix('idDepartemenBedah'))
            ->where('so.statusenabled', true);

        if (isset($request['ruanganid']) && $request['ruanganid'] != '') {
            $dataOrder = $dataOrder->where('ruTu.id', '=', $request['ruanganid']);
        }
        // if(isset($request['tgl']) && $request['tgl'] !=''){
        //     $dataOrder = $dataOrder ->whereRaw("to_char(so.tglpelayananawal,'yyyy-MM-dd') = '$request[tgl]'");
        // }
        if (isset($request['noorder']) && $request['noorder'] != '') {
            $dataOrder = $dataOrder->where('so.noorder', '=', $request['noorder']);
        }

        $dataOrder = $dataOrder->get();

        $res['dataOperasi'] = $dataOrder;
        return $this->respond($res);
    }

    public function getOrderPelayananBedah(Request $request)
    {
        $so = StrukOrder::where('norec', $request['strukorderfk'])->where('kdprofile', $this->kdProfile)->first();
        $pasienDaftar = PasienDaftar::where('norec', $so->noregistrasifk)->where('kdprofile', $this->kdProfile)->first();
        $jp = (int) $pasienDaftar->jenispelayanan;
        $idpenjamin = '-1'; // $pasienDaftar->objectrekananfk == null ? '-1' : $pasienDaftar->objectrekananfk;

        if ($idpenjamin != "-1") {
            $dataOrderPelayanan = DB::table('strukorder_t as so')
                ->leftjoin('orderpelayanan_t as op', function ($join) use ($request) {
                    $join->on('so.norec', '=', 'op.strukorderfk')
                        ->where('op.kdprofile', $this->kdProfile)
                        ->where('op.strukorderfk', $request['strukorderfk']);
                })
                ->leftjoin('produk_m as pr', 'pr.id', 'op.objectprodukfk')
                ->leftjoin('harganettoprodukbykelas_m as hnp', function ($join) use ($request, $jp, $idpenjamin) {
                    $join->on('hnp.objectprodukfk', '=', 'pr.id')
                        ->where('hnp.objectkelasfk', '=', $request['objectkelasfk'])
                        ->where('hnp.objectjenispelayananfk', '=', $jp)
                        ->where('hnp.objectpenjaminfk', '=', $idpenjamin);
                })
                ->leftjoin('kelas_m as kls', 'kls.id', 'hnp.objectkelasfk')
                ->leftjoin('ruangan_m as ru', 'ru.id', 'so.objectruangantujuanfk')
                ->leftjoin('ruangan_m as ru2', 'so.objectruanganfk', 'ru2.id')
                ->leftjoin('departemen_m as dpm', 'dpm.id', 'ru.objectdepartemenfk')
                ->leftjoin('jenisoperasi_m as jo', 'jo.id', 'so.jenisoperasifk')
                ->leftjoin('pegawai_m as pg', 'pg.id', 'so.dokteroperatorfk')
                ->leftjoin('pegawai_m as pg1', 'pg1.id', 'so.dokteranastesifk')
                ->leftjoin('pegawai_m as pg2', 'pg2.id', 'so.dokteranakfk')
                ->leftjoin('pegawai_m as pg3', 'pg3.id', 'so.penerimafk')
                ->leftjoin('pegawai_m as pg4', 'pg4.id', 'so.objectpegawaiorderfk')
                ->leftjoin('pelayananpasien_t as pps', function ($join) {
                    $join->on('pps.strukorderfk', '=', 'so.norec')->on('op.objectprodukfk', '=', 'pps.produkfk');
                })
                ->select(DB::raw(
                    "DISTINCT op.norec as norec_op,
                    so.tglpelayananawal as tgloperasi,
                    pr.id as prid,
                    pr.namaproduk,
                    hnp.objectkelasfk,
                    op.tglpelayanan,
                    op.qtyproduk,
                    ru.namaruangan as ruangantujuan,
                    ru2.namaruangan as asalruangan,
                    ru.objectdepartemenfk,
                    op.strukorderfk,
                    so.objectruangantujuanfk,
                    hnp.hargasatuan,
                    kls.namakelas,
                    so.keteranganlainnya as rencanatindakan
                    dpm.namadepartemen,
                    so.dokteroperatorfk,
                    so.tgloperasi,
                    so.dokteranastesifk,
                    so.diagnosis,
                    so.durasi,
                    so.persiapan,
                    so.keteranganlainnya,
                    so.tb,
                    so.bb,
                    so.jaminan,
                    so.objectpegawaiorderfk,
                    pg.namalengkap as dokteroperator,
                    pg1.namalengkap as dokteranastesi,
                    pg2.namalengkap as dokteranak,
                    pg3.namalengkap as namapenerima,
                    pg4.namalengkap as pengorder,
                    so.jenisoperasifk,
                    jo.jenisoperasi,
                    so.statusoperasi,
                    pps.norec as norec_pp, CASE WHEN hnp.hargadijamin IS NULL THEN 0 ELSE hnp.hargadijamin END AS hargadijamin"
                ))
                ->where('so.norec', $request['strukorderfk'])
                ->get();
        } else {
            $dataOrderPelayanan = [];
        }
        if (count($dataOrderPelayanan) == 0) {
            $dataOrderPelayanan = DB::table('strukorder_t as so')
                ->leftjoin('orderpelayanan_t as op', function ($join) use ($request) {
                    $join->on('so.norec', '=', 'op.strukorderfk')
                        ->where('op.kdprofile', $this->kdProfile)
                        ->where('op.strukorderfk', $request['strukorderfk']);
                })
                ->leftjoin('produk_m as pr', 'pr.id', 'op.objectprodukfk')
                ->leftjoin('harganettoprodukbykelas_m as hnp', function ($join) use ($request, $jp, $idpenjamin) {
                    $join->on('hnp.objectprodukfk', '=', 'pr.id')
                        ->where('hnp.objectkelasfk', '=', $request['objectkelasfk'])
                        ->where('hnp.objectjenispelayananfk', '=', $jp)
                        ->where('hnp.objectpenjaminfk', '=', $idpenjamin);
                })
                ->leftjoin('kelas_m as kls', 'kls.id', 'hnp.objectkelasfk')
                ->leftjoin('ruangan_m as ru', 'ru.id', 'so.objectruangantujuanfk')
                ->leftjoin('ruangan_m as ru2', 'ru2.id', 'so.objectruanganfk')
                ->leftjoin('departemen_m as dpm', 'dpm.id', 'ru.objectdepartemenfk')
                ->leftjoin('jenisoperasi_m as jo', 'jo.id', 'so.jenisoperasifk')
                ->leftjoin('pegawai_m as pg', 'pg.id', 'so.dokteroperatorfk')
                ->leftjoin('pegawai_m as pg1', 'pg1.id', 'so.dokteranastesifk')
                ->leftjoin('pegawai_m as pg2', 'pg2.id', 'so.dokteranakfk')
                ->leftjoin('pegawai_m as pg3', 'pg3.id', 'so.penerimafk')
                ->leftjoin('pegawai_m as pg4', 'pg4.id', 'so.objectpegawaiorderfk')
                ->leftjoin('pelayananpasien_t as pps', function ($join) {
                    $join->on('pps.strukorderfk', '=', 'so.norec')
                        ->on('pps.produkfk', '=', 'pr.id')->on('pps.kdprofile', 'so.kdprofile');
                })

                ->select(DB::raw("op.norec as norec_op,
                pr.id as prid,
                so.tglpelayananawal as tgloperasi,
                pr.namaproduk,op.tglpelayanan,
                op.qtyproduk ,
                ru.namaruangan as ruangantujuan,
                ru2.namaruangan as asalruangan,
                ru.objectdepartemenfk,
                op.strukorderfk,
                so.objectruangantujuanfk,
                hnp.objectkelasfk,
                hnp.hargasatuan,
                kls.namakelas,
                dpm.namadepartemen,
                so.dokteroperatorfk,
                so.tgloperasi,
                so.dokteranastesifk,
                so.diagnosis,
                so.durasi,
                so.persiapan,
                so.keteranganlainnya,
                so.tb,
                so.riwayatswab,
                so.alat,
                so.bb,
                so.jaminan,
                so.tglselesai,
                so.dokteranakfk,
                so.penerimafk,
                so.statusoperasi,
                so.objectpegawaiorderfk,
                pg.namalengkap as dokteroperator,
                pg1.namalengkap as dokteranastesi,
                pg2.namalengkap as dokteranak,
                pg3.namalengkap as namapenerima,
                pg4.namalengkap as pengorder,
                so.jenisoperasifk,
                jo.jenisoperasi,
                so.statusoperasi,
                pps.norec as norec_pp, CASE WHEN hnp.hargadijamin IS NULL THEN 0 ELSE hnp.hargadijamin END AS hargadijamin
                "))
                ->where('so.norec', $request['strukorderfk'])
                ->get();
        } else {
            return $this->respond('nothing');
        }

        $result = [];
        foreach ($dataOrderPelayanan as $item) {
            $datas = DB::table('harganettoprodukbykelasd_m as hnp')
                ->leftjoin('produk_m as prd', 'prd.id', 'hnp.objectprodukfk')
                ->leftjoin('komponenharga_m as kh', 'kh.id', 'hnp.objectkomponenhargafk')
                ->leftjoin('kelas_m as kls', 'kls.id', 'hnp.objectkelasfk')
                ->select(DB::raw(
                    "distinct hnp.objectkomponenhargafk,kh.komponenharga,hnp.hargasatuan,
                                          hnp.objectprodukfk,hnp.objectjenispelayananfk,
                                          CASE WHEN hnp.hargadijamin IS NULL THEN 0 ELSE hnp.hargadijamin END AS hargadijamin"
                ))
                ->where('hnp.kdprofile', $this->kdProfile)
                ->where('hnp.objectkelasfk', $item->objectkelasfk)
                ->where('hnp.objectpenjaminfk', $idpenjamin)
                ->where('hnp.statusenabled', true)
                ->where('objectjenispelayananfk', $jp)
                ->where('prd.id', $item->prid)
                ->get();

            if (count($datas) == 0) {
                $datas = DB::table('harganettoprodukbykelasd_m as hnp')
                    ->leftjoin('produk_m as prd', 'prd.id', 'hnp.objectprodukfk')
                    ->leftjoin('komponenharga_m as kh', 'kh.id', 'hnp.objectkomponenhargafk')
                    ->leftjoin('kelas_m as kls', 'kls.id', 'hnp.objectkelasfk')
                    ->select(DB::raw(
                        "distinct hnp.objectkomponenhargafk,kh.komponenharga,hnp.hargasatuan,
                                              hnp.objectprodukfk,hnp.objectjenispelayananfk,
                                              CASE WHEN hnp.hargadijamin IS NULL THEN 0 ELSE hnp.hargadijamin END AS hargadijamin"
                    ))
                    ->where('hnp.kdprofile', $this->kdProfile)
                    ->where('hnp.objectkelasfk', $item->objectkelasfk)
                    ->where('hnp.objectpenjaminfk', null)
                    ->where('hnp.statusenabled', true)
                    ->where('hnp.objectjenispelayananfk', $jp)
                    ->where('prd.id', $item->prid)
                    ->get();
            }
            $nilaiCito = 0;
            $nilaiStatusCito = 0;

            $result[] = array(
                'norec_op' => $item->norec_op,
                'norec_pp' => $item->norec_pp,
                'prid' => $item->prid,
                'tgloperasi' => $item->tgloperasi,
                'namaproduk' => $item->namaproduk,
                'qtyproduk' => $item->qtyproduk,
                'tglpelayanan' => $item->tglpelayanan,
                'idruangan' => $item->objectruangantujuanfk,
                'ruangantujuan' => $item->ruangantujuan,
                'asalruangan' => $item->asalruangan,
                'hargasatuan' => $item->hargasatuan,
                'hargadijamin' => $item->hargadijamin,
                'namakelas' => $item->namakelas,
                'objectdepartemenfk' => $item->objectdepartemenfk,
                'namadepartemen' => $item->namadepartemen,
                'cito' => $nilaiCito,
                'nilaiStatusCito' => $nilaiStatusCito,
                'objectrekananfk' => $pasienDaftar->objectrekananfk,
                'dokteroperatorfk' => $item->dokteroperatorfk,
                'pegawaiorderfk' => $item->objectpegawaiorderfk,
                'pengorder' => $item->pengorder,
                'penerimafk' => $item->penerimafk,
                'tglselesai' => $item->tglselesai,
                'dokteranastesifk' => $item->dokteranastesifk,
                'dokteranakfk' => $item->dokteranakfk,
                'diagnosis' => $item->diagnosis,
                'durasi' => $item->durasi,
                'keteranganlainnya' => $item->keteranganlainnya,
                'persiapan' => $item->persiapan,
                'tb' => $item->tb,
                'bb' => $item->bb,
                'alat' => $item->alat,
                'riwayatswab' => $item->riwayatswab,
                'jaminan' => $item->jaminan,
                'dokteroperator' => $item->dokteroperator,
                'dokteranak' => $item->dokteranak,
                'jenisoperasi' => $item->jenisoperasi,
                'jenisoperasifk' => $item->jenisoperasifk,
                'statusoperasi' => $item->statusoperasi,
                'namapenerima' => $item->namapenerima,
                'dokteranastesi' => $item->dokteranastesi,
                'komponenharga' => $datas,
            );
        }

        return $this->respond($result);
    }

    public function getKomponenHargaBedah(Request $request)
    {
        $data = DB::table('harganettoprodukbykelasd_m as hnp')
            ->join('produk_m as prd', 'prd.id', '=', 'hnp.objectprodukfk')
            ->join('komponenharga_m as kh', 'kh.id', '=', 'hnp.objectkomponenhargafk')
            ->join('kelas_m as kls', 'kls.id', '=', 'hnp.objectkelasfk')
            ->select('hnp.objectkomponenhargafk', 'kh.komponenharga', 'hnp.hargasatuan', 'hnp.objectprodukfk', 'kh.iscito')
            ->where('hnp.kdprofile', $this->kdProfile)
            ->where('hnp.objectkelasfk', $request['idKelas'])
            ->where('hnp.objectprodukfk', $request['idProduk'])
            ->where('hnp.objectjenispelayananfk', $request['idJenLayan'])
            ->where('hnp.statusenabled', true)
            ->distinct()
            ->get();

        return $this->respond($data);
    }

    public function getPelayanaBedah(Request $request)
    {
        $datas = DB::table('mapruangantoproduk_m as mpr')
            ->join('harganettoprodukbykelas_m as hnp', function ($join) {
                $join->on('hnp.objectprodukfk', 'mpr.objectprodukfk')
                    ->where('hnp.statusenabled', true);
            })
            ->join('produk_m as prd', 'prd.id', '=', 'mpr.objectprodukfk')
            // ->join ('kelas_m as kls','kls.id','hnp.objectkelasfk')
            ->join('ruangan_m as ru', 'ru.id', '=', 'mpr.objectruanganfk')
            ->select(
                'mpr.id',
                'prd.id as idpro',
                'prd.namaproduk',
                'hnp.hargasatuan',
                'ru.namaruangan',
                'mpr.objectprodukfk',
                'mpr.objectruanganfk'
            )
            ->where('mpr.kdprofile', $this->kdProfile)
            ->where('hnp.objectkelasfk', $request['idkelas'])
            ->where('hnp.objectjenispelayananfk', $request['idjenispelayanan'])
            ->where('hnp.objectkebangsaanfk', $request['objectkebangsaanfk'])
            ->where('mpr.objectruanganfk', $request['objectruangantujuanfk'])
            ->whereIN('ru.objectdepartemenfk', explode(',', $this->settingFix('idDepartemenBedah')))
            ->where('mpr.statusenabled', true);

        if ($request->idProduk) {
            $datas = $datas->where('mpr.objectprodukfk', $request['idProduk']);
        }

        //var_dump($datas->toSql());
        $datas = $datas->groupBy(
            'mpr.id',
            'prd.id',
            'prd.namaproduk',
            'hnp.hargasatuan',
            'ru.namaruangan',
            'mpr.objectprodukfk',
            'mpr.objectruanganfk'
        );
        $datas = $datas->get();

        return $this->respond($datas);
    }

    // public function ListVerif()
    // {
    //     $praktek  = DB::table('jadwaldokter_m as jd')
    //         ->leftjoin('ruangan_m as ru', 'ru.id', '=', 'jd.objectruanganfk')
    //         ->leftjoin('pegawai_m as pg', 'pg.id', '=', 'jd.objectpegawaifk')
    //         ->select(
    //             'ru.id',
    //             'ru.namaruangan',
    //             'pg.namalengkap',
    //             'jd.jammulai',
    //             'jd.jamakhir',
    //             DB::raw("lower(jd.hari) as hari"),
    //         )
    //         ->where('jd.kdprofile', $this->kdProfile)
    //         ->where('jd.statusenabled', '=', 'true')
    //         ->where('ru.objectdepartemenfk', $this->settingFix('idDepartemenBedah'));

    //     $praktek =  $praktek->get();

    //     return $this->respond($praktek);
    // }

    public function savePelayananPasienBedah(Request $request)
    {
        $parameter = $request['parameter'];
        $dataOrder = $request['data'];
        DB::beginTransaction();
        $apd = AntrianPasienDiperiksa::where('noregistrasifk', $parameter['pd_norec'])
            ->where('kdprofile', $this->kdProfile)
            ->where('objectruanganfk', $parameter['idruangtujuan'])
            ->where('statusenabled', true)
            ->first();
        $getLastAntrian = AntrianPasienDiperiksa::where('objectruanganfk', $parameter['idruangtujuan'])
            ->where('tglregistrasi', '>=', date('Y-m-d', strtotime($parameter['tglregistrasi'])) . ' 00:00')
            ->where('tglregistrasi', '<=', date('Y-m-d', strtotime($parameter['tglregistrasi'])) . ' 23:59')
            ->where('statusenabled', true)
            ->max('noantrian');
        if (!$apd) {

            $dataAPD = new AntrianPasienDiperiksa;
            $dataAPD->norec = $dataAPD->generateNewId();
            $dataAPD->kdprofile = $this->kdProfile;
            ;
            $dataAPD->objectasalrujukanfk = 1;
            $dataAPD->statusenabled = true;
            $dataAPD->objectkelasfk = $parameter['objectkelasfk'];
            $dataAPD->noantrian = $getLastAntrian + 1;
            $dataAPD->noregistrasifk = $parameter['pd_norec'];
            $dataAPD->objectpegawaifk = $parameter['objectpegawaiorderfk'];
            $dataAPD->objectruanganfk = $parameter['idruangtujuan'];
            $dataAPD->statusantrian = 0;
            $dataAPD->statuspasien = 1;
            $dataAPD->iskonsul = $parameter['iscito'] ? true : null;
            $dataAPD->status = "Belum Dipanggil";
            $dataAPD->objectstrukorderfk = $parameter['so_norec'];
            $dataAPD->tglregistrasi = $parameter['tglregistrasi']; // date('Y-m-d H:i:s');
            $dataAPD->tglmasuk = date('Y-m-d H:i:s');
            $dataAPD->tglkeluar = date('Y-m-d H:i:s');
            $dataAPD->noregistrasi = $parameter['noregistrasi'];
            $dataAPD->save();
            $dataAPDnorec = $dataAPD->norec;
            $dataAPDtglPel = $dataAPD->tglregistrasi;
        } else {
            $dataAPDnorec = $apd->norec;
            $dataAPDtglPel = $apd->tglregistrasi;
        }

        try {

            StrukOrder::where('norec', $parameter['so_norec'])
                ->where('kdprofile', $this->kdProfile)
                ->update(
                    [
                        'statusorder' => $parameter['statusoperasi'] == 3 ? 2 : 1,
                        'norec_apd' => $dataAPDnorec,
                        'durasi' => $parameter['estimasiwaktuoperasi'],
                        'objectkamaroperasifk' => $parameter['kamaroperasifk'],
                        // 'jenisoperasifk' => $parameter['jenisoperasifk'],
                        'dokteroperatorfk' => $parameter['dokteroperatorfk'],
                        'dokteranastesifk' => $parameter['dokteranastesifk'],
                        'tglselesai' => $parameter['tglselesai'],
                        'tgloperasi' => $parameter['tgloperasi'],
                        'objectpegawaiorderfk' => $parameter['objectpegawaiorderfk'],
                        'dokteranakfk' => $parameter['dokteranakfk'],
                        'penerimafk' => $parameter['penerimafk'],
                        'statusoperasi' => $parameter['statusoperasi'],
                        'anastesitambahanfk' => $parameter['arranastesi'],
                        'operatorhelperfk' => $parameter['arroperator']
                    ]
                );
            AntrianPasienDiperiksa::where('norec', $dataAPDnorec)->update([
                'iskonsul' => $parameter['iscito'] ? true : null
            ]);

            DB::commit();
            $result = [
                'message' => 'Data Berhasil disimpan',
                'parameter' => $parameter,
                //'pelPasien' => $dataPelayanan,
                // 'petugaspelayanan' => $PelPasienPetugas,
                //'detailPelayanan' => $PelPasienDetail,
                'kode' => 200
            ];
        } catch (InvalidOrderException $e) {
            DB::rollBack();

            $result = [
                'message' => 'Data Gagal Disimpan, Silakan Periksa Kembali Data',
                'status' => $e->getMessage(),
                'kode' => 400
            ];
        }
        return $this->respond($result, $result['kode'], $result['message']);
    }

    public function savePelayananPasienBedahBaheula(Request $request)
    {
        $parameter = $request['parameter'];
        $dataOrder = $request['data'];
        DB::beginTransaction();
        $apd = AntrianPasienDiperiksa::where('noregistrasifk', $parameter['pd_norec'])
            ->where('kdprofile', $this->kdProfile)
            ->where('objectruanganfk', $parameter['idruangtujuan'])
            ->where('statusenabled', true)
            ->first();
        $getLastAntrian = AntrianPasienDiperiksa::where('objectruanganfk', $parameter['idruangtujuan'])
            ->where('tglregistrasi', '>=', date('Y-m-d', strtotime($parameter['tglregistrasi'])) . ' 00:00')
            ->where('tglregistrasi', '<=', date('Y-m-d', strtotime($parameter['tglregistrasi'])) . ' 23:59')
            ->where('statusenabled', true)
            ->max('noantrian');
        if (!$apd) {

            $dataAPD = new AntrianPasienDiperiksa;
            $dataAPD->norec = $dataAPD->generateNewId();
            $dataAPD->kdprofile = $this->kdProfile;
            ;
            $dataAPD->objectasalrujukanfk = 1;
            $dataAPD->statusenabled = true;
            $dataAPD->objectkelasfk = $parameter['objectkelasfk'];
            $dataAPD->noantrian = $getLastAntrian + 1;
            $dataAPD->noregistrasifk = $parameter['pd_norec'];
            $dataAPD->objectpegawaifk = $parameter['objectpegawaiorderfk'];
            $dataAPD->objectruanganfk = $parameter['idruangtujuan'];
            $dataAPD->statusantrian = 0;
            $dataAPD->statuspasien = 1;
            $dataAPD->status = "Belum Dipanggil";
            $dataAPD->objectstrukorderfk = $parameter['so_norec'];
            $dataAPD->tglregistrasi = $parameter['tglregistrasi']; // date('Y-m-d H:i:s');
            $dataAPD->tglmasuk = date('Y-m-d H:i:s');
            $dataAPD->tglkeluar = date('Y-m-d H:i:s');
            $dataAPD->noregistrasi = $parameter['noregistrasi'];
            $dataAPD->save();
            $dataAPDnorec = $dataAPD->norec;
            $dataAPDtglPel = $dataAPD->tglregistrasi;
        } else {
            $dataAPDnorec = $apd->norec;
            $dataAPDtglPel = $apd->tglregistrasi;
        }

        try {

            StrukOrder::where('norec', $parameter['so_norec'])
                ->where('kdprofile', $this->kdProfile)
                ->update(
                    [
                        'statusorder' => $parameter['statusoperasi'] == 3 ? 2 : 1,
                        'norec_apd' => $dataAPDnorec,
                        'durasi' => $parameter['estimasiwaktuoperasi'],
                        'objectkamaroperasifk' => $parameter['kamaroperasifk'],
                        // 'jenisoperasifk' => $parameter['jenisoperasifk'],
                        'dokteroperatorfk' => $parameter['dokteroperatorfk'],
                        'dokteranastesifk' => $parameter['dokteranastesifk'],
                        'tglselesai' => $parameter['tglselesai'],
                        'objectpegawaiorderfk' => $parameter['objectpegawaiorderfk'],
                        'dokteranakfk' => $parameter['dokteranakfk'],
                        'penerimafk' => $parameter['penerimafk'],
                        'statusoperasi' => $parameter['statusoperasi'],
                    ]
                );

            // if($parameter['statusoperasi'] == 3){
            //     StrukOrder::where('norec', $parameter['so_norec'])
            //     ->where('kdprofile', $this->kdProfile)
            //     ->update(
            //         [
            //             'statusorder' => 2
            //         ]
            //         );
            // }

            if (count($dataOrder) > 0) {
                foreach ($dataOrder as $data) {
                    // return $this->respond($data['idProduk']);
                    $dataPelayanan = new PelayananPasien();
                    $dataPelayanan->norec = $dataPelayanan->generateNewId();
                    $dataPelayanan->kdprofile = $this->kdProfile;
                    $dataPelayanan->statusenabled = true;
                    $dataPelayanan->noregistrasifk = $dataAPDnorec;
                    $dataPelayanan->aturanpakai = '-';
                    $dataPelayanan->hargadiscount = 0;
                    $dataPelayanan->hargajual = $data['hargaLayanan'];
                    $dataPelayanan->hargasatuan = $data['hargaLayanan'];
                    $dataPelayanan->jumlah = $data['jumlah'];
                    $dataPelayanan->kdkelompoktransaksi = 1;
                    $dataPelayanan->piutangpenjamin = 0;
                    $dataPelayanan->piutangrumahsakit = 0;
                    $dataPelayanan->produkfk = $data['idProduk'];
                    $dataPelayanan->stock = 1;
                    $dataPelayanan->strukorderfk = $parameter['so_norec'];
                    $dataPelayanan->tglpelayanan = date('Y-m-d H:i:s');
                    $dataPelayanan->harganetto = $data['hargaLayanan'];
                    $dataPelayanan->noregistrasi = $parameter['noregistrasi'];
                    $dataPelayanan->save();


                    foreach ($data['pelayananpetugas'] as $items) {
                        foreach ($items['listpegawai'] as $itemsPPP) {
                            $new_PPP = new PelayananPasienPetugas();
                            $new_PPP->norec = $new_PPP->generateNewId();
                            $new_PPP->kdprofile = $this->kdProfile;
                            $new_PPP->statusenabled = true;
                            $new_PPP->nomasukfk = $dataAPDnorec;
                            $new_PPP->objectjenispetugaspefk = $items['objectjenispetugaspefk'];
                            $new_PPP->objectpegawaifk = $itemsPPP['id'];
                            $new_PPP->pelayananpasien = $dataPelayanan->norec;
                            $new_PPP->noregistrasi = $parameter['noregistrasi'];
                            $new_PPP->save();
                        }
                    }

                    // $PelPasienPetugas = new PelayananPasienPetugas();
                    // $PelPasienPetugas->norec = $PelPasienPetugas->generateNewId();
                    // $PelPasienPetugas->kdprofile = $this->kdProfile;
                    // $PelPasienPetugas->statusenabled = true;
                    // $PelPasienPetugas->nomasukfk = $dataAPDnorec;
                    // // $PelPasienPetugas->object $parameter['dokterverify']; //$request['objectpegawaiorderfk'];
                    // $PelPasienPetugas->objectpegawaifk = $parameter['dokteroperasi'];
                    // $PelPasienPetugas->tglpelayanan =  date('Y-m-d H:i:s');
                    // $PelPasienPetugas->objectjenispetugaspefk = $this->settingFix('idDokterPemeriksa'); //$jenisPetugasPe->objectjenispetugaspefk;
                    // $PelPasienPetugas->pelayananpasien = $dataPelayanan->norec;
                    // $PelPasienPetugas->noregistrasi =  $parameter['noregistrasi'];
                    // $PelPasienPetugas->save();

                    $PPnorec = $dataPelayanan->norec;
                    foreach ($data['komponenharga'] as $itemKomponen) {
                        $PelPasienDetail = new PelayananPasienDetail();
                        $PelPasienDetail->norec = $PelPasienDetail->generateNewId();
                        $PelPasienDetail->kdprofile = $this->kdProfile;
                        $PelPasienDetail->statusenabled = true;
                        $PelPasienDetail->noregistrasifk = $dataAPDnorec;
                        $PelPasienDetail->aturanpakai = '-';
                        $PelPasienDetail->hargadiscount = 0;
                        $PelPasienDetail->hargajual = $itemKomponen['hargasatuan'];
                        $PelPasienDetail->hargasatuan = $itemKomponen['hargasatuan'];
                        // if (isset($item['hargadijamin'])) {
                        //     $PelPasienDetail->hargadijamin =  $item['hargadijamin'];
                        // }
                        $PelPasienDetail->jumlah = 1;
                        $PelPasienDetail->keteranganlain = '-';
                        $PelPasienDetail->keteranganpakai2 = '-';
                        $PelPasienDetail->komponenhargafk = $itemKomponen['objectkomponenhargafk'];
                        $PelPasienDetail->pelayananpasien = $PPnorec;
                        $PelPasienDetail->piutangpenjamin = 0;
                        $PelPasienDetail->piutangrumahsakit = 0;
                        $PelPasienDetail->produkfk = $itemKomponen['objectprodukfk'];
                        $PelPasienDetail->stock = 1;
                        $PelPasienDetail->strukorderfk = $parameter['so_norec'];
                        $PelPasienDetail->tglpelayanan = $dataAPDtglPel;
                        $PelPasienDetail->harganetto = $itemKomponen['hargasatuan'];
                        $PelPasienDetail->noregistrasi = $parameter['noregistrasi'];
                        $PelPasienDetail->save();

                        $PPDnorec = $PelPasienDetail->norec;
                        $transStatus = 'true';
                    }
                }
            }


            DB::commit();
            $result = [
                'message' => 'Data Berhasil disimpan',
                'parameter' => $parameter,
                //'pelPasien' => $dataPelayanan,
                // 'petugaspelayanan' => $PelPasienPetugas,
                //'detailPelayanan' => $PelPasienDetail,
                'kode' => 200
            ];
        } catch (InvalidOrderException $e) {
            DB::rollBack();

            $result = [
                'message' => 'Data Gagal Disimpan, Silakan Periksa Kembali Data',
                'status' => $e->getMessage(),
                'kode' => 400
            ];
        }
        return $this->respond($result, $result['kode'], $result['message']);
    }



    public function getBedahVerif(Request $request)
    {

        $datas = DB::table('pelayananpasien_t as pp')
            ->join('produk_m as prd', 'prd.id', 'pp.produkfk')
            ->leftjoin('strukorder_t as so', 'so.norec', '=', 'pp.strukorderfk')
            ->leftjoin('pegawai_m as pe', 'pe.id', 'so.objectpegawaiorderfk')
            ->select(
                'pp.tglpelayanan',
                'pe.namalengkap',
                'pp.hargasatuan',
                'so.tglpelayananawal as tgloperasi',
                DB::raw("(pp.jumlah*pp.hargasatuan) as total"),
                'prd.namaproduk',
                'pp.jumlah'
            )
            ->where('pp.strukorderfk', $request['norec_so'])
            ->where('pp.kdprofile', $this->kdProfile)
            ->where('pp.statusenabled', true)
            ->get();

        return $this->respond($datas);
    }

    public function getpetugasVerif(Request $request)
    {
        $datas = DB::table('pelayananpasien_t as pp')
            ->join('produk_m as prd', 'prd.id', 'pp.produkfk')
            ->leftjoin('pelayananpasienpetugas_t as ppp', 'ppp.pelayananpasien', 'pp.norec')
            ->leftjoin('jenispetugaspelaksana_m as jp', 'jp.id', '=', 'ppp.objectjenispetugaspefk')
            ->leftjoin('pegawai_m as pg', 'pg.id', '=', 'ppp.objectpegawaifk')
            ->leftjoin('strukorder_t as so', 'so.norec', '=', 'pp.strukorderfk')
            ->leftjoin('pegawai_m as pe', 'pe.id', 'so.objectpegawaiorderfk')
            ->select(
                'pp.tglpelayanan',
                'jp.jenispetugaspe',
                'ppp.objectpegawaifk',
                'ppp.objectjenispetugaspefk',
                DB::raw('MAX(pg.namalengkap) as namalengkappetugas'),
            )
            ->where('pp.strukorderfk', $request['norec_so'])
            ->where('pp.kdprofile', $this->kdProfile)
            ->where('pp.statusenabled', true)
            ->whereIn('ppp.objectjenispetugaspefk', [6, 17])
            ->groupBy(
                'pp.tglpelayanan',
                'jp.jenispetugaspe',
                'ppp.objectpegawaifk',
                'ppp.objectjenispetugaspefk',
            )
            ->get();

        $result = [];
        $processedIds = [];
        foreach ($datas as $data) {
            $key = $data->objectjenispetugaspefk;

            if (!in_array($key, $processedIds)) {
                $processedIds[] = $key;
                $result[] = $data;
            }
        }

        return $this->respond($result);
    }


    public function getBedahDetail(Request $r)
    {
        // $now = $this->hari(date('Y-m-d'));
        $kdProfile = $this->kdProfile;
        $dokter = DB::table('jadwaldokter_m as jd')
            ->leftjoin('pegawai_m as pg', 'pg.id', '=', 'jd.objectpegawaifk')
            ->leftjoin('ruangan_m as ru', 'ru.id', '=', 'jd.objectruanganfk')
            ->select(
                'pg.namalengkap',
                'jd.jammulai',
                'jd.jamakhir',
                'ru.namaruangan',
                DB::raw("lower(jd.hari) as hari"),
            )
            ->where('jd.kdprofile', $this->kdProfile)
            ->whereIn('ru.objectdepartemenfk', explode(',', $this->settingFix('idDepartemenBedah')))
            // ->where('jd.hari', 'ilike', '%'.$now .'%')
            ->where('jd.statusenabled', true);

        if (isset($r['ruanganid']) && $r['ruanganid'] != '') {
            $dokter = $dokter->where('ru.id', '=', $r['ruanganid']);
        }
        if (isset($r['namadokter']) && $r['namadokter'] != '') {
            $dokter = $dokter->where('pg.namalengkap', 'ilike', '%' . $r['namadokter'] . '%');
        }
        if (isset($r['limit']) && $r['limit'] != '') {
            $dokter = $dokter->limit($r['limit']);
        }

        $dokter = $dokter->get();

        $produk = DB::table('stokprodukdetail_t as spd')
            ->join('ruangan_m as ru', 'spd.objectruanganfk', '=', 'ru.id')
            ->leftjoin('produk_m as pr', 'spd.objectprodukfk', '=', 'pr.id')
            ->leftjoin('asalproduk_m as ap', 'spd.objectasalprodukfk', '=', 'ap.id')
            ->select(
                DB::raw("sum(spd.qtyproduk) as qtyproduk,
            ru.id,
            ru.namaruangan,
            pr.namaproduk,
            ap.asalproduk,
            spd.harganetto1,
            spd.harganetto2")
            )
            ->where('spd.kdprofile', $this->kdProfile)
            ->whereIn('ru.objectdepartemenfk', explode(',', $this->settingFix('idDepartemenBedah')))
            ->where('spd.statusenabled', true);

        if (isset($r['ruanganid']) && $r['ruanganid'] != '') {
            $produk->where('ru.id', '=', $r['ruanganid']);
        }
        if (isset($r['nama']) && $r['nama'] != '') {
            $produk->where('pr.namaproduk', 'ilike', '%' . $r['nama'] . '%');
        }
        if (isset($r['limit']) && $r['limit'] != '') {
            $produk->limit($r['limit']);
        }
        $produk->groupBy('ru.id', 'ru.namaruangan', 'pr.namaproduk', 'ap.asalproduk', 'spd.harganetto1', 'spd.harganetto2');
        $produk->orderBy('pr.namaproduk');
        $produk = $produk->get();

        $res['dokter'] = $dokter;
        $res['produk'] = $produk;
        return $this->respond($res);
    }

    // Laporan Tindakan Operasi

    public function LapTindakanOperasi(Request $request)
    {
        $dataOrder = DB::table('strukorder_t as so')
            ->leftJoin('pelayananpasien_t AS pp', 'pp.strukorderfk', '=', 'so.norec')
            ->leftJoin('produk_m AS pr', 'pr.id', '=', 'pp.produkfk')
            ->leftJoin('pasiendaftar_t AS pd', 'pd.norec', '=', 'so.noregistrasifk')
            ->leftJoin('pasien_m AS pas', 'pd.nocmfk', '=', 'pas.id')
            ->leftJoin('ruangan_m AS ruAs', 'so.objectruanganfk', '=', 'ruAs.id')
            ->leftJoin('ruangan_m AS ruTu', 'so.objectruangantujuanfk', '=', 'ruTu.id')
            ->leftJoin('pegawai_m AS peg', 'peg.id', '=', 'so.objectpegawaiorderfk')
            ->leftJoin('jeniskelamin_m AS jk', 'jk.id', '=', 'pas.objectjeniskelaminfk')
            ->leftJoin('kelompokpasien_m AS kp', 'kp.id', '=', 'pd.objectkelompokpasienlastfk')
            ->leftJoin('departemen_m AS dep', 'dep.id', '=', 'ruAs.objectdepartemenfk')
            ->leftJoin('departemen_m AS dep2', 'dep2.id', '=', 'ruTu.objectdepartemenfk')
            ->leftJoin('kelas_m AS kls', 'kls.id', '=', 'pd.objectkelasfk')
            ->leftJoin('pelayananpasienpetugas_t AS ppp', 'ppp.pelayananpasien', '=', 'pp.norec')
            ->leftJoin('pegawai_m AS peg2', 'peg2.id', '=', 'ppp.objectpegawaifk')
            ->leftJoin('pegawai_m AS peg3', 'peg3.id', '=', 'so.dokteranastesifk')
            ->leftJoin('pegawai_m AS peg4', 'peg4.id', '=', 'so.dokteroperatorfk')
            ->leftJoin('pegawai_m AS peg5', 'peg4.id', '=', 'so.penerimafk')
            ->leftJoin('kamaroperasi_m as kom', 'kom.id', '=', 'so.objectkamaroperasifk')
            ->select(

                'so.norec',
                'peg5.namalengkap as userpenerima',
                'pd.noregistrasi',
                'pd.norec AS pd_norec',
                'so.noorder',
                'so.statusorder',
                'pd.jenispelayanan AS jenispelayananfk',
                'pd.tglregistrasi',
                DB::raw('DATE(so.tglpelayananawal) AS tgloperasi'),
                'pas.namapasien',
                'pas.tgllahir',
                'pas.nocm',
                'so.durasi as estimasi',
                'pas.objectjeniskelaminfk',
                DB::raw('CAST(so.tglorder AS DATE) AS tglorder'),
                DB::raw('CAST(so.tglorder AS TIME) AS jamoperasi'),
                'pas.noidentitas',
                'jk.jeniskelamin',
                'kp.kelompokpasien',
                'kls.namakelas',
                'kom.namakamarok as kamaroperasi',
                'so.penerimafk as userpenrima',
                'dep.namadepartemen AS asldepartemen',
                'ruAs.namaruangan AS asalruangan',
                'ruTu.namaruangan AS ruangantujuan',
                'dep2.namadepartemen AS departementujuan',
                'peg.namalengkap',
                'pas.noidentitas',
                'pp.norec',
                'pr.namaproduk',
                'peg2.namalengkap AS dokterpemeriksa',
                DB::raw('(pp.jumlah * pp.hargasatuan) AS total'),
                'pp.jumlah',
                'pp.hargasatuan',
                DB::raw('EXTRACT(YEAR FROM AGE(pas.tgllahir)) AS umur_pasien'),
                'peg3.namalengkap AS dokteranestesi',
                'so.diagnosis',
                'so.tb AS tinggibadan',
                'so.bb AS beratbadan',
                'so.riwayatswab'
            )
            ->whereIn('dep2.id', [37, 45, 46])
            ->where('so.statusenabled', true);

        // Filter berdasarkan request
        if (!empty($request->statusorder)) {
            $dataOrder->where('so.statusorder', $request->statusorder);
        }
        if (!empty($request->qnamapasien)) {
            $dataOrder->where('pas.namapasien', 'ilike', '%' . $request->qnamapasien . '%');
        }
        if (!empty($request->qnocm)) {
            $dataOrder->where('pas.nocm', 'ilike', '%' . $request->qnocm . '%');
        }
        if (!empty($request->qnoregistrasi)) {
            $dataOrder->where('pd.noregistrasi', 'ilike', '%' . $request->qnoregistrasi . '%');
        }
        if (!empty($request->ruanganid)) {
            $dataOrder->where('ruTu.id', $request->ruanganid);
        }
        if (!empty($request->noorder)) {
            $dataOrder->where('so.noorder', $request->noorder);
        }
        if (!empty($request->dari)) {
            $dataOrder->where(DB::raw('so.tgloperasi::date'), '>=', $request->dari);
        }
        if (!empty($request->sampai)) {
            $dataOrder->where(DB::raw('so.tgloperasi::date'), '<=', $request->sampai);
        }
        if (isset($request['id_dokter']) && $request['id_dokter'] != '') {
            $dataOrder = $dataOrder->where(function ($query) use ($request) {
                $query->where('so.dokteroperatorfk', $request['id_dokter'])
                    ->orWhereJsonContains('so.operatorhelperfk', (int) $request['id_dokter']);
            });
        }

        $dataOrder = $dataOrder->get();

        return $this->respond($dataOrder);
    }
}
