<?php

namespace App\Models\Transaksi;
use App\Models\Transaksi\_BaseModel;

class PasienPerjanjian extends _BaseModel
{
    protected $table = "pasienperjanjian_t";
    protected $fillable = [];
    public $timestamps = false;
    public $incrementing = false;
    protected $primaryKey = "norec";
}
