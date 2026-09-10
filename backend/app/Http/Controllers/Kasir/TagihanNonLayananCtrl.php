<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use App\Models\Master\CaraBayar;
use App\Models\Master\Departemen;
use App\Models\Master\KelompokPasien;
use App\Models\Master\KelompokTransaksi;
use App\Models\Master\Pegawai;
use App\Models\Master\Ruangan;
use App\Models\Transaksi\PasienDaftar;
use App\Models\Transaksi\PelayananPasien;
use App\Models\Transaksi\StrukBuktiPenerimaan;
use App\Models\Transaksi\StrukBuktiPenerimaanCaraBayar;
use App\Models\Transaksi\StrukPelayanan;
use App\Models\Transaksi\StrukPelayananDetail;
use App\Traits\Valet;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;

class TagihanNonLayananCtrl extends Controller
{
    use Valet;
    public function __construct()
    {
        parent::__construct($is_encrypt = true);
    }

    public function tagihanNonLayanan(Request $r)
    {

        $result['strukpelayanan'] = DB::table('strukpelayanan_t as sp')
            ->select(
                'sp.objectkelompoktransaksifk',
                'sp.keteranganlainnya',
                'sp.objectkelompokpasienfk',
                'sp.objectrekananfk',
                'sp.namapasien_klien',
                'sp.noteleponfaks',
                'sp.tglstruk',
                'sp.totalharusdibayar'
            )
            ->where('statusenabled', true)
            ->where('kdprofile', $this->kdProfile)
            ->where('norec', $r['norec_sp'])
            ->first();

        $result['strukpelayanandetail'] = DB::table('strukpelayanandetail_t as sp')
            ->join('produk_m as pr', 'sp.objectprodukfk', '=', 'pr.id')
            ->leftjoin('pegawai_m as pg', 'pg.id', '=', 'sp.objectpegawaifk_dokter')
            ->select(
                'sp.objectprodukfk as produkfk',
                'sp.qtyproduk as jumlah',
                'sp.qtyoranglast',
                'sp.hargasatuan as harga',
                'sp.keteranganlainnya as keterangan',
                'pr.namaproduk',
                'sp.namaproduk as namaproduk_freetext',
                'sp.objectpegawaifk_dokter as id_dokter',
                'pg.namalengkap as dokter'
            )
            ->where('sp.statusenabled', true)
            ->where('sp.kdprofile', $this->kdProfile)
            ->where('sp.nostrukfk', $r['norec_sp'])
            ->get();
        foreach ($result['strukpelayanandetail'] as $k => $d) {
            $d->no =  $k + 1;
            $d->jumlah = (float) $d->jumlah;
            $d->harga = (float) $d->harga;
            $d->total =  $d->jumlah *    $d->harga;
        }
        $result['as'] = '@epic';

        return $this->respond($result);
    }
    public function dropdownTagihanNonLayanan(Request $r)
    {
        $result['kelompokpasien'] = KelompokPasien::mine()->get();
        $result['kelompoktransaksi'] = KelompokTransaksi::mine()->whereIn('id', explode(',', $this->settingFix('kelompokTransaksiNonPelayanan')))->get();
        $result['idPelayananLainnyaNonLayanan'] = $this->settingFix('idPelayananLainnyaNonLayanan');

        $result['as'] = '@epic';

        return $this->respond($result);
    }
    public function listPelayananNonKelas(Request $r)
    {
        $data = DB::table('produk_m as pr')
            ->JOIN('harganettoprodukbykelas_m as het', 'het.objectprodukfk', '=', 'pr.id')
            ->select('pr.id', 'pr.namaproduk', 'het.harganetto1 as harga')
            ->where('pr.kdprofile', $this->kdProfile)
            ->where('pr.statusenabled', true)
            ->where('het.objectkelasfk', $this->settingFix('idNonKelas'))
            ->where('het.objectjenispelayananfk', $this->settingFix('idJenisPelayananReguler'))
            ->where('het.statusenabled', true);
        if (isset($r['prid']) && $r['prid'] != "" && $r['prid'] != "undefined") {
            $data = $data->where('ru.id', $r['prid']);
        }
        if (isset($r['namaproduk']) && $r['namaproduk'] != "" && $r['namaproduk'] != "undefined") {
            $data = $data->where('pr.namaproduk', 'ilike', '%' . $r['namaproduk'] . '%');
        }
        $data = $data->limit($r['limit']);
        $data = $data->get();
        $result['data'] = $data;
        $result['as'] = '@epic';
        return $this->respond($result);
    }
    public function listPenjaminByKelompokPasien(Request $r)
    {
        $result = DB::table('mapkelompokpasientopenjamin_m as mkp')
            ->join('rekanan_m as rk', 'rk.id', '=', 'mkp.kdpenjaminpasien')
            ->select('rk.id', 'rk.namarekanan')
            ->where('mkp.objectkelompokpasienfk', $r['id'])
            ->where('mkp.statusenabled', true)
            ->where('rk.statusenabled', true)
            ->when(isset($r['query']), function ($query) use ($r) {
                return $query->where('rk.namarekanan', 'ilike', '%' . $r['query'] . '%');
            })
            ->distinct()
            ->where('mkp.kdprofile', (int)$this->kdProfile)
            ->orderBy('rk.namarekanan')
            ->get();
        return $this->respond($result);
    }

