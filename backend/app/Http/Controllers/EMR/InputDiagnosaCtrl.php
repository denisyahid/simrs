<?php

namespace App\Http\Controllers\EMR;

use App\Http\Controllers\Controller;
use App\Models\Master\Diagnosa;
use App\Models\Master\DiagnosaTindakan;
use App\Models\Master\DiagnosaMorfologi;
use App\Models\Master\JenisDiagnosa;
use App\Models\Master\JenisPetugasPelaksana;
use App\Models\Master\Pegawai;
use App\Models\Master\Ruangan;
use App\Models\Transaksi\AntrianPasienDiperiksa;
use App\Models\Transaksi\DetailDiagnosaPasien;
use App\Models\Transaksi\DetailDiagnosaTindakanPasien;
use App\Models\Transaksi\DetailDiagnosaMorfologiPasien;
use App\Models\Transaksi\DiagnosaPasien;
use App\Models\Transaksi\DiagnosaTindakanPasien;
use App\Models\Transaksi\DiagnosaMorfologiPasien;
use App\Models\Transaksi\PasienDaftar;
use App\Models\Transaksi\PelayananPasien;
use App\Models\Transaksi\PelayananPasienDetail;
use App\Models\Transaksi\PelayananPasienPetugas;
use App\Traits\Valet;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;

