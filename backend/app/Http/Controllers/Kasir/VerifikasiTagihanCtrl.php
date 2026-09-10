<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use App\Models\Master\Kelas;
use App\Models\Transaksi\AntrianPasienDiperiksa;
use App\Models\Transaksi\PasienDaftar;
use App\Models\Transaksi\PelayananPasien;
use App\Models\Transaksi\PelayananPasienDetail;
use App\Models\Transaksi\StrukBuktiPenerimaan;
use App\Models\Transaksi\StrukBuktiPengeluaran;
use App\Models\Transaksi\StrukPelayanan;
use App\Models\Transaksi\StrukPelayananPenjamin;
use App\Models\Transaksi\StrukPelayananPenjaminDetail;
use App\Traits\Valet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;

class VerifikasiTagihanCtrl extends Controller
{
    use Valet;
    public function __construct()
    {
        parent::__construct($is_encrypt = true);
    }
    public function dataTagihan(Request $r)
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
            ->leftJOIN('strukresep_t as sr', 'sr.norec', '=', 'pp.strukresepfk')
            ->leftJOIN('pegawai_m as pg', 'pg.id', '=', 'sr.penulisresepfk')
            // ->leftJoin('pegawai_m as pg2', 'pg2.id','=','pp.pelayananpegawaifk')
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
                DB::raw("
                case when pp.jasa is not null then pp.jasa else 0 end jasa,
                case when pp.hargadiscount is not null then pp.hargadiscount else 0 end hargadiscount,
                (
                    ROUND((pp.hargasatuan  - case when pp.hargadiscount is null then 0 else pp.hargadiscount end)
                     * pp.jumlah)
                + (case when pp.jasa is not null then pp.jasa else 0 end))
                 as total,
                to_char(pp.tglpelayanan,'yyyy-MM-dd')  as tglpelayanan_group ,
                case when pp.strukresepfk is null then 'Layanan' else 'Resep' end as jenis,
                case when pp.strukfk is null then 'Belum Verifikasi' else 'Verifikasi' end as statusverifikasi
               "),

            )
            ->where('pp.statusenabled', true)
            ->where('pp.kdprofile', $kdProfile)
            //->whereNull('pp.strukfk')
            ->where('apd.noregistrasifk', $r['norec_pd']);
        // if (isset($r['strukfk']) && $r['strukfk'] != '' && $r['strukfk'] == null) {
        //     $data = $data->whereNull('pp.strukfk');
        // }
        $data = $data->orderByDesc('pp.tglpelayanan');

        $datakronis = DB::table('pelayananpasienobatkronis_t as pp')
            ->join('antrianpasiendiperiksa_t as apd', 'apd.norec', '=', 'pp.noregistrasifk')
            ->join('kelas_m as kls', 'kls.id', '=', 'apd.objectkelasfk')
            ->join('produk_m as prd', 'prd.id', '=', 'pp.produkfk')
            ->join('ruangan_m as ru', 'ru.id', '=', 'apd.objectruanganfk')
            ->leftJOIN('strukresep_t as sr', 'sr.norec', '=', 'pp.strukresepfk')
            ->leftJOIN('pegawai_m as pg', 'pg.id', '=', 'sr.penulisresepfk')
            // ->leftJoin('pegawai_m as pg2', 'pg2.id','=','pp.pelayananpegawaifk')
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
                DB::raw("
                case when pp.jasa is not null then pp.jasa else 0 end jasa,
                case when pp.hargadiscount is not null then pp.hargadiscount else 0 end hargadiscount,
                (
                    ROUND((pp.hargasatuan  - case when pp.hargadiscount is null then 0 else pp.hargadiscount end)
                     * pp.jumlah)
                + (case when pp.jasa is not null then pp.jasa else 0 end))
                 as total,
                to_char(pp.tglpelayanan,'yyyy-MM-dd')  as tglpelayanan_group ,
                case when pp.strukresepfk is null then 'Layanan' else 'Resep' end as jenis,
                case when pp.strukfk is null then 'Belum Verifikasi' else 'Verifikasi' end as statusverifikasi
               "),

            )
            ->where('pp.statusenabled', true)
            ->where('pp.kdprofile', $kdProfile)
            ->where('pp.harganetto', '>', 0)
            ->where('pp.jumlah', '>', 0)
            //->whereNull('pp.strukfk')
            ->where('apd.noregistrasifk', $r['norec_pd']);
        // if (isset($r['strukfk']) && $r['strukfk'] != '' && $r['strukfk'] == null) {
        //     $data = $data->whereNull('pp.strukfk');
        // }
        $datakronis = $datakronis->orderByDesc('pp.tglpelayanan');

