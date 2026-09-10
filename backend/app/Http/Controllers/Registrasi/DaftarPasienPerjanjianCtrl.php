<?php

namespace App\Http\Controllers\Registrasi;

use App\Http\Controllers\Controller;
use App\Models\Master\Agama;
use App\Models\Master\Alamat;
use App\Models\Master\DesaKelurahan;
use App\Models\Master\GolonganDarah;
use App\Models\Master\JenisKelamin;
use App\Models\Master\Kebangsaan;
use App\Models\Master\Kecamatan;
use App\Models\Master\KotaKabupaten;
use App\Models\Master\Negara;
use App\Models\Master\Pasien;
use App\Models\Master\Pekerjaan;
use App\Models\Master\Pendidikan;
use App\Models\Master\Provinsi;
use App\Models\Master\RunningNumber;
use App\Models\Master\StatusPerkawinan;
use App\Models\Master\Suku;
use App\Models\Transaksi\AntrianPasienRegistrasi;
use App\Traits\Valet;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DaftarPasienPerjanjianCtrl extends Controller
{
    use Valet;
    public function __construct()
    {
        parent::__construct($is_encrypt = true);
    }

    public function index(Request $request)
    {
        $filter = $request;
        $limit = $request->limit;
        $offset = $request->offset;
        $datas = DB::table('antrianpasienregistrasi_t as apr')
            ->leftJoin('pasienperjanjian_t as pp', 'pp.norec', '=', 'apr.perjanjianfk')
            ->leftJoin('suratketerangan_t as sk', 'sk.norec', '=', 'pp.objectsuratfk')
            ->leftJoin('pasien_m as pm', 'pm.id', '=', 'apr.nocmfk')
            ->leftJoin('ruangan_m as ru', 'ru.id', '=', 'apr.objectruanganfk')
            ->leftJoin('pegawai_m as pg', 'pg.id', '=', 'pp.objectdokterfk')
            ->leftJoin('pegawai_m as pg2', 'pg2.id', '=', 'apr.objectpegawaifk')
            ->leftJoin('kelompokpasien_m as kps', 'kps.id', '=', 'apr.objectkelompokpasienfk')
            ->leftJoin('pasiendaftar_t as pd', 'pd.antrianpasienregistrasifk', '=', 'apr.norec')
            ->leftJoin('antrianpasiendiperiksa_t as apd', function ($join) {
                $join->on('apd.noregistrasifk', '=', 'apd.norec')
                    ->on('pd.objectruanganlastfk', '=', 'apd.objectruanganfk');
            })
            ->selectRaw('apr.norec, pm.nocm, apr.noreservasi, apr.objectruanganfk, apr.objectpegawaifk, ru.namaruangan, apr.isconfirm,
             (CASE WHEN pg.namalengkap IS NULL THEN pg2.namalengkap ELSE pg.namalengkap END) AS dokter, apr.notelepon,
             pm.namapasien, apr.namapasien, apr.objectkelompokpasienfk, kps.kelompokpasien, apr.tglinput, apr.nocmfk,
             (CASE WHEN pm.namapasien IS NULL THEN apr.namapasien ELSE pm.namapasien END) AS namapasien,
             (CASE WHEN apr.ismobilejkn = true AND pd.ischeckin = true THEN  \'Confirm\'
                   WHEN apr.ismobilejkn = true AND (pd.ischeckin = false OR pd.ischeckin IS NULL) THEN \'Reservasi\'
                   WHEN apr.isconfirm = TRUE THEN \'Confirm\' ELSE \'Reservasi\' END) AS status,
             apr.ismobilejkn, apr.noantrian, apr.jenis, apr.caradaftar, apr.perjanjianfk, sk.indikasi, apr.tanggalreservasi,
             (CASE WHEN pg.kddokterbpjs IS NULL THEN pg2.kddokterbpjs ELSE pg2.kddokterbpjs END) AS kddokterbpjs,
             ru.noruangan AS kdruangbpjs, apr.objectkelompokpasienfk, pd.norec AS norec_pd, apd.norec AS norec_apd,apr.noidentitas')
            ->where('apr.noreservasi', '<>', '-')
            ->where('apr.statusenabled', true)
            ->where('apr.kdprofile', $this->kdProfile)
            ->whereNotNull('apr.noreservasi')
            ->when(isset($filter['ruanganfk']) && $filter['ruanganfk'] != "" && $filter['ruanganfk'] != "undefined", function ($query) use ($filter) {
                return $query->where('ru.id', $filter['ruanganfk']);
            })
            ->when(isset($filter['kdReservasi']) && $filter['kdReservasi'] != "" && $filter['kdReservasi'] != "undefined", function ($query) use ($filter) {
                return $query->where('apr.noreservasi', 'ilike', '%' . $filter['kdReservasi'] . '%');
            })
            ->when(isset($filter['statusRev']) && $filter['statusRev'] == "Confirm", function ($query) {
                return $query->where('apr.isconfirm', true);
            })
            ->when(isset($filter['statusRev']) && $filter['statusRev'] == "Reservasi", function ($query) {
                return $query->where('apr.isconfirm', null);
            })
            ->when(isset($filter['namapasienpm']) && $filter['namapasienpm'] != "" && $filter['namapasienpm'] != "undefined", function ($query) use ($filter) {
                return $query->where('pm.namapasien', 'ilike', '%' . $filter['namapasienpm'] . '%');
            })
            ->when(isset($filter['caradaftar']) && $filter['caradaftar'] != "" && $filter['caradaftar'] != "undefined", function ($query) use ($filter) {
                if ($filter['caradaftar'] == 'Mobile-JKN') {
                    return $query->where('apr.ismobilejkn', true);
                }
                if ($filter['caradaftar'] == 'Kontrol') {
                    return $query->whereNotNull('apr.perjanjianfk');
                }
            })
            ->orderBy('apr.tanggalreservasi', 'DESC');
            if (isset($filter->tglAwal) && $filter->tglAwal != '') {
                $datas = $datas->where(DB::raw("apr.tanggalreservasi::date"), '>=', $filter->tglAwal);
            }
            if (isset($filter->tglAkhir) && $filter->tglAkhir != '') {
                $datas = $datas->where(DB::raw("apr.tanggalreservasi::date"), '<=', $filter->tglAkhir);
            }
            $total = $datas->count();

            if (isset($request['limit']) && $request['limit'] != '') {
                $data = $datas->limit($request['limit']);
            }
            if (isset($request['offset']) && $request['offset'] != '') {
                $data = $datas->offset($request['offset']);
            }
            $data = $datas->get();
            foreach ($data as $d) {
                $d->nomorantrean  = null;
                // $d->caradaftar = '';
                if ($d->caradaftar  == null) {
                    if ($d->ismobilejkn == true) {
                        $d->caradaftar = 'Mobile-JKN';
                    } else if ($d->perjanjianfk  != null) {
                        $d->caradaftar = 'Kontrol';
                    } else {
                        $d->caradaftar  = 'MyParamarta';
                    }
                }
                if ($d->ismobilejkn == true) {

                    $nomorAntrian = strlen((string)$d->noantrian);
                    if ($nomorAntrian == 1) {
                        $nomorAntrian = '0' . $d->noantrian;
                    } else {
                        $nomorAntrian = $d->noantrian;
                    }
                    $d->nomorantrean  = $d->jenis . '-' . $nomorAntrian;
                }
            }
            $result = array(
                'data' => $data,
                'total' =>$total,
                // 'datariwayat' => $dataRiwayat,
                'message' => 'cepot',
            );
            return $this->respond($result);
    }

    public function updateTglReservasi(Request $request)
    {

        DB::beginTransaction();
        try {
            AntrianPasienRegistrasi::where('norec', $request['norec'])->where('kdprofile', $this->kdProfile)->update(['tanggalreservasi' => $request['tanggalreservasi']]);
            DB::commit();
            $response = [
                "status" => 200,
                "message" => 'Update tanggal reservasi berhasil',
                "data" => 'berhasil'
            ];
        } catch (Exception $e) {
            DB::rollBack();
            $response = [
                "status" => 400,
                "message" => 'Update tanggal reservasi gagal',
                "data" => $e->getMessage()
            ];
        }

        return $this->respond($response['data'], $response['status'], $response['message']);
    }

    public function deleteReservasi(Request $request)
    {
        DB::beginTransaction();
        try {
            AntrianPasienRegistrasi::where('norec', $request['norec'])->update(['statusenabled' => false,]);
            DB::commit();
            $response = [
                "status" => 200,
                "message" => "Data Reservasi Berhasil dihapus",
                "data" => $request['norec'],
            ];
        } catch (Exception $e) {
            DB::rollBack();
            $response = [
                "status" => 400,
                "message" => "Hapus Data Reservasi Gagal",
                "data" => $e->getMessage(),
            ];
        }

        return $this->respond($response['data'], $response['status'], $response['message']);
    }
}
