<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use App\Models\Master\KelompokPasien;
use App\Models\Master\Rekanan;
use App\Models\Master\SettingDataFixed;
use App\Models\Transaksi\OrderPelayanan;
use App\Models\Transaksi\PasienDaftar;
use App\Models\Transaksi\StrukBuktiPenerimaan;
use App\Models\Transaksi\StrukBuktiPenerimaanCaraBayar;
use App\Models\Transaksi\StrukOrder;
use App\Models\Transaksi\StrukPelayanan;
use App\Models\Transaksi\StrukPelayananPenjamin;
use App\Models\Transaksi\StrukVerifikasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use App\Traits\Valet;
use Exception;
use LDAP\Result;
use Psy\Command\HistoryCommand;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PiutangPasien;
use App\Models\Master\Profile;

class PiutangPasienCtrl extends Controller
{
    use Valet;
    public function __construct()
    {
        parent::__construct($is_encrypt = true);
    }


    public function daftarPiutangPasien(Request $request)
    {
        $rangeDate = [$request->tglAwal, $request->tglAkhir];
        if(isset($request['export']) && $request['export'] == 'excel') {
            $profile = Profile::where('id', $this->kdProfile)->first();
            if(isset($request['allPeriode']) && $request['allPeriode'] == 'true') $rangeDate = ['-', '-'];
            $file = Excel::download(new PiutangPasien($request, $profile, $rangeDate), date('YmdHis').'-daftarpasienpiutang.xlsx');
            return $file;
        }

        $dataPiutang = DB::table('pasiendaftar_t as pd')
            ->join('antrianpasiendiperiksa_t as ap', 'pd.norec', '=', 'ap.noregistrasifk')
            ->join('pelayananpasien_t as pp', 'ap.norec', '=', 'pp.noregistrasifk')
            ->leftjoin('strukpelayanan_t as sp', 'pp.strukfk', '=', 'sp.norec')
            ->leftjoin('rekanan_m as rkn', 'rkn.id', '=', 'pd.objectrekananfk')
            ->join('pasien_m as p', 'p.id', '=', 'pd.nocmfk')
            ->join('jeniskelamin_m as jk', 'jk.id', '=', 'p.objectjeniskelaminfk')
            ->join('statuspiutang_m as stp', 'stp.id', '=', 'pd.objectstatuspiutangfk')
            ->leftJoin('ruangan_m as ru', 'ru.id', '=', 'pd.objectruanganlastfk')
            ->leftJoin('departemen_m as dept', 'dept.id', '=', 'ru.objectdepartemenfk')
            ->leftJoin('kelompokpasien_m as kp', 'kp.id', '=', 'pd.objectkelompokpasienlastfk')
            ->leftJoin('strukbuktipenerimaan_t as sbm', 'sbm.nostrukfk', '=', 'sp.norec')
            ->select(
                'kp.kelompokpasien',
                'sp.tglstruk',
                'pd.noregistrasi',
                'pd.tglregistrasi',
                'p.nocm',
                'p.namapasien',
                DB::raw("totaldibayar"),
                'jk.jeniskelamin',
                'jk.id as jkid',
                'pd.norec as norec_pd',
                'sp.norec',
                'sp.totalharusdibayar',
                'sp.totaliurbayar',
                'pd.tglpulang',
                'pd.nocmfk',
                'pd.nostruklastfk',
                'pd.nosbmlastfk',
                'sbm.norec as norec_sbm',
                'sbm.tglsbm as tgldibayar'
            )
            ->where('pd.statusenabled', true)
            ->where('sbm.statusenabled', true)
            ->where('sp.statusenabled', true)
            //->where('kp.id', 1)
            //->whereDate('pd.tglpulang', '<', date('Y-m-d'))
            //->whereNull('pd.nosbmlastfk')
            ->whereNotNull('pd.objectstatuspiutangfk')
            // ->whereBetween(DB::raw("pd.tglpulang::date"), $rangeDate)
            ->where('pd.kdprofile', $this->kdProfile);
        
        if(!isset($request['allPeriode']) || $request['allPeriode'] != 'true') {
            $dataPiutang = $dataPiutang->whereBetween(DB::raw("pd.tglpulang::date"), $rangeDate);
        }
        if ($request['status']) {
            if ($request['status'] == '1') {
                $dataPiutang = $dataPiutang->whereNotNull('pd.nostruklastfk');
            }
            // if ($request['status'] == '2') {
            //     $dataPiutang = $dataPiutang->whereNull('pd.nostruklastfk');
            // }
        }

        if (isset($request['klmpasien']) && $request['klmpasien'] != "") {
            $dataPiutang = $dataPiutang->where('kp.id', '=', $request['klmpasien']);
        }

        if (isset($request['namaPasien']) && $request['namaPasien'] != "") {
            $dataPiutang = $dataPiutang->where('p.namapasien', 'ilike', '%' . $request['namaPasien'] . '%');
        }

        if (isset($request['norm']) && $request['norm'] != "") {
            $dataPiutang = $dataPiutang->where('p.nocm', 'ilike', '%' . $request['norm'] . '%');
        }

        if (isset($request['noregis']) && $request['noregis'] != "") {
            $dataPiutang = $dataPiutang->where('pd.noregistrasi', '=', $request['noregis']);
        }
        if (isset($request['search']) && $request['search'] != '') {
            $filter = true;
            $searchTerm = '%' . $request['search'] . '%';
            $dataPiutang = $dataPiutang->where(function ($query) use ($searchTerm) {
                $query->where('p.namapasien', 'ilike', $searchTerm)
                    ->orWhere('pd.noregistrasi', 'ilike', $searchTerm)
                    ->orWhere('p.nocm', 'ilike', $searchTerm);
            });
        }

        $dataPiutang = $dataPiutang->groupBy(
            'kp.kelompokpasien',
            'sp.tglstruk',
            'pd.noregistrasi',
            'pd.tglregistrasi',
            'p.nocm',
            'p.namapasien',
            'jk.jeniskelamin',
            'jk.id',
            'norec_pd',
            'pd.tglpulang',
            'sp.norec',
            'sp.totalharusdibayar',
            'sp.totaliurbayar',
            'pd.nocmfk',
            'pd.nostruklastfk',
            'pd.nosbmlastfk',
            'sbm.norec',
            'sbm.tglsbm'
        );
        $sqlPiutang = $dataPiutang->clone();
        $total = DB::connection()->table(DB::raw("({$sqlPiutang->toSql()}) as sub"))
            ->mergeBindings($sqlPiutang)
            ->count();
        if (isset($request['offset']) && $request['offset'] != '') {
            $dataPiutang = $dataPiutang->offset($request['offset']);
        }
        if (isset($request['limit']) && $request['limit'] != '') {
            $dataPiutang = $dataPiutang->limit($request['limit']);
        }
        // $dataPiutang = $dataPiutang->orderBy('p.namapasien');
        $dataPiutang = $dataPiutang->orderBy('sp.tglstruk', 'desc');
        $dataPiutang = $dataPiutang->get();
        // return $dataPiutang->toSql();
        // $total = count($dataPiutang);
        $result = [];
        foreach ($dataPiutang as $key => $item) {
            if ($item->nostruklastfk != null) {
                $statusVerifikasi = "Verifikasi";
                $isVerified = true;
            } else {
                $statusVerifikasi = "Belum Diverifikasi";
                $isVerified = false;
            }
            if ($item->nosbmlastfk != null) {
                $statusClosing = "Sudah Bayar";
            } else {
                $statusClosing = "Belum Bayar";
            }

            $sisaPiutang = $item->totalharusdibayar - $item->totaldibayar + ($item->totaliurbayar != 0 ? $item->totaliurbayar : 0);
            if($statusClosing == "Sudah Bayar" && $sisaPiutang > 0){
                $statusPiutang = "Belum Lunas";
            }else if($statusClosing == "Sudah Bayar" && $sisaPiutang == 0){
                $statusPiutang = "Lunas";
            }else{
                $statusPiutang = "Belum Bayar";
            }


            $result[] = array(
                'tglTransaksi' => $item->tglstruk,
                'noRegistrasi' => $item->noregistrasi,
                'nocm' => $item->nocm,
                'namaPasien' => $item->namapasien,
                'jeniskelamin' => $item->jeniskelamin,
                'kdJenisKelamin' => $item->jkid == 1 ? 'L' : 'P',
                'kelasRawat' => '-',
                'jenisPasisen' => $item->kelompokpasien,
                'kelasPenjamin' => "-",
                'totalDibayar' => $item->totaldibayar,
                'totalHarusDibayar' => $item->totalharusdibayar,
                'sisa' => $sisaPiutang,
                'statusVerifikasi' => $statusVerifikasi,
                'statusClosing' => $statusClosing,
                'colorVerif' => $isVerified ? 'green' : 'orange',
                'colorPiutang' => $statusPiutang == "Belum Lunas" ? 'orange' : ($statusPiutang == "Lunas" ? 'green' : 'danger'),
                'norec_pd' => $item->norec_pd,
                'norec' => $item->norec,
                'nocmfk' => $item->nocmfk,
                'tglpulang' => $item->tglpulang,
                'isVerified' => $isVerified,
                'tglregistrasi' => $item->tglregistrasi,
                'statuspiutang' => $statusPiutang,
                'tgldibayar' => $item->tgldibayar
            );
        }

        // return $this->respond($result);
        $datadata = array(
            'data' =>   $result,
            'total' => $total,
        );
        return $this->respond($datadata);
    }

