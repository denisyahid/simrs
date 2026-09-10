<?php

namespace App\Models\Transaksi;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class LabBuktiTransaksi extends  _BaseModel
{
    use HasFactory;

    protected $table = "labbukti_t";
    protected $fillable = [];
    public $timestamps = false;
    public $incrementing = false;
    protected $primaryKey = "norec";
}
