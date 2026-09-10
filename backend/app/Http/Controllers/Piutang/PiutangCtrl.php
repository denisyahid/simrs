<?php

namespace App\Http\Controllers\Piutang;

use App\Http\Controllers\Controller;
use App\Models\Standar\LoginUser;
use App\Models\Transaksi\BPJSGagalKlaimTxt;
use App\Models\Transaksi\BPJSKlaimTxt;
use App\Models\Transaksi\PostingHutangPiutang;
use App\Models\Transaksi\StrukBuktiPenerimaan;
use App\Models\Transaksi\StrukKwitansiPiutang;
use App\Models\Transaksi\StrukPelayananPenjamin;
use App\Models\Transaksi\StrukPosting;
use App\Traits\Valet;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PiutangCtrl extends Controller
{
    use Valet;
    public function daftarPiutang(Request $request)
    {
        $filter = $request->all();
        $kdProfile = (int) $this->kdProfile;

        $dataPiutang = DB::table('strukpelayananpenjamin_t as spp')
            ->join('strukpelayanan_t as sp', 'sp.norec', '=', 'spp.nostrukfk')
            ->join('pasien_m as p', 'p.id', '=', 'sp.nocmfk')
            ->join('pasiendaftar_t as pd', 'sp.noregistrasifk', '=', 'pd.norec')
            ->join('ruangan_m as ru', 'ru.id', '=', 'pd.objectruanganlastfk')
            ->leftjoin('rekanan_m as rkn', 'rkn.id', '=', 'pd.objectrekananfk')
            ->join('kelompokpasien_m as kp', 'kp.id', '=', 'pd.objectkelompokpasienlastfk')
            ->leftjoin('postinghutangpiutang_t as php', 'php.nostrukfk', '=', 'spp.norec')
            ->leftjoin('strukposting_t as spt', 'spt.noposting', '=', 'php.noposting')
            ->select(DB::raw("
                kp.kelompokpasien,spp.norec,pd.tglpulang AS tglstruk,pd.noregistrasi,pd.tglregistrasi,p.nocm,pd.nosbmlastfk,
                p.namapasien,ru.namaruangan,spp.totalppenjamin,spp.totalharusdibayar,spp.totalsudahdibayar,
                spp.totalbiaya,spp.noverifikasi,rkn.namarekanan,php.noposting,spt.statusenabled,pd.norec AS norec_pd,
                php.statusenabled AS sttts,sp.totalharusdibayar AS totaltidakdiklaim,rkn.id as idrekanan
            "))
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
        if (isset($filter['search']) && $filter['search'] != "") {
            $dataPiutang = $dataPiutang->where('p.nocm', '=', $filter['search'])->orWhere('pd.noregistrasi', 'ilike', '%' . $filter['search'] . '')->orWhere('p.namapasien', 'ilike', '%' . $filter['search'] . '');
        }
        if (isset($filter['status']) && $filter['status'] != "") {
            $status = $filter['status'];
            $dataPiutang->where(function ($query) use ($status) {
                if ($status == "Piutang") {
                    $query->whereNull('pd.nosbmlastfk')
                        ->orWhere('pd.nosbmlastfk', '!=', null)
                        ->whereNull('spt.noposting')
                        ->orWhere('spt.noposting', '!=', null)
                        ->whereRaw('spp.totalppenjamin > spp.totalsudahdibayar');
                } elseif ($status == "Collecting") {
                    $query->whereNull('pd.nosbmlastfk')
                        ->whereNotNull('spt.noposting')
                        ->whereRaw('spp.totalppenjamin > spp.totalsudahdibayar');
                } elseif ($status == "Lunas") {
                    $query->whereRaw('spp.totalppenjamin <= spp.totalsudahdibayar');
                }
            });
        }
        $dataPiutang = $dataPiutang->get();
        $result = array();
        foreach ($dataPiutang as $item) {
            if ($item->statusenabled ==  1 || is_null($item->statusenabled)) {
                if ($item->sttts == 1 || is_null($item->sttts)) {
                    if ($item->nosbmlastfk == null && $item->totalppenjamin > $item->totalsudahdibayar) {
                        if (!isset($item->noposting)) {
                            $status = 'Piutang';
                        } else {
                            $status = 'Collecting';
                        }
                    } elseif ($item->nosbmlastfk != null && $item->totalppenjamin > $item->totalsudahdibayar) {
                        $status = 'Piutang';
                    } else {
                        $status = 'Lunas';
                    }

                    $result[] = array(
                        'noRec' => $item->norec,
                        'tglTransaksi' => $item->tglstruk,
                        'noRegistrasi' => $item->noregistrasi,
                        'namaPasien' => $item->namapasien,
                        'ruangan' => $item->namaruangan,
                        'kelasRawat' => $item->kelompokpasien,
                        'jenisPasien' => $item->kelompokpasien,
                        'umur' => $this->getAge($item->tglstruk, date('Y-m-d H:i:s')),
                        'kelasPenjamin' => "-",
                        'totalBilling' => $item->totalbiaya,
                        'totalKlaim' => $item->totalppenjamin,
                        'totalBayar' => $item->totalsudahdibayar,
                        'totaltidakdiklaim' => $item->totaltidakdiklaim,
                        'sisautang' => (float)$item->totalppenjamin - $item->totalsudahdibayar,
                        'rekanan' => $item->namarekanan,
                        'status' => $status,
                        'norec_pd' => $item->norec_pd,
                        'noposting' => $item->noposting,
                        'stts' => $item->statusenabled,
                        'idrekanan' => $item->idrekanan
                    );
                }
            }
        }
        return $this->respond($result);
    }
    public function daftarPiutangNonLayanan(Request $request)
    {
        $filter         = $request->all();
        $kdProfile      = (int) $this->kdProfile;
        $dataPiutang    = DB::table('strukpelayananpenjamin_t as spp')
            ->join('strukpelayanan_t as sp', 'sp.norec', '=', 'spp.nostrukfk')
            ->leftjoin('rekanan_m as rkn', 'rkn.id', '=', 'sp.objectrekananfk')
            ->join('kelompokpasien_m as kp', 'kp.id', '=', 'sp.objectkelompokpasienfk')
            ->leftjoin('postinghutangpiutang_t as php', 'php.nostrukfk', '=', 'spp.norec')
            ->leftjoin('strukposting_t as spt', 'spt.noposting', '=', 'php.noposting')
            ->select(DB::raw("
                kp.kelompokpasien,spp.norec,sp.tglstruk,'-' AS noregistrasi,sp.tglstruk AS tglregistrasi,
                sp.nostruk_intern AS nocm,sp.namapasien_klien AS namapasien,'' AS namaruangan,spp.totalppenjamin,
                spp.totalharusdibayar,spp.totalsudahdibayar,spp.totalbiaya,spp.noverifikasi,rkn.namarekanan,php.noposting,
                spt.statusenabled,sp.norec AS norec_pd,php.statusenabled AS sttts,
                sp.totalharusdibayar AS totaltidakdiklaim,sp.nosbmlastfk
            "))
            ->where('spp.kdprofile', $kdProfile)
            ->where('sp.statusenabled', true);
        if (isset($filter['tglAwal']) && $filter['tglAwal'] != "") {
            $dataPiutang = $dataPiutang->where('sp.tglstruk', '>=', $filter['tglAwal']);
        }

        if (isset($filter['tglAkhir']) && $filter['tglAkhir'] != "") {
            $tgl = $filter['tglAkhir'] . " 23:59:59";
            $dataPiutang = $dataPiutang->where('sp.tglstruk', '<=', $tgl);
        }

        if (isset($filter['objectkelompokpasienfk']) && $filter['objectkelompokpasienfk'] != "") {
            $dataPiutang = $dataPiutang->where('sp.objectkelompokpasienfk', '=', $filter['objectkelompokpasienfk']);
        }
        if (isset($filter['rekananfk']) && $filter['rekananfk'] != "") {
            $dataPiutang = $dataPiutang->where('sp.objectrekananfk', '=', $filter['rekananfk']);
        }

        if (isset($filter['ruanganId']) && $filter['ruanganId'] != "") {
            $dataPiutang = $dataPiutang->where('ru.id', '=', $filter['ruanganId']);
        }
        if (isset($filter['namaPasien']) && $filter['namaPasien'] != "") {
            $dataPiutang = $dataPiutang->where('sp.namapasien_klien', 'ilike', '%' . $filter['namaPasien'] . '%');
        }
        $dataPiutang = $dataPiutang->get();
        $result = array();
        foreach ($dataPiutang as $item) {
            if ($item->statusenabled ==  1 || is_null($item->statusenabled)) {
                if ($item->sttts == 1 || is_null($item->sttts)) {
                    if ($item->nosbmlastfk == null && $item->totalppenjamin > $item->totalsudahdibayar) {
                        if (!isset($item->noposting)) {
                            $status = 'Piutang';
                        } else {
                            $status = 'Collecting';
                        }
                    } else {
                        $status = 'Lunas';
                    }
                    $result[] = array(
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
                        'totaltidakdiklaim' => $item->totaltidakdiklaim,
                        'sisautang' => (float)$item->totalppenjamin - $item->totalsudahdibayar,
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
    public function daftarCollectedPiutang(Request $request)
    {
        $kdProfile      = (int) $this->kdProfile;
        $filter         = $request->all();
        $dataCollector  = DB::table('postinghutangpiutang_t as php')
            ->join('strukpelayananpenjamin_t as spp', 'spp.norec', '=', 'php.nostrukfk')
            ->join('strukpelayanan_t as spy', 'spy.norec', '=', 'spp.nostrukfk')
            ->join('pasiendaftar_t as pd', 'pd.norec', '=', 'spy.noregistrasifk')
            ->leftjoin('rekanan_m as rkn', 'rkn.id', '=', 'pd.objectrekananfk')
            ->join('strukposting_t as sp', 'sp.noposting', '=', 'php.noposting')
            ->join('loginuser_s as lu', 'sp.kdhistorylogins', '=', 'lu.id')
            ->join('pegawai_m as p', 'lu.objectpegawaifk', '=', 'p.id')
            ->leftjoin('strukkwitansipiutang_t as skp', 'skp.norec', '=', 'php.strukkwitansipiutangfk')
            ->select(
                'sp.norec',
                'sp.tglposting',
                'php.noposting',
                'rkn.id as idrekanan',
                'rkn.namarekanan',
                'rkn.kodeexternal as partnercode',
                'php.statusenabled',
                'p.namalengkap',
                'php.nomorreferencebri',
                'skp.norec AS norec_skp',
                'skp.nokwitansi',
                DB::raw('SUM(spp.totalppenjamin) as totalpenjamin'),
                DB::raw('sum(spp.totalsudahdibayar) as sumtotalsudahdibayar'),
                DB::raw("count(php.noposting) as jlhpasien,CASE WHEN spp.totaldiskon IS NULL THEN 0 ELSE spp.totaldiskon END AS totaldiskon ")
            )
            ->where('php.kdprofile', $kdProfile);

        if (isset($filter['tglAwal']) && $filter['tglAwal'] != "") {
            $dataCollector = $dataCollector->where('sp.tglposting', '>=', DB::raw("'" . $filter['tglAwal'] . "'"));
        }

        if (isset($filter['tglAkhir']) && $filter['tglAkhir'] != "") {
            $tgl = $filter['tglAkhir'] . " 23:59:59";
            $dataCollector = $dataCollector->where('sp.tglposting', '<=', DB::raw("'" . $tgl . "'"));
        }

        if (isset($filter['status']) && $filter['status'] == "Collecting") {
            $dataCollector = $dataCollector->where('spp.totalsudahdibayar', '=', 0);
        }
        if (isset($filter['status']) && $filter['status'] == "Lunas") {
            $dataCollector = $dataCollector->where('spp.totalsudahdibayar', '>', 0);
        }
        if (isset($filter['namaPasien']) && $filter['namaPasien'] != "") {
            $dataCollector = $dataCollector->where('p.namalengkap', 'ilike', '%' . $filter['namaPasien'] . '%');
        }
        if (isset($filter['noposting']) && $filter['noposting'] != "") {
            $dataCollector = $dataCollector->where('php.noposting', 'ilike', '%' . $filter['noposting'] . '');
        }
        if (isset($filter['namaRekanan']) && $filter['namaRekanan'] != "") {
            $dataCollector = $dataCollector->where('pd.objectrekananfk', '=', $filter['namaRekanan']);
        }
        $dataCollector = $dataCollector->where('sp.statusenabled', '=', '1');
        $dataCollector = $dataCollector->where('php.statusenabled', 1);

        $dataCollector = $dataCollector->groupBy(
            'sp.norec',
            'php.noposting',
            'sp.tglposting',
            'rkn.id',
            'rkn.namarekanan',
            'php.statusenabled',
            'p.namalengkap',
            'php.nomorreferencebri',
            'rkn.kodeexternal',
            'skp.norec',
            'skp.nokwitansi',
            'spp.totaldiskon'
        );
        $dataCollector = $dataCollector->get();

        $result = array();
        foreach ($dataCollector as $item) {
            $totalBayar = (float) $item->sumtotalsudahdibayar + (float) $item->totaldiskon;
            $status = '-';
            if ($totalBayar < $item->totalpenjamin) {
                $status = "Collecting";
            } elseif ($totalBayar == $item->totalpenjamin or $totalBayar > $item->totalpenjamin) {
                $status = "Lunas";
            }
            $result[] = array(
                'noPosting' => $item->noposting,
                'tglTransaksi' => $item->tglposting,
                'collector' => $item->namalengkap,
                'jlhPasien' => $item->jlhpasien,
                'totalKlaim' => $item->totalpenjamin,
                'status' => $status,
                'totalSudahDibayar' => $item->sumtotalsudahdibayar,
                'kelompokpasien' => "Perusahaan/Asuransi",
                'idrekanan' => $item->idrekanan,
                'namarekanan' => $item->namarekanan,
                'statusenabled' => $item->statusenabled,
                'nomorreferencebri' => $item->nomorreferencebri,
                'partnercode' => $item->partnercode,
                'norec_skp' => $item->norec_skp,
                'nokwitansi' => $item->nokwitansi,
                'totaldiskon' => $item->totaldiskon,

            );
        }
        return $this->respond($result);
    }
    public function collectingPiutang(Request $request)
    {
        $kdProfile      = (int) $this->kdProfile;
        DB::beginTransaction();
        if ($request['nopostings'] == '') {
            $strukPosting = new StrukPosting();
            $strukPosting->norec = $strukPosting->generateNewId();
            $noPosting = $this->generateCode(new StrukPosting, 'noposting', 16, $this->getDateTime()->format('dm-y') . '-PI', $kdProfile);
            $strukPosting->noposting = $noPosting;
        } else {
            $strukPosting = StrukPosting::where('noposting', $request['nopostings'])->first();
        }

        $strukPosting->kdprofile = $kdProfile;
        $strukPosting->objectkelompoktransaksifk = 1;
        $strukPosting->kdhistorylogins = $this->getPegawaiId();
        $strukPosting->keteranganlainnya = "Collecting Piutang Penjamin";
        $strukPosting->tglposting = $request['tglcollecting'];
        $strukPosting->objectruanganfk = 10;
        try {
            $strukPosting->save();
        } catch (\Exception $e) {
            $this->transStatus = false;
            $this->transMessage = "Collecting Piutang Gagal{1}"  . $e->getMessage();
        }

        if ($this->transStatus) {
            foreach ($request['strukPenjamin'] as $norec) {
                if ($norec['norec'] == null) {
                    $this->transStatus = false;
                    $this->transMessage = "Collecting gagal perbaiki data !";
                    break;
                } else {

                    $strukPelayananPenjamin = StrukPelayananPenjamin::where('norec', $norec['norec'])->first();
                    if ($strukPelayananPenjamin) {
                        $postingHutang = new PostingHutangPiutang();
                        $postingHutang->norec = $postingHutang->generateNewId();
                        $postingHutang->kdprofile = $kdProfile;
                        $postingHutang->noposting = $strukPosting->noposting;
                        $postingHutang->nostrukfk = $strukPelayananPenjamin->norec;
                        $postingHutang->keteranganlainnya = "Colecting Piutang Penjamin";
                        $postingHutang->totalpiutang = $norec['totalKlaim'];

                        try {
                            $postingHutang->save();
                        } catch (\Exception $e) {
                            $this->transStatus = false;
                            $this->transMessage = "Collecting Piutang Gagal{2}" . $e->getMessage() . 'line' . $e->getLine();
                            break;
                        }
                    }
                }
            }
        }

        if ($this->transStatus) {
            DB::commit();
            $response = [
                'status' => 200,
                'message' => 'Collecting Piutang Berhasil',
                'result' => []
            ];
        } else {
            DB::rollBack();
            $response = [
                'status' => 400,
                'message' => $this->transMessage,
                'result' => null
            ];
        }
        return $this->respond($response, $response['status'], $response['message']);
    }
    public function collectedPiutang(Request $request, $noPosting)
    {
        $kdProfile   = (int) $this->kdProfile;
        $search = $request->search;
        $dataSpp = DB::table('strukpelayananpenjamin_t as spp')
            ->join('strukpelayanan_t as sp', 'sp.norec', '=', 'spp.nostrukfk')
            ->join('pelayananpasien_t as pp', 'pp.strukfk', '=', 'sp.norec')
            ->join('antrianpasiendiperiksa_t as ap', 'ap.norec', '=', 'pp.noregistrasifk')
            ->join('pasiendaftar_t as pd', 'pd.norec', '=', 'ap.noregistrasifk')
            ->leftjoin('pemakaianasuransi_t as pa', 'pa.noregistrasifk', '=', 'sp.noregistrasifk')
            ->leftjoin('bpjsklaimtxt_t as bpjs', 'bpjs.sep', '=', 'pa.nosep')
            ->leftjoin('bpjsgagalklaimtxt_t as gagalbpjs', 'gagalbpjs.nosep', '=', 'pa.nosep')
            ->join('pasien_m as p', 'p.id', '=', 'pd.nocmfk')
            ->join('postinghutangpiutang_t as php', 'php.nostrukfk', '=', 'spp.norec')
            ->join('strukposting_t as stp', 'stp.noposting', '=', 'php.noposting')
            ->leftJoin('rekanan_m as r', 'r.id', '=', 'pd.objectrekananfk')
            ->leftJoin('kelas_m as kls', 'kls.id', '=', 'pd.objectkelasfk')
            ->leftJoin('kelompokpasien_m as kp', 'kp.id', '=', 'pd.objectkelompokpasienlastfk')
            ->leftJoin('strukkwitansipiutang_t as skp', 'skp.norec', '=', 'php.strukkwitansipiutangfk')
            ->leftJoin('loginuser_s as us', 'us.id', '=', 'stp.kdhistorylogins')
            ->leftJoin('pegawai_m as pg', 'pg.id', '=', 'us.objectpegawaifk')
            ->select(
                'kp.id as kpid',
                'kp.kelompokpasien',
                'spp.norec',
                'stp.tglposting',
                'pd.noregistrasi',
                'pd.tglregistrasi',
                'p.nocm',
                'p.namapasien',
                'spp.totalppenjamin',
                'spp.totalharusdibayar as tarifklaim',
                'bpjs.tarif_inacbg as tarifklaimbpjs',
                'spp.totalsudahdibayar',
                'r.id as rknid',
                'r.namarekanan',
                'spp.totalbiaya',
                'spp.noverifikasi',
                'php.noposting',
                'pg.namalengkap as collector',
                'kls.namakelas',
                'stp.tglposting',
                'p.tgllahir',
                'gagalbpjs.keterangan',
                'skp.norec AS norec_skp',
                'skp.nomorsurat AS nokwitansi',
                'pd.nosbmlastfk'
            )
            // ->whereNotNull('spp.noverifikasi')
            ->where('spp.kdprofile', $kdProfile)
            ->where('php.statusenabled', true);
        $dataSpp = $dataSpp->where('php.noposting', $noPosting);
        if (isset($request['noRegistrasi']) && $request['noRegistrasi'] != '') {
            $searchTerm = '%' . $request['noRegistrasi'] . '%';
            $dataSpp = $dataSpp->where(function ($query) use ($searchTerm) {
                $query->where('pd.noregistrasi', 'ilike', $searchTerm);
            });
        }
        if (isset($request['namaPasien']) && $request['namaPasien'] != '') {
            $searchTerm = '%' . $request['namaPasien'] . '%';
            $dataSpp = $dataSpp->where(function ($query) use ($searchTerm) {
                $query->where('p.namapasien', 'ilike', $searchTerm);
            });
        }
        $dataSpp = $dataSpp->groupBy(
            'kp.kelompokpasien',
            'spp.norec',
            'stp.tglposting',
            'pd.noregistrasi',
            'pd.tglregistrasi',
            'p.nocm',
            'p.namapasien',
            'spp.totalppenjamin',
            'spp.totalharusdibayar',
            'spp.totalsudahdibayar',
            'r.namarekanan',
            'spp.totalbiaya',
            'spp.noverifikasi',
            'php.noposting',
            'pg.namalengkap',
            'kls.namakelas',
            'stp.tglposting',
            'bpjs.tarif_inacbg',
            'r.namarekanan',
            'p.tgllahir',
            'r.id',
            'kp.id',
            'gagalbpjs.keterangan',
            'skp.norec',
            'skp.nomorsurat',
            'pd.nosbmlastfk'
        );
        $dataSpp = $dataSpp->orderBy('p.namapasien');
        $dataSpp = $dataSpp->get();
        $result = array();
        $statusCollect = '';
        foreach ($dataSpp as $item) {
            if ($item->tarifklaimbpjs == null) {
                $tarifklaim = (float)$item->totalppenjamin;
                $selisihKlaim = 0;
            } else {
                $tarifklaim = (float)$item->tarifklaimbpjs;
                $selisihKlaim = (float)$item->tarifklaimbpjs - (float)$item->totalppenjamin;
            }
            if ($item->nosbmlastfk == null) {
                $statusCollect = 'Collecting';
            } else {
                $statusCollect = 'Lunas';
            }
            $result[] = array(
                'noRec' => $item->norec,
                'noPosting' => $item->noposting,
                'tglPosting' => $item->tglposting,
                'tglTransaksi' => $item->tglregistrasi,
                'noRegistrasi' => $item->noregistrasi,
                'namaPasien' => $item->namapasien,
                'kelasRawat' => $item->namakelas,
                'collector' =>  $item->collector,
                'kpid' => $item->kpid,
                'jenisPasien' => $item->kelompokpasien,
                'kelasPenjamin' => $item->namakelas,
                'umur' => $this->hitungUmur($item->tgllahir),
                'totalBilling' => $item->totalbiaya,
                'totalKlaim' => $item->totalppenjamin,
                'totalBayar' => $item->totalsudahdibayar,
                'status' => $statusCollect,
                'rknid' => $item->rknid,
                'namarekanan' => $item->namarekanan,
                'tarifselisihklaim' => $selisihKlaim,
                'tarifinacbgs' => $tarifklaim,
                'keterangan' => $item->keterangan,
                'norec_skp' => $item->norec_skp,
                'nokwitansi' => $item->nokwitansi,
                'nosbmlastfk' => $item->nosbmlastfk
            );
        }
        return $this->respond($result);
    }

    public function detailPiutangPasienCollecting($noPosting, Request $request)
    {
        $kdProfile   = (int) $this->kdProfile;
        $php = DB::select(
            DB::raw("select norec,noposting,nostrukfk from postinghutangpiutang_t
               where kdprofile = $kdProfile and noposting='$noPosting' order by nostrukfk limit 1")
        );
        foreach ($php as $item) {
            $phps = $item->nostrukfk;
        };
        $spp = StrukPelayananPenjamin::where('norec', $phps)->where('kdprofile', $kdProfile)->first();
        $sbp = StrukBuktiPenerimaan::where('nostrukfk', $spp->nostrukfk)->where('objectkelompoktransaksifk', 76)->where('kdprofile', $kdProfile)->orderBy('nosbm')->get();

        $detailPembayaran = array();
        foreach ($sbp as $item) {
            $detailPembayaran[] = array(
                'noSbm' => $item->nosbm,
                'tglPembayaran' => $item->tglsbm,
                'jlhPembayaran' => $item->totaldibayar,
                'diskon' => $item->totaldiskon
            );
        }
        $data = array(
            "noRecSPP" => $spp->norec,
            "detailPembayaran" => $detailPembayaran
        );

        return $this->respond($data);
    }
    public function batalCollectingPiutang(Request $request)
    {
        $kdProfile   = (int) $this->kdProfile;
        DB::beginTransaction();
        $this->transStatus = true;
        try {
            $data = StrukPosting::where('noposting', $request['noposting'])->where('kdprofile', $kdProfile)
                ->update(['statusenabled' => false]);
            $data2 = PostingHutangPiutang::where('noposting', $request['noposting'])->where('kdprofile', $kdProfile)->delete();
        } catch (\Exception $e) {
            $this->transStatus = false;
            $this->transMessage = "Gagal!";
        }

        if ($this->transStatus) {
            DB::commit();
            $transMessage = "Berhasil!!";
            $result = [
                'result' => [],
                'status' => 201,
            ];
        } else {
            $transMessage = "Gagal" . $e->getMessage();
            DB::rollBack();
            $result = [
                'result' => [],
                'status' => 400,
            ];
        }

        return $this->respond($result['result'], $result['status'], $transMessage);
    }

    public function saveDataKwitansiPiutang(Request $request)
    {
        $kdProfile   = (int) $this->kdProfile;
        DB::beginTransaction();
        try {

            if ($request['norec'] == '') {
                $noKwitansi = $this->generateCodeBySeqTable(new StrukKwitansiPiutang(), 'nokwitansi', 14, 'IVP-' . date('ym'), $kdProfile);
                $no = substr($noKwitansi, -4) . "/" . $this->getDateTime()->format('m') . "/KWT/" . $this->getDateTime()->format('Y');
                if ($noKwitansi == '') {
                    $transMessage = "Gagal mengumpukan data, Coba lagi.!";
                    DB::rollBack();
                    $result = array(
                        "status" => 400,
                        "message" => $transMessage,
                        "as" => 'as@epic',
                    );
                    return $this->setStatusCode($result['status'])->respond($result, $transMessage);
                }
                $data = new StrukKwitansiPiutang();
                $data->norec = $data->generateNewId();
                $data->kdprofile = $kdProfile;
                $data->statusenabled = true;
                $data->nokwitansi = $noKwitansi;
                $data->nomorsurat = $no;
            } else {
                $data = StrukKwitansiPiutang::where('norec', $request['norec'])
                    ->where('kdprofile', $kdProfile)
                    ->first();
            }

            $keterangan = "";
            if (isset($request['jenispasien'])) {
                if ($request['jenispasien'] == "Perusahaan") {
                    $keterangan = "Pembayaran Tagihan Perusahaan " . $request['namarekanan'];
                } elseif ($request['jenispasien'] == "Asuransi") {
                    $keterangan = "Pembayaran Tagihan Asuransi " . $request['namarekanan'];
                } elseif ($request['jenispasien'] == "BPJS") {
                    $keterangan = "Pembayaran Tagihan Asuransi " . $request['namarekanan'];
                } elseif ($request['jenispasien'] == "Karyawan") {
                    $keterangan = "Pembayaran Tagihan Asuransi " . $request['namarekanan'];
                } elseif ($request['jenispasien'] == "Umum/Pribadi") {
                    $keterangan = "Pembayaran Tagihan Piutang Pribadi ";
                }
            }

            $data->tanggal = date('Y-m-d H:i:s');
            $data->namarekanan = $request['namarekanan'];
            $data->nominal = $request['totaltagihan'];
            $data->keterangan = $keterangan;
            $data->objectrekananfk = $request['rknid'];
            $data->noposting = $request['noposting'];
            $data->save();
            $norecPosting = $data->norec;

            $Posting = PostingHutangPiutang::where('noposting', $request['noposting'])
                ->update([
                    "strukkwitansipiutangfk" => $norecPosting,
                ]);

            $transStatus = true;
        } catch (\Exception $e) {
            $transStatus = false;
        }

        if ($transStatus) {
            $transMessage = "Simpan Data Kwitansi Berhasil";
            DB::commit();
            $result = array(
                "status" => 201,
                "message" => $transMessage,
                "result" => $data,
                "as" => 'ea@epic',
            );
        } else {
            $transMessage = "Simpan Data Kwitansi  Gagal.";
            DB::rollBack();
            $result = array(
                "status" => 400,
                "message"  => $transMessage . $e->getMessage() . $e->getLine(),
                "as" => 'ea@epic',
                "result" => []
            );
        }
        return $this->respond($result['result'], $result['status'], $result['message']);
    }
    public function daftarKartuPiutang(Request $request)
    {
        $kdProfile   = (int) $this->kdProfile;
        $filter = $request->all();
        $dataCollector = DB::table('postinghutangpiutang_t as php')
            ->join('strukpelayananpenjamin_t as spp', 'spp.norec', '=', 'php.nostrukfk')
            ->join('strukpelayanan_t as spy', 'spy.norec', '=', 'spp.nostrukfk')
            ->join('pasiendaftar_t as pd', 'pd.norec', '=', 'spy.noregistrasifk')
            ->join('rekanan_m as rkn', 'rkn.id', '=', 'pd.objectrekananfk')
            ->join('strukposting_t as sp', 'sp.noposting', '=', 'php.noposting')
            ->join('ruangan_m as ru', 'ru.id', '=', 'pd.objectruanganlastfk')
            ->join('pasien_m as p', 'p.id', '=', 'pd.nocmfk')
            ->select(DB::raw('spy.tglstruk,p.nocm,pd.noregistrasi,p.namapasien,ru.id as ruanganid,ru.namaruangan,rkn.id as idrekanan,rkn.namarekanan,
                             spy.totalprekanan as piutang,spp.totalsudahdibayar,0 as administrasi,
                             (CASE WHEN spy.totalprekanan = spp.totalsudahdibayar THEN 0
                             WHEN spy.totalprekanan <> spp.totalsudahdibayar THEN spy.totalprekanan - spp.totalsudahdibayar
                             ELSE spp.totalsisapiutang end) as sistagihan'))
            ->where('php.kdprofile', $kdProfile)
            ->orderBy('spy.tglstruk', 'asc');

        if (isset($filter['tglAwal']) && $filter['tglAwal'] != "" && $filter['tglAwal'] != 'undefined') {
            $dataCollector = $dataCollector->where('spy.tglstruk', '>=', $filter['tglAwal']);
        }
        if (isset($filter['tglAkhir']) && $filter['tglAkhir'] != "" && $filter['tglAkhir'] != 'undefined') {
            $dataCollector = $dataCollector->where('spy.tglstruk', '<=', $filter['tglAkhir']);
        }
        if (isset($filter['noPosting']) && $filter['noPosting'] != "") {
            $dataCollector = $dataCollector->where('pd.noregistrasi', 'ilike', '%' . $filter['noPosting'] . '');
        }
        if (isset($filter['idPerusahaan']) && $filter['idPerusahaan'] != "") {
            $dataCollector = $dataCollector->where('rkn.id', '=', $filter['idPerusahaan']);
        }
        $dataCollector = $dataCollector->where('sp.statusenabled', '=', '1');
        $dataCollector = $dataCollector->get();

        $totalsaldo = 0;
        $saldo = 0;
        $dataz = array();
        $terbilang = '';
        foreach ($dataCollector as $value) {
            $dataz[] = array(
                'tglstruk' => $value->tglstruk,
                'nocm' => $value->nocm,
                'noregistrasi' => $value->noregistrasi,
                'pasien' => $value->nocm . ' / ' . $value->noregistrasi,
                'namapasien' => $value->namapasien,
                'ruanganid' => $value->ruanganid,
                'namaruangan' => $value->namaruangan,
                'idrekanan' => $value->idrekanan,
                'namarekanan' => $value->namarekanan,
                'piutang' => $value->piutang,
                'totalsudahdibayar' => $value->totalsudahdibayar,
                'administrasi' => $value->administrasi,
                'sistagihan' => $value->sistagihan,
            );


            foreach ($dataz as $t) {
                $saldo = $totalsaldo + $t['sistagihan'];
                $totalsaldo = $saldo;
                $terbilang = $this->terbilang($totalsaldo);
            }
        }
        $result = array(
            'data' => $dataz,
            'saldopiutang' => $totalsaldo,
            'terbilang' => $terbilang
        );
        return $this->respond($result);
    }
    public function getMonitoringKlaimApi(Request $request)
    {
        $filter         = $request->all();
        $kdProfile      = $this->kdProfile;
        $startDate      = $request['startDate'];
        $endDate        = $request['endDate'];
        $rangeDate      = [$startDate, $endDate];
        $search         = $request['search'];
        $idProfile      = (int) $kdProfile;

        $tglAwal = "";
        if (isset($filter['tglAwal']) && $filter['tglAwal'] != "") {
            $tgla = $filter['tglAwal'] . " 00:00:00";
            $tglAwal = " AND bpjs.tglpulang >= '" . $tgla . "'";
        }

        $tglAkhir = "";
        if (isset($filter['tglAkhir']) && $filter['tglAkhir'] != "") {
            $tgl = $filter['tglAkhir'] . " 23:59:59";
            $tglAkhir = " AND bpjs.tglpulang <= '" . $tgl . "'";
        }

        $instalasiId = "";
        if (isset($filter['instalasiId']) && $filter['instalasiId'] != "") {
            $instalasiId = " AND dept.id = " . $filter['instalasiId'] . "";
        }

        $ruanganId = "";
        if (isset($filter['ruanganId']) && $filter['ruanganId'] != "") {
            $ruanganId = " AND ru.id = " . $filter['ruanganId'] . " ";
        }

        $namaPasien = "";
        if (isset($filter['namaPasien']) && $filter['namaPasien'] != "") {
            $namaPasien = " AND p.namapasien ilike '%" . $filter['namaPasien'] . "%'  AND p.namapasien IS NOT NULL";
        }

        $noReg = "";
        if (isset($filter['noReg']) && $filter['noReg'] != "") {
            $noReg = " AND pd.noregistrasi = '" . $filter['noReg'] . "'";
        }

        $jenisPelayanan = "";
        if (isset($filter[' ']) && $filter['jenisPelayanan'] != "") {
            $jenisPelayanan = " AND bpjs.jenispelayanan = '" . $filter['jenisPelayanan'] . "'";
        }

        $nofpk = "";
        if (isset($filter['nofpk']) && $filter['nofpk'] != "") {
            $nofpk = " AND bpjs.nofpk = '" . $filter['nofpk'] . "'";
        }
        $limit = "";
        if (isset($filter['limit']) && $filter['limit'] != "") {
            $limit = "LIMIT '" . $filter['limit'] . "'";
        }
        $offset = "";
        if (isset($filter['offset']) && $filter['offset'] != "") {
            $offset = "OFFSET  '" . $filter['offset'] . "'";
        }
        $dataPiutang = DB::select(DB::raw("
            SELECT x.tglpulang
            ,x.nosep
            ,x.noregistrasi
            ,x.namapasien
            ,x.namaruangan
            ,x.kelompokpasien
            ,x.namarekanan
            ,SUM(x.totalbiaya) AS totalbiaya
            ,SUM(x.totaltidakdiklaim) AS totaltidakdiklaim
            ,SUM(x.totalsudahdibayar) AS totalsudahdibayar
            ,x.totaltarifrs
            ,x.totalpengajuan
            ,x.totalsetujui
            ,x.nofpk
            ,x.noposting
            FROM (
                SELECT DISTINCT
                bpjs.tglpulang,
                bpjs.nosep,
                pd.noregistrasi,
                P.namapasien,
                ru.namaruangan,
                kp.kelompokpasien,
                r.namarekanan,
                COALESCE (spp.totalbiaya, 0) AS totalbiaya,
                COALESCE (sp.totalharusdibayar, 0) AS totaltidakdiklaim,
                COALESCE (spp.totalsudahdibayar, 0) AS totalsudahdibayar,
                bpjs.totaltarifrs,
                bpjs.totalpengajuan,
                bpjs.totalsetujui,
                bpjs.nofpk,
                php.noposting
                FROM monitoringklaim_t AS bpjs
                INNER JOIN pemakaianasuransi_t AS pa ON pa.nosep = bpjs.nosep
                LEFT JOIN strukpelayanan_t AS sp ON sp.noregistrasifk = pa.noregistrasifk  AND sp.kdprofile = $idProfile and sp.statusenabled=true
                LEFT JOIN strukpelayananpenjamin_t AS spp ON sp.norec = spp.nostrukfk
                INNER JOIN pasiendaftar_t AS pd ON pd.norec = pa.noregistrasifk
                LEFT JOIN rekanan_m AS rkn ON rkn.id = pd.objectrekananfk
                LEFT JOIN pasien_m AS p ON p.id = pd.nocmfk
                LEFT JOIN ruangan_m AS ru ON ru.id = pd.objectruanganlastfk
                LEFT JOIN departemen_m AS dept ON dept.id = ru.objectdepartemenfk
                LEFT JOIN rekanan_m AS r ON r.id = spp.kdrekananpenjamin
                LEFT JOIN kelompokpasien_m AS kp ON kp.id = pd.objectkelompokpasienlastfk
                LEFT JOIN postinghutangpiutang_t AS php ON php.nostrukfk = spp.norec
                LEFT JOIN strukposting_t AS spt ON spt.noposting = php.noposting
                WHERE pd.statusenabled = true

                $tglAwal
                $tglAkhir
                $instalasiId
                $ruanganId
                $namaPasien
                $noReg
                $jenisPelayanan
                $nofpk
                $limit
                $offset
            ) x
            GROUP BY x.tglpulang
            ,x.nosep
            ,x.noregistrasi
            ,x.namapasien
            ,x.namaruangan
            ,x.kelompokpasien
            ,x.namarekanan
            ,x.totaltarifrs
            ,x.totalpengajuan
            ,x.totalsetujui
            ,x.nofpk
            ,x.noposting
            UNION ALL
            SELECT
            bpjs.tglpulang,
            bpjs.nosep,
            null as noregistrasi,
            null as namapasien,
            null as namaruangan,
            null as kelompokpasien,
            null as namarekanan,
            0 AS totalbiaya,
            0 AS totaltidakdiklaim,
            0 AS totalsudahdibayar,
            bpjs.totaltarifrs,
            bpjs.totalpengajuan,
            bpjs.totalsetujui,
            bpjs.nofpk,
            null as noposting
            FROM monitoringklaim_t AS bpjs
            LEFT JOIN pemakaianasuransi_t AS pa ON pa.nosep = bpjs.nosep
            WHERE pa.nosep IS NULL
            $tglAwal
            $tglAkhir
            $jenisPelayanan
            $nofpk
            $limit
            $offset
        "));
        $totalCount = DB::select("
            SELECT COUNT(*) AS total_count FROM (
                SELECT x.tglpulang
                    ,x.nosep
                    ,x.noregistrasi
                    ,x.namapasien
                    ,x.namaruangan
                    ,x.kelompokpasien
                    ,x.namarekanan
                    ,SUM(x.totalbiaya) AS totalbiaya
                    ,SUM(x.totaltidakdiklaim) AS totaltidakdiklaim
                    ,SUM(x.totalsudahdibayar) AS totalsudahdibayar
                    ,x.totaltarifrs
                    ,x.totalpengajuan
                    ,x.totalsetujui
                    ,x.nofpk
                    ,x.noposting
                FROM (
                    SELECT DISTINCT
                    bpjs.tglpulang,
                    bpjs.nosep,
                    pd.noregistrasi,
                    P.namapasien,
                    ru.namaruangan,
                    kp.kelompokpasien,
                    r.namarekanan,
                    COALESCE (spp.totalbiaya, 0) AS totalbiaya,
                    COALESCE (sp.totalharusdibayar, 0) AS totaltidakdiklaim,
                    COALESCE (spp.totalsudahdibayar, 0) AS totalsudahdibayar,
                    bpjs.totaltarifrs,
                    bpjs.totalpengajuan,
                    bpjs.totalsetujui,
                    bpjs.nofpk,
                    php.noposting
                    FROM monitoringklaim_t AS bpjs
                    INNER JOIN pemakaianasuransi_t AS pa ON pa.nosep = bpjs.nosep
                    INNER JOIN strukpelayanan_t AS sp ON sp.noregistrasifk = pa.noregistrasifk
                    LEFT JOIN strukpelayananpenjamin_t AS spp ON sp.norec = spp.nostrukfk
                    INNER JOIN pelayananpasien_t AS pp ON pp.strukfk = sp.norec
                    INNER JOIN antrianpasiendiperiksa_t AS ap ON ap.norec = pp.noregistrasifk
                    INNER JOIN pasiendaftar_t AS pd ON pd.norec = ap.noregistrasifk
                    LEFT JOIN rekanan_m AS rkn ON rkn.id = pd.objectrekananfk
                    LEFT JOIN pasien_m AS p ON p.id = pd.nocmfk
                    LEFT JOIN ruangan_m AS ru ON ru.id = pd.objectruanganlastfk
                    LEFT JOIN departemen_m AS dept ON dept.id = ru.objectdepartemenfk
                    LEFT JOIN rekanan_m AS r ON r.id = spp.kdrekananpenjamin
                    LEFT JOIN kelompokpasien_m AS kp ON kp.id = pd.objectkelompokpasienlastfk
                    LEFT JOIN postinghutangpiutang_t AS php ON php.nostrukfk = spp.norec
                    LEFT JOIN strukposting_t AS spt ON spt.noposting = php.noposting
                    WHERE sp.statusenabled = true
                    AND sp.kdprofile = ?
                    $tglAwal
                    $tglAkhir
                    $instalasiId
                    $ruanganId
                    $namaPasien
                    $noReg
                    $jenisPelayanan
                    $nofpk
                    $limit
                    $offset
                ) x
                GROUP BY x.tglpulang
                ,x.nosep
                ,x.noregistrasi
                ,x.namapasien
                ,x.namaruangan
                ,x.kelompokpasien
                ,x.namarekanan
                ,x.totaltarifrs
                ,x.totalpengajuan
                ,x.totalsetujui
                ,x.nofpk
                ,x.noposting
                UNION ALL
                SELECT
                bpjs.tglpulang,
                bpjs.nosep,
                null as noregistrasi,
                null as namapasien,
                null as namaruangan,
                null as kelompokpasien,
                null as namarekanan,
                0 AS totalbiaya,
                0 AS totaltidakdiklaim,
                0 AS totalsudahdibayar,
                bpjs.totaltarifrs,
                bpjs.totalpengajuan,
                bpjs.totalsetujui,
                bpjs.nofpk,
                null as noposting
                FROM monitoringklaim_t AS bpjs
                LEFT JOIN pemakaianasuransi_t AS pa ON pa.nosep = bpjs.nosep
                WHERE pa.nosep IS NULL
                $tglAwal
                $tglAkhir
                $jenisPelayanan
                $nofpk
            ) AS count_subquery
        ", [$idProfile]);
        $result = [];
        foreach ($dataPiutang as $item) {
            $result[] = array(
                'tglpulang' => $item->tglpulang,
                'nosep' => $item->nosep,
                'noRegistrasi' => $item->noregistrasi,
                'namaPasien' => $item->namapasien,
                'namaruangan' => $item->namaruangan,
                'jenisPasisen' => $item->kelompokpasien,
                'rekanan' => $item->namarekanan,
                'totalBilling' => $item->totalbiaya,
                'totaltidakdiklaim' => $item->totaltidakdiklaim,
                'totalBayar' => $item->totalsudahdibayar,
                'totaltarifrs' => $item->totaltarifrs,
                'totalpengajuan' => $item->totalpengajuan,
                'totalsetujui' => $item->totalsetujui,
                'nofpk' => $item->nofpk,
                'noposting' => $item->noposting,
            );
        }
        $data = [
            'data' => $result,
            'total' => isset($totalCount[0]->total_count) ? $totalCount[0]->total_count : 1
        ];
        return $this->respond($data);
    }
    public function daftarKartuPiutangPerusahaanPeriode(Request $request)
    {
        $kdProfile      = (int) $this->kdProfile;
        $filter         = $request->all();
        $dataCollector  = DB::table('postinghutangpiutang_t as php')
            ->join('strukpelayananpenjamin_t as spp', 'spp.norec', '=', 'php.nostrukfk')
            ->join('strukpelayanan_t as spy', 'spy.norec', '=', 'spp.nostrukfk')
            ->join('pasiendaftar_t as pd', 'pd.norec', '=', 'spy.noregistrasifk')
            ->leftjoin('rekanan_m as rkn', 'rkn.id', '=', 'pd.objectrekananfk')
            ->join('strukposting_t as sp', 'sp.noposting', '=', 'php.noposting')
            ->join('loginuser_s as lu', 'sp.kdhistorylogins', '=', 'lu.id')
            ->join('pegawai_m as p', 'lu.objectpegawaifk', '=', 'p.id')
            ->leftjoin('strukkwitansipiutang_t as skp', 'skp.norec', '=', 'php.strukkwitansipiutangfk')
            ->select(
                'sp.norec',
                'sp.tglposting',
                'php.noposting',
                'rkn.namarekanan',
                'rkn.kodeexternal as partnercode',
                'php.statusenabled',
                'p.namalengkap',
                'php.nomorreferencebri',
                'skp.norec AS norec_skp',
                'php.keteranganlainnya AS keterangan',
                'skp.nokwitansi',
                DB::raw('SUM(spp.totalppenjamin) as totalpenjamin'),
                DB::raw('sum(spp.totalsudahdibayar) as sumtotalsudahdibayar'),
                DB::raw("count(php.noposting) as jlhpasien,CASE WHEN spp.totaldiskon IS NULL THEN 0 ELSE spp.totaldiskon END AS totaldiskon "),
                DB::raw("sp.tglposting, sp.keteranganlainnya, php.noposting, rkn.namarekanan, php.statusenabled,
                 'KPS-'|| rkn.id as idrekanan,'KPS-'|| rkn.id ||' ' || rkn.namarekanan as kps")
            )
            ->where('php.kdprofile', $kdProfile);
        $dataCollector = $dataCollector->where('pd.objectrekananfk', '=', $filter['idPerusahaan'])->where('php.noposting', $filter['noposting']);
        $dataCollector = $dataCollector->where('sp.statusenabled', '=', '1');
        $dataCollector = $dataCollector->where('php.statusenabled', 1);
        $dataCollector = $dataCollector->groupBy(
            'sp.norec',
            'php.noposting',
            'sp.tglposting',
            'rkn.id',
            'rkn.namarekanan',
            'php.statusenabled',
            'p.namalengkap',
            'php.nomorreferencebri',
            'rkn.kodeexternal',
            'skp.norec',
            'skp.nokwitansi',
            'spp.totaldiskon',
            'php.keteranganlainnya'
        );
        $dataCollector = $dataCollector->get();

        $data = array();
        $saldo = 0;
        foreach ($dataCollector as $item) {
            $saldo += $item->totalpenjamin - $item->sumtotalsudahdibayar;
            $data[] = array(
                'noCollect' => $item->noposting,
                'tglCollect' => $item->tglposting,
                'piutang' => $item->totalpenjamin,
                'bayar' => $item->sumtotalsudahdibayar,
                'idrekanan' => $item->idrekanan,
                'namarekanan' => $item->namarekanan,
                'statusenabled' => $item->statusenabled,
                'adm' => 0,
                'saldo' => $item->totalpenjamin - $item->sumtotalsudahdibayar,
                'keterangan' => $item->keterangan
            );
        }
        $result[] = array(
            'data' => $data,
            'terbilang' => $this->terbilang($saldo),
        );
        return $this->respond($result);
    }
    public function daftarPembayaranPiutangPeriode(Request $request)
    {
        $kdProfile      = (int) $this->kdProfile;
        $filter         = $request->all();
        $tglawal        = $request->tglAwal;
        $tglakhir       = $request->tglAkhir;
        $dataCollector = DB::table('postinghutangpiutang_t as php')
            ->join('strukpelayananpenjamin_t as spp', 'spp.norec', '=', 'php.nostrukfk')
            ->join('strukbuktipenerimaan_t as sbm', 'sbm.nostrukfk', '=', 'spp.nostrukfk')
            ->join('strukpelayanan_t as spy', 'spy.norec', '=', 'spp.nostrukfk')
            ->join('pasiendaftar_t as pd', 'pd.norec', '=', 'spy.noregistrasifk')
            ->join('rekanan_m as rkn', 'rkn.id', '=', 'pd.objectrekananfk')
            ->join('strukposting_t as sp', 'sp.noposting', '=', 'php.noposting')
            ->join('loginuser_s as lu', 'sp.kdhistorylogins', '=', 'lu.id')
            ->select(
                'sbm.tglsbm',
                'php.noposting',
                'rkn.id as idRekanan',
                'rkn.namarekanan',
                'php.statusenabled',
                'sbm.keteranganlainnya',
                DB::raw('sum(sbm.totaldibayar) as totaldibayar')
            )
            ->where('php.kdprofile', $kdProfile);
        if (isset($filter['tglAwal']) && $filter['tglAwal'] != "" && $filter['tglAwal'] != "undefined") {
            $dataCollector = $dataCollector->where('sbm.tglsbm', '>=', $filter['tglAwal']);
        }
        if (isset($filter['tglAkhir']) && $filter['tglAkhir'] != "" && $filter['tglAwal'] != "undefined") {
            $dataCollector = $dataCollector->where('sbm.tglsbm', '<=', $filter['tglAkhir']);
        }
        if (isset($filter['noPosting']) && $filter['noPosting'] != "") {
            $dataCollector = $dataCollector->where('sp.noposting', 'ilike', '%' . $filter['noPosting'] . '');
        }
        if (isset($filter['idPerusahaan']) && $filter['idPerusahaan'] != "") {
            $dataCollector = $dataCollector->where('rkn.id', '=', $filter['idPerusahaan']);
        }
        $dataCollector = $dataCollector->where('sp.statusenabled', '=', 1);
        $dataCollector = $dataCollector->where('sbm.objectkelompoktransaksifk', $this->settingDataFixed('pembayaranCicilanPiutang', $kdProfile));
        $dataCollector = $dataCollector->groupBy('sbm.tglsbm', 'php.noposting', 'rkn.id', 'rkn.namarekanan', 'php.statusenabled', 'sbm.keteranganlainnya');
        $dataCollector = $dataCollector->orderBy('sbm.tglsbm', 'desc');
        $dataCollector = $dataCollector->get();

        $result1 = array();
        foreach ($dataCollector as $item) {
            $bayar = 0;
            $totalbayar = 0;
            foreach ($dataCollector as $itemd) {
                $bayar = $totalbayar + $itemd->totaldibayar;
                $totalbayar = $bayar;
                $terbilang = $this->terbilang($totalbayar);
            }
            $result1[] = array(
                'noPosting' => $item->noposting,
                'tglBayar' => $item->tglsbm,
                'totalBayar' => $item->totaldibayar,
                'terbilang' => $terbilang,
                'idrekanan' => $item->idRekanan,
                'namarekanan' => $item->namarekanan,
                'statusenabled' => $item->statusenabled,
                'keterangan' => $item->keteranganlainnya
            );
        }

        $idPerusahaan = $request->idPerusahaan;
        if (isset($idPerusahaan) && $idPerusahaan != "") {
            $dataRekap = DB::table('postinghutangpiutang_t as php')
                ->selectRaw("to_char(sbm.tglsbm, 'yyyy-MM-dd') as tglbayar, 0 as adm, sbm.totaldibayar")
                ->join('strukpelayananpenjamin_t as spp', 'spp.norec', '=', 'php.nostrukfk')
                ->join('strukbuktipenerimaan_t as sbm', 'sbm.nostrukfk', '=', 'spp.nostrukfk')
                ->join('strukpelayanan_t as spy', 'spy.norec', '=', 'spp.nostrukfk')
                ->join('strukposting_t as sp', 'sp.noposting', '=', 'php.noposting')
                ->join('pasiendaftar_t as pd', 'pd.norec', '=', 'spy.noregistrasifk')
                ->join('rekanan_m as rkn', 'rkn.id', '=', 'pd.objectrekananfk')
                ->where('php.kdprofile', $kdProfile)
                ->where('rkn.id', $idPerusahaan)
                ->when($tglawal, function ($query) use ($tglawal) {
                    return $query->whereRaw("to_char(sbm.tglsbm, 'yyyy-MM-dd') >= ?", [$tglawal]);
                })
                ->when($tglakhir, function ($query) use ($tglakhir) {
                    return $query->whereRaw("to_char(sbm.tglsbm, 'yyyy-MM-dd') <= ?", [$tglakhir]);
                })
                ->where('sp.statusenabled', 1)
                ->where('sbm.objectkelompoktransaksifk', $this->settingDataFixed('pembayaranCicilanPiutang', $kdProfile))
                ->groupBy('tglbayar', 'adm', 'sbm.totaldibayar')
                ->orderBy('tglbayar')
                ->get();
        } else {
            $dataRekap = DB::table('postinghutangpiutang_t as php')
                ->selectRaw("to_char(sbm.tglsbm, 'yyyy-MM-dd') as tglbayar, 0 as adm, sbm.totaldibayar")
                ->join('strukpelayananpenjamin_t as spp', 'spp.norec', '=', 'php.nostrukfk')
                ->join('strukbuktipenerimaan_t as sbm', 'sbm.nostrukfk', '=', 'spp.nostrukfk')
                ->join('strukpelayanan_t as spy', 'spy.norec', '=', 'spp.nostrukfk')
                ->join('strukposting_t as sp', 'sp.noposting', '=', 'php.noposting')
                ->join('pasiendaftar_t as pd', 'pd.norec', '=', 'spy.noregistrasifk')
                ->join('rekanan_m as rkn', 'rkn.id', '=', 'pd.objectrekananfk')
                ->where('php.kdprofile', $kdProfile)
                ->where('sp.statusenabled', 1)
                ->when($tglawal, function ($query) use ($tglawal) {
                    return $query->whereRaw("to_char(sbm.tglsbm, 'yyyy-MM-dd') >= ?", [$tglawal]);
                })
                ->when($tglakhir, function ($query) use ($tglakhir) {
                    return $query->whereRaw("to_char(sbm.tglsbm, 'yyyy-MM-dd') <= ?", [$tglakhir]);
                })
                ->where('sbm.objectkelompoktransaksifk', $this->settingDataFixed('pembayaranCicilanPiutang', $kdProfile))
                ->groupBy('tglbayar', 'adm', 'sbm.totaldibayar')
                ->orderBy('tglbayar')
                ->get();
        }

        $result2 = array();
        foreach ($dataRekap as $data) {
            $result2[] = array(
                'tglBayar' => $data->tglbayar,
                'adm' => $data->adm,
                'totalBayar' => $data->totaldibayar
            );
        }
        $result[] = array(
            'data' => $result1,
            'rekap' => $result2
        );

        return $this->respond($result);
    }
    public function RekapKlainDiagnosaTXT(Request $request)
    {
        if ($request['ptd'] == '1') {
            $data = DB::select(
                DB::raw(
                    "select  x.diaglist[1],diag.namadiagnosa,sum(x.tarif) as total,count(x.sep) as qty  from
                    (select DISTINCT regexp_split_to_array(\"diaglist\", ';' ) as diaglist,\"tarif_inacbg\" as tarif,sep from bpjsklaimtxt_t
                    where \"ptd\"='1') as x
                    INNER JOIN diagnosa_m as diag on x.diaglist[1]=diag.kddiagnosa
                    group by diag.namadiagnosa,x.diaglist[1]
                    order by count(x.sep) desc;
              "
                )
            );
        } elseif ($request['ptd'] == '2') {
            $data = DB::select(
                DB::raw(
                    "select  x.diaglist[1],diag.namadiagnosa,sum(x.tarif) as total,count(x.sep) as qty  from
                    (select DISTINCT regexp_split_to_array(\"diaglist\", ';' ) as diaglist,\"tarif_inacbg\" as tarif,sep from bpjsklaimtxt_t
                    where \"ptd\"='2') as x
                    INNER JOIN diagnosa_m as diag on x.diaglist[1]=diag.kddiagnosa
                    group by diag.namadiagnosa,x.diaglist[1]
                    order by count(x.sep) desc;
              "
                )
            );
        }


        $hasilna = array(
            'data' => $data,
            'by' => 'as@epic'
        );
        return  $this->respond($hasilna);
    }
    public function simpanBpjsKlaim(Request $request)
    {
        $kdProfile = (int) $this->kdProfile;
        DB::beginTransaction();
        try {
            BPJSKlaimTxt::where('txtfilename', $request['fileName'])->delete();
            $result = [];
            foreach ($request['data'] as $item) {
                $data1 = new BPJSKlaimTxt();
                $data1->norec = $data1->generateNewId();
                $data1->kdprofile = $kdProfile;
                $data1->statusenabled = true;


                $data1->kode_rs = $item['KODE_RS'];
                $data1->kelas_rs = $item['KELAS_RS'];
                $data1->kelas_rawat = $item['KELAS_RAWAT'];
                $data1->kode_tarif = $item['KODE_TARIF'];
                $data1->ptd = $item['PTD'];
                $data1->admission_date = $item['ADMISSION_DATE'];
                $data1->discharge_date = $item['DISCHARGE_DATE'];
                $data1->birth_date = $item['BIRTH_DATE'];
                $data1->birth_weight = $item['BIRTH_WEIGHT'];
                $data1->sex = $item['SEX'];
                $data1->discharge_status = $item['DISCHARGE_STATUS'];
                $data1->diaglist = $item['DIAGLIST'];
                $data1->proclist = $item['PROCLIST'];
                $data1->adl1 = $item['ADL1'];
                $data1->adl2 = $item['ADL2'];
                $data1->in_sp = $item['IN_SP'];
                $data1->in_sr = $item['IN_SR'];
                $data1->in_si = $item['IN_SI'];
                $data1->in_sd = $item['IN_SD'];
                $data1->inacbg = $item['INACBG'];
                $data1->subacute = $item['SUBACUTE'];
                $data1->chronic = $item['CHRONIC'];
                $data1->sp = $item['SP'];
                $data1->sr = $item['SR'];
                $data1->si = $item['SI'];
                $data1->sd = $item['SD'];
                $data1->deskripsi_inacbg = $item['DESKRIPSI_INACBG'];
                $data1->tarif_inacbg = $item['TARIF_INACBG'];
                $data1->tarif_subacute = $item['TARIF_SUBACUTE'];
                $data1->tarif_chronic = $item['TARIF_CHRONIC'];
                $data1->deskripsi_sp = $item['DESKRIPSI_SP'];
                $data1->tarif_sp = $item['TARIF_SP'];
                $data1->deskripsi_sr = $item['DESKRIPSI_SR'];
                $data1->tarif_sr = $item['TARIF_SR'];
                $data1->deskripsi_si = $item['DESKRIPSI_SI'];
                $data1->tarif_si = $item['TARIF_SI'];
                $data1->deskripsi_sd = $item['DESKRIPSI_SD'];
                $data1->tarif_sd = $item['TARIF_SD'];
                $data1->total_tarif = $item['TOTAL_TARIF'];
                $data1->tarif_rs = $item['TARIF_RS'];
                $data1->tarif_poli_eks = $item['TARIF_POLI_EKS'];
                $data1->los = $item['LOS'];
                $data1->icu_indikator = $item['ICU_INDIKATOR'];
                $data1->icu_los = $item['ICU_LOS'];
                $data1->icu_indikator = $item['VENT_HOUR'];
                $data1->nama_pasien = $item['NAMA_PASIEN'];
                $data1->mrn = $item['MRN'];
                $data1->umur_tahun = $item['UMUR_TAHUN'];
                $data1->umur_hari = $item['UMUR_HARI'];
                $data1->dpjp = $item['DPJP'];
                $data1->sep = $item['SEP'];
                $data1->nokartu = $item['NOKARTU'];
                $data1->payor_id = $item['PAYOR_ID'];
                $data1->coder_id = $item['CODER_ID'];
                $data1->versi_inacbg = $item['VERSI_INACBG'];
                $data1->versi_grouper = $item['VERSI_GROUPER'];
                $data1->c1 = $item['C1'];
                $data1->c2 = $item['C2'];
                $data1->c3 = $item['C3'];
                $data1->c4 = $item['C4'];
                $data1->txtfilename = $request['fileName'];
                $data1->save();
                $result[] = $data1;
            }
            $transStatus = 'true';
        } catch (Exception $e) {
            $transStatus = 'false';
        }

        $transMessage = "Simpan BPJS Klaim";
        if ($transStatus != 'false') {
            DB::commit();
            $result = array(
                "status" => 201,
                "message" => $transMessage . ' Berhasil',
                "result" => $result
            );
        } else {
            DB::rollBack();
            $result = array(
                "status" => 400,
                "message" => $transMessage . ' Gagal' . $e->getMessage(),
                "result" => $result
            );
        }
        return $this->respond($result['result'], $result['status'], $result['message']);
    }
    public function CollectingFromTxtInaCbgs(Request $request)
    {
        $txtFileName = $request['fileName'];
        $dataSpp = DB::table('bpjsklaimtxt_t as bpjs')
            ->leftJoin('pemakaianasuransi_t as pa', 'pa.nosep', '=', 'bpjs.sep')
            ->leftJoin('pasiendaftar_t as pd', 'pd.norec', '=', 'pa.noregistrasifk')
            ->leftJoin('pasien_m as ps', 'ps.id', '=', 'pd.nocmfk')
            ->leftJoin('kelas_m as kls', 'kls.id', '=', 'pd.objectkelasfk')
            ->leftJoin('asuransipasien_m as ap', 'ap.id', '=', 'pa.objectasuransipasienfk')
            ->leftJoin('kelas_m as kls2', 'kls2.id', '=', 'ap.objectkelasdijaminfk')
            ->leftJoin('rekanan_m as rkn', 'rkn.id', '=', 'pd.objectrekananfk')
            ->leftJoin('kelompokpasien_m as kp', 'kp.id', '=', 'pd.objectkelompokpasienlastfk')
            ->leftJoin('strukpelayanan_t as sp', 'sp.noregistrasifk', '=', 'pd.norec')
            ->leftJoin('strukpelayananpenjamin_t as spp', 'spp.nostrukfk', '=', 'sp.norec')
            ->select(
                DB::raw('case when spp.noverifikasi is null then null else spp.norec end as norec'),
                'pd.tglregistrasi',
                DB::raw("case
                    when pd.noregistrasi is null then 'Register NOSEP : ' || bpjs.sep
                    when sp.norec is null then 'Null Verif Tarek ' || pd.noregistrasi
                    when spp.noverifikasi is null then 'Null Verif Piutang ' || pd.noregistrasi
                    else pd.noregistrasi
                end as noregistrasi"),
                DB::raw("case when ps.namapasien is null then bpjs.mrn || ' ' || bpjs.nama_pasien else ps.namapasien end as namapasien"),
                'kls.namakelas',
                'kp.id as kpid',
                'kp.kelompokpasien',
                'kls2.namakelas as kelasdijamin',
                'bpjs.tarif_rs as totalbiaya',
                'bpjs.tarif_inacbg as totalppenjamin',
                'rkn.namarekanan',
                'rkn.id as rknid',
                DB::raw('bpjs.tarif_rs - bpjs.tarif_inacbg as tarifselisihklaim'),
                'bpjs.tarif_inacbg as tarifinacbgs',
                DB::raw("'' as keterangan"),
                'bpjs.sep'
            )
            ->where('bpjs.txtfilename', $txtFileName)
            ->whereNull('sp.statusenabled')
            ->get();


        $result = array();
        foreach ($dataSpp as $item) {
            $result[] = array(
                'noRec' => $item->norec,
                'noPosting' => '',
                'tglPosting' => '',
                'tglTransaksi' => $item->tglregistrasi,
                'noRegistrasi' => $item->noregistrasi,
                'namaPasien' => $item->namapasien,
                'kelasRawat' => $item->namakelas,
                'collector' =>  '',
                'kpid' => $item->kpid,
                'jenisPasien' => $item->kelompokpasien,
                'kelasPenjamin' => $item->namakelas,
                'umur' => 0,
                'totalBilling' => $item->totalbiaya,
                'totalKlaim' => $item->totalppenjamin,
                'totalBayar' => 0,
                'status' => 'Piutang',
                'rknid' => $item->rknid,
                'namarekanan' => $item->namarekanan,
                'tarifselisihklaim' => $item->tarifselisihklaim,
                'tarifinacbgs' => $item->tarifinacbgs,
                'keterangan' => $item->keterangan,
                'sep' => $item->sep,
            );
        }

        return $this->respond($result);
    }
    public function collectionPiutang(Request $request)
    {
        $key = $request->get('key');
        // return response()->json(['data' =>$key]);
        switch ($key) {
            case 'pelayanan':
                break;
            case 'nonpelayanan':
                break;
            case 'bpjs_klaim_inacbgs':
                return $this->CollectingFromTxtInaCbgs($request);
                break;
            case 'bpjs_klaim_bpjs_api':
                return $this->collectedPiutangApi($request);
                break;
            case 'no_posting':
                return $this->collectedPiutang($request, $request->get('no_posting'));
                break;
            default:
                break;
        }
    }
    public function collectedPiutangApi(Request $request)
    {
        $kdProfile = (int) $this->kdProfile;
        $dataSpp = DB::table('monitoringklaim_t as bpjs')
            ->leftJoin('pemakaianasuransi_t as pa', 'bpjs.nosep', '=', 'pa.nosep')
            ->leftJoin('pasiendaftar_t as pd', 'pd.norec', '=', 'pa.noregistrasifk')
            ->leftJoin('strukpelayanan_t as sp', 'pd.norec', '=', 'sp.noregistrasifk')
            ->leftJoin('strukpelayananpenjamin_t as spp', 'sp.norec', '=', 'spp.nostrukfk')
            ->leftjoin('bpjsgagalklaimtxt_t as gagalbpjs', 'gagalbpjs.nosep', '=', 'pa.nosep')
            ->leftJoin('pasien_m as p', 'p.id', '=', 'pd.nocmfk')
            ->leftJoin('postinghutangpiutang_t as php', 'php.nostrukfk', '=', 'spp.norec')
            ->leftJoin('strukposting_t as stp', 'stp.noposting', '=', 'php.noposting')
            ->leftJoin('rekanan_m as r', 'r.id', '=', 'pd.objectrekananfk')
            ->leftJoin('kelas_m as kls', 'kls.id', '=', 'pd.objectkelasfk')
            ->leftJoin('kelompokpasien_m as kp', 'kp.id', '=', 'pd.objectkelompokpasienlastfk')
            ->leftJoin('strukkwitansipiutang_t as skp', 'skp.norec', '=', 'php.strukkwitansipiutangfk')
            ->select(
                DB::raw("case
                when pd.noregistrasi is null then 'Register NOSEP : ' || bpjs.nosep
                when sp.norec is null then 'Null Verif Tarek ' || pd.noregistrasi
                when spp.noverifikasi is null then 'Null Verif Piutang ' || pd.noregistrasi
                else pd.noregistrasi
                end as noregistrasi"),
                'kp.id as kpid',
                'kp.kelompokpasien',
                'spp.norec',
                'stp.tglposting',
                'pd.tglregistrasi',
                'p.nocm',
                'p.namapasien',
                'spp.totalppenjamin',
                'spp.totalharusdibayar as tarifklaim',
                'bpjs.totalsetujui as tarifklaimbpjs',
                'spp.totalsudahdibayar',
                'r.id as rknid',
                'r.namarekanan',
                'spp.totalbiaya',
                'spp.noverifikasi',
                'php.noposting',
                'stp.kdhistorylogins',
                'kls.namakelas',
                'stp.tglposting',
                'p.tgllahir',
                'gagalbpjs.keterangan',
                'skp.norec AS norec_skp',
                'skp.nomorsurat AS nokwitansi',
                'bpjs.nofpk'
            )
            // ->whereNotNull('spp.noverifikasi')
            ->where('bpjs.kdprofile', $kdProfile);
        // ->where('php.statusenabled', true);
        $dataSpp = $dataSpp->when($request->nofpk, function ($query) use ($request) {
            return $query->where('bpjs.nofpk', $request->nofpk);
        });

        $dataSpp = $dataSpp->orderBy('p.namapasien');
        $dataSpp = $dataSpp->get();
        $result = array();

        foreach ($dataSpp as $item) {
            // $namaUser = LoginUser::where('id', $item->kdhistorylogins)->first();
            // $SPP = StrukPelayananPenjamin::find($item->norec);
            if ($item->tarifklaimbpjs == null) {
                $tarifklaim = (float)$item->totalppenjamin;
                $selisihKlaim = 0;
            } else {
                $tarifklaim = (float)$item->tarifklaimbpjs;
                $selisihKlaim = (float)$item->tarifklaimbpjs - (float)$item->totalppenjamin;
            }
            $result[] = array(
                'noRec' => $item->norec,
                'noPosting' => $item->noposting,
                'tglPosting' => $item->tglposting,
                'tglTransaksi' => $item->tglregistrasi,
                'noRegistrasi' => $item->noregistrasi,
                'namaPasien' => $item->namapasien,
                'kelasRawat' => $item->namakelas,
                // 'collector' =>  $namaUser->pegawai->namalengkap ?? "",
                'kpid' => $item->kpid,
                'jenisPasien' => $item->kelompokpasien,
                'kelasPenjamin' => $item->namakelas,
                'umur' => $this->hitungUmur($item->tgllahir),
                'totalBilling' => $item->totalbiaya,
                'totalKlaim' => $tarifklaim, // $item->totalppenjamin,
                'totalBayar' => $item->totalsudahdibayar,
                // 'status' => $SPP->StatusCollectingPiutang,
                'rknid' => $item->rknid,
                'namarekanan' => $item->namarekanan,
                'tarifselisihklaim' => $selisihKlaim,
                'tarifinacbgs' => $tarifklaim,
                'keterangan' => $item->keterangan,
                'norec_skp' => $item->norec_skp,
                'nokwitansi' => $item->nokwitansi,
                'nofpk' => $item->nofpk,
            );
        }

        return $this->respond($result);
    }
    public function getChecklistKlaim(Request $request)
    {
        $kdProfile = (int) $this->kdProfile;
        $data = DB::select(
            DB::raw("select tgl,
                sum(case when objectdepartemenfk <> 16 then  BPJS else 0 end) as bpjs_rajal,
                sum(case when objectdepartemenfk <> 16 then  dokumen else 0 end) as berkas_rajal,
                sum(case when objectdepartemenfk=16 and objectkelasdijaminfk=3 then  dokumen else 0 end) as berkas_kls1,
                sum(case when objectdepartemenfk=16 and objectkelasdijaminfk=2 then  dokumen else 0 end) as berkas_kls2,
                sum(case when objectdepartemenfk=16 and objectkelasdijaminfk=1 then  dokumen else 0 end) as berkas_kls3,
                sum(case when objectdepartemenfk=16 and objectkelasdijaminfk=3 then  BPJS else 0 end) as bpjs_kls1,
                sum(case when objectdepartemenfk=16 and objectkelasdijaminfk=2 then  BPJS else 0 end) as bpjs_kls2,
                sum(case when objectdepartemenfk=16 and objectkelasdijaminfk=1 then  BPJS else 0 end) as bpjs_kls3
                 from
                (select to_char(pd.tglpulang, 'YYYY-MM-DD') as tgl, ru.objectdepartemenfk,pd.objectkelasfk,kls.namakelas,ap.objectkelasdijaminfk,
                case when bpjs.norec is null then 0 else 1 end as BPJS,case when pa.norec is null then 0 else 1 end as dokumen
                from pemakaianasuransi_t as pa
                INNER JOIN asuransipasien_m as ap on ap.id=pa.objectasuransipasienfk
                inner JOIN monitoringklaim_t as bpjs  on pa.nosep=bpjs.nosep
                INNER JOIN pasiendaftar_t as pd on pd.norec=pa.noregistrasifk
                INNER JOIN ruangan_m as ru on ru.id=pd.objectruanganlastfk
                INNER JOIN kelas_m as kls on kls.id=ap.objectkelasdijaminfk
                where pa.kdprofile = $kdProfile and pd.tglpulang between :tglAwal and :tglAkhir
                and pd.objectkelompokpasienlastfk=2) as x group by tgl order by tgl;
            "),
            array(
                'tglAwal' => $request['tglAwal'],
                'tglAkhir' => $request['tglAkhir']
            )
        );
        return $this->respond($data);
    }
    public function simpanGagalHitungBpjsKlaim(Request $request)
    {
        $kdProfile = (int) $this->kdProfile;
        DB::beginTransaction();
        try {
            $data = [];
            BPJSGagalKlaimTxt::where('txtfilename', $request['filename'])->delete();
            foreach ($request['data'] as $item) {
                $data1 = new BPJSGagalKlaimTxt();
                $data1->norec = $data1->generateNewId();
                $data1->kdprofile = $kdProfile;
                $data1->statusenabled = true;

                $data1->nosep = $item['NOSEP'];
                $data1->tglsep = $item['TGLSEP'];
                $data1->nokartu = $item['NOKARTU'];
                $data1->nmpeserta = $item['NMPESERTA'];
                $data1->rirj = $item['RIRJ'];
                $data1->kdinacbg = $item['KDINACBG'];
                $data1->bypengajuan = $item['BYPENGAJUAN'];
                $data1->keterangan = $item['KETERANGAN'];
                $data1->txtfilename = $request['filename'];
                $data1->save();
                $data[] = $data1;
            }

            $transStatus = 'true';
        } catch (Exception $e) {
            $transStatus = 'false';
            $transMessage = $e->getMessage() . $e->getLine();
        }
        if ($transStatus != 'false') {
            DB::commit();
            $result = array(
                "status" => 201,
                "message" => "Simpan BPJS Gagal Klaim" . ' Berhasil',
                "result" => $data
            );
        } else {
            DB::rollBack();
            $result = array(
                "status" => 400,
                "message" => $transMessage . ' Gagal',
                'result' => ''
            );
        }

        return $this->respond($result['result'], $result['status'], $result['message']);
    }
    public function umurPiutang(Request $request)
    {
        $kdProfile = (int) $this->kdProfile;
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
            ->leftjoin('strukbuktipenerimaan_t as sbp', 'sbp.nostrukfk', '=', 'spp.nostrukfk')
            ->select(DB::raw("
                sbp.tglsbm AS tglpembayaran,spp.totalsudahdibayar,sbp.totaldibayar,p.namapasien,spp.totalppenjamin,
                kp.kelompokpasien,pd.nosbmlastfk,pd.tglpulang AS tglstruk,pd.noregistrasi,rkn.id as idrekanan,rkn.namarekanan AS namarekanan
            "))
            ->where('spp.kdprofile', $kdProfile)
            ->whereNotNull('spp.noverifikasi')
            ->where('sp.statusenabled', true);
        if (isset($filter['rekananfk']) && $filter['rekananfk'] != "") {
            $dataPiutang = $dataPiutang->where('pd.objectrekananfk', '=', $filter['rekananfk']);
        }
        $dataPiutang = $dataPiutang->get();
        $dataGrouped = [];
        foreach ($dataPiutang as $item) {
            $patientKey = $item->namapasien . '-' . $item->kelompokpasien . '-' . $item->tglstruk . '-' . $item->noregistrasi . '-' . $item->idrekanan;
            if ($item->nosbmlastfk == null && $item->totalppenjamin > $item->totalsudahdibayar) {
                if (!isset($item->noposting)) {
                    $status = 'Piutang';
                } else {
                    $status = 'Collecting';
                }
            } elseif ($item->nosbmlastfk != null && $item->totalppenjamin > $item->totalsudahdibayar) {
                $status = 'Piutang';
            } else {
                $status = 'Lunas';
            }
            if (!isset($dataGrouped[$patientKey])) {
                $dataGrouped[$patientKey] = [
                    'namapasien' => $item->namapasien,
                    'kelompokpasien' => $item->kelompokpasien,
                    'tglstruk' => $item->tglstruk,
                    'noregistrasi' => $item->noregistrasi,
                    'idrekanan' => $item->idrekanan,
                    'namarekanan' => $item->namarekanan,
                    'totalklaim' => $item->totalppenjamin,
                    'status' => $status,
                    'umur' => $this->getAge($item->tglstruk, date('Y-m-d H:i:s')),
                    'sudahbayar' => 0,
                    'bulan3' => [
                        'totaldibayar' => 0,
                        'sudahbayar' => 0,
                        'sisa' => 0,
                    ],
                    'bulan6' => [
                        'totaldibayar' => 0,
                        'sudahbayar' => 0,
                        'sisa' => 0,
                    ],
                    'bulan9' => [
                        'totaldibayar' => 0,
                        'sudahbayar' => 0,
                        'sisa' => 0,
                    ],
                    'bulan12' => [
                        'totaldibayar' => 0,
                        'sudahbayar' => 0,
                        'sisa' => 0,
                    ],
                    'lebihdari12' => [
                        'totaldibayar' => 0,
                        'sudahbayar' => 0,
                        'sisa' => 0,
                    ],
                ];
            }

            if ($item->tglpembayaran !== null || $item->totaldibayar !== null) {
                $sudahBayar = $item->totaldibayar;
                $dataGrouped[$patientKey]['sudahbayar'] += $sudahBayar;
                $sisa = $item->totalppenjamin - $dataGrouped[$patientKey]['sudahbayar'];
                $tglpembayaran = Carbon::parse($item->tglpembayaran);
                $tglstruk = Carbon::parse($item->tglstruk);
                $monthDiff = $tglpembayaran->diffInDays($tglstruk) / 30;
                if ($monthDiff <= 3) {
                    $bulanKey = 'bulan3';
                } elseif ($monthDiff <= 6) {
                    $bulanKey = 'bulan6';
                } elseif ($monthDiff <= 9) {
                    $bulanKey = 'bulan9';
                } elseif ($monthDiff <= 12) {
                    $bulanKey = 'bulan12';
                } else {
                    $bulanKey = 'lebihdari12';
                }
                $dataGrouped[$patientKey][$bulanKey]['totaldibayar'] += $item->totaldibayar;
                $dataGrouped[$patientKey][$bulanKey]['sudahbayar'] += $sudahBayar;
                $dataGrouped[$patientKey][$bulanKey]['sisa'] = $sisa;
            }
        }
        $dataGrouped = array_values($dataGrouped);
        $result = [
            'data' => $dataGrouped,
            'count' => count($dataPiutang)
        ];
        return $this->respond($result);
    }
    public function gagalKlaimBpjs(Request $request)
    {
        $kdProfile = (int) $this->kdProfile;
        $filter = $request->all();
        $data = DB::table('bpjsgagalklaimtxt_t AS  bpjs')
            ->leftjoin('pemakaianasuransi_t as pa', 'pa.nosep', '=', 'bpjs.nosep')
            ->leftJoin('antrianpasiendiperiksa_t as ap', 'ap.norec', '=', 'pa.noregistrasifk')
            ->leftJoin('pasiendaftar_t as pd', 'pd.norec', '=', 'ap.noregistrasifk')
            ->leftJoin('pasien_m as p', 'p.id', '=', 'pd.nocmfk')
            ->select('p.namapasien', 'bpjs.*');
        if (isset($filter['namapasien']) && $filter['namapasien'] != "") {
            $data = $data->where('ps.namapasien', '=', $filter['namapasien']);
        }
        if (isset($filter['tglAwal']) && $filter['tglAwal'] != "") {
            $data = $data->where('bpjs.tglsep', '>=', $filter['tglAwal']);
        }
        if (isset($filter['tglAkhir']) && $filter['tglAkhir'] != "") {
            $data = $data->where('bpjs.tglsep', '>=', $filter['tglAkhir']);
        }
        $result = [
            'data' => $data->get(),
            'count' => $data->count()
        ];
        return $this->respond($result);
    }
}
