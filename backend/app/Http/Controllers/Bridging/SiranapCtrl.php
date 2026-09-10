<?php

namespace App\Http\Controllers\Bridging;

use App\Http\Controllers\Controller;
use App\Models\Master\Kamar;
use App\Models\Master\Kelas;
use App\Models\Master\Pegawai;
use App\Models\Master\SettingDataFixed;
use App\Traits\Valet;
use Carbon\Carbon;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request as Psr7Request;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Webpatser\Uuid\Uuid;
use Exception;

class SiranapCtrl extends Controller
{
    use Valet;
    public function __construct()
    {
        parent::__construct(true);
    }
    function getSetting()
    {
        $res = [
            'SIRANAP_RS_ID' => '',
            'SIRANAP_RS_PASS' => 'S!rs2020!!',
            'SIRANAP_URL' => 'https://sirs.kemkes.go.id/fo/index.php/',
        ];

        $data = DB::table('settingdatafixed_m')->where('kdprofile', session('kdProfile'))
            ->select('namafield', 'nilaifield')
            ->where('statusenabled', true)
            ->whereIn('namafield', [
                'SIRANAP_RS_ID',
                'SIRANAP_RS_PASS',
                'SIRANAP_URL'
            ])
            ->get();


        foreach ($data as $v) {
            foreach (array_keys($res) as $v2) {
                if ($v->namafield == $v2) {
                    $res[$v->namafield] = $v->nilaifield;
                }
            }
        }

        return $res;
    }
    function getHeader()
    {
        $set = $this->getSetting();

        $data = $set['SIRANAP_RS_ID'];
        $password = $set['SIRANAP_RS_PASS'];
        date_default_timezone_set('UTC');
        $tStamp = strval(time() - strtotime('1970-01-01 00:00:00'));


        $header = array(
            "Content-Type" => "application/json",
            "X-rs-id" => $data,
            "X-pass" => $password,
            "X-timestamp" => $tStamp,

        );

        return $header;
    }
    public function siranapTools(Request $request, $local = false)
    {
        try {
            $headers = $this->getHeader();

            $set = $this->getSetting();
            $dataJsonSend = null;

            if ($request['data'] != null) {
                $dataJsonSend = $request['data']; //json_encode($request['data']);
            }

            $methods = $request['method'];
            $baseURL = $set['SIRANAP_URL'];
            $methods = strtolower($methods);
            $url = $baseURL . $request['url'];

            if (empty($dataJsonSend)) {
                $response = Http::withHeaders($headers)
                    ->withoutVerifying()
                    ->withOptions(["verify" => false])
                    ->{$methods}($url);
            } else {
                $response = Http::withHeaders($headers)
                    ->withoutVerifying()
                    ->withOptions(["verify" => false])
                    ->{$methods}($url, $dataJsonSend);
            }


            if ($response->ok()) {
                $metadata['code'] = 200;
                $metadata['message'] = 'Ok';
                return $this->respond($response->json(),  $metadata['code'], $metadata['message']);
            } else {
                $metadata['code'] = 201;
                $metadata['message'] = 'Response Dari SIRANAP : ' . json_encode($response);

                return $this->respond(null,  $metadata['code'], $metadata['message']);
            }
        } catch (\Exception $e) {
            $metadata['code'] = 201;
            $metadata['message'] = $e->getMessage() . ' ' . $e->getLine();

            if ($local) {
                return array(
                    'metaData' => $metadata,
                    'response' => null,
                );
            }
            return $this->respond(null,  $metadata['code'], $metadata['message']);
        }
    }

