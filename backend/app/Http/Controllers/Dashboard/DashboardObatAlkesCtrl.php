<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Traits\Valet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;

class DashboardObatAlkesCtrl extends Controller
{
    use Valet;
    public function __construct()
    {
        parent::__construct($is_encrypt = true);
    }
    public function getObat(Request $r)
    {
        $data  = DB::table('stokprodukdetail_t as spd')
            ->JOIN('ruangan_m as ru', 'ru.id', '=', 'spd.objectruanganfk')
            ->JOIN('produk_m as pr', 'pr.id', '=', 'spd.objectprodukfk')
            ->select(
                'ru.namaruangan',
                'pr.namaproduk',
                'spd.qtyproduk',
                'spd.objectprodukfk'
            )
            ->where('spd.kdprofile', $this->kdProfile)
            ->where('spd.statusenabled', true);

        if (isset($r['norec']) && $r['norec'] != '') {
            $data = $data->where('spd.norec', '=',  $r['norec']);
        }
        if (isset($r['norec']) && $r['norec'] != '') {
            $data = $data->where('spd.objectruanganfk', '=',  $r['objectruanganfk']);
        }

        if (isset($r['objectprodukfk']) && $r['objectprodukfk'] != '') {
            $data = $data->where('spd.objectprodukfk', 'ilike', '%' . $r['objectprodukfk'] . '%');
        }

        $data = $data->orderBy('spd.qtyproduk', 'DESC');
        $data = $data->limit(50);
        $data = $data->get();

        $res['data'] = $data;
        return $this->respond($res);
    }

