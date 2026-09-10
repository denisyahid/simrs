<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Master\GolonganDarah;
use App\Models\Master\JenisKelamin;
use App\Models\Master\Pasien;
use App\Models\Master\Pegawai;
use App\Models\Master\Ruangan;
use App\Models\Transaksi\AntrianPasienDiperiksa;
use App\Models\Transaksi\PasienDaftar;
use App\Models\Transaksi\PelayananPasien;
use App\Models\Transaksi\PelayananPasienDetail;
use App\Models\Transaksi\PelayananPasienPetugas;
use App\Models\Transaksi\OrderPelayanan;
use App\Models\Transaksi\StrukOrder;
use App\Traits\Valet;
use Exception;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Mockery\Exception\InvalidOrderException;
use Mockery\Undefined;

class DashboardLaboratoriumCtrl extends Controller
{
    use Valet;
    public function __construct()
    {
        parent::__construct($is_encrypt = true);
    }
    public function listLab(Request $r)
    {
        $set = explode(',', $this->settingFix('idDepartemenLab'));
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
        ->whereIn('objectdepartemenfk', $set)
        ->groupBy('ru.id', 'ru.namaruangan')
        ->get();
        $res['jeniskelamin'] = JenisKelamin::mine()->get();
        $res['golongandarah'] = GolonganDarah::mine()->get();
        $res['idJenisPegawaiDokter'] = explode(',', $this->settingFix('idJenisPegawaiDokter'));
        $res['namalengkap'] =
            Pegawai::mine()
            ->where('objectjenispegawaifk', $res['idJenisPegawaiDokter'])
            ->search($r['label'])
            ->paging($r['limit'])
            ->get();

        $res['cito'] = $this->settingFix('tarifCito');

        return $this->respond($res);
    }

    public function getStrukOrderLab(Request $request)
    {
        $dateBetween = [$request->tglAwal, $request->tglAkhir];
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
            ->leftJoin('kebangsaan_m as kbs', 'kbs.id', '=', 'pas.objectkebangsaanfk')
            // ->leftJoin('diagnosapasien_t as dp', 'dp.noregistrasifk', 'apd.norec')
            ->select(
                'so.norec',
                'ruTu.id',
                'pd.noregistrasi',
                'pd.norec as pd_norec',
                // 'apd.noregistrasifk',
                // 'apd.norec as apd_norec',
                // 'dp.ketdiagnosis',
                // 'dp.norec as dp_norec',
                'so.noorder',
                'so.statusorder',
                'pd.jenispelayanan as jenispelayananfk',
                'pd.tglregistrasi',
                'pas.namapasien',
                'pas.noidentitas',
                'pas.nobpjs',
                'pas.nohp',
                'pas.tgllahir',
                'pas.nocm',
                'pas.objectjeniskelaminfk',
                DB::raw("CAST(so.tglorder AS DATE)"),
                'pas.noidentitas',
                'pas.objectkebangsaanfk',
                'pas.id as id_pasien',
                'pas.alamatlengkap as alamat',
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
                'so.catatanklinis',
                'so.keteranganlainnya',
                'dep2.namadepartemen as departementujuan',
                'peg.namalengkap',
                'pa.nosep',
                'so.namafile',
                'so.tglorder',
                'kbs.name as kebangsaan',
            )
            ->whereBetween(DB::raw("CAST(so.tglorder AS DATE)"), $dateBetween)
            ->where('so.kdprofile', $this->kdProfile)
            // ->where('so.objectruangantujuanfk', $this->settingFix('idDepartemenLab'))
            ->where('ruTu.objectdepartemenfk', $this->settingFix('idDepartemenLab'))
            ->where('so.statusenabled', true)
            ->orderByDesc('pd.tglregistrasi');

        if (isset($request['statusorder']) && $request['statusorder'] != '' && $request['statusorder'] != 'undefined') {
            $dataOrder = $dataOrder->where('so.statusorder', '=', $request['statusorder']);
        }
        if (isset($request['ruanganid']) && $request['ruanganid'] != '') {
            $dataOrder = $dataOrder->where('ruTu.id', '=',  $request['ruanganid']);
        }
        if (isset($request['ruanganAsal_id']) && $request['ruanganAsal_id'] != '') {
            $dataOrder = $dataOrder->where('ruAs.id', '=',  $request['ruanganAsal_id']);
        }
        if (isset($request['noorder']) && $request['noorder'] != '') {
            $dataOrder = $dataOrder->where('so.noorder', '=', $request['noorder']);
        }
        if (isset($request['search']) && $request['search'] != '') {
            $searchTerm = '%' . $request['search'] . '%';
            $dataOrder = $dataOrder->where(function ($query) use ($searchTerm) {
                $query->where('pas.namapasien', 'ilike', $searchTerm)
                      ->orWhere('pd.noregistrasi', 'ilike', $searchTerm)
                      ->orWhere('pas.nocm', 'ilike', $searchTerm)
                      ->orWhere('pas.nobpjs', 'ilike', $searchTerm)
                      ->orWhere('pas.noidentitas', 'ilike', $searchTerm)
                      ->orWhere('so.noorder', 'ilike', $searchTerm);
            });
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
        $total = $dataOrder->count();
        if (isset($request['limit']) && $request['limit'] != '') {
            $dataOrder = $dataOrder->limit($request['limit']);
        }
        if (isset($request['offset']) && $request['offset'] != '') {
            $dataOrder = $dataOrder->offset($request['offset']);
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
                'noregistrasi' => $datas->noregistrasi,
                'so_norec' => $datas->norec,
                'iscito' => $datas->cito,
                'pd_norec' => $datas->pd_norec,
                'jenispelayananfk' => $datas->jenispelayananfk,
                'noorder' => $datas->noorder,
                'tglregistrasi' => $datas->tglregistrasi,
                'tglorder' => $datas->tglorder,
                'id_pasien' => $datas->id_pasien,
                'alamat' => $datas->alamat,
                'pas_nocm' => $datas->nocm,
                'pas_noidentitas' => $datas->noidentitas,
                'no_bpjs' => $datas->nobpjs,
                'nohp' => $datas->nohp,
                'statusorder' => $datas->statusorder,
                'jeniskelamin' => $datas->jeniskelamin,
                'objectkebangsaanfk' => $datas->objectkebangsaanfk,
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
                'catatanklinis'=> $datas->catatanklinis,
                'keteranganlainnya' => $datas->keteranganlainnya,
                'objectpegawaiorderfk' => $datas->objectpegawaiorderfk,
                'umur' => $this->getAge($datas->tgllahir, $datas->tglorder),
                'nosep' =>$datas->nosep,
                'namafile' => $datas->namafile,
                'tgllahir' => $datas->tgllahir,
                'kebangsaan' => $datas->kebangsaan,
                // 'detailDiagnosa' => $detail
            ];
        }
        $results =[
            'data' => $result,
            'count' => $total
        ];
        return $this->respond($results);
    }

