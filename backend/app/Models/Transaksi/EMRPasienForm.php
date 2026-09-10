<?php
namespace App\Models\Transaksi;

class EMRPasienForm extends _BaseModel
{
    protected $table ="emrpasienform_t";
    protected $primaryKey = 'norec';
    protected $fillable = [];
    public $timestamps = false;
    public $incrementing = false;
}