<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use App\Models\Master\SettingDataFixed;
use App\Models\Transaksi\OrderPelayanan;
use App\Models\Transaksi\PasienDaftar;
use App\Models\Transaksi\StrukOrder;
use App\Models\Transaksi\StrukPelayanan;
use App\Models\Transaksi\StrukBuktiPenerimaan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use App\Traits\Valet;
use Exception;

class TagihanPasienCtrl extends Controller
{
    use Valet;
    public function __construct()
    {
        parent::__construct($is_encrypt = true);
    }
    // Tagihan Lunas
    public function DaftarTagihanLunas(Request $request)
    {
        $result = array();
        $filter = $request->all();
        $kdProfile =  $this->kdProfile;
        $dataStrukPelayanan = DB::table('strukpelayanan_t as sp')
            ->leftjoin('pasiendaftar_t as pd', 'pd.norec', '=', 'sp.noregistrasifk')
            ->leftjoin('pasien_m as p', 'p.id', '=', 'pd.nocmfk')
            ->leftJoin('ruangan_m as r', 'r.id', '=', 'pd.objectruanganlastfk')
            ->leftJoin('departemen_m as dept', 'dept.id', '=', 'r.objectdepartemenfk')
            ->leftJoin('kelompokpasien_m as kp', 'kp.id', '=', 'pd.objectkelompokpasienlastfk')
            ->leftJoin('kelas_m as k', 'k.id', '=', 'pd.objectkelasfk')
            ->select(
                'pd.noregistrasi',
                'p.nocm',
                'p.namapasien',
                'pd.tglregistrasi',
                'pd.tglpulang',
                'r.namaruangan',
                'kp.kelompokpasien',
                'sp.totalharusdibayar',
                'sp.norec',
                'sp.nostruk',
                'k.namakelas',
                'sp.tglstruk',
                'sp.totalprekanan',
                'r.id as ruanganId',
                'dept.id as departmentId'
            )
            ->where('sp.kdprofile', $kdProfile)
            ->whereNotNull('sp.nosbmlastfk');

        if (isset($filter['noReg']) && $filter['noReg'] != "" && $filter['noReg'] != "undefined") {
            $dataStrukPelayanan  = $dataStrukPelayanan->where('pd.noregistrasi', $filter['noReg']);
        }

        if (isset($filter['noRm']) && $filter['noRm'] != "" && $filter['noRm'] != "undefined") {
            $dataStrukPelayanan  = $dataStrukPelayanan->where('p.nocm', 'ilike', '%' . $filter['noRm'] . '%');
        }

        if (isset($filter['tglAwal']) && $filter['tglAwal'] != "" && $filter['tglAwal'] != "undefined") {
            $dataStrukPelayanan = $dataStrukPelayanan->where('sp.tglstruk', '>=', $filter['tglAwal']);
        }

        if (isset($filter['tglAkhir']) && $filter['tglAkhir'] != "" && $filter['tglAkhir'] != "undefined") {
            $tgl = $filter['tglAkhir']; //." 23:59:59";
            $dataStrukPelayanan = $dataStrukPelayanan->where('sp.tglstruk', '<=', $tgl);
        }

        if (isset($filter['instalasiId']) && $filter['instalasiId'] != "" && $filter['instalasiId'] != "undefined") {
            $dataStrukPelayanan = $dataStrukPelayanan->where('dept.id', '=', $filter['instalasiId']);
        }

        if (isset($filter['ruanganId']) && $filter['ruanganId'] != "" && $filter['ruanganId'] != "undefined") {
            $dataStrukPelayanan = $dataStrukPelayanan->where('r.id', '=', $filter['ruanganId']);
        }

        if (isset($filter['namaPasien']) && $filter['namaPasien'] != "" && $filter['namaPasien'] != "undefined") {
            $dataStrukPelayanan = $dataStrukPelayanan->where('p.namapasien', 'ilike', '%' . $filter['namaPasien'] . '%');
        }

        if (isset($filter['kelompokPasienId']) && $filter['kelompokPasienId'] != "" && $filter['kelompokPasienId'] != "undefined") {
            $dataStrukPelayanan = $dataStrukPelayanan->where('pd.objectkelompokpasienlastfk', '=', $filter['kelompokPasienId']);
        }


        if (isset($filter['status']) && $filter['status'] != "") {
            if ($filter['status'] == 'Lunas') {
                $dataStrukPelayanan  = $dataStrukPelayanan->whereNotNull('sp.nosbmlastfk');
            } else {
                $dataStrukPelayanan  = $dataStrukPelayanan->whereNull('sp.nosbmlastfk');
            }
        }
        $dataStrukPelayanan = $dataStrukPelayanan->take(50);
        $dataStrukPelayanan = $dataStrukPelayanan->whereRaw('(sp.statusenabled is null or sp.statusenabled =true)');
        $dataStrukPelayanan  = $dataStrukPelayanan->whereNotNull('sp.totalharusdibayar');
        $dataStrukPelayanan  = $dataStrukPelayanan->where('sp.totalharusdibayar', '<>', 0);
        $dataStrukPelayanan  = $dataStrukPelayanan->whereNotNull('sp.noregistrasifk');
        if (!empty($filter['tglAwal']) && !empty($filter['tglAkhir']) && empty($filter['noReg']) && empty($filter['noRm']) && empty($filter['status']) && empty($filter['ruanganId']) && empty($filter['namaPasien']) && empty($filter['instalasiId'])) {
            $dataStrukPelayanan = $dataStrukPelayanan->get();
        } else if (empty($filter['tglAwal']) && empty($filter['tglAkhir']) && empty($filter['noReg']) && empty($filter['noRm']) && empty($filter['status']) && empty($filter['ruanganId']) && empty($filter['namaPasien']) && empty($filter['instalasiId'])) {
            $dataStrukPelayanan = $dataStrukPelayanan->limit(10)->get();
        } else if (!empty($filter['tglAwal']) && !empty($filter['tglAkhir']) && empty($filter['noReg']) && empty($filter['noRm']) && empty($filter['status']) && empty($filter['ruanganId']) && empty($filter['namaPasien']) && $filter['instalasiId'] == "undefined") {
            $dataStrukPelayanan = $dataStrukPelayanan->limit(10)->get();
        } else {
            $dataStrukPelayanan    = $dataStrukPelayanan->get();
        }

        foreach ($dataStrukPelayanan as $key => $item) {
            $sp = StrukPelayanan::find($item->norec);
            $result[] = array(
                'noRec' => $item->norec,
                'tglStruk' => $item->tglstruk,
                'tglMasuk' => $item->tglregistrasi,
                'tglPulang' => $item->tglpulang,
                'noRegistrasi' => $item->noregistrasi,
                'namaPasien' => $item->namapasien,
                'noCm' => $item->nocm,
                'kelasRawat' => $item->namakelas,
                'lastRuangan' => $item->namaruangan,
                'jenisPasien' => $item->kelompokpasien,
                'kelasPenjamin' => "-",
                'totalBilling' => $item->totalharusdibayar,
                'totalKlaim' => $item->totalprekanan,
                'totalBayar' => $item->totalharusdibayar,
                'statusBayar' => $sp->statusBayar,
                'ruanganId' => $item->ruanganId,
                'departmentId' => $item->departmentId,
            );
        }
        return $this->respond($result);
    }

