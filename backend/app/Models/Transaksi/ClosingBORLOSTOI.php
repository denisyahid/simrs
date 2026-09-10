<?php

namespace App\Models\Transaksi;

use App\Models\Transaksi\PeriodeAccount;
use App\Models\Transaksi\PeriodeAccountSaldo;
use App\Models\Transaksi\PostingJurnalTransaksiD;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ClosingBORLOSTOI extends  _BaseModel
{
    protected $table ="closingborlostoi_t";
    protected $fillable = [];
    public $timestamps = false;
    public $incrementing = false;
    protected $primaryKey = "norec";

}
