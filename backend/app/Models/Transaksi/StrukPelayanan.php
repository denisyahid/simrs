<?php

namespace App\Models\Transaksi;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;


class StrukPelayanan extends _BaseModel
{
    use HasFactory;
    protected $table = "strukpelayanan_t";
    protected $fillable = [];
    public $timestamps = true;
    public $incrementing = false;
    protected $primaryKey = "norec";

    public function getStatusVerifikasiPiutangAttribute(){
        if($this->noverifikasipiutang==null){
            return "Belum Verifikasi";
        }
        return "Verifikasi";
    }

    public function getIsVerifiedPiutangAttribute(){
        if($this->noverifikasipiutang==null){
            return false;
        }
        return true;
    }

    public function getStatusBayarAttribute(){
        if($this->nosbmlastfk==null){
            return "Belum Bayar";
        }
        return "Lunas";
    }

    public function getStatusBayarKeluarAttribute(){
        if($this->nosbklastfk==null){
            return "Belum Bayar";
        }
        return "Lunas";
    }

    public function getBiayaSebelumReturnAttribute(){
        if($this->norectriger!=null){
            $sp = StrukPelayanan::find($this->norectriger);
            return $sp->totalharusdibayar;
        }
        return 0;
    }

    public function getStatusCollectingPiutangAttribute(){
        if($this->nocollectingpiutang==null){
            return "Piutang";
        }
        return "Collecting";
    }

    public function getUmurPiutangAttribute(){
        $tahun=(int)date('Y', strtotime($this->tglstruk));
        $bulan=(int)date('m', strtotime($this->tglstruk));
        $tanggal=(int)date('d', strtotime($this->tglstruk));
        $selisih_bulan=0;
        $selisih_tahun=0;

        $selisih_tanggal = (int)date('d')-$tanggal;
        if($selisih_tanggal<0){
            $selisih_bulan--;
            $selisih_tanggal+= 30;
        }

        $selisih_bulan += (int)date('m')-$bulan;
        if($selisih_bulan<0){
            $selisih_tahun--;
            $selisih_bulan += 12;
        }


        $selisih_tahun += (int)date('Y') - $tahun;
        $result = "";
        if($selisih_tahun>0){
            $result = abs($selisih_tahun).' Tahun, ';
        }
        if($selisih_bulan>0){
            $result .= abs($selisih_bulan).' Bulan, ';
        }
        if($selisih_tanggal>0){
            $result .= abs($selisih_tanggal).' Hari. ';
        }

//      return abs($selisih_tahun).' Tahun, '.abs($selisih_bulan).' Bulan, '.abs($selisih_tanggal).' Hari.';
        return $result;
    }

//    public function getTotalBillingAttribute(){
//        $pelayananPasien=
//        return true;
//    }

//    public function __construct(){$this->setTransformerPath('App\Transformers\Transaksi\StrukPostingTransformer');}

    public function pasien_daftar(){
        return $this->belongsTo('App\Models\Transaksi\PasienDaftar','noregistrasifk');
    }

    public function struk_pelayanan_detail(){
        return $this->hasMany('App\Models\Transaksi\StrukPelayananDetail','nostrukfk');
    }

    public function pelayanan_pasien(){
        return $this->hasMany('App\Models\Transaksi\PelayananPasien', 'strukfk');
    }

    public function kelompok_transaksi(){
        return $this->belongsTo('App\Models\Master\KelompokTransaksi','objectkelompoktransaksifk');
    }

    public function pasien(){
        return $this->belongsTo('App\Models\Master\Pasien','nocmfk');
    }
    public function ruangan(){
        return $this->belongsTo('App\Models\Master\Ruangan','objectruanganfk');
    }
    public function scopeTotalKlaim($builder, string $noregistrasi = null)
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
            $total = $total+ (float) $d->totalprekanan;
        }
        return $total ;
    }
    public function scopeTotalVerif($builder, string $noregistrasi = null)
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
            $total = $total+ (float) $d->totalhargasatuan;
        }
        return $total ;
    }
    

}
