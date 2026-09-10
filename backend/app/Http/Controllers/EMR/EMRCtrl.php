<?php

namespace App\Http\Controllers\EMR;

use App\Models\Master\Ruangan;
use Exception;
use Carbon\Carbon;
use App\Models\User;
use App\Traits\Valet;
use Ramsey\Uuid\Uuid;
use Mockery\Undefined;
use Illuminate\Http\Request;
use App\Models\Master\Pasien;
use App\Models\Transaksi\EMR;
use App\Models\Master\Pegawai;
use App\Models\Master\Diagnosa;
use App\Models\Standar\KelompokUser;
use Illuminate\Support\Facades\DB;
use App\Models\Transaksi\EMRPasien;
use Illuminate\Support\Facades\App;
use App\Http\Controllers\Controller;
use App\Models\Transaksi\EmrDokumen;
use App\Models\Transaksi\NoSuratKeterangan;
use App\Models\Transaksi\StrukOrder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use App\Models\Transaksi\ResumeMedis;
use App\Models\Transaksi\PasienDaftar;
use Illuminate\Database\Query\Builder;
use App\Models\Transaksi\EMROdontogram;
use App\Models\Transaksi\EMRPasienForm;
use Illuminate\Support\Facades\Storage;
use App\Models\Transaksi\SuratKeterangan;
use App\Models\Transaksi\PasienPerjanjian;
use App\Models\Transaksi\IntruksiCPPT;
use App\Models\Transaksi\ResumeMedisDetail;
use App\Models\Transaksi\AntrianPasienDiperiksa;
use App\Models\Transaksi\AntrianPasienRegistrasi;
use App\Models\Transaksi\FormulirSkemaPenyinaranRadioterapi;
use App\Models\Transaksi\BundleKlaim;
use Barryvdh\DomPDF\Facade\Pdf;

class EMRCtrl extends Controller
{
    use Valet;
    public function __construct()
    {
        parent::__construct($is_encrypt = true);
    }

    public function getPasieBynocm(Request $r)
    {
        $data = DB::table('pasien_m as ps')
            ->select('namapasien')
            ->where('ps.statusenabled', true)
            ->where('ps.nocm', $r['nocm'])
            ->get();

        return $this->respond($data);
    }

    public function saveSuratKeterangan(Request $r)
    {
        DB::beginTransaction();
        try {
            $data = $r->input('data');
            $now = date('Y-m-d H:i:s');
            $registrasi = $data['registrasi'];
            $pasien = $data['pasien'];
            $collection = $r->input('collection');

            // Add index
            $data['user_input'] = array(
                'id' => $this->getUserId(),
                'namauser' => $this->getUsername(),
                'pegawaifk' => $this->getPegawai()->id,
                'namalengkap' => $this->getPegawai()->namalengkap,
            );
            $data['profile'] = array(
                'kdprofile' => $this->kdProfile,
                'namaprofile' => $this->getProfile()->namalengkap,
            );
            if (isset($r['userBy'])) {
                $data['userBy'] = $r['userBy'];
            }

            $invalidDates = $this->findInvalidDates($data);
            if ($invalidDates > 0) {
                $transMessage = "Simpan Gagal Format Tanggal Tidak Sesuai !";
                DB::rollBack();
                $result = array(
                    "status" => 400,
                    "result" => array('invalid_date_count' => $invalidDates)
                );
                return $this->respond($result['result'], $result['status'], $transMessage);
            }

            // Add norec_emr and insert to postgress table emrpasien_t
            if ($r['norec_emr'] == '') {
                $noemr = $this->SEQUENCE(new EMRPasien(), 'noemr', 15, 'MR' . date('ym') . '/', $this->kdProfile);
                $EMR = new EMRPasien();
                $norec = $EMR->generateNewId();
                $EMR->norec = $norec;
                $EMR->kdprofile = $this->kdProfile;
                $EMR->statusenabled = true;
                $EMR->noregistrasifk = $registrasi['norec_pd'];
                $EMR->noregistrasi = $registrasi['noregistrasi'];
            } else {
                $EMR = EMRPasien::where('norec', $r['norec_emr'])
                    ->where('kdprofile', $this->kdProfile)
                    ->first();
                $noemr = $EMR['noemr'];
                $norec = $EMR['norec'];
            }

            $EMR->noemr = $noemr;
            $EMR->emrfk = 0;
            $EMR->nocm = $pasien['nocm'];
            $EMR->nocmfk = $pasien['nocmfk'];
            $EMR->namapasien = $pasien['namapasien'];
            $EMR->jeniskelamin = $pasien['jeniskelamin'];
            $EMR->umur = $pasien['umur'];
            $EMR->tgllahir = $pasien['tgllahir'];
            $EMR->notelepon = $pasien['nohp'];
            $EMR->alamat = $pasien['alamatlengkap'];
            $EMR->kelompokpasien = $registrasi['kelompokpasien'];
            $EMR->tglregistrasi = $registrasi['tglregistrasi'];
            $EMR->norec_apd = $registrasi['norec_apd'];
            $EMR->namakelas = $registrasi['namakelas'];
            $EMR->namaruangan = $registrasi['namaruangan'];
            $EMR->jenisemr = $r['jenis_emr'];
            $EMR->pegawaifk = $this->getPegawai()->id;
            $EMR->tglemr = $now;
            $EMR->save();

            // Inserting to MongoDB
            $data['statusenabled'] = true;
            $data['noemr'] = $EMR->noemr;
            $data['emrpasienfk'] = $norec;

            if ($r->input('id') == '') {
                $data['id'] = $this->Uuid4();
                $data['created_at'] = $now;
                $data['updated_at'] = null;

                $genSurat = $this->genSurat(new NoSuratKeterangan(), 'nosint', $r->input('collection'), 4, '', $this->kdProfile, $registrasi['namaruangan']);
                $Suket = new NoSuratKeterangan();
                $norecNew = $Suket->generateNewId();
                $Suket->norec = $norecNew;
                $Suket->kdprofile = $this->kdProfile;
                $Suket->statusenabled = true;
                $Suket->nosint = $genSurat['noint'];
                $data['nosurat'] = $genSurat['nosurat'];
                $Suket->save();

                DB::connection('mongodb')
                    ->table($collection)
                    ->insert($data);
            } else {
                unset($data['registrasi']);
                unset($data['pasien']);

                $data['updated_at'] = $now;
                DB::connection('mongodb')
                    ->table($collection)
                    ->where('id', $r->input('id'))
                    ->update($data);
            }

            // History EMR
            $formExist = DB::connection('mongodb')
                ->table('#ResumeEMR')
                ->where('emrpasienfk', $norec)
                ->where('table', $collection)
                ->where('noregistrasifk', $registrasi['norec_pd'])
                ->first();

            if (empty($formExist)) {
                $resume = array(
                    'id' => $this->Uuid4(),
                    'kdprofile' => $this->kdProfile,
                    'statusenabled' => true,
                    'noregistrasifk' => $registrasi['norec_pd'],
                    'nocmfk' => $pasien['nocmfk'],
                    'emrpasienfk' => $norec,
                    'table' => $collection,
                    'last_update' => $now,
                    'author' => $this->getPegawai()->namalengkap,
                    'url_form' => $r['url_form'],
                    'namaemr' => $r['name_form'],
                    'noemr' => $EMR->noemr,
                    'icon' => isset($r['icon']) ? $r['icon'] : null,
                    'ruangan' => $registrasi['namaruangan'],
                );

                DB::connection('mongodb')
                    ->table('#ResumeEMR')
                    ->insert($resume);
            } else {
                $resume = array(
                    'kdprofile' => $this->kdProfile,
                    'statusenabled' => true,
                    'noregistrasifk' => $registrasi['norec_pd'],
                    'nocmfk' => $pasien['nocmfk'],
                    'emrpasienfk' => $norec,
                    'table' => $collection,
                    'last_update' => $now,
                    'author' => $this->getPegawai()->namalengkap,
                    'url_form' => $r['url_form'],
                    'namaemr' => $r['name_form'],
                    'noemr' => $EMR->noemr,
                    'icon' => isset($formExist['icon']) ? $formExist['icon'] : null,
                    'ruangan' => $registrasi['namaruangan'],
                );
                DB::connection('mongodb')
                    ->table('#ResumeEMR')
                    ->where('id', $formExist['id'])
                    ->update($resume);
            }

            $this->LOGGING(
                $collection,
                $norec,
                'EMR',
                'input EMR ' . $r['name_form'] . ' dari pasien dengan no registrasi ' . $registrasi['noregistrasi'] . ' oleh ' . $this->getPegawai()->namalengkap . ' id :' . $this->getPegawai()->id . ' di ruangan ' . $registrasi['namaruangan']
            );

            $transMessage = "Sukses";
            DB::commit();
            $result = array(
                "status" => 200,
                "result" => array(
                    "norec_emr" => $EMR->norec,
                    "noemr" => $EMR->noemr,
                    "id" => $data['id'],
                    "as" => '@epic',
                )
            );
        } catch (\Exception $e) {
            $transMessage = "Simpan Gagal";
            DB::rollBack();
            $result = array(
                "status" => 400,
                "result" => $e->getMessage() . ' ' . $e->getLine()
            );
        }
        return $this->respond($result['result'], $result['status'], $transMessage);
    }

    public function saveEMR(Request $r)
    {
        DB::beginTransaction();
        try {
            $data = $r->input('data');
            $now = date('Y-m-d H:i:s');
            $registrasi = $data['registrasi'];
            $pasien = $data['pasien'];
            $collection = $r->input('collection');

            // Add index
            $data['user_input'] = array(
                'id' => $this->getUserId(),
                'namauser' => $this->getUsername(),
                'pegawaifk' => $this->getPegawai()->id,
                'namalengkap' => $this->getPegawai()->namalengkap,
            );
            $data['profile'] = array(
                'kdprofile' => $this->kdProfile,
                'namaprofile' => $this->getProfile()->namalengkap,
            );
            if (isset($r['userBy'])) {
                $data['userBy'] = $r['userBy'];
            }

            $invalidDates = $this->findInvalidDates($data);
            if ($invalidDates > 0) {
                $transMessage = "Simpan Gagal Format Tanggal Tidak Sesuai !";
                DB::rollBack();
                $result = array(
                    "status" => 400,
                    "result" => array('invalid_date_count' => $invalidDates)
                );
                return $this->respond($result['result'], $result['status'], $transMessage);
            }

            // Add norec_emr and insert to postgress table emrpasien_t
            if ($r['norec_emr'] == '') {
                $noemr = $this->SEQUENCE(new EMRPasien(), 'noemr', 15, 'MR' . date('ym') . '/', $this->kdProfile);
                $EMR = new EMRPasien();
                $norec = $EMR->generateNewId();
                $EMR->norec = $norec;
                $EMR->kdprofile = $this->kdProfile;
                $EMR->statusenabled = true;
                $EMR->noregistrasifk = $registrasi['norec_pd'];
                $EMR->noregistrasi = $registrasi['noregistrasi'];
            } else {
                $EMR = EMRPasien::where('norec', $r['norec_emr'])
                    ->where('kdprofile', $this->kdProfile)
                    ->first();
                $noemr = $EMR['noemr'];
                $norec = $EMR['norec'];
            }

            $EMR->noemr = $noemr;
            $EMR->emrfk = 0;
            $EMR->nocm = $pasien['nocm'];
            $EMR->nocmfk = $pasien['nocmfk'];
            $EMR->namapasien = $pasien['namapasien'];
            $EMR->jeniskelamin = $pasien['jeniskelamin'];
            $EMR->umur = $pasien['umur'];
            $EMR->tgllahir = $pasien['tgllahir'];
            $EMR->notelepon = $pasien['nohp'];
            $EMR->alamat = $pasien['alamatlengkap'];
            $EMR->kelompokpasien = $registrasi['kelompokpasien'];
            $EMR->tglregistrasi = $registrasi['tglregistrasi'];
            $EMR->norec_apd = $registrasi['norec_apd'];
            $EMR->namakelas = $registrasi['namakelas'];
            $EMR->namaruangan = $registrasi['namaruangan'];
            $EMR->jenisemr = $r['jenis_emr'];
            $EMR->pegawaifk = $this->getPegawai()->id;
            $EMR->tglemr = $now;
            $EMR->save();

            // Inserting to MongoDB
            $data['statusenabled'] = true;
            $data['noemr'] = $EMR->noemr;
            $data['emrpasienfk'] = $norec;

            if ($r->input('id') == '') {
                $data['id'] = $this->Uuid4();
                $data['created_at'] = $now;
                $data['updated_at'] = null;
                DB::connection('mongodb')
                    ->table($collection)
                    ->insert($data);
            } else {
                unset($data['registrasi']);
                unset($data['pasien']);

                $data['updated_at'] = $now;
                DB::connection('mongodb')
                    ->table($collection)
                    ->where('id', $r->input('id'))
                    ->update($data);
            }

            // History EMR
            $formExist = DB::connection('mongodb')
                ->table('#ResumeEMR')
                ->where('emrpasienfk', $norec)
                ->where('table', $collection)
                ->where('noregistrasifk', $registrasi['norec_pd'])
                ->first();

            //? Menambahkan validasi untuk handover CPPT Rawat Inap
            if ($collection != 'HandoverShiftRawatInap') {
                if (empty($formExist)) {
                    $resume = array(
                        'id' => $this->Uuid4(),
                        'kdprofile' => $this->kdProfile,
                        'statusenabled' => true,
                        'noregistrasifk' => $registrasi['norec_pd'],
                        'nocmfk' => $pasien['nocmfk'],
                        'emrpasienfk' => $norec,
                        'table' => $collection,
                        'last_update' => $now,
                        'author' => $this->getPegawai()->namalengkap,
                        'url_form' => $r['url_form'],
                        'namaemr' => $r['name_form'],
                        'noemr' => $EMR->noemr,
                        'icon' => isset($r['icon']) ? $r['icon'] : null,
                        'ruangan' => $registrasi['namaruangan'],
                    );

                    DB::connection('mongodb')
                        ->table('#ResumeEMR')
                        ->insert($resume);
                } else {
                    $resume = array(
                        'kdprofile' => $this->kdProfile,
                        'statusenabled' => true,
                        'noregistrasifk' => $registrasi['norec_pd'],
                        'nocmfk' => $pasien['nocmfk'],
                        'emrpasienfk' => $norec,
                        'table' => $collection,
                        'last_update' => $now,
                        'author' => $this->getPegawai()->namalengkap,
                        'url_form' => $r['url_form'],
                        'namaemr' => $r['name_form'],
                        'noemr' => $EMR->noemr,
                        'icon' => isset($formExist['icon']) ? $formExist['icon'] : null,
                        'ruangan' => $registrasi['namaruangan'],
                    );
                    DB::connection('mongodb')
                        ->table('#ResumeEMR')
                        ->where('id', $formExist['id'])
                        ->update($resume);
                }
            }

            // Inserting to pasiendaftar_t, for vital sign pasien currently
            if ($collection == 'VitalSign') {
                $pd = PasienDaftar::where('norec', $registrasi['norec_pd'])
                    ->where('kdprofile', $this->kdProfile)
                    ->first();
                $pd->tinggibadan = isset($data['tinggiBadan']) ? (float) str_replace(',', '.', $data['tinggiBadan']) : null;
                $pd->beratbadan = isset($data['beratBadan']) ? (float) str_replace(',', '.', $data['beratBadan']) : null;

                $pd->suhu = isset($data['suhu']) ? (float) str_replace(',', '.', $data['suhu']) : null;
                $pd->nadi = isset($data['nadi']) ? (float) str_replace(',', '.', $data['nadi']) : null;
                $pd->pernafasan = isset($data['pernapasan']) ? (float) str_replace(',', '.', $data['pernapasan']) : null;
                $pd->tekanandarah = isset($data['tekananDarah']) ? (float) str_replace(',', '.', $data['tekananDarah']) : null;
                $pd->spo2 = isset($data['SPO2']) ? (float) str_replace(',', '.', $data['SPO2']) : null;
                $pd->save();
            }

            $apd_flag = [];
            $pd_flag = [];
            $collections = [
                'TransThoracaEchoBayi' => ['isPenunjangKhusus' => true],
                'TransThoracaEchoDewasa' => ['isPenunjangKhusus' => true],
                'LowerExtermityDuplexUltrasoundUSGDoppler' => ['isPenunjangKhusus' => true],
                'CarotidDuplexUltrasound' => ['isPenunjangKhusus' => true],
                'FormulirHasilPemeriksaanEkg' => ['isPenunjangKhusus' => true],
                'PemeriksaanKardiotokografi' => ['isPenunjangKhusus' => true],
                'PemeriksaanObstetri' => ['isPenunjangKhusus' => true],
                'PemeriksaanTHT' => ['isPenunjangKhusus' => true],
                'PemeriksaanGynekologi' => ['isPenunjangKhusus' => true],
                'PemeriksaanFetal' => ['isPenunjangKhusus' => true],
                'PemeriksaanEkgMcu' => ['isPenunjangKhusus' => true],
                'PemeriksaanUrologi' => ['isPenunjangKhusus' => true],
                'HasilPemeriksaanBodyplethy' => ['isPenunjangKhusus' => true],
                'HasilPemeriksaanDLCOBodyplethy' => ['isPenunjangKhusus' => true],
                'HasilPemeriksaanSpirometri' => ['isPenunjangKhusus' => true],
                'PemeriksaanEkgInterna' => ['isPenunjangKhusus' => true],
                'PemeriksaanEkgParu' => ['isPenunjangKhusus' => true],
            ];
            $selectedCollection = $collection;

            if (array_key_exists($selectedCollection, $collections)) {
                PasienDaftar::where('norec', $registrasi['norec_pd'])
                    ->where('kdprofile', $this->kdProfile)
                    ->update($collections[$selectedCollection]);
            }

            // Flag for trigger patient show at Nurse Station
            if ($collection == 'AsesmenAwalKeperawatanPasienRawatJalanNurse' || $collection == 'AsesmenAwalKebidananRawatJalanNurse') {
                $apd_flag = ['isaskepnurse' => true, 'updated_at' => $now];
            }

            // Flag khusus Dokter
            if (
                in_array($collection, [
                    'AsesmenMedisRawatJalan',
                    'AsesmenAwalMedisHemodialisa',
                    'AsesmenPsikologis'
                ])
            ) {
                $pd_flag = ['isasmed' => true, 'updated_at' => $now];
            }

            if ($collection == 'AsesmenAwalMedisGawatDarurat' && isset($data['Parameter_GawatDarurat'])) {
                $update_PD = PasienDaftar::where('norec', $registrasi['norec_pd'])->where('kdprofile', $this->kdProfile);
                if ($data['Parameter_GawatDarurat'] == 'Gawat Darurat') {
                    $update_PD->update(['isgadar' => true]);
                } else {
                    $update_PD->update(['isgadar' => false]);
                }
            }

            // Flag khusus Perawat
            if ($collection == 'AsesmenAwalKeperawatanPasienRawatJalan' || $collection == 'AsesmenAwalKebidananRawatJalan' || $collection == 'CatatanKegiatanRadioterapi') {
                $pd_flag = ['isaskeprj' => true, 'updated_at' => $now];
            }

            if (!empty($apd_flag)) {
                AntrianPasienDiperiksa::where('norec', $registrasi['norec_apd'])
                    ->where('kdprofile', $this->kdProfile)
                    ->update($apd_flag);
            }

            if (!empty($pd_flag)) {
                PasienDaftar::where('norec', $registrasi['norec_pd'])
                    ->where('kdprofile', $this->kdProfile)
                    ->update($pd_flag);
            }

            $this->LOGGING(
                $collection,
                $norec,
                'EMR',
                'input EMR ' . $r['name_form'] . ' dari pasien dengan no registrasi ' . $registrasi['noregistrasi'] . ' oleh ' . $this->getPegawai()->namalengkap . ' id :' . $this->getPegawai()->id . ' di ruangan ' . $registrasi['namaruangan']
            );

            $transMessage = "Sukses";
            DB::commit();

            $ihs = null;
            if ($collection == 'VitalSign') {
                $objetoRequest = new \Illuminate\Http\Request();
                $objetoRequest['noregistrasi'] = $registrasi['noregistrasi'];
                $ihs = app('App\Http\Controllers\Bridging\SATUSEHATCtrl')->Observation($objetoRequest, true);
            }

            $result = array(
                "status" => 200,
                "result" => array(
                    "norec_emr" => $EMR->norec,
                    "noemr" => $EMR->noemr,
                    "id" => $data['id'],
                    "Observation" => $ihs,
                    "as" => '@epic',
                )
            );
        } catch (\Exception $e) {
            $transMessage = "Simpan Gagal";
            DB::rollBack();
            $result = array(
                "status" => 400,
                "result" => $e->getMessage() . ' ' . $e->getLine()
            );
        }
        return $this->respond($result['result'], $result['status'], $transMessage);
    }
    public function saveEMRTemplate(Request $r)
    {
        DB::beginTransaction();
        try {
            $data = $r->input('data');
            unset($data['_id']);
            $invalidDates = $this->findInvalidDates($data);

            if ($invalidDates > 0) {
                $transMessage = "Simpan Gagal Format Tanggal Tidak Sesuai !";
                DB::rollBack();
                $result = array(
                    "status" => 400,
                    "result" => array(
                        'invalid_date_count' => $invalidDates
                    )
                );
                return $this->respond($result['result'], $result['status'], $transMessage);
            }
            $now = date('Y-m-d H:i:s');
            $data = $r->input('data');
            if (isset($data['nocm'])) {
                unset($data['nocm']);
            }

            $data['user_input'] = array(
                'id' => $this->getUserId(),
                'namauser' => $this->getUsername(),
                'pegawaifk' => $this->getPegawai()->id,
                // 'kelompokPegawai' => $r['userBy'],
                'namalengkap' => $this->getPegawai()->namalengkap,
            );
            $data['profile'] = array(
                'kdprofile' => $this->kdProfile,
                'namaprofile' => $this->getProfile()->namalengkap,
            );
            if ($r['userBy']) {
                $data['userBy'] = $r['userBy'];
            }
            $data['statusenabled'] = true;

            if ($r->input('id') == '') {
                $data['id'] = $this->Uuid4();
                $data['created_at'] = $now;
                $data['updated_at'] = null;
                DB::connection('mongodb')
                    ->table($r->input('collection'))
                    ->insert($data);
            } else {
                unset($data['_id']);
                $data['updated_at'] = $now;
                if (isset($data['_id'])) {
                    unset($data['_id']);
                }
                DB::connection('mongodb')
                    ->table($r->input('collection'))
                    ->where('id', $r->input('id'))
                    ->update($data);
            }

            $transMessage = "Sukses";
            DB::commit();
            $ihs = null;

            $result = array(
                "status" => 200,
                "result" => array(
                    "id" => $data['id'],
                    "as" => '@epic',
                ),
            );
        } catch (\Exception $e) {

            $transMessage = "Simpan Gagal";
            DB::rollBack();
            $result = array(
                "status" => 400,
                "result" => $e->getMessage() . ' ' . $e->getLine()

            );
        }
        return $this->respond($result['result'], $result['status'], $transMessage);
    }
    public function getOrderObatByNocmfk(Request $r)
    {
        // $results = DB::table('strukorder_t as so')
        //     ->join('orderpelayanan_t as op', 'op.strukorderfk', '=', 'so.norec')
        //     ->join('produk_m as p', 'p.id', '=', 'op.objectprodukfk')
        //     ->where('so.keteranganorder', 'Order Farmasi')
        //     ->select([
        //         'so.norec',
        //         'op.strukorderfk',
        //         'op.objectprodukfk',
        //         'op.noregistrasifk',
        //         'p.namaproduk'
        //     ])
        //     ->limit(5)
        //     ->get();
        $results = DB::table('orderpelayanan_t as op')
            ->join('strukorder_t as so', 'so.norec', '=', 'op.strukorderfk')
            ->join('produk_m as prd', 'prd.id', '=', 'op.objectprodukfk')
            ->join('pasiendaftar_t as pd', 'pd.norec', '=', 'so.noregistrasifk')
            ->join('detailjenisproduk_m as djp', 'djp.id', '=', 'prd.objectdetailjenisprodukfk')
            ->where('prd.objectdetailjenisprodukfk', 2546)
            ->where('so.noregistrasi', $r['noregistrasi'])
            // ->where('so.statusorder', 5)  // uncomment if you need this filter
            // ->limit(5)  // uncomment if you need to limit results
            ->select([
                'prd.namaproduk',
                'prd.objectdetailjenisprodukfk',
                'so.statusorder',
                'djp.reportdisplay as jenisObat'
            ])
            ->get();
        // if (isset($r['noregistrasifk']) && $r['noregistrasifk'] != '') {
        //     $results = $results->where('so.noregistrasifk', $r['noregistrasifk']);
        // }

        return $this->respond($results);
    }

    public function getEMR(Request $r)
    {
        if (empty($r['collection'])) {
            return $this->respond([]);
        }
        $nocmfk = $r['nocmfk'];

        $res = DB::connection('mongodb')
            ->table($r['collection'])
            ->where('pasien.nocmfk', $nocmfk)
            ->where('profile.kdprofile', $this->kdProfile)
            ->where('statusenabled', true)
            ->whereNull('namatemplate');

        if (isset($r['emrpasienfk']) && $r['emrpasienfk'] != '') {
            $res = $res->where('emrpasienfk', '=', $r['emrpasienfk']);
        }
        if (isset($r['noorder']) && $r['noorder'] != '') {
            $res = $res->where('noorder', '=', $r['noorder']);
        }
        if ($r['kelompokPegawai'] == 'perawat') {
            $res = $res->where('userBy', '=', $r['kelompokPegawai']);
        }
        if (isset($r['norec_pd']) && $r['norec_pd'] != '') {
            $res = $res->where('registrasi.norec_pd', $r['norec_pd']);
        }
        if (isset($r['index_tabs']) && $r['index_tabs'] != '' && $r['index_tabs'] > 1) {
            $res = $res->where('index_tabs', (int) $r['index_tabs']);
        }

        // Orderby untuk menampilkan halaman pertama
        if (isset($r['index_tabs']) && $r['index_tabs'] != '' && $r['index_tabs'] == 1) {
            $res = $res->orderBy('created_at');
        } else {
            $res = $res->orderByDesc('created_at')->orderByDesc('update_at');
        }

        $res = $res->get();

        if (count($res) > 0) {
            $res = $res->toArray();
            foreach ($res as $k => $rr) {
                unset($res[$k]['_id']);
            }
        }

        return $this->respond($res);
    }

