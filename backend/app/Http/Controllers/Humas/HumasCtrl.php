<?php

namespace App\Http\Controllers\Humas;

use App\Http\Controllers\Controller;
use App\Models\Master\JenisPelayanan;
use App\Models\Master\Kebangsaan;
use App\Models\Master\Kelas;
use App\Models\Master\Produk;
use App\Models\Master\Rekanan;
use App\Models\Master\Ruangan;
use App\Traits\Valet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;

class HumasCtrl extends Controller
{
    use Valet;

    public function infoBed(Request $request)
    {
        $kdProfile =  $this->kdProfile;
        $ruanganfk = '';
        if ($request->ruanganfk) {
            $ruanganfk = 'and ru.id = ' . $request->ruanganfk;
        }
        $limit = '';
        if ($request->limit) {
            $limit = 'limit '. $request->limit;
        }
        $offset = '';
        if ($request->offset) {
            $offset = 'offset '. $request->offset;
        }
        $statusBedKosong = $this->settingFix('idStatusBedKosong');
        $statusBedIsi = $this->settingFix('idStatusBedIsi');
        $statusBedDipesan = $this->settingFix('idStatusBedDipesan');
        $statusBedRusak = $this->settingFix('idStatusBedRusak');
        $kelompokTrsansaksiMutasi = $this->settingFix('kelompoktransaksiRencanaMutasi');
        $data = collect(DB::select("
        SELECT
        x.namaruangan,
        x.id_ruangan,
        SUM(x.isi) AS isi,
        SUM(x.kosong) AS kosong,
        SUM(x.rusak) AS rusak,
        SUM(x.dipesan) AS terpesan,
        COUNT(x.tt_id) AS total
        --(SELECT COUNT(norec) FROM strukorder_t WHERE objectruangantujuanfk = x.id_ruangan and objectkelompoktransaksifk = $kelompokTrsansaksiMutasi AND statusorder IS NULL ) AS terpesan
        FROM
        (
            SELECT
            CAST(tt.nomorbed AS INT) AS nomor,
            tt.ID AS tt_id,
            tt.nomorbed AS namabed,
            kmr.ID AS kmr_id,
            kmr.namakamar,
            ru.ID AS id_ruangan,
            ru.namaruangan,
            kls.namakelas,
            sb.statusbed,
            CASE WHEN sb.ID = $statusBedIsi THEN 1 ELSE 0 END AS isi,
            CASE WHEN sb.ID = $statusBedKosong THEN 1 ELSE 0 END AS kosong,
            CASE WHEN sb.ID = $statusBedRusak THEN 1 ELSE 0 END AS rusak,
            CASE WHEN sb.ID = $statusBedDipesan THEN 1 ELSE 0 END AS dipesan
            FROM
            tempattidur_m AS tt
            INNER JOIN statusbed_m AS sb ON sb.ID = tt.objectstatusbedfk
            INNER JOIN kamar_m AS kmr ON kmr.ID = tt.objectkamarfk
            INNER JOIN kelas_m AS kls ON kls.ID = kmr.objectkelasfk
            INNER JOIN ruangan_m AS ru ON ru.ID = kmr.objectruanganfk
            WHERE
            tt.kdprofile = $kdProfile
            AND tt.statusenabled = TRUE
            AND kmr.statusenabled = TRUE
            $ruanganfk
            ) AS x
            GROUP BY
            x.namaruangan, x.id_ruangan;

            "));
        $totalKamar = $data->count();
        $totalBed = 0;
        $totalIsi = 0;
        $totalKosong = 0;
        $totalTerpesan = 0;
        foreach ($data as $item) {
            $totalBed =    $totalBed + (float) $item->total;
            $totalIsi =    $totalIsi + (float) $item->isi;
            $totalKosong =    $totalKosong + (float) $item->kosong;
            $totalTerpesan = $totalTerpesan + (float) $item->terpesan;
        }

        $tt = collect(DB::select("SELECT
            ru.id AS idruangan,
            ru.namaruangan,
            km.id AS idkamar,
            km.namakamar,
            tt.id AS idtempattidur,
            tt.reportdisplay,
            tt.nomorbed,
            sb.id AS idstatusbed,
            sb.statusbed,
            kl.id AS idkelas,
            kl.namakelas
            FROM
            tempattidur_m AS tt
            LEFT JOIN kamar_m AS km ON km.id = tt.objectkamarfk
            LEFT JOIN ruangan_m AS ru ON ru.id = km.objectruanganfk
            LEFT JOIN statusbed_m AS sb ON sb.id = tt.objectstatusbedfk
            LEFT JOIN kelas_m AS kl ON kl.id = km.objectkelasfk
            WHERE
            ru.objectdepartemenfk IN (16,35)
            $ruanganfk
            AND ru.statusenabled = true
            AND km.statusenabled = true
            AND tt.statusenabled = true
            AND tt.kdprofile = $kdProfile"));

        $data10 = [];
        $sama = false;
        $bed = 0;
        $isi = 0;
        $kosong = 0;
        $rusak = 0;
        foreach ($tt as $item) {
            $sama = false;
            $i = 0;
            foreach ($data10 as $hideung) {
                if ($item->namaruangan == $data10[$i]['namaruangan']) {
                    $sama = 1;
                    $jml = (float)$hideung['bed'] + 1;
                    $data10[$i]['bed'] = $jml;
                    if ($item->idstatusbed == 1) {
                        $data10[$i]['isi'] = (float)$hideung['isi'] + 1;
                    }
                    if ($item->idstatusbed == 2) {
                        $data10[$i]['kosong'] = (float)$hideung['kosong'] + 1;
                    }
                    if ($item->idstatusbed == 6) {
                        $data10[$i]['rusak'] = (float)$hideung['rusak'] + 1;
                    }
                }
                $i = $i + 1;
            }
            if ($sama == false) {
                if ($item->idstatusbed == 1) {
                    $isi = 1;
                    $kosong = 0;
                    $rusak = 0;
                }
                if ($item->idstatusbed == 2) {
                    $isi = 0;
                    $kosong = 1;
                    $rusak = 0;
                }
                if ($item->idstatusbed == 5) {
                    $isi = 0;
                    $kosong = 0;
                    $rusak = 1;
                }

                $data10[] = array(
                    'idruangan' => $item->idruangan,
                    'namaruangan' => $item->namaruangan,
                    'idstatusbed' => $item->idstatusbed,
                    'bed' => 1,
                    'kosong' => $kosong,
                    'isi' => $isi,
                    'rusak' => $rusak,
                    'terpesan' => 0,
                );
            }
        }

        $res['totalKamar'] = $totalKamar;
        $res['totalBed'] = $totalBed;
        $res['totalIsi'] = $totalIsi;
        $res['totalKosong'] = $totalKosong;
        $res['totalTerpesan'] = $totalTerpesan;
        $res['data'] = $data;

        $res['detail'] = $data10;
        $res['as'] = '@epic';
        return $this->respond($res);
    }

    public function getDetailBed(Request $request)
    {
        $ruangx = $request->ruangan;
        $kdProfile = $this->kdProfile;
        $data = DB::select(DB::raw("
                            SELECT DISTINCT
                            tt.ID AS bed_id,
                            ru.namaruangan,
                            kmr.namakamar,
                            kls.namakelas,
                            tt.reportdisplay AS nobed,
                            sb.reportdisplay AS status,
                            sb.ID AS statusid,
                            ps.nocm,
                            ps.namapasien,
                            ps.jeniskelamin,
                            ps.tgllahir AS tanggal_lahir,
                            EXTRACT(YEAR FROM AGE(CURRENT_DATE, ps.tgllahir)) || ' Thn ' ||
                            EXTRACT(MONTH FROM AGE(CURRENT_DATE, ps.tgllahir)) || ' Bln ' ||
                            EXTRACT(DAY FROM AGE(CURRENT_DATE, ps.tgllahir)) || ' Hr' AS umur
                            FROM
                            tempattidur_m AS tt
                            LEFT JOIN kamar_m AS kmr ON kmr.ID = tt.objectkamarfk
                            LEFT JOIN statusbed_m AS sb ON sb.ID = tt.objectstatusbedfk
                            LEFT JOIN ruangan_m AS ru ON ru.ID = kmr.objectruanganfk
                            LEFT JOIN pasiendaftar_t as pd on pd.objectruanganlastfk = ru.id
                            LEFT JOIN kelas_m AS kls ON kls.ID = kmr.objectkelasfk
                            LEFT JOIN (
                                SELECT
                                *
                                FROM
                                (
                                    SELECT P
                                    .nocm,
                                    pd.noregistrasi,
                                    P.namapasien,
                                    P.tgllahir,
                                    jk.jeniskelamin,

                                    CASE
                                    WHEN P.nobpjs IS NULL THEN
                                    '-' ELSE P.nobpjs
                                    END AS nobpjs,
                                    apd.nobed,
                                    ROW_NUMBER ( ) OVER ( PARTITION BY pd.noregistrasi ORDER BY apd.tglmasuk DESC ) AS rownum
                                    FROM
                                    pasiendaftar_t AS pd
                                    lEFT JOIN antrianpasiendiperiksa_t AS apd ON pd.norec = apd.noregistrasifk
                                    AND apd.tglkeluar IS NULL --inner join registrasipelayananpasien_t as rpp on rpp.noregistrasifk=pd.norec
                                    LEFT JOIN pasien_m AS P ON P.ID = pd.nocmfk
                                    LEFT JOIN jeniskelamin_m as jk ON P.objectjeniskelaminfk = jk.id
                                    LEFT JOIN ruangan_m AS ru ON ru.ID = pd.objectruanganlastfk
                                    LEFT JOIN batalregistrasi_t AS br ON pd.norec = br.pasiendaftarfk
                                    WHERE
                                    br.norec IS NULL
                                    AND pd.tglpulang IS NULL
                                    AND pd.kdprofile = 1
                                    AND pd.statusenabled = TRUE
                                    AND apd.nobed IS NOT NULL
                                    AND apd.statusenabled is true
                                    ) AS x
                                    WHERE
                                    x.rownum = 1
                                    ) AS ps ON ps.nobed = tt.ID
                                    WHERE
                                    tt.statusenabled = TRUE
                                    AND tt.kdprofile = $kdProfile
                                    AND ru.ID = $ruangx
                                    ORDER BY
                                    ru.namaruangan ASC
                                    "));

        $result = [
            'data' => $data,
            'message' => 'success',
        ];

        return $this->respond($result);
    }

    public function getInfoLayanan(Request $request)
    {
        $data = DB::table('produk_m as pr')
            ->leftjoin('mapruangantoproduk_m as mprtp', 'mprtp.objectprodukfk', 'pr.id')
            ->leftjoin('harganettoprodukbykelas_m as hrpk', 'hrpk.objectprodukfk', 'pr.id')
            ->join('kelas_m as kls', 'kls.id', 'hrpk.objectkelasfk')
            ->join('jenispelayanan_m as jnsp', 'jnsp.id', 'hrpk.objectjenispelayananfk')
            ->join('ruangan_m as ru', 'ru.id', 'mprtp.objectruanganfk')
            ->leftjoin('rekanan_m as rkn', 'rkn.id', 'hrpk.objectpenjaminfk')
            ->leftjoin('kebangsaan_m as kb', 'kb.id', 'hrpk.objectkebangsaanfk')
            ->select(
                'pr.id',
                'pr.namaproduk',
                'hrpk.harganetto1 AS hargalayanan',
                'kls.id as idkelas',
                'kls.namakelas',
                'jnsp.id as jenispelayananid',
                'jnsp.jenispelayanan',
                'mprtp.objectruanganfk as ruid',
                'ru.id as ruid',
                'ru.namaruangan',
                'rkn.namarekanan',
                'kb.name as kebangsaan',
                DB::raw("CASE WHEN hrpk.hargadijamin IS NULL THEN 0 ELSE hrpk.hargadijamin END AS hargadijamin"),
                'hrpk.objectpenjaminfk'
            )
            ->where('pr.kdprofile', $this->kdProfile)
            ->where('pr.statusenabled', true)
            ->distinct();

        if (isset($request['idruangan']) && $request['idruangan'] != '') {
            $data = $data->where('mprtp.objectruanganfk', '=',  $request['idruangan']);
        }
        if (isset($request['idkelas']) && $request['idkelas'] != '') {
            $data = $data->where('kls.id', '=',  $request['idkelas']);
        }
        if (isset($request['idkebangsaan']) && $request['idkebangsaan'] != '') {
            $data = $data->where('kb.id', '=',  $request['idkebangsaan']);
        }
        if (isset($request['idrekanan']) && $request['idrekanan'] != '' && $request['idrekanan'] == 'UMUM') {
            $data = $data->whereNull('hrpk.objectpenjaminfk');
        } else if (isset($request['idrekanan']) && $request['idrekanan'] != '') {
            $data = $data->where('hrpk.objectpenjaminfk', '=',  $request['idrekanan']);
        }
        if (isset($request['idproduk']) && $request['idproduk'] != '') {
            $data = $data->where('pr.id', '=',  $request['idproduk']);
        }
        if (isset($request['jenispelayananid']) && $request['jenispelayananid'] != '') {
            $data = $data->where('jnsp.id', '=',  $request['jenispelayananid']);
        }
        if (isset($request['offset']) && $request['offset'] != '') {
            $data = $data->offset($request['offset']);
        }
        if (isset($request['limit']) && $request['limit'] != '') {
            $data = $data->limit($request['limit']);
        }
        $res['total'] = $data->count();
        $data = $data->get();

        $res['data'] = $data;
        return $this->respond($res);
    }

    public function getPilihan(Request $request)
    {
        $idProfile = (int)$this->kdProfile;
        $res['ruangan'] = Ruangan::select('id', 'namaruangan')->where('kdprofile', $idProfile)->where('statusenabled', true)->get();

        $res['kelas'] = Kelas::mine()->get();
        // $res['jenispelayanan'] = JenisPelayanan::mine()->get();
        $res['rekanan'] = Rekanan::mine()->where('id', 2551)->get();
        $res['produk'] = Produk::mine()->get();
        $res['kebangsaan'] = Kebangsaan::mine()->get();

        return $this->respond($res);
    }

    public function getDaftarRegistrasiPasien(Request $request)
    {

        $dateRange = [$request->tglAwal, $request->tglAkhir];

        $data = DB::table('pasiendaftar_t as pd')
        ->join('pasien_m as ps', 'ps.id','pd.nocmfk')
        ->leftjoin('pegawai_m as pg','pg.id','pd.objectpegawaifk')
        ->leftJoin('kelompokpasien_m as kp', 'kp.id','pd.objectkelompokpasienlastfk')
        // ->join('antrianpasiendiperiksa_t as apd', 'pd.norec','apd.noregistrasifk')
        ->join('ruangan_m as ru', 'ru.id','pd.objectruanganlastfk')
        ->join('departemen_m as dept','dept.id','ru.objectdepartemenfk')
        ->leftJoin('strukpelayanan_t as sp','sp.norec','pd.nostruklastfk')
        ->leftJoin('strukbuktipenerimaan_t as sbm','sbm.norec','pd.nosbmlastfk')
        ->leftjoin('loginuser_s as lu', 'lu.id','sbm.objectpegawaipenerimafk')
        ->leftjoin('pegawai_m as pgs', 'pgs.id','lu.objectpegawaifk')
        ->leftjoin('pemakaianasuransi_t as pas','pas.noregistrasifk','pd.norec')
        ->leftjoin('batalregistrasi_t as br','br.pasiendaftarfk','pd.norec')
        ->select(
            'pd.norec',
            'pd.tglregistrasi',
            'ps.nocm',
            'pd.noregistrasi',
            'ru.namaruangan',
            'ps.namapasien',
            'kp.kelompokpasien',
            'pd.tglpulang',
            'pd.statuspasien',
            'sp.nostruk',
            'sbm.nosbm',
            'pg.id as pgid',
            'pg.namalengkap as namadokter',
            'pgs.namalengkap as kasir',
            'pd.objectruanganlastfk as ruanganid',
            'pas.nosep',
            'br.norec as norec_br'
        )
        ->whereNull('br.norec')
        ->where('pd.kdprofile', $this->kdProfile)
        ->whereBetween(DB::raw('pd.tglregistrasi::date'), $dateRange);

        if (isset($request['ruanganfk']) && $request['ruanganfk'] != "" && $request['ruanganfk'] != "undefined") {
            $data = $data->where('ru.id', '=', $request['ruanganfk']);
        }
        if (isset($request['departemen']) && $request['departemen'] != "" && $request['departemen'] != "undefined") {
            $data = $data->where('dept.id', '=', $request['departemen']);
        }

        if (isset($request['keyword']) && $request['keyword'] != '') {
            $searchTerm = '%' . $request['keyword'] . '%';
            $data = $data->where(function ($query) use ($searchTerm) {
                $query->where('ps.namapasien', 'ilike', $searchTerm)
                    ->orWhere('pd.noregistrasi', 'ilike', $searchTerm)
                    ->orWhere('ps.nocm', 'ilike', $searchTerm);
            });
        }

        $data = $data->orderBy('pd.noregistrasi');
        $data = $data->get();
        return $this->respond($data);
    }

}