    // Tagihan Belum Lunas
    public function DaftarTagihanBelumLunas(Request $request)
    {
        $result = array();
        $filter = $request->all();
        $kdProfile =  $this->kdProfile;
        $dataStrukPelayanan = DB::table('strukpelayanan_t as sp')
            ->leftjoin('pasiendaftar_t as pd', 'pd.norec', '=', 'sp.noregistrasifk')
            ->leftjoin('pasien_m as p', 'p.id', '=', 'pd.nocmfk')
            ->leftJoin('ruangan_m as r', 'r.id', '=', 'pd.objectruanganlastfk')
            ->leftJoin('departemen_m as dept', 'dept.id', '=', 'r.objectdepartemenfk')
            ->leftJoin('kelompokpasien_m as kp', 'kp.id', '=', 'pd.objectkelompokpasienlastfk')
            ->leftJoin('kelas_m as k', 'k.id', '=', 'pd.objectkelasfk')
            ->select(
                'pd.noregistrasi',
                'p.nocm',
                'p.namapasien',
                'pd.tglregistrasi',
                'pd.tglpulang',
                'r.namaruangan',
                'kp.kelompokpasien',
                'sp.totalharusdibayar',
                'sp.norec',
                'sp.nostruk',
                'k.namakelas',
                'sp.tglstruk',
                'sp.totalprekanan',
                'r.id as ruanganId',
                'dept.id as departmentId'
            )
            ->where('sp.kdprofile', $kdProfile)
            ->whereNull('sp.nosbmlastfk');

        if (isset($filter['noReg']) && $filter['noReg'] != "" && $filter['noReg'] != "undefined") {
            $dataStrukPelayanan  = $dataStrukPelayanan->where('pd.noregistrasi', $filter['noReg']);
        }

        if (isset($filter['noRm']) && $filter['noRm'] != "" && $filter['noRm'] != "undefined") {
            $dataStrukPelayanan  = $dataStrukPelayanan->where('p.nocm', 'ilike', '%' . $filter['noRm'] . '%');
        }

        if (isset($filter['tglAwal']) && $filter['tglAwal'] != "" && $filter['tglAwal'] != "undefined") {
            $dataStrukPelayanan = $dataStrukPelayanan->where('sp.tglstruk', '>=', $filter['tglAwal']);
        }

        if (isset($filter['tglAkhir']) && $filter['tglAkhir'] != "" && $filter['tglAkhir'] != "undefined") {
            $tgl = $filter['tglAkhir']; //." 23:59:59";
            $dataStrukPelayanan = $dataStrukPelayanan->where('sp.tglstruk', '<=', $tgl);
        }

        if (isset($filter['instalasiId']) && $filter['instalasiId'] != "" && $filter['instalasiId'] != "undefined") {
            $dataStrukPelayanan = $dataStrukPelayanan->where('dept.id', '=', $filter['instalasiId']);
        }

        if (isset($filter['ruanganId']) && $filter['ruanganId'] != "" && $filter['ruanganId'] != "undefined") {
            $dataStrukPelayanan = $dataStrukPelayanan->where('r.id', '=', $filter['ruanganId']);
        }

        if (isset($filter['namaPasien']) && $filter['namaPasien'] != "" && $filter['namaPasien'] != "undefined") {
            $dataStrukPelayanan = $dataStrukPelayanan->where('p.namapasien', 'ilike', '%' . $filter['namaPasien'] . '%');
        }

        if (isset($filter['kelompokPasienId']) && $filter['kelompokPasienId'] != "" && $filter['kelompokPasienId'] != "undefined") {
            $dataStrukPelayanan = $dataStrukPelayanan->where('pd.objectkelompokpasienlastfk', '=', $filter['kelompokPasienId']);
        }


        if (isset($filter['status']) && $filter['status'] != "") {
            if ($filter['status'] == 'Lunas') {
                $dataStrukPelayanan  = $dataStrukPelayanan->whereNotNull('sp.nosbmlastfk');
            } else {
                $dataStrukPelayanan  = $dataStrukPelayanan->whereNull('sp.nosbmlastfk');
            }
        }
        $dataStrukPelayanan = $dataStrukPelayanan->take(50);
        $dataStrukPelayanan = $dataStrukPelayanan->whereRaw('(sp.statusenabled is null or sp.statusenabled =true)');
        $dataStrukPelayanan  = $dataStrukPelayanan->whereNotNull('sp.totalharusdibayar');
        $dataStrukPelayanan  = $dataStrukPelayanan->where('sp.totalharusdibayar', '<>', 0);
        $dataStrukPelayanan  = $dataStrukPelayanan->whereNotNull('sp.noregistrasifk');
        if (!empty($filter['tglAwal']) && !empty($filter['tglAkhir']) && empty($filter['noReg']) && empty($filter['noRm']) && empty($filter['status']) && empty($filter['ruanganId']) && empty($filter['namaPasien']) && empty($filter['instalasiId'])) {
            $dataStrukPelayanan = $dataStrukPelayanan->get();
        } else if (empty($filter['tglAwal']) && empty($filter['tglAkhir']) && empty($filter['noReg']) && empty($filter['noRm']) && empty($filter['status']) && empty($filter['ruanganId']) && empty($filter['namaPasien']) && empty($filter['instalasiId'])) {
            $dataStrukPelayanan = $dataStrukPelayanan->limit(10)->get();
        } else if (!empty($filter['tglAwal']) && !empty($filter['tglAkhir']) && empty($filter['noReg']) && empty($filter['noRm']) && empty($filter['status']) && empty($filter['ruanganId']) && empty($filter['namaPasien']) && $filter['instalasiId'] == "undefined") {
            $dataStrukPelayanan = $dataStrukPelayanan->limit(10)->get();
        } else {
            $dataStrukPelayanan    = $dataStrukPelayanan->get();
        }

        foreach ($dataStrukPelayanan as $key => $item) {
            $sp = StrukPelayanan::find($item->norec);
            $spj[] = array(
                'noRec' => $item->norec,
                'tglStruk' => $item->tglstruk,
                'tglMasuk' => $item->tglregistrasi,
                'tglPulang' => $item->tglpulang,
                'noRegistrasi' => $item->noregistrasi,
                'namaPasien' => $item->namapasien,
                'noCm' => $item->nocm,
                'kelasRawat' => $item->namakelas,
                'lastRuangan' => $item->namaruangan,
                'jenisPasien' => $item->kelompokpasien,
                'kelasPenjamin' => "-",
                'totalBilling' => $item->totalharusdibayar,
                'totalKlaim' => $item->totalprekanan,
                'totalBayar' => $item->totalharusdibayar,
                'statusBayar' => $sp->statusBayar,
                'ruanganId' => $item->ruanganId,
                'departmentId' => $item->departmentId,
            );
        }
        return $this->respond($spj);
    }