    public function getEMRMonitoringICUJadwalTerbuka(Request $r)
    {
        if (empty($r['collection'])) {
            return $this->respond([]);
        }
        // $nocmfk = strlen($r['nocmfk']) >= 32 ? $r['nocmfk'] : (int)$r['nocmfk'];
        $nocmfk = strlen($r['nocmfk']) >= 32 ? $r['nocmfk'] : $r['nocmfk'];

        $res = DB::connection('mongodb')
            ->table($r['collection'])
            // ->where('namatemplate', '=', null)
            ->where('pasien.nocmfk', $nocmfk)
            ->where('profile.kdprofile', $this->kdProfile)
            ->where('statusenabled', true)
            // ->where('statusJadwal', '=', 'Terbuka')
            ->whereNull('namatemplate');
        // ->whereNull('namatemplate');
        if (isset($r['statusJadwal']) && $r['statusJadwal'] != '') {
            $res = $res->where('statusJadwal', $r['statusJadwal']);
        }
        if (isset($r['emrpasienfk']) && $r['emrpasienfk'] != '') {
            $res = $res->where('emrpasienfk', '=', $r['emrpasienfk']);
        }
        if (isset($r['noorder']) && $r['noorder'] != '') {
            $res = $res->where('noorder', '=', $r['noorder']);
        }
        if (isset($r['namaruangan']) && $r['namaruangan'] != '') {
            $res = $res->where('registrasi.namaruangan', '=', $r['namaruangan']);
        }
        if ($r['kelompokPegawai'] == 'perawat') {
            $res = $res->where('userBy', '=', $r['kelompokPegawai']);
        }
        if (isset($r['norec_pd']) && $r['norec_pd'] != '') {
            $res = $res->where('registrasi.norec_pd', $r['norec_pd']);
        }
        if (isset($r['index_tabs']) && $r['index_tabs'] != '' && $r['index_tabs'] > 1) {
            // Kenapa butuh parameter baru, supaya bisa get index tabnya, kalo ga pake form yang belum pernah ada penambahan halaman akan hilang
            if (isset($r['check_first_tab'])) {
                $res = $res->where('index_tabs', (int) $r['index_tabs']);
            } else {
                $res = $res->where('index_tabs', (int) $r['index_tabs']);
            }
        }
        // FOR FOSIOTERAPI
        if (isset($r['tglregis']) && $r['tglregis'] != '') {
            $date = date('Y-m-d', strtotime('+ 1 day', strtotime($r['tglregis'])));
            $res = $res->where('created_at', '<=', $date);
            $res = $res->orderByDesc('created_at');
        } else if (isset($r['index_tabs']) && $r['index_tabs'] != '') {
            $res = $res->orderBy('index_tabs')->orderByDesc('created_at');
        } else {
            $res = $res->orderByDesc('created_at');
        }
        $res = $res->get();
        // return $res;


        $close_fisio = false;
        if (count($res) > 0) {
            $res = $res->toArray();
            foreach ($res as $k => $rr) {
                unset($res[$k]['_id']);
            }
        }

        return $this->respond($res);
    }

    public function getEMRMonitoringICUJadwalTertutup(Request $r)
    {
        if (empty($r['collection'])) {
            return $this->respond([]);
        }
        // $nocmfk = strlen($r['nocmfk']) >= 32 ? $r['nocmfk'] : (int)$r['nocmfk'];
        $nocmfk = strlen($r['nocmfk']) >= 32 ? $r['nocmfk'] : $r['nocmfk'];

        $res = DB::connection('mongodb')
            ->table($r['collection'])
            ->select('created_at', 'id', 'statusJadwal')
            // ->where('namatemplate', '=', null)
            ->where('pasien.nocmfk', $nocmfk)
            ->where('profile.kdprofile', $this->kdProfile)
            ->where('statusenabled', true)
            // ->where('statusJadwal', '=', 'Tertutup')
            ->whereNull('namatemplate');
        // ->whereNull('namatemplate');
        if (isset($r['statusJadwal'])) {
            $res = $res->where('statusJadwal', $r['statusJadwal']);
        }
        if (isset($r['norec_pd']) && $r['norec_pd'] != '') {
            $res = $res->where('registrasi.norec_pd', $r['norec_pd']);
        }
        if (isset($r['index_tabs']) && $r['index_tabs'] != '' && $r['index_tabs'] > 1) {
            // Kenapa butuh parameter baru, supaya bisa get index tabnya, kalo ga pake form yang belum pernah ada penambahan halaman akan hilang
            if (isset($r['check_first_tab'])) {
                $res = $res->where('index_tabs', (int) $r['index_tabs']);
            } else {
                $res = $res->where('index_tabs', (int) $r['index_tabs']);
            }
        }
        // FOR FOSIOTERAPI
        if (isset($r['tglregis']) && $r['tglregis'] != '') {
            $date = date('Y-m-d', strtotime('+ 1 day', strtotime($r['tglregis'])));
            $res = $res->where('created_at', '<=', $date);
            $res = $res->orderByDesc('created_at');
        } else if (isset($r['index_tabs']) && $r['index_tabs'] != '') {
            $res = $res->orderBy('index_tabs')->orderByDesc('created_at');
        } else {
            $res = $res->orderByDesc('created_at');
        }
        $res = $res->get();
        // return $res;


        $close_fisio = false;
        if (count($res) > 0) {
            $res = $res->toArray();
            foreach ($res as $k => $rr) {
                unset($res[$k]['_id']);
            }
        }

        return $this->respond($res);
    }

    public function getEMRMonitoringICUJadwalTertutupDetail(Request $r)
    {
        if (empty($r['collection'])) {
            return $this->respond([]);
        }
        // $nocmfk = strlen($r['nocmfk']) >= 32 ? $r['nocmfk'] : (int)$r['nocmfk'];
        $nocmfk = strlen($r['nocmfk']) >= 32 ? $r['nocmfk'] : $r['nocmfk'];

        $res = DB::connection('mongodb')
            ->table($r['collection'])
            // ->where('namatemplate', '=', null)
            ->where('pasien.nocmfk', $nocmfk)
            // ->where('profile.kdprofile', $this->kdProfile)
            ->where('statusenabled', true)
            ->whereNull('namatemplate');

        if (isset($r['norec_pd']) && $r['norec_pd'] != '') {
            $res = $res->where('registrasi.norec_pd', $r['norec_pd']);
        }

        if (isset($r['id']) && $r['id'] != '') {
            $res = $res->where('id', $r['id']);
        }

        if (isset($r['index_tabs']) && $r['index_tabs'] != '' && $r['index_tabs'] > 1) {
            // Kenapa butuh parameter baru, supaya bisa get index tabnya, kalo ga pake form yang belum pernah ada penambahan halaman akan hilang
            if (isset($r['check_first_tab'])) {
                $res = $res->where('index_tabs', (int) $r['index_tabs']);
            } else {
                $res = $res->where('index_tabs', (int) $r['index_tabs']);
            }
        }
        // FOR FOSIOTERAPI
        if (isset($r['tglregis']) && $r['tglregis'] != '') {
            $date = date('Y-m-d', strtotime('+ 1 day', strtotime($r['tglregis'])));
            $res = $res->where('created_at', '<=', $date);
            $res = $res->orderByDesc('created_at');
        } else if (isset($r['index_tabs']) && $r['index_tabs'] != '') {
            $res = $res->orderBy('index_tabs')->orderByDesc('created_at');
        } else {
            $res = $res->orderByDesc('created_at');
        }

        $res = $res->get();
        // return $res;


        $close_fisio = false;
        if (count($res) > 0) {
            $res = $res->toArray();
            foreach ($res as $k => $rr) {
                unset($res[$k]['_id']);
            }
        }

        return $this->respond($res);
    }

    public function getLastEMR(Request $r)
    {
        if (empty($r['collection'])) {
            return $this->respond([]);
        }
        $nocmfk = strlen($r['nocmfk']) >= 32 ? $r['nocmfk'] : $r['nocmfk'];

        $res = DB::connection('mongodb')
            ->table($r['collection'])
            ->where('pasien.nocmfk', $nocmfk)
            ->where('profile.kdprofile', $this->kdProfile)
            ->where('statusenabled', true)
            ->whereNull('namatemplate');

        if (isset($r['norec_pd']) && $r['norec_pd'] != '') {
            $res = $res->where('registrasi.norec_pd', $r['norec_pd']);
        }
        if (isset($r['index_tabs']) && $r['index_tabs'] != '' && $r['index_tabs'] > 1) {
            $res = $res->where('index_tabs', (int) $r['index_tabs']);
        }

        $res = $res->orderByDesc('created_at');
        $res = $res->first();

        if (isset($res)) {
            unset($res['_id']);
        }

        return $this->respond($res);
    }

    public function getEMRCT(Request $r)
    {
        if (empty($r['collection'])) {
            return $this->respond([]);
        }
        // $nocmfk = strlen($r['nocmfk']) >= 32 ? $r['nocmfk'] : (int)$r['nocmfk'];
        $nocmfk = strlen($r['nocmfk']) >= 32 ? $r['nocmfk'] : $r['nocmfk'];

        $res = DB::connection('mongodb')
            ->table($r['collection'])
            ->where('namatemplate', '=', null)
            ->where('pasien.nocmfk', $nocmfk)
            ->where('profile.kdprofile', $this->kdProfile)
            ->where('statusenabled', true);

        $res_now = DB::connection('mongodb')
            ->table($r['collection'])
            ->where('namatemplate', '=', null)
            ->where('pasien.nocmfk', $nocmfk)
            ->where('profile.kdprofile', $this->kdProfile)
            ->where('statusenabled', true)
            ->where('created_at', '<=', date('Y-m-d') . ' 23:59:59')
            ->orderByDesc('created_at')
            ->get();

        $res = $res->orderByDesc('created_at');
        $res = $res->get();

        if (count($res) > 0) {
            $res = $res->toArray();
            foreach ($res as $k => $rr) {
                unset($res[$k]['_id']);
            }
        }

        if (count($res_now) > 0) {
            $res_now = $res_now->toArray();
            foreach ($res_now as $k => $rr) {
                unset($res_now[$k]['_id']);
            }
        }

        $result = array(
            "data" => $res,
            "dataToday" => $res_now
        );

        return $this->respond($result);
    }
    public function getEmrRE(Request $r)
    {
        if (empty($r['collection'])) {
            return $this->respond([]);
        }

        $res = DB::connection('mongodb')
            ->table($r['collection'])
            ->whereNull('namatemplate')
            ->where('pasien.nocmfk', $r['nocmfk'])
            ->where('profile.kdprofile', $this->kdProfile)
            ->where('statusenabled', true)
            ->orderByDesc('created_at')
            ->get();

        if (isset($r['emrpasienfk']) && $r['emrpasienfk'] != '') {
            $res = $res->where('emrpasienfk', '=', $r['emrpasienfk']);
        }
        if ($r['kelompokPegawai'] == 'perawat') {
            $res = $res->where('userBy', '=', $r['kelompokPegawai']);
        }
        if (isset($r['norec_pd']) && $r['norec_pd'] != '') {
            $res = $res->where('registrasi.norec_pd', $r['norec_pd']);
        }
        if (isset($r['index_tabs']) && $r['index_tabs'] != '') {
            $res = $res->where('index_tabs', (int) $r['index_tabs']);
        }
        if (isset($r['flag']) && $r['flag'] !== '') {
            $res = $res->filter(function ($item) use ($r) {
                if (!isset($item->details) || !is_array($item->details)) {
                    return false;
                }
                foreach ($item->details as $detail) {
                    if (isset($detail['flag']) && $detail['flag'] == $r['flag']) {
                        return true;
                    }
                }
                return false;
            });
        }
        return $this->respond($res);
    }

    public function getEMRTemplate(Request $r)
    {
        if (empty($r['collection'])) {
            return $this->respond([]);
        }

        // $nocmfk = strlen($r['nocmfk']) >= 32 ? $r['nocmfk'] : (int)$r['nocmfk'];
        $nocmfk = strlen($r['nocmfk']) >= 32 ? $r['nocmfk'] : $r['nocmfk'];

        $res = DB::connection('mongodb')
            ->table($r['collection'])
            ->whereNotNull('namatemplate')
            ->where('namatemplate', '!=', '')
            ->where('profile.kdprofile', $this->kdProfile);

        if (!isset($r['isAll']) || $r['isAll'] == 'false') {
            $res = $res->where('user_input.id', $r['userData']['id']);
        }
        if (isset($r['nocmfk']) && $r['nocmfk'] != '') {
            $res = $res->where('pasien.nocmfk', $r['nocmfk']);
        }

        $res = $res->where('statusenabled', true);
        $res = $res->orderByDesc('created_at');
        $res = $res->get();

        return $this->respond($res);
    }

    public function deleteEMRTemplate(Request $r)
    {
        DB::beginTransaction();
        try {
            if (!isset($r['id']) || !isset($r['collection'])) {
                return $this->respond([
                    "status" => 500,
                    "message" => "Template Tidak ditemukan"
                ], 500);
            }

            $res = DB::connection('mongodb')
                ->table($r['collection'])
                ->where('id', $r['id'])
                ->delete();
            DB::commit();

            return $this->respond([
                "status" => 200,
                "message" => "Hapus Template Berhasil",
                "data" => $res
            ], 200);
        } catch (\Exception $e) {
            DB::rollback();
            return $this->respond([
                "status" => 500,
                "message" => $e->getMessage()
            ], 500);
        }
    }
    public function hapusPenunjang(Request $r, $collection)
    {
        try {
            $updateResult = DB::connection('mongodb')
                ->table($collection)
                ->where('profile.kdprofile', $this->kdProfile)
                ->where('id', $r['id'])
                ->update(['statusenabled' => false]);

            $updatedDocument = DB::connection('mongodb')
                ->table($collection)
                ->where('profile.kdprofile', $this->kdProfile)
                ->where('id', $r['id'])
                ->first();

            $result = array(
                "status" => 200,
                "result" => array(
                    "norec_emr" => $updatedDocument['norec'] ?? null,
                    "noemr" => $updatedDocument['noemr'] ?? null,
                    "id" => $updatedDocument['id'] ?? null,
                    "as" => '@epic',
                ),
            );

            return response()->json($result);
        } catch (\Exception $e) {
            // Log::error($e->getMessage());

            return response()->json(['status' => 500, 'message' => 'Error occurred while deleting record: ' . $e->getMessage()], 500);
        }
    }


    public function getEMRTgl(Request $r)
    {
        if (empty($r['collection'])) {
            return $this->respond([]);
        }
        // $nocmfk = strlen($r['nocmfk']) >= 32 ? $r['nocmfk'] : (int)$r['nocmfk'];
        $nocmfk = strlen($r['nocmfk']) >= 32 ? $r['nocmfk'] : $r['nocmfk'];

        $res = DB::connection('mongodb')
            ->table($r['collection'])

            ->where('pasien.nocmfk', $nocmfk)
            ->where('profile.kdprofile', $this->kdProfile)
            ->where('statusenabled', true)
            ->whereNull('namatemplate')
            ->select('created_at');
        if (isset($r['emrpasienfk']) && $r['emrpasienfk'] != '') {
            $res = $res->where('emrpasienfk', '=', $r['emrpasienfk']);
        }
        if ($r['kelompokPegawai'] == 'perawat') {
            $res = $res->where('userBy', '=', $r['kelompokPegawai']);
        }
        if (isset($r['norec_pd']) && $r['norec_pd'] != '') {
            $res = $res->where('registrasi.norec_pd', $r['norec_pd']);
        }
        if (isset($r['index_tabs']) && $r['index_tabs'] != '') {
            $res = $res->where('index_tabs', (int) $r['index_tabs']);
        }
        $res = $res->orderByDesc('created_at');
        $res = $res->get();
        if (count($res) > 0) {
            $res = $res->toArray();
            foreach ($res as $k => $rr) {
                unset($res[$k]['_id']);
            }
        }

        return $this->respond($res);
    }

    public function getAsesmenRanap(Request $r)
    {
        $data['askep'] = DB::connection('mongodb')
            ->table('AsesmenAwalKeperawatanRawatInap')
            ->where('pasien.nocmfk', $r['nocmfk'])
            ->where('registrasi.norec_pd', $r['norec_pd'])
            ->select(
                'TAKeluhanUtama',
                'TARiwayatPenyakitSekarang',
                'TARiwayatPenyakitPengobatan',
                'TARiwayatPenyakitDahulu',
                'TARiwayatPenyakitKeluarga',
            )
            ->latest()
            ->first();
        unset($data['askep']['_id']);

        if ($r['roles'] == 'dokter') {
            $data['asmed'] = DB::connection('mongodb')
                ->table('AsesmenMedisRawatJalan')
                ->select(
                    'perlukontrol',
                    'riwayatkeluar',
                    'statuskeluar',
                    'anamnesis'
                )
                ->latest()
                ->first();
            unset($data['asmed']['_id']);
        }
        return $this->respond($data);
    }

    public function checkAsesmenCPPT(Request $r)
    {
        $nocmfk = $r['nocmfk'] ?? null;
        if ($nocmfk == null)
            throw new \Exception('Empty Data');
        try {
            $kelompokUser = KelompokUser::where('id', session('kelompokuser_id'))->first();

            $nurs = DB::connection('mongodb')
                ->table('AsesmenAwalKeperawatanPasienRawatJalanNurse')
                ->where('pasien.nocmfk', $nocmfk)
                ->where('profile.kdprofile', $this->kdProfile)
                ->select(
                    'tekananDarahObgyn',
                    'nafasObgyn',
                    'celciusObgyn',
                    'nadiObgyn',
                    'sao2Obgyn',
                    'gcse',
                    'gcsv',
                    'gcsm',
                    'keluhanutama',
                    'riwayatpenyakit',
                    'riwayatpenyakitdahulu',
                    'riwayatpengobatan',
                    'riwayatpenyakitkeluarga',
                    'riwayatalergi',
                    'beratbadanObgyn',
                    'tinggibadanObgyn',
                    'created_at',
                    'perlukontrol',
                    'riwayatkeluar',
                    'statuskeluar',
                    'keadaanumumobgyn',
                    'keadaanumum'
                )
                ->where('statusenabled', true)
                ->latest()
                ->first();

            $nursx = DB::connection('mongodb')
                ->table('AsesmenAwalKebidananRawatJalanNurse')
                ->where('pasien.nocmfk', $nocmfk)
                ->where('profile.kdprofile', $this->kdProfile)
                ->select(
                    'tekananDarahObgyn',
                    'nafasObgyn',
                    'celciusObgyn',
                    'nadiObgyn',
                    'sao2Obgyn',
                    'gcse',
                    'gcsv',
                    'gcsm',
                    'keluhanutama',
                    'riwayatpenyakit',
                    'riwayatpenyakitdahulu',
                    'riwayatpengobatan',
                    'riwayatpenyakitkeluarga',
                    'riwayatalergi',
                    'beratbadanObgyn',
                    'tinggibadanObgyn',
                    'created_at',
                    'perlukontrol',
                    'riwayatkeluar',
                    'statuskeluar',
                    'keadaanumumobgyn',
                    'keadaanumum'
                )
                ->where('statusenabled', true)
                ->latest()
                ->first();

            if (!isset($nurs) && !isset($nursx)) {
                $result = [
                    "status" => 201,
                    "message" => "Data Nurse Station belum ada pada pasien ini!",
                    "type" => "keperawatan"
                ];
                return $this->respond($result);
            }
            // if(!isset($medis)) {
            //     $result = [
            //         "status" => 201,
            //         "message" => "Data Assesmen Medis belum ada pada pasien ini!",
            //         "type" => "medis"
            //     ];
            //     return $this->respond($result);
            // }
            if (isset($nurs)) {
                $taked_data = $nurs;
                $taked_data['from'] = 'nurse_perawat';
            } else if (isset($nursx)) {
                $taked_data = $nursx;
                $taked_data['from'] = 'nurse_bidan';
            }

            if (!empty($kelompokUser) && str_contains($kelompokUser->kelompokuser, 'perawat')) {
                $keperawatan = DB::connection('mongodb')
                    ->table('AsesmenAwalKeperawatanPasienRawatJalan')
                    ->where('pasien.nocmfk', $nocmfk)
                    ->where('profile.kdprofile', $this->kdProfile)
                    ->where('statusenabled', true)
                    ->select(
                        'created_at',
                        'perlukontrol',
                        'riwayatkeluar',
                        'statuskeluar',
                        'keadaanumum',
                        'hasilpemeriksaanpenunjang',
                        'instruksiAsesmen',
                        'TADiagnosa',
                        'normal'
                    )
                    ->latest()
                    ->first();

                $keperawatanx = DB::connection('mongodb')
                    ->table('AsesmenAwalKebidananRawatJalan')
                    ->where('pasien.nocmfk', $nocmfk)
                    ->where('profile.kdprofile', $this->kdProfile)
                    ->where('statusenabled', true)
                    ->select(
                        'created_at',
                        'perlukontrol',
                        'riwayatkeluar',
                        'statuskeluar',
                        'keadaanumum',
                        'hasilpemeriksaanpenunjang',
                        'instruksiAsesmen',
                        'TADiagnosa',
                        'normal'
                    )
                    ->latest()
                    ->first();

                if (!isset($keperawatan) && !isset($keperawatanx)) {
                    if (isset($taked_data['_id'])) {
                        unset($taked_data['_id']);
                    }
                    $result = [
                        "status" => 201,
                        "message" => "Data Assesmen Keperawatan belum ada pada pasien ini!",
                        "type" => "askep",
                        "result" => $taked_data
                    ];
                    return $this->respond($result);
                }
            }

            if (!empty($kelompokUser) && str_contains($kelompokUser->kelompokuser, 'dokter')) {
                $medis = DB::connection('mongodb')
                    ->table('AsesmenMedisRawatJalan')
                    ->where('pasien.nocmfk', $nocmfk)
                    ->where('profile.kdprofile', $this->kdProfile)
                    ->where('statusenabled', true)
                    ->select(
                        'anamnesis',
                        'beratBadan',
                        'tinggiBadan',
                        'IMT',
                        'nadi',
                        'celcius',
                        'tekananDarah',
                        'nafas',
                        'sao2',
                        'gcse',
                        'gcsv',
                        'gcsm',
                        'keadaanumum',
                        'created_at',
                        'perlukontrol',
                        'riwayatkeluar',
                        'statuskeluar',
                        'hasilpemeriksaanpenunjang',
                        'instruksiAsesmen',
                        'TADiagnosa',
                        'normal',
                        'lokalisNonTrauma'
                    )
                    ->latest()
                    ->first();

                // if (!isset($medis)) {

                //     $result = [
                //         "status" => 201,
                //         "message" => "Data Assesmen Medis belum ada pada pasien ini!",
                //         "type" => "medis",
                //         "result" => $taked_data
                //     ];
                //     return $this->respond($result);
                // }
            }

            // if(isset($medis)) {
            //     $medis = [
            //         'tekananDarahObgyn' => $medis['tekananDarah'] ?? null,
            //         'nadiObgyn' => $medis['nadi'] ?? null,
            //         'nafasObgyn' => $medis['nafas'] ?? null,
            //         'celciusObgyn' => $medis['celcius'] ?? null,
            //         'sao2Obgyn' => $medis['sao2'] ?? null,
            //         'gcse' => $medis['gcse'] ?? null,
            //         'gcsv' => $medis['gcsv'] ?? null,
            //         'gcsm' => $medis['gcsm'] ?? null,
            //         'keadaanumumobgyn' => $medis['keadaanumum'] ?? null,
            //         'anamnesis' => $medis['anamnesis'] ?? null,
            //         'riwayatkeluar' => $medis['riwayatkeluar'] ?? null,
            //         'statuskeluar' => $medis['statuskeluar'] ?? null,
            //         'perlukontrol' => $medis['perlukontrol'] ?? null,
            //         'TADiagnosa' => $medis['TADiagnosa'] ?? null,
            //         'lokalisNonTrauma' => $medis['lokalisNonTrauma'] ?? null,
            //         'created_at' => $medis['created_at'] ?? null,
            //     ];
            //     $taked_data = $medis;
            //     $taked_data['from'] = 'medis_dokter';
            // }

            // $taked_data['ass_type'] = "nursestation";
            // if($pd > 1) {
            //     if(isset($medis)) {
            //         $taked_data = $medis;
            //         $taked_data['ass_type'] = "medis";
            //     }
            // }
            //  JANGAN DI HAPUS
            // if(isset($taked_data['created_at'])) {
            //     $lastDate = date_create(date('Y-m-d', strtotime($taked_data['created_at'])));
            //     $dateNow = date_create(date('Y-m-d'));
            //     $diff = date_diff($lastDate, $dateNow)->days;
            //     if ($diff >= 90) {
            //         $result = [
            //             "status" => 201,
            //             "message" => "Assesmen terakhir tanggal ". date("Y-m-d", strtotime($taked_data['created_at'])). " Silahkan input kembali!",
            //             "type" => "medis"
            //         ];
            //         return $this->respond($result);
            //     }
            // }

            $taked_data['perlukontrol'] = $medis['perlukontrol'] ?? null;
            $taked_data['riwayatkeluar'] = $medis['riwayatkeluar'] ?? null;
            $taked_data['statuskeluar'] = $medis['statuskeluar'] ?? null;
            $taked_data['keadaanumum'] = $medis['keadaanumumobgyn'] ?? null;
            $taked_data['hasilpemeriksaanpenunjang'] = $medis['hasilpemeriksaanpenunjang'] ?? null;
            $taked_data['instruksiAsesmen'] = $medis['instruksiAsesmen'] ?? null;
            $taked_data['TADiagnosa'] = $medis['TADiagnosa'] ?? null;
            $taked_data['normal'] = $medis['normal'] ?? null;

            if (isset($taked_data['_id'])) {
                unset($taked_data['_id']);
            }
            $result = [
                "status" => 200,
                "message" => "CPPT dapat dibuat",
                "result" => $taked_data
            ];
            return $this->respond($result);
        } catch (\Exception $e) {
            $result = [
                "status" => 500,
                "message" => $e->getMessage() . ' ' . $e->getLine()
            ];
            return $this->respond($result, $result['status'], $result['message']);
        }
    }

