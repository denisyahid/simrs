<?php

namespace App\Models\Transaksi;
use App\Models\Transaksi\_BaseModel;
class NoSuratKeterangan extends _BaseModel
{
    protected $table ="nosuratketerangan_t";
    protected $fillable = [];
    public $timestamps = false;
    public $incrementing = false;
    protected $primaryKey = "norec";

}
