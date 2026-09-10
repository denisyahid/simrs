<?php

namespace App\Http\Controllers\Logistik;

use App\Http\Controllers\Controller;
use App\Models\Master\JenisUsulan;
use App\Models\Master\Pengendali;
use App\Models\Master\Rekanan;
use App\Models\Master\Ruangan;
use App\Models\Transaksi\MataAnggaran;
use App\Models\Transaksi\OrderPelayanan;
use App\Models\Transaksi\StrukOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SuratPerintahKerjaCtrl extends Controller
{
    public function __construct()
    {
        parent::__construct($is_encrypt = true);
    }

    public function getComboSuratPerintah()
    {
        $result['unitPengusul'] = DB::table('maploginusertoruangan_s as mlur')
                                    ->join('loginuser_s as lu','lu.id','mlur.objectloginuserfk') 
                                    ->join('ruangan_m as ru','ru.id','mlur.objectruanganfk') 
                                    ->select('ru.id','ru.namaruangan')
                                    ->where('lu.kdprofile',$this->kdProfile)
                                    ->where('mlur.kdprofile',$this->kdProfile)
                                    ->where('mlur.statusenabled',true)
                                    ->where('lu.id',$this->getUserId())
                                    ->get();

        $result['ruanganAll'] = Ruangan::mine()->get();
        $result['jenisUsulan'] = JenisUsulan::mine()->get();
        $result['jenisPengendali'] = Pengendali::mine()->get();
        $result['mataAnggaran'] = MataAnggaran::mine()->get();
        $result['rekanan'] = Rekanan::mine()->get();

        return $this->respond($result);
    }

    public function getDataProdukLogistik(Request $request)
    {

        $idProfile = $this->kdProfile;
        $req = $request->all();
        $dataProduk = DB::table('produk_m as pr')
        ->JOIN('detailjenisproduk_m as djp', 'djp.id', '=', 'pr.objectdetailjenisprodukfk')
        ->JOIN('jenisproduk_m as jp', 'jp.id', '=', 'djp.objectjenisprodukfk')
        ->leftJOIN('satuanstandar_m as ss', 'ss.id', '=', 'pr.objectsatuanstandarfk')
        ->select('pr.id', 'pr.namaproduk', 'ss.id as ssid', 'ss.satuanstandar', 'pr.kdproduk')
        ->where('pr.statusenabled', true)
            ->where('pr.kdprofile', $idProfile)
            ->where('pr.namaproduk', '!=', '')
            ->orderBy('pr.namaproduk');

        if (isset($req['namaproduk']) && $req['namaproduk'] != "undefined") {
            $dataProduk = $dataProduk->where('pr.namaproduk', 'ILIKE', '%' . $req['namaproduk'] . '%');
        };
        if (isset($req['id']) && $req['id'] != "undefined") {
            $dataProduk = $dataProduk->where('pr.id', 'ILIKE', '%' . $req['id'] . '%');
        };

        $dataProduk = $dataProduk->take(20);
        $dataProduk = $dataProduk->get();

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
        ->where('ks.kdprofile', $idProfile)
            ->where('ks.statusenabled', true)
            ->get();


        $dataProdukResult = [];
        foreach ($dataProduk as $item) {
            $satuanKonversi = [];
            foreach ($dataKonversiProduk  as $item2) {
                if ($item->id == $item2->objekprodukfk) {
                    $satuanKonversi[] = array(
                        'ssid' =>   $item2->satuanstandar_tujuan,
                        'satuanstandar' =>   $item2->satuanstandar2,
                        'nilaikonversi' =>   $item2->nilaikonversi,
                    );
                }
            }

            $dataProdukResult[] = array(
                'id' =>   $item->id,
                'namaproduk' =>   $item->namaproduk,
                'ssid' =>   $item->ssid,
                'satuanstandar' =>   $item->satuanstandar,
                'konversisatuan' => $satuanKonversi,
                'kdproduk' => $item->kdproduk,
            );
        }

        return $this->respond($dataProdukResult);
    }

    public function getDaftarSPK(Request $request)
    {

        $dateRange = [$request->tglAwal,$request->tglAkhir];
        $idProfile = $this->kdProfile;
        $dataLogin = $request->all();
        $dataRuangan = DB::table('maploginusertoruangan_s as mlu')
        ->JOIN('ruangan_m as ru', 'ru.id', '=', 'mlu.objectruanganfk')
        ->select('ru.id')
        ->where('mlu.kdprofile', $idProfile)
            ->where('mlu.objectloginuserfk', $this->getUserId())
            ->get();
        $strRuangan = [];
        foreach ($dataRuangan as $epic) {
            $strRuangan[] = $epic->id;
        }

        $data = DB::table('strukorder_t as sp')
        ->JOIN('orderpelayanan_t as op', 'op.nokontrakspk', '=', 'sp.nokontrakspk')
        ->LEFTJOIN('strukpelayanan_t as spn', 'spn.noorderfk', '=', 'sp.norec')
        ->LEFTJOIN('pegawai_m as pg', 'pg.id', '=', 'sp.objectpegawaiorderfk')
        ->LEFTJOIN('pegawai_m as pg2', 'pg2.id', '=', 'sp.objectpetugasfk')
        ->LEFTJOIN('ruangan_m as ru', 'ru.id', '=', 'sp.objectruanganfk')
        ->LEFTJOIN('ruangan_m as ru2', 'ru2.id', '=', 'sp.objectruangantujuanfk')
        ->LEFTJOIN('rekanan_m as rk', 'rk.id', '=', 'sp.objectrekananfk')
        ->select(
            'sp.norec',
            'sp.tglorder',
            'sp.noorder',
            'pg.namalengkap as penanggungjawab',
            'pg2.namalengkap as mengetahui',
            'sp.tglvalidasi as tglkebutuhan',
            'sp.alamattempattujuan',
            'sp.keteranganlainnya',
            'sp.tglvalidasi',
            'sp.noorderintern',
            'sp.keterangankeperluan',
            'sp.keteranganorder',
            'ru.namaruangan as ruangan',
            'ru.id as ruid',
            'ru2.namaruangan as ruangantujuan',
            'ru2.id as ruidtujuan',
            'sp.qtyproduk',
            'sp.totalhargasatuan',
            'sp.status',
            'pg2.nippns',
            'op.nokontrakspk',
            'sp.tglkontrak',
            'sp.objectrekananfk',
            'rk.namarekanan',
            'spn.norec as norecpenerimaan'
        )
            ->where('sp.kdprofile', $idProfile)
            ->whereBetween(DB::raw("CAST(sp.tglorder as date)"),$dateRange)
            ->whereNotNull('sp.nokontrakspk');

        if (isset($request['noorder']) && $request['noorder'] != "" && $request['noorder'] != "undefined") {
            $data = $data->where('sp.noorder', 'ILIKE', '%' . $request['noorder']);
        }
        if (isset($request['noKontrak']) && $request['noKontrak'] != "" && $request['noKontrak'] != "undefined") {
            $data = $data->where('sp.nokontrakspk', 'ILIKE', '%' . $request['noKontrak'] . '%');
        }
        if (isset($request['keterangan']) && $request['keterangan'] != "" && $request['keterangan'] != "undefined") {
            $data = $data->where('sp.keteranganorder', 'ILIKE', '%' . $request['keterangan']);
        }
        if (isset($request['produkfk']) && $request['produkfk'] != "" && $request['produkfk'] != "undefined") {
            $data = $data->where('op.objectprodukfk', '=', $request['produkfk']);
        }

        $data = $data->distinct();
        $data = $data->whereNotNull('op.noorderfk');
        $data = $data->where('sp.statusenabled', true);
        $data = $data->where('sp.objectkelompoktransaksifk', $this->kelompokTransaksi('SURAT PERINTAH KERJA'));
        $data = $data->orderBy('sp.noorder');
        $data = $data->get();

        $detailProduk = DB::table('orderpelayanan_t as spd')
                         ->leftJoin('produk_m as pr','pr.id', 'spd.objectprodukfk')
                         ->leftJoin('satuanstandar_m as ss','ss.id','spd.objectsatuanstandarfk')
                         ->selectRaw("pr.namaproduk,ss.satuanstandar,spd.tglpelayanan,spd.qtyproduk,spd.qtyterimalast,spd.hargasatuan,spd.hargadiscount,spd.hargappn,
                            (spd.qtyproduk*spd.hargasatuan + (spd.hargappn-hargadiscount)) as total,spd.strukorderfk,
                            (spd.qtyprodukkonfirmasi*(spd.hargasatuanquo+spd.hargappn-hargadiscountquo)) as totalkonfirmasi,
                            spd.tglpelayananakhir as tglkebutuhan,spd.deskripsiprodukquo as spesifikasi,pr.id as prid,
                            spd.hargasatuanquo,spd.qtyprodukkonfirmasi")
                        ->where('spd.kdprofile',$this->kdProfile)
                        ->where('spd.statusenabled',true)
                        ->whereBetween(DB::raw("CAST(spd.tglpelayanan as date)"),$dateRange);
                        if (isset($request['produk']) && $request['produk'] != "" && $request['produk'] != "undefined") {
                            $detailProduk = $detailProduk->where('pr.id', $request['produk']);
                        }
        $detailProduk = $detailProduk->get();

        $results = array();
        foreach ($data as $item) {
            $details = [];
            foreach ($detailProduk as $produk) {
                if ($produk->strukorderfk == $item->norec) {
                      $details[] = $produk;
                }
            }
            $results[] = array(
                'tglorder' => $item->tglorder,
                'noorder' => $item->noorder,
                'norec' => $item->norec,
                'penanggungjawab' => $item->penanggungjawab,
                'keterangan' => $item->keteranganorder,
                'koordinator' => $item->keteranganlainnya,
                'tglkebutuhan' => $item->tglkebutuhan,
                'tglusulan' => $item->tglorder,
                'nousulan' => $item->noorderintern,
                'namapengadaan' => $item->keterangankeperluan,
                'mengetahui' => $item->mengetahui,
                'ruangan' => $item->ruangan,
                'ruangantujuan' => $item->ruangantujuan,
                'totalhargasatuan' => $item->totalhargasatuan,
                'status' => $item->status,
                'nospk' => $item->nokontrakspk,
                'tglkontrak' => $item->tglkontrak,
                'supplier' => $item->namarekanan,
                'rekananfk' => $item->objectrekananfk,
                'jmlitem' => $item->qtyproduk,
                'norecpenerimaan' => $item->norecpenerimaan,
                'details' => $details,
            );
        }

        return $this->respond($results);
    }

    public function SaveSPK(Request $request)
    {
        $idProfile = $this->kdProfile;
        DB::beginTransaction();
        try {
            if ($request['strukorder']['norec'] == '') {

                //CARI KODE USULAN BERDASARKAN RUANGAN PENG-USUL
                $dataSO = new StrukOrder();
                $dataSO->norec = $dataSO->generateNewId();
                $dataSO->kdprofile = $idProfile;
                $dataSO->statusenabled = true;

            } else {
                $dataSO = StrukOrder::where('norec', $request['strukorder']['norec'])->first();
                OrderPelayanan::where('strukorderfk', $request['strukorder']['norec'])->delete();
            }
            $dataSO->nokontrakspk = $request['strukorder']['nokontrakspk'];
            $dataSO->noorder = $request['strukorder']['nokontrakspk'];
            $dataSO->isdelivered = 0;
            $dataSO->objectkelompoktransaksifk = 90;
            $dataSO->nokontrak = $request['strukorder']['nokontrakspk'];
            $dataSO->keteranganorder = $request['strukorder']['keteranganorder'];
            $dataSO->qtyjenisproduk = $request['strukorder']['qtyjenisproduk'];
            $dataSO->qtyproduk = $request['strukorder']['qtyjenisproduk'];
            $dataSO->tglorder = $request['strukorder']['tglUsulan'];
            $dataSO->tglkontrak = $request['strukorder']['tglkontrak'];
            $dataSO->tglvalidasi = $request['strukorder']['tglDibutuhkan'];
            $dataSO->keteranganlainnya = $request['strukorder']['koordinator'];
            $dataSO->noorderintern = $request['strukorder']['nousulan'];
            $dataSO->objectruanganfk = $request['strukorder']['ruanganfkPengusul'];
            $dataSO->objectruangantujuanfk = $request['strukorder']['ruanganfkTujuan'];
            $dataSO->objectpegawaiorderfk = $request['strukorder']['penanggungjawabfk'];
            $dataSO->objectpetugasfk = $request['strukorder']['mengetahuifk'];
            $dataSO->objectpegawaispkfk = $request['strukorder']['objectpegawaispkfk'];
            $dataSO->objectrekananfk = $request['strukorder']['rekananfk'];
            $dataSO->objectmataanggaranfk = $request['strukorder']['objectmataanggaranfk'];
            $dataSO->statusorder = 0;
            $dataSO->totalbeamaterai = 0;
            $dataSO->totalbiayakirim =  $request['strukorder']['biayakirim'];
            $dataSO->jenispengendalifk =  $request['strukorder']['jenispengendalifk'];
            $dataSO->objectpegawaipengendalifk =  $request['strukorder']['pegawaipengendalifk'];
            $dataSO->totalbiayatambahan = 0;
            $dataSO->totaldiscount = 0;
            $dataSO->totalhargasatuan = 0;
            $dataSO->totalharusdibayar = 0;
            $dataSO->totalpph = 0;
            $dataSO->totalppn = 0;
            $dataSO->save();

            $SO = array(
                "norec"  => $dataSO->norec,
                "nospk" => $dataSO->nokontrakspk,
                "keltransaksi" => $dataSO->objectkelompoktransaksifk,
            );

            foreach ($request['details'] as $item) {

                $dataOP = new OrderPelayanan();
                $dataOP->norec = $dataOP->generateNewId();
                $dataOP->kdprofile = $idProfile;
                $dataOP->statusenabled = true;
                $dataOP->hasilkonversi = $item['nilaikonversi'];
                $dataOP->iscito = 0;
                $dataOP->noorderfk = $SO['norec'];
                $dataOP->nokontrakspk = $request['strukorder']['nokontrakspk'];
                $dataOP->objectprodukfk = $item['produkfk'];
                $dataOP->qtyproduk = $item['jumlah'];
                $dataOP->qtyprodukretur = 0;
                $dataOP->objectsatuanstandarfk = $item['satuanstandarfk'];
                $dataOP->strukorderfk = $SO['norec'];
                $dataOP->tglkontrak = $request['strukorder']['tglkontrak'];
                $dataOP->objectrekananfk = $request['strukorder']['rekananfk'];
                $dataOP->tglpelayanan = $request['strukorder']['tglUsulan'];
                $dataOP->hargasatuan = $item['hargasatuan'];
                $dataOP->hargadiscount = $item['hargadiskon'];
                $dataOP->hargappn = $item['nilaippn'];
                $dataOP->persenppn = $item['persenppn'];
                $dataOP->persendiscount = $item['persendiscount'];
                $dataOP->deskripsiprodukquo = $item['spesifikasi'];
                $dataOP->tglpelayananakhir = $item['tglkebutuhan'];
                $dataOP->save();
            }

            DB::commit();
            $result = array(
                "status" => 201,
                "message" => 'Simpan Berhasil',
                "nokirim" => $dataSO->norec,
                "as" => 'ea@epic',
            );

        } catch (\Exception $e) {
            DB::rollBack();
            $result = array(
                "status" => 400,
                "message"  => 'Simpan Gagal',
                "data" => $e->getMessage(),
                "as" => 'ea@epic',
            );
        }

        return $this->respond($result,$result['status'],$result['message']);

    }

    public function getDetailDataSPk(Request $request)
    {
        $idProfile = $this->kdProfile;
        $dataReq = $request->all();
        $dataStruk = DB::table('strukorder_t as sp')
        ->leftJoin('mataanggaran_m as ma','ma.id', 'sp.objectmataanggaranfk')
        ->LEFTJOIN('riwayatrealisasi_t as rr', 'rr.objectstrukfk', '=', 'sp.norec')
        ->LEFTJOIN('riwayatrealisasi_t as rr2', 'rr2.kontrakfk', '=', 'sp.norec')
        ->LEFTJOIN('strukrealisasi_t as sr', 'sr.norec', '=', 'rr.objectstrukrealisasifk')
        ->LEFTJOIN('strukrealisasi_t as sr2', 'sr2.norec', '=', 'rr2.objectstrukrealisasifk')
        ->LEFTJOIN('pegawai_m as pg', 'pg.id', '=', 'sp.objectpegawaiorderfk')
        ->LEFTJOIN('pegawai_m as pg2', 'pg2.id', '=', 'sp.objectpetugasfk')
        ->LEFTJOIN('pegawai_m as pg3', 'pg3.id', '=', 'sp.objectpegawaispkfk')
        ->LEFTJOIN('pegawai_m as pg4', 'pg4.id', '=', 'sp.objectpegawaipengendalifk')
        ->LEFTJOIN('rekanan_m as rkn', 'rkn.id', '=', 'sp.objectrekananfk')
        ->LEFTJOIN('ruangan_m as ru', 'ru.id', '=', 'sp.objectruanganfk')
        ->LEFTJOIN('ruangan_m as ru1', 'ru1.id', '=', 'sp.objectruangantujuanfk')
        ->LEFTJOIN('jenisusulan_m as ju', 'ju.jenisusulan', '=', 'sp.keteranganlainnya')
        ->LEFTJOIN('pengendali_m as jp', 'jp.id', '=', 'sp.jenispengendalifk')
        ->select(
            'sp.norec',
            'sp.objectpegawaipengendalifk',
            'pg4.namalengkap as pegawaipengendali',
            'sp.jenispengendalifk',
            'jp.pengendali',
            'sp.tglorder',
            'sp.noorder',
            'pg.namalengkap',
            'pg.id as pgid',
            'pg2.id as pegawaimengetahuiid',
            'pg2.namalengkap as pegawaimengetahui',
            'pg2.nippns',
            'sp.alamat',
            'sp.alamattempattujuan',
            'sp.keteranganlainnya',
            'sp.tglvalidasi',
            'sp.noorderintern',
            'sp.keterangankeperluan',
            'sp.nokontrakspk',
            'sp.objectmataanggaranfk',
            'sp.noorderrfq',
            'sp.keteranganorder',
            'rkn.namarekanan',
            'rkn.id as rknid',
            'sp.namarekanansales',
            'sp.totalhargasatuan',
            'sr.norealisasi',
            'sr.norec as norecrealisasi',
            'ma.namamataanggaran',
            'rr.norec as norecrr',
            'rr.objectkelompoktransaksifk as keltransaksi',
            'sp.objectsrukverifikasifk',
            'rr.objectstrukfk',
            'rr2.objectstrukrealisasifk as norecrealisasikontrak',
            'rr2.norec as rrnoreckontrak',
            'sp.tglhps',
            'sp.noorderhps',
            'ru.id as idunitpengusul',
            'ru.namaruangan as unitpengusul',
            'ru1.id as idunittujuan',
            'ru1.namaruangan as unittujuan',
            'sp.nokontrak as kontrak',
            'pg3.id as idpegawaispk',
            'pg3.namalengkap as pegawaispk',
            'sp.totalbiayakirim',
            'sp.tglkontrak',
            'ju.id as jenisusulanid'
        )
            ->where('sp.kdprofile', $idProfile);

        if (isset($request['norec']) && $request['norec'] != "" && $request['norec'] != "undefined") {
            $dataStruk = $dataStruk->where('sp.norec', '=', $request['norec']);
        }
        $dataStruk = $dataStruk->first();
        $detail = array(
            'tglorder' => $dataStruk->tglorder,
            'noorder' => $dataStruk->noorder,
            'jenispengendalifk' => $dataStruk->jenispengendalifk,
            'pengendali' => $dataStruk->pengendali,
            'objectpegawaipengendalifk' => $dataStruk->objectpegawaipengendalifk,
            'pegawaipengendali' => $dataStruk->pegawaipengendali,
            'norec' => $dataStruk->norec,
            'penanggungjawabid' => $dataStruk->pgid,
            'penanggungjawab' => $dataStruk->namalengkap,
            'pegawaimengetahuiid' => $dataStruk->pegawaimengetahuiid,
            'nippns' => $dataStruk->nippns,
            'pegawaimengetahui' => $dataStruk->pegawaimengetahui,
            'keterangan' => $dataStruk->keteranganorder,
            'keteranganlainnya' => $dataStruk->keteranganlainnya,
            'jenisusulanid' => $dataStruk->jenisusulanid,
            'alamat' => $dataStruk->alamat,
            'telp' => $dataStruk->alamattempattujuan,
            'koordinator' => $dataStruk->keteranganlainnya,
            'tglusulan' => $dataStruk->tglvalidasi,
            'nousulan' => $dataStruk->noorderintern,
            'namapengadaan' => $dataStruk->keterangankeperluan,
            'nokontrak' => $dataStruk->nokontrakspk,
            'tahunusulan' => $dataStruk->noorderrfq,
            'rekananid' => $dataStruk->rknid,
            'namarekanan' => $dataStruk->namarekanan,
            'totalhargasatuan' => $dataStruk->totalhargasatuan,
            'norealisasi' => $dataStruk->norealisasi,
            'norecrealisasi' => $dataStruk->norecrealisasi,
            'mataanggaran' => $dataStruk->namamataanggaran,
            'mataanggaranid' => $dataStruk->objectmataanggaranfk,
            'norecrr' => $dataStruk->norecrr,
            'keltransaksi' => $dataStruk->keltransaksi,
            'objectsrukverifikasifk' => $dataStruk->objectsrukverifikasifk,
            'objectstrukfk' => $dataStruk->objectstrukfk,
            'norecrealisasikontrak' => $dataStruk->norecrealisasikontrak,
            'rrnoreckontrak' => $dataStruk->rrnoreckontrak,
            'tglhps' => $dataStruk->tglhps,
            'noorderhps' => $dataStruk->noorderhps,
            'idunitpengusul' => $dataStruk->idunitpengusul,
            'unitpengusul' => $dataStruk->unitpengusul,
            'idunittujuan' => $dataStruk->idunittujuan,
            'unittujuan' => $dataStruk->unittujuan,
            'kontrak' => $dataStruk->kontrak,
            'idpegawaispk' => $dataStruk->idpegawaispk,
            'pegawaispk' => $dataStruk->pegawaispk,
            'totalbiayakirim' => $dataStruk->totalbiayakirim,
            'tglkontrak' => $dataStruk->tglkontrak,
        );

        $i = 0;
        $dataStok = $details = DB::select(
            DB::raw("select spd.norec as norec_op, pr.id as produkfk,pr.namaproduk,spd.objectrekananfk,rek.namarekanan,
                    ss.satuanstandar,ss.id as ssid,spd.qtyproduk,spd.qtyterimalast,spd.hargasatuan,spd.hargappn,
					spd.hargadiscount,spd.qtyprodukkonfirmasi,spd.deskripsiprodukquo,spd.hargasatuanquo,spd.hargappnquo,
					spd.hargadiscountquo,sb.name as statusbarang,spd.persenppn,persendiscount,
                    (spd.qtyproduk*spd.hargasatuan+(spd.hargappn-spd.hargadiscount)) as total,
                    (spd.qtyprodukkonfirmasi*(spd.hargasatuanquo+spd.hargappnquo-spd.hargadiscountquo)) as totalkonfirmasiss,
                    spd.hasilkonversi,spd.noorderfk,spd.objectasalprodukfk,ap.id as apid,ap.asalproduk,
                    spd.tglpelayananakhir as tglkebutuhan,spd.qtyterimalast
                    from orderpelayanan_t as spd 
                    left JOIN produk_m as pr on pr.id=spd.objectprodukfk
                    left JOIN satuanstandar_m as ss on ss.id=spd.objectsatuanstandarfk
                    left JOIN asalproduk_m as ap on ap.id=spd.objectasalprodukfk
                    left JOIN rekanan_m as rek on rek.id=spd.objectrekananfk
                    left JOIN status_barang_m as sb on sb.id = spd.objectstatusbarang
                    where spd.kdprofile = $idProfile and spd.noorderfk=:norec"),
            array(
                'norec' => $request['norec'],
            )
        );
        
        $jmlstok = 0;
        $details = [];
        foreach ($dataStok as $item) {
            $i = $i + 1;
            if ($item->qtyterimalast == null) {
                $qtyterima = 0;
            } else {
                $qtyterima = (float)$item->qtyterimalast;
            }
            if ((float)$item->qtyproduk != $qtyterima || $qtyterima == 0) {
                $details[] = array(
                    'no' => $i,
                    'produkfk' => $item->produkfk,
                    'norec_op' => $item->norec_op,
                    'namaproduk' => $item->namaproduk,
                    'namarekanan' => $item->namarekanan,
                    'rekananfk' => $item->objectrekananfk,
                    'nilaikonversi' => $item->hasilkonversi,
                    'satuanstandarfk' => $item->ssid,
                    'satuan' => $item->satuanstandar,
                    'satuanviewfk' => $item->ssid,
                    'satuanview' => $item->satuanstandar,
                    'spesifikasi' => $item->deskripsiprodukquo,
                    'jmlstok' => $jmlstok,
                    'jumlahspk' => (float)$item->qtyproduk,
                    'jumlahterima' => $qtyterima,
                    'jumlah' => (float)$item->qtyproduk - $qtyterima,
                    'persenppn' => (float)$item->persenppn,
                    'persendiscount' => (float)$item->persendiscount,
                    'hargasatuan' => $item->hargasatuan,
                    'hargasatuanquo' => $item->hargasatuanquo,
                    'hargappnquo' => $item->hargappnquo,
                    'hargadiscountquo' => $item->hargadiscountquo,
                    'qtyprodukkonfirmasi' => $item->qtyprodukkonfirmasi,
                    'qtyterima' => $item->qtyterimalast,
                    'hargasatuankonfirmasi' => $item->hargasatuanquo,
                    //                    'totalkonfirmasi' => $item->totalkonfirmasi,
                    'totalkonfirmasiss' => $item->totalkonfirmasiss,
                    'hargadiskon' => $item->hargadiscount,
                    'nilaippn' => $item->hargappn,
                    'subtotal' => $item->total,
                    'ruanganfk' => 50,
                    'asalprodukfk' => $item->apid,
                    'asalproduk' => $item->asalproduk,
                    'keterangan' => '',
                    'nobatch' => '',
                    'statusbarang' => $item->statusbarang,
                    'tglkadaluarsa' => null,
                    'tglkebutuhan' => $item->tglkebutuhan,
                );
            }
        }

        $result = array(
            'detail' => $detail,
            'details' => $details,
            'datalogin' => $dataReq,
            'message' => 'as@epic',
        );

        return $this->respond($result);
    }

    public function DeleteSPK(Request $request)
    {

        DB::beginTransaction();
        try {
            StrukOrder::where('norec', $request['norec_so'])->where('kdprofile', $this->kdProfile)->update(['statusenabled' => 'f',]);
            OrderPelayanan::where('noorderfk', $request['norec_so'])->where('kdprofile', $this->kdProfile)->update(['statusenabled' => 'f',]);

            DB::commit();
            $result = array(
                "status" => 201,
                "message" => 'Hapus Data Berhasil',
                "as" => 'ea@epic',
            );

        } catch (\Exception $e) {
            DB::rollBack();
            $result = array(
                "status" => 400,
                "message"  => "Gagal Hapus Data",
                "data" => $e->getMessage(),
            );
        }

        return $this->respond($result,$result['status'],$result['message']);
    }
}
