<?php

namespace App\Http\Controllers\Indikator;

use App\Http\Controllers\Controller;
use App\Models\Master\JenisPelayanan;
use App\Models\Master\Kelas;
use App\Models\Master\Produk;
use App\Models\Master\Ruangan;
use App\Models\Transaksi\IdentifikasiRisiko;
use App\Models\Transaksi\IdentifikasiRisikoDetail;
use App\Models\Transaksi\InsidenKeselamatanPasien;
use App\Models\Transaksi\LaporanInsidenInternal;
use App\Models\Transaksi\LembarKerjaInvestigasi;
use App\Models\Transaksi\RiskRegister;
use App\Traits\Valet;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;

class PMKPCtrl extends Controller
{
    use Valet;

    public function __construct()
    {
        parent::__construct($is_encrypt = true);
    }

    public function getDaftarIndikator(Request $request)
    {

        $data = DB::table('indikatorrensar_m as head')
            ->select(DB::raw("head.id,head.indikator,head.denominator,head.numerator,'NUM' as keterangan"))
            ->where('head.kdprofile', $this->kdProfile)
            ->where('head.statusenabled', true);
        if (isset($request['departemenfk'])) {
            $data = $data->where('head.objectdepartemenfk', $request['departemenfk']);
        }

        $dataSatu = DB::table('indikatorrensar_m as head')
            ->select(DB::raw("head.id,head.indikator,head.denominator,head.numerator,'DENUM' as keterangan"))
            ->where('head.kdprofile', $this->kdProfile)
            ->where('head.statusenabled', true);
        if (isset($request['departemenfk'])) {
            $dataSatu = $dataSatu->where('head.objectdepartemenfk', $request['departemenfk']);
        }
        $dataSatu = $dataSatu->union($data);

        $dataSatu = $dataSatu->orderBy('id', 'ASC');
        $dataSatu = $dataSatu->get();

        return $this->respond($dataSatu);
    }

    public function getHasilSensus(Request $request)
    {
        $res = DB::connection('mongodb')
            ->table('SensusPengukuranMutu')
            ->where('bulan', $request['bulan'])
            ->where('kdprofile', $this->kdProfile);
        if (isset($request['departemenfk'])) {
            $res = $res->where('departemenfk', (int)$request['departemenfk']);
        }
        $res = $res->get();
        return $this->respond($res);
    }

    public function simpanSensusMutu(Request $request)
    {

        DB::beginTransaction();
        try {
            $data = $request->input('data');
            $data['kdprofile'] = $this->kdProfile;
            if ($request->input('id') == '') {
                $data['id'] = $this->Uuid4();
                $data['created_at'] = date('Y-m-d H:i:s');
                $data['updated_at'] =  null;
                DB::connection('mongodb')
                    ->table($request->input('collection'))
                    ->insert($data);
            } else {
                $data['updated_at'] = date('Y-m-d H:i:s');
                $update = DB::connection('mongodb')
                    ->table($request->input('collection'))
                    ->where('id', $request->input('id'))
                    ->update($data);
            }

            DB::commit();
            $result = [
                'status' => 201,
                'message' => 'Simpan Data Berhasil',
            ];
        } catch (Exception $e) {
            DB::rollBack();
            $result = [
                'status' => 400,
                'data' => $e->getMessage(),
                'message' => 'Simpan Gagal !',
            ];
        }

        return $this->respond($result, $result['status'], $result['message']);
    }