class InputDiagnosaCtrl extends Controller
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
                'ps.email',
                'pd.objectkelompokpasienlastfk',
                'pd.objectruanganasalfk'
            )
            ->where('ps.kdprofile', (int)$this->kdProfile)
            ->where('ps.statusenabled', true)
            ->where('ps.id', $r['nocmfk'])
            ->first();
        if (!empty($data)) {
            $data->umur =  $this->getAge($data->tgllahir, date('Y-m-d H:i:s'));
        }
        $registrasi =  DB::table('pasiendaftar_t as pd')
            ->JOIN('antrianpasiendiperiksa_t as apd', 'apd.noregistrasifk', '=', 'pd.norec')
            ->JOIN('ruangan_m as ru', 'ru.id', '=', 'apd.objectruanganfk')
            ->JOIN('departemen_m as dp', 'dp.id', '=', 'ru.objectdepartemenfk')
            ->LEFTJOIN('kelompokpasien_m as kp', 'kp.id', '=', 'pd.objectkelompokpasienlastfk')
            ->LEFTJOIN('pemakaianasuransi_t as pa', 'pa.noregistrasifk', '=', 'pd.norec')
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
                'pd.objectruanganasalfk',
                'apd.objectruanganfk',
                'ru.namaruangan',
                'pd.objectkelasfk',
                'kl.namakelas',
                'pd.nocmfk',
                'apd.tglmasuk',
                'apd.tglkeluar',
                'apd.nobed',
                'pa.nosep',
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
        $result['as'] = '@epic';

        return $this->respond($result);
    }

    public function listTindakan(Request $r)
    {
        $data = DB::table('mapruangantoproduk_m as mpr')
            ->join('produk_m as prd', 'prd.id', '=', 'mpr.objectprodukfk')
            ->select(
                'mpr.objectprodukfk as id',
                'prd.namaproduk',
                'mpr.objectruanganfk',
                'prd.namaproduk'
            )
            ->where('mpr.kdprofile', $this->kdProfile)
            ->where('mpr.objectruanganfk', $r['idruangan'])
            ->where('mpr.statusenabled', true)
            ->where('prd.statusenabled', true);

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
        $result['data'] = $data;
        $result['as'] = '@epic';

        return $this->respond($result);
    }
    public function listDropdownDiagnosa(Request $r)
    {
        $result['jenisdiagnosa'] =  JenisDiagnosa::mine()->get();
        return $this->respond($result);
    }
    // public function listDianosaX(Request $r)
    // {
    //     $result['diagnosa'] =
    //         // Diagnosa::whereRaw("regexp_replace(kddiagnosa, '[^a-zA-Z0-9]', '', 'g') ilike '%".$r['name']."%'")
    //         Diagnosa::whereRaw("kddiagnosa ilike '%".$r['name']."%' and statusenabled = true")
    //         ->paging($r['limit'])
    //         ->orderBy('kddiagnosa')
    //         ->get();

    //     return $this->respond($result);
    // }
    public function listDianosaX(Request $r)
    {
        $keyword = strtolower(trim($r['name']));
        $result['diagnosa'] = Diagnosa::where(function ($query) use ($keyword) {
            $query->whereRaw("LOWER(regexp_replace(kddiagnosa, '[^a-zA-Z0-9]', '', 'g')) ilike ?", ["%{$keyword}%"])
                ->orWhereRaw("LOWER(namadiagnosa) ilike ?", ["%{$keyword}%"]); 
        })
        ->where('statusenabled', true) 
        ->orderBy('kddiagnosa') 
        ->paging($r['limit']) 
        ->get();

        return $this->respond($result);
    }

    public function listDianosaIX(Request $r)
    {
        $result['diagnosatindakan'] =
            // DiagnosaTindakan::whereRaw("regexp_replace(kddiagnosatindakan, '[^a-zA-Z0-9]', '', 'g') ilike '%".$r['name']."%'")
            DiagnosaTindakan::whereRaw("regexp_replace(kddiagnosatindakan, '[^a-zA-Z0-9]', '', 'g') ilike '%".$r['name']."%' ")
            ->orWhereRaw("LOWER(namadiagnosatindakan) ilike '%".$r['name']."%' ")
            ->orWhereRaw("LOWER(kddiagnosatindakan || ' - ' || namadiagnosatindakan) ilike '%".$r['name']."%' ")
            ->paging($r['limit'])
            ->orderBy('kddiagnosatindakan');

        $result['diagnosatindakan'] = $result['diagnosatindakan']->get();
        return $this->respond($result);
    }
    public function listDianosaO(Request $r)
    {
        $result['diagnosamorfologi'] =
            DiagnosaMorfologi::mine()
            ->search($r['name'])
            ->paging($r['limit'])
            ->orderBy('kddiagnosamorfologi');

        if (isset($r['id']) && $r['id'] != "" && $r['id'] != "undefined") {
            $result['diagnosamorfologi'] = $result['diagnosamorfologi']->where('id', '=', $r['id']);
        };

        if (isset($r['kddiagnosamorfologi']) && $r['kddiagnosamorfologi'] != "" && $r['kddiagnosamorfologi'] != "undefined") {
            $result['diagnosamorfologi'] = $result['diagnosamorfologi']->where('kddiagnosamorfologi', 'ilike', '%'.$r['kddiagnosamorfologi'].'%');
        };

        $result['diagnosamorfologi'] = $result['diagnosamorfologi']->get();
        return $this->respond($result);
    }
    public function saveDiagnosaPasien(Request $r)
    {

        DB::beginTransaction();

        $diagP = $r['diagnosapasien'];

        $diagDP = $r['detaildiagnosapasien'];

        $getRegistrasi = AntrianPasienDiperiksa::where('norec', $diagP['noregistrasifk'])->first();

        try {
            if ($diagP['norec'] == '') {
                $model = new DiagnosaPasien();
                $model->norec = $model->generateNewId();
                $model->kdprofile = $this->kdProfile;
                $model->statusenabled = true;
            } else {
                $model = DiagnosaPasien::where('norec', $diagP['norec'])->first();
                DetailDiagnosaPasien::where('objectdiagnosapasienfk', $diagP['norec'])->delete();
            }
            $model->noregistrasifk = $diagP['noregistrasifk'];
            $model->ketdiagnosis = $diagP['ketdiagnosis'];
            $model->tglregistrasi = $getRegistrasi['tglregistrasi'];
            $model->tglpendaftaran = $getRegistrasi['tglregistrasi'];
            $model->iskasusbaru = $diagP['iskasusbaru'];
            $model->iskasuslama = $diagP['iskasuslama'];
            $model->save();

            $model2 = new DetailDiagnosaPasien();
            $model2->norec = $model2->generateNewId();
            $model2->kdprofile = $this->kdProfile;
            $model2->statusenabled = true;
            $model2->keterangan = $diagP['ketdiagnosis'];
            $model2->objectpegawaifk = $this->getPegawai()->id;
            $model2->noregistrasifk = $diagP['noregistrasifk'];
            $model2->tglregistrasi = $getRegistrasi['tglregistrasi'];
            $model2->norec = $model2->generateNewId();
            $model2->objectdiagnosafk = $diagDP['objectdiagnosafk'];
            $model2->objectdiagnosapasienfk = $model->norec;
            $model2->objectjenisdiagnosafk = $diagDP['objectjenisdiagnosafk'];
            $model2->tglinputdiagnosa = $diagDP['tglinputdiagnosa'];
            $model2->keterangan = $diagP['ketdiagnosis'];
            $model2->objectpegawaifk = $this->getPegawai()->id;
            $model2->noregistrasi =  $getRegistrasi['noregistrasi'];
            $model2->iskasusbaru =  isset($diagDP['iskasusbaru']) ? $diagDP['iskasusbaru'] : null;
            $model2->iskasuslama =  isset($diagDP['iskasuslama']) ? $diagDP['iskasuslama'] : null;
            $model2->save();

            $this->LOGGING(
                'Tambah Diagnosa',
                $model->norec,
                'diagnosapasien_t',
                'Tambah Diagnosa ' . ' pada Pasien ' .
                    $r['pasien']['namapasien'] ?? null . ' (' . $r['pasien']['nocm'] ?? null . ') - ' . $r['pasien']['noregistrasi'] ?? null
            );


            DB::commit();
            $objetoRequest = new \Illuminate\Http\Request();
            $objetoRequest['noregistrasi'] = $getRegistrasi['noregistrasi'];
            $ihs = app('App\Http\Controllers\Bridging\SATUSEHATCtrl')->Condition($objetoRequest, true);
            $result = [
                'message' => 'Simpan ICD 10',
                'result' => $model,
                'Condition' => $ihs,
                'status' => 200
            ];
        } catch (Exception $e) {
            DB::rollBack();
            $result = [
                'message' => 'Simpan Gagal',
                'result' => $e->getMessage() . $e->getLine(),
                'status' => 400
            ];
        }

        return $this->respond($result['result'], $result['status'], $result['message']);
    }

    public function saveDiagnosaPasienRM(Request $r)
    {

        DB::beginTransaction();

        $diagP = $r['diagnosapasien'];

        $diagDP = $r['detaildiagnosapasien'];

        $getRegistrasi = AntrianPasienDiperiksa::where('norec', $diagP['noregistrasifk'])->first();

        try {
            if ($diagP['norec'] == '') {
                $model = new DiagnosaPasien();
                $model->norec = $model->generateNewId();
                $model->kdprofile = $this->kdProfile;
                $model->statusenabled = true;
            } else {
                $model = DiagnosaPasien::where('norec', $diagP['norec'])->first();
                DetailDiagnosaPasien::where('objectdiagnosapasienfk', $diagP['norec'])->delete();
            }
            $model->noregistrasifk = $diagP['noregistrasifk'];
            $model->ketdiagnosis = $diagP['ketdiagnosis'];
            $model->tglregistrasi = $getRegistrasi['tglregistrasi'];
            $model->tglpendaftaran = $getRegistrasi['tglregistrasi'];
             // $model->iskasusbaru = $diagP['iskasusbaru'];
            // $model->iskasuslama = $diagP['iskasuslama'];

            $kasusLama = DB::table('pasiendaftar_t as pd')
            ->leftJoin('pasien_m as ps','ps.id', '=', 'pd.nocmfk')
            ->leftJoin('antrianpasiendiperiksa_t as apd', 'apd.noregistrasifk', '=', 'pd.norec')
            ->leftJoin('diagnosapasien_t as dp', 'dp.noregistrasifk', '=', 'apd.norec')
            ->join('detaildiagnosapasien_t as ddp', 'ddp.objectdiagnosapasienfk', '=', 'dp.norec')
            ->where('ps.nocm', '=', $r['pasien']['nocm']) 
            ->where('pd.norec', '<', $getRegistrasi['norec']) 
            ->where('ddp.objectjenisdiagnosarmfk', '=', $diagDP['objectjenisdiagnosarmfk']) 
            ->exists(); // Jika ada, berarti kasus lama

            // Jika tidak ada kasus lama, maka iskasusbaru = true
            $model->iskasusbaru = !$kasusLama;
            $model->iskasuslama = $kasusLama;
            $model->save();

            $model2 = new DetailDiagnosaPasien();
            $model2->norec = $model2->generateNewId();
            $model2->kdprofile = $this->kdProfile;
            $model2->statusenabled = true;
            $model2->keterangan = $diagP['ketdiagnosis'];
            $model2->objectpegawaifk = $this->getPegawai()->id;
            $model2->noregistrasifk = $diagP['noregistrasifk'];
            $model2->tglregistrasi = $getRegistrasi['tglregistrasi'];
            $model2->norec = $model2->generateNewId();
            $model2->objectdiagnosarmfk = $diagDP['objectdiagnosafk'];
            $model2->objectdiagnosapasienfk = $model->norec;
            $model2->objectjenisdiagnosarmfk = $diagDP['objectjenisdiagnosarmfk'];
            $model2->tglinputdiagnosa = $diagDP['tglinputdiagnosa'];
            $model2->keterangan = $diagP['ketdiagnosis'];
            $model2->iskematian = $diagDP['iskematian'];
            $model2->ismoi = isset($diagDP['ismoi']) ? $diagDP['ismoi'] : null;
            $model2->ismasuk = isset($diagDP['ismasuk']) ? $diagDP['ismasuk'] : null;
            $model2->objectpegawaifk = $this->getPegawai()->id;
            $model2->noregistrasi =  $getRegistrasi['noregistrasi'];
            // $model2->iskasusbaru =  isset($diagDP['iskasusbaru']) ? $diagDP['iskasusbaru'] : null;
            // $model2->iskasuslama =  isset($diagDP['iskasuslama']) ? $diagDP['iskasuslama'] : null;
            $model2->iskasusbaru = !$kasusLama; // Update iskasusbaru 
            $model2->iskasuslama = $kasusLama;
            $model2->save();

            $this->LOGGING(
                'Tambah Diagnosa',
                $model->norec,
                'diagnosapasien_t',
                'Tambah Diagnosa ' . ' pada Pasien ' .
                    $r['pasien']['namapasien'] ?? null . ' (' . $r['pasien']['nocm'] ?? null . ') - ' . $r['pasien']['noregistrasi'] ?? null
            );


            DB::commit();
            $objetoRequest = new \Illuminate\Http\Request();
            $objetoRequest['noregistrasi'] = $getRegistrasi['noregistrasi'];
            $ihs = app('App\Http\Controllers\Bridging\SATUSEHATCtrl')->Condition($objetoRequest, true);
            $result = [
                'message' => 'Simpan ICD 10',
                'result' => $model,
                'Condition' => $ihs,
                'status' => 200
            ];
        } catch (Exception $e) {
            DB::rollBack();
            $result = [
                'message' => 'Simpan Gagal',
                'result' => $e->getMessage() . $e->getLine(),
                'status' => 400
            ];
        }

        return $this->respond($result['result'], $result['status'], $result['message']);
    }

    public function saveDiagnosaPasienKlaim(Request $r)
    {

        DB::beginTransaction();

        $diagP = $r['diagnosapasien'];

        $diagDP = $r['detaildiagnosapasien'];

        // ini cek diagnosa dari klaim

        $cekkodediag = Diagnosa::where('kddiagnosa', '=', $diagDP['objectdiagnosafk'])->where('statusenabled', '=', true)->first();
        if (empty($cekkodediag)) {
            $newId = Diagnosa::max('id');
            $newId = $newId + 1;
            $dataDiagnosa = new Diagnosa();
            $dataDiagnosa->id = $newId;
            $dataDiagnosa->kdprofile = $this->kdProfile;
            $dataDiagnosa->statusenabled = true;
            $dataDiagnosa->namaexternal = $diagDP['namadiagnosa'];
            $dataDiagnosa->norec = $newId;
            $dataDiagnosa->reportdisplay = $diagDP['namadiagnosa'];
            $dataDiagnosa->kddiagnosa = $diagDP['objectdiagnosafk'];
            $dataDiagnosa->namadiagnosa = $diagDP['namadiagnosa'];
            $dataDiagnosa->qdiagnosa = $newId;
            $dataDiagnosa->save();
            $iddiagnosa = $dataDiagnosa->id;
        } else {
            $cekkodediag2 = Diagnosa::where('kddiagnosa', '=', $diagDP['objectdiagnosafk'])->where('namadiagnosa', '=', $diagDP['namadiagnosa'])->where('statusenabled', '=', true)->first();
            if (empty($cekkodediag2)) {
                $cekkodediag->namaexternal = $diagDP['namadiagnosa'];
                $cekkodediag->reportdisplay = $diagDP['namadiagnosa'];
                $cekkodediag->namadiagnosa = $diagDP['namadiagnosa'];
                $cekkodediag->save();
                $iddiagnosa = $cekkodediag->id;
            } else {
                $iddiagnosa = $cekkodediag2->id;
            }
        }

        // end

        $getRegistrasi = AntrianPasienDiperiksa::where('norec', $diagP['noregistrasifk'])->first();

        try {
            $cek = DetailDiagnosaPasien::where('objectdiagnosaklaimfk', $iddiagnosa)->where('noregistrasifk', $diagP['noregistrasifk'])->first();

            if ($diagP['norec'] == '') {
                $model = new DiagnosaPasien();
                $model->norec = $model->generateNewId();
                $model->kdprofile = $this->kdProfile;
                $model->statusenabled = true;
            } else {
                $model = DiagnosaPasien::where('norec', $diagP['norec'])->first();
                if(empty($cek)){
                    DetailDiagnosaPasien::where('objectdiagnosapasienfk', $diagP['norec'])->delete();
                }
            }
            $model->noregistrasifk = $diagP['noregistrasifk'];
            $model->ketdiagnosis = $diagP['ketdiagnosis'];
            $model->tglregistrasi = $getRegistrasi['tglregistrasi'];
            $model->tglpendaftaran = $getRegistrasi['tglregistrasi'];
            $model->iskasusbaru = $diagP['iskasusbaru'];
            $model->iskasuslama = $diagP['iskasuslama'];
            $model->save();

            if($diagDP['objectjenisdiagnosaklaimfk'] == 1){
                $jenisdiagnosaina = 7;
            } else{
                $jenisdiagnosaina = 8;
            }

            if(empty($cek)){
                $model2 = new DetailDiagnosaPasien();
                $model2->norec = $model2->generateNewId();
                $model2->kdprofile = $this->kdProfile;
                $model2->statusenabled = true;
                $model2->keterangan = $diagP['ketdiagnosis'];
                $model2->objectpegawaifk = $this->getPegawai()->id;
                $model2->noregistrasifk = $diagP['noregistrasifk'];
                $model2->tglregistrasi = $getRegistrasi['tglregistrasi'];
                $model2->norec = $model2->generateNewId();
                $model2->objectdiagnosaklaimfk = $iddiagnosa;
                $model2->objectdiagnosapasienfk = $model->norec;
                $model2->objectjenisdiagnosaklaimfk = $diagDP['objectjenisdiagnosaklaimfk'];
                $model2->tglinputdiagnosa = $diagDP['tglinputdiagnosa'];
                $model2->keterangan = $diagP['ketdiagnosis'];
                $model2->iskematian = $diagDP['iskematian'];
                $model2->objectpegawaifk = $this->getPegawai()->id;
                $model2->noregistrasi =  $getRegistrasi['noregistrasi'];
                $model2->iskasusbaru =  isset($diagDP['iskasusbaru']) ? $diagDP['iskasusbaru'] : null;
                $model2->iskasuslama =  isset($diagDP['iskasuslama']) ? $diagDP['iskasuslama'] : null;
                $model2->save();

                if($diagDP['objectjenisdiagnosaklaimfk'] == 1 || $diagDP['objectjenisdiagnosaklaimfk'] == 2){
                    $model2 = new DetailDiagnosaPasien();
                    $model2->norec = $model2->generateNewId();
                    $model2->kdprofile = $this->kdProfile;
                    $model2->statusenabled = true;
                    $model2->keterangan = $diagP['ketdiagnosis'];
                    $model2->objectpegawaifk = $this->getPegawai()->id;
                    $model2->noregistrasifk = $diagP['noregistrasifk'];
                    $model2->tglregistrasi = $getRegistrasi['tglregistrasi'];
                    $model2->norec = $model2->generateNewId();
                    $model2->objectdiagnosaklaimfk = $iddiagnosa;
                    $model2->objectdiagnosapasienfk = $model->norec;
                    $model2->objectjenisdiagnosaklaimfk = $jenisdiagnosaina;
                    $model2->tglinputdiagnosa = $diagDP['tglinputdiagnosa'];
                    $model2->keterangan = $diagP['ketdiagnosis'];
                    $model2->iskematian = $diagDP['iskematian'];
                    $model2->objectpegawaifk = $this->getPegawai()->id;
                    $model2->noregistrasi =  $getRegistrasi['noregistrasi'];
                    $model2->iskasusbaru =  isset($diagDP['iskasusbaru']) ? $diagDP['iskasusbaru'] : null;
                    $model2->iskasuslama =  isset($diagDP['iskasuslama']) ? $diagDP['iskasuslama'] : null;
                    $model2->save();
                }
            }

            $this->LOGGING(
                'Tambah Diagnosa',
                $model->norec,
                'diagnosapasien_t',
                'Tambah Diagnosa ' . ' pada Pasien ' .
                    $r['pasien']['namapasien'] ?? null . ' (' . $r['pasien']['nocm'] ?? null . ') - ' . $r['pasien']['noregistrasi'] ?? null
            );


            DB::commit();
            $objetoRequest = new \Illuminate\Http\Request();
            $objetoRequest['noregistrasi'] = $getRegistrasi['noregistrasi'];
            $ihs = app('App\Http\Controllers\Bridging\SATUSEHATCtrl')->Condition($objetoRequest, true);
            $result = [
                'message' => 'Simpan ICD 10',
                'result' => $model,
                'Condition' => $ihs,
                'status' => 200
            ];
        } catch (Exception $e) {
            DB::rollBack();
            $result = [
                'message' => 'Simpan Gagal',
                'result' => $e->getMessage() . $e->getLine(),
                'status' => 400
            ];
        }

        return $this->respond($result['result'], $result['status'], $result['message']);
    }

    public function saveDiagnosaPasienKlaimPrimary(Request $r)
    {

        DB::beginTransaction();

        try {
            $data = DetailDiagnosaPasien::where('norec', '=', $r['norec_ddp'])->first();

            if($data->objectjenisdiagnosaklaimfk == 1 || $data->objectjenisdiagnosaklaimfk == 2){
                $ddp = DetailDiagnosaPasien::where('noregistrasi', '=', $data->noregistrasi)->whereNotIn('objectjenisdiagnosaklaimfk', [7,8])->update([
                    'objectjenisdiagnosaklaimfk' => 2,
                ]);
        
                $ddp1 = DetailDiagnosaPasien::where('norec', '=', $r['norec_ddp'])->first();
                $ddp1->objectjenisdiagnosaklaimfk = 1;
                $ddp1->save();

                $ddp2 = DetailDiagnosaPasien::where('noregistrasi', '=', $data->noregistrasi)->whereIn('objectjenisdiagnosaklaimfk', [7,8])->update([
                    'objectjenisdiagnosaklaimfk' => 8,
                ]);
    
                $ddp3 = DetailDiagnosaPasien::where('noregistrasi', '=', $data->noregistrasi)->where('objectdiagnosaklaimfk', '=', $ddp1->objectdiagnosaklaimfk)->where('objectjenisdiagnosaklaimfk', '=', 8)->first();
                $ddp3->objectjenisdiagnosaklaimfk = 7;
                $ddp3->save();
            } else{
                $ddp2 = DetailDiagnosaPasien::where('noregistrasi', '=', $data->noregistrasi)->whereIn('objectjenisdiagnosaklaimfk', [7,8])->update([
                    'objectjenisdiagnosaklaimfk' => 8,
                ]);

                $ddp3 = DetailDiagnosaPasien::where('norec', '=', $r['norec_ddp'])->first();
                $ddp3->objectjenisdiagnosaklaimfk = 7;
                $ddp3->save();
            }
            

            


            DB::commit();
            $result = [
                'message' => 'Simpan Berhasil',
                'result' => 'Simpan Berhasil',
                'status' => 200
            ];
        } catch (Exception $e) {
            DB::rollBack();
            $result = [
                'message' => 'Simpan Gagal',
                'result' => $e->getMessage() . $e->getLine(),
                'status' => 400
            ];
        }

        return $this->respond($result['result'], $result['status'], $result['message']);
    }

    public function saveMOI(Request $r)
    {

        DB::beginTransaction();

        $diagP = $r['diagnosapasien'];

        try {
            $model = PasienDaftar::where('norec', $diagP['norec'])->first();
            $model->moi = $diagP['moi'];
            $model->save();

            DB::commit();
            $result = [
                'message' => 'Simpan MOI',
                'result' => $model,
                'status' => 200
            ];
        } catch (Exception $e) {
            DB::rollBack();
            $result = [
                'message' => 'Simpan Gagal',
                'result' => $e->getMessage() . $e->getLine(),
                'status' => 400
            ];
        }

        return $this->respond($result['result'], $result['status'], $result['message']);
    }

    public function saveMoreDiagnosaPasien(Request $r)
    {

        DB::beginTransaction();
        try {
            $getRegistrasi = AntrianPasienDiperiksa::where('norec', $r['noregistrasifk'])->first();
            $pasien = DB::table('pasiendaftar_t as pd')
                ->join('pasien_m as ps', 'ps.id', 'pd.nocmfk')
                ->select('ps.*')
                ->where('pd.noregistrasi', $getRegistrasi['noregistrasi'])
                ->where('pd.kdprofile', $this->kdProfile)
                ->where('pd.statusenabled', true)
                ->where('ps.statusenabled', true)
                ->first();
            foreach ($r['diagnosis'] as $dataDiagnosa) {
                if (isset($dataDiagnosa['jenisDiagnosa'])) {
                    if ($dataDiagnosa['norecDiagnosa'] == '') {
                        $model = new DiagnosaPasien();
                        $model->norec = $model->generateNewId();
                        $model->kdprofile = $this->kdProfile;
                        $model->statusenabled = true;
                    } else {
                        $model = DiagnosaPasien::where('norec', $dataDiagnosa['norecDiagnosa'])->first();
                        DetailDiagnosaPasien::where('objectdiagnosapasienfk', $dataDiagnosa['norecDiagnosa'])->delete();
                    }
                    $model->noregistrasifk = $r['noregistrasifk'];
                    $model->ketdiagnosis = isset($dataDiagnosa['ketDiagnosaDok']) ? $dataDiagnosa['ketDiagnosaDok'] : '';
                    $model->tglregistrasi = $getRegistrasi['tglregistrasi'];
                    $model->tglpendaftaran = $getRegistrasi['tglregistrasi'];
                    $model->iskasusbaru = null;
                    $model->iskasuslama = null;
                    $model->save();
                    $model2 = new DetailDiagnosaPasien();
                    $model2->norec = $model2->generateNewId();
                    $model2->kdprofile = $this->kdProfile;
                    $model2->statusenabled = true;
                    $model2->objectpegawaifk = $this->getPegawai()->id;
                    $model2->noregistrasifk = $r['noregistrasifk'];
                    $model2->tglregistrasi = $getRegistrasi['tglregistrasi'];
                    $model2->norec = $model2->generateNewId();
                    $model2->objectdiagnosafk = isset($dataDiagnosa['diagnosaIcd10']) && is_array($dataDiagnosa['diagnosaIcd10']) ? $dataDiagnosa['diagnosaIcd10']['value'] : null;
                    $model2->objectdiagnosapasienfk = $model->norec;
                    $model2->objectjenisdiagnosafk = $dataDiagnosa['jenisDiagnosa']['value'];
                    $model2->tglinputdiagnosa = date('Y-m-d H:i:s');
                    $model2->keterangan =  isset($dataDiagnosa['ketDiagnosaDok']) ? $dataDiagnosa['ketDiagnosaDok'] : '';
                    $model2->objectpegawaifk = $this->getPegawai()->id;
                    $model2->noregistrasi =  $getRegistrasi['noregistrasi'];
                    $model2->iskasusbaru = null;
                    $model2->iskasuslama = null;
                    $model2->save();

                    $this->LOGGING(
                        'Tambah Diagnosa',
                        $model->norec,
                        'diagnosapasien_t',
                        'Tambah Diagnosa ' . ' pada Pasien ' .
                            $pasien->namapasien . ' (' . $pasien->nocm . ') - ' . $getRegistrasi['noregistrasi'] ?? null
                    );
                }
            }

            DB::commit();
            $objetoRequest = new \Illuminate\Http\Request();
            $objetoRequest['noregistrasi'] = $getRegistrasi['noregistrasi'];
            $ihs = app('App\Http\Controllers\Bridging\SATUSEHATCtrl')->Condition($objetoRequest, true);
            $result = [
                'message' => 'Simpan ICD 10',
                'result' => $model,
                'Condition' => $ihs,
                'status' => 201
            ];
        } catch (Exception $e) {
            DB::rollBack();
            $result = [
                'message' => 'Gagal Simpan ICD 10',
                'result' => $e->getMessage() . $e->getLine(),
                'status' => 400
            ];
        }

        return $this->respond($result['result'], $result['status'], $result['message']);
    }

    public function saveMoreDiagnosaTindakanPasien(Request $r)
    {
        DB::beginTransaction();
        try {
            // $diagP = $r['diagnosapasien'];
            // $diagDP = $r['detaildiagnosapasien'];
            $getRegistrasi = AntrianPasienDiperiksa::where('norec', $r['noregistrasifk'])->first();
            foreach ($r['diagnosaPerawat'] as $diagPer) {
                if ($diagPer['norecDiagnosa9'] == '') {
                    $model = new DiagnosaTindakanPasien();
                    $model->norec = $model->generateNewId();
                    $model->kdprofile = $this->kdProfile;
                    $model->statusenabled = true;
                } else {
                    $model = DiagnosaTindakanPasien::where('norec', $diagPer['norecDiagnosa9'])->first();
                    DetailDiagnosaTindakanPasien::where('objectdiagnosatindakanpasienfk', $diagPer['norecDiagnosa9'])->delete();
                }
                $model->objectpasienfk = $r['noregistrasifk'];
                $model->tglpendaftaran = $getRegistrasi['tglregistrasi'];
                $model->save();
                $model2 = new DetailDiagnosaTindakanPasien();
                $model2->norec = $model2->generateNewId();
                $model2->kdprofile = $this->kdProfile;
                $model2->statusenabled = true;
                $model2->objectpegawaifk = $this->getPegawai()->id;
                $model2->noregistrasifk = $r['noregistrasifk'];
                $model2->objectdiagnosatindakanfk = isset($diagPer['diagnosaIcd9']) && is_array($diagPer['diagnosaIcd9']) ? $diagPer['diagnosaIcd9']['value'] : null;
                $model2->objectdiagnosatindakanpasienfk = $model->norec;
                $model2->tglinputdiagnosa = date('Y-m-d H:i:s');
                $model2->keterangantindakan = isset($diagPer['ketTindakanDokter']) ? $diagPer['ketTindakanDokter'] : '';
                $model2->objectpegawaifk = $this->getPegawai()->id;
                $model2->noregistrasi =  $getRegistrasi['noregistrasi'];
                $model2->save();
            }

            DB::commit();
            $result = [
                'message' => 'Simpan ICD 9',
                'result' => $model2,
                'status' => 201
            ];
        } catch (Exception $e) {
            DB::rollBack();
            $result = [
                'message' => 'Gagal Simpan ICD 9',
                'result' => $e->getMessage(),
                'status' => 400
            ];
        }
        return $this->respond($result['result'], $result['status'], $result['message']);
    }

    public function saveDiagnosaTindakanPasien(Request $r)
    {
        DB::beginTransaction();
        try {
            $diagP = $r['diagnosapasien'];
            $diagDP = $r['detaildiagnosapasien'];
            if ($diagP['norec'] == '') {
                $model = new DiagnosaTindakanPasien();
                $model->norec = $model->generateNewId();
                $model->kdprofile = $this->kdProfile;
                $model->statusenabled = true;
            } else {
                $model = DiagnosaTindakanPasien::where('norec', $diagP['norec'])->first();
                DetailDiagnosaTindakanPasien::where('objectdiagnosatindakanpasienfk', $diagP['norec'])->delete();
            }
            $model->objectpasienfk = $diagP['noregistrasifk'];
            $model->tglpendaftaran = $diagP['tglregistrasi'];
            $model->save();
            $model2 = new DetailDiagnosaTindakanPasien();
            $model2->norec = $model2->generateNewId();
            $model2->kdprofile = $this->kdProfile;
            $model2->statusenabled = true;
            $model2->objectpegawaifk = $this->getPegawai()->id;
            $model2->noregistrasifk = $diagP['noregistrasifk'];
            $model2->objectdiagnosatindakanfk = $diagDP['objectdiagnosatindakanfk'];
            $model2->objectdiagnosatindakanpasienfk = $model->norec;
            $model2->tglinputdiagnosa = $diagDP['tglinputdiagnosa'];
            $model2->keterangantindakan = $diagP['keterangantindakan'];
            $model2->objectpegawaifk = $this->getPegawai()->id;
            $model2->noregistrasi =  $diagDP['noregistrasi'];
            $model2->save();

            DB::commit();
            $getRegistrasi = AntrianPasienDiperiksa::where('norec', $diagP['noregistrasifk'])->first();
            $objetoRequest = new \Illuminate\Http\Request();
            $objetoRequest['noregistrasi'] = $getRegistrasi->noregistrasi;
            $ihs = app('App\Http\Controllers\Bridging\SATUSEHATCtrl')->Procedure($objetoRequest, true);
            $result = [
                'message' => 'Simpan ICD 9',
                'result' => $model,
                'status' => 200
            ];
        } catch (Exception $e) {
            DB::rollBack();
            $result = [
                'message' => 'Simpan Gagal',
                'result' => $e->getMessage(). " " .$e->getLine(),
                'status' => 400
            ];
        }
        return $this->respond($result['result'], $result['status'], $result['message']);
    }

    public function saveDiagnosaTindakanPasienRM(Request $r)
    {
        DB::beginTransaction();
        try {
            $diagP = $r['diagnosapasien'];
            $diagDP = $r['detaildiagnosapasien'];
            if ($diagP['norec'] == '') {
                $model = new DiagnosaTindakanPasien();
                $model->norec = $model->generateNewId();
                $model->kdprofile = $this->kdProfile;
                $model->statusenabled = true;
            } else {
                $model = DiagnosaTindakanPasien::where('norec', $diagP['norec'])->first();
                DetailDiagnosaTindakanPasien::where('objectdiagnosatindakanpasienfk', $diagP['norec'])->delete();
            }
            $model->objectpasienfk = $diagP['noregistrasifk'];
            $model->tglpendaftaran = $diagP['tglregistrasi'];
            $model->save();
            $model2 = new DetailDiagnosaTindakanPasien();
            $model2->norec = $model2->generateNewId();
            $model2->kdprofile = $this->kdProfile;
            $model2->statusenabled = true;
            $model2->objectpegawaifk = $this->getPegawai()->id;
            $model2->noregistrasifk = $diagP['noregistrasifk'];
            $model2->objectdiagnosatindakanrmfk = $diagDP['objectdiagnosatindakanrmfk'];
            $model2->objectdiagnosatindakanpasienfk = $model->norec;
            $model2->tglinputdiagnosa = $diagDP['tglinputdiagnosa'];
            $model2->keterangantindakan = $diagP['keterangantindakan'];
            $model2->objectpegawaifk = $this->getPegawai()->id;
            $model2->noregistrasi =  $diagDP['noregistrasi'];
            $model2->save();

            DB::commit();
            $getRegistrasi = AntrianPasienDiperiksa::where('norec', $diagP['noregistrasifk'])->first();
            $objetoRequest = new \Illuminate\Http\Request();
            $objetoRequest['noregistrasi'] = $getRegistrasi->noregistrasi;
            $ihs = app('App\Http\Controllers\Bridging\SATUSEHATCtrl')->Procedure($objetoRequest, true);
            $result = [
                'message' => 'Simpan ICD 9',
                'result' => $model,
                'status' => 200
            ];
        } catch (Exception $e) {
            DB::rollBack();
            $result = [
                'message' => 'Simpan Gagal',
                'result' => $e->getMessage(),
                'status' => 400
            ];
        }
        return $this->respond($result['result'], $result['status'], $result['message']);
    }

    public function saveDiagnosaTindakanPasienKlaim(Request $r)
    {
        DB::beginTransaction();
        try {
            $diagP = $r['diagnosapasien'];
            $diagDP = $r['detaildiagnosapasien'];

            $cekkodediag = DiagnosaTindakan::where('kddiagnosatindakan', '=', $diagDP['objectdiagnosatindakanklaimfk'])->where('statusenabled', '=', true)->first();
            if (empty($cekkodediag)) {
                $newId = DiagnosaTindakan::max('id');
                $newId = $newId + 1;
                $dataDiagnosa = new DiagnosaTindakan();
                $dataDiagnosa->id = $newId;
                $dataDiagnosa->kdprofile = $this->kdProfile;
                $dataDiagnosa->statusenabled = true;
                $dataDiagnosa->namaexternal = $diagDP['namadiagnosatindakan'];
                $dataDiagnosa->norec = $newId;
                $dataDiagnosa->reportdisplay = $diagDP['namadiagnosatindakan'];
                $dataDiagnosa->kddiagnosatindakan = $diagDP['objectdiagnosatindakanklaimfk'];
                $dataDiagnosa->namadiagnosatindakan = $diagDP['namadiagnosatindakan'];
                $dataDiagnosa->qdiagnosatindakan = $newId;
                $dataDiagnosa->save();
                $iddiagnosa = $dataDiagnosa->id;
            } else {
                $cekkodediag2 = DiagnosaTindakan::where('kddiagnosatindakan', '=', $diagDP['objectdiagnosatindakanklaimfk'])->where('namadiagnosatindakan', '=', $diagDP['namadiagnosatindakan'])->where('statusenabled', '=', true)->first();
                if (empty($cekkodediag2)) {
                    $cekkodediag->namaexternal = $diagDP['namadiagnosatindakan'];
                    $cekkodediag->reportdisplay = $diagDP['namadiagnosatindakan'];
                    $cekkodediag->namadiagnosatindakan = $diagDP['namadiagnosatindakan'];
                    $cekkodediag->save();
                    $iddiagnosa = $cekkodediag->id;
                } else {
                    $iddiagnosa = $cekkodediag2->id;
                }
            }

            $cek = DetailDiagnosaTindakanPasien::where('objectdiagnosatindakanklaimfk', $iddiagnosa)->where('noregistrasifk', $diagP['noregistrasifk'])->first();

            if ($diagP['norec'] == '') {
                $model = new DiagnosaTindakanPasien();
                $model->norec = $model->generateNewId();
                $model->kdprofile = $this->kdProfile;
                $model->statusenabled = true;
            } else {
                $model = DiagnosaTindakanPasien::where('norec', $diagP['norec'])->first();
                if(empty($cek)){
                    DetailDiagnosaTindakanPasien::where('objectdiagnosatindakanpasienfk', $diagP['norec'])->delete();
                }
            }
            $model->objectpasienfk = $diagP['noregistrasifk'];
            $model->tglpendaftaran = $diagP['tglregistrasi'];
            $model->save();

            if(empty($cek)){
                if($diagDP['objectjenisdiagnosatindakanklaimfk'] == 9){
                    $model2 = new DetailDiagnosaTindakanPasien();
                    $model2->norec = $model2->generateNewId();
                    $model2->kdprofile = $this->kdProfile;
                    $model2->statusenabled = true;
                    $model2->objectpegawaifk = $this->getPegawai()->id;
                    $model2->noregistrasifk = $diagP['noregistrasifk'];
                    $model2->objectdiagnosatindakanklaimfk = $iddiagnosa;
                    $model2->objectdiagnosatindakanpasienfk = $model->norec;
                    $model2->tglinputdiagnosa = $diagDP['tglinputdiagnosa'];
                    $model2->objectjenisdiagnosatindakanklaimfk = $diagDP['objectjenisdiagnosatindakanklaimfk'];
                    $model2->keterangantindakan = $diagP['keterangantindakan'];
                    $model2->objectpegawaifk = $this->getPegawai()->id;
                    $model2->noregistrasi =  $diagDP['noregistrasi'];
                    $model2->save();
                }

                $model2 = new DetailDiagnosaTindakanPasien();
                $model2->norec = $model2->generateNewId();
                $model2->kdprofile = $this->kdProfile;
                $model2->statusenabled = true;
                $model2->objectpegawaifk = $this->getPegawai()->id;
                $model2->noregistrasifk = $diagP['noregistrasifk'];
                $model2->objectdiagnosatindakanklaimfk = $iddiagnosa;
                $model2->objectdiagnosatindakanpasienfk = $model->norec;
                $model2->tglinputdiagnosa = $diagDP['tglinputdiagnosa'];
                $model2->objectjenisdiagnosatindakanklaimfk = 10;
                $model2->keterangantindakan = $diagP['keterangantindakan'];
                $model2->objectpegawaifk = $this->getPegawai()->id;
                $model2->noregistrasi =  $diagDP['noregistrasi'];
                $model2->save();
                
            }

            DB::commit();
            $getRegistrasi = AntrianPasienDiperiksa::where('norec', $diagP['noregistrasifk'])->first();
            $objetoRequest = new \Illuminate\Http\Request();
            $objetoRequest['noregistrasi'] = $getRegistrasi->noregistrasi;
            $ihs = app('App\Http\Controllers\Bridging\SATUSEHATCtrl')->Procedure($objetoRequest, true);
            $result = [
                'message' => 'Simpan ICD 9',
                'result' => $model,
                'status' => 200
            ];
        } catch (Exception $e) {
            DB::rollBack();
            $result = [
                'message' => 'Simpan Gagal',
                'result' => $e->getMessage(). ' ' .$e->getLine(),
                'status' => 400
            ];
        }
        return $this->respond($result['result'], $result['status'], $result['message']);
    }

    public function saveDiagnosaMorfologiPasienRM(Request $r)
    {
        DB::beginTransaction();
        try {
            $diagP = $r['diagnosapasien'];
            $diagDP = $r['detaildiagnosapasien'];
            if ($diagP['norec'] == '') {
                $model = new DiagnosaMorfologiPasien();
                $model->norec = $model->generateNewId();
                $model->kdprofile = $this->kdProfile;
                $model->statusenabled = true;
            } else {
                $model = DiagnosaMorfologiPasien::where('norec', $diagP['norec'])->first();
                DetailDiagnosaMorfologiPasien::where('objectdiagnosamorfologipasienfk', $diagP['norec'])->delete();
            }
            $model->objectpasienfk = $diagP['noregistrasifk'];
            $model->tglpendaftaran = $diagP['tglregistrasi'];
            $model->save();
            $model2 = new DetailDiagnosaMorfologiPasien();
            $model2->norec = $model2->generateNewId();
            $model2->kdprofile = $this->kdProfile;
            $model2->statusenabled = true;
            $model2->objectpegawaifk = $this->getPegawai()->id;
            $model2->noregistrasifk = $diagP['noregistrasifk'];
            $model2->objectdiagnosamorfologifk = $diagDP['objectdiagnosamorfologifk'];
            $model2->objectdiagnosamorfologipasienfk = $model->norec;
            $model2->tglinputdiagnosa = $diagDP['tglinputdiagnosa'];
            $model2->objectpegawaifk = $this->getPegawai()->id;
            $model2->noregistrasi =  $diagDP['noregistrasi'];
            $model2->save();

            DB::commit();
            $getRegistrasi = AntrianPasienDiperiksa::where('norec', $diagP['noregistrasifk'])->first();
            $result = [
                'message' => 'Simpan ICD-O',
                'result' => $model,
                'status' => 200
            ];
        } catch (Exception $e) {
            DB::rollBack();
            $result = [
                'message' => 'Simpan Gagal',
                'result' => $e->getMessage(),
                'status' => 400
            ];
        }
        return $this->respond($result['result'], $result['status'], $result['message']);
    }


    public function riwayatDiagnosaIX(Request $request)
    {
        $data = DB::table('pasiendaftar_t as pd')
            ->select(
                'pd.noregistrasi',
                'pd.tglregistrasi',
                'apd.objectruanganfk',
                'ru.namaruangan',
                'apd.norec as norec_apd',
                'ddt.objectdiagnosatindakanfk',
                'dt.kddiagnosatindakan',
                'dt.namadiagnosatindakan',
                'dtp.norec as norec_diagnosapasien',
                'ddt.norec as norec_detaildpasien',
                'dt.kddiagnosatindakan',
                'dt.namadiagnosatindakan',
                'ddt.keterangantindakan',
                'pg.namalengkap',
                'ddt.tglinputdiagnosa'
            )
            ->join('pasien_m as ps', 'ps.id', '=', 'pd.nocmfk')
            ->join('antrianpasiendiperiksa_t as apd', 'apd.noregistrasifk', '=', 'pd.norec')
            ->join('ruangan_m as ru', 'ru.id', '=', 'apd.objectruanganfk')
            ->join('diagnosatindakanpasien_t as dtp', 'dtp.objectpasienfk', '=', 'apd.norec')
            ->join('detaildiagnosatindakanpasien_t as ddt', 'ddt.objectdiagnosatindakanpasienfk', '=', 'dtp.norec')
            ->join('diagnosatindakan_m as dt', 'dt.id', '=', 'ddt.objectdiagnosatindakanfk')
            ->leftjoin('pegawai_m as pg', 'pg.id', '=', 'ddt.objectpegawaifk')
            ->where('pd.kdprofile', $this->kdProfile);
        if (isset($request['norec_pd']) && $request['norec_pd'] != "" && $request['norec_pd'] != "undefined") {
            $data = $data->where('pd.norec', '=', $request['norec_pd']);
        };

        if (isset($request['nocmfk']) && $request['nocmfk'] != "" && $request['nocmfk'] != "undefined") {
            $data = $data->where('ps.id', '=', $request['nocmfk']);
        };
        $data = $data->get();

        $result = array(
            'data' => $data,
            'message' => '@epic',
        );
        return $this->respond($result);
    }

    public function riwayatDiagnosaO(Request $request)
    {
        $data = DB::table('pasiendaftar_t as pd')
            ->select(
                'pd.noregistrasi',
                'pd.tglregistrasi',
                'apd.objectruanganfk',
                'ru.namaruangan',
                'apd.norec as norec_apd',
                'ddt.objectdiagnosamorfologifk',
                'dtp.norec as norec_diagnosapasien',
                'ddt.norec as norec_detaildpasien',
                'dt.kddiagnosamorfologi',
                'dt.diagnosamorfologi',
                'pg.namalengkap',
                'ddt.tglinputdiagnosa'
            )
            ->join('pasien_m as ps', 'ps.id', '=', 'pd.nocmfk')
            ->join('antrianpasiendiperiksa_t as apd', 'apd.noregistrasifk', '=', 'pd.norec')
            ->join('ruangan_m as ru', 'ru.id', '=', 'apd.objectruanganfk')
            ->join('diagnosamorfologipasien_t as dtp', 'dtp.objectpasienfk', '=', 'apd.norec')
            ->join('detaildiagnosamorfologipasien_t as ddt', 'ddt.objectdiagnosamorfologipasienfk', '=', 'dtp.norec')
            ->join('diagnosamorfologi_m as dt', 'dt.id', '=', 'ddt.objectdiagnosamorfologifk')
            ->leftjoin('pegawai_m as pg', 'pg.id', '=', 'ddt.objectpegawaifk')
            ->where('pd.kdprofile', $this->kdProfile);
        if (isset($request['norec_pd']) && $request['norec_pd'] != "" && $request['norec_pd'] != "undefined") {
            $data = $data->where('pd.norec', '=', $request['norec_pd']);
        };

        if (isset($request['nocmfk']) && $request['nocmfk'] != "" && $request['nocmfk'] != "undefined") {
            $data = $data->where('ps.id', '=', $request['nocmfk']);
        };
        $data = $data->get();

        $result = array(
            'data' => $data,
            'message' => '@epic',
        );
        return $this->respond($result);
    }

    public function riwayatDiagnosaIXRM(Request $request)
    {
        $data = DB::table('pasiendaftar_t as pd')
            ->select(
                'pd.noregistrasi',
                'pd.tglregistrasi',
                'apd.objectruanganfk',
                'ru.namaruangan',
                'apd.norec as norec_apd',
                'ddt.objectdiagnosatindakanrmfk',
                'dt.kddiagnosatindakan',
                'dt.namadiagnosatindakan',
                'dtp.norec as norec_diagnosapasien',
                'ddt.norec as norec_detaildpasien',
                'dt.kddiagnosatindakan',
                'dt.namadiagnosatindakan',
                'ddt.keterangantindakan',
                'pg.namalengkap',
                'ddt.tglinputdiagnosa'
            )
            ->join('pasien_m as ps', 'ps.id', '=', 'pd.nocmfk')
            ->join('antrianpasiendiperiksa_t as apd', 'apd.noregistrasifk', '=', 'pd.norec')
            ->join('ruangan_m as ru', 'ru.id', '=', 'apd.objectruanganfk')
            ->join('diagnosatindakanpasien_t as dtp', 'dtp.objectpasienfk', '=', 'apd.norec')
            ->join('detaildiagnosatindakanpasien_t as ddt', 'ddt.objectdiagnosatindakanpasienfk', '=', 'dtp.norec')
            ->join('diagnosatindakan_m as dt', 'dt.id', '=', 'ddt.objectdiagnosatindakanrmfk')
            ->leftjoin('pegawai_m as pg', 'pg.id', '=', 'ddt.objectpegawaifk')
            ->where('pd.kdprofile', $this->kdProfile);
        if (isset($request['norec_pd']) && $request['norec_pd'] != "" && $request['norec_pd'] != "undefined") {
            $data = $data->where('pd.norec', '=', $request['norec_pd']);
        };

        if (isset($request['nocmfk']) && $request['nocmfk'] != "" && $request['nocmfk'] != "undefined") {
            $data = $data->where('ps.id', '=', $request['nocmfk']);
        };
        $data = $data->orderBy('ddt.created_at', 'asc');
        $data = $data->get();

        $result = array(
            'data' => $data,
            'message' => '@epic',
        );
        return $this->respond($result);
    }

    public function riwayatDiagnosaIXKlaim(Request $request)
    {
        $data = DB::table('pasiendaftar_t as pd')
            ->select(
                'pd.noregistrasi',
                'pd.tglregistrasi',
                'apd.objectruanganfk',
                'ru.namaruangan',
                'apd.norec as norec_apd',
                'ddt.objectdiagnosatindakanklaimfk',
                'dt.kddiagnosatindakan',
                'dt.namadiagnosatindakan',
                'dtp.norec as norec_diagnosapasien',
                'ddt.norec as norec_detaildpasien',
                'dt.kddiagnosatindakan',
                'dt.namadiagnosatindakan',
                'ddt.keterangantindakan',
                'pg.namalengkap',
                'ddt.tglinputdiagnosa',
                'ddt.objectjenisdiagnosatindakanklaimfk'
            )
            ->join('pasien_m as ps', 'ps.id', '=', 'pd.nocmfk')
            ->join('antrianpasiendiperiksa_t as apd', 'apd.noregistrasifk', '=', 'pd.norec')
            ->join('ruangan_m as ru', 'ru.id', '=', 'apd.objectruanganfk')
            ->join('diagnosatindakanpasien_t as dtp', 'dtp.objectpasienfk', '=', 'apd.norec')
            ->join('detaildiagnosatindakanpasien_t as ddt', 'ddt.objectdiagnosatindakanpasienfk', '=', 'dtp.norec')
            ->join('diagnosatindakan_m as dt', 'dt.id', '=', 'ddt.objectdiagnosatindakanklaimfk')
            ->leftjoin('pegawai_m as pg', 'pg.id', '=', 'ddt.objectpegawaifk')
            ->where('pd.kdprofile', $this->kdProfile)
            ->whereRaw("(ddt.objectjenisdiagnosatindakanklaimfk is null or ddt.objectjenisdiagnosatindakanklaimfk = 9)");
        if (isset($request['norec_pd']) && $request['norec_pd'] != "" && $request['norec_pd'] != "undefined") {
            $data = $data->where('pd.norec', '=', $request['norec_pd']);
        };

        if (isset($request['nocmfk']) && $request['nocmfk'] != "" && $request['nocmfk'] != "undefined") {
            $data = $data->where('ps.id', '=', $request['nocmfk']);
        };
        $data = $data->orderBy('ddt.tglinputdiagnosa', 'asc');
        $data = $data->get();

        $result = array(
            'data' => $data,
            'message' => '@epic',
        );
        return $this->respond($result);
    }

    public function riwayatDiagnosaIXKlaimINA(Request $request)
    {
        $data = DB::table('pasiendaftar_t as pd')
            ->select(
                'pd.noregistrasi',
                'pd.tglregistrasi',
                'apd.objectruanganfk',
                'ru.namaruangan',
                'apd.norec as norec_apd',
                'ddt.objectdiagnosatindakanklaimfk',
                'dt.kddiagnosatindakan',
                'dt.namadiagnosatindakan',
                'dtp.norec as norec_diagnosapasien',
                'ddt.norec as norec_detaildpasien',
                'dt.kddiagnosatindakan',
                'dt.namadiagnosatindakan',
                'ddt.keterangantindakan',
                'pg.namalengkap',
                'ddt.tglinputdiagnosa',
                'ddt.objectjenisdiagnosatindakanklaimfk'
            )
            ->join('pasien_m as ps', 'ps.id', '=', 'pd.nocmfk')
            ->join('antrianpasiendiperiksa_t as apd', 'apd.noregistrasifk', '=', 'pd.norec')
            ->join('ruangan_m as ru', 'ru.id', '=', 'apd.objectruanganfk')
            ->join('diagnosatindakanpasien_t as dtp', 'dtp.objectpasienfk', '=', 'apd.norec')
            ->join('detaildiagnosatindakanpasien_t as ddt', 'ddt.objectdiagnosatindakanpasienfk', '=', 'dtp.norec')
            ->join('diagnosatindakan_m as dt', 'dt.id', '=', 'ddt.objectdiagnosatindakanklaimfk')
            ->leftjoin('pegawai_m as pg', 'pg.id', '=', 'ddt.objectpegawaifk')
            ->where('pd.kdprofile', $this->kdProfile)
            ->where("ddt.objectjenisdiagnosatindakanklaimfk", '=', 10);
        if (isset($request['norec_pd']) && $request['norec_pd'] != "" && $request['norec_pd'] != "undefined") {
            $data = $data->where('pd.norec', '=', $request['norec_pd']);
        };

        if (isset($request['nocmfk']) && $request['nocmfk'] != "" && $request['nocmfk'] != "undefined") {
            $data = $data->where('ps.id', '=', $request['nocmfk']);
        };
        $data = $data->orderBy('ddt.tglinputdiagnosa', 'asc');
        $data = $data->get();

        $result = array(
            'data' => $data,
            'message' => '@epic',
        );
        return $this->respond($result);
    }

    public function riwayatDiagnosaX(Request $request)
    {

        $data = DB::table('pasiendaftar_t as pd')
            ->select(
                'pd.noregistrasi',
                'pd.tglregistrasi',
                'apd.objectruanganfk',
                'ru.namaruangan',
                'apd.norec as norec_apd',
                'ddp.objectdiagnosafk',
                'dg.kddiagnosa',
                'dg.namadiagnosa',
                'ddp.tglinputdiagnosa',
                'ddp.objectjenisdiagnosafk',
                'jd.jenisdiagnosa',
                'dp.norec as norec_diagnosapasien',
                'dg.id as id_diagnosa',
                'ddp.norec as norec_detaildpasien',
                'ddp.tglinputdiagnosa',
                'pg.namalengkap',
                'dp.ketdiagnosis',
                'ddp.keterangan',
                'dp.iskasusbaru',
                'dp.iskasuslama'
            )
            ->join('pasien_m as ps', 'ps.id', '=', 'pd.nocmfk')
            ->join('antrianpasiendiperiksa_t as apd', 'apd.noregistrasifk', '=', 'pd.norec')
            ->join('ruangan_m as ru', 'ru.id', '=', 'apd.objectruanganfk')
            ->join('diagnosapasien_t as dp', 'dp.noregistrasifk', '=', 'apd.norec')
            ->join('detaildiagnosapasien_t as ddp', 'ddp.objectdiagnosapasienfk', '=', 'dp.norec')
            ->join('diagnosa_m as dg', 'dg.id', '=', 'ddp.objectdiagnosafk')
            ->join('jenisdiagnosa_m as jd', 'jd.id', '=', 'ddp.objectjenisdiagnosafk')
            ->leftjoin('pegawai_m as pg', 'pg.id', '=', 'ddp.objectpegawaifk')
            ->where('pd.kdprofile', $this->kdProfile)
            ->orderby('ddp.tglinputdiagnosa', 'asc');
        if (isset($request['norec_pd']) && $request['norec_pd'] != "" && $request['norec_pd'] != "undefined") {
            $data = $data->where('pd.norec', '=', $request['norec_pd']);
        };

        if (isset($request['nocmfk']) && $request['nocmfk'] != "" && $request['nocmfk'] != "undefined") {
            $data = $data->where('ps.id', '=', $request['nocmfk']);
        };

        $data = $data->get();

        $result = array(
            'data' => $data,
            'message' => '@epic',
        );
        return $this->respond($result);
    }

    public function riwayatMOI(Request $request)
    {

        $data = DB::table('pasiendaftar_t as pd')->where('pd.norec', '=', $request['norec_pd'])->first();

        $result = array(
            'data' => $data,
            'message' => '@epic',
        );
        return $this->respond($result);
    }

    public function riwayatDiagnosaXRM(Request $request)
    {

        $data = DB::table('pasiendaftar_t as pd')
            ->select(
                'pd.noregistrasi',
                'pd.tglregistrasi',
                'apd.objectruanganfk',
                'ru.namaruangan',
                'apd.norec as norec_apd',
                'ddp.objectdiagnosarmfk',
                'dg.kddiagnosa',
                'dg.namadiagnosa',
                'ddp.tglinputdiagnosa',
                'ddp.objectjenisdiagnosarmfk',
                'jd.jenisdiagnosa',
                'dp.norec as norec_diagnosapasien',
                'dg.id as id_diagnosa',
                'ddp.norec as norec_detaildpasien',
                'ddp.tglinputdiagnosa',
                'pg.namalengkap',
                'dp.ketdiagnosis',
                'ddp.keterangan',
                'ddp.iskematian',
                'ddp.ismoi',
                'ddp.ismasuk',
                'dp.iskasusbaru',
                'dp.iskasuslama'
            )
            ->join('pasien_m as ps', 'ps.id', '=', 'pd.nocmfk')
            ->join('antrianpasiendiperiksa_t as apd', 'apd.noregistrasifk', '=', 'pd.norec')
            ->join('ruangan_m as ru', 'ru.id', '=', 'apd.objectruanganfk')
            ->join('diagnosapasien_t as dp', 'dp.noregistrasifk', '=', 'apd.norec')
            ->join('detaildiagnosapasien_t as ddp', 'ddp.objectdiagnosapasienfk', '=', 'dp.norec')
            ->join('diagnosa_m as dg', 'dg.id', '=', 'ddp.objectdiagnosarmfk')
            ->join('jenisdiagnosa_m as jd', 'jd.id', '=', 'ddp.objectjenisdiagnosarmfk')
            ->leftjoin('pegawai_m as pg', 'pg.id', '=', 'ddp.objectpegawaifk')
            ->where('pd.kdprofile', $this->kdProfile)
            ->orderby('ddp.objectjenisdiagnosarmfk', 'asc');
        if (isset($request['norec_pd']) && $request['norec_pd'] != "" && $request['norec_pd'] != "undefined") {
            $data = $data->where('pd.norec', '=', $request['norec_pd']);
        };

        if (isset($request['nocmfk']) && $request['nocmfk'] != "" && $request['nocmfk'] != "undefined") {
            $data = $data->where('ps.id', '=', $request['nocmfk']);
        };

        if (isset($request['iskematian']) && $request['iskematian'] != "" && $request['iskematian'] != "undefined") {
            $data = $data->where('ddp.iskematian', '=', $request['iskematian']);
        } else{
            $data = $data->whereNull('ddp.iskematian');
        };

        if (isset($request['ismoi']) && $request['ismoi'] != "" && $request['ismoi'] != "undefined") {
            $data = $data->where('ddp.ismoi', '=', $request['ismoi']);
        } else {
            $data = $data->where(function ($query) {
                $query->whereNull('ddp.ismoi')
                        ->orWhere('ddp.ismoi', false);
            });                    
        };
        if (isset($request['ismasuk']) && $request['ismasuk'] != "" && $request['ismasuk'] != "undefined") {
            $data = $data->where('ddp.ismasuk', '=', $request['ismasuk']);
        } else {
            $data = $data->where(function ($query) {
                $query->whereNull('ddp.ismasuk')
                        ->orWhere('ddp.ismasuk', false);
            });                    
        };

        $data = $data->get();

        $result = array(
            'data' => $data,
            'message' => '@epic',
        );
        return $this->respond($result);
    }

    public function riwayatDiagnosaXKlaim(Request $request)
    {

        $data = DB::table('pasiendaftar_t as pd')
            ->select(
                'pd.noregistrasi',
                'pd.tglregistrasi',
                'apd.objectruanganfk',
                'ru.namaruangan',
                'apd.norec as norec_apd',
                'ddp.objectdiagnosaklaimfk',
                'dg.kddiagnosa',
                'dg.namadiagnosa',
                'ddp.tglinputdiagnosa',
                'ddp.objectjenisdiagnosaklaimfk',
                'jd.jenisdiagnosa',
                'dp.norec as norec_diagnosapasien',
                'dg.id as id_diagnosa',
                'ddp.norec as norec_detaildpasien',
                'ddp.tglinputdiagnosa',
                'pg.namalengkap',
                'dp.ketdiagnosis',
                'ddp.keterangan',
                'dp.iskasusbaru',
                'dp.iskasuslama'
            )
            ->join('pasien_m as ps', 'ps.id', '=', 'pd.nocmfk')
            ->join('antrianpasiendiperiksa_t as apd', 'apd.noregistrasifk', '=', 'pd.norec')
            ->join('ruangan_m as ru', 'ru.id', '=', 'apd.objectruanganfk')
            ->join('diagnosapasien_t as dp', 'dp.noregistrasifk', '=', 'apd.norec')
            ->join('detaildiagnosapasien_t as ddp', 'ddp.objectdiagnosapasienfk', '=', 'dp.norec')
            ->join('diagnosa_m as dg', 'dg.id', '=', 'ddp.objectdiagnosaklaimfk')
            ->join('jenisdiagnosa_m as jd', 'jd.id', '=', 'ddp.objectjenisdiagnosaklaimfk')
            ->leftjoin('pegawai_m as pg', 'pg.id', '=', 'ddp.objectpegawaifk')
            ->where('pd.kdprofile', $this->kdProfile)
            ->whereIn('jd.id', [1,2])
            ->orderby('ddp.objectjenisdiagnosaklaimfk', 'asc');
        if (isset($request['norec_pd']) && $request['norec_pd'] != "" && $request['norec_pd'] != "undefined") {
            $data = $data->where('pd.norec', '=', $request['norec_pd']);
        };

        if (isset($request['nocmfk']) && $request['nocmfk'] != "" && $request['nocmfk'] != "undefined") {
            $data = $data->where('ps.id', '=', $request['nocmfk']);
        };

        if (isset($request['iskematian']) && $request['iskematian'] != "" && $request['iskematian'] != "undefined") {
            $data = $data->where('ddp.iskematian', '=', $request['iskematian']);
        } else{
            $data = $data->whereNull('ddp.iskematian');
        };

        $data = $data->get();

        $result = array(
            'data' => $data,
            'message' => '@epic',
        );
        return $this->respond($result);
    }

    public function riwayatDiagnosaXKlaimINA(Request $request)
    {

        $data = DB::table('pasiendaftar_t as pd')
            ->select(
                'pd.noregistrasi',
                'pd.tglregistrasi',
                'apd.objectruanganfk',
                'ru.namaruangan',
                'apd.norec as norec_apd',
                'ddp.objectdiagnosaklaimfk',
                'dg.kddiagnosa',
                'dg.namadiagnosa',
                'ddp.tglinputdiagnosa',
                'ddp.objectjenisdiagnosaklaimfk',
                'jd.jenisdiagnosa',
                'dp.norec as norec_diagnosapasien',
                'dg.id as id_diagnosa',
                'ddp.norec as norec_detaildpasien',
                'ddp.tglinputdiagnosa',
                'pg.namalengkap',
                'dp.ketdiagnosis',
                'ddp.keterangan',
                'dp.iskasusbaru',
                'dp.iskasuslama'
            )
            ->join('pasien_m as ps', 'ps.id', '=', 'pd.nocmfk')
            ->join('antrianpasiendiperiksa_t as apd', 'apd.noregistrasifk', '=', 'pd.norec')
            ->join('ruangan_m as ru', 'ru.id', '=', 'apd.objectruanganfk')
            ->join('diagnosapasien_t as dp', 'dp.noregistrasifk', '=', 'apd.norec')
            ->join('detaildiagnosapasien_t as ddp', 'ddp.objectdiagnosapasienfk', '=', 'dp.norec')
            ->join('diagnosa_m as dg', 'dg.id', '=', 'ddp.objectdiagnosaklaimfk')
            ->join('jenisdiagnosa_m as jd', 'jd.id', '=', 'ddp.objectjenisdiagnosaklaimfk')
            ->leftjoin('pegawai_m as pg', 'pg.id', '=', 'ddp.objectpegawaifk')
            ->where('pd.kdprofile', $this->kdProfile)
            ->whereIn('jd.id', [7,8])
            ->orderby('ddp.objectjenisdiagnosaklaimfk', 'asc');
        if (isset($request['norec_pd']) && $request['norec_pd'] != "" && $request['norec_pd'] != "undefined") {
            $data = $data->where('pd.norec', '=', $request['norec_pd']);
        };

        if (isset($request['nocmfk']) && $request['nocmfk'] != "" && $request['nocmfk'] != "undefined") {
            $data = $data->where('ps.id', '=', $request['nocmfk']);
        };

        if (isset($request['iskematian']) && $request['iskematian'] != "" && $request['iskematian'] != "undefined") {
            $data = $data->where('ddp.iskematian', '=', $request['iskematian']);
        } else{
            $data = $data->whereNull('ddp.iskematian');
        };

        $data = $data->get();

        $result = array(
            'data' => $data,
            'message' => '@epic',
        );
        return $this->respond($result);
    }

    public function deleteDiagnosaPasienX(Request $r)
    {
        DB::beginTransaction();
        try {

            $model = DiagnosaPasien::where('norec', $r['norec'])->delete();
            DetailDiagnosaPasien::where('objectdiagnosapasienfk', $r['norec'])->delete();

            $transStatus = 'true';
        } catch (\Exception $e) {
            $transStatus = 'false';
        }

        if ($transStatus == 'true') {
            $transMessage = "Data Berhasil di Hapus";
            DB::commit();
            $result = array(
                "status" => 200,
                "result" => array(
                    "data"  => $model,
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


    public function deleteDiagnosaPasienO(Request $r)
    {
        DB::beginTransaction();
        try {

            $model = DiagnosaMorfologiPasien::where('norec', $r['norec'])->delete();
            DetailDiagnosaMorfologiPasien::where('objectdiagnosamorfologipasienfk', $r['norec'])->delete();

            $transStatus = 'true';
        } catch (\Exception $e) {
            $transStatus = 'false';
        }

        if ($transStatus == 'true') {
            $transMessage = "Data Berhasil di Hapus";
            DB::commit();
            $result = array(
                "status" => 200,
                "result" => array(
                    "data"  => $model,
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
    
    public function deleteDiagnosaPasienIX(Request $r)
    {
        DB::beginTransaction();
        try {

            $model = DiagnosaTindakanPasien::where('norec', $r['norec'])->delete();
            DetailDiagnosaTindakanPasien::where('objectdiagnosatindakanpasienfk', $r['norec'])->delete();

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
                    "data"  => $model,
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
    public function riwayatDiagnosaXCppt(Request $request)
    {

        $data = DB::table('diagnosapasien_t as dp')
            ->leftjoin('detaildiagnosapasien_t as ddp', 'ddp.objectdiagnosapasienfk', 'dp.norec')
            ->leftjoin('diagnosa_m as dg', 'dg.id', '=', 'ddp.objectdiagnosafk')
            ->leftjoin('jenisdiagnosa_m as jd', 'jd.id', '=', 'ddp.objectjenisdiagnosafk')
            ->select('dp.norec', 'dg.kddiagnosa', 'dg.namadiagnosa', 'jd.jenisdiagnosa', 'jd.id as objectdiagnosafk', 'dg.id', 'ddp.keterangan', 'ddp.objectjenisdiagnosafk')
            ->where('ddp.kdprofile', $this->kdProfile)
            ->where('ddp.noregistrasi', '=', $request['noregistrasi'])
            // ->orderby('ddp.tglinputdiagnosa', 'desc')
            ->get();

        $result = array(
            'data' => $data,
            'message' => '@epic',
        );
        return $this->respond($result);
    }

    public function riwayatDiagnosaIXCppt(Request $request)
    {

        $data = DB::table('diagnosatindakanpasien_t as dtp')
            ->leftjoin('detaildiagnosatindakanpasien_t as ddtp', 'ddtp.objectdiagnosatindakanpasienfk', 'dtp.norec')
            ->leftjoin('diagnosatindakan_m as dt', 'dt.id', '=', 'ddtp.objectdiagnosatindakanfk')
            ->select('dtp.norec', 'dt.kddiagnosatindakan', 'dt.namadiagnosatindakan', 'dt.id')
            ->where('ddtp.kdprofile', $this->kdProfile)
            ->where('ddtp.noregistrasi', '=', $request['noregistrasi'])
            // ->orderby('ddp.tglinputdiagnosa', 'desc')
            ->get();

        $result = array(
            'data' => $data,
            'message' => '@epic',
        );
        return $this->respond($result);
    }
}
