<?php

namespace App\Models\Transaksi;


class RisOrder extends _BaseModel
{
    protected $table ="ris_order";
    protected $primaryKey = 'order_key';
    protected $fillable = [];
    public $timestamps = false;

}
