<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Resources\Dashboard\TotalPasien\TotalPasienResource;
use App\Models\Master\Departemen;
use App\Models\Master\Kamar;
use App\Models\Master\Ruangan;
use App\Models\Master\TempatTidur;
use App\Models\Transaksi\AntrianPasienDiperiksa;
use App\Models\Transaksi\PasienDaftar;
use App\Traits\Valet;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Transaksi\OrderPelayanan;
use App\Models\Transaksi\StrukOrder;

class DashboardRencanaMutasiCtrl extends Controller
{
    use Valet;
    public function __construct()
    {
        parent::__construct($is_encrypt = true);
    }

    public function getRuanganRanap()
    {
        $ruangan = DB::table('maploginusertoruangan_s as mlur')
            ->join('loginuser_s as lu', function ($j) {
                $j->on('lu.id', 'mlur.objectloginuserfk')->on('lu.kdprofile', 'mlur.kdprofile');
            })
            ->join('ruangan_m as ru', function ($j) {
                $j->on('ru.id', 'mlur.objectruanganfk')->on('ru.kdprofile', 'mlur.kdprofile');
            })
            ->select('ru.id', 'ru.namaruangan')
            ->where('mlur.statusenabled', true)
            ->where('lu.kdprofile', $this->kdProfile)
            ->where('ru.objectdepartemenfk', $this->settingFix('idDepRawatInap'))
            ->where('ru.statusenabled', true)
            ->where('lu.id', $this->getUserId())
            ->groupBy('ru.id', 'ru.namaruangan')
            ->get();

        $ruanganTersedia = [];
        foreach ($ruangan as $ru) {
            $ruanganTersedia[] = $ru->id;
        }

        return $ruanganTersedia;
    }