    public function daftarPiutang(Request $request)
    {
        $filter = $request->all();
        $dataPiutang = DB::table('strukpelayananpenjamin_t as spp')
            ->join('strukpelayanan_t as sp', 'sp.norec', '=', 'spp.nostrukfk')
            ->join('pasien_m as p', 'p.id', '=', 'sp.nocmfk')
            ->join('pasiendaftar_t as pd', 'sp.noregistrasifk', '=', 'pd.norec')
            ->join('ruangan_m as ru', 'ru.id', '=', 'pd.objectruanganlastfk')
            ->leftjoin('rekanan_m as rkn', 'rkn.id', '=', 'pd.objectrekananfk')
            ->join('kelompokpasien_m as kp', 'kp.id', '=', 'pd.objectkelompokpasienlastfk')
            ->leftjoin('postinghutangpiutang_t as php', 'php.nostrukfk', '=', 'spp.norec')
            ->leftjoin('strukposting_t as spt', 'spt.noposting', '=', 'php.noposting')
            ->select(
                'kp.kelompokpasien',
                'spp.norec',
                'pd.tglpulang as tglstruk',
                'pd.noregistrasi',
                'pd.tglregistrasi',
                'p.nocm',
                'p.namapasien',
                'ru.namaruangan',
                'spp.totalppenjamin',
                'spp.totalharusdibayar',
                'spp.totalsudahdibayar',
                'spp.totalbiaya',
                'spp.noverifikasi',
                'rkn.namarekanan',
                'php.noposting',
                'spt.statusenabled',
                'pd.norec as norec_pd',
                'php.statusenabled as sttts'
            )
            ->where('spp.kdprofile', $this->kdProfile)
            ->whereNotNull('spp.noverifikasi')
            ->where('sp.statusenabled', true)
            ->get();
        return $this->respond($dataPiutang);

        if (isset($filter['tglAwal']) && $filter['tglAwal'] != "") {
            $dataPiutang = $dataPiutang->where('pd.tglpulang', '>=', $filter['tglAwal']);
        }

        if (isset($filter['tglAkhir']) && $filter['tglAkhir'] != "") {
            $tgl = $filter['tglAkhir'] . " 23:59:59";
            $dataPiutang = $dataPiutang->where('pd.tglpulang', '<=', $tgl);
        }

        if (isset($filter['kelompokpasienfk']) && $filter['kelompokpasienfk'] != "") {
            $dataPiutang = $dataPiutang->where('pd.objectkelompokpasienlastfk', '=', $filter['kelompokpasienfk']);
        }

        if (isset($filter['penjaminID']) && $filter['penjaminID'] != "") {
            $dataPiutang = $dataPiutang->where('pd.objectkelompokpasienlastfk', '=', $filter['penjaminID']);
        }
        if (isset($filter['rekananfk']) && $filter['rekananfk'] != "") {
            $dataPiutang = $dataPiutang->where('pd.objectrekananfk', '=', $filter['rekananfk']);
        }

        if (isset($filter['ruanganId']) && $filter['ruanganId'] != "") {
            $dataPiutang = $dataPiutang->where('ru.id', '=', $filter['ruanganId']);
        }
        if (isset($filter['namaPasien']) && $filter['namaPasien'] != "") {
            $dataPiutang = $dataPiutang->where('p.namapasien', 'ilike', '%' . $filter['namaPasien'] . '%');
        }
        if (isset($filter['noregistrasi']) && $filter['noregistrasi'] != "") {
            $dataPiutang = $dataPiutang->where('pd.noregistrasi', 'ilike', '%' . $filter['noregistrasi'] . '');
        }
        if (isset($filter['nocm']) && $filter['nocm'] != "") {
            $dataPiutang = $dataPiutang->where('p.nocm', '=', $filter['nocm']);
        }
        if (isset($filter['jmlRows']) && $filter['jmlRows'] != "" && $filter['jmlRows'] != "undefined") {
            $dataPiutang = $dataPiutang->take($filter['jmlRows']);
        }
        $dataPiutang = $dataPiutang->orderBy('pd.tglpulang');
        $dataPiutang = $dataPiutang->get();
        $result = array();
        $no = 1;
        foreach ($dataPiutang as $item) {
            if ($item->statusenabled ==  1 || is_null($item->statusenabled)) {
                if ($item->sttts == 1 || is_null($item->sttts)) {
                    if ($item->totalppenjamin > $item->totalsudahdibayar) {
                        if (!isset($item->noposting)) {
                            $status = 'Piutang';
                        } else {
                            $status = 'Collecting';
                        }
                    } else {
                        $status = 'Lunas';
                    }

                    $result[] = array(
                        'no' => $no++,
                        'noRec' => $item->norec,
                        'tglTransaksi' => $item->tglstruk,
                        'noRegistrasi' => $item->noregistrasi,
                        'namaPasien' => $item->namapasien,
                        'ruangan' => $item->namaruangan,
                        'kelasRawat' => $item->kelompokpasien,
                        'jenisPasien' => $item->kelompokpasien,
                        'umur' => $this->hitungUmur($item->tglstruk),
                        'kelasPenjamin' => "-",
                        'totalBilling' => $item->totalbiaya,
                        'totalKlaim' => $item->totalppenjamin,
                        'totalBayar' => $item->totalsudahdibayar,
                        'rekanan' => $item->namarekanan,
                        'status' => $status,
                        'norec_pd' => $item->norec_pd,
                        'noposting' => $item->noposting,
                        'stts' => $item->statusenabled,
                    );
                }
            }
        }
        return $this->respond($result,);
    }

