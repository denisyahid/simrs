<?php

namespace App\Http\Controllers\Sysadmin;

use App\Http\Controllers\Controller;
use App\Models\Master\JadwalDokter;
use App\Models\Master\Pegawai;
use App\Models\Master\Ruangan;
use App\Traits\Valet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Mockery\Exception\InvalidOrderException;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use App\Models\Master\SlottingKiosk;

class MasterJadwalDokterCtrl extends Controller
{
    use Valet;
    public function __construct()
    {
        parent::__construct($is_encrypt = true);
    }
    public function masterJadwalDokter(Request $r)
    {
        $data  = DB::table('jadwaldokter_m as jd')
            ->join('ruangan_m as ru', 'jd.objectruanganfk', '=', 'ru.id')
            ->leftjoin('pegawai_m as pe', 'jd.objectpegawaifk', '=', 'pe.id')
            ->leftjoin('statuspraktek_m as sp', 'jd.objectstatuspraktekfk', '=', 'sp.id')

            ->select(
                'jd.id',
                'jd.objectpegawaifk',
                'jd.objectruanganfk',
                'jd.statusenabled',
                'ru.namaruangan',
                'pe.namalengkap',
                'jd.hari',
                'jd.jammulai',
                'jd.jamakhir',
                'jd.keterangan',
                'jd.objectstatuspraktekfk',
                'sp.statuspraktek',
                DB::raw("to_char(jd.tanggal,'DD-MM-YYYY') as tanggal"),
                'jd.quota'
            )
            ->where('jd.kdprofile', $this->kdProfile);

        if (isset($r['statusenabled']) && $r['statusenabled'] != '') {
            $data = $data->where('jd.statusenabled', '=',  $r['statusenabled']);
        }
        if (isset($r['namalengkap']) && $r['namalengkap'] != '') {
            $data = $data->where('pe.namalengkap', 'ilike', '%' . $r['namalengkap'] . '%');
        }
        if (isset($r['namaruangan']) && $r['namaruangan'] != '') {
            $data = $data->where('ru.id', '=', $r['namaruangan']);
        }
        if (isset($r['namalengkap']) && $r['namalengkap'] != '') {
            $data = $data->where('pe.id', '=', $r['namalengkap']);
        }
        if (isset($r['tanggal']) && $r['tanggal'] != '') {
            $data = $data->whereDate('jd.tanggal', '=', $r['tanggal']);
        }
        if (isset($r['objectruanganfk']) && $r['objectruanganfk'] != '') {
            // return "masuk ini";
            $data = $data->where('jd.objectruanganfk', '=', $r['objectruanganfk'] );
        }
        if (isset($r['objectpegawaifk']) && $r['objectpegawaifk'] != '') {
            $data = $data->where('jd.objectpegawaifk', '=', $r['objectpegawaifk'] );
        }
        if (
            isset($r['limit']) &&
            $r['limit'] != "" &&
            $r['limit'] != "undefined"
        ) {
            $data = $data->take($r['limit']);
        }

        $data = $data->orderByDesc('jd.id', 'desc');
        // $data = $data->orderBy('tanggal', 'desc');
        $data = $data->get();

        $res['data'] = $data;
        return $this->respond($res);
    }
    public function masterJadwalDokterdropdown(Request $r)
    {
        $res['namaruangan'] = Ruangan::mine()->get();
        $res['namalengkap'] = Pegawai::mine()->get();
        $res['statuspraktek'] = DB::table('statuspraktek_m')
            ->where('statusenabled', true)
            ->where('kdprofile', $this->kdProfile)
            ->get();
        return $this->respond($res);
    }