    public function getDaftarDepositPasien(Request $request)
    {

        $rangeDate = [$request->tglAwal, $request->tglAkhir];

        $datas = DB::table('strukpelayanan_t as sp')
            ->join('pasiendaftar_t as pd', 'sp.noregistrasifk', '=', 'pd.norec')
            ->join('pasien_m as pas', 'pas.id', '=', 'pd.nocmfk')
            ->join('strukbuktipenerimaan_t as sbm', 'sbm.nostrukfk', '=', 'sp.norec')
            ->join('pegawai_m as pg', 'pg.id', '=', 'sbm.objectpegawaipenerimafk')
            ->select(
                'pas.namapasien',
                'pas.nocm',
                'pg.namalengkap as kasir',
                'pas.id as nocmfk',
                // 'sp.nostruk',
                // 'sp.tglstruk',
                'pd.tglregistrasi',
                // 'sp.tglstruk',
                DB::raw("sum(sbm.totaldibayar) as totaldibayar"),
                'pd.noregistrasi',
                'pd.norec as norec_pd',
                // 'sp.nosbklastfk'
            )
            ->whereBetween(DB::raw("CAST(pd.tglregistrasi as DATE)"),$rangeDate)
            ->where('pd.kdprofile', $this->kdProfile)
            ->where('sp.statusenabled', true)
            ->where('sp.objectkelompoktransaksifk',  $this->kelompokTransaksi('PEMBAYARAN DEPOSIT PASIEN'))
            ->groupBy('pas.namapasien','pas.nocm','pg.namalengkap','pd.tglregistrasi','pd.noregistrasi','pd.norec', 'pas.id');

        if (isset($request['search']) && $request['search'] != '') {
            $searchTerm = '%' . $request['search'] . '%';
            $datas = $datas->where(function ($query) use ($searchTerm) {
                $query->where('pas.nocm', 'ilike', $searchTerm)
                    ->orWhere('pd.noregistrasi', 'ilike', $searchTerm)
                    ->orWhere('pas.namapasien', 'ilike', $searchTerm);
            });
        }

        $datas = $datas->get();
        // return $datas;
        $data = DB::table('strukpelayanan_t as sp')
            ->join('pasiendaftar_t as pd', 'sp.noregistrasifk', '=', 'pd.norec')
            ->join('strukbuktipenerimaan_t as sbm', 'sbm.nostrukfk', '=', 'sp.norec')
            ->join('pegawai_m as pg', 'pg.id', '=', 'sbm.objectpegawaipenerimafk')
            ->select(
                'pg.namalengkap as kasir',
                'sp.nostruk',
                'sp.tglstruk',
                'sbm.totaldibayar',
                'sbm.tglsbm',
                'sbm.nosbm',
                'pd.nocmfk',
                'pd.noregistrasi',
                'sp.tglstruk',
                'pd.norec as norec_pd',
                'sp.norec as norec_sp',
                'sp.nosbklastfk',
                DB::raw("'penerimaan' as jenis")
            )
            ->where('pd.kdprofile', $this->kdProfile)
            ->where('sp.statusenabled', true)
            ->whereBetween(DB::raw("CAST(pd.tglregistrasi as DATE)"), $rangeDate)
            ->where('sp.objectkelompoktransaksifk',   $this->kelompokTransaksi('PEMBAYARAN DEPOSIT PASIEN'))
            ->get();

        $data2 = DB::table('strukpelayanan_t as sp')
        ->join('pasiendaftar_t as pd', 'sp.noregistrasifk', '=', 'pd.norec')
            ->join('strukbuktipengeluaran_t as sbm', 'sbm.norec', '=', 'sp.nosbklastfk')
            ->join('pegawai_m as pg', 'pg.id', '=', 'sbm.objectpegawaipenerimafk')
            ->select(
                'pg.namalengkap as kasir',
                'sp.nostruk',
                'sp.tglstruk',
                'sbm.totaldibayar',
                'sbm.tglsbk as tglsbm',
                'sbm.nosbk as nosbm',
                'pd.noregistrasi',
                'sp.tglstruk',
                'pd.norec as norec_pd',
                'sp.norec as norec_sp',
                'sp.nosbklastfk',
                DB::raw("'pengembalian' as jenis")
            )
            ->where('pd.kdprofile', $this->kdProfile)
            ->where('sp.statusenabled', true)
            ->whereBetween(DB::raw("CAST(pd.tglregistrasi as DATE)"), $rangeDate)
            // ->where('pd.noregistrasi',  $r['noregistrasi'])
            ->where('sbm.objectkelompoktransaksifk',   $this->kelompokTransaksi('PENGEMBALIAN DEPOSIT PASIEN'))
            ->get();

        $arr = array_merge($data->toArray(), $data2->toArray());

        foreach($datas as $data){
            $data->details = [];
            $data->statuspengembalian = 'Belum Dikembalikan';
            foreach($arr as $item){
                 if($data->noregistrasi == $item->noregistrasi){
                    $data->details[] = $item;
                    if($item->jenis == 'pengembalian'){
                        $data->statuspengembalian = 'Sudah Dikembalikan';
                    }
                 }
            }

        }


        return $this->respond($datas);

    }


