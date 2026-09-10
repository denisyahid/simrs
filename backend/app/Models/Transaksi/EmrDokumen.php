<?php
namespace App\Models\Transaksi;

class EmrDokumen extends _BaseModel
{
    protected $table ="emrdokumen_t";
    protected $primaryKey = 'norec';
    protected $fillable = [];
    public $timestamps = false;
    public $incrementing = false;
}