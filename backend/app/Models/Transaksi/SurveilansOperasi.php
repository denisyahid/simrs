<?php

namespace App\Models\Transaksi;



class SurveilansOperasi extends _BaseModel
{
    protected $table = 'surveilansoperasi_t';
    protected $fillable = [];
    public $timestamps = false;
    public $incrementing = false;
    protected $primaryKey = "norec";
}
