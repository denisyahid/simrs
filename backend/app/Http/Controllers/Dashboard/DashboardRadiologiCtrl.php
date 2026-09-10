<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Master\GolonganDarah;
use App\Models\Master\JenisKelamin;
use App\Models\Master\KelompokUser;
use App\Models\Master\Pasien;
use App\Models\Master\Pegawai;
use App\Models\Master\Ruangan;
use App\Models\Standar\LoginUser;
use App\Models\Transaksi\AntrianPasienDiperiksa;
use App\Models\Transaksi\PasienDaftar;
use App\Models\Transaksi\PelayananPasien;
use App\Models\Transaksi\PelayananPasienDetail;
use App\Models\Transaksi\PelayananPasienPetugas;
use App\Models\Transaksi\StrukOrder;
use App\Models\Transaksi\OrderPelayanan;
use App\Models\Transaksi\AksesEMR;
use App\Models\Transaksi\PerubahanJadwal;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Mockery\Exception\InvalidOrderException;
use App\Traits\Valet;
use Ramsey\Uuid\Uuid;

class DashboardRadiologiCtrl extends Controller
{
    use Valet;
    public function __construct()
    {
        parent::__construct($is_encrypt = true);
    }

    public function getStrukOrderRad(Request $request)
    {
        $dateBetween = [$request->tglAwal . ' 00:00:00', $request->tglAkhir . ' 23:59:59'];
        $idDepartemenRadiologi = explode(',', $this->settingFix('idDepartemenRadiologi'));
        $allPeriode = ['2024-12-13 00:00:00', date('Y-m-d').' 23:59:59'];

        $dataOrder = DB::table('strukorder_t as so')
            ->join('pasiendaftar_t as pd', 'pd.norec', 'so.noregistrasifk')
            // ->join('antrianpasiendiperiksa_t as apd', 'apd.noregistrasifk', 'pd.norec')
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
            ->Join('kebangsaan_m as kbs', 'kbs.id', '=', 'pas.objectkebangsaanfk')
            // ->leftJoin('diagnosapasien_t as dp', 'dp.noregistrasifk', 'apd.norec')
            ->select(
                'so.norec',
                'ruTu.id',
                'pd.noregistrasi',
                'pd.nocmfk',
                'pd.norec as pd_norec',
                // 'apd.noregistrasifk',
                // 'apd.norec as apd_norec',
                // 'dp.ketdiagnosis',
                // 'dp.norec as dp_norec',
                'so.noorder',
                'so.statusorder',
                'so.keteranganlainnya',
                'pd.jenispelayanan as jenispelayananfk',
                'pd.tglregistrasi',
                'pas.namapasien',
                'pas.tgllahir',
                'pas.nocm',
                'so.terapiradioaktif',
                'pas.objectjeniskelaminfk',
                'pas.objectkebangsaanfk',
                DB::raw("CAST(so.tglorder AS DATE)"),
                'pas.noidentitas',
                'pas.nobpjs',
                'jk.jeniskelamin',
                'kp.kelompokpasien',
                'kls.namakelas',
                'pd.objectkelasfk',
                'pd.objectrekananfk',
                'dep.namadepartemen as asldepartemen',
                'ruAs.namaruangan as asalruangan',
                'ruTu.namaruangan as ruangantujuan',
                'so.objectruangantujuanfk',
                'so.cito',
                'so.objectpegawaiorderfk',
                'dep2.namadepartemen as departementujuan',
                'peg.namalengkap',
                'pa.nosep',
                'so.catatanklinis',
                'so.keteranganlainnya',
                'so.tglorder',
                'kbs.name as kebangsaan',
                'ruAs.objectdepartemenfk as flagranap'
            )
            ->where('so.kdprofile', $this->kdProfile)
            // ->where('so.objectruangantujuanfk', $this->settingFix('idDepartemenLab'))
            ->whereIn('ruTu.objectdepartemenfk', $idDepartemenRadiologi)
            ->where('so.statusenabled', true);
            // ->where('ruAs.namaruangan', '!=', 'RADIOLOGI')
            

        if (isset($request['allperiode']) && $request['allperiode'] == 'true') {
            $dataOrder = $dataOrder->whereBetween(DB::raw("so.tglorder"),$allPeriode);
        }
        if (isset($request['allperiode']) && $request['allperiode'] == 'false' && isset($request['statusorder']) && $request['statusorder'] != 5 ) {
            $dataOrder = $dataOrder->whereBetween(DB::raw("so.tglorder"), $dateBetween);
        }
        if (isset($request['statusorder']) && $request['statusorder'] != '' && $request['statusorder'] == 5) {
            $dataOrder = $dataOrder->where('so.statusorder', '=', $request['statusorder']);
            $dataOrder = $dataOrder->whereBetween(DB::raw("so.tglpenjadwalanradiologi"), $dateBetween);
        }
        if (isset($request['statusorder']) && $request['statusorder'] != '') {
            $dataOrder = $dataOrder->where('so.statusorder', '=', $request['statusorder']);
        }
        if (isset($request['ruanganid']) && $request['ruanganid'] != '') {
            if($request['ruanganid'] == 330) {
                $dataOrder = $dataOrder->where('so.objectruangantujuanfk', '=', $request['ruanganid']);
            }
            else {
                $dataOrder = $dataOrder->where('so.objectruangantujuanfk', '=', $request['ruanganid']);
                $dataOrder = $dataOrder->whereBetween(DB::raw("so.tglorder"), $dateBetween);
            }
        }
        if (isset($request['search']) && $request['search'] != '') {
            $searchTerm = '%' . trim($request['search']) . '%';
            $dataOrder = $dataOrder->where(function ($query) use ($searchTerm) {
                $query->where('pas.namapasien', 'ilike', $searchTerm)
                    ->orWhere('pd.noregistrasi', 'ilike', $searchTerm)
                    ->orWhere('pas.nocm', 'ilike', $searchTerm)
                    ->orWhere('pas.nobpjs', 'ilike', $searchTerm)
                    ->orWhere('pas.noidentitas', 'ilike', $searchTerm);
            });
        }
        if (isset($request['noorder']) && $request['noorder'] != '') {
            $dataOrder = $dataOrder->where('so.noorder', '=', $request['noorder']);
        }
        if (isset($request['qnamapasien']) && $request['qnamapasien'] != '') {
            $dataOrder = $dataOrder->where('pas.namapasien', '=', $request['qnamapasien']);
        }
        if (isset($request['cariranap']) && $request['cariranap'] == 'true') {
            $dataOrder = $dataOrder->where('ruAs.objectdepartemenfk', $this->settingFix('idDepRawatInap'));
        }
        // if (isset($request['cariranap']) && $request['cariranap'] == 'false') {
        //     $dataArray = explode(',', $this->settingFix('kdDepartemenRawatJalanFix'));
        //     $dataOrder = $dataOrder->whereIn('ruAs.objectdepartemenfk',$dataArray);
        //     // $dataOrder = $dataOrder->whereIn('ruAs.objectdepartemenfk',[18,9,27,3,4]);   //statis value
        // }
        if (isset($request['qnoregistrasi']) && $request['qnoregistrasi'] != '') {
            $dataOrder = $dataOrder->where('pd.noregistrasi', '=', $request['qnoregistrasi']);
        }
        if (isset($request['qnocm']) && $request['qnocm'] != '') {
            $dataOrder = $dataOrder->where('pas.nocm', '=', $request['qnocm']);
        }
        $total = $dataOrder->count();
        if (isset($request['limit']) && $request['limit'] != '') {
            $dataOrder = $dataOrder->limit($request['limit']);
        }
        if (isset($request['offset']) && $request['offset'] != '') {
            $dataOrder = $dataOrder->offset($request['offset']);
        }

        $dataOrder = $dataOrder->orderBy('so.tglorder', 'desc');
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
                'noregistrasi' => $datas->noregistrasi,
                'so_norec' => $datas->norec,
                'nocmfk' => $datas->nocmfk,
                'pd_norec' => $datas->pd_norec,
                'jenispelayananfk' => $datas->jenispelayananfk,
                'noorder' => $datas->noorder,
                'iscito' => $datas->cito,
                'tglregistrasi' => $datas->tglregistrasi,
                'tglorder' => $datas->tglorder,
                'pas_nocm' => $datas->nocm,
                'nobpjs' => $datas->nobpjs,
                'noidentitas' => $datas->noidentitas,
                'statusorder' => $datas->statusorder,
                'jeniskelamin' => $datas->jeniskelamin,
                'id_jenisK' => $datas->objectjeniskelaminfk,
                'kelompokpasien' => $datas->kelompokpasien,
                'objectkebangsaanfk' => $datas->objectkebangsaanfk,
                'namakelas' => $datas->namakelas,
                'objectrekananfk' => $datas->objectrekananfk,
                'objectkelasfk' => $datas->objectkelasfk,
                'asal_departemen' => $datas->asldepartemen,
                'asal_ruangan' => $datas->asalruangan,
                'terapiradioaktif' => $datas->terapiradioaktif,
                'keterangan' => $datas->keteranganlainnya,
                'catatanklinis' => $datas->catatanklinis,
                'keterangan' => $datas->keteranganlainnya,
                'ruangantujuan' => $datas->ruangantujuan,
                'objectruangantujuanfk' => $datas->objectruangantujuanfk,
                'departementujuan' => $datas->departementujuan,
                'nama_pegawai' => $datas->namalengkap,
                'objectpegawaiorderfk' => $datas->objectpegawaiorderfk,
                'umur' => $this->getAge($datas->tgllahir, $datas->tglorder),
                'nosep' => $datas->nosep,
                'kebangsaan' => $datas->kebangsaan,
                'tgllahir' => $datas->tgllahir,
                'flagranap' => $datas->flagranap,
                // 'detailDiagnosa' => $detail
            ];
        }
        //dd($result);


        $results = [
            'data' => $result,
            'total' => $total
        ];
        return $this->respond($results);
    }

    public function getOrderPelayananRad(Request $request)
    {
        $so = StrukOrder::where('norec', $request['strukorderfk'])->where('kdprofile', $this->kdProfile)->first();
        $pasienDaftar = PasienDaftar::where('norec', $so->noregistrasifk)->where('kdprofile', $this->kdProfile)->first();
        $pasien = Pasien::where('id', '=', $pasienDaftar->nocmfk)->where('kdprofile', $this->kdProfile)->first();
        $jp = (int) $pasienDaftar->jenispelayanan;
        $idpenjamin = $pasienDaftar->objectrekananfk == null ? '-1' : $pasienDaftar->objectrekananfk;
        if ($idpenjamin != "-1") {
            $dataOrderPelayanan = DB::table('strukorder_t as so')
                ->join('orderpelayanan_t as op', 'op.strukorderfk', 'so.norec')
                ->join('produk_m as pr', 'pr.id', 'op.objectprodukfk')
                ->join('harganettoprodukbykelas_m as hnp', function ($join) {
                    $join->on('pr.id', '=', 'hnp.objectprodukfk')->on('pr.kdprofile', '=', 'hnp.kdprofile')
                        ->where('hnp.statusenabled', true);
                })
                ->join('kelas_m as kls', 'kls.id', 'hnp.objectkelasfk')
                ->join('ruangan_m as ru', 'ru.id', 'so.objectruangantujuanfk')
                ->join('departemen_m as dpm', 'dpm.id', 'ru.objectdepartemenfk')
                ->leftjoin('pelayananpasien_t as pps', function ($join) {
                    $join->on('pps.strukorderfk', '=', 'so.norec')->on('op.objectprodukfk', '=', 'pps.produkfk');
                })
                ->select(DB::raw(
                    "DISTINCT op.norec as norec_op,pr.id as prid,pr.namaproduk,hnp.objectkelasfk,
                                                    op.tglpelayanan,op.qtyproduk ,ru.namaruangan as ruangantujuan,ru.objectdepartemenfk,op.strukorderfk,
                                                    so.objectruangantujuanfk,hnp.hargasatuan ,kls.namakelas,dpm.namadepartemen,pps.norec as norec_pp,
                                                    CASE WHEN hnp.hargadijamin IS NULL THEN 0 ELSE hnp.hargadijamin END AS hargadijamin"
                ))
                ->where('op.kdprofile', $this->kdProfile)
                ->where('op.strukorderfk', $request['strukorderfk'])
                ->where('hnp.objectkelasfk', $request['objectkelasfk'])
                ->where('hnp.objectjenispelayananfk', $jp)
                ->where('hnp.objectpenjaminfk', $idpenjamin)
                ->where('hnp.objectkebangsaanfk', $pasien->objectkebangsaanfk)
                ->get();
        } else {
            $dataOrderPelayanan = [];
        }
        if (count($dataOrderPelayanan) == 0) {
            // return $this->respond('cek');
            $dataOrderPelayanan = DB::table('strukorder_t as so')
                ->join('orderpelayanan_t as op', 'op.strukorderfk', 'so.norec')
                ->join('produk_m as pr', 'pr.id', 'op.objectprodukfk')
                ->join('harganettoprodukbykelas_m as hnp', 'pr.id', 'hnp.objectprodukfk')
                ->join('kelas_m as kls', 'kls.id', 'hnp.objectkelasfk')
                ->join('ruangan_m as ru', 'ru.id', 'so.objectruangantujuanfk')
                ->join('departemen_m as dpm', 'dpm.id', 'ru.objectdepartemenfk')
                ->leftjoin('pelayananpasien_t as pps', function ($join) {
                    $join->on('pps.strukorderfk', '=', 'so.norec')
                        ->on('pps.produkfk', '=', 'pr.id')->on('pps.kdprofile', 'so.kdprofile');
                })
                ->select(DB::raw("op.norec as norec_op,pr.id as prid,pr.namaproduk,
                                    op.tglpelayanan,op.qtyproduk ,ru.namaruangan as ruangantujuan,ru.objectdepartemenfk,
                                    op.strukorderfk,so.objectruangantujuanfk,hnp.objectkelasfk,
                                    hnp.hargasatuan ,kls.namakelas,dpm.namadepartemen,pps.norec as norec_pp,
                                    CASE WHEN hnp.hargadijamin IS NULL THEN 0 ELSE hnp.hargadijamin END AS hargadijamin"))
                ->where('op.kdprofile', $this->kdProfile)
                ->where('hnp.objectkelasfk', $request['objectkelasfk'])
                ->where('hnp.statusenabled', true)
                ->whereNull('hnp.objectpenjaminfk')
                ->where('op.strukorderfk', $request['strukorderfk'])
                ->where('hnp.objectjenispelayananfk', $jp)
                ->where('hnp.objectkebangsaanfk', $pasien->objectkebangsaanfk)
                ->get();

            // return $dataOrderPelayanan;
        }
        //  else {
        //     // return $this->respond('nothing');
        // }

        // var_dump($jp);
        // var_dump($pasien->objectkebangsaanfk);

        $result = [];
        foreach ($dataOrderPelayanan as $item) {
            $datas = DB::table('produk_m as prd')
                ->join('harganettoprodukbykelasd_m as hnp', 'hnp.objectprodukfk', 'prd.id')
                ->join('komponenharga_m as kh', 'kh.id', 'hnp.objectkomponenhargafk')
                ->join('kelas_m as kls', 'kls.id', 'hnp.objectkelasfk')
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
                $datas = DB::table('produk_m as prd')
                    ->join('harganettoprodukbykelasd_m as hnp', 'hnp.objectprodukfk', 'prd.id')
                    ->join('komponenharga_m as kh', 'kh.id', 'hnp.objectkomponenhargafk')
                    ->join('kelas_m as kls', 'kls.id', 'hnp.objectkelasfk')
                    ->select(DB::raw(
                        "distinct hnp.objectkomponenhargafk,kh.komponenharga,hnp.hargasatuan,
                                              hnp.objectprodukfk,hnp.objectjenispelayananfk,
                                              CASE WHEN hnp.hargadijamin IS NULL THEN 0 ELSE hnp.hargadijamin END AS hargadijamin"
                    ))
                    ->where('hnp.kdprofile', $this->kdProfile)
                    ->where('hnp.objectkelasfk', $item->objectkelasfk)
                    ->whereNull('hnp.objectpenjaminfk')
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
                'namaproduk' => $item->namaproduk,
                'qtyproduk' => $item->qtyproduk,
                'tglpelayanan' => $item->tglpelayanan,
                'idruangan' => $item->objectruangantujuanfk,
                'ruangantujuan' => $item->ruangantujuan,
                'hargasatuan' => $item->hargasatuan,
                'hargadijamin' => $item->hargadijamin,
                'namakelas' => $item->namakelas,
                'objectdepartemenfk' => $item->objectdepartemenfk,
                'namadepartemen' => $item->namadepartemen,
                'cito' => $nilaiCito,
                'nilaiStatusCito' => $nilaiStatusCito,
                'objectrekananfk' => $pasienDaftar->objectrekananfk,
                'komponenharga' => $datas,
            );
        }

        return $this->respond($result);
    }

    public function unverifPelayananPasien(Request $request) {
        $kdProfile = $this->kdProfile;
        $idProfile = (int) $kdProfile;
        // dd($request['noregistrasi']);
        DB::beginTransaction();
        try{
            $data = DB::table('pelayananpasien_t as pp')
            ->JOIN('strukorder_t as so','so.norec','=','pp.strukorderfk')
            ->select('pp.norec as norec_pp','so.keteranganorder','so.norec as norec_order')
            ->where('pp.strukorderfk',$request['norec_so'])
            ->whereNull('pp.strukfk')
            ->get();

            foreach ($data as $item) {
                $HapusPP = PelayananPasien::where('norec', $item->norec_pp)->get();
                foreach ($HapusPP as $pp) {
                    $HapusPPD = PelayananPasienDetail::where('pelayananpasien', $pp['norec'])->where('kdprofile', $idProfile)->delete();
                    $HapusPPP = PelayananPasienPetugas::where('pelayananpasien', $pp['norec'])->where('kdprofile', $idProfile)->delete();
                }
                $Edit = PelayananPasien::where('norec', $item->norec_pp)->where('kdprofile', $idProfile)->delete();
                
                $sOrder = StrukOrder::where('norec',$item->norec_order)
                    ->where('kdprofile', $idProfile)
                    ->update([
                        'statusorder'=> 0
                    ]);
            }

            $transStatus = 'false';
            
            if (!empty($data)) {
                 $transStatus = 'true';
            }
        } catch (\Exception $e) {
            $transStatus = 'false';
            $transMessage = "gagal";
        }
        if ($transStatus == 'true') {
            $transMessage = "Unverif Order Berhasil";
            DB::commit();
            $result = array(
                "status" => 200,
                "message" => $transMessage,
                "as" => 'maman',
            );
        } else {
            $transMessage = "Gagal / sudah di verifikasi kasir!";
            DB::rollBack();
            $result = array(
                "status" => 400,
                "message"  => $transMessage,
                "as" => 'maman',
            );
        }
        return $this->respond($result, $result['status'], $result['message']);
    }

    public function getKomponenHarga(Request $request)
    {
        $data = DB::table('harganettoprodukbykelasd_m as hnp')
            ->join('produk_m as prd', 'prd.id', '=', 'hnp.objectprodukfk')
            ->join('komponenharga_m as kh', 'kh.id', '=', 'hnp.objectkomponenhargafk')
            ->join('kelas_m as kls', 'kls.id', '=', 'hnp.objectkelasfk')
            ->select('hnp.objectkomponenhargafk', 'kh.komponenharga', 'hnp.hargasatuan', 'hnp.objectprodukfk', 'kh.iscito')
            ->where('hnp.kdprofile', $this->kdProfile)
            ->where('hnp.objectkelasfk', $request['idKelas'])
            // ->where('hnp.objectkelasfk', $this->settingFix('kdKelasLabRad'))
            ->where('hnp.objectprodukfk', $request['idProduk'])
            ->where('hnp.objectjenispelayananfk', $request['idJenLayan'])
            ->where('hnp.statusenabled', true)
            ->distinct()
            ->get();

        if (count($data) == 0) {
            $data = DB::table('harganettoprodukbykelasd_m as hnp')
                ->join('produk_m as prd', 'prd.id', '=', 'hnp.objectprodukfk')
                ->join('komponenharga_m as kh', 'kh.id', '=', 'hnp.objectkomponenhargafk')
                ->join('kelas_m as kls', 'kls.id', '=', 'hnp.objectkelasfk')
                ->select('hnp.objectkomponenhargafk', 'kh.komponenharga', 'hnp.hargasatuan', 'hnp.objectprodukfk', 'kh.iscito')
                ->where('hnp.kdprofile', $this->kdProfile)
                // ->where('hnp.objectkelasfk', $this->settingFix('kdKelasLabRad'))
                ->where('hnp.objectkelasfk', $request['idKelas'])
                ->where('hnp.objectprodukfk', $request['idProduk'])
                ->whereNull('hnp.objectjenispelayananfk')
                ->where('hnp.statusenabled', true)
                ->distinct()
                ->get();
        }

        return $this->respond($data);
    }

    public function getPelayananNuklirTerapi(Request $request)
    {
        $datas = DB::table('mapruangantoproduk_m as mpr')
            ->join('harganettoprodukbykelas_m as hnp', function ($join) {
                $join->on('hnp.objectprodukfk', '=', 'mpr.objectprodukfk')
                    ->where('hnp.statusenabled', true);
            })
            ->join('produk_m as prd', 'prd.id', '=', 'mpr.objectprodukfk')
            // ->join ('kelas_m as kls','kls.id','hnp.objectkelasfk')
            ->join('ruangan_m as ru', 'ru.id', '=', 'mpr.objectruanganfk')
            ->leftjoin('kebangsaan_m as kbg', 'kbg.id','=','hnp.objectkebangsaanfk')
            ->select(
                'mpr.id',
                'prd.id as idpro',
                'prd.namaproduk',
                'hnp.hargasatuan',
                'ru.namaruangan',
                //  db::raw("prd.namaproduk || ' ~ ' || kbg.name as namaproduk"),
                'mpr.objectprodukfk',
                // 'hnp.objectprodukfk',
                'mpr.objectruanganfk'
            )
            ->where('mpr.kdprofile', $this->kdProfile)
            // ->where('hnp.objectkelasfk', $request['idkelas'])
            // ->where('hnp.objectkelasfk', $this->settingFix('kdKelasLabRad'))
            // ->where('hnp.objectjenispelayananfk', $request['idjenispelayanan'])
            ->where('mpr.objectruanganfk', 389)
            ->where('mpr.statusenabled', true)
            ->where('prd.statusenabled', true);

        if (isset($request->idProduk)) {
            $datas = $datas->where('mpr.objectprodukfk', $request['idProduk']);
        }
        $kebangsaan = isset($request['kebangsaan']) ? $request['kebangsaan'] : 'WNI';
        $datas = $datas->where('kbg.name', $kebangsaan);
        $datas = $datas->distinct()->get();

        return $this->respond($datas);
    }
    
    public function getPelayananNuklirInVivo(Request $request)
    {
        $datas = DB::table('mapruangantoproduk_m as mpr')
            ->join('harganettoprodukbykelas_m as hnp', function ($join) {
                $join->on('hnp.objectprodukfk', '=', 'mpr.objectprodukfk')
                    ->where('hnp.statusenabled', true);
            })
            ->join('produk_m as prd', 'prd.id', '=', 'mpr.objectprodukfk')
            // ->join ('kelas_m as kls','kls.id','hnp.objectkelasfk')
            ->join('ruangan_m as ru', 'ru.id', '=', 'mpr.objectruanganfk')
            ->leftjoin('kebangsaan_m as kbg', 'kbg.id','=','hnp.objectkebangsaanfk')
            ->select(
                'mpr.id',
                'prd.id as idpro',
                'prd.namaproduk',
                'hnp.hargasatuan',
                'ru.namaruangan',
                //  db::raw("prd.namaproduk || ' ~ ' || kbg.name as namaproduk"),
                'mpr.objectprodukfk',
                // 'hnp.objectprodukfk',
                'mpr.objectruanganfk'
            )
            ->where('mpr.kdprofile', $this->kdProfile)
            // ->where('hnp.objectkelasfk', $request['idkelas'])
            // ->where('hnp.objectkelasfk', $this->settingFix('kdKelasLabRad'))
            // ->where('hnp.objectjenispelayananfk', $request['idjenispelayanan'])
            ->where('mpr.objectruanganfk', 331)
            ->where('mpr.statusenabled', true)
            ->where('prd.statusenabled', true);

        if (isset($request->idProduk)) {
            $datas = $datas->where('mpr.objectprodukfk', $request['idProduk']);
        }
        $kebangsaan = isset($request['kebangsaan']) ? $request['kebangsaan'] : 'WNI';
        $datas = $datas->where('kbg.name', $kebangsaan);
        $datas = $datas->distinct()->get();

        return $this->respond($datas);
    }

    public function getPelayanan(Request $request)
    {
        $datas = DB::table('mapruangantoproduk_m as mpr')
            ->join('harganettoprodukbykelas_m as hnp', function ($join) {
                $join->on('hnp.objectprodukfk', '=', 'mpr.objectprodukfk')
                    ->where('hnp.statusenabled', true);
            })
            ->join('produk_m as prd', 'prd.id', '=', 'mpr.objectprodukfk')
            // ->join ('kelas_m as kls','kls.id','hnp.objectkelasfk')
            ->join('ruangan_m as ru', 'ru.id', '=', 'mpr.objectruanganfk')
            ->leftjoin('kebangsaan_m as kbg', 'kbg.id','=','hnp.objectkebangsaanfk')
            ->select(
                'mpr.id',
                'prd.id as idpro',
                'prd.namaproduk',
                'hnp.hargasatuan',
                'ru.namaruangan',
                //  db::raw("prd.namaproduk || ' ~ ' || kbg.name as namaproduk"),
                'mpr.objectprodukfk',
                // 'hnp.objectprodukfk',
                'mpr.objectruanganfk'
            )
            ->where('mpr.kdprofile', $this->kdProfile)
            ->where('hnp.objectkelasfk', $request['idkelas'])
            // ->where('hnp.objectkelasfk', $this->settingFix('kdKelasLabRad'))
            ->where('hnp.objectjenispelayananfk', $request['idjenispelayanan'])
            ->where('mpr.objectruanganfk', $this->settingFix('idRuanganRadiologi'))
            ->where('mpr.statusenabled', true)
            ->where('prd.statusenabled', true);

        if (isset($request->idProduk)) {
            $datas = $datas->where('mpr.objectprodukfk', $request['idProduk']);
        }
        $kebangsaan = isset($request['kebangsaan']) ? $request['kebangsaan'] : 'WNI';
        $datas = $datas->where('kbg.name', $kebangsaan);
        $datas = $datas->distinct()->get();

        return $this->respond($datas);
    }

    public function getOnlyDokter()
    {
        try {
            $dataDokter = DB::table('pegawai_m as ru')
                ->select('ru.id', 'namalengkap')
                ->where('ru.kdprofile', $this->kdProfile)
                ->where('ru.statusenabled', true)
                ->where('ru.objectjenispegawaifk', $this->settingFix('idJenisPegawaiDokter'))
                ->where('ru.namalengkap', 'ilike', '%Sp.Rad%')
                ->orWhere('ru.namalengkap', 'ilike', '%Sp.KN%')
                ->orderBy('ru.namalengkap')
                ->get();
            $res['data'] = $dataDokter;
        } catch (\Exception $e) {
            $res['data'] = null;
            $res['code'] = 500;
            $res['message'] = $e->getMessage();
        }
        return $this->respond($res);
    }

    public function getOnlyDokterKemoterapi()
    {
        try {
            $dataDokter = DB::table('pegawai_m as ru')
                ->select('ru.id', 'namalengkap')
                ->where('ru.kdprofile', $this->kdProfile)
                ->where('ru.statusenabled', true)
                ->where('ru.objectjenispegawaifk', $this->settingFix('idJenisPegawaiDokter'))
                ->where('ru.namalengkap', 'ilike', '%Sp.Onk.Rad%')
                ->orWhere('ru.namalengkap', 'ilike', '%Sp. Onk. Rad%')
                ->orderBy('ru.namalengkap')
                ->get();
            $res['data'] = $dataDokter;
        } catch (\Exception $e) {
            $res['data'] = null;
            $res['code'] = 500;
            $res['message'] = $e->getMessage();
        }
        return $this->respond($res);
    }

    public function getDataDokter(Request $re)
    {
        $set = explode(',', $this->settingFix('idDepartemenRadiologi'));
        $res['ruangan'] = DB::table('maploginusertoruangan_s as mlur')
            ->join('loginuser_s as lu', function ($j) {
                $j->on('lu.id', 'mlur.objectloginuserfk')->on('lu.kdprofile', 'mlur.kdprofile');
            })
            ->join('ruangan_m as ru', function ($j) {
                $j->on('ru.id', 'mlur.objectruanganfk')->on('ru.kdprofile', 'mlur.kdprofile');
            })
            ->select('ru.id', 'ru.namaruangan', 'ru.objectdepartemenfk')
            ->where('lu.kdprofile', $this->kdProfile)
            ->where('mlur.statusenabled', true)
            ->where('lu.id', $this->getUserId())
            ->whereIn('objectdepartemenfk', $set)
            ->groupBy('ru.id', 'ru.namaruangan')
            ->get();
        $dataDokter = DB::table('pegawai_m as ru')
            ->select('ru.id', 'namalengkap','ru.namaexternal', 'ru.sanata_id')
            ->where('ru.kdprofile', $this->kdProfile)
            ->where('ru.statusenabled', true)
            ->where('ru.objectjenispegawaifk', $this->settingFix('idJenisPegawaiDokter'))
            ->where(function ($query) {
                $query->where('ru.namalengkap', 'ilike', '%Sp.Rad%')
                    ->orWhere('ru.namalengkap', 'ilike', '%Sp.KN%')
                    ->orWhere('ru.namaexternal', 'ilike', '%DOKTER ANASTESI%');
            })
            // ->where('ru.namalengkap', '!=', 'dr. Lisa Herawati Diah, Sp.KNTM, Subsp. Onk (K), FANMB')
            // ->where('ru.namalengkap', '!=', 'dr. Priska Gusti Wulandari, Sp.KNTM')
            // ->whereNotIn('ru.id',[428,429])
            // ->whereNotNull('ru.sanata_id')
            // ->orderBy('ru.namalengkap')
            ->orderByraw('CHAR_LENGTH(namaexternal) ASC')
            ->get();
        $res['data'] = $dataDokter;
        $res['jeniskelamin'] = JenisKelamin::mine()->get();
        $res['golongandarah'] = GolonganDarah::mine()->get();
        $res['pegawaiRadiologi'] = DB::table('loginuser_s as ls')
            ->join('pegawai_m as pg', 'pg.id', 'ls.objectpegawaifk')
            ->select('pg.id', 'pg.namalengkap', 'pg.namaexternal', 'pg.sanata_id')
            ->where('ls.statusenabled', true)
            ->where('ls.kdprofile', $this->kdProfile);
            if(isset($re['iskemo']) && $re['iskemo'] =='true'){
                $res['pegawaiRadiologi']= $res['pegawaiRadiologi']->whereIn('ls.objectkelompokuserfk',explode(',',$this->settingFix('idkelompokuserkemoterapi')));
            }
            else{
                $res['pegawaiRadiologi']= $res['pegawaiRadiologi']->where('ls.objectkelompokuserfk', $this->settingFix('kdKelompokUser'));
            };

            $res['pegawaiRadiologi']= $res['pegawaiRadiologi']->get();
        $prioritizedIds = [943, 896, 1217];
        $prioritized = $res['pegawaiRadiologi']->filter(function ($item) use ($prioritizedIds) {
            return in_array($item->id, $prioritizedIds);
        });

        $remaining = $res['pegawaiRadiologi']->reject(function ($item) use ($prioritizedIds) {
            return in_array($item->id, $prioritizedIds);
        });
        $res['pegawaiRadiologi'] = $prioritized->merge($remaining)->values();
        return $this->respond($res);
    }

    public function getDataDokterNuklir()
    {
        $set = explode(',', $this->settingFix('idDepartemenRadiologi'));
        $res['ruangan'] = DB::table('maploginusertoruangan_s as mlur')
            ->join('loginuser_s as lu', function ($j) {
                $j->on('lu.id', 'mlur.objectloginuserfk')->on('lu.kdprofile', 'mlur.kdprofile');
            })
            ->join('ruangan_m as ru', function ($j) {
                $j->on('ru.id', 'mlur.objectruanganfk')->on('ru.kdprofile', 'mlur.kdprofile');
            })
            ->select('ru.id', 'ru.namaruangan')
            ->where('lu.kdprofile', $this->kdProfile)
            ->where('mlur.statusenabled', true)
            ->where('lu.id', $this->getUserId())
            ->where('ru.id', '!=', 330)
            ->whereIn('objectdepartemenfk', $set)
            ->groupBy('ru.id', 'ru.namaruangan')
            ->get();
        $dataDokter = DB::table('pegawai_m as ru')
            ->select('ru.id', 'namalengkap')
            ->where('ru.kdprofile', $this->kdProfile)
            ->where('ru.statusenabled', true)
            ->where('ru.objectjenispegawaifk', $this->settingFix('idJenisPegawaiDokter'))
            ->where(function ($query) {
                $query->where('ru.namalengkap', 'ilike', '%dr. Lisa Herawati Diah, Sp.KNTM, Subsp. Onk (K), FANMB%')
                    ->orWhere('ru.namalengkap', 'ilike', '%dr. Priska Gusti Wulandari, Sp.KNTM%');
            })
            ->orderBy('ru.namalengkap')
            ->get();
        $res['data'] = $dataDokter;
        $res['jeniskelamin'] = JenisKelamin::mine()->get();
        $res['golongandarah'] = GolonganDarah::mine()->get();
        $res['pegawaiRadiologi'] = DB::table('loginuser_s as ls')
            ->join('pegawai_m as pg', 'pg.id', 'ls.objectpegawaifk')
            ->select('pg.id', 'pg.namalengkap')
            // ->where('ls.objectkelompokuserfk', $this->settingFix('kdKelompokUser'))
            ->where('ls.statusenabled', true)
            ->where('ls.kdprofile', $this->kdProfile)
            ->where(function ($query) {
                $query
                    ->where('pg.namalengkap', 'ilike', '%I WAYAN DODY EKA PRATAMA%')
                    ->orWhere('pg.namalengkap', 'ilike', '%GUSTI AGUNG INTAN PUTRINATHA%');
            })
            ->get();
        return $this->respond($res);
    }

    public function getPegawaiinVitro(){
        $dataPegawai = DB::table('pegawai_m as pg')
                        ->select('pg.id', 'pg.namalengkap')
                        // ->where(function($query) {
                        //     $query->where('pg.namalengkap', 'ilike', '%I DEWA GEDE ADI NUGRAHA%')
                        //           ->orWhere('pg.namalengkap', 'ilike', '%I WAYAN BAGUS ADIGUNAWAN%');
                        // })
                        ->get();
        return $this->respond($dataPegawai);
    }

    public function getPegawaiNuklirTerapi(){
        $dataPegawai = DB::table('pegawai_m as pg')
        ->select('pg.id', 'pg.namalengkap')
        ->whereIn('pg.id', [
            483, 490, 508, 552, 601, 230, 234, 986, 
            152, 156, 103, 817, 192, 947, 109, 150, 1354
        ])
        ->get();
        return $this->respond($dataPegawai);
    }

    public function savePenjadwalan(Request $req){

        $paramsNorec_so = $req['norec_so'];
        $paramsTglpenjadwalan = $req['tglpenjadwalan'];
        $kdProfile = $this->kdProfile;

       try {
            DB::beginTransaction();
            $history=DB::table('strukorder_t as so')
            ->join('antrianpasiendiperiksa_t as apd','apd.norec','=','so.norec_apd')
            ->join('pasiendaftar_t as pd','pd.norec','=','apd.noregistrasifk')
            ->join('pasien_m as ps','ps.id','=','pd.nocmfk')
            ->select(
                'so.norec as norec_so',
                'pd.norec as norec_pd',
                'apd.norec as norec_apd',
                'pd.objectruanganasalfk',
                'pd.statuspasien',
                'pd.objectpegawaifk',
                'pd.objectkelompokpasienlastfk',
                'ps.id',
                'pd.objectrekananfk',
                'pd.jenispelayanan'
            )
            ->where('so.norec',$paramsNorec_so)
            ->first();

            $noregistrasi = $this->SEQUENCE(new PasienDaftar, 'noregistrasi', 10, date('ymd'), $kdProfile);
            if ($noregistrasi == '') {
                abort(400, 'SEQ ERROR');
            }
            $model_PD = new PasienDaftar();
            $model_PD->norec = $model_PD->generateNewId();
            $model_PD->kdprofile = $kdProfile;
            $model_PD->statusenabled = true;
            $model_PD->objectruanganasalfk = $history->objectruanganasalfk;
            $model_PD->statuspasien = $history->statuspasien;
            $model_PD->objectruanganlastfk = 330;
            $model_PD->objectpegawaifk =  $history->objectpegawaifk;
            $model_PD->objectpegawairawatbersamafk = null;
            $model_PD->objectkelompokpasienlastfk = $history->objectkelompokpasienlastfk;
            $model_PD->nocmfk = $history->id;
            $model_PD->objectrekananfk = $history->objectrekananfk;
            $model_PD->ismobilejkn = null;
            $model_PD->antrianpasienregistrasifk = null;
            $model_PD->noreservasi =  null;
            $model_PD->tglregistrasi = $paramsTglpenjadwalan;
            $model_PD->asalrujukanfk =  5;
            $model_PD->keteranganasalrujukan = null;
            $model_PD->noregistrasi = $noregistrasi;
            $model_PD->petugas = $this->getNamaPegawai();
            $model_PD->jenispelayanan =   $history->jenispelayanan;
            $model_PD->iskiosk = null;
            $model_PD->objectkelasfk = 6;
            $model_PD->objectkelasrawatfk = null;
            $model_PD->tglpulang = $paramsTglpenjadwalan;
            $model_PD->iskelastitip =  null;
            $model_PD->ispenjadwalanradiologi = true;
            $model_PD->save();

            $model_APD = new AntrianPasienDiperiksa;
            $model_APD->norec = $model_APD->generateNewId();
            $model_APD->kdprofile = (int)$kdProfile;
            $model_APD->statusenabled = true;
            $model_APD->noantrian = 1;
            $model_APD->objectasalrujukanfk =  5;
            $model_APD->objectkamarfk = null;
            $model_APD->objectruanganfk = 330;
            $model_APD->objectkelasfk = 6;
            $model_APD->tglkeluar = $paramsTglpenjadwalan;
            $model_APD->nobed = null;
            $model_APD->noregistrasifk = $model_PD->norec;
            $model_APD->objectpegawaifk =  $history->objectpegawaifk;
            $model_APD->statusantrian = 0;
            $model_APD->status = "Belum Dipanggil";
            $model_APD->statuskunjungan = $history->statuspasien;
            $model_APD->tglregistrasi =  $paramsTglpenjadwalan;
            $model_APD->tglmasuk = $paramsTglpenjadwalan;
            $model_APD->israwatgabung = false;
            $model_APD->nojkn =  null;
            $model_APD->noregistrasi = $noregistrasi;
            $model_APD->ispenjadwalanradiologi = true;
            $model_APD->save();

            DB::commit();

            $valueToUpdateOrder=array(
                'norec_so'=>$paramsNorec_so,
                'norec_apd'=>$model_APD->norec,
                'norec_pd' => $model_PD->norec,
                'noregistrasi'=>$noregistrasi,
                'tglpelayanan'=>$paramsTglpenjadwalan
            );

            $updateOrderPenjadwlan=$this->updateDataOrderPenjadwalan($valueToUpdateOrder);
            $result=array(
                'getHistoryData'=>$history, 
                'dataPD' =>$model_PD,
                'dataAPD' => $model_APD,
                'updateOrderPenjadwalan' => $updateOrderPenjadwlan,
                'code' => 201,
                'message' => 'Sukses buat penjadwalan'
            );
        } catch (Exception $e) {
            DB::rollBack();
            $result=array(
                'error'=> $e->getMessage(),
                'line' => $e->getLine(),
                'code' => 400,
                'message' =>'Something went wrong'
            );
       }
        return $this->respond($result);
    }

    private function updateDataOrderPenjadwalan ($value){

        try {
            DB::beginTransaction();
            $dataOrderPelayanan=OrderPelayanan::where('strukorderfk',$value['norec_so'])->update(
                [
                    'noregistrasi' =>$value['noregistrasi'],
                    'tglpelayanan' =>$value['tglpelayanan'],
                    'ispenjadwalanradiologi'=>true
                ]
            );
            $dataStrukOrder=StrukOrder::where('norec',$value['norec_so'])->update(
                [
                    'ispenjadwalanradiologi'=>true,
                    'norec_apd'=>$value['norec_apd'],
                    'noregistrasi'=>$value['noregistrasi'],
                    'noregistrasifk'=>$value['norec_pd'],
                    'statusorder'=> 5,
                    'tglpenjadwalanradiologi' =>$value['tglpelayanan']
                ]
            );
            DB::commit();

            $result=array(
                'dataOrderPelayanan'=>$dataOrderPelayanan,
                'dataStrukorder'=>$dataStrukOrder,
                'code' => 201
            );
        } catch (Exception $e) {
            DB::rollBack();
            $result=array(
                'error'=>$e->getMessage(),
                'Line'=>$e->getLine(),
                'code' => 400
            );
        }
        return $this->respond($result);
    }

    public function savePelayananPasien(Request $request)
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
            $dataAPD->objectkelasfk = 6;
            $dataAPD->noantrian = $getLastAntrian + 1;
            $dataAPD->noregistrasifk = $parameter['pd_norec'];
            $dataAPD->objectpegawaifk = $parameter['objectpegawaiorderfk'];
            $dataAPD->objectruanganfk = $parameter['idruangtujuan'];
            // $dataAPD->objectruanganfk = $parameter['waktuPemeriksaan'];
            $dataAPD->statusantrian = 0;
            $dataAPD->statuspasien = 1;
            $dataAPD->status = "Belum Dipanggil";
            $dataAPD->objectstrukorderfk = $parameter['so_norec'];
            $dataAPD->tglregistrasi = $parameter['tglregistrasi']; // date('Y-m-d H:i:s');
            $dataAPD->tglmasuk = $parameter['tglpelayanan'];
            $dataAPD->tglkeluar = $parameter['tglpelayanan'];
            $dataAPD->noregistrasi = $parameter['noregistrasi'];
            $dataAPD->save();
            $dataAPDnorec = $dataAPD->norec;
            $dataAPDtglPel = $dataAPD->tglregistrasi;
        } else {
            $dataAPDnorec = $apd->norec;
            $dataAPDtglPel = $apd->tglregistrasi;
        }

        try {
            $struk = StrukOrder::where('norec', $parameter['so_norec'])->where('kdprofile', $this->kdProfile)->where('statusenabled', true)->first();
            OrderPelayanan::where('strukorderfk', $struk->norec)->delete();
            $dataCreate = [];
            foreach ($dataOrder as $key => $item) {

                $dataCreate[$key]['norec'] = substr(Uuid::uuid4(), 0, 36);
                $dataCreate[$key]['kdprofile'] = $this->kdProfile;
                $dataCreate[$key]['statusenabled'] = true;
                $dataCreate[$key]['iscito'] = isset($item['iscito']) ? (float) $item['iscito'] : 0;
                $dataCreate[$key]['noorderfk'] = $struk->norec;
                $dataCreate[$key]['objectprodukfk'] = $item['idProduk'];
                $dataCreate[$key]['qtyproduk'] = $item['jumlah'];
                $dataCreate[$key]['objectkelasfk'] = 6;
                $dataCreate[$key]['qtyprodukretur'] = 0;
                $dataCreate[$key]['objectruanganfk'] = $struk->objectruanganfk;
                $dataCreate[$key]['objectruangantujuanfk'] = $struk->objectruangantujuanfk;
                $dataCreate[$key]['strukorderfk'] = $struk->norec;
                $dataCreate[$key]['tglpelayanan'] = $struk->tglorder;
                $dataCreate[$key]['objectnamapenyerahbarangfk'] = $struk->objectpegawaiorderfk;
                $dataCreate[$key]['nourut'] = null;
                // $dataOP = new OrderPelayanan;
                // $dataOP->norec = $dataOP->generateNewId();
                // $dataOP->kdprofile = $this->kdProfile;
                // $dataOP->statusenabled = true;
                // if (isset($item['iscito'])) {
                //     $dataOP->iscito = (float) $item['iscito'];
                // } else {
                //     $dataOP->iscito = 0;
                // }

                // $dataOP->noorderfk = $struk->norec;
                // $dataOP->objectprodukfk = $item['idProduk'];
                // $dataOP->qtyproduk = $item['jumlah'];
                // $dataOP->objectkelasfk = 6;
                // $dataOP->qtyprodukretur = 0;
                // $dataOP->objectruanganfk = $struk->objectruanganfk;
                // $dataOP->objectruangantujuanfk = $struk->objectruangantujuanfk;
                // $dataOP->strukorderfk = $struk->norec;
                // $dataOP->tglpelayanan = $struk->tglorder;
                // $dataOP->objectnamapenyerahbarangfk = $struk->objectpegawaiorderfk;
                // $dataOP->nourut = null;

                // $dataOP->save();
            }
            $dataOP = OrderPelayanan::insert($dataCreate);

            StrukOrder::where('norec', $parameter['so_norec'])
                ->where('kdprofile', $this->kdProfile)
                ->update(
                    [
                        'statusorder' => 1,
                        'norec_apd' => $dataAPDnorec,
                        'tglverif' => date('Y-m-d H:i:s'),
                        'nobatchradionuklida' => $parameter['nobatchradionuklida'],
                        'nobatchradiofarmaka' => $parameter['nobatchradiofarmaka'],
                        'dosisradiofarmasis' => $parameter['dosisradiofarmasis'],
                        'jampermintaan' => $parameter['jampermintaan'],
                        'dosisfullsyringe' => $parameter['dosisfullsyringe'],
                        'jamfullsyringe' => $parameter['jamfullsyringe'],
                        'dosisemptysyringe' => $parameter['dosisemptysyringe'],
                        'jamemptysyringe' => $parameter['jamemptysyringe'],
                        'rutelokasisuntik' => $parameter['rutelokasisuntik'],
                        'jaminjeksi' => $parameter['jaminjeksi'],
                        'pemeriksaanradiograferfk' => $parameter['pemeriksaanradiograferfk'],
                        'jamakuisisi' => $parameter['jamakuisisi'],
                        'treatment' => $parameter['treatment'],
                        'paparanradiasi' => $parameter['paparanradiasi'],
                        'catatanklinis' => $parameter['catatanklinis'],
                        'catatandiagnosa' => $parameter['catatanDiagnosis'],
                        'keteranganradiologi' => $parameter['keteranganradiologi'],
                        'keteranganlainnya' => $parameter['catatan'] ? $parameter['catatan'] : null,
                    ]
                );
            // $createPelayanan = [];
            $dataPelayanans = [];
            $pelPasienPetugas = [];
            $pelPasienDetails = [];

            foreach ($dataOrder as $keyP => $data) {
                $newPelayanan = [
                    'norec' => (new PelayananPasien())->generateNewId(),
                    'kdprofile' => $this->kdProfile,
                    'statusenabled' => true,
                    'noregistrasifk' => $dataAPDnorec,
                    'aturanpakai' => '-',
                    'hargadiscount' => 0,
                    'hargajual' => $data['hargaLayanan'],
                    'hargasatuan' => $data['hargaLayanan'],
                    'jumlah' => $data['jumlah'],
                    'kdkelompoktransaksi' => 1,
                    'piutangpenjamin' => 0,
                    'piutangrumahsakit' => 0,
                    'produkfk' => $data['idProduk'],
                    'stock' => 1,
                    'strukorderfk' => $parameter['so_norec'],
                    'tglpelayanan' => $parameter['tglpelayanan'],
                    'harganetto' => $data['hargaLayanan'],
                    'noregistrasi' => $parameter['noregistrasi'],
                ];
                $dataPelayanans[] = $newPelayanan;

                $newPelPasienPetugas = [
                    'norec' => (new PelayananPasienPetugas())->generateNewId(),
                    'kdprofile' => $this->kdProfile,
                    'statusenabled' => true,
                    'nomasukfk' => $dataAPDnorec,
                    'objectpegawaifk' => $data['iddokterverif'],
                    'pegawaiverifikatorfk' => $parameter['pegawaiverifikatorfk'],
                    'radiograferfk' => $parameter['radiograferfk'],
                    'tglpelayanan' => date('Y-m-d H:i:s'),
                    'objectjenispetugaspefk' => $this->settingFix('idDokterPemeriksa'),
                    'pelayananpasien' => $newPelayanan['norec'],
                    'noregistrasi' => $parameter['noregistrasi'],
                ];
                $pelPasienPetugas[] = $newPelPasienPetugas;

                foreach ($data['komponenharga'] as $itemKomponen) {
                    $newPelPasienDetail = [
                        'norec' => (new PelayananPasienDetail())->generateNewId(),
                        'kdprofile' => $this->kdProfile,
                        'statusenabled' => true,
                        'noregistrasifk' => $dataAPDnorec,
                        'aturanpakai' => '-',
                        'hargadiscount' => 0,
                        'hargajual' => $itemKomponen['hargasatuan'],
                        'hargasatuan' => $itemKomponen['hargasatuan'],
                        'jumlah' => 1,
                        'keteranganlain' => '-',
                        'keteranganpakai2' => '-',
                        'komponenhargafk' => $itemKomponen['objectkomponenhargafk'],
                        'pelayananpasien' => $newPelayanan['norec'],
                        'piutangpenjamin' => 0,
                        'piutangrumahsakit' => 0,
                        'produkfk' => $itemKomponen['objectprodukfk'],
                        'stock' => 1,
                        'strukorderfk' => $parameter['so_norec'],
                        'tglpelayanan' => $dataAPDtglPel,
                        'harganetto' => $itemKomponen['hargasatuan'],
                        'noregistrasi' => $parameter['noregistrasi'],
                    ];
                    $pelPasienDetails[] = $newPelPasienDetail;
                }
            }

            PelayananPasien::insert($dataPelayanans);
            PelayananPasienPetugas::insert($pelPasienPetugas);
            PelayananPasienDetail::insert($pelPasienDetails);


            DB::commit();
            $result = [
                'message' => 'Data Berhasil disimpan',
                'pelPasien' => $dataPelayanans,
                'petugaspelayanan' => $pelPasienPetugas,
                'detailPelayanan' => $pelPasienDetails,
                'kode' => 200
            ];
        } catch (InvalidOrderException $e) {
            DB::rollBack();

            $result = [
                'message' => 'Something Went Wrong',
                'status' => '',
                'kode' => 400
            ];
        }
        return $this->respond($result, $result['kode'], $result['message']);
    }

    public function savePelayananPasienEdit(Request $request)
    {
        $parameter = $request['parameter'];

        DB::beginTransaction();

        try {

            StrukOrder::where('norec', $parameter['so_norec'])
                ->where('kdprofile', $this->kdProfile)
                ->update(
                    [
                        'nobatchradionuklida' => $parameter['nobatchradionuklida'],
                        'nobatchradiofarmaka' => $parameter['nobatchradiofarmaka'],
                        'dosisradiofarmasis' => $parameter['dosisradiofarmasis'],
                        'jampermintaan' => $parameter['jampermintaan'],
                        'dosisfullsyringe' => $parameter['dosisfullsyringe'],
                        'jamfullsyringe' => $parameter['jamfullsyringe'],
                        'dosisemptysyringe' => $parameter['dosisemptysyringe'],
                        'jamemptysyringe' => $parameter['jamemptysyringe'],
                        'rutelokasisuntik' => $parameter['rutelokasisuntik'],
                        'jaminjeksi' => $parameter['jaminjeksi'],
                        'pemeriksaanradiograferfk' => $parameter['pemeriksaanradiograferfk'],
                        'jamakuisisi' => $parameter['jamakuisisi'],
                        'jenisakuisisifk' => $parameter['jenisakuisisifk'],
                        'treatment' => $parameter['treatment'],
                        'paparanradiasi' => $parameter['paparanradiasi'],
                        'treatment_ppr' => isset($parameter['treatment_ppr']) ? $parameter['treatment_ppr'] : null,
                        'terapiradiofarmaka' => isset($parameter['terapiradiofarmaka']) ? $parameter['terapiradiofarmaka'] : null,
                        'paparanradiasi_ppr' => isset($parameter['paparanradiasi_ppr']) ? $parameter['paparanradiasi_ppr'] : null,
                        'paparanradiasi_ppr2' => isset($parameter['paparanradiasi_ppr2']) ? $parameter['paparanradiasi_ppr2'] : null,
                        'paparanradiasi_ppr3' => isset($parameter['paparanradiasi_ppr3']) ? $parameter['paparanradiasi_ppr3'] : null,
                        'paparanradiasi_ppr4' => isset($parameter['paparanradiasi_ppr4']) ? $parameter['paparanradiasi_ppr4'] : null,
                        'paparanradiasi_ppr5' => isset($parameter['paparanradiasi_ppr5']) ? $parameter['paparanradiasi_ppr5'] : null,
                        'paparanradiasi_ppr6' => isset($parameter['paparanradiasi_ppr6']) ? $parameter['paparanradiasi_ppr6'] : null,
                        'paparanradiasi_ppr7' => isset($parameter['paparanradiasi_ppr7']) ? $parameter['paparanradiasi_ppr7'] : null,
                    ]
                );

            DB::commit();
            $result = [
                'message' => 'Data Berhasil disimpan',
                'kode' => 201
            ];
        } catch (InvalidOrderException $e) {
            DB::rollBack();

            $result = [
                'message' => 'Something Went Wrong',
                'status' => '',
                'kode' => 400
            ];
        }
        return $this->respond($result, $result['kode'], $result['message']);
    }

    public function savePelayananPasienEditReschedule(Request $request)
    {
        $parameter = $request['parameter'];
        $kdProfile = $this->kdProfile;
        $dataLogin = $request->all();

        DB::beginTransaction();

        try {

            $cekRI = StrukOrder::where('norec', $parameter['so_norec'])
                ->first();
            if ($cekRI->statusorder != 0) {
                DB::rollBack();
                $transMessage = 'Data Ordedr sudah diverifikasi';
                $result = array("status" => 400, "result" => $cekRI);
                return $this->respond($result['result'], $result['status'], $transMessage);
            }

            $nextDay = date('Y-m-d', strtotime('+1 day', strtotime($parameter['tglakhir'])));

            PasienDaftar::where('norec', $parameter['norec_pd'])
                ->where('kdprofile', $this->kdProfile)
                ->update(
                    [
                        'tglregistrasi' => $parameter['tglakhir'],
                        'tglpulang' => $parameter['tglakhir'],
                    ]
                );


            AntrianPasienDiperiksa::where('noregistrasifk', $parameter['norec_pd'])
                ->where('kdprofile', $this->kdProfile)
                ->update(
                    [
                        'tglregistrasi' => $parameter['tglakhir'],
                        'tglmasuk' => $parameter['tglakhir'],
                        'tglkeluar' => $parameter['tglakhir'],
                    ]
                );

            AksesEMR::where('pasienfk', $parameter['nocmfk'])
                ->where('kdprofile', $this->kdProfile)
                ->update(
                    [
                        'tglmulai' => $parameter['tglakhir'],
                        'tglberakhir' => $nextDay,
                    ]
                );

            StrukOrder::where('norec', $parameter['so_norec'])
                ->where('kdprofile', $this->kdProfile)
                ->update(
                    [
                        'tglpelayananakhir' => $parameter['tglakhir'],
                        'tglpelayananawal' => $parameter['tglakhir'],
                        'tglorder' => $parameter['tglakhir'],
                    ]
                );

            $model_PD = new PerubahanJadwal();
            $model_PD->norec = $model_PD->generateNewId();
            $model_PD->kdprofile = $kdProfile;
            $model_PD->statusenabled = true;
            $model_PD->tglawal = $parameter['tglawal'];
            $model_PD->tglakhir = $parameter['tglakhir'];
            $model_PD->tglinput = date('Y-m-d H:i:s');
            $model_PD->jenis = $parameter['jenis'];
            $model_PD->alasan = $parameter['alasan'];
            $model_PD->noregistrasifk = $parameter['norec_pd'];
            // $model_PD->objectpegawaifk = $dataLogin['userData']['id'];
            $model_PD->objectpegawaifk = $parameter['pegawaifk'];
            $model_PD->save();


            if ($parameter['jenis'] == 'Pembatalan') {
                StrukOrder::where('norec', $parameter['so_norec'])
                    ->where('kdprofile', $kdProfile)
                    ->delete();
            }


            DB::commit();
            $result = [
                'message' => 'Data Berhasil disimpan',
                'kode' => 201
            ];
        } catch (InvalidOrderException $e) {
            DB::rollBack();

            $result = [
                'message' => 'Something Went Wrong',
                'status' => '',
                'kode' => 400
            ];
        }
        return $this->respond($result, $result['kode'], $result['message']);
    }

    public function getDetailOrderVerify(Request $request)
    {

        $datas = DB::table('pelayananpasien_t as pp')
            ->join('produk_m as prd', 'prd.id', 'pp.produkfk')
            ->leftjoin('pelayananpasienpetugas_t as ppp', 'pp.norec', 'ppp.pelayananpasien')
            ->leftjoin('pegawai_m as pg', 'pg.id', 'ppp.objectpegawaifk')
            ->select(
                'pp.tglpelayanan',
                'pp.hargasatuan',
                DB::raw("(pp.jumlah*pp.hargasatuan) as total"),
                'prd.namaproduk',
                'pp.jumlah',
                'pg.namalengkap'
            )
            ->where('pp.strukorderfk', $request['norec_so'])
            ->where('pp.kdprofile', $this->kdProfile)
            ->where('pp.statusenabled', true)
            ->get();

        return $this->respond($datas);
    }

    public function chartOrderByRuangan(Request $request)
    {
        $dateBetween = [$request->tglAwal, $request->tglAkhir];
        $datas = DB::table('strukorder_t as so')
            ->join('ruangan_m as ru', 'ru.id', 'so.objectruanganfk')
            ->select('ru.namaruangan as namaruangan', DB::raw("count(so.objectruanganfk) as jumlah"))
            ->where('so.objectruangantujuanfk', $this->settingFix('idRuanganRadiologi'))
            ->where('so.kdprofile', $this->kdProfile)
            ->where('so.statusenabled', true)
            ->whereBetween(DB::raw("CAST(so.tglorder AS DATE)"), $dateBetween)
            ->groupBy('ru.namaruangan')
            ->get();

        $totalLayanan = [];
        $seriesLayanan = [];

        foreach ($datas as $dat) {
            $totalLayanan[] = $dat->jumlah;
            $seriesLayanan[] = strtolower($dat->namaruangan);
        }

        $result['chartLO']['count'] = $totalLayanan;
        $result['chartLO']['categories'] = $seriesLayanan;

        return $this->respond($result);
    }

    public function getRadDetail(Request $r)
    {
        $now = $this->hari_ini(date('Y-m-d'));
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
            ->whereIn('jd.objectruanganfk', explode(',', $this->settingFix('idRuanganRadiologi')))
            ->where('jd.hari', 'ilike', '%' . $now . '%')
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
            ->whereIn('ru.objectdepartemenfk', explode(',', $this->settingFix('kdDepartemenRawatJalanFix')))
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

    // Penunjang
    public function HeaderPasienRad(Request $r)
    {
        $data = DB::table('pasiendaftar_t as pd')
            ->JOIN('ruangan_m as ru', 'ru.id', '=', 'pd.objectruanganlastfk')
            ->JOIN('departemen_m as dp', 'dp.id', '=', 'ru.objectdepartemenfk')
            ->JOIN('kelompokpasien_m as kp', 'kp.id', '=', 'pd.objectkelompokpasienlastfk')
            ->JOIN('pasien_m as ps', 'ps.id', '=', 'pd.nocmfk')
            ->JOIN('alamat_m as alm', 'alm.nocmfk', '=', 'ps.id')
            ->JOIN('jeniskelamin_m as jk', 'jk.id', '=', 'ps.objectjeniskelaminfk')
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
                'kp.kelompokpasien'
            )
            ->where('ps.kdprofile', (int) $this->kdProfile)
            ->where('ps.statusenabled', true)
            ->where('ps.id', $r['nocmfk'])
            ->first();
        if (!empty($data)) {
            $data->umur = $this->getAge($data->tgllahir, date('Y-m-d H:i:s'));
        }
        $registrasi = DB::table('pasiendaftar_t as pd')
            ->JOIN('antrianpasiendiperiksa_t as apd', 'apd.noregistrasifk', '=', 'pd.norec')
            ->JOIN('ruangan_m as ru', 'ru.id', '=', 'apd.objectruanganfk')
            ->JOIN('departemen_m as dp', 'dp.id', '=', 'ru.objectdepartemenfk')
            ->JOIN('kelompokpasien_m as kp', 'kp.id', '=', 'pd.objectkelompokpasienlastfk')
            ->JOIN('kelas_m as kl', 'kl.id', '=', 'pd.objectkelasfk')
            ->select(
                'pd.noregistrasi',
                'pd.norec as norec_pd',
                'pd.tglregistrasi',
                'pd.tglpulang',
                'dp.namadepartemen',
                'kp.kelompokpasien',
                'apd.norec as norec_apd',
                'pd.objectruanganlastfk',
                'apd.objectruanganfk',
                'ru.namaruangan',
                'pd.objectkelasfk',
                'kl.namakelas',
                'pd.nocmfk',
                'apd.tglmasuk',
                'apd.tglkeluar',
                'pd.objectkelompokpasienlastfk',
                'pd.objectrekananfk',
                'pd.jenispelayanan as jenispelayananfk'
            )
            ->where('pd.kdprofile', (int) $this->kdProfile)
            ->where('pd.statusenabled', true)
            ->where('apd.statusenabled', true)
            ->where('pd.nocmfk', $r['nocmfk'])
            ->where('pd.norec', $r['norec_pd'])
            ->get();
        $last = array();
        $tgl = date('2000-01-01 00:00');
        foreach ($registrasi as $d) {
            if (isset($r['norec_apd']) && $r['norec_apd'] != '' && $r['norec_apd'] == $d->norec_apd) {
                $last = $d;
                break;
            } else {
                if ($d->objectruanganlastfk == $d->objectruanganfk && $tgl < $d->tglmasuk) {
                    $tgl = $d->tglmasuk;
                    $last = $d;
                }
            }
        }
        $result['pasien'] = $data;
        $result['registrasi'] = $registrasi;
        $result['last_registrasi'] = $last;
        $result['as'] = '@epic';

        return $this->respond($result);
    }


    public function getDaftarPasienPenunjang(Request $request)
    {
        $idProfile = $this->kdProfile;
        $dateBetween = [$request->tglAwal, $request->tglAkhir];
        $idDepartemenRadiologi = explode(',', $this->settingFix('idDepartemenRadiologi'));
        $allPeriode = ['2024-12-13', date('Y-m-d')];
        // return $idDepartemenRadiologi;
        $data = DB::table('pasiendaftar_t as pd')
            ->leftjoin('antrianpasiendiperiksa_t as apd', 'pd.norec', '=', 'apd.noregistrasifk')
            ->leftjoin('strukorder_t as so', 'apd.norec', '=', 'so.norec_apd')
            ->JOIN('ruangan_m as ru', 'ru.id', '=', 'apd.objectruanganfk')
            ->leftJOIN('ruangan_m as ruso', 'ruso.id', '=', 'so.objectruanganfk')
            ->Join('departemen_m as dept', 'dept.id', '=', 'ru.objectdepartemenfk')
            ->JOIN('pasien_m as ps', 'ps.id', '=', 'pd.nocmfk')
            ->Join('jeniskelamin_m as jk', 'jk.id', '=', 'ps.objectjeniskelaminfk')
            ->Join('kelompokpasien_m as kp', 'kp.id', '=', 'pd.objectkelompokpasienlastfk')
            ->leftjoin('rekanan_m as rk', 'rk.id', '=', 'pd.objectrekananfk')
            ->leftjoin('kelas_m as kl', 'kl.id', '=', 'pd.objectkelasfk')
            ->leftjoin('kebangsaan_m as kbs', 'kbs.id', '=', 'ps.objectkebangsaanfk')
            ->leftJoin('strukpelayanan_t as sp', 'sp.norec', '=', 'pd.nostruklastfk')
            ->leftJoin('alamat_m as alm', 'alm.nocmfk', '=', 'ps.id')
            ->leftJoin('golongandarah_m as gol', 'gol.id', '=', 'ps.objectgolongandarahfk')
            // ->join('pelayananpasien_t as pp','pp.strukorderfk','so.norec')
            ->leftJoin('ruangan_m as ru1', 'ru1.id', '=', 'so.objectruanganfk')
            ->select(
                'apd.norec as norec_apd',
                'ru.id as ruid',
                'ru.namaruangan',
                'ruso.objectdepartemenfk',
                'pd.noregistrasi',
                'ps.nocm',
                'ps.nobpjs',
                'ps.noidentitas',
                'pd.nocmfk',
                'ps.namapasien',
                'jk.jeniskelamin',
                'ps.objectjeniskelaminfk',
                'ps.objectgolongandarahfk',
                'ps.alamatrmh',
                'kp.kelompokpasien',
                'rk.namarekanan',
                'kl.namakelas',
                // 'pp.tglpelayanan',
                'kl.id as klid',
                'pd.tglregistrasi',
                'pd.tglpulang',
                'ps.tgllahir',
                'apd.norec',
                'so.tglorder',
                'pd.norec as norec_pd',
                'so.norec as norec_so',
                'sp.tglstruk',
                'pd.nostruklastfk',
                'alm.alamatlengkap',
                'gol.golongandarah',
                'apd.tglmasuk',
                'apd.isExpertise',
                'ru1.namaruangan as ruanganasal',
                'kbs.name as kebangsaan',
                DB::raw(" '' AS expertise,so.catatanklinis,'' as kddiagnosa")
            )
            ->where('apd.kdprofile', $idProfile)
            //->whereBetween(DB::raw("CAST(so.tglverif as DATE)"), $dateBetween)
            // ->whereBetween(DB::raw("CAST(apd.updated_at as DATE)"), $dateBetween)
            // ->whereBetween(function ($query) use ($dateBetween){
            //     $query->whereBetween(DB::raw("CAST(apd.tglmasuk as DATE)"),$dateBetween)
            //     ->orwhereBetween(DB::raw("CAST(so.tglorder as DATE)"),$dateBetween);
            // })
            ->whereIn('ru.objectdepartemenfk', $idDepartemenRadiologi)
            // ->whereBetween(DB::raw("CAST(apd.tglmasuk as DATE)"),$dateRange)
            ->orderBy('apd.tglregistrasi', 'desc');
        // return $this->settingFix('idDepartemenRadiologi');
        if (isset($request['allperiode']) && $request['allperiode'] == 'true') {
            $data = $data->whereBetween(DB::raw("CAST(so.tglorder as DATE)"), $allPeriode);
        }
        if (isset($request['allperiode']) && $request['allperiode'] == 'false') {
            $data = $data->whereBetween(DB::raw("CAST(so.tglorder as DATE)"), $dateBetween);
        }
        if (isset($request['statusorder']) && $request['statusorder'] == '0') {
            // $data = $data->whereNotNull('so.norec');
            $data = $data->whereIn('ruso.objectdepartemenfk',explode(',',$this->settingFix('kdDepartemenRawatJalanFix')));
        }
        if (isset($request['statusorder']) && $request['statusorder'] == '1') {
            $data = $data->whereNull('so.norec');
        }
        if (isset($request['statusorder']) && $request['statusorder'] == '2') {
            $data = $data->where('ruso.objectdepartemenfk',16);
        }
        if (isset($request['statusorder']) && $request['statusorder'] == '3') {
            $data = $data->where('ruso.objectdepartemenfk',45);
        }
        if (isset($request['ruanganid']) && $request['ruanganid'] != '') {
            $data = $data->where('ru.id', '=', $request['ruanganid']);
        }
        if (isset($request['qsearch']) && $request['qsearch'] != '') {
            $searchTerm = '%' . $request['qsearch'] . '%';
            $data = $data->where(function ($query) use ($searchTerm) {
                $query->where('ps.namapasien', 'ilike', $searchTerm)
                    ->orWhere('pd.noregistrasi', 'ilike', $searchTerm)
                    ->orWhere('ps.nocm', 'ilike', $searchTerm)
                    ->orWhere('ps.nobpjs', 'ilike', $searchTerm)
                    ->orWhere('ps.noidentitas', 'ilike', $searchTerm);
            });
        }
        // if (isset($request['qnama']) && $request['qnama'] != '') {
        //     $data = $data->where('ps.namapasien', 'ilike', '%' . $request['qnama'] . '%');
        // }
        // if (isset($request['qnocm']) && $request['qnocm'] != '') {
        //     $data = $data->where('ps.nocm', 'ilike', '%' . $request['qnocm'] . '%');
        // }
        $total = $data->count();
        // if (isset($request['qnoregistrasi']) && $request['qnoregistrasi'] != '') {
        //     $data = $data->where('pd.noregistrasi', 'ilike', '%' . $request['qnoregistrasi'] . '%');
        // }
        if (isset($request['limit']) && $request['limit'] != '') {
            $data = $data->limit($request['limit']);
        }
        if (isset($request['offset']) && $request['offset'] != '') {
            $data = $data->offset($request['offset']);
        }

        $data = $data->get();
        // return $data;
        $apdnorec = [];
        foreach ($data as $key => $v) {
            $apdnorec[] = $v->norec_apd;
        }

        $hasilLab = DB::table('pelayananpasien_t as pp')
            ->join('hasillaboratorium_t as hh', 'pp.norec', '=', 'hh.norecpelayanan')
            ->distinct()
            ->select('hh.tglhasil as tanggal', 'pp.noregistrasifk as norec_apd')
            ->whereIn('pp.noregistrasifk', $apdnorec)
            ->orderBy('hh.tglhasil', 'desc')
            ->get();
        $i = 0;
        foreach ($data as $key => $v) {
            $data[$i]->expertise = false;
            $data[$i]->tglexpertise = null;
            foreach ($hasilLab as $key2 => $v2) {
                if ($data[$i]->norec_apd == $v2->norec_apd) {
                    $data[$i]->expertise = true;
                    $data[$i]->tglexpertise = $v2->tanggal;
                }
            }
            $i = $i + 1;
        }
        $result = array(
            "data" => $data,
            "as" => '@epic',
            "total" => $total
        );
        return $this->respond($result);
    }



    // Update Jenis Kelamin
    public function UpdateJK(Request $request)
    {
        $kdProfile = $this->kdProfile;
        DB::beginTransaction();
        try {
            $pasien = Pasien::where('nocm', $request['nocm'])->where('kdprofile', $kdProfile)
                ->update(['objectjeniskelaminfk' => $request->objectjeniskelaminfk]);
            DB::commit();
            $result = [
                "status" => 200,
                "message" => "Edit Jenis Kelamin Berhasil",
                "result" => $pasien
            ];
        } catch (Exception $e) {
            DB::rollBack();
            $result = array(
                "status" => 400,
                "message" => "Simpan Gagal !",
                "result" => null
            );
        }
        return $this->respond($result['result'], $result['status'], $result['message']);
    }
    // Update Golongan Darah
    public function UpdateGoldar(Request $request)
    {
        $kdProfile = $this->kdProfile;
        DB::beginTransaction();
        try {
            $pasien = Pasien::where('nocm', $request['nocm'])->where('kdprofile', $kdProfile)
                ->update(['objectgolongandarahfk' => $request->objectgolongandarahfk]);
            DB::commit();
            $result = [
                "status" => 200,
                "message" => "Edit Golongan Darah Berhasil",
                "result" => $pasien
            ];
        } catch (Exception $e) {
            DB::rollBack();
            $result = array(
                "status" => 400,
                "message" => "Simpan Gagal !",
                "result" => $e->getMessage() . $e->getLine()
            );
        }
        return $this->respond($result['result'], $result['status'], $result['message']);
    }

    public function detailPetugasRad(Request $r)
    {
        $kdProfile = $this->kdProfile;
        $result = DB::table('pelayananpasienpetugas_t as pp')
            ->join('jenispetugaspelaksana_m as jp', 'jp.id', '=', 'pp.objectjenispetugaspefk')
            ->join('pegawai_m as pg', 'pg.id', '=', 'pp.objectpegawaifk')
            ->select(
                'pp.norec as norec_pp',
                'pg.namalengkap',
                'jp.jenispetugaspe',
                'pp.objectpegawaifk',
                'pp.objectjenispetugaspefk',
                'pp.nomasukfk',
                'pp.pelayananpasien'
            )
            ->where('pp.pelayananpasien', $r['norec_pp'])
            ->where('pp.statusenabled', true)
            ->where('pp.kdprofile', $kdProfile)
            ->get();

        return $this->respond($result);
    }

    public function deleteJenisPetugasRad(Request $r)
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
                'Hapus Petugas Tindakan ' . $pg->namalengkap . ' pelayanan '
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
                "result" => $e->getMessage() . ' ' . $e->getLine()
            );
        }
        return $this->respond($result['result'], $result['status'], $transMessage);
    }

    public function savePetugasRad(Request $r)
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
                $log . 'Simpan Petugas Tindakan',
                $new_PPP->norec,
                'pelayananpasienpetugas_t',
                $log . 'Petugas Tindakan ' . $pg->namalengkap
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
                "result" => $e->getMessage() . ' ' . $e->getLine()
            );
        }
        return $this->respond($result['result'], $result['status'], $transMessage);
    }
}
