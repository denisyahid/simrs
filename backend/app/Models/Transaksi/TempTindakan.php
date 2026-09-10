<?php

namespace App\Models\Transaksi;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TempTindakan extends _BaseModel
{
    use HasFactory;
    protected $table ="temp_tindakan_t";
    protected $fillable = [
        "norec",
        "norec_pd",
        "data",
        "tanggal",
        "issaved",
        "nocmfk"
    ];
    public $incrementing = false;
    protected $primaryKey = "norec";

    protected $casts = [
        "data" => "array"
    ];
    
}