    public function getDaftarReturObat(Request $request)
    {

        $rangeDate = [$request->tglAwal, $request->tglAkhir];
        $data = DB::table('strukretur_t as srt')
            ->LEFTJOIN('strukresep_t as sr', 'sr.norec', '=', 'srt.strukresepfk')
            ->LEFTJOIN('antrianpasiendiperiksa_t as apd', 'apd.norec', '=', 'sr.pasienfk')
            ->LEFTJOIN('pasiendaftar_t as pd', 'pd.norec', '=', 'apd.noregistrasifk')
            ->LEFTJOIN('pasien_m as ps', 'ps.id', '=', 'pd.nocmfk')
            ->LEFTJOIN('pegawai_m as pg', 'pg.id', '=', 'srt.objectpegawaifk')
            ->LEFTJOIN('ruangan_m as ru', 'ru.id', '=', 'srt.objectruanganfk')
            ->LEFTJOIN('strukpelayanan_t as sp', 'sp.norec', '=', 'srt.strukresepfk')
            ->select(
                'srt.tglretur',
                'srt.noretur',
                'pd.noregistrasi',
                'ps.nocm',
                'ps.namapasien',
                'pg.namalengkap',
                'ru.namaruangan',
                'srt.norec',
                'srt.keteranganlainnya',
                'sp.nostruk_intern as nocm_sp',
                'sp.namapasien_klien as namapasien_sp'
            )
            ->where('srt.kdprofile', $this->kdProfile)
            ->whereBetween(DB::raw("CAST(srt.tglretur as DATE)"), $rangeDate);

        if (isset($request['noretur']) && $request['noretur'] != "" && $request['noretur'] != "undefined") {
            $data = $data->where('srt.noretur', 'ilike', '%' . $request['noretur']);
        }
        if (isset($request['idRuangan']) && $request['idRuangan'] != "" && $request['idRuangan'] != "undefined") {
            $data = $data->where('srt.objectruanganfk', $request['idRuangan']);
        }

        $data = $data->where('srt.statusenabled', true);
        $data = $data->orderBy('srt.noretur');
        $data = $data->get();
        $results = [];
        foreach ($data as $item) {
            $details = DB::select(
                DB::raw("
                select spd.tglpelayanan, spd.rke,jkm.jeniskemasan,pr.namaproduk,
                ss.satuanstandar,spd.jumlah,spd.hargasatuan,spd.hargadiscount,
                spd.jasa,((spd.hargasatuan-spd.hargadiscount)*spd.jumlah)+spd.jasa as total from pelayananpasienretur_t as spd
                INNER JOIN produk_m as pr on pr.id=spd.produkfk
                INNER JOIN jeniskemasan_m as jkm on jkm.id=spd.jeniskemasanfk
                INNER JOIN satuanstandar_m as ss on ss.id=spd.satuanviewfk
                where spd.kdprofile = $this->kdProfile and strukreturfk=:norec"),
                array(
                    'norec' => $item->norec,
                )
            );
            $namapasien = $item->namapasien;
            $nocm = $item->nocm;
            $noregistrasi = $item->noregistrasi;
            if (is_null($item->noregistrasi)) {
                $namapasien =  $item->namapasien_sp;
                $nocm = $item->nocm_sp;
                $noregistrasi = '-';
            }

            $results[] = array(
                'tglretur' => $item->tglretur,
                'noretur' => $item->noretur,
                'noregistrasi' => $noregistrasi,
                'nocm' => $nocm,
                'namapasien' => $namapasien,
                'namalengkap' => $item->namalengkap,
                'namaruangan' => $item->namaruangan,
                'norec' => $item->norec,
                'keteranganlainnya' => $item->keteranganlainnya,
                'details' => $details,
            );
        }

        $result = array(
            'daftar' => $results,
            'message' => 'as@epic',
        );

        return $this->respond($result);
    }

    public function cetakReturResep(Request $request)
    {
        $norec = $request['norec'];
        $profile = collect(DB::select("select * from profile_m where id = $this->kdProfile limit 1"))->first();

        $data = DB::table('strukretur_t as srt')
            ->leftJoin('strukresep_t as sr', 'sr.norec', 'srt.strukresepfk')
            ->leftJoin('pelayananpasienretur_t as ppr', 'ppr.strukreturfk', 'srt.norec')
            ->leftJoin('produk_m as pr', 'pr.id', 'ppr.produkfk')
            ->leftJoin('jeniskemasan_m as jkm', 'jkm.id', 'ppr.jeniskemasanfk')
            ->leftJoin('antrianpasiendiperiksa_t as apd', 'apd.norec', 'sr.pasienfk')
            ->leftJoin('pasiendaftar_t as pd', 'pd.norec', 'apd.noregistrasifk')
            ->leftJoin('pasien_m as ps', 'ps.id', 'pd.nocmfk')
            ->leftJoin('pegawai_m as pg', 'pg.id', 'srt.objectpegawaifk')
            ->leftJoin('pegawai_m as pg2', 'pg2.id', 'apd.objectpegawaifk')
            ->leftJoin('kelompokpasien_m as kp', 'kp.id', 'pd.objectkelompokpasienlastfk')
            ->leftJoin('ruangan_m as ru', 'ru.id', 'srt.objectruanganfk')
            ->leftJoin('ruangan_m as ru2', 'ru2.id', 'apd.objectruanganfk')
            ->select(
                'srt.norec',
                'srt.tglretur',
                'srt.noretur',
                'pd.tglregistrasi',
                'pd.noregistrasi',
                'ps.nocm',
                'ps.namapasien',
                'pg.namalengkap',
                'ru.namaruangan',
                'ru2.namaruangan as ruanganpasien',
                'srt.keteranganlainnya',
                'ps.tgllahir',
                'kp.kelompokpasien as penjamin',
                'pg2.namalengkap as dokter',
                'pr.namaproduk',
                'jkm.jeniskemasan',
                'ppr.rke',
                'ppr.jumlah',
                'ppr.hargasatuan'
            )
            ->where('srt.norec', $request['norec'])
            ->first();

        $detail = collect(DB::select("
            select srt.norec,srt.tglretur,srt.noretur,pd.tglregistrasi,pd.noregistrasi,ps.nocm,ps.namapasien,pg.namalengkap,
            ru.namaruangan ,ru2.namaruangan as ruanganpasien ,srt.keteranganlainnya,ps.tgllahir,kp.kelompokpasien as penjamin,
            pg2.namalengkap as dokter,pr.namaproduk,jkm.jeniskemasan,ppr.rke,ppr.jumlah,ppr.hargasatuan,ppr.jumlah * ppr.hargasatuan AS totalharga
            from strukretur_t as srt
            LEFT JOIN strukresep_t as sr on sr.norec=srt.strukresepfk
            LEFT JOIN pelayananpasienretur_t as ppr on ppr.strukreturfk=srt.norec
            LEFT JOIN produk_m as pr on pr.id=ppr.produkfk
            LEFT JOIN jeniskemasan_m as jkm on jkm.id=ppr.jeniskemasanfk
            LEFT JOIN antrianpasiendiperiksa_t as apd on apd.norec=sr.pasienfk
            LEFT JOIN pasiendaftar_t as pd on pd.norec=apd.noregistrasifk
            LEFT JOIN pasien_m as ps on ps.id=pd.nocmfk
            LEFT JOIN pegawai_m as pg on pg.id=srt.objectpegawaifk
            LEFT JOIN pegawai_m as pg2 on pg2.id=apd.objectpegawaifk
            LEFT JOIN kelompokpasien_m as kp on kp.id=pd.objectkelompokpasienlastfk
            LEFT JOIN ruangan_m as ru on ru.id=srt.objectruanganfk
            LEFT JOIN ruangan_m as ru2 on ru.id=apd.objectruanganfk
            WHERE srt.norec='$norec' order by ppr.rke ASC
        "));
        $detail = $detail->groupBy('jeniskemasan');
        $pageWidth = 950;

        $res['pdf']  = false;

        $dataReport = array (
            'datas' => $data,
            'detail' => $detail
        
        );

        $blade = 'report.farmasi.cetak-retur-resep';
        if ($res['pdf']) {
            $pdf = App::make('dompdf.wrapper');
            $pdf->loadView(
                $blade,
                array(
                    'profile' => $profile,
                    'pageWidth' => $pageWidth,
                    'res' => $res,
                )
            );
            return $pdf->stream();
        }

        return view($blade, compact('dataReport', 'profile', 'pageWidth', 'res'));
        //     // dd($dataReport);
        //     return view('report.apotik.cetak-retur-resep',
        //         compact('dataReport', 'pageWidth','profile'));
        // }
    }
}
