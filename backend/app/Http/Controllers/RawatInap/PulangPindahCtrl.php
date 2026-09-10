<?php

namespace App\Http\Controllers\RawatInap;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Registrasi\RegistrasiRuanganCtrl;
use App\Models\Master\HubunganKeluarga;
use App\Models\Master\Kamar;
use App\Models\Master\Kelas;
use App\Models\Master\KondisiPasien;
use App\Models\Master\Pasien;
use App\Models\Master\PenyebabKematian;
use App\Models\Master\Ruangan;
use App\Models\Master\StatusKeluar;
use App\Models\Master\StatusPulang;
use App\Models\Master\TempatTidur;
use App\Models\Transaksi\AksesEMR;
use App\Models\Transaksi\AntrianPasienDiperiksa;
use App\Models\Transaksi\PasienDaftar;
use App\Models\Transaksi\PelayananPasien;
use App\Models\Transaksi\StrukOrder;
use App\Traits\Valet;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PulangPindahCtrl extends Controller
{
    use Valet;
    public function __construct()
    {
        parent::__construct($is_encrypt = true);
    }
    public function dataPasien(Request $r)
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
                'ps.email'
            )
            ->where('ps.kdprofile', (int)$this->kdProfile)
            ->where('ps.statusenabled', true)
            ->where('ps.id', $r['nocmfk'])
            ->first();
        if (!empty($data)) {
            $data->umur =  $this->getAge($data->tgllahir, date('Y-m-d H:i:s'));
        }
        $registrasi =   DB::table('pasiendaftar_t as pd')
            ->JOIN('ruangan_m as ru', 'ru.id', '=', 'pd.objectruanganlastfk')
            ->JOIN('departemen_m as dp', 'dp.id', '=', 'ru.objectdepartemenfk')
            ->JOIN('kelompokpasien_m as kp', 'kp.id', '=', 'pd.objectkelompokpasienlastfk')
            ->LEFTJOIN('rekanan_m as rk', 'rk.id', '=', 'pd.objectrekananfk')
            ->JOIN('kelas_m as kl', 'kl.id', '=', 'pd.objectkelasfk')
            ->select(
                'pd.noregistrasi',
                'pd.norec',
                'pd.tglregistrasi',
                'pd.tglpulang',
                'dp.namadepartemen',
                'kp.kelompokpasien',
                'ru.namaruangan',
                'kl.namakelas',
                'pd.nocmfk',
                'rk.namarekanan',
            )
            ->where('pd.kdprofile', (int)$this->kdProfile)
            ->where('pd.statusenabled', true)
            ->where('pd.nocmfk', $r['nocmfk']);

        if (isset($r['dari']) && $r['dari']) {
            $registrasi =  $registrasi->where('pd.tglregistrasi', '>=', $r['dari'] . ' 00:00');
        }
        if (isset($r['sampai']) && $r['sampai']) {
            $registrasi =  $registrasi->where('pd.tglregistrasi', '<=', $r['sampai'] . ' 23:59');
        }
        $jmlRegis = $registrasi->count();
        if (isset($r['limit']) && $r['limit'] != '') {
            $registrasi = $registrasi->limit($r['limit']);
        }
        if (isset($r['offset']) && $r['offset'] != '') {
            $registrasi = $registrasi->offset($r['offset']);
        }
        $registrasi =  $registrasi->orderByDesc('pd.tglregistrasi');
        $registrasi =  $registrasi->get();
        $result['pasien'] = $data;
        $result['registrasi'] = $registrasi;
        $result['count_registrasi'] = $jmlRegis;
        $result['as'] = '@epic';

        return $this->respond($result);
    }

    public function dropDownPulang(Request $request)
    {
        // $set = explode(',', $this->settingFix('kdDepartemenRanapFix'));
        $res['statuskeluar'] = StatusKeluar::mine()->get();
        $res['kondisipasien'] = KondisiPasien::mine()->get();
        $res['statuspulang'] = StatusPulang::mine()->get();
        $res['hubungankeluarga'] = HubunganKeluarga::mine()->get();
        $res['namakelas'] = Kelas::mine()->get();
        if (isset($request['departemenfk']) && $request['departemenfk'] == 9) {
            $res['namaruangan'] = Ruangan::mine()->where('objectdepartemenfk', '=', 18)->get();
        } else {
            $res['namaruangan'] = Ruangan::mine()->whereNotIn('namaexternal', ['FARMASI', 'RADIOLOGI', 'KASIR', 'LABORATORIUM', 'GUDANG FARMASI', 'GUDANG UMUM'])->get();
        }
        $res['penyebabkematian'] = PenyebabKematian::mine()->get();
        $res['namakamar'] = Kamar::mine()->get();

        return $this->respond($res);
    }

    public function riwayatAPD(Request $r)
    {
        $data = DB::table('pasiendaftar_t as pd')
            ->join('antrianpasiendiperiksa_t as apd', 'pd.norec', '=', 'apd.noregistrasifk')
            ->leftjoin('asalrujukan_m as as', 'as.id', '=', 'apd.objectasalrujukanfk')
            ->leftjoin('pegawai_m as pg', 'pg.id', '=', 'apd.objectpegawaifk')
            ->join('ruangan_m as ru', 'ru.id', '=', 'apd.objectruanganfk')
            ->leftjoin('departemen_m as dept', 'dept.id', '=', 'ru.objectdepartemenfk')
            ->leftjoin('kamar_m as km', 'km.id', '=', 'apd.objectkamarfk')
            ->leftjoin('kelas_m as kls', 'kls.id', '=', 'apd.objectkelasfk')
            ->leftjoin('pasien_m as pm', 'pm.id', '=', 'pd.nocmfk')
            ->leftjoin('pemakaianasuransi_t as pa', 'pa.noregistrasifk', '=', 'pd.norec')
            ->leftjoin('tempattidur_m as tt', 'tt.id', '=', 'apd.nobed')
            ->select(
                'apd.norec',
                'pd.norec as norec_pd',
                'pd.noregistrasi',
                'ru.namaruangan',
                'kls.namakelas',
                'km.namakamar',
                'tt.reportdisplay as nobed',
                'apd.statusantrian',
                'apd.statuskunjungan',
                'apd.tglregistrasi',
                'apd.israwatgabung',
                'apd.tgldipanggildokter',
                'apd.tgldipanggilsuster',
                'pg.namalengkap as namadokter',
                'as.asalrujukan',
                'pd.nostruklastfk',
                'pd.nosbmlastfk',
                'apd.tglmasuk',
                'apd.tglkeluar',
                'pd.tglpulang',
                'pd.isnaikkelas',
                'pd.iskelastitip',
                'dept.namadepartemen',
                'pm.tglmeninggal',
                'pa.nosep',
                'pd.objectkelompokpasienlastfk',
                'apd.objectruanganasalfk',
                DB::raw("
                FLOOR(EXTRACT(EPOCH FROM NOW() - pd.tglregistrasi)::INTEGER / (24 * 3600)) || ' hari ' ||
                FLOOR(EXTRACT(EPOCH FROM NOW() - pd.tglregistrasi)::INTEGER % (24 * 3600) / 3600) || ' jam ' ||
                FLOOR(EXTRACT(EPOCH FROM NOW() - pd.tglregistrasi)::INTEGER % 3600 / 60) || ' menit' AS selisihWaktu
            ")
            )
            ->where('pd.kdprofile', (int)$this->kdProfile)
            ->where('apd.statusenabled', true)
            ->where('pd.statusenabled', true);

        $filter = $r->all();

        if (isset($filter['norec_pd']) && $filter['norec_pd'] != "" && $filter['norec_pd'] != "undefined") {
            $data = $data->where('pd.norec', '=', $filter['norec_pd']);
        }
        if (isset($filter['nocmfk']) && $filter['nocmfk'] != "" && $filter['nocmfk'] != "undefined") {
            $data = $data->where('pd.nocmfk', '=', $filter['nocmfk']);
        }

        $data = $data->orderBy('apd.tglmasuk', 'asc');
        $data = $data->get();
        foreach ($data as $d) {
            $d->lamarawat =  $this->getAge($d->tglmasuk, $d->tglkeluar ? $d->tglkeluar : date('Y-m-d H:i:s'));
        }

        return $this->respond($data);
    }

    public function savePindah(Request $request)
    {
        DB::beginTransaction();
        try {
            $kdProfile = $this->kdProfile;
            $r_NewPD = $request['pasiendaftar'];
            $r_NewAPD = $request['antrianpasiendiperiksa'];
            $SET['idStatusBedKosong'] = $this->settingFix('idStatusBedKosong');
            $SET['idStatusBedIsi'] = $this->settingFix('idStatusBedIsi');

            // Update PD kelas pasien, mau itu rawat atau kelas haknya
            PasienDaftar::where('norec', $r_NewPD['norec_pd'])->update([
                'objectruanganlastfk' => $r_NewPD['objectruangantujuanfk'],
                'iskelastitip' => $r_NewPD['iskelastitip'],
                'isnaikkelas' => $r_NewPD['isnaikkelas'],
                'objectkelasfk' => $r_NewAPD['objectkelasfk'],
                'objectkelasrawatfk' => $r_NewAPD['objectkelasrawatfk'],
            ]);

            $cekUpdateTglKeluar=AntrianPasienDiperiksa::where('noregistrasifk',$r_NewPD['norec_pd'])->whereNull('tglkeluar')->orderby('created_at','desc')->where('statusenabled',true)->pluck('norec')->toArray();

            // $antrianapd=AntrianPasienDiperiksa::where('norec', $r_NewAPD['norec_apd'])->update(['tglkeluar' => $r_NewAPD['tglkeluar']]);

            if (!empty($cekUpdateTglKeluar)) {
                AntrianPasienDiperiksa::whereIn('norec', $cekUpdateTglKeluar)
                    ->update(['tglkeluar' => $r_NewAPD['tglkeluar']]);
            }

            $apd = AntrianPasienDiperiksa::where('norec', $r_NewAPD['norec_apd'])->first();

            // Update APD kelas pasien, mau itu rawat atau kelas haknya
            // AntrianPasienDiperiksa::where('norec', $r_NewAPD['norec_apd'])->update([
            //     'objectkelasfk' => $r_NewAPD['objectkelasfk'],
            //     'kelasrawatfk' => $r_NewAPD['objectkelasrawatfk'],
            // ]);

            TempatTidur::where('id', $apd->nobed)
                ->where('kdprofile', $this->kdProfile)
                ->where('statusenabled', true)
                ->lockForUpdate()
                ->update(['objectstatusbedfk' =>  $SET['idStatusBedKosong']]);

            $this->historyBED([
                "tempattidurfk" => $r_NewAPD['nobed'],
                "statusbedfk" => $SET['idStatusBedKosong'],
                "ruanganfk" => $apd->objectruanganfk,
                "kamarfk" => $apd->objectkamarfk,
            ]);

            $countNoAntrian = AntrianPasienDiperiksa::where('objectruanganfk', $r_NewPD['objectruangantujuanfk'])
                ->where('tglregistrasi', '>=', date('Y-m-d', strtotime($r_NewAPD['tglkeluar'])) . ' 00:00')
                ->where('tglregistrasi', '<=',  date('Y-m-d', strtotime($r_NewAPD['tglkeluar'])) . ' 23:59')
                ->count();

            $noAntrian = $countNoAntrian + 1;

            PasienDaftar::where('statusenabled', true)->where('kdprofile', $this->kdProfile)
                ->where('norec', $r_NewPD['norec_pd'])
                ->update([
                    'objectruanganasalfk' => $r_NewPD['objectruanganasalfk'],
                    'objectruanganlastfk' => $r_NewPD['objectruangantujuanfk']
                ]);
            $pd = PasienDaftar::where('norec', $r_NewPD['norec_pd'])->first();

            $dataAPD = new AntrianPasienDiperiksa;
            $dataAPD->norec = $dataAPD->generateNewId();
            $dataAPD->kdprofile = $kdProfile;
            $dataAPD->statusenabled = true;
            $dataAPD->objectruanganfk = $r_NewPD['objectruangantujuanfk'];
            $dataAPD->objectkamarfk = $r_NewAPD['objectkamarfk'];
            $dataAPD->objectkelasfk = $r_NewAPD['objectkelasfk'];
            $dataAPD->kelasrawatfk = $r_NewAPD['objectkelasrawatfk'];
            $dataAPD->noantrian = $noAntrian; //count tgl pasien perruanga
            $dataAPD->nobed = $r_NewAPD['objectbedfk'];
            $dataAPD->noregistrasifk = $r_NewPD['norec_pd'];
            $dataAPD->statusantrian = 0;
            $dataAPD->status = "Belum Dipanggil";
            $dataAPD->statuspasien = 1;
            $dataAPD->tglregistrasi =  $pd->tglregistrasi;
            $dataAPD->objectruanganasalfk = $r_NewPD['objectruanganasalfk'];
            $dataAPD->tglkeluar = null;
            $dataAPD->tglmasuk = $r_NewAPD['tglkeluar'];
            $dataAPD->israwatgabung = $r_NewAPD['israwatgabung'];
            $dataAPD->noregistrasi = $pd->noregistrasi;
            $dataAPD->save();

            // $cek = DB::table('tempattidur_m')
            // ->where('kdprofile', $this->kdProfile)
            // ->where('statusenabled', true)
            // ->where('id',  $dataAPD->nobed)
            // ->first();

            // if (!empty($cek) && $cek->objectstatusbedfk == $SET['idStatusBedIsi'] ) {
            //     DB::rollBack();
            //     $transMessage = 'Bed Sudah Terisi, Silakan Pilih Bed Lain';
            //     $result = array("status" => 400 ,'message' => $transMessage, "result"  => $cek);
            //     return $this->respond($result, $result['status'], $transMessage);
            // }
            if ($dataAPD->israwatgabung == false) {
                $cek = DB::table('tempattidur_m')
                    ->where('kdprofile', $this->kdProfile)
                    ->where('statusenabled', true)
                    ->where('id', $dataAPD->nobed)
                    ->first();

                if (!empty($cek) && $cek->objectstatusbedfk == $SET['idStatusBedIsi']) {
                    DB::rollBack();
                    $transMessage = 'Bed Sudah Terisi, Silakan Pilih Bed Lain';
                    $result = array("status" => 400, 'message' => $transMessage, "result" => $cek);
                    return $this->respond($result, $result['status'], $transMessage);
                }
            }

            //update statusbed jadi Isi

            DB::table('tempattidur_m')
                ->where('kdprofile', $this->kdProfile)
                ->where('statusenabled', true)
                ->where('id', $dataAPD->nobed)
                ->lockForUpdate()
                ->update(['objectstatusbedfk' =>  $SET['idStatusBedIsi']]);

            $this->historyBED([
                "tempattidurfk" => $dataAPD->nobed,
                "statusbedfk" => $SET['idStatusBedIsi'],
                "ruanganfk" => $r_NewPD['objectruangantujuanfk'],
                "kamarfk" => $r_NewAPD['objectkamarfk'],
            ]);

            $this->LOGGING(
                'Pindah Pasien',
                $r_NewPD['norec_pd'],
                'pasiendaftar_t',
                'Pindah Pasien atasnama ' .
                    $request['namapasien'] . ' (' . $request['nocm'] . ') - ' . $request['noregistrasi']
            );

            DB::commit();

            $objetoRequest = new \Illuminate\Http\Request();
            $objetoRequest['idruangan'] =   $dataAPD->objectruanganasalfk;
            $objetoRequest['idkelas'] =   $dataAPD->objectkelasfk;
            $aplicare = app('App\Http\Controllers\Bridging\BridgingBPJSCtrl')->updateAplicaresBedAfter($objetoRequest);


            $result = array(
                "status" => 200,
                "result" => $dataAPD,
                "message" => "Pasien Pindah Berhasil",
                'Aplicare' => $aplicare,
            );
        } catch (Exception $e) {
            DB::rollBack();
            $result = array(
                "status" => 400,
                "result"  => $e->getMessage() . $e->getLine(),
                "message" => "Somthing Want Wrong",
            );
        }

        return $this->respond($result['result'], $result['status'], $result['message']);
    }

    public function savePulang(Request $request)
    {
        DB::beginTransaction();
        try {
            $kdProfile = $this->kdProfile;
            $r_NewPD = $request['pasiendaftar'];
            $r_NewAPD = $request['antrianpasiendiperiksa'];
            $SET['idStatusBedKosong'] = $this->settingFix('idStatusBedKosong');
            $SET['idStatusBedIsi'] = $this->settingFix('idStatusBedIsi');

            PasienDaftar::where('norec', $r_NewPD['norec_pd'])->update([
                'objectstatuskeluarfk' => $r_NewPD['objectstatuskeluarfk']['id'],
                'objectstatuspulangfk' => $r_NewPD['objectstatuspulangfk'],
                'objectkondisipasienfk' => $r_NewPD['objectkondisipasienfk'],
                'tglpulang' => $r_NewPD['tglpulang'],
                'objecthubungankeluargaambilpasienfk'  => $r_NewPD['objecthubungankeluargaambilpasienfk'],
                'namalengkapambilpasien'  => $r_NewPD['namalengkapambilpasien'],
            ]);

            $apd = AntrianPasienDiperiksa::where('norec', $r_NewAPD['norec_apd'])->first();
            $PD = PasienDaftar::where('norec', $r_NewPD['norec_pd'])->first();
            $AksesEMRExsist = AksesEMR::where('statusenabled', true)->where('kdprofile', $this->kdProfile)->where('pasienfk', $PD->nocmfk)->first();

            if ($AksesEMRExsist) {
                AksesEMR::where('pasienfk', $PD->nocmfk)->where('statusenabled', true)->where('kdprofile', $this->kdProfile)
                    ->update([
                        'tglberakhir' => $r_NewAPD['tglkeluar'],
                        'deskripsi' => 'EMR dikunci, Pasien Sudah Pulang',
                        'objectkelompokuserfk' => '',
                        'pegawaipemohonfk' => null,
                    ]);
            } else {
                $aksesEMR = new AksesEMR();
                $aksesEMR->norec = $aksesEMR->generateNewId();
                $aksesEMR->statusenabled = true;
                $aksesEMR->kdprofile = $this->kdProfile;
                $aksesEMR->pegawaipenerimafk = $this->getPegawaiId();
                $aksesEMR->pasienfk = $PD->nocmfk;
                $aksesEMR->tglmulai = date('Y-m-d');
                $aksesEMR->objectkelompokuserfk = '';
                $aksesEMR->tglberakhir = $r_NewAPD['tglkeluar'] ;
                $aksesEMR->deskripsi = 'EMR dikunci, Pasien Sudah Pulang';
                $aksesEMR->save();
            }
            // Pasien::where('id', $PD->nocmfk)->where('statusenabled',true)->where('kdprofile',$this->kdProfile)->update(['statusemr' => 1]);
            // return $apd;
            AntrianPasienDiperiksa::where('statusenabled', true)
                ->where('kdprofile', $this->kdProfile)
                ->where('norec', $r_NewAPD['norec_apd'])
                ->update(['tglkeluar' => $r_NewAPD['tglkeluar']]);

            DB::table('tempattidur_m')
                ->where('kdprofile', $this->kdProfile)
                ->where('statusenabled', true)
                ->where('id', $r_NewAPD['nobed'])
                ->lockForUpdate()
                ->update(['objectstatusbedfk' =>  $SET['idStatusBedKosong']]);
            $this->historyBED([
                "tempattidurfk" => $r_NewAPD['nobed'],
                "statusbedfk" => $SET['idStatusBedKosong'],
                "ruanganfk" => $apd->objectruanganfk,
                "kamarfk" => $apd->objectkamarfk,
            ]);
            $this->LOGGING(
                'Pulang Pasien',
                $r_NewPD['norec_pd'],
                'pasiendaftar_t',
                'Pulang Pasien atas nama ' .
                    $request['namapasien'] . ' (' . $request['nocm'] . ') - ' . $request['noregistrasi']
            );

            $transStatus = 'true';
        } catch (\Exception $e) {
            $transStatus = 'false';
        }
        if ($transStatus == 'true') {
            $transMessage = "Sukses";
            DB::commit();

            $objetoRequest = new \Illuminate\Http\Request();
            $objetoRequest['idruangan'] =    $apd->objectruanganfk;
            $objetoRequest['idkelas'] =   $apd->objectkelasfk;
            $aplicare = app('App\Http\Controllers\Bridging\BridgingBPJSCtrl')->updateAplicaresBedAfter($objetoRequest);


            $result = array(
                "status" => 200,
                'Aplicare' => $aplicare,
            );
        } else {
            $transMessage = "Simpan Gagal";
            DB::rollBack();
            $result = array(
                "status" => 400,
                "result"  => $e->getMessage() . $e->getLine()
            );
        }
        return $this->respond($result, $result['status'], $transMessage);
    }

    public function saveRujuk(Request $request)
    {

        DB::beginTransaction();
        try {
            $kdProfile = $this->kdProfile;
            $r_NewPD = $request['pasiendaftar'];
            $r_NewAPD = $request['antrianpasiendiperiksa'];
            $SET['idStatusBedKosong'] = $this->settingFix('idStatusBedKosong');
            $SET['idStatusBedIsi'] = $this->settingFix('idStatusBedIsi');

            PasienDaftar::where('norec', $r_NewPD['norec_pd'])->update([
                'objectstatuskeluarfk' => $r_NewPD['objectstatuspulangfk'],
                'objectstatuspulangfk' => $r_NewPD['objectstatuspulangfk'],
                'objectkondisipasienfk' => $r_NewPD['objectkondisipasienfk'],
                'tglpulang' => $r_NewPD['tglpulang'],
                'objecthubungankeluargaambilpasienfk'  => $r_NewPD['objecthubungankeluargaambilpasienfk'],
                'namalengkapambilpasien'  => $r_NewPD['namalengkapambilpasien'],
                'rujuk'  => $r_NewPD['rujuk'],
                'alamatrujuk'  => $r_NewPD['alamatrujuk'],

            ]);

            $PD = PasienDaftar::where('norec', $r_NewPD['norec_pd'])->first();

            $AksesEMRExsist = AksesEMR::where('statusenabled', true)->where('kdprofile', $this->kdProfile)->where('pasienfk', $PD->nocmfk)->first();

            if ($AksesEMRExsist) {
                AksesEMR::where('pasienfk', $PD->nocmfk)->where('statusenabled', true)->where('kdprofile', $this->kdProfile)
                    ->update([
                        'tglberakhir' => $r_NewAPD['tglkeluar'],
                        'deskripsi' => 'EMR dikunci, Pasien Sudah Dirujuk',
                        'objectkelompokuserfk' => '',
                        'pegawaipemohonfk' => null,
                    ]);
            } else {
                $aksesEMR = new AksesEMR();
                $aksesEMR->norec = $aksesEMR->generateNewId();
                $aksesEMR->statusenabled = true;
                $aksesEMR->kdprofile = $this->kdProfile;
                $aksesEMR->pegawaipenerimafk = $this->getPegawaiId();
                $aksesEMR->pasienfk = $PD->nocmfk;
                $aksesEMR->tglmulai = date('Y-m-d');
                $aksesEMR->objectkelompokuserfk = '';
                $aksesEMR->tglberakhir = $r_NewAPD['tglkeluar'];
                $aksesEMR->deskripsi = 'EMR dikunci, Pasien Sudah Pulang';
                $aksesEMR->save();
            }
            // Pasien::where('id',$PD->nocmfk)->where('statusenabled',true)->update(['statusemr' => 1]);

            $apd = AntrianPasienDiperiksa::where('norec', $r_NewAPD['norec_apd'])->first();
            $apd->tglkeluar = $r_NewAPD['tglkeluar'];
            $apd->save();
            DB::table('tempattidur_m')
                ->where('kdprofile', $this->kdProfile)
                ->where('statusenabled', true)
                ->where('id',  $r_NewAPD['nobed'])
                ->lockForUpdate()
                ->update(['objectstatusbedfk' =>  $SET['idStatusBedKosong']]);
            $this->historyBED([
                "tempattidurfk" => $r_NewAPD['nobed'],
                "statusbedfk" => $SET['idStatusBedKosong'],
                "ruanganfk" => $apd->objectruanganfk,
                "kamarfk" => $apd->objectkamarfk,
            ]);


            $transStatus = 'true';
        } catch (\Exception $e) {
            $transStatus = 'false';
        }
        if ($transStatus == 'true') {
            $transMessage = "Sukses";
            DB::commit();

            $objetoRequest = new \Illuminate\Http\Request();
            $objetoRequest['idruangan'] =    $apd->objectruanganfk;
            $objetoRequest['idkelas'] =   $apd->objectkelasfk;
            $aplicare = app('App\Http\Controllers\Bridging\BridgingBPJSCtrl')->updateAplicaresBedAfter($objetoRequest);

            $result = array(
                "status" => 200,
                'Aplicare' => $aplicare,
            );
        } else {
            $transMessage = "Simpan Gagal";
            DB::rollBack();
            $result = array(
                "status" => 400,
                "result"  => $e->getMessage() . $e->getLine()
            );
        }
        return $this->respond($result, $result['status'], $transMessage);
    }

    public function saveMeninggal(Request $request)
    {
        DB::beginTransaction();
        try {
            $kdProfile = $this->kdProfile;
            $r_NewPD = $request['pasiendaftar'];
            $r_NewP = $request['pasien'];
            $r_NewAPD = $request['antrianpasiendiperiksa'];
            $SET['idStatusBedKosong'] = $this->settingFix('idStatusBedKosong');
            $SET['idStatusBedIsi'] = $this->settingFix('idStatusBedIsi');


            PasienDaftar::where('norec', $r_NewPD['norec_pd'])->update([
                'objectstatuskeluarfk' => $r_NewPD['objectstatuspulangfk'],
                'objectstatuspulangfk' => $r_NewPD['objectstatuspulangfk'],
                'objectkondisipasienfk' => $r_NewPD['objectkondisipasienfk'],
                'tglpulang'  => $r_NewPD['tglmeninggal'],
                'tglmeninggal' => $r_NewPD['tglmeninggal'],
                'objectpenyebabkematianfk' => $r_NewPD['objectpenyebabkematianfk'],
                'objecthubungankeluargaambilpasienfk'  => $r_NewPD['objecthubungankeluargaambilpasienfk'],
                'namalengkapambilpasien'  => $r_NewPD['namalengkapambilpasien'],
            ]);

            Pasien::where('id', $request['pasien']['nocmfk'])
                ->where('kdprofile', $kdProfile)
                ->update(['tglmeninggal' => $request['pasien']['tglmeninggal']]);

            $AksesEMRExsist = AksesEMR::where('statusenabled', true)->where('kdprofile', $this->kdProfile)->where('pasienfk', $r_NewP['nocmfk'])->first();

            if ($AksesEMRExsist) {
                AksesEMR::where('pasienfk', $r_NewP['nocmfk'])->where('statusenabled', true)->where('kdprofile', $this->kdProfile)
                    ->update([
                        'tglberakhir' => $r_NewPD['tglmeninggal'],
                        'deskripsi' => 'EMR dikunci, Pasien Sudah Meninggal',
                        'objectkelompokuserfk' => '',
                        'pegawaipemohonfk' => null,
                    ]);
            } else {
                $aksesEMR = new AksesEMR();
                $aksesEMR->norec = $aksesEMR->generateNewId();
                $aksesEMR->statusenabled = true;
                $aksesEMR->kdprofile = $this->kdProfile;
                $aksesEMR->pegawaipenerimafk = $this->getPegawaiId();
                $aksesEMR->pasienfk = $r_NewP['nocmfk'];
                $aksesEMR->objectkelompokuserfk = '';
                $aksesEMR->tglmulai = date('Y-m-d');
                $aksesEMR->tglberakhir = $r_NewPD['tglmeninggal'];
                $aksesEMR->deskripsi = 'EMR dikunci, Pasien Sudah Meninggal';
                $aksesEMR->save();
            }

            // AksesEMR::where('kdprofile',$this->kdProfile)->where('statusenabled',true)
            //         ->where('pasienfk', $r_NewP['nocmfk'])
            //         ->update([
            //             'tglberakhir' => $r_NewPD['tglmeninggal'],
            //             'deskripsi' => 'EMR dikunci, Pasien Sudah Pulang',
            //             'objectkelompokuserfk' => '',
            //             'pegawaipemohonfk' => null,
            //         ]);

            // return $this->respond($d);

            $apd = AntrianPasienDiperiksa::where('norec', $r_NewAPD['norec_apd'])->first();
            $apd->tglkeluar = $r_NewAPD['tglkeluar'];
            $apd->save();

            DB::table('tempattidur_m')
                ->where('kdprofile', $this->kdProfile)
                ->where('statusenabled', true)
                ->where('id', $r_NewAPD['nobed'])
                ->lockForUpdate()
                ->update(['objectstatusbedfk' =>  $SET['idStatusBedKosong']]);

            $this->historyBED([
                "tempattidurfk" => $r_NewAPD['nobed'],
                "statusbedfk" => $SET['idStatusBedKosong'],
                "ruanganfk" => $apd->objectruanganfk,
                "kamarfk" => $apd->objectkamarfk,
            ]);
            $transStatus = 'true';
        } catch (\Exception $e) {
            $transStatus = 'false';
        }
        if ($transStatus == 'true') {
            $transMessage = "Sukses";
            DB::commit();

            $objetoRequest = new \Illuminate\Http\Request();
            $objetoRequest['idruangan'] =    $apd->objectruanganfk;
            $objetoRequest['idkelas'] =   $apd->objectkelasfk;
            $aplicare = app('App\Http\Controllers\Bridging\BridgingBPJSCtrl')->updateAplicaresBedAfter($objetoRequest);

            $result = array(
                "status" => 200,
                "result" => null,
                'Aplicare' => $aplicare,
            );

        } else {
            $transMessage = "Simpan Gagal";
            DB::rollBack();
            $result = array(
                "status" => 400,
                "result"  => $e->getMessage() . $e->getLine()
            );
        }
        return $this->respond($result, $result['status'], $transMessage);
    }

    public function RanapKelasByRuangan(Request $r)
    {
        $result = DB::table('mapruangantokelas_m as mrk')
            ->join('kelas_m as kl', 'kl.id', '=', 'mrk.objectkelasfk')
            ->select('kl.id', 'kl.namakelas')
            ->where('mrk.objectruanganfk', $r['id'])
            ->where('mrk.kdprofile',  $this->kdProfile)
            ->where('mrk.statusenabled', true)
            ->where('kl.statusenabled', true)
            ->orderBy('kl.namakelas')
            ->get();

        return $this->respond($result);
    }

    //function lama
    // public function RanapKamarByKelas(Request $r)
    // {
    //     $result['kamar'] = DB::table('kamar_m as kmr')
    //         ->join('ruangan_m as ru', 'ru.id', '=', 'kmr.objectruanganfk')
    //         ->join('kelas_m as kl', 'kl.id', '=', 'kmr.objectkelasfk')
    //         ->select(
    //             'kmr.id',
    //             'kmr.namakamar',
    //             'kl.id as id_kelas',
    //             'kl.namakelas',
    //             'ru.id as id_ruangan',
    //             'ru.namaruangan',
    //             'kmr.jumlakamarisi',
    //             'kmr.qtybed'
    //         )
    //         // ->where('kmr.objectruanganfk', $r['idRuangan'])
    //         ->where('kmr.objectkelasfk', $r['id'])
    //         ->where('kmr.objectruanganfk', $r['idRuangan'])
    //         ->where('kmr.statusenabled', true)
    //         ->where('kmr.kdprofile', $this->kdProfile)
    //         ->orderBy('kmr.namakamar')
    //         ->get();

    //     // return $this->respond($result);
    //     $result['idStatusBedKosong'] = explode(',', $this->settingFix('idStatusBedKosong'));
    //     $tt = DB::table('tempattidur_m')
    //         ->select('id', 'reportdisplay', 'nomorbed', 'objectkamarfk')
    //         ->where('kdprofile', $this->kdProfile)
    //         ->where('statusenabled', true)
    //         ->where('objectstatusbedfk', $result['idStatusBedKosong'])
    //         ->orderBy('reportdisplay')
    //         ->get();

    //     $kamar = [];
    //     if (isset($r['isRG']) && $r['isRG'] != 'undefined' && $r['isRG'] == 'false') {
    //         for ($i = count($result['kamar']) - 1; $i >= 0; $i--) {
    //             $id =   $result['kamar'][$i]->id;
    //             $result['kamar'][$i]->details = [];
    //             foreach ($tt as $itemTT) {
    //                 if ($itemTT->objectkamarfk == $id) {
    //                     $result['kamar'][$i]->details[] = $itemTT;
    //                 }
    //             }
    //             foreach ($tt as $itemTT) {
    //                 if ($itemTT->objectkamarfk == $id) {
    //                     $kamar[] =  $result['kamar'][$i];
    //                     break;
    //                 }
    //             }
    //         }
    //     } else {
    //         for ($i = count($result['kamar']) - 1; $i >= 0; $i--) {
    //             $id =   $result['kamar'][$i]->id;
    //             $result['kamar'][$i]->details = [];
    //             foreach ($tt as $itemTT) {
    //                 if ($itemTT->objectkamarfk == $id) {
    //                     $result['kamar'][$i]->details[] = $itemTT;
    //                 }
    //             }
    //         }
    //         $kamar[] = $result['kamar'];
    //     }
    //     return $this->respond($kamar);
    // }

    //function baru
    public function RanapKamarByKelas(Request $r)
    {
        $result['kamar'] = DB::table('kamar_m as kmr')
            ->join('ruangan_m as ru', 'ru.id', '=', 'kmr.objectruanganfk')
            ->join('kelas_m as kl', 'kl.id', '=', 'kmr.objectkelasfk')
            ->select(
                'kmr.id',
                'kmr.namakamar',
                'kl.id as id_kelas',
                'kl.namakelas',
                'ru.id as id_ruangan',
                'ru.namaruangan',
                'kmr.jumlakamarisi',
                'kmr.qtybed'
            )
            ->where('kmr.objectkelasfk', $r['id'])
            ->where('kmr.objectruanganfk', $r['idRuangan'])
            ->where('kmr.statusenabled', true)
            ->where('kmr.kdprofile', $this->kdProfile)
            ->orderBy('kmr.namakamar')
            ->get();

        $result['idStatusBedKosong'] = explode(',', $this->settingFix('idStatusBedKosong'));

        // cek di param nilanya cuy
        if (isset($r['isRG']) && $r['isRG'] == 'true') {
            // Jika isRG == true, ambil semua tempat tidur tanpa filter status
            $tt = DB::table('tempattidur_m')
                ->select('id', 'reportdisplay', 'nomorbed', 'objectkamarfk')
                ->where('kdprofile', $this->kdProfile)
                ->where('statusenabled', true)
                ->orderBy('reportdisplay')
                ->get();
        } else {
            // Jika isRG == false, hanya ambil tempat tidur dengan status kosong
            $tt = DB::table('tempattidur_m')
                ->select('id', 'reportdisplay', 'nomorbed', 'objectkamarfk')
                ->where('kdprofile', $this->kdProfile)
                ->where('statusenabled', true)
                ->whereIn('objectstatusbedfk', $result['idStatusBedKosong'])
                ->orderBy('reportdisplay')
                ->get();
        }

        $kamar = [];
        for ($i = count($result['kamar']) - 1; $i >= 0; $i--) {
            $id = $result['kamar'][$i]->id;
            $result['kamar'][$i]->details = [];

            foreach ($tt as $itemTT) {
                if ($itemTT->objectkamarfk == $id) {
                    $result['kamar'][$i]->details[] = $itemTT;
                }
            }

            // Jika isRG == false, hanya tambahkan kamar yang memiliki tempat tidur kosong
            if (!(isset($r['isRG']) && $r['isRG'] == 'true')) {
                foreach ($tt as $itemTT) {
                    if ($itemTT->objectkamarfk == $id) {
                        $kamar[] =  $result['kamar'][$i];
                        break;
                    }
                }
            }
        }

        // Jika isRG == true, tambahkan semua kamar tanpa filter tempat tidur kosong
        if (isset($r['isRG']) && $r['isRG'] == 'true') {
            $kamar = $result['kamar'];
        }

        return $this->respond($kamar);
    }

    public function saveBatalPindah(Request $request)
    {
        DB::beginTransaction();
        try {
            $kdProfile = $this->kdProfile;
            $r_NewPD = $request['pasiendaftar'];
            $r_NewAPD = $request['antrianpasiendiperiksa'];
            $SET['idStatusBedIsi'] = $this->settingFix('idStatusBedIsi');

            $cekPP = PelayananPasien::where('noregistrasifk', $r_NewAPD['norec_apd'])->where('statusenabled', true)->first();
            if ($cekPP) {
                $message = 'Pasien sudah mendapatkan Pelayanan ';
                return $this->respond($cekPP, 400, $message);
            } else {

                $ruanganTerakhir = DB::table('antrianpasiendiperiksa_t as apd')
                    ->leftjoin('ruangan_m as ru', 'ru.id', '=', 'apd.objectruanganasalfk')
                    ->leftjoin('ruangan_m as ruTu', 'ruTu.id', 'apd.objectruanganfk')
                    ->join('pasiendaftar_t as pd', 'pd.norec', 'apd.noregistrasifk')
                    ->select(
                        'ru.objectdepartemenfk',
                        'apd.norec',
                        'apd.objectruanganfk',
                        'apd.objectruanganasalfk',
                        'apd.objectkelasfk',
                        'apd.nobed',
                        'apd.tglkeluar',
                        'ru.namaruangan as ruanganAsal',
                        'ruTu.namaruangan as ruanganTujuan',
                        'pd.norec as norec_pd',
                        'apd.objectkamarfk'
                    )
                    ->where('apd.statusenabled', true)
                    ->where('apd.kdprofile', $kdProfile)
                    ->whereNull('apd.tglkeluar')
                    ->whereNotNull('apd.nobed')
                    ->where('apd.noregistrasifk', $r_NewPD['norec_pd'])
                    ->first();
                // return $ruanganTerakhir;
                AntrianPasienDiperiksa::where('kdprofile', $this->kdProfile)->where('statusenabled', true)
                    ->where('noregistrasifk', $r_NewPD['norec_pd'])
                    ->where('objectruanganfk', $ruanganTerakhir->objectruanganasalfk)
                    ->update(['tglkeluar' => null]);

                $ruanganSebelumnya = DB::table('antrianpasiendiperiksa_t as apd')
                    ->leftjoin('ruangan_m as ru', 'ru.id', 'apd.objectruanganfk')
                    ->leftjoin('ruangan_m as ruAs', 'ruAs.id', '=', 'apd.objectruanganasalfk')
                    ->leftJoin('tempattidur_m as tt', 'tt.id', 'apd.nobed')
                    ->select(
                        'ru.namaruangan',
                        'apd.norec',
                        'apd.tglkeluar',
                        'apd.nobed',
                        'tt.reportdisplay',
                        'tt.objectstatusbedfk',
                        'ruAs.namaruangan as ruanganAsal',
                        'ruAs.id as ruanganasalfk',
                        'ru.id as ruanganlastfk',
                        'apd.objectkelasfk'
                    )
                    ->where('apd.kdprofile', $this->kdProfile)
                    ->where('apd.statusenabled', true)
                    ->where('apd.noregistrasifk', $r_NewPD['norec_pd'])
                    ->where('apd.objectruanganfk', $ruanganTerakhir->objectruanganasalfk)
                    ->first();

                TempatTidur::where('statusenabled', true)->where('kdprofile', $this->kdProfile)->where('id', $ruanganSebelumnya->nobed)->update(['objectstatusbedfk' => $SET['idStatusBedIsi']]);

                PasienDaftar::where('norec', $r_NewPD['norec_pd'])->update([
                    'objectruanganlastfk' => $ruanganSebelumnya->ruanganlastfk,
                    'objectkelasfk' => $ruanganSebelumnya->objectkelasfk,
                ]);

                TempatTidur::where('id', $ruanganTerakhir->nobed)->where('kdprofile', $this->kdProfile)->where('statusenabled', true)->update(['objectstatusbedfk' => $this->settingFix('idStatusBedKosong')]);

                AntrianPasienDiperiksa::where('norec', $ruanganTerakhir->norec)->where('statusenabled', true)
                    ->where('kdprofile', $this->kdProfile)->whereNotNull('objectruanganasalfk')
                    ->update(['statusenabled' => false]);
            }
            DB::commit();
            $result = array(
                "status" => 200,
                "message" => 'Berhasil Batal Pindah Ruangan',
                "result" => 'success',
            );
        } catch (Exception $e) {
            DB::rollBack();
            $result = array(
                "status" => 400,
                "message" => "Something Went Wrong",
                "result"  => $e->getMessage() . $e->getLine()
            );
        }
        return $this->respond($result['result'], $result['status'], $result['message']);
    }

    public function saveBatalPulang(Request $request)
    {
        DB::beginTransaction();
        try {
            $kdProfile = $this->kdProfile;
            $SET['idStatusBedKosong'] = $this->settingFix('idStatusBedKosong');
            $SET['idStatusBedIsi'] = $this->settingFix('idStatusBedIsi');
            $pd = PasienDaftar::where('norec', $request['norec_pd'])->first();
            PasienDaftar::where('norec', $request['norec_pd'])->update([
                'objectstatuskeluarfk' => null,
                'objectstatuspulangfk' => null,
                'objectkondisipasienfk' => null,
                'tglpulang' => null,
                'objecthubungankeluargaambilpasienfk'  => null,
                'namalengkapambilpasien'  => null,
            ]);

            AksesEMR::where('pasienfk', $pd->nocmfk)->where('statusenabled', true)->where('kdprofile', $this->kdProfile)
                ->update([
                    'tglberakhir' => null,
                    'deskripsi' => 'Kunci EMR dibuka, Pasien Batal Pulang',
                    'objectkelompokuserfk' => '',
                    'pegawaipemohonfk' => null,
                ]);

            AntrianPasienDiperiksa::where('noregistrasifk', $pd->norec)
                ->where('objectruanganfk', $pd->objectruanganlastfk)
                ->update(['tglkeluar' => null]);
            $apd = AntrianPasienDiperiksa::where('noregistrasifk', $pd->norec)
                ->where('objectruanganfk', $pd->objectruanganlastfk)
                ->first();

            DB::table('tempattidur_m')
                ->where('id', $apd->nobed)
                ->update(['objectstatusbedfk' =>  $SET['idStatusBedIsi']]);

            $transStatus = 'true';
        } catch (\Exception $e) {
            $transStatus = 'false';
        }
        if ($transStatus == 'true') {
            $transMessage = "Sukses";
            DB::commit();
            $result = array(
                "status" => 200,
                "result" => "Berhasil Batal Pulang"
            );
        } else {
            $transMessage = "Simpan Gagal";
            DB::rollBack();
            $result = array(
                "status" => 400,
                "result"  => $e->getMessage() . $e->getLine()
            );
        }
        return $this->respond($result['result'], $result['status'], $transMessage);
    }

    public function updateRencanaMutasi(Request $request)
    {
        DB::beginTransaction();
        try {
            $kdProfile = $this->kdProfile;
            PasienDaftar::where('norec', $request['norec_pd'])->update([
                'isRencanaMutasi' => true,
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
                "result" => "Berhasil Simpan"
            );
        } else {
            $transMessage = "Simpan Gagal";
            DB::rollBack();
            $result = array(
                "status" => 400,
                "result"  => $e->getMessage() . $e->getLine()
            );
        }
        return $this->respond($result['result'], $result['status'], $transMessage);
    }

    public function savePindahBed(Request $request)
    {
        DB::beginTransaction();
        try {
            $kdProfile = $this->kdProfile;
            $r_NewPD = $request['pasiendaftar'];
            $r_NewAPD = $request['antrianpasiendiperiksa'];
            $r_Pasien = $request['pasien'];
            $SET['idStatusBedKosong'] = $this->settingFix('idStatusBedKosong');
            $SET['idStatusBedIsi'] = $this->settingFix('idStatusBedIsi');

            PasienDaftar::where('norec', $r_NewPD['norec_pd'])->update([
                'objectkelasfk' => $r_NewAPD['objectkelasfk'],
                'objectkelasrawatfk' => $r_NewAPD['objectkelasrawatfk'],
            ]);

            AntrianPasienDiperiksa::where('norec', $r_NewAPD['norec_apd'])
                ->update([
                    'tglkeluar' => isset($r_NewAPD['tglkeluar']) ? $r_NewAPD['tglkeluar'] : date('Y-m-d H:i:s')
                ]);

            $apd = AntrianPasienDiperiksa::where('norec', $r_NewAPD['norec_apd'])->first();

            TempatTidur::where('id', $apd->nobed)
                ->where('kdprofile', $this->kdProfile)
                ->where('statusenabled', true)
                ->lockForUpdate()
                ->update(['objectstatusbedfk' =>  $SET['idStatusBedKosong']]);

            $this->historyBED([
                "tempattidurfk" => $apd->nobed,
                "statusbedfk" => $SET['idStatusBedKosong'],
                "ruanganfk" => $apd->objectruanganfk,
                "kamarfk" => $apd->objectkamarfk,
            ]);

            $dataAPD = AntrianPasienDiperiksa::where('norec', $r_NewAPD['norec_apd'])->first();
            $dataAPD->kdprofile = $kdProfile;
            $dataAPD->statusenabled = true;
            $dataAPD->objectruanganfk = $r_NewPD['objectruangantujuanfk'];
            $dataAPD->objectkamarfk = $r_NewAPD['objectkamarfk'];
            $dataAPD->objectkelasfk = $r_NewAPD['objectkelasfk'];
            $dataAPD->kelasrawatfk = $r_NewAPD['objectkelasrawatfk'];
            $dataAPD->nobed = $r_NewAPD['objectbedfk'];
            $dataAPD->noregistrasifk = $r_NewPD['norec_pd'];
            $dataAPD->tglkeluar = null;
            $dataAPD->tglmasuk = $r_NewAPD['tglkeluar'];
            $dataAPD->update();

            $cek = DB::table('tempattidur_m')
                ->where('kdprofile', $this->kdProfile)
                ->where('statusenabled', true)
                ->where('id',  $dataAPD->nobed)
                ->first();

            // if (!empty($cek) && $cek->objectstatusbedfk == $SET['idStatusBedIsi']) {
            //     DB::rollBack();
            //     $transMessage = 'Bed Sudah Terisi, Silakan Pilih Bed Lain';
            //     $result = array("status" => 400, 'message' => $transMessage, "result"  => $cek);
            //     return $this->respond($result, $result['status'], $transMessage);
            // }

            DB::table('tempattidur_m')
                ->where('kdprofile', $this->kdProfile)
                ->where('statusenabled', true)
                ->where('id', $dataAPD->nobed)
                ->lockForUpdate()
                ->update(['objectstatusbedfk' =>  $SET['idStatusBedIsi']]);

            $this->historyBED([
                "tempattidurfk" => $dataAPD->nobed,
                "statusbedfk" => $SET['idStatusBedIsi'],
                "ruanganfk" => $r_NewPD['objectruangantujuanfk'],
                "kamarfk" => $r_NewAPD['objectkamarfk'],
            ]);

            // $this->LOGGING(
            //     'Pindah Pasien',
            //     $r_NewPD['norec_pd'],
            //     'pasiendaftar_t',
            //     'Pindah Pasien atas nama ' .
            //         $r_Pasien['namapasien'] . ' (' . $r_Pasien['nocm'] . ') - ' . $r_Pasien['noregistrasi']
            // );

            DB::commit();

            $objetoRequest = new \Illuminate\Http\Request();
            $objetoRequest['idruangan'] =   $dataAPD->objectruanganfk;
            $objetoRequest['idkelas'] =   $dataAPD->objectkelasfk;
            $aplicare = app('App\Http\Controllers\Bridging\BridgingBPJSCtrl')->updateAplicaresBedAfter($objetoRequest);


            $result = array(
                "status" => 200,
                "result" => $dataAPD,
                "message" => "Pasien Pindah Bed Berhasil",
                'Aplicare' => $aplicare,
            );
        } catch (Exception $e) {
            DB::rollBack();
            $result = array(
                "status" => 400,
                "result"  => $e->getMessage(). $e->getLine(),
                "message" => "Somthing Want Wrong",
            );
        }

        return $this->respond($result['result'], $result['status'], $result['message']);
    }
}
