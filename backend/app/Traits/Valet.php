<?php

namespace App\Traits;

use App\Models\Master\Pasien;
use App\Models\Transaksi\KartuStok;
use App\Models\Transaksi\KetersediaanTempatTidur;
use App\Models\Transaksi\LoggingUser;
use App\Models\Transaksi\PasienDaftar;
use App\Models\Transaksi\PelayananPasien;
use App\Models\Transaksi\SeqMaster;
use App\Models\Transaksi\SeqNumber;
use App\Models\Transaksi\StrukBuktiPenerimaan;
use Carbon\Carbon;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Uuid;
use GoogleTranslate;

trait Valet
{

    protected function generateCodeBySeqTable($objectModel, $atrribute, $length = 8, $prefix = '', $idProfile)
    {
        DB::beginTransaction();
        try {
            $result = SeqNumber::where('seqnumber', 'LIKE', $prefix . '%')
                ->where('seqname', $atrribute)
                ->where('kdprofile', $idProfile)
                ->max('seqnumber');
            $prefixLen = strlen($prefix);
            $subPrefix = substr(trim($result), $prefixLen);
            $SN = $prefix . (str_pad((int) $subPrefix + 1, $length - $prefixLen, "0", STR_PAD_LEFT));

            $newSN = new SeqNumber();
            $newSN->kdprofile = $idProfile;
            $newSN->seqnumber = $SN;
            $newSN->tgljamseq = date('Y-m-d H:i:s');;
            $newSN->seqname = $atrribute;
            $newSN->save();

            $transStatus = 'true';
        } catch (\Exception $e) {
            $transStatus = 'false';
        }

        if ($transStatus == 'true') {
            DB::commit();
            return $SN;
        } else {
            DB::rollBack();
            return '';
        }

        return $this->setStatusCode($result['status'])->respond($result);
    }
    protected function generateCode($objectModel, $atrribute, $length = 8, $prefix = '', $idProfile)
    {
        $result = $objectModel->where($atrribute, 'LIKE', $prefix . '%')->where('kdprofile', $idProfile)->max($atrribute);
        $prefixLen = strlen($prefix);
        $subPrefix = substr(trim($result), $prefixLen);
        return $prefix . (str_pad((int) $subPrefix + 1, $length - $prefixLen, "0", STR_PAD_LEFT));
    }

    protected function generateCodeDibelakang($objectModel, $atrribute, $length = 8, $prefix = '')
    {
        $result = $objectModel->where($atrribute, 'LIKE', '%' . $prefix)->max($atrribute);
        $prefixLen = strlen($prefix);
        $subPrefix = substr(trim($result), $prefixLen);
        return (str_pad((int) $subPrefix + 1, $length - $prefixLen, "0", STR_PAD_LEFT)) . $prefix;
    }

    protected function generateCode2($objectModel, $atrribute, $length = 0, $prefix = '')
    {
        $result = $objectModel->where($atrribute, 'LIKE', $prefix . '%')->max($atrribute);
        $prefixLen = strlen($prefix);
        $subPrefix = substr(trim($result), $prefixLen);
        return $prefix . (str_pad((int) $subPrefix + 1, $length - $prefixLen, "0", STR_PAD_LEFT));
    }

    protected function getCountArray($objectArr)
    {
        $counting = 0;
        foreach ($objectArr as $hint) {
            $counting = $counting + 1;
        }
        return $counting;
    }

    protected function getSequence($name = 'hibernate_sequence')
    {
        $result = null;
        if (DB::connection()->getName() == 'pgsql') {
            $next_id = DB::select("select nextval('" . $name . "')");
            $result = $next_id['0']->nextval;
        }
        return $result;
    }

    protected function getDateTime()
    {
        return Carbon::now();
    }

    protected function terbilang($number)
    {
        if ($number < 0) {
            return "";
        }
        $x = abs($number);
        $angka = array(
            "",
            "satu",
            "dua",
            "tiga",
            "empat",
            "lima",
            "enam",
            "tujuh",
            "delapan",
            "sembilan",
            "sepuluh",
            "sebelas"
        );
        $temp = "";
        if ($number < 12) {
            $temp = " " . $angka[$number];
        } else if ($number < 20) {
            $temp = $this->terbilang($number - 10) . " belas";
        } else if ($number < 100) {
            $temp = $this->terbilang($number / 10) . " puluh" . $this->terbilang($number % 10);
        } else if ($number < 200) {
            $temp = " seratus" . $this->terbilang($number - 100);
        } else if ($number < 1000) {
            $temp = $this->terbilang($number / 100) . " ratus" . $this->terbilang($number % 100);
        } else if ($number < 2000) {
            $temp = " seribu" . $this->terbilang($number - 1000);
        } else if ($number < 1000000) {
            $temp = $this->terbilang($number / 1000) . " ribu" . $this->terbilang($number % 1000);
        } else if ($number < 1000000000) {
            $temp = $this->terbilang($number / 1000000) . " juta" . $this->terbilang($number % 1000000);
        } else if ($number < 1000000000000) {
            $temp = $this->terbilang($number / 1000000000) . " milyar" . $this->terbilang(fmod($number, 1000000000));
        } else if ($number < 1000000000000000) {
            $temp = $this->terbilang($number / 1000000000000) . " trilyun" . $this->terbilang(fmod($number, 1000000000000));
        }
        return strtoupper($temp);
    }

    protected function makeTerbilang($number, $prefix = ' rupiah', $suffix = '')
    {
        if ($number < 0) {
            $hasil = "negatif " . trim($this->terbilang($number));
        } else {
            $hasil = trim($this->terbilang($number));
        }
        return $suffix . $hasil . $prefix;
    }

    public function getMoneyFormatString($number)
    {
        return number_format($number, 2, ",", ".");
    }

    public function getQtyFormatString($number)
    {
        return str_replace(',00', '', number_format($number, 2, ",", "."));
    }

