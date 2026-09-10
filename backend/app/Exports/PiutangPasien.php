<?php
namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithCustomValueBinder;
use DB;

class PiutangPasien extends \PhpOffice\PhpSpreadsheet\Cell\StringValueBinder implements FromView, ShouldAutoSize, WithCustomValueBinder
{
    use Exportable;
    protected $request;
    protected $profile;
    protected $rangeDate;

    public function __construct($request, $profile, $rangeDate) {
        $this->request = $request;
        $this->profile = $profile;
        $this->rangeDate = $rangeDate;
    }

    public function view(): View
    {
        $request = $this->request;
        $profile = $this->profile;
        $rangeDate = $this->rangeDate;

        $subSbm = DB::table('strukbuktipenerimaan_t')
        ->select(
            'nostrukfk',
            DB::raw('SUM(totaldibayar)       as totaldibayar'),
            DB::raw('MAX(tglsbm)             as tgldibayar'),
            DB::raw('MAX(norec)              as norec_sbm')
        )
        ->where('statusenabled', true)
        ->groupBy('nostrukfk');

        $subQueryTanggalLunas = DB::table('strukbuktipenerimaancarabayar_t as sbcb')
        ->select('nosbmfk', DB::raw('MIN(sbcb.created_at) as tanggal_lunas'))
        ->groupBy('nosbmfk');

        $dataPiutang = DB::table('pasiendaftar_t as pd')
            ->join('antrianpasiendiperiksa_t as ap', 'pd.norec', '=', 'ap.noregistrasifk')
            ->join('pelayananpasien_t as pp', 'ap.norec', '=', 'pp.noregistrasifk')
            ->leftjoin('strukpelayanan_t as sp', 'pp.strukfk', '=', 'sp.norec')
            ->leftjoin('rekanan_m as rkn', 'rkn.id', '=', 'pd.objectrekananfk')
            ->join('pasien_m as p', 'p.id', '=', 'pd.nocmfk')
            ->join('jeniskelamin_m as jk', 'jk.id', '=', 'p.objectjeniskelaminfk')
            ->join('statuspiutang_m as stp', 'stp.id', '=', 'pd.objectstatuspiutangfk')
            ->leftJoin('ruangan_m as ru', 'ru.id', '=', 'pd.objectruanganlastfk')
            ->leftJoin('departemen_m as dept', 'dept.id', '=', 'ru.objectdepartemenfk')
            ->leftJoin('kelompokpasien_m as kp', 'kp.id', '=', 'pd.objectkelompokpasienlastfk')
            ->leftJoinSub($subSbm, 'sbm', function($join){
                    $join->on('sbm.nostrukfk', '=', 'sp.norec');
            })
            ->leftJoinSub($subQueryTanggalLunas, 'lunas', function($join){
                $join->on('lunas.nosbmfk', '=', 'sbm.norec_sbm');
            })
            ->select(
                'kp.kelompokpasien',
                'sp.tglstruk',
                'pd.noregistrasi',
                'pd.tglregistrasi',
                'p.nocm',
                'p.namapasien',
                DB::raw("sbm.totaldibayar"),
                'jk.jeniskelamin',
                'jk.id as jkid',
                'pd.norec as norec_pd',
                'sp.norec',
                'sp.totalharusdibayar',
                'sp.totaliurbayar',
                'pd.tglpulang',
                'pd.nocmfk',
                'pd.nostruklastfk',
                'pd.nosbmlastfk',
                'sbm.totaldibayar',
                'sbm.tgldibayar',
                'sbm.norec_sbm as norec_sbm',        // sudah ter-aggregate
                'lunas.tanggal_lunas as tanggalLunas'
            )
            ->where('pd.statusenabled', true)
            ->where('sp.statusenabled', true)
            ->whereNotNull('pd.objectstatuspiutangfk')
            ->where('pd.kdprofile', $profile->id);
            // ->get();
            // dd($request->export);
        
        if(!isset($request['allPeriode']) || $request['allPeriode'] != 'true') {
            $dataPiutang = $dataPiutang->whereBetween(DB::raw("pd.tglpulang::date"), $rangeDate);
        }
        if ($request['status']) {
            if ($request['status'] == '1') {
                $dataPiutang = $dataPiutang->whereNotNull('pd.nostruklastfk');
            }
        }

        if (isset($request['klmpasien']) && $request['klmpasien'] != "") {
            $dataPiutang = $dataPiutang->where('kp.id', '=', $request['klmpasien']);
        }

        if (isset($request['namaPasien']) && $request['namaPasien'] != "") {
            $dataPiutang = $dataPiutang->where('p.namapasien', 'ilike', '%' . $request['namaPasien'] . '%');
        }

        if (isset($request['norm']) && $request['norm'] != "") {
            $dataPiutang = $dataPiutang->where('p.nocm', 'ilike', '%' . $request['norm'] . '%');
        }

        if (isset($request['noregis']) && $request['noregis'] != "") {
            $dataPiutang = $dataPiutang->where('pd.noregistrasi', '=', $request['noregis']);
        }
        if (isset($request['search']) && $request['search'] != '') {
            $filter = true;
            $searchTerm = '%' . $request['search'] . '%';
            $dataPiutang = $dataPiutang->where(function ($query) use ($searchTerm) {
                $query->where('p.namapasien', 'ilike', $searchTerm)
                    ->orWhere('pd.noregistrasi', 'ilike', $searchTerm)
                    ->orWhere('p.nocm', 'ilike', $searchTerm);
            });
        }

        $dataPiutang = $dataPiutang->groupBy(
            'kp.kelompokpasien',
            'sp.tglstruk',
            'pd.noregistrasi',
            'pd.tglregistrasi',
            'p.nocm',
            'p.namapasien',
            'jk.jeniskelamin',
            'jk.id',
            'norec_pd',
            'pd.tglpulang',
            'sp.norec',
            'sp.totalharusdibayar',
            'sp.totaliurbayar',
            'pd.nocmfk',
            'pd.nostruklastfk',
            'pd.nosbmlastfk',
            'sbm.totaldibayar',
            'sbm.tgldibayar',
            'sbm.norec_sbm', 
            'lunas.tanggal_lunas'
        );
        if (isset($request['offset']) && $request['offset'] != '') {
            $dataPiutang = $dataPiutang->offset($request['offset']);
        }
        if (isset($request['limit']) && $request['limit'] != '') {
            $dataPiutang = $dataPiutang->limit($request['limit']);
        }
        $dataPiutang = $dataPiutang->orderBy('sp.tglstruk', 'desc');
        $dataPiutang = $dataPiutang->get();

        // dd ($dataPiutang);
        $data = [];
        foreach ($dataPiutang as $key => $item) {
            // $check = DB::table("strukbuktipenerimaancarabayar_t")->where('nosbmfk', $item->norec_sbm)->first();
            // $tanggalLunas = null;
            // if($check != null) {
            //     $tanggalLunas = date('Y-m-d H:i:s', strtotime($check->created_at));
            // }
            if ($item->nostruklastfk != null) {
                $statusVerifikasi = "Verifikasi";
                $isVerified = true;
            } else {
                $statusVerifikasi = "Belum Diverifikasi";
                $isVerified = false;
            }
            if ($item->nosbmlastfk != null) {
                $statusClosing = "Sudah Bayar";
            } else {
                $statusClosing = "Belum Bayar";
            }

            $sisaPiutang = $item->totalharusdibayar - $item->totaldibayar + ($item->totaliurbayar != 0 ? $item->totaliurbayar : 0);
            if($statusClosing == "Sudah Bayar" && $sisaPiutang > 0){
                $statusPiutang = "Belum Lunas";
            }else if($statusClosing == "Sudah Bayar" && $sisaPiutang == 0){
                $statusPiutang = "Lunas";
            }else{
                $statusPiutang = "Belum Bayar";
            }


            $data[] = array(
                'tglTransaksi' => $item->tglstruk,
                'noRegistrasi' => $item->noregistrasi,
                'nocm' => $item->nocm,
                'namaPasien' => $item->namapasien,
                'jeniskelamin' => $item->jeniskelamin,
                'kdJenisKelamin' => $item->jkid == 1 ? 'L' : 'P',
                'kelasRawat' => '-',
                'jenisPasisen' => $item->kelompokpasien,
                'kelasPenjamin' => "-",
                'totalDibayar' => $item->totaldibayar,
                'totalHarusDibayar' => $item->totalharusdibayar,
                'sisa' => $sisaPiutang,
                'statusVerifikasi' => $statusVerifikasi,
                'statusClosing' => $statusClosing,
                'colorVerif' => $isVerified ? 'green' : 'orange',
                'colorPiutang' => $statusPiutang == "Belum Lunas" ? 'orange' : ($statusPiutang == "Lunas" ? 'green' : 'danger'),
                'norec_pd' => $item->norec_pd,
                'norec' => $item->norec,
                'nocmfk' => $item->nocmfk,
                'tglpulang' => $item->tglpulang,
                'isVerified' => $isVerified,
                'tglregistrasi' => $item->tglregistrasi,
                'statuspiutang' => $statusPiutang,
                'tgldibayar' => $item->tgldibayar,
                'tanggalLunas' => $item->tanggalLunas
            );
        }
        return view('report.kasir.piutang-pasien-export', compact('data','profile', 'rangeDate'));
    }
}