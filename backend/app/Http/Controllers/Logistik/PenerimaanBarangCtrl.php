<?php

namespace App\Http\Controllers\Logistik;

use App\Http\Controllers\Controller;
use App\Models\Master\Jabatan;
use App\Models\Master\Profile;
use App\Models\Transaksi\KartuStok;
use App\Models\Transaksi\MataAnggaran;
use App\Models\Transaksi\OrderPelayanan;
use App\Models\Transaksi\RiwayatRealisasi;
use App\Models\Transaksi\StokProdukDetail;
use App\Models\Transaksi\StrukPelayanan;
use App\Models\Transaksi\StrukPelayananDetail;
use App\Models\Transaksi\StrukRealisasi;
use App\Models\Transaksi\StrukRetur;
use App\Traits\Valet;
use App\Models\Transaksi\StrukReturDetail;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;

class PenerimaanBarangCtrl extends Controller
{
    use Valet;
    public function __construct()
    {
        parent::__construct($is_encrypt = true);
    }

    public function getDataCombo()
    {

        $pegawai = DB::table('loginuser_s as lu')
            ->JOIN('pegawai_m as pg', function ($j) {
                $j->on('pg.id', '=', 'lu.objectpegawaifk')->on('pg.kdprofile', '=', 'lu.kdprofile');
            })
            ->select('pg.id', 'pg.namalengkap')
            ->where('lu.kdprofile', $this->kdProfile)
            ->where('lu.id', $this->getUserId())
            ->get();

        $dataKelompokProduk = DB::table('kelompokproduk_m as jk')
            ->select('jk.id', 'jk.kelompokproduk')
            ->where('jk.kdprofile', $this->kdProfile)
            ->where('jk.statusenabled', true)
            ->get();

        $ruangan = DB::table('maploginusertoruangan_s as mlur')
            ->join('loginuser_s as lu', function ($j) {
                $j->on('lu.id', 'mlur.objectloginuserfk')->on('lu.kdprofile', 'mlur.kdprofile');
            })
            ->join('ruangan_m as ru', function ($j) {
                $j->on('ru.id', 'mlur.objectruanganfk')->on('ru.kdprofile', 'mlur.kdprofile');
            })
            ->select('ru.id', 'ru.namaruangan')
            ->where('lu.kdprofile', $this->kdProfile)
            ->where('ru.statusenabled', true)
            ->where('lu.id', $this->getUserId())
            ->groupBy('ru.id', 'ru.namaruangan')
            ->get();

        $pegawaiPembuat = DB::table('pegawai_m')
            ->select('id', 'namalengkap')
            ->where('kdprofile', $this->kdProfile)
            ->where('statusenabled', true)
            ->get();

        $koordinator = DB::table('jenisusulan_m')
            ->select('id', 'jenisusulan')
            ->where('statusenabled', true)
            ->where('kdprofile', $this->kdProfile)
            ->get();

        $dataSumberDana = DB::table('asalproduk_m as lu')
            ->select('lu.id', 'lu.asalproduk')
            ->where('lu.statusenabled', true)
            ->get();

        $rekanan = DB::table('rekanan_m')
            ->select('id', 'namarekanan')
            ->where('kdprofile', $this->kdProfile)
            ->where('statusenabled', true)
            ->where('objectjenisrekananfk', 1)
            ->get();
        $pabrik = DB::table('rekanan_m')
            ->select('id', 'namarekanan')
            ->where('kdprofile', $this->kdProfile)
            ->where('statusenabled', true)
            ->where('objectjenisrekananfk', 10)
            ->orderBy('id','asc')
            ->get();

        // $dataProduk = DB::table('produk_m as pr')
        //     ->JOIN('detailjenisproduk_m as djp', 'djp.id', '=', 'pr.objectdetailjenisprodukfk')
        //     ->JOIN('jenisproduk_m as jp', 'jp.id', '=', 'djp.objectjenisprodukfk')
        //     ->leftJOIN('satuanstandar_m as ss', 'ss.id', '=', 'pr.objectsatuanstandarfk')
        //     ->JOIN('stokprodukdetail_t as spd', 'spd.objectprodukfk', '=', 'pr.id')
        //     ->select('pr.id', 'pr.kdproduk', 'pr.namaproduk', 'ss.id as ssid', 'ss.satuanstandar')
        //     ->where('pr.statusenabled', true)
        //     ->where('pr.kdprofile', $this->kdProfile)
        //     ->where('spd.qtyproduk', '>', 0)
        //     ->groupBy('pr.id', 'pr.kdproduk', 'pr.namaproduk', 'ssid', 'ss.satuanstandar')
        //     ->orderBy('pr.namaproduk')
        //     ->get();

        $jabatan = Jabatan::where('kdprofile', $this->kdProfile)
            ->where('statusenabled', true)
            ->get();

        // $dataKonversiProduk = DB::table('konversisatuan_t as ks')
        //     ->JOIN('satuanstandar_m as ss', 'ss.id', '=', 'ks.satuanstandar_asal')
        //     ->JOIN('satuanstandar_m as ss2', 'ss2.id', '=', 'ks.satuanstandar_tujuan')
        //     ->select(
        //         'ks.objekprodukfk',
        //         'ks.satuanstandar_asal',
        //         'ss.satuanstandar',
        //         'ks.satuanstandar_tujuan',
        //         'ss2.satuanstandar as satuanstandar2',
        //         'ks.nilaikonversi'
        //     )
        //     ->where('ks.kdprofile', $this->kdProfile)
        //     ->where('ks.statusenabled', true)
        //     ->get();

        // $dataProdukResult = [];
        // foreach ($dataProduk as $item) {
        //     $satuanKonversi = [];
        //     foreach ($dataKonversiProduk  as $item2) {
        //         if ($item->id == $item2->objekprodukfk) {
        //             $satuanKonversi[] = array(
        //                 'proid' => $item2->objekprodukfk,
        //                 'ssid' =>   $item2->satuanstandar_tujuan,
        //                 'satuanstandar' =>   $item2->satuanstandar2,
        //                 'nilaikonversi' =>   $item2->nilaikonversi,
        //             );
        //         }
        //     }

        //     $dataProdukResult[] = array(
        //         'id' =>   $item->id,
        //         'namaproduk' =>   $item->namaproduk,
        //         'kdsirs' => $item->kdproduk,
        //         'kdproduk' => $item->kdproduk,
        //         'ssid' =>   $item->ssid,
        //         'satuanstandar' =>   $item->satuanstandar,
        //         'konversisatuan' => $satuanKonversi,
        //     );
        // }
        $password = $this->settingFix('StockOpnamePassword');
        $mataanggaran = MataAnggaran::where('kdprofile', $this->kdProfile)
        ->where('statusenabled', true)
        ->get();
        $result = [
            'pegawai' => $pegawai,
            'kelompokbarang' => $dataKelompokProduk,
            'ruangan' => $ruangan,
            'suplier' => $rekanan,
            'pabrik' => $pabrik,
            'pinjamsupp' => $rekanan,
            // 'produk' => $dataProduk,
            'sumberdana' => $dataSumberDana,
            'koordinator' => $koordinator,
            'pembuatkomit' => $pegawaiPembuat,
            // 'produk' => $dataProdukResult,
            'password' => $password,
            'jabatan' => $jabatan,
            'mataanggaran' => $mataanggaran
        ];

        return $this->respond($result);
    }

    public function getJabatan(){
        $data = Jabatan::mine()->get();

        return $this->respond($data);
    }

    public function getDataProdukDetail(Request $request)
    {
        $dataProduk = DB::table('produk_m as pr')
            ->JOIN('detailjenisproduk_m as djp', 'djp.id', '=', 'pr.objectdetailjenisprodukfk')
            ->JOIN('jenisproduk_m as jp', 'jp.id', '=', 'djp.objectjenisprodukfk')
            ->JOIN('kelompokproduk_m as kp', 'kp.id', '=', 'jp.objectkelompokprodukfk')
            ->leftJOIN('satuanstandar_m as ss', 'ss.id', '=', 'pr.objectsatuanstandarfk')
            ->leftJOIN('stokprodukdetail_t as spd', 'spd.objectprodukfk', '=', 'pr.id')
            ->select('pr.id', 'pr.kdproduk', 'pr.namaproduk', 'kp.id as kpid', 'kp.kelompokproduk', 'ss.id as ssid', 'ss.satuanstandar', 'pr.spesifikasi')
            ->where('pr.kdprofile', $this->kdProfile)
            // ->where('pr.namaproduk', 'ilike', '%' . $request['namaproduk'] . '%')
            ->where(function($query) use ($request) {
                $query->where('pr.namaproduk', 'ilike', '%' . $request['namaproduk'] . '%')
                      ->orWhere('pr.kdproduk', 'ilike', '%' . $request['namaproduk'] . '%');
            })
            ->where('pr.statusenabled', true)
            ->where('kp.id', $request['idkelompokproduk'])
            ->groupBy('pr.id', 'pr.namaproduk', 'ss.id', 'kpid', 'ss.satuanstandar', 'ssid', 'pr.spesifikasi','kdproduk')
            ->orderBy('pr.namaproduk')
            ->limit(20)
            ->get();

        $dataKonversiProduk = DB::table('konversisatuan_t as ks')
            ->JOIN('satuanstandar_m as ss', 'ss.id', '=', 'ks.satuanstandar_asal')
            ->JOIN('satuanstandar_m as ss2', 'ss2.id', '=', 'ks.satuanstandar_tujuan')
            ->select(
                'ks.objekprodukfk',
                'ks.satuanstandar_asal',
                'ss.satuanstandar',
                'ks.satuanstandar_tujuan',
                'ss2.satuanstandar as satuanstandar2',
                'ks.nilaikonversi'
            )
            ->where('ks.kdprofile', $this->kdProfile)
            ->where('ks.statusenabled', true)
            ->get();
        $dataProdukResult = [];
        foreach ($dataProduk as $item) {
            $satuanKonversi = [];
            foreach ($dataKonversiProduk as $item2) {
                if ($item->id == $item2->objekprodukfk) {
                    $satuanKonversi[] = array(
                        'ssid' => $item2->satuanstandar_tujuan,
                        'satuanstandar' => $item2->satuanstandar2,
                        'nilaikonversi' => $item2->nilaikonversi,
                    );
                }
            }

            $dataProdukResult[] = array(
                'id' => $item->id,
                'namaproduk' => $item->namaproduk,
                'kdproduk' => $item->kdproduk,
                'ssid' => $item->ssid,
                'kelompokprofk' => $item->kpid,
                'kelompokproduk' => $item->kelompokproduk,
                'satuanstandar' => $item->satuanstandar,
                'konversisatuan' => $satuanKonversi,
                'spesifikasi' => $item->spesifikasi
            );
        }

        $result = array(
            'produk' => $dataProdukResult,
            'message' => 'as@epic',
        );

        return $this->respond($result);
    }

