<?php

namespace App\Models\Transaksi;

use App\Models\Transaksi\_BaseModel;

class StrukPraOrderDetail extends _BaseModel
{
    protected $table = "strukpraorderdetail_t";
    protected $fillable = [];
    public $timestamps = true;
    public $incrementing = false;
    protected $primaryKey = "norec";
}