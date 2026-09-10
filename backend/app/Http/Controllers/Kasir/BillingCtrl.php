<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use App\Http\Controllers\EMR\TindakanCtrl;
use App\Http\Controllers\Farmasi\InputResepCtrl;
use App\Models\Master\Kelas;
use App\Models\Master\KelompokPasien;
use App\Models\Master\Pegawai;
use App\Models\Transaksi\AntrianPasienDiperiksa;
use App\Models\Transaksi\PasienDaftar;
use App\Models\Transaksi\PelayananPasien;
use App\Models\Transaksi\PelayananPasienDetail;
use App\Models\Transaksi\PelayananPasienPetugas;
use App\Models\Transaksi\StrukBuktiPenerimaan;
use App\Models\Transaksi\StrukBuktiPengeluaran;
use App\Models\Transaksi\StrukPelayananPenjamin;
use App\Models\Transaksi\StrukPelayananPenjaminDetail;

use App\Models\Transaksi\StrukPelayanan;
use App\Models\Transaksi\BPDCheckout;
use App\Models\Transaksi\BPDLog;
//use App\Services\BridgingBPDService;
use App\Traits\Valet;
use Dompdf\Options;
use Dompdf\Dompdf;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\InvoiceRanap;
use App\Exports\InvoiceCOB;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Endroid\QrCode\QrCode as Png;

class BillingCtrl extends Controller
{
    use Valet;
    protected $tindakanCtrl;
    protected $inputResepCtrl;
    protected $bpdService;
    public function __construct(TindakanCtrl $tindakanCtrl, InputResepCtrl $inputResepCtrl)
    //, BridgingBPDService $bpdService)
    {
        parent::__construct($is_encrypt = true);
        $this->tindakanCtrl = $tindakanCtrl;
        $this->inputResepCtrl = $inputResepCtrl;
        //$this->bpdService = $bpdService;

    }

