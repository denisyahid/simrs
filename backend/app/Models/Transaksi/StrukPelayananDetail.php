<?php

namespace App\Models\Transaksi;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;


class StrukPelayananDetail extends _BaseModel
{
    protected $table ="strukpelayanandetail_t";
    protected $fillable = [];
    public $timestamps = true;
    public $incrementing = false;
    protected $primaryKey = "norec";

    public function produk(){
        return $this->belongsTo('App\Models\Master\Produk', 'objectprodukfk');
    }

    public function getPelayananSebelumReturnAttribute(){
        $spd = StrukPelayananDetail::where('norec',$this->norectriger)->first();
        return $spd;
    }

    public function ruangan(){
        return $this->belongsTo('App\Master\Ruangan','objectruanganfk');
    }

}