        $data = $data->union($datakronis)->get();

        // $data = $data->get();

        $pelayananpetugas = DB::table('pelayananpasienpetugas_t as ptu')
            ->leftjoin('pegawai_m as pg', 'pg.id', '=', 'ptu.objectpegawaifk')
            ->leftjoin('pelayananpasien_t as pp', 'pp.norec', '=', 'ptu.pelayananpasien')
            ->leftJoin('pegawai_m as pg2', DB::raw('CAST(pp.pelayananpegawaifk AS INT)'), '=', 'pg2.id')
            ->select('ptu.pelayananpasien', 'pg.namalengkap', 'pg.id as iddokterpemeriksa', 'pg2.namalengkap as pemeriksa', 'pg2.id as idpemeriksa')
            ->where('ptu.kdprofile', $kdProfile)
            // ->where('ptu.objectjenispetugaspefk', $sDokterPemeriksa)
            ->where('ptu.noregistrasi', $pd->noregistrasi)
            ->get();
        // return $pelayananpetugas;
        $result['total'] = 0;
        $result['deposit'] = 0;
        $result['diskon'] = 0;
        $result['dibayar'] = 0;
        $result['sisa'] = 0;

        $sama = false;
        $group  = [];
        foreach ($data as $item) {
            // if($item->strukresepfk != null) {
            //     $item->dokterpemeriksa = $item->penulisresep;
            // }else if($item->strukresepfk == null && isset($item->pelaksana)) {
            //     $item->dokterpemeriksa = $item->pelaksana;
            // }else {
            //     $item->dokterpemeriksa = '';
            // }
            $item->dokterpemeriksa = $item->strukresepfk != null ? $item->penulisresep : '-';
            $item->pemeriksa = null;
            $item->objectpemeriksa = null;
            $item->iddokterpemeriksa = null;
            $item->checked = false;
            $result['total']  = $result['total']  + (float) $item->total;
            $result['diskon']  = $result['diskon']  + (float) $item->hargadiscount;
            foreach ($pelayananpetugas as $itemd) {
                if ($itemd->pelayananpasien == $item->norec) {
                    $item->dokterpemeriksa = $itemd->namalengkap;
                    $item->iddokterpemeriksa = $itemd->iddokterpemeriksa;
                    $item->pemeriksa = $itemd->pemeriksa;
                    $item->objectpemeriksa = $itemd->idpemeriksa;
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

        // $sbms = StrukBuktiPenerimaan::where('noregistrasi', $pd->noregistrasi)->get()->pluck();
        $sbms = DB::table('strukbuktipenerimaan_t as sbm')
        ->select('cb.carabayar')
        ->leftJoin('strukbuktipenerimaancarabayar_t as sbmcr', 'sbmcr.nosbmfk', 'sbm.norec')
        ->leftJoin('carabayar_m as cb', 'cb.id', 'sbmcr.objectcarabayarfk')
        ->where('sbm.noregistrasi', $pd->noregistrasi)
        ->where('sbm.statusenabled', true)
        ->where('sbmcr.statusenabled', true)
        ->get();
        $getSP = StrukPelayanan::where('noregistrasifk', $pd->norec)->where('statusenabled', true)->first();
        $result['klaim']  = StrukPelayanan::totalKlaim($pd->noregistrasi);
        $result['verif']  = StrukPelayanan::totalVerif($pd->noregistrasi);
        $result['pengembalian'] = StrukBuktiPengeluaran::totalPengembalian($pd->noregistrasi);
        $result['deposit'] = StrukBuktiPenerimaan::deposit($pd->noregistrasi, true) - $result['pengembalian'];
        $result['carabayar'] = $sbms;
        $result['deposit'] =  $result['deposit'] < 0 ? 0 : $result['deposit'];
        $result['dibayar'] = StrukBuktiPenerimaan::totalBayar($pd->noregistrasi);
        $result['sisa'] =   $result['total']  -  $result['dibayar'] - $result['deposit']; // -  $result['diskon'];
        $result['length'] = count($data);
        $result['list_ruangan'] = AntrianPasienDiperiksa::listRuangan($pd->noregistrasi);
        $result['detail'] = $group; //collect($data)->groupBy('tglpelayanan_group')->sortByDesc('tglpelayanan_group');
        $result['iurbayar'] = isset($getSP) ? $getSP->totaliurbayar : 0;
        $result['as'] = '@epic';

        return $this->respond($result);
    }
    public function simpanVerifikasiTagihan(Request $r)
    {

        $kdProfile = $this->kdProfile;
        if(isset($r['isclosingzero']) && $r['isclosingzero']) {
            return $this->verifTagihanNol($r);
        }
        DB::beginTransaction();
        try {
            $pelayanan = PelayananPasien::where('noregistrasi', $r['noregistrasi'])
                ->where('kdprofile', $kdProfile)
                ->whereNull('strukfk')
                ->get();
            if (count($pelayanan) == 0) {
                $result = array(
                    "status" => 400,
                    "result"  => 'Pelayanan yang dilakukan pasien tidak ada'
                );
                return $this->respond($result, $result['status'], $result['result']);
                // abort(400, '');
            }
            $PD = PasienDaftar::where('noregistrasi', $r['noregistrasi'])
            ->where('kdprofile', $kdProfile)
            ->first();
            $checkSP = StrukPelayanan::where('noregistrasifk', $PD->norec)->where('nostruk', 'not ilike', 'D%' )->first();
            if(isset($checkSP) && $checkSP->nostruk) {
                $noStruk = $checkSP->nostruk;
            }else {
                $noStruk = $this->SEQUENCE(new StrukPelayanan(), 'noverifikasi_tagihan', 10, 'S', $kdProfile);
                if ($noStruk == '') {
                    abort(400, 'SEQ ERROR');
                }
            }

            $ruang = DB::table('ruangan_m')
            ->where('id', $PD->objectruanganlastfk)
            ->first();

            $filterDepart = explode(',', $this->settingFix('kdDepartemenRawatJalanFix'));
            if(in_array($ruang->objectdepartemenfk, $filterDepart)) {
                if(!isset($PD->tglpulang) || $PD->tglpulang == "" || $PD->tglpulang == null) {
                    $PD->tglpulang = date('Y-m-d H:i:s');
                }
            }else {
                if(!isset($PD->tglpulang) ) {
                    $result = array(
                        "status" => 400,
                        "result"  => 'Pasien belum dipulangkan'
                    );
                    return $this->respond($result, $result['status'], $result['result']);
                }
            }
            if(!isset($PD->tglclosing)) {
                $PD->tglclosing = date('Y-m-d H:i:s');
            }
            $PD->isclosing = true; 
            $PD->save();

            // $totalBilling = 0;
            $totalKlaim = (float)$r['klaim'];
            // $pelayananH = PelayananPasien::where('noregistrasi', $r['noregistrasi'])
            // ->where('kdprofile', $kdProfile)
            // ->get();
            // foreach ($pelayananH as $pel) {
            //     $harga = ($pel->hargajual == null) ? 0 : $pel->hargajual;
            //     $diskon = ($pel->hargadiscount == null) ? 0 : $pel->hargadiscount;
            //     $totalBilling += (($harga - $diskon) * $pel->jumlah) + $pel->jasa;
            // }

            $SP = new StrukPelayanan();
            $SP->norec = $SP->generateNewId();
            $SP->kdprofile = $kdProfile;
            $SP->statusenabled = true;
            $SP->nocmfk = $PD->nocmfk;
            $SP->noregistrasifk = $PD->norec;
            $SP->noregistrasi = $PD->noregistrasi;
            $SP->objectkelaslastfk = $PD->objectkelasfk;
            $SP->objectkelompoktransaksifk = $this->kelompokTransaksi('VERIFIKASI TAGIHAN PASIEN');
            $SP->objectpegawaipenerimafk = $this->getPegawaiId();
            $SP->nostruk = $noStruk;
            $SP->totalhargasatuan = (float)$r['total'];
            $SP->totalharusdibayar = (float)$r['totalbayar'];
            $SP->tglstruk = isset($checkSP) ? $checkSP->tglstruk : $this->now();
            $SP->objectruanganfk = $PD->objectruanganlastfk;
            $SP->totalprekanan = $totalKlaim;
            $SP->totaliurbayar =  (float)$r['totaliurbayar'];
            $SP->totalbebanrs =  (float)$r['totalbebanrs'];
            $SP->totalkeuntunganrs =  (float)$r['totalkeuntunganrs'];
            $SP->kodingkelashak = $r['kodingkelashak'] ?? null;
            $SP->kodingkelasnaik = $r['kodingkelasnaik'] ?? null;
            $SP->kodingplafonvalue = $r['kodingplafonvalue'] ?? null;
            $SP->kodingplafontotal = $r['kodingplafontotal'] ?? null;
            $SP->kodingdiagnosa = $r['kodingdiagnosa'] ?? null;
            $SP->save();

            // ini_set('max_execution_time', 10000);
            try {
                $chkNorec = collect($r['details'])->pluck('norec')->toArray();
                $sqlPelayananPasien = "UPDATE pelayananpasien_t SET strukfk = ? WHERE norec IN (" . implode(',', array_fill(0, count($chkNorec), '?')) . ") AND kdprofile = ?";
                $sqlPelayananPasienDetail = "UPDATE pelayananpasiendetail_t SET strukfk = ? WHERE pelayananpasien IN (" . implode(',', array_fill(0, count($chkNorec), '?')) . ") AND kdprofile = ?";
    
                $bindings = array_merge([$SP->norec], $chkNorec, [$kdProfile]);
    
                \DB::update($sqlPelayananPasien, $bindings);
                \DB::update($sqlPelayananPasienDetail, $bindings);
            } catch (\Exception $e) {
                $transMessage =  $e->getCode() == 404 ? $e->getMessage() : "Simpan Gagal";
                DB::rollBack();
                $result = array(
                    "status" => 400,
                    "result"  => $e->getMessage() . '<br />' . $e->getLine()
                );
                return $this->respond($result['result'], $result['status'], $transMessage);
            }

            // $norecChunks = array_chunk($chkNorec, 100); // Berat cuy kalo ga di chunk
            // foreach ($norecChunks as $chunk) {
            //     PelayananPasien::whereIn('norec', $chunk)
            //         ->where('kdprofile', $kdProfile)
            //         ->update(['strukfk' => $SP->norec]);
            //     PelayananPasienDetail::whereIn('pelayananpasien', $chunk)
            //         ->where('kdprofile', $kdProfile)
            //         ->update(['strukfk' => $SP->norec]);
            // }


            if ($totalKlaim > 0) {
                $SPP = new StrukPelayananPenjamin();
                $SPP->norec = $SPP->generateNewId();
                $SPP->statusenabled = true;
                $SPP->kdprofile = $kdProfile;
                $SPP->kdkelompokpasien = $PD->objectkelompokpasienlastfk;
                $SPP->kdrekananpenjamin = $PD->objectrekananfk;
                $SPP->totalbiaya = (float)$r['totalbayar'] + $totalKlaim + (float)$r['deposit'];
                $SPP->totalsudahppenjamin = $totalKlaim;
                $SPP->totalsisaharusdibayar = $totalKlaim;
                $SPP->totalppenjamin = $totalKlaim;
                $SPP->totalharusdibayar = $totalKlaim;
                $SPP->totalsudahdibayar = 0;
                $SPP->totalsudahdibebaskan = 0;
                $SPP->totalsisapiutang = $totalKlaim;
                $SPP->totaldibayarlebih = 0;
                $SPP->nostrukfk = $SP->norec;
                $PD->nostruklastfk = $SP->norec;
                $SPP->save();
            }
            $PD->statusbayar = 'Verifikasi';
            if ($r['totalbayar'] == 0) {
                $PD->statusbayar = 'Lunas';
            }
            $PD->nostruklastfk = $SP->norec;

            $PD->save();
            if (isset($r['multipenjamin']) && count($r['multipenjamin']) > 0) {
                $reqDetail = $r['multipenjamin'];
                foreach ($reqDetail as $values) {
                    $SPPenjaminDet = new StrukPelayananPenjaminDetail();
                    $SPPenjaminDet->norec = $SPPenjaminDet->generateNewId();
                    $SPPenjaminDet->statusenabled = true;
                    $SPPenjaminDet->kdprofile = $kdProfile;
                    $SPPenjaminDet->nostrukfk = $SP->norec;
                    $SPPenjaminDet->totalppenjamin = $values['total'];
                    $SPPenjaminDet->totalharusdibayar = $values['total'];
                    $SPPenjaminDet->keteranganlainnya = 'Multi Penjamin';
                    $SPPenjaminDet->kdrekananpenjamin = $values['penjaminfk'];
                    $SPPenjaminDet->strukpelayananpenjaminfk = $SPP->norec;
                    $SPPenjaminDet->kdkelompokpasien = $values['kelompokpasienfk'];
                    $SPPenjaminDet->save();
                }
            }

            $this->LOGGING(
                'Verifikasi Tagihan',
                $SP->norec,
                'strukpelayanan_t',
                'Verifikasi Tagihan pada Pasien ' .
                    $r['namapasien'] .
                    ' (' . $r['nocm'] . ') - ' .
                    $SP->nostruk
            );
            $transStatus = true;
        } catch (\Exception $e) {
            $transStatus = false;
        }

        if ($transStatus) {
            $transMessage = "Sukses";
            DB::commit();
            $ihs = null;
            $objetoRequest = new \Illuminate\Http\Request();
            $objetoRequest['noregistrasi'] = $PD->noregistrasi;
            if($PD->ihs_diagnosis != null){
                $objetoRequest['diagnosis']=  json_decode($PD->ihs_diagnosis);
            }
            $ihs = app('App\Http\Controllers\Bridging\SATUSEHATCtrl')->Encounter($objetoRequest, true);
            $result = array(
                "status" => 200,
                "result" => array(
                    "sp"  => $SP,
                    "pd" => $PD,
                    "Encounter"=>$ihs,
                    "as" => '@epic',
                ),
            );
        } else {
            $transMessage =  $e->getCode() == 404 ? $e->getMessage() : "Simpan Gagal";
            DB::rollBack();
            $result = array(
                "status" => 400,
                "result"  => $e->getMessage() . '<br />' . $e->getLine()
            );
        }
        return $this->respond($result['result'], $result['status'], $transMessage);
    }

    public function verifTagihanNol(Request $r) {
        $kdProfile = $this->kdProfile;
        DB::beginTransaction();
        try {
            $pelayanan = PelayananPasien::where('noregistrasi', $r['noregistrasi'])
                ->where('kdprofile', $kdProfile)
                ->whereNull('strukfk')
                ->get();
            if (count($pelayanan) != 0) {
                $result = array(
                    "status" => 400,
                    "result"  => 'Terdapat Pelayanan Pasien yang harus di verifikasi.'
                );
                return $this->respond($result, $result['status'], $result['result']);
            }

            $noStruk = $this->SEQUENCE(new StrukPelayanan(), 'noverifikasi_tagihan', 10, 'S', $kdProfile);
            if ($noStruk == '') {
                abort(400, 'SEQ ERROR');
            }
            $PD = PasienDaftar::where('noregistrasi', $r['noregistrasi'])
                ->where('kdprofile', $kdProfile)
                ->first();
            $ruang = DB::table('ruangan_m')
            ->where('id', $PD->objectruanganlastfk)
            ->first();

            $filterDepart = explode(',', $this->settingFix('kdDepartemenRawatJalanFix'));
            if(in_array($ruang->objectdepartemenfk, $filterDepart)) {
                if(!isset($PD->tglpulang)) {
                    $PD->tglpulang = date('Y-m-d H:i:s');
                }
            }else {
                if(!isset($PD->tglpulang) ) {
                    $result = array(
                        "status" => 400,
                        "result"  => 'Pasien belum dipulangkan'
                    );
                    return $this->respond($result, $result['status'], $result['result']);
                }
            }
            if(!isset($PD->tglclosing)) {
                $PD->tglclosing = date('Y-m-d H:i:s');
            }
            $PD->isclosing = true; 
            $PD->save();

            // $totalBilling = 0;
            $totalKlaim = (float)$r['klaim'];
            $checkSP = StrukPelayanan::where('noregistrasifk', $PD->norec)->first();

            $SP = new StrukPelayanan();
            $SP->norec = $SP->generateNewId();
            $SP->kdprofile = $kdProfile;
            $SP->statusenabled = true;
            $SP->nocmfk = $PD->nocmfk;
            $SP->noregistrasifk = $PD->norec;
            $SP->noregistrasi = $PD->noregistrasi;
            $SP->objectkelaslastfk = $PD->objectkelasfk;
            $SP->objectkelompoktransaksifk = $this->kelompokTransaksi('VERIFIKASI TAGIHAN PASIEN');
            $SP->objectpegawaipenerimafk = $this->getPegawaiId();
            $SP->nostruk = $noStruk;
            $SP->totalhargasatuan = (float)$r['total'];
            $SP->totalharusdibayar = (float)$r['totalbayar'];
            $SP->tglstruk = isset($checkSP) ? $checkSP->tglstruk : $this->now();
            $SP->objectruanganfk = $PD->objectruanganlastfk;
            $SP->totalprekanan = $totalKlaim;
            $SP->totaliurbayar =  (float)$r['totaliurbayar'];
            $SP->totalbebanrs =  (float)$r['totalbebanrs'];
            $SP->totalkeuntunganrs =  (float)$r['totalkeuntunganrs'];
            $SP->kodingkelashak = $r['kodingkelashak'] ?? null;
            $SP->kodingkelasnaik = $r['kodingkelasnaik'] ?? null;
            $SP->kodingplafonvalue = $r['kodingplafonvalue'] ?? null;
            $SP->kodingplafontotal = $r['kodingplafontotal'] ?? null;
            $SP->kodingdiagnosa = $r['kodingdiagnosa'] ?? null;
            $SP->isclosingnol = true;
            $SP->save();

            if ($totalKlaim > 0) {
                $SPP = new StrukPelayananPenjamin();
                $SPP->norec = $SPP->generateNewId();
                $SPP->statusenabled = true;
                $SPP->kdprofile = $kdProfile;
                $SPP->kdkelompokpasien = $PD->objectkelompokpasienlastfk;
                $SPP->kdrekananpenjamin = $PD->objectrekananfk;
                $SPP->totalbiaya = (float)$r['totalbayar'] + $totalKlaim + (float)$r['deposit'];
                $SPP->totalsudahppenjamin = $totalKlaim;
                $SPP->totalsisaharusdibayar = $totalKlaim;
                $SPP->totalppenjamin = $totalKlaim;
                $SPP->totalharusdibayar = $totalKlaim;
                $SPP->totalsudahdibayar = 0;
                $SPP->totalsudahdibebaskan = 0;
                $SPP->totalsisapiutang = $totalKlaim;
                $SPP->totaldibayarlebih = 0;
                $SPP->nostrukfk = $SP->norec;
                $PD->nostruklastfk = $SP->norec;
                $SPP->save();
            }
            $PD->statusbayar = 'Verifikasi';
            if ($r['totalbayar'] == 0) {
                $PD->statusbayar = 'Lunas';
            }
            $PD->nostruklastfk = $SP->norec;

            $PD->save();
            if (isset($r['multipenjamin']) && count($r['multipenjamin']) > 0) {
                $reqDetail = $r['multipenjamin'];
                foreach ($reqDetail as $values) {
                    $SPPenjaminDet = new StrukPelayananPenjaminDetail();
                    $SPPenjaminDet->norec = $SPPenjaminDet->generateNewId();
                    $SPPenjaminDet->statusenabled = true;
                    $SPPenjaminDet->kdprofile = $kdProfile;
                    $SPPenjaminDet->nostrukfk = $SP->norec;
                    $SPPenjaminDet->totalppenjamin = $values['total'];
                    $SPPenjaminDet->totalharusdibayar = $values['total'];
                    $SPPenjaminDet->keteranganlainnya = 'Multi Penjamin';
                    $SPPenjaminDet->kdrekananpenjamin = $values['penjaminfk'];
                    $SPPenjaminDet->strukpelayananpenjaminfk = $SPP->norec;
                    $SPPenjaminDet->kdkelompokpasien = $values['kelompokpasienfk'];
                    $SPPenjaminDet->save();
                }
            }

            $this->LOGGING(
                'Verifikasi Tagihan',
                $SP->norec,
                'strukpelayanan_t',
                'Verifikasi Tagihan pada Pasien ' .
                    $r['namapasien'] .
                    ' (' . $r['nocm'] . ') - ' .
                    $SP->nostruk
            );
            $transStatus = true;
        } catch (\Exception $e) {
            $transStatus = false;
        }

        if ($transStatus) {
            $transMessage = "Sukses";
            DB::commit();
            $ihs = null;
            $objetoRequest = new \Illuminate\Http\Request();
            $objetoRequest['noregistrasi'] = $PD->noregistrasi;
            if($PD->ihs_diagnosis != null){
                $objetoRequest['diagnosis']=  json_decode($PD->ihs_diagnosis);
            }
            $ihs = app('App\Http\Controllers\Bridging\SATUSEHATCtrl')->Encounter($objetoRequest, true);
            $result = array(
                "status" => 200,
                "result" => array(
                    "sp"  => $SP,
                    "pd" => $PD,
                    "Encounter"=>$ihs,
                    "as" => '@epic',
                ),
            );
        } else {
            $transMessage =  $e->getCode() == 404 ? $e->getMessage() : "Simpan Gagal";
            DB::rollBack();
            $result = array(
                "status" => 400,
                "result"  => $e->getMessage() . '<br />' . $e->getLine()
            );
        }
        return $this->respond($result['result'], $result['status'], $transMessage);
    }
    public function listKelas(Request $r)
    {
        $res['kelas'] = DB::table('kelas_m')->where('kdprofile', $this->kdProfile)
            ->where('statusenabled', true)
            ->select('id', 'namakelas', 'namabpjs')
            ->orderBy('namakelas')
            ->get();
        return $this->respond($res);
    }

    public function listKoding(Request $r)
    {
        $filter = '';
        if (isset($r['query']) && $r['query'] != '' && $r['query'] != 'undefined') {
            $filter = "and kode || ' ' || deskripsi ilike '%".$r['query']."%'";
        }
        $res['koding'] = DB::select(DB::raw("select distinct kode, deskripsi, kode || ' ' || deskripsi as text 
        from plafonbpjs_m
        where statusenabled = true
        $filter"));
        return $this->respond($res);
    }

    public function getPlafon(Request $r)
    {
        $res = DB::select(DB::raw("select * 
        from plafonbpjs_m
        where statusenabled = true
        and kode = '".$r['koding']."'
        and tipepelayanan = '".$r['departemen']."'"));
        return $this->respond($res);
    }

    public function getMultiPenjamin(Request $r)
    {
        $multi = DB::table('strukpelayananpenjamindetail_t as sppd')
        ->select(
            'sppd.totalppenjamin', 'sppd.totalharusdibayar', 'rk.namarekanan', 'sppd.norec', 
            'rk.id as penjaminfk', 'rk.namarekanan', 'kp.id as kelompokpasienfk', 'kp.kelompokpasien'
        )
        ->join('strukpelayananpenjamin_t as spp', 'sppd.strukpelayananpenjaminfk', 'spp.norec')
        ->join('pasiendaftar_t as pd', 'pd.nostruklastfk', 'spp.nostrukfk')
        ->leftjoin('rekanan_m as rk', 'rk.id', 'sppd.kdrekananpenjamin')
        ->leftJoin('kelompokpasien_m as kp', 'pd.objectkelompokpasienlastfk', 'kp.id')
        ->where('pd.noregistrasi', $r['noregistrasi'])
        ->where('sppd.statusenabled', true)
        ->where('sppd.keteranganlainnya', 'Multi Penjamin')
        ->get();

        return $this->respond($multi);
    }

    public function updateTanggalPulang(Request $r)
    {
        try {
            $pd = DB::table('pasiendaftar_t')->where('norec', $r['norec_pd'])
            ->where('statusenabled', true)
            ->update([
                'tglpulang' => date("Y-m-d H:i:s",strtotime($r['tanggalpulang']))
            ]);
            $this->LOGGING(
                'Update Tanggal Pulang',
                $r['norec_pd'],
                'pasiendaftar_t',
                'Tanggal pulang pasien '. $r['namapasien'] .
                ' di update oleh ' .
                $this->getNamaPegawai()
            );
            $result = array(
                "status" => 200,
                "result"  => "success"
            );
            $transMessage = "Sukses";
        } catch (\Exception $th) {
            $transMessage = "Pasien tidak ditemukan";
            $result = array(
                "status" => 400,
                "result"  => "Pasien tidak ditemukan."
            );
        }

        return $this->respond($result['result'], $result['status'], $transMessage);
    }
}
