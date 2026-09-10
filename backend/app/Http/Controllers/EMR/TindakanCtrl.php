<?php

namespace App\Http\Controllers\EMR;

use App\Http\Controllers\Controller;
use App\Models\Master\JenisPetugasPelaksana;
use App\Models\Master\Pegawai;
use App\Models\Master\Ruangan;
use App\Models\Transaksi\AntrianPasienDiperiksa;
use App\Models\Transaksi\PasienDaftar;
use App\Models\Transaksi\PelayananPasien;
use App\Models\Transaksi\PelayananPasienDetail;
use App\Models\Transaksi\PelayananPasienLimit;
use App\Models\Transaksi\PelayananPasienPetugas;
use App\Models\Transaksi\TempTindakan;
use App\Traits\Valet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use function PHPUnit\Framework\isEmpty;

class TindakanCtrl extends Controller
{
    use Valet;
    public function __construct()
    {
        parent::__construct($is_encrypt = true);
    }
    public function headerPasien(Request $r)
    {
        $data = DB::table('pasiendaftar_t as pd')
            ->JOIN('ruangan_m as ru', 'ru.id', '=', 'pd.objectruanganlastfk')
            ->JOIN('departemen_m as dp', 'dp.id', '=', 'ru.objectdepartemenfk')
            ->JOIN('kelompokpasien_m as kp', 'kp.id', '=', 'pd.objectkelompokpasienlastfk')
            ->JOIN('pasien_m as ps', 'ps.id', '=', 'pd.nocmfk')
            ->JOIN('alamat_m as alm', 'alm.nocmfk', '=', 'ps.id')
            ->JOIN('jeniskelamin_m as jk', 'jk.id', '=', 'ps.objectjeniskelaminfk')
            ->select(
                'ps.nocm',
                'ps.id as nocmfk',
                'ps.namapasien',
                'ps.tgllahir',
                'ps.tempatlahir',
                'ps.objectjeniskelaminfk',
                'jk.jeniskelamin',
                'ps.objectagamafk',
                'ps.noidentitas',
                'ps.nobpjs',
                'ps.noasuransilain',
                'alm.alamatlengkap',
                'alm.kodepos',
                'ps.notelepon',
                'ps.nohp',
                'ps.namaayah',
                'ps.namaibu',
                'ps.objectkebangsaanfk',
                'ps.email'
            )
            ->where('ps.kdprofile', (int)$this->kdProfile)
            ->where('ps.statusenabled', true)
            ->where('ps.id', $r['nocmfk'])
            ->first();
        if (!empty($data)) {
            $data->umur =  $this->getAge($data->tgllahir, date('Y-m-d H:i:s'));
        }
        $registrasi = DB::table('pasiendaftar_t as pd')
            ->JOIN('antrianpasiendiperiksa_t as apd', 'apd.noregistrasifk', '=', 'pd.norec')
            ->JOIN('ruangan_m as ru', 'ru.id', '=', 'apd.objectruanganfk')
            ->JOIN('departemen_m as dp', 'dp.id', '=', 'ru.objectdepartemenfk')
            ->JOIN('kelompokpasien_m as kp', 'kp.id', '=', 'pd.objectkelompokpasienlastfk')
            ->JOIN('kelas_m as kl', 'kl.id', '=', 'apd.objectkelasfk')
            ->select(
                'pd.noregistrasi',
                'pd.norec as norec_pd',
                'pd.tglregistrasi',
                'pd.tglpulang',
                'dp.namadepartemen',
                'kp.kelompokpasien',
                'apd.norec as norec_apd',
                'pd.objectruanganlastfk',
                'apd.objectruanganfk',
                'ru.namaruangan',
                'apd.objectkelasfk',
                'kl.namakelas',
                'pd.nocmfk',
                'apd.tglmasuk',
                'apd.tglkeluar',
                'pd.objectkelompokpasienlastfk',
                'pd.objectrekananfk',
                'pd.jenispelayanan',
                'pd.jenispelayanan as jenispelayananfk',
                'pd.iskelastitip'
            )
            ->where('pd.kdprofile', (int)$this->kdProfile)
            ->where('pd.statusenabled', true)
            ->where('apd.statusenabled', true)
            ->where('pd.nocmfk', $r['nocmfk'])
            ->where('pd.norec', $r['norec_pd'])
            ->orderBy('apd.tglmasuk', 'desc')
            ->get();
        $last = $registrasi[0];
        $tgl = date('2000-01-01 00:00');
        foreach ($registrasi as $d) {
            if (isset($r['norec_apd']) && $r['norec_apd'] != '' && $r['norec_apd'] == $d->norec_apd) {
                $last  = $d;
                break;
            }
            // else {
            //     if ($d->objectruanganlastfk == $d->objectruanganfk && $tgl < $d->tglmasuk) {
            //         $tgl = $d->tglmasuk;
            //         $last  = $d;
            //     }
            // }
        }
        $result['pasien'] = $data;
        $result['registrasi'] = $registrasi;
        $result['last_registrasi'] = $last;
        $result['as'] = '@epic';

        return $this->respond($result);
    }

    public function listTindakan(Request $r)
    {

        try {
            $data = DB::table('mapruangantoproduk_m as mpr')
                ->join('produk_m as prd', 'prd.id', '=', 'mpr.objectprodukfk')
                ->select(
                    'mpr.objectprodukfk as id',
                    'prd.namaproduk',
                    'mpr.objectruanganfk',
                )->distinct()
                ->where('mpr.kdprofile', $this->kdProfile)
                // ->where('mpr.objectruanganfk', $r['idruangan'])
                ->where('mpr.statusenabled', true)
                ->where('prd.statusenabled', true);
            // ->where('prd.namaproduk', '!=', 'Biaya Registrasi Pasien');
            if (isset($r['idruangan']) &&  $r['idruangan'] != '' &&  $r['idruangan'] != 'undefined') {
                $data = $data->where('mpr.objectruanganfk', $r['idruangan']);
            }
            // else{
            //     $data = $data->where('mpr.objectruanganfk',0);
            // }
            if (
                isset($r['name']) &&
                $r['name'] != "" &&
                $r['name'] != "undefined"
            ) {
                $data = $data
                    ->where('prd.namaproduk', 'ilike', '%' . $r['name'] . '%');
            }
            if (
                isset($r['limit']) &&
                $r['limit'] != "" &&
                $r['limit'] != "undefined"
            ) {
                $data = $data->take($r['limit']);
            }
            $data = $data->orderBy('prd.namaproduk', 'ASC');
            $data = $data->get();
            if (
                isset($r['idkelas']) &&
                $r['idkelas'] != "" &&
                $r['idkelas'] != "undefined"
            ) {

                $sk =  DB::table('suratkeputusan_m')
                    ->where('statusenabled', true)
                    ->where('objectjeniskeputusanfk', $this->settingFix('jenisSK_TARIF'))->first();
                $skID = !empty($sk) ? $sk->id : 0;

                $q2 = DB::table('harganettoprodukbykelas_m as hnp')
                    ->join('mapruangantoproduk_m as mpr', 'mpr.objectprodukfk', '=', 'hnp.objectprodukfk')
                    ->join('produk_m as prd', 'prd.id', '=', 'mpr.objectprodukfk')
                    ->join('suratkeputusan_m as sk', 'hnp.suratkeputusanfk', '=', 'sk.id')
                    ->select(
                        'hnp.objectprodukfk'
                    )
                    ->distinct();
                if (isset($r['idruangan']) &&  $r['idruangan'] != '' &&  $r['idruangan'] != 'undefined') {
                    $q2->where('mpr.objectruanganfk', $r['idruangan']);
                }
                if (isset($r['idkelas']) &&  $r['idkelas'] != '' &&  $r['idkelas'] != 'undefined') {
                    $q2->where('hnp.objectkelasfk', $r['idkelas']);
                }

                $q2->where('hnp.suratkeputusanfk', $skID)
                    ->whereNull('hnp.objectpenjaminfk')
                    ->where('hnp.statusenabled', true)
                    ->where('mpr.statusenabled', true)
                    ->where('hnp.kdprofile',  $this->kdProfile)
                    ->where('prd.statusenabled', true);
                $data2 = $q2->get();
                $filterData = [];
                foreach ($data as $d) {
                    foreach ($data2 as $d2) {
                        if ($d->id == $d2->objectprodukfk) {
                            $filterData[] = $d;
                            break;
                        }
                    }
                }
                $data = $filterData;
            }

            if (!isset($r['idruangan'])) {
                $data = array_reduce(json_decode($data, true), function ($carry, $item) {
                    if (!isset($carry[$item['id']])) {
                        $carry[$item['id']] = $item;
                    }
                    return $carry;
                }, []);
            }


            $result['data'] = array_values($data);
            // $result['data'] = array_values($uniqueData);
            // $result['filterData'] = count($filterData);
            $result['as'] = '@epic';
            return $this->respond($result);
        } catch (\Exception $e) {
            $ress = [
                "data" => null,
                "message" => $e->getMessage()
            ];
            return $this->respond($ress);
        }
    }

