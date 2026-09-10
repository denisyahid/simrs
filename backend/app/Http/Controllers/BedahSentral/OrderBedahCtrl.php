<?php

namespace App\Http\Controllers\BedahSentral;

use App\Http\Controllers\Controller;
use App\Models\Master\Ruangan;
use App\Models\Transaksi\StrukOrder;
use App\Traits\Valet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\App;

class OrderBedahCtrl extends Controller
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
                'pd.jenispelayanan'
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

        $res['jenisOperasi'] = DB::table('jenisoperasi_m')->select('id', 'jenisoperasi')
            ->where('kdprofile', $this->kdProfile)
            ->where('statusenabled', true)->get();
        $res['ruanganLab'] = Ruangan::mine()
            ->where('objectdepartemenfk', $this->settingFix('idDepartemenBedah'))
            ->get();
        $res['kamaroperasi'] = DB::table('kamaroperasi_m')
            ->select('id', 'namakamarok')
            ->where('kdprofile', $this->kdProfile)
            ->where('statusenabled', true)
            ->get();
        return $this->respond($res);
    }

    public function listDropdownNuklir(Request $r)
    {

        $res['jenisOperasi'] = DB::table('jenisoperasi_m')->select('id', 'jenisoperasi')->where('kdprofile', $this->kdProfile)
            ->where('statusenabled', true)->get();
        $res['ruanganLab'] = Ruangan::mine()
            ->where('id', $this->settingFix('idRuanganNuklir'))
            ->get();
        $res['kamaroperasi'] = DB::table('kamaroperasi_m')
            ->select('id', 'namakamarok')
            ->where('kdprofile', $this->kdProfile)
            ->where('statusenabled', true)
            ->get();
        $res['radionuklidaterapi'] = DB::table('radionuklida_m')
            ->where('kdprofile', $this->kdProfile)
            ->where('statusenabled', true)
            ->where('isterapi', true)
            ->get();
        $res['radionuklidadiagnostik'] = DB::table('radionuklida_m')
            ->where('kdprofile', $this->kdProfile)
            ->where('statusenabled', true)
            ->where('isdiagnostik', true)
            ->get();
        $res['farmaka'] = DB::table('farmaka_m')
            ->where('kdprofile', $this->kdProfile)
            ->where('statusenabled', true)
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
            ->select(
                'mpr.objectprodukfk as id',
                'prd.namaproduk',
                'prd.objectdetailjenisprodukfk',
                'mpr.objectruanganfk',
                'prd.namaproduk'
            )
            ->where('mpr.kdprofile', $kdProfile)
            ->where('mpr.objectruanganfk', $r['ruanganfk'])
            ->where('mpr.statusenabled', true)
            ->where('prd.statusenabled', true)
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


    public function listRiwayatOrder(Request $r)
    {
        $kdProfile =  $this->kdProfile;
        $depLab = $this->settingFix('idDepartemenBedah');
        $nocmfk = '';
        $norec_pd = '';
        if (isset($r['nocmfk']) && $r['nocmfk'] != '') {
            $nocmfk = " and pd.nocmfk='" . $r['nocmfk'] . "'";
        }
        // if (isset($r['norec_pd']) && $r['norec_pd'] != '') {
        //     $norec_pd = " and pd.norec='" . $r['norec_pd'] . "'";
        // }
        $data = collect(DB::select("select so.tglorder,so.noorder,
        pr.id,pr.namaproduk,op.qtyproduk,so.norec,
        so.tglpelayananawal as tgloperasi,so.estimasiwaktuoperasi,
        ru.namaruangan as ruanganasal,p.namalengkap as dokter, pd.noregistrasi, pd.norec as norec_pd,
        case when so.statusorder = 1 then 'verifikasi'  
        when so.statusorder = 2 then 'selesai'
        else 'pending' end as status,
        case when so.statusorder = 1 then 'info'  
        when so.statusorder = 2 then 'success'
        else 'warning' end as color_status,
        op.norec as norec_op,jo.jenisoperasi
        from strukorder_t as so
        left join orderpelayanan_t as op on op.noorderfk = so.norec
        inner join pasiendaftar_t as pd on pd.norec=so.noregistrasifk
        inner join pasien_m as ps on ps.id=pd.nocmfk
        left join produk_m as pr on pr.id=op.objectprodukfk
        inner join ruangan_m as ru on ru.id=so.objectruanganfk
        inner join ruangan_m as ru2 on ru2.id=so.objectruangantujuanfk
        left join pegawai_m as p on p.id=so.objectpegawaiorderfk
        left join jenisoperasi_m as jo on jo.id=so.jenisoperasifk
        where 
        pd.kdprofile=$kdProfile
        and pd.statusenabled=true
        and so.statusenabled=true
        and ru2.objectdepartemenfk =$depLab
        $norec_pd
        $nocmfk
        union all 
        
        select pp.tglpelayanan as tglorder,null as noorder,
        pr.id,pr.namaproduk,pp.jumlah as qtyproduk ,pp.norec,
        null as tgloperasi, null as estimasiwaktuoperasi,
        ru2.namaruangan as ruanganasal,p.namalengkap as dokter, pd.noregistrasi, pd.norec as norec_pd,
        'selesai' as status,'success' as color_status,
        null as norec_op,null  as jenisoperasi
        from pelayananpasien_t as pp
        inner join antrianpasiendiperiksa_t as apd on apd.norec=pp.noregistrasifk
        inner join pasiendaftar_t as pd on pd.norec=apd.noregistrasifk
        inner join ruangan_m as ru on ru.id=apd.objectruanganfk
        inner join ruangan_m as ru2 on ru2.id=pd.objectruanganlastfk
        inner join pasien_m as ps on ps.id=pd.nocmfk
        inner join produk_m as pr on pr.id=pp.produkfk
        left join pegawai_m as p on p.id=apd.objectpegawaifk
        where 
        pd.kdprofile=$kdProfile
        and pd.statusenabled=true
        and pp.strukresepfk is NULL
        and pp.strukorderfk is null
        and ru.objectdepartemenfk =$depLab
        $norec_pd
        $nocmfk
        "));

        $sama = false;
        $group  = [];
        foreach ($data as $item) {
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
                            'namaproduk' =>  $gg->namaproduk,
                        );
                    };
                }
                $group[] = array(
                    'tglorder' => $item->tglorder,
                    'noorder' => $item->noorder,
                    'tgloperasi' => $item->tgloperasi,
                    'estimasiwaktuoperasi' => $item->estimasiwaktuoperasi,
                    'norec' => $item->norec,
                    'ruanganasal' => $item->ruanganasal,
                    'dokter' => $item->dokter,
                    'color_status' => $item->color_status,
                    'status' => $item->status,
                    'jenisoperasi' => $item->jenisoperasi,
                    'details' => $dataDetail0,
                    'noregistrasi' => $item->noregistrasi,
                    'norec_pd' => $item->norec_pd
                );
            }
        }
        return $this->respond($group);
    }

    public function detailOrder(Request $r)
    {
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
        so.keteranganlainnya, so.dokteroperatorfk, so.tgloperasi,
        so.dokteranastesifk, so.diagnosis, so.durasi,
        so.persiapan, so.tb, so.bb, so.jaminan,
        op.norec as norec_op,so.cito, so.riwayatswab,
        so.anastesitambahanfk, so.operatorhelperfk, so.isanastesi,
        so.iselektif, so.isurgent, so.alat
        from strukorder_t as so
        left join orderpelayanan_t as op on op.noorderfk = so.norec
        inner join pasiendaftar_t as pd on pd.norec=so.noregistrasifk
        inner join pasien_m as ps on ps.id=pd.nocmfk
        left join produk_m as pr on pr.id=op.objectprodukfk
        inner join ruangan_m as ru on ru.id=so.objectruanganfk
        inner join ruangan_m as ru2 on ru2.id=so.objectruangantujuanfk
        left join pegawai_m as p on p.id=so.objectpegawaiorderfk
        where 
        pd.kdprofile=$kdProfile
        and pd.statusenabled=true
        and so.statusenabled=true
        and so.norec='$r[norec]'
        "));

        //var_dump($data);

        return $this->respond($data);
    }
    public  function simpanOrderBedah(Request $r)
    {
        # code...
    }
    public function hapusOrderBedah(Request $request)
    {
        DB::beginTransaction();
        try {
            StrukOrder::where('noorder', request(['noorder']))
                ->where('kdprofile', $this->kdProfile)
                ->delete();

            $transStatus = 'true';
        } catch (\Exception $e) {
            $transStatus = 'false';
        }
        if ($transStatus == 'true') {
            $transMessage = "Sukses";
            DB::commit();
            $result = array(
                "status" => 200,
                "result" => array(
                    "data" => null,
                    "as" => '@epic',
                ),
            );
        } else {
            $transMessage = "Hapus Data Gagal";
            DB::rollBack();
            $result = array(
                "status" => 400,
                "result" => null
            );
        }
        return $this->respond($result['result'], $result['status'], $transMessage);
    }

    public function saveRegistrasi(Request $r)
    {
        if ((isset($r['pasiendaftar']['isjkn']) && $r['pasiendaftar']['isjkn'] == true)) {
        } else {
            DB::beginTransaction();
        }
        try {
            //region Save
            $kdProfile = $this->kdProfile;
            $PD = $r['pasiendaftar'];
            $APD = $r['antrianpasiendiperiksa'];

            // $isLama = PasienDaftar::where('nocmfk',$PD['nocmfk'])->count();

            $cekDepartemen = Ruangan::where('statusenabled', true)->where('kdprofile', $this->kdProfile)
                ->where('id', $PD['objectruanganlastfk'])
                ->first();
            $isIGD = $cekDepartemen->objectdepartemenfk == $this->settingFix('idDepartemenIGD') ? 'true' : 'false';

            $cekRI = PasienDaftar::where('nocmfk', $PD['nocmfk'])
                ->whereNull('tglpulang')
                ->where('statusenabled', true)
                ->first();
            if (!empty($cekRI) && $PD['norec'] == '') {
                DB::rollBack();
                $transMessage = 'Pasien belum dipulangkan dg No. Registrasi : '
                    . $cekRI->noregistrasi . ' (' . $cekRI->tglregistrasi . ')';
                $result = array("status" => 400, "result"  => $cekRI);
                return $this->respond($result['result'], $result['status'], $transMessage);
            }
            $cekStatus = PasienDaftar::where('nocmfk', $PD['nocmfk'])
                ->whereNotNull('tglmeninggal')
                ->where('statusenabled', true)
                ->first();
            // if (!empty($cekStatus) && $PD['norec'] == '') {
            //     DB::rollBack();
            //     $transMessage = 'Pasien sudah Meninggal : '
            //         . $cekStatus->noregistrasi . ' (' . $cekStatus->tglmeninggal . ')';
            //     $result = array("status" => 400, "result"  => $cekStatus);
            //     return $this->respond($result['result'], $result['status'], $transMessage);
            // }
            $SET['idNonKelas'] = (int) $this->settingFix('idNonKelas');
            $SET['idJenisPelayananEksek'] = (int) $this->settingFix('idJenisPelayananEksek');
            $SET['idKelasIPKKU'] = (int) $this->settingFix('idKelasIPKKU');
            if ($PD['norec'] == '') {
                $noregistrasi = $this->SEQUENCE(new PasienDaftar, 'noregistrasi', 10, date('ymd'), $kdProfile);
                $noAntrian = 0;
                if ($noregistrasi == '') {
                    abort(400, 'SEQ ERROR');
                }
                $model_PD = new PasienDaftar();
                $model_PD->norec = $model_PD->generateNewId();
                $model_PD->kdprofile = $kdProfile;
                $model_PD->statusenabled = true;
                $model_PD->objectruanganasalfk = $PD['objectruanganlastfk'];
                $model_PD->statuspasien = $PD['statuspasien'];
                $namaLog = 'Tambah Registrasi ke Ruang ' . Ruangan::mine()->where('id', $PD['objectruanganlastfk'])->first()->namaruangan . ' ';
                if ($PD['israwatinap'] == 'true') {
                    $model_PD->tglpulang = null;
                }
            } else {
                $model_PD =  PasienDaftar::where('norec', $PD['norec'])->first();
                $noregistrasi = $model_PD->noregistrasi;
                $namaLog = 'Edit Registrasi ke Ruang ' . Ruangan::mine()->where('id', $PD['objectruanganlastfk'])->first()->namaruangan . ' ';
            }
            $model_PD->objectruanganlastfk = $PD['objectruanganlastfk'];
            $model_PD->objectpegawaifk =  $PD['objectpegawaifk'];
            $model_PD->objectpegawairawatbersamafk =  isset($PD['objectpegawairawatbersamafk']) ? $PD['objectpegawairawatbersamafk'] : null;
            // $model_PD->jenispelayananfk =   $PD['jenispelayananfk'];
            $model_PD->jenispelayanan =   $PD['jenispelayananfk'];
            if ($PD['israwatinap'] == 'true') {
                $model_PD->objectkelasfk = $PD['objectkelasfk'];
                $model_PD->objectkelasrawatfk = $PD['objectkelasrawatfk'];
                $model_PD->tglpulang = null;
            } else {

                if ($PD['jenispelayananfk'] == $SET['idJenisPelayananEksek']) {
                    $model_PD->objectkelasfk =  $SET['idKelasIPKKU'];
                } else {
                    $model_PD->objectkelasfk =  $SET['idNonKelas'];
                }
                $model_PD->tglpulang =  $isIGD == 'true' ? null : $PD['tglregistrasi'];
            }
            $model_PD->objectkelompokpasienlastfk = $PD['objectkelompokpasienlastfk'];
            $model_PD->nocmfk = $PD['nocmfk'];
            $model_PD->objectrekananfk = $PD['objectrekananfk'];
            // $model_PD->statuspasien = $isLama == 0 ? 'BARU' : 'LAMA';
            $model_PD->ismobilejkn = isset($PD['isjkn']) ? $PD['isjkn'] : null;
            $model_PD->antrianpasienregistrasifk = isset($PD['antrianpasienregistrasifk']) ? $PD['antrianpasienregistrasifk'] : null;
            $model_PD->noreservasi = isset($PD['noreservasi']) ? $PD['noreservasi'] : null;
            $model_PD->tglregistrasi =  $PD['tglregistrasi'];
            $model_PD->asalrujukanfk =  $PD['asalrujukanfk'];
            $model_PD->keteranganasalrujukan = $PD['asalrujukanfk'] == 5 ? null : (isset($PD['keteranganasalrujukan']) ? $PD['keteranganasalrujukan'] : null);
            $model_PD->noregistrasi = $noregistrasi;
            $model_PD->petugas = $this->getNamaPegawai();
            $model_PD->iskiosk = isset($PD['iskiosk']) ? $PD['iskiosk'] : null;

            if (isset($PD['antrianpasienregistrasifk']) && (isset($PD['statusschedule']) && $PD['statusschedule'] != 'Kios-K')) {
                $reserv = AntrianPasienRegistrasi::where('noreservasi', $PD['statusschedule'])->first();
                if (!empty($reserv)) {
                    $model_PD->antrianpasienregistrasifk = $reserv->norec;
                }
            }

            $model_PD->save();

            if ($model_PD->antrianpasienregistrasifk != null) {
                AntrianPasienRegistrasi::where('norec', $model_PD->antrianpasienregistrasifk)
                    ->update(['isconfirm' => true, 'pasiendaftarfk' => $model_PD->norec]);
            }
            if ($PD['israwatinap'] == 'true') {
                $SET['idStatusBedKosong'] = $this->settingFix('idStatusBedKosong');
                $SET['idStatusBedIsi'] = $this->settingFix('idStatusBedIsi');
            }

            if ($APD['norec'] == '') {
                $max = AntrianPasienDiperiksa::where('objectruanganfk', $PD['objectruanganlastfk'])
                    ->where('tglregistrasi', '>=', date('Y-m-d', strtotime($PD['tglregistrasi'])) . ' 00:00')
                    ->where('tglregistrasi', '<=', date('Y-m-d', strtotime($PD['tglregistrasi'])) . ' 23:59')
                    ->where('statusenabled', true)
                    ->max('noantrian');
                $noAntrian = $max + 1;

                $model_APD = new AntrianPasienDiperiksa;
                $model_APD->norec = $model_APD->generateNewId();
                $model_APD->kdprofile = (int)$kdProfile;
                $model_APD->statusenabled = true;
                $model_APD->noantrian = $noAntrian;
            } else {
                $model_APD =  AntrianPasienDiperiksa::where('norec', $APD['norec'])->first();
                if ($PD['objectruanganlastfk'] != $model_APD->objectruanganfk) {
                    $max = AntrianPasienDiperiksa::where('objectruanganfk', $PD['objectruanganlastfk'])
                        ->where('tglregistrasi', '>=', date('Y-m-d', strtotime($PD['tglregistrasi'])) . ' 00:00')
                        ->where('tglregistrasi', '<=', date('Y-m-d', strtotime($PD['tglregistrasi'])) . ' 23:59')
                        ->where('statusenabled', true)
                        ->max('noantrian');
                    $noAntrian = $max + 1;
                    $model_APD->noantrian = $noAntrian;
                }
                if ($PD['israwatinap'] == 'true') {
                    DB::table('tempattidur_m')
                        ->where('id', $model_APD->nobed)
                        ->lockForUpdate()
                        ->update(['objectstatusbedfk' =>  $SET['idStatusBedKosong']]);
                    // TempatTidur::where('id', $model_APD->nobed)->update();
                }
            }

            $model_APD->objectasalrujukanfk =  $PD['asalrujukanfk'];
            $model_APD->objectkamarfk = $APD['objectkamarfk'];
            $model_APD->objectruanganfk = $PD['objectruanganlastfk'];
            if ($PD['israwatinap'] == 'true') {
                $model_APD->objectkelasfk = $PD['objectkelasfk'];
                $model_APD->kelasrawatfk = $PD['objectkelasrawatfk'];
                $model_APD->tglkeluar = null;
            } else {

                if ($PD['jenispelayananfk'] == $SET['idJenisPelayananEksek']) {
                    $model_APD->objectkelasfk =  $SET['idKelasIPKKU'];
                } else {
                    $model_APD->objectkelasfk =  $SET['idNonKelas'];
                }
                $model_APD->tglkeluar = $isIGD == 'true' ? null : $PD['tglregistrasi'];
            }
            $model_APD->nobed = $APD['nobed'];
            $model_APD->noregistrasifk = $model_PD->norec;
            $model_APD->objectpegawaifk =  $PD['objectpegawaifk'];
            $model_APD->statusantrian = 0;
            $model_APD->status = "Belum Dipanggil";
            $model_APD->statuskunjungan = $PD['statuspasien'];
            $model_APD->tglregistrasi =  $PD['tglregistrasi'];
            $model_APD->tglmasuk = $PD['tglregistrasi'];
            $model_APD->israwatgabung = isset($APD['israwatgabung']) ? $APD['israwatgabung'] : false;
            $model_APD->nojkn = isset($PD['isjkn']) ? $PD['nojkn'] : null;
            $model_APD->noregistrasi = $noregistrasi;
            $model_APD->save();

            $AksesEMRExsist = AksesEMR::where('statusenabled', true)->where('kdprofile', $this->kdProfile)->where('pasienfk', $PD['nocmfk'])->first();
            $nextDay = $PD['israwatinap'] || $isIGD == 'true' ? null : date('Y-m-d', strtotime('+1 day', strtotime(date('Y-m-d'))));

            if ($AksesEMRExsist) {
                AksesEMR::where('statusenabled', true)->where('kdprofile', $this->kdProfile)->where('pasienfk', $PD['nocmfk'])
                    ->update([
                        'pegawaipenerimafk' => is_int($this->getPegawaiId()) ? $this->getPegawaiId() : 1,
                        'tglmulai' =>  date('Y-m-d'),
                        'tglberakhir' =>  $nextDay,
                        'deskripsi' => 'Akses EMR Dibuka dari Registrasi Pasien',
                        'objectkelompokuserfk' => '',
                        'pegawaipemohonfk' => null,
                    ]);
            } else {
                $aksesEMR = new AksesEMR();
                $aksesEMR->norec = $aksesEMR->generateNewId();
                $aksesEMR->statusenabled = true;
                $aksesEMR->kdprofile = $this->kdProfile;
                $aksesEMR->pegawaipenerimafk = is_int($this->getPegawaiId()) ? $this->getPegawaiId() : 1;
                $aksesEMR->pasienfk = $PD['nocmfk'];
                $aksesEMR->tglmulai = date('Y-m-d');
                $aksesEMR->objectkelompokuserfk = '';
                $aksesEMR->tglberakhir = $nextDay;
                $aksesEMR->deskripsi = 'Akses EMR Dibuka dari Registrasi Pasien';
                $aksesEMR->save();
            }


            if ($PD['israwatinap'] == 'true') {
                $cek = DB::table('tempattidur_m')
                    ->where('kdprofile', $this->kdProfile)
                    ->where('statusenabled', true)
                    ->where('id',  $APD['nobed'])
                    ->first();

                if (!empty($cek) && $cek->objectstatusbedfk == $SET['idStatusBedIsi']) {
                    DB::rollBack();
                    $transMessage = 'Bed Sudah Terisi, Silakan Pilih Bed Lain';
                    $result = array("status" => 400, 'message' => $transMessage, "result"  => $cek);
                    return $this->respond($result, $result['status'], $transMessage);
                }

                DB::table('tempattidur_m')
                    ->where('kdprofile', $this->kdProfile)
                    ->where('statusenabled', true)
                    ->where('id',  $APD['nobed'])
                    ->lockForUpdate()
                    ->update(['objectstatusbedfk' =>  $SET['idStatusBedIsi']]);

                $this->historyBED([
                    "tempattidurfk" =>  $APD['nobed'],
                    "statusbedfk" => $SET['idStatusBedIsi'],
                    "ruanganfk" => $PD['objectruanganlastfk'],
                    "kamarfk" => $APD['objectkamarfk'],
                ]);
            }

            //endregion
            $ps = Pasien::where('id', $PD['nocmfk'])->first();

            Pasien::where('id', $PD['nocmfk'])->where('kdprofile', $this->kdProfile)->where('statusenabled', true)->update(['statusemr' => null]);

            $this->LOGGING(
                'Registrasi Pasien',
                $model_PD->norec,
                'pasiendaftar_t',
                $namaLog . ' pada Pasien ' .  $ps->namapasien . ' (' . $ps->nocm . ') - ' . $noregistrasi
            );

            $transStatus = 'true';
        } catch (\Exception $e) {
            $transStatus = 'false';
        }

        if ($transStatus == 'true') {
            $responseTelem = null;
            $transMessage = "Sukses";
            if ((isset($r['pasiendaftar']['isjkn']) && $r['pasiendaftar']['isjkn'] == true)) {
            } else {
                DB::commit();
            }
            $aplicare = null;
            if ($PD['israwatinap'] == 'true' || $PD['israwatinap'] == true) {
                $objetoRequest = new \Illuminate\Http\Request();
                $objetoRequest['idruangan'] = $model_APD->objectruanganfk;
                $objetoRequest['idkelas'] = $model_APD->objectkelasfk;
                $aplicare = app('App\Http\Controllers\Bridging\BridgingBPJSCtrl')->updateAplicaresBedAfter($objetoRequest);
            }
            // if ($PD['norec'] == '' && $PD['israwatinap'] == false) {
            //     $ruangan = Ruangan::where('id', $model_PD->objectruanganlastfk)->first();
            //     $dataJsonSend = array(
            //         'nocm' => $ps->nocm,
            //         'namapasien' => $ps->namapasien,
            //         'namaruangan' => $ruangan->namaruangan,
            //         'idruangan' => (string) $model_PD->objectruanganlastfk,
            //         'noantrian' =>(string) $model_APD->noantrian
            //     );
            //     $url = $this->settingFix('urlTelemedicine') . "my-queues";

            //     $headers['Content-Type']  = 'application/json';

            //     $resTel = Http::withHeaders($headers)
            //     ->withoutVerifying()
            //     ->withOptions(["verify" => false])
            //     ->post($url, $dataJsonSend);
            //     $responseTelem = $resTel->json();
            // }

            $ihs = null;
            $objetoRequest = new \Illuminate\Http\Request();
            $objetoRequest['noregistrasi'] = $model_PD->noregistrasi;
            $ihs = app('App\Http\Controllers\Bridging\SATUSEHATCtrl')->Encounter($objetoRequest, true);
            $result = array(
                "status" => 200,
                "result" => array(
                    "dataPD" => $model_PD,
                    "dataAPD" => $model_APD,
                    "registrasi"  => array(
                        "pd" => $model_PD,
                        "apd" => $model_APD, #
                        "nocm" => $ps->nocm,
                    ),
                    'Encounter' => $ihs,
                    'Aplicare' => $aplicare,
                    // "queue_telemedicine" => $responseTelem,
                    "as" => '@epic',
                ),
            );
        } else {
            $transMessage = "Simpan Gagal";
            if ((isset($r['pasiendaftar']['isjkn']) && $r['pasiendaftar']['isjkn'] == true)) {
                Log::info("Simpan JKN gagal " . $e->getMessage() . " " . $e->getLine());
            } else {
                DB::rollBack();
            }
            $result = array(
                "status" => 400,
                "result"  => $e->getMessage() . ' ' . $e->getline()
            );
        }
        return $this->respond($result['result'], $result['status'], $transMessage);
    }

    public function autoFillBedah(Request $r)
    {
        try {
            $data = DB::table('pasiendaftar_t as pd')
                ->leftjoin('pasien_m as ps', 'ps.id', 'pd.nocmfk')
                ->leftJoin('rekanan_m as rk', 'pd.objectrekananfk', 'rk.id')
                ->leftJoin('pegawai_m as pg', 'pg.id', 'pd.objectpegawaifk')
                ->select(
                    'rk.namarekanan',
                    'rk.id',
                    'ps.nohp',
                    'ps.telponpenanggungjawab as nohpkeluarga',
                    'pg.namalengkap as dokterdpjp',
                    'ps.id as nocmfk',
                    'pd.objectpegawaifk as objectdpjp'
                )
                ->where('pd.norec', $r['norec_pd'])
                ->first();

            if (isset($data)) {
                $medis = DB::connection('mongodb')
                    ->table('AsesmenMedisRawatJalan')
                    ->where('pasien.nocmfk', $data->nocmfk)
                    ->where('statusenabled', true)
                    ->select(
                        'created_at',
                        'perlukontrol',
                        'riwayatkeluar',
                        'statuskeluar',
                        'keadaanumum',
                        'hasilpemeriksaanpenunjang',
                        'instruksiAsesmen',
                        'TADiagnosa',
                        'normal',
                        'tinggiBadanObgyn',
                        'beratBadanObgyn',
                    )
                    ->latest()
                    ->first();
                if (isset($medis)) {
                    $data->diagnosa = $medis['TADiagnosa'] ?? '';
                    $data->tinggiBadan = $medis['tinggiBadanObgyn'] ?? '';
                    $data->beratBadan = $medis['beratBadanObgyn'] ?? '';
                }
            }
            if (isset($data)) {
                $persetujuan = DB::connection('mongodb')
                    ->table('PersetujuanTindakanKedokteran')
                    ->where('pasien.nocmfk', $data->nocmfk)
                    ->where('statusenabled', true)
                    ->select(
                        'persetujuan'
                    )
                    ->latest()
                    ->first();
                if (isset($persetujuan)) {
                    $data->keterangan = $persetujuan['persetujuan'] ?? '';
                }
            }
        } catch (\Exception $e) {
            $data['code'] = 500;
            $data['message'] = $e->getMessage();
        }

        return $this->respond($data);
    }

    public function SuratPengantarRencanaOperasi(Request $r)
    {
        $noregistrasi = '';
        $nocmfk = '';
        $norec_pd = '';
        $user = $r['user'];
        $profile = $this->profile();
        $kdProfile =  $this->kdProfile;
        $depLab = $this->settingFix('idDepartemenBedah');
        if (isset($r['nocmfk']) && $r['nocmfk'] != '') {
            $nocmfk = " and pd.nocmfk='" . $r['nocmfk'] . "'";
        }
        if (isset($r['norec_pd']) && $r['norec_pd'] != '') {
            $norec_pd = " and pd.norec='" . $r['norec_pd'] . "'";
        }
        if (isset($r['noregistrasi']) && $r['noregistrasi'] != '') {
            $noregistrasi = " and pd.noregistrasi='" . $r['noregistrasi'] . "'";
        }

        $dataRegis = collect(DB::select("SELECT 
            so.tglorder, so.noorder,ps.tgllahir,
            ps.namapasien, ps.nocm, jk.jeniskelamin, so.diagnosis, 
            peg3.namalengkap as pengorder, so.keteranganlainnya, 
            so.durasi, so.persiapan, so.created_at, so.tgloperasi,
            so.isanastesi, so.norec as norec_so, ru1.namaruangan as ruanganasal, 
            ru2.namaruangan as ruangantujuan, peg1.namalengkap as dokterpengirim, peg2.namalengkap as dokteroperator,
                peg4.dokteroperator2,peg5.dokteroperator3,
            so.tb, so.bb, so.jaminan, so.nohp, so.nohpkel, so.alat, 
            so.riwayatswab, so.riwayatvaksin, so.isurgent, so.iselektif, so.cito,
            pr.id, pr.namaproduk, op.qtyproduk, so.norec,
            CASE 
                WHEN so.statusorder = 1 THEN 'verifikasi'  
                WHEN so.statusorder = 2 THEN 'selesai'
                ELSE 'pending' 
            END AS status,
            CASE 
                WHEN so.statusorder = 1 THEN 'info'  
                WHEN so.statusorder = 2 THEN 'success'
                ELSE 'warning' 
            END AS color_status,
            op.norec as norec_op, jo.jenisoperasi

        FROM strukorder_t AS so
        LEFT JOIN orderpelayanan_t AS op ON op.noorderfk = so.norec
        INNER JOIN pasiendaftar_t AS pd ON pd.norec = so.noregistrasifk
        INNER JOIN pasien_m AS ps ON ps.id = pd.nocmfk
        LEFT JOIN jeniskelamin_m AS jk ON jk.id = ps.objectjeniskelaminfk
        LEFT JOIN produk_m AS pr ON pr.id = op.objectprodukfk
        LEFT JOIN jenisoperasi_m AS jo ON jo.id = so.jenisoperasifk
        LEFT JOIN ruangan_m AS ru1 ON ru1.id = so.objectruanganfk
        LEFT JOIN ruangan_m AS ru2 ON ru2.id = so.objectruangantujuanfk
        LEFT JOIN pegawai_m AS peg1 ON peg1.id = so.objectpegawaiorderfk
        LEFT JOIN pegawai_m AS peg2 ON peg2.id = so.dokteroperatorfk
        LEFT JOIN pegawai_m AS peg3 ON peg3.id = so.objectpegawaiorderfk
        LEFT JOIN LATERAL (
        SELECT SPLIT_PART(STRING_AGG(pg.namalengkap, ', '), ',',1) AS dokteroperator2
        FROM pegawai_m pg
        WHERE pg.id IN (
            SELECT (jsonb_array_elements_text(so.operatorhelperfk))::int
        )
        ) peg4 ON TRUE
        LEFT JOIN LATERAL (
        SELECT SPLIT_PART(STRING_AGG(pg.namalengkap, ', '), ',',2) AS dokteroperator3
        FROM pegawai_m pg
        WHERE pg.id IN (
            SELECT (jsonb_array_elements_text(so.operatorhelperfk))::int
        )
        ) peg5 ON TRUE
    WHERE 
        pd.kdprofile = $kdProfile AND 
        pd.statusenabled = TRUE AND 
        so.statusenabled = TRUE AND 
        ru2.objectdepartemenfk = $depLab
        $norec_pd
        $nocmfk
        $noregistrasi
    GROUP BY so.tglorder,so.noorder, ps.tgllahir,
        ps.namapasien, ps.nocm, jk.jeniskelamin, so.diagnosis, 
        peg3.namalengkap, so.keteranganlainnya, 
        so.durasi, so.persiapan, so.created_at, so.tgloperasi,
        so.isanastesi, so.norec, ru1.namaruangan,ru2.namaruangan, peg1.namalengkap, peg2.namalengkap,
            peg4.dokteroperator2,peg5.dokteroperator3,
        so.tb, so.bb, so.jaminan, so.nohp, so.nohpkel, so.alat, 
        so.riwayatswab, so.riwayatvaksin, so.isurgent, so.iselektif, so.cito,
        pr.id, pr.namaproduk, op.qtyproduk, so.norec,op.norec,jo.jenisoperasi
    
    UNION ALL
    
    SELECT
        pp.tglpelayanan AS tglorder, NULL AS noorder,ps.tgllahir,
        ps.namapasien, ps.nocm, jk.jeniskelamin, NULL AS diagnosis, 
        NULL AS pengorder, NULL AS keteranganlainnya, 
        NULL AS durasi, NULL AS persiapan, NULL AS created_at, NULL AS tgloperasi,
        NULL AS isanastesi, NULL AS norec_so, ru2.namaruangan AS ruanganasal, 
        NULL AS ruangantujuan, NULL AS dokterpengirim, pg1.namalengkap AS dokteroperator, pg2.namalengkap AS dokteroperator2, pg3.namalengkap AS dokteroperator3,
        NULL AS tb, NULL AS bb, NULL AS jaminan, NULL AS nohp, NULL AS nohpkel, NULL AS alat, 
        NULL AS riwayatswab, NULL AS riwayatvaksin, NULL AS isurgent, NULL AS iselektif, NULL AS cito,
        pr.id, pr.namaproduk, pp.jumlah AS qtyproduk, pp.norec,
        'selesai' AS status, 'success' AS color_status,
        NULL AS norec_op, NULL AS jenisoperasi

    FROM pelayananpasien_t AS pp
    INNER JOIN antrianpasiendiperiksa_t AS apd ON apd.norec = pp.noregistrasifk
    INNER JOIN pasiendaftar_t AS pd ON pd.norec = apd.noregistrasifk
    LEFT JOIN pelayananpasienpetugas_t as ppp on ppp.pelayananpasien = pp.norec
    LEFT JOIN pegawai_m AS pg1 ON pg1.id = ppp.objectoperator1fk
    LEFT JOIN pegawai_m AS pg2 ON pg2.id = ppp.objectoperator2fk
    LEFT JOIN pegawai_m AS pg3 ON pg3.id = ppp.objectoperator4fk
    INNER JOIN ruangan_m AS ru ON ru.id = apd.objectruanganfk
    INNER JOIN ruangan_m AS ru2 ON ru2.id = pd.objectruanganlastfk
    INNER JOIN pasien_m AS ps ON ps.id = pd.nocmfk
    LEFT JOIN jeniskelamin_m AS jk ON jk.id = ps.objectjeniskelaminfk
    INNER JOIN produk_m AS pr ON pr.id = pp.produkfk
    LEFT JOIN pegawai_m AS p ON p.id = apd.objectpegawaifk
        WHERE 
        pd.kdprofile = $kdProfile AND 
        pd.statusenabled = TRUE AND 
        pp.strukresepfk IS NULL AND 
        pp.strukorderfk IS NULL AND 
        ru.objectdepartemenfk = $depLab AND
        pg1.id IS NOT NULL 
        $norec_pd
        $nocmfk
        $noregistrasi
        "))->first();
    
// dd($dataRegis);
        $pasien = [];
        $pageWidth = 950;
        $dataReport = array(
            'namaprofile' => $profile->namalengkap,
            'alamat' => $profile->namakota,
            'user' => $user,
            'judul' => "Surat Pengantar Pasien Rencana Operasi Di Ruang Operasi IBSA",

        );
        $res['pdf'] = true;
        $judul = 'Surat Pengantar Pasien Rencana Operasi Di Ruang Operasi IBSA';
        $blade = 'report.bedah.surat-pengantar-operasi';
        // return "disini";

        if ($res['pdf'] == true) {
            $pdf = App::make('dompdf.wrapper');
            $pdf->setpaper('a4', 'portrait');
            $pdf->loadView(
                "report.bedah.surat-pengantar-operasi",
                array(
                    'dataReport' => $dataReport,
                    'pageWidth' => $pageWidth,
                    'res' => $res,
                    // 'tte' => $qrcode,
                    'dataRegis' => $dataRegis,
                )
            );
            return $pdf->stream();
        }
        return view(
            'report.bedah.surat-pengantar-operasi',
            compact('dataReport', 'pageWidth', 'profile')
        );
    }

    public function SuratPengantarRencanaOperasiKlaim(Request $r)
    {
        $noregistrasi = '';
        $nocmfk = '';
        $norec_pd = '';
        $user = $r['user'];
        $profile = $this->profile();
        $kdProfile =  $this->kdProfile;
        $depLab = $this->settingFix('idDepartemenBedah');
        if (isset($r['nocmfk']) && $r['nocmfk'] != '') {
            $nocmfk = " and pd.nocmfk='" . $r['nocmfk'] . "'";
        }
        if (isset($r['norec_pd']) && $r['norec_pd'] != '') {
            $norec_pd = " and pd.norec='" . $r['norec_pd'] . "'";
        }
        if (isset($r['noregistrasi']) && $r['noregistrasi'] != '') {
            $noregistrasi = " and pd.noregistrasi='" . $r['noregistrasi'] . "'";
        }

        $dataRegis = collect(DB::select("SELECT 
            so.tglorder, so.noorder,ps.tgllahir,
            ps.namapasien, ps.nocm, jk.jeniskelamin, so.diagnosis, 
            peg3.namalengkap as pengorder, so.keteranganlainnya, 
            so.durasi, so.persiapan, so.created_at, so.tgloperasi,
            so.isanastesi, so.norec as norec_so, ru1.namaruangan as ruanganasal, 
            ru2.namaruangan as ruangantujuan, peg1.namalengkap as dokterpengirim, peg2.namalengkap as dokteroperator,
                peg4.dokteroperator2,peg5.dokteroperator3,
            so.tb, so.bb, so.jaminan, so.nohp, so.nohpkel, so.alat, 
            so.riwayatswab, so.riwayatvaksin, so.isurgent, so.iselektif, so.cito,
            pr.id, pr.namaproduk, op.qtyproduk, so.norec,
            CASE 
                WHEN so.statusorder = 1 THEN 'verifikasi'  
                WHEN so.statusorder = 2 THEN 'selesai'
                ELSE 'pending' 
            END AS status,
            CASE 
                WHEN so.statusorder = 1 THEN 'info'  
                WHEN so.statusorder = 2 THEN 'success'
                ELSE 'warning' 
            END AS color_status,
            op.norec as norec_op, jo.jenisoperasi

        FROM strukorder_t AS so
        LEFT JOIN orderpelayanan_t AS op ON op.noorderfk = so.norec
        INNER JOIN pasiendaftar_t AS pd ON pd.norec = so.noregistrasifk
        INNER JOIN pasien_m AS ps ON ps.id = pd.nocmfk
        LEFT JOIN jeniskelamin_m AS jk ON jk.id = ps.objectjeniskelaminfk
        LEFT JOIN produk_m AS pr ON pr.id = op.objectprodukfk
        LEFT JOIN jenisoperasi_m AS jo ON jo.id = so.jenisoperasifk
        LEFT JOIN ruangan_m AS ru1 ON ru1.id = so.objectruanganfk
        LEFT JOIN ruangan_m AS ru2 ON ru2.id = so.objectruangantujuanfk
        LEFT JOIN pegawai_m AS peg1 ON peg1.id = so.objectpegawaiorderfk
        LEFT JOIN pegawai_m AS peg2 ON peg2.id = so.dokteroperatorfk
        LEFT JOIN pegawai_m AS peg3 ON peg3.id = so.objectpegawaiorderfk
        LEFT JOIN LATERAL (
        SELECT SPLIT_PART(STRING_AGG(pg.namalengkap, ', '), ',',1) AS dokteroperator2
        FROM pegawai_m pg
        WHERE pg.id IN (
            SELECT (jsonb_array_elements_text(so.operatorhelperfk))::int
        )
        ) peg4 ON TRUE
        LEFT JOIN LATERAL (
        SELECT SPLIT_PART(STRING_AGG(pg.namalengkap, ', '), ',',2) AS dokteroperator3
        FROM pegawai_m pg
        WHERE pg.id IN (
            SELECT (jsonb_array_elements_text(so.operatorhelperfk))::int
        )
        ) peg5 ON TRUE
    WHERE 
        pd.kdprofile = $kdProfile AND 
        pd.statusenabled = TRUE AND 
        so.statusenabled = TRUE AND 
        ru2.objectdepartemenfk = $depLab
        $norec_pd
        $nocmfk
        $noregistrasi
    GROUP BY so.tglorder,so.noorder, ps.tgllahir,
        ps.namapasien, ps.nocm, jk.jeniskelamin, so.diagnosis, 
        peg3.namalengkap, so.keteranganlainnya, 
        so.durasi, so.persiapan, so.created_at, so.tgloperasi,
        so.isanastesi, so.norec, ru1.namaruangan,ru2.namaruangan, peg1.namalengkap, peg2.namalengkap,
            peg4.dokteroperator2,peg5.dokteroperator3,
        so.tb, so.bb, so.jaminan, so.nohp, so.nohpkel, so.alat, 
        so.riwayatswab, so.riwayatvaksin, so.isurgent, so.iselektif, so.cito,
        pr.id, pr.namaproduk, op.qtyproduk, so.norec,op.norec,jo.jenisoperasi
    
    UNION ALL
    
    SELECT
        pp.tglpelayanan AS tglorder, NULL AS noorder,ps.tgllahir,
        ps.namapasien, ps.nocm, jk.jeniskelamin, NULL AS diagnosis, 
        NULL AS pengorder, NULL AS keteranganlainnya, 
        NULL AS durasi, NULL AS persiapan, NULL AS created_at, NULL AS tgloperasi,
        NULL AS isanastesi, NULL AS norec_so, ru2.namaruangan AS ruanganasal, 
        NULL AS ruangantujuan, NULL AS dokterpengirim, pg1.namalengkap AS dokteroperator, pg2.namalengkap AS dokteroperator2, pg3.namalengkap AS dokteroperator3,
        NULL AS tb, NULL AS bb, NULL AS jaminan, NULL AS nohp, NULL AS nohpkel, NULL AS alat, 
        NULL AS riwayatswab, NULL AS riwayatvaksin, NULL AS isurgent, NULL AS iselektif, NULL AS cito,
        pr.id, pr.namaproduk, pp.jumlah AS qtyproduk, pp.norec,
        'selesai' AS status, 'success' AS color_status,
        NULL AS norec_op, NULL AS jenisoperasi

    FROM pelayananpasien_t AS pp
    INNER JOIN antrianpasiendiperiksa_t AS apd ON apd.norec = pp.noregistrasifk
    INNER JOIN pasiendaftar_t AS pd ON pd.norec = apd.noregistrasifk
    LEFT JOIN pelayananpasienpetugas_t as ppp on ppp.pelayananpasien = pp.norec
    LEFT JOIN pegawai_m AS pg1 ON pg1.id = ppp.objectoperator1fk
    LEFT JOIN pegawai_m AS pg2 ON pg2.id = ppp.objectoperator2fk
    LEFT JOIN pegawai_m AS pg3 ON pg3.id = ppp.objectoperator4fk
    INNER JOIN ruangan_m AS ru ON ru.id = apd.objectruanganfk
    INNER JOIN ruangan_m AS ru2 ON ru2.id = pd.objectruanganlastfk
    INNER JOIN pasien_m AS ps ON ps.id = pd.nocmfk
    LEFT JOIN jeniskelamin_m AS jk ON jk.id = ps.objectjeniskelaminfk
    INNER JOIN produk_m AS pr ON pr.id = pp.produkfk
    LEFT JOIN pegawai_m AS p ON p.id = apd.objectpegawaifk
        WHERE 
        pd.kdprofile = $kdProfile AND 
        pd.statusenabled = TRUE AND 
        pp.strukresepfk IS NULL AND 
        pp.strukorderfk IS NULL AND 
        ru.objectdepartemenfk = $depLab AND
        pg1.id IS NOT NULL 
        $norec_pd
        $nocmfk
        $noregistrasi
        "))->first();
    
// dd($dataRegis);
        $pasien = [];
        $pageWidth = 950;
        $dataReport = array(
            'namaprofile' => $profile->namalengkap,
            'alamat' => $profile->namakota,
            'user' => $user,
            'judul' => "Surat Pengantar Pasien Rencana Operasi Di Ruang Operasi IBSA",

        );
        $res['pdf'] = true;
        $judul = 'Surat Pengantar Pasien Rencana Operasi Di Ruang Operasi IBSA';
        $blade = 'report.bedah.surat-pengantar-operasi';
        // return "disini";

        // if ($res['pdf'] == true) {
            $pdf = App::make('dompdf.wrapper');
            $pdf->setpaper('a4', 'portrait');
            $pdf->loadView(
                "report.bedah.surat-pengantar-operasi",
                array(
                    'dataReport' => $dataReport,
                    'pageWidth' => $pageWidth,
                    'res' => $res,
                    // 'tte' => $qrcode,
                    'dataRegis' => $dataRegis,
                )
            );
            return $pdf;
        // }
        // return view(
        //     'report.bedah.surat-pengantar-operasi',
        //     compact('dataReport', 'pageWidth', 'profile')
        // );
    }
}
