<?php

namespace App\Models\Transaksi;

use App\Models\Transaksi\_BaseModel;

class PelayananPasienRetur extends _BaseModel
{
    protected $table ="pelayananpasienretur_t";
    protected $fillable = [];
    public $timestamps = true;
    public $incrementing = false;
    protected $primaryKey = "norec";

}