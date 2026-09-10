<?php

namespace App\Http\Controllers\Logistik;

use App\Http\Controllers\Controller;
use App\Models\Master\DetailJenisProduk;
use App\Models\Master\JenisProduk;
use App\Models\Master\Produk;
use App\Models\Master\Ruangan;
use App\Models\Transaksi\SaldoProdukDetail;
use App\Traits\Valet;
use Exception;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PersediaanCtrl extends Controller
{

    use Valet;
    public function __construct()
    {
        parent::__construct($is_encrypt = true);
    }

    public function saveSaldoProdukDetail(Request $request, $lokal = false)
    {
        set_time_limit(300);
        $kdProfile = $this->kdProfile;
        
        DB::beginTransaction();
        try {
            $tglAyeuna = date('Y-m-d H:i:s');
            $tgl = date('Y-m-d 00:00:00');

            $details = DB::select(
                DB::raw("
            select * from saldoprodukdetail_t where tglsaldo=:tgl and kdprofile=$kdProfile "),
                array(
                    'tgl' => $tgl,
                )
            );

            if (count($details) > 0) {
                $transMessage = "Sudah Update";
                $result = array(
                    "status" => 400,
                    "result"  => null
                );
                if ($lokal) {
                    return $result;
                }
                return $this->respond($result['result'], $result['status'], $transMessage);
            }

            $aslProduk = $this->settingFix('idAsalProdukPersediaan');
            $data = DB::select(DB::raw("

            select objectruanganfk,objectprodukfk,harganetto2,tglkadaluarsa,objectasalprodukfk,
            sum(qtyproduk) as qty
            from stokprodukdetail_t where statusenabled='t' and qtyproduk <> 0
            GROUP BY objectruanganfk,objectprodukfk,harganetto2,tglkadaluarsa,objectasalprodukfk
            order by objectruanganfk;
        "));
            $dataInsert = [];
            foreach ($data as $item) {

                $dataInsert[] = array(
                    'norec' => $this->Uuid4(),
                    'kdprofile' => $kdProfile,
                    'statusenabled' => true,
                    'objectasalprodukfk' =>  $item->objectasalprodukfk,
                    'harganetto' =>  $item->harganetto2,
                    'objectprodukfk' =>   $item->objectprodukfk,
                    'qtyproduk' =>   $item->qty,
                    'objectruanganfk' =>  $item->objectruanganfk,
                    'tglkadaluarsa' =>  $item->tglkadaluarsa,
                    'tglsaldo' => $tgl,
                    'tglclosing' => $tglAyeuna
                );
            }
            $chunkSize = 2000; // Adjust the chunk size based on your needs

            collect($dataInsert)->chunk($chunkSize)->each(function ($chunk) {
                DB::table('saldoprodukdetail_t')->insert($chunk->toArray());
            });
            $this->LOGGING(
                'Posting Saldo Harian Stok Otomatis',
                '',
                'stokprodukdetail_t',
                'Posting Saldo Harian Stok Otomatis Sukses Tanggal ' . $tglAyeuna
            );
            // foreach ($data as $item) {

            //     $dataInsert[] = array(
            //         'norec' => $this->Uuid4(),
            //         'kdprofile' => $kdProfile,
            //         'statusenabled' => true,
            //         'objectasalprodukfk' =>  $item->objectasalprodukfk,
            //         'harganetto' =>  $item->harganetto2,
            //         'objectprodukfk' =>   $item->objectprodukfk,
            //         'qtyproduk' =>   $item->qty,
            //         'objectruanganfk' =>  $item->objectruanganfk,
            //         'tglkadaluarsa' =>  $item->tglkadaluarsa,
            //         'tglsaldo' => $tgl,
            //         'tglclosing' => $tglAyeuna
            //     );
            //     if (count($dataInsert) > 100) {
            //         SaldoProdukDetail::insert($dataInsert);
            //         $dataInsert = [];
            //     }
            // }
            // if (count($dataInsert) > 0) {
            //     SaldoProdukDetail::insert($dataInsert);
            //     $dataInsert = [];
            // }
            $transMessage = "Sukses";
            DB::commit();
            $result = array(
                "status" => 200,
                "result" => array(
                    "as" => '@epic',
                ),
            );
        } catch (Exception $e) {
            $transMessage = "Simpan Gagal";
            DB::rollBack();
            $result = array(
                "status" => 400,
                "result"  => $e->getMessage() . ' ' . $e->getLine()

            );
        }

        if ($lokal) {
            return $result;
        }
        return $this->respond($result['result'], $result['status'], $transMessage);
    }

    public function getRuanganPersediaan(Request $request)
    {
        $res['ruangan'] = Ruangan::mine()
            ->whereIn('objectdepartemenfk', [$this->settingFix('idInstalasiFarmasi')])
            ->get();
        return $this->respond($res);
    }
    public function getDataSaldoRuanganDetail(Request $request)
    {
        $kdProfile = $this->kdProfile;
        $tglAwal = $request['tglAwal'] . ' 00:00:00';
        $tglAkhir = $request['tglAwal'] . ' 23:59:59';


        // $paramKelProduk = "";
        // if(isset($request['kelompokprodukid']) && $request['kelompokprodukid']!="" && $request['kelompokprodukid']!="undefined"){
        //     $paramKelProduk = " AND jp.objectkelompokprodukfk = " . $request['kelompokprodukid'];
        // }

        // $paramJenisProduk = "";
        // if(isset($request['jeniskprodukid']) && $request['jeniskprodukid']!="" && $request['jeniskprodukid']!="undefined"){
        //     $paramJenisProduk = " AND djp.objectjenisprodukfk = " . $request['jeniskprodukid'];
        // }

        $paramProduk = "";
        if (isset($request['produkfk']) && $request['produkfk'] != "" && $request['produkfk'] != "undefined") {
            $paramProduk = " AND spd.objectprodukfk = " . $request['produkfk'];
        }

        $paramNamaProduk = "";
        if (isset($request['namaproduk']) && $request['namaproduk'] != "" && $request['namaproduk'] != "undefined") {
            $paramNamaProduk = " AND pr.namaproduk ILIKE '%" . $request['namaproduk'] . "%'";
        }


        $paramRuangan = "";
        if (isset($request['ruanganfk']) && $request['ruanganfk'] != "" && $request['ruanganfk'] != "undefined") {
            $paramRuangan = " AND spd.objectruanganfk = " .  $request['ruanganfk'];
        }
        $rows = "";
        if (isset($request['rows']) && $request['rows'] != "" && $request['rows'] != "undefined") {
            $rows = " limit " .  $request['rows'];
        }

        // $paramAsalProduk = "";
        // if(isset($request['asalprodukfk']) && $request['asalprodukfk']!="" && $request['asalprodukfk']!="undefined"){
        //     $paramAsalProduk = " AND spd.objectasalprodukfk = " . $request['asalprodukfk'];
        // }

        $data = collect(DB::select("
            SELECT 
                spd.objectprodukfk,
                pr.kdproduk,
                pr.namaproduk,
                spd.qtyproduk  AS jumlah,
                djp.detailjenisproduk,
                ss.satuanstandar
            FROM saldoprodukdetail_t AS spd
            INNER JOIN produk_m AS pr ON pr.id = spd.objectprodukfk
            INNER JOIN satuanstandar_m AS ss ON ss.id = pr.objectsatuanstandarfk
            INNER JOIN detailjenisproduk_m as djp on djp.id = pr.objectdetailjenisprodukfk
            WHERE pr.statusenabled = true AND spd.statusenabled = true
            AND pr.kdprofile = $kdProfile AND spd.kdprofile = $kdProfile
            AND spd.tglsaldo BETWEEN '$tglAwal' AND '$tglAkhir'
            $paramProduk
            $paramNamaProduk
            $paramRuangan
            $rows

        "));


        $result = array(
            'data' => $data,
            'message' => '@epic',
        );
        return $this->respond($result);
    }


    public function getDropdownPersediaan(Request $request)
    {
        $res['jenisproduk'] = JenisProduk::mine()->get();
        $res['detailjenisproduk'] = DetailJenisProduk::mine()->get();
        return $this->respond($res);
    }
    public function getLaporanPersediaan_v4_2(Request $request)
    {

        $kdProfile =  $this->kdProfile;
        $tglAwal = $request['tglawal'];
        $tglAkhir = $request['tglakhir'];
        $tglawalsaldo = $request['tglakhir'];
        $tglakhirsaldo = $request['tglakhirsaldo'];
        $tglAyeuna = date('Y-m-d 23:59');
        $bulanlalu = $request['blnLalu'];
        $namaproduk = $request['nmproduk'];

        $filterDjProduk = "";
        if (isset($request['djp']) && $request['djp'] != "" && $request['djp'] != "undefined") {
            $filterDjProduk = " and pr.objectdetailjenisprodukfk in (" . $request['djp'] . ")";
        }
        $filterJenisProduk = "";
        if (isset($request['jp']) && $request['jp'] != "" && $request['jp'] != "undefined") {
            $filterJenisProduk = " and djp.objectjenisprodukfk in (" . $request['jp'] . ")";
        }

        //        $tglresepBlnIni = 'O/' . date('ym', strtotime($tglAwal));
        //  select '0' as norec,pr.id,pr.namaproduk,
        //                                 sum(cp.qtyproduk) AS saldoawal,
        //                                 0 AS qtyterima,
        //                                 0 AS qtykeluar,
        //                                 0 AS saldoakhir,0 as returjual,0 as returbeli,round(cp.harganetto) as hargasatuan,ap.asalproduk,case when cp.objectasalprodukfk = 1 then 16 else cp.objectasalprodukfk end as apid,ss.satuanstandar
        //                                 from saldoprodukdetail_t AS cp
        //                                 inner join produk_m as pr on pr.id = cp.objectprodukfk
        //                                 LEFT JOIN satuanstandar_m AS ss ON ss.id = pr.objectsatuanstandarfk
        //                                 LEFT JOIN asalproduk_m AS ap ON ap.id = case when cp.objectasalprodukfk = 1 then 16 else cp.objectasalprodukfk end
        //                                 where cp.tglsaldo = '$tglAwal'
        //                                 and cp.kdprofile = 21
        //                                 and cp.statusenabled = TRUE
        //                                 --and pr.statusenabled = TRUE
        //                                 and cp.qtyproduk >0
        //                                 and cp.objectruanganfk <>211
        //                     group by pr.id,pr.namaproduk,cp.harganetto,cp.objectasalprodukfk,ss.satuanstandar,ap.asalproduk



        $data = DB::select(DB::raw("

        select x.id,x.namaproduk,sum(x.saldoawal) as saldoawal,sum(x.qtyterima) as qtyterima,sum(x.qtykeluar) as qtykeluar,sum(x.saldoakhir) as saldoakhir,
        sum(x.returjual) as returjual,sum(x.returbeli) as returbeli,
        x.hargasatuan,x.asalproduk,x.apid,x.satuanstandar  from (
            select '0' as norec,pr.id,pr.namaproduk,
                        sum(cp.saldoakhir) AS saldoawal,
                        0 AS qtyterima,
                        0 AS qtykeluar,
                        0 AS saldoakhir,0 as returjual,0 as returbeli,(cp.harga) as hargasatuan,ap.asalproduk,case when cp.asalprodukfk = 1 then 16 else cp.asalprodukfk end as apid,ss.satuanstandar
                        from closingpersediaan_t AS cp
                        inner join produk_m as pr on pr.id = cp.produkfk
                        INNER JOIN detailjenisproduk_m as djp on djp.id = pr.objectdetailjenisprodukfk
                        LEFT JOIN satuanstandar_m AS ss ON ss.id = pr.objectsatuanstandarfk
                        LEFT JOIN asalproduk_m AS ap ON ap.id = case when cp.asalprodukfk = 1 then 16 else cp.asalprodukfk end
                        where cp.bulanclosing = '$bulanlalu'
                        and cp.kdprofile = $kdProfile
                        and cp.statusenabled = TRUE
                        --and pr.statusenabled = TRUE
                        and cp.saldoakhir >0 $filterDjProduk $filterJenisProduk
            group by pr.id,pr.namaproduk,cp.harga,cp.asalprodukfk,ss.satuanstandar,ap.asalproduk
            union all

                        -- penerimaan
                        select sp.norec,pr.id,pr.namaproduk, 0 as saldoawal,(spd.qtyproduk) as qtyterima,0 as qtykeluar,
                        0 as saldoakhir,0 as returjual,0 as returbeli,round(spd.hargasatuan) as hargasatuan ,ap.asalproduk,
                        case when ap.id = 1 then 16 else ap.id end as apid,ss.satuanstandar
                        from strukpelayanan_t sp
                        INNER JOIN strukpelayanandetail_t spd on spd.nostrukfk=sp.norec
                        INNER JOIN produk_m pr on pr.id=spd.objectprodukfk
                        INNER JOIN detailjenisproduk_m as djp on djp.id = pr.objectdetailjenisprodukfk
                        INNER JOIN asalproduk_m ap on ap.id=case when spd.objectasalprodukfk = 1 then 16 else spd.objectasalprodukfk end
                        LEFT JOIN satuanstandar_m AS ss ON ss.id = pr.objectsatuanstandarfk
                        where sp.tglstruk between '$tglAwal' and '$tglAkhir'
                        and sp.objectkelompoktransaksifk=35
                        and sp.statusenabled = TRUE $filterDjProduk $filterJenisProduk
                        --and pr.statusenabled = TRUE

             union all

                 -- returbeli
                 SELECT  --ppr.*
                 sr.norec,pr.id,pr.namaproduk, 0 as saldoawal,ppr.qtyproduk as qtyterima,0 as qtykeluar,
                 0 as saldoakhir,0 as returjual,ppr.qtyproduk as returbeli,0 as hargasatuan ,'' as asalproduk,
                 0 as apid,ss.satuanstandar
                 FROM strukretur_t AS sr
                 INNER JOIN strukreturdetail_t AS ppr ON sr.norec = ppr.strukreturfk
--                 -- inner join strukpelayanandetail_t spd on spd.nostrukfk=ppr.nostrukterimafk and spd.objectprodukfk=ppr.objectprodukfk
                 INNER JOIN produk_m as pr on pr.id = ppr.objectprodukfk
                 INNER JOIN detailjenisproduk_m as djp on djp.id = pr.objectdetailjenisprodukfk
--                  --INNER JOIN asalproduk_m ap on ap.id=case when spd.objectasalprodukfk  = 1 then 16 else spd.objectasalprodukfk  end
                 LEFT JOIN satuanstandar_m AS ss ON ss.id = pr.objectsatuanstandarfk
                 WHERE  sr.tglretur BETWEEN '$tglAwal' and '$tglAkhir'
                 AND sr.statusenabled = true $filterDjProduk $filterJenisProduk
                 --and pr.statusenabled = TRUE

                -- ## ## ## ## ## ## ## ## ## ## ## #
                -- ####   tambah adjusment trx  #####
                -- ## ## ## ## ## ## ## ## ## ## ## #

            ) as x
            where x.namaproduk ilike '%$namaproduk%'
            group by x.id,x.namaproduk, x.hargasatuan,x.asalproduk,x.apid,x.satuanstandar
            order by x.namaproduk,x.hargasatuan asc

        "));
        $data2 = DB::select(DB::raw("

            select x.id,x.namaproduk,sum(x.saldoawal) as saldoawal,sum(x.qtyterima) as qtyterima,sum(x.qtykeluar) as qtykeluar,sum(x.saldoakhir) as saldoakhir,sum(x.returjual) as returjual,sum(x.returbeli) as returbeli
            from (

            --resep
            select x.norec,x.id,x.namaproduk,0 as saldoawal,0 as qtyterima,sum(x.jumlah) as qtykeluar,0 as saldoakhir,0 as returjual,0 as returbeli, x.hargasatuan,x.asalproduk,x.apid,x.satuanstandar from
            (select pr.id,pp.norec,pr.namaproduk,pp.jumlah,0 as harganetto,0 as hargasatuan,pp.strukterimafk ,'' as asalproduk,
            16 as apid,ss.satuanstandar
            from pelayananpasien_t pp
            INNER JOIN produk_m pr on pr.id=pp.produkfk
            INNER JOIN detailjenisproduk_m as djp on djp.id = pr.objectdetailjenisprodukfk
            LEFT JOIN satuanstandar_m AS ss ON ss.id = pr.objectsatuanstandarfk
            where pp.isobat='t'
            and pp.tglpelayanan between  '$tglAwal' and '$tglAkhir' $filterDjProduk $filterJenisProduk
            --and pp.statusenabled='t'
            ) as x
            group by x.norec,x.id,x.namaproduk, x.hargasatuan,x.asalproduk,x.apid,x.satuanstandar

            -- select x.norec,x.id,x.namaproduk,0 as saldoawal,0 as qtyterima,sum(x.jumlah) as qtykeluar,0 as saldoakhir,0 as returjual,0 as returbeli, x.hargasatuan,x.asalproduk,x.apid,x.satuanstandar from
--             (select  pr.id,pp.norec,pr.namaproduk,pp.jumlah,0 as harganetto,0  as hargasatuan,pp.strukterimafk ,'' as asalproduk,
--             16 as apid,ss.satuanstandar
--             from pelayananpasien_t pp
--             INNER JOIN produk_m pr on pr.id=pp.produkfk
--             --inner join strukpelayanandetail_t spd on spd.nostrukfk=pp.strukterimafk and spd.objectprodukfk=pp.produkfk
--             --INNER JOIN asalproduk_m ap on ap.id=case when spd.objectasalprodukfk = 1 then 16 else spd.objectasalprodukfk end
--             LEFT JOIN satuanstandar_m AS ss ON ss.id = pr.objectsatuanstandarfk
--             LEFT JOIN strukresep_t sr on sr.norec=pp.strukresepfk
--             LEFT JOIN antrianpasiendiperiksa_t apd on apd.norec=sr.pasienfk
--             LEFT JOIN pasiendaftar_t pd on pd.norec=apd.noregistrasifk
--             LEFT JOIN pasien_m ps on ps.id=pd.nocmfk
--             where pp.isobat='t'
--             and pp.tglpelayanan between '$tglAwal' and '$tglAkhir'
--             and pr.statusenabled = TRUE) as x
--             group by x.norec,x.id,x.namaproduk, x.hargasatuan,x.asalproduk,x.apid,x.satuanstandar

            union all

            --obat bebas
            select  sp.norec ,pr.id,pr.namaproduk,0 as saldoawal,0 as qtyterima, (spd.qtyproduk) as qtykeluar,0 as saldoakhir, 0 as returjual,0 as returbeli,
            round(spd.hargasatuan) as hargasatuan,'' as asalproduk,0 as apid,ss.satuanstandar
            from strukpelayanan_t sp
            INNER JOIN strukpelayanandetail_t spd on spd.nostrukfk=sp.norec
            INNER JOIN produk_m pr on pr.id=spd.objectprodukfk
            INNER JOIN detailjenisproduk_m as djp on djp.id = pr.objectdetailjenisprodukfk
            LEFT JOIN satuanstandar_m AS ss ON ss.id = pr.objectsatuanstandarfk
            where sp.tglstruk between '$tglAwal' and '$tglAkhir'
            and substring(sp.nostruk,1,2)='OB'
            and spd.objectprodukfk <> 10013803
            and sp.statusenabled = TRUE $filterDjProduk $filterJenisProduk

            -- select  sp.norec ,pr.id,pr.namaproduk,0 as saldoawal,0 as qtyterima, (spd.qtyproduk) as qtykeluar,0 as saldoakhir, 0 as returjual,0 as returbeli,
--             round(spd.hargasatuan) as hargasatuan,ap.asalproduk,case when ap.id = 1 then 16 else ap.id end as apid,ss.satuanstandar
--             from strukpelayanan_t sp
--             INNER JOIN strukpelayanandetail_t spd on spd.nostrukfk=sp.norec
--             INNER JOIN produk_m pr on pr.id=spd.objectprodukfk
--             INNER JOIN asalproduk_m ap on ap.id=case when spd.objectasalprodukfk = 1 then 16 else spd.objectasalprodukfk end
--             LEFT JOIN satuanstandar_m AS ss ON ss.id = pr.objectsatuanstandarfk
--             where sp.tglstruk between '$tglAwal' and '$tglAkhir'
--             and substring(sp.nostruk,1,2)='OB'
--             and spd.objectprodukfk <> 10013803
--             and sp.statusenabled = TRUE
--             and pr.statusenabled = TRUE $filterDjProduk

            union all

                --amprah
                SELECT   sk.norec,  pr.id,pr.namaproduk,0 as saldoawal,0 as qtyterima,(kp.qtyproduk) as qtykeluar,0 as saldoakhir, 0 as returjual,0 as returbeli,
                0 as hargasatuan,'' as asalproduk,0 as apid,ss.satuanstandar
                FROM strukkirim_t AS sk
                INNER JOIN kirimproduk_t AS kp ON kp.nokirimfk = sk.norec
                INNER JOIN produk_m as pr on pr.id = kp.objectprodukfk
                INNER JOIN detailjenisproduk_m as djp on djp.id = pr.objectdetailjenisprodukfk
                LEFT JOIN satuanstandar_m AS ss ON ss.id = pr.objectsatuanstandarfk
                WHERE sk.kdprofile = $kdProfile and sk.jenispermintaanfk = 1
                and sk.statusenabled = TRUE and kp.qtyproduk > 0
                and sk.tglkirim BETWEEN '$tglAwal' and '$tglAkhir' $filterDjProduk $filterJenisProduk
                --and pr.statusenabled = TRUE

                -- SELECT   sk.norec,  pr.id,pr.namaproduk,0 as saldoawal,0 as qtyterima,(kp.qtyproduk) as qtykeluar,0 as saldoakhir, 0 as returjual,0 as returbeli,
--                 0 as hargasatuan,'' as asalproduk,0 as apid,ss.satuanstandar
--                 FROM strukkirim_t AS sk
--                 INNER JOIN kirimproduk_t AS kp ON kp.nokirimfk = sk.norec
-- --                 inner join strukpelayanandetail_t spd on spd.nostrukfk=kp.nostrukterimafk and spd.objectprodukfk=kp.objectprodukfk
--                 INNER JOIN produk_m as pr on pr.id = kp.objectprodukfk
-- --                 INNER JOIN asalproduk_m ap on ap.id=case when spd.objectasalprodukfk = 1 then 16 else spd.objectasalprodukfk end
--                 INNER JOIN ruangan_m ru on ru.id=sk.objectruangantujuanfk
--                 LEFT JOIN satuanstandar_m AS ss ON ss.id = pr.objectsatuanstandarfk
--                 WHERE sk.kdprofile = $kdProfile and sk.jenispermintaanfk = 1
--                 and sk.statusenabled = TRUE and kp.qtyproduk > 0
--                 and sk.tglkirim BETWEEN '$tglAwal' and '$tglAkhir'
--                 and pr.statusenabled = TRUE

            union all

                --STOK OPNAME
                select sc.norec,  pr.id,pr.namaproduk,0 AS saldoawal,
                case when spdo.qtyproduksystem-spdo.qtyprodukreal < 0 then (spdo.qtyproduksystem-spdo.qtyprodukreal) * (-1) else 0 end as qtyterima,
                case when spdo.qtyproduksystem-spdo.qtyprodukreal > 0 then spdo.qtyproduksystem-spdo.qtyprodukreal else 0 end as qtykeluar,
                0 as saldoakhir,0 as returjual,0 as returbeli,round(spdo.harganetto1) AS hargasatuan,ap.asalproduk,case when spdo.objectasalprodukfk = 1 then 16 else spdo.objectasalprodukfk end as apid,ss.satuanstandar
                from strukclosing_t as sc
                INNER JOIN stokprodukdetailopname_t as spdo on spdo.noclosingfk=sc.norec
                LEFT JOIN produk_m as pr on pr.id=spdo.objectprodukfk
                INNER JOIN detailjenisproduk_m as djp on djp.id = pr.objectdetailjenisprodukfk
                LEFT JOIN satuanstandar_m AS ss ON ss.id = pr.objectsatuanstandarfk
                INNER JOIN ruangan_m AS ru ON ru.id = sc.objectruanganfk
                LEFT JOIN asalproduk_m AS ap ON ap.id = case when spdo.objectasalprodukfk = 1 then 16 else spdo.objectasalprodukfk end
                where sc.kdprofile = $kdProfile
                and sc.statusenabled = true
                and sc.tglclosing BETWEEN '$tglAwal' and '$tglAkhir'
                and spdo.qtyproduksystem <> spdo.qtyprodukreal $filterDjProduk $filterJenisProduk
                --and pr.statusenabled = TRUE

            union all

                -- returjual
                SELECT
                sr.norec,pr.id,pr.namaproduk, 0 as saldoawal,0 as qtyterima,ppr.jumlah as qtykeluar,
                0 as saldoakhir,ppr.jumlah as returjual,0 as returbeli,0 as hargasatuan ,'' as asalproduk,
                0 apid,ss.satuanstandar
                FROM strukretur_t AS sr
                INNER JOIN pelayananpasienretur_t AS ppr ON sr.norec = ppr.strukreturfk
                INNER JOIN produk_m as pr on pr.id = ppr.produkfk
                INNER JOIN detailjenisproduk_m as djp on djp.id = pr.objectdetailjenisprodukfk
                LEFT JOIN satuanstandar_m AS ss ON ss.id = pr.objectsatuanstandarfk
                WHERE  sr.tglretur BETWEEN '$tglAwal' and '$tglAkhir'
                AND sr.statusenabled = true $filterDjProduk $filterJenisProduk
                --and pr.statusenabled = TRUE

                union all

                --pemakaian ruangan
                SELECT  sp.norec,pr.id,pr.namaproduk, 0 as saldoawal,0 as qtyterima,spd.qtyproduk as qtykeluar,
                0 as saldoakhir,0 as returjual,0 as returbeli,0 as hargasatuan ,'' as asalproduk,
                0 apid,ss.satuanstandar
                    from strukpelayanan_t sp
                    INNER JOIN strukpelayanandetail_t spd on sp.norec=spd.nostrukfk
                    left JOIN produk_m as pr on pr.id=spd.objectprodukfk
                    INNER JOIN detailjenisproduk_m as djp on djp.id = pr.objectdetailjenisprodukfk
                    left JOIN satuanstandar_m as ss on ss.id=spd.objectsatuanstandarfk
                    INNER JOIN ruangan_m ru on ru.id=sp.objectruanganfk

                    where objectkelompoktransaksifk=45 and
                    tglstruk BETWEEN '$tglAwal' and '$tglAkhir'
                    AND sp.statusenabled = true
                    and pr.statusenabled = TRUE $filterDjProduk $filterJenisProduk

                union ALL
					-- kadaluarsa
                    select '0' as norec, pr.id,pr.namaproduk, 0 as saldoawal,0 as qtyterima,SUM(spd.qtyproduk)  as qtykeluar,0 as saldoakhir,0 as returjual,0 as returbeli,0 as hargasatuan ,'' as asalproduk,
                    0 apid,ss.satuanstandar
                    from stokprodukdetail_t as spd
                    INNER JOIN produk_m as pr on pr.id = spd.objectprodukfk
                    INNER JOIN detailjenisproduk_m as djp on djp.id = pr.objectdetailjenisprodukfk
                    --INNER JOIN jenisproduk_m as jp on jp.id = djp.objectjenisprodukfk
                    INNER JOIN satuanstandar_m as ss on ss.id = pr.objectsatuanstandarfk
                    INNER JOIN ruangan_m as ru on ru.id = spd.objectruanganfk
                    where spd.kdprofile = $kdProfile and pr.statusenabled = true and spd.statusenabled='t'
                    and spd.tglkadaluarsa between '$tglAwal' and '$tglAkhir' $filterDjProduk $filterJenisProduk
                    group by pr.id,pr.namaproduk,ss.satuanstandar

                union all
                    --closing ed
                    select '1' as norec, pr.id,pr.namaproduk, 0 as saldoawal,0 as qtyterima,SUM(ce.stok)  as qtykeluar,0 as saldoakhir,0 as returjual,0 as returbeli,0 as hargasatuan ,'' as asalproduk,
                    0 apid,ss.satuanstandar
                    from closinged_t ce
                    INNER JOIN produk_m as pr on pr.id = ce.produkfk
                    INNER JOIN detailjenisproduk_m as djp on djp.id = pr.objectdetailjenisprodukfk
                    --INNER JOIN jenisproduk_m as jp on jp.id = djp.objectjenisprodukfk
                    INNER JOIN satuanstandar_m as ss on ss.id = pr.objectsatuanstandarfk
                    INNER JOIN ruangan_m as ru on ru.id = ce.ruanganfk
                    where tglakhir ='2022-12-31 23:59' and ce.kdprofile = $kdProfile $filterDjProduk $filterJenisProduk
                    group by pr.id,pr.namaproduk,ce.harga,ss.satuanstandar


            ) as x
            where x.namaproduk ilike '%$namaproduk%'

            group by x.id,x.namaproduk
            order by x.id ;

    "));
        // and x.id not in (1002122251	,
        //         1002122272	,
        //         1002122225	,
        //         5011986	,
        //         5011983	,
        //         5011881	,
        //         1002122711	,
        //         5013731	,
        //         1002122406	,
        //         5013003	,
        //         5012655	,
        //         1002122620	,
        //         5012377	,
        //         5013739	,
        //         5011567	,
        //         5011986	,
        //         5012746	,
        //         1002122686	,
        //         5012131	,
        //         5012357	,
        //         5012136	,
        //         5011799	,
        //         5011877	,
        //         1002122671	,
        //         5012304	,
        //         5012973	,
        //         5012341	,
        //         5011638	,
        //         5012954	,
        //         1002122290	,
        //         1002122722	,
        //         5011465	,
        //         1002122277	,
        //         5012204	,
        //         5012138	,
        //         5011681	,
        //         5011594	,
        //         5012334	,
        //         5012138	,
        //         5011808	,
        //         5013016	,
        //         1002122772	,
        //         5011616	,
        //         5011789	,
        //         5012076	,
        //         1002122283	,
        //         1002122611	,
        //         5011440	,
        //         5012777	,
        //         5012253	,
        //         5012456	,
        //         5011987	,
        //         1002122270	,
        //         5012445	,
        //         5011907	,
        //         1002122267	,
        //         5012849	,
        //         5012851	,
        //         5012401	,
        //         5012041	,
        //         5011905	,
        //         5012174	,
        //         5011504	,
        //         5011629	,
        //         5011629	,
        //         5012402	,
        //         1002122607	,
        //         5013222
        //         )

        $result = [];
        $saldoAwalReal = 0;
        $saldoAkhirReal = 0;
        $hrgAkhir = 0;
        $hrgsama = false;
        $data1res = [];
        foreach ($data as $itemHiji) {
            $data1res[] = array(
                'apid' => $itemHiji->apid,
                'asalproduk' => $itemHiji->asalproduk,
                'hargasatuan' => $itemHiji->hargasatuan,
                'id' => $itemHiji->id,
                'namaproduk' => $itemHiji->namaproduk,
                'qtykeluar' => (float)$itemHiji->qtykeluar,
                'qtyterima' => (float)$itemHiji->qtyterima,
                'saldoakhir' => (float)$itemHiji->saldoakhir,
                'returjual' => (float)$itemHiji->returjual,
                'returbeli' => (float)$itemHiji->returbeli,
                'saldoawal' => (float)$itemHiji->saldoawal,
                'satuanstandar' => $itemHiji->satuanstandar,
                // 'tgl' =>  $itemHiji->tgl,
            );
        }
        $data2res = [];
        foreach ($data2 as $itemDua) {
            $data2res[] = array(
                'id' => $itemDua->id,
                'namaproduk' => $itemDua->namaproduk,
                'qtykeluar' => $itemDua->qtykeluar,
                'qtyterima' => $itemDua->qtyterima,
                'returjual' =>  (float)$itemDua->returjual,
                'returbeli' =>  (float)$itemDua->returbeli,
                'ttlkeluar' =>  (float)$itemDua->qtykeluar,
                //  'hargasatuan' => $itemDua->hargasatuan,
            );
        }
        $result = [];
        for ($j = count($data1res) - 1; $j >= 0; $j--) {
            if ($data1res[$j]['saldoawal']  == 0) {
                $saldoAwalReal = 0;
                $saldoAkhirReal = 0;
                $hrgAkhir = (float)$data1res[$j]['hargasatuan'];
                $qtyterima = (float)$data1res[$j]['qtyterima'];
                $qtykeluar = 0;
                $saldoAwalReal = (float)$data1res[$j]['saldoawal'];
                $saldoAkhirReal = $saldoAwalReal + $qtyterima - $qtykeluar;
                $result[] = array(
                    'id' => $data1res[$j]['id'],
                    'namaproduk' => $data1res[$j]['namaproduk'],
                    'satuanstandar' => $data1res[$j]['satuanstandar'],
                    'objectasalprodukfk' => $data1res[$j]['apid'],
                    'asalproduk' => $data1res[$j]['asalproduk'],
                    'saldoawal' =>  $saldoAwalReal,
                    'hargasatuan' => $hrgAkhir,
                    'totalrpsaldoawal' => 0,
                    'qtyterima' => $qtyterima,
                    'hargaterima' => 0,
                    'totalhargaterima' => 0,
                    'qtykeluar' => $qtykeluar,
                    'hargakeluar' => 0,
                    'totalhargakeluar' => 0,
                    'saldoakhir' => $saldoAkhirReal,
                    'returjual' => (float)$data1res[$j]['returjual'],
                    'returbeli' => (float)$data1res[$j]['returbeli'],
                    'hargasaldoakhir' => 0,
                    'totalrpsaldoakhir' => 0,
                    'ttlterima' => $saldoAwalReal + $qtyterima,
                    'ttlkeluar' => 0,
                    'qtyed' => 0,
                    'totalrped' => 0
                );
            }
        }
        for ($j = count($data1res) - 1; $j >= 0; $j--) {
            if ($data1res[$j]['saldoawal']  > 0) {
                $saldoAwalReal = 0;
                $saldoAkhirReal = 0;
                $hrgAkhir = (float)$data1res[$j]['hargasatuan'];
                $qtyterima = (float)$data1res[$j]['qtyterima'];
                $qtykeluar = 0;
                $saldoAwalReal = (float)$data1res[$j]['saldoawal'];
                $saldoAkhirReal = $saldoAwalReal + $qtyterima - $qtykeluar;
                $result[] = array(
                    'id' => $data1res[$j]['id'],
                    'namaproduk' => $data1res[$j]['namaproduk'],
                    'satuanstandar' => $data1res[$j]['satuanstandar'],
                    'objectasalprodukfk' => $data1res[$j]['apid'],
                    'asalproduk' => $data1res[$j]['asalproduk'],
                    'saldoawal' =>  $saldoAwalReal,
                    'hargasatuan' => $hrgAkhir,
                    'totalrpsaldoawal' => 0,
                    'qtyterima' => $qtyterima,
                    'hargaterima' => 0,
                    'totalhargaterima' => 0,
                    'qtykeluar' => $qtykeluar,
                    'hargakeluar' => 0,
                    'totalhargakeluar' => 0,
                    'saldoakhir' => $saldoAkhirReal,
                    'returjual' => (float)$data1res[$j]['returjual'],
                    'returbeli' => (float)$data1res[$j]['returbeli'],
                    'hargasaldoakhir' => 0,
                    'totalrpsaldoakhir' => 0,
                    'ttlterima' => $saldoAwalReal + $qtyterima,
                    'ttlkeluar' => 0,
                    'qtyed' => 0,
                    'totalrped' => 0
                );
            }
        }
        for ($i = 0; $i < count($data2res); $i++) {
            for ($j = count($result) - 1; $j >= 0; $j--) {
                if ($data2res[$i]['id'] == $result[$j]['id'] && (float)$data2res[$i]['qtyterima'] >= 0) {
                    $result[$j]['ttlterima'] = $result[$j]['ttlterima'] + $data2res[$i]['qtyterima'] +   $data2res[$i]['returjual'] - (float)$result[$j]['returbeli'];

                    $result[$j]['qtyterima'] = $result[$j]['qtyterima'] + $data2res[$i]['qtyterima'];

                    $result[$j]['returjual'] = $data2res[$i]['returjual'];
                    $result[$j]['returbeli'] = $data2res[$i]['returbeli'];

                    $data2res[$i]['returjual'] = 0;
                    $data2res[$i]['returbeli'] = 0;
                    $data2res[$i]['qtyterima'] = 0;
                    $result[$j]['saldoakhir'] = $result[$j]['ttlterima'];
                    // $result[$j]['ttlkeluar'] = $result[$j]['ttlkeluar'] + $data2res[$i]['qtykeluar'];
                    // $result[$j]['returjual'] = $data2res[$i]['returjual'] ;
                    // $data2res[$i]['returjual'] = 0;
                    // $result[$j]['returbeli'] = $data2res[$i]['returbeli'] ;
                    // $data2res[$i]['returbeli'] = 0;
                }
            }
        }
        for ($i = 0; $i < count($data2res); $i++) {
            for ($j = count($result) - 1; $j >= 0; $j--) {
                if ($data2res[$i]['id'] == $result[$j]['id'] && $data2res[$i]['ttlkeluar'] > 0) {
                    if ($result[$j]['ttlterima']  >= $data2res[$i]['ttlkeluar']) {
                        $result[$j]['saldoakhir'] = $result[$j]['ttlterima'] - $data2res[$i]['ttlkeluar']; //  + $data2res[$i]['returbeli'];
                        $result[$j]['qtykeluar'] = $data2res[$i]['ttlkeluar']; //+   $data2res[$i]['returjual'] - $data2res[$i]['returbeli'];
                        $result[$j]['ttlterima'] = $result[$j]['ttlterima'] - $data2res[$i]['ttlkeluar'];
                        $data2res[$i]['ttlkeluar'] = 0;
                    } else {
                        $result[$j]['saldoakhir'] = 0;
                        $data2res[$i]['ttlkeluar'] =   $data2res[$i]['ttlkeluar'] - $result[$j]['ttlterima'];
                        $result[$j]['qtykeluar'] = $result[$j]['ttlterima']; // +   $data2res[$i]['returjual'] - $data2res[$i]['returbeli'];
                        $result[$j]['ttlterima'] = 0;
                    }
                    $result[$j]['saldoakhir'] = $result[$j]['saldoakhir']; //+   $data2res[$i]['returjual'] - $data2res[$i]['returbeli'];
                }
            }
        }

        $dataHargaED = DB::select(DB::raw("
            select objectprodukfk,harganetto1 from stokprodukdetail_t
            where tglkadaluarsa between '$tglAwal' and '$tglAkhir'
            group by objectprodukfk,harganetto1
        "));
        $rst = [];
        for ($i = 0; $i < count($data2res); $i++) {
            if ($data2res[$i]['ttlkeluar'] > 0) {
                $hrgAkhir = 0;
                $satuanstandar = '';
                $objectasalprodukfk = 0;
                $asalproduk = '';
                for ($j = count($result) - 1; $j >= 0; $j--) {
                    if ($data2res[$i]['id'] == $result[$j]['id']) {
                        $hrgAkhir = (float)$result[$j]['hargasatuan'];
                        $satuanstandar  = $result[$j]['satuanstandar'];
                        $objectasalprodukfk  = $result[$j]['objectasalprodukfk'];
                        $asalproduk  = $result[$j]['asalproduk'];
                        break;
                    }
                }
                if ($hrgAkhir == 0) {
                    foreach ($dataHargaED as $itemDua2) {
                        if ($itemDua2->objectprodukfk == $data2res[$i]['id']) {
                            $hrgAkhir = (float)$itemDua2->harganetto1;
                        }
                    }
                }

                $saldoAwalReal = 0;
                $saldoAkhirReal = 0;
                $qtyterima = round($data2res[$i]['qtyterima'], 3);
                $qtykeluar = round($data2res[$i]['ttlkeluar'], 3);
    
                $saldoAkhirReal = 0;
                $result[] = array(
                    'id' => $data2res[$i]['id'],
                    'namaproduk' => $data2res[$i]['namaproduk'],
                    'satuanstandar' => $satuanstandar,
                    'objectasalprodukfk' => $objectasalprodukfk,
                    'asalproduk' => '', //$asalproduk,
                    'saldoawal' =>  $saldoAwalReal,
                    'hargasatuan' => $hrgAkhir,
                    'totalrpsaldoawal' => $saldoAwalReal * $hrgAkhir,
                    'qtyterima' => $qtyterima,
                    'hargaterima' => $hrgAkhir,
                    'totalhargaterima' => $qtyterima * $hrgAkhir,
                    'qtykeluar' => $qtykeluar,
                    'hargakeluar' => $hrgAkhir,
                    'totalhargakeluar' => $qtykeluar * $hrgAkhir,
                    'saldoakhir' => $saldoAwalReal + $qtyterima - $qtykeluar,
                    'returjual' => $data2res[$i]['returjual'],
                    'returbeli' => $data2res[$i]['returbeli'],
                    'hargasaldoakhir' => $hrgAkhir,
                    'totalrpsaldoakhir' => $saldoAkhirReal * $hrgAkhir,
                    'ttlterima' => $saldoAwalReal + $qtyterima,
                    'ttlkeluar' => 0,
                    'qtyed' => 0,
                    'totalrped' => 0
                );
                //  $rst[] =  array(
                //     'id' => $data2res[$i]['id'],
                //     'namaproduk' => $data2res[$i]['namaproduk'],
                //     'satuanstandar' => $satuanstandar,
                //     'objectasalprodukfk' => $objectasalprodukfk,
                //     'asalproduk' => $asalproduk,
                //     'saldoawal' =>  $saldoAwalReal,
                //     'hargasatuan' => $hrgAkhir,
                //     'totalrpsaldoawal' => $saldoAwalReal * $hrgAkhir,
                //     'qtyterima' => $qtyterima,
                //     'hargaterima' => $hrgAkhir,
                //     'totalhargaterima' => $qtyterima * $hrgAkhir,
                //     'qtykeluar' => $qtykeluar,
                //     'hargakeluar' => $hrgAkhir,
                //     'totalhargakeluar' => $qtykeluar * $hrgAkhir,
                //     'saldoakhir' => $saldoAwalReal + $qtyterima - $qtykeluar,
                //     'returjual' => $data2res[$i]['returjual'],
                //     'returbeli' => $data2res[$i]['returbeli'],
                //     'hargasaldoakhir' => $hrgAkhir,
                //     'totalrpsaldoakhir' => $saldoAkhirReal * $hrgAkhir,
                //     'ttlterima' => $saldoAwalReal + $qtyterima,
                //     'ttlkeluar' => 0,
                //     'qtyed' => 0,
                //     'totalrped' => 0
                // );
            }
        }
        for ($j = count($result) - 1; $j >= 0; $j--) {
            $result[$j]['totalrpsaldoawal']  = $result[$j]['saldoawal']    * $result[$j]['hargasatuan'];
            $result[$j]['totalhargaterima'] =  ($result[$j]['qtyterima'] + $result[$j]['returjual'])  * $result[$j]['hargasatuan'];
            $result[$j]['totalhargakeluar']  = ($result[$j]['qtykeluar'] + $result[$j]['returbeli']) *  $result[$j]['hargasatuan'];
            $result[$j]['totalrpsaldoakhir'] = $result[$j]['saldoakhir'] *  $result[$j]['hargasatuan'];
            $result[$j]['saldoakhir'] = $result[$j]['saldoakhir'];
        }


        $res['data'] = $result;
        $res['data1'] = $data;
        $res['data2'] = $data2;
        $res['data1res'] = $data1res;
        $res['data2res'] = $data2res;
        //  $res['rst'] = $rst;
        return $this->respond($res);
    }
    
    public function getLaporanPersediaan_v5(Request $request)
    {

        $kdProfile =  $this->kdProfile;
        $tglAwal = $request['tglawal'];
        $tglAkhir = $request['tglakhir'];
        $tglawalsaldo = $request['tglakhir'];
        $tglakhirsaldo = $request['tglakhirsaldo'];
        $tglAyeuna = date('Y-m-d 23:59');
        $bulanlalu = $request['blnLalu'];
        $namaproduk = $request['nmproduk'];
        $blnAwal =  $request['blnawal'];
        $blnAkhir =  $request['blnakhir'];

        $filterDjProduk = "";
        if (isset($request['djp']) && $request['djp'] != "" && $request['djp'] != "undefined") {
            $filterDjProduk = " and pr.objectdetailjenisprodukfk in (" . $request['djp'] . ")";
        }
        $filterJenisProduk = "";
        if (isset($request['jp']) && $request['jp'] != "" && $request['jp'] != "undefined") {
            $filterJenisProduk = " and djp.objectjenisprodukfk in (" . $request['jp'] . ")";
        }

        $filterProdukID = "";
        // $filterProdukID = " and pr.ID =4043234 ";
        $filterNamaProduk = "";
        if (isset($request['nmproduk']) && $request['nmproduk'] != "" && $request['nmproduk'] != "undefined") {
            $filterNamaProduk = " and  pr.namaproduk ilike '%" . $request['nmproduk'] . "%'";
        }

        $dataListProduk = DB::select(DB::raw("

            --list produk
            SELECT
                spd.objectprodukfk,	pr.namaproduk,ss.satuanstandar,	16 as objectasalprodukfk,	'BLUD' as asalproduk,
                CAST ( spd.harganetto1 AS FLOAT8 ) AS harganetto1 ,pr.kodebmn
            FROM	produk_m pr
                INNER JOIN detailjenisproduk_m AS djp ON djp.ID = pr.objectdetailjenisprodukfk
                INNER JOIN stokprodukdetail_t spd ON spd.objectprodukfk = pr.id

                LEFT JOIN satuanstandar_m AS ss ON ss.ID = pr.objectsatuanstandarfk
            WHERE
               -- djp.objectjenisprodukfk IN ( 27671, 27670, 97 )
                --AND
                 spd.kdprofile = $kdProfile
                $filterProdukID $filterNamaProduk
            GROUP BY
              spd.objectprodukfk,	pr.namaproduk,	CAST ( spd.harganetto1 AS FLOAT8 ),ss.satuanstandar--,	spd.objectasalprodukfk,	ap.asalproduk
             ,pr.kodebmn
            ORDER BY
                spd.objectprodukfk;


        "));
        // $cp = 0;
        // if ($tglAkhir <= '2023-01-31 23:59'){
        $cp = 1;
        $dataAwal = DB::select(DB::raw("

                --closing persediaan 2022-12-31
                SELECT
                    cp.produkfk AS objectprodukfk,	pr.namaproduk ,		16 as objectasalprodukfk,	'BLUD' as asalproduk,
                    --cp.asalprodukfk as objectasalprodukfk,	ap.asalproduk,
                     ( cp.harga  ) AS harganetto,
                    SUM ( cp.saldoakhir ) AS saldoawal,
                    ( cp.harga  * SUM ( cp.saldoakhir )) as ttl
                FROM
                    closingpersediaan_t cp
                    --INNER JOIN asalproduk_m ap ON ap.ID = cp.asalprodukfk
                    INNER JOIN produk_m pr ON pr.ID = cp.produkfk
                   -- INNER JOIN satuanstandar_m ss ON ss.ID = pr.objectsatuanstandarfk
                    --INNER JOIN detailjenisproduk_m AS djp ON djp.ID = pr.objectdetailjenisprodukfk
                WHERE
                    bulanclosing='$bulanlalu'
                    --and pr.id=26185
                    AND cp.statusenabled = TRUE
                    $filterProdukID $filterNamaProduk
                    AND cp.kdprofile = $kdProfile
                    --AND djp.objectjenisprodukfk IN ( 27671, 27670, 97 )
                GROUP BY
                    cp.produkfk,	pr.namaproduk,
                    --cp.asalprodukfk,	ap.asalproduk,
                     ( cp.harga  )


            "));
        // }else{
            // $dataAwal = DB::select(DB::raw("

            //     --saldo awal
            //     SELECT
            //         spd.objectprodukfk,	pr.namaproduk,		16 as objectasalprodukfk,	'BLUD' as asalproduk,--spd.objectasalprodukfk,	ap.asalproduk,
            //         CAST ( spd.harganetto AS FLOAT8 ) AS harganetto,	SUM ( spd.qtyproduk ) AS saldoawal,
            //         0 as ttl
            //     FROM
            //         saldoprodukdetail_t spd
            //         INNER JOIN produk_m pr ON pr.ID = spd.objectprodukfk
            //         INNER JOIN detailjenisproduk_m AS djp ON djp.ID = pr.objectdetailjenisprodukfk
            //         INNER JOIN asalproduk_m ap ON ap.ID = spd.objectasalprodukfk
            //     WHERE
            //         --spd.tglsaldo = '$blnAwal'
            //         spd.tglsaldo = '2023-12-06 00:00:00'
            //         and spd.statusenabled ='t'
            //         --AND djp.objectjenisprodukfk IN ( 27671, 27670, 97 )
            //         $filterProdukID $filterNamaProduk
            //     GROUP BY
            //         pr.namaproduk,	spd.objectprodukfk,	--spd.objectasalprodukfk,	ap.asalproduk,
            //         CAST ( spd.harganetto AS FLOAT8 ) ;


            // "));
        // }

        //jika masih di tengah bulan
        $dataAkhir = [];
        if ($blnAkhir > date('Y-m-d H:i:s')) {
            $dataAkhir = DB::select(DB::raw("

            --saldo akhir spd
            SELECT
                pr.namaproduk,	16 as objectasalprodukfk,	'BLUD' as asalproduk,--spd.objectasalprodukfk,	ap.asalproduk,
                spd.objectprodukfk,
                CAST ( spd.harganetto1 AS FLOAT8 ) AS harganetto,
                SUM ( spd.qtyproduk ) AS saldoakhir
            FROM
                stokprodukdetail_t spd
                INNER JOIN produk_m pr ON pr.ID = spd.objectprodukfk
                INNER JOIN detailjenisproduk_m AS djp ON djp.ID = pr.objectdetailjenisprodukfk
                INNER JOIN asalproduk_m ap ON ap.ID = spd.objectasalprodukfk
            WHERE
                spd.statusenabled ='t'
                --AND djp.objectjenisprodukfk IN ( 27671, 27670, 97 )
                AND spd.tglkadaluarsa > '$blnAkhir'
                --and spd.qtyproduk > 0
                $filterProdukID $filterNamaProduk

            GROUP BY
                pr.namaproduk,	--spd.objectasalprodukfk,	ap.asalproduk,
                spd.objectprodukfk,
                CAST ( spd.harganetto1 AS FLOAT8 );

        "));
        } else {
            $dataAkhir = DB::select(DB::raw("

                --saldo akhir
                SELECT
                    pr.namaproduk,	16 as objectasalprodukfk,	'BLUD' as asalproduk,--spd.objectasalprodukfk,	ap.asalproduk,
                    spd.objectprodukfk,
                    CAST ( spd.harganetto AS FLOAT8 ) AS harganetto,
                    SUM ( spd.qtyproduk ) AS saldoakhir
                FROM
                    saldoprodukdetail_t spd
                    INNER JOIN produk_m pr ON pr.ID = spd.objectprodukfk
                    INNER JOIN detailjenisproduk_m AS djp ON djp.ID = pr.objectdetailjenisprodukfk
                    --INNER JOIN asalproduk_m ap ON ap.ID = spd.objectasalprodukfk
                WHERE
                    spd.tglsaldo = '$blnAkhir'
                    and spd.statusenabled ='t'
                    AND spd.tglkadaluarsa > '$blnAkhir'
                   -- AND djp.objectjenisprodukfk IN ( 27671, 27670, 97 )
                    $filterProdukID $filterNamaProduk

                GROUP BY
                    pr.namaproduk,	--spd.objectasalprodukfk,	ap.asalproduk,
                    spd.objectprodukfk,
                    CAST ( spd.harganetto AS FLOAT8 );

            "));
        }

        $set = $this->settingFix('kelmpokTransaksiPenerimaanBarang');
        $dataTerima = DB::select(DB::raw("

                --terima suplier
                SELECT
                    spd.objectprodukfk,
                    pr.namaproduk ,16 as objectasalprodukfk,	'BLUD' as asalproduk,
                    --spd.objectasalprodukfk,	ap.asalproduk,
                    SUM ( spd.qtyproduk * spd.hasilkonversi ) AS qtyterima,
                    CAST ( spd.harganetto AS FLOAT8 ) AS harganetto
                FROM
                    strukpelayanan_t sp
                    INNER JOIN strukpelayanandetail_t spd ON spd.nostrukfk = sp.norec
                    INNER JOIN produk_m pr ON pr.ID = spd.objectprodukfk
                    INNER JOIN detailjenisproduk_m AS djp ON djp.ID = pr.objectdetailjenisprodukfk
                    --INNER JOIN asalproduk_m ap ON ap.ID = spd.objectasalprodukfk
                WHERE
                    sp.tglstruk BETWEEN '$blnAwal' AND '$blnAkhir'
                    --AND djp.objectjenisprodukfk IN ( 27671, 27670, 97 )
                    AND sp.objectkelompoktransaksifk = $set
                    AND sp.statusenabled = TRUE
                    $filterProdukID $filterNamaProduk

                GROUP BY
                spd.objectprodukfk,
                    pr.namaproduk,	--spd.objectasalprodukfk,	ap.asalproduk,
                    CAST ( spd.harganetto AS FLOAT8 );


        "));
        $dataReturBeli = DB::select(DB::raw("

            -- returbeli
            SELECT
                ppr.objectprodukfk,
                pr.namaproduk,	16 as objectasalprodukfk,	'BLUD' as asalproduk,--ppr.objectasalprodukfk,	ap.asalproduk,
                SUM ( ppr.qtyproduk ) AS qtyterima,
                CAST ( ppr.harganetto1 AS FLOAT8 ) AS harganetto
            FROM
                strukretur_t AS sr
                INNER JOIN strukreturdetail_t AS ppr ON sr.norec = ppr.strukreturfk
                INNER JOIN produk_m AS pr ON pr.ID = ppr.objectprodukfk
                INNER JOIN detailjenisproduk_m AS djp ON djp.ID = pr.objectdetailjenisprodukfk
               -- INNER JOIN asalproduk_m ap ON ap.ID = ppr.objectasalprodukfk
            WHERE
                sr.tglretur BETWEEN '$blnAwal' AND '$blnAkhir'
               -- AND djp.objectjenisprodukfk IN ( 27671, 27670, 97 )
                AND sr.statusenabled = TRUE
                $filterProdukID $filterNamaProduk

            GROUP BY
                ppr.objectprodukfk,
                pr.namaproduk,	--ppr.objectasalprodukfk,	ap.asalproduk,
                CAST ( ppr.harganetto1 AS FLOAT8 );


        "));

        $dataStockOp = DB::select(DB::raw("

            --STOK OPNAME
            SELECT
                spdo.objectprodukfk,
                pr.namaproduk,	16 as objectasalprodukfk,	'BLUD' as asalproduk,--ppr.objectasalprodukfk,	ap.asalproduk,
                SUM ( case when spdo.qtyproduksystem-spdo.qtyprodukreal < 0 then (spdo.qtyproduksystem-spdo.qtyprodukreal) * (-1) else 0 end ) AS qtyterima,
                CAST ( spdo.harganetto1 AS FLOAT8 ) AS harganetto

                from strukclosing_t as sc
                INNER JOIN stokprodukdetailopname_t as spdo on spdo.noclosingfk=sc.norec
                LEFT JOIN produk_m as pr on pr.id=spdo.objectprodukfk
               -- INNER JOIN detailjenisproduk_m as djp on djp.id = pr.objectdetailjenisprodukfk
            WHERE sc.kdprofile = $kdProfile
                and sc.statusenabled = true
                and sc.tglclosing BETWEEN '$blnAwal' AND '$blnAkhir'
                --AND djp.objectjenisprodukfk IN ( 27671, 27670, 97 )
                and spdo.qtyproduksystem <> spdo.qtyprodukreal
                $filterProdukID $filterNamaProduk

            GROUP BY
                spdo.objectprodukfk,
                pr.namaproduk,
                CAST ( spdo.harganetto1 AS FLOAT8 )


        "));
        $dataClosingED = [];
        if ($blnAwal == '2023-01-01 00:00:00') {
            $dataClosingED = DB::select(DB::raw("

                --closing ed
                SELECT
                    ce.produkfk as objectprodukfk,
                    pr.namaproduk,	16 as objectasalprodukfk,	'BLUD' as asalproduk,
                    SUM ( ce.stok ) AS qtyterima,
                    CAST ( ce.harga AS FLOAT8 ) AS harganetto
                from closinged_t ce
                    INNER JOIN produk_m as pr on pr.id = ce.produkfk
                    INNER JOIN detailjenisproduk_m as djp on djp.id = pr.objectdetailjenisprodukfk
                WHERE
                    tglakhir ='2022-12-31 23:59'
                    and ce.kdprofile = $kdProfile
                    --AND djp.objectjenisprodukfk IN ( 27671, 27670, 97 )
                GROUP BY
                    ce.produkfk,
                    pr.namaproduk,CAST ( ce.harga AS FLOAT8 )

            "));
        }
        $dataSaldoED = DB::select(DB::raw("

            SELECT
                    pr.namaproduk,	16 as objectasalprodukfk,	'BLUD' as asalproduk,
                    spd.objectprodukfk,
                    CAST ( spd.harganetto1 AS FLOAT8 ) AS harganetto,
                    SUM ( spd.qtyproduk ) AS qtyed
            FROM
                    stokprodukdetail_t spd
                    INNER JOIN produk_m pr ON pr.ID = spd.objectprodukfk
                   -- INNER JOIN detailjenisproduk_m AS djp ON djp.ID = pr.objectdetailjenisprodukfk
                  --  INNER JOIN asalproduk_m ap ON ap.ID = spd.objectasalprodukfk
            WHERE
                    spd.statusenabled ='t'
                    --AND djp.objectjenisprodukfk IN ( 27671, 27670, 97 )
                    and spd.qtyproduk <> 0
                    AND spd.tglkadaluarsa  between '$blnAwal' AND '$blnAkhir'

            GROUP BY
                    pr.namaproduk,
                    spd.objectprodukfk,
                    CAST ( spd.harganetto1 AS FLOAT8 );

            "));


        $result = [];
        $no = 0;
        foreach ($dataListProduk as $itm) {
            $no = $no + 1;
            $result[] = array(
                'no' => $no,
                'objectprodukfk' => $itm->objectprodukfk,
                'namaproduk' => $itm->namaproduk,
                'objectasalprodukfk' => $itm->objectasalprodukfk,
                'asalproduk' => $itm->asalproduk,
                'harganetto1' => (float)$itm->harganetto1,
                'hargasatuan' => (float)$itm->harganetto1,
                'hargaawal' => 0,
                'satuanstandar' => $itm->satuanstandar,
                'saldoawal' => 0,
                'qtyterima' => 0,
                'qtykeluar' => 0,
                'saldoakhir' => 0,
                'ttlawal' => 0,
                'ttlmasuk' => 0,
                'ttlkeluar' => 0,
                'ttlakhir' => 0,
                'returjual' => 0,
                'returbeli' => 0,
                'qtyed' => 0,
                'ttled' => 0,
                'ttlawal' => 0,
                'kodebmn' =>  $itm->kodebmn
            );
        }
        $resAwal = [];
        $ttlAwal = 0;
        foreach ($dataAwal as $itm) {
            $resAwal[] = array(
                'objectprodukfk' => $itm->objectprodukfk,
                'namaproduk' => $itm->namaproduk,
                'objectasalprodukfk' => $itm->objectasalprodukfk,
                'asalproduk' => $itm->asalproduk,
                'harganetto' => round((float)$itm->harganetto),
                'harganetto1' => $itm->harganetto,
                'saldoawal' => $itm->saldoawal,
                'ttl' => $itm->ttl,
                // 'cp' => $itm->cp
            );
            $ttlAwal = $ttlAwal + ($itm->ttl);
        }
        $resAkhir = [];
        foreach ($dataAkhir as $itm) {
            $resAkhir[] = array(
                'objectprodukfk' => $itm->objectprodukfk,
                'namaproduk' => $itm->namaproduk,
                'objectasalprodukfk' => $itm->objectasalprodukfk,
                'asalproduk' => $itm->asalproduk,
                'harganetto' => round((float)$itm->harganetto),
                'saldoakhir' => $itm->saldoakhir
            );
        }
        $resTerima = [];
        foreach ($dataTerima as $itm) {
            $resTerima[] = array(
                'objectprodukfk' => $itm->objectprodukfk,
                'namaproduk' => $itm->namaproduk,
                'objectasalprodukfk' => $itm->objectasalprodukfk,
                'asalproduk' => $itm->asalproduk,
                'harganetto' => round((float)$itm->harganetto),
                'qtyterima' => $itm->qtyterima
            );
        }
        $resRetur = [];
        foreach ($dataReturBeli as $itm) {
            $resRetur[] = array(
                'objectprodukfk' => $itm->objectprodukfk,
                'namaproduk' => $itm->namaproduk,
                'objectasalprodukfk' => $itm->objectasalprodukfk,
                'asalproduk' => $itm->asalproduk,
                'harganetto' => round((float)$itm->harganetto),
                'qtyterima' => $itm->qtyterima
            );
        }

        $resSO = [];
        foreach ($dataStockOp as $itm) {
            $resSO[] = array(
                'objectprodukfk' => $itm->objectprodukfk,
                'namaproduk' => $itm->namaproduk,
                'objectasalprodukfk' => $itm->objectasalprodukfk,
                'asalproduk' => $itm->asalproduk,
                'harganetto' => round((float)$itm->harganetto),
                'qtyterima' => $itm->qtyterima
            );
        }
        $resCLosingED = [];
        foreach ($dataClosingED as $itm) {
            $resCLosingED[] = array(
                'objectprodukfk' => $itm->objectprodukfk,
                'namaproduk' => $itm->namaproduk,
                'objectasalprodukfk' => $itm->objectasalprodukfk,
                'asalproduk' => $itm->asalproduk,
                'harganetto' => round((float)$itm->harganetto),
                'qtyterima' => $itm->qtyterima
            );
        }
        $resSaldoED = [];
        foreach ($dataSaldoED as $itm) {
            $resSaldoED[] = array(
                'objectprodukfk' => $itm->objectprodukfk,
                'namaproduk' => $itm->namaproduk,
                'objectasalprodukfk' => $itm->objectasalprodukfk,
                'asalproduk' => $itm->asalproduk,
                'harganetto' => round((float)$itm->harganetto),
                'qtyed' => $itm->qtyed
            );
        }

        for ($j = count($result) - 1; $j >= 0; $j--) {
            for ($a = count($resAwal) - 1; $a >= 0; $a--) {
                if (
                    $result[$j]['objectprodukfk'] == $resAwal[$a]['objectprodukfk'] &&
                    round($result[$j]['harganetto1']) == $resAwal[$a]['harganetto'] &&
                    $result[$j]['objectasalprodukfk'] == $resAwal[$a]['objectasalprodukfk']
                ) {
                    $result[$j]['saldoawal'] =  $result[$j]['saldoawal'] + $resAwal[$a]['saldoawal'];
                    $resAwal[$a]['saldoawal'] = 0;
                    $result[$j]['hargaawal'] =  $resAwal[$a]['harganetto1'];
                    $result[$j]['ttlawal'] =  $resAwal[$a]['ttl'];
                }
            }
            for ($a = count($resAkhir) - 1; $a >= 0; $a--) {
                if (
                    $result[$j]['objectprodukfk'] == $resAkhir[$a]['objectprodukfk'] &&
                    round($result[$j]['harganetto1']) == $resAkhir[$a]['harganetto'] &&
                    $result[$j]['objectasalprodukfk'] == $resAkhir[$a]['objectasalprodukfk']
                ) {
                    $result[$j]['saldoakhir'] =  (float)$result[$j]['saldoakhir'] + (float)$resAkhir[$a]['saldoakhir'];
                    $resAkhir[$a]['saldoakhir'] = 0;
                }
            }
            for ($a = count($resTerima) - 1; $a >= 0; $a--) {
                if (
                    $result[$j]['objectprodukfk'] == $resTerima[$a]['objectprodukfk'] &&
                    round($result[$j]['harganetto1']) == $resTerima[$a]['harganetto'] &&
                    $result[$j]['objectasalprodukfk'] == $resTerima[$a]['objectasalprodukfk']
                ) {
                    $result[$j]['qtyterima'] =  (float)$result[$j]['qtyterima'] + (float)$resTerima[$a]['qtyterima'];
                    $resTerima[$a]['qtyterima'] = 0;
                }
            }
            for ($a = count($resRetur) - 1; $a >= 0; $a--) {
                if (
                    $result[$j]['objectprodukfk'] == $resRetur[$a]['objectprodukfk'] &&
                    round($result[$j]['harganetto1']) == $resRetur[$a]['harganetto'] &&
                    $result[$j]['objectasalprodukfk'] == $resRetur[$a]['objectasalprodukfk']
                ) {
                    $result[$j]['qtyterima'] =  (float)$result[$j]['qtyterima'] + (float)$resRetur[$a]['qtyterima'];
                    $resRetur[$a]['qtyterima'] = 0;
                }
            }
            for ($a = count($resSO) - 1; $a >= 0; $a--) {
                if (
                    $result[$j]['objectprodukfk'] == $resSO[$a]['objectprodukfk'] &&
                    round($result[$j]['harganetto1']) == $resSO[$a]['harganetto'] &&
                    $result[$j]['objectasalprodukfk'] == $resSO[$a]['objectasalprodukfk']
                ) {
                    $result[$j]['qtyterima'] =  (float)$result[$j]['qtyterima'] + (float)$resSO[$a]['qtyterima'];
                    $resSO[$a]['qtyterima'] = 0;
                }
            }
            for ($a = count($resSaldoED) - 1; $a >= 0; $a--) {
                if (
                    $result[$j]['objectprodukfk'] == $resSaldoED[$a]['objectprodukfk'] &&
                    round($result[$j]['harganetto1']) == $resSaldoED[$a]['harganetto'] &&
                    $result[$j]['objectasalprodukfk'] == $resSaldoED[$a]['objectasalprodukfk']
                ) {
                    $result[$j]['qtyed'] =  (float)$result[$j]['qtyed'] + (float)$resSaldoED[$a]['qtyed'];
                    $resSaldoED[$a]['qtyed'] = 0;
                }
            }
        }
        $totalEdClosing = 0;
        for ($j = count($result) - 1; $j >= 0; $j--) {
            for ($a = count($resCLosingED) - 1; $a >= 0; $a--) {
                if (
                    $result[$j]['objectprodukfk'] == $resCLosingED[$a]['objectprodukfk'] &&
                    round($result[$j]['harganetto1']) == $resCLosingED[$a]['harganetto'] &&
                    $result[$j]['objectasalprodukfk'] == $resCLosingED[$a]['objectasalprodukfk']
                ) {
                    $result[$j]['qtyed'] =  (float)$result[$j]['qtyed'] + $resCLosingED[$a]['qtyterima'];
                    if ($result[$j]['saldoawal'] > $resCLosingED[$a]['qtyterima']) {
                        $totalEdClosing = $totalEdClosing + ((float)$resCLosingED[$a]['qtyterima'] * $result[$j]['harganetto1']);
                        $result[$j]['saldoawal'] =  (float)$result[$j]['saldoawal'] - (float)$resCLosingED[$a]['qtyterima'];
                        $resCLosingED[$a]['qtyterima'] = 0;
                    } else {
                        $totalEdClosing = $totalEdClosing + ((float)$result[$j]['saldoawal'] * $result[$j]['harganetto1']);
                        $resCLosingED[$a]['qtyterima'] = (float)$resCLosingED[$a]['qtyterima'] - (float)$result[$j]['saldoawal'];
                        $result[$j]['saldoawal'] =  0;
                    }
                }
            }
        }

        // for ($j = count($result)-1;$j>=0; $j--) {
        //     for ($a = count($resSaldoED)-1;$a>=0; $a--) {
        //         if($result[$j]['objectprodukfk'] == $resSaldoED[$a]['objectprodukfk'] &&
        //         round($result[$j]['harganetto1']) == $resSaldoED[$a]['harganetto'] &&
        //         $result[$j]['objectasalprodukfk'] == $resSaldoED[$a]['objectasalprodukfk']){
        //             // $result[$j]['qtyed'] =  (float)$result[$j]['qtyed'] + $resSaldoED[$a]['qtyterima'];
        //             if($result[$j]['saldoakhir'] > $resSaldoED[$a]['qtyed']){
        //                 $result[$j]['saldoakhir'] =  $result[$j]['saldoakhir'] - $resSaldoED[$a]['qtyed'];
        //                 $resSaldoED[$a]['qtyed'] = 0;
        //             }else{
        //                 $resSaldoED[$a]['qtyed'] = $resSaldoED[$a]['qtyed'] - $result[$j]['saldoakhir'];
        //                 $result[$j]['saldoakhir'] =  0;
        //             }
        //         }
        //     }
        // }

        $resAwal1 = [];
        for ($a = count($resAwal) - 1; $a >= 0; $a--) {
            if ($resAwal[$a]['saldoawal'] > 0) {
                $resAwal1[] = array(
                    'objectprodukfk' => $resAwal[$a]['objectprodukfk'],
                    'namaproduk' => $resAwal[$a]['namaproduk'],
                    'objectasalprodukfk' => $resAwal[$a]['objectasalprodukfk'],
                    'asalproduk' => $resAwal[$a]['asalproduk'],
                    'harganetto' => $resAwal[$a]['harganetto'],
                    'saldoawal' => $resAwal[$a]['saldoawal']
                );
            }
        };
        $resAkhir1 = [];
        for ($a = count($resAkhir) - 1; $a >= 0; $a--) {
            if ($resAkhir[$a]['saldoakhir'] > 0) {
                $resAkhir1[] = array(
                    'objectprodukfk' => $resAkhir[$a]['objectprodukfk'],
                    'namaproduk' => $resAkhir[$a]['namaproduk'],
                    'objectasalprodukfk' => $resAkhir[$a]['objectasalprodukfk'],
                    'asalproduk' => $resAkhir[$a]['asalproduk'],
                    'harganetto' => $resAkhir[$a]['harganetto'],
                    'saldoakhir' => $resAkhir[$a]['saldoakhir']
                );
            }
        };
        $resTerima1 = [];
        for ($a = count($resTerima) - 1; $a >= 0; $a--) {
            if ($resTerima[$a]['qtyterima'] > 0) {
                $resTerima1[] = array(
                    'objectprodukfk' => $resTerima[$a]['objectprodukfk'],
                    'namaproduk' => $resTerima[$a]['namaproduk'],
                    'objectasalprodukfk' => $resTerima[$a]['objectasalprodukfk'],
                    'asalproduk' => $resTerima[$a]['asalproduk'],
                    'harganetto' => $resTerima[$a]['harganetto'],
                    'qtyterima' => $resTerima[$a]['qtyterima']
                );
            }
        };
        $resRetur1 = [];
        for ($a = count($resRetur) - 1; $a >= 0; $a--) {
            if ($resRetur[$a]['qtyterima'] > 0) {
                $resRetur1[] = array(
                    'objectprodukfk' => $resRetur[$a]['objectprodukfk'],
                    'namaproduk' => $resRetur[$a]['namaproduk'],
                    'objectasalprodukfk' => $resRetur[$a]['objectasalprodukfk'],
                    'asalproduk' => $resRetur[$a]['asalproduk'],
                    'harganetto' => $resRetur[$a]['harganetto'],
                    'qtyterima' => $resRetur[$a]['qtyterima']
                );
            }
        };
        $resSO1 = [];
        for ($a = count($resSO) - 1; $a >= 0; $a--) {
            if ($resSO[$a]['qtyterima'] > 0) {
                $resSO1[] = array(
                    'objectprodukfk' => $resSO[$a]['objectprodukfk'],
                    'namaproduk' => $resSO[$a]['namaproduk'],
                    'objectasalprodukfk' => $resSO[$a]['objectasalprodukfk'],
                    'asalproduk' => $resSO[$a]['asalproduk'],
                    'harganetto' => $resSO[$a]['harganetto'],
                    'qtyterima' => $resSO[$a]['qtyterima']
                );
            }
        };
        $resCLosingED1 = [];
        for ($a = count($resCLosingED) - 1; $a >= 0; $a--) {
            if ($resCLosingED[$a]['qtyterima'] > 0) {
                $resCLosingED1[] = array(
                    'objectprodukfk' => $resCLosingED[$a]['objectprodukfk'],
                    'namaproduk' => $resCLosingED[$a]['namaproduk'],
                    'objectasalprodukfk' => $resCLosingED[$a]['objectasalprodukfk'],
                    'asalproduk' => $resCLosingED[$a]['asalproduk'],
                    'harganetto' => $resCLosingED[$a]['harganetto'],
                    'qtyterima' => $resCLosingED[$a]['qtyterima']
                );
            }
        };

        $dataGrid = [];
        for ($j = count($result) - 1; $j >= 0; $j--) {
            if (
                $result[$j]['saldoawal'] == 0 &&
                $result[$j]['qtyterima'] == 0 &&
                $result[$j]['qtykeluar'] == 0 &&
                $result[$j]['saldoakhir'] == 0
            ) {
            } else {
                $dataGrid[] = array(
                    'no' => $result[$j]['no'],
                    'objectprodukfk' => $result[$j]['objectprodukfk'],
                    'id' => $result[$j]['objectprodukfk'],
                    'namaproduk' => $result[$j]['namaproduk'],
                    'objectasalprodukfk' => $result[$j]['objectasalprodukfk'],
                    'asalproduk' => $result[$j]['asalproduk'],
                    'harganetto1' => $result[$j]['harganetto1'],
                    'hargasatuan' => $result[$j]['hargasatuan'],
                    'satuanstandar' => $result[$j]['satuanstandar'],
                    'saldoawal' => $result[$j]['saldoawal'],
                    'qtyterima' => $result[$j]['qtyterima'],
                    'qtykeluar' => $result[$j]['saldoawal'] + $result[$j]['qtyterima'] - $result[$j]['saldoakhir'],
                    'saldoakhir' => $result[$j]['saldoakhir'],
                    'totalrpsaldoawal' => $result[$j]['ttlawal'], //$cp == 1 ? $result[$j]['saldoawal'] *  $result[$j]['hargaawal']  : $result[$j]['saldoawal'] * $result[$j]['harganetto1'],
                    'totalhargaterima' => (float)$result[$j]['qtyterima'] * (float)$result[$j]['harganetto1'],
                    'totalhargakeluar' => (float)($result[$j]['saldoawal'] + $result[$j]['qtyterima'] - $result[$j]['saldoakhir']) * (float)$result[$j]['harganetto1'],
                    'totalrpsaldoakhir' => (float)$result[$j]['saldoakhir'] * (float)$result[$j]['harganetto1'],
                    'returjual' => $result[$j]['returjual'],
                    'returbeli' => $result[$j]['returbeli'],
                    'qtyed' => $result[$j]['qtyed'],
                    'totalrped' => 0,
                    'ttlawal' => $result[$j]['ttlawal'],
                    'kodebmn' => $result[$j]['kodebmn'],
                );
            }
        }
        $dataMinus = [];
        $bagi12 = 0;
        if (1 == 1) {
            for ($j = count($dataGrid) - 1; $j >= 0; $j--) {
                if ($dataGrid[$j]['qtykeluar'] < 0) {
                    $dataMinus[] = array(
                        'no' => $dataGrid[$j]['no'],
                        'objectprodukfk' => $dataGrid[$j]['objectprodukfk'],
                        'id' => $dataGrid[$j]['id'],
                        'namaproduk' => $dataGrid[$j]['namaproduk'],
                        'objectasalprodukfk' => $dataGrid[$j]['objectasalprodukfk'],
                        'asalproduk' => $dataGrid[$j]['asalproduk'],
                        'harganetto1' => $dataGrid[$j]['harganetto1'],
                        'hargasatuan' => $dataGrid[$j]['hargasatuan'],
                        'satuanstandar' => $dataGrid[$j]['satuanstandar'],
                        'saldoawal' => $dataGrid[$j]['saldoawal'],
                        'qtyterima' => $dataGrid[$j]['qtyterima'],
                        'qtykeluar' => $dataGrid[$j]['qtykeluar'] * (-1),
                        'saldoakhir' => $dataGrid[$j]['saldoakhir'],
                        'totalrpsaldoawal' => $dataGrid[$j]['totalrpsaldoawal'],
                        'totalhargaterima' => $dataGrid[$j]['totalhargaterima'],
                        'totalhargakeluar' => $dataGrid[$j]['totalhargakeluar'],
                        'totalrpsaldoakhir' => $dataGrid[$j]['totalrpsaldoakhir'],
                        'returjual' => $dataGrid[$j]['returjual'],
                        'returbeli' => $dataGrid[$j]['returbeli'],
                        'qtyed' => $dataGrid[$j]['qtyed'],
                        'totalrped' => $dataGrid[$j]['totalrped'],
                    );
                    $dataGrid[$j]['saldoakhir'] = $dataGrid[$j]['saldoakhir'] + $dataGrid[$j]['qtykeluar'];
                    $dataGrid[$j]['qtykeluar'] = 0;
                    $dataGrid[$j]['totalhargakeluar'] = 0;
                    // $dataGrid[$j]['totalrpsaldoakhir'] = $dataGrid[$j]['harganetto1'] * $dataGrid[$j]['saldoakhir'];
                }
            }
            for ($j = count($dataGrid) - 1; $j >= 0; $j--) {
                for ($k = count($dataMinus) - 1; $k >= 0; $k--) {
                    if ($dataGrid[$j]['objectprodukfk'] == $dataMinus[$k]['objectprodukfk']) {
                        if ($dataGrid[$j]['qtykeluar'] >  $dataMinus[$k]['qtykeluar']) {
                            $dataGrid[$j]['qtykeluar'] = $dataGrid[$j]['qtykeluar'] - $dataMinus[$k]['qtykeluar'];
                            $dataMinus[$k]['qtykeluar'] = 0;
                        } else {
                            $dataMinus[$k]['qtykeluar'] = $dataMinus[$k]['qtykeluar'] - $dataGrid[$j]['qtykeluar'];
                            $dataGrid[$j]['qtykeluar'] = 0;;
                        };
                    };
                }
            }

            // yg minus tambahkan ke retur jual
            for ($j = count($dataGrid) - 1; $j >= 0; $j--) {
                for ($k = count($dataMinus) - 1; $k >= 0; $k--) {
                    if ($bagi12 < 200 * 1000 * 1000) {
                        if ($dataGrid[$j]['objectprodukfk'] == $dataMinus[$k]['objectprodukfk']) {
                            if ($dataMinus[$k]['qtykeluar'] > 0) {
                                // $dataGrid[$j]['qtyterima'] = $dataGrid[$j]['qtyterima'] + $dataMinus[$k]['qtykeluar'];
                                $dataGrid[$j]['returjual'] = $dataGrid[$j]['returjual'] + $dataMinus[$k]['qtykeluar'];
                                $bagi12 = $bagi12 + ($dataMinus[$k]['qtykeluar'] * $dataMinus[$k]['harganetto1']);
                                $dataMinus[$k]['qtykeluar'] = 0;
                            }
                        }
                    } else {
                    }
                }
            }
            for ($j = count($dataGrid) - 1; $j >= 0; $j--) {
                $dataGrid[$j]['saldoakhir'] = $dataGrid[$j]['saldoawal'] + $dataGrid[$j]['qtyterima'] + $dataGrid[$j]['returjual'] - $dataGrid[$j]['qtykeluar'];
                $dataGrid[$j]['totalrpsaldoawal'] = $dataGrid[$j]['saldoawal'] * $dataGrid[$j]['harganetto1'];
                $dataGrid[$j]['totalhargaterima'] = ($dataGrid[$j]['qtyterima'] + $dataGrid[$j]['returjual']) * $dataGrid[$j]['harganetto1'];
                $dataGrid[$j]['totalhargakeluar'] = $dataGrid[$j]['qtykeluar'] * $dataGrid[$j]['harganetto1'];
                $dataGrid[$j]['totalrpsaldoakhir'] = $dataGrid[$j]['saldoakhir'] * $dataGrid[$j]['harganetto1'];
                $dataGrid[$j]['totalrped'] = $dataGrid[$j]['qtyed'] * $dataGrid[$j]['harganetto1'];
            }
        }

        //hitung yg msh minus
        $dataMinus1 = [];
        $totalminus = 0;
        for ($a = count($dataMinus) - 1; $a >= 0; $a--) {
            if ($dataMinus[$a]['qtykeluar'] > 0) {
                $dataMinus1[] = array(
                    'objectprodukfk' => $dataMinus[$a]['objectprodukfk'],
                    'namaproduk' => $dataMinus[$a]['namaproduk'],
                    'objectasalprodukfk' => $dataMinus[$a]['objectasalprodukfk'],
                    'asalproduk' => $dataMinus[$a]['asalproduk'],
                    'harganetto1' => $dataMinus[$a]['harganetto1'],
                    'qtykeluar' => $dataMinus[$a]['qtykeluar']
                );
                $totalminus = $totalminus + ($dataMinus[$a]['harganetto1'] * $dataMinus[$a]['qtykeluar']);
            }
        };

        $res['data'] = $dataGrid;
        $res['listproduk'] = $dataListProduk;
        $res['awal'] = $dataAwal;
        $res['akhir'] = $dataAkhir;
        $res['masuk'] = $dataTerima;
        $res['retur'] = $dataReturBeli;
        $res['awal1'] = $resAwal1;
        $res['akhir1'] = $resAkhir1;
        $res['masuk1'] = $resTerima1;
        $res['retur1'] = $resRetur1;
        $res['so'] = $resSO;
        $res['so1'] = $resSO1;
        $res['ed'] = $dataClosingED;
        $res['ed1'] = $resCLosingED1;
        $res['minus'] = $dataMinus;
        $res['minus1'] = $dataMinus1;
        $res['totalminus'] = $totalminus;
        $res['totalawal'] = $ttlAwal;
        $res['totaledclosing'] = $totalEdClosing;
        $res['bagi12'] = $bagi12;
        return $this->respond($res);
    }

    public function getLaporanPersediaan_v5_2 (Request $request) {
        ini_set('max_execution_time', 1000); //6 minutes

        $kdProfile =  $this->kdProfile;
        $tglAwal= $request['tglawal'];
        $tglAkhir = $request['tglakhir'];
        $tglawalsaldo = $request['tglawalsaldo'] . ':00';
        $tglakhirsaldo = $request['tglakhirsaldo'];
        $tglAyeuna = date('Y-m-d 23:59');
        $bulanlalu = $request['blnLalu'];
        $namaproduk = $request['nmproduk'];
        $blnAwal =  $request['blnawal'];
        $blnAkhir =  $request['blnakhir'];
        $blnIni =  $request['bulanini'];

        $filterDjProduk = "";
        if(isset($request['djp']) && $request['djp']!="" && $request['djp'] != "undefined"){
            $filterDjProduk = " and pr.objectdetailjenisprodukfk in (".$request['djp'].")";
        }
        $filterJenisProduk = "";
        if(isset($request['jp']) && $request['jp']!="" && $request['jp'] != "undefined"){
            $filterJenisProduk = " and djp.objectjenisprodukfk in (".$request['jp'].")";
        }

        $filterProdukID = "";
        // $filterProdukID = " and pr.ID =4043234 ";
        $filterNamaProduk = "";
        if(isset($request['nmproduk']) && $request['nmproduk']!="" && $request['nmproduk'] != "undefined"){
            $filterNamaProduk = " and  pr.namaproduk ilike '%" . $request['nmproduk'] . "%'";
        }

        $dataListProduk = DB::select(DB::raw("

            --list produk
            SELECT
                spd.objectprodukfk,	pr.namaproduk,ss.satuanstandar,	16 as objectasalprodukfk,
                -- 'BLUD' as asalproduk,
                spd.objectasalprodukfk,djp.detailjenisproduk as namadetail,
                ap.asalproduk,
                CAST ( spd.harganetto1 AS FLOAT4 ) AS harganetto1 ,pr.kodebmn,spd.tglpelayanan
            FROM produk_m pr
                INNER JOIN detailjenisproduk_m AS djp ON djp.ID = pr.objectdetailjenisprodukfk
                INNER JOIN stokprodukdetail_t spd ON spd.objectprodukfk = pr.id
                INNER JOIN asalproduk_m ap ON ap.ID = spd.objectasalprodukfk
                LEFT JOIN satuanstandar_m AS ss ON ss.ID = pr.objectsatuanstandarfk
            WHERE
                djp.objectjenisprodukfk IN ( 27671, 27670, 97 )
                AND spd.kdprofile = $kdProfile
                $filterProdukID $filterNamaProduk $filterDjProduk
            GROUP BY
                spd.objectprodukfk,	pr.namaproduk,	CAST ( spd.harganetto1 AS FLOAT4 ),ss.satuanstandar
                ,spd.objectasalprodukfk,ap.asalproduk,djp.detailjenisproduk
                ,pr.kodebmn,spd.tglpelayanan
            ORDER BY
                spd.objectprodukfk,spd.tglpelayanan;


        "));
        // $cp = 0;
        // if ($tglAkhir <= '2023-01-31 23:59'){
            $cp = 1;
            // $dataAwal = DB::select(DB::raw("

            //     --closing persediaan 2022-12-31
            //     SELECT
            //         cp.produkfk AS objectprodukfk,	pr.namaproduk ,
            //         -- 16 as objectasalprodukfk,
            //         -- 'BLUD' as asalproduk,
            //          cp.asalprodukfk as objectasalprodukfk,
            //          ap.asalproduk,
            //          ( cp.harga  ) AS harganetto,
            //          SUM ( cp.saldoakhir ) AS saldoawal,
            //          ( cp.harga  * SUM ( cp.saldoakhir )) as ttl
            //     FROM
            //         closingpersediaan_t cp
            //         INNER JOIN asalproduk_m ap ON ap.ID = cp.asalprodukfk
            //         INNER JOIN produk_m pr ON pr.ID = cp.produkfk
            //         INNER JOIN satuanstandar_m ss ON ss.ID = pr.objectsatuanstandarfk
            //         INNER JOIN detailjenisproduk_m AS djp ON djp.ID = pr.objectdetailjenisprodukfk
            //     WHERE
            //         bulanclosing='$bulanlalu'
            //         --and pr.id=26185
            //         AND cp.statusenabled = TRUE
            //         $filterProdukID $filterNamaProduk
            //         AND cp.kdprofile = $kdProfile
            //         AND djp.objectjenisprodukfk IN ( 27671, 27670, 97 )
            //     GROUP BY
            //         cp.produkfk,pr.namaproduk,
            //         cp.asalprodukfk,ap.asalproduk,
            //         ( cp.harga )


            // "));

//-------------------------------------------------------------------------------------
        $carbonBulanLalu = Carbon::createFromFormat('m.Y', $bulanlalu)->startOfMonth();
        $startOfMonth = $carbonBulanLalu->format('Y-m-01'); // 
        $endOfMonth = $carbonBulanLalu->endOfMonth()->format('Y-m-t'); 
        $dataAwal = [];
        $dataAwal = DB::select(DB::raw("
        SELECT
        cp.objectprodukfk AS objectprodukfk,
        pr.namaproduk,
        cp.objectasalprodukfk AS objectasalprodukfk,
        ap.asalproduk,
        cp.harganetto AS harganetto,
        SUM(cp.qtyproduk) AS saldoawal,
        (cp.harganetto * SUM(cp.qtyproduk)) AS ttl
    FROM
        saldoprodukdetail_t AS cp
        LEFT JOIN produk_m pr ON pr.ID = cp.objectprodukfk
        LEFT JOIN asalproduk_m ap ON ap.ID = cp.objectasalprodukfk
        LEFT JOIN satuanstandar_m ss ON ss.ID = pr.objectsatuanstandarfk
        LEFT JOIN detailjenisproduk_m AS djp ON djp.ID = pr.objectdetailjenisprodukfk
        INNER JOIN (
            SELECT
                objectprodukfk,
                MAX(tglsaldo) AS max_tglsaldo
            FROM
                saldoprodukdetail_t
            WHERE
                tglsaldo BETWEEN '$startOfMonth' AND '$endOfMonth'
            GROUP BY
                objectprodukfk
        ) AS maxsaldo ON cp.objectprodukfk = maxsaldo.objectprodukfk AND cp.tglsaldo = maxsaldo.max_tglsaldo
        WHERE
            cp.qtyproduk != 0
            AND cp.statusenabled = TRUE
            $filterProdukID $filterNamaProduk $filterDjProduk
            AND cp.kdprofile = $kdProfile
            AND djp.objectjenisprodukfk IN (27671, 27670, 97)
        GROUP BY
            cp.objectprodukfk, pr.namaproduk,cp.objectasalprodukfk,
            pr.objectsumberdanafk, ap.asalproduk,
            cp.harganetto
    "));


        // }else{
        //     $dataAwal = DB::select(DB::raw("

        //         --saldo awal
        //         SELECT
        //             spd.objectprodukfk,	pr.namaproduk,		16 as objectasalprodukfk,	'BLUD' as asalproduk,--spd.objectasalprodukfk,	ap.asalproduk,
        //             CAST ( spd.harganetto AS FLOAT8 ) AS harganetto,	SUM ( spd.qtyproduk ) AS saldoawal,
        //             0 as ttl
        //         FROM
        //             saldoprodukdetail_t spd
        //             INNER JOIN produk_m pr ON pr.ID = spd.objectprodukfk
        //             INNER JOIN detailjenisproduk_m AS djp ON djp.ID = pr.objectdetailjenisprodukfk
        //             INNER JOIN asalproduk_m ap ON ap.ID = spd.objectasalprodukfk
        //         WHERE
        //             spd.tglsaldo = '$blnAwal'
        //             and spd.statusenabled ='t'
        //             AND djp.objectjenisprodukfk IN ( 27671, 27670, 97 )
        //             $filterProdukID $filterNamaProduk
        //         GROUP BY
        //             pr.namaproduk,	spd.objectprodukfk,	--spd.objectasalprodukfk,	ap.asalproduk,
        //             CAST ( spd.harganetto AS FLOAT8 ) ;


        //     "));
        // }

        //jika masih di tengah bulan
        $dataAkhir = [];
        if($blnAkhir > date('Y-m-d H:i:s')){
            $dataAkhir = DB::select(DB::raw("

            --saldo akhir spd
            SELECT
                pr.namaproduk,
                -- 16 as objectasalprodukfk,
                -- 'BLUD' as asalproduk,
                spd.objectasalprodukfk,
                ap.asalproduk,
                spd.objectprodukfk,
                CAST ( spd.harganetto1 AS FLOAT4 ) AS harganetto,
                SUM ( spd.qtyproduk ) AS saldoakhir,
                ( CAST ( spd.harganetto1 AS FLOAT4 ) * SUM ( spd.qtyproduk  )) as ttl
            FROM
                stokprodukdetail_t spd
                INNER JOIN produk_m pr ON pr.ID = spd.objectprodukfk
                INNER JOIN detailjenisproduk_m AS djp ON djp.ID = pr.objectdetailjenisprodukfk
                INNER JOIN asalproduk_m ap ON ap.ID = spd.objectasalprodukfk
            WHERE
                spd.statusenabled ='t'
                AND djp.objectjenisprodukfk IN ( 27671, 27670, 97 )
                AND spd.tglkadaluarsa > '$blnAkhir'
                --and spd.qtyproduk > 0
                $filterProdukID $filterNamaProduk $filterDjProduk

            GROUP BY
                pr.namaproduk,
                spd.objectasalprodukfk,ap.asalproduk,
                spd.objectprodukfk,
                CAST ( spd.harganetto1 AS FLOAT4 );

        "));
        }else{
            $dataAkhir = DB::select(DB::raw("

                --saldo akhir
                SELECT
                    pr.namaproduk,
                    -- 16 as objectasalprodukfk,
                    -- 'BLUD' as asalproduk,
                    spd.objectasalprodukfk,
                    ap.asalproduk,
                    spd.objectprodukfk,
                    CAST ( spd.harganetto AS FLOAT4 ) AS harganetto,
                    SUM ( spd.qtyproduk ) AS saldoakhir,
                    ( CAST ( spd.harganetto AS FLOAT4 )* SUM ( spd.qtyproduk  )) as ttl
                FROM
                    saldoprodukdetail_t spd
                    INNER JOIN produk_m pr ON pr.ID = spd.objectprodukfk
                    INNER JOIN detailjenisproduk_m AS djp ON djp.ID = pr.objectdetailjenisprodukfk
                    INNER JOIN asalproduk_m ap ON ap.ID = spd.objectasalprodukfk
                WHERE
                    spd.tglsaldo = '$blnAkhir'
                    and spd.statusenabled ='t'
                    AND spd.tglkadaluarsa > '$blnAkhir'
                    AND djp.objectjenisprodukfk IN ( 27671, 27670, 97 )
                    $filterProdukID $filterNamaProduk $filterDjProduk

                GROUP BY
                    pr.namaproduk,
                    spd.objectasalprodukfk,ap.asalproduk,
                    spd.objectprodukfk,
                    CAST ( spd.harganetto AS FLOAT4 );

            "));
        }
        $dataTerima = DB::select(DB::raw("

                --terima suplier
                SELECT
                    spd.objectprodukfk,
                    pr.namaproduk,
                    -- 16 as objectasalprodukfk,
                    -- 'BLUD' as asalproduk,
                    spd.objectasalprodukfk,ap.asalproduk,
                    SUM ( spd.qtyproduk * spd.hasilkonversi ) AS qtyterima,
                    CAST ( spd.harganetto AS FLOAT4 ) AS harganetto
                FROM
                    strukpelayanan_t sp
                    INNER JOIN strukpelayanandetail_t spd ON spd.nostrukfk = sp.norec
                    INNER JOIN produk_m pr ON pr.ID = spd.objectprodukfk
                    INNER JOIN detailjenisproduk_m AS djp ON djp.ID = pr.objectdetailjenisprodukfk
                    INNER JOIN asalproduk_m ap ON ap.ID = spd.objectasalprodukfk
                WHERE
                    sp.tglstruk BETWEEN '$blnAwal' AND '$blnAkhir'
                    AND djp.objectjenisprodukfk IN ( 27671, 27670, 97 )
                    AND sp.objectkelompoktransaksifk = 35
                    AND sp.statusenabled = TRUE
                    $filterProdukID $filterNamaProduk $filterDjProduk

                GROUP BY
                spd.objectprodukfk,
                    pr.namaproduk,
                    spd.objectasalprodukfk,ap.asalproduk,
                    CAST ( spd.harganetto AS FLOAT4 );


        "));

        $dataReturBeli = DB::select(DB::raw("

            -- returJual
            SELECT
                ppr.produkfk as objectprodukfk,
                pr.namaproduk,
                16 as objectasalprodukfk,'BLUD' as asalproduk,
                -- ppr.objectasalprodukfk,ap.asalproduk,
                SUM ( ppr.jumlah ) AS qtyterima,
                CAST ( ppr.hargasatuan AS FLOAT4 ) AS harganetto
            FROM
                strukretur_t AS sr
                INNER JOIN pelayananpasienretur_t AS ppr ON sr.norec = ppr.strukreturfk
                INNER JOIN produk_m AS pr ON pr.ID = ppr.produkfk
                INNER JOIN detailjenisproduk_m AS djp ON djp.ID = pr.objectdetailjenisprodukfk
               -- LEFT JOIN asalproduk_m ap ON ap.ID = ppr.objectasalprodukfk
            WHERE
                sr.tglretur BETWEEN '$blnAwal' AND '$blnAkhir'
                AND djp.objectjenisprodukfk IN ( 27671, 27670, 97 )
                AND sr.statusenabled = TRUE
                $filterProdukID $filterNamaProduk $filterDjProduk

            GROUP BY
                ppr.produkfk,
                pr.namaproduk,
                -- ppr.objectasalprodukfk,ap.asalproduk,
                CAST ( ppr.hargasatuan AS FLOAT4 );


        "));

        $dataStockOp = DB::select(DB::raw("

            --STOK OPNAME
            SELECT
                spdo.objectprodukfk,
                pr.namaproduk,
                -- 16 as objectasalprodukfk,'BLUD' as asalproduk,
                spdo.objectasalprodukfk,ap.asalproduk,
                SUM ( case when spdo.qtyproduksystem-spdo.qtyprodukreal < 0 then (spdo.qtyproduksystem-spdo.qtyprodukreal) * (-1) else 0 end ) AS qtyterima,
                CAST ( spdo.harganetto1 AS FLOAT4 ) AS harganetto
                from strukclosing_t as sc
                INNER JOIN stokprodukdetailopname_t as spdo on spdo.noclosingfk=sc.norec
                LEFT JOIN produk_m as pr on pr.id=spdo.objectprodukfk
                INNER JOIN detailjenisproduk_m as djp on djp.id = pr.objectdetailjenisprodukfk
                INNER JOIN asalproduk_m ap ON ap.ID = spdo.objectasalprodukfk
            WHERE sc.kdprofile = $kdProfile
                and sc.statusenabled = true
                and sc.tglclosing BETWEEN '$blnAwal' AND '$blnAkhir'
                AND djp.objectjenisprodukfk IN ( 27671, 27670, 97 )
                and spdo.qtyproduksystem <> spdo.qtyprodukreal
                $filterProdukID $filterNamaProduk $filterDjProduk

            GROUP BY
                spdo.objectprodukfk,
                pr.namaproduk,
                spdo.objectasalprodukfk,ap.asalproduk
                ,CAST ( spdo.harganetto1 AS FLOAT4 )


        "));
        $dataClosingED = [];
        if($blnAwal == '2023-01-01 00:00:00'){
            $dataClosingED = DB::select(DB::raw("

                --closing ed
                SELECT
                    ce.produkfk as objectprodukfk,
                    pr.namaproduk,
                    16 as objectasalprodukfk,'BLUD' as asalproduk,
                    SUM ( ce.stok ) AS qtyterima,
                    CAST ( ce.harga AS FLOAT4 ) AS harganetto
                from closinged_t ce
                    INNER JOIN produk_m as pr on pr.id = ce.produkfk
                    INNER JOIN detailjenisproduk_m as djp on djp.id = pr.objectdetailjenisprodukfk
                WHERE
                    tglakhir ='2022-12-31 23:59'
                    and ce.kdprofile = $kdProfile
                    AND djp.objectjenisprodukfk IN ( 27671, 27670, 97 ) $filterNamaProduk $filterDjProduk
                GROUP BY
                    ce.produkfk,
                    pr.namaproduk,CAST ( ce.harga AS FLOAT4 )

            "));
        }

        $dataSaldoED = [];
        if($blnAkhir < date('Y-m-d H:i:s')){
            $dataSaldoED = DB::select(DB::raw("

                SELECT
                        pr.namaproduk,
                        -- 16 as objectasalprodukfk,'BLUD' as asalproduk,
                        spd.objectasalprodukfk,ap.asalproduk,
                        spd.objectprodukfk,
                        CAST ( spd.harganetto AS FLOAT4 ) AS harganetto,
                        SUM ( spd.qtyproduk ) AS qtyed
                FROM
                    saldoprodukdetail_t as spd
                    INNER JOIN produk_m as pr on pr.id = spd.objectprodukfk
                    INNER JOIN detailjenisproduk_m as djp on djp.id = pr.objectdetailjenisprodukfk
                    INNER JOIN jenisproduk_m as jp on jp.id = djp.objectjenisprodukfk
                    INNER JOIN satuanstandar_m as sa on sa.id = pr.objectsatuanstandarfk
                    INNER JOIN ruangan_m as ru on ru.id = spd.objectruanganfk
                    INNER JOIN asalproduk_m ap ON ap.ID = spd.objectasalprodukfk
                WHERE
                        spd.kdprofile = $kdProfile and spd.statusenabled ='t'
                        AND djp.objectjenisprodukfk IN ( 27671, 27670, 97 )
                        and spd.qtyproduk <> 0 and pr.statusenabled = true
                        AND spd.tglkadaluarsa  between '$tglAwal' AND '$tglAkhir'
                        and spd.tglsaldo = '$blnAkhir'
                        $filterNamaProduk $filterDjProduk

                GROUP BY
                        pr.namaproduk,
                        spd.objectprodukfk,
                        spd.objectasalprodukfk,ap.asalproduk,
                        CAST ( spd.harganetto AS FLOAT4 );
                "));
        }else{
            $dataSaldoED = DB::select(DB::raw("

                SELECT
                        pr.namaproduk,
                        -- 16 as objectasalprodukfk,'BLUD' as asalproduk,
                        spd.objectasalprodukfk,ap.asalproduk,
                        spd.objectprodukfk,
                        CAST ( spd.harganetto1 AS FLOAT4 ) AS harganetto,
                        SUM ( spd.qtyproduk ) AS qtyed
                FROM
                    stokprodukdetail_t as spd
                    INNER JOIN produk_m as pr on pr.id = spd.objectprodukfk
                    INNER JOIN detailjenisproduk_m as djp on djp.id = pr.objectdetailjenisprodukfk
                    INNER JOIN jenisproduk_m as jp on jp.id = djp.objectjenisprodukfk
                    INNER JOIN satuanstandar_m as sa on sa.id = pr.objectsatuanstandarfk
                    INNER JOIN ruangan_m as ru on ru.id = spd.objectruanganfk
                    INNER JOIN asalproduk_m ap ON ap.ID = spd.objectasalprodukfk
                WHERE
                        spd.kdprofile = $kdProfile and spd.statusenabled ='t'
                        AND djp.objectjenisprodukfk IN ( 27671, 27670, 97 )
                        and spd.qtyproduk <> 0 and pr.statusenabled = true
                        AND spd.tglkadaluarsa  between '$tglAwal' AND '$tglAkhir'
                        --and spd.tglsaldo = '$tglawalsaldo'
                        $filterNamaProduk $filterDjProduk

                GROUP BY
                        pr.namaproduk,
                        spd.objectprodukfk,
                        spd.objectasalprodukfk,ap.asalproduk,
                        CAST ( spd.harganetto1 AS FLOAT4 );

                "));
        }


        $result=[];
        $no = 0;
        $chunkSize = 1000;
        $chunks = array_chunk($dataListProduk, $chunkSize);

        foreach ($chunks as $chunk) {
            foreach ($chunk as $itm) {
        // foreach ($dataListProduk as $itm){
                $no = $no + 1;


                $result[] = array(
                    'no' => $no,
                    'objectprodukfk' => $itm->objectprodukfk,
                    'ddetail' => $itm->namadetail,
                    'namaproduk' => $itm->namaproduk,
                    'objectasalprodukfk' => $itm->objectasalprodukfk,
                    'asalproduk' => $itm->asalproduk,
                    'harganetto1' => (float)$itm->harganetto1,
                    'hargasatuan' => (float)$itm->harganetto1,
                    'hargaawal' => 0,
                    'hargaed' => 0,
                    'satuanstandar' => $itm->satuanstandar,
                    'saldoawal' => 0,
                    'qtyterima' => 0,
                    'qtykeluar' => 0,
                    'saldoakhir' => 0,
                    'ttlawal' => 0,
                    'ttlmasuk' => 0,
                    'ttlkeluar' => 0,
                    'ttlakhir' => 0,
                    'returjual' => 0,
                    'returbeli' => 0,
                    'qtyed' => 0,
                    'ttled' => 0,
                    'ttlawal' => 0,
                    'kodebmn' =>  $itm->kodebmn,
                    'tglpelayanan' =>  $itm->tglpelayanan
                );
            }
       }
        $resAwal=[];
        $ttlAwal = 0;
        foreach ($dataAwal as $itm){
            $resAwal[] = array(
                'objectprodukfk' => $itm->objectprodukfk,
                'namaproduk' => $itm->namaproduk,
                'objectasalprodukfk' => $itm->objectasalprodukfk,
                'asalproduk' => $itm->asalproduk,
                'harganetto' => ((float)$itm->harganetto),
                'harganetto1' => $itm->harganetto,
                'saldoawal' => $itm->saldoawal,
                'ttl' => $itm->ttl,
                // 'cp' => $itm->cp
            );
            $ttlAwal = $ttlAwal + ($itm->ttl);
        }
        $resAkhir=[];
        $totalsaldoakhir = 0;
        foreach ($dataAkhir as $itm){
            $resAkhir[] = array(
                'objectprodukfk' => $itm->objectprodukfk,
                'namaproduk' => $itm->namaproduk,
                'objectasalprodukfk' => $itm->objectasalprodukfk,
                'asalproduk' => $itm->asalproduk,
                'harganetto' => ((float)$itm->harganetto),
                'saldoakhir' => (float)$itm->saldoakhir,
                'ttl' => $itm->ttl
            );

            $totalsaldoakhir = $totalsaldoakhir + $itm->ttl;
        }
        $resTerima=[];
        foreach ($dataTerima as $itm){
            $resTerima[] = array(
                'objectprodukfk' => $itm->objectprodukfk,
                'namaproduk' => $itm->namaproduk,
                'objectasalprodukfk' => $itm->objectasalprodukfk,
                'asalproduk' => $itm->asalproduk,
                'harganetto' => ((float)$itm->harganetto),
                'qtyterima' => $itm->qtyterima
            );
        }
        $resRetur=[];
        foreach ($dataReturBeli as $itm){
            $resRetur[] = array(
                'objectprodukfk' => $itm->objectprodukfk,
                'namaproduk' => $itm->namaproduk,
                'objectasalprodukfk' => $itm->objectasalprodukfk,
                'asalproduk' => $itm->asalproduk,
                'harganetto' => ((float)$itm->harganetto),
                'qtyterima' => $itm->qtyterima
            );
        }

        $resSO=[];
        foreach ($dataStockOp as $itm){
            $resSO[] = array(
                'objectprodukfk' => $itm->objectprodukfk,
                'namaproduk' => $itm->namaproduk,
                'objectasalprodukfk' => $itm->objectasalprodukfk,
                'asalproduk' => $itm->asalproduk,
                'harganetto' => ((float)$itm->harganetto),
                'qtyterima' => $itm->qtyterima
            );
        }
        $resCLosingED=[];
        foreach ($dataClosingED as $itm){
            $resCLosingED[] = array(
                'objectprodukfk' => $itm->objectprodukfk,
                'namaproduk' => $itm->namaproduk,
                'objectasalprodukfk' => $itm->objectasalprodukfk,
                'asalproduk' => $itm->asalproduk,
                'harganetto' => ((float)$itm->harganetto),
                'qtyterima' => $itm->qtyterima
            );
        }
        $resSaldoED=[];
        foreach ($dataSaldoED as $itm){
            $resSaldoED[] = array(
                'objectprodukfk' => $itm->objectprodukfk,
                'namaproduk' => $itm->namaproduk,
                'objectasalprodukfk' => $itm->objectasalprodukfk,
                'asalproduk' => $itm->asalproduk,
                'harganetto' => ((float)$itm->harganetto),
                'harganetto0' => (float)$itm->harganetto,
                'qtyed' => (float)$itm->qtyed
            );
        }
        $totaled = 0;
        // $totalsaldoakhir = 0;
        for ($j = count($result)-1;$j>=0; $j--) {
            for ($a = count($resAwal)-1;$a>=0; $a--) {
                if($result[$j]['objectprodukfk'] == $resAwal[$a]['objectprodukfk'] &&
                ($result[$j]['harganetto1']) == $resAwal[$a]['harganetto'] &&
                $result[$j]['objectasalprodukfk'] == $resAwal[$a]['objectasalprodukfk']){
                    $result[$j]['saldoawal'] =  $result[$j]['saldoawal'] + $resAwal[$a]['saldoawal'];
                    $resAwal[$a]['saldoawal'] = 0;
                    $result[$j]['hargaawal'] =  $resAwal[$a]['harganetto1'];
                    $result[$j]['ttlawal'] =  $resAwal[$a]['ttl'];
                }
            }
            for ($a = count($resAkhir)-1;$a>=0; $a--) {
                if($result[$j]['objectprodukfk'] == $resAkhir[$a]['objectprodukfk'] &&
                ($result[$j]['harganetto1']) == $resAkhir[$a]['harganetto'] &&
                $result[$j]['objectasalprodukfk'] == $resAkhir[$a]['objectasalprodukfk']){
                    $result[$j]['saldoakhir'] =  (float)$result[$j]['saldoakhir'] + (float)$resAkhir[$a]['saldoakhir'];
                    // $totalsaldoakhir = $totalsaldoakhir + ($resAkhir[$a]['saldoakhir'] * $resAkhir[$a]['harganetto']);
                    $resAkhir[$a]['saldoakhir'] = 0;
                }
            }
            for ($a = count($resTerima)-1;$a>=0; $a--) {
                if($result[$j]['objectprodukfk'] == $resTerima[$a]['objectprodukfk'] &&
                ($result[$j]['harganetto1']) == $resTerima[$a]['harganetto'] &&
                $result[$j]['objectasalprodukfk'] == $resTerima[$a]['objectasalprodukfk']){
                    $result[$j]['qtyterima'] =  (float)$result[$j]['qtyterima'] + (float)$resTerima[$a]['qtyterima'];
                    $resTerima[$a]['qtyterima'] = 0;
                }
            }
            for ($a = count($resRetur)-1;$a>=0; $a--) {
                if($result[$j]['objectprodukfk'] == $resRetur[$a]['objectprodukfk'] &&
                ($result[$j]['harganetto1']) == $resRetur[$a]['harganetto'] &&
                $result[$j]['objectasalprodukfk'] == $resRetur[$a]['objectasalprodukfk']){
                    $result[$j]['returjual'] =  (float)$result[$j]['returjual'] + (float)$resRetur[$a]['qtyterima'];
                    $resRetur[$a]['qtyterima'] = 0;
                }
            }
            for ($a = count($resSO)-1;$a>=0; $a--) {
                if($result[$j]['objectprodukfk'] == $resSO[$a]['objectprodukfk'] &&
                ($result[$j]['harganetto1']) == $resSO[$a]['harganetto'] &&
                $result[$j]['objectasalprodukfk'] == $resSO[$a]['objectasalprodukfk']){
                    $result[$j]['qtyterima'] =  (float)$result[$j]['qtyterima'] + (float)$resSO[$a]['qtyterima'];
                    $resSO[$a]['qtyterima'] = 0;
                }
            }
            for ($a = count($resSaldoED)-1;$a>=0; $a--) {
                if($result[$j]['objectprodukfk'] == $resSaldoED[$a]['objectprodukfk'] &&
                ($result[$j]['harganetto1']) == $resSaldoED[$a]['harganetto0'] &&
                $result[$j]['objectasalprodukfk'] == $resSaldoED[$a]['objectasalprodukfk']){
                    $totaled = $totaled + ($resSaldoED[$a]['harganetto0'] * (float)$resSaldoED[$a]['qtyed']);

                    $result[$j]['qtyed'] =  (float)$result[$j]['qtyed'] + (float)$resSaldoED[$a]['qtyed'];
                    $result[$j]['hargaed'] = $resSaldoED[$a]['harganetto0'];
                    $resSaldoED[$a]['qtyed'] = 0;
                }
            }
        }
        for ($j = count($result)-1;$j>=0; $j--) {
            for ($a = count($resAwal)-1;$a>=0; $a--) {
                if($result[$j]['objectprodukfk'] == $resAwal[$a]['objectprodukfk'] &&
                 0 < $resAwal[$a]['saldoawal'] ){
                    $result[] = array(
                        'no' => 0,
                        'objectprodukfk' => $result[$j]['objectprodukfk'],
                        'namaproduk' => $result[$j]['namaproduk'],
                        'objectasalprodukfk' => $resAwal[$a]['objectasalprodukfk'],
                        'asalproduk' => $resAwal[$a]['asalproduk'],
                        'harganetto1' => (float)$resAwal[$a]['harganetto'],
                        'hargasatuan' => (float)$resAwal[$a]['harganetto'],
                        'hargaawal' => 0,
                        'hargaed' => 0,
                        'satuanstandar' => $result[$j]['satuanstandar'],
                        'saldoawal' => $resAwal[$a]['saldoawal'],
                        'qtyterima' => 0,
                        'qtykeluar' => 0,
                        'saldoakhir' => 0,
                        'ttlawal' => 0,
                        'ttlmasuk' => 0,
                        'ttlkeluar' => 0,
                        'ttlakhir' => 0,
                        'returjual' => 0,
                        'returbeli' => 0,
                        'qtyed' => 0,
                        'ttled' => 0,
                        'ttlawal' => 0,
                        'kodebmn' =>  $result[$j]['kodebmn'],
                        'tglpelayanan' =>  $result[$j]['tglpelayanan']
                    );
                    $resAwal[$a]['saldoawal'] = 0;
                }
            }
        }

        for ($j = count($result)-1;$j>=0; $j--) {
            for ($a = count($resRetur)-1;$a>=0; $a--) {
                if($result[$j]['objectprodukfk'] == $resRetur[$a]['objectprodukfk'] &&
                $resRetur[$a]['qtyterima'] > 0
                //  &&
                // $result[$j]['objectasalprodukfk'] == $resRetur[$a]['objectasalprodukfk']
                ){
                    $result[] = array(
                        'no' => 0,
                        'objectprodukfk' => $result[$j]['objectprodukfk'],
                        'namaproduk' => $result[$j]['namaproduk'],
                        'objectasalprodukfk' => $resRetur[$a]['objectasalprodukfk'],
                        'asalproduk' => $resRetur[$a]['asalproduk'],
                        'harganetto1' => (float)$resRetur[$a]['harganetto'],
                        'hargasatuan' => (float)$resRetur[$a]['harganetto'],
                        'hargaawal' => 0,
                        'hargaed' => 0,
                        'satuanstandar' => $result[$j]['satuanstandar'],
                        'saldoawal' => 0,
                        'qtyterima' => 0,
                        'qtykeluar' => 0,
                        'saldoakhir' => 0,
                        'ttlawal' => 0,
                        'ttlmasuk' => 0,
                        'ttlkeluar' => 0,
                        'ttlakhir' => 0,
                        'returjual' => $resRetur[$a]['qtyterima'],
                        'returbeli' => 0,
                        'qtyed' => 0,
                        'ttled' => 0,
                        'ttlawal' => 0,
                        'kodebmn' =>  $result[$j]['kodebmn'],
                        'tglpelayanan' =>  $result[$j]['tglpelayanan']
                    );
                    $resRetur[$a]['qtyterima'] = 0;
                }
            }
        }
        for ($j = count($result)-1;$j>=0; $j--) {
            for ($a = count($resAkhir)-1;$a>=0; $a--) {
                if($result[$j]['objectprodukfk'] == $resAkhir[$a]['objectprodukfk'] &&
                $resAkhir[$a]['saldoakhir'] > 0 //&&
                // $result[$j]['objectasalprodukfk'] == $resAkhir[$a]['objectasalprodukfk']
                ){
                    $result[] = array(
                        'no' => 0,
                        'objectprodukfk' => $result[$j]['objectprodukfk'],
                        'namaproduk' => $result[$j]['namaproduk'],
                        'objectasalprodukfk' => $resAkhir[$a]['objectasalprodukfk'],
                        'asalproduk' => $resAkhir[$a]['asalproduk'],
                        'harganetto1' => (float)$resAkhir[$a]['harganetto'],
                        'hargasatuan' => (float)$resAkhir[$a]['harganetto'],
                        'hargaawal' => 0,
                        'hargaed' => 0,
                        'satuanstandar' => $result[$j]['satuanstandar'],
                        'saldoawal' => 0,
                        'qtyterima' => 0,
                        'qtykeluar' => 0,
                        'saldoakhir' => $resAkhir[$a]['saldoakhir'],
                        'ttlawal' => 0,
                        'ttlmasuk' => 0,
                        'ttlkeluar' => 0,
                        'ttlakhir' => 0,
                        'returjual' => 0,
                        'returbeli' => 0,
                        'qtyed' => 0,
                        'ttled' => 0,
                        'ttlawal' => 0,
                        'kodebmn' =>  $result[$j]['kodebmn'],
                        'tglpelayanan' =>  $result[$j]['tglpelayanan']
                    );
                    $resAkhir[$a]['saldoakhir'] = 0;
                }
            }
        }


        // for ($j = count($result)-1;$j>=0; $j--) {
        //     for ($a = count($resSaldoED)-1;$a>=0; $a--) {
        //         if($result[$j]['objectprodukfk'] == $resSaldoED[$a]['objectprodukfk'] &&
        //         round($result[$j]['harganetto1']) == $resSaldoED[$a]['harganetto'] &&
        //         $result[$j]['objectasalprodukfk'] == $resSaldoED[$a]['objectasalprodukfk']){
        //             // $result[$j]['qtyed'] =  (float)$result[$j]['qtyed'] + $resSaldoED[$a]['qtyterima'];
        //             if($result[$j]['saldoakhir'] > $resSaldoED[$a]['qtyed']){
        //                 $result[$j]['saldoakhir'] =  $result[$j]['saldoakhir'] - $resSaldoED[$a]['qtyed'];
        //                 $resSaldoED[$a]['qtyed'] = 0;
        //             }else{
        //                 $resSaldoED[$a]['qtyed'] = $resSaldoED[$a]['qtyed'] - $result[$j]['saldoakhir'];
        //                 $result[$j]['saldoakhir'] =  0;
        //             }
        //         }
        //     }
        // }

        $totalEdClosing = 0;
        for ($j = count($result)-1;$j>=0; $j--) {
            for ($a = count($resCLosingED)-1;$a>=0; $a--) {
                // dd($result);
                if($result[$j]['objectprodukfk'] == $resCLosingED[$a]['objectprodukfk'] &&
                round($result[$j]['harganetto1'],2) == round($resCLosingED[$a]['harganetto'],2) &&
                // $result[$j]['objectasalprodukfk'] == $resCLosingED[$a]['objectasalprodukfk']
                $result[$j]['saldoawal'] > 0){
                    // $result[$j]['qtyed'] =  (float)$result[$j]['qtyed'] + $resCLosingED[$a]['qtyterima'];
                    if($result[$j]['saldoawal'] >= (float)$resCLosingED[$a]['qtyterima']){
                        $totalEdClosing = $totalEdClosing +( (float)$resCLosingED[$a]['qtyterima'] *$result[$j]['harganetto1']);
                        $result[$j]['saldoawal'] =  (float)$result[$j]['saldoawal'] - (float)$resCLosingED[$a]['qtyterima'];
                        $resCLosingED[$a]['qtyterima'] = 0;
                    }else{
                        $totalEdClosing = $totalEdClosing +( (float)$result[$j]['saldoawal'] *$result[$j]['harganetto1']);
                        $resCLosingED[$a]['qtyterima'] = (float)$resCLosingED[$a]['qtyterima'] - (float)$result[$j]['saldoawal'];
                        $result[$j]['saldoawal'] =  0;
                    }
                }
            }
        }

        for ($j = count($result)-1;$j>=0; $j--) {
            for ($a = count($resCLosingED)-1;$a>=0; $a--) {
                if($result[$j]['objectprodukfk'] == $resCLosingED[$a]['objectprodukfk'] && $resCLosingED[$a]['qtyterima'] > 0){
                    $result[] = array(
                        'no' => 0,
                        'objectprodukfk' => $result[$j]['objectprodukfk'],
                        'namaproduk' => $result[$j]['namaproduk'],
                        'objectasalprodukfk' => $resCLosingED[$a]['objectasalprodukfk'],
                        'asalproduk' => $resCLosingED[$a]['asalproduk'],
                        'harganetto1' => (float)$resCLosingED[$a]['harganetto'],
                        'hargasatuan' => (float)$resCLosingED[$a]['harganetto'],
                        'hargaawal' => 0,
                        'hargaed' => 0,
                        'satuanstandar' => $result[$j]['satuanstandar'],
                        'saldoawal' => 0,
                        'qtyterima' => $resCLosingED[$a]['qtyterima'],
                        'qtykeluar' => 0,
                        'saldoakhir' => 0,
                        'ttlawal' => 0,
                        'ttlmasuk' => 0,
                        'ttlkeluar' => 0,
                        'ttlakhir' => 0,
                        'returjual' => 0,
                        'returbeli' => 0,
                        'qtyed' => 0,
                        'ttled' => 0,
                        'ttlawal' => 0,
                        'kodebmn' =>  $result[$j]['kodebmn'],
                        'tglpelayanan' =>  $result[$j]['tglpelayanan']
                    );
                    $resCLosingED[$a]['qtyterima'] = 0;
                }
            }
        }

        $resAwal1 = [];
        for ($a = count($resAwal)-1;$a>=0; $a--) {
            if($resAwal[$a]['saldoawal'] > 0){
                $resAwal1[] = array(
                    'objectprodukfk' => $resAwal[$a]['objectprodukfk'],
                    'namaproduk' => $resAwal[$a]['namaproduk'],
                    'objectasalprodukfk' => $resAwal[$a]['objectasalprodukfk'],
                    'asalproduk' => $resAwal[$a]['asalproduk'],
                    'harganetto' => $resAwal[$a]['harganetto'],
                    'saldoawal' => $resAwal[$a]['saldoawal']
                );
            }
        };
        $resAkhir1 = [];
        for ($a = count($resAkhir)-1;$a>=0; $a--) {
            if($resAkhir[$a]['saldoakhir'] > 0){
                $resAkhir1[] = array(
                    'objectprodukfk' => $resAkhir[$a]['objectprodukfk'],
                    'namaproduk' => $resAkhir[$a]['namaproduk'],
                    'objectasalprodukfk' => $resAkhir[$a]['objectasalprodukfk'],
                    'asalproduk' => $resAkhir[$a]['asalproduk'],
                    'harganetto' => $resAkhir[$a]['harganetto'],
                    'saldoakhir' => $resAkhir[$a]['saldoakhir']
                );
            }
        };
        $resTerima1 = [];
        for ($a = count($resTerima)-1;$a>=0; $a--) {
            if($resTerima[$a]['qtyterima'] > 0){
                $resTerima1[] = array(
                    'objectprodukfk' => $resTerima[$a]['objectprodukfk'],
                    'namaproduk' => $resTerima[$a]['namaproduk'],
                    'objectasalprodukfk' => $resTerima[$a]['objectasalprodukfk'],
                    'asalproduk' => $resTerima[$a]['asalproduk'],
                    'harganetto' => $resTerima[$a]['harganetto'],
                    'qtyterima' => $resTerima[$a]['qtyterima']
                );
            }
        };
        $resRetur1 = [];
        for ($a = count($resRetur)-1;$a>=0; $a--) {
            if($resRetur[$a]['qtyterima'] > 0){
                $resRetur1[] = array(
                    'objectprodukfk' => $resRetur[$a]['objectprodukfk'],
                    'namaproduk' => $resRetur[$a]['namaproduk'],
                    'objectasalprodukfk' => $resRetur[$a]['objectasalprodukfk'],
                    'asalproduk' => $resRetur[$a]['asalproduk'],
                    'harganetto' => $resRetur[$a]['harganetto'],
                    'qtyterima' => $resRetur[$a]['qtyterima']
                );
            }
        };
        $resSO1 = [];
        for ($a = count($resSO)-1;$a>=0; $a--) {
            if($resSO[$a]['qtyterima'] > 0){
                $resSO1[] = array(
                    'objectprodukfk' => $resSO[$a]['objectprodukfk'],
                    'namaproduk' => $resSO[$a]['namaproduk'],
                    'objectasalprodukfk' => $resSO[$a]['objectasalprodukfk'],
                    'asalproduk' => $resSO[$a]['asalproduk'],
                    'harganetto' => $resSO[$a]['harganetto'],
                    'qtyterima' => $resSO[$a]['qtyterima']
                );
            }
        };
        $resCLosingED1 = [];
        for ($a = count($resCLosingED)-1;$a>=0; $a--) {
            if($resCLosingED[$a]['qtyterima'] > 0){
                $resCLosingED1[] = array(
                    'objectprodukfk' => $resCLosingED[$a]['objectprodukfk'],
                    'namaproduk' => $resCLosingED[$a]['namaproduk'],
                    'objectasalprodukfk' => $resCLosingED[$a]['objectasalprodukfk'],
                    'asalproduk' => $resCLosingED[$a]['asalproduk'],
                    'harganetto' => $resCLosingED[$a]['harganetto'],
                    'qtyterima' => $resCLosingED[$a]['qtyterima']
                );
            }
        };
        $resSaldoED1 = [];
        for ($a = count($resSaldoED)-1;$a>=0; $a--) {
            if($resSaldoED[$a]['qtyed'] > 0){
                $resSaldoED1[] = array(
                    'objectprodukfk' => $resSaldoED[$a]['objectprodukfk'],
                    'namaproduk' => $resSaldoED[$a]['namaproduk'],
                    'objectasalprodukfk' => $resSaldoED[$a]['objectasalprodukfk'],
                    'asalproduk' => $resSaldoED[$a]['asalproduk'],
                    'harganetto' => $resSaldoED[$a]['harganetto'],
                    'harganetto0' => $resSaldoED[$a]['harganetto0'],
                    'qtyed' => $resSaldoED[$a]['qtyed']
                );
            }
        };

        // dd($result);
        $dataGrid=[];
        for ($j = count($result)-1;$j>=0; $j--) {
            if ($result[$j]['saldoawal'] == 0 &&
            $result[$j]['qtyterima'] == 0 &&
            $result[$j]['returjual'] == 0 &&
            $result[$j]['qtykeluar'] == 0 &&
            $result[$j]['saldoakhir'] == 0 &&
            $result[$j]['qtyed'] == 0){

            }else{
                $dataGrid[] = array(
                    'no' => $result[$j]['no'],
                    'objectprodukfk' => $result[$j]['objectprodukfk'],
                    'id' => $result[$j]['objectprodukfk'],
                    'namaproduk' => $result[$j]['namaproduk'],
                    'objectasalprodukfk' => $result[$j]['objectasalprodukfk'],
                    'asalproduk' => $result[$j]['asalproduk'],
                    'harganetto1' => (float)$result[$j]['harganetto1'],
                    'hargasatuan' => $result[$j]['hargasatuan'],
                    'hargaawal' => $result[$j]['hargaawal'],
                    'hargaed' => $result[$j]['hargaed'],
                    'satuanstandar' => $result[$j]['satuanstandar'],
                    'saldoawal' => $result[$j]['saldoawal'],
                    'qtyterima' => $result[$j]['qtyterima'],
                    'qtykeluar' => $result[$j]['saldoawal'] + $result[$j]['qtyterima'] + $result[$j]['returjual'] - $result[$j]['saldoakhir'],
                    'saldoakhir' => $result[$j]['saldoakhir'],
                    'totalrpsaldoawal' => $result[$j]['ttlawal'],//$cp == 1 ? $result[$j]['saldoawal'] *  $result[$j]['hargaawal']  : $result[$j]['saldoawal'] * $result[$j]['harganetto1'],
                    'totalhargaterima' => (float)$result[$j]['qtyterima'] * (float)$result[$j]['harganetto1'],
                    'totalhargakeluar' => (float)($result[$j]['saldoawal'] + $result[$j]['qtyterima'] + $result[$j]['returjual'] - $result[$j]['saldoakhir']) * (float)$result[$j]['harganetto1'],
                    'totalrpsaldoakhir' => (float)$result[$j]['saldoakhir'] * (float)$result[$j]['harganetto1'],
                    'returjual' => $result[$j]['returjual'],
                    'returbeli' => $result[$j]['returbeli'],
                    'qtyed' => $result[$j]['qtyed'],
                    'totalrped' => 0,
                    'ttlawal' => $result[$j]['ttlawal'],
                    'kodebmn' => $result[$j]['kodebmn'],
                    'tglpelayanan' => $result[$j]['tglpelayanan'],
                );
            }
        }
        // dd($dataGrid);
        $dataMinus = [];
        $bagi12 = 0;
        $ttlAwalhitunglagi = 0;
        $hitungbeda = 0;
        $totaledakhir =0 ;
        $sama=false;
        if(1 == 1){
            for ($j = count($dataGrid)-1;$j>=0; $j--) {
                if ($dataGrid[$j]['qtykeluar'] < 0 ){
                    $sama = false;
                    for ($k = count($dataMinus)-1;$k>=0; $k--) {
                        if($dataGrid[$j]['objectprodukfk'] == $dataMinus[$k]['objectprodukfk']){
                            $dataMinus[$k]['qtykeluar'] = $dataMinus[$k]['qtykeluar'] + $dataGrid[$j]['qtykeluar']* (-1) ;
                            $dataGrid[$j]['qtykeluar'] = 0;
                            $sama = true;
                            break;
                        }
                    }
                    if($sama == false){
                        $dataMinus [] = array(
                            'no' => $dataGrid[$j]['no'],
                            'objectprodukfk' => $dataGrid[$j]['objectprodukfk'],
                            'id' => $dataGrid[$j]['id'],
                            'namaproduk' => $dataGrid[$j]['namaproduk'],
                            'objectasalprodukfk' => $dataGrid[$j]['objectasalprodukfk'],
                            'asalproduk' => $dataGrid[$j]['asalproduk'],
                            'harganetto1' => $dataGrid[$j]['harganetto1'],
                            'hargasatuan' => $dataGrid[$j]['hargasatuan'],
                            'satuanstandar' => $dataGrid[$j]['satuanstandar'],
                            'saldoawal' => $dataGrid[$j]['saldoawal'],
                            'qtyterima' => $dataGrid[$j]['qtyterima'],
                            'qtykeluar' => $dataGrid[$j]['qtykeluar']* (-1) ,
                            'saldoakhir' => $dataGrid[$j]['saldoakhir'],
                            'totalrpsaldoawal' => $dataGrid[$j]['totalrpsaldoawal'],
                            'totalhargaterima' => $dataGrid[$j]['totalhargaterima'],
                            'totalhargakeluar' => $dataGrid[$j]['totalhargakeluar'],
                            'totalrpsaldoakhir' => $dataGrid[$j]['totalrpsaldoakhir'],
                            'returjual' => $dataGrid[$j]['returjual'],
                            'returbeli' => $dataGrid[$j]['returbeli'],
                            'qtyed' => $dataGrid[$j]['qtyed'],
                            'totalrped' => $dataGrid[$j]['totalrped'],
                        );
                        $dataGrid[$j]['qtykeluar'] = 0;
                    }
                }
            }
            //sebarkan nilai minus ke nilai yg plus di harga yg berbeda
            //** versi 5_1 */
                // for ($j = 0;count($dataGrid) > $j; $j++) {
                //     for ($k = count($dataMinus)-1;$k>=0; $k--) {
                //         if($dataMinus[$k]['qtykeluar'] > 0){
                //             if($dataGrid[$j]['objectprodukfk'] == $dataMinus[$k]['objectprodukfk'] &&  $dataGrid[$j]['saldoawal'] > 0){
                //                 if( $dataMinus[$k]['qtykeluar'] > $dataGrid[$j]['saldoawal']){
                //                     $dataGrid[$j]['qtykeluar'] = 0;
                //                     $dataMinus[$k]['qtykeluar'] = $dataMinus[$k]['qtykeluar'] - $dataGrid[$j]['saldoawal'];
                //                 }else{
                //                     if($dataGrid[$j]['qtykeluar'] < $dataMinus[$k]['qtykeluar']){
                //                         $dataMinus[$k]['qtykeluar'] = $dataMinus[$k]['qtykeluar'] - $dataGrid[$j]['qtykeluar'];
                //                         $dataGrid[$j]['qtykeluar'] = 0;
                //                     }else{
                //                         $dataGrid[$j]['qtykeluar'] = $dataGrid[$j]['qtykeluar'] - $dataMinus[$k]['qtykeluar'];
                //                         $dataMinus[$k]['qtykeluar'] = 0;
                //                     }

                //                 }
                //             }
                //         }
                //     }
                // }
            //** end versi 5_1 */

            //** versi 5  */

                //sebarkan nilai minus ke nilai yg plus di harga yg berbeda
                for ($j = count($dataGrid)-1;$j>=0; $j--) {
                    for ($k = count($dataMinus)-1;$k>=0; $k--) {
                        if($dataGrid[$j]['objectprodukfk'] == $dataMinus[$k]['objectprodukfk']){
                            if($dataGrid[$j]['qtykeluar'] >  $dataMinus[$k]['qtykeluar']){
                                $dataGrid[$j]['qtykeluar'] = $dataGrid[$j]['qtykeluar'] - $dataMinus[$k]['qtykeluar'];
                                // $dataGrid[$j]['harganetto1'] = $dataMinus[$k]['harganetto1'];
                                $dataMinus[$k]['qtykeluar'] = 0;
                            }else{
                                $dataMinus[$k]['qtykeluar'] = $dataMinus[$k]['qtykeluar'] - $dataGrid[$j]['qtykeluar'];
                                $dataGrid[$j]['qtykeluar'] = 0;
                            };
                        };
                    }
                }

            //** end versi 5 */

            // yg minus tambahkan ke retur jual
            $jmlloop = 0;
            for ($j = count($dataGrid)-1;$j>=0; $j--) {
                for ($k = count($dataMinus)-1;$k>=0; $k--) {
                    if($dataGrid[$j]['objectprodukfk'] == $dataMinus[$k]['objectprodukfk']){
                        $jmlloop = $dataMinus[$k]['qtykeluar'];
                        for ($l = $jmlloop-1;$l>=0; $l--) {
                            if($bagi12 < 642000000){
                                if($dataMinus[$k]['qtykeluar'] > 0){
                                    // $dataGrid[$j]['returjual'] = $dataGrid[$j]['returjual'] + $dataMinus[$k]['qtykeluar'];
                                    // $bagi12 = $bagi12 + ($dataMinus[$k]['qtykeluar']* $dataMinus[$k]['harganetto1']);
                                    // $dataMinus[$k]['qtykeluar'] = 0;
                                    $dataGrid[$j]['returjual'] = $dataGrid[$j]['returjual'] + 1;
                                    $bagi12 = $bagi12 + (1 * $dataMinus[$k]['harganetto1']);
                                    $dataMinus[$k]['qtykeluar'] = $dataMinus[$k]['qtykeluar'] -1;
                                }
                            }else{
                                break;
                            }
                        }
                    }

                }
            }
             // qtyed minuskan ke qtykeluar
            for ($j = count($dataGrid)-1;$j>=0; $j--) {
                $dataGrid[$j]['qtykeluar'] = $dataGrid[$j]['qtykeluar'] - $dataGrid[$j]['qtyed'];
            }
            // dd($dataGrid);
             // qtyed minustmbah ke retur jual
             for ($j = count($dataGrid)-1;$j>=0; $j--) {
                if($dataGrid[$j]['qtykeluar'] < 0){
                    $dataGrid[$j]['returjual'] = $dataGrid[$j]['returjual']  + ($dataGrid[$j]['qtykeluar'] * (-1));
                    $dataGrid[$j]['qtykeluar'] = 0;
                }
            }

            $ttlAwalhitunglagi = 0;
            $hitungbeda = [] ;
            $totaledakhir = 0;
            for ($j = count($dataGrid)-1;$j>=0; $j--) {
                $dataGrid[$j]['totalrpsaldoawal'] = $dataGrid[$j]['saldoawal'] * $dataGrid[$j]['harganetto1'];
                $ttlAwalhitunglagi =$ttlAwalhitunglagi + ($dataGrid[$j]['saldoawal'] * $dataGrid[$j]['harganetto1']);
                // $dataGrid[$j]['qtykeluar'] = $dataGrid[$j]['saldoawal'] + $dataGrid[$j]['qtyterima'] + $dataGrid[$j]['returjual'] + $dataGrid[$j]['qtyed'] - $dataGrid[$j]['saldoakhir'];
                // $dataGrid[$j]['totalrpsaldoawal'] = $dataGrid[$j]['saldoawal'] * $dataGrid[$j]['harganetto1'];
                $dataGrid[$j]['totalhargaterima'] = ($dataGrid[$j]['qtyterima'] + $dataGrid[$j]['returjual']) * $dataGrid[$j]['harganetto1'];
                $dataGrid[$j]['totalhargakeluar'] = $dataGrid[$j]['qtykeluar'] * $dataGrid[$j]['harganetto1'];

                $dataGrid[$j]['saldoakhir'] = $dataGrid[$j]['saldoawal'] + $dataGrid[$j]['qtyterima'] + $dataGrid[$j]['returjual'] - $dataGrid[$j]['qtykeluar'] - $dataGrid[$j]['qtyed'];
                $dataGrid[$j]['totalrpsaldoakhir'] = $dataGrid[$j]['saldoakhir'] * $dataGrid[$j]['harganetto1'];
                // $dataGrid[$j]['totalrpsaldoakhir'] = ($dataGrid[$j]['saldoawal'] * $dataGrid[$j]['harganetto1']) + (($dataGrid[$j]['qtyterima'] + $dataGrid[$j]['returjual']) * $dataGrid[$j]['harganetto1'])
                //                                 - ($dataGrid[$j]['qtykeluar'] * $dataGrid[$j]['harganetto1']) - ($dataGrid[$j]['qtyed'] * $dataGrid[$j]['hargaed']);
                // $dataGrid[$j]['totalrped'] = $dataGrid[$j]['qtyed'] * $dataGrid[$j]['hargaed'];
                $dataGrid[$j]['totalrped'] = $dataGrid[$j]['qtyed'] * $dataGrid[$j]['harganetto1'];
                $totaledakhir = $totaledakhir + ($dataGrid[$j]['qtyed'] * $dataGrid[$j]['harganetto1']);
            }
        }

        //hitung yg msh minus
        $dataMinus1 = [];
        $totalminus = 0;
        for ($a = count($dataMinus)-1;$a>=0; $a--) {
            if($dataMinus[$a]['qtykeluar'] > 0){
                $dataMinus1[] = array(
                    'objectprodukfk' => $dataMinus[$a]['objectprodukfk'],
                    'namaproduk' => $dataMinus[$a]['namaproduk'],
                    'objectasalprodukfk' => $dataMinus[$a]['objectasalprodukfk'],
                    'asalproduk' => $dataMinus[$a]['asalproduk'],
                    'harganetto1' => $dataMinus[$a]['harganetto1'],
                    'qtykeluar' => $dataMinus[$a]['qtykeluar']
                );
                $totalminus = $totalminus + ($dataMinus[$a]['harganetto1'] * $dataMinus[$a]['qtykeluar']);
            }
        };
        if(count($dataGrid )!= 0){
            // foreach ($dataGrid as $key => $row) {
            //     $count[$key] = $row['tglpelayanan'];
            // }

            // array_multisort($count, SORT_ASC, $dataGrid);
            $sort = array();
            foreach($dataGrid as $k=>$v) {
                $sort['namaproduk'][$k] = $v['namaproduk'];
                $sort['tglpelayanan'][$k] = $v['tglpelayanan'];
            }
            # sort by event_type desc and then title asc
            array_multisort($sort['namaproduk'], SORT_DESC, $sort['tglpelayanan'], SORT_ASC,$dataGrid);
        }
        // $dataGrid = collect($dataGrid)->sortBy('tglpelayanan')->reverse()->toArray();

         $res['data'] = $dataGrid;
         $res['listproduk'] = $dataListProduk;
         $res['awal'] = $dataAwal;
         $res['akhir'] = $dataAkhir;
         $res['masuk'] = $dataTerima;
         $res['retur'] = $dataReturBeli;
         $res['awal1'] = $resAwal1;
         $res['akhir1'] = $resAkhir1;
         $res['masuk1'] = $resTerima1;
         $res['tgl1'] = $carbonBulanLalu;
         $res['tgl2'] = $startOfMonth;
         $res['tgl3'] = $endOfMonth;
         $res['tgl4'] = $bulanlalu;
         $res['retur1'] = $resRetur1;
         $res['so'] = $resSO;
         $res['so1'] = $resSO1;
         $res['ed'] = $dataClosingED;
         $res['ed1'] = $resCLosingED1;
         $res['edsaldo'] = $dataSaldoED;
         $res['edsaldo1'] = $resSaldoED1;
         $res['minus'] = $dataMinus;
         $res['minus1'] = $dataMinus1;
         $res['totalminus'] = $totalminus;
         $res['totalawal'] = $ttlAwal;
         $res['totalawalhitunglg'] = $ttlAwalhitunglagi;
         $res['totaledclosing'] = $totalEdClosing;
         $res['totalsaminedclosing'] = $ttlAwal - $totalEdClosing;
         $res['bagi12'] = $bagi12;
         $res['hitungbeda'] = $hitungbeda;
         $res['totaled'] = $totaled;
         $res['totaledakhir'] = $totaledakhir;
         $res['totalsaldoakhir'] = $totalsaldoakhir;
         return $this->respond($res);
     }

    public function getLaporanPersediaan_new(Request $request)
    {
        $bulanlalu = $request['blnLalu'];
        $carbonBulanLalu = Carbon::createFromFormat('m.Y', $bulanlalu)->startOfMonth();
        $startOfMonth = $carbonBulanLalu->format('Y-m-01');
        $endOfMonth = $carbonBulanLalu->endOfMonth()->format('Y-m-t');
        $tglakhir = $request['tglakhirsaldo'];

        $filterDjProduk = "";
        if (isset($request['djp']) && $request['djp'] != "" && $request['djp'] != "undefined") {
            $filterDjProduk = " and pr.objectdetailjenisprodukfk in (" . $request['djp'] . ")";
        }
        $filterJenisProduk = "";
        if (isset($request['jp']) && $request['jp'] != "" && $request['jp'] != "undefined") {
            $filterJenisProduk = " and djp.objectjenisprodukfk in (" . $request['jp'] . ")";
        }
        $filterNamaProduk = "";
        if (isset($request['nmproduk']) && $request['nmproduk'] != "" && $request['nmproduk'] != "undefined") {
            $filterNamaProduk = " and  pr.namaproduk ilike '%" . $request['nmproduk'] . "%'";
        }


        $data = DB::select(DB::raw("SELECT 
            idproduk,
            kodeproduk,
            namaproduk,
            satuanstandar,
            asalproduk,
            detailjenisproduk,
            hargasatuan,
            SUM(saldoawal) AS saldoawal,
            ( SUM(saldoawal) * hargasatuan ) AS totalrpsaldoawal,
            SUM(qtypenerimaan) AS qtyterima,
            ( SUM(qtypenerimaan) * hargasatuan ) AS totalhargaterima,
            SUM(qtypengeluaran) AS qtykeluar,
            ( SUM(qtypengeluaran) * hargasatuan ) AS totalhargakeluar,
            SUM(qtyexpired) AS qtyed,
            ( SUM(qtyexpired) * hargasatuan ) AS totalrped
            FROM (
                -- Saldo Awal
                SELECT
                    pr.ID AS idproduk,
                    pr.kdproduk AS kodeproduk,
                    pr.namaproduk AS namaproduk,
                    ss.satuanstandar,
                    ap.asalproduk,
                    djp.detailjenisproduk,
                    spd.harganetto AS hargasatuan,
                    SUM(qtyproduk) AS saldoawal,
                    0 AS qtypenerimaan,
                    0 AS qtypengeluaran,
                    0 AS qtyexpired
                FROM saldoprodukdetail_t AS spd
                    JOIN produk_m AS pr ON pr.ID = spd.objectprodukfk
                    JOIN detailjenisproduk_m AS djp ON djp.ID = pr.objectdetailjenisprodukfk
                    JOIN satuanstandar_m AS ss ON ss.ID = pr.objectsatuanstandarfk
                    JOIN asalproduk_m AS ap ON ap.ID = spd.objectasalprodukfk 
                WHERE 
                    spd.statusenabled = 't'
                    and pr.statusenabled = 't'
                    AND spd.tglsaldo BETWEEN '$startOfMonth' AND '$startOfMonth'
                    $filterNamaProduk $filterDjProduk $filterJenisProduk
                GROUP BY pr.ID, pr.kdproduk, pr.namaproduk, ss.satuanstandar, ap.asalproduk, spd.harganetto, djp.detailjenisproduk

                UNION ALL

                -- Penerimaan
                SELECT
                    pr.ID AS idproduk,
                    pr.kdproduk AS kodeproduk,
                    pr.namaproduk AS namaproduk,
                    ss.satuanstandar,
                    ap.asalproduk,
                    djp.detailjenisproduk,
                    ((spd.hargasatuan - ( spd.hargasatuan * spd.persendiscount / 100)) + ((spd.hargasatuan - ( spd.hargasatuan * spd.persendiscount / 100 )) * spd.persenppn / 100 )) AS hargasatuan,
                    0 AS saldoawal,
                    SUM(spd.qtyproduk) AS qtypenerimaan,
                    0 AS qtypengeluaran,
                    0 AS qtyexpired
                FROM strukpelayanan_t AS sp
                    JOIN strukpelayanandetail_t AS spd ON spd.nostrukfk = sp.norec
                    JOIN produk_m AS pr ON pr.ID = spd.objectprodukfk
                    JOIN detailjenisproduk_m AS djp ON djp.ID = pr.objectdetailjenisprodukfk
                    JOIN satuanstandar_m AS ss ON ss.ID = pr.objectsatuanstandarfk
                    JOIN asalproduk_m AS ap ON ap.ID = spd.objectasalprodukfk 
                WHERE 
                    sp.statusenabled = 't'
                    and pr.statusenabled = 't'
                    AND spd.statusenabled = 't'
                    AND sp.objectkelompoktransaksifk = 518
                    AND spd.tglpelayanan BETWEEN '$startOfMonth' AND '$tglakhir'
                    $filterNamaProduk $filterDjProduk $filterJenisProduk
                GROUP BY pr.ID, pr.kdproduk, pr.namaproduk, ss.satuanstandar, ap.asalproduk, djp.detailjenisproduk,
                        ((spd.hargasatuan - ( spd.hargasatuan * spd.persendiscount / 100)) + ((spd.hargasatuan - ( spd.hargasatuan * spd.persendiscount / 100 )) * spd.persenppn / 100 ))

                UNION ALL

                -- Pengeluaran: Resep dan Bebas dan Pemakaian
                SELECT 	
                    idproduk,
                    kodeproduk,
                    namaproduk,
                    satuanstandar,
                    asalproduk,
                    detailjenisproduk,
                    hargasatuan AS hargasatuan,
                    0 as saldoawal,
                    0 AS qtypenerimaan,
                    SUM ( qtypengeluaran ) AS qtypengeluaran,
                    0 as qtyexpired 
                    FROM 
                    (
                    -- Resep Biasa dan Kronis
                    SELECT
                        pr.ID AS idproduk,
                        pr.kdproduk AS kodeproduk,
                        pr.namaproduk AS namaproduk,
                        ss.satuanstandar,
                        ap.asalproduk,
                        djp.detailjenisproduk,
                        spd.harganetto2 AS hargasatuan,
                        0 AS saldoawal,
                        0 AS qtypenerimaan,
                        SUM(pp.jumlah + COALESCE(ppo.jumlah, 0)) AS qtypengeluaran
                    FROM pelayananpasien_t AS pp
                        JOIN stokprodukdetail_t AS spd ON spd.norec = pp.stokprodukdetailfk
                        LEFT JOIN pelayananpasienobatkronis_t AS ppo ON ppo.strukresepfk = pp.strukresepfk and pp.produkfk = ppo.produkfk
                        JOIN produk_m AS pr ON pr.ID = pp.produkfk
                        JOIN detailjenisproduk_m AS djp ON djp.ID = pr.objectdetailjenisprodukfk
                        JOIN satuanstandar_m AS ss ON ss.ID = pr.objectsatuanstandarfk
                        LEFT JOIN asalproduk_m AS ap ON ap.ID = spd.objectasalprodukfk 
                    WHERE 
                        pp.jumlah > 0
                        AND pp.statusenabled = 't' 
                        and pr.statusenabled = 't'
                        AND pp.tglpelayanan BETWEEN '$startOfMonth' AND '$tglakhir'
                    $filterNamaProduk $filterDjProduk $filterJenisProduk
                    GROUP BY pr.ID, pr.kdproduk, pr.namaproduk, ss.satuanstandar, ap.asalproduk, djp.detailjenisproduk, spd.harganetto2

                    UNION ALL

                    -- Penjualan Obat Bebas
                    SELECT
                        pr.ID AS idproduk,
                        pr.kdproduk AS kodeproduk,
                        pr.namaproduk AS namaproduk,
                        ss.satuanstandar,
                        ap.asalproduk,
                        djp.detailjenisproduk,
                        spdd.harganetto2 AS hargasatuan,
                        0 AS saldoawal,
                        0 AS qtypenerimaan,
                        SUM(spd.qtyproduk) AS qtypengeluaran
                    FROM strukpelayanan_t AS sp
                        JOIN strukpelayanandetail_t AS spd ON spd.nostrukfk = sp.norec
                        JOIN stokprodukdetail_t AS spdd ON spdd.norec = spd.stokprodukdetailfk
                        JOIN produk_m AS pr ON pr.ID = spd.objectprodukfk
                        JOIN detailjenisproduk_m AS djp ON djp.ID = pr.objectdetailjenisprodukfk
                        JOIN satuanstandar_m AS ss ON ss.ID = pr.objectsatuanstandarfk
                        LEFT JOIN asalproduk_m AS ap ON ap.ID = spdd.objectasalprodukfk 
                    WHERE sp.keteranganlainnya = 'Penjualan Obat Bebas'
                        and pr.statusenabled = 't'
                        AND spd.statusenabled = 't'
                        AND spd.qtyproduk > 0
                        AND spd.tglpelayanan BETWEEN '$startOfMonth' AND '$tglakhir'
                    $filterNamaProduk $filterDjProduk $filterJenisProduk
                    GROUP BY pr.ID, pr.kdproduk, pr.namaproduk, ss.satuanstandar, ap.asalproduk, djp.detailjenisproduk, spdd.harganetto2

                    UNION ALL

                    -- Pemakaian (Kirim ke ruangan)
                    SELECT
                        pr.ID AS idproduk,
                        pr.kdproduk AS kodeproduk,
                        pr.namaproduk AS namaproduk,
                        ss.satuanstandar,
                        ap.asalproduk,
                        djp.detailjenisproduk,
                        spd.harganetto2 AS hargasatuan,
                        0 AS saldoawal,
                        0 AS qtypenerimaan,
                        SUM(kp.qtyprodukkonfirmasi) AS qtypengeluaran
                    FROM strukkirim_t AS sk
                        JOIN kirimproduk_t AS kp ON kp.nokirimfk = sk.norec
                        JOIN stokprodukdetail_t AS spd ON spd.norec = kp.stokprodukdetailfk
                        JOIN produk_m AS pr ON pr.ID = kp.objectprodukfk
                        JOIN detailjenisproduk_m AS djp ON djp.ID = pr.objectdetailjenisprodukfk
                        JOIN satuanstandar_m AS ss ON ss.ID = pr.objectsatuanstandarfk
                        LEFT JOIN asalproduk_m AS ap ON ap.ID = spd.objectasalprodukfk 
                    WHERE 
                        sk.isfloor = 't'
                        and pr.statusenabled = 't'
                        AND kp.statusenabled = 't'
                        AND sk.statusenabled = 't'
                        AND kp.qtyprodukkonfirmasi > 0
                        AND kp.tglpelayanan BETWEEN '$startOfMonth' AND '$tglakhir'
                    $filterNamaProduk $filterDjProduk $filterJenisProduk
                    GROUP BY 
                        pr.ID,
                        pr.kdproduk, 
                        pr.namaproduk, 
                        ss.satuanstandar, 
                        ap.asalproduk, 
                        djp.detailjenisproduk, 
                        spd.harganetto2
                ) AS pengeluaran
                GROUP BY 
                    idproduk,
                    kodeproduk,
                    namaproduk,
                    satuanstandar,
                    asalproduk,
                    detailjenisproduk,
                    hargasatuan
                

                UNION ALL

                -- Stok Expired
                SELECT
                    pr.ID AS idproduk,
                    pr.kdproduk AS kodeproduk,
                    pr.namaproduk AS namaproduk,
                    ss.satuanstandar,
                    ap.asalproduk,
                    djp.detailjenisproduk,
                    spd.harganetto2 AS hargasatuan,
                    0 AS saldoawal,
                    0 AS qtypenerimaan,
                    0 AS qtypengeluaran,
                    SUM(kp.qtyprodukkonfirmasi) AS qtyexpired
                FROM strukkirim_t AS sk
                    JOIN kirimproduk_t AS kp ON kp.nokirimfk = sk.norec
                    JOIN stokprodukdetail_t AS spd ON spd.norec = kp.stokprodukdetailfk
                    JOIN produk_m AS pr ON pr.ID = kp.objectprodukfk
                    JOIN detailjenisproduk_m AS djp ON djp.ID = pr.objectdetailjenisprodukfk
                    JOIN satuanstandar_m AS ss ON ss.ID = pr.objectsatuanstandarfk
                    LEFT JOIN asalproduk_m AS ap ON ap.ID = spd.objectasalprodukfk 
                WHERE 
                    kp.objectruanganfk = 344
                    and pr.statusenabled = 't'
                    AND kp.statusenabled = 't'
                    AND sk.statusenabled = 't'
                    AND kp.qtyprodukkonfirmasi > 0
                    AND kp.tglpelayanan BETWEEN '$startOfMonth' AND '$tglakhir'
                    $filterNamaProduk $filterDjProduk $filterJenisProduk
                GROUP BY pr.ID, pr.kdproduk, pr.namaproduk, ss.satuanstandar, ap.asalproduk, djp.detailjenisproduk, spd.harganetto2
            ) AS LaporanPenerimaan
            GROUP BY 
                idproduk,
                kodeproduk,
                namaproduk, 
                satuanstandar, 
                asalproduk, 
                hargasatuan, 
                detailjenisproduk                                
            "));

        $grouped = collect($data)->groupBy('idproduk');
        $adjusted = $grouped->flatMap(function ($items) {
            $items = collect($items)->values();

            for ($i = 0; $i < $items->count(); $i++) {
                $item = $items[$i];
                $saldoAkhir = ($item->saldoawal + $item->qtyterima - $item->qtykeluar - $item->qtyed);

                if ($saldoAkhir < 0) {
                    $defisit = abs($saldoAkhir);

                    $donors = $items->filter(function ($row, $index) use ($i) {
                        if ($index === $i) 
                        return false;
                        $saldo = ($row->saldoawal + $row->qtyterima - $row->qtykeluar - $row->qtyed);
                        return $saldo > 0;
                    })->sortByDesc(function ($row) {
                        return ($row->saldoawal + $row->qtyterima - $row->qtykeluar - $row->qtyed);
                    })->values();

                    foreach ($donors as $donor) {
                        $donorSaldo = ($donor->saldoawal + $donor->qtyterima - $donor->qtykeluar - $donor->qtyed);
                        $transfer = min($donorSaldo, $defisit);

                        $donor->qtykeluar += $transfer;
                        $donor->totalhargakeluar = $donor->qtykeluar * $donor->hargasatuan;

                        $item->qtykeluar -= $transfer;
                        $item->totalhargakeluar = $item->qtykeluar * $item->hargasatuan;

                        $defisit -= $transfer;
                        if ($defisit <= 0) break;
                    }

                    if ($defisit > 0) {
                        $item->saldoawal += $defisit;
                    }
                }
            }

            return $items->map(function ($row) {
                $row->saldoakhir = ($row->saldoawal + $row->qtyterima - $row->qtykeluar - $row->qtyed);
                $row->totalrpsaldoakhir = ($row->saldoawal + $row->qtyterima - $row->qtykeluar - $row->qtyed) * $row->hargasatuan;
                return $row;
            });
        });


        $result = array(
            'datanya' => $data,
            'data' => $adjusted,
            'message' => 'sep@epic',
        );
        return $this->respond($result);
    }
}
