<?php

namespace App\Http\Controllers\Remunerasi;

use App\Http\Controllers\Controller;
use App\Models\Master\GolonganPegawai;
use App\Models\Master\Jabatan;
use App\Models\Master\JenisPagu;
use App\Models\Master\KelompokPasien;
use App\Models\Master\Pegawai;
use App\Models\Master\Pendidikan;
use App\Models\Master\Ruangan;
use App\Models\Master\UnitKerjaPegawai;
use App\Models\Transaksi\DetailKelompokPenghasil;
use App\Models\Transaksi\DetailPegawaiPagu;
use App\Models\Transaksi\StrukClosing;
use App\Models\Transaksi\StrukDetailPagu;
use App\Models\Transaksi\StrukPagu;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Traits\Valet;
use Exception;
use Ramsey\Uuid\Uuid;

class RemunerasiCtrl extends Controller
{
    use Valet;
    public function __construct()
    {
        parent::__construct($is_encrypt = true);
    }

    public function getComboIdx()
    {
        $res['pendidikan'] = Pendidikan::where('statusenabled', true)->where('kdprofile', $this->kdProfile)
            ->select('id', 'pendidikan')->orderBy('pendidikan')->get();
        $res['jabatan'] = Jabatan::where('statusenabled', true)->where('kdprofile', $this->kdProfile)
            ->select('id', 'namajabatan')->orderBy('namajabatan')->get();
        $res['golonganpegawai'] = GolonganPegawai::where('statusenabled', true)->where('kdprofile', $this->kdProfile)
            ->select('id', 'name as golongan')->orderBy('name')->get();
        $res['jenispagu'] = JenisPagu::mine()->get();
        $res['pegawai'] = Pegawai::mine()->get();
        $res['unitkerja'] = UnitKerjaPegawai::where('statusenabled', true)->where('kdprofile', $this->kdProfile)->select('id', 'name as unitkerja')->orderBy('name')->get();
        $res['ruangan'] = Ruangan::select('namaruangan', 'id')->where('kdprofile', $this->kdProfile)->where('statusenabled', true)->get();
        $res['kelompokpasien'] = KelompokPasien::mine()->get();

        return $this->respond($res);
    }

    public function getRuangan(Request $request)
    {
        $data = Ruangan::select('namaruangan', 'id')->where('objectdepartemenfk', $request['departemenfk'])
            ->where('kdprofile', $this->kdProfile)->where('statusenabled', true)->get();

        return $this->respond($data);
    }

