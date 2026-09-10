<?php

namespace App\Http\Controllers\Ambulan;

use App\Http\Controllers\Controller;
use App\Models\Master\HubunganKeluarga;
use App\Models\Master\JenisKelamin;
use App\Models\Master\Keperluan;
use App\Models\Master\Pasien;
use App\Models\Master\Pegawai;
use App\Models\Master\Profile;
use App\Models\Master\Ruangan;
use App\Models\Transaksi\AntrianPasienDiperiksa;
use App\Models\Transaksi\LoggingUser;
use App\Models\Transaksi\PasienDaftar;
use App\Models\Transaksi\PelayananPasien;
use App\Models\Transaksi\PelayananPasienDetail;
use App\Models\Transaksi\PelayananPasienPetugas;
use App\Models\Transaksi\PengambilanJenazah;
use App\Models\Transaksi\StrukOrder;
use App\Models\Transaksi\SuratKeterangan;
use App\Models\Transaksi\SuratPermohonanJenazah;
use App\Traits\Valet;
use Exception;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Mockery\Exception\InvalidOrderException;
use PhpParser\Node\Stmt\TryCatch;

class AmbulanCtrl extends Controller
{
    use Valet;
    public function __construct()
    {
        parent::__construct($is_encrypt = true);
    }
    public function ambulanDD(Request $r)
    {
        $set = explode(',', $this->settingFix('kdAmbulance'));
        $res['ruangan'] = Ruangan::mine()->whereIn('objectdepartemenfk', $set)->get();
        $res['hubungankeluarga'] = HubunganKeluarga::mine()->get();
        $res['jeniskelamin'] = JenisKelamin::mine()->get();
        $res['idJenisPegawaiDokter'] = explode(',', $this->settingFix('idJenisPegawaiDokter'));
        $res['keperluan'] = Keperluan::mine()->get();
        $res['namalengkap'] =
            Pegawai::mine()
            ->where('objectjenispegawaifk', $res['idJenisPegawaiDokter'])
            ->search($r['label'])
            ->paging($r['limit'])
            ->get();

        $res['cito'] = $this->settingFix('tarifCito');

        return $this->respond($res);
    }