    public function getDateReport($objectCarbonDate)
    {
        $tahun = $objectCarbonDate->year;
        $bulan = $objectCarbonDate->month;
        $tanggal = $objectCarbonDate->day;
        $labelBulan = array('', 'Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des');
        return $tanggal . " " . $labelBulan[$bulan] . " " . $tahun;
    }

    public function getDateTimeReport($objectCarbonDate)
    {
        $dateString = $this->getDateReport($objectCarbonDate);
        return $dateString . " " . $objectCarbonDate->hour . ":" . $objectCarbonDate->minute . ":" . $objectCarbonDate->second;
    }

    public function getBiayaMaterai($total)
    {
        $biayaMaterai = 0;
        if ($total > 1000000.99) {
            $biayaMaterai = 6000;
        } elseif ($total > 500000.99) {
            $biayaMaterai = 3000;
        }
        return $biayaMaterai;
    }

    public function hitungUmur($params)
    {
        $tahun = (int) date('Y', strtotime($params));
        $bulan = (int) date('m', strtotime($params));
        $tanggal = (int) date('d', strtotime($params));
        $selisih_bulan = 0;
        $selisih_tahun = 0;

        $selisih_tanggal = (int) date('d') - $tanggal;
        if ($selisih_tanggal < 0) {
            $selisih_bulan--;
            $selisih_tanggal += 30;
        }

        $selisih_bulan += (int) date('m') - $bulan;
        if ($selisih_bulan < 0) {
            $selisih_tahun--;
            $selisih_bulan += 12;
        }


        $selisih_tahun += (int) date('Y') - $tahun;
        $result = "";
        if ($selisih_tahun > 0) {
            $result = abs($selisih_tahun) . ' Tahun, ';
        }
        if ($selisih_bulan > 0) {
            $result .= abs($selisih_bulan) . ' Bulan, ';
        }
        if ($selisih_tanggal > 0) {
            $result .= abs($selisih_tanggal) . ' Hari. ';
        }

        return $result;
    }

    protected function subDateTime($string)
    {
        return substr($string, 0, 19);
    }

    protected function isPasienRawatInap($pasienDaftar)
    {
        if ($pasienDaftar->objectruanganlastfk != null) {
            if ((int) $pasienDaftar->ruangan->objectdepartemenfk == 16) {
                return true;
            }
        }
        return false;
    }
    protected function isPasienRawatInap2($pasienDaftar)
    {
        if ($pasienDaftar->objectruanganlastfk != null) {
            if ((int) $pasienDaftar->objectdepartemenfk == 16) {
                return true;
            }
        }
        return false;
    }

    protected function KonDecRomawi($angka)
    {
        $hsl = "";
        if ($angka == 1) {
            $hsl = 'I';
        };
        if ($angka == 2) {
            $hsl = 'II';
        };
        if ($angka == 3) {
            $hsl = 'III';
        };
        if ($angka == 4) {
            $hsl = 'IV';
        };
        if ($angka == 5) {
            $hsl = 'V';
        };
        if ($angka == 6) {
            $hsl = 'VI';
        };
        if ($angka == 7) {
            $hsl = 'VII';
        };
        if ($angka == 8) {
            $hsl = 'VIII';
        };
        if ($angka == 9) {
            $hsl = 'IX';
        };
        if ($angka == 10) {
            $hsl = 'X';
        };
        if ($angka == 11) {
            $hsl = 'XI';
        };
        if ($angka == 12) {
            $hsl = 'XII';
        };
        return ($hsl);
    }

    protected function genCode2($objectModel, $atrribute, $length = 4, $prefix = '')
    {

        $result = $objectModel->where($atrribute, 'LIKE', '%' . '/RSM/' . '%')->max($atrribute);
        $bln2 = Carbon::now()->format('Y/m');
        $a = substr(trim($result), 0, 7);

        if ($a != $bln2) {
            $subPrefix = '000';
        } else {
            $subPrefix = substr(trim($result), 8, 11);
        }
        $prefixLen = strlen($prefix);


        return $prefix . (str_pad((int) $subPrefix + 1, $length - $prefixLen, "0", STR_PAD_LEFT));
    }


    public function getAge($tgllahir, $now)
    {
        $datetime = new \DateTime(date($tgllahir));
        return $datetime->diff(new \DateTime($now))
            ->format('%ythn %mbln %dhr');
    }


