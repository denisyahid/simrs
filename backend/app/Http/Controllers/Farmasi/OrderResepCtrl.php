<?php

namespace App\Http\Controllers\Farmasi;

use App\Http\Controllers\Controller;
use App\Models\Master\Ruangan;
use App\Models\Transaksi\OrderPelayanan;
use App\Models\Transaksi\StrukOrder;
use App\Models\Transaksi\PermohonanAlat;
use Illuminate\Http\Request;
use App\Models\Transaksi\StrukResep;
use App\Models\Transaksi\StrukRetur;
use App\Models\Transaksi\KartuStok;
use App\Models\Transaksi\PelayananPasien;
use App\Models\Transaksi\PelayananPasienDetail;
use App\Models\Transaksi\PelayananPasienObatKronis;
use App\Models\Transaksi\PelayananPasienPetugas;
use App\Models\Transaksi\PelayananPasienRetur;
use App\Models\Transaksi\StokProdukDetail;
use App\Models\Transaksi\StrukReturPerawat;
use App\Models\Transaksi\PpraHistoryTransaksi;
use Illuminate\Support\Facades\DB;
use App\Models\Transaksi\AntrianApotik;

use App\Traits\Valet;
use Exception;
use Ramsey\Uuid\Uuid;

class OrderResepCtrl extends Controller
{
    use Valet;
    public function __construct()
    {
        parent::__construct($is_encrypt = true);
    }
    public function simpanOrderResep(Request $request)
    {

        DB::beginTransaction();
        try {

            $kdProfile = (int) $this->kdProfile;
            foreach ($request['data'] as $data) {
                $r_SR = $data['strukorder'];

                $dataDetail = DB::table('antrianpasiendiperiksa_t as apd')
                    ->JOIN('pasiendaftar_t as pd', 'pd.norec', '=', 'apd.noregistrasifk')
                    ->select('pd.norec', 'pd.nocmfk', 'apd.objectruanganfk', 'pd.objectkelasfk', 'apd.norec as apdnorec')
                    ->where('apd.norec', $r_SR['noregistrasifk'])
                    ->first();

                if(empty($dataDetail)){
                    $transMessage = "Data Tidak Ada";
                    DB::commit();
                    $result = array(
                        "status" => 200,
                        "result" => array(
                            "as" => '@epic',
                        ),
                    );

                    return $this->respond($result['result'], $result['status'], $transMessage);
                }
                $pasien = DB::table('pasien_m')->where('id', $dataDetail->nocmfk)->first();
                $newNorecSo='';

                $set = json_decode($this->settingFix('kelompokTransaksiOrderResep'));
                if ($r_SR['norec'] == '') {
                    $noOrder = $this->SEQUENCE(new StrukOrder(), $set->seqname, 10, date('ym'), $kdProfile);
                    if ($noOrder == '') {
                        $transMessage = "Gagal mengumpukan data, Coba lagi.!";
                        DB::rollBack();
                        $result = array("status" => 400, "result"  => null);
                        return $this->respond($result['result'], $result['status'], $transMessage);
                    }

                    $newSO = new StrukOrder();
                    $newSO->norec = $newSO->generateNewId();
                    $newNorecSo=$newSO->norec;
                    $newSO->kdprofile = $kdProfile;
                    $newSO->statusenabled = true;
                } else {
                    $newSO = StrukOrder::where('norec', $r_SR['norec'])->first();
                    $noOrder = $newSO->noorder;
                    OrderPelayanan::where('noorderfk', $newSO->norec)->delete();
                }


                $newSO->nocmfk = $dataDetail->nocmfk;
                $newSO->kddokter = $r_SR['penulisresepfk'];
                $newSO->isantibiotik = $r_SR['isantibiotik'];
                $newSO->isdelivered = 1;
                $newSO->objectkelompoktransaksifk = $set->objectkelompoktransaksifk;
                $newSO->keteranganorder =  $set->keteranganorder;
                $newSO->noorder = $noOrder;
                $newSO->noregistrasifk = $dataDetail->norec;
                $newSO->norec_apd = $r_SR['noregistrasifk'];
                $newSO->objectpegawaiorderfk = $r_SR['penulisresepfk'];
                $newSO->qtyproduk = $r_SR['qtyproduk'];
                $newSO->qtyjenisproduk = $r_SR['qtyproduk'];
                $newSO->objectruanganfk = $dataDetail->objectruanganfk;
                $newSO->objectruangantujuanfk = $r_SR['ruanganfk'];
                $newSO->statusorder = $set->statusorder;
                $newSO->tglorder = $r_SR['tglresep'];
                $newSO->cito = $r_SR['cito'];
                $newSO->isrutin = $r_SR['isrutin'];
                $newSO->alergiobat = $r_SR['alergiobat'];
                $newSO->isbpl = $r_SR['isbpl'];
                $newSO->totalbeamaterai = 0;
                $newSO->totalbiayakirim = 0;
                $newSO->totalbiayatambahan = 0;
                $newSO->totaldiscount = 0;
                $newSO->totalhargasatuan = 0;
                $newSO->totalharusdibayar = 0;
                $newSO->totalpph = 0;
                $newSO->totalppn = 0;
                $newSO->isreseppulang = $r_SR['isreseppulang'];
                $newSO->nourutruangan = isset($r_SR['noruangan']) ? $r_SR['noruangan'] : null;
                $newSO->isambilobat = isset($r_SR['isambilobat']) ? $r_SR['isambilobat'] : null;
                $newSO->jarak_kirim =  isset($r_SR['jarak_kirim']) ? $r_SR['jarak_kirim'] : null;
                $newSO->alamat_kirim =  isset($r_SR['alamat_kirim']) ? $r_SR['alamat_kirim'] : null;
                $newSO->harga_kirim =  isset($r_SR['harga_kirim']) ? $r_SR['harga_kirim'] : null;
                $newSO->no_hp =  isset($r_SR['no_hp']) ? $r_SR['no_hp'] : null;
                $newSO->iskurir = isset($r_SR['iskurir']) ? $r_SR['iskurir'] : null;
                $newSO->noregistrasi = $data['noregistrasi'];
                $newSO->save();
                $norec_SR = $newSO->norec;

                $r_PP = $data['orderpelayanan'];
                $prod = [];
                foreach ($r_PP as $r_PPL) {
                    $r_PPL['nilaikonversi']=    isset( $r_PPL['nilaikonversi']) ? $r_PPL['nilaikonversi'] :1;
                    $qtyJumlah = (float)$r_PPL['jumlah'] * (float)$r_PPL['nilaikonversi'];
                    // $pro = DB::table('produk_m')->where('id', $r_PPL['produkfk'])->first();
                    $prod[] =  $r_PPL['produkfk'];
                    $newPP = new OrderPelayanan();
                    $norecPP = $newPP->generateNewId();
                    $newPP->norec = $norecPP;
                    $newPP->kdprofile = $kdProfile;
                    $newPP->statusenabled = true;
                    $newPP->aturanpakai = $r_PPL['aturanpakai'];
                    $newPP->isreadystok = 1;
                    $newPP->kddokter = $r_SR['penulisresepfk'];;
                    $newPP->objectkelasfk = $dataDetail->objectkelasfk;;
                    $newPP->nocmfk = $dataDetail->nocmfk;
                    $newPP->noorderfk = $norec_SR;
                    $newPP->noregistrasifk = $dataDetail->norec;
                    $newPP->objectprodukfk = $r_PPL['produkfk'];
                    if (isset($r_PPL['jumlahxmakan'])) {
                        $newPP->qtykemasan = $r_PPL['jumlahxmakan'];
                    }
                    $newPP->qtyproduk = $r_PPL['jumlah'];
                    $newPP->qtystokcurrent = $r_PPL['jmlstok'];
                    $newPP->racikanke = $r_PPL['rke'];
                    $newPP->objectruanganfk = $dataDetail->objectruanganfk;
                    // $newPP->objectruangantujuanfk = $r_PPL['ruanganfk'];
                    // $newPP->objectsatuanstandarfk = $r_PPL['satuanstandarfk'];
                    $newPP->objectruangantujuanfk = $r_SR['ruanganfk'];
                    $newPP->objectsatuanstandarfk = $r_PPL['satuanviewfk'];
                    $newPP->strukorderfk = $norec_SR;
                    $newPP->tglpelayanan = $r_SR['tglresep'];
                    $newPP->jenisobatfk = isset($r_PPL['jenisobatfk']) ? $r_PPL['jenisobatfk'] : null;
                    $newPP->jumlah =ceil($qtyJumlah) ;
                    // $newPP->jumlah = $r_PPL['jumlah'];
                    $newPP->iscito = 0;
                    $newPP->isbpl = 0;
                    $newPP->isrutin = 0;
                    $newPP->stokprodukdetailfk = isset($r_PPL['norec_spd']) ? $r_PPL['norec_spd'] : null;
                    $newPP->alergiobat =  $r_SR['alergiobat'];
                    $newPP->hargasatuan = $r_PPL['hargasatuan'];
                    $newPP->hargadiscount = $r_PPL['hargadiscount'];
                    $newPP->qtyprodukretur = 0;
                    $newPP->hasilkonversi = isset( $r_PPL['nilaikonversi']) ? $r_PPL['nilaikonversi'] :1;
                    $newPP->jeniskemasanfk = $r_PPL['jeniskemasanfk'];
                    $newPP->dosis = $r_PPL['dosis'];
                    $newPP->rke = $r_PPL['rke'];
                    $newPP->satuanviewfk = $r_PPL['satuanviewfk'];
                    $newPP->ispagi = isset($r_PPL['ispagi']) ? $r_PPL['ispagi'] : null;
                    $newPP->issiang =  isset($r_PPL['issiang']) ? $r_PPL['issiang'] : null;
                    $newPP->ismalam = isset($r_PPL['ismalam']) ? $r_PPL['ismalam'] : null;
                    $newPP->issore =  isset($r_PPL['issore']) ? $r_PPL['issore'] : null;
                    $newPP->keteranganpakai = $r_PPL['keterangan'];
                    $newPP->satuanresepfk = isset($r_PPL['satuanresepfk']) ? $r_PPL['satuanresepfk'] : null;
                    
                    $newPP->tglkadaluarsa = isset($r_PPL['tglkadaluarsa']) ? $r_PPL['tglkadaluarsa'] : null;
                    $newPP->isoutofstok =  isset($r_PPL['isoutofstok']) ? $r_PPL['isoutofstok'] : null;
                    $newPP->nostrukterimafk = isset($r_PPL['nostrukterimafk']) ? $r_PPL['nostrukterimafk'] : null;
                    $newPP->objectasalprodukfk = isset($r_PPL['asalprodukfk']) ? $r_PPL['asalprodukfk'] : null;
                    $newPP->noregistrasi = $data['noregistrasi'];
                    $newPP->racikan = $r_PPL['jumlahobat'];
                    $newPP->iskronis30 = isset($r_PPL['iskronis30']) ? $r_PPL['iskronis30'] : null;
                    $newPP->iskronis23 = isset($r_PPL['iskronis23']) ? $r_PPL['iskronis23'] : null;
                    $newPP->save();
                }
            }
             $pro = DB::table('produk_m')->whereIn('id', $prod)->get();

            // $statusBot = false;
            // $telegram_id = '';
            // foreach ($request['data'] as $data) {
            //     if ($data['strukorder']['ruanganfk'] == 94) {
            //         $telegram_id = '-437464365';
            //         $statusBot = true;
            //     }
            //     if ($data['strukorder']['ruanganfk'] == 116) {
            //         $telegram_id = '-573225350';
            //         $statusBot = true;
            //     }
            //     if ($data['strukorder']['ruanganfk'] == 125) {
            //         $telegram_id = '-598519318';
            //         $statusBot = true;
            //     }
            //     if ($data['strukorder']['ruanganfk'] == 556) {
            //         $telegram_id = '-443840485';
            //         $statusBot = true;
            //     }
            //     if ($data['strukorder']['ruanganfk'] == 744) {

            //         $telegram_id = '-1001433844424';
            //         $statusBot = true;
            //     }
            //     $tglresep = $data['strukorder']['tglresep'];
            //     if ($statusBot) {
            //         $setting = $this->settingDataFixed('settingOrderTelegramLab', $idProfile);
            //         if (!empty($setting) &&  $setting == 'true') {
            //             // $from =  DB::table('ruangan_m')->where('id',$request['objectruanganfk'])->first();
            //             $to =  DB::table('ruangan_m')->where('id', $data['strukorder']['ruanganfk'])->first();
            //             $peg =  DB::table('pegawai_m')->where('id',  $dokter2)->first();
            //             $cito = '';
            //             if (isset($request['iscito']) && $request['iscito'] == 'true') {
            //                 $cito = ' Cito ';
            //             }
            //             if ($data['strukorder']['isreseppulang'] == 1) {
            //                 $cito = ' RESEP PULANG ';
            //             }
            //             $secret_token = "1545548931:AAHwGMJrXxGMc609WwO9e2UQTcRquu5ri-M";
            //             $produks = '';
            //             foreach ($prod as $key => $value) {
            //                 $produks = $produks . " \n " . $value;
            //             }

            //             $url = "https://api.telegram.org/bot" . $secret_token . "/sendMessage?parse_mode=html&chat_id=" . $telegram_id;
            //             $url = $url . "&text=" . urlencode("✅ Order Baru : <b> " . $to->namaruangan .
            //                 "</b>  \n Pengorder : <b>" . $peg->namalengkap . "</b>  \n Pasien : <b>" . $pasien->namapasien . " (" . $pasien->nocm . ") " .
            //                 "</b> \n Tgl Order: <b>" . $tglresep .
            //                 "</b> \n Status : <b>" . $cito . "</b>  \n Nama Obat : <b>" . $produks . "</b> \n\n");
            //             // return $url;
            //             $ch = curl_init();
            //             $optArray = array(
            //                 CURLOPT_URL => $url,
            //                 CURLOPT_RETURNTRANSFER => true
            //             );
            //             curl_setopt_array($ch, $optArray);
            //             $result = curl_exec($ch);
            //             curl_close($ch);
            //         }
            //     }
            // }
            $ruangan = Ruangan::where('id', $dataDetail->objectruanganfk)->first();
            $ruangan2 = Ruangan::where('id', $r_SR['ruanganfk'])->first();
            $mergeProduk ='';
            foreach ($pro as $value) {
                $mergeProduk = $mergeProduk .", ". $value->namaproduk;
            }
            $mergeProduk = substr($mergeProduk, 1, strlen($mergeProduk)-1);
            $this->LOGGING(
                'Order Resep',
                $newSO->norec,
                'strukorder_t',
                'Order Resep ' .$noOrder.
                $mergeProduk
                . ' dari ' .$ruangan->namaruangan
                . ' ke ' .$ruangan2->namaruangan . ' pada Pasien ' .
                $pasien->namapasien . ' (' .   $pasien->nocm . ') - ' .
                $data['noregistrasi']
            );
            $transStatus = 'true';
            if(isset($r_SR['flag']) && $r_SR['flag'] == true){
                $functionSavePppraHsitory=$this->savePPRAHistory($r_SR,$newNorecSo,$r_PP);
            }
        } catch (Exception $e) {
            $transStatus = 'false';
        }
        if ($transStatus == 'true') {
            $transMessage = "Sukses";
            DB::commit();
            $objetoRequest = new \Illuminate\Http\Request();
            $objetoRequest['noorder'] = $noOrder;
            $ihs = app('App\Http\Controllers\Bridging\SATUSEHATCtrl')->MedicationRequest($objetoRequest, true);
            $result = array(
                "status" => 200,
                "result" => array(
                    "data" =>$newSO,
                    "executeFunctionHistory" => isset($r_SR['flag']) && $r_SR['flag'] == true ? $functionSavePppraHsitory : null,
                    "as" => '@epic',
                    "medicationRequest" => $ihs
                ),
            );
        } else {
            $transMessage = "Simpan Gagal";
            DB::rollBack();
            $result = array(
                "status" => 400,
                "result"  => $e->getMessage() . ' '. $e->getLine(). ' '. $e->getFile()
            );
        }
        return $this->respond($result['result'], $result['status'], $transMessage);
    }

