<?php

namespace App\Models\Transaksi;

use App\Models\Transaksi\_BaseModel;

class RealisasiDetail extends _BaseModel
{
    protected $table = "realisasidetail_t";
    protected $fillable = [];
    public $timestamps = false;
    public $incrementing = false;
    protected $primaryKey = "norec";

}
