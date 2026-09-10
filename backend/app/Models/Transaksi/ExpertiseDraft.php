<?php


namespace App\Models\Transaksi;


class ExpertiseDraft extends _BaseModel
{
    protected $table = "expertise_draft";
    protected $fillable = [];
    public $timestamps = true;
    public $incrementing = false;
    protected $primaryKey = "id";
}
