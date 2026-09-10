<?php

namespace App\Http\Controllers\Sysadmin;

use App\Http\Controllers\Controller;
use App\Models\Master\Agama;
use App\Models\Master\Departemen;
use App\Models\Master\DetailKategoryPegawai;
use App\Models\Master\Eselon;
use App\Models\Master\GolonganPegawai;
use App\Models\Master\HubunganKeluarga;
use App\Models\Master\Jabatan;
use App\Models\Master\JenisKelamin;
use App\Models\Master\JenisPegawai;
use App\Models\Master\JenisRekanan;
use App\Models\Master\KedudukanPegawai;
use App\Models\Master\KelompokJabatan;
use App\Models\Master\Pegawai;
use App\Models\Master\KelompokPasien;
use App\Models\Master\KelompokShiftKerja;
use App\Models\Master\KeluargaPegawai;
use App\Models\Master\Negara;
use App\Models\Master\Pekerjaan;
use App\Models\Master\Pendidikan;
use App\Models\Master\StatusPegawai;
use App\Models\Master\StatusPerkawinan;
use App\Models\Master\Suku;
use App\Models\Master\TypePegawai;
use App\Models\Transaksi\RiwayatPendidikan;
use App\Traits\Valet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;


class MasterPegawaiCtrl extends Controller
{
    use Valet;
    public function __construct()
    {
        parent::__construct($is_encrypt = true);
    }
    public function masterPegawai(Request $r)
    {
        $data  = DB::table('pegawai_m as pg')
            ->leftjoin('agama_m as ag', 'pg.objectagamafk', '=', 'ag.id')
            ->leftjoin('jabatan_m as jb', 'pg.objectjabatanfungsionalfk', '=', 'jb.id')
            ->leftjoin('departemen_m as dd', 'pg.objectunitkerjapegawaifk', '=', 'dd.id')
            ->leftjoin('jeniskelamin_m as jk', 'pg.objectjeniskelaminfk', '=', 'jk.id')
            ->leftjoin('detailkategorypegawai_m as dkp', 'pg.objectdetailkategorypegawaifk', '=', 'dkp.id')
            ->leftjoin('jenispegawai_m as jp', 'pg.objectjenispegawaifk', '=', 'jp.id')
            ->leftjoin('negara_m as ne', 'pg.objectnegarafk', '=', 'ne.id')
            ->leftjoin('pendidikan_m as pe', 'pg.objectpendidikanterakhirfk', '=', 'pe.id')
            ->leftjoin('statuspegawai_m as sp', 'pg.objectstatuspegawaifk', '=', 'sp.id')
            ->leftjoin('statusperkawinan_m as spk', 'pg.objectstatusperkawinanfk', '=', 'spk.id')
            ->leftjoin('suku_m as su', 'pg.objectsukufk', '=', 'su.id')
            ->leftjoin('typepegawai_m as tp', 'pg.objecttypepegawaifk', '=', 'tp.id')
            ->leftjoin('kelompokjabatan_m as kj', 'pg.objectkelompokjabatanfk', '=', 'kj.id')
            ->leftjoin('sdm_kedudukan_m as sku', 'pg.kedudukanfk', '=', 'sku.id')
            ->leftjoin('sdm_golongan_m as gpi', 'pg.objectgolonganpegawaifk', '=', 'gpi.id')
            ->leftjoin('eselon_m as es', 'pg.objecteselonfk', '=', 'es.id')
            ->leftjoin('sdm_kelompokshift_m as ksk', 'pg.objectshiftkerja', '=', 'ksk.id')
            // ->leftjoin('ruangan_m as ru', 'jd.objectruanganfk', 'ru.id')
            ->select(
                'pg.id',
                'ag.agama',
                'jb.namajabatan',
                'pg.objectjabatanfungsionalfk',
                'pg.objectagamafk',
                'sp.statuspegawai',
                'pe.pendidikan',
                'spk.statusperkawinan',
                'pg.objectstatusperkawinanfk',
                'ne.namanegara',
                'su.suku',
                'pg.kedudukanfk',
                'sku.kedudukan',
                'dd.namadepartemen',
                'pg.objectunitkerjapegawaifk',
                'kj.namakelompokjabatan',
                'pg.objectkelompokjabatanfk',
                'tp.typepegawai',
                'jk.jeniskelamin',
                'jp.jenispegawai',
                'pg.namalengkap',
                'pg.statusenabled',
                'pg.namapanggilan',
                'pg.statusenabled',
                'pg.bankrekeningatasnama',
                'pg.bankrekeningnama',
                'pg.bankrekeningnomor',
                'pg.fingerprintid',
                'pg.nik_intern',
                'pg.nip_pns',
                'pg.noidentitas',
                'pg.npwp',
                'pg.photodiri',
                'pg.qpegawai',
                'pg.qtyanak',
                'pg.qtytanggungan',
                'pg.qtytotaljiwa',
                'pg.tempatlahir',
                'pg.email',
                'pg.nohandphone',
                'pg.notlp',
                'pg.nobpjs',
                'pg.alamat',
                'pg.tgllahir',
                'pg.tglmasuk',
                'pg.tglkeluar',
                'pg.nosip',
                'pg.nostr',
                'pg.tglberakhirsip',
                'pg.tglberakhirstr',
                'pg.tglterbitsip',
                'pg.tglterbitstr',
                'dkp.detailkategorypegawai',
                'pg.objectgolonganpegawaifk',
                'gpi.name',
                'es.eselon',
                'pg.objecteselonfk',
                'ksk.kelompokshiftkerja',
                'pg.objectshiftkerja'

            )
            ->where('pg.statusenabled', true)
            ->where('pg.kdprofile', $this->kdProfile);

        if (isset($r['id']) && $r['id'] != '') {
            $data = $data->where('pg.id', '=',  $r['id']);
        }
        if (isset($r['namalengkap']) && $r['namalengkap'] != '') {
            $data = $data->where('pg.namalengkap', 'ilike', '%' . $r['namalengkap'] . '%');
        }
        if (isset($r['nik']) && $r['nik'] != '') {
            $data = $data->where('pg.nik_intern', '=',  $r['nik']);
        }
        if (isset($r['nobpjs']) && $r['nobpjs'] != '') {
            $data = $data->where('pg.nobpjs', '=',  $r['nobpjs']);
        }
        if (isset($r['alamat']) && $r['alamat'] != '') {
            $data = $data->where('pg.alamat', '=',  $r['alamat']);
        }
        if (isset($r['_total']) && $r['_total'] != '') {
        }
        $total = $data->count();
        if (isset($r['offset']) && $r['offset'] != '') {
            $data = $data->offset($r['offset']);
        }
        if (isset($r['limit']) && $r['limit'] != '') {
            $data = $data->limit($r['limit']);
        }

        // $data = $data->orderByDesc('pg.namalengkap');
        $data = $data->get();
        // return $data;
        foreach ($data as $d) {
            $d->tglmasuk = date('Y-m-d', strtotime($d->tgllahir));
            $d->status = 'Aktif';
            $d->status_c = 'purple';
            if ($d->tglkeluar != null) {
                $d->status = 'Nonaktif';
                $d->status_c = 'danger';
            }
        }
        $res['data'] = $data;
        $res['total'] = $total;
        return $this->respond($res);
    }
    public function masterPegawaiD3(Request $r)
    {
        $kdProfile = (int) $this->kdProfile;
        $data = DB::table('pegawai_m as pg')
            ->LEFTJOIN('jenispegawai_m as jp', 'jp.id', '=', 'pg.objectjenispegawaifk')
            ->LEFTJOIN('pendidikan_m as pend', 'pend.id', '=', 'pg.objectpendidikanterakhirfk')
            ->select(DB::raw("pg.nippns,pg.namalengkap,jp.jenispegawai,pend.pendidikan"))
            ->where('pg.kdprofile', $kdProfile)
            ->where('pg.id', '<>', 1)
            ->where('pend.nourut' ,'>' ,3)
            ->where('pg.kdprofile', $kdProfile)
            ->where('pg.objectjenispegawaifk', '=', 2);
        if (isset($r['id']) && $r['id'] != '') {
            $data = $data->where('pg.id', '=',  $r['id']);
        }
        if (isset($r['namalengkap']) && $r['namalengkap'] != '') {
            $data = $data->where('pg.namalengkap', 'ilike', '%' . $r['namalengkap'] . '%');
        }
        if (isset($r['nik']) && $r['nik'] != '') {
            $data = $data->where('pg.nik_intern', '=',  $r['nik']);
        }
        if (isset($r['nobpjs']) && $r['nobpjs'] != '') {
            $data = $data->where('pg.nobpjs', '=',  $r['nobpjs']);
        }
        if (isset($r['alamat']) && $r['alamat'] != '') {
            $data = $data->where('pg.alamat', '=',  $r['alamat']);
        }
        $total = $data->count();
        if (isset($r['offset']) && $r['offset'] != '') {
            $data = $data->offset($r['offset']);
        }
        if (isset($r['limit']) && $r['limit'] != '') {
            $data = $data->limit($r['limit']);
        }
        $data =  $data->get();
        $result = [
            'data' => $data,
            'total' => $total,
            'message' => 'success@epic',
        ];
        return $this->respond($result);
    }

