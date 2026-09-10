<?php

namespace App\Models\Transaksi;
use App\Models\Transaksi\_BaseModel;
class SuratKeterangan extends _BaseModel
{
    protected $table ="suratketerangan_t";
    protected $fillable = [];
    public $timestamps = false;
    public $incrementing = false;
    protected $primaryKey = "norec";

}