    public function getOrderAmbulan(Request $request)
    {
        $dateBetween = [$request->tglAwal, $request->tglAkhir];
        $dataOrder = DB::table('strukorder_t as so')
            ->join('pasiendaftar_t as pd', 'pd.norec', 'so.noregistrasifk')
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
            ->select(
                'so.norec',
                'ruTu.id',
                'pd.noregistrasi',
                'pd.norec as pd_norec',
                'so.noorder',
                'so.statusorder',
                'pd.jenispelayanan as jenispelayananfk',
                'pd.tglregistrasi',
                'pas.namapasien',
                'pas.noidentitas',
                'pas.nobpjs',
                'pas.tgllahir',
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
                'so.cito',
                'so.catatanklinis',
                'so.keteranganlainnya',
                'dep2.namadepartemen as departementujuan',
                'peg.namalengkap',
                'pa.nosep',
                'so.namafile',
                'so.tglorder'

            )
            ->whereBetween(DB::raw("CAST(so.tglorder AS DATE)"), $dateBetween)
            ->where('so.kdprofile', $this->kdProfile)

            ->where('ruTu.objectdepartemenfk', $this->settingFix('kdAmbulance'))
            ->where('so.statusenabled', true);

        if (isset($request['statusorder']) && $request['statusorder'] != '') {
            $dataOrder = $dataOrder->where('so.statusorder', '=', $request['statusorder']);
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
                'pas_nocm' => $datas->nocm,
                'pas_noidentitas' => $datas->noidentitas,
                'no_bpjs' => $datas->nobpjs,
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
                'catatanklinis' => $datas->catatanklinis,
                'keteranganlainnya' => $datas->keteranganlainnya,
                'objectpegawaiorderfk' => $datas->objectpegawaiorderfk,
                'umur' => $this->getAge($datas->tgllahir, $datas->tglorder),
                'nosep' => $datas->nosep,
                'namafile' => $datas->namafile,
                'tgllahir' => $datas->tgllahir
                // 'detailDiagnosa' => $detail
            ];
        }
        $res['data'] = $result;
        $res['total'] = $total;
        return $this->respond($res);
    }

    public function detailOrderAmbulan(Request $request)
    {
        $so = StrukOrder::where('norec', $request['strukorderfk'])->where('kdprofile', $this->kdProfile)->first();
        $pasienDaftar = PasienDaftar::where('norec', $so->noregistrasifk)->where('kdprofile', $this->kdProfile)->first();
        $jp = (int)$pasienDaftar->jenispelayanan;
        $idpenjamin = -1; //$pasienDaftar->objectrekananfk == null ? '-1' : $pasienDaftar->objectrekananfk;
        if ($idpenjamin != "-1") {
            $dataOrderPelayanan = DB::table('orderpelayanan_t as op')
                ->leftjoin('strukorder_t as so', 'so.norec', 'op.strukorderfk')
                ->join('produk_m as pr', 'pr.id', 'op.objectprodukfk')
                ->leftjoin('harganettoprodukbykelas_m as hnp', function ($join) {
                    $join->on('pr.id', '=', 'hnp.objectprodukfk')
                        ->where('pr.kdprofile', $this->kdProfile)
                        ->where('hnp.statusenabled', true);
                })
                ->leftjoin('kelas_m as kls', 'kls.id', 'hnp.objectkelasfk')
                ->leftjoin('ruangan_m as ru', 'ru.id', 'so.objectruangantujuanfk')
                ->leftjoin('ruangan_m as ru1', 'ru1.id', 'so.objectruanganfk')
                ->leftjoin('departemen_m as dpm', 'dpm.id', 'ru.objectdepartemenfk')
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
                    so.catatanklinis,
                    hnp.hargasatuan ,
                    kls.namakelas,
                    dpm.namadepartemen,
                    pps.norec as norec_pp,
                    CASE WHEN hnp.hargadijamin IS NULL THEN 0 ELSE hnp.hargadijamin END AS hargadijamin"
                ))
                ->where('op.kdprofile', $this->kdProfile)
                ->where('op.strukorderfk', $request['strukorderfk'])
                ->where('hnp.objectkelasfk', $request['objectkelasfk'])
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
                ->leftjoin('ruangan_m as ru1', 'ru1.id', 'so.objectruanganfk')
                ->leftjoin('departemen_m as dpm', 'dpm.id', 'ru.objectdepartemenfk')
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
                so.catatanklinis,
                kls.namakelas,
                ru1.namaruangan as ruanganasal,
                dpm.namadepartemen,
                pps.norec as norec_pp,
                CASE WHEN hnp.hargadijamin IS NULL THEN 0 ELSE hnp.hargadijamin END AS hargadijamin"))
                ->where('op.kdprofile', $this->kdProfile)
                // ->where('hnp.objectkelasfk', $request['objectkelasfk'])
                ->where('hnp.objectkelasfk', $request['objectkelasfk'])
                ->where('hnp.statusenabled', true)
                ->whereNull('hnp.objectpenjaminfk')
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
                // ->where('hnp.objectkelasfk', $this->settingFix('kdKelasLabRad'))
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
                    // ->where('hnp.objectkelasfk', $this->settingFix('kdKelasLabRad'))
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

    public function getLayananAmbulance(Request $request)
    {
        $datas = DB::table('mapruangantoproduk_m as mpr')
            ->leftjoin('harganettoprodukbykelas_m as hnp', function ($join) {
                $join->on('hnp.objectprodukfk', 'mpr.objectprodukfk')
                    ->where('hnp.statusenabled', true);
            })
            ->leftjoin('produk_m as prd', 'prd.id', '=', 'mpr.objectprodukfk')
            ->leftjoin('kelas_m as kls', 'kls.id', 'hnp.objectkelasfk')
            ->leftjoin('ruangan_m as ru', 'ru.id', '=', 'mpr.objectruanganfk')
            ->leftjoin('departemen_m as dp', 'dp.id', '=', 'ru.objectdepartemenfk')
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
            ->where('mpr.objectruanganfk', $request['idruangan'])
            ->whereIN('ru.objectdepartemenfk', explode(',', $this->settingFix('kdAmbulance')))
            ->where('mpr.statusenabled', true)
            ->where('prd.statusenabled', true)
            ->where('kls.statusenabled', true);

        if ($request->idProduk) {
            $datas = $datas->where('mpr.objectprodukfk', $request['idProduk']);
        }
        $datas = $datas->get();

        return $this->respond($datas);
    }

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
            ->where('ru.objectdepartemenfk', explode(',', $this->settingFix('kdAmbulance')));

        $praktek =  $praktek->get();

        return $this->respond($praktek);
    }

    public function savePelayananAmbulan(Request $request)
    {
        $parameter = $request['parameter'];
        $dataOrder = $request['data'];


        DB::beginTransaction();
        try {
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
                $dataPelayanan->hargajual = $data['hargaLayanan'];
                $dataPelayanan->hargasatuan = $data['hargaLayanan'];
                $dataPelayanan->jumlah = $data['jumlah'];
                $dataPelayanan->kdkelompoktransaksi =  1;
                $dataPelayanan->piutangpenjamin = 0;
                $dataPelayanan->piutangrumahsakit = 0;
                $dataPelayanan->produkfk =  $data['idProduk'];
                $dataPelayanan->stock = 1;
                $dataPelayanan->strukorderfk =  $parameter['so_norec'];
                $dataPelayanan->tglpelayanan =  date('Y-m-d H:i:s');
                $dataPelayanan->harganetto = $data['hargaLayanan'];
                $dataPelayanan->noregistrasi =  $parameter['noregistrasi'];
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

                $PPnorec = $PelPasienPetugas->norec;
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

            DB::commit();
            $result = [
                'message' => 'Data Berhasil disimpan',
                'pelPasien' => $dataPelayanan,
                'petugaspelayanan' => $PelPasienPetugas,
                'detailPelayanan' => $PelPasienDetail,
                'kode' => 200
            ];
        } catch (Exception  $e) {
            DB::rollBack();

            $result = [
                'message' => 'Data Gagal Disimpan, Silakan Periksa Kembali Data',
                'status' => $e->getMessage(),
                'kode' => 400
            ];
        }
        return $this->respond($result, $result['kode'], $result['message']);
    }

    public function laporanOrderAmbulan(Request $request)
    {

        $dateRange = [$request->tglAwal, $request->tglAkhir];

        $data = DB::table('pelayananpasien_t as pp')
            ->leftjoin('produk_m as prd', 'prd.id', 'pp.produkfk')
            ->join('strukorder_t as so', 'so.norec', 'pp.strukorderfk')
            ->join('ruangan_m as ru', 'ru.id', 'so.objectruangantujuanfk')
            ->join('pasiendaftar_t as pd', 'pd.norec', 'so.noregistrasifk')
            ->join('pasien_m as ps', 'ps.id', 'pd.nocmfk')
            ->join('suratketerangan_t as sk', 'sk.strukorderfk', 'so.norec')
            ->join('keperluan_m as kep', 'kep.id', 'sk.keperluanfk')
            ->leftjoin('pegawai_m as per', 'per.id', 'sk.dokterfk')
            ->leftjoin('pegawai_m as peng', 'peng.id', 'sk.pegawaifk')
            ->leftjoin('strukpelayanan_t as sp', 'sp.norec', 'pp.strukfk')
            ->leftJoin('strukbuktipenerimaan_t as sbm', 'sbm.nostrukfk', 'sp.norec')
            // ->leftJoin('strukbuktipenerimaancarabayar_t as sbpc', 'sbpc.nosbmfk', 'sbm.norec')
            // ->leftJoin('carabayar_m as cb', 'cb.id', 'sbpc.objectcarabayarfk')
            // ->join('ruangan_m as ruas', 'ruas.id', 'so.objectruanganfk')
            ->select(
                'ps.namapasien',
                'ps.nocm',
                'pd.noregistrasi',
                'ru.namaruangan',
                'per.namalengkap as namaperawat',
                'peng.namalengkap as pengemudi',
                'sk.tujuan',
                'prd.namaproduk as zona',
                'pp.hargasatuan as biaya',
                'sk.nosint as nopolisi',
                'kep.keperluan',
                'pp.tglpelayanan',
                'so.tglorder',
                'so.statusorder',
                'pd.tglregistrasi',
                DB::raw(
                    "case when sp.nosbmlastfk is not null
                or sp.nosbklastfk is not null
                then 'Bayar' else '-' end
                as statusbayar",
                )
            )
            ->where('pp.kdprofile', $this->kdProfile)
            ->where('pp.statusenabled', true)
            ->where('so.objectkelompoktransaksifk', $this->kelompokTransaksi('PELAYANAN AMBULANCE'))
            ->whereBetween(DB::raw('so.tglorder::date'), $dateRange)
            ->get();

        return $this->respond($data);
    }

    public function DetailTindakanAmbulan(Request $r)
    {
        $kdProfile = (int)$this->kdProfile;
        $sDokterPemeriksa = $this->settingFix('jenisPetugasDokterPemeriksa');
        $pd = PasienDaftar::where('kdprofile', $kdProfile)
            ->where('norec', $r['norec_pd'])
            ->first();
        $data = DB::table('pelayananpasien_t as pp')
            ->join('antrianpasiendiperiksa_t as apd', 'apd.norec', '=', 'pp.noregistrasifk')
            ->join('kelas_m as kls', 'kls.id', '=', 'apd.objectkelasfk')
            ->join('produk_m as prd', 'prd.id', '=', 'pp.produkfk')
            ->join('ruangan_m as ru', 'ru.id', '=', 'apd.objectruanganfk')
            ->leftJOIN('strukpelayanan_t as sp', 'sp.norec', '=', 'pp.strukfk')
            ->leftJOIN('strukresep_t as sr', 'sr.norec', '=', 'pp.strukresepfk')
            ->leftJOIN('pegawai_m as pg', 'pg.id', '=', 'sr.penulisresepfk')
            ->leftJOIN('strukorder_t as so', 'so.norec', '=', 'pp.strukorderfk')
            // ->leftJOIN('order_bridge as lis', 'lis.order_number', '=', 'so.noorder')
            ->leftJOIN('order_lab as lis', function ($join) {
                $join->on('lis.no_lab', '=', 'so.noorder');
                $join->on(DB::raw('lis.kode_test::int'), '=', 'pp.produkfk');
            })
            ->select(
                'pp.norec',
                'apd.norec as norec_apd',
                'prd.namaproduk',
                'kls.namakelas',
                'pp.tglpelayanan',
                'ru.namaruangan',
                'pp.strukresepfk',
                'pp.jumlah',
                'pp.hargasatuan',
                'pg.namalengkap',
                'apd.norec as norec_apd',
                'so.noorder',
                'so.keteranganlainnya',
                'pp.strukfk',
                'pp.produkfk',
                'ru.objectdepartemenfk',
                'lis.no_lab as idbridging',
                'so.namafile',
                'so.norec as norec_so',
                DB::raw("
                case when pp.jasa is not null then pp.jasa else 0 end jasa,
                case when pp.hargadiscount is not null then pp.hargadiscount else 0 end hargadiscount,
                (
                    (pp.hargasatuan  - case when pp.hargadiscount is null then 0 else pp.hargadiscount end)
                     * pp.jumlah)
                + (case when pp.jasa is not null then pp.jasa else 0 end)
                 as total,
                to_char(pp.tglpelayanan,'yyyy-MM-dd')  as tglpelayanan_group ,
                case when pp.strukresepfk is null then 'Layanan' else 'Resep' end as jenis
               ")
            )
            ->where('pp.statusenabled', true)
            ->whereNull('pp.strukresepfk')
            ->where('pp.kdprofile', $kdProfile)
            ->where('ru.objectdepartemenfk', $this->settingFix('kdAmbulance'))
            ->where('apd.noregistrasifk', $r['norec_pd']);
        if (isset($r['strukfk']) && $r['strukfk'] != '' && $r['strukfk'] == null) {
            $data = $data->whereNull('pp.strukfk');
        }
        $data = $data->orderByDesc('pp.tglpelayanan');
        $data = $data->get();

        $pelayananpetugas = DB::table('pelayananpasienpetugas_t as ptu')
            ->leftjoin('pegawai_m as pg', 'pg.id', '=', 'ptu.objectpegawaifk')
            ->select('ptu.pelayananpasien', 'pg.namalengkap')
            ->where('ptu.kdprofile', $kdProfile)
            ->where('ptu.objectjenispetugaspefk', $sDokterPemeriksa)
            ->where('ptu.noregistrasi', $pd->noregistrasi)
            ->get();

        $result['total'] = 0;
        $result['deposit'] = 0;
        $result['diskon'] = 0;
        $result['dibayar'] = 0;
        $result['sisa'] = 0;

        $sama = false;
        $group  = [];
        foreach ($data as $item) {
            // $item->dokterpemeriksa = $item->strukresepfk != null ? $item->penulisresep : '-';
            $item->checked = false;
            $result['total']  = $result['total']  + (float) $item->total;
            $result['diskon']  = $result['diskon']  + (float) $item->hargadiscount;
            foreach ($pelayananpetugas as $itemd) {
                if ($itemd->pelayananpasien == $item->norec) {
                    $item->dokterpemeriksa = $itemd->namalengkap;
                }
            }
            $sama = false;
            $i = 0;
            foreach ($group as $itemg) {
                if ($item->tglpelayanan_group == $group[$i]['tglpelayanan_group']) {
                    $sama = true;
                }
                $i = $i + 1;
            }
            if ($sama == false) {
                $group[] = array(
                    'tglpelayanan_group' => $item->tglpelayanan_group,
                    'details' => []
                );
            }
        }
        foreach ($group as $k => $d) {
            foreach ($data as $d2) {
                if ($d['tglpelayanan_group'] == $d2->tglpelayanan_group) {
                    $group[$k]['details'][] = $d2;
                }
            }
        }


        $result['length'] = count($data);
        $result['detail'] = $group; //collect($data)->groupBy('tglpelayanan_group')->sortByDesc('tglpelayanan_group');
        $result['list_ruangan'] = AntrianPasienDiperiksa::listRuangan($pd->noregistrasi);
        $result['as'] = '@epic';

        return $this->respond($result);
    }

    // Penunjang

    public function getPasienAmbulan(Request $request)
    {
        $idProfile = $this->kdProfile;
        $dateBetween = [$request->tglAwal, $request->tglAkhir];
        $data = DB::table('antrianpasiendiperiksa_t as apd')
            ->join('pasiendaftar_t as pd', 'pd.norec', '=', 'apd.noregistrasifk')
            ->leftjoin('pegawai_m as pg', 'pg.id', '=', 'apd.objectpegawaifk')
            ->JOIN('ruangan_m as ru', 'ru.id', '=', 'apd.objectruanganfk')
            ->Join('departemen_m as dept', 'dept.id', '=', 'ru.objectdepartemenfk')
            ->JOIN('pasien_m as ps', 'ps.id', '=', 'pd.nocmfk')
            ->leftJoin('jeniskelamin_m as jk', 'jk.id', '=', 'ps.objectjeniskelaminfk')
            ->leftJoin('kelompokpasien_m as kp', 'kp.id', '=', 'pd.objectkelompokpasienlastfk')
            ->leftJoin('rekanan_m as rk', 'rk.id', '=', 'pd.objectrekananfk')
            ->leftJoin('kelas_m as kl', 'kl.id', '=', 'pd.objectkelasfk')
            ->leftJoin('strukpelayanan_t as sp', 'sp.norec', '=', 'pd.nostruklastfk')
            ->leftJoin('alamat_m as alm', 'alm.nocmfk', '=', 'ps.id')
            ->leftjoin('strukorder_t as so', 'so.norec', '=', 'apd.objectstrukorderfk')
            ->leftJoin('ruangan_m as ru1', 'ru1.id', '=', 'so.objectruanganfk')
            ->select(
                'apd.norec as norec_apd',
                'ru.id as ruid',
                'ru.namaruangan',
                'pd.noregistrasi',
                'ps.nocm',
                'ps.nobpjs',
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
                'apd.norec',
                'pd.norec as norec_pd',
                'sp.tglstruk',
                'pd.nostruklastfk',
                'alm.alamatlengkap',
                'apd.tglmasuk',
                'pg.namalengkap as dokter',
                'ru1.namaruangan as ruanganasal',

            )
            ->where('apd.kdprofile', $idProfile)
            ->where('apd.statusenabled', '=', 'true')
            ->whereBetween(DB::raw("CAST(apd.updated_at as DATE)"), $dateBetween)
            ->where('ru.objectdepartemenfk', $this->settingFix('kdAmbulance'))
            // ->whereBetween(DB::raw("CAST(apd.tglmasuk as DATE)"),$dateRange)
            ->orderBy('apd.tglregistrasi', 'desc');

        if (isset($request['ruanganid']) && $request['ruanganid'] != '') {
            $data = $data->where('ru.id', '=',  $request['ruanganid']);
        }
        if (isset($request['qnama']) && $request['qnama'] != '') {
            $data = $data->where('ps.namapasien', 'ilike', '%' . $request['qnama'] . '%');
        }
        if (isset($request['search']) && $request['search'] != '') {
            $searchTerm = '%' . $request['search'] . '%';
            $data = $data->where(function ($query) use ($searchTerm) {
                $query->where('ps.namapasien', 'ilike', $searchTerm)
                    ->orWhere('pd.noregistrasi', 'ilike', $searchTerm)
                    ->orWhere('ps.nocm', 'ilike', $searchTerm)
                    ->orWhere('ps.nobpjs', 'ilike', $searchTerm)
                    ->orWhere('ps.noidentitas', 'ilike', $searchTerm);
            });
        }
        if (isset($request['qnocm']) && $request['qnocm'] != '') {
            $data = $data->where('ps.nocm', 'ilike', '%' . $request['qnocm'] . '%');
        }
        if (isset($request['qnoregistrasi']) && $request['qnoregistrasi'] != '') {
            $data = $data->where('pd.noregistrasi', 'ilike', '%' . $request['qnoregistrasi'] . '%');
        }

        $total = $data->count();
        if (isset($request['limit']) && $request['limit'] != '') {
            $data = $data->limit($request['limit']);
        }
        if (isset($request['offset']) && $request['offset'] != '') {
            $data = $data->offset($request['offset']);
        }
        $data = $data->get();

        foreach ($data as $d) {
            $d->umur =  $this->getAge($d->tgllahir,   date('Y-m-d H:i:s'));
        }


        $result = array(
            "data" => $data,
            "as" => '@epic',
            "total" => $total
        );
        return $this->respond($result);
    }

    public function layananAmbulanperTindakan(Request $request)
    {
        $kdProfile = $this->kdProfile;
        $KodeJasaMedis = $this->settingFix('KdKomponenTarifJasaDokter');
        $noregistrasi = $request['noregistrasi'];
        $user = $request['user'];
        $norecPp = '';
        $datNorec = explode('|', $request['norec']);
        foreach ($datNorec as $ob) {
            $norecPp = $norecPp . ",'" . $ob . "'";
        }
        $norecPp = substr($norecPp, 1, strlen($norecPp) - 1);
        $paramsPp = "";
        if ($norecPp != '') {
            $paramsPp = " AND tp.norec IN (" . $norecPp . ")";
        }
        if ($request['so_norec']) {
            $paramsPp = "AND tp.strukorderfk = '" . $request['so_norec'] . "'";
        }
        $profile = $this->profile();

        $ruangan = DB::table('pasiendaftar_t as pd')
            ->join('ruangan_m as ru', 'ru.id', 'pd.objectruanganlastfk')
            ->select('ru.namaruangan')
            ->where('pd.noregistrasi', '=', $noregistrasi)
            ->get();

        $data =  $this->indentitasCetak($kdProfile, $noregistrasi);
        $data->ruanganasal = $ruangan[0]->namaruangan;
        // return $data;
        $sDokterPemeriksa = $this->settingFix('jenisPetugasDokterPemeriksa');
        $details = collect(DB::select("
            SELECT x.tglpelayanan,x.namadokter,x.namaproduk,x.jumlah,x.hargasatuan,x.diskon,x.jasa,(x.jumlah * (x.hargasatuan - x.diskon)) + x.jasa AS total, x.namaruangan
            FROM ( SELECT tp.tglpelayanan, ru.namaruangan, (select pg.namalengkap from pegawai_m as pg INNER JOIN pelayananpasienpetugas_t p3 on p3.objectpegawaifk = pg.id
                   WHERE p3.pelayananpasien = tp.norec AND p3.objectjenispetugaspefk = $sDokterPemeriksa limit 1) AS namadokter,tp.produkfk,pro.namaproduk,tp.jumlah,
                   tp.hargajual as hargasatuan,CASE WHEN tp.hargadiscount IS NULL THEN 0 ELSE tp.hargadiscount END AS diskon,
                   CASE WHEN tp.jasa IS NULL THEN 0 ELSE tp.jasa END AS jasa
            FROM pelayananpasien_t AS tp
            LEFT JOIN produk_m AS pro ON tp.produkfk = pro.id
            INNER JOIN antrianpasiendiperiksa_t AS apdp ON apdp.norec = tp.noregistrasifk
            INNER JOIN ruangan_m AS ru ON apdp.objectruanganfk = ru.id
            LEFT JOIN pegawai_m AS pp ON apdp.objectpegawaifk = pp.id
            WHERE tp.kdprofile = $kdProfile
            AND tp.statusenabled = true
            $paramsPp

            ) AS x
            ORDER BY x.tglpelayanan
        "));

        $pageWidth = 950;
        // $totalbayar = $data->totaldibayar;
        // $terbilang = $this->terbilang($totalbayar); //strtoupper($this->terbilang($totalbayar));
        $dataReport = array(
            'namaprofile' => $profile->namalengkap,
            'alamat' => $profile->alamatlengkap,
            'user' => $user,
            'judul' => "BUKTI PELAYANAN AMBULANCE",
            'header' => $data,
            'details' =>  $details,
        );
        // dd($dataReport);
        return view(
            'report.ambulan.bukti-layanan-ambulan',
            compact('dataReport', 'pageWidth', 'profile')
        );
    }
    function indentitasCetak($kdProfile, $noregistrasi)
    {
        $data = collect(DB::select("
        SELECT pd.noregistrasi,ps.nocm,ps.tgllahir,to_char(ps.tgllahir, 'DD-MM-YYYY') as tglkelahiran,ps.namapasien, ps.noidentitas, ps.nohp,ps.tempatlahir as tempatlahir , ng.namanegara,
               pd.tglregistrasi,jk.reportdisplay AS jk,ru2.namaruangan AS ruanganperiksa,ru.namaruangan AS ruangakhir,
               ks.namakelas,ar.asalrujukan,ps.notelepon,CASE WHEN rek.namarekanan is null then '-' else rek.namarekanan END as namapenjamin,
               CASE WHEN kmr.namakamar is null then '-' else kmr.namakamar END as namakamar,alm.alamatlengkap,kp.kelompokpasien,pp.namalengkap AS dpjp
        FROM pasiendaftar_t AS pd
        INNER JOIN pasien_m AS ps ON pd.nocmfk = ps.id
        INNER JOIN jeniskelamin_m AS jk ON ps.objectjeniskelaminfk = jk.id
        INNER JOIN kelompokpasien_m AS kp ON pd.objectkelompokpasienlastfk = kp.id
        INNER JOIN antrianpasiendiperiksa_t AS apdp ON apdp.noregistrasifk = pd.norec
        INNER JOIN ruangan_m AS ru ON apdp.objectruanganfk = ru.id
        LEFT JOIN pegawai_m AS pp ON apdp.objectpegawaifk = pp.id
        LEFT JOIN kelas_m AS ks ON apdp.objectkelasfk = ks.id
        LEFT JOIN asalrujukan_m AS ar ON apdp.objectasalrujukanfk = ar.id
        left JOIN rekanan_m AS rek ON rek.id= pd.objectrekananfk
        left JOIN kamar_m as kmr on apdp.objectkamarfk=kmr.id
        INNER join ruangan_m  as ru2 on ru2.id=apdp.objectruanganfk
        LEFT JOIN alamat_m as alm on alm.nocmfk = ps.id
        LEFT JOIN negara_m as ng on ng.id = ps.objectnegarafk
        WHERE pd.kdprofile = $kdProfile AND pd.statusenabled = true AND pd.noregistrasi = '$noregistrasi'
"))->first();
        return $data;
    }


    public function detailPetugasAmbulan(Request $r)
    {
        $kdProfile = $this->kdProfile;
        $result = DB::table('pelayananpasienpetugas_t as pp')
            ->join('jenispetugaspelaksana_m as jp', 'jp.id', '=', 'pp.objectjenispetugaspefk')
            ->join('pegawai_m as pg', 'pg.id', '=', 'pp.objectpegawaifk')
            ->select(
                'pp.norec',
                'pg.namalengkap',
                'jp.jenispetugaspe',
                'pp.objectpegawaifk',
                'pp.objectjenispetugaspefk',
                'pp.nomasukfk',
                'pp.pelayananpasien'
            )
            ->where('pp.pelayananpasien', $r['norec'])
            ->where('pp.statusenabled', true)
            ->where('pp.kdprofile', $kdProfile)
            ->get();

        return $this->respond($result);
    }

    public function savePetugasAmbulan(Request $r)
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
                $log . 'Petugas Tindakan',
                $new_PPP->norec,
                'pelayananpasienpetugas_t',
                $log . 'Petugas Tindakan ' . $pg->namalengkap . ' pelayanan ' . $r['namaproduk'] . ' di ' . $r['namaruangan'] . ' pada Pasien ' .
                    $r['noregistrasi']
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
                "result"  => $e->getMessage() . $e->getLine()
            );
        }
        return $this->respond($result['result'], $result['status'], $transMessage);
    }

    public function deletePetugasPJ(Request $r)
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
                'Hapus Petugas Tindakan ' . $pg->namalengkap . ' pelayanan ' . $r['namaproduk'] . ' di ' .  ' pada Pasien ' .
                    $r['noregistrasi']
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
                "result"  => $e->getMessage() . $e->getLine()
            );
        }
        return $this->respond($result['result'], $result['status'], $transMessage);
    }

    public function listPasienAmbulan(Request $r)
    {
        $data  = DB::table('pasiendaftar_t as pd')
            ->join('ruangan_m as ru', 'pd.objectruanganlastfk', '=', 'ru.id')
            ->leftjoin('pegawai_m as pg', 'pd.objectpegawaifk', '=', 'pg.id')
            ->leftjoin('pasien_m as ps', 'pd.nocmfk', '=', 'ps.id')
            ->join('kelas_m as kls', 'kls.id', '=', 'pd.objectkelasfk')
            ->leftjoin('jeniskelamin_m as jk', 'jk.id', '=', 'ps.objectjeniskelaminfk')
            ->select(
                'pd.norec as norec_pd',
                'pd.statusenabled',
                'pd.tglregistrasi',
                'ps.nocm',
                'pd.nocmfk',
                'pd.noregistrasi',
                'ru.namaruangan',
                'ps.namapasien',
                'pg.namalengkap as namadokter',
                'pd.tglpulang',
                'pd.statuspasien',
                'pd.objectpegawaifk as pgid',
                'pd.objectruanganlastfk',
                'pd.nostruklastfk',
                'kls.namakelas',
                'ps.tgllahir',
                'ru.objectdepartemenfk',
                'pd.objectkelasfk',
                'ps.nobpjs',
                'jk.jeniskelamin',
                // 'apd.norec as norec_apd',
                'ps.noidentitas',
                DB::raw("CAST(pd.tglregistrasi
                AS DATE),
                (case when pd.ispelayananpasien=true then 'Selesai' else 'Menunggu Pelayanan' end) as statuspelayanan,
                ps.objectjeniskelaminfk")
            )

            ->where('pd.kdprofile', $this->kdProfile)
            // ->whereIn('ru.objectdepartemenfk', explode(',', $this->settingFix('KdDeptPasienRJ')))
            // ->whereBetween(DB::raw("pd.tglregistrasi::date"),$rangeDate)
            ->where('pd.statusenabled', true);
        // ->where('apd.statusenabled', true);


        if (isset($r['ruanganid']) && $r['ruanganid'] != '') {
            $data = $data->where('ru.id', '=',  $r['ruanganid']);
        }

        if (isset($r['noregistrasi']) && $r['noregistrasi'] != '') {
            $data = $data->where('pd.noregistrasi', '=',  $r['noregistrasi']);
        }
        if (isset($r['nocm']) && $r['nocm'] != '') {
            $data = $data->where('ps.nocm', '=',  $r['nocm']);
        }
        if (isset($r['search']) && $r['search'] != '') {
            $searchTerm = '%' . $r['search'] . '%';
            $data = $data->where(function ($query) use ($searchTerm) {
                $query->where('ps.namapasien', 'ilike', $searchTerm)
                    ->orWhere('pd.noregistrasi', 'ilike', $searchTerm)
                    ->orWhere('ps.nocm', 'ilike', $searchTerm)
                    ->orWhere('ps.nobpjs', 'ilike', $searchTerm)
                    ->orWhere('ps.noidentitas', 'ilike', $searchTerm);
            });
        }
        if (isset($r['namapasien']) && $r['namapasien'] != '') {
            $data = $data->where('ps.namapasien', 'ilike', '%' . $r['namapasien'] . '%');
        }
        if (isset($r['dari']) && $r['dari'] != '') {
            $data = $data->where(DB::raw("pd.tglregistrasi::date"), '>=', $r->dari);
        }
        if (isset($r['sampai']) && $r['sampai'] != '') {
            $data = $data->where(DB::raw("pd.tglregistrasi::date"), '<=', $r->sampai);
        }
        if (isset($r['status']) && $r['status'] != '') {
            $data = $data->where('pd.ispelayananpasien', '=', $r['status']);
        }

        $total = $data->count();
        if (isset($r['limit']) && $r['limit'] != '') {
            $data = $data->limit($r['limit']);
        }
        if (isset($r['offset']) && $r['offset'] != '') {
            $data = $data->offset($r['offset']);
        }
        $data = $data->orderBy('pd.tglregistrasi');
        $data = $data->get();

        foreach ($data as $d) {
            $d->umur =  $this->getAgeYear($d->tgllahir, $d->tglregistrasi) . ' thn';
        }
        $res['data'] = $data;
        $res['total'] = $total;
        return $this->respond($res);
    }

    public function saveBatalMeninggal(Request $request)
    {
        DB::beginTransaction();
        try {
            $kdProfile = $this->kdProfile;
            $r_NewPD = $request['pasiendaftar'];


            PasienDaftar::where('norec', $r_NewPD['norec_pd'])->update([
                'objectstatuskeluarfk' => null,
                'objectstatuspulangfk' => null,
                'objectkondisipasienfk' => null,
                'tglpulang' => null,
                'objecthubungankeluargaambilpasienfk'  => null,
                'namalengkapambilpasien'  => null,
            ]);

            Pasien::where('id', $r_NewPD['nocmfk'])
                ->where('kdprofile', $kdProfile)
                ->update([
                    'tglmeninggal' => null,
                ]);

            // $this->LOGGING(
            //     'Batal Meninggal',
            //     $r_NewPD['norec_pd'],
            //     'pasiendaftar_t',
            //     'Batal Meninggal atas nama ' .
            //     $r_NewPD['namapasien'] . ' (' . $r_NewPD['nocm'] . ') - ' . $r_NewPD['noregistrasi']
            // );


            $transStatus = 'true';
        } catch (\Exception $e) {
            $transStatus = 'false';
        }
        if ($transStatus == 'true') {
            $transMessage = "Sukses";
            DB::commit();
            $result = array(
                "status" => 200,
                "result" => null
            );
        } else {
            $transMessage = "Simpan Gagal";
            DB::rollBack();
            $result = array(
                "status" => 400,
                "result"  => $e->getMessage() . $e->getLine()
            );
        }
        return $this->respond($result['result'], $result['status'], $transMessage);
    }

    public function savePermohonanPelayananJenazah(Request $request)
    {
        $kdProfile = $this->kdProfile;
        $idProfile = (int) $kdProfile;
        $tglAyeuna = date('Y-m-d H:i:s');
        $dataLogin = $request->all();
        $dataPegawai = DB::table('loginuser_s as lu')
            ->select('lu.objectpegawaifk')
            ->where('lu.id', $dataLogin['userData']['id'])
            ->where('lu.kdprofile', $idProfile)
            ->first();
        $pasien = DB::table('pasiendaftar_t as pd')
            ->join('ruangan_m as ru', 'pd.objectruanganlastfk', '=', 'ru.id')
            ->join('departemen_m as dept', 'dept.id', '=', 'ru.objectdepartemenfk')
            ->select('pd.noregistrasi', 'ru.namaruangan', 'pd.tglpulang')
            ->where('pd.norec', '=', $request['nores_pd'])
            ->where('pd.kdprofile', $idProfile)
            ->where('pd.statusenabled', true)
            ->first();

        $keterangan = '';
        DB::beginTransaction();
        try {

            if ($request['norec'] == "") {
                $keterangan = "Input Permohonan Pelayanan Jenazah";
                $dataSO = new SuratPermohonanJenazah();
                $dataSO->norec = $dataSO->generateNewId();
                $dataSO->kdprofile = $idProfile;
                $dataSO->statusenabled = true;
                $dataSO->tglsurat = $tglAyeuna;
                $dataSO->pasiendaftarfk = $request['nores_pd'];
            } else {
                $keterangan = "Ubah Permohonan Pelayanan Jenazah";
                $dataSO = SuratPermohonanJenazah::where('norec', $request['norec'])->first();
            }
            $dataSO->nosurat = $request['nosurat'];
            $dataSO->penanggungjawab = $request['penanggungjawab'];
            $dataSO->objectjeniskelaminfk = $request['objectjeniskelaminfk'];
            $dataSO->objecthubungankeluargafk = $request['objecthubungankeluargafk'];
            $dataSO->alamat = $request['alamat'];
            $dataSO->covid = $request['covid'];
            $dataSO->noncovid = $request['noncovid'];
            $dataSO->petugassatu = $request['petugassatu'];
            $dataSO->petugasdua = $request['petugasdua'];
            $dataSO->petugastiga = $request['petugastiga'];
            $dataSO->petugasempat = $request['petugasempat'];
            $dataSO->petugaslima = $request['petugaslima'];
            $dataSO->pemulasaraanjenazah = $request['pemulasaraanjenazah'];
            $dataSO->pengkafanan = $request['pengkafanan'];
            $dataSO->plastisisasi = $request['plastisisasi'];
            $dataSO->kantongjenazah = $request['kantongjenazah'];
            $dataSO->petijenazah = $request['petijenazah'];
            $dataSO->disinfektanjenazah = $request['disinfektanjenazah'];
            $dataSO->pelayanankerohanian = $request['pelayanankerohanian'];
            $dataSO->transportasiambulan = $request['transportasiambulan'];
            $dataSO->disinfektanambulan = $request['disinfektanambulan'];
            $dataSO->save();
            $dataSOnorec = $dataSO->norec;

            /*Logging User*/
            $newId = LoggingUser::max('id');
            $newId = $newId + 1;
            $logUser = new LoggingUser();
            $logUser->id = $newId;
            $logUser->norec = $logUser->generateNewId();
            $logUser->kdprofile = $kdProfile;
            $logUser->statusenabled = true;
            $logUser->jenislog = $keterangan;
            $logUser->noreff = $dataSOnorec;
            $logUser->referensi = 'Norec Surat Permohonan Jenazah';
            $logUser->objectloginuserfk =  $this->getUserId();
            $logUser->tanggal = $tglAyeuna;
            $logUser->keterangan = $keterangan . ' Pasien Dengan No Registrasi ' . $pasien->noregistrasi;
            $logUser->save();
            /*End Logging User*/


            $transStatus = 'true';
        } catch (\Exception $e) {
            $transStatus = 'false';
        }
        if ($transStatus == 'true') {
            $transMessage = "Sukses";
            DB::commit();
            $result = array(
                "status" => 200,
                "result" => null
            );
        } else {
            $transMessage = "Simpan Gagal";
            DB::rollBack();
            $result = array(
                "status" => 400,
                "result"  => $e->getMessage() . $e->getLine()
            );
        }
        return $this->respond($result['result'], $result['status'], $transMessage);
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


    public function RiwayatPelayanan(Request $request)
    {
        $idProfile = $this->kdProfile;
        $sDokterPemeriksa = $this->settingFix('jenisPetugasDokterPemeriksa');
        $pelayanan = DB::table('pelayananpasien_t as pp')
            ->JOIN('antrianpasiendiperiksa_t as apd', 'apd.norec', '=', 'pp.noregistrasifk')
            ->JOIN('pasiendaftar_t as pd', 'pd.norec', '=', 'apd.noregistrasifk')
            ->JOIN('pasien_m as ps', 'ps.id', '=', 'pd.nocmfk')
            ->leftJOIN('jeniskelamin_m as jk', 'jk.id', '=', 'ps.objectjeniskelaminfk')
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
                ->leftjoin('pegawai_m as pg', 'pg.id', '=', 'ptu.objectpegawaifk')
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
        if ($lokal) {
            return $result;
        }

        return $this->respond($result);
    }

    public function saveTransaksiAmbulan(Request $request)
    {
        DB::beginTransaction();
        try {
            $kdProfile = $this->kdProfile;
            $r_NewPD = $request['pasiendaftar'];
            $r_NewAPD = $request['antrianpasiendiperiksa'];
            $idKelasRadLab = (int) $this->settingFix('kdKelasLabRad');

            $countNoAntrian = AntrianPasienDiperiksa::where('objectruanganfk', $r_NewAPD['objectruangantujuanfk'])
                ->where('tglregistrasi', '>=', date('Y-m-d', strtotime($r_NewPD['tglregistrasi'])) . ' 00:00')
                ->where('tglregistrasi', '<=',  date('Y-m-d', strtotime($r_NewPD['tglregistrasi'])) . ' 23:59')
                ->count();

            $noAntrian = $countNoAntrian + 1;

            // PasienDaftar::where('norec', $r_NewPD['norec_pd'])
            // ->where('kdprofile', $this->kdProfile)
            // ->update(
            //     [
            //         'objectkelasfk' => $idKelasRadLab
            //     ]
            // );

            $pd = PasienDaftar::where('norec', $r_NewPD['norec_pd'])->first();

            $dataAPD = new AntrianPasienDiperiksa;
            $dataAPD->norec = $dataAPD->generateNewId();
            $dataAPD->kdprofile = $this->kdProfile;
            $dataAPD->statusenabled = true;
            // $dataAPD->objectasalrujukanfk = $r_NewPD['asalrujukanfk'];
            $dataAPD->objectkelasfk =  $r_NewPD['objectkelasfk'];
            $dataAPD->noantrian = $noAntrian;
            $dataAPD->noregistrasifk = $r_NewPD['norec_pd'];
            $dataAPD->objectpegawaifk = $r_NewAPD['objectpegawaifk'];
            $dataAPD->objectruanganfk = $r_NewAPD['objectruangantujuanfk'];
            $dataAPD->statusantrian = 0;
            $dataAPD->statuspasien = 1;
            $dataAPD->statuskunjungan = 'LAMA';
            $dataAPD->statuspenyakit = 'BARU';
            // $dataAPD->objectruanganasalfk = $r_NewPD['objectruanganasalfk'];;
            $dataAPD->tglregistrasi = $r_NewPD['tglregistrasi'];
            // $dataAPD->tglregistrasi = $r_NewPD->tglregistrasi; //date('Y-m-d H:i:s');
            $dataAPD->tglkeluar = date('Y-m-d H:i:s');
            $dataAPD->tglmasuk = date('Y-m-d H:i:s');
            $dataAPD->save();

            $this->LOGGING(
                'Registrasi Transaksi Pelayanan',
                $r_NewPD['norec_pd'],
                'pasiendaftar_t',
                ' pada Pasien ' .
                    $r_NewPD['norec_pd'] . 'ke' . $r_NewAPD['objectruangantujuanfk']
            );



            DB::commit();
            $result = array(
                "status" => 200,
                "message" => 'Berhasil',
                "result" => $dataAPD,
            );
        } catch (Exception $e) {
            DB::rollBack();
            $result = [
                "status" => 400,
                "message" => "Simpan Gagal !",
                "result"  => $e->getMessage() . $e->getLine()
            ];
        }
        return $this->respond($result['result'], $result['status'], $result['message']);
    }

    public function getSuratJalan(Request $request)
    {

        $data = DB::table('suratketerangan_t as sk')
            ->join('pegawai_m as pr', 'pr.id', 'sk.dokterfk')
            ->join('pegawai_m as peg', 'peg.id', 'sk.pegawaifk')
            ->join('keperluan_m as kep', 'kep.id', 'sk.keperluanfk')
            ->select('sk.*', 'peg.namalengkap as pengemudi', 'pr.namalengkap as perawat', 'kep.keperluan')
            ->where('sk.kdprofile', $this->kdProfile)
            ->where('sk.statusenabled', true)->where('strukorderfk', $request['strukorderfk'])
            ->where('sk.pasiendaftarfk', $request['norec_pd'])
            ->where('sk.jenissuratfk', $this->settingFix('idSuratJalan'))
            ->first();

        return $this->respond($data);
    }

    public function simpanSuratJalan(Request $request)
    {

        DB::beginTransaction();
        try {

            if ($request['norec'] == '') {
                $suratKet = new SuratKeterangan();
                $suratKet->norec = $suratKet->generateNewId();
                $suratKet->statusenabled = true;
                $suratKet->kdprofile = $this->kdProfile;
                $suratKet->keterangan = "Surat Jalan";
            } else {
                $suratKet = SuratKeterangan::where('norec', $request['norec'])->first();
            }
            $suratKet->nosurat = $request['nosuratjalan'];
            $suratKet->nosint = $request['nokendaraan'];
            $suratKet->jenissuratfk = $this->settingFix('idSuratJalan');
            $suratKet->dokterfk = $request['perawatfk'];
            $suratKet->pegawaifk = $request['pengemudifk'];
            $suratKet->pasiendaftarfk = $request['norec_pd'];
            $suratKet->strukorderfk = $request['strukorderfk'];
            $suratKet->jarakjalan = $request['jalan'];
            $suratKet->jarakdatang = $request['datang'];
            $suratKet->jaraktempuh = $request['jaraktempuh'];
            $suratKet->bensin = $request['bensin'];
            $suratKet->keperluanfk = $request['keperluanfk'];
            $suratKet->tujuan = $request['tujuan'];
            $suratKet->save();

            DB::commit();

            $result = [
                'status' => 201,
                'message' => "Simpan Data Berhasil",

            ];
        } catch (Exception $e) {
            DB::rollBack();

            $result = [
                'status' => 400,
                'data' => $e->getMessage(),
                'message' => "Simpan Gagal !",
            ];
        }

        return $this->respond($result, $result['status'], $result['message']);
    }

    public function cetakLaporan(Request $request)
    {

        $dateRange = [$request->tglAwal, $request->tglAkhir];

        $data = DB::table('pelayananpasien_t as pp')
            ->leftjoin('produk_m as prd', 'prd.id', 'pp.produkfk')
            ->join('strukorder_t as so', 'so.norec', 'pp.strukorderfk')
            ->join('ruangan_m as ru', 'ru.id', 'so.objectruangantujuanfk')
            ->join('pasiendaftar_t as pd', 'pd.norec', 'so.noregistrasifk')
            ->join('pasien_m as ps', 'ps.id', 'pd.nocmfk')
            ->join('suratketerangan_t as sk', 'sk.strukorderfk', 'so.norec')
            ->join('keperluan_m as kep', 'kep.id', 'sk.keperluanfk')
            ->leftjoin('pegawai_m as per', 'per.id', 'sk.dokterfk')
            ->leftjoin('pegawai_m as peng', 'peng.id', 'sk.pegawaifk')
            ->leftjoin('strukpelayanan_t as sp', 'sp.norec', 'pp.strukfk')
            ->select(
                'ps.namapasien',
                'ps.nocm',
                'pd.noregistrasi',
                'ru.namaruangan',
                'per.namalengkap as namaperawat',
                'peng.namalengkap as pengemudi',
                'sk.tujuan',
                'sk.nosurat',
                'prd.namaproduk as zona',
                'pp.hargasatuan as biaya',
                'sk.nosint as nopolisi',
                'kep.keperluan',
                'pp.tglpelayanan',
                'so.tglorder',
                'pd.tglregistrasi',
                DB::raw(
                    "case when sp.nosbmlastfk is not null
                or sp.nosbklastfk is not null
                then 'Bayar' else '-' end
                as statusbayar",
                )
            )
            ->where('pp.kdprofile', $this->kdProfile)
            ->where('pp.statusenabled', true)
            // ->where('so.statusorder', 1)
            ->where('so.objectkelompoktransaksifk', $this->kelompokTransaksi('PELAYANAN AMBULANCE'))
            ->whereBetween(DB::raw('so.tglorder::date'), $dateRange)
            ->get();

        $profile = Profile::where('id', $this->kdProfile)->first();

        if ($request['pdf'] == true) {
            $pdf = App::make('dompdf.wrapper');
            $pdf->setPaper('legal', 'landscape');
            $pdf->loadView(
                'report.ambulan.cetak-laporan-ambulan',
                array(
                    'dataReport'    => $data,
                    'tglAwal' => $request['tglAwal'],
                    'tglAkhir' => $request['tglAkhir'],
                    'request'       => $request,
                    'profile'       => $profile,
                    'user' => $this->getNamaPegawai(),
                    'res'           => array(
                        'pdf' => true
                    ),
                    'profile' => $profile,
                )
            );
            return $pdf->stream();
        } else {
            return view(
                'report.jenazah.cetak-laporan-jenazah',
                compact('pageWidth', 'profile', 'request')
            );
        }
    }
}
