<?php

namespace App\Http\Controllers\Pelayanan;

use App\Http\Controllers\Controller;
use App\Models\Master\Diagnosa;
use App\Models\Master\DiagnosaTindakan;
use App\Models\Master\JenisDiagnosa;
use App\Models\Master\JenisPetugasPelaksana;
use App\Models\Master\Pegawai;
use App\Models\Master\Ruangan;
use App\Models\Transaksi\AntrianPasienDiperiksa;
use App\Models\Transaksi\DetailDiagnosaPasien;
use App\Models\Transaksi\DetailDiagnosaTindakanPasien;
use App\Models\Transaksi\DiagnosaPasien;
use App\Models\Transaksi\DiagnosaTindakanPasien;
use App\Models\Transaksi\OrderPelayanan;
use App\Models\Transaksi\PasienDaftar;
use App\Models\Transaksi\PelayananPasien;
use App\Models\Transaksi\PelayananPasienDetail;
use App\Models\Transaksi\PelayananPasienPetugas;
use App\Models\Transaksi\StrukOrder;
use App\Traits\Valet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PHPUnit\Framework\RiskyTestError;

class DokterCareIntegrasiCtrl extends Controller
{
    use Valet;
    public function __construct()
    {
        parent::__construct($is_encrypt = true);
    }
   
