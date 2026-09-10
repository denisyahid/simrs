<?php

namespace App\Models\Transaksi;

use App\Traits\Valet;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;

class PelayananPasien extends _BaseModel
{
    use HasFactory;
    protected $table = "pelayananpasien_t";
    protected $fillable = [];
    public $timestamps = true;
    public $incrementing = false;
    protected $primaryKey = "norec";

    public function antrian_pasien_diperiksa()
    {
        return $this->belongsTo('App\Models\Transaksi\AntrianPasienDiperiksa', 'noregistrasifk', 'norec');
    }

    //    public function __construct(){$this->setTransformerPath('App\Transformers\Transaksi\PeriodeAccountTransformer');}

    //    public function periode_account_saldo(){
    //        return $this->hasMany('App\Transaksi\PeriodeAccountSaldo', 'kdperiodeaccount', 'kdperiodeaccount');
    //    }
    //
    public  function produk()
    {
        return $this->belongsTo('App\Models\Master\Produk', 'produkfk');
    }


    public function struk_pelayanan()
    {
        return $this->belongsTo('App\Models\Transaksi\StrukPelayanan', 'strukfk');
    }

    //    public function pelayan_pasien_detail(){
    //        return $this->hasMany('App\Transaksi\PelayananPasienDetail', 'strukfk');
    //    }
    //
    public function kelas()
    {
        return $this->belongsTo('App\Models\Master\Kelas', 'kelasfk');
    }
    //
    //    public function kelompok_pasien(){
    //        return $this->belongsTo('App\Master\KelompokPasien', 'objectkelompokpasienlastfk');
    //    }
    public function scopeDeposit($q,$noregistrasi = null)
    {
        if (empty($noregistrasi)) {
            return 0;
        }
        $produkDep =  DB::table('settingdatafixed_m')->where('namafield','idProdukDeposit')
        ->where("kdprofile", request()->session()->get('kdProfile'))
        ->where("statusenabled", true)->first();
        if (empty($produkDep)) {
            return 0;
        }
        $data = $q->where("statusenabled", true)
                ->where('noregistrasi', $noregistrasi)
                ->where('produkfk',$produkDep->nilaifield )
                ->where("kdprofile", request()->session()->get('kdProfile'))
                ->get();
        $total = 0;
        foreach ($data as $d) {
            $total = $total + $d->hargasatuan;
        }
        return $total;
    }
    public function scopeTotalTagihan($q,$noregistrasi = null)
    {
        if (empty($noregistrasi)) {
            return 0;
        }
        $data = $q->where("statusenabled", true)
                ->where('noregistrasi', $noregistrasi)
                ->where("kdprofile", request()->session()->get('kdProfile'))
                ->get();
        $total = 0;
        foreach ($data as $d) {
            $total = $total + 
            ((((float)$d->hargasatuan - (float)$d->hargadiscount) 
            *(float)$d->jumlah) + (float)$d->jasa );
        }
        return $total;
    }

}
