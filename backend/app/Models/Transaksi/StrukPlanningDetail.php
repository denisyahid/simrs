<?php

namespace App\Models\Transaksi;

use App\Models\Transaksi\_BaseModel;

class StrukPlanningDetail extends _BaseModel
{
    protected $table = "strukplanningdetail_t";
    protected $fillable = [];
    public $timestamps = true;
    public $incrementing = false;
    protected $primaryKey = "norec";
}