    public function profileDokter(Request $r){
        $kdProfile =  $this->kdProfile;
        $nik = $r['nik'];
        
        $dataProfile =[];
        $dataProfile = collect(DB::select("
        
            select pg.id,pg.noidentitas,pg.nip_pns,pg.nosip,pg.namalengkap,pg.tempatlahir,pg.tgllahir,pg.alamat,
            pg.tglberakhirsip ,pg.nostr,pg.tglberakhirstr,
            pg.tglmasuk,pg.objectstatuspegawaifk,sp.statuspegawai,pg.objectjenispegawaifk,jp.jenispegawai,
            pg.objectnegarafk,ngr.namanegara,pg.email,pg.notlp
            from pegawai_m pg 
            INNER JOIN statuspegawai_m sp on sp.id=pg.objectstatuspegawaifk
            INNER JOIN jenispegawai_m jp on jp.id=pg.objectjenispegawaifk
            INNER JOIN negara_m ngr on ngr.id=pg.objectnegarafk --limit 100
            where pg.kdprofile=$kdProfile and pg.noidentitas ='$nik'

        "));
        
        $result['profile'] = $dataProfile;
        return $this->respond($result);
    }
    public function jadwalDokter(Request $r){
        $kdProfile =  $this->kdProfile;
        $nik = $r['nik'];
        
        $dataJadwal = collect(DB::select("
        
            select jd.hari,jd.jammulai,jd.jamakhir 
            from jadwaldokter_m jd
            inner join pegawai_m pg on pg.id=jd.objectpegawaifk 
            where jd.kdprofile=$kdProfile  
            and pg.noidentitas = '$nik'
            and jd.statusenabled='t'

        "));
    
        $result['jadwaldokter'] = $dataJadwal;
        return $this->respond($result);
    }

    public function rekapHarianReservasi(Request $r){
        $kdProfile =  $this->kdProfile;
        $t_awal = $r['tglawal'];
        $t_akhir = $r['tglakhir'];
        $nik = $r['nik'];
     
        $dataPasienReservasi =0;
        $dataPasienReservasi = collect(DB::select("
        
            select count(apr.norec) as jml ,'$t_awal' as tglawal , '$t_akhir' as tglakhir,pg.noidentitas as nikdokter
            from antrianpasienregistrasi_t apr
            INNER JOIN pegawai_m pg on pg.id=apr.objectpegawaifk
            where apr.tanggalreservasi  between '$t_awal' and '$t_akhir'
            and pg.noidentitas ='$nik'
            group by pg.noidentitas;

        "));
        $dataDetailPasienReservasi =[];
        $dataDetailPasienReservasi = collect(DB::select("
        
            select noreservasi,ps.nocm,ps.namapasien,apr.objectruanganfk as idruangan, ru.namaruangan,
            apr.tanggalreservasi,apr.objectpegawaifk as iddokter,pg.namalengkap as dokter,pg.noidentitas as nikdokter,
            ps.tgllahir,jk.jeniskelamin
            from antrianpasienregistrasi_t apr
            INNER JOIN pasien_m ps on ps.id=apr.nocmfk
            INNER JOIN jeniskelamin_m jk on jk.id=ps.objectjeniskelaminfk
            INNER JOIN pegawai_m pg on pg.id=apr.objectpegawaifk
            INNER JOIN ruangan_m ru on ru.id=apr.objectruanganfk
            where tanggalreservasi  between '$t_awal' and '$t_akhir'
            and pg.noidentitas ='$nik';

        "));
        

        $result['reservasi']['qtyreservasi'] = $dataPasienReservasi;
        $result['reservasi']['detailreservasi'] = $dataDetailPasienReservasi;
        return $this->respond($result);
    }
    public function rekapHarianPasienOperasi(Request $r){
        $kdProfile =  $this->kdProfile;
        $t_awal = $r['tglawal'];
        $t_akhir = $r['tglakhir'];
        $nik = $r['nik'];
     
        
        $dataPasienOperasi=0;
        $dataPasienOperasi = collect(DB::select("
        
            select count(so.norec) as jml ,'$t_awal' as tglawal , '$t_akhir' as tglakhir,pg.noidentitas as nikdokter
            from strukorder_t so
            INNER JOIN pegawai_m pg on pg.id=so.objectpegawaiorderfk
            where so.keteranganorder='Order Jadwal Operasi'
            and so.tglpelayananawal    between '$t_awal' and '$t_akhir'
            and pg.noidentitas ='$nik'
            group by pg.noidentitas;

        "));
        $dataDetailPasienOperasi = [];
        $dataDetailPasienOperasi = collect(DB::select("
        
            select so.noorder,so.tglorder,so.tglpelayananawal as jadwaloperasi,case when so.statusorder=0 then 'Buat Jadwal Operasi' else 'Verifikasi Jadwal' end as status,
            ps.nocm,ps.namapasien,so.noregistrasi,so.objectruanganfk as idruanganasal, ru.namaruangan as ruanganasal,
            so.objectruangantujuanfk as idruangantujuan, ru2.namaruangan as namaruangantujuan, jo.jenisoperasi,pg.namalengkap,pg.noidentitas as nikdokter
            from strukorder_t so
            INNER JOIN pasien_m ps on ps.id=so.nocmfk
            INNER JOIN ruangan_m ru on ru.id=so.objectruanganfk
            INNER JOIN ruangan_m ru2 on ru2.id=so.objectruangantujuanfk
            INNER JOIN jenisoperasi_m jo on jo.id=so.jenisoperasifk
            INNER JOIN pegawai_m pg on pg.id=so.objectpegawaiorderfk
            where so.keteranganorder='Order Jadwal Operasi'
            and so.tglpelayananawal  between '$t_awal' and '$t_akhir'
            and pg.noidentitas ='$nik';

        "));
        
        $result['jadwaloperasi']['qtyjadwaloperasi'] = $dataPasienOperasi;
        $result['jadwaloperasi']['detailjadwaloperasi'] = $dataDetailPasienOperasi;
        return $this->respond($result);
    }
    public function rekapHarianRemun(Request $r){
        $kdProfile =  $this->kdProfile;
        $t_awal = $r['tglawal'];
        $t_akhir = $r['tglakhir'];
        $nik = $r['nik'];
     
        
        $dataTotalRemun =0;
        $dataTotalRemun = collect(DB::select("
        
            select pg.namalengkap,sum(dpp.jenispaginilaitotal) as total
            from detailpegawaipagu_t dpp
            INNER JOIN pegawai_m pg on pg.id=dpp.pegawaiid
            INNER JOIN strukdetailpagu_t sdp on sdp.norec=dpp.norec_sdp
            INNER JOIN pelayananpasien_t pp on pp.norec=sdp.pelayananpasienfk
            INNER JOIN produk_m pr on pr.id=sdp.produkfk
            INNER JOIN detailjenispagu_t djp on djp.id=dpp.djpid
            INNER JOIN jenispagu_t jp on jp.id=dpp.jpid
            where  pg.noidentitas ='$nik' and dpp.djpid=141
            and pp.tglpelayanan between '$t_awal' and '$t_akhir'
            group by pg.namalengkap;

        "));
        $dataDetailTotalRemun =[];
        $dataDetailTotalRemun = collect(DB::select("
        
        select pg.namalengkap,pp.tglpelayanan,pp.harganetto,pr.namaproduk,dpp.jenispaginilaitotal as remun,djp.detailjenispagu,jp.jenispagu,dpp.djpid
        ,pp.jumlah as qty,case when pp.iscito ='t' then 'CITO' else '' end as cito

            from detailpegawaipagu_t dpp
            INNER JOIN pegawai_m pg on pg.id=dpp.pegawaiid
            INNER JOIN strukdetailpagu_t sdp on sdp.norec=dpp.norec_sdp
            INNER JOIN pelayananpasien_t pp on pp.norec=sdp.pelayananpasienfk
            INNER JOIN produk_m pr on pr.id=sdp.produkfk
            INNER JOIN detailjenispagu_t djp on djp.id=dpp.djpid
            INNER JOIN jenispagu_t jp on jp.id=dpp.jpid
            where  pg.noidentitas ='$nik' and dpp.djpid=141
            and pp.tglpelayanan between '$t_awal' and '$t_akhir'

        "));
       
        $result['remun']['totalremun'] = $dataTotalRemun;
        $result['remun']['detailtotalremun'] = $dataDetailTotalRemun;
        return $this->respond($result);
    }
    public function rekapHarianPasienDokter(Request $r){
        $kdProfile =  $this->kdProfile;
        $t_awal = $r['tglawal'];
        $t_akhir = $r['tglakhir'];
        $nik = $r['nik'];
     
        
        $dataPasienDokter=0;
        $dataPasienDokter = collect(DB::select("
        
            select count(pd.norec) as jml ,'$t_awal' as tglawal , '$t_akhir' as tglakhir,pg.noidentitas as nikdokter
            from pasiendaftar_t pd 
            INNER JOIN pasien_m ps on ps.id=pd.nocmfk
            INNER JOIN pegawai_m pg on pg.id=pd.objectpegawaifk
            INNER JOIN jeniskelamin_m jk on jk.id=ps.objectjeniskelaminfk
            INNER JOIN ruangan_m ru on ru.id=pd.objectruanganlastfk
            INNER JOIN departemen_m dp on dp.id=ru.objectdepartemenfk
            INNER JOIN kelompokpasien_m kp on kp.id=pd.objectkelompokpasienlastfk
            INNER JOIN asalrujukan_m ar on ar.id=pd.asalrujukanfk
            where pd.tglregistrasi between '$t_awal' and '$t_akhir'
            and pg.noidentitas ='$nik'
            group by pg.noidentitas;
        "));
        $dataDetailPasienDokter = [];
        $dataDetailPasienDokter = collect(DB::select("
        
            select pd.noregistrasi,pd.tglregistrasi,ps.nocm,ps.namapasien, jk.jeniskelamin,ps.tempatlahir,ps.tgllahir,pg.namalengkap as dokter,
            ru.namaruangan,dp.namadepartemen as instalasi,kp.kelompokpasien,pd.statuspasien,
            case when pd.nostruklastfk is not null then 'VERIFIKASI TAGIHAN' else '' end as statuspelayanan,
            case when pd.nosbmlastfk is null then '' else 'LUNAS KWITANSI' end as statuspembayaran,
            ar.asalrujukan,'data limit 50' as desc,pg.noidentitas as nikdokter
            from pasiendaftar_t pd 
            INNER JOIN pasien_m ps on ps.id=pd.nocmfk
            INNER JOIN pegawai_m pg on pg.id=pd.objectpegawaifk
            INNER JOIN jeniskelamin_m jk on jk.id=ps.objectjeniskelaminfk
            INNER JOIN ruangan_m ru on ru.id=pd.objectruanganlastfk
            INNER JOIN departemen_m dp on dp.id=ru.objectdepartemenfk
            INNER JOIN kelompokpasien_m kp on kp.id=pd.objectkelompokpasienlastfk
            INNER JOIN asalrujukan_m ar on ar.id=pd.asalrujukanfk
            where pd.tglregistrasi between '$t_awal' and '$t_akhir'
            and pg.noidentitas ='$nik' limit 50;

        "));

        $result['pasiendokter']['qtypasiendokter'] = $dataPasienDokter;
        $result['pasiendokter']['detailpasiendokter'] = $dataDetailPasienDokter;
        return $this->respond($result);
    }
}