    public function listRegistrasi(Request $r)
    {
        $registrasi = DB::table('pasiendaftar_t as pd')
            // ->join('antrianpasiendiperiksa_t as apd', function ($join) {
            //     $join->on('apd.noregistrasifk', '=', 'pd.norec');
            //     // $join->limit))
            // })
            ->join('antrianpasiendiperiksa_t as apd', 'apd.noregistrasifk', '=', 'pd.norec')
            ->JOIN('ruangan_m as ru', 'ru.id', '=', 'apd.objectruanganfk')
            ->JOIN('departemen_m as dp', 'dp.id', '=', 'ru.objectdepartemenfk')
            ->JOIN('asalrujukan_m as asru', 'asru.id', '=', 'pd.asalrujukanfk')
            ->JOIN('kelompokpasien_m as kp', 'kp.id', '=', 'pd.objectkelompokpasienlastfk')
            ->LEFTJOIN('rekanan_m as rk', 'rk.id', '=', 'pd.objectrekananfk')
            ->LEFTJOIN('pegawai_m as pg', 'pg.id', '=', db::raw('COALESCE(apd.objectpegawaifk,pd.objectpegawaifk)'))
            ->LEFTJOIN('pemakaianasuransi_t as pa', 'pa.noregistrasifk', '=', 'pd.norec')
            ->JOIN('kelas_m as kl', 'kl.id', '=', 'apd.objectkelasfk')
            ->LEFTJOIN('jenispelayanan_m as jp', 'jp.id', '=', 'pd.jenispelayanan')
            // ->leftjoin('strukorder')
            ->select(
                'pd.noregistrasi',
                'pd.norec',
                'pd.isclosing',
                'pd.norec as norec_pd',
                DB::raw("to_char(apd.tglmasuk, 'DD-MM-YYYY') as tglregistrasi"),
                'pd.tglregistrasi as tanggal',
                'pd.tglpulang',
                'dp.namadepartemen',
                'kp.kelompokpasien',
                'asru.asalrujukan',
                'ru.namaruangan',
                'kl.namakelas',
                'pd.nocmfk',
                'rk.namarekanan',
                'pg.namalengkap as dokter',
                'pd.objectruanganlastfk',
                'apd.objectruanganfk',
                'apd.norec as norec_apd',
                'pd.objectkelompokpasienlastfk',
                'pd.objectrekananfk',
                'pd.jenispelayanan',
                'pd.jenispelayanan as jenispelayananfk',
                'apd.objectkelasfk',
                'apd.kelasrawatfk',
                'ru.objectdepartemenfk',
                'apd.objectpegawaifk',
                'pd.isnaikkelas',
                'pd.objectpegawaifk_dod',
                'pa.nosep',
                'pd.inacbg_totalgrouper',
                'ru.kdsubspesialisbpjs',
                'ru.namasubspesialisbpjs',
                'pa.dpjplayan_kode',
                'pa.dpjplayan_nama',
                'pd.tinggibadan',
                'pd.beratbadan',
                'pd.suhu',
                'pd.nadi',
                'pd.pernafasan',
                'pd.tekanandarah',
                'pd.spo2',
                'jp.jenispelayanan as jenispelayananBaru',
                'pd.isberkas',
                'pd.iskelastitip'
            )
            ->where('pd.kdprofile', (int)$this->kdProfile)
            ->where('pd.statusenabled', true)
            ->where('apd.statusenabled', true);


        $jmlRegis = $registrasi->count();
        if (isset($r['nocmfk']) && $r['nocmfk'] != '') {
            $registrasi = $registrasi->where('pd.nocmfk', $r['nocmfk']);
        }

        if (isset($r['norec_pd']) && $r['norec_pd'] != '') {
            $registrasi = $registrasi->where('pd.norec', $r['norec_pd']);
        }
        if (isset($r['limit']) && $r['limit'] != '') {
            $registrasi = $registrasi->limit($r['limit']);
        }
        if (isset($r['offset']) && $r['offset'] != '') {
            $registrasi = $registrasi->offset($r['offset']);
            $registrasi =  $registrasi->orderByDesc('pd.tglregistrasi');
            $registrasi =  $registrasi->orderByDesc('dp.namadepartemen');
        }

        if (isset($r['rad']) && $r['rad'] == 'true') {
            $registrasi = $registrasi->where('ru.objectdepartemenfk', $this->settingFix('idDepartemenRadiologi'))->orderByDesc('apd.tglregistrasi')->first();
        } else {
            $registrasi =  $registrasi->get();
        }

        $result['registrasi'] = $registrasi;
        return $this->respond($result);
    }

    public function listJenisPetugasPE(Request $r)
    {
        if (isset($r['israd']) && $r['israd'] == true) {
            $data = [11, 4, 16, 6];
            $res['jenispetugaspelaksana'] = JenisPetugasPelaksana::mine()->whereIn('id', $data)->orderBy('id')->get();
        } else {
            $res['jenispetugaspelaksana'] = JenisPetugasPelaksana::mine()->orderBy('id')->get();
        }

        $res['cito'] = $this->settingFix('tarifCito');

        return $this->respond($res);
    }
    public function listMapJenisPetugasPE(Request $r)
    {
        $data = DB::table('mapjenispetugasptojenispegawai_m as mpp')
            ->join('jenispegawai_m as jp', 'jp.id', '=', 'mpp.objectjenispegawaifk')
            ->join('pegawai_m as pg', 'pg.objectjenispegawaifk', '=', 'jp.id')
            ->join('jenispetugaspelaksana_m as jpp', 'jpp.id', '=', 'mpp.objectjenispetugaspefk')
            ->select(
                'pg.namalengkap',
                'pg.id'
            )
            ->where('mpp.kdprofile', $this->kdProfile)
            ->where('mpp.objectjenispetugaspefk', $r['idJenisPetugas'])
            ->where('mpp.statusenabled', true)
            ->where('pg.statusenabled', true)
            ->where('jpp.statusenabled', true)
            ->orderBy('pg.namalengkap', 'ASC');

        if (isset($r['israd']) && $r['israd'] == true && isset($r['idJenisPetugas']) && $r['idJenisPetugas'] == 4 || $r['idJenisPetugas'] == 6) {
            $data = $data->where('pg.namalengkap', 'ilike', '%Sp.Rad%')
                ->orWhere('pg.namalengkap', 'ilike', '%Sp.KN%')
                ->orWhere('pg.namaexternal', 'ilike', '%DOKTER ANASTESI%');
        }
        $data = $data->distinct()->get();


        return $this->respond($data);
    }
    public function listMapPetugasAll(Request $r)
    {
        $data = DB::table('pegawai_m as pg')
            ->select(
                'pg.namalengkap',
                'pg.id'
            )
            ->where('pg.statusenabled', true)
            ->whereIn('pg.objectjenispegawaifk', [1, 10])
            ->when($r->filled('q'), function ($q) use ($r) {
                $q->where(DB::raw('LOWER(pg.namalengkap)'), 'like', '%' . strtolower($r->q) . '%');
            })
            ->when($r->filled('limit'), function ($q) use ($r) {
                $q->limit($r['limit']);
            })
            ->groupBy('pg.id')
            ->orderBy('pg.namalengkap', 'ASC');
        // ->get();

        if (isset($r['namaexternal']) && $r['namaexternal'] != '') {
            $data = $data->where('namaexternal', 'DOKTER');
        }

        $data = $data->get();

        return $this->respond($data);
    }

