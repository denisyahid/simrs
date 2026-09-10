<?php

namespace App\Http\Controllers\Reservasi;

use App\Http\Controllers\Controller;
use App\Models\Master\Alamat;
use App\Models\Master\Agama;
use App\Models\Master\HubunganKeluarga;
use App\Models\Master\JenisKelamin;
use App\Models\Master\Pasien;
use App\Models\Master\Pegawai;
use App\Models\Master\Ruangan;
use App\Models\Master\KelompokPasien;
use App\Models\Master\Kebangsaan;
use App\Models\Transaksi\AnggotaKeluarga;
use App\Models\Transaksi\AntrianPasienRegistrasi;
use App\Models\Transaksi\PasienDaftar;
use App\Models\Transaksi\StrukBuktiPenerimaan;
use App\Models\Transaksi\StrukBuktiPengeluaran;
use App\Models\Transaksi\StrukPelayanan;
use App\Traits\Valet;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Ramsey\Uuid\Uuid;

class ReservasiMobileCtrl extends Controller
{
    use Valet;
    public function __construct()
    {
        parent::__construct($is_encrypt = true);
    }
    public function getComboReservasi(Request $request)
    {
        $kdProfile = $this->kdProfile;
        // return  $kdProfile;
        $deptJalan = explode(',', $this->settingFix('kdDepartemenRawatJalanFix'));
        $kdDepartemenRawatJalan = [];
        foreach ($deptJalan as $item) {
            $kdDepartemenRawatJalan[] =  (int)$item;
        }

        $dataRuanganJalan = DB::table('ruangan_m as ru')
            ->select('ru.id', 'ru.namaruangan', 'ru.objectdepartemenfk', 'ru.noruangan', 'ru.kdinternal')
            ->where('ru.statusenabled', true)
            ->where('ru.kdprofile', $kdProfile)
            ->whereNotIn('ru.id', [235,280])
            ->wherein('ru.objectdepartemenfk', $kdDepartemenRawatJalan)
            ->orderBy('ru.namaruangan')
            ->get();
        $jk = DB::table('jeniskelamin_m')
            ->select('id', 'jeniskelamin')
            ->where('statusenabled', true)
            ->orderBy('jeniskelamin')
            ->get();
        $kdJenisPegawaiDokter = $this->settingFix('idJenisPegawaiDokter');

        $dkoter = DB::table('pegawai_m')
            ->select('id', 'namalengkap')
            ->where('statusenabled', true)
            ->where('kdprofile', $kdProfile)
            ->where('objectjenispegawaifk', $kdJenisPegawaiDokter)
            ->orderBy('namalengkap')
            ->get();

        $kelompokPasien = DB::table('kelompokpasien_m')
            ->select('id', 'kelompokpasien')
            ->where('kdprofile', $kdProfile)
            ->where('statusenabled', true)
            ->orderBy('kelompokpasien')
            ->get();

        $now = date('Y-m-d');

        $agama = Agama::mine()->get();
        $kebangsaan = Kebangsaan::mine()->get();

        $libur = DB::table('slottinglibur_m')
            ->select(DB::raw("to_char(tgllibur,'yyyy/MM/dd') as tgllibur,id,statusenabled"))
            ->where('statusenabled', true)
            ->where('kdprofile', $kdProfile)
            ->whereRaw("to_char(tgllibur,'yyyy-MM-dd') >= '$now'")
            ->orderBy('tgllibur')
            ->get();

        $result = array(
            'jeniskelamin' => $jk,
            'maxJamReservasi' => $this->settingFix('maxJamReservasi'),
            'isRentangReservasi' => (float) $this->settingFix('isRentangReservasi'),
            'kelompokpasien' => $kelompokPasien,
            'libur' => $libur,
            'ruanganrajal' => $dataRuanganJalan,
            'dokter' => $dkoter,
            'agama' => $agama,
            'kebangsaan' => $kebangsaan,
        );

        return $this->respond($result);
    }
    public function getRuanganRajal(Request $request)
    {
        $kdProfile = $this->kdProfile;
        // return  $kdProfile;
        $deptJalan = explode(',', $this->settingFix('kdDepartemenRawatJalanFix'));
        $kdDepartemenRawatJalan = [];
        foreach ($deptJalan as $item) {
            $kdDepartemenRawatJalan[] =  (int)$item;
        }

        $dataRuanganJalan = DB::table('ruangan_m as ru')
            ->select('ru.id', 'ru.namaruangan', 'ru.objectdepartemenfk', 'ru.noruangan', 'ru.kdinternal')
            ->where('ru.statusenabled', true)
            ->where('ru.kdprofile', $kdProfile)
            ->wherein('ru.objectdepartemenfk', $kdDepartemenRawatJalan)
            ->whereNotIn('ru.id', [235,280])
            ->when(isset($request['search']), function($q) use($request) {
                $q->where('ru.namaruangan', 'ilike', '%'.strtoupper($request['search']).'%');
            })
            ->orderBy('ru.namaruangan')
            ->get();

        return $this->respond($dataRuanganJalan);
    }
    public function getDokterByRuang(Request $request)
    {


        if (isset($request['tgl']) && $request['tgl'] != "" && $request['tgl'] != "undefined") {
            $cek = DB::select(DB::raw("select * from slottinglibur_m where tgllibur = '".$request['tgl']."'"));

            if (count($cek) != 0) {
                $result = array(
                    'list' => $cek,
                );
                return $this->respond($result, 201, 'Pelayanan tutup pada tanggal tersebut');
            }
        }

        if (!isset($request['id_ruangan']) || $request['id_ruangan'] == '') {
            return $this->respond([], 201, 'id_ruangan harus di isi');
        }
        if (!isset($request['tgl']) || $request['tgl'] == '') {

            return $this->respond([], 201, 'tgl harus di isi');
        }

        // $data10 = DB::table('pegawai_m')
        // ->select('id','namalengkap')
        // ->where('kdprofile',  $this->kdProfile)
        // ->where('statusenabled', true)
        // ->where('objectjenispegawaifk',  $this->settingFix('idJenisPegawaiDokter'))
        // ->orderBy('namalengkap')
        // ->get();

        $kdProfile = $this->kdProfile;
        $dokter = DB::table('jadwaldokter_m as slot')
            ->join('ruangan_m as ru', 'slot.objectruanganfk', '=', 'ru.id')
            ->join('pegawai_m as pg', 'slot.objectpegawaifk', '=', 'pg.id')
            ->select('pg.namalengkap as dokter', 'slot.hari', 'pg.id as idok', DB::raw("to_char(slot.tanggal,'yyyy-MM-dd') as tanggal"))
            ->where('ru.statusenabled', true)
            ->where('ru.statusenabled', true)
            ->where('ru.id', $request['id_ruangan'])
            ->whereDate('slot.tanggal', $request['tgl'])
            ->where('slot.kdprofile', $kdProfile)
            ->where('slot.statusenabled', true)
            ->distinct()
            ->get();
            
        $data10 = [];
        for ($i = count($dokter) - 1; $i >= 0; $i--) {
            $data10[] = array(
                'id' => $dokter[$i]->idok,
                'namalengkap' => $dokter[$i]->dokter,
                'jadwal' =>  strtoupper($this->hari_ini($dokter[$i]->tanggal)),
            );
        }
        if (count($data10) == 0) {
            $result = array(
                'list' => $data10,
            );
            return $this->respond($result, 201, 'Jadwal Dokter belum tersedia');
        }
        $result = array(
            'list' => $data10,
        );
        return $this->respond($result);
    }
    public function getSlottingByRuanganDokter(Request $request)
    {

        try {
            $kdProfile = $this->kdProfile;
            $dataReservasi = DB::table('antrianpasienregistrasi_t as apr')
                ->select('apr.norec', 'apr.tanggalreservasi', 'apr.objectpegawaifk')
                ->whereRaw(" to_char(apr.tanggalreservasi,'yyyy-MM-dd') = '$request[tgl]'")
                ->where('apr.objectruanganfk', $request['id_ruangan'])
                // ->where('apr.objectpegawaifk', $request['id_dokter'])
                ->where('apr.noreservasi', '!=', '-')
                ->where('apr.kdprofile', $kdProfile)
                ->whereNotNull('apr.noreservasi')
                ->where('apr.statusenabled', true)
                ->get();


                //gabung sama kiosk & jkn
            //  $ruangan = DB::table('slottingkiosk_m as slot')
            //  ->join('ruangan_m as ru', 'slot.objectruanganfk', '=', 'ru.id')
            //  ->select(
            //         'ru.id',
            //         'ru.namaruangan',
            //         'ru.objectdepartemenfk',
            //         'slot.jambuka',
            //         'slot.jamtutup',
            //         'slot.quota',
            //         'slot.hari',
            //         DB::raw("( EXTRACT ( EPOCH FROM slot.jamtutup ) - EXTRACT ( EPOCH FROM slot.jambuka ) ) / 3600 AS totaljam ")
            //     )
            //     ->where('ru.statusenabled', true)
            //     ->where('ru.id', $request['id_ruangan'])
            //     ->where('slot.tanggal', $request['tgl'])
            //     ->where('slot.statusenabled', true)
            //     ->where('slot.kdprofile', $kdProfile)
            //     ->get();

                $dokter = DB::table('pegawai_m')
                ->select('id','namalengkap','namalengkap as dokter')
                ->where('kdprofile',  $this->kdProfile)
                ->where('statusenabled', true)
                ->where('id',$request['id_dokter'])
                ->first();

                if (empty($dokter)) {
                    return $this->respond([], 201, ' Dokter tidak ditemukan');
                }

            $ruangan = DB::table('jadwaldokter_m as slot')
                ->join('ruangan_m as ru', 'slot.objectruanganfk', '=', 'ru.id')
                ->join('pegawai_m as pg', 'slot.objectpegawaifk', '=', 'pg.id')
                ->select(
                    'ru.id',
                    'ru.namaruangan',
                    'ru.objectdepartemenfk',
                    'slot.jammulai as jambuka',
                    'slot.jamakhir as jamtutup',
                    'slot.quota',
                    'pg.namalengkap as dokter',
                    'slot.hari',
                    'slot.objectpegawaifk',
                    'pg.namalengkap',
                    DB::raw("( EXTRACT ( EPOCH FROM slot.jamakhir ) - EXTRACT ( EPOCH FROM slot.jammulai ) ) / 3600 AS totaljam ")
                )
                ->where('ru.statusenabled', true)
                ->where('ru.statusenabled', true)
                ->where('ru.id', $request['id_ruangan'])
                ->where('slot.objectpegawaifk', $request['id_dokter'])
                ->where('slot.statusenabled', true)
                ->where('slot.kdprofile', $kdProfile)
                ->get();

            if (count($ruangan) == 0) {
                $result = array(
                    'listjam' => [],
                );
                return $this->respond($result, 201, 'Slot tidak ada/belum dijadwalkan');
            }
            $data10 = null;
            $hari = $this->hari_ini($request['tgl']);

            $quota = 0;
            foreach ($ruangan as $ruu) {
                $quota = $quota+ $ruu->quota;
                $now = explode(', ', $ruu->hari);
                for ($i2 = count($now) - 1; $i2 >= 0; $i2--) {
                    if (strtoupper($now[$i2]) == strtoupper($hari)) {
                        $data10 = $ruu;
                        break;
                    }
                }
            }

            if (empty($data10)) {
                $result = array(

                    'listjam' => [],

                );
                return $this->respond($result, 201, 'Jadwal Dokter tidak ditemukan');
            }


            $begin = new Carbon($data10->jambuka);
            $jamBuka = $begin->format('H:i');
            $end = new Carbon($data10->jamtutup);
            $jamTutup = $end->format('H:i');
            $quota = (float)$data10->quota;
            if ($quota == 0) {
                $result = array(

                    'listjam' => [],

                );
                return $this->respond($result, 201, 'Kuota belum tersedia');
            }
            $waktuPerorang = ((float)$data10->totaljam / (float)$quota) * 60;

            $i = 0;
            $reservasi = [];
            foreach ($dataReservasi as $items) {
                $jamUse =  new Carbon($items->tanggalreservasi);
                $reservasi[] = array(
                    'jamreservasi' => $jamUse->format('H:i')
                );
            }

            $intervals = [];
            $begin = new \DateTime($jamBuka);
            $end = new \DateTime($jamTutup);
            $interval = \DateInterval::createFromDateString(floor($waktuPerorang) . ' minutes');

            $period = new \DatePeriod($begin, $interval, $end);

            foreach ($period as $dt) {
                if($request['tgl']. ' '.$dt->format("H:i") > date('Y-m-d H:i')){
                    $intervals[] = array(
                        'jam' =>  $dt->format("H:i"),
                    );
                }
            }

            if (count($intervals) == 0) {
                $result = array(

                    'listjam' => [],

                );
                return $this->respond($result, 201, 'Slotting tidak ditemukan');
            }

            if (count($reservasi) > 0) {
                for ($j = count($reservasi) - 1; $j >= 0; $j--) {
                    for ($k = count($intervals) - 1; $k >= 0; $k--) {
                        if ($intervals[$k]['jam'] == $reservasi[$j]['jamreservasi']) {
                            // $intervals[$k]['terpakai'] = true;
                            array_splice($intervals, $k, 1);
                        }
                    }
                }
            }
            $slot  = array(
                'id_ruangan' => $data10->id,
                'namaruangan' => $data10->namaruangan,
                'id_dokter' => $dokter->id,
                'namalengkap' => $dokter->namalengkap,
                'hari' => $hari,
                'tgl' =>  $request['tgl'],
                'jambuka' => $jamBuka,
                'jamtutup' => $jamTutup,
                'totaljam' => $data10->totaljam,
                'quota' => (float)$quota,
                'interval' => $waktuPerorang,

            );
            $result = array(
                'slot' => $slot,
                'reservasi' => $reservasi,
                'listjam' => $intervals,

            );
        } catch (\Exception $e) {
            return $this->respond([], 201, $e->getMessage() . ' ' . $e->getLine());
        }

        return $this->respond($result);
    }
    public function saveReservasi(Request $request)
    {
        DB::beginTransaction();
        try {

            $kdProfile = $this->kdProfile;
            $tgl = $request['tglReservasiFix'];

            $dataReservasi = DB::table('antrianpasienregistrasi_t as apr')
                ->select('apr.norec', 'apr.tanggalreservasi')
                ->whereRaw("apr.tanggalreservasi = '$tgl'")
                ->where('apr.objectruanganfk', $request['poliKlinik']['id'])
                ->where('apr.noreservasi', '!=', '-')
                ->whereNotNull('apr.noreservasi')
                ->where('apr.statusenabled', true)
                ->where('apr.kdprofile', (int) $kdProfile);
            if (isset($request['dokter']) && $request['dokter'] != null && isset($request['dokter']['id'])) {
                $dataReservasi = $dataReservasi->where('apr.objectpegawaifk', $request['dokter']['id']);
            }
            $dataReservasi = $dataReservasi->get();

            if (count($dataReservasi) > 0) {
                return $this->respond([], 201, 'Mohon maaf dijam tersebut sudah ada yang reservasi, Coba di jadwal yang lain');
            }
            if (isset($request['dokter']) && $request['dokter'] != null && isset($request['dokter']['id'])) {
                $dokter = DB::table('jadwaldokter_m as slot')
                    ->select('slot.hari', 'slot.objectruanganfk', 'slot.objectpegawaifk')
                    ->where('slot.objectruanganfk', $request['poliKlinik']['id'])
                    ->where('slot.objectpegawaifk', $request['dokter']['id'])
                    ->where('slot.statusenabled', true)
                    ->get();

                //  $dokter = DB::table('slottingkiosk_m as slot')
                //     ->select('slot.hari', 'slot.objectruanganfk')
                //     ->where('slot.objectruanganfk', $request['poliKlinik']['id'])
                //     // ->where('slot.objectpegawaifk', $request['dokter']['id'])
                //     ->where('slot.statusenabled', true)
                //     ->get();
                $hari = $this->hari_ini($request['tglReservasiFix']);

                $data10 = [];
                for ($i = count($dokter) - 1; $i >= 0; $i--) {
                    $now = explode(', ', $dokter[$i]->hari);
                    for ($i2 = count($now) - 1; $i2 >= 0; $i2--) {
                        if (strtoupper($now[$i2]) == strtoupper($hari)) {
                            $data10[] = $dokter[$i];
                        }
                    }
                }
                if (count($data10) == 0) {
                    return $this->respond([], 201, 'Jadwal Dokter tidak tersedia di Poli ini');
                }
            }

            if ($request['isBaru'] == false) {
                $pasien  = Pasien::where('nocm', $request['noCm'])
                    ->where('statusenabled', true)->first();

                $tglCek = substr(str_replace('/','-',$tgl),0,10);
                $tglCek = date('Y-m-d',strtotime($tglCek));
                $cekPerpasien = DB::table('antrianpasienregistrasi_t as apr')
                    ->select('apr.norec', 'apr.tanggalreservasi')
                    ->whereRaw("to_char(apr.tanggalreservasi,'yyyy-MM-dd') = '$tglCek'")

                    ->where('apr.noreservasi', '!=', '-')
                    ->whereNotNull('apr.noreservasi')
                    ->where('apr.statusenabled', true)
                    ->where('apr.nocmfk', $pasien->id)
                    ->where('apr.kdprofile', (int) $kdProfile);
                // if (isset($request['dokter']) && $request['dokter'] != null && isset($request['dokter']['id'])) {
                //     $cekPerpasien = $cekPerpasien->where('apr.objectpegawaifk', $request['dokter']['id']);
                // }
                if ($request['tipePembayaran']['id'] != 2) {
                     $cekPerpasien = $cekPerpasien ->where('apr.objectruanganfk', $request['poliKlinik']['id']);
                }
                $cekPerpasien = $cekPerpasien->get();

                if (count($cekPerpasien) > 0) {
                    return $this->respond([], 201, 'Mohon maaf tidak bisa reservasi dihari yang sama');
                }

            }

            $newptp = new AntrianPasienRegistrasi();
            $nontrian = AntrianPasienRegistrasi::max('noantrian') + 1;
            $newptp->norec = $newptp->generateNewId();
            $newptp->kdprofile = (int) $kdProfile;
            $newptp->statusenabled = true;
            $newptp->objectruanganfk = $request['poliKlinik']['id'];
            $newptp->objectjeniskelaminfk = $request['jenisKelamin']['id'];
            $newptp->noreservasi = substr(Uuid::uuid4()->toString(), 0, 7);
            $newptp->tanggalreservasi = $request['tglReservasiFix'];
            $newptp->tgllahir = $request['tglLahir'];
            $newptp->objectkelompokpasienfk = $request['tipePembayaran']['id'];
            $newptp->objectpendidikanfk = 0;
            $newptp->namapasien =  $request['namaPasien'];
            $newptp->noidentitas =  $request['nik'];
            $newptp->tglinput = date('Y-m-d H:i:s');
            if ($request['tipePembayaran']['id'] == 2) {
                $newptp->nobpjs = $request['noKartuPeserta'];
                $newptp->norujukan = $request['noRujukan'];
            } else {
                $newptp->noasuransilain = $request['noKartuPeserta'];
            }
            $newptp->notelepon = $request['noTelpon'];
            if (isset($request['dokter']['id'])) {
                $newptp->objectpegawaifk =  $request['dokter']['id'];
            }
            if (isset($request['caraDaftar'])) {
                $newptp->caradaftar =  $request['caraDaftar'];
            }

            if ($request['isBaru'] == true) {
                $newptp->tipepasien = "BARU";
                $newptp->type = "BARU";
            } else {
                $newptp->tipepasien = "LAMA";
                $newptp->type = "LAMA";
            }

            if (isset($pasien) && !empty($pasien)) {
                $newptp->objectagamafk = $pasien->objectagamafk;
                $alamat = Alamat::where('nocmfk', $pasien->id)->first();
                if (!empty($alamat)) {
                    $newptp->alamatlengkap = $alamat->alamatlengkap;
                    $newptp->objectdesakelurahanfk = $alamat->objectdesakelurahanfk;
                    $newptp->negara = $alamat->objectnegarafk;
                }
                $newptp->objectgolongandarahfk =  $pasien->objectgolongandarahfk;
                $newptp->kebangsaan = $pasien->objectkebangsaanfk;
                $newptp->namaayah = $pasien->namaayah;
                $newptp->namaibu = $pasien->namaibu;
                $newptp->namasuamiistri = $pasien->namasuamiistri;

                $newptp->noaditional = $pasien->noaditional;
                //                $newptp->noantrian= 0;
                $newptp->noidentitas = $pasien->noidentitas;
                $newptp->nocmfk =  $pasien->id;
                $newptp->paspor =  $pasien->paspor;
                $newptp->objectpekerjaanfk =  $pasien->objectpekerjaanfk;
                $newptp->objectpendidikanfk = $pasien->objectpendidikanfk != null ? $pasien->objectpendidikanfk  : 0;
                $newptp->objectstatusperkawinanfk =  $pasien->objectstatusperkawinanfk;
                $newptp->tempatlahir = $pasien->tempatlahir;
            }
            $newptp->keterangan = "reservasi-online";
            $newptp->save();
            $newptp->namaruangan = Ruangan::where('id', $newptp->objectruanganfk)
                ->where('kdprofile', (int) $kdProfile)
                ->first()->namaruangan;

            if (isset($request['dokter']['id'])) {
                $cek = Pegawai::where('id', $request['dokter']['id'])
                    ->where('kdprofile', (int) $kdProfile)
                    ->first();
                $newptp->dokter = !empty($cek) ? $cek->namalengkap : '-';
            }
            $transStatus = true;
        } catch (\Exception $e) {
            $transStatus = false;
        }

        if ($transStatus == 'true') {
            $transMessage = "Sukses";
            DB::commit();
            $result = array(
                "status" => 200,
                "result" => array(
                    "data" => $newptp,
                    "as" => '@epic',
                ),
            );
        } else {
            $transMessage = "Simpan Gagal";
            DB::rollBack();
            $result = array(
                "status" => 201,
                "result"  => null
            );
        }
        return $this->respond($result['result'], $result['status'], $transMessage);
    }
    public function getHistoryReservasi(Request $request)
    {
        $kdProfile = $this->kdProfile;
        $noreservasi = $request['noReservasi'];

        // $data = DB::connection('sqlsrv_rsbm')
        //         ->table('SIMtrReservasi')
        //         ->whereRaw("cast(UntukTanggal as date) = '".date('Y-m-d')."'")
        //         //->whereRaw("cast(UntukTanggal as date) = '".date('2024-10-07')."'")
        //         ->whereRaw("replace(NRM, '.', '') = '".$noreservasi."'")
        //         ->orWhereRaw("NoReservasi = '".$noreservasi."'")
        //         ->get();

        // $dokter = Pegawai::where('kodeexternal', '=', $data[0]->UntukDokterID)->get();
        // $poli = Ruangan::where('kodeexternal', '=', $data[0]->UntukSectionID)->get();
        // $kelompokpasien = Kelompokpasien::where('kodeexternal', '=', $data[0]->JenisKerjasamaID)->get();
        // $agama = Agama::where('kodeexternal', '=', $data[0]->Agama)->get();
        // $jeniskelamin = JenisKelamin::where('kodeexternal', '=', $data[0]->JenisKelamin)->get();

        // if(count($data) != 0){
        //     if($data[0]->NationalityID == null || $data[0]->NationalityID == 'INA'){
        //         $kebangsaan = 1;
        //     } else{
        //         $kebangsaan = 3;
        //     }
        // } else{
        //     $kebangsaan = 1;
        // }
        
        // if(count($data) != 0){
        //     if($data[0]->NRM != null){
        //         $pasien =  Pasien::where('nocm', '=', preg_replace('/\D/', '', $data[0]->NRM))->get();
        //     } else{
        //         $pasien = []; 
        //     }
        // } else{
        //     $pasien = [];
        // }
        

        $data = DB::table('antrianpasienregistrasi_t as apr')
            ->leftJoin('pasien_m as pm', 'pm.id', '=', 'apr.nocmfk')
            ->leftJoin('alamat_m as alm', 'alm.nocmfk', '=', 'pm.id')
            ->leftJoin('jeniskelamin_m as jk', 'jk.id', '=', 'pm.objectjeniskelaminfk')
            ->leftJoin('jeniskelamin_m as jks', 'jks.id', '=', 'apr.objectjeniskelaminfk')
            ->leftJoin('pekerjaan_m as pk', 'pk.id', '=', 'pm.objectpekerjaanfk')
            ->leftJoin('pendidikan_m as pdd', 'pdd.id', '=', 'pm.objectpendidikanfk')
            ->leftJoin('ruangan_m as ru', 'ru.id', '=', 'apr.objectruanganfk')
            ->leftJoin('pegawai_m as pg', 'pg.id', '=', 'apr.objectpegawaifk')
            ->join('kelompokpasien_m as kps', 'kps.id', '=', 'apr.objectkelompokpasienfk')
            ->select(
                'apr.norec',
                'pm.nocm',
                'pm.nobpjs',
                'apr.noreservasi',
                'apr.tanggalreservasi',
                'apr.objectruanganfk',
                'apr.objectpegawaifk',
                'apr.tipepasien',
                'ru.namaruangan',
                'apr.isconfirm',
                'apr.noantrian',
                'apr.noantrianpoli',
                'pg.namalengkap as dokter',
                'pm.id as nocmfk',
                'pk.pekerjaan',
                'pm.noasuransilain',
                'pdd.pendidikan',
                'apr.type',
                'kps.kelompokpasien',
                'apr.objectkelompokpasienfk',
                'ru.objectdepartemenfk',
                'ru.prefixnoantrian',
                'apr.norujukan',
                'apr.nosuratkontrol',
                'ru.id as idruangan',
                'pg.id as iddokter',
                'pg.kddokterbpjs',
                'apr.jenis',
                DB::raw("case when apr.isconfirm=true then 'Confirm' else 'Reservasi' end as status,
                case when pm.tempatlahir is null then apr.tempatlahir else pm.tempatlahir end as tempatlahir,
                case when pm.noidentitas is null then apr.noidentitas else pm.noidentitas end as noidentitas,
                case when pm.nobpjs is null then apr.nobpjs else pm.nobpjs end as nobpjs,
                case when pm.namapasien is null then apr.namapasien else pm.namapasien end as namapasien,
                case when pm.tempatlahir is null then apr.tempatlahir else pm.tempatlahir end as tempatlahir,
                case when pm.objectjeniskelaminfk is null then apr.objectjeniskelaminfk else pm.objectjeniskelaminfk end as objectjeniskelaminfk,
                case when pm.nohp is null then apr.notelepon else pm.nohp end as notelepon,
                case when pm.email is null then apr.email else pm.email end as email,
                case when alm.alamatlengkap is null then apr.alamatlengkap else alm.alamatlengkap end as alamatlengkap,
                case when pm.objectkebangsaanfk is null then apr.objectkebangsaanfk else pm.objectkebangsaanfk end as objectkebangsaanfk,
                case when pm.objectagamafk is null then apr.objectagamafk else pm.objectagamafk end as objectagamafk,
                case when jk.jeniskelamin is null then jks.jeniskelamin else jk.jeniskelamin end as jeniskelamin,
                case when pm.tgllahir is null then to_char(apr.tgllahir,'YYYY-MM-DD') else to_char(pm.tgllahir,'YYYY-MM-DD') end as tgllahir, 
                apr.loketkiosk")
            )
            ->whereNotNull('apr.noreservasi')
            ->whereNull('apr.noantrian')
            ->where('apr.kdprofile',  $kdProfile)
            ->where('apr.statusenabled', true)
            ->whereDate('apr.tanggalreservasi', \Carbon\Carbon::now()->format('Y-m-d'));

        if (isset($request['nocmNama']) && $request['nocmNama'] != "" && $request['nocmNama'] != "undefined" && $request['nocmNama'] != "null") {
            $data = $data->where('apr.noreservasi', $request['nocmNama'])->orWhere('pm.nocm','=',$request['nocmNama'])->orWhere('pm.noidentitas','=',$request['nocmNama'])->orWhere('pm.nobpjs','=',$request['nocmNama'])->orWhere('pm.namapasien','=',$request['nocmNama'])->orWhere('apr.namapasien','=',$request['nocmNama']);
        }
        if (isset($request['noReservasi']) && $request['noReservasi'] != "" && $request['noReservasi'] != "undefined" && $request['noReservasi'] != "null") {
            $data = $data->whereRaw("(apr.noreservasi = '$noreservasi' 
                OR pm.nocm = '$noreservasi'
                OR pm.namapasien = '$noreservasi'
                OR pm.noidentitas = '$noreservasi'
                OR pm.nobpjs = '$noreservasi'
                OR apr.namapasien = '$noreservasi')");
        }
        if (isset($request['tgllahir']) && $request['tgllahir'] != "" && $request['tgllahir'] != "undefined" && $request['tgllahir'] != "null" && $request['tgllahir'] != "Invalid date") {
            $data = $data->whereDate('pm.tgllahir', $request['tgllahir']);
        }
        $data = $data->orderBy('apr.tanggalreservasi', 'desc')->orderBy('apr.noantrianpoli', 'asc')->get();
        // return $data;
        $result = array(
            'total' => count($data),
            'data' => $data,
            'as' => '@epic',
        );
        return $this->respond($result);
    }
    public function getHistoryEReservasi(Request $request)
    {
        $kdProfile = $this->kdProfile;
        $noreservasi = $request['noReservasi'];
        $nocmnama = $request['nocmNama'];
        $tgllahir = $request['tgllahir'];

        // $data = DB::connection('sqlsrv_rsbm')
        //         ->table('SIMtrReservasi')
        //         ->whereRaw("cast(UntukTanggal as date) = '".date('Y-m-d')."'")
        //         //->whereRaw("cast(UntukTanggal as date) = '".date('2024-10-07')."'")
        //         ->whereRaw("replace(NRM, '.', '') = '".$noreservasi."'")
        //         ->orWhereRaw("NoReservasi = '".$noreservasi."'")
        //         ->get();

        // $dokter = Pegawai::where('kodeexternal', '=', $data[0]->UntukDokterID)->get();
        // $poli = Ruangan::where('kodeexternal', '=', $data[0]->UntukSectionID)->get();
        // $kelompokpasien = Kelompokpasien::where('kodeexternal', '=', $data[0]->JenisKerjasamaID)->get();
        // $agama = Agama::where('kodeexternal', '=', $data[0]->Agama)->get();
        // $jeniskelamin = JenisKelamin::where('kodeexternal', '=', $data[0]->JenisKelamin)->get();

        // if(count($data) != 0){
        //     if($data[0]->NationalityID == null || $data[0]->NationalityID == 'INA'){
        //         $kebangsaan = 1;
        //     } else{
        //         $kebangsaan = 3;
        //     }
        // } else{
        //     $kebangsaan = 1;
        // }
        
        // if(count($data) != 0){
        //     if($data[0]->NRM != null){
        //         $pasien =  Pasien::where('nocm', '=', preg_replace('/\D/', '', $data[0]->NRM))->get();
        //     } else{
        //         $pasien = []; 
        //     }
        // } else{
        //     $pasien = [];
        // }
        

        $data = DB::table('antrianpasienregistrasi_t as apr')
            ->leftJoin('pasien_m as pm', 'pm.id', '=', 'apr.nocmfk')
            ->leftJoin('alamat_m as alm', 'alm.nocmfk', '=', 'pm.id')
            ->leftJoin('jeniskelamin_m as jk', 'jk.id', '=', 'pm.objectjeniskelaminfk')
            ->leftJoin('jeniskelamin_m as jks', 'jks.id', '=', 'apr.objectjeniskelaminfk')
            ->leftJoin('pekerjaan_m as pk', 'pk.id', '=', 'pm.objectpekerjaanfk')
            ->leftJoin('pendidikan_m as pdd', 'pdd.id', '=', 'pm.objectpendidikanfk')
            ->leftJoin('ruangan_m as ru', 'ru.id', '=', 'apr.objectruanganfk')
            ->leftJoin('pegawai_m as pg', 'pg.id', '=', 'apr.objectpegawaifk')
            ->leftJoin('jadwaldokter_m as jd', function ($join) {
                $join->on('jd.objectruanganfk', '=', 'apr.objectruanganfk')
                ->whereRaw("to_char(apr.tanggalreservasi,'YYYY-MM-DD') = to_char(jd.tanggal,'YYYY-MM-DD')");
            })
            ->join('kelompokpasien_m as kps', 'kps.id', '=', 'apr.objectkelompokpasienfk')
            ->select(
                'apr.norec',
                'pm.nocm',
                'pm.nobpjs',
                'apr.noreservasi',
                'apr.tanggalreservasi',
                DB::raw("jd.jammulai || ' - ' || jd.jamakhir as jamreservasi"),
                'apr.objectruanganfk',
                'apr.objectpegawaifk',
                'ru.namaruangan',
                'apr.isconfirm',
                'apr.noantrian',
                'apr.noantrianpoli',
                'pg.namalengkap as dokter',
                'pm.id as nocmfk',
                'pk.pekerjaan',
                'pm.noasuransilain',
                'pdd.pendidikan',
                'apr.type',
                'kps.kelompokpasien',
                'apr.objectkelompokpasienfk',
                'ru.objectdepartemenfk',
                'ru.prefixnoantrian',
                'apr.norujukan',
                'apr.nosuratkontrol',
                'ru.id as idruangan',
                'pg.id as iddokter',
                'pg.kddokterbpjs',
                'apr.jenis',
                DB::raw("case when apr.isconfirm=true then 'Confirm' else 'Reservasi' end as status,
                case when pm.tempatlahir is null then apr.tempatlahir else pm.tempatlahir end as tempatlahir,
                case when pm.noidentitas is null then apr.noidentitas else pm.noidentitas end as noidentitas,
                case when pm.nobpjs is null then apr.nobpjs else pm.nobpjs end as nobpjs,
                case when pm.namapasien is null then apr.namapasien else pm.namapasien end as namapasien,
                case when pm.tempatlahir is null then apr.tempatlahir else pm.tempatlahir end as tempatlahir,
                case when pm.objectjeniskelaminfk is null then apr.objectjeniskelaminfk else pm.objectjeniskelaminfk end as objectjeniskelaminfk,
                case when pm.nohp is null then apr.notelepon else pm.nohp end as notelepon,
                case when pm.email is null then apr.email else pm.email end as email,
                case when alm.alamatlengkap is null then apr.alamatlengkap else alm.alamatlengkap end as alamatlengkap,
                case when pm.objectkebangsaanfk is null then apr.objectkebangsaanfk else pm.objectkebangsaanfk end as objectkebangsaanfk,
                case when pm.objectagamafk is null then apr.objectagamafk else pm.objectagamafk end as objectagamafk,
                case when jk.jeniskelamin is null then jks.jeniskelamin else jk.jeniskelamin end as jeniskelamin,
                case when pm.tgllahir is null then to_char(apr.tgllahir,'YYYY-MM-DD') else to_char(pm.tgllahir,'YYYY-MM-DD') end as tgllahir, 
                apr.loketkiosk")
            )
            ->whereNotNull('apr.noreservasi')
            ->whereNull('apr.noantrian')
            ->where('apr.kdprofile',  $kdProfile)
            ->where('apr.statusenabled', true);
        
        if (isset($request['tgllahir']) && $request['tgllahir'] != "" && $request['tgllahir'] != "undefined" && $request['tgllahir'] != "null" && $request['tgllahir'] != "Invalid date") {
            $data = $data->whereRaw("(pm.tgllahir = '$tgllahir' or apr.tgllahir = '$tgllahir')");
        }
        if (isset($request['nocmNama']) && $request['nocmNama'] != "" && $request['nocmNama'] != "undefined" && $request['nocmNama'] != "null") {
            $data = $data->whereRaw("(apr.noreservasi = '$nocmnama' 
            OR pm.nocm = '$nocmnama'
            OR pm.noidentitas = '$nocmnama'
            OR pm.nobpjs = '$nocmnama'
            OR pm.namapasien = '$nocmnama'
            OR apr.namapasien = '$nocmnama')");
        }
        if (isset($request['noReservasi']) && $requesst['noReservasi'] != "" && $request['noReservasi'] != "undefined" && $request['noReservasi'] != "null") {
            $data = $data->whereRaw("(apr.noreservasi = '$noreservasi' 
                OR pm.nocm = '$noreservasi'
                OR pm.noidentitas = '$nocmnama'
                OR pm.nobpjs = '$nocmnama'
                OR pm.namapasien = '$noreservasi'
                OR apr.namapasien = '$noreservasi')");
        }
        $data = $data->orderBy('apr.tanggalreservasi', 'desc')->orderBy('apr.noantrianpoli', 'asc')->get();

        $result = array(
            'total' => count($data),
            'data' => $data,
            'as' => '@epic',
        );
        return $this->respond($result);
    }
    public function deleteReservasi(Request $request)
    {
        DB::beginTransaction();
        try {
            AntrianPasienRegistrasi::where('norec', $request['norec'])->update([
                'statusenabled' => false,
            ]);
            $transStatus = 'true';

            $transMessage = "Hapus Reservasi Sukses";
        } catch (\Exception $e) {
            $transStatus = 'false';
            $transMessage = "Hapus Reservasi Gagal";
        }

        if ($transStatus == 'true') {
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
                "status" => 200,
                "result"  => null
            );
        }
        return $this->respond($result['result'], $result['status'], $transMessage);
    }

    public function getPasienByNoka($nokartu)
    {
        $data = DB::table('pasien_m as ps')
            ->leftJOIN('alamat_m as alm', 'alm.nocmfk', '=', 'ps.id')
            ->leftjoin('pendidikan_m as pdd', 'ps.objectpendidikanfk', '=', 'pdd.id')
            ->leftjoin('pekerjaan_m as pk', 'ps.objectpekerjaanfk', '=', 'pk.id')
            ->leftjoin('jeniskelamin_m as jk', 'jk.id', '=', 'ps.objectjeniskelaminfk')
            ->select(
                'ps.nocm',
                'ps.id as nocmfk',
                'ps.namapasien',
                'ps.objectjeniskelaminfk',
                'jk.jeniskelamin',
                'alm.alamatlengkap',
                'pdd.pendidikan',
                'pk.pekerjaan',
                'ps.noidentitas',
                'ps.notelepon',
                'ps.tempatlahir',
                'ps.nobpjs',
                DB::raw(" to_char ( ps.tgllahir,'yyyy-MM-dd') as tgllahir")
            )
            ->where('ps.statusenabled', true)
            ->where('ps.nobpjs', '=', $nokartu)
            ->get();

        $result = array(
            'data' => $data,
            'as' => '@epic',
        );
        return $this->respond($result);
    }
    public function getDaftarRiwayatRegistrasi(Request $request)
    {
        $kdProfile = $this->kdProfile;

        $data = DB::table('pasien_m as ps')
            ->join('pasiendaftar_t as pd', 'pd.nocmfk', '=', 'ps.id')
            ->join('ruangan_m as ru', 'ru.id', '=', 'pd.objectruanganlastfk')
            ->join('kelompokpasien_m as kp', 'kp.id', '=', 'pd.objectkelompokpasienlastfk')
            ->join('kelas_m as kl', 'kl.id', '=', 'pd.objectkelasfk')
            ->leftjoin('pelayananpasien_t as pp', 'pp.noregistrasi', '=', 'pd.noregistrasi')
            ->leftjoin('pegawai_m as pg', 'pg.id', '=', 'pd.objectpegawaifk')
            ->select(DB::raw("pd.norec as norec_pd,pd.tglregistrasi,ps.nocm,pd.noregistrasi,ps.namapasien,ru.namaruangan,
			                  pg.namalengkap as namadokter,pd.tglpulang,ps.tgllahir,kp.kelompokpasien,kl.namakelas,
                              sum((
                                (pp.hargasatuan  - case when pp.hargadiscount is null then 0 else pp.hargadiscount end)
                                 * pp.jumlah)
                            + (case when pp.jasa is not null then pp.jasa else 0 end))
                             as totalbilling"))
            ->where('pd.statusenabled',  true)
            ->where('pd.kdprofile',   $kdProfile);


        if (isset($request['norm']) && $request['norm'] != "" && $request['norm'] != "undefined") {
            $data = $data->where('ps.nocm', '=',  $request['norm']);
        };
        if (isset($request['namaPasien']) && $request['namaPasien'] != "" && $request['namaPasien'] != "undefined") {
            $data = $data->where('ps.namapasien', 'ilike', '%' . $request['namaPasien'] . '%');
        };
        if (isset($request['noReg']) && $request['noReg'] != "" && $request['noReg'] != "undefined") {
            $data = $data->where('pd.noregistrasi', '=', $request['noReg']);
        };
        if (isset($request['idRuangan']) && $request['idRuangan'] != "" && $request['idRuangan'] != "undefined") {
            $data = $data->where('pd.objectruanganlastfk', '=', $request['idRuangan']);
        };

        $data = $data->where('ps.statusenabled', true);
        $data = $data->orderBy('pd.tglregistrasi', 'desc');
        $data = $data->groupBy('pd.norec','pd.tglregistrasi','ps.nocm','pd.noregistrasi','ps.namapasien','ru.namaruangan',
        'pg.namalengkap','pd.tglpulang','ps.tgllahir','kp.kelompokpasien','kl.namakelas');
        $data = $data->limit(100);
        $data = $data->get();
        foreach($data as $d){
            $d->totalbilling =  'Rp. ' . number_format((float)  $d->totalbilling , 2, ',', '.');
        }
        $result = array(
            'data' => $data,
            'as' => '@epic',
        );
        return $this->respond($result);
    }
    public function billingPasien(Request $r)
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
            ->leftJOIN('strukpelayanan_t as sp', 'sp.norec', '=', 'pp.strukfk')
            ->leftJOIN('strukresep_t as sr', 'sr.norec', '=', 'pp.strukresepfk')
            ->leftJOIN('pegawai_m as pg', 'pg.id', '=', 'sr.penulisresepfk')
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
                'pp.strukfk',
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
        $data = $data->orderByDesc('pp.tglpelayanan');
        $data = $data->get();

        $pelayananpetugas = DB::table('pelayananpasienpetugas_t as ptu')
            ->leftjoin('pegawai_m as pg', 'pg.id', '=', 'ptu.objectpegawaifk')
            ->select('ptu.pelayananpasien', 'pg.namalengkap')
            ->where('ptu.kdprofile', $kdProfile)
            ->where('ptu.objectjenispetugaspefk', $sDokterPemeriksa)
            ->where('ptu.noregistrasi', $pd->noregistrasi)
            ->get();

        $result['total'] = 0;
        $result['deposit'] = 0;
        $result['diskon'] = 0;
        $result['dibayar'] = 0;
        $result['sisa'] = 0;

        $sama = false;
        $group  = [];
        foreach ($data as $item) {
            $item->dokterpemeriksa = $item->strukresepfk != null ? $item->penulisresep : '-';
            $item->checked = false;
            $result['total']  = $result['total']  + (float) $item->total;
            $result['diskon']  = $result['diskon']  + (float) $item->hargadiscount;
            foreach ($pelayananpetugas as $itemd) {
                if ($itemd->pelayananpasien == $item->norec) {
                    $item->dokterpemeriksa = $itemd->namalengkap;
                }
            }
            $sama = false;
            $i = 0;
            foreach ($group as $itemg) {
                if ($item->namaruangan == $group[$i]['namaruangan']) {
                    $sama = true;
                }
                $i = $i + 1;
            }
            if ($sama == false) {
                $group[] = array(
                    'namaruangan' => $item->namaruangan,
                    'list_pelayanan' => []
                );
            }
        }
        foreach ($group as $k => $d) {
            foreach ($data as $d2) {
                if ($d['namaruangan'] == $d2->namaruangan) {
                    $group[$k]['list_pelayanan'][] = array(
                        'tglpelayanan' => $d2->tglpelayanan,
                        'namapelayanan' => $d2->namaproduk,
                        'jumlah' => $d2->jumlah,
                        'total' => 'Rp. ' . number_format((float)$d2->total, 2, ',', '.'),

                    );
                }
            }
        }


        $result['klaim_bpjs']  = StrukPelayanan::totalKlaim($pd->noregistrasi);
        $result['deposit'] = StrukBuktiPenerimaan::deposit($pd->noregistrasi);
        $result['dibayar'] = StrukBuktiPenerimaan::totalBayar($pd->noregistrasi);
        $result['pengembalian'] = StrukBuktiPengeluaran::totalPengembalian($pd->noregistrasi);
        $result['sisa'] =   $result['total'] - $result['dibayar'] - $result['deposit'] - $result['klaim_bpjs'] + $result['pengembalian']; // -  $result['diskon'];'
        $result['terbilang'] = $this->terbilang($result['total']) . ' RUPIAH';
        $result['diskon']  = 'Rp. ' . number_format((float) $result['diskon'], 2, ',', '.');
        $result['total']  = 'Rp. ' . number_format((float) $result['total'], 2, ',', '.');
        $result['klaim_bpjs']  = 'Rp. ' . number_format($result['klaim_bpjs'], 2, ',', '.');
        $result['deposit'] = 'Rp. ' . number_format($result['deposit'], 2, ',', '.');
        $result['dibayar'] = 'Rp. ' . number_format($result['dibayar'], 2, ',', '.');
        $result['pengembalian'] = 'Rp. ' . number_format($result['pengembalian'], 2, ',', '.');


        $result['sisa'] = 'Rp. ' . number_format($result['sisa'], 2, ',', '.');
        $result['length'] = count($data);
        $result['detail_perruangan'] = $group; //collect($data)->groupBy('tglpelayanan_group')->sortByDesc('tglpelayanan_group');

        $result['as'] = '@epic';
        return $this->respond($result);
    }

    public function infoBed(Request $r)
    {
        $kdProfile =  $this->kdProfile;
        $data = collect(DB::select("
        SELECT
          x.namaruangan,
          SUM (x.isi) AS isi,
          SUM (x.kosong) AS kosong,
      count(x.tt_id) as total
      FROM
          (
              SELECT
                  CAST (tt.nomorbed AS INT) AS nomor,
                  tt. ID AS tt_id,
                  tt.nomorbed AS namabed,
                  kmr. ID AS kmr_id,
                  kmr.namakamar,
                  ru. ID AS id_ruangan,
                  ru.namaruangan,
                  kls.namakelas,
                  sb.statusbed,
                  CASE 	WHEN sb. ID = 1 THEN	1	ELSE 0 END AS isi,
              CASE WHEN sb. ID = 2 THEN	1	ELSE 0	END AS kosong
          FROM
              tempattidur_m AS tt
          INNER JOIN statusbed_m AS sb ON sb. ID = tt.objectstatusbedfk
          INNER JOIN kamar_m AS kmr ON kmr. ID = tt.objectkamarfk
          INNER JOIN kelas_m AS kls ON kls. ID = kmr.objectkelasfk
          INNER JOIN ruangan_m AS ru ON ru. ID = kmr.objectruanganfk
          WHERE
              tt.kdprofile = $kdProfile
          AND tt.statusenabled = TRUE
          AND kmr.statusenabled = TRUE
          ) AS x
      GROUP BY
          x.namaruangan

      "));
        $totalKamar = $data->count();
        $totalBed = 0;
        $totalIsi = 0;
        $totalKosong = 0;
        foreach ($data as $item) {
            $totalBed =    $totalBed + (float) $item->total;
            $totalIsi =    $totalIsi + (float) $item->isi;
            $totalKosong =    $totalKosong + (float) $item->kosong;
        }

        $tt = collect(DB::select("SELECT
      ru.id AS idruangan,
      ru.namaruangan,
      km.id AS idkamar,
      km.namakamar,
      tt.id AS idtempattidur,
      tt.reportdisplay,
      tt.nomorbed,
      sb.id AS idstatusbed,
      sb.statusbed,
      kl.id AS idkelas,
      kl.namakelas
  FROM
      tempattidur_m AS tt
  LEFT JOIN kamar_m AS km ON km.id = tt.objectkamarfk
  LEFT JOIN ruangan_m AS ru ON ru.id = km.objectruanganfk
  LEFT JOIN statusbed_m AS sb ON sb.id = tt.objectstatusbedfk
  LEFT JOIN kelas_m AS kl ON kl.id = km.objectkelasfk
  WHERE
      ru.objectdepartemenfk IN (16, 35)
  AND ru.statusenabled = true
  AND km.statusenabled = true
  AND tt.statusenabled = true
  AND tt.kdprofile = $kdProfile"));

        $data10 = [];
        $sama = false;
        $bed = 0;
        $isi = 0;
        $kosong = 0;
        foreach ($tt as $item) {
            $sama = false;
            $i = 0;
            foreach ($data10 as $hideung) {
                if ($item->namaruangan == $data10[$i]['namaruangan']) {
                    $sama = 1;
                    $jml = (float)$hideung['bed'] + 1;
                    $data10[$i]['bed'] = $jml;
                    if ($item->idstatusbed == 1) {
                        $data10[$i]['isi'] = (float)$hideung['isi'] + 1;
                    }
                    if ($item->idstatusbed == 2) {
                        $data10[$i]['kosong'] = (float)$hideung['kosong'] + 1;
                    }
                }
                $i = $i + 1;
            }
            if ($sama == false) {
                if ($item->idstatusbed == 1) {
                    $isi = 1;
                    $kosong = 0;
                }
                if ($item->idstatusbed == 2) {
                    $isi = 0;
                    $kosong = 1;
                }

                $data10[] = array(
                    'idruangan' => $item->idruangan,
                    'namaruangan' => $item->namaruangan,
                    'idstatusbed' => $item->idstatusbed,
                    'bed' => 1,
                    'kosong' => $kosong,
                    'isi' => $isi,
                );
            }
        }

        $res['totalKamar'] = $totalKamar;
        $res['totalBed'] = $totalBed;
        $res['totalIsi'] = $totalIsi;
        $res['totalKosong'] = $totalKosong;

        $res['detail'] = $data10;
        $res['as'] = '@epic';
        return $this->respond($res);
    }

    public function  jadwalDokter(Request $r)
    {
        $now = $this->hari_ini(date('Y-m-d'));
        $dokter  = DB::table('jadwaldokter_m as jd')
            ->leftjoin('pegawai_m as pg', 'pg.id', '=', 'jd.objectpegawaifk')
            ->leftjoin('ruangan_m as ru', 'ru.id', '=', 'jd.objectruanganfk')
            ->select(
                'pg.namalengkap',
                'jd.jammulai',
                'jd.jamakhir',
                'ru.namaruangan',
                DB::raw("jd.hari"),
            )
            ->where('jd.kdprofile', $this->kdProfile)
            ->where('jd.statusenabled', true)
            ->where('ru.statusenabled', true)
            ->where('pg.statusenabled', true)
            ->whereIn('ru.objectdepartemenfk',  explode(',', $this->settingFix('kdDepartemenRawatJalanFix')))
            // ->where('jd.hari', 'ilike', '%'.$now .'%')
            ->where('pg.namalengkap', '<>','Dokter Umum');

        if (isset($r['ruanganid']) && $r['ruanganid'] != '') {
            $dokter = $dokter->where('ru.id', '=',  $r['ruanganid']);
        }
        if (isset($r['namadokter']) && $r['namadokter'] != '') {
            $dokter = $dokter->where('pg.namalengkap', 'ilike',  '%'.$r['namadokter'].'%');
        }
        if (isset($r['hari']) && $r['hari'] != '') {
            $dokter = $dokter->where('jd.hari', 'ilike',  '%'.$r['hari'].'%');
        }
        if (isset($r['limit']) && $r['limit'] != '') {
            $dokter = $dokter->limit( $r['limit']);
        }
        $dokter->orderBy('ru.namaruangan');

        $data =  $dokter->get();

        // foreach($dokter as $d){
        //     $d->hari = $now;
        // }
        $group = [];
        foreach ($data as $item) {

            $sama = false;
            $i = 0;
            foreach ($group as $itemg) {
                if ($item->namaruangan == $group[$i]['namaruangan']) {
                    $sama = true;
                }
                $i = $i + 1;
            }
            if ($sama == false) {
                $group[] = array(
                    'namaruangan' => $item->namaruangan,
                    'list_jadwal' => []
                );
            }
        }
        foreach ($group as $k => $d) {
            foreach ($data as $d2) {
                if ($d['namaruangan'] == $d2->namaruangan) {
                    $group[$k]['list_jadwal'][] = $d2;

                }
            }
        }
        $res['data'] = $group;
        $res['as'] = '@epic';
        return $this->respond($res);
    }
    public function getPasienByNoCmTglLahir($nocm,$tgllahir) {
		$data = DB::table('pasien_m as ps')
			->leftJOIN ('alamat_m as alm','alm.nocmfk','=','ps.id')
			->leftjoin ('pendidikan_m as pdd','ps.objectpendidikanfk','=','pdd.id')
			->leftjoin ('pekerjaan_m as pk','ps.objectpekerjaanfk','=','pk.id')
			->leftjoin ('jeniskelamin_m as jk','jk.id','=','ps.objectjeniskelaminfk')
			->select('ps.nocm','ps.id as nocmfk','ps.namapasien','ps.objectjeniskelaminfk','jk.jeniskelamin',
				'alm.alamatlengkap','pdd.pendidikan','pk.pekerjaan','ps.noidentitas','ps.notelepon','ps.tempatlahir',
                'ps.nobpjs',
				DB::raw(" to_char ( ps.tgllahir,'yyyy-MM-dd') as tgllahir"))
            ->where('ps.statusenabled',true);
			// ->where('ps.nocm', $nocm);

        if(isset($tgllahir) &&$tgllahir != "" && $tgllahir != "undefined" && $tgllahir != "null") {
            $data = $data ->whereDate("ps.tgllahir",  '=', $tgllahir);
        }
        if(isset($nocm) &&$nocm != "" && $nocm != "undefined" && $nocm != "null") {
            $data = $data->where('ps.nocm','=',$nocm)
             ->Orwhere('ps.noidentitas','=',$nocm)
             ->Orwhere('ps.nobpjs','=',$nocm);
        }
		$data = $data->get();

		$result = array(
			'data'=> $data,
			'message' => 'ramdanegie',
		);
		return $this->respond($result);
	}
    public function  getPasienByNoRegistrasi($noregistrasi,Request $request){
        $kdProfile = $this->kdProfile;
        $data = \DB::table('pasiendaftar_t as pd')
            ->leftjoin ('pasien_m as ps','ps.id','=','pd.nocmfk')
            ->leftjoin ('ruangan_m as ru','ru.id','=','pd.objectruanganlastfk')
            ->leftjoin ('kelas_m as kls','kls.id','=','pd.objectkelasfk')
            ->leftjoin ('kelompokpasien_m as kps','kps.id','=','pd.objectkelompokpasienlastfk')
            ->leftjoin ('rekanan_m as rk','rk.id','=','pd.objectrekananfk')
            ->leftjoin ('jeniskelamin_m as jk','jk.id','=','ps.objectjeniskelaminfk')
            ->leftjoin ('alamat_m as alm','alm.id','=','pd.nocmfk')
            ->leftjoin ('agama_m as agm','agm.id','=','ps.objectagamafk')
            ->select('pd.norec as norec_pd','pd.noregistrasi','pd.tglregistrasi','ps.nocm','ps.namapasien',
                'ps.tgllahir','ps.namakeluarga','ru.namaruangan','kls.namakelas','kps.kelompokpasien','rk.namarekanan','alm.alamatlengkap',
                'jk.jeniskelamin','agm.agama','ps.nohp','pd.statuspasien','pd.tglpulang')
            ->where('pd.noregistrasi', $noregistrasi)
            ->where('pd.kdprofile',   $kdProfile)
            ->first();

        $result = array(
            'data'=> $data,
            'message' => 'ramdanegie',
        );
        return $this->respond($result);
    }

    public function getTagihanEbilling($noregistasi,Request $request)
    {
         $kdProfile = $this->kdProfile;
        $pelayanan = \DB::table('pasiendaftar_t as pd')
            ->join('antrianpasiendiperiksa_t as apd', 'apd.noregistrasifk', '=', 'pd.norec')
            ->leftjoin('pelayananpasien_t as pp', 'pp.noregistrasifk', '=', 'apd.norec')
            ->leftjoin('produk_m as pr', 'pr.id', '=', 'pp.produkfk')
            ->join('kelas_m as kl', 'kl.id', '=', 'apd.objectkelasfk')
            ->leftjoin('ruangan_m as ru', 'ru.id', '=', 'apd.objectruanganfk')
            ->leftjoin('strukpelayanan_t as sp', 'sp.norec', '=', 'pp.strukfk')
            ->leftjoin('strukbuktipenerimaan_t as sbm', 'sp.nosbmlastfk', '=', 'sbm.norec')
            ->leftjoin('strukresep_t as sre', 'sre.norec', '=', 'pp.strukresepfk')
            ->select('pp.norec', 'pp.tglpelayanan', 'pp.rke', 'pr.id as prid', 'pr.namaproduk', 'pp.jumlah', 'kl.id as klid', 'kl.namakelas',
                'ru.id as ruid', 'ru.namaruangan', 'pp.produkfk', 'pp.hargajual', 'pp.hargadiscount', 'sp.nostruk', 'sp.tglstruk', 'apd.norec as norec_apd',
                'sbm.nosbm', 'sp.norec as norec_sp', 'pp.jasa', 'pd.nocmfk',
                'pd.nostruklastfk','pd.noregistrasi',
                'pd.tglregistrasi', 'pd.norec as norec_pd', 'pd.tglpulang',
                'pd.objectrekananfk as rekananid',
                'pp.jasa',  'sp.totalharusdibayar', 'sp.totalprekanan',
                'sp.totalbiayatambahan','pp.aturanpakai','pp.iscito','pd.statuspasien','pp.isparamedis','pp.strukresepfk'
            )
             ->where('pd.kdprofile', $kdProfile)
            ->where('pd.noregistrasi', $noregistasi);
//          ->orderBy('pp.tglpelayanan', 'pp.rke');

        $pelayanan = $pelayanan->get();

        if (count($pelayanan) > 0) {
            $details = null;
            foreach ($pelayanan as $value) {
                // if($value->prid != $this->getProdukIdDeposit()){
                    $jasa = 0;
                    if (isset($value->jasa) && $value->jasa != "" && $value->jasa != null) {
                        $jasa =(float) $value->jasa;
                    }

                    $harga = (float)$value->hargajual;
                    $diskon = (float)$value->hargadiscount;
                    $detail = array(
                        'norec' => $value->norec,
                        'tglPelayanan' => $value->tglpelayanan,
                        'namaPelayanan' => $value->namaproduk,
                        'jumlah' => (float)$value->jumlah,
                        'kelasTindakan' => @$value->namakelas,
                        'ruanganTindakan' => @$value->namaruangan,
                        'harga' => $harga,
                        'diskon' => $diskon,
                        'total' => (($harga - $diskon) * $value->jumlah) + $jasa,
                        'strukfk' => $value->nostruk ,
                        'sbmfk' => $value->nosbm,
                        'pgid' => '',
                        'ruid' => $value->ruid,
                        'prid' => $value->prid,
                        'klid' => $value->klid,
                        'norec_apd' => $value->norec_apd,
                        'norec_pd' => $value->norec_pd,
                        'norec_sp' => $value->norec_sp,
                        'jasa' => $jasa,
                        'aturanpakai' => $value->aturanpakai,
                        'iscito' => $value->iscito,
                        'isparamedis' => $value->isparamedis,
                        'strukresepfk' => $value->strukresepfk
                    );

                    $details[] = $detail;
                // }


            }
        }

        $arrHsil = array(
            'details' => $details,
            'deposit' => StrukBuktiPenerimaan::deposit($noregistasi),
            'totalklaim' =>   StrukPelayanan::totalKlaim($noregistasi),
            'bayar' => StrukBuktiPenerimaan::totalBayar($noregistasi),
        );
        return $this->respond($arrHsil);
    }

    public  function getTotalKlaim($noregistrasi,$kdProfile)
    {
       $pelayanan =collect(\DB::select("select sum(x.totalppenjamin) as totalklaim
         from (select spp.norec,spp.totalppenjamin
         from pasiendaftar_t as pd
            join antrianpasiendiperiksa_t as apd on apd.noregistrasifk=pd.norec
            join pelayananpasien_t as pp on pp.noregistrasifk =apd.norec
            join strukpelayanan_t as sp on sp.norec= pp.strukfk
            join strukpelayananpenjamin_t as spp on spp.nostrukfk=sp.norec
            where pd.noregistrasi ='$noregistrasi'
        --and spp.statusenabled is null
        and pd.kdprofile=$kdProfile
        GROUP BY spp.norec,spp.totalppenjamin

        ) as x"))->first();
        if(!empty($pelayanan) && $pelayanan->totalklaim!= null){
             return (float) $pelayanan->totalklaim;
         }else{
            return 0;
         }


    }
    public function getTotolBayar($noregistrasi,$kdProfile)
    {
      $pelayanan =collect(\DB::select("select sum(x.totaldibayar) as totaldibayar
         from (select sbm.norec,sbm.totaldibayar
         from pasiendaftar_t as pd
        join antrianpasiendiperiksa_t as apd on apd.noregistrasifk=pd.norec
        join pelayananpasien_t as pp on pp.noregistrasifk =apd.norec
        join strukpelayanan_t as sp on sp.norec= pp.strukfk
        join strukbuktipenerimaan_t as sbm on sbm.nostrukfk = sp.norec
        where pd.noregistrasi ='$noregistrasi'
        and sbm.statusenabled =true
        and pd.kdprofile=$kdProfile
        GROUP BY sbm.norec,sbm.totaldibayar

        ) as x"))->first();
        if(!empty($pelayanan) && $pelayanan->totaldibayar!= null){
             return (float) $pelayanan->totaldibayar;
         }else{
            return 0;
         }


    }
    public function getHistoryReservasiMobile(Request $request)
    {
        $kdProfile = $this->kdProfile;
        $data = DB::table('antrianpasienregistrasi_t as apr')
            ->leftJoin('pasien_m as pm', 'pm.id', '=', 'apr.nocmfk')
            ->leftJoin('alamat_m as alm', 'alm.nocmfk', '=', 'pm.id')
            ->leftJoin('jeniskelamin_m as jk', 'jk.id', '=', 'pm.objectjeniskelaminfk')
            ->leftJoin('jeniskelamin_m as jks', 'jks.id', '=', 'apr.objectjeniskelaminfk')
            ->leftJoin('pekerjaan_m as pk', 'pk.id', '=', 'pm.objectpekerjaanfk')
            ->leftJoin('pendidikan_m as pdd', 'pdd.id', '=', 'pm.objectpendidikanfk')
            ->leftJoin('ruangan_m as ru', 'ru.id', '=', 'apr.objectruanganfk')
            ->leftJoin('pegawai_m as pg', 'pg.id', '=', 'apr.objectpegawaifk')
            ->leftJoin('kelompokpasien_m as kps', 'kps.id', '=', 'apr.objectkelompokpasienfk')
            ->select(
                'apr.norec',
                'pm.nocm',
                'apr.noreservasi',
                'apr.tanggalreservasi',
                'apr.created_at as createdAt',
                'apr.objectruanganfk',
                'apr.objectpegawaifk',
                'ru.namaruangan',
                DB::raw('(case when apr.isconfirm is null then false else true end) as isConfirm'),
                'pg.namalengkap as dokter',
                'pm.id as nocmfk',
                'pm.namapasien',
                'apr.namapasien',
                'alm.alamatlengkap',
                'pk.pekerjaan',
                'pm.noasuransilain',
                'pm.noidentitas',
                'apr.nobpjs',
                'pm.nohp',
                'pdd.pendidikan',
                'apr.type',
                'kps.kelompokpasien',
                'apr.objectkelompokpasienfk',
                'ru.objectdepartemenfk',
                'ru.prefixnoantrian',
                'apr.norujukan',
                'ru.id as idruangan',
                'pg.id as iddokter',
                DB::raw('(case when pm.namapasien is null then apr.namapasien else pm.namapasien end) as namapasien,
                (case when apr.isconfirm=true then \'Confirm\' else \'Reservasi\' end) as status,case when pm.tempatlahir is null then apr.tempatlahir else pm.tempatlahir end as tempatlahir,
                case when jk.jeniskelamin is null then jks.jeniskelamin else jk.jeniskelamin end as jeniskelamin,
                case when apr.tgllahir is null then pm.tgllahir else apr.tgllahir end as tgllahir,
                case when apr.tipepasien = \'LAMA\' then pm.nohp else  apr.notelepon end as notelepon,
                case when apr.perjanjianfk is not null then  \'pasien-kontrol\' else  \'reservasi-online\' end as jenis,
                case when apr.statusenabled = true  then \'Aktif\' else \'Batal\' end as status')
            )

            ->where('apr.noreservasi', '!=', '-')
            ->whereNotNull('apr.noreservasi')
            ->where('apr.kdprofile',  $kdProfile);
            // ->where('apr.statusenabled', true);

        if (isset($request['jenis']) && $request['jenis'] != "" && $request['jenis'] == "reservasi-online" ) {
            $data =  $data->whereNull('apr.perjanjianfk');
        }
        if (isset($request['nocmNama']) && $request['nocmNama'] != "" && $request['nocmNama'] != "undefined" && $request['nocmNama'] != "null") {
            $data = $data->whereRaw("(pm.nocm  = '$request[nocmNama]' or apr.namapasien ilike '%$request[nocmNama]%')");
        }
        if (isset($request['tgllahir']) && $request['tgllahir'] != "" && $request['tgllahir'] != "undefined" && $request['tgllahir'] != "null" &&  $request['tgllahir'] != 'Invaliddate') {
            $tgllahir = $request['tgllahir'];
            $data =  $data->whereRaw("to_char( apr.tgllahir, 'dd-MM-yyyy')  ='$tgllahir' ");
        }

        if (isset($request['noReservasi']) && $request['noReservasi'] != "" && $request['noReservasi'] != "undefined" && $request['noReservasi'] != "null") {
            $data = $data->where('apr.noreservasi', $request['noReservasi']);
        }
        if (isset($request['nik']) && $request['nik'] != "" && $request['nik'] != "undefined" && $request['nik'] != "null") {
            $data =   $data->where('apr.noidentitas', $request['nik']);
        }
        if (
            isset($request['id_ruangan']) && $request['id_ruangan'] != ""
            && $request['id_ruangan'] != "undefined" && $request['id_ruangan'] != "null"
        ) {
            $data =  $data->where('apr.objectruanganfk', $request['id_ruangan']);
        }
        if (
            isset($request['id_dokter']) && $request['id_dokter'] != ""
            && $request['id_dokter'] != "undefined" && $request['id_dokter'] != "null"
        ) {
            $data =  $data->where('apr.objectpegawaifk', $request['id_dokter']);
        }
        if (
            isset($request['dari']) && $request['dari'] != ""
            && $request['dari'] != "undefined" && $request['dari'] != "null"
        ) {
            $data =  $data->where('apr.tanggalreservasi', '>=', $request['dari'] . ' 00:00');
        }
        if (
            isset($request['sampai']) && $request['sampai'] != ""
            && $request['sampai'] != "undefined" && $request['sampai'] != "null"
        ) {
            $data =  $data->where('apr.tanggalreservasi', '<=', $request['sampai'] . ' 23:59');
        }

        if (isset($request['cekin']) && $request['cekin'] == "true") {
            $data =  $data->whereNull('apr.isconfirm');
        }
        if (isset($request['isConfirm']) && $request['isConfirm'] == "true") {
            $data =  $data->where('apr.isconfirm',true);
        }
        if (isset($request['createdAt']) && $request['createdAt'] != "") {
            $dari = $request->createdAt . ' 00:00';
            $sampai = $request->createdAt . ' 23:59';
            $data =  $data->where('apr.created_at', '>=', $dari)->where('apr.created_at', '<=', $sampai);
        }
        if (isset($request['pasien_id']) && $request['pasien_id'] != "") {
            $data =  $data->where('pm.id',  $request['pasien_id']);
        }
        $data = $data->orderBy('apr.tanggalreservasi', 'desc');
        if (isset($request['jmlRows']) && $request['jmlRows'] != "" && $request['jmlRows'] != "undefined" && $request['jmlRows'] != "null" && $request['jmlRows'] != 0) {
            $data = $data->take($request['jmlRows']);
        }
        if (isset($request['limit']) && $request['limit'] != "" && $request['limit'] != "undefined" && $request['limit'] != "null" && $request['limit'] != 0) {
            $data = $data->take($request['limit']);
        }

        if (isset($request['jmlOffset']) && $request['jmlOffset'] != "" && $request['jmlOffset'] != "undefined" && $request['jmlOffset'] != "null") {
            $data = $data->offset($request['jmlOffset']);
        }
        $data = $data->get();
        $akrif = 0;
        $batal = 0;
        foreach($data as $d){
            if($d->status =='Aktif'){
                $akrif = $akrif+1;
            }else{
                $batal = $batal+1;
            }
        }
        $result = array(
            'total' => count($data),
            'aktif' => $akrif,
            'batal' => $batal,
            'data' => $data,
            'as' => '@epic',
        );
        return $this->respond($result);
    }
    public function updateJadwalReservasi(Request $request){
        $kdProfile = $this->kdProfile;
        // dd($request->all());
        DB::beginTransaction();
        try {

            $tgl =$request['tglReservasiFix'];
            $dataReservasi = \DB::table('antrianpasienregistrasi_t as apr')
                    ->select('apr.norec','apr.tanggalreservasi')
                    ->whereRaw("apr.tanggalreservasi = '$tgl'")
                    ->where('apr.objectruanganfk', $request['poliKlinik']['id'])
                    ->where('apr.noreservasi','!=','-')
                    ->whereNotNull('apr.noreservasi')
                    ->where('apr.statusenabled',true)
                    ->where('apr.kdprofile', (int) $kdProfile );

            if(isset($request['dokter']) && $request['dokter']!=null && isset($request['dokter']['id'])){
                $dataReservasi = $dataReservasi->where('apr.objectpegawaifk',$request['dokter']['id']);
            }
            $dataReservasi=$dataReservasi->get();

            if(count($dataReservasi) > 0){
                $result = array(
                    "status" => 400,
                    "message" => 'Mohon maaf dijam tersebut sudah ada yang reservasi, Coba di jadwal yang lain',
                );
                return $this->setStatusCode($result['status'])->respond($result, 'Mohon maaf dijam tersebut sudah ada yang reservasi, Coba di jadwal yang lain');
            }

            if(isset($request['dokter']) && $request['dokter']!=null && isset($request['dokter']['id'])){
                $dokter = \DB::table('jadwaldokter_m as slot')
                ->select('slot.hari','slot.objectruanganfk','slot.objectpegawaifk')
                ->where('slot.objectruanganfk', $request['poliKlinik']['id'])
                ->where('slot.objectpegawaifk', $request['dokter']['id'])
                ->where('slot.statusenabled', true)
                ->get();

                $hari = $this->hari_ini($request['tglReservasiFix']);

                $data10 =[];
                for ($i = count($dokter) - 1; $i >= 0; $i--) {
                    $now = explode(', ',$dokter[$i]->hari);
                    for ($i2 = count($now) - 1; $i2 >= 0; $i2--) {
                        if(strtoupper($now[$i2]) == strtoupper($hari)){
                            $data10 [] =$dokter[$i];
                        }
                    }
                }
                if(count($data10) == 0){
                    $msg = 'Jadwal Dokter tidak tersedia di Poli ini';
                    $result = array(
                        "status" => 400,
                        "message" => $msg
                    );
                    return $this->setStatusCode($result['status'])->respond($result, $msg);
                }
            }

            $dataReservasi = \DB::table('antrianpasienregistrasi_t')
                            ->where('noreservasi', $request['noreservasi'])
                            ->update([
                                'tanggalreservasi' => $request['tanggalreservasi'],
                                'tglupdatelast' => date('Y-m-d H:i:s'),
                                'keteranganupdate' => "update jadwal reservasi",
                                'objectpegawaifk' => $request['dokter']['id'],
                            ]);

            $reservasi =  \DB::table('antrianpasienregistrasi_t')->where('noreservasi', $request['noreservasi'])->first();


           $transStatus = true;
           $transMessage = 'update jadwal reservasi berhasil';
        } catch (\Exception $e) {
           $transStatus = false;
           $transMessage = 'update jadwal reservasi gagal';
        }

        if ($transStatus) {
            DB::commit();
            $result = array(
                "status" => 201,
                "message" => $transMessage,
                "data" => $reservasi,
                "as" => 'fate@epic',
            );
        }else{
            DB::rollBack();
            $result = array(
                "status" => 400,
                "message" => $transMessage,
                "as" => 'fate@epic',
            );

        }
        return $this->setStatusCode($result['status'])->respond($result, $transMessage);
    }

    public function batalReservasi(Request $request)
    {
        DB::beginTransaction();
        try {
            AntrianPasienRegistrasi::where('noreservasi',$request['noreservasi'])
            ->update([
               'keteranganupdate' => $request['keteranganbatal'],
               'statusenabled'=>false,
               'tglupdatelast' => date('Y-m-d H:i:s'),
            ]);

            $reservasi =  \DB::table('antrianpasienregistrasi_t')->where('noreservasi', $request['noreservasi'])->first();

            $transStatus = 'true';
            $transMessage = "Hapus Reservasi Sukses";
        } catch (\Exception $e) {
            $transStatus = 'false';
            $transMessage = "Hapus Reservasi Gagal";
        }

        if ($transStatus != 'false') {
            DB::commit();
            $result = array(
                "status" => 201,
                "message" => $transMessage,
                "data" => $reservasi,
                "as" => 'ramdan@epic',
            );
        } else {
            DB::rollBack();
            $result = array(
                "status" => 400,
                "message" => $transMessage,
            );
        }

        return $this->setStatusCode($result['status'])->respond($result, $transMessage);
    }

    public function UpdateStatConfirm(Request $request)
    {
        DB::beginTransaction();
        try {
            AntrianPasienRegistrasi::where('noreservasi',$request['noreservasi'])
            ->update([
               'isconfirm' => true,
            ]);

            $reservasi =  \DB::table('antrianpasienregistrasi_t')->where('noreservasi', $request['noreservasi'])->first();

            $registrasi = DB::table('pasiendaftar_t AS pd')
            ->Join('pasien_m as ps', 'ps.id', '=', 'pd.nocmfk')
            ->Join('antrianpasiendiperiksa_t as apdp', 'apdp.noregistrasifk', '=', 'pd.norec')
            ->Join('antrianpasienregistrasi_t as apr', 'apr.norec', '=', 'pd.antrianpasienregistrasifk')
            ->where('apr.noreservasi', '=', $request['noreservasi'])
            ->select('pd.norec as norec_pd', 'apdp.norec as norec_apd', 'pd.noregistrasi', 'ps.objectkebangsaanfk')
            ->first();
            // DB::select(DB::raw("select pd.norec, apd.norec
            // from antrianpasienregistrasi_t"));

            $transStatus = 'true';
            $transMessage = "Confirm Reservasi Sukses";
        } catch (\Exception $e) {
            $transStatus = 'false';
            $transMessage = "Confirm Reservasi Gagal";
        }

        if ($transStatus != 'false') {
            DB::commit();
            $result = array(
                "status" => 201,
                "message" => $transMessage,
                "data" => $reservasi,
                "registrasi" => $registrasi,
                "as" => '@vexana',
            );
        } else {
            DB::rollBack();
            $result = array(
                "status" => 400,
                "message" => $transMessage,
                "e" => $e->getMessage() . ' ' . $e->getLine()
            );
        }

        return $this->respond($result, $result['status'], $transMessage);
    }

    public function cekPasienByNik($nik) {
        $data =  Pasien::where('noidentitas',$nik)
            ->where('statusenabled',true)->get();

        $result = array(
            'data'=> $data,
            'message' => 'er@epic',
        );
        return $this->respond($result);
    }


    public function akunRegister(Request $r){
        DB::beginTransaction();
        try {
            $message = '';
            $kdProfile =  $this->kdProfile;
            $nocm = $r['nocm'];
            $tgllahir = $r['tgllahir'];

            $dataPasien = [];
            $dataPasien = DB::table('pasien_m as ps')
                ->select('ps.nocm', 'ps.namalengkap','ps.tgllahir','ps.jeniskelamin')
                ->JOIN('jeniskelamin_m as jk', 'jk.id', '=', 'ps.objectjeniskelaminfk')
                ->where('ps.nocm', $nocm)
                ->where('ps.tgllahir', $tgllahir)
                ->where('ps.kdprofile', $kdProfile)
                ->where('ps.statusenabled', true)
                ->get();

            if(count($dataPasien)>0){



                DB::commit();
                $response = [
                    'message' => 'Sukses.',
                    'status' => 201,
                    'datas' => $dataPasien
                ];
            }else{
                DB::commit();
                $response = [
                    'message' => 'Silahkan lakukan pendaftaran terlebih dahulu pada RSCM untuk mendapatkan No CM pasien guna bisa menggunakan applikasi RSCMKU.',
                    'status' => 400,
                    'datas' => $dataPasien
                ];
            }


        } catch (\Exception $e) {
            DB::rollBack();
            $response = [
                'message' => 'Gagal.',
                'status' => 400,
                'datas' => $e->getMessage()
            ];
        }

        return $this->respond($response);
    }
    public function savePasienKeluarga(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'nocmfk' => 'required',
            'hubungankeluargafk' => 'required',
        ],[
            'nocmfk.required' => 'Nocmfk Harus Disis !',
            'hubungankeluargafk.required' => 'Hubungankeluargafk Harus Disis !',
        ]);
        if ($validator->fails()) {
            $response = [
                'metaData' => [
                    "code" => 400,
                    "message" => 'Validator Failed !',
                ],
                'response' =>null,
                'error' => $validator->errors(),
            ];
            return response()->json($response, $response['metaData']['code']);
        }
        $data = $request->only('nocmfk','hubungankeluargafk');
        $pasienId = session('pasien')->id;
        $pasien = DB::table('pasien_m')->where('nocm' ,$data['nocmfk'])->where('statusenabled' ,true)->first();
        if (!$pasien) {
            $response = [
                'metaData' => [
                    "code" => 404,
                    "message" => 'No Rekam Medis tidak ditemukan !',
                ],
                'response' => null,
            ];
            return response()->json($response, $response['metaData']['code']);
        }
        $findHubunganKeluarga = HubunganKeluarga::find($data['hubungankeluargafk'])->where('statusenabled' ,true)->count();
        if ($findHubunganKeluarga == 0) {
            $response = [
                'metaData' => [
                    "code" => 404,
                    "message" => 'Hubungan Keluarga Tidak Ditemukan !',
                ],
                'response' => null,
            ];
            return response()->json($response, $response['metaData']['code']);
        }
        $findDuplicateData = AnggotaKeluarga::where('nocmfk' ,$pasien->id)->where('statusenabled' ,true)->where('nocmheadfk',$pasienId)->count();
        if ($findDuplicateData > 0) {
            $response = [
                'metaData' => [
                    "code" => 409,
                    "message" => 'Pasien Telah ditambahkan !',
                ],
                'response' => null,
            ];
            return response()->json($response, $response['metaData']['code']);
        }
        try {
            $anggotaKeluarga =  new AnggotaKeluarga();
            $anggotaKeluarga->norec = $anggotaKeluarga->generateNewId();
            $anggotaKeluarga->kdprofile = $this->kdProfile;
            $anggotaKeluarga->statusenabled = true;
            $anggotaKeluarga->nocmfk = $pasien->id;
            $anggotaKeluarga->nocmheadfk = $pasienId;
            $anggotaKeluarga->hubungankeluargafk = $data['hubungankeluargafk'];
            $anggotaKeluarga->save();
            $response = [
                'metaData' => [
                    "code" => 200,
                    "message" => 'Sukses',
                ],
                'response' => $anggotaKeluarga
            ];
        } catch (Exception $e) {
            $response = [
                'metaData' => [
                    "code" => 500,
                    "message" => 'Simpan gagal '
                ],
                'response' => null,
            ];
        }
        return response()->json($response, $response['metaData']['code']);
    }

    public function getPasienAnggotaKeluarga(Request $request)
    {
        $pasienId = session('pasien')->id;
        $data = DB::table('anggotakeluarga_t AS ag')
        ->join('pasien_m as ps' ,'ps.id' ,'ag.nocmfk')
        ->leftJoin('hubungankeluarga_m AS hu','hu.id' ,'ag.hubungankeluargafk')
        ->leftJoin('jeniskelamin_m AS jk','jk.id' ,'ps.objectjeniskelaminfk')
        ->select('hu.hubungankeluarga AS hubunganKeluarga', 'ps.namapasien AS namaPasien', 'jk.jeniskelamin AS jenisKelamin',
        DB::raw("TO_CHAR(ps.tgllahir, 'DD-Mon-YYYY') AS tglLahir"), 'nocm AS nocm')
        ->where('ag.statusenabled',true)
        ->where('ps.statusenabled',true)
        ->where('ag.nocmheadfk',$pasienId);
        $total = $data->count();
        if (isset($request['limit']) && $request['limit'] != '') {
            $data = $data->limit($request['limit']);
        }
        if (isset($request['offset']) && $request['offset'] != '') {
            $data = $data->offset($request['offset']);
        }
       $data = $data->get();
        if (count($data) == 0) {
            return $this->respond([],404, 'Data tidak ditemukan !');
        }
        $result = [
            'total' => $total,
            'data' => $data
        ];
        return $this->respond($result,200,'Success');
    }
    public function getHubunganKeluarga(Request $request){
        $search = $request->search;
        $data = HubunganKeluarga::mine()
        ->when($search,function ($query) use ($search){
            return $query->search($search);
        })
        ->get();
        if (count($data) == 0) {
            return $this->respond([],404, 'Data tidak ditemukan !');
        }
        return $this->respond($data,200,'Success');
    }
    public function saveCheckinPasien(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'kodereservasi' => 'required',
        ],[
            'kodereservasi.required' => 'Kode Reservasi Harus Diisi !',
        ]);
        if ($validator->fails()) {
            $response = [
                'metaData' => [
                    "code" => 400,
                    "message" => 'Validator Failed !',
                ],
                'response' =>null,
                'error' => $validator->errors(),
            ];
            return response()->json($response, $response['metaData']['code']);
        }

        $data = DB::table('antrianpasienregistrasi_t AS ar')
        ->join('pasien_m as ps' ,'ps.id' ,'ar.nocmfk')
        ->leftJoin('pegawai_m as pg' ,'pg.id' ,'ar.objectpegawaifk')
        ->leftJoin('ruangan_m as ru' ,'ru.id' ,'ar.objectruanganfk')
        ->select('ar.nocmfk as nocmfk' ,'ru.id as objectruanganlastfk' ,'ar.objectkelompokpasienfk' ,'pg.id as objectpegawaifk','ar.type as status',
                'ar.kdprofile as kdProfile' ,'ps.nocm','ps.namapasien as namapasien','ar.norec as norecreservasi' ,'ar.objectasalrujukanfk','ar.isconfirm',
                'ar.updated_at'
            )
        ->where('ar.noreservasi', $request->kodereservasi)
        ->first();
        if (!$data) {
            return $this->respond([],404, 'Data tidak ditemukan !');
        }
        if ($data->isconfirm == true) {
            $date = date('d-m-Y H:i:s', strtotime($data->updated_at));
            return $this->respond([],409, "Pasien telah checkin pada  ! $date");
        }
        $rekanan  = DB::table('mapkelompokpasientopenjamin_m as mkp')
        ->join('rekanan_m as rk', 'rk.id', '=', 'mkp.kdpenjaminpasien')
        ->select('rk.id', 'rk.namarekanan')
        ->where('mkp.objectkelompokpasienfk', $data->objectkelompokpasienfk)
        ->where('mkp.statusenabled', true)
        ->where('rk.statusenabled', true)
        ->distinct()
        ->where('mkp.kdprofile', $data->kdProfile)
        ->orderBy('rk.namarekanan')
        ->first();
        $result = [
            'pasiendaftar' =>[
                'norec' => '',
                'nocmfk' => $data->nocmfk,
                'tglregistrasi' => date('Y-m-d H:m:s'),
                'objectruanganlastfk' => $data->objectruanganlastfk,
                'asalrujukanfk' => $data->objectasalrujukanfk,
                'keteranganasalrujukan' => null,
                'objectkelompokpasienlastfk' =>$data->objectkelompokpasienfk,
                'jenispelayananfk'=>1,
                'objectpegawaifk' =>$data->objectpegawaifk,
                'objectpegawairawatbersamafk' => null,
                'objectkelasfk' => null,
                'objectkelasrawatfk' => null,
                'israwatinap' =>false,
                'catatan' =>false,
                'statuspasien' =>$data->status,
                'objectrekananfk' =>$rekanan->id ?? 0,
                'nocm' => $data->nocm,
                'namapasien' =>$data->namapasien,
                'antrianpasienregistrasifk' =>$data->norecreservasi
            ],
            'antrianpasiendiperiksa' =>[
                'norec' =>null,
                'objectkamarfk' =>null,
                'nobed' => null,
                'israwatgabung' =>null,
            ]
        ];
        $request = new \Illuminate\Http\Request();
        $request->merge($result);
        try {
            $res = app('App\Http\Controllers\Registrasi\RegistrasiRuanganCtrl')->saveRegistrasi($request);
            return $res;
        } catch (Exception $e) {
            $response = [
                'metaData' => [
                    "code" => 500,
                    "message" => 'Checkin gagal '
                ],
                'response' => null,
            ];
            return response()->json($response, $response['metaData']['code']);
        }
    }
    public function antrianPoli(Request $request){
        $kdProfile = $this->kdProfile;
        $startDate = Carbon::now()->startOfDay();
        $endDate = Carbon::now()->endOfDay();
        $ruangan = [];
        if ($request->has('ruangan')) {
            $ruangan = explode(',', $request->ruangan);
        }
        $apd = DB::table('antrianpasiendiperiksa_t as apd')
        ->join('ruangan_m as ru', 'ru.id', 'apd.objectruanganfk')
        ->when($ruangan ,function($query) use ($ruangan){
            return $query->whereIn('apd.objectruanganfk', $ruangan);
        })
        ->where('apd.statusenabled', true)
        ->where('apd.kdprofile', $kdProfile)
        ->whereBetween(DB::raw('apd.tglregistrasi::date'), [$startDate, $endDate])
        ->orderBy('apd.noantrian', 'asc')
        ->select('ru.namaruangan as namaRuangan', 'apd.noantrian as antrianSekarang' ,'apd.status')
        ->get();
        $respond = [];
        $poliklinikData = [];
        foreach ($apd as $item) {
            $namaRuangan = $item->namaRuangan;
            if (!isset($poliklinikData[$namaRuangan])) {
                $poliklinikData[$namaRuangan] = [
                    'namaRuangan' => $namaRuangan,
                    'antrianSekarang' => 0,
                    'sisaAntrian' => 0,
                ];
            }

            $poliklinikData[$namaRuangan]['antrianSekarang']++;
            if ($item->status != 'Sudah Dipanggil') {
                $poliklinikData[$namaRuangan]['sisaAntrian']++;
            }
        }

        $respond = [];
        foreach ($poliklinikData as &$poliklinik) {
            $respond[] = $poliklinik;
        }
        return $this->respond($respond);
    }
    public function antrianRadiologi(Request $request){
        $kdProfile = $this->kdProfile;
        $startDate = Carbon::now()->startOfDay();
        $endDate = Carbon::now()->endOfDay();
        $dep = $this->settingFix('idDepartemenRadiologi');
        $data = DB::table('pelayananpasien_t as pp')
        ->join('antrianpasiendiperiksa_t as apd', 'apd.norec', '=', 'pp.noregistrasifk')
        ->join('ruangan_m as ru', 'ru.id', 'apd.objectruanganfk')
        ->where('apd.statusenabled', true)
        ->where('apd.kdprofile', $kdProfile)
        ->whereBetween(DB::raw('apd.tglregistrasi::date'), [$startDate, $endDate])
        ->orderBy('apd.noantrian', 'asc')
        ->where('ru.objectdepartemenfk',$dep)
        ->select('ru.namaruangan as namaRuangan', 'apd.noantrian as antrianSekarang' ,'apd.status' ,'apd.tglregistrasi')
        ->get();
        $respond = [];
        $poliklinikData = [];
        foreach ($data as $item) {
            $namaRuangan = $item->namaRuangan;
            if (!isset($poliklinikData[$namaRuangan])) {
                $poliklinikData[$namaRuangan] = [
                    'namaRuangan' => $namaRuangan,
                    'antrianSekarang' => 0,
                    'sisaAntrian' => 0,
                ];
            }

            $poliklinikData[$namaRuangan]['antrianSekarang']++;
            if ($item->status != 'Sudah Dipanggil') {
                $poliklinikData[$namaRuangan]['sisaAntrian']++;
            }
        }

        $respond = [];
        foreach ($poliklinikData as &$poliklinik) {
            $respond[] = $poliklinik;
        }
        return $this->respond($respond);
    }

    public function getDataPasienOnlyRm(Request $request) {
        $kdProfile = $this->kdProfile;
        $norm = $request['norm'];
        $data = DB::table('pasien_m as ps')
        ->select('ps.id', 'ps.nocm', 'ps.namapasien', 'ps.noidentitas', 'ps.nobpjs', 'ps.tgllahir', 'jk.id as id_jeniskelamin', 'jk.jeniskelamin', 'al.id as id_alamat', 'al.alamatlengkap',
        DB::raw("case when ps.notelepon = null then '12345678' when ps.notelepon='' then '12345678' else ps.notelepon end as notelepon"))
        ->leftJoin('jeniskelamin_m as jk', 'jk.id', '=', 'ps.objectjeniskelaminfk')
        ->leftJoin('alamat_m as al', 'al.nocmfk', '=', 'ps.id')
        ->where('ps.statusenabled', true)
        ->where('ps.kdprofile', $kdProfile)
        ->where('ps.nocm', $norm)
        ->first();

        $result = [
            'data' => $data,
            'message' => 'hs@epic'
        ];
        return $this->respond($result);
    }
}
