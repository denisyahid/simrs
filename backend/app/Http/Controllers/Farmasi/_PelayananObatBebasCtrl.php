<?php

namespace App\Http\Controllers\Farmasi;

use App\Http\Controllers\Controller;
use App\Models\Master\Ruangan;
use App\Models\Master\Signa;
use App\Models\Transaksi\KartuStok;
use App\Models\Transaksi\PelayananPasienRetur;
use App\Models\Transaksi\StokProdukDetail;
use App\Models\Transaksi\StrukPelayanan;
use App\Models\Transaksi\StrukPelayananDetail;
use App\Models\Transaksi\StrukRetur;
use Illuminate\Support\FacadesDB;

use App\Traits\Valet;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PelayananObatBebasCtrl extends Controller
{
    use Valet;

    public function __construct()
    {
        parent::__construct($is_encrypt = true);
    }

    public function getDaftarPenjualanBebas(Request $request)
    {
        $dateBetween = [$request->tglAwal, $request->tglAkhir];
        $data = DB::table('strukpelayanan_t as sp')
            ->LEFTJOIN('pegawai_m as pg', 'pg.id', '=', 'sp.objectpegawaipenanggungjawabfk')
            ->LEFTJOIN('ruangan_m as ru', 'ru.id', '=', 'sp.objectruanganfk')
            ->LEFTJOIN('strukbuktipenerimaan_t as sbm', 'sbm.norec', '=', 'sp.nosbmlastfk')
            ->select(
                'sp.tglstruk',
                'sp.nostruk',
                'sp.nostruk_intern',
                'sp.namapasien_klien',
                'pg.namalengkap',
                'ru.namaruangan',
                'sp.norec',
                'sp.noteleponfaks',
                'sp.namatempattujuan',
                'sbm.nosbm',
                'sp.totalharusdibayar',
                'sp.namakurirpengirim'
            )
            ->where('sp.kdprofile', $this->kdProfile)
            ->whereBetween(DB::raw("CAST(sp.tglstruk as DATE)"), $dateBetween);

        if (isset($request['ruanganfk']) && $request['ruanganfk'] != "" && $request['ruanganfk'] != "undefined") {
            $data = $data->where('ru.id', 'ilike', '%' . $request['ruanganfk']);
        }
        if (isset($request['namapasien']) && $request['namapasien'] != "" && $request['namapasien'] != "undefined") {
            $data = $data->where('sp.namapasien_klien', 'ilike', '%' . $request['namapasien'] . '%');
        }

        $data = $data->whereRaw("substring(sp.nostruk,1,2)='OB'");
        $data = $data->where('sp.statusenabled', true);
        $data = $data->wherein('ru.objectdepartemenfk', [14]);
        $data = $data->orderBy('sp.nostruk');
        $data = $data->get();
        $result = [];
        foreach ($data as $item) {
            $details = DB::select(
                DB::raw("
                    select spd.tglpelayanan, spd.resepke,jkm.jeniskemasan,pr.namaproduk,objectjeniskemasanfk,
                    ss.satuanstandar,sum(spd.qtyproduk) as qtyproduk,spd.hargasatuan,spd.hargadiscount,
                    spd.hargatambahan,((spd.hargasatuan-spd.hargadiscount)* sum(spd.qtyproduk))+spd.hargatambahan as total,spd.aturanpakai
                    from strukpelayanandetail_t as spd
                    left JOIN produk_m as pr on pr.id=spd.objectprodukfk
                    left JOIN jeniskemasan_m as jkm on jkm.id=spd.objectjeniskemasanfk
                    left JOIN satuanstandar_m as ss on ss.id=spd.objectsatuanstandarfk
                    where spd.kdprofile = $this->kdProfile and nostrukfk=:norec
                    group by spd.tglpelayanan, spd.resepke,jkm.jeniskemasan,pr.namaproduk,objectjeniskemasanfk,
                    ss.satuanstandar,spd.hargasatuan,spd.hargadiscount,
                    spd.hargatambahan,spd.aturanpakai
                    "),
                array(
                    'norec' => $item->norec,
                )
            );
            $namapasienasdsa = $item->namapasien_klien;
            if ($item->namakurirpengirim != '') {
                $namapasienasdsa = $item->namakurirpengirim . ' / ' . $item->namapasien_klien;
            }
            $result[] = array(
                'tglstruk' => $item->tglstruk,
                'nostruk' => $item->nostruk,
                'totalharusdibayar' => $item->totalharusdibayar,
                'nostruk_intern' => $item->nostruk_intern,
                'namapasien_klien' => $namapasienasdsa,
                'namalengkap' => $item->namalengkap,
                'norec' => $item->norec,
                'namaruangan' => $item->namaruangan,
                'noteleponfaks' => $item->noteleponfaks,
                'namatempattujuan' => $item->namatempattujuan,
                'nosbm' => $item->nosbm,
                'details' => $details,
            );
        }

        $result = array(
            'daftar' => $result,
            'message' => 'as@epic',
        );

        return $this->respond($result);
    }

    public function getPasien(Request $request)
    {
        $data = DB::table('pasien_m as ps')
            ->leftJOIN('alamat_m as al', 'al.nocmfk', '=', 'ps.id')
            ->leftJOIN('pekerjaan_m as pkr', 'pkr.id', '=', 'ps.objectpekerjaanfk')
            ->leftJOIN('jeniskelamin_m as jk', 'jk.id', '=', 'ps.objectjeniskelaminfk')
            ->select(
                'ps.nocm',
                'ps.namapasien',
                'ps.notelepon',
                'ps.tgllahir',
                'al.alamatlengkap',
                'pkr.id as pekerjaanid',
                'pkr.pekerjaan',
                'jk.id as jkid',
                'jk.jeniskelamin',
                'al.alamatemail'
            )
            ->where('ps.kdprofile', $this->kdProfile)
            ->where('ps.statusenabled', true)
            ->where('ps.nocm', $request->nocm)
            ->first();

        return $this->respond($data);
    }

    public function saveInputTagihanObat(Request $request)
    {

        DB::beginTransaction();
        try {

            $norm = $request['strukresep']['nocm'] ?  $request['strukresep']['nocm'] : '';
            $namaPasien = $norm . ' ' . $request['strukresep']['namapasien'];

            if ($request['strukresep']['nostruk'] == '') {
                $noResep = $this->SEQUENCE(new StrukPelayanan, 'nostruk', 13, 'OB/' . $this->getDateTime()->format('ym/'), $this->kdProfile);
                $SP = new StrukPelayanan();
                $norecSP = $SP->generateNewId();
                $noStruk = $noResep;
                $SP->norec = $norecSP;
                $SP->kdprofile = $this->kdProfile;
                $SP->statusenabled = true;
                $SP->nostruk = $noStruk;
                $message = 'Data berhasil disimpan';
            } else {
                $SP = StrukPelayanan::where('norec', $request['strukresep']['nostruk'])->where('kdprofile', $this->kdProfile)->first();
                $noStruk = $SP->nostruk;
                $norecSP = $SP->norec;
                KartuStok::where('keterangan',  'Pelayanan Obat Bebas ' . $noStruk . ' ' . $namaPasien)->where('kdprofile', $this->kdProfile)->update(['flagfk' => null]);

                if ($request['strukresep']['ruanganfk'] == $SP->objectruanganfk) {
                    //##PENAMBAHAN KEMBALI STOKPRODUKDETAIL
                    $dataKembaliStok = DB::table('strukpelayanandetail_t')
                        ->select('qtyproduk', 'hasilkonversi', 'objectruanganfk', 'objectprodukfk', 'harganetto','strukterimafk')
                        ->where('kdprofile', $this->kdProfile)
                        ->where('statusenabled',true)
                        ->where('nostrukfk', $norecSP)
                         ->get();

                    // $test = [];
                    foreach ($dataKembaliStok as $item5) {
                       
                        $dataSaldoAwal = DB::table('stokprodukdetail_t')
                            ->select(DB::raw("sum(qtyproduk) as qty"))
                            ->where('kdprofile', $this->kdProfile)
                            ->where('statusenabled', true)
                            ->where('objectruanganfk', $item5->objectruanganfk)
                            ->where('objectprodukfk', $item5->objectprodukfk)
                            ->first();
                       
                        $saldoAwal = (float)$dataSaldoAwal->qty;
                        $TambahStok = (float)$item5->qtyproduk * (float)$item5->hasilkonversi;

                        $newSPD = StokProdukDetail::where('objectruanganfk', $item5->objectruanganfk)
                            ->where('kdprofile', $this->kdProfile)
                            ->where('nostrukterimafk', $item5->strukterimafk)
                            ->where('objectprodukfk', $item5->objectprodukfk)
                            ->where('statusenabled', true)
                            ->first();

                        DB::table('stokprodukdetail_t')
                            ->where('kdprofile', $this->kdProfile)
                            ->where('norec', $newSPD->norec)
                            ->where('statusenabled', true)
                            ->sharedLock()
                            ->increment('qtyproduk', (float)$TambahStok);

                        $tglnow =  date('Y-m-d H:i:s');
                        $tglUbah = date('Y-m-d H:i:s', strtotime('-10 seconds', strtotime($tglnow)));
                        // return $tglUbah;
                        $this->kartu_STOK(array(
                            "saldoawal" => $saldoAwal,
                            "qtyin" => (float)$TambahStok,
                            "qtyout" => 0,
                            "saldoakhir" => $saldoAwal + $TambahStok,
                            "keterangan" => 'Ubah Resep Obat Bebas No. ' . $noStruk,
                            "produkfk" => $item5->objectprodukfk,
                            "ruanganfk" => $item5->objectruanganfk,
                            "tglinput" => $tglUbah,
                            "tglkejadian" => $tglUbah,
                            "nostrukterimafk" => $newSPD->nostrukterimafk,
                            "norectransaksi" => $newSPD->norec,
                            "tabletransaksi" => 'stokprodukdetail_t',
                            "stokprodukdetailfk" => $newSPD->norec,
                            "flagfk" => null,
                        ));
                    }

                    //END##PENAMBAHAN KEMBALI STOKPRODUKDETAIL

                } else {

                    //##PENAMBAHAN KEMBALI STOKPRODUKDETAIL
                    $dataKembaliStok = DB::table('strukpelayanandetail_t as sp')
                        ->leftJoin('produk_m as pr', 'pr.id', 'sp.objectprodukfk')
                        ->select('sp.qtyproduk', 'sp.hasilkonversi', 'sp.objectruanganfk', 'sp.objectprodukfk', 'sp.harganetto', 'pr.namaproduk', 'sp.strukterimafk')
                        ->where('sp.kdprofile', $this->kdProfile)
                        ->where('sp.nostrukfk', $norecSP)
                        ->get();

                    foreach ($dataKembaliStok as $item5) {
                        $dataSaldoAwal = DB::table('stokprodukdetail_t')
                            ->select(DB::raw("sum(qtyproduk) as qty"))
                            ->where('kdprofile', $this->kdProfile)
                            ->where('statusenabled', true)
                            ->where('objectruanganfk', $SP->objectruanganfk)
                            ->where('objectprodukfk', $item5->objectprodukfk)
                            ->first();

                        $saldoAwal = 0;
                        $saldoAwal = (float)$dataSaldoAwal->qty + (float)$item5->qtyproduk;
                        $TambahStok = (float)$item5->qtyproduk * (float)$item5->hasilkonversi;
                        $newSPD = StokProdukDetail::where('objectruanganfk', $SP->objectruanganfk)
                            ->where('kdprofile', $this->kdProfile)
                            ->where('nostrukterimafk', $item5->strukterimafk)
                            ->where('objectprodukfk', $item5->objectprodukfk)
                            ->first();

                        DB::table('stokprodukdetail_t')
                            ->where('kdprofile', $this->kdProfile)
                            ->where('norec', $newSPD->norec)
                            ->where('statusenabled', true)
                            ->sharedLock()
                            ->increment('qtyproduk', (float)$TambahStok);

                        $tglnow =  date('Y-m-d H:i:s');
                        $tglUbah = date('Y-m-d H:i:s', strtotime('-10 seconds', strtotime($tglnow)));

                        $this->kartu_STOK(array(
                            "saldoawal" => $saldoAwal,
                            "qtyin" => (float)$TambahStok,
                            "qtyout" => 0,
                            "saldoakhir" => $saldoAwal + $TambahStok,
                            "keterangan" => 'Ubah Resep Obat Bebas No. ' . $noStruk . 'Pada produk ' . $item5->namaproduk,
                            "produkfk" => $item5->objectprodukfk,
                            "ruanganfk" => $SP->objectruanganfk,
                            "tglinput" => $tglUbah,
                            "tglkejadian" => $tglUbah,
                            "nostrukterimafk" => $newSPD->nostrukterimafk,
                            "norectransaksi" => $newSPD->norec,
                            "tabletransaksi" => 'stokprodukdetail_t',
                            "stokprodukdetailfk" => $newSPD->norec,
                            "flagfk" => null,
                        ));
                    }
                    //END##PENAMBAHAN KEMBALI STOKPRODUKDETAIL
                }
                StrukPelayananDetail::where('nostrukfk', $request['strukresep']['nostruk'])->where('kdprofile', $this->kdProfile)->delete();
                $message = 'Data berhasil diupdate';
            }
            $SP->objectkelompoktransaksifk = $this->kelompokTransaksi('TAGIHAN OBAT BEBAS');
            $SP->keteranganlainnya = $request['strukresep']['keteranganlainnya'] ? $request['strukresep']['keteranganlainnya'] : '';
            $SP->namapasien_klien = $request['strukresep']['namapasien'];
            $SP->nostruk_intern = $request['strukresep']['nocm'] ?  $request['strukresep']['nocm'] : '';
            $SP->tglfaktur =  $request['strukresep']['tglLahir'] ? $request['strukresep']['tglLahir'] : '';
            $SP->namatempattujuan =  $request['strukresep']['alamat'] ?  $request['strukresep']['alamat'] : '';
            $SP->namarekanan = 'Umum/Tunai';
            $SP->objectpegawaipenanggungjawabfk = $request['strukresep']['penulisresepfk'];
            $SP->tglstruk = $request['strukresep']['tglresep'];
            $SP->totalharusdibayar = $request['strukresep']['total'];
            $SP->objectruanganfk = $request['strukresep']['ruanganfk'];
            $SP->namakurirpengirim = $request['strukresep']['karyawan'];
            $SP->save();

            $arr_produk = [];
            foreach ($request['details'] as $item) {

                //## StokProdukDetail
                $qtyJumlah = (float)$item['jumlah'];

                $saldoAwal = StokProdukDetail::where('objectprodukfk', $item['produkfk'])->where('objectruanganfk', $item['ruanganfk'])
                                ->where('statusenabled',true)->where('kdprofile',$this->kdProfile)->sum('qtyproduk');
               
                $infoSaldoRuangan = StokProdukDetail::where('objectprodukfk', $item['produkfk'])->where('objectruanganfk', $item['ruanganfk'])
                                    ->where('statusenabled', true)->where('kdprofile', $this->kdProfile)
                                    ->orderby('tglkadaluarsa')->get();
        
                // $dataSaldoAwal = collect(
                //         DB::select("
                //     select sum(qtyproduk) as qty from stokprodukdetail_t
                //     where kdprofile = $this->kdProfile and objectruanganfk='$ruang' and statusenabled = true
                //     and objectprodukfk=$item[produkfk]")
                //     )->first();

                if($saldoAwal < $qtyJumlah){
                    $transMessage = "Simpan Resep Gagal, Stok Produk " . $item['productname'] . ", ada " . (float)$saldoAwal . " Data Stok Kurang Dari Qty Resep !";
                    DB::rollBack();
                    $result = array(
                        "status" => 400,
                        "message"  => $transMessage,
                        "as" => 'as@epic',
                    );
                    return $this->setStatusCode($result['status'])->respond($result, $transMessage);
                }
               
                if($item['tglkadaluarsa']){
                    $SPD = new StrukPelayananDetail();
                    $norecKS = $SPD->generateNewId();
                    $SPD->norec = $norecKS;
                    $SPD->kdprofile = $this->kdProfile;
                    $SPD->statusenabled = true;
                    $SPD->nostrukfk = $SP->norec;
                    $SPD->ispagi = $item['ispagi'];
                    $SPD->issiang = $item['issiang'];
                    $SPD->issore = $item['issore'];
                    $SPD->ismalam = $item['ismalam'];
                    $SPD->objectasalprodukfk = $item['asalprodukfk'];
                    $SPD->objectjeniskemasanfk = $item['jeniskemasanfk'];
                    $SPD->objectprodukfk = $item['produkfk'];
                    $SPD->objectruanganfk = $item['ruanganfk'];
                    $SPD->objectruanganstokfk = $item['ruanganfk'];
                    $SPD->objectsatuanstandarfk = $item['satuanstandarfk'];
                    $SPD->aturanpakai = $item['aturanpakai'];
                    $SPD->hargadiscount = $item['hargadiscount'];
                    $SPD->hargadiscountgive = 0;
                    $SPD->hargadiscountsave = 0;
                    $SPD->harganetto = $item['hargasatuan'];
                    $SPD->hargapph = 0;
                    $SPD->hargappn = 0;
                    $SPD->hargasatuan = $item['hargasatuan'];
                    $SPD->hasilkonversi = $item['nilaikonversi'];
                    $SPD->namaproduk = $item['namaproduk'];
                    $SPD->resepke = $item['rke'];
                    $SPD->hargasatuandijamin = 0;
                    $SPD->hargasatuanppenjamin = 0;
                    $SPD->hargasatuanpprofile = 0;
                    $SPD->hargatambahan = $item['jasa'];
                    $SPD->isonsiteservice = 0;
                    $SPD->kdpenjaminpasien = 0;
                    $SPD->persendiscount =  $item['persendiscount'];
                    $SPD->qtyproduk = $item['jumlah'];
                    $SPD->qtyprodukoutext = 0;
                    $SPD->qtyprodukoutint = 0;
                    $SPD->qtyprodukretur = 0;
                    $SPD->satuan = '-';
                    $SPD->satuanstandar = $item['satuanviewfk'];
                    $SPD->satuanresepfk = $item['satuanresepfk'];
                    $SPD->tglpelayanan = $request['strukresep']['tglresep'];
                    $SPD->is_terbayar = 0;
                    $SPD->linetotal = 0;
                    $SPD->qtydetailresep = $item['jumlahobat'];
                    $SPD->dosis = $item['dosis'];
                    $SPD->strukterimafk = $item['nostrukterimafk'] ? $item['nostrukterimafk'] : '';
                    $SPD->tglkadaluarsa = $item['tglkadaluarsa'];
                    $SPD->stokprodukdetailfk = isset($item['norec_spd']) ? $item['norec_spd'] : '';
                    $SPD->save();                    
                    StokProdukDetail::where('nostrukterimafk', $item['nostrukterimafk'])
                        ->where('kdprofile', $this->kdProfile)
                        ->where('objectruanganfk', $item['ruanganfk'])
                        ->where('statusenabled', true)
                        ->where('norec', $item['norec_spd'])
                        ->where('objectprodukfk', $item['produkfk'])
                        ->where('qtyproduk', '>', 0)
                        ->decrement('qtyproduk',$qtyJumlah);
                }else{
                    
                    $jumlah = (float)$item['jumlah']; 

                    foreach ($infoSaldoRuangan as $items) {
                        if ((float)$items->qtyproduk <= $jumlah) {

                            $dataSPD = StokProdukDetail::where('kdprofile', $this->kdProfile)->where('norec', $items->norec)->first();

                            $SPD = new StrukPelayananDetail();
                            $norecKS = $SPD->generateNewId();
                            $SPD->norec = $norecKS;
                            $SPD->kdprofile = $this->kdProfile;
                            $SPD->statusenabled = true;
                            $SPD->nostrukfk = $SP->norec;
                            $SPD->ispagi = $item['ispagi'];
                            $SPD->issiang = $item['issiang'];
                            $SPD->issore = $item['issore'];
                            $SPD->ismalam = $item['ismalam'];
                            $SPD->objectasalprodukfk = $item['asalprodukfk'];
                            $SPD->objectjeniskemasanfk = $item['jeniskemasanfk'];
                            $SPD->objectprodukfk = $item['produkfk'];
                            $SPD->objectruanganfk = $item['ruanganfk'];
                            $SPD->objectruanganstokfk = $item['ruanganfk'];
                            $SPD->objectsatuanstandarfk = $item['satuanstandarfk'];
                            $SPD->aturanpakai = $item['aturanpakai'];
                            $SPD->hargadiscount = $item['hargadiscount'];
                            $SPD->hargadiscountgive = 0;
                            $SPD->hargadiscountsave = 0;
                            $SPD->harganetto = $item['hargasatuan'];
                            $SPD->hargapph = 0;
                            $SPD->hargappn = 0;
                            $SPD->hargasatuan = $item['hargasatuan'];
                            $SPD->hasilkonversi = $item['nilaikonversi'];
                            $SPD->namaproduk = $item['namaproduk'];
                            $SPD->resepke = $item['rke'];
                            $SPD->hargasatuandijamin = 0;
                            $SPD->hargasatuanppenjamin = 0;
                            $SPD->hargasatuanpprofile = 0;
                            $SPD->hargatambahan = $item['jasa'];
                            $SPD->isonsiteservice = 0;
                            $SPD->kdpenjaminpasien = 0;
                            $SPD->persendiscount =  $item['persendiscount'];
                            $SPD->qtyproduk = $items->qtyproduk;
                            $SPD->qtyprodukoutext = 0;
                            $SPD->qtyprodukoutint = 0;
                            $SPD->qtyprodukretur = 0;
                            $SPD->satuan = '-';
                            $SPD->satuanstandar = $item['satuanviewfk'];
                            $SPD->satuanresepfk = $item['satuanresepfk'];
                            $SPD->tglpelayanan = $request['strukresep']['tglresep'];
                            $SPD->is_terbayar = 0;
                            $SPD->linetotal = 0;
                            $SPD->qtydetailresep = $item['jumlahobat'];
                            $SPD->dosis = $item['dosis'];
                            $SPD->strukterimafk = $dataSPD->nostrukterimafk;
                            $SPD->tglkadaluarsa = $dataSPD->tglkadaluarsa;
                            $SPD->stokprodukdetailfk = $dataSPD->norec;
                            $SPD->save();                    

                            StokProdukDetail::where('kdprofile', $this->kdProfile)
                                ->where('norec', $items->norec)
                                ->where('statusenabled', true)
                                ->sharedLock()
                                ->decrement('qtyproduk', (float)$items->qtyproduk);

                            $jumlah = $jumlah - (float)$items->qtyproduk;

                        } else {
                            $dataSPD = StokProdukDetail::where('kdprofile', $this->kdProfile)->where('norec', $items->norec)->first();

                            $SPD = new StrukPelayananDetail();
                            $norecKS = $SPD->generateNewId();
                            $SPD->norec = $norecKS;
                            $SPD->kdprofile = $this->kdProfile;
                            $SPD->statusenabled = true;
                            $SPD->nostrukfk = $SP->norec;
                            $SPD->ispagi = $item['ispagi'];
                            $SPD->issiang = $item['issiang'];
                            $SPD->issore = $item['issore'];
                            $SPD->ismalam = $item['ismalam'];
                            $SPD->objectasalprodukfk = $item['asalprodukfk'];
                            $SPD->objectjeniskemasanfk = $item['jeniskemasanfk'];
                            $SPD->objectprodukfk = $item['produkfk'];
                            $SPD->objectruanganfk = $item['ruanganfk'];
                            $SPD->objectruanganstokfk = $item['ruanganfk'];
                            $SPD->objectsatuanstandarfk = $item['satuanstandarfk'];
                            $SPD->aturanpakai = $item['aturanpakai'];
                            $SPD->hargadiscount = $item['hargadiscount'];
                            $SPD->hargadiscountgive = 0;
                            $SPD->hargadiscountsave = 0;
                            $SPD->harganetto = $item['hargasatuan'];
                            $SPD->hargapph = 0;
                            $SPD->hargappn = 0;
                            $SPD->hargasatuan = $item['hargasatuan'];
                            $SPD->hasilkonversi = $item['nilaikonversi'];
                            $SPD->namaproduk = $item['namaproduk'];
                            $SPD->resepke = $item['rke'];
                            $SPD->hargasatuandijamin = 0;
                            $SPD->hargasatuanppenjamin = 0;
                            $SPD->hargasatuanpprofile = 0;
                            $SPD->hargatambahan = $item['jasa'];
                            $SPD->isonsiteservice = 0;
                            $SPD->kdpenjaminpasien = 0;
                            $SPD->persendiscount =  $item['persendiscount'];
                            $SPD->qtyproduk = $jumlah;
                            $SPD->qtyprodukoutext = 0;
                            $SPD->qtyprodukoutint = 0;
                            $SPD->qtyprodukretur = 0;
                            $SPD->satuan = '-';
                            $SPD->satuanstandar = $item['satuanviewfk'];
                            $SPD->satuanresepfk = $item['satuanresepfk'];
                            $SPD->tglpelayanan = $request['strukresep']['tglresep'];
                            $SPD->is_terbayar = 0;
                            $SPD->linetotal = 0;
                            $SPD->qtydetailresep = $item['jumlahobat'];
                            $SPD->dosis = $item['dosis'];
                            $SPD->strukterimafk = $dataSPD->nostrukterimafk;
                            $SPD->tglkadaluarsa = $dataSPD->tglkadaluarsa;
                            $SPD->stokprodukdetailfk = $dataSPD->norec;
                            $SPD->save();                    

                            StokProdukDetail::where('kdprofile', $this->kdProfile)
                                ->where('norec', $items->norec)
                                ->where('statusenabled', true)
                                ->sharedLock()
                                ->decrement('qtyproduk', $jumlah);

                            $jumlah = 0;
                        }
                    }

                }

                // $GetNorec = StokProdukDetail::where('kdprofile', $this->kdProfile)
                //             ->where('objectruanganfk', $item['ruanganfk'])
                //             ->where('objectasalprodukfk', $item['asalprodukfk'])
                //             ->where('statusenabled', true)
                //             ->where('objectprodukfk', $item['produkfk'])
                //             ->where('qtyproduk', '>', 0)
                //             ->orderby('tglkadaluarsa')
                //             ->get();
                // $ruang = $request['strukresep']['ruanganfk'];
    
                // $saldoAwal = (float)$dataSaldoAwal->qty - (float)$qtyJumlah;
                // $jmlPengurang = (float)$qtyJumlah;
                // $kurangStok = (float)0;

                // $namaProduk = $item['namaproduk'];
                // if ((float)$dataSaldoAwal->qty == 0 || (float)$qtyJumlah > (float)$dataSaldoAwal->qty) {
                //     $transMessage = "Simpan Resep Gagal, Stok Produk " . $namaProduk . ", ada " . (float)$dataSaldoAwal->qty . " Data Stok Kurang Dari Qty Resep !";
                //     DB::rollBack();
                //     $result = array(
                //         "status" => 400,
                //         "message"  => $transMessage,
                //         "as" => 'as@epic',
                //     );
                //     return $this->setStatusCode($result['status'])->respond($result, $transMessage);
                // }
                // foreach ($GetNorec as $item2) {
                //     $newSPD = StokProdukDetail::where('nostrukterimafk', $item['nostrukterimafk'])
                //         ->where('kdprofile', $this->kdProfile)
                //         ->where('objectruanganfk', $item['ruanganfk'])
                //         ->where('objectasalprodukfk', $item['asalprodukfk'])
                //         ->where('objectprodukfk', $item['produkfk'])
                //         ->where('norec', $item2->norec)
                //         ->where('statusenabled', true)
                //         ->where('qtyproduk', '>', 0)
                //         ->first();

                //     if ((float)$newSPD->qtyproduk <= (float)$jmlPengurang) {
                //         $kurangStok = (float)$newSPD->qtyproduk;
                //         $jmlPengurang = (float)$jmlPengurang - (float)$kurangStok;
                //     } else {
                //         $kurangStok = (float)$jmlPengurang;
                //         $jmlPengurang = (float)$jmlPengurang - (float)$kurangStok;
                //     }

                //     DB::table('stokprodukdetail_t')
                //         ->where('kdprofile', $this->kdProfile)
                //         ->where('norec', $newSPD->norec)
                //         ->where('statusenabled', true)
                //         ->sharedLock()
                //         ->decrement('qtyproduk', (float)$kurangStok);
                // }

                $this->kartu_STOK([
                    "saldoawal" => $saldoAwal,
                    "qtyin" => 0,
                    "qtyout" => $qtyJumlah,
                    "saldoakhir" => $saldoAwal - $qtyJumlah,
                    "keterangan" => 'Pelayanan Obat Bebas ' . $noStruk . '. Pada pasien ' . $request['strukresep']['namapasien'] . ' , dengan produk ' .  $item['namaproduk'],
                    "produkfk" => $item['produkfk'],
                    "ruanganfk" => $request['strukresep']['ruanganfk'],
                    "tglinput" => date('Y-m-d H:i:s'),
                    "tglkejadian" => date('Y-m-d H:i:s'),
                    "nostrukterimafk" => $item['nostrukterimafk'],
                    "norectransaksi" => $SP->norec,
                    "tabletransaksi" => 'strukpelayanan_t',
                    "stokprodukdetailfk" => $item['norec_spd'],
                    "flagfk" => null,
                ]);
            }

            $ruangan = Ruangan::where('id',  $SP->objectruanganfk)->first();
            $norm = $SP->nostruk_intern ? $SP->nostruk_intern : '-';
            $this->LOGGING(
                $SP->keteranganlainnya,
                $SP->norec,
                'strukpelayanan_t',
                "Penjualan Obat Bebas pada pasien $SP->namapasien_klien ($norm) - " .
                    "dari ruangan $ruangan->namaruangan - no struk $SP->nostruk"
            );

            DB::commit();
            $result = [
                'status' => 201,
                'message' => $message,
                'data' => [
                    'SP' => $SP,
                    'SPD' => $SPD,
                ]
            ];
        } catch (Exception $e) {
            DB::rollBack();
            $result = [
                'status' => 400,
                'message' => 'Something Went wrong',
                'data' => $e->getMessage(),
            ];
        }
        return $this->respond($result['data'], $result['status'], $result['message']);
    }

    // public function saveInputTagihanObat(Request $request)
    // {

    //     DB::beginTransaction();
    //     try {

    //         $norm = $request['strukresep']['nocm'] ?  $request['strukresep']['nocm'] : '';
    //         $namaPasien = $norm . ' ' . $request['strukresep']['namapasien'];

    //         if ($request['strukresep']['nostruk'] == '') {
    //             $noResep = $this->SEQUENCE(new StrukPelayanan, 'nostruk', 13, 'OB/' . $this->getDateTime()->format('ym/'), $this->kdProfile);
    //             $SP = new StrukPelayanan();
    //             $norecSP = $SP->generateNewId();
    //             $noStruk = $noResep;
    //             $SP->norec = $norecSP;
    //             $SP->kdprofile = $this->kdProfile;
    //             $SP->statusenabled = true;
    //             $SP->nostruk = $noStruk;
    //             $message = 'Data berhasil disimpan';
    //         } else {
    //             $SP = StrukPelayanan::where('norec', $request['strukresep']['nostruk'])->where('kdprofile', $this->kdProfile)->first();
    //             $noStruk = $SP->nostruk;
    //             $norecSP = $SP->norec;
    //             KartuStok::where('keterangan',  'Pelayanan Obat Bebas ' . $noStruk . ' ' . $namaPasien)->where('kdprofile', $this->kdProfile)->update(['flagfk' => null]);

    //             if ($request['strukresep']['ruanganfk'] == $SP->objectruanganfk) {
    //                 //##PENAMBAHAN KEMBALI STOKPRODUKDETAIL
    //                 $dataKembaliStok = DB::table('strukpelayanandetail_t')
    //                     ->select('qtyproduk', 'hasilkonversi', 'objectruanganfk', 'objectprodukfk', 'harganetto','strukterimafk')
    //                     ->where('kdprofile', $this->kdProfile)
    //                     ->where('statusenabled',true)
    //                     ->where('nostrukfk', $norecSP)
    //                      ->get();

    //                 // $test = [];
    //                 foreach ($dataKembaliStok as $item5) {
                       
    //                     $dataSaldoAwal = DB::table('stokprodukdetail_t')
    //                         ->select(DB::raw("sum(qtyproduk) as qty"))
    //                         ->where('kdprofile', $this->kdProfile)
    //                         ->where('statusenabled', true)
    //                         ->where('objectruanganfk', $item5->objectruanganfk)
    //                         ->where('objectprodukfk', $item5->objectprodukfk)
    //                         ->first();
                       
    //                     $saldoAwal = (float)$dataSaldoAwal->qty;
    //                     $TambahStok = (float)$item5->qtyproduk * (float)$item5->hasilkonversi;

    //                     $newSPD = StokProdukDetail::where('objectruanganfk', $item5->objectruanganfk)
    //                         ->where('kdprofile', $this->kdProfile)
    //                         ->where('nostrukterimafk', $item5->strukterimafk)
    //                         ->where('objectprodukfk', $item5->objectprodukfk)
    //                         ->where('statusenabled', true)
    //                         ->first();

    //                     DB::table('stokprodukdetail_t')
    //                         ->where('kdprofile', $this->kdProfile)
    //                         ->where('norec', $newSPD->norec)
    //                         ->where('statusenabled', true)
    //                         ->sharedLock()
    //                         ->increment('qtyproduk', (float)$TambahStok);

    //                     $tglnow =  date('Y-m-d H:i:s');
    //                     $tglUbah = date('Y-m-d H:i:s', strtotime('-10 seconds', strtotime($tglnow)));
    //                     // return $tglUbah;
    //                     $this->kartu_STOK(array(
    //                         "saldoawal" => $saldoAwal,
    //                         "qtyin" => (float)$TambahStok,
    //                         "qtyout" => 0,
    //                         "saldoakhir" => $saldoAwal + $TambahStok,
    //                         "keterangan" => 'Ubah Resep Obat Bebas No. ' . $noStruk,
    //                         "produkfk" => $item5->objectprodukfk,
    //                         "ruanganfk" => $item5->objectruanganfk,
    //                         "tglinput" => $tglUbah,
    //                         "tglkejadian" => $tglUbah,
    //                         "nostrukterimafk" => $newSPD->nostrukterimafk,
    //                         "norectransaksi" => $newSPD->norec,
    //                         "tabletransaksi" => 'stokprodukdetail_t',
    //                         "stokprodukdetailfk" => $newSPD->norec,
    //                         "flagfk" => null,
    //                     ));
    //                 }

    //                 //END##PENAMBAHAN KEMBALI STOKPRODUKDETAIL

    //             } else {

    //                 //##PENAMBAHAN KEMBALI STOKPRODUKDETAIL
    //                 $dataKembaliStok = DB::table('strukpelayanandetail_t as sp')
    //                     ->leftJoin('produk_m as pr', 'pr.id', 'sp.objectprodukfk')
    //                     ->select('sp.qtyproduk', 'sp.hasilkonversi', 'sp.objectruanganfk', 'sp.objectprodukfk', 'sp.harganetto', 'pr.namaproduk', 'sp.strukterimafk')
    //                     ->where('sp.kdprofile', $this->kdProfile)
    //                     ->where('sp.nostrukfk', $norecSP)
    //                     ->get();

    //                 foreach ($dataKembaliStok as $item5) {
    //                     $dataSaldoAwal = DB::table('stokprodukdetail_t')
    //                         ->select(DB::raw("sum(qtyproduk) as qty"))
    //                         ->where('kdprofile', $this->kdProfile)
    //                         ->where('statusenabled', true)
    //                         ->where('objectruanganfk', $SP->objectruanganfk)
    //                         ->where('objectprodukfk', $item5->objectprodukfk)
    //                         ->first();

    //                     $saldoAwal = 0;
    //                     $saldoAwal = (float)$dataSaldoAwal->qty + (float)$item5->qtyproduk;
    //                     $TambahStok = (float)$item5->qtyproduk * (float)$item5->hasilkonversi;
    //                     $newSPD = StokProdukDetail::where('objectruanganfk', $SP->objectruanganfk)
    //                         ->where('kdprofile', $this->kdProfile)
    //                         ->where('nostrukterimafk', $item5->strukterimafk)
    //                         ->where('objectprodukfk', $item5->objectprodukfk)
    //                         ->first();

    //                     DB::table('stokprodukdetail_t')
    //                         ->where('kdprofile', $this->kdProfile)
    //                         ->where('norec', $newSPD->norec)
    //                         ->where('statusenabled', true)
    //                         ->sharedLock()
    //                         ->increment('qtyproduk', (float)$TambahStok);

    //                     $tglnow =  date('Y-m-d H:i:s');
    //                     $tglUbah = date('Y-m-d H:i:s', strtotime('-10 seconds', strtotime($tglnow)));

    //                     $this->kartu_STOK(array(
    //                         "saldoawal" => $saldoAwal,
    //                         "qtyin" => (float)$TambahStok,
    //                         "qtyout" => 0,
    //                         "saldoakhir" => $saldoAwal + $TambahStok,
    //                         "keterangan" => 'Ubah Resep Obat Bebas No. ' . $noStruk . 'Pada produk ' . $item5->namaproduk,
    //                         "produkfk" => $item5->objectprodukfk,
    //                         "ruanganfk" => $SP->objectruanganfk,
    //                         "tglinput" => $tglUbah,
    //                         "tglkejadian" => $tglUbah,
    //                         "nostrukterimafk" => $newSPD->nostrukterimafk,
    //                         "norectransaksi" => $newSPD->norec,
    //                         "tabletransaksi" => 'stokprodukdetail_t',
    //                         "stokprodukdetailfk" => $newSPD->norec,
    //                         "flagfk" => null,
    //                     ));
    //                 }
    //                 //END##PENAMBAHAN KEMBALI STOKPRODUKDETAIL
    //             }
    //             StrukPelayananDetail::where('nostrukfk', $request['strukresep']['nostruk'])->where('kdprofile', $this->kdProfile)->delete();
    //             $message = 'Data berhasil diupdate';
    //         }
    //         $SP->objectkelompoktransaksifk = $this->kelompokTransaksi('TAGIHAN OBAT BEBAS');
    //         $SP->keteranganlainnya = $request['strukresep']['keteranganlainnya'] ? $request['strukresep']['keteranganlainnya'] : '';
    //         $SP->namapasien_klien = $request['strukresep']['namapasien'];
    //         $SP->nostruk_intern = $request['strukresep']['nocm'] ?  $request['strukresep']['nocm'] : '';
    //         $SP->tglfaktur =  $request['strukresep']['tglLahir'] ? $request['strukresep']['tglLahir'] : '';
    //         $SP->namatempattujuan =  $request['strukresep']['alamat'] ?  $request['strukresep']['alamat'] : '';
    //         $SP->namarekanan = 'Umum/Tunai';
    //         $SP->objectpegawaipenanggungjawabfk = $request['strukresep']['penulisresepfk'];
    //         $SP->tglstruk = $request['strukresep']['tglresep'];
    //         $SP->totalharusdibayar = $request['strukresep']['total'];
    //         $SP->objectruanganfk = $request['strukresep']['ruanganfk'];
    //         $SP->namakurirpengirim = $request['strukresep']['karyawan'];
    //         $SP->save();

    //         $arr_produk = [];
    //         foreach ($request['details'] as $item) {
    //             $SPD = new StrukPelayananDetail();
    //             $qtyJumlah = (float)$item['jumlah'];
    //             $norecKS = $SPD->generateNewId();
    //             $SPD->norec = $norecKS;
    //             $SPD->kdprofile = $this->kdProfile;
    //             $SPD->statusenabled = true;
    //             $SPD->nostrukfk = $SP->norec;
    //             $SPD->ispagi = $item['ispagi'];
    //             $SPD->issiang = $item['issiang'];
    //             $SPD->issore = $item['issore'];
    //             $SPD->ismalam = $item['ismalam'];
    //             $SPD->objectasalprodukfk = $item['asalprodukfk'];
    //             $SPD->objectjeniskemasanfk = $item['jeniskemasanfk'];
    //             $arr_produk[] = $item['produkfk'];
    //             $SPD->objectprodukfk = $item['produkfk'];
    //             $SPD->objectruanganfk = $item['ruanganfk'];
    //             $SPD->objectruanganstokfk = $item['ruanganfk'];
    //             $SPD->objectsatuanstandarfk = $item['satuanstandarfk'];
    //             $SPD->aturanpakai = $item['aturanpakai'];
    //             $SPD->hargadiscount = $item['hargadiscount'];
    //             $SPD->hargadiscountgive = 0;
    //             $SPD->hargadiscountsave = 0;
    //             $SPD->harganetto = $item['hargasatuan'];
    //             $SPD->hargapph = 0;
    //             $SPD->hargappn = 0;
    //             $SPD->hargasatuan = $item['hargasatuan'];
    //             $SPD->hasilkonversi = $item['nilaikonversi'];
    //             $SPD->namaproduk = $item['namaproduk'];
    //             $SPD->resepke = $item['rke'];
    //             $SPD->hargasatuandijamin = 0;
    //             $SPD->hargasatuanppenjamin = 0;
    //             $SPD->hargasatuanpprofile = 0;
    //             $SPD->hargatambahan = $item['jasa'];
    //             $SPD->isonsiteservice = 0;
    //             $SPD->kdpenjaminpasien = 0;
    //             $SPD->persendiscount =  $item['persendiscount'];
    //             $SPD->qtyproduk = $item['jumlah'];
    //             $SPD->qtyprodukoutext = 0;
    //             $SPD->qtyprodukoutint = 0;
    //             $SPD->qtyprodukretur = 0;
    //             $SPD->satuan = '-';
    //             $SPD->satuanstandar = $item['satuanviewfk'];
    //             $SPD->satuanresepfk = $item['satuanresepfk'];
    //             $SPD->tglpelayanan = $request['strukresep']['tglresep'];
    //             $SPD->is_terbayar = 0;
    //             $SPD->linetotal = 0;
    //             $SPD->qtydetailresep = $item['jumlahobat'];
    //             $SPD->dosis = $item['dosis'];
    //             $SPD->strukterimafk = $item['nostrukterimafk'] ? $item['nostrukterimafk'] : '';
    //             $SPD->tglkadaluarsa = $item['tglkadaluarsa'];
    //             $SPD->save();
    //             //## StokProdukDetail
    //             $GetNorec = StokProdukDetail::where('nostrukterimafk', $item['nostrukterimafk'])
    //                 ->where('kdprofile', $this->kdProfile)
    //                 ->where('objectruanganfk', $item['ruanganfk'])
    //                 ->where('objectasalprodukfk', $item['asalprodukfk'])
    //                 ->where('statusenabled', true)
    //                 ->where('objectprodukfk', $item['produkfk'])
    //                 ->where('qtyproduk', '>', 0)
    //                 ->select('norec')
    //                 ->get();

    //             $ruang = $request['strukresep']['ruanganfk'];
    //             $dataSaldoAwal = collect(
    //                 DB::select("
    //                 select sum(qtyproduk) as qty from stokprodukdetail_t
    //                 where kdprofile = $this->kdProfile and objectruanganfk='$ruang' and statusenabled = true
    //                 and objectprodukfk=$item[produkfk]")
    //             )->first();

    //             $saldoAwal = 0;
    //             $saldoAwal = (float)$dataSaldoAwal->qty - (float)$qtyJumlah;
    //             $jmlPengurang = (float)$qtyJumlah;
    //             $kurangStok = (float)0;

    //             $namaProduk = $item['namaproduk'];
    //             if ((float)$dataSaldoAwal->qty == 0 || (float)$qtyJumlah > (float)$dataSaldoAwal->qty) {
    //                 $transMessage = "Simpan Resep Gagal, Stok Produk " . $namaProduk . ", ada " . (float)$dataSaldoAwal->qty . " Data Stok Kurang Dari Qty Resep !";
    //                 DB::rollBack();
    //                 $result = array(
    //                     "status" => 400,
    //                     "message"  => $transMessage,
    //                     "as" => 'as@epic',
    //                 );
    //                 return $this->setStatusCode($result['status'])->respond($result, $transMessage);
    //             }
    //             foreach ($GetNorec as $item2) {
    //                 $newSPD = StokProdukDetail::where('nostrukterimafk', $item['nostrukterimafk'])
    //                     ->where('kdprofile', $this->kdProfile)
    //                     ->where('objectruanganfk', $item['ruanganfk'])
    //                     ->where('objectasalprodukfk', $item['asalprodukfk'])
    //                     ->where('objectprodukfk', $item['produkfk'])
    //                     ->where('norec', $item2->norec)
    //                     ->where('statusenabled', true)
    //                     ->where('qtyproduk', '>', 0)
    //                     ->first();

    //                 if ((float)$newSPD->qtyproduk <= (float)$jmlPengurang) {
    //                     $kurangStok = (float)$newSPD->qtyproduk;
    //                     $jmlPengurang = (float)$jmlPengurang - (float)$kurangStok;
    //                 } else {
    //                     $kurangStok = (float)$jmlPengurang;
    //                     $jmlPengurang = (float)$jmlPengurang - (float)$kurangStok;
    //                 }

    //                 DB::table('stokprodukdetail_t')
    //                     ->where('kdprofile', $this->kdProfile)
    //                     ->where('norec', $newSPD->norec)
    //                     ->where('statusenabled', true)
    //                     ->sharedLock()
    //                     ->decrement('qtyproduk', (float)$kurangStok);
    //             }
    //             $this->kartu_STOK([
    //                 "saldoawal" => $dataSaldoAwal->qty,
    //                 "qtyin" => 0,
    //                 "qtyout" => $qtyJumlah,
    //                 "saldoakhir" => $dataSaldoAwal->qty - $qtyJumlah,
    //                 "keterangan" => 'Pelayanan Obat Bebas ' . $noStruk . '. Pada pasien ' . $namaPasien . ' , dengan produk ' .  $item['namaproduk'],
    //                 "produkfk" => $item['produkfk'],
    //                 "ruanganfk" => $request['strukresep']['ruanganfk'],
    //                 "tglinput" => date('Y-m-d H:i:s'),
    //                 "tglkejadian" => date('Y-m-d H:i:s'),
    //                 "nostrukterimafk" => $item['nostrukterimafk'],
    //                 "norectransaksi" => $SP->norec,
    //                 "tabletransaksi" => 'strukpelayanan_t',
    //                 "stokprodukdetailfk" => $item2['norec'],
    //                 "flagfk" => null,
    //             ]);
    //         }

    //         $ruangan = Ruangan::where('id',  $SP->objectruanganfk)->first();
    //         $norm = $SP->nostruk_intern ? $SP->nostruk_intern : '-';
    //         $this->LOGGING(
    //             $SP->keteranganlainnya,
    //             $SP->norec,
    //             'strukpelayanan_t',
    //             "Penjualan Obat Bebas pada pasien $SP->namapasien_klien ($norm) - " .
    //                 "dari ruangan $ruangan->namaruangan - no struk $SP->nostruk"
    //         );

    //         DB::commit();
    //         $result = [
    //             'status' => 201,
    //             'message' => $message,
    //             'data' => [
    //                 'SP' => $SP,
    //                 'SPD' => $SPD,
    //             ]
    //         ];
    //     } catch (Exception $e) {
    //         DB::rollBack();
    //         $result = [
    //             'status' => 400,
    //             'message' => 'Something Went wrong',
    //             'data' => $e->getMessage(),
    //         ];
    //     }
    //     return $this->respond($result['data'], $result['status'], $result['message']);
    // }

    public function getDetailResepBebas(Request $request)
    {
        $dataAsalProduk = DB::table('asalproduk_m as ap')
            ->select('ap.id', 'ap.asalproduk')
            ->where('ap.kdprofile', $this->kdProfile)
            ->get();
        $dataSigna = Signa::where('kdprofile', $this->kdProfile)->where('statusenabled', true)->get();
        $dataStruk = DB::table('strukpelayanan_t as sr')
            ->JOIN('pegawai_m as pg', 'pg.id', '=', 'sr.objectpegawaipenanggungjawabfk')
            ->JOIN('ruangan_m as ru', 'ru.id', '=', 'sr.objectruanganfk')
            ->select(
                'sr.nostruk',
                'pg.id as pgid',
                'pg.namalengkap',
                'ru.id as ruid',
                'ru.namaruangan',
                'sr.nostruk_intern as nocm',
                'sr.namapasien_klien as nama',
                'sr.tglfaktur as tgllahir',
                'sr.totalharusdibayar',
                'sr.noteleponfaks as notlp',
                'sr.namatempattujuan as alamat',
                'sr.tglstruk as tglresep',
            )
            ->where('sr.kdprofile', $this->kdProfile);

        if (isset($request['norecResep']) && $request['norecResep'] != "" && $request['norecResep'] != "undefined") {
            $dataStruk = $dataStruk->where('sr.norec', '=', $request['norecResep']);
        }
        $dataStruk = $dataStruk->first();

        $data = DB::table('strukpelayanan_t as sp')
            ->JOIN('strukpelayanandetail_t as spd', 'spd.nostrukfk', '=', 'sp.norec')
            ->JOIN('ruangan_m as ru', 'ru.id', '=', 'sp.objectruanganfk')
            ->JOIN('jeniskemasan_m as jk', 'jk.id', '=', 'spd.objectjeniskemasanfk')
            ->JOIN('produk_m as pr', 'pr.id', '=', 'spd.objectprodukfk')
            ->JOIN('satuanstandar_m as ss', 'ss.id', '=', 'spd.objectsatuanstandarfk')
            ->leftJoin('satuanresep_m as sn', 'sn.id', '=', 'spd.satuanresepfk')
            ->select(
                'spd.nostrukfk',
                'sp.nostruk',
                'spd.hargasatuan',
                'spd.qtyprodukoutext',
                'sp.objectruanganfk',
                'sp.totalharusdibayar',
                'ru.namaruangan',
                'spd.resepke',
                'jk.id as jkid',
                'jk.jeniskemasan',
                'spd.ispagi',
                'spd.issiang',
                'spd.issore',
                'spd.ismalam',
                'spd.strukterimafk',
                'spd.aturanpakai',
                'spd.objectprodukfk as produkfk',
                'pr.namaproduk',
                'spd.hasilkonversi as nilaikonversi',
                'spd.objectsatuanstandarfk',
                'ss.satuanstandar',
                'spd.satuanstandar as satuanviewfk',
                'ss.satuanstandar as ssview',
                'spd.qtyproduk as jumlah',
                'spd.hargadiscount',
                'spd.persendiscount',
                'spd.hargatambahan as jasa',
                'spd.hargasatuan as hargajual',
                'spd.harganetto',
                'spd.qtydetailresep',
                'pr.kekuatan',
                'spd.dosis',
                'spd.satuanresepfk',
                'sn.satuanresep',
                'spd.tglkadaluarsa',
            )
            ->where('sp.kdprofile', $this->kdProfile);

        if (isset($request['norecResep']) && $request['norecResep'] != "" && $request['norecResep'] != "undefined") {
            $data = $data->where('sp.norec', '=', $request['norecResep']);
        }
        $data = $data->get();
        $pelayananPasien = [];
        $i = 0;

        $dataStok = DB::table('stokprodukdetail_t as spd')
            ->join('strukpelayanan_t as sk', 'sk.norec', 'spd.nostrukterimafk')
            ->select(
                'sk.norec',
                'spd.objectprodukfk',
                'sk.tglstruk',
                'spd.objectasalprodukfk',
                'spd.harganetto2 as hargajual',
                'spd.harganetto2 as harganetto',
                'spd.hargadiscount',
                DB::raw("sum(spd.qtyproduk) as qtyproduk"),
                'spd.objectruanganfk'
            )
            ->where('spd.kdprofile', $this->kdProfile)
            ->where('spd.statusenabled', true)
            ->where('spd.objectruanganfk', $dataStruk->ruid)
            ->groupBy(
                'sk.norec',
                'spd.objectprodukfk',
                'sk.tglstruk',
                'spd.objectasalprodukfk',
                'spd.harganetto2',
                'spd.hargadiscount',
                'spd.persendiscount',
                'spd.qtyproduk',
                'spd.objectruanganfk'
            )
            ->orderBy('sk.tglstruk')
            ->get();
        $hargajual = 0;
        $harganetto = 0;
        $nostrukterimafk = '';
        $asalprodukfk = 0;
        $asalproduk = '';
        $jmlstok = 0;
        $hargasatuan = 0;
        $hargadiscount = 0;
        $persendiscount = 0;
        $total = 0;
        $aturanpakaifk = 0;
        foreach ($data as $item) {
            $i = $i + 1;
            foreach ($dataStok as $item2) {
                $hargajual = 0;
                $harganetto = 0;
                $hargadiscount = 0;
                if ($item2->objectprodukfk == $item->produkfk) {
                    if ($item2->qtyproduk != 0) {
                        $hargajual = $item->hargajual;
                        $harganetto = $item->harganetto;
                        $nostrukterimafk = $item->strukterimafk;
                        $asalprodukfk = $item2->objectasalprodukfk;
                        $jmlstok = $item2->qtyproduk;
                        $hargasatuan = $harganetto;
                        $hargadiscount = $item->hargadiscount;
                        $persendiscount = $item->persendiscount;
                        $total = (((float)$item->jumlah * ((float)$hargasatuan - (float)$hargadiscount)));
                        break;
                    }
                }
            }
            foreach ($dataAsalProduk as $item3) {
                if ($asalprodukfk == $item3->id) {
                    $asalproduk = $item3->asalproduk;
                }
            }
            foreach ($dataSigna as $item4) {
                if ($item->aturanpakai == $item4->id) {
                    $aturanpakaifk = $item4->id;
                }
            }
            $dosis = 1;
            if (!empty((float)$item->dosis)) {
                $dosis = (float)$item->dosis;
            }
            $jmlxMakan = (((float)$item->jumlah / (float)$item->nilaikonversi) / $dosis) * (float)$item->kekuatan;
            $pelayananPasien[] = array(
                'no' => $i,
                'noregistrasifk' => '',
                'tglregistrasi' => '',
                'generik' => null,
                'hargajual' => $hargajual,
                'jenisobatfk' => '',
                'kelasfk' => '',
                'stock' => $jmlstok,
                'harganetto' => $harganetto,
                'nostrukterimafk' => $nostrukterimafk,
                'ruanganfk' => $item->objectruanganfk,
                'rke' => $item->resepke,
                'jeniskemasanfk' => $item->jkid,
                'jeniskemasan' => $item->jeniskemasan,
                'aturanpakaifk' => $aturanpakaifk,
                'aturanpakai' => $item->aturanpakai,
                'ispagi' => $item->ispagi,
                'issiang' => $item->issiang,
                'issore' => $item->issore,
                'ismalam' => $item->ismalam,
                'routefk' => 0,
                'route' => '',
                'asalprodukfk' => $asalprodukfk,
                'asalproduk' => $asalproduk,
                'produkfk' => $item->produkfk,
                'namaproduk' => $item->namaproduk,
                'nilaikonversi' => $item->nilaikonversi,
                'satuanstandarfk' => $item->satuanviewfk,
                'satuanstandar' => $item->ssview,
                'satuanviewfk' => $item->satuanviewfk,
                'satuanview' => $item->ssview,
                'jmlstok' => $jmlstok,
                'jumlah' => $item->jumlah / $item->nilaikonversi,
                'dosis' => $item->dosis,
                'hargasatuan' => $hargasatuan,
                'hargadiscount' => $hargadiscount,
                'persendiscount' => $persendiscount,
                'total' => $total + $item->jasa,
                'jasa' => $item->jasa,
                'jmldosis' => (string)$jmlxMakan . '/' . (string)$item->dosis . '/' . $item->kekuatan,
                'jumlahobat' => $item->qtydetailresep,
                'satuanresepfk' => $item->satuanresepfk,
                'satuanresep' => $item->satuanresep,
                'tglkadaluarsa' => $item->tglkadaluarsa,
            );
        }

        $result = array(
            'detailresep' => $dataStruk,
            'pelayananPasien' => $pelayananPasien,
            'data' => $data,
            'datastok' => $dataStok,
            'message' => 'as@epic',
        );

        return $this->respond($result);
    }

    public function stokMerger(Request $request)
    {
        $idHibah = $this->settingFix('objectasalprodukHibah');

        DB::beginTransaction();
        try {
            $data = StokProdukDetail::where('objectprodukfk', $request['produkfk'])
                ->where('kdprofile', $this->kdProfile)
                ->where('objectruanganfk', $request['ruanganfk'])
                ->where('qtyproduk', '>', 0)
                ->orderby('tglkadaluarsa', 'desc')
                ->select('norec', 'qtyproduk', 'tglkadaluarsa');

            if (isset($r['isdonasi'])) {
                $data = $data->where('spd.objectasalprodukfk', $idHibah);
            } else {
                $data = $data->where('spd.objectasalprodukfk', '!=', $idHibah);
            }

            $data = $data->get();
            return $data;

            $qtyTotal = 0;
            $norec = '';

            foreach ($data as $item) {
                $qtyTotal = $qtyTotal + $item->qtyproduk;
                $data2 = StokProdukDetail::where('norec', $item->norec)->first();
                $data2->qtyproduk = 0;
                $data2->save();
            }
            foreach ($data as $item) {
                if ($item->tglkadaluarsa != null) {
                    $norec = $item->norec;
                    break;
                }
            }
            $data2 = StokProdukDetail::where('norec', $norec)->where('kdprofile', $this->kdProfile)->where('statusenabled', true)->first();
            $data2->qtyproduk = $qtyTotal;
            $data2->save();

            $data3 = StokProdukDetail::where('objectprodukfk', $request['produkfk'])
                ->where('kdprofile', $this->kdProfile)
                ->where('objectruanganfk', $request['ruanganfk'])
                ->where('statusenabled', true)
                ->where('qtyproduk', '>', 0)
                ->orderby('tglkadaluarsa', 'desc')
                ->select('norec', 'qtyproduk')
                ->get();

            DB::commit();
            $result = array(
                "status" => 200,
                "message" => 'Proses Marger Berhasil',
                "data" => $data3,
            );
        } catch (Exception $e) {
            DB::rollBack();
            $result = array(
                "status" => 400,
                "data" => $e->getMessage(),
                "message" => 'Proses Marger Gagal'
            );
        }

        return $this->respond($result, $result['status'], $result['message']);
    }

    public function deleteResepOB(Request $request)
    {
        DB::beginTransaction();
        $strukfk = $request['norec_sp'];
        $dataKembaliStok = DB::table('strukpelayanandetail_t as spd')
            ->join('strukpelayanan_t as sp', 'sp.norec', 'spd.nostrukfk')
            ->select(
                'spd.qtyproduk',
                'spd.hasilkonversi',
                'spd.objectruanganfk',
                'spd.objectprodukfk',
                'spd.harganetto',
                'spd.nostrukfk',
                'sp.nostruk',
                'spd.tglkadaluarsa'
            )
            ->where('spd.kdprofile', $this->kdProfile)
            ->where('spd.nostrukfk', $strukfk)
            ->get();
        try {
            
            foreach ($dataKembaliStok as $item5) {
                $TambahStok = (float)$item5->qtyproduk * (float)$item5->hasilkonversi;
                $dataSaldoAwal = DB::table('stokprodukdetail_t as spd')
                    ->select(DB::raw("sum(qtyproduk) as qty"))
                    ->where('kdprofile', $this->kdProfile)
                    ->where('statusenabled', true)
                    ->where('objectruanganfk', $item5->objectruanganfk)
                    ->where('objectprodukfk', $item5->objectprodukfk)
                    ->first();

                $saldoAwal = (float)$dataSaldoAwal->qty + (float)$item5->qtyproduk;
                $newSPD = StokProdukDetail::where('objectruanganfk', $item5->objectruanganfk)
                    ->where('kdprofile', $this->kdProfile)
                    ->where('objectprodukfk', $item5->objectprodukfk)
                    ->where('tglkadaluarsa', $item5->tglkadaluarsa)
                    ->where('statusenabled', true)
                    ->first();

                $dataPasien = DB::table('strukpelayanan_t as sr')
                    ->select(DB::raw("sr.namapasien_klien, sr.nostruk, sr.nostruk_intern"))
                    ->where('sr.kdprofile', $this->kdProfile)
                    ->where('sr.norec', $request['norec_sp'])
                    ->first();

                DB::table('stokprodukdetail_t')
                    ->where('kdprofile', $this->kdProfile)
                    ->where('norec', $newSPD->norec)
                    ->where('statusenabled', true)
                    ->sharedLock()
                    ->increment('qtyproduk', (float)$TambahStok);
                // $dataSaldoAwal->qty
        
                $this->kartu_STOK(array(
                    "saldoawal" => $dataSaldoAwal->qty,
                    "qtyin" => $item5->qtyproduk,
                    "qtyout" => 0,
                    "saldoakhir" => $dataSaldoAwal->qty + (float)$item5->qtyproduk,
                    "keterangan" => 'Hapus Resep Obat Bebas No. ' . $item5->nostruk,
                    "produkfk" => $item5->objectprodukfk,
                    "ruanganfk" => $item5->objectruanganfk,
                    "tglinput" => date('Y-m-d H:i:s'),
                    "tglkejadian" => date('Y-m-d H:i:s'),
                    "nostrukterimafk" => $newSPD['nostrukterimafk'],
                    "norectransaksi" => $strukfk,
                    "tabletransaksi" => 'strukpelayanan_t',
                    "stokprodukdetailfk" => $newSPD->norec,
                    "flagfk" => null,
                ));
            }
            StrukPelayanan::where('norec', $strukfk)->where('kdprofile', $this->kdProfile)->update(['statusenabled' => false]);
            DB::commit();
            $result = array(
                "status" => 200,
                "message" => 'Hapus Pelayanan Obat Bebas Berhasil',
            );
        } catch (Exception $e) {
            DB::rollBack();
            $result = array(
                "status" => 400,
                "message" => 'Gagal Hapus Pelayanan Obat Bebas',
                "result" => $e->getMessage(),
            );
        }
        return $this->respond($result, $result['status'], $result['message']);
    }

    public function saveReturTagihanObat(Request $request)
    {
        DB::beginTransaction();
        $req = $request->all();
        $pegawai = DB::table('loginuser_s as lu')
            ->JOIN('pegawai_m as pg', function ($j) {
                $j->on('pg.id', '=', 'lu.objectpegawaifk')->on('pg.kdprofile', '=', 'lu.kdprofile');
            })
            ->select('pg.id', 'pg.namalengkap')
            ->where('lu.kdprofile', $this->kdProfile)
            ->where('lu.id', $this->getUserId())
            ->first();

        try {
            if ($request['strukresep']['norec_sp'] == '') {
                $SP = new StrukPelayanan();
                $norecSP = $SP->generateNewId();
                $noStruk = $this->generateCode(new StrukPelayanan, 'nostruk', 13, 'OB/' . $this->getDateTime()->format('ym/'), $this->kdProfile());
                $SP->norec = $norecSP;
                $SP->kdprofile = $this->kdProfile;
                $SP->statusenabled = true;
                $SP->nostruk = $noStruk;
            } else {
                $SP = StrukPelayanan::where('nostruk', $request['strukresep']['noresep'])->where('kdprofile', $this->kdProfile)->first();
                $noStruk = $SP->nostruk;
                $norecSP = $SP->norec;
            }
            $SP->objectkelompoktransaksifk = $this->kelompokTransaksi("RETUR OBAT FARMASI");
            $SP->keteranganlainnya = $req['strukresep']['keteranganlainnya'];
            $SP->namapasien_klien = $req['strukresep']['namapasien'];
            $SP->nostruk_intern = $req['strukresep']['nocm'];
            $SP->namarekanan = 'Umum/Tunai';
            $SP->tglfaktur =  $req['strukresep']['tglLahir'];
            $SP->noteleponfaks =  $req['strukresep']['noTelepon'];
            $SP->namatempattujuan =  $req['strukresep']['alamat'];
            $SP->objectpegawaipenanggungjawabfk = $req['strukresep']['penulisresepfk'];
            $SP->tglstruk = $req['strukresep']['tglresep'];
            $SP->totalharusdibayar = $req['strukresep']['totalharusdibayar'];
            $SP->objectruanganfk = $req['strukresep']['ruanganfk'];
            $SP->namakurirpengirim = $req['strukresep']['karyawan'];
            $SP->save();

            $namaPasien = $req['strukresep']['nocm'] . ' ' . $req['strukresep']['namapasien'];
            $nocmNama = $req['strukresep']['nocm'] . '-' . $req['strukresep']['namapasien'];

            if ($request['strukresep']['noresep'] != '-') {
                if ($request['strukresep']['retur'] != '') {
                    $newSRetur = new StrukRetur();
                    $norecSRetur = $newSRetur->generateNewId();
                    $noRetur = $this->generateCode(new StrukRetur, 'noretur', 12, 'RET/' . $this->getDateTime()->format('ym') . '/', $this->kdProfile);
                    $newSRetur->norec = $norecSRetur;
                    $newSRetur->kdprofile = $this->kdProfile;
                    $newSRetur->statusenabled = true;
                    $newSRetur->objectkelompoktransaksifk = $this->kelompokTransaksi('RETUR OBAT FARMASI');
                    $newSRetur->keteranganalasan = $request['strukresep']['alasan'];
                    $newSRetur->keteranganlainnya = 'RETUR OBAT BEBAS';
                    $newSRetur->noretur = $noRetur;
                    $newSRetur->objectruanganfk = $request['strukresep']['ruanganfk'];
                    $newSRetur->objectpegawaifk = $pegawai->id;
                    $newSRetur->tglretur = $this->getDateTime()->format('Y-m-d H:i:s');
                    $newSRetur->strukresepfk = $norecSP;
                    $newSRetur->save();
                    $norec_retur = $newSRetur->norec;

                    foreach ($request['details'] as $item) {
                        if ((int)$item['jmlretur'] != 0) {
                            $newPPR = new PelayananPasienRetur();
                            $norecPPR = $newPPR->generateNewId();
                            $newPPR->norec = $norecPPR;
                            $newPPR->kdprofile = $this->kdProfile;
                            $newPPR->statusenabled = true;
                            $newPPR->aturanpakai = $item['aturanpakai'];
                            $newPPR->generik = $item['generik'];
                            $newPPR->hargadiscount = $item['hargadiscount'];
                            $newPPR->hargajual = $item['hargajual'];
                            $newPPR->hargasatuan = $item['hargasatuan'];
                            $newPPR->jenisobatfk = $item['jenisobatfk'];
                            $newPPR->jumlah = $item['jmlretur'];
                            $newPPR->kdkelompoktransaksi = 1;
                            $newPPR->produkfk = $item['produkfk'];
                            $newPPR->routefk = $item['routefk'];
                            $newPPR->stock = $item['stock'];
                            $newPPR->tglpelayanan = $req['strukresep']['tglresep']; //$r_SR['tglresep']->format('Y-m-d H:i:s');
                            $newPPR->harganetto = $item['harganetto'];
                            $newPPR->jeniskemasanfk = $item['jeniskemasanfk'];
                            $newPPR->rke = $item['rke'];
                            $newPPR->strukresepfk = $norecSP;
                            $newPPR->satuanviewfk = $item['satuanviewfk'];
                            $newPPR->nilaikonversi = (float)$item['nilaikonversi'];
                            $newPPR->strukterimafk = $item['nostrukterimafk'];
                            $newPPR->dosis = $item['dosis'];
                            $newPPR->keteranganlain = $nocmNama;
                            if ((int)$item['jumlah'] == 0) {
                                $newPPR->jasa = $item['jasa'];
                            } else {
                                $newPPR->jasa = 0;
                            }
                            $newPPR->strukreturfk = $norec_retur;
                            $newPPR->save();

                            //## TAMBAH STOK DARI RETUR
                            $ruanganfk = $req['strukresep']['ruanganfk'];
                            $saldoAwalin = 0;
                            $TambahStok = (float)$item['jmlretur'] * (float)$item['nilaikonversi']; //$item['jmlretur'];
                            $dataSaldoAwal = collect(DB::select("select sum(qtyproduk) as qty from stokprodukdetail_t
                            where kdprofile = $this->kdProfile and objectruanganfk='$ruanganfk' and statusenabled = true and objectprodukfk=$item[produkfk]"))->first();
                            $saldoAkhir = (float)$dataSaldoAwal->qty + $TambahStok;

                            $newSPD = StokProdukDetail::where('nostrukterimafk', $item['nostrukterimafk'])
                                ->where('kdprofile', $this->kdProfile)
                                ->where('objectruanganfk', $req['strukresep']['ruanganfk'])
                                ->where('objectprodukfk', $item['produkfk'])
                                ->where('statusenabled', true)
                                ->orderby('tglkadaluarsa', 'desc')
                                ->first();  

                            DB::table('stokprodukdetail_t')
                                ->where('kdprofile', $this->kdProfile)
                                ->where('norec', $newSPD->norec)
                                ->where('statusenabled', true)
                                ->sharedLock()
                                ->increment('qtyproduk', (float)$TambahStok);

                            $this->kartu_STOK(array(
                                "saldoawal" => (float)$dataSaldoAwal->qty,
                                "qtyin" => (float)$TambahStok,
                                "qtyout" => 0,
                                "saldoakhir" => $saldoAkhir,
                                "keterangan" => 'Retur Resep Obat Bebas No. ' . $noStruk,
                                "produkfk" => $item['produkfk'],
                                "ruanganfk" => $req['strukresep']['ruanganfk'],
                                "tglinput" => date('Y-m-d H:i:s'),
                                "tglkejadian" => date('Y-m-d H:i:s'),
                                "nostrukterimafk" => $newSPD->nostrukterimafk,
                                "norectransaksi" => $newSPD->norec,
                                "tabletransaksi" => 'stokprodukdetail_t',
                                "stokprodukdetailfk" => $newSPD->norec,
                                "flagfk" => 3,
                            ));
                        }
                    }
                    StrukPelayananDetail::where('nostrukfk', $request['strukresep']['norec_sp'])->where('kdprofile', $this->kdProfile)->delete();
                }
            }

            foreach ($req['details'] as $item) {
                $qtyJumlah = (float)$item['jumlah'];
                $SPD = new StrukPelayananDetail();
                $norecKS = $SPD->generateNewId();
                $SPD->norec = $norecKS;
                $SPD->kdprofile = $this->kdProfile;
                $SPD->statusenabled = true;
                $SPD->nostrukfk = $SP->norec;
                $SPD->objectasalprodukfk = $item['asalprodukfk'];
                $SPD->objectjeniskemasanfk = $item['jeniskemasanfk'];
                $SPD->objectprodukfk = $item['produkfk'];
                $SPD->objectruanganfk = $item['ruanganfk'];
                $SPD->objectruanganstokfk = $item['ruanganfk'];
                $SPD->objectsatuanstandarfk = $item['satuanstandarfk'];
                $SPD->aturanpakai = $item['aturanpakai'];
                $SPD->hargadiscount = $item['hargadiscount'];
                $SPD->hargadiscountgive = 0;
                $SPD->hargadiscountsave = 0;
                $SPD->harganetto = $item['hargasatuan'];
                $SPD->hargapph = 0;
                $SPD->hargappn = 0;
                $SPD->hargasatuan = $item['hargasatuan'];
                $SPD->hasilkonversi = (float)$item['nilaikonversi'];
                $SPD->namaproduk = $item['namaproduk'];
                $SPD->resepke = $item['rke'];
                $SPD->hargasatuandijamin = 0;
                $SPD->hargasatuanppenjamin = 0;
                $SPD->hargasatuanpprofile = 0;
                $SPD->hargatambahan = $item['jasa'];
                $SPD->isonsiteservice = 0;
                $SPD->kdpenjaminpasien = 0;
                $SPD->persendiscount = 0;
                $SPD->strukterimafk = $request['strukresep']['norec_sp'];
                $SPD->qtyproduk = $item['jumlah'] - $item['jmlretur'];
                $SPD->qtyprodukoutext = 0;
                $SPD->qtyprodukoutint = 0;
                $SPD->qtyprodukretur = 0;
                $SPD->satuan = '-';
                $SPD->satuanstandar = $item['satuanviewfk'];
                $SPD->tglpelayanan = $req['strukresep']['tglresep'];
                $SPD->tglkadaluarsa = $item['tglkadaluarsa'];
                $SPD->is_terbayar = 0;
                $SPD->linetotal = 0;
                $SPD->save();
            }

            DB::commit();
            $result = array(
                "status" => 200,
                "message" => "Simpan Struk Pelayanan Berhasil",
                "data" => $SP,
            );
        } catch (\Exception $e) {
            DB::rollBack();
            $result = [
                "status" => 400,
                "message"  => "Simpan Struk Pelayanan Gagal!!",
                "data" => $e->getMessage(),
            ];
        }

        return $this->respond($result, $result['status'], $result['message']);
    }
}
