<?php

namespace App\Http\Controllers\Antrian;

use App\Http\Controllers\Controller;
use App\Models\Master\Kelas;
use App\Models\Master\Profile;
use App\Models\Master\Ruangan;
use App\Models\Transaksi\AntrianPasienRegistrasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use App\Traits\Valet;
use Exception;

class AntrianCtrl extends Controller
{
    use Valet;
    public function __construct()
    {
        parent::__construct(true);
    }
    public function getListAntrian(Request $r)
    {
        try {
            $now = date('Y-m-d');
            $kdProfile = $this->kdProfile;
            //$loket = $r['loket'];
            $antrian = $r['antrian'];
            //var_dump($antrian);


            // $data = DB::select(DB::raw("select jenis, count(noantrian) as last
            //      from antrianpasienregistrasi_t
            //      where statuspanggil ='0'
            //      and tanggalreservasi between '$now 00:00' and '$now 23:59'
            //      and kdprofile = $kdProfile
            //      --and loketkiosk = '$loket'
            //      and jenis in ($antrian)
            //      group by jenis order by jenis"));
            // $data2 = DB::select(DB::raw("select jenis, max(noantrian) as last
            //      from antrianpasienregistrasi_t
            //      where statuspanggil !='0'
            //      and tanggalreservasi between '$now 00:00' and '$now 23:59'
            //      and kdprofile = $kdProfile
            //      --and loketkiosk = '$loket'
            //      and jenis in ($antrian)
            //      group by jenis order by jenis"));

            // $jenisAntrian = DB::table('slottingkiosk_m as sk')
            // ->select('sk.*', 'ru.namaruangan', 'ru.noruangan')
            // ->join('ruangan_m as ru', 'ru.id', '=', 'sk.objectruanganfk')
            // ->where('sk.statusenabled', true)
            // ->where('sk.kdprofile', $kdProfile)
            // ->where('sk.loket', $r['loket'])
            // ->where('sk.tanggal', $now)
            // ->whereNotNull('ru.noruangan')
            // ->get();

            $lamareservasi = "";
            $lamanonreservasi = "";
            $barureservasi = "";
            $barunonreservasi = "";
            $wna = "";
            $lansiaanak = "";
            foreach (explode(',', $antrian) as $row) {
                //var_dump($row);
                // if($row == "'RL'"){
                //     $lamareservasi = "or (apr.jenis = 'RL' and ps.objectkebangsaanfk = 1)";
                // }
                // if($row == "'LB'"){
                //     $lamanonreservasi = "or (apr.jenis = 'LB' and ps.objectkebangsaanfk = 1)";
                // }
                // if($row == "'RB'"){
                //     $barureservasi = "or (apr.jenis = 'RB' and ps.objectkebangsaanfk = 1)";
                // }
                // if($row == "'B'"){
                //     $barunonreservasi = "or (apr.jenis = 'B' and apr.objectkebangsaanfk = 1)";
                // }
                // if($row == "'WNA'"){
                //     $wna = "or (apr.jenis in('RL', 'RB', 'LB', 'B', 'LA', 'RA', 'PN') and (ps.objectkebangsaanfk <> 1 or apr.objectkebangsaanfk <> 1))";
                // }
                // if($row == "'LA'"){
                //     $lansiaanak = "or (apr.jenis in('RA', 'LA') and (ps.objectkebangsaanfk = 1 or apr.objectkebangsaanfk = 1))";
                // }


                if ($row == "'1'") {
                    $lamareservasi = "or (apr.jenis = 'RL' or apr.jenis = 'LB' or apr.jenis = 'RMJ')";
                }
                if ($row == "'2'") {
                    $lamanonreservasi = "or (apr.jenis = 'RB' or apr.jenis = 'B' or (apr.jenis = 'LA' and apr.type = 'BARU'))";
                }
                if ($row == "'3'") {
                    $barureservasi = "or (apr.jenis = 'RA' or (apr.jenis = 'LA' and apr.type = 'LAMA'))";
                }
                if ($row == "'4'") {
                    $barunonreservasi = "or (apr.jenis = 'PN')";
                }
            }

            $jenisAntrian = DB::select(DB::raw(
                "select  apr.jenis as noruangan, apr.norec, coalesce(kb.name, kb2.name) as name, ru1.namaruangan as ruanganreservasi, pa1.user,
            case 
            when length(apr.noantrian::varchar) = 1 then '00' || apr.noantrian 
            when length(apr.noantrian::varchar) = 2 then '0' || apr.noantrian 
            when length(apr.noantrian::varchar) = 3 then '' || apr.noantrian 
            end as antrian, ps.objectkebangsaanfk,
            case 
                when apr.jenis = 'B' then 'PASIEN BARU' 
                when apr.jenis = 'LB' then 'PASIEN LAMA BPJS' 
                when apr.jenis = 'LA' then 'PASIEN LANSIA & ANAK' 
                when apr.jenis = 'RA' then 'PASIEN RESERVASI LANSIA & ANAK'
                when apr.jenis = 'RB' then 'PASIEN RESERVASI BARU'
                when (apr.jenis = 'RL' or apr.jenis = 'RMJ') then 'PASIEN RESERVASI LAMA'
                when apr.jenis = 'PN' then 'PASIEN LAMA BPJS WNA'
                else ru.namaruangan 
            end as namaruangan
            from antrianpasienregistrasi_t apr
            left join pasien_m as ps on ps.id = apr.nocmfk
            left join pemakaianasuransi_t as pa on pa.nomr = ps.nocm 
            left join ruangan_m ru on ru.noruangan = apr.jenis
            left join kebangsaan_m kb on kb.id = apr.objectkebangsaanfk
            left join kebangsaan_m kb2 on kb2.id = ps.objectkebangsaanfk
            left join ruangan_m ru1 on ru1.id = apr.objectruanganfk
            left join pasiendaftar_t as pd on pd.antrianpasienregistrasifk = apr.norec and pd.statusenabled = true
            left join pemakaianasuransi_t as pa1 on pa1.noregistrasifk = pd.norec and pa1.user = 'Kiosk'
            where apr.tglfinish is null
            and isskip is null
            and issedangdipanggil is null
            and apr.noantrian is not null
            and apr.tanggalreservasi between '$now 00:00' and '$now 23:59'
            and apr.kdprofile = $kdProfile
            and (apr.jenis is null 
            $lamareservasi
            $lamanonreservasi
            $barureservasi
            $barunonreservasi
            $wna
            $lansiaanak)
            group by apr.jenis, apr.noantrian, ru.namaruangan, apr.norec, ps.objectkebangsaanfk, kb.name, ru1.namaruangan, kb2.name, pa1.user
            order by case when SUBSTRING(apr.jenis, 1, 1) = 'R' and apr.jenis = 'RA' then 1 when SUBSTRING(apr.jenis, 1, 1) = 'R' and apr.jenis = 'RMJ' then 2 when SUBSTRING(apr.jenis, 1, 1) = 'R' and (apr.jenis <> 'RA' and apr.jenis <> 'RMJ') then 3 when SUBSTRING(apr.jenis, 1, 2) = 'LA' then 3 ELSE 4 end , apr.tanggalreservasi"
            ));

            $jenisAntrianSudah = DB::select(DB::raw(
                "select  apr.jenis as noruangan, apr.norec, coalesce(kb.name, kb2.name) as name, ru1.namaruangan as ruanganreservasi, pa1.user,
            case 
            when length(apr.noantrian::varchar) = 1 then '00' || apr.noantrian 
            when length(apr.noantrian::varchar) = 2 then '0' || apr.noantrian 
            when length(apr.noantrian::varchar) = 3 then '' || apr.noantrian 
            end as antrian, 'Loket ' || apr.tempatlahir as loket, ps.objectkebangsaanfk,
            case 
                when apr.jenis = 'B' then 'PASIEN BARU' 
                when apr.jenis = 'LB' then 'PASIEN LAMA BPJS' 
                when apr.jenis = 'LA' then 'PASIEN LANSIA & ANAK' 
                when apr.jenis = 'RA' then 'PASIEN RESERVASI LANSIA & ANAK'
                when apr.jenis = 'RB' then 'PASIEN RESERVASI BARU'
                when (apr.jenis = 'RL' or apr.jenis = 'RMJ') then 'PASIEN RESERVASI LAMA'
                when apr.jenis = 'PN' then 'PASIEN LAMA BPJS WNA'
                else ru.namaruangan 
            end as namaruangan
            from antrianpasienregistrasi_t apr
            left join pasien_m as ps on ps.id = apr.nocmfk
            left join pemakaianasuransi_t as pa on pa.nomr = ps.nocm 
            left join ruangan_m ru on ru.noruangan = apr.jenis
            left join kebangsaan_m kb on kb.id = apr.objectkebangsaanfk
            left join kebangsaan_m kb2 on kb2.id = ps.objectkebangsaanfk
            left join ruangan_m ru1 on ru1.id = apr.objectruanganfk
            left join pasiendaftar_t pd on pd.antrianpasienregistrasifk = apr.norec and pd.statusenabled = true
            left join pemakaianasuransi_t as pa1 on pa1.noregistrasifk = pd.norec and pa1.user = 'Kiosk'
            where apr.isskip = true
            and apr.tanggalreservasi between '$now 00:00' and '$now 23:59'
            and apr.tglfinish is null
            and apr.noantrian is not null
            and apr.kdprofile = $kdProfile
            and (apr.jenis is null 
            $lamareservasi
            $lamanonreservasi
            $barureservasi
            $barunonreservasi
            $wna
            $lansiaanak)
            group by apr.jenis, apr.noantrian, ru.namaruangan, apr.norec, ps.objectkebangsaanfk, kb.name, ru1.namaruangan, kb2.name, pa1.user
            order by case when SUBSTRING(apr.jenis, 1, 1) = 'R' and apr.jenis = 'RA' then 1 when SUBSTRING(apr.jenis, 1, 1) = 'R' and apr.jenis = 'RMJ' then 2 when SUBSTRING(apr.jenis, 1, 1) = 'R' and (apr.jenis <> 'RA' and apr.jenis <> 'RMJ') then 3 when SUBSTRING(apr.jenis, 1, 2) = 'LA' then 3 ELSE 4 end , apr.tanggalreservasi"
            ));

            $respond = [];
            $respondsudah = [];
            foreach ($jenisAntrian as $item) {
                array_push($respond, array(
                    'namaruangan' => $item->namaruangan,
                    'antrian' => $item->antrian,
                    'jenis' => $item->noruangan,
                    'norec' => $item->norec,
                    'name' => $item->name,
                    'user' => $item->user,
                    'ruanganreservasi' => $item->ruanganreservasi,
                    'objectkebangsaanfk' => $item->objectkebangsaanfk,
                    'sekarang' => 0,
                    'sisa' => 0
                ));
            }

            foreach ($jenisAntrianSudah as $item) {
                array_push($respondsudah, array(
                    'namaruangan' => $item->namaruangan,
                    'antrian' => $item->antrian,
                    'jenis' => $item->noruangan,
                    'norec' => $item->norec,
                    'name' => $item->name,
                    'user' => $item->user,
                    'ruanganreservasi' => $item->ruanganreservasi,
                    'loket' => $item->loket,
                    'objectkebangsaanfk' => $item->objectkebangsaanfk,
                    'sekarang' => 0,
                    'sisa' => 0
                ));
            }
            // $respond = [
            //     array('jenis' => 'A', 'sekarang' => 0, 'sisa' => 0),
            //     array('jenis' => 'B', 'sekarang' => 0, 'sisa' => 0),
            //     array('jenis' => 'C', 'sekarang' => 0, 'sisa' => 0),
            //     array('jenis' => 'D', 'sekarang' => 0, 'sisa' => 0),
            //     array('jenis' => 'E', 'sekarang' => 0, 'sisa' => 0),
            //     array('jenis' => 'F', 'sekarang' => 0, 'sisa' => 0),
            //     array('jenis' => 'G', 'sekarang' => 0, 'sisa' => 0)
            // ];




            // $i = 0;
            // $j = 0;
            // $last = [];
            // foreach ($respond as $res) {
            //     foreach ($data2 as $d) {
            //         if ($d->jenis == $res['jenis']) {
            //             $respond[$i]['sekarang'] = $d->last;
            //             $last[] = array(
            //                 'nomer' => $d->last,
            //                 'jenis' =>  $res['jenis']
            //             );
            //         }
            //     }
            //     foreach ($data as $d2) {
            //         if ($d2->jenis == $res['jenis']) {
            //             $respond[$i]['sisa'] = $d2->last;
            //         }
            //     }
            //     $i++;
            // }
            // $res['last'] = $last;
            $res['data'] = $respond;
            $res['datasudah'] = $respondsudah;
            return $this->respondV2($res);
        } catch (\Exception $th) {
            $res = [
                "data" => null,
                "message" => $th->getMessage() . ' ' . $th->getLine()
            ];
            return $this->respondV2($res);
        }
    }