    public function getOrderPelayananLab(Request $request)
    {
        $so = StrukOrder::where('norec', $request['strukorderfk'])->where('kdprofile', $this->kdProfile)->where('statusenabled',true)->first();
        $pasienDaftar = PasienDaftar::where('norec', $so->noregistrasifk)->where('kdprofile', $this->kdProfile)->orderBy('tglregistrasi','Desc')->first();
        $pasien = Pasien::where('id', '=', $pasienDaftar->nocmfk)->where('kdprofile', $this->kdProfile)->where('statusenabled',true)->first();
        $jp = (int)$pasienDaftar->jenispelayanan;
        $idpenjamin = -1;//$pasienDaftar->objectrekananfk == null ? '-1' : $pasienDaftar->objectrekananfk;
        if ($idpenjamin != "-1") {
            $dataOrderPelayanan = DB::table('strukorder_t as so')
                ->join('orderpelayanan_t as op', 'op.strukorderfk', 'so.norec')
                ->join('produk_m as pr', 'pr.id', 'op.objectprodukfk')
                ->join('harganettoprodukbykelas_m as hnp', function ($join) {
                    $join->on('pr.id', '=', 'hnp.objectprodukfk')
                        ->where('pr.kdprofile', $this->kdProfile)
                        ->where('hnp.statusenabled', true);
                })
                ->join('kelas_m as kls', 'kls.id', 'hnp.objectkelasfk')
                ->join('ruangan_m as ru', 'ru.id', 'so.objectruangantujuanfk')
                ->leftjoin('ruangan_m as ru1', 'ru1.id', 'so.objectruanganfk')
                ->join('departemen_m as dpm', 'dpm.id', 'ru.objectdepartemenfk')
                ->leftjoin('pelayananpasien_t as pps', function ($join) {
                    $join->on('pps.strukorderfk', '=', 'so.norec')->on('op.objectprodukfk', '=', 'pps.produkfk');
                })
                ->select(DB::raw(
                    "DISTINCT op.norec as norec_op,
                    pr.id as prid,
                    pr.namaproduk,
                    hnp.objectkelasfk,
                    op.tglpelayanan,
                    op.qtyproduk ,
                    ru.namaruangan as ruangantujuan,
                    ru1.namaruangan as ruanganasal,
                    ru.objectdepartemenfk,
                    op.strukorderfk,
                    so.objectruangantujuanfk,
                    so.objectruanganfk,
                    so.cito,
                    so.catatanklinis,
                    hnp.hargasatuan ,
                    kls.namakelas,
                    dpm.namadepartemen,
                    pps.norec as norec_pp,
                    CASE WHEN hnp.hargadijamin IS NULL THEN 0 ELSE hnp.hargadijamin END AS hargadijamin"
                ))
                ->where('op.kdprofile', $this->kdProfile)
                ->where('op.statusenabled', true)
                ->where('op.strukorderfk', $request['strukorderfk'])
                // ->where('hnp.objectkelasfk',  $request['objectkelasfk'])
                ->where('hnp.objectkelasfk', $this->settingFix('kdKelasLabRad'))
                ->where('hnp.objectjenispelayananfk', $jp)
                ->where('hnp.objectkebangsaanfk', $pasien->objectkebangsaanfk)
                ->where('hnp.objectpenjaminfk', $idpenjamin)
                ->get();
        } else {
            $dataOrderPelayanan = [];
        }
        if (count($dataOrderPelayanan) == 0) {
            // return $this->respond('cek');
            $dataOrderPelayanan = DB::table('strukorder_t  as so')
                ->join('orderpelayanan_t as op', 'op.strukorderfk', 'so.norec')
                ->join('produk_m as pr', 'pr.id', 'op.objectprodukfk')
                ->join('harganettoprodukbykelas_m as hnp', 'pr.id', 'hnp.objectprodukfk')
                ->join('kelas_m as kls', 'kls.id', 'hnp.objectkelasfk')
                ->join('ruangan_m as ru', 'ru.id', 'so.objectruangantujuanfk')
                ->leftjoin('ruangan_m as ru1', 'ru1.id', 'so.objectruanganfk')
                ->join('departemen_m as dpm', 'dpm.id', 'ru.objectdepartemenfk')
                ->leftjoin('pelayananpasien_t as pps', function ($join) {
                    $join->on('pps.strukorderfk', '=', 'so.norec')
                        ->on('pps.produkfk', '=', 'pr.id')->on('pps.kdprofile', 'so.kdprofile');
                })

                ->select(DB::raw("op.norec as norec_op,
                pr.id as prid,
                pr.namaproduk,
                op.tglpelayanan,
                op.qtyproduk,
                ru.namaruangan as ruangantujuan,
                ru.objectdepartemenfk,
                op.strukorderfk,
                so.objectruangantujuanfk,
                hnp.objectkelasfk,
                hnp.hargasatuan,
                so.objectruanganfk,
                so.cito,
                so.catatanklinis,
                kls.namakelas,
                ru1.namaruangan as ruanganasal,
                dpm.namadepartemen,
                pps.norec as norec_pp,
                CASE WHEN hnp.hargadijamin IS NULL THEN 0 ELSE hnp.hargadijamin END AS hargadijamin"))
                ->where('op.kdprofile', $this->kdProfile)
                // ->where('hnp.objectkelasfk', $request['objectkelasfk'])
                ->where('hnp.objectkelasfk', $this->settingFix('kdKelasLabRad'))
                ->where('hnp.statusenabled', true)
                ->whereNull('hnp.objectpenjaminfk')
                ->where('op.statusenabled', true)
                ->where('op.strukorderfk', $request['strukorderfk'])
                ->where('hnp.objectjenispelayananfk', $jp)
                ->where('hnp.objectkebangsaanfk', $pasien->objectkebangsaanfk)
                ->get();
        } else {
            return $this->respond('nothing');
        }

        //var_dump($dataOrderPelayanan->toSql());

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
                ->where('hnp.objectkelasfk', $this->settingFix('kdKelasLabRad'))
                // ->where('hnp.objectkelasfk', $item->objectkelasfk)
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
                    // ->where('hnp.objectkelasfk', $item->objectkelasfk)
                    ->where('hnp.objectkelasfk', $this->settingFix('kdKelasLabRad'))
                    ->whereNull('hnp.objectpenjaminfk')
                    ->where('hnp.statusenabled', true)
                    ->where('hnp.objectjenispelayananfk', $jp)
                    ->where('prd.id', $item->prid)
                    ->get();
            }
            $nilaiCito = $item->cito;
            $nilaiStatusCito = 0;

            $result[] = array(
                'norec_op' => $item->norec_op,
                'norec_pp' => $item->norec_pp,
                'prid' => $item->prid,
                'catatanklinis' => $item->catatanklinis,
                'namaproduk' => $item->namaproduk,
                'qtyproduk' => $item->qtyproduk,
                'tglpelayanan' => $item->tglpelayanan,
                'idruangan' => $item->objectruangantujuanfk,
                'ruangantujuan' => $item->ruangantujuan,
                'ruanganasal' => $item->ruanganasal,
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

    public function getKomponenHargaLab(Request $request)
    {
        $data = DB::table('harganettoprodukbykelasd_m as hnp')
            ->join('produk_m as prd', 'prd.id', '=', 'hnp.objectprodukfk')
            ->join('komponenharga_m as kh', 'kh.id', '=', 'hnp.objectkomponenhargafk')
            ->join('kelas_m as kls', 'kls.id', '=', 'hnp.objectkelasfk')
            ->select('hnp.objectkomponenhargafk', 'kh.komponenharga', 'hnp.hargasatuan', 'hnp.objectprodukfk', 'kh.iscito')
            ->where('hnp.kdprofile', $this->kdProfile)
            // ->where('hnp.objectkelasfk', $request['idKelas'])
            ->where('hnp.objectkelasfk', $this->settingFix('kdKelasLabRad'))
            ->where('hnp.objectprodukfk', $request['idProduk'])
            ->where('hnp.objectjenispelayananfk', $request['idJenLayan'])
            ->where('hnp.statusenabled', true)
            ->distinct()
            ->get();

        return $this->respond($data);
    }

    public function getPelayananLab(Request $request)
    {
        $datas = DB::table('produk_m as prd')
            ->join('mapruangantoproduk_m as mpr','mpr.objectprodukfk','=', 'prd.id')
            ->join('harganettoprodukbykelas_m as hnp', function ($join) {
                $join->on('hnp.objectprodukfk', 'mpr.objectprodukfk')
                    ->where('hnp.statusenabled', true);
            })
            ->join ('kelas_m as kls','kls.id','hnp.objectkelasfk')
            ->join('ruangan_m as ru','ru.id', '=','mpr.objectruanganfk')
            ->join('departemen_m as dp', 'dp.id','=', 'ru.objectdepartemenfk')
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
            // ->where('hnp.objectkelasfk','=',$request['idkelas'])
            ->where('hnp.objectkelasfk', $this->settingFix('kdKelasLabRad'))
            ->where('hnp.objectjenispelayananfk', $request['idjenispelayanan'])
            ->where('hnp.objectkebangsaanfk', $request['objectkebangsaanfk'])
            ->where('mpr.objectruanganfk', $request['idruangan'])
            ->whereIN('ru.objectdepartemenfk', explode(',', $this->settingFix('idDepartemenLab')))
            ->where('mpr.statusenabled', true)
            ->where('prd.statusenabled', true)
            ->where('kls.statusenabled', true);

        if ($request->idProduk) {
            $datas = $datas->where('mpr.objectprodukfk', $request['idProduk']);
        }
        $datas = $datas->groupBy(
            'mpr.id',
            'prd.id',
            'prd.namaproduk',
            'hnp.hargasatuan',
            'ru.namaruangan',
            'mpr.objectprodukfk',
            'mpr.objectruanganfk'
        )->get();

        return $this->respond($datas);
    }

    // public function getRiwayatOrderPrint(Request $req){
    //     $datas = DB::table('strukorder_t as so')
    //         ->join('orderpelayanan_t as op', 'op.strukorderfk', 'so.norec')
    //         ->join('ruangan_m as ru', 'ru.id', 'so.objectruanganfk')
    //         ->join('produk_m as pr', 'pr.id', 'op.objectprodukfk')
    //         ->join('pasiendaftar_t as pd', 'pd.norec', '=', 'so.noregistrasifk')
    //         ->join('pasien_m as ps', 'ps.id', '=', 'pd.nocmfk')
    //         ->leftJoin('pegawai_m AS pg', 'pg.id', 'so.objectpegawaiorderfk')
    //         ->leftJoin('alamat_m AS al', 'al.nocmfk', 'ps.id')
    //         ->leftJoin('kelompokpasien_m as kp', 'kp.id', 'pd.objectkelompokpasienlastfk')
    //         ->leftJoin('diagnosapasien_t as dp', 'dp.noregistrasifk', 'so.norec')
    //         ->select(
    //             'so.noorder',
    //             'so.tglorder',
    //             'ps.nocm',
    //             'ps.namapasien',
    //             'ps.tgllahir',
    //             'ps.email',
    //             'al.alamatlengkap',
    //             'ps.nohp',
    //             'ru.namaruangan AS ruanganasal',
    //             'kp.kelompokpasien',
    //             'pr.namaproduk',
    //             'pg.namalengkap AS pengorder',
    //             'pg.notlp AS tlpdokter',
    //             'pg.alamat AS alamatdokter',
    //             'ps.penanggungjawab'
    //         )
    //         ->where('so.norec', $req['norec_so'])
    //         ->get();

    //     $result=array(
    //         'data'=>$datas
    //     );
    //     return $this->respond($result);
    // }

    public function ListDokterVerify()
    {
        $praktek  = DB::table('jadwaldokter_m as jd')
            ->leftjoin('ruangan_m as ru', 'ru.id', '=', 'jd.objectruanganfk')
            ->leftjoin('pegawai_m as pg', 'pg.id', '=', 'jd.objectpegawaifk')
            ->select(
                'ru.id',
                'ru.namaruangan',
                'pg.namalengkap',
                'jd.jammulai',
                'jd.jamakhir',
                DB::raw("lower(jd.hari) as hari"),
            )
            ->where('jd.kdprofile', $this->kdProfile)
            ->where('jd.statusenabled', '=', 'true')
            ->where('ru.objectdepartemenfk', explode(',', $this->settingFix('idDepartemenLab')));

        $praktek =  $praktek->get();

        return $this->respond($praktek);
    }

    public function savePelayananPasienLab(Request $request)
    {
        $parameter = $request['parameter'];
        $dataOrder = $request['data'];
        $idProfile = (int) $this->kdProfile;
        // $compareDataOp=DB::table('orderpelayanan_t as op')->join('strukorder_t as so','so.norec','=','op.strukorderfk')->where('so.noorder',$request['noorder'])->where('op.statusenabled',true)->select('op.objectprodukfk')->get()->toArray();
        
        $compareDataOp = DB::table('orderpelayanan_t as op')
            ->join('strukorder_t as so', 'so.norec', '=', 'op.strukorderfk')
            ->where('so.noorder', $request['noorder'])
            ->where('op.statusenabled', true)
            ->select('op.objectprodukfk')
            ->get()
            ->pluck('objectprodukfk')  
            ->map(function($item) { return (int)$item; })
            ->toArray(); 

        // Validasi ketika ada pemeriksaan yang sama
        if (empty($compareDataOp) || count($compareDataOp) == 0) {
            $idProdukList = array_column($dataOrder, 'idProduk');
            foreach ($idProdukList as $dt) {
                if (in_array($dt, $compareDataOp)) {
                    $result = [
                        'message' => 'Terdapat pemeriksaan yang sama!',
                        'kode' => 400
                    ];
                    return $this->respond($result, $result['kode'], $result['message']);
                }
            }
        }

        DB::beginTransaction();
        try {
            $dataSO=DB::table('strukorder_t as so')->where('so.noorder',$request['noorder'])->where('statusenabled',true)->select('so.norec as norec','so.objectruanganfk','so.objectruangantujuanfk')->orderbyDesc('so.tglorder')->first();
            if ($parameter['idruangtujuan'] != 338) {
                $cekDataOP=DB::table('orderpelayanan_t as op')->where('op.strukorderfk',$dataSO->norec)->where('op.statusenabled',true)->select('op.norec as norec')->first();
            }

            // $datdel=DB::table('orderpelayanan_t as op')->where('op.strukorderfk',$dataSO->norec)->delete();
            // if(!empty($datdel)){
            //     DB::commit();
            // }

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
            $dataAPD->kdprofile = $this->kdProfile;;
            $dataAPD->objectasalrujukanfk = 1;
            $dataAPD->statusenabled = true;
            $dataAPD->objectkelasfk = 6;
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
            $dataAPD->noregistrasi =  $parameter['noregistrasi'];
            $dataAPD->save();
            $dataAPDnorec = $dataAPD->norec;
            $dataAPDtglPel = $dataAPD->tglregistrasi;
        } else {
            AntrianPasienDiperiksa::where('noregistrasifk', $parameter['pd_norec'])
            ->where('kdprofile', $this->kdProfile)
            ->where('objectruanganfk', $parameter['idruangtujuan'])
            ->where('statusenabled', true)
            ->update(['updated_at' => date('Y-m-d H:i:s')]);
            $dataAPDnorec = $apd->norec;
            $dataAPDtglPel = $apd->tglregistrasi;
        }


            StrukOrder::where('norec', $parameter['so_norec'])
                ->where('kdprofile', $this->kdProfile)
                ->update(
                    [
                        'catatanklinis' => $parameter['catatanklinis'],
                        'statusorder' => 1,
                        'norec_apd' => $dataAPDnorec,
                        'tglverif' => date('Y-m-d H:i:s')
                        
                    ]
                );

            foreach ($dataOrder as $data) {
                // return $this->respond($data['idProduk']);
                $dataPelayanan = new PelayananPasien();
                $dataPelayanan->norec = $dataPelayanan->generateNewId();
                $dataPelayanan->kdprofile = $this->kdProfile;
                $dataPelayanan->statusenabled = true;
                $dataPelayanan->noregistrasifk = $dataAPDnorec;
                $dataPelayanan->aturanpakai = '-';
                $dataPelayanan->hargadiscount = 0;
                // $dataPelayanan->hargajual =  $data['hargaLayanan'];          //OLD METHOD
                $dataPelayanan->hargajual = isset($data['istidaktagih']) == true ? 0 : $data['hargaLayanan'];
                // $dataPelayanan->hargasatuan = $data['hargaLayanan'];         //OLD METHOD
                $dataPelayanan->hargasatuan = isset($data['istidaktagih']) == true ? 0 : $data['hargaLayanan'];
                $dataPelayanan->jumlah = $data['jumlah'];
                $dataPelayanan->kdkelompoktransaksi =  1;
                $dataPelayanan->piutangpenjamin = 0;
                $dataPelayanan->piutangrumahsakit = 0;
                $dataPelayanan->produkfk =  $data['idProduk'];
                $dataPelayanan->stock = 1;
                $dataPelayanan->pelayananpegawaifk=$parameter['objectpegawaifk'];
                $dataPelayanan->strukorderfk =  $parameter['so_norec'];
                $dataPelayanan->tglpelayanan =  date('Y-m-d H:i:s');
                // $dataPelayanan->harganetto = $data['hargaLayanan'];          //OLD METHOD
                $dataPelayanan->harganetto = isset($data['istidaktagih']) == true ? 0 : $data['hargaLayanan'];
                $dataPelayanan->noregistrasi =  $parameter['noregistrasi'];
                $dataPelayanan->istidaktagih =  isset($data['istidaktagih']) ? $data['istidaktagih'] : null;
                // $dataPelayanan->iscito = $parameter['iscito'];
                $dataPelayanan->save();

                $PelPasienPetugas = new PelayananPasienPetugas();
                $PelPasienPetugas->norec = $PelPasienPetugas->generateNewId();
                $PelPasienPetugas->kdprofile = $this->kdProfile;
                $PelPasienPetugas->statusenabled = true;
                $PelPasienPetugas->nomasukfk = $dataAPDnorec;
                $PelPasienPetugas->objectpegawaifk = $parameter['objectpegawaifk']; //$request['objectpegawaiorderfk'];
                $PelPasienPetugas->pegawaiverifikatorfk = $parameter['pegawaiverifikatorfk'];
                $PelPasienPetugas->tglpelayanan =  date('Y-m-d H:i:s');
                $PelPasienPetugas->objectjenispetugaspefk = $this->settingFix('idDokterPemeriksa'); //$jenisPetugasPe->objectjenispetugaspefk;
                $PelPasienPetugas->pelayananpasien = $dataPelayanan->norec;
                $PelPasienPetugas->noregistrasi =  $parameter['noregistrasi'];
                $PelPasienPetugas->save();

                if(!in_array($data['idProduk'], $compareDataOp)){
                    $dataOP = new OrderPelayanan();
                    $dataOP->norec = $dataOP->generateNewId();
                    $dataOP->kdprofile = $idProfile;
                    $dataOP->statusenabled = true;
                    $dataOP->iscito = $data['cito'];
                    $dataOP->noorderfk = $dataSO->norec;
                    $dataOP->objectprodukfk = $data['idProduk'];
                    $dataOP->qtyproduk = $data['jumlah'];
                    $dataOP->objectkelasfk = 2;
                    $dataOP->qtyprodukretur = 0;
                    $dataOP->noregistrasi = $parameter['noregistrasi'];
                    $dataOP->objectruanganfk = $dataSO->objectruanganfk;
                    $dataOP->objectruangantujuanfk = $dataSO->objectruangantujuanfk;
                    $dataOP->strukorderfk = $dataSO->norec;
                    $dataOP->tglpelayanan = date('Y-m-d H:i:s');
                    $dataOP->save();
                }

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
                    $PelPasienDetail->produkfk =  $itemKomponen['objectprodukfk'];
                    $PelPasienDetail->stock = 1;
                    $PelPasienDetail->strukorderfk =  $parameter['so_norec'];
                    $PelPasienDetail->tglpelayanan = $dataAPDtglPel;
                    $PelPasienDetail->harganetto = $itemKomponen['hargasatuan'];
                    $PelPasienDetail->noregistrasi =  $parameter['noregistrasi'];
                    $PelPasienDetail->save();

                    $PPDnorec = $PelPasienDetail->norec;
                    $transStatus = 'true';
                }
            }

            $datSO = StrukOrder::where('norec', $parameter['so_norec'])->where('kdprofile', $this->kdProfile)->first();

            DB::commit();
            $objetoRequest = new \Illuminate\Http\Request();
            $objetoRequest['noorder'] = $datSO->noorder;
            $ihs = app('App\Http\Controllers\Bridging\SATUSEHATCtrl')->Specimen($objetoRequest, true);
            $result = [
                'message' => 'Data Berhasil disimpan',
                'pelPasien' => $dataPelayanan,
                'petugaspelayanan' => $PelPasienPetugas,
                'detailPelayanan' => $PelPasienDetail,
                'dataOp' => empty($dataOP) ? null : $dataOP,
                "composition" => $ihs,
                'kode' => 200,
                'comparedata'=>$compareDataOp
            ];
        } catch (Exception  $e) {
            DB::rollBack();

            $result = [
                'message' => 'Data Gagal Disimpan, Silakan Periksa Kembali Data',
                'status' => $e->getMessage() . ' ' . $e->getLine(),
                'kode' => 400
            ];
        }
        return $this->respond($result, $result['kode'], $result['message']);
    }

    public function getOrderLab(Request $request)
    {
        $datas = DB::table('pelayananpasien_t as pp')
            ->leftjoin ('pelayananpasienpetugas_t as ppp', 'pp.norec', 'ppp.pelayananpasien')
            ->join ('pegawai_m as pg', 'pg.id', 'ppp.objectpegawaifk' )
            ->join('produk_m as prd', 'prd.id', 'pp.produkfk')
            ->join('strukorder_t as so', 'so.norec', '=', 'pp.strukorderfk')
            ->join('ruangan_m as ru', 'ru.id', '=', 'so.objectruangantujuanfk')
            ->select(
                'pp.tglpelayanan',
                'pp.hargasatuan',
                DB::raw("(pp.jumlah*pp.hargasatuan) as total"),
                'prd.namaproduk',
                'pp.jumlah',
                'pg.namalengkap',
                'so.noorder',
                'prd.id',
                'so.norec as norec_so',
                'ru.id as ruid'
                // 'pp.norec'
                )
            ->where('pp.strukorderfk', $request['norec_so'])
            ->where('pp.kdprofile', $this->kdProfile)
            ->where('pp.statusenabled', true)
            ->distinct()
            ->get();

        return $this->respond($datas);
    }

    public function chartOrderLabByRuangan(Request $request)
    {
        $dateBetween = [$request->tglAwal, $request->tglAkhir];
        $datas = DB::table('strukorder_t as so')
            ->join('ruangan_m as ru', 'ru.id', 'so.objectruanganfk')
            ->join('ruangan_m as ru2', 'ru2.id', 'so.objectruangantujuanfk')
            ->select('ru.namaruangan as namaruangan', DB::raw("count(so.objectruanganfk) as jumlah"))
            // ->where('so.objectruangantujuanfk', $this->settingFix('idDepartemenLab'))
            ->where('ru2.objectdepartemenfk', $this->settingFix('idDepartemenLab'))
            ->where('so.kdprofile', $this->kdProfile)
            ->whereBetween(DB::raw("CAST(so.tglorder AS DATE)"), $dateBetween)
            ->where('so.statusenabled', true)
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

    public function  getLabDetail(Request $r)
    {
        $now = $this->hari_ini(date('Y-m-d'));
        $kdProfile = $this->kdProfile;
        $dokter  = DB::table('jadwaldokter_m as jd')
            ->leftjoin('pegawai_m as pg', 'pg.id', '=', 'jd.objectpegawaifk')
            ->join('ruangan_m as ru', 'ru.id', '=', 'jd.objectruanganfk')
            ->select(
                'pg.namalengkap',
                'jd.jammulai',
                'jd.jamakhir',
                'ru.namaruangan',
                DB::raw("lower(jd.hari) as hari"),
            )
            ->where('jd.kdprofile', $this->kdProfile)
            ->whereIn('ru.objectdepartemenfk',  explode(',', $this->settingFix('idDepartemenLab')))
            ->where('jd.statusenabled', true)
            ->where('jd.hari', 'ilike', '%'.$now .'%');

        if (isset($r['ruanganid']) && $r['ruanganid'] != '') {
            $dokter = $dokter->where('ru.id', '=',  $r['ruanganid']);
        }
        if (isset($r['namadokter']) && $r['namadokter'] != '') {
            $dokter = $dokter->where('pg.namalengkap', 'ilike',  '%'.$r['namadokter'].'%');
        }
        if (isset($r['limit']) && $r['limit'] != '') {
            $dokter = $dokter->limit( $r['limit']);
        }
        $dokter->orderBy('jd.jammulai');
        $dokter =  $dokter->get();

        $produk  = DB::table('stokprodukdetail_t as spd')
        ->join('ruangan_m as ru', 'spd.objectruanganfk', '=', 'ru.id')
        ->join('produk_m as pr', 'spd.objectprodukfk', '=', 'pr.id')
        ->join('asalproduk_m as ap', 'spd.objectasalprodukfk', '=', 'ap.id')
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
        ->whereIn('ru.objectdepartemenfk',  explode(',', $this->settingFix('idDepartemenLab')))
        ->where('spd.statusenabled', true);

    if (isset($r['ruanganid']) && $r['ruanganid'] != '') {
       $produk->where('ru.id', '=',  $r['ruanganid']);
    }
    if (isset($r['nama']) && $r['nama'] != '') {
        $produk->where('pr.namaproduk', 'ilike',  '%'.$r['nama'].'%');
    }
    if (isset($r['limit']) && $r['limit'] != '') {
       $produk->limit( $r['limit']);
    }
    $produk->groupBy('ru.id', 'ru.namaruangan',  'pr.namaproduk',  'ap.asalproduk',  'spd.harganetto1', 'spd.harganetto2');
    $produk->orderBy('pr.namaproduk');
    $produk =  $produk->get();


    $res['dokter'] = $dokter;
    $res['produk'] = $produk;
    return $this->respond($res);
}

    // Penunjang

    public function getPenunjangPasien(Request $request)
    {
        $idProfile = $this->kdProfile;
        $dateBetween = [$request->tglAwal, $request->tglAkhir];
        $data = DB::table('antrianpasiendiperiksa_t as apd')
            ->join('pasiendaftar_t as pd', 'pd.norec', '=', 'apd.noregistrasifk')
            ->JOIN('ruangan_m as ru', 'ru.id', '=', 'apd.objectruanganfk')
            ->Join('departemen_m as dept', 'dept.id', '=', 'ru.objectdepartemenfk')
            ->JOIN('pasien_m as ps', 'ps.id', '=', 'pd.nocmfk')
            ->leftjoin('aksesemr_t as emr', 'emr.pasienfk', '=', 'ps.id')
            ->leftJoin('jeniskelamin_m as jk', 'jk.id', '=', 'ps.objectjeniskelaminfk')
            ->leftJoin('kelompokpasien_m as kp', 'kp.id', '=', 'pd.objectkelompokpasienlastfk')
            ->leftJoin('rekanan_m as rk', 'rk.id', '=', 'pd.objectrekananfk')
            ->leftJoin('kelas_m as kl', 'kl.id', '=', 'pd.objectkelasfk')
            ->leftJoin('strukpelayanan_t as sp', 'sp.norec', '=', 'pd.nostruklastfk')
            ->leftJoin('alamat_m as alm', 'alm.nocmfk', '=', 'ps.id')
            ->leftJoin('golongandarah_m as gol', 'gol.id', '=', 'ps.objectgolongandarahfk')
            ->leftjoin('strukorder_t as so', 'so.norec_apd', '=', 'apd.norec')
            ->leftJoin('ruangan_m as ru1', 'ru1.id', '=', 'so.objectruanganfk')
            ->leftJoin('kebangsaan_m as kbs', 'kbs.id', '=', 'ps.objectkebangsaanfk')
            ->select(
                'apd.norec as norec_apd',
                'ru.id as ruid',
                'ru.namaruangan',
                'pd.noregistrasi',
                'ps.nocm',
                'ps.nobpjs',
                'ps.nohp',
                'ps.noidentitas',
                'pd.nocmfk',
                'ps.namapasien',
                'jk.jeniskelamin',
                'ps.objectjeniskelaminfk',
                'ps.objectgolongandarahfk',
                'emr.tglberakhir',
                'emr.tglmulai',
                'emr.objectkelompokuserfk',
                'emr.pegawaipemohonfk',
                'kp.kelompokpasien',
                'rk.namarekanan',
                'kl.namakelas',
                'kl.id as klid',
                'pd.tglregistrasi',
                'pd.tglpulang',
                'ps.tgllahir',
                // 'apd.norec',
                'pd.norec as norec_pd',
                'so.norec as norec_so',
                'so.noorder',
                'sp.tglstruk',
                'pd.nostruklastfk',
                'alm.alamatlengkap',
                'gol.golongandarah',
                'apd.tglmasuk',
                'ru1.namaruangan as ruanganasal',
                'kbs.name as kebangsaan',
                DB::raw(
                    "'' AS expertise,so.catatanklinis,'' as kddiagnosa,
                CASE WHEN sp.nosbmlastfk IS NULL THEN 'Belum Bayar' ELSE 'Lunas' END AS status
                "
                )
            )
            ->where('apd.kdprofile', $idProfile)
            ->where('apd.statusenabled','=','true')
            ->whereBetween(DB::raw("CAST(apd.updated_at as DATE)"), $dateBetween)
            ->where('ru.objectdepartemenfk', $this->settingFix('idDepartemenLab'))
            // ->whereBetween(DB::raw("CAST(apd.tglmasuk as DATE)"),$dateRange)
            ->orderBy('apd.tglregistrasi', 'desc');

        if (isset($request['ruanganid']) && $request['ruanganid'] != '') {
            $data = $data->where('ru.id', '=',  $request['ruanganid']);
        }
        if (isset($request['ruanganAsal_id']) && $request['ruanganAsal_id'] != '') {
            $data = $data->where('ru1.id', '=',  $request['ruanganAsal_id']);
        }
        if(isset($request['statusorder']) && $request['statusorder'] == 0){
            $data=$data->whereNull('so.norec');
        }
        if(isset($request['statusorder']) && $request['statusorder'] == 1){
            $data=$data->whereNotNull('so.norec');
        }
        if (isset($request['qnama']) && $request['qnama'] != '') {
            $data = $data->where('ps.namapasien', 'ilike', '%' . $request['qnama'] . '%');
        }
        $total = $data->count();
        if (isset($request['limit']) && $request['limit'] != '') {
            $data = $data->limit($request['limit']);
        }
        if (isset($request['offset']) && $request['offset'] != '') {
            $data = $data->offset($request['offset']);
        }
        if (isset($request['search']) && $request['search'] != '') {
            $searchTerm = '%' . $request['search'] . '%';
            $data = $data->where(function ($query) use ($searchTerm) {
                $query->where('ps.namapasien', 'ilike', $searchTerm)
                      ->orWhere('pd.noregistrasi', 'ilike', $searchTerm)
                      ->orWhere('ps.nocm', 'ilike', $searchTerm)
                      ->orWhere('ps.nobpjs', 'ilike', $searchTerm)
                      ->orWhere('ps.noidentitas', 'ilike', $searchTerm)
                      ->orWhere('so.noorder', 'ilike', $searchTerm);
            });
        }
        if (isset($request['qnocm']) && $request['qnocm'] != '') {
            $data = $data->where('ps.nocm', 'ilike', '%' . $request['qnocm'] . '%');
        }
        if (isset($request['qnoregistrasi']) && $request['qnoregistrasi'] != '') {
            $data = $data->where('pd.noregistrasi', 'ilike', '%' . $request['qnoregistrasi'] . '%');
        }

        $data = $data->get();
        $apdnorec = [];
        foreach ($data as $key => $v) {
            $norecapd = $v->norec_apd;
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
                if ($data[$i]->norec_apd ==  $v2->norec_apd) {
                    $data[$i]->expertise = true;
                    $data[$i]->tglexpertise = $v2->tanggal;
                }
            }
            $i = $i + 1;
        }
        $result = array(
            "data" => $data,
            "as" => '@epic',
            "total" =>$total
        );
        return $this->respond($result);
    }


    public function HeaderPasienLab(Request $r)
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
                'ps.objectkebangsaanfk',
                'kp.kelompokpasien'
            )
            ->where('ps.kdprofile', (int)$this->kdProfile)
            ->where('ps.statusenabled', true)
            ->where('ps.id', $r['nocmfk'])
            ->first();
        if (!empty($data)) {
            $data->umur =  $this->getAge($data->tgllahir, date('Y-m-d H:i:s'));
        }
        $registrasi =   DB::table('pasiendaftar_t as pd')
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
            ->where('pd.kdprofile', (int)$this->kdProfile)
            // ->where('pd.statusenabled', true)
            ->where('apd.statusenabled', true)
            ->where('pd.nocmfk', $r['nocmfk'])
            ->where('pd.norec', $r['norec_pd'])
            ->get();
        $last = array();
        $tgl = date('2000-01-01 00:00');
        foreach ($registrasi as $d) {
            if (isset($r['norec_apd']) && $r['norec_apd'] != '' && $r['norec_apd'] == $d->norec_apd) {
                $last  = $d;
                break;
            } else {
                if ($d->objectruanganlastfk == $d->objectruanganfk && $tgl < $d->tglmasuk) {
                    $tgl = $d->tglmasuk;
                    $last  = $d;
                }
            }
        }
        $result['pasien'] = $data;
        $result['registrasi'] = $registrasi;
        $result['last_registrasi'] = $last;
        $result['as'] = '@epic';