    public function checkAsesmenHemo(Request $r)
    {
        $nocmfk = $r['nocmfk'] ?? null;
        if ($nocmfk == null)
            throw new \Exception('Empty Data');
        try {
            $taked_data = null;
            $kelompokUser = KelompokUser::where('id', session('kelompokuser_id'))->first();
            $medis = DB::connection('mongodb')
                ->table('AsesmenAwalMedisHemodialisa')
                ->where('pasien.nocmfk', $nocmfk)
                ->where('profile.kdprofile', $this->kdProfile)
                ->where('statusenabled', true)
                ->select(
                    'created_at',
                    'perlukontrol',
                    'riwayatkeluar',
                    'statuskeluar',
                    'keadaanumum',
                    'hasilpemeriksaanpenunjang',
                    'instruksiAsesmen',
                    'TADiagnosa',
                    'normal'
                )
                ->latest()
                ->first();

            if (!empty($kelompokUser) && str_contains($kelompokUser->kelompokuser, 'dokter')) {
                if (!isset($medis)) {
                    $result = [
                        "status" => 201,
                        "message" => "Data Assesmen Medis belum ada pada pasien ini!",
                        "type" => "medis",
                        "result" => $taked_data
                    ];
                    return $this->respond($result);
                }
                $taked_data = $medis;
            }

            if (!empty($kelompokUser) && str_contains($kelompokUser->kelompokuser, 'perawat')) {
                $keperawatan = DB::connection('mongodb')
                    ->table('FormulirAsuhanKeperawatanDanObservasiPasienHemodialisa')
                    ->where('pasien.nocmfk', $nocmfk)
                    ->where('profile.kdprofile', $this->kdProfile)
                    ->where('statusenabled', true)
                    ->select(
                        'created_at',
                        'perlukontrol',
                        'riwayatkeluar',
                        'statuskeluar',
                        'keadaanumum',
                        'hasilpemeriksaanpenunjang',
                        'instruksiAsesmen',
                        'TADiagnosa',
                        'normal'
                    )
                    ->latest()
                    ->first();

                if (!isset($keperawatan)) {
                    $result = [
                        "status" => 201,
                        "message" => "Data Assesmen Keperawatan belum ada pada pasien ini!",
                        "type" => "askep",
                        "result" => $taked_data
                    ];
                    return $this->respond($result);
                }

                $taked_data = $keperawatan;
            }

            $taked_data['perlukontrol'] = $medis['perlukontrol'] ?? null;
            $taked_data['riwayatkeluar'] = $medis['riwayatkeluar'] ?? null;
            $taked_data['statuskeluar'] = $medis['statuskeluar'] ?? null;
            $taked_data['keadaanumum'] = $medis['keadaanumum'] ?? null;
            $taked_data['hasilpemeriksaanpenunjang'] = $medis['hasilpemeriksaanpenunjang'] ?? null;
            $taked_data['instruksiAsesmen'] = $medis['instruksiAsesmen'] ?? null;
            $taked_data['TADiagnosa'] = $medis['TADiagnosa'] ?? null;
            $taked_data['normal'] = $medis['normal'] ?? null;

            $result = [
                "status" => 200,
                "message" => "CPPT dapat dibuat",
                "result" => $taked_data
            ];
            return $this->respond($result);
        } catch (\Exception $e) {
            $result = [
                "status" => 500,
                "message" => $e->getMessage() . ' ' . $e->getLine()
            ];
            return $this->respond($result);
        }
    }

    public function getEMRTglRuangan(Request $r)
    {
        if (empty($r['collection'])) {
            return $this->respond([]);
        }
        // $nocmfk = strlen($r['nocmfk']) >= 32 ? $r['nocmfk'] : (int)$r['nocmfk'];
        $nocmfk = strlen($r['nocmfk']) >= 32 ? $r['nocmfk'] : $r['nocmfk'];

        $res = DB::connection('mongodb')
            ->table($r['collection'])

            ->where('pasien.nocmfk', $nocmfk)
            ->where('profile.kdprofile', $this->kdProfile)
            ->where('statusenabled', true)
            ->select('created_at', 'registrasi.namaruangan');
        if (isset($r['emrpasienfk']) && $r['emrpasienfk'] != '') {
            $res = $res->where('emrpasienfk', '=', $r['emrpasienfk']);
        }
        if ($r['kelompokPegawai'] == 'perawat') {
            $res = $res->where('userBy', '=', $r['kelompokPegawai']);
        }
        if (isset($r['norec_pd']) && $r['norec_pd'] != '') {
            $res = $res->where('registrasi.norec_pd', $r['norec_pd']);
        }
        if (isset($r['index_tabs']) && $r['index_tabs'] != '') {
            $res = $res->where('index_tabs', (int) $r['index_tabs']);
        }
        $res = $res->orderByDesc('created_at');
        $res = $res->get();
        if (count($res) > 0) {
            $res = $res->toArray();
            foreach ($res as $k => $rr) {
                unset($res[$k]['_id']);
            }
        }

        return $this->respond($res);
    }
    public function getEMRCPPT(Request $r)
    {
        if (empty($r['collection'])) {
            return $this->respond([]);
        }
        $nocmfk = strlen($r['nocmfk']) >= 32 ? $r['nocmfk'] : $r['nocmfk'];
        // return $r['collection'];
        $res = DB::connection('mongodb')
            ->table($r['collection'])
            // ->where('registrasi.norec_pd', $r['norec_pd'])
            ->where('pasien.nocmfk', $nocmfk)
            ->where('profile.kdprofile', $this->kdProfile)
            ->where('statusenabled', true)
            ->latest();
        if (isset($r['emrpasienfk']) && $r['emrpasienfk'] != '') {
            $res = $res->where('emrpasienfk', '=', $r['emrpasienfk']);
        }
        // if(isset($r['norec_apd']) && $r['norec_apd'] != '') {
        //     $res = $res->where('registrasi.norec_apd', '=', $r['norec_apd']);
        // }

        // KALO LAG TOLONG BUKA INI, BIAR TAU AJA DAH PUSING COY GIMANA LAGI
        if (isset($r['allPeriode']) && $r['allPeriode'] == true) {
        } else {
            if (isset($r['norec_pd']) && $r['norec_pd'] != '') {
                $res = $res->where('registrasi.norec_pd', '=', $r['norec_pd']);
            }
        }
        $res = $res->orderByDesc('created_at');
        $res = $res->get();
        // return $res;

        $cppt = DB::connection('mongodb')
            ->table('CPPTDetail')
            // ->where('norec_pd', $r['norec_pd'])
            ->where('nocmfk', $nocmfk)
            ->where('kdprofile', $this->kdProfile)
            ->where('statusenabled', true);
        $cpptFull = $cppt;

        if (isset($r['emrpasienfk']) && $r['emrpasienfk'] != '') {
            $cppt = $cppt->where('emrpasienfk', '=', $r['emrpasienfk']);
        }
        if (isset($r['ruangan']) && $r['ruangan'] != '') {
            $cppt = $cppt->where('ruangan', '=', $r['ruangan']);
            $cpptFull = $cpptFull->where('ruangan', '=', $r['ruangan']);
        }
        if (isset($r['allPeriode']) && $r['allPeriode'] == true) {
        } else {
            // KALO LAG TOLONG BUKA INI, BIAR TAU AJA DAH PUSING COY GIMANA LAGI
            if (isset($r['norec_pd']) && $r['norec_pd'] != '') {
                $cppt = $cppt->where('norec_pd', '=', $r['norec_pd']);
                $cpptFull = $cpptFull->where('norec_pd', '=', $r['norec_pd']);
            }
        }
        if (isset($r['flag']) && $r['flag'] != '') {
            $cppt = $cppt->where('flag', '=', $r['flag']);
            $cpptFull = $cpptFull->where('flag', '=', $r['flag']);
        }
        $cppt = $cppt->orderBy('created_at', 'DESC');
        $cppt = $cppt->get();

        $cpptFull = $cpptFull->orderBy('created_at', 'DESC');
        $cpptFull = $cpptFull->get();

        $apd = DB::table('antrianpasiendiperiksa_t as apd')
            ->leftJoin('ruangan_m as ru', 'ru.id', 'apd.objectruanganfk')
            ->leftJoin('pasiendaftar_t as pd', 'pd.norec', 'apd.noregistrasifk')
            ->select(
                'apd.norec',
                'apd.objectruanganfk',
                'apd.objectkelasfk',
                'apd.kelasrawatfk',
                'ru.namaruangan'
            )
            ->where('pd.nocmfk', $nocmfk)
            ->where('apd.statusenabled', true)
            ->where('pd.statusenabled', true);

        // KALO LAG TOLONG BUKA INI, BIAR TAU AJA DAH PUSING COY GIMANA LAGI
        // if (isset($r['norec_pd']) && $r['norec_pd'] != '') {
        //     $apd = $apd->where('apd.noregistrasifk', $r['norec_pd']);
        // }

        $apd = $apd->get();
        $apd = $apd->keyBy('norec');



        if (count($res) > 0) {
            $res = $res->toArray();
            $cppt = $cppt->toArray();
            $cpptFull = $cpptFull->toArray();
            $apd = $apd->toArray();
            foreach ($res as $k => $rr) {
                $res[$k]['details'] = [];
                $res[$k]['detailsFull'] = [];
                foreach ($cpptFull as $z => $x) {
                    $res[$k]['detailsFull'][] = $cpptFull[$z];
                }
                foreach ($cppt as $z => $x) {
                    // if ($rr['emrpasienfk'] == $x['emrpasienfk']) {
                    //     return "ss";
                    // }
                    unset($cppt[$z]['_id']);
                    if ($rr['emrpasienfk'] == $cppt[$z]['emrpasienfk']) {
                        $cppt[$z]['apd'] = isset($x['norec_apd']) && isset($apd[$x['norec_apd']]) ? $apd[$x['norec_apd']] : [];
                        $res[$k]['details'][] = $cppt[$z];
                    }
                }
                unset($res[$k]['_id']);
            }
        }

        return $this->respond($res);
    }
    public function saveEMRCPPT(Request $r)
    {
        DB::beginTransaction();
        try {
            $data = $r->input('data');
            $invalidDates = $this->findInvalidDates($data);
            if ($invalidDates > 0) {
                $transMessage = "Simpan Gagal Format Tanggal Tidak Sesuai !";
                DB::rollBack();
                $result = array(
                    "status" => 400,
                    "result" => array(
                        'invalid_date_count' => $invalidDates
                    )
                );
                return $this->respond($result['result'], $result['status'], $transMessage);
            }
            date_default_timezone_set('Asia/Jakarta');
            $isDokterAuthor = DB::table('pegawai_m as pg')
                ->join('jenispegawai_m as jp', 'pg.objectjenispegawaifk', 'jp.id')
                ->select('pg.namalengkap', 'jp.jenispegawai', 'pg.objectjenispegawaifk')
                ->where('pg.id', $this->getPegawai()->id)
                ->where('pg.statusenabled', true)
                ->where('pg.objectjenispegawaifk', 1)
                ->where('pg.kdprofile', $this->kdProfile)
                ->count();
            $now = date('Y-m-d H:i:s');
            $registrasi = $r->input('data')['registrasi'];
            $pasien = $r->input('data')['pasien'];
            if ($r['norec_emr'] == '') {
                $noemr = $this->SEQUENCE(new EMRPasien(), 'noemr', 15, 'MR' . date('ym') . '/', $this->kdProfile);
                $EMR = new EMRPasien();
                $norec = $EMR->generateNewId();
                $EMR->norec = $norec;
                $EMR->kdprofile = $this->kdProfile;
                $EMR->statusenabled = true;
                $EMR->noregistrasifk = $registrasi['norec_pd'];
                $EMR->noregistrasi = $registrasi['noregistrasi'];
                $EMRPASIENDETAIL = [];
            } else {
                $EMR = EMRPasien::where('norec', $r['norec_emr'])
                    ->first();

                $noemr = $EMR->noemr;
                $norec = $EMR->norec;

                $EMRPASIENDETAIL = DB::connection('mongodb')
                    ->table('CPPTDetail')
                    ->where('emrpasienfk', $norec)
                    ->get();
            }
            $EMR->noemr = $noemr;
            $EMR->emrfk = 0;
            $EMR->nocm = $pasien['nocm'];
            $EMR->nocmfk = $pasien['nocmfk'];
            $EMR->namapasien = $pasien['namapasien'];
            $EMR->jeniskelamin = $pasien['jeniskelamin'];
            $EMR->umur = $pasien['umur'];
            $EMR->tgllahir = $pasien['tgllahir'];
            $EMR->notelepon = $pasien['nohp'];
            $EMR->alamat = $pasien['alamatlengkap'];
            $EMR->kelompokpasien = $registrasi['kelompokpasien'];
            $EMR->tglregistrasi = $registrasi['tglregistrasi'];
            $EMR->norec_apd = $registrasi['norec_apd'];
            $EMR->namakelas = $registrasi['namakelas'];
            $EMR->namaruangan = $registrasi['namaruangan'];
            $EMR->jenisemr = $r['jenis_emr'];
            $EMR->pegawaifk = $this->getPegawai()->id;
            $EMR->tglemr = $now;
            $EMR->save();
            // MONGO
            $data = $r->input('data');
            if (isset($data['nocm'])) {
                unset($data['nocm']);
            }

            $data['user_input'] = array(
                'id' => $this->getUserId(),
                'namauser' => $this->getUsername(),
                'pegawaifk' => $this->getPegawai()->id,
                'namalengkap' => $this->getPegawai()->namalengkap,
            );
            $data['profile'] = array(
                'kdprofile' => $this->kdProfile,
                'namaprofile' => $this->getProfile()->namalengkap,
            );
            $data['statusenabled'] = true;
            $data['noemr'] = $EMR->noemr;
            $data['emrpasienfk'] = $norec;



            $formExist = DB::connection('mongodb')
                ->table('#ResumeEMR')
                ->where('emrpasienfk', $norec)
                ->where('table', $r->input('collection'))
                ->where('noregistrasifk', $registrasi['norec_pd'])
                ->first();

            if (empty($formExist)) {
                $resume = array(
                    'id' => $this->Uuid4(),
                    'kdprofile' => $this->kdProfile,
                    'statusenabled' => true,
                    'noregistrasifk' => $registrasi['norec_pd'],
                    'nocmfk' => $pasien['nocmfk'],
                    'emrpasienfk' => $norec,
                    'table' => $r->input('collection'),
                    'last_update' => $now,
                    'author' => $this->getPegawai()->namalengkap,
                    'url_form' => $r['url_form'],
                    'namaemr' => $r['name_form'],
                    'noemr' => $EMR->noemr,
                    'icon' => isset($r['icon']) ? $r['icon'] : null,
                    'ruangan' => $registrasi['namaruangan'],
                );
                DB::connection('mongodb')
                    ->table('#ResumeEMR')
                    ->insert($resume);
            } else {
                $resume = array(
                    'kdprofile' => $this->kdProfile,
                    'statusenabled' => true,
                    'noregistrasifk' => $registrasi['norec_pd'],
                    'nocmfk' => $pasien['nocmfk'],
                    'emrpasienfk' => $norec,
                    'table' => $r->input('collection'),
                    'last_update' => $now,
                    'author' => $this->getPegawai()->namalengkap,
                    'url_form' => $r['url_form'],
                    'namaemr' => $r['name_form'],
                    'noemr' => $EMR->noemr,
                    'icon' => isset($formExist['icon']) ? $formExist['icon'] : null,
                    'ruangan' => $registrasi['namaruangan'],
                );
                DB::connection('mongodb')
                    ->table('#ResumeEMR')
                    ->where('id', $formExist['id'])
                    ->update($resume);
            }

            $i = 0;

            $sama = 0;
            $j = 0;
            $h = 0;
            $d = 0;
            $saveCPPT_Detail_Backup = [];
            $detailCPPTDeleted = [];
            $intruksiCPPT = [];

            // return $data['details'];
            // $kCpptDetail = 0l
            foreach ($data['details'] as $item) {
                $item['emrpasienfk'] = $norec;
                $item['kdprofile'] = $this->kdProfile;
                $item['statusenabled'] = true;
                $item['norec_pd'] = $registrasi['norec_pd'];
                $item['nocmfk'] = $pasien['nocmfk'];

                $sama = 0;
                if (isset($item['isDeleted']) && $item['isDeleted'] == true) {
                    DB::connection('mongodb')
                        ->table('CPPTDetail')
                        ->where('kdprofile', $this->kdProfile)
                        ->where('emrpasienfk', $norec)
                        ->where('uuid', $item['uuid'])
                        ->update(['statusenabled' => false]);
                    // ->delete();

                    $detailCPPTDeleted[] = $item['uuid'];
                    $j++;
                } else {
                    if (isset($item['intruksi']) && $item['intruksi'] == "Intruksi DPJP") {
                        // $norecIntruksi = new IntruksiCPPT();
                        $intruksiCPPT[] = [
                            "norec" => (new IntruksiCPPT())->generateNewId(),
                            "norec_cpptdetail" => $item['uuid'],
                            "norecpd" => $registrasi['norec_pd'],
                            "norecapd" => $registrasi['norec_apd'],
                            "objectruanganfk" => $registrasi['objectruanganfk'],
                            "objectpegawaifk" => $item['dpjpUtama'] ? $item['dpjpUtama']['value'] : null ?? null,
                            "objectppafk" => $item['tenagaMedis'] ? $item['tenagaMedis']['value'] : null ?? null,
                            "isconfirm" => false,
                            "israber" => false,
                            "note" => $item['intruksiPPA'],
                            "statusenabled" => true,
                            "created_at" => date("Y-m-d H:i:s"),
                            "updated_at" => date("Y-m-d H:i:s")
                        ];
                    }
                    if (isset($item['dokterraber'])) {
                        foreach ($item['dpjpRawatBersama'] as $k => $rawatbersama) {
                            if (isset($rawatbersama['isintruksi']) && $rawatbersama['isintruksi'] == 'Intruksi untuk Dokter Bersama') {
                                // $norecIntruksi = new IntruksiCPPT();
                                $intruksiCPPT[] = [
                                    "norec" => (new IntruksiCPPT())->generateNewId(),
                                    "norec_cpptdetail" => $item['uuid'],
                                    "norecpd" => $registrasi['norec_pd'],
                                    "norecapd" => $registrasi['norec_apd'],
                                    "objectruanganfk" => $registrasi['objectruanganfk'],
                                    "objectpegawaifk" => isset($rawatbersama['id']['value']) ? $rawatbersama['id']['value'] : null ?? null,
                                    "objectppafk" => isset($item['tenagaMedis']) ? $item['tenagaMedis']['value'] : '' ?? '',
                                    "isconfirm" => false,
                                    "israber" => true,
                                    "note" => $rawatbersama['intruksi'],
                                    "statusenabled" => true,
                                    "created_at" => date("Y-m-d H:i:s"),
                                    "updated_at" => date("Y-m-d H:i:s")
                                ];
                            }
                        }
                    }
                    foreach ($EMRPASIENDETAIL as $emrupdate) {
                        // return $EMRPASIENDETAIL;
                        $sama = 0;
                        if (!isset($item['uuid'])) {
                            $item['uuid'] = $this->Uuid4();
                        }
                        if (!isset($emrupdate['uuid'])) {
                            $emrupdate['uuid'] = $this->Uuid4();
                        }
                        if ($item['uuid'] == $emrupdate['uuid']) {
                            $sama = 2;
                            break;
                        }
                    }
                    if ($sama == 2) {
                        // unset($item['tenagaMedis']);
                        $item['updated_at'] = date('Y-m-d H:i:s');
                        DB::connection('mongodb')
                            ->table('CPPTDetail')
                            ->where('kdprofile', $this->kdProfile)
                            ->where('emrpasienfk', $norec)
                            ->where('uuid', $item['uuid'])
                            ->update($item);

                        $j++;
                    }

                    if ($sama == 0) {
                        $item['norec_apd'] = $registrasi['norec_apd'];
                        $item['tenagaMedis'] = [
                            "label" => $this->getPegawai()->namalengkap,
                            "value" => $this->getPegawai()->id
                        ];
                        $item['created_at'] = date('Y-m-d H:i:s');
                        DB::connection('mongodb')
                            ->table('CPPTDetail')
                            ->insert($item);
                        $h++;
                    }
                }
                $i = $i + 1;
                $saveCPPT_Detail_Backup[] = $item;
            }

            if (count($detailCPPTDeleted) > 0) {
                // Kalo Delete
                DB::table('intruksi_cppt_t')
                    ->whereIn('norec_cpptdetail', $detailCPPTDeleted)
                    ->delete();
            }
            // return $intruksiCPPT;
            if (!empty($intruksiCPPT)) {
                $getIntru = DB::table('intruksi_cppt_t')
                    ->whereIn('norec_cpptdetail', array_column($intruksiCPPT, 'norec_cpptdetail'))
                    ->get()
                    ->keyBy('norec_cpptdetail')
                    ->toArray();

                $arrUpdate = [];
                $arrCreate = [];
                $dnow = now();

                foreach ($intruksiCPPT as $dataIntru) {
                    $dataIntru['updated_at'] = $dnow;

                    if (isset($getIntru[$dataIntru['norec_cpptdetail']])) {
                        $existingRecord = $getIntru[$dataIntru['norec_cpptdetail']];
                        $dataIntruToUpdate = $dataIntru;
                        unset($dataIntruToUpdate['norec'], $dataIntruToUpdate['created_at']);
                        $arrUpdate[] = [
                            'data' => $dataIntruToUpdate,
                            'norec_cpptdetail' => $dataIntru['norec_cpptdetail']
                        ];
                    } else {
                        $dataIntru['created_at'] = $dnow;
                        $arrCreate[] = $dataIntru;
                    }
                }

                if (!empty($arrCreate)) {
                    DB::table('intruksi_cppt_t')->insert($arrCreate);
                }

                foreach ($arrUpdate as $dataup) {
                    DB::table('intruksi_cppt_t')
                        ->where('norec_cpptdetail', $dataup['norec_cpptdetail'])
                        ->update($dataup['data']);
                }
            }


            unset($data['details']);
            unset($data['detailsFull']);
            $insertCPPTdata = null;
            if ($r->input('id') == '') {

                $data['id'] = $this->Uuid4();
                $data['created_at'] = $now;
                $data['updated_at'] = null;
                $insertCPPTdata = DB::connection('mongodb')
                    ->table($r->input('collection'))
                    ->insert($data);
            } else {
                $data['updated_at'] = $now;
                $insertCPPTdata = DB::connection('mongodb')
                    ->table($r->input('collection'))
                    ->where('id', $r->input('id'))
                    ->first();
                if (!isset($insertCPPTdata)) {
                    unset($data['_id']);
                    $data['id'] = $this->Uuid4();
                    $data['created_at'] = $now;
                    $data['updated_at'] = null;
                    $insertCPPTdata = DB::connection('mongodb')
                        ->table($r->input('collection'))
                        ->insert($data);
                } else {
                    $insertCPPTdata = DB::connection('mongodb')
                        ->table($r->input('collection'))
                        ->where('id', $r->input('id'))
                        ->update($data);
                }
                $data['id'] = $r->input('id');
            }
            $transMessage = "Sukses";
            if ($isDokterAuthor > 0) {
                AntrianPasienDiperiksa::where('noregistrasifk', $registrasi['norec_pd'])
                    ->where('statusenabled', true)
                    ->where('kdprofile', $this->kdProfile)
                    ->update(['status' => 'Selesai']);
            }

            //* Update Status CPPT
            PasienDaftar::where('norec', $registrasi['norec_pd'])
                ->where('kdprofile', $this->kdProfile)
                ->update(['iscppt' => true]);

            if (isset($data['kelompokUser']) && ($data['kelompokUser'] == 'perawat' || $data['kelompokUser'] == 'gizi')) {
                AntrianPasienDiperiksa::where('norec', $registrasi['norec_apd'])
                    ->where('statusenabled', true)
                    ->where('kdprofile', $this->kdProfile)
                    ->update(['iscppt_perawat' => true]);
            }
            if (isset($data['kelompokUser']) && $data['kelompokUser'] == 'dokter') {
                AntrianPasienDiperiksa::where('norec', $registrasi['norec_apd'])
                    ->where('statusenabled', true)
                    ->where('kdprofile', $this->kdProfile)
                    ->update(['iscppt_dokter' => true]);
            }

            $this->LOGGING(
                $r->input('collection'),
                $norec,
                'EMR',
                'input EMR ' . $r['name_form'] . ' dari pasien dengan no registrasi ' . $registrasi['noregistrasi'] . ' oleh ' . $this->getPegawai()->namalengkap . ' id :' . $this->getPegawai()->id . ' di ruangan ' . $registrasi['namaruangan']
            );
            DB::commit();
            $result = array(
                "status" => 200,
                "result" => array(
                    "norec_emr" => $EMR->norec,
                    "noemr" => $EMR->noemr,
                    "id" => $data['id'],
                    "as" => '@epic',
                ),
            );
            $this->saveCPPTBackup($saveCPPT_Detail_Backup);
        } catch (\Exception $e) {

            $transMessage = "Simpan Gagal";
            DB::rollBack();
            $result = array(
                "status" => 400,
                "result" => $e->getMessage() . ' ' . $e->getLine() . ' ' . $e->getFile()

            );
        }
        return $this->respond($result['result'], $result['status'], $transMessage);
    }

