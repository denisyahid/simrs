<?php

namespace App\Http\Controllers\Laboratorium;

use App\Http\Controllers\Controller;
use App\Models\Master\Ruangan;
use App\Models\Master\SettingDataFixed;
use App\Models\Transaksi\OrderPelayanan;
use App\Models\Transaksi\PasienDaftar;
use App\Models\Transaksi\PelayananPasien;
use App\Models\Transaksi\StrukOrder;
use App\Traits\Valet;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpKernel\Event\ResponseEvent;

class OrderLaboratoriumCtrl extends Controller
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
            ->where('objectdepartemenfk', $this->settingFix('idDepartemenLab'))
            ->orderBy('kodeexternal', 'ASC')
            ->get();

        return $this->respond($res);
    }
    public function listTindakanForOrder(Request $r)
    {
        // $setting = $this->settingFix('isFilterProdukLab');
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
                'hnp.objectkelasfk',
                'hnp.objectkebangsaanfk',
                'hnp.objectkelasfk',
                'prd.objectdetailjenisprodukfk',
                'mpr.objectruanganfk',
                'prd.namaproduk',
                DB::raw('MAX(hnp.hargasatuan) as hargasatuan')
            )
            ->where('mpr.kdprofile', $kdProfile)
            ->where('mpr.objectruanganfk', $r['ruanganfk'])
            ->where('mpr.statusenabled', true)
            ->where('hnp.objectkebangsaanfk',$r['idkebangsaan'])
            ->where('hnp.objectkelasfk',$r['kelasfk'])
            ->where('prd.statusenabled', true);

        // if(!empty($setting) && $setting == 'true'){
        //     $data = $data->where('prd.isorderlab', true);
        // }else{
        //     $data = $data->whereRaw("(prd.isorderlab is null or prd.isorderlab =false ) ");
        // }
        $array=[3417,3418,3419,3420,3421,3422,3423,3424,3425,3426,3427,3428,3429,3536,3415,3416,3414];
        if(isset($r['isruangan']) && $r['isruangan'] == 'true' && isset($r['ruanganfk']) && $r['ruanganfk'] == 302){
            $data=$data->whereNotIn('prd.id',$array);
        };
        $data = $data->groupBy('mpr.objectprodukfk', 'prd.namaproduk','hnp.objectkelasfk','hnp.objectkebangsaanfk', 'prd.objectdetailjenisprodukfk', 'mpr.objectruanganfk');
        $data = $data->orderBy('prd.namaproduk', 'ASC');
        $data = $data->get();
        // return $data;
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

    public function simpanOrderLab(Request $request)
    {

        DB::beginTransaction();
        try {
            $idProfile = (int) $this->kdProfile;
            date_default_timezone_set('Asia/Jakarta');

            $dataPD = PasienDaftar::where('norec', $request['norec_pd'])->first();
            $pasien = DB::table('pasien_m')->where('id', $dataPD->nocmfk)->first();
            $ruDep = DB::table('ruangan_m as ru')
            ->join('departemen_m as dep' ,'dep.id' ,'ru.objectdepartemenfk')
            ->where('ru.id',$request['objectruangantujuanfk'])->first();

            if ($request['norec_so'] == "") {
                $setting = json_decode($this->settingFix('settingSeqNoOrder'));
                $noOrder = '';
                $ket = '';
                $kelompokTransaksi = null;
                foreach ($setting as $set) {
                    if ($request['departemenfk']  == $set->objectdepartemenfk) {
                        $noOrder = $this->SEQUENCE(new StrukOrder(), $set->seqname, 11, $set->prefix . date('ym'), $idProfile);
                        $ket = $set->desc;
                        $kelompokTransaksi = $set->kelompoktransfk;
                        break;
                    }
                }
                if ($noOrder == '') {
                    $transMessage = "Gagal mengumpukan data, Coba lagi.!";
                    DB::rollBack();
                    $result = array("status" => 400, "result"  => null);
                    return $this->respond($result['result'], $result['status'], $transMessage);
                }

                $dataSO = new StrukOrder;
                $dataSO->norec = $dataSO->generateNewId();
                $dataSO->kdprofile = $idProfile;
                $dataSO->statusenabled = true;
                $dataSO->nocmfk = $dataPD->nocmfk;
                $typeLog = "Tambah";
            }

            else{
                $dataSO =  StrukOrder::where('norec',$request['norec_so'] )->first();
                $noOrder = $dataSO->noorder;
                $ket = $dataSO->keteranganorder;
                $kelompokTransaksi =  $dataSO->objectkelompoktransaksifk;
                OrderPelayanan::where('strukorderfk',$request['norec_so'])->delete();
                $typeLog = "Edit";
            }

            $dataSO->isdelivered = 1;
            $dataSO->noorder = $noOrder;
            $dataSO->noorderintern = $noOrder;
            $dataSO->noregistrasifk = $dataPD->norec;
            $dataSO->objectpegawaiorderfk = $request['pegawaiorderfk'];
            $dataSO->qtyjenisproduk = 1;
            $dataSO->qtyproduk = $request['qtyproduk'];
            $dataSO->objectruanganfk = $request['objectruanganfk'];
            $dataSO->objectruangantujuanfk = $request['objectruangantujuanfk'];
            $dataSO->keteranganorder = $ket;
            $dataSO->objectkelompoktransaksifk = $kelompokTransaksi;
            $dataSO->tglpelayananakhir = $request['tgloperasi'];
            $dataSO->tglpelayananawal = $request['tgloperasi'];
            $dataSO->tgloperasi = $request['tgloperasi'];
            $dataSO->tglrencana = $request['tglrencana'];
            $dataSO->keteranganlainnya = $request['keterangan'];
            $dataSO->catatanklinis = $request['catatanKlinis'];
            $dataSO->jenisoperasifk = $request['jenisoperasifk'];
            $dataSO->tglorder = $request['tanggal'];
            $dataSO->totalbeamaterai = 0;
            $dataSO->statusorder = $request->has('langsungregis') ? 1 : 0;
            $dataSO->totalbiayakirim = 0;
            $dataSO->totalbiayatambahan = 0;
            $dataSO->totaldiscount = 0;
            $dataSO->totalhargasatuan = 0;
            $dataSO->totalharusdibayar = 0;
            $dataSO->totalpph = 0;
            $dataSO->totalppn = 0;
            $dataSO->cito = $request['iscito'];
            $dataSO->iselektif = $request['iselektif'];
            $dataSO->isurgent = $request['isurgent'];
            $dataSO->estimasiwaktuoperasi = isset($request['estimasiwaktuoperasi']) ? $request['estimasiwaktuoperasi'] : null;
            $dataSO->objectkamaroperasifk = isset($request['kamaroperasifk']) ? $request['kamaroperasifk'] : null;
            $dataSO->golongandarahfk = isset($request['golongandarahfk']) ? $request['golongandarahfk'] : null;
            $dataSO->golongandarahfk = isset($request['golongandarahfk']) ? $request['golongandarahfk'] : null;
            $dataSO->dokteroperatorfk = isset($request['dokteroperatorfk']) ? $request['dokteroperatorfk'] : null;
            $dataSO->dokteranastesifk = isset($request['dokteranastesifk']) ? $request['dokteranastesifk'] : null;
            $dataSO->diagnosis = isset($request['diagnosis']) ? $request['diagnosis'] : null;
            $dataSO->persiapan = isset($request['persiapan']) ? $request['persiapan'] : null;
            $dataSO->tb = isset($request['tb']) ? $request['tb'] : null;
            $dataSO->bb = isset($request['bb']) ? $request['bb'] : null;
            $dataSO->durasi = isset($request['durasi']) ? $request['durasi'] : null;
            $dataSO->jaminan = isset($request['jaminan']) ? $request['jaminan'] : null;
            $dataSO->noregistrasi = $request['noregistrasi'];
            $dataSO->norec_apd = $request['norec_apd'];
            $dataSO->operatorhelperfk = isset($request['dokteroperatortambahan']) ? $request['dokteroperatortambahan'] : null;
            $dataSO->nohp = isset($request['nohp']) ? $request['nohp'] : null;
            $dataSO->nohpkel = isset($request['nohpkel']) ? $request['nohpkel'] : null;
            $dataSO->alat = isset($request['alat']) ? $request['alat'] : null;
            $dataSO->riwayatswab = isset($request['riwayatSwab']) ? $request['riwayatSwab'] : null;
            $dataSO->riwayatvaksin = isset($request['riwayatvaksin']) ? $request['riwayatvaksin'] : null;
            $dataSO->isanastesi = isset($request['isanastesi']) ? $request['isanastesi'] : 0;
            $dataSO->anastesitambahanfk = isset($request['anastesitambahan']) ? $request['anastesitambahan'] : null;

            $dataSO->save();

            $dataSOnorec = $dataSO->norec;

            $listProduk = '';
            $prod = [];
            if(isset($request['details']) && !empty($request['details'])){    
                foreach ($request['details'] as $item) {
                    if($item['produkfk'] != "NaN"){
                        if (isset($request['status'])  && $request['status']== 'bridinglangsung') {
                            PelayananPasien::where('norec', $item['norec_pp'])
                                ->where('kdprofile', $idProfile)
                                ->update(
                                    [
                                        'strukorderfk' => $dataSOnorec
                                    ]
                                );
                        }
                        // if(isset($request['status'])  && $request['status'] == 'notBridging'){
                        // //     $compareData=DB::table('orderpelayanan_t as op')->join('strukorder_t as so','so.norec','=','op.strukorderfk')->where('norec_apd',$request['norec_apd'])->select('op.objectprodukfk')->get()->toArray();
                        // //    foreach ($compareData as  $valueCompare) {
                        // //     if($item['produkfk'] != $valueCompare->objectprodukfk){
                        //         $dataPelayanan = new PelayananPasien();
                        //         $dataPelayanan->norec = $dataPelayanan->generateNewId();
                        //         $dataPelayanan->kdprofile = $this->kdProfile;
                        //         $dataPelayanan->statusenabled = true;
                        //         $dataPelayanan->noregistrasifk = $request['norec_apd'];
                        //         $dataPelayanan->aturanpakai = '-';
                        //         $dataPelayanan->hargadiscount = 0;
                        //         $dataPelayanan->hargajual = $item['hargaLayanan'];
                        //         $dataPelayanan->hargasatuan = $item['hargaLayanan'];
                        //         $dataPelayanan->jumlah = $item['qtyproduk'];
                        //         $dataPelayanan->kdkelompoktransaksi =  1;
                        //         $dataPelayanan->piutangpenjamin = 0;
                        //         $dataPelayanan->piutangrumahsakit = 0;
                        //         $dataPelayanan->produkfk =  $item['produkfk'];
                        //         $dataPelayanan->stock = 1;
                        //         $dataPelayanan->strukorderfk =  $dataSOnorec;
                        //         $dataPelayanan->tglpelayanan =  date('Y-m-d H:i:s');
                        //         $dataPelayanan->harganetto =  $item['hargaLayanan'];
                        //         $dataPelayanan->noregistrasi =  $request['noregistrasi'];
                        //         $dataPelayanan->istidaktagih = null;
                        //         $dataPelayanan->save();
                        // //     }
                        // //    }
                        // }
                        $pro = DB::table('produk_m')->where('id', $item['produkfk'])->first();
                        $prod[] = $pro->namaproduk;
        
                        $listProduk = $listProduk . ',' . $pro->namaproduk;
                        $listProduk = substr($listProduk, 1, strlen($listProduk) - 1);
        
                        $dataOP = new OrderPelayanan();
                        $dataOP->norec = $dataOP->generateNewId();
                        $dataOP->kdprofile = $idProfile;
                        $dataOP->statusenabled = true;
                        $dataOP->iscito = $request['iscito'];
                        $dataOP->noorderfk = $dataSOnorec;
                        $dataOP->objectprodukfk = $item['produkfk'];
                        $dataOP->qtyproduk = $item['qtyproduk'];
                        // $dataOP->objectkelasfk = $item['objectkelasfk'];
                        $dataOP->objectkelasfk = 2;
                        $dataOP->qtyprodukretur = 0;
                        $dataOP->objectruanganfk = $request['objectruanganfk'];
                        $dataOP->objectruangantujuanfk = $request['objectruangantujuanfk'];
                        $dataOP->strukorderfk = $dataSOnorec;
                        if (isset($item['pemeriksaanluar'])) {
                            if ($item['pemeriksaanluar'] == 1) {
                                $dataOP->keteranganlainnya = 'isPemeriksaanKeluar';
                            }
                        }
                        if (isset($item['tglrencana'])) {
                            $dataOP->tglpelayanan = $item['tglrencana'];
                        } else {
                            $dataOP->tglpelayanan = date('Y-m-d H:i:s');
                        }
                        if (isset($item['dokterid']) && $item['dokterid'] != "") {
                            $dataOP->objectnamapenyerahbarangfk = $item['dokterid'];
                        }
                        $dataOP->nourut = $item['nourut'];
                        $dataOP->noregistrasi = $request['noregistrasi'];
                        $dataOP->save();
                    }
                    
                }
            }

            $dataSO->tanggal_server =  date('Y-m-d H:i:s');
            $this->LOGGING(
                $typeLog . 'Order Pelayanan ' . $ruDep->namadepartemen,
                $dataSO->norec,
                'strukorder_t',
                $typeLog . 'Order Pelayanan ' . $ruDep->namadepartemen . ' ke rungan ' . $ruDep->namaruangan . ' pada Pasien ' .
                $pasien->namapasien  .' ' .'no RM :'.  $pasien->nocm  .' ' .'no Registrasi :'.  $dataPD->noregistrasi .' '
                .'dengan no order :'.  $dataSO->noorder
            );
            $transStatus = 'true';
        } catch (\Exception $e) {
            $transStatus = 'false';
        }

        if ($transStatus == 'true') {
            $transMessage = "Sukses";
            DB::commit();
            $objetoRequest = new \Illuminate\Http\Request();
            $objetoRequest['noorder'] = $noOrder;
            $ihs = app('App\Http\Controllers\Bridging\SATUSEHATCtrl')->ServiceRequest($objetoRequest, true);
            $result = array(
                "status" => 200,
                "result" => array(
                    "data"  => $dataSO,
                    "ServiceRequest" => $ihs,
                    "as" => '@epic',
                ),
            );
        } else {
            $transMessage = "Simpan Gagal";
            DB::rollBack();
            $result = array(
                "status" => 400,
                "result"  =>$e->getMessage(). ' '.$e->getLine()

            );
        }
        return $this->respond($result['result'], $result['status'], $transMessage);
    }

    public function orderMerge (Request $req){
    
        $targetUpdate=$req['details'];

        $value=DB::table('strukorder_t')->where('norec_apd',$req['norec_apd'])->where('noorder','like','NT%')->orderByDesc('created_at')->pluck('norec')->first();

        if(empty($value)){
            $value=DB::table('strukorder_t')->where('norec_apd',$req['norec_apd'])->where('noorder','like','L%')->orderByDesc('created_at')->pluck('norec')->first();
        }
        
        try {
            $updateQuery=DB::table('pelayananpasien_t')->whereIn('norec',$targetUpdate)->whereNull('strukorderfk')->update([
                'strukorderfk'=>$value 
            ]);
            DB::commit();
            $result=array(
                "code"=>201,
                "message"=>'sukses update data',
                'rowAffected'=>$updateQuery
            );
        } catch (Exception $e) {
                DB::rollBack();
                $result=array(
                    "code"=>500,
                    "message"=>$e->getMessage() . ' Line '.$e->getLine()
                );
        }
        return $this->respond($result);
    }

    public function simpanOrderLabSusulan(Request $request)
    {

        DB::beginTransaction();
        try {
            $idProfile = (int) $this->kdProfile;
            
            $dataPD = PasienDaftar::where('norec', $request['norec_pd'])->first();
            if($request['islaboratorium'] == true && $request['isradiologi'] == false){
                $dataPD->isPenunjangSusulan = $request['tanggal'];
            } else if($request['islaboratorium'] == false && $request['isradiologi'] == true){
                $dataPD->isPenunjangSusulanRad = $request['tanggal'];
            }
            $dataPD->save();

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
                    "data"  => $dataPD,
                    "as" => '@epic',
                ),
            );
        } else {
            $transMessage = "Simpan Gagal";
            DB::rollBack();
            $result = array(
                "status" => 400,
                "result"  =>$e->getMessage(). ' '.$e->getLine()

            );
        }
        return $this->respond($result['result'], $result['status'], $transMessage);
    }

    public function riwayatOrderLIS(Request $r){
        $valueTargetPasien=DB::table('pasien_m')->where('id',$r['nocmfk'])->select('nocm')->first();
        if(empty($valueTargetPasien)){
            return $this->respond('data tidak ditemukan');
        }
        $dataMandiriDaftar = DB::connection('sqlsrv_lis')
        ->table('reshd as rh')
        ->where('rh.PID',$valueTargetPasien->nocm)
        ->whereNotNull('rh.pdf_url')
        ->distinct()
        ->orderByDesc('rh.ID')
        ->get('rh.*');
        if(empty($dataMandiriDaftar)){
            $result=array(
                "dataLIS"=>null
            );
        }
        $result=array(
            "dataLIS"=>$dataMandiriDaftar
        );
        return $this->respond($result);
    }



    public function listRiwayatOrder(Request $r){
        $kdProfile =  $this->kdProfile;
        $depLab = $this->settingFix('idDepartemenLab');
        $nocmfk = '';
        $norec_pd = '';
        if(isset($r['nocmfk'] ) && $r['nocmfk'] !=''){
            $nocmfk = " and pd.nocmfk='".$r['nocmfk'] ."'";
        }
        if(isset($r['norec_pd'] ) && $r['norec_pd'] !=''){
            $norec_pd = " and pd.norec='".$r['norec_pd'] ."'";
        }
        $data = collect(DB::select("select so.tglorder,so.noorder,so.noregistrasi,
        pr.id,pr.namaproduk,op.qtyproduk,so.norec,ru2.namaruangan as ruangantujuan,
        ru.namaruangan as ruanganasal,p.namalengkap as dokter,
        case when so.statusorder = 1 then 'verifikasi'
        when so.statusorder = 2 then 'selesai'
        else 'pending' end as status,
        case when so.statusorder = 1 then 'info'
        when so.statusorder = 2 then 'success'
        else 'warning' end as color_status,so.norec_apd,
        op.norec as norec_op,pd.noregistrasi,so.objectruangantujuanfk
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
        and op.statusenabled=true
        and so.statusenabled=true
        and ru2.objectdepartemenfk ='$depLab'
        $norec_pd
        $nocmfk
        union all

        select pp.tglpelayanan as tglorder,null as noorder,pd.noregistrasi,
        pr.id,pr.namaproduk,pp.jumlah as qtyproduk ,pp.norec,
        ru2.namaruangan as ruanganasal,p.namalengkap as dokter,
        'selesai' as status,'success' as color_status,apd.norec as norec_apd,
        null as norec_op,pd.noregistrasi,null as ruangantujuan,apd.objectruanganfk as objectruangantujuanfk
        from pelayananpasien_t as pp
        left join antrianpasiendiperiksa_t as apd on apd.norec=pp.noregistrasifk
        inner join pasiendaftar_t as pd on pd.norec=apd.noregistrasifk
        inner join ruangan_m as ru on ru.id=apd.objectruanganfk
        inner join ruangan_m as ru2 on ru2.id=pd.objectruanganlastfk
        inner join pasien_m as ps on ps.id=pd.nocmfk
        left join produk_m as pr on pr.id=pp.produkfk
        left join pegawai_m as p on p.id=apd.objectpegawaifk
        where
        pd.kdprofile=$kdProfile
        and pd.statusenabled=true
        and pp.strukresepfk is NULL
        and pp.strukorderfk is null
        and ru.objectdepartemenfk ='$depLab'
        $norec_pd
        $nocmfk
        ORDER BY tglorder"));
        if(isset($r['ruangan']) && $r['ruangan'] != '' && $r['ruangan'] == 'IGD') {
            $data = $data->sortByDesc('tglorder')->values();
        }
        $sama = false;
        $group  = [];
        // return $data;
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
                            'idproduk' =>  $gg->id,
                            'namaproduk' =>  $gg->namaproduk,
                            'pp_norec' =>  $gg->norec,
                        );
                    };
                }
                $group[] = array(
                    'tglorder' => $item->tglorder,
                    'noregistrasi' => $item->noregistrasi,
                    'noorder' => $item->noorder,
                    'norec' => $item->norec,
                    'ruanganasal' => $item->ruanganasal,
                    'dokter' => $item->dokter,
                    'color_status' => $item->color_status,
                    'status' => $item->status,
                    'ruangantujuan' => $item->ruangantujuan,
                    'idruangantujuan' => $item->objectruangantujuanfk,
                    'norec_apd' => $item->norec_apd,
                    'noregistrasi' => $item->noregistrasi,
                    'details' => $dataDetail0
                );
            }
        }
        return $this->respond($group);
    }

    public function hapusOrderLab(Request $request)
    {
        DB::beginTransaction();
        try {
            $idProfile = $this->kdProfile;

            StrukOrder::where('noorder',$request['noorder'])
            ->where('kdprofile',$idProfile)
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
                    "data"  => null,
                    "as" => '@epic',
                ),
            );
        } else {
            $transMessage = "Hapus Data Gagal";
            DB::rollBack();
            $result = array(
                "status" => 400,
                "result"  => null

            );
        }
        return $this->respond($result['result'], $result['status'], $transMessage);
    }

    public function hapusAmprahLab(Request $request)
    {
        DB::beginTransaction();
        try {
            $idProfile = $this->kdProfile;

            $cek = StrukOrder::where('norec',$request['norec'])->where('kdprofile',$idProfile)->first();
            $cek->statusenabled = false;
            $cek->isbatalamprah = true;
            $cek->tglbatal = date('Y-m-d H:i:s');
            $cek->save();

            PelayananPasien::where('strukorderfk', $request['norec'])
                ->where('kdprofile', $this->kdProfile)
                ->update(
                    [
                        'statusenabled' => false
                    ]
                );

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
                    "data"  => null,
                    "as" => '@vexana',
                ),
            );
        } else {
            $transMessage = "Hapus Data Gagal";
            DB::rollBack();
            $result = array(
                "status" => 400,
                "result"  => null

            );
        }
        return $this->respond($result['result'], $result['status'], $transMessage);
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

    public function saveBerkasLab(Request $r)
    {
        DB::beginTransaction();
        try {

            $uploadBerkasPasien = $r->file('file');
            $path = 'berkas_lab/' . $r['norec_so'];
            $extension = $uploadBerkasPasien->getClientOriginalExtension();
            $filename =  $r['norec_so']. '.' . $extension;
            StrukOrder::where('norec',$r['norec_so'])->update([
            'namafile' => $filename]);


            $r->fPut(
                $path . '/' . $filename,
                File::get($r->file('file')->getRealPath()),
                'public'
            );
            DB::commit();
            $transMessage = 'Sukses';
            $result = array(
                "status" => 200,
                "result" => array(
                    "as" => '@epic',
                ),
            );
        } catch (\Exception $e) {
            $transMessage = 'Simpan Gagal';
            DB::rollBack();
            $result = array(
                "status" => 400,
                "result"  => null
            );
        }

        return $this->respond($result['result'], $result['status'], $transMessage);
    }
    public function updateFilterProd(Request $request)
    {
        DB::beginTransaction();
        try {
            SettingDataFixed::where('namafield', 'isFilterProdukLab')
                ->where('kdprofile', $this->kdProfile)
                ->update(
                    [
                        'nilaifield' => $request['isFilterProdukLab']
                    ]
                );

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
                "result"  =>$e->getMessage(). ' '.$e->getLine()

            );
        }
        return $this->respond($result['result'], $result['status'], $transMessage);
    }

    public function simpanOrderLabPA(Request $request)
    {
        /*
            Simpan order LAB PA dijadikan satu struk order per pemeriksaan
        */

        DB::beginTransaction();
        try {
            date_default_timezone_set('Asia/Jakarta');
            $idProfile = (int) $this->kdProfile;
            $setting = json_decode($this->settingFix('settingSeqNoOrder'));
            $dataPD = PasienDaftar::where('norec', $request['norec_pd'])->first();
            $pasien = DB::table('pasien_m')->where('id', $dataPD->nocmfk)->first();
            $ruDep = DB::table('ruangan_m as ru')
                ->join('departemen_m as dep' ,'dep.id' ,'ru.objectdepartemenfk')
                ->where('ru.id', $request['objectruangantujuanfk'])
                ->first();

            if(!empty($request['details']) && count($request['details']) != 0){    
                foreach ($request['details'] as $index => $item) { 
                    if($item['produkfk'] != "NaN") {
                        if ($request['norec_so'] == "") {
                            $noOrder = '';
                            $ket = '';
                            $kelompokTransaksi = null;

                            foreach ($setting as $set) {
                                if ($request['departemenfk']  == $set->objectdepartemenfk) {
                                    $noOrder = $this->SEQUENCE(new StrukOrder(), $set->seqname, 11, $set->prefix . date('ym'), $idProfile);
                                    $ket = $set->desc;
                                    $kelompokTransaksi = $set->kelompoktransfk;
                                    break;
                                }
                            }

                            if ($noOrder == '') {
                                $transMessage = "Gagal mengumpukan data, Coba lagi.!";
                                DB::rollBack();
                                $result = array("status" => 400, "result"  => null);
                                return $this->respond($result['result'], $result['status'], $transMessage);
                            }

                            $dataSO = new StrukOrder;
                            $dataSO->norec = $dataSO->generateNewId();
                            $dataSO->kdprofile = $idProfile;
                            $dataSO->statusenabled = true;
                            $dataSO->nocmfk = $dataPD->nocmfk;
                            $typeLog = "Tambah";
                        }

                        else {
                            $dataSO =  StrukOrder::where('norec',$request['norec_so'] )->first();
                            $noOrder = $dataSO->noorder;
                            $ket = $dataSO->keteranganorder;
                            $kelompokTransaksi =  $dataSO->objectkelompoktransaksifk;
                            OrderPelayanan::where('strukorderfk',$request['norec_so'])->delete();
                            $typeLog = "Edit";
                        }

                        $dataSO->isdelivered = 1;
                        $dataSO->noorder = $noOrder;
                        $dataSO->noorderintern = $noOrder;
                        $dataSO->noregistrasifk = $dataPD->norec;
                        $dataSO->objectpegawaiorderfk = $request['pegawaiorderfk'];
                        $dataSO->qtyjenisproduk = 1;
                        $dataSO->qtyproduk = 1; // Karena dibuat 1 strukorder per pemeriksaan
                        $dataSO->objectruanganfk = $request['objectruanganfk'];
                        $dataSO->objectruangantujuanfk = $request['objectruangantujuanfk'];
                        $dataSO->keteranganorder = $ket;
                        $dataSO->objectkelompoktransaksifk = $kelompokTransaksi;
                        $dataSO->tglpelayananakhir = $request['tgloperasi'];
                        $dataSO->tglpelayananawal = $request['tgloperasi'];
                        $dataSO->tgloperasi = $request['tgloperasi'];
                        $dataSO->tglrencana = $request['tglrencana'];
                        $dataSO->keteranganlainnya = $request['keterangan'];
                        $dataSO->catatanklinis = $request['catatanKlinis'];
                        $dataSO->jenisoperasifk = $request['jenisoperasifk'];
                        $dataSO->tglorder = $request['tanggal'];
                        $dataSO->totalbeamaterai = 0;
                        $dataSO->statusorder = $request->has('langsungregis') ? 1 : 0;
                        $dataSO->totalbiayakirim = 0;
                        $dataSO->totalbiayatambahan = 0;
                        $dataSO->totaldiscount = 0;
                        $dataSO->totalhargasatuan = 0;
                        $dataSO->totalharusdibayar = 0;
                        $dataSO->totalpph = 0;
                        $dataSO->totalppn = 0;
                        $dataSO->cito = $request['iscito'];
                        $dataSO->iselektif = $request['iselektif'];
                        $dataSO->isurgent = $request['isurgent'];
                        $dataSO->estimasiwaktuoperasi = isset($request['estimasiwaktuoperasi']) ? $request['estimasiwaktuoperasi'] : null;
                        $dataSO->objectkamaroperasifk = isset($request['kamaroperasifk']) ? $request['kamaroperasifk'] : null;
                        $dataSO->golongandarahfk = isset($request['golongandarahfk']) ? $request['golongandarahfk'] : null;
                        $dataSO->golongandarahfk = isset($request['golongandarahfk']) ? $request['golongandarahfk'] : null;
                        $dataSO->dokteroperatorfk = isset($request['dokteroperatorfk']) ? $request['dokteroperatorfk'] : null;
                        $dataSO->dokteranastesifk = isset($request['dokteranastesifk']) ? $request['dokteranastesifk'] : null;
                        $dataSO->diagnosis = isset($request['diagnosis']) ? $request['diagnosis'] : null;
                        $dataSO->persiapan = isset($request['persiapan']) ? $request['persiapan'] : null;
                        $dataSO->tb = isset($request['tb']) ? $request['tb'] : null;
                        $dataSO->bb = isset($request['bb']) ? $request['bb'] : null;
                        $dataSO->durasi = isset($request['durasi']) ? $request['durasi'] : null;
                        $dataSO->jaminan = isset($request['jaminan']) ? $request['jaminan'] : null;
                        $dataSO->noregistrasi = $request['noregistrasi'];
                        $dataSO->norec_apd = $request['norec_apd'];
                        $dataSO->operatorhelperfk = isset($request['dokteroperatortambahan']) ? $request['dokteroperatortambahan'] : null;
                        $dataSO->nohp = isset($request['nohp']) ? $request['nohp'] : null;
                        $dataSO->nohpkel = isset($request['nohpkel']) ? $request['nohpkel'] : null;
                        $dataSO->alat = isset($request['alat']) ? $request['alat'] : null;
                        $dataSO->riwayatswab = isset($request['riwayatSwab']) ? $request['riwayatSwab'] : null;
                        $dataSO->riwayatvaksin = isset($request['riwayatvaksin']) ? $request['riwayatvaksin'] : null;
                        $dataSO->isanastesi = isset($request['isanastesi']) ? $request['isanastesi'] : 0;
                        $dataSO->anastesitambahanfk = isset($request['anastesitambahan']) ? $request['anastesitambahan'] : null;

                        $dataSO->save();
                        $dataSOnorec = $dataSO->norec;

                        $listProduk = '';
                        $prod = [];

                        if (isset($request['status'])  && $request['status']== 'bridinglangsung') {
                            PelayananPasien::where('norec', $item['norec_pp'])
                                ->where('kdprofile', $idProfile)
                                ->update(['strukorderfk' => $dataSOnorec]);
                        }

                        $pro = DB::table('produk_m')->where('id', $item['produkfk'])->first();
                        $prod[] = $pro->namaproduk;

                        $listProduk = $listProduk . ',' . $pro->namaproduk;
                        $listProduk = substr($listProduk, 1, strlen($listProduk) - 1);

                        $dataOP = new OrderPelayanan();
                        $dataOP->norec = $dataOP->generateNewId();
                        $dataOP->kdprofile = $idProfile;
                        $dataOP->statusenabled = true;
                        $dataOP->iscito = $request['iscito'];
                        $dataOP->noorderfk = $dataSOnorec;
                        $dataOP->objectprodukfk = $item['produkfk'];
                        $dataOP->qtyproduk = $item['qtyproduk'];
                        // $dataOP->objectkelasfk = $item['objectkelasfk'];
                        $dataOP->objectkelasfk = 2;
                        $dataOP->qtyprodukretur = 0;
                        $dataOP->objectruanganfk = $request['objectruanganfk'];
                        $dataOP->objectruangantujuanfk = $request['objectruangantujuanfk'];
                        $dataOP->strukorderfk = $dataSOnorec;
                        if (isset($item['pemeriksaanluar'])) {
                            if ($item['pemeriksaanluar'] == 1) {
                                $dataOP->keteranganlainnya = 'isPemeriksaanKeluar';
                            }
                        }
                        if (isset($item['tglrencana'])) {
                            $dataOP->tglpelayanan = $item['tglrencana'];
                        } else {
                            $dataOP->tglpelayanan = date('Y-m-d H:i:s');
                        }
                        if (isset($item['dokterid']) && $item['dokterid'] != "") {
                            $dataOP->objectnamapenyerahbarangfk = $item['dokterid'];
                        }
                        $dataOP->nourut = $item['nourut'];
                        $dataOP->noregistrasi = $request['noregistrasi'];
                        $dataOP->save();

                        $dataSO->tanggal_server =  date('Y-m-d H:i:s');
                        $this->LOGGING(
                            $typeLog . 'Order Pelayanan ' . $ruDep->namadepartemen,
                            $dataSO->norec,
                            'strukorder_t',
                            $typeLog . 'Order Pelayanan ' . $ruDep->namadepartemen . ' ke rungan ' . $ruDep->namaruangan . ' pada Pasien ' .
                            $pasien->namapasien  .' ' .'no RM :'.  $pasien->nocm  .' ' .'no Registrasi :'.  $dataPD->noregistrasi .' '
                            .'dengan no order :'.  $dataSO->noorder
                        );                        

                        try {
                            $objetoRequest = new \Illuminate\Http\Request();
                            $objetoRequest['noorder'] = $noOrder;
                            $ihs = app('App\Http\Controllers\Bridging\SATUSEHATCtrl')->ServiceRequest($objetoRequest, true);
                        } catch (\Exception $th) {
                            $ihs = null;
                        }
                    }
                }
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
                    "data"  => $dataSO,
                    // "ServiceRequest" => $ihs,
                    "as" => '@epic',
                ),
            );
        } else {
            $transMessage = "Simpan Gagal";
            DB::rollBack();
            $result = array(
                "status" => 400,
                "result"  =>$e->getMessage(). ' '.$e->getLine()

            );
        }
        return $this->respond($result['result'], $result['status'], $transMessage);
    }
}

