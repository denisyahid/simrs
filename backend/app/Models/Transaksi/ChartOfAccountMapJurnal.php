<?php

namespace App\Models\Transaksi;

use App\Models\Transaksi\PeriodeAccount;
use App\Models\Transaksi\PeriodeAccountSaldo;
use App\Models\Transaksi\PostingJurnalTransaksiD;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ChartOfAccountMapJurnal extends  _BaseModel
{
    protected $table ="chartofaccountmapjurnal_t";
    protected $fillable = [];
    public $timestamps = true;
    public $incrementing = false;
    protected $primaryKey = "norec";

}