    // Detail Tagihan
    public function detailTagihanPasien(Request $request)
    {

        $kdProfile =  $this->kdProfile;
        $norec_pd = $request['norec_pd'];
        $dataStrukPelayanan = DB::table('strukpelayanan_t as sp')
            ->leftjoin('pasiendaftar_t as pd', 'pd.norec', '=', 'sp.noregistrasifk')
            ->leftjoin('pasien_m as p', 'p.id', '=', 'pd.nocmfk')
            ->leftJoin('ruangan_m as r', 'r.id', '=', 'pd.objectruanganlastfk')
            ->leftJoin('departemen_m as dept', 'dept.id', '=', 'r.objectdepartemenfk')
            ->leftJoin('kelompokpasien_m as kp', 'kp.id', '=', 'pd.objectkelompokpasienlastfk')
            ->leftJoin('kelas_m as k', 'k.id', '=', 'pd.objectkelasfk')
            ->select(
                'pd.noregistrasi',
                'p.nocm',
                'p.namapasien',
                'pd.tglregistrasi',
                'pd.tglpulang',
                'r.namaruangan',
                'kp.kelompokpasien',
                'sp.totalharusdibayar',
                'sp.norec',
                'sp.nostruk',
                'k.namakelas',
                'sp.tglstruk',
                'sp.totalprekanan',
                'r.id as ruanganId',
                'dept.id as departmentId'
            )
            ->where('sp.kdprofile', $kdProfile)
            ->where('sp.norec', $norec_pd)
            ->first();
        // $strukPelayanan = StrukPelayanan::where('norec', $norec_pd)->where('kdprofile', $kdProfile)->first();
        return $this->respond($dataStrukPelayanan);

    }

