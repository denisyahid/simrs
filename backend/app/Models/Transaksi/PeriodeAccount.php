<?php
namespace App\Models\Transaksi;

use App\Models\Transaksi\_BaseModel;
use App\Models\Master\ChartOfAccount;
use Exception;

class PeriodeAccount extends _BaseModel
{    
    protected $table ="periodeaccount_t";
    protected $fillable = [];
    public $timestamps = false;
    public $incrementing = false;
    protected $primaryKey = "norec";

    public function __construct(){$this->setTransformerPath('App\Transformers\Transaksi\PeriodeAccountTransformer');}

    public function periode_account_saldo(){
        return $this->hasMany('App\Transaksi\PeriodeAccountSaldo', 'kdperiodeaccount', 'kdperiodeaccount');
    }

    public  function ruangan(){
        return $this->belongsTo('App\Master\Ruangan', 'objectruanganfk');
    }

    public function jenis_account(){
        return $this->belongsTo('App\Master\JenisAccount', 'objectjenisaccountfk');
    }
}