    public function simpanUpdateRekananPD(Request $request)
    {
        DB::beginTransaction();
        try {
            PasienDaftar::where('norec', $request['norec_pd'])
                ->update(
                    [
                        'objectrekananfk' => $request['objectrekananfk'],
                        'objectkelompokpasienlastfk' => $request['objectkelompokpasienlastfk'],
                    ]
                );
            DB::commit();
            $result = array(
                "status" => 200,
                "message" => "Update Rekanan berhasil",
            );
        } catch (Exception $e) {
            DB::rollBack();
            $result = array(
                "status" => 400,
                "message" => "Something Went Wrong",
                "data" => $e->getMessage()
            );
        }

        return $this->respond($result, $result['status'], $result['message']);
    }

    public function verifyPiutangPasien(Request $request)
    {
        DB::beginTransaction();
        try {
            $verifikasi = new StrukVerifikasi();
            $noVerif = $this->generateCode(new StrukVerifikasi, 'noverifikasi', 12, 'VP' . $this->getDateTime()->format('dmy'), $this->kdProfile);
            $verifikasi->norec = $verifikasi->generateNewId();
            $verifikasi->kdprofile = $this->kdProfile;
            $verifikasi->objectkelompoktransaksifk = 1; ///ambil dari datafixed pastinya
            $verifikasi->objectpegawaipjawabfk = 1;
            $verifikasi->objectruanganfk = 1; //ambil dari pegawai yang ada ruangankerja
            $verifikasi->namaverifikasi = "Verifikasi Piutang Penjamin";
            $verifikasi->noverifikasi = $noVerif; //$this->generateCode(new StrukVerifikasi, 'noverifikasi', 10, 'VP');
            $verifikasi->tglverifikasi = $this->getDateTime();
            $verifikasi->save();

            $strukPelayanan = StrukPelayananPenjamin::where('norec', $request['norec'])
                ->update(['noverifikasi' => $verifikasi->noverifikasi]);

            DB::commit();
            $result = array(
                'status' => 201,
                'message' => "Verifikasi Piutang Berhasil",
                'result' => $verifikasi,
            );
        } catch (Exception $e) {
            DB::rollBack();
            $result = array(
                'status' => 400,
                'message'  =>  "Verifikasi Piutang Gagal",
                'result' => $e->getMessage(),
            );
        }

        return $this->respond($result, $result['status'], $result['message']);
    }