    public function getDropdown(Request $request)
    {
        $listRuangan = $this->getRuanganRanap();

        $set = explode(',', $this->settingFix('idDepRawatInap'));
        $res['ruangan'] = Ruangan::mine()->whereIn('objectdepartemenfk', $set)->whereIn('id', $listRuangan)->get();
        $res['ruanganAll'] = Ruangan::mine()->whereIn('objectdepartemenfk', $set)->get();

        return $this->respond($res);
    }
    public function getDetailRencanaMutasi(Request $request)
    {
        $ruanganTersedia = $this->getRuanganRanap();
        $SET['idStatusBedKosong'] = $this->settingFix('idStatusBedKosong');
        $SET['idStatusBedIsi'] = $this->settingFix('idStatusBedIsi');

        $data = DB::table('tempattidur_m as tt')
            ->join('kamar_m as kmr', 'kmr.id', 'tt.objectkamarfk')
            ->join('ruangan_m as ru', 'ru.id', 'kmr.objectruanganfk')
            ->join('kelas_m as kl', 'kl.id', 'kmr.objectkelasfk')
            ->select(
                'ru.id',
                'ru.namaruangan',
                'kl.namakelas',
                'kmr.namakamar',
                DB::raw("
                    sum(case when tt.objectstatusbedfk = $SET[idStatusBedKosong]  then  1 else 0 end ) as isi,
                    sum(case when tt.objectstatusbedfk = $SET[idStatusBedIsi] then  1 else 0 end ) as kosong,
                    sum(case when tt.objectstatusbedfk = $SET[idStatusBedKosong] then  1 else 0 end ) +
                    sum(case when tt.objectstatusbedfk =$SET[idStatusBedIsi] then  1 else 0 end ) as total
                    ")
            )
            ->where('tt.kdprofile', $this->kdProfile)
            ->where('kmr.statusenabled', true)
            ->where('tt.statusenabled', true)
            ->where('ru.statusenabled', true)
            ->where('ru.objectdepartemenfk', $this->settingFix('idDepRawatInap'));
        // if (isset($request['ruanganfk']) && $request['ruanganfk'] != '') {
        //     $data = $data->whereIn('ru.id', explode(',', $request['ruanganfk']));
        // } else {
        //     // $data = $data->whereIn('ru.id', $ruanganTersedia);
        // }
        if (isset($request['namakamar']) && $request['namakamar'] != '') {
            $searchTerm = '%' . $request['namakamar'] . '%';
            $data = $data->where(function ($query) use ($searchTerm) {
                $query->Where('kmr.namakamar', 'ilike', $searchTerm)
                    ->orWhere('ru.namaruangan', 'ilike', $searchTerm)
                    ->orWhere('kl.namakelas', 'ilike', $searchTerm);
            });
        }

        $data = $data->groupBy('ru.id', 'ru.namaruangan', 'kl.namakelas', 'kmr.namakamar');

        $data = $data->get();

        $dataBed = DB::table('tempattidur_m as tt')
            ->join('kamar_m as kmr', 'kmr.id', 'tt.objectkamarfk')
            ->join('ruangan_m as ru', 'ru.id', 'kmr.objectruanganfk')
            ->join('kelas_m as kl', 'kl.id', 'kmr.objectkelasfk')
            ->select(
                'ru.id',
                'ru.namaruangan',
                'kl.namakelas',
                'kmr.namakamar',
                DB::raw("string_agg(tt.reportdisplay, ', ') as bed")
            )
            ->where('tt.kdprofile', $this->kdProfile)
            ->where('kmr.statusenabled', true)
            ->where('tt.statusenabled', true)
            ->where('ru.statusenabled', true)
            ->where('ru.objectdepartemenfk', $this->settingFix('idDepRawatInap'));
        // if (isset($request['ruanganfk']) && $request['ruanganfk'] != '') {
        //     $dataBed = $dataBed->whereIn('ru.id', explode(',', $request['ruanganfk']));
        // } else {
        //     // $data = $data->whereIn('ru.id', $ruanganTersedia);
        // }
        if (isset($request['namakamar']) && $request['namakamar'] != '') {
            $searchTerm = '%' . $request['namakamar'] . '%';
            $dataBed = $dataBed->where(function ($query) use ($searchTerm) {
                $query->Where('kmr.namakamar', 'ilike', $searchTerm)
                    ->orWhere('ru.namaruangan', 'ilike', $searchTerm)
                    ->orWhere('kl.namakelas', 'ilike', $searchTerm);
            });
        }

        $dataBed = $dataBed->groupBy('ru.id', 'ru.namaruangan', 'kl.namakelas', 'kmr.namakamar');

        $dataBed = $dataBed->get();

        $bedIsi = 0;
        $bedKosong = 0;
        foreach ($data as $d) {
            $bedKosong = $bedKosong + $d->kosong;
            $bedIsi = $bedIsi + $d->isi;
            foreach ($dataBed as $db) {
                if ($d->namaruangan == $db->namaruangan && $d->namakelas == $db->namakelas && $d->namakamar == $db->namakamar) {
                    $d->bed = $db->bed;
                }
            }
        }



        $idkelrencanamutasi = $this->settingFix('kelompoktransaksiRencanaMutasi');
        $totalRencanaMutasi = StrukOrder::where('objectkelompoktransaksifk', $idkelrencanamutasi)
            ->where('kdprofile', $this->kdProfile)
            ->whereNull('statusorder');
        if (isset($request['ruanganfk']) && $request['ruanganfk'] != '') {
            $totalRencanaMutasi = $totalRencanaMutasi->whereIn('objectruangantujuanfk', explode(',', $request['ruanganfk']));
        }
        $totalRencanaMutasi = $totalRencanaMutasi->count();

        $totalBelumMutasi = StrukOrder::where('objectkelompoktransaksifk', $idkelrencanamutasi)
            ->where('kdprofile', $this->kdProfile)
            ->where('statusenabled', true)
            ->whereNull('statusorder');
        if (isset($request['ruanganfk']) && $request['ruanganfk'] != '') {
            $totalBelumMutasi = $totalBelumMutasi->whereIn('objectruangantujuanfk', explode(',', $request['ruanganfk']));
        }
        $totalBelumMutasi = $totalBelumMutasi->count();

        // $totalSudahMutasi = StrukOrder::where('objectkelompoktransaksifk', $idkelrencanamutasi)
        // ->where('kdprofile', $this->kdProfile)
        // ->where('statusenabled', true)
        // ->whereNotNull('statusorder');
        // if (isset($request['ruanganfk']) && $request['ruanganfk'] != '') {
        //     $totalSudahMutasi = $totalSudahMutasi->whereIn('objectruangantujuanfk', explode(',', $request['ruanganfk']));
        // }
        // $totalSudahMutasi = $totalSudahMutasi->count();

        $totalDitolak = StrukOrder::where('objectkelompoktransaksifk', $idkelrencanamutasi)
            ->where('kdprofile', $this->kdProfile)
            ->where('statusenabled', false)
            ->whereNull('statusorder')
            ->count();

        $res['data'] = $data;
        $res['totalRencanaMutasi'] = $totalRencanaMutasi;
        $res['totalBelumMutasi'] = $totalBelumMutasi;
        // $res['totalSudahMutasi'] = $totalSudahMutasi;
        $res['totalDitolak'] = $totalDitolak;
        return $this->respond($res);
    }
    public function getRencanaMutasi(Request $request)
    {
        $idkelrencanamutasi = $this->settingFix('kelompoktransaksiRencanaMutasi');
        $data = DB::table("strukorder_t as so")
            ->select(
                'so.norec'
                ,
                'so.tglorder'
                ,
                'so.objectruanganfk'
                ,
                'so.objectruangantujuanfk'
                ,
                'so.norec_apd'
                ,
                'so.objectkelasfk'
                ,
                'so.asalrujukanfk'
                ,
                'so.keteranganasalrujukan'
                ,
                'so.objectcarabayar_quofk'
                ,
                'so.jenispelayanan'
                ,
                'so.keteranganlainnya'
                ,
                'so.objectrekananfk'
                ,
                'so.keterangaantrian'
                ,
                'so.objectpegawaitujuanfk'
                ,
                'so.nocmfk'
                ,
                'so.iskelastitip'
                ,
                'op.objectkelasfk as objectkelasrawatfk'
                ,
                'op.objectkamarfk'
                ,
                'op.nobed as objectbedfk'
                ,
                'op.israwatgabung'
                ,
                'ps.namapasien'
                ,
                'ps.nocm'
                ,
                'ps.nobpjs'
                ,
                'ps.noidentitas'
                ,
                'pd.norec as norec_pd'
                ,
                'pd.noregistrasi'
                ,
                'pg.namalengkap'
                ,
                'kp.kelompokpasien'
                ,
                'kl.namakelas'
                ,
                'kmr.namakamar'
                ,
                'tt.reportdisplay'
                ,
                'rm.namaruangan'
                ,
                'dp.namadepartemen'
                ,
                'ds.namadesakelurahan'
                ,
                'km.namakecamatan'
                ,
                'kbp.namakotakabupaten'
                ,
                'tt.id as nobed'
            )
            ->join('orderpelayanan_t as op', 'so.norec', '=', 'op.strukorderfk')
            ->join('pasien_m as ps', 'so.nocmfk', '=', 'ps.id')
            ->join('ruangan_m as rm', 'rm.id', '=', 'so.objectruangantujuanfk')
            ->leftjoin('kelas_m as kl', 'kl.id', '=', 'so.objectkelasfk')
            ->leftjoin('kamar_m as kmr', 'kmr.id', '=', 'op.objectkamarfk')
            ->leftjoin('tempattidur_m as tt', 'tt.id', 'op.nobed')
            ->leftjoin('pasiendaftar_t as pd', 'pd.norec', '=', 'so.noregistrasifk')
            ->leftjoin('pegawai_m as pg', 'so.objectpegawaitujuanfk', '=', 'pg.id')
            ->leftjoin('kelompokpasien_m as kp', DB::raw('cast(so.objectcarabayar_quofk as integer)'), '=', 'kp.id')
            ->leftJoin('alamat_m as alm', 'alm.nocmfk', '=', 'ps.id')
            ->leftJoin('kotakabupaten_m as kbp', 'alm.objectkotakabupatenfk', '=', 'kbp.id')
            ->leftJoin('kecamatan_m as km', 'alm.objectkecamatanfk', '=', 'km.id')
            ->leftJoin('desakelurahan_m as ds', 'alm.objectdesakelurahanfk', '=', 'ds.id')
            ->leftJoin('departemen_m as dp', 'dp.id', '=', 'rm.objectdepartemenfk')
            ->where('so.statusenabled', true)
            ->where('so.kdprofile', $this->kdProfile)
            ->where('so.objectkelompoktransaksifk', $idkelrencanamutasi)
            ->whereNull('statusorder')
            ->orderBy('so.tglorder', 'DESC');

        // if (isset($request['ruanganfk']) && $request['ruanganfk'] != '') {
        //     $data = $data->whereIn('so.objectruangantujuanfk', explode(',', $request['ruanganfk']));
        // }
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
                    ->orWhere('ps.nocm', 'ilike', $searchTerm)
                    ->orWhere('ps.nobpjs', 'ilike', $searchTerm)
                    ->orWhere('ps.noidentitas', 'ilike', $searchTerm);
            });
        }
        $data = $data->get();

        $res['data'] = $data;
        $res['total'] = $total;
        return $this->respond($res);
    }
    public function getRencanaMutasiDitolak(Request $request)
    {
        $idkelrencanamutasi = $this->settingFix('kelompoktransaksiRencanaMutasi');
        $data = DB::table("strukorder_t as so")
            ->select(
                'so.norec'
                ,
                'so.tglorder'
                ,
                'ps.namapasien'
                ,
                'ps.nocm'
                ,
                'ps.nobpjs'
                ,
                'ps.noidentitas'
                ,
                'pd.norec as norec_pd'
                ,
                'pd.noregistrasi'
                ,
                'pg.namalengkap'
                ,
                'kp.kelompokpasien'
                ,
                'kl.namakelas'
                ,
                'kmr.namakamar'
                ,
                'tt.reportdisplay'
                ,
                'rm.namaruangan'
                ,
                'ds.namadesakelurahan'
                ,
                'km.namakecamatan'
                ,
                'kbp.namakotakabupaten'
            )
            ->join('orderpelayanan_t as op', 'so.norec', '=', 'op.strukorderfk')
            ->join('pasien_m as ps', 'so.nocmfk', '=', 'ps.id')
            ->join('ruangan_m as rm', 'rm.id', '=', 'so.objectruangantujuanfk')
            ->join('kelas_m as kl', 'kl.id', '=', 'so.objectkelasfk')
            ->join('kamar_m as kmr', 'kmr.id', '=', 'op.objectkamarfk')
            ->join('tempattidur_m as tt', 'tt.id', 'op.nobed')
            ->leftjoin('pasiendaftar_t as pd', 'pd.norec', '=', 'so.noregistrasifk')
            ->leftjoin('pegawai_m as pg', 'so.objectpegawaitujuanfk', '=', 'pg.id')
            ->leftjoin('kelompokpasien_m as kp', DB::raw('cast(so.objectcarabayar_quofk as integer)'), '=', 'kp.id')
            ->leftJoin('alamat_m as alm', 'alm.nocmfk', '=', 'ps.id')
            ->leftJoin('kotakabupaten_m as kbp', 'alm.objectkotakabupatenfk', '=', 'kbp.id')
            ->leftJoin('kecamatan_m as km', 'alm.objectkecamatanfk', '=', 'km.id')
            ->leftJoin('desakelurahan_m as ds', 'alm.objectdesakelurahanfk', '=', 'ds.id')
            ->where('so.statusenabled', false)
            ->where('so.kdprofile', $this->kdProfile)
            ->where('so.objectkelompoktransaksifk', $idkelrencanamutasi)
            ->orderBy('so.tglorder', 'DESC');

        if (isset($request['ruanganfk']) && $request['ruanganfk'] != '') {
            $data = $data->whereIn('so.objectruangantujuanfk', explode(',', $request['ruanganfk']));
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
                    ->orWhere('ps.nocm', 'ilike', $searchTerm)
                    ->orWhere('ps.nobpjs', 'ilike', $searchTerm)
                    ->orWhere('ps.noidentitas', 'ilike', $searchTerm);
            });
        }
        $data = $data->get();

        $res['data'] = $data;
        $res['total'] = $total;
        return $this->respond($res);
    }
    public function SaveRencanaMutasi(Request $request)
    {
        $idProfile = (int) $this->kdProfile;
        $PD = $request['pasiendaftar'];
        $APD = $request['antrianpasiendiperiksa'];
        $idkelrencanamutasi = $this->settingFix('kelompoktransaksiRencanaMutasi');
        $keltransaksi = DB::table('kelompoktransaksi_m')->where('id', $idkelrencanamutasi)->first();
        $isRI_NuklirTerapi = $PD['objectruanganlastfk'] == 315 ? 'true' : 'false';
        $dataSO = StrukOrder::where('nocmfk', $PD['nocmfk'])
            ->where('objectkelompoktransaksifk', $idkelrencanamutasi)
            ->whereNull('statusorder')
            ->first();
        if (!empty($dataSO)) {
            $transMessage = 'Pasien sudah dalam rencana mutasi';
            $result = array("status" => 400, 'message' => $transMessage, "result" => $dataSO);
            return $this->respond($result, $result['status'], $transMessage);
        }

        if ($PD['objectkelompokpasienlastfk'] != 1) {
            $cekRI = PasienDaftar::where('nocmfk', $PD['nocmfk'])
                ->whereNull('tglpulang')
                ->where('statusenabled', true)
                ->first();

            if ($isRI_NuklirTerapi == 'false') {
                if (isset($cekRI) && $PD['norec'] == '') {
                    DB::rollBack();
                    $transMessage = 'Pasien belum dipulangkan dg No. Registrasi : '
                        . $cekRI->noregistrasi . ' (' . $cekRI->tglregistrasi . ')';
                    $result = array("status" => 400, "result" => $cekRI);
                    return $this->respond($result['result'], $result['status'], $transMessage);
                }
            }
        }

        DB::beginTransaction();
        try {

            $noOrder = $this->generateCodeBySeqTable(new StrukOrder, 'noorderrencanamutasi', 11, 'MT' . date('ym'), $idProfile);
            if ($noOrder == '') {
                $transMessage = "Gagal mengumpukan data, Coba lagi.!";
                DB::rollBack();
                $result = array("status" => 400, "result" => null);
                return $this->respond($result['result'], $result['status'], $transMessage);
            }

            $dataSO = new StrukOrder;
            $dataSO->norec = $dataSO->generateNewId();
            $dataSO->kdprofile = $idProfile;
            $dataSO->statusenabled = true;
            $dataSO->nocmfk = $PD['nocmfk'];

            $dataSO->isdelivered = 1;
            $dataSO->qtyjenisproduk = 1;
            $dataSO->qtyproduk = 1;
            $dataSO->totalbeamaterai = 0;
            $dataSO->totalbiayakirim = 0;
            $dataSO->totalbiayatambahan = 0;
            $dataSO->totaldiscount = 0;
            $dataSO->totalhargasatuan = 0;
            $dataSO->totalharusdibayar = 0;
            $dataSO->totalpph = 0;
            $dataSO->totalppn = 0;
            $dataSO->keteranganorder = $keltransaksi->kelompoktransaksi;
            $dataSO->objectkelompoktransaksifk = $keltransaksi->id;
            $dataSO->noorder = $noOrder;
            $dataSO->noorderintern = $noOrder;
            $dataSO->tglorder = $PD['tglregistrasi'];
            $dataSO->objectruangantujuanfk = $PD['objectruanganlastfk'];
            $dataSO->asalrujukanfk = $PD['asalrujukanfk'];
            $dataSO->keteranganasalrujukan = $PD['keteranganasalrujukan'];
            $dataSO->objectcarabayar_quofk = $PD['objectkelompokpasienlastfk'];
            $dataSO->jenispelayanan = $PD['jenispelayananfk'];
            $dataSO->objectpegawaitujuanfk = $PD['objectpegawaifk'];
            $dataSO->objectpegawaiorderfk = $this->getPegawaiId();
            $dataSO->objectpegawaispsfk = $PD['objectpegawairawatbersamafk'];
            $dataSO->objectkelasfk = $PD['objectkelasfk'];
            $dataSO->keteranganlainnya = $PD['catatan'];
            $dataSO->objectrekananfk = $PD['objectrekananfk'];
            $dataSO->iskelastitip = $PD['iskelastitip'];
            $dataSO->keterangaantrian = "registrasi";
            $dataSO->save();

            $dataSOnorec = $dataSO->norec;
            $dataOP = new OrderPelayanan();
            $dataOP->norec = $dataOP->generateNewId();
            $dataOP->kdprofile = $idProfile;
            $dataOP->statusenabled = true;
            $dataOP->iscito = false;
            $dataOP->qtyproduk = 1;
            $dataOP->qtyprodukretur = 1;
            $dataOP->noorderfk = $dataSOnorec;
            $dataOP->objectruangantujuanfk = $PD['objectruanganlastfk'];
            $dataOP->objectkelasfk = $PD['objectkelasrawatfk'];
            $dataOP->strukorderfk = $dataSOnorec;
            $dataOP->israwatgabung = $APD['israwatgabung'];
            $dataOP->objectkamarfk = $APD['objectkamarfk'];
            $dataOP->nobed = $APD['nobed'];
            $dataOP->save();


            $transStatus = true;
        } catch (Exception $e) {
            $transStatus = false;
        }

        if ($transStatus) {
            $transMessage = "Sukses";
            DB::commit();
            $result = array(
                "status" => 200,
                "result" => array(
                    "data" => $dataSO,
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

    public function SaveRencanaMutasiPindah(Request $request)
    {
        $idProfile = (int) $this->kdProfile;
        $PD = $request['pasiendaftar'];
        $APD = $request['antrianpasiendiperiksa'];
        $idkelrencanamutasi = $this->settingFix('kelompoktransaksiRencanaMutasi');
        $keltransaksi = DB::table('kelompoktransaksi_m')->where('id', $idkelrencanamutasi)->first();

        $dataPD = PasienDaftar::where('norec', $PD['norec_pd'])->first();
        $dataSO = StrukOrder::where('nocmfk', $dataPD->nocmfk)
            ->where('objectkelompoktransaksifk', $idkelrencanamutasi)
            ->whereNull('statusorder')
            ->where('statusenabled', true)
            ->first();
        if (!empty($dataSO)) {
            $transMessage = 'Pasien sudah dalam rencana mutasi';
            $result = array("status" => 400, 'message' => $transMessage, "result" => $dataSO);
            return $this->respond($result, $result['status'], $transMessage);
        }

        DB::beginTransaction();
        try {
            $noOrder = $this->generateCodeBySeqTable(new StrukOrder, 'noorderrencanamutasi', 11, 'MT' . date('ym'), $idProfile);
            if ($noOrder == '') {
                $transMessage = "Gagal mengumpukan data, Coba lagi.!";
                DB::rollBack();
                $result = array("status" => 400, "result" => null);
                return $this->respond($result['result'], $result['status'], $transMessage);
            }

            $dataSO = new StrukOrder;
            $dataSO->norec = $dataSO->generateNewId();
            $dataSO->kdprofile = $idProfile;
            $dataSO->statusenabled = true;
            $dataSO->nocmfk = $dataPD->nocmfk;

            $dataSO->isdelivered = 1;
            $dataSO->qtyjenisproduk = 1;
            $dataSO->qtyproduk = 1;
            $dataSO->totalbeamaterai = 0;
            $dataSO->totalbiayakirim = 0;
            $dataSO->totalbiayatambahan = 0;
            $dataSO->totaldiscount = 0;
            $dataSO->totalhargasatuan = 0;
            $dataSO->totalharusdibayar = 0;
            $dataSO->totalpph = 0;
            $dataSO->totalppn = 0;
            $dataSO->keteranganorder = $keltransaksi->kelompoktransaksi;
            $dataSO->objectkelompoktransaksifk = $keltransaksi->id;
            $dataSO->noorder = $noOrder;
            $dataSO->noorderintern = $noOrder;
            $dataSO->tglorder = $APD['tglkeluar'];
            $dataSO->noregistrasifk = $PD['norec_pd'];
            $dataSO->objectruanganfk = $PD['objectruanganasalfk'];
            $dataSO->objectruangantujuanfk = $PD['objectruangantujuanfk'];
            $dataSO->objectpegawaitujuanfk = $dataPD->objectpegawaifk;
            $dataSO->objectcarabayar_quofk = $dataPD->objectkelompokpasienlastfk;
            $dataSO->norec_apd = $APD['norec_apd'];
            $dataSO->objectkelasfk = $APD['objectkelasfk'];
            $dataSO->objectpegawaiorderfk = $this->getPegawaiId();
            $dataSO->keterangaantrian = "pindah ruangan";
            $dataSO->save();

            $dataSOnorec = $dataSO->norec;
            $dataOP = new OrderPelayanan();
            $dataOP->norec = $dataOP->generateNewId();
            $dataOP->kdprofile = $idProfile;
            $dataOP->statusenabled = true;
            $dataOP->iscito = false;
            $dataOP->qtyproduk = 1;
            $dataOP->qtyprodukretur = 1;
            $dataOP->noorderfk = $dataSOnorec;
            $dataOP->objectruangantujuanfk = $PD['objectruangantujuanfk'];
            $dataOP->objectkelasfk = $APD['objectkelasrawatfk'];
            $dataOP->strukorderfk = $dataSOnorec;
            $dataOP->israwatgabung = $APD['israwatgabung'];
            $dataOP->objectkamarfk = $APD['objectkamarfk'];
            $dataOP->nobed = $APD['objectbedfk'];
            $dataOP->save();

            $transStatus = true;
        } catch (Exception $e) {
            $transStatus = false;
        }

        if ($transStatus) {
            $transMessage = "Sukses";
            DB::commit();
            $result = array(
                "status" => 200,
                "result" => array(
                    "data" => $dataSO,
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

    public function SaveRencanaMutasiMutasi(Request $request)
    {
        $idProfile = (int) $this->kdProfile;
        $PD = $request['pasiendaftar'];
        $APD = $request['antrianpasiendiperiksa'];
        $idkelrencanamutasi = $this->settingFix('kelompoktransaksiRencanaMutasi');
        $keltransaksi = DB::table('kelompoktransaksi_m')->where('id', $idkelrencanamutasi)->first();
        $dataSO = StrukOrder::where('nocmfk', $PD['nocmfk'])
            ->where('objectkelompoktransaksifk', $idkelrencanamutasi)
            ->whereNull('statusorder')
            ->where('statusenabled', true)
            ->first();
        if (!empty($dataSO)) {
            $transMessage = 'Pasien sudah dalam rencana mutasi';
            $result = array("status" => 400, 'message' => $transMessage, "result" => $dataSO);
            return $this->respond($result, $result['status'], $transMessage);
        }

        $cekDepartemen = DB::table('pasiendaftar_t as pd')
            ->join('ruangan_m as ru', 'pd.objectruanganlastfk', 'ru.id')
            ->select('ru.objectdepartemenfk', 'ru.namaruangan', 'pd.noregistrasi')
            ->where('pd.norec', $PD['norec_pd'])
            ->where('pd.kdprofile', $this->kdProfile)
            ->first();

        $isIGD = $cekDepartemen->objectdepartemenfk == $this->settingFix('idDepartemenIGD') ? 'true' : 'false';
        $cekRI = null;
        if ($isIGD == 'false') {
            $cekRI = PasienDaftar::where('norec', $PD['norec_pd'])
                ->whereNull('tglpulang')
                ->where('statusenabled', true)
                ->first();
        }
        if (!empty($cekRI)) {
            DB::rollBack();
            $transMessage = 'Pasien Terdaftar di Rawat Inap No. Registrasi : '
                . $cekRI->noregistrasi . ' (' . $cekRI->tglregistrasi . ')';
            $result = array("status" => 400, "result" => $cekRI);
            return $this->respond($result['result'], $result['status'], $transMessage);
        }
        DB::beginTransaction();
        try {

            $noOrder = $this->generateCodeBySeqTable(new StrukOrder, 'noorderrencanamutasi', 11, 'MT' . date('ym'), $idProfile);
            if ($noOrder == '') {
                $transMessage = "Gagal mengumpukan data, Coba lagi.!";
                DB::rollBack();
                $result = array("status" => 400, "result" => null);
                return $this->respond($result['result'], $result['status'], $transMessage);
            }

            $dataSO = new StrukOrder;
            $dataSO->norec = $dataSO->generateNewId();
            $dataSO->kdprofile = $idProfile;
            $dataSO->statusenabled = true;
            $dataSO->nocmfk = $PD['nocmfk'];

            $dataSO->isdelivered = 1;
            $dataSO->qtyjenisproduk = 1;
            $dataSO->qtyproduk = 1;
            $dataSO->totalbeamaterai = 0;
            $dataSO->totalbiayakirim = 0;
            $dataSO->totalbiayatambahan = 0;
            $dataSO->totaldiscount = 0;
            $dataSO->totalhargasatuan = 0;
            $dataSO->totalharusdibayar = 0;
            $dataSO->totalpph = 0;
            $dataSO->totalppn = 0;
            $dataSO->keteranganorder = $keltransaksi->kelompoktransaksi;
            $dataSO->objectkelompoktransaksifk = $keltransaksi->id;
            $dataSO->noorder = $noOrder;
            $dataSO->noorderintern = $noOrder;
            $dataSO->tglorder = $APD['tglregistrasi'];
            $dataSO->noregistrasifk = $PD['norec_pd'];
            $dataSO->objectruanganfk = $APD['objectruanganasalfk'];
            $dataSO->objectruangantujuanfk = $PD['objectruangantujuanfk'];
            $dataSO->asalrujukanfk = $PD['asalrujukanfk'];
            $dataSO->jenispelayanan = $PD['jenispelayananfk'];
            $dataSO->objectcarabayar_quofk = $PD['objectkelompokpasienlastfk'];
            $dataSO->objectpegawaitujuanfk = $PD['objectpegawaifk'];
            $dataSO->objectpegawaiorderfk = $this->getPegawaiId();
            $dataSO->objectkelasfk = $PD['objectkelasfk'];
            $dataSO->norec_apd = $APD['norec_apd'];
            $dataSO->keterangaantrian = "mutasi";
            $dataSO->save();

            $dataSOnorec = $dataSO->norec;
            $dataOP = new OrderPelayanan();
            $dataOP->norec = $dataOP->generateNewId();
            $dataOP->kdprofile = $idProfile;
            $dataOP->statusenabled = true;
            $dataOP->iscito = false;
            $dataOP->qtyproduk = 1;
            $dataOP->qtyprodukretur = 1;
            $dataOP->noorderfk = $dataSOnorec;
            $dataOP->objectruangantujuanfk = $APD['objectruangantujuanfk'];
            $dataOP->objectkelasfk = $PD['objectkelasrawatfk'];
            $dataOP->strukorderfk = $dataSOnorec;
            $dataOP->israwatgabung = $APD['israwatgabung'];
            $dataOP->objectkamarfk = $APD['objectkamarfk'];
            $dataOP->nobed = $APD['objectbedfk'];
            $dataOP->save();

            $statusBedKosong = $this->settingFix('idStatusBedKosong');
            $statusBedIsi = $this->settingFix('idStatusBedIsi');
            $statusBedDipesan = $this->settingFix('idStatusBedDipesan');

            $cekBed = DB::table('tempattidur_m')
                ->where('kdprofile', $this->kdProfile)
                ->where('statusenabled', true)
                ->where('id', $APD['objectbedfk'])
                ->first();

            if (!empty($cekBed) && $cekBed->objectstatusbedfk == $statusBedIsi) {
                DB::rollBack();
                $transMessage = 'Bed Sudah Terisi, Silakan Pilih Bed Lain';
                $result = array(
                    "status" => 400,
                    'message' => $transMessage,
                    "result" => $cekBed
                )
                ;
                return $this->respond($result, $result['status'], $transMessage);
            }

            if (!empty($cekBed) && $cekBed->objectstatusbedfk == $statusBedDipesan) {
                DB::rollBack();
                $transMessage = 'Bed Sudah Dipesan, Silakan Pilih Bed Lain';
                $result = array(
                    "status" => 400,
                    'message' => $transMessage,
                    "result" => $cekBed
                )
                ;
                return $this->respond($result, $result['status'], $transMessage);
            }

            DB::table('tempattidur_m')
                ->where('kdprofile', $this->kdProfile)
                ->where('statusenabled', true)
                ->where('id', $APD['objectbedfk'])
                ->lockForUpdate()
                ->update(['objectstatusbedfk' => $statusBedDipesan]);

            $this->historyBED([
                "tempattidurfk" => $APD['objectbedfk'],
                "statusbedfk" => $statusBedDipesan,
                "ruanganfk" => $APD['objectruangantujuanfk'],
                "kamarfk" => $APD['objectkamarfk'],
            ]);

            $transStatus = true;
        } catch (Exception $e) {
            $transStatus = false;
        }

        if ($transStatus) {
            $transMessage = "Sukses";
            DB::commit();
            $result = array(
                "status" => 200,
                "result" => array(
                    "data" => $dataSO,
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

    public function UpdateStatusRencanaMutasi(Request $request)
    {
        DB::beginTransaction();
        try {
            if (isset($request['action']) && $request['action'] == 'terima') {
                StrukOrder::where('norec', $request['norec'])
                    ->where('kdprofile', $this->kdProfile)
                    ->update(
                        [
                            'statusorder' => 1
                        ]
                    );
            }

            if (isset($request['action']) && $request['action'] == 'tolak') {
                StrukOrder::where('norec', $request['norec'])
                    ->where('kdprofile', $this->kdProfile)
                    ->update(
                        [
                            'statusenabled' => false
                        ]
                    );
            }

            if (isset($request['action']) && $request['action'] == 'ubah') {
                StrukOrder::where('norec', $request['norec'])
                    ->where('kdprofile', $this->kdProfile)
                    ->update(
                        [
                            'statusenabled' => true,
                            'objectruangantujuanfk' => $request['objectruangantujuanfk'],
                            'objectkelasfk' => $request['objectkelasfk'],
                            'objectpegawaiorderfk' => $this->getPegawaiId(),
                        ]
                    );
                OrderPelayanan::where('strukorderfk', $request['norec'])
                    ->where('kdprofile', $this->kdProfile)
                    ->update(
                        [
                            'objectruangantujuanfk' => $request['objectruangantujuanfk'],
                            'objectkelasfk' => $request['objectkelasrawatfk'],
                            'objectkamarfk' => $request['objectkamarfk'],
                            'nobed' => $request['objectbedfk'],
                        ]
                    );
            }

            $transStatus = true;
        } catch (Exception $e) {
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