    public function listTindakanKomponen(Request $request, $lokal = false)
    {
        $idProfile = (int) $this->kdProfile;
        $request['idJenisPelayanan'] = 1;
        $nilaiCito  = (float) $this->settingFix('tarifCito');
        // $set = $this->settingFix('idPenjaminUmum');

        // if ($set == $request['idPenjamin']) {
        // $request['idPenjamin'] = null;
        // }

        $sk =  DB::table('suratkeputusan_m')->where('statusenabled', true)->where('objectjeniskeputusanfk', $this->settingFix('jenisSK_TARIF'))->first();
        $skID = !empty($sk) ? $sk->id : 0;

        if (
            isset($request['idPenjamin']) 
            && $request['idPenjamin'] != 'null' 
            && $request['idPenjamin'] != ''
            && $request['idPenjamin'] != null
        ) {
            $data = DB::table('harganettoprodukbykelasd_m as hnp')
                ->join('mapruangantoproduk_m as mpr', 'mpr.objectprodukfk', '=', 'hnp.objectprodukfk')
                ->join('produk_m as prd', 'prd.id', '=', 'mpr.objectprodukfk')
                ->join('komponenharga_m as kh', 'kh.id', '=', 'hnp.objectkomponenhargafk')
                ->select(
                    'hnp.objectkomponenhargafk',
                    'kh.komponenharga',
                    'hnp.hargasatuan',
                    'mpr.objectprodukfk',
                    'kh.iscito',
                    'hnp.hargadijamin',
                    DB::raw("CASE WHEN hnp.hargadiscount IS NULL THEN 0 ELSE hnp.hargadiscount END AS diskon")
                )
                //->where('mpr.objectruanganfk', $request['idRuangan'])
                ->where('hnp.objectkelasfk', $request['idKelas'])
                ->where('mpr.objectprodukfk', $request['idProduk'])
                ->where('hnp.objectjenispelayananfk', $request['idJenisPelayanan'])
                ->where('hnp.objectpenjaminfk', $request['idPenjamin'])
                ->where('hnp.objectkebangsaanfk', $request['objectkebangsaanfk'])
                ->where('hnp.suratkeputusanfk', $skID)
                ->where('mpr.statusenabled', true)
                ->where('hnp.statusenabled', true)
                ->where('prd.statusenabled', true);
            $data = $data->distinct();
            $data = $data->get();

            if (isset($request['isCito']) && $request['isCito'] == 'true' && count($data) > 0) {
                foreach ($data as $index => $dat) {
                    $harga_cito = $dat->hargasatuan * $nilaiCito;
                    $dat->hargasatuan = $dat->hargasatuan + $harga_cito;
                }
            }
        } else {
            $data = [];
        }


        if (count($data) == 0) {
            $data = DB::table('harganettoprodukbykelasd_m as hnp')
                ->join('mapruangantoproduk_m as mpr', 'mpr.objectprodukfk', '=', 'hnp.objectprodukfk')
                ->join('produk_m as prd', 'prd.id', '=', 'mpr.objectprodukfk')
                ->join('komponenharga_m as kh', 'kh.id', '=', 'hnp.objectkomponenhargafk')
                ->select(
                    'hnp.objectkomponenhargafk',
                    'kh.komponenharga',
                    'hnp.hargasatuan',
                    'mpr.objectprodukfk',
                    'kh.iscito',
                    'hnp.hargadijamin',
                    DB::raw("CASE WHEN hnp.hargadiscount IS NULL THEN 0 ELSE hnp.hargadiscount END AS diskon")
                )
                //->where('mpr.objectruanganfk', $request['idRuangan'])
                ->where('hnp.objectkelasfk', $request['idKelas'])
                ->where('mpr.objectprodukfk', $request['idProduk'])
                ->where('hnp.objectjenispelayananfk', $request['idJenisPelayanan'])
                ->where('hnp.objectkebangsaanfk', $request['objectkebangsaanfk'])
                ->where('hnp.suratkeputusanfk', $skID)
                ->whereNull('hnp.objectpenjaminfk')
                ->where('mpr.statusenabled', true)
                ->where('hnp.statusenabled', true)
                ->where('prd.statusenabled', true);
            $data = $data->distinct();
            $data = $data->get();

            if (isset($request['isCito']) && $request['isCito'] == 'true' && count($data) > 0) {
                foreach ($data as $index => $dat) {
                    $harga_cito = $dat->hargasatuan * $nilaiCito;
                    $dat->hargasatuan = $dat->hargasatuan + $harga_cito;
                }
            }
        }

        if (
            isset($request['idPenjamin']) 
            && $request['idPenjamin'] != 'null' 
            && $request['idPenjamin'] != ''
            && $request['idPenjamin'] != null
        ) {
            $data2 = DB::table('harganettoprodukbykelas_m as hnp')
                ->join('mapruangantoproduk_m as mpr', 'mpr.objectprodukfk', '=', 'hnp.objectprodukfk')
                ->join('produk_m as prd', 'prd.id', '=', 'mpr.objectprodukfk')
                ->join('suratkeputusan_m as sk', 'hnp.suratkeputusanfk', '=', 'sk.id')
                ->select(
                    'hnp.id',
                    'hnp.hargasatuan',
                    'hnp.hargadijamin',
                    DB::raw("CASE WHEN hnp.hargadiscount IS NULL THEN 0 ELSE hnp.hargadiscount END AS diskon")
                )
                //->where('mpr.objectruanganfk', $request['idRuangan'])
                ->where('hnp.objectkelasfk', $request['idKelas'])
                ->where('mpr.objectprodukfk', $request['idProduk'])
                ->where('hnp.objectjenispelayananfk', $request['idJenisPelayanan'])
                ->where('hnp.objectpenjaminfk', $request['idPenjamin'])
                ->where('hnp.objectkebangsaanfk', $request['objectkebangsaanfk'])
                ->where('hnp.suratkeputusanfk', $skID)
                ->where('hnp.statusenabled', true)
                ->where('sk.statusenabled', true)
                ->where('mpr.statusenabled', true)
                ->where('hnp.kdprofile', $idProfile)
                ->where('prd.statusenabled', true)
                ->distinct()
                ->first();

                if (isset($request['isCito']) && $request['isCito'] == 'true' && isset($data2)) {
                    $harga_cito = $data2->hargasatuan * $nilaiCito;
                    $data2->hargasatuan = $data2->hargasatuan + $harga_cito;
                }
        } else {
            $data2 = null;
        }

        if (empty($data2)) {
            $data2 = DB::table('harganettoprodukbykelas_m as hnp')
                ->join('mapruangantoproduk_m as mpr', 'mpr.objectprodukfk', '=', 'hnp.objectprodukfk')
                ->join('produk_m as prd', 'prd.id', '=', 'mpr.objectprodukfk')
                ->join('suratkeputusan_m as sk', 'hnp.suratkeputusanfk', '=', 'sk.id')
                ->select(
                    'hnp.id',
                    'hnp.hargasatuan',
                    'hnp.hargadijamin',
                    DB::raw("CASE WHEN hnp.hargadiscount IS NULL THEN 0 ELSE hnp.hargadiscount END AS diskon")
                )
                //->where('mpr.objectruanganfk', $request['idRuangan'])
                ->where('hnp.objectkelasfk', $request['idKelas'])
                ->where('mpr.objectprodukfk', $request['idProduk'])
                ->where('hnp.suratkeputusanfk', $skID)
                ->where('hnp.objectjenispelayananfk', $request['idJenisPelayanan'])
                ->where('hnp.objectkebangsaanfk', $request['objectkebangsaanfk'])
                ->whereNull('hnp.objectpenjaminfk')
                ->where('hnp.statusenabled', true)
                ->where('sk.statusenabled', true)
                ->where('mpr.statusenabled', true)
                ->where('hnp.kdprofile', $idProfile)
                ->where('prd.statusenabled', true)
                ->distinct()
                ->first();
            $istarifpenjamin = false;

            if (isset($request['isCito']) && $request['isCito'] == 'true' && isset($data2)) {
                $harga_cito = $data2->hargasatuan * $nilaiCito;
                $data2->hargasatuan = $data2->hargasatuan + $harga_cito;
            }
        }

        $result = array(
            'komponen' => $data,
            'harga' => $data2,
            // 'istarifpenjamin' => $istarifpenjamin,
            'as' => '@epic',
        );
        if ($lokal) {
            return $result;
        }

        return $this->respond($result);
    }
    public function saveTindakan(Request $r)
    {
        DB::beginTransaction();
        try {
            $idProfile = (int) $this->kdProfile;
            $kpID = $this->settingFix('idKelompokTransaksiPelayanan');
            $tuslah = (float) $this->settingFix('persenUpTuslah');
            $nilaiCito = (float) $this->settingFix('tarifCito');
            $komponenHargaJasaDokter = $this->settingFix('komponenHargaJasaDokter');
            $alurflag = $r['flag'];

            $totalJasa = 0;
            $totJasa = 0;
            $penjumlahanJasa = 0;
            $penjumlahanJasaTuslah = 0;
            $noreckun = [];
            $noreckunlab = [];
            $noreckunrad = [];
            $new = null;
            $totalInsert = 0;
            foreach ($r['pelayananpasien'] as $item) {
                $totalJasa = 0;
                $totJasa = 0;
                $penjumlahanJasa = 0;
                $penjumlahanJasaTuslah = 0;
                if (!isset($item['norec_pp']) || $item['norec_pp'] == '') {
                    if (isset($item['limit']) && $item['limit'] == true) {
                        $new = new PelayananPasienLimit();
                    } else {
                        $new = new PelayananPasien();
                    }
                    $new->norec = $new->generateNewId();
                } else {
                    if (isset($item['limit']) && $item['limit'] == true) {
                        $new = PelayananPasienLimit::where('norec', $item['norec_pp'])->first();
                    } else {
                        $new = PelayananPasien::where('norec', $item['norec_pp'])->first();
                    }
                }
                $new->kdprofile = 1;
                $new->statusenabled = true;
                $norec_apd = '';

                if ($item['objectruanganfk'] != null && $item['isPaketTambah'] == true) {
                    $dataapd = AntrianPasienDiperiksa::where('noregistrasifk', $item['norec_pd'])->where('objectruanganfk', $item['objectruanganfk'])->first();
                    if (!empty($dataapd)) {
                        $norec_apd = $dataapd->norec;
                    } else {
                        $max = AntrianPasienDiperiksa::where('objectruanganfk', $item['objectruanganfk'])
                            ->where('tglregistrasi', '>=', date('Y-m-d', strtotime($item['tglregistrasi'])) . ' 00:00')
                            ->where('tglregistrasi', '<=', date('Y-m-d', strtotime($item['tglregistrasi'])) . ' 23:59')
                            ->where('statusenabled', true)
                            ->max('noantrian');
                        $noAntrian = $max + 1;

                        $model_APD = new AntrianPasienDiperiksa;
                        $model_APD->norec = $model_APD->generateNewId();
                        $model_APD->kdprofile = $idProfile;
                        $model_APD->statusenabled = true;
                        $model_APD->noantrian = $noAntrian;
                        $model_APD->objectasalrujukanfk = 23;
                        $model_APD->objectkelasfk = 6;
                        $model_APD->objectkamarfk = null;
                        $model_APD->objectruanganfk = $item['objectruanganfk'];
                        $model_APD->tglkeluar = date_create(date('Y-m-d H:i:s', strtotime($item['tglregistrasi'])));
                        $model_APD->noregistrasifk = $item['norec_pd'];
                        $model_APD->objectpegawaifk = null;
                        $model_APD->statusantrian = 0;
                        $model_APD->status = "Belum Dipanggil";
                        $model_APD->statuskunjungan = null;
                        $model_APD->iskonsul = true;
                        $model_APD->isaskepnurse = true;
                        $model_APD->tglregistrasi = date_create(date('Y-m-d H:i:s', strtotime($item['tglregistrasi'])));
                        $model_APD->tglmasuk = date_create(date('Y-m-d H:i:s', strtotime($item['tglregistrasi'])));
                        $model_APD->israwatgabung = false;
                        $model_APD->save();

                        $norec_apd = $model_APD->norec;
                    }
                } else {
                    $norec_apd = $item['norec_apd'];
                }

                $new->noregistrasifk = $norec_apd;
                $new->tglregistrasi = date_create(date('Y-m-d H:i:s', strtotime($item['tglregistrasi'])));
                $new->hargadiscount = $item['diskon'];
                $new->hargajual = $item['hargasatuan'];
                $new->hargasatuan = $item['hargasatuan'];
                $new->hargasebelumcito = $item['hargasebelumcito'] ?? null;
                $new->jumlah = $item['jumlah'];
                $new->kelasfk = $item['kelasfk'];
                $new->kdkelompoktransaksi = $kpID;
                $new->keteranganlain = 'Tindakan';
                $new->piutangpenjamin = 0;
                $new->piutangrumahsakit = 0;
                $new->produkfk = $item['produkfk'];
                $new->stock = 1;
                $new->tglpelayanan = date_create(date('Y-m-d H:i:s', strtotime($item['tglpelayanan'])));
                $new->harganetto = $item['hargasatuan'];
                $new->iscito = $item['iscito'];
                $new->pelayananpegawaifk = isset($item['pelaksana']) ? $item['pelaksana']['value'] : null;
                $new->dpjp = $item['dokter'];
                $new->isparamedis = $item['isparamedis'];
                $new->jenispelayananfk = isset($item['jenispelayananfk']) ? $item['jenispelayananfk'] : null;
                $new->istarifdetault = isset($item['istarifdetault']) ? $item['istarifdetault'] : null;
                $new->hargadijamin = isset($item['hargadijamin']) ? $item['hargadijamin'] : null;
                $new->noregistrasi = $r['noregistrasi'];
                $new->jasa = 0;
                $new->petugas = $this->getNamaPegawai();
                $new->objecthargaprodukfk = $item['id_hnp'] ?? null;
                $new->save();

                AntrianPasienDiperiksa::where('norec', $norec_apd)->update(['objectpegawaifk' => $item['dokter'],]);

                //? Lab & Bank Darah
                if (($item['objectruanganfk'] == 123 || $item['objectruanganfk'] == 124) && $item['isPaketTambah'] == true) {
                    $new->namaproduk = $item['namaproduk'];
                    $new->dokterVerifLab = $item['pelaksana']['label'];
                    $new->iddokterVerifLab = $item['pelaksana']['value'];
                    $noreckunlab[] = $new;
                }

                //? Radiologi
                if (($item['objectruanganfk'] == 120) && $item['isPaketTambah'] == true) {
                    $new->namaproduk = $item['namaproduk'];
                    $noreckunrad[] = $new;
                }

                $noreckun[] = $new->norec;

                $this->LOGGING(
                    'Tambah Tindakan',
                    $new->norec,
                    'pelayananpasien_t',
                    'Tambah Tindakan ' . $item['namaproduk'] . ' di ' . $r['namaruangan'] . ' pada Pasien ' .
                    $r['namapasien'] . ' (' . $r['nocm'] . ') - ' . $r['noregistrasi']
                );

                if (isset($r['departemen']) && $r['departemen'] == 'instalasi radiologi') {
                    if (isset($item['dokterVerifRad']['default'])) {
                        $default = $item['dokterVerifRad']['default'];
                        $new_PPP = new PelayananPasienPetugas();
                        $new_PPP->norec = $new_PPP->generateNewId();
                        $new_PPP->kdprofile = $idProfile;
                        $new_PPP->statusenabled = true;
                        $new_PPP->nomasukfk = $item['norec_apd'];
                        $new_PPP->objectjenispetugaspefk = $this->settingFix('idDokterPemeriksa');
                        $new_PPP->objectpegawaifk = $default['id'];
                        $new_PPP->pelayananpasien = $new->norec;
                        $new_PPP->noregistrasi = $r['noregistrasi'];
                        $new_PPP->save();
                    } else {
                        echo '
                            <script language="javascript">
                                window.alert("Terjadi kesalahan.");
                                window.close()
                            </script>';
                        die;
                    }
                } else {
                    //? Handle setting datafixed tidak terupdate.
                    $jenisPelaksana = !empty($this->settingFix('defaultPetugasPelaksana')) ? $this->settingFix('defaultPetugasPelaksana') : 2;
                    foreach ($item['pelayananpetugas'] as $items) {
                        foreach ($items['listpegawai'] as $itemsPPP) {
                            if (!isset($item['norec_pp']) || $item['norec_pp'] == '') {
                                $new_PPP = new PelayananPasienPetugas();
                                $new_PPP->norec = $new_PPP->generateNewId();
                            } else {
                                //? Kenapa ga pakai norec_ppp? Karena untuk tindakan biasa hanya diinsert satu petugas saja.
                                $new_PPP = PelayananPasienPetugas::where('pelayananpasien', $new->norec)->first();
                                if (empty($new_PPP)) {
                                    $new_PPP = new PelayananPasienPetugas;
                                    $new_PPP->norec = $new_PPP->generateNewId();
                                }
                            }

                            $new_PPP->kdprofile = $idProfile;
                            $new_PPP->statusenabled = true;
                            $new_PPP->nomasukfk = $item['norec_apd'];
                            // $new_PPP->objectjenispetugaspefk = $items['jenispelaksana'];
                            $new_PPP->objectjenispetugaspefk = $jenisPelaksana;
                            $new_PPP->objectpegawaifk = $itemsPPP['value'];
                            $new_PPP->pelayananpasien = $new->norec;
                            $new_PPP->noregistrasi = $r['noregistrasi'];
                            $new_PPP->save();
                        }
                    }
                }

                foreach ($item['komponenharga'] as $itemKomponen) {
                    if (!isset($item['norec_pp']) || $item['norec_pp'] == '') {
                        $new_PPD = new PelayananPasienDetail();
                        $new_PPD->norec = $new_PPD->generateNewId();
                    } else {
                        $new_PPD = PelayananPasienDetail::where('norec', $itemKomponen['norec'])->first();
                        if (empty($new_PPD)) {
                            $new_PPD = new PelayananPasienDetail;
                            $new_PPD->norec = $new_PPD->generateNewId();
                        }
                    }

                    $new_PPD->kdprofile = $idProfile;
                    $new_PPD->statusenabled = true;
                    $new_PPD->noregistrasifk = $item['norec_apd'];
                    $new_PPD->tglregistrasi = date_create(date('Y-m-d H:i:s', strtotime($item['tglregistrasi'])));
                    $new_PPD->aturanpakai = null;
                    $new_PPD->hargadiscount = $item['diskon'];
                    $new_PPD->hargajual = $itemKomponen['hargasatuan'];
                    $new_PPD->hargasatuan = $itemKomponen['hargasatuan'];
                    $new_PPD->komphargasebelumcito = $itemKomponen['komphargasebelumcito'] ?? null;
                    $new_PPD->jumlah = 1;
                    $new_PPD->komponenhargafk = $itemKomponen['objectkomponenhargafk'];
                    $new_PPD->pelayananpasien = $new->norec;
                    $new_PPD->piutangpenjamin = 0;
                    $new_PPD->piutangrumahsakit = 0;
                    $new_PPD->produkfk = $item['produkfk'];
                    $new_PPD->stock = 1;
                    $new_PPD->tglpelayanan = date_create(date('Y-m-d H:i:s', strtotime($item['tglpelayanan'])));
                    $new_PPD->harganetto = $itemKomponen['hargasatuan'];
                    $new_PPD->hargadijamin = isset($itemKomponen['hargadijamin']) ? $itemKomponen['hargadijamin'] : null;
                    //? Tidak dipakai, karena sudah dikalkulasi di frontend
                    // if ($item['iscito'] == true) {
                    //     if ($tuslah > 0) {
                    //         $penjumlahanJasa = ($itemKomponen['hargasatuan'] - $item['diskon']) * $nilaiCito;
                    //         $penjumlahanJasaTuslah = (((float) $itemKomponen['hargasatuan'] * (int) $tuslah) / 100);
                    //         $totalJasa = $totalJasa + $penjumlahanJasa + $penjumlahanJasaTuslah;
                    //         $new_PPD->jasa = $penjumlahanJasa + $penjumlahanJasaTuslah;
                    //     } else {
                    //         $penjumlahanJasa = ($itemKomponen['hargasatuan'] - $item['diskon']) * $nilaiCito;
                    //         $new_PPD->jasa = $penjumlahanJasa;
                    //         $totalJasa = $totalJasa + $penjumlahanJasa;
                    //     }
                    // } else {
                    //     if ($tuslah > 0) {
                    //         $penjumlahanJasaTuslah = ((float) $itemKomponen['hargasatuan'] * (int) $tuslah) / 100;
                    //         $new_PPD->jasa = $penjumlahanJasaTuslah;
                    //         $totalJasa = $totalJasa + $penjumlahanJasaTuslah;
                    //     } else {
                    //         $penjumlahanJasa = 0;
                    //         $new_PPD->jasa = $penjumlahanJasa;
                    //         $totalJasa = $totalJasa + $penjumlahanJasa;
                    //     }
                    // }
                    $new_PPD->noregistrasi = $r['noregistrasi'];
                    $new_PPD->save();
                    $transStatus = 'true';
                }


                if ($item['iscito'] == true) {
                    $dataaa = PelayananPasienDetail::where('pelayananpasien', $new->norec)->get();
                    foreach ($dataaa as $itemss) {
                        $totJasa = $totJasa + $itemss->jasa;
                    }
                    PelayananPasien::where('norec', $new->norec)->update(['jasa' => $totalJasa]);
                }
                if ($tuslah > 0) {
                    PelayananPasien::where('norec', $new->norec)->update([
                        'istuslah' => 1,
                        'jasa' => $totalJasa
                    ]);
                }
                if ($item['diskon'] != 0) {
                    PelayananPasienDetail::where('pelayananpasien', $new->norec)
                        ->where('komponenhargafk', $komponenHargaJasaDokter)
                        ->update(['hargadiscount' => $item['diskon']]);
                }
                $totalInsert++;
            }
            AntrianPasienDiperiksa::where('norec', $r['pelayananpasien'][0]['norec_apd'])->update(['ispelayananpasien' => true, 'status' => 'Selesai']);
            $transStatus = 'true';
        } catch (\Exception $e) {
            $transStatus = 'false';
        }

        if ($transStatus == 'true') {
            $transMessage = "Sukses";
            DB::commit();
            $result = array(
                "status" => 200,
                "result" => array(
                    "data" => $new,
                    "totalInsert" => $totalInsert,
                    "norec_pp" => $noreckun,
                    "norec_pp_lab" => $noreckunlab,
                    "norec_pp_rad" => $noreckunrad,
                    "darah" => empty($test) ? null : $test,
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
    public function saveTindakanOperasi(Request $r)
    {
        DB::beginTransaction();
        try {
            $idProfile = (int) $this->kdProfile;
            $kpID = $this->settingFix('idKelompokTransaksiPelayanan');
            $tuslah = (float) $this->settingFix('persenUpTuslah');
            $nilaiCito = (float) $this->settingFix('tarifCito');
            $komponenHargaJasaDokter = $this->settingFix('komponenHargaJasaDokter');
            $alurflag = $r['flag'];

            $totalJasa = 0;
            $totJasa = 0;
            $penjumlahanJasa = 0;
            $penjumlahanJasaTuslah = 0;
            $noreckun = [];
            $noreckunlab = [];
            $noreckunrad = [];
            $new = null;
            $totalInsert = 0;
            foreach ($r['pelayananpasien'] as $item) {
                $totalJasa = 0;
                $totJasa = 0;
                $penjumlahanJasa = 0;
                $penjumlahanJasaTuslah = 0;
                if (!isset($item['norec_pp']) || $item['norec_pp'] == '') {
                    if (isset($item['limit']) && $item['limit'] == true) {
                        $new = new PelayananPasienLimit();
                    } else {
                        $new = new PelayananPasien();
                    }
                    $new->norec = $new->generateNewId();
                    // $r
                } else {
                    if (isset($item['limit']) && $item['limit'] == true) {
                        $new = PelayananPasienLimit::where('norec', $item['norec_pp'])->first();
                    } else {
                        $new = PelayananPasien::where('norec', $item['norec_pp'])->first();
                    }
                }
                $new->kdprofile = 1;
                $new->statusenabled = true;
                $norec_apd = '';
                if ($item['objectruanganfk'] != null && $item['isPaketTambah'] == true) {
                    $dataapd = AntrianPasienDiperiksa::where('noregistrasifk', $item['norec_pd'])->where('objectruanganfk', $item['objectruanganfk'])->first();
                    if (!empty($dataapd)) {
                        $norec_apd = $dataapd->norec;
                    } else {
                        $max = AntrianPasienDiperiksa::where('objectruanganfk', $item['objectruanganfk'])
                            ->where('tglregistrasi', '>=', date('Y-m-d', strtotime($item['tglregistrasi'])) . ' 00:00')
                            ->where('tglregistrasi', '<=', date('Y-m-d', strtotime($item['tglregistrasi'])) . ' 23:59')
                            ->where('statusenabled', true)
                            ->max('noantrian');
                        $noAntrian = $max + 1;

                        $model_APD = new AntrianPasienDiperiksa;
                        $model_APD->norec = $model_APD->generateNewId();
                        $model_APD->kdprofile = $idProfile;
                        $model_APD->statusenabled = true;
                        $model_APD->noantrian = $noAntrian;
                        $model_APD->objectasalrujukanfk = 23;
                        $model_APD->objectkelasfk = 6;
                        $model_APD->objectkamarfk = null;
                        $model_APD->objectruanganfk = $item['objectruanganfk'];
                        $model_APD->tglkeluar = date_create(date('Y-m-d H:i:s', strtotime($item['tglregistrasi'])));
                        $model_APD->noregistrasifk = $item['norec_pd'];
                        $model_APD->objectpegawaifk = null;
                        $model_APD->statusantrian = 0;
                        $model_APD->status = "Belum Dipanggil";
                        $model_APD->statuskunjungan = null;
                        $model_APD->iskonsul = true;
                        $model_APD->isaskepnurse = true;
                        $model_APD->tglregistrasi = date_create(date('Y-m-d H:i:s', strtotime($item['tglregistrasi'])));
                        $model_APD->tglmasuk = date_create(date('Y-m-d H:i:s', strtotime($item['tglregistrasi'])));
                        $model_APD->israwatgabung = false;
                        $model_APD->save();

                        $norec_apd = $model_APD->norec;
                    }
                } else {
                    $norec_apd = $item['norec_apd'];
                }
                $new->noregistrasifk = $norec_apd;
                $new->tglregistrasi = date_create(date('Y-m-d H:i:s', strtotime($item['tglregistrasi'])));
                $new->hargadiscount = $item['diskon'];
                $new->hargajual = $item['hargasatuan'];
                $new->hargasatuan = $item['hargasatuan'];
                $new->jumlah = $item['jumlah'];
                $new->kelasfk = $item['kelasfk'];
                $new->kdkelompoktransaksi = $kpID;
                $new->keteranganlain = 'Tindakan Operasi';
                $new->piutangpenjamin = 0;
                $new->piutangrumahsakit = 0;
                $new->produkfk = $item['produkfk'];
                $new->stock = 1;
                $new->tglpelayanan = date_create(date('Y-m-d H:i:s', strtotime($item['tglpelayanan'])));
                $new->harganetto = $item['hargasatuan'];
                $new->iscito = $item['iscito'];
                $new->isbedah = true;
                $new->dpjp = $item['dokter'];
                $new->isasa1 = $item['asa1'] ?? null;
                $new->isasa2 = $item['asa2'] ?? null;
                $new->isasa3 = $item['asa3'] ?? null;
                $new->isasa4 = $item['asa4'] ?? null;
                $new->isasa0 = $item['asa0'] ?? null;
                // $new->pelayananpegawaifk = isset($item['pelaksana']) ? $item['pelaksana']['value'] : null;
                $new->isparamedis = $item['isparamedis'];
                $new->jenispelayananfk = isset($item['jenispelayananfk']) ? $item['jenispelayananfk'] : null;
                $new->istarifdetault = isset($item['istarifdetault']) ? $item['istarifdetault'] : null;
                $new->hargadijamin = isset($item['hargadijamin']) ? $item['hargadijamin'] : null;
                $new->noregistrasi = $r['noregistrasi'];
                $new->jasa = 0;
                $new->petugas = $this->getNamaPegawai();
                $new->objecthargaprodukfk = $item['id_hnp'] ?? null;
                $new->save();

                $noreckun[] = $new->norec;

                $this->LOGGING(
                    'Tambah Tindakan Operasi',
                    $new->norec,
                    'pelayananpasien_t',
                    'Tambah Tindakan ' . $item['namaproduk'] . ' di ' . $r['namaruangan'] . ' pada Pasien ' .
                    $r['namapasien'] . ' (' . $r['nocm'] . ') - ' . $r['noregistrasi']
                );

                foreach ($item['pelayananpetugas'] as $items) {
                    foreach ($items['listPegawai'] as $itemsPPP) {
                        if (empty($itemsPPP['norec_ppp']) || $itemsPPP['norec_ppp'] == '') {
                            $new_PPP = new PelayananPasienPetugas();
                            $new_PPP->norec = $new_PPP->generateNewId();
                        } else {
                            $new_PPP = PelayananPasienPetugas::where('norec', $itemsPPP['norec_ppp'])->first();
                            if (empty($new_PPP)) {
                                $new_PPP = new PelayananPasienPetugas;
                                $new_PPP->norec = $new_PPP->generateNewId();
                            }
                        }

                        $new_PPP->kdprofile = $idProfile;
                        $new_PPP->statusenabled = true;
                        $new_PPP->nomasukfk = $item['norec_apd'];
                        $new_PPP->objectjenispetugaspefk = $itemsPPP['jenispelaksana'];
                        $new_PPP->objectpegawaifk = $itemsPPP['value'];
                        $new_PPP->pelayananpasien = $new->norec;
                        $new_PPP->noregistrasi = $r['noregistrasi'];
                        $new_PPP->save();
                    }
                }

                foreach ($item['komponenharga'] as $itemKomponen) {
                    if (!isset($item['norec_pp']) || $item['norec_pp'] == '') {
                        $new_PPD = new PelayananPasienDetail();
                        $new_PPD->norec = $new_PPD->generateNewId();
                    } else {
                        $new_PPD = PelayananPasienDetail::where('norec', $itemKomponen['norec'])->first();
                        if (empty($new_PPD)) {
                            $new_PPD = new PelayananPasienDetail;
                            $new_PPD->norec = $new_PPD->generateNewId();
                        }
                    }

                    $new_PPD->kdprofile = $idProfile;
                    $new_PPD->statusenabled = true;
                    $new_PPD->noregistrasifk = $item['norec_apd'];
                    $new_PPD->tglregistrasi = date_create(date('Y-m-d H:i:s', strtotime($item['tglregistrasi'])));
                    $new_PPD->aturanpakai = null;
                    $new_PPD->hargadiscount = $item['diskon'];
                    $new_PPD->hargajual = $itemKomponen['hargasatuan'];
                    $new_PPD->hargasatuan = $itemKomponen['hargasatuan'];
                    $new_PPD->jumlah = 1;
                    $new_PPD->komponenhargafk = $itemKomponen['objectkomponenhargafk'];
                    $new_PPD->pelayananpasien = $new->norec;
                    $new_PPD->piutangpenjamin = 0;
                    $new_PPD->piutangrumahsakit = 0;
                    $new_PPD->produkfk = $item['produkfk'];
                    $new_PPD->stock = 1;
                    $new_PPD->tglpelayanan = date_create(date('Y-m-d H:i:s', strtotime($item['tglpelayanan'])));
                    $new_PPD->harganetto = $itemKomponen['hargasatuan'];
                    $new_PPD->hargadijamin = isset($itemKomponen['hargadijamin']) ? $itemKomponen['hargadijamin'] : null;
                    if ($item['iscito'] == true) {
                        if ($tuslah > 0) {
                            $penjumlahanJasa = ($itemKomponen['hargasatuan'] - $item['diskon']) * $nilaiCito;
                            $penjumlahanJasaTuslah = (((float) $itemKomponen['hargasatuan'] * (int) $tuslah) / 100);
                            $totalJasa = $totalJasa + $penjumlahanJasa + $penjumlahanJasaTuslah;
                            $new_PPD->jasa = $penjumlahanJasa + $penjumlahanJasaTuslah;
                        } else {
                            $penjumlahanJasa = ($itemKomponen['hargasatuan'] - $item['diskon']) * $nilaiCito;
                            $new_PPD->jasa = $penjumlahanJasa;
                            $totalJasa = $totalJasa + $penjumlahanJasa;
                        }
                    } else {
                        if ($tuslah > 0) {
                            $penjumlahanJasaTuslah = ((float) $itemKomponen['hargasatuan'] * (int) $tuslah) / 100;
                            $new_PPD->jasa = $penjumlahanJasaTuslah;
                            $totalJasa = $totalJasa + $penjumlahanJasaTuslah;
                        } else {
                            $penjumlahanJasa = 0;
                            $new_PPD->jasa = $penjumlahanJasa;
                            $totalJasa = $totalJasa + $penjumlahanJasa;
                        }
                    }
                    $new_PPD->noregistrasi = $r['noregistrasi'];
                    $new_PPD->save();
                    $transStatus = 'true';
                }

                if ($item['iscito'] == true) {
                    $dataaa = PelayananPasienDetail::where('pelayananpasien', $new->norec)->get();
                    foreach ($dataaa as $itemss) {
                        $totJasa = $totJasa + $itemss->jasa;
                    }
                    PelayananPasien::where('norec', $new->norec)->update(['jasa' => $totalJasa]);
                }

                if ($tuslah > 0) {
                    PelayananPasien::where('norec', $new->norec)->update([
                        'istuslah' => 1,
                        'jasa' => $totalJasa
                    ]);
                }

                if ($item['diskon'] != 0) {
                    PelayananPasienDetail::where('pelayananpasien', $new->norec)
                        ->where('komponenhargafk', $komponenHargaJasaDokter)
                        ->update(['hargadiscount' => $item['diskon']]);
                }
                $totalInsert++;
            }

            AntrianPasienDiperiksa::where('norec',$r['pelayananpasien'][0]['norec_apd'])
                ->update([
                    'ispelayananpasien' => true,
                    'status' => 'Selesai',
                ]);

            $transStatus = 'true';
        } catch (\Exception $e) {
            $transStatus = 'false';
        }


        if ($transStatus == 'true') {
            $transMessage = "Sukses";
            DB::commit();
            $result = array(
                "status" => 200,
                "result" => array(
                    "data" => $new,
                    "totalInsert" => $totalInsert,
                    "norec_pp" => $noreckun,
                    "norec_pp_lab" => $noreckunlab,
                    "norec_pp_rad" => $noreckunrad,
                    "darah" => empty($test) ? null : $test,
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

    public function listPaket(Request $request)
    {
        $kdProfile = (int) $this->kdProfile;
        $data = DB::table('paket_m as mpr')
            ->select('mpr.id', 'mpr.namapaket', 'mpr.harga')
            ->where('mpr.kdprofile', $kdProfile)
            ->where('mpr.statusenabled', true)
            ->orderBy('mpr.namapaket', 'ASC');

        // if (isset($request['flag']) && $request['flag'] != '' && $request['flag'] == 'tindakan') {
        //     $data = $data->whereNull('mpr.objectjenispaketfk');
        // }

        //Uncomment jika dibutuhkan

        if (!empty($request['flag']) && $request['flag'] == 'lab') {
            $data = $data->where('mpr.objectjenispaketfk', 4);
        } else if (!empty($request['flag']) && $request['flag'] == 'rad') {
            $data = $data->where('mpr.objectjenispaketfk', 5);
        }

        $data = $data->get();

        $data2 = [];
        foreach ($data as $item) {
            $idPaket = $item->id;
            $details = DB::select(DB::raw("select
                    maps.id,prd.namaproduk, maps.objectprodukfk, maps.objectruanganfk ,prd.objectdetailjenisprodukfk
                    from mappakettoproduk_m as maps
                    join produk_m as prd on prd.id =maps.objectprodukfk
                    where maps.kdprofile = $kdProfile and maps.objectpaketfk='$idPaket'
                    and maps.statusenabled = true
                     and maps.statusenabled = true"));
            if (count($details) > 0) {
                $data2[] = array(
                    'id' =>   $item->id,
                    'namapaket' =>   $item->namapaket,
                    'jml' => count($details),
                    'hargapaket' => $item->harga == null ? 0 : (float) $item->harga,
                    'details' => $details
                );
            }
        }

        return $this->respond($data2);
    }

    public function getTempTindakan(Request $r)
    {
        $data = TempTindakan::where('norec_pd', $r['norec_pd'])
            ->where('nocmfk', $r['nocmfk'])
            // ->whereBetween(DB::raw("CAST(tanggal as Date)"), [date('Y-m-d') . ' 00:00:00', date('Y-m-d') . ' 23:59:59'])
            // ->whereBetween('tanggal', ,date('Y-m-d'))
            ->first();
        // return $data;

        // return $data;
        $dataArray = [];
        $result = [];
        // return $dataArray;
        //UPDATE / AMBIL HARGA
        if (isset($data)) {
            // $result = [
            //     "norec" => $data->norec,
            //     "norec_pd" => $data->norec_pd,
            //     "nocmfk" => $data->nocmfk,
            //     "tanggal" => $data->tanggal,
            //     "issaved" => $data->issaved,
            //     "created_at" => $data->created_at,
            //     "updated_at" => $data->updated_at
            // ];

            $dataArray = $data->data;
            foreach ($dataArray as $s => $v) {
                $dataArray[$s]['tanggal'] = date('d-m-Y', strtotime($v['tanggal']));
            }
            // if(count($dataArray) > 0) {
            //     $idHarga = [];
            //     $idTindakan = [];
            //     // $kelasfk = null;

            //     foreach($dataArray as $key => $val) {
            //         $idTindakan[] = $val['id'];
            //         // $ruangan = $val['objectruanganfk'];
            //         foreach($val['komponenharga'] as $s) {
            //             $idHarga[] = $s['objectkomponenhargafk'];
            //         }
            //     }
            //     $getTindakan = DB::table('harganettoprodukbykelas_m')
            //     ->select(
            //         'objectprodukfk',
            //         'hargasatuan',
            //         'hargadijamin',
            //         DB::raw("CASE WHEN hargadiscount IS NULL THEN 0 ELSE hargadiscount END AS diskon")
            //     )
            //     ->whereIn('objectprodukfk', $idTindakan)
            //     // ->where('objectruanganfk')
            //     ->get()
            //     ->keyBy('objectprodukfk')
            //     ->toArray();
            //     // return $getTindakan;
            //     $getHarga = DB::table('harganettoprodukbykelasd_m')
            //         ->select(
            //             'id',
            //             'hargasatuan',
            //             'hargadijamin'
            //         )
            //         ->whereIn('id', $idHarga)
            //         ->get()
            //         ->keyBy('id')
            //         ->toArray();
            //     foreach($dataArray as $keyArr => $dtArr) {
            //         $dataArray[$keyArr]['hargasatuan'] = isset($getTindakan[$dtArr['id']]) ? $getTindakan[$dtArr['id']]->hargasatuan : $dtArr['hargasatuan'];
            //         $dataArray[$keyArr]['hargadijamin'] = isset($getTindakan[$dtArr['id']]) ? $getTindakan[$dtArr['id']]->hargadijamin : $dtArr['hargadijamin'];
            //         foreach($dtArr['komponenharga'] as $keyKom => $dtKom) {
            //             $dataArray[$keyArr]['komponenharga'][$keyKom]['hargasatuan'] = isset($getHarga[$dtKom['objectkomponenhargafk']]) ? $getHarga[$dtKom['objectkomponenhargafk']]->hargasatuan : $dtKom['hargasatuan'];
            //             $dataArray[$keyArr]['komponenharga'][$keyKom]['hargadijamin'] = isset($getHarga[$dtKom['objectkomponenhargafk']]) ? $getHarga[$dtKom['objectkomponenhargafk']]->hargadijamin : $dtKom['hargadijamin'];
            //         }
            //     }
            //     // return $dataArray;

            //     // $idharga = array_columns()
            // }
            $result["data"] = array_values($dataArray);
            $result["issaved"] = $data->issaved;
        }
        // $res = $data->data ?? [];
        return $this->respond($result);
    }

    public function saveTempTindakan(Request $r)
    {
        try {
            $data = TempTindakan::updateOrCreate([
                'norec_pd' => $r['norec_pd'],
                // 'tanggal' => date("Y-m-d"),
            ], [
                'norec' => (new TempTindakan())->generateNewId(),
                'issaved' => $r['issaved'],
                'data' => $r['data'],
                'nocmfk' => $r['nocmfk'],
                'tanggal' => date('Y-m-d')
            ]);

            // $data = TempTindakan::where('norec_pd', $r['norec_pd'])
            // ->where('tanggal', date("Y-m-d"))
            // ->first();
            // // return $data;
            // if(!isset($data)) {
            //     $data = new TempTindakan();
            //     $data->norec = $data->generateNewId();
            //     $data->issaved = $r['issaved'];
            //     $data->data = $r['data'];
            //     $data->nocmfk = $r['nocmfk'];
            //     $data->save();
            // }else {
            //     $data->issaved = $r['issaved'];
            //     $data->data = $r['data'];
            //     $data->
            //     // $newArr = [];
            //     // foreach($data->data as $k => $val) {
            //     //     // return $val;
            //     // }
            // }

            return $this->respond($data);
        } catch (\Exception $e) {
            return $this->respond([
                "code" => 500,
                "message" => $e->getMessage()
            ], 500);
        }
    }

    public function updateTempTindakan(Request $r)
    {
        try {
            $data = TempTindakan::where('norec_pd', $r['norec_pd'])
                ->where('nocmfk', $r['nocmfk'])
                ->whereDate('tanggal', '=', date('Y-m-d'))
                ->first();

            if (isset($data)) {
                // $data->data = $r['data'];
                $newArr = [];
                foreach ($r['data'] as $k => $det) {
                    $newArr[] = $det;
                    $newArr[$k]['isNewAdded'] = false;
                    // $det['isNewAdded'] = false;
                }
                $data->data = $newArr;
                $data->issaved = true;
                $data->update();
            }

            return $this->respond($data);
        } catch (\Exception $e) {
            return $this->respond([
                "code" => 500,
                "message" => $e->getMessage()
            ], 500);
        }
    }

    public function guessPelaksana(Request $r)
    {
        if ($r['tanggal'] == "Invalid date" || $r['tanggal'] == 'undefined') {
            $r['tanggal'] = date('d-m-Y');
        }
        $data = PelayananPasien::where('noregistrasifk', $r['norec_apd'])
            ->where('jumlah', $r['jumlah'])
            ->where('produkfk', $r['id'])
            ->whereDate('tglpelayanan', '=', $r['tanggal'])
            ->where('statusenabled', true)
            ->first();

        return $this->respond($data);
    }

    public function listPetugas(Request $r)
    {
        return $this->respond(Pegawai::select('id as value', 'namalengkap as label')->where('statusenabled', true)->orderBy('id')->get());
    }
}
