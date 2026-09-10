<?php

namespace App\Http\Controllers\Remunerasi;

use App\Http\Controllers\Controller;
use App\Models\Master\GolonganPegawai;
use App\Models\Master\Jabatan;
use App\Models\Master\KelompokPasien;
use App\Models\Master\Pegawai;
use App\Models\Master\Pendidikan;
use App\Models\Master\Ruangan;
use App\Models\Master\UnitKerjaPegawai;
use App\Models\Transaksi\StrukPagu;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Traits\Valet;
use Exception;

class RemunerasiDokterCtrl extends Controller
{
    use Valet;
    public function __construct()
    {
        parent::__construct($is_encrypt = true);
    }

    public function getCombo()
    {
        $res['dokter'] = DB::table('remunerasidokter_t')
            ->select('namadokter')
            ->where('kdprofile', $this->kdProfile)
            ->groupBy('namadokter')
            ->where('statusenabled', true)
            ->get();

        $res['ruangan'] = DB::table('remunerasidokter_t')
            ->select('ruangan')
            ->where('kdprofile', $this->kdProfile)
            ->where('statusenabled', true)
            ->groupBy('ruangan')
            ->get();

        return $this->respond($res);
    }

    public function getData(Request $request)
    {
        $dateBetween = [$request->dari, $request->sampai];

        $data = DB::table('remunerasidokter_t')
            ->selectRaw('*')
            ->where('namadokter', $request['namadokter'])
            ->whereBetween('tanggal', $dateBetween)
            ->where('statusenabled', true)
            ->where('kdprofile', $this->kdProfile);
            if(isset($request['jenis']) && $request['jenis']!=''){
                $data=$data   ->where('jenis',  $request['jenis']);
            }
            if(isset($request['namakegiatan']) && $request['namakegiatan']!=''){
                $data=$data   ->where('kegiatankelompok',  $request['namakegiatan']);
            }
            if(isset($request['kunjinstalasi']) && $request['kunjinstalasi']!=''){
                $data=$data   ->where('kunjinstalasi',  $request['kunjinstalasi']);
            }

            $data=$data   ->orderBy('tanggal');
            $data=$data   ->get();

        return $this->respond($data);
    }

    public function getTotalLayanan(Request $request)
    {
        $data['total'] = DB::table('remunerasidokter_t')
            ->selectRaw("namadokter,SUM(jasa)")
            ->where('namadokter', $request['namadokter'])
            ->where('statusenabled', true)
            ->where('kdprofile', $this->kdProfile)
            ->groupBy('namadokter')
            ->get();

        $data['JKN'] = DB::table('remunerasidokter_t')
            ->selectRaw("namadokter,SUM(jasa)")
            ->where('namadokter', $request['namadokter'])
            ->where('jenis', 'JKN')
            ->where('statusenabled', true)
            ->where('kdprofile', $this->kdProfile)
            ->groupBy('namadokter')
            ->get();

        $data['EXECUTIVE'] = DB::table('remunerasidokter_t')
            ->selectRaw("namadokter,SUM(jasa)")
            ->where('namadokter', $request['namadokter'])
            ->where('jenis', 'EXECUTIVE')
            ->where('statusenabled', true)
            ->where('kdprofile', $this->kdProfile)
            ->groupBy('namadokter')
            ->get();

        $data['REGULER'] = DB::table('remunerasidokter_t')
            ->selectRaw("namadokter,SUM(jasa)")
            ->where('namadokter', $request['namadokter'])
            ->where('jenis', 'REGULER')
            ->where('statusenabled', true)
            ->where('kdprofile', $this->kdProfile)
            ->groupBy('namadokter')
            ->get();

        return $this->respond($data);
    }
    public function getDataTindakan(Request $request)
    {
        $dateBetween = [$request->dari, $request->sampai];
        $data = DB::table('remunerasidokter_t')
            ->select('kegiatankelompok')
            ->where('statusenabled', true)
            ->where('kdprofile', $this->kdProfile)
            ->where('namadokter', $request['namadokter'])
            ->whereBetween(DB::raw("CAST(tanggal AS DATE)"), $dateBetween);
            if(isset($request['jenis']) && $request['jenis']!=''){
                $data=$data   ->where('jenis',  $request['jenis']);
            }
            if(isset($request['namakegiatan']) && $request['namakegiatan']!=''){
                $data=$data   ->where('namakegiatan',  $request['namakegiatan']);
            }
            $data=$data ->groupbY('kegiatankelompok');
            $data=$data ->get();

        return $this->respond($data);
    }

}
