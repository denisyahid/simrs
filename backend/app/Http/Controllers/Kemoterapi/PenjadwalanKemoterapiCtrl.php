<?php

namespace App\Http\Controllers\Kemoterapi;

use App\Traits\Valet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

use App\Models\Master\Ruangan;
use App\Models\Transaksi\PasienDaftar;
use App\Models\Transaksi\Penjadwalan;

class PenjadwalanKemoterapiCtrl extends Controller
{
    use Valet;
    public function __construct()
    {
        parent::__construct($is_encrypt = true);
    }

    public function listDropdown(Request $r)
    {
        $res['ruangan'] = Ruangan::mine()
            ->where('namaruangan', 'ilike', '%KEMOTERAPI%')
            ->get();

        return $this->respond($res);
    }

    public function savePenjadwalan(Request $r)
    {
        DB::beginTransaction();
        try {
            $idProfile = (int) $this->kdProfile;
            date_default_timezone_set('Asia/Jakarta');

            // Save data
            $dataPJ = new Penjadwalan;
            $dataPJ->norec = $dataPJ->generateNewId();
            $dataPJ->kdprofile = $idProfile;
            $dataPJ->statusenabled = true;
            $dataPJ->nocmfk = $r['nocmfk'];
            $dataPJ->norec_pd = $r['norec_pd'];
            $dataPJ->norec_apd = $r['norec_apd'];
            $dataPJ->ruanganfk = $r['objectruanganfk'];
            $dataPJ->ruangantujuanfk = $r['objectruangantujuanfk'];
            $dataPJ->pegawaiorderfk = $r['pegawaiorderfk'];
            $dataPJ->pegawaifk = $r['pegawaifk'];
            $dataPJ->jenis = isset($r['jenis']) ? $r['jenis'] : null;
            $dataPJ->keterangan = isset($r['keterangan']) ? $r['keterangan'] : null;
            $dataPJ->tglorder = isset($r['tanggal']) ? $r['tanggal'] : null;
            $dataPJ->tglpenjadwalan = isset($r['tglpenjadwalan']) ? $r['tglpenjadwalan'] : null;
            $dataPJ->diagnosa = isset($r['diagnosa']) ? $r['diagnosa'] : null;
            $dataPJ->regimen_kemoterapi = isset($r['regimen']) ? $r['regimen'] : null;
            $dataPJ->statusorder = 0;
            $dataPJ->save();

            // Data pasien
            $ruangan = Ruangan::where('id', $r['objectruangantujuanfk'])->first();
            $dataPasien = DB::table('pasiendaftar_t as pd')
                ->join('ruangan_m as ruas', 'ruas.id', 'pd.objectruanganasalfk')
                ->join('pasien_m as ps', 'ps.id', 'pd.nocmfk')
                ->select('ps.namapasien', 'ruas.namaruangan as ruanganasal', 'pd.noregistrasi', 'ps.nocm')
                ->where('pd.kdprofile', $idProfile)
                ->where('pd.statusenabled', true)
                ->where('pd.norec', $r['norec_pd'])
                ->first();

            $this->LOGGING(
                'Penjadwalan Kemoterapi',
                $dataPJ->norec,
                'penjadwalan_t',
                'Order Penjadwalan Kemoterapi atas nama ' . $dataPasien->namapasien . ' (' . $dataPasien->nocm . ') ' .
                ' dari ' . $dataPasien->ruanganasal . ' ke ' . $ruangan->namaruangan . ' - ' . $dataPasien->noregistrasi
            );

            $transStatus = 'true';
        } catch (\Exception $e) {
            $transStatus = 'false';
        }

        if ($transStatus == 'true') {
            $transMessage = "Sukses";
            DB::commit();
            $result = array(
                "status" => 200,
                "result" => array(
                    "data" => $dataPJ,
                    "as" => '@epic',
                ),
            );
        } else {
            $transMessage = "Simpan Gagal";
            DB::rollBack();
            $result = array(
                "status" => 400,
                "result" => $e->getMessage() . ' ' . $e->getLine()
            );
        }

        return $this->respond($result['result'], $result['status'], $transMessage);
    }