    public function getHargaTerakhir(Request $request)
    {
        $maxHarga = DB::table('stokprodukdetail_t as spd')
            ->join('produk_m as pr', 'pr.id', 'spd.objectprodukfk')
            ->join('satuanstandar_m as ss', 'ss.id', 'pr.objectsatuanstandarfk')
            ->leftjoin('detailjenisproduk_m as djp', 'djp.id', 'pr.objectdetailjenisprodukfk')
            ->leftjoin('strukpelayanandetail_t as spdl', 'spdl.nostrukfk', 'spd.nostrukterimafk')
            ->select(
                'spd.tglpelayanan',
                'spd.harganetto1 as harga',
                'spdl.harganetto',
                'pr.id',
                'pr.kdproduk',
                'pr.namaproduk',
                'ss.satuanstandar',
                'pr.objectdetailjenisprodukfk'
            )
            ->distinct()
            ->where('spd.kdprofile', $this->kdProfile)
            ->where('spd.objectprodukfk', $request['produkfk'])
            //   ->where('spd.objectruanganfk',$request['ruangan'])
            ->where('djp.objectjenisprodukfk', $this->settingFix('kdJenisProdukObat'))
            ->get();

        // return $this->respond($maxHarga);

        $dataNyawaTerakhir = [];
        $samateu = false;
        foreach ($maxHarga as $item) {
            $samateu = false;
            foreach ($dataNyawaTerakhir as $items) {
                if ($item->id == $items['id']) {
                    $samateu = true;
                    if ($item->tglpelayanan > date($items['tglpelayanan'])) {
                        $items['harga'] = $item->harga;
                        $items['hargapenerimaan'] = $item->harganetto;
                        $items['tglpelayanan'] = $item->tglpelayanan;
                        break;
                    }
                }
            }
            if ($samateu == false) {
                $dataNyawaTerakhir[] = array(
                    'id' => $item->id,
                    'tglpelayanan' => $item->tglpelayanan,
                    // 'harga' => $item->harga,
                    'harga' => $item->objectdetailjenisprodukfk == 2549 ? 0 : $item->harga,
                    'hargapenerimaan' => $item->harganetto,
                    'kdproduk' => $item->kdproduk,
                    'namaproduk' => $item->namaproduk,
                    'satuanstandar' => $item->satuanstandar,
                );
            }
        }

        $result = array(
            'detail' => $dataNyawaTerakhir,
            'message' => 'as@epic',
        );
        return $this->respond($result);
    }