    public function paguRemunerasi(Request $request)
    {
        //TODO : CARI PAGU
        $kdProfile = $this->kdProfile;
        // $rangeDate = [$request->tglAwal, $request->tglAkhir];
        // $tglAwal = $request['tglAwal'];
        // $tglAkhir = $request['tglAkhir'];
        $nama = $request['nama'];
        $produk = $request['search'];
        $kpId = '';
        $search = '';
        $kpIn = '';
        $kpNotIn = '';
        if (isset($request['kpId']) && $request['kpId'] != '') {
            $kpId  = "  and pd.objectkelompokpasienlastfk in (" . $request['kpId'] . " )"; //explode(',',$request['kpId']);
        } else {
            $kpIn = ' and pd.objectkelompokpasienlastfk  in (2,4,5,10)';
            $kpNotIn = ' and pd.objectkelompokpasienlastfk not in (2,4,5,10)';
        }

        //        return $kpId;
        $dtdtdt2 = [];
        $dataPersen3 = [];

        $SCSC = StrukPagu::whereDate('periodeawal', $request['tglpelayanan'])->get();
        $StrukPagu = false;
        if (count($SCSC) > 0) {
            $StrukPagu = true;
        }
        $bayar = StrukPagu::whereDate('periodeawal', $request['tglpelayanan'])->where('isbayar', true)->get();
        $isbyar = false;
        if (count($bayar) > 0) {
            $isbyar = true;
        }
        $persenJaspel = $this->settingFix('PersenJasaPelayanan');
        $persenDireksi = $this->settingFix('PRDireksi'); // 2.5
        $persenStruktural = $this->settingFix('PRStruktural'); //6.5
        $persenAdm = $this->settingFix('PRAdministrasi'); // 1
        $persenJPL = $this->settingFix('PRJPL'); // 4.7
        $persenJPTL = $this->settingFix('PRJPTL'); // 11
        $persenGabungan = $this->settingFix('PRGabungan'); // 32

        /*
         * umum bpjs resep
         */
        $subQuery1 = DB::table('pelayananpasien_t AS pp')
            ->join('pelayananpasienpetugas_t AS ppp', 'ppp.pelayananpasien', 'pp.norec')
            ->leftJoin('antrianpasiendiperiksa_t AS apd', 'apd.norec', 'pp.noregistrasifk')
            ->join('pasiendaftar_t AS pd', 'pd.norec', 'apd.noregistrasifk')
            ->leftJoin('kelompokpasien_m AS kp', 'kp.id', 'pd.objectkelompokpasienlastfk')
            ->leftJoin('pegawai_m AS pgpj', 'pgpj.id', 'ppp.objectpegawaifk')
            ->leftJoin('produk_m AS pr', 'pr.id', 'pp.produkfk')
            ->leftJoin('ruangan_m AS ru', 'ru.id', 'apd.objectruanganfk')
            ->join('strukpelayanan_t AS sp', 'sp.norec', 'pp.strukfk')
            ->join('strukbuktipenerimaan_t AS sbm', 'sbm.norec', 'sp.nosbmlastfk')
            ->select([
                'pd.noregistrasi',
                'pp.tglpelayanan',
                'pr.namaproduk',
                'kp.kelompokpasien',
                DB::raw('case when kp.kelompokpasien = \'BPJS\' then \'JKN\' else \'NONJKN\' end as kelompokpasienformat'),
                DB::raw('((pp.hargajual - COALESCE(pp.hargadiscount, 0) + COALESCE(pp.jasa, 0)) * pp.jumlah) AS jasapelayanan,0 AS totalbilling,0 AS totalklaim'),
                'pgpj.namalengkap AS dokterpj',
                'pgpj.id AS dokterpjid',
                'apd.norec AS norec_apd',
                'pp.jumlah',
                'pp.norec AS norec_pp',
                'pp.produkfk',
                'ppp.objectpegawaifk',
                'ru.objectdepartemenfk',
                'apd.objectruanganfk',
                'pp.isparamedis',
                DB::raw('(CASE WHEN pp.jasa IS NULL THEN 0 ELSE pp.jasa END) * pp.jumlah AS jasa'),
                'ru.namaruangan'
            ])
            ->whereDate('pp.tglpelayanan', $request['tglpelayanan'])
            ->where('ppp.objectjenispetugaspefk', $this->settingFix('idDokterPemeriksa'))
            ->where('ppp.statusenabled', true)
            ->where('sbm.statusenabled', true)
            ->where('pd.kdprofile', $this->kdProfile);
        if ((isset($request['kpId']))) {
            $subQuery1 = $subQuery1->where('pd.objectkelompokpasienlastfk', $request['kpId']);
        } else {
            $subQuery1 = $subQuery1->whereNotIn('pd.objectkelompokpasienlastfk', [2, 4, 5, 10]);
        }
        if ((isset($request['dokterfk']))) {
            $subQuery1 = $subQuery1->where('ppp.objectpegawaifk', $request['dokterfk']);
        }
        if ((isset($request['search']))) {
            $searchTerm = '%' . $request['search'] . '%';
            $subQuery1 = $subQuery1->where(function ($query) use ($searchTerm) {
                $query->where('pr.namaproduk', 'ilike', $searchTerm);
                // $query->orWhere('pgpj.namalengkap', 'ilike', $searchTerm);
            });
        }
        $query1 = DB::table(DB::raw("({$subQuery1->toSql()}) as x"))
            ->mergeBindings($subQuery1)
            ->select([
                // DB::raw(
                //     "CASE WHEN x.objectruanganfk = 111 THEN (x.jasapelayanan * 25 / 100) * $persenJaspel / 100  ELSE (x.jasapelayanan * $persenJaspel / 100) END AS jaspelproporsi"
                // ), 'x.*',
                DB::raw(
                    "(x.jasapelayanan * $persenJaspel / 100) AS jaspelproporsi"
                ), 'x.*',
            ]);

        $subQuery2 = DB::table('pelayananpasien_t AS pp')
            ->join('pelayananpasienpetugas_t AS ppp', 'ppp.pelayananpasien', 'pp.norec')
            ->leftJoin('antrianpasiendiperiksa_t AS apd', 'apd.norec', 'pp.noregistrasifk')
            ->join('pasiendaftar_t AS pd', 'pd.norec', 'apd.noregistrasifk')
            ->leftJoin('kelompokpasien_m AS kp', 'kp.id', 'pd.objectkelompokpasienlastfk')
            ->leftJoin('pegawai_m AS pgpj', 'pgpj.id', 'ppp.objectpegawaifk')
            ->leftJoin('produk_m AS pr', 'pr.id', 'pp.produkfk')
            ->leftJoin('ruangan_m AS ru', 'ru.id', 'apd.objectruanganfk')
            ->join('strukpelayanan_t AS sp', 'sp.norec', 'pp.strukfk')
            ->join('strukpelayananpenjamin_t AS spp', 'spp.nostrukfk', 'sp.norec')
            ->leftJoin('pemakaianasuransi_t AS pa', 'pa.noregistrasifk', 'pd.norec')
            ->leftJoin('bpjsklaimtxt_t AS bpjs', 'bpjs.sep', 'pa.nosep')
            ->leftJoin('nonbpjsklaimtxt_t AS nonbpjs', 'nonbpjs.norec_pd', 'pd.norec')
            ->select([
                'pd.noregistrasi',
                'pp.tglpelayanan',
                'pr.namaproduk',
                'kp.kelompokpasien',
                DB::raw('case when kp.kelompokpasien = \'BPJS\' then \'JKN\' else \'NONJKN\' end as kelompokpasienformat'),
                DB::raw('((pp.hargajual - COALESCE(pp.hargadiscount, 0) + COALESCE(pp.jasa, 0)) * pp.jumlah) AS jasapelayanan'),
                DB::raw('spp.totalbiaya AS totalbilling'),
                DB::raw('(CASE WHEN pd.objectkelompokpasienlastfk <> 5 THEN (CASE WHEN bpjs.tarif_inacbg IS NOT NULL THEN bpjs.tarif_inacbg ELSE spp.totalppenjamin END) ELSE (CASE WHEN nonbpjs.nominal IS NOT NULL THEN nonbpjs.nominal ELSE spp.totalppenjamin END) END) AS totalklaim'),
                'pgpj.namalengkap AS dokterpj',
                'pgpj.id AS dokterpjid',
                'apd.norec AS norec_apd',
                'pp.jumlah',
                'pp.norec AS norec_pp',
                'pp.produkfk',
                'ppp.objectpegawaifk',
                'ru.objectdepartemenfk',
                'apd.objectruanganfk',
                'pp.isparamedis',
                DB::raw('(CASE WHEN pp.jasa IS NULL THEN 0 ELSE pp.jasa END) * pp.jumlah AS jasa'),
                'ru.namaruangan'
            ])
            ->distinct()
            ->whereDate('pp.tglpelayanan', $request['tglpelayanan'])
            ->where('ppp.objectjenispetugaspefk', $this->settingFix('idDokterPemeriksa'))
            ->where('ppp.statusenabled', true)
            ->where('pd.kdprofile', $this->kdProfile)
            ->where('ppp.statusenabled', '=', true)
            ->whereNotNull('bpjs.norec')
            ->orWhereNotNull('nonbpjs.norec');
        if ((isset($request['kpId']))) {
            $subQuery2 = $subQuery2->where('pd.objectkelompokpasienlastfk', $request['kpId']);
        } else {
            $subQuery2 = $subQuery2->whereIn('pd.objectkelompokpasienlastfk', [2, 4, 5, 10]);
        }
        if ((isset($request['dokterfk']))) {
            $subQuery2 = $subQuery2->where('ppp.objectpegawaifk', $request['dokterfk']);
        }
        if ((isset($request['search']))) {
            $searchTerm = '%' . $request['search'] . '%';
            $subQuery2 = $subQuery2->where(function ($query) use ($searchTerm) {
                $query->where('pr.namaproduk', 'ilike', $searchTerm);
                // $query->orWhere('pgpj.namalengkap', 'ilike', $searchTerm);
            });
        }

        $query2 = DB::table(DB::raw("({$subQuery2->toSql()}) as x"))
            ->mergeBindings($subQuery2)
            ->select([
                DB::raw(" (((x.totalklaim/x.totalbilling)*x.jasapelayanan) * $persenJaspel/100) as jaspelproporsi"), 'x.*',
                // DB::raw("CASE WHEN x.objectruanganfk = 111 then (((x.totalklaim/x.totalbilling)*x.jasapelayanan) * 25/100) *$persenJaspel/100
                //          ELSE (((x.totalklaim/x.totalbilling)*x.jasapelayanan) * $persenJaspel/100) end as jaspelproporsi"), 'x.*',
            ]);

        $subQuery3 = DB::table('pelayananpasien_t AS pp')
            ->join('strukresep_t AS sr', 'sr.norec', 'pp.strukresepfk')
            ->leftJoin('antrianpasiendiperiksa_t AS apd', 'apd.norec', 'pp.noregistrasifk')
            ->join('pasiendaftar_t AS pd', 'pd.norec', 'apd.noregistrasifk')
            ->leftJoin('kelompokpasien_m AS kp', 'kp.id', 'pd.objectkelompokpasienlastfk')
            ->leftJoin('pegawai_m AS pgpj', 'pgpj.id', 'sr.penulisresepfk')
            ->leftJoin('produk_m AS pr', 'pr.id', 'pp.produkfk')
            ->leftJoin('ruangan_m AS ru', 'ru.id', 'apd.objectruanganfk')
            ->join('strukpelayanan_t AS sp', 'sp.norec', 'pp.strukfk')
            ->join('strukbuktipenerimaan_t AS sbm', 'sbm.norec', 'sp.nosbmlastfk')
            ->select([
                'pd.noregistrasi',
                'pp.tglpelayanan',
                'pr.namaproduk',
                'kp.kelompokpasien',
                DB::raw('case when kp.kelompokpasien = \'BPJS\' then \'JKN\' else \'NONJKN\' end as kelompokpasienformat'),
                DB::raw('((pp.hargajual - COALESCE(pp.hargadiscount, 0) + COALESCE(pp.jasa, 0)) * pp.jumlah) AS jasapelayanan,0 AS totalbilling,0 AS totalklaim'),
                'pgpj.namalengkap AS dokterpj',
                'pgpj.id AS dokterpjid',
                'apd.norec AS norec_apd',
                'pp.jumlah',
                'pp.norec AS norec_pp',
                'pp.produkfk',
                'sr.penulisresepfk AS objectpegawaifk',
                'ru.objectdepartemenfk',
                'apd.objectruanganfk',
                'pp.isparamedis',
                DB::raw('(CASE WHEN pp.jasa IS NULL THEN 0 ELSE pp.jasa END) * pp.jumlah AS jasa'),
                'ru.namaruangan'
            ])
            ->whereDate('pp.tglpelayanan', $request['tglpelayanan'])
            ->where('sr.statusenabled', true)
            ->where('sbm.statusenabled', true)
            ->where('pd.kdprofile', $this->kdProfile);
        if ((isset($request['kpId']))) {
            $subQuery3 = $subQuery3->where('pd.objectkelompokpasienlastfk', $request['kpId']);
        } else {
            $subQuery3 = $subQuery3->whereNotIn('pd.objectkelompokpasienlastfk', [2, 4, 5, 10]);
        }
        if ((isset($request['dokterfk']))) {
            $subQuery3 = $subQuery3->where('sr.penulisresepfk', $request['dokterfk']);
        }
        if ((isset($request['search']))) {
            $searchTerm = '%' . $request['search'] . '%';
            $subQuery3 = $subQuery3->where(function ($query) use ($searchTerm) {
                $query->where('pr.namaproduk', 'ilike', $searchTerm);
                // $query->orWhere('pgpj.namalengkap', 'ilike', $searchTerm);
            });
        }
        $query3 = DB::table(DB::raw("({$subQuery3->toSql()}) as x"))
            ->mergeBindings($subQuery3)
            ->select([
                DB::raw("x.jasapelayanan * 25/100 * $persenJaspel/100 as jaspelproporsi"), 'x.*',
            ]);

        $subQuery4 = DB::table('pelayananpasien_t AS pp')
            ->join('strukresep_t AS sr', 'sr.norec', 'pp.strukresepfk')
            ->leftJoin('antrianpasiendiperiksa_t AS apd', 'apd.norec', 'pp.noregistrasifk')
            ->join('pasiendaftar_t AS pd', 'pd.norec', 'apd.noregistrasifk')
            ->leftJoin('kelompokpasien_m AS kp', 'kp.id', 'pd.objectkelompokpasienlastfk')
            ->leftJoin('pegawai_m AS pgpj', 'pgpj.id', 'sr.penulisresepfk')
            ->leftJoin('produk_m AS pr', 'pr.id', 'pp.produkfk')
            ->leftJoin('ruangan_m AS ru', 'ru.id', 'apd.objectruanganfk')
            ->join('strukpelayanan_t AS sp', 'sp.norec', 'pp.strukfk')
            ->join('strukpelayananpenjamin_t AS spp', 'spp.nostrukfk', 'sp.norec')
            ->leftJoin('pemakaianasuransi_t AS pa', 'pa.noregistrasifk', 'pd.norec')
            ->leftJoin('bpjsklaimtxt_t AS bpjs', 'bpjs.sep', 'pa.nosep')
            ->leftJoin('nonbpjsklaimtxt_t AS nonbpjs', 'nonbpjs.norec_pd', 'pd.norec')
            ->select([
                'pd.noregistrasi',
                'pp.tglpelayanan',
                'pr.namaproduk',
                'kp.kelompokpasien',
                DB::raw('case when kp.kelompokpasien = \'BPJS\' then \'JKN\' else \'NONJKN\' end as kelompokpasienformat'),
                DB::raw('((pp.hargajual - COALESCE(pp.hargadiscount, 0) + COALESCE(pp.jasa, 0)) * pp.jumlah) AS jasapelayanan'),
                DB::raw('spp.totalbiaya AS totalbilling'),
                DB::raw('(CASE WHEN pd.objectkelompokpasienlastfk <> 5 THEN (CASE WHEN bpjs.tarif_inacbg IS NOT NULL THEN bpjs.tarif_inacbg ELSE spp.totalppenjamin END) ELSE (CASE WHEN nonbpjs.nominal IS NOT NULL THEN nonbpjs.nominal ELSE spp.totalppenjamin END) END) AS totalklaim'),
                'pgpj.namalengkap AS dokterpj',
                'pgpj.id AS dokterpjid',
                'apd.norec AS norec_apd',
                'pp.jumlah',
                'pp.norec AS norec_pp',
                'pp.produkfk',
                'sr.penulisresepfk AS objectpegawaifk',
                'ru.objectdepartemenfk',
                'apd.objectruanganfk',
                'pp.isparamedis',
                DB::raw('(CASE WHEN pp.jasa IS NULL THEN 0 ELSE pp.jasa END) * pp.jumlah AS jasa'),
                'ru.namaruangan'
            ])
            ->distinct()
            ->whereDate('pp.tglpelayanan', $request['tglpelayanan'])
            ->where('sr.statusenabled', true)
            ->where('pd.kdprofile', $this->kdProfile)
            ->whereNotNull('bpjs.norec')
            ->orWhereNotNull('nonbpjs.norec');
        if ((isset($request['kpId']))) {
            $subQuery4 = $subQuery4->where('pd.objectkelompokpasienlastfk', $request['kpId']);
        } else {
            $subQuery4 = $subQuery4->whereIn('pd.objectkelompokpasienlastfk', [2, 4, 5, 10]);
        }
        if ((isset($request['dokterfk']))) {
            $subQuery4 = $subQuery4->where('sr.penulisresepfk', $request['dokterfk']);
        }
        if ((isset($request['search']))) {
            $searchTerm = '%' . $request['search'] . '%';
            $subQuery4 = $subQuery4->where(function ($query) use ($searchTerm) {
                $query->where('pr.namaproduk', 'ilike', $searchTerm);
                // $query->orWhere('pgpj.namalengkap', 'ilike', $searchTerm);
            });
        }

        $query4 = DB::table(DB::raw("({$subQuery4->toSql()}) as x"))
            ->mergeBindings($subQuery4)
            ->select([
                DB::raw("(((x.totalklaim/x.totalbilling) * x.jasapelayanan )  * 25/100) * $persenJaspel/100 as jaspelproporsi"), 'x.*',
            ]);

        $result = $query1
            ->unionAll($query2)
            ->unionAll($query3)
            ->unionAll($query4)
            ->get();

        $totalBiling = DB::table('pelayananpasien_t as pp')
            ->selectRaw("SUM((pp.hargajual - COALESCE(pp.hargadiscount, 0) + COALESCE(pp.jasa, 0)) * pp.jumlah) AS total,pp.noregistrasi")
            ->whereDate('pp.tglpelayanan', $request['tglpelayanan'])
            ->groupBy('pp.noregistrasi')
            ->get();

        $dataAkhir = [];
        foreach ($result as $res) {
            foreach ($totalBiling as $total) {
                if ($res->noregistrasi == $total->noregistrasi) {
                    $dataAkhir[] = [
                        'noregistrasi' => $res->noregistrasi,
                        'tglpelayanan' =>  $res->tglpelayanan,
                        'jaspelproporsi' => $res->jaspelproporsi,
                        'jasapelayanan' => $res->jasapelayanan,
                        'namaproduk' => $res->namaproduk,
                        'kelompokpasien' => $res->kelompokpasien,
                        'dokterpj' => $res->dokterpj,
                        'dokterpjid' => $res->dokterpjid,
                        'norec_apd' => $res->norec_apd,
                        'jumlah' => $res->jumlah,
                        'norec_pp' => $res->norec_pp,
                        'produkfk' => $res->produkfk,
                        'objectpegawaifk' => $res->objectpegawaifk,
                        'objectdepartemenfk' => $res->objectdepartemenfk,
                        'objectruanganfk' => $res->objectruanganfk,
                        'isparamedis' => $res->isparamedis,
                        'jasa' => $res->jasa,
                        'namaruangan' => $res->namaruangan,
                        'totalbilling' => $total->total,
                        'totalklaim' => $res->totalklaim,
                        'kelompokpasienformat' => $res->kelompokpasienformat,
                    ];
                }
            }
        }

        // $dataDokterAnestesi = [];
        // return $this->settingFix('JenisPetugasAnestesi');
        $dataDokterAnestesi = DB::table('pelayananpasien_t as pp')
            ->join('pelayananpasienpetugas_t as ppp', 'ppp.pelayananpasien', 'pp.norec')
            ->leftjoin('antrianpasiendiperiksa_t as apd', 'apd.norec', 'pp.noregistrasifk')
            ->join('pasiendaftar_t as pd', 'pd.norec', 'apd.noregistrasifk')
            ->join('pegawai_m as pgpj', 'pgpj.id', 'ppp.objectpegawaifk')
            ->select(
                'pp.tglpelayanan',
                'ppp.objectpegawaifk as dokterpjid',
                'pp.jumlah',
                'pp.norec as norec_pp',
                'pp.produkfk',
                'ppp.objectjenispetugaspefk',
                'ppp.objectpegawaifk',
                'pp.isparamedis',
                'pgpj.namalengkap as dokterpj'
            )
            ->whereDate('pp.tglpelayanan', $request['tglpelayanan'])
            ->where('ppp.objectjenispetugaspefk', $this->settingFix('JenisPetugasAnestesi'))
            ->where('pp.kdprofile', $this->kdProfile)
            ->where('pp.statusenabled', true)
            ->where('pd.statusenabled', true)
            ->where('ppp.statusenabled', true)
            ->get();

        $newData = [];
        $sama = false;
        $iddokter = '';
        $nmdokter = '';

        foreach ($dataAkhir as $dt) {
            $sama = false;
            $iddokter = '';
            $nmdokter = '';
            foreach ($dataDokterAnestesi as $da) {
                if ($dt['norec_pp'] == $da->norec_pp) {
                    $sama = true;
                    $iddokter = $da->objectpegawaifk;
                    $nmdokter = $da->dokterpj;
                }
            }

            if ((float)$dt['jasapelayanan'] > 0) {
                if ($sama == true) {
                    $newData[] = array(
                        'tglpelayanan' => $dt['tglpelayanan'],
                        'namaproduk' => $dt['namaproduk'],
                        'kelompokpasien' => $dt['kelompokpasien'],
                        'jasapelayanan' => ((float)$dt['jaspelproporsi'] / 100) * 81,
                        'dokterpj' => $dt['dokterpj'],
                        'dokterpjid' => $dt['dokterpjid'],
                        'norec_apd' => $dt['norec_apd'],
                        'jumlah' => $dt['jumlah'],
                        'norec_pp' => $dt['norec_pp'],
                        'produkfk' => $dt['produkfk'],
                        'objectpegawaifk' => $dt['objectpegawaifk'],
                        'objectdepartemenfk' => $dt['objectdepartemenfk'],
                        'objectruanganfk' => $dt['objectruanganfk'],
                        'isparamedis' => true,
                        'jasa' => ((float)$dt['jasa'] / 100) * 81, //$dt['jasa,
                        'tipedokter' => 'Medis',
                        'namaruangan' => $dt['namaruangan'],
                        'hargasatuan' => ((float)$dt['jasapelayanan'] / 100) * 81,
                        'totalklaim' => $dt['totalklaim'] != null ?  (float)$dt['totalklaim'] : null,
                        'totalbilling' => $dt['totalbilling'] != null ?  (float)$dt['totalbilling'] : null,
                        'tipe' => $dt['totalklaim'] != null ? 'nonumum' : 'umum',
                        'kelompokpasienformat' => $dt['kelompokpasienformat'],
                    );
                    $newData[] = array(
                        'tglpelayanan' => $dt['tglpelayanan'],
                        'namaproduk' => $dt['namaproduk'],
                        'kelompokpasien' => $dt['kelompokpasien'],
                        'jasapelayanan' => ((float)$dt['jaspelproporsi'] / 100) * 19,
                        'dokterpj' => $nmdokter,
                        'dokterpjid' => $iddokter,
                        'norec_apd' => $dt['norec_apd'],
                        'jumlah' => $dt['jumlah'],
                        'norec_pp' => $dt['norec_pp'],
                        'produkfk' => $dt['produkfk'],
                        'objectpegawaifk' => $dt['objectpegawaifk'],
                        'objectdepartemenfk' => $dt['objectdepartemenfk'],
                        'objectruanganfk' => $dt['objectruanganfk'],
                        'isparamedis' => $dt['isparamedis'],
                        'jasa' => ((float)$dt['jasa'] / 100) * 19, //$dt['jasa,
                        'tipedokter' => 'Anestesi',
                        'namaruangan' => $dt['namaruangan'],
                        'hargasatuan' => ((float)$dt['jasapelayanan'] / 100) * 19,
                        'totalklaim' => $dt['totalklaim'] != null ?  (float)$dt['totalklaim'] : null,
                        'totalbilling' => $dt['totalbilling'] != null ?  (float)$dt['totalbilling'] : null,
                        'tipe' => $dt['totalklaim'] != null ? 'nonumum' : 'umum',
                        'kelompokpasienformat' => $dt['kelompokpasienformat'],
                    );
                } else {
                    $newData[] = array(
                        'tglpelayanan' => $dt['tglpelayanan'],
                        'namaproduk' => $dt['namaproduk'],
                        'kelompokpasien' => $dt['kelompokpasien'],
                        'jasapelayanan' =>  (float)$dt['jaspelproporsi'],
                        'dokterpj' => $dt['dokterpj'],
                        'dokterpjid' => $dt['dokterpjid'],
                        'norec_apd' => $dt['norec_apd'],
                        'jumlah' => $dt['jumlah'],
                        'norec_pp' => $dt['norec_pp'],
                        'produkfk' => $dt['produkfk'],
                        'objectpegawaifk' => $dt['objectpegawaifk'],
                        'objectdepartemenfk' => $dt['objectdepartemenfk'],
                        'objectruanganfk' => $dt['objectruanganfk'],
                        'isparamedis' => $dt['isparamedis'],
                        'jasa' => $dt['jasa'],
                        'tipedokter' => 'Medis',
                        'namaruangan' => $dt['namaruangan'],
                        'hargasatuan' => (float)$dt['jasapelayanan'],
                        'totalklaim' => $dt['totalklaim'] != null ?  (float)$dt['totalklaim'] : null,
                        'totalbilling' => $dt['totalbilling'] != null ?  (float)$dt['totalbilling'] : null,
                        'tipe' => $dt['totalklaim'] != null ? 'nonumum' : 'umum',
                        'kelompokpasienformat' => $dt['kelompokpasienformat'],
                    );
                }
            }
        }

        $umum = "select x.*,
                    (x.jaspel* $persenDireksi)/100 as direksi,
                    (x.jaspel* $persenStruktural)/100 as struktural,
                    (x.jaspel* $persenAdm)/100  as administrasi,
                    (x.jaspel*  $persenJPL)/100 as jpl,
                    (x.jaspel* $persenJPTL)/100 as jptl,
                    (x.jaspel* $persenGabungan)/100 as gabungan
                from
                (
                    select sum(z.jasapelayanan  * $persenJaspel/100 ) as jaspel ,z.* from (
                            select pd.noregistrasi,pp.tglpelayanan,pr.namaproduk,
                            ((pp.hargajual-(case when pp.hargadiscount is null then 0 else pp.hargadiscount end)+(case when pp.jasa is null then 0 else pp.jasa end))*pp.jumlah)
                            as jasapelayanan, 0 as totalbilling,0 as totalklaim,
                             pgpj.namalengkap as dokter ,pgpj.id as dokterid ,
                            apd.norec as norec_apd,pp.jumlah,pp.norec as norec_pp,pp.produkfk,ppp.objectpegawaifk,ru.objectdepartemenfk,apd.objectruanganfk,pp.isparamedis,
                            (case when pp.jasa is null then 0 else pp.jasa end)*pp.jumlah as jasa,ru.namaruangan,
                            case when kp.kelompokpasien = 'BPJS' then 'JKN' else 'NONJKN' end as kelompokpasienformat
                            from pelayananpasien_t as pp
                            inner join pelayananpasienpetugas_t as ppp on ppp.pelayananpasien=pp.norec
                            left join antrianpasiendiperiksa_t as apd on apd.norec=pp.noregistrasifk
                            inner join pasiendaftar_t as pd on pd.norec=apd.noregistrasifk
                            left join pegawai_m as pgpj on pgpj.id=ppp.objectpegawaifk
                            left join produk_m as pr on pr.id =pp.produkfk
                            left join ruangan_m as ru on ru.id=apd.objectruanganfk
                            inner join strukpelayanan_t as sp on sp.norec=pp.strukfk
                            inner join strukbuktipenerimaan_t as sbm on sp.nosbmlastfk = sbm.norec
                            left join kelompokpasien_m as kp on kp.id=pd.objectkelompokpasienlastfk
                            where CAST(pp.tglpelayanan as DATE) = '$request->tglpelayanan'
                            and ppp.objectjenispetugaspefk = 4 and ppp.statusenabled=true
                            and pr.namaproduk ilike '%$produk%'
                            $kpId
                            $kpNotIn
                            and sbm.statusenabled = true
                            and pd.kdprofile=$kdProfile
                    ) as z
                    group by z.noregistrasi,z.tglpelayanan,z.namaproduk,
                    z.dokter ,z.dokterid,z.norec_apd,z.jumlah,z.norec_pp,
                    z.produkfk,z.objectpegawaifk,z.objectdepartemenfk,z.objectruanganfk,
                    z.isparamedis,z.jasa,z.namaruangan,z.jasapelayanan,z.totalklaim,z.totalbilling,z.kelompokpasienformat
                ) as x ";

        $bpjs = "select x.*,
                    (x.jaspel* $persenDireksi)/100 as direksi,
                    (x.jaspel* $persenStruktural)/100 as struktural,
                    (x.jaspel* $persenAdm)/100  as administrasi,
                    (x.jaspel*  $persenJPL)/100 as jpl,
                    (x.jaspel* $persenJPTL)/100 as jptl,
                    (x.jaspel* $persenGabungan)/100 as gabungan
                from
                (
                 select sum(((z.totalklaim/z.totalbilling)*z.jasapelayanan) * $persenJaspel/100) as jaspel ,z.* from (
                        select distinct pd.noregistrasi,pp.tglpelayanan,pr.namaproduk,
                        ((pp.hargajual-(case when pp.hargadiscount is null then 0 else pp.hargadiscount end)+(case when pp.jasa is null then 0 else pp.jasa end))*pp.jumlah)
                        as jasapelayanan, spp.totalbiaya as totalbilling,
                        case when pd.objectkelompokpasienlastfk <> 5 then (
                        case when bpjs.tarif_inacbg is not null then bpjs.tarif_inacbg else  spp.totalppenjamin  end) ELSE
                        (case when nonbpjs.nominal is not null then nonbpjs.nominal else  spp.totalppenjamin  end) end
                        as totalklaim,
                        pgpj.namalengkap as dokter ,pgpj.id as dokterid ,
                        apd.norec as norec_apd,pp.jumlah,pp.norec as norec_pp,pp.produkfk,ppp.objectpegawaifk,ru.objectdepartemenfk,apd.objectruanganfk,pp.isparamedis,
                        (case when pp.jasa is null then 0 else pp.jasa end)*pp.jumlah as jasa,ru.namaruangan,
                        case when kp.kelompokpasien = 'BPJS' then 'JKN' else 'NONJKN' end as kelompokpasienformat
                        from pelayananpasien_t as pp
                        inner join pelayananpasienpetugas_t as ppp on ppp.pelayananpasien=pp.norec
                        left join antrianpasiendiperiksa_t as apd on apd.norec=pp.noregistrasifk
                        inner join pasiendaftar_t as pd on pd.norec=apd.noregistrasifk
                        left join pegawai_m as pgpj on pgpj.id=ppp.objectpegawaifk
                        left join produk_m as pr on pr.id =pp.produkfk
                        left join ruangan_m as ru on ru.id=apd.objectruanganfk
                        inner join strukpelayanan_t as sp on sp.norec=pp.strukfk
                        inner join strukpelayananpenjamin_t as spp on spp.nostrukfk =sp.norec
                        left join pemakaianasuransi_t as pa on  pa.noregistrasifk =pd.norec
                        left join bpjsklaimtxt_t as bpjs  on bpjs.sep = pa.nosep
                        left join nonbpjsklaimtxt_t as nonbpjs  on nonbpjs.norec_pd = pd.norec
                        left join kelompokpasien_m as kp on kp.id=pd.objectkelompokpasienlastfk
                        where CAST(pp.tglpelayanan as DATE) = '$request->tglpelayanan'
                        and pr.namaproduk ilike '%$produk%'
                        and ppp.objectjenispetugaspefk=4 and ppp.statusenabled=true
                        $kpId
                        $kpIn
                        and pd.kdprofile=$kdProfile
                        and (bpjs.norec is not null or nonbpjs.norec is not null)
                    ) as z
                    group by  z.noregistrasi,z.tglpelayanan,z.namaproduk,
                    z.dokter ,z.dokterid,z.norec_apd,z.jumlah,z.norec_pp,
                    z.produkfk,z.objectpegawaifk,z.objectdepartemenfk,z.objectruanganfk,
                    z.isparamedis,z.jasa,z.namaruangan,z.jasapelayanan,z.totalbilling,z.totalklaim,z.kelompokpasienformat
               ) as x";

        $umumResep  = "select x.*,
                    (x.jaspel* $persenDireksi)/100 as direksi,
                    (x.jaspel* $persenStruktural)/100 as struktural,
                    (x.jaspel* $persenAdm)/100  as administrasi,
                    (x.jaspel*  $persenJPL)/100 as jpl,
                    (x.jaspel* $persenJPTL)/100 as jptl,
                    (x.jaspel* $persenGabungan)/100 as gabungan
                from
                (
                    select sum(((z.jasapelayanan *25/100)  * $persenJaspel/100)) as jaspel ,z.* from (
                            select pd.noregistrasi,pp.tglpelayanan,pr.namaproduk,
                            ((pp.hargajual-(case when pp.hargadiscount is null then 0 else pp.hargadiscount end)+(case when pp.jasa is null then 0 else pp.jasa end))*pp.jumlah)
                            as jasapelayanan, 0 as totalbilling,0 as totalklaim,
                             pgpj.namalengkap as dokter ,pgpj.id as dokterid ,
                            apd.norec as norec_apd,pp.jumlah,pp.norec as norec_pp,pp.produkfk,sr.penulisresepfk as objectpegawaifk,ru.objectdepartemenfk,sr.ruanganfk as objectruanganfk,pp.isparamedis,
                            (case when pp.jasa is null then 0 else pp.jasa end)*pp.jumlah as jasa,ru.namaruangan,
                            case when kp.kelompokpasien = 'BPJS' then 'JKN' else 'NONJKN' end as kelompokpasienformat
                            from pelayananpasien_t as pp
                            inner join strukresep_t as sr on sr.norec=pp.strukresepfk
                            left join antrianpasiendiperiksa_t as apd on apd.norec=pp.noregistrasifk
                            inner join pasiendaftar_t as pd on pd.norec=apd.noregistrasifk
                            left join pegawai_m as pgpj on pgpj.id=sr.penulisresepfk
                            left join produk_m as pr on pr.id =pp.produkfk
                            left join ruangan_m as ru on ru.id=apd.objectruanganfk
                            inner join strukpelayanan_t as sp on sp.norec=pp.strukfk
                            inner join strukbuktipenerimaan_t as sbm on sp.nosbmlastfk = sbm.norec
                            left join kelompokpasien_m as kp on kp.id=pd.objectkelompokpasienlastfk
                            where CAST(pp.tglpelayanan as DATE) = '$request->tglpelayanan' and pgpj.namalengkap ilike '%$nama%'
                            and pr.namaproduk ilike '%$produk%'
                            and sr.statusenabled=true
                            $kpId
                            $kpNotIn
                            and sbm.statusenabled =true
                            and pd.kdprofile=$kdProfile
                    ) as z
                    group by  z.noregistrasi,z.tglpelayanan,z.namaproduk,
                    z.dokter ,z.dokterid,z.norec_apd,z.jumlah,z.norec_pp,
                    z.produkfk,z.objectpegawaifk,z.objectdepartemenfk,z.objectruanganfk,
                    z.isparamedis,z.jasa,z.namaruangan,z.jasapelayanan,z.totalklaim,z.totalbilling,z.kelompokpasienformat
                ) as x";

        $bpjsResep = "select x.*,
                    (x.jaspel* $persenDireksi)/100 as direksi,
                    (x.jaspel* $persenStruktural)/100 as struktural,
                    (x.jaspel* $persenAdm)/100  as administrasi,
                    (x.jaspel*  $persenJPL)/100 as jpl,
                    (x.jaspel* $persenJPTL)/100 as jptl,
                    (x.jaspel* $persenGabungan)/100 as gabungan
                from
                (
                 select sum ((((z.totalklaim/z.totalbilling)*z.jasapelayanan ) *25/100) * $persenJaspel/100) as jaspel ,z.*   from (
                        select distinct pd.noregistrasi,pp.tglpelayanan,pr.namaproduk,
                        ((pp.hargajual-(case when pp.hargadiscount is null then 0 else pp.hargadiscount end)+(case when pp.jasa is null then 0 else pp.jasa end))*pp.jumlah)
                        as jasapelayanan, spp.totalbiaya as totalbilling,
                        case when pd.objectkelompokpasienlastfk <> 5 then (
                        case when bpjs.tarif_inacbg is not null then bpjs.tarif_inacbg else  spp.totalppenjamin  end) ELSE
                        (case when nonbpjs.nominal is not null then nonbpjs.nominal else  spp.totalppenjamin  end) end
                        as totalklaim,
                        pgpj.namalengkap as dokter ,pgpj.id as dokterid ,
                        apd.norec as norec_apd,pp.jumlah,pp.norec as norec_pp,pp.produkfk,sr.penulisresepfk as objectpegawaifk,ru.objectdepartemenfk,sr.ruanganfk as objectruanganfk,pp.isparamedis,
                        (case when pp.jasa is null then 0 else pp.jasa end)*pp.jumlah as jasa,ru.namaruangan,
                        case when kp.kelompokpasien = 'BPJS' then 'JKN' else 'NONJKN' end as kelompokpasienformat
                        from pelayananpasien_t as pp
                        inner join strukresep_t as sr on sr.norec=pp.strukresepfk
                        left join antrianpasiendiperiksa_t as apd on apd.norec=pp.noregistrasifk
                        inner join pasiendaftar_t as pd on pd.norec=apd.noregistrasifk
                        left join pegawai_m as pgpj on pgpj.id=sr.penulisresepfk
                        left join produk_m as pr on pr.id =pp.produkfk
                        left join ruangan_m as ru on ru.id=apd.objectruanganfk
                        inner join strukpelayanan_t as sp on sp.norec=pp.strukfk
                        inner join strukpelayananpenjamin_t as spp on spp.nostrukfk =sp.norec
                        left join pemakaianasuransi_t as pa on  pa.noregistrasifk =pd.norec
                        left join bpjsklaimtxt_t as bpjs  on bpjs.sep = pa.nosep
                        left join nonbpjsklaimtxt_t as nonbpjs  on nonbpjs.norec_pd = pd.norec
                        left join kelompokpasien_m as kp on kp.id=pd.objectkelompokpasienlastfk
                        where CAST(pp.tglpelayanan as DATE) = '$request->tglpelayanan' and pgpj.namalengkap ilike '%$nama%'
                        and pr.namaproduk ilike '%$produk%'
                        and sr.statusenabled=true
                        $kpId
                        $kpIn
                        and pd.kdprofile=$kdProfile
                        and (bpjs.norec is not null or nonbpjs.norec is not null)
                        -- and pd.noregistrasi='2010001366'
                    ) as z
                    group by  z.noregistrasi,z.tglpelayanan,z.namaproduk,
                    z.dokter ,z.dokterid,z.norec_apd,z.jumlah,z.norec_pp,
                    z.produkfk,z.objectpegawaifk,z.objectdepartemenfk,z.objectruanganfk,
                    z.isparamedis,z.jasa,z.namaruangan,z.jasapelayanan,z.totalbilling,z.totalklaim,z.kelompokpasienformat
               ) as x  ";

        $data2 = DB::select(DB::raw(
            $umum . " UNION ALL  " . $bpjs . " UNION ALL " . $umumResep . " UNION ALL " . $bpjsResep
        ));

        $newData2 = [];
        $sama = false;
        $iddokter = '';
        $nmdokter = '';
        $totalG = 0;
        foreach ($data2 as $dt) {
            $sama = false;
            $iddokter = '';
            $nmdokter = '';
            foreach ($dataDokterAnestesi as $da) {
                if ($dt->norec_pp == $da->norec_pp) {
                    $sama = true;
                    $iddokter = $da->objectpegawaifk;
                    $nmdokter = $da->dokterpj;
                }
            }
            if ($sama == true) { //dokter pemeriksa dan dokter anestesi
                if (in_array($dt->objectruanganfk, [87])) { //BEDAH/OK Kebidanan
                    $newData2[] = array(
                        'tglpelayanan' => $dt->tglpelayanan,
                        'namaproduk' => $dt->namaproduk,
                        'rc' => (float)$dt->jptl,
                        'rcdokter' => ((float)$dt->jpl / 100) * 83,
                        'postremun' => (float)$dt->gabungan,
                        'ccdireksi' => (float)$dt->direksi,
                        'ccstaffdireksi' => (float)$dt->struktural,
                        'ccmanajemen' => (float)$dt->administrasi,
                        'dokter' => $dt->dokter,
                        'dokterid' => $dt->dokterid,
                        'norec_apd' => $dt->norec_apd,
                        'jumlah' => $dt->jumlah,
                        'norec_pp' => $dt->norec_pp,
                        'produkfk' => $dt->produkfk,
                        'objectdepartemenfk' => $dt->objectdepartemenfk,
                        'objectruanganfk' => $dt->objectruanganfk,
                        'isparamedis' => true,
                        'tipedokter' => 'Medis',
                        'namaruangan' => $dt->namaruangan,
                        'kelompokpasienformat' => $dt->kelompokpasienformat,
                    );

                    $newData2[] = array(
                        'tglpelayanan' => $dt->tglpelayanan,
                        'namaproduk' => $dt->namaproduk,
                        'rc' => 0,
                        'rcdokter' => ((float)$dt->jpl / 100) * 17,
                        'postremun' => 0,
                        'ccdireksi' => 0,
                        'ccstaffdireksi' => 0,
                        'ccmanajemen' => 0,
                        'dokter' => $nmdokter,
                        'dokterid' => $iddokter,
                        'norec_apd' => $dt->norec_apd,
                        'jumlah' => $dt->jumlah,
                        'norec_pp' => $dt->norec_pp,
                        'produkfk' => $dt->produkfk,
                        'objectdepartemenfk' => $dt->objectdepartemenfk,
                        'objectruanganfk' => $dt->objectruanganfk,
                        'isparamedis' => $dt->isparamedis,
                        'tipedokter' => 'Anestesi',
                        'namaruangan' => $dt->namaruangan,
                        'kelompokpasienformat' => $dt->kelompokpasienformat,
                    );
                } else {
                    $newData2[] = array(
                        'tglpelayanan' => $dt->tglpelayanan,
                        'namaproduk' => $dt->namaproduk,
                        'rc' => (float)$dt->jptl,
                        'rcdokter' => ((float)$dt->jpl / 100) * 81,
                        'postremun' => (float)$dt->gabungan,
                        'ccdireksi' => (float)$dt->direksi,
                        'ccstaffdireksi' => (float)$dt->struktural,
                        'ccmanajemen' => (float)$dt->administrasi,
                        'dokter' => $dt->dokter,
                        'dokterid' => $dt->dokterid,
                        'norec_apd' => $dt->norec_apd,
                        'jumlah' => $dt->jumlah,
                        'norec_pp' => $dt->norec_pp,
                        'produkfk' => $dt->produkfk,
                        'objectdepartemenfk' => $dt->objectdepartemenfk,
                        'objectruanganfk' => $dt->objectruanganfk,
                        'isparamedis' => true,
                        'tipedokter' => 'Medis',
                        'namaruangan' => $dt->namaruangan,
                        'kelompokpasienformat' => $dt->kelompokpasienformat,
                    );
                    $newData2[] = array(
                        'tglpelayanan' => $dt->tglpelayanan,
                        'namaproduk' => $dt->namaproduk,
                        'rc' => 0,
                        'rcdokter' => ((float)$dt->jpl / 100) * 19,
                        'postremun' => 0,
                        'ccdireksi' => 0,
                        'ccstaffdireksi' => 0,
                        'ccmanajemen' => 0,
                        'dokter' => $nmdokter,
                        'dokterid' => $iddokter,
                        'norec_apd' => $dt->norec_apd,
                        'jumlah' => $dt->jumlah,
                        'norec_pp' => $dt->norec_pp,
                        'produkfk' => $dt->produkfk,
                        'objectdepartemenfk' => $dt->objectdepartemenfk,
                        'objectruanganfk' => $dt->objectruanganfk,
                        'isparamedis' => $dt->isparamedis,
                        'tipedokter' => 'Anestesi',
                        'namaruangan' => $dt->namaruangan,
                        'kelompokpasienformat' => $dt->kelompokpasienformat,
                    );
                }
            } else {
                $totalG = $totalG + (float) $dt->struktural + (float)$dt->jptl + (float)$dt->jpl +  (float)$dt->gabungan + (float) $dt->direksi;
                $newData2[] = array(
                    'tglpelayanan' => $dt->tglpelayanan,
                    'namaproduk' => $dt->namaproduk,
                    'rc' => (float)$dt->jptl,
                    'rcdokter' => (float) $dt->jpl,
                    'postremun' => (float)$dt->gabungan,
                    'ccdireksi' => (float) $dt->direksi,
                    'ccstaffdireksi' => (float) $dt->struktural,
                    'ccmanajemen' => (float)$dt->administrasi,
                    'dokter' => $dt->dokter,
                    'dokterid' => $dt->dokterid,
                    'norec_apd' => $dt->norec_apd,
                    'jumlah' => $dt->jumlah,
                    'norec_pp' => $dt->norec_pp,
                    'produkfk' => $dt->produkfk,
                    'objectdepartemenfk' => $dt->objectdepartemenfk,
                    'objectruanganfk' => $dt->objectruanganfk,
                    'isparamedis' => $dt->isparamedis,
                    'tipedokter' => 'Medis',
                    'namaruangan' =>  $dt->namaruangan,
                    'kelompokpasienformat' => $dt->kelompokpasienformat,
                );
            }
        }

        $result = array(
            'data1' => $newData,
            'data2' => $newData2,
            'data3' => $dataDokterAnestesi,
            'strukpagu' => $StrukPagu,
            'isbayar' => $isbyar,
            'message' => 'as@epic',
        );

        return $this->respond($result);
    }

