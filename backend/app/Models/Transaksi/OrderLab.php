<?php

namespace App\Models\Transaksi;

use App\Traits\Valet;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;

class OrderLab extends _BaseModel
{
    use HasFactory;
    protected $table = "order_lab";
    protected $fillable = [];
    public $timestamps = false;
    public $incrementing = false;
    // protected $primaryKey = "id";

}