    public function updateBulkJadwalDokter(Request $r)
    {
        $arrJadwal = $r['jadwaldokter'];
        $forDokter = [];
        $forKiosk = [];
        $gettgl = array_column($arrJadwal, 'tanggal');

        try {
            DB::BeginTransaction();
            $cekDokter = JadwalDokter::whereIn('tanggal', $gettgl)
            ->where('statusenabled', true)
            ->where('kdprofile', (int)$this->kdProfile)
            ->get();
            $cekKiosK = SlottingKiosk::whereIn('tanggal', $gettgl)
            ->where('statusenabled', true)
            ->where('kdprofile', (int)$this->kdProfile)
            ->get();
            
            $jadwal = $arrJadwal[0];
            if(count($cekDokter) > 0) {
                foreach ($cekDokter as $fd) {
                    $forDokter[] = [
                        "id" => $fd->id,
                        "statusenabled" => true,
                        "kdprofile" => (int)$this->kdProfile,
                        "objectruanganfk" => $jadwal["objectruanganfk"],
                        "objectpegawaifk" => $jadwal["objectpegawaifk"],
                        "hari" => $jadwal["hari"],
                        "jammulai" => $jadwal["jammulai"],
                        "jamakhir" => $jadwal["jamakhir"],
                        "keterangan" => $jadwal["keterangan"],
                        "quota" => $jadwal["quota"],
                        "objectstatuspraktekfk" => $jadwal["objectstatuspraktekfk"],
                    ];
                }
            }
            if (count($cekKiosK) > 0) {
                foreach($cekKiosK as $dk) {
                    $forKiosk[] = [
                        "id" => $dk->id,
                        "statusenabled" => true,
                        "kdprofile" => (int)$this->kdProfile,
                        "objectruanganfk" => $jadwal["objectruanganfk"],
                        "objectpegawaifk" => $jadwal["objectpegawaifk"],
                        "jambuka" => $jadwal["jammulai"],
                        "jamtutup" => $jadwal["jamakhir"],
                        "quota" => $jadwal["quota"],
                        "quotafix" => $jadwal["quota"],
                        "tanggal" => $jadwal["tanggal"],
                        "loket" => $dk->loket,
                    ];
                }
            }
            
            // s
            foreach ($forDokter as $updateData) {
                JadwalDokter::where('id', $updateData['id'])->update($updateData);
            }
            foreach ($forKiosk as $updateData) {
                SlottingKiosk::where('id', $updateData['id'])->update($updateData);
            }

            DB::commit();

            $result = [
                "statusCode" => 201,
                "message" => "Simpan Berhasil",
                "result" => [
                    "data"  => null,
                    "as" => '@epic',
                ],
            ];
            return $this->respond($result['result'], $result['statusCode'], $result['message']);

        } catch (\Exception $e) {
            DB::rollBack();
            $result = array(
                "statusCode" => 500,
                "message" => "Simpan Gagal !",
                "result"  => $e->getMessage()

            );
            return $this->respond($result['result'], $result['statusCode'], $result['message']);
        }

    }

