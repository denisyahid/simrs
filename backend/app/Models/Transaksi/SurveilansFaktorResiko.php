<?php

namespace App\Models\Transaksi;


class SurveilansFaktorResiko extends _BaseModel
{
    protected $table = 'surveilansfaktorresiko_t';
    protected $fillable = [];
    public $timestamps = false;
    public $incrementing = false;
    protected $primaryKey = "norec";
}
