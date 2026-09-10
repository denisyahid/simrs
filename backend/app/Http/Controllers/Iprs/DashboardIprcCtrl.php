<?php

namespace App\Http\Controllers\Iprs;

use App\Datatrans\StrukOrder;
use App\Http\Controllers\Controller;
use App\Models\Master\JenisPekerjaan;
use App\Models\Master\JenisUsulan;
use App\Models\Master\KelompokBarang;
use App\Models\Master\KelompokProduk;
use App\Models\Master\Pegawai;
use App\Models\Master\Produk;
use App\Models\Master\Ruangan;
use App\Models\Master\StatusPekerjaan;
use App\Models\Transaksi\KirimProduk;
use App\Models\Transaksi\RiwayatRealisasi;
use App\Models\Transaksi\StokProdukDetail;
use App\Models\Transaksi\StrukKirim;
use App\Models\Transaksi\StrukKonfirmasi;
use App\Models\Transaksi\StrukPlanning;
use App\Models\Transaksi\StrukPlanningDetail;
use App\Models\Transaksi\StrukPraOrder;
use App\Models\Transaksi\StrukPraOrderDetail;
use App\Models\Transaksi\StrukRealisasi;
use App\Models\Transaksi\StrukVerifikasi;
use App\Traits\Valet;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardIprcCtrl extends Controller
{
    use Valet;

    public function combo(Request $request)
    {
        $idDepIPSRS = $this->settingFix('idDepartemenIPSRS');

        $result['kelompokproduk'] = KelompokProduk::mine()->get();
        $result['kelompokbarang'] = KelompokBarang::mine()->get();
        $result['jenisalat'] = JenisPekerjaan::mine()->where('namaexternal', 'Jenis Alat')->get();
        $result['jeniskerusakan'] = JenisPekerjaan::mine()->where('namaexternal', 'Status Jenis')->get();
        $result['statuspekerjaan'] = StatusPekerjaan::mine()->where('kodeexternal', 'IPSRS')->get();
        $result['bulanromawi'] = $this->KonDecRomawi($this->getDateTime()->format('m'));
        $result['jenisusulan'] = JenisUsulan::mine()->get();
        $result['ruangan'] = Ruangan::mine()->where('objectdepartemenfk', $idDepIPSRS)->get();

        return $this->respond($result);
    }

    public function getPegawaiPenangungJawab(Request $request){
        
        $data = Pegawai::select('namalengkap','id','nip')->where('kdprofile',$this->kdProfile)
                    ->where('statusenabled',true)->search($request['namalengkap'])->limit(20)->get();
                
        return $this->respond($data);            
    }

    public function saveRencanaUsulanPermintaanNew(Request $request)
    {

        DB::beginTransaction();
      
        try {
            if ($request['strukorder']['norec'] == '') {

                $dataSO = new StrukPraOrder();
                $dataSO->norec = $dataSO->generateNewId();
                $dataSO->kdprofile = $this->kdProfile;
                $dataSO->statusenabled = true;
                $dataSO->noorder = $request['strukorder']['noUsulan'];

            } else {
                $dataSO = StrukPraOrder::where('norec', $request['strukorder']['norec'])->first();
                $dataSO->nostruk;
                StrukPraOrderDetail::where('strukorderfk', $request['strukorder']['norec'])->delete();
            }
            $dataSO->isdelivered = 0;
            $dataSO->objectkelompoktransaksifk = $this->kelompokTransaksi('RENCANA USULAN PERMINTAAN BARANG');
            $dataSO->keteranganorder = $request['strukorder']['keteranganorder'];
            $dataSO->objectkelompokbarangfk = $request['strukorder']['kelompokbarangfk'];
            $dataSO->qtyjenisproduk = $request['strukorder']['qtyjenisproduk'];
            $dataSO->qtyproduk = $request['strukorder']['qtyjenisproduk'];
            $dataSO->tglorder = $request['strukorder']['tglUsulan'];
            $dataSO->tglvalidasi = $request['strukorder']['tglDibutuhkan'];
            $dataSO->keteranganlainnya = $request['strukorder']['koordinator'];
            $dataSO->noorderintern = $request['strukorder']['noUsulan'];
            $dataSO->objectruanganfk = $request['strukorder']['ruanganfkPengusul'];
            $dataSO->objectruangantujuanfk = $request['strukorder']['ruanganfkTujuan'];
            $dataSO->objectpegawaiorderfk = $request['strukorder']['penanggungjawabfk'];
            $dataSO->objectpetugasfk = $request['strukorder']['mengetahuifk'];
            $dataSO->noorderintern = $request['strukorder']['noUsulan'];
            $dataSO->objectpegawaiperencanafk = $this->getUserId();
            $dataSO->statusorder = 0;
            $dataSO->totalbeamaterai = 0;
            $dataSO->totalbiayakirim = 0;
            $dataSO->totalbiayatambahan = 0;
            $dataSO->totaldiscount = 0;
            $dataSO->totalhargasatuan = $request['strukorder']['total'];
            $dataSO->totalharusdibayar = 0;
            $dataSO->totalpph = 0;
            $dataSO->totalppn =  $request['strukorder']['ppn'];
            $dataSO->save();

            $SO = array(
                "norec"  => $dataSO->norec,
                "objectkelompoktransaksifk" => $dataSO->objectkelompoktransaksifk,

            );
            foreach ($request['details'] as $item) {
                $dataOP = new StrukPraOrderDetail();
                $dataOP->norec = $dataOP->generateNewId();
                $dataOP->kdprofile = $this->kdProfile;
                $dataOP->statusenabled = true;
                $dataOP->hasilkonversi = $item['nilaikonversi'];
                $dataOP->iscito = 0;
                $dataOP->noorderfk = $SO['norec'];
                $dataOP->objectprodukfk = $item['produkfk'];
                $dataOP->objectasalprodukfk = null;
                $dataOP->qtyproduk = $item['jumlah'];
                $dataOP->qtyprodukretur = 0;
                $dataOP->objectsatuanstandarfk = $item['satuanviewfk'];
                $dataOP->strukorderfk = $SO['norec'];
                $dataOP->tglpelayanan = $request['strukorder']['tglUsulan'];
                $dataOP->hargasatuan = (float)$item['hargasatuan'];
                $dataOP->hargadiscount = $item['hargadiscount'];
                $dataOP->persendiscount = $item['persendiscount'];
                $dataOP->hargappn = $item['ppn'];
                $dataOP->persenppn = $item['persenppn'];
                $dataOP->deskripsiprodukquo = $item['spesifikasi']; //$item['spesifikasi'];
                $dataOP->tglpelayananakhir = $item['tglkebutuhan'];
                $dataOP->save();     
            }

            //***** Struk Realisasi *****
            // $datanorecSR = '';
            if ($request['strukorder']['norecrealisasi'] == '') {
                $dataSR = new StrukRealisasi();
                $norealisasi = $this->generateCode(new StrukRealisasi(), 'norealisasi', 10, 'RA-' . $this->getDateTime()->format('ym'), $this->kdProfile);
                $dataSR->norec = $dataSR->generateNewId();
                $dataSR->kdprofile = $this->kdProfile;
                $dataSR->statusenabled = true;
                $dataSR->norealisasi = $norealisasi;
            } else {
                $dataSR = StrukRealisasi::where('norec', $request['strukorder']['norecrealisasi'])->first();
            }
            $dataSR->tglrealisasi = $request['strukorder']['tglUsulan'];
            $dataSR->totalbelanja = $request['strukorder']['total'];
            $dataSR->save();

            $SR = array("norec"  => $dataSR->norec);

            if ($request['strukorder']['norecrealisasi'] == '') {
                $dataRR = new RiwayatRealisasi();
                $dataRR->norec = $dataRR->generateNewId();
                $dataRR->kdprofile = $this->kdProfile;
                $dataRR->statusenabled = true;
                $dataRR->objectkelompoktransaksifk = 117;
                $dataRR->rencanaorderfk = $SO['norec'];
            } else {
                $dataRR = RiwayatRealisasi::where('objectstrukrealisasifk', $request['strukorder']['norecrealisasi'])->first();
            }
            $dataRR->objectstrukrealisasifk = $SR['norec'];
            $dataRR->tglrealisasi = $request['strukorder']['tglUsulan'];
            $dataRR->objectpetugasfk = $this->getUserId();
            $dataRR->noorderintern = $request['strukorder']['noUsulan'];
            $dataRR->keteranganlainnya = $request['strukorder']['keteranganorder'];
            $dataRR->save();

            DB::commit();
            $result = array(
                "status" => 201,
                "message" => 'Simpan Data Berhasil',
                "nokirim" => $dataSO,
                "as" => 'as@epic',
            );

        } catch (\Exception $e) {

            DB::rollBack();
            $result = array(
                "status" => 400,
                "message"  => 'Something Want Wrong',
                "nokirim" => $dataSO,
                "data" => $e->getMessage(),
                "as" => 'as@epic',
            );
        }

        return $this->respond($result,$result['status'],$result['message']);
    }

    public function getDaftarRencanaUsulanPermintaan(Request $request)
    {
        $dateRange = [$request->tglAwal, $request->tglAkhir];

        $datas = DB::table('strukpraorder_t as sp')
        ->JOIN('strukpraorderdetail_t as op', 'op.noorderfk', '=', 'sp.norec')
        ->LEFTJOIN('riwayatrealisasi_t as rr', 'rr.rencanaorderfk', '=', 'sp.norec')
        ->LEFTJOIN('strukrealisasi_t as sr', 'sr.norec', '=', 'rr.objectstrukrealisasifk')
        ->LEFTJOIN('pegawai_m as pg', 'pg.id', '=', 'sp.objectpegawaiorderfk')
        ->LEFTJOIN('pegawai_m as pg2', 'pg2.id', '=', 'sp.objectpetugasfk')
        ->LEFTJOIN('ruangan_m as ru', 'ru.id', '=', 'sp.objectruanganfk')
        ->LEFTJOIN('ruangan_m as ru2', 'ru2.id', '=', 'sp.objectruangantujuanfk')
        ->LEFTJOIN('strukverifikasi_t as sv', 'sv.norec', '=', 'sp.objectsrukverifikasifk')
        ->LEFTJOIN('strukverifikasi_t as sv1', 'sv1.norec', '=', 'sp.objectsrukverifikasikafk')
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
            'sp.totalhargasatuan',
            'sp.status',
            'sr.norec as norecrealisasi',
            'sv.noverifikasi as noverifpengelolaurusan',
            'sv.norec as norecverif',
            'sv1.noverifikasi as noverifkepalainstalasi'
        )
        ->where('sp.kdprofile', $this->kdProfile)
        ->whereBetween(DB::raw("CAST(sp.tglorder as Date)"), $dateRange)
        ->where('sp.statusenabled', true)
        ->where('sp.objectkelompoktransaksifk', $this->kelompokTransaksi('RENCANA USULAN PERMINTAAN BARANG'));

        if (isset($request['search']) && $request['search'] != "" && $request['search'] != "undefined") {
            $datas = $datas->where('sp.noorder', 'ILIKE', '%' . $request['search'] . '%');
        }
        if (isset($request['noKontrak']) && $request['noKontrak'] != "" && $request['noKontrak'] != "undefined") {
            $datas = $datas->where('sp.nokontrakspk', 'ILIKE', '%' . $request['noKontrak'] . '%');
        }

        $datas = $datas->distinct();
        $datas = $datas->orderBy('sp.tglorder');
        $datas = $datas->get();

        $details = DB::table('strukpraorderdetail_t as spd')
                     ->leftJoin('produk_m as pr' ,'pr.id','spd.objectprodukfk')
                     ->leftJoin('satuanstandar_m as ss','ss.id','spd.objectsatuanstandarfk')
                     ->selectRaw('pr.namaproduk, ss.satuanstandar,spd.qtyproduk,spd.hargasatuan,spd.hargadiscount,spd.hargappn,spd.strukorderfk,
                        (spd.qtyproduk*(spd.hargasatuan)) as total, spd.tglpelayananakhir as tglkebutuhan,spd.deskripsiprodukquo as spesifikasi,pr.id as prid')
                     ->where('spd.kdprofile',$this->kdProfile)
                     ->whereBetween(DB::raw('spd.tglpelayanan::date'), $dateRange)
                     ->where('spd.statusenabled',true);
                    if (isset($request['produkfk']) && $request['produkfk'] != "" && $request['produkfk'] != "undefined") {
                        $details = $details->where('pr.id', $request['produkfk']);
                    }

                 $details = $details->get();

        $results = array();
        foreach($datas as $data){
            foreach($details as $item){
                if($data->norec == $item->strukorderfk){
                    $results[] = [
                        'tglorder' => $data->tglorder,
                        'noorder' => $data->noorder,
                        'norec' => $data->norec,
                        'penanggungjawab' => $data->penanggungjawab,
                        'keterangan' => $data->keteranganorder,
                        'koordinator' => $data->keteranganlainnya,
                        'tglkebutuhan' => $data->tglkebutuhan,
                        'tglusulan' => $data->tglorder,
                        'nousulan' => $data->noorderintern,
                        'namapengadaan' => $data->keterangankeperluan,
                        'mengetahui' => $data->mengetahui,
                        'ruangan' => $data->ruangan,
                        'ruangantujuan' => $data->ruangantujuan,
                        'totalhargasatuan' => $data->totalhargasatuan,
                        'status' => $data->status,
                        'norecverif' => $data->norecverif,
                        'noverifpengelolaurusan' => $data->noverifpengelolaurusan,
                        'noverifkepalainstalasi' => $data->noverifkepalainstalasi,
                        'details' => $details,
                        'norecrealisasi' => $data->norecrealisasi,
                    ];
                }
            }
            
        }

        return $this->respond($results);
    }


    public function hapusDataRUPB(Request $request)
    {

        DB::beginTransaction();
        try {

            StrukPraOrder::where('norec', $request['norec'])->where('lu.kdprofile', $this->kdProfile)->update(['statusenabled' => false]);

            if ($request['norecrealisasi'] != '') {

                $dataRR = new RiwayatRealisasi();
                $dataRR->norec = $dataRR->generateNewId();
                $dataRR->kdprofile = $this->kdProfile;
                $dataRR->statusenabled = true;
                $dataRR->objectkelompoktransaksifk = 118;
                $dataRR->objectstrukrealisasifk = $request['norecrealisasi'];
                $dataRR->tglrealisasi = date('Y-m-d H:i:s'); //$request['tglusulan'];
                $dataRR->objectpetugasfk = $this->getUserId();
                $dataRR->noorderintern = $request['nousulan'];
                $dataRR->rencanaorderfk = $request['norec'];
                $dataRR->keteranganlainnya = 'Batal Input Rencana Usulan Permintaan Barang';
                $dataRR->save();
            }

            //## Logging User
              $this->LOGGING(
                $dataRR->keteranganlainnya,
                $request['norec'],
                'strukpraorder_t',
                'Pembatalan Rancana Usulan Permintaan Barang dengan nomer usulan : ' . $request['nousulan']
            );

            DB::commit();
            $result = array(
                'status' => 201,
                'message' => 'Batal Rencana Usulan Berhasil',
                'as' => 'ea@epic',
            );

        } catch (Exception $e) {
            DB::rollBack();
            $result = array(
                'status' => 400,
                'message'  => 'Something Want Wrong',
                'as' => 'ea@epic',
            );
        }

        return $this->respond($result,$result['status'],$result['message']);
    }

    public function getDetailRUPB(Request $request)
    {
        $idProfile = (int) $this->kdProfile;
        $dataStruk = DB::table('strukpraorder_t as sp')
        ->LEFTJOIN('kelompokbarang_m as kb', 'kb.id','sp.objectkelompokbarangfk')
        ->LEFTJOIN('riwayatrealisasi_t as rr', 'rr.rencanaorderfk','sp.norec')
        ->LEFTJOIN('strukrealisasi_t as sr', 'sr.norec','rr.objectstrukrealisasifk')
        ->LEFTJOIN('pegawai_m as pg', 'pg.id','sp.objectpegawaiorderfk')
        ->LEFTJOIN('pegawai_m as pg1', 'pg1.id','sp.objectpetugasfk')
        ->LEFTJOIN('pegawai_m as pg2', 'pg2.id','sp.objectpegawaiperencanafk')
        ->LEFTJOIN('ruangan_m as ru', 'ru.id','sp.objectruanganfk')
        ->LEFTJOIN('ruangan_m as ru1', 'ru1.id','sp.objectruangantujuanfk')
        ->selectRaw("sp.norec,sp.tglorder,sp.noorder,pg.namalengkap,pg.id as pgid,pg1.nip,sp.alamat,
			 sp.alamattempattujuan,sp.keteranganlainnya,sp.tglvalidasi,sp.noorderintern,sp.keterangankeperluan,
			 sp.nokontrakspk,sp.noorderrfq,sp.keteranganorder,sp.namarekanansales,sp.totalhargasatuan,sp.objectkelompokbarangfk,
			 kb.kelompokbarang,sp.objectpetugasfk,pg1.namalengkap as mengetahui,sr.norealisasi,sr.norec as norecrealisasi,
			 ru.id as idunitpengusul,ru.namaruangan as unitpengusul,ru1.id as idunittujuan,ru1.namaruangan as unittujuan,		 	
             EXTRACT(YEAR FROM sp.tglorder) AS tahunusulan,pg2.id as idperencana,pg2.namalengkap as namaperencana")
        ->where('sp.kdprofile', $idProfile);

        if (isset($request['norec']) && $request['norec'] != "" && $request['norec'] != "undefined") {
            $dataStruk = $dataStruk->where('sp.norec', '=', $request['norec']);
        }
        $dataStruk = $dataStruk->first();

        $detail = array(
            'tglorder' => $dataStruk->tglorder,
            'noorder' => $dataStruk->noorder,
            'kelompokbarang' => $dataStruk->kelompokbarang,
            'kelompokbarangfk' => $dataStruk->objectkelompokbarangfk,
            'norec' => $dataStruk->norec,
            'petugasid' => $dataStruk->pgid,
            'petugas' => $dataStruk->namalengkap,
            'petugasmengetahui' => $dataStruk->mengetahui,
            'petugasmengetahuiid' => $dataStruk->objectpetugasfk,
            'nip' => $dataStruk->nip,
            'keterangan' => $dataStruk->keteranganorder,
            'koordinator' => $dataStruk->keteranganlainnya,
            'tgldibutuhkan' => $dataStruk->tglvalidasi,
            'tglusulan' => $dataStruk->tglorder,
            'nousulan' => $dataStruk->noorderintern,
            'namapengadaan' => $dataStruk->keterangankeperluan,
            'nokontrak' => $dataStruk->nokontrakspk,
            'tahunusulan' => $dataStruk->tahunusulan,
            'totalhargasatuan' => $dataStruk->totalhargasatuan,
            'norealisasi' => $dataStruk->norealisasi,
            'norecrealisasi' => $dataStruk->norecrealisasi,
            'idunitpengusul' => $dataStruk->idunitpengusul,
            'unitpengusul' => $dataStruk->unitpengusul,
            'idunittujuan' => $dataStruk->idunittujuan,
            'unittujuan' => $dataStruk->unittujuan,
        );

        $i = 0;
        $dataStok = $details = DB::select(
            DB::raw("select spd.norec as norec_op, pr.id as produkfk,pr.namaproduk,pr.kdproduk,
                                 spd.deskripsiprodukquo as spesifikasi,ss.satuanstandar,ss.id as ssid,spd.qtyproduk,spd.hargasatuan,
                                 spd.hargadiscount,spd.hargappn,spd.qtyproduk*spd.hargasatuan as subtotal,spd.persenppn,spd.persendiscount,
                                 (spd.qtyproduk*spd.hargasatuan + spd.hargappn-spd.hargadiscount) as total,
                                 spd.hasilkonversi,spd.strukorderfk,spd.tglpelayananakhir as tglkebutuhan
                    from strukpraorderdetail_t as spd 
                    left JOIN produk_m as pr on pr.id=spd.objectprodukfk
                    left JOIN satuanstandar_m as ss on ss.id=spd.objectsatuanstandarfk
                    left JOIN asalproduk_m as ap on ap.id=spd.objectasalprodukfk
                    left JOIN status_barang_m as sb on sb.id = spd.objectstatusbarang
                    where spd.kdprofile = $idProfile and spd.strukorderfk=:norec"),
            array(
                'norec' => $request['norec'],
            )
        );
        $jmlstok = 0;
        $details = [];
        foreach ($dataStok as $item) {
            $i = $i + 1;
            $details[] = array(
                'no' => $i,
                'kdproduk' => $item->kdproduk,
                'produkfk' => $item->produkfk,
                'norec_op' => $item->norec_op,
                'namaproduk' => $item->namaproduk,
                'nilaikonversi' => $item->hasilkonversi,
                'satuanstandarfk' => $item->ssid,
                'satuanstandar' => $item->satuanstandar,
                'satuanviewfk' => $item->ssid,
                'satuanview' => $item->satuanstandar,
                'spesifikasi' => $item->spesifikasi,
                'jumlah' => (float)$item->qtyproduk,
                'hargasatuan' => $item->hargasatuan,
                'hargadiscount' => $item->hargadiscount,
                'ppn' => $item->hargappn,
                'subtotal' => $item->subtotal,
                'total' => $item->total,
                'persendiscount' => $item->persendiscount,
                'persenppn' => $item->persenppn,
                'keterangan' => '',
                'nobatch' => '',
                'tglkadaluarsa' => null,
                'tglkebutuhan' => $item->tglkebutuhan,
            );
        }

        $result = array(
            'header' => $detail,
            'details' => $details,
            'message' => 'as@epic',
        );
        return $this->respond($result);
    }

    public function saveVerifikasiPengelolaUrusan(Request $request)
    {

        DB::beginTransaction();

        try {
            if ($request['norec'] != '') {

                //#struk Verifikasi
                if ($request['verifikasifk'] == '') {
                    $noVerifikasi = $this->generateCode( new StrukVerifikasi(),'noverifikasi',10,'VPU' . $this->getDateTime()->format('ym'),$this->kdProfile);
                    $dataSV = new StrukVerifikasi();
                    $dataSV->norec = $dataSV->generateNewId();
                    $dataSV->noverifikasi = $noVerifikasi;
                    $dataSV->kdprofile = $this->kdProfile;
                    $dataSV->statusenabled = true;
                    $dataSV->objectkelompoktransaksifk = $this->kelompokTransaksi('VERIFIKASI USULAN PERMINTAAN BARANG / JASA');
                } else {
                    $dataSV = StrukVerifikasi::where('norec', $request['verifikasifk'])->first();
                }
                $dataSV->keteranganlainnya = 'Verifikasi RUPB Pengelola Urusan';
                $dataSV->objectpegawaipjawabfk = $this->getUserId();
                $dataSV->namaverifikasi = 'Verifikasi RUPB Pengelola Urusan';
                $dataSV->tglverifikasi = date('Y-m-d H:i:s');
                $dataSV->tgleksekusi = date('Y-m-d H:i:s');
                $dataSV->save();
                $noVerifikasi = $dataSV->noverifikasi;
                $dataSV = $dataSV->norec;

                StrukPraOrder::where('norec', $request['norec'])->update(['objectsrukverifikasifk' => $dataSV]);
            }

            //***** Monitoring Pengajuan Pelatihan Detail *****
            if ($request['norecrealisasi'] != '') {
                $dataRR = new RiwayatRealisasi();
                $dataRR->norec = $dataRR->generateNewId();
                $dataRR->kdprofile = $this->kdProfile;
                $dataRR->statusenabled = true;
                $dataRR->objectkelompoktransaksifk = $this->kelompokTransaksi('VERIFIKASI USULAN PERMINTAAN BARANG / JASA');
                $dataRR->objectstrukrealisasifk = $request['norecrealisasi'];
                $dataRR->tglrealisasi = date('Y-m-d H:i:s');
                $dataRR->objectpetugasfk = $this->getUserId();
                $dataRR->noorderintern = $request['nousulan'];
                $dataRR->rencanaorderfk = $request['norec'];
                $dataRR->keteranganlainnya = 'Verifikasi RUPB Pengelola Urusan';
                $dataRR->save();
            }

            //## Logging User
            $this->LOGGING('Verifikasi RUPB Pengelola Urusan', $dataSV,'strukverifikasi_t', 'VERIFIKASI USULAN PERMINTAAN BARANG / JASA' . ', Dengan No Verifikasi : ' . $noVerifikasi);

            DB::commit();
            $result = array(
                "status" => 201,
                "message" => 'Verifikasi Berhasil',
                "noplanning" => $request['nousulan'],
                "data" => $dataSV,
                "as" => 'as@epic',
            );

        } catch (\Exception $e) {
            DB::rollBack();
            $result = array(
                "status" => 400,
                "message"  => 'Something Went Wrong',
                "nokirim" => $request['nousulan'],
                "data" => $e->getMessage(),
                "as" => 'as@epic',
            );
        }

        return $this->respond($result,$result['status'],$result['message']);
    }

    public function getDaftarIPSRS(Request $request)
    {
        $dateRange = [$request->tglAwal, $request->tglAkhir];

        $idKelomTranskasi = $this->kelompokTransaksi('IPSRS');

        $data = DB::table('strukplanning_t as stp')
                ->join('ruangan_m as rg' ,'rg.id' , 'stp.objectruanganfk')
                ->leftJoin('pegawai_m as pg','pg.id','stp.objectpegawaipjawabfk')
                ->leftJoin('pegawai_m as pg2' ,'pg2.id' ,'stp.objectpegawaipjawabevaluasifk')
                ->leftJoin('pegawai_m as pg3','pg3.id','stp.narasumberfk')
                ->leftJoin('statuspekerjaan_m as sttp','sttp.id' ,'stp.objectstatuspekerjaanfk')
                ->leftJoin('jenispekerjaan_m as jpk','jpk.id', 'stp.objectjenispekerjaanfk')
                ->leftJoin('jenispekerjaan_m as jpk2','jpk2.id','stp.objectjenisalatfk')
                ->leftJoin('ruangan_m as r','r.id','stp.objectruangantujuanfk')
                ->selectRaw(
            "stp.norec, rg.namaruangan, stp.tglplanning,stp.objectpegawaipjawabfk,pg.namalengkap, stp.startdate, stp.duedate, stp.rincianexecuteplanning_askep, stp.pelapor, 
                    stp.deskripsiplanning, stp.keteranganverifikasi, sttp.namaexternal,stp.signdate, stp.worklist, stp.keteranganverifikasi, 
                    pg2.nama as namainspektor, pg2.id as namainspektorfk,pg3.nama as namapelapor,stp.objectstatuspekerjaanfk,sttp.reportdisplay as statuspekerjaan,stp.objectjenispekerjaanfk AS idjeniskerusakan,
                    jpk.reportdisplay as jeniskerusakan,stp.objectjenisalatfk,jpk2.reportdisplay as jenisalat,r.namaruangan as namaruangantujuan"
                )
                ->where('stp.kdprofile',$this->kdProfile)
                ->where('stp.statusenabled',true)
                ->where('stp.objectkelompoktransaksifk', $idKelomTranskasi)
                ->whereBetween(DB::raw('stp.tglplanning::date'), $dateRange);

                if (isset($request['ruanganfk']) && $request['ruanganfk'] != "" && $request['ruanganfk'] != "undefined") {
                    $data = $data->where('stp.objectruanganfk', $request['ruanganfk']);
                }
                if (isset($request['jenisalatfk']) && $request['jenisalatfk'] != "" && $request['jenisalatfk'] != "undefined") {
                    $data = $data->where('stp.objectjenisalatfk', $request['jenisalatfk']);
                }

            $data = $data->get();

        $result = [];

        foreach ($data as $item) {
             $details = DB::table('strukplanningdetail_t as spd')
                     ->leftJoin('pegawai_m as pr', 'pr.id' ,'spd.pegawaifk')
                     ->select('spd.pegawaifk as id','pr.namalengkap')
                     ->where('spd.kdprofile',$this->kdProfile)
                     ->where('spd.statusenabled', true)
                     ->where('spd.noplanningfk',$item->norec)
                     ->get();
        
            $result[] = array(
                'norec' => $item->norec,
                'namaruangan' => $item->namaruangan,
                'tglplanning' => $item->tglplanning,
                'objectpegawaipjawabfk' => $item->objectpegawaipjawabfk,
                'namalengkap' => $item->namalengkap,
                'startdate' => $item->startdate,
                'duedate' => $item->duedate,
                'rincianexecuteplanning_askep' => $item->rincianexecuteplanning_askep,
                'pelapor' => $item->pelapor,
                'deskripsiplanning' => $item->deskripsiplanning,
                'keteranganverifikasi' => $item->keteranganverifikasi,
                'signdate' => $item->signdate,
                'worklist' => $item->worklist,
                'namainspektor' => $item->namainspektor,
                'namainspektorfk' => $item->namainspektorfk,
                'namapelapor' => $item->namapelapor,
                'objectstatuspekerjaanfk' => $item->objectstatuspekerjaanfk,
                'statuspekerjaan' => $item->statuspekerjaan,
                'idjeniskerusakan' => $item->idjeniskerusakan,
                'jeniskerusakan' => $item->jeniskerusakan,
                'jenisalat' => $item->jenisalat,
                'objectjenisalatfk' => $item->objectjenisalatfk,
                'details' => $details,
                'namaruangantujuan' => $item->namaruangantujuan
            );
        }

        
        $results = array(
            'data' => $result,
            'as' => 'as@epic',
        );

        return $this->respond($results);
    }

    public function SavePermohonan(Request $request)
    {
      
        DB::beginTransaction();
        try {
            if ($request['norec'] == '') {
                $newCOA = new StrukPlanning();
                $norecHead = $newCOA->generateNewId();
                $newCOA->kdprofile = $this->kdProfile;
                $newCOA->norec = $norecHead;
                $newCOA->statusenabled = 1;
            } else {

                $newCOA =  StrukPlanning::where('norec', $request['norec'])->first();
            }
            $newCOA->tglplanning = $request['tglplanning'];
            $newCOA->objectruanganfk = $request['ruangandesc'];
            $newCOA->objectkelompoktransaksifk = $this->kelompokTransaksi('IPSRS');
            $newCOA->rincianexecuteplanning_askep = $request['rincian'];
            $newCOA->pelapor = $request['pelapor'];
            $newCOA->narasumberfk = $request['idpelapor'];
            $newCOA->objectruangantujuanfk = $request['ruangantujuan'];
            $newCOA->save();

            DB::commit();
            $result = array(
                "status" => 201,
                "as" => 'as@epic',
                "message" => "Simpan Data Berhasil"
            );

        } catch (\Exception $e) {
            DB::rollBack();
            $result = array(
                "status" => 400,
                "as" => 'as@epic',
                "message" => "Something Went Wrong"
            );
        }
     
        return $this->respond($result,$result['status'],$result['message']);
    }

    public function SavePengerjaanPermohonan(Request $request)
    {
        DB::beginTransaction();

        try {

            if ($request['norec'] == '') {
                return $this->respond("ASUP");
                $newCOA = new StrukPlanning();
                $norecHead = $newCOA->generateNewId();
                $newCOA->kdprofile = $this->kdProfile;
                $newCOA->norec = $norecHead;
                $newCOA->statusenabled = true;
            } else {
                $newCOA =  StrukPlanning::where('norec', $request['norec'])->where('kdprofile', $this->kdProfile)->first();
                $Spd = StrukPlanningDetail::where('noplanningfk', $request['norec'])->delete();
            }
            $newCOA->startdate = $request['strukplanning']['tglmulai'];
            $newCOA->duedate = $request['strukplanning']['tglselesai'];
            $newCOA->objectstatuspekerjaanfk = $request['strukplanning']['status'];
            $newCOA->deskripsiplanning = $request['strukplanning']['worklist'];
            $newCOA->keteranganverifikasi = $request['strukplanning']['identifikasikerusakan'];
            $newCOA->objectpegawaipjawabevaluasifk = $request['strukplanning']['penanngungjawab'];
            $newCOA->objectpegawaipjawabfk = $request['strukplanning']['penanngungjawab'];
            $newCOA->objectjenispekerjaanfk = $request['strukplanning']['jeniskerusakan'];
            $newCOA->objectjenisalatfk = $request['strukplanning']['jenisalat'];
            $newCOA->save();
            $norecHead2 = $newCOA->norec;

            foreach ($request['datapegawai'] as $items) {
                $Spd = new StrukPlanningDetail();
                $norecDetail = $newCOA->generateNewId();
                $Spd->kdprofile = $this->kdProfile;
                $Spd->norec = $norecDetail;
                $Spd->statusenabled = true;
                $Spd->pegawaifk = $items['idpegawai'];
                $Spd->noplanningfk = $norecHead2;
                $Spd->save();
            }

            DB::commit();
            $result = array(
                "status" => 201,
                "data" => $newCOA,
                "message" => "Simpan Data Berhasil",
                "as" => 'as@epic',
            );

        } catch (Exception $e) {
            DB::rollBack();
            $result = array(
                "status" => 400,
                "data" => $e->getMessage(),
                "message" => "Something Went Wrong",
                "as" => 'as@epic',
            );
        }
       
        return $this->respond($result,$result['status'],$result['message']);
    }

    public function HapusPermohonanIPSRS(Request $request)
    {

        DB::beginTransaction();
        try {
            StrukPlanning::where('norec', $request['norec'])->where('kdprofile', $this->kdProfile)->update(['statusenabled' => false]);

            // DB::commit();
            $result = array(
                'status' => 201,
                'message' => "Hapus Data Berhasil",
                'as' => 'dy@epic',
            );
        } catch (\Exception $e) {
            DB::rollBack();
            $result = array(
                'status' => 400,
                'message' => "Something Went Wrong",
                'as' => 'dy@epic',
            );
        }

        return $this->respond($result,$result['status'],$result['message']);
    }

    public function dropdownProduk(Request $r)
    {

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

        $dataProduk = DB::table('produk_m as pr')
        ->JOIN('detailjenisproduk_m as djp', 'djp.id', '=', 'pr.objectdetailjenisprodukfk')
        ->JOIN('jenisproduk_m as jp', 'jp.id', '=', 'djp.objectjenisprodukfk')
        ->leftJOIN('satuanstandar_m as ss', 'ss.id', '=', 'pr.objectsatuanstandarfk')
        ->JOIN('stokprodukdetail_t as spd', 'spd.objectprodukfk', '=', 'pr.id')
        ->select(
            'pr.id',
            'pr.namaproduk',
            'ss.id as ssid',
            'ss.satuanstandar',
            'pr.namaexternal',
            'pr.statusenabled'
        )
        ->where('pr.kdprofile', $this->kdProfile)
        ->where('pr.statusenabled', true);

        if (isset($r['limit']) && $r['limit'] != '') {
            $dataProduk = $dataProduk->limit($r['limit']);
        }
        if (isset($r['ruanganfk']) && $r['ruanganfk'] != '') {
            $dataProduk = $dataProduk->where('spd.objectruanganfk', $r['ruanganfk']);
        }
        if (isset($r['namaproduk']) && $r['namaproduk'] != '') {
            $searchTerm = '%' . $r['namaproduk'] . '%';
            $dataProduk = $dataProduk->where(function ($query) use ($searchTerm) {
                $query->where('pr.namaproduk', 'ilike', $searchTerm)
                    ->orWhere('pr.namaexternal', 'ilike', $searchTerm)
                    ->orWhere('pr.id', 'ilike', $searchTerm)
                    ->orWhere('pr.kdproduk', 'ilike', $searchTerm);
            });
        }

        $dataProduk = $dataProduk->groupBy('pr.id', 'pr.namaproduk', 'ss.id', 'ss.satuanstandar');
        $dataProduk = $dataProduk->orderBy('pr.namaproduk');
        $dataProduk = $dataProduk->get();

        $dataProdukResult = [];

        foreach ($dataProduk as $item) {
            $satuanKonversi = [];
            foreach ($dataKonversiProduk  as $item2) {
                if ($item->id == $item2->objekprodukfk) {
                    $stok = DB::select(
                        DB::raw("
                        select sum(qtyproduk) as stok
                        from stokprodukdetail_t                        
                        where kdprofile = $this->kdProfile and objectprodukfk =:produkId 
                        and statusenabled = 't'
                        and objectruanganfk =:ruanganid"),
                        array(
                            'produkId' => $item->id,
                            'ruanganid' => $r['ruanganfk'],
                        )
                    );
                    $satuanKonversi[] = array(
                        'stok' => count($stok) > 0 ? $stok[0]->stok : 0,
                        'ssid' =>   $item2->satuanstandar_tujuan,
                        'satuanstandar' =>   $item2->satuanstandar2,
                        'nilaikonversi' =>   $item2->nilaikonversi,
                    );
                }
            }

            $stok = DB::select(
                DB::raw("
                select sum(qtyproduk) as stok
                from stokprodukdetail_t                        
                where kdprofile = $this->kdProfile 
                and objectprodukfk =:produkId 
                and statusenabled = 't'
                and objectruanganfk =:ruanganid"),
                array(
                    'produkId' => $item->id,
                    'ruanganid' => $r['ruanganfk'],
                )
            );

            $dataProdukResult[] = array(
                'id' =>   $item->id,
                'namaproduk' => $item->namaproduk,
                'stok' =>  count($stok) > 0 ? $stok[0]->stok : 0,
                'generik' =>   $item->namaexternal,
                'ssid' =>   $item->ssid,
                'satuanstandar' =>   $item->satuanstandar,
                'konversisatuan' => $satuanKonversi,
                'statusenabled' => $item->statusenabled,
            );
        }
        return $this->respond($dataProdukResult);
    }

    public function saveKirimBarangRuangan(Request $request)
    {

        DB::beginTransaction();

        $idProfile = $this->kdProfile;
        if ($request['strukkirim']['jenispermintaanfk'] == 2) {
            $noKirim = $this->generateCodeBySeqTable(new StrukKirim, 'nokirim', 14, 'TRF-' . $this->getDateTime()->format('ym'), $idProfile);
        } else {
            $noKirim = $this->generateCodeBySeqTable(new StrukKirim, 'nokirim', 14, 'AMP-' . $this->getDateTime()->format('ym'), $idProfile);
        }
        if ($noKirim == '') {
            DB::rollBack();
            $result = array(
                "status" => 400,
                "NOKIRIM" => $noKirim,
                "message"  => 'Gagal mengumpukan data, Coba lagi.!',
                "as" => 'as@epic',
            );
            return $this->respond($result, $result['status'],$result['message']);
        }
       
        $ruanganAsal = Ruangan::where('kdprofile',$this->kdProfile)->where('statusenabled',true)
                        ->where('id', $request['strukkirim']['objectruanganfk'])->first();
        $ruanganTujuan = Ruangan::where('kdprofile', $this->kdProfile)->where('statusenabled', true)
                        ->where('id', $request['strukkirim']['objectruangantujuanfk'])->first();
        $strRuanganAsal = $ruanganAsal->namaruangan;
        $strRuanganTujuan = $ruanganTujuan->namaruangan;

        try {
            if ($request['strukkirim']['noreckirim'] == '') {
                if ($request['strukkirim']['norecOrder'] != '') {
                    StrukOrder::where('norec', $request['strukkirim']['norecOrder'])->where('kdprofile', $idProfile)->update(['statusorder' => 1]);
                }
                
                $dataSK = new StrukKirim;
                $dataSK->norec = $dataSK->generateNewId();
                $dataSK->nokirim = $noKirim;
 
            } else {
          
                $ruanganStrukKirimSebelumnya = DB::select(
                    DB::raw("
                         select  ru.id, ru.namaruangan
                         from ruangan_m as ru 
                         where ru.kdprofile = $idProfile and ru.id=(select objectruangantujuanfk from strukkirim_t where norec = :norec)"),
                    array(
                        'norec' => $request['strukkirim']['noreckirim'],
                    )
                );

                $strNmRuanganStrukKirimSebelumnya = '';
                $strIdRuanganStrukKirimSebelumnya = '';
                $strNmRuanganStrukKirimSebelumnya = $ruanganStrukKirimSebelumnya[0]->namaruangan;
                $strIdRuanganStrukKirimSebelumnya = $ruanganStrukKirimSebelumnya[0]->id;
                //#1
                $dataSK = StrukKirim::where('norec', $request['strukkirim']['noreckirim'])->where('kdprofile', $idProfile)->first();
                $strukKirimOld = StrukKirim::where('norec', $request['strukkirim']['noreckirim'])->where('kdprofile', $idProfile)->first();
                KartuStok::where('keterangan',  'Kirim Amprahan, dari Ruangan ' . $strRuanganAsal . ' ke Ruangan ' . $strRuanganTujuan . ' No Kirim: ' .  $dataSK->nokirim)
                    ->update([
                        'flagfk' => null
                    ]);

                if ($request['strukkirim']['objectruanganfk'] == $strukKirimOld->objectruanganfk) {
                    $getDetails = KirimProduk::where('nokirimfk', $request['strukkirim']['noreckirim'])
                    ->where('kdprofile', $idProfile)
                        ->where('qtyproduk', '>', 0)
                        ->get();
                    foreach ($getDetails as $item) {
                        //PENGIRIM
                        $saldoAwalPengirim = 0;
                        $noterimaS = $item['nostrukterimafk'];
                        $ruangKirim = $request['strukkirim']['objectruanganfk'];
                        $dataSaldoAwalK = collect(DB::select("
                            select sum(qtyproduk) as qty from stokprodukdetail_t 
                            where kdprofile = $idProfile 
                            and objectruanganfk=$ruangKirim 
                            and objectprodukfk=$item->objectprodukfk
                            -- and nostrukterimafk = '$noterimaS'
                        "))->first();

                        $saldoAwalPengirim = (float)$dataSaldoAwalK->qty + (float)$item->qtyproduk;
                        $tambah = StokProdukDetail::where('nostrukterimafk', $noterimaS)
                            ->where('kdprofile', $kdProfile)
                            ->where('objectruanganfk', $strukKirimOld->objectruanganfk)
                            ->where('objectprodukfk', $item->objectprodukfk)
                            ->first();

                        \DB::table('stokprodukdetail_t')
                        ->where('kdprofile', $kdProfile)
                            ->where('norec', $tambah->norec)
                            ->where('objectruanganfk', $ruangKirim)
                            ->where('objectprodukfk', $item->objectprodukfk)
                            ->lockForUpdate()
                            ->increment('qtyproduk',  (float)$item->qtyproduk);

                        $tglnow =  date('Y-m-d H:i:s');
                        $tglUbah = date('Y-m-d H:i:s', strtotime('-10 seconds', strtotime($tglnow)));

                        //## KartuStok
                        $newKSKir = new KartuStok();
                        $norecKSKir = $newKSKir->generateNewId();
                        $newKSKir->norec = $norecKSKir;
                        $newKSKir->kdprofile = $idProfile;
                        $newKSKir->statusenabled = true;
                        $newKSKir->jumlah = (float)$item->qtyproduk;
                        $newKSKir->keterangan = 'Ubah Kirim Barang, dari Ruangan ' . $strRuanganAsal . ' ke Ruangan ' . $strNmRuanganStrukKirimSebelumnya . ' No Kirim: ' .  $dataSK->nokirim;
                        $newKSKir->produkfk = $item->objectprodukfk;
                        $newKSKir->ruanganfk = $request['strukkirim']['objectruanganfk'];
                        $newKSKir->saldoawal = (float)$saldoAwalPengirim;
                        $newKSKir->status = 1;
                        $newKSKir->tglinput = $tglUbah; //date('Y-m-d H:i:s');
                        $newKSKir->tglkejadian = $tglUbah; //date('Y-m-d H:i:s');
                        $newKSKir->nostrukterimafk =  $item->nostrukterimafk;
                        $newKSKir->norectransaksi = $request['strukkirim']['noreckirim'];
                        $newKSKir->tabletransaksi = 'strukkirim_t';
                        $newKSKir->save();

                        if ($request['strukkirim']['jenispermintaanfk'] == 2) {
                            //PENERIMA
                            $saldoAwalPenerima = 0;
                            $dataSaldoAwalT = collect(DB::select("
                                    select sum(qtyproduk) as qty from stokprodukdetail_t 
                                    where objectruanganfk='$strIdRuanganStrukKirimSebelumnya' 
                                    and objectprodukfk=$item->objectprodukfk
                                    -- and nostrukterimafk = '$noterimaS'
                                "))->first();
                            $saldoAwalPenerima = (float)$dataSaldoAwalT->qty - (float)$item->qtyproduk;
                            if ($dataSK->jenispermintaanfk == 2) {
                                $kurang = StokProdukDetail::where('nostrukterimafk', $noterimaS)
                                    ->where('kdprofile', $kdProfile)
                                    ->where('objectruanganfk', $strIdRuanganStrukKirimSebelumnya)
                                    ->where('objectprodukfk', $item->objectprodukfk)
                                    ->first();

                                \DB::table('stokprodukdetail_t')
                                ->where('norec', $kurang->norec)
                                    ->where('objectruanganfk', $request['strukkirim']['objectruangantujuanfk'])
                                    ->where('objectprodukfk', $item->objectprodukfk)
                                    ->lockForUpdate()
                                    ->decrement('qtyproduk',  (float)$item->qtyproduk);

                                $tglnow1 =  date('Y-m-d H:i:s');
                                $tglUbah1 = date('Y-m-d H:i:s', strtotime('-10 seconds', strtotime($tglnow1)));

                                //## KartuStok
                                $newKSPe = new KartuStok();
                                $norecKSPe = $newKSPe->generateNewId();
                                $newKSPe->norec = $norecKSPe;
                                $newKSPe->kdprofile = $kdProfile;
                                $newKSPe->statusenabled = true;
                                $newKSPe->jumlah = (float)$item->qtyproduk;
                                $newKSPe->keterangan = 'Ubah Terima Barang, dari Ruangan ' . $strRuanganAsal . ' ke Ruangan ' . $strNmRuanganStrukKirimSebelumnya . ' No Kirim: ' .  $dataSK->nokirim;
                                $newKSPe->produkfk = $item->objectprodukfk;
                                $newKSPe->ruanganfk = $strIdRuanganStrukKirimSebelumnya; //$request['strukkirim']['objectruangantujuanfk'];
                                $newKSPe->saldoawal = (float)$saldoAwalPenerima;
                                $newKSPe->status = 0;
                                $newKSPe->tglinput = $tglUbah1; //date('Y-m-d H:i:s');
                                $newKSPe->tglkejadian = $tglUbah1; //date('Y-m-d H:i:s');
                                $newKSPe->nostrukterimafk =  $item->nostrukterimafk;
                                $newKSPe->norectransaksi = $request['strukkirim']['noreckirim'];
                                $newKSPe->tabletransaksi = 'strukkirim_t';
                                $newKSPe->save();
                            } else {
                            }
                        } elseif ($strukKirimOld->jenispermintaanfk != $request['strukkirim']['jenispermintaanfk']) {
                            $saldoAwalPenerima = 0;
                            $dataSaldoAwalT = collect(DB::select("
                                select sum(qtyproduk) as qty from stokprodukdetail_t 
                                where kdprofile = $idProfile 
                                and objectruanganfk = $strIdRuanganStrukKirimSebelumnya 
                                and objectprodukfk = $item->objectprodukfk
                                -- and nostrukterimafk = '$noterimaS'
                            "));
                            $saldoAwalPenerima = (float)$dataSaldoAwalT->qty - (float)$item->qtyproduk;
                            $kurangin = StokProdukDetail::where('nostrukterimafk', $noterimaS)
                                ->where('kdprofile', $kdProfile)
                                ->where('objectruanganfk', $request['strukkirim']['objectruangantujuanfk'])
                                ->where('objectprodukfk', $item->objectprodukfk)
                                ->first();

                            \DB::table('stokprodukdetail_t')
                            ->where('kdprofile', $kdProfile)
                                ->where('norec', $kurangin->norec)
                                ->where('objectruanganfk', $strIdRuanganStrukKirimSebelumnya)
                                ->where('objectprodukfk', $item->objectprodukfk)
                                ->lockForUpdate()
                                ->decrement('qtyproduk',  (float)$item->qtyproduk);

                            $tglnow1 =  date('Y-m-d H:i:s');
                            $tglUbah1 = date('Y-m-d H:i:s', strtotime('-10 seconds', strtotime($tglnow1)));

                            //## KartuStok
                            $newKS = new KartuStok();
                            $norecKS = $newKS->generateNewId();
                            $newKS->norec = $norecKS;
                            $newKS->kdprofile = $idProfile;
                            $newKS->statusenabled = true;
                            $newKS->jumlah = (float)$item->qtyproduk;
                            $newKS->keterangan = 'Ubah Terima Barang, dari Ruangan ' . $strRuanganAsal . ' ke Ruangan ' . $strNmRuanganStrukKirimSebelumnya . ' No Kirim: ' .  $dataSK->nokirim;
                            $newKS->produkfk = $item->objectprodukfk;
                            $newKS->ruanganfk = $strIdRuanganStrukKirimSebelumnya; //$request['strukkirim']['objectruangantujuanfk'];
                            $newKS->saldoawal = (float)$saldoAwalPenerima; //- (float)$item->qtyproduk;
                            $newKS->status = 0;
                            $newKS->tglinput = $tglUbah1; //date('Y-m-d H:i:s');
                            $newKS->tglkejadian = $tglUbah1; //date('Y-m-d H:i:s');
                            $newKS->nostrukterimafk =  $item->nostrukterimafk;
                            $newKS->norectransaksi = $request['strukkirim']['noreckirim'];
                            $newKS->tabletransaksi = 'strukkirim_t';
                            $newKS->save();
                        } else {
                        }

                        KirimProduk::where('nokirimfk', $request['strukkirim']['noreckirim'])->where('kdprofile', $kdProfile)->delete();
                    }
                } else {

                    $ruanganAsal = Ruangan::where('id', $strukKirimOld->objectruanganfk)->where('kdprofile', $idProfile)->first();
                    $ruanganTujuan = Ruangan::where('id', $strukKirimOld->objectruangantujuanfk)->where('kdprofile', $idProfile)->first();
                    $getDetails = KirimProduk::where('nokirimfk', $request['strukkirim']['noreckirim'])
                    ->where('kdprofile', $idProfile)
                        ->where('qtyproduk', '>', 0)
                        ->get();

                    foreach ($getDetails as $item) {
                        //PENGIRIM
                        $saldoAwalPengirim = 0;
                        $noterimaS = $item['nostrukterimafk'];
                        $dataSaldoAwalK = collect(DB::select("
                            select sum(qtyproduk) as qty from stokprodukdetail_t 
                            where kdprofile = $idProfile and objectruanganfk=$strukKirimOld->objectruanganfk 
                            and objectprodukfk=$item->objectprodukfk
                            -- and nostrukterimafk = '$noterimaS'
                        "))->first();
                        $saldoAwalPengirim = (float)$dataSaldoAwalK->qty + (float)$item->qtyproduk;
                        $tambah = StokProdukDetail::where('nostrukterimafk', $noterimaS)
                            ->where('kdprofile', $idProfile)
                            ->where('objectruanganfk', $strukKirimOld->objectruanganfk)
                            ->where('objectprodukfk', $item->objectprodukfk)
                            ->first();
                        \DB::table('stokprodukdetail_t')
                        ->where('kdprofile', $kdProfile)
                            ->where('norec', $tambah->norec)
                            ->where('objectruanganfk', $strukKirimOld->objectruanganfk)
                            ->where('objectprodukfk', $item->objectprodukfk)
                            ->lockForUpdate()
                            ->increment('qtyproduk', (float)$item->qtyproduk);

                        $tglnow =  date('Y-m-d H:i:s');
                        $tglUbah = date('Y-m-d H:i:s', strtotime('-10 seconds', strtotime($tglnow)));

                        //## KartuStok
                        $newKS = new KartuStok();
                        $norecKS = $newKS->generateNewId();
                        $newKS->norec = $norecKS;
                        $newKS->kdprofile = $idProfile;
                        $newKS->statusenabled = true;
                        $newKS->jumlah = (float)$item->qtyproduk;
                        $newKS->keterangan = 'Ubah Kirim Barang, dari Ruangan ' . $ruanganAsal->namaruangan . ' ke Ruangan ' . $ruanganTujuan->namaruangan . ' No Kirim: ' .  $dataSK->nokirim;
                        $newKS->produkfk = $item->objectprodukfk;
                        $newKS->ruanganfk = $strukKirimOld->objectruanganfk; //$request['strukkirim']['objectruanganfk'];
                        $newKS->saldoawal = (float)$saldoAwalPengirim;
                        $newKS->status = 1;
                        $newKS->tglinput = $tglUbah; //date('Y-m-d H:i:s');
                        $newKS->tglkejadian = $tglUbah; //date('Y-m-d H:i:s');
                        $newKS->nostrukterimafk =  $item->nostrukterimafk;
                        $newKS->norectransaksi = $request['strukkirim']['noreckirim'];
                        $newKS->tabletransaksi = 'strukkirim_t';
                        $newKS->save();

                        if ($request['strukkirim']['jenispermintaanfk'] == 2) {
                            //PENERIMA
                            $saldoAwalPenerima = 0;
                            $dataSaldoAwalT = collect(DB::select("
                                select sum(qtyproduk) as qty from stokprodukdetail_t 
                                where kdprofile = $idProfile and objectruanganfk=$strIdRuanganStrukKirimSebelumnya 
                                and objectprodukfk=$item->objectprodukfk
                                -- and nostrukterimafk = '$noterimaS'
                            "))->first();
                            $saldoAwalPenerima = (float)$dataSaldoAwalT->qty - (float)$item->qtyproduk;
                            if ($dataSK->jenispermintaanfk == 2) {
                                $kurang = StokProdukDetail::where('nostrukterimafk', $noterimaS)
                                    ->where('kdprofile', $kdProfile)
                                    ->where('objectruanganfk', $strIdRuanganStrukKirimSebelumnya)
                                    ->where('objectprodukfk', $item->objectprodukfk)
                                    ->first();

                                \DB::table('stokprodukdetail_t')
                                ->where('kdprofile', $kdProfile)
                                    ->where('norec', $kurang->norec)
                                    ->where('objectruanganfk', $strIdRuanganStrukKirimSebelumnya)
                                    ->where('objectprodukfk', $item->objectprodukfk)
                                    ->lockForUpdate()
                                    ->decrement('qtyproduk', (float)$item->qtyproduk);

                                $tglnow1 =  date('Y-m-d H:i:s');
                                $tglUbah1 = date('Y-m-d H:i:s', strtotime('-10 seconds', strtotime($tglnow1)));

                                //## KartuStok
                                $newKSPer = new KartuStok();
                                $norecKSPer = $newKS->generateNewId();
                                $newKSPer->norec = $norecKSPer;
                                $newKSPer->kdprofile = $idProfile;
                                $newKSPer->statusenabled = true;
                                $newKSPer->jumlah = (float)$item->qtyproduk;
                                $newKSPer->keterangan = 'Ubah Terima Barang, dari Ruangan ' . $ruanganAsal->namaruangan . ' ke Ruangan ' . $ruanganTujuan->namaruangan . ' No Kirim: ' .  $dataSK->nokirim;
                                $newKSPer->produkfk = $item->objectprodukfk;
                                $newKSPer->ruanganfk = $strukKirimOld->objectruangantujuanfk; //$strIdRuanganStrukKirimSebelumnya;//$request['strukkirim']['objectruangantujuanfk'];
                                $newKSPer->saldoawal = (float)$saldoAwalPenerima; //- (float)$item->qtyproduk;
                                $newKSPer->status = 0;
                                $newKSPer->tglinput = $tglUbah1; //date('Y-m-d H:i:s');
                                $newKSPer->tglkejadian = $tglUbah1; //date('Y-m-d H:i:s');
                                $newKSPer->nostrukterimafk =  $item->nostrukterimafk;
                                $newKSPer->norectransaksi = $request['strukkirim']['noreckirim'];
                                $newKSPer->tabletransaksi = 'strukkirim_t';
                                $newKSPer->save();
                            } else {
                            }
                        } else {
                        }

                        KirimProduk::where('nokirimfk', $request['strukkirim']['noreckirim'])->where('kdprofile', $kdProfile)->delete();
                    }
                }
            }

            $dataSK->kdprofile = $idProfile;
            $dataSK->statusenabled = true;
            $dataSK->objectpegawaipengirimfk = $request['strukkirim']['objectpegawaipengirimfk'];
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

            foreach ($request['details'] as $item) {
                //cari satuan standar
                $noterimaS = $item['nostrukterimafk'];
                $satuanstandar = Produk::where('statusenabled',true)->where('kdprofile',$this->kdProfile)->where('id',$item['produkfk'])->first();
                $satuanstandarfk = $satuanstandar->objectsatuanstandarfk;
        
                if ($request['strukkirim']['jenispermintaanfk'] == 2) {
                    //PENGIRIM
                    $ru = $request['strukkirim']['objectruanganfk'];
                    $dataSaldoAwalK = StokProdukDetail::where('kdprofile',$this->kdProfile)->where('objectruanganfk', $request['strukkirim']['objectruanganfk'])
                            ->where('objectprodukfk',$item['produkfk'])
                            ->selectRaw('qtyproduk as qty,nostrukterimafk,norec,objectasalprodukfk as asalprodukfk,
                                        hargadiscount,harganetto1 as harganetto,harganetto1 as hargasatuan')
                            ->get();
     
                    //PENERIMA
                    $saldoAwalPenerimaIn = 0;
                    $saldoAwalPengirimIn = 0;
                    $jumlah = (float)$item['jumlah'] * (float)$item['nilaikonversi'];
                    $ruanganPer = $request['strukkirim']['objectruangantujuanfk'];
                    $dataSaldoAwalT = collect(DB::select("
                        select sum(qtyproduk) as qty from stokprodukdetail_t 
                        where kdprofile = $idProfile 
                        and objectruanganfk = $ruanganPer 
                        and objectprodukfk = $item[produkfk]
                    "))->first();
                    
                    $rus = $request['strukkirim']['objectruanganfk'];
                    $dataSaldoAwaPengirim = collect(DB::select("
                            select sum(qtyproduk) as qty from stokprodukdetail_t 
                            where kdprofile = $idProfile 
                            and objectruanganfk = $rus 
                            and objectprodukfk = $item[produkfk]
                    "))->first();
            
                    $saldoAkhirPenerima = (float)$dataSaldoAwalT->qty + $jumlah;
                    $saldoAkhirPengirimIn = $dataSaldoAwaPengirim->qty - $jumlah;

                    if ($saldoAkhirPengirimIn < 0) {
                        $transMessage = "Simpan Kirim Barang Gagal, Stok Produk " . $item['namaproduk'] . ", ada " . $jumlah . " Data Stok Kurang Dari Qty Resep !";
                        DB::rollBack();
                        $result = array(
                            "status" => 400,
                            "message"  => $transMessage,
                            "as" => 'as@epic',
                        );
                        return $this->respond($result,$result['status'],$result['message']);
                    }
                    foreach ($dataSaldoAwalK as $items) {
                        if ((float)$items->qty <= $jumlah) {

                            if ((float)$items->qty > 0) {

                                $qtyqtyqty = (float)$items->qty;
                                $dataKP = new KirimProduk;
                                $dataKP->norec = $dataKP->generateNewId();
                                $dataKP->kdprofile = $idProfile;
                                $dataKP->statusenabled = true;
                                $dataKP->objectasalprodukfk = $items->asalprodukfk;
                                $dataKP->hargadiscount = $items->hargadiscount;
                                $dataKP->harganetto = $items->harganetto;
                                $dataKP->hargapph = 0;
                                $dataKP->hargappn = 0;
                                $dataKP->hargasatuan = $items->hargasatuan;
                                $dataKP->hargatambahan = 0;
                                $dataKP->hasilkonversi = $item['nilaikonversi'];;
                                $dataKP->objectprodukfk = $item['produkfk'];
                                $dataKP->objectprodukkirimfk = $item['produkfk'];
                                $dataKP->nokirimfk = $norecSK;
                                $dataKP->persendiscount = 0;
                                $dataKP->qtyproduk = $qtyqtyqty;
                                $dataKP->qtyprodukkonfirmasi = $qtyqtyqty;
                                $dataKP->qtyprodukretur = 0;
                                $dataKP->qtyorder = $item['qtyorder'];
                                $dataKP->qtyprodukterima = $qtyqtyqty;
                                $dataKP->nostrukterimafk = $items->nostrukterimafk;
                                $dataKP->objectruanganfk = $request['strukkirim']['objectruangantujuanfk'];
                                $dataKP->objectruanganpengirimfk = $request['strukkirim']['objectruanganfk'];
                                $dataKP->satuan = '-';
                                $dataKP->objectsatuanstandarfk = $satuanstandarfk; //$item['satuanstandarfk'];
                                $dataKP->satuanviewfk = $item['satuanviewfk'];
                                $dataKP->tglpelayanan = date($request['strukkirim']['tglkirim']);
                                $dataKP->qtyprodukterimakonversi = $qtyqtyqty;
                                $dataKP->save();

                                $jumlah = $jumlah - (float)$items->qty;
    
                                DB::table('stokprodukdetail_t')
                                    ->where('kdprofile', $this->kdProfile)
                                    ->where('norec', $items->norec)
                                    ->lockForUpdate()
                                    ->decrement('qtyproduk', (float)$items->qty);


                                $dataStok = StokProdukDetail::where('norec', $items->norec)->first();

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
                                $dataNewSPD->qtyproduk = $qtyqtyqty;
                                $dataNewSPD->qtyprodukonhand = 0;
                                $dataNewSPD->qtyprodukoutext = 0;
                                $dataNewSPD->qtyprodukoutint = 0;
                                $dataNewSPD->objectruanganfk = $request['strukkirim']['objectruangantujuanfk'];
                                $dataNewSPD->nostrukterimafk = $noterimaS;
                                $dataNewSPD->noverifikasifk = $dataStok->noverifikasifk;
                                $dataNewSPD->nobatch = $dataStok->nobatch;
                                $dataNewSPD->tglkadaluarsa = $dataStok->tglkadaluarsa;
                                $dataNewSPD->tglpelayanan = $dataStok->tglpelayanan;
                                $dataNewSPD->tglproduksi = $dataStok->tglproduksi;
                                $dataNewSPD->save();
                            }
                        } else {
                
                            if ((float)$items->qty > 0) {

                                $dataKP = new KirimProduk;
                                $dataKP->norec = $dataKP->generateNewId();
                                $dataKP->kdprofile = $idProfile;
                                $dataKP->statusenabled = true;
                                $dataKP->objectasalprodukfk = $items->asalprodukfk;
                                $dataKP->hargadiscount = $items->hargadiscount;
                                $dataKP->harganetto = $items->harganetto;
                                $dataKP->hargapph = 0;
                                $dataKP->hargappn = 0;
                                $dataKP->hargasatuan = $items->hargasatuan;
                                $dataKP->hargatambahan = 0;
                                $dataKP->hasilkonversi = $item['nilaikonversi'];
                                $dataKP->objectprodukfk = $item['produkfk'];
                                $dataKP->objectprodukkirimfk = $item['produkfk'];
                                $dataKP->nokirimfk = $norecSK;
                                $dataKP->persendiscount = 0;
                                $dataKP->qtyproduk = $jumlah;
                                $dataKP->qtyprodukkonfirmasi = $jumlah;
                                $dataKP->qtyprodukretur = 0;
                                $dataKP->qtyorder = $item['qtyorder'];
                                $dataKP->qtyprodukterima = $jumlah;
                                $dataKP->nostrukterimafk = $items->nostrukterimafk;
                                $dataKP->objectruanganfk = $request['strukkirim']['objectruangantujuanfk'];
                                $dataKP->objectruanganpengirimfk = $request['strukkirim']['objectruanganfk'];
                                $dataKP->satuan = '-';
                                $dataKP->objectsatuanstandarfk = $satuanstandarfk; //$item['satuanstandarfk'];
                                $dataKP->satuanviewfk = $item['satuanviewfk'];
                                $dataKP->tglpelayanan = date($request['strukkirim']['tglkirim']);
                                $dataKP->qtyprodukterimakonversi = $jumlah;
                                $dataKP->save();
                                
                                DB::table('stokprodukdetail_t')
                                    ->where('kdprofile', $this->kdProfile)
                                    ->where('norec', $items->norec)
                                    ->lockForUpdate()
                                    ->decrement('qtyproduk', $jumlah);

                                $dataStok = StokProdukDetail::where('norec', $items->norec)
                                    ->where('kdprofile', $idProfile)
                                    ->first();
                         
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
                                $dataNewSPD->qtyproduk = ((float)$jumlah);
                                $dataNewSPD->qtyprodukonhand = 0;
                                $dataNewSPD->qtyprodukoutext = 0;
                                $dataNewSPD->qtyprodukoutint = 0;
                                $dataNewSPD->objectruanganfk = $request['strukkirim']['objectruangantujuanfk'];
                                $dataNewSPD->nostrukterimafk = $noterimaS;
                                $dataNewSPD->noverifikasifk = $dataStok->noverifikasifk;
                                $dataNewSPD->nobatch = $dataStok->nobatch;
                                $dataNewSPD->tglkadaluarsa = $dataStok->tglkadaluarsa;
                                $dataNewSPD->tglpelayanan = $dataStok->tglpelayanan;
                                $dataNewSPD->tglproduksi = $dataStok->tglproduksi;
                                $dataNewSPD->save();
                    
                                $jumlah = 0;
                            }
                        }
                    }


                    $this->kartu_STOK(array(
                        "saldoawal" => (float)$dataSaldoAwaPengirim->qty,
                        "qtyin" => 0,
                        "qtyout" => ((float)$item['jumlah'] * (float)$item['nilaikonversi']),
                        "saldoakhir" => (float)$dataSaldoAwaPengirim->qty - ((float)$item['jumlah'] * (float)$item['nilaikonversi']),
                        "keterangan" => 'Kirim Barang, dari Ruangan ' . $strRuanganAsal . ' ke Ruangan ' . $strRuanganTujuan . ' No Kirim: ' .  $dataSK->nokirim,
                        "produkfk" => $item['produkfk'],
                        "ruanganfk" => $request['strukkirim']['objectruanganfk'],
                        "tglinput" => date('Y-m-d H:i:s'),
                        "tglkejadian" => date('Y-m-d H:i:s'),
                        "nostrukterimafk" =>  $dataStok->nostrukterimafk,
                        "norectransaksi" => $norecSK,
                        "tabletransaksi" => 'strukkirim_t',
                        "stokprodukdetailfk" => 0,
                        "flagfk" => 2,
                    ));
                    
                    $this->kartu_STOK(array("saldoawal" => $dataSaldoAwalT->qty == null ? 0 : $dataSaldoAwalT->qty,
                        "qtyin" => ((float)$item['jumlah'] * (float)$item['nilaikonversi']),
                        "qtyout" => 0,
                        "saldoakhir" => $saldoAkhirPenerima,
                        "keterangan" => 'Terima Barang, dari Ruangan ' . $strRuanganAsal . ' ke Ruangan ' . $strRuanganTujuan . ' No Kirim: ' .  $dataSK->nokirim,
                        "produkfk" => $item['produkfk'],
                        "ruanganfk" => $request['strukkirim']['objectruangantujuanfk'],
                        "tglinput" => date('Y-m-d H:i:s'),
                        "tglkejadian" => date('Y-m-d H:i:s'),
                        "nostrukterimafk" =>  $dataStok->nostrukterimafk,
                        "norectransaksi" => $norecSK,
                        "tabletransaksi" => 'strukkirim_t',
                        "stokprodukdetailfk" => 0,
                        "flagfk" => 2,
                    ));

                    
                }

                if ($request['strukkirim']['jenispermintaanfk'] == 1) {
                    //PENGIRIM AMPRAHAN
                    $dataSaldoAwalK = DB::select(
                        DB::raw("
                        select qtyproduk as qty,nostrukterimafk,norec,objectasalprodukfk as asalprodukfk,
                        hargadiscount,harganetto1 as harganetto,harganetto1 as hargasatuan
                        from stokprodukdetail_t 
                        where kdprofile = $idProfile and objectruanganfk=:ruanganfk and objectprodukfk=:produkfk and qtyproduk > 0 
                        "),
                        array(
                            'ruanganfk' => $request['strukkirim']['objectruanganfk'],
                            'produkfk' => $item['produkfk'],
                        )
                    );

                    $saldoAwalPengirimAmp = 0;
                    $jumlah = (float)$item['jumlah'] * (float)$item['nilaikonversi'];
                    $qtyProd = 0;
                    $rus = $request['strukkirim']['objectruanganfk'];
                    $dataSaldoAwaPengirim = collect(DB::select("
                            select sum(qtyproduk) as qty from stokprodukdetail_t 
                            where kdprofile = $idProfile 
                            and objectruanganfk = $rus 
                            and objectprodukfk = $item[produkfk]

                    "))->first();

                    $saldoAwalPengirimAmp = $dataSaldoAwaPengirim->qty - $jumlah;
                    if ((float)$saldoAwalPengirimAmp < 0) {
                        $transMessage = "Simpan Kirim Barang Gagal, Stok Produk " . $item['namaproduk'] . ", ada " . (float)$item['jumlah'] . " Data Stok Kurang Dari Qty Resep !";
                        DB::rollBack();
                        $result = array(
                            "status" => 400,
                            "message"  => $transMessage,
                            "as" => 'as@epic',
                        );
                        return $this->respond($result,$result['status'],$result['message']);
                    }
                    foreach ($dataSaldoAwalK as $items) {
                        if ((float)$items->qty <= $jumlah) {
                            $dataKP = new KirimProduk;
                            $dataKP->norec = $dataKP->generateNewId();
                            $dataKP->kdprofile = $idProfile;
                            $dataKP->statusenabled = true;
                            $dataKP->objectasalprodukfk = $items->asalprodukfk;
                            $dataKP->hargadiscount = $items->hargadiscount;
                            $dataKP->harganetto = $items->harganetto;
                            $dataKP->hargapph = 0;
                            $dataKP->hargappn = 0;
                            $dataKP->hargasatuan = $items->hargasatuan;
                            $dataKP->hargatambahan = 0;
                            $dataKP->hasilkonversi = $item['nilaikonversi'];;
                            $dataKP->objectprodukfk = $item['produkfk'];
                            $dataKP->objectprodukkirimfk = $item['produkfk'];
                            $dataKP->nokirimfk = $norecSK;
                            $dataKP->persendiscount = 0;
                            $dataKP->qtyproduk = (float)$items->qty;
                            $dataKP->qtyprodukkonfirmasi = (float)$items->qty;
                            $dataKP->qtyprodukretur = 0;
                            $dataKP->qtyorder = $item['qtyorder'];
                            $dataKP->qtyprodukterima = (float)$items->qty;
                            $dataKP->nostrukterimafk = $items->nostrukterimafk;
                            $dataKP->objectruanganfk = $request['strukkirim']['objectruangantujuanfk'];
                            $dataKP->objectruanganpengirimfk =  $request['strukkirim']['objectruanganfk'];
                            $dataKP->satuan = '-';
                            $dataKP->objectsatuanstandarfk = $satuanstandarfk; //$item['satuanstandarfk'];
                            $dataKP->satuanviewfk = $item['satuanviewfk'];
                            $dataKP->tglpelayanan = date($request['strukkirim']['tglkirim']);
                            $dataKP->qtyprodukterimakonversi = (float)$items->qty;
                            $dataKP->save();

                            $jumlah = $jumlah - (float)$items->qty;

                            $qtyProd = $jumlah;
                            DB::table('stokprodukdetail_t')
                            ->where('kdprofile', $this->kdProfile)
                                ->where('norec', $items->norec)
                                ->lockForUpdate()
                                ->decrement('qtyproduk', (float)$items->qty);
                        } else {

                            $dataKP = new KirimProduk;
                            $dataKP->norec = $dataKP->generateNewId();
                            $dataKP->kdprofile = $idProfile;
                            $dataKP->statusenabled = true;
                            $dataKP->objectasalprodukfk = $items->asalprodukfk;
                            $dataKP->hargadiscount = $items->hargadiscount;
                            $dataKP->harganetto = $items->harganetto;
                            $dataKP->hargapph = 0;
                            $dataKP->hargappn = 0;
                            $dataKP->hargasatuan = $items->hargasatuan;
                            $dataKP->hargatambahan = 0;
                            $dataKP->hasilkonversi = $item['nilaikonversi'];
                            $dataKP->objectprodukfk = $item['produkfk'];
                            $dataKP->objectprodukkirimfk = $item['produkfk'];
                            $dataKP->nokirimfk = $norecSK;
                            $dataKP->persendiscount = 0;
                            $dataKP->qtyproduk = $jumlah;
                            $dataKP->qtyprodukkonfirmasi = $jumlah;
                            $dataKP->qtyprodukretur = 0;
                            $dataKP->qtyorder = $item['qtyorder'];
                            $dataKP->qtyprodukterima = $jumlah;
                            $dataKP->nostrukterimafk = $noterimaS;
                            $dataKP->objectruanganfk = $request['strukkirim']['objectruangantujuanfk'];
                            $dataKP->objectruanganpengirimfk =  $request['strukkirim']['objectruanganfk'];
                            $dataKP->satuan = '-';
                            $dataKP->objectsatuanstandarfk = $satuanstandarfk; //$item['satuanstandarfk'];
                            $dataKP->satuanviewfk = $item['satuanviewfk'];
                            $dataKP->tglpelayanan = date($request['strukkirim']['tglkirim']);
                            $dataKP->qtyprodukterimakonversi = $jumlah;
                            $dataKP->save();
                            // $saldoakhir =(float)$items->qty - $jumlah;

                            $qtyProd = $jumlah;
                            DB::table('stokprodukdetail_t')
                            ->where('kdprofile', $this->kdProfile)
                                ->where('norec', $items->norec)
                                ->lockForUpdate()
                                ->decrement('qtyproduk', $jumlah);

                            $jumlah = 0;
                        }
                    }

                    //## KartuStok
                    // $newKS = new KartuStok();
                    // $norecKS = $newKS->generateNewId();
                    // $newKS->norec = $norecKS;
                    // $newKS->kdprofile = $idProfile;
                    // $newKS->statusenabled = true;
                    // $newKS->jumlah = ((float)$item['jumlah'] * (float)$item['nilaikonversi']); //$qtyProd;
                    // $newKS->keterangan = 'Kirim Amprahan, dari Ruangan ' . $strRuanganAsal . ' ke Ruangan ' . $strRuanganTujuan . ' No Kirim: ' .  $dataSK->nokirim;
                    // $newKS->produkfk = $item['produkfk'];
                    // $newKS->ruanganfk = $request['strukkirim']['objectruanganfk'];
                    // $newKS->saldoawal = (float)$saldoAwalPengirimAmp; // - ((float)$item['jumlah'] * (float)$item['nilaikonversi']);
                    // $newKS->status = 0;
                    // $newKS->tglinput = date('Y-m-d H:i:s');
                    // $newKS->tglkejadian = date('Y-m-d H:i:s');
                    // $newKS->nostrukterimafk =  $items->norec;
                    // $newKS->norectransaksi = $norecSK;
                    // $newKS->tabletransaksi = 'strukkirim_t';
                    // $newKS->flagfk = 2;
                    // $newKS->save();

                    $this->kartu_STOK(array(
                        "saldoawal" => (float)$saldoAwalPengirimAmp,
                        "qtyin" => 0,
                        "qtyout" => ((float)$item['jumlah'] * (float)$item['nilaikonversi']),
                        "saldoakhir" => (float)$saldoAwalPengirimAmp - ((float)$item['jumlah'] * (float)$item['nilaikonversi']),
                        "keterangan" => 'Kirim Amprahan, dari Ruangan ' . $strRuanganAsal . ' ke Ruangan ' . $strRuanganTujuan . ' No Kirim: ' .  $dataSK->nokirim,
                        "produkfk" => $item['produkfk'],
                        "ruanganfk" => $request['strukkirim']['objectruanganfk'],
                        "tglinput" => date('Y-m-d H:i:s'),
                        "tglkejadian" => date('Y-m-d H:i:s'),
                        "nostrukterimafk" => $items->norec,
                        "norectransaksi" => $norecSK,
                        "tabletransaksi" => 'strukkirim_t',
                        "stokprodukdetailfk" => 0,
                        "flagfk" => 2,
                    ));
                }

                $dataSTOKDETAIL2[] = DB::select(
                    DB::raw("select qtyproduk as qty,nostrukterimafk,norec from stokprodukdetail_t 
                        where kdprofile = $idProfile and objectruanganfk=:ruanganfk and objectprodukfk=:produkfk   "),
                    array(
                        'ruanganfk' => $request['strukkirim']['objectruangantujuanfk'],
                        'produkfk' => $item['produkfk'],
                    )
                );

         
                $dataSTOKDETAIL[] = DB::select(
                    DB::raw("select qtyproduk as qty,nostrukterimafk,norec from stokprodukdetail_t 
                        where kdprofile = $idProfile and objectruanganfk=:ruanganfk and objectprodukfk=:produkfk"),
                    array(
                        'ruanganfk' => $request['strukkirim']['objectruanganfk'],
                        'produkfk' => $item['produkfk'],
                    )
                );
            }

            DB::commit();
            $result = array(
                "status" => 201,
                "message" => "Simpan Data Berhasil",
                "nokirim" => $dataSK,
                "stokdetailPenerima" => $dataSTOKDETAIL2,
                "as" => 'as@epic',
            );
        } catch (\Exception $e) {
            DB::rollBack();
            $result = array(
                "status" => 400,
                "message"  => "Something Went Wrong",
                "error" => $e->getMessage(),
                "as" => 'as@epic',
            );
        }
   
        return $this->respond($result,$result['status'],$result['message']);
    }

    public function CekProdukKirim(Request $request)
    {
        
        $idProfile = $this->kdProfile;
        foreach ($request['details'] as $item) {
            $produkfk[] = $item['produkfk'];
        }
        $details = DB::table('stokprodukdetail_t as spd')
            ->join('produk_m as pr', 'pr.id', '=', 'spd.objectprodukfk')
            ->select(DB::raw("pr.id as produkfk,pr.namaproduk,sum(spd.qtyproduk) as stok"))
            ->where('spd.qtyproduk', '>=', 0)
            ->whereIn('pr.id', $produkfk)
            ->where('spd.objectruanganfk', $request['objectruanganfk'])
            ->where('spd.kdprofile', $idProfile)
            ->groupBy('pr.id', 'pr.namaproduk')
            ->get();

        $result = array(
            'data' => $details,
            'message' => 'as@epic',
        );
        return $this->respond($result);
    }

    public function getDaftarPemeliharaan(Request $request)
    {

        $dateRange = [$request->tglAwal, $request->tglAkhir];

        $data = DB::table('strukplanning_t as spl')
        ->leftjoin('pegawai_m as pg', 'pg.id', '=', 'spl.objectpegawaipjawabfk')
        ->select(
            'spl.tglplanning',
            'spl.objectpegawaipjawabfk',
            'spl.keteranganlainnya',
            'spl.norec',
            'pg.namalengkap',
            'spl.startdate',
            'spl.duedate',
            'spl.keteranganverifikasi'
        )
            ->where('spl.kdprofile', $this->kdProfile)
            ->where('spl.objectkelompoktransaksifk', $this->kelompokTransaksi('PEMELIHARAAN'))
            ->whereBetween(DB::raw('spl.tglplanning::date'), $dateRange);

        if (isset($request['norecasset']) && $request['norecasset'] != "" && $request['norecasset'] != "undefined") {
            $data = $data->where('spl.noregisterassetfk', '=', $request['norecasset']);
        };
        if (isset($request['jenis']) && $request['jenis'] != "" && $request['jenis'] != "undefined") {
            $data = $data->whereNotNull('spl.duedate');
        };

        $data = $data->get();

        return $this->respond($data);
    }

    public function getDataProduk(Request $request)
    {

        $dataProduk = DB::table('produk_m as pr')
        ->JOIN('detailjenisproduk_m as djp', 'djp.id', 'pr.objectdetailjenisprodukfk')
        ->JOIN('jenisproduk_m as jp', 'jp.id', 'djp.objectjenisprodukfk')
        ->JOIN('registrasiaset_t as ra', 'pr.id', 'ra.objectprodukfk')
        ->leftJOIN('satuanstandar_m as ss', 'ss.id', 'pr.objectsatuanstandarfk')
        ->select('ra.norec as id', 'pr.namaproduk', 'ss.id as ssid', 'ss.satuanstandar', 'pr.kdproduk')
        ->where('pr.statusenabled', true)
        ->where('ra.statusenabled', true)
        ->where('pr.kdprofile', $this->kdProfile)
        ->orderBy('pr.namaproduk');
        if (isset($request['namaproduk']) && $request['namaproduk'] != "" && $request['namaproduk'] != "undefined") {
            $dataProduk = $dataProduk->where('pr.namaproduk', 'ilike', '%' . $request['namaproduk'] . '%');
        };

        $dataProduk = $dataProduk->take(20);
        $dataProduk = $dataProduk->get();

        $dataKonversiProduk = DB::table('konversisatuan_t as ks')
        ->JOIN('satuanstandar_m as ss', 'ss.id', 'ks.satuanstandar_asal')
        ->JOIN('satuanstandar_m as ss2', 'ss2.id', 'ks.satuanstandar_tujuan')
        ->select(
            'ks.objekprodukfk',
            'ks.satuanstandar_asal',
            'ss.satuanstandar',
            'ks.satuanstandar_tujuan',
            'ss2.satuanstandar as satuanstandar2',
            'ks.nilaikonversi'
        )
        ->where('ks.statusenabled', true)
        ->where('ks.kdprofile', $this->kdProfile)
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
}