    public function saveBulkJadwalDokter(Request $r)
    {
        ini_set('max_execution_time', 60000);
        try {
            DB::beginTransaction();
            $arrJadwal = $r['jadwaldokter'];
            $forDokter = [];
            $forKiosk = [];
            $testRes = [];
            $updateDokter = [];
            $updateKiosk = [];

            $kiosid = SlottingKiosk::max('id') + 1;
            $datenow = date('Y-m-d H:i:s');
            $ids = [];
            foreach ($arrJadwal as $key => $jadwal) {

                //GA NGERTI LG
                $cek = JadwalDokter::where('kdprofile', (int)$this->kdProfile)
                    ->where('objectruanganfk', $jadwal["objectruanganfk"])
                    ->where('objectpegawaifk', $jadwal["objectpegawaifk"])
                    ->where('statusenabled', true)
                    ->where('tanggal', $jadwal["tanggal"])
                    ->get();
                if(count($cek) > 0) {
                    foreach ($cek as $updateData) {
                        JadwalDokter::where('id', $updateData['id'])->update([
                            "objectruanganfk" => $jadwal["objectruanganfk"],
                            "objectpegawaifk" => $jadwal["objectpegawaifk"],
                            "hari" => $jadwal["hari"],
                            "jammulai" => $jadwal["jammulai"],
                            "jamakhir" => $jadwal["jamakhir"],
                            "keterangan" => $jadwal["keterangan"],
                            "quota" => $jadwal["quota"],
                            "objectstatuspraktekfk" => $jadwal["objectstatuspraktekfk"],
                            "updated_at" => $datenow
                        ]);
                    }
                }else {
                    $forDokter[] = [
                        "id" => $this->SEQUENCE_MASTER(new JadwalDokter(), 'id', $this->kdProfile) + ($key + 1),
                        "statusenabled" => true,
                        "kdprofile" => (int)$this->kdProfile,
                        "objectruanganfk" => $jadwal["objectruanganfk"],
                        "objectpegawaifk" => $jadwal["objectpegawaifk"],
                        "hari" => $jadwal["hari"],
                        "jammulai" => $jadwal["jammulai"],
                        "jamakhir" => $jadwal["jamakhir"],
                        "tanggal" => $jadwal["tanggal"],
                        "keterangan" => $jadwal["keterangan"],
                        "quota" => $jadwal["quota"],
                        "objectstatuspraktekfk" => $jadwal["objectstatuspraktekfk"],
                        "created_at" => $datenow,
                        "updated_at" => $datenow
                    ];
                }

                $cekkiosk = SlottingKiosk::where('tanggal', $jadwal["tanggal"])
                ->where('statusenabled', true)
                ->when(isset($jadwal['objectpegawaifk']), function($q) use($jadwal) {
                    $q->where('objectpegawaifk', $jadwal['objectpegawaifk']);
                })
                ->where('objectruanganfk', $jadwal['objectruanganfk'])
                ->where('loket', 1)
                ->where('kdprofile', (int)$this->kdProfile)
                ->get();
                if(count($cekkiosk) > 0) {
                    foreach ($cekkiosk as $updateData) {
                        SlottingKiosk::where('id', $updateData['id'])->update([
                            "objectruanganfk" => $jadwal["objectruanganfk"],
                            "objectpegawaifk" => $jadwal["objectpegawaifk"],
                            "jambuka" => $jadwal["jammulai"],
                            "jamtutup" => $jadwal["jamakhir"],
                            "quota" => $jadwal["quota"],
                            "quotafix" => $jadwal["quota"],
                            "tanggal" => $jadwal["tanggal"],
                            "polisore" => $jadwal["polisore"],
                            "updated_at" => $datenow
                        ]);
                    }
                    
                }else {
                    // return "GAMASUK";
                    $forKiosk[] = [
                        "id" => $kiosid++,
                        "statusenabled" => true,
                        "kdprofile" => (int)$this->kdProfile,
                        "objectruanganfk" => $jadwal["objectruanganfk"],
                        "objectpegawaifk" => $jadwal["objectpegawaifk"],
                        "jambuka" => $jadwal["jammulai"],
                        "jamtutup" => $jadwal["jamakhir"],
                        "quota" => $jadwal["quota"],
                        "quotafix" => $jadwal["quota"],
                        "tanggal" => $jadwal["tanggal"],
                        "polisore" => $jadwal["polisore"],
                        "loket" => 1,
                        "created_at" => $datenow,
                        "updated_at" => $datenow
                    ];
                }

                // yg kedua, idk tp cegah bug we
                $cekkiosk2 = SlottingKiosk::where('tanggal', $jadwal["tanggal"])
                ->where('statusenabled', true)
                ->when(isset($jadwal['objectpegawaifk']), function($q) use($jadwal) {
                    $q->where('objectpegawaifk', $jadwal['objectpegawaifk']);
                })
                ->where('objectruanganfk', $jadwal['objectruanganfk'])
                ->where('loket', 2)
                ->where('kdprofile', (int)$this->kdProfile)
                ->get();
                
                if(count($cekkiosk2) > 0) {
                    foreach ($cekkiosk2 as $updateData) {
                        SlottingKiosk::where('id', $updateData['id'])->update([
                            "objectruanganfk" => $jadwal["objectruanganfk"],
                            "objectpegawaifk" => $jadwal["objectpegawaifk"],
                            "jambuka" => $jadwal["jammulai"],
                            "jamtutup" => $jadwal["jamakhir"],
                            "quota" => $jadwal["quota"],
                            "quotafix" => $jadwal["quota"],
                            "tanggal" => $jadwal["tanggal"],
                            "polisore" => $jadwal["polisore"],
                        ]);
                    }
                }else {
                    $forKiosk[] = [
                        "id" => $kiosid++,
                        "statusenabled" => true,
                        "kdprofile" => (int)$this->kdProfile,
                        "objectruanganfk" => $jadwal["objectruanganfk"],
                        "objectpegawaifk" => $jadwal["objectpegawaifk"],
                        "jambuka" => $jadwal["jammulai"],
                        "jamtutup" => $jadwal["jamakhir"],
                        "quota" => $jadwal["quota"],
                        "quotafix" => $jadwal["quota"],
                        "tanggal" => $jadwal["tanggal"],
                        "polisore" => $jadwal["polisore"],
                        "loket" => 2,
                        "created_at" => $datenow,
                        "updated_at" => $datenow
                    ];
                }
            }
            if(count($forDokter) > 0) {
                $dokter = JadwalDokter::insert($forDokter);
            }
            if(count($forKiosk) > 0) {
                $kiosk = SlottingKiosk::insert($forKiosk);
            }
            
            DB::commit();

            $result = [
                "statusCode" => 201,
                "message" => "Simpan Berhasil",
                "result" => [
                    "data" => [],
                    "as" => '@epic',
                ],
            ];
            return $this->respond($result['result'], $result['statusCode'], $result['message']);
        } catch (\Exception $e) {
            DB::rollback();
            $result = array(
                "statusCode" => 500,
                "message" => "Simpan Gagal !",
                "result"  => $e->getMessage()
            );
            return $this->respond($result['result'], $result['statusCode'], $result['message']);
        }
    }

