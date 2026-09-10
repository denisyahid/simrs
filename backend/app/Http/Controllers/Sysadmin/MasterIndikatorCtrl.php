<?php

namespace App\Http\Controllers\Sysadmin;

use Exception;
use Faker\Factory;
use App\Traits\Valet;
use Ramsey\Uuid\Uuid;
use App\Models\Master\StatusKeluar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Master\IndikatorRensar;
use App\Models\Master\JenisIndikator;
use App\Models\Master\JenisKondisiPasien;
use Illuminate\Support\Facades\Validator;
use Mockery\Exception\InvalidOrderException;
use Mockery\Undefined;

class MasterIndikatorCtrl extends Controller
{

    use Valet;

    public function __construct()
    {
        parent::__construct($is_encrypt = true);
    }

    public function getIndikatorRensar_M(Request $request)
    {

        $indikator = DB::table('indikatorrensar_m as ir')
        ->leftjoin('jenisindikator_m as ji', 'ji.id', '=', 'ir.jenisindikatorfk')
        ->leftjoin('dimensimutu_m as dm', 'dm.id', '=', 'ir.demensimutufk')
        ->leftjoin('frekuensidata_m as fd', 'fd.id', '=', 'ir.frekuensifk')
        ->leftjoin('waktulaporan_m as wl', 'wl.id', '=', 'ir.waktulaporanfk')
        ->leftjoin('periodepelaporan_m as pl', 'pl.id', '=', 'ir.periodefk')
        ->leftjoin('metologi_m as mtl', 'mtl.id', '=', 'ir.metologifk')
        ->leftjoin('metologianalisisdata_m as man', 'ji.id', '=', 'ir.analisisdatafk')
        ->leftjoin('cakupandata_m as cd', 'cd.id', '=', 'ir.cakupandatafk')
        ->leftjoin('publikasidata_m as pd', 'pd.id', '=', 'ir.publikasidatafk')
        ->leftjoin('kategoryindikator_m as ki', 'ki.id', '=', 'ir.kategoryindikatorfk')
        ->leftjoin('departemen_m as dept', 'dept.id', '=', 'ir.objectdepartemenfk')
        ->select(
            'ir.*',
            'ji.jenisindikator',
            'dm.demensimutu',
            'fd.frekuensi',
            'wl.waktulaporan',
            'pl.periodepelaporan',
            'mtl.metologi',
            'man.analisisdata',
            'cd.cakupandata',
            'pd.publikasidata',
            'ki.kategoryindikator',
            'dept.namadepartemen'
        )
            ->where('ir.statusenabled', true)
            ->where('ir.kdprofile', $this->kdProfile)
            ->orderBy('ir.urutan');
        if (isset($request['pic']) && $request['pic'] != '' && $request['pic'] != "undefined") {
            $indikator = $indikator->where('ir.pic','ILIKE', '%' . $request['pic'] . '%');
        }
        if (isset($request['indikator']) && $request['indikator'] != '' && $request['indikator'] != "undefined") {
            $indikator = $indikator->where('ir.indikator', 'ILIKE', '%' . $request['indikator'] . '%');
        }
        if (isset($request['jenisindikator']) && $request['jenisindikator'] != '') {
            $indikator = $indikator->where('ir.jenisindikatorfk', $request['jenisindikator']);
        }
        $indikator = $indikator->get();

        return $this->respond($indikator);
    }