    public function detailBayaran(Request $request)
    {
        $kdProfile =  $this->kdProfile;
        $norec_pd = $request['norec_pd'];
        $strukPelayanan = StrukPelayanan::where('norec', $norec_pd)->where('kdprofile', $kdProfile)->first();
        if ($strukPelayanan) {
            //return notfound
        }
        $pelayanan_pasien = $strukPelayanan->pelayanan_pasien;
        $deposit = 0;
        $detailTagihan = array();

        foreach ($pelayanan_pasien as $value) {
            $harga = ($value->hargajual == null) ? 0 : $value->hargajual;
            $diskon = ($value->hargadiscount == null) ? 0 : $value->hargadiscount;
            if ($value->nilainormal == -1) {
                $deposit += $harga;
            } else {
                $detailTagihan[] = array(
                    'namaLayanan'  => $value->produk->namaproduk,
                    "ruangan" => @$value->antrian_pasien_diperiksa->ruangan->reportdisplay,
                    'jumlah'  => $value->jumlah,
                    'harga'  => $harga,
                    'diskon'  => $diskon,
                    'total'  => ($harga - $diskon) * $value->jumlah,
                );
            }
        }

        $noregistasi = $strukPelayanan->pasien_daftar->noregistrasi;
        $result = array(
            "noRegistrasi"  => $strukPelayanan->pasien_daftar->noregistrasi,
            "noCm"  => $strukPelayanan->pasien_daftar->pasien->nocm,
            "namaPasien"  => $strukPelayanan->pasien_daftar->pasien->namapasien,
            "jenisPenjamin"  => $strukPelayanan->pasien_daftar->kelompok_pasien->kelompokpasien,
            "jenisKelamin"  => $strukPelayanan->pasien_daftar->pasien->jenis_kelamin->jeniskelamin,
            "umur"  => $strukPelayanan->pasien_daftar->pasien->Umur,
            "totalDeposit"  => $this->getDepositPasien($noregistasi), // $deposit,
            "jumlahBayar"  => $strukPelayanan->totalharusdibayar, //+ $this->getDepositPasien($noregistasi),
            "totalPenjamin" => ($strukPelayanan->totalprekanan == null) ? 0 : $strukPelayanan->totalprekanan,
            "detailTagihan"  => $detailTagihan,

        );
        return $this->respond($result, "Detail Tagihan Pasien");
    }
    public function closePemeriksaanPD(Request $request){
        $kdProfile = $this->kdProfile;
        $transStatus = 'true';
        DB::beginTransaction();
        try {
            $status = true;
            $msg = '';
            $tglClose = date('Y-m-d H:i:s');
            if($request['close'] ==false){
                $status = null;
                $tglClose = null;
                $msg = 'Batal';
            }
            $data = PasienDaftar::where('kdprofile',$kdProfile)->where('noregistrasi', $request->noregistrasi)->first();
            $data->isclosing = $status;
            $data->tglclosing = $tglClose;
            $data->save();
            $transMessage = $msg. " Closing Pemeriksaan Berhasil";
        } catch (Exception $e) {
            $transStatus = 'false';
            $transMessage = "Closing Pemeriksaan Gagal";
        }

        if ($transStatus != 'false') {
            DB::commit();
            $result = [
                "status" => 200,
                "message" => $transMessage,
                "result" => $data
            ];
        } else {
            DB::rollBack();
            $result = [
                "status" => 400,
                "result" =>[],
                "message" => $transMessage .PHP_EOL .$e->getMessage() .$e->getLine(),
            ];
        }
        return $this->respond($result['result'], $result['status'], $result['message']);
    }

