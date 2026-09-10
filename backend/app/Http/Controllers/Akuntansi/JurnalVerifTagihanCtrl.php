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

class JurnalVerifTagihanCtrl extends Controller
{
    use Valet;
    public function __construct()
    {
        parent::__construct(true);
    }
    public function getDetailVerifikasi(Request $request)
    {
        $idProfile = (int) $this->kdProfile;
        $tglAwal = $request['tglAwal'];
        $tglAkhir = $request['tglAkhir'];
        $filterNoreg = '';
        if (isset($request['noreg']) && $request['noreg'] != "" && $request['noreg'] != "undefined") {
            $filterNoreg = " and pd.noregistrasi = '" . $request['noreg']  . "'";
        }

        $kelId = '';
        if (isset($request['kelId']) && $request['kelId'] != "" && $request['kelId'] != "undefined") {
            $kelId = " and pd.objectkelompokpasienlastfk in ("  . $request['kelId']  . ")";
        }
        $filter = '';
        if (isset($request['search']) && $request['search'] != '') {

            $filter =" and ( ps.namapasien ilike '%". $request['search']."%' or   pd.noregistrasi ilike '%". $request['search']."%' or    ps.nocm ilike '%". $request['search']."%' )";
        }
        $data = DB::select(
            DB::raw("
               
                select  sp.norec as norec_sp,sp.nostruk,pd.noregistrasi,ps.namapasien,pd.tglpulang as tglstruk,sp.totalharusdibayar,sp.totalprekanan,
                kp.id as kpid,ru.objectdepartemenfk,ru.objectdepartemenfk as dept_pd,to_char(pd.tglpulang, 'YYYY-MM-DD') as tgl,pd.objectruanganlastfk,pd.objectrekananfk,
                ru.namaruangan,rkn.namarekanan,pd.objectkelompokpasienlastfk,ru.jenis,kp.kelompokpasien,
                string_agg(cast(coa.id as text), ' , ' ) as idagg
                ,string_agg(case when pjtd.hargasatuank = 0 then 'D'  else  'K'  end || ' : ' || coa.namaaccount || ' ' || 
                case when pjtd.hargasatuank = 0 then pjtd.hargasatuand  else  pjtd.hargasatuank  end, ' , ' order by case when pjtd.hargasatuank = 0 then 'D'  else  'K'  end) as jurnal

                from strukpelayanan_t as sp
                INNER JOIN pasiendaftar_t as pd on pd.norec=sp.noregistrasifk
                INNER JOIN antrianpasiendiperiksa_t as apd on apd.noregistrasifk=pd.norec and pd.objectruanganlastfk=apd.objectruanganfk
                INNER JOIN ruangan_m as ru on ru.id=pd.objectruanganlastfk
                INNER JOIN pasien_m as ps on ps.id=pd.nocmfk
                INNER JOIN kelompokpasien_m as kp on kp.id=pd.objectkelompokpasienlastfk
                INNER JOIN rekanan_m as rkn on rkn.id=pd.objectrekananfk
                left join postingjurnaltransaksi_t pjt on pjt.norecrelated = sp.norec
                LEFT join postingjurnaltransaksid_t pjtd on pjtd.norecrelated=pjt.norec 
                left join chartofaccount_m coa on coa.id=pjtd.objectaccountfk
                where sp.kdprofile = $idProfile and pd.tglpulang BETWEEN '$tglAwal' and '$tglAkhir' 
                and sp.statusenabled=true $filterNoreg
                $filter
                $kelId
                group by  sp.norec ,sp.nostruk,pd.noregistrasi,ps.namapasien,pd.tglpulang ,sp.totalharusdibayar,sp.totalprekanan,
                kp.id ,ru.objectdepartemenfk,ru.objectdepartemenfk ,to_char(pd.tglpulang, 'YYYY-MM-DD') ,pd.objectruanganlastfk,pd.objectrekananfk,
                ru.namaruangan,rkn.namarekanan,pd.objectkelompokpasienlastfk,ru.jenis,apd.tglmasuk,apd.norec


        ")
        );
        return $this->respond($data);
    }
    public function getDetailMapCoaByKelompokPasienRekanan(Request $request)
    {
        $kdProfile = $this->kdProfile;

        $kpid = $request['objectkelompokpasienfk'];
        $rkid = "";
        if (isset($request['objectrekananfk']) && $request['objectrekananfk'] != "" && $request['objectrekananfk'] != "undefined") {
            $rkid = " and map.objectrekananfk = " . $request['objectrekananfk'];
        }
        $objectjenistrxfk = "";
        if (isset($request['objectjenistrxfk']) && $request['objectjenistrxfk'] != "" && $request['objectjenistrxfk'] != "undefined") {
            $objectjenistrxfk = " and  map.objectjenistrxfk in  (" . $request['objectjenistrxfk']  . ") ";
        }

        $data = DB::select(
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
            where map.objectkelompokpasienfk=$kpid
             $rkid $objectjenistrxfk;

        ")
        );
        return $this->respond($data);
    }
    public function PostingJurnal_PerDetailTransaksi_verifikasi(Request $request)
    {
        $kdProfile = (int) $this->kdProfile;
        DB::beginTransaction();

        try {
            PostingJurnalTransaksi::where('norecrelated', $request['verifikasi']['norec_sp'])
            ->where('deskripsiproduktransaksi', 'verifikasi_tarek')
            ->where('statusenabled', true)
            ->delete();

            $objectkelompokpasienlastfk = $request['verifikasi']['objectkelompokpasienlastfk'];
            $objectruanganlastfk  = $request['verifikasi']['objectruanganlastfk'];
            $objectrekananfk = "";//$request['verifikasi']['objectrekananfk'];
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
                    where map.objectkelompokpasienfk=$objectkelompokpasienlastfk
                    and map.objectruanganfk = $objectruanganlastfk
        
                ")
            );
            $dataHasilJurnal = [];
            foreach ($dataMapJurnal as $item) {
                if($item->objectjenistrxfk == 2){//verifikasi
                    
                    $debetId = $item->objectcoadebetfk;
                    $kreditId = $item->objectcoakreditfk;
                    
                    $noReg = $request['verifikasi']['noregistrasi'];
                    $namaPasien = $request['verifikasi']['namapasien'];
                    $noBuktiTransaksi = $request['verifikasi']['nostruk'];
                    $noPosting = '-';

                    $noJurnalIntern = Carbon::parse($request['verifikasi']['tglstruk'])->format('ym') . 'PN' . Carbon::parse($request['verifikasi']['tglstruk'])->format('d') . '00002';
                    $tgl = Carbon::parse($request['verifikasi']['tglstruk'])->format('Y-m-d');
                    
                    $cekSudahPosting =  PostingJurnal::where('norecrelated',$noJurnalIntern)->where('kdprofile', $kdProfile)->get();
                    if ($this->getCountArray($cekSudahPosting) == 0) {
                        $postingJurnalTransaksi = new PostingJurnalTransaksi;
                        $norecHead = $postingJurnalTransaksi->generateNewId();
                        $postingJurnalTransaksi->norec = $norecHead;
                        $postingJurnalTransaksi->kdprofile = $kdProfile;
                        $postingJurnalTransaksi->noposting = $noPosting;
                        $postingJurnalTransaksi->nojurnal = 0;
                        $postingJurnalTransaksi->objectjenisjurnalfk = 1;
                        $postingJurnalTransaksi->nobuktitransaksi = $noBuktiTransaksi;
                        $postingJurnalTransaksi->tglbuktitransaksi = $request['verifikasi']['tglstruk'];
                        $postingJurnalTransaksi->kdproduk = null;
                        $postingJurnalTransaksi->namaproduktransaksi = 'Verifikasi tagihan ' . $noReg . ', ' . $namaPasien . ' di TataRekening';
                        $postingJurnalTransaksi->deskripsiproduktransaksi = 'verifikasi_tarek';
                        $postingJurnalTransaksi->keteranganlainnya = 'Verifikasi Tagihan Tgl. ' . $tgl;
                        $postingJurnalTransaksi->nojurnal_intern = $noJurnalIntern;
                        $postingJurnalTransaksi->statusenabled = 1;
                        $postingJurnalTransaksi->norecrelated = $request['verifikasi']['norec_sp'];
                        $postingJurnalTransaksi->jenis = $request['verifikasi']['jenis'];
                        $postingJurnalTransaksi->save();

                        $norec_pj = $postingJurnalTransaksi->norec;

                        $totalRp = $request['verifikasi']['totalharusdibayar'];
                        $totalRekananRp = $request['verifikasi']['totalprekanan'];

                        if ($totalRekananRp > 0) {
                            //debet
                            $postingJurnalTransaksiD = new PostingJurnalTransaksiD;
                            $postingJurnalTransaksiD->norec = $postingJurnalTransaksiD->generateNewId();
                            $postingJurnalTransaksiD->kdprofile = $kdProfile;
                            $postingJurnalTransaksiD->nojurnal = 0;
                            $postingJurnalTransaksiD->noposting = $noPosting;
                            $postingJurnalTransaksiD->objectaccountfk = $debetId;
                            $postingJurnalTransaksiD->hargasatuand = $totalRekananRp;
                            $postingJurnalTransaksiD->hargasatuank = 0;
                            $postingJurnalTransaksiD->statusenabled = 1;
                            $postingJurnalTransaksiD->norecrelated = $norec_pj;
                            $postingJurnalTransaksiD->save();

                            //kredit
                            $postingJurnalTransaksiD = new PostingJurnalTransaksiD;
                            $postingJurnalTransaksiD->norec = $postingJurnalTransaksiD->generateNewId();
                            $postingJurnalTransaksiD->kdprofile = $kdProfile;
                            $postingJurnalTransaksiD->nojurnal = 0;
                            $postingJurnalTransaksiD->noposting = $noPosting;
                            $postingJurnalTransaksiD->objectaccountfk = $kreditId;
                            $postingJurnalTransaksiD->hargasatuand = 0;
                            $postingJurnalTransaksiD->hargasatuank = $totalRekananRp;
                            $postingJurnalTransaksiD->statusenabled = 1;
                            $postingJurnalTransaksiD->norecrelated = $norec_pj;
                            $postingJurnalTransaksiD->save();
                        }
                        if ($totalRp > 0) {

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
                            $postingJurnalTransaksiD->norecrelated = $norec_pj;
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
                            $postingJurnalTransaksiD->norecrelated = $norec_pj;
                            $postingJurnalTransaksiD->save();
                        }
                    }
                    
                    $dataHasilJurnal[]=array(
                        "debet" => "D : " . $item->nmdebet . ' ' . $totalRekananRp,
                        "kredit" => "K : " . $item->nmkredit . ' ' . $totalRekananRp,
                        "norec_sp" => $request['verifikasi']['norec_sp'],
                        "nojurnal" => $noJurnalIntern,
                    );
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
                    "e" => $e->getMessage() . ' ' . $e->getLine(),
                    "as" => 'as@epic',
                )
            );
        }
        return $this->respond($result['result'], $result['status'], $transMessage);
    }
}
