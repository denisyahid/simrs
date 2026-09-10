<?php

namespace App\Models\Transaksi;


class SkriningFarmasi extends _BaseModel
{
    protected $table = "skriningfarmasi_t";
    protected $fillable = [];
    public $timestamps = false;
    public $incrementing = false;
    protected $primaryKey = "norec";
}