    public function deleteDeposit(Request $request){
        $kdProfile = $this->kdProfile;
        $transStatus = 'true';
        DB::beginTransaction();
        try {
            $data = StrukPelayanan::where('kdprofile',$kdProfile)->where('norec', $request->norec)->first();
            $data->statusenabled = false;
            $data->save();

            $datas = StrukBuktiPenerimaan::where('kdprofile',$kdProfile)->where('nostrukfk', $request->norec)->first();
            $datas->statusenabled = false;
            $datas->save();

            $transMessage = "Hapus Deposit Berhasil";
        } catch (Exception $e) {
            $transStatus = 'false';
            $transMessage = "Hapus Deposit Gagal";
        }

        if ($transStatus != 'false') {
            DB::commit();
            $result = [
                "status" => 200,
                "message" => $transMessage,
                "result" => $data
            ];
        } else {
            DB::rollBack();
            $result = [
                "status" => 400,
                "result" =>[],
                "message" => $transMessage .PHP_EOL .$e->getMessage() .$e->getLine(),
            ];
        }
        return $this->respond($result['result'], $result['status'], $result['message']);
    }

    public function listTindakanBelumVerifikasi(Request $r){
        $kdProfile = $this->kdProfile;
        $data = DB::table('pelayananpasien_t as pp')
            ->join('antrianpasiendiperiksa_t as apd', 'apd.norec', '=', 'pp.noregistrasifk')
            ->join('kelas_m as kls', 'kls.id', '=', 'apd.objectkelasfk')
            ->join('produk_m as prd', 'prd.id', '=', 'pp.produkfk')
            ->join('ruangan_m as ru', 'ru.id', '=', 'apd.objectruanganfk')
            ->leftJOIN('strukresep_t as sr', 'sr.norec', '=', 'pp.strukresepfk')
            ->leftJOIN('pegawai_m as pg', 'pg.id', '=', 'sr.penulisresepfk')
            ->select(
                'pp.norec',
                'prd.namaproduk',
                'kls.namakelas',
                'pp.tglpelayanan',
                'ru.namaruangan',
                'pp.strukresepfk',
                'pp.jumlah',
                'pp.hargasatuan',
                'pg.namalengkap as penulisresep',
                'apd.norec as norec_apd',
            )
            ->where('pp.statusenabled', true)
            ->where('pp.kdprofile', $kdProfile)
            ->whereNull('pp.strukfk')
            ->where('apd.noregistrasifk', $r['norec_pd']);
        $data = $data->orderByDesc('pp.tglpelayanan');
        $data = $data->get();
        return $this->respond($data);
    }