        return $this->respond($result);
    }


    public function RiwayatPelayanan(Request $request)
    {
        $idProfile = $this->kdProfile;
        $sDokterPemeriksa = $this->settingFix('jenisPetugasDokterPemeriksa');
        $pelayanan = DB::table('pelayananpasien_t as pp')
            ->JOIN('antrianpasiendiperiksa_t as apd', 'apd.norec', '=', 'pp.noregistrasifk')
            ->JOIN('pasiendaftar_t as pd', 'pd.norec', '=', 'apd.noregistrasifk')
            ->JOIN('pasien_m as ps', 'ps.id', '=', 'pd.nocmfk')
            ->JOIN('jeniskelamin_m as jk', 'jk.id', '=', 'ps.objectjeniskelaminfk')
            ->JOIN('produk_m as pr', 'pr.id', '=', 'pp.produkfk')
            ->JOIN('ruangan_m as ru', 'ru.id', '=', 'apd.objectruanganfk')
            ->JOIN('departemen_m as dp', 'dp.id', '=', 'ru.objectdepartemenfk')
            ->leftJOIN('strukpelayanan_t as sp', 'sp.norec', '=', 'pp.strukfk')
            ->leftJOIN('strukbuktipenerimaan_t as sbm', 'sp.nosbmlastfk', '=', 'sbm.norec')
            ->leftJOIN('strukorder_t as so', 'so.norec', '=', 'pp.strukorderfk')
            ->select(
                'ps.nocm',
                'pd.nocmfk',
                'ps.namapasien',
                'jk.jeniskelamin',
                'pp.tglpelayanan',
                'pp.produkfk',
                'pr.namaproduk',
                'pp.jumlah',
                'pp.hargasatuan',
                'pp.hargadiscount',
                'sp.nostruk',
                'pd.noregistrasi',
                'ru.namaruangan',
                'dp.namadepartemen',
                'ps.id as psid',
                'pd.norec as norec_pd',
                'apd.norec as norec_apd',
                'sp.norec as norec_sp',
                'pp.norec as norec_pp',
                'ru.objectdepartemenfk',
                'so.noorder',
                'so.keteranganlainnya',
                'apd.objectruanganfk',
                'pp.iscito',
                'pp.jasa',
                'ps.objectjeniskelaminfk',
                'ps.tgllahir',
                'sbm.nosbm',
                'so.keteranganlainnya',

            )
            ->where('pp.kdprofile', $idProfile)
            ->where('pp.statusenabled', true)
            ->where('ru.objectdepartemenfk', $this->settingFix('idDepartemenLab'))
            ->orderBy('pp.tglpelayanan');

        if (isset($request['nocmfk']) && $request['nocmfk'] != "" && $request['nocmfk'] != "undefined") {
            $pelayanan = $pelayanan->where('pd.nocmfk', '=', $request['nocmfk']);
        }
        if (isset($request['norec_pd']) && $request['norec_pd'] != "" && $request['norec_pd'] != "undefined") {
            $pelayanan = $pelayanan->where('pd.norec', '=', $request['norec_pd']);
        }

        $pelayanan = $pelayanan->get();

        if (count($pelayanan) > 0) {
            $pelayananpetugas = DB::table('pelayananpasienpetugas_t as ptu')
                ->join('pegawai_m as pg', 'pg.id', '=', 'ptu.objectpegawaifk')
                ->select('ptu.pelayananpasien', 'pg.namalengkap')
                ->where('ptu.kdprofile', $idProfile)
                ->where('ptu.objectjenispetugaspefk', $sDokterPemeriksa)
                ->where('ptu.noregistrasi', $request['noregistrasi'])
                ->get();

            $result = [];
            foreach ($pelayanan as $item) {
                if (isset($request['norec_pd'])) {
                    $diskon = $item->hargadiscount;
                } else {
                    $diskon = 0;
                }
                $NamaDokter = '-';
                $DokterId = '';
                foreach ($pelayananpetugas as $hahaha) {
                    if ($hahaha->pelayananpasien == $item->norec_pp) {
                        $NamaDokter = $hahaha->namalengkap;
                        $DokterId = $hahaha->id;
                    }
                }
                $total = (((float)$item->hargasatuan - (float)$diskon) * (float)$item->jumlah) + (float)$item->jasa;
                $result[] = array(
                    'nocmfk' => $item->nocmfk,
                    'namapasien' => $item->namapasien,
                    'jeniskelamin' => $item->jeniskelamin,
                    'tglpelayanan' => $item->tglpelayanan,
                    'namaproduk' => $item->namaproduk,
                    'jumlah' => (float)$item->jumlah,
                    'hargasatuan' => (float)$item->hargasatuan,
                    'hargadiscount' => (float)$diskon,
                    'total' => (float)$total,
                    'nostruk' => $item->nostruk,
                    'noregistrasi' => $item->noregistrasi,
                    'ruangan' => $item->namaruangan,
                    'departemen' => $item->namadepartemen,
                    'norec_apd' => $item->norec_apd,
                    'norec_sp' => $item->norec_sp,
                    'norec_pp' => $item->norec_pp,
                    'dokter' => $NamaDokter,
                    'dokterid' => $DokterId,
                    'noorder' => $item->noorder,
                    'iscito' => $item->iscito,
                    'jasa' => (float)$item->jasa,
                    'tgllahir' => $item->tgllahir,
                    'nosbm' => $item->nosbm,
                    'keteranganlainnya' => $item->keteranganlainnya
                );
            }
        } else {
            $result = [];
        }
        $dataTea = array(
            'data' => $result,
            'message' => '@epic'
        );
        return $this->respond($dataTea);
    }

    public function hapusPelayananTindakan(Request $r)
    {
        DB::beginTransaction();
        try {
            foreach ($r['data'] as $item) {
                PelayananPasienDetail::where('pelayananpasien', $item['norec_pp'])->where('kdprofile', $this->kdProfile)->delete();
                PelayananPasienPetugas::where('pelayananpasien', $item['norec_pp'])->where('kdprofile', $this->kdProfile)->delete();
                PelayananPasien::where('norec', $item['norec_pp'])->where('kdprofile', $this->kdProfile)->delete();

                $this->LOGGING(
                    'Hapus Tindakan',
                    $item['norec_pp'],
                    'pelayananpasien_t',
                    'Hapus Tindakan ' . ' pada Pasien ' .
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
                "result"  => null
            );
        }
        return $this->respond($result['result'], $result['status'], $transMessage);
    }

    // Petugas
    public function detailPetugasLab(Request $r)
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

    public function deleteJenisPetugasLab(Request $r)
    {
        DB::beginTransaction();
        try {
            PelayananPasienPetugas::where('norec', $r['norec_ppp'])->delete();
            $ps = PasienDaftar::detailPasien($r['noregistrasi']);
            $pg =  Pegawai::where('id', $r['objectpegawaifk'])->first();
            $this->LOGGING(
                'Hapus Petugas Tindakan',
                $r['norec_ppp'],
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
                "result"  => null
            );
        }
        return $this->respond($result['result'], $result['status'], $transMessage);
    }

    public function savePetugasPe(Request $r)
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

            $pg =  Pegawai::where('id', $r['objectpegawaifk'])->first();
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
                "result"  => $e->getMessage() . ' ' . $e->getLine()
            );
        }
        return $this->respond($result['result'], $result['status'], $transMessage);
    }

    // Update Jenis Kelamin
    public function UpdateJenisKelamin(Request $request)
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
                "result"  => null
            );
        }
        return $this->respond($result['result'], $result['status'], $result['message']);
    }
    // Update Golongan Darah
    public function UpdateGolonganDarah(Request $request)
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
                "result"  => $e->getMessage() . $e->getLine()
            );
        }
        return $this->respond($result['result'], $result['status'], $result['message']);
    }

    public function listHargaLayanan(Request $request, $lokal = false)
    {
        $idProfile = (int) $this->kdProfile;
        // $set = $this->settingFix('idPenjaminUmum');

        // if ($set == $request['idPenjamin']) {
            $request['idPenjamin'] = null;
        // }
        $sk =  DB::table('suratkeputusan_m')
            ->where('statusenabled', true)
            ->where('objectjeniskeputusanfk', $this->settingFix('jenisSK_TARIF'))->first();
        $skID = !empty($sk) ? $sk->id : 0;
        if (
            isset($request['idPenjamin']) && $request['idPenjamin'] != 'null' && $request['idPenjamin'] != ''
            && $request['idPenjamin'] != null
        ) {
            $data = DB::table('harganettoprodukbykelasd_m as hnp')
                ->join('mapruangantoproduk_m as mpr', 'mpr.objectprodukfk', '=', 'hnp.objectprodukfk')
                ->join('produk_m as prd', 'prd.id', '=', 'mpr.objectprodukfk')
                ->join('komponenharga_m as kh', 'kh.id', '=', 'hnp.objectkomponenhargafk')
                ->select('hnp.objectkomponenhargafk', 'kh.komponenharga', 'hnp.hargasatuan', 'mpr.objectprodukfk', 'kh.iscito', 'hnp.hargadijamin',
                    DB::raw("CASE WHEN hnp.hargadiscount IS NULL THEN 0 ELSE hnp.hargadiscount END AS diskon")
                )
                ->where('mpr.objectruanganfk', $request['idRuangan'])
                // ->where('hnp.objectkelasfk', $request['idKelas'])
                ->where('hnp.objectkelasfk', $this->settingFix('kdKelasLabRad'))
                ->where('mpr.objectprodukfk', $request['idProduk'])
                ->where('hnp.objectjenispelayananfk', $request['idJenisPelayanan'])
                ->where('hnp.objectpenjaminfk', $request['idPenjamin'])
                ->where('hnp.suratkeputusanfk', $skID)
                ->where('mpr.statusenabled', true)
                ->where('hnp.statusenabled', true)
                ->where('prd.statusenabled', true);
            $data = $data->distinct();
            $data = $data->get();
        } else {
            $data = [];
        }


        if (count($data) == 0) {
            $data = DB::table('harganettoprodukbykelasd_m as hnp')
                ->join('mapruangantoproduk_m as mpr', 'mpr.objectprodukfk', '=', 'hnp.objectprodukfk')
                ->join('produk_m as prd', 'prd.id', '=', 'mpr.objectprodukfk')
                ->join('komponenharga_m as kh', 'kh.id', '=', 'hnp.objectkomponenhargafk')
                ->select(
                    'hnp.objectkomponenhargafk',
                    'kh.komponenharga',
                    'hnp.hargasatuan',
                    'mpr.objectprodukfk',
                    'kh.iscito',
                    'hnp.hargadijamin',
                    DB::raw("CASE WHEN hnp.hargadiscount IS NULL THEN 0 ELSE hnp.hargadiscount END AS diskon")
                )
                ->where('mpr.objectruanganfk', $request['idRuangan'])
                // ->where('hnp.objectkelasfk', $request['idKelas'])
                ->where('hnp.objectkelasfk', $this->settingFix('kdKelasLabRad'))
                ->where('mpr.objectprodukfk', $request['idProduk'])
                ->where('hnp.objectjenispelayananfk', $request['idJenisPelayanan'])
                ->where('hnp.suratkeputusanfk', $skID)
                ->whereNull('hnp.objectpenjaminfk')
                ->where('mpr.statusenabled', true)
                ->where('hnp.statusenabled', true)
                ->where('prd.statusenabled', true);
            $data = $data->distinct();
            $data = $data->get();
        }

        if (
            isset($request['idPenjamin']) && $request['idPenjamin'] != 'null' && $request['idPenjamin'] != ''
            && $request['idPenjamin'] != null
        ) {
            $data2 = DB::table('harganettoprodukbykelas_m as hnp')
                ->join('mapruangantoproduk_m as mpr', 'mpr.objectprodukfk', '=', 'hnp.objectprodukfk')
                ->join('produk_m as prd', 'prd.id', '=', 'mpr.objectprodukfk')
                ->join('suratkeputusan_m as sk', 'hnp.suratkeputusanfk', '=', 'sk.id')
                ->select(
                    'hnp.hargasatuan',
                    'hnp.hargadijamin',
                    DB::raw("CASE WHEN hnp.hargadiscount IS NULL THEN 0 ELSE hnp.hargadiscount END AS diskon")
                )
                ->where('mpr.objectruanganfk', $request['idRuangan'])
                // ->where('hnp.objectkelasfk', $request['idKelas'])
                ->where('hnp.objectkelasfk', $this->settingFix('kdKelasLabRad'))
                ->where('mpr.objectprodukfk', $request['idProduk'])
                ->where('hnp.objectjenispelayananfk', $request['idJenisPelayanan'])
                ->where('hnp.objectpenjaminfk', $request['idPenjamin'])
                ->where('hnp.suratkeputusanfk', $skID)
                ->where('hnp.statusenabled', true)
                ->where('sk.statusenabled', true)
                ->where('mpr.statusenabled', true)
                ->where('hnp.kdprofile', $idProfile)
                ->where('prd.statusenabled', true)
                ->distinct()
                ->first();
        } else {
            $data2 = null;
        }

        if (empty($data2)) {

            $data2 = DB::table('harganettoprodukbykelas_m as hnp')
                ->join('mapruangantoproduk_m as mpr', 'mpr.objectprodukfk', '=', 'hnp.objectprodukfk')
                ->join('produk_m as prd', 'prd.id', '=', 'mpr.objectprodukfk')
                ->join('suratkeputusan_m as sk', 'hnp.suratkeputusanfk', '=', 'sk.id')
                ->select(
                    'hnp.hargasatuan',
                    'hnp.hargadijamin',
                    DB::raw("CASE WHEN hnp.hargadiscount IS NULL THEN 0 ELSE hnp.hargadiscount END AS diskon")
                )
                ->where('mpr.objectruanganfk', $request['idRuangan'])
                // ->where('hnp.objectkelasfk', $request['idKelas'])
                ->where('hnp.objectkelasfk', $this->settingFix('kdKelasLabRad'))
                ->where('mpr.objectprodukfk', $request['idProduk'])
                ->where('hnp.suratkeputusanfk', $skID)
                ->where('hnp.objectjenispelayananfk', $request['idJenisPelayanan'])
                ->whereNull('hnp.objectpenjaminfk')
                ->where('hnp.statusenabled', true)
                ->where('sk.statusenabled', true)
                ->where('mpr.statusenabled', true)
                ->where('hnp.kdprofile', $idProfile)
                ->where('prd.statusenabled', true)
                ->distinct()
                ->first();
            $istarifpenjamin = false;
        }

        $result = array(
            'komponen' => $data,
            'harga' => $data2,
            'istarifpenjamin' => $istarifpenjamin,
            'as' => '@epic',
        );
        if($lokal){
            return $result;
        }

        return $this->respond($result);
    }

    public function BatalVerifLab(Request $request) {
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
            $transMessage = "Batal Verif Berhasil";
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

    public function updatePegawaiOrderGeneral (Request $req){
        try {
            DB::beginTransaction();

            $target=StrukOrder::where('norec',$req['so_norec'])->update(
                [
                    "objectpegawaiorderfk" => $req['id_dokter']
                ]
            );

            if(!empty($target)){
                DB::commit();
                $res=array(
                    "code"=>200,
                    "message"=>'update Success',
                    "rowAffected"=>$target,
                );
            }
        } catch (Exception $e) {
            DB::rollBack();
            $res=array(
                "code"=>500,
                "message"=>'Data Not update',
                "hint"=>'message '.$e->getMessage().' * '.$e->getLine()
            );
        }

        return $this->respond($res);
    }

}
