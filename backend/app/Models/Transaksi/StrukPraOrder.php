<?php

namespace App\Models\Transaksi;

use App\Models\Transaksi\_BaseModel;

class StrukPraOrder extends _BaseModel
{
    protected $table = "strukpraorder_t";
    protected $fillable = [];
    public $timestamps = true;
    public $incrementing = false;
    protected $primaryKey = "norec";
}