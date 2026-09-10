<?php

namespace App\Models\Transaksi;

use App\Models\Transaksi\Transaksi;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;

class IHS_Transaction extends Transaksi
{
    protected $table = "ihs_transaction";
    protected $fillable = [];
    public $timestamps = true;
    public $incrementing = false;
    protected $primaryKey = "norec";
}