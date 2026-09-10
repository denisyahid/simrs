<?php

namespace App\Models\Transaksi;

use App\Models\Transaksi\_BaseModel;

class StrukRealisasi extends _BaseModel
{
    protected $table = "strukrealisasi_t";
    protected $fillable = [];
    public $timestamps = true;
    public $incrementing = false;
    protected $primaryKey = "norec";

    public function order_pelayanan(){
        return $this->hasMany('App\Transaksi\OrderPelayanan', 'noorderfk');
    }
}