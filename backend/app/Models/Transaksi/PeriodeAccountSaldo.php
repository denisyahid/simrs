<?php
namespace App\Models\Transaksi;

use App\Models\Transaksi\_BaseModel;
use App\Models\Master\ChartOfAccount;
use Exception;

class PeriodeAccountSaldo extends _BaseModel
{
    protected $table ="periodeaccountsaldo_t";
    protected $fillable = [];
    public $timestamps = false;
    protected $primaryKey = "norec";

    public function __construct(){$this->setTransformerPath('App\Transformers\Transaksi\PeriodeAccountSaldoTransformer');}

    public function periode_account(){
        return $this->belongsTo('App\Transaksi\PeriodeAccount', 'kdperiodeaccount', 'kdperiodeaccount');
    }
}