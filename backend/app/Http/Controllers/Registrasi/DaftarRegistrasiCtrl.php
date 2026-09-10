<?php

namespace App\Http\Controllers\Registrasi;

use App\Http\Controllers\Controller;
use App\Models\Master\AsalRujukan;
use App\Models\Master\Departemen;
use App\Models\Master\JenisPelayanan;
use App\Models\Master\Kelas;
use App\Models\Master\KelompokPasien;
use App\Models\Master\KelompokTransaksi;
use App\Models\Master\Pegawai;
use App\Models\Master\Ruangan;
use App\Models\Transaksi\AntrianPasienDiperiksa;
use App\Models\Transaksi\PasienDaftar;
use App\Models\Transaksi\PelayananPasienPetugas;
use App\Models\Transaksi\RegistrasiPelayananPasien;
use App\Traits\Valet;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DaftarRegistrasiCtrl extends Controller
{
    use Valet;
    public function __construct()
    {
        parent::__construct($is_encrypt = true);
    }
    public function listRegistrasi(Request $r)
    {
        $data  = DB::table('pasiendaftar_t as pd')
            ->join('pasien_m as ps', 'ps.id', '=', 'pd.nocmfk')
            ->join('ruangan_m as ru', 'ru.id', '=', 'pd.objectruanganlastfk')
            ->leftjoin('pegawai_m as pg', 'pg.id', '=', 'pd.objectpegawaifk')
            ->join('kelompokpasien_m as kp', 'kp.id', '=', 'pd.objectkelompokpasienlastfk')
            ->join('kelas_m as kls', 'kls.id', '=', 'pd.objectkelasfk')
            ->leftjoin('pemakaianasuransi_t as pa', 'pa.noregistrasifk', '=', 'pd.norec')
            ->leftjoin('rekanan_m as rek', 'rek.id', '=', 'pd.objectrekananfk')
            ->leftjoin('asuransipasien_m as asu', 'pa.objectasuransipasienfk', '=', 'asu.id')
            ->leftjoin('kelas_m as klstg', 'klstg.id', '=', 'asu.objectkelasdijaminfk')
            ->leftJoin('antrianpasiendiperiksa_t as apd', function ($join) {
                $join->on('apd.noregistrasifk', '=', 'pd.norec');
                $join->on('apd.objectruanganfk', '=', 'pd.objectruanganlastfk');
                $join->whereNull('apd.objectruanganasalfk');
            })
            ->leftjoin('jenispelayanan_m as jpl', 'jpl.id', '=', 'pd.jenispelayanan')
            ->leftJOIN('jeniskelamin_m as jks', 'jks.id', '=', 'ps.objectjeniskelaminfk')
            ->distinct()
            ->select(
                'pd.norec',
                'pd.statusenabled',
                'pd.tglregistrasi',
                'ps.nocm',
                'pd.nocmfk',
                'pd.noregistrasi',
                'ru.namaruangan',
                'ps.namapasien',
                'kp.kelompokpasien',
                'rek.namarekanan',
                'pg.namalengkap as namadokter',
                'pd.tglpulang',
                'pd.statuspasien',
                'pa.norec as norec_pa',
                'pa.objectasuransipasienfk',
                'pd.objectkelompokpasienlastfk',
                'pd.objectpegawaifk as pgid',
                'pd.objectruanganlastfk',
                'pa.nosep',
                'pd.nostruklastfk',
                'klstg.namakelas as kelasditanggung',
                'kls.namakelas',
                'ps.tgllahir',
                'ru.objectdepartemenfk',
                'pd.objectkelasfk',
                'pa.ppkrujukan',
                'ru.icons',
                'jpl.jenispelayanan',
                'pa.objectdiagnosafk as iddiagnosabpjs',
                'ps.nobpjs',
                'jks.jeniskelamin',
                'apd.norec as norec_apd'
            )

            ->where('apd.statusenabled', true)
            ->where('pd.statusenabled', true)
            ->where('pd.kdprofile', (int) $this->kdProfile);

        if (isset($r['dari']) && $r['dari'] != "" && $r['dari'] != "undefined") {
            $data = $data->where('pd.tglregistrasi', '>=', $r['dari'] . ' 00:00');
        }
        if (isset($r['sampai']) && $r['sampai'] != "" && $r['sampai'] != "undefined") {
            $data = $data->where('pd.tglregistrasi', '<=', $r['sampai'] . ' 23:59');
        }
        if (isset($r['dep']) && $r['dep'] != "" && $r['dep'] != "undefined") {
            $data = $data->where('ru.objectdepartemenfk', '=', $r['dep']);
        }
        if (isset($r['ruang']) && $r['ruang'] != "" && $r['ruang'] != "undefined") {
            $data = $data->where('ru.id', '=', $r['ruang']);
        }
        if (isset($r['kelompokpasienfk']) && $r['kelompokpasienfk'] != "" && $r['kelompokpasienfk'] != "undefined") {

            $data = $data->whereIn('kp.id', explode(',', $r['kelompokpasienfk']));
        }
        if (isset($r['dokterfk']) && $r['dokterfk'] != "" && $r['dokterfk'] != "undefined") {
            $data = $data->where('pg.id', '=', $r['dokterfk']);
        }
        if (isset($r['statuspasien']) && $r['statuspasien'] != "" && $r['statuspasien'] != "undefined") {
            $data = $data->where('pd.statuspasien', '=', $r['statuspasien']);
        }
        if (isset($r['noreg']) && $r['noreg'] != "" && $r['noreg'] != "undefined") {
            $data = $data->where('pd.noregistrasi', '=', $r['noreg']);
        }
        if (isset($r['nocm']) && $r['nocm'] != "" && $r['nocm'] != "undefined") {
            $data = $data->where('ps.nocm', 'ilike', '%' . $r['nocm'] . '%');
        }
        if (isset($r['namapasien']) && $r['namapasien'] != "" && $r['namapasien'] != "undefined") {
            $data = $data->where('ps.namapasien', 'ilike', '%' . $r['namapasien'] . '%');
        }
        if (isset($r['offset']) && $r['offset'] != '') {
            $data = $data->offset($r['offset']);
        }
        if (isset($r['limit']) && $r['limit'] != '') {
            $data = $data->limit($r['limit']);
        }
        $data = $data->orderByDesc('noregistrasi');
        $data = $data->get();
        $result['data'] = $data;
        $result['as'] = '@epic';

        return $this->respond($result);
    }
    public function listRegistrasiDropdown(Request $r)
    {

        $result['listDepartemenPelayanan'] = explode(',', $this->settingFix('listDepartemenPelayanan'));
        $result['departemen'] = Departemen::mine()->get();
        $result['departemen_filter'] = [];
        foreach ($result['departemen'] as $de) {
            foreach ($result['listDepartemenPelayanan'] as $dep) {
                if ($de->id == $dep) {
                    $result['departemen_filter'][] = $de;
                }
            }
        }
        $result['ruangan'] = Ruangan::mine()->get();
        $result['kelompokpasien'] = KelompokPasien::mine()->get();
        // $result['kelas'] = Kelas::mine()->get();
        $result['as'] = '@epic';

        return $this->respond($result);
    }

    public function batalRegistrasi(Request $request)
    {

        DB::beginTransaction();
        try {
            PasienDaftar::where('noregistrasi', $request->noregis)
                ->where('kdprofile', $this->kdProfile)
                ->where('statusenabled', true)
                ->update(['statusenabled' => false]);

            AntrianPasienDiperiksa::where('noregistrasi', $request->noregis)
                ->where('kdprofile', $this->kdProfile)
                ->where('statusenabled', true)
                ->update(['statusenabled' => false]);
            DB::commit();
            $response = [
                "message" => "Registrasi Pasien Berhasil dibatalkan",
                "status" => 200,
                "data" => "Berhasil",
            ];
        } catch (Exception $e) {
            DB::rollBack();
            $response = [
                "message" => "Registrasi Pasien Gagal dibatalkan",
                "status" => 400,
                "data" => $e->getMessage()
            ];
        }

        return $this->respond($response['data'], $response['status'], $response['message']);
    }

    public function ubahDokter(Request $request)
    {

        DB::beginTransaction();
        try {

            $pelayananPasien = DB::select(DB::raw("select pp.norec,pp.tglpelayanan, prd.namaproduk,
                ppp.objectpegawaifk,apd.norec as norec_apd,pp.produkfk,  apd.objectruanganfk,ru.namaruangan
                from pasiendaftar_t as pd
                INNER JOIN antrianpasiendiperiksa_t as apd on apd.noregistrasifk =pd.norec
                INNER JOIN pelayananpasien_t as pp on apd.norec =pp.noregistrasifk
                inner join produk_m as prd on prd.id=pp.produkfk
                inner join detailjenisproduk_m as djp on djp.id=prd.objectdetailjenisprodukfk
                inner join jenisproduk_m as jp on jp.id=djp.objectjenisprodukfk
                inner join ruangan_m as ru on ru.id=apd.objectruanganfk
                LEFT JOIN pelayananpasienpetugas_t as ppp on ppp.pelayananpasien =pp.norec
                where ppp.objectpegawaifk is null and pd.kdprofile = $this->kdProfile
                and pd.noregistrasi='$request->noregis'"));

            foreach ($pelayananPasien as $item) {
                $PelPasienPetugas = new PelayananPasienPetugas();
                $PelPasienPetugas->norec = $PelPasienPetugas->generateNewId();
                $PelPasienPetugas->kdprofile = $this->kdProfile;
                $PelPasienPetugas->statusenabled = true;
                $PelPasienPetugas->nomasukfk = $item->norec_apd;
                $PelPasienPetugas->objectjenispetugaspefk = 4; //dokter pemeriksa
                $PelPasienPetugas->objectpegawaifk = $request['objectpegawaifk'];
                $PelPasienPetugas->objectprodukfk = $item->produkfk;
                $PelPasienPetugas->objectruanganfk = $item->objectruanganfk;
                $PelPasienPetugas->pelayananpasien = $item->norec;
                $PelPasienPetugas->tglpelayanan = $item->tglpelayanan;
                $PelPasienPetugas->save();

                PelayananPasienPetugas::where('pelayananpasien', $item->norec)->where('kdprofile', $this->kdProfile)
                    ->update(['objectpegawaifk' => $request['objectpegawaifk']]);
            }

            PasienDaftar::where('kdprofile', $this->kdProfile)
                ->where('statusenabled', true)
                ->where('norec', $request->norec_pd)
                ->update([
                    'objectpegawaifk' => $request['objectpegawaifk'],
                    'objectdokterpemeriksafk' => $request['objectpegawaifk']
                ]);
            AntrianPasienDiperiksa::where('kdprofile', $this->kdProfile)
                ->where('statusenabled', true)
                ->where('norec', $request->norec_apd)
                ->update(['objectpegawaifk' => $request->objectpegawaifk]);

            DB::commit();

            $response = [
                "message" => "Dokter Berhasil diubah",
                "status" => 200,
                "data" => "Berhasil",
            ];
        } catch (Exception $exc) {
            DB::rollBack();
            $response = [
                "message" => "Dokter Gagal diubah",
                "status" => 400,
                "data" => $exc->getMessage()
            ];
        }

        return $this->respond($response['data'], $response['status'], $response['message']);
    }

    public function ubahDokterAPD(Request $request)
    {

        DB::beginTransaction();
        try {

            $dataPD = PasienDaftar::where('kdprofile', $this->kdProfile)
                ->where('statusenabled', true)
                ->where('norec', $request['norec_pd'])
                ->first();

            if ($dataPD->objectruanganlastfk == $request['ruanganfk']) {
                PasienDaftar::where('kdprofile', $this->kdProfile)
                    ->where('statusenabled', true)
                    ->where('norec', $request['norec_pd'])
                    ->update(['objectpegawaifk' => $request->objectpegawaifk]);
            }
            AntrianPasienDiperiksa::where('kdprofile', $this->kdProfile)
                ->where('statusenabled', true)
                ->where('norec', $request->norec_apd)
                ->update(['objectpegawaifk' => $request->objectpegawaifk]);

            DB::commit();
            $response = [
                "message" => "Dokter Berhasil diubah",
                "status" => 200,
                "data" => "Berhasil",
            ];
        } catch (Exception $e) {
            DB::rollBack();
            $response = [
                "message" => "Dokter Gagal diubah",
                "status" => 400,
                "data" => $e->getMessage()
            ];
        }

        return $this->respond($response['data'], $response['status'], $response['message']);
    }
    public function ubahDokterDPJP(Request $request)
    {
        DB::beginTransaction();
        try {
            if ($request->konsul == null) {
                $updateData = [
                    'objectpegawaifk' => $request->objectpegawaifk,
                    'objectpegawairawatbersamafk' => $request->objectpegawairawatbersamafk,
                    'objectpegawaifk_dod' => $request->objectpegawaifk_dod,
                ];
            
                if ($request->has('objectpegawairawatbersamadinamisfk')) {
                    $updateData['objectpegawairawatbersamadinamisfk'] = $request->objectpegawairawatbersamadinamisfk;
                }
            
                PasienDaftar::where('kdprofile', $this->kdProfile)
                    ->where('statusenabled', true)
                    ->where('norec', $request->norec_pd)
                    ->update($updateData);
            }
            
            DB::table('antrianpasiendiperiksa_t')
            ->where('norec', $request->norec_apd)
            ->update([
                'objectpegawaifk' => $request->objectpegawaifk
            ]);

            DB::connection('mongodb')
                ->table('LembarMonitoringRehabilitasiMedik')
                ->where('norec', $request->norec_pd)
                ->update([
                    'registrasi.objectpegawaifk' => $request->objectpegawaifk,
                    'registrasi.objectpegawairawatbersamafk' => $request->objectpegawairawatbersamafk,

                ]);

            DB::commit();
            $response = [
                "message" => "Dokter Berhasil diubah",
                "status" => 200,
                "data" => "Berhasil",
            ];
        } catch (Exception $e) {
            DB::rollBack();
            $response = [
                "message" => "Dokter Gagal diubah",
                "status" => 400,
                "data" => $e->getMessage()
            ];
        }

        return $this->respond($response['data'], $response['status'], $response['message']);
    }

    public function tetapkanPerawat(Request $request)
    {
        DB::beginTransaction();
        try {
            PasienDaftar::where('kdprofile', $this->kdProfile)
                ->where('statusenabled', true)
                ->where('norec', $request->norec_pd)
                ->update(['perawatfk' => $request->perawatfk]);

            DB::commit();
            $response = [
                "message" => "Penetapan Perawat Pada Pasien Berhasil",
                "status" => 200,
                "data" => "Berhasil",
            ];
        } catch (Exception $e) {
            DB::rollBack();
            $response = [
                "message" => "Dokter Gagal diubah",
                "status" => 400,
                "data" => $e->getMessage()
            ];
        }

        return $this->respond($response['data'], $response['status'], $response['message']);
    }

    public function detailRegistrasi(Request $request)
    {

        $pelayanan = DB::table('pasiendaftar_t as pd')
            ->join('antrianpasiendiperiksa_t as apd', 'apd.noregistrasifk', '=', 'pd.norec')
            ->leftjoin('pelayananpasien_t as pp', 'pp.noregistrasifk', '=', 'pd.norec')
            ->leftjoin('strukpelayanan_t as sp', 'sp.norec', '=', 'pp.strukfk')
            ->join('pasien_m as pas', 'pas.id', '=', 'pd.nocmfk')
            ->leftjoin('agama_m as ag', 'ag.id', '=', 'pas.objectagamafk')
            ->leftjoin('jeniskelamin_m as jkel', 'jkel.id', '=', 'pas.objectjeniskelaminfk')
            ->leftjoin('kelompokpasien_m as kp', 'kp.id', '=', 'pd.objectkelompokpasienlastfk')
            ->leftjoin('kelas_m as kls2', 'kls2.id', '=', 'pd.objectkelasfk')
            ->join('ruangan_m as ru2', 'ru2.id', '=', 'pd.objectruanganlastfk')
            ->leftjoin('rekanan_m as rk', 'rk.id', '=', 'pd.objectrekananfk')
            ->leftJoin('kelompokpasien_m AS klp', 'klp.id', 'pd.objectkelompokpasienlastfk')
            ->leftjoin('kamar_m as kamar', 'kamar.id', '=', 'apd.objectkamarfk')
            ->leftjoin('pegawai_m as pg', 'pg.id', '=', 'apd.objectpegawaifk')
            ->leftjoin('kebangsaan_m as bg', 'pas.objectkebangsaanfk', '=', 'bg.id')
            ->select(
                'bg.name as kebangsaan',
                'apd.norec as norec_apd',
                'pd.nocmfk',
                'pd.nocmfk',
                'pd.nostruklastfk',
                'ag.id as agid',
                'ag.agama',
                'pas.tgllahir',
                'kp.id as kpid',
                'kp.kelompokpasien as jenisPasien',
                'pas.objectstatusperkawinanfk',
                'pas.namaayah',
                'pas.namasuamiistri',
                'pas.id as pasid',
                'pas.nobpjs  as nobpjs',
                'pas.noidentitas as noidentitas',
                'pas.notelepon as notelepon',
                'pas.alamatrmh as alamatlengkap',
                'pas.nocm as noCm',
                'jkel.id as jkelid',
                'jkel.jeniskelamin',
                'jkel.reportdisplay as jenisKelamin',
                'pd.noregistrasi as noRegistrasi',
                'pas.namapasien as namaPasien',
                'pd.tglregistrasi as tglMasuk',
                'pd.norec as norec_pd',
                'pd.tglpulang as tglPulang',
                'pas.notelepon',
                'pas.nohp',
                'pd.objectrekananfk as rekananid',
                'pas.noidentitas',
                // 'pas.nobpjs',
                'kls2.id as klsid2',
                'kls2.namakelas as kelasRawat',
                'pg.id as pgid',
                'pg.namalengkap as namadokter',
                'rk.namarekanan as namaPenjamin',
                'rk.id as objectrekananfk',
                'klp.namaexternal AS kelompokpasien',
                'pd.objectkelompokpasienlastfk',
                'ru2.namaruangan as lastRuangan',
                'sp.nostruk',
                'sp.norec as strukfk',
                'pd.statuspasien as StatusPasien'
            )
            ->where('pd.kdprofile', $this->kdProfile)
            ->where('pd.statusenabled', true)
            ->where('apd.statusenabled', true)
            ->where('pd.noregistrasi', $request['noregistrasi'])
            ->first();


        $result = array(
            'data' => $pelayanan,
            'message' => 'setiawan@epic',
        );

        return $this->respond($result);
    }
    public function detailRegistrasiPasien(Request $request)
    {
        $data = DB::table('pasiendaftar_t as pd')
            ->join('antrianpasiendiperiksa_t as apd', 'pd.norec', '=', 'apd.noregistrasifk')
            ->leftjoin('pegawai_m as pg', 'pg.id', '=', 'apd.objectpegawaifk')
            ->leftjoin('ruangan_m as ru', 'ru.id', '=', 'apd.objectruanganfk')
            ->leftJoin('departemen_m as dept', 'dept.id', '=', 'ru.objectdepartemenfk')
            ->leftJoin('kamar_m as km', 'km.id', '=', 'apd.objectkamarfk')
            ->leftJoin('kelas_m as kls', 'kls.id', '=', 'apd.objectkelasfk')
            ->leftJoin('kelas_m as klsr', 'klsr.id', '=', 'apd.kelasrawatfk')
            ->leftJoin('pasien_m as pm', 'pm.id', '=', 'pd.nocmfk')
            ->leftJoin('tempattidur_m as tt', 'tt.id', '=', 'apd.nobed')
            ->leftJoin('rekanan_m AS rk', 'rk.id', 'pd.objectrekananfk')
            ->leftJoin('kelompokpasien_m AS klp', 'klp.id', 'pd.objectkelompokpasienlastfk')
            ->select(
                'ru.id as ruid_asal',
                'ru.namaruangan',
                'kls.id as kelasid',
                'kls.namakelas',
                'klsr.id as kelasrawatid',
                'klsr.namakelas as namakelasrawat',
                'km.namakamar',
                'tt.reportdisplay as nobed',
                'apd.norec',
                'apd.objectkamarfk',
                'apd.tglregistrasi',
                'apd.statusantrian',
                'apd.statuskunjungan',
                'apd.tgldipanggildokter',
                'apd.tgldipanggilsuster',
                'apd.israwatgabung',
                'apd.tglmasuk',
                'apd.tglkeluar',
                'apd.kelasrawatfk',
                'pg.id as pgid',
                'pg.namalengkap as namadokter',
                'apd.objectasalrujukanfk',
                'pd.norec as norec_pd',
                'pd.nostruklastfk',
                'pd.nosbmlastfk',
                'pd.nocmfk',
                'pd.iskelastitip',
                'pd.isnaikkelas',
                'ru.objectdepartemenfk',
                'dept.namadepartemen',
                'pm.tglmeninggal',
                'rk.namarekanan AS jenisrekanan',
                'klp.namaexternal AS kelompokpasien',
                'pd.objectkelompokpasienlastfk',
                'pd.objectrekananfk',
                DB::raw("
                    FLOOR(
                        EXTRACT(EPOCH FROM (
                            COALESCE(apd.tglkeluar, NOW()) - apd.tglmasuk
                        ))::INTEGER / (24 * 3600)
                    ) || ' hari ' ||
                    FLOOR(
                        EXTRACT(EPOCH FROM (
                            COALESCE(apd.tglkeluar, NOW()) - apd.tglmasuk
                        ))::INTEGER % (24 * 3600) / 3600
                    ) || ' jam ' ||
                    FLOOR(
                        EXTRACT(EPOCH FROM (
                            COALESCE(apd.tglkeluar, NOW()) - apd.tglmasuk
                        ))::INTEGER % 3600 / 60
                    ) || ' menit' 
                    AS selisihwaktu
                ")
            )
            ->where('pd.kdprofile', $this->kdProfile)
            ->where('pd.statusenabled', true)
            ->where('apd.statusenabled', true)
            ->where('pd.noregistrasi', $request['noregistrasi'])
            ->orderBy('apd.tglmasuk', 'asc')
            ->get();

        return $this->respond($data);
    }

    public function getDataComboDetailRegis(Request $request)
    {
        $ruangan = Ruangan::mine()->where('statusenabled', true)->where('kdprofile', $this->kdProfile)->get();
        $kelas = Kelas::mine()->where('statusenabled', true)->where('kdprofile', $this->kdProfile)->get();
        $dokter = Pegawai::mine()->where('statusenabled', true)->where('kdprofile', $this->kdProfile)->where('objectjenispegawaifk', $this->settingFix('idJenisPegawaiDokter'))->get();

        $response = [
            "ruangan" => $ruangan,
            "kelas" => $kelas,
            "dokter" => $dokter
        ];

        return $this->respond($response);
    }

    public function simpanKonsul(Request $request)
    {
        DB::beginTransaction();
        try {
            $pd = PasienDaftar::where('norec', $request['norec_pd'])->first();

            if (isset($request['norec']) && $request['norec'] != '') {
                $dataAPD = AntrianPasienDiperiksa::where('norec', '=', $request['norec'])->first();
            } else {
                $dataAPD = new AntrianPasienDiperiksa;
                $dataAPD->norec = $dataAPD->generateNewId();
                $dataAPD->kdprofile = $this->kdProfile;
                $dataAPD->statusenabled = true;
            }

            $dataAPD->objectasalrujukanfk = $request['asalRujukanfk'];
            $dataAPD->objectkelasfk = $request['kelasfk'];

            $max = AntrianPasienDiperiksa::where('objectruanganfk', $request['objectruangantujuan'])
                ->whereBetween('tglregistrasi', [
                    now()->format('Y-m-d 00:00:00'), // Start of today
                    now()->format('Y-m-d 23:59:59')  // End of today
                ])
                ->where('statusenabled', true)
                ->max('noantrian');;

            $noAntrian = $max + 1;
            $dataAPD->noantrian = $noAntrian;
            $dataAPD->noregistrasifk = $request['norec_pd'];
            $dataAPD->objectpegawaifk = $request['dokterfk'];
            $dataAPD->objectruanganfk = $request['objectruangantujuan'];
            $dataAPD->statusantrian = 0;
            $dataAPD->statuspasien = 1;
            $dataAPD->iskonsul = true;
            $dataAPD->isaskepnurse = true;
            $dataAPD->iscppt_perawat = null;
            $dataAPD->iscppt_dokter = null;
            $dataAPD->statuskunjungan = 'LAMA';
            $dataAPD->statuspenyakit = 'BARU';
            $dataAPD->objectruanganasalfk = $request['objectruanganasalfk'];;
            $dataAPD->tglregistrasi = date('Y-m-d H:i:s', strtotime($request['tanggalKonsul']));  //$pd->tglregistrasi;
            $dataAPD->tglkeluar = date('Y-m-d H:i:s', strtotime($request['tanggalKonsul']));
            $dataAPD->tglmasuk = date('Y-m-d H:i:s', strtotime($request['tanggalKonsul']));
            $dataAPD->noregistrasi = $pd->noregistrasi;
            $dataAPD->save();

            DB::commit();
            $response = [
                "status" => 200,
                "message" => "Registrasi Konsul Berhasil",
                "data" => $dataAPD
            ];
        } catch (Exception $e) {
            DB::rollBack();
            $response = [
                "status" => 400,
                "message" => "Registrasi Konsul Gagal",
                "data" => $e->getMessage()
            ];
        }

        return $this->respond($response['data'], $response['status'], $response['message']);
    }

    public function hapusAPD(Request $request)
    {

        DB::beginTransaction();

        try {
            $APD_Currently = AntrianPasienDiperiksa::where('norec', $request['norec_apd'])->select('nobed')->first();
            // Get last ruangan, ruangan penunjang tidak termasuk
            $data = DB::table('pasiendaftar_t as pd')
                ->select('apd.objectruanganfk', 'apd.nobed')
                ->join('antrianpasiendiperiksa_t as apd', 'apd.noregistrasifk', '=', 'pd.norec')
                ->where('apd.statusenabled', true)
                ->where('pd.statusenabled', true)
                ->where('pd.norec', $request['norec_pd'])
                ->where('apd.norec', '!=', $request['norec_apd'])
                ->whereNotIn('apd.objectruanganfk', [338, 335, 336, 337, 753, 330, 363]) // Ruangan penunjang tidak termasuk
                ->orderByDesc('apd.tglmasuk')
                ->first();

            // Update bed sebelumnya yang sudah terisi/kosong, menjadi kosong
            if (isset($APD_Currently->nobed) && $APD_Currently->nobed != '') {
                DB::table('tempattidur_m')
                    ->where('kdprofile', (int)$this->kdProfile)
                    ->where('statusenabled', true)
                    ->where('id', $APD_Currently->nobed)
                    ->lockForUpdate()
                    ->update(['objectstatusbedfk' =>  $this->settingFix('idStatusBedKosong')]);
            }

            PasienDaftar::where('norec', $request['norec_pd'])->update(['objectruanganlastfk' => $data->objectruanganfk]);
            AntrianPasienDiperiksa::where('norec', $request['norec_apd'])->update(['statusenabled' => false, 'tglkeluar' => date('Y-m-d H:i:s')]);
            DB::commit();
            $response = [
                "data" => "Terhapus",
                "message" => "Data Berhasil Dihapus",
                "status" => 200,
            ];
        } catch (Exception $e) {
            $response = [
                "data" => "Gagal",
                "message" => "Tidak bisa dihapus, sudah ada tindakannya",
                "status" => 400,
            ];
        }

        return $this->respond($response['data'], $response['status'], $response['message']);
    }

    public function ubahTanggalDetailRegis(Request $request)
    {
        DB::beginTransaction();
        //##Update Pasiendaftar##
        try {
            $dataRPP = RegistrasiPelayananPasien::where('noregistrasifk', $request['norec_pd'])->count();
            if ($request['tglregistrasi']) {
                PasienDaftar::where('noregistrasi', $request['noregistrasi'])->update(['tglregistrasi' => $request['tglregistrasi']]);
                AntrianPasienDiperiksa::where('norec', $request['norec_apd'])->update(['tglregistrasi' => $request['tglregistrasi']]);
            }

            if ($request['tglkeluar'] != '' && $request['tglmasuk'] != '') {
                AntrianPasienDiperiksa::where('norec', $request['norec_apd'])->update(
                    [
                        'tglkeluar' => $request['tglkeluar'],
                        'tglmasuk' => $request['tglmasuk']
                    ]
                );
                $updateWrbPD = PasienDaftar::where('noregistrasi', $request['noregistrasi'])->first();
                if ($updateWrbPD->objectruanganlastfk == $request['ruanganasal']) {
                    $updateWrbPD->tglpulang = $request['tglkeluar'];
                    $updateWrbPD->save();
                }
                if ($dataRPP > 0) {
                    DB::table('registrasipelayananpasien_t')
                        ->select('noregistrasifk', 'objectruanganfk', 'rpp.tglkeluar')
                        ->where('objectruanganfk', $request['ruanganasal'])
                        ->where('noregistrasifk', $request['norec_pd'])
                        ->update(
                            [
                                'tglkeluar' => $request['tglkeluar'],
                                'tglmasuk' => $request['tglmasuk']
                            ]
                        );
                }
            }
            if ($request['tglkeluar'] == '' && $request['tglmasuk'] != '') {
                AntrianPasienDiperiksa::where('norec', $request['norec_apd'])->update(['tglmasuk' => $request['tglmasuk']]);
                if ($dataRPP > 0) {
                    DB::table('registrasipelayananpasien_t')
                        ->select('noregistrasifk', 'objectruanganfk', 'tglkeluar')
                        ->where('objectruanganfk', $request['ruanganasal'])
                        ->where('noregistrasifk', $request['norec_pd'])
                        ->update(['tglmasuk' => $request['tglmasuk']]);
                }
            }
            if ($request['tglkeluar'] != '' && $request['tglmasuk'] == '') {
                AntrianPasienDiperiksa::where('norec', $request['norec_apd'])->update(['tglkeluar' => $request['tglkeluar']]);
                $updateWrbPD = PasienDaftar::where('noregistrasi', $request['noregistrasi'])->first();
                if ($updateWrbPD->objectruanganlastfk == $request['ruanganasal']) {
                    $updateWrbPD->tglpulang = $request['tglkeluar'];
                    $updateWrbPD->save();
                }
                if ($dataRPP  > 0) {
                    DB::table('registrasipelayananpasien_t')
                        ->select('noregistrasifk', 'objectruanganfk', 'tglkeluar')
                        ->where('objectruanganfk', $request['ruanganasal'])
                        ->where('noregistrasifk', $request['norec_pd'])
                        ->update(['tglkeluar' => $request['tglkeluar']]);
                }
            }

            DB::commit();

            $response = [
                "status" => 200,
                "message" => "Tanggal Berhasil diubah",
                "data" => $dataRPP
            ];
        } catch (Exception $e) {
            $response = [
                "status" => 400,
                "message" => "Tanggal Gagal diubah",
                "data" => $e->getMessage()
            ];
        }

        return $this->respond($response['data'], $response['status'], $response['message']);
    }
    public function getDaftarPasienMeninggal2(Request $request)
    {
        $kdProfile = (int)$this->kdProfile;
        $filter = $request->all();
        $data = DB::table('pasiendaftar_t as pd')
            ->join('pasien_m as ps', function ($join) {
                $join->on('ps.id', '=', 'pd.nocmfk')->on('ps.kdprofile', '=', 'pd.kdprofile');
            })
            ->join('jeniskelamin_m as jk', function ($join) {
                $join->on('jk.id', '=', 'ps.objectjeniskelaminfk')->on('jk.kdprofile', '=', 'ps.kdprofile');
            })
            ->leftjoin('statuskeluar_m as sk', function ($j) {
                $j->on('sk.id', '=', 'pd.objectstatuskeluarfk')->on('sk.kdprofile', '=', 'pd.kdprofile');
            })
            ->leftjoin('statuspulang_m as sp', function ($j) {
                $j->on('sp.id', '=', 'pd.objectstatuspulangfk')->on('sp.kdprofile', '=', 'pd.kdprofile');
            })
            ->leftjoin('penyebabkematian_m as pk', function ($j) {
                $j->on('pk.id', '=', 'pd.objectpenyebabkematianfk')->on('pk.kdprofile', '=', 'pd.kdprofile');
            })
            ->leftjoin('ruangan_m as ru', function ($join) {
                $join->on('ru.id', '=', 'pd.objectruanganlastfk')->on('ru.kdprofile', '=', 'pd.kdprofile');
            })
            ->select(DB::raw("pd.tglregistrasi,pd.noregistrasi,ps.nocm,ps.namapasien,jk.jeniskelamin,ps.tgllahir,
            sk.statuskeluar,sp.statuspulang,pd.namalengkapambilpasien,
            case
                when pd.objectpenyebabkematianfk = 4
                then pd.keteranganpenyebabkematian
                else pk.penyebabkematian
            end
               as keterangan,
            pd.tglmeninggal, ru.namaruangan,pk.namaexternal"))
            ->where('pd.objectstatuskeluarfk', 5)
            ->where('pd.kdprofile', $kdProfile);

        if (isset($filter['tglAwal']) && $filter['tglAwal'] != '' && isset($filter['tglAkhir']) && $filter['tglAkhir'] != '') {
            $data = $data->whereBetween('pd.tglmeninggal', [$filter['tglAwal'],  $filter['tglAkhir']]);
        }
        if (isset($filter['noReg']) && $filter['noReg'] != "" && $filter['noReg'] != "undefined") {
            $data = $data->where('pd.noregistrasi', 'ilike', '%' . $filter['noReg'] . '%');
        }
        if (isset($filter['noCm']) && $filter['noCm'] != "" && $filter['noCm'] != "undefined") {
            $data = $data->where('ps.nocm', 'ilike', '%' . $filter['noCm'] . '%');
        }
        if (isset($filter['namaPasien']) && $filter['namaPasien'] != "" && $filter['namaPasien'] != "undefined") {
            $data = $data->where('ps.namapasien', 'ilike', '%' . $filter['namaPasien'] . '%');
        }
        $data = $data->get();
        $result = array(
            'data' => $data,
            'message' => '@epic',
        );

        return $this->respond($result);
    }

    //    public function getDaftarKonsulFromOrder(Request $request)
    // {
    //     $data = DB::table('strukorder_t as so')
    //         ->Join('pasiendaftar_t as pd', 'pd.norec', '=', 'so.noregistrasifk')
    //         ->Join('pasien_m as ps', 'ps.id', '=', 'pd.nocmfk')
    //         ->leftJoin('ruangan_m as ru', 'ru.id', '=', 'so.objectruanganfk')
    //         ->leftJoin('ruangan_m as rutuju', 'rutuju.id', '=', 'so.objectruangantujuanfk')
    //         ->leftJoin('pegawai_m as pg', 'pg.id', '=', 'so.objectpegawaiorderfk')
    //         ->leftJoin('pegawai_m as pet', 'pet.id', '=', 'so.objectpetugasfk')
    //         ->leftJoin('antrianpasiendiperiksa_t as apd', 'apd.objectstrukorderfk', '=', 'so.norec')
    //         ->select(
    //             'so.norec',
    //             'so.noorder',
    //             'so.tglorder',
    //             'so.rawatbersama',
    //             'so.konsultasi',
    //             'so.lainlain',
    //             'ru.namaruangan as ruanganasal',
    //             'pg.namalengkap',
    //             'rutuju.namaruangan as ruangantujuan',
    //             'pet.namalengkap as pengonsul',
    //             'pd.noregistrasi',
    //             'pd.tglregistrasi',
    //             'ps.nocm',
    //             'so.keteranganorder',
    //             'pd.norec as norec_pd',
    //             'ps.namapasien',
    //             'pg.id as pegawaifk',
    //             'so.objectruangantujuanfk',
    //             'so.objectruanganfk',
    //             'apd.norec as norec_apd',
    //             'so.keteranganlainnya',
    //             'pd.objectkelasfk as kelasfk_pd'
    //         )
    //         ->where('so.kdprofile', $this->kdProfile)
    //         ->where('so.statusenabled', true)
    //         ->where('so.objectkelompoktransaksifk', $this->settingFix('idKelompokTransaksiKonsul'))
    //         ->orderBy('so.tglorder', 'desc');
    //     if (isset($request['tglAwal']) && $request['tglAwal'] != '') {
    //         $data = $data->where('so.tglorder', '>=', $request['tglAwal'] . ' 00:00');
    //     }
    //     if (isset($request['tglAkhir']) && $request['tglAkhir'] != '') {
    //         $data = $data->where('so.tglorder', '<=', $request['tglAkhir'] . ' 23:59');
    //     }
    //     if (isset($request['norecpd']) && $request['norecpd'] != '') {
    //         $data = $data->where('pd.norec', $request['norecpd']);
    //     }
    //     // if (isset($request['dokterid']) && $request['dokterid'] != '') {
    //     //     $data = $data->where('pg.id', $request['dokterid']);
    //     // }
    //     if (isset($request['idPegawai']) && $request['idPegawai'] != "" && $request['idPegawai'] != "undefined") {
    //         $data = $data->where('pg.id', '=', $request['idPegawai']);
    //     }
    //     if (isset($request['nocm']) && $request['nocm'] != '') {
    //         $data = $data->where('ps.nocm', $request['nocm']);
    //     }
    //     if (isset($request['noregistrasi']) && $request['noregistrasi'] != '') {
    //         $data = $data->where('pd.noregistrasi', $request['noregistrasi']);
    //     }
    //     if (isset($request['isnotverif']) && $request['isnotverif'] != '' &&  $request['isnotverif'] == 'true') {
    //         $data = $data->wherenull('apd.norec');
    //     }
    //     $data = $data->get();
    //     $result = array(
    //         'data' => $data,
    //     );
    //     return $this->respond($result);
    // }

    public function getDaftarKonsulFromOrder(Request $request)
    {
        $ruangan = DB::table('maploginusertoruangan_s as mlur')
            ->join('loginuser_s as lu', function ($j) {
                $j->on('lu.id', 'mlur.objectloginuserfk')->on('lu.kdprofile', 'mlur.kdprofile');
            })
            ->join('ruangan_m as ru', function ($j) {
                $j->on('ru.id', 'mlur.objectruanganfk')->on('ru.kdprofile', 'mlur.kdprofile');
            })
            ->select('ru.id', 'ru.namaruangan')
            ->where('lu.kdprofile', $this->kdProfile)
            ->where('ru.statusenabled', true)
            ->where('lu.id', $this->getUserId())
            ->groupBy('ru.id', 'ru.namaruangan')
            ->get();

        $ruanganTersedia = [];
        foreach ($ruangan as $ru) {
            $ruanganTersedia[] = $ru->id;
        }
        $dateRange = [$request->tglAwal, $request->tglAkhir];
        $filter = $request->all();
        $data = DB::table('strukorder_t as so')
            ->Join('antrianpasiendiperiksa_t as apd', 'so.norec', '=', 'apd.objectstrukorderfk')
            ->leftjoin('pasiendaftar_t as pd', 'pd.norec', '=', 'apd.noregistrasifk')
            ->leftjoin('pasien_m as ps', 'ps.id', '=', 'pd.nocmfk')
            ->leftjoin('ruangan_m as ru', 'ru.id', '=', 'apd.objectruanganfk')
            ->leftjoin('ruangan_m as ru2', 'ru2.id', '=', 'apd.objectruanganasalfk')
            ->leftJoin('pegawai_m as pg', 'pg.id', '=', 'apd.objectpegawaifk')
            ->leftJoin('pegawai_m as pg2', 'pg2.id', '=', 'so.objectpetugasfk')
            ->leftJoin('kelompokpasien_m as kp', 'kp.id', '=', 'pd.objectkelompokpasienlastfk')
            ->select(
                'so.norec',
                'so.noorder',
                'so.tglorder',
                'so.rawatbersama',
                'so.konsultasi',
                'so.lainlain',
                'ru2.namaruangan as ruanganasal',
                'ru.namaruangan as ruangantujuan',
                'pd.noregistrasi',
                'pd.tglregistrasi',
                'ps.nocm',
                'pd.nocmfk',
                'so.keteranganorder',
                'pd.norec as norec_pd',
                'ps.namapasien',
                'pg.id as pegawaifk',
                'pg2.namalengkap',
                'pg.namalengkap as dokter',
                'so.objectruangantujuanfk',
                'so.objectruanganfk',
                'apd.norec as norec_apd',
                'so.keteranganlainnya',
                'pd.objectkelasfk as kelasfk_pd'
            )
            ->where('so.kdprofile', $this->kdProfile)
            ->where('pd.statusenabled', true)
            ->where('ps.statusenabled', true)
            ->where('so.objectkelompoktransaksifk', $this->settingFix('idKelompokTransaksiKonsul'))
            ->whereBetween(DB::raw("CAST(apd.tglmasuk as Date)"), $dateRange);

        // if (isset($filter['ruanganfk']) && $filter['ruanganfk'] != "" && $filter['ruanganfk'] != "undefined") {
        //     $data = $data->where('ru.id', '=', $filter['ruanganfk']);
        // }
        // if (isset($filter['idpegawai']) && $filter['idpegawai'] != "" && $filter['idpegawai'] != "undefined") {
        //     $data = $data->where('pg.id', '=', $filter['idpegawai']);
        // }
        // if (isset($request['ruanganfk']) && $request['ruanganfk'] != '') {
        //     $data = $data->where('so.objectruangantujuanfk', $request['ruanganfk']);
        // }
        if (isset($request['ruanganfk']) && $request['ruanganfk'] != '') {
            $filter = true;
            $data = $data->whereIn('ru.id', explode(',', $request['ruanganfk']));
        } else {
            $filter = true;
            $data = $data->whereIn('ru.id', $ruanganTersedia);
        }
        if (isset($request['search']) && $request['search'] != '') {
            $searchTerm = '%' . $request['search'] . '%';
            $data = $data->where(function ($query) use ($searchTerm) {
                $query->where('ps.namapasien', 'ilike', $searchTerm)
                    ->orWhere('pd.noregistrasi', 'ilike', $searchTerm)
                    ->orWhere('ps.nocm', 'ilike', $searchTerm)
                    ->orWhere('ps.noidentitas', 'ilike', $searchTerm);
            });
        }

        // $data = $data->orderBy('so.tglorder', 'desc');
        $data = $data->get();

        return $this->respond($data);
    }

    public function getOrderKonsul(Request $request)
    {
        $idProfile = (int) $this->kdProfile;
        $arrRuangId = [];
        if (isset($request['perawatId']) && $request['perawatId'] != '') {
            $dataruangan = DB::table('maploginusertoruangan_s as mlu')
                ->join('ruangan_m as ru', 'ru.id', '=', 'mlu.objectruanganfk')
                ->select('ru.id', 'ru.namaruangan')
                ->where('mlu.kdprofile', $idProfile)
                ->where('mlu.objectloginuserfk', $request['perawatId'])
                ->get();

            if (count($dataruangan) > 0) {
                foreach ($dataruangan as $item) {
                    $arrRuangId[]  = $item->id;
                }
            }
        }

        $kelTrans = KelompokTransaksi::where('kelompoktransaksi', 'KONSULTASI DOKTER')->where('kdprofile', $idProfile)->first();
        $data = DB::table('strukorder_t as so')
            ->Join('pasiendaftar_t as pd', 'pd.norec', '=', 'so.noregistrasifk')
            ->Join('pasien_m as ps', 'ps.id', '=', 'pd.nocmfk')
            ->leftJoin('ruangan_m as ru', 'ru.id', '=', 'so.objectruanganfk')
            ->leftJoin('ruangan_m as rutuju', 'rutuju.id', '=', 'so.objectruangantujuanfk')
            ->leftJoin('pegawai_m as pg', 'pg.id', '=', 'so.objectpegawaiorderfk')
            ->leftJoin('pegawai_m as pet', 'pet.id', '=', 'so.objectpetugasfk')
            ->leftJoin('antrianpasiendiperiksa_t as apd', 'apd.objectstrukorderfk', '=', 'so.norec')
            ->select(
                'so.norec',
                'so.noorder',
                'so.tglorder',
                'ru.namaruangan as ruanganasal',
                'pg.namalengkap',
                'rutuju.namaruangan as ruangantujuan',
                'pet.namalengkap as pengonsul',
                'pd.noregistrasi',
                'pd.tglregistrasi',
                'ps.nocm',
                'pd.objectkelasfk as kelasfk_pd',
                'apd.objectkelasfk as kelasfk_apd',
                'so.keteranganorder',
                'pd.norec as norec_pd',
                'ps.namapasien',
                'pg.id as pegawaifk',
                'so.objectruangantujuanfk',
                'so.objectruanganfk',
                'apd.norec as norec_apd'
            )
            ->where('so.kdprofile', $idProfile)
            ->where('so.statusenabled', true)
            ->wherenull('apd.norec')
            ->where('so.objectkelompoktransaksifk', $kelTrans->id)
            ->orderBy('so.tglorder', 'desc');

        if (isset($request['norecpd']) && $request['norecpd'] != '') {
            $data = $data->where('pd.norec', $request['norecpd']);
        }
        if (isset($request['idpegawai']) && $request['idpegawai'] != '' &&  $request['kelompokuser'] == 'dokter') {
            $data = $data->where('pg.id', $request['idpegawai']);
        }
        if (isset($request['perawatId']) && $request['perawatId'] != '') {
            $data = $data->whereIn('rutuju.id', $arrRuangId);
        }

        $data = $data->get();
        $result = array(
            'data' => $data,
            'message' => 'Inhuman',
        );
        return $this->respond($result);
    }

    public function saveKonsulFromOrder(Request $request)
    {
        DB::beginTransaction();
        try {
            $pd = PasienDaftar::where('norec', $request['norec_pd'])->first();
            $apd = AntrianPasienDiperiksa::where('noregistrasifk', $request['norec_pd'])->where('kdprofile', $this->kdProfile)->first();

            $noAntrian = 0;

            $dataAPD = new AntrianPasienDiperiksa;
            $dataAPD->norec = $dataAPD->generateNewId();
            $dataAPD->kdprofile = $this->kdProfile;
            $dataAPD->statusenabled = true;
            $dataAPD->objectasalrujukanfk = $apd->objectasalrujukanfk;
            $dataAPD->objectkelasfk = $request['kelasfk'];

            // return $request['objectruangantujuanfk'];
            $max = AntrianPasienDiperiksa::where('objectruanganfk', $request['objectruangantujuanfk'])
                ->where('tglregistrasi', '>=', date('Y-m-d' . ' 00:00'))
                ->where('tglregistrasi', '<=', date('Y-m-d' . ' 23:59'))
                ->where('statusenabled', true)
                ->max('noantrian');
            $noAntrian = $max + 1;
            $dataAPD->noantrian = $noAntrian;

            $dataAPD->noregistrasifk = $request['norec_pd'];
            $dataAPD->objectpegawaifk = $request['dokterfk'];
            $dataAPD->objectruanganfk = $request['objectruangantujuanfk'];
            $dataAPD->statusantrian = 0;
            $dataAPD->statuspasien = 1;
            $dataAPD->statuskunjungan = 'LAMA';
            $dataAPD->statuspenyakit = 'BARU';
            $dataAPD->status = "Belum Dipanggil";
            $dataAPD->objectruanganasalfk = $request['objectruanganasalfk'];
            $dataAPD->tglregistrasi = date('Y-m-d H:i:s');
            $dataAPD->tglkeluar = date('Y-m-d H:i:s');
            $dataAPD->tglmasuk = date('Y-m-d H:i:s');
            $dataAPD->objectstrukorderfk = $request['norec_so'];
            $dataAPD->noregistrasi = $pd->noregistrasi;
            $dataAPD->save();

            // return $dataAPD;
            DB::commit();

            $respond = [
                "status" => 200,
                "message" => "Berhasil Verifikasi",
                "data" => $dataAPD->norec
            ];
        } catch (Exception $e) {

            DB::rollBack();
            $respond = [
                "status" => 400,
                "message" => "Gagal Verifikasi",
                "data" => $e->getMessage(),
            ];
        }

        return $this->respond($respond['data'], $respond['status'], $respond['message']);
    }

    public function updateDokterAntrian(Request $request)
    {

        DB::beginTransaction();
        try {

            if ($request['norec_apd'] != null) {
                $apd =  AntrianPasienDiperiksa::where('norec', $request['norec_apd'])->where('kdprofile', $this->kdProfile)->first();
                AntrianPasienDiperiksa::where('norec', $request['norec_apd'])->where('kdprofile', $this->kdProfile)->update(['objectpegawaifk' => $request['iddokter']]);

                PasienDaftar::where('norec', $apd->noregistrasifk)->where('kdprofile', $this->kdProfile)->update(['objectpegawaifk' => $request['iddokter']]);
            }

            DB::commit();
            $respond = [
                "message" => "Update Berhasil",
                "status"  => 200,
                "data" => "Berhasil"
            ];
        } catch (Exception $e) {

            DB::rollBack();
            $respond = [
                "message" => "Update Gagal",
                "status" => 400,
                "data" => $e->getMessage(),
            ];
        }

        return $this->respond($respond['data'], $respond['status'], $respond['message']);
    }

    public function ubahKelas(Request $r)
    {
        DB::beginTransaction();
        try {
            $idProfile = (int) $this->kdProfile;
            date_default_timezone_set('Asia/Jakarta');
            $PD = PasienDaftar::where('norec', $r['norec_pd'])->first();
            $APD = AntrianPasienDiperiksa::where('norec', $r['norec_apd'])->first();

            // Save data
            $PD->objectkelasfk = $r['objectkelasfk'];
            $PD->iskelastitip = $r['iskelastitip'];
            $PD->isnaikkelas = $r['isnaikkelas'];
            $PD->save();

            $APD->objectkelasfk = $r['objectkelasfk'];
            $APD->kelasrawatfk = $r['kelasrawatfk'];
            $APD->israwatgabung = $r['israwatgabung'];
            $APD->save();

            // Data pasien
            $dataPasien = DB::table('pasiendaftar_t as pd')
                ->join('antrianpasiendiperiksa_t as apd', 'pd.norec', 'apd.noregistrasifk')
                ->join('ruangan_m as ruas', 'ruas.id', 'pd.objectruanganasalfk')
                ->join('pasien_m as ps', 'ps.id', 'pd.nocmfk')
                ->join('kelas_m as kl', 'kl.id', 'apd.objectkelasfk')
                ->join('kelas_m as klr', 'klr.id', 'apd.kelasrawatfk')
                ->select('ps.namapasien', 'ruas.namaruangan as ruanganasal', 'pd.noregistrasi', 'ps.nocm', 'kl.namakelas', 'klr.namakelas as namakelasrawat')
                ->where('pd.kdprofile', $idProfile)
                ->where('pd.statusenabled', true)
                ->where('pd.norec', $r['norec_pd'])
                ->where('apd.norec', $r['norec_apd'])
                ->first();

            $this->LOGGING(
                'Edit Kelas',
                $PD->norec,
                'antrianpasiendiperiksa_t',
                'Edit kelas menjadi ' . 'Kelas Hak ' . $dataPasien->namakelas . ' dan Kelas Rawat ' . $dataPasien->namakelasrawat . ' pada pasien ' . $dataPasien->namapasien . ' (' . $dataPasien->nocm . ') ' . ' - ' . $dataPasien->noregistrasi
            );

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
                    "data" => $APD,
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
}