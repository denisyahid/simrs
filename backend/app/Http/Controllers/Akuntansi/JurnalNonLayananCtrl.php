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

class JurnalNonLayananCtrl extends Controller
{
    use Valet;
    public function __construct()
    {
        parent::__construct(true);
    }
    public function getDetailNonLayanan(Request $request)
    {

        $idProfile = (int) $this->kdProfile;
        $tglAwal = $request['tglAwal'];
        $tglAkhir = $request['tglAkhir'];
        $filterNoreg = '';
        if (isset($request['nostruk']) && $request['nostruk'] != "" && $request['nostruk'] != "undefined") {
            $filterNoreg = " and  sp.nostruk = '" . $request['nostruk']  . "'";
        }
        $list = $this->settingFix('kelompokTransaksiNonPelayanan', $idProfile);
        $data = DB::select(
            DB::raw("
               
            select sp.tglstruk, sp.nostruk,sp.namapasien_klien, spd.objectprodukfk,pr.namaproduk,ru.namaruangan,ss.satuanstandar,
            spd.qtyproduk,spd.hargasatuan,hargadiscount,hargappn,spd.objectprodukfk,
            spd.qtyproduk *((spd.hargasatuan-spd.hargadiscount)+hargappn) as hargatotal,sp.totalharusdibayar
            from strukpelayanan_t sp
            INNER JOIN strukpelayanandetail_t spd on spd.nostrukfk=sp.norec
            INNER JOIN produk_m pr on pr.id=spd.objectprodukfk
            INNER JOIN ruangan_m ru on ru.id=spd.objectruanganfk
            INNER JOIN satuanstandar_m ss on ss.id=spd.objectsatuanstandarfk
            where sp.kdprofile = $idProfile and  sp.tglstruk between '$tglAwal' and '$tglAkhir' 
            and sp.statusenabled=true $filterNoreg
            and  ( sp.objectkelompoktransaksifk in ($list) or   left(sp.nostruk,2)='OB')
           
            order by sp.nostruk
                
        ")
        );
        return $this->respond($data);
    }
   
}
