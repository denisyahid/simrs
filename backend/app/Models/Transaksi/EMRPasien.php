<?php
namespace App\Models\Transaksi;

class EMRPasien extends _BaseModel
{
    protected $table ="emrpasien_t";
    protected $primaryKey = 'norec';
    protected $fillable = [];
    public $timestamps = false;
    public $incrementing = false;
}