<?php

namespace App\Http\Controllers\Bridging;

use App\Datatrans\PelayananPasien;
use App\Http\Controllers\Controller;
use App\Models\Transaksi\OrderBridgeDLIS;
use App\Models\Transaksi\OrderBridgeLIS;
use App\Models\Transaksi\OrderPelayanan;
use App\Models\Transaksi\RisOrder;
use App\Models\Transaksi\StrukOrder;
use App\Traits\Valet;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request as Psr7Request;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use App\Models\Master\HargaNettoProdukByKelas;
use App\Models\Master\Profile;
use App\Models\Transaksi\OrderLab;
use App\Models\Transaksi\PasienDaftar;
use App\Models\Transaksi\PelayananPasien as TransaksiPelayananPasien;
use App\Models\Transaksi\StrukBuktiPenerimaan;
use App\Models\Transaksi\StrukBuktiPenerimaanCaraBayar;
use App\Models\Transaksi\StrukPelayanan;
use Exception;

class NoAuthCtrl extends Controller
{
    use Valet;
    public function __construct()
    {
        parent::__construct($is_encrypt = true);
    }

    public function saveSendBack(Request $request)
    {
        DB::beginTransaction();
        try {

            $data = RisOrder::where('accession_num', $request['Report']['order']['id'])
                ->update(
                    [
                        'order_complete' => 1,
                        'description' => $request['Report']['report']['description'],
                        'report_date' => $request['Report']['report']['reportDate'],
                        'charge_doc_id' => $request['Report']['report']['doctorID'],
                        'charge_doc_name' => $request['Report']['report']['doctorName'],
                        'link' => $request['Report']['report']['link']
                    ]
                );



            DB::commit();
            $result = array(
                "status" => 200,
                "message" => "Sukses",
                "data" => $data,

            );
        } catch (Exception $e) {
            DB::rollBack();
            $result = array(
                "status" => 400,
                "message" => $e->getMessage() . " " . $e->getLine(),
                "data" => null,
            );
        }
        return $this->respond($result['data'], $result['status'], $result['message']);
    }
    public function cekNoStruk($request)
    {

        $status = '';
        $noStruk = $request['nokuitansi'];
        $cekbayar = true;
        if (stripos($request['nokuitansi'], 'NL') !== false) {
            $status = 'Pembayaran Tagihan Non Layanan';
            $status2 = 'tagihanNonLayanan';
        } else if (stripos($request['nokuitansi'], 'OB') !== false) {
            $status = 'Pembayaran Tagihan Obat Bebas';
            $status2 = 'tagihanNonLayanan';
        } else if (stripos($request['nokuitansi'], 'S') !== false) {
            $status = 'Pembayaran Tagihan';
            $status2 = 'tagihanPasien';
        } else if ((isset($request['deposit']) && $request['deposit'] == true) && isset($request['jumlahdeposit'])) {
            $status = 'depositPasien';
            $status2 = 'depositPasien';
            $cekbayar = false;
        }
        if ($status == '') {
            $subst = str_replace('/', '', substr($request['nokuitansi'], 2));
            $c = strlen(trim($subst));
            // dd( $subst);
            $noStruk =  '';
            if (strlen($request['nokuitansi']) == 8) {
                $noStruk = 'NL' . $request['nokuitansi'];
                $status = 'Pembayaran Tagihan Non Layanan';
                $status2 = 'tagihanNonLayanan';
            } else if (strlen($request['nokuitansi']) == 9) {
                $awal = substr($request['nokuitansi'], 0, 4);
                $akhir = substr($request['nokuitansi'], 4);
                $noStruk = 'OB/' . $awal . '/' . $akhir;
                $status = 'Pembayaran Tagihan Obat Bebas';
                $status2 = 'tagihanNonLayanan';
            } else {
                $prefixLen = 1;
                $noStruk = 'S' . (str_pad($request['nokuitansi'], 10 - $prefixLen, "0", STR_PAD_LEFT));
                $status = 'Pembayaran Tagihan';
                $status2 = 'tagihanPasien';
            }
            $datas['nostruk'] = $noStruk;
            $datas['status'] = $status;
            $datas['cekbayar'] = $cekbayar;
            $datas['switch'] = $status2;
            return $datas;
        } else {
            $datas['nostruk'] = $noStruk;
            $datas['status'] = $status;
            $datas['cekbayar'] = $cekbayar;
            $datas['switch'] = $status2;
            return $datas;
        }
    }
    public function getTagihan(Request $request)
    {
        $profile = Profile::where('statusenabled', true)->first();
        $kdProfile = $profile->id;
        if (!isset($request['nokuitansi'])) {
            $result = array(
                "status" => 400,
                "message" => "Parameter harus di isi"
            );
            return $this->setStatusCode($result['status'])->respond($result);
        }

        $res = $this->cekNoStruk($request->input());


        $noStruk = $res['nostruk'];
        $status = $res['status'];
        $data = DB::table('strukpelayanan_t as sp')
            ->leftjoin('pasien_m as ps', 'ps.id', '=', 'sp.nocmfk')
            ->leftjoin('alamat_m as alm', 'alm.nocmfk', '=', 'ps.id')
            ->leftjoin('jeniskelamin_m as jk', 'jk.id', '=', 'ps.objectjeniskelaminfk')
            ->select(DB::raw("case when ps.nocm is null then sp.nostruk_intern  else ps.nocm end as nocm,
       case when ps.namapasien is null then upper(sp.namapasien_klien)   else ps.namapasien end as namapasien,
       case when ps.namapasien is null then upper(sp.namapasien_klien)   else ps.namapasien end as namapasien,
       case when ps.tgllahir is null then sp.tglfaktur  else ps.tgllahir end as tgllahir,
       case when alm.alamatlengkap is null then sp.namatempattujuan  else alm.alamatlengkap end as alamatlengkap,
       case when ps.nohp is null then sp.noteleponfaks  else ps.nohp end as nohp,
        sp.nostruk,sp.nosbmlastfk,sp.keteranganlainnya,
        jk.jeniskelamin,
        '' as keterangan,sp.totalharusdibayar"))
            ->where('sp.nostruk', $noStruk)
            ->where('sp.statusenabled', true)
            ->where('sp.kdprofile', $kdProfile)
            ->first();

        if (!empty($data)) {
            if ($data->nosbmlastfk != null) {
                $result = array(
                    "status" => 201,
                    "result"  => null
                );
                $msg = 'sudah lunas';
            } else {
                if ($data->totalharusdibayar == null || $data->totalharusdibayar == 0) {

                    $result = array(
                        "status" => 201,
                        "result"  => null
                    );
                    $msg = "Tidak ada tagihan yg harus di bayar atau Tagihan Nol";
                } else {
                    $msg = 'Ok';
                    // dd($this->pembulatan((float)$data->totalharusdibayar));
                    $result = array(
                        "status" => 200,
                        "result" => array(
                            'nomorrm' => $data->nocm != null ? $data->nocm : '-',
                            'nama' => $data->namapasien,
                            'alamat' => $data->alamatlengkap  != null ? $data->alamatlengkap : '-',
                            'gender' => $data->jeniskelamin != null ? $data->jeniskelamin : '-',
                            'tgl_lahir' => $data->tgllahir != null ? date('Y-m-d', strtotime($data->tgllahir)) : '',
                            'nohp' => $data->nohp,
                            'usia' => $this->getAge($data->tgllahir, date('Y-m-d')),
                            'nokuitansi' => $data->nostruk,
                            'keterangan' => $status,
                            'tagihan' => $this->pembulatan((float)$data->totalharusdibayar)['hasil'], // (float)$data->totalharusdibayar ,
                            'pembulatan' => $this->pembulatan((float)$data->totalharusdibayar)['pembulatan'],
                            'tagihanawal' => (float)$data->totalharusdibayar
                        )
                    );
                }
            }
        } else {
            $result = array(
                "status" => 201,
                "result" => null
            );
            $msg = "Tidak ada tagihan yg harus di bayar atau Tagihan Nol";
        }

        return $this->respond($result['result'], $result['status'], $msg);
    }
    function is_decimal($val)
    {
        return is_numeric($val) && floor($val) != $val;
    }
    function pembulatan($uang)
    {
        $res['hasil'] = (float) $uang;
        $res['pembulatan'] = (float)$uang;
        return $res;
        $fraction = 0;
        $isDecimal = false;
        if ($this->is_decimal($uang)) {
            $isDecimal = true;
            $n = $uang;
            $whole = floor($n);      // 1
            $fraction = $n - $whole; // .25
            $ratusan = substr((int)$uang, -2);
        } else {
            $ratusan = substr($uang, -2);
        }

        if ($isDecimal == true) {
            $ratusan = (float)$ratusan + $fraction;
        }

        // $ratusan = 100;
        // dd($ratusan);
        if ((float)$ratusan < 100 &&  (float)$ratusan != 0) {
            //      $akhir = $uang - $ratusan;
            // }else{
            $akhir = $uang + (100 - $ratusan);
        } else {
            $akhir = $uang; // + ($ratusan);
        }
        $res['hasil'] = (float) $akhir;
        $res['pembulatan'] = (float)$ratusan != 0 ? 100 - (float)$ratusan : 0;
        return $res;
    }

    public function updateBayar(Request $request)
    {
        if (!isset($request['nokuitansi']) || !isset($request['status'])) {
            $result = array(
                "status" => 201,
                "result" => null
            );
            $msg  = "Parameter harus di isi";
            return $this->respond($result['result'], $result['status'], $msg);
        }
        if ($request['status'] == 1) {
            $profile = Profile::where('statusenabled', true)->first();
            $kdProfile = $profile->id;

            $res = $this->cekNoStruk($request->input());
            $noStruk = $res['nostruk'];
            $status = $res['switch'];
            $cekbayar = $res['cekbayar'];
            if ($cekbayar == true) {
                $cekSudahBayar = StrukPelayanan::where('nostruk', $noStruk)
                    ->where('kdprofile', $kdProfile)
                    ->first();
                if ($cekSudahBayar->nosbmlastfk != null) {

                    $result = array(
                        "status" => 201,
                        "result" => null
                    );
                    $msg  = "Sudah bayar";
                    return $this->respond($result['result'], $result['status'], $msg);
                }
            }
            $request['nokuitansi'] = $noStruk;

            // $objetoRequest = new \Illuminate\Http\Request();
            switch ($status) {
                case 'depositPasien':
                    return $this->simpanPembayaranDeposit($request);
                    break;
                case 'PenyetoranDepositKasirKembali':
                    return $this->simpanPengembalianDeposit($request);
                    break;
                case 'tagihanPasien':
                    return $this->simpanPembayaranTagihanPasien($request);
                    break;
                case 'cicilanPasien':
                    return $this->simpanCicilanPasien($request);
                    break;
                case 'tagihanNonLayanan':
                    return $this->simpanPembayaranTagihanNonLayanan($request);
                    break;
                case 'cicilanPasienCollect':
                    return $this->simpanCicilanPasienCollect($request);
                    break;
                default:
                    $result = array(
                        "status" => 201,
                        "result" => null
                    );
                    $msg  = "Pending / Error System";
                    return $this->respond($result['result'], $result['status'], $msg);
            }
        } else {
            $result = array(
                "status" => 201,
                "result" => null
            );
            $msg  = "Pending / Error System";
            return $this->respond($result['result'], $result['status'], $msg);
        }
    }
    public function simpanPembayaranTagihanPasien(Request $r)
    {

        DB::beginTransaction();
        try {
            $profile = Profile::where('statusenabled', true)->first();
            $kdProfile = $profile->id;
            $strukPelayanan = StrukPelayanan::where('nostruk', $r['nokuitansi'])
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
            $SBM->objectkelompoktransaksifk = 45;//$this->kelompokTransaksi('PEMBAYARAN TAGIHAN PASIEN');
            $SBM->objectpegawaipenerimafk  = 1;
            $SBM->tglsbm  =   date('Y-m-d H:i:s');
            $SBM->totaldibayar = (float)$strukPelayanan->totalharusdibayar;
            $SBM->namapegawaipenerima  =   'API';
            $SBM->nosbm = $this->SEQUENCE(new StrukBuktiPenerimaan, 'nosbm', 14, 'RV-' . date('ym'), $kdProfile);


            $SBM->save();

            // foreach ($r['details'] as $pembayaran) {
                $SBPCB = new StrukBuktiPenerimaanCaraBayar();
                $SBPCB->norec = $SBPCB->generateNewId();
                $SBPCB->kdprofile = $kdProfile;
                $SBPCB->statusenabled = true;
                $SBPCB->nosbmfk = $SBM->norec;
                $SBPCB->objectcarabayarfk =$this->settingDataFixed('idCaraBayarBankNTT', $kdProfile);
                $SBPCB->totaldibayar = (float)$strukPelayanan->totalharusdibayar;
                $SBPCB->save();
            // }

            $strukPelayanan->nosbmlastfk = $SBM->norec;
            $strukPelayanan->save();
            $totalTagihan = $this->scopeTotalTagihan($strukPelayanan->noregistrasi,$kdProfile);

            $pd = $strukPelayanan->pasien_daftar;
            $pd->statusbayar = $sisa <=  $totalTagihan ? 'Lunas' : 'Bayar Sebagian';
            $pd->nosbmlastfk = $SBM->norec;
            $pd->save();

            $this->LOGGING(
                'Pembayaran Tagihan',
                $SBM->norec,
                'strukbuktipenerimaan_t',
                'Pembayaran Tagihan  BANK NTT pada  ' .

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
                    "as" => '@epic',
                ),
            );
        } else {
            $transMessage = $e->getCode() == 404 ? $e->getMessage() : "Simpan Gagal";
            DB::rollBack();
            $result = array(
                "status" => 201,
                "result"  =>  null
            );
        }
        return $this->respond($result['result'], $result['status'], $transMessage);
    }
    public function scopeTotalTagihan($noregistrasi = null,$kdProfile)
    {
        if (empty($noregistrasi)) {
            return 0;
        }
        $data = DB::table('pelayananpasien_t')->where("statusenabled", true)
                ->where('noregistrasi', $noregistrasi)
                ->where("kdprofile", $kdProfile)
                ->get();
        $total = 0;
        foreach ($data as $d) {
            $total = $total +
            ((((float)$d->hargasatuan - (float)$d->hargadiscount)
            *(float)$d->jumlah) + (float)$d->jasa );
        }
        return $total;
    }
    public function batalBayar(Request $r)
    {

        DB::beginTransaction();
        try {
            $profile = Profile::where('statusenabled', true)->first();
            $kdProfile = $profile->id;
            $sp =  StrukPelayanan::where('nostruk',$r['nokuitansi'])->first();
            if(empty($sp)){
                $transMessage = "Tagihan tidak ada";
                DB::rollBack();
                $result = array(
                    "status" => 201,
                    "result"  =>  null
                );

              return $this->respond($result['result'], $result['status'], $transMessage);
            }
            $sbm = StrukBuktiPenerimaan::where('nostrukfk', $sp->norec)->first();


            StrukPelayanan::where('norec', $sp->norec)
                ->where('kdprofile', $kdProfile)
                ->update(
                    [
                        'nosbmlastfk'    => null,
                    ]
                );
            StrukBuktiPenerimaan::where('norec', $sbm->norec)
                ->where('kdprofile', $kdProfile)
                ->update(
                    [
                        'statusenabled' => false,
                        'nostrukfk'    => null,
                    ]
                );
            PasienDaftar::where('nostruklastfk', $sp->norec)
                ->where('kdprofile', $kdProfile)
                ->update(
                    [
                        'nosbmlastfk'    => null,
                    ]
                );

            PasienDaftar::where('noregistrasi', $sbm->noregistrasi)->update(
                [
                    'statusbayar' => 'Belum Bayar'
                ]
            );

            $this->LOGGING(
                'Batal Bayar',
                $sbm->norec,
                'strukbuktipenerimaan_t',
                'Batal Bayar BANK NTT - ' .
                    $sbm->nosbm
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
                "status" => 201,
                "result"  => $e->getMessage() . ' ' . $e->getLine()
            );
        }
        return $this->respond($result['result'], $result['status'], $transMessage);
    }

}
