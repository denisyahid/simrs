<?php

namespace App\Http\Controllers\Akuntansi;

use App\Http\Controllers\Controller;
use App\Models\Master\Departemen;
use App\Models\Master\KelompokPasien;
use App\Models\Master\Profile;
use App\Models\Master\Ruangan;
use App\Models\Transaksi\ChartOfAccountMapJurnal;
use App\Models\Transaksi\PostingJurnal;
use App\Models\Transaksi\PostingJurnalTransaksi;
use App\Models\Transaksi\PostingJurnalTransaksiD;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use App\Traits\Valet;
use Carbon\Carbon;
use Exception;

class JurnalPenerimaanPersediaanCtrl extends Controller
{
    use Valet;
    public function __construct()
    {
        parent::__construct(true);
    }
    public function getDetailPenerimaanBarang(Request $request)
    {

        $idProfile = (int) $this->kdProfile;
        $tglAwal = $request['tglAwal'];
        $tglAkhir = $request['tglAkhir'];
        $filterNoreg = '';
        if (isset($request['nostruk']) && $request['nostruk'] != "" && $request['nostruk'] != "undefined") {
            $filterNoreg = " and sp.nostruk = '" . $request['nostruk']  . "'";
        }
        $kek = $this->kelompokTransaksi('PENERIMAAN BARANG SUPPLIER');
        $data = DB::select(
            DB::raw("
               
            select spd.norec, sp.tglstruk, sp.nostruk,rkn.namarekanan,sp.nokontrak,
            ru.namaruangan,sp.nofaktur,sp.totalharusdibayar,to_char(sp.tglstruk, 'YYYY-MM-DD') as tgl,
            pr.namaproduk,spd.hargadiscount ,spd.hargappn,djp.detailjenisproduk,jp.jenisproduk,djp.id as djpid,jp.id as jpid,pr.id as prid,
            CAST(((spd.qtyproduk*spd.hargasatuan)-(((spd.persendiscount*spd.hargasatuan)/100)*spd.qtyproduk))+(spd.persenppn*((spd.qtyproduk*spd.hargasatuan)-(((spd.persendiscount*spd.hargasatuan)/100)*spd.qtyproduk))/100) AS FLOAT) AS total,
            string_agg(cast(coa.id as text), ' , ' ) as idagg
            ,string_agg(case when pjtd.hargasatuank = 0 then 'D'  else  'K'  end || ' : ' || coa.namaaccount || ' ' || 
            case when pjtd.hargasatuank = 0 then pjtd.hargasatuand  else  pjtd.hargasatuank  end, ' , ' order by case when pjtd.hargasatuank = 0 then 'D'  else  'K'  end) as jurnal,
            pjt.nojurnal_intern
            
            from strukpelayanan_t as sp
            INNER JOIN strukpelayanandetail_t as spd on spd.nostrukfk=sp.norec
            left JOIN produk_m as pr on pr.id=spd.objectprodukfk
            INNER JOIN rekanan_m as rkn on rkn.id=sp.objectrekananfk
            INNER JOIN ruangan_m as ru on ru.id=sp.objectruanganfk
            INNER JOIN detailjenisproduk_m djp on djp.id=pr.objectdetailjenisprodukfk
            INNER JOIN jenisproduk_m jp on jp.id=djp.objectjenisprodukfk
            left join postingjurnaltransaksi_t pjt on pjt.norecrelated = spd.norec
            LEFT join postingjurnaltransaksid_t pjtd on pjtd.norecrelated=pjt.norec 
            left join chartofaccount_m coa on coa.id=pjtd.objectaccountfk
            where sp.kdprofile = $idProfile and sp.tglstruk between '$tglAwal' and '$tglAkhir' 
            and  sp.objectkelompoktransaksifk=$kek and sp.statusenabled <> 'f'
            $filterNoreg
            
            group by  spd.norec, sp.tglstruk, sp.nostruk,rkn.namarekanan,sp.nokontrak,
            ru.namaruangan,sp.nofaktur,sp.totalharusdibayar,to_char(sp.tglstruk, 'YYYY-MM-DD') ,
            pr.namaproduk,spd.hargadiscount ,spd.hargappn,pr.id,
            CAST(((spd.qtyproduk*spd.hargasatuan)-(((spd.persendiscount*spd.hargasatuan)/100)*spd.qtyproduk))+(spd.persenppn*((spd.qtyproduk*spd.hargasatuan)-(((spd.persendiscount*spd.hargasatuan)/100)*spd.qtyproduk))/100) AS FLOAT),djp.detailjenisproduk,jp.jenisproduk,djp.id,jp.id,
            pjt.nojurnal_intern

        ")
        );
        return $this->respond($data);
    }
    public function getDetailMapCoaByprodukPersediaan(Request $request)
    {
        $idProfile = (int) $this->kdProfile;
        $produkid = $request['produkid'];

        $data = DB::select(
            DB::raw("
               
            select  coa.noaccount as kddebet,coa.namaaccount as nmdebet,coa2.noaccount as kdkredit,coa2.namaaccount as nmkredit,
            dp.namadepartemen,ru.namaruangan,pr.namaproduk,rk.namarekanan,kp.kelompokpasien,
            cb.carabayar,bk.nama as bank,jpj.jenistransaksi,djp.detailjenisproduk,jp.jenisproduk,
            map.*
            from chartofaccountmapjurnal_t map
            INNER JOIN chartofaccount_m coa on coa.id=map.objectcoadebetfk
            INNER JOIN chartofaccount_m coa2 on coa2.id=map.objectcoakreditfk
            INNER JOIN jenispelayananjurnal_m jpj on jpj.id=map.objectjenistrxfk
            left JOIN departemen_m dp on dp.id=map.objectdepartemenfk
            left JOIN ruangan_m ru on ru.id=map.objectruanganfk
            left JOIN produk_m pr on pr.id=map.objectprodukfk
            left JOIN detailjenisproduk_m djp on djp.id=map.objectdetailjenisprodukfk
            left JOIN jenisproduk_m jp on jp.id=map.objectjenisprodukfk
            left JOIN rekanan_m rk on rk.id=map.objectrekananfk
            left JOIN kelompokpasien_m kp on kp.id=map.objectkelompokpasienfk
            left join carabayar_m cb on cb.id=map.objectcarabayarfk
            left join bank_m bk on bk.id=map.objectbankfk
            where map.objectprodukfk=$produkid 
            and map.objectjenistrxfk in (4,6,7)
            and map.kdprofile=$idProfile

        ")
        );
        return $this->respond($data);
    }
    public function PostingJurnal_PerDetailTransaksi_PenerimaanSuplier(Request $request)
    {
        $kdProfile = (int) $this->kdProfile;
        DB::beginTransaction();

        try {
            PostingJurnalTransaksi::where('norecrelated', $request['penerimaan']['norec'])
                ->where('deskripsiproduktransaksi', 'penerimaan_barang')
                ->where('statusenabled', true)
                ->delete();

            $idProduk = $request['penerimaan']['prid'];
            $dataMapJurnal = DB::select(
                DB::raw("
                   
                    select  coa.noaccount as kddebet,coa.namaaccount as nmdebet,coa2.noaccount as kdkredit,coa2.namaaccount as nmkredit,
                    dp.namadepartemen,ru.namaruangan,pr.namaproduk,rk.namarekanan,kp.kelompokpasien,cb.carabayar,bk.nama as bank,jpj.jenistransaksi,map.*
                    from chartofaccountmapjurnal_t map
                    INNER JOIN chartofaccount_m coa on coa.id=map.objectcoadebetfk
                    INNER JOIN chartofaccount_m coa2 on coa2.id=map.objectcoakreditfk
                    INNER JOIN jenispelayananjurnal_m jpj on jpj.id=map.objectjenistrxfk
                    left JOIN departemen_m dp on dp.id=map.objectdepartemenfk
                    left JOIN ruangan_m ru on ru.id=map.objectruanganfk
                    left JOIN produk_m pr on pr.id=map.objectprodukfk
                    left JOIN rekanan_m rk on rk.id=map.objectrekananfk
                    left JOIN kelompokpasien_m kp on kp.id=map.objectkelompokpasienfk
                    left join carabayar_m cb on cb.id=map.objectcarabayarfk
                    left join bank_m bk on bk.id=map.objectbankfk
                    where map.objectprodukfk=$idProduk
        
                ")
            );
            $dataHasilJurnal = [];
            $totalRp = 0;
            $totalDiskon = 0;
            $totalPpn = 0;
            foreach ($dataMapJurnal as $item) {
                $totalRp = 0;
                // $totalDiskon=0;
                // $totalPpn=0;
                if ($item->objectjenistrxfk == 4) {
                    $totalRp = $request['penerimaan']['total'];
                } else if ($item->objectjenistrxfk == 6) {
                    //Nilai Diskon Penerimaan
                    $totalRp = $request['penerimaan']['hargadiscount'];
                } else if ($item->objectjenistrxfk == 7) {
                    //Nilai PPN Penerimaan Barang
                    $totalRp = $request['penerimaan']['hargappn'];
                }

                if ($totalRp != 0) {
                    $debetId = $item->objectcoadebetfk;
                    $kreditId = $item->objectcoakreditfk;

                    $noBuktiTransaksi = $request['penerimaan']['nostruk'];
                    $noPosting = '-';


                    $noJurnalIntern = Carbon::parse($request['penerimaan']['tglstruk'])->format('ym') . 'PN' . Carbon::parse($request['penerimaan']['tglstruk'])->format('d') . '00004';
                    $cekSudahPosting =  PostingJurnal::where('norecrelated', $noJurnalIntern)->where('kdprofile', $kdProfile)->get();
                    $tgl = Carbon::parse($request['penerimaan']['tglstruk'])->format('Y-m-d');

                    if (count($cekSudahPosting) == 0) {

                        $newPJT = new PostingJurnalTransaksi;
                        $norecHead = $newPJT->generateNewId();
                        $newPJT->norec = $norecHead;

                        $newPJT->kdprofile = $kdProfile;

                        $newPJT->noposting = $noPosting;
                        $newPJT->nojurnal = 0; //$nojurnal;
                        $newPJT->nojurnal_intern = $noJurnalIntern;
                        $newPJT->objectjenisjurnalfk = 1;
                        $newPJT->nobuktitransaksi = $noBuktiTransaksi;
                        $newPJT->tglbuktitransaksi = $request['penerimaan']['tglstruk']; // $this->getDateTime()->format('Y-m-d H:i:s');
                        $newPJT->kdproduk = null;
                        $newPJT->namaproduktransaksi = 'Penerimaan Barang ' . ' - ' . $request['penerimaan']['namaproduk'] .  ' dari ' . $request['penerimaan']['namarekanan']  . '->' . $request['penerimaan']['nostruk'] . '/' . $request['penerimaan']['nofaktur'];
                        $newPJT->deskripsiproduktransaksi = 'penerimaan_barang';
                        $newPJT->keteranganlainnya = 'Penerimaan Barang ' . $tgl;
                        $newPJT->statusenabled = true;
                        $newPJT->norecrelated = $request['penerimaan']['norec'];
                        $newPJT->save();

                        //debet
                        $postingJurnalTransaksiD = new PostingJurnalTransaksiD;
                        $postingJurnalTransaksiD->norec = $postingJurnalTransaksiD->generateNewId();
                        $postingJurnalTransaksiD->kdprofile = $kdProfile;
                        $postingJurnalTransaksiD->nojurnal = 0;
                        $postingJurnalTransaksiD->noposting = $noPosting;
                        $postingJurnalTransaksiD->objectaccountfk = $debetId;
                        $postingJurnalTransaksiD->hargasatuand = $totalRp;
                        $postingJurnalTransaksiD->hargasatuank = 0;
                        $postingJurnalTransaksiD->statusenabled = 1;
                        $postingJurnalTransaksiD->norecrelated = $norecHead;
                        $postingJurnalTransaksiD->save();

                        //kredit
                        $postingJurnalTransaksiD = new PostingJurnalTransaksiD;
                        $postingJurnalTransaksiD->norec = $postingJurnalTransaksiD->generateNewId();
                        $postingJurnalTransaksiD->kdprofile = $kdProfile;
                        $postingJurnalTransaksiD->nojurnal = 0;
                        $postingJurnalTransaksiD->noposting = $noPosting;
                        $postingJurnalTransaksiD->objectaccountfk = $kreditId;
                        $postingJurnalTransaksiD->hargasatuand = 0;
                        $postingJurnalTransaksiD->hargasatuank = $totalRp;
                        $postingJurnalTransaksiD->statusenabled = 1;
                        $postingJurnalTransaksiD->norecrelated = $norecHead;
                        $postingJurnalTransaksiD->save();

                        $dataHasilJurnal[] = array(
                            "debet" => "D : " . $item->nmdebet . ' ' . $totalRp,
                            "kredit" => "K : " . $item->nmkredit . ' ' . $totalRp,
                            "norec" => $request['penerimaan']['norec'],
                            "nojurnal" => $noJurnalIntern,
                        );
                    }
                }
            }
            $transStatus = 'true';
        } catch (\Exception $e) {
            $transStatus = 'false';
        }

        $transMessage = "Posting";

        if ($transStatus == 'true') {
            $transMessage = $transMessage . "";
            DB::commit();
            $result = array(
                "status" => 200,
                "result" => array(
                    "data" => $dataHasilJurnal,
                    "as" => 'as@epic',
                )
            );
        } else {
            $transMessage = $transMessage . " Gagal!!";
            DB::rollBack();
            $result = array(
                "status" => 400,
                "result" => array(
                    "as" => 'as@epic',
                    "e" => $e->getMessage(). ' '.$e->getLine()
                )
            );
        }
        return $this->respond($result['result'], $result['status'], $transMessage);
    }
}
