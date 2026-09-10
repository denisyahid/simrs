<?php

namespace App\Models\Transaksi;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BundleKlaim extends Model
{
    use HasFactory;
    protected $table ="bundleklaim_t";
    protected $fillable = [
        'norec',
        'noregistrasi',
        'filename',
        'data',
        'urut'
    ];
    public $timestamps = false;
    public $incrementing = false;
    protected $primaryKey = "norec";
}
