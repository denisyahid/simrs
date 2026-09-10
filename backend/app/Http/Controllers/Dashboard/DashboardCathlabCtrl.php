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
use App\Models\Transaksi\RisOrder;
use App\Models\Transaksi\StrukOrder;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Mockery\Exception\InvalidOrderException;
use App\Traits\Valet;

class DashboardCathlabCtrl extends Controller
{
    use Valet;
    public function __construct()
    {
        parent::__construct($is_encrypt = true);
    }

    public function getStrukOrderCathlab(Request $request)
    {
        $dateBetween = [$request->tglAwal, $request->tglAkhir];
        $dataOrder = DB::table('strukorder_t as so')
            ->join('pasiendaftar_t as pd', 'pd.norec', 'so.noregistrasifk')
            // ->join('antrianpasiendiperiksa_t as apd', 'apd.noregistrasifk','pd.norec')
            ->Join('pasien_m as pas', 'pd.nocmfk', 'pas.id')
            ->leftjoin('ruangan_m as ruAs', 'so.objectruanganfk', 'ruAs.id')
            ->join('ruangan_m as ruTu', 'so.objectruangantujuanfk', 'ruTu.id')
            ->join('pegawai_m as peg', 'peg.id', 'so.objectpegawaiorderfk')
            ->join('jeniskelamin_m as jk', 'jk.id', 'pas.objectjeniskelaminfk')
            ->join('kelompokpasien_m as kp', 'kp.id', 'pd.objectkelompokpasienlastfk')
            ->join('departemen_m as dep', 'dep.id', 'ruAs.objectdepartemenfk')
            ->join('departemen_m as dep2', 'dep2.id', 'ruTu.objectdepartemenfk')
            ->join('kelas_m as kls', 'kls.id', 'pd.objectkelasfk')
            // ->leftJoin('diagnosapasien_t as dp','dp.noregistrasifk', 'apd.norec')
            ->select(
                'so.norec',
                'pd.noregistrasi',
                'pd.norec as pd_norec',
                // 'apd.noregistrasifk',
                // 'apd.norec as apd_norec',
                // 'dp.ketdiagnosis',
                // 'dp.norec as dp_norec',
                'so.noorder',
                'pas.objectkebangsaanfk',
                'so.norec_apd',
                'so.statusorder',
                'pd.jenispelayanan as jenispelayananfk',
                'pd.tglregistrasi',
                'pas.namapasien',
                'pas.tgllahir',
                'pas.nobpjs',
                'pas.id as nocmfk',
                'pas.nocm',
                'pas.objectjeniskelaminfk',
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
                'dep2.namadepartemen as departementujuan',
                'peg.namalengkap'
            )
            ->whereBetween(DB::raw("CAST(so.tglorder AS DATE)"), $dateBetween)
            ->where('so.kdprofile', $this->kdProfile)
            ->whereIn('dep2.id', explode(',', $this->settingFix('KdDepartemenCathlab')))
            ->where('so.statusenabled', true);

        if (isset($request['statusorder']) && $request['statusorder'] != '') {
            $dataOrder = $dataOrder->where('so.statusorder', '=', $request['statusorder']);
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
        $dataOrder = $dataOrder->get();

        $result = [];

        $detail = DB::table('detaildiagnosapasien_t as ddp')
            ->join('diagnosa_m as dg', 'dg.id', '=', 'ddp.objectdiagnosafk')
            ->Join('jenisdiagnosa_m as jd', 'jd.id', '=', 'ddp.objectjenisdiagnosafk')
            ->join('pasiendaftar_t as pd', 'pd.noregistrasi', 'ddp.noregistrasi')
            ->Join('pasien_m as pas', 'pd.nocmfk', 'pas.id')
            ->select('dg.kddiagnosa', 'dg.namadiagnosa', 'jd.jenisdiagnosa', 'pd.noregistrasi')
            // ->where('ddp.objectdiagnosapasienfk', $datas->dp_norec)
            ->where('ddp.kdprofile', $this->kdProfile)
            ->where('ddp.statusenabled', true);
        if (isset($request['qnamapasien']) && $request['qnamapasien'] != '') {
            $detail = $detail->where('pas.namapasien', '=', $request['qnamapasien']);
        }
        if (isset($request['qnoregistrasi']) && $request['qnoregistrasi'] != '') {
            $detail = $detail->where('pd.noregistrasi', '=', $request['qnoregistrasi']);
        }
        if (isset($request['qnocm']) && $request['qnocm'] != '') {
            $detail = $detail->where('pas.nocm', '=', $request['qnocm']);
        }
        $detail = $detail->get();

        foreach ($dataOrder as $datas) {
            $detailDiagnosa = [];
            foreach ($detail as $di) {
                if ($datas->noregistrasi == $di->noregistrasi) {
                    $detailDiagnosa[] = $di;
                }
            }

            $result[] = [
                'namapasien' => $datas->namapasien,
                'noregistrasi' => $datas->noregistrasi,
                'norec_apd' => $datas->norec_apd,
                'so_norec' => $datas->norec,
                'nocm' => $datas->nocm,
                'noidentitas' => $datas->noidentitas,
                'nocmfk' => $datas->nocmfk,
                'pd_norec' => $datas->pd_norec,
                'jenispelayananfk' => $datas->jenispelayananfk,
                'noorder' => $datas->noorder,
                'tglregistrasi' => $datas->tglregistrasi,
                'pas_nocm' => $datas->nocm,
                'statusorder' => $datas->statusorder,
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
                'objectpegawaiorderfk' => $datas->objectpegawaiorderfk,
                'umur' => $this->getAge($datas->tgllahir, $datas->tglorder),
                'detailDiagnosa' => $detailDiagnosa
            ];
        }
        return $this->respond($result);
    }

    public function getOrderPelayanan(Request $request)
    {
        $so = StrukOrder::where('norec', $request['strukorderfk'])->where('kdprofile', $this->kdProfile)->first();
        $pasienDaftar = PasienDaftar::where('norec', $so->noregistrasifk)->where('kdprofile', $this->kdProfile)->first();
        $jp = (int)$pasienDaftar->jenispelayanan;
        $idpenjamin = '-1'; // $pasienDaftar->objectrekananfk == null ? '-1' : $pasienDaftar->objectrekananfk;
        $idKelas = $pasienDaftar->objectkelasfk;

        if ($idpenjamin != "-1") {
            $dataOrderPelayanan = DB::table('orderpelayanan_t as op')
                ->leftjoin('strukorder_t as so', 'so.norec', 'op.strukorderfk')
                ->leftjoin('produk_m as pr', 'pr.id', 'op.objectprodukfk')
                ->leftjoin('harganettoprodukbykelas_m as hnp', function ($join) {
                    $join->on('pr.id', '=', 'hnp.objectprodukfk')->on('pr.kdprofile', '=', 'hnp.kdprofile')
                        ->where('hnp.statusenabled', true);
                })
                ->leftjoin('kelas_m as kls', 'kls.id', 'hnp.objectkelasfk')
                ->leftjoin('ruangan_m as ru', 'ru.id', 'so.objectruangantujuanfk')
                ->leftjoin('departemen_m as dpm', 'dpm.id', 'ru.objectdepartemenfk')
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
                ->where('hnp.objectkelasfk', $idKelas)
                ->where('hnp.objectjenispelayananfk', $jp)
                ->where('hnp.objectpenjaminfk', $idpenjamin)
                ->get();
        } else {
            $dataOrderPelayanan = [];
        }
        if (count($dataOrderPelayanan) == 0) {
            // return $this->respond('cek');
            $dataOrderPelayanan = DB::table('orderpelayanan_t as op')
                ->leftjoin('strukorder_t as so', 'so.norec', 'op.strukorderfk')
                ->leftjoin('produk_m as pr', 'pr.id', 'op.objectprodukfk')
                ->leftjoin('harganettoprodukbykelas_m as hnp', 'pr.id', 'hnp.objectprodukfk')
                ->leftjoin('kelas_m as kls', 'kls.id', 'hnp.objectkelasfk')
                ->leftjoin('ruangan_m as ru', 'ru.id', 'so.objectruangantujuanfk')
                ->leftjoin('departemen_m as dpm', 'dpm.id', 'ru.objectdepartemenfk')
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
                ->where('hnp.objectkelasfk', $idKelas)
                ->where('hnp.statusenabled', true)
                ->where('hnp.objectpenjaminfk', null)
                ->where('op.strukorderfk', $request['strukorderfk'])
                ->where('hnp.objectjenispelayananfk', $jp)
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

    public function getKomponenHarga(Request $request)
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

    public function getPelayanan(Request $request)
    {
        $datas = DB::table('mapruangantoproduk_m as mpr')
            ->join('harganettoprodukbykelas_m as hnp', function ($join) {
                $join->on('hnp.objectprodukfk', '=', 'mpr.objectprodukfk')
                    ->on('hnp.kdprofile', '=', 'mpr.kdprofile')
                    ->where('hnp.statusenabled', true);
            })
            ->join('produk_m as prd', function ($join) {
                $join->on('prd.id', '=', 'mpr.objectprodukfk')->on('prd.kdprofile', '=', 'mpr.kdprofile');
            })
            // ->join ('kelas_m as kls','kls.id','hnp.objectkelasfk')
            ->join('ruangan_m as ru', function ($join) {
                $join->on('ru.id', '=', 'mpr.objectruanganfk')->on('ru.kdprofile', '=', 'mpr.kdprofile');
            })
            ->join('departemen_m as dept', 'dept.id', 'ru.objectdepartemenfk')
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
            ->whereIn('dept.id', explode(',', $this->settingFix('KdDepartemenCathlab')))
            ->where('mpr.statusenabled', true);

        if ($request->idProduk) {
            $datas = $datas->where('mpr.objectprodukfk', $request['idProduk']);
        }
        $datas = $datas->get();

        return $this->respond($datas);
    }

    public function getDataDokter()
    {
        $set = explode(',', $this->settingFix('KdDepartemenCathlab'));
        $res['ruangan'] = Ruangan::mine()->whereIn('objectdepartemenfk', $set)->get();
        $dataDokter = DB::table('pegawai_m as ru')
            ->select('id', 'namalengkap')
            ->where('ru.kdprofile', $this->kdProfile)
            ->where('ru.statusenabled', true)
            ->where('ru.objectjenispegawaifk', $this->settingFix('idJenisPegawaiDokter'))
            ->orderBy('ru.namalengkap')
            ->get();
        $res['data'] = $dataDokter;
        $res['jeniskelamin'] = JenisKelamin::mine()->get();
        $res['golongandarah'] = GolonganDarah::mine()->get();
        return $this->respond($res);
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
            $dataAPD->tglregistrasi = $parameter['tglregistrasi'];
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
            $test = StrukOrder::where('norec', $parameter['so_norec'])
                ->where('kdprofile', $this->kdProfile)
                ->update([
                    'statusorder' => $parameter['statusoperasi'] == 3 ? 2 : 1,
                    'norec_apd' => $dataAPDnorec,
                    'durasi' => $parameter['estimasiwaktuoperasi'],
                    'objectkamaroperasifk' => $parameter['kamaroperasifk'],
                    'dokteroperatorfk' => $parameter['dokteroperatorfk'],
                    'dokteranastesifk' => $parameter['dokteranastesifk'],
                    // 'tglselesai' => $parameter['tglselesai'],
                    'tgloperasi' => $parameter['tgloperasi'],
                    'objectpegawaiorderfk' => $parameter['objectpegawaiorderfk'],
                    'dokteranakfk' => $parameter['dokteranakfk'],
                    'penerimafk' => $parameter['penerimafk'],
                    'statusoperasi' => $parameter['statusoperasi'],
                    'anastesitambahanfk' => $parameter['arranastesi'],
                    'operatorhelperfk' => $parameter['arroperator']
                ]);

            // Loop for processing the dataOrder if necessary
            // foreach ($dataOrder as $data) {
            //     Add logic for processing $data
            // }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
        $result = array(
            'data' => empty($test) ? $test : null
        );
        return $this->respond($result);
    }


    // public function savePelayananPasien(Request $request)
    // {
    //     $parameter = $request['parameter'];
    //     $dataOrder = $request['data'];
    //     DB::beginTransaction();
    //     $apd = AntrianPasienDiperiksa::where('noregistrasifk', $parameter['pd_norec'])
    //         ->where('kdprofile', $this->kdProfile)
    //         ->where('objectruanganfk', $parameter['idruangtujuan'])
    //         ->where('statusenabled', true)
    //         ->first();
    //     $getLastAntrian = AntrianPasienDiperiksa::where('objectruanganfk', $parameter['idruangtujuan'])
    //         ->where('tglregistrasi', '>=', date('Y-m-d', strtotime($parameter['tglregistrasi'])) . ' 00:00')
    //         ->where('tglregistrasi', '<=', date('Y-m-d', strtotime($parameter['tglregistrasi'])) . ' 23:59')
    //         ->where('statusenabled', true)
    //         ->max('noantrian');
    //     if (!$apd) {

    //         $dataAPD = new AntrianPasienDiperiksa;
    //         $dataAPD->norec = $dataAPD->generateNewId();
    //         $dataAPD->kdprofile = $this->kdProfile;;
    //         $dataAPD->objectasalrujukanfk = 1;
    //         $dataAPD->statusenabled = true;
    //         $dataAPD->objectkelasfk = $parameter['objectkelasfk'];
    //         $dataAPD->noantrian = $getLastAntrian + 1;
    //         $dataAPD->noregistrasifk = $parameter['pd_norec'];
    //         $dataAPD->objectpegawaifk = $parameter['objectpegawaiorderfk'];
    //         $dataAPD->objectruanganfk = $parameter['idruangtujuan'];
    //         $dataAPD->statusantrian = 0;
    //         $dataAPD->statuspasien = 1;
    //         $dataAPD->status = "Belum Dipanggil";
    //         $dataAPD->objectstrukorderfk = $parameter['so_norec'];
    //         $dataAPD->tglregistrasi = $parameter['tglregistrasi']; // date('Y-m-d H:i:s');
    //         $dataAPD->tglmasuk = date('Y-m-d H:i:s');
    //         $dataAPD->tglkeluar = date('Y-m-d H:i:s');
    //         $dataAPD->noregistrasi =  $parameter['noregistrasi'];
    //         $dataAPD->save();
    //         $dataAPDnorec = $dataAPD->norec;
    //         $dataAPDtglPel = $dataAPD->tglregistrasi;
    //     } else {
    //         $dataAPDnorec = $apd->norec;
    //         $dataAPDtglPel = $apd->tglregistrasi;
    //     }

    //     try {

    //         StrukOrder::where('norec', $parameter['so_norec'])
    //             ->where('kdprofile', $this->kdProfile)
    //             ->update(
    //                 [
    //                     'statusorder' => $parameter['statusoperasi'] == 3 ? 2 : 1,
    //                     'norec_apd' => $dataAPDnorec,
    //                     'durasi' => $parameter['estimasiwaktuoperasi'],
    //                     'objectkamaroperasifk' => $parameter['kamaroperasifk'],
    //                     // 'jenisoperasifk' => $parameter['jenisoperasifk'],
    //                     'dokteroperatorfk' => $parameter['dokteroperatorfk'],
    //                     'dokteranastesifk' => $parameter['dokteranastesifk'],
    //                     'tglselesai' => $parameter['tglselesai'],
    //                     'tgloperasi' => $parameter['tgloperasi'],
    //                     'objectpegawaiorderfk' => $parameter['objectpegawaiorderfk'],
    //                     'dokteranakfk' => $parameter['dokteranakfk'],
    //                     'penerimafk' => $parameter['penerimafk'],
    //                     'statusoperasi' => $parameter['statusoperasi'],
    //                     'anastesitambahanfk' => $parameter['arranastesi'],
    //                     'operatorhelperfk' => $parameter['arroperator']
    //                 ]
    //             );

    //         DB::commit();
    //         $result = [
    //             'message' => 'Data Berhasil disimpan',
    //             'parameter' => $parameter,
    //             //'pelPasien' => $dataPelayanan,
    //             // 'petugaspelayanan' => $PelPasienPetugas,
    //             //'detailPelayanan' => $PelPasienDetail,
    //             'kode' => 200
    //         ];
    //     } catch (InvalidOrderException  $e) {
    //         DB::rollBack();

    //         $result = [
    //             'message' => 'Data Gagal Disimpan, Silakan Periksa Kembali Data',
    //             'status' => $e->getMessage(),
    //             'kode' => 400
    //         ];
    //     }
    //     return $this->respond($result, $result['kode'], $result['message']);
    // }
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
            ->join('departemen_m as dept', 'dept.id', 'ru.objectdepartemenfk')
            ->select('ru.namaruangan as namaruangan', DB::raw("count(so.objectruanganfk) as jumlah"))
            ->whereIn('dept.id', explode(',', $this->settingFix('KdDepartemenCathlab')))
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

    public function  getRadDetail(Request $r)
    {
        $kdProfile = $this->kdProfile;
        $dokter  = DB::table('jadwaldokter_m as jd')
            ->leftjoin('pegawai_m as pg', 'pg.id', '=', 'jd.objectpegawaifk')
            ->leftjoin('ruangan_m as ru', 'ru.id', '=', 'jd.objectruanganfk')
            ->leftjoin('departemen_m as dept', 'dept.id', '=', 'ru.objectdepartemenfk')
            ->select(
                'pg.namalengkap',
                'jd.jammulai',
                'jd.jamakhir',
                'ru.namaruangan',
                DB::raw("lower(jd.hari) as hari"),
            )
            ->where('jd.kdprofile', $this->kdProfile)
            ->whereIn('dept.id', explode(',', $this->settingFix('KdDepartemenCathlab')))
            ->where('jd.statusenabled', true);

        if (isset($r['ruanganid']) && $r['ruanganid'] != '') {
            $dokter = $dokter->where('ru.id', '=',  $r['ruanganid']);
        }

        $dokter =  $dokter->get();

        $produk  = DB::table('stokprodukdetail_t as spd')
            ->join('ruangan_m as ru', 'spd.objectruanganfk', '=', 'ru.id')
            ->leftjoin('produk_m as pr', 'spd.objectprodukfk', '=', 'pr.id')
            ->leftjoin('asalproduk_m as ap', 'spd.objectasalprodukfk', '=', 'ap.id')
            ->join('departemen_m as dept', 'dept.id', '=', 'ru.objectdepartemenfk')
            ->select(
                'ru.id',
                'ru.namaruangan',
                'spd.qtyproduk',
                'pr.namaproduk',
                'ap.asalproduk',
                'spd.harganetto1',
                'spd.harganetto2',
            )
            ->where('spd.kdprofile', $this->kdProfile)
            ->whereIn('dept.id', explode(',', $this->settingFix('KdDepartemenCathlab')))
            ->where('spd.statusenabled', true);

        if (isset($r['ruanganid']) && $r['ruanganid'] != '') {
            $produk = $produk->where('ru.id', '=',  $r['ruanganid']);
        }

        $produk =  $produk->get();


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
            ->where('pd.statusenabled', true)
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

    public function getDaftarPasienPenunjang(Request $request)
    {
        $idProfile = $this->kdProfile;
        $dateBetween = [$request->tglAwal, $request->tglAkhir];
        $data = DB::table('antrianpasiendiperiksa_t as apd')
            ->join('pasiendaftar_t as pd', 'pd.norec', '=', 'apd.noregistrasifk')
            ->JOIN('ruangan_m as ru', 'ru.id', '=', 'apd.objectruanganfk')
            ->Join('departemen_m as dept', 'dept.id', '=', 'ru.objectdepartemenfk')
            ->JOIN('pasien_m as ps', 'ps.id', '=', 'pd.nocmfk')
            ->leftJoin('jeniskelamin_m as jk', 'jk.id', '=', 'ps.objectjeniskelaminfk')
            ->leftJoin('kelompokpasien_m as kp', 'kp.id', '=', 'pd.objectkelompokpasienlastfk')
            ->leftJoin('rekanan_m as rk', 'rk.id', '=', 'pd.objectrekananfk')
            ->leftJoin('kelas_m as kl', 'kl.id', '=', 'pd.objectkelasfk')
            ->leftJoin('strukpelayanan_t as sp', 'sp.norec', '=', 'pd.nostruklastfk')
            ->leftJoin('alamat_m as alm', 'alm.nocmfk', '=', 'ps.id')
            ->leftJoin('golongandarah_m as gol', 'gol.id', '=', 'ps.objectgolongandarahfk')
            ->leftjoin('strukorder_t as so', 'so.norec', '=', 'apd.objectstrukorderfk')
            ->leftJoin('ruangan_m as ru1', 'ru1.id', '=', 'so.objectruanganfk')
            ->select(
                'apd.norec as norec_apd',
                'ru.id as ruid',
                'ru.namaruangan',
                'pd.noregistrasi',
                'ps.nocm',
                'ps.noidentitas',
                'pd.nocmfk',
                'ps.namapasien',
                'jk.jeniskelamin',
                'ps.objectjeniskelaminfk',
                'ps.objectgolongandarahfk',
                'kp.kelompokpasien',
                'rk.namarekanan',
                'kl.namakelas',
                'kl.id as klid',
                'pd.tglregistrasi',
                'pd.tglpulang',
                'ps.tgllahir',
                'ps.nobpjs',
                'apd.norec',
                'pd.norec as norec_pd',
                'sp.tglstruk',
                'pd.nostruklastfk',
                'alm.alamatlengkap',
                'gol.golongandarah',
                'apd.tglmasuk',
                'ru1.namaruangan as ruanganasal',
                DB::raw("'' AS expertise,so.catatanklinis,'' as kddiagnosa
                ")
            )
            ->where('apd.kdprofile', $idProfile)
            ->whereBetween(DB::raw("CAST(apd.tglmasuk as DATE)"), $dateBetween)
            ->whereIn('dept.id', explode(',', $this->settingFix('KdDepartemenCathlab')))
            ->orderBy('apd.tglregistrasi', 'desc');

        if (isset($request['ruanganid']) && $request['ruanganid'] != '') {
            $data = $data->where('ru.id', '=',  $request['ruanganid']);
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
        if (isset($request['qnama']) && $request['qnama'] != '') {
            $data = $data->where('ps.namapasien', 'ilike', '%' . $request['qnama'] . '%');
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
                "result"  => null
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
                "result"  => $e->getMessage() . $e->getLine()
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
            $pg =  Pegawai::where('id', $r['objectpegawaifk'])->first();
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
                "result"  => $e->getMessage() . ' ' . $e->getLine()
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
}
