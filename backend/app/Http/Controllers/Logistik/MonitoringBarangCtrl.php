<?php

namespace App\Http\Controllers\Logistik;

use App\Http\Controllers\Controller;
use App\Models\Master\JenisProduk;
use App\Models\Master\KelompokProduk;
use App\Models\Master\Produk;
use App\Models\Master\Ruangan;
use App\Models\Master\SatuanResep;
use App\Models\Transaksi\KartuStok;
use App\Models\Transaksi\KirimProduk;
use App\Models\Transaksi\OrderPelayanan;
use App\Models\Transaksi\StokProdukDetail;
use App\Models\Transaksi\StrukKirim;
use App\Models\Transaksi\StrukOrder;
use App\Models\Transaksi\StrukRetur;
use App\Models\Transaksi\StrukReturDetail;
use App\Traits\Valet;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class MonitoringBarangCtrl extends Controller
{
    use Valet;
    public function __construct()
    {
        parent::__construct($is_encrypt = true);
    }

    public function getCombo()
    {
        $result['kelompokproduk'] = KelompokProduk::mine()->get();
        $result['jenisproduk'] = JenisProduk::mine()->get();

        return $this->respond($result);
    }

    public function getProduk(Request $r)
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

        $dataProduk = DB::table('produk_m as pr')
            ->JOIN('detailjenisproduk_m as djp', 'djp.id', '=', 'pr.objectdetailjenisprodukfk')
            ->JOIN('jenisproduk_m as jp', 'jp.id', '=', 'djp.objectjenisprodukfk')
            ->leftJOIN('satuanstandar_m as ss', 'ss.id', '=', 'pr.objectsatuanstandarfk')
            ->JOIN('stokprodukdetail_t as spd', 'spd.objectprodukfk', '=', 'pr.id')
            ->select('pr.id', 'pr.namaproduk', 'ss.id as ssid', 'ss.satuanstandar')
            ->where('pr.kdprofile', $idProfile)
            ->where('pr.statusenabled', true);
        // ->whereIn('jp.id', explode(',', $this->settingFix('kdJenisProdukObat')));
        if (isset($r['namaproduk']) && $r['namaproduk'] != '') {
            $dataProduk = $dataProduk->where('pr.namaproduk', 'ilike', '%' . $r['namaproduk'] . '%');
        }
        if (isset($r['limit']) && $r['limit'] != '') {
            $dataProduk = $dataProduk->limit($r['limit']);
        }
        //->where('spd.qtyproduk','>',0)
        $dataProduk = $dataProduk->groupBy('pr.id', 'pr.namaproduk', 'ss.id', 'ss.satuanstandar');
        $dataProduk = $dataProduk->orderBy('pr.namaproduk');
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
            );
        }
        $res['produk'] = $dataProdukResult;
        return $this->respond($res);
    }

    public function getDataFastMoving(Request $request)
    {
        $idProfile = $this->kdProfile;
        $tglAwal = $request['tglawal'];
        $tglAkhir = $request['tglakhir'];
        $kelompokProduk = $this->settingFix('KelompokProdukMonitoringBarang');

        $idKelProduk = ' ';
        if (isset($request['klmproduk']) && $request['klmproduk'] != "" && $request['klmproduk'] != "undefined") {
            $idKelProduk = ' AND kp.id = ' . $request['klmproduk'];
        }

        $idJenisProduk = ' ';
        if (isset($request['idJenisProduk']) && $request['idJenisProduk'] != "" && $request['idJenisProduk'] != "undefined") {
            $idJenisProduk = ' AND jp.id = ' . $request['idJenisProduk'];
        }
        $namaProduk = ' ';
        if (isset($request['namaproduk']) && $request['namaproduk'] != "" && $request['namaproduk'] != "undefined") {
            $namaProduk = " AND prd.namaproduk ilike '%"  . $request['namaproduk'] . "%'";
        }

        $data = DB::select(DB::raw("
        select sum(x.jumlah) as jumlah,x.namaproduk,x.kelompokproduk from (SELECT
            pp.tglpelayanan,
            pp.produkfk,
            prd.namaproduk,
            pp.jumlah,
            kp.id AS idkelompokproduk,
            kp.kelompokproduk,
            jp.id AS idjenisproduk,
            jp.jenisproduk 
        FROM
            pelayananpasien_t AS pp
            INNER JOIN produk_m AS prd ON prd.id = pp.produkfk
            LEFT JOIN detailjenisproduk_m AS djp ON djp.id = prd.objectdetailjenisprodukfk
            LEFT JOIN jenisproduk_m AS jp ON jp.id = djp.objectjenisprodukfk
            LEFT JOIN kelompokproduk_m AS kp ON kp.id = jp.objectkelompokprodukfk
            LEFT JOIN antrianpasiendiperiksa_t AS apd ON apd.norec = pp.noregistrasifk
            LEFT JOIN strukresep_t AS ar ON ar.norec = pp.strukresepfk
            INNER JOIN ruangan_m AS ru ON ru.id = apd.objectruanganfk 
            WHERE
            pp.kdprofile = $idProfile 
            AND pp.tglpelayanan::date between '$tglAwal' and '$tglAkhir'
            $idKelProduk
            $idJenisProduk
            $namaProduk  
            AND kp.id IN ($kelompokProduk)
        UNION ALL
            SELECT
            pp.tglpelayanan,
            pp.produkfk,
            prd.namaproduk,
            pp.jumlah,
            kp.id AS idkelompokproduk,
            kp.kelompokproduk,
            jp.id AS idjenisproduk,
            jp.jenisproduk 
        FROM
            pelayananpasienobatkronis_t AS pp
            INNER JOIN produk_m AS prd ON prd.id = pp.produkfk
            LEFT JOIN detailjenisproduk_m AS djp ON djp.id = prd.objectdetailjenisprodukfk
            LEFT JOIN jenisproduk_m AS jp ON jp.id = djp.objectjenisprodukfk
            LEFT JOIN kelompokproduk_m AS kp ON kp.id = jp.objectkelompokprodukfk
            LEFT JOIN antrianpasiendiperiksa_t AS apd ON apd.norec = pp.noregistrasifk
            LEFT JOIN strukresep_t AS ar ON ar.norec = pp.strukresepfk
            INNER JOIN ruangan_m AS ru ON ru.id = apd.objectruanganfk 
            WHERE
            pp.kdprofile = $idProfile 
            AND pp.tglpelayanan::date between '$tglAwal' and '$tglAkhir'
            $idKelProduk
            $idJenisProduk
            $namaProduk
            AND kp.id IN ($kelompokProduk))x
        group by x.namaproduk, x.kelompokproduk	
        order by jumlah desc
        
        "));
        return $this->respond($data);
    }


    public function getDataSlowMoving(Request $request)
    {
        $idProfile = $this->kdProfile;
        $tglAwal = $request['tglawal'];
        $tglAkhir = $request['tglakhir'];
        
        $jeniBarangFarmasi = $this->settingFix('kdJenisProdukObat');

        $idKelProduk = ' ';
        if (isset($request['klmproduk']) && $request['klmproduk'] != "" && $request['klmproduk'] != "undefined") {
            $idKelProduk = ' AND kp.id = ' . $request['klmproduk'];
        }

        $idJenisProduk = ' ';
        if (isset($request['idJenisProduk']) && $request['idJenisProduk'] != "" && $request['idJenisProduk'] != "undefined") {
            $idJenisProduk = ' AND jp.id = ' . $request['idJenisProduk'];
        }
        $namaProduk = ' ';
        if (isset($request['namaproduk']) && $request['namaproduk'] != "" && $request['namaproduk'] != "undefined") {
            $namaProduk = " AND prd.namaproduk ilike '%"  . $request['namaproduk'] . "%'";
        }

        $data = DB::select(DB::raw("
        SELECT ROW_NUMBER() OVER (ORDER BY prd.namaproduk) AS nomor_urutan,
        prd.id,prd.namaproduk,kp.kelompokproduk
        FROM produk_m prd
        LEFT JOIN detailjenisproduk_m AS djp ON djp.id = prd.objectdetailjenisprodukfk
        LEFT JOIN jenisproduk_m AS jp ON jp.id = djp.objectjenisprodukfk
        LEFT JOIN kelompokproduk_m AS kp ON kp.id = jp.objectkelompokprodukfk
        WHERE jp.id IN ($jeniBarangFarmasi) and prd.statusenabled =true and prd.kdprofile = $idProfile 
        $namaProduk
        and prd.id NOT IN (
            SELECT
            pp.produkfk
        FROM
            pelayananpasien_t AS pp
            INNER JOIN produk_m AS prd ON prd.id = pp.produkfk
            LEFT JOIN detailjenisproduk_m AS djp ON djp.id = prd.objectdetailjenisprodukfk
            LEFT JOIN jenisproduk_m AS jp ON jp.id = djp.objectjenisprodukfk
            LEFT JOIN kelompokproduk_m AS kp ON kp.id = jp.objectkelompokprodukfk
        WHERE
            pp.tglpelayanan::date between '$tglAwal' and '$tglAkhir'
            $idKelProduk
            $idJenisProduk
            $namaProduk
        )
        order by prd.namaproduk
        "));
        
        return $this->respond($data);
    }

    public function getDataDeadMoving(Request $request)
    {
        $jeniBarangFarmasi = $this->settingFix('kdJenisProdukObat');
        $idProfile = $this->kdProfile;
        $tglAwal = $request['tglawal'];
        $tglAkhir = $request['tglakhir'];

        $idKelProduk = ' ';
        if (isset($request['idKelProduk']) && $request['idKelProduk'] != "" && $request['idKelProduk'] != "undefined") {
            $idKelProduk = 'WHERE kp.id = ' . $request['idKelProduk'];
        }

        $idJenisProduk = ' ';
        if (isset($request['idJenisProduk']) && $request['idJenisProduk'] != "" && $request['idJenisProduk'] != "undefined") {
            $idJenisProduk = ' AND jp.id = ' . $request['idJenisProduk'];
        }
        $namaProduk = ' ';
        if (isset($request['namaproduk']) && $request['namaproduk'] != "" && $request['namaproduk'] != "undefined") {
            $namaProduk = " AND prd.namaproduk ilike '%"  . $request['namaproduk'] . "%'";
        }

        $data = DB::select(DB::raw("
        SELECT  ROW_NUMBER() OVER (ORDER BY prd.namaproduk) AS nomor_urutan,
        prd.id,prd.namaproduk,kp.kelompokproduk
        FROM produk_m prd
        LEFT JOIN detailjenisproduk_m AS djp ON djp.id = prd.objectdetailjenisprodukfk
        LEFT JOIN jenisproduk_m AS jp ON jp.id = djp.objectjenisprodukfk
        LEFT JOIN kelompokproduk_m AS kp ON kp.id = jp.objectkelompokprodukfk
        WHERE jp.id IN ( $jeniBarangFarmasi ) and prd.statusenabled = true and prd.kdprofile = $idProfile 
        $namaProduk
        and prd.id NOT IN (
            SELECT
            pp.produkfk
        FROM
            pelayananpasien_t AS pp
            INNER JOIN produk_m AS prd ON prd.id = pp.produkfk
            LEFT JOIN detailjenisproduk_m AS djp ON djp.id = prd.objectdetailjenisprodukfk
            LEFT JOIN jenisproduk_m AS jp ON jp.id = djp.objectjenisprodukfk
            LEFT JOIN kelompokproduk_m AS kp ON kp.id = jp.objectkelompokprodukfk
            $idKelProduk
            $idJenisProduk
            $namaProduk
        )
        order by prd.namaproduk
        "));
        return $this->respond($data);
    }

}
