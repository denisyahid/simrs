<?php

namespace App\Models\Transaksi;


class SurveilansAntibiotik extends _BaseModel
{
    protected $table = 'surveilansantibiotik_t';
    protected $fillable = [];
    public $timestamps = false;
    public $incrementing = false;
    protected $primaryKey = "norec";
}
