<?php
namespace App\Models\Transaksi;

use App\Models\Transaksi\_BaseModel;
use App\Models\Master\ChartOfAccount;
use Exception;

class PostingJurnalTransaksiD extends _BaseModel
{
    protected $table ="postingjurnaltransaksid_t";
    protected $fillable = [];
    public $timestamps = false;
    public $incrementing = false;
    protected $primaryKey = "norec";
    protected $balance = null;

    public function __construct(){$this->setTransformerPath('App\Transformers\Transaksi\PostingJurnalTransaksiDTransformer');}

    public function setAccountIdByNoAccountAttribute($noAccont)
    {
        $account = ChartOfAccount::where('noaccount', $noAccont)->first();
        if(!$account){
            throw new Exception('gagal menyimpan data no Account: ' . $noAccont. ' Tidak terdaftar');
        }

        $this->attributes['objectaccountfk'] = strtolower($account->id);
    }

    public function setBalanceAttribute($balance){
        $this->balance = $balance;
    }

    public function setSaldoAttribute($saldo){
        if($this->balance=='D'){
            $this->attributes['hargasatuand'] = $saldo;
            $this->attributes['hargasatuank'] = 0;
        }else{
            $this->attributes['hargasatuand'] = 0;
            $this->attributes['hargasatuank'] = $saldo;
        }

    }

    public function  posting_jurnal_transaksi(){
        return $this->hasMany('App\Transaksi\PostingJurnalTransaksi', 'norecrelated', 'norec');
    }

    public function account(){
        return $this->belongsTo('App\Master\ChartOfAccount', 'objectaccountfk');
    }



}
