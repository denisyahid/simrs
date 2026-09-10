<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Master\Departemen;
use App\Models\Master\JadwalDokter;
use App\Models\Master\Kamar;
use App\Models\Master\Ruangan;
use App\Models\Standar\KelompokUser;
use App\Models\Standar\MapLoginUserToRuangan;
use App\Models\Transaksi\AntrianPasienDiperiksa;
use App\Models\Transaksi\PasienDaftar;
use App\Models\Transaksi\StokProdukDetail;
use App\Traits\Valet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardIGDCtrl extends Controller
{
    use Valet;
    public function __construct()
    {
        parent::__construct($is_encrypt = true);
    }
    // DROPDOWN RUANGAN

    public function getIGD(Request $r)
    {
        $set = explode(',', $this->settingFix('idDepartemenIGD'));
        $res['ruangan'] = Ruangan::mine()->whereIn('objectdepartemenfk', $set)->get();

        return $this->respond($res);
    }

    public function getIGDDetail(Request $r)
    {
        $now = $this->hari_ini(date('Y-m-d'));
        $dokter  = DB::table('jadwaldokter_m as jd')
            ->leftjoin('pegawai_m as pg', 'pg.id', '=', 'jd.objectpegawaifk')
            ->leftjoin('ruangan_m as ru', 'ru.id', '=', 'jd.objectruanganfk')
            ->select(
                'pg.namalengkap',
                'jd.jammulai',
                'jd.jamakhir',
                'ru.namaruangan',
                DB::raw("lower(jd.hari) as hari"),
            )
            ->where('jd.kdprofile', $this->kdProfile)
            ->whereIn('ru.objectdepartemenfk', explode(',', $this->settingFix('idDepartemenIGD')))
            ->where('jd.statusenabled', true)
            ->where('ru.statusenabled', true)
            ->where('pg.statusenabled', true)
            ->where('jd.hari', 'ilike', '%' . $now . '%')
            ->where('pg.namalengkap', '<>', 'Dokter Umum');

        if (isset($r['ruanganid']) && $r['ruanganid'] != '') {
            $dokter = $dokter->where('ru.id', '=',  $r['ruanganid']);
        }
        if (isset($r['namadokter']) && $r['namadokter'] != '') {
            $dokter = $dokter->where('pg.namalengkap', 'ilike',  '%' . $r['namadokter'] . '%');
        }
        if (isset($r['limit']) && $r['limit'] != '') {
            $dokter = $dokter->limit($r['limit']);
        }
        $dokter->orderBy('pg.namalengkap');

        $dokter =  $dokter->get();

        foreach ($dokter as $d) {
            $d->hari = $now;
        }
        $produk  = DB::table('stokprodukdetail_t as spd')
            ->join('ruangan_m as ru', 'spd.objectruanganfk', '=', 'ru.id')
            ->leftjoin('produk_m as pr', 'spd.objectprodukfk', '=', 'pr.id')
            ->leftjoin('asalproduk_m as ap', 'spd.objectasalprodukfk', '=', 'ap.id')
            ->select(
                DB::raw("sum(spd.qtyproduk) as qtyproduk,
                ru.id,
                ru.namaruangan,
                pr.namaproduk,
                ap.asalproduk,
                spd.harganetto1,
                spd.harganetto2")
            )
            ->where('spd.kdprofile', $this->kdProfile)
            ->whereIn('ru.objectdepartemenfk', explode(',', $this->settingFix('idDepartemenIGD')))
            ->where('spd.statusenabled', true);

        if (isset($r['ruanganid']) && $r['ruanganid'] != '') {
            $produk->where('ru.id', '=',  $r['ruanganid']);
        }
        if (isset($r['nama']) && $r['nama'] != '') {
            $produk->where('pr.namaproduk', 'ilike',  '%' . $r['nama'] . '%');
        }
        if (isset($r['limit']) && $r['limit'] != '') {
            $produk->limit($r['limit']);
        }
        $produk->groupBy('ru.id', 'ru.namaruangan',  'pr.namaproduk',  'ap.asalproduk',  'spd.harganetto1', 'spd.harganetto2');
        $produk->orderBy('pr.namaproduk');
        $produk =  $produk->get();


        $res['dokter'] = $dokter;
        $res['produk'] = $produk;
        return $this->respond($res);
    }

    public function getIGDPasien(Request $r)
    {
        $data  = DB::table('antrianpasiendiperiksa_t as apd')
            ->join('pasiendaftar_t as pd', 'apd.noregistrasifk', '=', 'pd.norec')
            ->join('ruangan_m as ru_last','pd.objectruanganlastfk','ru_last.id')
            ->join('ruangan_m as ru', 'apd.objectruanganfk', '=', 'ru.id')
            ->leftjoin('pegawai_m as pg', 'pd.objectpegawaifk', '=', 'pg.id')
            ->leftjoin('pegawai_m as dod', 'pd.objectpegawaifk_dod', '=', 'dod.id')
            ->join('pasien_m as ps', 'pd.nocmfk', '=', 'ps.id')
            ->join('kebangsaan_m as kbs', 'kbs.id', '=', 'ps.objectkebangsaanfk')
            ->leftjoin('aksesemr_t as emr', 'emr.pasienfk', '=', 'ps.id')
            ->leftjoin('alamat_m as alm', 'alm.nocmfk', '=', 'ps.id')
            ->leftjoin('kotakabupaten_m as kbp', 'alm.objectkotakabupatenfk', '=', 'kbp.id')
            ->leftjoin('kecamatan_m as km', 'alm.objectkecamatanfk', '=', 'km.id')
            ->leftjoin('desakelurahan_m as ds', 'alm.objectdesakelurahanfk', '=', 'ds.id')
            ->leftjoin('jeniskelamin_m as jk', 'jk.id', '=', 'ps.objectjeniskelaminfk')
            ->leftjoin('kelompokpasien_m as kp', 'pd.objectkelompokpasienlastfk', '=', 'kp.id')
            ->leftjoin('pemakaianasuransi_t as pa', 'pa.noregistrasifk', '=', 'pd.norec')
            ->select(
                'kbs.name as kebangsaan',
                'ru.namaruangan',
                'pd.norec as norec_pd',
                'apd.norec as norec_apd',
                'pd.nocmfk',
                'dod.id as id_dod',
                'dod.namalengkap as dod',
                'ps.nocm',
                'ds.namadesakelurahan',
                'km.namakecamatan',
                'kbp.namakotakabupaten',
                'ps.alamatrmh',
                'ru.objectdepartemenfk',
                'ru_last.objectdepartemenfk as iddepartlastruangan',
                'ps.tgllahir',
                'emr.tglberakhir',
                'emr.tglmulai',
                'emr.objectkelompokuserfk',
                'emr.pegawaipemohonfk',
                'ps.nobpjs',
                'ps.noidentitas',
                'pd.objectpegawaifk',
                'pd.noregistrasi',
                'pd.tglmeninggal',
                'pg.namalengkap',
                'ps.namapasien',
                'jk.jeniskelamin',
                'kp.kelompokpasien',
                'apd.tglkeluar as tglkeluar',
                'pd.tglpulang as tglpulang',
                'pa.nosep',
                'pd.isRencanaMutasi',
                DB::raw("TO_CHAR(apd.tglregistrasi, 'HH24:MI:SS') as tgldaftar"),
                DB::raw("CAST(pd.tglregistrasi
                AS DATE),
                (case when pd.ispelayananpasien=true then 'Selesai' else 'Menunggu Pelayanan' end) as statuspelayanan,
                ps.objectjeniskelaminfk"),
                DB::raw(
                    "
                CASE
                    WHEN apd.tglkeluar IS NULL THEN
                    FLOOR(EXTRACT(EPOCH FROM NOW() - pd.tglregistrasi)::INTEGER / (24 * 3600)) || ' hari ' ||
                    FLOOR(EXTRACT(EPOCH FROM NOW() - pd.tglregistrasi)::INTEGER % (24 * 3600) / 3600) || ' jam ' ||
                    FLOOR(EXTRACT(EPOCH FROM NOW() - pd.tglregistrasi)::INTEGER % 3600 / 60) || ' menit'
                    ELSE
                        FLOOR(EXTRACT(EPOCH FROM apd.tglkeluar - apd.tglmasuk)::INTEGER / (24 * 3600)) || ' hari ' ||
                        FLOOR(EXTRACT(EPOCH FROM apd.tglkeluar - apd.tglmasuk)::INTEGER % (24 * 3600) / 3600) || ' jam ' ||
                        FLOOR(EXTRACT(EPOCH FROM apd.tglkeluar - apd.tglmasuk)::INTEGER % 3600 / 60) || ' menit'
                END AS selisihWaktu"
                )
            )
            ->where('apd.kdprofile', $this->kdProfile)
            ->whereIn('ru.objectdepartemenfk', explode(',', $this->settingFix('idDepartemenIGD')))
            // ->whereBetween(DB::raw("pd.tglregistrasi::date"),$rangeDate)
            ->where('apd.statusenabled', true)
            ->where('pd.statusenabled', true)
            ->orderByDesc('pd.tglregistrasi');

        if (isset($r['ruanganid']) && $r['ruanganid'] != '') {
            $data = $data->where('ru.id', '=',  $r['ruanganid']);
        }

        if (isset($r['idpegawai']) && $r['idpegawai'] != '') {
            $data = $data->where('pd.objectpegawaifk', '=',  $r['idpegawai']);
        }

        if (isset($r['noregistrasi']) && $r['noregistrasi'] != '') {
            $data = $data->where('pd.noregistrasi', 'ilike', '%' . $r['noregistrasi'] . '%');
        }
        if (isset($r['nocm']) && $r['nocm'] != '') {
            $data = $data->where('ps.nocm',  'ilike', '%' . $r['nocm'] . '%');
        }

        if (isset($r['namapasien']) && $r['namapasien'] != '') {
            $data = $data->where('ps.namapasien', 'ilike', '%' . $r['namapasien'] . '%');
        }
        if (isset($r['dari']) && $r['dari'] != '') {
            $data = $data->where(DB::raw("apd.tglregistrasi::date"), '>=', $r->dari);
        }
        if (isset($r['sampai']) && $r['sampai'] != '') {
            $data = $data->where(DB::raw("apd.tglregistrasi::date"), '<=', $r->sampai);
        }
        if (isset($r['status']) && $r['status'] != '') {
            $data = $data->where('pd.ispelayananpasien', '=', $r['status']);
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
        if (isset($r['statuspanggil']) && $r['statuspanggil'] != '') {
            if ($r['statuspanggil'] == 'Pasien Pulang') {
                $data = $data->whereNotNull('pd.tglpulang');
            }
            else if ($r['statuspanggil'] == 'Pasien Mutasi') {
                $data = $data->where('ru_last.objectdepartemenfk', 16);
            }
            else if ($r['statuspanggil'] == 'Pasien Belum Pulang') {
                $data = $data->whereNull('pd.tglpulang');
            }
        } else {
            // $data = $data->whereNull('pd.tglpulang');
        }
        $total = $data->count();
        if (isset($r['limit']) && $r['limit'] != '') {
            $data = $data->limit($r['limit']);
        }
        if (isset($r['offset']) && $r['offset'] != '') {
            $data = $data->offset($r['offset']);
        }
        $data = $data->orderBy('apd.tglregistrasi');
        $data = $data->get();

        $norecPdValues = $data->pluck('norec_pd')->toArray();

        $isEstimasi = DB::connection('mongodb')
            ->table('PerkiraanBiaya')
            ->select('Parameter_Estimasi', 'registrasi.norec_pd') // Include statuskeluar
            ->whereNotNull('Parameter_Estimasi') // Ensures riwayatkeluar is not null
            ->whereIn('registrasi.norec_pd', $norecPdValues) // Filters by relevant norec_pd values
            ->get()
            ->keyBy('registrasi.norec_pd');

        foreach ($data as $d) {
            $d->umur =  $this->getAgeYear($d->tgllahir, $d->tglregistrasi) . ' thn';

            if (isset($isEstimasi[$d->norec_pd])) {
                $record = $isEstimasi[$d->norec_pd];

                // Assign riwayatkeluar and statuskeluar if they exist
                $d->Parameter_Estimasi = $record['Parameter_Estimasi'] ?? '';
            } else {
                // Assign "" if the norec_pd is not found
                $d->Parameter_Estimasi = '';
            }
        }
        $res['total'] = $total;
        $res['data'] = $data;
        return $this->respond($res);
    }

    public function Ruangandropdown(Request $r)
    {
        $res['namadepartemen'] = Departemen::mine()->get();

        return $this->respond($res);
    }

    public function HitungAntrianIGD(Request $r)
    {
        $kdProfile = $this->kdProfile;
        $data =  DB::table('antrianpasiendiperiksa_t as at')
            ->join('ruangan_m as ru', 'ru.id', '=', 'at.objectruanganfk')
            ->select(DB::raw("count (ru.id) as total, case when at.status is null or (at.statusantrian is null or at.statusantrian = '0')  then 'Belum Dipanggil' else  at.status end as name "))
            ->where('at.statusenabled', true)
            ->where('at.kdprofile', $kdProfile)
            ->whereIN('ru.objectdepartemenfk', explode(',', $this->settingFix('idDepartemenIGD')));
        if (isset($r['ruanganid']) && $r['ruanganid'] != '') {
            $data = $data->where('ru.id', '=',  $r['ruanganid']);
        }
        if (isset($r['dari']) && $r['dari'] != '') {
            $data = $data->where('at.tglregistrasi', '>=', $r['dari'] . ' 00:00');
        }
        if (isset($r['sampai']) && $r['sampai'] != '') {
            $data = $data->where('at.tglregistrasi', '<=',  $r['sampai'] . ' 23:59');
        }
        $data = $data->groupBy('at.status', 'at.statusantrian');
        $data = $data->get();

        $seriesJK = [];
        $labelJK = [];
        foreach ($data as $d) {
            $seriesJK[] = $d->total;
            $labelJK[]  = $d->name;
        }
        $result['chartStatus']['series'] = $seriesJK;
        $result['chartStatus']['labels'] = $labelJK;
        return $this->respond($result);
    }

    public function panggilPasienIGD(Request $r)
    {

        DB::beginTransaction();
        try {
            $status =  'Sudah Dipanggil';
            $kelompokUser = KelompokUser::where('id', session('kelompokuser_id'))->first();
            if (!empty($kelompokUser) && $kelompokUser->kelompokuser == 'dokter') {
                $update =  [
                    'status' => $status,
                    'statusantrian' => 1,
                    'tgldipanggildokter' => date('Y-m-d H:i:s'),
                ];
            } else {
                $update =  [
                    'status' => $status,
                    'statusantrian' => 1,
                    'tgldipanggilsuster' => date('Y-m-d H:i:s'),
                ];
            }
            AntrianPasienDiperiksa::where('norec', $r->norec_apd)
                ->where('kdprofile', $this->kdProfile)
                ->update($update);

            $transMessage = "Sukses";
            DB::commit();
            $result = array(
                "status" => 200,
                "result" => array(
                    "status" => $status,
                    "as" => '@epic',
                ),
            );
        } catch (\Exception $e) {
            $transMessage = "Simpan Gagal";
            DB::rollBack();
            $result = array(
                "status" => 400,
                "result"  => null
            );
        }
        return $this->respond($result['result'], $result['status'], $transMessage);
    }

    public function getRiwayatMutasiIGD(Request $r)
    {

        $data  = DB::table('pasiendaftar_t as pd')
            ->join('kelas_m as kls', 'kls.id', 'pd.objectkelasfk')
            ->join('kamar_m as kmr', 'kmr.objectkelasfk', 'kls.id')
            ->join('antrianpasiendiperiksa_t as apd', 'pd.norec', '=', 'apd.noregistrasifk')
            ->join('ruangan_m as ru', 'ru.id', '=', 'pd.objectruanganlastfk')
            ->join('ruangan_m as ruas', 'ruas.id', 'pd.objectruanganasalfk')
            ->join('tempattidur_m as tt', 'tt.id', 'apd.nobed')
            ->leftjoin('pegawai_m as pg', 'pd.objectpegawaifk', '=', 'pg.id')
            ->leftjoin('pegawai_m as pg2', 'pd.objectpegawairawatbersamafk', '=', 'pg2.id')
            ->leftjoin('pasien_m as ps', 'pd.nocmfk', '=', 'ps.id')
            ->leftjoin('aksesemr_t as emr', 'emr.pasienfk', '=', 'ps.id')
            ->leftjoin('alamat_m as alm', 'alm.nocmfk', '=', 'ps.id')
            ->leftjoin('kotakabupaten_m as kbp', 'alm.objectkotakabupatenfk', '=', 'kbp.id')
            ->leftjoin('kecamatan_m as km', 'alm.objectkecamatanfk', '=', 'km.id')
            ->leftjoin('desakelurahan_m as ds', 'alm.objectdesakelurahanfk', '=', 'ds.id')
            ->select(
                DB::raw("
                DISTINCT on (apd.noregistrasifk)
                ruas.id,
                ru.statusenabled,
                kls.namakelas,
                kmr.namakamar,
                pd.norec as norec_pd,
                pd.nocmfk,
                ps.nocm,
                ds.namadesakelurahan,
                km.namakecamatan,
                kbp.namakotakabupaten,
                ps.nobpjs,
                ps.alamatrmh,
                ps.noidentitas,
                pd.tglpulang,
                ru.objectdepartemenfk,
                emr.tglberakhir,
                emr.tglmulai,
                emr.objectkelompokuserfk,
                emr.pegawaipemohonfk,
                ruas.namaruangan,
                tt.reportdisplay,
                pd.objectruanganlastfk,
                pd.objectpegawaifk,
                pd.objectpegawairawatbersamafk,
                pd.noregistrasi,
                pg.namalengkap,
                pd.objectruanganasalfk,
                pg2.namalengkap as nama,
                ps.namapasien,
                apd.norec as norec_apd,
                CAST(pd.tglregistrasi AS DATE)
                ")
            )
            ->where('pd.kdprofile', $this->kdProfile)
            ->where('ru.objectdepartemenfk',  $this->settingFix('idDepRawatInap'))
            ->where('ruas.objectdepartemenfk', explode(',', $this->settingFix('idDepartemenIGD')))
            ->where('pd.statusenabled', true)
            ->where('apd.statusenabled', true)
            ->whereNull('apd.tglkeluar')
            ->where('apd.kdprofile', $this->kdProfile)
            ->whereNull('pd.tglpulang');

        if (isset($r['searchmutasi']) && $r['searchmutasi'] != '') {
            $searchTermMutasi = '%' . $r['searchmutasi'] . '%';
            $data = $data->where(function ($query) use ($searchTermMutasi) {
                $query->where('pd.noregistrasi', 'ilike', $searchTermMutasi)
                    ->orWhere('ps.nocm', 'ilike', $searchTermMutasi)
                    ->orWhere('ps.namapasien', 'ilike', $searchTermMutasi)
                    ->orWhere('ps.nobpjs', 'ilike', $searchTermMutasi)
                    ->orWhere('ps.noidentitas', 'ilike', $searchTermMutasi);
            });
        } elseif (isset($r['noregistrasi']) && $r['noregistrasi'] != '') {
            $data = $data->where('pd.noregistrasi', 'ilike', '%' . $r['noregistrasi'] . '%');
        } elseif (isset($r['nocm']) && $r['nocm'] != '') {
            $data = $data->where('ps.nocm', 'ilike', '%' . $r['nocm'] . '%');
        } elseif (isset($r['namapasien']) && $r['namapasien'] != '') {
            $data = $data->where('ps.namapasien', 'ilike', '%' . $r['namapasien'] . '%');
        }
        $total =$data->count();
        if (isset($r['limit']) && $r['limit'] != '') {
            $data= $data->limit($r['limit']);
        }
        if (isset($r['offset']) && $r['offset'] != ''){
            $data = $data->offset($r['offset']);
        }
        $data = $data->get();

        foreach ($data as $d) {
            $d->lamarawat =  $this->getAge($d->tglregistrasi, $d->tglpulang ? $d->tglpulang : date('Y-m-d H:i:s'));
        }
        $result = [
            'data' => $data,
            'total' => $total
        ];
        return $this->respond($result);
    }
}
