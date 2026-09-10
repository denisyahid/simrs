<?php

namespace App\Http\Controllers\Farmasi;

use App\Http\Controllers\Controller;
use App\Models\Master\Produk;
use App\Models\Transaksi\AntrianApotik;
use App\Models\Transaksi\KartuStok;
use App\Models\Transaksi\PelayananPasien;
use App\Models\Transaksi\PelayananPasienDetail;
use App\Models\Transaksi\PelayananPasienObatKronis;
use App\Models\Transaksi\PelayananPasienPetugas;
use App\Models\Transaksi\StokProdukDetail;
use App\Models\Transaksi\StrukOrder;
use App\Models\Transaksi\StrukResep;
use App\Traits\Valet;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class TransaksiPelayananFarmasiCtrl extends Controller
{
    use Valet;

    public function __construct()
    {
        parent::__construct($is_encrypt = true);
    }
    public function transaksiPelayananFarmasi(Request $r)
    {
        $data = DB::table('pasiendaftar_t as pd')
            ->JOIN('ruangan_m as ru', 'ru.id', '=', 'pd.objectruanganlastfk')
            ->JOIN('pasien_m as ps', 'ps.id', '=', 'pd.nocmfk')
            ->leftJoin('jeniskelamin_m as jk', 'jk.id', '=', 'ps.objectjeniskelaminfk')
            ->JOIN('kelompokpasien_m as kp', 'kp.id', '=', 'pd.objectkelompokpasienlastfk')
            ->leftJoin('rekanan_m as rek', 'rek.id', '=', 'pd.objectrekananfk')
            ->JOIN('kelas_m as kl', 'kl.id', '=', 'pd.objectkelasfk')
            ->select(
                'ru.namaruangan',
                'pd.noregistrasi',
                'ps.nocm',
                'pd.statusbayar',
                'ps.namapasien',
                'jk.jeniskelamin',
                'kp.id as kpid',
                'kp.kelompokpasien',
                'kl.namakelas',
                'kl.id as klid',
                'pd.tglregistrasi',
                'pd.tglpulang',
                'ps.tgllahir',
                'pd.nostruklastfk',
                'pd.isclosing',
                'pd.norec as norec_pd',
                'rek.namarekanan'
            )
            ->where('pd.kdprofile', $this->kdProfile)
            ->where('pd.statusenabled', true)
            ->where('pd.norec', $r['norec_pd'])
            ->first();
        if (!empty($data)) {
            $data->umur = $this->getAge($data->tgllahir, $data->tglregistrasi);
        }

        $detail = DB::table('pelayananpasien_t as pp')
            ->JOIN('antrianpasiendiperiksa_t as apd', 'apd.norec', '=', 'pp.noregistrasifk')
            ->JOIN('produk_m as pr', 'pr.id', '=', 'pp.produkfk')
            ->JOIN('ruangan_m as ru', 'ru.id', '=', 'apd.objectruanganfk')
            ->JOIN('jeniskemasan_m as jkm', 'jkm.id', '=', 'pp.jeniskemasanfk')
            ->leftJoin('jenisracikan_m as jra', 'jra.id', '=', 'pp.jenisracikanfk')
            ->leftJoin('satuanstandar_m as ss', 'ss.id', '=', 'pp.satuanviewfk')
            ->leftJoin('satuanstandar_m as ss2', 'ss2.id', '=', 'pr.objectsatuanstandarfk')
            ->JOIN('detailjenisproduk_m as djp', 'djp.id', '=', 'pr.objectdetailjenisprodukfk')
            ->JOIN('jenisproduk_m as jp', 'jp.id', '=', 'djp.objectjenisprodukfk')
            ->leftJOIN('strukpelayanan_t as sp', 'sp.norec', '=', 'pp.strukfk')
            ->leftJOIN('konversisatuan_t as ks', function ($join) {
                $join->on('ks.objekprodukfk', '=', 'pr.id')
                    ->on('ks.satuanstandar_tujuan', '=', 'pp.satuanviewfk');
            })
            ->JOIN('strukresep_t as sr', 'sr.norec', '=', 'pp.strukresepfk')
            ->JOIN('ruangan_m as ru2', 'ru2.id', '=', 'sr.ruanganfk')
            ->leftJOIN('pegawai_m as dok', 'dok.id', '=', 'sr.penulisresepfk')
            ->leftJOIN('rm_sediaan_m AS rs', 'rs.id', '=', 'pr.objectsediaanfk')
            ->leftJoin('satuanresep_m as sn', 'sn.id', '=', 'pp.satuanresepfk')
            ->select(
                'pp.tglpelayanan',
                'pp.produkfk',
                'pr.namaproduk',
                'ss.satuanstandar',
                'pp.jumlah',
                'pp.hargasatuan',
                'pp.hargadiscount',
                'sp.nostruk',
                'ks.nilaikonversi',
                'ss2.satuanstandar as satuanstandar2',
                'sr.noresep',
                'sr.norec as norec_resep',
                'pp.rke',
                'jkm.jeniskemasan',
                'pp.jenisracikanfk',
                'jra.jenisracikan',
                'pp.jasa',
                'ru2.id as ruangandepoid',
                'ru2.namaruangan as ruangandepo',
                'pp.aturanpakai',
                'ru.namaruangan',
                'dok.namalengkap as dokter',
                'pp.ispagi',
                'pp.issiang',
                'pp.ismalam',
                'pp.issore',
                'pp.iskronis',
                'pp.isdonasi',
                'sr.isreseppulang as reseppulang',
                'sn.satuanresep',
                'pp.satuanresepfk',
                'pp.tglkadaluarsa',
                'pp.dosis',
                'djp.detailjenisproduk',
                'jp.jenisproduk',
                'sr.cito',
                'apd.norec as norec_apd',
                'apd.noregistrasifk as norec_pd',
                DB::raw("CASE WHEN pr.kekuatan IS NOT NULL AND rs.name IS NOT NULL THEN pr.kekuatan || ' ' || rs.name ELSE '' END AS kekuatan")
            )
            ->where('pp.kdprofile', $this->kdProfile)
            ->whereNotNull('pp.strukresepfk')
            ->where('apd.noregistrasifk', $r['norec_pd'])
            ->orderBy('pp.tglpelayanan')
            ->orderBy('pp.rke')
            ->get();

        foreach ($detail as $no => $item) {
            $nKonversi = 1;
            $item->no = $no + 1;
            if ($item->nilaikonversi != null) {
                $nKonversi = $item->nilaikonversi;
            }
            if ($item->satuanstandar != null) {
                $ss = $item->satuanstandar;
            } else {
                $ss = $item->satuanstandar2;
            }
            $JenisKemasan = $item->jeniskemasan;
            if ($item->jenisracikan != null) {
                $JenisKemasan = $item->jeniskemasan . '/' . $item->jenisracikan;
            }
            $jasa = 0;
            if ($item->jasa != null && $item->jasa != '') {
                $jasa = $item->jasa;
            }
            $item->jumlah =  (float)$item->jumlah / (float)$nKonversi;
            $item->satuanstandar =  $ss;
            $item->noregistrasi = $data->noregistrasi;
            $item->jasa = $jasa;
            $item->jeniskemasan = $JenisKemasan;
            if($item->isdonasi === true) {
                $item->total = 800;
            } else {
                $item->total = (((float)$item->jumlah * (float)$item->hargasatuan) - (float)$item->hargadiscount) + (float)     $item->jasa;
            }
        }
        $res['header'] = $data;
        $res['detail'] = $detail;
        return $this->respond($res);
    }
    public function transaksiPelayananFarmasiModal(Request $r)
    {
        $data = DB::table('pasiendaftar_t as pd')
            ->JOIN('antrianpasiendiperiksa_t as apd', 'pd.norec', '=', 'apd.noregistrasifk')
            ->JOIN('ruangan_m as ru', 'ru.id', '=', 'apd.objectruanganfk')
            ->leftJOIN('pegawai_m as pg', 'pg.id', '=', 'apd.objectpegawaifk')
            ->JOIN('kelas_m as kl', 'kl.id', '=', 'apd.objectkelasfk')
            ->select(
                'ru.namaruangan',
                'apd.tglregistrasi',
                'pg.namalengkap as namadokter',
                'kl.namakelas',
                'apd.tglmasuk',
                'apd.tglkeluar',
                'apd.norec',
                'apd.noregistrasifk as norec_pd',
                'pg.id as pgid'
            )
            ->where('apd.kdprofile', $this->kdProfile)
            ->where('apd.noregistrasifk', $r['norec_pd'])
            ->where('apd.statusenabled', true)
            ->orderBy('apd.tglmasuk', 'desc')
            ->get();
        return $this->respond($data);
    }
    public function transaksiPelayananFarmasiHapus(Request $r_SR)
    {
        $idProfile = (int) $this->kdProfile;
        DB::beginTransaction();
        try {
            //region @TRANSACTION
            $dataPasien = DB::table('strukresep_t as sr')
                ->JOIN('antrianpasiendiperiksa_t as apd', 'apd.norec', '=', 'sr.pasienfk')
                ->JOIN('pasiendaftar_t as pd', 'pd.norec', '=', 'apd.noregistrasifk')
                ->JOIN('pasien_m as pm', 'pm.id', '=', 'pd.nocmfk')
                ->select('pm.namapasien', 'sr.noresep', 'pm.nocm','pd.noregistrasi')
                ->where('sr.kdprofile', $idProfile)
                ->where('sr.norec', $r_SR['norec'])
                ->first();

            KartuStok::where('keterangan',  'Pelayanan Obat Alkes No. ' . $dataPasien->noresep . ' ' . $dataPasien->nocm . ' ' . $dataPasien->namapasien)
                ->where('kdprofile', $idProfile)
                ->update([
                    'flagfk' => null
                ]);

            $newSR = StrukResep::where('norec', $r_SR['norec'])->where('kdprofile', $idProfile)->first();
            if (isset($request['norec_order'])) {
                $newSR->orderfk = null;
            }
            $newSR->statusenabled = false;
            $newSR->save();

            $norec_SR = $newSR->norec;
            $idRuangan_SR = $newSR->ruanganfk;

            $newPP = PelayananPasien::where('strukresepfk', $norec_SR)->where('kdprofile', $idProfile)->get();

            foreach ($newPP as $r_PPL) {
                $norec_PP = $r_PPL->norec;
                PelayananPasienDetail::where('pelayananpasien', $norec_PP)->where('kdprofile', $idProfile)->delete();
                PelayananPasienPetugas::where('pelayananpasien', $norec_PP)->where('kdprofile', $idProfile)->delete();

                $qtyJumlah = (float)$r_PPL['jumlah'];

                $dataSaldoAwal = collect(
                    DB::select("select sum(qtyproduk) as qty from stokprodukdetail_t
                    where kdprofile = $idProfile and objectruanganfk='$idRuangan_SR' and objectprodukfk=$r_PPL[produkfk]")
                )->first();

                $saldoAwal = (float)$dataSaldoAwal->qty;
                $saldoAkhir = (float)$dataSaldoAwal->qty + $qtyJumlah;

                $jmlPengurang = (float)$qtyJumlah;
                DB::table('stokprodukdetail_t')
                    ->where('kdprofile', $idProfile)
                    ->where('norec', $r_PPL['stokprodukdetailfk'])
                    ->sharedLock()
                    ->increment('qtyproduk', (float)$jmlPengurang);

                $this->kartu_STOK(array(
                    "saldoawal" => $saldoAwal,
                    "qtyin" => (float)$jmlPengurang,
                    "qtyout" => 0,
                    "saldoakhir" => $saldoAkhir,
                    "keterangan" => 'Hapus Pelayanan Obat Alkes No. ' . $dataPasien->noresep . ' ' . $dataPasien->nocm . ' ' . $dataPasien->namapasien,
                    "produkfk" => $r_PPL['produkfk'],
                    "ruanganfk" => $idRuangan_SR,
                    "tglinput" => date('Y-m-d H:i:s'),
                    "tglkejadian" => date('Y-m-d H:i:s'),
                    "nostrukterimafk" =>  $r_PPL['nostrukterimafk'],
                    "norectransaksi" =>  $norec_PP,
                    "tabletransaksi" => 'pelayananpasien_t',
                    "stokprodukdetailfk" => $r_PPL['stokprodukdetailfk'],
                    "flagfk" => null,
                ));
            }

            if (isset($request['norec_order'])) {
                StrukOrder::where('norec', $request['norec_order'])
                    ->where('kdprofile', $idProfile)
                    ->update([
                        'statusorder' => null,
                        'namapengambilorder' => null,
                        'tglambilorder' => null
                    ]);
                AntrianApotik::where('noresep', $newSR->noresep)->where('kdprofile', $idProfile)->delete();
            }

            $newPP = PelayananPasien::where('strukresepfk', $norec_SR)->where('kdprofile', $idProfile)->delete();
            $this->LOGGING(
                'Hapus Pelayanan Obat Alkes dengan',
                $norec_SR,
                'pelayananpasien_t',
                'Hapus Pelayanan Obat Alkes dengan No. '  . $newSR->noresep . '. Atas pasien ' . $dataPasien->namapasien . '. NoCM ' . $dataPasien->nocm . '. No Registrasi  ' .  $dataPasien->noregistrasi
            );
            //endregion
            $transStatus = 'true';
        } catch (\Exception $e) {
            $transStatus = 'false';
        }

        if ($transStatus == 'true') {
            $transMessage = "Hapus Pelayanan Apotik Berhasil";
            DB::commit();
            $result = array(
                "status" => 200,
                "result" => array(
                    "resep"  => $newSR,
                    "as" => 'as@epic',
                ),
            );
        } else {
            $transMessage = "Hapus Pelayanan Apotik Gagal!!";
            DB::rollBack();
            $result = array(
                "status" => 400,
                "result"  => null,
            );
        }
        return $this->respond($result['result'], $result['status'], $transMessage);
    }
    public function transaksiPelayananFarmasiKronis(Request $request) {
        $kdProfile = (int) $this->kdProfile;
        $detail=$request->all();
        $result=[];
        $data = DB::table('pelayananpasienobatkronis_t as pp')
            ->JOIN('antrianpasiendiperiksa_t as apd','apd.norec','=','pp.noregistrasifk')
            ->JOIN('pasiendaftar_t as pd','pd.norec','=','apd.noregistrasifk')
            ->JOIN('pasien_m as ps','ps.id','=','pd.nocmfk')
            ->leftJOIN('jeniskelamin_m as jk','jk.id','=','ps.objectjeniskelaminfk')
            ->leftJOIN('produk_m as pr','pr.id','=','pp.produkfk')
            ->leftJOIN('ruangan_m as ru','ru.id','=','apd.objectruanganfk')
            ->leftJOIN('jeniskemasan_m as jkm','jkm.id','=','pp.jeniskemasanfk')
            ->leftJoin('jenisracikan_m as jra','jra.id','=','pp.jenisobatfk')
            ->leftJoin('satuanstandar_m as ss','ss.id','=','pp.satuanviewfk')
            ->leftJoin('satuanstandar_m as ss2','ss2.id','=','pr.objectsatuanstandarfk')
            ->leftJOIN('detailjenisproduk_m as djp','djp.id','=','pr.objectdetailjenisprodukfk')
            ->leftJOIN('jenisproduk_m as jp','jp.id','=','djp.objectjenisprodukfk')
            ->leftJOIN('strukpelayanan_t as sp','sp.norec','=','pp.strukfk')
            ->leftJOIN('konversisatuan_t as ks', function($join){
                $join->on('ks.objekprodukfk', '=', 'pr.id')
                    ->on('ks.satuanstandar_tujuan', '=', 'pp.satuanviewfk');
            })
            ->JOIN('strukresep_t as sr','sr.norec','=','pp.strukresepfk')
            ->leftJOIN('ruangan_m as ru2','ru2.id','=','sr.ruanganfk')
            ->leftJOIN('pegawai_m as pg','pg.id','=','sr.penulisresepfk')
            ->select('ps.nocm','ps.namapasien','jk.jeniskelamin','pp.tglpelayanan','pp.produkfk','pr.namaproduk',
                'ss.satuanstandar','pp.jumlah','pp.hargasatuan','pp.hargadiscount','sp.nostruk','pd.noregistrasi',
                'ks.nilaikonversi','ss2.satuanstandar as satuanstandar2','sr.noresep','sr.norec as norec_resep','pp.rke',
                'jkm.jeniskemasan','jk.id as jkid','pp.jenisobatfk','jra.jenisracikan','pp.jasa','ru2.id as ruangandepoid','ru2.namaruangan as ruangandepo',
                'pd.norec as norec_pd','apd.norec as norec_apd','ru2.namaruangan','pg.namalengkap as dokter',
                DB::raw("sr.noresep || '/23' as noresepok"))
            ->where('pd.kdprofile', $kdProfile)
            ->orderBy('pp.tglpelayanan')
            ->orderBy('pp.rke');
        if(isset($request['nocm']) && $request['nocm']!="" && $request['nocm']!="undefined"){
            $data = $data->where('ps.nocm','=', $request['nocm']);
        }
        if(isset($request['noregistrasifk']) && $request['noregistrasifk']!="" && $request['noregistrasifk']!="undefined"){
            $data = $data->where('pp.noregistrasifk','=', $request['noregistrasifk']);
        }
        if(isset($request['noReg']) && $request['noReg']!="" && $request['noReg']!="undefined"){
            $data = $data->where('pd.noregistrasi','=', $request['noReg']);
        }
        if(isset($request['norec_pd']) && $request['norec_pd']!="" && $request['norec_pd']!="undefined"){
            $data = $data->where('pd.norec','=', $request['norec_pd']);
        }
        if(isset($request['norec_resep']) && $request['norec_resep']!="" && $request['norec_resep']!="undefined"){
            $data = $data->where('sr.norec','=', $request['norec_resep']);
        }
        $data = $data->get();

        foreach ($data as $item){
            if (isset($item->nilaikonversi)){
                $nKonversi = $item->nilaikonversi;
            }else{
                $nKonversi=1;
            }
            if (isset($item->satuanstandar)){
                $ss = $item->satuanstandar;
            }else{
                $ss = $item->satuanstandar2;
            }
            $JenisKemasan=$item->jeniskemasan;
            if(isset($item->jenisracikan)){
                $JenisKemasan=$item->jeniskemasan .'/'. $item->jenisracikan;
            }
            $jasa=0;
            if(isset($item->jasa) && $item->jasa!="" && $item->jasa!="undefined"){
                $jasa=$item->jasa;
            }
            $item->total = (((float)$item->jumlah * (float)$item->hargasatuan) - (float)$item->hargadiscount) + (float)     $item->jasa;
            $item->satuanstandar = $ss;
            $item->jeniskemasan = $JenisKemasan;
            $item->jasa = $jasa;
            $item->depoid = $item->ruangandepoid;
            $item->namaruangandepo = $item->ruangandepo;
            $item->noresepok = $item->noresepok;
            $item->jumlah =(float)$item->jumlah / (float)$nKonversi;

        }
        return $this->respond($data);
    }
    public function transaksiPelayananFarmasiHapusKronis(Request $r_SR)
    {
        $idProfile = (int) $this->kdProfile;
        DB::beginTransaction();
        try {
            //region @TRANSACTION
            $dataPasien = DB::table('strukresep_t as sr')
                ->JOIN('antrianpasiendiperiksa_t as apd', 'apd.norec', '=', 'sr.pasienfk')
                ->JOIN('pasiendaftar_t as pd', 'pd.norec', '=', 'apd.noregistrasifk')
                ->JOIN('pasien_m as pm', 'pm.id', '=', 'pd.nocmfk')
                ->select('pm.namapasien', 'sr.noresep', 'pm.nocm')
                ->where('sr.kdprofile', $idProfile)
                ->where('sr.norec', $r_SR['norec'])
                ->first();

            KartuStok::where('keterangan',  'Pelayanan Obat Alkes No. ' . $dataPasien->noresep . ' ' . $dataPasien->nocm . ' ' . $dataPasien->namapasien)
                ->where('kdprofile', $idProfile)
                ->update([
                    'flagfk' => null
                ]);

            $newSR = StrukResep::where('norec', $r_SR['norec'])->where('kdprofile', $idProfile)->first();
            if (isset($request['norec_order'])) {
                $newSR->orderfk = null;
            }
            $newSR->statusenabled = false;
            $newSR->save();

            $norec_SR = $newSR->norec;
            $idRuangan_SR = $newSR->ruanganfk;

            $newPP = PelayananPasienObatKronis::where('strukresepfk', $norec_SR)->where('kdprofile', $idProfile)->get();

            foreach ($newPP as $r_PPL) {
                $produk =  Produk::where('id',$r_PPL->produkfk)->first()->namaproduk;
                $norec_PP = $r_PPL->norec;
                // PelayananPasienDetail::where('pelayananpasien', $norec_PP)->where('kdprofile', $idProfile)->delete();
                // PelayananPasienPetugas::where('pelayananpasien', $norec_PP)->where('kdprofile', $idProfile)->delete();

                $qtyJumlah = (float)$r_PPL['jumlah'];

                $dataSaldoAwal = collect(
                    DB::select("select sum(qtyproduk) as qty from stokprodukdetail_t
                    where kdprofile = $idProfile and objectruanganfk='$idRuangan_SR' and objectprodukfk=$r_PPL[produkfk]")
                )->first();

                $saldoAwal = (float)$dataSaldoAwal->qty;
                $saldoAkhir = (float)$dataSaldoAwal->qty + $qtyJumlah;

                $jmlPengurang = (float)$qtyJumlah;
                DB::table('stokprodukdetail_t')
                    ->where('kdprofile', $idProfile)
                    ->where('norec', $r_PPL['stokprodukdetailfk'])
                    ->lockForUpdate()
                    ->increment('qtyproduk', (float)$jmlPengurang);

                $this->kartu_STOK(array(
                    "saldoawal" => $saldoAwal,
                    "qtyin" => (float)$jmlPengurang,
                    "qtyout" => 0,
                    "saldoakhir" => $saldoAkhir,
                    "keterangan" => 'Hapus Pelayanan Obat Kronis No. ' . $dataPasien->noresep . ' '
                    . '. Pada produk ' . $produk
                    . $dataPasien->nocm . ' ' . $dataPasien->namapasien,
                    "produkfk" => $r_PPL['produkfk'],
                    "ruanganfk" => $idRuangan_SR,
                    "tglinput" => date('Y-m-d H:i:s'),
                    "tglkejadian" => date('Y-m-d H:i:s'),
                    "nostrukterimafk" =>  $r_PPL['nostrukterimafk'],
                    "norectransaksi" =>  $norec_PP,
                    "tabletransaksi" => 'pelayananpasien_t',
                    "stokprodukdetailfk" => $r_PPL['stokprodukdetailfk'],
                    "flagfk" => null,
                ));
            }

            if (isset($request['norec_order'])) {
                StrukOrder::where('norec', $request['norec_order'])
                    ->where('kdprofile', $idProfile)
                    ->update([
                        'statusorder' => null,
                        'namapengambilorder' => null,
                        'tglambilorder' => null
                    ]);
                AntrianApotik::where('noresep', $newSR->noresep)->where('kdprofile', $idProfile)->delete();
            }

            $newPP = PelayananPasienObatKronis::where('strukresepfk', $norec_SR)->where('kdprofile', $idProfile)->delete();
            //endregion
            $transStatus = 'true';
        } catch (\Exception $e) {
            $transStatus = 'false';
        }

        if ($transStatus == 'true') {
            $transMessage = "Hapus Pelayanan Apotik Berhasil";
            DB::commit();
            $result = array(
                "status" => 200,
                "result" => array(
                    "resep"  => $newSR,
                    "as" => 'as@epic',
                ),
            );
        } else {
            $transMessage = "Hapus Pelayanan Apotik Gagal!!";
            DB::rollBack();
            $result = array(
                "status" => 400,
                "result"  => null,
            );
        }
        return $this->respond($result['result'], $result['status'], $transMessage);
    }
    public function saveStockSplit(Request $request){ {
            $idProfile = (int) $this->kdProfile;
            DB::beginTransaction();
            try {

                $dataPelayanan = PelayananPasien::where('strukresepfk', $request['norecresep'])->first();
                PelayananPasienObatKronis::where('strukresepfk', $request['norecresep'])->delete();

                // inset ke table pelayanan kronis
                if ($request['qtykronis'] > 0) {
                    $newPP = new PelayananPasienObatKronis();
                    $newPP->norec = $newPP->generateNewId();
                    $newPP->kdprofile = $idProfile;
                    $newPP->statusenabled = true;
                    $newPP->noregistrasifk = $dataPelayanan->noregistrasifk;
                    $newPP->aturanpakai = $dataPelayanan->aturanpakai;
                    $newPP->generik = $dataPelayanan->generik;
                    $newPP->hargadiscount = 0;
                    $newPP->hargajual = (float)$request['hargasatuan'] === null ? 0 : (float)$request['hargasatuan'];
                    $newPP->hargasatuan = (float)$request['hargasatuan'] === null ? 0 : (float)$request['hargasatuan'];
                    $newPP->jenisobatfk = $dataPelayanan->jenisobatfk;
                    $newPP->jumlah = $request['qtykronis'];
                    $newPP->kelasfk = $dataPelayanan->kelasfk;
                    $newPP->kdkelompoktransaksi = 1;
                    $newPP->produkfk = $dataPelayanan->produkfk;
                    $newPP->routefk = $dataPelayanan->routefk;
                    $newPP->stock = $dataPelayanan->stock;
                    $newPP->tglpelayanan = $dataPelayanan->tglpelayanan;
                    $newPP->harganetto = (float)$request['hargasatuan'] === null ? 0 : (float)$request['hargasatuan'];
                    $newPP->jeniskemasanfk = $dataPelayanan->jeniskemasanfk;
                    $newPP->rke = $dataPelayanan->rke;
                    $newPP->strukresepfk = $dataPelayanan->strukresepfk;
                    $newPP->satuanviewfk = $dataPelayanan->satuanviewfk;
                    $newPP->nilaikonversi = $dataPelayanan->nilaikonversi;
                    $newPP->strukterimafk = $dataPelayanan->strukterimafk;
                    $newPP->dosis = $dataPelayanan->dosis;
                    $newPP->jasa = $dataPelayanan->jasa;
                    $newPP->isobat = 1;
                    $newPP->noregistrasifk = $request['noregistrasifk'];
                    $newPP->save();
                }
                // $dataPelayanan->jumlah = $request['qtypelayanan'];
                // $dataPelayanan->qtyawalpelayanan = $request['qtyawal'];
                // $dataPelayanan->qtykronis = $request['qtykronis'];
                $dataPelayanan->save();

                $transStatus = true;
            } catch (Exception $e) {
                $transStatus = false;
            }

            if ($transStatus) {
                $transMessage = 'Simpan Stok Split Berhasil';
                DB::commit();
                $result = array(
                    'status' => 201,
                    'message' => $transMessage,
                    'as' => 'hs@epic',
                );
            } else {
                $transMessage = 'Simpan Stok Split Gagal';
                DB::rollBack();
                $result = array(
                    'status' => 400,
                    'message'  => $transMessage,
                    "error" => $e->getMessage() . ' ' . $e->getLine(),
                    'as' => 'hs@epic',
                );
            }
            return $this->respond($result, $result['status'], $transMessage);
        }
    }
}
