<?php
namespace App\Models\Transaksi;

use App\Models\Transaksi\_BaseModel;

class RiwayatRealisasi extends _BaseModel
{
    protected $table = "riwayatrealisasi_t";
    protected $fillable = [];
    public $timestamps = true;
    public $incrementing = false;
    protected $primaryKey = "norec";
}