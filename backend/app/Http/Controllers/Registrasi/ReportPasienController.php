<?php

namespace App\Http\Controllers\Registrasi;

use App\Http\Controllers\Controller;
use App\Models\Master\Pasien;
use App\Models\Master\Profile;
use App\Models\Standar\LoginUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;

class ReportPasienController extends Controller
{
    public function cetakGelangPasien(Request $r)
    {
        $kdProfile = (int)$this->getDataKdProfile($r);
        $registrasi = collect(DB::select("
        SELECT
            pm.nocm,
            pm.namapasien,
            '*' || pm.nocm || '*' AS barcode,
            jk.jeniskelamin,
            CASE WHEN pm.noidentitas IS NULL THEN '-' ELSE pm.noidentitas END AS nik,
            to_char( pm.tgllahir, 'DD-MM-YYYY' ) AS umur,
            pr.namalengkap as namaprofile,pm.tgllahir
        
        FROM
            pasiendaftar_t pd
            INNER JOIN pasien_m pm ON pd.nocmfk = pm.id
            LEFT JOIN jeniskelamin_m jk ON jk.ID = pm.objectjeniskelaminfk and  jk.kdprofile = pm.kdprofile
            LEFT JOIN profile_m pr ON pr.ID = pd.kdprofile and  pd.kdprofile = pr.kdprofile
           
        WHERE
            pd.noregistrasi = '$r[noreg]'
            and pd.kdprofile =$kdProfile
            and pd.statusenabled=true
        "))->first();
        $pageWidth = 365;
        $height = 600;

        $blade = 'report.pendaftaran.gelangpasien';
        $res['pdf'] = 'true';
        $res['storage']  = true;
        $pdf = App::make('dompdf.wrapper');
        $pdf->loadView(
            $blade,
            array(
                'res' => $res,
                'pageWidth' => $pageWidth,
                'registrasi' => $registrasi
            )
        );
        // return $pdf->stream();
        return view(
            'report.pendaftaran.gelangpasien',
            compact('registrasi', 'pageWidth', 'r')
        );
    }
    public function cetakLembarRawatInap(Request $request)
    {
        $data =  DB::table('pasiendaftar_t as pd')
            ->join('pasien_m as ps', 'ps.id', '=', 'pd.nocmfk')
            ->join('ruangan_m as ru', 'ru.id', '=', 'pd.objectruanganlastfk')
            ->join('kelas_m as kl', 'kl.id', '=', 'pd.objectkelasfk')
            ->join('departemen_m as dp', 'dp.id', '=', 'ru.objectdepartemenfk')
            ->join('kelompokpasien_m as kp', 'kp.id', '=', 'pd.objectkelompokpasienlastfk')
            ->leftjoin('jeniskelamin_m as jk', 'jk.id', '=', 'ps.objectjeniskelaminfk')
            ->leftjoin('pegawai_m as pg', 'pg.id', '=', 'pd.objectpegawaifk')
            ->leftjoin('rekanan_m as rk', 'rk.id', '=', 'pd.objectrekananfk')
            ->leftjoin('pemakaianasuransi_t as pa', 'pa.noregistrasifk', '=', 'pd.norec')
            ->select(
                'pd.noregistrasi as  noreg',
                'ps.namapasien as nama',
                'rk.desakelurahan as kelurahan',
                'rk.kecamatan',
                'rk.kotakabupaten',
                'rk.alamatlengkap',
                'kp.kelompokpasien',
                'ps.nocm',
                'pd.*'

            )
            ->where('pd.statusenabled', true)
            ->where('pd.kdprofile', $this->kdProfile)
            ->where('pd.norec', $request['noregistrasi'])
            ->first();

        $diagnosaX = DB::table('detaildiagnosapasien_t as ddp')
            ->join('antrianpasiendiperiksa_t as apd', 'apd.norec', '=', 'ddp.noregistrasifk')
            ->join('diagnosa_m as dg', 'dg.id', '=', 'ddp.objectdiagnosafk')
            ->join('jenisdiagnosa_m as jd', 'jd.id', '=', 'ddp.objectjenisdiagnosafk')
            ->select(
                'dg.kddiagnosa',
                'dg.namadiagnosa',
                'ddp.tglinputdiagnosa',
                'jd.jenisdiagnosa',
                'ddp.keterangan',
                DB::raw("'ICD X' as jenis")
            )
            ->where('ddp.kdprofile', $this->kdProfile)
            ->where('apd.noregistrasifk', $data->norec)
            ->orderByDesc('ddp.tglinputdiagnosa')
            ->get();
        $diagnosaIX = DB::table('detaildiagnosatindakanpasien_t as ddt')
            ->join('antrianpasiendiperiksa_t as apd', 'apd.norec', '=', 'ddt.noregistrasifk')
            ->join('diagnosatindakan_m as dt', 'dt.id', '=', 'ddt.objectdiagnosatindakanfk')
            ->select(
                'dt.kddiagnosatindakan as kddiagnosa',
                'dt.namadiagnosatindakan as namadiagnosa',
                'ddt.keterangantindakan as keterangan',
                'ddt.tglinputdiagnosa',
                DB::raw("'ICD IX' as jenis, null as jenisdiagnosa")
            )
            ->where('ddt.kdprofile', $this->kdProfile)
            ->where('apd.noregistrasifk', $data->norec)
            ->orderByDesc('ddt.tglinputdiagnosa')
            ->get();

        $diagnosa = array_merge($diagnosaX->toArray(), $diagnosaIX->toArray());


        $pageWidth = 890;
        $blade = 'report.pendaftaran.cetak-lemabar-rawat-inap';
        $res['profile'] = Profile::where('id', $this->getDataKdProfile($request))->first();
        $res['pdf'] = 'true';
        $res['storage']  = true;
        $pdf = App::make('dompdf.wrapper');
        $pdf->loadView(
            $blade,
            array(
                'data' => $data,
                'res' => $res,
                'pageWidth' => $pageWidth,
                'penaggungJawab' => $request->penaggungJawab,
                'alamat' => $request->alamat,
                'diagnosa' => $diagnosa
            )
        );
        return $pdf->stream();
    }
    public function getDataKdProfile(Request $request)
    {
        $dataLogin = $request->all();
        $idUser = $dataLogin['userData']['id'];
        $data = LoginUser::where('id', $idUser)->first();
        if (!empty($data)) {
            $idKdProfile = (int)$data->kdprofile;
            $Query = DB::table('profile_m')
                ->where('id', '=', $idKdProfile)
                ->first();
            $Profile = $Query;
            return (int)$Profile->id;
        } else {
            $data = Pasien::where('id', $idUser)->first();
            if (!empty($data)) {
                $idKdProfile = (int)$data->kdprofile;
                $Query = DB::table('profile_m')
                    ->where('id', '=', $idKdProfile)
                    ->first();
                $Profile = $Query;
                return (int)$Profile->id;
            } else {
                return null;
            }
        }
    }
}
