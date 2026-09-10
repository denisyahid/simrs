<?php

namespace App\Models\Transaksi;

use App\Models\Standar\LoginUser;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StrukKonfirmasi extends _BaseModel
{
    use HasFactory;
    protected $table = "strukkonfirmasi_t";
    protected $fillable = [];
    public $timestamps = false;
    public $incrementing = false;
    protected $primaryKey = "norec";

    public function login_user()
    {
        return $this->belongsTo(LoginUser::class, 'kdhistorylogins');
    }
}