    public function updatePanggil(Request $r)
    {
        $kdProfile = $this->kdProfile;
        $now = date('Y-m-d');
        $sedang = AntrianPasienRegistrasi::where('norec', $r['norec'])
            ->where('kdprofile', $kdProfile)
            ->where('issedangdipanggil', true)
            ->first();
        $sedangloket = AntrianPasienRegistrasi::where('norec', $r['norec'])
            ->where('kdprofile', $kdProfile)
            ->where('issedangdipanggil', true)
            ->where('tempatlahir', $r['loket'])
            ->first();
        $data = collect(DB::select("select norec, noantrian
            from antrianpasienregistrasi_t where
            norec ='$r[norec]'"))
            ->first();
        $res['msg'] = 'Antrian Habis';
        if (!empty($sedang) && empty($sedangloket)) {
            $res['msg'] = 'Antrian Sedang Dipanggil';
        } else {
            if (!empty($data)) {
                AntrianPasienRegistrasi::where('norec', $r['norec'])
                    ->where('kdprofile', $kdProfile)
                    ->update([
                        'statuspanggil' => '1',
                        'issedangdipanggil' => true,
                        'tempatlahir' => $r['loket'],
                        // 'tglinput' => date('Y-m-d H:i:s'),
                        'devicememanggil' => gethostname(),
                    ]);
                $res['msg'] = 'Antrian Ada';
            }
        }

        return $this->respondV2($res);
    }

    public function updateFinish(Request $request)
    {
        DB::beginTransaction();
        try {

            $cek = AntrianPasienRegistrasi::where('norec', $request['norec'])->first();

            if ($cek->statuspanggil == 0) {
                DB::rollBack();
                $transMessage = "Mohon Panggil Antrian Terlebih Dahulu";
            } else {
                AntrianPasienRegistrasi::where('norec', $request['norec'])->update([
                    'tglfinish' => date('Y-m-d H:i:s'),
                ]);
                $transStatus = 'true';

                $transMessage = "Finish Sukses";
            }
        } catch (\Exception $e) {
            $transStatus = 'false';
            $transMessage = "Finish Gagal";
        }

        // if ($transStatus == 'true') {
        //     $transMessage = "Finish Sukses";
        //     DB::commit();
        //     $result = array(
        //         "status" => 200,
        //         "result" => array(
        //             "as" => '@vexana',
        //         ),
        //     );
        // } else {
        //     $transMessage = $transMessage;
        //     DB::rollBack();
        //     $result = array(
        //         "status" => 400,
        //         "result"  => null
        //     );
        // }
        // return $this->respond($result['result'], $result['status'], $transMessage);
        return $this->respondV2($transMessage);
    }

    public function updateSkip(Request $request)
    {
        DB::beginTransaction();
        try {
            AntrianPasienRegistrasi::where('norec', $request['norec'])->update([
                'isskip' => true,
                'issedangdipanggil' => null,
            ]);
            $transStatus = 'true';

            $transMessage = "Skip Sukses";
        } catch (\Exception $e) {
            $transStatus = 'false';
            $transMessage = "Skip Gagal";
        }

        if ($transStatus == 'true') {
            $transMessage = "Skip Sukses";
            DB::commit();
            $result = array(
                "status" => 200,
                "result" => array(
                    "as" => '@vexana',
                ),
            );
        } else {
            $transMessage = "Skip Gagal";
            DB::rollBack();
            $result = array(
                "status" => 200,
                "result"  => null
            );
        }
        return $this->respond($result['result'], $result['status'], $transMessage);
    }

    public function updateSedang(Request $request)
    {
        DB::beginTransaction();
        try {
            AntrianPasienRegistrasi::where('norec', $request['norec'])->update([
                'issedangdipanggil' => true,
            ]);
            $transStatus = 'true';

            $transMessage = "Memanggil Pasien...";
        } catch (\Exception $e) {
            $transStatus = 'false';
            $transMessage = "Gagal Memanggil Pasien";
        }

        if ($transStatus == 'true') {
            $transMessage = "Memanggil Pasien...";
            DB::commit();
            $result = array(
                "status" => 200,
                "result" => array(
                    "as" => '@vexana',
                ),
            );
        } else {
            $transMessage = "Gagal Memanggil Pasien";
            DB::rollBack();
            $result = array(
                "status" => 200,
                "result"  => null
            );
        }
        return $this->respond($result['result'], $result['status'], $transMessage);
    }

    public function getViewer(Request $r)
    {
        $kdProfile = $this->kdProfile;
        $awal = date('Y-m-d 00:00');
        $akhir = date('Y-m-d 23:59');
        $data = AntrianPasienRegistrasi::whereIn('statuspanggil', ['1', '2'])
            ->whereBetween('tanggalreservasi', [$awal, $akhir])
            ->where('kdprofile', $kdProfile)
            ->orderBy('tanggalreservasi', 'desc')
            ->orderBy('tglinput', 'desc')
            ->get();

        foreach ($data as $item) {
            $item->antrianjenis = $item->jenis . "-" . str_pad($item->noantrian, 3, "0", STR_PAD_LEFT);
        }
        return $this->respondV2($data);
    }

    public function getSettingViewer(Request $r)
    {
        $idProfile = $this->kdProfile;

        // $deptJalan = explode(',', $this->settingFix('listRuanganViewer'));
        // $ruangan = [];
        // foreach ($deptJalan as $item) {
        //     $ruangan[] = array(
        //         'id' => $item,
        //         'namaruangan' => $item,
        //         'nocounter' => null
        //     );
        // }
        $deptJalan =  explode(',', $this->settingFix('kdDepartemenRawatJalanFix'));
        $ruangan = DB::table('ruangan_m')
            ->select('id', 'namaruangan', 'nocounter')
            ->where('statusenabled', true)
            ->wherein('objectdepartemenfk', $deptJalan)
            ->orderBy('namaruangan')
            ->get();
        $farmasi = DB::table('ruangan_m')
            ->select('id', 'namaruangan', 'nocounter')
            ->where('statusenabled', true)
            ->where('kdprofile', $idProfile)
            ->wherein('objectdepartemenfk', [14])
            ->orderBy('namaruangan')
            ->get();

        $laborat = Ruangan::mine()->where('objectdepartemenfk', $this->settingFix('idDepartemenLab'))->get();
        // $res['ruangan'] = $ruangan;
        $res['farmasi'] = $farmasi;
        $res['ruangan'] = $ruangan;
        $res['laborat'] = $laborat;

        return $this->respondV2($res);
    }

    public function getDipanggil(Request $r)
    {
        $kdProfile = $this->kdProfile;
        $arruangn = [];
        foreach (explode(',', $r['ruangan']) as $z) {
            $arruangn[] = $z;
        }
        $apd = DB::table('antrianpasiendiperiksa_t as apd')
            ->join('pasiendaftar_t as pd', 'pd.norec', '=', 'apd.noregistrasifk')
            ->join('pasien_m as ps', 'ps.id', '=', 'pd.nocmfk')
            ->select('ps.nocm', 'ps.namapasien', 'apd.noantrian', 'apd.objectruanganfk')
            ->where('apd.statusenabled', true)
            ->where('apd.kdprofile', $kdProfile)
            ->whereIn('apd.objectruanganfk', $arruangn)
            ->where('apd.statusantrian', 1)
            ->whereNotNull('apd.tgldipanggilsuster')
            ->orderByRaw("apd.noantrian asc,apd.tgldipanggilsuster asc")
            ->first();
        return $this->respondV2($apd);
    }
    
    public function getListAntrianFarm(Request $request)
    {
        $kdProfile = $this->kdProfile;
        $statusPengerjaanViewerFar = $this->settingFix('statusPengerjaanViewerFar');
        $tglAwal = date('Y-m-d 00:00');
        $tglAkhir = date('Y-m-d 23:59');
        $data = DB::select(DB::raw("
        select * from (
        SELECT DISTINCT ON (ps.nocm)
        sr.noresep,aa.noantri AS aanoantri,ps.nocm,ps.namapasien,aa.jenis AS aajenis,aa.keterangan,sr.tglresep,ru2.namaruangan,ru.namaruangan as ruanganasal,
        CASE
        when sr.tglambilresep !=null  then 'Sudah Di Ambil'
        when st.statuspengerjaan is not null then st.statuspengerjaan
        else ''  end as statusorder,pd.noregistrasi,ps.nocm,ps.namapasien,
        EXTRACT (YEAR FROM AGE(pd.tglregistrasi,ps.tgllahir )) || ' Thn ' as umur,
        CASE WHEN ps.objectjeniskelaminfk = 1 THEN 'L' ELSE 'P' END as jk
        FROM strukresep_t AS sr
        INNER JOIN antrianpasiendiperiksa_t AS apd ON apd.norec = sr.pasienfk
        INNER JOIN pasiendaftar_t AS pd ON pd.norec = apd.noregistrasifk
        INNER JOIN pasien_m AS ps ON ps.id = pd.nocmfk
        INNER JOIN ruangan_m AS ru2 ON ru2.id = sr.ruanganfk
        LEFT JOIN ruangan_m AS ru ON ru.id = apd.objectruanganfk
        INNER JOIN antrianapotik_t AS aa ON aa.noresep = sr.noresep
        LEFT JOIN statuspengerjaan_m AS st ON st.id = aa.status
        WHERE sr.kdprofile = $kdProfile AND sr.tglresep between '$tglAwal' and '$tglAkhir'
        AND sr.statusenabled = 't'
        and sr.kdprofile = $kdProfile
        and sr.tglambilresep is null
        and sr.ruanganfk = 325
        and aa.status in ($statusPengerjaanViewerFar)
        and ru.objectdepartemenfk = 18
        and ru.id not in (385,382,376,384,369,379,378,373,371,370,365,386,375,367,381,383,380,377,374,372,368,366,756,769,332)
        ORDER BY ps.nocm asc) as queryy
        order by aanoantri asc;
        "));
        
        $totalA = DB::table('antrianapotik_t as aa')
        ->where('aa.statusenabled', 't')
        ->where('aa.jenis', 'A')
        ->whereBetween('aa.tglresep', [$tglAwal, $tglAkhir])
        ->count();

        $totalB = DB::table('antrianapotik_t as aa')
        ->where('aa.statusenabled', 't')
        ->where('aa.jenis', 'B')
        ->whereBetween('aa.tglresep', [$tglAwal, $tglAkhir])
        ->count();

        return [
            'data' => $data,
            'totalA' => $totalA,
            'totalB' => $totalB,
        ];
    }

    public function getViewerFar(Request $r)
    {
        $kdProfile = $this->kdProfile;
        $awal = date('Y-m-d 00:00');
        $akhir = date('Y-m-d 23:59');
        $data = DB::table('antrianapotik_t')
            ->where('status', '5')
            ->where('kdprofile', $kdProfile)
            ->whereBetween('tglresep', [$awal, $akhir])
            ->orderBy('tglresep', 'desc')
            ->get();

        $farmasi = DB::table('ruangan_m')
            ->select('id', 'namaruangan', 'nocounter')
            ->where('statusenabled', true)
            ->where('kdprofile', $kdProfile)
            ->wherein('objectdepartemenfk', [14])
            ->orderBy('namaruangan')
            ->get();
        $res['farmasi'] = $farmasi;
        $res['data'] = $data;
        return $this->respondV2($res);
    }

    public function getListAntrianLab(Request $r)
    {
        $kdProfile = $this->kdProfile;
        $awal = date('Y-m-d 00:00');
        $akhir = date('Y-m-d 23:59');
        $data = DB::table('antrianpasiendiperiksa_t as apd')
            ->join('pasiendaftar_t as pd', 'pd.norec', 'apd.noregistrasifk')
            ->JOIN('ruangan_m as ru', 'ru.id', 'apd.objectruanganfk')
            ->Join('departemen_m as dept', 'dept.id', 'ru.objectdepartemenfk')
            ->JOIN('pasien_m as ps', 'ps.id', 'pd.nocmfk')
            ->select(
                'ru.id as ruid',
                'ru.namaruangan',
                'pd.noregistrasi',
                'ps.nocm',
                'ps.namapasien',
                'apd.noantrian',
                'apd.statusantrian',
            )
            ->where('apd.kdprofile', $kdProfile)
            ->where('apd.statusenabled', true)
            ->whereBetween(DB::raw('apd.tglmasuk::date'), [$awal, $akhir])
            ->where('ru.objectdepartemenfk', $this->settingFix('idDepartemenLab'))
            ->orderBy('apd.noantrian')
            ->get();

        // $farmasi = DB::table('ruangan_m')
        //     ->select('id', 'namaruangan', 'nocounter')
        //     ->where('statusenabled', true)
        //     ->where('kdprofile', $kdProfile)
        //     ->wherein('objectdepartemenfk', [14])
        //     ->orderBy('namaruangan')
        //     ->get();
        // $res['farmasi'] = $farmasi;
        $res['data'] = $data;
        return $this->respondV2($res);
    }
    public function getListAntrian_awal(Request $r)
    {
        $kdProfile = $this->kdProfile;
        $now = date('Y-m-d');
        $data = DB::select(DB::raw("select jenis, count(noantrian) as last from antrianpasienregistrasi_t
             where  statuspanggil ='0'
             and tanggalreservasi between '$now 00:00' and '$now 23:59'
             and kdprofile= $kdProfile
             group by jenis order by jenis"));
        $data2 = DB::select(DB::raw("select jenis, max(noantrian) as last from antrianpasienregistrasi_t
             where  statuspanggil !='0'
             and tanggalreservasi between '$now 00:00' and '$now 23:59'
             and kdprofile= $kdProfile
             group by jenis order by jenis"));
        // dd($data2);
        $respond = [
            array('jenis' => 'LT1', 'sekarang' => 0, 'sisa' => 0),
            array('jenis' => 'LT2', 'sekarang' => 0, 'sisa' => 0),
            array('jenis' => 'LT3', 'sekarang' => 0, 'sisa' => 0),
            array('jenis' => 'LT4', 'sekarang' => 0, 'sisa' => 0),
            array('jenis' => 'LT5', 'sekarang' => 0, 'sisa' => 0),
            array('jenis' => 'LT6', 'sekarang' => 0, 'sisa' => 0),
            array('jenis' => 'LT7', 'sekarang' => 0, 'sisa' => 0),
            array('jenis' => 'LT8', 'sekarang' => 0, 'sisa' => 0)
        ];
        $i = 0;
        $j = 0;
        $last = [];
        foreach ($respond as $res) {
            foreach ($data2 as $d) {
                if ($d->jenis == $res['jenis']) {
                    $respond[$i]['sekarang'] = $d->last;
                    $last[] = array(
                        'nomer' => $d->last,
                        'jenis' =>  $res['jenis']
                    );
                }
            }
            foreach ($data as $d2) {
                if ($d2->jenis == $res['jenis']) {
                    $respond[$i]['sisa'] = $d2->last;
                }
            }
            $i++;
        }
        $res['last'] = $last;
        $res['data'] = $respond;
        return $this->respondV2($res);
    }
    public function getDetail(Request $r)
    {
        $now = date('Y-m-d');
        $data = DB::select(DB::raw("select noantrian, 'Loket ' || tempatlahir as loket, tglinput, devicememanggil from antrianpasienregistrasi_t where
            statuspanggil != '0' and
            jenis ='$r[jenis]' and
            tanggalreservasi between '$now 00:00' and '$now 23:59'
            and kdprofile= $this->kdProfile
            order by tanggalreservasi"));
        return $this->respondV2($data);
    }
    public function getListCallerByRuangan(Request $request)
    {
        $kdProfile = $this->kdProfile;
        $now = date('Y-m-d');
        $loket = $request->loket;
        // Membagi string menjadi array menggunakan koma sebagai pemisah
        $arrayNumbers = explode(',', $loket);

        // Menambahkan tanda kutip pada setiap elemen array
        $quotedNumbers = array_map(function ($number) {
            return "'$number'";
        }, $arrayNumbers);

        // Menggabungkan array menjadi string dengan koma sebagai pemisah
        $outputString = implode(',', $quotedNumbers);

        $data = DB::select(DB::raw("
        SELECT x.* FROM (
            SELECT rm.namaruangan
            ,apr.jenis
            ,apr.noantrian
            ,ROW_NUMBER() OVER (PARTITION BY apr.jenis ORDER BY apr.tglinput DESC) as nomor
            FROM antrianpasienregistrasi_t apr
            INNER JOIN ruangan_m rm on rm.id = apr.objectruanganfk
            WHERE apr.statuspanggil in ('1','2')
            AND apr.tanggalreservasi between '$now 00:00' AND '$now 23:59'
            AND apr.tempatlahir in ($outputString)
            and apr.kdprofile = $kdProfile
        ) x
        WHERE x.nomor = 1
        "));

        foreach ($data as $item) {
            $item->antrian = $item->jenis . "-" . str_pad($item->noantrian, 3, "0", STR_PAD_LEFT);
        }
        return $this->respondV2($data);
    }
    public function getViewerOK(Request $request)
    {
        $data = DB::table('kamaroperasi_m')->select('id', 'namakamarok')->where('statusenabled', true)->get();

        $jadwal = \DB::table('strukorder_t AS so')
            ->select(
                'so.norec as norec_so',
                'so.noorder',
                'so.objectpegawaiorderfk',
                'so.statusorder',
                'so.keteranganlainnya',
                'so.tglorder',
                'so.tglpelayananakhir',
                'so.objectkamaroperasifk',
                'pd.norec AS norec_pd',
                'pd.nocmfk',
                'pd.noregistrasi',
                'pd.tglregistrasi as tglmasuk',
                'apd.norec as norec_apd',
                'so.tglpelayananakhir as jammulaioperasi',
                'so.tglpelayananakhir as jamselesaioperasi',
                'so.statusorder as statusoperasi',
                'ps.nocm',
                'ps.namapasien',
                DB::raw("to_char(ps.tgllahir,'YYYY-MM-DD') as tgllahir"),
                'pgdokter.namalengkap AS namadokter',
            )
            ->join('pasiendaftar_t AS pd', 'pd.norec', '=', 'so.noregistrasifk')
            ->join('pasien_m AS ps', 'ps.id', '=', 'pd.nocmfk')
            ->join('antrianpasiendiperiksa_t as apd', 'apd.objectstrukorderfk', '=', 'so.norec')
            ->leftJoin('pegawai_m AS pgdokter', 'pgdokter.id', '=', 'apd.objectpegawaifk')
            ->leftjoin('ruangan_m as ru', 'ru.id', '=', 'so.objectruangantujuanfk')
            // ->where('apd.objectkamaroperasifk', $item->id)
            // ->where('apd.statusoperasi', 1)
            // ->where('so.keteranganorder', 'Pesan Jadwal Operasi')
            ->where('ru.objectdepartemenfk', $this->settingFix('idDepartemenBedah'))
            ->where('so.statusenabled', true)
            ->whereNotNull('so.statusorder')
            ->whereDate(DB::raw("to_char(so.tglpelayananakhir,'YYYY-MM-DD')"), '>=', \Carbon\Carbon::now()->format('Y-m-d'))
            ->orderBy('so.tglpelayananakhir', 'DESC')
            ->get();
        // dd($jadwal);

        $i = 0;
        foreach ($data as $item) {

            $data[$i]->nocm = '';
            $data[$i]->namapasien = '-';
            $data[$i]->dokter = '-';
            foreach ($jadwal as $jj) {
                $umur = '';
                if ($jj->objectkamaroperasifk == $item->id) {
                    if (!empty($jj->tgllahir)) {
                        $tglLahir = new \DateTime($jj->tgllahir);
                        $today = new \DateTime("today");
                        if ($tglLahir > $today) {
                            $umur = '0 Thn 0 Bln 0 Hr';
                        } else {
                            $y = $today->diff($tglLahir)->y;
                            $m = $today->diff($tglLahir)->m;
                            $d = $today->diff($tglLahir)->d;
                            $umur = " - " . $y . " Thn " . $m . " Bln " . $d . " Hr";
                        }
                    }

                    $data[$i]->nocm = $jj->nocm . $umur;
                    $data[$i]->namapasien = $jj->namapasien;
                    $data[$i]->dokter = $jj->namadokter;
                }
            }
            $i++;
        }

        $menunggu = \DB::table('strukorder_t AS so')
            ->select(
                'so.norec as norec_so',
                'so.noorder',
                'so.objectpegawaiorderfk',
                'so.statusorder',
                'so.keteranganlainnya',
                'so.tglorder',
                DB::raw("to_char(so.tglpelayananakhir,'YYYY-MM-DD') as tglpelayananakhir"),
                'so.objectkamaroperasifk',
                'so.diagnosis',
                'pd.norec AS norec_pd',
                'pd.nocmfk',
                'pd.noregistrasi',
                'pd.tglregistrasi as tglmasuk',
                'kp.kelompokpasien',
                'prod.namaproduk',
                'so.norec',
                'jen.jeniskelamin',
                'dokter1.namalengkap as dokterop',
                'dokter2.namalengkap as dokteranis',
                'dokter3.namalengkap as dokteranak',
                'apd.norec as norec_apd',
                'so.tglpelayananakhir as jammulaioperasi',
                'so.tglpelayananakhir as jamselesaioperasi',
                'so.statusoperasi as statusoperasi',
                'so.durasi as estimasiwaktuoperasi',
                'ru.namaruangan',
                'ps.tgllahir',
                'kop.namakamarok',
                'ps.nocm',
                'ps.namapasien',
                DB::raw("to_char(ps.tgllahir,'YYYY-MM-DD') as tgllahir"),
                'pgdokter.namalengkap AS namadokter',
            )
            ->join('pasiendaftar_t AS pd', 'pd.norec', '=', 'so.noregistrasifk')
            ->join('pasien_m AS ps', 'ps.id', '=', 'pd.nocmfk')
            ->join('jeniskelamin_m AS jen', 'jen.id', '=', 'ps.objectjeniskelaminfk')
            ->leftjoin('pelayananpasien_t AS pp', 'pp.strukorderfk', '=', 'so.norec')
            ->leftjoin('produk_m AS prod', 'prod.id', '=', 'pp.produkfk')
            ->leftjoin('kamaroperasi_m AS kop', 'kop.id', '=', 'so.objectkamaroperasifk')
            ->leftJoin('antrianpasiendiperiksa_t as apd', 'apd.objectstrukorderfk', '=', 'so.norec')
            ->leftJoin('pegawai_m AS pgdokter', 'pgdokter.id', '=', 'apd.objectpegawaifk')
            ->leftJoin('pegawai_m AS dokter1', 'dokter1.id', '=', 'so.dokteroperatorfk')
            ->leftJoin('pegawai_m AS dokter2', 'dokter2.id', '=', 'so.dokteranastesifk')
            ->leftJoin('pegawai_m AS dokter3', 'dokter3.id', '=', 'so.dokteranakfk')
            ->leftjoin('ruangan_m as ru', 'ru.id', '=', 'so.objectruangantujuanfk')
            ->join('kelompokpasien_m as kp', 'kp.id', '=', 'pd.objectkelompokpasienlastfk')
            // ->where('so.keteranganorder', 'Pesan Jadwal Operasi')
            ->where('ru.objectdepartemenfk', $this->settingFix('idDepartemenBedah'))
            // ->whereIn('apd.statusoperasi', [null, 0])
            // ->where(function ($q) {
            // 	$q->whereNull('so.statusorder')->orWhere('so.statusorder', 0);
            // })
            ->where('so.statusenabled', true)
            ->whereDate(DB::raw("to_char(so.tglpelayananakhir,'YYYY-MM-DD')"), '=', \Carbon\Carbon::now()->format('Y-m-d'))
            ->get();

        $recoveryroom = \DB::table('strukorder_t AS so')
            ->select(
                'so.norec as norec_so',
                'so.noorder',
                'so.objectpegawaiorderfk',
                'so.statusorder',
                'so.keteranganlainnya',
                'so.tglorder',
                DB::raw("to_char(so.tglpelayananakhir,'YYYY-MM-DD') as tglpelayananakhir"),
                'so.objectkamaroperasifk',
                'pd.norec AS norec_pd',
                'pd.nocmfk',
                'pd.noregistrasi',
                'pd.tglregistrasi as tglmasuk',
                'apd.norec as norec_apd',
                'so.tglpelayananakhir as jammulaioperasi',
                'so.tglpelayananakhir as jamselesaioperasi',
                'so.statusorder as statusoperasi',
                'so.estimasiwaktuoperasi',
                'ps.nocm',
                'ps.namapasien',
                DB::raw("to_char(ps.tgllahir,'YYYY-MM-DD') as tgllahir"),
                'pgdokter.namalengkap AS namadokter',
            )
            ->join('pasiendaftar_t AS pd', 'pd.norec', '=', 'so.noregistrasifk')
            ->join('pasien_m AS ps', 'ps.id', '=', 'pd.nocmfk')
            ->join('antrianpasiendiperiksa_t as apd', 'apd.objectstrukorderfk', '=', 'so.norec')
            ->leftJoin('pegawai_m AS pgdokter', 'pgdokter.id', '=', 'apd.objectpegawaifk')
            ->where('so.statusorder', 2)
            ->where('so.statusenabled', true)
            ->whereNotNull('so.statusorder')
            ->get();

        $result = [
            'berjalan' => $data,
            'menunggu' => $menunggu,
            'recoveryroom' => $recoveryroom,
        ];
        return $this->respond($result);
    }

    public function getTempatTidur(Request $request)
    {
        $kelas =  Kelas::mine()->get();
        $res = \DB::table('kamar_m as km')
            ->join('tempattidur_m as tt', 'tt.objectkamarfk', '=', 'km.id')
            ->select(\DB::raw('count(*) as total'), 'tt.objectstatusbedfk', 'km.objectkelasfk')
            ->where('tt.statusenabled', true)
            ->where('km.statusenabled', true)
            ->groupBy('tt.objectstatusbedfk', 'km.objectkelasfk')
            ->get();
        $resKelas = [];
        foreach ($kelas as $item) {
            $resKelas[] = [
                'id'        => $item->id,
                'kelas'     => $item->namakelas,
                'terisi'    => 0,
                'tersedia'  => 0,
                'total'     => 0,
            ];
        }
        foreach ($resKelas as  $x => $item) {
            foreach ($res as $ii) {
                if ($ii->objectkelasfk == $item['id']) {
                    $resKelas[$x]['total'] =   $resKelas[$x]['total'] + $ii->total;
                    if ($ii->objectstatusbedfk == 1) {
                        $resKelas[$x]['terisi'] =   $resKelas[$x]['terisi'] + $ii->total;
                    }
                    if ($ii->objectstatusbedfk == 2) {
                        $resKelas[$x]['tersedia'] =   $resKelas[$x]['tersedia'] + $ii->total;
                    }
                }
            }
        }

        $ruangan = DB::table('ruangan_m as ru')
            ->join('mapruangantokelas_m as mr', 'mr.objectruanganfk', '=', 'ru.id')
            ->select('ru.id', 'ru.namaruangan')
            ->where('ru.statusenabled', true)
            ->wherein('ru.objectdepartemenfk',  explode(',', $this->settingFix('kdDepartemenRanapFix')))
            ->groupBy('ru.id', 'ru.namaruangan')
            ->get();
        $res = \DB::table('kamar_m as km')
            ->join('tempattidur_m as tt', 'tt.objectkamarfk', '=', 'km.id')
            ->select(\DB::raw('count(*) as total'), 'tt.objectstatusbedfk', 'km.objectruanganfk')
            ->where([
                // 'km.objectruanganfk' => $item->id,
                'tt.statusenabled' => true,
                'km.statusenabled' => true
            ])
            ->groupBy('tt.objectstatusbedfk', 'km.objectruanganfk')
            ->get();
        $i = 0;
        foreach ($ruangan as $item) {
            $ruangan[$i]->terisi    = 0;
            $ruangan[$i]->tersedia  = 0;
            $ruangan[$i]->total     = 0;
            foreach ($res as $ii) {
                if ($ii->objectruanganfk == $item->id) {
                    $ruangan[$i]->total  =  $ruangan[$i]->total + $ii->total;
                    if ($ii->objectstatusbedfk == 1) {
                        $ruangan[$i]->terisi =   $ruangan[$i]->terisi + $ii->total;
                    }
                    if ($ii->objectstatusbedfk == 2) {
                        $ruangan[$i]->tersedia =   $ruangan[$i]->tersedia + $ii->total;
                    }
                }
            }

            $i++;
        }

        $result = [
            'kelas'     => $resKelas,
            'ruangan'   => $ruangan,
        ];
        return $this->respond($result);
    }
}
