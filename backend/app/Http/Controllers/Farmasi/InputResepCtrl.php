<?php

namespace App\Http\Controllers\Farmasi;

use App\Http\Controllers\Controller;
use App\Models\Master\AsalProduk;
use App\Models\Master\JenisKemasan;
use App\Models\Master\JenisRacikan;
use App\Models\Master\Kelas;
use App\Models\Master\KelompokPasien;
use App\Models\Master\Pegawai;
use App\Models\Master\RouteFarmasi;
use App\Models\Master\Ruangan;
use App\Models\Master\SatuanResep;
use App\Models\Master\Signa;
use App\Models\Standar\MapDepoToRuangan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Master\Kebangsaan;

use App\Models\Transaksi\AntrianApotik;
use App\Models\Transaksi\KartuStok;
use App\Models\Transaksi\PelayananPasien;
use App\Models\Transaksi\PelayananPasienDetail;
use App\Models\Transaksi\PelayananPasienObatKronis;
use App\Models\Transaksi\PelayananPasienPetugas;
use App\Models\Transaksi\PelayananPasienRetur;
use App\Models\Transaksi\SkriningFarmasi;
use App\Models\Transaksi\StokProdukDetail;
use App\Models\Transaksi\StrukOrder;
use App\Models\Transaksi\StrukResep;
use App\Models\Transaksi\StrukReturPerawat;
use App\Models\Transaksi\StrukRetur;
use App\Traits\Valet;
use Carbon\Carbon;
use Exception;
use Ramsey\Uuid\Uuid;

class InputResepCtrl extends Controller
{
    use Valet;
    public function __construct()
    {
        parent::__construct($is_encrypt = true);
    }

    public function getHeader(Request $r)
    {
        $data = DB::table('pasiendaftar_t as pd')
            ->join('antrianpasiendiperiksa_t AS apd', 'apd.noregistrasifk', '=', 'pd.norec')
            ->join('pasien_m as ps', 'ps.id', '=', 'pd.nocmfk')
            ->leftjoin('kebangsaan_m as kbm', 'kbm.id', '=', 'ps.objectkebangsaanfk')
            ->leftjoin('jeniskelamin_m as jk', 'jk.id', '=', 'ps.objectjeniskelaminfk')
            ->leftjoin('alamat_m as alm', 'alm.nocmfk', '=', 'ps.id')
            ->join('kelompokpasien_m as kp', 'kp.id', '=', 'pd.objectkelompokpasienlastfk')
            ->leftjoin('rekanan_m as rek', 'rek.id', '=', 'pd.objectrekananfk')
            ->join('ruangan_m AS ru', 'ru.id', '=', 'pd.objectruanganlastfk')
            ->join('ruangan_m AS ru1', 'ru1.id', '=', 'apd.objectruanganfk')
            ->join('departemen_m AS dp', 'dp.id', '=', 'ru1.objectdepartemenfk')
            ->join('kelas_m AS kl', 'kl.id', '=', 'apd.objectkelasfk')
            ->leftjoin('pegawai_m AS pg', 'pg.id', '=', 'apd.objectpegawaifk')
            ->leftJoin('pemakaianasuransi_t as pa', 'pa.noregistrasifk', 'pd.norec')
            ->select(
                'ps.nocm',
                'ps.alamatrmh',
                'alm.alamatlengkap',
                'apd.objectruanganfk',
                'kp.kelompokpasien',
                'ps.tgllahir',
                'ps.nohp',
                'kbm.name as kebangsaan',
                'ps.nobpjs',
                'ru.namaruangan as ruangrawat',
                'ru1.namaruangan as ruanginput',
                'apd.objectpegawaifk',
                'pg.namalengkap',
                'pd.statusbayar',
                'dp.namadepartemen',
                'pd.tglpulang',
                'pd.tglmeninggal',
                'ru1.objectdepartemenfk',
                'kp.id as kpid',
                'pd.statusschedule as noreservasi',
                'ps.namapasien',
                'pd.tglregistrasi',
                'rek.namarekanan',
                'jk.jeniskelamin',
                'kl.id as klsid',
                'kl.namakelas',
                'pd.noregistrasi',
                'pd.nocmfk',
                'pa.nosep as nosep',
                'pa.tglsep as tglsep',
                'ps.objectkebangsaanfk',
                'ru.kodeapotikonline as kodeapotikonline',
            )

            ->where('pd.kdprofile', (int)$this->kdProfile)
            ->where('pd.statusenabled', true);

        if (isset($r['noregistrasi']) && $r['noregistrasi'] != "" && $r['noregistrasi'] != "undefined") {
            $data = $data->where('pd.noregistrasi', $r['noregistrasi']);
        }
        if (isset($r['norec_apd']) && $r['norec_apd'] != "" && $r['norec_apd'] != "undefined") {
            $data = $data->where('apd.norec', $r['norec_apd']);
        }
        if (isset($r['norec_pd']) && $r['norec_pd'] != "" && $r['norec_apd'] != "undefined") {
            $data = $data->where('pd.norec', $r['norec_pd']);
        }

        $data = $data->first();
        if (!empty($data)) {
            $data->umur = $this->getAge($data->tgllahir, $data->tglregistrasi);
            $data->beratbadan = '-';
            $data->tinggibadan = '-';
            $suratkontrol = DB::table('riwayatkontrol_t')
            ->where('nocmfk', $data->nocmfk)
            // ->whereRaw("tglentry::date = $data->tglregistrasi")
            ->whereDate('tglentry', '=', $data->tglregistrasi)
            ->orderBy('tglkontrol', 'desc')
            ->first();
            $data->suratkontrol = isset($suratkontrol) ? $suratkontrol->tglkontrol : '-';
            // $suratkontrol = DB::table('pasiendaftar_t as pd')
            // ->join('pasien_m as ps', 'ps.id', 'pd.nocmfk')
            // ->join('antrianpasienregistrasi_t as apr', 'ps.id', '=', 'apr.nocmfk')
            // ->join('riwayatkontrol_t as rk', 'apr.norec', 'rk.antrianpasienregistrasifk')
            // ->where('pd.kdprofile', $this->kdProfile)
            // ->where('pd.statusenabled', true)
            // ->where('apr.statusenabled', true)
            // ->whereRaw("rk.tglentry::date = pd.tglregistrasi::date")
            // ->where("pd.nocmfk", "=", $d->nocmfk)
            // ->select('pd.tglregistrasi', 'pd.noregistrasi', 'ps.namapasien', 'rk.nocmfk', 'rk.tglkontrol', 'rk.catatan', 'rk.indikasikontrol', 'rk.diagnosaakhir', 'rk.norec')
            // ->groupBy('pd.tglregistrasi', 'pd.noregistrasi', 'ps.namapasien', 'rk.nocmfk', 'rk.tglkontrol', 'rk.catatan', 'rk.indikasikontrol', 'rk.diagnosaakhir', 'rk.norec')
            // ->orderBy('rk.tglkontrol', 'desc')
            // ->first();
        }

        $res['data'] = $data;
        return $this->respond($res);
    }


    public function ruanganToDepo(Request $request)
    {
        $mapDepoToRuangan = DB::table('ruangan_m as ru')
            ->join('mapdepofarmasitoruangan_s as map', 'ru.id', 'map.headruanganfk')
            ->select('ru.namaruangan')
            ->whereIn('ru.id', ['map.childruanganfk'])
            ->get();
    }    
    public function getCombo(Request $request)
    {
        $idProfile = (int)$this->kdProfile;
        $res['penulisresep'] = Pegawai::mine()->where('objectjenispegawaifk', $this->settingFix('idJenisPegawaiDokter'))->get();
        $res['signa'] = Signa::mine()->get();
        $res['kebangsaan'] = Kebangsaan::mine()->get();
        $res['jasaNonRacikanWNI'] = explode(',', $this->settingFix('jasaNonRacikanWNI'));
        $res['jasaNonRacikanWNA-Kitas'] = explode(',', $this->settingFix('jasaNonRacikanWNA-Kitas'));
        $res['jasaNonRacikanWNA-NonKitas'] = explode(',', $this->settingFix('jasaNonRacikanWNA-NonKitas'));
        $res['jasaRacikan'] = explode(',', $this->settingFix('jasaRacikan'));
        $res['jasaRacikanLebih'] = explode(',', $this->settingFix('jasaRacikanLebih'));
        $res['jasaAseptik'] = explode(',', $this->settingFix('jasaAseptik'));

        $cekDepo = 0;
        if (isset($request['ruanganfk']) && isset($request['departemenfk']) && $request['ruanganfk'] != '' && $request['departemenfk'] != '' && $request['ruanganfk'] != 'undefined') {
            $cekDepo = MapDepoToRuangan::where('kdprofile', $this->kdProfile)->where('statusenabled', true)
                ->where('objectruanganfk', $request['ruanganfk'])
                ->count();
        }
        if(isset($request['floorstock']) && $request['floorstock'] != ''){
            $res['ruangan']=DB::table('ruangan_m')->where('statusenabled',true)->select('id','namaruangan')->get();    
        }
        else{
            if ($cekDepo > 0 && $request['ruanganfk'] != 'undefined') {
                $res['ruangan'] = DB::table('mapdepotoruangan_t as rupo')
                    ->join('ruangan_m as ru', 'ru.id', 'rupo.objectruanganfk')
                    ->join('ruangan_m as depo', 'depo.id', 'rupo.objectdepofk')
                    ->select('depo.namaruangan', 'depo.id')
                    ->where('rupo.kdprofile', $this->kdProfile)
                    ->where('ru.statusenabled', true)
                    ->where('depo.statusenabled', true)
                    ->where('rupo.objectruanganfk', $request['ruanganfk'])
                    // ->where('mlu.objectloginuserfk', $this->getUserId())
                    ->where('rupo.statusenabled', true)
                    ->orderByDesc('depo.id')
                    ->get();
    
    
            } else {
                $res['ruangan'] =  DB::table('maploginusertoruangan_s as mlu')
                    ->JOIN('ruangan_m as ru', 'ru.id', '=', 'mlu.objectruanganfk')
                    ->select('ru.id', 'ru.namaruangan')
                    ->where('mlu.kdprofile', $idProfile)
                    ->where('ru.statusenabled', true)
                    ->where('mlu.statusenabled', true)
                    ->whereIn('ru.objectdepartemenfk', [$this->settingFix('idInstalasiFarmasi')])
                    ->orWhere('mlu.objectruanganfk', 363)
                    ->where('mlu.objectloginuserfk', $this->getUserId())
                    ->groupBy('ru.id', 'ru.namaruangan')
                    ->orderByDesc('ru.id')
                    ->get();
            }
        }

        // $res['ruangan'] =  DB::table('maploginusertoruangan_s as mlur')
        // ->join('ruangan_m as ru', 'ru.id', '=', 'mlur.objectruanganfk')
        // ->join('loginuser_s as lu', 'lu.id', '=', 'mlur.objectloginuserfk')
        // ->select('ru.id', 'ru.namaruangan')
        // ->where('mlur.statusenabled', true)
        // ->where('mlur.objectloginuserfk', $this->getUserId())
        // ->where('ru.kdprofile', $this->kdProfile)
        // ->where('ru.statusenabled', true)
        // ->where('mlur.kdprofile', $this->kdProfile)
        // ->get();

        $res['ruanganFarmasi'] = Ruangan::where('kdprofile', $this->kdProfile)
            ->where('statusenabled', true)
            ->whereIn('objectdepartemenfk', [$this->settingFix('idInstalasiFarmasi')])
            ->get();


        $res['jeniskemasan'] = JenisKemasan::mine()->orderBy('jeniskemasan', 'desc')->get();
        $res['jenisracikan'] = JenisRacikan::mine()->get();
        $res['asalproduk'] = AsalProduk::mine()->get();
        $res['route'] = RouteFarmasi::mine()->get();
        $res['satuanresep'] = SatuanResep::mine()->get();
        // $res['antrianfarmasi'] = AntrianFarmasi::mine()->get();

        // $dataKonversiProduk = DB::table('konversisatuan_t as ks')
        //     ->JOIN('satuanstandar_m as ss', 'ss.id', '=', 'ks.satuanstandar_asal')
        //     ->JOIN('satuanstandar_m as ss2', 'ss2.id', '=', 'ks.satuanstandar_tujuan')
        //     ->select(
        //         'ks.objekprodukfk',
        //         'ks.satuanstandar_asal',
        //         'ss.satuanstandar',
        //         'ks.satuanstandar_tujuan',
        //         'ss2.satuanstandar as satuanstandar2',
        //         'ks.nilaikonversi'
        //     )
        //     ->where('ks.kdprofile', $idProfile)
        //     ->where('ks.statusenabled', true)
        //     ->get();

        // $dataProduk = DB::table('produk_m as pr')
        //     ->JOIN('detailjenisproduk_m as djp', 'djp.id', '=', 'pr.objectdetailjenisprodukfk')
        //     ->JOIN('jenisproduk_m as jp', 'jp.id', '=', 'djp.objectjenisprodukfk')
        //     ->leftJOIN('satuanstandar_m as ss', 'ss.id', '=', 'pr.objectsatuanstandarfk')
        //     ->JOIN('stokprodukdetail_t as spd', 'spd.objectprodukfk', '=', 'pr.id')
        //     ->select('pr.id', 'pr.namaproduk', 'ss.id as ssid', 'ss.satuanstandar')
        //     ->where('pr.kdprofile', $idProfile)
        //     ->where('pr.statusenabled', true)
        //     ->whereIn('jp.id', explode(',', $this->settingFix('kdJenisProdukObat')));
        // if (isset($r['namaproduk']) && $r['namaproduk'] != '') {
        //     $dataProduk = $dataProduk->where('pr.namaproduk', 'ilike', '%' . $r['namaproduk'] . '%');
        // }
        // //->where('spd.qtyproduk','>',0)
        // $dataProduk = $dataProduk->groupBy('pr.id', 'pr.namaproduk', 'ss.id', 'ss.satuanstandar');
        // $dataProduk = $dataProduk->orderBy('pr.namaproduk');
        // $dataProduk = $dataProduk->get();

        $dataProdukResult = [];
        // foreach ($dataProduk as $item) {
        //     $satuanKonversi = [];
        //     foreach ($dataKonversiProduk  as $item2) {
        //         if ($item->id == $item2->objekprodukfk) {
        //             $satuanKonversi[] = array(
        //                 'ssid' =>   $item2->satuanstandar_tujuan,
        //                 'satuanstandar' =>   $item2->satuanstandar2,
        //                 'nilaikonversi' =>   $item2->nilaikonversi,
        //             );
        //         }
        //     }

        //     $dataProdukResult[] = array(
        //         'id' =>   $item->id,
        //         'namaproduk' =>   $item->namaproduk,
        //         'ssid' =>   $item->ssid,
        //         'satuanstandar' =>   $item->satuanstandar,
        //         'konversisatuan' => $satuanKonversi,
        //     );
        // }
        $res['produk'] = $dataProdukResult;
        $res['tarifadminresep'] = $this->settingFix('tarifadminresep');
        $res['tarifKitas'] = $this->settingFix('jasaNonRacikanWNA-Kitas');
        $res['tarifNonKitas'] = $this->settingFix('jasaNonRacikanWNA-NonKitas');
        $res['tarifWNI'] = $this->settingFix('jasaNonRacikanWNI');

        return $this->respond($res);
    }
    
