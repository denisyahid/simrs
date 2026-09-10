<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Master\Departemen;
use App\Models\Master\JadwalDokter;
use App\Models\Master\JenisDiet;
use App\Models\Master\JenisWaktu;
use App\Models\Master\Kamar;
use App\Models\Master\KategoryDiet;
use App\Models\Master\Kelas;
use App\Models\Master\KelompokPasien;
use App\Models\Master\Produk;
use App\Models\Master\Ruangan;
use App\Models\Standar\MapLoginUserToRuangan;
use App\Models\Transaksi\AntrianPasienDiperiksa;
use App\Models\Transaksi\KirimProduk;
use App\Models\Transaksi\OrderPelayanan;
use App\Models\Transaksi\PasienDaftar;
use App\Models\Transaksi\PelayananPasien;
use App\Models\Transaksi\PelayananPasienDetail;
use App\Models\Transaksi\StokProdukDetail;
use App\Models\Transaksi\StrukKirim;
use App\Models\Transaksi\StrukOrder;
use App\Traits\Valet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardGiziCtrl extends Controller
{
    use Valet;
    public function __construct()
    {
        parent::__construct($is_encrypt = true);
    }
    public function headerPasienGizi(Request $r)
    {
        $data = DB::table('pasiendaftar_t as pd')
            ->JOIN('ruangan_m as ru', 'ru.id', '=', 'pd.objectruanganlastfk')
            ->JOIN('departemen_m as dp', 'dp.id', '=', 'ru.objectdepartemenfk')
            ->JOIN('kelompokpasien_m as kp', 'kp.id', '=', 'pd.objectkelompokpasienlastfk')
            ->JOIN('pasien_m as ps', 'ps.id', '=', 'pd.nocmfk')
            ->JOIN('alamat_m as alm', 'alm.nocmfk', '=', 'ps.id')
            ->JOIN('jeniskelamin_m as jk', 'jk.id', '=', 'ps.objectjeniskelaminfk')
            ->select(
                'ps.nocm',
                'ps.id as nocmfk',
                'ps.namapasien',
                'ps.tgllahir',
                'ps.tempatlahir',
                'ps.objectjeniskelaminfk',
                'jk.jeniskelamin',
                'ps.objectagamafk',
                'ps.noidentitas',
                'ps.nobpjs',
                'ps.noasuransilain',
                'alm.alamatlengkap',
                'alm.kodepos',
                'ps.notelepon',
                'ps.nohp',
                'ps.namaayah',
                'ps.namaibu',
                'ps.email',
                'kp.kelompokpasien'
            )
            ->where('ps.kdprofile', (int) $this->kdProfile)
            ->where('ps.statusenabled', true)
            ->where('ps.id', $r['nocmfk'])
            ->first();
        if (!empty($data)) {
            $data->umur = $this->getAge($data->tgllahir, date('Y-m-d H:i:s'));
        }
        $registrasi = DB::table('pasiendaftar_t as pd')
            ->JOIN('antrianpasiendiperiksa_t as apd', 'apd.noregistrasifk', '=', 'pd.norec')
            ->JOIN('ruangan_m as ru', 'ru.id', '=', 'apd.objectruanganfk')
            ->JOIN('departemen_m as dp', 'dp.id', '=', 'ru.objectdepartemenfk')
            ->JOIN('kelompokpasien_m as kp', 'kp.id', '=', 'pd.objectkelompokpasienlastfk')
            ->LEFTJOIN('kelas_m as kl', 'kl.id', '=', 'apd.objectkelasfk')
            ->select(
                'pd.noregistrasi',
                'pd.norec as norec_pd',
                'pd.tglregistrasi',
                'pd.tglpulang',
                'dp.namadepartemen',
                'kp.kelompokpasien',
                'apd.norec as norec_apd',
                'pd.objectruanganlastfk',
                'apd.objectruanganfk',
                'ru.namaruangan',
                'apd.objectkelasfk',
                'apd.kelasrawatfk',
                'kl.namakelas',
                'pd.nocmfk',
                'apd.tglmasuk',
                'apd.tglkeluar',
                'pd.objectkelompokpasienlastfk',
                'pd.objectrekananfk',
                'pd.jenispelayanan as jenispelayananfk'
            )
            ->where('pd.kdprofile', (int) $this->kdProfile)
            ->where('pd.statusenabled', true)
            ->where('pd.statusenabled', true)
            ->where('pd.nocmfk', $r['nocmfk'])
            ->where('pd.norec', $r['norec_pd'])
            ->get();
        $last = array();
        $tgl = date('2000-01-01 00:00');
        foreach ($registrasi as $d) {
            if ($d->objectruanganlastfk == $d->objectruanganfk && $tgl < $d->tglmasuk) {
                $tgl = $d->tglmasuk;
                $last = $d;
            }
        }
        $result['pasien'] = $data;
        $result['registrasi'] = $registrasi;
        $result['last_registrasi'] = $last;
        $result['as'] = '@epic';

        return $this->respond($result);
    }
    public function listOrderGizi(Request $r)
    {
        // $dataProduk = DB::table('produk_m as pr')
        //     ->join('detailjenisproduk_m as djp', 'djp.id', '=', 'pr.objectdetailjenisprodukfk')
        //     ->join('jenisproduk_m as jp', 'jp.id', '=', 'djp.objectjenisprodukfk')
        //     ->join('kelompokproduk_m as kp', 'kp.id', '=', 'jp.objectkelompokprodukfk')
        //     ->leftjoin('satuanstandar_m as ss', 'ss.id', '=', 'pr.objectsatuanstandarfk')
        //     ->select('pr.id', 'pr.namaproduk', 'ss.id as ssid', 'ss.satuanstandar')
        //     ->where('pr.statusenabled', true)
        //     ->whereIN('kp.id', explode(',', $this->settingFix('kdKelasNonKelasRegistrasi'), $this->kdProfile))
        //     ->groupBy('pr.id', 'pr.namaproduk', 'ss.id', 'ss.satuanstandar')
        //     ->orderBy('pr.namaproduk')
        //     ->get();
        // $res['produk'] = $dataProduk;

        if (isset($r['filter'])) {
            $res['jenisdiet'] = JenisDiet::mine();

            // Filter
            if (isset($r['kategorydiet']) && $r['kategorydiet'] != '') {
                $res['jenisdiet'] = $res['jenisdiet']->where('kategorydietfk', $r['kategorydiet'])->get();
            }
        } else {
            $set = explode(',', $this->settingFix('idPelayananGizi'));
            $res['jeniswaktu'] = JenisWaktu::mine()->get();
            $res['jenisdiet'] = JenisDiet::mine()->get();
            $res['kategorydiet'] = KategoryDiet::mine()->get();
            $res['kelashak'] = Kelas::mine()->get();
            $res['kelompokpasien'] = KelompokPasien::mine()->get();
            $res['ruangan'] = Ruangan::mine()->where('objectdepartemenfk', $this->settingFix('idDepRawatInap'))->get();
            // Add ruangan IGD
            $addruanganigd = Ruangan::mine()->where('objectdepartemenfk', $this->settingFix('idDepartemenIGD'))->get();
            $res['ruangan'] = $res['ruangan']->merge($addruanganigd);
        }

        return $this->respond($res);
    }

    public function getPasienInap(Request $r)
    {
        $data = DB::table('pasiendaftar_t as pd')
            ->join('ruangan_m as ru', 'pd.objectruanganlastfk', '=', 'ru.id')
            ->leftjoin('pegawai_m as pg', 'pd.objectpegawaifk', '=', 'pg.id')
            ->join('pasien_m as ps', 'pd.nocmfk', '=', 'ps.id')
            ->join('kebangsaan_m as kbs', 'kbs.id', '=', 'ps.objectkebangsaanfk')
            ->join('antrianpasiendiperiksa_t as apd', 'pd.norec', '=', 'apd.noregistrasifk')
            ->leftjoin('tempattidur_m as tt', 'tt.id', '=', 'apd.nobed')
            ->leftjoin('kamar_m as km', 'km.id', '=', 'apd.objectkamarfk')
            ->leftjoin('jeniskelamin_m as jk', 'ps.objectjeniskelaminfk', '=', 'jk.id')
            ->select(
                DB::raw("
                    tt.reportdisplay as nobed,
                    ru.id,
                    apd.norec as norec_apd,
                    apd.objectkelasfk,
                    apd.kelasrawatfk,
                    ru.statusenabled,
                    ru.namaruangan,
                    pd.norec as norec_pd,
                    pd.objectruanganasalfk,
                    pd.nocmfk,
                    pd.tglpulang,
                    ru.objectdepartemenfk,
                    jk.jeniskelamin,
                    pd.objectruanganlastfk,
                    pd.objectpegawaifk,
                    pd.noregistrasi,
                    pg.namalengkap,
                    ps.namapasien,
                    ps.nocm,
                    kbs.name as kebangsaan,
                    apd.norec as norec_apd,
                    apd.isordergizi as isordergizi,
                    km.namakamar as kamar,
                    CAST(pd.tglregistrasi AS DATE)
                ")

            )
            ->where('pd.kdprofile', $this->kdProfile)
            ->where('ru.objectdepartemenfk', $this->settingFix('idDepRawatInap'))
            ->where('pd.statusenabled', true)
            ->where('apd.statusenabled', true)
            // ->where('apd.isordergizi', null)
            ->whereNull('pd.tglpulang')
            ->whereNull('apd.tglkeluar');

        if (isset($r['ruanganid']) && $r['ruanganid'] != '' && $r['ruanganid'] != 'null') {
            $data = $data->where('ru.id', '=', $r['ruanganid']);
        }
        if (isset($r['noregistrasi']) && $r['noregistrasi'] != '') {
            $data = $data->where('pd.noregistrasi', '=', $r['noregistrasi']);
        }
        if (isset($r['nocm']) && $r['nocm'] != '') {
            $data = $data->where('pd.nocm', '=', $r['nocm']);
        }
        if (isset($r['namapasien']) && $r['namapasien'] != '') {
            $data = $data->where('ps.namapasien', 'ilike', '%' . $r['namapasien'] . '%');
        }
        $total = $data->count();
        if (isset($r['limit']) && $r['limit'] != '') {
            $data = $data->limit($r['limit']);
        }
        if (isset($r['offset']) && $r['offset'] != '') {
            $data = $data->offset($r['offset']);
        }

        $data = $data->orderBy('apd.isordergizi', 'desc');
        $data = $data->get();

        // $res['produk'] = $produk;
        $res['data'] = $data;
        $res['total'] = $total;
        return $this->respond($res);
    }

    public function getHistoriOrder(Request $r)
    {
        $data = DB::table('strukorder_t as so')
            ->join('orderpelayanan_t as op', 'op.strukorderfk', '=', 'so.norec')
            ->join('kategorydiet_m as kd', 'kd.id', '=', 'op.objectkategorydietfk')
            ->join('jenisdiet_m as jd', 'jd.id', '=', 'op.jenisdietfk')
            ->join('ruangan_m as ru', 'ru.id', '=', 'so.objectruanganfk')
            ->where('ru.objectdepartementfk', $this->settingFix('idDepRawatInap'))
            ->where('so.statusenabled', true)
            ->where('op.statusenabled', true)
            ->where('so.keteranganorder', 'Order Gizi')
            ->where('so.noregistrasifk', '=', $r['norec_pd'])
            ->select('so.tglorder', 'kd.kategorydiet', 'jd.jenisdiet', 'kd.id as idkd', 'jd.id as idjd');

        $data = $data->get();
        return $this->respond($data);
    }

    public function riwayatOrderGizi(Request $r)
    {
        $depRI = $this->settingFix('idDepRawatInap');
        $depIGD = $this->settingFix('idDepartemenIGD');

        // Saat ini riwayatnya masih mengambil ruangan terakhir pasien
        $data = DB::table('orderpelayanan_t as op')
            ->join('ruangan_m as r', 'r.id', '=', 'op.objectruanganfk')
            ->join('pasiendaftar_t as pd', 'pd.norec', '=', 'op.noregistrasifk')
            ->join('pasien_m as ps', 'ps.id', '=', 'op.nocmfk')
            ->join('kategorydiet_m as kd', 'kd.id', '=', 'op.objectkategorydietfk')
            ->join('jeniswaktu_m as jw', 'jw.id', '=', 'op.objectjeniswaktufk')
            ->join('strukorder_t as so', 'op.strukorderfk', '=', 'so.norec')
            ->leftjoin('jenisdiet_m as jd', 'jd.id', '=', 'op.jenisdietfk')
            // Untuk menghindari double order
            ->join('antrianpasiendiperiksa_t as apd', function ($join) {
                $join->on('apd.noregistrasifk', '=', 'pd.norec')
                    ->on('apd.objectruanganfk', '=', 'op.objectruanganfk');
            })
            ->leftjoin('tempattidur_m as tt', 'tt.id', '=', 'apd.nobed')
            ->leftjoin('kamar_m as km', 'km.id', '=', 'apd.objectkamarfk')
            ->join('kelas_m as kls', 'kls.id', 'apd.objectkelasfk')
            ->leftjoin('kelas_m as klsr', 'klsr.id', 'apd.kelasrawatfk')
            ->join('kelompokpasien_m as kp', 'kp.id', 'pd.objectkelompokpasienlastfk')
            ->select(
                'tt.reportdisplay as nobed',
                'pd.norec as norec_pd',
                'apd.norec as norec_apd',
                // 'apd.tglkeluar',
                'pd.nocmfk',
                'pd.objectruanganlastfk',
                'pd.isRencanaMutasi',
                'pd.noregistrasi',
                'ps.namapasien',
                'so.tglorder',
                'apd.objectkelasfk',
                'apd.kelasrawatfk',
                'so.noorder',
                'ps.nocm',
                'r.namaruangan',
                'r.objectdepartemenfk',
                'kd.kategorydiet',
                'jw.jeniswaktu',
                'jd.jenisdiet',
                'jw.id as idjw',
                'kd.id as idkd',
                'jd.id as idjd',
                // 'op.jenisdietexternal',
                'op.takaran',
                'op.keteranganlainnya',
                'op.norec as norec_op',
                'op.isverifikasi',
                'so.norec as norec_so',
                'km.namakamar as kamar',
                'kls.namakelas as namakelas',
                'klsr.namakelas as namakelasrawat',
                'kp.kelompokpasien',
            )
            // ->whereNull('apd.tglkeluar')
            // ->whereNull('pd.tglpulang')
            ->where('apd.statusenabled', true)
            ->where('so.statusenabled', true)
            ->where('op.statusenabled', true)
            ->whereIn('r.objectdepartemenfk', [$depRI, $depIGD]);

        if (isset($r['norec_pd'])) {
            $data = $data->where('pd.norec', $r['norec_pd']);
        }
        if (isset($r['nocm']) && $r['nocm'] != '') {
            $data = $data->where('pd.nocmfk', $r['nocm']);
        }
        if (isset($r['jenisWaktu']) && $r['jenisWaktu'] != '' && $r['jenisWaktu'] != 'null') {
            $data = $data->where('jw.id', $r['jenisWaktu']);
        }
        if (isset($r['nocm'])) {
            $data = $data->where('pd.nocmfk', $r['nocm']);
        }
        if (isset($r['ruanganid']) && $r['ruanganid'] != '' && $r['ruanganid'] != 'null') {
            $data = $data->where('r.id', '=', $r['ruanganid']);
        }
        if (isset($r['dari']) && $r['dari'] != '') {
            $data = $data->where(DB::raw("so.tglorder::date"), '>=', $r->dari);
        }
        if (isset($r['sampai']) && $r['sampai'] != '') {
            $data = $data->where(DB::raw("so.tglorder::date"), '<=', $r->sampai);
        }
        if (isset($r['noregistrasi']) && $r['noregistrasi'] != '') {
            $data = $data->where('pd.noregistrasi', '=', $r['noregistrasi']);
        }
        if (isset($r['kelashak']) && $r['kelashak'] != '') {
            $data = $data->where('apd.objectkelasfk', '=', $r['kelashak']);
        }
        if (isset($r['kelasrawat']) && $r['kelasrawat'] != '') {
            $data = $data->where('apd.kelasrawatfk', '=', $r['kelasrawat']);
        }
        if (isset($r['belumVerif']) && $r['belumVerif'] != '') {
            $data = $data->whereNull('op.isverifikasi');
        }
        if (isset($r['kelompokpasien']) && $r['kelompokpasien'] != '') {
            $data = $data->where('pd.objectkelompokpasienlastfk', '=', $r['kelompokpasien']);
        }
        if (isset($r['namapasien']) && $r['namapasien'] != '') {
            $data = $data->where('ps.namapasien', 'ilike', '%' . $r['namapasien'] . '%');
        }
        if (isset($r['limit']) && $r['limit'] != '') {
            $data = $data->limit($r['limit']);
        }
        if (isset($r['offset']) && $r['offset'] != '') {
            $data = $data->offset($r['offset']);
        }

        $data = $data->orderBy('pd.tglregistrasi', 'asc');
        $data = $data->get()->toArray();

        // Counting rumus
        $nb = 0;
        $bb = 0;
        foreach ($data as $i => $dt) {
            // Menggabungkan takaran
            $dt->jenisdiet = $dt->jenisdiet . ' ' . $dt->takaran;

            // Filter pasien IGD khusus rencana mutasi saja
            if ($dt->objectdepartemenfk == $depIGD && empty($dt->isRencanaMutasi)) {
                unset($data[$i]);
            }
            // Makanan Biasa
            if ($dt->idkd == 30) {
                $nb++;
            }
            // Makanan Cair
            if ($dt->idkd == 23) {
                $bb++;
            }
        }

        $rumus['nb'] = $nb;
        $rumus['bb'] = $bb;
        $rumus['fajar'] = [
            'NB' => 100 * $nb,
            'BB' => 35 * $bb
        ];
        $rumus['siang'] = [
            'NB' => 125 * $nb,
            'BB' => 50 * $bb
        ];
        $rumus['sore'] = [
            'NB' => 105 * $nb,
            'BB' => 50 * $bb
        ];

        $data = array_values($data);
        $total = count($data);
        $dataResult = array(
            'message' => '@epic',
            'data' => $data,
            'total' => $total,
            'rumus' => $rumus
        );
        return $this->respond($dataResult);
    }
    public function laporanDataGizi(Request $r)
    {

        $depRI = $this->settingFix('idDepRawatInap');
        $depIGD = $this->settingFix('idDepartemenIGD');

        $data = DB::table('orderpelayanan_t as op')
            ->join('ruangan_m as r', 'r.id', '=', 'op.objectruanganfk')
            ->join('pasiendaftar_t as pd', 'pd.norec', '=', 'op.noregistrasifk')
            ->join('pasien_m as ps', 'ps.id', '=', 'op.nocmfk')
            ->join('kategorydiet_m as kd', 'kd.id', '=', 'op.objectkategorydietfk')
            ->join('jeniswaktu_m as jw', 'jw.id', '=', 'op.objectjeniswaktufk')
            ->join('strukorder_t as so', 'op.strukorderfk', '=', 'so.norec')
            ->leftJoin('jenisdiet_m as jd', 'jd.id', '=', 'op.jenisdietfk')
            ->join('antrianpasiendiperiksa_t as apd', function ($join) {
                $join->on('apd.noregistrasifk', '=', 'pd.norec')
                    ->on('apd.objectruanganfk', '=', 'pd.objectruanganlastfk');
            })
            ->leftJoin('tempattidur_m as tt', 'tt.id', '=', 'apd.nobed')
            ->leftJoin('kamar_m as km', 'km.id', '=', 'apd.objectkamarfk')
            ->join('kelas_m as kls', 'kls.id', 'apd.objectkelasfk')
            ->leftJoin('kelas_m as klsr', 'klsr.id', 'apd.kelasrawatfk')
            ->join('kelompokpasien_m as kp', 'kp.id', 'pd.objectkelompokpasienlastfk')
            ->select(
                'tt.reportdisplay as nobed',
                'pd.norec as norec_pd',
                'apd.norec as norec_apd',
                'pd.nocmfk',
                'pd.objectruanganlastfk',
                'pd.isRencanaMutasi',
                'pd.noregistrasi',
                'ps.namapasien',
                'so.tglorder',
                'apd.objectkelasfk',
                'apd.kelasrawatfk',
                'so.noorder',
                'ps.nocm',
                'r.namaruangan',
                'r.objectdepartemenfk',
                'kd.kategorydiet',
                'jw.jeniswaktu',
                'jd.jenisdiet',
                'jw.id as idjw',
                'kd.id as idkd',
                'jd.id as idjd',
                'op.keteranganlainnya',
                'op.norec as norec_op',
                'op.isverifikasi',
                'so.norec as norec_so',
                'km.namakamar as kamar',
                'kls.namakelas as namakelas',
                'klsr.namakelas as namakelasrawat',
                'kp.kelompokpasien'
            )
            ->where('apd.statusenabled', true)
            ->where('so.statusenabled', true)
            ->where('op.statusenabled', true)
            ->whereIn('r.objectdepartemenfk', [$depRI, $depIGD])
            ->where(DB::raw("so.tglorder::date"), '=', $r['tglorder'])
            ->where('jw.id', $r['jenisWaktu']);

        $data = $data->orderBy('pd.tglregistrasi', 'asc')->get();

        $totalPerKelasDiet = $data->groupBy(function($item) {
            return $item->namaruangan .'-'. $item->objectkelasfk . '-' . $item->kategorydiet .'-'. $item->jenisdiet;
        })->map(function($group) {
            return count($group);
        });

        $total = $data->count();
        
        return $this->respond([
            'message' => '@epic',
            'data' => $data,
            'total' => $total,
            'totalPerKelasDiet' => $totalPerKelasDiet
        ]);
    }

    public function getDaftarOrderGizi(Request $request)
    {

        $data = DB::table('orderpelayanan_t as op')
            ->join('pasiendaftar_t as pd', 'pd.norec', '=', 'op.noregistrasifk')
            ->join('ruangan_m as ru', 'ru.id', '=', 'op.objectruanganfk')
            ->join('pasien_m as ps', 'ps.id', '=', 'op.nocmfk')
            ->leftjoin('jeniskelamin_m as jk', 'jk.id', '=', 'ps.objectjeniskelaminfk')
            ->join('strukorder_t as so', 'so.norec', '=', 'op.strukorderfk')
            ->join('ruangan_m as ru2', 'ru2.id', '=', 'so.objectruangantujuanfk')
            ->leftjoin('strukkirim_t as sk', 'sk.norec', '=', 'op.strukkirimfk')
            ->leftjoin('jeniswaktu_m as jw', 'jw.id', '=', 'op.objectjeniswaktufk')
            ->join('kategorydiet_m as kd', 'kd.id', '=', 'op.objectkategorydietfk')
            ->leftjoin('kelas_m as kls', 'kls.id', '=', 'op.objectkelasfk')
            ->leftJoin('pegawai_m as pg', 'pg.id', '=', 'so.objectpegawaiorderfk')
            ->select(
                'so.norec as norec_so',
                'op.norec as norec_op',
                'so.noorder',
                'so.tglorder',
                'so.tglpelayananawal as tglmenu',
                'pd.tglregistrasi',
                'ps.tgllahir',
                'ps.namapasien',
                'ps.nocm',
                'ps.id as nocmfk',
                'ru.namaruangan as ruanganasal',
                'jk.jeniskelamin',
                'op.objectruanganfk',
                'jw.jeniswaktu',
                'so.pengorder',
                'op.strukorderfk',
                'op.objectkategorydietfk',
                'kd.kategorydiet',
                'op.qtyproduk',
                'op.objectjeniswaktufk',
                'op.objectjenisdietfk',
                'op.keteranganlainnya',
                'op.statusgizi as jenisorder',
                'op.qtyprodukinuse as cc',
                'op.jumlah as volume',
                'op.objectkelasfk',
                'kls.namakelas',
                'pd.noregistrasi',
                'so.objectpegawaiorderfk',
                'pg.namalengkap as pegawaiorder',
                'sk.nokirim',
                'sk.qtyproduk',
                'pd.norec as norec_pd',
                'so.objectruangantujuanfk',
                'op.strukkirimfk',
                'sk.nokirim',
                'ru2.namaruangan as ruangantujuan',
                'jw.jeniswaktu',
                'op.objectjeniswaktufk',
                'op.arrjenisdiet',
                DB::raw("case when op.strukkirimfk is not null then 'Sudah Dikirim'  else '-' end as statuskirim")
            )
            ->where('so.kdprofile', $this->kdProfile)
            ->where('so.statusenabled', true)
            ->where('so.objectkelompoktransaksifk', $this->kelompokTransaksi('PELAYANAN GIZI'));

        if (isset($request['deptId']) && $request['deptId'] != "" && $request['deptId'] != "undefined") {
            $data = $data->where('ru.objectdepartemenfk', '=', $request['deptId']);
        }

        if (isset($request['pegId']) && $request['pegId'] != "" && $request['pegId'] != "undefined") {
            $data = $data->where('so.objectpegawaiorderfk', '=', $request['pegId']);
        }
        if (isset($request['ruangId']) && $request['ruangId'] != "" && $request['ruangId'] != "undefined") {
            $data = $data->where('ru.id', '=', $request['ruangId']);
        }
        if (isset($request['jenisDietId']) && $request['jenisDietId'] != "" && $request['jenisDietId'] != "undefined") {
            $data = $data->where('op.objectjenisdietfk', '=', $request['jenisDietId']);
        }
        if (isset($request['jenisWaktuId']) && $request['jenisWaktuId'] != "" && $request['jenisWaktuId'] != "undefined") {
            $data = $data->where('op.objectjeniswaktufk', '=', $request['jenisWaktuId']);
        }

        if (isset($request['noorder']) && $request['noorder'] != "" && $request['noorder'] != "undefined") {
            $data = $data->where('so.noorder', 'ilike', '%' . $request['noorder'] . '%');
        }

        if (isset($request['noreg']) && $request['noreg'] != "" && $request['noreg'] != "undefined") {
            $data = $data->where('pd.noregistrasi', 'ilike', '%' . $request['noreg'] . '%');
        }
        if (isset($request['norm']) && $request['norm'] != "" && $request['norm'] != "undefined") {
            $data = $data->where('ps.nocm', 'ilike', '%' . $request['norm'] . '%');
        }
        if (isset($request['nama']) && $request['nama'] != "" && $request['nama'] != "undefined") {
            $data = $data->where('ps.namapasien', 'ilike', '%' . $request['nama'] . '%');
        }
        $data = $data->whereNull('pd.tglpulang');
        $data = $data->orderBy('so.noorder');
        $data = $data->get();

        foreach ($data as $item) {
            $item->umur = $this->getAge($item->tgllahir, $item->tglregistrasi);
        }

        $dataResult = array(
            'message' => 'inhuman',
            'data' => $data,

        );
        return $this->respond($dataResult);
    }

    public function laporanOrderGiziOld(Request $r)
    {
        $data = DB::table('orderpelayanan_t as op')
            ->leftjoin('pasiendaftar_t as pd', 'pd.norec', '=', 'op.noregistrasifk')
            ->join('antrianpasiendiperiksa_t as apd', 'apd.noregistrasifk', '=', 'pd.norec')
            ->leftJoin('tempattidur_m as tt', 'tt.id', 'apd.nobed')
            ->leftjoin('kelompokpasien_m as kp', 'pd.objectkelompokpasienlastfk', '=', 'kp.id')
            ->leftjoin('ruangan_m as ru', 'ru.id', '=', 'op.objectruanganfk')
            ->leftjoin('pasien_m as ps', 'ps.id', '=', 'op.nocmfk')
            ->leftjoin('strukorder_t as so', 'so.norec', '=', 'op.strukorderfk')
            ->leftjoin('ruangan_m as ru2', 'ru2.id', '=', 'so.objectruangantujuanfk')
            ->leftjoin('jenisdiet_m as jd', 'jd.id', '=', 'op.objectjenisdietfk')
            ->leftjoin('kategorydiet_m as kd', 'kd.id', '=', 'op.objectkategorydietfk')
            ->leftjoin('kelas_m as kls', 'kls.id', '=', 'op.objectkelasfk')
            ->leftJoin('pegawai_m as pg', 'pg.id', '=', 'so.objectpegawaiorderfk')
            ->select(
                'pd.norec',
                'so.norec as norec_so',
                'op.norec as norec_op',
                'so.noorder',
                'tt.reportdisplay',
                'so.tglorder',
                'so.tglpelayananawal as tglmenu',
                'pd.tglregistrasi',
                'ps.tgllahir',
                'ps.nocm',
                'ps.namapasien',
                'pd.nocmfk',
                'ps.id as nocmfk',
                'ru.namaruangan as ruanganasal',
                'op.objectruanganfk',
                'op.strukorderfk',
                'op.objectkategorydietfk',
                'kd.kategorydiet',
                'op.qtyproduk',
                'op.keteranganlainnya',
                'op.statusgizi as jenisorder',
                'op.qtyprodukinuse as cc',
                'op.jumlah as volume',
                'op.objectkelasfk',
                'kls.namakelas',
                'pd.noregistrasi',
                'pg.namalengkap as pegawaiorder',
                'pd.norec',
                'so.objectruangantujuanfk',
                'op.strukkirimfk',
                'ru2.namaruangan as ruangantujuan',
                'op.arrjenisdiet',
                'kp.kelompokpasien',
            )
            ->where('op.kdprofile', $this->kdProfile)
            ->where('op.statusenabled', true)
            ->whereNotNull('tt.reportdisplay')
            ->whereIN('so.objectkelompoktransaksifk', explode(',', $this->settingFix('idPelayananGizi')));

        if (isset($r['dari']) && $r['dari'] != '') {
            $data = $data->where(DB::raw("so.tglorder::date"), '>=', $r->dari);
        }
        if (isset($r['sampai']) && $r['sampai'] != '') {
            $data = $data->where(DB::raw("so.tglorder::date"), '<=', $r->sampai);
        }
        if (isset($r['noregistrasi']) && $r['noregistrasi'] != '') {
            $data = $data->where('pd.noregistrasi', '=', $r['noregistrasi']);
        }

        if (isset($r['namapasien']) && $r['namapasien'] != '') {
            $data = $data->where('ps.namapasien', 'ilike', '%' . $r['namapasien'] . '%');
        }
        if (isset($r['nocm']) && $r['nocm'] != '') {
            $data = $data->where('ps.nocm', 'ilike', '%' . $r['nocm'] . '%');
        }

        $data = $data->whereNull('pd.tglpulang');
        $data = $data->orderBy('so.noorder', 'desc');
        $data = $data->get();
        $dataResult = array(
            'message' => '@epic',
            'data' => $data,
        );
        return $this->respond($dataResult);
    }

    public function laporanOrderGizi(Request $r)
    {
        $data = DB::table('orderpelayanan_t as op')
            ->join('jenisdiet_m as jd', 'jd.id', '=', 'op.jenisdietfk')
            ->join('strukorder_t as so', 'op.strukorderfk', '=', 'so.norec')
            ->select('jd.jenisdiet', DB::raw('COUNT(op.norec) as total'))
            ->groupBy('jd.jenisdiet');

        if (isset($r['dari']) && $r['dari'] != '') {
            $data = $data->where(DB::raw("so.tglorder::date"), '>=', $r->dari);
        }
        if (isset($r['sampai']) && $r['sampai'] != '') {
            $data = $data->where(DB::raw("so.tglorder::date"), '<=', $r->sampai);
        }
        if (isset($r['ruanganid']) && $r['ruanganid'] != '') {
            $data = $data->where('op.objectruanganfk', $r['ruanganid']);
        }
        if (isset($r['kelasid']) && $r['kelasid'] != '') {
            $data = $data->where('op.objectkelasfk', $r['kelasid']);
        }
        if (isset($r['kelasrawatfk']) && $r['kelasrawatfk'] != '') {
            $data = $data->where('op.kelasrawatfk', $r['kelasrawatfk']);
        }
        if (isset($r['jeniswaktuid']) && $r['jeniswaktuid'] != '') {
            $data = $data->where('op.objectjeniswaktufk', $r['jeniswaktuid']);
        }

        $data = $data->orderBy('jd.jenisdiet', 'asc');
        $data = $data->get();
        $dataResult = array(
            'message' => '@epic',
            'data' => $data,
        );
        return $this->respond($dataResult);
    }

    public function verifikasiOrderGizi(Request $r)
    {
        DB::beginTransaction();
        try {
            if ($r['status'] == 'verif') {
                $statusVerif = ['isverifikasi' => true];
            } else {
                $statusVerif = ['isverifikasi' => null];
            }

            OrderPelayanan::where('strukorderfk', $r['norec_so'])->update($statusVerif);
            $dataPD = PasienDaftar::where('norec', $r['norec_pd'])->where('kdprofile', $this->kdProfile)->first();
            $objetoRequest = new \Illuminate\Http\Request();
            $objetoRequest['noregistrasi'] = $dataPD->noregistrasi;
            $ihs = app('App\Http\Controllers\Bridging\SATUSEHATCtrl')->Composition($objetoRequest, true);

            // $this->LOGGING(
            //     'Verifikasi Order Gizi',
            //     $r['norec_op'],
            //     'orderpelayanan_t',
            //     'Verifikasi Order Gizi ' .
            //         $r['namapasien'] . ' (' . $r['nocm'] . ') - ' . $r['noregistrasi']
            // );

            DB::commit();
            $result = array(
                "status" => 200,
                "message" => "Verifikasi Berhasil",
                "result" => array(
                    "composition" => $ihs,
                    "as" => '@epic',
                ),
            );
        } catch (\Exception $e) {
            DB::rollBack();
            $result = array(
                "status" => 400,
                "message" => 'Gagal',
                "result" => $e->getMessage() . " " . $e->getLine()
            );
        }

        return $this->respond($result['result'], $result['status'], $result['message']);
    }

    public function simpanOrderGizi(Request $r)
    {

        DB::beginTransaction();
        try {
            $item = $r['strukorder'];
            $tglorder = date('Y-m-d H:i:s');
            if ($item['norec_so'] == '') {
                $noOrder = $this->SEQUENCE(new StrukOrder, 'noorder', 11, 'G' . $this->getDateTime()->format('ym'), $this->kdProfile);
                $dataSO = new StrukOrder();
                $dataSO->norec = $dataSO->generateNewId();
                $dataSO->kdprofile = $this->kdProfile;
                $dataSO->statusenabled = true;
                $dataSO->isdelivered = 1;
                $dataSO->noorder = $noOrder;
                $dataSO->noorderintern = $noOrder;
            } else {
                $dataSO = StrukOrder::where('norec', $item['norec_so'])->where('kdprofile', $this->kdProfile)->first();
                $del = OrderPelayanan::where('strukorderfk', $item['norec_so'])->where('kdprofile', $this->kdProfile)->delete();
            }
            $dataSO->noregistrasifk = $item['details'][0]['norec_pd'];
            $dataSO->nocmfk = $item['nocmfk'];
            $dataSO->noregistrasi = $item['noregistrasi'];
            $dataSO->norec_apd = $item['norec_apd'];
            $dataSO->objectpegawaiorderfk = $this->getUserId();
            $dataSO->qtyjenisproduk = 1;
            $dataSO->qtyproduk = 1;
            $dataSO->objectruangantujuanfk = $this->settingFix('idInstalasiGizi', $this->kdProfile);
            $dataSO->keteranganorder = 'Order Gizi';
            $dataSO->objectkelompoktransaksifk = 8; /* Pelayanan Gizi*/
            $dataSO->tglorder = $item['tglorder']; // date('Y-m-d H:i:s');
            // $dataSO->tglpelayananawal = $item['tglmenu']; //$item['tglorder'];
            $dataSO->totalbeamaterai = 0;
            $dataSO->totalbiayakirim = 0;
            $dataSO->totalbiayatambahan = 0;
            $dataSO->totaldiscount = 0;
            $dataSO->totalhargasatuan = 0;
            $dataSO->totalharusdibayar = 0;
            $dataSO->totalpph = 0;
            $dataSO->totalppn = 0;
            $dataSO->save();
            $dataSOnorec = $dataSO->norec;

            AntrianPasienDiperiksa::where('norec', $item['norec_apd'])
                ->where('statusenabled', true)
                ->where('kdprofile', $this->kdProfile)
                ->update(['isordergizi' => true]);

            foreach ($item['details'] as $itemDetails) {
                $dataOP = new OrderPelayanan;
                $dataOP->norec = $dataOP->generateNewId();
                $dataOP->kdprofile = $this->kdProfile;
                $dataOP->statusenabled = true;
                $dataOP->iscito = 0;
                $dataOP->jenisdietfk = $itemDetails['jenisdietfk']['value'];
                // $dataOP->jenisdietexternal = $itemDetails['jenisdietexternal'];
                $dataOP->objectjeniswaktufk = $itemDetails['objectjeniswaktufk'];
                $dataOP->objectkategorydietfk = $itemDetails['objectkategorydietfk'];
                $dataOP->keteranganlainnya = $itemDetails['keteranganlainnya']; //'Order Gizi';
                $dataOP->takaran = $itemDetails['takaran'];
                $dataOP->nocmfk = $itemDetails['nocmfk'];
                $dataOP->noregistrasifk = $itemDetails['norec_pd'];
                $dataOP->noorderfk = $dataSOnorec;
                $dataOP->qtyproduk = 1;
                $dataOP->objectkelasfk = $itemDetails['objectkelasfk'];
                $dataOP->kelasrawatfk = $itemDetails['kelasrawatfk'];
                $dataOP->qtyprodukretur = 0;
                $dataOP->objectruanganfk = $itemDetails['objectruanganlastfk'];
                $dataOP->objectruangantujuanfk = $this->settingFix('idInstalasiGizi', $this->kdProfile);
                $dataOP->strukorderfk = $dataSOnorec;
                $dataOP->tglpelayanan = $item['tglorder'];
                // $dataOP->batasKonsumsiAwal = $itemDetails['batasKonsumsiAwal'];
                // $dataOP->batasKonsumsiAkhir = $itemDetails['batasKonsumsiAkhir'];
                $dataOP->save();
            }
            $dataPD = PasienDaftar::where('norec', $item['norec_pd'])->where('kdprofile', $this->kdProfile)->first();
            $objetoRequest = new \Illuminate\Http\Request();
            $objetoRequest['noregistrasi'] = $dataPD->noregistrasi;
            $ihs = app('App\Http\Controllers\Bridging\SATUSEHATCtrl')->Composition($objetoRequest, true);
            DB::commit();
            $result = array(
                "status" => 200,
                "message" => "Simpan Berhasil",
                "result" => array(
                    "data" => $dataSO,
                    "composition" => $ihs,
                    "as" => '@epic',
                ),
            );
        } catch (\Exception $e) {
            DB::rollBack();

            $result = array(
                "status" => 400,
                "message" => 'Gagal',
                "result" => $e->getMessage() . " " . $e->getLine()

            );
        }

        return $this->respond($result['result'], $result['status'], $result['message']);
    }

    public function simpanMultipleOrderGizi(Request $r)
    {
        DB::beginTransaction();
        try {
            $data = $r['data'];
            foreach ($data as $do) {
                $data = $do['strukorder'];
                $noOrder = $this->SEQUENCE(new StrukOrder, 'noorder', 11, 'G' . $this->getDateTime()->format('ym'), $this->kdProfile);
                $dataSO = new StrukOrder();
                $dataSO->norec = $dataSO->generateNewId();
                $dataSO->kdprofile = $this->kdProfile;
                $dataSO->statusenabled = true;
                $dataSO->isdelivered = 1;
                $dataSO->noorder = $noOrder;
                $dataSO->noorderintern = $noOrder;
                $dataSO->nocmfk = $data['nocmfk'];
                $dataSO->noregistrasifk = $data['details'][0]['norec_pd'];
                $dataSO->norec_apd = $data['norec_apd'];
                $dataSO->noregistrasi = $data['noregistrasifk'];
                $dataSO->objectpegawaiorderfk = $this->getUserId();
                $dataSO->qtyjenisproduk = 1;
                $dataSO->qtyproduk = 1;
                $dataSO->objectruangantujuanfk = $this->settingFix('idInstalasiGizi', $this->kdProfile);
                $dataSO->keteranganorder = 'Order Gizi';
                $dataSO->objectkelompoktransaksifk = 8;
                $dataSO->tglorder = $data['tglorder'];
                $dataSO->totalbeamaterai = 0;
                $dataSO->totalbiayakirim = 0;
                $dataSO->totalbiayatambahan = 0;
                $dataSO->totaldiscount = 0;
                $dataSO->totalhargasatuan = 0;
                $dataSO->totalharusdibayar = 0;
                $dataSO->totalpph = 0;
                $dataSO->totalppn = 0;
                $dataSO->save();
                $dataSOnorec = $dataSO->norec;

                AntrianPasienDiperiksa::where('norec', $data['norec_apd'])
                    ->where('statusenabled', true)
                    ->where('kdprofile', $this->kdProfile)
                    ->update(['isordergizi' => true]);

                foreach ($data['details'] as $itemDetails) {
                    $dataOP = new OrderPelayanan;
                    $dataOP->norec = $dataOP->generateNewId();
                    $dataOP->kdprofile = $this->kdProfile;
                    $dataOP->statusenabled = true;
                    $dataOP->iscito = 0;
                    // $dataOP->arrjenisdiet = $itemDetails['arrjenisdiet'];
                    $dataOP->jenisdietfk = $itemDetails['arrjenisdiet'];
                    // $dataOP->jenisdietexternal = $itemDetails['jenisdietexternal'];
                    $dataOP->objectjeniswaktufk = $itemDetails['objectjeniswaktufk'];
                    $dataOP->objectkategorydietfk = $itemDetails['objectkategorydietfk'];
                    $dataOP->keteranganlainnya = $itemDetails['keteranganlainnya'];
                    $dataOP->takaran = $itemDetails['takaran'];
                    $dataOP->nocmfk = $itemDetails['nocmfk'];
                    $dataOP->noregistrasifk = $itemDetails['norec_pd'];
                    $dataOP->noorderfk = $dataSOnorec;
                    $dataOP->qtyproduk = 1;
                    $dataOP->objectkelasfk = $itemDetails['objectkelasfk'];
                    $dataOP->kelasrawatfk = $itemDetails['kelasrawatfk'];
                    $dataOP->qtyprodukretur = 0;
                    $dataOP->objectruanganfk = $itemDetails['objectruanganlastfk'];
                    $dataOP->objectruangantujuanfk = $this->settingFix('idInstalasiGizi', $this->kdProfile);
                    $dataOP->strukorderfk = $dataSOnorec;
                    $dataOP->tglpelayanan = $data['tglorder'];
                    // $dataOP->batasKonsumsiAwal = $itemDetails['batasKonsumsiAwal'];
                    // $dataOP->batasKonsumsiAkhir = $itemDetails['batasKonsumsiAkhir'];
                    $dataOP->save();
                }

                $dataPD = PasienDaftar::where('norec', $data['norec_pd'])->where('kdprofile', $this->kdProfile)->first();
                $objetoRequest = new \Illuminate\Http\Request();
                $objetoRequest['noregistrasi'] = $dataPD->noregistrasi;
                $ihs = app('App\Http\Controllers\Bridging\SATUSEHATCtrl')->Composition($objetoRequest, true);
            }
            DB::commit();
            $result = array(
                "status" => 200,
                "message" => "Simpan Berhasil",
                "result" => array(
                    "as" => '@epic',
                ),
            );
        } catch (\Exception $e) {
            DB::rollBack();
            $result = array(
                "status" => 400,
                "message" => 'Gagal',
                "result" => $e->getMessage() . " " . $e->getLine()
            );
        }
        return $this->respond($result['result'], $result['status'], $result['message']);
    }
    public function editMultipleOrderGizi(Request $r)
    {

        DB::beginTransaction();
        try {
            $data = $r['data'];
            foreach ($data as $do) {
                $data = $do['strukorder'];
                $dataSO = StrukOrder::where('norec', $data['norec_so'])->where('kdprofile', $this->kdProfile)->first();
                $dataSO->kdprofile = $this->kdProfile;
                $dataSO->statusenabled = true;
                $dataSO->isdelivered = 1;
                $dataSO->nocmfk = $data['nocmfk'];
                $dataSO->noregistrasifk = $data['details'][0]['norec_pd'];
                $dataSO->noregistrasi = $data['noregistrasifk'];
                $dataSO->objectpegawaiorderfk = $this->getUserId();
                $dataSO->qtyjenisproduk = 1;
                $dataSO->qtyproduk = 2;
                $dataSO->objectruangantujuanfk = $this->settingFix('kdRuanganGizi', $this->kdProfile);
                $dataSO->keteranganorder = 'Order Gizi';
                $dataSO->objectkelompoktransaksifk = 8;
                $dataSO->tglorder = $data['tglorder'];
                $dataSO->totalbeamaterai = 0;
                $dataSO->totalbiayakirim = 0;
                $dataSO->totalbiayatambahan = 0;
                $dataSO->totaldiscount = 0;
                $dataSO->totalhargasatuan = 0;
                $dataSO->totalharusdibayar = 0;
                $dataSO->totalpph = 0;
                $dataSO->totalppn = 0;
                $dataSO->save();
                $dataSOnorec = $dataSO->norec;

                foreach ($data['details'] as $itemDetails) {
                    $dataOP = OrderPelayanan::where('norec', $itemDetails['norec_op'])->first();
                    if ($dataOP) {
                        $dataOP->kdprofile = $this->kdProfile;
                        $dataOP->statusenabled = true;
                        $dataOP->iscito = 0;
                        $dataOP->arrjenisdiet = $itemDetails['arrjenisdiet'];
                        $dataOP->objectkategorydietfk = $itemDetails['objectkategorydietfk'];
                        $dataOP->keteranganlainnya = $itemDetails['keteranganlainnya'];
                        $dataOP->nocmfk = $itemDetails['nocmfk'];
                        $dataOP->noregistrasifk = $itemDetails['norec_pd'];
                        $dataOP->noorderfk = $dataSOnorec;
                        $dataOP->qtyproduk = 1;
                        $dataOP->objectkelasfk = $itemDetails['objectkelasfk'];
                        $dataOP->kelasrawatfk = $itemDetails['kelasrawatfk'];
                        $dataOP->qtyprodukretur = 0;
                        $dataOP->objectruanganfk = $itemDetails['objectruanganlastfk'];
                        $dataOP->objectruangantujuanfk = $this->settingFix('kdRuanganGizi', $this->kdProfile);
                        $dataOP->strukorderfk = $dataSOnorec;
                        $dataOP->tglpelayanan = $data['tglorder'];
                        $dataOP->save();
                    }
                }

                $dataPD = PasienDaftar::where('norec', $data['norec_pd'])->where('kdprofile', $this->kdProfile)->first();
                $objetoRequest = new \Illuminate\Http\Request();
                $objetoRequest['noregistrasi'] = $dataPD->noregistrasi;
                $ihs = app('App\Http\Controllers\Bridging\SATUSEHATCtrl')->Composition($objetoRequest, true);
            }
            DB::commit();
            $result = array(
                "status" => 200,
                "message" => "Simpan Berhasil",
                "result" => array(
                    "as" => '@epic',
                ),
            );
        } catch (\Exception $e) {
            DB::rollBack();
            $result = array(
                "status" => 400,
                "message" => 'Gagal',
                "result" => $e->getMessage() . " " . $e->getLine()
            );
        }
        return $this->respond($result['result'], $result['status'], $result['message']);
    }


    public function editOrderGizi(Request $r)
    {

        DB::beginTransaction();
        try {
            $data = $r['strukorder'];
            $dataSO = StrukOrder::where('norec', $data['norec_so'])->where('kdprofile', $this->kdProfile)->first();
            $dataSO->kdprofile = $this->kdProfile;
            $dataSO->statusenabled = true;
            $dataSO->isdelivered = 1;
            $dataSO->nocmfk = $data['nocmfk'];
            $dataSO->noregistrasifk = $data['details'][0]['norec_pd'];
            $dataSO->noregistrasi = $data['noregistrasifk'];
            $dataSO->objectpegawaiorderfk = $this->getUserId();
            $dataSO->qtyjenisproduk = 1;
            $dataSO->qtyproduk = 1;
            $dataSO->objectruangantujuanfk = $this->settingFix('kdRuanganGizi', $this->kdProfile);
            $dataSO->keteranganorder = 'Order Gizi';
            $dataSO->objectkelompoktransaksifk = 8;
            $dataSO->tglorder = $data['tglorder'];
            $dataSO->totalbeamaterai = 0;
            $dataSO->totalbiayakirim = 0;
            $dataSO->totalbiayatambahan = 0;
            $dataSO->totaldiscount = 0;
            $dataSO->totalhargasatuan = 0;
            $dataSO->totalharusdibayar = 0;
            $dataSO->totalpph = 0;
            $dataSO->totalppn = 0;
            $dataSO->save();
            $dataSOnorec = $dataSO->norec;

            foreach ($data['details'] as $itemDetails) {
                $dataOP = OrderPelayanan::where('norec', $itemDetails['norec_op'])->first();
                if ($dataOP) {
                    $dataOP->kdprofile = $this->kdProfile;
                    $dataOP->statusenabled = true;
                    $dataOP->iscito = 0;
                    $dataOP->jenisdietfk = $itemDetails['arrjenisdiet'];
                    $dataOP->objectjeniswaktufk = $itemDetails['objectjeniswaktufk'];
                    $dataOP->objectkategorydietfk = $itemDetails['objectkategorydietfk'];
                    $dataOP->keteranganlainnya = $itemDetails['keteranganlainnya'];
                    $dataOP->nocmfk = $itemDetails['nocmfk'];
                    $dataOP->noregistrasifk = $itemDetails['norec_pd'];
                    $dataOP->noorderfk = $dataSOnorec;
                    $dataOP->qtyproduk = 1;
                    $dataOP->objectkelasfk = $itemDetails['objectkelasfk'];
                    $dataOP->kelasrawatfk = $itemDetails['kelasrawatfk'];
                    $dataOP->qtyprodukretur = 0;
                    $dataOP->objectruanganfk = $itemDetails['objectruanganlastfk'];
                    $dataOP->objectruangantujuanfk = $this->settingFix('kdRuanganGizi', $this->kdProfile);
                    $dataOP->strukorderfk = $dataSOnorec;
                    $dataOP->tglpelayanan = $data['tglorder'];
                    $dataOP->save();
                }
            }

            $dataPD = PasienDaftar::where('norec', $data['norec_pd'])->where('kdprofile', $this->kdProfile)->first();
            $objetoRequest = new \Illuminate\Http\Request();
            $objetoRequest['noregistrasi'] = $dataPD->noregistrasi;
            $ihs = app('App\Http\Controllers\Bridging\SATUSEHATCtrl')->Composition($objetoRequest, true);
            DB::commit();
            $result = array(
                "status" => 200,
                "message" => "Simpan Berhasil",
                "result" => array(
                    "as" => '@epic',
                ),
            );
        } catch (\Exception $e) {
            DB::rollBack();
            $result = array(
                "status" => 400,
                "message" => 'Gagal',
                "result" => $e->getMessage() . " " . $e->getLine()
            );
        }
        return $this->respond($result['result'], $result['status'], $result['message']);
    }
    public function deleteOrderGizi(Request $r)
    {
        DB::beginTransaction();
        try {

            $model = OrderPelayanan::where('norec', $r['norec'])->delete();
            AntrianPasienDiperiksa::where('norec', $r['norec_apd'])
                ->where('kdprofile', $this->kdProfile)
                ->update(['isordergizi' => null]);
            $transStatus = 'true';
        } catch (\Exception $e) {
            $transStatus = 'false';
        }

        if ($transStatus == 'true') {
            $transMessage = "Data Berhasil Di Hapus";
            DB::commit();
            $result = array(
                "status" => 200,
                "result" => array(
                    "data" => $model,
                    "as" => '@epic',
                ),
            );
        } else {
            $transMessage = "Hapus Gagal";
            DB::rollBack();
            $result = array(
                "status" => 400,
                "result" => null

            );
        }
        return $this->respond($result['result'], $result['status'], $transMessage);
    }

    public function multipleDeleteOrderGizi(Request $r)
    {
        DB::beginTransaction();
        try {
            $data = $r['data'];
            foreach ($data as $d) {
                OrderPelayanan::where('norec', $d['norec_op'])->delete();
                // AntrianPasienDiperiksa::where('norec', $d['norec_apd'])
                //     ->where('kdprofile', $this->kdProfile)
                //     ->update(['isordergizi' => null]);
            }

            $transStatus = 'true';
        } catch (\Exception $e) {
            $transStatus = 'false';
        }

        if ($transStatus == 'true') {
            $transMessage = "Data Berhasil Di Hapus";
            DB::commit();
            $result = array(
                "status" => 200,
                "result" => array(
                    "as" => '@epic',
                ),
            );
        } else {
            $transMessage = "Hapus Gagal";
            DB::rollBack();
            $result = array(
                "status" => 400,
                "result" => null

            );
        }
        return $this->respond($result['result'], $result['status'], $transMessage);
    }

    public function saveKirimGizi(Request $r)
    {
        DB::beginTransaction();
        try {
            if ($r['strukkirim']['norec_sk'] == '') {
                $noKirim = $this->SEQUENCE(new StrukKirim, 'nokirim', 14, 'KM-' . $this->getDateTime()->format('ym'), $this->kdProfile);
                $dataSK = new StrukKirim();
                $dataSK->norec = $dataSK->generateNewId();
                $dataSK->nokirim = $noKirim;
                $dataSK->kdprofile = $this->kdProfile;
                $dataSK->statusenabled = true;
                $dataSK->tglkirim = date('Y-m-d H:i:s');
            } else {
                $dataSK = StrukKirim::where('norec', $r['strukkirim']['norec_sk'])->first();
                $delKP = KirimProduk::where('nokirimfk', $r['strukkirim']['norec_sk'])->delete();
            }
            $dataSK->objectpegawaipengirimfk = $this->getUserId();
            $dataSK->objectruanganasalfk = $r['strukkirim']['objectruanganasalfk']; /*ruang gizi */
            $dataSK->objectruanganfk = $r['strukkirim']['objectruanganfk'];
            $dataSK->jenispermintaanfk = 1; /*transfer */
            $dataSK->objectkelompoktransaksifk = 98;
            // $dataSK->keteranganlainnyakirim = $r['strukkirim']['keterangan'];
            $dataSK->qtydetailjenisproduk = 0;
            $dataSK->qtyproduk = 1;
            $dataSK->totalbeamaterai = 0;
            $dataSK->totalbiayakirim = 0;
            $dataSK->totalbiayatambahan = 0;
            $dataSK->totaldiscount = 0;
            $dataSK->totalhargasatuan = 0;
            $dataSK->totalharusdibayar = 0;
            $dataSK->totalpph = 0;
            $dataSK->totalppn = 0;
            $dataSK->noregistrasifk = $r['strukkirim']['noregistrasifk'];

            $dataSK->save();
            $norecSK = $dataSK->norec;

            foreach ($r['strukkirim']['details'] as $items) {
                $dataKP = new KirimProduk;
                $dataKP->norec = $dataKP->generateNewId();
                $dataKP->kdprofile = $this->kdProfile;
                $dataKP->statusenabled = true;
                $dataKP->hargadiscount = 0;
                $dataKP->harganetto = 0;
                $dataKP->hargapph = 0;
                $dataKP->hargappn = 0;
                $dataKP->hargasatuan = 0;
                $dataKP->hargatambahan = 0;
                $dataKP->objectprodukfk = $items['produk'];
                $dataKP->objectprodukkirimfk = $items['produk'];
                $dataKP->nokirimfk = $norecSK;
                $dataKP->persendiscount = 0;
                $dataKP->qtyproduk = $items['qtyproduk'];
                $dataKP->qtyprodukkonfirmasi = $items['qtyproduk'];
                $dataKP->qtyprodukretur = 0;
                $dataKP->qtyprodukterima = $items['qtyproduk'];
                $dataKP->objectruanganpengirimfk = $r['strukkirim']['objectruanganasalfk'];
                $dataKP->satuan = '-';
                $dataKP->tglpelayanan = date('Y-m-d H:i:s');
                $dataKP->qtyprodukterimakonversi = $items['qtyproduk'];
                $dataKP->save();
            }

            $kdProfile = $this->kdProfile;

            $norec_pd = $r['strukkirim']['noregistrasifk'];
            $pd = DB::table('pasiendaftar_t as pd')->where('pd.norec', $norec_pd)
                ->join('pasien_m as pe', 'pe.id', '=', 'pd.nocmfk')
                ->select('pe.namapasien', 'pe.nocm', 'pd.*')
                ->first();

            $apd = AntrianPasienDiperiksa::where('noregistrasifk', $norec_pd)
                ->where('objectruanganfk', $r['strukkirim']['objectruanganasalfk'])
                ->first();
            $data = DB::select(
                DB::raw("select * from pelayananpasien_t as pp
                    where pp.noregistrasi = '$pd->noregistrasi' and pp.keteranganlain = 'gizi otomatis' and pp.kdprofile = $this->kdProfile
                    and pp.statusenabled=true")

            );
            $jenispelayananfk = " and hett.objectjenispelayananfk = " . $pd->jenispelayanan;

            $objectrekananfk = "  AND hett.objectpenjaminfk IS NULL";
            if (count($data) == 0) {
                $set = explode(',', $this->settingFix('kodePelayananGiziOtomatis'));
                //var_dump($set);

                if ($set[0] != '0') {
                    foreach ($set as $dd) {
                        $sirahMacan = DB::select(
                            DB::raw("select hett.* from harganettoprodukbykelas_m as hett
                        where hett.objectkelasfk='$apd->objectkelasfk'
                        and hett.kdprofile =$kdProfile
                        and hett.objectprodukfk = $dd
                        and hett.statusenabled=true
                        $objectrekananfk
                        $jenispelayananfk
                        ")
                        );
                        $produk = Produk::where('id', $dd)
                            ->first();
                        if (count($sirahMacan) == 0) {

                            $this->LOGGING(
                                'Pelayanan Gizi Otomatis',
                                $pd->noregistrasi,
                                'pasiendaftar_t',
                                'Pelayanan Gizi Otomatis gagal Master tarif tidak ada (' . $produk->namaproduk . ') pada Pasien ' . $pd->namapasien . ' (' . $pd->nocm . ') - ' . $pd->noregistrasi
                            );
                        } else {
                            $PelPasien = new PelayananPasien();
                            $PelPasien->norec = $PelPasien->generateNewId();
                            $PelPasien->kdprofile = $kdProfile;
                            $PelPasien->statusenabled = true;
                            $PelPasien->noregistrasifk = $apd->norec;
                            $PelPasien->tglregistrasi = $pd->tglregistrasi;
                            $PelPasien->hargadiscount = 0;
                            $PelPasien->hargajual = $sirahMacan[0]->hargasatuan;
                            $PelPasien->hargasatuan = $sirahMacan[0]->hargasatuan;
                            $PelPasien->jumlah = 1;
                            $PelPasien->kelasfk = $apd->objectkelasfk;
                            $PelPasien->kdkelompoktransaksi = 1;
                            $PelPasien->piutangpenjamin = 0;
                            $PelPasien->piutangrumahsakit = 0;
                            $PelPasien->produkfk = $sirahMacan[0]->objectprodukfk;
                            $PelPasien->stock = 1;
                            $PelPasien->tglpelayanan = date('Y-m-d H:i:s');
                            $PelPasien->keteranganlain = 'gizi otomatis';
                            $PelPasien->harganetto = $sirahMacan[0]->harganetto1;
                            $PelPasien->noregistrasi = $pd->noregistrasi;
                            $PelPasien->save();
                            $PPnorec = $PelPasien->norec;

                            $buntutMacan = DB::select(
                                DB::raw("select hett.* from harganettoprodukbykelasd_m as hett
                            where hett.objectkelasfk='$apd->objectkelasfk'
                            and hett.kdprofile =$kdProfile
                            and hett.objectprodukfk = $dd
                            and hett.statusenabled=true
                            $objectrekananfk
                            $jenispelayananfk
                            ")
                            );

                            foreach ($buntutMacan as $itemKomponen) {
                                $PelPasienDetail = new PelayananPasienDetail();
                                $PelPasienDetail->norec = $PelPasienDetail->generateNewId();
                                $PelPasienDetail->kdprofile = $kdProfile;
                                $PelPasienDetail->statusenabled = true;
                                $PelPasienDetail->noregistrasifk = $apd->norec;
                                $PelPasienDetail->aturanpakai = '-';
                                $PelPasienDetail->hargadiscount = 0;
                                $PelPasienDetail->hargajual = $itemKomponen->hargasatuan;
                                $PelPasienDetail->hargasatuan = $itemKomponen->hargasatuan;
                                $PelPasienDetail->jumlah = 1;
                                $PelPasienDetail->keteranganlain = '-';
                                $PelPasienDetail->keteranganpakai2 = '-';
                                $PelPasienDetail->komponenhargafk = $itemKomponen->objectkomponenhargafk;
                                $PelPasienDetail->pelayananpasien = $PPnorec;
                                $PelPasienDetail->piutangpenjamin = 0;
                                $PelPasienDetail->piutangrumahsakit = 0;
                                $PelPasienDetail->produkfk = $itemKomponen->objectprodukfk;
                                $PelPasienDetail->stock = 1;
                                $PelPasienDetail->tglpelayanan = date('Y-m-d H:i:s');
                                $PelPasienDetail->harganetto = $itemKomponen->harganetto1;
                                $PelPasienDetail->noregistrasi = $pd->noregistrasi;
                                $PelPasienDetail->save();

                                $diskon = 0;
                            }


                            $this->LOGGING(
                                'Pelayanan Gizi Otomatis',
                                $PPnorec,
                                'pelayananpasien_t',
                                'Pelayanan Gizi Otomatis Sukses, Layanan : ' . $produk->namaproduk . ' pada pasien ' . $pd->namapasien . ' (' . $pd->nocm . ') - ' . $pd->noregistrasi
                            );
                        }
                    }
                }

            }

            OrderPelayanan::where('norec', $r['strukkirim']['norec_op'])->update([
                'strukkirimfk' => $norecSK,
            ]);

            DB::commit();
            $result = array(
                "status" => 200,
                "message" => "Simpan Berhasil",
                "result" => array(
                    "data" => $dataSK,
                    "as" => '@epic',
                ),
            );
        } catch (\Exception $e) {
            DB::rollBack();

            $result = array(
                "status" => 400,
                "message" => 'Gagal',
                "result" => $e->getMessage() . " " . $e->getLine()

            );
        }

        return $this->respond($result['result'], $result['status'], $result['message']);
    }

    function riwayatKirimGizi(Request $r)
    {
        $data = DB::table('strukkirim_t as sk')
            ->leftjoin('pasiendaftar_t as pd', 'pd.norec', '=', 'sk.noregistrasifk')
            ->leftjoin('pasien_m as ps', 'ps.id', '=', 'pd.nocmfk')
            ->leftjoin('ruangan_m as ru', 'ru.id', '=', 'sk.objectruanganasalfk')
            ->leftjoin('ruangan_m as ru2', 'ru2.id', '=', 'sk.objectruangantujuanfk')
            ->leftJoin('pegawai_m as pg', 'pg.id', '=', 'sk.objectpegawaipengirimfk')
            ->select(
                'sk.norec as norec_sk',
                'sk.nokirim',
                'pg.namalengkap as pegawaikirim',
                'ru2.namaruangan as ruangantujuan',
                'sk.tglkirim',
                'ru.namaruangan as ruanganasal',
                'sk.objectruangantujuanfk',
                'sk.objectruanganasalfk',
                'sk.objectpegawaipengirimfk',
                'pd.noregistrasi',
                'ps.nocm',
                'ps.namapasien',
                'sk.keteranganlainnyakirim',
                'sk.qtyproduk'
            )
            ->where('sk.statusenabled', true)
            ->where('sk.objectkelompoktransaksifk', 98);

        if (isset($r['tgl']) && $r['tgl'] != '') {
            $data = $data->whereRaw("to_char(sk.tglkirim,'yyyy-MM-dd') = '$r[tgl]'");
        }
        if (isset($r['ruanganid']) && $r['ruanganid'] != '') {
            $data = $data->where('ru.id', '=', $r['ruanganid']);
        }

        $data = $data->orderBy('sk.nokirim', 'desc');
        $data = $data->get();
        $result = [];
        foreach ($data as $item) {
            $details2 = DB::select(
                DB::raw("
                    select  pr.namaproduk,
                    ss.satuanstandar,spd.qtyproduk,spd.objectprodukfk as produkfk
                    from kirimproduk_t as spd
                    left JOIN produk_m as pr on pr.id=spd.objectprodukfk
                    left JOIN satuanstandar_m as ss on ss.id=spd.objectsatuanstandarfk
                    where nokirimfk=:norec_sk and spd.qtyproduk <> 0"),
                array(
                    'norec_sk' => $item->norec_sk,
                )
            );
            $details = DB::select(
                DB::raw("
                    select  pd.nocmfk,pd.noregistrasi,ps.namapasien,ps.nocm,
                    kd.kategorydiet,jd.jenisdiet,ru.namaruangan
                  ,jw.jeniswaktu
                    from orderpelayanan_t as op
                     JOIN pasiendaftar_t as pd on pd.norec=op.noregistrasifk
                    left JOIN pasien_m as ps on ps.id=pd.nocmfk
                    left JOIN kategorydiet_m as kd on kd.id=op.objectkategorydietfk
                    left JOIN jenisdiet_m as jd on jd.id=op.objectjenisdietfk
                    left JOIN jeniswaktu_m as jw on jw.id=op.objectjeniswaktufk
                     left JOIN ruangan_m as ru on ru.id=op.objectruanganfk

                    where op.strukkirimfk=:norec_sk "),
                array(
                    'norec_sk' => $item->norec_sk,
                )
            );

            $result[] = array(
                'norec_sk' => $item->norec_sk,
                'nokirim' => $item->nokirim,
                'pegawaikirim' => $item->pegawaikirim,
                'ruangantujuan' => $item->ruangantujuan,
                'tglkirim' => $item->tglkirim,
                'ruanganasal' => $item->ruanganasal,
                'objectruangantujuanfk' => $item->objectruangantujuanfk,
                'objectruanganasalfk' => $item->objectruanganasalfk,
                'objectpegawaipengirimfk' => $item->objectpegawaipengirimfk,
                'noregistrasi' => $item->noregistrasi,
                'nocm' => $item->nocm,
                'namapasien' => $item->namapasien,
                'keterangan' => $item->keteranganlainnyakirim,
                'qtyproduk' => $item->qtyproduk,
                'details' => $details,
                'details2' => $details2,
            );
        }

        $dataResult = array(
            'message' => '@epic',
            'data' => $result,

        );
        return $this->respond($dataResult);
    }
}
