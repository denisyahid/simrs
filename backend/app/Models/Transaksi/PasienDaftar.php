<?php

namespace App\Models\Transaksi;

use App\Models\Master\Profile;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class PasienDaftar extends _BaseModel
{
    use HasFactory;
    protected $table = "pasiendaftar_t";
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
        ->select('id','noregistrasi','tglregistrasi');
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
    public function periode_account_saldo(){
        return $this->hasMany('App\Models\Transaksi\PeriodeAccountSaldo', 'kdperiodeaccount', 'kdperiodeaccount');
    }

    public  function ruangan(){
        return $this->belongsTo('App\Models\Master\Ruangan', 'objectruanganlastfk');
    }

    public function kelas(){
        return $this->belongsTo('App\Models\Master\Kelas', 'objectkelasfk');
    }

    public function pemakaian_asuransi(){
        return $this->hasManyThrough('App\Models\Transaksi\PemakaianAsuransi', 'App\Models\Transaksi\AntrianPasienDiperiksa', 'noregistrasifk', 'noregistrasifk', 'norec');
    }

    public function pemakaian_asuransi2(){
        return $this->hasMany('App\Models\Transaksi\PemakaianAsuransi', 'noregistrasifk');
    }

    public function kelompok_pasien(){
        return $this->belongsTo('App\Models\Master\KelompokPasien', 'objectkelompokpasienlastfk');
    }

    public function rekanan(){
        return $this->belongsTo('App\Models\Master\Rekanan', 'objectrekananfk');
    }

    public function dokter(){
        return $this->belongsTo('App\Models\Master\Pegawai', 'objectpegawaifk');
    }

    public function antrian_pasien_diperiksa(){
        return $this->hasMany('App\Models\Transaksi\AntrianPasienDiperiksa', 'noregistrasifk', 'norec');
    }

    public function pelayanan_pasien()
    {
        return $this->hasManyThrough('App\Models\Transaksi\PelayananPasien', 'App\Models\Transaksi\AntrianPasienDiperiksa', 'noregistrasifk', 'noregistrasifk', 'norec');
    }

    public function setDepositIdAttribute($produkDepositId){
        $this->depositId = $produkDepositId;
    }

    public function setSewaAlatIdAttribute($produkSewaAlatId){
        $this->SewaAlatId = $produkSewaAlatId;
    }

    public function list_deposit(){
        return $this->pelayanan_pasien()->where('produkfk', $this->depositId);
    }

    public function list_sewaalat(){
        return $this->pelayanan_pasien()->where('produkfk', $this->SewaAlatId);
    }

    public function pelayanan_pasien_detail()
    {
        return $this->hasManyThrough('App\Models\Transaksi\PelayananPasienDetail', 'App\Models\Transaksi\AntrianPasienDiperiksa', 'noregistrasifk', 'noregistrasifk', 'norec');
    }

    public  function pasien(){
        return $this->belongsTo('App\Models\Master\Pasien', 'nocmfk');
    }

    public function last_ruangan(){
        return $this->belongsTo('App\Models\Master\Ruangan', 'objectruanganlastfk');
    }

    public function  getIsVerifiedAttribute(){
        $pelayananPasien= $this->pelayanan_pasien()->whereNotNull('strukfk')->where('produkfk', '<>', $this->depositId)->get();
        if(count($pelayananPasien)>0){
            return true;
        }else{
            return false;
        }
    }

    protected function isBayar(){
        $pelayananPasien= $this->pelayanan_pasien()->whereNotNull('strukfk')->where('produkfk', '<>', $this->depositId)->get();
        foreach ($pelayananPasien as $pp){
            if($pp->struk_pelayanan) {
                if($pp->struk_pelayanan->nosbmlastfk != null){
                    return true;
                }

            }
        }
        return false;
    }

    public function getIsBayarAttribute(){
        return $this->isBayar();
    }

    public function getStatusBayarAttribute(){
        if($this->isBayar()){
            return 'Lunas';
        }else{
            return 'Belum Bayar';
        }
    }
    public function scopeDetailPasien($q,$noregistrasi = null)
    {
        if (empty($noregistrasi)) {
            return 0;
        }
        $data =  DB::table('pasiendaftar_t as pd')
        ->join('pasien_m as ps','ps.id','=','pd.nocmfk')
        ->join('ruangan_m as ru','ru.id','=','pd.objectruanganlastfk')
        ->select('pd.noregistrasi','pd.tglregistrasi',
        'pd.tglpulang','ru.namaruangan as ruanganlast',
        'ps.nocm','ps.namapasien')
        ->where("pd.kdprofile", request()->session()->get('kdProfile'))
        ->where("pd.statusenabled", true)
        ->where("pd.noregistrasi", $noregistrasi)
        ->first();
       
        return $data;
    }
}