    public function getDetailPegawai(Request $r)
    {
        $kdProfile = $this->kdProfile;
        $data = DB::table('pegawai_m as pg')
            ->leftJoin('agama_m as ag', 'ag.id', '=', 'pg.objectagamafk')
            ->leftJoin('detailkategorypegawai_m as dkp', 'dkp.id', '=', 'pg.objectdetailkategorypegawaifk ')
            ->leftJoin('golongandarah_m as gd', 'gd.id', '=', 'pg.objectgolongandarahfk')
            ->leftJoin('jabatan_m as jb', 'jb.id', '=', 'pg.objectjabatanfungsionalfk')
            ->leftJoin('jabatan_m as jb1', 'jb1.id', '=', 'pg.objectjabatanstrukturalfk')
            ->leftJoin('golonganpegawai_m as gp', 'gp.id', '=', 'pg.objectgolonganpegawaifk')
            ->leftJoin('unitkerjapegawai_m as uk', 'uk.id', '=', 'pg.objectunitkerjapegawaifk')
            ->leftJoin('statuspegawai_m as sp', 'sp.id', '=', 'pg.objectstatuspegawaifk')
            ->leftJoin('kelompokjabatan_m as kj', 'kj.id', '=', 'pg.objectkelompokjabatanfk')
            ->leftJoin('statusperkawinanpegawai_m as spp', 'spp.id', '=', 'pg.objectstatusperkawinanpegawaifk')
            ->leftJoin('eselon_m as ese', 'ese.id', '=', 'pg.objecteselonfk')
            ->leftJoin('pendidikan_m as pend', 'pend.id', '=', 'pg.objectpendidikanterakhirfk')
            ->leftJoin('jeniskelamin_m as jk', 'jk.id', '=', 'pg.objectjeniskelaminfk')
            ->leftJoin('sdm_golongan_m as gol', 'gol.id', '=', 'pg.objectgolonganfk')
            ->leftJoin('nilaikelompokjabatan_m as nkj', 'nkj.id', '=', 'pg.objectkelompokjabatanfk')
            ->leftJoin('sdm_kelompokshift_m as ks', 'ks.id', '=', 'pg.objectshiftkerja')
            ->leftJoin('sdm_kedudukan_m as kdd', 'kdd.id', '=', 'pg.kedudukanfk')
            ->leftJoin('kategorypegawai_m as kp', 'kp.id', '=', 'pg.kategorypegawai')
            ->leftJoin('jenispegawai_m as jp', 'jp.id', '=', 'pg.objectjenispegawaifk')
            ->select(DB::raw("pg.*,ag.agama,dkp.detailkategorypegawai,gd.golongandarah,jb.namajabatan as jbfungsional,
			                        jb1.namajabatan as jbstruktural,gp.golonganpegawai,uk.name as unitkerja,sp.statuspegawai,
			                        kj.namakelompokjabatan,spp.statusperkawinan,ese.eselon,pend.pendidikan,jk.jeniskelamin,
			                        gol.name as golongan,nkj.detailkelompokjabatan,nkj.grade,ks.kelompokshiftkerja,
			                        kp.kategorypegawai as namakategorypegawai,kdd.name as kedudukan,pg.objectjenispegawaifk,jp.jenispegawai"))
            ->where('pg.kdprofile', $kdProfile)
            ->where('pg.statusenabled', true)
            ->orderBy('pg.namalengkap');

        if (isset($request['idPegawai']) && $request['idPegawai'] != "" && $request['idPegawai'] != "undefined") {
            $data = $data->where('pg.id', '=', $request['idPegawai']);
        }
        $data = $data->get();

        $dataKeluarga = DB::table('keluargapegawai_m as kp')
            ->leftJoin('pegawai_m as pg', 'pg.id', '=', 'kp.objectpegawaifk')
            ->leftJoin('jeniskelamin_m as jk', 'jk.id', '=', 'kp.objectjeniskelaminfk')
            ->leftJoin('hubungankeluarga_m as hk', 'hk.id', '=', 'kp.objectkdhubunganfk')
            ->leftJoin('statusperkawinanpegawai_m as spp', 'spp.id', '=', 'kp.objectstatusperkawinanpegawaifk')
            ->leftJoin('pendidikan_m as pend', 'pend.id', '=', 'kp.objectpendidikanterakhirfk')
            ->leftJoin('pekerjaan_m as pe', 'pe.id', '=', 'kp.objectpekerjaanfk')
            ->select(DB::raw("kp.*,pg.namalengkap as namapegawai,jk.jeniskelamin,hk.reportdisplay as hubungankeluarga,
	                          spp.statusperkawinan,pend.pendidikan,pe.pekerjaan"))
            ->where('kp.kdprofile', $kdProfile)
            ->where('pg.statusenabled', true)
            ->orderBy('pg.namalengkap');

        if (isset($request['idPegawai']) && $request['idPegawai'] != "" && $request['idPegawai'] != "undefined") {
            $dataKeluarga = $dataKeluarga->where('pg.id', '=', $request['idPegawai']);
        }
        $dataKeluarga = $dataKeluarga->get();

        $dataPendidikan = DB::table('riwayatpendidikan_t as rp')
            ->leftJoin('pegawai_m as pg', 'pg.id', '=', 'rp.objectpegawaifk')
            ->leftJoin('pendidikan_m as pend', 'pend.id', '=', 'rp.objectpendidikanfk')
            ->select(DB::raw("rp.*,pend.pendidikan,pg.namalengkap"))
            ->where('rp.kdprofile', $kdProfile)
            ->where('pg.statusenabled', true)
            ->orderBy('rp.tgllulus');

        if (isset($request['idPegawai']) && $request['idPegawai'] != "" && $request['idPegawai'] != "undefined") {
            $dataPendidikan = $dataPendidikan->where('pg.id', '=', $request['idPegawai']);
        }
        $dataPendidikan = $dataPendidikan->get();

        $dataPelatihan = DB::table('riwayatpelatihan_t as rpl')
            ->leftJoin('pegawai_m as pg', 'pg.id', '=', 'rpl.objectpegawaifk')
            ->select(DB::raw("rpl.*,pg.namalengkap"))
            ->where('rpl.kdprofile', $kdProfile)
            ->where('pg.statusenabled', true)
            ->orderBy('rpl.tglmulai');

        if (isset($request['idPegawai']) && $request['idPegawai'] != "" && $request['idPegawai'] != "undefined") {
            $dataPelatihan = $dataPelatihan->where('pg.id', '=', $request['idPegawai']);
        }
        $dataPelatihan = $dataPelatihan->get();

        $dataJabatan = DB::table('riwayatjabatan_t as rj')
            ->leftJoin('jenisjabatan_m as jb', 'jb.id', '=', 'rj.objectjenisjabatanfk')
            ->leftJoin('jabatan_m as jab', 'jab.id', '=', 'rj.objectjabatanttdfk')
            ->leftJoin('pegawai_m as pg', 'pg.id', '=', 'rj.objectpegawaifk')
            ->leftJoin('pegawai_m as pg1', 'pg1.id', '=', 'rj.objectpegawaittdfk')
            ->select(DB::raw("rj.*,jb.jenisjabatan,jab.namajabatan as namajabatanttd,
			                  pg.namalengkap as namapegawai,pg1.namalengkap as pegawaittd,
			                  pg1.namalengkap || ' / ' || jab.namajabatan as pegawaipenanggungjawab"))
            ->where('pg.statusenabled', true)
            ->where('rj.kdprofile', $kdProfile)
            ->orderBy('rj.tglsk');
        if (isset($request['idPegawai']) && $request['idPegawai'] != "" && $request['idPegawai'] != "undefined") {
            $dataJabatan = $dataJabatan->where('pg.id', '=', $request['idPegawai']);
        }
        $dataJabatan = $dataJabatan->get();

        $result = array(
            'datapegawai' => $data,
            'datakeluarga' => $dataKeluarga,
            'datapendidikan' => $dataPendidikan,
            'datapelatihan' => $dataPelatihan,
            'datajabatan' => $dataJabatan,

            "createby : " => 'ea@epic',

        );
        return $this->respond($result);
    }

    // public function savePegawai(Request $r)
    // {
    //     DB::beginTransaction();
    //     try {
    //         //region Save Pegawai

    //         $PSN =  $r['pegawai'];
    //         if ($PSN['id'] == '') {
    //             $uuid1 = Uuid::uuid4();
    //             $id = $uuid1->toString();
    //             $dataPS = new Pegawai();
    //             $dataPS->id = $id;
    //             $dataPS->kdprofile = (int)$this->kdProfile;
    //             $dataPS->statusenabled = true;
    //         } else {
    //             $dataPS = Pegawai::where('id', $PSN['id'])
    //                 ->where('statusenabled', true)
    //                 ->first();
    //             $id =  $dataPS->id;
    //         }
    //         $dataPS->namalengkap =  $PSN['namalengkap'];
    //         $dataPS->namapanggilan =  $PSN['namapanggilan'];
    //         $dataPS->objectpendidikanterakhirfk = isset($PSN['objectpendidikanterakhirfk']['objectpendidikanterakhirfk'])?$PSN['objectpendidikanterakhirfk']['objectpendidikanterakhirfk']:null;
    //         $dataPS->objectstatuspegawaifk = isset($PSN['objectstatuspegawaifk']['objectstatuspegawaifk'])?$PSN['objectstatuspegawaifk']['objectstatuspegawaifk']:null;
    //         $dataPS->objectjeniskelaminfk = isset($PSN['objectjeniskelaminfk']['objectjeniskelaminfk'])?$PSN['objectjeniskelaminfk']['objectjeniskelaminfk']:null;
    //         $dataPS->objectagamafk = $PSN['objectagamafk'];
    //         $dataPS->objectnegarafk = $PSN['objectnegarafk'];
    //         $dataPS->objectdetailkategorypegawaifk = $PSN['objectdetailkategorypegawaifk'];
    //         $dataPS->objectstatusperkawinanfk = $PSN['objectstatusperkawinanfk'];
    //         $dataPS->objectjenispegawaifk = $PSN['objectjenispegawaifk'];
    //         $dataPS->bankrekeningatasnama = $PSN['bankrekeningatasnama'];
    //         $dataPS->bankrekeningnomor = $PSN['bankrekeningnomor'];
    //         $dataPS->objectsukufk = $PSN['objectsukufk'];
    //         $dataPS->objecttypepegawaifk = $PSN['objecttypepegawaifk'];
    //         $dataPS->objectkelompokjabatanfk = $PSN['objectkelompokjabatanfk'];
    //         $dataPS->fingerprintid = $PSN['fingerprintid'];
    //         $dataPS->nik_intern =  $PSN['nik_intern'];
    //         $dataPS->nip_pns =  $PSN['nip_pns'];
    //         $dataPS->noidentitas =  $PSN['noidentitas'];
    //         $dataPS->npwp =  $PSN['npwp'];
    //         $dataPS->photodiri =  $PSN['photodiri'];
    //         $dataPS->qpegawai =  $PSN['qpegawai'];
    //         $dataPS->qtyanak =  $PSN['qtyanak'];
    //         $dataPS->qtytanggungan =  $PSN['qtytanggungan'];
    //         $dataPS->qtytotaljiwa =  $PSN['qtytotaljiwa'];
    //         $dataPS->tempatlahir =  $PSN['tempatlahir'];
    //         $dataPS->tgllahir =  $PSN['tgllahir'];
    //         $dataPS->tglmasuk =  $PSN['tglmasuk'];
    //         $dataPS->tglkeluar =  $PSN['tglkeluar'];
    //         $dataPS->nosip =  $PSN['nosip'];
    //         $dataPS->nostr =  $PSN['nostr'];
    //         $dataPS->tglberakhirsip =  $PSN['tglberakhirsip'];
    //         $dataPS->tglberakhirstr =  $PSN['tglberakhirstr'];
    //         $dataPS->tglterbitsip =  $PSN['tglterbitsip'];
    //         $dataPS->tglterbitstr =  $PSN['tglterbitstr'];
    //         $dataPS->email =  $PSN['email'];
    //         $dataPS->nohandphone =  $PSN['nohandphone'];
    //         $dataPS->notlp =  $PSN['notlp'];
    //         $dataPS->nobpjs =  $PSN['nobpjs'];
    //         $dataPS->alamat =  $PSN['alamat'];
    //         $dataPS->kedudukanfk =  $PSN['kedudukanfk'];
    //         $dataPS->objectjabatanstrukturalfk =  $PSN['objectjabatanstrukturalfk'];
    //         $dataPS->objecteselonfk =  $PSN['objecteselonfk'];
    //         $dataPS->objectjenispegawaifk =  $PSN['objectjenispegawaifk'];
    //         $dataPS->objectshiftkerja =  $PSN['objectshiftkerja'];
    //         $dataPS->nilaijabatan =  $PSN['nilaijabatan'];
    //         $dataPS->grade =  $PSN['grade'];

    //         $dataPS->save();

    //         //endregion

    //         $transStatus = 'true';
    //     } catch (\Exception $e) {
    //         $transStatus = 'false';
    //     }

    //     if ($transStatus == 'true') {
    //         $transMessage = "Sukses";
    //         DB::commit();

    //         $result = array(
    //             "status" => 200,
    //             "result" => array(
    //                 "data"  => $dataPS,
    //                 "as" => '@epic',
    //             ),
    //         );
    //     } else {
    //         $transMessage = "Simpan Gagal";
    //         DB::rollBack();

    //         $result = array(
    //             "status" => 400,
    //             "result"  => array(
    //                 "e"  => $e->getLine() . ' ' . $e->getMessage(),
    //             ),

    //         );
    //     }
    //     return $this->respond($result['result'], $result['status'], $transMessage);
    // }
    public function savePegawai(Request $request)
    {
        $kdProfile = $this->kdProfile;
        DB::beginTransaction();

        try {
            #region savePegawai
            $dataPegawai = $request['datapegawai'];
            $dataKeluargaPegawai = $request['datakeluarga'];
            $dataRiwayatPendidikan = $request['riwayatpendidikan'];
            $message = "Simpan Data Pegawai Berhasil";

            if ($dataPegawai['id'] == '') {
                $idPegawai = $this->SEQUENCE_MASTER(new Pegawai(), 'id', $this->kdProfile);
                $dataSavePegawai = new Pegawai();
                $dataSavePegawai->id = $idPegawai;
                $dataSavePegawai->kdprofile = $kdProfile;
                $dataSavePegawai->statusenabled = true;
                $dataSavePegawai->norec = $dataSavePegawai->generateNewId();
            } else {
                $dataSavePegawai = Pegawai::where('id', $dataPegawai['id'])->where('kdprofile', $kdProfile)->first();
                $message = "Update Data Pegawai Berhasil";
            }
            $dataSavePegawai->namalengkap = $dataPegawai['namalengkap'];
            $dataSavePegawai->nama = $dataPegawai['nama'];
            $dataSavePegawai->nik_intern = $dataPegawai['nik'];
            $dataSavePegawai->gelardepan = $dataPegawai['gelardepan'];
            $dataSavePegawai->gelarbelakang = $dataPegawai['gelarbelakang'];
            $dataSavePegawai->tempatlahir = $dataPegawai['tempatlahir'];
            $dataSavePegawai->bankrekeningnomor = $dataPegawai['bankrekeningnomor'];
            $dataSavePegawai->bankrekeningatasnama = $dataPegawai['bankrekeningatasnama'];
            $dataSavePegawai->bankrekeningnama = $dataPegawai['bankrekeningnama'];
            $dataSavePegawai->npwp = $dataPegawai['npwp'];
            $dataSavePegawai->kodepos = $dataPegawai['kodepos'];
            $dataSavePegawai->email = $dataPegawai['email'];
            $dataSavePegawai->notlp = $dataPegawai['notlp'];

            $dataSavePegawai->alamat = $dataPegawai['alamat'];
            $dataSavePegawai->idfinger = $dataPegawai['idfinger'];
            $dataSavePegawai->nip = $dataPegawai['nip'];
            $dataSavePegawai->pensiun =  $dataPegawai['pensiun'];
            $dataSavePegawai->tglpensiun =  $dataPegawai['tglpensiun'];
            $dataSavePegawai->tgllahir = $dataPegawai['tgllahir'];
            $dataSavePegawai->tglmasuk = $dataPegawai['tglmasuk'];
            $dataSavePegawai->objectagamafk = $dataPegawai['objectagamafk'];
            $dataSavePegawai->objectjeniskelaminfk = $dataPegawai['objectjeniskelaminfk'];
            // $dataSavePegawai->kategorypegawai =  $dataPegawai['statuspegawai'];
            $dataSavePegawai->kedudukanfk =  $dataPegawai['kedudukanfk'];
            $dataSavePegawai->objectgolonganpegawaifk =  $dataPegawai['objectgolonganpegawaifk'];
            $dataSavePegawai->objectstatuspegawaifk =  $dataPegawai['statuspegawaifk'];
            $dataSavePegawai->objectjabatanfungsionalfk = $dataPegawai['objectjabatanfungsionalfk'];
            $dataSavePegawai->objectpendidikanterakhirfk =  $dataPegawai['objectpendidikanterakhirfk'];
            $dataSavePegawai->objectkelompokjabatanfk =  $dataPegawai['objectkelompokjabatanfk'];
            $dataSavePegawai->objectstatusperkawinanpegawaifk = $dataPegawai['objectstatusperkawinanpegawaifk'];
            $dataSavePegawai->objecteselonfk =  $dataPegawai['objecteselonfk'];
            $dataSavePegawai->objectshiftkerja =  $dataPegawai['objectshiftkerja'];
            $dataSavePegawai->pensiun = $dataPegawai['pensiun'];
            $dataSavePegawai->nilaijabatan =  $dataPegawai['nilaijabatan'];
            $dataSavePegawai->grade = $dataPegawai['grade'];
            $dataSavePegawai->objectjenispegawaifk = $dataPegawai['objectjenispegawaifk'];
            $dataSavePegawai->objectunitkerjapegawaifk = $dataPegawai['objectunitkerjapegawaifk'];
            $dataSavePegawai->save();
            $IdPegawais = $dataPegawai['id'];

            if ($IdPegawais == "") {
                $IdPegawais = $idPegawai;
            }

            if ($dataKeluargaPegawai != null) {
                $dataSaveKeluarga = KeluargaPegawai::where('objectpegawaifk', $IdPegawais)
                    ->where('kdprofile', $kdProfile)
                    ->delete();

                foreach ($dataKeluargaPegawai as $item) {

                    $idKeluarga = $this->SEQUENCE_MASTER(new KeluargaPegawai(), 'id', $this->kdProfile);
                    $dataSaveKeluarga = new KeluargaPegawai();
                    $dataSaveKeluarga->id = $idKeluarga;
                    $dataSaveKeluarga->kdprofile = $kdProfile;
                    $dataSaveKeluarga->statusenabled = true;
                    $dataSaveKeluarga->norec = $dataSaveKeluarga->generateNewId();
                    $dataSaveKeluarga->objectpegawaifk = $IdPegawais;

                    $dataSaveKeluarga->alamat = $item['alamat2'];
                    $dataSaveKeluarga->objectjeniskelaminfk = $item['objectjeniskelaminfk2'];
                    $dataSaveKeluarga->keterangan = $item['keterangan'];
                    $dataSaveKeluarga->namaayah = $item['namaayah'];
                    $dataSaveKeluarga->namaibu = $item['namaibu'];
                    $dataSaveKeluarga->namalengkap = $item['namalengkap2'];
                    $dataSaveKeluarga->objectkdhubunganfk = $item['objectkdhubunganfk'];
                    $dataSaveKeluarga->objectpekerjaanfk = $item['objectpekerjaanfk'];
                    $dataSaveKeluarga->objectstatusperkawinanpegawaifk = $item['objectstatusperkawinanpegawaifk2'];
                    $dataSaveKeluarga->tgllahir = $item['tgllahir2'];
                    $dataSaveKeluarga->objectpendidikanterakhirfk = $item['objectpendidikanterakhirfk2'];
                    $dataSaveKeluarga->save();
                }
            }

            if ($dataRiwayatPendidikan != null) {
                foreach ($dataRiwayatPendidikan as $items) {
                    if ($items['norec'] == "") {
                        $dataSavePendidikan = new RiwayatPendidikan();
                        $dataSavePendidikan->kdprofile = $kdProfile;
                        $dataSavePendidikan->statusenabled = true;
                        $dataSavePendidikan->norec = $dataSavePendidikan->generateNewId();
                        $dataSavePendidikan->objectpegawaifk = $IdPegawais;
                    } else {
                        $dataSavePendidikan = RiwayatPendidikan::where('norec', $items['norec'])
                            ->where('kdprofile', $kdProfile)
                            ->where('objectpegawaifk', $IdPegawais)
                            ->first();
                    }
                    $dataSavePendidikan->namatempatpendidikan = $items['namatempatpendidikan'];
                    $dataSavePendidikan->alamattempatpendidikan = $items['alamattempatpendidikan'];
                    $dataSavePendidikan->objectpendidikanfk = $items['objectpendidikanfk'];
                    $dataSavePendidikan->jurusan = $items['jurusan'];
                    $dataSavePendidikan->tglmasuk = $items['tglmasuk'];
                    $dataSavePendidikan->tgllulus = $items['tgllulus'];
                    $dataSavePendidikan->nilaiipk = $items['nilaiipk'];
                    $dataSavePendidikan->noijazah = $items['noijazah'];
                    if (isset($items['tglijazah'])) {
                        $dataSavePendidikan->tglijazah = $items['tglijazah'];
                    }
                    $dataSavePendidikan->save();
                }
            }

            DB::commit();

            $result = array(
                "status" => 200,
                "message" => $message,
                "result" => array(
                    "data"  => $dataSavePegawai,
                    "as" => '@epic',
                ),
            );
        } catch (Exception $e) {
            DB::rollBack();
            $result = array(
                "status" => 400,
                "message" => "Simpan Gagal",
                "result"  => $e->getMessage() . ' ' . $e->getLine()

            );
        }
        return $this->respond($result['result'], $result['status'], $result['message']);
    }
    public function deletePegawai(Request $r)
    {
        DB::beginTransaction();
        try {

            $dataPS = Pegawai::where('id', $r['id'])
                ->update([
                    'statusenabled' => false
                ]);

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
                    "data"  => $dataPS,
                    "as" => '@epic',
                ),
            );
        } else {
            $transMessage = "Hapus Gagal";
            DB::rollBack();

            $result = array(
                "status" => 400,
                "result"  => null

            );
        }
        return $this->respond($result['result'], $result['status'], $transMessage);
    }
    public function masterPegawaidropdown(Request $r)
    {
        $res['jeniskelamin'] = JenisKelamin::mine()->get();
        $res['agama'] = Agama::mine()->get();
        $res['detailkategorypegawai'] = DetailKategoryPegawai::mine()->get();
        $res['jenispegawai'] = JenisPegawai::mine()->get();
        $res['namanegara'] = Negara::mine()->get();
        $res['suku'] = Suku::mine()->get();
        $res['namadepartemen'] = Departemen::mine()->get();
        $res['pendidikan'] = Pendidikan::mine()->get();
        $res['statusperkawinan'] = StatusPerkawinan::mine()->get();
        $res['typepegawai'] = TypePegawai::mine()->get();
        $res['statuspegawai'] = StatusPegawai::mine()->get();
        $res['namakelompokjabatan'] = KelompokJabatan::mine()->get();
        $res['kedudukan'] = KedudukanPegawai::mine()->get();
        $res['name'] = GolonganPegawai::mine()->get();
        $res['namajabatan'] = Jabatan::mine()->get();
        $res['eselon'] = Eselon::mine()->get();
        $res['kelompokshiftkerja'] = KelompokShiftKerja::mine()->get();
        $res['hubungankeluarga'] = HubunganKeluarga::mine()->get();
        $res['pekerjaan'] = Pekerjaan::mine()->get();


        return $this->respond($res);
    }
    public function pegawaiByID(Request $r)
    {
        $data  = DB::table('pegawai_m as pg')
            ->leftjoin('agama_m as ag', 'pg.objectagamafk', '=', 'ag.id')
            ->leftjoin('jeniskelamin_m as jk', 'pg.objectjeniskelaminfk', '=', 'jk.id')
            ->leftjoin('detailkategorypegawai_m as dkp', 'pg.objectdetailkategorypegawaifk', '=', 'dkp.id')
            ->leftjoin('jenispegawai_m as jp', 'pg.objectjenispegawaifk', '=', 'jp.id')
            ->leftjoin('negara_m as ne', 'pg.objectnegarafk', '=', 'ne.id')
            ->leftjoin('pendidikan_m as pe', 'pg.objectpendidikanterakhirfk', '=', 'pe.id')
            ->leftjoin('statuspegawai_m as sp', 'pg.objectstatuspegawaifk', '=', 'sp.id')
            ->leftjoin('statusperkawinan_m as spk', 'pg.objectstatusperkawinanfk', '=', 'spk.id')
            ->leftjoin('suku_m as su', 'pg.objectsukufk', '=', 'su.id')
            ->leftjoin('typepegawai_m as tp', 'pg.objecttypepegawaifk', '=', 'tp.id')
            ->leftjoin('kelompokjabatan_m as kj', 'pg.objectkelompokjabatanfk', '=', 'kj.id')
            ->select(
                'pg.id',
                'ag.agama',
                'pg.objectagamafk',
                'pg.objectdetailkategorypegawaifk',
                'pg.objectjenispegawaifk',
                'pg.objectunitkerjapegawaifk',
                'pg.objectnegarafk',
                'sp.statuspegawai',
                'pg.objectstatuspegawaifk',
                'pe.pendidikan',
                'pg.objectpendidikanterakhirfk',
                'spk.statusperkawinan',
                'ne.namanegara',
                'pg.objectnegarafk',
                'su.suku',
                'pg.objectsukufk',
                'kj.namakelompokjabatan',
                'tp.typepegawai',
                // 'pg.objecttypegawaifk',
                'jk.jeniskelamin',
                'pg.objectjeniskelaminfk',
                'jp.jenispegawai',
                'pg.namalengkap',
                'pg.kodepos',
                'pg.statusenabled',
                'pg.statusenabled',
                'pg.bankrekeningatasnama',
                'pg.bankrekeningnama',
                'pg.bankrekeningnomor',
                'pg.fingerprintid',
                'pg.namalengkap',
                'pg.namapanggilan',
                'pg.nik_intern',
                'pg.nip_pns',
                'pg.noidentitas',
                'pg.npwp',
                'pg.photodiri',
                'pg.qpegawai',
                'pg.qtyanak',
                'pg.qtytanggungan',
                'pg.qtytotaljiwa',
                'pg.tempatlahir',
                'pg.email',
                'pg.nohandphone',
                'pg.notlp',
                'pg.nobpjs',
                'pg.alamat',
                'pg.tgllahir',
                'pg.tglmasuk',
                'pg.tglkeluar',
                'pg.nosip',
                'pg.nostr',
                'pg.tglberakhirsip',
                'pg.tglberakhirstr',
                'pg.tglterbitsip',
                'pg.tglterbitstr',
                'dkp.detailkategorypegawai',
                'pg.objectdetailkategorypegawaifk',
                'pg.nip'

            )
            ->where('pg.kdprofile', (int)$this->kdProfile)
            ->where('pg.statusenabled', true)
            ->where('pg.id', $r['id'])
            ->first();

        $result = array(
            'pegawai' => $data,
            'as' => '@epic',
        );
        return $this->respond($result);
    }
    public function masterPegawaiSatset(Request $r)
    {
        $data  = DB::table('pegawai_m as pg')
            ->join('jenispegawai_m as dp', 'pg.objectjenispegawaifk', '=', 'dp.id')
            ->select(
                'pg.id',
                'pg.namalengkap',
                'dp.jenispegawai',
                'pg.ihs_id',
                'pg.noidentitas'
            )
            ->where('pg.kdprofile', $this->kdProfile)
            ->where('pg.statusenabled', true);


        if (isset($r['ihs_id']) && $r['ihs_id'] != '') {
            $data = $data->whereNotNull('pg.ihs_id');
        }
        $data = $data->orderByRaw('pg.namalengkap,dp.jenispegawai');
        $data = $data->get();

        $res['data'] = $data;
        return $this->respond($res);
    }
    public function updatePegawai(Request $r)
    {
        DB::beginTransaction();
        try {
            $dataPS = Pegawai::where('id', $r['id'])->update([
                'ihs_id' => $r['ihs_id'],
                'noidentitas' => $r['noidentitas']
            ]);

            DB::commit();
            $result = [
                'status' => 201,
                'message' => 'Sukses',
                'result' => $dataPS,
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            $result = [
                'status' => 400,
                'message' => "Simpan Gagal !",
                'result' => $e->getMessage(),
            ];
        }
        return $this->respond($result['result'], $result['status'], $result['message']);
    }

    public function jadwalKerja(Request $r)
    {
        $data  = DB::table('jadwaldokter_m as jd')
            ->join('pegawai_m as pg', 'pg.id', 'jd.objectpegawaifk')
            ->join('ruangan_m as ru', 'ru.id', 'jd.objectruanganfk')
            ->select(
                'ru.namaruangan',
                'jd.hari',
                'jd.jammulai',
                'jd.jamakhir',
                'pg.namalengkap'
            )
            // ->where('pg.id', $r['id'])
            ->where('pg.kdprofile', $this->kdProfile)
            ->where('jd.statusenabled', true);

        if (isset($r['idpegawai']) && $r['idpegawai'] != '') {
            $data = $data->where('pg.id', '=',  $r['idpegawai']);
        }

        $data = $data->get();

        $login  = DB::table('loginuser_s as ru')
            ->join('kelompokuser_s as dp', 'ru.objectkelompokuserfk', '=', 'dp.id')
            ->join('pegawai_m as pg', 'ru.objectpegawaifk', '=', 'pg.id')
            ->select(
                'ru.id',
                'ru.namauser',
                'dp.kelompokuser',
                'pg.namalengkap',
            )
            ->where('ru.kdprofile', $this->kdProfile)
            ->where('ru.statusenabled', true);

        if (isset($r['idpegawai']) && $r['idpegawai'] != '') {
            $login = $login->where('pg.id', '=',  $r['idpegawai']);
        }
        $login = $login->get();

        $res['data'] = $data;
        $res['login'] = $login;

        return $this->respond($res);
    }
}
