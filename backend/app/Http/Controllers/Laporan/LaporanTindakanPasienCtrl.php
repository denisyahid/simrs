<?php

namespace App\Http\Controllers\laporan;

use App\Http\Controllers\Controller;
use App\Models\Master\Ruangan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;

class LaporanTindakanPasienCtrl extends Controller
{
    public function __construct()
    {
        parent::__construct($is_encrypt = true);
    }

    public function getPelayananTindakan (Request $request) {

        $kdProfile = $this->kdProfile;
        $Awal = date('Y-m-d 00:00');
        $Akhir = date('Y-m-d 23:59');
        $tglAwal = $request->tglAwal . " 00:00:00";
        $tglAkhir = $request->tglAkhir . " 23:59:59";

        $nocm = "";
        if(isset($request->norm) && $request->norm != '' && $request->norm != null) {
            $nocm = " and ps.nocm = '". $request->norm ."'";
        }
        $nama = "";
        if(isset($request->nama) && $request->nama != '' && $request->nama != null) {
            $nama = " and ps.namapasien ilike '%". $request->nama ."%'";
        }
        $ruangId = "";
        if(isset($request->ruangId) && $request->ruangId != '' && $request->ruangId != null) {
            $ruangId = " and ru.id = '". $request->ruangId ."'";
        }
        $range ="";
        if($tglAwal != '' && $tglAkhir != '' && $tglAwal != null && $tglAkhir != null) {
            $range =" and pp.tglpelayanan between '".$tglAwal."' and '". $tglAkhir ."'";
        }
        $data = DB::select(DB::raw("select pd.noregistrasi
        ,ps.nocm
        ,ps.namapasien
        ,ru.namaruangan
        ,pp.tglpelayanan
        ,pr.namaproduk
        ,STRING_AGG(jpp.jenispetugaspe , ' & ') as jenispetugas
        ,STRING_AGG(pg.namalengkap , ' & ') as namapetugas
        ,( (pp.hargasatuan  - COALESCE (pp.hargadiscount, 0))   * pp.jumlah) + ( COALESCE (pp.jasa, 0)) as total
          from pelayananpasien_t pp
        left join pelayananpasienpetugas_t ppp on pp.norec=ppp.pelayananpasien
        left join pegawai_m pg on ppp.objectpegawaifk=pg.id
        left join jenispetugaspelaksana_m jpp on ppp.objectjenispetugaspefk=jpp.id
        inner join antrianpasiendiperiksa_t apd on pp.noregistrasifk=apd.norec
        inner join pasiendaftar_t pd on apd.noregistrasifk=pd.norec
        inner join pasien_m ps on pd.nocmfk=ps.id
        inner join produk_m pr on pp.produkfk=pr.id
        inner join ruangan_m ru on apd.objectruanganfk=ru.id
        and pp.kdprofile= $kdProfile
        and pp.statusenabled=TRUE
        and pd.statusenabled=true
        and pp.strukresepfk is null
        $ruangId
        $nama
        $range
        GROUP BY  pd.noregistrasi
        ,ps.nocm
        ,ps.namapasien
        ,ru.namaruangan
        ,pp.tglpelayanan
        ,pr.namaproduk
        ,pp.hargasatuan
        ,pp.jumlah
        ,pp.hargadiscount
        ,pp.jasa
        order by pp.tglpelayanan "));

        return $this->respond($data);
    }

    public function getBatalAmprah (Request $request) {

        $kdProfile = $this->kdProfile;
        $tglAwal = $request->tglAwal . " 00:00:00";
        $tglAkhir = $request->tglAkhir . " 23:59:59";

        $data = DB::select(DB::raw("select pd.nocmfk, pd.norec as norec_pd, apd.norec as norec_apd, ps.nocm, ps.namapasien, pd.noregistrasi, 
        so.noorder, so.tglorder, so.tglbatal
        from pasien_m as ps
        inner join pasiendaftar_t as pd on pd.nocmfk = ps.id
        inner join antrianpasiendiperiksa_t as apd on apd.noregistrasifk = pd.norec
        and apd.objectruanganfk = 302
        inner join strukorder_t as so on so.noregistrasifk = pd.norec
        where so.isbatalamprah = true
        and so.tglbatal between '$tglAwal' and '$tglAkhir'
        "));

        $dataEMR = DB::connection('mongodb')
        ->table('FormulirPembatalanPenggunaanKomponenDarah')
        ->where('profile.kdprofile', $this->kdProfile)
        ->where('statusenabled', true)
        ->where('created_at', '>=', $tglAwal)
        ->where('created_at', '<=', $tglAkhir)
        ->get();

        // nama user yang menerima, nama user yang melakukan pembatalan, tanggal dilakukan pembatalan, alasan dilakukan pembatalan

        $data10 = [];
        for($x = 0; $x < count($data); $x++){
            $item = $data[$x];
            $penerima = '';
            $pembatal = '';
            $tglpembatalan = '';
            $alasan = '';
            for($y = 0; $y < count($dataEMR); $y++){
                if($data[$x]->noregistrasi == $dataEMR[$y]['registrasi']['noregistrasi']){
                    if(isset($dataEMR[$y]['petugasYangMembatalkan'])){
                        $pembatal = $dataEMR[$y]['petugasYangMembatalkan']['label'];
                    }
                    if(isset($dataEMR[$y]['penerimaPembatalan'])){
                        $penerima = $dataEMR[$y]['penerimaPembatalan']['label'];
                    }
                    $tglpembatalan = $dataEMR[$y]['tglPembatalan'];
                    $alasan = $dataEMR[$y]['alasanPembatalan'];

                    
                }
            }

            $data10[] = array(
                'nocmfk' => $item->nocmfk,
                'norec_pd' => $item->norec_pd,
                'norec_apd' => $item->norec_apd,
                'nocm' => $item->nocm,
                'namapasien' => $item->namapasien,
                'noregistrasi' => $item->noregistrasi,
                'noorder' => $item->noorder,
                'tglorder' => $item->tglorder,
                'tglbatal' => $item->tglbatal,
                'penerima' => $penerima,
                'pembatal' => $pembatal,
                'tglpembatalan' => $tglpembatalan,
                'alasan' => $alasan,
            );

            
        }

        return $this->respond($data10);
    }

    public function getTimeRespon(Request $request)
    {
        $rangeDate = [$request->tglAwal, $request->tglAkhir];

        $datas = DB::table('strukorder_t as so')
            ->join('orderpelayanan_t as or', 'so.norec','or.noorderfk')
            ->join('pegawai_m as pg', 'pg.id','so.objectpegawaiorderfk')
            ->join('produk_m as prd', 'prd.id', '=', 'or.objectprodukfk')
            ->join('ruangan_m as ru','ru.id', '=', 'or.objectruanganfk')
            ->join('pasien_m as ps','ps.id','so.nocmfk' )
            ->leftjoin('pelayananpasien_t as pp','pp.strukorderfk', 'so.norec')
            ->leftjoin('pelayananpasienpetugas_t as ppp', 'ppp.pelayananpasien', 'pp.norec')
            ->join('pegawai_m as pg2', 'pg2.id','ppp.objectpegawaifk')
            ->leftjoin('hasilradiologi_t as hr', 'pp.norec', 'hr.pelayananpasienfk')
            ->select(
                'ps.namapasien',
                'prd.namaproduk',
                'ru.namaruangan',
                'so.tglorder',
                'so.tglverif',
                'pg.namalengkap as pengorder',
                'pg2.namalengkap as dokter',
                // 'so.tglexpertise',
                'hr.tanggal',
                DB::raw("(so.tglverif - so.tglorder) as selisih, (hr.tanggal - so.tglverif) as selisih2, (hr.tanggal - so.tglorder) as total")
            )
            ->where('so.kdprofile', $this->kdProfile)
            ->whereBetween(DB::raw("CAST(so.tglorder AS DATE)"), $rangeDate)
            ->where('or.objectruangantujuanfk', $this->settingFix('idRuanganRadiologi'))
            ->where('so.statusenabled', true)
            ->where('or.statusenabled', true);

            if (isset($request['nama']) && $request['nama'] != "" && $request['nama'] != "undefined") {
                $datas = $datas->Where('ps.namapasien', 'ilike', '%' . $request['nama'] . '%');
            }

            if (isset($request['ruanganfk']) && $request['ruanganfk'] != "" && $request['ruanganfk'] != "undefined") {
                $datas = $datas->where('ru.id', '=', $request['ruanganfk']);
            }

            if (isset($request['produk']) && $request['produk'] != "" && $request['produk'] != "undefined") {
                $datas = $datas->where('prd.id', $request['produk']);
            }



        $datas = $datas->get();

        return $this->respond($datas);
    }

    public function pilihanSearch(Request $request)
    {
        $idProfile = (int)$this->kdProfile;
        $res['ruangan'] = Ruangan::select('id', 'namaruangan')->where('kdprofile', $idProfile)->where('statusenabled', true)->get();

        $data  = DB::table('mapruangantoproduk_m as mp')
        ->join('produk_m as pr', 'mp.objectprodukfk', '=', 'pr.id')
        ->join('ruangan_m as ru', 'mp.objectruanganfk', '=', 'ru.id')
        ->select(
            'mp.objectprodukfk',
            'pr.id',
            'mp.objectruanganfk',
            'pr.namaproduk',
            'ru.namaruangan',
        )
            ->where('mp.statusenabled', true)
            ->where('mp.kdprofile', $this->kdProfile)
            ->where('mp.objectruanganfk', $this->settingFix('idRuanganRadiologi'));

        if (isset($r['id']) && $r['id'] != '') {
            $data = $data->where('mp.id', '=',  $r['id']);
        }
        if (isset($r['statusenabled']) && $r['statusenabled'] != '') {
            $data = $data->where('mp.statusenabled', '=',  $r['statusenabled']);
        }

        if (isset($r['namaproduk']) && $r['namaproduk'] != '') {
            $data = $data->where('pr.namaproduk', 'ilike', '%'.$r['namaproduk'].'%');
        }

        $data = $data->get();

        $res['data'] = $data;


        return $this->respond($res);
    }

}
