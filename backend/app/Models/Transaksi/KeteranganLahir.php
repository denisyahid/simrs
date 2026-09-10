<?php

namespace App\Models\Transaksi;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Transaksi\_BaseModel;

class KeteranganLahir extends _BaseModel
{
    use HasFactory;
    protected $table = "keteranganlahir_t";
    protected $fillable = [];
    public $timestamps = true;
    public $incrementing = false;
    protected $primaryKey = "norec";

    public function generateSKL()
    {
        $no = 1;
        $qLatest = $this->latest()->first();
        if (isset($qLatest)) {
            $arr = explode("/", $qLatest->noskl);
            $no = (int) $arr[0];
            $getDate = $arr[5];

            // Reset increment every new Year
            if ($getDate == date("Y")) {
                $no = $no + 1;
            } else {
                $no = 1;
            }
        }
        $number = (int)date('m');
        $map = array('M' => 1000, 'CM' => 900, 'D' => 500, 'CD' => 400, 'C' => 100, 'XC' => 90, 'L' => 50, 'XL' => 40, 'X' => 10, 'IX' => 9, 'V' => 5, 'IV' => 4, 'I' => 1);
        $returnValue = '';
        while ($number > 0) {
            foreach ($map as $roman => $int) {
                if($number >= $int) {
                    $number -= $int;
                    $returnValue .= $roman;
                    break;
                }
            }
        }

        $first = sprintf('%04d', $no);

        $roman = $returnValue;
        $middle = "/SKL/VK/INSIDAT.RSBM/$roman/"; //   Still dosnt know is this static or not
        $last = date("Y");

        return $first . $middle . $last;
    }

}