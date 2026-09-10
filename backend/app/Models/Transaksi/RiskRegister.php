<?php

namespace App\Models\Transaksi;

class RiskRegister extends Transaksi
{
    protected $table = 'riskregister_t';
    protected $fillable = [];
    public $timestamps = false;
    public $incrementing = false;
    protected $primaryKey = "norec";
}