    public function cancelVerifyPiutangPasien(Request $request)
    {
        DB::beginTransaction();
        try {
            $strukPelayanan = StrukPelayananPenjamin::where('norec', $request['norec'])
                ->where('kdprofile', $this->kdProfile)
                ->update(['noverifikasi' => null]);
            DB::commit();
            $result = array(
                'status' => 201,
                'message' =>  "Unverifikasi Piutang Berhasil",
                'result' => $strukPelayanan,
            );
        } catch (Exception $e) {
            DB::rollBack();
            $result = array(
                'status' => 400,
                'message'  => "Unverifikasi Piutang Gagal",
                'result' => $e->getMessage(),
            );
        }

        return $this->respond($result, $result['status'], $result['message']);
    }

    public function editRekanan(Request $request)
    {
        DB::beginTransaction();

        try {
            PasienDaftar::where('norec', $request['norec_pd'])
                ->update(
                    [
                        'objectrekananfk' => $request['objectrekananfk'],
                        'objectkelompokpasienlastfk' => $request['objectkelompokpasienlastfk'],
                    ]
                );

            DB::commit();
            $result = array(
                "status" => 200,
                "message" =>  "Update Rekanan berhasil",
            );
        } catch (Exception $e) {
            $result = array(
                "status" => 400,
                "message" =>  "Somthing Went Wrong",
                "result" => $e->getMessage()
            );
        }

        return $this->respond($result, $result['status'], $result['message']);
    }

