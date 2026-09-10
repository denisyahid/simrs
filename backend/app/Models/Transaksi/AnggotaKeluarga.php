<?php

namespace App\Models\Transaksi;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnggotaKeluarga extends _BaseModel
{
    use HasFactory;

    protected $table = "anggotakeluarga_t";
    protected $fillable = [];
    public $incrementing = false;
    protected $primaryKey = "norec";
}
