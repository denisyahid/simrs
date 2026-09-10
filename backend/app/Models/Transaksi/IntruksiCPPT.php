<?php

namespace App\Models\Transaksi;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IntruksiCPPT extends _BaseModel
{
    use HasFactory;
    protected $table ="intruksi_cppt_t";
    protected $fillable = [];
    public $incrementing = false;
    protected $primaryKey = "norec";
    
}
