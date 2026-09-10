<?php

namespace App\Http\Controllers\Registrasi;

use App\Http\Controllers\Controller;
use App\Models\Master\AsalRujukan;
use App\Models\Master\JenisPelayanan;
use App\Models\Master\Kelas;
use App\Models\Master\KelompokPasien;
use App\Models\Master\Pasien;
use App\Models\Master\Pegawai;
use App\Models\Master\Ruangan;
use App\Models\Master\TempatTidur;
use App\Models\Transaksi\AntrianPasienDiperiksa;
use App\Models\Transaksi\AntrianPasienRegistrasi;
use App\Models\Transaksi\PasienDaftar;
use App\Traits\Valet;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MutasiPasienCtrl extends Controller
{
    use Valet;
    public function __construct()
    {
        parent::__construct($is_encrypt = true);
    }

    public function headMutasi(Request $r)
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
            ->JOIN('antrianpasiendiperiksa_t as apd', 'apd.noregistrasifk', '=', 'pd.norec')
            ->JOIN('ruangan_m as ru', 'ru.id', '=', 'apd.objectruanganfk')
            ->JOIN('departemen_m as dp', 'dp.id', '=', 'ru.objectdepartemenfk')
            ->JOIN('kelompokpasien_m as kp', 'kp.id', '=', 'pd.objectkelompokpasienlastfk')
            ->JOIN('kelas_m as kl', 'kl.id', '=', 'pd.objectkelasfk')
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
                'pd.objectkelasfk',
                'kl.namakelas',
                'pd.nocmfk',
                'apd.tglmasuk',
                'apd.tglkeluar',
                'pd.objectkelompokpasienlastfk',
                'pd.objectrekananfk',
                'pd.jenispelayanan as jenispelayananfk'
            )
            ->where('pd.kdprofile', (int)$this->kdProfile)
            ->where('pd.statusenabled', true)
            ->where('pd.statusenabled', true)
            ->where('pd.nocmfk', $r['nocmfk'])
            ->where('pd.norec', $r['norec_pd'])
            ->get();
        $last = array();
        $tgl = date('2000-01-01 00:00');
        foreach ($registrasi as $d) {
            if ($d->objectruanganlastfk == $d->objectruanganfk && $tgl < $d->tglmasuk) {
                $tgl = $d->tglmasuk;
                $last  = $d;
            }
        }
        $result['pasien'] = $data;
        $result['registrasi'] = $registrasi;
        $result['last_registrasi'] = $last;
        $result['idKelompokPasienBPJS'] = explode(',', $this->settingFix('idKelompokPasienBPJS'));
        $result['idKelompokPasienUMUM'] = $this->settingFix('idKelompokPasienUMUM');

        $set = explode(',', $this->settingFix('kdDepartemenRanapFix'));
        $result['ruangan'] = Ruangan::mine()->whereIn('objectdepartemenfk', $set)->get();

        $result['jenispelayanan'] = JenisPelayanan::mine()->get();
        $result['asalrujukan'] = AsalRujukan::mine()->get();
        $result['kelompokpasien'] = KelompokPasien::mine()->get();
        $result['kelas'] = Kelas::mine()->get();
        $result['as'] = '@epic';

        return $this->respond($result);
    }


    public function dokterMutasi(Request $r)
    {
        $result['idJenisPegawaiDokter'] = explode(',', $this->settingFix('idJenisPegawaiDokter'));
        $result['dokter'] =
            Pegawai::mine()
            ->where('objectjenispegawaifk', $result['idJenisPegawaiDokter'])
            ->search($r['name'])
            ->paging($r['limit'])
            ->get();
        return $this->respond($result);
    }
    public function listKelasMutasi(Request $r)
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
    public function listKamarMutasi(Request $r)
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
            ->where('kmr.objectruanganfk', $r['idRuangan'])
            ->where('kmr.objectkelasfk', $r['id'])
            ->where('kmr.statusenabled', true)
            ->where('kmr.kdprofile', $this->kdProfile)
            ->orderBy('kmr.namakamar')
            ->get();

        $result['idStatusBedKosong'] = explode(',', $this->settingFix('idStatusBedKosong'));
        $tt = DB::table('tempattidur_m')
            ->select('id', 'reportdisplay', 'nomorbed', 'objectkamarfk')
            ->where('kdprofile', $this->kdProfile)
            ->where('statusenabled', true)
            ->where('objectstatusbedfk', $result['idStatusBedKosong'])
            ->orderBy('reportdisplay')
            ->get();

        $kamar = [];
        if (isset($r['isRG']) && $r['isRG'] != 'undefined' && $r['isRG'] == 'false') {
            for ($i = count($result['kamar']) - 1; $i >= 0; $i--) {
                $id =   $result['kamar'][$i]->id;
                $result['kamar'][$i]->details = [];
                foreach ($tt as $itemTT) {
                    if ($itemTT->objectkamarfk == $id) {
                        $result['kamar'][$i]->details[] = $itemTT;
                    }
                }
                foreach ($tt as $itemTT) {
                    if ($itemTT->objectkamarfk == $id) {
                        $kamar[] =  $result['kamar'][$i];
                        break;
                    }
                }
            }
        } else {
            for ($i = count($result['kamar']) - 1; $i >= 0; $i--) {
                // $id =   $result['kamar'][$i]->id;
                // $result['kamar'][$i]->details = [];
                // foreach ($tt as $itemTT) {
                //     if ($itemTT->objectkamarfk == $id) {
                //         $result['kamar'][$i]->details[] = $itemTT;
                //     }
                // }
                 $kamar[] = $result['kamar'][$i];

            }
        }
        return $this->respond($kamar);
    }
    public function listPenjaminMutasi(Request $r)
    {
        $result = DB::table('mapkelompokpasientopenjamin_m as mkp')
            ->join('rekanan_m as rk', 'rk.id', '=', 'mkp.kdpenjaminpasien')
            ->select('rk.id', 'rk.namarekanan')
            ->where('mkp.objectkelompokpasienfk', $r['id'])
            ->where('mkp.statusenabled', true)
            ->where('rk.statusenabled', true)
            ->distinct()
            ->where('mkp.kdprofile', (int)$this->kdProfile)
            ->orderBy('rk.namarekanan')
            ->get();
        return $this->respond($result);
    }

    // public function saveMutasiPasien(Request $request) {
    //     $kdProfile = $this->kdProfile;
    //          DB::beginTransaction();
    //          if ($request['tglpulang']!= 'null'){
    //              $ddddd=PasienDaftar::where('norec', $request['norec'])
    //                  ->update([
    //                          'tglpulang' => null,
    //                          'objectruanganlastfk' => $request['ruangan']['id'],
    //                          'objectkelompokpasienlastfk' => $request['kelompokPasien']['id'],
    //                          'objectpegawaifk' => $request['pegawai']['id'],
    //                          'objectkelasfk' => $request['kelas']['id'],
    //                          'nostruklastfk' => null,
    //                          'nosbmlastfk' => null

    //                      ]
    //                  );
    //          }
    //          try{

    //              $countNoAntrian = AntrianPasienDiperiksa::where('objectruanganfk',$request['ruangan']['id'])
    //                  ->where('tglregistrasi', '>=', $request['tglRegisDateOnly'].' 00:00')
    //                  ->where('tglregistrasi', '<=', $request['tglRegisDateOnly'].' 23:59')
    //                  ->count('norec');
    //              $noAntrian = $countNoAntrian + 1;

    //              $dataAPD = new AntrianPasienDiperiksa();
    //              $dataAPD->kdprofile = $kdProfile;
    //              $dataAPD->statusenabled = true;
    //              $dataAPD->norec =  $dataAPD->generateNewId();
    //              $dataAPD->objectasalrujukanfk =  $request['asalRujukan']['id'];
    //              $dataAPD->objectkamarfk = $request['kamar']['id'];
    //              $dataAPD->objectkasuspenyakitfk = null;
    //              $dataAPD->objectkelasfk = $request['kelas']['id'];
    //              $dataAPD->noantrian = $noAntrian; //count tgl pasien perruanga
    //              $dataAPD->nobed = $request['nomorTempatTidur']['id'];
    //              $dataAPD->noregistrasifk = $request['noRecPasienDaftar'];
    //              $dataAPD->objectpegawaifk = $request['pegawai']['id'];
    //              $dataAPD->objectruanganfk = $request['ruangan']['id'];
    //              $dataAPD->statusantrian = 0;
    //              $dataAPD->statuskunjungan = $request['statusPasien'];
    //              $dataAPD->statuspasien = 1;
    //              $dataAPD->tglregistrasi =  $request['tglRegistrasi'];
    //              $dataAPD->objectruanganasalfk = $request['objectruanganasalfk'];
    //              $dataAPD->tglkeluar = null;
    //              $dataAPD->tglmasuk =$request['tglRegistrasi'];
    //              $dataAPD->israwatgabung = null;

    //              $dataAPD->save();

    //              //update statusbed jadi Isi
    //              TempatTidur::where('id',$request['nomorTempatTidur']['id'])->update(['objectstatusbedfk'=>1]);

    //              $transStatus = 'true';
    //     } catch (\Exception $e) {
    //         $transStatus = 'false';
    //     }

    //     if ($transStatus == 'true') {
    //         $transMessage = "Sukses";
    //         DB::commit();
    //         $result = array(
    //             "status" => 200,
    //             "result" => array(
    //                 "registrasi"  => array(
    //                     "pd" => $ddddd,
    //                     "apd" => $dataAPD,
    //                 ),
    //                 "as" => '@epic',
    //             ),
    //         );
    //     } else {
    //         $transMessage = "Simpan Gagal";
    //         DB::rollBack();
    //         $result = array(
    //             "status" => 400,
    //             "result"  => null
    //         );
    //     }
    //     return $this->respond($result['result'], $result['status'], $transMessage);
    // }

    public function saveMutasi(Request $request)
    {
        DB::beginTransaction();
        try {
            //? Data
            $kdProfile = $this->kdProfile;
            $r_NewPD = $request['pasiendaftar'];
            $r_NewAPD = $request['antrianpasiendiperiksa'];
            $SET['idStatusBedKosong'] = $this->settingFix('idStatusBedKosong');
            $SET['idStatusBedIsi'] = $this->settingFix('idStatusBedIsi');
            $uuidPD = !empty($r_NewPD['norec_pd']) ? $r_NewPD['norec_pd'] : (!empty($r_NewPD['norec']) ? $r_NewPD['norec'] : ''); //? norec_pd

            //? New var
            $ismutasilangsung = false;

            //? Pasien mutasi langsung
            if(isset($r_NewPD) && $uuidPD == ''){
                $ismutasilangsung = true;
                $noregistrasi = $this->SEQUENCE(new PasienDaftar, 'noregistrasi', 10, date('ymd'), $kdProfile);
                if ($noregistrasi == '') {
                    abort(400, 'SEQ ERROR');
                }
                $model_PD = new PasienDaftar();
                $model_PD->norec = $model_PD->generateNewId();
                $model_PD->kdprofile = $kdProfile;
                $model_PD->statusenabled = true;
                $model_PD->objectruanganasalfk = $r_NewPD['objectruanganlastfk'];
                $model_PD->statuspasien = $r_NewPD['statuspasien'];
                $model_PD->objectruanganlastfk = $r_NewPD['objectruanganlastfk'];
                $model_PD->objectpegawaifk =  $r_NewPD['objectpegawaifk'];
                $model_PD->objectpegawairawatbersamafk =  isset($r_NewPD['objectpegawairawatbersamafk'])?$r_NewPD['objectpegawairawatbersamafk']:null;
                $model_PD->jenispelayanan =   $r_NewPD['jenispelayananfk'];
                $model_PD->objectkelasfk = $r_NewPD['objectkelasfk'];
                $model_PD->objectkelasrawatfk = $r_NewPD['objectkelasrawatfk'];
                $model_PD->tglpulang = null;
                $model_PD->objectkelompokpasienlastfk = $r_NewPD['objectkelompokpasienlastfk'];
                $model_PD->nocmfk = $r_NewPD['nocmfk'];
                $model_PD->objectrekananfk = $r_NewPD['objectrekananfk'];
                $model_PD->ismobilejkn = null;
                $model_PD->antrianpasienregistrasifk = null;
                $model_PD->noreservasi = null;
                $model_PD->tglregistrasi =  $r_NewPD['tglregistrasi'];
                $model_PD->asalrujukanfk =  $r_NewPD['asalrujukanfk'];
                $model_PD->keteranganasalrujukan = $r_NewPD['asalrujukanfk'] == 5 ? null : (isset($r_NewPD['keteranganasalrujukan'])?$r_NewPD['keteranganasalrujukan']:null);
                $model_PD->noregistrasi = $noregistrasi;
                $model_PD->petugas = $this->getNamaPegawai();
                $model_PD->iskiosk = null;
                $model_PD->iskelastitip = isset($r_NewPD['iskelastitip']) ? $r_NewPD['iskelastitip'] : null;
                $model_PD->isnaikkelas = isset($r_NewPD['isnaikkelas']) ? $r_NewPD['isnaikkelas'] : null;
                $model_PD->ispenjadwalankemoterapi = isset($cek_jk) ? true : null;
                $model_PD->save();

                $uuidPD = $model_PD->norec;
            }

            $cekDepartemen = DB::table('pasiendaftar_t as pd')
                ->join('ruangan_m as ru', 'pd.objectruanganlastfk', 'ru.id')
                ->select('ru.objectdepartemenfk', 'ru.namaruangan', 'pd.noregistrasi')
                ->where('pd.norec', $uuidPD)
                ->where('pd.kdprofile', $this->kdProfile)
                ->first();

            $isIGD = (isset($cekDepartemen->objectdepartemenfk) ? $cekDepartemen->objectdepartemenfk : null) == $this->settingFix('idDepartemenIGD') ? 'true' : 'false';
            if ($isIGD == 'true') {
                //? Update tanggal keluar APD
                AntrianPasienDiperiksa::where('norec', $r_NewAPD['norec_apd'])
                    ->where('statusenabled', true)
                    ->where('kdprofile', $this->kdProfile)
                    ->update(['tglkeluar' => date('Y-m-d H:i:s')]);

                $cekRI = null;
            } else {
                if ($ismutasilangsung == false && $cekDepartemen->objectdepartemenfk != 18){
                    $cekRI = PasienDaftar::where('norec', $uuidPD)
                        ->whereNull('tglpulang')
                        ->where('statusenabled', true)
                        ->first();
                }
            }

            //? Validasi rawat inap
            if (!empty($cekRI)) {
                DB::rollBack();
                $transMessage = 'Pasien Terdaftar di Rawat Inap No. Registrasi : '
                    . $cekRI->noregistrasi . ' (' . $cekRI->tglregistrasi . ')';
                $result = array("status" => 400, "result"  => $cekRI);
                return $this->respond($result['result'], $result['status'], $transMessage);
            }

            //? Mungkin untuk mutasi rawat inap langsung, soalnya ga ada yang pakai function ini selain mutasi rawat inap
            if ($request['tglpulang'] != 'null') {
                PasienDaftar::where('norec', $uuidPD)->update([
                    'tglpulang' => null,
                    'objectruanganlastfk' => $r_NewPD['objectruangantujuanfk'],
                    'objectkelasfk' => $r_NewPD['objectkelasfk'],
                    'objectkelasrawatfk' => $r_NewPD['objectkelasrawatfk'],
                    'objectkelompokpasienlastfk' => $r_NewPD['objectkelompokpasienlastfk'],
                    'objectpegawaifk' => $r_NewPD['objectpegawaifk'],
                    'nostruklastfk' => null,
                    'nosbmlastfk' => null,
                    'iskelastitip' => isset($r_NewPD['iskelastitip']) ? $r_NewPD['iskelastitip'] : null,
                    'isnaikkelas' => isset($r_NewPD['isnaikkelas']) ? $r_NewPD['isnaikkelas'] : null
                ]);
            }

            $countNoAntrian = AntrianPasienDiperiksa::where('objectruanganfk', $r_NewPD['objectruangantujuanfk'])
                ->where('tglregistrasi', '>=', date('Y-m-d', strtotime($r_NewAPD['tglregistrasi'])) . ' 00:00')
                ->where('tglregistrasi', '<=',  date('Y-m-d', strtotime($r_NewAPD['tglregistrasi'])) . ' 23:59')
                ->count();

            $noAntrian = $countNoAntrian + 1;
            $dataAPD = new AntrianPasienDiperiksa;
            $dataAPD->norec = $dataAPD->generateNewId();
            $dataAPD->kdprofile = $kdProfile;
            $dataAPD->statusenabled = true;
            $dataAPD->objectruanganfk = $r_NewPD['objectruangantujuanfk'];
            $dataAPD->objectasalrujukanfk =  isset($r_NewAPD['objectasalrujukanfk']) ? $r_NewAPD['objectasalrujukanfk'] : 5;
            $dataAPD->objectkamarfk = $r_NewAPD['objectkamarfk'];
            $dataAPD->objectpegawaifk = isset($r_NewPD['objectpegawaifk']) ? $r_NewPD['objectpegawaifk'] : null;
            $dataAPD->objectkelasfk = $r_NewAPD['objectkelasfk'];
            $dataAPD->kelasrawatfk = $r_NewAPD['objectkelasrawatfk'];
            $dataAPD->noantrian = $noAntrian;
            // $dataAPD->nobed = $r_NewAPD['objectbedfk'];
            $dataAPD->noregistrasifk = $uuidPD;
            $dataAPD->statusantrian = 0;
            $dataAPD->status = "Belum Dipanggil";
            $dataAPD->statuskunjungan = $r_NewPD['statuspasien'];
            $dataAPD->statuspasien = 1;
            $dataAPD->tglregistrasi =  $r_NewPD['tglregistrasi'];
            $dataAPD->objectruanganasalfk = isset($r_NewPD['objectruanganasalfkfk']) ? $r_NewPD['objectruanganasalfkfk'] : null;
            $dataAPD->tglkeluar = null;
            $dataAPD->tglmasuk = date('Y-m-d H:i:s');
            $dataAPD->israwatgabung = $r_NewAPD['israwatgabung'];
            $dataAPD->noregistrasi = isset($cekDepartemen) ? $cekDepartemen->noregistrasi : null;

            //? Set Bed
            if ($r_NewAPD['israwatgabung']) {
                $dataAPD->nobed = null;
            } else {
                $dataAPD->nobed = $r_NewAPD['objectbedfk'];
            }

            $dataAPD->save();

            $dataPasien = DB::table('pasiendaftar_t as pd')
                ->join('ruangan_m as rutu', 'rutu.id', 'pd.objectruanganlastfk')
                ->join('ruangan_m as ruas', 'ruas.id', 'pd.objectruanganasalfk')
                ->join('pasien_m as ps', 'ps.id', 'pd.nocmfk')
                ->select('ps.namapasien', 'rutu.namaruangan as ruangantujuan', 'ruas.namaruangan as ruanganasal', 'pd.noregistrasi', 'ps.nocm')
                ->where('pd.kdprofile', $this->kdProfile)
                ->where('pd.statusenabled', true)
                ->where('pd.norec', $uuidPD)
                ->first();

            //? Update Status BED
            $cek = DB::table('tempattidur_m')
                ->where('kdprofile', $this->kdProfile)
                ->where('statusenabled', true)
                ->where('id',  $dataAPD->nobed)
                ->first();

            //? Cek Bed Terisi
            if (!empty($cek) && $cek->objectstatusbedfk == $SET['idStatusBedIsi'] ) {
                DB::rollBack();
                $transMessage = 'Bed Sudah Terisi, Silakan Pilih Bed Lain';
                $result = array("status" => 400 ,'message' => $transMessage, "result"  => $cek);
                return $this->respond($result, $result['status'], $transMessage);
            }

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
                'Mutasi Pasien',
                $uuidPD,
                'pasiendaftar_t',
                'Mutasi pasien atas nama ' . $dataPasien->namapasien . ' (' .  $dataPasien->nocm  . ') ' .
                ' dari ' . $dataPasien->ruanganasal . ' ke ' . $dataPasien->ruangantujuan . ' - '. $dataPasien->noregistrasi
            );

            $objetoRequest3 = new Request();
            $objetoRequest3['norec'] = $uuidPD;
            $objetoRequest3['norec_apd'] = $dataAPD->norec;
            $objetoRequest3['user'] = $this->getNamaPegawai();
            app('App\Http\Controllers\Registrasi\RegistrasiRuanganCtrl')->saveAdministrasi($objetoRequest3);

            DB::commit();
            $result = array(
                "status" => 200,
                "message" => 'Berhasil',
                "result" => $dataAPD,
            );
        } catch (Exception $e) {
            DB::rollBack();
            $result = [
                "status" => 400,
                "message" => "Something Went Wrong",
                "result"  => $e->getMessage() . $e->getLine()
            ];
        }
        return $this->respond($result['result'], $result['status'], $result['message']);
    }

    public function editMutasi(Request $request)
    {
        DB::beginTransaction();
        try {
            $kdProfile = $this->kdProfile;
            $PD = $request['pasiendaftar'];
            $APD = $request['antrianpasiendiperiksa'];
            $SET['idStatusBedKosong'] = $this->settingFix('idStatusBedKosong');
            $SET['idStatusBedIsi'] = $this->settingFix('idStatusBedIsi');
            $data_PD = PasienDaftar::where('norec', $PD['norec'])->select('objectruanganasalfk')->first();
            $data_APD = AntrianPasienDiperiksa::where('norec', $APD['norec'])->select('nobed')->first();
            $ruangan = Ruangan::where('id', $PD['objectruangantujuanfk'])->first();

            $dataPasien = DB::table('pasiendaftar_t as pd')
                ->join('ruangan_m as ruas', 'ruas.id', 'pd.objectruanganasalfk')
                ->join('pasien_m as ps', 'ps.id', 'pd.nocmfk')
                ->select('ps.namapasien', 'ruas.namaruangan as ruanganasal', 'pd.noregistrasi', 'ps.nocm')
                ->where('pd.kdprofile', $kdProfile)
                ->where('pd.statusenabled', true)
                ->where('pd.norec', $PD['norec'])
                ->first();

            PasienDaftar::where('norec', $PD['norec'])->update([
                'tglpulang' => null,
                'objectruanganlastfk' => $PD['objectruangantujuanfk'],
                'objectkelasfk' => $PD['objectkelasfk'],
                'objectkelasrawatfk' => $PD['objectkelasrawatfk'],
                'objectkelompokpasienlastfk' => $PD['objectkelompokpasienlastfk'],
                'objectpegawaifk' => $PD['objectpegawaifk'],
                'iskelastitip' => isset($PD['iskelastitip']) ? $PD['iskelastitip'] : null,
                'isnaikkelas' => isset($PD['isnaikkelas']) ? $PD['isnaikkelas'] : null
            ]);

            AntrianPasienDiperiksa::where('norec', $APD['norec'])->update([
                'objectruanganasalfk' => $data_PD->objectruanganasalfk,
                'objectruanganfk' => $PD['objectruangantujuanfk'],
                'objectasalrujukanfk' => isset($PD['asalrujukanfk']) ? $PD['asalrujukanfk'] : 5,
                'objectkamarfk' => $APD['objectkamarfk'],
                'objectkelasfk' => $APD['objectkelasfk'],
                'kelasrawatfk' => $APD['objectkelasrawatfk'],
                'israwatgabung' => $APD['israwatgabung'],
                'nobed' => $APD['nobed'],
                'tglkeluar' => null,
            ]);

            //? Bed Terisi = 1, Bed Kosong = 2
            $cek = DB::table('tempattidur_m')
                ->where('kdprofile', $kdProfile)
                ->where('statusenabled', true)
                ->where('id',  $APD['nobed'])
                ->first();

            //? Jika sudah terisi, dan pasien tersebut mau pindah bed yang berbeda
            if (!empty($cek) && $cek->objectstatusbedfk == $SET['idStatusBedIsi'] && $data_APD['nobed'] != $APD['nobed']) {
                DB::rollBack();
                $transMessage = 'Bed Sudah Terisi, Silakan Pilih Bed Lain';
                $result = array("status" => 400 ,'message' => $transMessage, "result"  => $cek);
                return $this->respond($result, $result['status'], $transMessage);
            }

            //? Update status BED ketika sudah berbeda nobed
            if ($data_APD['nobed'] != $APD['nobed']) {
                // Update bed ketika kosong menjadi terisi
                DB::table('tempattidur_m')
                    ->where('kdprofile', $kdProfile)
                    ->where('statusenabled', true)
                    ->where('id', $APD['nobed'])
                    ->lockForUpdate()
                    ->update(['objectstatusbedfk' =>  $SET['idStatusBedIsi']]);

                // Update bed sebelumnya yang sudah terisi/kosong, menjadi kosong
                DB::table('tempattidur_m')
                    ->where('kdprofile', $kdProfile)
                    ->where('statusenabled', true)
                    ->where('id', $data_APD->nobed)
                    ->lockForUpdate()
                    ->update(['objectstatusbedfk' =>  $SET['idStatusBedKosong']]);
            }

            $this->historyBED([
                "tempattidurfk" => $APD['nobed'],
                "statusbedfk" => $SET['idStatusBedIsi'],
                "ruanganfk" => $PD['objectruangantujuanfk'],
                "kamarfk" => $APD['objectkamarfk'],
            ]);

            $this->LOGGING(
                'Edit Mutasi Pasien',
                $PD['norec'],
                'pasiendaftar_t',
                'Edit Mutasi pasien atas nama ' . $dataPasien->namapasien . ' (' .  $dataPasien->nocm  . ') ' .
                ' dari ' . $dataPasien->ruanganasal . ' ke ' . $ruangan->namaruangan . ' - '. $dataPasien->noregistrasi
            );

            DB::commit();
            $APD_result = AntrianPasienDiperiksa::where('norec', $APD['norec'])->first();
            $result = array(
                "status" => 200,
                "message" => 'Berhasil',
                "result" => $APD_result
            );
        } catch (Exception $e) {
            DB::rollBack();
            $result = [
                "status" => 400,
                "message" => "Something Went Wrong",
                "result"  => $e->getMessage() . $e->getLine()
            ];
        }
        return $this->respond($result['result'], $result['status'], $result['message']);
    }
}
