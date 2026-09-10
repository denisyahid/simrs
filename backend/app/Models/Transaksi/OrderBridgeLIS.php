<?php


namespace App\Models\Transaksi;


class OrderBridgeLIS extends _BaseModel
{
    protected $table = "order_bridge";
    protected $fillable = [];
    public $timestamps = false;
    public $incrementing = false;
    protected $primaryKey = "norec";
}