    public function saveJadwalDokter(Request $r){
        ini_set('max_execution_time', 60000);
        DB::beginTransaction();
        try {
            // $count = JadwalDokter::count();
            $PSN =  $r['jadwaldokter'];
            $startDate = $PSN['tglAwal'];
            $endDate = $PSN['tglAkhir'];

            $dateRange = CarbonPeriod::create($startDate, $endDate);
            foreach ($dateRange as $date) {
                //echo $date->format('Y-m-d');
                //var_dump($date->format('Y-m-d'));
                // return $date;
                if ($PSN['id'] == '') {
                    $dokter = new JadwalDokter();
                    $dokter->id = $this->SEQUENCE_MASTER(new JadwalDokter(),'id',$this->kdProfile);//(string)Uuid::uuid4();
                    $dokter->statusenabled = true;
                    $transMessage = "Proses Simpan Data Berhasil";
                } else {
                    $dokter = JadwalDokter::where('id', $PSN['id'])->first();
                    $dokter->statusenabled = $PSN['statusenabled'];
                    $transMessage = "Proses Update Data Berhasil";
                    $dokter->id;
                }
                $dokter->kdprofile = (int)$this->kdProfile;
                $dokter->objectruanganfk =  $PSN['objectruanganfk'];
                $dokter->objectpegawaifk =  $PSN['objectpegawaifk'];
                $dokter->hari =  isset($PSN['hari'])?$PSN['hari']:null;
                $dokter->jammulai = $PSN['jammulai'];
                $dokter->jamakhir = $PSN['jamakhir'];
                $dokter->tanggal = $date->format('Y-m-d');
                $dokter->keterangan = $PSN['keterangan'];
                $dokter->quota = $PSN['quota'];
                $dokter->objectstatuspraktekfk = $PSN['objectstatuspraktekfk'];
                $dokter->save();

                if($PSN['objectpegawaifk'] == null){
                    $cek = SlottingKiosk::where('tanggal', $date->format('Y-m-d'))
                    ->where('objectruanganfk', $PSN['objectruanganfk'])
                    ->where('loket', 1)
                    ->first();
                } else{
                    $cek = SlottingKiosk::where('tanggal', $date->format('Y-m-d'))
                    ->where('objectruanganfk', $PSN['objectruanganfk'])
                    ->where('loket', 1)
                    ->where('objectpegawaifk', $PSN['objectpegawaifk'])
                    ->first();
                }
                

                if(empty($cek)){
                    $newptp = new SlottingKiosk();
                    $newptp->id = SlottingKiosk::max('id')+1;
                    $newptp->statusenabled = true;
                    $newptp->kdprofile = (int)$this->kdProfile;
                }else{
                    $newptp = $cek;
                }

                $newptp->objectruanganfk = $PSN['objectruanganfk'];
                $newptp->objectpegawaifk = $PSN['objectpegawaifk'];
                $newptp->jambuka = $PSN['jammulai'];
                $newptp->jamtutup =  $PSN['jamakhir'];
                $newptp->quota =  $PSN['quota'];
                $newptp->quotafix =  $PSN['quota'];
                $newptp->tanggal =$date->format('Y-m-d');
                $newptp->loket = 1;
                $newptp->save();

                if($PSN['objectpegawaifk'] == null){
                    $cek2 = SlottingKiosk::where('tanggal', $date->format('Y-m-d'))
                    ->where('objectruanganfk', $PSN['objectruanganfk'])
                    ->where('loket', 2)
                    ->first();
                } else{
                    $cek2 = SlottingKiosk::where('tanggal', $date->format('Y-m-d'))
                    ->where('objectruanganfk', $PSN['objectruanganfk'])
                    ->where('loket', 2)
                    ->where('objectpegawaifk', $PSN['objectpegawaifk'])
                    ->first();
                }
                

                if(empty($cek2)){
                    $newptp2 = new SlottingKiosk();
                    $newptp2->id = SlottingKiosk::max('id')+1;
                    $newptp2->statusenabled = true;
                    $newptp2->kdprofile = (int)$this->kdProfile;
                }else{
                    $newptp2 = $cek2;
                }

                $newptp2->objectruanganfk = $PSN['objectruanganfk'];
                $newptp2->objectpegawaifk = $PSN['objectpegawaifk'];
                $newptp2->jambuka = $PSN['jammulai'];
                $newptp2->jamtutup =  $PSN['jamakhir'];
                $newptp2->quota =  $PSN['quota'];
                $newptp2->quotafix =  $PSN['quota'];
                $newptp2->tanggal =$date->format('Y-m-d');
                $newptp2->loket = 2;
                $newptp2->save();
            }
            //var_dump($dateRange->toArray());

            

            DB::commit();

            $result = [
                "statusCode" => 201,
                "message" => "Simpan Berhasil",
                "result" => [
                    "data"  => $dokter,
                    "as" => '@epic',
                ],
            ];
            return $this->respond($result['result'], $result['statusCode'], $result['message']);

        } catch (InvalidOrderException $e) {
            DB::rollBack();
            $result = array(
                "statusCode" => $e->getCode(),
                "message" => "Simpan Gagal !",
                "result"  => $e->getMessage()

            );
            return $this->respond($result['result'], $result['statusCode'], $result['message']);
        }
    }