    public function billingPasien(Request $r)
    {
        $kdProfile = (int) $this->kdProfile;
        $sDokterPemeriksa = $this->settingFix('jenisPetugasDokterPemeriksa');
        $pd = PasienDaftar::where('kdprofile', $kdProfile)
            ->where('norec', $r['norec_pd'])
            ->first();

        if (empty($pd)) {
            $result['as'] = '@epic';
            return $this->respond($result);
        }
        $data = DB::table('pelayananpasien_t as pp')
            ->join('antrianpasiendiperiksa_t as apd', 'apd.norec', '=', 'pp.noregistrasifk')
            ->join('kelas_m as kls', 'kls.id', '=', 'apd.objectkelasfk')
            ->join('produk_m as prd', 'prd.id', '=', 'pp.produkfk')
            ->join('ruangan_m as ru', 'ru.id', '=', 'apd.objectruanganfk')
            ->leftJOIN('strukpelayanan_t as sp', 'sp.norec', '=', 'pp.strukfk')
            ->leftJOIN('strukresep_t as sr', 'sr.norec', '=', 'pp.strukresepfk')
            ->leftjoin('ruangan_m as ru1', 'ru1.id', '=', 'sr.ruanganfk')
            ->leftJOIN('pegawai_m as pg', 'pg.id', '=', 'sr.penulisresepfk')
            ->select(
                'pp.norec',
                'pp.objecthargaprodukfk as id_hnp',
                'prd.namaproduk',
                'prd.id as idjasaproduk',
                'kls.namakelas',
                'pp.tglpelayanan',
                'ru.id as idruangannya',
                'ru.namaruangan',
                'pp.strukresepfk',
                'pp.jumlah',
                'ru1.namaruangan as ruangan',
                'pp.hargasatuan',
                'pp.hargasebelumcito',
                'pg.namalengkap as penulisresep',
                'apd.norec as norec_apd',
                'pp.keteranganlain',
                'pp.strukfk',
                'kls.id as objectkelasfk',
                'apd.tglregistrasi as tanggalregistrasi',
                'pp.iscito',

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
            ->where('pp.kdprofile', $kdProfile)
            ->where('apd.noregistrasifk', $r['norec_pd']);

        if (isset($r['strukfk']) && $r['strukfk'] != '' && $r['strukfk'] == null) {
            $data = $data->whereNull('pp.strukfk');
        }
        if (isset($r['istindakan']) && $r['istindakan'] != '' && $r['istindakan'] == 'true') {
            $data = $data->whereNull('pp.strukresepfk');
        }
        if (isset($r['tindakanOperasi']) && $r['tindakanOperasi'] != '') {
            // $data = $data->where('apd.objectruanganfk', $this->settingFix('idRuanganBedah'));
            $data = $data->where('pp.isbedah', true);
        } else {
            if (isset($r['ruanganid']) && $r['ruanganid'] != '') {
                $data = $data->where('apd.objectruanganfk', $r['ruanganid']);
            }
            if (isset($r['riwayat']) && $r['riwayat'] != '') {
                $data = $data->where('pp.isbedah', null); // Filter khusus tindakan non bedah, untuk menghindari pergantian PPP
            }
        }
        $data = $data->orderByDesc('pp.tglpelayanan');
        $data = $data->get();

        if (isset($r['tindakanOperasi']) && $r['tindakanOperasi'] != '') {
            $pelayananpetugas = DB::table('pelayananpasien_t as pp')
                ->join('antrianpasiendiperiksa_t as apd', 'apd.norec', '=', 'pp.noregistrasifk')
                ->join('pasiendaftar_t as pd', 'pd.norec', '=', 'apd.noregistrasifk')
                ->leftjoin('pelayananpasienpetugas_t as ppp', 'ppp.pelayananpasien', '=', 'pp.norec')
                ->leftjoin('jenispetugaspelaksana_m as jpp', 'jpp.id', '=', 'ppp.objectjenispetugaspefk')
                ->leftjoin('pegawai_m as pg', 'pg.id', '=', db::raw('COALESCE(apd.objectpegawaifk, pd.objectpegawaifk)'))
                ->leftjoin('pegawai_m as pg1', 'pg1.id', '=', db::raw('COALESCE(pp.pelayananpegawaifk,ppp.objectpegawaifk)'))
                ->select(
                    'pp.norec as pelayananpasien',
                    'pp.objecthargaprodukfk as id_hnp',
                    'pg.namalengkap',
                    'pg.id as iddokterpemeriksa',
                    'pg1.namalengkap as pemeriksa',
                    'pg1.id as idpemeriksa',
                    'ppp.norec as norec_ppp',
                    'ppp.objectjenispetugaspefk as idjenispelaksana',
                    'jpp.jenispetugaspe as jenispelaksana',

                    db::raw("case when pp.isasa0 = true then '0'
                    WHEN PP.isasa1 = 1 then '1'
                    when pp.isasa2 = 1 then '2'
                    when pp.isasa3 = 1 then '3'
                    WHEN pp.isasa4 = true then '4'
                    else null end as asa")
                )
                ->where('pp.kdprofile', $kdProfile)
                ->where('pp.noregistrasi', $pd->noregistrasi)
                ->get();
        } else {
            $pelayananpetugas = DB::table('pelayananpasien_t as pp')
                ->join('antrianpasiendiperiksa_t as apd', 'apd.norec', '=', 'pp.noregistrasifk')
                ->join('pasiendaftar_t as pd', 'pd.norec', '=', 'apd.noregistrasifk')
                ->leftjoin('pelayananpasienpetugas_t as ppp', 'ppp.pelayananpasien', '=', 'pp.norec')
                ->leftjoin('jenispetugaspelaksana_m as jpp', 'jpp.id', '=', 'ppp.objectjenispetugaspefk')
                ->leftjoin('pegawai_m as pg', 'pg.id', '=', db::raw('COALESCE(apd.objectpegawaifk, pd.objectpegawaifk)'))
                ->leftjoin('pegawai_m as pg1', 'pg1.id', '=', db::raw('COALESCE(pp.pelayananpegawaifk,ppp.objectpegawaifk)'))
                ->select(
                    'pp.norec as pelayananpasien',
                    'pg.namalengkap',
                    'pg.id as iddokterpemeriksa',
                    'pg1.namalengkap as pemeriksa',
                    'pg1.id as idpemeriksa',
                    'ppp.objectjenispetugaspefk as idjenispelaksana',
                    'jpp.jenispetugaspe as jenispelaksana'
                )
                ->where('pp.kdprofile', $kdProfile)
                ->where('pp.noregistrasi', $pd->noregistrasi)
                ->get();
        }

        $result['total'] = 0;
        $result['deposit'] = 0;
        $result['diskon'] = 0;
        $result['dibayar'] = 0;
        $result['sisa'] = 0;

        $sama = false;
        $group = [];
        foreach ($data as $item) {
            $item->dokterpemeriksa = $item->strukresepfk != null ? $item->penulisresep : '-';
            $item->pemeriksa = null;
            $item->objectpemeriksa = null;
            $item->iddokterpemeriksa = null;
            $item->idjenispelaksana = null;
            $item->jenispelaksana = null;
            $item->listpetugas = [];
            $item->asa = null;

            $item->checked = false;
            $result['total'] = $result['total'] + (float) $item->total;
            $result['diskon'] = $result['diskon'] + (float) $item->hargadiscount;
            foreach ($pelayananpetugas as $index => $itemd) {
                if ($itemd->pelayananpasien == $item->norec) {
                    if (isset($r['tindakanOperasi']) && $r['tindakanOperasi'] != '') {
                        $item->listpetugas[] = ["value" => $itemd->idpemeriksa, "label" => $itemd->pemeriksa, "idjenispelaksana" => $itemd->idjenispelaksana, "norec_ppp" => $itemd->norec_ppp];
                        $item->asa = $itemd->asa;
                    } else {
                        $item->dokterpemeriksa = $itemd->namalengkap;
                        $item->iddokterpemeriksa = $itemd->iddokterpemeriksa;
                        $item->pemeriksa = $itemd->pemeriksa;
                        $item->objectpemeriksa = $itemd->idpemeriksa;
                        $item->idjenispelaksana = $itemd->idjenispelaksana;
                        $item->jenispelaksana = $itemd->jenispelaksana;
                    }
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

        $result['tarif_inacbg'] = $pd->inacbg_totalgrouper;
        $result['klaim'] = StrukPelayanan::totalKlaim($pd->noregistrasi);
        $result['deposit'] = StrukBuktiPenerimaan::deposit($pd->noregistrasi);
        $result['dibayar'] = StrukBuktiPenerimaan::totalBayar($pd->noregistrasi);
        $result['iurbayar'] = StrukBuktiPenerimaan::totalBayarIUR($pd->noregistrasi);
        $result['pengembalian'] = StrukBuktiPengeluaran::totalPengembalian($pd->noregistrasi);
        $result['sisa'] = $result['total'] - $result['dibayar'] - $result['deposit'] - $result['klaim'] + $result['pengembalian']; // -  $result['diskon'];
        $result['length'] = count($data);
        $result['detail'] = $group; //collect($data)->groupBy('tglpelayanan_group')->sortByDesc('tglpelayanan_group');
        $result['list_ruangan'] = AntrianPasienDiperiksa::listRuangan($pd->noregistrasi);
        $result['as'] = '@epic';

        return $this->respond($result);
    }

    public function billingPasienKoding(Request $r)
    {
        $kdProfile = (int)$this->kdProfile;
        $sDokterPemeriksa = $this->settingFix('jenisPetugasDokterPemeriksa');
        $pd = PasienDaftar::where('kdprofile', $kdProfile)
            ->where('norec', $r['norec_pd'])
            ->first();

        if (empty($pd)) {
            $result['as'] = '@epic';
            return $this->respond($result);
        }
        $data = DB::table('pelayananpasien_t as pp')
            ->join('antrianpasiendiperiksa_t as apd', 'apd.norec', '=', 'pp.noregistrasifk')
            ->join('kelas_m as kls', 'kls.id', '=', 'apd.objectkelasfk')
            ->join('produk_m as prd', 'prd.id', '=', 'pp.produkfk')
            ->join('ruangan_m as ru', 'ru.id', '=', 'apd.objectruanganfk')
            ->leftJOIN('strukpelayanan_t as sp', 'sp.norec', '=', 'pp.strukfk')
            ->leftJOIN('strukresep_t as sr', 'sr.norec', '=', 'pp.strukresepfk')
            ->leftjoin('ruangan_m as ru1', 'ru1.id', '=', 'sr.ruanganfk')
            ->leftJOIN('pegawai_m as pg', 'pg.id', '=', 'sr.penulisresepfk')
            ->select(
                'prd.namaproduk',
                'prd.id'
            )
            ->where('pp.statusenabled', true)
            ->where('pp.kdprofile', $kdProfile)
            ->where('apd.noregistrasifk', $r['norec_pd']);

        if (isset($r['strukfk']) && $r['strukfk'] != '' && $r['strukfk'] == null) {
            $data = $data->whereNull('pp.strukfk');
        }
        if (isset($r['istindakan']) && $r['istindakan'] != '' && $r['istindakan'] == 'true') {
            $data = $data->whereNull('pp.strukresepfk');
        }
        if (isset($r['tindakanOperasi']) && $r['tindakanOperasi'] != '') {
            $data = $data->where('apd.objectruanganfk', $this->settingFix('idRuanganBedah'));
        } else {
            if(isset($r['ruanganid']) && $r['ruanganid'] != '') {
                $data = $data->where('apd.objectruanganfk', $r['ruanganid']);
            }    
        }
        $data = $data->groupBy(
            'prd.namaproduk',
            'prd.id'
        );
        $data = $data->orderByDesc('prd.namaproduk');
        $data = $data->get();

        return $this->respond($data);
    }

    public function cetakBillingCasemix(Request $r)
    {

        $profile = $this->profile();
        $print = false;
        $pageWidth = '100%';
        $res['user'] = $r['user'];
        $res['noregistrasi'] = $r['noregistrasi'];
        $res['identitas'] =  DB::table('pasiendaftar_t as pd')
            ->join('pasien_m as ps', 'ps.id', '=', 'pd.nocmfk')
            ->join('ruangan_m as ru', 'ru.id', '=', 'pd.objectruanganlastfk')
            ->join('kelas_m as kl', 'kl.id', '=', 'pd.objectkelasfk')
            ->join('departemen_m as dp', 'dp.id', '=', 'ru.objectdepartemenfk')
            ->join('kelompokpasien_m as kp', 'kp.id', '=', 'pd.objectkelompokpasienlastfk')
            ->leftjoin('jeniskelamin_m as jk', 'jk.id', '=', 'ps.objectjeniskelaminfk')
            ->leftjoin('pegawai_m as pg', 'pg.id', '=', 'pd.objectpegawaifk')
            ->leftjoin('rekanan_m as rk', 'rk.id', '=', 'pd.objectrekananfk')
            ->leftjoin('pemakaianasuransi_t as pa', 'pa.noregistrasifk', '=', 'pd.norec')
            ->leftJoin('antrianpasiendiperiksa_t as apd', 'apd.norec', '=', 'pd.noregistrasifk')
            ->leftjoin('kamar_m as km', 'km.id', '=', 'apd.objectkamarfk')
            ->leftjoin('alamat_m as alm', 'alm.nocmfk', '=', 'ps.id')
            ->select(
                'pd.noregistrasi',
                'pd.tglregistrasi',
                'pd.tglpulang',
                'pd.tglclosing',
                'ps.namapasien',
                'ps.nocm',
                'ru.namaruangan',
                'kl.namakelas',
                'kp.kelompokpasien',
                'dp.namadepartemen',
                'jk.jeniskelamin',
                'pg.namalengkap',
                'rk.namarekanan',
                'km.namakamar',
                'alm.alamatlengkap',
                'ps.nobpjs',
                DB::raw("TO_CHAR(age(ps.tgllahir), 'YY thn MM Bulan DD hr') AS umur"),
                'pa.nosep'
            )
            ->where('pd.statusenabled', true)
            ->where('pd.kdprofile', $this->kdProfile)
            ->where('pd.noregistrasi', $r['noregistrasi'])
            ->first();

        $data =  DB::table('pelayananpasien_t as pp')
            ->join('antrianpasiendiperiksa_t as apd', 'apd.norec', '=', 'pp.noregistrasifk')
            ->join('pasiendaftar_t as pd', 'pd.norec', '=', 'apd.noregistrasifk')
            ->join('kelas_m as kls', 'kls.id', '=', 'apd.objectkelasfk')
            ->join('produk_m as prd', 'prd.id', '=', 'pp.produkfk')
            ->join('ruangan_m as ru', 'ru.id', '=', 'apd.objectruanganfk')
            ->join('departemen_m as dp', 'dp.id', '=', 'ru.objectdepartemenfk')
            ->leftJOIN('strukresep_t as sr', 'sr.norec', '=', 'pp.strukresepfk')
            ->leftJOIN('ruangan_m as ru2', 'ru2.id', '=', 'sr.ruanganfk')
            ->leftJOIN('departemen_m as dp2', 'dp2.id', '=', 'ru2.objectdepartemenfk')
            ->leftJOIN('pegawai_m as pg', 'pg.id', '=', 'sr.penulisresepfk')
            ->leftJOIN('detailjenisproduk_m as djp', 'djp.id', '=', 'prd.objectdetailjenisprodukfk')
            ->leftJOIN('jenisproduk_m as jp', 'jp.id', '=', 'djp.objectjenisprodukfk')
            ->leftjoin('strukpelayanan_t AS sp', 'sp.norec', '=', 'pp.strukfk')
            ->leftjoin('strukbuktipenerimaan_t AS sbm', 'sp.nosbmlastfk', '=', 'sbm.norec')
            ->leftJoin('strukorder_t as so', 'so.norec', 'apd.objectstrukorderfk')
            ->leftJoin('ruangan_m as ru3', 'ru3.id', 'so.objectruanganfk')
            ->select(
                'pp.norec',
                'prd.namaproduk',
                'kls.namakelas',
                'pp.tglpelayanan',
                'ru2.namaruangan as ruanganfarmasi',
                'ru.namaruangan as ruanganinput',
                'pp.strukresepfk',
                'pp.jumlah',
                'sbm.nosbm',
                'pp.hargasatuan',
                'pg.namalengkap as penulisresep',
                'jp.jenisproduk',
                'dp.namadepartemen',
                'dp2.namadepartemen as deparemenfarmasi',
                
                DB::raw("
                    case when pp.jasa is not null then pp.jasa else 0 end jasa,
                    COALESCE (pp.hargadiscount, 0)  as diskon,
                    ( (COALESCE(pp.hargasatuan,0)  - COALESCE (pp.hargadiscount, 0))   * pp.jumlah)
                    + ( COALESCE (pp.jasa, 0)) as total,
                    to_char(pp.tglpelayanan,'yyyy-MM-dd')  as tglpelayanan_group ,
                    case when pp.strukresepfk is null then 'Layanan' else 'Resep' end as jenis,
                    case when pp.strukresepfk is null then ru.namaruangan else ru2.namaruangan end as ruang_group ,
                    case
                        when dp.id = 9 then 'UNIT: IGD'
                        when dp.id = 45 then 'UNIT: IBSA'
                        when dp.id in (3, 27) then 
                            case
                                when so.objectruanganfk is not null then 
                                    case
                                        when ru3.objectdepartemenfk = 9 then 'UNIT: IGD'
                                        when ru3.objectdepartemenfk = 45 then 'UNIT: IBSA'
                                        else 'UNIT: Rawat Inap ' || ru3.namaruangan
                                    end
                            end
                        else 'UNIT: Rawat Inap ' || ru.namaruangan
                    end as namaruangan,
                    case
                        when prd.objectdetailjenisprodukfk in(3032, 3060, 3007, 3011) then djp.detailjenisproduk
                        when pp.isobat = true then 'Obat : ' || ru2.namaruangan
                        when dp.id = 27 then 'Radiologi'
                        when dp.id = 3 then 'Laboratorium'
                        else 'Tindakan'
                    end as layanan_group,

                    case when pp.strukresepfk is null then dp.namadepartemen else dp2.namadepartemen end as departemen_group
                ")
            )
            // when dp.id = 27 then 'Radiologi'
            // when dp.id = 3 then 'Laboratorium'
            ->where('pp.statusenabled', true)
            ->where('pp.kdprofile', $this->kdProfile)
            ->where('pd.noregistrasi', $r['noregistrasi'])
            ->where('pp.jumlah', '>', 0)
            ->orderBy('pp.tglpelayanan', 'asc')
            ->distinct()
            ->get();
        // return $data;

        $sDokterPemeriksa = $this->settingFix('jenisPetugasDokterPemeriksa');
        $pelayananpetugas = DB::table('pelayananpasienpetugas_t as ptu')
            ->leftjoin('pegawai_m as pg', 'pg.id', '=', 'ptu.objectpegawaifk')
            ->leftjoin('pelayananpasien_t as pp', 'pp.norec', '=', 'ptu.pelayananpasien')
            ->leftJoin('pegawai_m as pg2', DB::raw('CAST(pp.pelayananpegawaifk AS INT)'), '=', 'pg2.id')
            ->select('ptu.pelayananpasien', 'pg.namalengkap', 'pg2.namalengkap as pemeriksa', 'pg2.id as idpemeriksa')
            ->where('ptu.kdprofile', $this->kdProfile)
            // ->where('ptu.objectjenispetugaspefk', $sDokterPemeriksa)
            ->where('ptu.noregistrasi', $r['noregistrasi'])
            ->get();
        $res['total'] = 0;
        foreach ($data as $item) {
            $item->dokter = $item->strukresepfk != null ? $item->penulisresep : '-';
            $item->pemeriksa = null;
            $item->idpemeriksa = null;
            $res['total']  = (float) $res['total']  + (float) $item->total;
            foreach ($pelayananpetugas as $itemd) {
                if ($itemd->pelayananpasien == $item->norec) {
                    $item->dokter = $itemd->namalengkap;
                    $item->idpemeriksa = $itemd->idpemeriksa;
                    $item->pemeriksa = $itemd->pemeriksa;
                }
            }
        }
        // return $data;
        $res['total'] = round($res['total']);
        $res['billing'] =  $data->groupBy('namaruangan');
        $multi = DB::table('strukpelayananpenjamindetail_t as sppd')
        ->select('sppd.totalppenjamin', 'sppd.totalharusdibayar', 'rk.namarekanan')
        ->join('strukpelayananpenjamin_t as spp', 'sppd.strukpelayananpenjaminfk', 'spp.norec')
        ->join('pasiendaftar_t as pd', 'pd.nostruklastfk', 'spp.nostrukfk')
        ->leftjoin('rekanan_m as rk', 'rk.id', 'sppd.kdrekananpenjamin')
        ->where('pd.noregistrasi', $r['noregistrasi'])
        ->where('sppd.statusenabled', true)
        ->where('sppd.keteranganlainnya', 'Multi Penjamin')
        ->get();
        $res['ismultipenjamin'] = count($multi) > 0 ? true : false;
        $res['multipenjamin'] = $multi;
        $res['klaim']  = StrukPelayanan::totalKlaim($r['noregistrasi']);
        $res['deposit'] = StrukBuktiPenerimaan::deposit($r['noregistrasi']);
        $res['dibayar'] = StrukBuktiPenerimaan::totalBayar($r['noregistrasi']);
        $res['iurbayar'] = StrukBuktiPenerimaan::totalBayarIUR($r['noregistrasi']);
        $res['pengembalian'] = StrukBuktiPengeluaran::totalPengembalian($r['noregistrasi']);
        $res['sisa'] =   round($res['total'])  -  $res['dibayar'] - $res['deposit'] - $res['klaim'] + $res['pengembalian'];
        $res['pdf']  = $r['pdf'];

        //dd($res['billing']);
        // foreach($res['billing'] as $ruangan) {
        //     return $ruangan->groupBy('jenistindakan');
        //     // foreach($ruangan->groupBy('jenisproduk') as $item) {
        //     //     return $
        //     // } 
        // }

        $blade = 'report.kasir.billing-casemix';
        $user = $this->getPegawai();

        if (isset($r['rekap']) && $r['rekap'] == true) {
            $res['billing'] =  $data->groupBy('departemen_group');
            $blade = 'report.kasir.rekap-billing';
        }
        if ($res['pdf'] == 'true') {
            $pdf = App::make('dompdf.wrapper');
            $pdf->loadView(
                $blade,
                array(
                    'profile' => $profile,
                    'pageWidth' => $pageWidth,
                    'print' => $print,
                    'res' => $res,
                    'data' => $data,
                    'user' => $user,
                )
            );
            $pdf->setPaper('a4', 'potrait');
            return $pdf->stream();
        }
        if (isset($r['storage'])) {
            $res['storage']  = true;
            $pdf = App::make('dompdf.wrapper');
            $pdf->loadView(
                $blade,
                array(
                    'profile' => $profile,
                    'pageWidth' => $pageWidth,
                    'print' => $print,
                    'res' => $res,
                    'data' => $data,
                    'user' => $user,
                )
            );
            $pdf->setPaper('a4', 'potrait');
            return $pdf;
        }
        if(isset($r['isexcel']) && $r['isexcel'] == 'true') {
            $headers = array(
                'Content-Type: application/xlsx',
            );

            $file = Excel::download(new InvoiceCOB($profile,$pageWidth,$print,$res,$data,$user), date('YmdHis').'-invoice-pasien-cob-'.$r['noregistrasi'].'.xlsx');
            return $file;
        }

        return view(
            $blade,
            compact('profile', 'pageWidth', 'print', 'res', 'data', 'user')
        );
    }

    public function cetakBillingCasemixKlaim(Request $r)
    {

        $profile = $this->profile();
        $print = false;
        $pageWidth = '100%';
        $res['user'] = $r['user'];
        $qrcode2= '';
        $res['noregistrasi'] = $r['noregistrasi'];
        $res['identitas'] =  DB::table('pasiendaftar_t as pd')
            ->join('pasien_m as ps', 'ps.id', '=', 'pd.nocmfk')
            ->join('ruangan_m as ru', 'ru.id', '=', 'pd.objectruanganlastfk')
            ->join('kelas_m as kl', 'kl.id', '=', 'pd.objectkelasfk')
            ->join('departemen_m as dp', 'dp.id', '=', 'ru.objectdepartemenfk')
            ->join('kelompokpasien_m as kp', 'kp.id', '=', 'pd.objectkelompokpasienlastfk')
            ->leftjoin('jeniskelamin_m as jk', 'jk.id', '=', 'ps.objectjeniskelaminfk')
            ->leftjoin('pegawai_m as pg', 'pg.id', '=', 'pd.objectpegawaifk')
            ->leftjoin('rekanan_m as rk', 'rk.id', '=', 'pd.objectrekananfk')
            ->leftjoin('pemakaianasuransi_t as pa', 'pa.noregistrasifk', '=', 'pd.norec')
            ->leftJoin('antrianpasiendiperiksa_t as apd', 'apd.norec', '=', 'pd.noregistrasifk')
            ->leftjoin('antrianpasiendiperiksa_t as apd1', function ($join) {
                $join->on('apd1.noregistrasifk', '=', 'pd.norec')
                    ->on('apd1.objectruanganfk', '=', 'pd.objectruanganlastfk');
            })
            ->leftjoin('pegawai_m as pg1', 'pg1.id', '=', 'apd1.objectpegawaifk')
            ->leftjoin('kamar_m as km', 'km.id', '=', 'apd.objectkamarfk')
            ->leftjoin('alamat_m as alm', 'alm.nocmfk', '=', 'ps.id')
            ->select(
                'pd.noregistrasi',
                'pd.tglregistrasi',
                'pd.tglpulang',
                'ps.namapasien',
                'ps.nocm',
                'ru.namaruangan',
                'kl.namakelas',
                'kp.kelompokpasien',
                'dp.namadepartemen',
                'dp.id as iddept',
                'jk.jeniskelamin',
                'pg.namalengkap',
                'pg1.namalengkap as dokterspesialis',
                'rk.namarekanan',
                'km.namakamar',
                'alm.alamatlengkap',
                'kp.id as id_kelompokpasien',
                'ps.nobpjs',
                DB::raw("TO_CHAR(age(pd.tglregistrasi, ps.tgllahir), 'YY thn MM Bulan DD hr') AS umur"),
                'pa.nosep'
            )
            ->where('pd.statusenabled', true)
            ->where('pd.kdprofile', $this->kdProfile)
            ->where('pd.noregistrasi', $r['noregistrasi'])
            ->first();

        $data =  DB::table('pelayananpasien_t as pp')
            ->join('antrianpasiendiperiksa_t as apd', 'apd.norec', '=', 'pp.noregistrasifk')
            ->join('pasiendaftar_t as pd', 'pd.norec', '=', 'apd.noregistrasifk')
            ->join('kelas_m as kls', 'kls.id', '=', 'apd.objectkelasfk')
            ->join('produk_m as prd', 'prd.id', '=', 'pp.produkfk')
            ->join('ruangan_m as ru', 'ru.id', '=', 'apd.objectruanganfk')
            ->join('departemen_m as dp', 'dp.id', '=', 'ru.objectdepartemenfk')
            ->leftJOIN('strukresep_t as sr', 'sr.norec', '=', 'pp.strukresepfk')
            ->leftJOIN('ruangan_m as ru2', 'ru2.id', '=', 'sr.ruanganfk')
            ->leftJOIN('departemen_m as dp2', 'dp2.id', '=', 'ru2.objectdepartemenfk')
            ->leftJOIN('pegawai_m as pg', 'pg.id', '=', 'sr.penulisresepfk')
            ->leftJOIN('detailjenisproduk_m as djp', 'djp.id', '=', 'prd.objectdetailjenisprodukfk')
            ->leftJOIN('jenisproduk_m as jp', 'jp.id', '=', 'djp.objectjenisprodukfk')
            ->leftjoin('strukpelayanan_t AS sp', 'sp.norec', '=', 'pp.strukfk')
            ->leftJOIN('pegawai_m as pg1', 'pg1.id', '=', 'sp.objectpegawaipenerimafk')
            ->leftjoin('strukbuktipenerimaan_t AS sbm', 'sp.nosbmlastfk', '=', 'sbm.norec')
            ->leftJoin('strukorder_t as so', 'so.norec', 'apd.objectstrukorderfk')
            ->leftJoin('ruangan_m as ru3', 'ru3.id', 'so.objectruanganfk')
            ->select(
                'prd.namaproduk',
                'ru2.namaruangan as ruanganfarmasi',
                'ru.namaruangan as ruanganinput',
                DB::raw("sum(pp.jumlah) as jumlah"),
                'pp.hargasatuan',
                'jp.jenisproduk',
                'dp.namadepartemen',
                'dp2.namadepartemen as deparemenfarmasi',
                'pg1.namalengkap as pegawaiverif',
                
                DB::raw("
                    case when pp.isobat is null then ru.namaruangan else ru2.namaruangan end as ruang_group ,
                    case
                        when dp.id = 9 then 'UNIT: IGD'
                        when dp.id = 45 then 'UNIT: IBSA'
                        when dp.id in (3, 27) then 
                            case
                                when so.objectruanganfk is not null then 
                                    case
                                        when ru3.objectdepartemenfk = 9 then 'UNIT: IGD'
                                        when ru3.objectdepartemenfk = 45 then 'UNIT: IBSA'
                                        else 'UNIT: Rawat Inap ' || ru3.namaruangan
                                    end
                            end
                        else 'UNIT: Rawat Inap ' || ru.namaruangan
                    end as namaruangan,
                    case
                        when prd.objectdetailjenisprodukfk in(3032, 3060, 3007, 3011) then djp.detailjenisproduk
                        when pp.isobat = true then 'Obat : ' || ru2.namaruangan
                        when dp.id = 27 then 'Radiologi'
                        when dp.id = 3 then 'Laboratorium'
                        else 'Tindakan'
                    end as layanan_group,

                    case when pp.isobat is null then dp.namadepartemen else dp2.namadepartemen end as departemen_group
                ")
            )
            // when dp.id = 27 then 'Radiologi'
            // when dp.id = 3 then 'Laboratorium'
            ->where('pp.statusenabled', true)
            ->where('pp.kdprofile', $this->kdProfile)
            ->where('pd.noregistrasi', $r['noregistrasi'])
            ->where('pp.jumlah', '>', 0)
            ->groupBy('prd.namaproduk',
            'ru2.namaruangan',
            'ru.namaruangan',
                'pp.hargasatuan',
                'jp.jenisproduk',
                'dp.namadepartemen',
                'dp2.namadepartemen',
                'pg1.namalengkap',
                'dp.id',
                'so.objectruanganfk',
                'ru3.objectdepartemenfk',
                'ru3.namaruangan',
                'prd.objectdetailjenisprodukfk',
                'djp.detailjenisproduk',
                'pp.isobat'
                )
            ->get();
        // return $data;

        if($res['identitas']->id_kelompokpasien == 2){
            $qrcode2 = base64_encode(QrCode::format('svg')->size(45)->generate($res['identitas']->nobpjs));
        }

        $qrcode = base64_encode(QrCode::format('svg')->size(45)->generate($data[0]->pegawaiverif));

        $sDokterPemeriksa = $this->settingFix('jenisPetugasDokterPemeriksa');
        $pelayananpetugas = DB::table('pelayananpasienpetugas_t as ptu')
            ->leftjoin('pegawai_m as pg', 'pg.id', '=', 'ptu.objectpegawaifk')
            ->leftjoin('pelayananpasien_t as pp', 'pp.norec', '=', 'ptu.pelayananpasien')
            ->leftJoin('pegawai_m as pg2', DB::raw('CAST(pp.pelayananpegawaifk AS INT)'), '=', 'pg2.id')
            ->select('ptu.pelayananpasien', 'pg.namalengkap', 'pg2.namalengkap as pemeriksa', 'pg2.id as idpemeriksa')
            ->where('ptu.kdprofile', $this->kdProfile)
            // ->where('ptu.objectjenispetugaspefk', $sDokterPemeriksa)
            ->where('ptu.noregistrasi', $r['noregistrasi'])
            ->get();
        $res['total'] = 0;
        // foreach ($data as $item) {
        //     $item->dokter = $item->strukresepfk != null ? $item->penulisresep : '-';
        //     $item->pemeriksa = null;
        //     $item->idpemeriksa = null;
        //     $res['total']  = (float) $res['total']  + (float) $item->total;
        //     foreach ($pelayananpetugas as $itemd) {
        //         if ($itemd->pelayananpasien == $item->norec) {
        //             $item->dokter = $itemd->namalengkap;
        //             $item->idpemeriksa = $itemd->idpemeriksa;
        //             $item->pemeriksa = $itemd->pemeriksa;
        //         }
        //     }
        // }
        // return $data;
        $res['total'] = round($res['total']);
        $res['billing'] =  $data->groupBy('namaruangan');
        $multi = DB::table('strukpelayananpenjamindetail_t as sppd')
        ->select('sppd.totalppenjamin', 'sppd.totalharusdibayar', 'rk.namarekanan')
        ->join('strukpelayananpenjamin_t as spp', 'sppd.strukpelayananpenjaminfk', 'spp.norec')
        ->join('pasiendaftar_t as pd', 'pd.nostruklastfk', 'spp.nostrukfk')
        ->leftjoin('rekanan_m as rk', 'rk.id', 'sppd.kdrekananpenjamin')
        ->where('pd.noregistrasi', $r['noregistrasi'])
        ->where('sppd.statusenabled', true)
        ->where('sppd.keteranganlainnya', 'Multi Penjamin')
        ->get();
        $res['ismultipenjamin'] = count($multi) > 0 ? true : false;
        $res['multipenjamin'] = $multi;
        $res['klaim']  = StrukPelayanan::totalKlaim($r['noregistrasi']);
        $res['deposit'] = StrukBuktiPenerimaan::deposit($r['noregistrasi']);
        $res['dibayar'] = StrukBuktiPenerimaan::totalBayar($r['noregistrasi']);
        $res['iurbayar'] = StrukBuktiPenerimaan::totalBayarIUR($r['noregistrasi']);
        $res['pengembalian'] = StrukBuktiPengeluaran::totalPengembalian($r['noregistrasi']);
        $res['sisa'] =   round($res['total'])  -  $res['dibayar'] - $res['deposit'] - $res['klaim'] + $res['pengembalian'];
        $res['pdf']  = $r['pdf'];

        //dd($res['billing']);
        // foreach($res['billing'] as $ruangan) {
        //     return $ruangan->groupBy('jenistindakan');
        //     // foreach($ruangan->groupBy('jenisproduk') as $item) {
        //     //     return $
        //     // } 
        // }

        $blade = 'report.kasir.billing-casemix-klaim';
        $user = $this->getPegawai();

        if (isset($r['rekap']) && $r['rekap'] == true) {
            $res['billing'] =  $data->groupBy('departemen_group');
            $blade = 'report.kasir.rekap-billing';
        }
        if ($res['pdf'] == 'true') {
            $pdf = App::make('dompdf.wrapper');
            $pdf->loadView(
                $blade,
                array(
                    'profile' => $profile,
                    'pageWidth' => $pageWidth,
                    'print' => $print,
                    'res' => $res,
                    'data' => $data,
                    'user' => $user,
                    'qrcode' => $qrcode,
                    'qrcode2' => $qrcode2,
                )
            );
            $pdf->setPaper('a4', 'potrait');
            return $pdf->stream();
        }
        if (isset($r['storage'])) {
            $res['storage']  = true;
            $pdf = App::make('dompdf.wrapper');
            $pdf->loadView(
                $blade,
                array(
                    'profile' => $profile,
                    'pageWidth' => $pageWidth,
                    'print' => $print,
                    'res' => $res,
                    'data' => $data,
                    'user' => $user,
                    'qrcode' => $qrcode,
                    'qrcode2' => $qrcode2,
                )
            );
            $pdf->setPaper('a4', 'potrait');
            return $pdf;
        }
        if(isset($r['isexcel']) && $r['isexcel'] == 'true') {
            $headers = array(
                'Content-Type: application/xlsx',
            );

            $file = Excel::download(new InvoiceCOB($profile,$pageWidth,$print,$res,$data,$user), date('YmdHis').'-invoice-pasien-cob-'.$r['noregistrasi'].'.xlsx');
            return $file;
        }

        return view(
            $blade,
            compact('profile', 'pageWidth', 'print', 'res', 'data', 'user', 'qrcode', 'qrcode2')
        );
    }

    public function cetakBillingCasemixKlaimRanap(Request $r)
    {

        $profile = $this->profile();
        $print = false;
        $pageWidth = '100%';
        $res['user'] = $r['user'];
        $qrcode2= '';
        $res['noregistrasi'] = $r['noregistrasi'];
        $res['identitas'] =  DB::table('pasiendaftar_t as pd')
            ->join('pasien_m as ps', 'ps.id', '=', 'pd.nocmfk')
            ->join('ruangan_m as ru', 'ru.id', '=', 'pd.objectruanganlastfk')
            ->join('kelas_m as kl', 'kl.id', '=', 'pd.objectkelasfk')
            ->join('departemen_m as dp', 'dp.id', '=', 'ru.objectdepartemenfk')
            ->join('kelompokpasien_m as kp', 'kp.id', '=', 'pd.objectkelompokpasienlastfk')
            ->leftjoin('jeniskelamin_m as jk', 'jk.id', '=', 'ps.objectjeniskelaminfk')
            ->leftjoin('pegawai_m as pg', 'pg.id', '=', 'pd.objectpegawaifk')
            ->leftjoin('rekanan_m as rk', 'rk.id', '=', 'pd.objectrekananfk')
            ->leftjoin('pemakaianasuransi_t as pa', 'pa.noregistrasifk', '=', 'pd.norec')
            ->leftJoin('antrianpasiendiperiksa_t as apd', 'apd.norec', '=', 'pd.noregistrasifk')
            ->leftjoin('antrianpasiendiperiksa_t as apd1', function ($join) {
                $join->on('apd1.noregistrasifk', '=', 'pd.norec')
                    ->on('apd1.objectruanganfk', '=', 'pd.objectruanganlastfk');
            })
            ->leftjoin('pegawai_m as pg1', 'pg1.id', '=', 'apd1.objectpegawaifk')
            ->leftjoin('kamar_m as km', 'km.id', '=', 'apd.objectkamarfk')
            ->leftjoin('alamat_m as alm', 'alm.nocmfk', '=', 'ps.id')
            ->select(
                'pd.noregistrasi',
                'pd.tglregistrasi',
                'pd.tglpulang',
                'ps.namapasien',
                'ps.nocm',
                'ru.namaruangan',
                'kl.namakelas',
                'kp.kelompokpasien',
                'dp.namadepartemen',
                'dp.id as iddept',
                'jk.jeniskelamin',
                'pg.namalengkap',
                'pg1.namalengkap as dokterspesialis',
                'rk.namarekanan',
                'km.namakamar',
                'alm.alamatlengkap',
                'kp.id as id_kelompokpasien',
                'ps.nobpjs',
                DB::raw("TO_CHAR(age(pd.tglregistrasi, ps.tgllahir), 'YY thn MM Bulan DD hr') AS umur"),
                'pa.nosep'
            )
            ->where('pd.statusenabled', true)
            ->where('pd.kdprofile', $this->kdProfile)
            ->where('pd.noregistrasi', $r['noregistrasi'])
            ->first();

        $data =  DB::table('pelayananpasien_t as pp')
            ->join('antrianpasiendiperiksa_t as apd', 'apd.norec', '=', 'pp.noregistrasifk')
            ->join('pasiendaftar_t as pd', 'pd.norec', '=', 'apd.noregistrasifk')
            ->join('kelas_m as kls', 'kls.id', '=', 'apd.objectkelasfk')
            ->join('produk_m as prd', 'prd.id', '=', 'pp.produkfk')
            ->join('ruangan_m as ru', 'ru.id', '=', 'apd.objectruanganfk')
            ->leftjoin('departemen_m as dp', function ($join) {
                $join->on('dp.id', 'ru.objectdepartemenfk')
                    ->whereIn('dp.id', [3,27]);
            })
            ->leftJOIN('strukresep_t as sr', 'sr.norec', '=', 'pp.strukresepfk')
            ->leftJOIN('ruangan_m as ru2', 'ru2.id', '=', 'sr.ruanganfk')
            ->leftJOIN('departemen_m as dp2', 'dp2.id', '=', 'ru2.objectdepartemenfk')
            ->leftJOIN('pegawai_m as pg', 'pg.id', '=', 'sr.penulisresepfk')
            ->leftJOIN('detailjenisproduk_m as djp', 'djp.id', '=', 'prd.objectdetailjenisprodukfk')
            ->leftJOIN('jenisproduk_m as jp', 'jp.id', '=', 'djp.objectjenisprodukfk')
            ->leftjoin('strukpelayanan_t AS sp', 'sp.norec', '=', 'pp.strukfk')
            ->leftJOIN('pegawai_m as pg1', 'pg1.id', '=', 'sp.objectpegawaipenerimafk')
            ->leftjoin('strukbuktipenerimaan_t AS sbm', 'sp.nosbmlastfk', '=', 'sbm.norec')
            ->leftJoin('strukorder_t as so', 'so.norec', 'apd.objectstrukorderfk')
            ->leftJoin('ruangan_m as ru3', 'ru3.id', 'so.objectruanganfk')
            ->select(
                'prd.namaproduk',
                DB::raw("sum(pp.jumlah) as jumlah"),
                'pp.hargasatuan',
                'jp.jenisproduk',
                'pg1.namalengkap as pegawaiverif',
                
                DB::raw("
                    case
                        when prd.objectdetailjenisprodukfk in(3032, 3060, 3007, 3011) then djp.detailjenisproduk
                        when pp.isobat = true then 'Obat'
                        when dp.id = 27 then 'Radiologi'
                        when dp.id = 3 then 'Laboratorium'
                        else 'Tindakan'
                    end as layanan_group
                ")
            )
            // when dp.id = 27 then 'Radiologi'
            // when dp.id = 3 then 'Laboratorium'
            ->where('pp.statusenabled', true)
            ->where('pp.kdprofile', $this->kdProfile)
            ->where('pd.noregistrasi', $r['noregistrasi'])
            ->where('pp.jumlah', '>', 0)
            ->groupBy('prd.namaproduk',
                'pp.hargasatuan',
                'jp.jenisproduk',
                'dp.namadepartemen',
                'pg1.namalengkap',
                'dp.id',
                'prd.objectdetailjenisprodukfk',
                'djp.detailjenisproduk',
                'pp.isobat'
                )
            ->get();
        // return $data;

        if($res['identitas']->id_kelompokpasien == 2){
            $qrcode2 = base64_encode(QrCode::format('svg')->size(45)->generate($res['identitas']->nobpjs));
        }

        $qrcode = base64_encode(QrCode::format('svg')->size(45)->generate($data[0]->pegawaiverif));

        $sDokterPemeriksa = $this->settingFix('jenisPetugasDokterPemeriksa');
        $pelayananpetugas = DB::table('pelayananpasienpetugas_t as ptu')
            ->leftjoin('pegawai_m as pg', 'pg.id', '=', 'ptu.objectpegawaifk')
            ->leftjoin('pelayananpasien_t as pp', 'pp.norec', '=', 'ptu.pelayananpasien')
            ->leftJoin('pegawai_m as pg2', DB::raw('CAST(pp.pelayananpegawaifk AS INT)'), '=', 'pg2.id')
            ->select('ptu.pelayananpasien', 'pg.namalengkap', 'pg2.namalengkap as pemeriksa', 'pg2.id as idpemeriksa')
            ->where('ptu.kdprofile', $this->kdProfile)
            // ->where('ptu.objectjenispetugaspefk', $sDokterPemeriksa)
            ->where('ptu.noregistrasi', $r['noregistrasi'])
            ->get();
        $res['total'] = 0;
        // foreach ($data as $item) {
        //     $item->dokter = $item->strukresepfk != null ? $item->penulisresep : '-';
        //     $item->pemeriksa = null;
        //     $item->idpemeriksa = null;
        //     $res['total']  = (float) $res['total']  + (float) $item->total;
        //     foreach ($pelayananpetugas as $itemd) {
        //         if ($itemd->pelayananpasien == $item->norec) {
        //             $item->dokter = $itemd->namalengkap;
        //             $item->idpemeriksa = $itemd->idpemeriksa;
        //             $item->pemeriksa = $itemd->pemeriksa;
        //         }
        //     }
        // }
        // return $data;
        $res['total'] = round($res['total']);
        $res['billing'] =  $data->groupBy('prd.namaproduk',
        'pp.hargasatuan',
        'jp.jenisproduk',
        'dp.namadepartemen',
        'pg1.namalengkap',
        'dp.id',
        'prd.objectdetailjenisprodukfk',
        'djp.detailjenisproduk',
        'pp.isobat'
        );
        $multi = DB::table('strukpelayananpenjamindetail_t as sppd')
        ->select('sppd.totalppenjamin', 'sppd.totalharusdibayar', 'rk.namarekanan')
        ->join('strukpelayananpenjamin_t as spp', 'sppd.strukpelayananpenjaminfk', 'spp.norec')
        ->join('pasiendaftar_t as pd', 'pd.nostruklastfk', 'spp.nostrukfk')
        ->leftjoin('rekanan_m as rk', 'rk.id', 'sppd.kdrekananpenjamin')
        ->where('pd.noregistrasi', $r['noregistrasi'])
        ->where('sppd.statusenabled', true)
        ->where('sppd.keteranganlainnya', 'Multi Penjamin')
        ->get();
        $res['ismultipenjamin'] = count($multi) > 0 ? true : false;
        $res['multipenjamin'] = $multi;
        $res['klaim']  = StrukPelayanan::totalKlaim($r['noregistrasi']);
        $res['deposit'] = StrukBuktiPenerimaan::deposit($r['noregistrasi']);
        $res['dibayar'] = StrukBuktiPenerimaan::totalBayar($r['noregistrasi']);
        $res['iurbayar'] = StrukBuktiPenerimaan::totalBayarIUR($r['noregistrasi']);
        $res['pengembalian'] = StrukBuktiPengeluaran::totalPengembalian($r['noregistrasi']);
        $res['sisa'] =   round($res['total'])  -  $res['dibayar'] - $res['deposit'] - $res['klaim'] + $res['pengembalian'];
        $res['pdf']  = $r['pdf'];

        //dd($res['billing']);
        // foreach($res['billing'] as $ruangan) {
        //     return $ruangan->groupBy('jenistindakan');
        //     // foreach($ruangan->groupBy('jenisproduk') as $item) {
        //     //     return $
        //     // } 
        // }

        $blade = 'report.kasir.billing-casemix-klaim-ranap';
        $user = $this->getPegawai();

        if (isset($r['rekap']) && $r['rekap'] == true) {
            $res['billing'] =  $data->groupBy('departemen_group');
            $blade = 'report.kasir.rekap-billing';
        }
        if ($res['pdf'] == 'true') {
            $pdf = App::make('dompdf.wrapper');
            $pdf->loadView(
                $blade,
                array(
                    'profile' => $profile,
                    'pageWidth' => $pageWidth,
                    'print' => $print,
                    'res' => $res,
                    'data' => $data,
                    'user' => $user,
                    'qrcode' => $qrcode,
                    'qrcode2' => $qrcode2,
                )
            );
            $pdf->setPaper('a4', 'potrait');
            return $pdf->stream();
        }
        if (isset($r['storage'])) {
            $res['storage']  = true;
            $pdf = App::make('dompdf.wrapper');
            $pdf->loadView(
                $blade,
                array(
                    'profile' => $profile,
                    'pageWidth' => $pageWidth,
                    'print' => $print,
                    'res' => $res,
                    'data' => $data,
                    'user' => $user,
                    'qrcode' => $qrcode,
                    'qrcode2' => $qrcode2,
                )
            );
            $pdf->setPaper('a4', 'potrait');
            return $pdf;
        }
        if(isset($r['isexcel']) && $r['isexcel'] == 'true') {
            $headers = array(
                'Content-Type: application/xlsx',
            );

            $file = Excel::download(new InvoiceCOB($profile,$pageWidth,$print,$res,$data,$user), date('YmdHis').'-invoice-pasien-cob-'.$r['noregistrasi'].'.xlsx');
            return $file;
        }

        return view(
            $blade,
            compact('profile', 'pageWidth', 'print', 'res', 'data', 'user', 'qrcode', 'qrcode2')
        );
    }

    public function cetakBilling(Request $r)
    {

        $profile = $this->profile();
        $print = false;
        $pageWidth = 930;
        $res['user'] = $r['user'];
        $res['noregistrasi'] = $r['noregistrasi'];
        $res['identitas'] =  DB::table('pasiendaftar_t as pd')
            ->join('pasien_m as ps', 'ps.id', '=', 'pd.nocmfk')
            ->join('ruangan_m as ru', 'ru.id', '=', 'pd.objectruanganlastfk')
            ->join('kelas_m as kl', 'kl.id', '=', 'pd.objectkelasfk')
            ->join('departemen_m as dp', 'dp.id', '=', 'ru.objectdepartemenfk')
            ->join('kelompokpasien_m as kp', 'kp.id', '=', 'pd.objectkelompokpasienlastfk')
            ->leftjoin('jeniskelamin_m as jk', 'jk.id', '=', 'ps.objectjeniskelaminfk')
            ->leftjoin('pegawai_m as pg', 'pg.id', '=', 'pd.objectpegawaifk')
            ->leftjoin('rekanan_m as rk', 'rk.id', '=', 'pd.objectrekananfk')
            ->leftjoin('pemakaianasuransi_t as pa', 'pa.noregistrasifk', '=', 'pd.norec')
            ->leftJoin('antrianpasiendiperiksa_t as apd', 'apd.norec', '=', 'pd.noregistrasifk')
            ->leftjoin('kamar_m as km', 'km.id', '=', 'apd.objectkamarfk')
            ->leftjoin('alamat_m as alm', 'alm.nocmfk', '=', 'ps.id')
            ->select(
                'pd.noregistrasi',
                'pd.tglregistrasi',
                'pd.tglpulang',
                'pd.tglclosing',
                'ps.namapasien',
                'ps.nocm',
                'ru.namaruangan',
                'kl.namakelas',
                'kp.kelompokpasien',
                'dp.namadepartemen',
                'jk.jeniskelamin',
                'pg.namalengkap',
                'rk.namarekanan',
                'km.namakamar',
                'alm.alamatlengkap',
                'ps.nobpjs',
                DB::raw("TO_CHAR(age(ps.tgllahir), 'YY thn MM Bulan DD hr') AS umur"),
                'pa.nosep'
            )
            ->where('pd.statusenabled', true)
            ->where('pd.kdprofile', $this->kdProfile)
            ->where('pd.noregistrasi', $r['noregistrasi'])
            ->first();

        $data =  DB::table('pelayananpasien_t as pp')
            ->join('antrianpasiendiperiksa_t as apd', 'apd.norec', '=', 'pp.noregistrasifk')
            ->join('pasiendaftar_t as pd', 'pd.norec', '=', 'apd.noregistrasifk')
            ->join('kelas_m as kls', 'kls.id', '=', 'apd.objectkelasfk')
            ->join('produk_m as prd', 'prd.id', '=', 'pp.produkfk')
            ->join('ruangan_m as ru', 'ru.id', '=', 'apd.objectruanganfk')
            ->join('departemen_m as dp', 'dp.id', '=', 'ru.objectdepartemenfk')
            ->leftJOIN('strukresep_t as sr', 'sr.norec', '=', 'pp.strukresepfk')
            ->leftJOIN('ruangan_m as ru2', 'ru2.id', '=', 'sr.ruanganfk')
            ->leftJOIN('departemen_m as dp2', 'dp2.id', '=', 'ru2.objectdepartemenfk')
            ->leftJOIN('pegawai_m as pg', 'pg.id', '=', 'sr.penulisresepfk')
            ->leftJOIN('detailjenisproduk_m as djp', 'djp.id', '=', 'prd.objectdetailjenisprodukfk')
            ->leftJOIN('jenisproduk_m as jp', 'jp.id', '=', 'djp.objectjenisprodukfk')
            ->leftjoin('strukpelayanan_t AS sp', 'sp.norec', '=', 'pp.strukfk')
            ->leftjoin('strukbuktipenerimaan_t AS sbm', 'sp.nosbmlastfk', '=', 'sbm.norec')
            ->leftJOIN('pegawai_m as pg2', 'pg2.id', '=', 'sp.objectpegawaipenerimafk')
            ->leftJoin('kelas_m as kls2', 'kls2.id', '=', 'pp.kelasfk')
            ->select(
                'pp.norec',
                'prd.namaproduk',
                'prd.id as idprd',
                'kls.namakelas',
                'kls2.namakelas as namakelaspp',
                'pp.tglpelayanan',
                'ru2.namaruangan as ruanganfarmasi',
                'pp.strukresepfk',
                'pp.jumlah',
                'sbm.nosbm',
                DB::raw("coalesce(sbm.totaldiskon, 0) as totaldiskon"),
                'pg2.namalengkap as closer',
                'pp.hargasatuan',
                'pg.namalengkap as penulisresep',
                'jp.jenisproduk',
                'dp.namadepartemen',
                'dp2.namadepartemen as deparemenfarmasi',
                DB::raw("
                    case when pp.jasa is not null then pp.jasa else 0 end jasa,
                    COALESCE (pp.hargadiscount, 0)  as diskon,
                    ( (COALESCE(pp.hargasatuan,0)  - COALESCE (pp.hargadiscount, 0))   * pp.jumlah)
                    + ( COALESCE (pp.jasa, 0)) as total,
                    to_char(pp.tglpelayanan,'yyyy-MM-dd')  as tglpelayanan_group ,
                    case when pp.strukresepfk is null then 'Layanan' else 'Resep' end as jenis,
                    case when pp.strukresepfk is null then ru.namaruangan else ru2.namaruangan end as ruang_group ,
                    case
                        when pp.isobat = true then 'Obat'
                        when dp.id = 3 then 'Laboratorium'
                        when dp.id = 27 then 'Radiologi'
                        when dp.id in(18,24) then 'Poliklinik/UGD'
                        when prd.objectdetailjenisprodukfk in(3032, 3060, 3007, 3011) then djp.detailjenisproduk
                        else 'Tindakan'
                    end as namaruangan,
                    case
                        when pp.isobat = true then 'Medicine'
                        when dp.id = 3 then 'Laboratory'
                        when dp.id = 27 then 'Radiology'
                        when dp.id in(18,24) then 'Polyclinic/IGD'
                        when prd.objectdetailjenisprodukfk in(3032, 3060, 3007, 3011) then djp.detailjenisproduk
                        else 'Treatment'
                    end as namaruanganenglish,
                    case when pp.strukresepfk is null then dp.namadepartemen else dp2.namadepartemen end as departemen_group
                ")
            )
            ->where('pp.statusenabled', true)
            ->where('pp.kdprofile', $this->kdProfile)
            ->where('pd.noregistrasi', $r['noregistrasi'])
            ->orderBy('prd.namaproduk', 'ASC')
            ->where('pp.hargasatuan', '>', 0)
            ->where('pp.jumlah', '>', 0)
            ->distinct()
            ->get();
        // return $data;

        $sDokterPemeriksa = $this->settingFix('jenisPetugasDokterPemeriksa');
        $pelayananpetugas = DB::table('pelayananpasienpetugas_t as ptu')
            ->leftjoin('pegawai_m as pg', 'pg.id', '=', 'ptu.objectpegawaifk')
            ->leftjoin('pelayananpasien_t as pp', 'pp.norec', '=', 'ptu.pelayananpasien')
            ->leftJoin('pegawai_m as pg2', DB::raw('CAST(pp.pelayananpegawaifk AS INT)'), '=', 'pg2.id')
            ->select('ptu.pelayananpasien', 'pg.namalengkap', 'pg2.namalengkap as pemeriksa', 'pg2.id as idpemeriksa')
            ->where('ptu.kdprofile', $this->kdProfile)
            // ->where('ptu.objectjenispetugaspefk', $sDokterPemeriksa)
            ->where('ptu.noregistrasi', $r['noregistrasi'])
            ->get();
        $res['total'] = 0;
        $res['diskon'] = $data[0]->totaldiskon;

        $temps = [];
        $res['closer'] = "-";
        foreach ($data as $item) {
            $item->dokter = $item->strukresepfk != null ? $item->penulisresep : '-';
            $item->pemeriksa = null;
            $item->idpemeriksa = null;
            $res['closer'] = $item->closer;
            $res['total']  = (float) $res['total']  + (float) $item->total;
            foreach ($pelayananpetugas as $itemd) {
                if ($itemd->pelayananpasien == $item->norec) {
                    $item->dokter = $itemd->namalengkap;
                    $item->idpemeriksa = $itemd->idpemeriksa;
                    $item->pemeriksa = $itemd->pemeriksa;
                }
            }
            $ada = false;
            foreach ($temps as $keyTemp => $temp) {
                if ($temp->idprd == $item->idprd && $temp->hargasatuan == $item->hargasatuan) {
                    $temps[$keyTemp]->jumlah = (float)$temp->jumlah + (float)$item->jumlah;
                    $temps[$keyTemp]->total = (float)$temp->total + (float)$item->total;
                    // $temps[$keyTemp]->hargasatuan = (float)$temp->hargasatuan + (float)$item->hargasatuan;
                    $ada = true;
                    break;
                }
            }
            
            if (!$ada) {
                $temps[] = $item;
            }
        }
        $res['total'] = round($res['total']);
        $res['billing'] =  collect($temps)->groupBy('namaruangan');
        // $res['billing'] =  $data->groupBy('namaruangan');
        $multi = DB::table('strukpelayananpenjamindetail_t as sppd')
        ->select('sppd.totalppenjamin', 'sppd.totalharusdibayar', 'rk.namarekanan')
        ->join('strukpelayananpenjamin_t as spp', 'sppd.strukpelayananpenjaminfk', 'spp.norec')
        ->join('pasiendaftar_t as pd', 'pd.nostruklastfk', 'spp.nostrukfk')
        ->leftjoin('rekanan_m as rk', 'rk.id', 'sppd.kdrekananpenjamin')
        ->where('pd.noregistrasi', $r['noregistrasi'])
        ->where('sppd.statusenabled', true)
        ->where('sppd.keteranganlainnya', 'Multi Penjamin')
        ->get();
        $res['ismultipenjamin'] = count($multi) > 0 ? true : false;
        $res['multipenjamin'] = $multi;
        $res['klaim']  = StrukPelayanan::totalKlaim($r['noregistrasi']);
        $res['deposit'] = StrukBuktiPenerimaan::deposit($r['noregistrasi']);
        $res['dibayar'] = StrukBuktiPenerimaan::totalBayar($r['noregistrasi']);
        $res['iurbayar'] = StrukBuktiPenerimaan::totalBayarIUR($r['noregistrasi']);
        $res['pengembalian'] = StrukBuktiPengeluaran::totalPengembalian($r['noregistrasi']);
        $res['sisa'] =   round($res['total'])  -  $res['dibayar'] - $res['deposit'] - $res['klaim'] + $res['pengembalian'];
        $res['pdf']  = $r['pdf'];

        $res['indo'] = isset($r['bangsa']) && $r['bangsa'] != 'WNI' ? false : true;
        // $res['indo'] = true;

        //dd($res['billing']);
        // foreach($res['billing'] as $ruangan) {
        //     return $ruangan->groupBy('jenistindakan');
        //     // foreach($ruangan->groupBy('jenisproduk') as $item) {
        //     //     return $
        //     // } 
        // }

        $blade = 'report.kasir.billing';
        $user = $this->getPegawai();


        if (isset($r['rekap']) && $r['rekap'] == true) {
            $res['billing'] =  $data->groupBy('departemen_group');
            $blade = 'report.kasir.rekap-billing';
        }
        if ($res['pdf'] == 'true') {
            $pdf = App::make('dompdf.wrapper');
            $pdf->loadView(
                $blade . '-dom',
                array(
                    'profile' => $profile,
                    'pageWidth' => $pageWidth,
                    'print' => $print,
                    'res' => $res,
                    'data' => $data,
                    'user' => $user,
                )
            );
            return $pdf->stream();
        }
        if (isset($r['storage'])) {
            // return "asd";
            $pageWidth = '100%';
            $res['storage']  = true;
            $pdf = App::make('dompdf.wrapper');
            $pdf->loadView(
                $blade,
                array(
                    'profile' => $profile,
                    'pageWidth' => $pageWidth,
                    'print' => $print,
                    'res' => $res,
                    'data' => $data,
                    'user' => $user,
                )
            );
            $pdf->setPaper('a4', 'potrait');
            return $pdf;
        }
        if(isset($r['isexcel']) && $r['isexcel'] == 'true') {
            $headers = array(
                'Content-Type: application/xlsx',
            );

            $file = Excel::download(new InvoiceRanap($profile,$pageWidth,$print,$res,$data,$user), date('YmdHis').'-invoice-pasien-'.$r['noregistrasi'].'.xlsx');
            return $file;
        }

        return view(
            $blade,
            compact('profile', 'pageWidth', 'print', 'res', 'data', 'user')
        );
    }

    public function hapusTindakan(Request $r)
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
                    'Hapus Tindakan ' . $item['namaproduk'] . ' di ' . $item['namaruangan'] . ' pada Pasien ' .
                        $r['namapasien'] . ' (' . $r['nocm'] . ') - ' . $r['noregistrasi']
                );
            }


            $transStatus = true;
        } catch (\Exception $e) {
            $transStatus = false;
            $transMessage = $e->getMessage();
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
            // $transMessage = "Hapus Gagal";
            DB::rollBack();
            $result = array(
                "status" => 400,
                "result"  => null
            );
        }
        return $this->respond($result['result'], $result['status'], $transMessage);
    }

    public function detailPetugasTindakan(Request $r)
    {
        $kdProfile = $this->kdProfile;
        $result = DB::table('pelayananpasienpetugas_t as pp')
            ->join('jenispetugaspelaksana_m as jp', 'jp.id', '=', 'pp.objectjenispetugaspefk')
            ->join('pegawai_m as pg', 'pg.id', '=', 'pp.objectpegawaifk')
            ->leftjoin('pegawai_m as pg1', 'pg1.id', '=', 'pp.objectoperator1fk')
            ->leftjoin('pegawai_m as pg2', 'pg2.id', '=', 'pp.objectoperator2fk')
            ->leftjoin('pegawai_m as pg3', 'pg3.id', '=', 'pp.objectoperator3fk')
            ->leftjoin('pegawai_m as pg4', 'pg4.id', '=', 'pp.objectoperator4fk')
            ->leftjoin('pegawai_m as pg5', 'pg5.id', '=', 'pp.objectoperator5fk')
            ->select(
                'pp.norec',
                'pg.namalengkap',
                'pg1.namalengkap as operator1',
                'pg2.namalengkap as operator2',
                'pg3.namalengkap as operator3',
                'pg4.namalengkap as operator4',
                'pg5.namalengkap as operator5',
                'jp.jenispetugaspe',
                'pp.objectpegawaifk',
                'pp.objectoperator1fk',
                'pp.objectoperator2fk',
                'pp.objectoperator3fk',
                'pp.objectoperator4fk',
                'pp.objectoperator5fk',
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

    public function saveJenisPetugasTindakan(Request $r)
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
            $new_PPP->objectjenispetugaspefk = $r['objectjenispetugaspefk']['value'];
            $new_PPP->objectpegawaifk = $r['objectpegawaifk']['value'];
            $new_PPP->pelayananpasien = $r['pelayananpasien'];
            $new_PPP->noregistrasi = $r['noregistrasi'];
            $new_PPP->save();

            $pg =  Pegawai::where('id', $r['objectpegawaifk']['value'])->first();
            $ps = PasienDaftar::detailPasien($r['noregistrasi']);

            $this->LOGGING(
                $log . 'Petugas Tindakan',
                $new_PPP->norec,
                'pelayananpasienpetugas_t',
                $log . 'Petugas Tindakan ' . $pg->namalengkap . ' pelayanan ' . $r['namaproduk'] . ' di ' . $r['namaruangan'] . ' pada Pasien ' .
                    $ps->namapasien . ' (' .  $ps->nocm . ') - ' . $r['noregistrasi']
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
    public function deleteJenisPetugasTindakan(Request $r)
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
                'Hapus Petugas Tindakan ' . $pg->namalengkap . ' pelayanan ' . $r['namaproduk'] . ' di ' . $ps->ruanganlast . ' pada Pasien ' .
                    $ps->namapasien . ' (' .  $ps->nocm . ') - ' . $r['noregistrasi']
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
    public function updateTglTindakan(Request $r)
    {
        DB::beginTransaction();
        try {

            PelayananPasien::where('norec', $r['norec'])->update([
                'tglpelayanan' => $r['tglpelayanan']
            ]);

            $ps = PasienDaftar::detailPasien($r['noregistrasi']);

            $this->LOGGING(
                'Ubah Tgl Pelayanan',
                $r['norec'],
                'pelayananpasien_t',
                'Ubah Tgl Pelayanan ' . $r['tglpelayanan'] . ' ' . $r['namaproduk'] . ' pada Pasien ' .
                    $ps->namapasien . ' (' .  $ps->nocm . ') - ' . $r['noregistrasi']
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

    public function detailKomponenTindakan(Request $r)
    {
        $kdProfile = $this->kdProfile;
        $result = DB::table('pelayananpasiendetail_t as pp')
            ->join('komponenharga_m as km', 'km.id', '=', 'pp.komponenhargafk')
            ->select(
                'pp.norec',
                'km.komponenharga',
                'km.id as objectkomponenhargafk',
                'pp.hargadiscount',
                'pp.hargasatuan',
                'pp.jumlah',
                'pp.pelayananpasien',
                'pp.jasa',
                'pp.produkfk as objectprodukfk',
                'km.iscito',
                'pp.hargadijamin',
                'pp.hargadiscount'
            )
            ->where('pp.pelayananpasien', $r['norec'])
            ->where('pp.statusenabled', true)
            ->where('pp.kdprofile', $kdProfile)
            ->get();
        $res['data'] = $result;
        $total = 0;
        foreach ($result as $d) {
            $total =  $total + ((($d->hargasatuan  - $d->hargadiscount)  * $d->jumlah) + $d->jasa);
        }
        $res['total'] = $total;

        return $this->respond($res);
    }
    public function updateDiskon(Request $r)
    {
        DB::beginTransaction();
        try {
            $nilaiCito = 0;
            $totalJasa = 0;
            if ($r['jasa'] != 0) {
                $nilaiCito = (float) $this->settingFix('tarifCito');
            }
            PelayananPasienDetail::where('norec', $r['norec'])
                ->update(
                    [
                        'hargadiscount' => $r['hargadiscount'],
                        'jasa' => ($r['hargasatuan'] - $r['hargadiscount']) * $nilaiCito,
                    ]
                );
            $totalDiskon = 0.0;
            $dataaa = PelayananPasienDetail::where('pelayananpasien', $r['norec_pp'])->get();
            foreach ($dataaa as $item) {
                $totalDiskon = $totalDiskon + $item->hargadiscount;
                $totalJasa = $totalJasa + $item->jasa;
            }
            PelayananPasien::where('norec', $r['norec_pp'])
                ->update([
                    'hargadiscount' => $totalDiskon,
                    'jasa' => $totalJasa
                ]);
            $ps = PasienDaftar::detailPasien($r['noregistrasi']);
            $this->LOGGING(
                'Diskon Komponen',
                $r['norec'],
                'pelayananpasiendetail_t',
                'Diskon Komponen Rp. ' . $r['hargadiscount'] . ' '  . $r['namaproduk'] . ' pada Pasien ' .
                    $ps->namapasien . ' (' .  $ps->nocm . ') - ' . $r['noregistrasi']
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
    public function cetakBuktiLayananJasa(Request $request)
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
        $profile = $this->profile();

        $data =  $this->indentitasCetak($kdProfile, $noregistrasi);
        $sDokterPemeriksa = $this->settingFix('jenisPetugasDokterPemeriksa');
        $idKomponeJasaMedis = $this->settingFix('komponenHargaJasaDokter');
        $details = collect(DB::select("
            SELECT x.tglpelayanan,x.namadokter,x.namaproduk,x.jumlah,x.hargasatuan,x.diskon,x.jasa,(x.jumlah * (x.hargasatuan - x.diskon)) + x.jasa AS total
            FROM (SELECT tp.tglpelayanan,(select pg.namalengkap from pegawai_m as pg INNER JOIN pelayananpasienpetugas_t p3 on p3.objectpegawaifk = pg.id
                   WHERE p3.pelayananpasien = tp.norec AND p3.objectjenispetugaspefk = $sDokterPemeriksa limit 1) AS namadokter,tp.produkfk,pro.namaproduk,tp.jumlah,
                   (select case when hargasatuan is null then 0 else hargasatuan end as hargajual from pelayananpasiendetail_t where pelayananpasien=tp.norec and komponenhargafk=$idKomponeJasaMedis limit 1) as hargasatuan,
                   (select case when hargadiscount is null then 0 else hargadiscount end as hargadiscount from pelayananpasiendetail_t where pelayananpasien=tp.norec and komponenhargafk=$idKomponeJasaMedis limit 1) as diskon,
                   (select case when jasa is null then 0 else jasa end as jasa from pelayananpasiendetail_t where pelayananpasien=tp.norec and komponenhargafk=$idKomponeJasaMedis limit 1) as jasa
            FROM pelayananpasien_t AS tp
            LEFT JOIN produk_m AS pro ON tp.produkfk = pro.id
            INNER JOIN antrianpasiendiperiksa_t AS apdp ON apdp.norec = tp.noregistrasifk
            INNER JOIN ruangan_m AS ru ON apdp.objectruanganfk = ru.id
            LEFT JOIN pegawai_m AS pp ON apdp.objectpegawaifk = pp.id
            WHERE tp.kdprofile = $kdProfile AND tp.statusenabled = true
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
            'judul' => "BUKTI LAYANAN JASA MEDIS",
            'header' => $data,
            'details' =>  $details,
        );
        // dd($dataReport);
        return view(
            'report.kasir.bukti-layanan',
            compact('dataReport', 'pageWidth', 'profile')
        );
    }

    public function cetakBuktiLayananPerTindakan(Request $request)
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
        $profile = $this->profile();

        $data =  $this->indentitasCetak($kdProfile, $noregistrasi);
        // dd($data);
        $sDokterPemeriksa = $this->settingFix('jenisPetugasDokterPemeriksa');
        $details = collect(DB::select("
            SELECT x.tglpelayanan,x.namadokter,x.namaproduk,x.jumlah,x.hargasatuan,x.diskon,x.jasa,(x.jumlah * (x.hargasatuan - x.diskon)) + x.jasa AS total
            FROM ( SELECT tp.tglpelayanan,(select pg.namalengkap from pegawai_m as pg INNER JOIN pelayananpasienpetugas_t p3 on p3.objectpegawaifk = pg.id
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
            'judul' => "BUKTI LAYANAN RUANGAN PER TINDAKAN",
            'header' => $data,
            'details' =>  $details,
        );
        // dd($dataReport);
        return view(
            'report.kasir.bukti-layanan',
            compact('dataReport', 'pageWidth', 'profile')
        );
    }
    public function cetakFormNuklir(Request $request)
    {
        $kdProfile = $this->kdProfile;
        $norecPp = '';
        $datNorec = explode('|', $request['norec']);
        foreach ($datNorec as $ob) {
            $norecPp = $norecPp . ",'" . $ob . "'";
        }
        $norecPp = substr($norecPp, 1, strlen($norecPp) - 1);
        $paramsPp = "";
        if ($norecPp != '') {
            $paramsPp = " AND so.norec IN (" . $norecPp . ")";
        }
        $profile = $this->profile();
        $idRuanganNuklir = $this->settingFix('idRuanganNuklirOrder');
        //var_dump($idRuanganNuklir);

        $details = DB::select(DB::raw("select so.norec, pd.noregistrasi, pd.norec as pd_norec, so.noorder, pd.tglregistrasi, pas.namapasien, pas.noidentitas, pas.nobpjs,
        pas.tgllahir, pas.nocm, so.tglorder::date AS DATE, jk.jeniskelamin, kp.kelompokpasien, ruAs.namaruangan as asalruangan, ruTu.namaruangan as ruangantujuan,
        peg.namalengkap as dpjp, string_agg(pr.namaproduk, ', ') as tindakan, so.catatanklinis, so.terapiradioaktif, so.catatanterapiradioaktif,
        so.terapiiodium || ' mCi' as terapiiodium, so.terapiradiofarmaka || ' mCi' as terapiradiofarmaka, rd.radionuklida, fr.farmaka, so.catatanfarmaka, peg1.namalengkap as dpjp,
        so.nobatchradionuklida, so.nobatchradiofarmaka, so.dosisradiofarmasis, so.jampermintaan, so.dosisfullsyringe, so.jamfullsyringe, so.dosisemptysyringe, so.jamemptysyringe,
        so.rutelokasisuntik, so.jaminjeksi, jt.jenisterapi, ja.jenisakuisisi, so.jamakuisisi, so.treatment, so.paparanradiasi || ' μSv/jam' paparanradiasi, so.pemeriksaanradiograferfk,
        so.jenisakuisisifk, so.paparanradiasi as paparan, pd.nocmfk, ruTu.id as idruangantujuan, apd.norec as norec_apd, pas.tgllahir, so.tb, so.bb,
        case when so.statusorder = 0 then 'Belum Verifikasi' else 'Verifikasi' end as statusorder
        from strukorder_t as so
        inner join pasiendaftar_t as pd on pd.norec = so.noregistrasifk
        inner join antrianpasiendiperiksa_t as apd on apd.noregistrasifk = pd.norec
        and apd.norec = so.norec_apd
        inner join pasien_m as pas on pd.nocmfk = pas.id
        left join ruangan_m as ruAs on so.objectruanganfk = ruAs.id
        inner join ruangan_m as ruTu on so.objectruangantujuanfk = ruTu.id
        inner join pegawai_m as peg on peg.id = so.objectpegawaiorderfk
        inner join jeniskelamin_m as jk on jk.id = pas.objectjeniskelaminfk
        inner join kelompokpasien_m as kp on kp.id = pd.objectkelompokpasienlastfk
        inner join departemen_m as dep on dep.id = ruAs.objectdepartemenfk
        inner join departemen_m as dep2 on dep2.id = ruTu.objectdepartemenfk
        inner join kelas_m as kls on kls.id = pd.objectkelasfk
        left join pegawai_m as peg1 on peg1.id = pd.objectpegawaifk
        left join orderpelayanan_t as op on so.norec = op.strukorderfk
        left join produk_m as pr on pr.id = op.objectprodukfk
        left join radionuklida_m as rd on rd.id = so.jenisradionuklidafk
        left join farmaka_m as fr on fr.id = so.jenisfarmakafk
        left join jenisterapi_m as jt on jt.id = so.pemeriksaanradiograferfk
        left join jenisakuisisi_m as ja on ja.id = so.jenisakuisisifk
        where so.statusenabled = true
        and so.kdprofile = 1
        and so.objectruangantujuanfk in ($idRuanganNuklir)
        $paramsPp
        group by so.norec, pd.noregistrasi, pd.norec, so.noorder, pd.tglregistrasi, pas.namapasien, pas.noidentitas, pas.nobpjs, pas.tgllahir, pas.nocm, so.tglorder::date, pas.tgllahir,
        jk.jeniskelamin, kp.kelompokpasien, ruAs.namaruangan, ruTu.namaruangan, so.catatanklinis, peg.namalengkap, so.catatanklinis, so.terapiradioaktif, pd.nocmfk, ruTu.id, apd.norec, so.tb, so.bb,
        so.catatanterapiradioaktif, so.terapiiodium, so.terapiradiofarmaka, rd.radionuklida, fr.farmaka, so.catatanfarmaka, peg1.namalengkap, so.nobatchradionuklida, so.nobatchradiofarmaka, so.dosisradiofarmasis, so.jampermintaan, so.dosisfullsyringe, so.jamfullsyringe, so.dosisemptysyringe, so.jamemptysyringe,
        so.rutelokasisuntik, so.jaminjeksi, jt.jenisterapi, ja.jenisakuisisi, so.jamakuisisi, so.treatment, so.paparanradiasi, so.pemeriksaanradiograferfk, so.jenisakuisisifk, so.statusorder
        order by pd.tglregistrasi
        "));

        $pageWidth = 950;

        $dataReport = array(
            'namaprofile' => $profile->namalengkap,
            'alamat' => $profile->alamatlengkap,
            'judul' => "FORMULIR NUKLIR",
            'details' =>  $details,
        );
        // dd($dataReport);
        return view(
            'report.kasir.form-nuklir',
            compact('dataReport', 'pageWidth', 'profile')
        );
    }
    public function cetakFormJadwal(Request $request)
    {
        $kdProfile = $this->kdProfile;

        $profile = $this->profile();
        $idRuanganNuklir = $this->settingFix('idRuanganNuklirOrder');

        $namapasien = '';
        if ($request->namapasien != '') {
            $namapasien = "and pas.namapasien ilike '%" . $request->namapasien . "%'";
        }

        $ruangan = '';
        if (isset($request->ruanganfk) && $request->ruanganfk != '') {
            $ruangan = "and ruTu.id in(" . $request->ruanganfk . ")";
        }

        $idRuanganNuklir = $this->settingFix('idRuanganNuklirOrder');

        $data = DB::select(DB::raw("select so.norec, pd.noregistrasi, pd.norec as pd_norec, so.noorder, pd.tglregistrasi, pas.namapasien, pas.noidentitas, pas.nobpjs,
        pas.tgllahir, pas.nocm, so.tglorder::date AS DATE, jk.jeniskelamin, kp.kelompokpasien, ruAs.namaruangan as asalruangan, ruTu.namaruangan as ruangantujuan,
        peg.namalengkap as dpjp, string_agg(pr.namaproduk, ', ') as tindakan, so.catatanklinis, so.terapiradioaktif, so.catatanterapiradioaktif,
        so.terapiiodium || ' mCi' as terapiiodium, so.terapiradiofarmaka || ' mCi' as terapiradiofarmaka, rd.radionuklida, fr.farmaka, so.catatanfarmaka, peg1.namalengkap as dpjp,
        so.nobatchradionuklida, so.nobatchradiofarmaka, so.dosisradiofarmasis, so.dosisradiofarmasis || ' mCi' as dosisradiofarmasistext, so.jampermintaan, so.dosisfullsyringe, so.dosisfullsyringe || ' mCi' as dosisfullsyringetext, so.jamfullsyringe, so.dosisemptysyringe, so.dosisemptysyringe || ' mCi' as dosisemptysyringetext, so.jamemptysyringe,
        so.rutelokasisuntik, so.jaminjeksi, jt.jenisterapi, ja.jenisakuisisi, so.jamakuisisi, so.treatment, so.paparanradiasi || ' μSv/jam' paparanradiasi, so.pemeriksaanradiograferfk,
        so.jenisakuisisifk, so.paparanradiasi as paparan, pd.nocmfk, ruTu.id as idruangantujuan, apd.norec as norec_apd, so.bb, so.tb, TO_CHAR(age(pas.tgllahir), 'YY thn') as umur,
        case when so.statusorder = 0 then 'Belum Verifikasi' else 'Verifikasi' end as statusorder,
        string_agg('Reschedule dari ' || pj.tglawal || ' ke ' || pj.tglakhir || ' karena ' || pj.alasan , ';') as keterangan
        from strukorder_t as so
        inner join pasiendaftar_t as pd on pd.norec = so.noregistrasifk
        inner join antrianpasiendiperiksa_t as apd on apd.noregistrasifk = pd.norec
        and apd.norec = so.norec_apd
        inner join pasien_m as pas on pd.nocmfk = pas.id
        left join ruangan_m as ruAs on so.objectruanganfk = ruAs.id
        inner join ruangan_m as ruTu on so.objectruangantujuanfk = ruTu.id
        inner join pegawai_m as peg on peg.id = so.objectpegawaiorderfk
        inner join jeniskelamin_m as jk on jk.id = pas.objectjeniskelaminfk
        inner join kelompokpasien_m as kp on kp.id = pd.objectkelompokpasienlastfk
        inner join departemen_m as dep on dep.id = ruAs.objectdepartemenfk
        inner join departemen_m as dep2 on dep2.id = ruTu.objectdepartemenfk
        inner join kelas_m as kls on kls.id = pd.objectkelasfk
        left join pegawai_m as peg1 on peg1.id = pd.objectpegawaifk
        left join orderpelayanan_t as op on so.norec = op.strukorderfk
        left join produk_m as pr on pr.id = op.objectprodukfk
        left join radionuklida_m as rd on rd.id = so.jenisradionuklidafk
        left join farmaka_m as fr on fr.id = so.jenisfarmakafk
        left join jenisterapi_m as jt on jt.id = so.pemeriksaanradiograferfk
        left join jenisakuisisi_m as ja on ja.id = so.jenisakuisisifk
        left join perubahanjadwal_t pj on pj.noregistrasifk = pd.norec
        where so.statusenabled = true
        and so.kdprofile = 1
        and so.objectruangantujuanfk in ($idRuanganNuklir)
        and so.tglorder::date between '$request->tglAwal' and '$request->tglAkhir'
        $namapasien
        $ruangan
        group by so.norec, pd.noregistrasi, pd.norec, so.noorder, pd.tglregistrasi, pas.namapasien, pas.noidentitas, pas.nobpjs, pas.tgllahir, pas.nocm, so.tglorder::date, so.bb, so.tb,
        jk.jeniskelamin, kp.kelompokpasien, ruAs.namaruangan, ruTu.namaruangan, so.catatanklinis, peg.namalengkap, so.catatanklinis, so.terapiradioaktif, pd.nocmfk, ruTu.id, apd.norec, TO_CHAR(age(pas.tgllahir), 'YY thn'),
        so.catatanterapiradioaktif, so.terapiiodium, so.terapiradiofarmaka, rd.radionuklida, fr.farmaka, so.catatanfarmaka, peg1.namalengkap, so.nobatchradionuklida, so.nobatchradiofarmaka, so.dosisradiofarmasis, so.jampermintaan, so.dosisfullsyringe, so.jamfullsyringe, so.dosisemptysyringe, so.jamemptysyringe,
        so.rutelokasisuntik, so.jaminjeksi, jt.jenisterapi, ja.jenisakuisisi, so.jamakuisisi, so.treatment, so.paparanradiasi, so.pemeriksaanradiograferfk, so.jenisakuisisifk, so.statusorder
        order by pd.tglregistrasi
        "));

        $pageWidth = 950;

        $dataReport = array(
            'namaprofile' => $profile->namalengkap,
            'alamat' => $profile->alamatlengkap,
            'judul' => "JADWAL NUKLIR",
            'details' =>  $data,
        );
        // dd($dataReport);
        return view(
            'report.kasir.form-jadwal',
            compact('dataReport', 'pageWidth', 'profile')
        );
    }
    function indentitasCetak($kdProfile, $noregistrasi)
    {
        $data = collect(DB::select("
        SELECT pd.noregistrasi,ps.nocm,ps.tgllahir,to_char(ps.tgllahir, 'DD-MM-YYYY') as tglkelahiran,ps.namapasien,
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
        WHERE pd.kdprofile = $kdProfile AND pd.statusenabled = true AND pd.noregistrasi = '$noregistrasi'
"))->first();
        return $data;
    }
    public function cetakBuktiLayananRuangan(Request $request)
    {
        $kdProfile = $this->kdProfile;
        $KodeJasaMedis = $this->settingFix('KdKomponenTarifJasaDokter');
        $noregistrasi = $request['noregistrasi'];
        $user = $request['user'];
        $norecApd = $request['norec_apd'];
        $profile = $this->profile();
        $data =  $this->indentitasCetak($kdProfile, $noregistrasi);

        $sDokterPemeriksa = $this->settingFix('jenisPetugasDokterPemeriksa');

        $details = collect(DB::select("
            SELECT x.tglpelayanan,x.namadokter,x.namaproduk,x.jumlah,x.hargasatuan,x.diskon,x.jasa,(x.jumlah * (x.hargasatuan - x.diskon)) + x.jasa AS total
            FROM (SELECT tp.tglpelayanan,(select pg.namalengkap from pegawai_m as pg INNER JOIN pelayananpasienpetugas_t p3 on p3.objectpegawaifk = pg.id
                   WHERE p3.pelayananpasien = tp.norec AND p3.objectjenispetugaspefk = $sDokterPemeriksa limit 1) AS namadokter,tp.produkfk,pro.namaproduk,tp.jumlah,
                   tp.hargajual as hargasatuan,CASE WHEN tp.hargadiscount IS NULL THEN 0 ELSE tp.hargadiscount END AS diskon,
                   CASE WHEN tp.jasa IS NULL THEN 0 ELSE tp.jasa END AS jasa
            FROM pelayananpasien_t AS tp
            LEFT JOIN produk_m AS pro ON tp.produkfk = pro.id
            INNER JOIN antrianpasiendiperiksa_t AS apdp ON apdp.norec = tp.noregistrasifk
            INNER JOIN ruangan_m AS ru ON apdp.objectruanganfk = ru.id
            LEFT JOIN pegawai_m AS pp ON apdp.objectpegawaifk = pp.id
            WHERE tp.kdprofile = $kdProfile AND tp.statusenabled = true
            and apdp.norec = '$norecApd'
            ) AS x
            ORDER BY x.tglpelayanan
        "));


        $pageWidth = 950;
        $dataReport = array(
            'namaprofile' => $profile->namalengkap,
            'alamat' => $profile->alamatlengkap,
            'user' => $user,
            'judul' => "BUKTI LAYANAN",
            'header' => $data,
            'details' =>  $details,
        );

        return view(
            'report.kasir.bukti-layanan',
            compact('dataReport', 'pageWidth', 'profile')
        );
    }
    public function detailKonversiHarga(Request $request)
    {

        $idProfile = (int) $this->kdProfile;

        $pelayanan = DB::table('pasiendaftar_t as pd')
            ->leftjoin('antrianpasiendiperiksa_t as apd', 'apd.noregistrasifk', '=', 'pd.norec')
            ->leftjoin('pelayananpasien_t as pp', 'pp.noregistrasifk', '=', 'apd.norec')
            ->leftjoin('produk_m as pr', 'pr.id', '=', 'pp.produkfk')
            ->leftjoin('kelas_m as kl', 'kl.id', '=', 'apd.objectkelasfk')
            ->join('ruangan_m as ru', 'ru.id', '=', 'apd.objectruanganfk')
            ->leftjoin('strukpelayanan_t as sp', 'sp.norec', '=', 'pp.strukfk')
            ->leftjoin('strukbuktipenerimaan_t as sbm', 'sp.nosbmlastfk', '=', 'sbm.norec')
            ->leftjoin('strukresep_t as sr', 'sr.norec', '=', 'pp.strukresepfk')
            ->select(
                'pp.*',
                'pr.id as prid',
                'kl.id as klid',
                'kl.namakelas',
                'ru.id as ruid',
                'ru.namaruangan',
                'sp.nostruk',
                'sp.tglstruk',
                'apd.norec as norec_apd',
                'sbm.nosbm',
                'sp.norec as norec_sp',
                'pd.nocmfk',
                'apd.objectruanganfk',
                'pd.jenispelayanan',
                'pd.nostruklastfk',
                'pd.noregistrasi',
                'pd.tglregistrasi',
                'pd.norec as norec_pd',
                'pd.tglpulang',
                'pd.objectrekananfk as rekananid',
                'sp.totalharusdibayar',
                'sp.totalprekanan',
                'sp.totalbiayatambahan',
                'pd.kdprofile',
                'pd.statuspasien',
                'sr.ruanganfk',
                'pr.namaproduk'
            )
            ->where('pd.kdprofile', $idProfile)
            ->where('pd.norec',  $request['norec_pd']);

        $pelayanandetail = DB::table('pasiendaftar_t as pd')
            ->leftjoin('antrianpasiendiperiksa_t as apd', 'apd.noregistrasifk', '=', 'pd.norec')
            ->leftjoin('pelayananpasien_t as pp', 'pp.noregistrasifk', '=', 'apd.norec')
            ->leftjoin('pelayananpasiendetail_t as ppd', 'pp.norec', '=', 'ppd.pelayananpasien')
            ->select('ppd.*')
            ->where('pd.kdprofile', $idProfile)
            ->where('pd.norec',  $request['norec_pd']);

        if (isset($request['idruangan']) && $request['idruangan'] != "" && $request['idruangan'] != "undefined") {
            $pelayanan = $pelayanan->where('apd.objectruanganfk', '=', $request['idruangan']);
            $pelayanandetail = $pelayanandetail->where('apd.objectruanganfk', '=', $request['idruangan']);
        }

        if (isset($request['tglawal']) && $request['tglawal'] != "" && $request['tglawal'] != "undefined"  && $request['tglawal'] != "null") {
            $pelayanan = $pelayanan->where('pp.tglpelayanan', '>=', $request['tglawal']);
            $pelayanandetail = $pelayanandetail->where('pp.tglpelayanan', '>=', $request['tglawal']);
        }

        if (isset($request['tglakhir']) && $request['tglakhir'] != "" && $request['tglakhir'] != "undefined"  && $request['tglakhir'] != "null") {
            $pelayanan = $pelayanan->where('pp.tglpelayanan', '<=', $request['tglakhir']);
            $pelayanandetail = $pelayanandetail->where('pp.tglpelayanan', '<=', $request['tglakhir']);
        }

        $pelayanan->orderBy('pp.tglpelayanan');
        $pelayanan = $pelayanan->orderBy('pp.rke')->get();
        $pelayanandetail->orderBy('pp.tglpelayanan');
        $pelayanandetail = $pelayanandetail->orderBy('pp.rke')->get();

        if (count($pelayanan) > 0) {
            $details = array();
            foreach ($pelayanan as $value) {
                if ($value->produkfk == $this->settingFix('idProdukDeposit')) {
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

                $harga = (float)$value->hargajual;
                $diskon = (float)$value->hargadiscount;

                $value->harga = $harga;
                $value->diskon = $diskon;
                $value->total = (($harga - $diskon) * $value->jumlah) + $jasa;
                $value->strukfk = $value->nostruk . ' / ' . $value->nosbm;
                $value->jasa = $jasa;
                foreach ($pelayanandetail as $detail) {
                    if ($value->norec !== $detail->pelayananpasien) continue;
                    $value->detailpelayanan[] = $detail;
                }
                $details[] = $value;
            }
        }
        $arrHsil = array(
            'details' => $details
        );
        return $this->respond($arrHsil);
    }
    public function detailKonversiHargaDropdown(Request $request)
    {
        $res['kelompokpasien'] = KelompokPasien::mine()->get();
        $res['kelas'] = Kelas::mine()->get();
        return $this->respond($res);
    }
    public function konversiharga(Request $request)
    {

        DB::beginTransaction();
        try {
            $kdProfile = (int) $this->kdProfile;
            $noRegister = $request['noregistrasi'];
            $pelayananpasien = [];
            $pelayananpasiendetail = [];
            $SET['komponenHargaProfit'] = $this->settingFix('komponenHargaProfit');

            $datatagihan = $request['data'];

            foreach ($datatagihan as $data) {
                DB::table('pelayananpasien_temp_t')->where('noregistrasifk', $data['noregistrasifk'])->delete();
                DB::table('pelayananpasiendetail_temp_t')->where('noregistrasifk', $data['noregistrasifk'])->delete();
                if ($data['produkfk'] == $this->settingFix('idProdukDeposit')) {
                    continue;
                }
                if ($data['strukresepfk'] == null) {
                    // tindakan
                    $objetoRequest = new \Illuminate\Http\Request();
                    $objetoRequest['idRuangan'] =  $data['objectruanganfk'];
                    $objetoRequest['idKelas'] =    $request['idKelas'];
                    $objetoRequest['idProduk'] =  $data['produkfk'];
                    $objetoRequest['idJenisPelayanan'] =  $data['jenispelayanan'];
                    $objetoRequest['idPenjamin'] =  $request['idPenjamin'];

                    $datkonversi = $this->tindakanCtrl->listTindakanKomponen(
                        $objetoRequest,
                        true
                    );

                    if (count($datkonversi['komponen']) == 0) continue;
                    $jasa = 0;
                    if (isset($data['detailpelayanan'])) {

                        $detailpelayanan = $data['detailpelayanan'];

                        // return $datkonversi['komponen'];
                        // return $detailpelayanan;
                        $norecdetailprev = '';
                        foreach ($detailpelayanan as $detail) {
                            if (count($datkonversi['komponen']) == 0) continue;

                            foreach ($datkonversi['komponen'] as $komponen) {
                                if ($komponen->objectkomponenhargafk == $detail['komponenhargafk']) {
                                    if ($detail['norec'] === $norecdetailprev) continue;
                                    if ((float)$detail['hargajual'] > 0) {
                                        $jasa = ((float)$komponen->hargasatuan - (float)$detail['hargajual']) * ((float)$detail['jasa'] / (float)$detail['hargajual']);
                                        $jasa = $detail['jasa'] + $jasa;
                                    }

                                    $pelayananpasiendetail[] = [
                                        "norec" => $detail['norec'],
                                        "kdprofile" => $detail['kdprofile'],
                                        "statusenabled" => $detail['statusenabled'],
                                        "noregistrasifk" => $detail['noregistrasifk'],
                                        'tglregistrasi' => $detail['tglregistrasi'],
                                        "aturanpakai" => $detail['aturanpakai'],
                                        'generik' => $detail['generik'],
                                        "hargadiscount" => $detail['hargadiscount'],
                                        "hargajual" => $detail['hargajual'],
                                        "hargasatuan" => $detail['hargasatuan'],
                                        'jenisobatfk' => $detail['jenisobatfk'],
                                        "jumlah" => $detail['jumlah'],
                                        "keteranganlain" => $detail['keteranganlain'],
                                        "keteranganpakai2" => $detail['keteranganpakai2'],
                                        "komponenhargafk" => $detail['komponenhargafk'],
                                        "pelayananpasien" => $detail['pelayananpasien'],
                                        "piutangpenjamin" => $detail['piutangpenjamin'],
                                        "piutangrumahsakit" => $detail['piutangrumahsakit'],
                                        "produkfk" => $detail['produkfk'],
                                        'routefk' => $detail['routefk'],
                                        "stock" => $detail['stock'],
                                        "tglpelayanan" => $detail['tglpelayanan'],
                                        "harganetto" => $detail['harganetto'],
                                        "hargadijamin" => $detail['hargadijamin'],
                                        "jasa" => $jasa,
                                        "harganettokonversi" => $komponen->hargasatuan,
                                        "hargajualkonversi" => $komponen->hargasatuan,
                                        "hargasatuankonversi" => $komponen->hargasatuan,
                                    ];
                                }
                                $norecdetailprev = $detail['norec'];
                            }
                        }


                        $pelayananpasien[] = [
                            "norec" => $data['norec'],
                            "kdprofile" => $data['kdprofile'],
                            "statusenabled" => $data['statusenabled'],
                            "noregistrasifk" => $data['noregistrasifk'],
                            "tglregistrasi" => $data['tglregistrasi'],
                            'aturanpakai' => $data['aturanpakai'],
                            'generik' => $data['generik'],
                            "hargadiscount" => $data['hargadiscount'],
                            "hargajual" => $data['hargajual'],
                            "hargasatuan" => $data['hargasatuan'],
                            'jenisobatfk' => $data['jenisobatfk'],
                            "jumlah" => $data['jumlah'],
                            "kelasfk" => $data['kelasfk'],
                            "kdkelompoktransaksi" => $data['kdkelompoktransaksi'],
                            "keteranganlain" => $data['keteranganlain'],
                            "piutangpenjamin" => $data['piutangpenjamin'],
                            "piutangrumahsakit" => $data['piutangrumahsakit'],
                            "produkfk" => $data['produkfk'],
                            'routefk' => $data['routefk'],
                            "stock" => $data['stock'],
                            "tglpelayanan" => $data['tglpelayanan'],
                            "harganetto" => $data['harganetto'],
                            'jeniskemasanfk' => $data['jeniskemasanfk'],
                            'rke' => $data['rke'],
                            'strukresepfk' => $data['strukresepfk'],
                            'satuanviewfk' => $data['satuanviewfk'],
                            'nilaikonversi' => $data['nilaikonversi'],
                            'strukterimafk' => $data['strukterimafk'],
                            'dosis' => $data['dosis'],

                            'qtydetailresep' => $data['qtydetailresep'],
                            'isobat' => $data['isobat'],
                            'ispagi' => $data['ispagi'],
                            'issiang' => $data['issiang'],
                            'ismalam' => $data['ismalam'],
                            'issore' => $data['issore'],
                            'keteranganpakai' => $data['keteranganpakai'],
                            'iskronis' => $data['iskronis'],
                            "iscito" => $data['iscito'],
                            "isparamedis" => $data['isparamedis'],
                            "jenispelayananfk" => $data['jenispelayananfk'],
                            "istarifdetault" => $data['istarifdetault'],
                            "hargadijamin" => $data['hargadijamin'],
                            "istuslah" => $data['istuslah'],
                            'satuanresepfk' => $data['satuanresepfk'],
                            'tglkadaluarsa' => $data['tglkadaluarsa'],
                            "jasa" => $jasa,
                            "harganettokonversi" => $datkonversi['harga']->hargasatuan,
                            "hargajualkonversi" => $datkonversi['harga']->hargasatuan,
                            "hargasatuankonversi" => $datkonversi['harga']->hargasatuan,
                        ];
                    }
                } else {
                    //obat
                    $hargaupnya = 0;
                    $harganettonya = 0;
                    $nilaikonversi = $data['nilaikonversi'] == null ? 1 : (float)$data['nilaikonversi'];
                    $objetoRequest = new \Illuminate\Http\Request();
                    $objetoRequest['produkfk'] =  $data['produkfk'];
                    $objetoRequest['ruanganfk'] = $data['ruanganfk'];
                    $objetoRequest['kpid'] =  $request['idKelPasien'];
                    $objetoRequest['norec_apd'] =  $data['norec_apd'];
                    $datkonversi = $this->inputResepCtrl->getProdukDetail($objetoRequest, true);

                    if (count($datkonversi['detail']) == 0) continue;
                    foreach ($datkonversi['detail'] as $datakon) {
                        $hargaupnya = (float)$datakon['hargajual'] * $nilaikonversi;
                        $harganettonya = (float)$datakon['hargajual'];
                    }
                    $detailpelayanan = $data['detailpelayanan'];
                    foreach ($detailpelayanan as $detail) {
                        $pelayananpasiendetail[] = [
                            'norec' => $detail['norec'],
                            'kdprofile' => $detail['kdprofile'],
                            'statusenabled' => $detail['statusenabled'],
                            'noregistrasifk' => $detail['noregistrasifk'],
                            'tglregistrasi' => $detail['tglregistrasi'],
                            'aturanpakai' => $detail['aturanpakai'],
                            'generik' => $detail['generik'],
                            'hargadiscount' => $detail['hargadiscount'],
                            'hargajual' => $detail['hargajual'],
                            'hargasatuan' => $detail['hargasatuan'],
                            'jenisobatfk' => $detail['jenisobatfk'],
                            'jumlah' => $detail['jumlah'],
                            "keteranganlain" => $detail['keteranganlain'],
                            "keteranganpakai2" => $detail['keteranganpakai2'],
                            'komponenhargafk' => $detail['komponenhargafk'],
                            'pelayananpasien' => $detail['pelayananpasien'],
                            "piutangpenjamin" => $detail['piutangpenjamin'],
                            "piutangrumahsakit" => $detail['piutangrumahsakit'],
                            'produkfk' => $detail['produkfk'],
                            'routefk' => $detail['routefk'],
                            'stock' => $detail['stock'],
                            'tglpelayanan' => $detail['tglpelayanan'],
                            'harganetto' => $detail['harganetto'],
                            "hargadijamin" => $detail['hargadijamin'],
                            'jasa' => $detail['jasa'],
                            "harganettokonversi" => $detail['komponenhargafk'] == $SET['komponenHargaProfit'] ? $hargaupnya - $harganettonya : $hargaupnya,
                            "hargajualkonversi" => $detail['komponenhargafk'] == $SET['komponenHargaProfit'] ? $hargaupnya - $harganettonya : $hargaupnya,
                            "hargasatuankonversi" => $detail['komponenhargafk'] == $SET['komponenHargaProfit'] ? $hargaupnya - $harganettonya : $hargaupnya,
                        ];
                    }
                    $pelayananpasien[] = [
                        'norec' => $data['norec'],
                        'kdprofile' => $data['kdprofile'],
                        'statusenabled' => $data['statusenabled'],
                        'noregistrasifk' => $data['noregistrasifk'],
                        'tglregistrasi' => $data['tglregistrasi'],
                        'aturanpakai' => $data['aturanpakai'],
                        'generik' => $data['generik'],
                        'hargadiscount' => $data['hargadiscount'],
                        'hargajual' => $data['hargajual'],
                        'hargasatuan' => $data['hargasatuan'],
                        'jenisobatfk' => $data['jenisobatfk'],
                        'jumlah' => $data['jumlah'],
                        'kelasfk' => $data['kelasfk'],
                        'kdkelompoktransaksi' => $data['kdkelompoktransaksi'],
                        "keteranganlain" => $data['keteranganlain'],
                        "piutangpenjamin" => $data['piutangpenjamin'],
                        "piutangrumahsakit" => $data['piutangrumahsakit'],
                        'produkfk' => $data['produkfk'],
                        'routefk' => $data['routefk'],
                        'stock' => $data['stock'],
                        'tglpelayanan' => $data['tglpelayanan'],
                        'harganetto' => $data['harganetto'],
                        'jeniskemasanfk' => $data['jeniskemasanfk'],
                        'rke' => $data['rke'],
                        'strukresepfk' => $data['strukresepfk'],
                        'satuanviewfk' => $data['satuanviewfk'],
                        'nilaikonversi' => $data['nilaikonversi'],
                        'strukterimafk' => $data['strukterimafk'],
                        'dosis' => $data['dosis'],
                        'qtydetailresep' => $data['qtydetailresep'],
                        'isobat' => $data['isobat'],
                        'ispagi' => $data['ispagi'],
                        'issiang' => $data['issiang'],
                        'ismalam' => $data['ismalam'],
                        'issore' => $data['issore'],
                        'keteranganpakai' => $data['keteranganpakai'],
                        'iskronis' => $data['iskronis'],
                        "iscito" => $data['iscito'],
                        "isparamedis" => $data['isparamedis'],
                        "jenispelayananfk" => $data['jenispelayananfk'],
                        "istarifdetault" => $data['istarifdetault'],
                        "hargadijamin" => $data['hargadijamin'],
                        "istuslah" => $data['istuslah'],
                        'satuanresepfk' => $data['satuanresepfk'],
                        'tglkadaluarsa' => $data['tglkadaluarsa'],
                        'jasa' => $data['jasa'],
                        "harganettokonversi" => $hargaupnya,
                        "hargajualkonversi" => $hargaupnya,
                        "hargasatuankonversi" => $hargaupnya,
                    ];
                }
            }

            DB::table('pelayananpasien_temp_t')->insert($pelayananpasien);
            DB::table('pelayananpasiendetail_temp_t')->insert($pelayananpasiendetail);
            DB::commit();
            $datakonversi = $this->detailHasilKonversiHargaLayanan($kdProfile, $noRegister, $request['tglawal'], $request['tglakhir'], isset($request['idRuangan']) ? $request['idRuangan'] : 0);
            $transMessage = "Konversi data Berhasil.";
            $result = array(
                "status" => 200,
                "result" => array(
                    "details"  => $datakonversi,
                    "as" => '@epic',
                ),
            );
        } catch (Exception $e) {
            DB::rollBack();
            $transMessage = "Konversi data Gagal. " . (count($pelayananpasien) == 0 ? 'harga tujuan tidak ada' : '');
            $result = array(
                "status" => 400,
                "result"  => null

            );
        }

        return $this->respond($result['result'], $result['status'], $transMessage);
    }
    public function detailHasilKonversiHargaLayanan($idProfile, $noRegister, $tglawal, $tglakhir, $idRuangan)
    {
        $pelayanan = DB::table('pasiendaftar_t as pd')
            ->leftjoin('antrianpasiendiperiksa_t as apd', 'apd.noregistrasifk', '=', 'pd.norec')
            ->leftjoin('pelayananpasien_temp_t as pp', 'pp.noregistrasifk', '=', 'apd.norec')
            ->leftjoin('produk_m as pr', 'pr.id', '=', 'pp.produkfk')
            ->leftjoin('kelas_m as kl', 'kl.id', '=', 'apd.objectkelasfk')
            ->join('ruangan_m as ru', 'ru.id', '=', 'apd.objectruanganfk')
            ->leftjoin('strukpelayanan_t as sp', 'sp.norec', '=', 'pp.strukfk')
            ->leftjoin('strukbuktipenerimaan_t as sbm', 'sp.nosbmlastfk', '=', 'sbm.norec')
            ->leftjoin('strukresep_t as sr', 'sr.norec', '=', 'pp.strukresepfk')
            ->select(
                'pp.*',
                'pr.id as prid',
                'kl.id as klid',
                'kl.namakelas',
                'ru.id as ruid',
                'ru.namaruangan',
                'sp.nostruk',
                'sp.tglstruk',
                'apd.norec as norec_apd',
                'sbm.nosbm',
                'sp.norec as norec_sp',
                'pd.nocmfk',
                'apd.objectruanganfk',
                'pd.jenispelayanan',
                'pd.nostruklastfk',
                'pd.noregistrasi',
                'pd.tglregistrasi',
                'pd.norec as norec_pd',
                'pd.tglpulang',
                'pd.objectrekananfk as rekananid',
                'sp.totalharusdibayar',
                'sp.totalprekanan',
                'sp.totalbiayatambahan',
                'pd.kdprofile',
                'pd.statuspasien',
                'sr.ruanganfk',
                'pr.namaproduk'
            )
            ->where('pd.kdprofile', $idProfile)
            ->where('pd.noregistrasi', $noRegister);

        if (isset($tglawal) && $tglawal != "" && $tglawal != "undefined"  && $tglawal != "null") {
            $pelayanan = $pelayanan->where('pp.tglpelayanan', '>=', $tglawal);
        }
        if (isset($tglakhir) && $tglakhir != "" && $tglakhir != "undefined"  && $tglakhir != "null") {
            $pelayanan = $pelayanan->where('pp.tglpelayanan', '<=', $tglakhir);
        }
        if ($idRuangan != 0) {
            $pelayanan = $pelayanan->where('apd.objectruanganfk', $idRuangan);
        }
        $pelayanan = $pelayanan->orderBy('pp.tglpelayanan');
        $pelayanan = $pelayanan->orderBy('pp.rke');
        $pelayanan = $pelayanan->get();

        $pelayanandetail = DB::table('pasiendaftar_t as pd')
            ->leftjoin('antrianpasiendiperiksa_t as apd', 'apd.noregistrasifk', '=', 'pd.norec')
            ->leftjoin('pelayananpasien_temp_t as pp', 'pp.noregistrasifk', '=', 'apd.norec')
            ->leftjoin('pelayananpasiendetail_temp_t as ppd', 'pp.norec', '=', 'ppd.pelayananpasien')
            ->select('ppd.*')
            ->where('pd.kdprofile', $idProfile)
            ->where('pd.noregistrasi', $noRegister);

        if (isset($tglawal) && $tglawal != "" && $tglawal != "undefined"  && $tglawal != "null") {
            $pelayanandetail = $pelayanandetail->where('pp.tglpelayanan', '>=', $tglawal);
        }
        if (isset($tglakhir) && $tglakhir != "" && $tglakhir != "undefined"  && $tglakhir != "null") {
            $pelayanandetail = $pelayanandetail->where('pp.tglpelayanan', '<=', $tglakhir);
        }

        $pelayanandetail = $pelayanandetail->orderBy('pp.tglpelayanan');
        $pelayanandetail = $pelayanandetail->orderBy('pp.rke');
        $pelayanandetail = $pelayanandetail->get();

        $details = array();
        if (count($pelayanan) > 0) {
            foreach ($pelayanan as $value) {
                if ($value->produkfk == $this->settingFix('idProdukDeposit')) {
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

                $harga = (float)$value->hargajual;
                $hargakonversi = (float)$value->hargajualkonversi;
                $diskon = (float)$value->hargadiscount;

                $value->harga = $harga;
                $value->diskon = $diskon;
                $value->total = (($harga - $diskon) * $value->jumlah) + $jasa;
                $value->strukfk = $value->nostruk . ' / ' . $value->nosbm;
                $value->jasa = $jasa;
                $value->hargakonversi = $hargakonversi;
                $value->totalkonversi = (($hargakonversi - $diskon) * $value->jumlah) + $jasa;
                foreach ($pelayanandetail as $detail) {
                    if ($value->norec !== $detail->pelayananpasien) continue;
                    $value->detailpelayanan[] = $detail;
                }
                $details[] = $value;
            }
        }

        return $details;
    }

    public function simpankonversiharga(Request $request)
    {

        DB::beginTransaction();
        try {
            $kdProfile = $this->kdProfile;
            $norecpp = [];
            $jasa = [];
            $hargajual = [];
            $hargasatuan = [];
            $harganetto = [];
            $norec_apd = '';

            $datatagihan = $request['data'];
            foreach ($datatagihan as $data) {
                if ($data['produkfk'] == $this->settingFix('idProdukDeposit')) {
                    continue;
                }

                $jasa[] = "WHEN '{$data['norec']}' then " . (is_null($data['jasa']) ? 0 : $data['jasa']);
                $hargajual[] = "WHEN '{$data['norec']}' then " . (is_null($data['hargajualkonversi']) ? 0 : $data['hargajualkonversi']);
                $hargasatuan[] = "WHEN '{$data['norec']}' then " . (is_null($data['hargasatuankonversi']) ? 0 : $data['hargasatuankonversi']);
                $harganetto[] = "WHEN '{$data['norec']}' then " . (is_null($data['harganettokonversi']) ? 0 : $data['harganettokonversi']);
                $norecpp[] = "'" . $data['norec'] . "'";

                $norecppd = [];
                $jasad = [];
                $hargajuald = [];
                $hargasatuand = [];
                $harganettod = [];

                $detailpelayanan = $data['detailpelayanan'];
                foreach ($detailpelayanan as $detail) {
                    $jasad[] = "WHEN '{$detail['norec']}' then " . (is_null($detail['jasa']) ? 0 : $detail['jasa']);
                    $hargajuald[] = "WHEN '{$detail['norec']}' then " . (is_null($detail['hargajualkonversi']) ? 0 : $detail['hargajualkonversi']);
                    $hargasatuand[] = "WHEN '{$detail['norec']}' then " . (is_null($detail['hargasatuankonversi']) ? 0 : $detail['hargasatuankonversi']);
                    $harganettod[] = "WHEN '{$detail['norec']}' then " . (is_null($detail['harganettokonversi']) ? 0 : $detail['harganettokonversi']);
                    $norecppd[] = "'" . $detail['norec'] . "'";
                }
                $norecppd = implode(',', $norecppd);
                $hargajuald = implode(' ', $hargajuald);
                $hargasatuand = implode(' ', $hargasatuand);
                $harganettod = implode(' ', $harganettod);
                $jasad = implode(' ', $jasad);
                if (!empty($norecppd)) {
                    DB::update("UPDATE pelayananpasiendetail_t SET jasa = CASE norec {$jasad} END, hargajual = CASE norec {$hargajuald} END, hargasatuan = CASE norec {$hargasatuand} END, harganetto = CASE norec {$harganettod} END WHERE kdprofile = $kdProfile and norec in ({$norecppd})");
                }
                $norec_apd = $data['norec_apd'];
            }
            $norecpp = implode(',', $norecpp);
            $hargajual = implode(' ', $hargajual);
            $hargasatuan = implode(' ', $hargasatuan);
            $harganetto = implode(' ', $harganetto);
            $jasa = implode(' ', $jasa);
            if (!empty($norecpp)) {
                DB::update("UPDATE pelayananpasien_t SET jasa = CASE norec {$jasa} END, hargajual = CASE norec {$hargajual} END, hargasatuan = CASE norec {$hargasatuan} END, harganetto = CASE norec {$harganetto} END WHERE kdprofile = $kdProfile and  norec in ({$norecpp})");
                $updateapd = DB::table('antrianpasiendiperiksa_t')->where('norec', $norec_apd)->update(['objectkelasfk' => $request['idKelas']]);
            }
            DB::commit();
            $transMessage = "Konversi data Berhasil.";
            $result = array(
                "status" => 200,
                "result" => array(
                    "as" => '@epic',
                ),
            );
        } catch (Exception $e) {
            DB::rollBack();
            $transMessage = "Konversi data Gagal.";
            $result = array(
                "status" => 400,
                "result"  => $e->getMessage()
            );
        }

        return $this->respond($result['result'], $result['status'], $transMessage);
    }


    // Bridging BPD
    // public function ws_tagihan_insert(Request $request)
    // {
    //     //set_time_limit(30);
    //     $response = $this->bpdService->ws_tagihan_insert(
    //         $this->generateCode(new BPDCheckout, 'noid', 10, $this->getDateTime()->format('ymd'), 36),
    //         $request['nama'],
    //         $request['tagihan'],
    //         $request['ket_1_val'],
    //         $request['ket_2_val'],
    //         $request['ket_3_val'],
    //         $request['ket_4_val'],
    //     );

    //     $jsonData = json_decode($response, true);

    //     if($jsonData['code'] == 00){
    //         $bpdCheckout = new BPDCheckout();
    //         $bpdCheckout->noregistrasi = $jsonData['data'][0]['Nomor Registrasi'];
    //         $bpdCheckout->statusenabled = true;
    //         $bpdCheckout->noid = $jsonData['data'][0]['Nomor Billing'];
    //         $bpdCheckout->instansi = $jsonData['data'][0]['instansi'];
    //         $bpdCheckout->nama = $jsonData['data'][0]['nama'];
    //         $bpdCheckout->tagihan = $request['tagihan'];
    //         $bpdCheckout->tglpulang = $jsonData['data'][0]['Tanggal Pulang'];
    //         $bpdCheckout->alamat = $jsonData['data'][0]['Alamat'];
    //         $bpdCheckout->notelp = $jsonData['data'][0]['Nomor Telepon'];
    //         $bpdCheckout->tanggal_transaksi = $this->dateNow;
    //         $bpdCheckout->kdprofile = 36;
    //         $bpdCheckout->save();

    //         $transMessage = $jsonData['message'];

    //         $result = array(
    //             "status" => 201,
    //             "response" => $jsonData,
    //             "message" => $jsonData['message'],
    //             "data" => $jsonData['data'][0],
    //             "as" => 'kadekapoer'
    //         );

    //         $bpdLog = new BPDLog();
    //         $bpdLog->tgl_log = $this->dateNow;
    //         $bpdLog->user_id = $this->getCurrentUserID();
    //         $bpdLog->fungsi = 'ws_tagihan_insert';
    //         $bpdLog->noid = $jsonData['data'][0]['Nomor Billing'];
    //         $bpdLog->response = $response;
    //         $bpdLog->code = $jsonData['code'];
    //         $bpdLog->message = $jsonData['message'];
    //         $bpdLog->save();

    //         return $this->setStatusCode($result['status'])->respond($result, $transMessage);

    //     } else {
    //         $jsonData = json_decode($response, true);

    //         $transMessage = $jsonData['message'];

    //         $result = array(
    //             "status" => 400,
    //             "response" => $jsonData,
    //             "message" => $jsonData['message'],
    //             "data" => $jsonData['data'],
    //             "as" => 'kadekapoer'
    //         );

    //         $bpdLog = new BPDLog();
    //         $bpdLog->tgl_log = $this->dateNow;
    //         $bpdLog->user_id = $this->getCurrentUserID();
    //         $bpdLog->fungsi = 'ws_tagihan_insert';
    //         //$bpdLog->response = $response;
    //         $bpdLog->code = $jsonData['code'];
    //         $bpdLog->message = $jsonData['message'];
    //         $bpdLog->save();

    //         return $this->setStatusCode($result['status'])->respond($result, $transMessage);
    //     }

    // }

    public function getDataBPD(Request $request)
    {
        if (isset($request['tglAwal'])) {
            $data = \DB::table('bpdcheckout_t as bc')
                ->select('bc.*')
                ->where('bc.statusenabled', true)
                ->whereBetween('bc.tanggal_transaksi', [$request['tglAwal'], $request['tglAkhir']])
                ->orderBy('bc.tanggal_transaksi', 'DESC')
                ->get();
        } else {
            $data = \DB::table('bpdcheckout_t as bc')
                ->select('bc.*')
                ->where('bc.statusenabled', true)
                ->where('bc.noregistrasi', $request['noregistrasi'])
                ->orderBy('bc.tanggal_transaksi', 'DESC')
                ->get();
        }

        return $this->respond($data);
    }

    public function postLog(Request $request)
    {
        $bpdLog = new BPDLog();
        $bpdLog->tgl_log = $this->dateNow;
        $bpdLog->user_id = $this->getCurrentUserID();
        $bpdLog->fungsi = $request['fungsi'];
        // $bpdLog->noid = $request['noid'];
        // $bpdLog->response = $response;
        // $bpdLog->code = $jsonData['code'];
        $bpdLog->message = $request['message'];
        $bpdLog->save();
    }

    // public function ws_tagihan_delete_by_id(Request $request)
    // {
    //     set_time_limit(30);
    //     $inquiry = $this->bpdService->ws_inquiry_tagihan($request['noid']);
    //     $dataInquiry = json_decode($inquiry, true);

    //     if($dataInquiry['data'][0]['sts_bayar'] == '0'){
    //         $response = $this->bpdService->ws_tagihan_delete_by_id($request['noid']);

    //         $jsonData = json_decode($response, true);

    //         if($jsonData['code'] == 00){

    //             BPDCheckout::where('noid', $request['noid'])->update([
    //                 'statusenabled' => false
    //             ]);

    //             $transMessage = 'Tagihan berhasil dihapus';

    //             $result = array(
    //                 "status" => 201,
    //                 "response" => $jsonData,
    //                 "message" => $jsonData['message'],
    //                 "data" => $jsonData['data'],
    //                 "as" => 'kadekapoer'
    //             );

    //             $bpdLog = new BPDLog();
    //             $bpdLog->tgl_log = $this->dateNow;
    //             $bpdLog->user_id = $this->getCurrentUserID();
    //             $bpdLog->fungsi = 'ws_tagihan_delete_by_id';
    //             $bpdLog->noid = $request['noid'];
    //             $bpdLog->response = $response;
    //             $bpdLog->code = $jsonData['code'];
    //             $bpdLog->message = $jsonData['message'];
    //             $bpdLog->save();

    //             return $this->setStatusCode($result['status'])->respond($result, $transMessage);
    //         } else {

    //             $transMessage = $jsonData['message'];

    //             $result = array(
    //                 "status" => 400,
    //                 "response" => $jsonData,
    //                 "message" => $jsonData['message'],
    //                 "data" => $jsonData['data'],
    //                 "as" => 'kadekapoer'
    //             );

    //             $bpdLog = new BPDLog();
    //             $bpdLog->tgl_log = $this->dateNow;
    //             $bpdLog->user_id = $this->getCurrentUserID();
    //             $bpdLog->fungsi = 'ws_tagihan_delete_by_id';
    //             $bpdLog->noid = $request['noid'];
    //             $bpdLog->response = $response;
    //             $bpdLog->code = $jsonData['code'];
    //             $bpdLog->message = $jsonData['message'];
    //             $bpdLog->save();

    //             return $this->setStatusCode($result['status'])->respond($result, $transMessage);
    //         }
    //     } else {
    //         $transMessage = 'Tagihan sudah lunas, tidak bisa dihapus!';

    //         $result = array(
    //             "status" => 400,
    //             "response" => $jsonData,
    //             "message" => $jsonData['message'],
    //             "data" => $jsonData['data'],
    //             "as" => 'kadekapoer'
    //         );

    //         $bpdLog = new BPDLog();
    //         $bpdLog->tgl_log = $this->dateNow;
    //         $bpdLog->user_id = $this->getCurrentUserID();
    //         $bpdLog->fungsi = 'ws_tagihan_delete_by_id';
    //         $bpdLog->noid = $request['noid'];
    //         $bpdLog->response = $response;
    //         $bpdLog->code = $jsonData['code'];
    //         $bpdLog->message = $jsonData['message'];
    //         $bpdLog->save();

    //         return $this->setStatusCode($result['status'])->respond($result, $transMessage);
    //     }


    // }

    // public function ws_inquiry_tagihan(Request $request)
    // {
    //     try{
    //         set_time_limit(30);
    //         $response = $this->bpdService->ws_inquiry_tagihan($request['noid']);

    //         $jsonData = json_decode($response, true);

    //         if($jsonData['code'] == 00 && $jsonData['data'][0]['sts_bayar'] == 1){

    //             BPDCheckout::where('noid', $request['noid'])->update([
    //                 'status_bayar' => 1,
    //                 'tanggal_bayar' => $jsonData['data'][0]['tgl_upd']
    //             ]);

    //             $transMessage = 'Tagihan Lunas';

    //             $result = array(
    //                 "status" => 201,
    //                 "response" => $jsonData,
    //                 "message" => $jsonData['message'],
    //                 "data" => $jsonData['data'][0],
    //                 "as" => 'kadekapoer'
    //             );

    //             $bpdLog = new BPDLog();
    //             $bpdLog->tgl_log = $this->dateNow;
    //             $bpdLog->user_id = $this->getCurrentUserID();
    //             $bpdLog->fungsi = 'ws_inquiry_tagihan';
    //             $bpdLog->noid = $jsonData['data'][0]['Nomor Billing'];
    //             $bpdLog->response = $response;
    //             $bpdLog->code = $jsonData['code'];
    //             $bpdLog->message = $jsonData['message'];
    //             $bpdLog->save();

    //             return $this->setStatusCode($result['status'])->respond($result, $transMessage);
    //         } else {
    //             $jsonData = json_decode($response, true);

    //             $transMessage = 'Tagihan belum lunas';

    //             $result = array(
    //                 "status" => 400,
    //                 "response" => $jsonData,
    //                 "message" => $jsonData['message'],
    //                 "data" => $jsonData['data'],
    //                 "as" => 'kadekapoer'
    //             );

    //             $bpdLog = new BPDLog();
    //             $bpdLog->tgl_log = $this->dateNow;
    //             $bpdLog->user_id = $this->getCurrentUserID();
    //             $bpdLog->fungsi = 'ws_inquiry_tagihan';
    //             if(count($jsonData['data']) != 0){
    //                 $bpdLog->noid = $jsonData['data'][0]['Nomor Billing'];
    //             }
    //             $bpdLog->response = $response;
    //             $bpdLog->code = $jsonData['code'];
    //             $bpdLog->message = $jsonData['message'];
    //             $bpdLog->save();

    //             return $this->setStatusCode($result['status'])->respond($result, $transMessage);
    //         }
    //     } catch (\Exception $e) {
    //         throw new \Exception('Waktu eksekusi melebihi batas 1 detik.');
    //     }

    // }

}