    public function getRiwayatEMR(Request $r)
    {
        // http://localhost:5173/module/emr/profile-pasien/page-emr/cppt-rev?nocmfk=bb00a881-12c5-4eb7-880e-aab2a0e95084&norec_pd=dfd63d40-2abd-48f4-a53a-3181ad059f51&norec_apd=439637e2-0054-4a91-84d3-19c5773f8252
        $data = DB::table('emrpasien_t as emrp')
            ->leftJoin('pegawai_m as pg', 'pg.id', '=', 'emrp.pegawaifk')
            ->select('emrp.*', 'pg.namalengkap')
            ->where('emrp.statusenabled', true)
            ->where('emrp.kdprofile', $this->kdProfile)
            ->orderBy('emrp.tglemr', 'desc');

        if (isset($r['noemr']) && $r['noemr'] != '') {
            $data = $data->where('emrp.noemr', $r['noemr']);
        }
        if (isset($r['norec']) && $r['norec'] != '') {
            $data = $data->where('emrp.norec', $r['norec']);
        }
        if (isset($r['nocm']) && $r['nocm'] != '') {
            $data = $data->where('emrp.nocm', $r['nocm']);
        }
        if (isset($r['nocmfk']) && $r['nocmfk'] != '') {
            $data = $data->where('emrp.nocmfk', $r['nocmfk']);
        }
        if (isset($r['norec_pd']) && $r['norec_pd'] != '') {
            $data = $data->where('emrp.noregistrasifk', $r['norec_pd']);
        }
        if (isset($r['jenis_emr']) && $r['jenis_emr'] != '') {
            $data = $data->where('emrp.jenisemr', '=', $r['jenis_emr']);
        }
        if (isset($r['is_flash_dashboard']) && $r['is_flash_dashboard'] != '') {
            $data = $data->where('emrp.tglemr', '>=', date("Y-m-d", strtotime("-5 days")) . ' 00:00');
        }
        $data = $data->get();

        return $this->respond($data);
    }
    public function getRiwayatEMR_DETAIL(Request $r)
    {
        $exp = explode(',', $r['collection']);
        $array_col = [];
        foreach ($exp as $col) {
            $array_col[$col] = DB::connection('mongodb')
                ->table($col)
                ->where('emrpasienfk', $r['norec'])
                ->where('profile.kdprofile', $this->kdProfile)
                ->first();
        }

        return $this->respond($array_col);
    }
    public function hapusEMR(Request $r)
    {
        DB::beginTransaction();
        try {
            EMRPasien::where('norec', $r['norec'])
                ->where('kdprofile', $this->kdProfile)
                ->update([
                    'statusenabled' => false
                ]);
            $exp = explode(',', $r['collection']);
            foreach ($exp as $col) {
                DB::connection('mongodb')
                    ->table($col)
                    ->where('emrpasienfk', $r['norec'])
                    ->where('profile.kdprofile', $this->kdProfile)
                    ->update([
                        'statusenabled' => false
                    ]);

                DB::connection('mongodb')
                    ->table('#ResumeEMR')
                    ->where('kdprofile', $this->kdProfile)
                    ->where('emrpasienfk', $r['norec'])
                    ->where('table', '=', $col)
                    ->update([
                        'statusenabled' => false
                    ]);
            }
            $transMessage = "Sukses";
            DB::commit();
            $result = array(
                "status" => 200,
                "result" => array(
                    "as" => '@epic',
                ),
            );
        } catch (\Exception $e) {

            $transMessage = "Hapus Gagal";
            DB::rollBack();
            $result = array(
                "status" => 400,
                "result" => null, //$e->getMessage() . ' ' . $e->getLine()

            );
        }
        return $this->respond($result['result'], $result['status'], $transMessage);
    }
    public function menuEMR(Request $r)
    {
        $dataRaw = DB::table('emr_t as emr')
            ->where('emr.kdprofile', $this->kdProfile)
            ->where('emr.statusenabled', true)
            ->where('emr.namaemr', $r['namaemr'])
            ->select('emr.*')
            ->orderBy('emr.nourut');

        if (isset($r['departemen']) && $r['departemen'] != '' && $r['departemen'] != 'undefined') {
            $dataMapping = DB::table('mapruangantoemr_t as mapemr')
                ->select('mapemr.emrfk')
                ->where('mapemr.kdprofile', $this->kdProfile)
                ->where('mapemr.statusenabled', true)
                ->where('mapemr.objectdepartemenfk', $r['departemen']);
            if (isset($r['ruangan']) && $r['ruangan'] != '') {
                $dataMapping = $dataMapping->where('mapemr.objectruanganfk', $r['ruangan']);
            }
            $dataMapping = $dataMapping->get();

            if (count($dataMapping) > 0) {
                $collection = collect($dataMapping);
                $dataRaw = $dataRaw->whereRaw("emr.id in (" . $collection->implode('emrfk', ', ') . ")");
            }
        }

        $dataRaw = $dataRaw->get();
        if (count($dataRaw) == 0) {
            $dataRaw = DB::table('emr_t as emr')
                ->where('emr.kdprofile', $this->kdProfile)
                ->where('emr.statusenabled', true)
                ->where('emr.namaemr', $r['namaemr'])
                ->select('emr.*')
                ->orderBy('emr.nourut')
                ->get();
        }
        $dataraw3 = [];
        foreach ($dataRaw as $dataRaw2) {
            $dataraw3[] = array(
                'id' => $dataRaw2->id,
                // 'kdprofile' => $dataRaw2->kdprofile,
                // 'statusenabled' => $dataRaw2->statusenabled,
                // 'kodeexternal' => $dataRaw2->kodeexternal,
                // 'namaexternal' => $dataRaw2->namaexternal,
                // 'reportdisplay' => $dataRaw2->reportdisplay,
                // 'namaemr' => $dataRaw2->namaemr,
                'label' => $dataRaw2->caption,
                'headfk' => $dataRaw2->headfk,
                'nourut' => $dataRaw2->nourut,
                'url_form' => $dataRaw2->url_form,
                'collection' => $dataRaw2->collection
            );
        }
        $data = $dataraw3;

        function recursiveElements($data)
        {
            $elements = [];
            $tree = [];
            foreach ($data as &$element) {
                $id = $element['id'];
                $parent_id = $element['headfk'];

                $elements[$id] = &$element;
                if (isset($elements[$parent_id])) {
                    $elements[$parent_id]['child'][] = &$element;
                } else {
                    if ($parent_id <= 10) {
                        $tree[] = &$element;
                    }
                }
                //}
            }
            return $tree;
        }




        $data = recursiveElements($data);


        $result = array(
            'data' => $data,
            'total' => count($dataRaw),
            'message' => '@epic',
        );

        return $this->respond($result);
    }

    public function getDataComboPegawai(Request $request)
    {

        $data = Pegawai::select('id', 'namalengkap as nama')
            ->where('statusenabled', true)
            ->where('kdprofile', $this->kdProfile)
            ->search($request['name'])
            ->take(10)
            ->get();

        return $this->respond($data);
    }

    public function getDataComboDiagnosa(Request $request)
    {

        $data = Diagnosa::select('id', 'kddiagnosa', 'namadiagnosa as nama')
            ->where('kdprofile', $this->kdProfile)
            ->where('statusenabled', true)
            ->search($request['name'])
            ->take(10)
            ->get();

        $dt = [];
        foreach ($data as $item) {
            $dt[] = array(
                'id' => $item->id,
                'nama' => $item->kddiagnosa . ' - ' . $item->nama,
            );
        }

        return $this->respond($dt);
    }

    public function getDiagnosaPasienByNoregICD9(Request $request)
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
                'dt.*',
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
        //            ->join ('jenisdiagnosa_m as jd','jd.id','=','ddp.objectjenisdiagnosafk');
        if (isset($request['nocmfk']) && $request['nocmfk'] != "" && $request['nocmfk'] != "undefined") {
            $data = $data->where('ps.id', '=', $request['nocmfk']);
        }
        ;

        $data = $data->get();

