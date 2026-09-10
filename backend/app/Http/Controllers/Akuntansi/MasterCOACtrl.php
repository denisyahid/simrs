<?php

namespace App\Http\Controllers\Akuntansi;

use App\Http\Controllers\Controller;
use App\Models\Master\ChartOfAccount;
use App\Models\Master\Departemen;
use App\Models\Master\KelompokPasien;
use App\Models\Master\Profile;
use App\Models\Master\Ruangan;
use App\Models\Transaksi\AntrianPasienRegistrasi;
use App\Models\Transaksi\ChartOfAccountMapJurnal;
use App\Models\Transaksi\LoggingUser;
use App\Models\Transaksi\PostingJurnal;
use App\Models\Transaksi\PostingJurnalTransaksi;
use App\Models\Transaksi\PostingJurnalTransaksiD;
use App\Models\Transaksi\PostingSaldoAwal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\FacadesDB;
use App\Traits\Valet;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;

class MasterCOACtrl extends Controller
{
    use Valet;
    public function __construct()
    {
        parent::__construct(true);
    }
    public function getDaftarCoa(Request $request) {

        $idProfile = (int) $this->kdProfile;
        $data = DB::table('chartofaccount_m as coa')
            ->select('coa.norec','coa.id','coa.noaccount','coa.namaaccount',
                'coa.objectjenisaccountfk','ja.jenisaccount',
                'coa.objectkategoryaccountfk','ka.kategoryaccount',
                'coa.objectstatusaccountfk','sa.statusaccount',
                'coa.objectstrukturaccountfk','sta.strukturaccount',
                'coa.saldonormaladd','coa.saldonormalmin','coa.statusenabled')
            ->leftJOIN('jenisaccount_m as ja','ja.id','=','coa.objectjenisaccountfk')
            ->leftJOIN('kategoryaccount_m as ka','ka.id','=','coa.objectkategoryaccountfk')
            ->leftJOIN('statusaccount_m as sa','sa.id','=','coa.objectstatusaccountfk')
            ->leftJOIN('strukturaccount_m as sta','sta.id','=','coa.objectstrukturaccountfk')
            ->leftJOIN('suratkeputusan_m as sk','sk.id','=','coa.suratkeputusanfk')
            ->where('coa.kdprofile',$idProfile)
            ->where('coa.statusenabled','=',1)
            ->where('sk.statusenabled','=',1)
            ->orderBy('coa.noaccount');


        if(isset($request['noaccount']) && $request['noaccount']!="" && $request['noaccount']!="undefined"){
            $data = $data->where('noaccount','ilike',''. $request['noaccount'].'%');
        }
        if(isset($request['namaaccount']) && $request['namaaccount']!="" && $request['namaaccount']!="undefined"){
            $data = $data->where('namaaccount','ilike','%'. $request['namaaccount'].'%');
        }
        if(isset($request['rows']) && $request['rows']!="" && $request['rows']!="undefined"){
            $data = $data->take($request['rows']);
        }

        $data = $data->get();
        return $this->respond($data);
    }