    public function verifikasiPenjadwalan(Request $r)
    {
        DB::beginTransaction();
        try {
            $r = $r['parameter'];
            $idProfile = (int) $this->kdProfile;
            date_default_timezone_set('Asia/Jakarta');

            // Save data
            $dataPJ = Penjadwalan::where('norec', $r['norec_pj'])->first();
            $dataPJ->kdprofile = $idProfile;
            $dataPJ->statusenabled = true;
            $dataPJ->nocmfk = $r['nocmfk'];
            $dataPJ->norec_pd = $r['norec_pd'];
            $dataPJ->norec_apd = $r['norec_apd'];
            $dataPJ->ruanganfk = $r['objectruanganfk'];
            $dataPJ->ruangantujuanfk = $r['objectruangantujuanfk'];
            $dataPJ->pegawaiorderfk = $r['pegawaiorderfk'];
            $dataPJ->pegawaifk = $r['pegawaifk'];
            $dataPJ->jenis = isset($r['jenis']) ? $r['jenis'] : null;
            $dataPJ->keterangan = isset($r['keterangan']) ? $r['keterangan'] : null;
            $dataPJ->tglorder = isset($r['tanggal']) ? $r['tanggal'] : null;
            $dataPJ->tglpenjadwalan = $r['tglpenjadwalan'];
            $dataPJ->diagnosa = isset($r['diagnosa']) ? $r['diagnosa'] : null;
            $dataPJ->regimen_kemoterapi = isset($r['regimen']) ? $r['regimen'] : null;
            if (isset($r['verif_kemoterapi'])) {
                $dataPJ->verif_kemoterapi = $r['verif_kemoterapi'];
            }
            if (isset($r['verif_farmasi'])) {
                $dataPJ->verif_farmasi = $r['verif_farmasi'];
            }
            if (isset($r['verif_hematologi'])) {
                $dataPJ->verif_hematologi = $r['verif_hematologi'];
            }
            $dataPJ->save();

            // Data pasien
            $dataPasien = DB::table('pasiendaftar_t as pd')
                ->join('ruangan_m as ruas', 'ruas.id', 'pd.objectruanganasalfk')
                ->join('pasien_m as ps', 'ps.id', 'pd.nocmfk')
                ->select('ps.namapasien', 'ruas.namaruangan as ruanganasal', 'pd.noregistrasi', 'ps.nocm')
                ->where('pd.kdprofile', $idProfile)
                ->where('pd.statusenabled', true)
                ->where('pd.norec', $r['norec_pd'])
                ->first();

            $this->LOGGING(
                'Verifikasi Penjadwalan Kemoterapi',
                $dataPJ->norec,
                'penjadwalan_t',
                'Verifikasi Penjadwalan Kemoterapi oleh ' . $r['kelompokUser'] . ' pada pasien ' . $dataPasien->namapasien . ' (' . $dataPasien->nocm . ') ' . ' - ' . $dataPasien->noregistrasi
            );

            $transStatus = 'true';
        } catch (\Exception $e) {
            $transStatus = 'false';
        }

        if ($transStatus == 'true') {
            $transMessage = "Sukses";
            DB::commit();
            $result = array(
                "status" => 200,
                "result" => array(
                    "data" => $dataPJ,
                    "as" => '@epic',
                ),
            );
        } else {
            $transMessage = "Simpan Gagal";
            DB::rollBack();
            $result = array(
                "status" => 400,
                "result" => $e->getMessage() . ' ' . $e->getLine()
            );
        }

        return $this->respond($result['result'], $result['status'], $transMessage);
    }