    public function getDaftarRemunPegawai(Request $request)
    {
        $kdProfile = $this->kdProfile;
        $tglAwal = $request['tglAwal'];
        $tglAkhir = $request['tglAkhir'];
        $kelompokPenghasil = $this->settingFix('paguKelompokPenghasil');
        $namapeg = '';
        $row = '';
        if ($request['pegawaifk']) {
            $namapeg = " and pg.id = " . $request['pegawaifk'];
        }
        $ruangan = '';
        if ($request['ruanganfk']) {
            $ruangan = " and ru.id = " . $request['ruanganfk'];
        }
        $noclosing = '';
        if ($request['noclosing']) {
            $noclosing = " and sc.noclosing ilike '%" . $request['noclosing'] . "%'";
        }
        if ($request['jmlRows']) {
            $row = " limit " . $request['jmlRows'];
        }


        $data2 = "
                    select   pg.id as pgid, pg.namalengkap as namakaryawan,sc.noclosing,sc.tglawal,sc.tglakhir,
                    jb.namajabatan as jabatan,gol.name as golongan,to_char(pg.tglmasuk,'yyyy-MM-dd') as skpertamamasukrs,npwp, nip, nomorrekening, namarekening,ru.namaruangan as ruangankerja,
                    sum(dpp.jenispaginilaitotal) as total
                    from detailpegawaipagu_t as dpp
                    INNER JOIN pegawai_m as pg on pg.id=dpp.pegawaiid
                   --left JOIN pendidikan_m as pdd on pdd.id=pg.objectpendidikanterakhirfk
                    left JOIN sdm_golongan_m as gol on gol.id=pg.objectgolonganfk
                    left JOIN jabatan_m as jb on jb.id=pg.objectjabatanfungsionalfk
                    left JOIN ruangan_m as ru on ru.id=pg.objectruangankerjafk
                    --left JOIN petugasdiklat_m as pet on pet.id=pg.objectpetugasdiklatfk
                    INNER JOIN strukclosing_t as sc on sc.norec=dpp.strukclosingfk
                    where CAST(sc.tglclosing as Date) between '$tglAwal' and '$tglAkhir' $namapeg
                    $noclosing
                    and dpp.kdprofile=$kdProfile
                    and sc.statusenabled = true
                   -- and pg.statusenabled = true
                    and dpp.ruanganfk is null
                     --and dpp.djpid <> 142
                    and dpp.statusenabled = true
                    group by pg.id , pg.namalengkap,
                    jb.namajabatan,gol.name,pg.tglmasuk,sc.noclosing,sc.tglawal,sc.tglakhir,npwp, nip, nomorrekening, namarekening,ru.namaruangan


        	";
        $data3 = "
                select   ru.id as pgid, pg.namalengkap as namakaryawan,sc.noclosing,sc.tglawal,sc.tglakhir,
                djp.detailjenispagu as jabatan,'-' as golongan,'-' as skpertamamasukrs,'-' as npwp,'-' as nip, '-' as nomorrekening, '-' as namarekening,ru.namaruangan as ruangankerja,
                sum(dpp.jenispaginilaitotal) as total
                from detailpegawaipagu_t as dpp
                left JOIN pegawai_m as pg on pg.id=dpp.pegawaiid
                left JOIN ruangan_m as ru on ru.id=dpp.ruanganfk
                left JOIN detailjenispagu_t as djp on djp.id=dpp.djpid
                INNER JOIN strukclosing_t as sc on sc.norec=dpp.strukclosingfk
                where CAST(sc.tglclosing as Date) between '$tglAwal' and '$tglAkhir'
                $ruangan
                $noclosing
                and dpp.kdprofile=$kdProfile
                and sc.statusenabled = true
                --and pg.statusenabled = true
                and dpp.statusenabled = true
                and dpp.ruanganfk is not  null and dpp.djpid = $kelompokPenghasil
                group by ru.id , pg.namalengkap,
                djp.detailjenispagu,sc.noclosing,sc.tglawal,sc.tglakhir,npwp, nip, nomorrekening, namarekening

                   ";
        if ($request['iskelompokpenghasil'] == 'true') {
            $datasss = $data3 . "  $row";
        } else {
            $datasss = " ( " . $data2 . " union all " . $data3 . " )  $row";
        }
        $data = DB::select(DB::raw($datasss));

        return $this->respond($data);
    }

