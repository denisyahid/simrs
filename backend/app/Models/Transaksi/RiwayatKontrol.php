<?php

namespace App\Models\Transaksi;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Transaksi\_BaseModel;

class RiwayatKontrol extends _BaseModel
{
    use HasFactory;
    protected $table = "riwayatkontrol_t";
    protected $fillable = [];
    public $timestamps = true;
    public $incrementing = false;
    protected $primaryKey = "norec";

}