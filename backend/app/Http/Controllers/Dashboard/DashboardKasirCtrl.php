<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Master\KelompokPasien;
use App\Models\Master\Ruangan;
use App\Models\Transaksi\OrderPelayanan;
use App\Models\Transaksi\PasienDaftar;
use App\Models\Transaksi\PelayananPasien;
use App\Models\Transaksi\PelayananPasienDetail;
use App\Models\Transaksi\StrukBuktiPenerimaan;
use App\Models\Transaksi\StrukBuktiPengeluaran;
use App\Models\Transaksi\StrukOrder;
use App\Models\Transaksi\StrukPelayanan;
use App\Models\Transaksi\StrukPelayananPenjamin;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Date;
use App\Traits\Valet;
use Illuminate\Support\Facades\DB;

class DashboardKasirCtrl extends Controller
{
    use Valet;
    public function __construct()
    {
        parent::__construct($is_encrypt = true);
    }


    public function listTagihanPasien(Request $r)
    {
        $kdProfile =  $this->kdProfile;
        $data = DB::table('strukpelayanan_t as sp')
            ->JOIN('pasiendaftar_t as pd', 'pd.norec', '=', 'sp.noregistrasifk')
            ->JOIN('pasien_m as p', 'p.id', '=', 'pd.nocmfk')
            ->JOIN('ruangan_m as r', 'r.id', '=', 'pd.objectruanganlastfk')
            ->JOIN('departemen_m as dept', 'dept.id', '=', 'r.objectdepartemenfk')
            ->JOIN('kelompokpasien_m as kp', 'kp.id', '=', 'pd.objectkelompokpasienlastfk')
            ->JOIN('kelas_m as k', 'k.id', '=', 'pd.objectkelasfk')
            ->select(
                'pd.norec as norec_pd',
                'pd.noregistrasi',
                'p.nocm',
                'p.namapasien',
                'pd.tglregistrasi',
                'pd.tglpulang',
                'r.namaruangan',
                'kp.kelompokpasien',
                'sp.totalharusdibayar',
                'sp.totaliurbayar',
                'sp.norec',
                'sp.nostruk',
                'k.namakelas',
                'sp.tglstruk',
                'sp.totalprekanan',
                'r.id as ruanganId',
                'dept.id as departmentId',
                'pd.tglpulang',
                DB::raw("case when sp.nosbmlastfk is not null
                or sp.nosbklastfk is not null
                then 'Lunas' else 'Belum Lunas' end
                as statusbayar ,
                sp.totalharusdibayar + ( COALESCE (sp.totaliurbayar, 0)  ) as  totalharusdibayar")
            )
            ->where('sp.kdprofile', $kdProfile)
            ->where('sp.statusenabled', true)
            ->whereNotNull('sp.totalharusdibayar')
            ->whereRaw("( sp.totalharusdibayar != 0 or sp.totaliurbayar != 0 )  ");

            $filter = false;

        if (isset($r['status']) && $r['status'] != "") {
            $filter = true;
            $data = $data->where('pd.statusbayar', '=', $r['status']);
        }
        if (isset($r['dari']) && $r['dari'] != '') {
            $filter = true;
            $data = $data->where(DB::raw("pd.tglpulang::date"), '>=', $r->dari);
        }
        if (isset($r['sampai']) && $r['sampai'] != '') {
            $filter = true;
            $data = $data->where(DB::raw("pd.tglpulang::date"), '<=', $r->sampai);
        }

        if (isset($r['search']) && $r['search'] != '') {
            $filter = true;
            $searchTerm = '%' . $r['search'] . '%';
            $data = $data->where(function ($query) use ($searchTerm) {
                $query->where('p.namapasien', 'ilike', $searchTerm)
                    ->orWhere('pd.noregistrasi', 'ilike', $searchTerm)
                    ->orWhere('p.nocm', 'ilike', $searchTerm)
                    ->orWhere('p.noidentitas', 'ilike', $searchTerm);
            });
        }

        $data =  $data->get();

        return $this->respond($data);
    }

    // Tagihan Non Layanan

    public function TagihanNonLayanan(Request $request)
    {
        $kdProfile =  $this->kdProfile;
        $filter = $request->all();
        $list = explode(',', $this->settingFix('kdTransaksiNonLayanan', $kdProfile));
        $KdList = [];
        foreach ($list as $item) {
            $KdList[] =  (int)$item;
        }
        $datakelompokuser = DB::table('loginuser_s as lu')
            ->select('lu.objectkelompokuserfk')
            ->where('lu.kdprofile', $kdProfile)
            ->where('lu.id', '=', $filter['userData']['id'])
            ->get();

        if ($datakelompokuser[0]->objectkelompokuserfk == 58) {
            $dataNonLayanan = DB::table('strukpelayanan_t as sp')
                ->join('kelompoktransaksi_m as kt', 'sp.objectkelompoktransaksifk', '=', 'kt.id')
                ->select(
                    'sp.norec',
                    'sp.tglstruk',
                    'sp.namapasien_klien',
                    'kt.reportdisplay as jenistagihan',
                    'keteranganlainnya',
                    'sp.nosbklastfk',
                    'sp.nosbmlastfk',
                    'kt.id as jenisTagihanId',
                    DB::raw("CAST(sp.totalharusdibayar) AS totalharusdibayar AS FLOAT")
                )
                ->where('sp.kdprofile', $kdProfile)
                ->whereNotNull('sp.totalharusdibayar')
                ->whereIn('sp.objectkelompoktransaksifk', $KdList)
                ->where('kt.id', '=', 13);
        } else {
            $dataNonLayanan = DB::table('strukpelayanan_t as sp')
                ->leftjoin('kelompoktransaksi_m as kt', 'sp.objectkelompoktransaksifk', '=', 'kt.id')
                ->select(
                    'sp.norec',
                    'sp.tglstruk',
                    'sp.namapasien_klien',
                    'kt.reportdisplay as jenistagihan',
                    'keteranganlainnya',
                    'sp.nosbklastfk',
                    'sp.nosbmlastfk',
                    'kt.id as jenisTagihanId',
                    DB::raw("CAST(sp.totalharusdibayar AS FLOAT) AS totalharusdibayar")
                )
                ->where('sp.kdprofile', $kdProfile)
                ->whereNotNull('sp.totalharusdibayar')
                ->whereIn('sp.objectkelompoktransaksifk', $KdList);
        }

        if (isset($filter['tglAwal']) && $filter['tglAwal'] != "") {
            $dataNonLayanan = $dataNonLayanan->where('sp.tglstruk', '>=', $filter['tglAwal']);
        }

        if (isset($filter['tglAkhir']) && $filter['tglAkhir'] != "") {
            $tgl = $filter['tglAkhir']; //." 23:59:59";
            $dataNonLayanan = $dataNonLayanan->where('sp.tglstruk', '<=', $tgl);
        }
        //
        if (isset($filter['jenisTagihanId']) && $filter['jenisTagihanId'] != "") {
            $dataNonLayanan = $dataNonLayanan->where('kt.id', '=', $filter['jenisTagihanId']);
        }

        if (isset($filter['namaPelanggan']) && $filter['namaPelanggan'] != "") {
            $dataNonLayanan = $dataNonLayanan->where('sp.namapasien_klien', 'ilike', '%' . $filter['namaPelanggan'] . '%');
        }
        //
        if (isset($filter['status']) && $filter['status'] != "") {
            if ($filter['status'] == 'Lunas') {
                $dataNonLayanan = $dataNonLayanan->whereNotNull('sp.nosbmlastfk');
            } else {
                $dataNonLayanan = $dataNonLayanan->whereNull('sp.nosbmlastfk');
            }
        }
        if (isset($filter['statusK']) && $filter['statusK'] != "") {
            if ($filter['statusK'] == 'Lunas') {
                $dataNonLayanan = $dataNonLayanan->whereNotNull('sp.nosbklastfk');
            } else {
                $dataNonLayanan = $dataNonLayanan->whereNull('sp.nosbklastfk');
            }
        }



        $dataNonLayanan = $dataNonLayanan->where('sp.statusenabled', '=', true);
        $dataNonLayanan = $dataNonLayanan->get();
        $result = array();
        foreach ($dataNonLayanan as $item) {
            $statusBayar = "Belum Bayar";
            if ($item->nosbmlastfk != null || $item->nosbklastfk != null) {
                $statusBayar = "Lunas";
            }
            $result[] = array(
                'noRec' => $item->norec,
                'tglTransaksi' => $item->tglstruk,
                'namaPelanggan' => $item->namapasien_klien,
                'jenisTagihan' => $item->jenistagihan,
                'total' => $item->totalharusdibayar,
                'keterangan' => $item->keteranganlainnya,
                'jenisTagihanId' => $item->jenisTagihanId,
                'statusBayar' => $statusBayar

            );
        }

        return $this->respond($result);
    }

    //Daftar Piutang

    public function daftarPiutang(Request $request)
    {
        $kdProfile =  $this->kdProfile;
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
            ->where('spp.kdprofile', $kdProfile)
            ->whereNotNull('spp.noverifikasi')
            ->where('sp.statusenabled', true);

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
        return $this->respond($result);
    }
    public function daftarVerif(Request $r)
    {
        $data = DB::table('pasiendaftar_t as pd')
            ->join('pasien_m as ps', 'ps.id', '=', 'pd.nocmfk')
            ->join('ruangan_m as ru', 'ru.id', '=', 'pd.objectruanganlastfk')
            ->leftjoin('kelompokpasien_m as kp', 'kp.id', '=', 'pd.objectkelompokpasienlastfk')
            ->leftjoin('departemen_m as dept', 'dept.id', '=', 'ru.objectdepartemenfk')
            ->join('antrianpasiendiperiksa_t as apd', 'pd.norec', '=', 'apd.noregistrasifk')
            ->select(
                'pd.tglregistrasi',
                'ps.nocm',
                'pd.noregistrasi',
                'ru.namaruangan',
                'ps.namapasien',
                'kp.kelompokpasien',
                'pd.tglpulang',
                'pd.statuspasien',
                DB::raw("
                CASE WHEN pd.nostruklastfk IS NOT NULL AND pd.nosbmlastfk IS NOT NULL THEN '-'
                WHEN pd.nostruklastfk IS NOT NULL AND pd.nosbmlastfk IS NULL THEN 'Verifikasi'
                ELSE 'Belum Verifikasi' END AS statusverif
            ")
            )
            ->where('pd.statusenabled', true)
            ->where('pd.kdprofile', $this->kdProfile)
            ->whereNull('pd.tglpulang');


        $data = $data->groupBy(
            'pd.tglregistrasi',
            'ps.nocm',
            'pd.noregistrasi',
            'ru.namaruangan',
            'ps.namapasien',
            'kp.kelompokpasien',
            'pd.tglpulang',
            'pd.statuspasien',
            'pd.nostruklastfk',
            'pd.nosbmlastfk'
        );

        $data = $data->get();

        $result = array();
        foreach ($data as $pasienD) {
            $result[] = array(
                'tanggalMasuk' => $pasienD->tglregistrasi,
                'noCm' => $pasienD->nocm,
                'noRegistrasi' => $pasienD->noregistrasi,
                'namaRuangan' => $pasienD->namaruangan,
                'namaPasien' => $pasienD->namapasien,
                'jenisAsuransi' => $pasienD->kelompokpasien,
                'tanggalPulang' => $pasienD->tglpulang,
                'statusverif' => $pasienD->statusverif,
                'status' => $pasienD->statuspasien
            );
        }
        return $this->respond($result);
    }

    public function daftarPasienPulang(Request $r)
    {
        $totalTagihan = DB::table('pelayananpasien_t as pp')
            ->where('pp.statusenabled', true)
            ->groupBy('pp.noregistrasi', 'pp.norec')
            ->select(DB::raw("pp.noregistrasi,
            pp.norec,
            sum(((
            COALESCE (pp.hargasatuan, 0)
            - COALESCE (pp.hargadiscount, 0)) * pp.jumlah)
            + COALESCE (pp.jasa, 0)) as totaltagihan"));
        $bayar = DB::table('strukpelayanan_t as sp')
            ->leftjoin('strukbuktipenerimaan_t as sbm', function ($j) {
                $j->on('sbm.nostrukfk', '=', 'sp.norec')->where('sbm.statusenabled', true);
            })
            ->leftjoin('pegawai_m as pg','pg.id','=', 'sp.objectpegawaipenerimafk')
            ->where('sp.statusenabled', true)
            ->select(
                'sp.noregistrasi',
                DB::raw("MAX(pg.namalengkap) as closer"),
                DB::raw("SUM(sp.totalharusdibayar) as totalverif"),
                DB::raw("SUM(sbm.totaldibayar) as totalbayar"),
                DB::raw("SUM(sp.totaliurbayar) as totaliurbayar")
            )
            ->groupBy('sp.noregistrasi');

        $klaim = DB::table('strukpelayanan_t as sp')
            ->where('sp.statusenabled', true)
            ->select(
                'sp.noregistrasi',
                DB::raw('SUM(sp.totalprekanan) as totalklaim')
            )
            ->groupBy('sp.noregistrasi');

        $data = DB::table('pasiendaftar_t as pd')
            ->join('pasien_m as ps', 'ps.id', '=', 'pd.nocmfk')
            // ->join('antrianpasiendiperiksa_t as apd','apd.noregistrasifk','pd.norec')
            ->join('ruangan_m as ru', 'ru.id', '=', 'pd.objectruanganlastfk')
            ->join('kelompokpasien_m as kp', 'kp.id', '=', 'pd.objectkelompokpasienlastfk')
            ->leftJoinSub($bayar, 'byr', 'pd.noregistrasi', '=', 'byr.noregistrasi')
            ->leftJoinSub($klaim, 'kla', 'pd.noregistrasi', '=', 'kla.noregistrasi')
            ->leftJoinSub($totalTagihan, 'bil', 'pd.noregistrasi', '=', 'bil.noregistrasi')
            ->select(
                'pd.norec as norec_pd',
                'pd.nocmfk',
                'pd.tglregistrasi',
                'pd.statusbayar',
                'ps.nocm',
                // 'apd.norec as norec_apd',
                'pd.noregistrasi',
                'pd.isclosing',
                'ru.namaruangan',
                'ps.namapasien',
                'kp.kelompokpasien',
                'pd.objectkelompokpasienlastfk',
                'ps.objectkebangsaanfk',
                'pd.tglpulang',
                'pd.statuspasien',
                'ru.objectdepartemenfk',
                'byr.closer',
                DB::raw("
                COALESCE (ROUND(bil.totaltagihan), 0)  as totaltagihan,
                COALESCE (byr.totalverif, 0)  as totalverif,
                COALESCE (byr.totalbayar, 0) as totalbayar,
                COALESCE (kla.totalklaim, 0) as totalklaim,
                COALESCE (byr.totaliurbayar, 0) as totaliurbayar")
            )
            ->where('pd.statusenabled', true)
            ->where('pd.kdprofile', $this->kdProfile)
            ->whereNotNull('pd.tglpulang');

        if (isset($r['dari']) && $r['dari'] != '') {
            $data = $data->whereBetween('pd.tglpulang', [$r['dari'] . ' 00:00', $r['sampai'] . ' 23:59']);
        }
        if (isset($r['ruanganid']) && $r['ruanganid'] != '') {
            $data = $data->whereIn('ru.id', explode(',', $r['ruanganid']));
        }
        if (isset($r['ruanganfk']) && $r['ruanganfk'] != '') {
            $data = $data->where('pd.objectruanganlastfk', $r['ruanganfk']);
        }
        if (isset($r['nocm']) && $r['nocm'] != '') {
            $data = $data->where('ps.nocm', $r['nocm']);
        }
        if (!empty($r['namapasien'])) {
            $nama = '%' . $r['namapasien'] . '%';
            $data = $data->where(function($q) use ($nama) {
                $q->where('ps.namapasien', 'ilike', $nama)
                ->orWhere('ps.nocm', 'ilike', $nama);
            });
        }
        if (isset($r['noreg']) && $r['noreg'] != '') {
            $data = $data->where('pd.noregistrasi', 'ilike', '%' . $r['noreg'] . '%');
        }
        if (isset($r['ruanganfk']) && $r['ruanganfk'] != '') {
            $data = $data->where('pd.objectruanganlastfk',$r['ruanganfk']);
        }
        if (isset($r['kelompokpasienfk']) && $r['kelompokpasienfk'] != '') {
            $data = $data->where('pd.objectkelompokpasienlastfk',$r['kelompokpasienfk']);
        }
        if (isset($r['noreg']) && $r['noreg'] != '') {
            $data = $data->where('pd.noregistrasi', 'ilike', '%' . $r['noreg'] . '%');
        }
        if (isset($r['search']) && $r['search'] != '') {
            $searchTerm = '%' . $r['search'] . '%';
            $data = $data->where(function ($query) use ($searchTerm) {
                $query->where('pd.noregistrasi', 'ilike', $searchTerm)
                    ->orWhere('ps.nocm', 'ilike', $searchTerm)
                    ->orWhere('ps.namapasien', 'ilike', $searchTerm)
                    ->orWhere('ps.noidentitas', 'ilike', $searchTerm);
            });
        }
        if (isset($r['rows']) && $r['rows'] != '') {
            $data = $data->limit($r['rows']);
        }
        // $data = $data->groupBy('kla.norec', 'byr.norec', 'bil.norec');
        $data = $data->get();
        $dep = explode(',',$this->settingFix('kdDepartemenRawatJalanFix'));

        $dataTarif16 = DB::table('pasiendaftar_t as pd')
        ->join('antrianpasiendiperiksa_t as apd', 'apd.noregistrasifk', '=', 'pd.norec')
        ->join('pelayananpasien_t as pp', 'pp.noregistrasifk', '=', 'apd.norec')
        ->join('produk_m as pr', 'pr.id', '=', 'pp.produkfk')
        ->join('kelompokprodukbpjs_m as kpb', 'kpb.id', '=', 'pr.objectkelompokprodukbpjsfk')
        ->where('pd.statusenabled', true)
        ->where('pp.statusenabled', true)
        ->select('pd.norec', DB::raw('SUM(((pp.hargasatuan - COALESCE(pp.hargadiscount, 0)) * pp.jumlah) + COALESCE(pp.jasa, 0)) as ttl'))
        ->groupBy('pd.norec')
        ->get()
        ->keyBy('norec');

        // $tarifMap = collect($dataTarif16)->keyBy('norec');

        // return $data;
        foreach ($data as $d) {
            $d->isallowbatalplg = !in_array($d->objectdepartemenfk, $dep);
            $d->totaltagihanfix = $dataTarif16[$d->norec_pd]->ttl ?? null;
            
            // foreach ($dataTarif16 as $itm) {
            //     if ($itm->norec == $d->norec_pd) {
            //         $d->totaltagihanfix = (float)$itm->ttl;
            //     }
            // }
            // $d->totalbayar = $d->totalbayar - $d->totaliurbayar;
            if ($d->objectkelompokpasienlastfk == 1) {
                $d->sisa = $d->totaltagihan - $d->totalbayar;
            } else {
                $d->sisa = $d->totaltagihan - ($d->totalbayar - $d->totaliurbayar) - $d->totalklaim;
            }

            if ($d->sisa > 0) {
                $d->statusbayar = 'Masih ada tagihan';
                $d->color_statusbayar = 'info';
            }
            if ($d->sisa < 0) {
                $d->statusbayar = 'Pengembalian Deposit';
                $d->color_statusbayar = 'info';
            }

            if ($d->totalbayar == 0) {
                $d->statusbayar = 'Belum Lunas';
                $d->color_statusbayar = 'warning';
            }
            if ((float) $d->totaliurbayar == 0 && $d->totalverif == 0 && $d->totalbayar == 0 && $d->totalklaim == 0) {
                $d->statusbayar = 'Belum Verifikasi';
                $d->color_statusbayar = 'danger';
            }
            if ($d->sisa == 0) {
                $d->statusbayar = 'Lunas';
                $d->color_statusbayar = 'success';
            }
        }

        return $this->respond($data);
    }
    public function countDashboardKasir(Request $r)
    {
        $kdProfile = $this->kdProfile;

        $regis  =  PasienDaftar::where('statusenabled', true)
            ->where('kdprofile', $kdProfile);
        if (isset($r['dari']) && $r['dari'] != '' && isset($r['sampai']) && $r['sampai'] != '') {
            $regis = $regis->whereBetween('tglpulang', [$r['dari'] . ' 00:00', $r['sampai'] . ' 23:59']);
        }
        $regis = $regis->get();

        $bayar  =  StrukBuktiPenerimaan::where('statusenabled', true)
            ->where('kdprofile', $kdProfile);
        if (isset($r['dari']) && $r['dari'] != '' && isset($r['sampai']) && $r['sampai'] != '') {
            $bayar = $bayar->whereBetween('tglsbm', [$r['dari'] . ' 00:00', $r['sampai'] . ' 23:59']);
        }
        $bayar = $bayar->get();
        $res['c_total'] = count($regis);
        $res['c_lunas'] =  count($bayar);

        return $this->respond($res);
    }
    public function detailVerifikasi(Request $r)
    {
        $data = DB::table('strukpelayanan_t as sp')
            ->join('pasiendaftar_t as pd', 'sp.noregistrasifk', '=', 'pd.norec')
            ->leftJoin('strukbuktipenerimaan_t as sbm', 'sbm.nostrukfk', '=', 'sp.norec')
            ->leftjoin('pegawai_m as pg', 'pg.id', '=', 'sp.objectpegawaipenerimafk')
            ->leftJoin('pegawai_m as pg1', 'pg1.id', '=', 'sbm.objectpegawaipenerimafk')
            ->leftJoin('strukpelayananpenjamin_t as spp', 'spp.nostrukfk', '=', 'sp.norec')
            ->select(DB::raw("pd.noregistrasi,sp.norec,sp.nostruk,sp.tglstruk,pg.namalengkap as petugasverif,sbm.tglsbm,pd.norec as norec_pd,
                                    CASE WHEN sp.nosbmlastfk IS NULL THEN 'Belum Bayar' ELSE 'Lunas' END AS status,
		                            CASE WHEN sp.nosbmlastfk IS NULL THEN NULL ELSE pg1.namalengkap END AS kasir,
		                            spp.norec as norec_piutang,pd.objectkelompokpasienlastfk,sp.nosbmlastfk,spp.noverifikasi,
		                            CASE WHEN sp.totalprekanan IS NULL THEN 0 ELSE sp.totalprekanan END AS totalprekanan,
                                    CASE WHEN sp.totaldiscount IS NULL THEN 0 ELSE sp.totaldiscount END totaldiscount,
                                    (sp.totalharusdibayar + CASE WHEN sp.totalprekanan IS NULL THEN 0 ELSE sp.totalprekanan END +
				                    CASE WHEN sp.totaldiscount IS NULL THEN 0 ELSE sp.totaldiscount END) AS totaltagihanverifikasi,
                                    sp.totalharusdibayar + ( COALESCE (sp.totaliurbayar, 0)  ) as  totalharusdibayar"))
            ->where('pd.kdprofile', $this->kdProfile)
            ->where('sp.statusenabled', true)
            ->where('pd.noregistrasi',  $r['noregistrasi'])
            ->where('sp.objectkelompoktransaksifk',   $this->kelompokTransaksi('VERIFIKASI TAGIHAN PASIEN'))
            ->get();

        return $this->respond($data);
    }
    public function batalVerifikasiTagihan(Request $r)
    {
        DB::beginTransaction();
        try {
            // $sbm = StrukBuktiPenerimaan::where('nostrukfk', $r['norec_sp'])->first();
            $sbk = StrukBuktiPengeluaran::where('nostrukfk',  $r['norec_sp'])->first();
            // if (!empty($sbm)) {
            //     $transMessage =  'Tagihan ini sudah di bayarkan';
            //     DB::rollBack();
            //     $result = array(
            //         "status" => 400,
            //         "result"  => null
            //     );
            //     return $this->respond($result['result'], $result['status'], $transMessage);
            // }
            // return $sbk;
            if (isset($sbk) && $sbk != null) {
                $sbk->statusenabled = false;
                $sbk->save();
            }

            PelayananPasien::where('strukfk', $r['norec_sp'])
                ->update([
                    'strukfk' => null,
                ]);
            PelayananPasienDetail::where('strukfk', $r['norec_sp'])
                ->update([
                    'strukfk' => null,
                ]);
            StrukPelayananPenjamin::where('nostrukfk', $r['norec_sp'])
                ->update([
                    'statusenabled' => false
                ]);
            
            $pd = PasienDaftar::where('norec', $r['norec_pd'])->first();
            StrukBuktiPenerimaan::where('norec', $pd->nosbmlastfk)->update([
                'statusenabled' => false
            ]);
            $pd->nostruklastfk = null;
            $pd->nosbmlastfk = null;
            $pd->statusbayar = 'Belum Verifikasi';

            $ruang = DB::table('ruangan_m')
            ->where('id', $pd->objectruanganlastfk)
            ->first();

            $filterDepart = explode(',', $this->settingFix('kdDepartemenRawatJalanFix'));
            if(in_array($ruang->objectdepartemenfk, $filterDepart)) {
                $pd->tglpulang = null;
            }

            $pd->save();


            StrukPelayanan::where('norec', $r['norec_sp'])
                ->update([
                    'statusenabled' => false,
                ]);


            $transStatus = true;
        } catch (Exception $e) {
            $transStatus = false;
        }

        if ($transStatus) {
            $transMessage = "Sukses";
            
            $ps = \DB::table('pasiendaftar_t as pd')
            ->select('ps.namapasien')
            ->leftJoin('pasien_m as ps', 'ps.id', 'pd.nocmfk')
            ->where('pd.norec', $r['norec_pd'])
            ->first();
            // harusnya norec_pd terlanjur salah :( ambil dr noregistrasifk
            $this->LOGGING(
                'Open Bill',
                $r['norec_sp'],
                'strukpelayanan_t',
                'Pasien '. $ps->namapasien .' kembali di Open Bill oleh : ' . $this->getPegawai()->namalengkap,
            );
            DB::commit();
            $result = array(
                "status" => 200,
                "result" => array(
                    "as" => '@epic',
                ),
            );
        } else {
            $transMessage =  "Simpan Gagal";
            DB::rollBack();
            $result = array(
                "status" => 400,
                "result"  => $e->getMessage() . ' ' . $e->getLine()
            );
        }
        return $this->respond($result['result'], $result['status'], $transMessage);
    }

    public function batalPiutang(Request $r)
    {
        DB::beginTransaction();
        try {
            PasienDaftar::where('norec', $r['norec_pd'])
                ->update([
                    'objectstatuspiutangfk' => null,
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
                    "as" => '@epic',
                ),
            );
        } else {
            $transMessage =  "Simpan Gagal";
            DB::rollBack();
            $result = array(
                "status" => 400,
                "result"  => $e->getMessage() . ' ' . $e->getLine()
            );
        }
        return $this->respond($result['result'], $result['status'], $transMessage);
    }

    public function getDataComboKasir()
    {
        $res['ruanganFarmasi'] = Ruangan::select('namaruangan','id')
            ->where('kdprofile', $this->kdProfile)
            ->where('statusenabled', true)
            ->whereIn('objectdepartemenfk', [$this->settingFix('idInstalasiFarmasi')])
            ->get();

        $res['kelompokpasien'] = KelompokPasien::mine()->get();

        return $this->respond($res);
    }

    public function riwayatOpenbill(Request $request)
    {
        $data = DB::table('logginguser_t as lu')
        ->select(
            'lu.tanggal', 'ps.namapasien', 'pd.noregistrasi', 'ps.nocm', 'ru.namaruangan',
            'pd.tglregistrasi','lu.keterangan'
        )
        ->leftJoin('strukpelayanan_t as sp', 'sp.norec', 'lu.noreff')
        ->leftJoin('pasiendaftar_t as pd', 'pd.norec', 'sp.noregistrasifk')
        ->leftJoin('antrianpasiendiperiksa_t as apd', 'apd.noregistrasifk', 'pd.norec')
        ->leftJoin('pasien_m as ps', 'ps.id', 'pd.nocmfk')
        ->leftJoin('ruangan_m as ru', 'ru.id', 'pd.objectruanganlastfk')
        ->where('jenislog', 'Open Bill');

        if (isset($request['tglAwal']) && $request['tglAwal'] != "") {
            $data = $data->whereDate('lu.tanggal', '>=', $request['tglAwal']);
        }

        if (isset($request['tglAkhir']) && $request['tglAkhir'] != "") {
            $tgl = $request['tglAkhir'] . " 23:59:59";
            $data = $data->whereDate('lu.tanggal', '<=', $tgl);
        }

        if(isset($request['namaPasien']) && $request['namaPasien'] != '') {
            $data->where(function($q) use($request) {
                $q->where('ps.namapasien', 'ilike', '%'.$request['namaPasien'].'%')
                ->orWhere('ps.nocm', 'ilike', '%'.$request['namaPasien'].'%')
                ->orWhere('pd.noregistrasi', 'ilike', '%'.$request['namaPasien'].'%');
            });
        }
        if (isset($request['ruanganfk']) && $request['ruanganfk'] != '') {
            $request['ruanganfk'] = explode(',',$request['ruanganfk']);
            $data = $data->whereIn('pd.objectruanganlastfk', $request['ruanganfk']);
        }
        if (isset($request['kelompokpasienfk']) && $request['kelompokpasienfk'] != '') {
            $data = $data->where('pd.objectkelompokpasienlastfk',$request['kelompokpasienfk']);
        }
        $data = $data->distinct();
        $data = $data->groupBy(
            'ps.nocm', 'ps.namapasien','pd.noregistrasi', 'lu.tanggal', 'ru.namaruangan',
            'pd.tglregistrasi','lu.keterangan'
        );
        $data = $data->orderBy('lu.tanggal', 'desc');
        $data = $data->paginate(50);
        return $this->respond($data);
    }
}
