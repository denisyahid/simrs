<?php
namespace App\Models\Transaksi;

class EMR extends _BaseModel
{
    protected $table ="emr_t";
    protected $primaryKey = 'id';
    protected $fillable = [];
    public $timestamps = false;
    public $incrementing = false;
}