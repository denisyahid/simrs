<?php

namespace App\Models\Transaksi;

use App\Models\Master\Profile;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;

class StrukBuktiPengeluaran extends _BaseModel
{
    use HasFactory;

    protected $table = "strukbuktipengeluaran_t";
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
            ->select('id', 'asalanggaran');
    }
    public function scopeSearch(Builder $builder, string $search = null)
    {
        if (empty($search)) {
            return $builder;
        }

        return $builder->where(function (Builder $sql) use ($search) {
            $sql->where("asalanggaran", "like", "%{$search}%")
                ->orWhere("reportdisplay", "like", "%{$search}%");
        });
    }
    public function scopeTotalBayar($builder, string $noregistrasi = null)
    {
        if (empty($noregistrasi)) {
            return 0;
        }

        $data = $builder->where(function ( $sql) use ($noregistrasi) {
            $sql->where("statusenabled", true)
                ->where('noregistrasi', $noregistrasi)
                ->where("kdprofile", request()->session()->get('kdProfile'));
        })->get();
        $total = 0;
        foreach($data as $d){
            $total = $total + $d->totaldibayar;
        }
        return $total ;
    }
    public function scopeTotalPengembalian($builder, string $noregistrasi = null)
    {
        if (empty($noregistrasi)) {
            return 0;
        }
        $KT =  DB::table('kelompoktransaksi_m')
        ->where('kdprofile',request()->session()->get('kdProfile'))
        ->where('statusenabled',true)
        ->where('kelompoktransaksi','PENGEMBALIAN DEPOSIT PASIEN')
        ->first()
        ->id;
        $data = $builder->where(function ( $sql) use ($noregistrasi,$KT) {
            $sql->where("statusenabled", true)
                ->where('noregistrasi', $noregistrasi)
                ->where('objectkelompoktransaksifk','=', $KT)
                ->where("kdprofile", request()->session()->get('kdProfile'));
        })->get();
        $total = 0;
        foreach($data as $d){
            $total = $total + $d->totaldibayar;
        }
        return $total ;
    }
    
}