        return $this->respond($data);
    }

    public function getDiagnosaPasienICD10(Request $request)
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
                'ddp.norec as norec_detaildpasien',
                'pg.namalengkap',
                'dp.ketdiagnosis',
                'ddp.keterangan',
                'dg.*',
                'dp.iskasusbaru',
                'dp.iskasuslama',
                DB::raw("CAST(ddp.tglinputdiagnosa AS DATE)"),
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

        if (isset($request['nocmfk']) && $request['nocmfk'] != "" && $request['nocmfk'] != "undefined") {
            $data = $data->where('ps.id', '=', $request['nocmfk']);
        }
        ;

        $data = $data->get();

        return $this->respond($data);
    }

    public function getKasusDiagnosa(Request $request)
    {

        $nocmfk = $request['nocmfk'];
        $iddiagnosa = $request['iddiagnosa'];

        $data = DB::select(DB::raw("select * from
        pasien_m ps
        inner join pasiendaftar_t as pd on ps.id = pd.nocmfk
        inner join antrianpasiendiperiksa_t as app on app.noregistrasifk = pd.norec
        left join diagnosapasien_t as dp on dp.noregistrasifk = app.norec
        left join detaildiagnosapasien_t as ddp on ddp.objectdiagnosapasienfk = dp.norec
        where pd.statusenabled = true
        and ps.id = '$nocmfk'
        and ddp.objectdiagnosafk = $iddiagnosa"));

        $result = array(
            'datas' => $data,
            'message' => 'vexana',
        );
        return $this->respond($result);
    }

    public function getDropdownDiagnosaKeper(Request $request)
    {
        $data = EMR::select('caption', 'url_form', 'id')->where('kdprofile', $this->kdProfile)
            ->where('statusenabled', true)->where('headfk', 93)
            ->where('caption', 'ILIKE', '%' . $request['search'] . '%')
            ->limit(10)
            ->get();

        return $this->respond($data);
    }

    public function getMasterObat(Request $request)
    {
        $data = DB::table('produk_m as p')
            ->join('detailjenisproduk_m as djp', 'djp.id', '=', 'p.objectdetailjenisprodukfk')
            ->where('p.kdprofile', 1)
            ->where('p.statusenabled', true)
            ->where('djp.id', 2546)
            ->orderBy('p.namaproduk')
            ->select('p.id', 'p.namaproduk');

        $data = $data->get();

        return $this->respond($data);
    }

    public function dropdownEMR(Request $r, $table)
    {
        try {
            $search = $r['query'];
            $data = DB::table($table);
            if (isset($r['select']) && $r['select'] != '') {
                if ($table == 'diagnosa_m') {
                    $data = $data->select(DB::raw("id as value,kddiagnosa || ' - ' || namadiagnosa as label"));
                } else if ($table == 'diagnosatindakan_m') {
                    $data = $data->select(DB::raw("id as value,kddiagnosatindakan || ' - ' || namadiagnosatindakan as label"));
                } else if ($table == 'kebangsaan_m') {
                    $data = $data->select(DB::raw("id as value, name as label"));
                    if (isset($r['param_search']) && str_contains($r['param_search'], 'kebangsaan')) {
                        $r['param_search'] = str_replace('kebangsaan', 'name', $r['param_search']);
                    }
                } else if ($table == 'emr_t') {
                } else {
                    $exp = explode(',', $r['select']);
                    foreach ($exp as $items) {
                        if ($items == 'id') {
                            $items = $items . ' as value';
                        }
                        $select[] = $items;
                    }
                    if (isset($exp[1]) && $exp[1] != 'id') {
                        $select[] = $exp[1] . ' as label';
                        foreach ($select as $k => $sel) {
                            if ($sel == $exp[1]) {
                                array_splice($select, $k, 1);
                            }
                        }
                    }
                    $data = $data->select($select);
                }
            }
            if (isset($r['param_search']) && $r['param_search'] != '') {
                $exp = explode(',', $r['param_search']);
                foreach ($exp as $items) {
                    $where[] = [$items, 'ILIKE', '%' . $search . '%'];
                }
                $data = $data->where($where);
            }
            if (isset($r['settingdatafix']) && $r['settingdatafix'] != '') {
                $exp = explode(',', $r['settingdatafix']);
                $arrayDataFix = $this->settingFix($exp[1]);
                if ($table == 'ruangan_m') {
                    if (count($exp) > 2) {
                        $arrayDataFix = $arrayDataFix . ',' . $this->settingFix($exp[2]);
                    }
                }
                $valueSet = explode(',', $arrayDataFix);
                $data = $data->whereIn($exp[0], $valueSet);
            }
            $data = $data->where('kdprofile', $this->kdProfile);
            $data = $data->where('statusenabled', true);

            if (isset($r['orderby']) && $r['orderby'] != '') {
                $data = $data->orderBy($r['orderby']);
            }
            if (isset($r['nyariperawatsamadokter']) && $r['nyariperawatsamadokter'] == 'true') {
                $idjenis = [1, 2];
                $data = $data->whereIN('objectjenispegawaifk', $idjenis);
            }
            $data = $data->limit($r['limit']);
            $data = $data->get();
        } catch (Exception $e) {
            $data = $e->getMessage() . ' ' . $e->getLine();
        }

        return $this->respond($data);
    }
    public function camelCaseToSpace($input)
    {
        $pattern = '/(?<!^)[A-Z]/'; // Match uppercase letters not at the beginning
        $replacement = ' $0'; // Insert a space before the uppercase letter
        $output = preg_replace($pattern, $replacement, $input);
        return trim($output);
    }
    public function getEMRDynamic(Request $r)
    {
        $data = null;
        if (!empty($r['collection'])) {
            $data = DB::connection('mongodb')
                ->table('#DynamicForm')
                ->where('collection', $r['collection'])
                ->where('kdprofile', $this->kdProfile)
                ->first();
        }
        $result = [];
        $dataraw3A = [];
        $dataraw3B = [];
        $dataraw3C = [];
        $dataraw3D = [];
        if (!empty($data)) {
            foreach ($data['data'] as $d) {
                if (isset($d['kolom'])) {
                    if ($d['kolom'] == 1) {
                        $dataraw3A[] = $d;
                    } elseif ($d['kolom'] == 2) {
                        $dataraw3B[] = $d;
                    } elseif ($d['kolom'] == 3) {
                        $dataraw3C[] = $d;
                    } else {
                        $dataraw3D[] = $d;
                    }
                } else {
                    $dataraw3D[] = $d;
                }
            }
        }
        $result = array(
            'kolom1' => $dataraw3A,
            'kolom2' => $dataraw3B,
            'kolom3' => $dataraw3C,
            'kolom4' => $dataraw3D,
            'title' => '-',
            'classgrid' => !empty($data) ? $data['classgrid'] : '',
            'namaemr' => !empty($data) ? $this->camelCaseToSpace($data['collection']) : '',
            'tandatangan' => !empty($data) ? $data['tandatangan'] : [],
            'message' => '@epic',
        );
        return $this->respond($result);
    }

    public function getDataExist(Request $r)
    {
        $res = DB::connection('mongodb')
            ->table('VitalSign')
            ->select('tinggiBadan', 'IMT', 'SPO2', 'beratBadan', 'lingkarPerut', 'nadi', 'suhu', 'tekananDarah', 'pernapasan', 'GCSe', 'GCSv', 'GCSm', 'SkorSkalaNyeri')
            ->where('pasien.nocmfk', $r['nocmfk'])
            ->where('statusenabled', true);

        if (isset($r['norec_pd']) && $r['norec_pd'] != '') {
            $res = $res->where('registrasi.norec_pd', $r['norec_pd']);
        }
        $res = $res->latest();

        $res = $res->first();
        unset($res['_id']);
        return $this->respond($res);
    }
    public function getDataExistSemua(Request $r)
    {
        $res = DB::connection('mongodb')
            ->table('VitalSign')
            ->select('tinggiBadan', 'IMT', 'SPO2', 'beratBadan', 'lingkarPerut', 'nadi', 'suhu', 'tekananDarah', 'pernapasan', 'GCSe', 'GCSv', 'GCSm', 'tanggal', 'user_input')
            ->where('registrasi.norec_pd', $r['norec_pd'])
            ->where('statusenabled', true);

        if (isset($r['norec_pd']) && $r['norec_pd'] != '') {
            $res = $res->where('registrasi.norec_pd', $r['norec_pd']);
        }
        $res = $res->get();

        unset($res['_id']);
        return $this->respond($res);
    }
    public function getDataExistSemuaCPPT(Request $r)
    {
        $dat = DB::connection('mongodb')
            ->table('CatatanPerkembanganPasienTerintegrasi')
            ->select('emrpasienfk')
            ->where('registrasi.norec_pd', $r['norec_pd'])
            ->where('statusenabled', true)
            ->first();
        if (!isset($dat)) {
            return $this->respond([
                "message" => "null",
                "status" => 200
            ]);
        }

        $res = DB::connection('mongodb')
            ->table('CPPTDetail')
            ->select('S', 'O', 'A', 'P', 'user_input', 'tgl')
            ->where('emrpasienfk', $dat['emrpasienfk'])
            ->where('statusenabled', true);

        $res = $res->get();

        unset($res['_id']);
        return $this->respond($res);
    }
    public function getDataExistPertama(Request $r)
    {
        $res = DB::connection('mongodb')
            ->table('VitalSign')
            ->select('tinggiBadan', 'IMT', 'SPO2', 'beratBadan', 'lingkarPerut', 'nadi', 'suhu', 'tekananDarah', 'pernapasan', 'GCSe', 'GCSv', 'GCSm', 'SkorSkalaNyeri')
            ->where('pasien.nocmfk', $r['nocmfk'])
            ->where('statusenabled', true);

        if (isset($r['norec_pd']) && $r['norec_pd'] != '') {
            $res = $res->where('registrasi.norec_pd', $r['norec_pd']);
        }

        $res = $res->first();
        unset($res['_id']);
        return $this->respond($res);
    }

    public function getDokterDPJP(Request $r)
    {
        $dokter = DB::table('pasiendaftar_t as pd')
            ->LEFTJOIN('pegawai_m as pg', 'pg.id', '=', 'pd.objectpegawaifk')
            ->select(
                'pd.nocmfk',
                'pg.namalengkap as dokter',
            )
            ->where('pd.kdprofile', (int) $this->kdProfile)
            ->where('pd.statusenabled', true)
            ->where('pd.nocmfk', $r['nocmfk'])
            ->first();

        return $this->respond($dokter);
    }
    public function getTandaTangan($pegawaifk)
    {
        $res = DB::connection('mongodb')
            ->table('TandaTangan')
            ->where('pegawaifk', (int) $pegawaifk)
            ->where('statusenabled', true)
            ->where('kdprofile', (int) $this->kdProfile)
            ->first();
        return $this->respond($res);
    }

    public function getAutoFill(Request $r)
    {
        $select = explode(',', $r->input('field'));


        $res = DB::connection('mongodb')
            ->table($r->input('collection'))
            ->where('statusenabled', true)
            ->whereNull('namatemplate');

        if ($r->input('field')) {
            $res = $res->select($select);
        }

        if ($r->input('collection') == 'CPPTDetail') {
            if (isset($r['norec_pd']) && $r['norec_pd'] != '') {
                $res = $res->where('norec_pd', $r->input('norec_pd'));
            }
            if (isset($r['ruangan']) && $r['ruangan'] != '') {
                $res = $res->where('ruangan', '=', $r['ruangan']);
            }
        } else {
            if (isset($r['norec_pd']) && $r['norec_pd'] != '') {
                $res = $res->where('registrasi.norec_pd', $r->input('norec_pd'));
            }
            if (isset($r['ruangan']) && $r['ruangan'] != '') {
                $res = $res->where('registrasi.namaruangan', $r->input('ruangan'));
            }
            if (isset($r['nocmfk']) && $r['nocmfk'] != '') {
                $res = $res->where('pasien.nocmfk', $r->input('nocmfk'));
            }
        }

        // if (isset($r['namatemplate']) && $r['namatemplate'] == true) {
        //     $res = $res->whereNull('namatemplate');
        // }

        if (isset($r['flag']) && $r['flag'] != '') {
            $res = $res->where('flag', $r->input('flag'));
        }

        if ($r->input('collection') == 'CatatanKegiatanRadioterapi') {
            $res = $res->whereNotNull('tekananDarah');
        }

        // if (isset($r['orderBy']) && $r['orderBy'] != '' && $r['orderBy'] == 'created_at') {
        $res = $res->orderByDesc('created_at')->first();
        // } else {
        //     $res = $res->orderByDesc('updated_at')
        //         ->orderByDesc('created_at')
        //         ->first();
        // }

        unset($res['_id']);
        return $this->respond($res);
    }
    public function menuNavigasi(Request $r)
    {
        $res = DB::table('emr_t as emr')
            ->where('emr.kdprofile', $this->kdProfile)
            ->where('emr.statusenabled', true)
            ->where('emr.namaemr', 'navigasi')
            ->where('emr.caption', '<>', 'EMR')
            ->select('emr.*')
            ->orderBy('emr.nourut')
            ->get();
        $response[] = array(
            'name' => 'EMR',
            'desc' => 'Elektronik Medical Record Pasien',
            'color' => 'red',
            'icon' => 'lnir lnir-heartrate-monitor',
            'form' => 'module-emr-elektronik-medical-record',
        );
        foreach ($res as $d) {
            $response[] = array(
                'name' => $d->caption,
                'desc' => $d->namaexternal ? $d->namaexternal : $d->caption,
                'color' => $d->kodeexternal ? $d->kodeexternal : 'info',
                'icon' => $d->icon ? $d->icon : '',
                // 'form' => 'module-emr-order-bedah',
                'url_form' => $d->url_form,
            );
        }

        return $this->respond($response);
    }

    public function menuEMRBundle(Request $r)
    {
        $res = DB::table('emr_t as emr')
            ->where('emr.kdprofile', $this->kdProfile)
            ->where('emr.statusenabled', true)
            ->where('emr.namaemr', 'bundle')
            ->select('emr.*')
            ->orderBy('emr.nourut');
        if (isset($r['headBundling']) && $r['headBundling'] != '') {
            $res = $res->whereIn('id', explode(',', $r['headBundling']));
        } else if (isset($r['getHead'])) {
            $res = $res->limit(0);
        }

        $res = $res->get();
        $response = [];
        if (count($res) > 0) {
            foreach ($res as $d) {
                $response[] = array(
                    'id' => $d->id,
                    'name' => $d->caption,
                    'desc' => $d->namaexternal ? $d->namaexternal : $d->caption,
                    'color' => $d->kodeexternal ? $d->kodeexternal : 'info',
                    'icon' => $d->icon ? $d->icon : '',
                    'url_form' => $d->url_form,
                );
            }
        }

        return $this->respond($response);
    }

    public function menuEMRBundleDetail(Request $r)
    {
        // $dataRaw = DB::table('emr_t as emr')
        //     ->where('emr.kdprofile', $this->kdProfile)
        //     ->where('emr.statusenabled', true)
        //     ->where('emr.headbundlingfk', $r['head'])
        //     ->select('emr.*')
        //     ->orderBy('emr.nourut')
        //     ->get();

        $data = DB::table('emr_t as emr')
            ->select('emr.*')
            ->whereRaw("? = ANY(string_to_array(headbundlingfk, ','))", [$r['head']])
            ->whereNotNull('emr.headbundlingfk')
            ->get();

        $result = array(
            'data' => $data,
            'total' => count($data),
            'message' => '@epic',
        );

        return $this->respond($result);
    }

    public function getDataBundleHais(Request $request)
    {
        // $jenis = explode(',', $request->input('jenis'));
        $data = DB::table('haisbundle_m')
            ->where('kdprofile', $this->kdProfile)
            ->where('statusenabled', true)
            // ->whereIn('jenisbundle',$jenis)
            ->select(
                DB::raw(
                    "id AS idbundle,namaexternal || '. ' || kelompok AS kelompok,
            namabundle as keterangan,jenisbundle,nourut,kelompok AS namakelompok"
                )
            )
            ->get();


        return $this->respond($data);
    }
    public function saveBundleHis(Request $r)
    {
        DB::beginTransaction();
        try {
            $now = date('Y-m-d H:i:s');
            $registrasi = $r->input('data')['registrasi'];
            $pasien = $r->input('data')['pasien'];

            $resume = array(
                'norec' => $this->Uuid4(),
                'kdprofile' => $this->kdProfile,
                'statusenabled' => true,
                'noregistrasifk' => $registrasi['norec_pd'],
                'nocmfk' => $pasien['nocmfk'],
                'table' => $r->input('collection'),
                'last_update' => $now,
                'author' => $this->getPegawai()->namalengkap,
                'url_form' => $r['url_form'],
                'namaemr' => $r['name_form'],
                'icon' => isset($r['icon']) ? $r['icon'] : null,
            );

            // MONGO
            $data = $r->input('data');
            if (isset($data['nocm'])) {
                unset($data['nocm']);
            }

            $data['user_input'] = array(
                'id' => $this->getUserId(),
                'namauser' => $this->getUsername(),
                'pegawaifk' => $this->getPegawai()->id,
                'namalengkap' => $this->getPegawai()->namalengkap,
            );
            $data['profile'] = array(
                'kdprofile' => $this->kdProfile,
                'namaprofile' => $this->getProfile()->namalengkap,
            );
            $data['statusenabled'] = true;

            $data['emrpasienfk'] = $resume['norec'];
            if ($r['norec_emr'] == '') {
                $noemr = $this->SEQUENCE($r->input('collection_head'), 'noemr', 15, 'BH' . date('ym') . '/', $this->kdProfile);
                $resume['noemr'] = $noemr;
                $data['noemr'] = $noemr;

                DB::connection('mongodb')
                    ->table($r->input('collection_head'))
                    ->insert($resume);

                $data['id'] = $this->Uuid4();
                $data['created_at'] = $now;
                $data['updated_at'] = null;

                DB::connection('mongodb')
                    ->table($r->input('collection'))
                    ->insert($data);
            } else {
                $noemr = DB::connection('mongodb')
                    ->table($r->input('collection_head'))
                    ->where('norec', $r['norec_emr'])
                    ->first()['noemr'];
                $data['noemr'] = $noemr;
                // unset($resume['norec'] );
                DB::connection('mongodb')
                    ->table($r->input('collection_head'))
                    ->where('norec', $r['norec_emr'])
                    ->update($resume);


                $data['updated_at'] = $now;
                $update = DB::connection('mongodb')
                    ->table($r->input('collection'))
                    ->where('id', $r->input('id'));
                $data['id'] = $r->input('id');
                if ($r['norec_emr'] != '') {
                    $update = $update->where('emrpasienfk', $r['norec_emr']);
                }
                $update = $update->update($data);
            }



            $transMessage = "Sukses";
            DB::commit();
            $result = array(
                "status" => 200,
                "result" => array(
                    "norec_emr" => $data['emrpasienfk'],
                    "noemr" => $noemr,
                    "as" => '@epic',
                ),
            );
        } catch (\Exception $e) {

            $transMessage = "Simpan Gagal";
            DB::rollBack();
            $result = array(
                "status" => 400,
                "result" => $e->getMessage() . ' ' . $e->getLine()

            );
        }
        return $this->respond($result['result'], $result['status'], $transMessage);
    }
    public function hapusBundleHis(Request $r)
    {
        DB::beginTransaction();
        try {

            DB::connection('mongodb')
                ->table($r->input('collection'))
                ->where('emrpasienfk', $r['norec'])
                ->where('profile.kdprofile', $this->kdProfile)
                ->update([
                    'statusenabled' => false
                ]);

            DB::connection('mongodb')
                ->table($r->input('collection_head'))
                ->where('kdprofile', $this->kdProfile)
                ->where('emrpasienfk', $r['norec'])
                ->update([
                    'statusenabled' => false
                ]);
            $transMessage = "Sukses";
            DB::commit();
            $result = array(
                "status" => 200,
                "result" => array(
                    "as" => '@epic',
                ),
            );
        } catch (\Exception $e) {

            $transMessage = "Hapus Gagal";
            DB::rollBack();
            $result = array(
                "status" => 400,
                "result" => null, //$e->getMessage() . ' ' . $e->getLine()

            );
        }
        return $this->respond($result['result'], $result['status'], $transMessage);
    }

    public function getDataComboPartObat(Request $request)
    {
        $dataObat = DB::table('produk_m as pr')
            ->JOIN('stokprodukdetail_t as spd', 'spd.objectprodukfk', '=', 'pr.id')
            ->select('pr.id', 'pr.namaproduk')
            ->where('pr.kdprofile', $this->kdProfile)
            ->where('pr.statusenabled', true)
            ->where('spd.qtyproduk', '>', 0)
            ->groupBy('pr.id', 'pr.namaproduk')
            ->orderBy('pr.namaproduk');

        if (isset($request['filter']) && $request['filter']) {
            $dataObat = $dataObat->where('pr.namaproduk', 'ilike', '%' . $request['filter'] . '%');
        }

        $dataObat = $dataObat->take(10);
        $dataObat = $dataObat->get();

        return $this->respond($dataObat);
    }

    public function getFaktorRisiko(Request $request)
    {
        $kdProfile = $this->kdProfile;

        $ett = collect(DB::select("
            SELECT id AS idpemeriksaan,id,pemeriksaan,jenisfaktorresikofk FROM faktorresiko_m where jenisfaktorresikofk = 1 and kdprofile=$kdProfile
        "));

        $cvl = collect(DB::select("
            SELECT id AS idpemeriksaan,id,pemeriksaan,jenisfaktorresikofk FROM faktorresiko_m where jenisfaktorresikofk = 2 and kdprofile=$kdProfile
        "));

        $ivl = collect(DB::select("
            SELECT id AS idpemeriksaan,id,pemeriksaan,jenisfaktorresikofk FROM faktorresiko_m where jenisfaktorresikofk = 3 and kdprofile=$kdProfile
        "));

        $uc = collect(DB::select("
            SELECT id AS idpemeriksaan,id,pemeriksaan,jenisfaktorresikofk FROM faktorresiko_m where jenisfaktorresikofk = 4 and kdprofile=$kdProfile
        "));

        $tc = collect(DB::select("
            SELECT id AS idpemeriksaan,id,pemeriksaan,jenisfaktorresikofk FROM faktorresiko_m where jenisfaktorresikofk = 5 and kdprofile=$kdProfile
        "));

        $op = collect(DB::select("
            SELECT id AS idpemeriksaan,id,pemeriksaan,jenisfaktorresikofk FROM faktorresiko_m where jenisfaktorresikofk = 6 and kdprofile=$kdProfile
        "));

        $antibiotik = collect(DB::select("
            SELECT id AS idpemeriksaan,id,pemeriksaan,jenisfaktorresikofk FROM faktorresiko_m where jenisfaktorresikofk = 7 and kdprofile=$kdProfile
        "));


        $result = array(
            'ett' => $ett,
            'cvl' => $cvl,
            'ivl' => $ivl,
            'uc' => $uc,
            'tc' => $tc,
            'op' => $op,
            'antibiotik' => $antibiotik,
            'message' => 'ea@epic',
        );

        return $this->respond($result);
    }
    public function getFukuda(Request $request)
    {
        try {
            $dataJsonSend = null;
            $methods = 'GET';
            $set = $this->settingFix('linkECGFukuda');
            $url = str_replace(' ', '%20', $set . 'api/data/patient/data?nomr=' . $request['nocm'] . '&timeAwal=' . $request['dari'] . '&timeAkhir=' . $request['sampai']);

            $header = ['Content-Type:application/json'];

            $response = Http::withHeaders($header)
                ->{$methods}($url, $dataJsonSend);
            return $this->respond(str_replace('},]', '}]', $response));
        } catch (\Exception $e) {
            $response = $e->getMessage() . ' ' . $e->getLine();

            return $this->respond($response);
        }
    }
    public function getComboBerkas(Request $request)
    {
        $result = DB::table('berkaspasien_m as bp')
            ->select('bp.id', 'bp.nama', 'bp.isklaim')
            ->where('bp.kdprofile', $this->kdProfile)
            ->where('bp.statusenabled', true);

        if (isset($request['type']) && $request['type'] == 'lab') {
            $result = $result->whereIn('id', [8, 4, 32]);
        }
        if (isset($request['type']) && $request['type'] == 'rad') {
            $result = $result->whereIn('id', [9, 27, 28, 18, 19, 20, 21, 54, 55, 56, 57]);
        }
        $result = $result->get();


        return $this->respond($result);
    }

    public function saveBerkasPasienOld(Request $request)
    {
        DB::beginTransaction();
        try {
            $kdProfile = $this->kdProfile;
            $uploadBerkasPasien = $request->file('filePasien');
            $path = 'berkaspasien/' . $request['nocm'];
            $dataDokumen = DB::table('emrdokumen_t as emrdkt')
                ->where('emrdkt.norec', '=', $request['norec'])
                ->where('emrdkt.kdprofile', $kdProfile)
                ->where('emrdkt.statusenabled', true)
                ->first();

            if ($dataDokumen == null) {

                if (!empty($uploadBerkasPasien)) {
                    $extension = $uploadBerkasPasien->getClientOriginalExtension();
                    $filename = $request['namafile'] . '_' . date('YmdHis') . '.' . $extension;

                    if (isset($request['norec_so']) && isset($request['norec_pp'])) {
                        $filename = 'Berkas In Vivo_' . date('YmdHis') . '.' . $extension;
                    }

                    $emrDkmn = new EmrDokumen();
                    $norecDkmn = $emrDkmn->generateNewId();
                    $emrDkmn->norec = $norecDkmn;
                    $emrDkmn->kdprofile = $kdProfile;
                    $emrDkmn->statusenabled = true;
                    $emrDkmn->noregistrasi = $request['noregistrasi'];
                    $emrDkmn->nocm = $request['nocm'];
                    $emrDkmn->norec_apd = $request['norec_apd'];
                    $emrDkmn->tglemr = date('Y-m-d H:i:s');
                    $emrDkmn->file = $path . '/' . $filename;
                    $emrDkmn->namafile = $filename;
                    $emrDkmn->deskripsi = $request['keterangan'];
                    $emrDkmn->objectberkaspasien = $request['objectberkaspasien'];
                    $emrDkmn->author = isset($request['author']) ? $request['author'] : null;
                    $emrDkmn->norec_so = isset($request['norec_so']) ? $request['norec_so'] : null;
                    $emrDkmn->norec_hr = isset($request['norec_hr']) ? $request['norec_hr'] : null;
                    $emrDkmn->norec_pp = isset($request['norec_pp']) ? $request['norec_pp'] : null;
                    $emrDkmn->halaman = isset($request['halaman']) ? $request['halaman'] : null;
                    $emrDkmn->nama = isset($request['nama']) ? $request['nama'] : null;

                    $emrDkmn->save();
                    // $request->file('filePasien')->move($path, $filename);
                    $request->fPut(
                        $path . '/' . $filename,
                        File::get($request->file('filePasien')->getRealPath()),
                        'public'
                    );
                }
            } else {
                $emrDkmn = EmrDokumen::where('norec', $request['norec'])->first();
                $emrDkmn->author = isset($request['author']) ? $request['author'] : null;
                $emrDkmn->deskripsi = $request['keterangan'];
                $extension = explode('.', $emrDkmn->file)[1];

                if (!empty($emrDkmn)) {
                    if (File::exists($emrDkmn->file)) {
                        $filename = 'deleted_' . date('YmdHis') . '.' . $extension;
                        $request->fPut(
                            $path . '/' . $filename,
                            File::get($request->file('filePasien')->getRealPath()),
                            'public'
                        );
                    }
                    $filename = $request['namafile'] . '_' . date('YmdHis') . '.' . $extension;
                    $emrDkmn->file = $path . '/' . $filename;
                    $request->fPut(
                        $path . '/' . $filename,
                        File::get($request->file('filePasien')->getRealPath()),
                        'public'
                    );
                } else {
                    if (File::exists($emrDkmn->file)) {
                        $filename = $request['namafile'] . '_' . date('YmdHis') . '.' . $extension;
                        File::move($emrDkmn->file, $path . '/' . $filename);
                        $emrDkmn->file = $path . '/' . $filename;
                    }
                }

                $emrDkmn->namafile = $request['namafile'] . '_' . date('YmdHis') . '.' . $extension;
                $emrDkmn->objectberkaspasien = $request['objectberkaspasien'];
                if (isset($request['nama'])) {
                    $emrDkmn->nama = $request['nama'];
                }
                $emrDkmn->save();
            }
            PasienDaftar::where('noregistrasi', $request['noregistrasi'])->update([
                'isberkas' => true,
            ]);

            $dataRegis = PasienDaftar::where('noregistrasi', $request['noregistrasi'])->first();
            $extension = 'pdf';
            $path = 'dokumen_klaim/' . $request['noregistrasi'];
            $filename = 'berkas' . $request['noregistrasi'] . '_' . date('YmdHis') . '.' . $extension;

            $dataInsert = array(
                "norec" => Uuid::uuid4(),
                "kdprofile" => $this->kdProfile,
                "statusenabled" => true,
                "filename" => $filename,
                "filepath" => $path . '/' . $filename,
                "nocmfk" => $dataRegis->nocmfk,
                "noregistrasifk" => $dataRegis->norec,
                "documentklaimfk" => 298,
                "tglregistrasi" => $dataRegis->tglregistrasi,
            );
            DB::table('monitoringdokklaim_t')->insert($dataInsert);

            $request->fPut(
                $path . '/' . $filename,
                File::get($request->file('filePasien')->getRealPath()),
                'public'
            );

            $transMessage = "Sukses";
            DB::commit();
            $result = array(
                "status" => 200,
                "result" => array(
                    "as" => '@epic',
                ),
            );
        } catch (\Exception $e) {

            $transMessage = "Simpan Gagal";
            DB::rollBack();
            $result = array(
                "status" => 400,
                "result" => $e->getMessage() . ' ' . $e->getLine(),

            );
        }
        return $this->respond($result['result'], $result['status'], $transMessage);
    }

    public function saveBerkasPasien(Request $request)
    {
        DB::beginTransaction();
        try {
            $kdProfile = $this->kdProfile;
            $uploadBerkasPasien = $request->file('filePasien');
            $path = 'berkaspasien/' . $request['nocm'];

            // cek dulu ekstensi nya
            $isImage = in_array($uploadBerkasPasien->getClientOriginalExtension(), ['jpg', 'jpeg', 'png', 'gif', 'bmp']);

            // jika format image maka convert
            if ($isImage) {
                $imagePath = $uploadBerkasPasien->getRealPath();
                $imageData = base64_encode(file_get_contents($imagePath));
                $imageSrc = 'data:image/' . $uploadBerkasPasien->getClientOriginalExtension() . ';base64,' . $imageData;

                // membuat HTML gambar ke pdf
                $html = '<img src="' . $imageSrc . '" style="width: 100%;">';
                if (!File::exists(storage_path('app/' . $path))) {
                    File::makeDirectory(storage_path('app/' . $path), 0777, true, true);
                }


                // generate
                $pdf = Pdf::loadHTML($html);
                $pdfPath = storage_path('app/' . $path . '/' . $uploadBerkasPasien->getClientOriginalName() . '.pdf');
                $pdf->save($pdfPath);

                // timpa image ke pdf
                $uploadBerkasPasien = new \Illuminate\Http\UploadedFile(
                    $pdfPath,
                    $uploadBerkasPasien->getClientOriginalName() . '.pdf',
                    'application/pdf',
                    null,
                    true
                );
            }

            $dataDokumen = DB::table('emrdokumen_t as emrdkt')
                ->where('emrdkt.norec', '=', $request['norec'])
                ->where('emrdkt.kdprofile', $kdProfile)
                ->where('emrdkt.statusenabled', true)
                ->first();

            if ($dataDokumen == null) {
                if (!empty($uploadBerkasPasien)) {
                    $extension = $uploadBerkasPasien->getClientOriginalExtension();
                    $filename = $request['namafile'] . '_' . date('YmdHis') . '.' . $extension;

                    if (isset($request['norec_so']) && isset($request['norec_pp'])) {
                        $filename = 'Berkas In Vivo_' . date('YmdHis') . '.' . $extension;
                    }

                    $emrDkmn = new EmrDokumen();
                    $norecDkmn = $emrDkmn->generateNewId();
                    $emrDkmn->norec = $norecDkmn;
                    $emrDkmn->kdprofile = $kdProfile;
                    $emrDkmn->statusenabled = true;
                    $emrDkmn->noregistrasi = $request['noregistrasi'];
                    $emrDkmn->nocm = $request['nocm'];
                    $emrDkmn->norec_apd = $request['norec_apd'];
                    $emrDkmn->tglemr = date('Y-m-d H:i:s');
                    $emrDkmn->file = $path . '/' . $filename;
                    $emrDkmn->namafile = $filename;
                    $emrDkmn->deskripsi = $request['keterangan'];
                    $emrDkmn->objectberkaspasien = $request['objectberkaspasien'];
                    $emrDkmn->author = $request['author'];
                    $emrDkmn->norec_so = isset($request['norec_so']) ? $request['norec_so'] : null;
                    $emrDkmn->norec_hr = isset($request['norec_hr']) ? $request['norec_hr'] : null;
                    $emrDkmn->norec_pp = isset($request['norec_pp']) ? $request['norec_pp'] : null;
                    $emrDkmn->halaman = isset($request['halaman']) ? $request['halaman'] : null;
                    if (isset($request['nama'])) {
                        $emrDkmn->nama = $request['nama'];
                    }

                    $emrDkmn->save();
                    $request->fPut(
                        $path . '/' . $filename,
                        File::get($uploadBerkasPasien->getRealPath()),
                        'public'
                    );
                }
            } else {
                $emrDkmn = EmrDokumen::where('norec', $request['norec'])->first();
                $emrDkmn->deskripsi = $request['keterangan'];
                $extension = explode('.', $emrDkmn->file)[1];

                if (!empty($emrDkmn)) {
                    if (File::exists($emrDkmn->file)) {
                        $filename = 'deleted_' . date('YmdHis') . '.' . $extension;
                        $request->fPut(
                            $path . '/' . $filename,
                            File::get($uploadBerkasPasien->getRealPath()),
                            'public'
                        );
                    }

                    $filename = $request['namafile'] . '_' . date('YmdHis') . '.' . $extension;

                    $emrDkmn->file = $path . '/' . $filename;
                    $request->fPut(
                        $path . '/' . $filename,
                        File::get($uploadBerkasPasien->getRealPath()),
                        'public'
                    );
                } else {
                    if (File::exists($emrDkmn->file)) {
                        $filename = $request['namafile'] . '_' . date('YmdHis') . '.' . $extension;
                        File::move($emrDkmn->file, $path . '/' . $filename);
                        $emrDkmn->file = $path . '/' . $filename;
                    }
                }

                $emrDkmn->namafile = $request['namafile'] . '_' . date('YmdHis') . '.' . $extension;
                $emrDkmn->objectberkaspasien = $request['objectberkaspasien'];
                if (isset($request['nama'])) {
                    $emrDkmn->nama = $request['nama'];
                }
                $emrDkmn->save();
            }

            PasienDaftar::where('noregistrasi', $request['noregistrasi'])->update([
                'isberkas' => true,
            ]);

            $dataRegis = PasienDaftar::where('noregistrasi', $request['noregistrasi'])->first();
            $extension = 'pdf';
            $path = 'dokumen_klaim/' . $request['noregistrasi'];
            $filename = 'berkas' . $request['noregistrasi'] . '_' . date('YmdHis') . '.' . $extension;

            if ($request['isklaim'] != "false") {
                $dataInsert = array(
                    "norec" => Uuid::uuid4(),
                    "kdprofile" => $this->kdProfile,
                    "statusenabled" => true,
                    "filename" => $filename,
                    "filepath" => $path . '/' . $filename,
                    "nocmfk" => $dataRegis->nocmfk,
                    "noregistrasifk" => $dataRegis->norec,
                    "documentklaimfk" => 298,
                    "objectjenisdokumenfk" => $request['objectjenisdokumenfk'],
                    "tglregistrasi" => $dataRegis->tglregistrasi,
                );
                DB::table('monitoringdokklaim_t')->insert($dataInsert);
                BundleKlaim::updateOrCreate(
                    [
                        'noregistrasi' => $request['noregistrasi'],
                        'filename' => $filename,
                    ],
                    [
                        'data' => base64_encode(file_get_contents($uploadBerkasPasien->getRealPath())),
                        'urut' => 1,
                        'norec' => \Ramsey\Uuid\Uuid::uuid4()->toString(),
                    ]
                );
            }

            $request->fPut(
                $path . '/' . $filename,
                File::get($uploadBerkasPasien->getRealPath()),
                'public'
            );

            // hapus file lama
            if (isset($pdfPath) && file_exists($pdfPath)) {
                unlink($pdfPath);
            }

            $transMessage = "Sukses";
            DB::commit();
            $result = array(
                "status" => 200,
                "result" => array(
                    "as" => '@epic',
                ),
            );
        } catch (\Exception $e) {
            $transMessage = "Simpan Gagal";
            DB::rollBack();
            $result = array(
                "status" => 400,
                "result" => $e->getMessage() . ' ' . $e->getLine(),
            );
        }
        return $this->respond($result['result'], $result['status'], $transMessage);
    }
    public function getBerkasPasien(Request $request)
    {
        $data = DB::table('emrdokumen_t as emrdk')
            ->select('emrdk.*')
            ->where('emrdk.statusenabled', true)
            ->where('emrdk.kdprofile', $this->kdProfile);


        if (isset($request['nocm']) && $request['nocm'] != '') {
            $data = $data->where('emrdk.nocm', $request['nocm']);
        }
        if (isset($request['type']) && $request['type'] == 'lab') {
            $data = $data->whereIn('objectberkaspasien', [8, 4, 32]);
        }
        if (isset($request['type']) && $request['type'] == 'rad') {
            $data = $data->where('objectberkaspasien', 9);
        }
        if (isset($request['isAll']) && $request['isAll'] == 'true') {
            // Show all
        } else {
            if (isset($request['noregistrasi']) && $request['noregistrasi'] != '') {
                $data = $data->where('emrdk.noregistrasi', $request['noregistrasi']);
            }
        }
        if (isset($request['halaman']) && $request['halaman'] != '') {
            $data = $data->where('emrdk.halaman', $request['halaman']);
        }
        if (isset($request['dokumen']) && $request['dokumen'] == 'EKG') {
            $data = $data->where('emrdk.objectberkaspasien', '=', 1);
        }
        if (isset($request['dokumen']) && $request['dokumen'] != '' && $request['dokumen'] != 'EKG') {
            $data = $data->where('emrdk.objectberkaspasien', '=', $request['dokumen']);
        }

        if ($request->query('norec_hr')) {
            $norec_pp = DB::table('hasilradiologi_t as hrt')
                ->where('hrt.norec', '=', $request->query('norec_hr'))
                ->value('pelayananpasienfk'); // Get the column value directly

            $data = $data->where(function ($query) use ($request, $norec_pp) {
                if ($request->query('norec_hr')) {
                    $query->where('emrdk.norec_hr', '=', $request->query('norec_hr'));
                }
                if (!empty($norec_pp)) {
                    $query->orWhere('emrdk.norec_pp', '=', $norec_pp);
                }
            });
        }

        $data = $data->get();

        $result = array(
            'data' => $data,
            'message' => '@epic',
        );
        return $this->respond($result);
    }
    public function hapusBerkasPasien(Request $request)
    {
        DB::beginTransaction();
        try {

            $emrDkmn = EmrDokumen::where('norec', $request['norec'])
                ->where('kdprofile', $this->kdProfile)
                ->first();

            $path = Storage::disk('public')->getAdapter()->getPathPrefix() . $emrDkmn->file;

            if (File::exists($path)) {
                File::delete($path);
            }
            $emrDkmn->delete();

            $transMessage = "Sukses";
            DB::commit();
            $result = array(
                "status" => 200,
                "result" => array(
                    "as" => '@epic',
                ),
            );
        } catch (Exception $e) {

            $transMessage = "Hapus Gagal";
            DB::rollBack();
            $result = array(
                "status" => 400,
                "result" => $e->getMessage() . ' ' . $e->getLine()

            );
        }
        return $this->respond($result['result'], $result['status'], $transMessage);
    }
    public function getAutoFillICD10($noregistrasi)
    {
        $data = DB::table('detaildiagnosapasien_t as ddp')
            ->select(
                'dg.kddiagnosa',
                'dg.namadiagnosa'
            )
            ->join('diagnosa_m as dg', 'dg.id', '=', 'ddp.objectdiagnosafk')
            ->where('ddp.kdprofile', $this->kdProfile)
            ->where('ddp.noregistrasi', '=', $noregistrasi)
            ->get();

        return $this->respond($data);
    }
    public function simpanPerjanjian(Request $request)
    {


        DB::beginTransaction();
        try {
            $kdProfile = $this->kdProfile;
            $method = $request['method'];
            $norec = '';
            if ($method == 'save') {
                $kdJenisSurat = $this->settingFix('SuratKeteranganKontrol');


                $jenisSurat = DB::table('jenissurat_m')
                    ->select('*')
                    ->where('id', $kdJenisSurat)
                    ->first();
                $kodeSurat = '';
                if (!empty($jenisSurat)) {
                    $kodeSurat = $jenisSurat->kodeexternal;
                }
                if ($request['norec'] == '') {
                    $RekamMedis = new PasienPerjanjian();
                    $RekamMedis->norec = $RekamMedis->generateNewId();
                    $RekamMedis->kdprofile = $kdProfile;
                    $RekamMedis->statusenabled = true;
                    $noJanji = $this->SEQUENCE(new PasienPerjanjian, 'noperjanjian', 12, 'P' . $this->getDateTime()->format('ymd'), $kdProfile);

                    // new surat kontrol
                    $genSurat = $this->genSurat(new SuratKeterangan(), 'nosint', $kodeSurat, 4);
                    $SKS = new SuratKeterangan();
                    $SKS->norec = $SKS->generateNewId();
                    $SKS->kdprofile = $kdProfile;
                    $SKS->nosurat = $genSurat['nosurat'];
                    $SKS->nosint = $genSurat['noint'];
                    $SKS->statusenabled = true;
                    $SKS->jenissuratfk = $kdJenisSurat;
                    $SKS->tglsurat = date('Y-m-d H:i:s');
                } else {
                    $RekamMedis = PasienPerjanjian::where('norec', $request['norec'])->where('kdprofile', $kdProfile)->first();
                    $noJanji = $RekamMedis->noperjanjian;

                    // edit surat kontrol
                    $SKS = SuratKeterangan::where('norec', $RekamMedis->objectsuratfk)->where('kdprofile', $kdProfile)->first();
                }
                //surat
                $SKS->pasiendaftarfk = $request['norec_pd'];
                $SKS->dokterfk = $request['objectdokterfk'];
                $SKS->diagnosa = $request['diagnosa'];
                $SKS->indikasi = $request['indikasi'];
                $SKS->tglkontrol = $request['tglperjanjian'];
                $SKS->pegawaifk = $this->getPegawaiId();
                $SKS->save();

                $idPasien = Pasien::where(
                    'nocm',
                    $request['nocm']
                )->where('kdprofile', $kdProfile)->first();
                $RekamMedis->objectpasienfk = $idPasien->id;
                $RekamMedis->objectdokterfk = $request['objectdokterfk'];
                $RekamMedis->jumlahkujungan = $request['jumlahkujungan'];
                $RekamMedis->keterangan = $request['keterangan'];
                $RekamMedis->tglperjanjian = $request['tglperjanjian'];
                $RekamMedis->tglinput = date('Y-m-d H:i:s');
                $RekamMedis->objectruanganfk = $request['objectruanganfk'];
                $RekamMedis->noperjanjian = $noJanji;
                $RekamMedis->objectsuratfk = $SKS->norec;
                $RekamMedis->save();
                $norec = $RekamMedis->norec;

                $cekdatana = AntrianPasienRegistrasi::where('perjanjianfk', $norec)
                    ->where('kdprofile', $kdProfile)
                    ->where('statusenabled', true)
                    ->first();

                if (empty($cekdatana)) {
                    $APR = new AntrianPasienRegistrasi();
                    $APR->norec = $APR->generateNewId();
                    $APR->kdprofile = $kdProfile;
                    $APR->statusenabled = true;
                    $APR->noreservasi = substr(Uuid::uuid4()->toString(), 0, 7);
                    $APR->nocmfk = $idPasien->id;
                    $APR->objectpegawaifk = $request['objectdokterfk'];
                    if ($idPasien->objectpendidikanfk != null) {
                        $APR->objectpendidikanfk = $idPasien->objectpendidikanfk;
                    } else {
                        $APR->objectpendidikanfk = 0;
                    }

                    $APR->objectjeniskelaminfk = $idPasien->objectjeniskelaminfk;
                    $APR->objectruanganfk = $request['objectruanganfk'];
                    $APR->tanggalreservasi = $request['tglperjanjian'];
                    $APR->tipepasien = 'LAMA';
                    $APR->type = '';
                    $APR->keterangan == $request['keterangan'];
                    $APR->perjanjianfk = $norec;
                    $APR->save();
                }

                $penjamin = 0;
                if ($request['kelompokpasienfk'] != 1) {
                    $rekanan = DB::select(DB::raw("select * from mapkelompokpasientopenjamin_m where objectkelompokpasienfk = " . $request['kelompokpasienfk']));
                    $penjamin = $rekanan[0]->kdpenjaminpasien;
                }

                $noregistrasi = $this->SEQUENCE(new PasienDaftar, 'noregistrasi', 10, date('ymd'), $kdProfile);
                if ($noregistrasi == '') {
                    abort(400, 'SEQ ERROR');
                }

                $model_PD = new PasienDaftar();
                $model_PD->norec = $model_PD->generateNewId();
                $model_PD->kdprofile = $kdProfile;
                $model_PD->statusenabled = true;
                $model_PD->objectruanganasalfk = $request['objectruanganfk'];
                $model_PD->statuspasien = 'LAMA';
                $model_PD->tglpulang = null;
                $model_PD->objectruanganlastfk = $request['objectruanganfk'];
                $model_PD->objectpegawaifk = $request['objectdokterfk'];
                $model_PD->objectpegawairawatbersamafk = null;
                $model_PD->jenispelayanan = 1;
                $model_PD->objectkelasfk = 6;
                $model_PD->objectkelompokpasienlastfk = $request['kelompokpasienfk'];
                $model_PD->nocmfk = $idPasien->id;
                $model_PD->objectrekananfk = $penjamin;
                $model_PD->ismobilejkn = null;
                $model_PD->antrianpasienregistrasifk = $APR->norec;
                $model_PD->noreservasi = null;
                $model_PD->tglregistrasi = $request['tglperjanjian'];
                $model_PD->asalrujukanfk = null;
                $model_PD->keteranganasalrujukan = null;
                $model_PD->noregistrasi = $noregistrasi;
                $model_PD->petugas = $this->getNamaPegawai();
                $model_PD->iskiosk = null;
                $model_PD->save();

                $max = AntrianPasienDiperiksa::where('objectruanganfk', $request['objectruanganfk'])
                    ->where('tglregistrasi', '>=', date('Y-m-d', strtotime($request['tglperjanjian'])) . ' 00:00')
                    ->where('tglregistrasi', '<=', date('Y-m-d', strtotime($request['tglperjanjian'])) . ' 23:59')
                    ->where('statusenabled', true)
                    ->max('noantrian');
                $noAntrian = $max + 1;

                $model_APD = new AntrianPasienDiperiksa;
                $model_APD->norec = $model_APD->generateNewId();
                $model_APD->kdprofile = (int) $kdProfile;
                $model_APD->statusenabled = true;
                $model_APD->noantrian = $noAntrian;
                $model_APD->objectasalrujukanfk = null;
                $model_APD->objectkamarfk = null;
                $model_APD->objectruanganfk = $request['objectruanganfk'];
                $model_APD->tglkeluar = $request['tglperjanjian'];
                $model_APD->objectkelasfk = 6;
                $model_APD->nobed = null;
                $model_APD->noregistrasifk = $model_PD->norec;
                $model_APD->objectpegawaifk = $request['objectdokterfk'];
                $model_APD->statusantrian = 0;
                $model_APD->status = "Belum Dipanggil";
                $model_APD->statuskunjungan = 'LAMA';
                $model_APD->tglregistrasi = $request['tglperjanjian'];
                $model_APD->tglmasuk = $request['tglperjanjian'];
                $model_APD->israwatgabung = false;
                $model_APD->nojkn = null;
                $model_APD->noregistrasi = $noregistrasi;
                $model_APD->save();
            }
            if ($method == 'delete') {
                PasienPerjanjian::where('norec', $request['norec'])->update(
                    ['statusenabled' => false]
                );
            }
            $transMessage = "Sukses";
            DB::commit();
            $result = array(
                "status" => 200,
                "result" => array(
                    "pd" => $model_PD,
                    "apd" => $model_APD,
                    "pasien" => $idPasien,
                    "as" => '@vexana',
                ),
            );
        } catch (Exception $e) {

            $transMessage = "Simpan Gagal";
            DB::rollBack();
            $result = array(
                "status" => 400,
                "result" => $e->getMessage() . ' ' . $e->getLine()

            );
        }
        return $this->respond($result['result'], $result['status'], $transMessage);
    }

    public function hapusOrderPerjanjian(Request $request)
    {
        DB::beginTransaction();
        try {

            PasienPerjanjian::where('norec', $request['norec'])->update(['statusenabled' => false]);

            $transMessage = "Sukses";
            DB::commit();
            $result = array(
                "status" => 200,
                "result" => array(
                    "as" => '@epic',
                ),
            );
        } catch (Exception $e) {

            $transMessage = "Simpan Gagal";
            DB::rollBack();
            $result = array(
                "status" => 400,
                "result" => $e->getMessage() . ' ' . $e->getLine()

            );
        }
        return $this->respond($result['result'], $result['status'], $transMessage);
    }

    public function getPasienPerjanjian(Request $request)
    {
        $data = DB::table('pasienperjanjian_t as rm')
            ->select(
                'rm.norec',
                'rm.objectdokterfk',
                'pg.namalengkap',
                'rm.jumlahkujungan',
                'rm.keterangan',
                'rm.objectpasienfk',
                'rm.tglinput',
                'rm.tglperjanjian',
                'st.tglkontrol AS tglperjanjian',
                'rm.noperjanjian',
                'rm.objectruanganfk',
                'ru.namaruangan',
                'ru.kdinternal',
                'pg.kddokterbpjs',
                'ps.nocm',
                'pd.noregistrasi',
                'ps.namapasien',
                'st.diagnosa',
                'st.indikasi',
                'rm.objectsuratfk'
            )
            ->leftJoin('pasien_m as ps', 'rm.objectpasienfk', '=', 'ps.id')
            ->leftJoin('pegawai_m as pg', 'pg.id', '=', 'rm.objectdokterfk')
            ->leftJoin('ruangan_m as ru', 'ru.id', '=', 'rm.objectruanganfk')
            ->leftJoin('suratketerangan_t as st', 'st.norec', '=', 'rm.objectsuratfk')
            ->leftJoin('pasiendaftar_t as pd', 'pd.norec', '=', 'st.pasiendaftarfk')
            ->where('rm.kdprofile', $this->kdProfile)
            ->where('rm.statusenabled', true);


        if (isset($request['noregistrasifk']) && $request['noregistrasifk'] != '') {
            $data = $data->where('rm.noregistrasifk', $request['noregistrasifk']);
        }
        if (isset($request['nocm']) && $request['nocm'] != '') {
            $data = $data->where('ps.nocm', $request['nocm']);
        }
        if (isset($request['nik']) && $request['nik'] != '') {
            $data = $data->where('ps.noidentitas', $request['nik']);
        }
        if (isset($request['belum']) && $request['belum'] != '') {
            $data = $data->where('rm.tglinput', '>=', date('Y-m-d'));
        }
        if (isset($request['tanggal']) && $request['tanggal'] != '') {
            $data = $data->where('rm.tglperjanjian', '>=', date('Y-m-d'));
        }

        if (isset($request['limit']) && $request['limit'] != '') {
            $data = $data->limit($request['limit']);
        }

        $data->orderByDesc('rm.tglinput');
        $data = $data->get();
        $result = array(
            'data' => $data,
        );
        return $this->respond($result);
    }

    public function getIbu(Request $request)
    {
        $nocm = $request['nocm'];

        $data = DB::select(DB::raw("select pdi.norec as norec_pd, pdi.noregistrasi, pdi.nocmfk, ibu.*
        from pasien_m as ps
        inner join pasien_m as ibu on ibu.nocm = ps.nocmfkibu
        inner join pasiendaftar_t as pd on pd.nocmfk = ps.id
        inner join pasiendaftar_t as pdi on pdi.nocmfk = ibu.id
        and pdi.tglregistrasi < pd.tglregistrasi
        where ps.nocm = '$nocm'
        order by pdi.tglregistrasi desc
        limit 1"));

        return $this->respond($data);
    }

    public function getCollectionNameByForm($url_form)
    {
        $data = DB::table('emr_t')
            ->where('kdprofile', $this->kdProfile)
            ->where('statusenabled', true)
            ->where('url_form', $url_form)
            ->first();
        return $this->respond(!empty($data) ? $data->collection : null);
    }
    public function saveOrderKonsul(Request $request)
    {
        $kdProfile = (int) $this->kdProfile;
        DB::beginTransaction();
        try {
            if ($request['norec_so'] == "") {
                $dataSO = new StrukOrder();
                $dataSO->norec = $dataSO->generateNewId();
                $dataSO->kdprofile = $kdProfile;
                $dataSO->statusenabled = true;
                $namaLog = 'Tambah Konsultasi';
                $noOrder = $this->SEQUENCE(new StrukOrder, 'noorder', 11, 'K' . date('ym'), $kdProfile);
            } else {
                $namaLog = 'Ubah Konsultasi';
                $dataSO = StrukOrder::where('norec', $request['norec_so'])->where('kdprofile', $kdProfile)->first();
                $noOrder = $dataSO->noorder;
            }
            $dataSO->nocmfk = $request['nocmfk'];
            $dataSO->isdelivered = 1;
            $dataSO->noorder = $noOrder;
            $dataSO->noorderintern = $noOrder;
            $dataSO->noregistrasifk = $request['norec_pd'];
            $dataSO->objectpegawaiorderfk = $request['pegawaifk'];
            $dataSO->objectpetugasfk = $this->getPegawaiId();
            $dataSO->qtyjenisproduk = 0;
            $dataSO->qtyproduk = 0;
            $dataSO->objectruanganfk = $request['objectruanganasalfk'];
            $dataSO->objectruangantujuanfk = $request['objectruangantujuanfk'];
            $dataSO->keteranganorder = $request['keterangan'];
            $dataSO->objectkelompoktransaksifk = $this->settingFix('idKelompokTransaksiKonsul');
            $dataSO->tglorder = $request['tanggalKonsul'];
            $dataSO->rawatbersama = $request['rawatbersama'];
            $dataSO->konsultasi = $request['konsultasi'];
            $dataSO->lainlain = $request['lainlain'];
            $dataSO->objectkelasfk = $request['objectkelasfk'];
            $dataSO->totalbeamaterai = 0;
            $dataSO->totalbiayakirim = 0;
            $dataSO->totalbiayatambahan = 0;
            $dataSO->totaldiscount = 0;
            $dataSO->totalhargasatuan = 0;
            $dataSO->totalharusdibayar = 0;
            $dataSO->totalpph = 0;
            $dataSO->totalppn = 0;
            $dataSO->save();
            $this->LOGGING(
                'Konsultasi',
                $dataSO->norec,
                'strukorder_t',
                $namaLog . 'ke ruang ' . $request['ruangantujuan'] . ' pada Pasien ' . $request['namapasien'] . ' (' . $request['nocm'] . ') - ' . $request['noregistrasi']
            );
            $transMessage = "Sukses";
            DB::commit();
            $result = array(
                "status" => 200,
                "result" => array(
                    "as" => '@epic',
                    "data" => $dataSO
                ),
            );
        } catch (\Exception $e) {

            $transMessage = "Simpan Gagal";
            DB::rollBack();
            $result = array(
                "status" => 400,
                "result" => $e->getMessage() . ' ' . $e->getLine(), //$e->getMessage(). ' '. $e->getLine()
            );
        }
        return $this->respond($result['result'], $result['status'], $transMessage);
    }
    public function getOrderKonsul(Request $request)
    {
        $idProfile = (int) $this->kdProfile;
        $arrRuangId = [];
        $dateRange = [$request->tglAwal, $request->tglAkhir];
        // if (isset($request['idadmin']) && $request['idadmin'] != '') {
        // $dataruangan = DB::table('maploginusertoruangan_s as mlu')
        //     ->join('ruangan_m as ru', 'ru.id', '=', 'mlu.objectruanganfk')
        //     ->select('ru.id', 'ru.namaruangan')
        //     ->where('mlu.kdprofile', $idProfile)
        //     ->where('mlu.objectloginuserfk', $request['idadmin'])
        //     ->get();
        $dataruangan = DB::table('maploginusertoruangan_s as mlur')
            ->join('loginuser_s as lu', function ($j) {
                $j->on('lu.id', 'mlur.objectloginuserfk')->on('lu.kdprofile', 'mlur.kdprofile');
            })
            ->join('ruangan_m as ru', function ($j) {
                $j->on('ru.id', 'mlur.objectruanganfk')->on('ru.kdprofile', 'mlur.kdprofile');
            })
            ->select('ru.id', 'ru.namaruangan')
            ->where('lu.kdprofile', $this->kdProfile)
            ->where('mlur.statusenabled', true)
            ->where('lu.id', $this->getUserId())
            ->groupBy('ru.id', 'ru.namaruangan')
            ->get();

        if (count($dataruangan) > 0) {
            foreach ($dataruangan as $item) {
                $arrRuangId[] = $item->id;
            }
        }
        // }
        $data = DB::table('pasiendaftar_t as pd')
            ->Join('pasien_m as ps', 'ps.id', '=', 'pd.nocmfk')
            ->leftJoin('antrianpasiendiperiksa_t as apd', 'apd.noregistrasifk', '=', 'pd.norec')
            ->leftJoin('ruangan_m as ru', 'ru.id', '=', 'apd.objectruanganasalfk')
            ->leftJoin('ruangan_m as rutuju', 'rutuju.id', '=', 'apd.objectruanganfk')
            ->leftJoin('pegawai_m as pg', 'pg.id', '=', 'apd.objectpegawaifk')
            ->select(
                'ru.namaruangan as ruanganasal',
                'ru.id as objectruanganfk',
                'pg.namalengkap',
                'rutuju.namaruangan as ruangantujuan',
                'rutuju.id as objectruangantujuanfk',
                'pd.noregistrasi',
                'pd.tglregistrasi',
                'ps.nocm',
                'pd.norec as norec_pd',
                'ps.namapasien',
                'pg.id as pegawaifk',
                'apd.norec as norec',
                'pd.objectkelasfk as kelasfk_pd',
                'apd.tglmasuk as tglorder'
            )
            ->where('pd.kdprofile', $this->kdProfile)
            ->where('pd.statusenabled', true)
            ->where('apd.statusenabled', true)
            ->where('apd.objectasalrujukanfk', 23)
            ->orderBy('apd.tglmasuk', 'asc')
            ->whereBetween(DB::raw("CAST(apd.tglmasuk as Date)"), $dateRange);

        // if (isset($request['tglAwal']) && $request['tglAwal'] != '') {
        //     if ($request['isnotverif'] == true) {
        //         $data = $data->where('so.tglorder', '>=', $request['tglAwal'] . ' 00:00')->orWhere('apd.norec' ,null);
        //     }else{
        //         $data = $data->where('so.tglorder', '>=', $request['tglAwal'] . ' 00:00')->orWhere('apd.norec' ,'!=' ,null);
        //     }
        // }

        if (isset($request['dokterfk']) && $request['dokterfk'] != '') {
            $data = $data->where('pg.id', $request['dokterfk']);
        }
        // if (isset($request['ruanganfk']) && $request['ruanganfk'] != '') {
        //     $data = $data->where('so.objectruangantujuanfk', $request['ruanganfk']);
        // }
        if (isset($request['noregistrasi']) && $request['noregistrasi'] != '') {
            $data = $data->where('pd.noregistrasi', $request['noregistrasi']);
        } else {
            if (isset($request['ruanganfk']) && $request['ruanganfk'] != '') {
                $filter = true;
                $data = $data->whereIn('rutuju.id', explode(',', $request['ruanganfk']));
            } else {
                $filter = true;
                $data = $data->whereIn('rutuju.id', $arrRuangId);
            }
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

        // if (isset($request['tglAkhir']) && $request['tglAkhir'] != '') {
        //     if ($request['isnotverif'] == true) {
        //         $data = $data->where('so.tglorder', '<=', $request['tglAkhir'] . ' 23:59')->orWhere('apd.norec' ,null);
        //     }else{
        //         $data = $data->where('so.tglorder', '<=', $request['tglAkhir'] . ' 23:59')->orWhere('apd.norec' ,'!=' ,null);
        //     }
        // }
        // if (isset($request['ruanganfk']) && $request['ruanganfk'] != '') {
        //     $data = $data->where('so.objectruangantujuanfk', $request['ruanganfk']);
        // }
        // if (isset($request['namapasien']) && $request['namapasien'] != "" && $request['namapasien'] != "undefined") {
        //     $data = $data->where('ps.namapasien', 'ilike', '%' . $request['namapasien'] . '%');
        // }
        // if (isset($request['idpegawai']) && $request['idpegawai'] != '') {
        //     $data = $data->where('pg.id', $request['idpegawai']);
        // }
        // if (isset($request['nocm']) && $request['nocm'] != '') {
        //     $data = $data->where('ps.nocm', $request['nocm']);
        // }

        // if (isset($request['isnotverif']) && $request['isnotverif'] != '' &&  $request['isnotverif'] == 'true') {
        //     $data = $data->wherenull('apd.norec');
        // }
        // if (isset($request['idadmin']) && $request['idadmin'] != '') {
        //     $data = $data->whereIn('rutuju.id', $arrRuangId);
        // }
        $total = $data->count();
        if (isset($request['limit']) && $request['limit'] != '') {
            $data = $data->limit($request['limit']);
        }
        if (isset($request['offset']) && $request['offset'] != '') {
            $data = $data->offset($request['offset']);
        }
        $data = $data->get();
        $result = array(
            'data' => $data,
            'total' => $total
        );
        return $this->respond($result);
    }

    public function getHasilRadiologi(Request $request)
    {
        $norec = $request['norec_pd'];
        $hasil = DB::table('pelayananpasien_t as pp')
            ->leftjoin('antrianpasiendiperiksa_t as apd', 'pp.noregistrasifk', 'apd.norec')
            ->join('ruangan_m as ru', 'ru.id', 'apd.objectruanganfk')
            ->join('hasilradiologi_t as hr', 'pp.norec', 'hr.pelayananpasienfk')
            ->select(
                DB::raw(
                    "
                        '\n\nExpertise Radiologi Tgl : ' || TO_CHAR(hr.tanggalreport, 'DD/MM/YYYY HH24:MI:SS') ||'\n '|| hr.keterangan || '\n' AS hasilExpertise"
                    // 'Expertise Radiologi Tgl : ' || TO_CHAR(hr.tanggalreport, 'DD/MM/YYYY HH24:MI:SS') || E'\n' || hr.keterangan AS hasilExpertise"
                )
            )
            ->where('pp.noregistrasi', $request['noregistrasi'])
            ->where('ru.objectdepartemenfk', $this->settingFix('idDepartemenRadiologi'))
            ->get();

        return $this->respond($hasil);
    }

    public function hapusOrderKonsul(Request $request)
    {
        DB::beginTransaction();
        try {
            AntrianPasienDiperiksa::where('norec', $request['norec'])->where('kdprofile', $this->kdProfile)->update(['statusenabled' => false]);

            $this->LOGGING(
                'Konsultasi',
                $request['norec'],
                'strukorder_t',
                'Hapus konsultasi ke ' . $request['ruangantujuan'] . ' pada Pasien ' . $request['namapasien'] . ' (' . $request['nocm'] . ') - ' . $request['noregistrasi']
            );
            $transMessage = "Sukses";
            DB::commit();
            $result = array(
                "status" => 200,
                "result" => array(
                    "as" => '@epic',
                ),
            );
        } catch (\Exception $e) {

            $transMessage = "Hapus Gagal";
            DB::rollBack();
            $result = array(
                "status" => 400,
                "result" => $e->getMessage() . ' ' . $e->getLine()
            );
        }
        return $this->respond($result['result'], $result['status'], $transMessage);
    }

    public function jawabOrderKonsul(Request $request)
    {
        DB::beginTransaction();
        try {

            StrukOrder::where('norec', $request['norec_so'])->where('kdprofile', $this->kdProfile)->update([
                'keteranganlainnya' => $request['jawaban']
            ]);

            $transMessage = "Sukses";
            DB::commit();
            $result = array(
                "status" => 200,
                "result" => array(
                    "as" => '@epic',
                ),
            );
        } catch (\Exception $e) {

            $transMessage = "Jawab Konsul Gagal";
            DB::rollBack();
            $result = array(
                "status" => 400,
                "result" => null, //$e->getMessage(). ' '. $e->getLine()
            );
        }
        return $this->respond($result['result'], $result['status'], $transMessage);
    }

    public function uploadImageBarcode(Request $request)
    {
        return $request->file('image');
    }

    public function getResumeMedis(Request $request)
    {
        $data = DB::table('resumemedis_t as rm')
            ->Join('antrianpasiendiperiksa_t as apd', 'apd.norec', '=', 'rm.noregistrasifk')
            ->Join('pasiendaftar_t as pd', 'pd.norec', '=', 'apd.noregistrasifk')
            ->Join('pasien_m as ps', 'ps.id', '=', 'pd.nocmfk')
            ->leftJoin('ruangan_m as ru', 'ru.id', '=', 'apd.objectruanganfk')
            ->leftJoin('pegawai_m as pg', 'pg.id', '=', 'rm.pegawaifk')
            ->leftJoin('diagnosa_m as dg1', 'dg1.id', '=', 'rm.kddiagnosismasuk')
            ->leftJoin('diagnosa_m as dg2', 'dg2.id', '=', 'rm.kddiagnosisawal')
            ->leftJoin('diagnosa_m as dg3', 'dg3.id', '=', 'rm.kddiagnosistambahan')
            ->select(
                'rm.norec',
                'rm.tglresume',
                'ru.namaruangan',
                'pg.namalengkap as namadokter',
                'rm.namadiagnosatambahan',
                'rm.ringkasanriwayatpenyakit',
                'rm.pemeriksaanfisik',
                'rm.pemeriksaanpenunjang',
                'rm.hasilkonsultasi',
                'rm.terapi',
                'rm.diagnosisawal',
                'rm.diagnosissekunder',
                DB::raw("dg1.kddiagnosa || ' - ' || dg1.namadiagnosa as namadiagnosa1"),
                'rm.tindakanprosedur',
                // 'rm.diagnosismasuk', 'rm.diagnosistambahan', 'rm.alasandirawat',
                'rm.kddiagnosisawal',
                'rm.diagnosismasuk',
                'rm.kddiagnosismasuk',
                'rm.diagnosistambahan',
                'rm.kddiagnosistambahan',
                'rm.alasandirawat',
                DB::raw("dg2.kddiagnosa || ' - ' || dg2.namadiagnosa as namadiagnosa2"),
                'dg1.kddiagnosa as kddiagnosa1',
                // 'dg2.namadiagnosa as namadiagnosa2',
                'dg1.namadiagnosa as namadiagnosa1',
                'dg2.kddiagnosa as kddiagnosa2',
                // 'dg2.namadiagnosa as namadiagnosa2',
                'rm.tglkontrolpoli',
                'rm.rumahsakittujuan',
                'rm.alergi',
                'rm.instruksianjuran',
                'rm.hasillab',
                'rm.kondisiwaktukeluar',
                'rm.pengobatandilanjutkan',
                'rm.koderesume',
                'rm.pegawaifk',
                'pd.noregistrasi',
                'pd.tglregistrasi',
                'ps.nocm',
                'rm.noregistrasifk',
                'rm.pemeriksaanfisikreal',
                'rm.terapipulang',
                'ps.namapasien'
            )
            ->where('rm.kdprofile', $this->kdProfile)
            ->where('rm.statusenabled', true)
            // ->where('ps.nocm', $nocm)
            ->where('rm.keteranganlainnya', 'RawatInap');

        if (isset($request['nocm']) && $request['nocm'] != '') {
            $data = $data->where('ps.nocm', $request['nocm']);
        }
        $data = $data->get();
        $result = array(
            'data' => $data,
        );

        return $this->respond($result);
    }

    public function saveResumeMedis(Request $request)
    {
        $kdProfile = (int) $this->kdProfile;
        DB::beginTransaction();
        try {
            if ($request['norec'] == '') {
                $RekamMedis = new ResumeMedis();
                $RekamMedis->norec = $RekamMedis->generateNewId();
                $RekamMedis->kdprofile = $kdProfile;
                $RekamMedis->statusenabled = true;
                $kdResume = $this->SEQUENCE(new ResumeMedis, 'koderesume', 12, 'RI' . date('ym'), $kdProfile);
            } else {
                $RekamMedis = ResumeMedis::where('norec', $request['norec'])->where('kdprofile', $kdProfile)->first();
                $kdResume = $RekamMedis->koderesume;
                ResumeMedisDetail::where('resumefk', $request['norec'])->where('kdprofile', $kdProfile)->delete();
            }
            $RekamMedis->tglresume = date('Y-m-d H:i:s');
            $RekamMedis->diagnosismasuk = $request['diagnosismasuk'];

            if (isset($request['kddiagnosismasuk']) && $request['kddiagnosismasuk'] != "") {
                $RekamMedis->kddiagnosismasuk = $request['kddiagnosismasuk'];
            }
            $RekamMedis->diagnosisawal = $request['diagnosisawal'];
            if (isset($request['kddiagnosisawal']) && $request['kddiagnosisawal'] != "") {
                $RekamMedis->kddiagnosisawal = $request['kddiagnosisawal'];
            }

            $RekamMedis->namadiagnosatambahan = $request['namadiagnosatambahan'];
            $RekamMedis->tindakanprosedur = $request['tindakanprosedur'];
            $RekamMedis->alasandirawat = $request['alasandirawat'];
            $RekamMedis->ringkasanriwayatpenyakit = $request['ringkasanriwayatpenyakit'];
            $RekamMedis->pemeriksaanfisik = $request['pemeriksaanfisik'];
            $RekamMedis->pemeriksaanpenunjang = $request['pemeriksaanpenunjang'];
            $RekamMedis->terapi = $request['terapi'];

            // $RekamMedis->terapipulang = $request['terapipulang'];
            $RekamMedis->hasilkonsultasi = $request['hasilkonsultasi'];
            $RekamMedis->kondisiwaktukeluar = $request['kondisiwaktukeluar'];
            $RekamMedis->instruksianjuran = $request['instruksianjuran'];
            $RekamMedis->pengobatandilanjutkan = $request['pengobatandilanjutkan'];
            // $RekamMedis->pemeriksaanfisikreal = $request['pemeriksaanfisikreal'];
            $RekamMedis->tglkontrolpoli = $request['tglkontrolpoli'];
            $RekamMedis->rumahsakittujuan = $request['rumahsakittujuan'];
            $RekamMedis->pegawaifk = $this->getPegawaiId();
            $RekamMedis->noregistrasifk = $request['noregistrasifk'];
            $RekamMedis->keteranganlainnya = 'RawatInap';
            $RekamMedis->koderesume = $kdResume;
            if (isset($request['ascvdfk'])) {
                $RekamMedis->ascvdfk = $request['ascvdfk'];
            }
            // return $RekamMedis;
            $RekamMedis->save();
            $transMessage = "Sukses";
            DB::commit();
            $result = array(
                "status" => 200,
                "result" => array(
                    "as" => '@epic',
                ),
            );
        } catch (\Exception $e) {

            $transMessage = "Simpan Gagal";
            DB::rollBack();
            $result = array(
                "status" => 400,
                "result" => $e->getMessage() . ' ' . $e->getLine()
            );
        }
        return $this->respond($result['result'], $result['status'], $transMessage);
    }
    public function hapusResumeMedis(Request $request)
    {
        DB::beginTransaction();
        try {

            $dataRM = ResumeMedis::where('norec', $request['norec'])->where('kdprofile', $this->kdProfile)->update(['statusenabled' => false]);

            $this->LOGGING(
                'Resume Medis',
                $request['norec'],
                'resumemedis_t',
                'Hapus Resume Medis ' . ' pada Pasien ' . $request['namapasien'] . ' (' . $request['nocm'] . ') - ' . $request['noregistrasi']
            );
            $transMessage = "Sukses";
            DB::commit();
            $result = array(
                "status" => 200,
                "result" => array(
                    "as" => '@epic',
                ),
            );
        } catch (\Exception $e) {

            $transMessage = "Hapus Gagal";
            DB::rollBack();
            $result = array(
                "status" => 400,
                "result" => $e->getMessage() . ' ' . $e->getLine(), //$e->getMessage(). ' '. $e->getLine()
            );
        }
        return $this->respond($result['result'], $result['status'], $transMessage);
    }
    public function getTotalBilling(Request $request)
    {
    }
    public function listDiagnosaKeperawatan(Request $r)
    {

        $res['diagnosaKeperawatan'] = DB::table('diagnosakeperawatan_m')
            ->select('id', 'diagnosakep');


        if (isset($r['query']) && $r['query'] != '' && $r['query'] != 'undefined') {
            $res['diagnosaKeperawatan'] = $res['diagnosaKeperawatan']->where('diagnosakep', 'ilike', '%' . $r['query'] . '%');
        }
        $res['diagnosaKeperawatan'] = $res['diagnosaKeperawatan']->orderby('diagnosakep');
        $res['diagnosaKeperawatan'] = $res['diagnosaKeperawatan']->get();
        return $this->respond($res);
    }
    public function listDiagnosaSdki(Request $r)
    {

        $res['diagnosasdki'] = DB::table('diagnosasdki_m')
            ->select('id', 'deskripsidiagnosakep');


        if (isset($r['query']) && $r['query'] != '' && $r['query'] != 'undefined') {
            $res['diagnosasdki'] = $res['diagnosasdki']->where('deskripsidiagnosakep', 'ilike', '%' . $r['query'] . '%');
        }
        $res['diagnosasdki'] = $res['diagnosasdki']->orderby('deskripsidiagnosakep');
        $res['diagnosasdki'] = $res['diagnosasdki']->get();
        return $this->respond($res);
    }
    public function listSiki(Request $r)
    {
        $res['siki'] = DB::table('siki_m')
            ->select('id', 'name', 'objectdiagnosakepfk', 'type');

        // Tambahkan filter berdasarkan diagnosa yang dipilih
        if (isset($r['query']) && $r['query'] != '' && $r['query'] != 'undefined') {
            $res['siki'] = $res['siki']->where('objectdiagnosakepfk', '=', $r['query']);
        }

        $res['siki'] = $res['siki']->orderby('id')->get();
        return $this->respond($res);
    }


    public function listTujuanKeperawatan(Request $r)
    {
        $res['tujuanPerawat'] = DB::table('tujuanperawatan_m');
        if (isset($r['objectdiagnosakepfk']) && $r['objectdiagnosakepfk'] != '') {
            $res['tujuanPerawat'] = $res['tujuanPerawat']->where('objectdiagnosakepfk', $r['objectdiagnosakepfk']);
        }
        $res['tujuanPerawat'] = $res['tujuanPerawat']
            ->orderby('id')
            ->get();
        return $this->respond($res);
    }
    public function listIntervensiKeperawatan(Request $r)
    {
        $res['intervensi'] = DB::table('intervensi_m as in')
            ->select('name', 'kodeexternal', 'id')
            ->where('kdprofile', $this->kdProfile)
            ->where('statusenabled', true);
        // ->where('objecttujuankeperawatan',$r['keperawatanfk'])
        // ->when($r['keperawatanfk'], function ($query) use ($r) {
        //     $query->where('objecttujuankeperawatan', $r['keperawatanfk'])->orWhere('objecttujuankeperawatan', ['keperawatanfk']);
        // })

        // $res['intervensi'] = DB::table('intervensi_m');
        if (isset($r['keperawatanfk']) && $r['keperawatanfk'] != '') {
            $res['intervensi'] = $res['intervensi']->where('objecttujuankeperawatan', $r['keperawatanfk']);
        }
        $res['intervensi'] = $res['intervensi']->get();
        // $res['intervensi'] = $res['intervensi']
        //     ->orderby('id')
        //     ->get();
        return $this->respond($res);
    }
    public function implementasiKeperawatan(Request $r)
    {
        $res['implementasi'] = DB::table('implementasi_m');
        if (isset($r['objectintervensifk']) && $r['objectintervensifk'] != '') {
            $res['implementasi'] = $res['implementasi']->where('objectintervensifk', $r['objectintervensifk']);
        }
        $res['implementasi'] = $res['implementasi']
            ->orderby('id')
            ->get();
        return $this->respond($res);
    }
    public function getResep(Request $r)
    {
        $data = DB::table('strukorder_t as so')
            ->join('pasiendaftar_t as pd', 'pd.norec', '=', 'so.noregistrasifk')
            ->join('orderpelayanan_t as op', 'op.noorderfk', 'so.norec')
            ->join('produk_m as pr', 'op.objectprodukfk', 'pr.id')
            ->select('pr.namaproduk')
            ->where('so.statusenabled', true)
            ->where('so.kdprofile', $this->kdProfile)
            // ->where('so.keteranganorder', '=', 'Order Farmasi')
            ->where('so.noregistrasifk', $r->input('norec_pd'));

        $data = $data->get();

        return $this->respond($data);
    }
    public function getPetugasPe(Request $r)
    {
        $data = DB::table('strukorder_t as so')
            ->join('pasiendaftar_t as pd', 'pd.norec', '=', 'so.noregistrasifk')
            ->leftjoin('pegawai_m as pg', 'pg.id', 'so.objectpegawaiorderfk')
            ->select('pg.namalengkap')
            ->where('so.noregistrasifk', $r->input('norec_pd'));

        $data = $data->get();

        return $this->respond($data);
    }

    public function getRiwayatMenyusui(Request $r)
    {
        $res = DB::connection('mongodb')
            ->table('FormulirKajianRiwayatMenyusui')
            ->where('pasien.nocmfk', $r['nocmfk'])
            ->where('profile.kdprofile', $this->kdProfile)
            ->where('statusenabled', true);
        if (isset($r['norec_pd']) && $r['norec_pd'] != '') {
            $res = $res->where('registrasi.norec_pd', $r['norec_pd']);
        }
        $res = $res->first();

        unset($res['_id']);
        return $this->respond($res);
    }

    public function SaveTransaksiEMROdontogram(Request $request)
    {
        DB::beginTransaction();
        $dataReq = $request->all();
        $head = $dataReq['head'];
        $data = $dataReq['data'];
        //        emrodontogram_t

        try {

            $EMR = EMROdontogram::where('nocm', $head['nocm'])->delete();

            foreach ($data as $item) {
                //                if ($head['norec_emr'] == '-') {
                $noemr = $this->generateCode(new EMROdontogram, 'noemr', 10, 'DG' . $this->getDateTime()->format('ym') . '/', $this->kdProfile);
                $EMR = new EMROdontogram();
                $norecHead = $EMR->generateNewId();
                $EMR->norec = $norecHead;
                $norecTehMenikitunyaeuy = $norecHead;
                $EMR->norec = $norecTehMenikitunyaeuy;
                $EMR->kdprofile = $this->kdProfile;
                $EMR->statusenabled = 1;
                $EMR->noemr = $noemr;
                //                } else {

                $EMR->noregistrasifk = $head['norec_pd'];
                $EMR->nocm = $head['nocm'];
                $EMR->namapasien = $head['namapasien'];
                $EMR->jeniskelamin = $head['jeniskelamin'];
                $EMR->noregistrasi = $head['noregistrasi'];
                $EMR->umur = $head['umur'];
                $EMR->kelompokpasien = $head['kelompokpasien'];
                $tglregistrasi = date('Y-m-d H:i:s', strtotime($head['tglregistrasi'])); //;
                $EMR->tglregistrasi = $tglregistrasi;
                $EMR->norec_apd = $head['norec'];
                $EMR->namakelas = $head['namakelas'];
                $EMR->namaruangan = $head['namaruangan'];
                $EMR->tglemr = $this->getDateTime()->format('Y-m-d H:i:s');

                $EMR->colour = $item['colour'];
                $EMR->width = $item['width'];
                $EMR->height = $item['height'];
                $EMR->top = $item['top'];
                $EMR->left = $item['left'];
                $EMR->id = $item['id'];
                $EMR->brs = $item['brs'];
                $EMR->kol = $item['kol'];
                $EMR->seg = $item['seg'];
                $EMR->type = $item['type'];
                $EMR->color = $item['color'];
                $EMR->save();
            }

            DB::commit();
            $result = array(
                "status" => 201,
                "message" => "Simpan Odontogram Berhasil",
                "data" => $EMR,
                "as" => 'as@epic',
            );
        } catch (\Exception $e) {
            DB::rollBack();
            $result = array(
                "status" => 400,
                "data" => $e->getMessage(),
                "message" => "Simpan Odontogram Gagal",
                "as" => 'as@epic',
            );
        }

        return $this->respond($result, $result['status'], $result['message']);
    }

    public function getDataOdontogram(Request $request)
    {
        $data = DB::table('emrodontogram_t as emr')
            ->select(
                'emr.colour',
                'emr.width',
                'emr.height',
                'emr.top',
                'emr.left',
                'emr.id',
                'emr.brs',
                'emr.kol',
                'emr.seg',
                'emr.type',
                'emr.color'
            )
            ->where('emr.kdprofile', $this->kdProfile)
            ->where('emr.nocm', $request['nocm'])
            ->get();

        foreach ($data as $itm) {
            $dt2[] = array(
                'colour' => $itm->colour,
                'width' => (int) $itm->width,
                'height' => (int) $itm->height,
                'top' => (int) $itm->top,
                'left' => (int) $itm->left,
                'id' => (int) $itm->id,
                'brs' => (int) $itm->brs,
                'kol' => (int) $itm->kol,
                'seg' => (int) $itm->seg,
                'type' => $itm->type,
                'color' => $itm->color
            );
        }

        $result = array(
            'data' => isset($dt2) ? $dt2 : null,
            'message' => 'as@epic',
        );
        return $this->respond($result);
    }
    public function getEMRTabs(Request $r)
    {
        if (empty($r['collection'])) {
            return $this->respond([]);
        }
        $nocmfk = strlen($r['nocmfk']) >= 32 ? $r['nocmfk'] : $r['nocmfk'];

        $res = DB::connection('mongodb')
            ->table($r['collection'])
            ->where('pasien.nocmfk', $nocmfk)
            ->where('profile.kdprofile', $this->kdProfile)
            ->where('statusenabled', true)
            ->whereNull('namatemplate');

        if (isset($r['emrpasienfk']) && $r['emrpasienfk'] != '') {
            $res = $res->where('emrpasienfk', '=', $r['emrpasienfk']);
        }
        if (isset($r['norec_pd']) && $r['norec_pd'] != '') {
            $res = $res->where('registrasi.norec_pd', $r['norec_pd']);
        }
        if (isset($r['index_tabs']) && $r['index_tabs'] != '') {
            $res = $res->where('index_tabs', (int) $r['index_tabs']);
        }
        $res = $res->orderByDesc('index_tabs');
        $res = $res->first();
        $index = 1;
        if (!empty($res)) {
            $index = isset($res['index_tabs']) ? (int) $res['index_tabs'] : 1;
        }

        return $this->respond($index);
    }
    private function findInvalidDates($data)
    {
        $invalidDatesCount = 0;
        $arr = [];
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $count = $this->findInvalidDates($value);
                $invalidDatesCount += $count;
                $arr = array_merge($arr, $value);
            } else {
                if ($value === 'Invalid date') {
                    $invalidDatesCount++;
                    array_push($arr, $value);
                }
            }
        }

        return $invalidDatesCount;
    }
    public function saveCPPTBackup($data)
    {
        DB::beginTransaction();
        try {
            DB::connection('mongodb')
                ->table('CPPTDetail_Backup')
                ->insert($data);
        } catch (\Exception $e) {
            DB::rollBack();
        }
    }

    public function updateCPPT(Request $r)
    {
        DB::beginTransaction();
        try {
            if ($r['method'] == 'hapus') {
                DB::connection('mongodb')
                    ->table('CPPTDetail')
                    ->where('uuid', $r['uuid'])
                    ->update(['statusenabled' => false]);
                $transMessage = "Hapus Berhasil";
                $result = array(
                    "status" => 200,
                    "result" => 'Berhasil'
                );
                DB::commit();
            }
            if ($r['method'] == 'update') {
                DB::connection('mongodb')
                    ->table('CPPTDetail')
                    ->where('uuid', $r['data']['uuid'])
                    ->update($r['data']);
                $transMessage = "Update Berhasil";
                $result = array(
                    "status" => 200,
                    "result" => 'Berhasil'
                );
                DB::commit();
            }
        } catch (\Exception $e) {
            $transMessage = "Gagal";
            DB::rollBack();
            $result = array(
                "status" => 400,
                "result" => $e->getMessage() . ' ' . $e->getLine()
            );
        }
        return $this->respond($result['result'], $result['status'], $transMessage);
    }
    public function updateCPPTInvalid(Request $r)
    {
        DB::beginTransaction();
        try {

            $nocmfk = strlen($r['nocmfk']) >= 32 ? $r['nocmfk'] : $r['nocmfk'];

            $res = DB::connection('mongodb')
                ->table('CatatanPerkembanganPasienTerintegrasi')
                ->where('pasien.nocmfk', $nocmfk)
                ->where('profile.kdprofile', $this->kdProfile)
                ->where('statusenabled', true);
            $res = $res->orderByDesc('created_at');
            $res = $res->get();

            $cppt = DB::connection('mongodb')
                ->table('CPPTDetail')
                ->where('nocmfk', $nocmfk)
                ->where('kdprofile', $this->kdProfile)
                ->where('statusenabled', true)
                ->where('tgl', 'Invalid date');
            $cppt = $cppt->get();
            $invalid = [];

            if (count($res) > 0) {
                $res = $res->toArray();
                $cppt = $cppt->toArray();
                foreach ($cppt as $z => $x) {
                    foreach ($res as $k => $rr) {
                        if (
                            $rr['emrpasienfk'] == $cppt[$z]['emrpasienfk']
                            && $cppt[$z]['tgl'] == 'Invalid date'
                        ) {
                            $cppt[$z]['created_at_head'] = $rr['created_at'];
                            $cppt[$z]['updated_at_head'] = $rr['updated_at'];
                            $cppt[$z]['pasien'] = [
                                'nocm' => $rr['pasien']['nocm'],
                                'namapasien' => $rr['pasien']['namapasien'],
                                'uuid' => $cppt[$z]['uuid']
                            ];
                            $invalid[] = $cppt[$z];
                        }
                    }
                }
            }
            $pasienUpdated = [];
            $updated = 0;
            foreach ($invalid as $item) {
                $item['tgl'] = $item['created_at_head'];
                $item['tglVerifikasi'] = $item['created_at_head'];
                $p = DB::connection('mongodb')
                    ->table('CPPTDetail')
                    ->where('kdprofile', $this->kdProfile)
                    ->where('emrpasienfk', $item['emrpasienfk'])
                    ->where('uuid', $item['uuid'])
                    ->update($item);
                if ($p > 0) {
                    $pasienUpdated[] = $item['pasien'];
                    $updated++;
                }
            }



            DB::commit();
            $result = array(
                "status" => 200,
                "message" => "Sukses",
                "result" => [
                    "updated" => $updated,
                    "pasien" => $pasienUpdated,
                ],
                "as" => '@epic',
            );
        } catch (\Exception $e) {
            DB::rollBack();
            $result = array(
                "status" => 400,
                "message" => "Simpan Gagal " . $e->getMessage(),
                "result" => null,
                "as" => '@epic',
            );
        }

        return $this->respond($result['result'], $result['status'], $result['message']);
    }
    public function saveEMRIGD(Request $r)
    {
        DB::beginTransaction();
        try {
            $data = $r->input('data');
            $invalidDates = $this->findInvalidDates($data);
            if ($invalidDates > 0) {
                $transMessage = "Simpan Gagal Format Tanggal Tidak Sesuai !";
                DB::rollBack();
                $result = array(
                    "status" => 400,
                    "result" => []

                );
                return $this->respond($result['result'], $result['status'], $transMessage);
            }
            $now = date('Y-m-d H:i:s');
            // $registrasi = $r->input('data')['registrasi'];
            $pasien = $r->input('data');
            if ($r['norec_emr'] == '') {
                $noemr = $this->SEQUENCE(new EMRPasien(), 'noemr', 15, 'MR' . date('ym') . '/', $this->kdProfile);
                $EMR = new EMRPasien();
                $norec = $EMR->generateNewId();
                $EMR->norec = $norec;
                $EMR->kdprofile = $this->kdProfile;
                $EMR->statusenabled = true;
                $EMR->noregistrasifk = null;
                $EMR->noregistrasi = null;
            } else {
                $EMR = EMRPasien::where('norec', $r['norec_emr'])
                    ->where('kdprofile', $this->kdProfile)
                    ->first();
                // return $EMR;
                $noemr = $EMR['noemr'];
                $norec = $EMR['norec'];
            }
            ;

            $EMR->noemr = $noemr;
            $EMR->emrfk = 0;
            $EMR->nocm = $pasien['nocm'];
            $EMR->nocmfk = null;
            $EMR->namapasien = $pasien['namapasien'];
            $EMR->jeniskelamin = $pasien['jeniskelamin'];
            // $EMR->umur = $r['umur'];
            $EMR->tgllahir = $pasien['tgllahir'];
            $EMR->notelepon = isset($pasien['notelepon']) ? $pasien['notelepon'] : null;
            $EMR->alamat = $pasien['alamat'];
            // $EMR->kelompokpasien = $registrasi['kelompokpasien'];
            $EMR->tglregistrasi = $now;
            // $EMR->norec_apd = $registrasi['norec_apd'];
            // $EMR->namakelas = $registrasi['namakelas'];
            // $EMR->namaruangan = $registrasi['namaruangan'];
            $EMR->jenisemr = $r['jenis_emr'];
            $EMR->pegawaifk = $this->getPegawai()->id;
            $EMR->tglemr = $now;
            $EMR->save();

            // MONGO
            $data = $r->input('data');
            if (isset($data['nocm'])) {
                unset($data['nocm']);
            }

            $data['user_input'] = array(
                'id' => $this->getUserId(),
                'namauser' => $this->getUsername(),
                'pegawaifk' => $this->getPegawai()->id,
                // 'kelompokPegawai' => $r['userBy'],
                'namalengkap' => $this->getPegawai()->namalengkap,
            );
            $data['profile'] = array(
                'kdprofile' => $this->kdProfile,
                'namaprofile' => $this->getProfile()->namalengkap,
            );
            if ($r['userBy']) {
                $data['userBy'] = $r['userBy'];
            }
            $data['statusenabled'] = true;
            $data['noemr'] = $EMR->noemr;
            $data['emrpasienfk'] = $norec;

            if ($r->input('id') == '') {

                $data['id'] = $this->Uuid4();
                $data['created_at'] = $now;
                $data['updated_at'] = null;
                DB::connection('mongodb')
                    ->table($r->input('collection'))
                    ->insert($data);
            } else {
                $data['updated_at'] = $now;
                $update = DB::connection('mongodb')
                    ->table($r->input('collection'))
                    ->where('id', $r->input('id'))
                    ->update($data);
            }

            $formExist = DB::connection('mongodb')
                ->table('#ResumeEMR')
                ->where('emrpasienfk', $norec)
                ->where('table', $r->input('collection'))
                ->where('namapasien', $r['namapasien'])
                ->first();

            // return $EMR;

            if (empty($formExist)) {
                $resume = array(
                    'id' => $this->Uuid4(),
                    'kdprofile' => $this->kdProfile,
                    'statusenabled' => true,
                    // 'noregistrasifk' => $registrasi['norec_pd'],
                    // 'nocmfk' => $pasien['nocmfk'],
                    'emrpasienfk' => $norec,
                    'table' => $r->input('collection'),
                    'last_update' => $now,
                    'author' => $this->getPegawai()->namalengkap,
                    'url_form' => $r['url_form'],
                    'namaemr' => $r['name_form'],
                    'noemr' => $EMR->noemr,
                    'icon' => isset($r['icon']) ? $r['icon'] : null,
                );

                DB::connection('mongodb')
                    ->table('#ResumeEMR')
                    ->insert($resume);
            } else {
                $resume = array(
                    'kdprofile' => $this->kdProfile,
                    'statusenabled' => true,
                    // 'noregistrasifk' => $registrasi['norec_pd'],
                    // 'nocmfk' => $pasien['nocmfk'],
                    'emrpasienfk' => $norec,
                    'table' => $r->input('collection'),
                    'last_update' => $now,
                    'author' => $this->getPegawai()->namalengkap,
                    'url_form' => $r['url_form'],
                    'namaemr' => $r['name_form'],
                    'noemr' => $EMR->noemr,
                    'icon' => isset($formExist['icon']) ? $formExist['icon'] : null,
                );
                DB::connection('mongodb')
                    ->table('#ResumeEMR')
                    ->where('id', $formExist['id'])
                    ->update($resume);
            }


            $transMessage = "Sukses";
            DB::commit();

            $result = array(
                "status" => 200,
                "result" => array(
                    "norec_emr" => $EMR->norec,
                    "noemr" => $EMR->noemr,
                    "id" => $data['id'],

                    "as" => '@epic',
                ),
            );
        } catch (\Exception $e) {

            $transMessage = "Simpan Gagal";
            DB::rollBack();
            $result = array(
                "status" => 400,
                "result" => $e->getMessage() . ' ' . $e->getLine()

            );
        }
        return $this->respond($result['result'], $result['status'], $transMessage);
    }
    public function getEMRIGD(Request $r)
    {
        if (empty($r['collection'])) {
            return $this->respond([]);
        }
        // $nocmfk = strlen($r['nocmfk']) >= 32 ? $r['nocmfk'] : (int)$r['nocmfk'];
        $namapasien = $r['qnama'];

        $res = DB::connection('mongodb')
            ->table($r['collection'])
            ->where('qnama', $namapasien)
            ->where('profile.kdprofile', $this->kdProfile)
            ->where('statusenabled', true);
        if (isset($r['noemr']) && $r['noemr'] != '') {
            $res = $res->where('noemr', '=', $r['noemr']);
        }
        if ($r['kelompokPegawai'] == 'perawat') {
            $res = $res->where('userBy', '=', $r['kelompokPegawai']);
        }
        if (isset($r['norec_pd']) && $r['norec_pd'] != '') {
            $res = $res->where('registrasi.norec_pd', $r['norec_pd']);
        }
        if (isset($r['index_tabs']) && $r['index_tabs'] != '') {
            $res = $res->where('index_tabs', (int) $r['index_tabs']);
        }
        $res = $res->orderByDesc('created_at');
        $res = $res->get();
        if (count($res) > 0) {
            $res = $res->toArray();
            foreach ($res as $k => $rr) {
                unset($res[$k]['_id']);
            }
        }

        return $this->respond($res);
    }

    public function getSkemaPenyinaran(Request $request)
    {
        $data = DB::table('FormulirSkemaPenyinaranRadioterapi as emrdk');
        // ->where('emrdk.statusenabled', true)
        // ->where('emrdk.kdprofile', $this->kdProfile);


        if (isset($request['nocm']) && $request['nocm'] != '') {
            $data = $data->where('emrdk.nocm', $request['nocm']);
        }
        if (isset($request['noregistrasi']) && $request['noregistrasi'] != '') {
            $data = $data->where('emrdk.noregistrasi', $request['noregistrasi']);
        }

        $data = $data->get();

        $result = array(
            'data' => $data,
            'message' => '@epic',
        );
        return $this->respond($result);
    }

    public function hapusSkemaPenyinaran(Request $request)
    {
        DB::beginTransaction();
        try {

            $emrDkmn = FormulirSkemaPenyinaranRadioterapi::where('norec', $request['norec'])
                ->first();

            $path = Storage::disk('public')->getAdapter()->getPathPrefix() . $emrDkmn->file;

            if (File::exists($path)) {
                File::delete($path);
            }
            $emrDkmn->delete();

            $transMessage = "Sukses";
            DB::commit();
            $result = array(
                "status" => 200,
                "result" => array(
                    "as" => '@epic',
                ),
            );
        } catch (Exception $e) {

            $transMessage = "Hapus Gagal";
            DB::rollBack();
            $result = array(
                "status" => 400,
                "result" => $e->getMessage() . ' ' . $e->getLine()

            );
        }
        return $this->respond($result['result'], $result['status'], $transMessage);
    }

    public function saveSkemaPenyinaran(Request $request)
    {
        DB::beginTransaction();
        try {
            $kdProfile = $this->kdProfile;
            $uploadBerkasPasien = $request->file('filePasien');
            $path = 'berkaspasien/' . $request['nocm'];
            $dataDokumen = DB::table('FormulirSkemaPenyinaranRadioterapi as emrdkt')
                ->where('emrdkt.norec', '=', $request['norec'])
                // ->where('emrdkt.kdprofile', $kdProfile)
                // ->where('emrdkt.statusenabled', true)
                ->first();

            if ($dataDokumen == null) {

                if (!empty($uploadBerkasPasien)) {
                    $extension = $uploadBerkasPasien->getClientOriginalExtension();
                    $filename = $request['norec'] . '_' . date('YmdHis') . '.' . $extension;

                    $emrDkmn = new FormulirSkemaPenyinaranRadioterapi();
                    $norecDkmn = $emrDkmn->generateNewId();
                    $emrDkmn->norec = $norecDkmn;
                    // $emrDkmn->kdprofile = $kdProfile;
                    // $emrDkmn->statusenabled = true;
                    $emrDkmn->noregistrasi = $request['noregistrasi'];
                    $emrDkmn->nocm = $request['nocm'];
                    $emrDkmn->norec_apd = $request['norec_apd'];
                    $emrDkmn->tglemr = date('Y-m-d H:i:s');
                    $emrDkmn->file = $path . '/' . $filename;
                    $emrDkmn->namafile = $filename;
                    // $emrDkmn->deskripsi = $request['keterangan'];
                    $emrDkmn->tanggalKunjungan = $request['tanggalKunjungan'];
                    $emrDkmn->PeyanggaKepala = $request['PeyanggaKepala'];
                    $emrDkmn->PenyanggaBadan = $request['PenyanggaBadan'];
                    $emrDkmn->PeyanggaTangan = $request['PeyanggaTangan'];
                    $emrDkmn->Aksesoris = $request['Aksesoris'];
                    $emrDkmn->Lainnya = $request['Lainnya'];


                    $emrDkmn->save();
                    // $request->file('filePasien')->move($path, $filename);
                    $request->fPut(
                        $path . '/' . $filename,
                        File::get($request->file('filePasien')->getRealPath()),
                        'public'
                    );
                }
            } else {
                $emrDkmn = FormulirSkemaPenyinaranRadioterapi::where('norec', $request['norec'])->first();
                $emrDkmn->Lainnya = $request['Lainnya'];
                $extension = explode('.', $emrDkmn->file)[1];

                if (!empty($emrDkmn)) {
                    if (File::exists($emrDkmn->file)) {
                        $filename = 'deleted_' . date('YmdHis') . '.' . $extension;
                        $request->fPut(
                            $path . '/' . $filename,
                            File::get($request->file('filePasien')->getRealPath()),
                            'public'
                        );
                        // File::delete($emrDkmn->file);
                    }


                    $filename = $request['norec'] . '_' . date('YmdHis') . '.' . $extension;

                    $emrDkmn->file = $path . '/' . $filename;
                    // $request->file('filePasien')->move($path, $filename);
                    $request->fPut(
                        $path . '/' . $filename,
                        File::get($request->file('filePasien')->getRealPath()),
                        'public'
                    );
                } else {

                    if (File::exists($emrDkmn->file)) {
                        $filename = $request['norec'] . '_' . date('YmdHis') . '.' . $extension;
                        File::move($emrDkmn->file, $path . '/' . $filename);
                        $emrDkmn->file = $path . '/' . $filename;
                    }
                }

                $emrDkmn->norec = $request['norec'] . '_' . date('YmdHis') . '.' . $extension;
                // $emrDkmn->objectberkaspasien = $request['objectberkaspasien'];
                // if (isset($request['nama'])) {
                //     $emrDkmn->nama = $request['nama'];
                // }
                $emrDkmn->save();
            }
            PasienDaftar::where('noregistrasi', $request['noregistrasi'])->update([
                'isberkas' => true,
            ]);

            $transMessage = "Sukses";
            DB::commit();
            $result = array(
                "status" => 200,
                "result" => array(
                    "as" => '@epic',
                ),
            );
        } catch (\Exception $e) {

            $transMessage = "Simpan Gagal";
            DB::rollBack();
            $result = array(
                "status" => 400,
                "result" => $e->getMessage() . ' ' . $e->getLine(),

            );
        }
        return $this->respond($result['result'], $result['status'], $transMessage);
    }

    public function getMedisDiagnosa(Request $r)
    {
        $nocmfk = $r['nocmfk'];
        if (!isset($nocmfk)) {
            return $this->respond([]);
        }

        $medis = null;
        if (isset($r['type']) && $r['type'] == 'POLI') {
            $medis = DB::connection('mongodb')
                ->table('AsesmenMedisRawatJalan')
                ->where('pasien.nocmfk', $nocmfk)
                ->where('profile.kdprofile', $this->kdProfile)
                ->where('statusenabled', true)
                ->select(
                    'diagnosaIcd10',
                    'TADiagnosa'
                )
                ->latest()
                ->first();
        } else if (isset($r['type']) && $r['type'] == 'IGD') {
            $medis = DB::connection('mongodb')
                ->table('AsesmenAwalMedisGawatDarurat')
                ->where('pasien.nocmfk', $nocmfk)
                ->where('profile.kdprofile', $this->kdProfile)
                ->where('statusenabled', true)
                ->select(
                    'diagnosaIcd10',
                    'TADiagnosis'
                )
                ->latest()
                ->first();
            $medis['TADiagnosa'] = $medis['TADiagnosis'];
        } else if (isset($r['type']) && $r['type'] == 'NUKLIR') {
            $medis = DB::connection('mongodb')
                ->table('AsesmenMedisKedokteranNuklir')
                ->where('pasien.nocmfk', $nocmfk)
                ->where('profile.kdprofile', $this->kdProfile)
                ->where('statusenabled', true)
                ->select(
                    'diagnosaIcd10',
                    'TADiagnosa'
                )
                ->latest()
                ->first();
            $medis['TADiagnosa'] = $medis['TADiagnosa'];
        }
        unset($medis['_id']);
        $medis != null ? (count($medis) > 0 ? $medis : $medis = null) : $medis = null;
        return $this->respond($medis);
    }

    public function getEMRLab(Request $r)
    {
        try {
            $data = StrukOrder::where('noregistrasifk', $r['norec_pd'])
                ->where('norec_apd', $r['norec_apd'])
                ->where('noregistrasi', $r['noregistrasi'])
                ->where('keteranganorder', 'ilike', '%Laboratorium%')
                ->first();

            return $this->respond($data);
        } catch (\Exception $e) {
            return $this->respond([
                "status" => 500,
                "message" => $e->getMessage()
            ], 500);
        }
    }

    public function getEMRRadio(Request $r)
    {
        try {
            $data = StrukOrder::where('noregistrasifk', $r['norec_pd'])
                ->where('norec_apd', $r['norec_apd'])
                ->where('noregistrasi', $r['noregistrasi'])
                ->where('keteranganorder', 'ilike', '%Radiologi%')
                ->select('catatanklinis')
                ->first();

            return $this->respond($data);
        } catch (\Exception $e) {
            return $this->respond([
                "status" => 500,
                "message" => $e->getMessage()
            ], 500);
        }
    }

    public function getRuanganCustom(Request $request)
    {
        try {
            $dep = $this->settingFixMultiple(
                // ['kdDepartemenRawatJalanFix', 'kdDepartemenRanapFix', 'kdDepartementBedahFix', 'KdDepartemenCathlab']
                ['kdDepartemenRawatJalanFix', 'kdDepartementBedahFix', 'KdDepartemenCathlab']
            );

            $depart = [];
            foreach ($dep as $key => $ids) {
                $ids = explode(',', $ids);
                foreach ($ids as $k => $d) {
                    $depart[] = $d;
                }
            }
            // objectdepartemenfk,kdDepartemenRawatJalanFix,kdDepartemenRanapFix,idFarmasiBedah
            $ruangan = DB::table('ruangan_m')
                ->whereIn('objectdepartemenfk', $depart)
                ->where('statusenabled', true)
                ->select('id as value', 'namaruangan as label', 'objectdepartemenfk')
                ->get();

            return $this->respond($ruangan);
        } catch (Exception $e) {
            return $this->respond([
                "status" => 500,
                "message" => $e->getMessage()
            ], 500);
        }
    }

    public function getKelas(Request $request)
    {
        try {
            $data = DB::table('harganettoprodukbykelas_m as hnk')
                ->join('kelas_m as k', 'hnk.objectkelasfk', '=', 'k.id')
                ->where('hnk.statusenabled', true)
                ->where('hnk.objectprodukfk', 4293)
                ->where('hnk.objectkebangsaanfk', $request['kebangsaan'])
                ->select(
                    'hnk.harganetto1 as harga',
                    'k.namakelas',
                )
                ->distinct();

            if (isset($request['query']) && $request['query'] != '') {
                $data = $data->where('k.namakelas', 'ILIKE', '%' . $request['query'] . '%');
            }

            $data = $data->get();
            return $this->respond($data);
        } catch (Exception $e) {
            return $this->respond([
                "status" => 500,
                "message" => $e->getMessage()
            ], 500);
        }
    }

    public function getUserRuangan(Request $request)
    {
        try {
            $data = DB::table('maploginusertoruangan_s as mr')
                ->join('ruangan_m as ru', 'ru.id', '=', 'mr.objectruanganfk')
                ->join('loginuser_s as ls', 'ls.id', '=', 'mr.objectloginuserfk')
                ->join('pegawai_m as p', 'p.id', '=', 'ls.objectpegawaifk')
                ->where('mr.statusenabled', true)
                ->whereIn('ru.id', [$request['ruangan']])
                ->where('p.namaexternal', $request['jenis'])
                ->orderBy('p.namalengkap', 'asc')
                ->limit(10)
                ->select('p.id', 'p.namalengkap');

            if (isset($request['query']) && $request['query'] != '') {
                $data = $data->where('p.namalengkap', 'ILIKE', '%' . $request['query'] . '%');
            }

            $data = $data->get();
            return $this->respond($data);
        } catch (Exception $e) {
            return $this->respond([
                "status" => 500,
                "message" => $e->getMessage()
            ], 500);
        }
    }

    public function checkAsmed(Request $r)
    {
        if (!isset($r['norec_pd']) && !isset($r['namaruangan']))
            return $this->respond(false);

        $cek1 = DB::connection('mongodb')
            ->table('AsesmenMedisRawatJalan')
            ->select('registrasi.namaruangan')
            ->where('registrasi.norec_pd', $r['norec_pd'])
            ->latest()
            ->first();

        // Check if rawat inap
        $cek2 = DB::table('pasiendaftar_t as pd')
            ->join('antrianpasiendiperiksa_t as apd', 'apd.noregistrasifk', '=', 'pd.norec')
            ->join('ruangan_m as ru', 'ru.id', '=', 'apd.objectruanganfk')
            ->select('ru.namaruangan', 'ru.objectdepartemenfk')
            ->where('pd.statusenabled', true)
            ->where('apd.statusenabled', true)
            ->where('ru.objectdepartemenfk', 16)
            ->where('pd.norec', $r['norec_pd'])
            ->get();

        if ($cek1['registrasi']['namaruangan'] != $r['namaruangan'] && empty($cek2)) {
            $result = true;
        } else if (count($cek2) > 0) {
            $result = true;
        } else {
            $result = false;
        }

        // true means go, false dont
        return $this->respond($result);
    }

    public function checkresume(Request $r)
    {
        if (!isset($r['norec_pd']) && !isset($r['norec_apd']))
            return $this->respond(false);

        $apd = $r['norec_apd'];
        $pd = $r['norec_pd'];

        $data = DB::connection('mongodb')
            ->table('RingkasanKeluar')
            ->where('registrasi.norec_pd', $pd)
            ->where('registrasi.norec_apd', $apd)
            ->where('profile.kdprofile', $this->kdProfile)
            ->where('statusenabled', true)
            ->latest()
            ->first();

        // true means go, false dont
        return $this->respond($data ? false : true);
    }

    public function checkLP(Request $r)
    {
        if (!isset($r['norec_pd']) && !isset($r['norec_apd']))
            return $this->respond(false);

        $apd = $r['norec_apd'];
        $pd = $r['norec_pd'];

        $data = DB::connection('mongodb')
            ->table('FormulirLembaranPenyiaranRadioterapi')
            ->where('registrasi.norec_pd', $pd)
            ->where('registrasi.norec_apd', $apd)
            ->where('profile.kdprofile', $this->kdProfile)
            ->latest()
            ->first();

        // true means go, false dont
        return $this->respond($data ? false : true);
    }

    public function checkSuketGadar(Request $r)
    {
        if (!isset($r['norec_pd']) && !isset($r['norec_apd']))
            return $this->respond(false);

        $apd = $r['norec_apd'];
        $pd = $r['norec_pd'];

        $data = DB::connection('mongodb')
            ->table('SuratKeteranganGawatDarurat')
            ->where('registrasi.norec_pd', $pd)
            ->where('registrasi.norec_apd', $apd)
            ->where('profile.kdprofile', $this->kdProfile)
            ->where('statusenabled', true)
            ->latest()
            ->first();

        // true means go, false dont
        return $this->respond($data ? false : true);
    }

    public function getPenunjangKhusus(Request $r)
    {
        if (empty($r['nocmfk']) || empty($r['norec_pd'])) {
            return $this->respond(null);
        }

        $tables = explode(',', $r['tables']);
        $result = [];

        foreach ($tables as $key => $tbl) {
            $dt = DB::connection('mongodb')
                ->table($tbl)
                ->where('registrasi.norec_pd', $r['norec_pd'])
                ->where('pasien.nocmfk', $r['nocmfk'])
                ->latest()
                ->first();

            if (isset($dt)) {
                unset($dt['_id']);
                $dt['created_at'] = date("Y-m-d H:i:s", strtotime($dt['created_at']));
                $dt['table'] = $tbl;
                $result[] = $dt;
            }
        }

        usort($result, function ($dtA, $dtB) {
            return strtotime($dtA['created_at']) < strtotime($dtB['created_at']);
        });

        return $this->respond($result);
    }

    public function getRencanaKeperawatanRanap(Request $r)
    {
        try {
            $data = DB::connection('mongodb')
                ->table('RencanaKeperawatanRawatInap')
                ->select('details')
                ->where('pasien.nocmfk', $r['nocmfk'])
                ->where('registrasi.norec_pd', $r['norec_pd'])
                ->orderByDesc('created_at')
                ->first();

            if (isset($data)) {
                unset($data['_id']);
            }

            return $this->respond($data);
        } catch (\Exception $e) {
            return $this->respond([
                "code" => 500,
                "message" => $e->getMessage()
            ], 500);
        }
    }

    public function getDataFormulirFisik(Request $r)
    {
        $res = DB::connection('mongodb')
            ->table('FormulirKedokteranFisikDanRehabilitasi')
            ->select('TBDiagnosisFungsi', 'TBDiagnosisMedis', 'evaluasiMinggu')
            ->where('pasien.nocmfk', $r['nocmfk'])
            ->where('statusenabled', true);

        if (isset($r['norec_pd']) && $r['norec_pd'] != '') {
            $res = $res->where('registrasi.norec_pd', $r['norec_pd']);
        }
        $res = $res->latest();

        $res = $res->first();
        unset($res['_id']);
        return $this->respond($res);
    }

    public function getDataLokalisMata(Request $r)
    {
        $res = DB::connection('mongodb')
            ->table('AsesmenMedisRawatJalan')
            ->select('visusawalodu', 'visusawalodb', 'visusawalosu', 'visusawalosb', 'kacamataodu', 'kacamataodb', 'kacamataosu', 'kacamataosb', 'diagnosaIcd10')
            ->where('pasien.nocmfk', $r['nocmfk'])
            ->where('statusenabled', true);

        if (isset($r['norec_pd']) && $r['norec_pd'] != '') {
            $res = $res->where('registrasi.norec_pd', $r['norec_pd']);
        }
        $res = $res->latest();

        $res = $res->first();
        unset($res['_id']);
        return $this->respond($res);
    }

    public function getOrderLab(Request $r)
    {
        $lab_PK = []; // 335
        $lab_PA = []; // 336
        $lab_Mikro = []; // 337
        $norec_so = [];
        $result = [];
        $produk = [];

        $data = DB::table('strukorder_t as so')
            ->select(
                'so.norec as norec_so',
                'so.noorder',
                'so.objectruangantujuanfk',
                'so.tglorder',
                'ru.namaruangan as lab',
            )
            ->join('ruangan_m as ru', 'ru.id', '=', 'so.objectruangantujuanfk')
            ->where('so.statusenabled', true)
            ->where('ru.objectdepartemenfk', $this->settingFix('idDepartemenLab'))
            ->where('so.noregistrasi', $r['noregistrasi'])
            ->orderBy('so.tglorder')
            ->get();

        if (empty($data) || count($data) == 0) {
            abort(404, 'Pasien belum memiliki orderan laboratorium sama sekali!');
        }

        foreach ($data as $index => $dt) {
            $norec_so[] = $dt->norec_so;
        }

        $dataProduk = DB::table('pelayananpasien_t as pp')
            ->select(
                'pr.id as idproduk',
                'pr.namaproduk',
                'pp.strukorderfk'
            )
            ->leftjoin('produk_m as pr', 'pr.id', '=', 'pp.produkfk')
            ->where('pp.statusenabled', true)
            ->whereIn('pp.strukorderfk', $norec_so)
            ->get();

        foreach ($data as $index1 => $d) {
            $produk = []; // Reset array

            foreach ($dataProduk as $index2 => $dt) {
                if ($d->norec_so == $dt->strukorderfk) {
                    $produk[] = array(
                        'idproduk' => $dt->idproduk,
                        'namaproduk' => $dt->namaproduk
                    );
                }
            }

            // Validasi orderan yang sudah verif
            if (!empty($produk) || !count($produk) == 0) {
                $result[] = array(
                    'norec_so' => $d->norec_so,
                    'noorder' => $d->noorder,
                    'objectruangantujuanfk' => $d->objectruangantujuanfk,
                    'tglorder' => $d->tglorder,
                    'lab' => $d->lab,
                    'details' => $produk
                );
            }
        }

        return $this->respond($result);
    }

    public function getDetailHasilLab(Request $r)
    {
        /*
        1. Hasil Lab PK (Bridging)
        - noorder

        2. Hasil Lab Mikro (Bridging, jarang dipakai)
        - noorder

        3. Hasil Lab PA (Bridging)
        - noorder
        */

        $groupedData = [];
        $noorder = explode(',', $r['noorder']);
        $dataBrid = DB::connection('sqlsrv_lis')
            ->table('reshd as rh')
            ->join('resdt as rd', 'rd.ONO', '=', 'rh.ONO')
            ->whereIn('rh.ONO', $noorder)
            ->where('rd.RESULT_VALUE', '!=', '!')
            ->where('rd.RESULT_FT', '!=', '!')
            ->orderBy('rd.TEST_NM', 'desc')
            ->select('rd.*', 'rh.CLINICIAN_NM', 'rh.COMMENT')
            ->get();

        foreach ($dataBrid as $item) {
            // Format data untuk tiap item
            $formattedItem = [
                'namaproduk' => $item->ORDER_TESTNM,
                'namalengkap' => '-',
                'detailpemeriksaan' => $item->TEST_NM,
                'hasil' => $item->RESULT_VALUE,
                'result_ft' => $item->RESULT_FT,
                'test_group' => $item->TEST_GROUP,
                'tglOrder' => '-',
                'flag' => $item->FLAG,
                'test_comment' => $item->TEST_COMMENT,
                'nilaitext' => $item->REF_RANGE,
                'diotorisasi' => isset(explode('^', $item->VALIDATE_BY)[1]) ? explode('^', $item->VALIDATE_BY)[1] : explode('^', $item->VALIDATE_BY)[0],
                'dokterdiperiksa' => isset(explode('^', $item->RELEASE_BY)[1]) ? explode('^', $item->RELEASE_BY)[1] : explode('^', $item->RELEASE_BY)[0],
                'satuanstandar' => $item->UNIT,
                'analis' => $item->VALIDATE_BY,
                'tglhasil' => $this->formatTimestamp($item->VALIDATE_ON),
                'noorder' => $item->ONO,
                'dokterlab' => '-',
                'pegawaiverifikator' => '-',
                'nipdokterlab' => '-',
                'nippegawaiverifikator' => '-',
                'ruanganasal' => '-',
                'catatanklinis' => $item->TEST_COMMENT,
                'metode' => $item->METHOD,
                'comment' => $item->COMMENT ?? null
            ];

            // Kelompokkan berdasarkan test_group
            $groupKey = $item->TEST_GROUP;
            if (!isset($groupedData[$groupKey])) {
                $groupedData[$groupKey] = [
                    'group' => $groupKey,
                    'items' => [],
                    'diotorisasi' => isset(explode('^', $item->VALIDATE_BY)[1]) ? explode('^', $item->VALIDATE_BY)[1] : explode('^', $item->VALIDATE_BY)[0],
                    'dokterdiperiksa' => isset(explode('^', $item->RELEASE_BY)[1]) ? explode('^', $item->RELEASE_BY)[1] : explode('^', $item->RELEASE_BY)[0],
                ];
            }

            $groupedData[$groupKey]['items'][] = $formattedItem;
        }

        if (empty($groupedData)) {
            abort(404, 'Data belum di input oleh lab');
        }

        $result = array(
            'details' => json_decode(json_encode($groupedData), false)
        );

        return $this->respond($result);
    }

    public function getOrderRadiologi(Request $r)
    {
        $norec_so = [];
        $result = [];
        $produk = [];

        $data = DB::table('strukorder_t as so')
            ->join('ruangan_m as ru', 'ru.id', '=', 'so.objectruangantujuanfk')
            ->select(
                'so.norec as norec_so',
                'so.noorder',
                'so.tglorder'
            )
            ->where('so.statusenabled', true)
            ->where('ru.objectdepartemenfk', $this->settingFix('idDepartemenRadiologi'))
            ->where('so.noregistrasi', $r['noregistrasi'])
            ->orderBy('so.tglorder')
            ->get();

        if (empty($data) || count($data) == 0) {
            abort(404, 'Pasien belum memiliki orderan radiologi sama sekali!');
        }

        foreach ($data as $index => $dt) {
            $norec_so[] = $dt->norec_so;
        }

        $dataProduk = DB::table('pelayananpasien_t as pp')
            ->select(
                'pr.id as idproduk',
                'pr.namaproduk',
                'pp.strukorderfk'
            )
            ->join('produk_m as pr', 'pr.id', '=', 'pp.produkfk')
            ->where('pp.statusenabled', true)
            ->whereIn('pp.strukorderfk', $norec_so)
            ->get();

        foreach ($data as $index1 => $d) {
            $produk = []; // Reset array

            foreach ($dataProduk as $index2 => $dt) {
                if ($d->norec_so == $dt->strukorderfk) {
                    $produk[] = array(
                        'idproduk' => $dt->idproduk,
                        'namaproduk' => $dt->namaproduk
                    );
                }
            }

            // Validasi orderan yang sudah verif
            if (!empty($produk) || !count($produk) == 0) {
                $result[] = array(
                    'norec_so' => $d->norec_so,
                    'tglorder' => $d->tglorder,
                    'noorder' => $d->noorder,
                    'details' => $produk
                );
            }
        }

        return $this->respond($result);
    }

    public function getDetailHasilRadiologi(Request $r)
    {
        $noorder = explode(',', $r['noorder']);
        $data = DB::connection('sqlsrv_ris')
            ->table('ris_in as ri')
            ->join('ris_out as ro', 'ri.no_rontgen', '=', 'ro.no_rontgen')
            ->whereIn('ri.nobukti', $noorder)
            ->select('ro.expertise_text_finding as expertise_1', 'ro.expertise_text_conclusion as expertise_2', 'ri.nama_pemeriksaan')
            ->get();

        return $this->respond($data);
    }

    public function getOrderBedah(Request $r)
    {
        if (empty($r['nocmfk'])) {
            return $this->respond(null);
        }

        $data = DB::table('strukorder_t as so')
            ->select([
                'so.norec',
                'so.nocmfk',
                'so.noregistrasi',
                'so.tglorder',
                'so.diagnosis',
                'so.tgloperasi',
                'so.durasi',
                'so.keteranganlainnya'
            ])
            ->where('so.statusenabled', true)
            ->where('so.nocmfk', $r['nocmfk'])
            ->where('so.objectkelompoktransaksifk', 22)
            ->orderByDesc('so.tglorder')
            ->first();

        return $this->respond($data);
    }

    public function getDepartemenMasukPasien(Request $r)
    {
        if (empty($r['norec_pd'])) {
            return $this->respond(null);
        }

        $data = DB::table('antrianpasiendiperiksa_t as apd')
            ->join('ruangan_m as ru', 'ru.id', '=', 'apd.objectruanganfk')
            ->select('ru.namaruangan', 'ru.objectdepartemenfk', 'apd.objectruanganfk')
            ->where('apd.statusenabled', true)
            ->where('apd.noregistrasifk', $r['norec_pd'])
            ->orderBy('apd.tglmasuk')
            ->first();

        return $this->respond($data);
    }

    private function formatTimestamp($timestamp)
    {
        $year = substr($timestamp, 0, 4);
        $month = substr($timestamp, 4, 2);
        $day = substr($timestamp, 6, 2);
        $hour = substr($timestamp, 8, 2);
        $minute = substr($timestamp, 10, 2);
        $second = substr($timestamp, 12, 2);

        $date = Carbon::create($year, $month, $day, $hour, $minute, $second);
        return $date->format('d-m-Y H:i:s');
    }

    public function getMenuProfilePasien(Request $r)
    {
        // Get Menu EMR
        $data_emr = DB::table('emr_t as emr')
            ->join('mapruangantoemr_t as map', 'map.emrfk', 'emr.id')
            ->select('emr.caption', 'emr.url_form', 'emr.collection')
            ->where('map.statusenabled', true)
            ->where('map.kdprofile', $this->kdProfile)
            ->where('map.kodeexternal', 'MENU');

        $data_default = DB::table('emr_t as emr')
            ->join('mapruangantoemr_t as map', 'map.emrfk', 'emr.id')
            ->select('emr.caption', 'emr.url_form', 'emr.collection')
            ->where('map.statusenabled', true)
            ->where('map.kdprofile', $this->kdProfile)
            ->where('map.kodeexternal', 'DEFAULT');

        // Filter
        if (isset($r['kelompokuserfk']) && $r['kelompokuserfk'] != '') {
            $data_emr = $data_emr->where('map.kelompokuserfk', $r['kelompokuserfk']);
            $data_default = $data_default->where('map.kelompokuserfk', $r['kelompokuserfk']);
        }
        if (isset($r['ruanganfk']) && $r['ruanganfk'] != '') {
            $data_emr = $data_emr->where('map.objectruanganfk', $r['ruanganfk']);
        }
        // if (isset($r['departemenfk']) && $r['departemenfk'] != '') {
        //     $data_emr = $data_emr->where('map.objectdepartemenfk', $r['departemenfk']);
        //     $data_default = $data_default->where('map.objectdepartemenfk', $r['departemenfk']);
        // }

        $data_emr = $data_emr->orderBy('map.nourut')->get();
        $data_default = $data_default->orderBy('map.nourut')->get();

        // Return
        $result = array(
            'menu' => $data_emr,
            'default' => $data_default
        );

        return $this->respond($result);
    }
}