    public function getComboOrder(Request $request)
    {
        $idProfile = (int)$this->kdProfile;
        // $res['penulisresep'] = Pegawai::mine()->where('objectjenispegawaifk', $this->settingFix('idJenisPegawaiDokter'))->get();

        $penulisResep = Pegawai::mine()->get();


        $pegawaiLogin = [
            'id' => $this->getUserId() 
            
        ];


        if (!$penulisResep->contains('id', $pegawaiLogin['id'])) {
            $penulisResep->push((object)$pegawaiLogin);
        }
        $res['penulisresep'] = $penulisResep;

        $res['signa'] = Signa::mine()->get();
        $res['jasaNonRacikanWNI'] = explode(',', $this->settingFix('jasaNonRacikanWNI'));
        $res['jasaNonRacikanWNA-Kitas'] = explode(',', $this->settingFix('jasaNonRacikanWNA-Kitas'));
        $res['jasaNonRacikanWNA-NonKitas'] = explode(',', $this->settingFix('jasaNonRacikanWNA-NonKitas'));
        $res['jasaRacikan'] = explode(',', $this->settingFix('jasaRacikan'));
        $res['jasaRacikanLebih'] = explode(',', $this->settingFix('jasaRacikanLebih'));
        $res['jasaAseptik'] = explode(',', $this->settingFix('jasaAseptik'));

        $cekDepo = 0;
        if (isset($request['ruanganfk']) && isset($request['departemenfk']) && $request['ruanganfk'] != '' && $request['departemenfk'] != '' && $request['ruanganfk'] != 'undefined') {
            $cekDepo = MapDepoToRuangan::where('kdprofile', $this->kdProfile)->where('statusenabled', true)
                ->where('objectruanganfk', $request['ruanganfk'])
                ->count();
        }

        if ($cekDepo > 0 && $request['ruanganfk'] != 'undefined') {
            $res['ruangan'] = DB::table('mapdepotoruangan_t as rupo')
                ->join('ruangan_m as ru', 'ru.id', 'rupo.objectruanganfk')
                ->join('ruangan_m as depo', 'depo.id', 'rupo.objectdepofk')
                ->select('depo.namaruangan', 'depo.id')
                ->where('rupo.kdprofile', $this->kdProfile)
                ->where('ru.statusenabled', true)
                ->where('depo.statusenabled', true)
                ->where('rupo.objectruanganfk', $request['ruanganfk'])
                ->where('rupo.statusenabled', true)
                ->orderByDesc('depo.id')
                ->get();
        } else {
            $res['ruangan'] =  DB::table('maploginusertoruangan_s as mlu')
                ->JOIN('ruangan_m as ru', 'ru.id', '=', 'mlu.objectruanganfk')
                ->select('ru.id', 'ru.namaruangan')
                ->where('mlu.kdprofile', $idProfile)
                ->where('ru.statusenabled', true)
                ->where('mlu.statusenabled', true)
                ->whereIn('ru.objectdepartemenfk', [$this->settingFix('idInstalasiFarmasi')])
                ->where('mlu.objectloginuserfk', $this->getUserId())
                ->orderByDesc('ru.id')
                ->get();
        }

        $res['ruanganFarmasi'] = Ruangan::where('kdprofile', $this->kdProfile)
            ->where('statusenabled', true)
            ->whereIn('objectdepartemenfk', [$this->settingFix('idInstalasiFarmasi')])
            ->get();


        $res['jeniskemasan'] = JenisKemasan::mine()->get();
        $res['jenisracikan'] = JenisRacikan::mine()->get();
        $res['asalproduk'] = AsalProduk::mine()->get();
        $res['route'] = RouteFarmasi::mine()->get();
        $res['satuanresep'] = SatuanResep::mine()->get();
        // $res['antrianfarmasi'] = AntrianFarmasi::mine()->get();

        // $dataKonversiProduk = DB::table('konversisatuan_t as ks')
        //     ->JOIN('satuanstandar_m as ss', 'ss.id', '=', 'ks.satuanstandar_asal')
        //     ->JOIN('satuanstandar_m as ss2', 'ss2.id', '=', 'ks.satuanstandar_tujuan')
        //     ->select(
        //         'ks.objekprodukfk',
        //         'ks.satuanstandar_asal',
        //         'ss.satuanstandar',
        //         'ks.satuanstandar_tujuan',
        //         'ss2.satuanstandar as satuanstandar2',
        //         'ks.nilaikonversi'
        //     )
        //     ->where('ks.kdprofile', $idProfile)
        //     ->where('ks.statusenabled', true)
        //     ->get();

        // $dataProduk = DB::table('produk_m as pr')
        //     ->JOIN('detailjenisproduk_m as djp', 'djp.id', '=', 'pr.objectdetailjenisprodukfk')
        //     ->JOIN('jenisproduk_m as jp', 'jp.id', '=', 'djp.objectjenisprodukfk')
        //     ->leftJOIN('satuanstandar_m as ss', 'ss.id', '=', 'pr.objectsatuanstandarfk')
        //     ->JOIN('stokprodukdetail_t as spd', 'spd.objectprodukfk', '=', 'pr.id')
        //     ->select('pr.id', 'pr.namaproduk', 'ss.id as ssid', 'ss.satuanstandar')
        //     ->where('pr.kdprofile', $idProfile)
        //     ->where('pr.statusenabled', true)
        //     ->whereIn('jp.id', explode(',', $this->settingFix('kdJenisProdukObat')));
        // if (isset($r['namaproduk']) && $r['namaproduk'] != '') {
        //     $dataProduk = $dataProduk->where('pr.namaproduk', 'ilike', '%' . $r['namaproduk'] . '%');
        // }
        // //->where('spd.qtyproduk','>',0)
        // $dataProduk = $dataProduk->groupBy('pr.id', 'pr.namaproduk', 'ss.id', 'ss.satuanstandar');
        // $dataProduk = $dataProduk->orderBy('pr.namaproduk');
        // $dataProduk = $dataProduk->get();

        $dataProdukResult = [];
        // foreach ($dataProduk as $item) {
        //     $satuanKonversi = [];
        //     foreach ($dataKonversiProduk  as $item2) {
        //         if ($item->id == $item2->objekprodukfk) {
        //             $satuanKonversi[] = array(
        //                 'ssid' =>   $item2->satuanstandar_tujuan,
        //                 'satuanstandar' =>   $item2->satuanstandar2,
        //                 'nilaikonversi' =>   $item2->nilaikonversi,
        //             );
        //         }
        //     }

        //     $dataProdukResult[] = array(
        //         'id' =>   $item->id,
        //         'namaproduk' =>   $item->namaproduk,
        //         'ssid' =>   $item->ssid,
        //         'satuanstandar' =>   $item->satuanstandar,
        //         'konversisatuan' => $satuanKonversi,
        //     );
        // }
        $res['produk'] = $dataProdukResult;
        $res['tarifadminresep'] = $this->settingFix('tarifadminresep');
        $res['tarifKitas'] = $this->settingFix('jasaNonRacikanWNA-Kitas');
        $res['tarifNonKitas'] = $this->settingFix('jasaNonRacikanWNA-NonKitas');
        $res['tarifWNI'] = $this->settingFix('jasaNonRacikanWNI');

        return $this->respond($res);
    }
    public function getProduk(Request $r)
    {
        $idProfile = (int)$this->kdProfile;
        $dataKonversiProduk = DB::table('konversisatuan_t as ks')
            ->JOIN('satuanstandar_m as ss', 'ss.id', '=', 'ks.satuanstandar_asal')
            ->JOIN('satuanstandar_m as ss2', 'ss2.id', '=', 'ks.satuanstandar_tujuan')
            ->select(
                'ks.objekprodukfk',
                'ks.satuanstandar_asal',
                'ss.satuanstandar',
                'ks.satuanstandar_tujuan',
                'ss2.satuanstandar as satuanstandar2',
                'ks.nilaikonversi'
            )
            ->where('ks.kdprofile', $idProfile)
            ->where('ks.statusenabled', true)
            ->get();

        $dataProduk = DB::table('produk_m as pr')
            ->JOIN('detailjenisproduk_m as djp', 'djp.id', '=', 'pr.objectdetailjenisprodukfk')
            ->JOIN('jenisproduk_m as jp', 'jp.id', '=', 'djp.objectjenisprodukfk')
            ->leftJOIN('satuanstandar_m as ss', 'ss.id', '=', 'pr.objectsatuanstandarfk')
            ->JOIN('stokprodukdetail_t as spd', 'spd.objectprodukfk', '=', 'pr.id')
            ->select('pr.id', 'pr.namaproduk', '', 'ss.id as ssid', 'ss.satuanstandar', 'pr.nama')
            ->where('pr.kdprofile', $idProfile)
            ->where('pr.statusenabled', true)
            ->whereIn('jp.id', explode(',', $this->settingFix('kdJenisProdukObat')));
        if (isset($r['namaproduk']) && $r['namaproduk'] != '') {
            $dataProduk = $dataProduk->where('pr.namaproduk', 'ilike', '%' . $r['namaproduk'] . '%');
        }
        if (isset($r['namaproduk']) && $r['namaproduk'] != '') {
            $dataProduk = $dataProduk->where('pr.namaproduk', 'ilike', '%' . $r['namaproduk'] . '%');
        }
        //->where('spd.qtyproduk','>',0)
        $dataProduk = $dataProduk->groupBy('pr.id', 'pr.namaproduk', 'ss.id', 'ss.satuanstandar');
        $dataProduk = $dataProduk->orderBy('pr.namaproduk');
        $dataProduk = $dataProduk->get();

        $dataProdukResult = [];
        foreach ($dataProduk as $item) {
            $satuanKonversi = [];
            foreach ($dataKonversiProduk  as $item2) {
                if ($item->id == $item2->objekprodukfk) {
                    $satuanKonversi[] = array(
                        'ssid' =>   $item2->satuanstandar_tujuan,
                        'satuanstandar' =>   $item2->satuanstandar2,
                        'nilaikonversi' =>   $item2->nilaikonversi,
                    );
                }
            }

            $dataProdukResult[] = array(
                'id' =>   $item->id,
                'namaproduk' =>   $item->namaproduk,
                'ssid' =>   $item->ssid,
                'satuanstandar' =>   $item->satuanstandar,
                'konversisatuan' => $satuanKonversi,
            );
        }
        $res['produk'] = $dataProdukResult;
        return $this->respond($res);
    }
    public function getProdukDetail(Request $request, $lokal = false)
    {

        /*
        | MetodeAmbilHargaNetto	0	KOSONG .: pengambilan hn1 atau hn2

        | MetodeHargaNetto	2	AVG
        | MetodeHargaNetto	3	Harga Tertinggi
        | MetodeHargaNetto	1	Harga Netto #

        | MetodeStokHargaNetto	2	LIFO
        | MetodeStokHargaNetto	3	FEFO #
        | MetodeStokHargaNetto	4	LEFO
        | MetodeStokHargaNetto	1	FIFO
        | MetodeStokHargaNetto	5	Summary

        | SistemHargaNetto	7	Harga Terakhir #
        | SistemHargaNetto	6	LEFO
        | SistemHargaNetto	2	LIFO
        | SistemHargaNetto	3	Harga Tertinggi
        | SistemHargaNetto	4	AVG
        | SistemHargaNetto	5	FEFO
        | SistemHargaNetto	1	FIFO

        | KETERANGAN : (#) setting yg dipakai
        */
        $pasien = DB::select(DB::raw("select ps.objectkebangsaanfk
            from antrianpasiendiperiksa_t as apd
            join pasiendaftar_t as pd  on pd.norec=apd.noregistrasifk and apd.kdprofile =pd.kdprofile
            join pasien_m as ps  on ps.id=pd.nocmfk and pd.kdprofile =ps.kdprofile
            where apd.norec = '" . $request['norec_apd'] . "'"));

        $idHibah = $this->settingFix('objectasalprodukHibah');
        $isHibah = '';
        // if (isset($request['isdonasi'])) {
        //     $isHibah = 'and objectasalprodukfk = ' . $idHibah;
        // } else {
        //     $isHibah = 'and objectasalprodukfk <> ' . $idHibah;
        // }
        $idProfile = $this->kdProfile;
        $kdJenisTransOA = $this->settingFix('jenisTransaksiOA');
        $jenisTransaksi = DB::table('jenistransaksi_m as jt')
            ->where('jt.id', $kdJenisTransOA)
            ->where('jt.statusenabled', true)
            ->first();

        if (empty($jenisTransaksi)) {
            return $this->respond(null, 500, 'Setting jenistransaksi_m dulu');
        }
        $strMetodeAmbilHargaNetto = $jenisTransaksi->metodeambilharganetto;
        //        $strMetodeHargaNetto = $jenisTransaksi->metodeharganetto; //ketika penerimaan saja
        $strMetodeStokHargaNetto = $jenisTransaksi->metodestokharganetto;
        $strSistemHargaNetto = $jenisTransaksi->sistemharganetto;

        if (empty($strMetodeAmbilHargaNetto) || empty($strMetodeAmbilHargaNetto) || empty($strMetodeAmbilHargaNetto)) {
            return $this->respond(null, 500, 'Setting Data Fixed Belum ada');
        }
        $defaultKP = 0;
        $defaultKP = 0;
        if (isset($request['kpid']) && $request['kpid'] != "" && $request['kpid'] != "undefined") {
            $defaultKP = $request['kpid'];
        }

        $persenHargaJualProduk = DB::table('persenhargajualproduk_m as phjp')
            ->JOIN('range_m as rg', 'rg.id', '=', 'phjp.objectrangefk')
            ->select('rg.rangemin', 'rg.rangemax', 'phjp.persenuphargasatuan', 'phjp.objectrangefk', 'phjp.objectkelompokpasienfk')
            ->where('phjp.objectjenistransaksifk', $this->settingFix('jenisTransaksiOA'))
            ->where('phjp.statusenabled', true)
            ->where('phjp.objectkelompokpasienfk', $defaultKP)
            ->get();

        if (count($persenHargaJualProduk) == 0) {
            return $this->respond(array(
                'Error' => 'Setting persenhargajualproduk_m dulu',
                'message' => 'as@epic',
            ));
        }


        $strHN = '';
        $strMSHT = '';
        $SistemHargaNetto = '';
        $MetodeAmbilHargaNetto = '';
        $MetodeStokHargaNetto = '';
        $results = [];
        // ### FIFO ### //
        if ($strSistemHargaNetto == 1) {
            $SistemHargaNetto = 'FIFO';

            if ($strMetodeAmbilHargaNetto == 1) { //HN1
                $strHN = 'spd.harganetto1';
                $MetodeAmbilHargaNetto = 'HN1';
            }
            if ($strMetodeAmbilHargaNetto == 2) { //HN2
                $strHN = 'spd.harganetto2';
                $MetodeAmbilHargaNetto = 'HN2';
            }

            if ($strMetodeStokHargaNetto == 1) { //FIFO
                $strMSHT = 'sk.tglstruk';
                $MetodeStokHargaNetto = 'FIFO';
            }
            if ($strMetodeStokHargaNetto == 2) { //LIFO
                $strMSHT = '';
                $MetodeStokHargaNetto = 'LIFO';
            }
            if ($strMetodeStokHargaNetto == 3) { //FEFO
                $strMSHT = 'spd.tglkadaluarsa';
                $MetodeStokHargaNetto = 'FEFO';
            }
            if ($strMetodeStokHargaNetto == 4) { //LEFO
                $strMSHT = '';
                $MetodeStokHargaNetto = 'LEFO';
            }
            if ($strMetodeStokHargaNetto == 5) { //Summary
                $strMSHT = '';
                $MetodeStokHargaNetto = 'Summary';
            }
            $result = DB::select(
                DB::raw("select sk.norec,spd.objectprodukfk, $strMSHT as tgl,spd.objectasalprodukfk,
                      spd.hargadiscount,sum(spd.qtyproduk) as qtyproduk,spd.objectruanganfk,ap.asalproduk,spd.nostrukterimafk,spd.tglkadaluarsa,
                      spd.norec as norec_spd,sk.nofaktur,sk.nostruk,
                      CASE WHEN spd.objectasalprodukfk = $idHibah THEN 0 ELSE $strHN END AS harganetto
                from stokprodukdetail_t as spd
                inner JOIN strukpelayanan_t as sk on sk.norec=spd.nostrukterimafk
                inner JOIN asalproduk_m as ap on ap.id=spd.objectasalprodukfk
                where spd.kdprofile = $idProfile $isHibah and spd.objectprodukfk =:produkId and spd.statusenabled = true and spd.objectruanganfk =:ruanganid
                group by sk.norec,spd.objectprodukfk, $strMSHT,spd.objectasalprodukfk,
                        $strHN,spd.hargadiscount, spd.objectruanganfk,ap.asalproduk,spd.nostrukterimafk, spd.norec,sk.nofaktur,sk.nostruk
                order By qtyproduk desc, $strMSHT"),
                array(
                    'produkId' => $request['produkfk'],
                    'ruanganid' => $request['ruanganfk'],
                )
            );
            $results = [];
            $persenUpHargaSatuan = 0;
            $pesenUpDepos = explode(',', $this->settingFix('marginUpDepo'));
            $pesenUpDepo = [];
            foreach ($pesenUpDepos as $item) {
                // Pisahkan string menjadi id dan value menggunakan explode
                list($id, $value) = explode('|', $item);
                if ($request['ruanganfk'] == $id) {
                    $pesenUpDepo = ['id' => $id, 'value' => (float)$value];
                }
            }
            foreach ($result as $item) {
                $kebangsaan = 1;
                if (isset($pesenUpDepo['id'])) {
                    $persenUpHargaSatuan = (float)$pesenUpDepo['value'];
                } else {
                    foreach ($persenHargaJualProduk as $hitem) {
                        if ((float)$hitem->rangemin < (float)$item->harganetto && (float)$hitem->rangemax > (float)$item->harganetto) {
                            $kebangsaan = 1;
                            $persenUpHargaSatuan = (float)$hitem->persenuphargasatuan;
                            if (!empty($pasien)) {
                                if ($pasien[0]->objectkebangsaanfk != 1) {
                                    $kebangsaan = 1.5;
                                } else {
                                    $kebangsaan = 1;
                                }
                            } else {
                                $kebangsaan = 1;
                            }
                        }
                    }
                }

                //var_dump($pasien[0]);
                $results[] = array(
                    'norec' => $item->norec,
                    'objectprodukfk' => $item->objectprodukfk,
                    'tgl' => $item->tgl,
                    'objectasalprodukfk' => $item->objectasalprodukfk,
                    'asalproduk' => $item->asalproduk,
                    'harganetto' => $item->harganetto,
                    'hargadiscount' => $item->hargadiscount,
                    // 'objectkebangsaanfk '=> $pasien[0]->objectkebangsaanfk,
                    // 'kebangsaan' => $kebangsaan,
                    'hargajual' => $item->objectasalprodukfk != 3 ? (float)($item->harganetto + (((float)$item->harganetto * (float)$persenUpHargaSatuan) / 100)) * $kebangsaan : (float)($item->harganetto + (((float)$item->harganetto * (float)$persenUpHargaSatuan) / 100)),
                    'persenhargajualproduk' => $persenUpHargaSatuan,
                    'qtyproduk' => (float)$item->qtyproduk,
                    'objectruanganfk' => $item->objectruanganfk,
                    'nostrukterimafk' => $item->nostrukterimafk,
                    'tglkadaluarsa' => $item->tglkadaluarsa,
                    'persenup' => $persenUpHargaSatuan,
                    'norec_spd' => $item->norec_spd,

                );
            }
        }

        // ### END-FIFO ### //

        // ### Harga Tertinggi ### //
        if ($strSistemHargaNetto == 3) {
            $SistemHargaNetto = 'Harga Tertinggi';
            if ($strMetodeAmbilHargaNetto == 1) { //HN1
                $strHN = 'spd.harganetto1';
                $MetodeAmbilHargaNetto = 'HN1';
            }
            if ($strMetodeAmbilHargaNetto == 2) { //HN2
                $strHN = 'spd.harganetto2';
                $MetodeAmbilHargaNetto = 'HN2';
            }

            if ($strMetodeStokHargaNetto == 1) { //FIFO
                $strMSHT = 'sk.tglstruk';
                $MetodeStokHargaNetto = 'FIFO';
            }
            if ($strMetodeStokHargaNetto == 2) { //LIFO
                $strMSHT = '';
                $MetodeStokHargaNetto = 'LIFO';
            }
            if ($strMetodeStokHargaNetto == 3) { //FEFO
                $strMSHT = 'spd.tglkadaluarsa';
                $MetodeStokHargaNetto = 'FEFO';
            }
            if ($strMetodeStokHargaNetto == 4) { //LEFO
                $strMSHT = '';
                $MetodeStokHargaNetto = 'LEFO';
            }
            if ($strMetodeStokHargaNetto == 5) { //Summary
                $strMSHT = '';
                $MetodeStokHargaNetto = 'Summary';
            }
            // $maxHarga = DB::select(
            //     DB::raw("select $strHN as harga
            //     from stokprodukdetail_t as spd
            //     inner JOIN strukpelayanan_t as sk on sk.norec=spd.nostrukterimafk
            //     where spd.kdprofile = $idProfile $isHibah and spd.objectprodukfk =:produkId and spd.statusenabled = true and spd.objectruanganfk =:ruanganid"),
            //     array(
            //         'produkId' => $request['produkfk'],
            //         'ruanganid' => $request['ruanganfk'],
            //     )
            // );
            $maxHarga = DB::select(
                DB::raw("select MAX($strHN) as harga
                    from stokprodukdetail_t as spd
                    inner JOIN strukpelayanan_t as sk on sk.norec=spd.nostrukterimafk
                    where spd.kdprofile = $idProfile $isHibah 
                    and spd.objectprodukfk =:produkId 
                    and spd.statusenabled = true 
                    and spd.created_at BETWEEN (CURRENT_DATE - INTERVAL '1 year') AND (CURRENT_DATE + INTERVAL '1 day' - INTERVAL '1 second')"), // Filter satu tahun terakhir
                array(
                    'produkId' => $request['produkfk'],
                    // 'ruanganid' => $request['ruanganfk'],
                )
            );
            // $hargaTertinggi = !empty($maxHarga[0]->harga) ? (float)$maxHarga[0]->harga : 0;
            $hargaTertinggi = 0;
            foreach ($maxHarga as $item) {
                if ($hargaTertinggi < (float)$item->harga) {
                    $hargaTertinggi = (float)$item->harga;
                }
            }

            $result = DB::select(
                DB::raw("select sk.norec,spd.objectprodukfk, $strMSHT as tgl,spd.objectasalprodukfk,
                        $hargaTertinggi  as hargajual,spd.hargadiscount,sum(spd.qtyproduk) as qtyproduk,spd.objectruanganfk,ap.asalproduk,spd.nostrukterimafk,
                        spd.tglkadaluarsa, spd.norec as norec_spd,
                        CASE WHEN spd.objectasalprodukfk = $idHibah THEN 0 ELSE $hargaTertinggi END AS harganetto
                from stokprodukdetail_t as spd
                inner JOIN strukpelayanan_t as sk on sk.norec=spd.nostrukterimafk
                inner JOIN asalproduk_m as ap on ap.id=spd.objectasalprodukfk
                where spd.kdprofile = $idProfile $isHibah and spd.statusenabled = true and spd.objectprodukfk =:produkId and spd.objectruanganfk =:ruanganid
                group by sk.norec,spd.objectprodukfk, $strMSHT,spd.objectasalprodukfk,
                        spd.hargadiscount,
                spd.objectruanganfk,ap.asalproduk,spd.nostrukterimafk, spd.norec
                order By qtyproduk desc, $strMSHT"),

                array(
                    'produkId' => $request['produkfk'],
                    'ruanganid' => $request['ruanganfk'],
                )
            );
            $results = [];
            $persenUpHargaSatuan = 0;
            $pesenUpDepos = explode(',', $this->settingFix('marginUpDepo'));
            $pesenUpDepo = [];
            foreach ($pesenUpDepos as $item) {
                // Pisahkan string menjadi id dan value menggunakan explode
                list($id, $value) = explode('|', $item);
                if ($request['ruanganfk'] == $id) {
                    $pesenUpDepo = ['id' => $id, 'value' => (float)$value];
                }
            }
            foreach ($result as $item) {
                $kebangsaan = 1;
                if (isset($pesenUpDepo['id'])) {
                    $persenUpHargaSatuan = (float)$pesenUpDepo['value'];
                } else {
                    foreach ($persenHargaJualProduk as $hitem) {
                        if ((float)$hitem->rangemin < (float)$item->harganetto && (float)$hitem->rangemax > (float)$item->harganetto) {
                            $kebangsaan = 1;
                            $persenUpHargaSatuan = (float)$hitem->persenuphargasatuan;
                            if (!empty($pasien)) {
                                if ($pasien[0]->objectkebangsaanfk != 1) {
                                    $kebangsaan = 1.5;
                                } else {
                                    $kebangsaan = 1;
                                }
                            }
                             else {
                                $kebangsaan = 1;
                            }
                        }
                    }
                }
                $results[] = array(
                    'norec' => $item->norec,
                    'objectprodukfk' => $item->objectprodukfk,
                    'tgl' => $item->tgl,
                    'objectasalprodukfk' => $item->objectasalprodukfk,
                    'asalproduk' => $item->asalproduk,
                    'harganetto' => $item->harganetto,
                    'hargadiscount' => $item->hargadiscount,
                        // 'objectkebangsaanfk '=> $pasien[0]->objectkebangsaanfk ? $pasien[0]->objectkebangsaanfk : 1,
                        // 'kebangsaan' => $kebangsaan ? $kebangsaan : 1,
                    'hargajual' => $item->objectasalprodukfk != 3 ? (float)($item->harganetto + (((float)$item->harganetto * (float)$persenUpHargaSatuan) / 100)) * $kebangsaan : (float)($item->harganetto + (((float)$item->harganetto * (float)$persenUpHargaSatuan) / 100)) * $kebangsaan,
                    'persenhargajualproduk' => $persenUpHargaSatuan,
                    'qtyproduk' => (float)$item->qtyproduk,
                    'objectruanganfk' => $item->objectruanganfk,
                    'nostrukterimafk' => $item->nostrukterimafk,
                    'tglkadaluarsa' => $item->tglkadaluarsa,
                    'persenup' => $persenUpHargaSatuan,
                    'norec_spd' => $item->norec_spd,
                );
            }
        }
        // ### END-Harga Tertinggi ### //

        // ### Harga Terakhir ### //
        if ($strSistemHargaNetto == 7) {
            $SistemHargaNetto = 'Harga Terakhir';
            if ($strMetodeAmbilHargaNetto == 1) { //HN1
                $strHN = 'spd.harganetto1';
                $MetodeAmbilHargaNetto = 'HN1';
            }
            if ($strMetodeAmbilHargaNetto == 2) { //HN2
                $strHN = 'spd.harganetto2';
                $MetodeAmbilHargaNetto = 'HN2';
            }

            if ($strMetodeStokHargaNetto == 1) { //FIFO
                $strMSHT = 'sk.tglstruk';
                $MetodeStokHargaNetto = 'FIFO';
            }
            if ($strMetodeStokHargaNetto == 2) { //LIFO
                $strMSHT = 'sk.tglstruk desc';
                $MetodeStokHargaNetto = 'LIFO';
            }
            if ($strMetodeStokHargaNetto == 3) { //FEFO
                $strMSHT = 'spd.tglkadaluarsa';
                $MetodeStokHargaNetto = 'FEFO';
            }
            if ($strMetodeStokHargaNetto == 4) { //LEFO
                $strMSHT = 'spd.tglkadaluarsa desc';
                $MetodeStokHargaNetto = 'LEFO';
            }
            if ($strMetodeStokHargaNetto == 5) { //Summary
                $strMSHT = '';
                $MetodeStokHargaNetto = 'Summary';
            }
            $maxHarga = DB::select(
                DB::raw("select spd.tglpelayanan, $strHN as harga
                from stokprodukdetail_t as spd
                inner JOIN strukpelayanan_t as sk on sk.norec=spd.nostrukterimafk
                where spd.kdprofile = $idProfile $isHibah and spd.objectprodukfk =:produkId and spd.statusenabled = true"),
                array(
                    'produkId' => $request['produkfk'],
                )
            );
            $hargaTerakhir = 0;
            $tgl = date('2000-01-01 00:00');
            foreach ($maxHarga as $item) {
                if ($tgl < $item->tglpelayanan) {
                    $tgl = $item->tglpelayanan;
                    $hargaTerakhir = (float)$item->harga;
                }
            }
            $result = [];
            $result = DB::select(
                DB::raw("select sk.norec,spd.objectprodukfk, $strMSHT as tgl,spd.objectasalprodukfk,
                        $hargaTerakhir  as hargajual,spd.hargadiscount,spd.nostrukterimafk, sk.nostruk,
                sum(spd.qtyproduk) as qtyproduk,spd.objectruanganfk,ap.asalproduk,spd.tglkadaluarsa,
                spd.norec as norec_spd,
                (select sum(qtyproduk) from stokprodukdetail_t where kdprofile = $idProfile and objectprodukfk = spd.objectprodukfk and statusenabled = true) as totalstok,
                CASE WHEN spd.objectasalprodukfk = $idHibah THEN 0 ELSE $hargaTerakhir END AS harganetto
                from stokprodukdetail_t as spd
                inner JOIN strukpelayanan_t as sk on sk.norec=spd.nostrukterimafk
                inner JOIN asalproduk_m as ap on ap.id=spd.objectasalprodukfk
                where spd.kdprofile = $idProfile $isHibah and spd.statusenabled = true and spd.objectprodukfk =:produkId and spd.objectruanganfk =:ruanganid and spd.qtyproduk > 0
                group by sk.norec,spd.objectprodukfk, $strMSHT, spd.objectasalprodukfk,
                spd.hargadiscount,sk.nostruk,
                spd.objectruanganfk,ap.asalproduk,spd.nostrukterimafk, spd.norec
                order By qtyproduk desc, $strMSHT"),
                array(
                    'produkId' => $request['produkfk'],
                    'ruanganid' => $request['ruanganfk'],
                )
            );

            $results = [];
            $persenUpHargaSatuan = 0;
            $kebangsaan = 1;
            $pesenUpDepos = explode(',', $this->settingFix('marginUpDepo'));
            $pesenUpDepo = [];
            foreach ($pesenUpDepos as $item) {
                // Pisahkan string menjadi id dan value menggunakan explode
                list($id, $value) = explode('|', $item);
                if ($request['ruanganfk'] == $id) {
                    $pesenUpDepo = ['id' => $id, 'value' => (float)$value];
                }
            }
            foreach ($result as $item) {
                $kebangsaan = 1;
                if (isset($pesenUpDepo['id'])) {
                    $persenUpHargaSatuan = (float)$pesenUpDepo['value'];
                } else {
                    foreach ($persenHargaJualProduk as $hitem) {
                        if ((float)$hitem->rangemin < (float)$item->harganetto && (float)$hitem->rangemax > (float)$item->harganetto) {
                            $persenUpHargaSatuan = (float)$hitem->persenuphargasatuan;
                            if (!empty($pasien)) {
                                if ($pasien[0]->objectkebangsaanfk != 1) {
                                    $kebangsaan = 1.5;
                                } else {
                                    $kebangsaan = 1;
                                }
                            } else {
                                $kebangsaan = 1;
                            }
                        }
                    }
                }
                $results[] = array(
                    'norec' => $item->norec,
                    'objectprodukfk' => $item->objectprodukfk,
                    'tgl' => $item->tgl,
                    'objectasalprodukfk' => $item->objectasalprodukfk,
                    'asalproduk' => $item->asalproduk,
                    'harganetto' => (float)$item->harganetto, //$item->harganetto,
                    'hargadiscount' => $item->hargadiscount,
                    // 'objectkebangsaanfk '=> $pasien[0]->objectkebangsaanfk,
                    // 'kebangsaan' => $kebangsaan,
                    'hargajual' => (float)($item->harganetto + (((float)$item->harganetto * (float)$persenUpHargaSatuan) / 100)) * $kebangsaan,
                    'persenhargajualproduk' => $persenUpHargaSatuan,
                    'qtyproduk' => (float)$item->qtyproduk,
                    'totalstok' => $item->totalstok,
                    'objectruanganfk' => $item->objectruanganfk,
                    'nostrukterimafk' => $item->nostrukterimafk,
                    'nostruk' => $item->nostruk,
                    'tglkadaluarsa' => $item->tglkadaluarsa,
                    'persenup' => $persenUpHargaSatuan,
                    'norec_spd' => $item->norec_spd,
                );
            }
        }
        // ### END-Harga Terakhir ### //
        $jmlstok = 0;
        foreach ($result as $item) {
            $jmlstok = $jmlstok + $item->qtyproduk;
        }

        $getKekuatan = DB::table('produk_m as pr')
            ->leftjoin('rm_sediaan_m as sdn', 'sdn.id', 'pr.objectsediaanfk')
            ->select('pr.kekuatan', 'sdn.name', 'pr.isfornas', 'pr.objectdetailjenisprodukfk')
            ->where('pr.kdprofile', $this->kdProfile)
            ->where('pr.id', $request['produkfk'])
            ->first();


        // $cekKekuatanSupranatural = DB::select(
        //     DB::raw("

        //     select pr.kekuatan,sdn.name as sediaan from produk_m as pr
        //     inner join rm_sediaan_m as sdn on sdn.id=pr.objectsediaanfk
        //     where pr.kdprofile = $idProfile and pr.id=:produkfk;

        //     "),
        //     array(
        //         'produkfk' =>  $request['produkfk'],
        //     )
        // );
        $kekuatan = 0;
        $sediaan = '';
        $fornas = '';
        $objectdetailjenisprodukfk = '';
        if ($getKekuatan) {
            $kekuatan = $getKekuatan->kekuatan;
            $sediaan = $getKekuatan->name;
            $fornas = $getKekuatan->isfornas;
            $objectdetailjenisprodukfk = $getKekuatan->objectdetailjenisprodukfk;
        }
        // $kekuatan = 0;
        // $sediaan = 0;
        // if()
        // if (count($cekKekuatanSupranatural) > 0) {
        //     $kekuatan = (float)$cekKekuatanSupranatural[0]->kekuatan;
        //     $sediaan = $cekKekuatanSupranatural[0]->sediaan;
        //     if ($kekuatan == null) {
        //         $kekuatan = 0;
        //     }
        // }


        $result = array(
            'detail' => $results,
            'jmlstok' => $jmlstok,
            'kekuatan' => $kekuatan,
            'sediaan' => $sediaan,
            'fornas' => $fornas,
            'objectdetailjenisprodukfk' => $objectdetailjenisprodukfk,
            'sistemharganetto' => $SistemHargaNetto,
            'metodeambilharganetto' => $MetodeAmbilHargaNetto,
            'metodestokharganetto' => $MetodeStokHargaNetto,
            'message' => 'as@epic',
        );
        if ($lokal) {
            return $result;
        }
        return $this->respond($result);
    }
    
    public function getProdukDetailCeklis(Request $request, $lokal = false)
    {
        ini_set('max_execution_time', 1000);
        // $dataKonversiProduk = DB::table('konversisatuan_t as ks')
        // ->JOIN('satuanstandar_m as ss', 'ss.id', '=', 'ks.satuanstandar_asal')
        // ->JOIN('satuanstandar_m as ss2', 'ss2.id', '=', 'ks.satuanstandar_tujuan')
        // ->select(
        //     'ks.objekprodukfk',
        //     'ks.satuanstandar_asal',
        //     'ss.satuanstandar',
        //     'ks.satuanstandar_tujuan',
        //     'ss2.satuanstandar as satuanstandar2',
        //     'ks.nilaikonversi'
        // )
        // ->where('ks.kdprofile', $idProfile)
        // ->where('ks.statusenabled', true)
        // ->get();

        $results = [];

        $pasien = DB::select(DB::raw("select ps.objectkebangsaanfk
            from antrianpasiendiperiksa_t as apd
            join pasiendaftar_t as pd  on pd.norec=apd.noregistrasifk and apd.kdprofile =pd.kdprofile
            join pasien_m as ps  on ps.id=pd.nocmfk and pd.kdprofile =ps.kdprofile
            where apd.norec = '" . $request['norec_apd'] . "'"));

        $SistemHargaNetto = 'Harga Tertinggi';
        $strMetodeAmbilHargaNetto = 1;
        $strMetodeStokHargaNetto = 1;
        $idHibah = $this->settingFix('objectasalprodukHibah');
        $isHibah = '';
        // if (isset($request['isdonasi'])) {
        //     $isHibah = 'and objectasalprodukfk = ' . $idHibah;
        // } else {
        //     $isHibah = 'and objectasalprodukfk <> ' . $idHibah;
        //      $isHibah = 'and objectasalprodukfk = ' . $idHibah;
        // }
        $idProfile = $this->kdProfile;
        $kdJenisTransOA = $this->settingFix('jenisTransaksiOA');
        $jenisTransaksi = DB::table('jenistransaksi_m as jt')
            ->where('jt.id', $kdJenisTransOA)
            ->where('jt.statusenabled', true)
            ->first();

        if (empty($jenisTransaksi)) {
            return $this->respond(null, 500, 'Setting jenistransaksi_m dulu');
        }
        $strMetodeAmbilHargaNetto = $jenisTransaksi->metodeambilharganetto;
        //        $strMetodeHargaNetto = $jenisTransaksi->metodeharganetto; //ketika penerimaan saja
        $strMetodeStokHargaNetto = $jenisTransaksi->metodestokharganetto;
        $strSistemHargaNetto = $jenisTransaksi->sistemharganetto;

        if (empty($strMetodeAmbilHargaNetto) || empty($strMetodeAmbilHargaNetto) || empty($strMetodeAmbilHargaNetto)) {
            return $this->respond(null, 500, 'Setting Data Fixed Belum ada');
        }
        $defaultKP = 0;
        $defaultKP = 0;
        if (isset($request['kpid']) && $request['kpid'] != "" && $request['kpid'] != "undefined") {
            $defaultKP = $request['kpid'];
        }

        $persenHargaJualProduk = DB::table('persenhargajualproduk_m as phjp')
            ->JOIN('range_m as rg', 'rg.id', '=', 'phjp.objectrangefk')
            ->select('rg.rangemin', 'rg.rangemax', 'phjp.persenuphargasatuan', 'phjp.objectrangefk', 'phjp.objectkelompokpasienfk')
            ->where('phjp.objectjenistransaksifk', $this->settingFix('jenisTransaksiOA'))
            ->where('phjp.statusenabled', true)
            ->where('phjp.objectkelompokpasienfk', $defaultKP)
            ->get();

        if (count($persenHargaJualProduk) == 0) {
            return $this->respond(array(
                'Error' => 'Setting persenhargajualproduk_m dulu',
                'message' => 'as@epic',
            ));
        }


        $kelompokuser = DB::table('loginuser_s')
                        ->select('id','namauser','objectkelompokuserfk')
                        ->where('statusenabled', true)
                        ->where('id', $this->getUserId())
                        ->get();
                        
        

        $strSistemHargaNetto = 3;
        if ($strSistemHargaNetto == 3) {
            $SistemHargaNetto = 'Harga Tertinggi';
            if ($strMetodeAmbilHargaNetto == 1) { //HN1
                $strHN = 'spd.harganetto1';
                $MetodeAmbilHargaNetto = 'HN1';
            }
            if ($strMetodeAmbilHargaNetto == 2) { //HN2
                $strHN = 'spd.harganetto2';
                $MetodeAmbilHargaNetto = 'HN2';
            }

            if ($strMetodeStokHargaNetto == 1) { //FIFO
                $strMSHT = 'sk.tglstruk';
                $MetodeStokHargaNetto = 'FIFO';
            }
            if ($strMetodeStokHargaNetto == 2) { //LIFO
                $strMSHT = '';
                $MetodeStokHargaNetto = 'LIFO';
            }
            if ($strMetodeStokHargaNetto == 3) { //FEFO
                $strMSHT = 'spd.tglkadaluarsa';
                $MetodeStokHargaNetto = 'FEFO';
            }
            if ($strMetodeStokHargaNetto == 4) { //LEFO
                $strMSHT = '';
                $MetodeStokHargaNetto = 'LEFO';
            }
            if ($strMetodeStokHargaNetto == 5) { //Summary
                $strMSHT = '';
                $MetodeStokHargaNetto = 'Summary';
            }

            $filterSerch = "";
            if (isset($request['namaproduk']) && !empty($request['namaproduk'])) {
                $filterSerch = " and (pr.namaproduk ILIKE '%" . $request['namaproduk'] . "%'";

            
                if (isset($request['namaproduk']) && !empty($request['namaproduk'])) {
                    $filterSerch .= " OR pr.kdproduk ILIKE '%" . $request['namaproduk'] . "%'";
                }

                if (isset($request['namaproduk']) && !empty($request['namaproduk'])) {
                    $filterSerch .= " OR pr.id = " . (int)$request['namaproduk'];
                }

                $filterSerch .= ")";
            }

            $filterKdPrd = "";
            if (isset($request['kodeproduk']) && $request['kodeproduk'] != "") {
                $filterKdPrd = " and cast(pr.id as varchar) ilike '%" . $request['kodeproduk'] . "%'";
            }

            $filterproduk = "";
            if (isset($request['jenisobat']) && $request['jenisobat'] != "" && $request['jenisobat'] != "undefined" && $request['jenisobat'] == "d") {
                $filterproduk = "and pr.objectsubkategoryfk = 8";
            }
            else 
            {
                $filterproduk = "and (pr.objectsubkategoryfk != 8 or pr.objectsubkategoryfk is null)";
            }
            // if($kelompokuser[0]->objectkelompokuserfk == 10)
            // {
            //     $filterproduk = "and pr.objectdetailjenisprodukfk = 2546"; 
            // }

            // if  ($kelompokuser[0]->objectkelompokuserfk == 8)
            // {
            //     $filterproduk = "and pr.objectdetailjenisprodukfk in (2547,2549)"; 
            // }

            $orderBy = '';
            if ($request['kpid'] == 2) {
                $orderBy = 'ORDER BY pr.namaproduk ASC';
            } else {
                $orderBy = 'ORDER BY 
                        CASE 
                            WHEN pr.isfornas = false THEN 0
                            WHEN pr.isfornas IS NULL THEN 1
                            WHEN pr.isfornas = true THEN 2
                        END ASC,
                        pr.namaproduk ASC';
                            }
            $result = DB::select(
                DB::raw("select 
                    --sk.norec,
                     spd.objectprodukfk,
                    --$strMSHT as tgl,
                     spd.objectasalprodukfk,
                    --$strHN as hargajual,
                     spd.hargadiscount,
                    spd.objectruanganfk, 
                    ap.asalproduk, 
                    sum(spd.qtyproduk) as qtyproduk,
                    --spd.nostrukterimafk,
                    pr.isfornas, 
                    pr.objectsubkategoryfk,
                    pr.objectdetailjenisprodukfk,
                    --spd.tglkadaluarsa, 
                    --spd.norec as norec_spd,
                    pr.kekuatan,
                        --CASE 
                            --WHEN spd.objectasalprodukfk = $idHibah THEN 0 
                            --ELSE $strHN 
                            --END AS harganetto,
                        pr.namaproduk, 
                        ss.satuanstandar, 
                        kv.nilaikonversi, 
                        ss.id as satuanstandarfk, 
                        pr.kdproduk,
                    (select sum(qtyproduk) as qtyproduk from stokprodukdetail_t where kdprofile = $idProfile and objectprodukfk = spd.objectprodukfk and statusenabled = true) as totalstok,
                    rg.namaruangan as namaruangantujuan from stokprodukdetail_t as spd
                    inner JOIN strukpelayanan_t as sk on sk.norec = spd.nostrukterimafk
                    inner JOIN ruangan_m as rg on rg.id = spd.objectruanganfk
                    inner JOIN asalproduk_m as ap on ap.id = spd.objectasalprodukfk
                    inner join produk_m as pr on pr.id = spd.objectprodukfk
                    left join konversisatuan_t as kv on kv.objekprodukfk = pr.id
                    left join satuanstandar_m as std on std.id = kv.satuanstandar_asal
                    left JOIN satuanstandar_m as ss on ss.id = pr.objectsatuanstandarfk
                    where 
                        spd.kdprofile = $idProfile $isHibah 
                        and spd.statusenabled = true 
                        and pr.statusenabled = true and 
                        spd.objectruanganfk = :ruanganid
                    $filterSerch $filterKdPrd $filterproduk 
                    group by 
                    --sk.norec,
                    pr.namaproduk,
                    pr.kdproduk,
                    pr.objectsubkategoryfk,
                    spd.objectprodukfk,
                    --$strMSHT,
                    --spd.harganetto1,
                    spd.objectasalprodukfk,
                    spd.hargadiscount,
                    pr.kdproduk,
                    pr.kekuatan,
                    spd.objectruanganfk,
                    ap.asalproduk,
                    --spd.nostrukterimafk, 
                    --spd.norec, pr.namaproduk, 
                    ss.satuanstandar, 
                    kv.nilaikonversi, 
                    ss.id, 
                    pr.isfornas, 
                    pr.objectdetailjenisprodukfk, 
                    rg.namaruangan
                    $orderBy
                    --$strMSHT 
                    limit 30
                    "),
                array(
                    'ruanganid' => $request['ruanganfk'],
                )
            );

            foreach ($result as $item) {
                $kebangsaan = 1;
                $stokPengirim = '';
                $stokpengeluaran = '';
                $stokTujuanPengirim = '';

                if (isset($request['ruanganPemesanfk'])) 
                {
                    $ruanganPengirim = $request['ruanganPemesanfk'];
                    $ruanganTujuanPengirim = $request['ruanganfk'];
                    
                    $barangPengirim = $item->objectprodukfk;
                    $stokPengirim = DB::select(
                        DB::raw("select rg.namaruangan, pr.id, sum(spd.qtyproduk) from stokprodukdetail_t as spd
                        join produk_m as pr on pr.id = spd.objectprodukfk
                        join ruangan_m as rg on rg.id = spd.objectruanganfk where spd.objectruanganfk = $ruanganPengirim and spd.objectprodukfk = $barangPengirim and spd.statusenabled = true group by pr.id, rg.namaruangan")
                    );

                    $stokTujuanPengirim = DB::select(
                        DB::raw("select rg.namaruangan, pr.id, sum(spd.qtyproduk) from stokprodukdetail_t as spd
                        join produk_m as pr on pr.id = spd.objectprodukfk
                        join ruangan_m as rg on rg.id = spd.objectruanganfk where spd.objectruanganfk = $ruanganTujuanPengirim and spd.objectprodukfk = $barangPengirim and spd.statusenabled = true group by pr.id, rg.namaruangan")
                    );

                    $departemen = DB::table('ruangan_m')
                    ->select('id','namaruangan','objectdepartemenfk')
                    ->where('statusenabled', true)
                    ->where('id', $request['ruanganPemesanfk'])
                    ->first();

                    if((int)$departemen->objectdepartemenfk == 14)
                    {
                        $sevenDaysAgo = now()->subDays(7)->toDateString();
                        $stokpengeluaran = DB::select(
                            DB::raw("select SUM(pp.jumlah),pp.produkfk 
                                        from pelayananpasien_t as pp
                                        join strukresep_t as sr on sr.norec = pp.strukresepfk
                                        where sr.ruanganfk = $ruanganPengirim and 
                                        pp.produkfk = $barangPengirim and 
                                        pp.statusenabled = true and 
                                        pp.jumlah > 0 and 
                                        pp.tglpelayanan >= '$sevenDaysAgo'
                                        group by pp.produkfk")
                        );
                    }

                    else
                    {
                        $sevenDaysAgo = now()->subDays(90)->toDateString();
                        $stokpengeluaran = DB::select(
                            DB::raw("select SUM(kp.qtyprodukkonfirmasi),kp.objectprodukfk 
                                        from kirimproduk_t as kp
                                        join strukkirim_t as sk on sk.norec = kp.nokirimfk
                                        where sk.objectruanganfk = $ruanganPengirim and 
                                        kp.objectprodukfk = $barangPengirim and 
                                        kp.statusenabled = true and 
                                        kp.qtyprodukkonfirmasi > 0 and 
                                        kp.tglpelayanan >= '$sevenDaysAgo'
                                        group by kp.objectprodukfk")
                        );
                    }

                }

                

                $AmbilSPD = DB::select(
                    DB::raw("select 
                                    norec,tglkadaluarsa,nostrukterimafk
                                    from stokprodukdetail_t 
                                    where kdprofile = $idProfile $isHibah 
                                    and objectprodukfk =:produkId 
                                    and statusenabled = true 
                                    and objectruanganfk =:ruanganid
                                    and qtyproduk > 0
                                    order by tglpelayanan asc
                                    "), 
                            array(
                                'produkId' => $item->objectprodukfk,
                                'ruanganid' => $request['ruanganfk'],
                            )
                            
                );
                
                // $maxHarga = DB::select(
                //     DB::raw("select 
                //                 CASE 
                //                     WHEN spd.objectasalprodukfk = $idHibah THEN 0 
                //                     ELSE MAX($strHN)
                //                     END  as harga,
                //                     sk.tglstruk,
                //                     sk.norec
                //                 from stokprodukdetail_t as spd
                //                 left JOIN strukpelayanan_t as sk on sk.norec=spd.nostrukterimafk
                //                 where spd.kdprofile = $idProfile $isHibah 
                //                 and spd.objectprodukfk =:produkId 
                //                 and spd.statusenabled = true 
                //                 --and spd.created_at BETWEEN (CURRENT_DATE - INTERVAL '1 year') AND (CURRENT_DATE + INTERVAL '1 day' - INTERVAL '1 second') 
                //                 group by sk.tglstruk,sk.norec,spd.objectasalprodukfk,spd.harganetto1 order by spd.harganetto1 desc limit 1
                //                     "), 
                //             array(
                //                 'produkId' => $item->objectprodukfk,
                //             )
                            
                // );

                $maxHarga = DB::select(
                DB::raw("
                    (
                        SELECT
                            CASE 
                                WHEN spd.objectasalprodukfk = $idHibah THEN 0 
                                ELSE MAX($strHN)
                            END AS harga,
                            sk.tglstruk,
                            sk.norec,
                            1 AS prioritas
                        FROM stokprodukdetail_t AS spd
                        LEFT JOIN strukpelayanan_t AS sk ON sk.norec = spd.nostrukterimafk
                        WHERE spd.kdprofile = $idProfile 
                            $isHibah
                            AND spd.objectprodukfk = :produkId 
                            AND spd.statusenabled = TRUE
                            --AND spd.created_at BETWEEN '2025-01-01 00:00:00' AND NOW()
                        GROUP BY sk.tglstruk, sk.norec, spd.objectasalprodukfk, spd.harganetto1
                    )
                    UNION ALL
                    (
                        SELECT 
                            CASE 
                                WHEN spd.objectasalprodukfk = $idHibah THEN 0 
                                ELSE MAX($strHN)
                            END AS harga,
                            sk.tglstruk,
                            sk.norec,
                            2 AS prioritas
                        FROM stokprodukdetail_t AS spd
                        LEFT JOIN strukpelayanan_t AS sk ON sk.norec = spd.nostrukterimafk
                        WHERE spd.kdprofile = $idProfile 
                            $isHibah
                            AND spd.objectprodukfk = :produkId 
                            AND spd.statusenabled = TRUE
                            --AND spd.created_at BETWEEN (NOW() - INTERVAL '1 year') AND NOW()
                        GROUP BY sk.tglstruk, sk.norec, spd.objectasalprodukfk, spd.harganetto1
                    )
                    ORDER BY prioritas ASC, harga DESC 
                    LIMIT 1
                "),
                [
                    'produkId' => $item->objectprodukfk,
                ]
            );
                $norec = null;
                $harga = null;
                $tglstruk = null;
                if (isset($maxHarga[0])) {
                    $norec = $maxHarga[0]->norec ?? null;
                    $harga = $maxHarga[0]->harga ?? null;
                    $tglstruk = $maxHarga[0]->tglstruk ?? null;
                }

                $hargaTertinggi = 0;
                foreach ($maxHarga as $item1) {
                    if ($hargaTertinggi < (float)$item1->harga) 
                    {
                        $hargaTertinggi = (float)$item1->harga;
                    }
                }

                $persenUpHargaSatuan = 0;
                $pesenUpDepos = explode(',', $this->settingFix('marginUpDepo'));
                $pesenUpDepo = [];
                foreach ($pesenUpDepos as $item2) {
                    list($id, $value) = explode('|', $item2);
                    if ($request['ruanganfk'] == $id) {
                        $pesenUpDepo = ['id' => $id, 'value' => (float)$value];
                    }
                }

                if (isset($pesenUpDepo['id'])) 
                {
                    $persenUpHargaSatuan = (float)$pesenUpDepo['value'];
                } 
                else
                {
                    //  foreach ($persenHargaJualProduk as $hitem) {
                    //     if ((float)$hitem->rangemin < (float)$maxHarga[0]->harga && (float)$hitem->rangemax > (float)$maxHarga[0]->harga) {
                    //         $persenUpHargaSatuan = (float)$hitem->persenuphargasatuan;
                    //         if (!empty($pasien)) {
                    //             if ($pasien[0]->objectkebangsaanfk != 1) {
                    //                 $kebangsaan = 1.5;
                    //             } else {
                    //                 $kebangsaan = 1;
                    //             }
                    //         } else {
                    //             $kebangsaan = 1;
                    //         }
                    //     }
                    // }

                    foreach ($persenHargaJualProduk as $hitem) {
                        if ((float)$hitem->rangemin < (float)$harga && (float)$hitem->rangemax > (float)$harga) {
                            $persenUpHargaSatuan = (float)$hitem->persenuphargasatuan;
                            if (!empty($pasien)) {
                                if ($pasien[0]->objectkebangsaanfk != 1) {
                                    $kebangsaan = 1.5;
                                } else {
                                    $kebangsaan = 1;
                                }
                            } else {
                                $kebangsaan = 1;
                            }
                        }
                    }
                }

                $results[] = array(
                    'norec' => $norec,//isset($maxHarga[0]->norec) ? $maxHarga[0]->norec :null,
                    'kdproduk' => $item->kdproduk,
                    'kpid' => $request['kpid'],
                    'id' =>  $item->objectprodukfk,
                    'namaprodukuse' => "Produk :" . $item->namaproduk,
                    'namaproduk' => $item->namaproduk,
                    'fornas' => $item->isfornas,
                    'objectsubkategoryfk' => $item->objectsubkategoryfk,
                    'kekuatan' => $item->kekuatan,
                    'objectdetailjenisprodukfk' => $item->objectdetailjenisprodukfk,
                    'tgl' => $tglstruk,//isset($maxHarga[0]->tglstruk) ? $maxHarga[0]->tglstruk : null,
                    'objectasalprodukfk' => $item->objectasalprodukfk,
                    'asalproduk' => $item->asalproduk,
                    'harganetto' => $harga,//isset($maxHarga[0]->harga) ? $maxHarga[0]->harga :null,
                    'totalstok' => $item->totalstok,
                    'hargadiscount' => $item->hargadiscount,
                    'kebangsaan' => $kebangsaan ? $kebangsaan : null,
                    // 'hargajual' => isset($maxHarga[0]->harga) ? (float)($maxHarga[0]->harga + (((float)$maxHarga[0]->harga * (float)$persenUpHargaSatuan) / 100)) * $kebangsaan : null,
                    'hargajual' => (float)($harga + (((float)$harga * (float)$persenUpHargaSatuan) / 100)) * $kebangsaan,
                    'persenhargajualproduk' => $persenUpHargaSatuan,
                    'qtyproduk' => (float)$item->qtyproduk,
                    'pengeluaran' => isset($stokpengeluaran) && is_array($stokpengeluaran) && count($stokpengeluaran) != 0 ? (float)$stokpengeluaran[0]->sum : 0,
                    'avgpemakaian' => isset($stokpengeluaran) && is_array($stokpengeluaran) && count($stokpengeluaran) != 0 ? (float)($stokpengeluaran[0]->sum)/7 : 0,
                    'kebutuhan6bulan' => isset($stokpengeluaran) && is_array($stokpengeluaran) && count($stokpengeluaran) != 0 ? (float)(($stokpengeluaran[0]->sum)/7)*5.7 : 0,
                    'rencanakebutuhan' => isset($stokpengeluaran) && is_array($stokpengeluaran) && count($stokpengeluaran) != 0 ? (float)($stokpengeluaran[0]->sum)/7*5.7 - (float)$stokPengirim[0]->sum : 0,
                    'minstok' => isset($stokpengeluaran) && is_array($stokpengeluaran) && count($stokpengeluaran) != 0 ? (float)(($stokpengeluaran[0]->sum)/7)*2 : 0,
                    'maxstock' => isset($stokpengeluaran) && is_array($stokpengeluaran) && count($stokpengeluaran) != 0 ? (float)((($stokpengeluaran[0]->sum)/7)*2)+(5*(float)($stokpengeluaran[0]->sum)/7) : 0,
                    'tingkatkecukupan' => isset($stokpengeluaran) && is_array($stokpengeluaran) && count($stokpengeluaran) != 0 ? (float)((float)$item->qtyproduk/($stokpengeluaran[0]->sum)/7) : 0,
                    'qtyprodukpengirim' => isset($stokPengirim) && is_array($stokPengirim) && count($stokPengirim) != 0 ? (float)$stokPengirim[0]->sum : 0,
                    'qtyproduktujuanpengirim' => isset($stokTujuanPengirim) && is_array($stokTujuanPengirim) && count($stokTujuanPengirim) != 0 ? (float)$stokTujuanPengirim[0]->sum : 0,
                    'objectruanganfk' => $item->objectruanganfk,
                    'namaruanganpengirim' => isset($stokPengirim) && is_array($stokPengirim) && count($stokPengirim) != 0 ? $stokPengirim[0]->namaruangan : 0,
                    'namaruangantujuan' => $item->namaruangantujuan,
                    'nostrukterimafk' => isset($AmbilSPD[0]->nostrukterimafk) ? $AmbilSPD[0]->nostrukterimafk : null,
                    'tglkadaluarsa' => isset($AmbilSPD[0]->tglkadaluarsa) ? $AmbilSPD[0]->tglkadaluarsa : null,
                    'persenup' => $persenUpHargaSatuan,
                    'norec_spd' => isset($AmbilSPD[0]->norec) ? $AmbilSPD[0]->norec : null,
                    'satuanstandar' => $item->satuanstandar,
                    'nilaikonversi' => $item->nilaikonversi,
                    'satuanstandarfk' => $item->satuanstandarfk,
                );
            }
        }

        
        return $this->respond($results);
    }

    public function getProdukDetailCeklis2(Request $request, $lokal = false)
    {
        ini_set('max_execution_time', 1000);
       
        $results = [];

        $pasien = DB::select(DB::raw("select ps.objectkebangsaanfk
            from antrianpasiendiperiksa_t as apd
            join pasiendaftar_t as pd  on pd.norec=apd.noregistrasifk and apd.kdprofile =pd.kdprofile
            join pasien_m as ps  on ps.id=pd.nocmfk and pd.kdprofile =ps.kdprofile
            where apd.norec = '" . $request['norec_apd'] . "'"));

        $SistemHargaNetto = 'Harga Tertinggi';
        $strMetodeAmbilHargaNetto = 1;
        $strMetodeStokHargaNetto = 1;
        $idHibah = $this->settingFix('objectasalprodukHibah');
        $isHibah = '';
      
        $idProfile = $this->kdProfile;
        $kdJenisTransOA = $this->settingFix('jenisTransaksiOA');
        $jenisTransaksi = DB::table('jenistransaksi_m as jt')
            ->where('jt.id', $kdJenisTransOA)
            ->where('jt.statusenabled', true)
            ->first();

        if (empty($jenisTransaksi)) {
            return $this->respond(null, 500, 'Setting jenistransaksi_m dulu');
        }
        $strMetodeAmbilHargaNetto = $jenisTransaksi->metodeambilharganetto;
        $strMetodeStokHargaNetto = $jenisTransaksi->metodestokharganetto;
        $strSistemHargaNetto = $jenisTransaksi->sistemharganetto;

        if (empty($strMetodeAmbilHargaNetto) || empty($strMetodeAmbilHargaNetto) || empty($strMetodeAmbilHargaNetto)) {
            return $this->respond(null, 500, 'Setting Data Fixed Belum ada');
        }
        $defaultKP = 0;
        $defaultKP = 0;
        if (isset($request['kpid']) && $request['kpid'] != "" && $request['kpid'] != "undefined") {
            $defaultKP = $request['kpid'];
        }

        $persenHargaJualProduk = DB::table('persenhargajualproduk_m as phjp')
            ->JOIN('range_m as rg', 'rg.id', '=', 'phjp.objectrangefk')
            ->select('rg.rangemin', 'rg.rangemax', 'phjp.persenuphargasatuan', 'phjp.objectrangefk', 'phjp.objectkelompokpasienfk')
            ->where('phjp.objectjenistransaksifk', $this->settingFix('jenisTransaksiOA'))
            ->where('phjp.statusenabled', true)
            ->where('phjp.objectkelompokpasienfk', $defaultKP)
            ->get();

        if (count($persenHargaJualProduk) == 0) {
            return $this->respond(array(
                'Error' => 'Setting persenhargajualproduk_m dulu',
                'message' => 'as@epic',
            ));
        }


        $kelompokuser = DB::table('loginuser_s')
                        ->select('id','namauser','objectkelompokuserfk')
                        ->where('statusenabled', true)
                        ->where('id', $this->getUserId())
                        ->get();
                        
        

        $strSistemHargaNetto = 3;
        if ($strSistemHargaNetto == 3) {
            $SistemHargaNetto = 'Harga Tertinggi';
            if ($strMetodeAmbilHargaNetto == 1) { 
                $strHN = 'spd.harganetto1';
                $MetodeAmbilHargaNetto = 'HN1';
            }
            if ($strMetodeAmbilHargaNetto == 2) { 
                $strHN = 'spd.harganetto2';
                $MetodeAmbilHargaNetto = 'HN2';
            }

            if ($strMetodeStokHargaNetto == 1) { 
                $strMSHT = 'sk.tglstruk';
                $MetodeStokHargaNetto = 'FIFO';
            }
            if ($strMetodeStokHargaNetto == 2) { 
                $strMSHT = '';
                $MetodeStokHargaNetto = 'LIFO';
            }
            if ($strMetodeStokHargaNetto == 3) { 
                $strMSHT = 'spd.tglkadaluarsa';
                $MetodeStokHargaNetto = 'FEFO';
            }
            if ($strMetodeStokHargaNetto == 4) {
                $strMSHT = '';
                $MetodeStokHargaNetto = 'LEFO';
            }
            if ($strMetodeStokHargaNetto == 5) {
                $strMSHT = '';
                $MetodeStokHargaNetto = 'Summary';
            }

            $filterSerch = "";
            if (isset($request['namaproduk']) && !empty($request['namaproduk'])) {
                $filterSerch = " and (pr.namaproduk ILIKE '%" . $request['namaproduk'] . "%'";

            
                if (isset($request['namaproduk']) && !empty($request['namaproduk'])) {
                    $filterSerch .= " OR pr.kdproduk ILIKE '%" . $request['namaproduk'] . "%'";
                }

                if (isset($request['namaproduk']) && !empty($request['namaproduk'])) {
                    $filterSerch .= " OR pr.id = " . (int)$request['namaproduk'];
                }

                $filterSerch .= ")";
            }

            $filterKdPrd = "";
            if (isset($request['kodeproduk']) && $request['kodeproduk'] != "") {
                $filterKdPrd = " and cast(pr.id as varchar) ilike '%" . $request['kodeproduk'] . "%'";
            }

            $filterproduk = "";
          
            $result = DB::select(
                DB::raw("select 
                    --sk.norec,
                     spd.objectprodukfk,
                    --$strMSHT as tgl,
                     spd.objectasalprodukfk,
                    --$strHN as hargajual,
                     spd.hargadiscount,
                    spd.objectruanganfk, 
                    ap.asalproduk, 
                    sum(spd.qtyproduk) as qtyproduk,
                    --spd.nostrukterimafk,
                    pr.isfornas, 
                    pr.objectsubkategoryfk,
                    pr.objectdetailjenisprodukfk,
                    --spd.tglkadaluarsa, 
                    --spd.norec as norec_spd,
                    pr.kekuatan,
                        --CASE 
                            --WHEN spd.objectasalprodukfk = $idHibah THEN 0 
                            --ELSE $strHN 
                            --END AS harganetto,
                        pr.namaproduk, 
                        ss.satuanstandar, 
                        kv.nilaikonversi, 
                        ss.id as satuanstandarfk, 
                        pr.kdproduk,
                    (select sum(qtyproduk) as qtyproduk from stokprodukdetail_t where kdprofile = $idProfile and objectprodukfk = spd.objectprodukfk and statusenabled = true) as totalstok,
                    rg.namaruangan as namaruangantujuan from stokprodukdetail_t as spd
                    inner JOIN strukpelayanan_t as sk on sk.norec = spd.nostrukterimafk
                    inner JOIN ruangan_m as rg on rg.id = spd.objectruanganfk
                    inner JOIN asalproduk_m as ap on ap.id = spd.objectasalprodukfk
                    inner join produk_m as pr on pr.id = spd.objectprodukfk
                    left join konversisatuan_t as kv on kv.objekprodukfk = pr.id
                    left join satuanstandar_m as std on std.id = kv.satuanstandar_asal
                    left JOIN satuanstandar_m as ss on ss.id = pr.objectsatuanstandarfk
                    where 
                        spd.kdprofile = $idProfile $isHibah 
                        and spd.statusenabled = true 
                        and pr.statusenabled = true and 
                        spd.objectruanganfk = :ruanganid
                    $filterSerch $filterKdPrd 
                    group by 
                    --sk.norec,
                    pr.namaproduk,
                    pr.kdproduk,
                    pr.objectsubkategoryfk,
                    spd.objectprodukfk,
                    --$strMSHT,
                    --spd.harganetto1,
                    spd.objectasalprodukfk,
                    spd.hargadiscount,
                    pr.kdproduk,
                    pr.kekuatan,
                    spd.objectruanganfk,
                    ap.asalproduk,
                    --spd.nostrukterimafk, 
                    --spd.norec, pr.namaproduk, 
                    ss.satuanstandar, 
                    kv.nilaikonversi, 
                    ss.id, 
                    pr.isfornas, 
                    pr.objectdetailjenisprodukfk, 
                    rg.namaruangan
                    order By 
                    pr.namaproduk
                    --$strMSHT 
                    limit 30
                    "),
                array(
                    'ruanganid' => $request['ruanganfk'],
                )
            );

         
            foreach ($result as $item) {
                $ruanganpengiriselect = "";
                $stokPengirim = '';
                $stokpengeluaran = '';
                $stokTujuanPengirim = '';

                if (isset($request['ruanganPemesanfk'])) 
                {
                    $ruanganPengirim = $request['ruanganPemesanfk'];
                    $ruanganTujuanPengirim = $request['ruanganfk'];
                    
                    $barangPengirim = $item->objectprodukfk;
                    $stokPengirim = DB::select(
                        DB::raw("select rg.namaruangan, pr.id, sum(spd.qtyproduk) from stokprodukdetail_t as spd
                        join produk_m as pr on pr.id = spd.objectprodukfk
                        join ruangan_m as rg on rg.id = spd.objectruanganfk where spd.objectruanganfk = $ruanganPengirim and spd.objectprodukfk = $barangPengirim and spd.statusenabled = true group by pr.id, rg.namaruangan")
                    );

                    $stokTujuanPengirim = DB::select(
                        DB::raw("select rg.namaruangan, pr.id, sum(spd.qtyproduk) from stokprodukdetail_t as spd
                        join produk_m as pr on pr.id = spd.objectprodukfk
                        join ruangan_m as rg on rg.id = spd.objectruanganfk where spd.objectruanganfk = $ruanganTujuanPengirim and spd.objectprodukfk = $barangPengirim and spd.statusenabled = true group by pr.id, rg.namaruangan")
                    );

                    $departemen = DB::table('ruangan_m')
                    ->select('id','namaruangan','objectdepartemenfk')
                    ->where('statusenabled', true)
                    ->where('id', $request['ruanganPemesanfk'])
                    ->first();

                    if((int)$departemen->objectdepartemenfk == 14)
                    {
                        $sevenDaysAgo = now()->subDays(30)->toDateString();
                        $stokpengeluaran = DB::select(
                            DB::raw("select SUM(pp.jumlah),pp.produkfk 
                                        from pelayananpasien_t as pp
                                        join strukresep_t as sr on sr.norec = pp.strukresepfk
                                        where sr.ruanganfk = $ruanganPengirim and 
                                        pp.produkfk = $barangPengirim and 
                                        pp.statusenabled = true and 
                                        pp.jumlah > 0 and 
                                        pp.tglpelayanan >= '$sevenDaysAgo'
                                        group by pp.produkfk")
                        );
                    }

                    else
                    {
                        $sevenDaysAgo = now()->subDays(90)->toDateString();
                        $stokpengeluaran = DB::select(
                            DB::raw("select SUM(kp.qtyprodukkonfirmasi),kp.objectprodukfk 
                                        from kirimproduk_t as kp
                                        join strukkirim_t as sk on sk.norec = kp.nokirimfk
                                        where sk.objectruanganfk = $ruanganPengirim and 
                                        kp.objectprodukfk = $barangPengirim and 
                                        kp.statusenabled = true and 
                                        kp.qtyprodukkonfirmasi > 0 and 
                                        kp.tglpelayanan >= '$sevenDaysAgo'
                                        group by kp.objectprodukfk")
                        );
                    }

                }

                $AmbilSPD = DB::select(
                    DB::raw("select 
                                    norec,tglkadaluarsa,nostrukterimafk
                                    from stokprodukdetail_t 
                                    where kdprofile = $idProfile $isHibah 
                                    and objectprodukfk =:produkId 
                                    and statusenabled = true 
                                    and objectruanganfk =:ruanganid
                                    and qtyproduk > 0
                                    order by tglpelayanan asc
                                    "), 
                            array(
                                'produkId' => $item->objectprodukfk,
                                'ruanganid' => $request['ruanganfk'],
                            )
                            
                );
                
                $maxHarga = DB::select(
                    DB::raw("select 
                                CASE 
                                    WHEN spd.objectasalprodukfk = $idHibah THEN 0 
                                    ELSE MAX($strHN)
                                    END  as harga,
                                    sk.tglstruk,
                                    sk.norec
                                from stokprodukdetail_t as spd
                                inner JOIN strukpelayanan_t as sk on sk.norec=spd.nostrukterimafk
                                where spd.kdprofile = $idProfile $isHibah 
                                and spd.objectprodukfk =:produkId 
                                and spd.statusenabled = true 
                                and spd.objectruanganfk =:ruanganid
                                    and spd.created_at BETWEEN (CURRENT_DATE - INTERVAL '1 year') AND (CURRENT_DATE + INTERVAL '1 day' - INTERVAL '1 second') 
                                    group by sk.tglstruk,sk.norec,spd.objectasalprodukfk limit 1
                                    "), // Filter satu tahun terakhir
                            array(
                                'produkId' => $item->objectprodukfk,
                                'ruanganid' => $request['ruanganfk'],
                            )
                            
                );

                $hargaTertinggi = 0;
                foreach ($maxHarga as $item1) {
                    if ($hargaTertinggi < (float)$item1->harga) 
                    {
                        $hargaTertinggi = (float)$item1->harga;
                    }
                }

                $persenUpHargaSatuan = 0;
                $pesenUpDepos = explode(',', $this->settingFix('marginUpDepo'));
                $pesenUpDepo = [];
                foreach ($pesenUpDepos as $item2) {
                    list($id, $value) = explode('|', $item2);
                    if ($request['ruanganfk'] == $id) {
                        $pesenUpDepo = ['id' => $id, 'value' => (float)$value];
                    }
                }
                if (isset($pesenUpDepo['id'])) {
                    $persenUpHargaSatuan = (float)$pesenUpDepo['value'];
                } else {
                    foreach ($persenHargaJualProduk as $hitem) {
                        $kebangsaan = 1;
                        $persenUpHargaSatuan = (float)$hitem->persenuphargasatuan;
                        if (!empty($pasien)) {
                            if ($pasien[0]->objectkebangsaanfk != 1) {
                                $kebangsaan = 1.5;
                            } else {
                                $kebangsaan = 1;
                            }
                        } else {
                            $kebangsaan = 1;
                        }

                    }
                }

                if((int)$departemen->objectdepartemenfk == 14)
                {
                    $results[] = array(
                        'norec' => isset($maxHarga[0]->norec) ? $maxHarga[0]->norec :null,
                        'kdproduk' => $item->kdproduk,
                        'id' =>  $item->objectprodukfk,
                        'namaprodukuse' => "Produk :" . "[" . $item->kdproduk . "]" . "-" . $item->namaproduk,
                        'namaproduk' => $item->namaproduk,
                        'fornas' => $item->isfornas,
                        'objectsubkategoryfk' => $item->objectsubkategoryfk,
                        'kekuatan' => $item->kekuatan,
                        'objectdetailjenisprodukfk' => $item->objectdetailjenisprodukfk,
                        'tgl' => isset($maxHarga[0]->tglstruk) ? $maxHarga[0]->tglstruk : null,
                        'objectasalprodukfk' => $item->objectasalprodukfk,
                        'asalproduk' => $item->asalproduk,
                        'harganetto' => isset($maxHarga[0]->harga) ? $maxHarga[0]->harga :null,
                        'totalstok' => $item->totalstok,
                        'hargadiscount' => $item->hargadiscount,
                        'hargajual' => isset($maxHarga[0]->harga) ? (float)($maxHarga[0]->harga + (((float)$maxHarga[0]->harga * (float)$persenUpHargaSatuan) / 100)) * $kebangsaan : null,
                        'persenhargajualproduk' => $persenUpHargaSatuan,
                        'qtyproduk' => ceil((float)$item->qtyproduk),
                        'pengeluaran' => isset($stokpengeluaran) && is_array($stokpengeluaran) && count($stokpengeluaran) != 0 ? (float)$stokpengeluaran[0]->sum : 0,
                        'avgpemakaian' => isset($stokpengeluaran) && is_array($stokpengeluaran) && count($stokpengeluaran) != 0 ? (float)ceil(($stokpengeluaran[0]->sum)/30) : 0,
                        'kebutuhan6bulan' => isset($stokpengeluaran) && is_array($stokpengeluaran) && count($stokpengeluaran) != 0 ? (float)ceil(ceil(($stokpengeluaran[0]->sum)/30)*6) : 0,
                        'rencanakebutuhan' => isset($stokpengeluaran) && is_array($stokpengeluaran) && count($stokpengeluaran) != 0 ? (float)ceil(ceil(($stokpengeluaran[0]->sum)/30)*6) - (float)$stokPengirim[0]->sum : 0,
                        'minstok' => isset($stokpengeluaran) && is_array($stokpengeluaran) && count($stokpengeluaran) != 0 ? (float)ceil(ceil(($stokpengeluaran[0]->sum)/30)*4) : 0,
                        'maxstock' => isset($stokpengeluaran) && is_array($stokpengeluaran) && count($stokpengeluaran) != 0 
                        ? (float)(ceil(ceil(($stokpengeluaran[0]->sum)/30)*4)) + (6*ceil(($stokpengeluaran[0]->sum)/30))
                        : 0,
                        'tingkatkecukupan' => isset($stokpengeluaran) && is_array($stokpengeluaran) && count($stokpengeluaran) != 0 ? (float)(ceil(($stokpengeluaran[0]->sum)/30)*2)+(6*((float)ceil($stokpengeluaran[0]->sum)/30)) : 0,
                        'qtyprodukpengirim' => isset($stokPengirim) && is_array($stokPengirim) && count($stokPengirim) != 0 ? ceil((float)$stokPengirim[0]->sum) : 0,
                        'qtyproduktujuanpengirim' => isset($stokTujuanPengirim) && is_array($stokTujuanPengirim) && count($stokTujuanPengirim) != 0 ? (float)$stokTujuanPengirim[0]->sum : 0,
                        'objectruanganfk' => $item->objectruanganfk,
                        'namaruanganpengirim' => isset($stokPengirim) && is_array($stokPengirim) && count($stokPengirim) != 0 ? $stokPengirim[0]->namaruangan : 0,
                        'namaruangantujuan' => $item->namaruangantujuan,
                        'nostrukterimafk' => isset($AmbilSPD[0]->nostrukterimafk) ? $AmbilSPD[0]->nostrukterimafk : null,
                        'tglkadaluarsa' => isset($AmbilSPD[0]->tglkadaluarsa) ? $AmbilSPD[0]->tglkadaluarsa : null,
                        'persenup' => $persenUpHargaSatuan,
                        'norec_spd' => isset($AmbilSPD[0]->norec) ? $AmbilSPD[0]->norec : null,
                        'satuanstandar' => $item->satuanstandar,
                        'nilaikonversi' => $item->nilaikonversi,
                        'satuanstandarfk' => $item->satuanstandarfk,
                    );
                }
                else
                {
                    $results[] = array(
                        'norec' => isset($maxHarga[0]->norec) ? $maxHarga[0]->norec :null,
                        'kdproduk' => $item->kdproduk,
                        'id' =>  $item->objectprodukfk,
                        'namaprodukuse' => "Produk :" . "[" . $item->kdproduk . "]" . "-" . $item->namaproduk,
                        'namaproduk' => $item->namaproduk,
                        'fornas' => $item->isfornas,
                        'objectsubkategoryfk' => $item->objectsubkategoryfk,
                        'kekuatan' => $item->kekuatan,
                        'objectdetailjenisprodukfk' => $item->objectdetailjenisprodukfk,
                        'tgl' => isset($maxHarga[0]->tglstruk) ? $maxHarga[0]->tglstruk : null,
                        'objectasalprodukfk' => $item->objectasalprodukfk,
                        'asalproduk' => $item->asalproduk,
                        'harganetto' => isset($maxHarga[0]->harga) ? $maxHarga[0]->harga :null,
                        'totalstok' => $item->totalstok,
                        'hargadiscount' => $item->hargadiscount,
                        'hargajual' => isset($maxHarga[0]->harga) ? (float)($maxHarga[0]->harga + (((float)$maxHarga[0]->harga * (float)$persenUpHargaSatuan) / 100)) * $kebangsaan : null,
                        'persenhargajualproduk' => $persenUpHargaSatuan,
                        'qtyproduk' => ceil((float)$item->qtyproduk),
                        'pengeluaran' => isset($stokpengeluaran) && is_array($stokpengeluaran) && count($stokpengeluaran) != 0 ? (float)$stokpengeluaran[0]->sum : 0,
                        'avgpemakaian' => isset($stokpengeluaran) && is_array($stokpengeluaran) && count($stokpengeluaran) != 0 ? (float)ceil(($stokpengeluaran[0]->sum)/12) : 0,
                        'kebutuhan6bulan' => isset($stokpengeluaran) && is_array($stokpengeluaran) && count($stokpengeluaran) != 0 ? (float)ceil(ceil(($stokpengeluaran[0]->sum)/12)*5.7) : 0,
                        'rencanakebutuhan' => isset($stokpengeluaran) && is_array($stokpengeluaran) && count($stokpengeluaran) != 0 ? (float)ceil(ceil(($stokpengeluaran[0]->sum)/12)*5.7) - (float)$stokPengirim[0]->sum : 0,
                        'minstok' => isset($stokpengeluaran) && is_array($stokpengeluaran) && count($stokpengeluaran) != 0 ? (float)ceil(ceil(($stokpengeluaran[0]->sum)/12)*1.5) : 0,
                        'maxstock' => isset($stokpengeluaran) && is_array($stokpengeluaran) && count($stokpengeluaran) != 0 
                        ? (float) ceil(ceil($stokpengeluaran[0]->sum / 12) * 1.5 + ceil((5.7 * ceil($stokpengeluaran[0]->sum / 12)))) 
                        : 0,
                        'tingkatkecukupan' => isset($stokpengeluaran) && is_array($stokpengeluaran) && count($stokpengeluaran) != 0 ? (float)(ceil(($stokpengeluaran[0]->sum)/12)*1.5)+(5.7*((float)ceil($stokpengeluaran[0]->sum)/12)) : 0,
                        'qtyprodukpengirim' => isset($stokPengirim) && is_array($stokPengirim) && count($stokPengirim) != 0 ? ceil((float)$stokPengirim[0]->sum) : 0,
                        'qtyproduktujuanpengirim' => isset($stokTujuanPengirim) && is_array($stokTujuanPengirim) && count($stokTujuanPengirim) != 0 ? (float)$stokTujuanPengirim[0]->sum : 0,
                        'objectruanganfk' => $item->objectruanganfk,
                        'namaruanganpengirim' => isset($stokPengirim) && is_array($stokPengirim) && count($stokPengirim) != 0 ? $stokPengirim[0]->namaruangan : 0,
                        'namaruangantujuan' => $item->namaruangantujuan,
                        'nostrukterimafk' => isset($AmbilSPD[0]->nostrukterimafk) ? $AmbilSPD[0]->nostrukterimafk : null,
                        'tglkadaluarsa' => isset($AmbilSPD[0]->tglkadaluarsa) ? $AmbilSPD[0]->tglkadaluarsa : null,
                        'persenup' => $persenUpHargaSatuan,
                        'norec_spd' => isset($AmbilSPD[0]->norec) ? $AmbilSPD[0]->norec : null,
                        'satuanstandar' => $item->satuanstandar,
                        'nilaikonversi' => $item->nilaikonversi,
                        'satuanstandarfk' => $item->satuanstandarfk,
                    );
                }
                
            }
        }


        return $this->respond($results);
    }

    public function getProdukDetailAntibiotikProfilaksis(Request $request, $lokal = false)
    {
        ini_set('max_execution_time', 1000);
        // $dataKonversiProduk = DB::table('konversisatuan_t as ks')
        // ->JOIN('satuanstandar_m as ss', 'ss.id', '=', 'ks.satuanstandar_asal')
        // ->JOIN('satuanstandar_m as ss2', 'ss2.id', '=', 'ks.satuanstandar_tujuan')
        // ->select(
        //     'ks.objekprodukfk',
        //     'ks.satuanstandar_asal',
        //     'ss.satuanstandar',
        //     'ks.satuanstandar_tujuan',
        //     'ss2.satuanstandar as satuanstandar2',
        //     'ks.nilaikonversi'
        // )
        // ->where('ks.kdprofile', $idProfile)
        // ->where('ks.statusenabled', true)
        // ->get();

        $results = [];

        $pasien = DB::select(DB::raw("select ps.objectkebangsaanfk
            from antrianpasiendiperiksa_t as apd
            join pasiendaftar_t as pd  on pd.norec=apd.noregistrasifk and apd.kdprofile =pd.kdprofile
            join pasien_m as ps  on ps.id=pd.nocmfk and pd.kdprofile =ps.kdprofile
            where apd.norec = '" . $request['norec_apd'] . "'"));

        $SistemHargaNetto = 'Harga Tertinggi';
        $strMetodeAmbilHargaNetto = 1;
        $strMetodeStokHargaNetto = 1;
        $idHibah = $this->settingFix('objectasalprodukHibah');
        $isHibah = '';
        // if (isset($request['isdonasi'])) {
        //     $isHibah = 'and objectasalprodukfk = ' . $idHibah;
        // } else {
        //     $isHibah = 'and objectasalprodukfk <> ' . $idHibah;
        //      $isHibah = 'and objectasalprodukfk = ' . $idHibah;
        // }
        $idProfile = $this->kdProfile;
        $kdJenisTransOA = $this->settingFix('jenisTransaksiOA');
        $jenisTransaksi = DB::table('jenistransaksi_m as jt')
            ->where('jt.id', $kdJenisTransOA)
            ->where('jt.statusenabled', true)
            ->first();

        if (empty($jenisTransaksi)) {
            return $this->respond(null, 500, 'Setting jenistransaksi_m dulu');
        }
        $strMetodeAmbilHargaNetto = $jenisTransaksi->metodeambilharganetto;
        //        $strMetodeHargaNetto = $jenisTransaksi->metodeharganetto; //ketika penerimaan saja
        $strMetodeStokHargaNetto = $jenisTransaksi->metodestokharganetto;
        $strSistemHargaNetto = $jenisTransaksi->sistemharganetto;

        if (empty($strMetodeAmbilHargaNetto) || empty($strMetodeAmbilHargaNetto) || empty($strMetodeAmbilHargaNetto)) {
            return $this->respond(null, 500, 'Setting Data Fixed Belum ada');
        }
        $defaultKP = 0;
        $defaultKP = 0;
        if (isset($request['kpid']) && $request['kpid'] != "" && $request['kpid'] != "undefined") {
            $defaultKP = $request['kpid'];
        }

        $persenHargaJualProduk = DB::table('persenhargajualproduk_m as phjp')
            ->JOIN('range_m as rg', 'rg.id', '=', 'phjp.objectrangefk')
            ->select('rg.rangemin', 'rg.rangemax', 'phjp.persenuphargasatuan', 'phjp.objectrangefk', 'phjp.objectkelompokpasienfk')
            ->where('phjp.objectjenistransaksifk', $this->settingFix('jenisTransaksiOA'))
            ->where('phjp.statusenabled', true)
            ->where('phjp.objectkelompokpasienfk', $defaultKP)
            ->get();

        if (count($persenHargaJualProduk) == 0) {
            return $this->respond(array(
                'Error' => 'Setting persenhargajualproduk_m dulu',
                'message' => 'as@epic',
            ));
        }

        $strSistemHargaNetto = 3;
        if ($strSistemHargaNetto == 3) {
            $SistemHargaNetto = 'Harga Tertinggi';
            if ($strMetodeAmbilHargaNetto == 1) { //HN1
                $strHN = 'spd.harganetto1';
                $MetodeAmbilHargaNetto = 'HN1';
            }
            if ($strMetodeAmbilHargaNetto == 2) { //HN2
                $strHN = 'spd.harganetto2';
                $MetodeAmbilHargaNetto = 'HN2';
            }

            if ($strMetodeStokHargaNetto == 1) { //FIFO
                $strMSHT = 'sk.tglstruk';
                $MetodeStokHargaNetto = 'FIFO';
            }
            if ($strMetodeStokHargaNetto == 2) { //LIFO
                $strMSHT = '';
                $MetodeStokHargaNetto = 'LIFO';
            }
            if ($strMetodeStokHargaNetto == 3) { //FEFO
                $strMSHT = 'spd.tglkadaluarsa';
                $MetodeStokHargaNetto = 'FEFO';
            }
            if ($strMetodeStokHargaNetto == 4) { //LEFO
                $strMSHT = '';
                $MetodeStokHargaNetto = 'LEFO';
            }
            if ($strMetodeStokHargaNetto == 5) { //Summary
                $strMSHT = '';
                $MetodeStokHargaNetto = 'Summary';
            }

            $filterSerch = "";
            if (isset($request['namaproduk']) && !empty($request['namaproduk'])) {
                $filterSerch = " and (pr.namaproduk ILIKE '%" . $request['namaproduk'] . "%'";

                if (isset($request['namaproduk']) && !empty($request['namaproduk'])) {
                    $filterSerch .= " OR pr.kdproduk ILIKE '%" . $request['namaproduk'] . "%'";
                }

                if (isset($request['namaproduk']) && !empty($request['namaproduk'])) {
                    $filterSerch .= " OR pr.id = " . (int)$request['namaproduk'];
                }

                $filterSerch .= ")";
            }

            $filterKdPrd = "";
            if (isset($request['kodeproduk']) && $request['kodeproduk'] != "") {
                $filterKdPrd = " and cast(pr.id as varchar) ilike '%" . $request['kodeproduk'] . "%'";
            }

            $result = DB::select(
                DB::raw("select 
                    sk.norec, spd.objectprodukfk,
                    $strMSHT as tgl, spd.objectasalprodukfk,
                    $strHN as hargajual, spd.hargadiscount,
                    (spd.qtyproduk) as qtyproduk, 
                    spd.objectruanganfk, 
                    ap.asalproduk, 
                    spd.nostrukterimafk,
                    pr.isfornas, 
                    pjod.qtymax,
                    pr.objectdetailjenisprodukfk,
                    spd.tglkadaluarsa, 
                    spd.norec as norec_spd,
                    pr.kekuatan,
                        CASE 
                            WHEN spd.objectasalprodukfk = $idHibah THEN 0 
                            ELSE $strHN 
                        END AS harganetto,
                    pr.namaproduk, 
                    pr.objectgenerikfk,
                    ss.satuanstandar, 
                    kv.nilaikonversi, 
                    ss.id as satuanstandarfk, 
                    pr.kdproduk,
                    (select sum(qtyproduk) 
                     from stokprodukdetail_t 
                     where kdprofile = $idProfile 
                     and objectprodukfk = spd.objectprodukfk 
                     and statusenabled = true) as totalstok,
                    rg.namaruangan as namaruangantujuan 
                from ppra_jenisoperasidetail as pjod
                --inner JOIN ppra_generikdetail as pgd on pgd.objectgenerikfk = pjod.objectgenerikfk
                --inner join produk_m as pr on pr.id = pgd.objectprodukfk
                inner join produk_m as pr on pr.objectgenerikfk = pjod.objectgenerikfk
                inner JOIN stokprodukdetail_t as spd on spd.objectprodukfk = pr.id
                inner JOIN strukpelayanan_t as sk on sk.norec = spd.nostrukterimafk
                inner JOIN ruangan_m as rg on rg.id = spd.objectruanganfk
                inner JOIN asalproduk_m as ap on ap.id = spd.objectasalprodukfk
                left join konversisatuan_t as kv on kv.objekprodukfk = pr.id
                left join satuanstandar_m as std on std.id = kv.satuanstandar_asal
                left JOIN satuanstandar_m as ss on ss.id = pr.objectsatuanstandarfk
                where spd.kdprofile = $idProfile 
                  $isHibah 
                  and spd.statusenabled = true 
                  and pr.statusenabled = true 
                  and pr.objectsubkategoryfk = 8
                  --and pr.objectjenisgenerikfk = 27
                  and spd.objectruanganfk = :ruanganid 
                  and pjod.objectjenisoperasifk = :idjenisoperasi
                  $filterSerch $filterKdPrd
                group by sk.norec, spd.objectprodukfk, $strMSHT, spd.objectasalprodukfk,
                    spd.hargadiscount, pr.kdproduk, pr.kekuatan,
                    spd.objectruanganfk, ap.asalproduk, spd.nostrukterimafk,pr.objectgenerikfk, spd.norec, pr.namaproduk, 
                    ss.satuanstandar, pjod.qtymax, kv.nilaikonversi, ss.id, pr.isfornas, 
                    pr.objectdetailjenisprodukfk, rg.namaruangan
                order By pr.namaproduk, $strMSHT limit 30
                "),
                array(
                    'ruanganid' => $request['ruanganfk'],
                    'idjenisoperasi' => $request->input('idjenisoperasi'), // Parameter untuk jenis operasi
                )
            );


            // return $result;
            foreach ($result as $item) {
                // Modifikasi query untuk mengambil harga tertinggi dalam 1 tahun terakhir
                $ruanganpengiriselect = "";
                $stokPengirim = '';
                $stokTujuanPengirim = '';
                if (isset($request['ruanganPemesanfk'])) {
                    $ruanganPengirim = $request['ruanganPemesanfk'];
                    $ruanganTujuanPengirim = $request['ruanganfk'];
                    $barangPengirim = $item->objectprodukfk;
                    $stokPengirim = DB::select(
                        DB::raw("select rg.namaruangan, pr.id, sum(spd.qtyproduk) from stokprodukdetail_t as spd
            join produk_m as pr on pr.id = spd.objectprodukfk
            join ruangan_m as rg on rg.id = spd.objectruanganfk where spd.objectruanganfk = $ruanganPengirim and spd.objectprodukfk = $barangPengirim and spd.statusenabled = true group by pr.id, rg.namaruangan")
                    );

                    $stokTujuanPengirim = DB::select(
                        DB::raw("select rg.namaruangan, pr.id, sum(spd.qtyproduk) from stokprodukdetail_t as spd
            join produk_m as pr on pr.id = spd.objectprodukfk
            join ruangan_m as rg on rg.id = spd.objectruanganfk where spd.objectruanganfk = $ruanganTujuanPengirim and spd.objectprodukfk = $barangPengirim and spd.statusenabled = true group by pr.id, rg.namaruangan")
                    );
                    // return $stokPengirim[0]->sum;
                }
                $maxHarga = DB::select(
                    DB::raw("select MAX($strHN) as harga
                from stokprodukdetail_t as spd
                inner JOIN strukpelayanan_t as sk on sk.norec=spd.nostrukterimafk
                where spd.kdprofile = $idProfile $isHibah 
                and spd.objectprodukfk =:produkId 
                and spd.statusenabled = true 
                and spd.objectruanganfk =:ruanganid
                    and spd.created_at BETWEEN (CURRENT_DATE - INTERVAL '1 year') AND (CURRENT_DATE + INTERVAL '1 day' - INTERVAL '1 second')"), // Filter satu tahun terakhir
                    array(
                        'produkId' => $item->objectprodukfk,
                        'ruanganid' => $request['ruanganfk'],
                    )
                );

                $hargaTertinggi = 0;
                foreach ($maxHarga as $item1) {
                    if ($hargaTertinggi < (float)$item1->harga) {
                        $hargaTertinggi = (float)$item1->harga;
                    }
                }

                $persenUpHargaSatuan = 0;
                $pesenUpDepos = explode(',', $this->settingFix('marginUpDepo'));
                $pesenUpDepo = [];
                foreach ($pesenUpDepos as $item2) {
                    // Pisahkan string menjadi id dan value menggunakan explode
                    list($id, $value) = explode('|', $item2);
                    if ($request['ruanganfk'] == $id) {
                        $pesenUpDepo = ['id' => $id, 'value' => (float)$value];
                    }
                }
                if (isset($pesenUpDepo['id'])) {
                    $persenUpHargaSatuan = (float)$pesenUpDepo['value'];
                } else {
                    foreach ($persenHargaJualProduk as $hitem) {
                        // if ((float)$hitem->rangemin < (float)$item->harganetto && (float)$hitem->rangemax > (float)$item->harganetto) {
                        $kebangsaan = 1;
                        $persenUpHargaSatuan = (float)$hitem->persenuphargasatuan;
                        if (!empty($pasien)) {
                            if ($pasien[0]->objectkebangsaanfk != 1) {
                                $kebangsaan = 1.5;
                            } else {
                                $kebangsaan = 1;
                            }
                        } else {
                            $kebangsaan = 1;
                        }

                        //var_dump($pasien);
                        // }
                    }
                }

                $results[] = array(
                    'norec' => $item->norec,
                    'kdproduk' => $item->kdproduk,
                    'id' =>  $item->objectprodukfk,
                    'namaprodukuse' => "Produk :" . "[" . $item->kdproduk . "]" . "-" . $item->namaproduk,
                    'namaproduk' => $item->namaproduk,
                    'fornas' => $item->isfornas,
                    'kekuatan' => $item->kekuatan,
                    'objectdetailjenisprodukfk' => $item->objectdetailjenisprodukfk,
                    'tgl' => $item->tgl,
                    'objectasalprodukfk' => $item->objectasalprodukfk,
                    'asalproduk' => $item->asalproduk,
                    'harganetto' => $item->harganetto,
                    'objectgenerikfk' => $item->objectgenerikfk,
                    'totalstok' => $item->totalstok,
                    'qtymax' => $item->qtymax,
                    'hargadiscount' => $item->hargadiscount,
                    'hargajual' => (float)($item->harganetto + (((float)$item->harganetto * (float)$persenUpHargaSatuan) / 100)) * $kebangsaan,
                    'persenhargajualproduk' => $persenUpHargaSatuan,
                    'qtyproduk' => (float)$item->qtyproduk,
                    'qtyprodukpengirim' => isset($stokPengirim) && is_array($stokPengirim) && count($stokPengirim) != 0 ? (float)$stokPengirim[0]->sum : 0,
                    'qtyproduktujuanpengirim' => isset($stokTujuanPengirim) && is_array($stokTujuanPengirim) && count($stokTujuanPengirim) != 0 ? (float)$stokTujuanPengirim[0]->sum : 0,
                    'objectruanganfk' => $item->objectruanganfk,
                    'namaruanganpengirim' => isset($stokPengirim) && is_array($stokPengirim) && count($stokPengirim) != 0 ? $stokPengirim[0]->namaruangan : 0,
                    'namaruangantujuan' => $item->namaruangantujuan,
                    'nostrukterimafk' => $item->nostrukterimafk,
                    'tglkadaluarsa' => $item->tglkadaluarsa,
                    'persenup' => $persenUpHargaSatuan,
                    'norec_spd' => $item->norec_spd,
                    'satuanstandar' => $item->satuanstandar,
                    'nilaikonversi' => $item->nilaikonversi,
                    'satuanstandarfk' => $item->satuanstandarfk,
                );
            }
        }


        return $this->respond($results);
    }
    public function getProdukDetailAntibiotik(Request $request, $lokal = false)
    {
        ini_set('max_execution_time', 1000);
        // $dataKonversiProduk = DB::table('konversisatuan_t as ks')
        // ->JOIN('satuanstandar_m as ss', 'ss.id', '=', 'ks.satuanstandar_asal')
        // ->JOIN('satuanstandar_m as ss2', 'ss2.id', '=', 'ks.satuanstandar_tujuan')
        // ->select(
        //     'ks.objekprodukfk',
        //     'ks.satuanstandar_asal',
        //     'ss.satuanstandar',
        //     'ks.satuanstandar_tujuan',
        //     'ss2.satuanstandar as satuanstandar2',
        //     'ks.nilaikonversi'
        // )
        // ->where('ks.kdprofile', $idProfile)
        // ->where('ks.statusenabled', true)
        // ->get();

        $results = [];

        $pasien = DB::select(DB::raw("select ps.objectkebangsaanfk
            from antrianpasiendiperiksa_t as apd
            join pasiendaftar_t as pd  on pd.norec=apd.noregistrasifk and apd.kdprofile =pd.kdprofile
            join pasien_m as ps  on ps.id=pd.nocmfk and pd.kdprofile =ps.kdprofile
            where apd.norec = '" . $request['norec_apd'] . "'"));

        $SistemHargaNetto = 'Harga Tertinggi';
        $strMetodeAmbilHargaNetto = 1;
        $strMetodeStokHargaNetto = 1;
        $idHibah = $this->settingFix('objectasalprodukHibah');
        $isHibah = '';
        // if (isset($request['isdonasi'])) {
        //     $isHibah = 'and objectasalprodukfk = ' . $idHibah;
        // } else {
        //     $isHibah = 'and objectasalprodukfk <> ' . $idHibah;
        //      $isHibah = 'and objectasalprodukfk = ' . $idHibah;
        // }
        $idProfile = $this->kdProfile;
        $kdJenisTransOA = $this->settingFix('jenisTransaksiOA');
        $jenisTransaksi = DB::table('jenistransaksi_m as jt')
            ->where('jt.id', $kdJenisTransOA)
            ->where('jt.statusenabled', true)
            ->first();

        if (empty($jenisTransaksi)) {
            return $this->respond(null, 500, 'Setting jenistransaksi_m dulu');
        }
        $strMetodeAmbilHargaNetto = $jenisTransaksi->metodeambilharganetto;
        //        $strMetodeHargaNetto = $jenisTransaksi->metodeharganetto; //ketika penerimaan saja
        $strMetodeStokHargaNetto = $jenisTransaksi->metodestokharganetto;
        $strSistemHargaNetto = $jenisTransaksi->sistemharganetto;

        if (empty($strMetodeAmbilHargaNetto) || empty($strMetodeAmbilHargaNetto) || empty($strMetodeAmbilHargaNetto)) {
            return $this->respond(null, 500, 'Setting Data Fixed Belum ada');
        }
        $defaultKP = 0;
        $defaultKP = 0;
        if (isset($request['kpid']) && $request['kpid'] != "" && $request['kpid'] != "undefined") {
            $defaultKP = $request['kpid'];
        }

        $persenHargaJualProduk = DB::table('persenhargajualproduk_m as phjp')
            ->JOIN('range_m as rg', 'rg.id', '=', 'phjp.objectrangefk')
            ->select('rg.rangemin', 'rg.rangemax', 'phjp.persenuphargasatuan', 'phjp.objectrangefk', 'phjp.objectkelompokpasienfk')
            ->where('phjp.objectjenistransaksifk', $this->settingFix('jenisTransaksiOA'))
            ->where('phjp.statusenabled', true)
            ->where('phjp.objectkelompokpasienfk', $defaultKP)
            ->get();

        if (count($persenHargaJualProduk) == 0) {
            return $this->respond(array(
                'Error' => 'Setting persenhargajualproduk_m dulu',
                'message' => 'as@epic',
            ));
        }

        $strSistemHargaNetto = 3;
        if ($strSistemHargaNetto == 3) {
            $SistemHargaNetto = 'Harga Tertinggi';
            if ($strMetodeAmbilHargaNetto == 1) { //HN1
                $strHN = 'spd.harganetto1';
                $MetodeAmbilHargaNetto = 'HN1';
            }
            if ($strMetodeAmbilHargaNetto == 2) { //HN2
                $strHN = 'spd.harganetto2';
                $MetodeAmbilHargaNetto = 'HN2';
            }

            if ($strMetodeStokHargaNetto == 1) { //FIFO
                $strMSHT = 'sk.tglstruk';
                $MetodeStokHargaNetto = 'FIFO';
            }
            if ($strMetodeStokHargaNetto == 2) { //LIFO
                $strMSHT = '';
                $MetodeStokHargaNetto = 'LIFO';
            }
            if ($strMetodeStokHargaNetto == 3) { //FEFO
                $strMSHT = 'spd.tglkadaluarsa';
                $MetodeStokHargaNetto = 'FEFO';
            }
            if ($strMetodeStokHargaNetto == 4) { //LEFO
                $strMSHT = '';
                $MetodeStokHargaNetto = 'LEFO';
            }
            if ($strMetodeStokHargaNetto == 5) { //Summary
                $strMSHT = '';
                $MetodeStokHargaNetto = 'Summary';
            }

            $filterSerch = "";
            if (isset($request['namaproduk']) && !empty($request['namaproduk'])) {
                $filterSerch = " and (pr.namaproduk ILIKE '%" . $request['namaproduk'] . "%'";

                // Tambahkan kondisi untuk kdproduk jika ada
                if (isset($request['namaproduk']) && !empty($request['namaproduk'])) {
                    $filterSerch .= " OR pr.kdproduk ILIKE '%" . $request['namaproduk'] . "%'";
                }

                // Tambahkan kondisi untuk id jika ada
                if (isset($request['namaproduk']) && !empty($request['namaproduk'])) {
                    $filterSerch .= " OR pr.id = " . (int)$request['namaproduk'];
                }

                $filterSerch .= ")";
            }

            $filterKdPrd = "";
            if (isset($request['kodeproduk']) && $request['kodeproduk'] != "") {
                $filterKdPrd = " and cast(pr.id as varchar) ilike '%" . $request['kodeproduk'] . "%'";
            }

            $result = DB::select(
                DB::raw("select 
                    sk.norec, spd.objectprodukfk,
                    $strMSHT as tgl, spd.objectasalprodukfk,
                    $strHN as hargajual, spd.hargadiscount,
                    (spd.qtyproduk) as qtyproduk, 
                    spd.objectruanganfk, 
                    ap.asalproduk, 
                    spd.nostrukterimafk,
                    pr.isfornas, 
                    ptd.qtymax,
                    pr.objectdetailjenisprodukfk,
                    spd.tglkadaluarsa, 
                    spd.norec as norec_spd,
                    pr.kekuatan,
                        CASE 
                            WHEN spd.objectasalprodukfk = $idHibah THEN 0 
                            ELSE $strHN 
                        END AS harganetto,
                    pr.namaproduk, 
                    pr.objectgenerikfk,
                    ss.satuanstandar, 
                    kv.nilaikonversi, 
                    ss.id as satuanstandarfk, 
                    pr.kdproduk,
                    (select sum(qtyproduk) 
                     from stokprodukdetail_t 
                     where kdprofile = $idProfile 
                     and objectprodukfk = spd.objectprodukfk 
                     and statusenabled = true) as totalstok,
                    rg.namaruangan as namaruangantujuan 
                from ppra_tindakandetail as ptd
                --inner JOIN ppra_generikdetail as pgd on pgd.objectgenerikfk = ptd.objectgenerikfk
                --inner join produk_m as pr on pr.id = pgd.objectprodukfk
                inner join produk_m as pr on pr.objectgenerikfk = ptd.objectgenerikfk
                inner JOIN stokprodukdetail_t as spd on spd.objectprodukfk = pr.id
                inner JOIN strukpelayanan_t as sk on sk.norec = spd.nostrukterimafk
                inner JOIN ruangan_m as rg on rg.id = spd.objectruanganfk
                inner JOIN asalproduk_m as ap on ap.id = spd.objectasalprodukfk
                left join konversisatuan_t as kv on kv.objekprodukfk = pr.id
                left join satuanstandar_m as std on std.id = kv.satuanstandar_asal
                left JOIN satuanstandar_m as ss on ss.id = pr.objectsatuanstandarfk
                where spd.kdprofile = $idProfile 
                  $isHibah 
                  and spd.statusenabled = true 
                  and pr.statusenabled = true 
                  --and pr.objectjenisgenerikfk = 27
                  and pr.objectsubkategoryfk = 8
                  and spd.objectruanganfk = :ruanganid 
                  and ptd.objecttindakanfk = :idtindakan
                  $filterSerch $filterKdPrd
                group by sk.norec, spd.objectprodukfk, $strMSHT, spd.objectasalprodukfk,
                    spd.hargadiscount, pr.kdproduk, pr.kekuatan,ptd.qtymax, 
                    spd.objectruanganfk, ap.asalproduk, spd.nostrukterimafk,pr.objectgenerikfk, spd.norec, pr.namaproduk, 
                    ss.satuanstandar, kv.nilaikonversi, ss.id, pr.isfornas, 
                    pr.objectdetailjenisprodukfk, rg.namaruangan
                order By pr.namaproduk, $strMSHT limit 30
                "),
                array(
                    'ruanganid' => $request['ruanganfk'],
                    'idtindakan' => $request->input('idtindakan'),
                )
            );


            // return $result;
            foreach ($result as $item) {
                // Modifikasi query untuk mengambil harga tertinggi dalam 1 tahun terakhir
                $ruanganpengiriselect = "";
                $stokPengirim = '';
                $stokTujuanPengirim = '';
                if (isset($request['ruanganPemesanfk'])) {
                    $ruanganPengirim = $request['ruanganPemesanfk'];
                    $ruanganTujuanPengirim = $request['ruanganfk'];
                    $barangPengirim = $item->objectprodukfk;
                    $stokPengirim = DB::select(
                        DB::raw("select rg.namaruangan, pr.id, sum(spd.qtyproduk) from stokprodukdetail_t as spd
            join produk_m as pr on pr.id = spd.objectprodukfk
            join ruangan_m as rg on rg.id = spd.objectruanganfk where spd.objectruanganfk = $ruanganPengirim and spd.objectprodukfk = $barangPengirim and spd.statusenabled = true group by pr.id, rg.namaruangan")
                    );

                    $stokTujuanPengirim = DB::select(
                        DB::raw("select rg.namaruangan, pr.id, sum(spd.qtyproduk) from stokprodukdetail_t as spd
            join produk_m as pr on pr.id = spd.objectprodukfk
            join ruangan_m as rg on rg.id = spd.objectruanganfk where spd.objectruanganfk = $ruanganTujuanPengirim and spd.objectprodukfk = $barangPengirim and spd.statusenabled = true group by pr.id, rg.namaruangan")
                    );
                    // return $stokPengirim[0]->sum;
                }
                $maxHarga = DB::select(
                    DB::raw("select MAX($strHN) as harga
                from stokprodukdetail_t as spd
                inner JOIN strukpelayanan_t as sk on sk.norec=spd.nostrukterimafk
                where spd.kdprofile = $idProfile $isHibah 
                and spd.objectprodukfk =:produkId 
                and spd.statusenabled = true 
                and spd.objectruanganfk =:ruanganid
                    and spd.created_at BETWEEN (CURRENT_DATE - INTERVAL '1 year') AND (CURRENT_DATE + INTERVAL '1 day' - INTERVAL '1 second')"), // Filter satu tahun terakhir
                    array(
                        'produkId' => $item->objectprodukfk,
                        'ruanganid' => $request['ruanganfk'],
                    )
                );

                $hargaTertinggi = 0;
                foreach ($maxHarga as $item1) {
                    if ($hargaTertinggi < (float)$item1->harga) {
                        $hargaTertinggi = (float)$item1->harga;
                    }
                }

                $persenUpHargaSatuan = 0;
                $pesenUpDepos = explode(',', $this->settingFix('marginUpDepo'));
                $pesenUpDepo = [];
                foreach ($pesenUpDepos as $item2) {
                    // Pisahkan string menjadi id dan value menggunakan explode
                    list($id, $value) = explode('|', $item2);
                    if ($request['ruanganfk'] == $id) {
                        $pesenUpDepo = ['id' => $id, 'value' => (float)$value];
                    }
                }
                if (isset($pesenUpDepo['id'])) {
                    $persenUpHargaSatuan = (float)$pesenUpDepo['value'];
                } else {
                    foreach ($persenHargaJualProduk as $hitem) {
                        // if ((float)$hitem->rangemin < (float)$item->harganetto && (float)$hitem->rangemax > (float)$item->harganetto) {
                        $kebangsaan = 1;
                        $persenUpHargaSatuan = (float)$hitem->persenuphargasatuan;
                        if (!empty($pasien)) {
                            if ($pasien[0]->objectkebangsaanfk != 1) {
                                $kebangsaan = 1.5;
                            } else {
                                $kebangsaan = 1;
                            }
                        } else {
                            $kebangsaan = 1;
                        }

                        //var_dump($pasien);
                        // }
                    }
                }

                $results[] = array(
                    'norec' => $item->norec,
                    'kdproduk' => $item->kdproduk,
                    'id' =>  $item->objectprodukfk,
                    'namaprodukuse' => "Produk :" . "[" . $item->kdproduk . "]" . "-" . $item->namaproduk,
                    'namaproduk' => $item->namaproduk,
                    'fornas' => $item->isfornas,
                    'qtymax' => $item->qtymax,
                    'kekuatan' => $item->kekuatan,
                    'objectdetailjenisprodukfk' => $item->objectdetailjenisprodukfk,
                    'tgl' => $item->tgl,
                    'objectasalprodukfk' => $item->objectasalprodukfk,
                    'asalproduk' => $item->asalproduk,
                    'harganetto' => $item->harganetto,
                    'objectgenerikfk' => $item->objectgenerikfk,
                    'totalstok' => $item->totalstok,
                    'hargadiscount' => $item->hargadiscount,
                    'hargajual' => (float)($item->harganetto + (((float)$item->harganetto * (float)$persenUpHargaSatuan) / 100)) * $kebangsaan,
                    'persenhargajualproduk' => $persenUpHargaSatuan,
                    'qtyproduk' => (float)$item->qtyproduk,
                    'qtymax' => $item->qtymax,
                    'qtyprodukpengirim' => isset($stokPengirim) && is_array($stokPengirim) && count($stokPengirim) != 0 ? (float)$stokPengirim[0]->sum : 0,
                    'qtyproduktujuanpengirim' => isset($stokTujuanPengirim) && is_array($stokTujuanPengirim) && count($stokTujuanPengirim) != 0 ? (float)$stokTujuanPengirim[0]->sum : 0,
                    'objectruanganfk' => $item->objectruanganfk,
                    'namaruanganpengirim' => isset($stokPengirim) && is_array($stokPengirim) && count($stokPengirim) != 0 ? $stokPengirim[0]->namaruangan : 0,
                    'namaruangantujuan' => $item->namaruangantujuan,
                    'nostrukterimafk' => $item->nostrukterimafk,
                    'tglkadaluarsa' => $item->tglkadaluarsa,
                    'persenup' => $persenUpHargaSatuan,
                    'norec_spd' => $item->norec_spd,
                    'satuanstandar' => $item->satuanstandar,
                    'nilaikonversi' => $item->nilaikonversi,
                    'satuanstandarfk' => $item->satuanstandarfk,
                );
            }
        }


        return $this->respond($results);
    }

    public function simpanResep(Request $request)
    {
        DB::beginTransaction();
        try {
            //region @SIMPAN PELAYANAN OBAT IEU
            $idProfile = (int) $this->kdProfile;
            $racikanORnonracikan = 'A';
            $SET['depoRajal'] = explode(',', $this->settingFix('kdRuanganDepoRajal'));
            $SET['statusVerif'] = $this->settingFix('statusVerifApotik');
            $SET['statusSelesai'] = $this->settingFix('statusSelesaiApotik');
            $SET['kelTrans'] = $this->settingFix('kelompokTransaksiPelayanan');
            $SET['komponenHargaNetto'] = $this->settingFix('komponenHargaNetto');
            $SET['komponenHargaProfit'] = $this->settingFix('komponenHargaProfit');
            $SET['jenisPetugasDokterPJ'] = $this->settingFix('jenisPetugasDokterPJ');


            $tglTrans =  date('Y-m-d H:i:s');
            $r_SR = $request['strukresep'];
            if ($r_SR['noorder'] != '' && $r_SR['noorder'] != 'EditResep') {
                $dataOrder = StrukOrder::where('norec', $r_SR['noorder'])->where('kdprofile', $idProfile)->first();
                $dataOrder->statusorder =  $SET['statusSelesai'];
                $dataOrder->save();
            }
            $namaPasien = $r_SR['nocm'] . ' ' . $r_SR['namapasien'];
            $log = "";
            if ($r_SR['noresep'] == '-') {
                $log = "Pelayanan Obat Alkes No";
                $newSR = new StrukResep();
                if (isset($request['strukresep']['isobatalkes']) && $request['strukresep']['isobatalkes'] == 'floor-stock') {
                    $noResep = $this->SEQUENCE(new StrukResep(), 'noresep', 12, 'FS/' . $this->getDateTime()->format('ym') . '/', $idProfile);
                } else if (isset($request['strukresep']['isobatalkes']) && $request['strukresep']['isobatalkes'] == true) {
                    $noResep = $this->SEQUENCE(new StrukResep(), 'noresep', 12, 'OA/' . $this->getDateTime()->format('ym') . '/', $idProfile);
                } else {
                    $noResep = $this->SEQUENCE(new StrukResep, 'noresep', 12, 'O/' . $this->getDateTime()->format('ym') . '/', $idProfile);
                }
                if ($noResep == '') {
                    $transMessage = "Gagal mengumpukan data, Coba lagi.!";
                    DB::rollBack();
                    $result = array(
                        "status" => 400,
                        "result" => null
                    );
                    return $this->respond($result['result'], $result['status'], $transMessage);
                }
                $norecSR = $newSR->generateNewId();
                /** Obat ALKES */
                $newSR->norec = $norecSR;
            } else {
                $log = "Edit Pelayanan Obat Alkes No";
                $newSR = StrukResep::where('norec', $r_SR['norecResep'])->where('kdprofile', $idProfile)->first();
                $noResep = $newSR->noresep;
                $resepOld = $newSR;
            }
            $newSR->kdprofile = $idProfile;
            $newSR->statusenabled = 1;
            $newSR->noresep = $noResep;
            $newSR->pasienfk = $r_SR['pasienfk'];
            $newSR->penulisresepfk = $r_SR['penulisresepfk'];
            $newSR->ruanganfk = $r_SR['ruanganfk'];
            $newSR->status = $SET['statusVerif'];
            $newSR->tglresep =  $r_SR['tglresep'];
            $newSR->noregistrasi =  $r_SR['noregistrasi'];
            $newSR->alergiobat = isset($r_SR['alergiobat']) ? $r_SR['alergiobat'] : null;
            $newSR->petugas =  $this->getNamaPegawai();
            $newSR->isreseppulang = isset($r_SR['isreseppulang']) ? $r_SR['isreseppulang'] : null;
            $newSR->tepatpasien = $r_SR['tepatpasien'];
            $newSR->tepatobat = $r_SR['tepatobat'];
            $newSR->campuranobat = $r_SR['campuranobat'];
            $newSR->tepatdosis = $r_SR['tepatdosis'];
            $newSR->tepatrute = $r_SR['tepatrute'];
            $newSR->duplikasiobat = $r_SR['duplikasiobat'];
            $newSR->interaksiobat = $r_SR['interaksiobat'];
            $newSR->jenisobatl5 = $r_SR['jenisobatl5'];
            $newSR->kontraindikasi = $r_SR['kontraindikasi'];
            if (isset($r_SR['cito'])) {
                $newSR->cito =  $r_SR['cito'];
            }
            if (isset($r_SR['isbpl'])) {
                $newSR->isbpl =  $r_SR['isbpl'];
            }
            if (isset($r_SR['isrutin'])) {
                $newSR->isrutin =  $r_SR['isrutin'];
            }
            if (isset($r_SR['issementara'])) {
                $newSR->issementara =  $r_SR['issementara'];
            }
            $newSR->save();
            $norec_SR = $newSR->norec;
            $dokterPenulis =  $newSR->penulisresepfk;

            if ($r_SR['noorder'] != '' && $r_SR['noorder'] != 'EditResep') {
                $DataOrder = StrukOrder::where('norec', $r_SR['noorder'])->where('kdprofile', $idProfile)->first();
                $norecOrder = $DataOrder->norec;
                StrukResep::where('norec', $norec_SR)->update(['orderfk' => $norecOrder]);
            }

            // $TambahStok = 0;
            if ($r_SR['noresep'] != '-') {

                KartuStok::where('keterangan',  'Pelayanan Obat Alkes No. '  . $noResep . ' ' . $namaPasien)
                    ->where('kdprofile', $idProfile)
                    ->update(['flagfk' => null]);

                $tglUbah = date('Y-m-d H:i:s', strtotime('-5 seconds', strtotime($tglTrans)));

                //##PENAMBAHAN KEMBALI STOKPRODUKDETAIL
                $dataKembaliStok = collect(DB::select("
                            select pp.norec,pp.stokprodukdetailfk,pr.namaproduk,pp.strukterimafk as nostrukterimafk,pp.jumlah,pp.nilaikonversi,sr.ruanganfk,pp.produkfk
                            from pelayananpasien_t as pp
                            INNER JOIN produk_m as pr on pr.id = pp.produkfk
                            INNER JOIN strukresep_t sr on sr.norec=pp.strukresepfk
                            where pp.kdprofile = $idProfile
                            and sr.kdprofile = $idProfile
                            and sr.norec='$norec_SR'
                    "));


                if ($r_SR['ruanganfk'] == $resepOld->ruanganfk) {

                    foreach ($dataKembaliStok as $item5) {
                        $saldoAwal = 0;
                        $saldoAkhir = 0;
                        $TambahStok = (float)$item5->jumlah;
                        $dataSaldoAwal = collect(DB::select("
                                select sum(qtyproduk) as qty from stokprodukdetail_t
                                where kdprofile = $idProfile and objectruanganfk='$resepOld->ruanganfk' and statusenabled = true and objectprodukfk='$item5->produkfk'"))
                            ->first();
                        $saldoAwal = (float)$dataSaldoAwal->qty;
                        $saldoAkhir = (float)$dataSaldoAwal->qty + $TambahStok;

                        DB::table('stokprodukdetail_t')
                            ->where('kdprofile', $idProfile)
                            ->where('statusenabled', true)
                            ->where('norec', $item5->stokprodukdetailfk)
                            ->lockForUpdate()
                            ->increment('qtyproduk', (float)$TambahStok);

                        $this->kartu_STOK(array(
                            "saldoawal" => $saldoAwal,
                            "qtyin" => (float)$TambahStok,
                            "qtyout" => 0,
                            "saldoakhir" => $saldoAkhir,
                            "keterangan" => 'Ubah Pelayanan Obat Alkes No. '  . $noResep . '. pada produk ' .  $item5->namaproduk . '. Atas pasien ' . $namaPasien,
                            "produkfk" => $item5->produkfk,
                            "ruanganfk" => $r_SR['ruanganfk'],
                            "tglinput" => $tglUbah,
                            "tglkejadian" => $tglUbah,
                            "nostrukterimafk" => $item5->nostrukterimafk,
                            "norectransaksi" => $item5->norec,
                            "tabletransaksi" => 'pelayananpasien_t',
                            "stokprodukdetailfk" => $item5->stokprodukdetailfk,
                            "flagfk" => null,
                        ));
                    }
                } else {
                    foreach ($dataKembaliStok as $item5) {
                        $TambahStok = (float)$item5->jumlah;
                        $saldoAwal = 0;
                        $saldoAkhir = 0;

                        $dataSaldoAwal = collect(DB::select("
                            select sum(qtyproduk) as qty from stokprodukdetail_t
                            where kdprofile = $idProfile and objectruanganfk='$resepOld->ruanganfk' and statusenabled = true and objectprodukfk='$item5->produkfk'"))
                            ->first();


                        $saldoAwal = (float)$dataSaldoAwal->qty;
                        $saldoAkhir = (float)$dataSaldoAwal->qty + $TambahStok;

                        DB::table('stokprodukdetail_t')
                            ->where('kdprofile', $idProfile)
                            ->where('statusenabled', true)
                            ->where('norec', $item5->stokprodukdetailfk)
                            ->lockForUpdate()
                            ->increment('qtyproduk', (float)$TambahStok);

                        $this->kartu_STOK(array(
                            "saldoawal" => $saldoAwal,
                            "qtyin" => (float)$TambahStok,
                            "qtyout" => 0,
                            "saldoakhir" => $saldoAkhir,
                            "keterangan" => 'Ubah Resep No. '  . $noResep . '. pada produk ' .  $item5->namaproduk . '. Atas pasien ' . $namaPasien,
                            "produkfk" => $item5->produkfk,
                            "ruanganfk" => $r_SR['ruanganfk'],
                            "tglinput" => $tglUbah,
                            "tglkejadian" => $tglUbah,
                            "nostrukterimafk" => $item5->nostrukterimafk,
                            "norectransaksi" => $item5->norec,
                            "tabletransaksi" => 'pelayananpasien_t',
                            "stokprodukdetailfk" => $item5->stokprodukdetailfk,
                            "flagfk" => null,
                        ));
                    }
                }

                //END##PENAMBAHAN KEMBALI STOKPRODUKDETAIL

                //### LOGACC untuk penjurnalan blm ada
                $HapusPP = PelayananPasien::where('strukresepfk', $norec_SR)->where('kdprofile', $idProfile)->get();
                foreach ($HapusPP as $pp) {
                    $HapusPPD = PelayananPasienDetail::where('pelayananpasien', $pp['norec'])->where('kdprofile', $idProfile)->delete();
                    $HapusPPP = PelayananPasienPetugas::where('pelayananpasien', $pp['norec'])->where('kdprofile', $idProfile)->delete();
                }
                $HpsPP = PelayananPasien::where('strukresepfk', $norec_SR)->where('kdprofile', $idProfile)->delete();
            }

            //## PelayananPasien
            $r_PP = $request['pelayananpasien'];

            foreach ($r_PP as $r_PPL) {

                $qtyJumlah = (float)$r_PPL['jumlah'] * (float)$r_PPL['nilaikonversi'];
                $newPP = new PelayananPasien();
                $norecPP = $newPP->generateNewId();
                $newPP->norec = $norecPP;
                $newPP->kdprofile = $idProfile;
                $newPP->statusenabled = true;
                $newPP->noregistrasifk = $r_PPL['noregistrasifk'];
                $newPP->tglregistrasi =  $r_SR['tglregistrasi'];
                $newPP->aturanpakai = $r_PPL['aturanpakai'];

                $newPP->generik = $r_PPL['generik'];
                $newPP->hargadiscount = $r_PPL['hargadiscount'];
                $newPP->persendiscount = isset($r_PPL['persendiscount']) ? $r_PPL['persendiscount'] : 0;
                $newPP->hargajual = $r_PPL['hargajual'];
                $newPP->hargasatuan = $r_PPL['hargasatuan'];
                $newPP->jenisracikanfk = $r_PPL['jenisobatfk'];
                $newPP->jenisobatfk = $r_PPL['jenisobatfk'];
                $newPP->jumlah = $qtyJumlah;
                $newPP->kelasfk = $r_PPL['kelasfk'];
                $newPP->kdkelompoktransaksi = $SET['kelTrans'];
                $newPP->produkfk = $r_PPL['produkfk'];
                if (isset($r_PPL['routefk'])) {
                    $newPP->routefk = $r_PPL['routefk'];
                }
                $newPP->stock = $r_PPL['stock'];
                $newPP->tglpelayanan = $r_SR['tglresep'];
                $newPP->harganetto = $r_PPL['harganetto'];
                $newPP->jeniskemasanfk = $r_PPL['jeniskemasanfk'];
                $newPP->rke = $r_PPL['rke'];
                $newPP->strukresepfk = $norec_SR;
                $newPP->satuanviewfk = $r_PPL['satuanviewfk'];
                $newPP->nilaikonversi = $r_PPL['nilaikonversi'];
                $newPP->strukterimafk = $r_PPL['nostrukterimafk'];
                $newPP->dosis = $r_PPL['dosis'];
                $newPP->jasa = $r_PPL['jasa'];
                $newPP->qtydetailresep = $r_PPL['jumlahobat'];
                $newPP->isobat = true;
                $newPP->ispagi = isset($r_PPL['ispagi']) ?  $r_PPL['ispagi'] : null;
                $newPP->issiang = isset($r_PPL['issiang']) ? $r_PPL['issiang'] : null;
                $newPP->ismalam = isset($r_PPL['ismalam']) ? $r_PPL['ismalam'] : null;
                $newPP->issore = isset($r_PPL['issore']) ? $r_PPL['issore'] : null;
                // $newPP->jumlahsementara = $r_PPL['jumlahsementara'] ? $r_PPL['jumlahsementara'] : null ;
                $newPP->keteranganpakai = $r_PPL['keterangan'];
                if (isset($r_PPL['iskronis'])) {
                    $newPP->iskronis = $r_PPL['iskronis'];
                }
                else
                {
                    $newPP->iskronis = null;
                }
                if (isset($r_PPL['isdonasi'])) {
                    $newPP->isdonasi = $r_PPL['isdonasi'];
                }
                if (isset($r_PPL['issementara'])) {
                    $newPP->issementara = $r_PPL['issementara'];
                    $newPP->jumlahsementara = $r_PPL['issementara'] ? $r_PPL['jumlahsementara'] : 0;
                }
                if (isset($r_PPL['satuanresepfk'])) {
                    $newPP->satuanresepfk = $r_PPL['satuanresepfk'];
                }
                if (isset($r_PPL['tglkadaluarsa']) && $r_PPL['tglkadaluarsa'] != 'Invalid date' && $r_PPL['tglkadaluarsa'] != '') {
                    $newPP->tglkadaluarsa = $r_PPL['tglkadaluarsa'];
                }
                if (isset($r_PPL['isbud'])) {
                    $newPP->isbud = $r_PPL['isbud'];
                    $newPP->tglpemakaian = $r_PPL['isbud'] ? $r_PPL['tglpemakaian'] : null;
                }
                $newPP->stokprodukdetailfk = $r_PPL['norec_spd'];
                $newPP->jenisracikanfk = $r_PPL['jenisobatfk'];
                $newPP->noregistrasi =  $r_SR['noregistrasi'];
                if (isset($r_PPL['jumlahxmakan'])) {
                    $newPP->qtyracikan = $r_PPL['jumlahxmakan'];
                }
                if ((int)$r_PPL['jeniskemasanfk'] == 1) {
                    $racikanORnonracikan = 'B';
                }
                $newPP->racikan = isset($r_PPL['racikan']) ? $r_PPL['racikan'] : null;
                $newPP->save();


                $dataPP[] = $newPP;
                $norec_PP = $newPP->norec;
                //### PelayananPasienDetail
                $dataKomponen = [];
                $dataKomponen[] = array(
                    'komponenfk' => $SET['komponenHargaNetto'],
                    'komponen' => 'Harga Netto',
                    'harga' => (float)$r_PPL['harganetto']
                );
                $dataKomponen[] = array(
                    'komponenfk' => $SET['komponenHargaProfit'],
                    'komponen' => 'Profit',
                    'harga' => (float)$r_PPL['hargasatuan'] - (float)$r_PPL['harganetto']
                );

                foreach ($dataKomponen as $itemKomponen) {
                    $newPPD = new PelayananPasienDetail();
                    $norecPPD = $newPPD->generateNewId();
                    $newPPD->norec = $norecPPD;
                    $newPPD->kdprofile = $idProfile;
                    $newPPD->statusenabled = true;
                    $newPPD->noregistrasifk = $r_PPL['noregistrasifk'];
                    $newPPD->tglregistrasi = $r_SR['tglregistrasi'];
                    $newPPD->aturanpakai = $r_PPL['aturanpakai'];
                    $newPPD->generik = $r_PPL['generik'];
                    $newPPD->hargadiscount = 0;
                    $newPPD->hargajual = $itemKomponen['harga'];
                    $newPPD->hargasatuan = $itemKomponen['harga'];
                    $newPPD->jenisobatfk = $r_PPL['jenisobatfk'];
                    $newPPD->jumlah = $qtyJumlah;
                    $newPPD->komponenhargafk = $itemKomponen['komponenfk'];
                    $newPPD->pelayananpasien = $norec_PP;
                    $newPPD->produkfk = $r_PPL['produkfk'];
                    $newPPD->routefk = $r_PPL['routefk'];
                    $newPPD->stock = 0;
                    $newPPD->tglpelayanan =  $r_SR['tglresep'];
                    $newPPD->harganetto = $itemKomponen['harga'];
                    $newPP->jasa = $r_PPL['jasa'];
                    $newPPD->noregistrasi =  $r_SR['noregistrasi'];
                    $newPPD->save();
                }
                //## StokProdukDetail

                $jmlPengurang = (float)$qtyJumlah;
                $dataSaldoAwal = collect(DB::select("
                     select sum(qtyproduk) as qty from stokprodukdetail_t
                     where kdprofile = $idProfile
                     and objectruanganfk='$r_PPL[ruanganfk]' and statusenabled = true
                     and objectprodukfk='$r_PPL[produkfk]'"))
                    ->first();
                // dd($dataSaldoAwal);
                $namaProduk = $r_PPL['namaproduk'];
                $saldoAwalIn = (float)$dataSaldoAwal->qty;
                $saldoAkhirIn = (float)$dataSaldoAwal->qty - $jmlPengurang;
                // dd($saldoAwalIn);
                $newSPD = StokProdukDetail::where('norec', $r_PPL['norec_spd'])
                    ->where('kdprofile', $idProfile)
                    ->where('statusenabled', true)
                    ->where('qtyproduk', '>=', $jmlPengurang)
                    ->first();
                    
                if (empty($newSPD)) {
                    $transMessage = "Simpan Resep Gagal, cek stok barang " . $namaProduk;
                    DB::rollBack();
                    $result = array(
                        "status" => 400,
                        "result" => null
                    );
                    return $this->respond($result['result'], $result['status'], $transMessage);
                }
                
                DB::table('stokprodukdetail_t')
                    ->where('kdprofile', $idProfile)
                    ->where('norec',  $r_PPL['norec_spd'])
                    ->where('statusenabled', true)
                    ->lockForUpdate()
                    ->decrement('qtyproduk', (float)$jmlPengurang);

                $dataKS = [];

                if (
                    // (float)$dataSaldoAwal->qty == 0 || 
                    $jmlPengurang > (float)$dataSaldoAwal->qty
                ) {
                    $transMessage = "Simpan Resep Gagal, Stok Produk " . $namaProduk . ", ada " . (float)$dataSaldoAwal->qty . " Data Stok Kurang Dari Qty Resep !";
                    DB::rollBack();
                    $result = array(
                        "status" => 400,
                        "result" => null
                    );
                    return $this->respond($result['result'], $result['status'], $transMessage);
                }
                $this->kartu_STOK(array(
                    "saldoawal" => $saldoAwalIn,
                    "qtyin" => 0,
                    "qtyout" => (float)$qtyJumlah,
                    "saldoakhir" => (float) $saldoAkhirIn,
                    "keterangan" => 'Pelayanan Obat Alkes No. '  . $noResep . '. Pada produk ' . $r_PPL['namaproduk'] . '. Atas pasien ' . $namaPasien,
                    "produkfk" => $r_PPL['produkfk'],
                    "ruanganfk" => $r_SR['ruanganfk'],
                    "tglinput" => date('Y-m-d H:i:s'),
                    "tglkejadian" => $tglTrans,
                    "nostrukterimafk" => $r_PPL['nostrukterimafk'],
                    "norectransaksi" => $norec_PP,
                    "tabletransaksi" => 'pelayananpasien_t',
                    "stokprodukdetailfk" => $r_PPL['norec_spd'],
                    "flagfk" => 7,
                ));
                $this->LOGGING(
                    $log,
                    $noResep,
                    'pelayananpasien_t',
                    $log . ''  . $noResep . '. Dengan produk ' . $r_PPL['namaproduk'] . '. Atas pasien ' . $namaPasien . '. No CM ' . $r_SR['nocm'] . '.No Registrasi ' .  $r_SR['noregistrasi']
                );

                //## Petugas
                $newP3 = new PelayananPasienPetugas();
                $norecKS = $newP3->generateNewId();
                $newP3->norec = $norecKS;
                $newP3->kdprofile = $idProfile;
                $newP3->statusenabled = true;
                $newP3->nomasukfk = $r_PPL['noregistrasifk'];
                $newP3->objectasalprodukfk = $r_PPL['asalprodukfk'];
                $newP3->objectjenispetugaspefk = $SET['jenisPetugasDokterPJ'];
                $newP3->objectprodukfk = $r_PPL['produkfk'];
                $newP3->objectruanganfk = $r_PPL['ruanganfk'];
                $newP3->pelayananpasien = $norec_PP;
                $newP3->tglpelayanan = $r_SR['tglresep'];
                $newP3->objectpegawaifk = $dokterPenulis;
                $newP3->noregistrasi =  $r_SR['noregistrasi'];
                $newP3->save();
            }


            if ($r_SR['noorder'] != 'EditResep' && in_array($r_SR['ruanganfk'], $SET['depoRajal'])) {

                // $dari = date('Y-m-d 00:00', strtotime($r_SR['tglresep']));
                // $sampai = date('Y-m-d 23:59', strtotime($r_SR['tglresep']));
                $dari = date('Y-m-d 00:00:00');
                $sampai = date('Y-m-d 23:59:59');
                $countAntrian = 0;
                $idProfile = 1;
                
                if (
                    $r_SR['ruanganfk'] == 325 &&
                    !in_array($r_SR['idruanganorder'], [385,382,376,384,369,379,378,373,371,370,365,386,375,367,381,383,380,377,374,372,368,366,756,769,361,312,322,323,332])
                ) 
                {
                    $cek = DB::table('antrianapotik_t')
                    ->select('noregistrasi')
                    ->where('noregistrasi',$r_SR['noregistrasi'])
                    ->get();

                    if ($cek->isEmpty()) 
                    {
                        if($racikanORnonracikan == 'A')
                            {
                                $countAntrian = AntrianApotik::where('kdprofile', $idProfile)
                                    ->whereBetween('tglresep', [$dari, $sampai])
                                    ->where('jenis','A')
                                    ->max('noantri');
                            }
                            else 
                            {
                                $countAntrian = AntrianApotik::where('kdprofile', $idProfile)
                                ->whereBetween('tglresep', [$dari, $sampai])
                                ->where('jenis','B')
                                ->max('noantri');
                            }

                            $noAntriApotik = (str_pad((int)$countAntrian + 1, 4, "0", STR_PAD_LEFT));

                            $newAA = new AntrianApotik();
                            $newAA->norec = $newAA->generateNewId();;
                            $newAA->kdprofile = $idProfile;
                            $newAA->noregistrasi = $r_SR['noregistrasi'];
                            $newAA->statusenabled = true;
                            $newAA->noantri = $noAntriApotik;
                            $newAA->keterangan = $namaPasien;
                            $newAA->jenis = $racikanORnonracikan;
                            $newAA->tglresep = date('Y-m-d H:i:s', strtotime($r_SR['tglresep']));
                            $newAA->noresep = $noResep;
                            $newAA->status = $SET['statusVerif'];
                            $newAA->save();
                            if ($r_SR['noorder'] != 'EditResep' && $r_SR['noorder'] != '') {
                                $dataOrder = StrukOrder::where('norec', $r_SR['noorder'])
                                ->where('kdprofile', $idProfile)
                                ->update([
                                'noantri' => $noAntriApotik,
                                'jenis' => $racikanORnonracikan,
                                'keterangaantrian' => $namaPasien,
                                ]);
                            }
                    }
                    else 
                    {
                        $updateAA = AntrianApotik::where('noregistrasi', $r_SR['noregistrasi'])->first();
                        if ($updateAA) {
                            $updateAA->keterangan = $namaPasien;
                            $updateAA->tglresep = date('Y-m-d H:i:s', strtotime($r_SR['tglresep']));
                            $updateAA->noresep = $noResep;
                            $updateAA->status = $SET['statusVerif'];
                            $updateAA->save();
                        }
                    }
                }
            }

            $responseResep = DB::table('strukresep_t as sr')
                ->leftjoin('antrianapotik_t as aa', 'aa.noresep', '=', 'sr.noresep')
                ->select(
                    'sr.norec',
                    'aa.jenis',
                    'sr.noresep',
                    'sr.noregistrasi',
                    'sr.pasienfk',
                    'sr.penulisresepfk',
                    'sr.petugas',
                    'sr.ruanganfk',
                    'sr.ruanganfk',
                    'sr.status'
                )
                ->where('sr.norec', $norec_SR)
                ->first();

            //endregion
            $transStatus = 'true';
        } catch (\Exception $e) {
            $transStatus = 'false';
        }

        if ($transStatus == 'true') {
            $transMessage = "Simpan Pelayanan Apotik Berhasil";
            DB::commit();
            $objetoRequest = new \Illuminate\Http\Request();
            $objetoRequest['noresep'] = $responseResep->noresep;
            $ihs = app('App\Http\Controllers\Bridging\SATUSEHATCtrl')->MedicationDispense($objetoRequest, true);
            $result = array(
                "status" => 200,
                "result" => array(
                    "noresep"  => $responseResep,
                    "as" => '@epic',
                    "medicationDispense" => $ihs,
                ),
            );
        } else {
            $transMessage = "Simpan Pelayanan Apotik Gagal!!";
            DB::rollBack();
            $result = array(
                "status" => 400,
                "result"  => $e->getMessage()
            );
        }

        return $this->respond($result['result'], $result['status'], $transMessage);
    }

    public function getDetailResep(Request $request)
    {
        $idProfile = $this->kdProfile;
        $dataAsalProduk = AsalProduk::mine()->get();
        $data = DB::table('strukresep_t as sr')
        ->JOIN('pegawai_m as pg', 'pg.id', '=', 'sr.penulisresepfk')
        ->JOIN('ruangan_m as ru2', 'ru2.id', '=', 'sr.ruanganfk')
        ->JOIN('pelayananpasien_t as pp', 'pp.strukresepfk', '=', 'sr.norec')
        ->LEFTJOIN('pelayananpasienobatkronis_t as pp2', function($join) {
            $join->on('pp2.strukresepfk', '=', 'pp.strukresepfk')
                 ->on('pp2.produkfk', '=', 'pp.produkfk')
                 ->where('pp.iskronis', true);
        })
        ->JOIN('antrianpasiendiperiksa_t as apd', 'apd.norec', '=', 'pp.noregistrasifk')
        ->JOIN('ruangan_m as ru', 'ru.id', '=', 'apd.objectruanganfk')
        ->JOIN('jeniskemasan_m as jk', 'jk.id', '=', 'pp.jeniskemasanfk')
        ->LEFTJOIN('routefarmasi as rt', 'rt.id', '=', 'pp.routefk')
        ->JOIN('produk_m as pr', 'pr.id', '=', 'pp.produkfk')
        ->JOIN('satuanstandar_m as ss', 'ss.id', '=', 'pr.objectsatuanstandarfk')
        ->JOIN('satuanstandar_m as ss2', 'ss2.id', '=', 'pp.satuanviewfk')
        ->LEFTJOIN('satuanresep_m as sn', 'sn.id', '=', 'pp.satuanresepfk')
        ->JOIN('stokprodukdetail_t as spd', 'spd.norec', '=', 'pp.stokprodukdetailfk')
        ->select(
            'pg.id as pgid',
            'pg.namalengkap',
            'sr.tepatpasien',
            'sr.tepatobat',
            'sr.campuranobat',
            'sr.tepatdosis',
            'sr.tepatrute',
            'sr.duplikasiobat',
            'sr.interaksiobat',
            'sr.jenisobatl5',
            'sr.kontraindikasi',
            'ru2.id as ruidresep',
            'ru2.namaruangan as ruanganresep',
            'sr.tglresep',
            'sr.noresep',
            'pp.hargasatuan',
            'pp.tglregistrasi',
            'pp.tglpemakaian',
            'pp.isbud',
            'pp.stock',
            'apd.objectruanganfk',
            'ru.namaruangan',
            'pp.rke',
            'pp.jeniskemasanfk',
            'jk.id as jkid',
            'jk.jeniskemasan',
            'pp.aturanpakai',
            'pp.routefk',
            'pp.noregistrasifk',
            'rt.name as route',
            'pp.produkfk',
            'pr.kdproduk',
            'pr.namaproduk',
            'pr.namaproduk as productname',
            'pp.nilaikonversi',
            'pr.objectsatuanstandarfk',
            'ss.satuanstandar',
            'pp.satuanviewfk',
            'ss2.satuanstandar as ssview',
            'pp.jumlah',
            'pp.hargadiscount',
            'pp.dosis',
            'pp.jenisracikanfk',
            'pp.jasa',
            'pp.racikan',
            'pp.hargajual',
            'pp.hargasatuan',
            'pp.strukterimafk',
            'pp.qtydetailresep',
            'pp.ispagi',
            'pp.issiang',
            'pp.ismalam',
            'pp.issore',
            'pr.kekuatan',
            'pp.keteranganpakai',
            'pp.iskronis',
            'pp.isdonasi',
            'pp.satuanresepfk',
            'sn.satuanresep',
            'pp.tglkadaluarsa',
            'pp.norec as norecpp',
            'pp2.norec as norecpp2',
            'pp2.jumlah as jumlah2',
            'pp.jenisobatfk',
            'apd.objectkelasfk as kelasfk',
            'pp.persendiscount',
            'pp.stokprodukdetailfk as norec_spd',
            'pp.strukterimafk',
            'spd.objectasalprodukfk',
            'spd.qtyproduk as jmlstok'
        )
            ->where('sr.kdprofile', $idProfile)
            ->where('sr.norec', $request['norecResep'])
            ->get();


            // $data2 = DB::table('strukresep_t as sr')
            // ->JOIN('pegawai_m as pg', 'pg.id', '=', 'sr.penulisresepfk')
            // ->JOIN('ruangan_m as ru2', 'ru2.id', '=', 'sr.ruanganfk')
            // ->JOIN('pelayananpasienobatkronis_t as pp', 'pp.strukresepfk', '=', 'sr.norec')
            // ->JOIN('antrianpasiendiperiksa_t as apd', 'apd.norec', '=', 'pp.noregistrasifk')
            // ->JOIN('ruangan_m as ru', 'ru.id', '=', 'apd.objectruanganfk')
            // ->JOIN('jeniskemasan_m as jk', 'jk.id', '=', 'pp.jeniskemasanfk')
            // ->LeftJOIN('routefarmasi as rt', 'rt.id', '=', 'pp.routefk')
            // ->JOIN('produk_m as pr', 'pr.id', '=', 'pp.produkfk')
            // ->JOIN('satuanstandar_m as ss', 'ss.id', '=', 'pr.objectsatuanstandarfk')
            // ->JOIN('satuanstandar_m as ss2', 'ss2.id', '=', 'pp.satuanviewfk')
            // ->LeftJOIN('satuanresep_m as sn', 'sn.id', '=', 'pp.satuanresepfk')
            // ->JOIN('stokprodukdetail_t as spd', 'spd.norec', '=', 'pp.stokprodukdetailfk')
            // ->select(
            //     'pg.id as pgid',
            //     'pg.namalengkap',
            //     'ru2.id as ruidresep',
            //     'ru2.namaruangan as ruanganresep',
            //     'sr.tglresep',
            //     'sr.noresep',
            //     'pp.hargasatuan',
            //     'pp.tglregistrasi',
            //     'pp.tglpemakaian',
            //     'pp.isbud',
            //     'pp.stock',
            //     'apd.objectruanganfk',
            //     'ru.namaruangan',
            //     'pp.rke',
            //     'pp.jeniskemasanfk',
            //     'jk.id as jkid',
            //     'jk.jeniskemasan',
            //     'pp.aturanpakai',
            //     'pp.routefk',
            //     'pp.noregistrasifk',
            //     'rt.name as route',
            //     'pp.produkfk',
            //     'pr.kdproduk',
            //     'pr.namaproduk',
            //     'pr.namaproduk as productname',
            //     'pp.nilaikonversi',
            //     'pr.objectsatuanstandarfk',
            //     'ss.satuanstandar',
            //     'pp.satuanviewfk',
            //     'ss2.satuanstandar as ssview',
            //     'pp.jumlah',
            //     'pp.hargadiscount',
            //     'pp.dosis',
            //     'pp.jenisracikanfk',
            //     'pp.jasa',
            //     'pp.racikan',
            //     'pp.hargajual',
            //     'pp.hargasatuan',
            //     'pp.strukterimafk',
            //     'pp.qtydetailresep',
            //     'pp.ispagi',
            //     'pp.issiang',
            //     'pp.ismalam',
            //     'pp.issore',
            //     'pr.kekuatan',
            //     'pp.keteranganpakai',
            //     'pp.iskronis',
            //     'pp.isdonasi',
            //     'pp.satuanresepfk',
            //     'sn.satuanresep',
            //     'pp.tglkadaluarsa',
            //     'pp.norec as norecpp',
            //     'pp.jenisobatfk',
            //     'apd.objectkelasfk as kelasfk',
            //     'pp.persendiscount',
            //     'pp.stokprodukdetailfk as norec_spd',
            //     'pp.strukterimafk',
            //     'spd.objectasalprodukfk',
            //     'spd.qtyproduk as jmlstok'
            // )
            // ->where('sr.kdprofile', $idProfile)
            // ->where('sr.norec', $request['norecResep'])
            // ->get();
        // ->where()
        // if (isset($request['norecResep']) && $request['norecResep'] != "" && $request['norecResep'] != "undefined") {
        //     $data = $data->where('sr.norec', '=', $request['norecResep']);
        // }

        $asalprodukfk = 0;
        $asalproduk = '';
        $hargadiscount = 0;
        $total = 0;
        $totalbayar = 0;
        $aturanpakaifk = null;
        $aturanpakai = null;
        foreach ($data as $i => $item) {
            // return $item->aturanpakai;
            $asalprodukfk = $item->objectasalprodukfk;

            $total = (((float)$item->jumlah * ((float) $item->hargasatuan - (float) $item->hargadiscount)));

            foreach ($dataAsalProduk as $item3) {
                if ($asalprodukfk == $item3->id) {
                    $asalproduk = $item3->asalproduk;
                }
            }
            $jmlxMakan = (((float)$item->jumlah / (float)$item->nilaikonversi) / (float)$item->dosis) * (float)$item->kekuatan;

            $item->no =  $i + 1;
            $item->generik = null;
            $item->stock = $item->jmlstok;
            $item->hargajual = $item->hargajual;
            $item->harganetto = $item->hargasatuan;
            $item->hargasatuan =  $item->hargasatuan;
            $item->hargadiscount = $hargadiscount;
            $item->nostrukterimafk =  $item->strukterimafk;
            $item->ruanganfk =  $item->ruidresep;
            // $item->aturanpakaifk = $aturanpakaifk;
            $item->aturanpakai = $item->aturanpakai;
            $item->isbud = $item->isbud;
            $item->tglpemakaian = $item->tglpemakaian;
            $item->route = $item->route;
            $item->asalprodukfk = $asalprodukfk;
            $item->asalproduk = $asalproduk;
            $item->satuanstandarfk = $item->satuanviewfk;
            $item->satuanstandar = $item->ssview;
            $item->satuanview = $item->ssview;
            $item->kode_namaproduk = $item->kdproduk . ' - ' . $item->namaproduk;
            $item->jmlstok = $item->stock;
            $item->jumlah = $item->jumlah / $item->nilaikonversi;
            $item->jumlahobat = $item->qtydetailresep;
            if ($item->isdonasi === true) {
                $item->total = 0;
            } else {
                $item->total = $total + $item->jasa;
            }
            $item->total = $total + $item->jasa;
            $item->jmldosis = (string)$jmlxMakan . '/' . (string)$item->dosis . '/' . $item->kekuatan;
            $item->keterangan = $item->keteranganpakai;

            $dataStruk['pgid'] = $item->pgid;
            $dataStruk['namalengkap'] = $item->namalengkap;
            $dataStruk['id'] = $item->ruidresep;
            $dataStruk['namaruangan'] = $item->ruanganresep;
            $dataStruk['tglresep'] = $item->tglresep;
            $dataStruk['noresep'] = $item->noresep;
        }

        $result = array(
            'detailresep' => $dataStruk,
            'pelayananPasien' => $data,
            'message' => 'as@epic',
        );

        return $this->respond($result);
    }

    
    public function getDetailOrder(Request $request)
    {
        $idProfile =  $this->kdProfile;
        $dataAsalProduk = AsalProduk::mine()->get();
        $dataSigna = Signa::mine()->get();
        $set = json_decode($this->settingFix('kelompokTransaksiOrderResep'));
        $dataStruk = DB::table('strukorder_t as so')
            ->JOIN('pegawai_m as pg', 'pg.id', '=', 'so.objectpegawaiorderfk')
            ->JOIN('ruangan_m as ru', 'ru.id', '=', 'so.objectruangantujuanfk')
            ->select('so.noorder as norec_order', 'pg.id as pgid', 'pg.namalengkap', 'ru.id', 'ru.namaruangan', 'so.tglorder', 'so.statusorder', 'so.cito', 'so.isbpl', 'so.isrutin', 'so.alergiobat')
            ->where('so.kdprofile', $idProfile)
            ->where('so.statusorder', $set->statusorder);

        if (isset($request['noorder']) && $request['noorder'] != "" && $request['noorder'] != "undefined") {
            $dataStruk = $dataStruk->where('so.noorder', '=', $request['noorder']);
        }
        if (isset($request['norec']) && $request['norec'] != "" && $request['norec'] != "undefined") {
            $dataStruk = $dataStruk->where('so.norec', '=', $request['norec']);
        }
        $dataStruk = $dataStruk->first();
        if (empty($dataStruk)) {
            $result = array(
                'strukorder' => null,
                'orderpelayanan' => [],
            );
            return $this->respond($result);
        }



        $data = DB::table('strukorder_t as so')
            ->JOIN('orderpelayanan_t as op', 'op.strukorderfk', '=', 'so.norec')
            ->JOIN('ruangan_m as ru', 'ru.id', '=', 'so.objectruangantujuanfk')
            ->leftJOIN('jeniskemasan_m as jk', 'jk.id', '=', 'op.jeniskemasanfk')
            ->leftJOIN('routefarmasi as rt', 'rt.id', '=', 'op.routefk')
            ->JOIN('produk_m as pr', 'pr.id', '=', 'op.objectprodukfk')
            ->leftJOIN('rm_sediaan_m as sdn', 'sdn.id', '=', 'pr.objectsediaanfk')
            ->leftJOIN('satuanstandar_m as ss', 'ss.id', '=', 'op.objectsatuanstandarfk')
            ->leftJOIN('satuanstandar_m as ss2', 'ss2.id', '=', 'op.satuanviewfk')
            ->leftJOIN('satuanresep_m as sn', 'sn.id', '=', 'op.satuanresepfk')
            ->select(
                'so.noorder as norec_order',
                'op.hargasatuan',
                'op.qtystokcurrent',
                'so.objectruangantujuanfk',
                'ru.namaruangan',
                'op.rke',
                'so.alergiobat',
                'op.jeniskemasanfk',
                'jk.id as jkid',
                'jk.jeniskemasan',
                'op.aturanpakai',
                'op.routefk',
                'rt.name as namaroute',
                'op.objectprodukfk',
                'pr.namaproduk',
                'pr.kdproduk',
                'op.hasilkonversi',
                'op.objectsatuanstandarfk',
                'ss.satuanstandar',
                'op.satuanviewfk',
                'ss2.satuanstandar as ssview',
                'op.qtyproduk',
                'op.hargadiscount',
                'op.hasilkonversi',
                'op.qtystokcurrent',
                'op.dosis',
                'op.jenisobatfk',
                'op.hargasatuan',
                'op.hargadiscount',
                'pr.kekuatan',
                'sdn.name as sediaan',
                'op.ispagi',
                'op.issiang',
                'op.ismalam',
                'op.issore',
                'op.racikan',
                'op.keteranganpakai',
                'op.iskronis23',
                'op.iskronis30',
                'op.satuanresepfk',
                'sn.satuanresep',
                'op.tglkadaluarsa',
                'so.isreseppulang',
                'pr.isobatkronis',
                'op.alergiobat',
                'so.norec  as norec_so'
            )
            ->where('so.kdprofile', $idProfile);

        if (isset($request['noorder']) && $request['noorder'] != "" && $request['noorder'] != "undefined") {
            $data = $data->where('so.noorder', '=', $request['noorder']);
        }
        if (isset($request['norec']) && $request['norec'] != "" && $request['norec'] != "undefined") {
            $data = $data->where('so.norec', '=', $request['norec']);
        }
        $data = $data->get();

        $dateBetween = [date("Y-m-d", strtotime("-1 month")), date("Y-m-d")];

        $getLastOrder = DB::table('pelayananpasien_t as pp')
            ->join('antrianpasiendiperiksa_t as apd', 'apd.norec', 'pp.noregistrasifk')
            ->join('pasiendaftar_t as pd', 'pd.norec', 'apd.noregistrasifk')
            ->join('produk_m as pr', 'pr.id', 'pp.produkfk')
            ->join('pasien_m as ps', 'ps.id', 'pd.nocmfk')
            ->select('ps.namapasien', 'pp.norec', 'pp.tglpelayanan', 'pr.namaproduk', 'ps.nocm', 'pp.produkfk', 'pp.iskronis')
            ->distinct()
            // ->where('pp.produkfk',$request['produkfk'])
            ->where('pd.nocmfk', $request['nocmfk'])
            ->where('apd.norec', $request['norec_apd'])
            ->whereBetween(DB::raw("CAST(pp.tglpelayanan AS DATE)"), $dateBetween)
            ->orderByDesc('pp.tglpelayanan')
            ->get();

        $getLastOrder2 = DB::table('pelayananpasien_t as pp')
            ->join('antrianpasiendiperiksa_t as apd', 'apd.norec', 'pp.noregistrasifk')
            ->join('pasiendaftar_t as pd', 'pd.norec', 'apd.noregistrasifk')
            ->join('produk_m as pr', 'pr.id', 'pp.produkfk')
            ->join('pasien_m as ps', 'ps.id', 'pd.nocmfk')
            ->select(
                'ps.namapasien',
                'pr.namaproduk',
                'ps.nocm',
                'pp.produkfk',
                'pp.iskronis',
                DB::raw('SUM(pp.jumlah) AS total_jumlah')
            )
            // ->where('pp.produkfk',$request['produkfk'])
            ->where('pd.nocmfk', $request['nocmfk'])
            ->where('apd.norec', $request['norec_apd'])
            ->whereBetween(DB::raw("CAST(pp.tglpelayanan AS DATE)"), $dateBetween)
            ->groupBy(
                'ps.namapasien',
                'pr.namaproduk',
                'ps.nocm',
                'pp.produkfk',
                'pp.iskronis'
            )
            ->get();


        $orderPelayanan = [];
        $i = 0;
        $dataStok = DB::select(
            DB::raw("select
                    sk.norec,
                    spd.objectprodukfk,
                    sk.tglstruk,
                    spd.objectasalprodukfk,
                    spd.harganetto2 as hargajual,spd.harganetto2 as harganetto,
                    spd.hargadiscount,
                    sum(spd.qtyproduk) as qtyproduk,
                    spd.objectruanganfk,
                    spd.norec as norec_spd
                    from stokprodukdetail_t as spd
                    inner JOIN strukpelayanan_t as sk on sk.norec=spd.nostrukterimafk
                    where 
                    spd.statusenabled = true 
                    and spd.kdprofile = :kdprofile 
                    and spd.objectruanganfk =:ruanganid
                    and spd.qtyproduk > 0
                    group by
                    sk.norec,spd.objectprodukfk,
                    sk.tglstruk,spd.objectasalprodukfk,
                    spd.harganetto2,spd.hargadiscount,  spd.objectruanganfk,
                    spd.norec
                    order By spd.qtyproduk desc ,sk.tglstruk"),
            array(
                'ruanganid' => $dataStruk->id,
                'kdprofile' => $idProfile
            )
        );


        $hargajual = 0;
        $harganetto = 0;
        $nostrukterimafk = '';
        $norec_SPD = '';
        $asalprodukfk = 0;
        $asalproduk = '';
        $jmlstok = 0;
        $hargasatuan = 0;
        $hargadiscount = 0;
        $total = 0;
        $totalbayar = 0;
        $aturanpakaifk = 0;
        $rke = '0';
        $tarifadminjasa = $this->settingFix('tarifadminresep');
        $tarifKitas = $this->settingFix('jasaNonRacikanWNA-Kitas');
        $tarifNonKitas = $this->settingFix('jasaNonRacikanWNA-NonKitas');
        $tarifWNI = $this->settingFix('jasaNonRacikanWNI');
        foreach ($data as $item) {

            $tglLast = '';
            $isKronis = false;
            foreach ($getLastOrder as $last) {
                if ($last->produkfk == $item->objectprodukfk) {
                    $tglLast = $last->tglpelayanan;
                    $isKronis = $last->iskronis;
                }
            }

            $jumlahLast = '';
            foreach ($getLastOrder2 as $last) {
                if ($last->produkfk == $item->objectprodukfk) {
                    $jumlahLast = $last->total_jumlah;
                }
            }

            $i = $i + 1;
            $hargajual = 0;
            $harganetto = 0;
            $jmlstok = 0;
            $hargasatuan = 0;
            $hargadiscount = 0;
            $total = 0;
            $tarifjasa = 0;
            $qty20 = 0;
            if ($item->jkid == 2) {
                $tarifjasa = 0; // $tarifadminjasa;
            }
            if ($item->jkid == 1) {
                if ($rke != $item->rke) {
                    if ($item->qtyproduk > 20) {
                        $qty20 = number_format($item->qtyproduk / 20, 0);
                        if ($item->qtyproduk % 20 == 0) {
                            $qty20 = $qty20;
                        } else {
                            $qty20 = $qty20 + 1;
                        }

                        $tarifjasa = $tarifadminjasa * $qty20;
                    } else {
                        $tarifjasa = $tarifadminjasa;
                    }
                    $rke = $item->rke;
                }
            }
            $hargajual = round($item->hargasatuan, 0);
            $hargasatuan = round($item->hargasatuan, 0);
            $harganetto = round($item->hargadiscount, 0);

            foreach ($dataStok as $item2) {
                if ($item2->objectprodukfk == $item->objectprodukfk) {
                    if ($item2->qtyproduk > $item->qtyproduk * $item->hasilkonversi) {

                        $nostrukterimafk = $item2->norec;
                        $asalprodukfk = $item2->objectasalprodukfk;
                        $jmlstok = $item2->qtyproduk;
                        $norec_SPD = $item2->norec_spd;
                        $hargadiscount = $item2->hargadiscount;
                        $total = (((float)ceil($item->qtyproduk) * ((float)$hargasatuan - (float)$hargadiscount)) * $item->hasilkonversi) + $tarifjasa;
                        $totalbayar += $total;
                        break;
                    }
                }
            }
            foreach ($dataAsalProduk as $item3) {
                if ($asalprodukfk == $item3->id) {
                    $asalproduk = $item3->asalproduk;
                }
            }
            foreach ($dataSigna as $item4) {
                if ($item->aturanpakai == $item4->id) {
                    $aturanpakaifk = $item4->id;
                }
            }
            if ((float)$item->dosis == 0) {
                $item->dosis = 1;
            }
            $orderPelayanan[] = array(
                'no' => $i,
                'noregistrasifk' => '',
                'tglregistrasi' => '',
                'generik' => null,
                'hargajual' => $hargajual,
                'jenisobatfk' => $item->jenisobatfk,
                'kelasfk' => '',
                'lastorder' => $tglLast,
                'jumlahlast' => $jumlahLast,
                'stock' => $jmlstok,
                'harganetto' => $harganetto,
                'nostrukterimafk' => $nostrukterimafk,
                'ruanganfk' => $item->objectruangantujuanfk,
                'rke' => $item->rke,
                'jeniskemasanfk' => $item->jeniskemasanfk,
                'jeniskemasan' => $item->jeniskemasan,
                'aturanpakaifk' => $aturanpakaifk,
                'aturanpakai' => $item->aturanpakai,
                'routefk' => $item->routefk,
                'route' => $item->namaroute,
                'asalprodukfk' => $asalprodukfk,
                'asalproduk' => $asalproduk,
                'produkfk' => $item->objectprodukfk,
                'namaproduk' => $item->namaproduk,
                'kdproduk' => $item->kdproduk,
                'nilaikonversi' => $item->hasilkonversi,
                'satuanstandarfk' => $item->satuanviewfk,
                'satuanstandar' => $item->ssview,
                'satuanviewfk' => $item->satuanviewfk,
                'satuanview' => $item->ssview,
                'jmlstok' => $jmlstok,//$item->qtystokcurrent,
                'jumlah' => ceil($item->qtyproduk),
                'jumlahobat' => ceil($item->qtyproduk),
                'dosis' => $item->dosis,
                'kekuatan' => $item->kekuatan,
                'hargasatuan' => $hargasatuan,
                'hargadiscount' => $hargadiscount,
                'total' => $total,
                'sediaan' => $item->sediaan,
                'jmldosis' => (string)$item->qtyproduk / $item->dosis . '/' . (string)$item->dosis . '/' . (string)$item->kekuatan,
                'jasa' => $tarifjasa,
                'ispagi' => $item->ispagi,
                'issiang' =>  $item->issiang,
                'iskronis23' =>  $item->iskronis23,
                'iskronis30' =>  $item->iskronis30,
                'ismalam' =>  $item->ismalam,
                'issore' =>  $item->issore,
                "keterangan" => $item->keteranganpakai,
                'satuanresepfk' =>  $item->satuanresepfk,
                "satuanresep" => $item->satuanresep,
                "tglkadaluarsa" => $item->tglkadaluarsa,
                "isreseppulang" => $item->isreseppulang,
                "norec_spd" => $norec_SPD,
                "obatkronis" => null,//(bool)$isKronis,
                "alergiobat" => $item->alergiobat,
                "norec_pp" => $item->norec_so,
                'racikan' => $item->racikan,
            );
        }

        $result = array(
            'strukorder' => $dataStruk,
            'orderpelayanan' => $orderPelayanan,
            'totalbayar' => $totalbayar,
        );
        return $this->respond($result);
    }

    public function SimpanReturPelayananObat(Request $request)
    {
        $r_SR = $request['strukresep'];
        $norec_SR = $r_SR['norecResep'];
        $r_PP = $request['pelayananpasien'];

        DB::beginTransaction();
        try {
            $newSRetur = new StrukRetur();
            $norecSRetur = $newSRetur->generateNewId();
            $noRetur = $this->generateCode(new StrukRetur, 'noretur', 12, 'Ret/' . $this->getDateTime()->format('ym') . '/', $this->kdProfile);
            $newSRetur->norec = $norecSRetur;
            $newSRetur->kdprofile = $this->kdProfile;
            $newSRetur->statusenabled = true;
            $newSRetur->objectkelompoktransaksifk = $this->kelompokTransaksi('RETUR RUANGAN');
            $newSRetur->keteranganalasan = $r_SR['alasan'];
            $newSRetur->keteranganlainnya = 'RETUR OBAT ALKES';
            $newSRetur->noretur = $noRetur;
            $newSRetur->objectruanganfk = $r_SR['ruanganfk'];
            $newSRetur->objectpegawaifk = $this->getPegawaiId();
            $newSRetur->tglretur = $this->getDateTime()->format('Y-m-d H:i:s');
            $newSRetur->strukresepfk = $norec_SR;
            // $newSRetur->totalretur = $r_SR['totalretur'];
            // $newSRetur->jumlahitem = $r_SR['jumlahitem'];
            $newSRetur->save();
            $norec_retur = $newSRetur->norec;

            foreach ($r_PP as $r_PPLXXXX) {
                if (isset($r_PPLXXXX['jmlretur'])) {
                    $newPPR = new PelayananPasienRetur();
                    $norecPPR = $newPPR->generateNewId();
                    $newPPR->norec = $norecPPR;
                    $newPPR->kdprofile = $this->kdProfile;
                    $newPPR->statusenabled = true;
                    $newPPR->noregistrasifk = $r_PPLXXXX['noregistrasifk'];
                    $newPPR->tglregistrasi = $r_PPLXXXX['tglregistrasi'];
                    $newPPR->aturanpakai = $r_PPLXXXX['aturanpakai'];
                    $newPPR->generik = $r_PPLXXXX['generik'];
                    $newPPR->hargadiscount = $r_PPLXXXX['hargadiscount'];
                    $newPPR->hargajual = $r_PPLXXXX['hargajual'];
                    $newPPR->hargasatuan = $r_PPLXXXX['hargasatuan'];
                    $newPPR->jenisobatfk = $r_PPLXXXX['jenisobatfk'];
                    $newPPR->jumlah = $r_PPLXXXX['jmlretur'];
                    $newPPR->kelasfk = $r_PPLXXXX['kelasfk'];
                    $newPPR->kdkelompoktransaksi = 1;
                    $newPPR->produkfk = $r_PPLXXXX['produkfk'];
                    if (isset($r_PPL['routefk'])) {
                        $newPPR->routefk = $r_PPLXXXX['routefk'];
                    }
                    $newPPR->stock = $r_PPLXXXX['stock'];
                    $newPPR->tglpelayanan = $r_SR['tglresep'];
                    $newPPR->harganetto = $r_PPLXXXX['harganetto'];
                    $newPPR->jeniskemasanfk = $r_PPLXXXX['jeniskemasanfk'];
                    $newPPR->rke = $r_PPLXXXX['rke'];
                    $newPPR->strukresepfk = $norec_SR;
                    $newPPR->satuanviewfk = $r_PPLXXXX['satuanviewfk'];
                    $newPPR->nilaikonversi = $r_PPLXXXX['nilaikonversi'];
                    $newPPR->strukterimafk = $r_PPLXXXX['nostrukterimafk'];
                    $newPPR->dosis = $r_PPLXXXX['dosis'];
                    if ((int)$r_PPLXXXX['jumlah'] == 0) {
                        $newPPR->jasa = $r_PPLXXXX['jasa'];
                    } else {
                        $newPPR->jasa = 0;
                    }
                    $newPPR->strukreturfk = $norec_retur;
                    $newPPR->save();

                    $TambahStok = (float)$r_PPLXXXX['jmlretur'] * (float)$r_PPLXXXX['nilaikonversi'];
                    $dataSaldoAwal = collect(DB::select("
                         select sum(qtyproduk) as qty from stokprodukdetail_t
                         where kdprofile = $this->kdProfile and statusenabled = true and objectruanganfk=$r_SR[ruanganfk]
                         and objectprodukfk=$r_PPLXXXX[produkfk]
                    "))->first();

                    $saldoAkhir = (float)$dataSaldoAwal->qty + (float)$TambahStok;;

                    $newSPD = StokProdukDetail::where('nostrukterimafk', $r_PPLXXXX['nostrukterimafk'])
                        ->where('kdprofile', $this->kdProfile)
                        ->where('statusenabled', true)
                        ->where('objectruanganfk', $r_SR['ruanganfk'])
                        ->where('objectprodukfk', $r_PPLXXXX['produkfk'])
                        ->orderby('tglkadaluarsa', 'desc')
                        ->first();

                    DB::table('stokprodukdetail_t')
                        ->where('kdprofile', $this->kdProfile)
                        ->where('statusenabled', true)
                        ->where('norec', $newSPD->norec)
                        ->lockForUpdate()
                        ->increment('qtyproduk', (float)$TambahStok);

                    $this->kartu_STOK(array(
                        "saldoawal" => (float)$dataSaldoAwal->qty,
                        "qtyin" => (float)$TambahStok,
                        "qtyout" => 0,
                        "saldoakhir" => $saldoAkhir,
                        "keterangan" => 'Retur Resep Pasien No. ' . $r_PPLXXXX['noresep'] . '. Berupa Produk : ' . $r_PPLXXXX['namaproduk'],
                        "produkfk" => $r_PPLXXXX['produkfk'],
                        "ruanganfk" => $r_SR['ruanganfk'],
                        "tglinput" => date('Y-m-d H:i:s'),
                        "tglkejadian" => date('Y-m-d H:i:s'),
                        "nostrukterimafk" => $newSPD->nostrukterimafk,
                        "norectransaksi" => $newSPD->norec,
                        "tabletransaksi" => 'pelayananpasien_t',
                        "stokprodukdetailfk" => $newSPD->norec,
                        "flagfk" => 3,
                    ));

                    $jumlahNow = (int)$r_PPLXXXX['jumlah'] - (int) $r_PPLXXXX['jmlretur'];
                    PelayananPasien::where('norec', $r_PPLXXXX['norecpp'])->update(['jumlah' =>  $jumlahNow, 'qtydetailresep' => $jumlahNow]);
                }
            }
            $this->LOGGING(
                'Retur Resep',
                $r_SR['noresep'] ?? '',
                'pelayananpasien_t',
                'Retur Resep ' . ($r_SR['noresep'] ?? '') . '. Dengan produk ' . ($r_PPLXXXX['namaproduk'] ?? '') . '. Atas pasien ' . ($r_SR['namapasien'] ?? '') . '. ' . ($r_SR['nocm'] ?? '') . '. ' . ($r_SR['noregistrasi'] ?? '')
            );
            DB::commit();
            $result = [
                "status" => 200,
                "message" => 'Retur resep berhasil disimpan',
                "result" => ['strukRetur' => $newSRetur]
            ];
        } catch (Exception $e) {
            DB::rollBack();
            $result = [
                "status" => 400,
                "message"  => 'Something Went Wrong',
                "result" => $e->getMessage()
            ];
        }

        return $this->respond($result, $result['status'], $result['message']);
    }

    public function SimpanReturResepDibayar(Request $request)
    {
        $r_SR = $request['strukresep'];
        $norec_SR = $r_SR['norecResep'];
        $r_PP = $request['pelayananpasien'];

        DB::beginTransaction();
        try {
            $newSRetur = new StrukRetur();
            $norecSRetur = $newSRetur->generateNewId();
            $noRetur = $this->generateCode(new StrukRetur, 'noretur', 12, 'Ret/' . $this->getDateTime()->format('ym') . '/', $this->kdProfile);
            $newSRetur->norec = $norecSRetur;
            $newSRetur->kdprofile = $this->kdProfile;
            $newSRetur->statusenabled = true;
            $newSRetur->objectkelompoktransaksifk = $this->kelompokTransaksi('RETUR RUANGAN');
            $newSRetur->keteranganalasan = $r_SR['alasan'];
            $newSRetur->keteranganlainnya = 'RETUR OBAT ALKES';
            $newSRetur->noretur = $noRetur;
            $newSRetur->objectruanganfk = $r_SR['ruanganfk'];
            $newSRetur->objectpegawaifk = $this->getPegawaiId();
            $newSRetur->tglretur = $this->getDateTime()->format('Y-m-d H:i:s');
            $newSRetur->strukresepfk = $norec_SR;
            $newSRetur->jumlahitem = $r_SR['jumlahitem'];
            $newSRetur->totalretur = $r_SR['totalretur'];
            $newSRetur->save();
            $norec_retur = $newSRetur->norec;

            foreach ($r_PP as $r_PPLXXXX) {
                if (isset($r_PPLXXXX['jmlretur'])) {
                    $newPPR = new PelayananPasienRetur();
                    $norecPPR = $newPPR->generateNewId();
                    $newPPR->norec = $norecPPR;
                    $newPPR->kdprofile = $this->kdProfile;
                    $newPPR->statusenabled = true;
                    $newPPR->noregistrasifk = $r_PPLXXXX['noregistrasifk'];
                    $newPPR->tglregistrasi = $r_PPLXXXX['tglregistrasi'];
                    $newPPR->aturanpakai = $r_PPLXXXX['aturanpakai'];
                    $newPPR->generik = $r_PPLXXXX['generik'];
                    $newPPR->hargadiscount = $r_PPLXXXX['hargadiscount'];
                    $newPPR->hargajual = $r_PPLXXXX['hargajual'];
                    $newPPR->hargasatuan = $r_PPLXXXX['hargasatuan'];
                    $newPPR->jenisobatfk = $r_PPLXXXX['jenisobatfk'];
                    $newPPR->jumlah = $r_PPLXXXX['jmlretur'];
                    $newPPR->kelasfk = $r_PPLXXXX['kelasfk'];
                    $newPPR->kdkelompoktransaksi = 1;
                    $newPPR->produkfk = $r_PPLXXXX['produkfk'];
                    if (isset($r_PPL['routefk'])) {
                        $newPPR->routefk = $r_PPLXXXX['routefk'];
                    }
                    $newPPR->stock = $r_PPLXXXX['stock'];
                    $newPPR->tglpelayanan = $r_SR['tglresep'];
                    $newPPR->harganetto = $r_PPLXXXX['harganetto'];
                    $newPPR->jeniskemasanfk = $r_PPLXXXX['jeniskemasanfk'];
                    $newPPR->rke = $r_PPLXXXX['rke'];
                    $newPPR->strukresepfk = $norec_SR;
                    $newPPR->satuanviewfk = $r_PPLXXXX['satuanviewfk'];
                    $newPPR->nilaikonversi = $r_PPLXXXX['nilaikonversi'];
                    $newPPR->strukterimafk = $r_PPLXXXX['nostrukterimafk'];
                    $newPPR->dosis = $r_PPLXXXX['dosis'];
                    if ((int)$r_PPLXXXX['jumlah'] == 0) {
                        $newPPR->jasa = $r_PPLXXXX['jasa'];
                    } else {
                        $newPPR->jasa = 0;
                    }
                    $newPPR->strukreturfk = $norec_retur;
                    $newPPR->save();

                    $saldoAwal = 0;
                    $TambahStok = (float)$r_PPLXXXX['jmlretur'] * (float)$r_PPLXXXX['nilaikonversi'];
                    $dataSaldoAwal = collect(DB::select("
                         select sum(qtyproduk) as qty from stokprodukdetail_t
                         where statusenabled = true and kdprofile = $this->kdProfile and objectruanganfk=$r_SR[ruanganfk]
                         and objectprodukfk=$r_PPLXXXX[produkfk]
                    "))->first();

                    $saldoAkhir = (float)$dataSaldoAwal->qty + (float)$TambahStok;;

                    $newSPD = StokProdukDetail::where('nostrukterimafk', $r_PPLXXXX['nostrukterimafk'])
                        ->where('kdprofile', $this->kdProfile)
                        ->where('statusenabled', true)
                        ->where('objectruanganfk', $r_SR['ruanganfk'])
                        ->where('objectprodukfk', $r_PPLXXXX['produkfk'])
                        ->orderby('tglkadaluarsa', 'desc')
                        ->first();

                    DB::table('stokprodukdetail_t')
                        ->where('kdprofile', $this->kdProfile)
                        ->where('statusenabled', true)
                        ->where('norec', $newSPD->norec)
                        ->lockForUpdate()
                        ->increment('qtyproduk', (float)$TambahStok);

                    $this->kartu_STOK(array(
                        "saldoawal" => (float)$dataSaldoAwal->qty,
                        "qtyin" => (float)$TambahStok,
                        "qtyout" => 0,
                        "saldoakhir" => $saldoAkhir,
                        "keterangan" => 'Retur Resep sudah dibayar No. ' . $r_PPLXXXX['noresep'],
                        "produkfk" => $r_PPLXXXX['produkfk'],
                        "ruanganfk" => $r_SR['ruanganfk'],
                        "tglinput" => date('Y-m-d H:i:s'),
                        "tglkejadian" => date('Y-m-d H:i:s'),
                        "nostrukterimafk" => $newSPD->nostrukterimafk,
                        "norectransaksi" => $newSPD->norec,
                        "tabletransaksi" => 'pelayananpasien_t',
                        "stokprodukdetailfk" => $newSPD->norec,
                        "flagfk" => 3,
                    ));
                    $this->LOGGING(
                        'Retur Resep',
                        $r_SR['noresep'] ?? '',
                        'pelayananpasien_t',
                        'Retur Resep ' . ($r_SR['noresep'] ?? '') . '. Dengan produk ' . ($r_PPLXXXX['namaproduk'] ?? '') . '. Atas pasien ' . ($r_SR['namapasien'] ?? '') . '. ' . ($r_SR['nocm'] ?? '') . '. ' . ($r_SR['noregistrasi'] ?? '')
                    );
                }
            }

            DB::commit();
            $result = [
                "status" => 200,
                "message" => 'Retur resep berhasil disimpan',
                "result" => ['strukRetur' => $newSRetur]
            ];
        } catch (Exception $e) {
            DB::rollBack();
            $result = [
                "status" => 400,
                "message"  => 'Something Went Wrong',
                "result" => $e->getMessage()
            ];
        }

        return $this->respond($result, $result['status'], $result['message']);
    }

    public function cetakKwitansi(Request $request)
    {

        $profile = $this->profile();

        $dataOrder = DB::table('pasiendaftar_t as pd')
            ->JOIN('ruangan_m as ru', 'ru.id', '=', 'pd.objectruanganlastfk')
            ->JOIN('pasien_m as ps', 'ps.id', '=', 'pd.nocmfk')
            ->leftJoin('jeniskelamin_m as jk', 'jk.id', '=', 'ps.objectjeniskelaminfk')
            ->JOIN('kelompokpasien_m as kp', 'kp.id', '=', 'pd.objectkelompokpasienlastfk')
            ->leftJoin('rekanan_m as rek', 'rek.id', '=', 'pd.objectrekananfk')
            ->leftJoin('strukorder_t as so', 'so.noregistrasifk', '=', 'pd.norec')
            ->leftJoin('strukresep_t as sr', 'sr.orderfk', '=', 'so.norec')
            ->JOIN('kelas_m as kl', 'kl.id', '=', 'pd.objectkelasfk')
            ->select(
                'ru.namaruangan',
                'pd.noregistrasi',
                'ps.nocm',
                'pd.statusbayar',
                'ps.namapasien',
                'jk.jeniskelamin',
                'kp.kelompokpasien',
                'kl.namakelas',
                DB::raw("CAST(pd.tglregistrasi as DATE)"),
                'pd.tglpulang',
                'ps.tgllahir',
                'sr.norec as norec_sr',
                'pd.nostruklastfk',
                'pd.norec as norec_pd',
            )
            ->where('pd.kdprofile', $this->kdProfile)
            ->where('pd.statusenabled', true)
            ->where('pd.norec', $request->norec_pd)
            ->first();

        if (!empty($dataOrder)) {
            $dataOrder->umur = $this->getAge($dataOrder->tgllahir, $dataOrder->tglregistrasi);
        }
        $returAfterPrice = StrukRetur::where('kdprofile', $this->kdProfile)->where('statusenabled', true)
            ->where('strukresepfk', $dataOrder->norec_sr)->orderby('created_at', 'desc')
            ->first();

        $datasOrder = DB::table('strukretur_t as sr')
            ->join('strukresep_t AS srep', 'srep.norec', 'sr.strukresepfk')
            ->join('pelayananpasienretur_t AS ppr', 'ppr.strukreturfk', 'sr.norec')
            ->join('produk_m AS pr', 'pr.id', 'ppr.produkfk')
            ->select(
                'srep.norec as norec_resp',
                'pr.namaproduk',
                'ppr.jumlah AS qtyRetur',
                'ppr.hargajual',
                'ppr.hargadiscount',
                'ppr.jasa',
            )
            ->where('sr.norec', $returAfterPrice->norec)
            ->get();
        $no = 0;
        $totalTagihan = 0;
        if (count($datasOrder) > 0) {
            $datasCountOrder = PelayananPasien::where('strukresepfk', $datasOrder[0]->norec_resp)->select('jumlah', 'hargajual', 'strukresepfk', 'hargadiscount')->get();
            foreach ($datasCountOrder as $data) {
                $totalTagihan =  $data->hargajual * (float) $data->jumlah + $totalTagihan - (float)$data->hargadiscount;
            }
        }

        $totalRetur = 0;
        foreach ($datasOrder as $data) {
            $totalRetur = $totalRetur + ((float)$data->hargajual * (float)$data->qtyRetur - (float)$data->hargadiscount + (float)$data->jasa);
        }

        $pageWidth = 950;

        $result = [
            'dataPasien' => $dataOrder,
            'returOrder' => $datasOrder,
            'hargaAwal' => $totalTagihan,
            'kembalianPasien' => $totalRetur,
            'hargaAkhir' => $totalTagihan - $totalRetur,
        ];

        // return $this->respond($profile);

        return view(
            'report.kasir.kwitansi-retur-obat-dibayar',
            compact('result', 'pageWidth', 'profile')
        );
    }
    // public function dropdownObat(Request $r)
    // {
    //     $dataKonversiProduk = DB::table('konversisatuan_t as ks')
    //         ->JOIN('satuanstandar_m as ss', 'ss.id', '=', 'ks.satuanstandar_asal')
    //         ->JOIN('satuanstandar_m as ss2', 'ss2.id', '=', 'ks.satuanstandar_tujuan')
    //         ->select(
    //             'ks.objekprodukfk',
    //             'ks.satuanstandar_asal',
    //             'ss.satuanstandar',
    //             'ks.satuanstandar_tujuan',
    //             'ss2.satuanstandar as satuanstandar2',
    //             'ks.nilaikonversi'
    //         )
    //         ->where('ks.kdprofile', $this->kdProfile)
    //         ->where('ks.statusenabled', true)
    //         ->get();

    //     $dataProduk = DB::table('produk_m as pr')
    //         ->JOIN('detailjenisproduk_m as djp', 'djp.id', '=', 'pr.objectdetailjenisprodukfk')
    //         ->JOIN('jenisproduk_m as jp', 'jp.id', '=', 'djp.objectjenisprodukfk')
    //         ->leftJOIN('satuanstandar_m as ss', 'ss.id', '=', 'pr.objectsatuanstandarfk')
    //         ->JOIN('stokprodukdetail_t as spd', 'spd.objectprodukfk', '=', 'pr.id')
    //         ->select('pr.id', 'pr.namaproduk', 'ss.id as ssid', 'ss.satuanstandar', 'pr.namaexternal' , 'pr.statusenabled','jp.id as jpid')
    //         ->where('pr.kdprofile', $this->kdProfile)
    //         ->where('pr.statusenabled', true)
    //         ->whereIn('jp.id', explode(',', $this->settingFix('kdJenisProdukObat')));

    //     if (isset($r['limit']) && $r['limit'] != '') {
    //         $dataProduk = $dataProduk->limit($r['limit']);
    //     }
    //     if (isset($r['ruanganfk']) && $r['ruanganfk'] != '') {
    //         $dataProduk = $dataProduk->where('spd.objectruanganfk', $r['ruanganfk']);
    //     }
    //     if (isset($r['namaproduk']) && $r['namaproduk'] != '') {
    //         $searchTerm = '%' . $r['namaproduk'] . '%';
    //         $dataProduk = $dataProduk->where(function ($query) use ($searchTerm) {
    //             $query->where('pr.namaproduk', 'ilike', $searchTerm)
    //                   ->orWhere('pr.namaexternal', 'ilike', $searchTerm)  ;
    //         });

    //     }
    //     //->where('spd.qtyproduk','>',0)
    //     $dataProduk = $dataProduk->groupBy('pr.id', 'pr.namaproduk', 'ss.id', 'ss.satuanstandar','jp.id');
    //     $dataProduk = $dataProduk->orderBy('pr.namaproduk');
    //     $dataProduk = $dataProduk->get();

    //     $dataProdukResult = [];
    //     foreach ($dataProduk as $item) {
    //         $satuanKonversi = [];
    //         foreach ($dataKonversiProduk  as $item2) {
    //             if ($item->id == $item2->objekprodukfk) {
    //                 $satuanKonversi[] = array(
    //                     // 'id' => $item->id,
    //                     'ssid' =>   $item2->satuanstandar_tujuan,
    //                     'satuanstandar' =>   $item2->satuanstandar2,
    //                     'nilaikonversi' =>   $item2->nilaikonversi,
    //                 );
    //             }
    //         }


    //             $dataProdukResult[] = array(
    //                     'id' =>   $item->id,
    //                     'namaproduk' => $item->namaproduk,
    //                     'generik' =>   $item->namaexternal,
    //                     'ssid' =>   $item->ssid,
    //                     'satuanstandar' =>   $item->satuanstandar,
    //                     'konversisatuan' => $satuanKonversi,
    //                     'statusenabled' =>$item->statusenabled
    //             );

    //     }
    //     return $this->respond($dataProdukResult);
    // }

    public function dropdownObat(Request $r)
    {
        $idHibah = $this->settingFix('objectasalprodukHibah');
        $isHibah = '';

        $dataKonversiProduk = DB::table('konversisatuan_t as ks')
            ->JOIN('satuanstandar_m as ss', 'ss.id', '=', 'ks.satuanstandar_asal')
            ->JOIN('satuanstandar_m as ss2', 'ss2.id', '=', 'ks.satuanstandar_tujuan')
            ->select(
                'ks.objekprodukfk',
                'ks.satuanstandar_asal',
                'ss.satuanstandar',
                'ks.satuanstandar_tujuan',
                'ss2.satuanstandar as satuanstandar2',
                'ks.nilaikonversi'
            )
            ->where('ks.kdprofile', $this->kdProfile)
            ->where('ks.statusenabled', true)
            ->get();

        $dataProduk = DB::table('produk_m as pr')
            ->JOIN('detailjenisproduk_m as djp', 'djp.id', '=', 'pr.objectdetailjenisprodukfk')
            ->JOIN('jenisproduk_m as jp', 'jp.id', '=', 'djp.objectjenisprodukfk')
            ->leftJOIN('satuanstandar_m as ss', 'ss.id', '=', 'pr.objectsatuanstandarfk')
            ->JOIN('stokprodukdetail_t as spd', 'spd.objectprodukfk', '=', 'pr.id')
            ->select('pr.id', 'pr.namaproduk', 'pr.namaproduk as productname', 'ss.id as ssid', 'ss.satuanstandar', 'pr.namaexternal', 'pr.statusenabled', 'pr.kdproduk', 'spd.objectasalprodukfk')
            ->where('pr.kdprofile', $this->kdProfile)
            ->where('pr.statusenabled', true)
            ->where('spd.statusenabled', true)
            ->whereIn('jp.id', explode(',', $this->settingFix('kdJenisProdukObat')));

        if (isset($r['limit']) && $r['limit'] != '') {
            $dataProduk = $dataProduk->limit($r['limit']);
        }
        if (isset($r['ruanganfk']) && $r['ruanganfk'] != '') {
            $dataProduk = $dataProduk->where('spd.objectruanganfk', $r['ruanganfk']);
        }
        if (isset($r['namaproduk']) && $r['namaproduk'] != '') {
            $searchTerm = '%' . $r['namaproduk'] . '%';
            $dataProduk = $dataProduk->where(function ($query) use ($searchTerm) {
                $query->where('pr.namaproduk', 'ilike', $searchTerm)
                    ->orWhere('pr.namaexternal', 'ilike', $searchTerm)
                    ->orWhere('pr.id', 'ilike', $searchTerm)
                    ->orWhere('pr.kdproduk', 'ilike', $searchTerm);
            });
        }
        // if (isset($r['isdonasi'])) {
        //     $dataProduk = $dataProduk->where('spd.objectasalprodukfk', $idHibah);
        //     $isHibah = 'and objectasalprodukfk = ' . $idHibah;
        // } else {
        //     $dataProduk = $dataProduk->where('spd.objectasalprodukfk', '!=', $idHibah);
        //     $isHibah = 'and objectasalprodukfk <> ' . $idHibah;
        // }
        // ->where('spd.qtyproduk','>',0)
        $dataProduk = $dataProduk->groupBy('pr.id', 'pr.namaproduk', 'ss.id', 'ss.satuanstandar', 'pr.kdproduk', 'spd.objectasalprodukfk');
        $dataProduk = $dataProduk->orderBy('pr.namaproduk');
        $dataProduk = $dataProduk->get();

        $dataProdukResult = [];

        foreach ($dataProduk as $item) {
            $satuanKonversi = [];
            foreach ($dataKonversiProduk  as $item2) {
                if ($item->id == $item2->objekprodukfk) {
                    $stok = DB::select(
                        DB::raw("
                        select sum(qtyproduk) as stok
                        from stokprodukdetail_t
                        where kdprofile = $this->kdProfile and objectprodukfk =:produkId
                        and statusenabled = 't'
                        $isHibah
                        and objectruanganfk =:ruanganid"),
                        array(
                            'produkId' => $item->id,
                            'ruanganid' => $r['ruanganfk'],
                        )
                    );
                    $satuanKonversi[] = array(
                        // 'id' => $item->id,
                        'stok' => count($stok) > 0 ? $stok[0]->stok : 0,
                        'ssid' =>   $item2->satuanstandar_tujuan,
                        'satuanstandar' =>   $item2->satuanstandar2,
                        'nilaikonversi' =>   $item2->nilaikonversi,
                    );
                }
            }

            $stok = DB::select(
                DB::raw("
                select sum(qtyproduk) as stok, objectasalprodukfk
                from stokprodukdetail_t
                where kdprofile = $this->kdProfile
                and objectprodukfk =:produkId
                and statusenabled = 't'
                $isHibah
                and objectruanganfk =:ruanganid
                group by objectasalprodukfk"),
                array(
                    'produkId' => $item->id,
                    'ruanganid' => $r['ruanganfk'],
                )
            );

            $dataProdukResult[] = array(
                'id' =>   $item->id,
                'asalproduk' => $item->objectasalprodukfk,
                'productname' => $item->productname,
                'namaproduk' => $item->namaproduk,
                'kdproduk' => $item->kdproduk,
                'kode_namaproduk' => $item->kdproduk . ' - ' . $item->namaproduk,
                'stok' =>  count($stok) > 0 ? $stok[0]->stok : 0,
                'generik' =>   $item->namaexternal,
                'ssid' =>   $item->ssid,
                'satuanstandar' =>   $item->satuanstandar,
                'konversisatuan' => $satuanKonversi,
                'statusenabled' => $item->statusenabled,
                // 'stok' => $item->qtyproduk
            );
        }

        // dd($dataProdukResult);
        return $this->respond($dataProdukResult);
    }

    public function getStokProduk(Request $r)
    {

        $idHibah = $this->settingFix('objectasalprodukHibah');

        $data = DB::table('stokprodukdetail_t as spd')
            ->join('ruangan_m as ru', 'ru.id', 'spd.objectruanganfk')
            ->join('produk_m as pr', 'pr.id', 'spd.objectprodukfk')
            ->select('pr.id as produkfk', 'pr.namaproduk', 'ru.namaruangan', DB::raw("sum(spd.qtyproduk) as stok"), 'pr.kdproduk')
            ->where('pr.id', $r['produkfk'])
            ->where('spd.statusenabled', true)
            ->where('spd.qtyproduk', '>', 0)
            ->where('spd.kdprofile', $this->kdProfile);
        if (isset($r['isdonasi'])) {
            $data = $data->where('spd.objectasalprodukfk', $idHibah);
        } else {
            $data = $data->where('spd.objectasalprodukfk', '!=', $idHibah);
        }

        $data = $data->groupby('produkfk', 'pr.namaproduk', 'ru.namaruangan', 'pr.kdproduk');
        $data = $data->get();

        return $this->respond($data);
    }

    public function simpanResepKronis(Request $request)
    {

        DB::beginTransaction();

        try {
            $kdProfile = $this->kdProfile;
            $r_SR = $request['strukresep'];
            $dataPPOK = PelayananPasienObatKronis::where('strukresepfk', $request['norecresep'])->first();

            foreach ($request['pelayananpasienobatkronis'] as $item) {
                if ($dataPPOK == null) {
                    $newPP = new PelayananPasienObatKronis();
                    $norecPPOK = $newPP->generateNewId();
                    $newPP->norec = $norecPPOK;
                    $newPP->kdprofile = $kdProfile;
                    $newPP->statusenabled = true;
                    $qtyJumlah = $item['jumlah'];
                    $newPP->noregistrasifk = $item['noregistrasifk'];
                    $newPP->aturanpakai = $item['aturanpakai'];
                    if (isset($item['generik'])) {
                        $newPP->generik = $item['generik'];
                    }
                    $newPP->hargadiscount = $item['hargadiscount'];
                    $newPP->hargajual = $item['hargajual'];
                    $newPP->hargasatuan = $item['hargasatuan'];
                    $newPP->jenisobatfk = $item['jenisobatfk'];
                    $newPP->jumlah = $qtyJumlah;
                    $newPP->kelasfk = $item['kelasfk'];
                    $newPP->kdkelompoktransaksi = 1;
                    $newPP->produkfk = $item['produkfk'];
                    if (isset($item['routefk'])) {
                        $newPP->routefk = $item['routefk'];
                    }
                    $newPP->stock = $item['stock'];
                    $newPP->tglpelayanan = $r_SR['tglresep'];
                    $newPP->harganetto = $item['harganetto'];
                    $newPP->jeniskemasanfk = $item['jeniskemasanfk'];
                    $newPP->rke = $item['rke'];
                    $newPP->strukresepfk = $request['norecresep'];
                    $newPP->satuanviewfk = $item['satuanviewfk'];
                    $newPP->nilaikonversi = $item['nilaikonversi'];
                    $newPP->strukterimafk = $item['nostrukterimafk'];
                    $newPP->dosis = $item['dosis'];
                    $newPP->jasa = $item['jasa'];
                    $newPP->isobat = 1;
                    $newPP->petugas =  $this->getNamaPegawai();
                    $newPP->noregistrasi = $request['noregistrasi'];
                    $newPP->save();
                } else {
                    $delPP = PelayananPasienObatKronis::where('strukresepfk', $request['norecresep'])->delete();
                    $newPP = new PelayananPasienObatKronis();
                    $norecPPOK = $newPP->generateNewId();
                    $newPP->norec = $norecPPOK;
                    $newPP->kdprofile = $kdProfile;
                    $newPP->statusenabled = true;
                    $qtyJumlah = $item['jumlah'];
                    $newPP->noregistrasifk = $item['noregistrasifk'];
                    $newPP->aturanpakai = $item['aturanpakai'];
                    if (isset($item['generik'])) {
                        $newPP->generik = $item['generik'];
                    }
                    $newPP->hargadiscount = $item['hargadiscount'];
                    $newPP->hargajual = $item['hargajual'];
                    $newPP->hargasatuan = $item['hargasatuan'];
                    $newPP->jenisobatfk = $item['jenisobatfk'];
                    $newPP->jumlah = $qtyJumlah;
                    $newPP->kelasfk = $item['kelasfk'];
                    $newPP->kdkelompoktransaksi = 1;
                    $newPP->produkfk = $item['produkfk'];
                    if (isset($item['routefk'])) {
                        $newPP->routefk = $item['routefk'];
                    }
                    $newPP->stock = $item['stock'];
                    $newPP->tglpelayanan = $r_SR['tglresep'];
                    $newPP->harganetto = $item['harganetto'];
                    $newPP->jeniskemasanfk = $item['jeniskemasanfk'];
                    $newPP->rke = $item['rke'];
                    $newPP->strukresepfk = $request['norecresep'];
                    $newPP->satuanviewfk = $item['satuanviewfk'];
                    $newPP->nilaikonversi = $item['nilaikonversi'];
                    $newPP->strukterimafk = $item['nostrukterimafk'];
                    $newPP->dosis = $item['dosis'];
                    $newPP->jasa = $item['jasa'];
                    $newPP->isobat = 1;
                    $newPP->petugas =  $this->getNamaPegawai();
                    $newPP->noregistrasi = $request['noregistrasi'];
                    $newPP->save();
                }

                $ruanganfk = $request['strukresep']['ruanganfk'];


                $jmlPengurang = (float)$qtyJumlah;
                $kurangStok = (float)0;
                $dataSaldoAwal = collect(DB::select("select sum(qtyproduk) as qty from stokprodukdetail_t
                            where kdprofile = $kdProfile
                            and statusenabled = true
                            and objectruanganfk=$ruanganfk
                            and objectprodukfk=$item[produkfk]"))
                    ->first();

                $saldoAwalIn = (float)$dataSaldoAwal->qty - $jmlPengurang;


                $newSPD = StokProdukDetail::where('norec', $item['norec_spd'])
                    ->where('kdprofile', $kdProfile)
                    ->where('statusenabled', true)
                    ->first();

                if ((float)$newSPD->qtyproduk <= (float)$jmlPengurang) {
                    $kurangStok = (float)$newSPD->qtyproduk;
                    $jmlPengurang = (float)$jmlPengurang - (float)$kurangStok;
                } else {
                    $kurangStok = (float)$jmlPengurang;
                    $jmlPengurang = (float)$jmlPengurang - (float)$kurangStok;
                }

                DB::table('stokprodukdetail_t')
                    ->where('kdprofile', $kdProfile)
                    ->where('norec', $newSPD->norec)
                    ->lockForUpdate()
                    ->decrement('qtyproduk', (float)$kurangStok);


                $this->kartu_STOK(array(
                    "saldoawal" => $dataSaldoAwal->qty,
                    "qtyin" => 0,
                    "qtyout" => (float) $qtyJumlah,
                    "saldoakhir" => $saldoAwalIn,
                    "keterangan" =>
                    'Pelayanan Obat Kronis No. '  . $request['noresep']
                        . '. Pada produk ' . $item['namaproduk']
                        . '. Atas pasien ' .
                        $request['strukresep']['nocm']
                        . ' ' .
                        $request['strukresep']['namapasien'],

                    "produkfk" => $item['produkfk'],
                    "ruanganfk" => $item['ruanganfk'],
                    "tglinput" => date('Y-m-d H:i:s'),
                    "tglkejadian" => date('Y-m-d H:i:s'),
                    "nostrukterimafk" => $item['nostrukterimafk'],
                    "norectransaksi" => $norecPPOK,
                    "tabletransaksi" => 'pelayananpasienobatkronis_t',
                    "stokprodukdetailfk" => $newSPD->norec,
                    "flagfk" => null,
                ));
            }

            $transStatus = 'true';
        } catch (Exception $e) {
            $transStatus = 'false';
        }

        if ($transStatus == 'true') {
            $transMessage = "Simpan Obat Kronis";
            DB::commit();
            $result = array(
                "status" => 200,
                "result" => array(
                    "noresep"  => $dataPPOK,
                    "as" => '@epic',
                ),
            );
        } else {
            $transMessage = "Simpan Obat Kronis Gagal";
            DB::rollBack();
            $result = array(
                "status" => 400,
                "result"  => $e->getMessage() . '' . $e->getLine(),
            );
        }
        return $this->respond($result['result'], $result['status'], $transMessage);
    }

    public function chekPeriodeObat(Request $request)
    {
        // $startDate = Carbon::now()->subDays(30);
        $dateBetween = [date("Y-m-d", strtotime("-1 month")), date("Y-m-d")];
        $data = DB::table('pelayananpasien_t as pp')
            ->join('antrianpasiendiperiksa_t as apd', 'apd.norec', 'pp.noregistrasifk')
            ->join('pasiendaftar_t as pd', 'pd.norec', 'apd.noregistrasifk')
            ->join('produk_m as pr', 'pr.id', 'pp.produkfk')
            ->join('pasien_m as ps', 'ps.id', 'pd.nocmfk')
            ->select('ps.namapasien', 'pp.tglpelayanan', 'pr.namaproduk', 'ps.nocm')
            ->where('pp.produkfk', $request['produkfk'])
            ->where('pd.nocmfk', $request['nocmfk'])
            ->whereBetween(DB::raw("CAST(pp.tglpelayanan AS DATE)"), $dateBetween)
            ->orderByDesc('pp.tglpelayanan')
            ->first();

        $sumProduk = DB::table('pelayananpasien_t as pp')
            ->join('antrianpasiendiperiksa_t as apd', 'apd.norec', 'pp.noregistrasifk')
            ->join('pasiendaftar_t as pd', 'pd.norec', 'apd.noregistrasifk')
            ->join('produk_m as pr', 'pr.id', 'pp.produkfk')
            ->join('pasien_m as ps', 'ps.id', 'pd.nocmfk')
            ->select(
                'ps.namapasien',
                'pr.namaproduk',
                'ps.nocm',
                'pp.produkfk',
                'pp.iskronis',
                DB::raw('SUM(pp.jumlah) AS total_jumlah')
            )
            // ->where('pp.produkfk',$request['produkfk'])
            ->where('pd.nocmfk', $request['nocmfk'])
            ->where('pp.produkfk', $request['produkfk'])
            ->whereBetween(DB::raw("CAST(pp.tglpelayanan AS DATE)"), $dateBetween)
            ->groupBy(
                'ps.namapasien',
                'pr.namaproduk',
                'ps.nocm',
                'pp.produkfk',
                'pp.iskronis'
            )
            ->first();
        $res['data'] = $data;
        $res['sumProduk'] = $sumProduk;


        return $this->respond($res);;
    }
    public function getComboRuang(Request $request)
    {

        $res['ruanganFarmasi'] = Ruangan::where('kdprofile', $this->kdProfile)
            ->where('statusenabled', true)
            ->whereIn('objectdepartemenfk', [$this->settingFix('idInstalasiFarmasi')])
            ->get();

        return $this->respond($res);
    }

    public function getSkriningFarmasi(Request $request)
    {

        $data = SkriningFarmasi::where('strukresepfk', $request['strukresepfk'])->first();

        return $this->respond($data);
    }

    public function saveSkriningFarmasi(Request $request)
    {

        DB::beginTransaction();
        try {

            if ($request['norec'] == '') {
                $dataPP = new SkriningFarmasi();
                $dataPP->kdprofile = $this->kdProfile;
                $dataPP->statusenabled = true;
                $dataPP->norec = $dataPP->generateNewId();
            } else {
                $dataPP =  SkriningFarmasi::where('norec', $request['norec'])->first();
            }
            $dataPP->norec_apd = $request['norec_apd'];
            $dataPP->objectruanganfk = $request['objectruanganfk'];
            $dataPP->rpenulis = $request['rpenulis'];
            $dataPP->rtanggalresep = $request['rtanggalresep'];
            $dataPP->rmr = $request['rmr'];
            $dataPP->rpasien = $request['rpasien'];
            $dataPP->rtanggallahir = $request['rtanggallahir'];
            $dataPP->rberatbedan = $request['rberatbedan'];
            $dataPP->rdokter = $request['rdokter'];
            $dataPP->rruang = $request['rruang'];
            $dataPP->rstatusjamin = $request['rstatusjamin'];
            $dataPP->robat = $request['robat'];
            $dataPP->rkekuatan = $request['rkekuatan'];
            $dataPP->rjumlahobat = $request['rjumlahobat'];
            $dataPP->rstabilitas = $request['rstabilitas'];
            $dataPP->raturan = $request['raturan'];
            $dataPP->rindikasiobat = $request['rindikasiobat'];
            $dataPP->ralergi = $request['ralergi'];
            $dataPP->rkonsumsi = $request['rkonsumsi'];
            $dataPP->rduplikat = $request['rduplikat'];
            $dataPP->rinteraksi = $request['rinteraksi'];
            $dataPP->rantibiotik = $request['rantibiotik'];
            $dataPP->rpolifarmasi = $request['rpolifarmasi'];
            $dataPP->namapenyekriningresep = $request['namapenyekriningresep'];
            $dataPP->namaperacik = $request['namaperacik'];
            $dataPP->namapengecek = $request['namapengecek'];
            $dataPP->namapenyrahobat = $request['namapenyrahobat'];
            $dataPP->namapenerimaobat = $request['namapenerimaobat'];
            $dataPP->prinsipbesar = implode(',', $request['prinsipbesar']);
            $dataPP->ketpenulis = $request['ketpenulis'];
            $dataPP->kettanggal = $request['kettanggal'];
            $dataPP->ketrm = $request['ketrm'];
            $dataPP->ketpasien = $request['ketpasien'];
            $dataPP->kettanggallahir = $request['kettanggallahir'];
            $dataPP->ketberat = $request['ketberat'];
            $dataPP->ketdokter = $request['ketdokter'];
            $dataPP->ketruang = $request['ketruang'];
            $dataPP->ketstatus = $request['ketstatus'];
            $dataPP->ketobat = $request['ketobat'];
            $dataPP->ketkekuatan = $request['ketkekuatan'];
            $dataPP->ketjumlah = $request['ketjumlah'];
            $dataPP->ketstabilitas = $request['ketstabilitas'];
            $dataPP->ketaturan = $request['ketaturan'];
            $dataPP->ketalergi = $request['ketalergi'];
            $dataPP->ketkonsumsi = $request['ketkonsumsi'];
            $dataPP->ketduplikasi = $request['ketduplikasi'];
            $dataPP->ketinteraski = $request['ketinteraski'];
            $dataPP->ketantibiotik = $request['ketantibiotik'];
            $dataPP->ketpolifarmasi = $request['ketpolifarmasi'];
            $dataPP->tglinput = date('Y-m-d H:i:s');
            $dataPP->ketindikasi = $request['ketindikasi'];
            $dataPP->rcek = $request['rcek'];
            $dataPP->strukresepfk = $request['strukresepfk'];
            $dataPP->save();

            DB::commit();
            $result = array(
                'status' => 201,
                'message' => "Simpan Data Berhasil",
                'as' => 'ea@epic',
            );
        } catch (Exception $e) {
            DB::rollBack();
            $result = array(
                'status' => 400,
                'data' => $e->getMessage(),
                'message'  => "Somthing Want Wrong",
                'as' => 'ea@epic',
            );
        }
        return $this->respond($result, $result['status'], $result['message']);
    }


    public function getDetailResepRetur(Request $request)
    {
        $idProfile = $this->kdProfile;
        $dataAsalProduk = AsalProduk::mine()->get();

        $data = DB::table('strukresep_t as sr')
            ->JOIN('pegawai_m as pg', 'pg.id', '=', 'sr.penulisresepfk')
            ->JOIN('ruangan_m as ru2', 'ru2.id', '=', 'sr.ruanganfk')
            ->JOIN('pelayananpasien_t as pp', 'pp.strukresepfk', '=', 'sr.norec')
            ->JOIN('antrianpasiendiperiksa_t as apd', 'apd.norec', '=', 'pp.noregistrasifk')
            ->join('pasiendaftar_t as pd', 'apd.noregistrasifk', '=', 'pd.norec')
            ->JOIN('ruangan_m as ru', 'ru.id', '=', 'apd.objectruanganfk')
            ->JOIN('jeniskemasan_m as jk', 'jk.id', '=', 'pp.jeniskemasanfk')
            ->LeftJOIN('routefarmasi as rt', 'rt.id', '=', 'pp.routefk')
            ->JOIN('produk_m as pr', 'pr.id', '=', 'pp.produkfk')
            ->JOIN('satuanstandar_m as ss', 'ss.id', '=', 'pr.objectsatuanstandarfk')
            ->JOIN('satuanstandar_m as ss2', 'ss2.id', '=', 'pp.satuanviewfk')
            ->LeftJOIN('satuanresep_m as sn', 'sn.id', '=', 'pp.satuanresepfk')
            ->JOIN('stokprodukdetail_t as spd', 'spd.norec', '=', 'pp.stokprodukdetailfk')
            ->select(
                'pg.id as pgid',
                'pg.namalengkap',
                'ru2.id as ruidresep',
                'ru2.namaruangan as ruanganresep',
                'sr.tglresep',
                'sr.noresep',
                'pp.hargasatuan',
                'pp.tglregistrasi',
                'pp.tglpemakaian',
                'pp.strukresepfk as norec_resep',
                'pp.isbud',
                'pp.stock',
                'apd.objectruanganfk',
                'ru.namaruangan',
                'pp.rke',
                'pp.jeniskemasanfk',
                'jk.id as jkid',
                'jk.jeniskemasan',
                'pp.aturanpakai',
                'pp.routefk',
                'pp.noregistrasifk',
                'rt.name as route',
                'pp.produkfk',
                'pr.kdproduk',
                'pr.namaproduk',
                'pr.namaproduk as productname',
                'pp.nilaikonversi',
                'pr.objectsatuanstandarfk',
                'ss.satuanstandar',
                'pp.satuanviewfk',
                'ss2.satuanstandar as ssview',
                'pp.jumlah',
                'pp.hargadiscount',
                'pp.dosis',
                'pp.jenisracikanfk',
                'pp.jasa',
                'pp.racikan',
                'pp.hargajual',
                'pp.hargasatuan',
                'pp.strukterimafk',
                'pp.qtydetailresep',
                'pp.ispagi',
                'pp.issiang',
                'pp.ismalam',
                'pp.issore',
                'pr.kekuatan',
                'pp.keteranganpakai',
                'pp.iskronis',
                'pp.isdonasi',
                'pp.satuanresepfk',
                'sn.satuanresep',
                'pp.tglkadaluarsa',
                'pp.norec as norecpp',
                'pp.jenisobatfk',
                'apd.objectkelasfk as kelasfk',
                'pp.persendiscount',
                'pp.stokprodukdetailfk as norec_spd',
                'pp.strukterimafk',
                'spd.objectasalprodukfk',
                'spd.qtyproduk as jmlstok'
            )
            ->where('sr.kdprofile', $idProfile)
            ->where('apd.statusenabled', true)
            ->where('pd.statusenabled', true)
            ->where('pp.statusenabled', true)
            ->where('pp.jumlah', '>', 0)
            ->where('pd.norec', $request['norec_pd']);

            if (isset($request['search']) && $request['search'] != '') {
                $searchTerm = '%' . trim($request['search']) . '%';
                $data = $data->where(function ($query) use ($searchTerm) {
                    $query->where('pr.namaproduk', 'ilike', $searchTerm)
                        ->orWhere('pr.kdproduk', 'ilike', $searchTerm);
                });
            };

        $data = $data->get();

        $asalprodukfk = 0;
        $asalproduk = '';
        $hargadiscount = 0;
        $total = 0;
        $totalbayar = 0;
        $aturanpakaifk = null;
        $aturanpakai = null;


        // INI CODE BUAT METODE HEAD AND BODY (JIKA DIPERLUKAN)

        // $headAPDArray=[];

        // foreach ($data as $i => $item) {
        //     $asalprodukfk = $item->objectasalprodukfk;

        //     $total = (((float)$item->jumlah * ((float) $item->hargasatuan - (float) $item->hargadiscount)));

        //     $asalproduk = null;
        //     foreach ($dataAsalProduk as $item3) {
        //         if ($asalprodukfk == $item3->id) {
        //             $asalproduk = $item3->asalproduk;
        //         }
        //     }

        //     $jmlxMakan = (((float)$item->jumlah / (float)$item->nilaikonversi) / (float)$item->dosis) * (float)$item->kekuatan;



        //     // Cari apakah sudah ada data dengan `noregistrasifk` ini di $headAPDArray
        //     $existingHeaderIndex = null;
        //     foreach ($headAPDArray as $index => $header) {
        //         if ($header['apd_norec'] === $item->noregistrasifk) {
        //             $existingHeaderIndex = $index;
        //             break;
        //         }
        //     }

        //     // Jika belum ada, tambahkan header baru
        //     if ($existingHeaderIndex === null) {
        //         $headAPDArray[] = [
        //             'apd_norec' => $item->noregistrasifk,
        //             'pgid' => $item->pgid,
        //             'namalengkap' => $item->namalengkap,
        //             'id' => $item->ruidresep,
        //             'namaruangan' => $item->ruanganresep,
        //             'tglresep' => $item->tglresep,
        //             'noresep' => $item->noresep,
        //             'data' => [], // Inisialisasi body kosong
        //         ];
        //         $existingHeaderIndex = array_key_last($headAPDArray); // Dapatkan indeks header yang baru dibuat
        //     }

        //     // Tambahkan data ke body yang sesuai
        //     $headAPDArray[$existingHeaderIndex]['data'][] = [
        //         'no' => $i + 1,
        //         'generik' => null,
        //         'stock' => $item->jmlstok,
        //         'hargajual' => $item->hargajual,
        //         'harganetto' => $item->hargasatuan,
        //         'hargasatuan' => $item->hargasatuan,
        //         'hargadiscount' => $item->hargadiscount,
        //         'nostrukterimafk' => $item->strukterimafk,
        //         'ruanganfk' => $item->ruidresep,
        //         'aturanpakai' => $item->aturanpakai,
        //         'isbud' => $item->isbud,
        //         'tglpemakaian' => $item->tglpemakaian,
        //         'route' => $item->route,
        //         'asalprodukfk' => $asalprodukfk,
        //         'asalproduk' => $asalproduk,
        //         'satuanstandarfk' => $item->satuanviewfk,
        //         'satuanstandar' => $item->ssview,
        //         'satuanview' => $item->ssview,
        //         'kode_namaproduk' => $item->kdproduk . ' - ' . $item->namaproduk,
        //         'jmlstok' => $item->jmlstok,
        //         'jumlah' => $item->jumlah / $item->nilaikonversi,
        //         'jumlahobat' => $item->qtydetailresep,
        //         'total' => $item->isdonasi === true ? 0 : ($total + $item->jasa),
        //         'jmldosis' => (string)$jmlxMakan . '/' . (string)$item->dosis . '/' . $item->kekuatan,
        //     ];
        //     $dataStruk['pgid'] = $item->pgid;
        //     $dataStruk['namalengkap'] = $item->namalengkap;
        //     $dataStruk['id'] = $item->ruidresep;
        //     $dataStruk['namaruangan'] = $item->ruanganresep;
        //     $dataStruk['tglresep'] = $item->tglresep;
        //     $dataStruk['noresep'] = $item->noresep;
        // }



        foreach ($data as $i => $item) {
            // return $item->aturanpakai;
            $asalprodukfk = $item->objectasalprodukfk;

            $total = (((float)$item->jumlah * ((float) $item->hargasatuan - (float) $item->hargadiscount)));

            foreach ($dataAsalProduk as $item3) {
                if ($asalprodukfk == $item3->id) {
                    $asalproduk = $item3->asalproduk;
                }
            }
            $jmlxMakan = (((float)$item->jumlah / (float)$item->nilaikonversi) / (float)$item->dosis) * (float)$item->kekuatan;

            $item->no =  $i + 1;
            $item->generik = null;
            $item->stock = $item->jmlstok;
            $item->hargajual = $item->hargajual;
            $item->harganetto = $item->hargasatuan;
            $item->hargasatuan =  $item->hargasatuan;
            $item->hargadiscount = $hargadiscount;
            $item->nostrukterimafk =  $item->strukterimafk;
            $item->ruanganfk =  $item->ruidresep;
            // $item->aturanpakaifk = $aturanpakaifk;
            $item->aturanpakai = $item->aturanpakai;
            $item->isbud = $item->isbud;
            $item->tglpemakaian = $item->tglpemakaian;
            $item->route = $item->route;
            $item->asalprodukfk = $asalprodukfk;
            $item->asalproduk = $asalproduk;
            $item->satuanstandarfk = $item->satuanviewfk;
            $item->satuanstandar = $item->ssview;
            $item->satuanview = $item->ssview;
            $item->kode_namaproduk = $item->kdproduk . ' - ' . $item->namaproduk;
            $item->jmlstok = $item->stock;
            $item->jumlah = $item->jumlah / $item->nilaikonversi;
            $item->jumlahobat = $item->qtydetailresep;
            $item->jmlretur = null;
            if ($item->isdonasi === true) {
                $item->total = 0;
            } else {
                $item->total = $total + $item->jasa;
            }
            $item->total = $total + $item->jasa;
            $item->jmldosis = (string)$jmlxMakan . '/' . (string)$item->dosis . '/' . $item->kekuatan;
            $item->keterangan = $item->keteranganpakai;

            $dataStruk['pgid'] = $item->pgid;
            $dataStruk['namalengkap'] = $item->namalengkap;
            $dataStruk['id'] = $item->ruidresep;
            $dataStruk['namaruangan'] = $item->ruanganresep;
            $dataStruk['tglresep'] = $item->tglresep;
            $dataStruk['noresep'] = $item->noresep;
        }

        $message = 'Sukses load data';
        $color = 'succses';
        if (empty($dataStruk)) {
            $message = 'Data tidak ditemukan';
            $color = 'error';
        };


        $result = array(
            'detailresep' => !empty($dataStruk) ? $dataStruk : null,
            'data' => !empty($data) ? $data : [],
            'message' => 'as@epic',
            'pesan' => $message,
            'color' => $color
        );
        // $result['data']=!empty($data) ? $data : [];

        return $this->respond($result);
    }

    public function SimpanReturPelayananObatRanap(Request $request)
    {
        $r_SR = $request['strukresep'];
        $r_id_resep = $request['norec_resep'];
        $norec_SR = isset($r_SR['norecResep']) ? $r_SR['norecResep']  : null;
        $r_PP = $request['pelayananpasien'][0];
        $valueCompareSetIdRetur = [];

        // var_dump($r_id_resep);

        DB::beginTransaction();
        try {

            foreach ($r_id_resep as $key => $value) {

                $newSRetur = new StrukRetur();
                $norecSRetur = $newSRetur->generateNewId();
                $noRetur = $this->generateCode(new StrukRetur, 'noretur', 12, 'Ret/' . $this->getDateTime()->format('ym') . '/', $this->kdProfile);
                $newSRetur->norec = $norecSRetur;
                $newSRetur->kdprofile = $this->kdProfile;
                $newSRetur->statusenabled = true;
                $newSRetur->objectkelompoktransaksifk = $this->kelompokTransaksi('RETUR RUANGAN');
                $newSRetur->keteranganalasan = isset($r_SR['alasan']) ? $r_SR['alasan'] : null;
                $newSRetur->keteranganlainnya = 'RETUR OBAT ALKES';
                $newSRetur->noretur = $noRetur;
                $newSRetur->objectruanganfk = $r_SR['ruanganfk'];
                $newSRetur->objectpegawaifk = $this->getPegawaiId();
                $newSRetur->tglretur = $this->getDateTime()->format('Y-m-d H:i:s');
                // $newSRetur->strukresepfk = $norec_SR;        //DEFAULT
                $newSRetur->strukresepfk = $value['norec'];
                $newSRetur->totalretur = $r_SR['totalretur'];
                $newSRetur->jumlahitem = $r_SR['jumlahitem'];

                $newSRetur->save();
                // $valueCompareSetIdRetur[$key]=$newSRetur->norec;
                // array_push($valueCompareSetIdRetur,$newSRetur->norec);
                $valueCompareSetIdRetur[$value['norec']] = $newSRetur->norec;
            }
            // var_dump($valueCompareSetIdRetur);
            // return $valueCompareSetIdRetur;


            // $result = [];
            foreach ($r_PP as $r_PPLXXXX) {
                if (isset($r_PPLXXXX['jmlretur'])) {
                    $newPPR = new PelayananPasienRetur();
                    $norecPPR = $newPPR->generateNewId();
                    $newPPR->norec = $norecPPR;
                    $newPPR->kdprofile = $this->kdProfile;
                    $newPPR->statusenabled = true;
                    $newPPR->noregistrasifk = $r_PPLXXXX['noregistrasifk'];
                    $newPPR->tglregistrasi = $r_PPLXXXX['tglregistrasi'];
                    $newPPR->aturanpakai = $r_PPLXXXX['aturanpakai'];
                    $newPPR->generik = $r_PPLXXXX['generik'];
                    $newPPR->hargadiscount = $r_PPLXXXX['hargadiscount'];
                    $newPPR->hargajual = $r_PPLXXXX['hargajual'];
                    $newPPR->hargasatuan = $r_PPLXXXX['hargasatuan'];
                    $newPPR->jenisobatfk = $r_PPLXXXX['jenisobatfk'];
                    $newPPR->jumlah = $r_PPLXXXX['jmlretur'];
                    $newPPR->kelasfk = $r_PPLXXXX['kelasfk'];
                    $newPPR->kdkelompoktransaksi = 1;
                    $newPPR->produkfk = $r_PPLXXXX['produkfk'];
                    if (isset($r_PPL['routefk'])) {
                        $newPPR->routefk = $r_PPLXXXX['routefk'];
                    }
                    $newPPR->stock = $r_PPLXXXX['stock'];
                    $newPPR->tglpelayanan = $r_SR['tglresep'];
                    $newPPR->harganetto = $r_PPLXXXX['harganetto'];
                    $newPPR->jeniskemasanfk = $r_PPLXXXX['jeniskemasanfk'];
                    $newPPR->rke = $r_PPLXXXX['rke'];
                    $newPPR->strukresepfk = $r_PPLXXXX['norec_resep'];
                    $newPPR->satuanviewfk = $r_PPLXXXX['satuanviewfk'];
                    $newPPR->nilaikonversi = $r_PPLXXXX['nilaikonversi'];
                    $newPPR->strukterimafk = $r_PPLXXXX['nostrukterimafk'];
                    $newPPR->dosis = $r_PPLXXXX['dosis'];
                    if ((int)$r_PPLXXXX['jumlah'] == 0) {
                        $newPPR->jasa = $r_PPLXXXX['jasa'];
                    } else {
                        $newPPR->jasa = 0;
                    }
                    // $valueCompareSetIdRetur
                    foreach ($valueCompareSetIdRetur as $norec_struck => $returNorec) {

                        if ($norec_struck == $r_PPLXXXX['norec_resep']) {
                            $newPPR->strukreturfk = $returNorec;
                        }
                    }

                    $newPPR->save();
                    // var_dump($newPPR);

                    $TambahStok = (float)$r_PPLXXXX['jmlretur'] * (float)$r_PPLXXXX['nilaikonversi'];
                    $dataSaldoAwal = collect(DB::select("
                         select sum(qtyproduk) as qty from stokprodukdetail_t
                         where kdprofile = $this->kdProfile and statusenabled = true and objectruanganfk=$r_SR[ruanganfk]
                         and objectprodukfk=$r_PPLXXXX[produkfk]
                    "))->first();

                    $saldoAkhir = (float)$dataSaldoAwal->qty + (float)$TambahStok;;

                    $newSPD = StokProdukDetail::where('nostrukterimafk', $r_PPLXXXX['nostrukterimafk'])
                        ->where('kdprofile', $this->kdProfile)
                        ->where('statusenabled', true)
                        ->where('objectruanganfk', $r_SR['ruanganfk'])
                        ->where('objectprodukfk', $r_PPLXXXX['produkfk'])
                        ->orderby('tglkadaluarsa', 'desc')
                        ->first();
                    
                        $valueStokProdukDetail=StokProdukDetail::where('norec',$r_PPLXXXX['norec_spd'])->select(
                            'norec',
                            'hargadiscount',
                            'harganetto1',
                            'harganetto2',
                            'objectasalprodukfk',
                            'nobatch',
                            'objectprodukfk',
                            'nostrukterimafk',
                            'tglkadaluarsa',
                            'tglpelayanan',
                            'tglproduksi',
                            'noverifikasifk'
                        )->first();
                    
                        if(empty($newSPD)){
                            $dataNewSPD = new StokProdukDetail;
                            $dataNewSPD->norec = $dataNewSPD->generateNewId();
                            $dataNewSPD->kdprofile = $this->kdProfile;
                            $dataNewSPD->statusenabled = true;
                            $dataNewSPD->objectasalprodukfk = $valueStokProdukDetail->objectasalprodukfk;
                            $dataNewSPD->hargadiscount = $valueStokProdukDetail->hargadiscount;
                            $dataNewSPD->harganetto1 = $valueStokProdukDetail->harganetto1;
                            $dataNewSPD->harganetto2 = $valueStokProdukDetail->harganetto2;
                            $dataNewSPD->persendiscount = 0;
                            $dataNewSPD->objectprodukfk = $valueStokProdukDetail->objectprodukfk;
                            // $dataNewSPD->qtyproduk = (float)$items->qtyproduk;
                            $dataNewSPD->qtyproduk = (float)$TambahStok;
                            $dataNewSPD->qtyprodukonhand = 0;
                            $dataNewSPD->qtyprodukoutext = 0;
                            $dataNewSPD->qtyprodukoutint = 0;
                            $dataNewSPD->objectruanganfk = $r_SR['ruanganfk'];
                            $dataNewSPD->nostrukterimafk = $valueStokProdukDetail->nostrukterimafk;
                            $dataNewSPD->noverifikasifk = $valueStokProdukDetail->noverifikasifk;
                            $dataNewSPD->nobatch = $valueStokProdukDetail->nobatch;
                            $dataNewSPD->tglkadaluarsa = $valueStokProdukDetail->tglkadaluarsa;
                            $dataNewSPD->tglpelayanan = $valueStokProdukDetail->tglpelayanan;
                            $dataNewSPD->tglproduksi = $valueStokProdukDetail->tglproduksi;
                            $dataNewSPD->save();
                        }   
                    else{
                            DB::table('stokprodukdetail_t')
                            ->where('kdprofile', $this->kdProfile)
                            ->where('statusenabled', true)
                            ->where('norec', $newSPD->norec)
                            ->lockForUpdate()
                            ->increment('qtyproduk', (float)$TambahStok);
                        }
                    $this->kartu_STOK(array(
                        "saldoawal" => (float)$dataSaldoAwal->qty,
                        "qtyin" => (float)$TambahStok,
                        "qtyout" => 0,
                        "saldoakhir" => $saldoAkhir,
                        "keterangan" => 'Retur Resep Pasien No. ' . $r_PPLXXXX['noresep'] . '. Berupa Produk : ' . $r_PPLXXXX['namaproduk'],
                        "produkfk" => $r_PPLXXXX['produkfk'],
                        "ruanganfk" => $r_SR['ruanganfk'],
                        "tglinput" => date('Y-m-d H:i:s'),
                        "tglkejadian" => date('Y-m-d H:i:s'),
                        "nostrukterimafk" => isset($newSPD->nostrukterimafk) ? $newSPD->nostrukterimafk : $valueStokProdukDetail->nostrukterimafk,
                        "norectransaksi" => isset($newSPD->norec) ? $newSPD->norec :  $dataNewSPD->norec,
                        "tabletransaksi" => 'pelayananpasien_t',
                        "stokprodukdetailfk" => isset($newSPD->norec) ? $newSPD->norec :  $dataNewSPD->norec,
                        "flagfk" => 3,
                    ));

                    $jumlahNow = (int)$r_PPLXXXX['jumlah'] - (int) $r_PPLXXXX['jmlretur'];
                    PelayananPasien::where('norec', $r_PPLXXXX['norecpp'])->update(['jumlah' =>  $jumlahNow, 'qtydetailresep' => $jumlahNow]);
                    // $result[] = $newPPR;
                }
            }
            $this->LOGGING(
                'Retur Resep',
                $r_SR['noresep'] ?? '',
                'pelayananpasien_t',
                'Retur Resep ' . ($r_SR['noresep'] ?? '') . '. Dengan produk ' . ($r_PPLXXXX['namaproduk'] ?? '') . '. Atas pasien ' . ($r_SR['namapasien'] ?? '') . '. ' . ($r_SR['nocm'] ?? '') . '. ' . ($r_SR['noregistrasi'] ?? '')
            );
            // // var_dump($newSRetur);

            // var_dump($newPPR);
            // return $result;

            DB::commit();
            $result = [
                "status" => 200,
                "message" => 'Retur resep berhasil disimpan',
                "result" => [
                    'strukRetur' => $newSRetur,
                    // 'ppretur'=> $newPPR
                ]
            ];
        } catch (Exception $e) {
            DB::rollBack();
            $result = [
                "status" => 400,
                "message"  => 'Something Went Wrong',
                "result" => $e->getMessage() . $e->getLine()
            ];
        }

        return $this->respond($result, $result['status'], $result['message']);
    }

    public function SimpanReturResepDibayarRanap(Request $request)
    {
        $r_SR = $request['strukresep'];
        $r_id_resep = $request['norec_resep'];
        $norec_SR = $r_SR['norecResep'];
        $r_PP = $request['pelayananpasien'];

        DB::beginTransaction();
        try {
            foreach ($r_id_resep as $value) {
                $newSRetur = new StrukRetur();
                $norecSRetur = $newSRetur->generateNewId();
                $noRetur = $this->generateCode(new StrukRetur, 'noretur', 12, 'Ret/' . $this->getDateTime()->format('ym') . '/', $this->kdProfile);
                $newSRetur->norec = $norecSRetur;
                $newSRetur->kdprofile = $this->kdProfile;
                $newSRetur->statusenabled = true;
                $newSRetur->objectkelompoktransaksifk = $this->kelompokTransaksi('RETUR RUANGAN');
                $newSRetur->keteranganalasan = $r_SR['alasan'];
                $newSRetur->keteranganlainnya = 'RETUR OBAT ALKES';
                $newSRetur->noretur = $noRetur;
                $newSRetur->objectruanganfk = $r_SR['ruanganfk'];
                $newSRetur->objectpegawaifk = $this->getPegawaiId();
                $newSRetur->tglretur = $this->getDateTime()->format('Y-m-d H:i:s');
                $newSRetur->strukresepfk = $value['norec'];
                $newSRetur->jumlahitem = $r_SR['jumlahitem'];
                $newSRetur->totalretur = $r_SR['totalretur'];
                $newSRetur->save();
                $norec_retur = $newSRetur->norec;
            }
            foreach ($r_PP as $r_PPLXXXX) {
                if (isset($r_PPLXXXX['jmlretur'])) {
                    $newPPR = new PelayananPasienRetur();
                    $norecPPR = $newPPR->generateNewId();
                    $newPPR->norec = $norecPPR;
                    $newPPR->kdprofile = $this->kdProfile;
                    $newPPR->statusenabled = true;
                    $newPPR->noregistrasifk = $r_PPLXXXX['noregistrasifk'];
                    $newPPR->tglregistrasi = $r_PPLXXXX['tglregistrasi'];
                    $newPPR->aturanpakai = $r_PPLXXXX['aturanpakai'];
                    $newPPR->generik = $r_PPLXXXX['generik'];
                    $newPPR->hargadiscount = $r_PPLXXXX['hargadiscount'];
                    $newPPR->hargajual = $r_PPLXXXX['hargajual'];
                    $newPPR->hargasatuan = $r_PPLXXXX['hargasatuan'];
                    $newPPR->jenisobatfk = $r_PPLXXXX['jenisobatfk'];
                    $newPPR->jumlah = $r_PPLXXXX['jmlretur'];
                    $newPPR->kelasfk = $r_PPLXXXX['kelasfk'];
                    $newPPR->kdkelompoktransaksi = 1;
                    $newPPR->produkfk = $r_PPLXXXX['produkfk'];
                    if (isset($r_PPL['routefk'])) {
                        $newPPR->routefk = $r_PPLXXXX['routefk'];
                    }
                    $newPPR->stock = $r_PPLXXXX['stock'];
                    $newPPR->tglpelayanan = $r_SR['tglresep'];
                    $newPPR->harganetto = $r_PPLXXXX['harganetto'];
                    $newPPR->jeniskemasanfk = $r_PPLXXXX['jeniskemasanfk'];
                    $newPPR->rke = $r_PPLXXXX['rke'];
                    $newPPR->strukresepfk = $value['norec'];
                    $newPPR->satuanviewfk = $r_PPLXXXX['satuanviewfk'];
                    $newPPR->nilaikonversi = $r_PPLXXXX['nilaikonversi'];
                    $newPPR->strukterimafk = $r_PPLXXXX['nostrukterimafk'];
                    $newPPR->dosis = $r_PPLXXXX['dosis'];
                    if ((int)$r_PPLXXXX['jumlah'] == 0) {
                        $newPPR->jasa = $r_PPLXXXX['jasa'];
                    } else {
                        $newPPR->jasa = 0;
                    }
                    $newPPR->strukreturfk = $norec_retur;
                    $newPPR->save();

                    $saldoAwal = 0;
                    $TambahStok = (float)$r_PPLXXXX['jmlretur'] * (float)$r_PPLXXXX['nilaikonversi'];
                    $dataSaldoAwal = collect(DB::select("
                         select sum(qtyproduk) as qty from stokprodukdetail_t
                         where statusenabled = true and kdprofile = $this->kdProfile and objectruanganfk=$r_SR[ruanganfk]
                         and objectprodukfk=$r_PPLXXXX[produkfk]
                    "))->first();

                    $saldoAkhir = (float)$dataSaldoAwal->qty + (float)$TambahStok;;

                    $newSPD = StokProdukDetail::where('nostrukterimafk', $r_PPLXXXX['nostrukterimafk'])
                        ->where('kdprofile', $this->kdProfile)
                        ->where('statusenabled', true)
                        ->where('objectruanganfk', $r_SR['ruanganfk'])
                        ->where('objectprodukfk', $r_PPLXXXX['produkfk'])
                        ->orderby('tglkadaluarsa', 'desc')
                        ->first();

                    DB::table('stokprodukdetail_t')
                        ->where('kdprofile', $this->kdProfile)
                        ->where('statusenabled', true)
                        ->where('norec', $newSPD->norec)
                        ->lockForUpdate()
                        ->increment('qtyproduk', (float)$TambahStok);

                    $this->kartu_STOK(array(
                        "saldoawal" => (float)$dataSaldoAwal->qty,
                        "qtyin" => (float)$TambahStok,
                        "qtyout" => 0,
                        "saldoakhir" => $saldoAkhir,
                        "keterangan" => 'Retur Resep sudah dibayar No. ' . $r_PPLXXXX['noresep'],
                        "produkfk" => $r_PPLXXXX['produkfk'],
                        "ruanganfk" => $r_SR['ruanganfk'],
                        "tglinput" => date('Y-m-d H:i:s'),
                        "tglkejadian" => date('Y-m-d H:i:s'),
                        "nostrukterimafk" => $newSPD->nostrukterimafk,
                        "norectransaksi" => $newSPD->norec,
                        "tabletransaksi" => 'pelayananpasien_t',
                        "stokprodukdetailfk" => $newSPD->norec,
                        "flagfk" => 3,
                    ));
                    $this->LOGGING(
                        'Retur Resep',
                        $r_SR['noresep'] ?? '',
                        'pelayananpasien_t',
                        'Retur Resep ' . ($r_SR['noresep'] ?? '') . '. Dengan produk ' . ($r_PPLXXXX['namaproduk'] ?? '') . '. Atas pasien ' . ($r_SR['namapasien'] ?? '') . '. ' . ($r_SR['nocm'] ?? '') . '. ' . ($r_SR['noregistrasi'] ?? '')
                    );
                }
            }
            DB::commit();
            $result = [
                "status" => 200,
                "message" => 'Retur resep berhasil disimpan',
                "result" => ['strukRetur' => $newSRetur]
            ];
        } catch (Exception $e) {
            DB::rollBack();
            $result = [
                "status" => 400,
                "message"  => 'Something Went Wrong',
                "result" => $e->getMessage()
            ];
        }

        return $this->respond($result, $result['status'], $result['message']);
    }
    
    public function fetchDetailReturPerawat(Request $req){

        $data=DB::table('strukreturperawat_t as sr')
        // ->join('strukorder_t as so','so.norec','=','sr.strukresepfk')
        ->join('strukresep_t as sp','sp.norec','=','sr.strukresepfk')
        ->join('pegawai_m as pgorder','pgorder.id','=','sr.objectpegawaifk')
        ->join('pelayananpasien_t as pp','pp.norec','=','sr.pelayananpasienfk')
        ->join('produk_m as pr','pr.id','=','pp.produkfk')
        ->join('satuanstandar_m as ss','ss.id','=','pr.objectsatuanstandarfk')
        ->join('antrianpasiendiperiksa_t as apd','apd.norec','=','sr.objectnorecapdfk')
        ->join('pasiendaftar_t as pd','pd.norec','=','sr.noregistrasifk')
        ->join('ruangan_m as ru','ru.id','=','apd.objectruanganfk')
        ->select(
            'sp.noresep',
            'sr.norec as norec_retur_perawat',
            'pr.namaproduk',
            'ss.satuanstandar',
            'sr.qtyretur as jmlretur',
            'pgorder.namalengkap as namapemintaretur',
            'ru.namaruangan as namaruanganpeminta',
            'pp.jumlah',
            'pp.aturanpakai'
        )
        ->where('sr.statusenabled',true)
        ->where('pp.statusenabled',true)
        ->where('pd.statusenabled',true)
        ->where('apd.statusenabled',true)
        ->where('pd.norec',$req['pd_norec'])
        ->where('sr.statusorder',0)
        ->get();

        $res=array(
            'data'=>$data
        );
        return $this->respond($res);
    }
    public function verificationReturPerawat (Request $req){

        $paramsFe=$req['data'][0];
        $idVerifRuangan=$req['id_ruangan_verif'];
        $valueRetur = collect();
        $triggerForCreateHead = collect();
        $norecStrukRetur=collect();

        //DOKUMENTASI
        /*
            # untuk pengambilan data yang akan di retur itu berasal dari variable ($valueretur), bukan dari kiriman front-end, front-end hanya mengirim params penting dengan di namai paramater (norec_retur_perawat). 
            # konsep dari pembuatan data Head strukretur -> ketika value dari field strukresepfk berbeda, akan otomatis membuat head strukretur baru dan sebaliknya kalau value dari field strukresepfk sama hanya membuat head strukretur satu saja. Dengan dekalrasi variable ($triggerForCreateHead).
            # no retur hanya generate sekali saja.
        */

        foreach ($paramsFe as $value) {
            
            $result = DB::table('strukreturperawat_t as sp')
                ->join('antrianpasiendiperiksa_t as apd','apd.norec','=','sp.objectnorecapdfk')
                ->join('pelayananpasien_t as pp','pp.norec','=','sp.pelayananpasienfk')
                ->join('strukresep_t as srp','srp.norec','=','sp.strukresepfk')
                ->join('produk_m as pr','pr.id','=','pp.produkfk')
                ->join('pasiendaftar_t as pd','pd.norec','=','apd.noregistrasifk')
                ->join('pasien_m as ps','ps.id','=','pd.nocmfk')
                ->where('sp.norec', $value['norec_retur_perawat'])
                ->where('sp.statusenabled', true)
                ->where('apd.statusenabled', true)
                ->where('pp.statusenabled', true)
                ->select(
                    'sp.*',
                    'apd.objectruanganfk as id_ruangan_peminta',
                    'srp.norec as norec_strukresep',
                    'srp.noresep',
                    'pp.hargasatuan',
                    'pp.noregistrasifk as noregis_apd',
                    'pp.tglregistrasi',
                    'pp.aturanpakai',
                    'pp.generik',
                    'pp.hargajual',
                    'pp.hargadiscount',
                    'pp.harganetto',
                    'pp.jenisobatfk',
                    'pp.kelasfk',
                    'pp.produkfk',
                    'pp.routefk',
                    'pp.stock',
                    'pp.rke',
                    'pp.strukresepfk as norec_struk_resep_fk',
                    'pp.satuanviewfk',
                    'pp.nilaikonversi',
                    'pp.strukterimafk',
                    'pp.stokprodukdetailfk',
                    'pp.dosis',
                    'pp.jumlah',
                    'pp.jeniskemasanfk',
                    'pr.namaproduk',
                    'ps.namapasien',
                    'pd.noregistrasi',
                    'ps.nocm'
                    )
                ->get();
                foreach ($result as  $vt) {
                    if($vt->norec == $value['norec_retur_perawat']){
                        $vt->jmlhretur = $value['jmlretur'];
                    }
                }
            $valueRetur = $valueRetur->merge($result);
            $triggerForCreateHead = $triggerForCreateHead->merge($result);
        }

        // return $valueRetur;

        $triggerForCreateHead=$triggerForCreateHead->groupBy('norec_struk_resep_fk');
        $countTrigger=count($triggerForCreateHead);

        DB::beginTransaction();
        
        try {

            $noRetur = $this->generateCode(new StrukRetur, 'noretur', 12, 'Ret/' . $this->getDateTime()->format('ym') . '/', $this->kdProfile);
            
            if($countTrigger > 0){
                foreach ($valueRetur as $key => $val) {
                    $newSRetur = new StrukRetur();
                    $norecSRetur = $newSRetur->generateNewId();
                    $newSRetur->norec = $norecSRetur;
                    $newSRetur->kdprofile = $this->kdProfile;
                    $newSRetur->statusenabled = true;
                    $newSRetur->objectkelompoktransaksifk = $this->kelompokTransaksi('RETUR RUANGAN');
                    // $newSRetur->keteranganalasan = isset($r_SR['alasan']) ? $r_SR['alasan'] : null;
                    $newSRetur->keteranganlainnya = 'RETUR OBAT ALKES';
                    $newSRetur->noretur = $noRetur;
                    $newSRetur->objectruanganfk = $idVerifRuangan;  //Ruangan id Farmasi
                    $newSRetur->objectpegawaifk = $this->getPegawaiId();
                    $newSRetur->tglretur = $this->getDateTime()->format('Y-m-d H:i:s');
                    $newSRetur->strukresepfk = $val->norec_strukresep;
                    $newSRetur->totalretur = count($paramsFe);
                    $newSRetur->jumlahitem = count($valueRetur);
                    $newSRetur->save();
                    $norecStrukRetur->push($norecSRetur);
                }
            }
            else{
                $newSRetur = new StrukRetur();
                $norecSRetur = $newSRetur->generateNewId();
                $newSRetur->norec = $norecSRetur;
                $newSRetur->kdprofile = $this->kdProfile;
                $newSRetur->statusenabled = true;
                $newSRetur->objectkelompoktransaksifk = $this->kelompokTransaksi('RETUR RUANGAN');
                // $newSRetur->keteranganalasan = isset($r_SR['alasan']) ? $r_SR['alasan'] : null;
                $newSRetur->keteranganlainnya = 'RETUR OBAT ALKES';
                $newSRetur->noretur = $noRetur;
                $newSRetur->objectruanganfk = $idVerifRuangan;  //Ruangan id Farmasi
                $newSRetur->objectpegawaifk = $this->getPegawaiId();
                $newSRetur->tglretur = $this->getDateTime()->format('Y-m-d H:i:s');
                $newSRetur->strukresepfk = $result[0]->norec_struk_resep_fk;
                $newSRetur->totalretur = count($paramsFe);
                $newSRetur->jumlahitem = count($valueRetur);
                $newSRetur->save();
            }
            // return $norecStrukRetur;
       
            foreach ($valueRetur as $r_PPLXXXX) {
                if (isset($r_PPLXXXX->qtyretur)) {
                    $newPPR = new PelayananPasienRetur();
                    $norecPPR = $newPPR->generateNewId();
                    $newPPR->norec = $norecPPR;
                    $newPPR->kdprofile = $this->kdProfile;
                    $newPPR->statusenabled = true;
                    $newPPR->noregistrasifk = $r_PPLXXXX->noregis_apd;
                    $newPPR->tglregistrasi = $r_PPLXXXX->tglregistrasi;
                    $newPPR->aturanpakai = $r_PPLXXXX->aturanpakai;
                    $newPPR->generik = $r_PPLXXXX->generik;
                    $newPPR->hargadiscount = $r_PPLXXXX->hargadiscount;
                    $newPPR->hargajual = $r_PPLXXXX->hargajual;
                    $newPPR->hargasatuan = $r_PPLXXXX->hargasatuan;
                    $newPPR->jenisobatfk = $r_PPLXXXX->jenisobatfk;
                    $newPPR->jumlah = $r_PPLXXXX->qtyretur;
                    $newPPR->kelasfk = $r_PPLXXXX->kelasfk;
                    $newPPR->kdkelompoktransaksi = 1;
                    $newPPR->produkfk = $r_PPLXXXX->produkfk;
                    if (isset($r_PPLXXXX->routefk)) {
                        $newPPR->routefk = $r_PPLXXXX->routefk;
                    }
                    $newPPR->stock = $r_PPLXXXX->stock;
                    $newPPR->tglpelayanan = $this->getDateTime()->format('Y-m-d H:i:s');
                    $newPPR->harganetto = $r_PPLXXXX->harganetto;
                    $newPPR->jeniskemasanfk = $r_PPLXXXX->jeniskemasanfk;
                    $newPPR->rke = $r_PPLXXXX->rke;
                    $newPPR->strukresepfk = $r_PPLXXXX->norec_struk_resep_fk;
                    $newPPR->satuanviewfk = $r_PPLXXXX->satuanviewfk;
                    $newPPR->nilaikonversi = $r_PPLXXXX->nilaikonversi;
                    $newPPR->strukterimafk = $r_PPLXXXX->strukterimafk;
                    $newPPR->dosis = $r_PPLXXXX->dosis;
                    if ((int)$r_PPLXXXX->jumlah == 0) {
                        $newPPR->jasa = $r_PPLXXXX->jasa;
                    } else {
                        $newPPR->jasa = 0;
                    }
                    if($countTrigger > 0){
                        foreach ($norecStrukRetur as  $value_norec) {
                            $newPPR->strukreturfk = $value_norec;
                        }
                    }
                    else{
                        $newPPR->strukreturfk = $norecSRetur;
                    } 

                    $newPPR->save();

                    $TambahStok = (float)$r_PPLXXXX->jmlhretur * (float)$r_PPLXXXX->nilaikonversi;

                    $dataSaldoAwal = collect(DB::select("
                        select sum(qtyproduk) as qty from stokprodukdetail_t
                        where kdprofile = $this->kdProfile and statusenabled = true and objectruanganfk=$idVerifRuangan
                        and objectprodukfk=$r_PPLXXXX->produkfk
                    "))->first();

                    $saldoAkhir = (float)$dataSaldoAwal->qty + (float)$TambahStok;;

                    $newSPD = StokProdukDetail::where('nostrukterimafk', $r_PPLXXXX->strukterimafk)
                        ->where('kdprofile', $this->kdProfile)
                        ->where('statusenabled', true)
                        ->where('objectruanganfk', $idVerifRuangan)
                        ->where('objectprodukfk', $r_PPLXXXX->produkfk)
                        ->orderby('tglkadaluarsa', 'desc')
                        ->first();
                    
                    $valueStokProdukDetail=StokProdukDetail::where('norec',$r_PPLXXXX->stokprodukdetailfk)->select(
                        'norec',
                        'hargadiscount',
                        'harganetto1',
                        'harganetto2',
                        'objectasalprodukfk',
                        'nobatch',
                        'objectprodukfk',
                        'nostrukterimafk',
                        'tglkadaluarsa',
                        'tglpelayanan',
                        'tglproduksi',
                        'noverifikasifk'
                    )->first();

                    if(empty($newSPD)){
                        $dataNewSPD = new StokProdukDetail;
                        $dataNewSPD->norec = $dataNewSPD->generateNewId();
                        $dataNewSPD->kdprofile = $this->kdProfile;
                        $dataNewSPD->statusenabled = true;
                        $dataNewSPD->objectasalprodukfk = $valueStokProdukDetail->objectasalprodukfk;
                        $dataNewSPD->hargadiscount = $valueStokProdukDetail->hargadiscount;
                        $dataNewSPD->harganetto1 = $valueStokProdukDetail->harganetto1;
                        $dataNewSPD->harganetto2 = $valueStokProdukDetail->harganetto2;
                        $dataNewSPD->persendiscount = 0;
                        $dataNewSPD->objectprodukfk = $valueStokProdukDetail->objectprodukfk;
                        // $dataNewSPD->qtyproduk = (float)$items->qtyproduk;
                        $dataNewSPD->qtyproduk = (float)$TambahStok;
                        $dataNewSPD->qtyprodukonhand = 0;
                        $dataNewSPD->qtyprodukoutext = 0;
                        $dataNewSPD->qtyprodukoutint = 0;
                        $dataNewSPD->objectruanganfk = $idVerifRuangan;
                        $dataNewSPD->nostrukterimafk = $valueStokProdukDetail->nostrukterimafk;
                        $dataNewSPD->noverifikasifk = $valueStokProdukDetail->noverifikasifk;
                        $dataNewSPD->nobatch = $valueStokProdukDetail->nobatch;
                        $dataNewSPD->tglkadaluarsa = $valueStokProdukDetail->tglkadaluarsa;
                        $dataNewSPD->tglpelayanan = $valueStokProdukDetail->tglpelayanan;
                        $dataNewSPD->tglproduksi = $valueStokProdukDetail->tglproduksi;
                        $dataNewSPD->save();
                    }   
                    else{
                        DB::table('stokprodukdetail_t')
                        ->where('kdprofile', $this->kdProfile)
                        ->where('statusenabled', true)
                        ->where('norec', $newSPD->norec)
                        ->lockForUpdate()
                        ->increment('qtyproduk', (float)$TambahStok);
                    }
                 

                    $this->kartu_STOK(array(
                        "saldoawal" => (float)$dataSaldoAwal->qty,
                        "qtyin" => (float)$TambahStok,
                        "qtyout" => 0,
                        "saldoakhir" => $saldoAkhir,
                        "keterangan" => 'Retur Resep Pasien No. ' . $r_PPLXXXX->noresep . '. Berupa Produk : ' . $r_PPLXXXX->namaproduk,
                        "produkfk" => $r_PPLXXXX->produkfk,
                        "ruanganfk" => $idVerifRuangan,
                        "tglinput" => date('Y-m-d H:i:s'),
                        "tglkejadian" => date('Y-m-d H:i:s'),
                        "nostrukterimafk" => isset($newSPD->nostrukterimafk) ? $newSPD->nostrukterimafk : $dataNewSPD->nostrukterimafk,
                        "norectransaksi" =>isset( $newSPD->norec) ?  $newSPD->norec :  $dataNewSPD->norec,
                        "tabletransaksi" => 'pelayananpasien_t',
                        "stokprodukdetailfk" => isset($newSPD->norec) ? $newSPD->norec : $dataNewSPD->norec,
                        "flagfk" => 3,
                    ));

                    $jumlahNow = (int)$r_PPLXXXX->jumlah - (int) $TambahStok;
                    PelayananPasien::where('norec', $r_PPLXXXX->pelayananpasienfk)->update(['jumlah' =>  $jumlahNow, 'qtydetailresep' => $jumlahNow]);
                    StrukReturPerawat::where('norec', $r_PPLXXXX->norec)->update(['statusorder' =>  1]);

                    $result[] = $newPPR;
                }

                $this->LOGGING(
                    'Retur Resep',
                    $r_PPLXXXX->noresep ?? '',
                    'pelayananpasien_t',
                    'Retur Resep ' . ($r_PPLXXXX->noresep ?? '') . '. Dengan produk ' . ($r_PPLXXXX->namaproduk ?? '') . '. Atas pasien ' . ($r_PPLXXXX->namapasien ?? '') . '. ' . ($r_PPLXXXX->nocm ?? '') . '. ' . ($r_PPLXXXX->noregistrasi ?? '')
                );
            }
            // // var_dump($newSRetur);

            // var_dump($newPPR);
            // return $result;

            DB::commit();
            $result = [
                "status" => 200,
                "message" => 'Retur resep berhasil disimpan',
                "result" => [
                    'strukRetur' => $newSRetur,
                    'ppretur'=> $newPPR,
                    'dataSPD'=> isset($newSPD) ? $newSPD : $dataNewSPD
                ]
            ];
        } catch (Exception $e) {
            DB::rollBack();
            $result = [
                "status" => 400,
                "message"  => 'Something Went Wrong',
                "result" => $e->getMessage() . $e->getLine()
            ];
        }
        return $this->respond($result, $result['status'], $result['message']);
    }
}