    public function SaveDataChartOfAccount(Request $request) {

        $idProfile = (int) $this->kdProfile;
        DB::beginTransaction();
        $dataLogin = $request->all();

        try {
            if ($request['id'] == ''){
                $lasID =  ChartOfAccount::max('id');
                $newID = $lasID + 1;

                $newCOA = new ChartOfAccount();
                $norecHead = $newCOA->generateNewId();
                $newCOA->id = $newID;
                $newCOA->kdprofile = $idProfile;
                $newCOA->norec = $norecHead;
                $newCOA->qaccount = $newID;
            }else{

                $newCOA =  ChartOfAccount::where('id',$request['id'])->where('kdprofile', $idProfile)->first();
            }
            $newCOA->kodeexternal = $request['kodeexternal'];
            $newCOA->namaexternal = $request['kdaccount'];
            $newCOA->statusenabled = $request['statusenabled'];
            $newCOA->objectjenisaccountfk = $request['objectjenisaccountfk'];
            $newCOA->objectkategoryaccountfk = $request['objectkategoryaccountfk'];
            $newCOA->objectstatusaccountfk = $request['objectstatusaccountfk'];
            $newCOA->objectstrukturaccountfk = $request['objectstrukturaccountfk'];
            $newCOA->noaccount = $request['kdaccount'];
            $newCOA->namaaccount = $request['namaaccount'];
            $newCOA->saldonormaladd = $request['saldonormaladd'];
            $newCOA->saldonormalmin = $request['saldonormalmin'];
            $newCOA->suratkeputusanfk = 3157;
            $newCOA->save();

            $norecHead2 = $newCOA->norec;
            $transMessage = "Sukses";
            DB::commit();
            $result = array(
                "status" => 200,
                "result" => array(

                    "as" => '@epic',
                ),
            );
        } catch (\Exception $e) {

            $transMessage = "Simpan Gagal";
            DB::rollBack();
            $result = array(
                "status" => 400,
                "result"  => $e->getMessage() . ' ' . $e->getLine()

            );
        }
        return $this->respond($result['result'], $result['status'], $transMessage);

    }
    public function SaveHapusChartOfAccount(Request $request) {
        $idProfile = (int) $this->kdProfile;
        DB::beginTransaction();
        $dataLogin = $request->all();

        try {
            if ($request['id'] == ''){

            }else{

                $newCOA =  ChartOfAccount::where('id',$request['id'])->where('kdprofile', $idProfile)
                    ->update(
                        [ 'statusenabled' => 0]
                    );
                    $this->LOGGING(
                        'Hapus Chart Of Account',
                        $request['id'],
                        'chartofaccount_m',
                        'Hapus coa ' . $request['kdaccount'].' - '. $request['namaaccount']
                    );

            }

            $transMessage = "Hapus Sukses";

            DB::commit();
            $result = array(
                "status" => 200,
                "result" => array(
                    "as" => '@epic',
                ),
            );
        } catch (\Exception $e) {

            $transMessage = "Hapus Gagal";
            DB::rollBack();
            $result = array(
                "status" => 400,
                "result"  => $e->getMessage() . ' ' . $e->getLine()

            );
        }
        return $this->respond($result['result'], $result['status'], $transMessage);

    }
    public function getDataComboMasterAkun(Request $request){

        $idProfile = (int) $this->kdProfile;


        $dataJenisAccount= DB::table('jenisaccount_m as jd')
            ->where('jd.kdprofile', $idProfile)
            ->where('statusenabled',true)
            ->get();
        $dataKategoryAccount= DB::table('kategoryaccount_m as jd')
            ->where('jd.kdprofile', $idProfile)
            ->where('statusenabled',true)
            ->get();
        $dataStatusAccount= DB::table('statusaccount_m as jd')
            ->where('jd.kdprofile', $idProfile)
            ->where('statusenabled',true)
            ->get();
        $dataStrukturAccount= DB::table('strukturaccount_m as jd')
            ->where('jd.kdprofile', $idProfile)
            ->where('statusenabled',true)
            ->get();

        $result = array(

            'jenisaccount' =>   $dataJenisAccount,
            'kategoryaccount' =>   $dataKategoryAccount,
            'statusaccount' =>   $dataStatusAccount,
            'strukturaccount' =>   $dataStrukturAccount,
            'message' => '@epic',
        );

        return $this->respond($result);
    }
    public function getDaftarSaldoAwal(Request $request) {
        $idProfile = (int) $this->kdProfile;

        if(isset($request['coaid']) && $request['coaid']!="" && $request['coaid']!="undefined"){
            $data = DB::select(DB::raw("select *,TO_CHAR(x.tgl, 'DD/MM/YYYY') from
                (select cast(ym || '01' as date) as tgl,hargasatuand,hargasatuank,coa.noaccount,coa.namaaccount,psa.statusenabled,
                psa.norec,coa.id
                from postingsaldoawal_t as psa
                INNER JOIN chartofaccount_m as coa on coa.id=psa.objectaccountfk
                where psa.kdprofile = $idProfile and coa.id=:coaId order by ym desc limit 5) as x"),
                array(
                    'coaId' => $request['coaid'],
                )
            );
        }else{
            $data = DB::select(DB::raw("select *,TO_CHAR(x.tgl, 'DD/MM/YYYY') from
                (select cast(ym || '01' as date) as tgl,hargasatuand,hargasatuank,coa.noaccount,coa.namaaccount,psa.statusenabled,
                psa.norec,coa.id
                from postingsaldoawal_t as psa
                INNER JOIN chartofaccount_m as coa on coa.id=psa.objectaccountfk
                where psa.kdprofile = $idProfile and coa.noaccount=:noaccount order by ym desc limit 5) as x;"),
                array(
                    'noaccount' => $request['noaccount'],
                )
            );
        };

        return $this->respond($data);
    }

    public function SaveSaldoAwal(Request $request) {
        DB::beginTransaction();
        $dataReq = $request->all();
        $idProfile = (int) $this->kdProfile;
        try {
            if ($dataReq['norec'] == '-'){

                $postingSA = new PostingSaldoAwal();
                $norecHead = $postingSA->generateNewId();
                $postingSA->norec = $norecHead;
                $postingSA->kdprofile = $idProfile;
            }else{
                $postingSA = PostingSaldoAwal::where('norec', $dataReq['norec'])
                    ->first();

            }

            $postingSA->objectaccountfk = $dataReq['objectaccountfk'];
            $postingSA->hargasatuand = $dataReq['hargasatuand'];
            $postingSA->hargasatuank = $dataReq['hargasatuank'];
            $postingSA->statusenabled = $dataReq['statusenabled'];
            $postingSA->ym = $dataReq['ym'];
            $postingSA->save();
            $transMessage = "Sukses";
            DB::commit();
            $result = array(
                "status" => 200,
                "result" => array(

                    "as" => '@epic',
                ),
            );
        } catch (\Exception $e) {

            $transMessage = "Simpan Gagal";
            DB::rollBack();
            $result = array(
                "status" => 400,
                "result"  => $e->getMessage() . ' ' . $e->getLine()

            );
        }
        return $this->respond($result['result'], $result['status'], $transMessage);

    }
    public function SaveHapusSaldoAwal(Request $request) {
        $idProfile = $this->kdProfile;
        DB::beginTransaction();

        try {
            $newPPD2 = PostingSaldoAwal::where('norec', $request['head'])->where('kdprofile', $idProfile)->delete();
            $transMessage = "Hapus Sukses";
            DB::commit();
            $result = array(
                "status" => 200,
                "result" => array(
                    "as" => '@epic',
                ),
            );
        } catch (\Exception $e) {

            $transMessage = "Simpan Gagal";
            DB::rollBack();
            $result = array(
                "status" => 400,
                "result"  => $e->getMessage() . ' ' . $e->getLine()

            );
        }
        return $this->respond($result['result'], $result['status'], $transMessage);
    }

    public function mappingjurnal(Request $request){
        $idProfile = (int) $this->kdProfile;
        $data = DB::table('chartofaccountmapjurnal_t as cmap')
                ->select(
                    'cmap.norec',
                    'jpj.jenistransaksi',
                    'prd.id as idproduk',
                    'prd.namaproduk',
                    'coad.noaccount AS no_debit',
                    'coad.namaaccount AS coa_debit',
                    'coak.noaccount AS no_kredit',
                    'coak.namaaccount AS coa_kredit',
                    'dp.namadepartemen',
                    'ru.namaruangan',
                    'kp.kelompokpasien',
                    'djp.detailjenisproduk',
                    'cb.carabayar',
                    'rm.namarekanan',
                    'bm.nama',
                    'cmap.objectjenistrxfk',
                    'cmap.objectruanganfk',
                    'cmap.objectdepartemenfk',
                    'cmap.objectkelompokpasienfk',
                    'cmap.objectprodukfk',
                    'cmap.objectcarabayarfk',
                    'cmap.objectrekananfk',
                    'cmap.objectbankfk',
                    'cmap.objectcoadebetfk',
                    'cmap.objectcoakreditfk'
                )
                ->leftJoin('jenispelayananjurnal_m as jpj', 'jpj.id', '=', 'cmap.objectjenistrxfk')
                ->leftJoin('chartofaccount_m as coad', 'coad.id', '=', 'cmap.objectcoadebetfk')
                ->leftJoin('chartofaccount_m as coak', 'coak.id', '=', 'cmap.objectcoakreditfk')
                ->leftJoin('departemen_m as dp', 'dp.id', '=', 'cmap.objectdepartemenfk')
                ->leftJoin('ruangan_m as ru', 'ru.id', '=', 'cmap.objectruanganfk')
                ->leftJoin('kelompokpasien_m as kp', 'kp.id', '=', 'cmap.objectkelompokpasienfk')
                ->leftJoin('detailjenisproduk_m as djp', 'djp.id', '=', 'cmap.objectdetailjenisprodukfk')
                ->leftJoin('produk_m as prd', 'prd.id', '=', 'cmap.objectprodukfk')
                ->leftJoin('carabayar_m as cb', 'cb.id', '=', 'cmap.objectcarabayarfk')
                ->leftJoin('rekanan_m as rm', 'rm.id', '=', 'cmap.objectrekananfk')
                ->leftJoin('bank_m as bm', 'bm.id', '=', 'cmap.objectbankfk')
                ->where('cmap.kdprofile', $idProfile)
                ->where('cmap.statusenabled', true)
                ->orderBy('cmap.created_at', 'asc');

        if (isset($r['statusenabled']) && $r['statusenabled'] != '') {
            $data = $data->where('cmap.statusenabled', '=', $r['statusenabled']);
        }

        if(isset($request['rows']) && $request['rows']!="" && $request['rows']!="undefined"){
            $data = $data->take($request['rows']);
        }

        if(isset($request['namaproduk']) && $request['namaproduk']!="" && $request['namaproduk']!="undefined"){
            $data = $data->where('prd.namaproduk','ilike','%'. $request['namaproduk'].'%');
        }

        $data = $data->get();

        return $this->respond($data);
    }

}