    private function savePPRAHistory ($so,$valueSaveSo,$valueSaveOp){
        try {
            DB::beginTransaction();
            foreach ($valueSaveOp as  $dm) {
            $newPPra=new PpraHistoryTransaksi();
            $norecPPr = $newPPra->generateNewId();
            $newPPra->norec = $norecPPr;
            $newPPra->kdprofile = 1;
            $newPPra->statusenabled = true;
            $newPPra->strukorderfk = $valueSaveSo;
            $newPPra->noregistrasifk = $so['nopasiendaftarfk'];
            $newPPra->objectgenerikfk = $dm['objectgenerikfk'];
            $newPPra->qtyorder = $dm['jumlah'];
            $newPPra->objectprodukfk = $dm['produkfk'];
            $newPPra->objectppradivisifk = 0;
            // $newPPra->objectppraantibiotikfk = 0;
            $newPPra->objectppratindakanfk = $so['ppratindakan'];
            $newPPra->objectjenisoperasifk = $so['jenisoperasi'];
            $newPPra->tglhasil = $so['tglhasil'];
            $newPPra->jenissampel = $so['jenissampel'];
            $newPPra->hasil = $so['hasil'];
            $newPPra->rekomendasi = $so['rekomendasi'];
            $newPPra->antibiotik = $so['jenisobat'];
    
            $newPPra->save();
            }

            
            DB::commit();
            $result=array(
                'data'=>$newPPra,
                'code'=>200,
                'message'=>'sukses create data'
            );
        } catch (Exception $e) {
            DB::rollBack();
            $result=array(
                'data'=>null,
                'code'=>400,
                'message'=>'data not created',
                'hint'=>$e->getLine() . ' '. $e->getMessage()
            );   
        }
        return $this->respond($result);
    }

    public function getHistoryPpra (Request $re){
        $data = PpraHistoryTransaksi::where('noregistrasifk', $re['noregistrasifk'])
            ->select('objectgenerikfk', DB::raw('SUM(qtyorder) as qtyorder'))
            ->where('statusenabled', true)
            ->groupBy('objectgenerikfk')
            ->get();

        $result = array(
            'key' => $data,
        );

        return $this->respond($result);
    }

