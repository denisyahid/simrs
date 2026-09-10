<?php

namespace App\Http\Controllers\Logistik;

use App\Http\Controllers\Controller;
use App\Models\Master\Produk;
use App\Models\Master\Ruangan;
use App\Models\Transaksi\KirimProduk;
use App\Models\Transaksi\OrderPelayanan;
use App\Models\Transaksi\StokProdukDetail;
use App\Models\Transaksi\StrukKirim;
use App\Models\Transaksi\StrukOrder;
use App\Traits\Valet;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransferBarangCtrl extends Controller
{
    use Valet;
    public function __construct()
    {
        parent::__construct($is_encrypt = true);
    }

    public function getComboLogistik(Request $request)
    {
        // $kdProfile = $this->getDataKdProfile($request);
        $idProfile = (int)$this->kdProfile;
        $dataLogin = $request->all();
        $dataPegawaiUser = DB::select(
            DB::raw("select pg.id,pg.namalengkap,lu.objectkelompokuserfk
                from loginuser_s as lu
                INNER JOIN pegawai_m as pg on lu.objectpegawaifk=pg.id
                where lu.kdprofile = $idProfile and lu.id=:idLoginUser"),
            array(
                'idLoginUser' => $dataLogin['userData']['id'],
            )
        );

        // return $this->respond($dataPegawaiUser);

        $getPasswordStokOpname = $this->settingFix('passwordso', $idProfile);
        $getPasswordAdjusment = $this->settingFix('PasswordAdjusment', $idProfile);
        $data = DB::table('loginuser_s as lu')
            ->JOIN('pegawai_m as pg', 'pg.id', '=', 'lu.objectpegawaifk')
            ->select('pg.id', 'pg.namalengkap')
            ->where('lu.kdprofile', $idProfile)
            ->where('lu.id', $request['userData']['id'])
            ->get();
        $dataRuangan = DB::select(
            DB::raw("
            SELECT ru.id,ru.namaruangan
            FROM maploginusertoruangan_s AS mlur
            INNER JOIN loginuser_s lu on lu.id = mlur.objectloginuserfk
            INNER JOIN ruangan_m ru on ru.id = mlur.objectruanganfk
            where lu.kdprofile = $idProfile
            and ru.statusenabled = true
            and lu.id=:idLoginUser
            GROUP BY ru.id,ru.namaruangan"),
            array(
                'idLoginUser' => $request['userData']['id'],
            )
        );

        $dataSumberDana = DB::table('asalproduk_m as lu')
            ->select('lu.id', 'lu.asalproduk as asalProduk')
            ->where('lu.statusenabled', true)
            ->get();

        $dataRuanganAll = DB::table('ruangan_m as lu')
            ->select('lu.id', 'lu.namaruangan')
            ->where('lu.statusenabled', true)
            ->where('lu.kdprofile', $idProfile)
            ->get();

        $dataJenisProduk = DB::table('jenisproduk_m as lu')
            ->select('lu.id', 'lu.jenisproduk as jenisProduk')
            ->get();

        $dataKelompokProduk = DB::table('kelompokproduk_m as jk')
            ->select('jk.id', 'jk.kelompokproduk')
            ->where('jk.kdprofile', $idProfile)
            ->where('jk.statusenabled', true)
            ->get();

        $dataKelompokProduk2 = DB::table('kelompokproduk_m as jk')
            ->select('jk.id', 'jk.kelompokproduk as kelompokProduk')
            ->where('jk.kdprofile', $idProfile)
            ->where('jk.statusenabled', true)
            ->get();

        $jenisProduk =  DB::table('jenisproduk_m as jp')
            ->select('jp.id', 'jp.jenisproduk')
            ->where('jp.kdprofile', $idProfile)
            ->where('jp.statusenabled', true)
            ->get();

        $detailJenis =  DB::table('detailjenisproduk_m as djp')
            ->select('djp.id', 'djp.detailjenisproduk', 'objectjenisprodukfk')
            ->where('djp.kdprofile', $idProfile)
            ->where('djp.statusenabled', true)
            ->get();

        $dataJenisUsulan = DB::table('jenisusulan_m as jk')
            ->select('jk.id', 'jk.jenisusulan')
            ->where('jk.statusenabled', true)
            ->get();

        $dataAnggaran = DB::table('mataanggaran_m as ma')
            ->select('ma.id', 'ma.namamataanggaran')
            ->where('ma.kdprofile', $idProfile)
            ->where('ma.statusenabled', true)
            ->orderBy('ma.namamataanggaran')
            ->get();

        $dataAnggaranT = DB::table('mataanggaran_t as ma')
            ->select('ma.norec', 'ma.mataanggaran', 'ma.saldoawalblu', 'ma.saldoawalrm')
            ->where('ma.statusenabled', true)
            ->where('ma.kdprofile', $idProfile)
            ->orderBy('ma.mataanggaran')
            ->get();

        $dataJabatan = DB::table('jabatan_m as kp')
            ->select('kp.id', 'kp.namajabatan')
            ->where('kp.statusenabled', true)
            ->orderBy('kp.namajabatan')
            ->get();

        $idJabatanPenglolaUrusan = $this->settingDataFixed('IdJabatanPengelolaUrusan', $idProfile);
        $idJenisJabatanKaInstalasi = $this->settingDataFixed('IdJabatanKepalaInstalasi', $idProfile);
        $JabatanPengelolaUrusan = DB::select(DB::raw("select id,jenisjabatan from jenisjabatan_m where id = '$idJabatanPenglolaUrusan'"));
        $JenisJabatanKaInstalasi = DB::select(DB::raw("select id,jenisjabatan from jenisjabatan_m where id = '$idJenisJabatanKaInstalasi'"));
        $bulanromawi =  $this->KonDecRomawi($this->getDateTime()->format('m'));
        $prefix = $this->KonDecRomawi($this->getDateTime()->format('m')) . '/' . $this->getDateTime()->format('y');
        $resultr = StrukOrder::where('noorder', 'like', '%' . $prefix)->max('noorder');
        $subPrefix = str_replace($prefix, '', $resultr);
        $noOrder = (str_pad((int)$subPrefix + 1, 3, "0", STR_PAD_LEFT)) . '/' . $prefix;

        foreach ($jenisProduk as $item) {
            $detail = [];
            foreach ($detailJenis as $item2) {
                if ($item->id == $item2->objectjenisprodukfk) {
                    $detail[] = array(
                        'id' => $item2->id,
                        'detailjenisproduk' => $item2->detailjenisproduk,
                    );
                }
            }
            $dataJenisProduk[] = array(
                'id' => $item->id,
                'jenisproduk' => $item->jenisproduk,
                'detailjenisproduk' => $detail,
            );
        }

        $dataProduk = DB::table('produk_m as pr')
            ->JOIN('detailjenisproduk_m as djp', 'djp.id', '=', 'pr.objectdetailjenisprodukfk')
            ->JOIN('jenisproduk_m as jp', 'jp.id', '=', 'djp.objectjenisprodukfk')
            ->leftJOIN('satuanstandar_m as ss', 'ss.id', '=', 'pr.objectsatuanstandarfk')
            ->JOIN('stokprodukdetail_t as spd', 'spd.objectprodukfk', '=', 'pr.id')
            ->select('pr.id', 'pr.kdproduk as kdsirs', 'pr.namaproduk', 'ss.id as ssid', 'ss.satuanstandar')
            ->where('pr.statusenabled', true)
            ->where('pr.kdprofile', $idProfile)
            //            ->where('jp.id',97)
            ->where('spd.qtyproduk', '>', 0)
            ->groupBy('pr.id', 'pr.kdproduk', 'pr.namaproduk', 'ss.id', 'ss.satuanstandar')
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
                'kdsirs' => $item->kdsirs,
                'kdproduk' => $item->kdsirs,
                'ssid' =>   $item->ssid,
                'satuanstandar' =>   $item->satuanstandar,
                'konversisatuan' => $satuanKonversi,
            );
        }

        $dataTipeProduk = DB::table('typeproduk_m as tp')
            ->where('tp.statusenabled', true)
            ->orderBy('tp.typeproduk')
            ->get();

        $dataSatuan = DB::table('satuanstandar_m as ss')
            ->where('ss.statusenabled', true)
            ->orderBy('ss.satuanstandar')
            ->get();

        $dataJenisProduk = DB::table('jenisproduk_m as jp')
            ->where('jp.statusenabled', true)
            ->orderBy('jp.jenisproduk')
            ->get();

        $dataDetailJenisProduk = DB::table('detailjenisproduk_m as djp')
            ->where('djp.statusenabled', true)
            ->orderBy('djp.detailjenisproduk')
            ->get();

        $dataKelompokProduk = DB::table('kelompokproduk_m as kp')
            ->where('kp.statusenabled', true)
            ->where('kp.kdprofile', $idProfile)
            ->orderBy('kp.kelompokproduk')
            ->get();

        $dataKelompokAset = DB::table('kelompokaset_m as ka')
            ->where('ka.statusenabled', true)
            ->orderBy('ka.kelompokaset')
            ->get();

        $dataMerkProduk = DB::table('merkproduk_m as mp')
            ->where('mp.statusenabled', true)
            ->orderBy('mp.merkproduk')
            ->get();

        $dataJenisAset = DB::table('jenisaset_m as ja')
            ->where('ja.statusenabled', true)
            ->orderBy('ja.jenisaset')
            ->get();

        $dataKondisiAset = DB::table('kondisiaset_m as kna')
            ->where('kna.statusenabled', true)
            ->orderBy('kna.kondisiaset')
            ->get();

        $dataSatuanAset = DB::table('satuanaset_m as sa')
            ->where('sa.statusenabled', true)
            ->orderBy('sa.satuanaset')
            ->get();

        $dataUsiaAset = DB::table('usiaaset_m as ua')
            ->where('ua.statusenabled', true)
            ->orderBy('ua.usiaaset')
            ->get();

        $dataJenisSertifikat = DB::table('jenissertifikat_m as ua')
            ->where('ua.statusenabled', true)
            ->orderBy('ua.reportdisplay')
            ->get();

        $dataProdusen = DB::table('produsenproduk_m as ua')
            ->where('ua.statusenabled', true)
            ->orderBy('ua.namaprodusenproduk')
            ->get();

        $dataFungsiProduk = DB::table('fungsiproduk_m as ua')
            ->where('ua.statusenabled', true)
            ->orderBy('ua.fungsiproduk')
            ->get();

        $dataBahanProduk = DB::table('bahanproduk_m as ua')
            ->where('ua.statusenabled', true)
            ->orderBy('ua.namabahanproduk')
            ->get();

        $dataWarna = DB::table('warnaproduk_m as ua')
            ->where('ua.statusenabled', true)
            ->orderBy('ua.warnaproduk')
            ->get();

        $dataBulan = DB::table('bulan_m as b')
            ->where('b.statusenabled', true)
            ->orderBy('b.id')
            ->get();

        $result = array(
            'pegawai' => $data,
            'ruangan' => $dataRuangan,
            'kelompokproduk' => $dataKelompokProduk,
            'kelompokproduk2' => $dataKelompokProduk2,
            'jenisbarang' => $dataJenisProduk,
            'asalproduk' => $dataSumberDana,
            'ruanganall' => $dataRuanganAll,
            'datalogin' => $dataLogin,
            'passwordstokopname' => $getPasswordStokOpname,
            'detailjenisproduk' => $detailJenis,
            'jenisproduk' => $dataJenisProduk,
            'jenisusulan' => $dataJenisUsulan,
            'dataAnggaran' => $dataAnggaran,
            'mataanggaran' => $dataAnggaranT,
            'jabatanpengelolaurusan' => $JabatanPengelolaUrusan,
            'kainstalasi' => $JenisJabatanKaInstalasi,
            'bulanromawi' => $bulanromawi,
            'jabatan' => $dataJabatan,
            'detaillogin' => $dataPegawaiUser,
            'kodeusulan' => $noOrder,
            'produk' => $dataProdukResult,
            'tipeproduk' => $dataTipeProduk,
            'satuan' => $dataSatuan,
            'jenisproduk' => $dataJenisProduk,
            'detailjenisproduk' => $dataDetailJenisProduk,
            'kelompokproduk' => $dataKelompokProduk,
            'kelompokaset' => $dataKelompokAset,
            'merkproduk' => $dataMerkProduk,
            'jenisaset' => $dataJenisAset,
            'kondisiaset' => $dataKondisiAset,
            'satuanaset' => $dataSatuanAset,
            'usiaaset' => $dataUsiaAset,
            'jenissertifikat' => $dataJenisSertifikat,
            'produsen' => $dataProdusen,
            'fungsiproduk' => $dataFungsiProduk,
            'bahanproduk' => $dataBahanProduk,
            'warna' => $dataWarna,
            'passwordAdjusment' => $getPasswordAdjusment,
            'bulan' => $dataBulan,
            'by' => 'ea@epic',
        );
        return $this->respond($result);
    }

    public function saveKirimBarangRuangan(Request $request)
    {
        $idProfile = $this->kdProfile;

        DB::beginTransaction();

        $ruanganAsal = Ruangan::select('namaruangan')->where('kdprofile', $this->kdProfile)
            ->where('statusenabled', true)
            ->where('id', $request['strukkirim']['objectruanganfk'])
            ->first();

        $ruanganTujuan = Ruangan::select('namaruangan')->where('kdprofile', $this->kdProfile)
            ->where('statusenabled', true)
            ->where('id', $request['strukkirim']['objectruangantujuanfk'])
            ->first();

        $nameRuAsal = $ruanganAsal->namaruangan;
        $nameRuTujuan = $ruanganTujuan->namaruangan;

        try {
            StrukOrder::where('norec', $request['strukkirim']['norecOrder'])->where('kdprofile', $this->kdProfile)->update(['statusorder' => 3]);

            $noKirim = $request['strukkirim']['jenispermintaanfk'] == 2 ?
            $this->SEQUENCE(new StrukKirim, 'nokirim', 14, 'TRF-' . $this->getDateTime()->format('ym'), $this->kdProfile) :
            $this->SEQUENCE(new StrukKirim, 'nokirim', 14, 'AMP-' . $this->getDateTime()->format('ym'), $this->kdProfile);

            $dataSK = new StrukKirim;
            $dataSK->norec = $dataSK->generateNewId();
            $dataSK->nokirim = $noKirim;
            $dataSK->kdprofile = $this->kdProfile;
            $dataSK->statusenabled = true;
            $dataSK->objectpegawaipengirimfk = $this->getUserId();
            $dataSK->objectruanganasalfk = $request['strukkirim']['objectruanganfk'];
            $dataSK->objectruanganfk = $request['strukkirim']['objectruanganfk'];
            $dataSK->objectruangantujuanfk = $request['strukkirim']['objectruangantujuanfk'];
            $dataSK->jenispermintaanfk = $request['strukkirim']['jenispermintaanfk'];
            $dataSK->objectkelompoktransaksifk = $this->kelompokTransaksi('PENGIRIMAN BARANG ANTAR RUANGAN');
            $dataSK->keteranganlainnyakirim = $request['strukkirim']['keteranganlainnyakirim'];
            $dataSK->qtydetailjenisproduk = 0;
            $dataSK->qtyproduk = $request['strukkirim']['qtyproduk'];
            $dataSK->tglkirim = date($request['strukkirim']['tglkirim']);
            $dataSK->totalbeamaterai = 0;
            $dataSK->totalbiayakirim = 0;
            $dataSK->totalbiayatambahan = 0;
            $dataSK->totaldiscount = 0;
            $dataSK->totalhargasatuan = $request['strukkirim']['totalhargasatuan'];
            $dataSK->totalharusdibayar = 0;
            $dataSK->totalpph = 0;
            $dataSK->totalppn = 0;
            $dataSK->noregistrasifk = $request['strukkirim']['norec_apd'];
            $dataSK->noorderfk = $request['strukkirim']['norecOrder'];
            if (isset($request['strukkirim']['statuskirim'])) {
                $dataSK->statuskirim = $request['strukkirim']['statuskirim'];
            }
            $dataSK->save();
            $norecSK = $dataSK->norec;

            
            $test = [];
            if($request['strukkirim']['jenispermintaanfk'] == 2){
                
                foreach($request['details'] as $order){

                    $stokAwalPenerima = StokProdukDetail::where('objectprodukfk', $order['produkfk'])->where('objectruanganfk', $request['strukkirim']['objectruangantujuanfk'])
                        ->where('statusenabled', true)->where('kdprofile',$this->kdProfile)
                        ->sum('qtyproduk');

                    $detailKirimProduk = OrderPelayanan::where('noorderfk', $request['strukkirim']['norecOrder'])->where('objectprodukfk',$order['produkfk'])
                                        ->where('kdprofile',$this->kdProfile)->where('statusenabled',true)->get();

                    $jumlah = (float)$order['qtykonfirmasi'] * (float)$order['nilaikonversi'];

                    $saldoAkhirPenerima = (float)$stokAwalPenerima + $jumlah;
                    foreach ($detailKirimProduk as $items) {
                        if ((float)$items->qtyprodukkonfirmasi <= $jumlah) {

                            $dataStok = StokProdukDetail::where('norec', $items['stokprodukdetailfk'])->where('statusenabled', true)->where('kdprofile',$this->kdProfile)->first();
                            
                            $dataNewSPD = new StokProdukDetail;
                            $dataNewSPD->norec = $dataNewSPD->generateNewId();
                            $dataNewSPD->kdprofile = $idProfile;
                            $dataNewSPD->statusenabled = true;
                            $dataNewSPD->objectasalprodukfk = $dataStok->objectasalprodukfk;
                            $dataNewSPD->hargadiscount = $dataStok->hargadiscount;
                            $dataNewSPD->harganetto1 = $dataStok->harganetto1;
                            $dataNewSPD->harganetto2 = $dataStok->harganetto2;
                            $dataNewSPD->persendiscount = 0;
                            $dataNewSPD->objectprodukfk = $dataStok->objectprodukfk;
                            $dataNewSPD->qtyproduk = (float)$items['qtyprodukkonfirmasi'];
                            $dataNewSPD->qtyprodukonhand = 0;
                            $dataNewSPD->qtyprodukoutext = 0;
                            $dataNewSPD->qtyprodukoutint = 0;
                            $dataNewSPD->objectruanganfk = $request['strukkirim']['objectruangantujuanfk'];
                            $dataNewSPD->nostrukterimafk = $dataStok->nostrukterimafk;
                            $dataNewSPD->noverifikasifk = $dataStok->noverifikasifk;
                            $dataNewSPD->nobatch = $dataStok->nobatch;
                            $dataNewSPD->tglkadaluarsa = $dataStok->tglkadaluarsa;
                            $dataNewSPD->tglpelayanan = $dataStok->tglpelayanan;
                            $dataNewSPD->tglproduksi = $dataStok->tglproduksi;
                            $dataNewSPD->save();

                            $dataKP = new KirimProduk;
                            $dataKP->norec = $dataKP->generateNewId();
                            $dataKP->kdprofile = $this->kdProfile;
                            $dataKP->statusenabled = true;
                            $dataKP->objectasalprodukfk = $items['objectasalprodukfk'];
                            $dataKP->hargadiscount = $items['hargadiscount'];
                            $dataKP->harganetto = $items['hargasatuan'];
                            $dataKP->hargapph = 0;
                            $dataKP->hargappn = 0;
                            $dataKP->hargasatuan = $items['hargasatuan'];
                            $dataKP->hargatambahan = 0;
                            $dataKP->hasilkonversi = $items['hasilkonversi'];
                            $dataKP->objectprodukfk = $items['objectprodukfk'];
                            $dataKP->objectprodukkirimfk = $items['objectprodukfk'];
                            $dataKP->nokirimfk = $norecSK;
                            $dataKP->persendiscount = 0;
                            $dataKP->qtyproduk = (float)$items['qtyproduk'];
                            $dataKP->qtyprodukkonfirmasi = (float)$items['qtyprodukkonfirmasi'];
                            $dataKP->qtyprodukretur = 0;
                            $dataKP->qtyorder = $items['qtyproduk'];
                            $dataKP->qtyprodukterima = (float)$items['qtyprodukkonfirmasi'];
                            $dataKP->nostrukterimafk = $items['nostrukterimafk'];
                            $dataKP->objectruanganfk = $request['strukkirim']['objectruangantujuanfk'];
                            $dataKP->objectruanganpengirimfk = $request['strukkirim']['objectruanganfk'];
                            $dataKP->satuan = '-';
                            $dataKP->objectsatuanstandarfk = $items['objectsatuanstandarfk'];
                            $dataKP->satuanviewfk = $items['objectsatuanstandarfk'];
                            $dataKP->tglpelayanan = date($request['strukkirim']['tglkirim']);
                            $dataKP->qtyprodukterimakonversi = (float)$items['qtyprodukkonfirmasi'];
                            $dataKP->stokprodukdetailfk = $items['stokprodukdetailfk'];
                            $dataKP->stokprodukdetailterimafk = $dataNewSPD->norec;
                            $dataKP->save();
                            // $isExsist = StokProdukDetail::where('nostrukterimafk', $dataStok->nostrukterimafk)->where('tglkadaluarsa', $dataStok->tglkadaluarsa)
                            //     ->where('statusenabled', true)->where('kdprofile', $this->kdProfile)
                            //     ->where('objectprodukfk', $dataStok->objectprodukfk)->where('objectruanganfk', $request['strukkirim']['objectruangantujuanfk'])
                            //     ->first();

                            // if ($isExsist) {
                            //     StokProdukDetail::where('kdprofile', $this->kdProfile)
                            //         ->where('norec', $isExsist->norec)
                            //         ->where('statusenabled', true)
                            //         ->lockForUpdate()
                            //         ->increment('qtyproduk', (float)$items['qtyprodukkonfirmasi']);
                            // }else{
                            //     $dataNewSPD = new StokProdukDetail;
                            //     $dataNewSPD->norec = $dataNewSPD->generateNewId();
                            //     $dataNewSPD->kdprofile = $idProfile;
                            //     $dataNewSPD->statusenabled = true;
                            //     $dataNewSPD->objectasalprodukfk = $dataStok->objectasalprodukfk;
                            //     $dataNewSPD->hargadiscount = $dataStok->hargadiscount;
                            //     $dataNewSPD->harganetto1 = $dataStok->harganetto1;
                            //     $dataNewSPD->harganetto2 = $dataStok->harganetto2;
                            //     $dataNewSPD->persendiscount = 0;
                            //     $dataNewSPD->objectprodukfk = $dataStok->objectprodukfk;
                            //     $dataNewSPD->qtyproduk = (float)$items['qtyprodukkonfirmasi'];
                            //     $dataNewSPD->qtyprodukonhand = 0;
                            //     $dataNewSPD->qtyprodukoutext = 0;
                            //     $dataNewSPD->qtyprodukoutint = 0;
                            //     $dataNewSPD->objectruanganfk = $request['strukkirim']['objectruangantujuanfk'];
                            //     $dataNewSPD->nostrukterimafk = $dataStok->nostrukterimafk;
                            //     $dataNewSPD->noverifikasifk = $dataStok->noverifikasifk;
                            //     $dataNewSPD->nobatch = $dataStok->nobatch;
                            //     $dataNewSPD->tglkadaluarsa = $dataStok->tglkadaluarsa;
                            //     $dataNewSPD->tglpelayanan = $dataStok->tglpelayanan;
                            //     $dataNewSPD->tglproduksi = $dataStok->tglproduksi;
                            //     $dataNewSPD->save();
                            // }        
                            $jumlah = $jumlah - (float)$items['qtyprodukkonfirmasi'];
                           
                        } else {
                            $dataStok = StokProdukDetail::where('norec', $items['stokprodukdetailfk'])->where('statusenabled', true)->where('kdprofile',$this->kdProfile)->first();

                            $dataNewSPD = new StokProdukDetail;
                            $dataNewSPD->norec = $dataNewSPD->generateNewId();
                            $dataNewSPD->kdprofile = $idProfile;
                            $dataNewSPD->statusenabled = true;
                            $dataNewSPD->objectasalprodukfk = $dataStok->objectasalprodukfk;
                            $dataNewSPD->hargadiscount = $dataStok->hargadiscount;
                            $dataNewSPD->harganetto1 = $dataStok->harganetto1;
                            $dataNewSPD->harganetto2 = $dataStok->harganetto2;
                            $dataNewSPD->persendiscount = 0;
                            $dataNewSPD->objectprodukfk = $dataStok->objectprodukfk;
                            $dataNewSPD->qtyproduk = (float)$jumlah;
                            $dataNewSPD->qtyprodukonhand = 0;
                            $dataNewSPD->qtyprodukoutext = 0;
                            $dataNewSPD->qtyprodukoutint = 0;
                            $dataNewSPD->objectruanganfk = $request['strukkirim']['objectruangantujuanfk'];
                            $dataNewSPD->nostrukterimafk = $dataStok->nostrukterimafk;
                            $dataNewSPD->noverifikasifk = $dataStok->noverifikasifk;
                            $dataNewSPD->nobatch = $dataStok->nobatch;
                            $dataNewSPD->tglkadaluarsa = $dataStok->tglkadaluarsa;
                            $dataNewSPD->tglpelayanan = $dataStok->tglpelayanan;
                            $dataNewSPD->tglproduksi = $dataStok->tglproduksi;
                            $dataNewSPD->save();

                            $dataKP = new KirimProduk;
                            $dataKP->norec = $dataKP->generateNewId();
                            $dataKP->kdprofile = $this->kdProfile;
                            $dataKP->statusenabled = true;
                            $dataKP->objectasalprodukfk = $items['objectasalprodukfk'];
                            $dataKP->hargadiscount = $items['hargadiscount'];
                            $dataKP->harganetto = $items['hargasatuan'];
                            $dataKP->hargapph = 0;
                            $dataKP->hargappn = 0;
                            $dataKP->hargasatuan = $items['hargasatuan'];
                            $dataKP->hargatambahan = 0;
                            $dataKP->hasilkonversi = $items['hasilkonversi'];
                            $dataKP->objectprodukfk = $items['objectprodukfk'];
                            $dataKP->objectprodukkirimfk = $items['objectprodukfk'];
                            $dataKP->nokirimfk = $norecSK;
                            $dataKP->persendiscount = 0;
                            $dataKP->qtyproduk = (float)$items['qtyproduk'];
                            $dataKP->qtyprodukkonfirmasi = $jumlah;
                            $dataKP->qtyprodukretur = 0;
                            $dataKP->qtyorder = (float)$items['qtyproduk'];
                            $dataKP->qtyprodukterima = $jumlah;
                            $dataKP->nostrukterimafk = $items->nostrukterimafk;
                            $dataKP->objectruanganfk = $request['strukkirim']['objectruangantujuanfk'];
                            $dataKP->objectruanganpengirimfk = $request['strukkirim']['objectruanganfk'];
                            $dataKP->satuan = '-';
                            $dataKP->objectsatuanstandarfk = $items['objectsatuanstandarfk'];
                            $dataKP->satuanviewfk = $items['objectsatuanstandarfk'];
                            $dataKP->tglpelayanan = date($request['strukkirim']['tglkirim']);
                            $dataKP->qtyprodukterimakonversi = (float)$jumlah;
                            $dataKP->stokprodukdetailfk = $items['stokprodukdetailfk'];
                            $dataKP->stokprodukdetailterimafk = $dataNewSPD->norec;
                            $dataKP->save();
                          
                            $jumlah = 0;
                        }
                    }
                    
                    $this->kartu_STOK(array(
                        "saldoawal" => (float)$stokAwalPenerima,
                        "qtyin" => (float)$order['qtykonfirmasi'] * (float)$order['nilaikonversi'],
                        "qtyout" => 0,
                        "saldoakhir" => $saldoAkhirPenerima,
                        "keterangan" => 'Terima Barang, dari Ruangan ' . $nameRuAsal . ' ke Ruangan ' . $nameRuTujuan . ' berupa produk ' . $order['namaproduk'] . '. No Kirim: ' .  $noKirim,
                        "produkfk" => $order['produkfk'],
                        "ruanganfk" => $request['strukkirim']['objectruangantujuanfk'],
                        "tglinput" => date('Y-m-d H:i:s'),
                        "tglkejadian" => date('Y-m-d H:i:s'),
                        "nostrukterimafk" => null,
                        "norectransaksi" => $norecSK,
                        "tabletransaksi" => 'strukkirim_t',
                        "stokprodukdetailfk" => null,
                        "flagfk" => null,
                    ));
                }
            }else{
                foreach ($request['details'] as $order) {
                    $stokAwalPenerima = StokProdukDetail::where('objectprodukfk', $order['produkfk'])->where('objectruanganfk', $request['strukkirim']['objectruangantujuanfk'])
                        ->where('statusenabled', true)->where('kdprofile', $this->kdProfile)
                        ->sum('qtyproduk');

                    $detailKirimProduk = OrderPelayanan::where('noorderfk', $request['strukkirim']['norecOrder'])->where('objectprodukfk', $order['produkfk'])
                        ->where('kdprofile', $this->kdProfile)->where('statusenabled', true)->get();

                    $jumlah = (float)$order['qtykonfirmasi'] * (float)$order['nilaikonversi'];

                    $saldoAkhirPenerima = (float)$stokAwalPenerima + $jumlah;
                    foreach ($detailKirimProduk as $items) {
                        if ((float)$items->qtyprodukkonfirmasi <= $jumlah) {
                            $dataKP = new KirimProduk;
                            $dataKP->norec = $dataKP->generateNewId();
                            $dataKP->kdprofile = $this->kdProfile;
                            $dataKP->statusenabled = true;
                            $dataKP->objectasalprodukfk = $items['objectasalprodukfk'];
                            $dataKP->hargadiscount = $items['hargadiscount'];
                            $dataKP->harganetto = $items['hargasatuan'];
                            $dataKP->hargapph = 0;
                            $dataKP->hargappn = 0;
                            $dataKP->hargasatuan = $items['hargasatuan'];
                            $dataKP->hargatambahan = 0;
                            $dataKP->hasilkonversi = $items['hasilkonversi'];
                            $dataKP->objectprodukfk = $items['objectprodukfk'];
                            $dataKP->objectprodukkirimfk = $items['objectprodukfk'];
                            $dataKP->nokirimfk = $norecSK;
                            $dataKP->persendiscount = 0;
                            $dataKP->qtyproduk = (float)$items['qtyproduk'];
                            $dataKP->qtyprodukkonfirmasi = (float)$items['qtyprodukkonfirmasi'];
                            $dataKP->qtyprodukretur = 0;
                            $dataKP->qtyorder = $items['qtyproduk'];
                            $dataKP->qtyprodukterima = (float)$items['qtyprodukkonfirmasi'];
                            $dataKP->nostrukterimafk = $items['nostrukterimafk'];
                            $dataKP->objectruanganfk = $request['strukkirim']['objectruangantujuanfk'];
                            $dataKP->objectruanganpengirimfk = $request['strukkirim']['objectruanganfk'];
                            $dataKP->satuan = '-';
                            $dataKP->objectsatuanstandarfk = $items['objectsatuanstandarfk'];
                            $dataKP->satuanviewfk = $items['objectsatuanstandarfk'];
                            $dataKP->tglpelayanan = date($request['strukkirim']['tglkirim']);
                            $dataKP->qtyprodukterimakonversi = (float)$items['qtyprodukkonfirmasi'];
                            $dataKP->stokprodukdetailfk = $items['stokprodukdetailfk'];
                            // $dataKP->stokprodukdetailterimafk = $dataNewSPD->norec;
                            $dataKP->save();
                            $jumlah = $jumlah - (float)$items['qtyprodukkonfirmasi'];
                        }else{
                            $dataKP = new KirimProduk;
                            $dataKP->norec = $dataKP->generateNewId();
                            $dataKP->kdprofile = $this->kdProfile;
                            $dataKP->statusenabled = true;
                            $dataKP->objectasalprodukfk = $items['objectasalprodukfk'];
                            $dataKP->hargadiscount = $items['hargadiscount'];
                            $dataKP->harganetto = $items['hargasatuan'];
                            $dataKP->hargapph = 0;
                            $dataKP->hargappn = 0;
                            $dataKP->hargasatuan = $items['hargasatuan'];
                            $dataKP->hargatambahan = 0;
                            $dataKP->hasilkonversi = $items['hasilkonversi'];
                            $dataKP->objectprodukfk = $items['objectprodukfk'];
                            $dataKP->objectprodukkirimfk = $items['objectprodukfk'];
                            $dataKP->nokirimfk = $norecSK;
                            $dataKP->persendiscount = 0;
                            $dataKP->qtyproduk = (float)$items['qtyproduk'];
                            $dataKP->qtyprodukkonfirmasi = $jumlah;
                            $dataKP->qtyprodukretur = 0;
                            $dataKP->qtyorder = (float)$items['qtyproduk'];
                            $dataKP->qtyprodukterima = $jumlah;
                            $dataKP->nostrukterimafk = $items->nostrukterimafk;
                            $dataKP->objectruanganfk = $request['strukkirim']['objectruangantujuanfk'];
                            $dataKP->objectruanganpengirimfk = $request['strukkirim']['objectruanganfk'];
                            $dataKP->satuan = '-';
                            $dataKP->objectsatuanstandarfk = $items['objectsatuanstandarfk'];
                            $dataKP->satuanviewfk = $items['objectsatuanstandarfk'];
                            $dataKP->tglpelayanan = date($request['strukkirim']['tglkirim']);
                            $dataKP->qtyprodukterimakonversi = (float)$jumlah;
                            $dataKP->stokprodukdetailfk = $items['stokprodukdetailfk'];
                            // $dataKP->stokprodukdetailterimafk = $dataNewSPD->norec;
                            $dataKP->save();

                            $jumlah = 0;
                        }
                    }
                }  
            }


            DB::commit();
            $result = [
                "status" => 200,
                "message" => "Berhasil Verifikasi Data",
            ];
        } catch (Exception $e) {
            DB::rollBack();
            $result = [
                "status" => 400,
                "message" => "Something Want Wrong",
                "data" => $e->getMessage(),
            ];
        }

        return $this->respond($result, $result['status'], $result['message']);
    }
    // public function saveKirimBarangRuangan(Request $request)
    // {
    //     $idProfile = $this->kdProfile;

    //     DB::beginTransaction();

    //     $ruanganAsal = Ruangan::select('namaruangan')->where('kdprofile', $this->kdProfile)
    //         ->where('statusenabled', true)
    //         ->where('id', $request['strukkirim']['objectruanganfk'])
    //         ->first();

    //     $ruanganTujuan = Ruangan::select('namaruangan')->where('kdprofile', $this->kdProfile)
    //         ->where('statusenabled', true)
    //         ->where('id', $request['strukkirim']['objectruangantujuanfk'])
    //         ->first();

    //     $nameRuAsal = $ruanganAsal->namaruangan;
    //     $nameRuTujuan = $ruanganTujuan->namaruangan;

    //     try {
    //         StrukOrder::where('norec', $request['strukkirim']['norecOrder'])->where('kdprofile', $this->kdProfile)->update(['statusorder' => 3]);
            
    //         $strukKirim = StrukKirim::where('noorderfk', $request['strukkirim']['norecOrder'])->where('kdprofile', $this->kdProfile)->where('statusenabled', true)->first();
            
    //         $test = [];
    //         if($request['strukkirim']['jenispermintaanfk'] == 2){
                
    //             foreach($request['details'] as $order){

    //                 $stokAwalPenerima = StokProdukDetail::where('objectprodukfk', $order['produkfk'])->where('objectruanganfk', $request['strukkirim']['objectruangantujuanfk'])
    //                     ->where('statusenabled', true)->where('kdprofile',$this->kdProfile)
    //                     ->sum('qtyproduk');

    //                 $detailKirimProduk = KirimProduk::where('nokirimfk', $strukKirim->norec)->where('objectprodukfk', $order['produkfk'])->get();

    //                 $jumlah = (float)$order['qtykonfirmasi'] * (float)$order['nilaikonversi'];

    //                 $saldoAkhirPenerima = (float)$stokAwalPenerima + $jumlah;
    //                 foreach ($detailKirimProduk as $items) {
    //                     if ((float)$items->qtyprodukkonfirmasi <= $jumlah) {
                           
    //                         $dataStok = StokProdukDetail::where('norec', $items->stokprodukdetailfk)->where('statusenabled', true)->where('kdprofile',$this->kdProfile)->first();
                            
    //                         $isExsist = StokProdukDetail::where('nostrukterimafk', $dataStok->nostrukterimafk)->where('tglkadaluarsa', $dataStok->tglkadaluarsa)
    //                             ->where('statusenabled', true)->where('kdprofile', $this->kdProfile)
    //                             ->where('objectprodukfk', $dataStok->objectprodukfk)->where('objectruanganfk', $request['strukkirim']['objectruangantujuanfk'])
    //                             ->first();

    //                         if ($isExsist) {
    //                             StokProdukDetail::where('kdprofile', $this->kdProfile)
    //                                 ->where('norec', $isExsist->norec)
    //                                 ->where('statusenabled', true)
    //                                 ->lockForUpdate()
    //                                 ->increment('qtyproduk', (float)$items->qtyprodukkonfirmasi);
    //                         }else{
    //                             $dataNewSPD = new StokProdukDetail;
    //                             $dataNewSPD->norec = $dataNewSPD->generateNewId();
    //                             $dataNewSPD->kdprofile = $idProfile;
    //                             $dataNewSPD->statusenabled = true;
    //                             $dataNewSPD->objectasalprodukfk = $dataStok->objectasalprodukfk;
    //                             $dataNewSPD->hargadiscount = $dataStok->hargadiscount;
    //                             $dataNewSPD->harganetto1 = $dataStok->harganetto1;
    //                             $dataNewSPD->harganetto2 = $dataStok->harganetto2;
    //                             $dataNewSPD->persendiscount = 0;
    //                             $dataNewSPD->objectprodukfk = $dataStok->objectprodukfk;
    //                             $dataNewSPD->qtyproduk = (float)$items->qtyprodukkonfirmasi;
    //                             $dataNewSPD->qtyprodukonhand = 0;
    //                             $dataNewSPD->qtyprodukoutext = 0;
    //                             $dataNewSPD->qtyprodukoutint = 0;
    //                             $dataNewSPD->objectruanganfk = $request['strukkirim']['objectruangantujuanfk'];
    //                             $dataNewSPD->nostrukterimafk = $dataStok->nostrukterimafk;
    //                             $dataNewSPD->noverifikasifk = $dataStok->noverifikasifk;
    //                             $dataNewSPD->nobatch = $dataStok->nobatch;
    //                             $dataNewSPD->tglkadaluarsa = $dataStok->tglkadaluarsa;
    //                             $dataNewSPD->tglpelayanan = $dataStok->tglpelayanan;
    //                             $dataNewSPD->tglproduksi = $dataStok->tglproduksi;
    //                             $dataNewSPD->save();
    //                         }        
    //                         $jumlah = $jumlah - (float)$items->qtyprodukkonfirmasi;
                           
    //                     } else {

    //                         $dataStok = StokProdukDetail::where('norec', $items->stokprodukdetailfk)->where('statusenabled', true)->where('kdprofile',$this->kdProfile)->first();
                            
    //                         $isExsist = StokProdukDetail::where('nostrukterimafk', $dataStok->nostrukterimafk)->where('tglkadaluarsa', $dataStok->tglkadaluarsa)
    //                             ->where('statusenabled', true)->where('kdprofile', $this->kdProfile)
    //                             ->where('objectprodukfk', $dataStok->objectprodukfk)->where('objectruanganfk', $request['strukkirim']['objectruangantujuanfk'])
    //                             ->first();
    //                         if ($isExsist) {
    //                             StokProdukDetail::where('kdprofile', $this->kdProfile)
    //                                 ->where('norec', $isExsist->norec)
    //                                 ->where('statusenabled', true)
    //                                 ->lockForUpdate()
    //                                 ->increment('qtyproduk', (float)$jumlah);
    //                         }else{
    //                             $dataNewSPD = new StokProdukDetail;
    //                             $dataNewSPD->norec = $dataNewSPD->generateNewId();
    //                             $dataNewSPD->kdprofile = $idProfile;
    //                             $dataNewSPD->statusenabled = true;
    //                             $dataNewSPD->objectasalprodukfk = $dataStok->objectasalprodukfk;
    //                             $dataNewSPD->hargadiscount = $dataStok->hargadiscount;
    //                             $dataNewSPD->harganetto1 = $dataStok->harganetto1;
    //                             $dataNewSPD->harganetto2 = $dataStok->harganetto2;
    //                             $dataNewSPD->persendiscount = 0;
    //                             $dataNewSPD->objectprodukfk = $dataStok->objectprodukfk;
    //                             $dataNewSPD->qtyproduk = (float)$jumlah;
    //                             $dataNewSPD->qtyprodukonhand = 0;
    //                             $dataNewSPD->qtyprodukoutext = 0;
    //                             $dataNewSPD->qtyprodukoutint = 0;
    //                             $dataNewSPD->objectruanganfk = $request['strukkirim']['objectruangantujuanfk'];
    //                             $dataNewSPD->nostrukterimafk = $dataStok->nostrukterimafk;
    //                             $dataNewSPD->noverifikasifk = $dataStok->noverifikasifk;
    //                             $dataNewSPD->nobatch = $dataStok->nobatch;
    //                             $dataNewSPD->tglkadaluarsa = $dataStok->tglkadaluarsa;
    //                             $dataNewSPD->tglpelayanan = $dataStok->tglpelayanan;
    //                             $dataNewSPD->tglproduksi = $dataStok->tglproduksi;
    //                             $dataNewSPD->save();

    //                         }
    //                         $jumlah = 0;
    //                     }
    //                 }
                  
    //                 $this->kartu_STOK(array(
    //                     "saldoawal" => (float)$stokAwalPenerima,
    //                     "qtyin" => (float)$order['qtykonfirmasi'] * (float)$order['nilaikonversi'],
    //                     "qtyout" => 0,
    //                     "saldoakhir" => $saldoAkhirPenerima,
    //                     "keterangan" => 'Terima Barang, dari Ruangan ' . $nameRuAsal . ' ke Ruangan ' . $nameRuTujuan . ' berupa produk ' . $order['namaproduk'] . '. No Kirim: ' .  $strukKirim->nokirim,
    //                     "produkfk" => $order['produkfk'],
    //                     "ruanganfk" => $request['strukkirim']['objectruangantujuanfk'],
    //                     "tglinput" => date('Y-m-d H:i:s'),
    //                     "tglkejadian" => date('Y-m-d H:i:s'),
    //                     "nostrukterimafk" => null,
    //                     "norectransaksi" => $strukKirim->norec,
    //                     "tabletransaksi" => 'strukkirim_t',
    //                     "stokprodukdetailfk" => null,
    //                     "flagfk" => null,
    //                 ));
    //             }
    //         }

    //         DB::commit();
    //         $result = [
    //             "status" => 200,
    //             "message" => "Berhasil Verifikasi Data",
    //         ];
    //     } catch (Exception $e) {
    //         DB::rollBack();
    //         $result = [
    //             "status" => 400,
    //             "message" => "Something Want Wrong",
    //             "data" => $e->getMessage(),
    //         ];
    //     }

    //     return $this->respond($result, $result['status'], $result['message']);
    // }

    // public function saveKirimBarangRuanganBaru(Request $request)
    // {
    //     $idProfile = $this->kdProfile;
    //     $noKirim = $request['strukkirim']['jenispermintaanfk'] == 2 ?
    //         $this->SEQUENCE(new StrukKirim, 'nokirim', 14, 'TRF-' . $this->getDateTime()->format('ym'), $this->kdProfile) :
    //         $this->SEQUENCE(new StrukKirim, 'nokirim', 14, 'AMP-' . $this->getDateTime()->format('ym'), $this->kdProfile);

    //     DB::beginTransaction();

    //     $ruanganAsal = Ruangan::select('namaruangan')->where('kdprofile', $this->kdProfile)
    //         ->where('statusenabled', true)
    //         ->where('id', $request['strukkirim']['objectruanganfk'])
    //         ->first();

    //     $ruanganTujuan = Ruangan::select('namaruangan')->where('kdprofile', $this->kdProfile)
    //         ->where('statusenabled', true)
    //         ->where('id', $request['strukkirim']['objectruangantujuanfk'])
    //         ->first();

    //     $nameRuAsal = $ruanganAsal->namaruangan;
    //     $nameRuTujuan = $ruanganTujuan->namaruangan;

    //     try {
    //         if ($request['strukkirim']['noreckirim'] == '') {
    //             if ($request['strukkirim']['norecOrder'] != '') {
    //                 StrukOrder::where('norec', $request['strukkirim']['norecOrder'])
    //                     ->where('kdprofile', $this->kdProfile)
    //                     ->update(['statusorder' => 3]);
    //             }
    //             $dataSK = new StrukKirim;
    //             $dataSK->norec = $dataSK->generateNewId();
    //             $dataSK->nokirim = $noKirim;
    //             $message = "Verifikasi Order Barang Berhasil";
    //         }else{
    //             DB::rollBack();
    //             return $this->respond($request['strukkirim']['noreckirim'],200,'Data Udah Diverif');
    //         }
    //         $dataSK->kdprofile = $this->kdProfile;
    //         $dataSK->statusenabled = true;
    //         $dataSK->objectpegawaipengirimfk = $this->getUserId();
    //         $dataSK->objectruanganasalfk = $request['strukkirim']['objectruanganfk'];
    //         $dataSK->objectruanganfk = $request['strukkirim']['objectruanganfk'];
    //         $dataSK->objectruangantujuanfk = $request['strukkirim']['objectruangantujuanfk'];
    //         $dataSK->jenispermintaanfk = $request['strukkirim']['jenispermintaanfk'];
    //         $dataSK->objectkelompoktransaksifk = $this->kelompokTransaksi('PENGIRIMAN BARANG ANTAR RUANGAN');
    //         $dataSK->keteranganlainnyakirim = $request['strukkirim']['keteranganlainnyakirim'];
    //         $dataSK->qtydetailjenisproduk = 0;
    //         $dataSK->qtyproduk = $request['strukkirim']['qtyproduk'];
    //         $dataSK->tglkirim = date($request['strukkirim']['tglkirim']);
    //         $dataSK->totalbeamaterai = 0;
    //         $dataSK->totalbiayakirim = 0;
    //         $dataSK->totalbiayatambahan = 0;
    //         $dataSK->totaldiscount = 0;
    //         $dataSK->totalhargasatuan = $request['strukkirim']['totalhargasatuan'];
    //         $dataSK->totalharusdibayar = 0;
    //         $dataSK->totalpph = 0;
    //         $dataSK->totalppn = 0;
    //         $dataSK->noregistrasifk = $request['strukkirim']['norec_apd'];
    //         $dataSK->noorderfk = $request['strukkirim']['norecOrder'];
    //         if (isset($request['strukkirim']['statuskirim'])) {
    //             $dataSK->statuskirim = $request['strukkirim']['statuskirim'];
    //         }
    //         $dataSK->save();

    //         $norecSK = $dataSK->norec;
            
    //         $test = [];
    //         foreach ($request['details'] as $item) {

    //             $satuanstandarfk = Produk::select('objectsatuanstandarfk')
    //                 ->where('kdprofile', $this->kdProfile)
    //                 ->where('statusenabled', true)
    //                 ->where('id', $item['produkfk'])
    //                 ->first();
    //             $dataOrder = OrderPelayanan::where('strukorderfk', $request['strukkirim']['norecOrder'])->where('statusenabled', true)
    //                                         ->where('qtyprodukkonfirmasi','>', 0)->where('kdprofile', $this->kdProfile)->get();
                
    //             if ($request['strukkirim']['jenispermintaanfk'] == 2) {
    //                 $dataSaldoAwalPenerima = StokProdukDetail::where('kdprofile', $this->kdProfile)
    //                     ->where('objectruanganfk', $request['strukkirim']['objectruangantujuanfk'])
    //                     ->where('objectprodukfk', $item['produkfk'])
    //                     ->sum('qtyproduk');

    //                 $jumlah = (float)$item['qtykonfirmasi'] * (float)$item['nilaikonversi'];

    //                 $saldoAkhirPenerima = (float)$dataSaldoAwalPenerima + (float)$item['qtykonfirmasi'] * (float)$item['nilaikonversi'];

    //                 // $test = [];

    //                 $dataSaldoAwalPengirim = StokProdukDetail::where('kdprofile', $this->kdProfile)
    //                     ->where('objectruanganfk', $request['strukkirim']['objectruanganfk'])
    //                     ->where('objectprodukfk', $item['produkfk'])
    //                     ->where('qtyproduk', '>', 0)
    //                     ->orderBy('tglkadaluarsa', 'ASC')
    //                     ->get();

    //                 foreach ($dataSaldoAwalPengirim as $items) {
    //                     if ((float)$items->qtyproduk <= $jumlah) {
    //                         $dataKP = new KirimProduk;
    //                         $dataKP->norec = $dataKP->generateNewId();
    //                         $dataKP->kdprofile = $this->kdProfile;
    //                         $dataKP->statusenabled = true;
    //                         $dataKP->objectasalprodukfk = $items->objectasalprodukfk;
    //                         $dataKP->hargadiscount = $items->hargadiscount;
    //                         $dataKP->harganetto = $items->harganetto1;
    //                         $dataKP->hargapph = 0;
    //                         $dataKP->hargappn = 0;
    //                         $dataKP->hargasatuan = $items->harganetto1;
    //                         $dataKP->hargatambahan = 0;
    //                         $dataKP->hasilkonversi = $item['nilaikonversi'];;
    //                         $dataKP->objectprodukfk = $item['produkfk'];
    //                         $dataKP->objectprodukkirimfk = $item['produkfk'];
    //                         $dataKP->nokirimfk = $norecSK;
    //                         $dataKP->persendiscount = 0;
    //                         $dataKP->qtyproduk = (float)$items->qtyproduk;
    //                         $dataKP->qtyprodukkonfirmasi = (float)$items->qtyproduk;
    //                         $dataKP->qtyprodukretur = 0;
    //                         $dataKP->qtyorder = $item['qtyorder'];
    //                         $dataKP->qtyprodukterima = (float)$items->qtyproduk;
    //                         $dataKP->nostrukterimafk = $items->nostrukterimafk;
    //                         $dataKP->objectruanganfk = $request['strukkirim']['objectruangantujuanfk'];
    //                         $dataKP->objectruanganpengirimfk = $request['strukkirim']['objectruanganfk'];
    //                         $dataKP->satuan = '-';
    //                         $dataKP->objectsatuanstandarfk = $satuanstandarfk->id;
    //                         $dataKP->satuanviewfk = $item['satuanviewfk'];
    //                         $dataKP->tglpelayanan = date($request['strukkirim']['tglkirim']);
    //                         $dataKP->qtyprodukterimakonversi = (float)$items->qtyproduk;
    //                         $dataKP->stokprodukdetailfk = $items->norec;
    //                         $dataKP->save();

    //                         $jumlah = $jumlah - (float)$items->qtyproduk;
                        
    //                         $dataStok = StokProdukDetail::where('norec', $items->norec)->first();

    //                         $dataNewSPD = new StokProdukDetail;
    //                         $dataNewSPD->norec = $dataNewSPD->generateNewId();
    //                         $dataNewSPD->kdprofile = $this->kdProfile;
    //                         $dataNewSPD->statusenabled = true;
    //                         $dataNewSPD->objectasalprodukfk = $dataStok->objectasalprodukfk;
    //                         $dataNewSPD->hargadiscount = $dataStok->hargadiscount;
    //                         $dataNewSPD->harganetto1 = $dataStok->harganetto1;
    //                         $dataNewSPD->harganetto2 = $dataStok->harganetto2;
    //                         $dataNewSPD->persendiscount = 0;
    //                         $dataNewSPD->objectprodukfk = $dataStok->objectprodukfk;
    //                         $dataNewSPD->qtyproduk = (float)$items->qtyproduk;
    //                         $dataNewSPD->qtyprodukonhand = 0;
    //                         $dataNewSPD->qtyprodukoutext = 0;
    //                         $dataNewSPD->qtyprodukoutint = 0;
    //                         $dataNewSPD->objectruanganfk = $request['strukkirim']['objectruangantujuanfk'];
    //                         $dataNewSPD->nostrukterimafk = $items->nostrukterimafk;
    //                         $dataNewSPD->noverifikasifk = $dataStok->noverifikasifk;
    //                         $dataNewSPD->nobatch = $dataStok->nobatch;
    //                         $dataNewSPD->tglkadaluarsa = $dataStok->tglkadaluarsa;
    //                         $dataNewSPD->tglpelayanan = $dataStok->tglpelayanan;
    //                         $dataNewSPD->tglproduksi = $dataStok->tglproduksi;
    //                         $dataNewSPD->save();
    //                     } else {
    //                         $dataKP = new KirimProduk;
    //                         $dataKP->norec = $dataKP->generateNewId();
    //                         $dataKP->kdprofile = $this->kdProfile;
    //                         $dataKP->statusenabled = true;
    //                         $dataKP->objectasalprodukfk = $items->objectasalprodukfk;
    //                         $dataKP->hargadiscount = $items->hargadiscount;
    //                         $dataKP->harganetto = $items->harganetto1;
    //                         $dataKP->hargapph = 0;
    //                         $dataKP->hargappn = 0;
    //                         $dataKP->hargasatuan = $items->harganetto1;
    //                         $dataKP->hargatambahan = 0;
    //                         $dataKP->hasilkonversi = $item['nilaikonversi'];
    //                         $dataKP->objectprodukfk = $item['produkfk'];
    //                         $dataKP->objectprodukkirimfk = $item['produkfk'];
    //                         $dataKP->nokirimfk = $norecSK;
    //                         $dataKP->persendiscount = 0;
    //                         $dataKP->qtyproduk = $jumlah;
    //                         $dataKP->qtyprodukkonfirmasi = $jumlah;
    //                         $dataKP->qtyprodukretur = 0;
    //                         $dataKP->qtyorder = $item['qtyorder'];
    //                         $dataKP->qtyprodukterima = $jumlah;
    //                         $dataKP->stokprodukdetailfk = $items->norec;
    //                         $dataKP->nostrukterimafk = $items->nostrukterimafk;
    //                         $dataKP->objectruanganfk = $request['strukkirim']['objectruangantujuanfk'];
    //                         $dataKP->objectruanganpengirimfk = $request['strukkirim']['objectruanganfk'];
    //                         $dataKP->satuan = '-';
    //                         $dataKP->objectsatuanstandarfk = $satuanstandarfk->id; //$item['satuanstandarfk'];
    //                         $dataKP->satuanviewfk = $item['satuanviewfk'];
    //                         $dataKP->tglpelayanan = date($request['strukkirim']['tglkirim']);
    //                         $dataKP->qtyprodukterimakonversi = $jumlah;
    //                         $dataKP->save();

    //                         $dataStok = StokProdukDetail::where('norec', $items->norec)->where('kdprofile', $this->kdProfile)->first();
    //                         $dataNewSPD = new StokProdukDetail;
    //                         $dataNewSPD->norec = $dataNewSPD->generateNewId();
    //                         $dataNewSPD->kdprofile = $this->kdProfile;
    //                         $dataNewSPD->statusenabled = true;
    //                         $dataNewSPD->objectasalprodukfk = $dataStok->objectasalprodukfk;
    //                         $dataNewSPD->hargadiscount = $dataStok->hargadiscount;
    //                         $dataNewSPD->harganetto1 = $dataStok->harganetto1;
    //                         $dataNewSPD->harganetto2 = $dataStok->harganetto2;
    //                         $dataNewSPD->persendiscount = 0;
    //                         $dataNewSPD->objectprodukfk = $dataStok->objectprodukfk;
    //                         $dataNewSPD->qtyproduk = ((float)$jumlah);
    //                         $dataNewSPD->qtyprodukonhand = 0;
    //                         $dataNewSPD->qtyprodukoutext = 0;
    //                         $dataNewSPD->qtyprodukoutint = 0;
    //                         $dataNewSPD->objectruanganfk = $request['strukkirim']['objectruangantujuanfk'];
    //                         $dataNewSPD->nostrukterimafk = $items->nostrukterimafk;
    //                         $dataNewSPD->noverifikasifk = $dataStok->noverifikasifk;
    //                         $dataNewSPD->nobatch = $dataStok->nobatch;
    //                         $dataNewSPD->tglkadaluarsa = $dataStok->tglkadaluarsa;
    //                         $dataNewSPD->tglpelayanan = $dataStok->tglpelayanan;
    //                         $dataNewSPD->tglproduksi = $dataStok->tglproduksi;
    //                         $dataNewSPD->save();
    //                         $jumlah = 0;
    //                     }
    //                 }


    //                 // foreach ($dataOrder as $items) {
    //                 //     if ((float)$items->qtyproduk <= $jumlah) {
    //                 //         $dataKP = new KirimProduk;
    //                 //         $dataKP->norec = $dataKP->generateNewId();
    //                 //         $dataKP->kdprofile = $this->kdProfile;
    //                 //         $dataKP->statusenabled = true;
    //                 //         $dataKP->objectasalprodukfk = $items->objectasalprodukfk;
    //                 //         $dataKP->hargadiscount = $items->hargadiscount;
    //                 //         $dataKP->harganetto = $items->harganetto1;
    //                 //         $dataKP->hargapph = 0;
    //                 //         $dataKP->hargappn = 0;
    //                 //         $dataKP->hargasatuan = $items->harganetto1;
    //                 //         $dataKP->hargatambahan = 0;
    //                 //         $dataKP->hasilkonversi = $item['nilaikonversi'];;
    //                 //         $dataKP->objectprodukfk = $item['produkfk'];
    //                 //         $dataKP->objectprodukkirimfk = $item['produkfk'];
    //                 //         $dataKP->nokirimfk = $norecSK;
    //                 //         $dataKP->persendiscount = 0;
    //                 //         $dataKP->qtyproduk = (float)$items->qtyproduk;
    //                 //         $dataKP->qtyprodukkonfirmasi = (float)$items->qtyproduk;
    //                 //         $dataKP->qtyprodukretur = 0;
    //                 //         $dataKP->qtyorder = $item['qtyorder'];
    //                 //         $dataKP->qtyprodukterima = (float)$items->qtyproduk;
    //                 //         $dataKP->nostrukterimafk = $items->nostrukterimafk;
    //                 //         $dataKP->objectruanganfk = $request['strukkirim']['objectruangantujuanfk'];
    //                 //         $dataKP->objectruanganpengirimfk = $request['strukkirim']['objectruanganfk'];
    //                 //         $dataKP->satuan = '-';
    //                 //         $dataKP->objectsatuanstandarfk = $satuanstandarfk;
    //                 //         $dataKP->satuanviewfk = $item['satuanviewfk'];
    //                 //         $dataKP->tglpelayanan = date($request['strukkirim']['tglkirim']);
    //                 //         $dataKP->qtyprodukterimakonversi = (float)$items->qtyproduk;
    //                 //         $dataKP->stokprodukdetailfk = $items->norec;
    //                 //         $dataKP->save();

    //                 //         $dataStok = StokProdukDetail::where('norec', $items->norec)->first();

    //                 //         $dataNewSPD = new StokProdukDetail;
    //                 //         $dataNewSPD->norec = $dataNewSPD->generateNewId();
    //                 //         $dataNewSPD->kdprofile = $this->kdProfile;
    //                 //         $dataNewSPD->statusenabled = true;
    //                 //         $dataNewSPD->objectasalprodukfk = $dataStok->objectasalprodukfk;
    //                 //         $dataNewSPD->hargadiscount = $dataStok->hargadiscount;
    //                 //         $dataNewSPD->harganetto1 = $dataStok->harganetto1;
    //                 //         $dataNewSPD->harganetto2 = $dataStok->harganetto2;
    //                 //         $dataNewSPD->persendiscount = 0;
    //                 //         $dataNewSPD->objectprodukfk = $dataStok->objectprodukfk;
    //                 //         $dataNewSPD->qtyproduk = (float)$items->qtyproduk;
    //                 //         $dataNewSPD->qtyprodukonhand = 0;
    //                 //         $dataNewSPD->qtyprodukoutext = 0;
    //                 //         $dataNewSPD->qtyprodukoutint = 0;
    //                 //         $dataNewSPD->objectruanganfk = $request['strukkirim']['objectruangantujuanfk'];
    //                 //         $dataNewSPD->nostrukterimafk = $items->nostrukterimafk;
    //                 //         $dataNewSPD->noverifikasifk = $dataStok->noverifikasifk;
    //                 //         $dataNewSPD->nobatch = $dataStok->nobatch;
    //                 //         $dataNewSPD->tglkadaluarsa = $dataStok->tglkadaluarsa;
    //                 //         $dataNewSPD->tglpelayanan = $dataStok->tglpelayanan;
    //                 //         $dataNewSPD->tglproduksi = $dataStok->tglproduksi;
    //                 //         $dataNewSPD->save();
    //                 //     } else {
    //                 //         $dataKP = new KirimProduk;
    //                 //         $dataKP->norec = $dataKP->generateNewId();
    //                 //         $dataKP->kdprofile = $this->kdProfile;
    //                 //         $dataKP->statusenabled = true;
    //                 //         $dataKP->objectasalprodukfk = $items->objectasalprodukfk;
    //                 //         $dataKP->hargadiscount = $items->hargadiscount;
    //                 //         $dataKP->harganetto = $items->harganetto1;
    //                 //         $dataKP->hargapph = 0;
    //                 //         $dataKP->hargappn = 0;
    //                 //         $dataKP->hargasatuan = $items->harganetto1;
    //                 //         $dataKP->hargatambahan = 0;
    //                 //         $dataKP->hasilkonversi = $item['nilaikonversi'];
    //                 //         $dataKP->objectprodukfk = $item['produkfk'];
    //                 //         $dataKP->objectprodukkirimfk = $item['produkfk'];
    //                 //         $dataKP->nokirimfk = $norecSK;
    //                 //         $dataKP->persendiscount = 0;
    //                 //         $dataKP->qtyproduk = $jumlah;
    //                 //         $dataKP->qtyprodukkonfirmasi = $jumlah;
    //                 //         $dataKP->qtyprodukretur = 0;
    //                 //         $dataKP->qtyorder = $item['qtyorder'];
    //                 //         $dataKP->qtyprodukterima = $jumlah;
    //                 //         $dataKP->stokprodukdetailfk = $items->norec;
    //                 //         $dataKP->nostrukterimafk = $items->nostrukterimafk;
    //                 //         $dataKP->objectruanganfk = $request['strukkirim']['objectruangantujuanfk'];
    //                 //         $dataKP->objectruanganpengirimfk = $request['strukkirim']['objectruanganfk'];
    //                 //         $dataKP->satuan = '-';
    //                 //         $dataKP->objectsatuanstandarfk = $satuanstandarfk; //$item['satuanstandarfk'];
    //                 //         $dataKP->satuanviewfk = $item['satuanviewfk'];
    //                 //         $dataKP->tglpelayanan = date($request['strukkirim']['tglkirim']);
    //                 //         $dataKP->qtyprodukterimakonversi = $jumlah;
    //                 //         $dataKP->save();

    //                 //         $dataStok = StokProdukDetail::where('norec', $items->norec)->where('kdprofile', $this->kdProfile)->first();

    //                 //         $dataNewSPD = new StokProdukDetail;
    //                 //         $dataNewSPD->norec = $dataNewSPD->generateNewId();
    //                 //         $dataNewSPD->kdprofile = $this->kdProfile;
    //                 //         $dataNewSPD->statusenabled = true;
    //                 //         $dataNewSPD->objectasalprodukfk = $dataStok->objectasalprodukfk;
    //                 //         $dataNewSPD->hargadiscount = $dataStok->hargadiscount;
    //                 //         $dataNewSPD->harganetto1 = $dataStok->harganetto1;
    //                 //         $dataNewSPD->harganetto2 = $dataStok->harganetto2;
    //                 //         $dataNewSPD->persendiscount = 0;
    //                 //         $dataNewSPD->objectprodukfk = $dataStok->objectprodukfk;
    //                 //         $dataNewSPD->qtyproduk = (float)$jumlah;
    //                 //         $dataNewSPD->qtyprodukonhand = 0;
    //                 //         $dataNewSPD->qtyprodukoutext = 0;
    //                 //         $dataNewSPD->qtyprodukoutint = 0;
    //                 //         $dataNewSPD->objectruanganfk = $request['strukkirim']['objectruangantujuanfk'];
    //                 //         $dataNewSPD->nostrukterimafk = $items->nostrukterimafk;
    //                 //         $dataNewSPD->noverifikasifk = $dataStok->noverifikasifk;
    //                 //         $dataNewSPD->nobatch = $dataStok->nobatch;
    //                 //         $dataNewSPD->tglkadaluarsa = $dataStok->tglkadaluarsa;
    //                 //         $dataNewSPD->tglpelayanan = $dataStok->tglpelayanan;
    //                 //         $dataNewSPD->tglproduksi = $dataStok->tglproduksi;
    //                 //         $dataNewSPD->save();
    //                 //         $jumlah = 0;
    //                 //     }
    //                 // }

    //                 // foreach ($dataOrder as $order) {
    //                 //     $dataPengirim = StokProdukDetail::where('objectruanganfk', $request['strukkirim']['objectruanganfk'])
    //                 //         ->where('objectprodukfk', $order['objectprodukfk'])
    //                 //         ->where('statusenabled', true)
    //                 //         ->where('kdprofile', $this->kdProfile)
    //                 //         ->where('nostrukterimafk', $order['nostrukterimafk'])
    //                 //         ->first();
    //                 //         // return $dataPengirim;
    //                 //     // $test[] = $dataOrder;
    //                 //     $jumlah = (float)$order['qtyprodukkonfirmasi'] * (float)$item['nilaikonversi'];

    //                 //     $dataKP = new KirimProduk();
    //                 //     $dataKP->norec = $dataKP->generateNewId();
    //                 //     $dataKP->kdprofile = $idProfile;
    //                 //     $dataKP->statusenabled = true;
    //                 //     $dataKP->objectasalprodukfk = $dataPengirim->objectasalprodukfk;
    //                 //     if ($dataPengirim->hargadiscount == null) {
    //                 //         $dataKP->hargadiscount = 0;
    //                 //     } else {
    //                 //         $dataKP->hargadiscount = $dataPengirim->hargadiscount;
    //                 //     }
    //                 //     $dataKP->harganetto = $dataPengirim->harganetto1;
    //                 //     $dataKP->hargapph = 0;
    //                 //     $dataKP->hargappn = 0;
    //                 //     $dataKP->hargasatuan = $dataPengirim->harganetto1;
    //                 //     $dataKP->hargatambahan = 0;
    //                 //     $dataKP->hasilkonversi = $item['nilaikonversi'];
    //                 //     $dataKP->objectprodukfk = $item['produkfk'];
    //                 //     $dataKP->objectprodukkirimfk = $item['produkfk'];
    //                 //     $dataKP->nokirimfk = $norecSK;
    //                 //     $dataKP->persendiscount = 0;
    //                 //     $dataKP->qtyproduk = $jumlah;
    //                 //     $dataKP->qtyprodukkonfirmasi = $jumlah;
    //                 //     $dataKP->qtyprodukretur = 0;
    //                 //     $dataKP->qtyorder = $item['qtyorder'];
    //                 //     $dataKP->qtyprodukterima = $jumlah;
    //                 //     $dataKP->nostrukterimafk = $dataPengirim->nostrukterimafk;
    //                 //     $dataKP->objectruanganfk = $request['strukkirim']['objectruangantujuanfk'];
    //                 //     $dataKP->objectruanganpengirimfk = $request['strukkirim']['objectruanganfk'];
    //                 //     $dataKP->satuan = '-';
    //                 //     $dataKP->objectsatuanstandarfk = $satuanstandarfk->objectsatuanstandarfk; //$item['satuanstandarfk'];
    //                 //     $dataKP->satuanviewfk = $item['satuanviewfk'];
    //                 //     $dataKP->tglpelayanan = date($request['strukkirim']['tglkirim']);
    //                 //     $dataKP->qtyprodukterimakonversi = $jumlah;
    //                 //     if (isset($item['tglexp']) && $item['tglexp'] != 'Invalid date' && $item['tglexp'] != '') {
    //                 //         $dataKP->tglkadaluarsa = $item['tglexp'];
    //                 //     }
    //                 //     // $dataKP->nobatch = $item['nobatch'];
    //                 //     if (isset($r_NewPD['nobatch'])) {
    //                 //         $r_NewPD->nobatch = $r_NewPD['nobatch'];
    //                 //     }

    //                 //     $dataKP->save();

    //                 //     $dataNewSPD = new StokProdukDetail;
    //                 //     $dataNewSPD->norec = $dataNewSPD->generateNewId();
    //                 //     $dataNewSPD->kdprofile = $idProfile;
    //                 //     $dataNewSPD->statusenabled = true;
    //                 //     $dataNewSPD->objectasalprodukfk = $dataPengirim->objectasalprodukfk;
    //                 //     $dataNewSPD->hargadiscount = $dataPengirim->hargadiscount;
    //                 //     $dataNewSPD->harganetto1 = $dataPengirim->harganetto1;
    //                 //     $dataNewSPD->harganetto2 = $dataPengirim->harganetto2;
    //                 //     $dataNewSPD->persendiscount = 0;
    //                 //     $dataNewSPD->objectprodukfk = $dataPengirim->objectprodukfk;
    //                 //     $dataNewSPD->qtyproduk = (float)$jumlah;
    //                 //     $dataNewSPD->qtyprodukonhand = 0;
    //                 //     $dataNewSPD->qtyprodukoutext = 0;
    //                 //     $dataNewSPD->qtyprodukoutint = 0;
    //                 //     $dataNewSPD->objectruanganfk = $request['strukkirim']['objectruangantujuanfk'];
    //                 //     $dataNewSPD->nostrukterimafk = $dataPengirim->nostrukterimafk;
    //                 //     $dataNewSPD->noverifikasifk = $dataPengirim->noverifikasifk;
    //                 //     $dataNewSPD->nobatch = $dataPengirim->nobatch;
    //                 //     $dataNewSPD->tglkadaluarsa = $dataPengirim->tglkadaluarsa;
    //                 //     $dataNewSPD->tglpelayanan = $dataPengirim->tglpelayanan;
    //                 //     $dataNewSPD->tglproduksi = $dataPengirim->tglproduksi;
    //                 //     $dataNewSPD->save();

    //                 // }

    //                 $this->kartu_STOK(array(
    //                     "saldoawal" => (float)$dataSaldoAwalPenerima,
    //                     "qtyin" => (float)$item['qtykonfirmasi'] * (float)$item['nilaikonversi'],
    //                     "qtyout" => 0,
    //                     "saldoakhir" => $saldoAkhirPenerima,
    //                     "keterangan" => 'Terima Barang, dari Ruangan ' . $nameRuAsal . ' ke Ruangan ' . $nameRuTujuan . ' berupa produk ' . $item['namaproduk'] . '. No Kirim: ' .  $dataSK->nokirim,
    //                     "produkfk" => $item['produkfk'],
    //                     "ruanganfk" => $request['strukkirim']['objectruangantujuanfk'],
    //                     "tglinput" => date('Y-m-d H:i:s'),
    //                     "tglkejadian" => date('Y-m-d H:i:s'),
    //                     "nostrukterimafk" => null,
    //                     "norectransaksi" => $norecSK,
    //                     "tabletransaksi" => 'strukkirim_t',
    //                     "flagfk" => null,
    //                 ));
    //             }
    //         }

        
    //         DB::commit();
    //         $result = [
    //             "status" => 200,
    //             "message" => $message,
    //         ];
    //     } catch (Exception $e) {
    //         DB::rollBack();
    //         $result = [
    //             "status" => 400,
    //             "message" => "Something Want Wrong",
    //             "data" => $e->getMessage(),
    //         ];
    //     }

    //     return $this->respond($result, $result['status'], $result['message']);
    // }

}
