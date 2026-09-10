<?php

namespace App\Models\Transaksi;


class Surveilans extends _BaseModel
{
    protected $table = 'surveilans_t';
    protected $fillable = [];
    public $timestamps = false;
    public $incrementing = false;
    protected $primaryKey = "norec";
}