    public static function getDateIndo($date2)
    { // fungsi atau method untuk mengubah tanggal ke format indonesia
        // variabel BulanIndo merupakan variabel array yang menyimpan nama-nama bulan
        $BulanIndo2 = array(
            "Januari",
            "Februari",
            "Maret",
            "April",
            "Mei",
            "Juni",
            "Juli",
            "Agustus",
            "September",
            "Oktober",
            "November",
            "Desember"
        );

        $tahun2 = substr($date2, 0, 4); // memisahkan format tahun menggunakan substring
        $bulan2 = substr($date2, 5, 2); // memisahkan format bulan menggunakan substring
        $tgl2 = substr($date2, 8, 2); // memisahkan format tanggal menggunakan substring

        $result = $tgl2 . " " . $BulanIndo2[(int) $bulan2 - 1] . " " . $tahun2;
        return ($result);
    }
    protected function sendBridgingCurl($headers, $dataJsonSend = null, $url, $method, $tipe = null)
    {
        $curl = curl_init();
        if ($dataJsonSend == null) {
            curl_setopt_array($curl, array(
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 60,
                CURLOPT_SSL_VERIFYHOST => 0,
                CURLOPT_SSL_VERIFYPEER => 0,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => $method,
                CURLOPT_HTTPHEADER => $headers
            ));
        } else {
            curl_setopt_array($curl, array(
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 60,
                CURLOPT_SSL_VERIFYHOST => 0,
                CURLOPT_SSL_VERIFYPEER => 0,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => $method,
                CURLOPT_POSTFIELDS => $dataJsonSend,
                CURLOPT_HTTPHEADER => $headers
            ));
        }

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            // return $this->setStatusCode(500)->respond([], $err);
            $result = "Terjadi Kesalahan #:" . $err;
        } else {
            //            dd($tipe);
            if ($tipe != null) {
                //                $json = preg_replace("!\t!", "", $response);
                //                $response2 = preg_replace("/(<\/?)(\w+):([^>]*>)/", "$1$2$3", $response);
                //                $xml = new \SimpleXMLElement($response2);
                //                $body = $xml->xpath('//SBody')[0];
                //                $result = json_decode(json_encode((array)$body), TRUE);
                //                $json = preg_replace('!\\r?\\n!', "", $response);
                $result = json_decode($response);
            } else {
                $result = json_decode($response);
            }
        }
        return $result;
    }

    public static function getRomawi($angka)
    {
        $hsl = "";
        if ($angka == 1) {
            $hsl = 'I';
        };
        if ($angka == 2) {
            $hsl = 'II';
        };
        if ($angka == 3) {
            $hsl = 'III';
        };
        if ($angka == 4) {
            $hsl = 'IV';
        };
        if ($angka == 5) {
            $hsl = 'V';
        };
        if ($angka == 6) {
            $hsl = 'VI';
        };
        if ($angka == 7) {
            $hsl = 'VII';
        };
        if ($angka == 8) {
            $hsl = 'VIII';
        };
        if ($angka == 9) {
            $hsl = 'IX';
        };
        if ($angka == 10) {
            $hsl = 'X';
        };
        if ($angka == 11) {
            $hsl = 'XI';
        };
        if ($angka == 12) {
            $hsl = 'XII';
        };
        return ($hsl);
    }
    public function hari_ini($date)
    {
        $date = str_replace('/', '-', $date);
        $hari = date("D", strtotime($date));

        switch ($hari) {
            case 'Sun':
                $hari_ini = "Minggu";
                break;

            case 'Mon':
                $hari_ini = "Senin";
                break;

            case 'Tue':
                $hari_ini = "Selasa";
                break;

            case 'Wed':
                $hari_ini = "Rabu";
                break;

            case 'Thu':
                $hari_ini = "Kamis";
                break;

            case 'Fri':
                $hari_ini = "Jumat";
                break;

            case 'Sat':
                $hari_ini = "Sabtu";
                break;

            default:
                $hari_ini = "Tidak di ketahui";
                break;
        }

        return "" . $hari_ini . "";
    }

    public function PasscodeAuthorization($NamaAutorisasi, $KdProfile)
    {
        $Query = DB::table('passwordautorisasi_s')
            ->where('namaautorisasi', '=', $NamaAutorisasi);
        if ($KdProfile) {
            $Query->where('kdprofile', '=', $KdProfile);
        }
        $PasscodeAuthorization = $Query->first();
        if (!empty($PasscodeAuthorization)) {
            return $PasscodeAuthorization->namaautorisasi;
        } else {
            return null;
        }
    }
    public function getKdProfilePasien(Request $request)
    {
        $dataLogin = $request->all();
        $idUser = $dataLogin['userData']['id'];
        $data = Pasien::where('id', $idUser)->first();
        $idKdProfile = (int) $data->kdprofile;
        $Query = DB::table('profile_m')
            ->where('id', '=', $idKdProfile)
            ->first();
        $Profile = $Query;
        if (!empty($Profile)) {
            return (int) $Profile->id;
        } else {
            return null;
        }
    }
    protected function getUrlBios()
    {
        $set = 'http://training-bios2.kemenkeu.go.id/api/';
        return $set;
    }
    protected function getSatker()
    {
        $set = '648261';
        return $set;
    }
    protected function getPasswordConsumerBios()
    {
        $set = 'ueyX84m1MZdSESlc3Ky3YRJ6eah3tjjA';
        return $set;
    }
    protected function kdProfile()
    {
        return app('App\Http\Controllers\Controller')->getProfile();
    }
    protected function kdUser()
    {
        return app('App\Http\Controllers\Controller')->getUserId();
    }
    protected function userLogin()
    {
        return array(
            'user' => app('App\Http\Controllers\Controller')->getUsername(),
            'pegawai' => app('App\Http\Controllers\Controller')->getPegawai(),
        );
    }
    public function kartu_STOK($r)
    {
        $newKS = new KartuStok();
        $newKS->norec = $newKS->generateNewId();
        $newKS->kdprofile = $this->kdProfile()->id;
        $newKS->statusenabled = true;
        $newKS->saldoawal = $r['saldoawal'];
        $newKS->qtyin = $r['qtyin'];
        $newKS->qtyout = $r['qtyout'];
        $newKS->saldoakhir = $r['saldoakhir'];
        $newKS->keterangan = $r['keterangan'];
        $newKS->produkfk = $r['produkfk'];
        $newKS->ruanganfk = $r['ruanganfk'];
        if (isset($r['tglinput'])) {
            $newKS->tglinput = $r['tglinput'];
        } else {
            $newKS->tglinput = date('Y-m-d H:i:s');
        }
        if (isset($r['tglkejadian'])) {
            $newKS->tglkejadian = $r['tglkejadian'];
        } else {
            $newKS->tglkejadian = date('Y-m-d H:i:s');
        }
        $newKS->nostrukterimafk = $r['nostrukterimafk'];
        $newKS->norectransaksi = $r['norectransaksi'];
        $newKS->tabletransaksi = $r['tabletransaksi'];
        if (isset($r['flagfk'])) {
            $newKS->flagfk = $r['flagfk'];
        }
        if (isset($r['stokprodukdetailfk'])) {
            $newKS->stokprodukdetailfk = $r['stokprodukdetailfk'];
        }
        $newKS->save();
        return $newKS;
    }
    protected function SEQUENCE($objectModel = null, $atrribute, $length = 8, $prefix = '', $idProfile)
    {
        DB::beginTransaction();
        try {
            $result = SeqNumber::where('seqnumber', 'LIKE', $prefix . '%')
                ->where('seqname', $atrribute)
                ->where('kdprofile', $idProfile)
                ->max('seqnumber');
            $prefixLen = strlen($prefix);
            $subPrefix = substr(trim($result), $prefixLen);
            $SN = $prefix . (str_pad((int) $subPrefix + 1, $length - $prefixLen, "0", STR_PAD_LEFT));

            $newSN = new SeqNumber();
            $newSN->kdprofile = $idProfile;
            $newSN->seqnumber = $SN;
            $newSN->tgljamseq = date('Y-m-d H:i:s');;
            $newSN->seqname = $atrribute;
            $newSN->save();

            $transStatus = 'true';
        } catch (\Exception $e) {
            $transStatus = 'false';
        }

        if ($transStatus == 'true') {
            DB::commit();
            return $SN;
        } else {
            DB::rollBack();
            return ''; // $e->getMessage();
        }

        return $this->setStatusCode($result['status'])->respond($result);
    }
    // protected function SEQUENCE($objectModel = null, $atrribute, $length = 8, $prefix = '', $idProfile)
    // {
    //     DB::beginTransaction();
    //     try {
    //         $seqName = "seqnumber_seq_".$atrribute;

    //         $seqExist = DB::select("SELECT sequence_schema, sequence_name
    //         FROM information_schema.sequences
    //         where sequence_name='$seqName'");
    //         $cek = SeqNumber::where('seqnumber', 'LIKE', $prefix . '%')
    //          ->where('seqname', $atrribute)
    //          ->where('kdprofile', $idProfile)
    //          ->orderByDesc('created_at')
    //          ->first();
    //         if(count($seqExist) == 0){
    //             $subPrefix = 1;
    //             if(!empty($cek)){
    //                 $result = $cek->seqnumber;
    //                 $prefixLen = strlen($prefix);
    //                 $subPrefix =(int) (substr(trim($result), $prefixLen)) + 1;
    //             }
    //             $create = DB::select("CREATE SEQUENCE ".$seqName." start ".$subPrefix);
    //         }

    //         if(empty($cek)){
    //             // $reset = DB::select("SELECT setval('".$seqName."',1) ");
    //             $reset = DB::select("ALTER SEQUENCE ".$seqName." RESTART WITH 1;");
    //         }
    //         $newSequenceValue = DB::select("SELECT nextval('".$seqName."') AS seq")[0]->seq;

    //         $SN = $prefix . str_pad($newSequenceValue, $length - strlen($prefix), "0", STR_PAD_LEFT);

    //         $newSN = new SeqNumber();
    //         $newSN->kdprofile = $idProfile;
    //         $newSN->seqnumber = $SN;
    //         $newSN->tgljamseq = date('Y-m-d H:i:s');;
    //         $newSN->seqname = $atrribute;
    //         $newSN->save();


    //         $transStatus = 'true';
    //     } catch (\Exception $e) {
    //         $transStatus = 'false';
    //     }

    //     if ($transStatus == 'true') {
    //         DB::commit();
    //         return $SN;
    //     } else {
    //         dd( $e->getMessage());
    //         DB::rollBack();
    //         return '';// $e->getMessage();
    //     }
    // }
    public function Uuid4()
    {
        return substr(Uuid::uuid4(), 0, 36);
        //    return substr(Uuid::uuid4(), 0, 32);
    }
    protected function SEQUENCE_MASTER($objectModel, $field, $idProfile)
    {
        $t = time();
        $result = SeqMaster::where('kdprofile', $idProfile)
            ->where('table', $objectModel->getTable())
            ->first();
        $max = 1;
        if (empty($result)) {

            $cek = $objectModel::where('kdprofile', $idProfile)
                ->orderBy($field, 'desc')
                ->first();

            if (!empty($cek)) {
                $max = (int) $cek->{$field} + 1;
            }

            $new = new SeqMaster();
            $new->kdprofile = $idProfile;
            $new->table = $objectModel->getTable();
            $new->tgl = $t;
            // $new->seqname = $atrribute;
            $new->norec = $this->Uuid4();
            $new->idterakhir = $max;
            $new->save();
        } else {
            $cek = $objectModel::where('kdprofile', $idProfile)
                ->orderBy($field, 'desc')
                ->first();
            if ((int) $cek->{$field} + 1 != $result->idterakhir + 1) {
                $max = (int) $cek->{$field} + 1;
            } else {
                $max = $result->idterakhir + 1;
            }

            SeqMaster::where('norec', $result->norec)
                ->update([
                    'idterakhir' => $max
                ]);
        }

        // $prefixLen = strlen($prefix);
        // $SN = $prefix.(str_pad((int)$max, $length-$prefixLen, "0", STR_PAD_LEFT));
        return $max;
    }

    protected function getProdukDeposit()
    {
        return $this->settingFix('idProdukDeposit');
    }
    protected function getDepositPasien($noregistrasi)
    {
        $produkIdDeposit = $this->getProdukDeposit();
        $depositList = PelayananPasien::where('statusenabled', true)
            ->where('kdprofile', $this->kdProfile)
            ->where('noregistrasi', $noregistrasi)
            ->where('produkfk', $produkIdDeposit)
            ->get();
        $deposit = 0;
        foreach ($depositList as $item) {
            // if ($item->produkfk == $produkIdDeposit) {
            $deposit = $deposit + $item->hargasatuan;
            // }
        }
        // $deposit = 0;
        // $pasienDaftar  = PasienDaftar::has('pelayanan_pasien')->where('noregistrasi', $noregistrasi)->first();
        // if($pasienDaftar){
        //     $depositList = $pasienDaftar->pelayanan_pasien()->where('nilainormal', '-1')->whereNull('strukfk')->get();
        //     foreach ($depositList as $item){
        //         if($item->produkfk==$produkIdDeposit){
        //             $deposit = $deposit + $item->hargasatuan;
        //         }
        //     }
        // }
        return $deposit;
    }
    protected function getDibayarPasien($noregistrasi)
    {
        $depositList = StrukBuktiPenerimaan::where('statusenabled', true)
            ->where('kdprofile', $this->kdProfile)
            ->where('noregistrasi', $noregistrasi)
            ->get();
        $total = 0;
        foreach ($depositList as $item) {
            $total = $total + $item->totaldibayar;
        }
        return $total;
    }

    public static function penyebut($nilai)
    {
        $nilai = abs($nilai);
        $huruf = array("", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas");
        $temp = "";
        if ($nilai < 12) {
            $temp = " " . $huruf[$nilai];
        } else if ($nilai < 20) {
            $temp = static::penyebut($nilai - 10) . " belas";
        } else if ($nilai < 100) {
            $temp = static::penyebut($nilai / 10) . " puluh" . static::penyebut($nilai % 10);
        } else if ($nilai < 200) {
            $temp = " seratus" . static::penyebut($nilai - 100);
        } else if ($nilai < 1000) {
            $temp = static::penyebut($nilai / 100) . " ratus" . static::penyebut($nilai % 100);
        } else if ($nilai < 2000) {
            $temp = " seribu" . static::penyebut($nilai - 1000);
        } else if ($nilai < 1000000) {
            $temp = static::penyebut($nilai / 1000) . " ribu" . static::penyebut($nilai % 1000);
        } else if ($nilai < 1000000000) {
            $temp = static::penyebut($nilai / 1000000) . " juta" . static::penyebut($nilai % 1000000);
        } else if ($nilai < 1000000000000) {
            $temp = static::penyebut($nilai / 1000000000) . " milyar" . static::penyebut(fmod($nilai, 1000000000));
        } else if ($nilai < 1000000000000000) {
            $temp = static::penyebut($nilai / 1000000000000) . " trilyun" . static::penyebut(fmod($nilai, 1000000000000));
        }
        return $temp;
    }

    public static function static_terbilang($nilai)
    {
        if ($nilai < 0) {
            $hasil = "minus " . trim(static::penyebut($nilai));
        } else {
            $hasil = trim(static::penyebut($nilai));
        }
        return $hasil . " Rupiah";
    }
    public static function english($text)
    {

        $trans = new GoogleTranslate();
        $result = $trans->translate($text);

        return $result;
    }
    public function LOGGING($jenislog, $noreff, $referensi, $keterangan, $dataSend = null, $jsonRes = null)
    {
        $logUser = new LoggingUser();
        $logUser->norec = $logUser->generateNewId();
        $logUser->kdprofile = isset($this->kdProfile()->id) ? $this->kdProfile()->id : 1;
        $logUser->statusenabled = true;
        $logUser->jenislog = $jenislog;
        $logUser->noreff = $noreff;
        $logUser->referensi = $referensi;
        $logUser->keterangan = $keterangan;
        $logUser->objectloginuserfk = empty($this->kdUser()) || !is_int($this->kdUser()) ? 1 : $this->kdUser();
        $logUser->tanggal = date('Y-m-d H:i:s');
        $logUser->namauser = empty($this->userLogin()['user']) ? 'SERVER' : $this->userLogin()['user'];
        $logUser->namapegawai = empty($this->userLogin()['pegawai']) ? 'SERVER' : $this->userLogin()['pegawai']['namalengkap'];
        $logUser->data = $dataSend;
        $logUser->response = $jsonRes;
        $logUser->save();
    }
    protected function genSurat($objectModel, $atrribute, $kodesurat, $length = 4, $prefix = '', $kdProfile, $ruangan)
    {
        $thn = date('Y');
        $bln = date('m');
        $romawi = $this->KonDecRomawi($bln);
        $bln2 = Carbon::now()->format('Y/m');
        switch ($kodesurat) {
            case "SuratKeteranganSehat":
                $result = $objectModel->where($atrribute, 'LIKE', '%' . '/SKS/' . '%/' . $kodesurat . '%')->max($atrribute); // Search by collection or ruangan
                $a = substr(trim($result), 0, 7); // Get date

                if ($a != $bln2) {
                    $subPrefix = '0000';
                } else {
                    $subPrefix = substr(trim($result), 12, 4); // Get 4 digit number
                }
                $prefixLen = strlen($prefix);
                $number = $prefix . (str_pad((int) $subPrefix + 1, $length - $prefixLen, "0", STR_PAD_LEFT));

                $nosurat = sprintf('%s/SKS/%s/%s/%s', $number, 'RSMG', $romawi, $thn);
                $noint = sprintf('%s/%s/SKS/%s/%s', $thn, $bln, $number, $kodesurat);
                break;
            case "SuratKeteranganSehatJiwa":
                $result = $objectModel->where($atrribute, 'LIKE', '%' . '/SKSJ/' . '%/' . $kodesurat . '%')->max($atrribute);
                $a = substr(trim($result), 0, 7);

                if ($a != $bln2) {
                    $subPrefix = '0000';
                } else {
                    $subPrefix = substr(trim($result), 13, 4);
                }
                $prefixLen = strlen($prefix);
                $number = $prefix . (str_pad((int) $subPrefix + 1, $length - $prefixLen, "0", STR_PAD_LEFT));

                $nosurat = sprintf('%s/SKSJ/%s/%s/%s', $number, 'MCU.RSMG', $romawi, $thn);
                $noint = sprintf('%s/%s/SKSJ/%s/%s', $thn, $bln, $number, $kodesurat);
                break;
            case "SuratKeteranganBebasNarkoba":
                $result = $objectModel->where($atrribute, 'LIKE', '%' . '/SKBN/' . '%/' . $kodesurat . '%')->max($atrribute);
                $a = substr(trim($result), 0, 7);

                if ($a != $bln2) {
                    $subPrefix = '0000';
                } else {
                    $subPrefix = substr(trim($result), 12, 4);
                }
                $prefixLen = strlen($prefix);
                $number = $prefix . (str_pad((int) $subPrefix + 1, $length - $prefixLen, "0", STR_PAD_LEFT));

                $nosurat = sprintf('%s/SKBN/%s/%s/%s', $number, 'RSMG', $romawi, $thn);
                $noint = sprintf('%s/%s/SKBN/%s/%s', $thn, $bln, $number, $kodesurat);
                break;
            case "SuratKeteranganSakit":
                if ($ruangan == 'IGD') {
                    $result = $objectModel->where($atrribute, 'LIKE', '%' . '/SK.SAKIT/' . '%/' . $kodesurat . '%')->max($atrribute);
                    $a = substr(trim($result), 0, 7);
                    if ($a != $bln2) {
                        $subPrefix = '0000';
                    } else {
                        $subPrefix = substr(trim($result), 17, 4);
                    }
                    $prefixLen = strlen($prefix);
                    $number = $prefix . (str_pad((int) $subPrefix + 1, $length - $prefixLen, "0", STR_PAD_LEFT));

                    $nosurat = sprintf('%s/SK.SAKIT/%s/%s/%s', $number, 'IGD.RSMG', $romawi, $thn);
                    $noint = sprintf('%s/%s/SK.SAKIT/%s/%s', $thn, $bln, $number, $kodesurat);
                } else {
                    $result = $objectModel->where($atrribute, 'LIKE', '%' . '/SK.SAKIT/' . '%/' . $kodesurat . '%')->max($atrribute);
                    $a = substr(trim($result), 0, 7);
                    if ($a != $bln2) {
                        $subPrefix = '0000';
                    } else {
                        $subPrefix = substr(trim($result), 17, 4);
                    }
                    $prefixLen = strlen($prefix);
                    $number = $prefix . (str_pad((int) $subPrefix + 1, $length - $prefixLen, "0", STR_PAD_LEFT));

                    $nosurat = sprintf('%s/SK.SAKIT/%s/%s/%s', $number, 'MCU.RSMG', $romawi, $thn);
                    $noint = sprintf('%s/%s/SK.SAKIT/%s/%s', $thn, $bln, $number, $kodesurat);
                }
                break;
            case "SuratKeteranganSehatParu":
                $result = $objectModel->where($atrribute, 'LIKE', '%' . '/SKSP/' . '%/' . $kodesurat . '%')->max($atrribute);
                $a = substr(trim($result), 0, 7);

                if ($a != $bln2) {
                    $subPrefix = '0000';
                } else {
                    $subPrefix = substr(trim($result), 13, 4);
                }
                $prefixLen = strlen($prefix);
                $number = $prefix . (str_pad((int) $subPrefix + 1, $length - $prefixLen, "0", STR_PAD_LEFT));

                $nosurat = sprintf('%s/SKSP/%s/%s/%s/%s/%s', $number, 'JIWA', 'RJ', 'RSMG', $romawi, $thn);
                $noint = sprintf('%s/%s/SKSP/%s/%s', $thn, $bln, $number, $kodesurat);
                break;
            case "LaporanMCU":
                $result = $objectModel->where($atrribute, 'LIKE', '%' . '/MCU/' . '%/' . $kodesurat . '%')->max($atrribute);
                $a = substr(trim($result), 0, 7);

                if ($a != $bln2) {
                    $subPrefix = '0000';
                } else {
                    $subPrefix = substr(trim($result), 12, 4);
                }
                $prefixLen = strlen($prefix);
                $number = $prefix . (str_pad((int) $subPrefix + 1, $length - $prefixLen, "0", STR_PAD_LEFT));

                $nosurat = sprintf('%s/MCU/%s/%s/%s', $number, 'RSMG', $romawi, $thn);
                $noint = sprintf('%s/%s/MCU/%s/%s', $thn, $bln, $number, $kodesurat);
                break;
            case "SuratKeteranganKesehatan":
                $result = $objectModel->where($atrribute, 'LIKE', '%' . '/SKK/' . '%/' . $kodesurat . '%')->max($atrribute);
                $a = substr(trim($result), 0, 7);

                if ($a != $bln2) {
                    $subPrefix = '0000';
                } else {
                    $subPrefix = substr(trim($result), 12, 4);
                }
                $prefixLen = strlen($prefix);
                $number = $prefix . (str_pad((int) $subPrefix + 1, $length - $prefixLen, "0", STR_PAD_LEFT));

                $nosurat = sprintf('%s/SKK/%s/%s/%s', $number, 'RSMG', $romawi, $thn);
                $noint = sprintf('%s/%s/SKK/%s/%s', $thn, $bln, $number, $kodesurat);
                break;
            case "SuratKeteranganSehatJasmani":
                $result = $objectModel->where($atrribute, 'LIKE', '%' . '/SKSJ/' . '%/' . $kodesurat . '%')->max($atrribute);
                $a = substr(trim($result), 0, 7);

                if ($a != $bln2) {
                    $subPrefix = '0000';
                } else {
                    $subPrefix = substr(trim($result), 13, 4);
                }
                $prefixLen = strlen($prefix);
                $number = $prefix . (str_pad((int) $subPrefix + 1, $length - $prefixLen, "0", STR_PAD_LEFT));

                $nosurat = sprintf('%s/SKSJ/%s/%s/%s', $number, 'RSMG', $romawi, $thn);
                $noint = sprintf('%s/%s/SKSJ/%s/%s', $thn, $bln, $number, $kodesurat);
                break;
            case "HasilPemeriksaanKesehatan":
                $result = $objectModel->where($atrribute, 'LIKE', '%' . '/TPK/' . '%/' . $kodesurat . '%')->max($atrribute);
                $a = substr(trim($result), 0, 7);

                if ($a != $bln2) {
                    $subPrefix = '0000';
                } else {
                    $subPrefix = substr(trim($result), 12, 4);
                }
                $prefixLen = strlen($prefix);
                $number = $prefix . (str_pad((int) $subPrefix + 1, $length - $prefixLen, "0", STR_PAD_LEFT));

                $nosurat = sprintf('%s/TPK/%s/%s/%s', $number, 'RSMG', $romawi, $thn);
                $noint = sprintf('%s/%s/TPK/%s/%s', $thn, $bln, $number, $kodesurat);
                break;
            case "SuratKeteranganPoli":
                $result = $objectModel->where($atrribute, 'LIKE', '%/' . $kodesurat . '/%' . $ruangan)->max($atrribute);
                $a = substr(trim($result), 0, 7);

                if ($a != $bln2) {
                    $subPrefix = '0000';
                } else {
                    $subPrefix = substr(trim($result), 8, 4);
                }
                $prefixLen = strlen($prefix);
                $number = $prefix . (str_pad((int) $subPrefix + 1, $length - $prefixLen, "0", STR_PAD_LEFT));

                $nosurat = sprintf('%s/%s/%s/%s', $number, 'RSMG', $romawi, $thn);
                $noint = sprintf('%s/%s/%s/%s/%s', $thn, $bln, $number, $kodesurat, $ruangan);
                break;
            case "SuratKeteranganHamil":
                $result = $objectModel->where($atrribute, 'LIKE', '%/' . $kodesurat . '/%' . $ruangan)->max($atrribute);
                $a = substr(trim($result), 0, 7);

                if ($a != $bln2) {
                    $subPrefix = '0000';
                } else {
                    $subPrefix = substr(trim($result), 8, 4);
                }
                $prefixLen = strlen($prefix);
                $number = $prefix . (str_pad((int) $subPrefix + 1, $length - $prefixLen, "0", STR_PAD_LEFT));

                $nosurat = sprintf('%s/%s/%s/%s', $number, 'RSMG', $romawi, $thn);
                $noint = sprintf('%s/%s/%s/%s/%s', $thn, $bln, $number, $kodesurat, $ruangan);
                break;
            case "SuratKeteranganAdopsiAnak":
                $result = $objectModel->where($atrribute, 'LIKE', '%/' . $kodesurat . '/%' . $ruangan)->max($atrribute);
                $a = substr(trim($result), 0, 7);

                if ($a != $bln2) {
                    $subPrefix = '0000';
                } else {
                    $subPrefix = substr(trim($result), 8, 4);
                }
                $prefixLen = strlen($prefix);
                $number = $prefix . (str_pad((int) $subPrefix + 1, $length - $prefixLen, "0", STR_PAD_LEFT));

                $nosurat = sprintf('%s/%s/%s/%s', $number, 'RSMG', $romawi, $thn);
                $noint = sprintf('%s/%s/%s/%s/%s', $thn, $bln, $number, $kodesurat, $ruangan);
                break;
            case "SuratKeteranganHibahBarang":
                $result = $objectModel->where($atrribute, 'LIKE', '%/' . $kodesurat . '/%' . $ruangan)->max($atrribute);
                $a = substr(trim($result), 0, 7);

                if ($a != $bln2) {
                    $subPrefix = '0000';
                } else {
                    $subPrefix = substr(trim($result), 8, 4);
                }
                $prefixLen = strlen($prefix);
                $number = $prefix . (str_pad((int) $subPrefix + 1, $length - $prefixLen, "0", STR_PAD_LEFT));

                $nosurat = sprintf('%s/%s/%s/%s', $number, 'RSMG', $romawi, $thn);
                $noint = sprintf('%s/%s/%s/%s/%s', $thn, $bln, $number, $kodesurat, $ruangan);
                break;
            case "PermohonanPengantarPasienIK":
                $result = $objectModel->where($atrribute, 'LIKE', '%/' . $kodesurat . '%')->max($atrribute);
                $a = substr(trim($result), 0, 7);

                if ($a != $bln2) {
                    $subPrefix = '0000';
                } else {
                    $subPrefix = substr(trim($result), 8, 4);
                }
                $prefixLen = strlen($prefix);
                $number = $prefix . (str_pad((int) $subPrefix + 1, $length - $prefixLen, "0", STR_PAD_LEFT));

                $nosurat = sprintf('%s/%s/%s/%s', $number, 'RSMG', $romawi, $thn);
                $noint = sprintf('%s/%s/%s/%s', $thn, $bln, $number, $kodesurat);
                break;
            case "SuratKeteranganDisabilitas":
                $result = $objectModel->where($atrribute, 'LIKE', '%/' . $kodesurat . '%')->max($atrribute);
                $a = substr(trim($result), 0, 7);

                if ($a != $bln2) {
                    $subPrefix = '0000';
                } else {
                    $subPrefix = substr(trim($result), 8, 4);
                }
                $prefixLen = strlen($prefix);
                $number = $prefix . (str_pad((int) $subPrefix + 1, $length - $prefixLen, "0", STR_PAD_LEFT));

                $nosurat = sprintf('%s/%s/%s/%s', $number, 'RSMG', $romawi, $thn);
                $noint = sprintf('%s/%s/%s/%s', $thn, $bln, $number, $kodesurat);
                break;
            case "SuratKeteranganDiagnosa":
                $result = $objectModel->where($atrribute, 'LIKE', '%/' . $kodesurat . '%')->max($atrribute);
                $a = substr(trim($result), 0, 7);

                if ($a != $bln2) {
                    $subPrefix = '0000';
                } else {
                    $subPrefix = substr(trim($result), 8, 4);
                }
                $prefixLen = strlen($prefix);
                $number = $prefix . (str_pad((int) $subPrefix + 1, $length - $prefixLen, "0", STR_PAD_LEFT));

                $nosurat = sprintf('%s/%s/%s/%s', $number, 'RSMG', $romawi, $thn);
                $noint = sprintf('%s/%s/%s/%s', $thn, $bln, $number, $kodesurat);
                break;
            case "SuratPermintaanDirawat":
                $result = $objectModel->where($atrribute, 'LIKE', '%/' . $kodesurat . '%')->max($atrribute);
                $a = substr(trim($result), 0, 7);

                if ($a != $bln2) {
                    $subPrefix = '0000';
                } else {
                    $subPrefix = substr(trim($result), 8, 4);
                }
                $prefixLen = strlen($prefix);
                $number = $prefix . (str_pad((int) $subPrefix + 1, $length - $prefixLen, "0", STR_PAD_LEFT));

                $nosurat = sprintf('%s/%s/%s/%s', $number, 'RSMG', $romawi, $thn);
                $noint = sprintf('%s/%s/%s/%s', $thn, $bln, $number, $kodesurat);
                break;
            case "SuratKeteranganRekomendasiPsikologi":
                $result = $objectModel->where($atrribute, 'LIKE', '%/' . $kodesurat . '%')->max($atrribute);
                $a = substr(trim($result), 0, 7);

                if ($a != $bln2) {
                    $subPrefix = '0000';
                } else {
                    $subPrefix = substr(trim($result), 8, 4);
                }
                $prefixLen = strlen($prefix);
                $number = $prefix . (str_pad((int) $subPrefix + 1, $length - $prefixLen, "0", STR_PAD_LEFT));

                $nosurat = sprintf('%s/%s/%s/%s', $number, 'RSMG', $romawi, $thn);
                $noint = sprintf('%s/%s/%s/%s', $thn, $bln, $number, $kodesurat);
                break;
            case "SuratKeteranganHasilPemeriksaanMata":
                $result = $objectModel->where($atrribute, 'LIKE', '%/' . $kodesurat . '%')->max($atrribute);
                $a = substr(trim($result), 0, 7);

                if ($a != $bln2) {
                    $subPrefix = '0000';
                } else {
                    $subPrefix = substr(trim($result), 8, 4);
                }
                $prefixLen = strlen($prefix);
                $number = $prefix . (str_pad((int) $subPrefix + 1, $length - $prefixLen, "0", STR_PAD_LEFT));

                $nosurat = sprintf('%s/%s/%s/%s', $number, 'RSMG', $romawi, $thn);
                $noint = sprintf('%s/%s/%s/%s', $thn, $bln, $number, $kodesurat);
                break;
            case "SuratKeteranganBebasTato":
                $result = $objectModel->where($atrribute, 'LIKE', '%/' . $kodesurat . '%')->max($atrribute);
                $a = substr(trim($result), 0, 7);

                if ($a != $bln2) {
                    $subPrefix = '0000';
                } else {
                    $subPrefix = substr(trim($result), 8, 4);
                }
                $prefixLen = strlen($prefix);
                $number = $prefix . (str_pad((int) $subPrefix + 1, $length - $prefixLen, "0", STR_PAD_LEFT));

                $nosurat = sprintf('%s/%s/%s/%s/%s', $number, 'SK', 'RSMG', $romawi, $thn);
                $noint = sprintf('%s/%s/%s/%s', $thn, $bln, $number, $kodesurat);
                break;
            default:
                $nosurat = '';
                $noint = '';
                break;
        }

        $result = array(
            'noint' => $noint,
            'nosurat' => $nosurat,
        );

        return $result;
    }

    public function settingDataFixed($NamaField, $KdProfile)
    {
        $Query = DB::table('settingdatafixed_m')
            ->where('namafield', '=', $NamaField);
        if ($KdProfile) {
            $Query->where('kdprofile', '=', $KdProfile);
        }
        $settingDataFixed = $Query->first();
        if (!empty($settingDataFixed)) {
            return $settingDataFixed->nilaifield;
        } else {
            return null;
        }
    }
    public function terbilang2($angka)
    {
        $angka = floatval($angka);
        $bilangan = [
            '',
            'satu',
            'dua',
            'tiga',
            'empat',
            'lima',
            'enam',
            'tujuh',
            'delapan',
            'sembilan'
        ];
        $puluhan = [
            '',
            'sepuluh',
            'dua puluh',
            'tiga puluh',
            'empat puluh',
            'lima puluh',
            'enam puluh',
            'tujuh puluh',
            'delapan puluh',
            'sembilan puluh'
        ];
        $ribuan = ['ribu', 'juta', 'miliar', 'triliun'];

        $hasil = '';

        if ($angka == 0) {
            $hasil = 'nol';
        } else {
            $potong = [];
            $posisi = 0;

            while ($angka > 0) {
                $potong[$posisi] = $angka % 1000;
                $angka = floor($angka / 1000);
                $posisi++;
            }

            $posisi = count($potong) - 1;

            for ($i = $posisi; $i >= 0; $i--) {
                if ($potong[$i] > 0) {
                    $temp = '';
                    $ratusan = floor($potong[$i] / 100);
                    $puluhanan = $potong[$i] % 100;

                    if ($ratusan > 0) {
                        $temp .= $bilangan[$ratusan] . ' ratus ';
                    }

                    if ($puluhanan > 10 && $puluhanan < 20) {
                        $temp .= $bilangan[$puluhanan - 10] . ' belas ';
                    } elseif ($puluhanan == 10 || $puluhanan == 0) {
                        $temp .= $puluhan[$ratusan];
                    } else {
                        $temp .= $puluhan[floor($puluhanan / 10)] . ' ' . $bilangan[$puluhanan % 10];
                    }

                    if ($i > 0) {
                        if ($potong[$i] == 2) {
                            $temp .= ' ribu ';
                        } else {
                            $temp .= ' ' . $ribuan[$i - 1];
                        }
                    }

                    $hasil .= $temp;
                }
            }
        }

        return $hasil . ' rupiah';
    }
    protected function dateRange($first, $last, $step = '+1 day', $format = 'Y-m-d')
    {
        $dates = [];
        $current = strtotime($first);
        $last = strtotime($last);

        while ($current <= $last) {

            $dates[] = date($format, $current);
            $current = strtotime($step, $current);
        }

        return $dates;
    }
    public function historyBED($r)
    {
        $newKS = new KetersediaanTempatTidur();
        $newKS->norec = $newKS->generateNewId();
        $newKS->kdprofile = $this->kdProfile()->id;
        $newKS->statusenabled = true;
        $newKS->tempattidurfk = $r['tempattidurfk'];
        $newKS->statusbedfk = $r['statusbedfk'];
        $newKS->tglupdate = date('Y-m-d H:i:s');
        $newKS->ruanganfk = $r['ruanganfk'];
        $newKS->kamarfk = $r['kamarfk'];
        $newKS->save();
        return $newKS;
    }
}
