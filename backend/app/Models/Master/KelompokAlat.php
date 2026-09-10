<?php

namespace App\Models\Master;

use App\Models\Transaksi\_BaseModel;
use App\Models\Transaksi\Transaksi;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KelompokAlat extends _BaseModel
{
    protected $table = 'kelompokalat_m';
    protected $fillable = [];
    public $timestamps = false;
    protected $primaryKey = 'id';
}
