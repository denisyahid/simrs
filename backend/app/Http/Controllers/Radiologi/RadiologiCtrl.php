<?php

namespace App\Http\Controllers\Radiologi;

use App\Http\Controllers\Controller;
use App\Http\Controllers\EMR\EMRCtrl;
use App\Models\Master\Pegawai;
use App\Models\Transaksi\AntrianPasienDiperiksa;
use App\Models\Transaksi\HasilRadiologi;
use App\Models\Transaksi\PasienDaftar;
use App\Models\Transaksi\PelayananPasien;
use App\Models\Transaksi\PelayananPasienDetail;
use App\Models\Transaksi\PelayananPasienPetugas;
use App\Models\Transaksi\ExpertiseDraft;
use App\Models\Transaksi\StrukOrder;
use App\Traits\Valet;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Exception;
use setasign\Fpdi\Fpdi;
use FPDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Webklex\PDFMerger\Facades\PDFMergerFacade as PDFMerger;

class RadiologiCtrl extends Controller
{
    use Valet;
    public function __construct()
    {
        parent::__construct($is_encrypt = true);
    }

    public function LayananRad(Request $r)
    {
        $kdProfile = (int)$this->kdProfile;
        $sDokterPemeriksa = $this->settingFix('jenisPetugasDokterPemeriksa');
        $pd = PasienDaftar::where('kdprofile', $kdProfile)
            ->where('norec', $r['norec_pd'])
            ->first();
        $data = DB::table('pelayananpasien_t as pp')
            ->leftJoin('hasilradiologi_t as hr', 'hr.pelayananpasienfk', 'pp.norec')
            ->join('antrianpasiendiperiksa_t as apd', 'apd.norec', '=', 'pp.noregistrasifk')
            ->join('kelas_m as kls', 'kls.id', '=', 'apd.objectkelasfk')
            ->join('produk_m as prd', 'prd.id', '=', 'pp.produkfk')
            ->join('ruangan_m as ru', 'ru.id', '=', 'apd.objectruanganfk')
            ->leftJOIN('strukpelayanan_t as sp', 'sp.norec', '=', 'pp.strukfk')
            ->leftJOIN('strukresep_t as sr', 'sr.norec', '=', 'pp.strukresepfk')
            ->leftJOIN('pegawai_m as pg', 'pg.id', '=', 'sr.penulisresepfk')
            ->leftJoin('pegawai_m as pg2', 'pg2.id', '=', DB::raw('CAST(pp.pelayananpegawaifk AS INT)'))
            ->leftJOIN('strukorder_t as so', 'so.norec', '=', 'pp.strukorderfk')
            ->leftJOIN(
                'ris_order as ris',
                'ris.order_no',
                '=',
                DB::raw('so.noorder AND ris.order_code=cast(pp.produkfk as text)')
            )
            ->select(
                'pp.norec',
                'apd.norec as norec_apd',
                'prd.namaproduk',
                'hr.norec as norec_hr',
                'kls.namakelas',
                'pp.tglpelayanan',
                'ru.namaruangan',
                'pp.strukresepfk',
                'pp.jumlah',
                'pp.hargasatuan',
                'pg.namalengkap',
                'pg2.namalengkap as dokterpelaksana',
                'apd.norec as norec_apd',
                'so.noorder',
                'pp.strukfk',
                'pp.produkfk',
                'prd.sanata_jasa_id',
                'ru.objectdepartemenfk',
                'ris.order_key as idbridging',
                'ris.order_complete',
                'ris.description',
                'ris.report_date',
                'ris.link',
                'ris.accession_num',
                'ris.charge_doc_id',
                'ris.charge_doc_name',
                'so.norec as norec_so',
                'so.objectruangantujuanfk',
                'hr.keterangan as hasil',
                DB::raw("  ris.patient_id || '-' || ris.order_cnt as radiologiid,
                case when pp.jasa is not null then pp.jasa else 0 end jasa,
                case when pp.hargadiscount is not null then pp.hargadiscount else 0 end hargadiscount,
                (
                    (pp.hargasatuan  - case when pp.hargadiscount is null then 0 else pp.hargadiscount end)
                     * pp.jumlah)
                + (case when pp.jasa is not null then pp.jasa else 0 end)
                 as total,
                to_char(pp.tglpelayanan,'yyyy-MM-dd')  as tglpelayanan_group ,
                case when pp.strukresepfk is null then 'Layanan' else 'Resep' end as jenis
               ")
            )
            ->where('pp.statusenabled', true)
            ->whereNull('pp.strukresepfk')
            ->where('pp.kdprofile', $kdProfile)
            ->where('ru.objectdepartemenfk', $this->settingFix('idDepartemenRadiologi'))
            ->where('apd.noregistrasifk', $r['norec_pd']);
        if (isset($r['strukfk']) && $r['strukfk'] != '' && $r['strukfk'] == null) {
            $data = $data->whereNull('pp.strukfk');
        }
        $data = $data->orderByDesc('pp.tglpelayanan');
        $data = $data->get();

        // $pacs = DB::connection('sqlsrv_ris')
        // ->table('ris_in as ri')
        // ->join('ris_out as ro', 'ri.no_rontgen', '=', 'ri.no_rontgen')
        // ->where('ri.no_register', '=', $pd->noregistrasi)
        // ->get('ro.*', 'ri.nobukti', 'ri.kode_pemeriksaan');

        $pelayananpetugas = DB::table('pelayananpasienpetugas_t as ptu')
            ->leftjoin('pegawai_m as pg', 'pg.id', '=', 'ptu.objectpegawaifk')
            ->select('ptu.pelayananpasien', 'pg.namalengkap', 'pg.id')
            ->where('ptu.kdprofile', $kdProfile)
            ->where('ptu.objectjenispetugaspefk', $sDokterPemeriksa)
            ->where('ptu.noregistrasi', $pd->noregistrasi)
            ->get();


        $result['total'] = 0;
        $result['deposit'] = 0;
        $result['diskon'] = 0;
        $result['dibayar'] = 0;
        $result['sisa'] = 0;

        $sama = false;
        $group  = [];
        $urlPACSHasil = $this->settingFix('urlPACSHasil');
        foreach ($data as $item) {
            $item->checked = false;
            $item->url_pacs_hasil = null;
            if ($item->order_complete == 1) {
                $exp = explode(',', $urlPACSHasil);
                $exp[0] = $exp[0] . $item->accession_num;
                $exp[1] = $exp[1] . $item->accession_num;
                $urlPACSHasil = implode(",", $exp);
                $item->url_pacs_hasil = $urlPACSHasil;
            }

            // foreach($pacs as $pc){
            //     if($pc->nobukti == $item->nobukti && $pc->kode_pemeriksaan == $item->produkfk){
            //         $item->url_pacs_hasil = $pc->urllink4;
            //     }
            // }

            $result['total']  = $result['total']  + (float) $item->total;
            $result['diskon']  = $result['diskon']  + (float) $item->hargadiscount;
            foreach ($pelayananpetugas as $itemd) {
                if ($itemd->pelayananpasien == $item->norec) {
                    $item->dokterpemeriksa = $itemd->namalengkap;
                    $item->pegawaifk = $itemd->id;
                }
            }
            $sama = false;
            $i = 0;
            foreach ($group as $itemg) {
                if ($item->tglpelayanan_group == $group[$i]['tglpelayanan_group']) {
                    $sama = true;
                }
                $i = $i + 1;
            }
            if ($sama == false) {
                $group[] = array(
                    'tglpelayanan_group' => $item->tglpelayanan_group,
                    'details' => []
                );
            }
        }
        foreach ($group as $k => $d) {
            foreach ($data as $d2) {
                if ($d['tglpelayanan_group'] == $d2->tglpelayanan_group) {
                    $group[$k]['details'][] = $d2;
                }
            }
        }


        $result['length'] = count($data);
        $result['detail'] = $group; //collect($data)->groupBy('tglpelayanan_group')->sortByDesc('tglpelayanan_group');
        $result['as'] = '@epic';

        return $this->respond($result);
    }

    public function HasilPacs(Request $r)
    {
        $kdProfile = (int)$this->kdProfile;

        $exp=isset($r['idproduk']) ? explode(',',$r['idproduk']):[];

        $pacs = DB::connection('sqlsrv_ris')
        ->table('ris_in as ri')
        ->join('ris_out as ro', 'ri.no_rontgen', '=', 'ro.no_rontgen')
        ->where('ri.nobukti', '=', $r['noorder'])
        ->select('ro.*', 'ri.nobukti', 'ri.kode_pemeriksaan');
        if (!empty($exp)) {
            $pacs = $pacs->whereIn('ri.kode_pemeriksaan', $exp);
        } 
        else{
            $pacs=$pacs->where('ri.kode_pemeriksaan', '=', $r['idproduk']);
        }
        $pacs=$pacs->get();

        if ($pacs->isEmpty()) {
            $url=array(
                'url' => route('notfound')
            );
            return $this->respond($url);
        }

        if(!empty($pacs)){
            $path=$pacs[0]->urllink3;
        };
        $path_file=str_replace('http://10.60.1.27:8080/',"http://103.143.22.46:8080/",$path);
        //103.143.22.46:8080


        $result['data'] = isset($pacs) ? $pacs : null;
        $result['testurl'] = isset($path_file) ? $path_file : null;

        return $this->respond($result);

    }

    public function hapusTindakanRad(Request $r)
    {
        DB::beginTransaction();
        try {
            foreach ($r['data'] as $item) {
                PelayananPasienDetail::where('pelayananpasien', $item['norec_pp'])->where('kdprofile', $this->kdProfile)->delete();
                PelayananPasienPetugas::where('pelayananpasien', $item['norec_pp'])->where('kdprofile', $this->kdProfile)->delete();
                PelayananPasien::where('norec', $item['norec_pp'])->where('kdprofile', $this->kdProfile)->delete();
                StrukOrder::where('noorder', $item['noorder'])
                    ->where('kdprofile', $this->kdProfile)
                    ->update(
                        [
                            'statusorder' => 0,
                        ]
                    );

                $this->LOGGING(
                    'Hapus Tindakan',
                    $item['norec_pp'],
                    'pelayananpasien_t',
                    'Hapus Tindakan ' . $item['namaproduk'] . ' di ' . $item['namaruangan'] . ' pada Pasien ' .
                        $r['namapasien'] . ' (' . $r['nocm'] . ') - ' . $r['noregistrasi']
                );
            }


            $transStatus = true;
        } catch (\Exception $e) {
            $transStatus = false;
        }

        if ($transStatus) {
            $transMessage = "Sukses";
            DB::commit();
            $result = array(
                "status" => 200,
                "result" => array(
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

    public function hapusExpertise(Request $r)
    {
        DB::beginTransaction();
        try {
            foreach ($r['data'] as $item) {

                HasilRadiologi::where('norec', $item['norec_hr'])->where('kdprofile', $this->kdProfile)->delete();

                $this->LOGGING(
                    'Hapus Expertise',
                    $item['norec_hr'],
                    'hasilradiologi_t',
                    'Hapus Expertise ' . ' di ' . ' pada Pasien ' .
                        $r['namapasien'] . ' (' . $r['nocm'] . ') - ' . $r['noregistrasi']
                );
            }

            $countOrder = DB::table('pelayananpasien_t as pp')
                ->join('antrianpasiendiperiksa_t as apd', 'pp.noregistrasifk', 'apd.norec')
                ->leftjoin('ruangan_m as ru', 'ru.id', 'apd.objectruanganfk')
                ->where('pp.kdprofile', $this->kdProfile)
                ->where('pp.statusenabled', true)
                ->where('pp.noregistrasifk', $r['noregistrasifk'])
                ->where('ru.objectdepartemenfk', $this->settingFix('idDepartemenRadiologi'))
                ->count();

            $countHasilRad = HasilRadiologi::where('noregistrasifk', $r['noregistrasifk'])->where('kdprofile', $this->kdProfile)->where('statusenabled', true)->count();
            if ($countOrder > $countHasilRad) {
                AntrianPasienDiperiksa::where('kdprofile', $this->kdProfile)->where('statusenabled', true)->where('norec', $r['noregistrasifk'])->update(['isExpertise' => null]);
            }

            $transStatus = true;
        } catch (\Exception $e) {
            $transStatus = false;
        }

        if ($transStatus) {
            $transMessage = "Sukses";
            DB::commit();
            $result = array(
                "status" => 200,
                "result" => array(
                    "as" => '@epic',
                ),
            );
        } else {
            $transMessage = "Hapus Gagal";
            DB::rollBack();
            $result = array(
                "status" => 400,
                "result"  => $e->getMessage() . $e->getLine()
            );
        }
        return $this->respond($result['result'], $result['status'], $transMessage);
    }

    public function detailPetugasRad(Request $r)
    {
        $kdProfile = $this->kdProfile;
        $result = DB::table('pelayananpasienpetugas_t as pp')
            ->join('jenispetugaspelaksana_m as jp', 'jp.id', '=', 'pp.objectjenispetugaspefk')
            ->join('pegawai_m as pg', 'pg.id', '=', 'pp.objectpegawaifk')
            ->select(
                'pp.norec',
                'pg.namalengkap',
                'jp.jenispetugaspe',
                'pp.objectpegawaifk',
                'pp.objectjenispetugaspefk',
                'pp.nomasukfk',
                'pp.pelayananpasien'
            )
            ->where('pp.pelayananpasien', $r['norec'])
            ->where('pp.statusenabled', true)
            ->where('pp.kdprofile', $kdProfile)
            ->get();

        return $this->respond($result);
    }

    public function savePetugasRad(Request $r)
    {
        DB::beginTransaction();
        try {
            if ($r['norec'] == '') {
                $log = 'Input ';
                $new_PPP = new PelayananPasienPetugas();
                $new_PPP->norec = $new_PPP->generateNewId();
                $new_PPP->kdprofile = $this->kdProfile;
                $new_PPP->statusenabled = true;
            } else {
                $new_PPP = PelayananPasienPetugas::where('norec', $r['norec'])->first();
                $log = 'Ubah ';
            }

            $new_PPP->nomasukfk = $r['nomasukfk'];
            $new_PPP->objectjenispetugaspefk = $r['objectjenispetugaspefk'];
            $new_PPP->objectpegawaifk = $r['objectpegawaifk'];
            $new_PPP->pelayananpasien = $r['pelayananpasien'];
            $new_PPP->noregistrasi = $r['noregistrasi'];
            $new_PPP->save();

            $pg = Pegawai::where('id', $r['objectpegawaifk'])->first();
            $ps = PasienDaftar::detailPasien($r['noregistrasi']);

            $this->LOGGING(
                $log . 'Petugas Tindakan',
                $new_PPP->norec,
                'pelayananpasienpetugas_t',
                $log . 'Petugas Tindakan ' . $pg->namalengkap . ' pelayanan ' . $r['namaproduk'] . ' di ' . $r['namaruangan'] . ' pada Pasien ' .
                    $r['noregistrasi']
            );
            $transStatus = true;
        } catch (\Exception $e) {
            $transStatus = false;
        }

        if ($transStatus) {
            $transMessage = "Sukses";
            DB::commit();
            $result = array(
                "status" => 200,
                "result" => array(
                    "as" => '@epic',
                ),
            );
        } else {
            $transMessage = "Simpan Gagal";
            DB::rollBack();
            $result = array(
                "status" => 400,
                "result"  => $e->getMessage() . $e->getLine()
            );
        }
        return $this->respond($result['result'], $result['status'], $transMessage);
    }
    public function deletePetugasRad(Request $r)
    {
        DB::beginTransaction();
        try {

            PelayananPasienPetugas::where('norec', $r['norec'])->delete();

            $ps = PasienDaftar::detailPasien($r['noregistrasi']);
            $pg =  Pegawai::where('id', $r['objectpegawaifk'])->first();
            $this->LOGGING(
                'Hapus Petugas Tindakan',
                $r['norec'],
                'pelayananpasienpetugas_t',
                'Hapus Petugas Tindakan ' . $pg->namalengkap . ' pelayanan ' . $r['namaproduk'] . ' di ' .  ' pada Pasien ' .
                    $r['noregistrasi']
            );
            $transStatus = true;
        } catch (\Exception $e) {
            $transStatus = false;
        }

        if ($transStatus) {
            $transMessage = "Sukses";
            DB::commit();
            $result = array(
                "status" => 200,
                "result" => array(
                    "as" => '@epic',
                ),
            );
        } else {
            $transMessage = "Simpan Gagal";
            DB::rollBack();
            $result = array(
                "status" => 400,
                "result"  => $e->getMessage() . $e->getLine()
            );
        }
        return $this->respond($result['result'], $result['status'], $transMessage);
    }

    public function saveExpertise(Request $r)
    {
        DB::beginTransaction();

        try {
            $hh = $r['hasil'];
            $kdProfile = $this->kdProfile;
            $norec_hh=null;
            
            $pasien = DB::table('pasien_m as ps')
                ->select('pd.noregistrasi as noregistrasi' ,'ps.namapasien','ps.nocm')
                ->join('pasiendaftar_t as pd' ,'pd.nocmfk' ,'ps.id')
                ->where('pd.norec' ,$r['norec_pd'] )
                ->first();

            $pelayananpasien = DB::table('pelayananpasien_t as pp')
                ->select(
                    'pp.norec',
                    'pts.objectpegawaifk'    
                )
                ->leftjoin('pelayananpasienpetugas_t as pts','pts.pelayananpasien','=','pp.norec')
                ->where('pp.strukorderfk',$r['norec_so'])->where('pp.produkfk',$r['produkfk'])
                ->first();

            if (!empty($hh['filePasien'])){
                $uploadBerkasPasien = $r->file('filePasien');
                $path = 'berkaspasien/' . $r->nocm;
            }

            $pegawai_fk = $hh['pegawaifk'] ?? $pelayananpasien->objectpegawaifk;
            $noregisfk = $hh['noregistrasifk'] ?? $r['norec_pd'];
            $dokter = Pegawai::select('namalengkap')->where('id',$pegawai_fk)->first();
            $datapp = !empty($hh['pelayananpasienfk']) ? $hh['pelayananpasienfk'] : $pelayananpasien->norec;

            $cek = DB::table('hasilradiologi_t as hr')
                ->join('pelayananpasien_t as pp', 'pp.norec', 'hr.pelayananpasienfk')
                ->join('pegawai_m as pg', 'pg.id', 'hr.pegawaifk')
                ->select(
                    'hr.tanggal',
                    'hr.norec',
                    'pg.namalengkap',
                    'pp.produkfk',
                    'hr.keterangan',
                    'hr.pelayananpasienfk',
                    'hr.pegawaifk'
                )
                ->where('hr.pelayananpasienfk', $datapp)
                ->where('hr.statusenabled', 'true')
                ->where('hr.kdprofile', $kdProfile)
                ->first();

            if (!empty($cek)) {
                $typeLog = "Update";
                $message = 'Update Berhasil';
                $norec_hh = $cek->norec;

                HasilRadiologi::where('norec', $r['norec'])->update([
                    'keterangan' => $hh['keterangan'],
                    'pegawaifk' => $pegawai_fk,
                    'tanggal' =>  !empty($hh['tanggal']) ? $hh['tanggal'] : date('Y-m-d H:i:s'),
                    'tanggalreport' =>  !empty($hh['tanggalreport']) ? $hh['tanggalreport'] : date('Y-m-d H:i:s'),
                ]);

                //? Save berkas
                if (!empty($uploadBerkasPasien)) {
                    $extension = $uploadBerkasPasien->getClientOriginalExtension();
                    $filename = 'Foto In Vivo_' . date('YmdHis') . '.' . $extension;
                    HasilRadiologi::where('norec', $r['norec'])->update(['file' => $path . '/' . $filename]);
                }
            } else {
                $typeLog = "Tambah";
                $message = 'Simpan Berhasil';
                $h = new HasilRadiologi();
                $h->norec = $h->generateNewId();
                $h->kdprofile = $kdProfile;
                $h->statusenabled = true;
                $h->tanggal = $hh['tanggal'] ?? date('Y-m-d H:i:s');
                $h->tanggalreport = $hh['tanggalreport'] ?? date('Y-m-d H:i:s');
                $h->pegawaifk = $pegawai_fk;
                $h->keterangan = $hh['keterangan'];
                $h->pelayananpasienfk = $datapp;
                $h->noregistrasifk = $noregisfk;

                if (!empty($uploadBerkasPasien)) {
                    $extension = $uploadBerkasPasien->getClientOriginalExtension();
                    $filename = 'Foto In Vivo_' . date('YmdHis') . '.' . $extension;
                    $h->file = $path . '/' . $filename;
                }

                $h->save();
                $norec_hh=$h->norec;
            }

            $dataSO = StrukOrder::where('norec', $r['norec_so'])->where('kdprofile', $this->kdProfile)->first();
            $dataSO->tglexpertise = $hh['tanggalreport'] ?? date('Y-m-d H:i:s');
            $dataSO->catatanklinis = $hh['catatanklinis'];
            $dataSO->save();

            $countOrder = PelayananPasien::where('kdprofile', $this->kdProfile)->where('statusenabled', true)->where('strukorderfk', $r['norec_so'])->count();
            $countHasilRad = HasilRadiologi::where('noregistrasifk', $noregisfk)->where('kdprofile', $this->kdProfile)->where('statusenabled', true)->count();

            if ($countOrder == $countHasilRad) {
                AntrianPasienDiperiksa::where('kdprofile', $this->kdProfile)->where('statusenabled', true)->where('norec', $noregisfk)->update(['isExpertise' => true]);
            }

            PelayananPasienPetugas::where('pelayananpasien', $datapp)
                ->where('objectjenispetugaspefk', $this->settingFix('jenisPetugasDokterPemeriksa'))
                ->update([
                    'objectpegawaifk' => !empty($hh['pegawaifk']) ? $hh['pegawaifk'] : $pelayananpasien->objectpegawaifk  
                ]);

            ExpertiseDraft::where('norec_so_fk', $r['norec_so'])->update(['statusenabled'=>false]);

            //? Nyalakan jika ingin hemat penyimpanan
            // ExpertiseDraft::where('norec_so_fk', $r['norec_so'])->delete();

            $this->LOGGING(
                $typeLog . 'Expertise' . "Radiologi",
                $dataSO->norec,
                'strukorder_t',
                $typeLog . 'Expertise ' . 'Radiologi' . 'did rungan ' . $dataSO->namaruangan . ' pada Pasien ' .
                $pasien->namapasien  .' ' .'no RM :'.  $pasien->nocm  .' ' .'no Registrasi :'.  $pasien->noregistrasi .' '
                .'dengan no order :'.  $dataSO->noorder ,'dari dokter' . $dokter->namalengkap
            );

            DB::commit();
            $objetoRequest = new \Illuminate\Http\Request();
            $objetoRequest['noorder'] = $dataSO->noorder;
            $ihs = app('App\Http\Controllers\Bridging\SATUSEHATCtrl')->ServiceRequest($objetoRequest, true);
            $result = array(
                "status" => 200,
                "result" => 'sukses',
                "message" => $message,
                "ObservationRad" => $ihs,
                'data' => '',
                'norec_hh'=> $norec_hh
            );
        } catch (\Exception $e) {
            DB::rollBack();
            $result = array(
                "status" => 400,
                "message" => "Simpan Gagal !",
                "result"  => $e->getMessage() . ' ' . $e->getLine()
            );
        }
        return $this->respond($result, $result['status'], $result['message']);
    }

    public function saveExpertiseBerkas(Request $r)
    {
        DB::beginTransaction();

        try {
            $kdProfile = $this->kdProfile;
            if($r->file('filePasien')){
                $uploadBerkasPasien = $r->file('filePasien');
                $path = 'berkaspasien/' . $r['nocm'];
            }
            $cek = DB::table('hasilradiologi_t as hr')
                ->join('pelayananpasien_t as pp', 'pp.norec', 'hr.pelayananpasienfk')
                ->join('pegawai_m as pg', 'pg.id', 'hr.pegawaifk')
                ->select(
                    'hr.tanggal',
                    'hr.norec',
                    'pg.namalengkap',
                    'pp.produkfk',
                    'hr.keterangan',
                    'hr.pelayananpasienfk',
                    'hr.pegawaifk'
                )
                ->where('hr.norec', $r['norec'])
                ->where('hr.statusenabled', 'true')
                ->where('hr.kdprofile', $kdProfile)
                ->first();
            if (!empty($cek)) {

                if (!empty($uploadBerkasPasien)) {
                    $extension = $uploadBerkasPasien->getClientOriginalExtension();
                    $filename = 'Foto In Vivo_' . date('YmdHis') . '.' . $extension;

                    HasilRadiologi::where('norec', $r['norec'])->update([
                        'file' => $path . '/' . $filename
                    ]);
                }
                // update()
            } else {
                $typeLog = "Tambah";
                $message = 'Simpan Berhasil';
                $h = new HasilRadiologi();


                if (!empty($uploadBerkasPasien)) {
                    $extension = $uploadBerkasPasien->getClientOriginalExtension();
                    $filename = 'Foto In Vivo_' . date('YmdHis') . '.' . $extension;

                    $h->file = $path . '/' . $filename;
                }
                $h->save();
            }


            DB::commit();
            $objetoRequest = new \Illuminate\Http\Request();
            $result = array(
                "status" => 200,
                "result" => 'sukses',
                "message" => "Simpan Berhasil !",
                'data' => ''
            );
        } catch (\Exception $e) {
            DB::rollBack();
            $result = array(
                "status" => 400,
                "message" => "Simpan Gagal !",
                "result"  => $e->getMessage() . ' ' . $e->getLine()
            );
        }
        return $this->respond($result, $result['status'], $result['message']);
    }

    public function cetakLayananRadiologi(Request $request)
    {
        $kdProfile = $this->kdProfile;
        $KodeJasaMedis = $this->settingFix('KdKomponenTarifJasaDokter');
        $noregistrasi = $request['noregistrasi'];
        $user = $request['user'];
        $norecPp = '';
        $datNorec = explode('|', $request['norec']);
        foreach ($datNorec as $ob) {
            $norecPp = $norecPp . ",'" . $ob . "'";
        }
        $norecPp = substr($norecPp, 1, strlen($norecPp) - 1);
        $paramsPp = "";
        if ($norecPp != '') {
            $paramsPp = " AND tp.norec IN (" . $norecPp . ")";
        }
        if ($request['so_norec']) {
            $paramsPp = "AND tp.strukorderfk = '" . $request['so_norec'] . "'";
        }
        $profile = $this->profile();

        $data =  $this->indentitasCetak($kdProfile, $noregistrasi);
        // dd($data);
        // $so_norec = "5d16e070-5fa8-43a4-aeae-7d2641765631";
        $sDokterPemeriksa = $this->settingFix('jenisPetugasDokterPemeriksa');
        $details = collect(DB::select("
            SELECT x.tglpelayanan,x.namadokter,x.namaproduk,x.jumlah,x.hargasatuan,x.diskon,x.jasa,x.catatanklinis,(x.jumlah * (x.hargasatuan - x.diskon)) + x.jasa AS total
            FROM ( SELECT tp.tglpelayanan,(select pg.namalengkap from pegawai_m as pg INNER JOIN pelayananpasienpetugas_t p3 on p3.objectpegawaifk = pg.id
                   WHERE p3.pelayananpasien = tp.norec AND p3.objectjenispetugaspefk = $sDokterPemeriksa limit 1) AS namadokter,tp.produkfk,pro.namaproduk,tp.jumlah,
                   tp.hargajual as hargasatuan,CASE WHEN tp.hargadiscount IS NULL THEN 0 ELSE tp.hargadiscount END AS diskon, so.catatanklinis,
                   CASE WHEN tp.jasa IS NULL THEN 0 ELSE tp.jasa END AS jasa
            FROM pelayananpasien_t AS tp
            LEFT JOIN produk_m AS pro ON tp.produkfk = pro.id
            INNER JOIN antrianpasiendiperiksa_t AS apdp ON apdp.norec = tp.noregistrasifk
            INNER JOIN ruangan_m AS ru ON apdp.objectruanganfk = ru.id
            LEFT JOIN pegawai_m AS pp ON apdp.objectpegawaifk = pp.id
            left join strukorder_t so on so.norec = tp.strukorderfk
            WHERE tp.kdprofile = $kdProfile
            AND tp.statusenabled = true
            $paramsPp
            ) AS x
            ORDER BY x.tglpelayanan
            "));

        $pageWidth = 950;

        $dataReport = array(
            'namaprofile' => $profile->namalengkap,
            'alamat' => $profile->alamatlengkap,
            'user' => $user,
            'judul' => "BUKTI LAYANAN RADIOLOGI",
            'header' => $data,
            'details' =>  $details,
        );
        // dd($dataReport);
        return view(
            'report.radiologi.bukti-layanan-rad',
            compact('dataReport', 'pageWidth', 'profile')
        );
    }
    function indentitasCetak($kdProfile, $noregistrasi)
    {
        $data = collect(DB::select("
        SELECT pd.noregistrasi,ps.nocm,ps.tgllahir,to_char(ps.tgllahir, 'DD-MM-YYYY') as tglkelahiran,ps.namapasien,
               pd.tglregistrasi,jk.reportdisplay AS jk,ru2.namaruangan AS ruanganperiksa,ru.namaruangan AS ruangakhir,
               ks.namakelas,ar.asalrujukan,ps.notelepon,CASE WHEN rek.namarekanan is null then '-' else rek.namarekanan END as namapenjamin,
               CASE WHEN kmr.namakamar is null then '-' else kmr.namakamar END as namakamar,alm.alamatlengkap,kp.kelompokpasien,pp.namalengkap AS dpjp
        FROM pasiendaftar_t AS pd
        INNER JOIN pasien_m AS ps ON pd.nocmfk = ps.id
        INNER JOIN jeniskelamin_m AS jk ON ps.objectjeniskelaminfk = jk.id
        INNER JOIN kelompokpasien_m AS kp ON pd.objectkelompokpasienlastfk = kp.id
        INNER JOIN antrianpasiendiperiksa_t AS apdp ON apdp.noregistrasifk = pd.norec
        INNER JOIN ruangan_m AS ru ON apdp.objectruanganfk = ru.id
        LEFT JOIN pegawai_m AS pp ON apdp.objectpegawaifk = pp.id
        LEFT JOIN kelas_m AS ks ON apdp.objectkelasfk = ks.id
        LEFT JOIN asalrujukan_m AS ar ON apdp.objectasalrujukanfk = ar.id
        left JOIN rekanan_m AS rek ON rek.id= pd.objectrekananfk
        left JOIN kamar_m as kmr on apdp.objectkamarfk=kmr.id
        INNER join ruangan_m  as ru2 on ru2.id=apdp.objectruanganfk
        LEFT JOIN alamat_m as alm on alm.nocmfk = ps.id
        WHERE pd.kdprofile = $kdProfile AND pd.statusenabled = true AND pd.noregistrasi = '$noregistrasi'
"))->first();
        return $data;
    }

    public function getExpertise(Request $request)
    {
        $cekDraftExpertise = [];
        if(isset($request['norec_so']) && $request['norec_so'] != ''){
            $cekDraftExpertise = DB::table('expertise_draft as ad')
                ->leftJoin('pegawai_m as pg','pg.id','=','ad.objectpegawaifk')
                ->select(
                    'ad.objectpegawaifk as pegawaifk',
                    'pg.namalengkap',
                    'ad.draft_expertise as keterangan',
                    'ad.tanggal'
                )
                ->where('ad.statusenabled',true)
                ->where('ad.norec_so_fk',$request['norec_so'])
                ->first();
        }

        $data = DB::table('hasilradiologi_t as ar')
            ->leftjoin('pegawai_m as pg', 'pg.id', '=', 'ar.pegawaifk')
            // ->leftJoin('pelayananpasien_t as pp','pp.norec','pp.ar.pelayananpasienfk')
            ->orderBy('tanggal', 'DESC')
            ->select('ar.*', 'pg.namalengkap')
            ->where('ar.kdprofile', $this->kdProfile)
            ->where('ar.statusenabled', true)
            ->where('ar.pelayananpasienfk', $request['norec'])
            ->first();

        $dataFile = DB::table('emrdokumen_t as emrdkt')
            ->select('emrdkt.namafile')
            ->where('emrdkt.norec_pp', $request['norec'])
            ->get();

        $catatanKlinis = [];
        if (isset($request['norec_so']) && $request['norec_so'] != '') {
            $catatan = DB::table('strukorder_t')
                ->select('catatanklinis')
                ->where('norec', $request['norec_so'])
                ->first();
            if ($catatan) {
                $catatanKlinis = $catatan->catatanklinis;
            }
        }    

        $result=[];

        if (!empty($data)) {
            $result = $data;
            $result->expertise_text_only = !empty($data->keterangan) ? strip_tags($data->keterangan) : null;
            $result->dataFile = !empty($dataFile) && count($dataFile) > 0 ? $dataFile : null;
            $result->catatanklinis = $catatanKlinis;
        } else {
            $result = $cekDraftExpertise;
            //? Nyalain aja kalo dipake
            // $result->expertise_text_only = !empty($cekDraftExpertise->keterangan) ? strip_tags($cekDraftExpertise->keterangan) : null;
            $result->dataFile = !empty($dataFile) && count($dataFile) > 0 ? $dataFile : null;
            $result->catatanklinis = $catatanKlinis;
        }
        
        return $this->respond($result);
    }

    public function cetakExpertiseManual(Request $r){

        $kdProfile =  $this->kdProfile;

        $raw = collect(DB::select("SELECT
                so.nofoto,
                ps.nocm, 
                ps.namapasien,
                ps.tgllahir,
                kp.kelompokpasien,
                ru.namaruangan, 
                so.tanggal,
                jk.jeniskelamin, 
                sod.catatanklinis,
                CASE WHEN alm.alamatlengkap IS NULL THEN
                    '-' ELSE (
                    alm.alamatlengkap || ' ' || ds.namadesakelurahan || ' '|| kc.namakecamatan
                    || ' ' || kk.namakotakabupaten || ' '  || pro.namapropinsi )
                END AS alamatlengkap,
                pg.namalengkap as perujuk,
                pg2.namalengkap as dokterrad, 
                -- pg2.ttd as dokterrad_ttd, 
                sod.tglorder,
                ps.alamatlengkap as alamatkedua,
                pr.namaproduk,
                so.keterangan,
                pd.noregistrasi,
                pg2.nippns,
                pg2.nosip as dokterradnosip,
                pg2.id as pgid,  
                pg.nosip as perujukdokternosip, 
                pg.id as pgidperujuk,
                ru.norec as noruangan,
                pd.tglregistrasi as tglregistrasi, 
                sod.terapiradiofarmaka, 
                apd.tglmasuk, 
                apd.tglkeluar, 
                sod.noorder, 
                case when so.tanggal is not null then so.tanggal else sod.tglverif end as tglverif, 
                case when so.tanggalreport is not null then so.tanggalreport else sod.tglexpertise end as tglexpertise,
                TO_CHAR(age(ps.tgllahir), 'YY tahun') as umur, 
                rn.radionuklida, 
                fr.farmaka, 
                sod.catatanfarmaka, 
                sod.catatanklinis
            FROM hasilradiologi_t AS so
            INNER JOIN pelayananpasien_t AS pp ON pp.norec = so.pelayananpasienfk
            INNER JOIN antrianpasiendiperiksa_t AS apd ON apd.norec = pp.noregistrasifk
            left JOIN strukorder_t as sod ON sod.norec_apd = apd.norec
            INNER JOIN pasiendaftar_t AS pd ON pd.norec = apd.noregistrasifk
            INNER JOIN pasien_m AS ps ON ps. ID = pd.nocmfk
            INNER JOIN kelompokpasien_m AS kp ON kp. ID = pd.objectkelompokpasienlastfk
            INNER JOIN ruangan_m AS ru ON ru. ID = pd.objectruanganlastfk
            inner join produk_m as pr on pr.id =pp.produkfk
            LEFT JOIN radionuklida_m rn on rn.id = sod.jenisradionuklidafk
            LEFT JOIN farmaka_m fr on fr.id = sod.jenisfarmakafk
            LEFT JOIN jeniskelamin_m AS jk ON jk. ID = ps.objectjeniskelaminfk
            LEFT JOIN kelompokpasien_m AS kps ON kps. ID = pd.objectkelompokpasienlastfk
            LEFT JOIN alamat_m AS alm ON alm.nocmfk = ps. ID
            left join desakelurahan_m as ds on ds.id=alm.objectdesakelurahanfk
            left join kotakabupaten_m as kk on kk.id=alm.objectkotakabupatenfk
            left join kecamatan_m as kc on kc.id=alm.objectkecamatanfk
            left join propinsi_m as pro on pro.id=alm.objectpropinsifk
            LEFT JOIN pegawai_m AS pg ON pg. ID = pd.objectpegawaifk
            LEFT JOIN pegawai_m AS pg2 ON pg2. ID = so.pegawaifk
            WHERE so.norec ='$r[norec]'
            AND sod.kdprofile = $kdProfile
            AND sod.statusenabled = TRUE
        "))->first();

        $pageWidth = 950;
        $profile = $this->profile();
        $EMRCtrl = new EMRCtrl();
        $requestBerkas = new Request(['norec_hr' => $r['norec']]);
        $responseBerkas = $EMRCtrl->getBerkasPasien($requestBerkas);
        $berkasPasien = $responseBerkas->getData();        
        $berkasPasien = $berkasPasien->response->data; // Extract data
        
        $images = [];
        $pdfFiles = [];
        
        foreach ($berkasPasien as $berkas) {
            // Use Storage::path to correctly locate the file in storage/app/public/
            $filePath = Storage::path('public/' . $berkas->file);

            // dd($filePath);
            if (File::exists($filePath)) {
                if (str_ends_with($berkas->file, '.jpg') || str_ends_with($berkas->file, '.png')) {
                    $images[] = File::get($filePath); // Get image content
                } elseif (str_ends_with($berkas->file, '.pdf')) {
                    $pdfFiles[] = File::get($filePath); // Get PDF content
                }
            } else {
                Log::error("File not found: " . $filePath); // Log missing files
            }
        }
        
        $res['pdf']  =  true;
        $qrcode = base64_encode(QrCode::format('svg')->size(75)->generate($raw->dokterrad));
        $title = isset($r['echo']) ? 'ECHOCARDIOGRAFI' : 'RADIOLOGI';
        $pdf1 = App::make('dompdf.wrapper')->loadView(
            'report.radiologi.expertise-manual',
            [
                'raw' => $raw,
                'profile' => $profile,
                'pageWidth' => $pageWidth,
                // 'dokterrad_ttd' => $raw->dokterrad_ttd,
                'dokterrad_ttd' => null,
                'dataBridDokter' => $raw->dokterrad,
                'res' => $res,
                // 'img' => $img,
                'ttde' => $qrcode,
                'title' => $title,
            ]
        );

        $pdf1Content = $pdf1->output(); // ✅ Get the actual PDF content
        $pdf1Path = tempnam(sys_get_temp_dir(), 'pdf1_') . '.pdf'; // ✅ Create temporary file for pdf1
        File::put($pdf1Path, $pdf1Content); // ✅ Write PDF content to file

        // ✅ Ensure the file is not empty before using it
        if (file_exists($pdf1Path) && filesize($pdf1Path) > 0) {
            $pdfFiles = [$pdf1Path];
        } else {
            throw new Exception("Failed to generate a valid PDF.");
        }

        foreach ($berkasPasien as $berkas) {
            $filePath = storage_path('app/public/' . $berkas->file);
            if (File::exists($filePath)) {
                if (preg_match('/\.(jpg|jpeg|png|gif|bmp|webp|svg|tiff|tif|ico)$/i', $berkas->file)) {
                    list($width, $height) = getimagesize($filePath); // ✅ Get image size
                    // ✅ Generate PDF from Blade View
                    $pdf = Pdf::loadView('report.radiologi.image-pdf', [
                        'imagePath' => $filePath,
                        'imageWidth' => $width,
                        'imageHeight' => $height
                    ]);
                    $tempPdfPath = tempnam(sys_get_temp_dir(), 'img_pdf_') . '.pdf'; // ✅ Save temporary PDF
                    File::put($tempPdfPath, $pdf->output());
                } elseif (str_ends_with($berkas->file, '.pdf')) {
                    // ✅ Instead of using the existing PDF, create a temporary copy
                    $tempPdfPath = tempnam(sys_get_temp_dir(), 'existing_pdf_') . '.pdf';
                    File::copy($filePath, $tempPdfPath);
                }
                // ✅ Ensure the temporary file exists and has content before adding it to the array
                if (isset($tempPdfPath) && file_exists($tempPdfPath) && filesize($tempPdfPath) > 0) {
                    $pdfFiles[] = $tempPdfPath;
                }
            }
            
        }

        // Merge all PDFs
        $pdfMerger = PDFMerger::init();
        foreach ($pdfFiles as $pdf) { $pdfMerger->addPDF($pdf, 'all'); }
        $pdfMerger->merge();

        // Delete temporary files (except original PDFs)
        foreach ($pdfFiles as $pdf) {
            if (!in_array($pdf, array_column($berkasPasien, 'file'))) {
                File::delete($pdf);
            }
        }

        return response($pdfMerger->stream());
    }

    public function cetakEkspertiseEcho(Request $r)
    {


        $kdProfile =  $this->kdProfile;

        // if (isset($r['noregistrasi'])) {
        //     $data = DB::table("hasilradiologi_t as hr")
        //         ->select('hr.norec')
        //         ->join('antrianpasiendiperiksa_t as apd', 'apd.norec', '=', 'hr.noregistrasifk')
        //         ->join('pasiendaftar_t as pd', 'pd.norec', '=', 'apd.noregistrasifk')
        //         ->where('pd.noregistrasi', $r['noregistrasi'])
        //         ->first();

        //     if (!empty($data)) {
        //         $r['norec'] = $data->norec;
        //     } else {
        //         $r['norec'] = null;
        //     }
        // }

                //OLD QUERY DATA 

        // $raw = collect(DB::select("
        //     SELECT
        //         so.nofoto,ps.nocm, ps.namapasien,ps.tgllahir,kp.kelompokpasien,
        //     ru.namaruangan, so.tanggal,jk.jeniskelamin, sod.catatanklinis,
        //     CASE WHEN alm.alamatlengkap IS NULL THEN
        //         '-' ELSE (
        //         alm.alamatlengkap || ' ' || ds.namadesakelurahan || ' '|| kc.namakecamatan
        //         || ' ' || kk.namakotakabupaten || ' '  || pro.namapropinsi )
        //     END AS alamatlengkap,
        //     pg.namalengkap as perujuk,pg2.namalengkap as dokterrad, sod.tglorder,
        //     pr.namaproduk,so.keterangan,pd.noregistrasi,pg2.nippns,pg2.nosip as dokterradnosip,
        //     pg2.id as pgid,  pg.nosip as perujukdokternosip, pg.id as pgidperujuk,
        //     ru.norec as noruangan,pd.tglregistrasi as tglregistrasi, sod.terapiradiofarmaka,
        //     TO_CHAR(age(ps.tgllahir), 'YY tahun') as umur, rn.radionuklida, fr.farmaka, sod.catatanfarmaka
        //     FROM
        //         hasilradiologi_t AS so
        //     INNER JOIN antrianpasiendiperiksa_t AS apd ON apd.norec = so.noregistrasifk
        //     left JOIN strukorder_t as sod ON sod.norec_apd = apd.norec
        //     INNER JOIN pasiendaftar_t AS pd ON pd.norec = apd.noregistrasifk
        //     INNER JOIN pasien_m AS ps ON ps. ID = pd.nocmfk
        //     INNER JOIN kelompokpasien_m AS kp ON kp. ID = pd.objectkelompokpasienlastfk
        //     INNER JOIN ruangan_m AS ru ON ru. ID = pd.objectruanganlastfk
        //     INNER JOIN pelayananpasien_t AS pp ON pp.norec = so.pelayananpasienfk
        //     inner join produk_m as pr on pr.id =pp.produkfk
        //     LEFT JOIN radionuklida_m rn on rn.id = sod.jenisradionuklidafk
        //     LEFT JOIN farmaka_m fr on fr.id = sod.jenisfarmakafk
        //     LEFT JOIN jeniskelamin_m AS jk ON jk. ID = ps.objectjeniskelaminfk
        //     LEFT JOIN kelompokpasien_m AS kps ON kps. ID = pd.objectkelompokpasienlastfk
        //     LEFT JOIN alamat_m AS alm ON alm.nocmfk = ps. ID
        //     left join desakelurahan_m as ds on ds.id=alm.objectdesakelurahanfk
        //     left join kotakabupaten_m as kk on kk.id=alm.objectkotakabupatenfk
        //     left join kecamatan_m as kc on kc.id=alm.objectkecamatanfk
        //     left join propinsi_m as pro on pro.id=alm.objectpropinsifk
        //     LEFT JOIN pegawai_m AS pg ON pg. ID = pd.objectpegawaifk
        //     LEFT JOIN pegawai_m AS pg2 ON pg2. ID = so.pegawaifk

        //     WHERE
        //         sod.norec = '$r[noorder]'
        //     AND sod.kdprofile = $kdProfile
        //     AND sod.statusenabled = TRUE
        // "))->first();

            //NEW QUERY FOR DATA PASIEN EXPERTISE
        $raw = collect(DB::select("
            select 
                ps.namapasien,
                ps.tgllahir,
                ps.alamatlengkap as alamatmanual,
                ps.nocm,
                TO_CHAR(age(ps.tgllahir), 'YY tahun') as umur,
                so.noorder,
                so.terapiradiofarmaka,
                so.catatanfarmaka,
                so.tglorder,
                jk.jeniskelamin,
                pr.namaproduk,
                fr.farmaka,
                ru.namaruangan,
                pg.namalengkap AS perujuk,
                pg.id AS perujukid,
                rn.radionuklida,
            CASE WHEN 
                alm.alamatlengkap IS NULL THEN'-' 
            ELSE (
                alm.alamatlengkap || ' ' || ds.namadesakelurahan || ' '|| kc.namakecamatan || ' ' || kk.namakotakabupaten || ' '  || pro.namapropinsi )
            END AS alamatlengkap

            FROM
                strukorder_t AS so
                JOIN pasiendaftar_t AS pd ON so.noregistrasi = pd.noregistrasi
                JOIN pasien_m AS ps ON pd.nocmfk = ps.ID 
                JOIN orderpelayanan_t AS op ON so.norec = op.strukorderfk
                left JOIN pegawai_m AS pg ON pd.objectpegawaifk = pg.ID 
                JOIN ruangan_m AS ru ON pd.objectruanganlastfk = ru.ID 
                join produk_m as pr on op.objectprodukfk=pr.id
                LEFT JOIN jeniskelamin_m AS jk ON ps.objectjeniskelaminfk = jk.ID 
                left join farmaka_m as fr on so.jenisfarmakafk = fr.id
                left join alamat_m as alm on ps.id = alm.nocmfk
                left join desakelurahan_m as ds on ds.id=alm.objectdesakelurahanfk
                left join kotakabupaten_m as kk on kk.id=alm.objectkotakabupatenfk
                left join kecamatan_m as kc on kc.id=alm.objectkecamatanfk
                left join propinsi_m as pro on pro.id=alm.objectpropinsifk
                LEFT JOIN radionuklida_m rn on rn.id = so.jenisradionuklidafk

            WHERE
                so.noorder = '$r[noorder]'
            AND so.kdprofile = $kdProfile
            AND so.statusenabled = TRUE
        "))->first();
            // GET DATA HASIL EXPERTISE NYA
        $pacs = DB::connection('sqlsrv_ris')
        ->table('ris_in as ri')
        ->join('ris_out as ro', 'ri.no_rontgen', '=', 'ro.no_rontgen')
        ->where('ri.nobukti', '=', $r['noorder'])
        ->where('ri.kode_pemeriksaan', '=', $r['idproduk'])
        ->select('ro.expertise_text_finding','ro.expertise_text_conclusion', 'ri.nobukti','ro.kode_dokter_radiolog as kode_dokter_out', 'ri.kode_pemeriksaan','ri.kode_dokter_radiolog')
        ->get();

        // $testresult=array(
        //     'data'=>$pacs
        // );
        // dd($testresult);
        // dd($pacs);
        if(count($pacs) == 0){
            abort(404,'Hasil Expertise Belum Ada');
        }
            // GET DATA DOKTER DARI RIS NYA
        $getidDokterBrid=DB::table('pegawai_m')->where('sanata_id',$pacs[0]->kode_dokter_out)->where('statusenabled',true)->select('id','namalengkap as dokterrad','nosip as dokterradnosip')->first();
            // GET DATA PRODUK DARI RIS NYA
        $getProduk=DB::table('produk_m')->where('sanata_jasa_id',$pacs[0]->kode_pemeriksaan)->where('statusenabled',true)->select('namaproduk')->first();
        // dd($pacs);

        if (!empty($raw)) {
            $raw->umur = $this->getAge($raw->tgllahir, date('Y-m-d'));
        } else {
            // echo 'Data Tidak ada ';
            return null;
        }

        $pageWidth = 950;
        $profile = $this->profile();


        $dataImg = DB::connection('mongodb')
            ->table('TandaTangan')
            ->where('pegawaifk', (int)$getidDokterBrid->id)
            ->where('statusenabled', true)
            ->where('kdprofile', (int) $this->kdProfile)
            ->first();
        $res['pdf']  =  true;

        $img = null;
        if (!empty($dataImg)) {
            $img = $dataImg['ttd'];
        }
        $qrcode = base64_encode(QrCode::format('svg')->size(75)->generate($getidDokterBrid->dokterrad));
        $title = isset($r['echo']) ? 'ECHOCARDIOGRAFI' : 'RADIOLOGI';
        if (isset($r['storage']) && $r['storage']) {
            $pdf = App::make('dompdf.wrapper');
            // $customPaper = array(0, 0, 210, 295);
            // $pdf->setPaper($customPaper);
            $pdf->loadView(
                'report.radiologi.expertise',
                array(
                    'raw' => $raw,
                    'dataBrid'=>$pacs,
                    'dataBridDokter'=>$getidDokterBrid,
                    'getProdukBrid'=>$getProduk,
                    'profile' => $profile,
                    'pageWidth' => $pageWidth,
                    'res' => $res,
                    'img' => $img,
                    'title' => $title,
                )
            );
            return $pdf;
        }
        if ($res['pdf'] == 'true') {
            $pdf = App::make('dompdf.wrapper');
            $pdf->loadView(
                'report.radiologi.expertise',
                array(
                    'raw' => $raw,
                    'profile' => $profile,
                    'pageWidth' => $pageWidth,
                    'dataBridDokter'=>$getidDokterBrid,
                    'getProdukBrid'=>$getProduk,
                    'res' => $res,
                    'img' => $img,
                    'dataBrid'=>$pacs,
                    'ttde' => $qrcode,
                    'title' => $title,
                )
            );
            return $pdf->stream();
        }
        return view(
            'report.radiologi.expertise',
            compact('raw', 'pageWidth', 'img', 'title', 'res', 'profile','dataBrid','dataBridDokter','getProdukBrid')
        );
    }

    public function cetakExpertiseManualKlaim(Request $r){

        $kdProfile =  $this->kdProfile;
        $noregistrasi = $r['noregistrasi'];

         $raw = collect(DB::select("
            SELECT
                so.nofoto,ps.nocm, ps.namapasien,ps.tgllahir,kp.kelompokpasien,
            ru.namaruangan, so.tanggal,jk.jeniskelamin, sod.catatanklinis,
            CASE WHEN alm.alamatlengkap IS NULL THEN
                '-' ELSE (
                alm.alamatlengkap || ' ' || ds.namadesakelurahan || ' '|| kc.namakecamatan
                || ' ' || kk.namakotakabupaten || ' '  || pro.namapropinsi )
            END AS alamatlengkap,
            pg.namalengkap as perujuk,pg2.namalengkap as dokterrad, sod.tglorder,ps.alamatlengkap as alamatkedua,
            pr.namaproduk,so.keterangan,pd.noregistrasi,pg2.nippns,pg2.nosip as dokterradnosip,
            pg2.id as pgid,  pg.nosip as perujukdokternosip, pg.id as pgidperujuk,
            ru.norec as noruangan,pd.tglregistrasi as tglregistrasi, sod.terapiradiofarmaka,
            TO_CHAR(age(ps.tgllahir), 'YY tahun') as umur, rn.radionuklida, fr.farmaka, sod.catatanfarmaka
            FROM
                hasilradiologi_t AS so
            INNER JOIN antrianpasiendiperiksa_t AS apd ON apd.norec = so.noregistrasifk
            left JOIN strukorder_t as sod ON sod.norec_apd = apd.norec
            INNER JOIN pasiendaftar_t AS pd ON pd.norec = apd.noregistrasifk
            INNER JOIN pasien_m AS ps ON ps. ID = pd.nocmfk
            INNER JOIN kelompokpasien_m AS kp ON kp. ID = pd.objectkelompokpasienlastfk
            INNER JOIN ruangan_m AS ru ON ru. ID = pd.objectruanganlastfk
            INNER JOIN pelayananpasien_t AS pp ON pp.norec = so.pelayananpasienfk
            inner join produk_m as pr on pr.id =pp.produkfk
            LEFT JOIN radionuklida_m rn on rn.id = sod.jenisradionuklidafk
            LEFT JOIN farmaka_m fr on fr.id = sod.jenisfarmakafk
            LEFT JOIN jeniskelamin_m AS jk ON jk. ID = ps.objectjeniskelaminfk
            LEFT JOIN kelompokpasien_m AS kps ON kps. ID = pd.objectkelompokpasienlastfk
            LEFT JOIN alamat_m AS alm ON alm.nocmfk = ps. ID
            left join desakelurahan_m as ds on ds.id=alm.objectdesakelurahanfk
            left join kotakabupaten_m as kk on kk.id=alm.objectkotakabupatenfk
            left join kecamatan_m as kc on kc.id=alm.objectkecamatanfk
            left join propinsi_m as pro on pro.id=alm.objectpropinsifk
            LEFT JOIN pegawai_m AS pg ON pg. ID = pd.objectpegawaifk
            LEFT JOIN pegawai_m AS pg2 ON pg2. ID = so.pegawaifk

            WHERE
                pd.noregistrasi = '$noregistrasi'
            AND sod.kdprofile = $kdProfile
            AND sod.statusenabled = TRUE
        "))->first();

        $pageWidth = 950;
        $profile = $this->profile();

        // $EMRCtrl = new EMRCtrl();

        // $requestBerkas = new Request([
        //     'norec_hr' => $r['norec']
        // ]);

        // $responseBerkas = $EMRCtrl->getBerkasPasien($requestBerkas);
        
        // $berkasPasien = $responseBerkas->getData();

        // // dd($berkasPasien);
        
        // $berkasPasien = $berkasPasien->response->data; // Extract data
        
        // $images = [];
        // $pdfFiles = [];
        
        // foreach ($berkasPasien as $berkas) {
        //     // Use Storage::path to correctly locate the file in storage/app/public/
        //     $filePath = Storage::path('public/' . $berkas->file);

        //     // dd($filePath);
        //     if (File::exists($filePath)) {
        //         if (str_ends_with($berkas->file, '.jpg') || str_ends_with($berkas->file, '.png')) {
        //             $images[] = File::get($filePath); // Get image content
        //         } elseif (str_ends_with($berkas->file, '.pdf')) {
        //             $pdfFiles[] = File::get($filePath); // Get PDF content
        //         }
        //     } else {
        //         Log::error("File not found: " . $filePath); // Log missing files
        //     }
        // }
        
        
        // dd($berkasPasien);

        $dataImg = DB::connection('mongodb')
            ->table('TandaTangan')
            ->where('pegawaifk', (int)$raw->pgid)
            ->where('statusenabled', true)
            ->where('kdprofile', (int) $this->kdProfile)
            ->first();
        $res['pdf']  =  true;

        $img = null;
        if (!empty($dataImg)) {
            $img = $dataImg['ttd'];
        }
        $qrcode = base64_encode(QrCode::format('svg')->size(75)->generate($raw->dokterrad));
        $title = 'RADIOLOGI';
        // if (isset($r['storage']) && $r['storage']) {
        //     $pdf1 = App::make('dompdf.wrapper');
        //     // $customPaper = array(0, 0, 210, 295);
        //     // $pdf1->setPaper($customPaper);
        //     $pdf1Content = $pdf1->loadView(
        //         'report.radiologi.expertise-manual',
        //         array(
        //             'raw' => $raw,
        //             // 'dataBrid'=>$pacs,
        //             'dataBridDokter'=>$raw->dokterrad,
        //             // 'getProdukBrid'=>$getProduk,
        //             'profile' => $profile,
        //             'pageWidth' => $pageWidth,
        //             'res' => $res,
        //             'img' => $img,
        //             'title' => $title,
        //         )
        //     );
        //     $pdf1->output();
        // }
        // if ($res['pdf'] == 'true') {
            $pdf = App::make('dompdf.wrapper')->loadView(
                'report.radiologi.expertise-manual',
                [
                    'raw' => $raw,
                    'profile' => $profile,
                    'pageWidth' => $pageWidth,
                    'dataBridDokter' => $raw->dokterrad,
                    'res' => $res,
                    'img' => $img,
                    'ttde' => $qrcode,
                    'title' => $title,
                ]
            );

            // ✅ Get the actual PDF content
            // $pdf1Content = $pdf1->output();

            // // ✅ Create temporary file for pdf1
            // $pdf1Path = tempnam(sys_get_temp_dir(), 'pdf1_') . '.pdf';

            // // ✅ Write PDF content to file
            // File::put($pdf1Path, $pdf1Content);

            // // ✅ Ensure the file is not empty before using it
            // if (file_exists($pdf1Path) && filesize($pdf1Path) > 0) {
            //     $pdfFiles = [$pdf1Path];
            // } else {
            //     throw new Exception("Failed to generate a valid PDF.");
            // }


            // foreach ($berkasPasien as $berkas) {
            //     $filePath = storage_path('app/public/' . $berkas->file);
            
            //     if (File::exists($filePath)) {
            //         if (preg_match('/\.(jpg|jpeg|png|gif|bmp|webp|svg|tiff|tif|ico)$/i', $berkas->file)) {
            //             // ✅ Get image size
            //             list($width, $height) = getimagesize($filePath);
                
            //             // ✅ Generate PDF from Blade View
            //             $pdf = Pdf::loadView('report.radiologi.image-pdf', [
            //                 'imagePath' => $filePath,
            //                 'imageWidth' => $width,
            //                 'imageHeight' => $height
            //             ]);
                        
            //             // ✅ Save temporary PDF
            //             $tempPdfPath = tempnam(sys_get_temp_dir(), 'img_pdf_') . '.pdf';
            //             File::put($tempPdfPath, $pdf->output());
            //         } elseif (str_ends_with($berkas->file, '.pdf')) {
            //             // ✅ Instead of using the existing PDF, create a temporary copy
            //             $tempPdfPath = tempnam(sys_get_temp_dir(), 'existing_pdf_') . '.pdf';
            //             File::copy($filePath, $tempPdfPath);
            //         }
                
            //         // ✅ Ensure the temporary file exists and has content before adding it to the array
            //         if (isset($tempPdfPath) && file_exists($tempPdfPath) && filesize($tempPdfPath) > 0) {
            //             $pdfFiles[] = $tempPdfPath;
            //         }
            //     }
                
            // }
            
        
        // Merge all PDFs
        // $pdfMerger = PDFMerger::init();
        // // dd($pdf1Path, $pdfFiles);
        // foreach ($pdfFiles as $pdf) {
        //     $pdfMerger->addPDF($pdf, 'all');
        // }
        
        // // dd($pdfFiles);

        // // foreach ($pdfFiles as $pdf) {
        // //     if (!file_exists($pdf) || filesize($pdf) === 0) {
        // //         return response()->json(['error' => "Invalid PDF: $pdf does not exist or is empty"], 500);
        // //     }
        // // }
        
        // $pdfMerger->merge();

        // // Delete temporary files (except original PDFs)
        // foreach ($pdfFiles as $pdf) {
        //     // dd($pdf);
        //     if (!in_array($pdf, array_column($berkasPasien, 'file'))) {
        //         File::delete($pdf);
        //     }
        // }

        return $pdf;

    }

    public function cetakEkspertiseEchoKlaim(Request $r)
    {


        $kdProfile =  $this->kdProfile;
        $noregistrasi = $r['noregistrasi'];

        $order = DB::table('strukorder_t AS so')
            ->join('pasiendaftar_t AS pd', 'pd.norec', '=', 'so.noregistrasifk')
            ->leftjoin('pasien_m AS pm', 'pm.id', '=', 'so.nocmfk')
            ->where('pd.noregistrasi', '=', $r['noregistrasi'])
            ->where('so.keteranganorder', 'Order Radiologi')
            ->where('so.objectruangantujuanfk', 330)
            ->where('pd.statusenabled', true)
            ->where('so.statusenabled', true)
            ->select('so.noorder')
            ->get();
            

            $order = $order->toArray();

            // dd($order);


            $arr = [];
            for($x = 0; $x < count($order); $x++){
                $arr[$x] = $order[$x]->noorder;
            }

            // dd($arr);

            //NEW QUERY FOR DATA PASIEN EXPERTISE
        // $raw = collect(DB::select("
        //     select 
        //         ps.namapasien,
        //         ps.tgllahir,
        //         ps.alamatlengkap as alamatmanual,
        //         ps.nocm,
        //         TO_CHAR(age(ps.tgllahir), 'YY tahun') as umur,
        //         so.noorder,
        //         so.terapiradiofarmaka,
        //         so.catatanfarmaka,
        //         so.tglorder,
        //         jk.jeniskelamin,
        //         pr.namaproduk,
        //         fr.farmaka,
        //         ru.namaruangan,
        //         pg.namalengkap AS perujuk,
        //         pg.id AS perujukid,
        //         rn.radionuklida,
        //     CASE WHEN 
        //         alm.alamatlengkap IS NULL THEN'-' 
        //     ELSE (
        //         alm.alamatlengkap || ' ' || ds.namadesakelurahan || ' '|| kc.namakecamatan || ' ' || kk.namakotakabupaten || ' '  || pro.namapropinsi )
        //     END AS alamatlengkap

        //     FROM
        //         strukorder_t AS so
        //         JOIN pasiendaftar_t AS pd ON so.noregistrasi = pd.noregistrasi
        //         JOIN pasien_m AS ps ON pd.nocmfk = ps.ID 
        //         JOIN orderpelayanan_t AS op ON so.norec = op.strukorderfk
        //         JOIN pegawai_m AS pg ON pd.objectpegawaifk = pg.ID 
        //         JOIN ruangan_m AS ru ON pd.objectruanganlastfk = ru.ID 
        //         join produk_m as pr on op.objectprodukfk=pr.id
        //         LEFT JOIN jeniskelamin_m AS jk ON ps.objectjeniskelaminfk = jk.ID 
        //         left join farmaka_m as fr on so.jenisfarmakafk = fr.id
        //         left join alamat_m as alm on ps.id = alm.nocmfk
        //         left join desakelurahan_m as ds on ds.id=alm.objectdesakelurahanfk
        //         left join kotakabupaten_m as kk on kk.id=alm.objectkotakabupatenfk
        //         left join kecamatan_m as kc on kc.id=alm.objectkecamatanfk
        //         left join propinsi_m as pro on pro.id=alm.objectpropinsifk
        //         LEFT JOIN radionuklida_m rn on rn.id = so.jenisradionuklidafk

        //     WHERE
        //         pd.noregistrasi = '$noregistrasi'
        //     AND so.kdprofile = $kdProfile
        //     AND so.statusenabled = TRUE
        // "))->first();

        $raw = DB::table('strukorder_t as so')
            ->join('pasiendaftar_t as pd', 'so.noregistrasi', '=', 'pd.noregistrasi')
            ->join('pasien_m as ps', 'pd.nocmfk', '=', 'ps.id')
            ->join('orderpelayanan_t as op', 'so.norec', '=', 'op.strukorderfk')
            ->join('pegawai_m as pg', 'pd.objectpegawaifk', '=', 'pg.id')
            ->join('ruangan_m as ru', 'pd.objectruanganlastfk', '=', 'ru.id')
            ->join('produk_m as pr', 'op.objectprodukfk', '=', 'pr.id')
            ->leftJoin('jeniskelamin_m as jk', 'ps.objectjeniskelaminfk', '=', 'jk.id')
            ->leftJoin('farmaka_m as fr', 'so.jenisfarmakafk', '=', 'fr.id')
            ->leftJoin('alamat_m as alm', 'ps.id', '=', 'alm.nocmfk')
            ->leftJoin('desakelurahan_m as ds', 'ds.id', '=', 'alm.objectdesakelurahanfk')
            ->leftJoin('kotakabupaten_m as kk', 'kk.id', '=', 'alm.objectkotakabupatenfk')
            ->leftJoin('kecamatan_m as kc', 'kc.id', '=', 'alm.objectkecamatanfk')
            ->leftJoin('propinsi_m as pro', 'pro.id', '=', 'alm.objectpropinsifk')
            ->leftJoin('radionuklida_m as rn', 'rn.id', '=', 'so.jenisradionuklidafk')
            ->where('pd.noregistrasi', $noregistrasi)
            ->where('so.kdprofile', $kdProfile)
            ->where('so.statusenabled', true)
            ->selectRaw("
                ps.namapasien,
                ps.tgllahir,
                pd.tglregistrasi,
                ps.alamatlengkap as alamatmanual,
                ps.nocm,
                TO_CHAR(age(ps.tgllahir), 'YY tahun') as umur,
                so.noorder,
                so.terapiradiofarmaka,
                so.catatanfarmaka,
                so.tglorder,
                jk.jeniskelamin,
                pr.namaproduk,
                fr.farmaka,
                ru.namaruangan,
                pg.namalengkap AS perujuk,
                pg.id AS perujukid,
                rn.radionuklida,
                CASE 
                    WHEN alm.alamatlengkap IS NULL THEN '-' 
                    ELSE (
                        alm.alamatlengkap || ' ' || ds.namadesakelurahan || ' ' || kc.namakecamatan || ' ' || kk.namakotakabupaten || ' '  || pro.namapropinsi
                    )
                END AS alamatlengkap
            ")
            ->get();

        // Ambil data dari RIS
        $pacs = DB::connection('sqlsrv_ris')
            ->table('ris_in as ri')
            ->join('ris_out as ro', 'ri.no_rontgen', '=', 'ro.no_rontgen')
            ->whereIn('ri.nobukti', $arr)
            ->select('ro.expertise_text_finding','ro.expertise_text_conclusion', 'ro.radiolog_datetime_end','ri.nobukti', 'ri.kode_pemeriksaan','ri.kode_dokter_radiolog')
            ->get();

        $kodeDokters = $pacs->pluck('kode_dokter_radiolog')->unique()->toArray();
        $kodeProduks = $pacs->pluck('kode_pemeriksaan')->unique()->toArray();

        // Ambil data dokter
        $getDokter = DB::table('pegawai_m')
            ->whereIn('sanata_id', $kodeDokters)
            ->where('statusenabled', true)
            ->select('id','namalengkap as dokterrad','nosip as dokterradnosip','sanata_id')
            ->get()
            ->keyBy('sanata_id');

        // Ambil data produk
        $getProduk = DB::table('produk_m')
            ->whereIn('sanata_jasa_id', $kodeProduks)
            ->where('statusenabled', true)
            ->select('namaproduk', 'sanata_jasa_id')
            ->get()
            ->keyBy('sanata_jasa_id');

        // Ambil tanda tangan dokter dari MongoDB
        $dataImg = DB::connection('mongodb')
            ->table('TandaTangan')
            ->whereIn('pegawaifk', $getDokter->pluck('id')->toArray())
            ->where('statusenabled', true)
            ->where('kdprofile', (int) $this->kdProfile)
            ->get()
            ->keyBy('pegawaifk');

        // Persiapan data final
        $dataBrid = [];

        foreach ($raw as $rawValue) {
            foreach ($pacs as $pacsValue) {
                if ($rawValue->noorder == $pacsValue->nobukti) {
                    $dokter = $getDokter[$pacsValue->kode_dokter_radiolog] ?? null;
                    $produk = $getProduk[$pacsValue->kode_pemeriksaan] ?? null;
                    $ttd    = $dokter ? ($dataImg[$dokter->id] ?? null) : null;

                    $dataBrid[] = [
                        'expertise_text_finding'    => $pacsValue->expertise_text_finding,
                        'expertise_text_conclusion' => $pacsValue->expertise_text_conclusion,
                        'tgl_jam_expertise'             => $pacsValue->radiolog_datetime_end,
                        'namaruangan'               => $rawValue->namaruangan,
                        'nocm'                      => $rawValue->nocm,
                        'namapasien'                => $rawValue->namapasien,
                        'perujuk'                   => $rawValue->perujuk,
                        'jeniskelamin'              => $rawValue->jeniskelamin,
                        'alamatmanual'              => $rawValue->alamatmanual,
                        'alamatlengkap'             => $rawValue->alamatlengkap,
                        'dokterrad'                 => $dokter->dokterrad ?? null,
                        'dokterradnosip'            => $dokter->dokterradnosip ?? null,
                        'namaproduk'                => $produk->namaproduk ?? $rawValue->namaproduk,
                        'tandatangan'               => $ttd->filettd ?? null,
                        'tglorder'                  => $rawValue->tglorder,
                        'tgllahir'                  => $rawValue->tgllahir,
                        'umur'                      => $this->getAge($rawValue->tgllahir, $rawValue->tglregistrasi),
                        'ttde'                      => base64_encode(QrCode::format('svg')->size(75)->generate($dokter->dokterrad ?? ''))
                    ];
                }
            }
        }

        if(empty($pacs)){
            abort(404,'Hasil Expertise Belum Ada');
        }

        if (!empty($raw)) {
            $raw[0]->umur = $this->getAge($raw[0]->tgllahir, date('Y-m-d'));
        } else {
            return null;
        }

        $pageWidth = 950;
        $profile = $this->profile();
       
        $res['pdf']  =  true;

        // $img = null;
        // if (!empty($dataImg)) {
        //     $img = $dataImg['ttd'];
        // }
        
        $title = isset($r['echo']) ? 'ECHOCARDIOGRAFI' : 'RADIOLOGI';
        if (isset($r['storage']) && $r['storage']) {
            $pdf = App::make('dompdf.wrapper');
            // $customPaper = array(0, 0, 210, 295);
            // $pdf->setPaper($customPaper);
            $pdf->loadView(
                'report.radiologi.expertise-klaim',
                array(
                    'raw' => $raw,
                    'dataBrid'=>$dataBrid,
                    // 'dataBridDokter'=>$getidDokterBrid,
                    'getProdukBrid'=>$getProduk,
                    'profile' => $profile,
                    'pageWidth' => $pageWidth,
                    'res' => $res,
                    // 'img' => $img,
                    // 'ttde' => $qrcode,
                    'title' => $title,
                )
            );
            return $pdf;
        }
        if ($res['pdf'] == 'true') {
            $pdf = App::make('dompdf.wrapper');
            $pdf->loadView(
                'report.radiologi.expertise-klaim',
                array(
                    'raw' => $raw,
                    'profile' => $profile,
                    'pageWidth' => $pageWidth,
                    // 'dataBridDokter'=>$getidDokterBrid,
                    'getProdukBrid'=>$getProduk,
                    'res' => $res,
                    // 'img' => $img,
                    'dataBrid'=>$dataBrid,
                    // 'ttde' => $qrcode,
                    'title' => $title,
                )
            );
            return $pdf->stream();
        }
        return view(
            'report.radiologi.expertise-klaim',
            compact('raw', 'pageWidth', 'img', 'title', 'res', 'profile','dataBrid','dataBridDokter','getProdukBrid')
        );
    }

    public function cetakEkspertiseEchoKlaimInvivo(Request $r)
    {


        $kdProfile =  $this->kdProfile;
        $noregistrasi = $r['noregistrasi'];

        // if (isset($r['noregistrasi'])) {
        //     $data = DB::table("hasilradiologi_t as hr")
        //         ->select('hr.norec')
        //         ->join('antrianpasiendiperiksa_t as apd', 'apd.norec', '=', 'hr.noregistrasifk')
        //         ->join('pasiendaftar_t as pd', 'pd.norec', '=', 'apd.noregistrasifk')
        //         ->where('pd.noregistrasi', $r['noregistrasi'])
        //         ->first();

        //     if (!empty($data)) {
        //         $r['norec'] = $data->norec;
        //     } else {
        //         $r['norec'] = null;
        //     }
        // }

                //OLD QUERY DATA 

        // $raw = collect(DB::select("
        //     SELECT
        //         so.nofoto,ps.nocm, ps.namapasien,ps.tgllahir,kp.kelompokpasien,
        //     ru.namaruangan, so.tanggal,jk.jeniskelamin, sod.catatanklinis,
        //     CASE WHEN alm.alamatlengkap IS NULL THEN
        //         '-' ELSE (
        //         alm.alamatlengkap || ' ' || ds.namadesakelurahan || ' '|| kc.namakecamatan
        //         || ' ' || kk.namakotakabupaten || ' '  || pro.namapropinsi )
        //     END AS alamatlengkap,
        //     pg.namalengkap as perujuk,pg2.namalengkap as dokterrad, sod.tglorder,
        //     pr.namaproduk,so.keterangan,pd.noregistrasi,pg2.nippns,pg2.nosip as dokterradnosip,
        //     pg2.id as pgid,  pg.nosip as perujukdokternosip, pg.id as pgidperujuk,
        //     ru.norec as noruangan,pd.tglregistrasi as tglregistrasi, sod.terapiradiofarmaka,
        //     TO_CHAR(age(ps.tgllahir), 'YY tahun') as umur, rn.radionuklida, fr.farmaka, sod.catatanfarmaka
        //     FROM
        //         hasilradiologi_t AS so
        //     INNER JOIN antrianpasiendiperiksa_t AS apd ON apd.norec = so.noregistrasifk
        //     left JOIN strukorder_t as sod ON sod.norec_apd = apd.norec
        //     INNER JOIN pasiendaftar_t AS pd ON pd.norec = apd.noregistrasifk
        //     INNER JOIN pasien_m AS ps ON ps. ID = pd.nocmfk
        //     INNER JOIN kelompokpasien_m AS kp ON kp. ID = pd.objectkelompokpasienlastfk
        //     INNER JOIN ruangan_m AS ru ON ru. ID = pd.objectruanganlastfk
        //     INNER JOIN pelayananpasien_t AS pp ON pp.norec = so.pelayananpasienfk
        //     inner join produk_m as pr on pr.id =pp.produkfk
        //     LEFT JOIN radionuklida_m rn on rn.id = sod.jenisradionuklidafk
        //     LEFT JOIN farmaka_m fr on fr.id = sod.jenisfarmakafk
        //     LEFT JOIN jeniskelamin_m AS jk ON jk. ID = ps.objectjeniskelaminfk
        //     LEFT JOIN kelompokpasien_m AS kps ON kps. ID = pd.objectkelompokpasienlastfk
        //     LEFT JOIN alamat_m AS alm ON alm.nocmfk = ps. ID
        //     left join desakelurahan_m as ds on ds.id=alm.objectdesakelurahanfk
        //     left join kotakabupaten_m as kk on kk.id=alm.objectkotakabupatenfk
        //     left join kecamatan_m as kc on kc.id=alm.objectkecamatanfk
        //     left join propinsi_m as pro on pro.id=alm.objectpropinsifk
        //     LEFT JOIN pegawai_m AS pg ON pg. ID = pd.objectpegawaifk
        //     LEFT JOIN pegawai_m AS pg2 ON pg2. ID = so.pegawaifk

        //     WHERE
        //         sod.norec = '$r[noorder]'
        //     AND sod.kdprofile = $kdProfile
        //     AND sod.statusenabled = TRUE
        // "))->first();

        $order = DB::table('strukorder_t AS so')
            ->join('pasiendaftar_t AS pd', 'pd.norec', '=', 'so.noregistrasifk')
            ->leftjoin('pasien_m AS pm', 'pm.id', '=', 'so.nocmfk')
            ->where('pd.noregistrasi', '=', $r['noregistrasi'])
            ->where('so.keteranganorder', 'Order Radiologi')
            ->where('so.objectruangantujuanfk', 331)
            ->where('pd.statusenabled', true)
            ->where('so.statusenabled', true)
            ->select('so.noorder')
            ->get();
            

            $order = $order->toArray();

            // dd($order);


            $arr = [];
            for($x = 0; $x < count($order); $x++){
                $arr[$x] = $order[$x]->noorder;
            }

            // dd($arr);

            //NEW QUERY FOR DATA PASIEN EXPERTISE
        $raw = collect(DB::select("
            select 
                ps.namapasien,
                ps.tgllahir,
                ps.alamatlengkap as alamatmanual,
                ps.nocm,
                TO_CHAR(age(ps.tgllahir), 'YY tahun') as umur,
                so.noorder,
                so.terapiradiofarmaka,
                so.catatanfarmaka,
                so.tglorder,
                jk.jeniskelamin,
                pr.namaproduk,
                fr.farmaka,
                ru.namaruangan,
                pg.namalengkap AS perujuk,
                pg.id AS perujukid,
                rn.radionuklida,
            CASE WHEN 
                alm.alamatlengkap IS NULL THEN'-' 
            ELSE (
                alm.alamatlengkap || ' ' || ds.namadesakelurahan || ' '|| kc.namakecamatan || ' ' || kk.namakotakabupaten || ' '  || pro.namapropinsi )
            END AS alamatlengkap

            FROM
                strukorder_t AS so
                JOIN pasiendaftar_t AS pd ON so.noregistrasi = pd.noregistrasi
                JOIN pasien_m AS ps ON pd.nocmfk = ps.ID 
                JOIN orderpelayanan_t AS op ON so.norec = op.strukorderfk
                JOIN pegawai_m AS pg ON pd.objectpegawaifk = pg.ID 
                JOIN ruangan_m AS ru ON pd.objectruanganlastfk = ru.ID 
                join produk_m as pr on op.objectprodukfk=pr.id
                LEFT JOIN jeniskelamin_m AS jk ON ps.objectjeniskelaminfk = jk.ID 
                left join farmaka_m as fr on so.jenisfarmakafk = fr.id
                left join alamat_m as alm on ps.id = alm.nocmfk
                left join desakelurahan_m as ds on ds.id=alm.objectdesakelurahanfk
                left join kotakabupaten_m as kk on kk.id=alm.objectkotakabupatenfk
                left join kecamatan_m as kc on kc.id=alm.objectkecamatanfk
                left join propinsi_m as pro on pro.id=alm.objectpropinsifk
                LEFT JOIN radionuklida_m rn on rn.id = so.jenisradionuklidafk

            WHERE
                pd.noregistrasi = '$noregistrasi'
            AND so.kdprofile = $kdProfile
            AND so.statusenabled = TRUE
        "))->first();
            // GET DATA HASIL EXPERTISE NYA
        $pacs = DB::connection('sqlsrv_ris')
        ->table('ris_in as ri')
        ->join('ris_out as ro', 'ri.no_rontgen', '=', 'ro.no_rontgen')
        ->whereIn('ri.nobukti', $arr)
        // ->where('ri.kode_pemeriksaan', '=', $r['idproduk'])
        ->select('ro.expertise_text_finding','ro.expertise_text_conclusion', 'ri.nobukti', 'ri.kode_pemeriksaan','ri.kode_dokter_radiolog')
        ->get();

        // $testresult=array(
        //     'data'=>$pacs
        // );
        // dd($testresult);
        // dd($pacs);
        if(empty($pacs)){
            abort(404,'Hasil Expertise Belum Ada');
        }
            // GET DATA DOKTER DARI RIS NYA
        $getidDokterBrid=DB::table('pegawai_m')->where('sanata_id',$pacs[0]->kode_dokter_radiolog)->where('statusenabled',true)->select('id','namalengkap as dokterrad','nosip as dokterradnosip')->first();
            // GET DATA PRODUK DARI RIS NYA
        $getProduk=DB::table('produk_m')->where('sanata_jasa_id',$pacs[0]->kode_pemeriksaan)->where('statusenabled',true)->select('namaproduk')->first();
        // dd($pacs);

        if (!empty($raw)) {
            $raw->umur = $this->getAge($raw->tgllahir, date('Y-m-d'));
        } else {
            // echo 'Data Tidak ada ';
            return null;
        }

        $pageWidth = 950;
        $profile = $this->profile();


        $dataImg = DB::connection('mongodb')
            ->table('TandaTangan')
            ->where('pegawaifk', (int)$getidDokterBrid->id)
            ->where('statusenabled', true)
            ->where('kdprofile', (int) $this->kdProfile)
            ->first();
        $res['pdf']  =  true;

        $img = null;
        if (!empty($dataImg)) {
            $img = $dataImg['ttd'];
        }
        $qrcode = base64_encode(QrCode::format('svg')->size(75)->generate($getidDokterBrid->dokterrad));
        $title = isset($r['echo']) ? 'ECHOCARDIOGRAFI' : 'RADIOLOGI';
        if (isset($r['storage']) && $r['storage']) {
            $pdf = App::make('dompdf.wrapper');
            // $customPaper = array(0, 0, 210, 295);
            // $pdf->setPaper($customPaper);
            $pdf->loadView(
                'report.radiologi.expertise',
                array(
                    'raw' => $raw,
                    'dataBrid'=>$pacs,
                    'dataBridDokter'=>$getidDokterBrid,
                    'getProdukBrid'=>$getProduk,
                    'profile' => $profile,
                    'pageWidth' => $pageWidth,
                    'res' => $res,
                    'img' => $img,
                    'ttde' => $qrcode,
                    'title' => $title,
                )
            );
            return $pdf;
        }
        if ($res['pdf'] == 'true') {
            $pdf = App::make('dompdf.wrapper');
            $pdf->loadView(
                'report.radiologi.expertise',
                array(
                    'raw' => $raw,
                    'profile' => $profile,
                    'pageWidth' => $pageWidth,
                    'dataBridDokter'=>$getidDokterBrid,
                    'getProdukBrid'=>$getProduk,
                    'res' => $res,
                    'img' => $img,
                    'dataBrid'=>$pacs,
                    'ttde' => $qrcode,
                    'title' => $title,
                )
            );
            return $pdf->stream();
        }
        return view(
            'report.radiologi.expertise',
            compact('raw', 'pageWidth', 'img', 'title', 'res', 'profile','dataBrid','dataBridDokter','getProdukBrid')
        );
    }

    public function listRegisRadiologi(Request $r)
    {
        // $value = array_map('intval', explode(',', str_replace(',', '', $this->settingFix('idDepartemenRadiologi'))));
        $data  = DB::table('pasiendaftar_t as pd')
            // ->join('antrianpasiendiperiksa_t as apd', 'apd.noregistrasifk', '=', 'pd.norec')
            ->join('ruangan_m as ru', 'pd.objectruanganlastfk', '=', 'ru.id')
            ->leftjoin('pegawai_m as pg', 'pd.objectpegawaifk', '=', 'pg.id')
            ->leftjoin('pasien_m as ps', 'pd.nocmfk', '=', 'ps.id')
            ->join('kelas_m as kls', 'kls.id', '=', 'pd.objectkelasfk')
            ->leftjoin('jeniskelamin_m as jk', 'jk.id', '=', 'ps.objectjeniskelaminfk')
            ->leftJoin('kebangsaan_m as kbs', 'kbs.id', '=', 'ps.objectkebangsaanfk')
            ->select(
                'pd.norec as norec_pd',
                'pd.statusenabled',
                'pd.tglregistrasi',
                'ps.nocm',
                'pd.nocmfk',
                'pd.noregistrasi',
                'ru.namaruangan',
                'ps.norec as norec_ps',
                'ps.namapasien',
                'ps.objectkebangsaanfk',
                'pg.namalengkap as namadokter',
                'pd.tglpulang',
                'pd.statuspasien',
                'pd.objectpegawaifk as pgid',
                'pd.objectpegawaifk',
                'pd.objectruanganlastfk as objectruanganfk',
                'pd.objectruanganlastfk',
                'pd.nostruklastfk',
                'kls.namakelas',
                'ps.tgllahir',
                'ru.objectdepartemenfk',
                'pd.objectkelasfk',
                'ps.nobpjs',
                'jk.jeniskelamin',
                // 'apd.norec as norec_apd',
                'ps.noidentitas',
                'kbs.name as kebangsaan',
                DB::raw("CAST(pd.tglregistrasi
                AS DATE),
                (case when pd.ispelayananpasien=true then 'Selesai' else 'Menunggu Pelayanan' end) as statuspelayanan,
                ps.objectjeniskelaminfk")
            )

            ->where('pd.kdprofile', $this->kdProfile)
            // ->whereIn('ru.objectdepartemenfk', explode(',', $this->settingFix('KdDeptPasienRJ')))
            // ->whereBetween(DB::raw("pd.tglregistrasi::date"),$rangeDate)
            ->where('pd.statusenabled', true);
            // ->whereIn('ru.objectdepartemenfk', [27,18]);
            // ->where('ru.objectdepartemenfk', $this->settingFix('idDepartemenRadiologi'));


        if (isset($r['ruanganid']) && $r['ruanganid'] != '') {
            $data = $data->where('ru.id', '=',  $r['ruanganid']);
        }

        if (isset($r['noregistrasi']) && $r['noregistrasi'] != '') {
            $data = $data->where('pd.noregistrasi', '=',  $r['noregistrasi']);
        }
        if (isset($r['nocm']) && $r['nocm'] != '') {
            $data = $data->where('ps.nocm', '=',  $r['nocm']);
        }
        if (isset($r['rsearch']) && $r['rsearch'] != '') {
            $searchTerm = '%' . $r['rsearch'] . '%';
            $data = $data->where(function ($query) use ($searchTerm) {
                $query->where('ps.namapasien', 'ilike', $searchTerm)
                    ->orWhere('pd.noregistrasi', 'ilike', $searchTerm)
                    ->orWhere('ps.nocm', 'ilike', $searchTerm)
                    ->orWhere('ps.nobpjs', 'ilike', $searchTerm)
                    ->orWhere('ps.noidentitas', 'ilike', $searchTerm);
            });
        }

        if (isset($r['namapasien']) && $r['namapasien'] != '') {
            $data = $data->where('ps.namapasien', 'ilike', '%' . $r['namapasien'] . '%');
        }
        if (isset($r['dari']) && $r['dari'] != '') {
            $data = $data->where(DB::raw("pd.tglregistrasi::date"), '>=', $r->dari);
        }
        if (isset($r['sampai']) && $r['sampai'] != '') {
            $data = $data->where(DB::raw("pd.tglregistrasi::date"), '<=', $r->sampai);
        }
        if (isset($r['status']) && $r['status'] != '') {
            $data = $data->where('pd.ispelayananpasien', '=', $r['status']);
        }
        $total = $data->count();
        if (isset($r['limit']) && $r['limit'] != '') {
            $data = $data->limit($r['limit']);
        }
        if (isset($r['offset']) && $r['offset'] != '') {
            $data = $data->offset($r['offset']);
        }

        $data = $data->orderBy('pd.tglregistrasi');
        $data = $data->get();
        
        foreach ($data as $plankton) {
            $plankton->riawayatLayanan = null;
            $plankton->norec_so = null;
            $tuanKrabs=DB::table('pelayananpasien_t as pp')
                ->join('produk_m as prd','prd.id','=','pp.produkfk')
                ->leftJoin('strukorder_t as so','so.norec','=','pp.strukorderfk')
                ->where('pp.noregistrasi',$plankton->noregistrasi)
                ->whereIn('prd.objectdetailjenisprodukfk', explode(',', $this->settingFix('kodeJenisDetailProdukRadiologi')))
                ->select(
                    'prd.namaproduk',
                    'pp.noregistrasi',
                    'so.norec'
                )
                ->get();

            foreach ($tuanKrabs as $gerry) {
                if($plankton->noregistrasi == $gerry->noregistrasi){
                    $plankton->riawayatLayanan=$gerry->namaproduk;
                    $plankton->norec_so=$gerry->norec;
                    break;
                }
            }
        }
        foreach($data as $ultramennexus){
            $ultramennexus->norec_apd=null;
            $galaksi=DB::table('antrianpasiendiperiksa_t as apd')
            ->join('ruangan_m as ruki','ruki.id','=','apd.objectruanganfk')
            ->where('apd.statusenabled',true)
            ->where('apd.noregistrasi',$ultramennexus->noregistrasi)
            ->where('apd.noregistrasifk',$ultramennexus->norec_pd)
            ->where('ruki.objectdepartemenfk',$this->settingFix('idDepartemenRadiologi'))
            ->select(
                'apd.norec as norec_apd',
                'apd.noregistrasi',
                'apd.noregistrasifk'
            )
            ->orderByDesc('apd.tglregistrasi')
            ->get();

           foreach($galaksi as $rumahultramen){
                if($rumahultramen->noregistrasifk == $ultramennexus->norec_pd && $rumahultramen->noregistrasi == $ultramennexus->noregistrasi ){
                    $ultramennexus->norec_apd = $rumahultramen->norec_apd;
                    break;
                }
           }
        }

        if (isset($r['statusregis']) && $r['statusregis'] == '0') {
            $data=$data->whereNull('riawayatLayanan');
        }
        else if (isset($r['statusregis']) && $r['statusregis'] == '1') {
            $data=$data->whereNotNull('riawayatLayanan');
        }


        foreach ($data as $d) {
            $d->umur =  $this->getAgeYear($d->tgllahir, $d->tglregistrasi) . ' thn';
        }
        $res['data']= $data->values();
        $res['total'] = $total;
        return $this->respond($res);
    }

    public function saveTransaksiRad(Request $request)
    {
        DB::beginTransaction();
        try {
            $kdProfile = $this->kdProfile;
            $r_NewPD = $request['pasiendaftar'];
            $r_NewAPD = $request['antrianpasiendiperiksa'];

            $countNoAntrian = AntrianPasienDiperiksa::where('objectruanganfk', $r_NewAPD['objectruangantujuanfk'])
                ->where('tglregistrasi', '>=', date('Y-m-d', strtotime($r_NewPD['tglregistrasi'])) . ' 00:00')
                ->where('tglregistrasi', '<=',  date('Y-m-d', strtotime($r_NewPD['tglregistrasi'])) . ' 23:59')
                ->count();

            $noAntrian = $countNoAntrian + 1;

            $pd = PasienDaftar::where('norec', $r_NewPD['norec_pd'])->first();

            $dataAPD = new AntrianPasienDiperiksa;
            $dataAPD->norec = $dataAPD->generateNewId();
            $dataAPD->kdprofile = $this->kdProfile;
            $dataAPD->statusenabled = true;
            // $dataAPD->objectasalrujukanfk = $r_NewPD['asalrujukanfk'];
            $dataAPD->objectkelasfk =  $r_NewPD['objectkelasfk'];
            $dataAPD->noantrian = $noAntrian;
            $dataAPD->noregistrasifk = $r_NewPD['norec_pd'];
            // $dataAPD->objectpegawaifk = $r_NewPD['dokterfk'];
            $dataAPD->objectruanganfk = $r_NewAPD['objectruangantujuanfk'];
            $dataAPD->statusantrian = 0;
            $dataAPD->statuspasien = 1;
            $dataAPD->statuskunjungan = 'LAMA';
            $dataAPD->statuspenyakit = 'BARU';
            $dataAPD->objectruanganasalfk = $r_NewPD['objectruanganlastfk'];;
            $dataAPD->tglregistrasi = $r_NewPD['tglregistrasi'];
            $dataAPD->tglregistrasi = $pd->tglregistrasi; //date('Y-m-d H:i:s');
            $dataAPD->tglkeluar = date('Y-m-d H:i:s');
            $dataAPD->tglmasuk = date('Y-m-d H:i:s');
            $dataAPD->save();

            $this->LOGGING(
                'Registrasi Transaksi Pelayanan',
                $r_NewPD['norec_pd'],
                'pasiendaftar_t',
                ' pada Pasien ' .
                    $r_NewPD['norec_pd'] . 'ke' . $r_NewAPD['objectruangantujuanfk']
            );



            DB::commit();
            $result = array(
                "status" => 200,
                "message" => 'Berhasil',
                "result" => $dataAPD,
            );
        } catch (Exception $e) {
            DB::rollBack();
            $result = [
                "status" => 400,
                "message" => "Something Went Wrong",
                "result"  => $e->getMessage() . $e->getLine()
            ];
        }
        return $this->respond($result['result'], $result['status'], $result['message']);
    }

    public function getLaporanTindakanRadiologi(Request $request)
    {
        $kdProfile          = $this->kdProfile;
        $kelompokpasien     = $request->kelompokpasien;
        $tglAwal            = $request['tglAwal'] . " 00:00:00";
        $tglAkhir           = $request['tglAkhir'] . " 23:59:59";
        $kelompokpasien     = $request['kelompokpasien'] == "undefined" ? "" : $request['kelompokpasien'];
        $dokterdpjp         = $request['dokterdpjp'] == "undefined" ? "" : $request['dokterdpjp'];
        $sDokterPemeriksa   = $request['dokter'] == "undefined" ? "" : $request['dokter'];
        $rangeDate          = [$tglAwal, $tglAkhir];
        $searchTerm         = $request->search;
        try {
            $data = DB::table('pasiendaftar_t AS pd')
                ->select(
                    'hr.tanggal as test',
                    'pp.norec',
                    'pp.tglpelayanan',
                    'ps.nocm',
                    'pd.noregistrasi',
                    'ps.namapasien',
                    'hr.statusenabled',
                    'apd.objectruanganfk',
                    'jk.jeniskelamin',
                    'klp.kelompokpasien',
                    'pro.namaproduk',
                    'pp.jumlah',
                    'pp.hargajual',
                    'djp.detailjenisproduk',
                    'hr.tanggal as tglexpertise',
                    'pg.namalengkap AS dokterdpjp',
                    'pg1.namalengkap AS dokter',
                    'pg2.namalengkap AS radiografer',
                    DB::raw('
                        CASE
                        WHEN ru1.namaruangan IS NOT NULL THEN ru1.namaruangan
                        ELSE ru2.namaruangan
                        END AS ruangan

                    '),
                    'ppp.objectjenispetugaspefk  as ppp',
                    'pppr.objectjenispetugaspefk as pppr'
                )
                ->join('antrianpasiendiperiksa_t AS apd', 'apd.noregistrasifk', 'pd.norec')
                ->join('pelayananpasien_t as pp', 'pp.noregistrasifk', 'apd.norec')
                ->leftJoin('pelayananpasienpetugas_t as ppp', function ($join) {
                    $join->on('ppp.pelayananpasien', 'pp.norec');
                })
                ->leftJoin('pelayananpasienpetugas_t as pppr', function ($join) {
                    $join->on('pppr.pelayananpasien', 'pp.norec');
                })
                ->join('pasien_m AS ps', 'ps.id', 'pd.nocmfk')
                ->join('jeniskelamin_m AS jk', 'jk.id', 'ps.objectjeniskelaminfk')
                ->join('ruangan_m AS  rg', 'rg.id', 'pd.objectruanganlastfk')
                ->leftJoin('kelompokpasien_m AS klp', 'klp.id', 'pd.objectkelompokpasienlastfk')
                ->leftJoin('produk_m AS pro', 'pro.id', 'pp.produkfk')
                ->join('detailjenisproduk_m as djp', 'djp.id', 'pro.objectdetailjenisprodukfk')
                ->leftJoin('strukorder_t AS so', 'so.norec', 'apd.objectstrukorderfk')
                ->leftJoin('ruangan_m AS ru1', 'ru1.id', 'so.objectruanganfk')
                ->leftJoin('ruangan_m AS ru2', 'ru2.id', 'apd.objectruanganasalfk')
                ->leftJoin('ruangan_m AS ru3', 'ru3.id', 'apd.objectruanganfk')
                ->leftJoin('batalregistrasi_t AS  br', 'br.pasiendaftarfk', 'pd.norec')
                ->leftJoin('pegawai_m AS pg', 'pg.id', 'pd.objectpegawaifk')
                ->leftJoin('pegawai_m AS pg1', 'pg1.id', 'ppp.objectpegawaifk')
                ->join('pegawai_m AS pg2', 'pg2.id', 'pppr.objectpegawaifk')
                ->leftJoin('hasilradiologi_t as hr',  function ($join) {
                    $join->on('hr.pelayananpasienfk', 'pp.norec');
                    $join->on('hr.statusenabled', '=', DB::raw('\'t\''));
                })
                ->where('pd.kdprofile', $kdProfile)
                ->whereNotNull('pro.namaproduk')
                ->where('br.norec', null)
                ->where('ru3.objectdepartemenfk', $this->settingFix('idDepartemenRadiologi'))
                // $this->settingFix('idDepartemenRadiologi')
                // ->where('apd.objectruanganfk', 78)
                ->when($kelompokpasien, function ($query) use ($kelompokpasien) {
                    return $query->where('klp.id', $kelompokpasien);
                })
                ->when($dokterdpjp, function ($query) use ($dokterdpjp) {
                    return $query->where('pg.id', $dokterdpjp);
                })
                ->when($searchTerm, function ($query) use ($searchTerm) {
                    return $query->where('ps.namapasien', 'like', '%' . $searchTerm . '%');
                })
                ->when($sDokterPemeriksa, function ($query) use ($sDokterPemeriksa) {
                    return $query->where('pg1.id', $sDokterPemeriksa);
                })
                // ->orderBy('apd.tglmasuk', 'DESC')
                // ->where('pd.kdprofile', $kdProfile)
                ->when($tglAwal && $tglAkhir, function ($query) use ($tglAkhir, $tglAwal) {
                    return $query->where('pp.tglpelayanan', '>=', $tglAwal)->where('pp.tglpelayanan', '<=', $tglAkhir);
                })
                ->whereNotNull('hr.tanggal')
                ->get();
            $result = [
                'status' => 200,
                'message' => 'success',
                'data' => $data
            ];
        } catch (Exception $e) {
            $result = [
                "status" => 400,
                "message" =>  $e->getMessage() . $e->getLine(),
                "data"  => []
            ];
        }
        return $this->respond($result['data'], $result['status'], $result['message']);
    }

    public function getLaporanRekapTindakanRadiologi(Request $request)
    {
        $kdProfile          = $this->kdProfile;
        $kelompokpasien     = $request->kelompokpasien;
        $tglAwal            = Carbon::parse($request['tglAwal'])->format('Y-m-d') ?? date('Y-m-d');
        $tglAkhir           = Carbon::parse($request['tglAkhir'])->format('Y-m-d') ?? date('Y-m-d');
        $kelompokpasien     = $request['kelompokpasien'] == "undefined" ? "" : $request['kelompokpasien'];
        $dokterdpjp         = $request['dokterdpjp'] == "undefined" ? "" : $request['dokterdpjp'];
        $sDokterPemeriksa   = $request['dokter'] == "" ? "" : "and pg1.id =" . $request['dokter'];
        $rangeDate          = [$tglAwal, $tglAkhir];
        try {
            $data = collect(DB::select("	select pr.namaproduk,pg1.namalengkap as dokter,djp.detailjenisproduk, count(hr.norec) as qty
            from hasilradiologi_t as hr
            JOIN pegawai_m AS pg1 ON pg1.id = hr.pegawaifk
            join pelayananpasien_t as pp on pp.norec=hr.pelayananpasienfk
            join produk_m as pr on pr.id =pp.produkfk
             INNER JOIN detailjenisproduk_m AS djp ON djp.id = pr.objectdetailjenisprodukfk
             where hr.tanggalreport >= '$tglAwal 00:00:00'
             AND hr.tanggalreport <= '$tglAkhir 23:59:59'
             $sDokterPemeriksa
             and hr.statusenabled=true
             group by  pr.namaproduk,pg1.namalengkap,djp.detailjenisproduk
           "));

            $result = [
                'status' => 200,
                'message' => 'success',
                'data' => $data
            ];
        } catch (Exception $e) {
            $result = [
                "status" => 400,
                "message" =>  $e->getMessage() . $e->getLine(),
                "data"  => []
            ];
        }
        return $this->respond($result['data'], $result['status'], $result['message']);
    }
    public function saveDraftExpertise (Request $req)
    {
        try{
            DB::beginTransaction();
           
            $cekData=DB::table('expertise_draft')->where('statusenabled',true)->where('norec_so_fk',$req['norec_so'])->select(
                'id',
                'draft_expertise',
                'objectpegawaifk',
                'tanggal'
            )->first();

            if(!empty($cekData)){

                $updateRow=DB::table('expertise_draft')->where('id',$cekData->id)->update(
                    [
                        'draft_expertise'=>$req['draft'] == null ? $cekData->draft_expertise : $req['draft'] ,
                        'objectpegawaifk'=>$req['pegawaifk'] == null ? $cekData->objectpegawaifk : $req['pegawaifk'],
                        'tanggal'=>$req['tanggal'] == null ? $cekData->tanggal : $req['tanggal'] 
                    ]
                );
            }
            else{
                $newDraft= new ExpertiseDraft();
                $newDraft->id = $newDraft->generateNewId();
                $newDraft->statusenabled = true;
                $newDraft->norec_so_fk = $req['norec_so'];
                $newDraft->draft_expertise = $req['draft'];
                $newDraft->objectpegawaifk = $req['pegawaifk'];
                $newDraft->tanggal = $req['tanggal'];
                $newDraft->save();
            }
            DB::commit();
            $result=array(
                "code"=>200,
                "message"=>'Save Draft Success',
                "data"=>!empty($cekData) ? $updateRow : $newDraft,
            );
        }
        catch(Exception $e){
            DB::rollBack();
            $result=array(
                "code"=>400,
                "message"=>'Something Went Wrong',
                "data"=>null,
                "hint"=> $e->getMessage() . $e->getLine()
            );
        }
        return $this->respond($result);
    }
    public function laporanTransaksiRadiologi(Request $req){

        $startDate=$req['tglAwal']. ' 00:00';
        $endDate=$req['tglAkhir'].' 23:59';

        $data = DB::table('pelayananpasien_t as pp')
            ->join('antrianpasiendiperiksa_t as apd', 'apd.norec', '=', 'pp.noregistrasifk')
            ->join('strukorder_t as so','so.norec','=','pp.strukorderfk')
            ->join('pasiendaftar_t as pd', 'pd.norec', '=', 'apd.noregistrasifk')
            ->join('produk_m as pr', 'pr.id', '=', 'pp.produkfk')
            ->join('detailjenisproduk_m as djp', 'djp.id', '=', 'pr.objectdetailjenisprodukfk')
            ->join('kelompokpasien_m as km', 'km.id', '=', 'pd.objectkelompokpasienlastfk')
            ->join('pasien_m as pm', 'pm.norec', '=', 'pd.nocmfk')
            ->join('kebangsaan_m as kbm', 'kbm.id', '=', 'pm.objectkebangsaanfk')
            ->join('ruangan_m as rux','rux.id','=','so.objectruanganfk')
            ->select(
                'pr.namaproduk',
                'pr.namaexternal',
                'rux.namaruangan',
                DB::raw('SUM(pp.jumlah) as JumlahPemeriksaan'),
                DB::raw('SUM(CASE WHEN pd.objectkelompokpasienlastfk = 2 AND pm.objectkebangsaanfk = 1 THEN pp.jumlah ELSE 0 END) as BPJS'),
                DB::raw('SUM(CASE WHEN pd.objectkelompokpasienlastfk = 3 AND pm.objectkebangsaanfk = 1 THEN pp.jumlah ELSE 0 END) as IKS'),
                DB::raw('SUM(CASE WHEN pd.objectkelompokpasienlastfk = 1 AND pm.objectkebangsaanfk = 1 THEN pp.jumlah ELSE 0 END) as UMUM'),
                DB::raw('SUM(CASE WHEN pm.objectkebangsaanfk != 1 THEN pp.jumlah ELSE 0 END) as WNA'),
                DB::raw('SUM(CASE WHEN pd.objectkelompokpasienlastfk = 2 AND pm.objectkebangsaanfk = 1 THEN pp.hargasatuan ELSE 0 END) as PendapatanBPJS'),
                DB::raw('SUM(CASE WHEN pd.objectkelompokpasienlastfk = 3 AND pm.objectkebangsaanfk = 1 THEN pp.hargasatuan ELSE 0 END) as PendapatanIKS'),
                DB::raw('SUM(CASE WHEN pd.objectkelompokpasienlastfk = 1 AND pm.objectkebangsaanfk = 1 THEN pp.hargasatuan ELSE 0 END) as PendapatanUMUM'),
                DB::raw('SUM(CASE WHEN pm.objectkebangsaanfk != 1 THEN pp.hargasatuan ELSE 0 END) as PendapatanWNA'),
                DB::raw('SUM(CASE WHEN pd.objectkelompokpasienlastfk = 2 AND pm.objectkebangsaanfk = 1 THEN pp.hargasatuan ELSE 0 END) +
                        SUM(CASE WHEN pd.objectkelompokpasienlastfk = 3 AND pm.objectkebangsaanfk = 1 THEN pp.hargasatuan ELSE 0 END) +
                        SUM(CASE WHEN pd.objectkelompokpasienlastfk = 1 AND pm.objectkebangsaanfk = 1 THEN pp.hargasatuan ELSE 0 END) +
                        SUM(CASE WHEN pm.objectkebangsaanfk != 1 THEN pp.hargasatuan ELSE 0 END) as total')
            )
            ->where('apd.statusenabled', true)
            ->where('pp.statusenabled', true)
            ->whereIn('djp.id', explode(',',$this->settingFix('idProdukLaporanRadiologi')))
            ->where('apd.objectruanganfk', 330)
            ->whereBetween('pp.tglpelayanan',[$startDate, $endDate])
            ->groupBy('pr.id', 'pr.namaproduk','rux.namaruangan')
            ->orderBy('pr.namaexternal')
            ->get();

        return $this->respond($data);
    }
    public function laporanKunjunganRadiologi(Request $req){

        $startDate=$req['tglAwal']. ' 00:00';
        $endDate=$req['tglAkhir'].' 23:59';

        $data = DB::table('pasien_m as ps')
            ->join('pasiendaftar_t as pd', 'pd.nocmfk', '=', 'ps.id')
            ->join('antrianpasiendiperiksa_t as apd', 'apd.noregistrasifk', '=', 'pd.norec')
            ->join('strukorder_t as so', 'so.norec_apd', '=', 'apd.norec')
            ->join('pelayananpasien_t as pp', 'pp.strukorderfk', '=', 'so.norec')
            ->join('produk_m as pr', 'pr.id', '=', 'pp.produkfk')
            ->join('ruangan_m as ru', 'ru.id', '=', 'so.objectruanganfk')
            ->join('ruangan_m as ruapd', 'ruapd.id', '=', 'apd.objectruanganfk')
            ->join('jeniskelamin_m as jk', 'jk.id', '=', 'ps.objectjeniskelaminfk')
            ->join('pegawai_m as pg', 'pg.id', '=', 'so.objectpegawaiorderfk')
            ->join('kelompokpasien_m as klmp', 'klmp.id', '=', 'pd.objectkelompokpasienlastfk')
            ->select([
                'ps.namapasien',
                'ps.nocm',
                'jk.jeniskelamin',
                'pr.namaproduk',
                DB::raw("EXTRACT(YEAR FROM AGE(ps.tgllahir)) as umur"),
                'so.catatanklinis',
                'pg.namalengkap as namadokterpengirim',
                'ru.namaruangan as namaruanganpengorder',
                'klmp.kelompokpasien',
                'pp.tglpelayanan'
            ])
            ->where('apd.objectruanganfk', 330)
            ->where('pp.statusenabled', true)
            ->where('so.statusenabled', true)
            ->where('apd.statusenabled', true)
            ->where('pd.statusenabled', true)
            ->whereBetween('pp.tglpelayanan', [$startDate, $endDate])
            ->distinct()
            ->get();

        return $this->respond($data);
    }

    public function saveTemplate(Request $request)
    {
        DB::beginTransaction();
        try {
            $kdProfile = $this->kdProfile;
            $nama = $request->input('nama');
            $hasil = $request->input('hasil');
            $kelompok = $request->input('kelompok');

            $existing = DB::table('templateexpertiseecho_m')
                ->where('kdprofile', $kdProfile)
                ->where('nama', $nama)
                ->first();

            if ($existing) {
                DB::table('templateexpertiseecho_m')
                    ->where('id', $existing->id)
                    ->update([
                        'template' => $hasil,
                    ]);

                $result = [
                    'status' => 200,
                    'message' => 'Template berhasil diperbarui!',
                ];
            } else {
                $lastId = DB::table('templateexpertiseecho_m')->max('id');
                $newId = $lastId ? $lastId + 1 : 1;

                DB::table('templateexpertiseecho_m')->insert([
                    'id' => $newId,
                    'kdprofile' => $kdProfile,
                    'statusenabled' => true,
                    'reportdisplay' => $nama,
                    'nama' => $nama,
                    'template' => $hasil,
                    'kelompok' => $kelompok,
                ]);

                $result = [
                    'status' => 200,
                    'message' => 'Template berhasil disimpan!',
                ];
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            $result = [
                'status' => 400,
                'message' => 'Terjadi kesalahan saat menyimpan template!',
                'error' => $e->getMessage(),
            ];
        }
        return $this->respond($result, $result['status'], $result['message']);
    }
}
