<?php

namespace App\Models\Transaksi;


class RiwayatPMKP extends _BaseModel
{
    protected $table = "riwayatpmkp_t";
    protected $fillable = [];
    public $timestamps = false;
    public $incrementing = false;
    protected $primaryKey = "norec";
}