    public function simpanNonLayanan(Request $r)
    {
        DB::beginTransaction();
        try {
            $kdProfile = $this->kdProfile;
            if ($r['norec'] == '') {
                $SP = new StrukPelayanan();
                $noStruk = $this->SEQUENCE(new StrukPelayanan, 'nostruk', 10, 'NL' . date('ym'), $kdProfile);
                $SP->norec =  $SP->generateNewId();;
                $SP->kdprofile = $kdProfile;
                $SP->statusenabled = true;
                $SP->nostruk = $noStruk;
                $namaLog = 'Input Tagihan Non Layanan ';
            } else {
                $SP = StrukPelayanan::where('norec', $r['norec'])->where('kdprofile', $kdProfile)->first();
                StrukPelayananDetail::where('nostrukfk', $r['norec'])->where('kdprofile', $kdProfile)->delete();
                $namaLog = 'Edit Tagihan Non Layanan ';
            }
            $SP->objectkelompoktransaksifk = $r['objectkelompoktransaksifk'];
            $SP->keteranganlainnya = $r['keteranganlainnya'];
            $SP->namapasien_klien = $r['namapasien_klien'];
            $SP->noteleponfaks = $r['noteleponfaks'];
            $SP->tglstruk = $r['tglstruk'];
            $SP->totalharusdibayar = $r['totalharusdibayar'];
            $SP->objectkelompokpasienfk = $r['objectkelompokpasienfk'];
            $SP->objectrekananfk = $r['objectrekananfk'];
            $SP->save();
            $produk = '';
            foreach ($r['details'] as $item) {
                $produk = $produk . ',' . $item['namaproduk'];
                $SPD = new StrukPelayananDetail();
                $SPD->norec = $SPD->generateNewId();
                $SPD->kdprofile = $kdProfile;
                $SPD->statusenabled = true;
                $SPD->nostrukfk = $SP->norec;
                $SPD->objectprodukfk = $item['produkfk'];
                $SPD->hargadiscount = 0;
                $SPD->hargadiscountgive = 0;
                $SPD->hargadiscountsave = 0;
                $SPD->harganetto = $item['harga'];
                $SPD->hargapph = 0;
                $SPD->hargappn = 0;
                $SPD->hargasatuan = $item['harga'];
                $SPD->hargasatuandijamin = 0;
                $SPD->hargasatuanppenjamin = 0;
                $SPD->hargasatuanpprofile = 0;
                $SPD->hargatambahan = 0;
                $SPD->isonsiteservice = 0;
                $SPD->kdpenjaminpasien = 0;
                $SPD->persendiscount = 0;
                $SPD->qtyproduk = $item['jumlah'];
                $SPD->qtyoranglast = $item['qtyoranglast'];
                $SPD->qtyprodukoutext = 0;
                $SPD->qtyprodukoutint = 0;
                $SPD->qtyprodukretur = 0;
                $SPD->satuan = 0;
                $SPD->tglpelayanan = $r['tglstruk'];
                $SPD->is_terbayar = 0;
                $SPD->linetotal = 0;
                $SPD->keteranganlainnya = $item['keterangan'];
                $SPD->namaproduk =  $this->settingFix('idPelayananLainnyaNonLayanan') == $item['produkfk']?$item['namaproduk'] : null;
                $SPD->objectpegawaifk_dokter = isset($item['DDDokter']) ? $item['DDDokter']['value'] : null;
                $SPD->save();
            }
            $this->LOGGING(
                'Tagihan Non Layanan',
                $SP->norec,
                'strukpelayanan_t',
                $namaLog . ' pada Pasien '
                    .   $SP->namapasien_klien
                    . ' (' . $SP->noteleponfaks . ') - '
                    . substr($produk, 1, strlen($produk) - 1)
            );

            $transMessage = "Sukses";
            DB::commit();
            $result = array(
                "status" => 200,
                "result" => array(
                    "norec"  => $SP->norec,
                    "as" => '@epic',
                ),
            );
        } catch (Exception $e) {
            $transMessage = "Simpan Gagal";
            DB::rollBack();
            $result = array(
                "status" => 400,
                // "result"  => null,// $e->getMessage() . ' '. $e->getLine()
                "result"  => $e->getMessage() . ' '. $e->getLine()
            );
        }
        return $this->respond($result['result'], $result['status'], $transMessage);
    }
}