    public function saveIndikatorRensar_M(Request $request)
    {

        DB::beginTransaction();

        try {
            if ($request['id'] == '') {
                $new = new IndikatorRensar();
                $id = IndikatorRensar::max('id');
                $new->id = $id + 1;
                $new->kdprofile = $this->kdProfile;
                $new->norec = null;
                $new->statusenabled = true;
            } else {
                $new = IndikatorRensar::where('id', $request['id'])->first();
            }
            $new->definisioperasional = $request['definisioperasional'];
            $new->formula = $request['formula'];
            $new->indikator = $request['indikator'];
            $new->pic = $request['pic'];
            $new->jenisindikatorfk = $request['jenisindikatorfk'];
            $new->numerator = $request['numerator'];
            $new->denominator = $request['denominator'];
            $new->dasarpemikiran = $request['dasarpemikiran'];
            $new->demensimutufk = $request['dimensimutu'];
            $new->tujuan = $request['tujuan'];
            $new->targetpencapaian = $request['targetpencapaian'];
            $new->inklusi = $request['inklusi'];
            $new->eksklusi = $request['eksklusi'];
            $new->sumberdata = $request['sumberdata'];
            $new->frekuensifk = $request['pengumpulandata'];
            $new->waktulaporanfk = $request['jangkalaporan'];
            $new->periodefk = $request['periodeanalis'];
            $new->metologifk = $request['metodologipengumpulandata'];
            $new->cakupandatafk = $request['cakupandata'];
            $new->sampel = $request['sampel'];
            $new->analisisdatafk = $request['metodologianalisisdata'];
            $new->instrumenpengambilandata = $request['instrumenpengambilandata'];
            $new->publikasidatafk = $request['publikasidata'];
            $new->penanggungjawab = $request['penanggungjawab'];
            $new->objectdepartemenfk = $request['objectdepartemenfk'];
            $new->kategoryindikatorfk = $request['kategoryindikatorfk'];
            $new->save();
            DB::commit();
            $result = array(
                'status' => 201,
                'result' => $new,
                'message' => 'Berhasil Simpan Data',
                'as' => 'inhuman',
            );
        } catch (Exception $e) {
            DB::rollBack();
            $result = array(
                'status' => 400,
                'data' => $e->getMessage(),
                'message' => 'Gagal Simpan Data',
                'as' => 'inhuman',
            );
        }
        return $this->respond($result,$result['status'],$result['message']);
    }

    public function getDataCombo(Request $request)
    {
        $kdProfile = $this->kdProfile;

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
        $JenisKeselamatan = DB::table('jeniskeselamatan_m')
            ->select('id', 'jeniskeselamatan')
            ->where('kdprofile', $kdProfile)
            ->where('statusenabled', true)
            ->get();
        $JenisKeselamatan = DB::table('jeniskeselamatan_m')
            ->select('id', 'jeniskeselamatan')
            ->where('kdprofile', $kdProfile)
            ->where('statusenabled', true)
            ->get();
        $jenisIndikator = JenisIndikator::mine()->get();    
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

        foreach ($JenisKeselamatan as $item) {
            $detail = [];
            foreach ($Keselamatan as $item2) {
                if ($item->id == $item2->jeniskesalamatanfk) {
                    $detail[] = array(
                        'id' => $item2->id,
                        'keselamatan' => $item2->namakeselamatan,
                    );
                }
            }

            $dataJenisKeselamatan[] = array(
                'id' => $item->id,
                'jeniskesalamatan' => $item->jeniskeselamatan,
                'keselamatan' => $detail,
            );
        }

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

        $kdUserPmkp = $this->settingDataFixed('KdKelompokUserPmkp', $kdProfile);
        $dataKategoryRisiko = DB::table('kategoryrisiko_m')
            ->selectRaw("id,kategoryrisiko")
            ->where('kdprofile', $kdProfile)
            ->where('statusenabled', true)
            ->orderBy('id')
            ->get();

        $result = array(
            'ruanganrajal' => $ruanganrajal,
            'ruanganranap' => $ruanganranap,
            'jeniskeselamatan' => $dataJenisKeselamatan,
            'jenisindikator' => $jenisIndikator,
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
            'kategoryIndikator' => $dataKategoryIndikator,
            'regrading' => $dataRegrading,
            'kdUser' => $kdUserPmkp,
            'kategoryrisiko' => $dataKategoryRisiko,
            'message' => 'ea@epic',
        );

        return $this->respond($result);
    }

    public function delete(Request $request){

        IndikatorRensar::where('id',$request['id'])->update(['statusenabled'=>false]);

        return $this->respond('',200,'Berhasil Hapus Data');
    }
}