    public function listTindakanPasien(Request $r){
        $kdProfile = $this->kdProfile;
        $data = DB::table('pelayananpasien_t as pp')
            ->join('antrianpasiendiperiksa_t as apd', 'apd.norec', '=', 'pp.noregistrasifk')
            ->join('kelas_m as kls', 'kls.id', '=', 'apd.objectkelasfk')
            ->join('produk_m as prd', 'prd.id', '=', 'pp.produkfk')
            ->join('ruangan_m as ru', 'ru.id', '=', 'apd.objectruanganfk')
            ->leftJOIN('strukresep_t as sr', 'sr.norec', '=', 'pp.strukresepfk')
            ->leftJOIN('pegawai_m as pg', 'pg.id', '=', 'sr.penulisresepfk')
            ->select(
                'pp.norec',
                'prd.namaproduk',
                'kls.namakelas',
                'pp.tglpelayanan',
                'ru.namaruangan',
                'pp.strukresepfk',
                'pp.jumlah',
                'pp.hargasatuan',
                'pg.namalengkap as penulisresep',
                'apd.norec as norec_apd',
            )
            ->where('pp.statusenabled', true)
            ->where('pp.kdprofile', $kdProfile)
            ->where('apd.noregistrasifk', $r['norec_pd']);
        $data = $data->orderByDesc('pp.tglpelayanan');
        $data = $data->get();
        return $this->respond($data);
    }
}