    public function getTTeuy(Request $request)
    {
        $kdProfile = $this->kdProfile;

        $q = collect(DB::select("
                SELECT  case when kmr.kodesirs is not null then  kmr.kodesirs else  kl.kodesirs end AS id_tt,
                        ru.namaruangan as ruang,count(tt.id ) as jumlah, 0 as jumlah_ruang,
                        sum(case when sb.id=1 then 1 else 0  end) as terpakai,
                        case when ru.jenis='covid' then 1 else 0 end as covid
                FROM tempattidur_m AS tt
                INNER JOIN statusbed_m AS sb ON sb. ID = tt.objectstatusbedfk
                INNER JOIN kamar_m AS kmr ON kmr. ID = tt.objectkamarfk
                INNER JOIN kelas_m AS kl ON kl. ID = kmr.objectkelasfk
                INNER JOIN ruangan_m AS ru ON ru. ID = kmr.objectruanganfk
                WHERE tt.statusenabled = TRUE
                AND tt.kdprofile = $kdProfile
                AND kmr.statusenabled = TRUE
                AND  (kmr.kodesirs is not null or kl.kodesirs is not null )
                GROUP BY kmr.kodesirs,kl.kodesirs,ru.namaruangan,ru.jenis

            "));
        $jmlruangn = collect(DB::select("select x.ruang,count(x.ruang) as jumlah_ruang from (
            SELECT
            ru.namaruangan as ruang,
             case when kmr.kodesirs is not null then  kmr.kodesirs else  kl.kodesirs end AS id_tt

            FROM tempattidur_m AS tt
            INNER JOIN statusbed_m AS sb ON sb. ID = tt.objectstatusbedfk
            INNER JOIN kamar_m AS kmr ON kmr. ID = tt.objectkamarfk
            INNER JOIN kelas_m AS kl ON kl. ID = kmr.objectkelasfk
            INNER JOIN ruangan_m AS ru ON ru. ID = kmr.objectruanganfk
            WHERE tt.statusenabled = TRUE
            AND kmr.statusenabled = TRUE
            and tt.kdprofile = $kdProfile
            and  (kmr.kodesirs is not null or kl.kodesirs is not null)
            GROUP BY ru.namaruangan,kmr.kodesirs ,kl.kodesirs

            ) as x GROUP BY x.ruang
            "));
        foreach ($q as $v) {
            foreach ($jmlruangn as $dd) {
                if ($v->ruang == $dd->ruang) {
                    $v->jumlah_ruang = $dd->jumlah_ruang;
                }
            }
        }

        return $this->respond($q);
    }
    public function masterKelas(Request $r)
    {
        $data  = DB::table('kelas_m as ru')
            ->select(
                'ru.*'

            )
            ->where('ru.kdprofile', $this->kdProfile);

        if ($r['statusenabled']) {
            $data->where('ru.statusenabled', $r['statusenabled']);
        }
        if (isset($r['kodesirs']) && $r['kodesirs'] != '') {
            $data = $data->whereNotNull('ru.kodesirs');
        }
        $data = $data->orderByDesc('ru.namakelas', 'asc');
        $data = $data->get();

        $res['data'] = $data;
        return $this->respond($res);
    }
    public function masterKamar(Request $r)
    {
        $data  = DB::table('kamar_m as ru')
        ->join('kelas_m as kl','kl.id','=','ru.objectkelasfk')
        ->join('ruangan_m as rus','rus.id','=','ru.objectruanganfk')
            ->select(
                'ru.*',
                'kl.namakelas',
                'rus.namaruangan'
            )
            ->where('ru.kdprofile', $this->kdProfile);

        if ($r['statusenabled']) {
            $data->where('ru.statusenabled', $r['statusenabled']);
        }
        if (isset($r['kodesirs']) && $r['kodesirs'] != '') {
            $data = $data->whereNotNull('ru.kodesirs');
        }
        $data = $data->orderByDesc('rus.namaruangan', 'asc');
        $data = $data->get();

        $res['data'] = $data;
        return $this->respond($res);
    }

    public function mapKelas(Request $r)
    {
        DB::beginTransaction();
        try {

            $dataPS = Kelas::where('id', $r['id'])->update(['kodesirs' => $r['kodesirs']]);
            DB::commit();

            $result = [
                'status' => 200,
                'message' => 'Sukses',
                'result' => $dataPS,
            ];
        } catch (Exception $e) {
            $result = [
                'status' => 400,
                'message' => 'Simpan Gagal !',
                'result' => $e->getMessage(),
            ];
        }

        return $this->respond($result['result'], $result['status'], $result['message']);
    }
    public function mapKamar(Request $r)
    {
        DB::beginTransaction();
        try {

            $dataPS = Kamar::where('id', $r['id'])->update(['kodesirs' => $r['kodesirs']]);
            DB::commit();

            $result = [
                'status' => 200,
                'message' => 'Sukses',
                'result' => $dataPS,
            ];
        } catch (Exception $e) {
            $result = [
                'status' => 400,
                'message' => 'Simpan Gagal !',
                'result' => $e->getMessage(),
            ];
        }

        return $this->respond($result['result'], $result['status'], $result['message']);
    }
}