    public function savePenerimaanBarangSuplier(Request $request)
    {
        DB::beginTransaction();
        try {
            $noStruk = "";
            if ($request['struk']['nostruk'] == '') {
                $SP = new StrukPelayanan();
                $norecSP = $SP->generateNewId();
                $noStruk = $this->SEQUENCE(new StrukPelayanan, 'nostruk', 13, 'RS/' . $this->getDateTime()->format('ym/'), $this->kdProfile);
                $SP->noterima = $noStruk;
                $SP->norec = $norecSP;
                $SP->kdprofile = $this->kdProfile;
                $SP->statusenabled = true;
                $SP->noorderfk = $request['struk']['norecOrder'];
                $SP->nostruk = $noStruk;

                if ($request['struk']['asalproduk'] === 7) {
                    $noStrukKasKecil = $request['struk']['noBuktiKK']; //$request['struk']['noBuktiKK'];//$this->generateCode(new StrukPelayanan, 'nostruk_intern', 13, 'KK/'.$this->getDateTime()->format('ym/'));
                    $SP->nostruk_intern = $noStrukKasKecil;
                    $SP->objectruanganasalfk = $request['struk']['ruanganfkKK'];
                    $SP->tglspk = date('Y-m-d H:i:s', strtotime($request['struk']['tglKK']));
                    $SP->nosppb = $request['struk']['noorder'];
                }
                $message = 'Data Berhasil Disimpan';
            } else {
                $message = 'Data Berhasil Diupdate';
                $dataKS =  KartuStok::where('keterangan',  'Penerimaan Barang Suplier. No Terima. ' . $request['struk']['nostruk'] . ' Faktur No.' . $request['struk']['nofaktur'] . ' ' . $request['struk']['namarekanan'])
                    ->where('kdprofile', $this->kdProfile)
                    ->update(['flagfk' => null]);
                //##PENAMBAHAN KEMBALI STOKPRODUKDETAIL
                $dataKembaliStok = DB::select(
                    DB::raw("select spd.norec as norec_spd, sp.norec,spd.qtyproduk,spd.hasilkonversi,sp.objectruanganfk,spd.objectprodukfk,
                              sp.nostruk,pr.namaproduk
                                    from strukpelayanandetail_t as spd
                                    INNER JOIN strukpelayanan_t sp on sp.norec=spd.nostrukfk
                                    INNER JOIN produk_m as pr on pr.id = spd.objectprodukfk
                                    where sp.kdprofile = $this->kdProfile and sp.norec=:norec"),
                    array('norec' => $request['struk']['norec'])
                );
                $TambahStok = 0;
                $qtyJumlahSisa = 0;
                foreach ($dataKembaliStok as $item5) {
                    $qtyJumlahSisa = ((float)$item5->qtyproduk) * (float)$item5->hasilkonversi;
                    foreach ($request['details'] as $item) {
                        if ($item['produkfk'] == $item5->objectprodukfk) {
                            $qtyJumlahSisa = ((float)$item5->qtyproduk - (float)$item['jumlahdipakai']) * (float)$item5->hasilkonversi;
                        }
                    }
                    $TambahStok = (float)$item5->qtyproduk * (float)$item5->hasilkonversi;
                    $saldoAwal = 0;
                    $dataSaldoAwal = collect(DB::select("
                            select sum(qtyproduk) as qty from stokprodukdetail_t
                            where kdprofile = $this->kdProfile and objectruanganfk=$item5->objectruanganfk and objectprodukfk=$item5->objectprodukfk
                        "))->first();
                    $saldoKeun = (float)$dataSaldoAwal->qty - $qtyJumlahSisa; //$TambahStok;
                    $saldoAwal = (float)$dataSaldoAwal->qty;
                    if ($request['struk']['norecOrder'] != '') {
                        foreach ($request['details'] as $item) {
                            $dataOP = OrderPelayanan::where('noorderfk', $request['struk']['norecOrder'])
                                ->where('kdprofile', $this->kdProfile)
                                ->where('objectprodukfk', $item['produkfk'])
                                ->update(
                                    [
                                        'qtyterimalast' => (float)$item['jumlah']
                                    ]
                                );
                        }
                    }

                    $tglnow =  date('Y-m-d H:i:s');
                    $tglUbah = date('Y-m-d H:i:s', strtotime('-10 seconds', strtotime($tglnow)));
                    $this->kartu_STOK(array(
                        "saldoawal" => $saldoAwal + $TambahStok,
                        "qtyin" => 0,
                        "qtyout" => $TambahStok,
                        "saldoakhir" => $saldoAwal,
                        "keterangan" => 'Ubah Penerimaan No. ' . $item5->nostruk . ', pada produk ' . $item5->namaproduk,
                        "produkfk" => $item5->objectprodukfk,
                        "ruanganfk" => $item5->objectruanganfk,
                        "tglinput" => $tglUbah,
                        "tglkejadian" => $tglUbah,
                        "nostrukterimafk" => $request['struk']['nostruk'],
                        "norectransaksi" => $request['struk']['norecOrder'],
                        "stokprodukdetailfk" => $item5->norec_spd,
                        "tabletransaksi" => 'orderpelayanan_t',
                        "flagfk" => null,
                    ));

                    //END##PENAMBAHAN KEMBALI STOKPRODUKDETAIL
                    $SP = StrukPelayanan::where('norec', $request['struk']['norec'])->first();
                    // $noStruk = $SP->nostruk;
                    StokProdukDetail::where('nostrukterimafk', $request['struk']['norec'])
                        ->where('kdprofile', $this->kdProfile)
                        ->where('objectruanganfk', $item5->objectruanganfk)
                        ->where('objectprodukfk', $item5->objectprodukfk)
                        ->delete();
                    StrukPelayananDetail::where('nostrukfk', $request['struk']['norec'])
                        ->where('kdprofile', $this->kdProfile)
                        ->delete();
                }
            }

            $SP->objectkelompoktransaksifk = $this->kelompokTransaksi('PENERIMAAN BARANG SUPPLIER'); // $request['struk']['kelompoktranskasi'];
            $SP->objectrekananfk = $request['struk']['rekananfk'];
            $SP->namarekanan = $request['struk']['namarekanan'];
            $SP->objectruanganfk = $request['struk']['ruanganfk'];
            $SP->keteranganlainnya = 'Penerimaan Barang Dari Supplier';
            $SP->nokontrak = $request['struk']['nokontrak'];
            $SP->nofaktur = $request['struk']['nofaktur'];
            $SP->objectkelompokprodukfk = $request['struk']['kelompokprodukfk'];

            $SP->tglfaktur = date('Y-m-d H:i:s', strtotime($request['struk']['tglfaktur']));
            $SP->tglstruk = date('Y-m-d H:i:s', strtotime($request['struk']['tglstruk']));
            $SP->objectpegawaipenerimafk = $request['struk']['pegawaimenerimafk'];

            $SP->objectpegawaipenanggungjawabfk = $request['struk']['pegawaiKK'];
            $SP->namapegawaipenerima = $request['struk']['namapegawaipenerima'];
            $SP->qtyproduk = $request['struk']['qtyproduk'];
            $SP->totalharusdibayar = $request['struk']['totalharusdibayar'];
            $SP->totalpembulatan = $request['struk']['totalpembulatan'];
            $SP->totalppn = $request['struk']['totalppn'];
            $SP->totaldiscount = $request['struk']['totaldiscount'];
            $SP->totalhargasatuan = $request['struk']['totalhargasatuan'];
            $SP->keteranganambil = $request['struk']['ketTerima'];
            $SP->tgldokumen = date('Y-m-d H:i:s', strtotime($request['struk']['tglorder']));
            $SP->tglkontrak =  date('Y-m-d H:i:s', strtotime($request['struk']['tglkontrak']));
            $SP->namapengadaan = $request['struk']['namapengadaan'];
            $SP->tgljatuhtempo = $request['struk']['tglTempo'] ? date('Y-m-d H:i:s', strtotime($request['struk']['tglTempo'])) : null;
            $SP->ispinjam = $request['struk']['ispinjam'];
            $SP->rekananpinjamfk = $request['struk']['rekananpinjamfk'];
            $SP->pabrikfk = $request['struk']['pabrikfk'];
            $SP->save();
            $qtyJumlah = 0;
            $qtyJumlahSisa = 0;
            foreach ($request['details'] as $item) {
                $qtyJumlah = 0;
                $qtyJumlah = (float)$item['jumlah'] * (float)$item['nilaikonversi'];
                $qtyJumlahSisa = (float)$item['sisa'] * (float)$item['nilaikonversi'];
                $SPD = new StrukPelayananDetail();
                $norecKS = $SPD->generateNewId();
                $SPD->norec = $norecKS;
                $SPD->kdprofile = $this->kdProfile;
                $SPD->statusenabled = true;
                $SPD->nostrukfk = $SP->norec;
                $SPD->objectasalprodukfk = $request['struk']['asalproduk']; //$item['asalprodukfk'];
                $SPD->objectprodukfk = $item['produkfk'];
                $SPD->objectruanganfk = $request['struk']['ruanganfk'];
                $SPD->objectruanganstokfk = $request['struk']['ruanganfk'];
                $SPD->objectsatuanstandarfk = $item['ssid'];
                $SPD->hargadiscount = $item['hargadiskon'];
                $SPD->hargadiscountgive = 0;
                $SPD->hargadiscountsave = 0;
                $SPD->harganetto = $item['hargasatuan'];
                $SPD->hargapph = 0;
                $SPD->hargappn = $item['nilaippn'];
                $SPD->hargasatuan = $item['hargasatuan'];
                $SPD->hasilkonversi = $item['nilaikonversi'];
                $SPD->namaproduk = $item['namaproduk'];
                $SPD->keteranganlainnya = $item['keterangan'];
                $SPD->hargasatuandijamin = 0;
                $SPD->hargasatuanppenjamin = 0;
                $SPD->hargatambahan = 0;
                $SPD->hargasatuanpprofile = 0;
                $SPD->isonsiteservice = 0;
                $SPD->kdpenjaminpasien = 0;
                $SPD->persendiscount = $item['persendiscount'];
                $SPD->persenppn = $item['persenppn'];
                $SPD->qtyproduk = $item['jumlah'];
                $SPD->qtyprodukoutext = 0;
                $SPD->qtyprodukoutint = 0;
                $SPD->qtyprodukretur = 0;
                $SPD->satuan = '-'; //$item['satuanstandar'];;
                $SPD->satuanstandar = $item['satuan'];
                $SPD->tglpelayanan = date('Y-m-d H:i:s', strtotime($request['struk']['tglstruk'])); //$request['struk']['tglstruk'];
                $SPD->is_terbayar = 0;
                $SPD->linetotal = 0;
                $SPD->tglkadaluarsa = $item['tglkadaluarsa'] ? date('Y-m-d H:i:s', strtotime($item['tglkadaluarsa'])) : null;
                $SPD->nobatch = $item['nobatch'];
                $SPD->save();

                $ruanganId = $request['struk']['ruanganfk'];
                $saldoAwalIns = 0;
                $dataSaldoAwal = collect(DB::select("
                        select sum(qtyproduk) as qty from stokprodukdetail_t
                        where kdprofile = $this->kdProfile
                        and objectruanganfk = $ruanganId
                        and objectprodukfk = $item[produkfk]
                    "))->first();

                $dataSort = DB::table('stokprodukdetail_t')
                    ->select(DB::raw("
                                    norec,qtyproduk,sort
                                "))
                    ->where('kdprofile', $this->kdProfile)
                    ->where('objectruanganfk', $ruanganId)
                    ->where('objectprodukfk', $item['produkfk'])
                    ->get();

                $sort = null;
                $norec = null;
                foreach ($dataSort as $itm) {
                    if ($itm->qtyproduk <= 0) {
                        $norec = $itm->norec;
                    }
                }

                $saldoAwalIns = $dataSaldoAwal->qty + $qtyJumlahSisa; //$qtyJumlah;
                $sort = DB::table('stokprodukdetail_t')
                    ->where('kdprofile', $this->kdProfile)
                    ->where('objectruanganfk', $request['struk']['ruanganfk'])
                    ->where('objectprodukfk', $item['produkfk'])
                    ->where('nostrukterimafk', $SPD->norec)
                    ->update([
                        "sort" => null
                    ]);
                //## StokProdukDetail
                $StokPD = new StokProdukDetail();
                $norecStokPD = $StokPD->generateNewId();
                $StokPD->norec = $norecKS;
                $StokPD->kdprofile = $this->kdProfile;
                $StokPD->statusenabled = true;
                $StokPD->objectasalprodukfk = $request['struk']['asalproduk']; //$item['asalprodukfk'];
                $StokPD->hargadiscount = 0;
                $diskon = (((float)$item['persendiscount']) * (float)$item['hargasatuan']) / 100;
                $hargaStlhDiskon = (float)$item['hargasatuan'] -  $diskon;
                $ppn = ((float) $item['persenppn'] * $hargaStlhDiskon) / 100;
                $StokPD->harganetto1 = ((float)$item['hargasatuan'] + $ppn) / (float)$item['nilaikonversi'];
                $StokPD->harganetto2 = $hargaStlhDiskon + $ppn / (float)$item['nilaikonversi'];
                $StokPD->persendiscount = 0;
                $StokPD->objectprodukfk = $item['produkfk'];
                // $StokPD->qtyproduk = $qtyJumlahSisa; //$qtyJumlah;
                $StokPD->qtyproduk = $qtyJumlah;
                $StokPD->qtyprodukonhand = 0;
                $StokPD->qtyprodukoutext = 0;
                $StokPD->qtyprodukoutint = 0;
                $StokPD->objectruanganfk = $request['struk']['ruanganfk'];
                $StokPD->nostrukterimafk = $SP->norec;
                $StokPD->nobatch = $item['nobatch'];
                $StokPD->objectstrukpelayanandetail = $SPD->norec;
                $StokPD->tglkadaluarsa = $item['tglkadaluarsa'] ? date('Y-m-d H:i:s', strtotime($item['tglkadaluarsa'])) : null;
                $StokPD->tglpelayanan = date('Y-m-d H:i:s', strtotime($request['struk']['tglstruk'])); //$request['struk']['tglstruk'];
                $StokPD->sort = 1;
                $StokPD->save();

                //## KartuStok
                $this->kartu_STOK(array(
                    "saldoawal" => $dataSaldoAwal->qty,
                    "qtyin" => $qtyJumlah,
                    "qtyout" => 0,
                    "saldoakhir" => $dataSaldoAwal->qty + $qtyJumlah,
                    "keterangan" => 'Penerimaan Barang Suplier. Berupa produk ' .  $item['namaproduk'] . ', No Terima. ' . $noStruk . ', Faktur No.' . $request['struk']['nofaktur'] . ' ' . $request['struk']['namarekanan'],
                    "produkfk" => $item['produkfk'],
                    "ruanganfk" => $request['struk']['ruanganfk'],
                    "tglinput" => date('Y-m-d H:i:s'),
                    "tglkejadian" => date('Y-m-d H:i:s'),
                    "nostrukterimafk" => $SP->norec,
                    "norectransaksi" => $SP->norec,
                    "tabletransaksi" => 'strukpelayanan_t',
                    "stokprodukdetailfk" => $SPD->norec,
                    "flagfk" => null,
                ));
            }
            //***** Struk Realisasi *****
            $datanorecSR = '';
            if ($request['struk']['norecrealisasi'] == '') {
                $dataSR = new StrukRealisasi();
                $norealisasi = $this->generateCode(new StrukRealisasi(), 'norealisasi', 10, 'RA-' . $this->getDateTime()->format('ym'), $this->kdProfile);
                $dataSR->norec = $dataSR->generateNewId();
                $dataSR->kdprofile = $this->kdProfile;
                $dataSR->statusenabled = true;
                $dataSR->norealisasi = $norealisasi;
                $dataSR->tglrealisasi = date('Y-m-d H:i:s', strtotime($request['struk']['tglrealisasi']));
                $dataSR->objectmataanggaranfk = $request['struk']['objectmataanggaranfk'] == '' ?  null : $request['struk']['objectmataanggaranfk'];
                $dataSR->totalbelanja = (float) $request['struk']['totalharusdibayar'];
                $dataSR->save();
                $datanorecSR = $dataSR->norec;
            } else {
                $dataSR = StrukRealisasi::where('norec', $request['struk']['norecrealisasi'])->first();
                $dataSR->tglrealisasi = date('Y-m-d H:i:s', strtotime($request['struk']['tglrealisasi'])); //$request['struk']['tglstruk'];
                $dataSR->objectmataanggaranfk = $request['struk']['objectmataanggaranfk'] == '' ?  null : $request['struk']['objectmataanggaranfk'];
                $dataSR->totalbelanja = (float) $request['struk']['totalharusdibayar'];
                $dataSR->save();
                $datanorecSR = $request['struk']['norecrealisasi'];
            }

            //***** Riwayat Realisasi *****
            //            if ($request['struk']['norecrealisasi'] == '') {
            $dataRR = new RiwayatRealisasi();
            $dataRR->norec = $dataRR->generateNewId();
            $dataRR->kdprofile = $this->kdProfile;
            $dataRR->statusenabled = true;
            $dataRR->objectkelompoktransaksifk = $this->kelompokTransaksi('PENERIMAAN BARANG SUPPLIER');
            $dataRR->objectstrukrealisasifk = $datanorecSR;
            $dataRR->objectstrukfk = $request['struk']['norecOrder'];
            $dataRR->penerimaanfk = $SP->norec;
            $dataRR->tglrealisasi = date('Y-m-d H:i:s', strtotime($request['struk']['tglrealisasi'])); //$request['struk']['tglrealisasi'];
            $dataRR->objectpetugasfk = $request['struk']['pegawaimenerimafk'];
            $dataRR->noorderintern = $request['struk']['nousulan'];
            $dataRR->keteranganlainnya = 'Penerimaan Barang Dari Supplier';
            $dataRR->save();

            // $this->LOGGING(
            //     $SP->keteranganlainnya,
            //     $SP->norec,
            //     'strukpelayanan_t',
            //     "Penjualan Obat Bebas pada pasien $SP->namapasien_klien ($norm) - ".
            //     "dari ruangan $ruangan->namaruangan - no struk $SP->nostruk"
            // );

            DB::commit();

            $response = [
                'message' => $message,
                'status' => 201,
                'datas' => [
                    'strukPelayanan' => $SP,
                    'strukPelayananDetail' => $SPD,
                    'stokPD' => $StokPD,
                    'strukRealisasi' => $dataSR,
                    'riwayatRealisasi' => $dataRR
                ]
            ];
        } catch (Exception $e) {
            DB::rollBack();
            $response = [
                'message' => 'Something Went Wrong',
                'status' => 400,
                'datas' => $e->getMessage()
            ];
        }

        return $this->respond($response['datas'], $response['status'], $response['message']);
    }

    public function editHeaderPenerimaanSuplier(Request $request){

        DB::beginTransaction();
        try{
            StrukPelayanan::where('norec', $request['struk']['norec'])->where('statusenabled',true)->where('kdprofile',$this->kdProfile)
                    ->update([
                        'objectrekananfk' => $request['struk']['rekananfk'],
                        'namarekanan' => $request['struk']['namarekanan'],
                        'nokontrak' => $request['struk']['nokontrak'],
                        'nofaktur' => $request['struk']['nofaktur'],
                        'tglfaktur' => $request['struk']['tglfaktur'],
                        'tglstruk' => $request['struk']['tglstruk'],
                        'keteranganambil' => $request['struk']['ketTerima'],
                        'tgldokumen' => $request['struk']['tglorder'],
                        'tglkontrak' => $request['struk']['tglkontrak'],
                        'namapengadaan' =>$request['struk']['namapengadaan'],
                        'tgljatuhtempo' => $request['struk']['tglTempo']
                    ]);

            DB::commit();
            $result = [
                "status" => 200,
                "message" => "Proses Update Data Berhasil",
            ];
        }catch(Exception $e){
            DB::rollBack();
            $result = [
                "status" => 400,
                "message" => "Proses Update Data Berhasil",
                "respond" => $e->getMessage()
            ];
        }

        return $this->respond($result,$result['status'],$result['message']);
    }

    public function DeletePenerimaanBarangSupplier(Request $request)
    {
        DB::beginTransaction();
        try {
            $dataKembaliStok = DB::table('strukpelayanandetail_t as spd')
                ->join('strukpelayanan_t as sp', 'sp.norec', 'spd.nostrukfk')
                ->select(
                    'sp.norec',
                    'spd.qtyproduk',
                    'spd.hasilkonversi',
                    'sp.objectruanganfk',
                    'spd.objectprodukfk',
                    'sp.nostruk',
                    'spd.norec as norectransaksi',
                )
                ->where('spd.kdprofile', $this->kdProfile)
                ->where('sp.norec', $request['nostruk'])
                ->get();

            $dataStokSudahKirim = StokProdukDetail::where('nostrukterimafk', $request['nostruk'])
                ->where('kdprofile', $this->kdProfile)
                ->whereNotIn('objectruanganfk', [$dataKembaliStok[0]->objectruanganfk])
                ->where('qtyproduk', '>', 0)
                ->get();

            if (count($dataStokSudahKirim) == 0) {
                foreach ($dataKembaliStok as $item5) {
                    $TambahStok = (float)$item5->qtyproduk * (float)$item5->hasilkonversi;
                    $dataSaldoAwal = collect(DB::select("
                                select sum(qtyproduk) as qty from stokprodukdetail_t
                                where kdprofile = $this->kdProfile
                                and objectruanganfk = $item5->objectruanganfk
                                and objectprodukfk= $item5->objectprodukfk
                        "))->first();
                    $saldoakhir = (float)$dataSaldoAwal->qty - $TambahStok;
                    $dataPenerimaan = DB::table('strukpelayanan_t as sr')
                        ->leftJoin('rekanan_m as rkn', 'rkn.id', '=', 'sr.objectrekananfk')
                        ->select(DB::raw("sr.nostruk,sr.nofaktur,rkn.namarekanan"))
                        ->where('sr.kdprofile', $this->kdProfile)
                        ->where('sr.norec', $request['nostruk'])
                        ->first();

                    KartuStok::where('keterangan',  'Penerimaan Barang Suplier. No Terima. ' . $dataPenerimaan->nostruk . ' Faktur No.' . $dataPenerimaan->nofaktur . ' ' . $dataPenerimaan->namarekanan)
                        ->where('kdprofile', $this->kdProfile)
                        ->update([
                            'flagfk' => null
                        ]);

                    $this->kartu_STOK(array(
                        "saldoawal" => $dataSaldoAwal->qty,
                        "qtyin" => 0,
                        "qtyout" =>  $TambahStok,
                        "saldoakhir" => $saldoakhir,
                        "keterangan" => 'Batal Penerimaan No. ' . $item5->nostruk,
                        "produkfk" => $item5->objectprodukfk,
                        "ruanganfk" => $item5->objectruanganfk,
                        "tglinput" => date('Y-m-d H:i:s'),
                        "tglkejadian" => date('Y-m-d H:i:s'),
                        "nostrukterimafk" => $request['nostruk'],
                        "norectransaksi" => $item5->norectransaksi,
                        "tabletransaksi" => "stokprodukdetail_t",
                        "flagfk" => null,
                    ));

                    OrderPelayanan::where('noorderfk', $request['noorderfk'])
                        ->where('kdprofile', $this->kdProfile)
                        ->where('objectprodukfk', $item5->objectprodukfk)
                        ->update(['qtyterimalast' => 0]);
                }
                $SP = StrukPelayanan::where('norec', $request['nostruk'])->where('kdprofile', $this->kdProfile)->first();
                $SP->statusenabled = false;
                $SP->save();

                StokProdukDetail::where('nostrukterimafk', $request['nostruk'])
                    ->where('kdprofile', $this->kdProfile)
                    ->delete();

                $kirim = KartuStok::where('ruanganfk', $item5->objectruanganfk)
                    ->where('kdprofile', $this->kdProfile)
                    ->where('produkfk', $item5->objectprodukfk)
                    ->get();

                $kartuStok[] = $kirim;

                $dataSTOKDETAIL[] = DB::select(
                    DB::raw("select qtyproduk as qty,nostrukterimafk,norec from stokprodukdetail_t
                            where kdprofile = $this->kdProfile and objectruanganfk=:ruanganfk and objectprodukfk=:produkfk"),
                    array(
                        'ruanganfk' => $item5->objectruanganfk,
                        'produkfk' => $item5->objectprodukfk,
                    )
                );

                $stokdetail[] = $dataSTOKDETAIL;
                $message =  "Hapus Penerimaan Berhasil";
            } else {
                $message =  "Sudah ada distribusi, tidak dapat di batalkan!!";
            }
            DB::commit();
            $result = [
                'status' => 201,
                'message' => $message,
                'data' => $SP,
            ];
        } catch (Exception $e) {
            DB::rollBack();
            $result = [
                'status' => 400,
                'message' => 'Simpan Gagal !',
                'data' => $e->getMessage(),
            ];
        }

        return $this->respond($result['data'], $result['status'], $result['message']);
    }

    public function getNoTerimaGenerate(Request $request)
    {
        $noStruk = '';
        $noBuktiKK = '';
        if ($request['asalproduk'] == 7) {
            $noStruk = $this->generateCode(new StrukPelayanan, 'nostruk', 13, 'RS/' . $this->getDateTime()->format('ym/'), $this->kdProfile);
            $noBuktiKK = $this->generateCode(new StrukPelayanan, 'nostruk_intern', 13, 'KK/' . $this->getDateTime()->format('ym/'), $this->kdProfile);
        } else {
            $noStruk = $this->generateCode(new StrukPelayanan, 'nostruk', 13, 'RS/' . $this->getDateTime()->format('ym/'), $this->kdProfile);
            $noBuktiKK = '';
        }
        $result = array(
            'noStruk' => $noStruk,
            'noBuktiKK' => $noBuktiKK,
            'message' => 'Berhasil'
        );
        return $this->respond($result);
    }

    public function getDataProdukLogitik()
    {
        $rekanan = DB::table('rekanan_m')
            ->select('id', 'namarekanan')
            ->where('kdprofile', $this->kdProfile)
            ->where('statusenabled', true)
            ->get();

        $dataProduk = DB::table('produk_m as pr')
            ->JOIN('detailjenisproduk_m as djp', 'djp.id', '=', 'pr.objectdetailjenisprodukfk')
            ->JOIN('jenisproduk_m as jp', 'jp.id', '=', 'djp.objectjenisprodukfk')
            ->leftJOIN('satuanstandar_m as ss', 'ss.id', '=', 'pr.objectsatuanstandarfk')
            ->select('pr.id', 'pr.namaproduk', 'ss.id as ssid', 'ss.satuanstandar', 'pr.kdproduk')
            ->where('pr.statusenabled', true)
            ->where('pr.kdprofile', $this->kdProfile)
            ->orderBy('pr.namaproduk')
            ->get();

        $dataKonversiProduk = DB::table('konversisatuan_t as ks')
            ->JOIN('satuanstandar_m as ss', 'ss.id', '=', 'ks.satuanstandar_asal')
            ->JOIN('satuanstandar_m as ss2', 'ss2.id', '=', 'ks.satuanstandar_tujuan')
            ->select(
                'ks.objekprodukfk',
                'ks.satuanstandar_asal',
                'ss.satuanstandar',
                'ks.satuanstandar_tujuan',
                'ss2.satuanstandar as satuanstandar2',
                'ks.nilaikonversi'
            )
            ->where('ks.kdprofile', $this->kdProfile)
            ->where('ks.statusenabled', true)
            ->get();

        $dataProdukResult = [];
        foreach ($dataProduk as $proItem) {
            $satuanKonversi = [];
            foreach ($dataKonversiProduk as $konversiPro) {
                if ($proItem->id == $konversiPro->objekprodukfk) {
                    $satuanKonversi[] = [
                        'ssid' =>   $konversiPro->satuanstandar_tujuan,
                        'satuanstandar' =>   $konversiPro->satuanstandar2,
                        'nilaikonversi' =>   $konversiPro->nilaikonversi,
                    ];
                }
            }

            $dataProdukResult[] = [
                'id' => $proItem->id,
                'namaproduk' =>   $proItem->namaproduk,
                'ssid' =>   $proItem->ssid,
                'satuanstandar' =>   $proItem->satuanstandar,
                'konversisatuan' => $satuanKonversi,
                'kdproduk' => $proItem->kdproduk,
            ];
        }

        $result = ['rekanan' => $rekanan, 'produk' => $dataProdukResult];

        return $this->respond($result);
    }


    public function getDaftarPenerimaanSuplier(Request $request)
    {
        $dateBetween = [$request->tglAwal, $request->tglAkhir];
        $data = DB::table('strukpelayanan_t as sp')
            ->JOIN('strukpelayanandetail_t as spd', 'spd.nostrukfk', '=', 'sp.norec')
            ->JOIN('produk_m as pr', 'pr.id', '=', 'spd.objectprodukfk')
            ->join('asalproduk_m as ap', 'ap.id', 'spd.objectasalprodukfk')
            ->leftjoin('riwayatrealisasi_t as rr', 'rr.penerimaanfk', 'sp.norec')
            ->leftJOIN('rekanan_m as rkn', 'rkn.id', '=', 'sp.objectrekananfk')
            ->LEFTJOIN('pegawai_m as pg', 'pg.id', '=', 'sp.objectpegawaipenerimafk')
            ->LEFTJOIN('ruangan_m as ru', 'ru.id', '=', 'sp.objectruanganfk')
            ->LEFTJOIN('strukbuktipengeluaran_t as sbk', 'sbk.norec', '=', 'sp.nosbklastfk')
            ->select(
                DB::raw("
                sp.tglstruk, sp.nostruk,sp.noterima, sp.nostruk_intern,sp.keteranganlainnya,sp.namapengadaan,rkn.namarekanan,rkn.id as rekananfk,ru.id as ruanganfk,pg.namalengkap,
                pg.id as penerimafk,sp.objectruanganasalfk,sp.objectpegawaipenanggungjawabfk,sp.tglspk,sp.nokontrak,ap.asalproduk,ap.id as apid,sp.tgljatuhtempo,sp.tgldokumen,
                ru.namaruangan, sp.norec, sp.nofaktur,rr.noorderintern, sp.objectkelompokprodukfk , sp.tglfaktur,CAST(sp.totalharusdibayar AS FLOAT), sbk.nosbk, CAST(sp.totalpembulatan AS FLOAT),
                sp.nosppb, sp.totalppn,sp.totaldiscount,sp.totalhargasatuan, sp.noorderfk, sp.qtyproduk
            ")
            )
            ->where('sp.kdprofile', $this->kdProfile)
            ->where('sp.statusenabled', true)
            ->where('sp.objectkelompoktransaksifk', $this->kelompokTransaksi('PENERIMAAN BARANG SUPPLIER'))
            ->groupBy(
                'sp.tglstruk',
                'sp.nostruk',
                'sp.noterima',
                'sp.nostruk_intern',
                'sp.keteranganlainnya',
                'sp.namapengadaan',
                'rkn.namarekanan',
                'rekananfk',
                'pg.namalengkap',
                'penerimafk',
                'sp.nokontrak',
                'sp.objectruanganasalfk',
                'sp.tglspk',
                'rr.noorderintern',
                'ru.namaruangan',
                'sp.objectkelompokprodukfk',
                'ruanganfk',
                'ap.asalproduk',
                'apid',
                'sp.norec',
                'sp.nofaktur',
                'sp.tglfaktur',
                'sp.tgljatuhtempo',
                'sp.totalharusdibayar',
                'sp.totalpembulatan',
                'sp.objectpegawaipenanggungjawabfk',
                'sbk.nosbk',
                'sp.nosppb',
                'sp.tgldokumen',
                'sp.noorderfk',
                'sp.qtyproduk',
                'sp.totalppn',
                'sp.totaldiscount',
                'sp.totalhargasatuan',
            );
        if (isset($request['nostruk']) && $request['nostruk'] != "" && $request['nostruk'] != "undefined") {
            $data = $data->where('sp.nostruk', $request['nostruk']);
        }
        if (isset($request['nodokumen']) && $request['nodokumen'] != "" && $request['nodokumen'] != "undefined") {
            $data = $data->where('sp.nofaktur', $request['nodokumen']);
        }
        if (isset($request['rekananfk']) && $request['rekananfk'] != "" && $request['rekananfk'] != "undefined") {
            $data = $data->where('rkn.id', $request['rekananfk']);
        }
        if (isset($request['tglAwal']) && $request['tglAwal'] != "" && $request['tglAwal'] != "undefined") {
            $data = $data->WhereBetween(DB::raw("CAST(sp.tglstruk as DATE)"), $dateBetween);
        }
        if (isset($request['norec']) && $request['norec'] != "" && $request['norec'] != "undefined") {
            $data = $data->where('sp.norec', $request['norec']);
        }
        $total  = count($data->get());
        if (isset($request['limit']) && $request['limit'] != "" && $request['limit'] != "undefined") {
            $data = $data->limit($request['limit']);
        }
        if (isset($request['offset']) && $request['offset'] != "" && $request['offset'] != "undefined") {
            $data = $data->offset($request['offset']);
        }
        if (isset($request['namaproduk']) && $request['namaproduk'] != "" && $request['namaproduk'] != "undefined") {
            $data = $data->where('pr.namaproduk','ilike','%' . $request['namaproduk'] . '%');
        }
        if (isset($request['kodeproduk']) && $request['kodeproduk'] != "" && $request['kodeproduk'] != "undefined") {
            $data = $data->where('pr.kdproduk','ilike','%' . $request['kodeproduk'] . '%');
        }


        $data = $data->distinct();
        $data = $data->orderBy('sp.nostruk');
        $data = $data->get();
        foreach ($data as $item) {
            $details = DB::select(
                DB::raw("select  pr.namaproduk,pr.id as produkfk,ss.satuanstandar as satuan,ss.id as satuanfk,spd.qtyproduk as jumlah,spd.hasilkonversi,spd.qtyprodukretur,spd.hargasatuan,spd.hargadiscount as hargadiskon,
                    spd.hargappn as nilaippn,spd.persendiscount,spd.persenppn,CAST(((spd.qtyproduk*spd.hargasatuan)-(((spd.persendiscount*spd.hargasatuan)/100)*spd.qtyproduk))+(spd.persenppn*((spd.qtyproduk*spd.hargasatuan)-(((spd.persendiscount*spd.hargasatuan)/100)*spd.qtyproduk))/100) AS FLOAT) AS totalall,
                    spd.tglkadaluarsa,spd.keteranganlainnya,spd.nobatch,CAST(spd.qtyproduk*spd.hargasatuan as float) as subtotal
                    from strukpelayanandetail_t as spd
                    left JOIN produk_m as pr on pr.id=spd.objectprodukfk
                    left JOIN satuanstandar_m as ss on ss.id=spd.objectsatuanstandarfk
                    where spd.kdprofile = $this->kdProfile and nostrukfk=:norec"),
                array(
                    'norec' => $item->norec,
                )
            );
            $result[] = array(
                'tglstruk' => $item->tglstruk,
                'nostruk' => $item->nostruk,
                'noterima' => $item->noterima,
                'nofaktur' => $item->nofaktur,
                'tglfaktur' => $item->tglfaktur,
                'namarekanan' => $item->namarekanan,
                'namapengadaan' => $item->namapengadaan,
                'norec' => $item->norec,
                'asalproduk' => $item->asalproduk,
                'apid' => $item->apid,
                'namaruangan' => $item->namaruangan,
                'nobukti' => $item->nostruk_intern,
                'objectruanganasalfk' => $item->objectruanganasalfk,
                'tglspk' => $item->tglspk,
                'pegawaifkKK' => $item->objectpegawaipenanggungjawabfk,
                'rekananfk' => $item->rekananfk,
                'kelompokprodukfk' => $item->objectkelompokprodukfk,
                'ruanganfk' => $item->ruanganfk,
                'namapenerima' => $item->namalengkap,
                'penerimafk' => $item->penerimafk,
                'totalharusdibayar' => $item->totalharusdibayar,
                'totalpembulatan' => $item->totalpembulatan,
                'totalppn' => $item->totalppn,
                'totaldiscount' => $item->totaldiscount,
                'totalhargasatuan' => $item->totalhargasatuan,
                'nosbk' => $item->nosbk,
                'nosppb' => $item->nosppb,
                'tgldokumen' => $item->tgldokumen,
                'nokontrak' => $item->nokontrak,
                'noorderintern' => $item->noorderintern,
                'tgljatuhtempo' => $item->tgljatuhtempo,
                'noorderfk' => $item->noorderfk,
                'jmlitem' => $item->qtyproduk,
                'details' => $details,
            );
        }
        if (count($data) == 0) {
            $result = [];
        }

        $result = array(
            'daftar' => $result,
            'total' => $total,
            'message' => 'as@epic',
        );

        return $this->respond($result);
    }

    public function getDetailPenerimaanBarang(Request $request)
    {
        $dataStruk = DB::table('strukpelayanan_t as sp')
            ->leftJOIN('strukpelayanandetail_t as spd', 'spd.nostrukfk', '=', 'sp.norec')
            ->leftjoin('asalproduk_m as ap', 'ap.id', 'spd.objectasalprodukfk')
            ->LEFTJOIN('riwayatrealisasi_t as rr', 'rr.penerimaanfk', '=', 'sp.norec')
            ->LEFTJOIN('strukrealisasi_t as srr', 'srr.norec', '=', 'rr.objectstrukrealisasifk')
            ->LEFTJOIN('mataanggaran_m as ma', 'ma.id', '=', 'srr.objectmataanggaranfk')
            ->leftJOIN('pegawai_m as pg', 'pg.id', '=', 'sp.objectpegawaipenerimafk')
            ->leftJOIN('pegawai_m as pg1', 'pg1.id', '=', 'sp.objectpegawaipenanggungjawabfk')
            ->leftJOIN('strukretur_t as sr', 'sr.strukterimafk', '=', 'sp.norec')
            ->JOIN('ruangan_m as ru', 'ru.id', '=', 'sp.objectruanganfk')
            ->select(
                'sp.norec',
                'sp.tglstruk',
                'sp.nostruk',
                'rr.noorderintern as nousulan',
                'sp.nokontrak',
                'sp.totalppn',
                'sp.totaldiscount',
                'sp.totalhargasatuan',
                'sp.totalharusdibayar',
                'sr.norec as norecretur',
                'pg.id as pgid',
                'pg.namalengkap',
                'sp.nostruk_intern as nobukti',
                'ru.id',
                'ru.namaruangan',
                'sp.nofaktur',
                'sp.tglfaktur',
                'sp.tglspk',
                'sp.rekananpinjamfk',
                'sp.pabrikfk',
                'sp.objectruanganasalfk',
                'ap.asalproduk',
                'ap.id as asalprodukfk',
                'sp.namarekanan',
                'sp.objectrekananfk',
                'sp.nosppb',
                'sp.ispinjam',
                'sp.objectkelompokprodukfk',
                'srr.norealisasi',
                'srr.norec as norecrealisasi',
                'sp.tglkontrak',
                'sp.tgldokumen',
                'rr.objectstrukfk',
                'srr.objectmataanggaranfk as mataanggranid',
                'ma.namamataanggaran',
                'rr.tglrealisasi',
                'sp.keteranganlainnya',
                'sp.keteranganambil',
                'pg.id as pgid',
                'pg.namalengkap',
                'sp.objectpegawaipenanggungjawabfk',
                'pg1.namalengkap as penanggungjawab',
                'sp.namapengadaan',
                'sp.noorderfk',
                'sp.tgljatuhtempo',
                'sp.totalpembulatan',
                'sp.objectruanganfk'
            )
            ->where('sp.kdprofile', $this->kdProfile);
        if (isset($request['norec']) && $request['norec'] != "" && $request['norec'] != "undefined") {
            $dataStruk = $dataStruk->where('sp.norec', '=', $request['norec']);
        }

        $dataStruk = $dataStruk->first();

        $data = DB::table('strukpelayanan_t as sp')
            ->JOIN('strukpelayanandetail_t as spd', 'spd.nostrukfk', '=', 'sp.norec')
            ->JOIN('ruangan_m as ru', 'ru.id', '=', 'sp.objectruanganfk')
            ->JOIN('produk_m as pr', 'pr.id', '=', 'spd.objectprodukfk')
            ->leftJOIN('detailjenisproduk_m as djp', 'djp.id', '=', 'pr.objectdetailjenisprodukfk')
            ->leftJOIN('jenisproduk_m as jp', 'jp.id', '=', 'djp.objectjenisprodukfk')
            ->leftJOIN('kelompokproduk_m as kp', 'kp.id', '=', 'jp.objectkelompokprodukfk')
            ->leftJOIN('satuanstandar_m as ss', 'ss.id', '=', 'spd.objectsatuanstandarfk')
            ->leftJOIN('asalproduk_m as ap', 'ap.id', '=', 'spd.objectasalprodukfk')
            ->select(
                'sp.nostruk',
                'spd.hargasatuan',
                'spd.qtyproduk',
                'sp.objectruanganfk',
                'ru.namaruangan',
                'spd.objectprodukfk as produkfk',
                'pr.namaproduk',
                'spd.hasilkonversi as nilaikonversi',
                'sp.objectkelompokprodukfk',
                'spd.objectsatuanstandarfk',
                'ss.satuanstandar',
                'ss.id as ssid',
                'spd.qtyprodukretur',
                'spd.satuanstandar as satuanviewfk',
                'ss.satuanstandar as ssview',
                'spd.qtyproduk as jumlah',
                'spd.hargadiscount',
                'spd.hargappn',
                'spd.hargasatuan',
                'spd.objectasalprodukfk',
                'ap.asalproduk',
                'spd.persendiscount',
                'spd.persenppn',
                'spd.keteranganlainnya',
                'spd.nobatch',
                'spd.tglkadaluarsa',
                'kp.id as kpid',
                'kp.kelompokproduk'
            )
            ->where('sp.kdprofile', $this->kdProfile);

        if (isset($request['norec']) && $request['norec'] != "" && $request['norec'] != "undefined") {
            $data = $data->where('sp.norec', '=', $request['norec']);
        }
        $data = $data->get();

        $ruangannfk = $dataStruk->objectruanganfk;

        $norecSP = $request['norec'];
        $datakirim = collect(DB::select("
            select kp.objectprodukfk,sum(kp.qtyproduk) as jml from kirimproduk_t  kp
            INNER JOIN strukkirim_t sk on sk.norec=kp.nokirimfk
            where kp.nostrukterimafk='$norecSP'
            and kp.kdprofile = $this->kdProfile and kp.statusenabled=true
            and sk.objectruanganfk=$ruangannfk
            group by kp.objectprodukfk;
            "));

        $datapp = collect(DB::select("
            select pp.produkfk,sum(pp.jumlah)  as jml from pelayananpasien_t pp
            INNER JOIN strukresep_t sr on sr.norec=pp.strukresepfk
            where pp.kdprofile = $this->kdProfile and pp.statusenabled=true
            and sr.ruanganfk=$ruangannfk
            and pp.strukterimafk='$norecSP'
            group by pp.produkfk;
            "));

        $dataOb = collect(DB::select("
            select spd.objectprodukfk,sum(spd.qtyproduk) as jml
            from strukpelayanandetail_t AS spd
            inner join strukpelayanan_t AS sp ON sp.norec = spd.nostrukfk
            where sp.kdprofile =  $this->kdProfile and sp.statusenabled=true
            and sp.objectruanganfk = $ruangannfk
            and spd.nostrukfk ='$norecSP'
            group by spd.objectprodukfk;
        "));

        $pelayananPasien = [];
        $i = 0;
        $jmlDipakai = 0;
        $data2res = [];
        foreach ($datakirim as $itemDua) {
            $data2res[] = array(
                'objectprodukfk' => $itemDua->objectprodukfk,
                'jml' => $itemDua->jml,
            );
        }
        $data1res = [];
        foreach ($datapp as $itemDua) {
            $data1res[] = array(
                'objectprodukfk' => $itemDua->produkfk,
                'jml' => $itemDua->jml,
            );
        }

        $data3res = [];
        foreach ($dataOb as $itemTiga) {
            $data3res[] = array(
                'objectprodukfk' => $itemTiga->objectprodukfk,
                'jml' => $itemTiga->jml,
            );
        }

        foreach ($data as $item) {
            $i = $i + 1;
            $jmlDipakai = 0;
            for ($j = 0; $j < count($data2res); $j++) {
                if ($item->produkfk == $data2res[$j]['objectprodukfk'] && $item->jumlah >= (float)$data2res[$j]['jml']) {
                    $jmlDipakai = $jmlDipakai + (float)$data2res[$j]['jml'];
                    $data2res[$j]['jml'] = 0;
                }
            }
            for ($j = 0; $j < count($data1res); $j++) {
                if ($item->produkfk == $data1res[$j]['objectprodukfk']  && (float)$item->jumlah >= (float)$data1res[$j]['jml']) {
                    $jmlDipakai = $jmlDipakai + (float)$data1res[$j]['jml'];
                    $data1res[$j]['jml'] = 0;
                }
            }
            for ($k = 0; $k < count($data3res); $k++) {
                if ($item->produkfk == $data3res[$k]['objectprodukfk']  && (float)$item->jumlah >= (float)$data3res[$k]['jml']) {
                    $jmlDipakai = $jmlDipakai + (float)$data3res[$k]['jml'];
                    $data3res[$k]['jml'] = 0;
                }
            }
            $pelayananPasien[] = array(
                'no' => $i,
                'hargasatuan' => $item->hargasatuan,
                'ruanganfk' => $item->objectruanganfk,
                'asalprodukfk' => $item->objectasalprodukfk,
                'asalproduk' => $item->asalproduk,
                'produkfk' => $item->produkfk,
                'namaproduk' => $item->namaproduk,
                'nilaikonversi' => $item->nilaikonversi,
                'satuanstandarfk' => $item->objectsatuanstandarfk,
                'ssid' => $item->ssid,
                'qtyprodukretur' => $item->qtyprodukretur,
                'satuan' => $item->ssview,
                'satuanviewfk' => $item->satuanviewfk,
                'satuanview' => $item->ssview,
                'jumlah' => $item->jumlah,
                'jumlahdipakai' => $jmlDipakai,
                'sisa' => $item->jumlah - $jmlDipakai,
                'hargadiskon' => $item->hargadiscount,
                'persendiscount' => $item->persendiscount,
                'nilaippn' => $item->hargappn,
                'persenppn' => $item->persenppn,
                'subtotal' => ((float)$item->hargasatuan *  (float) $item->jumlah),
                'totalall' => ((float)$item->hargasatuan *  (float) $item->jumlah) - (float)$item->hargadiscount + (float)$item->hargappn,
                'keterangan' => $item->keteranganlainnya,
                'nobatch' => $item->nobatch,
                'tglkadaluarsa' => $item->tglkadaluarsa,
                'kpid' => $item->kpid,
                'kelompokproduk' => $item->kelompokproduk,
            );
        }

        $result = array(
            'detailterima' => $dataStruk,
            'pelayananPasien' => $pelayananPasien,
            'kirim' => $datakirim,
            'pp' => $datapp,
            'ruanganfk' => $ruangannfk,
            'message' => 'as@epic',
        );

        return $this->respond($result);
    }

    public function cetakBuktiPenerimaanBarang(Request $request)
    {
        $kdProfile = $request['kdprofile'];
        $norec = $request['norec'];
        $tglAyeuna = date('d/m/Y');
        $tglAyeuna = date('Y-m-d H:i:s');
        $print = false;
        $profile = Profile::where('id', $this->kdProfile)->first();
        $data = collect(DB::select("
                select  sp.nostruk,sp.nofaktur,to_char(sp.tglstruk, 'DD-MM-YYYY') as tglstruk,sp.tglspk,
                        to_char(sp.tglfaktur, 'DD-MM-YYYY') as tglfaktur,to_char(sp.tglkontrak, 'DD-MM-YYYY') as tglkontrak,
                        to_char(sr.tglrealisasi, 'DD-MM-YYYY') as tglrealisasi, case when ap.asalproduk is null then '-' else ap.asalproduk end as asalproduk,
                        case when sp.totaldiscount is null then 0 else (sp.totaldiscount * 100) / sp.totalhargasatuan end as persendiskon,
                        case when sp.totalppn is null then 0 else (sp.totalppn * 100) / sp.totalhargasatuan end as persenppn,
                        case when rk.namarekanan is null then '-' else rk.namarekanan end as rekanan,
                        pr.id as idproduk, pr.namaproduk,
                        ss.satuanstandar, sp.totalharusdibayar,
                        (spd.hargasatuan - spd.hargadiscount + spd.hargappn) as harga, spd.qtyproduk,
                        case when ru.namaruangan is null then '-' else ru.kdruangan  ||  ' - '  ||  ru.namaruangan end as gudang,sp.keteranganambil,
                        case when sp.nokontrak is null then '-' else sp.nokontrak end as nokontrak,case when sp.nosppb is null then '-' else sp.nosppb end as nosppb,
                        sp.keteranganambil,spd.qtyproduk*(spd.hargasatuan - spd.hargadiscount + spd.hargappn) as subtotal,
                        CASE WHEN spd.hargappn IS NULL THEN 0 ELSE spd.hargappn END AS ppn,
                        CASE WHEN spd.hargadiscount IS NULL THEN 0 ELSE spd.hargadiscount END AS diskon,
                        CAST (((spd.hargasatuan- spd.hargadiscount) * spd.qtyproduk) + spd.hargappn AS FLOAT) AS total
                from strukpelayanan_t sp
                join strukpelayanandetail_t spd on spd.nostrukfk=sp.norec
                left JOIN pegawai_m pg on pg.id=sp.objectpegawaipenanggungjawabfk
                JOIN ruangan_m ru on ru.id=sp.objectruanganfk
                JOIN produk_m pr on pr.id=spd.objectprodukfk
                join asalproduk_m as ap on ap.id=spd.objectasalprodukfk
                left join rekanan_m rk on rk.id=sp.objectrekananfk
                left JOIN jeniskemasan_m jkm on jkm.id=spd.objectjeniskemasanfk
                left join satuanstandar_m ss on ss.id=spd.objectsatuanstandarfk
                left join riwayatrealisasi_t as rr on rr.penerimaanfk = sp.norec
                left join strukrealisasi_t as sr on sr.norec = rr.objectstrukrealisasifk
                where sp.norec = '$norec'
                GROUP BY sp.nostruk,sp.nofaktur,sp.tglstruk,sp.tglspk,tglfaktur,sp.tglkontrak,ap.asalproduk,rk.kdrekanan,rk.namarekanan,ru.kdruangan,
                        ru.namaruangan,sp.tglstruk,sp.keteranganambil,sp.totaldiscount,sp.totalhargasatuan,sp.totalppn,pr.id,ss.satuanstandar,sp.totalharusdibayar,
                        spd.hargasatuan,spd.hargadiscount,spd.hargappn,pr.namaproduk,spd.qtyproduk,sp.nokontrak,sp.nosppb,sr.tglrealisasi
        "))->first();

        $detail = DB::table('strukpelayanan_t as sp')
            ->leftJoin('strukpelayanandetail_t as spd', 'spd.nostrukfk', 'sp.norec')
            ->leftJoin('pegawai_m as pg', 'pg.id', 'sp.objectpegawaipenanggungjawabfk')
            ->Join('ruangan_m as ru', 'ru.id', 'sp.objectruanganfk')
            ->Join('produk_m as pr', 'pr.id', 'spd.objectprodukfk')
            ->Join('detailjenisproduk_m as djp', 'djp.id', 'pr.objectdetailjenisprodukfk')
            ->leftJoin('asalproduk_m as ap', 'ap.id', 'spd.objectasalprodukfk')
            ->leftJoin('rekanan_m as rk', 'rk.id', 'sp.objectrekananfk')
            ->leftJoin('jeniskemasan_m as jkm', 'jkm.id', 'spd.objectjeniskemasanfk')
            ->leftJoin('satuanstandar_m as ss', 'ss.id', 'spd.objectsatuanstandarfk')
            ->leftJoin('riwayatrealisasi_t as rr', 'rr.penerimaanfk', 'sp.norec')
            ->leftJoin('strukrealisasi_t as sr', 'sr.norec', 'rr.objectstrukrealisasifk')
            ->select(
                'pr.namaproduk',
                'pr.id as produkfk',
                'ss.satuanstandar',
                'spd.qtyproduk',
                'spd.hasilkonversi',
                'spd.qtyprodukretur',
                'pr.kdproduk',
                'djp.detailjenisproduk',
                'spd.hargasatuan',
                'spd.nobatch',
                'spd.tglkadaluarsa',
                'spd.hargadiscount as hargadiskon',
                'spd.hargappn',
                'spd.persendiscount',
                'spd.persenppn',
                DB::raw("CAST(((spd.qtyproduk*spd.hargasatuan)-(((spd.persendiscount*spd.hargasatuan)/100)*spd.qtyproduk))+
                                (spd.persenppn*((spd.qtyproduk*spd.hargasatuan)-(((spd.persendiscount*spd.hargasatuan)/100)*
                                spd.qtyproduk))/100) AS FLOAT) AS totalall"),
                'spd.tglkadaluarsa',
                'spd.keteranganlainnya',
                'spd.nobatch',
                DB::raw("CAST(spd.qtyproduk*spd.hargasatuan as float) as subtotal")
            )
            ->where('sp.norec', $norec)
            ->get();

        $pageWidth = 950;
        $pegawaiMegetahui = "-";
        $nippegawaiMegetahui = "NIP. -";
        if (isset($request['pegawaiMengetahui']) && $request['pegawaiMengetahui'] != "") {
            $idPegMeg = (int) $request['pegawaiMengetahui'];
            $dataPegMeng = collect(DB::select("
                select pg.namalengkap,pg.nip_pns,jb.namajabatan
                from pegawai_m as pg
                left join jabatan_m as jb on jb.id = pg.objectjabatanstrukturalfk
                where pg.id = $idPegMeg
           "))->first();

            if (!empty($dataPegMeng)) {
                $pegawaiMegetahui = $dataPegMeng->namalengkap;
                $nippegawaiMegetahui = "NIP. " . $dataPegMeng->nip_pns;
            }
        }

        $pegawaiPenerima = "-";
        $nippegawaiPenerima = "NIP. -";
        if (isset($request['pegawaiPenerima']) && $request['pegawaiPenerima'] != "") {
            $idPegMeg2 = (int) $request['pegawaiPenerima'];
            $dataPegMeng2 = collect(DB::select("
                select pg.namalengkap,pg.nip_pns,jb.namajabatan
                from pegawai_m as pg
                left join jabatan_m as jb on jb.id = pg.objectjabatanstrukturalfk
                where pg.id = $idPegMeg2
           "))->first();

            if (!empty($dataPegMeng2)) {
                $pegawaiPenerima = $dataPegMeng2->namalengkap;
                $nippegawaiPenerima = "NIP. " . $dataPegMeng2->nip_pns;
            }
        }

        $pegawaiPenyerah = "-";
        $nippegawaiPenyerah = "NIP. -";
        if (isset($request['pegawaiMeminta']) && $request['pegawaiMeminta'] != "") {
            $idPegMeg3 = (int) $request['pegawaiMeminta'];
            $dataPegMeng3 = collect(DB::select("
                select pg.namalengkap,pg.nip_pns,jb.namajabatan
                from pegawai_m as pg
                left join jabatan_m as jb on jb.id = pg.objectjabatanstrukturalfk
                where pg.id = $idPegMeg3
           "))->first();

            if (!empty($dataPegMeng3)) {
                $pegawaiPenyerah = $dataPegMeng3->namalengkap;
                $nippegawaiPenyerah = "NIP. " . $dataPegMeng3->nip_pns;
            }
        }

        $dataReport = array(
            'datas' => $data,
            'detail' => $detail,
            'tanggal' => $tglAyeuna,
            'judul' => 'BUKTI PENERIMAAN BARANG',
            'pegawaimengetahui' => $pegawaiMegetahui,
            'nipmengetahui' => $nippegawaiMegetahui,
            'pegawaipenerima' => $pegawaiPenerima,
            'nippenerima' => $nippegawaiPenerima,
            'pegawaipenyerah' => $pegawaiPenyerah,
            'nippenyerah' => $nippegawaiPenyerah
        );

        $res['pdf']  = false;

        $blade = 'report.logistik.bukti-penerimaan';
        if ($res['pdf']) {
            $pdf = App::make('dompdf.wrapper');
            $pdf->loadView(
                $blade,
                array(
                    'profile' => $profile,
                    'pageWidth' => $pageWidth,
                    'print' => $print,
                    'res' => $res,
                )
            );
            return $pdf->stream();
        }

        return view(
            $blade,
            compact('dataReport', 'profile', 'pageWidth', 'print', 'res')
        );
    }

    public function SaveReturPenerimaan(Request $request)
    {
        DB::beginTransaction();
        try {
            if ($request['struk']['norecRetur'] == '') {
                $newSRetur = new StrukRetur();
                $norecSRetur = $newSRetur->generateNewId();
                $noRetur = $this->generateCode(new StrukRetur, 'noretur', 12, 'Ret/' . $this->getDateTime()->format('ym') . '/', $this->kdProfile);
                $newSRetur->norec = $norecSRetur;
                $newSRetur->kdprofile = $this->kdProfile;
                $newSRetur->statusenabled = true;
                $newSRetur->objectkelompoktransaksifk = $this->settingFix("KelompokTransaksiReturSupplier");;
            } else {
                $newSRetur =  StrukRetur::where('norec', $request['struk']['norecRetur'])->where('kdprofile', $this->kdProfile)->first();
                StrukReturDetail::where('strukreturfk', $request['struk']['norecRetur'])->where('kdprofile', $this->kdProfile)->delete();
            }
            $newSRetur->keteranganalasan = $request['struk']['keterangan'];
            $newSRetur->keteranganlainnya = 'Retur  Penerimaan ' . ' Dari Ruangan  ' . $request['struk']['namaruangan']  . ' Dengan No Terima:  ' . $request['struk']['noterima'] . '  Ke Supplier  ' . $request['struk']['namarekanan'];
            $newSRetur->noretur = $noRetur;
            $newSRetur->objectruanganfk = $request['struk']['ruanganfk'];
            $newSRetur->objectpegawaifk = $request['struk']['pegawaimenerimafk'];
            $newSRetur->tglretur = $this->getDateTime()->format('Y-m-d H:i:s');
            $newSRetur->strukterimafk = $request['struk']['nostruk'];
            $newSRetur->save();
            $norecRetur = $newSRetur->norec;

            foreach ($request['details'] as $item) {

                $noTarimakeun =  $request['struk']['nostruk'];

                $tambah = StokProdukDetail::where('nostrukterimafk', $noTarimakeun)
                    ->where('kdprofile', $this->kdProfile)
                    ->where('objectruanganfk', $request['struk']['ruanganfk'])
                    ->where('objectprodukfk', $item['produkfk'])
                    ->first();
                //## StrukReturDetail
                $StokPD = new StrukReturDetail();
                $norecStokPD = $StokPD->generateNewId();
                $StokPD->norec = $norecStokPD;
                $StokPD->kdprofile = $this->kdProfile;
                $StokPD->statusenabled = true;
                $StokPD->objectasalprodukfk = $item['asalprodukfk'];
                $StokPD->hargadiscount = 0;
                $StokPD->harganetto1 = (float)$tambah->harganetto1;
                $StokPD->harganetto2 = (float)$tambah->harganetto2;
                $StokPD->persendiscount = 0;
                $StokPD->objectprodukfk = $item['produkfk'];
                $StokPD->qtyproduk = (float)$item['qtyprodukretur'];
                $StokPD->qtyprodukonhand = 0;
                $StokPD->qtyprodukoutext = 0;
                $StokPD->qtyprodukoutint = 0;
                $StokPD->nostrukterimafk = $noTarimakeun;
                $StokPD->strukreturfk = $norecRetur;
                $StokPD->tglkadaluarsa = $tambah->tglkadaluarsa;
                $StokPD->save();
                //PENERIMA
                $dataSaldoAwalT = DB::select(
                    DB::raw("select sum(qtyproduk) as qty from stokprodukdetail_t
                    where kdprofile = $this->kdProfile and objectruanganfk=:ruanganfk and objectprodukfk=:produkfk"),
                    array(
                        'ruanganfk' => $request['struk']['ruanganfk'],
                        'produkfk' => $item['produkfk'],
                    )
                );

                $saldoAwalPenerima = 0;
                foreach ($dataSaldoAwalT as $items) {
                    $saldoAwalPenerima = (float)$items->qty;
                }

                $kurang = StokProdukDetail::where('nostrukterimafk', $noTarimakeun)
                    ->where('kdprofile', $this->kdProfile)
                    ->where('objectruanganfk', $request['struk']['ruanganfk'])
                    ->where('objectprodukfk', $item['produkfk'])
                    ->first();

                StokProdukDetail::where('norec', $kurang->norec)
                    ->where('kdprofile', $this->kdProfile)
                    ->update([
                        'qtyproduk' => (float)$kurang->qtyproduk - (float)$item['qtyprodukretur']
                    ]);

                StrukPelayananDetail::where('nostrukfk', $request['struk']['nostruk'])
                    ->where('kdprofile', $this->kdProfile)
                    ->where('objectprodukfk', $item['produkfk'])
                    ->update([
                        'qtyproduk' => (float)$kurang->qtyproduk - (float)$item['qtyprodukretur'],
                        'qtyprodukretur' => (float)$item['qtyprodukretur']
                    ]);

                //## KartuStok
                $this->kartu_STOK(array(
                    "saldoawal" => (float)$saldoAwalPenerima,
                    "qtyin" => 0,
                    "qtyout" => (float)$item['qtyprodukretur'],
                    "saldoakhir" => (float)$saldoAwalPenerima - (float)$item['qtyprodukretur'],
                    "keterangan" => 'Retur  Penerimaan ' . ' Dari Ruangan  ' . $request['struk']['namaruangan']  . ' Dengan No Terima:  ' . $request['struk']['noterima'] . '  Ke Supplier  ' . $request['struk']['namarekanan'],
                    "produkfk" => $item['produkfk'],
                    "ruanganfk" => $request['struk']['ruanganfk'],
                    "tglinput" => date('Y-m-d H:i:s'),
                    "tglkejadian" => date('Y-m-d H:i:s'),
                    "nostrukterimafk" => $noTarimakeun,
                    "norectransaksi" => $request['struk']['nostruk'],
                    "tabletransaksi" => 'strukpelayanan_t',
                    "stokprodukdetailfk" =>  $tambah->norec,
                    "flagfk" => null,
                ));
            }

            //## Logging User
            // $newId = LoggingUser::max('id');
            // $newId = $newId + 1;
            // $logUser = new LoggingUser();
            // $logUser->id = $newId;
            // $logUser->norec = $logUser->generateNewId();
            // $logUser->kdprofile = $this->kdProfile;
            // $logUser->statusenabled = true;
            // $logUser->jenislog = 'Batal Kirim';
            // $logUser->noreff = $request['struk']['nostruk'];
            // $logUser->referensi = 'norec Struk Terima';
            // $logUser->objectloginuserfk =  $dataLogin['userData']['id'];
            // $logUser->tanggal = $this->getDateTime()->format('Y-m-d H:i:s');
            // $logUser->keterangan = 'Retur  Penerimaan ' . ' Dari Ruangan  ' . $request['struk']['namaruangan']  . ' Dengan No Terima:  ' . $request['struk']['noterima'] . '  Ke Supplier  ' . $request['struk']['namarekanan'];
            // $logUser->save();
            DB::commit();
            $result = [
                "status" => 200,
                "noretur" => $norecRetur,
                "data" => $newSRetur,
                "message" => "Data berhasil diretur",
            ];
        } catch (Exception $e) {
            DB::rollBack();
            $result = [
                "status" => 400,
                "data" => $e->getMessage(),
                "message"  => 'Something Went Wrong',
            ];
        }
        return $this->respond($result, $result['status'], $result['message']);
    }

    public function getRekanan()
    {
        $rekanan = DB::table('rekanan_m')
            ->select('id', 'namarekanan')
            ->where('kdprofile', $this->kdProfile)
            ->where('statusenabled', true)
            ->get();



        $result = ['rekanan' => $rekanan];

        return $this->respond($result);
    }

    public function getDaftarRetur(Request $request)
    {
        $dateBetween = [$request->tglAwal, $request->tglAkhir];
        $data = DB::table('strukpelayanan_t as sp')
            ->JOIN('strukpelayanandetail_t as spd', 'spd.nostrukfk', '=', 'sp.norec')
            ->join('asalproduk_m as ap', 'ap.id', 'spd.objectasalprodukfk')
            ->LEFTJOIN ('strukreturdetail_t as srd', 'srd.nostrukterimafk', '=', 'sp.norec')
            ->leftjoin('riwayatrealisasi_t as rr', 'rr.penerimaanfk', 'sp.norec')
            ->leftJOIN('rekanan_m as rkn', 'rkn.id', '=', 'sp.objectrekananfk')
            ->LEFTJOIN('pegawai_m as pg', 'pg.id', '=', 'sp.objectpegawaipenerimafk')
            ->LEFTJOIN('ruangan_m as ru', 'ru.id', '=', 'sp.objectruanganfk')
            ->LEFTJOIN('strukbuktipengeluaran_t as sbk', 'sbk.norec', '=', 'sp.nosbklastfk')
            ->join('stokprodukdetail_t AS stokpd', 'stokpd.objectstrukpelayanandetail', '=', 'spd.norec')
            ->leftJoin('kirimproduk_t AS kp', 'kp.stokprodukdetailfk', '=', 'stokpd.norec')
            ->select(
                DB::raw("
                sp.tglstruk, sp.nostruk,sp.noterima, sp.nostruk_intern,sp.keteranganlainnya,sp.namapengadaan,rkn.namarekanan,rkn.id as rekananfk,ru.id as ruanganfk,pg.namalengkap,
                pg.id as penerimafk,sp.objectruanganasalfk,sp.objectpegawaipenanggungjawabfk,sp.tglspk,sp.nokontrak,ap.asalproduk,ap.id as apid,sp.tgljatuhtempo,sp.tgldokumen,
                ru.namaruangan, sp.norec, sp.nofaktur,rr.noorderintern, sp.objectkelompokprodukfk , sp.tglfaktur,CAST(sp.totalharusdibayar AS FLOAT), sbk.nosbk,
                sp.nosppb, sp.totalppn,sp.totaldiscount,sp.totalhargasatuan, sp.noorderfk, sp.qtyproduk
            ")
            )
            ->where('sp.kdprofile', $this->kdProfile)
            ->where('sp.statusenabled', true)
            ->where('sp.objectkelompoktransaksifk', $this->kelompokTransaksi('PENERIMAAN BARANG SUPPLIER'))
            ->where('stokpd.statusenabled', true)
            ->whereNotExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('kirimproduk_t')
                    ->whereColumn('kirimproduk_t.stokprodukdetailfk', 'stokpd.norec');
            })
            ->groupBy(
                'sp.tglstruk',
                'sp.nostruk',
                'sp.noterima',
                'sp.nostruk_intern',
                'sp.keteranganlainnya',
                'sp.namapengadaan',
                'rkn.namarekanan',
                'rekananfk',
                'pg.namalengkap',
                'penerimafk',
                'sp.nokontrak',
                'sp.objectruanganasalfk',
                'sp.tglspk',
                'rr.noorderintern',
                'ru.namaruangan',
                'sp.objectkelompokprodukfk',
                'ruanganfk',
                'ap.asalproduk',
                'apid',
                'sp.norec',
                'sp.nofaktur',
                'sp.tglfaktur',
                'sp.tgljatuhtempo',
                'sp.totalharusdibayar',
                'sp.objectpegawaipenanggungjawabfk',
                'sbk.nosbk',
                'sp.nosppb',
                'sp.tgldokumen',
                'sp.noorderfk',
                'sp.qtyproduk',
                'sp.totalppn',
                'sp.totaldiscount',
                'sp.totalhargasatuan',
            );
        if (isset($request['nostruk']) && $request['nostruk'] != "" && $request['nostruk'] != "undefined") {
            $data = $data->where('sp.nostruk', $request['nostruk']);
        }
        if (isset($request['noorderintern']) && $request['noorderintern'] != "" && $request['noorderintern'] != "undefined") {
            $data = $data->where('rr.noorderintern', $request['noorderintern']);
        }
        if (isset($request['nofaktur']) && $request['nofaktur'] != "" && $request['nofaktur'] != "undefined") {
            $data = $data->where('sp.nofaktur', $request['nofaktur']);
        }
        if (isset($request['nodokumen']) && $request['nodokumen'] != "" && $request['nodokumen'] != "undefined") {
            $data = $data->where('sp.nofaktur', $request['nodokumen']);
        }
        if (isset($request['noterima']) && $request['noterima'] != "" && $request['noterima'] != "undefined") {
            $data = $data->where('sp.noterima', $request['noterima']);
        }
        if (isset($request['rekananfk']) && $request['rekananfk'] != "" && $request['rekananfk'] != "undefined") {
            $data = $data->where('rkn.id', $request['rekananfk']);
        }
        if (isset($request['tglAwal']) && $request['tglAwal'] != "" && $request['tglAwal'] != "undefined") {
            $data = $data->WhereBetween(DB::raw("CAST(sp.tglstruk as DATE)"), $dateBetween);
        }
        if (isset($request['norec']) && $request['norec'] != "" && $request['norec'] != "undefined") {
            $data = $data->where('sp.norec', $request['norec']);
        }
        $data = $data->distinct();
        $data = $data->orderBy('sp.nostruk');
        if (isset($request['offset']) && $request['offset'] != '') {
            $data = $data->offset($request['offset']);
        }
        if (isset($request['limit']) && $request['limit'] != '') {
            $data = $data->limit($request['limit']);
        }
        $data = $data->get();
        foreach ($data as $item) {
            $details = DB::select(
                  // DB::raw("select  pr.namaproduk,pr.id as produkfk,ss.satuanstandar as satuan,ss.id as satuanfk,spd.qtyproduk as jumlah,spd.hasilkonversi,spd.qtyprodukretur,spd.hargasatuan,spd.hargadiscount as hargadiskon,
                //     spd.hargappn as nilaippn,spd.persendiscount,spd.persenppn,CAST(((spd.qtyproduk*spd.hargasatuan)-(((spd.persendiscount*spd.hargasatuan)/100)spd.qtyproduk))+(spd.persenppn((spd.qtyproduk*spd.hargasatuan)-(((spd.persendiscount*spd.hargasatuan)/100)*spd.qtyproduk))/100) AS FLOAT) AS totalall,
                //     spd.tglkadaluarsa,spd.keteranganlainnya,spd.nobatch,CAST(spd.qtyproduk*spd.hargasatuan as float) as subtotal
                //     from strukpelayanandetail_t as spd
                //     left JOIN produk_m as pr on pr.id=spd.objectprodukfk
                //     left JOIN satuanstandar_m as ss on ss.id=spd.objectsatuanstandarfk
                //     where spd.kdprofile = $this->kdProfile and nostrukfk=:norec"),
                DB::raw("select
                        pr.namaproduk,
                        pr.ID AS produkfk,
                        ss.satuanstandar AS satuan,
                        ss.ID AS satuanfk,
                        spd.qtyproduk AS jumlah,
                        spd.hasilkonversi,
                        spd.hargasatuan,
                        spd.hargadiscount AS hargadiskon,
                        spd.hargappn AS nilaippn,
                        spd.persendiscount,
                        spd.persenppn,
                        CAST (
                            (
                                ( spd.qtyproduk * spd.hargasatuan ) - ( ( ( spd.persendiscount * spd.hargasatuan ) / 100 ) * spd.qtyproduk )
                                ) + (
                                spd.persenppn * (
                                    ( spd.qtyproduk * spd.hargasatuan ) - ( ( ( spd.persendiscount * spd.hargasatuan ) / 100 ) * spd.qtyproduk )
                                ) / 100
                            ) AS FLOAT
                        ) AS totalall,
                        spd.tglkadaluarsa,
                        spd.keteranganlainnya,
                        spd.nobatch,
                            COALESCE(
                                        (SELECT SUM(srd.qtyproduk)
                                        FROM strukreturdetail_t AS srd
                                        WHERE srd.nostrukterimafk = spd.nostrukfk AND srd.objectprodukfk = pr.ID),
                                        0
                                    ) AS total_qty_retur
                    FROM
                        strukpelayanandetail_t AS spd
                        LEFT JOIN strukreturdetail_t  AS srd ON srd.nostrukterimafk = spd.nostrukfk
                        LEFT JOIN produk_m AS pr ON pr.ID = spd.objectprodukfk
                        LEFT JOIN satuanstandar_m AS ss ON ss.ID = spd.objectsatuanstandarfk
                    WHERE
                       spd.kdprofile = $this->kdProfile and nostrukfk=:norec
                    GROUP BY
                        spd.nostrukfk,
                        pr.namaproduk,
                        pr.ID,
                        ss.satuanstandar,
                        ss.ID,
                        spd.qtyproduk,
                        spd.hasilkonversi,
                        spd.hargasatuan,
                        spd.hargadiscount,
                        spd.hargappn,
                        spd.persendiscount,
                        spd.persenppn,
                        spd.tglkadaluarsa,
                        spd.keteranganlainnya,
                        spd.nobatch"),
                array(
                    'norec' => $item->norec,
                )
            );
            $result[] = array(
                'tglstruk' => $item->tglstruk,
                'nostruk' => $item->nostruk,
                'noterima' => $item->noterima,
                'nofaktur' => $item->nofaktur,
                'tglfaktur' => $item->tglfaktur,
                'namarekanan' => $item->namarekanan,
                'namapengadaan' => $item->namapengadaan,
                'norec' => $item->norec,
                'asalproduk' => $item->asalproduk,
                'apid' => $item->apid,
                'namaruangan' => $item->namaruangan,
                'nobukti' => $item->nostruk_intern,
                'objectruanganasalfk' => $item->objectruanganasalfk,
                'tglspk' => $item->tglspk,
                'pegawaifkKK' => $item->objectpegawaipenanggungjawabfk,
                'rekananfk' => $item->rekananfk,
                'kelompokprodukfk' => $item->objectkelompokprodukfk,
                'ruanganfk' => $item->ruanganfk,
                'namapenerima' => $item->namalengkap,
                'penerimafk' => $item->penerimafk,
                'totalharusdibayar' => $item->totalharusdibayar,
                'totalppn' => $item->totalppn,
                'totaldiscount' => $item->totaldiscount,
                'totalhargasatuan' => $item->totalhargasatuan,
                'nosbk' => $item->nosbk,
                'nosppb' => $item->nosppb,
                'tgldokumen' => $item->tgldokumen,
                'nokontrak' => $item->nokontrak,
                'noorderintern' => $item->noorderintern,
                'tgljatuhtempo' => $item->tgljatuhtempo,
                'noorderfk' => $item->noorderfk,
                'jmlitem' => $item->qtyproduk,
                'details' => $details,
            );
        }
        if (count($data) == 0) {
            $result = [];
        }

        $result = array(
            'daftar' => $result,
            'message' => 'as@epic',
        );

        return $this->respond($result);
    }
}