    public function getDaftarPerhitunganIndexPegawai(Request $request)
    {

        $data = DB::table('pegawai_m as pg')
            ->leftJoin('pendidikan_m as pdd', 'pdd.id', 'pg.objectpendidikanterakhirfk')
            ->leftJoin('sdm_golongan_m as gol', 'gol.id', 'pg.objectgolonganfk')
            ->leftJoin('jabatan_m as jb', 'jb.id', 'pg.objectjabatanfungsionalfk')
            ->leftJoin('ruangan_m as ru', 'ru.id', 'pg.objectruangankerjafk')
            ->leftJoin('unitkerjapegawai_m as un', 'un.id', 'pg.objectunitkerjafk')
            ->leftJoin('petugasdiklat_m as pet', 'pet.id', 'pg.objectpetugasdiklatfk')
            ->selectRaw("COALESCE(pg.idxbasic,0) + COALESCE(pg.idxcompetency,0) + COALESCE(pg.idxrisk,0)
            + COALESCE(pg.idxemergency,0) + COALESCE(pg.idxposition,0) + COALESCE( pg.idxperformance,0) as totalindex,
            pg.poinjpt as poinjpu,pg.tglmasuk,pg.namalengkap,pg.id as pgid,
            pdd.pendidikan,gol.name as golongan,jb.id, jb.namajabatan,ru.namaruangan,un.name as unitkerja,
            pg.idxbasic ,pg.idxcompetency,pg.idxrisk,pg.idxemergency,pg.idxposition,pg.idxperformance,
            pg.objectpendidikanterakhirfk,pg.objectgolonganfk,pg.objectjabatanfungsionalfk,pg.objectruangankerjafk,
            pg.objectunitkerjafk as  objectunitkerjapegawaifk,pg.objectpetugasdiklatfk,pg.nip,pg.nomorrekening,
            pg.pointcasemix")
            ->where('pg.statusenabled', true)
            ->where('pg.kdprofile', $this->kdProfile);
        if (isset($request['pegawaifk']) && $request['pegawaifk'] != '') {
            $data = $data->where('pg.id', $request['pegawaifk']);
        }
        if (isset($request['unitfk']) && $request['unitfk'] != '') {
            $data = $data->where('un.id', $request['unitfk']);
        }
        // if (isset($request['search'])) {
        //     $data = $data->where('pg.id', $request['search']);
        // }
        // if (isset($request['search'])) {
        //     $searchTerm = '%' . $request['search'] . '%';
        //     $data = $data->where(function ($query) use ($searchTerm) {
        //         $query->where('pg.namalengkap', 'ilike', $searchTerm);
        //         $query->orWhere('pg.nip', 'ilike', $searchTerm);
        //     });
        // }
        $data = $data->orderBy('pg.namalengkap');
        $data = $data->get();

        return $this->respond($data);
    }

    public function updateIndexPegawai(Request $request)
    {
        DB::beginTransaction();
        try {
            Pegawai::where('id', $request['id'])->update([
                'objectpendidikanterakhirfk' => $request['pendidikanfk'],
                'objectgolonganfk' => $request['golonganfk'],
                'objectjabatanfungsionalfk' => $request['jabatanfk'],
                'objectunitkerjafk' => $request['unitfk'],
                'tglmasuk' => $request['tglmasuk'],
                'idxbasic' => $request['basicIndex'],
                'idxcompetency' => $request['competencyIndex'],
                'idxrisk' => $request['riskIndex'],
                'idxemergency' => $request['emergencyIndex'],
                'idxposition' => $request['positionIndex'],
                'idxperformance' => $request['idxperformance'],
                'poinjpt' => $request['pointJpu'],
                'objectruangankerjafk' => $request['subunitkerjafk'],
                'nip' => $request['nip'],
                'nomorrekening' => $request['noRekening'],
                'pointcasemix' => $request['pointcasemix'],
            ]);

            DB::commit();

            $response = [
                'status' => 200,
                'message' => 'Update Index Pegawai Berhasil',
                'result' => null
            ];
        } catch (Exception $ex) {
            DB::rollBack();
            $response = [
                'status' => 400,
                'message' => 'Gagal Update Index Pegawai',
                'result' => $ex->getMessage()
            ];
        }

        return $this->respond($response, $response['status'], $response['message']);
    }

    public function rekapRemunerasiTemp(Request $r)
    {
        $jenis = '';
        if (isset($r['jenis']) && $r['jenis'] != '') {
            $jenis = " and jenis ='$r[jenis]'";
        }
        $nama = '';
        if (isset($r['namadokter']) && $r['namadokter'] != '') {
            $nama = " and namadokter  ilike '%$r[namadokter]%'";
        }
        $data  = collect(DB::select("


         select kelompokstafmedisgrp,namadokter,sum(jkn) as jkn,sum(reguler) as reguler ,
        sum(execu) as execu,sum(jkn)+sum(reguler)+sum(execu) as total
         from (SELECT
        kelompokstafmedisgrp,
        namadokter,
        CASE WHEN jenis = 'JKN' THEN jasa ELSE 0 	END AS jkn,
        CASE WHEN jenis = 'REGULER' THEN 	jasa ELSE 0 	END AS reguler,
        CASE 	WHEN jenis = 'EXECUTIVE' THEN	jasa ELSE 0 	END AS execu
        FROM
            remunerasidokter_t
            where  tanggal between '$r[dari]' and '$r[sampai]'
            and statusenabled=true
            and kdprofile= $this->kdProfile
            and namadokter!=''
            $nama
            $jenis
       ) as x
            GROUP BY kelompokstafmedisgrp,namadokter
            order by kelompokstafmedisgrp



        "));

        $jen  = collect(DB::select("select jenis,sum(jasa) as total from remunerasidokter_t
            where tanggal between '$r[dari]' and '$r[sampai]'
            and statusenabled=true
            and namadokter!=''
            $nama
            $jenis
            group by jenis"));
        $res['jenis'] = $jen;
        $res['data'] = $data;
        return $this->respond($res);
    }

    public function dropdownRemunTemp(Request $r)
    {

        $res['jenis']  = collect(DB::select("select jenis from remunerasidokter_t  group by jenis"));
        return $this->respond($res);
    }


    public function saveRemunerasiJP1(Request $request)
    {

        $kdProfile = $this->kdProfile;
        $dataReq = $request->all();
        DB::beginTransaction();
        try {
            $data2 = $request['data'];
            $newData2 = [];
            foreach ($data2 as $dt) {
                $newData2[] = array(
                    'tglpelayanan' => $dt['tglpelayanan'],
                    'namaproduk' => $dt['namaproduk'],
                    'direksi' => (float) $dt['ccdireksi'],
                    'struktural' => (float) $dt['ccstaffdireksi'],
                    'administrasi' => (float) $dt['ccmanajemen'],
                    'jpl' => (float) $dt['rcdokter'],
                    'jptl' => (float) $dt['rc'],
                    'gabungan' => (float) $dt['postremun'],
                    'dokter' => $dt['dokter'],
                    'dokterid' => $dt['dokterid'],
                    'norec_apd' => $dt['norec_apd'],
                    'jumlah' => $dt['jumlah'],
                    'norec_pp' => $dt['norec_pp'],
                    'produkfk' => $dt['produkfk'],
                    'objectdepartemenfk' => $dt['objectdepartemenfk'],
                    'objectruanganfk' => $dt['objectruanganfk'],
                    'isparamedis' => $dt['isparamedis'],
                    'tipedokter' =>  $dt['tipedokter'],
                );
            }
            $SCSC = StrukPagu::where('periodeawal', $dataReq['head']['periodeawal'])->select('norec')->where('kdprofile', $this->kdProfile)->get();

            if (count($SCSC) > 0) {
                $norecDel = [];
                foreach ($SCSC as $itemAkuh) {
                    $norecDel[] = $itemAkuh->norec;
                }
                // return $this->respond($norecDel);
                $delSCSC = StrukPagu::whereIn('norec', $norecDel)->delete();
                // $delSCSC2 = StrukDetailPagu::whereIn('strukpagufk', $norecDel)->delete();
            }

            $nostrukpagu = $this->generateCodeBySeqTable(new StrukPagu, 'nostrukpagu', 12, 'PGU/' . date('ym'), $this->kdProfile);

            if ($nostrukpagu == '') {
                $transMessage = "Gagal mengumpukan data, Coba lagi.!";
                DB::rollBack();
                $result = array(
                    "status" => 400,
                    "message"  => $transMessage,
                    "as" => 'as@epic',
                );
                return $this->setStatusCode($result['status'])->respond($result, $transMessage);
            }
            $dataSC = new StrukPagu();
            $dataSC->norec = $dataSC->generateNewId();
            $dataSC->kdprofile = $kdProfile;
            $dataSC->statusenabled = true;
            $dataSC->nostrukpagu = $nostrukpagu;
            $dataSC->tglstrukpagu = date('Y-m-d H:i:s');
            $dataSC->periodeawal = $dataReq['head']['periodeawal'];
            $dataSC->periodeakhir = null;
            $dataSC->pegawaiuserid = $this->getPegawaiId();
            $dataSC->totalrcdokter = $dataReq['head']['rcdokter'];
            $dataSC->totalpostrm = $dataReq['head']['postremun'];
            $dataSC->totalrc = $dataReq['head']['rc'];
            $dataSC->totalccdireksi = $dataReq['head']['ccdireksi'];
            $dataSC->totalccstaffdireksi = $dataReq['head']['ccstaffdireksi'];
            $dataSC->totalccmanajemen = $dataReq['head']['ccmanajemen'];
            $dataSC->save();
            $norecSC = $dataSC->norec;
            $dataInsert = [];
            foreach ($newData2 as $item) {
                if ((float)$item['direksi'] > 0) {
                    $dataInsert[] = array(
                        'norec' => Uuid::uuid4(),
                        'kdprofile' => $kdProfile,
                        'statusenabled' => true,
                        'strukpagufk' => $norecSC,
                        'pelayananpasienfk' => $item['norec_pp'],
                        'jenispagufk' => $this->settingFix('JenisPaguDireksi'),
                        'jenispagupersen' => null,
                        'jenispagunilai' => $item['direksi'],
                        'produkfk' => $item['produkfk'],
                        'dokterid' => $item['dokterid'],
                        'tglpelayanan' => $item['tglpelayanan'],
                        'jumlah' => $item['jumlah'],
                        'ruanganfk' => $item['objectruanganfk'],
                        'namaexternal' => $item['tipedokter'],
                        'isanastesi' => $item['isparamedis']
                    );
                }
                if ((float)$item['struktural'] > 0) {
                    $dataInsert[] = array(
                        'norec' => Uuid::uuid4(),
                        'kdprofile' => $kdProfile,
                        'statusenabled' => true,
                        'strukpagufk' => $norecSC,
                        'pelayananpasienfk' => $item['norec_pp'],
                        'jenispagufk' => $this->settingFix('JenisPaguStruktural'),
                        'jenispagupersen' => null,
                        'jenispagunilai' => $item['struktural'],
                        'produkfk' => $item['produkfk'],
                        'dokterid' => $item['dokterid'],
                        'tglpelayanan' => $item['tglpelayanan'],
                        'jumlah' => $item['jumlah'],
                        'ruanganfk' => $item['objectruanganfk'],
                        'namaexternal' => $item['tipedokter'],
                        'isanastesi' => $item['isparamedis']
                    );
                }
                if ((float)$item['administrasi'] > 0) {
                    $dataInsert[] = array(
                        'norec' => Uuid::uuid4(),
                        'kdprofile' => $kdProfile,
                        'statusenabled' => true,
                        'strukpagufk' => $norecSC,
                        'pelayananpasienfk' => $item['norec_pp'],
                        'jenispagufk' => $this->settingFix('JenisPaguCASEMIX'),
                        'jenispagupersen' => null,
                        'jenispagunilai' => $item['administrasi'],
                        'produkfk' => $item['produkfk'],
                        'dokterid' => $item['dokterid'],
                        'tglpelayanan' => $item['tglpelayanan'],
                        'jumlah' => $item['jumlah'],
                        'ruanganfk' => $item['objectruanganfk'],
                        'namaexternal' => $item['tipedokter'],
                        'isanastesi' => $item['isparamedis']
                    );
                }
                if ((float)$item['jpl'] > 0) {
                    $dataInsert[] = array(
                        'norec' => Uuid::uuid4(),
                        'kdprofile' => $kdProfile,
                        'statusenabled' => true,
                        'strukpagufk' => $norecSC,
                        'pelayananpasienfk' => $item['norec_pp'],
                        'jenispagufk' => $this->settingFix('JenisPaguJPL'),
                        'jenispagupersen' => null,
                        'jenispagunilai' => $item['jpl'],
                        'produkfk' => $item['produkfk'],
                        'dokterid' => $item['dokterid'],
                        'tglpelayanan' => $item['tglpelayanan'],
                        'jumlah' => $item['jumlah'],
                        'ruanganfk' => $item['objectruanganfk'],
                        'namaexternal' => $item['tipedokter'],
                        'isanastesi' => $item['isparamedis']
                    );
                }
                if ((float)$item['jptl'] > 0) {
                    $dataInsert[] = array(
                        'norec' => Uuid::uuid4(),
                        'kdprofile' => $kdProfile,
                        'statusenabled' => true,
                        'strukpagufk' => $norecSC,
                        'pelayananpasienfk' => $item['norec_pp'],
                        'jenispagufk' => $this->settingFix('JenisPaguJPTL'),
                        'jenispagupersen' => null,
                        'jenispagunilai' => $item['jptl'],
                        'produkfk' => $item['produkfk'],
                        'dokterid' => $item['dokterid'],
                        'tglpelayanan' => $item['tglpelayanan'],
                        'jumlah' => $item['jumlah'],
                        'ruanganfk' => $item['objectruanganfk'],
                        'namaexternal' => $item['tipedokter'],
                        'isanastesi' => $item['isparamedis']
                    );
                }
                if ((float)$item['gabungan'] > 0) {
                    $dataInsert[] = array(
                        'norec' => Uuid::uuid4(),
                        'kdprofile' => $kdProfile,
                        'statusenabled' => true,
                        'strukpagufk' => $norecSC,
                        'pelayananpasienfk' => $item['norec_pp'],
                        'jenispagufk' =>  $this->settingFix('jenisPaguGabungan'),
                        'jenispagupersen' => null,
                        'jenispagunilai' => $item['gabungan'],
                        'produkfk' => $item['produkfk'],
                        'dokterid' => $item['dokterid'],
                        'tglpelayanan' => $item['tglpelayanan'],
                        'jumlah' => $item['jumlah'],
                        'ruanganfk' => $item['objectruanganfk'],
                        'namaexternal' => $item['tipedokter'],
                        'isanastesi' => $item['isparamedis']
                    );
                }
                if (count($dataInsert) > 100) {
                    StrukDetailPagu::insert($dataInsert);
                    $dataInsert = [];
                }
            }
            StrukDetailPagu::insert($dataInsert);

            $transStatus = true;
        } catch (\Exception $e) {
            $transStatus = false;
        }
        if ($transStatus) {
            $transMessage = "Sukses";
            DB::commit();
            $result = array(
                'status' => 201,
                'norecsc' => $norecSC,
                'as' => 'as@epic',
            );
        } else {
            $transMessage = "Simpan Gagal";
            DB::rollBack();
            $result = array(
                'status' => 400,
                'result' => $e->getMessage(),
                'as' => 'as@epic',
            );
        }
        return $this->respond($result, $result['status'], $transMessage);
    }

    public function getDaftarJP1Rev2(Request $request)
    {
        $kdProfile = $this->kdProfile;
        $tglAwal = $request['tglAwal'];
        $tglAkhir = $request['tglAkhir'];
        $direksi = $this->settingFix('JenisPaguDireksi');
        $struktural = $this->settingFix('JenisPaguStruktural');
        $casemix = $this->settingFix('JenisPaguCASEMIX');
        $jpl = $this->settingFix('JenisPaguJPL');
        $jptl = $this->settingFix('JenisPaguJPTL');
        $gabungan = $this->settingFix('jenisPaguGabungan');
        // if (isset($request['search']) && $request['search'] != '') {
        //     $search  = "and sp.nostrukpagu = $request['search']"; //explode(',',$request['kpId']);
        // }

        $data = DB::select(DB::raw(
            "
            select x.norec,x.nostrukpagu,x.tglstrukpagu,x.periodeawal,x.periodeakhir,
            sum(x.totalrcdokter) as totalrcdokter,sum(x.totalrc) as totalrc,sum(x.totalpostrm) as totalpostrm,
            sum(x.totalccdireksi) as totalccdireksi,sum(x.totalccstaffdireksi) as totalccstaffdireksi,
            sum(x.totalccmanajemen) as totalccmanajemen,x.isbayar
            from (
                select sp.norec,sp.nostrukpagu,sp.tglstrukpagu,sp.periodeawal,sp.periodeakhir,sp.isbayar,
                    case when sdp.jenispagufk  = $direksi then sum(sdp.jenispagunilai) else 0 end as totalrcdokter,
                    case when sdp.jenispagufk  = $struktural then sum(sdp.jenispagunilai) else 0 end as totalrc,
                    case when sdp.jenispagufk  = $casemix then sum(sdp.jenispagunilai) else 0 end as totalpostrm,
                    case when sdp.jenispagufk  = $jpl then sum(sdp.jenispagunilai) else 0 end as totalccdireksi,
                    case when sdp.jenispagufk  = $jptl then sum(sdp.jenispagunilai) else 0 end as totalccstaffdireksi,
                    case when sdp.jenispagufk  = $gabungan then sum(sdp.jenispagunilai) else 0 end as totalccmanajemen
                    from strukdetailpagu_t as sdp
                    INNER JOIN strukpagu_t as sp on sp.norec=sdp.strukpagufk
                    INNER JOIN ruangan_m as ru on ru.id=sdp.ruanganfk
                    -- INNER JOIN jenispagu_t as jp on jp.id=sdp.jenispagufk
                    where sp.periodeawal BETWEEN '$tglAwal' and '$tglAkhir'
                    and sp.nostrukpagu like '%$request->search%'
                    and sdp.kdprofile = $kdProfile
                    group by sp.norec,sp.nostrukpagu,sp.tglstrukpagu,sp.periodeawal,sp.periodeakhir,sdp.jenispagufk,sp.isbayar
            )as x
            group by x.norec,x.nostrukpagu,x.tglstrukpagu,x.periodeawal,x.periodeakhir,x.isbayar
        "
        ));

        $result = array(
            'data' => $data,
            'message' => 'as@epic',
        );
        return $this->respond($result);
    }

    public function updateStatusBayar(Request $request)
    {

        DB::beginTransaction();
        try {
            StrukPagu::where('norec', $request['norec'])->where('kdprofile', $this->kdProfile)->update(['isbayar' => $request['status']]);
            // foreach ($request['data'] as $item) {
            //     StrukPagu::where('norec', $item['norec'])->where('kdprofile', $this->kdProfile)->update([
            //         'isbayar' => $item['status']
            //     ]);
            // }
            DB::commit();
            $result = [
                'status' => 200,
                'message' => 'Verifikasi Bayar Berhasil',
                'result' => $request['data']
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            $result = [
                'status' => 400,
                'message' => 'Simpan Gagal !',
                'result' => $e->getMessage(),
            ];
        }

        return $this->respond($result['result'], $result['status'], $result['message']);
    }

    public function saveClosingDireksi(Request $request)
    {
        //TODO : Closing DIREKSI
        DB::beginTransaction();
        try {
            $rq = $request->all();
            $dateRange = array($rq['periodeawal'], $rq['periodeakhir']);
            $kdProfile = $this->kdProfile;
            //DIREKSI

            $dataDireksi = DB::table('jenispagu_t as jp')
                ->join('strukdetailpagu_t as sdp', 'sdp.jenispagufk', 'jp.id')
                ->join('strukpagu_t as sp', 'sp.norec', 'sdp.strukpagufk')
                ->selectRaw("jp.kelompokpaguid, jp.id as jpid,jp.jenispagu,sum(sdp.jenispagunilai) as totalpagu")
                ->whereBetween(DB::raw("CAST(sp.periodeawal as DATE)"), $dateRange)
                ->where('jp.id', $this->settingFix('JenisPaguDireksi'))
                ->where('jp.statusenabled', true)
                ->where('jp.kdprofile', $kdProfile)
                ->groupBy('jp.kelompokpaguid', 'jp.id', 'jp.jenispagu')
                ->first();

            $dataDireksiPegawai = DB::table('mapjenispagutopegawai_t as mp')
                ->join('pegawai_m as pg', 'pg.id', 'mp.pegawaifk')
                ->join('jenispagu_t as jp', 'jp.id', 'mp.jenispagufk')
                ->join('detailjenispagu_t as djp', 'djp.id', 'mp.detailjenispagufk')
                ->selectRaw("distinct pg.id as idpegawai,pg.namalengkap, jp.jenispagu,pg.poinjpt as totalindex,mp.detailjenispagufk,djp.detailjenispagu,mp.jenispagufk")
                ->where('mp.jenispagufk', $this->settingFix('JenisPaguDireksi'))
                ->where('pg.statusenabled', true)
                ->where('mp.statusenabled', true)
                ->where('mp.kdprofile', $this->kdProfile)
                ->where('pg.kdprofile', $this->kdProfile)
                ->get();


            $totalRemunDir = $dataDireksi->totalpagu;
            $totalPoint = 0;
            foreach ($dataDireksiPegawai as $it) {
                if ($it->totalindex != null) {
                    $totalPoint = $totalPoint + (float)$it->totalindex;
                }
            }
            $ttl = 0;
            $dtRLangsung = [];

            foreach ($dataDireksiPegawai as $itm) {
                if ($itm->totalindex != null) {
                    $jenispaginilaitotal = $totalRemunDir * ((float)$itm->totalindex / (float)$totalPoint);
                    $ttl = $ttl + $jenispaginilaitotal;
                    $dtRLangsung[] = array(
                        'pegawaiid' => (int)$itm->idpegawai,
                        'jenispaginilaitotal' => (float)$jenispaginilaitotal,
                        'kelompokpaguid' => (int)$itm->jenispagufk,
                        'jpid' => $this->settingFix('JenisPaguDireksi'),
                        'tglpelayanan' => null,
                        'norec_sdp' => null,
                        'jenis' => 'DIREKSI',
                        'namaproduk' => $itm->detailjenispagu,
                        'jenispagu' => $itm->jenispagu,
                        'detailjenispagufk' => $itm->detailjenispagufk,
                        'potpersen' => 0,
                    );
                }
            }
            // END DIREKSI
            $dataSave = $dtRLangsung;

            $dataPegawai = DB::table('loginuser_s as lu')
                ->select('lu.objectpegawaifk')
                ->where('lu.id', $rq['userData']['id'])
                ->first();
            $SCSC = StrukClosing::where('tglawal', '>=', $rq['periodeawal'])
                ->where('tglakhir', '<=', $rq['periodeakhir'])
                ->select('norec')
                ->update([
                    'statusenabled' => false,
                ]);

            $SCSCS = StrukClosing::where('tglawal', '>=', $rq['periodeawal'])
                ->where('tglakhir', '<=', $rq['periodeakhir'])
                ->select('norec')
                ->first();

            if (!empty($SCSCS)) {
                DetailPegawaiPagu::where('strukclosingfk', $SCSCS->norec)->update(['statusenabled' => false]);
            }


            $nostrukClosing = $this->generateCode(new StrukClosing(), 'noclosing', 10, 'RC/' . $this->getDateTime()->format('ym'), $kdProfile);

            $dataSC = new StrukClosing();
            $dataSC->norec = $dataSC->generateNewId();
            $dataSC->kdprofile = $kdProfile;
            $dataSC->statusenabled = true;
            $dataSC->noclosing = $nostrukClosing;
            $dataSC->tglclosing = date('Y-m-d H:i:s');
            $dataSC->tglawal = $rq['periodeawal'];
            $dataSC->tglakhir = $rq['periodeakhir'];
            $dataSC->objectpegawaidiclosefk = $dataPegawai->objectpegawaifk;
            $dataSC->objectkelompoktransaksifk = $this->settingFix('ClosingRemunPeg');
            $dataSC->keteranganlainnya = 'Remun Pegawai';
            $dataSC->save();

            $norecSC = $dataSC->norec;

            foreach ($dataSave as $item) {
                $dataSPD = new DetailPegawaiPagu();
                $dataSPD->norec = $dataSPD->generateNewId();
                $dataSPD->kdprofile = $kdProfile;
                $dataSPD->statusenabled = true;
                $dataSPD->strukclosingfk = $norecSC;
                $dataSPD->jenis = $item['jenis'];
                $dataSPD->jenispaginilaitotal = $item['jenispaginilaitotal'];
                $dataSPD->jpid = $item['jpid'];
                $dataSPD->kodeexternal = $item['potpersen'];
                $dataSPD->norec_sdp = $item['norec_sdp'];
                $dataSPD->pegawaiid = $item['pegawaiid'];
                $dataSPD->tglpelayanan = $item['tglpelayanan'];
                $dataSPD->djpid = $item['detailjenispagufk'];
                $dataSPD->save();
            }

            DB::commit();
            $result = array(
                "status" => 201,
                // "data" => count($dataSave),
                "message" => "Closing Pagu DIREKSI Berhasil",
                "norecsc" => $norecSC,
                "total" => $ttl,
                "request" => $rq,
                "by" => 'er@epic',
            );
        } catch (\Exception $e) {
            DB::rollBack();
            $result = array(
                "status" => 201,
                "message" => "Closing Pagu DIREKSI Gagal",
                "data" => $e->getMessage()
            );
        }

        return $this->respond($result, $result['status'], $result['message']);
    }

    public function saveClosingJPL(Request $request)
    {
        $kdProfile = (int) $this->kdProfile;
        DB::beginTransaction();
        try {
            $rq = $request->all();
            $kdProfile = $this->kdProfile;
            $tglAwal = $rq['periodeawal'];
            $tglAkhir = $rq['periodeakhir'];

            $dataRemunLangsung = DB::select(DB::raw(
                "
                     select x.norec as norec_sdp,sum(x.totalkelompok) as totalkelompok,
                        sum(x.totalindividu) as totalindividu,
                        sum(x.totalpengirim) as totalpengirim,
                        sum(x.totalanak) as totalanak,
                        sum(x.totalkepalains) as totalkepalains,
                        sum(x.totalapoteker) as totalapoteker,
                        x.dokterid ,x.ruanganfk,x.dokterorder,
                        --x.persen_kelompok, x.persen_individu ,x.persen_pengirim,
                        x.jenispagunilai,x.tipejasa
                        from (
                        select
                        sdp.isanastesi,
                        (sdp.jenispagunilai*30)/100 as totalkelompok,
                        0 as totalkepalains,
                        0 as totalanak,
                        (sdp.jenispagunilai*70)/100 as totalindividu,
                        0 as totalapoteker,
                        0 as totalpengirim,
                        sdp.pelayananpasienfk,
                        sdp.dokterid as dokterid,sdp.ruanganfk,so.objectpegawaiorderfk as dokterorder,sdp.norec,
                        sdp.jenispagunilai,sdp.namaexternal as tipejasa
                        from strukdetailpagu_t as sdp
                        INNER JOIN strukpagu_t as sp on sp.norec=sdp.strukpagufk
                        INNER JOIN pelayananpasien_t as pp on pp.norec=sdp.pelayananpasienfk
                        INNER JOIN produk_m as prd on pp.produkfk=prd.id
                        left JOIN strukorder_t as so on so.norec=pp.strukorderfk
                        where sp.periodeawal between '$tglAwal' and '$tglAkhir'
                        and sdp.dokterid not in (0) and sdp.jenispagufk=16
                        ) as x
                        group by x.dokterid,x.ruanganfk,x.dokterorder,x.norec,
                        x.tipejasa, x.jenispagunilai
                "
            ));

            $dataApoteker = DB::table('mapjenispagutopegawai_t as mp')
                ->join('pegawai_m as pg', 'pg.id', 'mp.pegawaifk')
                ->select('pg.id as idpegawai', 'pg.namalengkap', 'pg.poinjpt as totalindex', 'mp.detailjenispagufk', 'mp.jenispagufk')
                ->where('mp.detailjenispagufk', $this->settingFix('DetailJenisPaguApoteker'))
                ->where('pg.statusenabled', true)
                ->where('pg.kdprofile', $this->kdProfile)
                ->get();

            $ruangIn = [];
            foreach ($dataRemunLangsung as $iz) {
                if (!in_array($iz->ruanganfk, $ruangIn)) {
                    $ruangIn[] =  $iz->ruanganfk;
                }
            }

            $jadwalRuangan = DB::table('mapremunkelompok_t as map')
                ->join('pegawai_m as pg', 'pg.id', '=', 'map.objectpegawaifk')
                ->select('map.objectruanganfk', 'map.objectpegawaifk')
                ->whereRaw(" ('$tglAwal' between map.tglawal and map.tglakhir or '$tglAkhir' between map.tglawal and map.tglakhir )")
                ->whereIn('map.objectruanganfk', $ruangIn)
                ->where('pg.statusenabled', true)
                ->where('map.statusenabled', true)
                ->get();

            $jadwalRuangan = collect($jadwalRuangan);
            $groupRuangan = $jadwalRuangan->groupBy('objectruanganfk');
            $ruang = [];
            foreach ($groupRuangan as $key => $ru) {
                $ruang[] = $key;
            }
            $dtRLangsung = [];
            $ttl = 0;

            foreach ($dataRemunLangsung as $itmLangsung) {
                if ($itmLangsung->dokterid != null) {
                    if ((float)$itmLangsung->totalindividu != 0) {
                        $ttl = $ttl +  (float)$itmLangsung->totalindividu;
                        $dtRLangsung[] = array(
                            'pegawaiid' => (int)$itmLangsung->dokterid,
                            'jenispaginilaitotal' => (float)$itmLangsung->totalindividu,
                            'jpid' => $this->settingFix('JenisPaguJPL'), //(int)$itmLangsung->jpid,
                            'tglpelayanan' => null, //$itmLangsung->tglpelayanan,
                            'norec_sdp' => $itmLangsung->norec_sdp,
                            'jenis' => 'JPL',
                            'namaproduk' => 'INDIVIDU PENGHASIL',
                            'jenispagu' => 'JPL', //$itmLangsung->jenispagu,
                            'detailjenispagufk' => $this->settingFix('DJPIndividuPenghasil'), //INDIVIDU PENGHASIL (141)
                            'potpersen' => 0,
                        );
                    }
                    if ((float)$itmLangsung->totalanak != 0) {
                        $ttl = $ttl +  (float)$itmLangsung->totalanak;
                        $dtRLangsung[] = array(
                            'pegawaiid' => $itmLangsung->dokterid,
                            'jenispaginilaitotal' => (float)$itmLangsung->totalanak,
                            'jpid' => $this->settingFix('JenisPaguJPL'), //(int)$itmLangsung->jpid,
                            'tglpelayanan' => null, //$itmLangsung->tglpelayanan,
                            'norec_sdp' => $itmLangsung->norec_sdp,
                            'jenis' => 'JPL',
                            'namaproduk' => 'INDIVIDU PENGHASIL (OK anak)',
                            'jenispagu' => 'JPL', //$itmLangsung->jenispagu,
                            'detailjenispagufk' => $this->settingFix('DJPSpesialisAnak'), //INDIVIDU PENGHASIL (202)
                            'potpersen' => 0,
                        );
                    }
                    if ((float)$itmLangsung->totalkepalains != 0) {
                        $ttl = $ttl +  (float)$itmLangsung->totalkepalains;
                        $dtRLangsung[] = array(
                            'pegawaiid' => $itmLangsung->dokterid, //	dr. Noor Priyo Hidayat, Sp.THT -KL
                            'jenispaginilaitotal' => (float)$itmLangsung->totalkepalains,
                            'jpid' => $this->settingFix('JenisPaguJPL'), //(int)$itmLangsung->jpid,
                            'tglpelayanan' => null, //$itmLangsung->tglpelayanan,
                            'norec_sdp' => $itmLangsung->norec_sdp,
                            'jenis' => 'JPL',
                            'namaproduk' => 'INDIVIDU PENGHASIL (Kepala OK)',
                            'jenispagu' => 'JPL', //$itmLangsung->jenispagu,
                            'detailjenispagufk' => $this->settingFix('DJPKepalaInst'), //INDIVIDU PENGHASIL (201)
                            'potpersen' => 0,
                        );
                    }
                    if ((float)$itmLangsung->totalkelompok != 0) {
                        //$ttl  = $ttl +(float)$itmLangsung->totalkelompok;
                        foreach ($groupRuangan as $key => $jd) {
                            if ($itmLangsung->ruanganfk == $key) {
                                $remunPerOrg = (float)$itmLangsung->totalkelompok  / count($jd);
                                foreach ($jd as $det) {
                                    $ttl  = $ttl + $remunPerOrg;
                                    $dtRLangsung[] = array(
                                        'pegawaiid' => $det->objectpegawaifk, //(int)$itmLangsung->dokterid,
                                        'jenispaginilaitotal' => $remunPerOrg, // (float)$itmLangsung->totalkelompok,
                                        'jpid' => $this->settingFix('JenisPaguJPL'), //(int)$itmLangsung->jpid,
                                        'tglpelayanan' => null, //$itmLangsung->tglpelayanan,
                                        'norec_sdp' => $itmLangsung->norec_sdp,
                                        'jenis' => 'JPL',
                                        'namaproduk' => 'KELOMPOK PENGHASIL', //$itmLangsung->namaproduk,
                                        'jenispagu' => 'JPL', //$itmLangsung->jenispagu,
                                        'detailjenispagufk' => $this->settingFix('paguKelompokPenghasil'), //KELOMPOK PENGHASIL
                                        'ruanganfk' => $itmLangsung->ruanganfk,
                                        'potpersen' => 0,
                                    );
                                }
                            }
                        }
                        if (!in_array($itmLangsung->ruanganfk, $ruang)) {
                            $ttl  = $ttl +  (float)$itmLangsung->totalkelompok;
                            $dtRLangsung[] = array(
                                'pegawaiid' => (int)$itmLangsung->dokterid,
                                'jenispaginilaitotal' => (float)$itmLangsung->totalkelompok,
                                'jpid' => $this->settingFix('JenisPaguJPL'), //(int)$itmLangsung->jpid,
                                'tglpelayanan' => null, //$itmLangsung->tglpelayanan,
                                'norec_sdp' => $itmLangsung->norec_sdp,
                                'jenis' => 'JPL',
                                'namaproduk' => 'KELOMPOK PENGHASIL', //$itmLangsung->namaproduk,
                                'jenispagu' => 'JPL', //$itmLangsung->jenispagu,
                                'detailjenispagufk' => $this->settingFix('paguKelompokPenghasil'), //KELOMPOK PENGHASIL
                                'ruanganfk' => $itmLangsung->ruanganfk,
                                'potpersen' => 0,
                            );
                        }
                    }
                    if ((float)$itmLangsung->totalapoteker != 0) {
                        $ttl = $ttl + (float)$itmLangsung->totalapoteker;
                        foreach ($dataApoteker as $apo) {
                            $dtRLangsung[] = array(
                                'pegawaiid' => $apo->idpegawai,
                                'jenispaginilaitotal' => (float)$itmLangsung->totalapoteker / count($dataApoteker),
                                'jpid' => $this->settingFix('JenisPaguJPL'), //(int)$itmLangsung->jpid,
                                'tglpelayanan' => null, //$itmLangsung->tglpelayanan,
                                'norec_sdp' => $itmLangsung->norec_sdp,
                                'jenis' => 'JPL',
                                'namaproduk' => 'APOTEKER', //$itmLangsung->namaproduk,
                                'jenispagu' => 'JPL', //$itmLangsung->jenispagu,
                                'detailjenispagufk' => $this->settingFix('paguKelompokApoteker'), //APOTEER
                                'potpersen' => 0,
                            );
                        }
                    }
                    if ((float)$itmLangsung->totalpengirim != 0) {
                        $ttl = $ttl + (float)$itmLangsung->totalpengirim;
                        $dtRLangsung[] = array(
                            'pegawaiid' => (int)$itmLangsung->dokterorder,
                            'jenispaginilaitotal' => (float)$itmLangsung->totalpengirim,
                            'jpid' => $this->settingFix('JenisPaguJPL'), //(int)$itmLangsung->jpid,
                            'tglpelayanan' => null, //$itmLangsung->tglpelayanan,
                            'norec_sdp' => $itmLangsung->norec_sdp,
                            'jenis' => 'JPL',
                            'namaproduk' => 'PENGIRIM', //$itmLangsung->namaproduk,
                            'jenispagu' => 'JPL', //$itmLangsung->jenispagu,
                            'detailjenispagufk' => $this->settingFix('detailJenispaguPengirim'), //KELOMPOK PENGIRIM
                            'potpersen' => 0,
                        );
                    }
                }
            }

            $dataSave = $dtRLangsung;

            $norecSC = ['norecsc']; //$dataSC->norec;
            // return $dataSave;

            foreach ($dataSave as $item) {
                $dataDPP = new DetailPegawaiPagu();
                $dataDPP->norec = $dataDPP->generateNewId();
                $dataDPP->kdprofile = $this->kdProfile;
                $dataDPP->statusenabled = true;
                $dataDPP->strukclosingfk = $request['norecsc'];
                $dataDPP->jenis = $item['jenis'];
                $dataDPP->jenispaginilaitotal = $item['jenispaginilaitotal'];
                $dataDPP->jpid = $item['jpid'];
                $dataDPP->kodeexternal = $item['potpersen'];
                $dataDPP->norec_sdp = $item['norec_sdp'];
                $dataDPP->pegawaiid = $item['pegawaiid'];
                $dataDPP->tglpelayanan = $item['tglpelayanan'];
                $dataDPP->djpid = $item['detailjenispagufk'];
                $dataDPP->ruanganfk = isset($item['ruanganfk']) ? $item['ruanganfk'] : null;
                $dataDPP->save();
            }
            DB::commit();
            $result = [
                'status' => 201,
                'message' => 'Berhasil Closing Dokter',
                'data' => count($dataSave),
                'total' => $ttl,
                'result => null'
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            $result = [
                'status' => 400,
                'message' => 'Gagal Closing Dokter',
                'result' => $e->getMessage()
            ];
        }

        return $this->respond($result, $result['status'], $result['message']);
    }

    public function saveClosingStruktural(Request $request)
    {
        $kdProfile = (int) $this->kdProfile;

        DB::beginTransaction();
        try {
            $rq = $request->all();
            $tglAwal = $rq['periodeawal'];
            $tglAkhir = $rq['periodeakhir'];

            //DIREKSI
            $dataDireksiTotal = DB::table('jenispagu_t as jp')
                ->join('strukdetailpagu_t as sdp', 'sdp.jenispagufk', 'jp.id')
                ->join('strukpagu_t as sp', 'sp.norec', 'sdp.strukpagufk')
                ->selectRaw("jp.kelompokpaguid, jp.id as jpid,jp.jenispagu,sum(sdp.jenispagunilai) as totalpagu")
                ->whereBetween('sp.periodeawal', [$tglAwal, $tglAkhir])
                ->where('jp.id', $this->settingFix('JenisPaguStruktural'))
                ->where('jp.statusenabled', true)
                ->where('jp.kdprofile', $this->kdProfile)
                ->groupBy('jp.kelompokpaguid', 'jp.id', 'jp.jenispagu')
                ->first();

            $dataDireksiPegawai = DB::table('mapjenispagutopegawai_t as mp')
                ->join('pegawai_m as pg', 'pg.id', 'mp.pegawaifk')
                ->join('jenispagu_t as jp', 'jp.id', 'mp.jenispagufk')
                ->join('detailjenispagu_t as djp', 'djp.id', 'mp.detailjenispagufk')
                ->select(
                    'pg.id as idpegawai',
                    'jp.jenispagu',
                    'pg.namalengkap',
                    'pg.poinjpt as totalindex',
                    'mp.detailjenispagufk',
                    'djp.detailjenispagu',
                    'mp.jenispagufk'
                )
                ->distinct()
                ->where('mp.jenispagufk', $this->settingFix('JenisPaguStruktural'))
                ->where('mp.statusenabled', true)
                ->where('pg.statusenabled', true)
                ->where('mp.kdprofile', $this->kdProfile)
                ->get();

            $totalRemunDir = $dataDireksiTotal->totalpagu;
            $totalPoint = 0;
            foreach ($dataDireksiPegawai as $it) {
                if ($it->totalindex != null) {
                    $totalPoint = $totalPoint + (float)$it->totalindex;
                }
            }
            $ttl = 0;
            $dtRLangsung = [];

            foreach ($dataDireksiPegawai as $itm) {
                if ($it->totalindex != null) {
                    $jenispaginilaitotal =  ((float)$itm->totalindex / (float)$totalPoint) * $totalRemunDir;
                    $ttl = $ttl + $jenispaginilaitotal;
                    $dtRLangsung[] = array(
                        'pegawaiid' => (int)$itm->idpegawai,
                        'jenispaginilaitotal' => (float)$jenispaginilaitotal,
                        'kelompokpaguid' => (int)$itm->jenispagufk,
                        'jpid' => $this->settingFix('JenisPaguStruktural'),
                        'tglpelayanan' => null,
                        'norec_sdp' => null,
                        'jenis' => 'STRUKTURAL',
                        'namaproduk' => $itm->detailjenispagu,
                        'jenispagu' => $itm->jenispagu,
                        'detailjenispagufk' => $itm->detailjenispagufk,
                        'potpersen' => 0,
                    );
                }
            }

            $dataSave = $dtRLangsung;

            $norecSC = $rq['norecsc']; //$dataSC->norec;

            foreach ($dataSave as $item) {
                $dataSPD = new DetailPegawaiPagu();
                $dataSPD->norec = $dataSPD->generateNewId();
                $dataSPD->kdprofile = $kdProfile;
                $dataSPD->statusenabled = true;
                $dataSPD->strukclosingfk = $norecSC;
                $dataSPD->jenis = $item['jenis'];
                $dataSPD->jenispaginilaitotal = $item['jenispaginilaitotal'];
                $dataSPD->jpid = $item['jpid'];
                $dataSPD->kodeexternal = $item['potpersen'];
                $dataSPD->norec_sdp = $item['norec_sdp'];
                $dataSPD->pegawaiid = $item['pegawaiid'];
                $dataSPD->tglpelayanan = $item['tglpelayanan'];
                $dataSPD->djpid = $item['detailjenispagufk'];
                $dataSPD->save();
            }

            DB::commit();
            $result = array(
                "status" => 201,
                "data" => count($dataSave),
                "total" => $ttl,
                "request" => $rq,
                'message' => 'Berhasil Closing Pagu Struktural',
                "by" => 'er@epic',
            );
        } catch (\Exception $e) {
            DB::rollBack();
            $result = array(
                "status" => 400,
                "by" => 'er@epic',
                'result' => $e->getMessage(),
                'message' => 'Gagal Closing Pagu Struktural',
            );
        }

        return $this->respond($result, $result['status'], $result['message']);
    }

    public function saveClosingJPTL(Request $request)
    {

        DB::beginTransaction();
        try {
            $rq = $request->all();
            $tglAwal = $rq['periodeawal'];
            $tglAkhir = $rq['periodeakhir'];

            // Start Admin
            $administrasi = DB::table('strukdetailpagu_t as sdp')
                ->join('strukpagu_t as sp', 'sp.norec', 'sdp.strukpagufk')
                ->join('pelayananpasien_t as pp', 'pp.norec', 'sdp.pelayananpasienfk')
                ->selectRaw("sum(sdp.jenispagunilai ) as totalpagu, 15 as jpid")
                ->where('sdp.jenispagufk', $this->settingFix('JenisPaguCASEMIX'))
                ->whereBetween('sp.periodeawal', [$tglAwal, $tglAkhir])
                ->first();

            $dataPegawaiAdmin = DB::table('mapjenispagutopegawai_t as mp')
                ->join('pegawai_m as pg', 'pg.id', 'mp.pegawaifk')
                ->join('jenispagu_t as jp', 'jp.id', 'mp.jenispagufk')
                ->join('detailjenispagu_t as djp', 'djp.id', 'mp.detailjenispagufk')
                ->selectRaw('distinct pg.id as idpegawai,pg.namalengkap,jp.jenispagu,djp.point,COALESCE(pg.pointcasemix,0) as pointcasemix,
                    pg.poinjpt as totalindex,mp.detailjenispagufk,djp.detailjenispagu,mp.jenispagufk')
                ->where('mp.jenispagufk', $this->settingFix('JenisPaguCASEMIX'))
                ->where('pg.statusenabled', true)
                ->where('mp.kdprofile', $this->kdProfile)
                ->where('mp.statusenabled', true)
                ->get();

            $totalRemunAdmin = $administrasi->totalpagu;
            $totalPointAdmin = 0;
            $remunCasemix = 0;

            foreach ($dataPegawaiAdmin as $tm) {
                $totalPointAdmin = (float) $tm->pointcasemix +  $totalPointAdmin;
            }

            $dtRLangsung = [];
            $ttl = 0;

            foreach ($dataPegawaiAdmin as $itm) {
                if ((float)$itm->pointcasemix > 0) {
                    $jenispaginilaitotal = ((float)$itm->pointcasemix / $totalPointAdmin) *  (float)$totalRemunAdmin;
                    $ttl = $ttl + $jenispaginilaitotal;
                    $dtRLangsung[] = array(
                        'pegawaiid' => (int)$itm->idpegawai,
                        'jenispaginilaitotal' => (float)$jenispaginilaitotal,
                        'kelompokpaguid' => (int)$itm->jenispagufk,
                        'jpid' => $this->settingFix('JenisPaguCASEMIX'), //ADMIN
                        'tglpelayanan' => null,
                        'norec_sdp' => null,
                        'jenis' => 'CASEMIX',
                        'namaproduk' => $itm->detailjenispagu,
                        'jenispagu' => $itm->jenispagu,
                        'detailjenispagufk' => $itm->detailjenispagufk,
                        'potpersen' => 0,
                    );
                }
            }
            // END Admin


            // Start JPTL

            $JPTL = DB::table('jenispagu_t as jp')
                ->join('strukdetailpagu_t as sdp', 'sdp.jenispagufk', 'jp.id')
                ->join('strukpagu_t as sp', 'sp.norec', 'sdp.strukpagufk')
                ->selectRaw("jp.kelompokpaguid, jp.id as jpid,jp.jenispagu,sum(sdp.jenispagunilai) as totalpagu")
                ->whereBetween('sp.periodeawal', [$tglAwal, $tglAkhir])
                ->where('jp.id', $this->settingFix('JenisPaguJPTL'))
                ->groupBy('jp.kelompokpaguid', 'jp.id', 'jp.jenispagu')
                ->first();

            $dataPegawaiJPTL = DB::table('mapjenispagutopegawai_t as mp')
                ->join('pegawai_m as pg', 'pg.id', 'mp.pegawaifk')
                ->join('jenispagu_t as jp', 'jp.id', 'mp.jenispagufk')
                ->join('detailjenispagu_t as djp', 'djp.id', 'mp.detailjenispagufk')
                ->selectRaw("distinct pg.id as idpegawai,pg.namalengkap, jp.jenispagu,mp.detailjenispagufk,djp.detailjenispagu,mp.jenispagufk")
                ->where('mp.statusenabled', true)
                ->where('pg.statusenabled', true)
                ->where('mp.kdprofile', $this->kdProfile)
                ->where('mp.jenispagufk', $this->settingFix('JenisPaguJPTL'))
                ->get();

            $totalPointJPTL = 0;
            $totalRemunJPTL = $JPTL->totalpagu;
            $remunJPTLperorang = 0;
            if (count($dataPegawaiJPTL) > 0) {
                $remunJPTLperorang = (float)$totalRemunJPTL / count($dataPegawaiJPTL);
            }

            $ttlJPTL = 0;
            foreach ($dataPegawaiJPTL as $itm) {
                if ($remunJPTLperorang > 0) {
                    $ttlJPTL = $ttlJPTL + $remunJPTLperorang;
                    $dtRLangsung[] = array(
                        'pegawaiid' => (int)$itm->idpegawai,
                        'jenispaginilaitotal' => (float)$remunJPTLperorang,
                        'kelompokpaguid' => (int)$itm->jenispagufk,
                        'jpid' => $this->settingFix('JenisPaguJPTL'),
                        'tglpelayanan' => null,
                        'norec_sdp' => null,
                        'jenis' => 'JPTL',
                        'namaproduk' => $itm->detailjenispagu,
                        'jenispagu' => $itm->jenispagu,
                        'detailjenispagufk' => $itm->detailjenispagufk,
                        'potpersen' => 0,
                    );
                }
            }
            //END JPTL

            $dataSave = $dtRLangsung;
            $norecSC = $rq['norecsc'];

            foreach ($dataSave as $item) {
                $dataSPD = new DetailPegawaiPagu();
                $dataSPD->norec = $dataSPD->generateNewId();
                $dataSPD->kdprofile = $this->kdProfile;
                $dataSPD->statusenabled = true;
                $dataSPD->strukclosingfk = $norecSC;
                $dataSPD->jenis = $item['jenis'];
                $dataSPD->jenispaginilaitotal = $item['jenispaginilaitotal'];
                $dataSPD->jpid = $item['jpid'];
                $dataSPD->kodeexternal = $item['potpersen'];
                $dataSPD->norec_sdp = $item['norec_sdp'];
                $dataSPD->pegawaiid = $item['pegawaiid'];
                $dataSPD->tglpelayanan = $item['tglpelayanan'];
                $dataSPD->djpid = $item['detailjenispagufk'];
                $dataSPD->save();
            }

            DB::commit();
            $result = array(
                "status" => 201,
                "data" => count($dataSave),
                "request" => $rq,
                "ttl" => $ttl + $ttlJPTL,
                "message" => 'Closing Pagu CASEMIX & JPTL Berhasil',
            );
        } catch (\Exception $e) {
            DB::rollBack();
            $result = array(
                "status" => 400,
                "message" => "Closing Pagu CASEMIX & JPTL Gagal",
                "by" => 'er@epic',
                "result" => $e->getMessage()
            );
        }

        return $this->respond($result, $result['status'], $result['message']);
    }

    public function saveClosingGabungan(Request $request)
    {
        $kdProfile = $this->kdProfile;

        DB::beginTransaction();
        try {
            $rq = $request->all();
            $tglAwal = $rq['periodeawal'];
            $tglAkhir = $rq['periodeakhir'];
            $paguCasemix = $this->settingFix('JenisPaguCASEMIX');
            //JPTL
            $JPTL = DB::table('jenispagu_t as jp')
                ->join('strukdetailpagu_t as sdp', 'sdp.jenispagufk', 'jp.id')
                ->join('strukpagu_t as sp', 'sp.norec', 'sdp.strukpagufk')
                ->selectRaw("jp.kelompokpaguid, jp.id as jpid,jp.jenispagu,sum(sdp.jenispagunilai) as totalpagu")
                ->whereBetween('sp.periodeawal', [$tglAwal, $tglAkhir])
                ->where('jp.id', $this->settingFix('jenisPaguGabungan'))
                ->where('jp.kdprofile', $this->kdProfile)
                ->where('jp.statusenabled', true)
                ->groupBy('jp.kelompokpaguid', 'jp.id', 'jp.jenispagu')
                ->first();

            $dataPegawaiJPTL = DB::select(DB::raw("select  pg.id as idpegawai ,pg.namalengkap,
                pg.idxbasic,pg.idxcompetency,pg.idxrisk,pg.idxemergency,pg.idxposition,pg.idxperformance,
                COALESCE(pg.idxbasic,0) + COALESCE(pg.idxcompetency,0) + COALESCE(pg.idxrisk,0)
                + COALESCE(pg.idxemergency,0) + COALESCE(pg.idxposition,0) + COALESCE( pg.idxperformance,0) as totalindex
                from  pegawai_m as pg
                where pg.statusenabled=true
                and pg.kdprofile=$kdProfile
                and pg.id not in(select pegawaifk from mapjenispagutopegawai_t where jenispagufk=$paguCasemix)"));

            $totalPointJPTL = 0;
            $totalRemunJPTL = $JPTL->totalpagu;

            $dtRLangsung = [];
            foreach ($dataPegawaiJPTL as $tm) {
                if ((float) $tm->totalindex != 0) {
                    $totalPointJPTL = (float) $tm->totalindex +  $totalPointJPTL;
                }
            }
            //            return $this->respond($dataPegawaiJPTL);
            $ttlJPTL = 0;
            $dtRLangsung = [];
            $i = 0;
            foreach ($dataPegawaiJPTL as $itm) {

                if ((float) $itm->totalindex > 0) {
                    $jenispaginilaitotal = ((float)$itm->totalindex / $totalPointJPTL) *  (float)$totalRemunJPTL;
                    $ttlJPTL = $ttlJPTL + $jenispaginilaitotal;
                    $dtRLangsung[] = array(
                        'pegawaiid' => (int)$itm->idpegawai,
                        'jenispaginilaitotal' => (float) $jenispaginilaitotal,
                        'kelompokpaguid' => $this->settingFix('jenisPaguGabungan') , //(int)$itm->jenispagufk(18),
                        'jpid' => $this->settingFix('jenisPaguGabungan'),
                        'pegawai' => $itm->namalengkap,
                        'tglpelayanan' => null,
                        'norec_sdp' => null,
                        'jenis' => 'GABUNGAN',
                        'namaproduk' => 'GABUNGAN', //$itm->detailjenispagu,
                        'jenispagu' => 'GABUNGAN', // $itm->jenispagu,
                        'detailjenispagufk' => $this->settingFix('DJPGabungan') , // $itm->detailjenispagufk(139),
                        'potpersen' => $totalPointJPTL,
                        'point' => (float)$itm->totalindex,
                    );
                }
                $i++;
            }
            //END JPTL

            $dataSave = $dtRLangsung;

            $norecSC = $rq['norecsc']; //$dataSC->norec;

            foreach ($dataSave as $item) {
                $dataSPD = new DetailPegawaiPagu();
                $dataSPD->norec = $dataSPD->generateNewId();
                $dataSPD->kdprofile = $kdProfile;
                $dataSPD->statusenabled = true;
                $dataSPD->strukclosingfk = $norecSC;
                $dataSPD->jenis = $item['jenis'];
                $dataSPD->jenispaginilaitotal = $item['jenispaginilaitotal'];
                $dataSPD->jpid = $item['jpid'];
                $dataSPD->kodeexternal = $item['potpersen'];
                $dataSPD->namaexternal = $item['point'];
                $dataSPD->norec_sdp = $item['norec_sdp'];
                $dataSPD->pegawaiid = $item['pegawaiid'];
                $dataSPD->tglpelayanan = $item['tglpelayanan'];
                $dataSPD->djpid = $item['detailjenispagufk'];
                $dataSPD->save();
            }
            DB::commit();
            $result = array(
                "status" => 201,
                "data" => count($dataSave),
                "ttl" => $ttlJPTL,
                "message" => "Closing Pagu Gabungan Berhasil",
                "request" => $rq,
                "by" => 'er@epic',
            );
        } catch (\Exception $e) {
            DB::rollBack();
            $result = array(
                "status" => 400,
                "message" => "Closing Pagu Gabungan Gagal",
                "by" => 'er@epic',
                "result" => $e->getMessage()
            );
        }

        return $this->respond($result, $result['status'], $result['message']);
    }

    public function saveClosingPotongan(Request $request)
    {

        $kdProfile = (int) $this->kdProfile;

        DB::beginTransaction();
        try {

            $rq = $request->all();
            $tglAwal = $rq['periodeawal'];
            $tglAkhir = $rq['periodeakhir'];
            $norecSC = $rq['norecsc'];
            //JPTL
            $potongan = DB::select(DB::raw(
                "
                select * ,case when x.potfix > 0 and   x.jmlremun > x.potfix
                then x.jmlremun - x.potfix else (
                case when x.potpersen > 0 and  x.jmlremun >(x.potpersen /100 *x.jmlremun )
                then x.jmlremun - (x.potpersen /100 *x.jmlremun ) else 0 end
                ) end potongan
                from (SELECT
                    jp.jenispagu,
                    pg.namalengkap,
                    pot.objectpegawaifk,
                    jp.id AS jpid,
                    djp.detailjenispagu,
                pg.objectkelompokjabatanfk,
                (select count(nn.id) as jml
                        from pegawai_m as pg2
                        join nilaikelompokjabatan_m as nn on nn.id=pg2.objectkelompokjabatanfk
                        where pg2.statusenabled = true
                        and nn.statusenabled = true
                        and nn.detailkelompokjabatan <> '-'
                and nn.id =pg.objectkelompokjabatanfk) as jmlorang,
                (select sum(dpp.jenispaginilaitotal) as jmlremun
                from strukclosing_t as sc
                INNER JOIN detailpegawaipagu_t as dpp on dpp.strukclosingfk=sc.norec
                where sc.norec= '$norecSC'
                and dpp.pegawaiid=pot.objectpegawaifk
                and dpp.statusenabled= true)  as jmlremun,
                (case when
                (case when pot.remunfixed is null then 0 else pot.remunfixed end )
                > 0 then pot.remunfixed else 0 end) as  potfix,
                (case when (case when pot.potpersen is null then 0 else pot.potpersen end )
                > 0 then pot.potpersen else 0 end) as  potpersen

                FROM
                    potonganremun_t AS pot
                LEFT JOIN jenispagu_t AS jp ON jp.id = pot.objectjenispagufk
                LEFT JOIN detailjenispagu_t AS djp ON djp.id = pot.objectdetailjenispagufk
                INNER JOIN pegawai_m AS pg ON pg.id = pot.objectpegawaifk
                WHERE
                    pg.statusenabled = true)
                as x"
            ));

            $dtRLangsung = [];
            if (count($potongan) > 0) {
                foreach ($potongan as $p) {
                    $idPeg = $p->objectpegawaifk;
                    $idKelJabatan = $p->objectkelompokjabatanfk;
                    if ((float)$p->potongan > 0) {
                        $dataUp =  DB::select(DB::raw("
                            select dpp.*
                            from strukclosing_t as sc
                            INNER JOIN detailpegawaipagu_t as dpp on dpp.strukclosingfk=sc.norec
                            where sc.norec= '$norecSC'
                            and dpp.pegawaiid=$idPeg
                            and dpp.statusenabled= true"));
                        foreach ($dataUp as $d) {
                            $update = DB::table('detailpegawaipagu_t')
                                ->where('norec', $d->norec)
                                ->update([
                                    'jenispaginilaitotal' =>  (float)$d->jenispaginilaitotal  -
                                        (((float)$d->jenispaginilaitotal / (float)$p->jmlremun) * $p->potfix)
                                ]);
                        }

                        $orang2Na = DB::select(DB::raw("
                            select pg.id as idpegawai, pg.namalengkap,nn.detailkelompokjabatan,pg.objectkelompokjabatanfk
                            from pegawai_m as pg
                            join nilaikelompokjabatan_m as nn on nn.id=pg.objectkelompokjabatanfk
                            where pg.statusenabled = true
                            and nn.statusenabled = true
                            and nn.detailkelompokjabatan <> '-'
                            and nn.id = $idKelJabatan
                        "));

                        $dtRLangsung[] = array(
                            'pegawaiid' => (int)$idPeg,
                            'jenispaginilaitotal' => -(float) $p->potfix,
                            'kelompokpaguid' => 20, //Potongan
                            'jpid' => $this->settingFix('jenisPaguPOTONGAN'),
                            'tglpelayanan' => null,
                            'norec_sdp' => null,
                            'jenis' => 'POTONGAN',
                            'namaproduk' => 'POTONGAN',
                            'jenispagu' => 'POTONGAN',
                            'detailjenispagufk' => $this->settingFix('DetailJenisPaguPotongan'), //Potongan
                            'potpersen' => (float)$p->potfix,
                            'keteranganpot' => '',
                        );
                        foreach ($orang2Na as $o) {
                            $jenispaginilaitotal = ((float) $p->potfix / (float)$p->jmlorang);
                            $dtRLangsung[] = array(
                                'pegawaiid' => (int)$o->idpegawai,
                                'jenispaginilaitotal' => (float) $jenispaginilaitotal,
                                'kelompokpaguid' => 19, //Potongan
                                'jpid' => $this->settingFix('jenisPaguADMINISTRASI'),
                                'tglpelayanan' => null,
                                'norec_sdp' => null,
                                'jenis' => 'PENERIMAAN POTONGAN',
                                'namaproduk' => 'PENERIMAAN POTONGAN',
                                'jenispagu' => 'PENERIMAAN POTONGAN',
                                'detailjenispagufk' => $this->settingFix('DPJPPenerimaanPotongan'), //Potongan(149)
                                'potpersen' => (float)$p->potfix,
                                'keteranganpot' => 'Penerimaan dari ' . $idPeg . ' ' . $p->namalengkap . ' pot : ' . (float)$p->potfix .
                                    ' ,' . $p->jmlorang . ' org , terima : ' . $jenispaginilaitotal,
                            );
                        }
                    }
                }
            }

            $dataSave = $dtRLangsung;
            $norecSC = $rq['norecsc']; //$dataSC->norec;
            $ttlJPTL = 0;
            foreach ($dataSave as $item) {
                $ttlJPTL = $ttlJPTL + $item['jenispaginilaitotal'];
                $dataSPD = new DetailPegawaiPagu();
                $dataSPD->norec = $dataSPD->generateNewId();
                $dataSPD->kdprofile = 0;
                $dataSPD->statusenabled = true;
                $dataSPD->strukclosingfk = $norecSC;
                $dataSPD->jenis = $item['jenis'];
                $dataSPD->jenispaginilaitotal = $item['jenispaginilaitotal'];
                $dataSPD->jpid = $item['jpid'];
                $dataSPD->kodeexternal = $item['potpersen'];
                $dataSPD->namaexternal = $item['keteranganpot'];
                $dataSPD->norec_sdp = $item['norec_sdp'];
                $dataSPD->pegawaiid = $item['pegawaiid'];
                $dataSPD->tglpelayanan = $item['tglpelayanan'];
                $dataSPD->djpid = $item['detailjenispagufk'];
                $dataSPD->save();
            }
            DB::commit();
            $result = array(
                "status" => 201,
                "message" => "Closing POTONGAN REMUN",
                "data" => count($dataSave),
                "ttl" => $ttlJPTL,
                "request" => $rq,
                "by" => 'er@epic',
            );
        } catch (\Exception $e) {
            DB::rollBack();
            $result = array(
                "status" => 400,
                "message" => "Closing POTONGAN Gagal",
                "by" => 'er@epic',
                "data" => $e->getMessage()
            );
        }
        return $this->respond($result, $result['status'],$result['message']);

    }

    public function getPegawaiByJenisPagu(Request $request)
    {
        $data = DB::table('mapjenispagutopegawai_t as mp')
            ->join('jenispagu_t as jp', 'jp.id', 'mp.jenispagufk')
            ->join('remundetailpegawai_t as pg', 'pg.idpegawai', 'mp.pegawaifk')
            ->select('mp.norec', 'pg.idpegawai as pgid', 'pg.namakaryawan as namalengkap', 'jp.id as jpid', 'jp.jenispagu')
            ->where('jp.id', $request['jpid'])
            ->where('jp.kdprofile', $this->kdProfile)
            ->where('jp.statusenabled', true)
            ->where('mp.statusenabled', true)
            ->where('mp.kdprofile', $this->kdProfile)
            ->get();

        $dataDetailJenisPagu = DB::table('detailjenispagu_t')->select('id', 'detailjenispagu', 'objectruanganfkarr', 'jumlahorg')
            ->where('jenispaguid', $request['jpid'])
            ->where('kdprofile', $this->kdProfile)
            ->where('statusenabled', true)
            ->orderBy('id')
            ->get();

        $dataRuangan = Ruangan::mine()->get();

        $arrRuanganfk = [];
        $namaruangan = '';
        $dataHasil = [];
        foreach ($dataDetailJenisPagu as $item) {
            $arrRuanganfk = [];
            $arrRuanganfk = explode(",", $item->objectruanganfkarr);
            $namaruangan = '';
            foreach ($arrRuanganfk as $tm) {
                foreach ($dataRuangan as $itm) {
                    if ($tm ==  $itm->id) {
                        $namaruangan = $itm->namaruangan . ', ' . $namaruangan;
                        break;
                    }
                }
            }
            $dataHasil[] = array(
                'id' => $item->id,
                'detailjenispagu' => $namaruangan . ' - ' . $item->detailjenispagu . ' - ' . $item->jumlahorg,
            );
        }
        $result = array(
            'data' => $data,
            'detailjenispagu' => $dataHasil,
            'message' => 'as@epic',
        );

        return $this->respond($result);
    }

    public function getDataDetailLaporanRemunerasi(Request $request)
    {
        $tglAwal = $request['tglAwal'];
        $tglAkhir = $request['tglAkhir'];
        $direksi = $this->settingFix('JenisPaguDireksi');
        $struktural = $this->settingFix('JenisPaguStruktural');
        $administrasi = $this->settingFix('JenisPaguCASEMIX');
        $jpl = $this->settingFix('JenisPaguJPL');
        $jptl = $this->settingFix('JenisPaguJPTL');
        $gabungan = $this->settingFix('jenisPaguGabungan');
        $kdProfile = $this->kdProfile;
        $deptId = $request['departemenfk'];
        $ruanganId = $request['ruanganfk'];
        $paramDep = ' ';
        if (isset($deptId) && $deptId != "" && $deptId != "undefined") {
            $paramDep = ' and ru.objectdepartemenfk = ' . $deptId;
        }

        $paramRuangan = ' ';
        if (isset($ruanganId) && $ruanganId != "" && $ruanganId != "undefined") {
            $paramRuangan = ' and ru.id = ' . $ruanganId;
        }

        $data = DB::select(DB::raw("select x.namaruangan, sum(x.direksi) as direksi,sum(x.struktural) as struktural,sum(x.administrasi) as administrasi,
                        sum(x.jpl) as jpl,sum(x.jptl) as jptl,sum(x.gabungan) as gabungan from
                        (select ru.namaruangan,
                        case when sdp.jenispagufk = ? then sum(sdp.jenispagunilai) else 0 end as direksi,
                        case when sdp.jenispagufk = ? then sum(sdp.jenispagunilai) else 0 end as struktural,
                        case when sdp.jenispagufk = ? then sum(sdp.jenispagunilai) else 0 end as administrasi,
                        case when sdp.jenispagufk = ? then sum(sdp.jenispagunilai) else 0 end as jpl,
                        case when sdp.jenispagufk = ? then sum(sdp.jenispagunilai) else 0 end as jptl,
                        case when sdp.jenispagufk = ? then sum(sdp.jenispagunilai) else 0 end as gabungan
                            from strukdetailpagu_t as sdp
                            INNER JOIN ruangan_m as ru on ru.id=sdp.ruanganfk
                            inner join strukpagu_t as sp on sp.norec =sdp.strukpagufk
                            where CAST(sp.periodeawal as DATE) BETWEEN ? and ?
                            and sdp.kdprofile= ?
                            $paramDep
                            $paramRuangan
                            group by ru.namaruangan,sdp.jenispagufk )as x
                            group by x.namaruangan"), [$direksi, $struktural, $administrasi, $jpl, $jptl, $gabungan, $tglAwal, $tglAkhir, $kdProfile,]);

        $result = array(
            'data' => $data,
            'message' => 'ea@epic',
        );
        return $this->respond($result);
    }

    public function getDataRekapLaporanRemunerasi(Request $request)
    {
        $data = [];
        $tglAwal = $request->tglAwal;
        $tglAkhir = $request->tglAkhir;
        $direksi = $this->settingFix('JenisPaguDireksi');
        $struktural = $this->settingFix('JenisPaguStruktural');
        $administrasi = $this->settingFix('JenisPaguCASEMIX');
        $jpl = $this->settingFix('JenisPaguJPL');
        $jptl = $this->settingFix('JenisPaguJPTL');
        $gabungan = $this->settingFix('jenisPaguGabungan');
        $kdProfile = $this->kdProfile;

        $paramDokter = ' ';
        if (isset($request['pegawaifk']) && $request['pegawaifk'] != "" && $request['pegawaifk'] != "undefined") {
            $paramDokter = ' and pg.id = ' . $request['pegawaifk'];
        }

        $data = DB::select(DB::raw("select x.namalengkap, sum(x.direksi) as direksi,sum(x.struktural) as struktural,sum(x.administrasi) as administrasi,
                        sum(x.jpl) as jpl,sum(x.jptl) as jptl,sum(x.gabungan) as gabungan from
                        (select pg.namalengkap,
                        case when sdp.jenispagufk = ? then sum(sdp.jenispagunilai) else 0 end as direksi,
                        case when sdp.jenispagufk = ? then sum(sdp.jenispagunilai) else 0 end as struktural,
                        case when sdp.jenispagufk = ? then sum(sdp.jenispagunilai) else 0 end as administrasi,
                        case when sdp.jenispagufk = ? then sum(sdp.jenispagunilai) else 0 end as jpl,
                        case when sdp.jenispagufk = ? then sum(sdp.jenispagunilai) else 0 end as jptl,
                        case when sdp.jenispagufk = ? then sum(sdp.jenispagunilai) else 0 end as gabungan
                        from strukdetailpagu_t as sdp
                        INNER JOIN ruangan_m as ru on ru.id=sdp.ruanganfk
                        inner join strukpagu_t as sp on sp.norec =sdp.strukpagufk
                        INNER JOIN pegawai_m as pg on pg.id=sdp.dokterid
                        where CAST(sp.periodeawal as DATE) BETWEEN ? and ?
                        and sdp.kdprofile = ?
                        $paramDokter
                        group by pg.namalengkap,sdp.jenispagufk)as x
                        group by x.namalengkap;"), [$direksi, $struktural, $administrasi, $jpl, $jptl, $gabungan, $tglAwal, $tglAkhir, $kdProfile,]);
        $result = array(
            'data' => $data,
            'message' => 'ea@epic',
        );
        return $this->respond($result);
    }

    public function getDataDetailLaporanRemunerasiDokter(Request $request)
    {
        $kdProfile = $this->kdProfile;
        $data = [];
        $data2 = [];
        $tglAwal = $request['tglAwal'];
        $tglAkhir = $request['tglAkhir'];
        $dokterid = $request['dokterfk'];
        $isEksekutif = $request['isExsekutif'];
        $deptId = $request['departemenfk'];
        $ruanganId = $request['ruanganfk'];

        $paramDep = ' ';
        if (isset($deptId) && $deptId != "" && $deptId != "undefined") {
            $paramDep = ' and ru.objectdepartemenfk = ' . $deptId;
        }

        $paramRuangan = ' ';
        if (isset($ruanganId) && $ruanganId != "" && $ruanganId != "undefined") {
            $paramRuangan = ' and ru.id = ' . $ruanganId;
        }

        $paramDokter = ' ';
        if (isset($dokterid) && $dokterid != "" && $dokterid != "undefined") {
            $paramDokter = ' and sdp.dokterid = ' . $dokterid;
        }

        $data = DB::select(DB::raw("select
                     x.tglpelayanan,x.nocm,x.noregistrasi,x.namapasien,x.namaruangan,
                     x.namaproduk,x.isparamedis,x.iscito,x.hargasatuan,
                     --sum
                     (x.jumlah) as qty,sum(x.jenispagunilai) as total
                from
                (select pp.tglpelayanan,ps.nocm,pd.noregistrasi,ps.namapasien,ru.namaruangan,
                        pr.namaproduk,pp.jumlah,pp.isparamedis,pp.iscito,pp.hargasatuan,
                        sdp.jenispagunilai
                from strukdetailpagu_t as sdp
                INNER JOIN pelayananpasien_t as pp on pp.norec=sdp.pelayananpasienfk
                INNER JOIN antrianpasiendiperiksa_t as apd on apd.norec=pp.noregistrasifk
                INNER JOIN pasiendaftar_t as pd on pd.norec=apd.noregistrasifk
                INNER JOIN produk_m as pr on sdp.produkfk=pr.id
                INNER JOIN pasien_m as ps on ps.id=pd.nocmfk
                INNER JOIN ruangan_m as ru on ru.id=sdp.ruanganfk
                where CAST(pd.tglpulang as DATE) between '$tglAwal' and '$tglAkhir'
                and sdp.kdprofile=  $kdProfile
                $paramDep
                $paramRuangan
                $paramDokter) as x
                group by  x.tglpelayanan,x.nocm,x.noregistrasi,x.namapasien,x.namaruangan,
 				          x.namaproduk,x.isparamedis,x.iscito,x.hargasatuan,
 				          x.jumlah
                "));
        $result = array(
            'data' => $data,
            'message' => 'ea@epic',
        );
        return $this->respond($result);
    }
    public function getDataDetailLaporanRemunerasiParamedis(Request $request)
    {
        $kdProfile = $this->kdProfile;
        $data = [];
        $jptl = $this->settingFix('JenisPaguJPTL');
        $tglAwal = $request['tglAwal'];
        $tglAkhir = $request['tglAkhir'];
        $dokterid = $request['dokterid'];
        $isEksekutif = $request['isExsekutif'];
        $deptId = $request['departemenfk'];
        $ruanganId = $request['ruanganfk'];

        $paramDep = ' ';
        if (isset($deptId) && $deptId != "" && $deptId != "undefined") {
            $paramDep = ' and ru.objectdepartemenfk = ' . $deptId;
        }

        $paramRuangan = ' ';
        if (isset($ruanganId) && $ruanganId != "" && $ruanganId != "undefined") {
            $paramRuangan = ' and ru.id = ' . $ruanganId;
        }

        $paramDokter = ' ';
        if (isset($dokterid) && $dokterid != "" && $dokterid != "undefined") {
            $paramDokter = ' and sdp.dokterid = ' . $dokterid;
        }

        $paramEksekutif = ' ';
        if (isset($isEksekutif) && $isEksekutif != "" && $isEksekutif != "undefined") {
            if ($isEksekutif == "true") {
                $paramEksekutif = ' and ru.iseksekutif = 1';
            } else if ($isEksekutif == "false") {
                $paramEksekutif = ' and ru.iseksekutif = 0';
            }
        }

        $data = DB::select(DB::raw("select
                     x.tglpelayanan,x.nocm,x.noregistrasi,x.namapasien,x.namaruangan,
                     x.namaproduk,x.isparamedis,x.iscito,x.hargasatuan,
                     sum(x.jumlah) as qty,sum(x.jenispagunilai) as total
                from
                (select pp.tglpelayanan,ps.nocm,pd.noregistrasi,ps.namapasien,ru.namaruangan,
                        pr.namaproduk,pp.jumlah,pp.isparamedis,pp.iscito,pp.hargasatuan,
                        sdp.jenispagunilai
                from strukdetailpagu_t as sdp
                INNER JOIN pelayananpasien_t as pp on pp.norec=sdp.pelayananpasienfk
                INNER JOIN antrianpasiendiperiksa_t as apd on apd.norec=pp.noregistrasifk
                INNER JOIN pasiendaftar_t as pd on pd.norec=apd.noregistrasifk
                INNER JOIN produk_m as pr on sdp.produkfk=pr.id
                INNER JOIN pasien_m as ps on ps.id=pd.nocmfk
                INNER JOIN ruangan_m as ru on ru.id=sdp.ruanganfk
                where CAST(pd.tglpulang as DATE) between '$tglAwal' and '$tglAkhir'
                and sdp.jenispagufk = $jptl
                and sdp.kdprofile=  $kdProfile
                $paramDep
                $paramRuangan
                $paramDokter
                $paramEksekutif) as x
                group by  x.tglpelayanan,x.nocm,x.noregistrasi,x.namapasien,x.namaruangan,
 				x.namaproduk,x.isparamedis,x.iscito,x.hargasatuan
                "));
        $result = array(
            'data' => $data,
            'message' => 'ea@epic',
        );
        return $this->respond($result);
    }

    public function getDaftarRemunKelompok(Request $request)
    {
        $rangeDate = [$request->tglAwal, $request->tglAkhir];

        $data = DB::table('detailpegawaipagu_t as dpp')
            ->join('pegawai_m as pg', 'pg.id', 'dpp.pegawaiid')
            ->leftjoin('ruangan_m as ru', 'ru.id', 'dpp.ruanganfk')
            ->leftjoin('detailjenispagu_t as djp', 'djp.id', 'dpp.djpid')
            ->join('strukclosing_t as sc', 'sc.norec', 'dpp.strukclosingfk')
            ->selectRaw("ru.id as ruid, ru.namaruangan ,sc.noclosing,sc.tglawal,sc.tglakhir,
                djp.detailjenispagu as jabatan,'-' as golongan,'-' as skpertamamasukrs,dpp.djpid,dpp.jpid,
                dpp.strukclosingfk,
                sum(dpp.jenispaginilaitotal) as total")
            ->whereBetween(DB::raw("CAST(sc.tglclosing as Date)"), $rangeDate)
            ->where('dpp.kdprofile', $this->kdProfile)
            ->where('dpp.statusenabled', true)
            ->whereNotNull('dpp.ruanganfk')
            ->where('dpp.djpid', $this->settingFix('paguKelompokPenghasil'))
            ->where('sc.statusenabled', true);
        if ($request['noclosing']) {
            $data = $data->where('sc.noclosing', 'ilike', '%' . $request['noclosing'] . '%');
        }
        if ($request['ruanganfk']) {
            $data = $data->where('ru.id', $request['ruanganfk']);
        }
        if ($request['pegawaifk']) {
            $data = $data->where('pg.id', $request['pegawaifk']);
        }

        $data = $data->groupBy('ru.id', 'ru.namaruangan', 'djp.detailjenispagu', 'sc.noclosing', 'sc.tglawal', 'sc.tglakhir', 'dpp.djpid', 'dpp.jpid', 'dpp.strukclosingfk');
        $data = $data->get();

        return $this->respond($data);
    }

    public function getRincianPendapatan(Request $request)
    {

        $kdprofile = $this->kdProfile;

        $data = DB::select(DB::raw(
            "
               select sum(z.jasapelayanan) as total,z.bulan,z.ket  from  (
                        select pd.noregistrasi,pp.tglpelayanan,to_char(pd.tglpulang,'MM') as bulan,
                        pd.objectkelompokpasienlastfk,case when pd.objectrekananfk =581136 then 'jasaraharja'
                        when pd.objectkelompokpasienlastfk =1 then 'umum'
                        when pd.objectkelompokpasienlastfk =11 then 'covid'
                        else 'lainlain' end as ket,
                        ((pp.hargajual-
                        (case when pp.hargadiscount is null then 0 else pp.hargadiscount end)+
                        (case when pp.jasa is null then 0 else pp.jasa end))*pp.jumlah)
                        as jasapelayanan,
                        null as totalbilling,null as totalklaim,
                        apd.norec as norec_apd,pp.jumlah,pp.norec as norec_pp
                        from pelayananpasien_t as pp
                        inner join pelayananpasienpetugas_t as ppp on ppp.pelayananpasien=pp.norec
                        left join antrianpasiendiperiksa_t as apd on apd.norec=pp.noregistrasifk
                        inner join pasiendaftar_t as pd on pd.norec=apd.noregistrasifk
                        inner join strukpelayanan_t as sp on sp.norec=pp.strukfk
                        inner join strukbuktipenerimaan_t as sbm on sp.nosbmlastfk = sbm.norec
                        where to_char(pd.tglpulang,'yyyy') ='$request[tahun]'
                        and pd.objectkelompokpasienlastfk not in (2,4,5,10)
                        and sbm.statusenabled =true
                        and pd.kdprofile= $kdprofile
                        ) as z
                        group by z.bulan,z.ket

                        union all

                        select
                                sum ((x.totalklaim/x.totalbilling)*x.jasapelayanan)  as total,
                        x.bulan, 'bpjs' as ket
                        from (
                                select distinct pd.objectkelompokpasienlastfk,pd.noregistrasi,pp.tglpelayanan,to_char(pd.tglpulang,'MM') as bulan,
                        ((pp.hargajual-(case when pp.hargadiscount is null then 0 else pp.hargadiscount end)+(case when pp.jasa is null then 0 else pp.jasa end))*pp.jumlah)
                             as jasapelayanan, spp.totalbiaya as totalbilling,
                        case when pd.objectkelompokpasienlastfk <> 5 then (
                        case when bpjs.tarif_inacbg is not null then bpjs.tarif_inacbg else  spp.totalppenjamin  end) ELSE
                        (case when nonbpjs.nominal is not null then nonbpjs.nominal else  spp.totalppenjamin  end) end
                            as totalklaim,
                        apd.norec as norec_apd,pp.jumlah,pp.norec as norec_pp,pp.produkfk
                        from pelayananpasien_t as pp
                        -- inner join pelayananpasienpetugas_t as ppp on ppp.pelayananpasien=pp.norec
                        left join antrianpasiendiperiksa_t as apd on apd.norec=pp.noregistrasifk
                        inner join pasiendaftar_t as pd on pd.norec=apd.noregistrasifk
                        inner join strukpelayanan_t as sp on sp.norec=pp.strukfk
                        inner join strukpelayananpenjamin_t as spp on spp.nostrukfk =sp.norec
                        left join pemakaianasuransi_t as pa on  pa.noregistrasifk =pd.norec
                        left join bpjsklaimtxt_t as bpjs  on bpjs.sep = pa.nosep
                        left join nonbpjsklaimtxt_t as nonbpjs  on nonbpjs.norec_pd = pd.norec
                        where to_char(pd.tglpulang,'yyyy') ='$request[tahun]'
                        and pd.objectkelompokpasienlastfk  in (2,4,5,10)
                        and pd.kdprofile= $kdprofile
                        and (bpjs.norec is not null )
                        ) as x group by x.bulan

                        union all

                        select
                                sum ((x.totalklaim/x.totalbilling)*x.jasapelayanan)  as total,
                        x.bulan, 'sktm' as ket
                        from (
                                select distinct pd.objectkelompokpasienlastfk,pd.noregistrasi,pp.tglpelayanan,to_char(pd.tglpulang,'MM') as bulan,
                        ((pp.hargajual-(case when pp.hargadiscount is null then 0 else pp.hargadiscount end)+(case when pp.jasa is null then 0 else pp.jasa end))*pp.jumlah)
                             as jasapelayanan, spp.totalbiaya as totalbilling,
                        case when pd.objectkelompokpasienlastfk <> 5 then (
                        case when bpjs.tarif_inacbg is not null then bpjs.tarif_inacbg else  spp.totalppenjamin  end) ELSE
                        (case when nonbpjs.nominal is not null then nonbpjs.nominal else  spp.totalppenjamin  end) end
                            as totalklaim,
                        apd.norec as norec_apd,pp.jumlah,pp.norec as norec_pp,pp.produkfk
                        from pelayananpasien_t as pp
                        -- inner join pelayananpasienpetugas_t as ppp on ppp.pelayananpasien=pp.norec
                        left join antrianpasiendiperiksa_t as apd on apd.norec=pp.noregistrasifk
                        inner join pasiendaftar_t as pd on pd.norec=apd.noregistrasifk
                        inner join strukpelayanan_t as sp on sp.norec=pp.strukfk
                        inner join strukpelayananpenjamin_t as spp on spp.nostrukfk =sp.norec
                        left join pemakaianasuransi_t as pa on  pa.noregistrasifk =pd.norec
                        left join bpjsklaimtxt_t as bpjs  on bpjs.sep = pa.nosep
                        left join nonbpjsklaimtxt_t as nonbpjs  on nonbpjs.norec_pd = pd.norec
                        where to_char(pd.tglpulang,'yyyy') ='$request[tahun]'
                        and pd.objectkelompokpasienlastfk  in (2,4,5,10)
                        and pd.kdprofile= $kdprofile
                        and (nonbpjs.norec is not null )
                        ) as x group by x.bulan
            "
        ));

        $bulan = [];
        for ($m = 1; $m <= 12; $m++) {
            $month = date('F', mktime(0, 0, 0, $m, 1, date('Y')));
            $month2 = date('m', mktime(0, 0, 0, $m, 1, date('Y')));
            $bulan[] = array(
                'bln' => $month2,
                'blnstr' => $month,
                'umum' => 0,
                'bpjs' => 0,
                'sktm' => 0,
                'jasaraharja' => 0,
                'diklat' => 0,
                'mcu' => 0,
                'covid' => 0,
                'lainlain' => 0,
                'jasagiro' => 0,
                'total' => 0,
            );
        }

        $i = 0;
        foreach ($bulan as $b) {
            foreach ($data as $k) {
                if ($bulan[$i]['bln'] == $k->bulan) {
                    if ($k->ket == 'umum') {
                        $bulan[$i]['umum']  = $bulan[$i]['umum'] + (float)$k->total;
                    }
                    if ($k->ket == 'bpjs') {
                        $bulan[$i]['bpjs']  = $bulan[$i]['bpjs'] + (float)$k->total;
                    }
                    if ($k->ket == 'sktm') {
                        $bulan[$i]['sktm']  = $bulan[$i]['sktm'] + (float)$k->total;
                    }
                    if ($k->ket == 'jasaraharja') {
                        $bulan[$i]['jasaraharja']  = $bulan[$i]['jasaraharja'] + (float)$k->total;
                    }
                    if ($k->ket == 'diklat') {
                        $bulan[$i]['diklat']  = $bulan[$i]['diklat'] + (float)$k->total;
                    }
                    if ($k->ket == 'mcu') {
                        $bulan[$i]['mcu']  = $bulan[$i]['mcu'] + (float)$k->total;
                    }
                    if ($k->ket == 'covid') {
                        $bulan[$i]['covid']  = $bulan[$i]['covid'] + (float)$k->total;
                    }
                    if ($k->ket == 'lainlain') {
                        $bulan[$i]['lainlain']  = $bulan[$i]['lainlain'] + (float)$k->total;
                    }
                    $bulan[$i]['total']  = $bulan[$i]['total'] + (float)$k->total;
                }
            }
            $i++;
        }

        return $this->respond($bulan);
    }


    public function getLapPagu(Request $request)
    {
        //TODO : CARI PAGU
        $kdProfile = $this->kdProfile;
        $tglAwal = $request['tglAwal'];
        $tglAkhir = $request['tglAkhir'];
        $nama = $request['nama'];
        $produk = $request['produk'];
        $kpId = '';
        $kpIn = '';
        $kpNotIn = '';
        if (isset($request['kpId']) && $request['kpId'] != '') {
            $kpId  = "  and pd.objectkelompokpasienlastfk in (" . $request['kpId'] . " )"; //explode(',',$request['kpId']);
        } else {
            $kpIn = ' and pd.objectkelompokpasienlastfk  in (2,4,5,10)';
            $kpNotIn = ' and pd.objectkelompokpasienlastfk not in (2,4,5,10)';
        }

        $SCSC = StrukPagu::where('periodeawal', $request['tglAwal'])->get();
        $StrukPagu = false;
        if (count($SCSC) > 0) {
            $StrukPagu = true;
        }

        $data = DB::select(DB::raw(
            "select v.noregistrasi, v.nocm,
             v.namapasien,v.kelompokpasien,sum(v.jaspelproporsi) as total
             from (
                select  (x.jasapelayanan  )  as  jaspelproporsi,x.* from (
                select pd.noregistrasi,ps.nocm,ps.namapasien,kp.kelompokpasien,pp.tglpelayanan,pr.namaproduk,
                ((pp.hargajual-(case when pp.hargadiscount is null then 0 else pp.hargadiscount end)+(case when pp.jasa is null then 0 else pp.jasa end))*pp.jumlah)
                  as jasapelayanan,
                0 as totalbilling,0 as totalklaim,
                pgpj.namalengkap as dokterpj ,pgpj.id as dokterpjid ,
                apd.norec as norec_apd,pp.jumlah,pp.norec as norec_pp,pp.produkfk,ppp.objectpegawaifk,ru.objectdepartemenfk,apd.objectruanganfk,pp.isparamedis,
                (case when pp.jasa is null then 0 else pp.jasa end)*pp.jumlah as jasa,ru.namaruangan
                from pelayananpasien_t as pp
                inner join pelayananpasienpetugas_t as ppp on ppp.pelayananpasien=pp.norec
                left join antrianpasiendiperiksa_t as apd on apd.norec=pp.noregistrasifk
                inner join pasiendaftar_t as pd on pd.norec=apd.noregistrasifk
                inner join pasien_m as ps on ps.id=pd.nocmfk
                inner join kelompokpasien_m as kp on kp.id=pd.objectkelompokpasienlastfk
                left join pegawai_m as pgpj on pgpj.id=ppp.objectpegawaifk
                left join produk_m as pr on pr.id =pp.produkfk
                left join ruangan_m as ru on ru.id=apd.objectruanganfk
                inner join strukpelayanan_t as sp on sp.norec=pp.strukfk
                inner join strukbuktipenerimaan_t as sbm on sp.nosbmlastfk = sbm.norec
                where pd.tglpulang between '$tglAwal' and '$tglAkhir'
                --and ppp.objectjenispetugaspefk=4
                and ppp.statusenabled=true
                $kpId
                $kpNotIn
                and sbm.statusenabled =true
                and pd.kdprofile=$kdProfile) as x

                union all

                select
                   (x.totalklaim/x.totalbilling)*x.jasapelayanan as jaspelproporsi,
                    x.*   from (
                    select distinct pd.noregistrasi,ps.nocm,ps.namapasien,kp.kelompokpasien,pp.tglpelayanan,pr.namaproduk,
                ((pp.hargajual-(case when pp.hargadiscount is null then 0 else pp.hargadiscount end)+(case when pp.jasa is null then 0 else pp.jasa end))*pp.jumlah)
                   as jasapelayanan, spp.totalbiaya as totalbilling,
                case when pd.objectkelompokpasienlastfk <> 5 then (
                case when bpjs.tarif_inacbg is not null then bpjs.tarif_inacbg else  spp.totalppenjamin  end) ELSE
                (case when nonbpjs.nominal is not null then nonbpjs.nominal else  spp.totalppenjamin  end) end
                  as totalklaim,
                pgpj.namalengkap as dokterpj ,pgpj.id as dokterpjid ,
                apd.norec as norec_apd,pp.jumlah,pp.norec as norec_pp,pp.produkfk,ppp.objectpegawaifk,ru.objectdepartemenfk,apd.objectruanganfk,pp.isparamedis,
                (case when pp.jasa is null then 0 else pp.jasa end)*pp.jumlah as jasa,ru.namaruangan
                from pelayananpasien_t as pp
                inner join pelayananpasienpetugas_t as ppp on ppp.pelayananpasien=pp.norec
                left join antrianpasiendiperiksa_t as apd on apd.norec=pp.noregistrasifk
                inner join pasiendaftar_t as pd on pd.norec=apd.noregistrasifk
                   inner join pasien_m as ps on ps.id=pd.nocmfk
                   inner join kelompokpasien_m as kp on kp.id=pd.objectkelompokpasienlastfk
                left join pegawai_m as pgpj on pgpj.id=ppp.objectpegawaifk
                left join produk_m as pr on pr.id =pp.produkfk
                left join ruangan_m as ru on ru.id=apd.objectruanganfk
                inner join strukpelayanan_t as sp on sp.norec=pp.strukfk
                inner join strukpelayananpenjamin_t as spp on spp.nostrukfk =sp.norec
                left join pemakaianasuransi_t as pa on  pa.noregistrasifk =pd.norec
                left join bpjsklaimtxt_t as bpjs  on bpjs.sep = pa.nosep
                left join nonbpjsklaimtxt_t as nonbpjs  on nonbpjs.norec_pd = pd.norec
                where pd.tglpulang between '$tglAwal' and '$tglAkhir'
                --and ppp.objectjenispetugaspefk=4
                and ppp.statusenabled=true
                $kpIn
                $kpId
                and pd.kdprofile=$kdProfile
                and (bpjs.norec is not null or nonbpjs.norec is not null)
                ) as x

                union all

                select ((x.totalklaim/x.totalbilling)* x.jasapelayanan ) as jaspelproporsi,x.*   from (
                select  distinct pd.noregistrasi,ps.nocm,ps.namapasien,kp.kelompokpasien,pp.tglpelayanan,pr.namaproduk,
                ((pp.hargajual-(case when pp.hargadiscount is null then 0 else pp.hargadiscount end)+(case when pp.jasa is null then 0 else pp.jasa end))*pp.jumlah)
                as jasapelayanan, spp.totalbiaya as totalbilling,
                case when pd.objectkelompokpasienlastfk <> 5 then (
                case when bpjs.tarif_inacbg is not null then bpjs.tarif_inacbg else  spp.totalppenjamin  end) ELSE
                (case when nonbpjs.nominal is not null then nonbpjs.nominal else  spp.totalppenjamin  end) end
                as totalklaim,
                pgpj.namalengkap as dokterpj ,pgpj.id as dokterpjid ,
                apd.norec as norec_apd,pp.jumlah,pp.norec as norec_pp,pp.produkfk,sr.penulisresepfk as objectpegawaifk,ru.objectdepartemenfk,apd.objectruanganfk,pp.isparamedis,
                (case when pp.jasa is null then 0 else pp.jasa end)*pp.jumlah as jasa,ru.namaruangan
                from pelayananpasien_t as pp
                inner join strukresep_t as sr on sr.norec=pp.strukresepfk
                left join antrianpasiendiperiksa_t as apd on apd.norec=pp.noregistrasifk
                inner join pasiendaftar_t as pd on pd.norec=apd.noregistrasifk
                inner join pasien_m as ps on ps.id=pd.nocmfk
                inner join kelompokpasien_m as kp on kp.id=pd.objectkelompokpasienlastfk
                left join pegawai_m as pgpj on pgpj.id=sr.penulisresepfk
                left join produk_m as pr on pr.id =pp.produkfk
                left join ruangan_m as ru on ru.id=apd.objectruanganfk
                inner join strukpelayanan_t as sp on sp.norec=pp.strukfk
                inner join strukpelayananpenjamin_t as spp on spp.nostrukfk =sp.norec
                left join pemakaianasuransi_t as pa on  pa.noregistrasifk =pd.norec
                left join bpjsklaimtxt_t as bpjs  on bpjs.sep = pa.nosep
                left join nonbpjsklaimtxt_t as nonbpjs  on nonbpjs.norec_pd = pd.norec
                where pd.tglpulang between '$tglAwal' and '$tglAkhir'
                and sr.statusenabled=true
                 $kpIn
                 $kpId
                 and pd.kdprofile=$kdProfile
                 and (bpjs.norec is not null or nonbpjs.norec is not null)
                    ) as x
                ) as v
                    group by  v.noregistrasi, v.nocm,
                    v.namapasien,v.kelompokpasien

            "
        ));

        $result = array(
            'data' => $data,
            'strukpagu' => $StrukPagu,
            'message' => 'as@epic',
        );

        return $this->respond($result);
    }

    public function getDafarDetailJenisPaguRemun(Request $r)
    {
        $dateRange = [$r->tglAwal, $r->tglAkhir];

        $data = DB::table('detailpegawaipagu_t as dpp')
            ->join('detailjenispagu_t as djp', 'djp.id', 'dpp.djpid')
            ->join('strukclosing_t as sc', 'sc.norec', 'dpp.strukclosingfk')
            ->join('jenispagu_t as jp', 'jp.id', 'dpp.jpid')
            ->selectRaw("
                sum(dpp.jenispaginilaitotal ) as jml,djp.detailjenispagu,sc.tglclosing,
                sc.noclosing,sc.tglawal,sc.tglakhir,jp.jenispagu")
            ->whereBetween(DB::raw("CAST(sc.tglclosing as Date)"), $dateRange)
            ->where('sc.kdprofile', $this->kdProfile)
            ->where('sc.statusenabled', true)
            ->where('dpp.statusenabled', true);
        if ($r['noclosing']) {
            $data = $data->where('sc.noclosing', 'ilike', '%' . $r['noclosing'] . '%');
        }
        if ($r['jenispagufk']) {
            $data = $data->where('jp.id', $r['jenispagufk']);
        }
        $data = $data->groupBy('djp.detailjenispagu', 'sc.tglclosing', 'sc.noclosing', 'sc.tglawal', 'sc.tglakhir', 'jp.id');
        $data = $data->get();

        return $this->respond($data);
    }

    public function GetDetailRemunPegawai(Request $request)
    {
        //TODO : daftar jenis remun
        $kdProfile = $this->kdProfile;

        $noclosing = $request['noclosing'];
        $dokterid = $request['IdDokter'];
        $RC = "";
        $RCEEG = "";
        $jpDireksi = $this->settingFix('JenisPaguDireksi');
        $jpStruktural = $this->settingFix('JenisPaguStruktural');
        $jpCasemix = $this->settingFix('JenisPaguCASEMIX');
        $jpJPTL = $this->settingFix('JenisPaguJPTL');
        $jpGabungan = $this->settingFix('jenisPaguGabungan');
        $jpAdministrasi = $this->settingFix('jenisPaguADMINISTRASI');
        $jpPotongan = $this->settingFix('jenisPaguPOTONGAN');

        $djpKlPenghasil = $this->settingFix('paguKelompokPenghasil');
        $djpKlPengirim = $this->settingFix('detailJenispaguPengirim');
        $djpIndPenghasil = $this->settingFix('DJPIndividuPenghasil');
        $djpKLApotik = $this->settingFix('paguKelompokApoteker');
        $djpKepalaInst = $this->settingFix('DJPKepalaInst');
        $djpSpesialisAnak = $this->settingFix('DJPSpesialisAnak');

        $remun = DB::table('strukclosing_t as sc')
            ->join('detailpegawaipagu_t as dpp', 'dpp.strukclosingfk', 'sc.norec')
            ->join('detailjenispagu_t as djp', 'djp.id', 'dpp.djpid')
            ->selectRaw(
                "sc.tglclosing as tglpelayanan,'-' as nocm,'-' as noregistrasi,'-' as namapasien,dpp.jenis as  namaproduk,
                        0 as jumlah,false as isparamedis,false as iscito,0 as hargasatuan,dpp.jenispaginilaitotal as jenispagunilai,'' as norec_pp,dpp.jpid as jpid,
                        '' as namaruangan,djp.detailjenispagu,'-' as kelompokpasien,null as kpid"
            )
            ->where('sc.noclosing', $noclosing)
            ->where('dpp.pegawaiid', $dokterid)
            ->whereIn('dpp.jpid', [$jpDireksi, $jpStruktural, $jpCasemix, $jpJPTL, $jpGabungan, $jpAdministrasi, $jpPotongan])
            ->where('dpp.statusenabled', true)
            ->where('dpp.kdprofile', $this->kdProfile);

        if ($request['klmpenghasil'] == 'true') {

            $jplIndividu = DB::table('strukclosing_t as sc')
                ->join('detailpegawaipagu_t AS dpp', 'dpp.strukclosingfk', 'sc.norec')
                ->join('strukdetailpagu_t AS sdp', 'sdp.norec', 'dpp.norec_sdp')
                ->join('detailjenispagu_t as djp', 'djp.id', 'dpp.djpid')
                ->join('pelayananpasien_t as pp', 'pp.norec', 'sdp.pelayananpasienfk')
                ->join('antrianpasiendiperiksa_t as apd', 'apd.norec', 'pp.noregistrasifk')
                ->join('pasiendaftar_t as pd', 'pd.norec', 'apd.noregistrasifk')
                ->join('produk_m as pr', 'pr.id', 'sdp.produkfk')
                ->join('pasien_m as ps', 'ps.id', 'pd.nocmfk')
                ->join('ruangan_m as ru', 'ru.id', 'apd.objectruanganfk')
                ->leftjoin('kelompokpasien_m as kp', 'kp.id', 'pd.objectkelompokpasienlastfk')
                ->select(
                    'pp.tglpelayanan',
                    'ps.nocm',
                    'pd.noregistrasi',
                    'ps.namapasien',
                    'pr.namaproduk',
                    'pp.jumlah',
                    'pp.isparamedis',
                    'pp.iscito',
                    'pp.hargasatuan',
                    'dpp.jenispaginilaitotal as jenispaginilai',
                    'pp.norec as norec_pp',
                    'sdp.jenispagufk as jpid',
                    'ru.namaruangan',
                    'djp.detailjenispagu',
                    'kp.kelompokpasien',
                    'kp.id as kpid'
                )
                ->where('sc.noclosing', $noclosing)
                ->where('sdp.jenispagufk', $this->settingFix('JenisPaguJPL'))
                ->where('dpp.djpid', $this->settingFix('paguKelompokPenghasil'))
                ->where('dpp.pegawaiid', $dokterid)
                ->where('dpp.statusenabled', true)
                ->where('sc.kdprofile', $this->kdProfile);

        } else {

            $jplIndividu1 = DB::table('strukclosing_t as sc')
                ->join('detailpegawaipagu_t AS dpp', 'dpp.strukclosingfk', 'sc.norec')
                ->join('strukdetailpagu_t AS sdp', 'sdp.norec', 'dpp.norec_sdp')
                ->join('detailjenispagu_t as djp', 'djp.id', 'dpp.djpid')
                ->leftjoin('pelayananpasien_t as pp', 'pp.norec', 'sdp.pelayananpasienfk')
                ->join('antrianpasiendiperiksa_t as apd', 'apd.norec', 'pp.noregistrasifk')
                ->join('pasiendaftar_t as pd', 'pd.norec', 'apd.noregistrasifk')
                ->join('produk_m as pr', 'pr.id', 'sdp.produkfk')
                ->join('pasien_m as ps', 'ps.id', 'pd.nocmfk')
                ->join('ruangan_m as ru', 'ru.id', 'apd.objectruanganfk')
                ->leftjoin('kelompokpasien_m as kp', 'kp.id', 'pd.objectkelompokpasienlastfk')
                ->select(
                    'pp.tglpelayanan',
                    'ps.nocm',
                    'pd.noregistrasi',
                    'ps.namapasien',
                    'pr.namaproduk',
                    'pp.jumlah',
                    'pp.isparamedis',
                    'pp.iscito',
                    'pp.hargasatuan',
                    'dpp.jenispaginilaitotal as jenispaginilai',
                    'pp.norec as norec_pp',
                    'sdp.jenispagufk as jpid',
                    'ru.namaruangan',
                    'djp.detailjenispagu',
                    'kp.kelompokpasien',
                    'kp.id as kpid'
                )
                ->where('sc.noclosing', $noclosing)
                ->where('sdp.jenispagufk', $this->settingFix('JenisPaguJPL'))
                ->whereIn('dpp.djpid',[$djpIndPenghasil,$djpKlPenghasil,$djpKlPengirim])
                ->where('dpp.pegawaiid', $dokterid)
                ->where('dpp.statusenabled', true)
                ->where('sc.kdprofile', $this->kdProfile);

            $jplIndividu2 = DB::table('strukclosing_t as sc')
                ->join('detailpegawaipagu_t AS dpp', 'dpp.strukclosingfk', 'sc.norec')
                ->join('strukdetailpagu_t AS sdp', 'sdp.norec', 'dpp.norec_sdp')
                ->join('detailjenispagu_t as djp', 'djp.id', 'dpp.djpid')
                ->join('pelayananpasien_t as pp', 'pp.norec', 'sdp.pelayananpasienfk')
                ->join('antrianpasiendiperiksa_t as apd', 'apd.norec', 'pp.noregistrasifk')
                ->join('pasiendaftar_t as pd', 'pd.norec', 'apd.noregistrasifk')
                ->join('produk_m as pr', 'pr.id', 'sdp.produkfk')
                ->join('pasien_m as ps', 'ps.id', 'pd.nocmfk')
                ->join('ruangan_m as ru', 'ru.id', 'apd.objectruanganfk')
                ->leftjoin('kelompokpasien_m as kp', 'kp.id', 'pd.objectkelompokpasienlastfk')
                ->select(
                    'pp.tglpelayanan',
                    'ps.nocm',
                    'pd.noregistrasi',
                    'ps.namapasien',
                    'pr.namaproduk',
                    'pp.jumlah',
                    'pp.isparamedis',
                    'pp.iscito',
                    'pp.hargasatuan',
                    'dpp.jenispaginilaitotal as jenispaginilai',
                    'pp.norec as norec_pp',
                    'sdp.jenispagufk as jpid',
                    'ru.namaruangan',
                    'djp.detailjenispagu',
                    'kp.kelompokpasien',
                    'kp.id as kpid'
                )
                ->where('sc.noclosing', $noclosing)
                ->where('sdp.jenispagufk', $this->settingFix('JenisPaguJPL'))
                ->whereIn('dpp.djpid', [$djpKlPengirim, $djpKlPenghasil, $djpKLApotik, $djpKepalaInst, $djpSpesialisAnak])
                ->where('dpp.pegawaiid', $dokterid)
                ->where('dpp.statusenabled', true)
                ->where('sc.kdprofile', $this->kdProfile);

            $jplIndividu = $jplIndividu1->union($jplIndividu2);

            // $JPLINDIVIDU = "select   pp.tglpelayanan,ps.nocm,pd.noregistrasi,ps.namapasien,pr.namaproduk,
            //     pp.jumlah,pp.isparamedis,pp.iscito,pp.hargasatuan,
            //     dpp.jenispaginilaitotal as jenispaginilai,pp.norec as norec_pp,sdp.jenispagufk as jpid,
            //     ru.namaruangan,djp.detailjenispagu,kp.kelompokpasien,kp.id as kpid
            //     from strukclosing_t as sc
            //     INNER JOIN detailpegawaipagu_t AS dpp ON dpp.strukclosingfk = sc.norec
            //     INNER JOIN strukdetailpagu_t AS sdp ON sdp.norec = dpp.norec_sdp
            //     INNER JOIN detailjenispagu_t as djp on djp.id=dpp.djpid
            //     INNER JOIN pelayananpasien_t as pp on pp.norec=sdp.pelayananpasienfk
            //     INNER JOIN antrianpasiendiperiksa_t as apd on apd.norec=pp.noregistrasifk
            //     INNER JOIN pasiendaftar_t as pd on pd.norec=apd.noregistrasifk
            //     INNER JOIN produk_m as pr on pr.id=sdp.produkfk
            //     INNER JOIN pasien_m as ps on ps.id=pd.nocmfk
            //     INNER JOIN ruangan_m as ru on ru.id=apd.objectruanganfk
            //     left JOIN kelompokpasien_m as kp on kp.id=pd.objectkelompokpasienlastfk

            //     where sc.noclosing='$noclosing'
            //      and sdp.dokterid= $dokterid
            //      and sdp.jenispagufk=16
            //     and dpp.djpid in (141,142,143) and dpp.pegawaiid =$dokterid
            //     and dpp.statusenabled = true
            //      and sc.kdprofile=$kdProfile

            //      union all

            //     select   pp.tglpelayanan,ps.nocm,pd.noregistrasi,ps.namapasien,pr.namaproduk,
            //     pp.jumlah,pp.isparamedis,pp.iscito,pp.hargasatuan,
            //     dpp.jenispaginilaitotal as jenispaginilai,pp.norec as norec_pp,sdp.jenispagufk as jpid,
            //     ru.namaruangan,djp.detailjenispagu,kp.kelompokpasien,kp.id as kpid
            //     from strukclosing_t as sc
            //     INNER JOIN detailpegawaipagu_t AS dpp ON dpp.strukclosingfk = sc.norec
            //     INNER JOIN strukdetailpagu_t AS sdp ON sdp.norec = dpp.norec_sdp
            //     INNER JOIN detailjenispagu_t as djp on djp.id=dpp.djpid
            //     INNER JOIN pelayananpasien_t as pp on pp.norec=sdp.pelayananpasienfk
            //     INNER JOIN antrianpasiendiperiksa_t as apd on apd.norec=pp.noregistrasifk
            //     INNER JOIN pasiendaftar_t as pd on pd.norec=apd.noregistrasifk
            //     INNER JOIN produk_m as pr on pr.id=sdp.produkfk
            //     INNER JOIN pasien_m as ps on ps.id=pd.nocmfk
            //     INNER JOIN ruangan_m as ru on ru.id=apd.objectruanganfk
            //     left JOIN kelompokpasien_m as kp on kp.id=pd.objectkelompokpasienlastfk
            //     where sc.noclosing='$noclosing'
            //      and sdp.dokterid !=  dpp.pegawaiid
            //      and sdp.jenispagufk=16
            //     and dpp.djpid in (142,201,202,135,143) and dpp.pegawaiid =$dokterid
            //     and dpp.statusenabled = true
            //      and sc.kdprofile=$kdProfile
            //     ";
        }

        $data = $remun->unionAll($jplIndividu)->get();

        return $this->respond($data);
    }

    public function getRincianRemunDetailPegawai(Request $r)
    {
        $kdProfile = $this->kdProfile;
        $persenJaspel = (int) $this->settingFix('PersenJasaPelayanan');

        $data = collect(DB::select("
                select  case
                    when x.objectruanganfk = 610 then  (x.jasapelayanan  * 25/100)
                    else (x.jasapelayanan ) end as  jaspel,
                    case
                    when x.objectruanganfk = 610 then  (x.jasapelayanan  * 25/100) * $persenJaspel /100--bank darah
                    else (x.jasapelayanan  * $persenJaspel/100) end as  jaspelproporsi,x.* from (
                select pd.noregistrasi,pp.tglpelayanan,pr.namaproduk,
                ((pp.hargajual-(case when pp.hargadiscount is null then 0 else pp.hargadiscount end)+(case when pp.jasa is null then 0 else pp.jasa end))*pp.jumlah)
                  as jasapelayanan,
                0 as totalbilling,0 as totalklaim,
                pgpj.namalengkap as dokterpj ,pgpj.id as dokterpjid ,
                apd.norec as norec_apd,pp.jumlah,pp.norec as norec_pp,pp.produkfk,ppp.objectpegawaifk,ru.objectdepartemenfk,apd.objectruanganfk,pp.isparamedis,
                (case when pp.jasa is null then 0 else pp.jasa end)*pp.jumlah as jasa,ru.namaruangan
                from pelayananpasien_t as pp
                inner join pelayananpasienpetugas_t as ppp on ppp.pelayananpasien=pp.norec
                left join antrianpasiendiperiksa_t as apd on apd.norec=pp.noregistrasifk
                inner join pasiendaftar_t as pd on pd.norec=apd.noregistrasifk
                left join pegawai_m as pgpj on pgpj.id=ppp.objectpegawaifk
                left join produk_m as pr on pr.id =pp.produkfk
                left join ruangan_m as ru on ru.id=apd.objectruanganfk
                inner join strukpelayanan_t as sp on sp.norec=pp.strukfk
                inner join strukbuktipenerimaan_t as sbm on sp.nosbmlastfk = sbm.norec
               where pp.norec='$r[norec_pp]'
                and ppp.objectjenispetugaspefk=4 and ppp.statusenabled=true
                and pd.objectkelompokpasienlastfk not in (2,4,5,10)
                and sbm.statusenabled =true
                and pd.kdprofile=$kdProfile) as x

                union all

                select
                     case
                    when x.objectruanganfk = 610 then (((x.totalklaim/x.totalbilling)*x.jasapelayanan) * 25/100)
                    else (((x.totalklaim/x.totalbilling)*x.jasapelayanan) ) end as jaspel,
                    case
                    when x.objectruanganfk = 610 then (((x.totalklaim/x.totalbilling)*x.jasapelayanan) * 25/100)  *$persenJaspel/100
                    else (((x.totalklaim/x.totalbilling)*x.jasapelayanan) * $persenJaspel/100) end as jaspelproporsi,
                    x.*   from (
                    select distinct pd.noregistrasi,pp.tglpelayanan,pr.namaproduk,
                ((pp.hargajual-(case when pp.hargadiscount is null then 0 else pp.hargadiscount end)+(case when pp.jasa is null then 0 else pp.jasa end))*pp.jumlah)
                   as jasapelayanan, spp.totalbiaya as totalbilling,
                case when pd.objectkelompokpasienlastfk <> 5 then (
                case when bpjs.tarif_inacbg is not null then bpjs.tarif_inacbg else  spp.totalppenjamin  end) ELSE
                (case when nonbpjs.nominal is not null then nonbpjs.nominal else  spp.totalppenjamin  end) end
                  as totalklaim,
                pgpj.namalengkap as dokterpj ,pgpj.id as dokterpjid ,
                apd.norec as norec_apd,pp.jumlah,pp.norec as norec_pp,pp.produkfk,ppp.objectpegawaifk,ru.objectdepartemenfk,apd.objectruanganfk,pp.isparamedis,
                (case when pp.jasa is null then 0 else pp.jasa end)*pp.jumlah as jasa,ru.namaruangan
                from pelayananpasien_t as pp
                inner join pelayananpasienpetugas_t as ppp on ppp.pelayananpasien=pp.norec
                left join antrianpasiendiperiksa_t as apd on apd.norec=pp.noregistrasifk
                inner join pasiendaftar_t as pd on pd.norec=apd.noregistrasifk
                left join pegawai_m as pgpj on pgpj.id=ppp.objectpegawaifk
                left join produk_m as pr on pr.id =pp.produkfk
                left join ruangan_m as ru on ru.id=apd.objectruanganfk
                inner join strukpelayanan_t as sp on sp.norec=pp.strukfk
                inner join strukpelayananpenjamin_t as spp on spp.nostrukfk =sp.norec
                left join pemakaianasuransi_t as pa on  pa.noregistrasifk =pd.norec
                left join bpjsklaimtxt_t as bpjs  on bpjs.sep = pa.nosep
                left join nonbpjsklaimtxt_t as nonbpjs  on nonbpjs.norec_pd = pd.norec
                where pp.norec='$r[norec_pp]'
                 and ppp.objectjenispetugaspefk=4 and ppp.statusenabled=true

               and pd.objectkelompokpasienlastfk  in (2,4,5,10)

                and pd.kdprofile=$kdProfile
                and (bpjs.norec is not null or nonbpjs.norec is not null)
                ) as x

                union all

                select   (x.jasapelayanan * 25/100)  as jaspel,
                       (x.jasapelayanan * 25/100)  *$persenJaspel/100 as jaspelproporsi,x.* from (
                    select pd.noregistrasi,pp.tglpelayanan,pr.namaproduk,
                    ((pp.hargajual-(case when pp.hargadiscount is null then 0 else pp.hargadiscount end)+(case when pp.jasa is null then 0 else pp.jasa end))*pp.jumlah)
                    as jasapelayanan,
                    0 as totalbilling,0 as totalklaim,
                    pgpj.namalengkap as dokterpj ,pgpj.id as dokterpjid ,
                    apd.norec as norec_apd,pp.jumlah,pp.norec as norec_pp,pp.produkfk,sr.penulisresepfk as objectpegawaifk,ru.objectdepartemenfk,apd.objectruanganfk,pp.isparamedis,
                    (case when pp.jasa is null then 0 else pp.jasa end)*pp.jumlah as jasa,ru.namaruangan
                    from pelayananpasien_t as pp
                    inner join strukresep_t as sr on sr.norec=pp.strukresepfk
                    left join antrianpasiendiperiksa_t as apd on apd.norec=pp.noregistrasifk
                    inner join pasiendaftar_t as pd on pd.norec=apd.noregistrasifk
                    left join pegawai_m as pgpj on pgpj.id=sr.penulisresepfk
                    left join produk_m as pr on pr.id =pp.produkfk
                    left join ruangan_m as ru on ru.id=apd.objectruanganfk
                    inner join strukpelayanan_t as sp on sp.norec=pp.strukfk
                    inner join strukbuktipenerimaan_t as sbm on sp.nosbmlastfk = sbm.norec
                   where pp.norec='$r[norec_pp]'
                    and sr.statusenabled=true
                    and pd.objectkelompokpasienlastfk not in (2,4,5,10)
                    and sbm.statusenabled =true
                    and pd.kdprofile=$kdProfile

                    ) as x

                    union all

                select
                       ((x.totalklaim/x.totalbilling)* x.jasapelayanan )  *25/100 as jaspel,
                       (( ((x.totalklaim/x.totalbilling)* x.jasapelayanan )  *25/100)* $persenJaspel/100) as jaspelproporsi,x.*   from (
                select  distinct pd.noregistrasi,pp.tglpelayanan,pr.namaproduk,
                ((pp.hargajual-(case when pp.hargadiscount is null then 0 else pp.hargadiscount end)+(case when pp.jasa is null then 0 else pp.jasa end))*pp.jumlah)
                as jasapelayanan, spp.totalbiaya as totalbilling,
                case when pd.objectkelompokpasienlastfk <> 5 then (
                case when bpjs.tarif_inacbg is not null then bpjs.tarif_inacbg else  spp.totalppenjamin  end) ELSE
                (case when nonbpjs.nominal is not null then nonbpjs.nominal else  spp.totalppenjamin  end) end
                as totalklaim,
                pgpj.namalengkap as dokterpj ,pgpj.id as dokterpjid ,
                apd.norec as norec_apd,pp.jumlah,pp.norec as norec_pp,pp.produkfk,sr.penulisresepfk as objectpegawaifk,ru.objectdepartemenfk,apd.objectruanganfk,pp.isparamedis,
                (case when pp.jasa is null then 0 else pp.jasa end)*pp.jumlah as jasa,ru.namaruangan
                from pelayananpasien_t as pp
                inner join strukresep_t as sr on sr.norec=pp.strukresepfk
                left join antrianpasiendiperiksa_t as apd on apd.norec=pp.noregistrasifk
                inner join pasiendaftar_t as pd on pd.norec=apd.noregistrasifk
                left join pegawai_m as pgpj on pgpj.id=sr.penulisresepfk
                left join produk_m as pr on pr.id =pp.produkfk
                left join ruangan_m as ru on ru.id=apd.objectruanganfk
                inner join strukpelayanan_t as sp on sp.norec=pp.strukfk
                inner join strukpelayananpenjamin_t as spp on spp.nostrukfk =sp.norec
                left join pemakaianasuransi_t as pa on  pa.noregistrasifk =pd.norec
                left join bpjsklaimtxt_t as bpjs  on bpjs.sep = pa.nosep
                left join nonbpjsklaimtxt_t as nonbpjs  on nonbpjs.norec_pd = pd.norec
                where pp.norec='$r[norec_pp]'

                 and sr.statusenabled=true
                 and pd.objectkelompokpasienlastfk  in (2,4,5,10)

                and pd.kdprofile=$kdProfile
                 and (bpjs.norec is not null or nonbpjs.norec is not null)
                ) as x
            "))->first();
        $pagu = collect(DB::select("select sum(jenispagunilai) as jenispagunilai from strukdetailpagu_t where pelayananpasienfk='$r[norec_pp]' and jenispagufk=$r[jpid]"))->first();
        if (!empty($data)) {
            $data->jaspel =  (float)  $data->jaspel;
            $data->jaspelproporsi =  (float)  $data->jaspelproporsi;
            $data->jasapelayanan =  (float)  $data->jasapelayanan;
            $data->totalbilling =  (float)  $data->totalbilling;
            $data->totalklaim =  (float)  $data->totalklaim;
        }
        $res['remun'] = $data;
        $res['persenJaspel'] = $persenJaspel;
        $res['pagu'] = $pagu;

        return $this->respond($res);
    }

    public function saveDetailKelompok(Request $request)
    {
        $kdProfile = $this->kdProfile;
        DB::beginTransaction();
        try {
            $sc = StrukClosing::where('noclosing', $request['noclosing'])
                ->where('statusenabled', true)
                ->where('kdprofile', $kdProfile)
                ->first();

            DetailKelompokPenghasil::where('strukclosingfk', $sc->norec)
                ->where('ruanganfk', $request['ruanganfk'])
                ->where('kdprofile', $kdProfile)
                ->delete();

            foreach ($request['details'] as $v) {
                $DKP = new DetailKelompokPenghasil();
                $DKP->norec = $DKP->generateNewId();
                $DKP->kdprofile = $kdProfile;
                $DKP->statusenabled = true;
                $DKP->strukclosingfk = $sc->norec;
                $DKP->jenis = null;
                $DKP->pagunilai = $v['pagunilai'];
                $DKP->norec_sdp = null;
                $DKP->pegawaiid = $v['pegawaiid'];
                $DKP->tglpelayanan = date('Y-m-d H:i');
                $DKP->djpid = $request['djpid'];
                $DKP->jpid = $request['jpid'];
                $DKP->ruanganfk = $request['ruanganfk'];
                $DKP->save();
            }

            DB::commit();
            $result = array(
                "status" => 201,
                "by" => 'er@epic',
                "message" => 'Simpan Data Berhasil',
            );
        } catch (\Exception $e) {
            DB::rollBack();
            $result = array(
                "status" => 400,
                "by" => 'er@epic',
                "message" => 'Simpan Gagal !',
                "data" => $e->getMessage()
            );
        }

        return $this->respond($result, $result['status'], $result['message']);
    }

    public function getDataDetailKelompok(Request $request)
    {

        $sc = StrukClosing::where('noclosing', $request['noclosing'])
            ->where('statusenabled', true)
            ->where('kdprofile', $this->kdProfile)
            ->first();

        $data = DB::table('detailkelompokpenghasil_t as dkp')
            ->leftJoin('pegawai_m as pg', 'pg.id', 'dkp.pegawaiid')
            ->select('pg.namalengkap as namapegawai', 'pg.id as pegawaiid', 'dkp.norec', 'dkp.pagunilai')
            ->where('dkp.strukclosingfk', $sc->norec)
            ->where('dkp.ruanganfk', $request['ruanganfk'])
            ->where('dkp.kdprofile', $this->kdProfile)
            ->where('dkp.statusenabled', true)
            ->get();

        return $this->respond($data);
    }
}
