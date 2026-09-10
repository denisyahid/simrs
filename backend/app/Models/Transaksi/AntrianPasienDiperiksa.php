<?php

namespace App\Models\Transaksi;

use App\Models\Master\Profile;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class AntrianPasienDiperiksa extends _BaseModel
{
    use HasFactory;
    protected $table = "antrianpasiendiperiksa_t";
    protected $fillable = [];
    public $timestamps = true;
    public $incrementing = false;
    protected $primaryKey = "norec";
    public function scopeMine(Builder $builder, int $kdProfile = null)
    {
        if(!empty(request()->session()->get('kdProfile'))) {
            $kdProfile = request()->session()->get('kdProfile');
        }else{
            $kdProfile = Profile::where('statusenabled',true)->first()->id;
        }
        return $builder->where("kdprofile", $kdProfile)
        ->where('statusenabled',true)
        ->select('id','noantrian','tglregistrasi');
    }
    public function scopeSearch(Builder $builder, string $search = null)
    {
        if (empty($search)) {
            return $builder;
        }

        return $builder->where(function (Builder $sql) use ($search) {
            $sql->where("noregistrasi", "like", "%{$search}%")
                ->orWhere("reportdisplay", "like", "%{$search}%");
        });
    }
    public function pelayanan_pasien(){
        return $this->belongsTo('App\Models\Transaksi\PelayananPasien',  'noregistrasifk','norec');
    }

    public  function ruangan(){
        return $this->belongsTo('App\Models\Master\Ruangan', 'objectruanganfk', 'id');
    }
//
//    public function kelas(){
//        return $this->belongsTo('App\Master\Kelas', 'objectkelasfk');
//    }
//
//    public function kelompok_pasien(){
//        return $this->belongsTo('App\Master\KelompokPasien', 'objectkelompokpasienlastfk');
//    }
    public function pasien_daftar(){
        return $this->belongsTo('App\Models\Transaksi\PasienDaftar', 'noregistrasifk');
    }
    public function kamar(){
        return $this->belongsTo('App\Models\Transaksi\Kamar', 'objectkamarfk');
    }
    public function scopeListRuangan($q,$noregistrasi = null)
    {
        if (empty($noregistrasi)) {
            return [];
        }
        $data = DB::table('antrianpasiendiperiksa_t as pd')
        ->join('ruangan_m as ru','ru.id','=','pd.objectruanganfk')
        ->join('departemen_m as dp','dp.id','=','ru.objectdepartemenfk')
        ->select( 'ru.id','ru.namaruangan',
            'pd.norec as norec_apd',
            'dp.namadepartemen')
        ->where("pd.noregistrasi", $noregistrasi)
        ->where("pd.kdprofile", request()->session()->get('kdProfile'))
        ->where("pd.statusenabled", true)
        ->get();
       
        return $data;
    }
}