    public function GetDaftarLaporanInsidenInternal(Request $request)
    {
        $dateRange = [$request->tglAwal, $request->tglAkhir];

        $data = DB::table('laporaninsideninternal_t as lki')
            ->JOIN('pasien_m as ps', 'ps.nocm', '=', 'lki.nocm')
            ->LEFTJOIN('ruangan_m as ru', 'ru.id', '=', 'lki.ruanganfk')
            ->LEFTJOIN('kelompokpasien_m as kp', 'kp.id', '=', 'lki.penanggungbiayapasienfk')
            ->LEFTJOIN('jeniskelamin_m as jk', 'jk.id', '=', 'lki.jeniskelaminfk')
            ->LEFTJOIN('insidenkeselamatan_m as ik', 'ik.id', '=', 'lki.insidenkeselamatanfk')
            ->LEFTJOIN('jeniskeselamatan_m as jkn', 'jkn.id', '=', 'ik.jeniskesalamatanfk')
            ->LEFTJOIN('lembarkerjainvestigasi_t AS lkt', 'lkt.laporaninsidenfk', '=', 'lki.norec')
            ->select(DB::raw("lki.*,ru.namaruangan,kp.kelompokpasien,ik.namakeselamatan as keselamatan,
                                     ik.jeniskesalamatanfk,jkn.jeniskeselamatan,lkt.norec as norec_lk,jk.jeniskelamin,ps.id as nocmfk"))
            ->where('lki.kdprofile', $this->kdProfile);
        if (isset($request->tglAwal)) {
            $data = $data->whereBetween(DB::raw('lki.tglinsiden::date'), $dateRange);
        }
        if (isset($request['idRuangan']) && $request['idRuangan'] != "" && $request['idRuangan'] != "undefined") {
            $data = $data->where('lki.ruanganfk', $request['idRuangan']);
        }
        if (isset($request['norec']) && $request['norec'] != "" && $request['norec'] != "undefined") {
            $data = $data->where('lki.norec', $request['norec']);
        }

        $data = $data->where('lki.statusenabled', true);
        $data = $data->get();
        $result = array(
            'data' => $data,
            'message' => 'ea@epic',
        );
        return $this->respond($result);
    }

    public function saveLaporanInsidenInternal(Request $request)
    {

        DB::beginTransaction();
        try {
            if ($request['data']['norec'] == '') {
                $data = new LaporanInsidenInternal();
                $data->norec = $data->generateNewId();
                $data->kdprofile = $this->kdProfile;
                $data->statusenabled = true;
            } else {
                $data = LaporanInsidenInternal::where('norec', $request['data']['norec'])->where('kdprofile', $this->kdProfile)->first();
            }
            $data->nocm = $request['data']['nocm'];
            $data->namapasien = $request['data']['namapasien'];
            $data->tglahir = $request['data']['tglahir'];
            $data->ruanganfk = $request['data']['ruanganfk'];
            $data->umur = $request['data']['umur'];
            $data->jeniskelaminfk = $request['data']['jeniskelaminfk'];
            $data->penanggungbiayapasienfk = $request['data']['penanggungbiayapasienfk'];
            $data->tglmasuk = $request['data']['tglmasuk'];
            $data->tglinsiden = $request['data']['tglinsiden'];
            $data->insiden = $request['data']['insiden'];
            $data->jenisinsiden = $request['data']['jenisinsiden'];
            $data->pelaporinsiden = $request['data']['pelaporinsiden'];
            // $data->insidenpenyangkut = $request['data']['insidenpenyangkut'];
            $data->tempatinsiden = $request['data']['tempatinsiden'];
            $data->insidenterjadi = $request['data']['insidenterjadi'];
            $data->jiwa = $request['data']['jiwa'];
            $data->unitterkait = $request['data']['unitterkait'];
            $data->akibatinsiden = $request['data']['akibatinsiden'];
            $data->penanganan = $request['data']['penanganan'];
            $data->dilakukanoleh = $request['data']['dilakukanoleh'];
            $data->kejadiansama = $request['data']['kejadiansama'];
            $data->langkahpenanganan = $request['data']['langkahpenanganan'];
            $data->pembuatlaporanfk = $request['data']['pembuatlaporanfk'];
            $data->pembuatlaporan = $request['data']['pembuatlaporan'];
            $data->tgllapor = $request['data']['tgllapor'];
            $data->penerimalaporanfk = $request['data']['penerimalaporanfk'];
            $data->penerimalaporan = $request['data']['penerimalaporan'];
            $data->tglterima = $request['data']['tglterima'];
            $data->grading = $request['data']['grading'];
            $data->insidenkeselamatanfk = $request['data']['insidenkeselamatanfk'];
            $data->noregistrasifk = $request['data']['noregistrasifk'];
            $data->save();

            DB::commit();
            $result = array(
                "status" => 201,
                "message" => 'Simpan Data Berhasil',
                "as" => 'ea@epic',
            );
        } catch (Exception $e) {
            DB::rollBack();
            $result = array(
                "status" => 400,
                "data" => $e->getMessage(),
                "message"  => 'Simpan Gagal !',
                "as" => 'ea@epic',
            );
        }

        return $this->respond($result, $result['status'], $result['message']);
    }

    public function deleteLaporanInsidenInternal(Request $request)
    {

        DB::beginTransaction();
        try {
            LaporanInsidenInternal::where('norec', $request['norec'])->where('kdprofile', $this->kdProfile)->update(['statusenabled' => false]);
            DB::commit();
            $result = [
                'status' => 200,
                'message' => 'Hapus Data Berhasil',
            ];
        } catch (Exception $e) {
            DB::rollBack();
            $result = [
                'status' => 400,
                'resp' => $e->getMessage(),
                'message' => 'Hapus Data Gagal',
            ];
        }

        return $this->respond($result, $result['status'], $result['message']);
    }
    public function getDataCombo(Request $request)
    {
        $kdProfile = $this->kdProfile;
        $ruangan = DB::table('ruangan_m')
            ->select('id', 'namaruangan')
            ->where('kdprofile', $kdProfile)
            ->where('statusenabled', true)
            ->get();

        $ruanganrajal = DB::table('ruangan_m')
            ->select('id', 'namaruangan')
            ->where('kdprofile', $kdProfile)
            ->where('statusenabled', true)
            ->whereIn('objectdepartemenfk', [18, 24, 26, 27, 3, 28, 29, 30])
            ->get();
        $ruanganranap = DB::table('ruangan_m')
            ->select('id', 'namaruangan')
            ->where('kdprofile', $kdProfile)
            ->where('statusenabled', true)
            ->whereIn('objectdepartemenfk', [16, 36, 25])
            ->get();

        // $keselamatanToJenis = DB::table('insidenkeselamatan_m as ik')
        //                         ->join('jeniskeselamatan_m as jk','jk.id','ik.jeniskeselamatanfk')
        //                         ->select('ik.namakeselamatan', 'ik.jeniskesalamatanfk', 'jk.jeniskeselamatan','ik.id as idik')
        //                         ->where('ik.statusenabled',true)
        //                         ->where('jk.statusenabled',true)
        //                         ->where('ik.kdprofile',$this->kdProfile)
        //                         ->get();

        $JenisKeselamatan = DB::table('jeniskeselamatan_m')
            ->select('id', 'jeniskeselamatan')
            ->where('kdprofile', $kdProfile)
            ->where('statusenabled', true)
            ->get();

        $Keselamatan = DB::table('insidenkeselamatan_m')
            ->select('id', 'namakeselamatan', 'jeniskesalamatanfk', 'namakeselamatan as keselamatan')
            ->where('kdprofile', $kdProfile)
            ->where('statusenabled', true)
            ->get();

        $dataInstalasi = DB::table('departemen_m as dp')
            ->where('dp.kdprofile', $kdProfile)
            ->where('dp.statusenabled', true)
            ->orderBy('dp.namadepartemen')
            ->get();

        $dataKeselamatanInsidenPasien = DB::table('jeniskeselamatan_m as jk')
            ->join('insidenkeselamatan_m as ik', 'ik.jeniskesalamatanfk', '=', 'jk.id')
            ->selectRaw("ik.id,ik.jeniskesalamatanfk,ik.namakeselamatan as keselamatan,jk.jeniskeselamatan")
            ->where('jk.kdprofile', $kdProfile)
            ->where('jk.statusenabled', true)
            ->where('ik.statusenabled', true)
            ->orderBy('ik.id', 'ASC')
            ->get();

        // foreach ($JenisKeselamatan as $item) {
        //     $detail = [];
        //     foreach ($Keselamatan as $item2) {
        //         if ($item->id == $item2->jeniskesalamatanfk) {
        //             $detail[] = array(
        //                 'id' => $item2->id,
        //                 'keselamatan' => $item2->namakeselamatan,
        //             );
        //         }
        //     }

        //     $dataJenisKeselamatan[] = array(
        //         'id' => $item->id,
        //         'jeniskesalamatan' => $item->jeniskeselamatan,
        //         'keselamatan' => $detail,
        //     );
        // }

        $DimensiMutu = DB::table('dimensimutu_m')
            ->select('id', 'demensimutu')
            ->where('kdprofile', $kdProfile)
            ->where('statusenabled', true)
            ->get();
        $FrekuensiData = DB::table('frekuensidata_m')
            ->select('id', 'frekuensi')
            ->where('kdprofile', $kdProfile)
            ->where('statusenabled', true)
            ->get();
        $dataWaktuLaporan = DB::table('waktulaporan_m')
            ->select('id', 'waktulaporan')
            ->where('statusenabled', true)
            ->where('kdprofile', $kdProfile)
            ->orderBy('waktulaporan')
            ->get();
        $dataPeriodeAnalis = DB::table('periodeanalis_m')
            ->select('id', 'periode')
            ->where('kdprofile', $kdProfile)
            ->where('statusenabled', true)
            ->orderBy('periode')
            ->get();
        $dataMetologi = DB::table('metologi_m')
            ->selectRaw("id,metologi || ': ' || keterangan as metologi")
            ->where('kdprofile', $kdProfile)
            ->where('statusenabled', true)
            ->orderBy('id')
            ->get();
        $dataMetologiAna = DB::table('metologianalisisdata_m')
            ->selectRaw("id,analisisdata")
            ->where('kdprofile', $kdProfile)
            ->where('statusenabled', true)
            ->orderBy('id')
            ->get();
        $dataCakupan = DB::table('cakupandata_m')
            ->selectRaw("id,cakupandata")
            ->where('kdprofile', $kdProfile)
            ->where('statusenabled', true)
            ->orderBy('id')
            ->get();
        $dataPublikasi = DB::table('publikasidata_m')
            ->selectRaw("id,publikasidata")
            ->where('kdprofile', $kdProfile)
            ->where('statusenabled', true)
            ->orderBy('id')
            ->get();
        $dataKategoryIndikator = DB::table('kategoryindikator_m')
            ->selectRaw("id,kategoryindikator")
            ->where('kdprofile', $kdProfile)
            ->where('statusenabled', true)
            ->orderBy('id')
            ->get();
        $dataRegrading = DB::table('regrading_m')
            ->selectRaw("id,regrading")
            ->where('kdprofile', $kdProfile)
            ->where('statusenabled', true)
            ->orderBy('id')
            ->get();

        $dataKategoryRisiko = DB::table('kategoryrisiko_m')
            ->selectRaw("id,kategoryrisiko")
            ->where('kdprofile', $kdProfile)
            ->where('statusenabled', true)
            ->orderBy('id')
            ->get();

        $result = array(
            'ruangan' => $ruangan,
            'ruanganrajal' => $ruanganrajal,
            'ruanganranap' => $ruanganranap,
            'jeniskeselamatan' => $JenisKeselamatan,
            'departemen' => $dataInstalasi,
            'datakeselamatan' => $Keselamatan,
            'insidenkeselamtanpasien' => $dataKeselamatanInsidenPasien,
            'dimensimutu' => $DimensiMutu,
            'frekuensidata' => $FrekuensiData,
            'waktulaporan' => $dataWaktuLaporan,
            'periodeanalis' => $dataPeriodeAnalis,
            'metologi' => $dataMetologi,
            'metologiana' => $dataMetologiAna,
            'cakupandata' => $dataCakupan,
            'publikasidata' => $dataPublikasi,
            'kategory' => $dataKategoryIndikator,
            'regrading' => $dataRegrading,
            'kategoryrisiko' => $dataKategoryRisiko,
            'message' => 'ea@epic',
        );

        return $this->respond($result);
    }

    public function GetDaftarLembarInvestigasiSederhana(Request $request)
    {
        $dateRange = [$request->tglAwal, $request->tglAkhir];

        $data = DB::table('lembarkerjainvestigasi_t as lki')
            ->JOIN('laporaninsideninternal_t as lii', 'lii.norec', 'lki.laporaninsidenfk')
            ->JOIN('pasien_m as ps', 'ps.nocm', 'lii.nocm')
            ->JOIN('ruangan_m as ru', 'ru.id', 'lii.ruanganfk')
            ->LEFTJOIN('pegawai_m as pg', 'pg.id', '=', 'lki.penanggungjawabfk')
            ->LEFTJOIN('pegawai_m as pg1', 'pg1.id', '=', 'lki.pegawaifk')
            ->LEFTJOIN('insidenkeselamatan_m as ik', 'ik.id', '=', 'lki.insidenkeselamatanfk')
            ->LEFTJOIN('jeniskeselamatan_m as jkn', 'jkn.id', '=', 'ik.jeniskesalamatanfk')
            ->select(DB::raw("lki.*,pg.namalengkap as penanggungjawab, pg1.namalengkap as pegawai,ik.namakeselamatan as keselamatan,
                            ik.jeniskesalamatanfk,jkn.jeniskeselamatan,ps.id as nocmfk,ps.namapasien,lii.noregistrasifk,ru.namaruangan"))
            ->where('lki.kdprofile', $this->kdProfile)
            ->where('lki.statusenabled', true);

        if (isset($request->tglAwal)) {
            $data = $data->whereBetween(DB::raw('lki.tanggalmulai::date'), $dateRange);
        }

        if (isset($request['norec']) && $request['norec'] != "" && $request['norec'] != "undefined") {
            $data = $data->where('lki.norec', '=', $request['norec']);
        }
        if (isset($request['ruanganfk']) && $request['ruanganfk'] != "" && $request['ruanganfk'] != "undefined") {
            $data = $data->where('lii.ruanganfk', $request['ruanganfk']);
        }

        $data = $data->get();

        return $this->respond($data);
    }

    public function saveLembarKerjaInvestigasi(Request $request)
    {

        DB::beginTransaction();
        try {
            if ($request['data']['norec'] == '') {
                $data = new LembarKerjaInvestigasi();
                $data->norec = $data->generateNewId();
                $data->kdprofile = $this->kdProfile;
                $data->statusenabled = true;
                $data->laporaninsidenfk = $request['data']['insidenfk'];
            } else {
                $data = LembarKerjaInvestigasi::where('norec', $request['data']['norec'])->where('kdprofile', $this->kdProfile)->first();
            }

            $data->penyebabinsidenlangsung =  $request['data']['penyebabinsidenlangsung'];
            $data->latarbelakanginsiden =  $request['data']['latarbelakanginsiden'];
            $data->rekomendasi =  $request['data']['rekomendasi'];
            $data->penanggungjawabfk =  $request['data']['penanggungjawabfk'];
            $data->tanggalrekomendasi =  $request['data']['tanggalrekomendasi'];
            $data->tindakan =  $request['data']['tindakan'];
            $data->pegawaifk =  $request['data']['pegawaifk'];
            $data->namakepala =  $request['data']['namakepala'];
            $data->tanggalmulai =  $request['data']['tanggalmulai'];
            $data->tanggalakhir =  $request['data']['tanggalakhir'];
            $data->tanggaltindakan =  $request['data']['tanggaltindakan'];
            $data->investigasilengkap =  $request['data']['investigasilengkap'];
            $data->investigasilanjutan =  $request['data']['investigasilanjutan'];
            $data->regrading =  $request['data']['regrading'];
            $data->regradingfk =  $request['data']['regradingfk'];
            $data->save();

            DB::commit();
            $result = array(
                "status" => 201,
                "message" => "Simpan Data Berhasil",
                "res" => $data,
                "as" => 'ea@epic',
            );
        } catch (Exception $e) {

            DB::rollBack();
            $result = array(
                "status" => 400,
                "res" => $e->getMessage(),
                "message"  => "Simpan Gagal !",
                "as" => 'ea@epic',
            );
        }

        return $this->respond($result, $result['status'], $result['message']);
    }


    public function  hapusDataLembarInvestigasi(Request $request)
    {

        DB::beginTransaction();
        try {
            LembarKerjaInvestigasi::where('norec', $request['norec'])->where('kdprofile', $this->kdProfile)->update(['statusenabled' => false]);

            DB::commit();
            $result = array(
                "status" => 201,
                "message" => 'Berhasil Hapus Data',
                "as" => 'ea@epic',
            );
        } catch (Exception $e) {
            DB::rollBack();
            $result = array(
                "status" => 400,
                "message"  => 'Simpan Gagal !',
                "as" => 'ea@epic',
            );
        }

        return $this->respond($result, $result['status'], $result['message']);
    }

    public function getLaporanSensusKeselamatanPasienBulanan(Request $request)
    {

        $data = DB::table('insidenkeselamatanpasien_t as lki')
        ->leftJoin('insidenkeselamatan_m as ik', 'ik.id', 'lki.keselamatanfk')
        ->leftJoin('jeniskeselamatan_m as jk', 'jk.id', 'ik.jeniskesalamatanfk')
        ->selectRaw(
            "EXTRACT(MONTH FROM lki.tanggal) AS bln,to_char(lki.tanggal, 'Mon') AS bulan,jk.jeniskeselamatan,ik.namakeselamatan AS keselamatan,
            lki.keselamatanfk,SUM(lki.jumlah) AS total,
            SUM(CASE WHEN EXTRACT(MONTH FROM lki.tanggal) = 1 THEN lki.jumlah ELSE null END) AS Jan,
            SUM(CASE WHEN EXTRACT(MONTH FROM lki.tanggal) = 2 THEN lki.jumlah ELSE null END) AS Feb,
            SUM(CASE WHEN EXTRACT(MONTH FROM lki.tanggal) = 3 THEN lki.jumlah ELSE null END) AS Mar,
            SUM(CASE WHEN EXTRACT(MONTH FROM lki.tanggal) = 4 THEN lki.jumlah ELSE null END) AS Apr,
            SUM(CASE WHEN EXTRACT(MONTH FROM lki.tanggal) = 5 THEN lki.jumlah ELSE null END) AS May,
            SUM(CASE WHEN EXTRACT(MONTH FROM lki.tanggal) = 6 THEN lki.jumlah ELSE null END) AS Jun,
            SUM(CASE WHEN EXTRACT(MONTH FROM lki.tanggal) = 7 THEN lki.jumlah ELSE null END) AS Jul,
            SUM(CASE WHEN EXTRACT(MONTH FROM lki.tanggal) = 8 THEN lki.jumlah ELSE null END) AS Aug,
            SUM(CASE WHEN EXTRACT(MONTH FROM lki.tanggal) = 9 THEN lki.jumlah ELSE null END) AS Sep,
            SUM(CASE WHEN EXTRACT(MONTH FROM lki.tanggal) = 10 THEN lki.jumlah ELSE null END) AS Oct,
            SUM(CASE WHEN EXTRACT(MONTH FROM lki.tanggal) = 11 THEN lki.jumlah ELSE null END) AS Nov,
            SUM(CASE WHEN EXTRACT(MONTH FROM lki.tanggal) = 12 THEN lki.jumlah ELSE null END) AS Dec
            "
        )
            ->where('lki.kdprofile', $this->kdProfile)
            ->where('lki.statusenabled', true)
            ->where(DB::raw("to_char(lki.tanggal, 'YYYY')"), $request['tahun'])
            ->groupByRaw("EXTRACT(MONTH FROM lki.tanggal),to_char(lki.tanggal, 'Mon'),ik.namakeselamatan,jk.jeniskeselamatan,lki.keselamatanfk")
            ->get();

        // $kdProfile = $this->kdProfile;
        // $tahun = $request['tahun'];
        // $ruanganId = $request['ruanganfk'];
        // $paramRuangan = ' ';
        // if (isset($ruanganId) && $ruanganId != "" && $ruanganId != "undefined") {
        //     $paramRuangan = ' and ru.id = ' . $ruanganId;
        // }

        // $data = DB::select(DB::raw("SELECT x.namapasien,x.noregistrasi,x.namaruangan,x.tglinsiden,x.insiden,SUM(x.sentinel) AS sentinel,SUM(x.ktd) AS ktd,SUM(x.ktc) AS ktc,
		// 	                               SUM(x.knc) as knc,SUM(x.kpc) as kpc,x.regrading,x.bulan
        //                     FROM (SELECT CASE WHEN pm.namapasien IS NULL THEN ii.namapasien ELSE pm.namapasien || ' (' || pm.nocm || ')' END AS namapasien,
        //                          ii.tglinsiden,pd.noregistrasi,ru.namaruangan,ii.insiden,CASE WHEN ikn.jeniskesalamatanfk = 1 THEN 1 ELSE 0 END AS sentinel,
        //                          CASE WHEN ikn.jeniskesalamatanfk = 2 THEN 1 ELSE 0 END AS ktd,
        //                          CASE WHEN ikn.jeniskesalamatanfk = 3 THEN 1 ELSE 0 END AS ktc,
        //                          CASE WHEN ikn.jeniskesalamatanfk = 4 THEN 1 ELSE 0 END AS knc,
        //                          CASE WHEN ikn.jeniskesalamatanfk = 5 THEN 1 ELSE 0 END AS kpc,lk.regrading,
        //                          CASE WHEN to_char(ii.tglinsiden,'M') = '1' THEN 'Januari ' || to_char(ii.tglinsiden,'YYYY')
        //                               WHEN to_char(ii.tglinsiden,'M') = '2' THEN 'Februari ' || to_char(ii.tglinsiden,'YYYY')
        //                               WHEN to_char(ii.tglinsiden,'M') = '3' THEN 'Maret ' || to_char(ii.tglinsiden,'YYYY')
        //                               WHEN to_char(ii.tglinsiden,'M') = '4' THEN 'April ' || to_char(ii.tglinsiden,'YYYY')
        //                               WHEN to_char(ii.tglinsiden,'M') = '5' THEN 'Mei ' || to_char(ii.tglinsiden,'YYYY')
        //                               WHEN to_char(ii.tglinsiden,'M') = '6' THEN 'Juni ' || to_char(ii.tglinsiden,'YYYY')
        //                               WHEN to_char(ii.tglinsiden,'M') = '7' THEN 'Juli ' || to_char(ii.tglinsiden,'YYYY')
        //                               WHEN to_char(ii.tglinsiden,'M') = '8' THEN 'Agustus ' || to_char(ii.tglinsiden,'YYYY')
        //                               WHEN to_char(ii.tglinsiden,'M') = '9' THEN 'September ' || to_char(ii.tglinsiden,'YYYY')
        //                               WHEN to_char(ii.tglinsiden,'M') = '10' THEN 'Oktober ' || to_char(ii.tglinsiden,'YYYY')
        //                               WHEN to_char(ii.tglinsiden,'M') = '11' THEN 'November ' || to_char(ii.tglinsiden,'YYYY')
        //                               WHEN to_char(ii.tglinsiden,'M') = '12' THEN 'Desember ' || to_char(ii.tglinsiden,'YYYY') END AS bulan
        //                     FROM laporaninsideninternal_t as ii
        //                     LEFT JOIN pasiendaftar_t as pd on pd.norec = ii.noregistrasifk
        //                     INNER JOIN lembarkerjainvestigasi_t as lk on lk.laporaninsidenfk = ii.norec
        //                     INNER JOIN insidenkeselamatan_m as ikn on ikn.id = ii.insidenkeselamatanfk
        //                     INNER JOIN jeniskeselamatan_m as jk on jk.id = ikn.jeniskesalamatanfk
        //                     LEFT JOIN pasien_m as pm on pm.id = pd.nocmfk
        //                     LEFT JOIN ruangan_m as ru on ru.id = pd.objectruanganlastfk
        //                     WHERE ii.kdprofile = $kdProfile and

        //                     to_char(ii.tglinsiden,'YYYY') = '$tahun'
        //                     $paramRuangan ) as x
        //                     GROUP BY x.namapasien,x.noregistrasi,x.namaruangan,x.tglinsiden,x.insiden,x.regrading,x.bulan"));

        // $result = array(
        //     'data' => $data,
        //     'message' => 'ea@epic',
        // );
        return $this->respond($data);
    }

    public function saveInsidenKeselamatan(Request $request)
    {

        DB::beginTransaction();
        try {
            if ($request['norec'] == '') {
                $data = new InsidenKeselamatanPasien();
                $data->norec = $data->generateNewId();
                $data->kdprofile = $this->kdProfile;
                $data->statusenabled = true;
            } else {
                $data = InsidenKeselamatanPasien::where('norec', $request['norec'])->where('kdprofile', $this->kdProfile)->first();
            }
            $data->tanggal = $request['tanggal'];
            $data->departemenfk = $request['departemenfk'];
            $data->keselamatanfk = $request['keselamatanfk'];
            $data->pegawaifk = $this->getUserId();
            $data->jumlah = $request['jumlah'];
            $data->tgl = $request['tanggal'];
            $data->save();

            DB::commit();
            $result = array(
                "status" => 201,
                "message" => "Data Berhasil disimpan",
                "res" => $data,
                "as" => 'ea@epic',
            );
        } catch (Exception $e) {
            DB::rollBack();
            $result = array(
                "status" => 400,
                "message"  => "Simpan Gagal !",
                "data" => $e->getMessage(),
            );
        }

        return $this->respond($result, $result['status'], $result['message']);
    }

    public function GetDaftarInsidenKeselamatanPasien(Request $request)
    {
        $dateRange = [$request->tglAwal, $request->tglAkhir];
        $data = DB::table('insidenkeselamatanpasien_t as lki')
                ->LEFTJOIN('insidenkeselamatan_m as ik', 'ik.id', 'lki.keselamatanfk')
                ->LEFTJOIN('jeniskeselamatan_m as jk', 'jk.id', 'ik.jeniskesalamatanfk')
                ->LEFTJOIN('departemen_m as dept', 'dept.id', 'lki.departemenfk')
                ->selectRaw(
            "lki.*, dept.namadepartemen as departemen, ik.namakeselamatan as keselamatan,lki.keselamatanfk as idkeselamatan,
                    ik.jeniskesalamatanfk,jk.jeniskeselamatan,EXTRACT(month from lki.tanggal) as bulan,EXTRACT(day from lki.tanggal) as tgl")
                ->where('lki.kdprofile', $this->kdProfile);

        if (isset($request['bulan']) && $request['bulan'] != "" && $request['bulan'] != "undefined") {
            $data = $data->where(DB::raw("to_char(lki.tanggal,'YYYY-MM')") , $request['bulan']);
        }
        if (isset($request['tglAwal']) && $request['tglAwal'] != "" && $request['tglAwal'] != "undefined") {
            $data = $data->whereBetween(DB::raw('lki.tanggal::date'), $dateRange);
        }
        if (isset($request['departemenfk']) && $request['departemenfk'] != "" && $request['departemenfk'] != "undefined") {
            $data = $data->where('lki.departemenfk', $request['departemenfk']);
        }
        if (isset($request['jeniskesalamatanfk']) && $request['jeniskesalamatanfk'] != "" && $request['jeniskesalamatanfk'] != "undefined") {
            $data = $data->where('ik.jeniskesalamatanfk', '=', $request['jeniskesalamatanfk']);
        }
        if (isset($request['keselamatanfk']) && $request['keselamatanfk'] != "" && $request['keselamatanfk'] != "undefined") {
            $data = $data->where('lki.keselamatanfk', '=', $request['keselamatanfk']);
        }

        $data = $data->where('lki.statusenabled', true);
        $data = $data->get();

        return $this->respond($data);
    }

    public function hapusInsidenKeselamatanPasien(Request $request)
    {

        DB::beginTransaction();
        try {
            InsidenKeselamatanPasien::where('norec', $request['norec'])->where('kdprofile', $this->kdProfile)->update(['statusenabled' => false]);

            DB::commit();
            $result = array(
                "status" => 201,
                "message" => 'Berhasil Hapus Data',
                "as" => 'ea@epic',
            );
        } catch (Exception $e) {
            DB::rollBack();
            $result = array(
                "status" => 400,
                "message"  => 'Simpan Gagal !',
                "as" => 'ea@epic',
            );
        }

        return $this->respond($result, $result['status'], $result['message']);
    }

    public function GetDaftarLaporanIdentifikasiRisiko(Request $request)
    {

        $dateRange = [$request->tglAwal, $request->tglAkhir];

        $data = DB::table('identifikasirisiko_t as ir')
            ->LEFTJOIN('kategoryrisiko_m as kr', 'kr.id', 'ir.kategoririsikofk')
            ->LEFTJOIN('departemen_m as dept', 'dept.id', 'ir.departemenfk')
            ->LEFTJOIN('pegawai_m as pg', 'pg.id', 'ir.kainstalasifk')
            ->LEFTJOIN('pegawai_m as pg1', 'pg1.id', 'ir.kepalabidangfk')
            ->LEFTJOIN('pegawai_m as pg2', 'pg2.id', 'ir.direkturfk')
            ->SELECT(DB::raw("ir.*,dept.namadepartemen,kr.kategoryrisiko,pg.namalengkap as kainstalasi,
                        pg1.namalengkap as kabidang,pg2.namalengkap as direktur"))
            ->where('ir.kdprofile', $this->kdProfile)
            ->where('ir.statusenabled',true);

        if (isset($request['tglAwal'])) {
            $data = $data->whereBetween(DB::raw("ir.tanggal::date"), $dateRange);
        }

        if (isset($request['departemenfk']) && $request['departemenfk'] != "" && $request['departemenfk'] != "undefined") {
            $data = $data->where('ir.departemenfk', $request['departemenfk']);
        }
        if (isset($request['norec'])) {
            $data = $data->where('ir.norec', $request['norec']);
        }
        $data = $data->get();

        $details = DB::table('identifikasirisikodetail_t')
            ->selectRaw('norec,identifikasirisikofk,jenisrisiko,keparahan,
                                 kemungkinan,skor,rangkingrisiko,pengendalian,rangkingaction')
            ->where('kdprofile', $this->kdProfile)
            ->where('statusenabled', true);
        if (isset($request['tglAwal'])) {
            $details = $details->whereBetween(DB::raw('tanggal::date'), $dateRange);
        }
        $details = $details->get();

        $result = [];
        foreach ($data as $item) {
            $detailss = [];
            foreach ($details as $detail) {
                if ($item->norec == $detail->identifikasirisikofk) {
                    $detailss[] = $detail;
                }
            }
            $result[] = array(
                'tanggal' => $item->tanggal,
                'norec' => $item->norec,
                'kategoririsikofk' => $item->kategoririsikofk,
                'kategoryrisiko' => $item->kategoryrisiko,
                'departemenfk' => $item->departemenfk,
                'namadepartemen' => $item->namadepartemen,
                'kainstalasifk' => $item->kainstalasifk,
                'kainstalasi' => $item->kainstalasi,
                'kepalabidangfk' => $item->kepalabidangfk,
                'kabidang' => $item->kabidang,
                'direkturfk' => $item->direkturfk,
                'direktur' => $item->direktur,
                'details' => $detailss,
            );
        }

        return $this->respond($result);
    }

    public function saveIdentifikasiRisiko(Request $request)
    {

        DB::beginTransaction();
        try {
            if ($request['norec'] == '') {
                $data = new IdentifikasiRisiko();
                $data->norec = $data->generateNewId();
                $data->kdprofile = $this->kdProfile;
                $data->statusenabled = true;
            } else {
                $data = IdentifikasiRisiko::where('norec', $request['norec'])->where('kdprofile', $this->kdProfile)->first();
                IdentifikasiRisikoDetail::where('identifikasirisikofk', $request['norec'])->where('kdprofile', $this->kdProfile)->delete();
            }
            $data->departemenfk = $request['departemenfk'];
            $data->kategoririsikofk = $request['kategoririsikofk'];
            $data->kainstalasifk = $request['instalasi'];
            $data->kepalabidangfk = $request['kplabidang'];
            $data->direkturfk = $request['direktur'];
            $data->pegawaifk = $this->getUserId();;
            $data->tanggal = $request['tanggal'];
            $data->save();
            $dataNorec = $data->norec;

            foreach ($request['details'] as $item) {
                $dataOP = new IdentifikasiRisikoDetail();
                $dataOP->norec = $dataOP->generateNewId();
                $dataOP->kdprofile = $this->kdProfile;
                $dataOP->statusenabled = true;
                $dataOP->jenisrisiko = $item['jenisrisiko'];
                $dataOP->identifikasirisikofk = $dataNorec;
                $dataOP->keparahan = $item['keparahan'];
                $dataOP->kemungkinan = $item['kemungkinan'];
                $dataOP->tanggal = $request['tanggal'];
                $dataOP->skor = $item['skor'];
                $dataOP->rangkingrisiko = $item['rangkingrisiko'];
                $dataOP->pengendalian = $item['pengendalian'];
                $dataOP->rangkingaction = $item['rangkingaction'];
                $dataOP->save();
            }

            DB::commit();
            $result = array(
                "status" => 201,
                "message" => "Simpan Data Berhasil",
                "data" => $data,
                "as" => 'ea@epic',
            );
        } catch (Exception $e) {
            DB::rollBack();
            $result = array(
                "status" => 400,
                "message"  => "Simpan Gagal !",
                "data" => $e->getMessage(),
                "as" => 'ea@epic',
            );
        }

        return $this->respond($result, $result['status'], $result['message']);
    }

    public function hapusIdentifikasiResiko(Request $request)
    {

        DB::beginTransaction();
        try {
            IdentifikasiRisiko::where('norec', $request['norec'])->where('kdprofile', $this->kdProfile)->update(['statusenabled' => false]);
            IdentifikasiRisikoDetail::where('identifikasirisikofk', $request['norec'])->where('kdprofile', $this->kdProfile)->update(['statusenabled' => false]);

            DB::commit();
            $result = array(
                "status" => 201,
                "message" => 'Berhasil Hapus Data',
                "as" => 'ea@epic',
            );
        } catch (Exception $e) {
            DB::rollBack();
            $result = array(
                "status" => 400,
                "message"  => 'Simpan Gagal !',
                "as" => 'ea@epic',
            );
        }

        return $this->respond($result, $result['status'], $result['message']);
    }

    public function getLaporanKematianPasienIgd(Request $request)
    {
        $dateRange = [$request->tglAwal, $request->tglAkhir];

        $data = DB::table('pasiendaftar_t as pd')
            ->JOIN('pasien_m as pm', 'pm.id', '=', 'pd.nocmfk')
            ->JOIN('jeniskelamin_m as jk', 'jk.id', '=', 'pm.objectjeniskelaminfk')
            ->LEFTJOIN('alamat_m as alm', 'alm.nocmfk', '=', 'pm.id')
            ->JOIN('ruangan_m as ru', 'ru.id', '=', 'pd.objectruanganlastfk')
            ->JOIN('kelompokpasien_m as kp', 'kp.id', '=', 'pd.objectkelompokpasienlastfk')
            ->select(DB::raw("pm.nocm,pd.noregistrasi,pm.namapasien,to_char(pm.tgllahir,'DD-MM-YYYY') as tgllahir,
			                 to_char(pd.tglregistrasi,'DD-MM-YYYY') as tglregistrasi,ru.namaruangan,pd.tglmeninggal"))
            ->whereRaw(" EXTRACT(hour from AGE(pd.tglregistrasi, pd.tglmeninggal)) < 24")
            ->whereBetween(DB::raw('pd.tglregistrasi::date'), $dateRange)
            ->where('pd.kdprofile', $this->kdProfile);

        if (isset($request['ruanganfk']) && $request['ruanganfk'] != "" && $request['ruanganfk'] != "undefined") {
            $data = $data->where('ru.id', $request['ruanganfk']);
        }

        $data =  $data->get();

        return $this->respond($data);
    }

    public function getDataLaporanDokterPelayananPoliklinik(Request $request)
    {

        $kdProfile = $this->kdProfile;
        $tglAwal = $request['tglAwal'];
        $tglAkhir = $request['tglAkhir'];
        $ruanganId = $request['ruanganfk'];
        $idDokter = $request['idDokter'];
        $paramRuangan = ' ';
        if (isset($ruanganId) && $ruanganId != "" && $ruanganId != "undefined") {
            $paramRuangan = ' and ru.id = ' . $ruanganId;
        }

        $paramDokter = ' ';
        if (isset($idDokter) && $idDokter != "" && $idDokter != "undefined") {
            $paramDokter = ' and pg.id = ' . $idDokter;
        }

        $data = DB::select(DB::raw("SELECT x.namaruangan,x.namalengkap,SUM(x.jumlah) as jumlah
                FROM (SELECT pp.tglpelayanan,ru.namaruangan,ppp.objectpegawaifk,pg.namalengkap,COUNT(pg.namalengkap) as jumlah
                FROM pasiendaftar_t as pd
                INNER JOIN antrianpasiendiperiksa_t as apd on apd.noregistrasifk = pd.norec
                INNER JOIN pelayananpasien_t as pp on pp.noregistrasifk = apd.norec
                INNER JOIN pelayananpasienpetugas_t as ppp on ppp.pelayananpasien = pp.norec
                INNER JOIN pasien_m as pm on pm.id = pd.nocmfk
                INNER JOIN jeniskelamin_m as jk on jk.id = pm.objectjeniskelaminfk
                LEFT JOIN alamat_m as alm on alm.nocmfk = pm.id
                LEFT JOIN pegawai_m as pg on pg.id = ppp.objectpegawaifk
                INNER JOIN ruangan_m as ru on ru.id = apd.objectruanganfk
                INNER JOIN kelompokpasien_m as kp on kp.id = pd.objectkelompokpasienlastfk
                WHERE pd.kdprofile = $kdProfile and ppp.objectjenispetugaspefk = 4 AND ppp.objectpegawaifk <> 1
                AND ru.objectdepartemenfk in (18,24,26,27,3,28,29,30)
                AND pp.tglpelayanan::date BETWEEN '$tglAwal' and '$tglAkhir'
                $paramRuangan
                $paramDokter
                GROUP BY pp.tglpelayanan,namaruangan,ppp.objectpegawaifk,pg.namalengkap)as x
                GROUP BY x.namaruangan,x.namalengkap"));

        $result = array(
            'data' => $data,
            'message' => 'ea@epic',
        );
        return $this->respond($result);
    }

    public function getDataLaporanDokterPelayananRanap(Request $request)
    {
        $kdProfile = $this->kdProfile;
        $data = [];
        $tglAwal = $request['tglAwal'];
        $tglAkhir = $request['tglAkhir'];
        $ruanganId = $request['ruanganfk'];
        $idDokter = $request['idDokter'];
        $paramRuangan = ' ';
        if (isset($ruanganId) && $ruanganId != "" && $ruanganId != "undefined") {
            $paramRuangan = ' and ru.id = ' . $ruanganId;
        }

        $paramDokter = ' ';
        if (isset($idDokter) && $idDokter != "" && $idDokter != "undefined") {
            $paramDokter = ' and pg.id = ' . $idDokter;
        }

        $data = DB::select(DB::raw("SELECT x.namaruangan,x.namalengkap,SUM(x.jumlah) as jumlah
                FROM (SELECT pp.tglpelayanan,ru.namaruangan,ppp.objectpegawaifk,pg.namalengkap,COUNT(pg.namalengkap) as jumlah
                FROM pasiendaftar_t as pd
                INNER JOIN antrianpasiendiperiksa_t as apd on apd.noregistrasifk = pd.norec
                INNER JOIN pelayananpasien_t as pp on pp.noregistrasifk = apd.norec
                INNER JOIN pelayananpasienpetugas_t as ppp on ppp.pelayananpasien = pp.norec
                INNER JOIN pasien_m as pm on pm.id = pd.nocmfk
                INNER JOIN jeniskelamin_m as jk on jk.id = pm.objectjeniskelaminfk
                LEFT JOIN alamat_m as alm on alm.nocmfk = pm.id
                LEFT JOIN pegawai_m as pg on pg.id = ppp.objectpegawaifk
                INNER JOIN ruangan_m as ru on ru.id = apd.objectruanganfk
                INNER JOIN kelompokpasien_m as kp on kp.id = pd.objectkelompokpasienlastfk
                WHERE pd.kdprofile = $kdProfile and ppp.objectjenispetugaspefk = 4 AND ppp.objectpegawaifk <> 1
                AND ru.objectdepartemenfk in (16,36,25)
                AND pp.tglpelayanan::date BETWEEN '$tglAwal' and '$tglAkhir'
                $paramRuangan
                $paramDokter
                GROUP BY pp.tglpelayanan,namaruangan,ppp.objectpegawaifk,pg.namalengkap)as x
                GROUP BY x.namaruangan,x.namalengkap"));

        $result = array(
            'data' => $data,
            'message' => 'ea@epic',
        );
        return $this->respond($result);
    }

    public function getDataLaporanDokterPenanggungJawabRanap(Request $request)
    {
        $kdProfile = $this->kdProfile;
        $tglAwal = $request['tglAwal'];
        $tglAkhir = $request['tglAkhir'];
        $ruanganId = $request['ruanganfk'];
        $idDokter = $request['idDokter'];
        $paramRuangan = ' ';
        if (isset($ruanganId) && $ruanganId != "" && $ruanganId != "undefined") {
            $paramRuangan = ' and ru.id = ' . $ruanganId;
        }

        $paramDokter = ' ';
        if (isset($idDokter) && $idDokter != "" && $idDokter != "undefined") {
            $paramDokter = ' and pg.id = ' . $idDokter;
        }

        $data = DB::select(DB::raw("SELECT x.namaruangan,x.namalengkap,SUM(x.jumlah) as jumlah
                FROM (SELECT apd.tglmasuk,ru.namaruangan,apd.objectpegawaifk,
                            CASE WHEN pg.namalengkap IS NULL THEN '-' ELSE pg.namalengkap END AS namalengkap,
                            COUNT(pg.namalengkap) as jumlah
                FROM pasiendaftar_t as pd
                INNER JOIN antrianpasiendiperiksa_t as apd on apd.noregistrasifk = pd.norec
                INNER JOIN pasien_m as pm on pm.id = pd.nocmfk
                INNER JOIN jeniskelamin_m as jk on jk.id = pm.objectjeniskelaminfk
                LEFT JOIN alamat_m as alm on alm.nocmfk = pm.id
                LEFT JOIN pegawai_m as pg on pg.id = apd.objectpegawaifk
                INNER JOIN ruangan_m as ru on ru.id = apd.objectruanganfk
                INNER JOIN kelompokpasien_m as kp on kp.id = pd.objectkelompokpasienlastfk
                WHERE pd.kdprofile = $kdProfile and ru.objectdepartemenfk in (16,36,25)
                AND apd.tglmasuk::date BETWEEN '$tglAwal' and '$tglAkhir'
                $paramRuangan
                $paramDokter
                GROUP BY apd.tglmasuk,namaruangan,apd.objectpegawaifk,pg.namalengkap)as x
                GROUP BY x.namaruangan,x.namalengkap"));

        $result = array(
            'data' => $data,
            'message' => 'ea@epic',
        );
        return $this->respond($result);
    }

    public function getLaporanJamVisiteDokter(Request $request)
    {
        $dateRange = [$request->tglAwal, $request->tglAkhir];

        $data = DB::table('pasiendaftar_t as pd')
            ->JOIN('antrianpasiendiperiksa_t as apd', 'apd.noregistrasifk', 'pd.norec')
            ->JOIN('pelayananpasien_t as pp', 'pp.noregistrasifk', 'apd.norec')
            ->JOIN('pelayananpasienpetugas_t as ppp', 'ppp.pelayananpasien', 'pp.norec')
            ->JOIN('pasien_m as pm', 'pm.id', 'pd.nocmfk')
            ->JOIN('jeniskelamin_m as jk', 'jk.id', 'pm.objectjeniskelaminfk')
            ->LEFTJOIN('alamat_m as alm', 'alm.nocmfk', 'pm.id')
            ->LEFTJOIN('pegawai_m as pg', 'pg.id', 'apd.objectpegawaifk')
            ->JOIN('ruangan_m as ru', 'ru.id', 'apd.objectruanganfk')
            ->JOIN('kelompokpasien_m as kp', 'kp.id', 'pd.objectkelompokpasienlastfk')
            ->JOIN('produk_m as pro', 'pro.id', 'pp.produkfk')
            ->select(DB::raw("pm.nocm,pd.noregistrasi,pm.namapasien,pp.tglpelayanan,pro.namaproduk,
			                CASE WHEN pg.namalengkap IS NULL THEN '-' ELSE pg.namalengkap END AS namalengkap,ru.namaruangan"))
            ->where('pd.kdprofile', $this->kdProfile)
            ->where('ppp.objectpegawaifk', '<>', 1)
            ->where('ppp.objectjenispetugaspefk', $this->settingFix('idDokterPemeriksa'))
            ->whereIn('ru.objectdepartemenfk', explode(',',$this->settingFix('kdDepartemenRanapFix')))
            ->whereIn('pp.produkfk', explode(',',$this->settingFix('idVisitDokter')))
            ->whereBetween(DB::raw('pp.tglpelayanan::date'),$dateRange);

        if (isset($request['ruanganfk']) && $request['ruanganfk'] != "" && $request['ruanganfk'] != "undefined") {
            $data = $data->where('ru.id', '=', $request['ruanganfk']);
        }
        if (isset($request['idDokter']) && $request['idDokter'] != "" && $request['idDokter'] != "undefined") {
            $data = $data->Where('pg.id', '=', $request['idDokter']);
        }
        $data =  $data->get();
        $result = array(
            'data' => $data,
            'message' => 'ea@epic',
        );
        return $this->respond($result);
    }

    public function getLaporanKematianPasienRanap(Request $request)
    {

        $dateRange = [$request->tglAwal, $request->tglAkhir];

        $data = DB::table('pasiendaftar_t as pd')
        ->JOIN('pasien_m as pm', 'pm.id', '=', 'pd.nocmfk')
        ->JOIN('jeniskelamin_m as jk', 'jk.id', '=', 'pm.objectjeniskelaminfk')
        ->LEFTJOIN('alamat_m as alm', 'alm.nocmfk', '=', 'pm.id')
        ->JOIN('ruangan_m as ru', 'ru.id', '=', 'pd.objectruanganlastfk')
        ->JOIN('kelompokpasien_m as kp', 'kp.id', '=', 'pd.objectkelompokpasienlastfk')
        ->select(DB::raw("pm.nocm,pd.noregistrasi,pm.namapasien,to_char(pm.tgllahir,'DD-MM-YYYY') as tgllahir,
			            to_char(pd.tglregistrasi,'DD-MM-YYYY') as tglregistrasi,ru.namaruangan,pd.tglmeninggal,
                        EXTRACT(DAY FROM age(NOW(), pd.tglmeninggal)) * 24 + EXTRACT(HOUR FROM age(NOW(), pd.tglmeninggal)) AS lama_meninggal
                        "))
        ->where('pd.kdprofile', $this->kdProfile)
        ->whereNotNull('pd.tglmeninggal')
        ->whereRaw("EXTRACT(DAY FROM age(NOW(), pd.tglmeninggal)) * 24 + EXTRACT(HOUR FROM age(NOW(), pd.tglmeninggal)) > 48")
        ->whereIn('ru.objectdepartemenfk',explode(',', $this->settingFix('kdDepartemenRanapFix')))
        ->whereBetween(DB::raw('pd.tglmeninggal::date'), $dateRange);

        if (isset($request['ruanganfk']) && $request['ruanganfk'] != "" && $request['ruanganfk'] != "undefined") {
            $data = $data->where('ru.id', '=', $request['ruanganfk']);
        }

        $data =  $data->get();
        $result = array(
            'data' => $data,
            'message' => 'ea@epic',
        );
        return $this->respond($result);
    }

    public function getLaporanPasienPulangPaksa(Request $request)
    {
        $dateRange = [$request->tglAwal, $request->tglAkhir];

        $data = DB::table('pasiendaftar_t as pd')
        ->JOIN('pasien_m as pm', 'pm.id', '=', 'pd.nocmfk')
        ->JOIN('jeniskelamin_m as jk', 'jk.id', '=', 'pm.objectjeniskelaminfk')
        ->LEFTJOIN('alamat_m as alm', 'alm.nocmfk', '=', 'pm.id')
        ->JOIN('ruangan_m as ru', 'ru.id', '=', 'pd.objectruanganlastfk')
        ->JOIN('kelompokpasien_m as kp', 'kp.id', '=', 'pd.objectkelompokpasienlastfk')
        ->select(DB::raw("pm.nocm,pd.noregistrasi,pm.namapasien,pm.tgllahir::date as tgllahir,
			         to_char(pd.tglregistrasi,'DD-MM-YYYY') as tglregistrasi,ru.namaruangan,to_char(pd.tglpulang,'DD-MM-YYYY') as tglpulang"))
        ->where('pd.kdprofile', $this->kdProfile)
        ->whereNotNull('pd.tglpulang')
        ->where('pd.objectstatuspulangfk', $this->settingFix('kdStatusPulangPaksa'))
        ->whereIn('ru.objectdepartemenfk', explode(',', $this->settingFix('kdDepartemenRanapFix')))
        ->whereBetween(DB::raw('pd.tglpulang::date'), $dateRange);

        if (isset($request['ruanganfk']) && $request['ruanganfk'] != "" && $request['ruanganfk'] != "undefined") {
            $data = $data->where('ru.id', '=', $request['ruanganfk']);
        }

        $data =  $data->get();
        $result = array(
            'data' => $data,
            'message' => 'ea@epic',
        );
        return $this->respond($result);
    }

    public function saveRiskRegister(Request $request)
    {

        DB::beginTransaction();
        try {
            if ($request['data']['norec'] == '') {
                $data = new RiskRegister();
                $data->norec = $data->generateNewId();
                $data->kdprofile = $this->kdProfile;
                $data->statusenabled = true;
                $data->identifikasiresikodetailfk = $request['data']['identifikasiresikodetailfk'];
                $data->identifikasiresikofk = $request['data']['identifikasiresikofk'];
            } else {
                $data = RiskRegister::where('norec', $request['data']['norec'])->where('kdprofile', $this->kdProfile)->first();
            }
            $data->tglpenilaian = $request['data']['tglpenilaian'];
            $data->tglevaluasi = $request['data']['tglevaluasi'];
            $data->tglsetujui = $request['data']['tglsetujui'];
            $data->tanggal = $request['data']['tanggal'];
            $data->tujuan = $request['data']['tujuan'];
            $data->lokasi = $request['data']['lokasi'];
            $data->pemilikrisiko = $request['data']['pemilikrisiko'];
            $data->penilaifk = $request['data']['penilaifk'];
            $data->pengevaluasifk = $request['data']['pengevaluasifk'];
            $data->penyetujuifk = $request['data']['penyetujuifk'];
            $data->deskripsirisiko = $request['data']['deskripsirisiko'];
            $data->dampak = $request['data']['dampak'];
            $data->penyebab = $request['data']['penyebab'];
            $data->upayakontrol = $request['data']['upayakontrol'];
            $data->efektifitas = $request['data']['efektifitas'];
            $data->dampakrisiko = $request['data']['dampakrisiko'];
            $data->kemungkinan = $request['data']['kemungkinan'];
            $data->level = $request['data']['level'];
            $data->evaluasirisiko = $request['data']['evaluasirisiko'];
            $data->tujuansasaran = $request['data']['tujuansasaran'];
            $data->rencanakegiatan = $request['data']['rencanakegiatan'];
            $data->penanggungjwabfk = $request['data']['penanggungjwabfk'];
            $data->jadwal = $request['data']['jadwal'];
            $data->statusjaminan = $request['data']['statusjaminan'];
            $data->laporansingkat = $request['data']['laporansingkat'];
            $data->ketlevel = $request['data']['ketlevel'];
            $data->save();

            DB::commit();
            $result = array(
                "status" => 201,
                "message" => "Simpan Data Berhasil",
                "data" => $data,
                "as" => 'ea@epic',
            );

        } catch (\Exception $e) {
            DB::rollBack();
            $result = array(
                "status" => 400,
                "message"  => "Simpan Gagal !",
                "data" => $e->getMessage(),
                "as" => 'ea@epic',
            );
        }

        return $this->respond($result,$result['status'],$result['message']);
    }

    public function getLaporanLamaHariPerawatanPasien(Request $request)
    {
        $dateRange = [$request->tglAwal, $request->tglAkhir];

        $data = DB::table('pasiendaftar_t as pd')
        ->JOIN('pasien_m as pm', 'pm.id', '=', 'pd.nocmfk')
        ->JOIN('jeniskelamin_m as jk', 'jk.id', '=', 'pm.objectjeniskelaminfk')
        ->LEFTJOIN('alamat_m as alm', 'alm.nocmfk', '=', 'pm.id')
        ->JOIN('ruangan_m as ru', 'ru.id', '=', 'pd.objectruanganlastfk')
        ->JOIN('kelompokpasien_m as kp', 'kp.id', '=', 'pd.objectkelompokpasienlastfk')
        ->select(DB::raw("pm.nocm,pd.noregistrasi,pm.namapasien,to_char(pm.tgllahir,'DD-MM-YYYY') as tgllahir,
                        pd.tglregistrasi::date,ru.namaruangan,pd.tglpulang::date,
                        EXTRACT(DAY FROM age(pd.tglpulang::date,pd.tglregistrasi::date)) as lamadirawat
                    "))
        ->where('pd.kdprofile', $this->kdProfile)
        ->whereNotNull('pd.tglpulang')
        ->whereIn('ru.objectdepartemenfk', explode(',', $this->settingFix('kdDepartemenRanapFix')))
        ->whereBetween(DB::raw('pd.tglregistrasi::date'), $dateRange);

        if (isset($request['ruanganfk']) && $request['ruanganfk'] != "" && $request['ruanganfk'] != "undefined") {
            $data = $data->where('ru.id', '=', $request['ruanganfk']);
        }

        $data =  $data->get();
        $result = array(
            'data' => $data,
            'message' => 'ea@epic',
        );
        return $this->respond($result);
    }

    public function getDataRekapSasaranMutu(Request $request)
    {
        $Bulan = $request['tahun'];
        $idDept = $request['idDept'];
        $kdProfile = (int) $this->kdProfile;
        $data = DB::select(DB::raw("SELECT x.bulan,x.indikator,x.target,SUM(x.num) as numerator,SUM(x.denum) as denumerator,((SUM(x.num) / SUM(x.denum))* 100) / 100 as capaian
                FROM (SELECT DISTINCT
                    sm.tglnilai,sm.tgl,CASE WHEN sm.keterangan = 'DENUM' THEN CAST(sm.nilai AS int) ELSE 0 END AS denum,
                    CASE WHEN sm.keterangan = 'NUM' THEN CAST(sm.nilai AS int) ELSE 0 END AS num,
                    CASE WHEN ir.targetpencapaian IS NULL THEN 0 ELSE ir.targetpencapaian END AS target,
                    CAST(sm.capaian AS int) as capaian,sm.keterangan,
                    ir.indikator,ir.denominator,ir.numerator,DATENAME(month, sm.tgl) as bulan
                FROM sasaranmutu_t AS sm
                INNER JOIN indikatorrensar_m AS ir ON ir.id = sm.indikatorfk
                WHERE sm.kdprofile = $kdProfile and ir.statusenabled = true
                AND extract (YEAR from sm.tglnilai) = $Bulan
                AND sm.departemenfk = $idDept) as x
                GROUP BY x.bulan,x.indikator,x.target"));

            return $data;
        $dataAnalisa = DB::select(DB::raw("SELECT ass.norec,ass.departemenfk,ass.tahun,ass.analisa,ass.tindaklanjut
                       FROM analisasasaranmutu_t AS ass
                       INNER JOIN departemen_m as dept on dept.id = ass.departemenfk
                       WHERE ass.kdprofile = $kdProfile and ass.tahun = $Bulan and ass.departemenfk = $idDept"));
        $result = array(
            'data' => $data,
            'analisa' => $dataAnalisa,
            'message' => 'as@epic',
        );
        return $this->respond($result);
    }

    public function getLaporanLamaHariPerawatanPasienGangguanJiwa(Request $request)
    {
        $dateRange = [$request->tglAwal, $request->tglAkhir];

        $data = DB::table('pasiendaftar_t as pd')
        ->JOIN('pasien_m as pm', 'pm.id', '=', 'pd.nocmfk')
        ->JOIN('jeniskelamin_m as jk', 'jk.id', '=', 'pm.objectjeniskelaminfk')
        ->LEFTJOIN('alamat_m as alm', 'alm.nocmfk', '=', 'pm.id')
        ->JOIN('ruangan_m as ru', 'ru.id', '=', 'pd.objectruanganlastfk')
        ->JOIN('kelompokpasien_m as kp', 'kp.id', '=', 'pd.objectkelompokpasienlastfk')
        ->select(DB::raw("pm.nocm,pd.noregistrasi,pm.namapasien,to_char(pm.tgllahir,'DD-MM-YYYY') as tgllahir,
                        pd.tglregistrasi::date,ru.namaruangan,pd.tglpulang::date,
                        EXTRACT(DAY FROM age(pd.tglpulang::date,pd.tglregistrasi::date)) as lamadirawat
                    "))
        ->where('pd.kdprofile', $this->kdProfile)
            ->whereNotNull('pd.tglpulang')
            ->whereIn('ru.objectdepartemenfk', explode(',', $this->settingFix('kdDepartemenRanapFix')))
            ->whereBetween(DB::raw('pd.tglregistrasi::date'), $dateRange);

        if (isset($request['ruanganfk']) && $request['ruanganfk'] != "" && $request['ruanganfk'] != "undefined") {
            $data = $data->where('ru.id', '=', $request['ruanganfk']);
        }

        $data =  $data->get();
        $result = array(
            'data' => $data,
            'message' => 'ea@epic',
        );
        return $this->respond($result);
    }

    public function getDaftarPenanganKeluhan(Request $request)
    {

        $dateRange = [$request->tglAwal, $request->tglAkhir];

        $data = DB::table('keluhanpelanggan_m as kp')
        ->leftJoin('penanganankeluhanpelanggan_t as pkp', 'pkp.keluhanpelangganfk','kp.id')
        ->leftJoin('penanganankeluhanpelanggand_t as pkpd', 'pkpd.penanganankeluhanfk','pkp.norec')
        ->leftJoin('pasien_m as ps', 'ps.nocm','kp.norm')
        ->leftJoin('alamat_m as al', 'al.nocmfk','ps.id')
        ->leftJoin('pekerjaan_m as pkr','pkr.id','kp.objectpekerjaanfk')
        ->leftJoin('jeniskelamin_m as jk','jk.id','ps.objectjeniskelaminfk')
        ->leftJoin('jeniskelamin_m as jk1','jk1.id','kp.objectjeniskelaminfk')
        ->leftJoin('ruangan_m as ru', 'ru.id','kp.objectruanganfk')
        ->leftJoin('pegawai_m as pg', 'pg.id','kp.objectpegawaifk')
        ->select(
            DB::raw('kp.norec,kp.id as kpid,kp.tglkeluhan,kp.tglorder,
                         CASE WHEN ps.nocm = kp.norm then ps.nocm else kp.norm end as nocm,
			             CASE WHEN ps.nocm = kp.norm then ps.namapasien else kp.namapasien end as namapasien,
                         kp.umur,
                         CASE WHEN ps.nocm = kp.norm then al.alamatlengkap else kp.alamat end as alamat,
                         CASE WHEN ps.nocm = kp.norm then ps.notelepon else kp.notlp end as notlp,
                         CASE WHEN ps.nocm = kp.norm then jk.id else jk1.id end as jkid,
                         CASE WHEN ps.nocm = kp.norm then jk.jeniskelamin else jk1.jeniskelamin end as jeniskelamin,
                         ru.id as ruid,ru.namaruangan,kp.keluhan,kp.saran,pkr.id as pekerjaanid,pkr.pekerjaan,kp.notlpkntr,
                         pg.id as pegawaiid,pg.namalengkap,kp.email,pkp.reply,pkpd.hasilklarifikasi,pkpd.kesimpulankronologis,
                         pkpd.solusikeluhan,pkpd.tindaklanjut,pkpd.respon,pkp.norec as norec_pp,pkp.tglpenanganan,pkpd.kategorikomplain')
        )
            ->where('kp.kdprofile', $this->kdProfile)
            ->whereBetween(DB::raw('kp.tglkeluhan::date'), $dateRange)
            ->where('pkp.statusenabled', true)
            ->orderBy('kp.tglkeluhan', 'asc')
            ->get();

        $result = array(
            'data' => $data,
            'message' => 'ea@epic',
        );
        return $this->respond($result);
    }

    public function cetakInsidenInternal(Request $res){

        $data = DB::table('laporaninsideninternal_t as lki')
            ->JOIN('pasien_m as ps', 'ps.nocm', '=', 'lki.nocm')
            ->LEFTJOIN('ruangan_m as ru', 'ru.id', '=', 'lki.ruanganfk')
            ->LEFTJOIN('kelompokpasien_m as kp', 'kp.id', '=', 'lki.penanggungbiayapasienfk')
            ->LEFTJOIN('jeniskelamin_m as jk', 'jk.id', '=', 'lki.jeniskelaminfk')
            ->LEFTJOIN('insidenkeselamatan_m as ik', 'ik.id', '=', 'lki.insidenkeselamatanfk')
            ->LEFTJOIN('jeniskeselamatan_m as jkn', 'jkn.id', '=', 'ik.jeniskesalamatanfk')
            ->LEFTJOIN('lembarkerjainvestigasi_t AS lkt', 'lkt.laporaninsidenfk', '=', 'lki.norec')
            ->select(DB::raw("lki.*,ru.namaruangan,kp.kelompokpasien,ik.namakeselamatan as keselamatan,
                    ik.jeniskesalamatanfk,jkn.jeniskeselamatan,lkt.norec as norec_lk,jk.jeniskelamin,ps.id as nocmfk"))
            ->where('lki.kdprofile', $this->kdProfile)
            ->where('lki.statusenabled',true)
            ->where('lki.norec', $res['norec'])
            ->first();

        $pasien = DB::table('pasien_m as ps')
                    ->join('jeniskelamin_m as jk', 'ps.objectjeniskelaminfk','jk.id')
                    ->select('ps.namapasien','ps.nocm','jk.jeniskelamin','ps.tgllahir')
                    ->where('ps.id',$res['nocmfk'])
                    ->where('ps.kdprofile',$this->kdProfile)
                    ->where('ps.statusenabled',true)
                    ->first();

        $profile = DB::table('profile_m')->where('statusenabled', true)->where('kdprofile', $this->kdProfile)->first();

        if ($res['pdf'] == 'true') {
            $pdf = App::make('dompdf.wrapper');
            $pdf->loadView('report.pmkp.insiden-internal',
                array(
                    'pageWidth' => 780,
                    'res' => $res,
                    'profile' => $profile,
                    'data' => $data,
                    'pasien' => $pasien
                )
            );
            $pdf->setPaper('A4', 'portrait');
            return $pdf->stream();
        } else {
            return view('report.pmkp.insiden-internal');
        }
    }
}