    public function deleteJadwalDokter(Request $r)
    {
        DB::beginTransaction();
        try {

            if($r['objectpegawaifk'] == null){
                $cek2 = SlottingKiosk::where('tanggal', $r['tanggal'])
                ->where('objectruanganfk', $r['objectruanganfk'])
                ->where('loket', 2)
                ->update([
                    'statusenabled' => false
                ]);

                $cek = SlottingKiosk::where('tanggal', $r['tanggal'])
                ->where('objectruanganfk', $r['objectruanganfk'])
                ->where('loket', 1)
                ->update([
                    'statusenabled' => false
                ]);
            } else{
                $cek2 = SlottingKiosk::where('tanggal', $r['tanggal'])
                ->where('objectruanganfk', $r['objectruanganfk'])
                ->where('loket', 2)
                ->where('objectpegawaifk', $r['objectpegawaifk'])
                ->update([
                    'statusenabled' => false
                ]);

                $cek = SlottingKiosk::where('tanggal', $r['tanggal'])
                ->where('objectruanganfk', $r['objectruanganfk'])
                ->where('loket', 1)
                ->where('objectpegawaifk', $r['objectpegawaifk'])
                ->update([
                    'statusenabled' => false
                ]);
            }

            $dataPS = JadwalDokter::where('id', $r['id'])
                ->update([
                    'statusenabled' => false
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
                    "data"  => $dataPS,
                    "as" => '@epic',
                ),
            );
        } else {
            $transMessage = "Hapus Gagal";
            DB::rollBack();

            $result = array(
                "status" => 400,
                "result"  => null

            );
        }
        return $this->respond($result['result'], $result['status'], $transMessage);
    }
}
