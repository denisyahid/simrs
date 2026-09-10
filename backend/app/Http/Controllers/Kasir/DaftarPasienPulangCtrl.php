<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use App\Models\Master\SettingDataFixed;
use App\Models\Transaksi\LogAcc;
use App\Models\Transaksi\OrderPelayanan;
use App\Models\Transaksi\PasienDaftar;
use App\Models\Transaksi\PelayananPasien;
use App\Models\Transaksi\StrukOrder;
use App\Models\Transaksi\StrukPelayanan;
use App\Models\Transaksi\StrukPelayananPenjamin;
use App\Models\Transaksi\StrukPelayananPenjaminDetail;
use App\Models\Transaksi\StrukBuktiPenerimaan;
use App\Traits\Valet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;

class DaftarPasienPulangCtrl extends Controller
{
    use Valet;
    public function __construct()
    {
        parent::__construct($is_encrypt = true);
    }


    public function daftarPasienPulang(Request $request)
    {
        $data = DB::table('pasiendaftar_t as pd')
            ->join('pasien_m as p', 'p.id', '=', 'pd.nocmfk')
            ->join('ruangan_m as ru', 'ru.id', '=', 'pd.objectruanganlastfk')
            ->leftJoin('kelompokpasien_m as kp', 'kp.id', '=', 'pd.objectkelompokpasienlastfk')
            ->leftjoin('antrianapotik_t as aa', 'aa.noregistrasi', '=', 'pd.noregistrasi')
            ->leftJoin('departemen_m as dept', 'dept.id', '=', 'ru.objectdepartemenfk')
            ->leftJoin('statuspiutang_m as stp', 'stp.id', '=', 'pd.objectstatuspiutangfk')
            ->leftjoin('pemakaianasuransi_t as pa', 'pa.noregistrasifk', '=', 'pd.norec')
            ->leftjoin('pegawai_m as pg', 'pg.id', '=', 'pd.objectpegawaifk')
            ->leftjoin('kebangsaan_m as kbg', 'kbg.id', '=', 'p.objectkebangsaanfk')
            ->leftjoin('jeniskelamin_m as jk', 'jk.id', '=', 'p.objectjeniskelaminfk')
            ->leftjoin('strukpelayanan_t as sp', function ($join) {
                $join->on('pd.norec', '=', 'sp.noregistrasifk')
                    ->where('sp.objectkelompoktransaksifk', '!=', 46)
                    ->where('sp.statusenabled', '=', true);
            })
            ->leftJoin('pegawai_m as pg2', 'pg2.id', '=', 'sp.objectpegawaipenerimafk')
            ->leftjoin('rekanan_m as rek', 'rek.id', '=', 'pd.objectrekananfk')
            ->leftJoin('strukbuktipenerimaan_t as sbm', function ($join) {
                $join->on('sbm.nostrukfk', '=', 'sp.norec')
                    ->where('sbm.statusenabled', '=', true);
            })
            // ->leftjoin('strukbuktipenerimaancarabayar_t as sbmcr', 'sbmcr.nosbmfk', '=', 'sbm.norec')
            ->select(
                'pd.norec AS norec_pd',
                'pd.tglregistrasi',
                'p.nocm',
                'pd.nocmfk',
                'aa.jenis',
                'aa.noantri',
                'pd.noregistrasi',
                'ru.namaruangan',
                'p.namapasien',
                'kp.kelompokpasien',
                'pd.tglpulang',
                'pd.statuspasien',
                'pd.nostruklastfk',
                'pd.nosbmlastfk',
                'pd.tglmeninggal',
                'pd.isclosing',
                'pd.statusbayar',
                'p.nosuratkematian',
                'pd.objectkelompokpasienlastfk',
                'dept.id as deptid',
                'pd.objectstatuspiutangfk',
                DB::raw("case when pd.objectstatuspiutangfk is not null then stp.statuspiutang else '-' end as statuspiutang"),
                DB::raw("p.namapasien || ' - (' || case when jk.id = 1 then 'L' else 'P' end || ') - ' || kbg.name as namatext"),
                DB::raw("case when jk.id = 1 then 'L' else 'P' end as jkText"),
                // 'kbg.name'
                'pd.tglclosing',
                'pd.objectruanganlastfk',
                'pd.objectkelasfk',
                'p.tgllahir',
                'rek.namarekanan',
                'pa.nosep as nosep',
                'pa.norec as norec_pa',
                'sp.norec as norec_sp',
                'pa.objectasuransipasienfk',
                'kbg.name as kebangsaan',
                'pa.ppkrujukan',
                'pa.objectdiagnosafk as iddiagnosabpjs',
                'sbm.nosbm',
                // 'sbmcr.norec as norec_sbmcr', 
                'sbm.norec as norec_sbm', 
                'pg.namalengkap AS dokter',
                'pg2.namalengkap as closer',
                'sp.totalharusdibayar',
                'sp.totaliurbayar',
                'sp.kodingdiagnosa',
                'pd.inacbg_grouper'
            )
            // ->where('pa.statusenabled', true)
            ->where('pd.statusenabled', true);
            // ->whereNotNull('pd.tglpulang');
            // ->whereNull('pd.nostruklastfk')
            // ->whereNull('pd.nosbmlastfk')

        

        $filter = $request->all();
        $tglAwal = $filter['tglAwal'];
        $tglAkhir = $filter['tglAkhir'];
        if (isset($filter['tglAwal']) && $filter['tglAwal'] != "" && $filter['tglAwal'] != "undefined") {
            $data = $data->whereDate('pd.tglregistrasi', '>=', $filter['tglAwal']);
        }

        if (isset($filter['tglAkhir']) && $filter['tglAkhir'] != "" && $filter['tglAkhir'] != "undefined") {
            $tgl = $filter['tglAkhir']; //." 23:59:59";
            $data = $data->whereDate('pd.tglregistrasi', '<=', $tgl);
        }

        if (isset($filter['ruanganfk']) && $filter['ruanganfk'] != '') {
            $data = $data->whereIn('ru.id', explode(',', $filter['ruanganfk']));
        }

        if (isset($filter['ruanganId']) && $filter['ruanganId'] != "" && $filter['ruanganId'] != "undefined") {
            $data = $data->where('ru.id', '=', $filter['ruanganId']);
        }

        // var_dump($filter['statusverifikasi']);

        if (isset($filter['statusverifikasi']) && $filter['statusverifikasi'] != "" && $filter['statusverifikasi'] != "undefined" && $filter['statusverifikasi'] == "false") {
            $data = $data->whereNull('pd.nostruklastfk');
        } else if (isset($filter['statusverifikasi']) && $filter['statusverifikasi'] != "" && $filter['statusverifikasi'] != "undefined" && $filter['statusverifikasi'] == "true") {
            $data = $data->whereNotNull('pd.nostruklastfk');
        }

        if (isset($filter['namaPasien']) && $filter['namaPasien'] != "" && $filter['namaPasien'] != "undefined") {
            $data = $data->whereRaw("(p.namapasien ilike '%".$filter['namaPasien']."%' or p.nocm ilike '%".$filter['namaPasien']."%' or pd.noregistrasi ilike '%".$filter['namaPasien']."%' )");
        }

        if (isset($filter['kelompokPasienId']) && $filter['kelompokPasienId'] != "" && $filter['kelompokPasienId'] != "undefined") {
            $data = $data->where('kp.id', '=', $filter['kelompokPasienId']);
        }

        if (isset($filter['jmlRows']) && $filter['jmlRows'] != "" && $filter['jmlRows'] != "undefined") {
            $data = $data->take($filter['jmlRows']);
        }
        
        if (isset($filter['limit']) && $filter['limit'] != "" && $filter['limit'] != "undefined") {
            $data = $data->limit($filter['limit']);
        }
        if (isset($filter['offset']) && $filter['offset'] != "" && $filter['offset'] != "undefined") {
            $data = $data->offset($filter['offset']);
        }

        //var_dump($data->toSql());
        $data = $data->groupBy(
                'pd.norec',
                'pd.tglregistrasi',
                'p.nocm',
                'aa.jenis',
                'aa.noantri',
                'pd.nocmfk',
                'pd.noregistrasi',
                'ru.namaruangan',
                'p.namapasien',
                'kp.kelompokpasien',
                'pd.tglpulang',
                'pd.statuspasien',
                'pd.nostruklastfk',
                'pd.nosbmlastfk',
                'pd.tglmeninggal',
                'p.nosuratkematian',
                'pd.objectkelompokpasienlastfk',
                'dept.id',
                'pd.objectstatuspiutangfk',
                'pd.tglclosing',
                'pd.objectruanganlastfk',
                'pd.objectkelasfk',
                'p.tgllahir',
                'rek.namarekanan',
                'pa.nosep',
                'pa.norec',
                'sp.norec',
                'pa.objectasuransipasienfk',
                'kbg.name',
                'pa.ppkrujukan',
                'pa.objectdiagnosafk',
                'sbm.nosbm',
                'stp.statuspiutang',
                'jk.id',
                // 'sbmcr.norec',
                'sbm.norec',
                'pg.namalengkap',
                'pg2.namalengkap',
        );
        // $data = $data->distinct();
        $data = $data->orderBy('pd.tglpulang')->get();

        $dataTarif16 = DB::select(
            DB::raw("select norec, sum(ttl) as ttl
            from(
                SELECT
                pd.norec,
                    SUM (
                    ( ( pp.hargasatuan - CASE WHEN pp.hargadiscount IS NULL THEN 0 ELSE pp.hargadiscount END ) * pp.jumlah ) +
                CASE
                    
                    WHEN pp.jasa IS NULL THEN
                    0 ELSE pp.jasa 
                END 
                    ) AS ttl
                FROM
                    pelayananpasien_t AS pp
                    JOIN antrianpasiendiperiksa_t AS apd ON apd.norec = pp.noregistrasifk
                    left join pasiendaftar_t as pd on pd.norec = apd.noregistrasifk
                    JOIN kelas_m AS kls ON kls.ID = apd.objectkelasfk
                    JOIN produk_m AS prd ON prd.ID = pp.produkfk
                    JOIN ruangan_m AS ru ON ru.ID = apd.objectruanganfk
                    LEFT JOIN strukresep_t AS sr ON sr.norec = pp.strukresepfk
                    LEFT JOIN pegawai_m AS pg ON pg.ID = sr.penulisresepfk 
                WHERE
                    pp.statusenabled = TRUE 
                    AND	pd.statusenabled = TRUE 
                    AND pd.tglregistrasi :: DATE BETWEEN '$tglAwal' 
                    AND '$tglAkhir' 
                GROUP BY pd.norec
                
                union all

                SELECT
                pd.norec,
                    SUM (
                    ( ( pp.hargasatuan - CASE WHEN pp.hargadiscount IS NULL THEN 0 ELSE pp.hargadiscount END ) * pp.jumlah ) +
                CASE
                    
                    WHEN pp.jasa IS NULL THEN
                    0 ELSE pp.jasa 
                END 
                    ) AS ttl
                FROM
                    pelayananpasienobatkronis_t AS pp
                    JOIN antrianpasiendiperiksa_t AS apd ON apd.norec = pp.noregistrasifk
                    left join pasiendaftar_t as pd on pd.norec = apd.noregistrasifk
                    JOIN kelas_m AS kls ON kls.ID = apd.objectkelasfk
                    JOIN produk_m AS prd ON prd.ID = pp.produkfk
                    JOIN ruangan_m AS ru ON ru.ID = apd.objectruanganfk
                    LEFT JOIN strukresep_t AS sr ON sr.norec = pp.strukresepfk
                    LEFT JOIN pegawai_m AS pg ON pg.ID = sr.penulisresepfk 
                WHERE
                    pp.harganetto > 0
                    and pp.jumlah > 0
                    AND	pd.statusenabled = TRUE 
                    AND pd.tglregistrasi :: DATE BETWEEN '$tglAwal' 
                    AND '$tglAkhir' 
                GROUP BY pd.norec
            ) as x
            group by norec"
            )
        );

        $kdProfile = (int)$this->kdProfile;
        $getResep = DB::table('pelayananpasien_t as pp')
        ->join('antrianpasiendiperiksa_t as apd', 'apd.norec', '=', 'pp.noregistrasifk')
        ->leftJOIN('strukresep_t as sr', 'sr.norec', '=', 'pp.strukresepfk')
        ->select(
            'pp.strukresepfk',
            'apd.noregistrasifk',
            DB::raw("
            case when pp.strukresepfk is null then 'Layanan' else 'Resep' end as jenis
           ")
        )
        ->where('pp.statusenabled', true)
        ->where('pp.kdprofile', $kdProfile)
        // ->where('apd.noregistrasifk', $r['norec_pd'])
        ->whereNotNull('pp.strukresepfk')
        ->groupBy('apd.noregistrasifk', 'pp.strukresepfk');
        if (isset($filter['tglAwal']) && $filter['tglAwal'] != "" && $filter['tglAwal'] != "undefined") {
            $getResep = $getResep->whereDate('pp.tglpelayanan', '>=', $filter['tglAwal']);
        }
        if (isset($filter['tglAkhir']) && $filter['tglAkhir'] != "" && $filter['tglAkhir'] != "undefined") {
            $tgl = $filter['tglAkhir']; //." 23:59:59";
            $getResep = $getResep->whereDate('pp.tglpelayanan', '<=', $tgl);
        }
        // $data->orderByDesc('');
        $getResep = $getResep->get();
        $getResep = $getResep->keyBy('noregistrasifk');
        // return $getResep;
        //var_dump($dataTarif16);


        $result = array();
        foreach ($data as $pasienD) {
            $status = "-";
            $statusclosing = "-";
            $totaltagihanfix = 0;
            // $totaltagihanfix = 0;

            // $dibayar = StrukBuktiPenerimaan::totalBayar($pasienD->noregistrasi);
            // $totaltagihanfix = $pasienD->totalharusdibayar + $pasienD->totaliurbayar - $dibayar;
            foreach ($dataTarif16 as $itm) {
                if ($itm->norec == $pasienD->norec_pd) {
                    $totaltagihanfix = (float)$itm->ttl;
                }
            }


            if ($pasienD->tglclosing != null) {
                $statusclosing = "Sudah Closing";
            } else{
                $statusclosing = "Belum Closing";
            }

            if($totaltagihanfix > 0) {
                if ($pasienD->nostruklastfk == null && $pasienD->nosbmlastfk == null) {
                    $status = "Belum Verifikasi";
                } elseif ($pasienD->nostruklastfk != null && $pasienD->nosbmlastfk == null) {
                    $status = "Verifikasi";
                } elseif ($pasienD->nostruklastfk != null && $pasienD->nosbmlastfk != null) {
                    $status = 'Sudah Dibayar'; //"Lunas";
                }
            }else {
                if($statusclosing == "Sudah Closing" && $pasienD->statusbayar == "Lunas" && $pasienD->isclosing == true) {
                    $status = "Sudah Dibayar";
                }else {
                    $status = "Belum Verifikasi";
                }
            }
            
            $hasResep = isset($getResep[$pasienD->norec_pd]) ? true : false;
            $kddiagnosa = $pasienD->kodingdiagnosa;
            if($pasienD->inacbg_grouper != null) {
                $cbggrouper = json_decode($pasienD->inacbg_grouper, true);
                if(json_last_error() === JSON_ERROR_NONE) {
                    $kddiagnosa = $cbggrouper['response']['cbg']['code'];
                }
            }
            $result[] = array(
                'tanggalMasuk' => $pasienD->tglregistrasi,
                'noantrianfarmasi' => $pasienD->jenis . '' . $pasienD->noantri,
                'noCm' => $pasienD->nocm,
                'nocmfk' => $pasienD->nocmfk,
                'noRegistrasi' => $pasienD->noregistrasi,
                'namaRuangan' => $pasienD->namaruangan,
                'namaPasien' => $pasienD->namapasien,
                'jenisAsuransi' => $pasienD->kelompokpasien,
                'tanggalPulang' => $pasienD->tglpulang,
                'tglmeninggal' => $pasienD->tglmeninggal,
                'norec_pd' => $pasienD->norec_pd,
                'status' => $status,
                'statusclosing' => $statusclosing,
                'deptid' => $pasienD->deptid,
                'namatext' => $pasienD->namatext,
                'totaltagihanfix' => $totaltagihanfix,
                'tglclosing' => $pasienD->tglclosing,
                'nosuratkematian' => $pasienD->nosuratkematian,
                'kelasid' => $pasienD->objectkelasfk,
                'objectstatuspiutangfk' => $pasienD->objectstatuspiutangfk,
                'statuspiutang' => $pasienD->statuspiutang,
                'ruanganid' => $pasienD->objectruanganlastfk,
                'statuspasien' => $pasienD->statuspasien,
                'kebangsaan' => $pasienD->kebangsaan,
                'nostruklastfk' => $pasienD->nostruklastfk,
                'tgllahir' => $pasienD->tgllahir,
                'namarekanan' => $pasienD->namarekanan,
                'objectkelompokpasienlastfk' => $pasienD->objectkelompokpasienlastfk,
                'nosep' => $pasienD->nosep,
                'norec_pa' => $pasienD->norec_pa,
                'norec_sbm' => $pasienD->norec_sbm,
                // 'norec_sbmcr' => $pasienD->norec_sbmcr,
                'norec_sp' => $pasienD->norec_sp,
                'nosbm' => $pasienD->nosbm,
                'iddiagnosabpjs' => $pasienD->iddiagnosabpjs,
                'dokter' => $pasienD->dokter,
                'isresep' => $hasResep,
                'jkText' => $pasienD->jktext,
                'closer' => $pasienD->closer,
                'kodingdiagnosa' => $kddiagnosa,
                // 'pd.inacbg_grouper' =>
            );
        }

        $total = count($result);

        $results = [
            'total' => $total,
            'data' => $result,
            'listkelompok' => $this->getKelompok()
        ];
        return $this->respond($results);
    }

    protected function getKelompok()
    {
        return DB::table('kelompokpasien_m as kp')
            ->select('id', 'reportdisplay as nama')
            ->get();
    }

    protected function getProdukIdDeposit()
    {
        $set = SettingDataFixed::where('namafield', 'idProdukDeposit')->first();
        $this->id = ($set) ? (int) $set->nilaifield : null;
        return $this->id;
    }

    public function verifikasiTagihan(Request $request)
    {
        $norec_pd = $request['norec_pd'];
        $kdProfile = $this->kdProfile;
        $idProfile = (int) $kdProfile;
        $pelayanan = DB::select(
            DB::raw("select pd.objectruanganlastfk,pd.nostruklastfk,ps.id as psid,ps.nocm,
            ps.namapasien,pd.tglpulang,kps.kelompokpasien,kl.namakelas,
            pd.objectruanganlastfk,ru.objectdepartemenfk,
            pd.noregistrasi,pp.*,ps.email ,ps.nohp,pd.norec as norec_pd
            from pasiendaftar_t pd
            left JOIN antrianpasiendiperiksa_t apd on apd.noregistrasifk=pd.norec
            left JOIN pelayananpasien_t pp on pp.noregistrasifk=apd.norec
            left JOIN pasien_m ps on ps.id=pd.nocmfk
            left JOIN kelas_m kl on kl.id=pd.objectkelasfk
            left JOIN kelompokpasien_m kps on kps.id=pd.objectkelompokpasienlastfk
            left JOIN ruangan_m ru on ru.id=pd.objectruanganlastfk
            where pd.kdprofile = :kdprofile and pd.norec=:norec and pp.strukfk is null;"),
            array(
                'norec' => $norec_pd,
                'kdprofile' => $idProfile,
            )
        );

        $pelayanantidakterklaim = DB::select(
            DB::raw("select pd.objectruanganlastfk,pd.nostruklastfk,ps.id as psid,ps.nocm,
            ps.namapasien,pd.tglpulang,kps.kelompokpasien,kl.namakelas,
            pd.objectruanganlastfk,ru.objectdepartemenfk,
            pd.noregistrasi,pp.* from pasiendaftar_t pd
            left JOIN antrianpasiendiperiksa_t apd on apd.noregistrasifk=pd.norec
            left JOIN pelayananpasientidakterklaim_t pp on pp.noregistrasifk=apd.norec
            left JOIN pasien_m ps on ps.id=pd.nocmfk
            left JOIN kelas_m kl on kl.id=pd.objectkelasfk
            left JOIN kelompokpasien_m kps on kps.id=pd.objectkelompokpasienlastfk
            left JOIN ruangan_m ru on ru.id=pd.objectruanganlastfk
            where pd.kdprofile = :kdprofile and pd.norec=:norec and pp.strukfk is null;"),
            array(
                'norec' => $norec_pd,
                'kdprofile' => $idProfile,
            )
        );

        $totalBilling = 0;
        $totalKlaim = 0;
        $totalDeposit = 0;
        $totaltakterklaim = 0;

        foreach ($pelayanantidakterklaim as $values) {
            $totaltakterklaim = $totaltakterklaim + (($values->hargajual - $values->hargadiscount) * $values->jumlah) + $values->jasa;
        }

        foreach ($pelayanan as $value) {
            if ($value->produkfk == $this->getProdukIdDeposit()) {
                $totalDeposit = $totalDeposit + $value->hargajual;
            } else {

                $value->jasa = (float) $value->jasa;
                $value->jumlah = (float) $value->jumlah;
                $value->hargadiscount = (float) $value->hargadiscount;
                $value->hargajual = (float) $value->hargajual;
                $totalBilling = (($value->hargajual - $value->hargadiscount) * $value->jumlah) + $value->jasa;
            }
        }

        $totalBilling = $totalBilling;
        $pelayanan = $pelayanan[0];
        $isRawatInap = false;
        if ($pelayanan->objectruanganlastfk != null) {
            if ((int) $pelayanan->objectdepartemenfk == 16) {
                $isRawatInap = true;
            }
        }

        $totalDeposit = $totalDeposit;
        $totalKlaim = 0;

        $nohp = $pelayanan->nohp;
        if ($nohp == null || strlen($nohp) <= 8) {
            $nohp = '00000000000';
        }
        $result = array(
            'pasienID' => $pelayanan->psid,
            'noCm' => $pelayanan->nocm,
            'noRegistrasi' => $pelayanan->noregistrasi,
            'namaPasien' => $pelayanan->namapasien,
            'tglPulang' => $pelayanan->tglpulang,
            'jenisPasien' => $pelayanan->kelompokpasien,
            'kelasRawat' => $pelayanan->namakelas,
            'noAsuransi' => '-',
            'kelasPenjamin' => '-',
            'billing' => $totalBilling,
            'penjamin' => '', //$penjamin=$this->getPenjamin($pelayanan)->namarekanan,
            'deposit' => $totalDeposit,
            'totalKlaim' => $totalKlaim,
            'jumlahBayar' => $totalBilling - $totalDeposit - $totalKlaim,
            'jumlahBayarNew' => $totalBilling - $totalDeposit - $totalKlaim - $totaltakterklaim, //jumlah bayar dengan tindakan yang tidak d klaim
            'jumlahPiutang' => 0,
            'needDokument' => true,
            'dokuments' => [],
            'totaltakterklaim' => $totaltakterklaim,
            'isRawatInap' => $isRawatInap,
            'email' => $pelayanan->email != null ? $pelayanan->email : '',
            'nohp' => $nohp,
            'norec_pd' => $pelayanan->norec_pd,

        );
        return $this->respond($result);
    }

    // Detail Tagihan Verifikasi

    public function detailTagihanVerifikasi(Request $request)
    {
        $norec_pd = $request['norec_pd'];
        $dataRuangan = DB::table('pasiendaftar_t as pd')
            ->leftjoin('ruangan_m as ru', 'ru.id', '=', 'pd.objectruanganlastfk')
            ->select('ru.namaruangan as namaruangan')
            ->where('pd.norec', $norec_pd)
            ->first();
        $pelayanan = [];
        $pelayanan = DB::table('pasiendaftar_t as pd')
            ->leftjoin('antrianpasiendiperiksa_t as apd', 'apd.noregistrasifk', '=', 'pd.norec')
            ->leftjoin('pelayananpasien_t as pp', 'pp.noregistrasifk', '=', 'apd.norec')
            ->leftjoin('produk_m as pr', 'pr.id', '=', 'pp.produkfk')
            ->leftjoin('kelas_m as kl', 'kl.id', '=', 'apd.objectkelasfk')
            ->join('ruangan_m as ru', 'ru.id', '=', 'apd.objectruanganfk')
            ->leftjoin('strukpelayanan_t as sp', 'sp.norec', '=', 'pp.strukfk')
            ->leftjoin('strukbuktipenerimaan_t as sbm', 'sp.nosbmlastfk', '=', 'sbm.norec')

            ->select(
                'pp.norec',
                'pp.tglpelayanan',
                'pp.rke',
                'pr.id as prid',
                'pr.namaproduk',
                'pp.jumlah',
                'kl.id as klid',
                'kl.namakelas',
                'ru.id as ruid',
                'ru.namaruangan',
                'pp.produkfk',
                'pp.hargajual',
                'pp.hargadiscount',
                'sp.nostruk',
                'sp.tglstruk',
                'apd.norec as norec_apd',
                'sbm.nosbm',
                'sp.norec as norec_sp',
                'pp.jasa',
                'pd.nocmfk',
                'pd.nostruklastfk',
                'pd.noregistrasi',
                'pd.tglregistrasi',
                'pd.norec as norec_pd',
                'pd.tglpulang',
                'pd.objectrekananfk as rekananid',
                'pp.jasa',
                'sp.totalharusdibayar',
                'sp.totalprekanan',
                'sp.totalbiayatambahan',
                'pd.kdprofile',
                'pp.aturanpakai',
                'pp.iscito',
                'pd.statuspasien',
                'pp.isparamedis'
            )

            ->where('pd.norec', $norec_pd)
            ->orderBy('pp.tglpelayanan');

        $pelayanan = $pelayanan->get();


        if (count($pelayanan) > 0) {

            $totalBilling = 0;
            $norecAPD = '';
            $norecSP = '';
            $details = array();
            $dibayar = 0;
            $diverif = 0;
            foreach ($pelayanan as $value) {
                if ($value->produkfk == $this->getProdukIdDeposit()) {
                    continue;
                }
                if ($value->namaproduk == null) {
                    continue;
                }
                $jasa = 0;
                if (isset($value->jasa) && $value->jasa != "" && $value->jasa != "undefined") {
                    $jasa = $value->jasa;
                }
                $kmpn = [];

                $harga = (float) $value->hargajual;
                $diskon = (float) $value->hargadiscount;
                $detail = array(
                    'norec' => $value->norec,
                    'tglPelayanan' => $value->tglpelayanan,
                    'namaPelayanan' => $value->namaproduk,
                    //                    'dokter' => $NamaDokter,
                    'jumlah' => $value->jumlah,
                    'kelasTindakan' => @$value->namakelas,
                    'ruanganTindakan' => @$value->namaruangan,
                    'harga' => $harga,
                    'diskon' => $diskon,
                    'total' => (($harga - $diskon) * $value->jumlah) + $jasa,
                    'jppid' => '',
                    'jenispetugaspe' => '',
                    'strukfk' => $value->nostruk . ' / ' . $value->nosbm,
                    'sbmfk' => $value->nosbm,
                    'pgid' => '',
                    'ruid' => $value->ruid,
                    'prid' => $value->prid,
                    'klid' => $value->klid,
                    'norec_apd' => $value->norec_apd,
                    'norec_pd' => $value->norec_pd,
                    'norec_sp' => $value->norec_sp,
                    'komponen' => $kmpn,
                    'jasa' => $jasa,
                    'aturanpakai' => $value->aturanpakai,
                    'iscito' => $value->iscito,
                    'isparamedis' => $value->isparamedis
                );
                $details[] = $detail;
            }
        }

        $arrHsil = array(
            'details' => $details
        );
        return $this->respond($arrHsil);
    }

    protected function getKelompokPasienPerjanjian()
    {
        $set = SettingDataFixed::where('namafield', 'idJenisPasienPerjanjian')->first();
        $this->id = ($set) ? (int) $set->nilaifield : null;
        return $this->id;
    }

    public function checkPasienActive(Request $r)
    {
        $data = DB::table('pasiendaftar_t as pd')
        ->select('pd.noregistrasi')
        ->leftJoin('pasien_m as ps', 'ps.id', 'pd.nocmfk')
        ->where('ps.id', $r['nocmfk'])
        ->where('pd.statusenabled', true)
        ->whereNull('pd.tglclosing')
        ->whereNull('pd.isclosing')
        ->first();

        $result = [
            "status" => 200,
            "message" => "",
            "data" => []
        ];

        if(isset($data)) {
            $result = [
                "status" => 400,
                "message" => "Pasien mempunyai Nomor Registrasi yang aktif ( ".$data->noregistrasi." ).",
                "data" => []
            ];
        }
        return $this->respond($result, $result['status'], $result['message']);
    }


}