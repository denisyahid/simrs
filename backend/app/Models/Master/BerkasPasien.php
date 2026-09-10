<?php

namespace App\Models\Master;

use App\Models\Transaksi\_BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BerkasPasien extends _BaseModel
{
    use HasFactory;
    protected $table = "berkaspasien_m";
    protected $fillable = [];
    public $timestamps = false;
    public $incrementing = true;
    protected $keyType = 'integer';
    protected $primaryKey = "id";
}
