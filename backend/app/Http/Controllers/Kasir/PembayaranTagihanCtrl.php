<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use App\Models\Master\CaraBayar;
use App\Models\Master\Ruangan;
use App\Models\Transaksi\AntrianPasienDiperiksa;
use App\Models\Transaksi\PasienDaftar;
use App\Models\Transaksi\PelayananPasien;
use App\Models\Transaksi\PelayananPasienDetail;
use App\Models\Transaksi\StrukBuktiPenerimaan;
use App\Models\Transaksi\StrukBuktiPenerimaanCaraBayar;
use App\Models\Transaksi\StrukBuktiPengeluaran;
use App\Models\Transaksi\StrukBuktiPengeluaranCaraBayar;
use App\Models\Transaksi\StrukPelayanan;
use App\Models\Transaksi\StrukPelayananPenjamin;
use App\Traits\Valet;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;

class PembayaranTagihanCtrl extends Controller
{
    use Valet;
    public function __construct()
    {
        parent::__construct($is_encrypt = true);
    }
    public function dataPembayaranPasien(Request $r)
    {
        $kdProfile = (int)$this->kdProfile;
        $strukPelayanan = StrukPelayanan::where('norec', $r['norec'])
            ->first();
        $bill = 0;
        $dibayar = 0;
        // return $strukPelayanan;
        if ($r['norec_pd'] != '') {
            $pd = PasienDaftar::where('norec', $r['norec_pd'])->first();
            $bill =  PelayananPasien::totalTagihan($pd->noregistrasi);
            if($r['from'] != 'ubahCaraBayar') {
                $dibayar = StrukBuktiPenerimaan::totalBayar($pd->noregistrasi);
            }
            // var_dump($dibayar);
        } else {
            $result['pasien'] = array(
                'nocm' => null,
                'namapasien' => $strukPelayanan->namapasien_klien,
                'nohp' => $strukPelayanan->noteleponfaks
            );
        }
        

        $result['title'] =  !empty($strukPelayanan) ?  'TOTAL HARUS BAYAR' : 'TOTAL BILLING';
        $result['jumlahBayar'] =  !empty($strukPelayanan) ? (float) $strukPelayanan->totalharusdibayar +  (float)  $strukPelayanan->totaliurbayar - $dibayar : $bill - $dibayar;
        $result['hargaIur'] = !empty($strukPelayanan) ? (float) $strukPelayanan->totaliurbayar : 0;
        $result['isIUR'] =  !empty($strukPelayanan) ? ((float)  $strukPelayanan->totaliurbayar != 0 ? true:false ): false;
        $result['terbilang'] =  $this->terbilang($result['jumlahBayar']);
        $result['carabayar'] =  CaraBayar::mine()->get();
        $result['ruangan'] = Ruangan::mine()->where('objectdepartemenfk',$this->settingFix('idDepartemenKasir'))->get();

        $result['as'] = '@epic';

        return $this->respond($result);
    }
    public function simpanVerifikasiTagihan(Request $r)
    {

        $kdProfile = $this->kdProfile;
        DB::beginTransaction();
        try {
            $pelayanan = PelayananPasien::where('noregistrasi', $r['noregistrasi'])
                ->where('kdprofile', $kdProfile)
                ->whereNull('strukfk')
                ->get();
            if (count($pelayanan) == 0) {
                abort(400, 'Pelayanan yang dilakukan pasien tidak ada');
            }
            $noStruk = $this->SEQUENCE(new StrukPelayanan(), 'noverifikasi_tagihan', 10, 'S', $kdProfile);
            if ($noStruk == '') {
                abort(400, 'SEQ ERROR');
            }
            $PD = PasienDaftar::where('noregistrasi', $r['noregistrasi'])
                ->where('kdprofile', $kdProfile)
                ->first();
            // $totalBilling = 0;
            $totalKlaim = (float)$r['klaim'];
            // $pelayananH = PelayananPasien::where('noregistrasi', $r['noregistrasi'])
            // ->where('kdprofile', $kdProfile)
            // ->get();
            // foreach ($pelayananH as $pel) {
            //     $harga = ($pel->hargajual == null) ? 0 : $pel->hargajual;
            //     $diskon = ($pel->hargadiscount == null) ? 0 : $pel->hargadiscount;
            //     $totalBilling += (($harga - $diskon) * $pel->jumlah) + $pel->jasa;
            // }

            $SP = new StrukPelayanan();
            $SP->norec = $SP->generateNewId();
            $SP->kdprofile = $kdProfile;
            $SP->statusenabled = true;
            $SP->nocmfk = $PD->nocmfk;
            $SP->noregistrasifk = $PD->norec;
            $SP->objectkelaslastfk = $PD->objectkelasfk;
            $SP->objectkelompoktransaksifk = $this->kelompokTransaksi('VERIFIKASI TAGIHAN PASIEN');
            $SP->objectpegawaipenerimafk = $this->getPegawaiId();
            $SP->nostruk = $noStruk;
            $SP->totalharusdibayar = (float)$r['totalbayar'];
            $SP->tglstruk = $this->now();
            $SP->objectruanganfk = $PD->objectruanganlastfk;
            $SP->totalprekanan = $totalKlaim;
            $SP->save();
            foreach ($r['details'] as $chklist) {
                PelayananPasien::where('norec', $chklist['norec'])
                    ->where('kdprofile', $kdProfile)
                    ->update(
                        [
                            'strukfk' => $SP->norec
                        ]
                    );
                PelayananPasienDetail::where('pelayananpasien', $chklist['norec'])
                    ->where('kdprofile', $kdProfile)
                    ->update(
                        [
                            'strukfk' => $SP->norec
                        ]
                    );
            }

            if ($totalKlaim > 0) {
                $SPP = new StrukPelayananPenjamin();
                $SPP->norec = $SPP->generateNewId();
                $SPP->statusenabled = true;
                $SPP->kdprofile = $kdProfile;
                $SPP->kdkelompokpasien = $PD->objectkelompokpasienlastfk;
                $SPP->kdrekananpenjamin = $PD->objectrekananfk;
                $SPP->totalbiaya = (float)$r['totalbayar'] + $totalKlaim + (float)$r['deposit'];
                $SPP->totalsudahppenjamin = $totalKlaim;
                $SPP->totalsisaharusdibayar = $totalKlaim;
                $SPP->totalppenjamin = $totalKlaim;
                $SPP->totalharusdibayar = $totalKlaim;
                $SPP->totalsudahdibayar = 0;
                $SPP->totalsudahdibebaskan = 0;
                $SPP->totalsisapiutang = $totalKlaim;
                $SPP->totaldibayarlebih = 0;
                $SPP->nostrukfk = $SP->norec;
                $PD->nostruklastfk = $SP->norec;
                $SPP->save();
            }
            $PD->statusbayar = 'Verifikasi';
            $PD->nostruklastfk = $SP->norec;
            $PD->save();

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
                    "sp"  => $SP,
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

    public function simpanPembayaran(Request $r)
    {
        switch ($r['parameterTambahan']) {
            case 'depositPasien':
                return $this->simpanPembayaranDeposit($r);
                break;
            case 'pengembalianDeposit':
                return $this->simpanPengembalianDeposit($r);
                break;
            case 'PenyetoranDepositKasirKembali':
                return $this->simpanPengembalianDeposit($r);
                break;
            case 'tagihanPasien':
                return $this->simpanPembayaranTagihanPasien($r);
                break;
            case 'cicilanPasien':
                return $this->simpanCicilanPasien($r);
                break;
            case 'tagihanNonLayanan':
                return $this->simpanPembayaranTagihanNonLayanan($r);
                break;
            case 'cicilanPasienCollect':
                return $this->simpanCicilanPasienCollect($r);
                break;
            case 'depositSewaAlat':
                return $this->simpanPembayaranSewaAlat($r);
                break;
            case 'PenyetoranDepositSewaAlatKembali':
                return $this->simpanPengembalianSewaAlat($r);
                break;
            case 'ubahCaraBayar':
                return $this->ubahCaraBayar($r);
            default:
                return 0;
        }
    }

    public function ubahCaraBayar(Request $r)
    {
        $kdProfile =  $this->kdProfile;
        DB::beginTransaction();
        try {
            $strukPelayanan = StrukPelayanan::where('norec', $r['norec'])
                ->where('kdprofile', $kdProfile)
                ->first();
            if (empty($strukPelayanan)) {
                abort(400, 'TAGIHAN TIDAK ADA');
            }
            $sisa = 0;
            if ($strukPelayanan->nosbmlastfk == null || $strukPelayanan->nosbmlastfk == '') {
                $sisa = $sisa + $this->getDepositPasien($strukPelayanan->noregistrasi);
            }
            $sisa = $sisa + $r['jumlahbayar'];

            $SBM = StrukBuktiPenerimaan::where('nostrukfk', $strukPelayanan->norec)->first();

            StrukBuktiPenerimaanCaraBayar::where('nosbmfk', $SBM->norec)->where('statusenabled', true)->update([
                'statusenabled' => false
            ]);

            
            foreach ($r['details'] as $pembayaran) {
                $SBPCB = new StrukBuktiPenerimaanCaraBayar();
                $SBPCB->norec = $SBPCB->generateNewId();
                $SBPCB->kdprofile = $kdProfile;
                $SBPCB->statusenabled = true;
                $SBPCB->nosbmfk = $SBM->norec;
                $SBPCB->objectcarabayarfk = $pembayaran['caraBayar']['id'];
                $SBPCB->totaldibayar = $pembayaran['nominal'];
                $SBPCB->save();
            }

            $strukPelayanan->nosbmlastfk = $SBM->norec;
            $strukPelayanan->save();
            $totalTagihan = PelayananPasien::totalTagihan($strukPelayanan->noregistrasi);

            $pd = $strukPelayanan->pasien_daftar;
            $pd->statusbayar = $sisa <=  $totalTagihan ? 'Lunas' : 'Bayar Sebagian';
            $pd->nosbmlastfk = $SBM->norec;
            $pd->save();

            if($r['objectstatuspiutangfk'] != "undefined" && $r['objectstatuspiutangfk'] != "null" && $r['objectstatuspiutangfk'] != 0){
                $pd = PasienDaftar::where('norec', $r['norec_pd'])
                ->where('kdprofile', $kdProfile)
                ->first();
                $pd->objectstatuspiutangfk = $r['objectstatuspiutangfk'];
                $pd->islunaspiutang = $r['islunaspiutang'];
                $pd->save();
            }

            $this->LOGGING(
                'Ubah Cara Bayar',
                $SBM->norec,
                'strukbuktipenerimaan_t',
                'Pengubahan Pembayaran Tagihan pada Pasien ' .
                    $r['namapasien'] .
                    ' (' . $r['nocm'] . ') - ' .
                    $SBM->nosbm
            );
            $transStatus = true;
        } catch (\Exception $e) {
            // return $e;
            $transMessage = $e->getMessage(). ' ' . $e->getLine();
            $transStatus = false;
        }
        if ($transStatus == true) {
            $transMessage = "Sukses";
            DB::commit();
            $result = array(
                "status" => 200,
                "result" => array(
                    "sbm"  => $SBM,
                    "sp" => $strukPelayanan,
                    "as" => '@epic',
                ),
            );
        } else {
            
            DB::rollBack();
            $result = array(
                "status" => 400,
                "result"  => null
            );
        }
        return $this->respond($result['result'], $result['status'], $transMessage);
    }

    public function simpanPembayaranTagihanPasien(Request $r)
    {
        $kdProfile =  $this->kdProfile;
        DB::beginTransaction();
        try {
            $strukPelayanan = StrukPelayanan::where('norec', $r['norec'])
                ->where('kdprofile', $kdProfile)
                ->first();
            if (empty($strukPelayanan)) {
                abort(400, 'TAGIHAN TIDAK ADA');
            }
            $sisa = 0;
            if ($strukPelayanan->nosbmlastfk == null || $strukPelayanan->nosbmlastfk == '') {
                $sisa = $sisa + $this->getDepositPasien($strukPelayanan->noregistrasi);
            }
            $sisa = $sisa + $r['jumlahbayar'];

            $SBM = new StrukBuktiPenerimaan();
            $SBM->norec = $SBM->generateNewId();
            $SBM->kdprofile = $kdProfile;
            $SBM->keteranganlainnya = (float)$strukPelayanan->totaliurbayar != 0 ? "Pembayaran IUR": "Pembayaran Tagihan";
            $SBM->statusenabled = true;
            $SBM->nostrukfk = $strukPelayanan->norec;
            $SBM->noregistrasi = $strukPelayanan->noregistrasi;
            $SBM->objectkelompokpasienfk = $strukPelayanan->pasien_daftar->pasien->objectkelompokpasienfk;
            $SBM->objectkelompoktransaksifk = $this->kelompokTransaksi('PEMBAYARAN TAGIHAN PASIEN');
            $SBM->objectpegawaipenerimafk  = $this->getPegawaiId();
            $SBM->tglsbm  =  $r['tglsbm'];
            if($r['objectstatuspiutangfk'] == 1){
                $SBM->totaldibayar = 0;
            } else{
                $SBM->totaldibayar = (float)$r['jumlahbayar'];
            }
            $SBM->totaldibayarbefore = (float)$r['jumlahbayarbefore'];
            $SBM->totaldiskon = (float)$r['diskon'];
            $SBM->namapegawaipenerima  =  $r['namapegawaipenerima'];
            $SBM->nosbm = $this->SEQUENCE(new StrukBuktiPenerimaan, 'nosbm', 14, 'RV-' . date('ym'), $kdProfile);
            if(isset($r['ruanganfk'])){
                $SBM->ruanganfk =  $r['ruanganfk'];
            }

            $SBM->save();

            foreach ($r['details'] as $pembayaran) {
                $SBPCB = new StrukBuktiPenerimaanCaraBayar();
                $SBPCB->norec = $SBPCB->generateNewId();
                $SBPCB->kdprofile = $kdProfile;
                $SBPCB->statusenabled = true;
                $SBPCB->nosbmfk = $SBM->norec;
                $SBPCB->objectcarabayarfk = $pembayaran['caraBayar']['id'];
                $SBPCB->totaldibayar = $pembayaran['nominal'];
                $SBPCB->save();
            }

            $strukPelayanan->nosbmlastfk = $SBM->norec;
            $strukPelayanan->save();
            $totalTagihan = PelayananPasien::totalTagihan($strukPelayanan->noregistrasi);

            $pd = $strukPelayanan->pasien_daftar;
            $pd->statusbayar = $sisa <=  $totalTagihan ? 'Lunas' : 'Bayar Sebagian';
            $pd->nosbmlastfk = $SBM->norec;
            $pd->save();

            if($r['objectstatuspiutangfk'] != "undefined" && $r['objectstatuspiutangfk'] != "null" && $r['objectstatuspiutangfk'] != 0){
                $pd = PasienDaftar::where('norec', $r['norec_pd'])
                ->where('kdprofile', $kdProfile)
                ->first();
                $pd->objectstatuspiutangfk = $r['objectstatuspiutangfk'];
                $pd->islunaspiutang = $r['islunaspiutang'];
                $pd->save();
            } else{
                $pd2 = PasienDaftar::where('norec', $r['norec_pd'])
                ->where('kdprofile', $kdProfile)
                ->first();

                if($pd2->objectstatuspiutangfk != null){
                    $pd2->objectstatuspiutangfk = null;
                    $pd2->islunaspiutang = true;
                    $pd2->save();

                    $sbm2 = StrukBuktiPenerimaan::where('nostrukfk', $strukPelayanan->norec)
                    ->where('kdprofile', $kdProfile)
                    ->first();
                    $sbm2->statusenabled = false;
                    $sbm2->save();
                }
            }

            // //update flag depos
            // $KT = $this->kelompokTransaksi('PEMBAYARAN DEPOSIT PASIEN');
            // StrukBuktiPenerimaan::where('noregistrasi',$strukPelayanan->noregistrasi)
            // ->where('kdprofile',$kdProfile)
            // ->where('objectkelompoktransaksifk', $KT)
            // ->update([
            //     'isbayar' => true
            // ]);

            $this->LOGGING(
                'Pembayaran Tagihan',
                $SBM->norec,
                'strukbuktipenerimaan_t',
                'Pembayaran Tagihan pada Pasien ' .
                    $r['namapasien'] .
                    ' (' . $r['nocm'] . ') - ' .
                    $SBM->nosbm
            );
            $transStatus = true;
        } catch (\Exception $e) {
            $transStatus = false;
        }
        if ($transStatus == true) {
            $transMessage = "Sukses";
            DB::commit();
            $result = array(
                "status" => 200,
                "result" => array(
                    "sbm"  => $SBM,
                    "sp" => $strukPelayanan,
                    "as" => '@epic',
                ),
            );
        } else {
            $transMessage = $e->getLine() .' - '. $e->getMessage();
            DB::rollBack();
            $result = array(
                "status" => 400,
                "result"  => null
            );
        }
        return $this->respond($result['result'], $result['status'], $transMessage);
    }
    protected function simpanPembayaranDeposit(Request $r)
    {
        $kdProfile =  $this->kdProfile;
        DB::beginTransaction();
        try {
            $pasienDaftar = PasienDaftar::where('norec', $r['norec_pd'])
                ->where('kdprofile', $kdProfile)
                ->first();

            $KT = $this->kelompokTransaksi('PEMBAYARAN DEPOSIT PASIEN');

            $SP = new StrukPelayanan();
            $SP->norec = $SP->generateNewId();
            $SP->kdprofile =  $kdProfile;
            $SP->statusenabled = true;
            $SP->nocmfk =  $pasienDaftar->nocmfk;
            $SP->noregistrasifk =  $pasienDaftar->norec;
            $SP->objectkelaslastfk =  $pasienDaftar->objectkelasfk;
            $SP->objectkelompoktransaksifk =  $KT;
            $SP->objectpegawaipenerimafk =  $this->getPegawaiId();
            $SP->nostruk = $this->SEQUENCE(new StrukPelayanan, 'nostruk_deposit', 10, 'D', $kdProfile);
            $SP->tglstruk =  $r['tglsbm'];
            $SP->objectruanganfk =  $pasienDaftar->objectruanganlastfk;
            $SP->noregistrasi =  $pasienDaftar->noregistrasi;
            // $SP->totalhargasatuan = PelayananPasien::totalTagihan($pasienDaftar->noregistrasi);
            $SP->totaldeposit = (float)$r['jumlahbayar'];
            $SP->save();

            $SBM = new StrukBuktiPenerimaan();
            $SBM->norec = $SBM->generateNewId();
            $SBM->kdprofile = $kdProfile;
            $SBM->keteranganlainnya = "Pembayaran Deposit";
            $SBM->statusenabled = true;
            $SBM->nostrukfk = $SP->norec;
            $SBM->noregistrasi = $pasienDaftar->noregistrasi;
            $SBM->objectkelompokpasienfk = $pasienDaftar->objectkelompokpasienlastfk;
            $SBM->objectkelompoktransaksifk = $KT;
            $SBM->objectpegawaipenerimafk  = $this->getPegawaiId();
            $SBM->tglsbm  =  $r['tglsbm'];
            $SBM->totaldibayar = (float)$r['jumlahbayar'];
            $SBM->namapegawaipenerima  =  $r['namapegawaipenerima'];
            $SBM->nosbm = $this->SEQUENCE(new StrukBuktiPenerimaan, 'nosbm', 14, 'RV-' . date('ym'), $kdProfile);
            $SBM->isbayar = false;
            if(isset($r['ruanganfk'])){
                $SBM->ruanganfk =  $r['ruanganfk'];
            }
            $SBM->save();

            foreach ($r['details'] as $pembayaran) {
                $SBPCB = new StrukBuktiPenerimaanCaraBayar();
                $SBPCB->norec = $SBPCB->generateNewId();
                $SBPCB->kdprofile = $kdProfile;
                $SBPCB->statusenabled = true;
                $SBPCB->nosbmfk = $SBM->norec;
                $SBPCB->objectcarabayarfk = $pembayaran['caraBayar']['id'];
                $SBPCB->totaldibayar = $pembayaran['nominal'];
                $SBPCB->save();
            }

            $transStatus = true;
        } catch (\Exception $e) {
            $transStatus = false;
        }
        if ($transStatus == true) {
            $transMessage = "Sukses";
            DB::commit();
            $result = array(
                "status" => 200,
                "result" => array(
                    "sbm"  => $SBM,
                    "sp"  => $SP,
                    "as" => '@epic',
                ),
            );
        } else {
            $transMessage = "Simpan Gagal"; // $e->getStatusCode() == 404 ? $e->getMessage() . ' '.$e->getLine(): "Simpan Gagal" ;
            DB::rollBack();
            $result = array(
                "status" => 400,
                "result"  => null, //$e->getMessage() . ' '.$e->getLine()
            );
        }
        return $this->respond($result['result'], $result['status'], $transMessage);
    }
    protected function simpanPengembalianDeposit($r)
    {
        $kdProfile =  $this->kdProfile;
        DB::beginTransaction();
        try {
            $pasienDaftar = PasienDaftar::where('norec', $r['norec_pd'])
                ->where('kdprofile', $kdProfile)
                ->first();
            $KT = $this->kelompokTransaksi('PEMBAYARAN DEPOSIT PASIEN');
            $KT2 = $this->kelompokTransaksi('PENGEMBALIAN DEPOSIT PASIEN');
            $SP = StrukPelayanan::where('noregistrasi', $pasienDaftar->noregistrasi)
                ->where('objectkelompoktransaksifk', $KT)
                ->where('kdprofile', $kdProfile)
                ->first();

            $SBM = new StrukBuktiPengeluaran();
            $SBM->norec = $SBM->generateNewId();
            $SBM->kdprofile = $kdProfile;
            $SBM->keteranganlainnya = "Pengembalian Deposit";
            $SBM->statusenabled = true;
            $SBM->nostrukfk = $SP->norec;
            $SBM->noregistrasi = $pasienDaftar->noregistrasi;
            $SBM->objectkelompokpasienfk = $pasienDaftar->objectkelompokpasienlastfk;
            $SBM->objectkelompoktransaksifk = $KT2;
            $SBM->objectpegawaipenerimafk  = $this->getPegawaiId();
            $SBM->tglsbk = $r['tglsbm'];
            $SBM->totaldibayar = (float)$r['jumlahbayar'];
            $SBM->namapegawaipenerima  =  $r['namapegawaipenerima'];
            $SBM->nosbk = $this->SEQUENCE(new StrukBuktiPengeluaran, 'nosbk', 14, 'PV-' . date('ym'), $kdProfile);
            // $SBM->totaldibebaskan = $SP->totaldeposit;
            // if(isset($r['ruanganfk'])){
            //     $SBM->ruanganfk =  $r['ruanganfk'];
            // }
            $SBM->save();

            foreach ($r['details'] as $pembayaran) {
                $SBPCB = new StrukBuktiPengeluaranCaraBayar();
                $SBPCB->norec = $SBPCB->generateNewId();
                $SBPCB->kdprofile = $kdProfile;
                $SBPCB->statusenabled = true;
                $SBPCB->nosbkfk = $SBM->norec;
                $SBPCB->carabayarfk = $pembayaran['caraBayar']['id'];
                $SBPCB->totaldibayar = $pembayaran['nominal'];
                $SBPCB->save();
            }

            $SP->nosbklastfk = $SBM->norec;
            $SP->isverifikasi = true;
            $SP->save();



            $transStatus = true;
        } catch (\Exception $e) {
            $transStatus = false;
        }
        if ($transStatus == true) {
            $transMessage = "Sukses";
            DB::commit();
            $result = array(
                "status" => 200,
                "result" => array(
                    "sbm"  => $SBM,
                    "sp"  => $SP,
                    "as" => '@epic',
                ),
            );
        } else {
            $transMessage = "Simpan Gagal";
            DB::rollBack();
            $result = array(
                "status" => 400,
                "result"  => $e->getMessage() . ' ' . $e->getLine()
            );
        }
        return $this->respond($result['result'], $result['status'], $transMessage);
    }
    protected function simpanPembayaranTagihanNonLayanan($r)
    {
        $kdProfile =  $this->kdProfile;
        DB::beginTransaction();
        try {
            $strukPelayanan = StrukPelayanan::where('norec', $r['norec'])
                ->where('kdprofile', $kdProfile)
                ->first();
            if (empty($strukPelayanan)) {
                abort(400, 'TAGIHAN TIDAK ADA');
            }

            if ($strukPelayanan->nosbmlastfk != null) {
                abort(400, 'TAGIHAN SUDAH DIBAYARKAN');
            }

            $SBM = new StrukBuktiPenerimaan();
            $SBM->norec = $SBM->generateNewId();
            $SBM->kdprofile = $kdProfile;
            $SBM->keteranganlainnya = "Pembayaran Non Layanan";
            $SBM->statusenabled = true;
            $SBM->nostrukfk = $strukPelayanan->norec;
            $SBM->objectkelompokpasienfk =  $strukPelayanan->objectkelompokpasienfk;
            $SBM->objectkelompoktransaksifk = $this->kelompokTransaksi('PEMBAYARAN TAGIHAN NON LAYANAN');
            $SBM->objectpegawaipenerimafk  = $this->getPegawaiId();
            $SBM->tglsbm  =  $r['tglsbm'];
            $SBM->totaldibayar = (float)$r['jumlahbayar'];
            $SBM->namapegawaipenerima  =  $r['namapegawaipenerima'];
            $SBM->nosbm = $this->SEQUENCE(new StrukBuktiPenerimaan, 'nosbm', 14, 'RV-' . date('ym'), $kdProfile);
            if(isset($r['ruanganfk'])){
                $SBM->ruanganfk =  $r['ruanganfk'];
            }
            $SBM->save();

            foreach ($r['details'] as $pembayaran) {
                $SBPCB = new StrukBuktiPenerimaanCaraBayar();
                $SBPCB->norec = $SBPCB->generateNewId();
                $SBPCB->kdprofile = $kdProfile;
                $SBPCB->statusenabled = true;
                $SBPCB->nosbmfk = $SBM->norec;
                $SBPCB->objectcarabayarfk = $pembayaran['caraBayar']['id'];
                $SBPCB->totaldibayar = $pembayaran['nominal'];
                $SBPCB->save();
            }

            $strukPelayanan->nosbmlastfk = $SBM->norec;
            $strukPelayanan->save();

            $this->LOGGING(
                'Pembayaran Tagihan Non Layanan',
                $SBM->norec,
                'strukbuktipenerimaan_t',
                'Pembayaran Tagihan Non Layanan pada Pasien ' .
                    $r['namapasien'] .
                    ' (' . $r['nocm'] . ') - ' .
                    $SBM->nosbm
            );
            $transStatus = true;
        } catch (\Exception $e) {
            $transStatus = false;
        }
        if ($transStatus == true) {
            $transMessage = "Sukses";
            DB::commit();
            $result = array(
                "status" => 200,
                "result" => array(
                    "sbm"  => $SBM,
                    "sp" => $strukPelayanan,
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

    protected function simpanCicilanPasien(Request $request)
    {

        $kdProfile = $this->kdProfile;
        DB::beginTransaction();
        try {
            $strukPelayananPenjamin = StrukPelayananPenjamin::where('norec', $request['parameter']['noRecStrukPelayanan'])
                ->where('kdprofile', $kdProfile)
                ->first();
            $strukPelayanan = StrukPelayanan::where('norec', $strukPelayananPenjamin->nostrukfk)
                ->where('kdprofile', $kdProfile)
                ->first();

            $strukBuktiPenerimanan = new StrukBuktiPenerimaan();
            $strukBuktiPenerimanan->norec = $strukBuktiPenerimanan->generateNewId();
            $strukBuktiPenerimanan->kdprofile = $kdProfile;
            $strukBuktiPenerimanan->keteranganlainnya = "Pembayaran Cicilan Tagihan Pasien";
            $strukBuktiPenerimanan->statusenabled = true;
            $strukBuktiPenerimanan->nostrukfk = $strukPelayanan->norec;
            $strukBuktiPenerimanan->noregistrasi = $strukPelayanan->noregistrasi;
            $strukBuktiPenerimanan->objectkelompokpasienfk = $strukPelayanan->objectkelompokpasienfk;
            $strukBuktiPenerimanan->objectkelompoktransaksifk = $this->kelompokTransaksi('PEMBAYARAN TAGIHAN PASIEN');
            $strukBuktiPenerimanan->objectpegawaipenerimafk  =  $this->getPegawaiId();
            $strukBuktiPenerimanan->tglsbm  = $this->getDateTime();
            $strukBuktiPenerimanan->totaldibayar = (float) $request['jumlahBayar'];
            $strukBuktiPenerimanan->nosbm = $this->SEQUENCE(new StrukBuktiPenerimaan, 'nosbm', 14, 'RV-' . date('ym'), $kdProfile);
            if(isset($r['ruanganfk'])){
                $strukBuktiPenerimanan->ruanganfk =  $request['ruanganfk'];
            }
            $strukBuktiPenerimanan->ispiutang = true;
            $strukBuktiPenerimanan->save() ;

            foreach ($request['pembayaran'] as $pembayaran) {

                $strukPelayananPenjamin->totalsudahdibayar = $strukPelayananPenjamin->totalsudahdibayar + $pembayaran['nominal'];
                $strukPelayananPenjamin->save();

                $SBPCB = new StrukBuktiPenerimaanCaraBayar();
                $SBPCB->norec = $SBPCB->generateNewId();
                $SBPCB->kdprofile = $kdProfile;
                $SBPCB->statusenabled = 1;
                $SBPCB->nosbmfk = $strukBuktiPenerimanan->norec;
                $SBPCB->objectcarabayarfk = $pembayaran['caraBayar']['id'];
                $SBPCB->totaldibayar = (float) $pembayaran['nominal'];

                $SBPCB->save();
            }

            $this->LOGGING(
                'Pembayaran Tagihan Cicilan',
                $strukBuktiPenerimanan->norec,
                'strukbuktipenerimaan_t',
                'Pembayaran Tagihan Cicilan pada Pasien ' .
                $request['namapasien'] .
                    ' (' . $request['nocm'] . ') - ' .
                $strukBuktiPenerimanan->nosbm
            );
            DB::commit();
            $transMessage ="Sukses" ;
            $result = array(
                "status" => 200,
                "result" => array(
                    "sbm"  => $strukBuktiPenerimanan,
                    "as" => '@epic',
                ),
            );

        } catch (Exception $e) {
            DB::rollBack();
            $transMessage ="Simpan Gagal" ;//// $e->getMessage() : ;
            $result = array(
                "status" => 400,
                "result"  => null
            );

        }
        return $this->respond($result['result'], $result['status'], $transMessage);
    }

    public function pembayaran(Request $request)
    {
        switch ($request['tipePembayaran']) {
            case 'depositPasien':
                return $this->getDetailPembayaranDeposit($request);
                break;
            case 'tagihanPasien':
                return $this->getDetailTagihanPasien($request);
                break;
            case 'cicilanPasien':
                return $this->getDetailCicilanPasien($request);
                break;
            case 'pembayaranNonLayanan':
                return $this->getDetailPembayaranNonLayanan($request);
            case 'depositSewaAlat':
                return $this->getDetailPembayaranDepositSewaAlat($request);
                break;
            default:
                return 0;
        }
    }


    protected function getDetailPembayaranDeposit(Request $request)
    {
        $result = array(
            'jumlahBayar' => $request['jumlahBayar'],
            'tipePembayaran' => $request['tipePembayaran'],
            'noRegistrasi' => $request['noRegistrasi'],
        );
        return $this->respond($result, "Data Pembayaran");
    }

    protected function getDetailPembayaranDepositSewaAlat(Request $request)
    {
        $result = array(
            'jumlahBayar' => $request['jumlahBayar'],
            'tipePembayaran' => $request['tipePembayaran'],
            'noRegistrasi' => $request['noRegistrasi'],
        );
        return $this->respond($result, "Data Pembayaran");
    }

    protected function getDetailTagihanPasien(Request $request)
    {
        $kdProfile = (int)$this->getDataKdProfile($request);
        $strukPelayanan = StrukPelayanan::where('norec', $request['noRecStrukPelayanan'])->where('kdprofile', $kdProfile)->first();
        $totalBilling = $strukPelayanan->totalharusdibayar;
        $totalBayar = $totalBilling;
        $result = array(
            "noRecStrukPelayanan"  => $strukPelayanan->norec,
            "jumlahBayar"  => $totalBayar,
        );
        return $this->respond($result, "Data Pembayaran");
    }

    protected  function getDetailCicilanPasien(Request $request)
    {
        $result = array(
            "noRecStrukPelayanan"  => $request['noRecStrukPelayanan'],
            "jumlahBayar"  => $request['jumlahBayar'],
        );
        return $this->respond($result, "Data Pembayaran");
    }

    protected  function getDetailPembayaranNonLayanan(Request $request)
    {
        return $this->respond("Data Pembayaran");
    }

    public function caraBayar()
    {
        $data = CaraBayar::mine()->get();
        return $this->respond($data);
    }

    protected function simpanCicilanPasienCollect($request)
    {
        $kdProfile = $this->kdProfile;
        DB::beginTransaction();
        $strukPelayananPenjamin = StrukPelayananPenjamin::where('norec', $request['parameter']['noRecStrukPelayanan'])
            ->where('kdprofile', $kdProfile)
            ->first();
        $strukPelayanan = StrukPelayanan::where('norec', $strukPelayananPenjamin->nostrukfk)
            ->where('kdprofile', $kdProfile)
            ->first();
        $totaldiskon = 0;
        if (isset($request['jumlahdiskon']) && $request['jumlahdiskon'] != "" || (float)$request['jumlahdiskon'] != 0) {
            $totaldiskon = (float)$request['jumlahdiskon'];
        }
        try {
            $NOSBM = array();
            foreach ($request['pembayaran'] as $pembayaran) {
                $strukBuktiPenerimanan = new StrukBuktiPenerimaan();
                $strukBuktiPenerimanan->norec = $strukBuktiPenerimanan->generateNewId();
                $strukBuktiPenerimanan->kdprofile = $kdProfile;
                $strukBuktiPenerimanan->keteranganlainnya = "Pembayaran Cicilan Tagihan Pasien Collecting";
                $strukBuktiPenerimanan->statusenabled = 1;
                $strukBuktiPenerimanan->nostrukfk = $strukPelayanan->norec;
                $strukBuktiPenerimanan->objectkelompokpasienfk = $strukPelayanan->pasien_daftar->pasien->objectkelompokpasienfk;
                $strukBuktiPenerimanan->objectkelompoktransaksifk = 76;
                $strukBuktiPenerimanan->objectpegawaipenerimafk  = $this->getPegawaiId();
                $strukBuktiPenerimanan->tglsbm  = $this->getDateTime();
                $strukBuktiPenerimanan->totaldibayar  = $pembayaran['nominal'];
                $strukBuktiPenerimanan->nosbm = $this->generateCode(new StrukBuktiPenerimaan, 'nosbm', 14, 'RV-' . $this->getDateTime()->format('ym'), $kdProfile);
                $NOSBM[] = $strukBuktiPenerimanan->nosbm;
                $nostrukfkSTR = $strukPelayanan->norec;
                $noSBM = $strukBuktiPenerimanan->nosbm;
                $strukBuktiPenerimanan->totaldiskon = $totaldiskon;
                $strukBuktiPenerimanan->save();
                $strukPelayananPenjamin->totalsudahdibayar = $pembayaran['nominal'];

                if ($this->transStatus) {
                    $SBPCB = new StrukBuktiPenerimaanCaraBayar();
                    $SBPCB->norec = $SBPCB->generateNewId();
                    $SBPCB->kdprofile = $kdProfile;
                    $SBPCB->statusenabled = 1;
                    $SBPCB->nosbmfk = $strukBuktiPenerimanan->norec;
                    $SBPCB->objectcarabayarfk = $pembayaran['caraBayar']['id'];
                    $SBPCB->totaldibayar  = $pembayaran['nominal'];

                    if (isset($pembayaran['detailBank'])) {
                        $SBPCB->objectjeniskartufk = $pembayaran['detailBank']['jenisKartu']['id'];
                        $SBPCB->nokartuaccount = $pembayaran['detailBank']['noKartu'];
                        $SBPCB->namabankprovider = $pembayaran['detailBank']['namaKartu'];
                        $SBPCB->namapemilik = $pembayaran['detailBank']['namaKartu'];
                    }
                    $SBPCB->save();
                }
            }
            $this->LOGGING(
                'Pembayaran Cicilan Piutang',
                $nostrukfkSTR,
                'strukbuktipenerimaan_t',
                'Pembayaran Cicilan Piutang Dengan NoSBM' .
                    ' - ' .
                    $strukBuktiPenerimanan->nosbm
            );
            foreach ($request['detailSPP'] as $DetailSPP) {
                $SPP = StrukPelayananPenjamin::where('norec', $DetailSPP['noRecSPP'])->where('kdprofile', $kdProfile)->first();
                $SP = StrukPelayanan::where('norec', $SPP->nostrukfk)->where('kdprofile', $kdProfile)->first();
                $PD = PasienDaftar::where('norec', $SP->noregistrasifk)->where('kdprofile', $kdProfile)->first();
                $SPP->totalsudahdibayar =  $DetailSPP['bayarKlaim'];
                $SPP->totaldiskon = $totaldiskon;
                // totaldiskon
                $SPP->save();
                $SP->nosbmlastfk  = $strukBuktiPenerimanan->norec;
                $SP->save();
                $pd = $SP->pasien_daftar;
                $pd->nosbmlastfk = $strukBuktiPenerimanan->norec;
                $pd->save();
            }
            $transStatus = 'true';
        } catch (\Exception $e) {
            $transStatus = 'false' . $e->getMessage();
        }

        $transMessage = "Pembayaran Piutang";
        if ($transStatus == 'true') {
            $transMessage = $transMessage . " Berhasil";
            DB::commit();
            $result = array(
                "result" =>  [],
                "status" => 201,
                "as" => 'ea@epic',
            );
        } else {
            $transMessage = $transMessage . $e->getMessage() . $e->getLine() . " Gagal!!";
            DB::rollBack();
            $result = array(
                "result" => [],
                "status" => 400,
                "as" => 'ea@epic',
            );
        }
        return $this->respond($result['result'], $result['status'], $transMessage);
    }
}