    public function riwayatOrderResep(Request $request)
    {
        $idProfile = $this->kdProfile;
        $nocmfk = '';
        $norec_pd = '';
        $penulisresep = '';
        $set = json_decode($this->settingFix('kelompokTransaksiOrderResep'));
        $data = DB::table('strukorder_t as so')
            ->JOIN('pasiendaftar_t as pd', 'pd.norec', '=', 'so.noregistrasifk')
            ->JOIN('ruangan_m as ru', 'ru.id', '=', 'so.objectruanganfk')
            ->JOIN('ruangan_m as ru2', 'ru2.id', '=', 'so.objectruangantujuanfk')
            ->leftJOIN('statuspengerjaan_m as sp', 'sp.id', '=', 'so.statusorder')
            ->leftJOIN('pegawai_m as pg', 'pg.id', '=', 'so.objectpegawaiorderfk')
            ->leftjoin('strukresep_t as sr', function ($join) {
                $join->on('sr.orderfk', '=', 'so.norec');
                $join->where('sr.statusenabled',true);
            })
            ->select(
                'so.norec as norec_so',
                'so.noorder',
                'so.isantibiotik',
                'ru.namaruangan as namaruanganrawat',
                'so.tglorder',
                'so.tglorder as tglorder_def',
                'pg.namalengkap',
                'ru2.namaruangan',
                'so.statusorder',
                'so.namapengambilorder',
                'so.noregistrasifk',
                'pd.noregistrasi',
                'pd.tglregistrasi',
                'so.tglambilorder',
                'so.norec as norec_order',
                'so.isreseppulang',
                'so.isambilobat',
                'so.iskurir',
                'so.cito',
                'so.isrutin',
                'so.isbpl',
                'sp.statuspengerjaan',
                'sp.reportdisplay as color_status',
                'so.norec_apd',
                'so.objectruangantujuanfk',
                'so.objectpegawaiorderfk',
                'so.alergiobat',
                'sr.norec as norec_resep',
                'sr.petugas as nama_pegawai_verifikator'
            )
            ->where('so.kdprofile', $idProfile)
            ->where('so.statusenabled', true)
            // ->where(function ($query) {
            // $query->where('so.isantibiotik', '!=', true)
            //         ->orWhereNull('so.isantibiotik');
            // })
            ->where('so.objectkelompoktransaksifk', $set->objectkelompoktransaksifk);

        if (isset($request['nocmfk']) && $request['nocmfk'] != "" && $request['nocmfk'] != "undefined") {
            $data = $data->where('pd.nocmfk', '=', $request['nocmfk']);
            $nocmfk = " and pd.nocmfk='" . $request['nocmfk'] . "'";
        }
        if (isset($request['norec_pd']) && $request['norec_pd'] != "" && $request['norec_pd'] != "undefined") {
            $data = $data->where('pd.norec', $request['norec_pd']);
            $norec_pd =  " and pd.norec='" . $request['norec_pd'] . "'";
        }
        if (isset($request['penulisresepfk']) && $request['penulisresepfk'] != "" && $request['penulisresepfk'] != "undefined") {
            $data = $data->where('so.objectpegawaiorderfk', $request['penulisresepfk']);
            $penulisresep =  " and so.objectpegawaiorderfk='" . $request['penulisresepfk'] . "'";
        }
        if (isset($request['limit']) && $request['limit'] != "" && $request['limit'] != "undefined") {
            $data = $data->limit($request['limit']);

        }
        $data = $data->orderByDesc('so.tglorder');
        $data = $data->get();


        $details = DB::select(
            DB::raw("
                SELECT
                so.norec as norec_so,
                op.rke, jk.jeniskemasan,
                pr.namaproduk, ss.satuanstandar, op.aturanpakai,
                op.jumlah, op.hargasatuan,op.keteranganpakai as keterangan,
                op.satuanresepfk,sn.satuanresep,op.tglkadaluarsa,
                op.qtystokcurrent as jmlstok,
                op.racikan,pr.objectsubkategoryfk,
                CASE 
                        WHEN pt.antibiotik = 'Obat Empiris' THEN ptn.namatindakan
                        WHEN pt.antibiotik = 'Obat Profilaksis' THEN pjo.namaoperasi
                        ELSE '' 
                    END as DiagnosaTindakan,
                op.jeniskemasanfk,jk.id as jkid,op.routefk,rt.name as namaroute,
                op.objectprodukfk,pr.namaproduk,op.hasilkonversi,
                op.objectsatuanstandarfk,ss.satuanstandar,op.satuanviewfk,ss2.satuanstandar as ssview,
                op.qtyproduk,op.hargadiscount,op.hasilkonversi as nilaikonversi,op.dosis,op.jenisobatfk,
                pr.kekuatan,sdn.name as sediaan,op.ispagi,op.issiang,op.ismalam,
                op.issore,op.keteranganpakai,op.satuanresepfk,sn.satuanresep,op.tglkadaluarsa,op.nostrukterimafk,
                op.objectasalprodukfk as asalprodukfk,asl.asalproduk,
                (select sum(qtyproduk) from stokprodukdetail_t where kdprofile = $idProfile and objectprodukfk = spd.objectprodukfk and statusenabled = true) as totalstok
                from strukorder_t as so
                inner join orderpelayanan_t as op on op.strukorderfk = so.norec
                inner join pasiendaftar_t as pd on pd.norec = so.noregistrasifk
                inner join produk_m as pr on pr.id=op.objectprodukfk
                left join jeniskemasan_m as jk on jk.id=op.jeniskemasanfk
                left join satuanstandar_m as ss on ss.id=op.objectsatuanstandarfk
                left join satuanstandar_m as ss2 on ss2.id=op.satuanviewfk
                left join satuanresep_m as sn on sn.id=op.satuanresepfk
                left join ppra_transaksi as pt on pt.strukorderfk=so.norec
                left join ppra_tindakan as ptn on ptn.id = pt.objectppratindakanfk
                left join ppra_jenisoperasi as pjo on pjo.id = pt.objectjenisoperasifk
                JOIN ruangan_m as ru on ru.id=so.objectruangantujuanfk
                left join routefarmasi as rt on rt.id=op.routefk
                left join rm_sediaan_m as sdn on sdn.id=pr.objectsediaanfk
                left join asalproduk_m as asl on asl.id=op.objectasalprodukfk
                left join stokprodukdetail_t as spd on spd.norec = op.stokprodukdetailfk
                where so.kdprofile = $idProfile
                and so.objectkelompoktransaksifk = $set->objectkelompoktransaksifk
                and so.statusenabled = true
                $nocmfk
                $norec_pd
                $penulisresep
               ")
        );

        $data2 = DB::table('strukresep_t as so')
            ->JOIN('antrianpasiendiperiksa_t as apd', 'apd.norec', '=', 'so.pasienfk')
            ->JOIN('pasiendaftar_t as pd', 'pd.norec', '=', 'apd.noregistrasifk')
            ->JOIN('ruangan_m as ru', 'ru.id', '=', 'apd.objectruanganfk')
            ->JOIN('ruangan_m as ru2', 'ru2.id', '=', 'so.ruanganfk')
            ->leftJOIN('statuspengerjaan_m as sp', 'sp.id', '=', 'so.status')
            ->leftJOIN('pegawai_m as pg', 'pg.id', '=', 'so.penulisresepfk')
            ->select(
                'so.norec as norec_so',
                'so.noresep as noorder',
                'ru.namaruangan as namaruanganrawat',
                'so.tglresep as tglorder',
                'so.tglresep as tglorder_def',
                'pg.namalengkap',
                'ru2.namaruangan',
                'so.isrutin',
                'so.isbpl',
                'so.cito',
                'so.status as statusorder',
                'so.namalengkapambilresep as namapengambilorder',
                'pd.norec as noregistrasifk',
                'pd.noregistrasi',
                'pd.tglregistrasi',
                'so.tglambilresep as tglambilorder',
                'so.orderfk as norec_order',
                'so.isreseppulang',
                DB::raw("  null as isambilobat
            ,null as iskurir"),
                'sp.statuspengerjaan',
                'sp.reportdisplay as color_status',
                'apd.norec as norec_apd',
                'so.ruanganfk as objectruangantujuanfk',
                'so.penulisresepfk as objectpegawaiorderfk'
            )
            ->where('so.kdprofile', $idProfile)
            ->where('so.statusenabled', true)
            ->whereNull('so.orderfk');

        if (isset($request['nocmfk']) && $request['nocmfk'] != "" && $request['nocmfk'] != "undefined") {
            $data2 = $data2->where('pd.nocmfk', '=', $request['nocmfk']);
        }

        if (isset($request['norec_pd']) && $request['norec_pd'] != "" && $request['norec_pd'] != "undefined") {
            $data2 = $data2->where('pd.norec', $request['norec_pd']);
        }
        if (isset($request['penulisresepfk']) && $request['penulisresepfk'] != "" && $request['penulisresepfk'] != "undefined") {
            $data2 = $data2->where('so.penulisresepfk', $request['penulisresepfk']);
        }
        if (isset($request['limit']) && $request['limit'] != "" && $request['limit'] != "undefined") {
            $data2 = $data2->limit($request['limit']);

        }
        $data2 = $data2->orderByDesc('so.tglresep');
        $data2 = $data2->get();

        $details2 = DB::table('pelayananpasien_t as pp')
            ->JOIN('antrianpasiendiperiksa_t as apd', 'apd.norec', '=', 'pp.noregistrasifk')
            ->JOIN('pasiendaftar_t as pd', 'pd.norec', '=', 'apd.noregistrasifk')
            ->JOIN('produk_m as pr', 'pr.id', '=', 'pp.produkfk')
            ->JOIN('ruangan_m as ru', 'ru.id', '=', 'apd.objectruanganfk')
            ->JOIN('jeniskemasan_m as jk', 'jk.id', '=', 'pp.jeniskemasanfk')
            ->leftJoin('jenisracikan_m as jra', 'jra.id', '=', 'pp.jenisracikanfk')
            ->leftJoin('satuanstandar_m as ss', 'ss.id', '=', 'pp.satuanviewfk')
            ->leftJoin('stokprodukdetail_t as spd', 'spd.norec', '=', 'pp.stokprodukdetailfk')
            ->leftJoin('satuanstandar_m as ss2', 'ss2.id', '=', 'pr.objectsatuanstandarfk')
            ->JOIN('detailjenisproduk_m as djp', 'djp.id', '=', 'pr.objectdetailjenisprodukfk')
            ->JOIN('jenisproduk_m as jp', 'jp.id', '=', 'djp.objectjenisprodukfk')
            ->leftJOIN('konversisatuan_t as ks', function ($join) {
                $join->on('ks.objekprodukfk', '=', 'pr.id')
                    ->on('ks.satuanstandar_tujuan', '=', 'pp.satuanviewfk');
            })
            ->leftJOIN('rm_sediaan_m AS sdn', 'sdn.id', '=', 'pr.objectsediaanfk')
            ->leftJoin('satuanresep_m as sn', 'sn.id', '=', 'pp.satuanresepfk')
            ->leftJoin('routefarmasi as rt', 'rt.id', '=', 'pp.routefk')
            ->leftJoin('strukresep_t as so', 'so.norec', '=', 'pp.strukresepfk')
            ->select(DB::raw("pp.strukresepfk as norec_so,
            pp.rke, jk.jeniskemasan,
            pr.namaproduk, ss.satuanstandar, pp.aturanpakai,
            pp.jumlah, pp.hargasatuan,pp.keteranganpakai as keterangan,
            pp.satuanresepfk,sn.satuanresep,pp.tglkadaluarsa,pp.stock as jmlstok,
            pp.racikan,pr.objectsubkategoryfk,
            '' as DiagnosaTindakan,
            pp.jeniskemasanfk,jk.id as jkid,pp.routefk,rt.name as namaroute,
            pp.produkfk as objectprodukfk,pr.namaproduk,pp.nilaikonversi as hasilkonversi,
            pr.objectsatuanstandarfk,ss.satuanstandar,pp.satuanviewfk,ss2.satuanstandar as ssview,
            pp.jumlah as qtyproduk,pp.hargadiscount,pp.nilaikonversi as hasilkonversi,pp.dosis,pp.jenisobatfk,
            pr.kekuatan,sdn.name as sediaan,pp.ispagi,pp.issiang,pp.ismalam,
            pp.issore,pp.keteranganpakai,pp.satuanresepfk,sn.satuanresep,pp.tglkadaluarsa,
            pp.strukterimafk, 
            (select sum(qtyproduk) from stokprodukdetail_t where kdprofile = $idProfile and objectprodukfk = spd.objectprodukfk and statusenabled = true) as totalstok,
            null  as asalprodukfk,null as asalproduk")
            )
            ->where('pp.kdprofile', $this->kdProfile)
            ->whereNotNull('pp.strukresepfk');

        if (isset($request['norec_pd']) && $request['norec_pd'] != "" && $request['norec_pd'] != "undefined") {
            $details2 = $details2->where('apd.noregistrasifk', $request['norec_pd']);
        }
        if (isset($request['penulisresepfk']) && $request['penulisresepfk'] != "" && $request['penulisresepfk'] != "undefined") {
            $details2 = $details2->where('so.penulisresepfk', $request['penulisresepfk']);
        }

        if (isset($request['nocmfk']) && $request['nocmfk'] != "" && $request['nocmfk'] != "undefined") {
            $details2 = $details2->where('pd.nocmfk', $request['nocmfk']);
        }
        $details2 = $details2->orderBy('pp.tglpelayanan');
        $details2 = $details2->orderBy('pp.rke');
        $details2 = $details2->get();

        foreach ($data as $item) {
            $item->details = [];
            if(isset($request['verif'] ) && $request['verif']  == 'true'){
                foreach ($details2 as $itemd) {
                    if ($item->norec_resep == $itemd->norec_so) {
                        $item->details[] = $itemd;
                    }
                }
            }else{
                foreach ($details as $itemd) {
                    if ($item->norec_so == $itemd->norec_so) {
                        $item->details[] = $itemd;
                    }
                }
            }
            // if($item->norec_resep != null){ //ambil ke pelayanapasien kalo udah verif
            //     foreach ($details2 as $itemd) {
            //         if ($item->norec_resep == $itemd->norec_so) {
            //             $item->details[] = $itemd;
            //         }
            //     }
            // }else{
            //     foreach ($details as $itemd) {
            //         if ($item->norec_so == $itemd->norec_so) {
            //             $item->details[] = $itemd;
            //         }
            //     }
            // }

        }

        foreach ($data2 as $item) {
            $item->details = [];
            foreach ($details2 as $itemd) {
                if ($item->norec_so == $itemd->norec_so) {
                    $item->details[] = $itemd;
                }
            }
        }

        // if(count($data2)> 0 && count($data2)> 0){
            $data =  array_merge($data->toArray(),$data2->toArray());
        // }

        // foreach ($data2 as $no => $item) {
        //     $nKonversi = 1;
        //     $item->no = $no + 1;
        //     if ($item->hasilkonversi != null) {
        //         $nKonversi = $item->hasilkonversi;
        //     }
        //     if ($item->satuanstandar != null) {
        //         $ss = $item->satuanstandar;
        //     } else {
        //         $ss = $item->satuanstandar2;
        //     }
        //     $JenisKemasan = $item->jeniskemasan;
        //     if ($item->jenisracikan != null) {
        //         $JenisKemasan = $item->jeniskemasan . '/' . $item->jenisracikan;
        //     }
        //     $jasa = 0;
        //     if ($item->jasa != null && $item->jasa != '') {
        //         $jasa = $item->jasa;
        //     }
        //     $item->jumlah =  (float)$item->jumlah / (float)$nKonversi;
        //     $item->satuanstandar =  $ss;
        //     $item->noregistrasi = $data->noregistrasi;
        //     $item->jasa = $jasa;
        //     $item->jeniskemasan = $JenisKemasan;

        //     $item->total = (((float)$item->jumlah * (float)$item->hargasatuan) - (float)$item->hargadiscount) + (float)     $item->jasa;
        // }


        return $this->respond($data);
    }

    public function saveAlatKuao(Request $request)
    {
        try 
        {
                DB::beginTransaction();
                PermohonanAlat::where('noregistrasifk', $request['noregistrasifk'])->delete();
                $newSO = new PermohonanAlat();
                $newSO->norec = $newSO->generateNewId();
                $newSO->statusenabled = true;
                $newSO->noregistrasifk = $request['noregistrasifk'];
                $newSO->nopasiendaftarfk = $request['nopasiendaftar'];
                $newSO->tglrencanaoperasi = $request['tglrencanaoperasi'];
                $newSO->satelitfk = $request['ruangansatelit'];
                $newSO->kamaroperasi = $request['kamaroperasi'];
                $newSO->diagnosamedis = $request['diagnosamedis'];
                $newSO->rencanatindakan = $request['rencanatindakan'];
                $newSO->rencanaoperasi = $request['rencanaoperasi'];
                $newSO->jenisoperasi = $request['jenisoperasi'];
                $newSO->operator = $request['operator'];
                $newSO->peralatankhusus = $request['peralatankhusus'];
                $newSO->implandipesan = $request['implandipesan'];
                $newSO->pemohon = $request['pemohon'];
                $newSO->farmasi = $request['farmasi'];
                $newSO->kepalaruangan = $request['kepalaruangan'];
                $newSO->norec_emr = $request['norec_emr'];
                $newSO->save();
                DB::commit();
                $result=array(
                    'data'=>$newSO,
                    'code'=>200,
                    'message'=>'sukses create data'
                );
        } 
        catch (Exception $e) 
        {
            DB::rollBack();
            $result=array(
                'data'=>null,
                'code'=>400,
                'message'=>'data not created',
                'hint'=>$e->getLine() . ' '. $e->getMessage()
            );   
        }
        return $this->respond($result);
    }

    public function UpdateAlatKuao(Request $request)
    {
        try 
        {
            DB::beginTransaction();
            PermohonanAlat::where('noregistrasifk', $request['noregistrasifk'])->update(['statuscetak' => true]);
            DB::commit();
            DB::rollBack();
            $result=array(
                'data'=>null,
                'code'=>200,
                'message'=>'Berhasil Dicetak',
            );   
        }

        catch (Exception $e) 
        {
            DB::rollBack();
            $result=array(
                'data'=>null,
                'code'=>400,
                'message'=>'data not created',
                'hint'=>$e->getLine() . ' '. $e->getMessage()
            );   
        }
        return $this->respond($result);
    }

    public function SetNoAntrianFarmasi(Request $request)
    {
        DB::beginTransaction();
        try {
            $cek = DB::table('antrianapotik_t')
                ->where('noregistrasi', $request['noregistrasi'])
                ->lockForUpdate()
                ->first(); 

            if ($cek) {
                DB::rollBack();
                return $this->respond([
                    'code' => 200,
                    'message' => 'nothing',
                ]);
            }

            $dari = date('Y-m-d 00:00:00');
            $sampai = date('Y-m-d 23:59:59');
            $idProfile = 1;

            $data = DB::table('strukorder_t as so')
                ->join('orderpelayanan_t as op', 'op.strukorderfk', '=', 'so.norec')
                ->select(
                    'so.noregistrasifk',
                    'op.jeniskemasanfk',
                    'op.iskronis23',
                    'op.iskronis30',
                    'so.objectruangantujuanfk'
                )
                ->where('so.noregistrasifk', $request->input('norec_pd'))
                ->where('so.objectruangantujuanfk', 325)
                ->whereNotIn('so.objectruanganfk', [
                    312, 322, 323, 332, 361, 366, 367, 368, 369, 370, 371,
                    372, 373, 374, 375, 376, 377, 378, 379, 380, 381, 382,
                    383, 384, 385, 386, 756, 769
                ])
                ->get();

            if ($data->isEmpty()) {
                DB::rollBack();
                return $this->respond([
                    'code' => 200,
                    'message' => 'nothing',
                ]);
            }

            $kode = 'A';
            foreach ($data as $item) {
                if ($item->jeniskemasanfk == 1) {
                    $kode = 'B';
                    break;
                }
            }

            $countAntrian = AntrianApotik::where('kdprofile', $idProfile)
                ->whereBetween('tglresep', [$dari, $sampai])
                ->where('jenis', $kode)
                ->max('noantri') ?? 0;

            $noAntriApotik = str_pad((int)$countAntrian + 1, 4, "0", STR_PAD_LEFT);

            $newAA = new AntrianApotik();
            $newAA->norec = $newAA->generateNewId();
            $newAA->kdprofile = $idProfile;
            $newAA->noregistrasi = $request->input('noregistrasi');
            $newAA->statusenabled = true;
            $newAA->tglresep = date('Y-m-d H:i:s');
            $newAA->noantri = $noAntriApotik;
            $newAA->jenis = $kode;
            $newAA->save();

            DB::commit();
            return $this->respond([
                'code' => 200,
                'message' => 'Berhasil menyimpan antrian'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->respond([
                'code' => 400,
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function riwayatResepPulang(Request $request)
    {
        $idProfile = $this->kdProfile;
        $data2 = DB::table('strukresep_t as so')
            ->JOIN('antrianpasiendiperiksa_t as apd', 'apd.norec', '=', 'so.pasienfk')
            ->JOIN('pasiendaftar_t as pd', 'pd.norec', '=', 'apd.noregistrasifk')
            ->join('pasien_m as ps','ps.id','pd.nocmfk')
            ->JOIN('ruangan_m as ru', 'ru.id', '=', 'apd.objectruanganfk')
            ->JOIN('ruangan_m as ru2', 'ru2.id', '=', 'so.ruanganfk')
            ->leftJOIN('statuspengerjaan_m as sp', 'sp.id', '=', 'so.status')
            ->leftJOIN('pegawai_m as pg', 'pg.id', '=', 'so.penulisresepfk')
            ->select(
                'so.norec as norec_so',
                'so.noresep as noorder',
                'ru.namaruangan as namaruanganrawat',
                'so.tglresep as tglorder',
                'so.tglresep as tglorder_def',
                'pg.namalengkap',
                'ru2.namaruangan',
                'so.status as statusorder',
                'so.namalengkapambilresep as namapengambilorder',
                'pd.norec as noregistrasifk',
                'ps.namapasien',
                'so.pasienfk',
                'pd.noregistrasi',
                'pd.tglregistrasi',
                'so.tglambilresep as tglambilorder',
                'so.orderfk as norec_order',
                'so.isreseppulang',
                'sp.statuspengerjaan',
                'sp.reportdisplay as color_status',
                'apd.norec as norec_apd',
                'so.ruanganfk as objectruangantujuanfk',
                'so.penulisresepfk as objectpegawaiorderfk'
            )
            ->where('so.kdprofile', $idProfile)
            ->where('so.isreseppulang',true)
            ->where('so.statusenabled', true)
            ->whereNotNull('so.orderfk');

        if (isset($request['nocmfk']) && $request['nocmfk'] != "" && $request['nocmfk'] != "undefined") {
            $data2 = $data2->where('pd.nocmfk', '=', $request['nocmfk']);
        }

        if (isset($request['norec_pd']) && $request['norec_pd'] != "" && $request['norec_pd'] != "undefined") {
            $data2 = $data2->where('pd.norec', $request['norec_pd']);
        }
        if (isset($request['penulisresepfk']) && $request['penulisresepfk'] != "" && $request['penulisresepfk'] != "undefined") {
            $data2 = $data2->where('so.penulisresepfk', $request['penulisresepfk']);
        }
        $data2 = $data2->orderByDesc('so.tglresep');
        $data2 = $data2->get();

        $details2 = DB::table('pelayananpasien_t as pp')
            ->JOIN('antrianpasiendiperiksa_t as apd', 'apd.norec', '=', 'pp.noregistrasifk')
            ->JOIN('produk_m as pr', 'pr.id', '=', 'pp.produkfk')
            ->JOIN('ruangan_m as ru', 'ru.id', '=', 'apd.objectruanganfk')
            ->JOIN('jeniskemasan_m as jk', 'jk.id', '=', 'pp.jeniskemasanfk')
            ->leftJoin('jenisracikan_m as jra', 'jra.id', '=', 'pp.jenisracikanfk')
            ->leftJoin('satuanstandar_m as ss', 'ss.id', '=', 'pp.satuanviewfk')
            ->leftJoin('satuanstandar_m as ss2', 'ss2.id', '=', 'pr.objectsatuanstandarfk')
            ->JOIN('detailjenisproduk_m as djp', 'djp.id', '=', 'pr.objectdetailjenisprodukfk')
            ->JOIN('jenisproduk_m as jp', 'jp.id', '=', 'djp.objectjenisprodukfk')
            ->leftJOIN('konversisatuan_t as ks', function ($join) {
                $join->on('ks.objekprodukfk', '=', 'pr.id')
                    ->on('ks.satuanstandar_tujuan', '=', 'pp.satuanviewfk');
            })
            ->leftJOIN('rm_sediaan_m AS sdn', 'sdn.id', '=', 'pr.objectsediaanfk')
            ->leftJoin('satuanresep_m as sn', 'sn.id', '=', 'pp.satuanresepfk')
            ->leftJoin('routefarmasi as rt', 'rt.id', '=', 'pp.routefk')
            ->leftJoin('strukresep_t as so', 'so.norec', '=', 'pp.strukresepfk')
            ->select(DB::raw("pp.strukresepfk as norec_so,
            pp.rke, jk.jeniskemasan,
            pr.namaproduk, ss.satuanstandar, pp.aturanpakai,
            pp.jumlah, pp.hargasatuan,pp.keteranganpakai as keterangan,
            pp.satuanresepfk,sn.satuanresep,pp.tglkadaluarsa,pp.stock as jmlstok,

            pp.jeniskemasanfk,jk.id as jkid,pp.routefk,rt.name as namaroute,
            pp.produkfk as objectprodukfk,pr.namaproduk,pp.nilaikonversi as hasilkonversi,
            pr.objectsatuanstandarfk,ss.satuanstandar,pp.satuanviewfk,ss2.satuanstandar as ssview,
            pp.jumlah as qtyproduk,pp.hargadiscount,pp.nilaikonversi as hasilkonversi,pp.dosis,pp.jenisobatfk,
            pr.kekuatan,sdn.name as sediaan,pp.ispagi,pp.issiang,pp.ismalam,
            pp.issore,pp.keteranganpakai,pp.satuanresepfk,sn.satuanresep,pp.tglkadaluarsa,
            pp.strukterimafk,
            null  as asalprodukfk,null as asalproduk")
            )
            ->where('pp.kdprofile', $this->kdProfile)
            ->whereNotNull('pp.strukresepfk');

        if (isset($request['norec_pd']) && $request['norec_pd'] != "" && $request['norec_pd'] != "undefined") {
            $details2 = $details2->where('apd.noregistrasifk', $request['norec_pd']);
        }
        if (isset($request['penulisresepfk']) && $request['penulisresepfk'] != "" && $request['penulisresepfk'] != "undefined") {
            $details2 = $details2->where('so.penulisresepfk', $request['penulisresepfk']);
        }
        $details2 = $details2->orderBy('pp.tglpelayanan');
        $details2 = $details2->orderBy('pp.rke');
        $details2 = $details2->get();
        foreach ($data2 as $item) {
            $item->details = [];
            foreach ($details2 as $itemd) {
                if ($item->norec_so == $itemd->norec_so) {
                    $item->details[] = $itemd;
                }
            }
        }
        return $this->respond($data2);
    }


    public function hapusOrderResep(Request $request)
    {

        DB::beginTransaction();
        try {
            $idProfile = (int) $this->kdProfile;
            $so = StrukOrder::where('norec', $request['norec'])->where('kdprofile', $idProfile)->first();
            if ($so->statusorder == 5) {
                $transMessage = "Tidak Bisa dihapus sudah Di Verifikasi";

                DB::rollBack();
                $result = array("status" => 400, "result"  => null);
                return $this->respond($result['result'], $result['status'], $transMessage);
            }
            StrukOrder::where('norec', $request['norec'])->update(['statusenabled' => false]);

            if($request['antibiotik'] == true)
            {
                DB::table('ppra_transaksi')
                ->where('strukorderfk', $request['norec'])
                ->update(['statusenabled' => false]);
            }

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
                    "as" => '@epic',
                ),
            );
        } else {
            $transMessage = "Simpan Gagal";
            DB::rollBack();
        $result = array(
                "status" => 400,
                "result"  => null

            );
        }
        return $this->respond($result['result'], $result['status'], $transMessage);
    }
    
    public function getOrderResepNow(Request $request)
    {
        $tglawal = date('Y-m-d') . " 00:00";
        $tglakhir = date('Y-m-d') . " 23:59";
        $dataOrder = DB::table('strukorder_t as so')
        ->join('pasiendaftar_t as pd', 'pd.norec', '=', 'so.noregistrasifk')
        ->join('orderpelayanan_t as op', 'op.strukorderfk', '=', 'so.norec')
        ->join('produk_m as pr', 'pr.id', '=', 'op.objectprodukfk')
        ->select(
            'so.noorder as nopesan',
            'pr.id as idproduk',
            'pr.namaproduk',
        )
        ->where('so.kdprofile', $this->kdProfile)
        ->where('so.statusenabled', true)
        ->whereBetween('so.tglorder', [$tglawal, $tglakhir])
        ->where('pd.norec', $request['norec'])
        ->get();

        // Aktifkan jika user pengen dari input resep juga
        // $dataResep = DB::table('strukresep_t as so')
        // ->join('pelayananpasien_t as pp', 'pp.strukresepfk', '=', 'so.norec')
        // ->join('produk_m as pr', 'pr.id', '=', 'pp.produkfk')
        // ->join('antrianpasiendiperiksa_t as apd', 'apd.norec', '=', 'pp.noregistrasifk')
        // ->join('pasiendaftar_t as pd', 'pd.norec', '=', 'apd.noregistrasifk')
        // ->select(
        //     'so.noresep as nopesan',
        //     'pr.id as idproduk',
        //     'pr.namaproduk',
        // )
        // ->where('so.kdprofile', $this->kdProfile)
        // ->where('so.statusenabled', true)
        // ->whereBetween('so.tglresep', [$tglawal, $tglakhir])
        // ->where('pd.norec', $request['norec'])
        // ->get();

        // $result =  array_merge($dataOrder->toArray(),$dataResep->toArray());

        return $this->respond($dataOrder);
    }

        public function resepVerif(Request $request)
        {
            $idProfile = $this->kdProfile;
            $nocmfk = '';
            $norec_pd = '';
            $penulisresep = '';
            $set = json_decode($this->settingFix('kelompokTransaksiOrderResep'));
            $data = DB::table('strukorder_t as so')
                ->JOIN('pasiendaftar_t as pd', 'pd.norec', '=', 'so.noregistrasifk')
                ->JOIN('ruangan_m as ru', 'ru.id', '=', 'so.objectruanganfk')
                ->JOIN('ruangan_m as ru2', 'ru2.id', '=', 'so.objectruangantujuanfk')
                ->leftJOIN('statuspengerjaan_m as sp', 'sp.id', '=', 'so.statusorder')
                ->leftJOIN('pegawai_m as pg', 'pg.id', '=', 'so.objectpegawaiorderfk')
                ->leftjoin('strukresep_t as sr', function ($join) {
                    $join->on('sr.orderfk', '=', 'so.norec');
                    $join->where('sr.statusenabled',true);
                })
                ->select(
                    'so.norec as norec_so',
                    'so.noorder',
                    'ru.namaruangan as namaruanganrawat',
                    'so.tglorder',
                    'so.tglorder as tglorder_def',
                    'pg.namalengkap',
                    'ru2.namaruangan',
                    'so.statusorder',
                    'so.namapengambilorder',
                    'so.noregistrasifk',
                    'pd.noregistrasi',
                    'pd.tglregistrasi',
                    'so.tglambilorder',
                    'so.norec as norec_order',
                    'so.isreseppulang',
                    'so.isambilobat',
                    'so.iskurir',
                    'sp.statuspengerjaan',
                    'sp.reportdisplay as color_status',
                    'so.norec_apd',
                    'so.objectruangantujuanfk',
                    'so.objectpegawaiorderfk',
                    'sr.norec as norec_resep'
                )
                ->where('so.kdprofile', $idProfile)
                ->where('so.statusenabled', true)
                ->where('so.statusorder', 5)
                ->where('so.objectkelompoktransaksifk', $set->objectkelompoktransaksifk);
    
            if (isset($request['nocmfk']) && $request['nocmfk'] != "" && $request['nocmfk'] != "undefined") {
                $data = $data->where('pd.nocmfk', '=', $request['nocmfk']);
                $nocmfk = " and pd.nocmfk='" . $request['nocmfk'] . "'";
            }
            if (isset($request['norec_pd']) && $request['norec_pd'] != "" && $request['norec_pd'] != "undefined") {
                $data = $data->where('pd.norec', $request['norec_pd']);
                $norec_pd =  " and pd.norec='" . $request['norec_pd'] . "'";
            }
            if (isset($request['penulisresepfk']) && $request['penulisresepfk'] != "" && $request['penulisresepfk'] != "undefined") {
                $data = $data->where('so.objectpegawaiorderfk', $request['penulisresepfk']);
                $penulisresep =  " and so.objectpegawaiorderfk='" . $request['penulisresepfk'] . "'";
            }
            if (isset($request['limit']) && $request['limit'] != "" && $request['limit'] != "undefined") {
                $data = $data->limit($request['limit']);
    
            }
            $data = $data->orderByDesc('so.tglorder');
            $data = $data->get();
    
    
            $details = DB::select(
                DB::raw("
                    SELECT
                    so.norec as norec_so,
                    op.rke, jk.jeniskemasan,
                    pp.norec as id_pp,
                    pr.namaproduk, ss.satuanstandar, op.aturanpakai,pr.objectsubkategoryfk,
                    op.jumlah, op.hargasatuan,op.keteranganpakai as keterangan,
                    op.satuanresepfk,sn.satuanresep,op.tglkadaluarsa,
                    op.qtystokcurrent as jmlstok,
                    op.jeniskemasanfk,jk.id as jkid,op.routefk,rt.name as namaroute,
                    op.objectprodukfk,pr.namaproduk,op.hasilkonversi,
                    op.objectsatuanstandarfk,ss.satuanstandar,op.satuanviewfk,ss2.satuanstandar as ssview,
                    op.qtyproduk,op.hargadiscount,op.hasilkonversi as nilaikonversi,op.dosis,op.jenisobatfk,
                    pr.kekuatan,sdn.name as sediaan,op.ispagi,op.issiang,op.ismalam,
                    op.issore,op.keteranganpakai,op.satuanresepfk,sn.satuanresep,op.tglkadaluarsa,op.nostrukterimafk,
                    op.objectasalprodukfk as asalprodukfk,asl.asalproduk,
                    (select sum(qtyproduk) from stokprodukdetail_t where kdprofile = $idProfile and objectprodukfk = spd.objectprodukfk and statusenabled = true) as totalstok
                    from strukorder_t as so
                    inner join orderpelayanan_t as op on op.strukorderfk = so.norec
                    inner join pasiendaftar_t as pd on pd.norec = so.noregistrasifk
                    inner join produk_m as pr on pr.id=op.objectprodukfk
                    left join strukresep_t as sr on sr.orderfk=so.norec
                    left join pelayananpasien_t as pp on pp.strukresepfk=sr.norec and pp.produkfk = op.objectprodukfk
                    left join jeniskemasan_m as jk on jk.id=op.jeniskemasanfk
                    left join satuanstandar_m as ss on ss.id=op.objectsatuanstandarfk
                    left join satuanstandar_m as ss2 on ss2.id=op.satuanviewfk
                    left join satuanresep_m as sn on sn.id=op.satuanresepfk
                    JOIN ruangan_m as ru on ru.id=so.objectruangantujuanfk
                    left join routefarmasi as rt on rt.id=op.routefk
                    left join rm_sediaan_m as sdn on sdn.id=pr.objectsediaanfk
                    left join asalproduk_m as asl on asl.id=op.objectasalprodukfk
                    left join stokprodukdetail_t as spd on spd.norec=op.stokprodukdetailfk
                    where so.kdprofile = $idProfile
                    and so.objectkelompoktransaksifk = $set->objectkelompoktransaksifk
                    and so.statusenabled = true
                    $nocmfk
                    $norec_pd
                    $penulisresep
                   ")
            );
    
            $data2 = DB::table('strukresep_t as so')
                ->JOIN('antrianpasiendiperiksa_t as apd', 'apd.norec', '=', 'so.pasienfk')
                ->JOIN('pasiendaftar_t as pd', 'pd.norec', '=', 'apd.noregistrasifk')
                ->JOIN('ruangan_m as ru', 'ru.id', '=', 'apd.objectruanganfk')
                ->JOIN('ruangan_m as ru2', 'ru2.id', '=', 'so.ruanganfk')
                ->leftJOIN('statuspengerjaan_m as sp', 'sp.id', '=', 'so.status')
                ->leftJOIN('pegawai_m as pg', 'pg.id', '=', 'so.penulisresepfk')
                ->select(
                    'so.norec as norec_so',
                    'so.noresep as noorder',
                    'ru.namaruangan as namaruanganrawat',
                    'so.tglresep as tglorder',
                    'so.tglresep as tglorder_def',
                    'pg.namalengkap',
                    'ru2.namaruangan',
                    'so.status as statusorder',
                    'so.namalengkapambilresep as namapengambilorder',
                    'pd.norec as noregistrasifk',
                    'pd.noregistrasi',
                    'pd.tglregistrasi',
                    'so.tglambilresep as tglambilorder',
                    'so.orderfk as norec_order',
                    'so.isreseppulang',
                    DB::raw("  null as isambilobat
                    ,null as iskurir"),
                    'sp.statuspengerjaan',
                    'sp.reportdisplay as color_status',
                    'apd.norec as norec_apd',
                    'so.ruanganfk as objectruangantujuanfk',
                    'so.penulisresepfk as objectpegawaiorderfk'
                )
                ->where('so.kdprofile', $idProfile)
                ->where('so.statusenabled', true)
                ->where('so.status', 5)
                ->whereNull('so.orderfk');
    
            if (isset($request['nocmfk']) && $request['nocmfk'] != "" && $request['nocmfk'] != "undefined") {
                $data2 = $data2->where('pd.nocmfk', '=', $request['nocmfk']);
            }
    
            if (isset($request['norec_pd']) && $request['norec_pd'] != "" && $request['norec_pd'] != "undefined") {
                $data2 = $data2->where('pd.norec', $request['norec_pd']);
            }
            if (isset($request['penulisresepfk']) && $request['penulisresepfk'] != "" && $request['penulisresepfk'] != "undefined") {
                $data2 = $data2->where('so.penulisresepfk', $request['penulisresepfk']);
            }
            if (isset($request['limit']) && $request['limit'] != "" && $request['limit'] != "undefined") {
                $data2 = $data2->limit($request['limit']);
    
            }
            if (isset($request['norec_apd']) && $request['norec_apd'] != "" && $request['norec_apd'] != "undefined") {
                $data2 = $data2->where('so.pasienfk', $request['norec_apd']);
            }
            
            $data2 = $data2->orderByDesc('so.tglresep');
            $data2 = $data2->get();
    
            $details2 = DB::table('pelayananpasien_t as pp')
                ->JOIN('antrianpasiendiperiksa_t as apd', 'apd.norec', '=', 'pp.noregistrasifk')
                ->JOIN('pasiendaftar_t as pd', 'pd.norec', '=', 'apd.noregistrasifk')
                ->JOIN('produk_m as pr', 'pr.id', '=', 'pp.produkfk')
                ->JOIN('ruangan_m as ru', 'ru.id', '=', 'apd.objectruanganfk')
                ->JOIN('jeniskemasan_m as jk', 'jk.id', '=', 'pp.jeniskemasanfk')
                ->leftJoin('jenisracikan_m as jra', 'jra.id', '=', 'pp.jenisracikanfk')
                ->leftJoin('satuanstandar_m as ss', 'ss.id', '=', 'pp.satuanviewfk')
                ->leftJoin('satuanstandar_m as ss2', 'ss2.id', '=', 'pr.objectsatuanstandarfk')
                ->JOIN('detailjenisproduk_m as djp', 'djp.id', '=', 'pr.objectdetailjenisprodukfk')
                ->JOIN('jenisproduk_m as jp', 'jp.id', '=', 'djp.objectjenisprodukfk')
                ->leftJOIN('konversisatuan_t as ks', function ($join) {
                    $join->on('ks.objekprodukfk', '=', 'pr.id')
                        ->on('ks.satuanstandar_tujuan', '=', 'pp.satuanviewfk');
                })
                ->leftJOIN('rm_sediaan_m AS sdn', 'sdn.id', '=', 'pr.objectsediaanfk')
                ->leftJoin('satuanresep_m as sn', 'sn.id', '=', 'pp.satuanresepfk')
                ->leftJoin('routefarmasi as rt', 'rt.id', '=', 'pp.routefk')
                ->leftJoin('strukresep_t as so', 'so.norec', '=', 'pp.strukresepfk')
                ->leftJoin('stokprodukdetail_t as spd', 'spd.norec', '=', 'pp.stokprodukdetailfk')
                ->select(DB::raw("pp.strukresepfk as norec_so,
                pp.rke, jk.jeniskemasan,pp.norec as id_pp,apd.norec as id_apd,
                pr.namaproduk, ss.satuanstandar, pp.aturanpakai,
                pp.jumlah, pp.hargasatuan, pp.hargajual,pp.generik,pp.keteranganpakai as keterangan,
                pp.satuanresepfk,sn.satuanresep,pp.tglkadaluarsa,pp.stock as jmlstok,
    
                pp.jeniskemasanfk,jk.id as jkid,pp.routefk,rt.name as namaroute,pr.objectsubkategoryfk,
                pp.produkfk as objectprodukfk,pr.namaproduk,pp.nilaikonversi as hasilkonversi,
                pr.objectsatuanstandarfk,ss.satuanstandar,pp.satuanviewfk,ss2.satuanstandar as ssview,
                pp.jumlah as qtyproduk,pp.hargadiscount,pp.nilaikonversi as hasilkonversi,pp.dosis,pp.jenisobatfk,
                pr.kekuatan,sdn.name as sediaan,pp.ispagi,pp.issiang,pp.ismalam,
                pp.issore,pp.keteranganpakai,pp.satuanresepfk,sn.satuanresep,pp.tglkadaluarsa,
                pp.strukterimafk, pp.tglpelayanan, pp.harganetto, pp.stock as stockpp,
                (select sum(qtyproduk) from stokprodukdetail_t where kdprofile = $idProfile and objectprodukfk = spd.objectprodukfk and statusenabled = true) as totalstok,
                null  as asalprodukfk,null as asalproduk")
                )
                ->where('pp.kdprofile', $this->kdProfile)
                ->whereNotNull('pp.strukresepfk');
    
            if (isset($request['norec_pd']) && $request['norec_pd'] != "" && $request['norec_pd'] != "undefined") {
                $details2 = $details2->where('apd.noregistrasifk', $request['norec_pd']);
            }
            if (isset($request['penulisresepfk']) && $request['penulisresepfk'] != "" && $request['penulisresepfk'] != "undefined") {
                $details2 = $details2->where('so.penulisresepfk', $request['penulisresepfk']);
            }

            if (isset($request['norec_apd']) && $request['norec_apd'] != "" && $request['norec_apd'] != "undefined") {
                $details2 = $details2->where('so.pasienfk', $request['norec_apd']);
            }

            if (isset($request['nocmfk']) && $request['nocmfk'] != "" && $request['nocmfk'] != "undefined") {
            $details2 = $details2->where('pd.nocmfk', $request['nocmfk']);
            }
            
            $details2 = $details2->orderBy('pp.tglpelayanan');
            $details2 = $details2->orderBy('pp.rke');
            $details2 = $details2->get();
    
            foreach ($data as $item) {
                $item->details = [];
                if(isset($request['verif'] ) && $request['verif']  == 'true'){
                    foreach ($details2 as $itemd) {
                        if ($item->norec_resep == $itemd->norec_so) {
                            $item->details[] = $itemd;
                        }
                    }
                }else{
                    foreach ($details as $itemd) {
                        if ($item->norec_so == $itemd->norec_so) {
                            $item->details[] = $itemd;
                        }
                    }
                }
                // if($item->norec_resep != null){ //ambil ke pelayanapasien kalo udah verif
                //     foreach ($details2 as $itemd) {
                //         if ($item->norec_resep == $itemd->norec_so) {
                //             $item->details[] = $itemd;
                //         }
                //     }
                // }else{
                //     foreach ($details as $itemd) {
                //         if ($item->norec_so == $itemd->norec_so) {
                //             $item->details[] = $itemd;
                //         }
                //     }
                // }
    
            }
    
            foreach ($data2 as $item) {
                $item->details = [];
                foreach ($details2 as $itemd) {
                    if ($item->norec_so == $itemd->norec_so) {
                        $item->details[] = $itemd;
                    }
                }
            }
    
            // if(count($data2)> 0 && count($data2)> 0){
                $data =  array_merge($data->toArray(),$data2->toArray());
            // }
    
            // foreach ($data2 as $no => $item) {
            //     $nKonversi = 1;
            //     $item->no = $no + 1;
            //     if ($item->hasilkonversi != null) {
            //         $nKonversi = $item->hasilkonversi;
            //     }
            //     if ($item->satuanstandar != null) {
            //         $ss = $item->satuanstandar;
            //     } else {
            //         $ss = $item->satuanstandar2;
            //     }
            //     $JenisKemasan = $item->jeniskemasan;
            //     if ($item->jenisracikan != null) {
            //         $JenisKemasan = $item->jeniskemasan . '/' . $item->jenisracikan;
            //     }
            //     $jasa = 0;
            //     if ($item->jasa != null && $item->jasa != '') {
            //         $jasa = $item->jasa;
            //     }
            //     $item->jumlah =  (float)$item->jumlah / (float)$nKonversi;
            //     $item->satuanstandar =  $ss;
            //     $item->noregistrasi = $data->noregistrasi;
            //     $item->jasa = $jasa;
            //     $item->jeniskemasan = $JenisKemasan;
    
            //     $item->total = (((float)$item->jumlah * (float)$item->hargasatuan) - (float)$item->hargadiscount) + (float)     $item->jasa;
            // }
    
    
            return $this->respond($data);
        }
    public function resepRutin(Request $request)
        {
            $idProfile = $this->kdProfile;
            $nocmfk = '';
            $norec_pd = '';
            $penulisresep = '';
            $set = json_decode($this->settingFix('kelompokTransaksiOrderResep'));
            $data = DB::table('strukorder_t as so')
                ->JOIN('pasiendaftar_t as pd', 'pd.norec', '=', 'so.noregistrasifk')
                ->JOIN('ruangan_m as ru', 'ru.id', '=', 'so.objectruanganfk')
                ->JOIN('ruangan_m as ru2', 'ru2.id', '=', 'so.objectruangantujuanfk')
                ->leftJOIN('statuspengerjaan_m as sp', 'sp.id', '=', 'so.statusorder')
                ->leftJOIN('pegawai_m as pg', 'pg.id', '=', 'so.objectpegawaiorderfk')
                ->leftjoin('strukresep_t as sr', function ($join) {
                    $join->on('sr.orderfk', '=', 'so.norec');
                    $join->where('sr.statusenabled',true);
                })
                ->select(
                    'so.norec as norec_so',
                    'so.noorder',
                    'so.isrutin',
                    'ru.namaruangan as namaruanganrawat',
                    'so.tglorder',
                    'so.tglorder as tglorder_def',
                    'pg.namalengkap',
                    'ru2.namaruangan',
                    'so.statusorder',
                    'so.namapengambilorder',
                    'so.noregistrasifk',
                    'pd.noregistrasi',
                    'pd.tglregistrasi',
                    'so.tglambilorder',
                    'so.norec as norec_order',
                    'so.isreseppulang',
                    'so.isambilobat',
                    'so.iskurir',
                    'sp.statuspengerjaan',
                    'sp.reportdisplay as color_status',
                    'so.norec_apd',
                    'so.objectruangantujuanfk',
                    'so.objectpegawaiorderfk',
                    'sr.norec as norec_resep'
                )
                ->where('so.kdprofile', $idProfile)
                ->where('so.statusenabled', true)
                ->where('so.statusorder', 5)
                ->where('so.isrutin', true)
                ->where('so.objectkelompoktransaksifk', $set->objectkelompoktransaksifk);
    
            if (isset($request['nocmfk']) && $request['nocmfk'] != "" && $request['nocmfk'] != "undefined") {
                $data = $data->where('pd.nocmfk', '=', $request['nocmfk']);
                $nocmfk = " and pd.nocmfk='" . $request['nocmfk'] . "'";
            }
            if (isset($request['norec_pd']) && $request['norec_pd'] != "" && $request['norec_pd'] != "undefined") {
                $data = $data->where('pd.norec', $request['norec_pd']);
                $norec_pd =  " and pd.norec='" . $request['norec_pd'] . "'";
            }
            if (isset($request['penulisresepfk']) && $request['penulisresepfk'] != "" && $request['penulisresepfk'] != "undefined") {
                $data = $data->where('so.objectpegawaiorderfk', $request['penulisresepfk']);
                $penulisresep =  " and so.objectpegawaiorderfk='" . $request['penulisresepfk'] . "'";
            }
            if (isset($request['limit']) && $request['limit'] != "" && $request['limit'] != "undefined") {
                $data = $data->limit($request['limit']);
    
            }
            $data = $data->orderByDesc('so.tglorder');
            $data = $data->get();
    
    
            $details = DB::select(
                DB::raw("
                    SELECT
                    so.norec as norec_so,
                    op.rke, jk.jeniskemasan,jr.jenisracikan,
                    pr.namaproduk, ss.satuanstandar, op.aturanpakai,pr.objectsubkategoryfk,
                    op.jumlah, op.hargasatuan,op.keteranganpakai as keterangan,
                    op.satuanresepfk,sn.satuanresep,op.tglkadaluarsa, null as totalstok,
                    op.qtystokcurrent as jmlstok,
                    op.jeniskemasanfk,jk.id as jkid,op.routefk,rt.name as namaroute,
                    op.objectprodukfk,pr.namaproduk,op.hasilkonversi,
                    op.objectsatuanstandarfk,ss.satuanstandar,op.satuanviewfk,ss2.satuanstandar as ssview,
                    op.qtyproduk,op.hargadiscount,op.hasilkonversi as nilaikonversi,op.dosis,op.jenisobatfk,
                    pr.kekuatan,sdn.name as sediaan,op.ispagi,op.issiang,op.ismalam,
                    op.issore,op.keteranganpakai,op.satuanresepfk,sn.satuanresep,op.tglkadaluarsa,op.nostrukterimafk,
                    op.objectasalprodukfk as asalprodukfk,asl.asalproduk
                    from strukorder_t as so
                    inner join orderpelayanan_t as op on op.strukorderfk = so.norec
                    inner join pasiendaftar_t as pd on pd.norec = so.noregistrasifk
                    inner join produk_m as pr on pr.id=op.objectprodukfk
                    left join jeniskemasan_m as jk on jk.id=op.jeniskemasanfk
                    left join satuanstandar_m as ss on ss.id=op.objectsatuanstandarfk
                    left join satuanstandar_m as ss2 on ss2.id=op.satuanviewfk
                    left join satuanresep_m as sn on sn.id=op.satuanresepfk
                    LEFT JOIN jenisracikan_m as jr on jr.id = op.jenisobatfk
                    JOIN ruangan_m as ru on ru.id=so.objectruangantujuanfk
                    left join routefarmasi as rt on rt.id=op.routefk
                    left join rm_sediaan_m as sdn on sdn.id=pr.objectsediaanfk
                    left join asalproduk_m as asl on asl.id=op.objectasalprodukfk
                    where so.kdprofile = $idProfile
                    and so.objectkelompoktransaksifk = $set->objectkelompoktransaksifk
                    and so.statusenabled = true
                    $nocmfk
                    $norec_pd
                    $penulisresep
                   ")
            );
    
            $data2 = DB::table('strukresep_t as so')
                ->JOIN('antrianpasiendiperiksa_t as apd', 'apd.norec', '=', 'so.pasienfk')
                ->JOIN('pasiendaftar_t as pd', 'pd.norec', '=', 'apd.noregistrasifk')
                ->JOIN('ruangan_m as ru', 'ru.id', '=', 'apd.objectruanganfk')
                ->JOIN('ruangan_m as ru2', 'ru2.id', '=', 'so.ruanganfk')
                ->leftJOIN('statuspengerjaan_m as sp', 'sp.id', '=', 'so.status')
                ->leftJOIN('pegawai_m as pg', 'pg.id', '=', 'so.penulisresepfk')
                ->select(
                    'so.norec as norec_so',
                    'so.noresep as noorder',
                    'ru.namaruangan as namaruanganrawat',
                    'so.tglresep as tglorder',
                    'so.tglresep as tglorder_def',
                    'pg.namalengkap',
                    'so.isrutin',
                    'ru2.namaruangan',
                    'so.status as statusorder',
                    'so.namalengkapambilresep as namapengambilorder',
                    'pd.norec as noregistrasifk',
                    'pd.noregistrasi',
                    'pd.tglregistrasi',
                    'so.tglambilresep as tglambilorder',
                    'so.orderfk as norec_order',
                    'so.isreseppulang',
                    DB::raw("  null as isambilobat
                ,null as iskurir"),
                    'sp.statuspengerjaan',
                    'sp.reportdisplay as color_status',
                    'apd.norec as norec_apd',
                    'so.ruanganfk as objectruangantujuanfk',
                    'so.penulisresepfk as objectpegawaiorderfk'
                )
                ->where('so.kdprofile', $idProfile)
                ->where('so.statusenabled', true)
                ->where('so.isrutin', true)
                ->whereNull('so.orderfk');
    
            if (isset($request['nocmfk']) && $request['nocmfk'] != "" && $request['nocmfk'] != "undefined") {
                $data2 = $data2->where('pd.nocmfk', '=', $request['nocmfk']);
            }
    
            if (isset($request['norec_pd']) && $request['norec_pd'] != "" && $request['norec_pd'] != "undefined") {
                $data2 = $data2->where('pd.norec', $request['norec_pd']);
            }
            if (isset($request['penulisresepfk']) && $request['penulisresepfk'] != "" && $request['penulisresepfk'] != "undefined") {
                $data2 = $data2->where('so.penulisresepfk', $request['penulisresepfk']);
            }
            if (isset($request['limit']) && $request['limit'] != "" && $request['limit'] != "undefined") {
                $data2 = $data2->limit($request['limit']);
    
            }
            $data2 = $data2->orderByDesc('so.tglresep');
            $data2 = $data2->get();
    
            $details2 = DB::table('pelayananpasien_t as pp')
                ->JOIN('antrianpasiendiperiksa_t as apd', 'apd.norec', '=', 'pp.noregistrasifk')
                ->JOIN('produk_m as pr', 'pr.id', '=', 'pp.produkfk')
                ->JOIN('ruangan_m as ru', 'ru.id', '=', 'apd.objectruanganfk')
                ->JOIN('jeniskemasan_m as jk', 'jk.id', '=', 'pp.jeniskemasanfk')
                ->leftJoin('jenisracikan_m as jra', 'jra.id', '=', 'pp.jenisracikanfk')
                ->leftJoin('satuanstandar_m as ss', 'ss.id', '=', 'pp.satuanviewfk')
                ->leftJoin('satuanstandar_m as ss2', 'ss2.id', '=', 'pr.objectsatuanstandarfk')
                ->JOIN('detailjenisproduk_m as djp', 'djp.id', '=', 'pr.objectdetailjenisprodukfk')
                ->JOIN('jenisproduk_m as jp', 'jp.id', '=', 'djp.objectjenisprodukfk')
                ->leftJOIN('konversisatuan_t as ks', function ($join) {
                    $join->on('ks.objekprodukfk', '=', 'pr.id')
                        ->on('ks.satuanstandar_tujuan', '=', 'pp.satuanviewfk');
                })
                ->leftJOIN('rm_sediaan_m AS sdn', 'sdn.id', '=', 'pr.objectsediaanfk')
                ->leftJoin('satuanresep_m as sn', 'sn.id', '=', 'pp.satuanresepfk')
                ->leftJoin('routefarmasi as rt', 'rt.id', '=', 'pp.routefk')
                ->leftJoin('strukresep_t as so', 'so.norec', '=', 'pp.strukresepfk')
                ->leftJoin('stokprodukdetail_t as spd', 'spd.norec', '=', 'pp.stokprodukdetailfk')
                ->select(DB::raw("pp.strukresepfk as norec_so,
                pp.rke, jk.jeniskemasan,
                pr.namaproduk, ss.satuanstandar, pp.aturanpakai,pr.objectsubkategoryfk,
                pp.jumlah, pp.hargasatuan,pp.keteranganpakai as keterangan,
                pp.satuanresepfk,sn.satuanresep,pp.tglkadaluarsa,pp.stock as jmlstok,
    
                pp.jeniskemasanfk,jk.id as jkid,pp.routefk,rt.name as namaroute, jra.jenisracikan,
                pp.produkfk as objectprodukfk,pr.namaproduk,pp.nilaikonversi as hasilkonversi,
                pr.objectsatuanstandarfk,ss.satuanstandar,pp.satuanviewfk,ss2.satuanstandar as ssview,
                pp.jumlah as qtyproduk,pp.hargadiscount,pp.nilaikonversi as hasilkonversi,pp.dosis,pp.jenisobatfk,
                pr.kekuatan,sdn.name as sediaan,pp.ispagi,pp.issiang,pp.ismalam,
                pp.issore,pp.keteranganpakai,pp.satuanresepfk,sn.satuanresep,pp.tglkadaluarsa,
                pp.strukterimafk, (select sum(qtyproduk) from stokprodukdetail_t where kdprofile = $idProfile and objectprodukfk = spd.objectprodukfk and statusenabled = true) as totalstok,
                null  as asalprodukfk,null as asalproduk")
                )
                ->where('pp.kdprofile', $this->kdProfile)
                ->whereNotNull('pp.strukresepfk');
    
            if (isset($request['norec_pd']) && $request['norec_pd'] != "" && $request['norec_pd'] != "undefined") {
                $details2 = $details2->where('apd.noregistrasifk', $request['norec_pd']);
            }
            if (isset($request['penulisresepfk']) && $request['penulisresepfk'] != "" && $request['penulisresepfk'] != "undefined") {
                $details2 = $details2->where('so.penulisresepfk', $request['penulisresepfk']);
            }
            $details2 = $details2->orderBy('pp.tglpelayanan');
            $details2 = $details2->orderBy('pp.rke');
            $details2 = $details2->get();
    
            foreach ($data as $item) {
                $item->details = [];
                // if(isset($request['verif'] ) && $request['verif']  == 'true'){
                //     foreach ($details2 as $itemd) {
                //         if ($item->norec_resep == $itemd->norec_so) {
                //             $item->details[] = $itemd;
                //         }
                //     }
                // }else{
                //     foreach ($details as $itemd) {
                //         if ($item->norec_so == $itemd->norec_so) {
                //             $item->details[] = $itemd;
                //         }
                //     }
                // }
                if($item->norec_resep != null){ //ambil ke pelayanapasien kalo udah verif
                    foreach ($details2 as $itemd) {
                        if ($item->norec_resep == $itemd->norec_so) {
                            $item->details[] = $itemd;
                        }
                    }
                }else{
                    foreach ($details as $itemd) {
                        if ($item->norec_so == $itemd->norec_so) {
                            $item->details[] = $itemd;
                        }
                    }
                }
    
            }
    
            // foreach ($data2 as $item) {
            //     $item->details = [];
            //     foreach ($details2 as $itemd) {
            //         if ($item->norec_so == $itemd->norec_so) {
            //             $item->details[] = $itemd;
            //         }
            //     }
            // }
    
            // if(count($data2)> 0 && count($data2)> 0){
                $data =  array_merge($data->toArray(),$data2->toArray());
            // }
    
            // foreach ($data2 as $no => $item) {
            //     $nKonversi = 1;
            //     $item->no = $no + 1;
            //     if ($item->hasilkonversi != null) {
            //         $nKonversi = $item->hasilkonversi;
            //     }
            //     if ($item->satuanstandar != null) {
            //         $ss = $item->satuanstandar;
            //     } else {
            //         $ss = $item->satuanstandar2;
            //     }
            //     $JenisKemasan = $item->jeniskemasan;
            //     if ($item->jenisracikan != null) {
            //         $JenisKemasan = $item->jeniskemasan . '/' . $item->jenisracikan;
            //     }
            //     $jasa = 0;
            //     if ($item->jasa != null && $item->jasa != '') {
            //         $jasa = $item->jasa;
            //     }
            //     $item->jumlah =  (float)$item->jumlah / (float)$nKonversi;
            //     $item->satuanstandar =  $ss;
            //     $item->noregistrasi = $data->noregistrasi;
            //     $item->jasa = $jasa;
            //     $item->jeniskemasan = $JenisKemasan;
    
            //     $item->total = (((float)$item->jumlah * (float)$item->hargasatuan) - (float)$item->hargadiscount) + (float)     $item->jasa;
            // }
    
    
            return $this->respond($data);
        }

    public function getDataPaketObat(Request $request)
    {
        $result=[];
        $data = DB::table('paketobat_m as sp')
            ->select('sp.id as paketId','sp.namapaket')
            ->where('sp.kdprofile', $this->kdProfile)
            ->where('sp.statusenabled', true);

        if(isset($request['paketId']) && $request['paketId'] !='' ){
            $data = $data->where('sp.id',$request['paketId']);
        }
        if(isset($request['namaPaket']) && $request['namaPaket'] !='' ){
            $data = $data->where('sp.namapaket','ilike','%'.$request['namaPaket'].'%');
        }
        $data = $data->get();
        foreach ($data as $item) {
            $details = DB::select(DB::raw("SELECT pkd.*,pro.namaproduk,pro.objectsatuanstandarfk,ss.satuanstandar,
                         pkd.qty as jumlah,sn.satuanresep
                    FROM paketobatd_m as pkd
                    INNER JOIN produk_m As pro ON pro.id = pkd.produkfk
                    LEFT JOIN satuanstandar_m AS ss ON ss.id = pro.objectsatuanstandarfk
                    LEFT JOIN satuanresep_m AS sn ON sn.id = pkd.satuanresepfk
                    where pkd.kdprofile = $this->kdProfile and pkd.objectpaketobatfk=:norec"),
                array(
                    'norec' => $item->paketId,
                )
            );
            $result[] = array(
                'paketId' => $item->paketId,
                'namapaket' => $item->namapaket,
                'details' => $details,
            );
        }
        $result = array(
            'data' => $result,
            'as' => 'epic'
        );
        return $this->respond($result);
    }

    public function returResep(Request $request)
    {
        $idProfile = $this->kdProfile;
        $nocmfk = '';
        $norec = '';
        $penulisresep = '';
        $set = json_decode($this->settingFix('kelompokTransaksiOrderResep'));
    
        // Mengambil data dari query builder
        $data = DB::table('pelayananpasien_t as pp')
            ->join('antrianpasiendiperiksa_t as apd', 'apd.norec', '=', 'pp.noregistrasifk')
            ->join('produk_m as pr', 'pr.id', '=', 'pp.produkfk')
            ->join('ruangan_m as ru', 'ru.id', '=', 'apd.objectruanganfk')
            ->leftJoin('satuanresep_m as sn', 'sn.id', '=', 'pp.satuanresepfk')
            ->leftJoin('strukresep_t as so', 'so.norec', '=', 'pp.strukresepfk')
            ->where('pp.kdprofile', '=', $idProfile)
            ->where('pp.statusenabled', true)
            ->whereNotNull('pp.strukresepfk');
    
        if (isset($request['norec']) && $request['norec'] != "" && $request['norec'] != "undefined") {
            $data = $data->where('pp.noregistrasifk', '=', $request['norec']);
            $nocmfk = " and pp.noregistrasifk = '" . $request['norec'] . "'";
        }
    
        $data = $data->groupBy(
                'pp.produkfk',
                'pr.namaproduk',
                'pp.hargasatuan',
                'pp.tglkadaluarsa',
                'pr.objectsatuanstandarfk',
                'pp.satuanviewfk',
                'pp.hargadiscount',
                'pp.nilaikonversi',
                'pp.dosis',
                'pp.jenisobatfk',
                'pr.kekuatan',
                'pp.ispagi',
                'pp.issiang',
                'pp.ismalam',
                'pp.issore',
                'pp.keteranganpakai',
                'pp.satuanresepfk',
                'sn.satuanresep',
                'pp.strukterimafk'
            )
            ->select(
                'pr.namaproduk',
                'pp.produkfk',
                DB::raw('SUM(pp.jumlah) AS qtyproduk_total'), // Penjumlahan jumlah berdasarkan produkfk
                'pp.hargasatuan',
                'pp.tglkadaluarsa',
                'pr.objectsatuanstandarfk',
                'pp.satuanviewfk',
                'pp.hargadiscount',
                'pp.nilaikonversi as hasilkonversi',
                'pp.dosis',
                'pp.jenisobatfk',
                'pr.kekuatan',
                'pp.ispagi',
                'pp.issiang',
                'pp.ismalam',
                'pp.issore',
                'pp.keteranganpakai',
                'pp.satuanresepfk',
                'sn.satuanresep',
                'pp.strukterimafk'
            )
            ->orderBy('pp.produkfk')
            ->get();
    
        return $this->respond($data);
    }

    public function returObatPerawat(Request $r) 
    {
        // return $r;
        DB::beginTransaction();
        try {
            // $arr = [];
            $arrNorecRetur = [];
            foreach($r['data'] as $kObat => $vObat) {
                $table_trans = new StrukReturPerawat();

                $norecTransaction=$table_trans->generateNewId();    
                $table_trans->norec=$norecTransaction;
                $table_trans->kdprofile=$this->kdProfile;
                $table_trans->statusenabled=true;
                $table_trans->strukresepfk=$vObat['strukresepfk'];
                $table_trans->objectnorecapdfk=$vObat['id_apd'];
                $table_trans->noregistrasifk=$vObat['noregistrasifk'];
                $table_trans->pelayananpasienfk=$vObat['id_pp'];
                $table_trans->qtyretur=$vObat['jumlah'];
                $table_trans->statusorder = 0;
                $table_trans->objectpegawaifk = $this->getUserId();
                $table_trans->save();

                
                // $newSRetur = new StrukRetur();
                // $norecSRetur = $newSRetur->generateNewId();
                // $noRetur = $this->generateCode(new StrukRetur, 'noretur', 12, 'Ret/' . $this->getDateTime()->format('ym') . '/', $this->kdProfile);
                // $newSRetur->norec = $norecSRetur;
                // $newSRetur->kdprofile = $this->kdProfile;
                // $newSRetur->statusenabled = true;
                // $newSRetur->objectkelompoktransaksifk = $this->kelompokTransaksi('RETUR RUANGAN');
                // $newSRetur->keteranganalasan = '';
                // $newSRetur->keteranganlainnya = 'RETUR OBAT ALKES';
                // $newSRetur->noretur = $noRetur;
                // $newSRetur->objectruanganfk = $vObat['ruanganfk'];
                // $newSRetur->objectpegawaifk = $this->getPegawaiId();
                // $newSRetur->tglretur = $this->getDateTime()->format('Y-m-d H:i:s');
                // $newSRetur->strukresepfk = $vObat['strukresepfk'];
                // $newSRetur->jumlahitem = $vObat['jumlahAwal'];
                // $newSRetur->totalretur = $vObat['jumlah'];
                // $newSRetur->save();
                // $arrNorecRetur[$vObat['strukresepfk']] = $newSRetur->norec;

                // $newPPR = new PelayananPasienRetur();
                // $norecPPR = $newPPR->generateNewId();
                // $newPPR->norec = $norecPPR;
                // $newPPR->kdprofile = $this->kdProfile;
                // $newPPR->statusenabled = true;
                // $newPPR->noregistrasifk = $vObat['noregistrasifk'] ?? null;
                // $newPPR->tglregistrasi = $vObat['tglregistrasi'] ?? null;
                // $newPPR->aturanpakai = $vObat['aturanpakai'] ?? null;
                // $newPPR->generik = $vObat['generik'] ?? null;
                // $newPPR->hargadiscount = $vObat['hargadiscount'] ?? null;
                // $newPPR->hargajual = $vObat['hargajual'] ?? null;
                // $newPPR->hargasatuan = $vObat['hargasatuan'] ?? null;
                // $newPPR->jenisobatfk = $vObat['jenisobatfk'] ?? null;
                // $newPPR->jumlah = $vObat['jumlah'] ?? 0;
                // $newPPR->kelasfk = $vObat['kelasfk'] ?? null;
                // $newPPR->kdkelompoktransaksi = 1;
                // $newPPR->produkfk = $vObat['objectprodukfk'] ?? null;
                // if (isset($vObat['routefk'])) {
                //     $newPPR->routefk = $vObat['routefk'];
                // }
                // $newPPR->stock = isset($vObat['stockpp']) ? $vObat['stockpp'] : ($vObat['jmlstok'] ?? '0');
                // $newPPR->tglpelayanan = $r_SR['tglpelayanan'] ?? null;
                // $newPPR->harganetto = $vObat['harganetto'] ?? 0;
                // $newPPR->jeniskemasanfk = $vObat['jeniskemasanfk'] ?? 0;
                // $newPPR->rke = $vObat['rke'] ?? null;
                // $newPPR->strukresepfk = $vObat['strukresepfk'] ?? null;
                // $newPPR->satuanviewfk = $vObat['satuanviewfk'] ?? null;
                // $newPPR->nilaikonversi = $vObat['nilaikonversi'] ?? 0;
                // $newPPR->strukterimafk = $vObat['nostrukterimafk'] ?? null;
                // $newPPR->dosis = $vObat['dosis'] ?? null;
                // $newPPR->jasa = 0;
                // $newPPR->strukreturfk = $norecSRetur;
                // $newPPR->save();

                // $saldoAwal = 0;
                // $TambahStok = (float)$vObat['jumlah'] * (float)$vObat['nilaikonversi'];
                // $dataSaldoAwal = collect(DB::select("
                //         select sum(qtyproduk) as qty from stokprodukdetail_t
                //         where statusenabled = true and kdprofile = $this->kdProfile and objectruanganfk=$vObat[ruanganfk]
                //         and objectprodukfk=$vObat[objectprodukfk]
                // "))->first();

                // $saldoAkhir = (float)$dataSaldoAwal->qty + (float)$TambahStok;;

                // $newSPD = StokProdukDetail::where('nostrukterimafk', $vObat['nostrukterimafk'])
                //     ->where('kdprofile', $this->kdProfile)
                //     ->where('statusenabled', true)
                //     ->where('objectruanganfk', $vObat['ruanganfk'])
                //     ->where('objectprodukfk', $vObat['objectprodukfk'])
                //     ->orderby('tglkadaluarsa', 'desc')
                //     ->first();

                // if(isset($newSPD)) {
                //     DB::table('stokprodukdetail_t')
                //         ->where('kdprofile', $this->kdProfile)
                //         ->where('statusenabled', true)
                //         ->where('norec', $newSPD->norec)
                //         ->lockForUpdate()
                //         ->increment('qtyproduk', (float)$TambahStok);
                //     $this->kartu_STOK(array(
                //         "saldoawal" => (float)$dataSaldoAwal->qty,
                //         "qtyin" => (float)$TambahStok,
                //         "qtyout" => 0,
                //         "saldoakhir" => $saldoAkhir,
                //         "keterangan" => 'Retur Resep sudah dibayar No. ' . $vObat['noorder'],
                //         "produkfk" => $vObat['objectprodukfk'],
                //         "ruanganfk" => $vObat['ruanganfk'],
                //         "tglinput" => date('Y-m-d H:i:s'),
                //         "tglkejadian" => date('Y-m-d H:i:s'),
                //         "nostrukterimafk" => $newSPD->nostrukterimafk,
                //         "norectransaksi" => $newSPD->norec,
                //         "tabletransaksi" => 'pelayananpasien_t',
                //         "stokprodukdetailfk" => $newSPD->norec,
                //         "flagfk" => 3,
                //     ));
                // }

                $this->LOGGING(
                    'Retur Resep',
                    $vObat['strukresepfk'] ?? '',
                    'pelayananpasien_t',
                    'Retur Resep ' . ($vObat['strukresepfk'] ?? '') . '. Dengan produk ' . ($vObat['namaproduk'] ?? '') . '. Atas pasien ' . ($vObat['noregistrasi'] ?? '')
                );
            }

            DB::commit();
            $result = [
                "status" => 200,
                "result" => $arrNorecRetur,
                "message" => "Retur Obat Berhasil"
            ];

        } catch (\Exception $e) {
            DB::rollBack();
            $result = array(
                "status" => 400,
                "result"  => null,
                "message" => $e->getMessage()
            );
        }
        return $this->respond($result['result'], $result['status'], $result['message']);
    }
    
}
