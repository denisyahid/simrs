<?php
namespace App\Models\Transaksi;

use App\Models\Transaksi\_BaseModel;
use App\Models\Master\ChartOfAccount;
use Exception;

class PostingJurnalD extends _BaseModel
{
    protected $table ="postingjurnald_t";
    protected $fillable = [];
    public $timestamps = false;
    public $incrementing = false;
    protected $primaryKey = "norec";


}
