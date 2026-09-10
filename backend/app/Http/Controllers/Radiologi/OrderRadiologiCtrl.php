<?php

namespace App\Http\Controllers\Radiologi;

use App\Http\Controllers\Controller;
use App\Models\Master\Ruangan;
use App\Models\Transaksi\RisOrder;
use App\Models\Transaksi\StrukOrder;
use App\Traits\Valet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class OrderRadiologiCtrl extends Controller
{
    use Valet;
    public function __construct()
    {
        parent::__construct($is_encrypt = true);
    }
    public function headerPasienOrder(Request $r)
    {
        $data = DB::table('pasiendaftar_t as pd')
            ->JOIN('ruangan_m as ru', 'ru.id', '=', 'pd.objectruanganlastfk')
            ->JOIN('departemen_m as dp', 'dp.id', '=', 'ru.objectdepartemenfk')
            ->JOIN('kelompokpasien_m as kp', 'kp.id', '=', 'pd.objectkelompokpasienlastfk')
            ->JOIN('pasien_m as ps', 'ps.id', '=', 'pd.nocmfk')
            ->JOIN('alamat_m as alm', 'alm.nocmfk', '=', 'ps.id')
            ->JOIN('jeniskelamin_m as jk', 'jk.id', '=', 'ps.objectjeniskelaminfk')
            ->select(
                'ps.nocm',
                'ps.id as nocmfk',
                'ps.namapasien',
                'ps.tgllahir',
                'ps.tempatlahir',
                'ps.objectjeniskelaminfk',
                'jk.jeniskelamin',
                'ps.objectagamafk',
                'ps.noidentitas',
                'ps.nobpjs',
                'ps.noasuransilain',
                'alm.alamatlengkap',
                'alm.kodepos',
                'ps.notelepon',
                'ps.nohp',
                'ps.namaayah',
                'ps.namaibu',
                'ps.email'
            )
            ->where('ps.kdprofile', (int)$this->kdProfile)
            ->where('ps.statusenabled', true)
            ->where('ps.id', $r['nocmfk'])
            ->first();
        if (!empty($data)) {
            $data->umur =  $this->getAge($data->tgllahir, date('Y-m-d H:i:s'));
        }
        $registrasi =   DB::table('pasiendaftar_t as pd')
            ->JOIN('antrianpasiendiperiksa_t as apd', 'apd.noregistrasifk', '=', 'pd.norec')
            ->JOIN('ruangan_m as ru', 'ru.id', '=', 'apd.objectruanganfk')
            ->JOIN('departemen_m as dp', 'dp.id', '=', 'ru.objectdepartemenfk')
            ->JOIN('kelompokpasien_m as kp', 'kp.id', '=', 'pd.objectkelompokpasienlastfk')
            ->JOIN('kelas_m as kl', 'kl.id', '=', 'pd.objectkelasfk')
            ->select(
                'pd.noregistrasi',
                'pd.norec as norec_pd',
                'pd.tglregistrasi',
                'pd.tglpulang',
                'dp.namadepartemen',
                'kp.kelompokpasien',
                'apd.norec as norec_apd',
                'pd.objectruanganlastfk',
                'apd.objectruanganfk',
                'ru.namaruangan',
                'pd.objectkelasfk',
                'kl.namakelas',
                'pd.nocmfk',
                'apd.tglmasuk',
                'apd.tglkeluar',
                'pd.objectkelompokpasienlastfk',
                'pd.objectrekananfk',
                'pd.jenispelayanan as jenispelayananfk'
            )
            ->where('pd.kdprofile', (int)$this->kdProfile)
            ->where('pd.statusenabled', true)
            ->where('pd.statusenabled', true)
            ->where('pd.nocmfk', $r['nocmfk'])
            ->where('pd.norec', $r['norec_pd'])
            ->get();
        $last = array();
        $tgl = date('2000-01-01 00:00');
        foreach ($registrasi as $d) {
            if ($d->objectruanganlastfk == $d->objectruanganfk && $tgl < $d->tglmasuk) {
                $tgl = $d->tglmasuk;
                $last  = $d;
            }
        }
        $result['pasien'] = $data;
        $result['registrasi'] = $registrasi;
        $result['last_registrasi'] = $last;
        $result['as'] = '@epic';

        return $this->respond($result);
    }
    public function listDropdown(Request $r)
    {
        $res['ruanganLab'] = Ruangan::mine()
            ->where('objectdepartemenfk', $this->settingFix('idDepartemenRadiologi'))
            ->where('id', '!=',331)
            ->where('id', '!=',389)
            ->get();

        return $this->respond($res);
    }
    public function listTindakanForOrder(Request $r)
    {
        $kdProfile = $this->kdProfile;
        $detail = DB::table('detailjenisproduk_m')
            ->select('id', 'detailjenisproduk')
            ->where('kdprofile', $kdProfile)
            ->where('statusenabled', true)
            ->get();

        $data = DB::table('mapruangantoproduk_m as mpr')
            ->join('produk_m as prd', 'prd.id', '=', 'mpr.objectprodukfk')
            ->leftjoin('harganettoprodukbykelas_m as hnp', function ($join) {
                $join->on('hnp.objectprodukfk', 'mpr.objectprodukfk')
                    ->where('hnp.statusenabled', true);
            })
            ->select(
                'mpr.objectprodukfk as id',
                'prd.namaproduk',
                'prd.objectdetailjenisprodukfk',
                'hnp.objectkelasfk',
                'hnp.objectkebangsaanfk',
                'mpr.objectruanganfk',
                'prd.namaproduk',
                DB::raw('MAX(hnp.hargasatuan) as hargasatuan')
            )
            
            ->where('mpr.kdprofile', $kdProfile)
            ->where('mpr.objectruanganfk', $r['ruanganfk'])
            ->where('hnp.objectkebangsaanfk', $r['idkebangsaan'])
            ->where('hnp.objectkelasfk', $r['kelasfk'])
            ->where('mpr.statusenabled', true)
            ->where('prd.statusenabled', true)
            ->groupBy('mpr.objectprodukfk', 'prd.namaproduk', 'prd.objectdetailjenisprodukfk', 'mpr.objectruanganfk', 'hnp.objectkelasfk','hnp.objectkebangsaanfk')
            ->orderBy('prd.namaproduk', 'ASC')
            ->get();

        foreach ($detail as $key => $value) {
            $value->details = [];
        }
        $i = 0;
        $detail = $detail->toArray();
        foreach ($detail as $value) {
            foreach ($data as $value2) {
                if ($detail[$i]->id == $value2->objectdetailjenisprodukfk) {
                    $detail[$i]->details[] = $value2;
                }
            }
            $i++;
        }

        for ($i = count($detail) - 1; $i >= 0; $i--) {
            if (count($detail[$i]->details) == 0) {
                array_splice($detail, $i, 1);
            }
        }
        $result = array(
            'list_tindakan' => $detail,
            'data' => $data,
            'as' => '@epic',
        );

        return $this->respond($result);
    }

    public function listTindakan(Request $r)
    {
        $data = DB::table('mapruangantoproduk_m as mpr')
            ->join('produk_m as prd', 'prd.id', '=', 'mpr.objectprodukfk')
            ->select(
                'mpr.objectprodukfk as id',
                'prd.namaproduk',
                'mpr.objectruanganfk',
                'prd.namaproduk'
            )
            ->where('mpr.kdprofile', $this->kdProfile)
            ->where('mpr.objectruanganfk', $r['idruangan'])
            ->where('mpr.statusenabled', true)
            ->where('prd.statusenabled', true);

        if (
            isset($r['name']) &&
            $r['name'] != "" &&
            $r['name'] != "undefined"
        ) {
            $data = $data
                ->where('prd.namaproduk', 'ilike', '%' . $r['name'] . '%');
        }
        if (
            isset($r['limit']) &&
            $r['limit'] != "" &&
            $r['limit'] != "undefined"
        ) {
            $data = $data->take($r['limit']);
        }
        $data = $data->orderBy('prd.namaproduk', 'ASC');
        $data = $data->get();
        $result['data'] = $data;
        $result['as'] = '@epic';

        return $this->respond($result);
    }


    public function listRiwayatOrder(Request $r){
        $kdProfile =  $this->kdProfile;
        $depLab = $this->settingFix('idDepartemenRadiologi');

        $nocmfk = '';
        $norec_pd = '';
        $noreg='';
        if(isset($r['nocmfk']) && $r['nocmfk'] !=''){
            $nocmfk = " and pd.nocmfk='".$r['nocmfk'] ."'";
        }
        if(isset($r['norec_pd']) && $r['norec_pd'] !=''){
            $norec_pd = " and pd.norec='".$r['norec_pd'] ."'";
        }
        if(isset($r['noregistrasi']) && $r['noregistrasi'] !=''){
            $noreg = " and pd.noregistrasi='".$r['noregistrasi'] ."'";
        }
        $echo = '';
        if(isset($r['echo'])){
            $kdEcho = $this->settingFix('KdDetailJenisProdukEcho');
            $echo =  " and pr.objectdetailjenisprodukfk in ($kdEcho)";
        }

        $data = collect(DB::select("select so.tglorder,so.noorder,so.noregistrasi,
        pr.id,pr.namaproduk,op.qtyproduk,so.norec,pr.sanata_jasa_id,
        ru.namaruangan as ruanganasal,p.namalengkap as dokter,
        case when so.statusorder = 1 then 'verifikasi'
        when so.statusorder = 2 then 'selesai'
        else 'pending' end as status,
        case when so.statusorder = 1 then 'info'
        when so.statusorder = 2 then 'success'
        else 'warning' end as color_status,pp.produkfk,
        op.norec as norec_op,so.objectruangantujuanfk,
        pp.norec as norec_pp,hr.norec as norec_hr, p1.namalengkap as dokterbaca,p1.id as iddokterbaca,so.norec as norecso,pd.norec as norecpd,emd.file as urlfile,ps.nocm
        from strukorder_t as so
        inner join orderpelayanan_t as op on op.noorderfk = so.norec
        left join pelayananpasien_t as pp on pp.strukorderfk = so.norec and pp.produkfk=op.objectprodukfk
        left join pelayananpasienpetugas_t as ppp on ppp.pelayananpasien = pp.norec
        left join hasilradiologi_t as hr on hr.pelayananpasienfk = pp.norec
        inner join pasiendaftar_t as pd on pd.norec=so.noregistrasifk
        inner join pasien_m as ps on ps.id=pd.nocmfk
        inner join produk_m as pr on pr.id=op.objectprodukfk
        inner join ruangan_m as ru on ru.id=so.objectruanganfk
        inner join ruangan_m as ru2 on ru2.id=so.objectruangantujuanfk
        left join pegawai_m as p on p.id=so.objectpegawaiorderfk
        left join pegawai_m as p1 on p1.id=ppp.objectpegawaifk
        left join emrdokumen_t as emd on emd.norec_so=so.norec 
        where
        pd.kdprofile=$kdProfile
        and pd.statusenabled=true
        and so.statusenabled=true
        and ru2.objectdepartemenfk =$depLab
        $norec_pd
        $nocmfk
        $noreg
        $echo

        union all

        select pp.tglpelayanan as tglorder,null as noorder,pd.noregistrasi,
        pr.id,pr.namaproduk,pp.jumlah as qtyproduk ,pp.norec,pr.sanata_jasa_id,
        ru2.namaruangan as ruanganasal,p.namalengkap as dokter,
        'selesai' as status,'success' as color_status,pp.produkfk,
        null as norec_op, apd.objectruanganfk as objectruangantujuanfk,
        pp.norec as norec_pp,hr.norec as norec_hr, p1.namalengkap as dokterbaca,p1.id as iddokterbaca,pd.norec as norecpd, null as norecso,null as urlfile,ps.nocm
        from pelayananpasien_t as pp
        left join hasilradiologi_t as hr on hr.pelayananpasienfk = pp.norec
        inner join antrianpasiendiperiksa_t as apd on apd.norec=pp.noregistrasifk
        inner join pasiendaftar_t as pd on pd.norec=apd.noregistrasifk
        inner join ruangan_m as ru on ru.id=apd.objectruanganfk
        inner join ruangan_m as ru2 on ru2.id=pd.objectruanganlastfk
        inner join pasien_m as ps on ps.id=pd.nocmfk
        inner join produk_m as pr on pr.id=pp.produkfk
        left join pelayananpasienpetugas_t as ppp on ppp.pelayananpasien = pp.norec
        left join pegawai_m as p on p.id=apd.objectpegawaifk
        left join pegawai_m as p1 on p1.id=ppp.objectpegawaifk
        where
        pd.kdprofile=$kdProfile
        and pd.statusenabled=true
        and pp.strukresepfk is NULL
        and pp.strukorderfk is null
        and ru.objectdepartemenfk =$depLab
        $norec_pd
        $noreg
        $nocmfk

        "));

        if(isset($r['ruangan'] ) && $r['ruangan'] != '' && $r['ruangan'] == 'IGD') {
            $data = $data->sortByDesc('tglorder')->values();
        }

        $risorder = RisOrder::where('order_complete',true)->where('kdprofile', $kdProfile);
        if (isset($request['noregistrasi']) && $request['noregistrasi'] != "" && $request['noregistrasi'] != "undefined") {
            $risorder = $risorder->where('extension1', '=', $request['noregistrasi']);
        }
        if (isset($request['nocm']) && $request['nocm'] != "" && $request['nocm'] != "undefined") {
            $risorder = $risorder->where('patient_id', '=', $request['nocm']);
        }
        $risorder = $risorder->get();
        $sama = false;
        $group  = [];
        $urlPACSHasil = $this->settingFix('urlPACSHasil');
        foreach ($data as $item) {
            $item->radiologiId = null;
            $item->url_pacs_hasil = null;
            foreach ($risorder as $ris) {
                if ($item->noorder == $ris->order_no && $item->id == $ris->order_code) {
                    $item->radiologiId =  $ris->patient_id . '-' . $ris->order_cnt;
                    if($ris->order_complete == 1){
                        $exp = explode(',',$urlPACSHasil);
                        $exp[0] = $exp[0]. $ris->accession_num;
                        $exp[1] = $exp[1]. $ris->accession_num;
                        $urlPACSHasil = implode(",",$exp);
                        $item->url_pacs_hasil = $urlPACSHasil ;
                        $item->status = 'selesai';
                        $item->color_status = 'success';
                        
                    }
                }
            }
            $sama = false;
            $i = 0;
            foreach ($group as $itemg) {
                if ($item->norec == $group[$i]['norec']) {
                    $sama = true;
                }
                $i = $i + 1;
            }
            if ($sama == false) {
                $dataDetail0 = [];
                foreach ($data as $gg) {
                    if ($gg->norec == $item->norec) {
                        $dataDetail0[] = array(
                            'produkfk' => $gg->produkfk,
                            'idproduk' => $gg->id,
                            'sanata_jasa_id' => $gg->sanata_jasa_id,
                            'namaproduk' =>  $gg->namaproduk,
                            'radiologiId' =>  $item->radiologiId,
                            'norec_pp' =>  $gg->norec_pp,  
                            'noorder' =>  $gg->noorder,  
                            'dokterbaca' =>  $gg->dokterbaca,  
                            'iddokterbaca' =>  $gg->iddokterbaca,  
                            'norecso' =>  $gg->norecso,  
                            'norecpd' =>  $gg->norecpd,  
                            'objectruangantujuanfk' =>  $gg->objectruangantujuanfk,  
                            'url_pacs_hasil' => $item->url_pacs_hasil,
                        );
                    };
                }
                $group[] = array(
                    'tglorder' => $item->tglorder,
                    'noorder' => $item->noorder,
                    'norec' => $item->norec,
                    'noregistrasi'=>$item->noregistrasi,
                    'norec_hr' => $item->norec_hr,
                    'ruanganasal' => $item->ruanganasal,
                    'objectruangantujuanfk' => $item->objectruangantujuanfk,
                    'norecso' =>  $item->norecso,  
                    'norecpd' =>  $item->norecpd,
                    'iddokterbaca' =>  $item->iddokterbaca,  
                    'dokter' => $item->dokter,
                    'dokterbaca' => $item->dokterbaca,
                    'color_status' => $item->color_status,
                    'status' => $item->status,
                    'urlfile' => isset($item->urlfile) ? $item->urlfile : null ,
                    'nocm' =>  $item->nocm,
                    'details' => $dataDetail0
                );
            }
        }
        return $this->respond($group);
    }

    public function detailOrder(Request $r){
        $kdProfile =  $this->kdProfile;

        $data = collect(DB::select("select so.tglorder,so.noorder,
        pr.id as produkfk,pr.namaproduk,op.qtyproduk,so.norec,
        ru.namaruangan as ruanganasal,p.namalengkap as dokter,
        case when so.statusorder = 1 then 'verifikasi'
        when so.statusorder = 2 then 'selesai'
        else 'pending' end as status,
        case when so.statusorder = 1 then 'info'
        when so.statusorder = 2 then 'success'
        else 'warning' end as color_status,
        so.objectpegawaiorderfk,so.objectruangantujuanfk,
        so.keteranganlainnya,
        op.norec as norec_op,so.cito
        from strukorder_t as so
        inner join orderpelayanan_t as op on op.noorderfk = so.norec
        inner join pasiendaftar_t as pd on pd.norec=so.noregistrasifk
        inner join pasien_m as ps on ps.id=pd.nocmfk
        inner join produk_m as pr on pr.id=op.objectprodukfk
        inner join ruangan_m as ru on ru.id=so.objectruanganfk
        inner join ruangan_m as ru2 on ru2.id=so.objectruangantujuanfk
        left join pegawai_m as p on p.id=so.objectpegawaiorderfk
        where
        pd.kdprofile=$kdProfile
        and pd.statusenabled=true
        and so.statusenabled=true
        and so.norec='$r[norec]'
        "));
        return $this->respond($data);
    }

    public function hapusOrderRad (Request $request)
    {
        DB::beginTransaction();
        try {
            StrukOrder:: where('noorder', request(['noorder']))
            ->where('kdprofile', $this->kdProfile)
            ->delete();

            $transStatus ='true';
        } catch (\Exception $e) {
            $transStatus = 'false';
        }
        if ($transStatus == 'true'){
            $transMessage = "Sukses";
            DB::commit();
            $result =array(
                "status" => 200,
                "result" => array(
                    "data" => null,
                    "as" => '@epic',
                ),
            );
        } else {
            $transMessage ="Hapus Data Gagal";
            DB::rollBack();
            $result = array(
                "status" => 400,
                "result" => null
            );
        }
        return $this->respond($result['result'], $result['status'], $transMessage);

    }
}
