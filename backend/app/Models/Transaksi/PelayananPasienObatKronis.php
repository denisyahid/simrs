<?php

namespace App\Models\Transaksi;


class PelayananPasienObatKronis extends _BaseModel
{
    protected $table = "pelayananpasienobatkronis_t";
    protected $fillable = [];
    public $timestamps = false;
    public $incrementing = false;
    protected $primaryKey = "norec";
}