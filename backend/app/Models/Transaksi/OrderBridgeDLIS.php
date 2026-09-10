<?php


namespace App\Models\Transaksi;


class OrderBridgeDLIS extends _BaseModel
{
    protected $table = "order_bridge_item";
    protected $fillable = [];
    public $timestamps = false;
    public $incrementing = false;
    protected $primaryKey = "norec";
}
