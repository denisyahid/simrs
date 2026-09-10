<?php

namespace App\Models\Transaksi;

use App\Models\Master\Profile;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;

class StrukBuktiPengeluaranCaraBayar extends _BaseModel
{
    use HasFactory;

    protected $table = "strukbuktipengeluarancarabayar_t";
    protected $fillable = [];
    public $timestamps = true;
    public $incrementing = false;
    protected $primaryKey = "norec";

    public function scopeMine(Builder $builder, int $kdProfile = null)
    {
        if (!empty(request()->session()->get('kdProfile'))) {
            $kdProfile = request()->session()->get('kdProfile');
        } else {
            $kdProfile = Profile::where('statusenabled', true)->first()->id;
        }
        return $builder->where("kdprofile", $kdProfile)
            ->where('statusenabled', true)
            ->select('norec', 'objectcarabayarfk');
    }
   
    public function scopeTotalBayar($builder, string $norecSBM = null)
    {
        if (empty($norecSBM)) {
            return 0;
        }

        $data = $builder->where(function ( $sql) use ($norecSBM) {
            $sql->where("statusenabled", true)
                ->where('nosbkfk', $norecSBM)
                ->where("kdprofile", request()->session()->get('kdProfile'));
        });
        $total = 0;
        foreach($data as $d){
            $total = $total+ $d->totaldibayar;
        }
        return $total ;
    }
}