    public function getPenjadwalan(Request $r)
    {
        $data = DB::table('penjadwalan_t as pj')
            ->join('pasiendaftar_t as pd', 'pd.norec', 'pj.norec_pd')
            ->join('pasien_m as ps', 'pd.nocmfk', 'ps.id')
            ->join('jeniskelamin_m as jk', 'jk.id', 'ps.objectjeniskelaminfk')
            ->join('pegawai_m as peg', 'peg.id', 'pj.pegawaiorderfk')
            ->join('kelompokpasien_m as kp', 'kp.id', 'pd.objectkelompokpasienlastfk')
            ->join('kelas_m as kls', 'kls.id', 'pd.objectkelasfk')
            ->join('ruangan_m as ruAs', 'pj.ruanganfk', 'ruAs.id')
            ->join('ruangan_m as ruTu', 'pj.ruangantujuanfk', 'ruTu.id')
            ->select(
                'ps.namapasien',
                'ps.id as nocmfk',
                'ps.tgllahir',
                'ps.nocm',
                'ps.noidentitas',
                'ps.nobpjs',
                'pd.noregistrasi',
                'pd.norec as norec_pd',
                'pd.tglregistrasi',
                'pj.norec as norec_pj',
                'pj.norec_apd as norec_apd',
                'pj.tglorder',
                'pj.tglpenjadwalan',
                'pj.statusorder',
                'peg.namalengkap',
                'kp.kelompokpasien',
                'kls.namakelas',
                'pj.verif_kemoterapi',
                'pj.verif_farmasi',
                'pj.verif_hematologi',
                'ruAs.namaruangan as asalruangan',
                'ruTu.namaruangan as ruangantujuan',
            );

        // Validasi
        if (isset($r['dari']) && $r['dari'] != '' && isset($r['sampai']) && $r['sampai'] != '') {
            if (isset($r['opsi']) && $r['opsi'] == 'tglorder') {
                $data = $data->whereBetween('pj.tglorder', [$r['dari']. ' 00:00', $r['sampai']. ' 23:59']);
            }
            if (isset($r['opsi']) && $r['opsi'] == 'tglpenjadwalan') {
                $data = $data->whereBetween('pj.tglpenjadwalan', [$r['dari']. ' 00:00', $r['sampai']. ' 23:59']);
            }
        }

        if (isset($r['ruanganid']) && $r['ruanganid'] != '') {
            $data = $data->where('ruTu.id', $r['ruanganid']);
        }

        if (isset($r['statusorder']) && $r['statusorder'] != '') {
            switch ($r['statusorder']) {
                case 'hematologi':
                    // $data = $data->whereNull('pj.verif_hematologi')->orWhere('pj.verif_hematologi', false);
                    break;
                case 'kemoterapi':
                    $data = $data->where(function ($query) {
                        $query->whereNotNull('pj.verif_hematologi')
                            ->orWhere('pj.verif_hematologi', '!=', false);
                    });
                    break;
                case 'farmasi':
                    // Pasien yang SUDAH diverifikasi kemoterapi, TAPI BELUM diverifikasi farmasi
                    $data = $data->where('pj.verif_kemoterapi', true);
                    $data = $data->where(function ($query) {
                        $query->whereNull('pj.verif_farmasi')->orWhere('pj.verif_farmasi', false);
                    });
                    break;
                // case 'farmasi':
                //     $data = $data->where('pj.verif_kemoterapi', '!=', null)
                //             ->orWhere('pj.verif_kemoterapi', '!=', false);
                //     $data = $data->whereNull('pj.verif_farmasi')->orWhere('pj.verif_farmasi', false);
                //     break;
                case 'terverifikasi':
                    $data = $data->where('pj.verif_kemoterapi', true)
                                ->where('pj.verif_farmasi', true)
                                ->where('pj.verif_hematologi', true);
                    break;
                default:
                    $data = $data->where(function ($query) {
                        $query->whereNull('pj.verif_kemoterapi')->orWhere('pj.verif_kemoterapi', false);
                    })->where(function ($query) {
                        $query->whereNull('pj.verif_farmasi')->orWhere('pj.verif_farmasi', false);
                    })->where(function ($query) {
                        $query->whereNull('pj.verif_hematologi')->orWhere('pj.verif_hematologi', false);
                    });
                    break;
            }
        }

        $data = $data->orderBy('pj.tglorder', 'desc');
        $data = $data->get();
        // $tosql = $data->toSql();
        // $binding = $data->getBindings();
        // return [$tosql, $binding];

        return $this->respond($data);
    }

    public function getDetailPenjadwalan(Request $r)
    {
        $data = DB::table('penjadwalan_t as pj')
            ->join('pasiendaftar_t as pd', 'pd.norec', 'pj.norec_pd')
            ->join('pasien_m as ps', 'pd.nocmfk', 'ps.id')
            ->join('jeniskelamin_m as jk', 'jk.id', 'ps.objectjeniskelaminfk')
            ->join('pegawai_m as peg', 'peg.id', 'pj.pegawaiorderfk')
            ->join('pegawai_m as peg2', 'peg2.id', 'pj.pegawaifk')
            ->join('kelompokpasien_m as kp', 'kp.id', 'pd.objectkelompokpasienlastfk')
            ->join('kelas_m as kls', 'kls.id', 'pd.objectkelasfk')
            ->join('ruangan_m as ruAs', 'pj.ruanganfk', 'ruAs.id')
            ->join('ruangan_m as ruTu', 'pj.ruangantujuanfk', 'ruTu.id')
            ->where('pj.norec', $r['norec_pj'])
            ->select(
                'ps.id as nocmfk',
                'ps.nohp',
                'ps.namapasien',
                'ps.tgllahir',
                'ps.nocm',
                'ps.noidentitas',
                'ps.nobpjs',
                'jk.jeniskelamin',
                'pd.noregistrasi',
                'pd.norec as norec_pd',
                'pd.tglregistrasi',
                'pj.norec as norec_pj',
                'pj.norec_apd as norec_apd',
                'pj.tglorder',
                'pj.tglpenjadwalan',
                'pj.keterangan',
                'pj.statusorder',
                'pj.regimen_kemoterapi',
                'pj.diagnosa',
                'peg.id as id_pegawaiorderfk',
                'peg.namalengkap',
                'peg2.id as id_pegawaifk',
                'peg2.namalengkap as namalengkap2',
                'kp.kelompokpasien',
                'kls.namakelas',
                'ruAs.id as id_asalruangan',
                'ruAs.namaruangan as asalruangan',
                'ruAs.objectdepartemenfk as dep_asalruangan',
                'ruTu.id as id_ruangantujuan',
                'ruTu.namaruangan as ruangantujuan',
                'ruTu.objectdepartemenfk as dep_ruangantujuan'
            )->first();

        return $this->respond($data);
    }
}