    public function loadListData()
    {

        $result['kelompokpasien'] = KelompokPasien::where('statusenabled', true)
            ->where('kdprofile', $this->kdProfile)
            ->mine()
            ->get();

        $result['rekanan'] = Rekanan::where('statusenabled', true)
            ->where('kdprofile', $this->kdProfile)
            ->mine()
            ->get();

        return $this->respond($result);
    }


    public function detailPiutangPasien(Request $request)
    {
        $spp = StrukPelayananPenjamin::where('norec', $request->norec_spp)
            ->where('kdprofile', $this->kdProfile)
            ->where('statusenabled', true)->first();
        $sbp = StrukBuktiPenerimaan::where('nostrukfk', $spp->nostrukfk)
            ->where('kdprofile', $this->kdProfile)
            ->where('statusenabled', true)
            ->orderBy('nosbm')->get();

        $detailPembayaran = array();
        foreach ($sbp as $item) {
            $detailPembayaran[] = array(
                'noSbm' => $item->nosbm,
                'tglPembayaran' => $item->tglsbm,
                'jlhPembayaran' => $item->totaldibayar
            );
        }
        $dibayarAwal = $spp->totalbiaya - $spp->totalppenjamin;
        $data = array(
            "totalTagihan" => $spp->totalbiaya,
            "sudahDibayar" => $spp->totalsudahdibayar + $dibayarAwal,
            "sisaPiutang" => $spp->totalbiaya - $dibayarAwal - $spp->totalsudahdibayar,
            "detailPembayaran" => $detailPembayaran
        );

        return $this->respond($data);
    }
}
