<?php

namespace App\Http\Controllers\Ppi;

use App\Http\Controllers\Controller;
use App\Models\Transaksi\CheklisApd;
use App\Models\Transaksi\EdukasiIpcln;
use App\Models\Transaksi\KepatuhanHandHygiene;
use App\Models\Transaksi\RiwayatPMKP;
use App\Models\Transaksi\Surveilans;
use App\Models\Transaksi\SurveilansAntibiotik;
use App\Models\Transaksi\SurveilansFaktorResiko;
use App\Models\Transaksi\SurveilansFrd;
use App\Models\Transaksi\SurveilansOperasi;
use App\Models\Transaksi\SuvervisiIPCN;
use App\Models\Transaksi\SuvervisiIPCNDetail;
use App\Traits\Valet;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PPICtrl extends Controller
{
    use Valet;
    public function saveEdukasiIpcln(Request $request)
    {
        $kdProfile = $this->kdProfile;
        $tanggal = date('Y-m-d H:i:s');
        DB::beginTransaction();
        try {
            foreach ($request['data'] as $item) {
                if (!is_array($item['tgl'])) {
                    if ($request['id'] == '') {
                        $id = EdukasiIpcln::max('id');
                        $dataJadwal = new EdukasiIpcln();
                        $dataJadwal->id = $dataJadwal->generateNewId();
                        $dataJadwal->norec = $dataJadwal->generateNewId();
                        $dataJadwal->kdprofile = $kdProfile;
                        $dataJadwal->statusenabled = true;
                    } else {
                        $dataJadwal = EdukasiIpcln::where('id', $request['id'])->first();
                    }

                    $dataJadwal->objectpegawaifk = $request['pegawaifk'];
                    $dataJadwal->objectruanganfk = $request['ruanganfk'];
                    $dataJadwal->jeniskegiatan = $item['jeniskegiatan'];
                    $dataJadwal->tglinput = $tanggal;
                    $dataJadwal->tgl = $item['tgl'];
                    if (isset($item['isi'])) {
                        $dataJadwal->isi = $item['isi'];
                    }

                    $dataJadwal->save();
                }
            }

            $transStatus = 'true';
        } catch (Exception $e) {
            $transStatus = 'false';
            $message = $e->getMessage() . ': ' . $e->getLine();
        }

        if ($transStatus == 'true') {
            $transMessage = "Simpan Jadwal Berhasil";
            DB::commit();
            $result = array(
                "status" => 201,
                "message" => $transMessage,
                "res" => $dataJadwal,
                "as" => 'ea@epic',
            );
        } else {
            $transMessage = "Simpan Jadwal Gagal" . $message;
            DB::rollBack();
            $result = array(
                "status" => 400,
                "message"  => $transMessage,
                "as" => 'ea@epic',
            );
        }
        return $this->respond($result, $result['status'], $result['message']);
    }
    public function getDataSurveilans(Request $request)
    {
        $kdProfile = $this->kdProfile;
        $data = DB::table('surveilans_t as sv')
            ->join('pasiendaftar_t as pd', 'pd.norec', '=', 'sv.noregistrasifk')
            ->join('antrianpasiendiperiksa_t as apd', 'apd.norec', '=', 'sv.norec_apd')
            ->leftJoin('pasien_m as pm', 'pm.id', '=', 'pd.nocmfk')
            ->leftJoin('ruangan_m as ru', 'ru.id', '=', 'apd.objectruanganfk')
            ->leftJoin('jeniskelamin_m as jk', 'jk.id', '=', 'pm.objectjeniskelaminfk')
            ->leftJoin('kelompokpasien_m as kp', 'kp.id', '=', 'pd.objectkelompokpasienlastfk')
            ->leftJoin('batalregistrasi_t as br', 'br.pasiendaftarfk', '=', 'pd.norec')
            ->select(DB::raw("sv.norec,sv.tglsurveilans,sv.nosurvailens,pd.tglregistrasi,pd.noregistrasi,
		                      pm.nocm,pm.namapasien,jk.reportdisplay as jk,ru.namaruangan,ru.objectdepartemenfk,
		                      kp.kelompokpasien,pm.tgllahir"))
            ->whereNull('br.norec')
            ->where('sv.kdprofile', (int)$kdProfile)
            ->where('sv.statusenabled', true);

        $filter = $request->all();
        if (isset($filter['tglAwal']) && $filter['tglAwal'] != "" && $filter['tglAwal'] != "undefined") {
            $data = $data->where('sv.tglsurveilans', '>=', $filter['tglAwal'] . " " . "00:00:00");
        }
        if (isset($filter['tglAkhir']) && $filter['tglAkhir'] != "" && $filter['tglAkhir'] != "undefined") {
            $tgl = $filter['tglAkhir'];
            $data = $data->where('sv.tglsurveilans', '<=', $tgl . " " . "23:59:00");
        }
        if (isset($filter['idDept']) && $filter['idDept'] != "" && $filter['idDept'] != "undefined") {
            $data = $data->where('ru.objectdepartemenfk', '=', $filter['idDept']);
        }
        if (isset($filter['idRuangan']) && $filter['idRuangan'] != "" && $filter['idRuangan'] != "undefined") {
            $data = $data->where('ru.id', '=', $filter['idRuangan']);
        }
        if (isset($filter['kelompokPasien']) && $filter['kelompokPasien'] != "" && $filter['kelompokPasien'] != "undefined") {
            $data = $data->where('pd.objectkelompokpasienlastfk', '=', $filter['kelompokPasien']);
        }
        if (isset($filter['namaPasien']) && $filter['namaPasien'] != "" && $filter['namaPasien'] != "undefined") {
            $data = $data->where('pm.namapasien', 'ilike', '%' . $filter['namaPasien'] . '%');
        }
        if (isset($filter['noRegis']) && $filter['noRegis'] != "" && $filter['noRegis'] != "undefined") {
            $data = $data->where('pd.noregistrasi', 'ilike', '%' . $filter['noRegis'] . '%');
        }
        if (isset($filter['noCm']) && $filter['noCm'] != "" && $filter['noCm'] != "undefined") {
            $data = $data->where('pm.nocm', 'ilike', '%' . $filter['noCm'] . '%');
        }
        $data = $data->get();

        $result = array(
            'data' => $data,
            'message' => 'ea@epic',
        );
        return $this->respond($result);
    }
    public function getDataKepatuhanHandHygieneIPCN(Request $request)
    {
        $bln = '';
        if (isset($request['bln']) && $request['bln'] != "" && $request['bln'] != "undefined") {
            $bln = "AND to_char(kh.tanggal,'yyyy-MM') ='" . $request['bln'] . "' ";
        };

        $data = DB::select(DB::raw("SELECT x.bulan, x.tahun, x.namaruangan, x.jenispegawai, SUM(x.patuh) AS patuh, SUM(x.tidakpatuh) AS tidakpatuh
            FROM
                (SELECT
                    EXTRACT(MONTH from kh.tanggal) AS bulan,
                    EXTRACT(YEAR from kh.tanggal) AS tahun,
                    ru.namaruangan,
                    jp.jenispegawai,
                    CASE WHEN kh.langkah=2 THEN 1 ELSE 0 END AS patuh,
                    CASE WHEN kh.langkah!=2 THEN 1 ELSE 0 END AS tidakpatuh
                FROM
                    kepatuhanhandhygiene_t AS kh
                LEFT JOIN ruangan_m AS ru ON ru.id = kh.objectruanganfk
                LEFT JOIN pegawai_m AS pg ON pg.id = kh.objectpegawaifk
                LEFT JOIN jenispegawai_m AS jp ON jp.id = kh.objectjenispegawaifk
                WHERE
                    kh.statusenabled = true
                    $bln) AS x
            GROUP BY x.tahun, x.bulan, x.namaruangan, x.jenispegawai "));

        $result = array(
            'data' => $data,
            'message' => 'dy@epic',
        );
        return $this->respond($result);
    }

    public function getDataKepatuhanHandHygiene(Request $request)
    {
        $kdProfile = $this->kdProfile;
        $idProfile = (int) $kdProfile;
        $data = DB::table('kepatuhanhandhygiene_t AS kh')
            ->leftJoin('ruangan_m AS ru', 'ru.id', '=', 'kh.objectruanganfk')
            ->leftJoin('indikasi_m AS ink', 'ink.id', '=', 'kh.objectindikasifk')
            ->leftJoin('handhygiene_m AS hh', 'hh.id', '=', 'kh.objecthygienefk')
            ->leftJoin('pegawai_m AS pg', 'pg.id', '=', 'kh.objectpegawaifk')
            ->leftJoin('jenispegawai_m AS jp', 'jp.id', '=', 'kh.objectjenispegawaifk')
            ->select(DB::raw("
                kh.norec,kh.tanggal,kh.objectruanganfk,CAST(kh.objectindikasifk AS VARCHAR) AS objectindikasifk,ru.namaruangan,
                CAST(kh.objecthygienefk AS VARCHAR) AS objecthygienefk,kh.objectpegawaifk,
                pg.namalengkap,kh.langkah,kh.objectjenispegawaifk,jp.jenispegawai,kh.kesempatan,kh.langkah,ink.indikasi as indikasi,hh.tindakan as tindakan,
                kh.langkah1,kh.langkah2,kh.langkah3,kh.langkah4,kh.langkah5,kh.langkah6,kh.langkah7
            "))
            ->where('kh.kdprofile', $idProfile)
            ->where('kh.statusenabled', true)
            ->orderBy('kh.tanggal');
        $filter = $request->all();

        if (isset($filter['tglAwal']) && $filter['tglAwal'] != "" && $filter['tglAwal'] != "undefined") {
            $data = $data->where('kh.tanggal', '>=', $filter['tglAwal']);
        }
        if (isset($filter['tglAkhir']) && $filter['tglAkhir'] != "" && $filter['tglAkhir'] != "undefined") {
            $data = $data->where('kh.tanggal', '<=', $filter['tglAkhir']);
        }
        if (isset($filter['pgid']) && $filter['pgid'] != "" && $filter['pgid'] != "undefined") {
            $data = $data->where('kh.objectpegawaifk', '=', $filter['pgid']);
        }
        if (isset($filter['prid']) && $filter['prid'] != "" && $filter['prid'] != "undefined") {
            $data = $data->where('kh.objectjenispegawaifk', '=', $filter['prid']);
        }
        if (isset($filter['bngid']) && $filter['bngid'] != "" && $filter['bngid'] != "undefined") {
            $data = $data->where('kh.objectruanganfk', '=', $filter['bngid']);
        }
        if (isset($filter['indikasiid']) && $filter['indikasiid'] != "" && $filter['indikasiid'] != "undefined") {
            $data = $data->where('ink.id', '=', $filter['indikasiid']);
        }
        $data = $data->get();
        $result = array(
            'data' => $data,
            'message' => 'afd@epic',
        );
        return $this->respond($result);
    }

    public function saveDataKepatuhanHandHygiene(Request $request)
    {
        $kdProfile = $this->kdProfile;
        $idProfile = (int) $kdProfile;
        DB::beginTransaction();
        $dataReq = $request->all();
        $kegiatan = '';
        $tgl = date('Y-m-d H:i:s');
        try {

            if ($dataReq['norec'] == '') {
                $dataSave = new KepatuhanHandHygiene();
                $dataSave->norec = $dataSave->generateNewId();
                $dataSave->kdprofile = $idProfile;
                $dataSave->statusenabled = true;
                $kegiatan = 'Input IPCLN';
                # code...
            } else {

                $dataSave = KepatuhanHandHygiene::where('norec', $dataReq['norec'])->first();
                $kegiatan = 'Update IPCLN';
            }
            $dataSave->tanggal = $dataReq['tanggal'];
            $dataSave->objectjenispegawaifk = $dataReq['objectjenispegawaifk'];
            $dataSave->objectpegawaifk = $dataReq['objectpegawaifk'];
            $dataSave->objectindikasifk = $dataReq['objectindikasifk'];
            $dataSave->objecthygienefk = $dataReq['objecthygienefk'];
            $dataSave->langkah = isset($dataReq['langkah']) ? $dataReq['langkah'] : null;
            $dataSave->objectruanganfk = isset($dataReq['objectruanganfk']) ? $dataReq['objectruanganfk'] : null;
            $dataSave->kesempatan = isset($dataReq['kesempatan'])  ? $dataReq['kesempatan'] : null;
            $dataSave->langkah1 = isset($dataReq['langkah1']) ? $dataReq['langkah1'] : null;
            $dataSave->langkah2 = isset($dataReq['langkah2']) ? $dataReq['langkah2'] : null;
            $dataSave->langkah3 = isset($dataReq['langkah3']) ? $dataReq['langkah3'] : null;
            $dataSave->langkah4 = isset($dataReq['langkah4']) ? $dataReq['langkah4'] : null;
            $dataSave->langkah5 = isset($dataReq['langkah5']) ? $dataReq['langkah5'] : null;
            $dataSave->langkah6 = isset($dataReq['langkah6']) ? $dataReq['langkah6'] : null;
            $dataSave->langkah7 = isset($dataReq['langkah7']) ? $dataReq['langkah7'] : null;

            $dataSave->save();
            $norec = $dataSave->norec;

            $this->LOGGING(
                $kegiatan,
                $norec,
                'norec kepatuhanhandhygne',
                $kegiatan . "denggan pegawai" . $dataReq['objectpegawaifk'] . "Pada tanggal" . $tgl
            );

            $transStatus = true;
        } catch (Exception $e) {
            $transStatus = false;
        }

        if ($transStatus) {
            $transMessage = "Simpan Berhasil";
            DB::commit();
            $result = array(
                "status" => 201,
                "result" => $dataSave,
                "as" => 'afd@epic',
            );
        } else {
            $transMessage = "Simpan Gagal!!";
            DB::rollBack();
            $result = array(
                "status" => 400,
                "as" => 'afd@epic',
                "result"  => $e->getMessage() . $e->getLine()
            );
        }
        return $this->respond($result['result'], $result['status'], $transMessage);
    }

    public function saveBatalKepatuhanHandHygiene(Request $request)
    {
        $kdProfile = (int) $this->kdProfile;
        DB::beginTransaction();
        try {
            KepatuhanHandHygiene::where('norec', $request['norec'])
                ->where('kdprofile', $kdProfile)
                ->update([

                    'statusenabled' => 0,
                ]);
            $transStatus = 'true';
        } catch (Exception $e) {
            $transStatus = 'false';
        }

        if ($transStatus == 'true') {
            $transMessage = "Hapus Berhasil";
            DB::commit();
            $result = array(
                'status' => 201,
                'message' => $transMessage,
                'as' => 'afd@epic',
                'result' => []
            );
        } else {
            $transMessage = "Hapus Gagal";
            DB::rollBack();
            $result = array(
                'status' => 400,
                'message' => $transStatus,
                'as' => 'afd@epic',
                'result' => $e->getMessage() . $e->getLine()
            );
        }
        return $this->respond($result['result'], $result['status'], $transMessage);
    }
    public function getRiwayat(Request $request)
    {
        $kdProfile = (int) $this->kdProfile;
        $data = DB::table('riwayatpmkp_t as tt')
            ->leftJoin('pegawai_m as pg', 'pg.id', '=', 'tt.pegawaifk')
            ->select('tt.*', 'pg.namalengkap')
            ->where('tt.kdprofile', $kdProfile)
            ->where('tt.statusenabled', true);
        if (isset($request['search']) && $request['search'] != "" && $request['search'] != "undefined") {
            $data = $data->where('tt.judul', 'ilike', '%' . $request['search'] . '%')->orWhere('tt.isi', 'ilike', '%' . $request['search'] . '%');
        };
        $data = $data->get();
        foreach ($data as $key => $val) {
            if ($val->image) {
                $path = 'berkas_ppi/' . $val->image;
                $url = asset(Storage::url($path));
                $data[$key]->photo = $url;
            } else {
                $data[$key]->photo = null;
            }
        }
        return $this->respond($data);
    }
    public function  saveRiwayat(Request $request)
    {
        $kdProfile = (int) $this->kdProfile;
        DB::beginTransaction();
        try {
            if ($request['norec'] == '') {
                $data = new RiwayatPMKP();
                $data->norec = $data->generateNewId();
                $data->kdprofile = $kdProfile;
                $data->statusenabled = true;
            } else {
                $data = RiwayatPMKP::where('norec', $request['norec'])->first();
            }

            $data->judul = $request['judul'];
            $data->isi = $request['isi'];
            $data->tgl = date('Y-m-d H:i');
            $data->pegawaifk = $this->getUserId();
            $data->keterangan = $request['keterangan'];
            if ($request->file) {
                $path           = 'public/berkas_ppi/';
                $base64         = $request->file;
                $image_parts    = explode(";base64,", $base64);
                $image_type_aux = explode("image/", $image_parts[0]);
                $extension      = $image_type_aux[1];
                $imageName      = Str::random(4) . '-' . Str::slug($request['judul'], '-') . '.' . $extension;
                $file           = $path . $imageName;
                Storage::put($file, base64_decode($image_parts[1]));
                $data->image    = $imageName;
            }
            $data->save();
            $transStatus = 'true';
        } catch (Exception $e) {
            $transStatus = 'false';
        }

        if ($transStatus == 'true') {
            $transMessage = "Simpan Berhasil";
            DB::commit();
            $result = array(
                "status" => 201,
                "message" => $transMessage,
                "result" => $data,
                "as" => 'er@epic',
            );
        } else {
            $transMessage = "Simpan Gagal!!";
            DB::rollBack();
            $result = array(
                "status" => 400,
                "message"  => $transMessage,
                "result" => $e->getMessage() . "-" . $e->getLine(),
                "as" => 'er@epic',
            );
        }
        return $this->respond($result['result'], $result['status'], $transMessage);
    }
    public function getindikatoripcn(Request $request)
    {
        $kdProfile = (int) $this->kdProfile;
        $data = DB::table('indikatoripcn_m AS ii')
            ->join('kelompokipcn_m AS ki', 'ki.id', '=', 'ii.kelompokipcnfk')
            ->join('departemen_m AS dept', 'dept.id', '=', 'ii.departemenfk')
            ->select(DB::raw("
                ki.kdkelompokipcn || '. ' || ki.kelompokipcn AS kelompok,ii.kelompokipcnfk,
                ii.id AS indikatorfk,ki.kelompokipcn,ii.id,ii.indikatoripcn,ii.departemenfk,
                dept.namadepartemen,ki.nourut
            "))
            ->where('ii.statusenabled', true)
            ->where('ii.kdprofile', $kdProfile);

        if (isset($request['id']) && $request['id'] != "" && $request['id'] != "undefined") {
            $data = $data->where('ii.id', '=', $request['id']);
        }
        if (isset($request['idKlmpokIPCN']) && $request['idKlmpokIPCN'] != "" && $request['idKlmpokIPCN'] != "undefined") {
            $data = $data->where('ii.kelompokipcnfk', '=', $request['idKlmpokIPCN']);
        }
        if (isset($request['objectdepartemenfk']) && $request['objectdepartemenfk'] != "" && $request['objectdepartemenfk'] != "undefined") {
            $data = $data->where('ii.departemenfk', '=', $request['objectdepartemenfk']);
        }
        if (isset($request['nmaIndikator']) && $request['nmaIndikator'] != "" && $request['nmaIndikator'] != "undefined") {
            $data = $data->where('ii.indikatoripcn', 'ILIKE', '%' . $request['nmaIndikator'] . '%');
        }
        $data = $data->get();
        return $this->respond($data);
    }

    public function saveSuvervisiIPCN(Request $request)
    {
        $kdProfile = (int) $this->kdProfile;
        DB::beginTransaction();
        try {

            if ($request['norec'] == "") {
                $JD = new SuvervisiIPCN();
                $JD->norec = $JD->generateNewId();
                $JD->kdprofile = $kdProfile;
                $JD->statusenabled = true;
            } else {
                $JD = SuvervisiIPCN::where('norec', $request['norec'])->where('kdprofile', $kdProfile)->first();
                SuvervisiIPCNDetail::where('suvervisipcnfk', $request['norec'])->where('kdprofile', $kdProfile)->delete();
            }
            $JD->tglinput = $request['tanggal'];
            $JD->departemenfk = $request['departemenfk'];
            $JD->ruanganfk = $request['ruanganfk'];
            $JD->jumlahiya = $request['jumlahiya'];
            $JD->jumlahtidak = $request['jumlahtidak'];
            $JD->jumlahna = $request['jumlahna'];
            $JD->skorkepatuhan = $request['skorkepatuhan'];
            $JD->petugasfk = $this->getUserId();
            $JD->save();
            $norecJD = $JD->norec;


            foreach ($request['detailS'] as $item) {
                $Jdd = new SuvervisiIPCNDetail();
                $Jdd->norec = $Jdd->generateNewId();
                $Jdd->kdprofile = $kdProfile;
                $Jdd->statusenabled = true;
                $Jdd->suvervisipcnfk = $norecJD;
                $Jdd->indikatorfk = isset($item['indikatorfk'])  ? $item['indikatorfk'] : '';
                $Jdd->nilaiiya = isset($item['iya']) ?  $item['iya'] : 0;
                $Jdd->nilaitidak = isset($item['tidak']) ? $item['tidak'] : 0;
                $Jdd->nilaina = isset($item['na']) ? $item['na'] : 0;
                $Jdd->keterangan = isset($item['keterangan']) ? $item['keterangan'] : '';
                $Jdd->rekomendasi = isset($item['rekomendasi']) ? $item['rekomendasi'] : '';
                $Jdd->save();
            }

            $transStatus = 'true';
        } catch (Exception $e) {
            $transStatus = 'false';
        }

        if ($transStatus == 'true') {
            $transMessage = "Simpan Suvervisi IPCN";
            DB::commit();
            $result = array(
                "status" => 201,
                "as" => 'inhuman',
                "result" => $JD
            );
        } else {
            $transMessage = "Simpan Gagal Suvervisi IPCN";
            DB::rollBack();
            $result = array(
                "status" => 400,
                "as" => 'inhuman',
                "result" => $e->getMessage() . "-" . $e->getLine()
            );
        }
        return $this->respond($result['result'], $result['status'], $transMessage);
    }

    public function saveCheklisApd(Request $request)
    {
        $kdProfile = $this->kdProfile;
        $tglAyeuna = date('Y-m-d H:i:s');
        DB::beginTransaction();
        try {
            foreach ($request['data'] as $item) {
                if ($request['id'] == '') {
                    $dataJadwal = new CheklisApd();
                    $dataJadwal->norec = $dataJadwal->generateNewId();
                    $dataJadwal->kdprofile = (int)$kdProfile;
                    $dataJadwal->statusenabled = true;
                } else {
                    $dataJadwal = CheklisApd::where('id', $request['id'])->first();
                }

                $dataJadwal->objectpegawaifk = $request['pegawaifk'];
                $dataJadwal->objectruanganfk = $request['ruanganfk'];
                $dataJadwal->jeniskegiatan = $item['jeniskegiatan'];
                $dataJadwal->tglinput = $tglAyeuna;
                $dataJadwal->tgl = $item['tgl'];
                $dataJadwal->isi = isset($item['isi']) ? $item['isi'] : '';
                $dataJadwal->save();
            }

            $transStatus = 'true';
        } catch (Exception $e) {
            $transStatus = 'false';
        }

        if ($transStatus == 'true') {
            $transMessage = "Simpan Berhasil";
            DB::commit();
            $result = array(
                "status" => 201,
                "message" => $transMessage,
                "result" => $dataJadwal,
                "as" => 'ea@epic',
            );
        } else {
            $transMessage = "Simpan Gagal";
            DB::rollBack();
            $result = array(
                "status" => 400,
                "message"  => $transMessage,
                "as" => 'ea@epic',
                "result" => $e->getMessage() . "-" . $e->getLine()
            );
        }
        return $this->respond($result['result'], $result['status'], $transMessage);
    }
    public function getDataCheklisApd(Request $request)
    {
        $kdProfile = (int) $this->kdProfile;
        $data = DB::table('cheklisapd_t as ei')
            ->join('pegawai_m as pg', 'pg.id', '=', 'ei.objectpegawaifk')
            ->join('ruangan_m as ru', 'ru.id', '=', 'ei.objectruanganfk')
            ->select(DB::raw("ei.*,ru.namaruangan,pg.namalengkap as petugas"))
            ->where('ei.kdprofile', $kdProfile)
            ->where('ei.statusenabled', true);
        if (
            isset($request['bulan']) &&
            $request['bulan'] != "" &&
            $request['bulan'] != "undefined"
        ) {
            $tgl = date('Y-m', strtotime($request['bulan']));
            $data = $data->whereNotNull('ei.tglinput')
                ->whereRaw("to_char(ei.tglinput, 'YYYY-MM') = '$tgl'");
        }
        if (
            isset($request['pegawaifk']) &&
            $request['pegawaifk'] != "" &&
            $request['pegawaifk'] != "undefined"
        ) {
            $data = $data->where('ei.objectpegawaifk', '=', $request['pegawaifk']);
        };
        if (
            isset($request['ruanganfk']) &&
            $request['ruanganfk'] != "" &&
            $request['ruanganfk'] != "undefined"
        ) {
            $data = $data->where('ru.id', '=', $request['ruanganfk']);
        };

        $data = $data->get();
        return $this->respond($data);
    }
    public function saveDataSurveilans(Request $request)
    {
        $idProfile = (int) $this->kdProfile;
        DB::beginTransaction();
        $tglAyeuna = date('Y-m-d H:i:s');
        try {

            $dataHead = $request['datahead'];
            $dataFaktorResiko = $request['faktorresiko'];
            $dataOperasi = $request['operasi'];
            $dataFrd = $request['faktorResikorawat'];
            $dataAnti = $request['antibiotika'];
            if ($dataHead['norec'] == '') {
                $noSurvailens = $this->generateCode(new Surveilans(), 'nosurvailens', 12, 'SV-' . $this->getDateTime()->format('ym'), $idProfile);
                $dataSurveilans = new Surveilans();
                $dataSurveilans->norec = $dataSurveilans->generateNewId();
                $dataSurveilans->kdprofile = $idProfile;
                $dataSurveilans->statusenabled = true;
                $dataSurveilans->nosurvailens = $noSurvailens;
                $dataSurveilans->noregistrasifk = $dataHead['norec_pd'];
                $dataSurveilans->norec_apd = $dataHead['norec_apd'];
            } else {
                $dataSurveilans = Surveilans::where('norec', $dataHead['norec'])->where('kdprofile', $idProfile)->first();
            }
            $dataSurveilans->tglsurveilans = isset($dataHead['tglsurveilans']) ? $dataHead['tglsurveilans'] : '';
            $dataSurveilans->diagnosamasukfk = $dataHead['diagnosamasukfk'];
            $dataSurveilans->diagnosakeluarfk = $dataHead['diagnosakeluarfk'];
            $dataSurveilans->keterangandiagnosamasuk = isset($dataHead['keterangandiagnosamasuk']) ? $dataHead['keterangandiagnosamasuk'] : '';
            $dataSurveilans->keterangandiagnosakeluar = isset($dataHead['keterangandiagnosakeluar']) ? $dataHead['keterangandiagnosakeluar'] : '';
            $dataSurveilans->antibiotikprofillaksis = isset($dataHead['antibiotikprofillaksis']) ? $dataHead['antibiotikprofillaksis'] : null;
            $dataSurveilans->dosis = isset($dataHead['dosis']) ? $dataHead['dosis'] : null;
            $dataSurveilans->ruanganfk = isset($dataHead['ruanganfk']) ? $dataHead['ruanganfk'] : null;
            $dataSurveilans->jeniswaktufk = isset($dataHead['jeniswaktufk']) ? $dataHead['jeniswaktufk'] : null;
            $dataSurveilans->culturdarah = isset($dataHead['culturdarah']) ? $dataHead['culturdarah'] : '';
            $dataSurveilans->cultururine = isset($dataHead['cultururine']) ? $dataHead['cultururine'] : '';
            $dataSurveilans->cultursputum = isset($dataHead['cultursputum']) ? $dataHead['cultursputum'] : '';
            $dataSurveilans->save();
            $norecSurveilan = $dataSurveilans->norec;

            if ($dataFaktorResiko != "") {
                if ($dataHead['norec'] == "") {
                    $dataSurveilansFR = new SurveilansFaktorResiko();
                    $dataSurveilansFR->kdprofile = $idProfile;
                    $dataSurveilansFR->statusenabled = true;
                    $dataSurveilansFR->norec = $dataSurveilansFR->generateNewId();
                    $dataSurveilansFR->nosurvailensfk = $norecSurveilan;
                } else {
                    $dataSurveilansFR = SurveilansFaktorResiko::where('nosurvailensfk', $dataHead['norec'])->where('kdprofile', $idProfile)->first();
                }
                $dataSurveilansFR->statusgizi = isset($dataFaktorResiko['statusgizi']) ? $dataFaktorResiko['statusgizi'] : '';
                $dataSurveilansFR->dm = isset($dataFaktorResiko['dm']) ?  $dataFaktorResiko['dm'] : '';
                $dataSurveilansFR->guladarah = isset($dataFaktorResiko['guladarah']) ? $dataFaktorResiko['guladarah'] : '';
                $dataSurveilansFR->merokok = isset($dataFaktorResiko['merokok']) ? $dataFaktorResiko['merokok'] : '';
                $dataSurveilansFR->obesitas = isset($dataFaktorResiko['obesitas']) ? $dataFaktorResiko['obesitas'] : '';
                $dataSurveilansFR->pemeriksaankultur = isset($dataFaktorResiko['pemeriksaankultur']) ? $dataFaktorResiko['pemeriksaankultur'] : '';
                $dataSurveilansFR->temp = isset($dataFaktorResiko['temp']) ? $dataFaktorResiko['temp'] : '';
                $dataSurveilansFR->hasilkultur = isset($dataFaktorResiko['hasilkultur']) ? $dataFaktorResiko['hasilkultur'] : '';
                $dataSurveilansFR->tglinput = isset($dataFaktorResiko['tglinput']) ? $dataFaktorResiko['tglinput'] : '';
                $dataSurveilansFR->save();
            }

            if ($dataOperasi != "") {
                if ($dataHead['norec'] == "") {
                    $dataSurveilansOPM = new SurveilansOperasi();
                    $dataSurveilansOPM->kdprofile = $idProfile;
                    $dataSurveilansOPM->statusenabled = 1;
                    $dataSurveilansOPM->norec = $dataSurveilansOPM->generateNewId();
                    $dataSurveilansOPM->nosurvailensfk = $norecSurveilan;
                } else {
                    $dataSurveilansOPM = SurveilansOperasi::where('nosurvailensfk', $dataHead['norec'])->where('kdprofile', $idProfile)->first();
                }
                $dataSurveilansOPM->diagnosafk = $dataOperasi['diagnosafk'];
                $dataSurveilansOPM->keterangandiagnosa = $dataOperasi['keterangandiagnosa'];
                $dataSurveilansOPM->produkfk = $dataOperasi['produkfk'];
                $dataSurveilansOPM->tgloperasi = $dataOperasi['tgloperasi'] != 'Invalid date' ? $dataOperasi['tgloperasi'] : null;
                $dataSurveilansOPM->jamoperasi = $dataOperasi['jamoperasi'];
                $dataSurveilansOPM->menitoperasi = $dataOperasi['menitoperasi'];
                $dataSurveilansOPM->asascorefk = $dataOperasi['asascorefk'];
                $dataSurveilansOPM->tglinput = $dataOperasi['tglinput'];
                $dataSurveilansOPM->jenisoperasifk = $dataOperasi['jenisoperasifk'];
                $dataSurveilansOPM->score = $dataOperasi['score'];
                $dataSurveilansOPM->penyakitpenyerta = $dataOperasi['penyakitpenyerta'];
                $dataSurveilansOPM->implant = $dataOperasi['implant'];
                $dataSurveilansOPM->save();
            }

            if ($dataFrd != "") {
                foreach ($dataFrd as $hideung) {
                    if ($hideung['norec'] == "") {
                        $dataSaveFRD = new SurveilansFrd();
                        $dataSaveFRD->kdprofile = $idProfile;
                        $dataSaveFRD->statusenabled = 1;
                        $dataSaveFRD->norec = $dataSaveFRD->generateNewId();
                        $dataSaveFRD->nosurvailensfk = $norecSurveilan;
                    } else {
                        $dataSaveFRD = SurveilansFrd::where('norec', $hideung['norec'])
                            ->where('nosurvailensfk', $dataHead['norec'])
                            ->first();
                    }
                    //                    return $this->respond($hideung['tglakhir'] != "Invalid date");
                    if (isset($hideung['tglmulai']) && $hideung['tglmulai'] != "Invalid date") {
                        $dataSaveFRD->tglmulai = $hideung['tglmulai'];
                    }

                    if (isset($hideung['tglakhir']) && $hideung['tglakhir'] != "Invalid date") {
                        $dataSaveFRD->tglakhir = $hideung['tglakhir'];
                    }

                    if (isset($hideung['tglinfeksi']) && $hideung['tglinfeksi'] != "Invalid date") {
                        $dataSaveFRD->tglinfeksi = $hideung['tglinfeksi'];
                    }

                    $dataSaveFRD->tindakanoperasifk = $hideung['idtindakan'];
                    $dataSaveFRD->infeksifk = $hideung['idinfeksi'];
                    $dataSaveFRD->score = $hideung['score'];
                    $dataSaveFRD->status = $hideung['status'];
                    $dataSaveFRD->idsah = $hideung['idsah'];
                    $dataSaveFRD->idbatal = $hideung['idbatal'];
                    $dataSaveFRD->lamapasang = $hideung['lamapasang'];
                    $dataSaveFRD->hasilkultur = $hideung['hasilkultur'];
                    $dataSaveFRD->save();
                }
            }

            if ($dataAnti != "") {
                if (count($dataAnti) == 0) {
                    $dataSaveAnti = SurveilansAntibiotik::where('nosurvailensfk', $dataHead['norec'])
                        ->delete();
                }
                foreach ($dataAnti as $hideung) {
                    if ($hideung['norec'] == "") {
                        $dataSaveAnti = new SurveilansAntibiotik();
                        $dataSaveAnti->kdprofile = $idProfile;
                        $dataSaveAnti->statusenabled = 1;
                        $dataSaveAnti->norec = $dataSaveAnti->generateNewId();
                        $dataSaveAnti->nosurvailensfk = $norecSurveilan;
                    } else {
                        $dataSaveAnti = SurveilansAntibiotik::where('norec', $hideung['norec'])
                            ->where('nosurvailensfk', $dataHead['norec'])
                            ->first();
                    }

                    if (isset($hideung['tglmulai']) && $hideung['tglmulai'] != "Invalid date") {
                        $dataSaveAnti->tglmulai = $hideung['tglmulai'];
                    }

                    if (isset($hideung['tglakhir']) && $hideung['tglakhir'] != "Invalid date") {
                        $dataSaveAnti->tglakhir = $hideung['tglakhir'];
                    }

                    $dataSaveAnti->tindakanoperasifk = $hideung['idantibiotika'];
                    $dataSaveAnti->dosis = $hideung['dosis'];
                    $dataSaveAnti->metodepemberian = $hideung['metodepemberian'];
                    $dataSaveAnti->status = $hideung['status'];
                    $dataSaveAnti->sah = $hideung['idsah'];
                    $dataSaveAnti->batal = $hideung['idbatal'];
                    $dataSaveAnti->save();
                }
            }
            $jenisLog =  $dataHead['norec'] == "Input Data Surveilans" ? "" : "Edit Data Surveilans";
            $this->LOGGING(
                $jenisLog,
                $norecSurveilan,
                'norec surveilans',
                $jenisLog . "dengan nomer" . $norecSurveilan
            );


            $transStatus = 'true';
        } catch (Exception $e) {
            $transStatus = 'false';
            $transMessage = "Simpan Gagal";
        }

        if ($transStatus == 'true') {
            $transMessage = "Simpan Berhasil";
            DB::commit();
            $result = array(
                "status" => 201,
                "message" => $transMessage,
                "as" => 'ea@epic',
                "result" => $dataSurveilans
            );
        } else {
            $transMessage = "Simpan Gagal!!";
            DB::rollBack();
            $result = array(
                "status" => 400,
                "message" => $transMessage,
                "as" => 'ea@epic',
                "result" => $e->getMessage() . '-' . $e->getLine()
            );
        }
        return $this->respond($result['result'], $result['status'], $transMessage);
    }
    public function getHistorySurveilans(Request $request)
    {
        $idProfile = (int) $this->kdProfile;
        $data = DB::table('surveilans_t as sv')
            ->join('pasiendaftar_t as pd', 'pd.norec', '=', 'sv.noregistrasifk')
            ->join('antrianpasiendiperiksa_t as apd', 'apd.norec', '=', 'sv.norec_apd')
            ->join('pasien_m as pm', 'pm.id', '=', 'pd.nocmfk')
            ->join('ruangan_m as ru', 'ru.id', '=', 'apd.objectruanganfk')
            ->leftJoin('batalregistrasi_t as br', 'br.pasiendaftarfk', '=', 'pd.norec')
            ->select(DB::raw("sv.norec,sv.tglsurveilans,sv.nosurvailens,pm.nocm || ' / ' || pd.noregistrasi as nocmregis,
			                  pm.namapasien,ru.namaruangan"))
            ->where('sv.kdprofile', $idProfile)
            ->whereNull('br.norec')
            ->where('sv.statusenabled', true);

        $filter = $request->all();
        if (isset($filter['namaPasien']) && $filter['namaPasien'] != "" && $filter['namaPasien'] != "undefined") {
            $data = $data->where('pm.namapasien', 'ilike', '%' . $filter['namaPasien'] . '%');
        }
        if (isset($filter['noRegis']) && $filter['noRegis'] != "" && $filter['noRegis'] != "undefined") {
            $data = $data->where('pd.noregistrasi', 'ilike', '%' . $filter['noRegis'] . '%');
        }
        if (isset($filter['noCm']) && $filter['noCm'] != "" && $filter['noCm'] != "undefined") {
            $data = $data->where('pm.nocm', 'ilike', '%' . $filter['noCm'] . '%');
        }
        if (isset($filter['nocmfk']) && $filter['nocmfk'] != "" && $filter['nocmfk'] != "undefined") {
            $data = $data->where('pm.id', $filter['nocmfk']);
        }
        $data = $data->get();

        $result = array(
            'data' => $data,
            'message' => 'ea@epic',
        );
        return $this->respond($result);
    }
    public function hapusDataSurveilans(Request $request)
    {
        $idProfile = (int) $this->kdProfile;
        DB::beginTransaction();
        try {

            Surveilans::where('kdprofile', $idProfile)->where('norec', $request['norec'])
                ->update([
                    'statusenabled' => '0',
                ]);
            $this->LOGGING(
                'Batal Surveilans',
                $request['norec'],
                'norec surveilans',
                'Hapus Surveilans' . "dengan nomer" . $request['norec']
            );

            $transStatus = true;
        } catch (Exception $e) {
            $transStatus = false;
        }

        if ($transStatus) {
            $transMessage = 'Sukses';
            DB::commit();
            $result = array(
                'status' => 201,
                'message' => $transMessage,
                'as' => 'ea@epic',
                'result' => []
            );
        } else {
            $transMessage = 'Hapus Gagal';
            DB::rollBack();
            $result = array(
                'status' => 400,
                'message' => $transMessage,
                'as' => 'ea@epic',
                'result' => $e->getMessage() . '-' . $e->getLine()
            );
        }
        return $this->respond($result['result'], $result['status'], $transMessage);
    }
    public function  hapusRiwayat(Request $request)
    {
        $kdProfile = (int) $this->kdProfile;
        DB::beginTransaction();
        try {

            RiwayatPMKP::where('norec', $request['norec'])
                ->where('kdprofile', $kdProfile)
                ->update([
                    'statusenabled' => false
                ]);

            $transStatus = 'true';
        } catch (Exception $e) {
            $transStatus = 'false';
        }

        if ($transStatus == 'true') {
            $transMessage = "Sukses ";
            DB::commit();
            $result = array(
                "status" => 201,
                "message" => $transMessage,
                "as" => 'win@success',
                "result" => []
            );
        } else {
            $transMessage = "Hapus Gagal!!";
            DB::rollBack();
            $result = array(
                "status" => 400,
                "message"  => $transMessage,
                "as" => 'win@error',
                "result" => $e->getMessage() . '-' . $e->getLine()
            );
        }
        return $this->respond($result['result'], $result['status'], $transMessage);
    }
    public function getDataIPCLN(Request $request)
    {
        $kdProfile = $this->kdProfile;
        $data = DB::table('edukasiipcln_t as ei')
            ->join('pegawai_m as pg', 'pg.id', '=', 'ei.objectpegawaifk')
            ->join('ruangan_m as ru', 'ru.id', '=', 'ei.objectruanganfk')
            ->select(DB::raw("ei.*,ru.namaruangan,pg.namalengkap as petugas"))
            ->where('ei.kdprofile', (int)$kdProfile)
            ->where('ei.statusenabled', true);
        if (
            isset($request['bulan']) &&
            $request['bulan'] != "" &&
            $request['bulan'] != "undefined"
        ) {
            $tgl = $request['bulan'];
            $data = $data->whereRaw("to_char( ei.tglinput,'mm.yyyy')  ='$tgl' ");
        };
        if (
            isset($request['namalengkap']) &&
            $request['namalengkap'] != "" &&
            $request['namalengkap'] != "undefined"
        ) {
            $data = $data->where('ei.objectpegawaifk', '=', $request['namalengkap']);
        };
        if (
            isset($request['idRuangan']) &&
            $request['idRuangan'] != "" &&
            $request['idRuangan'] != "undefined"
        ) {
            $data = $data->where('ru.id', '=', $request['idRuangan']);
        };

        $data = $data->get();
        return $this->respond($data);
    }
}
