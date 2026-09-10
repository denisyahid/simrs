<?php
namespace App\Models\Transaksi;

class EvaluasiPasien extends _BaseModel
{
    protected $table = "evaluasi_pasien_t";
    protected $primaryKey = 'norec';
    protected $fillable = [];
    protected $casts = [
        "pasienfk" => "array"
    ];

    public $incrementing = false;
}
