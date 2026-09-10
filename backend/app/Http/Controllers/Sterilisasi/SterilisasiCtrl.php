<?php

namespace App\Http\Controllers\Sterilisasi;

use App\Http\Controllers\Controller;
use App\Models\Master\KelompokAlat;
use App\Models\Master\KelompokAlatDetail;
use App\Models\Master\Produk;
use App\Models\Master\Ruangan;
use App\Models\Transaksi\KartuStok;
use App\Models\Transaksi\KirimProduk;
use App\Models\Transaksi\StokProdukDetail;
use App\Models\Transaksi\StrukKirim;
use App\Models\Transaksi\StrukOrder;
use App\Models\Transaksi\StrukPelayanan;
use App\Models\Transaksi\StrukPelayananDetail;
use App\Traits\Valet;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SterilisasiCtrl extends Controller
{
    use Valet;
    public function  getComboSteril(Request $r)
    {
        $kdProfile = (int) $this->kdProfile;
        $dataSumberDana = DB::table('asalproduk_m as lu')
            ->select('lu.id', 'lu.asalproduk as asalProduk')
            ->where('lu.statusenabled', true)
            ->where('lu.kdprofile', $kdProfile)
            ->get();

        $ruanganId = explode(',', $this->settingFix('idRuanganCSSD'));
        $dataRuangCssd = DB::table('ruangan_m as lu')
            ->select('lu.id', 'lu.namaruangan')
            ->whereIn('lu.id', $ruanganId)
            ->where('lu.kdprofile', $kdProfile)
            ->where('lu.statusenabled', true)
            ->get();
        $idDetailJenisProduk  = explode(',', $this->settingFix('idAlkesPersediaanCSSD'));
        $DetailJenisProduk = DB::table('detailjenisproduk_m')
            ->where("kdprofile", $kdProfile)
            ->where('statusenabled', true)
            ->whereIn('id', $idDetailJenisProduk)
            ->select('id', 'detailjenisproduk', 'objectjenisprodukfk')->get();
        $result = [
            'sumberDana' => $dataSumberDana,
            'ruangan' => $dataRuangCssd,
            'kdprofile' => $ruanganId,
            'detailjenisproduk' => $DetailJenisProduk
        ];
        return $this->respond($result);
    }
    public function getDataStokInsSteril(Request $request)
    {
        $idProfile = (int) $this->kdProfile;
        $data = DB::table('stokprodukdetail_t as spd')
            ->JOIN('strukpelayanan_t as sp', 'sp.norec', '=', 'spd.nostrukterimafk')
            ->JOIN('produk_m as pr', 'pr.id', '=', 'spd.objectprodukfk')
            ->JOIN('ruangan_m as ru', 'ru.id', '=', 'spd.objectruanganfk')
            ->JOIN('asalproduk_m as ap', 'ap.id', '=', 'spd.objectasalprodukfk')
            ->JOIN('satuanstandar_m as ss', 'ss.id', '=', 'pr.objectsatuanstandarfk')
            ->select(
                'sp.nostruk as noterima',
                'spd.objectprodukfk',
                'pr.kdproduk as kdsirs',
                'pr.namaproduk',
                'ap.asalproduk',
                DB::raw('sum(spd.qtyproduk) as qtyproduk'),
                'ss.satuanstandar',
                'spd.tglkadaluarsa',
                'spd.nobatch',
                'spd.harganetto1',
                'spd.nostrukterimafk',
                'ru.namaruangan'
            )
            ->where('spd.statusenabled', true)
            ->where('pr.statusenabled', true)
            ->where('pr.objectdetailjenisprodukfk', $this->settingFix('idAlkesPersediaanCSSD'))
            ->where('spd.qtyproduk', '>', 0)
            ->where('spd.kdprofile', $idProfile)
            ->groupBy(
                'sp.nostruk',
                'spd.objectprodukfk',
                'pr.kdproduk',
                'pr.namaproduk',
                'ap.asalproduk',
                'ss.satuanstandar',
                'spd.tglkadaluarsa',
                'spd.nobatch',
                'spd.harganetto1',
                'spd.nostrukterimafk',
                'ru.namaruangan'
            );
        if (isset($request['kelompokprodukid']) && $request['kelompokprodukid'] != "" && $request['kelompokprodukid'] != "undefined") {
            $data = $data->where('jp.objectkelompokprodukfk', '=', $request['kelompokprodukid']);
        }
        if (isset($request['produkfk']) && $request['produkfk'] != "" && $request['produkfk'] != "undefined") {
            $data = $data->where('spd.objectprodukfk', '=', $request['produkfk']);
        }
        if (isset($request['namaproduk']) && $request['namaproduk'] != "" && $request['namaproduk'] != "undefined") {
            $data = $data->where('pr.namaproduk', 'ilike', '%' . $request['namaproduk'] . '%');
        }
        if (isset($request['ruanganfk']) && $request['ruanganfk'] != "" && $request['ruanganfk'] != "undefined") {
            $data = $data->where('spd.objectruanganfk', '=', $request['ruanganfk']);
        }
        if (isset($request['asalprodukfk']) && $request['asalprodukfk'] != "" && $request['asalprodukfk'] != "undefined") {
            $data = $data->where('spd.objectasalprodukfk', '=', $request['asalprodukfk']);
        }
        if (isset($request['KdSirs1']) &&  $request['KdSirs1'] != '') {
            if ($request['KdSirs2'] != null &&  $request['KdSirs2'] != '' && $request['KdSirs1'] != null &&  $request['KdSirs1'] != '') {
                $data = $data->whereRaw(" (pr.kdproduk BETWEEN '" . $request['KdSirs1'] . "' and '" . $request['KdSirs2'] . "') ");
            } elseif ($request['KdSirs2'] &&  $request['KdSirs2'] != '' && $request['KdSirs1'] == '' ||  $request['KdSirs1'] == null) {
                $data = $data->whereRaw(" pr.kdproduk ilike '" . $request['KdSirs2'] . "%'");
            } elseif ($request['KdSirs1'] &&  $request['KdSirs1'] != '' && $request['KdSirs2'] == '' ||  $request['KdSirs2'] == null) {
                $data = $data->whereRaw(" pr.kdproduk ilike '" . $request['KdSirs1'] . "%'");
            }
        }
        if (isset($request['jmlRows']) && $request['jmlRows'] != "" && $request['jmlRows'] != "undefined") {
            $data = $data->take($request['jmlRows']);
        }
        $data = $data->get();
        $data2 = [];
        foreach ($data as $item) {
            $data2[] = array(
                'noTerima' => $item->noterima,
                'kodeProduk' => $item->objectprodukfk,
                'kdsirs' => $item->kdsirs,
                'namaProduk' => $item->namaproduk,
                'asalProduk' => $item->asalproduk,
                'qtyProduk' => $item->qtyproduk,
                'satuanStandar' => $item->satuanstandar,
                'tglKadaluarsa' => $item->tglkadaluarsa,
                'noBatch' => $item->nobatch,
                'harga' => $item->harganetto1,
                'nostrukterimafk' => $item->nostrukterimafk,
                'namaruangan' => $item->namaruangan,
            );
        }
        $result = [
            'detail' => $data2,
            'message' => 'success',
        ];
        return $this->respond($result);
    }
    public function getDaftarOrderAlatSteril(Request $request)
    {
        $kdProfile = $this->kdProfile;
        $idProfile = (int) $kdProfile;
        $kelompokTransaksi = $this->settingFix('idPengirimanBarangAntarRuangan');
        $dataRuangan = explode(',', $this->settingFix('idRuanganCSSD'));
        $data = DB::table('strukorder_t as sp')
            ->JOIN('orderpelayanan_t as op', 'op.strukorderfk', '=', 'sp.norec')
            ->LEFTJOIN('pegawai_m as pg', 'pg.id', '=', 'sp.objectpegawaiorderfk')
            ->LEFTJOIN('ruangan_m as ru', 'ru.id', '=', 'sp.objectruanganfk')
            ->LEFTJOIN('ruangan_m as ru2', 'ru2.id', '=', 'sp.objectruangantujuanfk')
            ->select(
                'sp.norec',
                'sp.tglorder',
                'sp.noorder',
                'sp.jenispermintaanfk',
                'pg.namalengkap',
                'ru.namaruangan as ruanganasal',
                'ru2.namaruangan as ruangantujuan',
                'sp.keteranganorder',
                'sp.statusorder',
                'sp.qtyjenisproduk'
            )
            ->where('sp.keteranganorder', '=', 'Order Barang Steril')
            ->where('sp.kdprofile', $idProfile);

        if (isset($request['tglAwal']) && $request['tglAwal'] != "" && $request['tglAwal'] != "undefined") {
            $data = $data->where('sp.tglorder', '>=', $request['tglAwal']);
        }
        if (isset($request['tglAkhir']) && $request['tglAkhir'] != "" && $request['tglAkhir'] != "undefined") {
            $tgl = $request['tglAkhir'];
            $data = $data->where('sp.tglorder', '<=', $tgl);
        }
        if (isset($request['noorder']) && $request['noorder'] != "" && $request['noorder'] != "undefined") {
            $data = $data->where('sp.noorder', 'ILIKE', '%' . $request['noorder']);
        }
        if (isset($request['ruangantujuanfk']) && $request['ruangantujuanfk'] != "" && $request['ruangantujuanfk'] != "undefined") {
            $data = $data->where('ru2.namaruangan', 'ILIKE', '%' . $request['ruangantujuanfk'] . '%');
        }
        if (isset($request['produkfk']) && $request['produkfk'] != "" && $request['produkfk'] != "undefined") {
            $data = $data->where('op.objectprodukfk', '=', $request['produkfk']);
        }
        if (isset($request['norecOrder']) && $request['norecOrder'] != "" && $request['norecOrder'] != "undefined") {
            $data = $data->where('sp.norec', '=', $request['norecOrder']);
        }

        $data = $data->distinct();
        $data = $data->where('sp.statusenabled', true);
        $data = $data->where('sp.objectkelompoktransaksifk', $kelompokTransaksi);
        $data = $data->whereIn('sp.objectruanganfk', $dataRuangan);
        $data = $data->orderBy('sp.noorder');
        $data = $data->get();

        $results = array();
        foreach ($data as $item) {
            $details = DB::select(
                DB::raw("
                     select  pr.id as kdproduk,pr.kdproduk as kdsirs,pr.namaproduk,
                    ss.satuanstandar,spd.qtyproduk
                     from orderpelayanan_t as spd
                    left JOIN produk_m as pr on pr.id=spd.objectprodukfk
                    left JOIN satuanstandar_m as ss on ss.id=spd.objectsatuanstandarfk
                    where spd.kdprofile = $idProfile and strukorderfk=:norec"),
                array(
                    'norec' => $item->norec,
                )
            );
            $jeniskirim = '';
            if ($item->jenispermintaanfk == 1) {
                $jeniskirim = 'Amprahan';
            }
            if ($item->jenispermintaanfk == 2) {
                $jeniskirim = 'Transfer';
            }
            if ($item->statusorder == 0) {
                $status = '';
            } else if ($item->statusorder == 1) {
                $status = 'Sudah Kirim';
            } else if ($item->statusorder == 2) {
                $status = 'Batal Kirim';
            }

            $results[] = array(
                'status' => 'Kirim Order Barang',
                'tglorder' => $item->tglorder,
                'noorder' => $item->noorder,
                'jeniskirim' => $jeniskirim,
                'norec' => $item->norec,
                'namaruanganasal' => $item->ruanganasal,
                'namaruangantujuan' => $item->ruangantujuan,
                'petugas' => $item->namalengkap,
                'keterangan' => $item->keteranganorder,
                'statusorder' => $status,
                'jmlitem' => $item->qtyjenisproduk,
                'details' => $details,
            );
        }
        $data2 = DB::table('strukorder_t as sp')
            ->JOIN('orderpelayanan_t as op', 'op.strukorderfk', '=', 'sp.norec')
            ->LEFTJOIN('pegawai_m as pg', 'pg.id', '=', 'sp.objectpegawaiorderfk')
            ->LEFTJOIN('ruangan_m as ru', 'ru.id', '=', 'sp.objectruanganfk')
            ->LEFTJOIN('ruangan_m as ru2', 'ru2.id', '=', 'sp.objectruangantujuanfk')
            ->select(
                'sp.norec',
                'sp.tglorder',
                'sp.noorder',
                'sp.jenispermintaanfk',
                'pg.namalengkap',
                'ru.namaruangan as ruanganasal',
                'ru2.namaruangan as ruangantujuan',
                'sp.keteranganorder',
                'sp.statusorder',
                'sp.qtyjenisproduk'
            )
            ->where('sp.keteranganorder', '=', 'Order Barang Steril')
            ->where('sp.kdprofile', $idProfile);

        if (isset($request['tglAwal']) && $request['tglAwal'] != "" && $request['tglAwal'] != "undefined") {
            $data2 = $data2->where('sp.tglorder', '>=', $request['tglAwal']);
        }
        if (isset($request['tglAkhir']) && $request['tglAkhir'] != "" && $request['tglAkhir'] != "undefined") {
            $tgl = $request['tglAkhir'];
            $data2 = $data2->where('sp.tglorder', '<=', $tgl);
        }
        if (isset($request['noorder']) && $request['noorder'] != "" && $request['noorder'] != "undefined") {
            $data2 = $data2->where('sp.noorder', 'ILIKE', '%' . $request['noorder']);
        }
        if (isset($request['ruangantujuanfk']) && $request['ruangantujuanfk'] != "" && $request['ruangantujuanfk'] != "undefined") {
            $data2 = $data2->where('ru2.namaruangan', 'ILIKE', '%' . $request['ruangantujuanfk'] . '%');
        }
        if (isset($request['produkfk']) && $request['produkfk'] != "" && $request['produkfk'] != "undefined") {
            $data2 = $data2->where('op.objectprodukfk', '=', $request['produkfk']);
        }

        $data2 = $data2->distinct();
        $data2 = $data2->where('sp.statusenabled', true);
        $data2 = $data2->where('sp.objectkelompoktransaksifk', $kelompokTransaksi);
        $data2 = $data2->whereIn('sp.objectruangantujuanfk', $dataRuangan);
        $data2 = $data2->orderBy('sp.noorder');
        $data2 = $data2->get();

        foreach ($data2 as $item) {
            $details = DB::select(
                DB::raw("
                     select  pr.id as kdproduk,pr.kdproduk as kdsirs,pr.namaproduk,
                    ss.satuanstandar,spd.qtyproduk
                     from orderpelayanan_t as spd
                    left JOIN produk_m as pr on pr.id=spd.objectprodukfk
                    left JOIN satuanstandar_m as ss on ss.id=spd.objectsatuanstandarfk
                    where spd.kdprofile = $idProfile and strukorderfk=:norec"),
                array(
                    'norec' => $item->norec,
                )
            );
            $jeniskirim = '';
            if ($item->jenispermintaanfk == 1) {
                $jeniskirim = 'Amprahan';
            }
            if ($item->jenispermintaanfk == 2) {
                $jeniskirim = 'Transfer';
            }
            if ($item->statusorder == 0) {
                $status = '';
            } else if ($item->statusorder == 1) {
                $status = 'Sudah Kirim';
            } else if ($item->statusorder == 2) {
                $status = 'Batal Kirim';
            }

            $results[] = array(
                'status' => 'Terima Order Barang',
                'tglorder' => $item->tglorder,
                'noorder' => $item->noorder,
                'jeniskirim' => $jeniskirim,
                'norec' => $item->norec,
                'namaruanganasal' => $item->ruanganasal,
                'namaruangantujuan' => $item->ruangantujuan,
                'petugas' => $item->namalengkap,
                'keterangan' => $item->keteranganorder,
                'statusorder' => $status,
                'jmlitem' => $item->qtyjenisproduk,
                'details' => $details,
            );
        }

        $result = [
            'message' => 'success',
            'daftar' => $results,
            'str' => $dataRuangan,

        ];
        return $this->respond($result);
    }
    public function getProdukCssd(Request $r)
    {
        $idProfile = (int)$this->kdProfile;
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

        $idDetailJenisProduk = explode(',', $this->settingFix('idAlkesPersediaanCSSD'));
        $dataProduk = DB::table('produk_m as pr')
            ->leftJoin('detailjenisproduk_m as djp', 'djp.id', '=', 'pr.objectdetailjenisprodukfk')
            ->leftJoin('jenisproduk_m as jp', 'jp.id', '=', 'djp.objectjenisprodukfk')
            ->leftJoin('satuanstandar_m as ss', 'ss.id', '=', 'pr.objectsatuanstandarfk')
            ->join('stokprodukdetail_t as spd', 'spd.objectprodukfk', '=', 'pr.id')
            ->select('pr.id', 'pr.namaproduk', 'ss.id as ssid', 'ss.satuanstandar', 'djp.id as dd')
            ->where('pr.kdprofile', $idProfile)
            ->where('pr.statusenabled', true)
            ->whereIn('djp.id', explode(',', $this->settingFix('idAlkesPersediaanCSSD')));
        if (isset($r['namaproduk']) && $r['namaproduk'] != '') {
            $dataProduk = $dataProduk->where('pr.namaproduk', 'ilike', '%' . $r['namaproduk'] . '%');
        }
        if (isset($r['limit']) && $r['limit'] != '') {
            $dataProduk = $dataProduk->limit($r['limit']);
        }
        $dataProduk = $dataProduk->groupBy('pr.id', 'pr.namaproduk', 'ss.id', 'ss.satuanstandar', 'djp.id');
        $dataProduk = $dataProduk->orderBy('pr.id', 'DESC');
        $dataProduk = $dataProduk->get();
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
                'djp' => $item->dd
            );
        }
        $dataProduk = $dataProdukResult;
        return $this->respond($dataProduk);
    }

    public function getInformasiStok(Request $request)
    {
        $idProfile = (int) $this->kdProfile;
        $results = DB::select(
            DB::raw("select sk.norec,spd.objectprodukfk, sk.tglstruk,spd.objectasalprodukfk,
                            spd.harganetto2,spd.hargadiscount,ru.namaruangan,
                   CAST(sum(spd.qtyproduk) AS FLOAT) as qtyproduk,spd.objectruanganfk as kdruangan
                    from stokprodukdetail_t as spd
                    inner JOIN ruangan_m as ru on ru.id=spd.objectruanganfk
                    inner JOIN strukpelayanan_t as sk on sk.norec=spd.nostrukterimafk
                    where spd.kdprofile = $idProfile and spd.objectprodukfk =:produkId
                    and ru.statusenabled = true
                    -- and ru.statusenabled = true
                    --and spd.objectruanganfk =:ruanganid
                    group by sk.norec,spd.objectprodukfk, sk.tglstruk,spd.objectasalprodukfk,
                            spd.harganetto2,spd.hargadiscount,ru.namaruangan,
                    spd.objectruanganfk
                    order By sk.tglstruk"),
            array(
                'produkId' => $request['produkfk'],
            )
        );
        $jmlstok = 0;
        foreach ($results as $item) {
            $jmlstok = $jmlstok + $item->qtyproduk;
        }
        $a = [];
        foreach ($results as $nenden) {
            $i = 0;
            $sama = false;
            foreach ($a as $hideung) {
                if ($nenden->kdruangan == $a[$i]['kdruangan']) {
                    $sama = true;
                    $a[$i]['qtyproduk'] = $a[$i]['qtyproduk'] + $nenden->qtyproduk;
                }
                $i = $i + 1;
            }

            if ($sama == false) {
                $a[] = array(
                    'qtyproduk' => $nenden->qtyproduk,
                    'kdruangan' => $nenden->kdruangan,
                    'namaruangan' => $nenden->namaruangan,
                );
            }
        }

        $result = array(
            'jmlstok' => $jmlstok,
            'infostok' => $a,
            'detail' => $results,
            'message' => 'success@epic',
        );
        return $this->respond($result);
    }
    public function saveRegistrasiBarangSteril(Request $request)
    {
        $idProfile = (int) $this->kdProfile;
        $req = $request;
        $kelompokTransaksi = $this->kelompokTransaksi('REGISTRASI BARANG STERILISASI CSSD');
        $noKirim = $this->SEQUENCE(new StrukPelayanan(), 'noregistrasialatcssd', 14, 'RSTR-' . $this->getDateTime()->format('ym'), $idProfile);
        if ($noKirim == '') {
            $transMessage = "Gagal mengumpukan data, Coba lagi.!";
            $result = [
                "status" => 400,
                "NOKIRIM" => $noKirim,
                "result" => [],
                "message" => $transMessage,
                "as" => 'as@epic',
            ];
            return $this->respond($result['result'], $result['status'], $transMessage);
        }
        DB::beginTransaction();
        try {
            if ($request['strukkirim']['noreckirim'] == "") {
                $SP = new StrukPelayanan();
                $norecSP = $SP->generateNewId();
                $noStruk = $noKirim;
                $SP->norec = $norecSP;
                $SP->kdprofile = $idProfile;
                $SP->statusenabled = true;
                $SP->nostruk = $noStruk;
                $SP->objectkelompoktransaksifk = $kelompokTransaksi;
            } else {
                $dataKembaliStok = DB::select(
                    DB::raw("
                            select sp.norec,spd.qtyproduk,spd.hasilkonversi,sp.objectruanganfk,spd.objectprodukfk,sp.nostruk
                            from strukpelayanandetail_t as spd
                            INNER JOIN strukpelayanan_t sp on sp.norec=spd.nostrukfk
                            where sp.kdprofile = $idProfile and sp.norec=:norec"),
                    array(
                        'norec' => $request['strukkirim']['noreckirim'],
                    )
                );
                $TambahStok = 0;
                foreach ($dataKembaliStok as $item5) {
                    $TambahStok = (float)$item5->qtyproduk * (float)$item5->hasilkonversi;
                    $dataSaldoAwal = DB::select(
                        DB::raw("select sum(qtyproduk) as qty from stokprodukdetail_t
                            where kdprofile = $idProfile and objectruanganfk=:ruanganfk and objectprodukfk=:produkfk"),
                        array(
                            'ruanganfk' => $item5->objectruanganfk,
                            'produkfk' => $item5->objectprodukfk,
                        )
                    );

                    $saldoAwal = 0;
                    foreach ($dataSaldoAwal as $itemss) {
                        $saldoAwal = (float)$itemss->qty;
                    }

                    foreach ($req['details'] as $hit) {
                        if ($saldoAwal == $hit['jumlah'] || $saldoAwal >= $hit['jumlah']) {
                            $tglnow = date('Y-m-d H:i:s');
                            $tglUbah = date('Y-m-d H:i:s', strtotime('-1 minutes', strtotime($tglnow)));
                            $newKS = new KartuStok();
                            $norecKS = $newKS->generateNewId();
                            $newKS->norec = $norecKS;
                            $newKS->kdprofile = $idProfile;
                            $newKS->statusenabled = true;
                            $newKS->jumlah = $TambahStok; //$r_PPL['jumlah'];
                            $newKS->keterangan = 'Ubah Registrasi Alat CSSD No.  ' . $item5->nostruk;
                            $newKS->produkfk = $item5->objectprodukfk;
                            $newKS->ruanganfk = $item5->objectruanganfk; //$item->ruanganfk;
                            $newKS->saldoawal = (float)$saldoAwal - (float)$TambahStok;
                            $newKS->status = 0;
                            $newKS->tglinput = $tglUbah; //date('Y-m-d H:i:s');//$r_SR['tglresep'];//$r_SR['tglresep']->format('Y-m-d H:i:s');
                            $newKS->tglkejadian = $tglUbah; //date('Y-m-d H:i:s');//$r_SR['tglresep'];// $r_SR['tglresep']->format('Y-m-d H:i:s');
                            $newKS->nostrukterimafk = $request['strukkirim']['noreckirim'];
                            $newKS->norectransaksi = $request['strukkirim']['noreckirim'];
                            $newKS->tabletransaksi = 'strukpelayanan_t';
                            $newKS->save();

                            //END##PENAMBAHAN KEMBALI STOKPRODUKDETAIL
                            $SP = StrukPelayanan::where('norec', $request['strukkirim']['noreckirim'])->first();
                            $noStruk = $SP->nostruk;
                            $delSPD = StokProdukDetail::where('nostrukterimafk', $request['strukkirim']['noreckirim'])
                                ->where('kdprofile', $idProfile)
                                ->delete();
                            $delSPD = StrukPelayananDetail::where('nostrukfk', $request['strukkirim']['noreckirim'])
                                ->where('kdprofile', $idProfile)
                                ->delete();
                        } else {

                            $hasil = 0;
                            $penamBahan = (float)$saldoAwal - (float)$TambahStok;
                            if ($penamBahan < 0) {
                                $hasil = 0;
                            } else {
                                $hasil = (float)$saldoAwal - (float)$TambahStok;
                            }

                            $tglnow1 = date('Y-m-d H:i:s');
                            $tglUbah1 = date('Y-m-d H:i:s', strtotime('-1 minutes', strtotime($tglnow1)));

                            $newKS = new KartuStok();
                            $norecKS = $newKS->generateNewId();
                            $newKS->norec = $norecKS;
                            $newKS->kdprofile = $idProfile;
                            $newKS->statusenabled = true;
                            $newKS->jumlah = $TambahStok;
                            $newKS->keterangan = 'Ubah Registrasi Alat CSSD No. ' . $item5->nostruk;
                            $newKS->produkfk = $item5->objectprodukfk;
                            $newKS->ruanganfk = $item5->objectruanganfk;
                            $newKS->saldoawal = $hasil;
                            $newKS->status = 0;
                            $newKS->tglinput = $tglUbah1;
                            $newKS->tglkejadian = $tglUbah1;
                            $newKS->nostrukterimafk = $request['strukkirim']['noreckirim'];
                            $newKS->norectransaksi = $request['strukkirim']['noreckirim'];
                            $newKS->tabletransaksi = 'strukpelayanan_t';
                            $newKS->save();

                            //END##PENAMBAHAN KEMBALI STOKPRODUKDETAIL
                            $SP = StrukPelayanan::where('norec', $request['strukkirim']['noreckirim'])->first();
                            $noStruk = $SP->nostruk;
                            $delSPD = StokProdukDetail::where('nostrukterimafk', $request['strukkirim']['noreckirim'])
                                ->where('kdprofile', $idProfile)
                                ->delete();
                            $delSPD = StrukPelayananDetail::where('nostrukfk', $request['strukkirim']['noreckirim'])
                                ->where('kdprofile', $idProfile)
                                ->delete();
                        }
                    }
                }
            }

            $SP->tglstruk = $req['strukkirim']['tglkirim'];
            $SP->objectpegawaipenanggungjawabfk = $this->getPegawaiId();
            $SP->qtyproduk = $req['strukkirim']['qtyproduk'];
            $SP->objectruanganfk = $req['strukkirim']['objectruangantujuanfk'];
            $SP->totalharusdibayar = 0;
            $SP->totalppn = 0;
            $SP->totaldiscount = 0;
            $SP->totalhargasatuan = 0;
            $SP->save();
            $norecSpt = $SP->norec;
            $noStrukT = $SP->nostruk;

            foreach ($req['details'] as $item) {
                $qtyJumlah = (float)$item['jumlah'] * (float)$item['nilaikonversi'];
                $SPD = new StrukPelayananDetail();
                $norecKS = $SPD->generateNewId();
                $SPD->norec = $norecKS;
                $SPD->kdprofile = $idProfile;
                $SPD->statusenabled = true;
                $SPD->nostrukfk = $SP->norec;
                $SPD->objectasalprodukfk = 11;
                $SPD->objectprodukfk = $item['produkfk'];
                $SPD->objectruanganfk = $req['strukkirim']['objectruangantujuanfk'];
                $SPD->objectruanganstokfk = $req['strukkirim']['objectruangantujuanfk'];
                $SPD->objectsatuanstandarfk = $item['satuanstandarfk'];
                $SPD->hargadiscount = 0;
                $SPD->hargadiscountgive = 0;
                $SPD->hargadiscountsave = 0;
                $SPD->harganetto = 0;
                $SPD->hargapph = 0;
                $SPD->hargappn =  0;
                $SPD->hargasatuan =  0;
                $SPD->hasilkonversi = $item['nilaikonversi'];
                $SPD->namaproduk = $item['namaproduk'];
                $SPD->hargasatuandijamin = 0;
                $SPD->hargasatuanppenjamin = 0;
                $SPD->hargatambahan = 0;
                $SPD->hargasatuanpprofile = 0;
                $SPD->isonsiteservice = 0;
                $SPD->kdpenjaminpasien = 0;
                $SPD->persendiscount = 0;
                $SPD->persenppn = 0;
                $SPD->qtyproduk = $qtyJumlah;
                $SPD->qtyprodukoutext = 0;
                $SPD->qtyprodukoutint = 0;
                $SPD->qtyprodukretur = 0;
                $SPD->satuan = '-'; //$item['satuanstandar'];;
                $SPD->satuanstandar = $item['satuanviewfk'];
                $SPD->tglpelayanan = $req['strukkirim']['tglkirim'];
                $SPD->is_terbayar = 0;
                $SPD->linetotal = 0;
                $SPD->nobatch = "STERIL";
                $SPD->save();

                //## StokProdukDetail
                $StokPD = new StokProdukDetail();
                $norecStokPD = $StokPD->generateNewId();
                $StokPD->norec = $norecKS;
                $StokPD->kdprofile = $idProfile;
                $StokPD->statusenabled = true;
                $StokPD->objectasalprodukfk = 11;
                $StokPD->hargadiscount = 0;
                $StokPD->harganetto1 = 0;
                $StokPD->harganetto2 = 0;
                $StokPD->persendiscount = 0;
                $StokPD->objectprodukfk = $item['produkfk'];
                $StokPD->qtyproduk = $qtyJumlah;
                $StokPD->qtyprodukonhand = 0;
                $StokPD->qtyprodukoutext = 0;
                $StokPD->qtyprodukoutint = 0;
                $StokPD->objectruanganfk = $req['strukkirim']['objectruangantujuanfk'];
                $StokPD->nostrukterimafk = $SP->norec;
                $StokPD->nobatch = "STERIL";
                $StokPD->objectstrukpelayanandetail = $SPD->norec;
                $StokPD->tglpelayanan = date('Y-m-d H:i:s', strtotime($req['strukkirim']['tglkirim']));
                $StokPD->save();

                $dataSaldoAwal = DB::select(
                    DB::raw("select sum(qtyproduk) as qty from stokprodukdetail_t
                  where kdprofile = $idProfile and objectruanganfk=:ruanganfk and objectprodukfk=:produkfk"),
                    array(
                        'ruanganfk' => $req['strukkirim']['objectruangantujuanfk'],
                        'produkfk' => $item['produkfk'],
                    )
                );

                foreach ($dataSaldoAwal as $items) {
                    $saldoAwal = (float)$items->qty;
                }
                if ($saldoAwal == 0) {
                    $saldoAwal = $qtyJumlah;
                }

                //## KartuStok
                $newKS = new KartuStok();
                $norecKS = $newKS->generateNewId();
                $newKS->norec = $norecKS;
                $newKS->kdprofile = $idProfile;
                $newKS->statusenabled = true;
                $newKS->jumlah = $qtyJumlah;
                $newKS->keterangan = 'Registrasi Alat CSSD. ' . $noStruk;
                $newKS->produkfk = $item['produkfk'];
                $newKS->ruanganfk = $req['strukkirim']['objectruangantujuanfk'];
                $newKS->saldoawal = (float)$saldoAwal;
                $newKS->status = 1;
                $newKS->tglinput = date('Y-m-d H:i:s');
                $newKS->tglkejadian = date('Y-m-d H:i:s');
                $newKS->nostrukterimafk = $SP->norec;
                $newKS->norectransaksi = $SP->norec;
                $newKS->tabletransaksi = 'strukpelayanan_t';
                $newKS->flagfk = 1;
                $newKS->save();
            }
            $this->LOGGING('Registrasi Alat CSSD', $noStrukT, 'norec strukpelayanan_t', 'Registrasi Alat CSSD Noregistrasi' . $norecSpt);
            $transStatus = 'true';
        } catch (Exception $e) {
            $transStatus = 'false';
            $transMessage = "GAGAL";
        }

        if ($transStatus == 'true') {
            $transMessage = "Registrasi Alat Berhasil";
            DB::commit();
            $result = [
                "status" => 201,
                "message" => $transMessage,
                "result" => [
                    "norec" => $norecSpt,
                    "nostruk" => $noStrukT,
                ],
                "as" => 'ea@epic',
            ];
        } else {
            $transMessage = "Registrasi Alat Gagal!!";
            DB::rollBack();
            $result = [
                "status" => 400,
                "message"  => $transMessage,
                "as" => 'ea@epic',
                "result" => $e->getMessage() . "" . $e->getLine()
            ];
        }
        return $this->respond($result['result'], $result['status'], $transMessage);
    }
    public function saveKelompokAlat(Request $request)
    {
        $kdProfile = (int) $this->kdProfile;
        DB::beginTransaction();
        try {
            if ($request['idPaket'] == '') {
                $data = new KelompokAlat();
                $newId = KelompokAlat::max('id') + 1;
                $data->id = $newId;
                $data->kdkelompokalat = $newId;
                $data->norec = $data->generateNewId();
                $data->kdprofile = $kdProfile;
                $data->statusenabled = true;
            } else {
                $data = KelompokAlat::where('id', $request['idPaket'])->where('kdprofile', $kdProfile)->first();
                $dataDetail = KelompokAlatDetail::where('objectkelompokalatfk', $request['idPaket'])->where('kdprofile', $kdProfile)->delete();
            }
            $data->namakelompokalat = $request['namakelompok'];
            $data->reportdisplay = $request['namakelompok'];
            $data->namaexternal = $request['namakelompok'];
            $data->save();
            $idPaket = $data->id;

            $mapResult = [];
            foreach ($request['details'] as $item) {
                $map = new KelompokAlatDetail();
                $map->id = KelompokAlatDetail::max('id') + 1;
                $map->kdprofile = $kdProfile;
                $map->statusenabled = true;
                $map->norec =  $data->generateNewId();
                $map->objectkelompokalatfk = $idPaket;
                $map->produkfk = $item['produkfk'];
                $map->qty = $item['jumlah'];
                $map->save();
                $mapResult[] = $map;
            }

            $transStatus = 'true';
        } catch (Exception $e) {
            $transStatus = 'false';
        }

        if ($transStatus == 'true') {
            $transMessage = "Simpan Berhasil";
            DB::commit();
            $result = [
                'status' => 201,
                'data' => $data,
                'detail' => $map,
                'result' => [
                    'data' => $data,
                    'detail' => $mapResult,
                ],
                'as' => 'success@epic',
            ];
        } else {
            $transMessage = "Simpan Gagal";
            DB::rollBack();
            $result = [
                'status' => 400,
                'as' => 'success@epic',
                'result' => $e->getMessage() . '-' . $e->getCode() . $e->getLine()
            ];
        }
        return $this->respond($result['result'], $result['status'], $transMessage);
    }
    public function getDataKelompokAlat(Request $request)
    {
        $kdProfile = (int) $this->kdProfile;
        $search = $request->search;
        $result = [];
        $data = DB::table('kelompokalat_m as sp')
            ->select('sp.id as kelompokAlatId', 'sp.namakelompokalat')
            ->where('sp.kdprofile', $kdProfile)
            ->where('sp.statusenabled', true);
        if (isset($search) && $search != '') {
            $data = $data->where('sp.namakelompokalat', 'ilike', '%' . $search . '%')->orWhere('sp.id', 'ilike', '%' . $search . '%');
        }
        $data = $data->get();
        foreach ($data as $item) {
            $details = DB::select(
                DB::raw("SELECT pkd.*,pro.namaproduk,
                         pro.objectsatuanstandarfk,ss.satuanstandar,
                         pkd.qty as jumlah
                    FROM kelompokalatdetail_t as pkd
                    INNER JOIN produk_m As pro ON pro.id = pkd.produkfk
                    LEFT JOIN satuanstandar_m AS ss ON ss.id = pro.objectsatuanstandarfk
                    where pkd.kdprofile = $kdProfile and pkd.objectkelompokalatfk=:norec"),
                array(
                    'norec' => $item->kelompokAlatId,
                )
            );
            $result[] = array(
                'kelompokAlatId' => $item->kelompokAlatId,
                'namakelompokalat' => $item->namakelompokalat,
                'details' => $details,
            );
        }
        $result = array(
            'data' => $result,
            'as' => 'success@epic'
        );
        return $this->respond($result);
    }
    public function deleteKelompokAlat(Request $request)
    {
        $kdProfile = (int) $this->kdProfile;
        DB::beginTransaction();
        try {

            KelompokAlat::where('id', $request->idPaket)->where('kdprofile', $kdProfile)
                ->update(['statusenabled' => 'f',]);

            $transStatus = 'true';
        } catch (Exception $e) {
            $transStatus = 'false';
        }

        if ($transStatus == 'true') {
            $transMessage = "Hapus Berhasil";
            DB::commit();

            $result = [
                'status' => 201,
                'as' => 'success@epic',
                'result' => []
            ];
        } else {
            $transMessage = "Hapus Gagal";
            DB::rollBack();
            $result = [
                'status' => 400,
                'as' => 'success@epic',
                'result' => $e->getMessage() . '-' . $e->getLine()
            ];
        }
        return $this->respond($result['result'], $result['status'], $transMessage);
    }
    public function getDaftarDistribusiBarangSteril(Request $request)
    {
        $kdProfile = $this->kdProfile;
        $idProfile = (int) $kdProfile;
        $dataLogin = $request->all();
        $ruanganId = explode(',', $this->settingFix('idRuanganCSSD'));
        $kelompokTransaksi = $this->settingFix('idPengirimanBarangAntarRuangan');
        $data = DB::table('strukkirim_t as sp')
            ->LEFTJOIN('kirimproduk_t as kp', 'kp.nokirimfk', '=', 'sp.norec')
            ->LEFTJOIN('pegawai_m as pg', 'pg.id', '=', 'sp.objectpegawaipengirimfk')
            ->LEFTJOIN('ruangan_m as ru', 'ru.id', '=', 'sp.objectruanganasalfk')
            ->LEFTJOIN('ruangan_m as ru2', 'ru2.id', '=', 'sp.objectruangantujuanfk')
            ->select(
                'sp.norec',
                'sp.tglkirim',
                'sp.nokirim',
                'sp.jenispermintaanfk',
                'pg.namalengkap',
                'sp.statuskirim',
                'sp.noorderfk',
                'ru.id as ruasalid',
                'ru.namaruangan as ruanganasal',
                'ru2.id as rutujuanid',
                'ru2.namaruangan as ruangantujuan',
                'sp.keteranganlainnyakirim',
                'sp.statusbersih',
                'sp.statussteril as statussterilisasi',
                DB::raw('count(kp.objectprodukfk) as jmlitem')
            )
            ->where('sp.kdprofile', $idProfile)
            ->groupBy(
                'sp.norec',
                'sp.tglkirim',
                'sp.nokirim',
                'sp.jenispermintaanfk',
                'pg.namalengkap',
                'sp.noorderfk',
                'ru.id',
                'ru.namaruangan',
                'ru2.id',
                'ru2.namaruangan',
                'sp.keteranganlainnyakirim',
                'sp.statuskirim',
                'sp.statusbersih',
                'sp.statussteril'
            );

        if (isset($request['tglAwal']) && $request['tglAwal'] != "" && $request['tglAwal'] != "undefined") {
            $data = $data->where('sp.tglkirim', '>=', $request['tglAwal']);
        }
        if (isset($request['tglAkhir']) && $request['tglAkhir'] != "" && $request['tglAkhir'] != "undefined") {
            $tgl = $request['tglAkhir'];
            $data = $data->where('sp.tglkirim', '<=', $tgl);
        }
        if (isset($request['nokirim']) && $request['nokirim'] != "" && $request['nokirim'] != "undefined") {
            $data = $data->where('sp.nokirim', 'ILIKE', '%' . $request['nokirim'] . '%');
        }
        if (isset($request['ruangantujuanfk']) && $request['ruangantujuanfk'] != "" && $request['ruangantujuanfk'] != "undefined") {
            $data = $data->where('sp.objectruangantujuanfk', '=', $request['ruangantujuanfk']);
        }
        if (isset($request['produkfk']) && $request['produkfk'] != "" && $request['produkfk'] != "undefined") {
            $data = $data->where('kp.objectprodukfk', '=', $request['produkfk']);
        }
        $data = $data->where('sp.statusenabled', true);
        $data = $data->where('sp.objectkelompoktransaksifk', $kelompokTransaksi);
        $data = $data->whereIn('sp.objectruanganasalfk', $ruanganId);
        $data = $data->where('sp.noregistrasifk', '=', 0);
        $data = $data->orderBy('sp.nokirim');
        $data = $data->get();

        $results = array();
        foreach ($data as $item) {
            $details = DB::select(
                DB::raw("
                     select  pr.id as kdproduk,pr.kdproduk as kdsirs,pr.namaproduk,
                    ss.satuanstandar,spd.qtyproduk,spd.qtyprodukretur,spd.objectprodukfk
                     from kirimproduk_t as spd
                    left JOIN produk_m as pr on pr.id=spd.objectprodukfk
                    left JOIN satuanstandar_m as ss on ss.id=spd.objectsatuanstandarfk
                    where spd.kdprofile = $idProfile and nokirimfk=:norec"),
                array(
                    'norec' => $item->norec,
                )
            );
            $jeniskirim = '';
            if ($item->jenispermintaanfk == 1) {
                $jeniskirim = 'Amprahan';
            }
            if ($item->jenispermintaanfk == 2) {
                $jeniskirim = 'Transfer';
            }
            $results[] = array(
                'status' => 'Kirim Barang',
                'tglstruk' => $item->tglkirim,
                'nostruk' => $item->nokirim,
                'noorderfk' => $item->noorderfk,
                'jenispermintaanfk' => $item->jenispermintaanfk,
                'jeniskirim' => $jeniskirim,
                'norec' => $item->norec,
                'ruasalid' => $item->ruasalid,
                'namaruanganasal' => $item->ruanganasal,
                'rutujuanid' => $item->rutujuanid,
                'namaruangantujuan' => $item->ruangantujuan,
                'petugas' => $item->namalengkap,
                'keterangan' => $item->keteranganlainnyakirim,
                'jmlitem' => $item->jmlitem,
                'details' => $details,
                'statussteril' => $item->statuskirim,
                'statusbersih' => $item->statusbersih,
                'statussterilisasi' => $item->statussterilisasi,
            );
        }
        $data2 = DB::table('strukkirim_t as sp')
            ->LEFTJOIN('kirimproduk_t as kp', 'kp.nokirimfk', '=', 'sp.norec')
            ->LEFTJOIN('pegawai_m as pg', 'pg.id', '=', 'sp.objectpegawaipengirimfk')
            ->LEFTJOIN('ruangan_m as ru', 'ru.id', '=', 'sp.objectruanganasalfk')
            ->LEFTJOIN('ruangan_m as ru2', 'ru2.id', '=', 'sp.objectruangantujuanfk')
            ->select(
                'sp.norec',
                'sp.tglkirim',
                'sp.nokirim',
                'sp.jenispermintaanfk',
                'pg.namalengkap',
                'sp.statuskirim',
                'sp.statusbersih',
                'sp.statussteril as statussterilisasi',
                'ru.namaruangan as ruanganasal',
                'ru.id as ruasalid',
                'ru2.namaruangan as ruangantujuan',
                'ru2.id as rutujuanid',
                'sp.keteranganlainnyakirim',
                DB::raw('count(kp.objectprodukfk) as jmlitem')
            )
            ->where('sp.kdprofile', $idProfile)
            ->groupBy(
                'sp.norec',
                'sp.tglkirim',
                'sp.nokirim',
                'sp.jenispermintaanfk',
                'pg.namalengkap',
                'sp.noorderfk',
                'ru.id',
                'ru.namaruangan',
                'ru2.id',
                'ru2.namaruangan',
                'sp.keteranganlainnyakirim',
                'sp.statuskirim',
                'sp.statusbersih',
                'sp.statussteril'
            );

        if (isset($request['tglAwal']) && $request['tglAwal'] != "" && $request['tglAwal'] != "undefined") {
            $data2 = $data2->where('sp.tglkirim', '>=', $request['tglAwal']);
        }
        if (isset($request['tglAkhir']) && $request['tglAkhir'] != "" && $request['tglAkhir'] != "undefined") {
            $tgl = $request['tglAkhir'];
            $data2 = $data2->where('sp.tglkirim', '<=', $tgl);
        }
        if (isset($request['nokirim']) && $request['nokirim'] != "" && $request['nokirim'] != "undefined") {
            $data2 = $data2->where('sp.nokirim', 'ILIKE', '%' . $request['nokirim']);
        }
        if (isset($request['ruangantujuanfk']) && $request['ruangantujuanfk'] != "" && $request['ruangantujuanfk'] != "undefined") {
            $data2 = $data2->where('sp.objectruangantujuanfk', '=', $request['ruangantujuanfk']);
        }
        if (isset($request['produkfk']) && $request['produkfk'] != "" && $request['produkfk'] != "undefined") {
            $data2 = $data2->where('kp.objectprodukfk', '=', $request['produkfk']);
        }
        $data2 = $data2->where('sp.statusenabled', true);
        $data2 = $data2->where('sp.objectkelompoktransaksifk', $kelompokTransaksi);
        $data2 = $data2->whereIn('sp.objectruangantujuanfk', $ruanganId);
        $data2 = $data2->orderBy('sp.nokirim');
        $data2 = $data2->get();
        foreach ($data2 as $item) {
            $details = DB::select(
                DB::raw("
                     select  pr.id as kdproduk,pr.kdproduk as kdsirs,pr.namaproduk,
                    ss.satuanstandar,spd.qtyproduk,spd.qtyprodukretur,spd.objectprodukfk
                     from kirimproduk_t as spd
                    left JOIN produk_m as pr on pr.id=spd.objectprodukfk
                    left JOIN satuanstandar_m as ss on ss.id=spd.objectsatuanstandarfk
                    where spd.kdprofile = $idProfile and nokirimfk=:norec and spd.qtyproduk <> 0"),
                array(
                    'norec' => $item->norec,
                )
            );
            $jeniskirim = '';
            if ($item->jenispermintaanfk == 1) {
                $jeniskirim = 'Amprahan';
            }
            if ($item->jenispermintaanfk == 2) {
                $jeniskirim = 'Transfer';
            }
            $results[] = array(
                'status' => 'Terima Barang',
                'tglstruk' => $item->tglkirim,
                'nostruk' => $item->nokirim,
                'jeniskirim' => $jeniskirim,
                'norec' => $item->norec,
                'jenispermintaanfk' => $item->jenispermintaanfk,
                'ruasalid' => $item->ruasalid,
                'namaruanganasal' => $item->ruanganasal,
                'rutujuanid' => $item->rutujuanid,
                'namaruangantujuan' => $item->ruangantujuan,
                'petugas' => $item->namalengkap,
                'keterangan' => $item->keteranganlainnyakirim,
                'jmlitem' => $item->jmlitem,
                'details' => $details,
                'statussteril' => $item->statuskirim,
                'statusbersih' => $item->statusbersih,
                'statussterilisasi' => $item->statussterilisasi,
            );
        }

        $result = array(
            'data' => $results,
            'message' => 'success@epic',
            'str' => $ruanganId,
        );

        return $this->respond($result);
    }
    public function saveKirimBarangRuangan(Request $request)
    {
        $idProfile = $this->kdProfile;
        $noKirim = $request['strukkirim']['jenispermintaanfk'] == 2 ?
            $this->SEQUENCE(new StrukKirim, 'nokirim', 14, 'TRF-' . $this->getDateTime()->format('ym'), $this->kdProfile) :
            $this->SEQUENCE(new StrukKirim, 'nokirim', 14, 'AMP-' . $this->getDateTime()->format('ym'), $this->kdProfile);


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
        DB::beginTransaction();
        try {
            if ($request['strukkirim']['noreckirim'] == '') {
                if ($request['strukkirim']['norecOrder'] != '') {
                    StrukOrder::where('norec', $request['strukkirim']['norecOrder'])
                        ->where('kdprofile', $this->kdProfile)
                        ->update(['statusorder' => 3]);
                }
                $dataSK = new StrukKirim();
                $dataSK->norec = $dataSK->generateNewId();
                $dataSK->nokirim = $noKirim;
                $message = "Verifikasi Order Barang Berhasil";
            } else {
                //1
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
                // KartuStok::where('keterangan',  'Kirim Amprahan, dari Ruangan ' . $strRuanganAsal . ' ke Ruangan ' . $strRuanganTujuan . ' No Kirim: ' .  $dataSK->nokirim)
                //     ->update([
                //         'flagfk' => null
                //     ]);

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
                            ->where('kdprofile', $idProfile)
                            ->where('objectruanganfk', $strukKirimOld->objectruanganfk)
                            ->where('objectprodukfk', $item->objectprodukfk)
                            ->first();

                        DB::table('stokprodukdetail_t')
                            ->where('kdprofile', $idProfile)
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
                        $newKSKir->keterangan = 'Ubah Kirim Barang, dari Ruangan ' . $nameRuAsal . ' ke Ruangan ' . $strNmRuanganStrukKirimSebelumnya . ' No Kirim: ' .  $dataSK->nokirim;
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
                                    ->where('kdprofile', $idProfile)
                                    ->where('objectruanganfk', $strIdRuanganStrukKirimSebelumnya)
                                    ->where('objectprodukfk', $item->objectprodukfk)
                                    ->first();

                                DB::table('stokprodukdetail_t')
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
                                $newKSPe->kdprofile = $idProfile;
                                $newKSPe->statusenabled = true;
                                $newKSPe->jumlah = (float)$item->qtyproduk;
                                $newKSPe->keterangan = 'Ubah Terima Barang, dari Ruangan ' . $nameRuAsal . ' ke Ruangan ' . $strNmRuanganStrukKirimSebelumnya . ' No Kirim: ' .  $dataSK->nokirim;
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
                                ->where('kdprofile', $idProfile)
                                ->where('objectruanganfk', $request['strukkirim']['objectruangantujuanfk'])
                                ->where('objectprodukfk', $item->objectprodukfk)
                                ->first();

                            DB::table('stokprodukdetail_t')
                                ->where('kdprofile', $idProfile)
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
                            $newKS->keterangan = 'Ubah Terima Barang, dari Ruangan ' . $nameRuAsal . ' ke Ruangan ' . $strNmRuanganStrukKirimSebelumnya . ' No Kirim: ' .  $dataSK->nokirim;
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

                        KirimProduk::where('nokirimfk', $request['strukkirim']['noreckirim'])->where('kdprofile', $idProfile)->delete();
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
                        DB::table('stokprodukdetail_t')
                            ->where('kdprofile', $idProfile)
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
                                    ->where('kdprofile', $idProfile)
                                    ->where('objectruanganfk', $strIdRuanganStrukKirimSebelumnya)
                                    ->where('objectprodukfk', $item->objectprodukfk)
                                    ->first();

                                DB::table('stokprodukdetail_t')
                                    ->where('kdprofile', $idProfile)
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

                        KirimProduk::where('nokirimfk', $request['strukkirim']['noreckirim'])->where('kdprofile', $idProfile)->delete();
                    }
                }
            }

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

            $cek = [];
            foreach ($request['details'] as $item) {
                //cari satuan standar
                $noterimaS = $item['nostrukterimafk'];
                $satuanstandarfk = Produk::select('objectsatuanstandarfk')
                    ->where('kdprofile', $this->kdProfile)
                    ->where('statusenabled', true)
                    ->where('id', $item['produkfk'])
                    ->first();

                if ($request['strukkirim']['jenispermintaanfk'] == 2) {

                    $dataPengirim = StokProdukDetail::where('objectruanganfk', $request['strukkirim']['objectruanganfk'])
                        ->where('objectprodukfk', $item['produkfk'])
                        ->where('statusenabled', true)
                        ->where('kdprofile', $this->kdProfile)
                        ->orderBy('tglpelayanan', 'desc')
                        ->first();
                    //PENERIMA

                    $saldoAwalPenerimaIn = 0;
                    $jumlah = (float)$item['jumlah'] * (float)$item['nilaikonversi'];

                    $dataSaldoAwalPenerima = StokProdukDetail::where('kdprofile', $this->kdProfile)
                        ->where('objectruanganfk', $request['strukkirim']['objectruangantujuanfk'])
                        ->where('objectprodukfk', $item['produkfk'])
                        ->sum('qtyproduk');

                    $rus = $request['strukkirim']['objectruanganfk'];
                    $dataSaldoAwaPengirim = StokProdukDetail::where('kdprofile', $this->kdProfile)
                        ->where('objectruanganfk', $request['strukkirim']['objectruanganfk'])
                        ->where('objectprodukfk', $item['produkfk'])
                        ->sum('qtyproduk');

                    $saldoAkhirPenerima = (float)$dataSaldoAwalPenerima + (float)$jumlah;

                    $dataKP = new KirimProduk();
                    $dataKP->norec = $dataKP->generateNewId();
                    $dataKP->kdprofile = $idProfile;
                    $dataKP->statusenabled = true;
                    $dataKP->objectasalprodukfk = $dataPengirim->objectasalprodukfk;
                    if ($dataPengirim->hargadiscount == null) {
                        $dataKP->hargadiscount = 0;
                    } else {
                        $dataKP->hargadiscount = $dataPengirim->hargadiscount;
                    }
                    $dataKP->harganetto = $dataPengirim->harganetto1;
                    $dataKP->hargapph = 0;
                    $dataKP->hargappn = 0;
                    $dataKP->hargasatuan = $dataPengirim->harganetto1;
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
                    $dataKP->nostrukterimafk = $dataPengirim->nostrukterimafk;
                    $dataKP->objectruanganfk = $request['strukkirim']['objectruangantujuanfk'];
                    $dataKP->objectruanganpengirimfk = $request['strukkirim']['objectruanganfk'];
                    $dataKP->satuan = '-';
                    $dataKP->objectsatuanstandarfk = $satuanstandarfk->objectsatuanstandarfk; //$item['satuanstandarfk'];
                    $dataKP->satuanviewfk = $item['satuanviewfk'];
                    $dataKP->tglpelayanan = date($request['strukkirim']['tglkirim']);
                    $dataKP->qtyprodukterimakonversi = $jumlah;
                    if (isset($item['tglexp']) && $item['tglexp'] != 'Invalid date' && $item['tglexp'] != '') {
                        $dataKP->tglkadaluarsa = $item['tglexp'];
                    }
                    // $dataKP->nobatch = $item['nobatch'];
                    if (isset($r_NewPD['nobatch'])) {
                        $r_NewPD->nobatch = $r_NewPD['nobatch'];
                    }

                    $dataKP->save();

                    $dataNewSPD = new StokProdukDetail;
                    $dataNewSPD->norec = $dataNewSPD->generateNewId();
                    $dataNewSPD->kdprofile = $idProfile;
                    $dataNewSPD->statusenabled = true;
                    $dataNewSPD->objectasalprodukfk = $dataPengirim->objectasalprodukfk;
                    $dataNewSPD->hargadiscount = $dataPengirim->hargadiscount;
                    $dataNewSPD->harganetto1 = $dataPengirim->harganetto1;
                    $dataNewSPD->harganetto2 = $dataPengirim->harganetto2;
                    $dataNewSPD->persendiscount = 0;
                    $dataNewSPD->objectprodukfk = $dataPengirim->objectprodukfk;
                    $dataNewSPD->qtyproduk = ((float)$jumlah);
                    $dataNewSPD->qtyprodukonhand = 0;
                    $dataNewSPD->qtyprodukoutext = 0;
                    $dataNewSPD->qtyprodukoutint = 0;
                    $dataNewSPD->objectruanganfk = $request['strukkirim']['objectruangantujuanfk'];
                    $dataNewSPD->nostrukterimafk = $dataPengirim->nostrukterimafk;
                    $dataNewSPD->noverifikasifk = $dataPengirim->noverifikasifk;
                    $dataNewSPD->nobatch = $dataPengirim->nobatch;
                    $dataNewSPD->tglkadaluarsa = $dataPengirim->tglkadaluarsa;
                    $dataNewSPD->tglpelayanan = $dataPengirim->tglpelayanan;
                    $dataNewSPD->tglproduksi = $dataPengirim->tglproduksi;

                    $dataNewSPD->save();

                    $this->kartu_STOK(array(
                        "saldoawal" => (float)$dataSaldoAwalPenerima,
                        "qtyin" => $jumlah,
                        "qtyout" => 0,
                        "saldoakhir" => $saldoAkhirPenerima,
                        "keterangan" => 'Terima Barang, dari Ruangan ' . $nameRuAsal . ' ke Ruangan ' . $nameRuTujuan . ' berupa produk ' . $item['namaproduk'] . '. No Kirim: ' .  $dataSK->nokirim,
                        "produkfk" => $item['produkfk'],
                        "ruanganfk" => $request['strukkirim']['objectruangantujuanfk'],
                        "tglinput" => date('Y-m-d H:i:s'),
                        "tglkejadian" => date('Y-m-d H:i:s'),
                        "nostrukterimafk" => $item['nostrukterimafk'],
                        "norectransaksi" => $norecSK,
                        "tabletransaksi" => 'strukkirim_t',
                        "flagfk" => null,
                    ));
                }

                $dataSTOKDETAIL2 = DB::select(
                    DB::raw("select qtyproduk as qty,nostrukterimafk,norec from stokprodukdetail_t
                        where kdprofile = $idProfile and objectruanganfk=:ruanganfk and objectprodukfk=:produkfk"),
                    array(
                        'ruanganfk' => $request['strukkirim']['objectruangantujuanfk'],
                        'produkfk' => $item['produkfk'],
                    )
                );
            }

            DB::commit();
            $result = [
                "status" => 200,
                "message" => $message,
                "nokirim" => $noKirim,
                "stokdetailPenerima" => $dataSTOKDETAIL2
            ];
        } catch (Exception $e) {
            DB::rollBack();
            $result = [
                "status" => 400,
                "message" => "Simpan Gagal !",
                "data" => $e->getMessage() . "-" . $e->getCode() . "-" . $e->getLine()
            ];
        }

        return $this->respond($result, $result['status'], $result['message']);
    }
}
