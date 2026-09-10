<?php

namespace App\Models\Transaksi;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KepatuhanHandHygiene extends _BaseModel
{
    protected $table = "kepatuhanhandhygiene_t";
    protected $primaryKey = 'norec';
    protected $fillable = [];
    public $timestamps = false;
}
