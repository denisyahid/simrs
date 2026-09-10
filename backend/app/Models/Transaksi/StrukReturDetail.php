<?php

namespace App\Models\Transaksi;

use App\Models\Transaksi\_BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;

class StrukReturDetail extends _BaseModel
{
    use HasFactory;
    protected $table = "strukreturdetail_t";
    protected $fillable = [];
    public $timestamps = true;
    public $incrementing = false;
    protected $primaryKey = "norec";
}
