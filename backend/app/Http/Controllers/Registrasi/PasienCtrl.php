<?php

namespace App\Http\Controllers\Registrasi;

use App\Http\Controllers\Controller;
use App\Models\Master\Agama;

use App\Models\Master\GolonganDarah;
use App\Models\Master\JenisKelamin;
use App\Models\Master\JenisPegawai;
use App\Models\Master\Kebangsaan;
use App\Models\Master\Negara;
use App\Models\Master\Pasien;
use App\Models\Master\Pekerjaan;
use App\Models\Master\Pendidikan;
use App\Models\Master\StatusPerkawinan;
use App\Models\Master\Suku;
use App\Models\Transaksi\PasienDaftar;
use App\Traits\Valet;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PasienCtrl extends Controller
{
    use Valet;
    public function __construct()
    {
        parent::__construct($is_encrypt = true);
    }
    public function listPasienGrid(Request $r)
    {
        try {
            $count = 0;
            $page = 1;

            $data  = DB::table('pasien_m as ps')
                ->join('alamat_m as alm', 'alm.nocmfk', '=', 'ps.id')
                ->join('jeniskelamin_m as jk', 'jk.id', '=', 'ps.objectjeniskelaminfk')
                ->leftJoin('pasiendaftar_t as pd', function ($join) use ($r) {
                    $join->on('ps.id', '=', 'pd.nocmfk');
                    $join->where('pd.statusenabled', true);
                })
                ->leftjoin('antrianpasienregistrasi_t as apr', 'apr.norec', '=', 'pd.antrianpasienregistrasifk')
                ->leftjoin('pemakaianasuransi_t as pa', 'pa.noregistrasifk', '=', 'pd.norec')
                ->leftjoin('ruangan_m as ru', 'pd.objectruanganlastfk', '=', 'ru.id')
                ->leftjoin('pegawai_m as pg', 'pd.objectpegawaifk', '=', 'pg.id')
                ->leftjoin('kebangsaan_m as kbs', 'ps.objectkebangsaanfk', '=', 'kbs.id')
                ->leftjoin('kelompokpasien_m as kp', 'pd.objectkelompokpasienlastfk', '=', 'kp.id')
                ->leftjoin(db::raw('(select distinct noregistrasifk, noregistrasifk as norec from emrpasien_t where statusenabled=true) as emrp'),'emrp.noregistrasifk','=','pd.norec')
                ->select(
                    'ps.id',
                    'ps.namapasien',
                    'ps.nocm',
                    'ps.noidentitas',
                    'ps.nohp',
                    'ps.nobpjs',
                    'jk.jeniskelamin',
                    'alm.alamatlengkap',
                    'ps.tempatlahir',
                    'ps.tgllahir',
                    'ru.namaruangan',
                    'pd.noregistrasi',
                    'pd.norec as norec_pd',
                    'emrp.norec as norec_emr',
                    'pd.tglregistrasi',
                    'kbs.name as kebangsaan',
                    'pd.objectkelompokpasienlastfk',
                    'ru.objectdepartemenfk',
                    'kp.kelompokpasien',
                    'ps.id as nocmfk',
                    'ru.kdinternal as kdsubspesialis',
                    DB::raw("case when length(apr.noantrian::varchar) = 1 then apr.jenis || '00' || apr.noantrian::varchar
                    when length(apr.noantrian::varchar) = 2 then apr.jenis || '0' || apr.noantrian::varchar
                    when length(apr.noantrian::varchar) = 3 then apr.jenis || '' || apr.noantrian::varchar end as antrianloket"),
                    DB::raw("case when jk.id = 1 then kbs.name || ' - (L)' else kbs.name || ' - (P)' end as kodejk"),
                    'pa.nosep',
                    'pa.user',
                    'pa.tglsep',
                    'pd.dikunjungi',
                    'pd.ismobilejkn',
                    'pd.ischeckin',
                    'apr.noreservasi',
                    'pd.objectruanganlastfk',
                    'ps.tglmeninggal',
                    'pd.tglcetak',
                    'pd.tglclosing',
                    'pd.tglpulang',
                    'pd.isgadar',
                    'ps.progress',
                    'pd.isRencanaMutasi'
                )
                ->where('ps.statusenabled', true)
                ->where('ps.kdprofile', $this->kdProfile);

            if (isset($r['dari']) && $r['dari'] != '' && isset($r['sampai']) && $r['sampai'] != '' && !isset($r['isAllPeriode'])) {
                $data = $data->whereBetween('pd.tglregistrasi', [$r['dari'],  $r['sampai']]);
            }
            if (isset($r['search']) && $r['search'] != '') {
                $searchTerm = '%' . $r['search'] . '%';
                $data = $data->where(function ($query) use ($searchTerm) {
                    $query->where('ps.namapasien', 'ilike', $searchTerm)
                        ->orWhere('pd.noregistrasi', 'ilike', $searchTerm)
                        ->orWhere('ps.nocm', 'ilike', $searchTerm)
                        ->orWhere('ps.nobpjs', 'ilike', $searchTerm)
                        ->orWhere('ps.noidentitas', 'ilike', $searchTerm);
                });
            }
            if (isset($r['id']) && $r['id'] != '') {
                $data = $data->where('ps.id', '=',  $r['id']);
            }
            if (isset($r['namapasien']) && $r['namapasien'] != '') {
                $data = $data->where('ps.namapasien', 'ilike', '%' . $r['namapasien'] . '%');
            }
            if (isset($r['pasien_aktif']) && $r['pasien_aktif'] != '' && $r['pasien_aktif'] == 'true') {
                $data = $data->whereNotNull('pd.norec');
            } else {
                $data = $data->whereNull('pd.norec');
            }
            if (isset($r['isKiosk']) && $r['isKiosk'] == true) {
                $data = $data->where('pa.user', '=', 'Kiosk');
            }
            if (isset($r['noregistrasi']) && $r['noregistrasi'] != '') {
                $data = $data->where('pd.noregistrasi', 'ilike', '%' . $r['noregistrasi'] . '%');
            }
            if (isset($r['noantrian']) && $r['noantrian'] != '') {
                $noantrian = $r['noantrian'];
                $data = $data->whereRaw("(case when length(apr.noantrian::varchar) = 1 then apr.jenis || '00' || apr.noantrian::varchar
                    when length(apr.noantrian::varchar) = 2 then apr.jenis || '0' || apr.noantrian::varchar
                    when length(apr.noantrian::varchar) = 3 then apr.jenis || '' || apr.noantrian::varchar end) ilike '%$noantrian%'");
            }
            if (isset($r['nocm']) && $r['nocm'] != '') {
                $data = $data->where('ps.nocm', 'ilike', '%' . $r['nocm'] . '%');
            }
            if (isset($r['nik']) && $r['nik'] != '') {
                $data = $data->where('ps.noidentitas', '=',  $r['nik']);
            }
            if (isset($r['nobpjs']) && $r['nobpjs'] != '') {
                $data = $data->where('ps.nobpjs', '=',  $r['nobpjs']);
            }
            if (isset($r['alamat']) && $r['alamat'] != '') {
                $data = $data->where('alm.alamatlengkap', '=',  $r['alamat']);
            }
            if (isset($r['kelompokpasienfk']) && $r['kelompokpasienfk'] != '') {
                $data = $data->whereIN('pd.objectkelompokpasienlastfk',  explode(',', $r['kelompokpasienfk']));
            }
            if (isset($r['instalasifk']) && $r['instalasifk'] != '') {
                $data = $data->whereIN('ru.objectdepartemenfk', explode(',', $r['instalasifk']));
            }
            if (isset($r['ruanganfk']) && $r['ruanganfk'] != '' && $r['ruanganfk'] != 'null') {
                $data = $data->where('ru.id',  $r['ruanganfk']);
            }
            if (!empty($r['unit']) && $r['unit'] == 3) {
                $data = $data->where('ru.objectdepartemenfk',  16);
            } else if (!empty($r['unit']) && $r['unit'] == 2) {
                $data = $data->where('ru.objectdepartemenfk',  9);
            } else if (!empty($r['unit']) && $r['unit'] == 1) {
                $data = $data->whereNotIn('ru.objectdepartemenfk',  [16, 9]);
            }
            if (isset($r['isNoSEP']) && $r['isNoSEP'] != '') {
                $data = $data->where('pd.objectkelompokpasienlastfk', 2);
                $data = $data->whereNull('pa.nosep');
            }

            $data = $data->orderByDesc('pd.tglregistrasi');
            $data = $data->paginate(isset($r['limit']) ? $r['limit'] : 50);
    
            if (isset($r['page']) && $r['page'] != '') {
                $page = $r['page'];
            }

            $data->setCollection($data->getCollection()->transform(function ($d) {
                // Format and calculate age
                $d->tgllahir = date('Y-m-d', strtotime($d->tgllahir));
                $d->umur = $this->getAge($d->tgllahir, date('Y-m-d H:i:s'));

                // Determine status (alive or deceased)
                $d->status = $d->tglmeninggal ? 'Meninggal' : 'Hidup';
                $d->status_c = $d->tglmeninggal ? 'danger' : 'purple';

                // Handle progress
                $d->progress = (int) ($d->progress ?? 0);

                if ($d->progress <= 50) {
                    $d->class_proggress = 'danger';
                } elseif ($d->progress <= 80) {
                    $d->class_proggress = 'warning';
                } else {
                    $d->class_proggress = 'success';
                }

                // Handle kebangsaan
                switch ($d->kebangsaan) {
                    case 'WNA KITAS':
                        $d->class_kebangsaan = 'tag is-warning';
                        break;
                    case 'WNA NON KITAS':
                        $d->class_kebangsaan = 'tag is-danger';
                        break;
                    default:
                        $d->class_kebangsaan = 'tag is-info';
                        break;
                }

                // Status pulang
                $d->class_statuspulang = $d->tglpulang ? 'tag is-primary' : 'tag is-danger';
                $d->label_statuspulang = $d->tglpulang ? 'Pulang' : 'Belum Pulang';

                // Status mutasi
                $d->class_statusmutasi = $d->isRencanaMutasi ? 'tag is-warning' : null;
                $d->label_statusmutasi = $d->isRencanaMutasi ? 'Rencana Mutasi' : null;

                // Status periksa
                $d->class_statusperiksa = $d->norec_emr ? 'tag is-primary' : 'tag is-danger';
                $d->label_statusperiksa = $d->norec_emr ? 'Sudah Periksa' : 'Belum Periksa';

                // Status closing
                $d->class_statusclosing = $d->tglclosing ? 'tag is-primary' : 'tag is-danger';
                $d->label_statusclosing = $d->tglclosing ? 'Sudah Closing' : 'Belum Closing';

                // Status gawat darurat
                if ($d->isgadar !== null) {
                    $d->class_statusgadar = 'tag is-danger';
                    $d->label_statusgadar = 'Gawat Darurat';
                } else {
                    $d->class_statusgadar = null;
                    $d->label_statusgadar = null;
                }

                return $d;
            }));

            $res['data'] = $data;
            $res['total'] = count($data);
            return $this->respond($res);
        } catch (\Exception $e) {
            $res = [
                "message" => $e->getMessage(),
                "data" => null,
                "code" => 400
            ];
            return $this->respond($res);
        }

    }
    public function CountDaftar(Request $r)
    {
        $tglAwal = $r->dari . " 00:00:00";
        $tglAkhir = $r->sampai . " 23:59:59";

        $range = "";
        if ($tglAwal != '' && $tglAkhir != '' && $tglAwal != null && $tglAkhir != null) {
            $range = " and pd.tglregistrasi between '" . $tglAwal . "' and '" . $tglAkhir . "'";
        }
        $data = DB::select(DB::raw("SELECT COUNT
        ( kp.kelompokpasien ) AS jmlkp , kp.kelompokpasien, kp.id
    FROM
        pasien_m AS ps
        INNER JOIN alamat_m AS alm ON alm.nocmfk = ps.id
        LEFT JOIN pasiendaftar_t AS pd ON ps.id = pd.nocmfk
        AND pd.statusenabled = true
        AND pd.norec IS NOT NULL
        LEFT JOIN antrianpasienregistrasi_t AS apr ON apr.norec = pd.antrianpasienregistrasifk
        LEFT JOIN pemakaianasuransi_t AS pa ON pa.noregistrasifk = pd.norec
        LEFT JOIN ruangan_m AS ru ON pd.objectruanganlastfk = ru.id
        LEFT JOIN pegawai_m AS pg ON pd.objectpegawaifk = pg.id
        LEFT JOIN kelompokpasien_m AS kp ON pd.objectkelompokpasienlastfk = kp.id
        INNER JOIN jeniskelamin_m AS jk ON jk.id = ps.objectjeniskelaminfk
    WHERE
        ps.statusenabled = true
        $range
        AND ps.kdprofile = 1
        AND pd.norec IS NOT NULL
        GROUP BY  kp.kelompokpasien, kp.id"));

        $data2 = DB::select(DB::raw("SELECT COUNT( ru.objectdepartemenfk ) AS jmldp , dp.namadepartemen, dp.id
        FROM pasien_m AS ps INNER JOIN alamat_m AS alm ON alm.nocmfk = ps.id LEFT JOIN pasiendaftar_t AS pd ON ps.id = pd.nocmfk
        AND pd.statusenabled = true
        AND pd.norec IS NOT NULL
        LEFT JOIN antrianpasienregistrasi_t AS apr ON apr.norec = pd.antrianpasienregistrasifk
        LEFT JOIN pemakaianasuransi_t AS pa ON pa.noregistrasifk = pd.norec
        LEFT JOIN ruangan_m AS ru ON pd.objectruanganlastfk = ru.id
        LEFT JOIN departemen_m AS dp ON ru.objectdepartemenfk = dp.id
        LEFT JOIN pegawai_m AS pg ON pd.objectpegawaifk = pg.id
        LEFT JOIN kelompokpasien_m AS kp ON pd.objectkelompokpasienlastfk = kp.id
        INNER JOIN jeniskelamin_m AS jk ON jk.id = ps.objectjeniskelaminfk
        WHERE
        ps.statusenabled = true
        $range
        AND ps.kdprofile = 1
        AND pd.norec IS NOT NULL
        GROUP BY  dp.namadepartemen, dp.id"));

        $res['kelompokpasien'] = $data;
        $res['departemen'] = $data2;

        return $this->respond($res);
    }

    public function batalMeninggal(Request $request){

        DB::beginTransaction();
        try {
            Pasien::where('id', $request['nocmfk'])->where('kdprofile', $this->kdProfile)
                ->where('statusenabled', true)
                ->update([
                    'tglmeninggal' => null,
                ]);

            PasienDaftar::where('norec', $request['norec_pd'])->where('kdprofile', $this->kdProfile)
                ->where('statusenabled', true)
                ->update(['tglmeninggal' => null]);

            DB::commit();

            $result = [
                'status' => 200,
                'message' => 'Batal Meninggal Berhasil',
            ];

        } catch (Exception $e) {
           DB::rollBack();
            $result = [
                'status' => 400,
                'data' => $e->getMessage(),
                'message' => 'Batal Meninggal Gagal',
            ];
        }

        return $this->respond($result,$result['status'],$result['message']);


    }
}
