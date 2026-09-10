<?php

namespace App\Http\Controllers\Bridging;

use App\Http\Controllers\Controller;
use App\Models\Transaksi\OrderBridgeDLIS;
use App\Models\Transaksi\OrderBridgeLIS;
use App\Models\Transaksi\OrderPelayanan;
use App\Models\Transaksi\RisOrder;
use App\Models\Transaksi\StrukOrder;
use App\Traits\Valet;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request as Psr7Request;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use App\Models\Master\HargaNettoProdukByKelas;
use App\Models\Transaksi\OrderLab;
use App\Models\Transaksi\PelayananPasienPetugas;
use Exception;

class BridgingPenunjangCtrl extends Controller
{
    use Valet;
    public function __construct()
    {
        parent::__construct($is_encrypt = true);
    }

    public function saveBridgingZeta(Request $request)
    {

        $kdProfile = (int)$this->kdProfile;
        $noorder = $request['noorder'];
        if (isset($request['details'])) {

            DB::beginTransaction();
            try {

                $struk = StrukOrder::where('noorder', $noorder)->where('kdprofile', $kdProfile)->where('statusenabled', true)->first();
                OrderPelayanan::where('strukorderfk', $struk->norec)->delete();
                foreach ($request['details'] as $item) {
                    $dataOP = new OrderPelayanan;
                    $dataOP->norec = $dataOP->generateNewId();
                    $dataOP->kdprofile = $kdProfile;
                    $dataOP->statusenabled = true;
                    if (isset($item['iscito'])) {
                        $dataOP->iscito = (float) $item['iscito'];
                    } else {
                        $dataOP->iscito = 0;
                    }

                    $dataOP->noorderfk = $struk->norec;
                    $dataOP->objectprodukfk = $item['produkid'];
                    $dataOP->qtyproduk = $item['qtyproduk'];
                    $dataOP->objectkelasfk = $request['objectkelasfk'];
                    $dataOP->qtyprodukretur = 0;
                    $dataOP->objectruanganfk = $struk->objectruanganfk;
                    $dataOP->objectruangantujuanfk = $struk->objectruangantujuanfk;
                    $dataOP->strukorderfk = $struk->norec;
                    if (isset($request['tglpelayanan'])) {
                        $dataOP->tglpelayanan = $request['tglpelayanan'];
                    } else {
                        $dataOP->tglpelayanan = $struk->tglorder; // date('Y-m-d H:i:s');
                    }
                    $dataOP->objectnamapenyerahbarangfk = $struk->objectpegawaiorderfk;

                    $dataOP->save();
                }
                $stt = 'true';
            } catch (\Exception $e) {
                $stt = 'false';
            }
            if ($stt == 'true') {
                DB::commit();
            } else {
                DB::rollBack();
            }
        }

        DB::beginTransaction();


        try {
            StrukOrder::where('noorder', $noorder)
                ->where('kdprofile', $kdProfile)
                ->update(
                    [
                        'statusorder' => 1
                    ]
                );
            $raw = DB::select(DB::raw("select op.norec, op.kdprofile as kdprofile,
		     op.iscito as cito,  op.tglpelayanan, prd.id as produkid_tm , op.nourut, prd.namaproduk,
             ps.nocm,  so.noorderintern, so.noorder as noorder, ps.namapasien,  ps.tgllahir,  jk.jeniskelamin,
             alm.alamatlengkap, ru.id as ruanganid, ru.namaruangan, pd.noregistrasi,

             pd.noregistrasi, pg.id as pgid,pg.namalengkap,
             dp.id as departemenid ,prd.objectdetailjenisprodukfk,
			 djp.detailjenisproduk,djp.kodeexternal as kodeexternaldjp ,djp.objectjenisprodukfk,
			 jp.jenisproduk,prd.sanata_kodepemeriksaan_rad as modality,kps.kelompokpasien,so.tglorder,
			 gdr.golongandarah,ris.order_key,prd.sanata_jasa_id as produkid
             FROM orderpelayanan_t as op
			 left join produk_m as prd on prd.id=op.objectprodukfk
			 left join detailjenisproduk_m as djp on djp.id=prd.objectdetailjenisprodukfk
             left join jenisproduk_m as jp on jp.id=djp.objectjenisprodukfk
			 left join strukorder_t as so on so.norec=op.strukorderfk
			 left join pasien_m as ps on ps.id =so.nocmfk
			 left join jeniskelamin_m as jk on jk.id=ps.objectjeniskelaminfk
			 left join alamat_m as alm on alm.nocmfk=ps.id

			 left join pasiendaftar_t as pd on pd.norec=so.noregistrasifk
			 left join kelompokpasien_m as kps on kps.id=pd.objectkelompokpasienlastfk
			 left join ruangan_m as ru on ru.id=pd.objectruanganlastfk
			 left join pegawai_m as pg on pg.id=so.objectpegawaiorderfk
			 left join departemen_m as dp on dp.id=ru.objectdepartemenfk
			 left join golongandarah_m as gdr on gdr.id=ps.objectgolongandarahfk
			 left join ris_order as ris on ris.order_no=so.noorder
                and ris.order_code=cast( op.objectprodukfk as text)
                and ris.kdprofile = so.kdprofile
       where so.noorder = '$noorder'
       and so.kdprofile = $kdProfile "));

            $errorrr =  "";


            //            try {
            foreach ($raw as $item) {
                $getAccNumber = $this->getAccNumber($item->kodeexternaldjp, $item->modality);

                if ($item->modality == null) {
                    $errorrr = "Modality belum disetting";
                    $modality = null;
                    //                    return $this->setStatusCode(400)->respond('', 'Kode External Bridging Produk '.$item->namaproduk. ' Kosong');
                } else {
                    if (strlen(trim($item->modality)) > 4) {
                        $errorrr = "Modality tidak dikenal";
                        $modality = null;
                    } else {
                        $modality = str_limit(trim($item->modality), 5);
                    }
                }

                if ($modality == null) {
                    $errorrr= '';
                    // break;
                }

                // if (empty($item->nourut)) {
                //   $errorrr = "Tidak ada nomor urut order pelayanan";
                //   break;
                // }

                $newBRG = new RisOrder();
                //                    if (is_null($item->order_key)) {
                $newId = RisOrder::max('order_key');

                $newId = $newId + 1;
                $nourut = RisOrder::where('patient_id', $item->nocm)->max('order_cnt');

                if (is_null($nourut) || empty($nourut) || $nourut == null) {
                    $nourut = 0;
                }

                $nourut = $nourut + 1;
                $newBRG->order_cnt = $nourut;

                $newBRG->order_key = $newId;
                $newBRG->norec_op_fk = $item->norec;
                //                    }else{
                //                        $newBRG = RisOrder::where('order_key',$item->order_key)->first();
                //                    }
                $newBRG->accession_num = $getAccNumber;
                $newBRG->aetitle = '-';
                $newBRG->charge_doc_id = $request['iddokterverif']; //dokter rad
                $newBRG->charge_doc_name =  $request['namadokterverif']; //dokter rad
                $newBRG->consult_doc_id =  $item->pgid;
                $newBRG->consult_doc_name =  $item->namalengkap;
                $newBRG->create_date = (string)date('YmdHi');
                $newBRG->extension1 = $item->noregistrasi;
                /*
               * if JenisDiagnosa==1 : isi namadiagnosa ke extension2 & extension4
               */
                $newBRG->extension2 = '-';
                $newBRG->extension4 = '-';
                /*
               * if JenisDiagnosa==2 : isi namadiagnosa ke eextension3
               */
                $newBRG->extension3 = '-';
                $newBRG->extension5 = $item->kelompokpasien;
                $newBRG->extension6 = $this->getUserId();
                $newBRG->extension7 = '-';
                $newBRG->extension8 = '-';
                $newBRG->extension9 = '-';
                $newBRG->extension10 = '-';
                //            $newBRG->first_name = '';
                $newBRG->flag = 'Y';
                $newBRG->group1 = '-';
                $newBRG->group2 = '-';
                $newBRG->group3 = '-';
                /*
                * - 18 : R. Jalan - 16 : R. Inap
                */
                if ($raw[0]->departemenid == 18) {
                    $io_date = 'E'; // AWALNYA O HARUSNYA E
                } else {
                    $io_date = 'I';
                }
                $newBRG->io_date = $io_date;
                //            $newBRG->last_name = '';
                $newBRG->middle_name = '-';
                $newBRG->order_bodypart = '-';
                $newBRG->order_code = $item->produkid;
                $newBRG->order_comment = '-';
                $newBRG->order_date = (string)date('YmdHi', strtotime($item->tglorder));;
                $newBRG->order_dept = $request['objectruangantujuanfk'];
                //            $newBRG->order_diag = $item->modality;

                $newBRG->order_modality = $modality;
                $newBRG->order_name = $item->namaproduk;
                $newBRG->order_no = $request['noorder'];
                $newBRG->order_reason = '-';
                $newBRG->order_status = 'NW';
                $newBRG->patient_birth_date = (string)date('Ymd', strtotime($item->tgllahir));
                $newBRG->patient_blood = $item->golongandarah;

                // $idPasien = ($item->nourut == null) ? $item->nocm : $item->nocm . '-' . $item->nourut;
                $idPasien = $item->nocm;

                $newBRG->patient_id = $idPasien;
                /*- E: Cito
                  -	*/
                if ($item->cito == '1') {
                    $patient_io = 'E'; // E = Emergency
                } else {
                    $patient_io = 'U'; // awalnya I, harusnya isinya bisa R = Routine, bisa A = Accident, bisa U = Urgent, bisa N = Newborn
                }
                $newBRG->patient_io = $patient_io;
                $newBRG->patient_name = $item->namapasien;
                /*
                   * - M : Laki-laki - F : Perempuan - O : Tidak diketahui
                   */
                if (strtolower($raw[0]->jeniskelamin) == 'laki-laki') {
                    $jk = 'M';
                } else if (strtolower($raw[0]->jeniskelamin) == 'perempuan') {
                    $jk = 'F';
                } else {
                    $jk = 'O';
                }
                $newBRG->patient_sex = $jk;
                $newBRG->patient_uid = '-';
                $newBRG->patient_ward = $item->namaruangan;
                $newBRG->study_remark = '-';
                $newBRG->study_reserv_date = (string)date('YmdHi', strtotime($item->tglpelayanan));
                $newBRG->kelas = $request['objectkelasfk'];

                $newBRG->save();
            }

            $transStatus = (empty($errorrr)) ? 'true' : 'false';
        } catch (\Exception $e) {
            $errorrr = $e->getMessage();

            $transStatus = 'false';
        }

        if ($transStatus == 'true') {
            $transMessage = "Sukses";
            DB::commit();
            // $objetoRequest = new \Illuminate\Http\Request();
            // $objetoRequest['noorder'] = $noorder;
            // $ihs = app('App\Http\Controllers\Bridging\IHSController')->ServiceRequest($objetoRequest, true);

            $result = array(
                "status" => 200,
                "result" => array(
                    "data"  => $newBRG,
                    // "ServiceRequest" => $ihs,
                    "as" => '@epic',
                ),
            );
        } else {
            $transMessage = "Simpan RIS gagal penyebab: " . $errorrr;
            DB::rollBack();
            $result = array(
                "status" => 200,
                "result"  =>null

            );
        }
        return $this->respond($result['result'], $result['status'], $transMessage);

    }
    public function getAccNumber($kdExternal, $namaExternal)
    {
        $d[0] = $kdExternal;
        $d[1] = $namaExternal;

        $kode = '';
        if ($namaExternal == null) {
            $kode = "5";
        } else if ($namaExternal == "CR") {
            if ($kdExternal == "1") {
                $kode = "1";
            } else {
                $kode = "2";
            }
        } else if ($namaExternal == "US" || $namaExternal == "EC") {
            $kode = "3";
        } else if ($namaExternal == "CT") {
            $kode = "4";
        } else if ($namaExternal == "MR") {
            $kode = "5";
        } else if ($namaExternal == "DX") {
            $kode = "6";
        } else if ($namaExternal == "XA") {
            $kode = "7";
        } else {
            $kode = "2";
        }

        if (!empty($kode)) {
            $month = (string)date('m');
            $year = (string)date('Y');
            $accessNum = DB::select(DB::raw("select max(a.accession_num) as max
                            from ris_order a where
                            substring(a.accession_num, 1, 1)= '$kode' and
                            substring(a.create_date, 5, 2) = '$month' and
                            substring(a.create_date, 1, 4)= '$year'"));



            if ($accessNum[0] != null) {
                $accessNum = $accessNum[0]->max;
                $yearNow = (string)date('y');

                $digit = null;
                $number = null;
                if (!empty($accessNum)) {
                    $number =  (substr($accessNum, 5)) + 1;
                } else {
                    $number = 1;
                }

                if (strlen($number) == 1) {
                    $digit = "000";
                } else if (strlen($number) == 2) {
                    $digit = "00";
                } else if (strlen($number) == 3) {
                    $digit = "0";
                }
                if (strlen($month) == 1)
                    $month = "0" . $month;

                $noUsulan = $kode . $yearNow . $month . $digit . $number;
            } else {
                $yearNow = (string)date('y');

                $number = 1;
                if (strlen($number) == 1) {
                    $digit = "000";
                } else if (strlen($number) == 2) {
                    $digit = "00";
                } else if (strlen($number) == 3) {
                    $digit = "0";
                }
                if (strlen($month) == 1)
                    $month = "0" . $month;

                $noUsulan = $kode . $yearNow . $month . $digit . $number;
            }
        }

        return $noUsulan;
    }
    public function saveBridgingVansLabOld(Request $request) {
        $kdProfile = (int)$this->kdProfile;
        DB::beginTransaction();
        try {
            $noorder = $request['noorder'];
            if(isset($request['details'] )){
                $struk = StrukOrder::where('noorder',$noorder )->where('kdprofile',$kdProfile)->where('statusenabled',true)->first();
                OrderPelayanan::where('strukorderfk',$struk->norec)->delete();
                foreach ($request['details'] as $item) {
                    $dataOP = new OrderPelayanan;
                    $dataOP->norec = $dataOP->generateNewId();
                    $dataOP->kdprofile = $kdProfile;
                    $dataOP->statusenabled = true;
                    if(isset($item['iscito'])){
                        $dataOP->iscito =(float) $item['iscito'];
                    }else{
                        $dataOP->iscito = 0;
                    }
                    $dataOP->noorderfk = $struk->norec;
                    $dataOP->objectprodukfk = $item['produkid'];
                    $dataOP->qtyproduk = $item['qtyproduk'];
                    $dataOP->objectkelasfk = 2;
                    $dataOP->qtyprodukretur = 0;
                    $dataOP->objectruanganfk =$struk->objectruanganfk;
                    $dataOP->objectruangantujuanfk = $struk->objectruangantujuanfk;
                    $dataOP->strukorderfk = $struk->norec;
                    if(isset($request['tglpelayanan'])){
                        $dataOP->tglpelayanan=$request['tglpelayanan'];
                    }else{
                        $dataOP->tglpelayanan =$struk->tglorder;
                    }
                    $dataOP->objectnamapenyerahbarangfk = $struk->objectpegawaiorderfk;
                    $dataOP->ihs_id =  isset($item['ihs_service_request'])?$item['ihs_service_request']:null;
                    $dataOP->save();
                }
            }


          $raw = DB::select(DB::raw("
                    SELECT pd.norec as norec_pd, op.norec,op.iscito AS cito,  so.tglorder,to_char(so.tglorder, 'HH:mm') AS jamorder,
                    prd.ID AS produkid,prd.namaproduk, ps.nocm,so.noorder AS noorder,	ps.namapasien,ps.tgllahir,jk. ID AS jkid,jk.jeniskelamin,
                    CASE WHEN alm.alamatlengkap IS NULL THEN   '-'   ELSE (   alm.alamatlengkap || ' ' || (
                        CASE WHEN ds.namadesakelurahan IS NOT NULL THEN 'Kel. ' ||  ds.namadesakelurahan  ELSE    ''   END ) || ' ' || (
                        CASE WHEN kc.namakecamatan IS NOT NULL THEN 'Kec. ' || kc.namakecamatan ELSE ''  END  ) || ' ' || (
                        CASE WHEN kk.namakotakabupaten IS NOT NULL THEN  kk.namakotakabupaten ELSE  '' END  ) || ' ' || (
                        CASE WHEN pro.namapropinsi IS NOT NULL THEN  'Prov. ' ||   pro.namapropinsi ELSE  ''  END  ) )
                    END AS alamatlengkap, ru.ID AS ruanganid, ru.namaruangan, pd.noregistrasi, pg. ID AS pgid,pg.namalengkap,
                 dp. ID AS departemenid, date_part('year', age(ps.tgllahir)) usia, kp. ID AS kode_cara_bayar,  kp.kelompokpasien AS cara_bayar, ru2. ID AS ideuangantujuan,
                 ru2.namaruangan AS ruangantujuan, ps.noidentitas as nik, alm.objectkotakabupatenfk, kk.namakotakabupaten,ps.nohp,
                 ru.objectdepartemenfk ,dp.namadepartemen,pd.objectkelasfk,kl.namakelas,so.objectpegawaiorderfk,  pg.namalengkap
                FROM
                    orderpelayanan_t AS op
                INNER JOIN produk_m AS prd ON prd. ID = op.objectprodukfk
                INNER JOIN strukorder_t AS so ON so.norec = op.strukorderfk
                INNER JOIN pasien_m AS ps ON ps. ID = so.nocmfk
                LEFT JOIN alamat_m as alm on alm.nocmfk=ps.id
                LEFT JOIN desakelurahan_m as ds on ds.id=alm.objectdesakelurahanfk
                LEFT JOIN kotakabupaten_m as kk on kk.id=alm.objectkotakabupatenfk
                LEFT JOIN kecamatan_m as kc on kc.id=alm.objectkecamatanfk
                LEFT JOIN propinsi_m as pro on pro.id=alm.objectpropinsifk
                INNER JOIN pasiendaftar_t AS pd ON pd.norec = so.noregistrasifk
                INNER JOIN kelompokpasien_m AS kp ON kp. ID = pd.objectkelompokpasienlastfk
                INNER JOIN ruangan_m AS ru ON ru. ID = pd.objectruanganlastfk
                INNER JOIN ruangan_m AS ru2 ON ru2. ID = so.objectruangantujuanfk
                LEFT JOIN jeniskelamin_m AS jk ON jk. ID = ps.objectjeniskelaminfk
                INNER JOIN kelas_m AS kl ON kl.id = pd.objectkelasfk
                LEFT JOIN pegawai_m AS pg ON pg. ID = so.objectpegawaiorderfk
                LEFT JOIN departemen_m AS dp ON dp. ID = ru.objectdepartemenfk
                WHERE
                    so.noorder = '$noorder'
                    and so.kdprofile=$kdProfile
                    and so.statusenabled=true
               "));



            $pdnorec = $raw[0]->norec_pd;
            $diag =  DB::select(DB::raw("
                select DISTINCT dg.kddiagnosa , dg.namadiagnosa,pd.noregistrasi,dg.id
                from pasiendaftar_t as pd
                join antrianpasiendiperiksa_t as apd on apd.noregistrasifk =pd.norec
                join detaildiagnosapasien_t  as ddp on ddp.noregistrasifk=apd.norec
                join diagnosa_m  as dg on dg.id=ddp.objectdiagnosafk
                where pd.kdprofile=$kdProfile and ddp.objectjenisdiagnosafk = 1
                and pd.statusenabled = true and pd.norec ='$pdnorec'
            "));

            $arrDiag ='';
            $kdDiag ='';
            if(count($diag) > 0){
                foreach ($diag as $d){
                    $arrDiag = $d->kddiagnosa .'-' .$d->namadiagnosa .  ' ,' .$arrDiag;
                    $kdDiag = $d->id;
                }
            }

            StrukOrder::where('noorder',$noorder)
                ->where('kdprofile', $kdProfile)
                ->update(
                [
                    'statusorder' => 1
                ]
            );


            $cek = OrderBridgeLIS::where('order_number',$noorder)->first();
            if(!empty($cek)){
                OrderBridgeLIS::where('order_number',$noorder)->delete();
            }

            $newBRG = new OrderBridgeLIS();
            $newBRG->norec = $newBRG->generateNewId();
            $newBRG->patient_id = $raw[0]->nocm ;
            $newBRG->gender_id = $raw[0]->jkid == 1 ? 'L':($raw[0]->jkid == 2 ? 'P':'');
            $newBRG->gender_name = $raw[0]->jeniskelamin ;
            $newBRG->date_of_birth = date('Y/m/d',strtotime( $raw[0]->tgllahir));
            $newBRG->patient_name = $raw[0]->namapasien ;
            $newBRG->patient_address = $raw[0]->alamatlengkap ;
            $newBRG->city_id = $raw[0]->objectkotakabupatenfk ;
            $newBRG->city_name = $raw[0]->namakotakabupaten ;
            $newBRG->phone_number =  $raw[0]->nohp ;
            $newBRG->fax_number = '' ;
            $newBRG->mobile_number =  $raw[0]->nohp ;
            $newBRG->email = '' ;
            $newBRG->visit_number =   $raw[0]->noregistrasi ;
            $newBRG->order_number = $noorder ;
            $newBRG->order_datetime =  $raw[0]->tglorder ;
            $newBRG->service_unit_id = $raw[0]->objectdepartemenfk ;
            $newBRG->service_unit_name = $raw[0]->namadepartemen ;
            $newBRG->guarantor_id =  $raw[0]->kode_cara_bayar ;
            $newBRG->guarantor_name =  $raw[0]->cara_bayar;
            $newBRG->agreement_id =  $raw[0]->objectkelasfk ;
            $newBRG->agreement_name =  $raw[0]->namakelas  ;
            $newBRG->doctor_id = $raw[0]->objectpegawaiorderfk;// $request['iddokterverif'] ;
            $newBRG->doctor_name = $raw[0]->namalengkap;//$request['namadokterverif'];
            $newBRG->class_id = $raw[0]->objectkelasfk ;
            $newBRG->class_name = $raw[0]->namakelas  ;
            $newBRG->ward_id =  $raw[0]->ruanganid ;
            $newBRG->ward_name = $raw[0]->namaruangan ;
            $newBRG->room_id =  $raw[0]->ideuangantujuan ;
            $newBRG->room_name = $raw[0]->ruangantujuan ;
            $newBRG->bed_id = '-' ;
            $newBRG->bed_name = '-' ;
            $newBRG->diagnosa_id = $kdDiag;
            $newBRG->diagnosa_name = $arrDiag ;
            $newBRG->iscito = $raw[0]->cito ;
            $newBRG->reg_user_id = $raw[0]->objectpegawaiorderfk;
            $newBRG->reg_user_name = $raw[0]->namalengkap;
            $newBRG->lis_reg_no = null ;
            $newBRG->retrieved_dt = null;
            $newBRG->retrieved_flag = null;
            $newBRG->nik = $raw[0]->nik;
            $newBRG->save();

            foreach($request['bridging'] as $item ){

                $cek = OrderBridgeDLIS::where('order_number',$noorder)
                    ->where('order_item_id', $item['produkfk'])->first();
                if(!empty($cek)){
                    OrderBridgeDLIS::where('order_number',$noorder)
                         ->where('order_item_id', $item['produkfk'])->delete();
                }
                $newBRG2 = new OrderBridgeDLIS();
                $newBRG2->norec = $newBRG2->generateNewId();
                $newBRG2->order_number =  $raw[0]->noorder;
                $newBRG2->order_item_id = $item['produkfk'];
                $newBRG2->order_item_name = $item['namaproduk'];
                $newBRG2->orderbridgefk = $newBRG->norec;
                $newBRG2->save();

            }

            $transStatus = 'true';
       } catch (\Exception $e) {
            $transStatus = 'false';
       }
       if ($transStatus == 'true') {
            $transMessage = "Sukses";
            DB::commit();
            // $objetoRequest = new \Illuminate\Http\Request();
            // $objetoRequest['noorder'] = $noorder;
            // $ihs = app('App\Http\Controllers\Bridging\IHSController')->ServiceRequest($objetoRequest, true);

            $result = array(
                "status" => 200,
                "result" => array(
                    "data"  => $newBRG,
                    // "ServiceRequest" => $ihs,
                    "as" => '@epic',
                ),
            );
        } else {
            $transMessage = "Simpan LIS gagal";
            DB::rollBack();
            $result = array(
                "status" => 400,
                "result"  => $e->getMessage(). ' '.$e->getLine(),//null

            );
        }
        return $this->respond($result['result'], $result['status'], $transMessage);

    }
    public function updateRadiograferRIS(Request $req){
        $params_pp=$req['norec_pp'];
        $params_noregitrasi=$req['noregistrasi'];
        $params_radiografer=$req['radiograferfk'];
        $params_norecAPd=$req['norec_apd'];
        DB::beginTransaction();
        try {
            $cek=PelayananPasienPetugas::where('statusenabled',true)
            ->where('pelayananpasien',$params_pp)
            ->where('noregistrasi',$params_noregitrasi)->first();
           
            if(!empty($cek)){
                $data = PelayananPasienPetugas::where('statusenabled',true)
                    ->where('pelayananpasien',$params_pp)
                    ->where('noregistrasi',$params_noregitrasi)
                    ->update(['radiograferfk' => $params_radiografer]);
            
                DB::commit();

            if(!empty($data)){
                    $valueBrid=DB::table('pelayananpasien_t as pp')
                    ->join('produk_m as prd','prd.id','=','pp.produkfk')
                    ->join('pelayananpasienpetugas_t as aptg','aptg.pelayananpasien','=','pp.norec')
                    ->join('pegawai_m as pgw','pgw.id','=','aptg.radiograferfk')
                    ->where('pp.norec',$params_pp)
                    ->where('pp.statusenabled',true)
                    ->select(
                        'pp.norec',
                        'prd.id as id_produk',
                        'prd.namaproduk',
                        'pgw.id as id_radiografer',
                        'pgw.namalengkap as nama_radiografer',
                        'aptg.objectpegawaifk as id_dokter_rad'
                    )
                    ->first();

                    $record = DB::connection('sqlsrv_ris')
                        ->table('ris_in')
                        ->where('no_register', $params_noregitrasi)
                        ->where('kode_pemeriksaan', $valueBrid->id_produk)
                        ->where('kode_dokter_radiolog', $valueBrid->id_dokter_rad)
                        ->first();

                    if ($record) {
                        DB::connection('sqlsrv_ris')
                            ->table('ris_in')
                            ->where('id', $record->id)
                            ->update([
                                'nama_user_radiografer' => $valueBrid->nama_radiografer,
                                'kode_user_radiografer' => strval($valueBrid->id_radiografer),
                            ]);
                        DB::commit();
                    }

                    // DB::connection('sqlsrv_ris')->table('ris_in')->where('no_register',$params_noregitrasi)->where('kode_pemeriksaan',$valueBrid->id_produk)
                    // ->update(
                    //     ['nama_user_radiografer' => $valueBrid->nama_radiografer],
                    //     ['kode_user_radiografer' => strval($valueBrid->id_radiografer)]
                    // );
                    else
                    {
                        DB::rollBack();
                        $result = array(
                            "status" => 400,
                            "result" => 'Unpexted Error',
                            "message" =>'No record Updated'
                        );
                    }
            }
            else{
                DB::rollBack();
                $result = array(
                    "status" => 400,
                    "result" => 'Unpexted Error',
                    "message" =>'No record Updated'
                );
            }           
           }
           else{
           
                $newGenerate=new PelayananPasienPetugas();
                // $newBRG->norec = $newBRG->generateNewId();
                $newGenerate->norec= $newGenerate->generateNewId();
                $newGenerate->kdprofile= $this->kdProfile;
                $newGenerate->nomasukfk= $params_norecAPd;
                // $newGenerate->objectpegawaifk= ;                 //HARUS DICARI SOLUSINYA
                // $newGenerate->pegawaiverifikatorfk= $newGenerate->generateNewId();
                $newGenerate->radiograferfk= $params_radiografer;
                $newGenerate->tglpelayanan= date('Y-m-d H:i:s');
                $newGenerate->objectjenispetugaspefk= $this->settingFix('idDokterPemeriksa');
                $newGenerate->pelayananpasien= $params_pp;
                $newGenerate->noregistrasi= $params_noregitrasi;

                $newGenerate->save();

                DB::commit();

                if($newGenerate){
                    $valueBrid=DB::table('pelayananpasien_t as pp')
                    ->join('produk_m as prd','prd.id','=','pp.produkfk')
                    ->join('pelayananpasienpetugas_t as aptg','aptg.pelayananpasien','=','pp.norec')
                    ->join('pegawai_m as pgw','pgw.id','=','aptg.radiograferfk')
                    ->where('pp.norec',$params_pp)
                    ->where('pp.statusenabled',true)
                    ->select(
                        'pp.norec',
                        'prd.id as id_produk',
                        'prd.namaproduk',
                        'pgw.id as id_radiografer',
                        'pgw.namalengkap as nama_radiografer'
                    )
                    ->first();

                    $updaterows=DB::connection('sqlsrv_ris')->table('ris_in')->where('no_register',$params_noregitrasi)->where('kode_pemeriksaan',$valueBrid->id_produk)
                    ->update(
                        ['nama_user_radiografer' => $valueBrid->nama_radiografer],
                        ['kode_user_radiografer' => $valueBrid->id_radiografer]
                    );
                    if($updaterows){
                        DB::commit();
                    }
                }
           }
            
            $result = array(
                "status" => 200,
                // "result" => $data,
                "message" => "Update berhasil",
                // "databrid" => $bridRis
            );
        } catch (Exception $e) {
            DB::rollBack();
            $result = array(
                "status" => 400,
                "result" => 'Unpexted Error',
                "message" => $e->getMessage().' '.$e->getLine()
            );
        }
        return $this->respond($result,$result['status'],$result['message']);
    }
    public function saveBridgingPacs2(Request $request)
    {
        ini_set('max_execution_time', 60000);
        $kdProfile = $this->kdProfile;
        $noorder = $request['noorder'];
        $noregistrasi = $request['noregistrasi'];
        DB::beginTransaction();
        try {
            $data = DB::connection('sqlsrv_ris')
                ->table('ris_in')
                ->get();

            DB::commit();
            $result = array(
                "status" => 200,
                "message" => "Sukses",
                "data" => $data,
            );
        } catch (Exception $e) {
            DB::rollBack();
            $result = array(
                "status" => 400,
                "message" => "Simpan Gagal !",
                "data"  => $e->getMessage() . $e->getLine()
            );
        }
        return $this->respond($result['data'], $result['status'], $result['message']);
    }

    public function saveBridgingVansLab(Request $request)
    {
        $kdProfile = $this->kdProfile;
        $noorder = $request['noorder'];
        $noregistrasi = $request['noregistrasi'];
        DB::beginTransaction();
        try {
            // $data = DB::connection('sqlsrv_lis')
            //     ->table('LIS_ORDER')
            //     ->get();


            $header = DB::select(DB::raw("
                SELECT ps.nocm, ps.namapasien, alm.alamatlengkap, ps.nohp, kp.kelompokpasien, regexp_replace(ps.tgllahir::varchar, '[^\w]+','','g') || '000000' as tgllahir,   to_char(so.tglorder, 'YYYYMMDDHHmmss') as tglorder,
                jk.id idjeniskelamin, ruso.id as idruanganpengirim, ruso.namaruangan as ruanganpengirim, pg.id as iddokterpengirim, pg.namalengkap as dokterpengirim,
                km.namakamar, string_agg('LAB'||prd.id::varchar, '^') as idproduk, rusot.id as idruangantujuan, so.noorder, so.cito
                FROM pasiendaftar_t AS pd
                INNER JOIN pasien_m AS ps ON ps.id = pd.nocmfk
                INNER JOIN strukorder_t AS so ON pd.norec = so.noregistrasifk
                INNER JOIN kelompokpasien_m AS kp ON kp.id = pd.objectkelompokpasienlastfk
                INNER JOIN ruangan_m AS ru ON ru.id = pd.objectruanganlastfk
                INNER JOIN antrianpasiendiperiksa_t as apd on apd.norec = so.norec_apd
                INNER JOIN orderpelayanan_t AS op ON so.norec = op.strukorderfk
                INNER JOIN produk_m AS prd ON prd.id = op.objectprodukfk
                LEFT JOIN ruangan_m AS ruso ON ruso.id = so.objectruanganfk
                LEFT JOIN ruangan_m AS rusot ON rusot.id = so.objectruangantujuanfk
                LEFT JOIN kamar_m as km ON km.id = apd.objectkamarfk
                LEFT JOIN alamat_m as alm on alm.nocmfk = ps.id
                LEFT JOIN jeniskelamin_m AS jk ON jk.id = ps.objectjeniskelaminfk
                LEFT JOIN pegawai_m AS pg ON pg.id = so.objectpegawaiorderfk
                LEFT JOIN departemen_m AS dp ON dp.id = ru.objectdepartemenfk
                WHERE so.noorder = '$noorder'
                AND pd.noregistrasi = '$noregistrasi'
                AND so.kdprofile = $kdProfile
                AND so.statusenabled = true
                AND op.statusenabled = true
                group by ps.nocm, ps.namapasien, alm.alamatlengkap, ps.nohp, kp.kelompokpasien, regexp_replace(ps.tgllahir::varchar, '[^\w]+','','g') || '000000',
                jk.id, ruso.id, ruso.namaruangan, pg.id, pg.namalengkap, km.namakamar,  to_char(so.tglorder, 'YYYYMMDDHHmmss'), rusot.id, so.noorder, so.cito
            "));

            // var_dump($header);

            $dokterverif = DB::select(DB::raw("select id,notlp,nohandphone from pegawai_m where id = ".$request['iddokterverif']));

            $kodelab = '';
            $ONO = '';
            if($header[0]->idruangantujuan == 335){
                $kodelab = 'LABPK';
            } else if($header[0]->idruangantujuan == 336){
                $kodelab = 'LABPA';
            } else if($header[0]->idruangantujuan == 337){
                $kodelab = 'LABMK';
            }
            $prefix = date('ymd').$kodelab.'-';
            $maxono = DB::connection('sqlsrv_lis')
                ->table('LIS_ORDER')
                ->where('ONO', 'LIKE', $prefix . '%')
                ->max('ONO');
            $prefixLen = strlen($prefix);
            $subPrefix = substr(trim($maxono), $prefixLen);
            $ONO = $prefix . (str_pad((int)$subPrefix + 1, 6, "0", STR_PAD_LEFT));
            $maxid = DB::connection('sqlsrv_lis')
                ->table('LIS_ORDER')
                ->max('id');
            $maxidLOG = DB::connection('sqlsrv_lis')
                ->table('LISORDERS_LOG')
                ->max('id');

            // $max = $maxidLOG + 1;

            // var_dump($max);

            // DB::connection('sqlsrv_lis')
            // ->table('LISORDERS_LOG')
            // ->insert([
            //     'ID' =>  $max,
            //     'MESSAGE_DT' => $header[0]->tglorder,
            //     'ORDER_CONTROL' => 'NW',
            //     'PID' => $header[0]->nocm,
            //     'PNAME' => $header[0]->namapasien,
            //     'ADDRESS1' => $header[0]->alamatlengkap,
            //     'ADDRESS2' => $request['namadokterverif'],
            //     'ADDRESS3' => $dokterverif[0]->notlp,
            //     'ADDRESS4' => $header[0]->kelompokpasien,
            //     'PTYPE' => 'OP',
            //     'BIRTH_DT' => $header[0]->tgllahir,
            //     'SEX' => $header[0]->idjeniskelamin,
            //     'ONO' => $ONO,
            //     'REQUEST_DT' => date('YmdHms'),
            //     'SOURCE' => $header[0]->idruanganpengirim.'^'.$header[0]->ruanganpengirim,
            //     'CLINICIAN' => $header[0]->iddokterpengirim.'^'.$header[0]->dokterpengirim,
            //     'ROOM_NO' => $header[0]->namakamar,
            //     'PRIORITY' => 'R',
            //     'CMT' => $request['catatan_klinis'],
            //     'VISITNO' => null,
            //     'ORDER_TESTID' => $header[0]->idproduk,
            // ]);

            /**
             * mekanisme lis yang di pakai saat ini adalah memakai skema insert entah itu edit produk atau pun hapus produk tetap memakai insert 
             * karna di aplikasi lis ketika masuk datanya akan secara otomatis di porses oleh aplikasi dan data di db table LIS_ORDER akan secara otomatasi terhapus dan masuk ke table ORDERS_LIS_log disnaa adalah riwayat order nya
             */


            $data = DB::connection('sqlsrv_lis')
                ->table('LIS_ORDER')                    //hanya digunakan untuk db development aja kalo db live optional 
                ->where('ONO', '=', $noorder)
                ->delete();
            
            DB::connection('sqlsrv_lis')->unprepared('SET IDENTITY_INSERT LIS_ORDER ON');

            DB::connection('sqlsrv_lis')
            ->table('LIS_ORDER')
            ->insert([
                'ID' =>  $maxidLOG + 1,
                // 'MESSAGE_DT' => $header[0]->tglorder,
                // 'MESSAGE_DT' => date('YmdHms'),      //old method time
                'MESSAGE_DT' => date('YmdHis'),         //new method time
                'ORDER_CONTROL' => 'NW',
                'PID' => $header[0]->nocm,
                'PNAME' => $header[0]->namapasien,
                'ADDRESS1' => $header[0]->alamatlengkap, //alamat
                'ADDRESS2' => $request['namadokterverif'], //dokter verif
                'ADDRESS3' => $dokterverif[0]->notlp != null ? str_replace(' ', '',$dokterverif[0]->notlp) : str_replace(' ', '',$dokterverif[0]->nohandphone) , //notelpon
                'ADDRESS4' => $header[0]->kelompokpasien,  //kelompok pasien
                'PTYPE' => 'OP',
                'BIRTH_DT' => $header[0]->tgllahir,
                'SEX' => $header[0]->idjeniskelamin,
                'ONO' => $header[0]->noorder,
                // 'REQUEST_DT' => date('YmdHms'),      //old method time
                'REQUEST_DT' => date('YmdHis'),         //new method time
                'SOURCE' => $header[0]->idruanganpengirim.'^'.$header[0]->ruanganpengirim,
                'CLINICIAN' => $header[0]->iddokterpengirim.'^'.$header[0]->dokterpengirim,
                // 'ROOM_NO' =>  preg_replace('/\s+/', '',substr($header[0]->namakamar, 15)),
                'ROOM_NO' =>  substr($header[0]->namakamar,0,15),
                'PRIORITY' => isset($header[0]->cito) && $header[0]->cito == true ? 'U' : 'R',
                'CMT' => $request['catatan_klinis'],
                'VISITNO' => null,
                'ORDER_TESTID' => $header[0]->idproduk,
            ]);

            DB::connection('sqlsrv_lis')->unprepared('SET IDENTITY_INSERT LIS_ORDER OFF');

            //DB::connection('sqlsrv_lis')->unprepared('SET IDENTITY_INSERT LISORDERS_LOG ON');

            

            //DB::connection('sqlsrv_lis')->unprepared('SET IDENTITY_INSERT LISORDERS_LOG OFF');


                //dd($data);
            // if(isset($request['details']))
            // {
            //     $struk = StrukOrder::where('noorder', $noorder)->where('kdprofile',$kdProfile)->where('statusenabled',true)->first();
            //     OrderPelayanan::where('strukorderfk', $struk->norec)->delete();
            //     foreach ($request['details'] as $item) {
            //         $dataOP = new OrderPelayanan();
            //         $dataOP->norec = $dataOP->generateNewId();
            //         $dataOP->kdprofile = $kdProfile;
            //         $dataOP->statusenabled = true;
            //         if(isset($item['iscito'])){
            //             $dataOP->iscito = (float)$item['iscito'];
            //         }else{
            //             $dataOP->iscito = 0;
            //         }
            //         $dataOP->noorderfk = $struk->norec;
            //         $dataOP->objectprodukfk = $item['idProduk'];
            //         $dataOP->qtyproduk = $item['jumlah'];
            //         $dataOP->objectkelasfk = $request['objectkelasfk'];
            //         $dataOP->qtyprodukretur = 0;
            //         $dataOP->objectruanganfk = $struk->objectruanganfk;
            //         $dataOP->objectruangantujuanfk = $struk->objectruangantujuanfk;
            //         $dataOP->strukorderfk = $struk->norec;
            //         $dataOP->tglpelayanan = date('Y-m-d H:i:s');
            //         $dataOP->objectnamapenyerahbarangfk = $struk->objectpegawaiorderfk;
            //         $dataOP->noregistrasi = $request['noregistrasi'];
            //         $dataOP->ihs_id =  isset($item['ihs_service_request'])?$item['ihs_service_request']:null;
            //         $dataOP->save();
            //     }
            // }

            // $raw = DB::select(DB::raw("
            //     SELECT pd.norec as norec_pd
            //     ,pd.noregistrasi
            //     ,op.norec
            //     ,op.iscito AS cito
            //     ,so.tglorder
            //     ,prd.id AS produkid
            //     ,prd.namaproduk
            //     ,ps.nocm
            //     ,so.noorder
            //     ,ps.namapasien
            //     ,ps.tgllahir
            //     ,jk.id AS jkid
            //     ,jk.jeniskelamin
            //     ,alm.alamatlengkap
            //     ,date_part('year', age(ps.tgllahir)) || ' Thn' as usia
            //     ,ru.id AS ruanganid
            //     ,ru.namaruangan
            //     ,dp.id AS departemenid
            //     ,CASE WHEN pg.id IS NULL THEN pg.id ELSE pg2.id END AS pgid
            //     ,CASE WHEN pg.namalengkap IS NULL THEN pg.namalengkap ELSE pg2.namalengkap END AS namalengkap
            //     ,kp.id AS kode_cara_bayar
            //     ,kp.kelompokpasien AS cara_bayar
            //     ,ru2.id AS idruangantujuan
            //     ,ru2.namaruangan AS ruangantujuan
            //     ,ps.noidentitas as nik
            //     FROM orderpelayanan_t AS op
            //     INNER JOIN strukorder_t AS so ON so.norec = op.strukorderfk
            //     INNER JOIN pasiendaftar_t AS pd ON pd.norec = so.noregistrasifk
            //     INNER JOIN produk_m AS prd ON prd.id = op.objectprodukfk
            //     INNER JOIN pasien_m AS ps ON ps.id = so.nocmfk
            //     LEFT JOIN alamat_m as alm on alm.nocmfk = ps.id
            //     INNER JOIN kelompokpasien_m AS kp ON kp.id = pd.objectkelompokpasienlastfk
            //     INNER JOIN ruangan_m AS ru ON ru.id = pd.objectruanganlastfk
            //     INNER JOIN ruangan_m AS ru2 ON ru2.id = so.objectruangantujuanfk
            //     LEFT JOIN jeniskelamin_m AS jk ON jk.id = ps.objectjeniskelaminfk
            //     LEFT JOIN pegawai_m AS pg ON pg.id = so.objectpegawaiorderfk
            //     LEFT JOIN pegawai_m AS pg2 ON pg2.id = pd.objectpegawaifk
            //     LEFT JOIN departemen_m AS dp ON dp.id = ru.objectdepartemenfk
            //     WHERE so.noorder = '$noorder'
            //     AND so.kdprofile = $kdProfile
            //     AND so.statusenabled = true
            // "));

            // StrukOrder::where('noorder',$noorder)
            // ->where('kdprofile', $kdProfile)
            // ->update(
            //     [
            //         'statusorder' => 1
            //     ]
            // );

            // foreach($raw as $item)
            // {
            //     if ($item->jkid == 1)
            //         $jk = 'L';
            //     else
            //         $jk =
            //     'P';

            //     if ($item->cito == '1')
            //         $priority = 'U';
            //     else
            //         $priority = 'R';

            //     $deptRanap = explode (',', $this->settingDataFixed('kdDepartemenRanapFix',$kdProfile));
            //     $jenisRawat = '';
            //     foreach ($deptRanap as $itemRanap){
            //         if((int)$itemRanap == $item->departemenid){
            //             $jenisRawat = 'RANAP';
            //             break;
            //         }else{
            //             $jenisRawat = 'RAJAL';
            //             break;
            //         }
            //     }

            //     $harga =  HargaNettoProdukByKelas::where('objectprodukfk', $item->produkid)
            //     ->where('kdprofile', $kdProfile)
            //     ->where('objectkelasfk', 2)
            //     ->where('statusenabled', true)
            //     ->first();

            //     $cek = OrderLab::where('no_lab',$noorder)->where('kode_test', $item->produkid)->first();
            //     if(!empty($cek)){
            //         OrderLab::where('no_lab',$noorder)->where('kode_test', $item->produkid)->delete();
            //     }
            //     $newBRG = new OrderLab();
            //     $newBRG->norec = $newBRG->generateNewId();
            //     $newBRG->asal_lab = $item->ruangantujuan;// $jenisRawat;
            //     $newBRG->no_lab = $item->noorder;
            //     $newBRG->no_registrasi = $item->noregistrasi;
            //     $newBRG->no_rm = $item->nocm;
            //     $newBRG->tgl_order = $item->tglorder;
            //     $newBRG->nama_pas = $item->namapasien;
            //     $newBRG->jenis_kel = $jk;
            //     $newBRG->tgl_lahir = $item->tgllahir;
            //     $newBRG->usia = $item->usia;
            //     $newBRG->alamat = $item->alamatlengkap;
            //     $newBRG->kode_dok_kirim = $request['iddokterverif'];
            //     $newBRG->nama_dok_kirim = substr($request['namadokterverif'], 0, 30);
            //     $newBRG->kode_ruang = $item->ruanganid;
            //     $newBRG->nama_ruang = $item->namaruangan;
            //     $newBRG->kode_cara_bayar = $item->kode_cara_bayar;
            //     $newBRG->cara_bayar = $item->cara_bayar;
            //     if(isset($request['catatan_klinis']))
            //     {
            //         $newBRG->ket_klinis = $request['catatan_klinis'];
            //     }
            //     $newBRG->kode_test = $item->produkid;
            //     $newBRG->test = $item->namaproduk;
            //     $newBRG->Harga = (float)$harga->hargasatuan;
            //     $newBRG->waktu_kirim = date('Y-m-d H:i:s');
            //     $newBRG->prioritas = $priority;
            //     $newBRG->jns_rawat = $jenisRawat;
            //     $newBRG->dok_jaga = $item->namalengkap;
            //     $newBRG->status = 0;
            //     $newBRG->NIK = $item->nik;
            //     $newBRG->Jumlah_test = count($raw);
            //     $newBRG->save();
            // }

            $data = DB::connection('sqlsrv_lis')
                ->table('LIS_ORDER')
                ->where('ONO', '=', $noorder)
                ->get();

            DB::commit();
            $result = array(
                "status" => 200,
                "message" => "Sukses",
                "data" => $data
            );
        } catch (Exception $e) {
            DB::rollBack();
            $result = array(
                "status" => 400,
                "message" => "Simpan Gagal Vanslab gagal !",
                "data"  => $e->getMessage() . $e->getLine()
            );
        }
        return $this->respond($result['data'], $result['status'], $result['message']);
    }

    public function saveValueEditBridgingVansLab(Request $request){

        $idProfile = (int) $this->kdProfile;
        $barange = $request['bridging'];
        $norec_pp =$request['norec_pp'];

        DB::beginTransaction();
        try {
            // $cekdulugan=DB::table('strukorder_t as so')->join('pasiendaftar_t as pd','pd.norec','=','so.noregistrasifk')
            // ->where('pd.noregistrasi',$request['noregistrasi'])->where('so.noorder',$request['noorder'])->where('so.statusenabled',true)
            // ->select(
            //     'so.norec',
            //     'pd.noregistrasi',
            //     'so.qtyproduk'
            //     )
            // ->first();

            // $compareDataOP=OrderPelayanan::where('strukorderfk',$cekdulugan->norec)->where('statusenabled',true)->select('norec','objectprodukfk')->get();

            // if(!empty($cekdulugan)){
            //     foreach($compareDataOP as $dataOpfirst){
            //         foreach ($barange as  $cantikku) {
            //             if($dataOpfirst->objectprodukfk != $cantikku['produkfk']){
            //                 $OP=new OrderPelayanan();
            //                 $OP->norec=$OP->generateNewId();
            //                 $OP->kdprofile=$idProfile;
            //                 $OP->statusenabled=true;
            //                 $OP->iscito=0;
            //                 $OP->objectkelasfk=$request['objectkelasfk'];
            //                 $OP->noorderfk=$cekdulugan->norec;
            //                 $OP->qtyproduk=1;
            //                 $OP->qtyprodukretur=0;
            //                 $OP->objectruanganfk=$request['objectruanganfk'];
            //                 $OP->objectruangantujuanfk=$request['objectruangantujuanfk'];
            //                 $OP->strukorderfk=$cekdulugan->norec;
            //                 $OP->tglpelayanan=date('Y-m-d H:i:s');
            //                 $OP->noregistrasi=$cekdulugan->noregistrasi;
            //                 $OP->objectprodukfk = $cantikku['produkfk'];
            //                 $OP->save();
            //             }
                        
            //         }
            //         $kuduUpdateSO=DB::table('strukorder_t')->where('norec',$cekdulugan->norec)->update([
            //             'qtyproduk'=>$cekdulugan->qtyproduk + count($barange)
            //         ]);
            //         $kuduUpdatePP=DB::table('pelayananpasien_t')->whereIn('norec',$norec_pp)->update([
            //             'strukorderfk'=>$cekdulugan->norec
            //         ]);
            //         DB::commit();
            //         $result = array(
            //             "status" => 200,
            //             "message" => "Sukses",
            //             "data" => $OP,
            //             "updateSO" => $kuduUpdateSO,
            //             "updatePP" => $kuduUpdatePP
            //         );
            //     }
            // }


            $cekdulugan = DB::table('strukorder_t as so')
                ->join('pasiendaftar_t as pd', 'pd.norec', '=', 'so.noregistrasifk')
                ->where('pd.noregistrasi', $request['noregistrasi'])
                ->where('so.noorder', $request['noorder'])
                ->where('so.statusenabled', true)
                ->select('so.norec', 'pd.noregistrasi', 'so.qtyproduk')
                ->first();
    
            if (!$cekdulugan) {
                return response()->json([
                    "status" => 404,
                    "message" => "Data struk order tidak ditemukan",
                ]);
            }
    
            $compareDataOP = OrderPelayanan::where('strukorderfk', $cekdulugan->norec)
                ->where('statusenabled', true)
                ->select('norec', 'objectprodukfk')
                ->get()
                ->pluck('objectprodukfk')
                ->toArray();
        
           
            foreach ($barange as $cantikku) {
                if (!in_array($cantikku['produkfk'], $compareDataOP)) {
                    $OP = new OrderPelayanan();
                    $OP->norec = $OP->generateNewId();
                    $OP->kdprofile = $idProfile;
                    $OP->statusenabled = true;
                    $OP->iscito = 0;
                    $OP->objectkelasfk = $request['objectkelasfk'];
                    $OP->noorderfk = $cekdulugan->norec;
                    $OP->qtyproduk = 1;
                    $OP->qtyprodukretur = 0;
                    $OP->objectruanganfk = $request['objectruanganfk'];
                    $OP->objectruangantujuanfk = $request['objectruangantujuanfk'];
                    $OP->strukorderfk = $cekdulugan->norec;
                    $OP->tglpelayanan = date('Y-m-d H:i:s');
                    $OP->noregistrasi = $cekdulugan->noregistrasi;
                    $OP->objectprodukfk = $cantikku['produkfk'];
                    $OP->save();
                }
            }

            $kuduUpdateSO=DB::table('strukorder_t')->where('norec',$cekdulugan->norec)->update([
                'qtyproduk'=>$cekdulugan->qtyproduk + count($barange)
            ]);
            $kuduUpdatePP=DB::table('pelayananpasien_t')->whereIn('norec',$norec_pp)->update([
                'strukorderfk'=>$cekdulugan->norec
            ]);
            DB::commit();
            $result = array(
                "status" => 200,
                "message" => "Sukses",
                "data" => !empty($OP) ? $OP : null,
                "updateSO" => $kuduUpdateSO,
                "updatePP" => $kuduUpdatePP
            );
                // }
            // }
            // else{
            //     DB::rollBack();
            //     $result = array(
            //         "status" => 400,
            //         "message" => "Data not update",
            //         "hint" => "data di strukorder tidak ada atau ter false status nya"
            //     );
            // }
            
        } catch (Exception $e) {
            DB::rollBack();
            $result=array(
                "status" => 400,
                "message" => "Simpan Gagal!",
                "data"  => $e->getMessage() . $e->getLine()
            );
        }
        return $this->respond($result);
    }

    public function editBridgingVansLab(Request $request){
        $kdProfile = $this->kdProfile;
        $noorder = $request['noorder'];
        $noregistrasi = $request['noregistrasi'];
        DB::beginTransaction();
        try {

            $header = DB::select(DB::raw("
                SELECT ps.nocm, ps.namapasien,so.norec as norec_so, alm.alamatlengkap, ps.nohp,so.catatanklinis, kp.kelompokpasien, regexp_replace(ps.tgllahir::varchar, '[^\w]+','','g') || '000000' as tgllahir,  to_char(so.tglorder, 'YYYYMMDDHHmmss') as tglorder,
                jk.id idjeniskelamin, ruso.id as idruanganpengirim, ruso.namaruangan as ruanganpengirim, pg.id as iddokterpengirim, pg.namalengkap as dokterpengirim,
                km.namakamar, string_agg('LAB'||prd.id::varchar, '^') as idproduk, rusot.id as idruangantujuan, so.noorder
                FROM pasiendaftar_t AS pd
                left JOIN pasien_m AS ps ON ps.id = pd.nocmfk
                left JOIN strukorder_t AS so ON pd.norec = so.noregistrasifk
                left JOIN kelompokpasien_m AS kp ON kp.id = pd.objectkelompokpasienlastfk
                left JOIN ruangan_m AS ru ON ru.id = pd.objectruanganlastfk
                left JOIN antrianpasiendiperiksa_t as apd on apd.norec = so.norec_apd
                left JOIN orderpelayanan_t AS op ON so.norec = op.strukorderfk
                left JOIN produk_m AS prd ON prd.id = op.objectprodukfk
                LEFT JOIN ruangan_m AS ruso ON ruso.id = so.objectruanganfk
                LEFT JOIN ruangan_m AS rusot ON rusot.id = so.objectruangantujuanfk
                LEFT JOIN kamar_m as km ON km.id = apd.objectkamarfk
                LEFT JOIN alamat_m as alm on alm.nocmfk = ps.id
                LEFT JOIN jeniskelamin_m AS jk ON jk.id = ps.objectjeniskelaminfk
                LEFT JOIN pegawai_m AS pg ON pg.id = so.objectpegawaiorderfk
                LEFT JOIN departemen_m AS dp ON dp.id = ru.objectdepartemenfk
                WHERE so.noorder = '$noorder'
                AND pd.noregistrasi = '$noregistrasi'
                AND so.kdprofile = $kdProfile
                AND so.statusenabled = true
                AND op.statusenabled = true
                group by ps.nocm, ps.namapasien, alm.alamatlengkap,so.catatanklinis,so.norec, ps.nohp, kp.kelompokpasien, regexp_replace(ps.tgllahir::varchar, '[^\w]+','','g') || '000000',
                jk.id, ruso.id, ruso.namaruangan, pg.id, pg.namalengkap, km.namakamar, to_char(so.tglorder, 'YYYYMMDDHHmmss'), rusot.id, so.noorder
            "));

            // var_dump($header);

            $getvalueop=DB::table('strukorder_t as so')->join('orderpelayanan_t as op','op.strukorderfk','=','so.norec')->where('so.noorder',$noorder)->select('op.norec')->get();


            $getDokterVerif=DB::table('pelayananpasien_t as pp')
            ->join('strukorder_t as so','so.norec','=','pp.strukorderfk') 
            ->join('pelayananpasienpetugas_t as ptu','ptu.pelayananpasien','=','pp.norec')
            ->where('so.norec',$header[0]->norec_so)
            ->where('so.noorder',$noorder)
            ->select(
                'so.norec as norec_so',
                'pp.norec as norec_pp',
                'ptu.objectpegawaifk as iddokterverif'
            )
            ->first();

            // $dokterverif=DB::table('pegawai_m')->where('id',$getDokterVerif->iddokterverif)
            // ->select(
            //     'pgw.namalengkap',
            //     'pgw.nohandphone',
            //     'pgw.notlp'
            // )
            // ->first();

            $dokterverif=DB::table('pegawai_m')->where('id',$getDokterVerif->iddokterverif)
            ->select(
                'namalengkap',
                'nohandphone',
                'notlp'
            )
            ->get();

            $kodelab = '';
            $ONO = '';
            if($header[0]->idruangantujuan == 335){
                $kodelab = 'LABPK';
            } else if($header[0]->idruangantujuan == 336){
                $kodelab = 'LABPA';
            } else if($header[0]->idruangantujuan == 337){
                $kodelab = 'LABMK';
            }
            $prefix = date('ymd').$kodelab.'-';
            $maxono = DB::connection('sqlsrv_lis')
                ->table('LIS_ORDER')
                ->where('ONO', 'LIKE', $prefix . '%')
                ->max('ONO');
            $prefixLen = strlen($prefix);
            $subPrefix = substr(trim($maxono), $prefixLen);
            $ONO = $prefix . (str_pad((int)$subPrefix + 1, 6, "0", STR_PAD_LEFT));
            $maxid = DB::connection('sqlsrv_lis')
                ->table('LIS_ORDER')
                ->max('id');
            $maxidLOG = DB::connection('sqlsrv_lis')
                ->table('LISORDERS_LOG')
                ->max('id');


            // $data = DB::connection('sqlsrv_lis')             //hanya digunakan untuk lis development
            //     ->table('LIS_ORDER')
            //     ->where('ONO', '=', $noorder)
            //     ->delete();
            
            DB::connection('sqlsrv_lis')->unprepared('SET IDENTITY_INSERT LIS_ORDER ON');


            DB::connection('sqlsrv_lis')
            ->table('LIS_ORDER')
            ->insert([
                'ID' =>  $maxidLOG + 1,
                // 'MESSAGE_DT' => $header[0]->tglorder,
                // 'MESSAGE_DT' => date('YmdHms'),      //old method time
                'MESSAGE_DT' => date('YmdHis'),         //new method time
                'ORDER_CONTROL' =>  count($getvalueop) == 1 ? 'CA':'RP',
                'PID' => $header[0]->nocm,
                'PNAME' => $header[0]->namapasien,

                //format dari vendor
                // 'ADDRESS1' => $header[0]->alamatlengkap, //alamat
                // 'ADDRESS2' => $request['namadokterverif'], //dokter verif
                // 'ADDRESS3' => $dokterverif[0]->notlp, //notelpon
                // 'ADDRESS4' => $header[0]->kelompokpasien,  //kelompok pasien


                'ADDRESS1' => $header[0]->alamatlengkap,
                'ADDRESS2' => $dokterverif[0]->namalengkap,
                'ADDRESS3' => $dokterverif[0]->notlp != null ? str_replace(' ', '',$dokterverif[0]->notlp) : str_replace(' ', '',$dokterverif[0]->nohandphone),                //cek lagi kiriman parameter api
                'ADDRESS4' => $header[0]->kelompokpasien,
                'PTYPE' => 'OP',
                'BIRTH_DT' => $header[0]->tgllahir,
                'SEX' => $header[0]->idjeniskelamin,
                'ONO' => $header[0]->noorder,
                // 'REQUEST_DT' => date('YmdHms'),      //old method time
                'REQUEST_DT' => date('YmdHis'),         //new method time
                'SOURCE' => $header[0]->idruanganpengirim.'^'.$header[0]->ruanganpengirim,
                'CLINICIAN' => $header[0]->iddokterpengirim.'^'.$header[0]->dokterpengirim,
                'ROOM_NO' => substr($header[0]->namakamar,0,15),
                'PRIORITY' => 'R',
                'CMT' => $header[0]->catatanklinis,
                'VISITNO' => null,
                'ORDER_TESTID' => $header[0]->idproduk,
            ]);

            DB::connection('sqlsrv_lis')->unprepared('SET IDENTITY_INSERT LIS_ORDER OFF');

            $data = DB::connection('sqlsrv_lis')
                ->table('LIS_ORDER')
                ->where('ONO', '=', $noorder)
                ->get();

            DB::commit();
            $result = array(
                "status" => 200,
                "message" => "Sukses",
                "data" => $data
            );
        } catch (Exception $e) {
            DB::rollBack();
            $result = array(
                "status" => 400,
                "message" => "Simpan Gagal Vanslab gagal !",
                "data"  => $e->getMessage() . $e->getLine()
            );
        }
        return $this->respond($result['data'], $result['status'], $result['message']);
    }
    public function deleteLabBriding(Request $req){
        DB::beginTransaction();
        try {
            $noorder=$req['noorder'];
            $kdProfile = $this->kdProfile;

            $header = DB::select(DB::raw("
            SELECT ps.nocm, ps.namapasien,so.norec as norec_so, alm.alamatlengkap, ps.nohp,so.catatanklinis, kp.kelompokpasien, regexp_replace(ps.tgllahir::varchar, '[^\w]+','','g') || '000000' as tgllahir,  to_char(so.tglorder, 'YYYYMMDDHHmmss') as tglorder,
            jk.id idjeniskelamin, ruso.id as idruanganpengirim, ruso.namaruangan as ruanganpengirim, pg.id as iddokterpengirim, pg.namalengkap as dokterpengirim,
            km.namakamar, string_agg('LAB'||prd.id::varchar, '^') as idproduk, rusot.id as idruangantujuan, so.noorder
            FROM pasiendaftar_t AS pd
            left JOIN pasien_m AS ps ON ps.id = pd.nocmfk
            left JOIN strukorder_t AS so ON pd.norec = so.noregistrasifk
            left JOIN kelompokpasien_m AS kp ON kp.id = pd.objectkelompokpasienlastfk
            left JOIN ruangan_m AS ru ON ru.id = pd.objectruanganlastfk
            left JOIN antrianpasiendiperiksa_t as apd on apd.norec = so.norec_apd
            left JOIN orderpelayanan_t AS op ON so.norec = op.strukorderfk
            left JOIN produk_m AS prd ON prd.id = op.objectprodukfk
            LEFT JOIN ruangan_m AS ruso ON ruso.id = so.objectruanganfk
            LEFT JOIN ruangan_m AS rusot ON rusot.id = so.objectruangantujuanfk
            LEFT JOIN kamar_m as km ON km.id = apd.objectkamarfk
            LEFT JOIN alamat_m as alm on alm.nocmfk = ps.id
            LEFT JOIN jeniskelamin_m AS jk ON jk.id = ps.objectjeniskelaminfk
            LEFT JOIN pegawai_m AS pg ON pg.id = so.objectpegawaiorderfk
            LEFT JOIN departemen_m AS dp ON dp.id = ru.objectdepartemenfk
            WHERE so.noorder = '$noorder'
            AND so.kdprofile = $kdProfile
            AND so.statusenabled = true
            AND op.statusenabled = true
            group by ps.nocm, ps.namapasien,so.norec,so.catatanklinis, alm.alamatlengkap, ps.nohp, kp.kelompokpasien, regexp_replace(ps.tgllahir::varchar, '[^\w]+','','g') || '000000',
            jk.id, ruso.id, ruso.namaruangan, pg.id, pg.namalengkap, km.namakamar, to_char(so.tglorder, 'YYYYMMDDHHmmss'), rusot.id, so.noorder
        "));
        
            // $data = DB::connection('sqlsrv_lis')
            //     ->table('LIS_ORDER')
            //     ->where('ONO', '=', $req['noorder'])
            //     ->update([
            //         'ORDER_CONTROL' => 'CA',
            //     ]);

            $kodelab = '';
            $ONO = '';
            if($header[0]->idruangantujuan == 335){
                $kodelab = 'LABPK';
            } else if($header[0]->idruangantujuan == 336){
                $kodelab = 'LABPA';
            } else if($header[0]->idruangantujuan == 337){
                $kodelab = 'LABMK';
            }
            $prefix = date('ymd').$kodelab.'-';
            $maxono = DB::connection('sqlsrv_lis')
                ->table('LIS_ORDER')
                ->where('ONO', 'LIKE', $prefix . '%')
                ->max('ONO');
            $prefixLen = strlen($prefix);
            $subPrefix = substr(trim($maxono), $prefixLen);
            $ONO = $prefix . (str_pad((int)$subPrefix + 1, 6, "0", STR_PAD_LEFT));
            $maxid = DB::connection('sqlsrv_lis')
                ->table('LIS_ORDER')
                ->max('id');
            $maxidLOG = DB::connection('sqlsrv_lis')
                ->table('LISORDERS_LOG')
                ->max('id');

            // $getID=DB::connection('sqlsrv_lis')
            // ->table('LISORDERS_LOG')
            // ->where('ONO',$header[0]->noorder)
            // ->where('PID',$header[0]->nocm)
            // ->select('ID')
            // ->orderByDesc('MESSAGE_DT')
            // ->first();

            // $getID=DB::connection('sqlsrv_lis')
            // ->table('LIS_ORDER')
            // ->where('ONO',$header[0]->noorder)
            // ->select('ID')
            // ->first();


            // $data = DB::connection('sqlsrv_lis')
            //     ->table('LIS_ORDER')
            //     ->where('ONO', '=', $noorder)
            //     ->delete();

            
            $getDokterVerif=DB::table('pelayananpasien_t as pp')
            ->join('strukorder_t as so','so.norec','=','pp.strukorderfk') 
            ->join('pelayananpasienpetugas_t as ptu','ptu.pelayananpasien','=','pp.norec')
            ->where('so.norec',$header[0]->norec_so)
            ->where('so.noorder',$noorder)
            ->select(
                'so.norec as norec_so,',
                'pp.norec as norec_pp',
                'ptu.objectpegawaifk as iddokterverif'
            )
            ->first();

            $dokterverif=DB::table('pegawai_m')->where('id',$getDokterVerif->iddokterverif)
            ->select(
                'namalengkap',
                'nohandphone',
                'notlp'
            )
            ->get();
            
            DB::connection('sqlsrv_lis')->unprepared('SET IDENTITY_INSERT LIS_ORDER ON');


            DB::connection('sqlsrv_lis')
            ->table('LIS_ORDER')
            ->insert([
                'ID' =>  $maxidLOG + 1,
                // 'MESSAGE_DT' => $header[0]->tglorder,
                // 'MESSAGE_DT' => date('YmdHms'),      //old method time
                'MESSAGE_DT' => date('YmdHis'),         //new method time
                'ORDER_CONTROL' => 'CA',
                'PID' => $header[0]->nocm,
                'PNAME' => $header[0]->namapasien,
                'ADDRESS1' => $header[0]->alamatlengkap,
                'ADDRESS2' => $dokterverif[0]->namalengkap,
                'ADDRESS3' => $dokterverif[0]->notlp != null ? str_replace(' ', '',$dokterverif[0]->notlp) : str_replace(' ', '',$dokterverif[0]->nohandphone),            //dari front end kriim parameter nya lagi
                'ADDRESS4' => $header[0]->kelompokpasien,
                'PTYPE' => 'OP',
                'BIRTH_DT' => $header[0]->tgllahir,
                'SEX' => $header[0]->idjeniskelamin,
                'ONO' => $header[0]->noorder,
                // 'REQUEST_DT' => date('YmdHms'),      //old method time
                'REQUEST_DT' => date('YmdHis'),         //new method time
                'SOURCE' => $header[0]->idruanganpengirim.'^'.$header[0]->ruanganpengirim,
                'CLINICIAN' => $header[0]->iddokterpengirim.'^'.$header[0]->dokterpengirim,
                'ROOM_NO' => substr($header[0]->namakamar,0,15),
                'PRIORITY' => 'R',
                'CMT' => $header[0]->catatanklinis,
                'VISITNO' => null,
                'ORDER_TESTID' => $header[0]->idproduk,
            ]);


            // DB::connection('sqlsrv_lis')
            // ->table('LIS_ORDER')
            // ->insert([
            //     'ID' =>  $maxidLOG + 1,
            //     // 'MESSAGE_DT' => $header[0]->tglorder,
            //     'MESSAGE_DT' => date('YmdHms'),
            //     'ORDER_CONTROL' => 'RP',
            //     'PID' => $header[0]->nocm,
            //     'PNAME' => $header[0]->namapasien,
            //     'ADDRESS1' => $header[0]->alamatlengkap,
            //     'ADDRESS2' => $request['namadokterverif'],
            //     // 'ADDRESS3' => $dokterverif[0]->notlp,
            //     'ADDRESS4' => $header[0]->kelompokpasien,
            //     'PTYPE' => 'OP',
            //     'BIRTH_DT' => $header[0]->tgllahir,
            //     'SEX' => $header[0]->idjeniskelamin,
            //     'ONO' => $header[0]->noorder,
            //     'REQUEST_DT' => date('YmdHms'),
            //     'SOURCE' => $header[0]->idruanganpengirim.'^'.$header[0]->ruanganpengirim,
            //     'CLINICIAN' => $header[0]->iddokterpengirim.'^'.$header[0]->dokterpengirim,
            //     'ROOM_NO' => $header[0]->namakamar,
            //     'PRIORITY' => 'R',
            //     'CMT' => $request['catatan_klinis'],
            //     'VISITNO' => null,
            //     'ORDER_TESTID' => $header[0]->idproduk,
            // ]);

            DB::connection('sqlsrv_lis')->unprepared('SET IDENTITY_INSERT LIS_ORDER OFF');

            $data = DB::connection('sqlsrv_lis')
                ->table('LIS_ORDER')
                ->where('ONO', '=', $noorder)
                ->get();
        
            
            if ($data) {
                DB::commit();
                $result = [
                    "status" => 200,
                    "message" => "Sukses",
                    "data" => $data
                ];
            } else {
                DB::rollBack();
                $result = [
                    "status" => 400,
                    "message" => "No records updated.",
                    "data" => null
                ];
            }
        } catch (Exception $e) {
            
            DB::rollBack();
            $result = [
                "status" => 400,
                "message" => "Update faileed!",
                "data" => $e->getMessage() . ' on line ' . $e->getLine()
            ];
        }
        
        return $this->respond($result['data']);
    }

    public function saveBridgingPacs(Request $request)
    {
        $dataLogin = $request->all();
        $kdProfile = $this->kdProfile;
        $noorder = $request['noorder'];
        $details =$request['details'];

        

        
        DB::beginTransaction();
        try {
            
            // if(isset($request['details'] )){
            //     $struk = StrukOrder::where('noorder', $noorder)->where('kdprofile', $kdProfile)->where('statusenabled', true)->first();
            //     OrderPelayanan::where('strukorderfk', $struk->norec)->delete();
            //     foreach ($request['details'] as $item) {
            //         $dataOP = new OrderPelayanan;
            //         $dataOP->norec = $dataOP->generateNewId();
            //         $dataOP->kdprofile = $kdProfile;
            //         $dataOP->statusenabled = true;
            //         if (isset($item['iscito'])) {
            //             $dataOP->iscito = (float) $item['iscito'];
            //         } else {
            //             $dataOP->iscito = 0;
            //         }

            //         $dataOP->noorderfk = $struk->norec;
            //         $dataOP->objectprodukfk = $item['produkfk'];
            //         $dataOP->qtyproduk = $item['qtyproduk'];
            //         $dataOP->objectkelasfk = $request['objectkelasfk'];
            //         $dataOP->qtyprodukretur = 0;
            //         $dataOP->objectruanganfk = $struk->objectruanganfk;
            //         $dataOP->objectruangantujuanfk = $struk->objectruangantujuanfk;
            //         $dataOP->strukorderfk = $struk->norec;
            //         if (isset($request['tglpelayanan'])) {
            //             $dataOP->tglpelayanan = $request['tglpelayanan'];
            //         } else {
            //             $dataOP->tglpelayanan = $struk->tglorder;
            //         }
            //         $dataOP->objectnamapenyerahbarangfk = $struk->objectpegawaiorderfk;
            //         $dataOP->nourut = isset($item['nourut'])?$item['nourut']:null;

            //         $dataOP->save();
            //     }
            // }

            

            // $raw = DB::select(DB::raw("select op.norec, op.kdprofile as kdprofile, ps.noidentitas,
            // op.iscito as cito, op.tglpelayanan, prd.id as produkid, op.nourut, prd.namaproduk,
            // ps.nocm, so.noorderintern, so.noorder, ps.namapasien, ps.tgllahir, jk.jeniskelamin,
            // alm.alamatlengkap, ru.id as ruanganid, ru.namaruangan, pd.noregistrasi, pd.norec as norec_pd, ps.nohp,
            // pg.id as pgid, pg.namalengkap, dp.id as departemenid ,prd.objectdetailjenisprodukfk,
            // kps.kelompokpasien,so.tglorder, gdr.golongandarah, prd.modality, pd.noregistrasi,
            // pro.namapropinsi, kk.namakotakabupaten, kt.namakecamatan, alm.kodepos, rke.id idrekanan, rke.namarekanan,
            // ru1.id as idruanganpengirim, ru1.namaruangan as ruanganpengirim,
            // ru2.id as idruangantujuan, ru2.namaruangan as ruangantujuan
            // FROM orderpelayanan_t as op
            // left join produk_m as prd on prd.id=op.objectprodukfk
            // left join detailjenisproduk_m as djp on djp.id=prd.objectdetailjenisprodukfk                     //DEFAULT
            // left join jenisproduk_m as jp on jp.id=djp.objectjenisprodukfk
            // left join strukorder_t as so on so.norec=op.strukorderfk
            // left join pasien_m as ps on ps.id =so.nocmfk
            // left join jeniskelamin_m as jk on jk.id=ps.objectjeniskelaminfk
            // left join alamat_m as alm on alm.nocmfk=ps.id
            // left join pasiendaftar_t as pd on pd.norec=so.noregistrasifk
            // left join kelompokpasien_m as kps on kps.id=pd.objectkelompokpasienlastfk
            // left join ruangan_m as ru on ru.id=pd.objectruanganlastfk
            // left join ruangan_m as ru1 on ru1.id=so.objectruanganfk
            // left join ruangan_m as ru2 on ru2.id=so.objectruangantujuanfk
            // left join pegawai_m as pg on pg.id=so.objectpegawaiorderfk
            // left join departemen_m as dp on dp.id=ru.objectdepartemenfk
            // left join golongandarah_m as gdr on gdr.id=ps.objectgolongandarahfk
            // left join propinsi_m as pro on pro.id = alm.objectpropinsifk
            // left join kotakabupaten_m as kk on kk.id = alm.objectkotakabupatenfk
            // left join kecamatan_m as kt on kt.id = alm.objectkecamatanfk
            // left join rekanan_m as rke on rke.id = pd.objectrekananfk
            // join antrianpasiendiperiksa_t as apd on apd.noregistrasifk = pd.norec
            // join pelayananpasien_t as pp on pp.noregistrasifk = apd.norec
            // where so.noorder = '$noorder'
            // and so.kdprofile = $kdProfile"));

            $raw = DB::select(DB::raw("
            SELECT 
                DISTINCT         
                    op.norec, op.kdprofile as kdprofile, ps.noidentitas,
                    op.iscito as cito, op.tglpelayanan, prd.id as produkid_tm, op.nourut, prd.namaproduk,
                    ps.nocm, so.noorderintern, so.noorder, ps.namapasien, ps.tgllahir, jk.jeniskelamin,
                    alm.alamatlengkap, ru.id as ruanganid, ru.namaruangan, pd.noregistrasi, pd.norec as norec_pd, ps.nohp,
                    pg.sanata_id as pgid, pg.namalengkap, dp.id as departemenid ,prd.objectdetailjenisprodukfk,
                    kps.kelompokpasien,so.tglorder, gdr.golongandarah, prd.sanata_kodepemeriksaan_rad as modality, pd.noregistrasi,
                    pro.namapropinsi, kk.namakotakabupaten, kt.namakecamatan, alm.kodepos, rke.id idrekanan, rke.namarekanan,
                    ru1.id as idruanganpengirim, ru1.namaruangan as ruanganpengirim,
                    ru2.id as idruangantujuan, ru2.namaruangan as ruangantujuan,pgwverif.namalengkap as namadokterverif, pgwverif.sanata_id as iddokterverif,prd.sanata_jasa_id as produkid
                    FROM orderpelayanan_t as op
                    left join produk_m as prd on prd.id=op.objectprodukfk
                    left join detailjenisproduk_m as djp on djp.id=prd.objectdetailjenisprodukfk
                    left join jenisproduk_m as jp on jp.id=djp.objectjenisprodukfk
                    left join strukorder_t as so on so.norec=op.strukorderfk
                    left join pasien_m as ps on ps.id =so.nocmfk
                    left join jeniskelamin_m as jk on jk.id=ps.objectjeniskelaminfk
                    left join alamat_m as alm on alm.nocmfk=ps.id
                    left join pasiendaftar_t as pd on pd.norec=so.noregistrasifk
                    left join kelompokpasien_m as kps on kps.id=pd.objectkelompokpasienlastfk
                    left join ruangan_m as ru on ru.id=pd.objectruanganlastfk
                    left join ruangan_m as ru1 on ru1.id=so.objectruanganfk
                    left join ruangan_m as ru2 on ru2.id=so.objectruangantujuanfk
                    left join pegawai_m as pg on pg.id=so.objectpegawaiorderfk
                    left join departemen_m as dp on dp.id=ru.objectdepartemenfk
                    left join golongandarah_m as gdr on gdr.id=ps.objectgolongandarahfk
                    left join propinsi_m as pro on pro.id = alm.objectpropinsifk
                    left join kotakabupaten_m as kk on kk.id = alm.objectkotakabupatenfk
                    left join kecamatan_m as kt on kt.id = alm.objectkecamatanfk
                    left join rekanan_m as rke on rke.id = pd.objectrekananfk
                    join antrianpasiendiperiksa_t as apd on apd.noregistrasifk = pd.norec
                    join pelayananpasien_t as pp on pp.noregistrasifk = apd.norec and pp.strukorderfk = so.norec and pp.produkfk = op.objectprodukfk
                    join pelayananpasienpetugas_t as ppg on ppg.pelayananpasien = pp.norec
                    join pegawai_m as pgwverif on ppg.objectpegawaifk =pgwverif.id 
                    where so.noorder = '$noorder'
                    and so.kdprofile = $kdProfile
				GROUP BY 
					op.norec, op.kdprofile, ps.noidentitas, op.iscito, op.tglpelayanan, prd.id, op.nourut, prd.namaproduk,
					ps.nocm, so.noorderintern, so.noorder, ps.namapasien, ps.tgllahir, jk.jeniskelamin, alm.alamatlengkap,
					ru.id, ru.namaruangan, pd.noregistrasi, pd.norec, ps.nohp, pg.id, pg.namalengkap, dp.id, 
					prd.objectdetailjenisprodukfk, kps.kelompokpasien, so.tglorder, gdr.golongandarah, prd.modality,
					pro.namapropinsi, kk.namakotakabupaten, kt.namakecamatan, alm.kodepos, rke.id, rke.namarekanan,
					ru1.id, ru1.namaruangan, ru2.id, ru2.namaruangan, pgwverif.namalengkap, pgwverif.id
				ORDER BY 
					so.noorder"));

                    // var_dump($raw);

            $errorrr =  "";
            $cek = null;
            $no = 0;
            $deptRajal = explode (',',$this->settingDataFixed('kdDepartemenRawatJalanFix',$kdProfile));
            foreach ($raw as $key => $item) {

                $maxid = DB::connection('sqlsrv_ris')
                ->table('ris_in')
                ->max('id');

                $jk = '';
                $modality = $item->modality ? $item->modality :  '';
                $no++;
                if (strtolower($raw[0]->jeniskelamin) == 'laki-laki'){
                    $jk = 'M';
                }else if(strtolower($raw[0]->jeniskelamin) == 'perempuan'){
                    $jk = 'F';
                }else{
                    $jk = 'O';
                }

                $norontgen = '';
                $prefix = date('ymd');
                $maxrontgen = DB::connection('sqlsrv_ris')
                ->table('ris_in')
                ->where('no_rontgen', 'LIKE', $prefix . '%')
                ->max('no_rontgen');
                $prefixLen = strlen($prefix);
                $subPrefix = substr(trim($maxrontgen), $prefixLen);
                $norontgen = $prefix . (str_pad((int)$subPrefix + 1, 6, "0", STR_PAD_LEFT));

            //     // if($item->modality == null) {
            //     //     $errorrr = "Modality belum disetting";
            //     //     $modality = null;
            //     // } else {
            //     //     if (strlen(trim($item->modality)) > 4) {
            //     //         $errorrr = "Modality tidak dikenal";
            //     //         $modality = null;
            //     //     } else {
            //     //         $modality = strtoupper(trim($item->modality));
            //     //     }
            //     // }
    
            //     // if ($modality == null) {
            //     //      break;
            //     // }

                DB::connection('sqlsrv_ris')->unprepared('SET IDENTITY_INSERT ris_in ON');


                    DB::connection('sqlsrv_ris')
                        ->table('ris_in')
                        ->insert([
                            'id' =>  $maxid + 1,
                            'tanggal_order' => $item->tglorder,
                            'no_rm' => $item->nocm,
                            'no_register' => $item->noregistrasi,
                            'no_rontgen' => $norontgen,
                            'klinis' => $item->namaproduk,
                            'kode_pemeriksaan' => $item->produkid,
                            'nama_pemeriksaan' => $item->namaproduk,
                            'modality' => $modality,
                            'nama_pasien' => $item->namapasien,
                            'nik' => $item->noidentitas,
                            'tgl_lahir' => $item->tgllahir,
                            'kelamin' => $jk,
                            'alamat' => $item->alamatlengkap,
                            'kecamatan' => $item->namakecamatan,
                            'kabupaten' => $item->namakotakabupaten,
                            'province' => $item->namapropinsi,
                            'kode_pos' => $item->kodepos,
                            'no_hp' => $item->nohp,
                            'cara_bayar' => $item->kelompokpasien,
                            'kode_asuransi' => $item->idrekanan,
                            'nama_asuransi' => $item->namarekanan,
                            'kode_ruangan_pengirim' => $item->idruanganpengirim,
                            'ruangan_pengirim' => $item->ruanganpengirim,
                            'kode_dokter_pengirim' => $item->pgid,
                            'dokter_pengirim' => $item->namalengkap,
                            'kode_dokter_radiolog' => $item->iddokterverif, //done
                            'nama_radiolog' => $item->namadokterverif,
                            'kode_user_admin' => $request['idadmin'],
                            'nama_user_admin' => $request['namaadmin'],
                            'kode_user_radiografer' => $request['idradiografer'],
                            'nama_user_radiografer' => $request['namaradiografer'],
                            'kode_instalasi_radiologi' => $item->idruangantujuan,
                            'instalasi_radiologi' => $item->ruangantujuan,
                            'Cito' => $item->cito,
                            'update_ris' => 'N',
                            'nobukti' => $item->noorder,
                            'nomor' => $no,
                            'TglDibuat' => date('Y-m-d H:i:s'),
                        ]);

                DB::connection('sqlsrv_ris')->unprepared('SET IDENTITY_INSERT ris_in OFF');

                

            //     // $cek = RisOrder::where('order_no',$request['noorder'])
            //     // ->where('order_code', $item->produkid)
            //     // ->first();
            //     // if(empty($cek)){
            //     //     $newBRG = new RisOrder();
            //     //     $newId = RisOrder::max('order_key');
            //     //     $newId = $newId + 1;
            //     // }else{
            //     //     $newBRG  = RisOrder::where('order_key',$cek->order_key)->first();
            //     //     $newId =$newBRG->order_key;
            //     // }

            //     // $getAccNumber = $noorder."".(string)$newId;//$this->getAccNumber($item->kodeexternaldjp , $item->namaexternaljp);
            //     // $nourut = RisOrder::where('patient_id',$item->nocm)->max('order_cnt');

            //     // if (is_null($nourut) || empty($nourut) || $nourut == null) {
            //     //   $nourut = 0;
            //     // }

            //     // $nourut = $nourut + 1;
            //     // $newBRG->order_cnt = $nourut;
            //     // $newBRG->order_key = $newId;
            //     // $newBRG->norec_op_fk = $item->norec;
            //     // $newBRG->accession_num = $getAccNumber;
            //     // $newBRG->aetitle = '-';
            //     // $newBRG->charge_doc_id = $request['iddokterverif']; //dokter rad
            //     // $newBRG->charge_doc_name =  $request['namadokterverif']; //dokter rad
            //     // $newBRG->consult_doc_id =  $item->pgid;
            //     // $newBRG->consult_doc_name =  $item->namalengkap ;
            //     // $newBRG->create_date = (string)date('YmdHi');
            //     // $newBRG->extension1 = $item->noregistrasi;
            //     // /*
            //     // * if JenisDiagnosa==1 : isi namadiagnosa ke extension2 & extension4
            //     // */
            //     // $newBRG->extension2 = '-';
            //     // $newBRG->extension4 =  $request['keterangan'];
            //     // /*
            //     // * if JenisDiagnosa==2 : isi namadiagnosa ke eextension3
            //     // */
            //     // $newBRG->extension3 = '-';
            //     // $newBRG->extension5 = $item->kelompokpasien;
            //     // $newBRG->extension6 = $dataLogin['userData']['id'];
            //     // $newBRG->extension7 = '-';
            //     // $newBRG->extension8 = '-';
            //     // $newBRG->extension9 = '-';
            //     // $newBRG->extension10 = '-';
            //     // $newBRG->flag = 'Y';
            //     // $newBRG->group1 = '-';
            //     // $newBRG->group2 = '-';
            //     // $newBRG->group3 = '-';
            //     // /*
            //     // * - 18 : R. Jalan - 16 : R. Inap
            //     // */
            //     // if (in_array($raw[0]->departemenid, $deptRajal)) {
            //     //     $io_date = 'E';
            //     // } else{
            //     //     $io_date = 'I';
            //     // }
            //     // $newBRG->io_date = $io_date;
            //     // $newBRG->middle_name = '-';
            //     // $newBRG->order_bodypart = '-';
            //     // $newBRG->order_code = $item->produkid;
            //     // $newBRG->order_comment = '-';
            //     // $newBRG->order_date = (string)date('YmdHi',strtotime($item->tglorder));;
            //     // $newBRG->order_dept = $request['objectruangantujuanfk'];
            //     // $newBRG->order_modality = $item->modality;
            //     // $newBRG->order_name = $item->namaproduk;
            //     // $newBRG->order_no = $request['noorder'];
            //     // $newBRG->order_reason = '-';
            //     // $newBRG->order_status = 'NW';
            //     // $newBRG->patient_birth_date = (string)date('Ymd',strtotime($item->tgllahir));
            //     // $newBRG->patient_blood = $item->golongandarah;
            //     // $newBRG->patient_id = $item->nocm;
            //     // /*- E: Cito
            //     // *
            //     // */
            //     // if ($item->cito == '1') {
            //     //     $patient_io = 'E'; // E = Emergency
            //     // } else {
            //     //     $patient_io = 'U'; // awalnya I, harusnya isinya bisa R = Routine, bisa A = Accident, bisa U = Urgent, bisa N = Newborn
            //     // }
            //     // $newBRG->patient_io = $patient_io;
            //     // $newBRG->patient_name = $item->namapasien;
            //     // /*
            //     // * - M : Laki-laki - F : Perempuan - O : Tidak diketahui
            //     // */
            //     // if (strtolower($raw[0]->jeniskelamin) == 'laki-laki'){
            //     //     $jk = 'M';
            //     // }else if(strtolower($raw[0]->jeniskelamin) == 'perempuan'){
            //     //     $jk = 'F';
            //     // }else{
            //     //     $jk = 'O';
            //     // }
            //     // $newBRG->patient_sex = $jk;
            //     // $newBRG->patient_uid = '-';
            //     // $newBRG->patient_ward = $item->namaruangan;
            //     // $newBRG->study_remark = '-';
            //     // $newBRG->study_reserv_date = (string)date('YmdHi',strtotime($item->tglpelayanan));
            //     // $newBRG->kelas = $request['objectkelasfk'];
            //     // $newBRG->kdprofile = $kdProfile;
            //     // $newBRG->save();
            //     // $response = null;
            //     // // cek status brigding atau enggak
            //     // $statusBrigding =  $this->settingFix('isBridgingPACS', $kdProfile);
            //     // if(!empty($statusBrigding) && $statusBrigding == 'true') {
            //     //     if($item->modality == null) {
            //     //         $errorrr = "Modality belum disetting";
            //     //         $modality = null;
            //     //     } else {
            //     //         if (strlen(trim($item->modality)) > 4) {
            //     //             $errorrr = "Modality tidak dikenal";
            //     //             $modality = null;
            //     //         } else {
            //     //             $modality = strtoupper(trim($item->modality));
            //     //         }
            //     //     }

            //     //     if ($modality == null) {
            //     //         break;
            //     //     }

            //     //     $dataJson = array (
            //     //         "Order" => array(
            //     //             "patient" => array(
            //     //                 "id" => $item->nocm,// str_pad($item->nocm, 10, '0', STR_PAD_LEFT),
            //     //                 "first_name" => "",
            //     //                 "middle_name" => "",
            //     //                 "last_name" => $item->namapasien,
            //     //                 "sex" => $jk,
            //     //                 "birthDate" => (string) date('Y-m-d', strtotime($item->tgllahir)),
            //     //                 "phone" => substr($item->nohp, 0, 12),
            //     //                 "address" => $item->alamatlengkap,
            //     //                 "height" => "0",
            //     //                 "weight" => "0",
            //     //                 "priority" => $patient_io,
            //     //                 "department" => $item->namaruangan,
            //     //                 "AdmissionType" => explode('/',$item->kelompokpasien)[0]
            //     //             ),
            //     //             "order" => array(
            //     //                 "id" => $getAccNumber,
            //     //                 "serviceCode" => (string)$item->produkid,
            //     //                 "serviceName" => $item->namaproduk,
            //     //                 "status" => "UPDATE",
            //     //                 "orderDate" => date('Y-m-d H:i:s'),//date('Y-m-d', strtotime($item->tglorder)),
            //     //                 "doctor" => $request['namadokterverif'],
            //     //                 "modality" => $item->modality
            //     //             ),
            //     //         )
            //     //     );
            //     //     $baseurl =  $this->settingFix('urlSendOrderPACS',$kdProfile);
            //     //     $response = Http::timeout(30)
            //     //     ->withHeaders(['Content-Type'=> 'application/json'])
            //     //     ->post($baseurl, $dataJson);
            //     //     $cek['url'] = $baseurl;
            //     //     $cek['body'] = $dataJson;
            //     //     $cek['status'] = $response->status();
            //     //     $cek['response'] = $response->body();
            //     //     $cek['json'] = $response->json();

            //     //     if($response->json()['code'] != 200) {
            //     //         $errorrr = 'KIRIM PACS GAGAL';
            //     //     }
            //     // }
            }

            $data = DB::connection('sqlsrv_ris')
                ->table('ris_in')
                ->where('nobukti', '=', $noorder)
                ->get();

            DB::commit();
            $result = array(
                "status" => 200,
                "message" => "Simpan RIS sukses",
                "data" => array(
                ),
            );

        } catch (Exception $e) {
            DB::rollBack();
            $result = array(
                "status" => 200,
                "message" => $e->getMessage() . " " . $e->getLine(),
                "data"  => $e->getMessage() . " " . $e->getLine()
            );
        }
        return $this->respond($result, $result['status'], $result['message']);
    }

    public function sendPACS($data, $kdProfile) {
        $baseurl = $this->settingDataFixed('urlSendOrderPACS',$kdProfile);
        $dataJsonSend = json_encode($data);
        $header = array(
            'Content-Type: application/json'
        );

        $curl = curl_init();
        curl_setopt_array($curl, array(
        CURLOPT_URL => $baseurl,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => $dataJsonSend,
        CURLOPT_HTTPHEADER => $header,
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return $response;
    }

    public function saveSendBack(Request $request)
    {
        $kdProfile = $this->kdProfile;
        DB::beginTransaction();
        try {
            $data = DB::table('ris_order as ro')
            ->select(
                'ro.patient_id',
                'ro.last_name',
                'ro.patient_sex',
                'ro.patient_birth_date',
                'ro.patient_io',
                'ro.extension5',
                'ro.order_code',
                'ro.order_name',
                'ro.order_date',
                'ro.consult_doc_name',
                'ro.order_modality',
                'ro.accession_num'
            )
            ->where('ro.accession_num', $request['accession_num'])
            ->first();

            RisOrder::where('accession_num', $request['accession_num'])
                ->update(
                    [
                        'order_complete' => 1,
                        'description' => $request['description'],
                        'report_date' => $request['report_date'],
                        'charge_doc_id' => $request['charge_doc_id'],
                        'charge_doc_name'=> $request['charge_doc_name'],
                        'link' => $request['link']
                    ]
                );

                $dataJson = array (
                    "Order" => array(
                        "patient" => array(
                            "id" => $data->patient_id,
                            "first_name" => "",
                            "middle_name" => "",
                            "last_name" => $data->last_name,
                            "sex" => $data->patient_sex,
                            "birthDate" => (string) date('Y-m-d', strtotime($data->patient_birth_date)),
                            "phone" => "",
                            "address" => "Indonesia",
                            "height" => "0",
                            "weight" => "0",
                            "priority" => $data->patient_io,
                            "department" => "Radiologi",
                            "AdmissionType" => $data->extension5,
                        ),
                        "order" => array(
                            "id" => $data->accession_num,
                            "serviceCode" => $data->order_code,
                            "serviceName" => $data->order_name,
                            "status" => "UPDATE",
                            "orderDate" => (string) date('Y-m-d', strtotime($data->order_date)),
                            "doctor" => $data->consult_doc_name,
                            "modality" => $data->order_modality
                        ),
                        "report" => array(
                            "description"=> $request['description'],
                            "reportDate"=> $request['report_date'],
                            "doctorID"=> $request['charge_doc_id'],
                            "doctorName"=> $request['charge_doc_name'] ,
                            "link"=> "null"
                        ),

                    )
                );

        DB::commit();
        $result = array(
            "status" => 200,
            "message" => "Simpan RIS sukses",
            "data" => $dataJson,
        );

    } catch (Exception $e) {
        DB::rollBack();
        $result = array(
            "status" => 400,
            "message" => $e->getMessage() . " " . $e->getLine(),
            "data"  => $e->getMessage() . " " . $e->getLine()
        );
    }
    return $this->respond($result['data'], $result['status'], $result['message']);
}
}
