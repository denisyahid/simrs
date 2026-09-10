<?php

namespace App\Http\Controllers\Darah;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Sysadmin\MasterDetailJenisProdukCtrl;
use App\Models\Master\AsalProduk;
use App\Models\Master\DetailJenisProduk;
use App\Models\Master\GolonganDarah;
use App\Models\Master\JenisDarah;
use App\Models\Master\KelompokProduk;
use App\Models\Master\Rekanan;
use App\Models\Master\Ruangan;
use App\Models\Transaksi\AntrianPasienDiperiksa;
use App\Models\Transaksi\OrderPelayanan;
use App\Models\Transaksi\HasilDarah;
use App\Models\Transaksi\HasilDarahDetail;
use App\Models\Transaksi\KartuStok;
use App\Models\Transaksi\PasienDaftar;
use App\Models\Transaksi\PelayananPasien;
use App\Models\Transaksi\PelayananPasienDetail;
use App\Models\Transaksi\PelayananPasienPetugas;
use App\Models\Transaksi\StokProdukDetail;
use App\Models\Transaksi\StrukOrder;
use App\Models\Transaksi\StrukResep;
use App\Models\Transaksi\StrukPelayanan;
use App\Models\Transaksi\StrukPelayananDetail;
use App\Traits\Valet;
use Egulias\EmailValidator\Validation\Exception\EmptyValidationList;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\App;


class BankDarahCtrl extends Controller
{
    use Valet;
    public function listPasienRegis(Request $r)
    {
        $data  = DB::table('pasiendaftar_t as pd')
            ->join('ruangan_m as ru', 'pd.objectruanganlastfk', '=', 'ru.id')
            ->leftjoin('pegawai_m as pg', 'pd.objectpegawaifk', '=', 'pg.id')
            ->leftjoin('pasien_m as ps', 'pd.nocmfk', '=', 'ps.id')
            ->join('kelas_m as kls', 'kls.id', '=', 'pd.objectkelasfk')
            ->leftjoin('jeniskelamin_m as jk', 'jk.id', '=', 'ps.objectjeniskelaminfk')
            ->select(
                'pd.norec as norec_pd',
                'pd.statusenabled',
                'pd.tglregistrasi',
                'ps.nocm',
                'pd.nocmfk',
                'pd.noregistrasi',
                'ru.namaruangan',
                'ps.namapasien',
                'pg.namalengkap as namadokter',
                'pd.tglpulang',
                'pd.statuspasien',
                'pd.objectpegawaifk as pgid',
                'pd.objectruanganlastfk',
                'pd.nostruklastfk',
                'kls.namakelas',
                'ps.tgllahir',
                'ru.objectdepartemenfk',
                'pd.objectkelasfk',
                'ps.nobpjs',
                'jk.jeniskelamin',
                // 'apd.norec as norec_apd',
                'ps.noidentitas',
                DB::raw("CAST(pd.tglregistrasi
                AS DATE),
                (case when pd.ispelayananpasien=true then 'Selesai' else 'Menunggu Pelayanan' end) as statuspelayanan,
                ps.objectjeniskelaminfk")
            )

            ->where('pd.kdprofile', $this->kdProfile)
            ->where('pd.statusenabled', true);


        if (isset($r['ruanganid']) && $r['ruanganid'] != '') {
            $data = $data->where('ru.id', '=',  $r['ruanganid']);
        }

        if (isset($r['noregistrasi']) && $r['noregistrasi'] != '') {
            $data = $data->where('pd.noregistrasi', '=',  $r['noregistrasi']);
        }
        if (isset($r['nocm']) && $r['nocm'] != '') {
            $data = $data->where('ps.nocm', '=',  $r['nocm']);
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
        if (isset($r['namapasien']) && $r['namapasien'] != '') {
            $data = $data->where('ps.namapasien', 'ilike', '%' . $r['namapasien'] . '%');
        }
        if (isset($r['dari']) && $r['dari'] != '') {
            $data = $data->where(DB::raw("pd.tglregistrasi::date"), '>=', $r->dari);
        }
        if (isset($r['sampai']) && $r['sampai'] != '') {
            $data = $data->where(DB::raw("pd.tglregistrasi::date"), '<=', $r->sampai);
        }
        if (isset($r['status']) && $r['status'] != '') {
            $data = $data->where('pd.ispelayananpasien', '=', $r['status']);
        }
        $total = $data->count();
        if (isset($r['limit']) && $r['limit'] != '') {
            $data = $data->limit($r['limit']);
        }
        if (isset($r['offset']) && $r['offset'] != '') {
            $data = $data->offset($r['offset']);
        }
        $data = $data->orderBy('pd.tglregistrasi');
        $data = $data->get();
        foreach ($data as $d) {
            $d->umur =  $this->getAgeYear($d->tgllahir, $d->tglregistrasi) . ' thn';
        }
        $res['total'] = $total;
        $res['data'] = $data;
        return $this->respond($res);
    }

    public function getOrderDarah(Request $request)
    {
        $dataOrder = DB::table('strukorder_t as so')
            ->join('pasiendaftar_t as pd', 'pd.norec', 'so.noregistrasifk')
            ->leftJoin('jenisoperasi_m as jp', 'jp.id', 'so.jenisoperasifk')
            ->leftJoin('pasien_m as pas', 'pd.nocmfk', 'pas.id')
            ->leftjoin('ruangan_m as ruAs', 'so.objectruanganfk', 'ruAs.id')
            ->leftJoin('ruangan_m as ruTu', 'so.objectruangantujuanfk', 'ruTu.id')
            ->leftJoin('pegawai_m as peg', 'peg.id', 'so.objectpegawaiorderfk')
            ->leftJoin('jeniskelamin_m as jk', 'jk.id', 'pas.objectjeniskelaminfk')
            ->leftJoin('kelompokpasien_m as kp', 'kp.id', 'pd.objectkelompokpasienlastfk')
            ->leftJoin('departemen_m as dep', 'dep.id', 'ruAs.objectdepartemenfk')
            ->leftJoin('departemen_m as dep2', 'dep2.id', 'ruTu.objectdepartemenfk')
            ->leftJoin('kelas_m as kls', 'kls.id', 'pd.objectkelasfk')
            ->leftjoin('pemakaianasuransi_t as pa', 'pa.noregistrasifk', '=', 'pd.norec')
            ->leftJoin('detailjenisproduk_m as g', 'g.id', '=', 'so.golongandarahfk')
            ->select(
                'so.norec',
                'pd.noregistrasi',
                'pd.norec as pd_norec',
                'so.noorder',
                'so.statusorder',
                'so.norec_apd',
                'jp.jenisoperasi',
                'pd.jenispelayanan as jenispelayananfk',
                'pd.tglregistrasi',
                'pas.namapasien',
                'pas.tgllahir',
                'pas.nocm',
                'pd.nocmfk',
                'pas.objectjeniskelaminfk',
                'so.tglorder',
                'pas.noidentitas',
                'jk.jeniskelamin',
                'kp.kelompokpasien',
                'kls.namakelas',
                'pd.objectkelasfk',
                'pd.objectrekananfk',
                'dep.namadepartemen as asldepartemen',
                'ruAs.namaruangan as asalruangan',
                'ruTu.namaruangan as ruangantujuan',
                'so.objectruangantujuanfk',
                'so.objectpegawaiorderfk',
                'so.cito',
                'dep2.namadepartemen as departementujuan',
                'peg.namalengkap',
                'pas.noidentitas',
                'pas.nobpjs',
                'pa.nosep',
                'pd.objectkelasfk',
                'g.detailjenisproduk AS golongandarah',
                'g.id as golongandarahfk',
                'so.keteranganlainnya as keterangan',
                'so.kodekantongdarah as kodekantongdarah'
            )
            ->where('so.kdprofile', $this->kdProfile)
            ->whereIn('ruTu.objectdepartemenfk', explode(',', $this->settingFix('idDepartemenBankDarah')))
            ->whereIn('ruTu.id', explode(',', $this->settingFix('ruanganBankDarah')))
            ->where('so.statusenabled', true);

        if (isset($request['statusorder']) && $request['statusorder'] != '') {
            $dataOrder = $dataOrder->where('so.statusorder', '=', $request['statusorder']);
        }
        if (isset($request['search']) && $request['search'] != '') {
            $searchTerm = '%' . $request['search'] . '%';
            $dataOrder = $dataOrder->where(function ($query) use ($searchTerm) {
                $query->where('pas.namapasien', 'ilike', $searchTerm)
                    ->orWhere('pd.noregistrasi', 'ilike', $searchTerm)
                    ->orWhere('pas.nocm', 'ilike', $searchTerm)
                    ->orWhere('pas.nobpjs', 'ilike', $searchTerm)
                    ->orWhere('so.noorder', 'ilike', $searchTerm)
                    ->orWhere('pas.noidentitas', 'ilike', $searchTerm);
            });
        }
        if (isset($request['ruanganid']) && $request['ruanganid'] != '') {
            $dataOrder = $dataOrder->where('ruTu.id', '=',  $request['ruanganid']);
        }
        if (isset($request['noorder']) && $request['noorder'] != '') {
            $dataOrder = $dataOrder->where('so.noorder', '=', $request['noorder']);
        }
        if (isset($request['dari']) && $request['dari'] != '') {
            $dataOrder = $dataOrder->where(DB::raw("so.tglorder::date"), '>=', $request->dari);
        }
        if (isset($request['sampai']) && $request['sampai'] != '') {
            $dataOrder = $dataOrder->where(DB::raw("so.tglorder::date"), '<=', $request->sampai);
        }
        if (isset($request['qnamapasien']) && $request['qnamapasien'] != '') {
            $dataOrder = $dataOrder->where('pas.namapasien', '=', $request['qnamapasien']);
        }
        if (isset($request['qnoregistrasi']) && $request['qnoregistrasi'] != '') {
            $dataOrder = $dataOrder->where('pd.noregistrasi', '=', $request['qnoregistrasi']);
        }
        if (isset($request['qnocm']) && $request['qnocm'] != '') {
            $dataOrder = $dataOrder->where('pas.nocm', '=', $request['qnocm']);
        }

        $total = $dataOrder->count();
        if (isset($request['limit']) && $request['limit'] != '') {
            $dataOrder = $dataOrder->limit($request['limit']);
        }
        if (isset($request['offset']) && $request['offset'] != '') {
            $dataOrder = $dataOrder->offset($request['offset']);
        }
        $dataOrder = $dataOrder->get();

        $result = [];
        foreach ($dataOrder as $datas) {
            $result[] = [
                'namapasien' => $datas->namapasien,
                'noidentitas' => $datas->noidentitas,
                'noregistrasi' => $datas->noregistrasi,
                'so_norec' => $datas->norec,
                'pd_norec' => $datas->pd_norec,
                'nocmfk' => $datas->nocmfk,
                'iscito' => $datas->cito,
                'jenispelayananfk' => $datas->jenispelayananfk,
                'noorder' => $datas->noorder,
                'tglregistrasi' => $datas->tglregistrasi,
                'nocm' => $datas->nocm,
                'statusorder' => $datas->statusorder,
                'norec_apd' => $datas->norec_apd,
                'jeniskelamin' => $datas->jeniskelamin,
                'id_jenisK' => $datas->objectjeniskelaminfk,
                'kelompokpasien' => $datas->kelompokpasien,
                'namakelas' => $datas->namakelas,
                'objectrekananfk' => $datas->objectrekananfk,
                'objectkelasfk' => $datas->objectkelasfk,
                'asal_departemen' => $datas->asldepartemen,
                'asal_ruangan' => $datas->asalruangan,
                'ruangantujuan' => $datas->ruangantujuan,
                'objectruangantujuanfk' => $datas->objectruangantujuanfk,
                'departementujuan' => $datas->departementujuan,
                'nama_pegawai' => $datas->namalengkap,
                'tglorder' => $datas->tglorder,
                'objectpegawaiorderfk' => $datas->objectpegawaiorderfk,
                'umur' => $this->getAge($datas->tgllahir, $datas->tglorder),
                'nosep' => $datas->nosep,
                'objectkelasfk' => $datas->objectkelasfk,
                'golongandarah' => $datas->golongandarah,
                'golongandarahfk' => $datas->golongandarahfk,
                'keterangan' => $datas->keterangan,
                'kodekantongdarah' => $datas->kodekantongdarah,
                // 'detailDiagnosa' => $detail
            ];
        }
        $res['data'] = $result;
        $res['total'] = $total;
        return $this->respond($res);
    }
    public function getPelayanaDarah(Request $request)
    {
        $datas = DB::table('mapruangantoproduk_m as mpr')
            ->join('harganettoprodukbykelas_m as hnp', function ($join) {
                $join->on('hnp.objectprodukfk', 'mpr.objectprodukfk')
                    ->where('hnp.statusenabled', true);
            })
            ->join('produk_m as prd', 'prd.id', '=', 'mpr.objectprodukfk')
            ->join ('kelas_m as kls','kls.id','hnp.objectkelasfk')
            ->join('ruangan_m as ru', 'ru.id', '=', 'mpr.objectruanganfk')
            ->select(
                'mpr.id',
                'prd.id as idpro',
                'prd.namaproduk',
                'hnp.hargasatuan',
                'ru.namaruangan',
                'mpr.objectprodukfk',
                'mpr.objectruanganfk'
            )
            ->where('mpr.kdprofile', $this->kdProfile)
            ->where('hnp.objectkelasfk', $request['idkelas'])
            ->where('hnp.objectjenispelayananfk', $request['idjenispelayanan'])
            ->whereIN('ru.objectdepartemenfk', explode(',', $this->settingFix('idDepartemenBankDarah')))
            ->where('mpr.statusenabled', true);

        if ($request->idProduk) {
            $datas = $datas->where('mpr.objectprodukfk', $request['idProduk']);
        }
        $datas = $datas->get();

        return $this->respond($datas);
    }
    public function savePelayananPasienDarah(Request $request)
    {
        $parameter = $request['parameter'];
        $dataOrder = $request['data'];
        DB::beginTransaction();
        $apd = AntrianPasienDiperiksa::where('noregistrasifk', $parameter['pd_norec'])
            ->where('kdprofile', $this->kdProfile)
            ->where('objectruanganfk', $parameter['idruangtujuan'])
            ->where('statusenabled', true)
            ->first();
        $getLastAntrian = AntrianPasienDiperiksa::where('objectruanganfk', $parameter['idruangtujuan'])
            ->where('tglregistrasi', '>=', date('Y-m-d', strtotime($parameter['tglregistrasi'])) . ' 00:00')
            ->where('tglregistrasi', '<=', date('Y-m-d', strtotime($parameter['tglregistrasi'])) . ' 23:59')
            ->where('statusenabled', true)
            ->max('noantrian');
        if (!$apd) {

            $dataAPD = new AntrianPasienDiperiksa();
            $dataAPD->norec = $dataAPD->generateNewId();
            $dataAPD->kdprofile = $this->kdProfile;;
            $dataAPD->objectasalrujukanfk = 1;
            $dataAPD->statusenabled = true;
            $dataAPD->objectkelasfk = $parameter['objectkelasfk'];
            $dataAPD->noantrian = $getLastAntrian + 1;
            $dataAPD->noregistrasifk = $parameter['pd_norec'];
            $dataAPD->objectpegawaifk = $parameter['objectpegawaiorderfk'];
            $dataAPD->objectruanganfk = $parameter['idruangtujuan'];
            $dataAPD->statusantrian = 0;
            $dataAPD->statuspasien = 1;
            $dataAPD->status = "Belum Dipanggil";
            $dataAPD->objectstrukorderfk = $parameter['so_norec'];
            $dataAPD->tglregistrasi = $parameter['tglregistrasi'];
            $dataAPD->tglmasuk = date('Y-m-d H:i:s');
            $dataAPD->tglkeluar = date('Y-m-d H:i:s');
            $dataAPD->noregistrasi =  $parameter['noregistrasi'];
            $dataAPD->save();
            $dataAPDnorec = $dataAPD->norec;
            $dataAPDtglPel = $dataAPD->tglregistrasi;
        } else {
            $dataAPDnorec = $apd->norec;
            $dataAPDtglPel = $apd->tglregistrasi;
        }

        try {
            StrukOrder::where('norec', $parameter['so_norec'])
                ->where('kdprofile', $this->kdProfile)
                ->update(
                    [
                        'statusorder' => 1,
                        'norec_apd' => $dataAPDnorec,
                        'kodekantongdarah' => $parameter['kodekantongdarah'],
                    ]
                );

            foreach ($dataOrder as $data) {
                $dataPelayanan = new PelayananPasien();
                $dataPelayanan->norec = $dataPelayanan->generateNewId();
                $dataPelayanan->kdprofile = $this->kdProfile;
                $dataPelayanan->statusenabled = true;
                $dataPelayanan->noregistrasifk = $dataAPDnorec;
                $dataPelayanan->aturanpakai = '-';
                $dataPelayanan->hargadiscount = 0;
                // $dataPelayanan->hargajual = $data['hargaLayanan'];
                // $dataPelayanan->hargasatuan = $data['hargaLayanan'];
                $dataPelayanan->hargajual = 0;
                $dataPelayanan->hargasatuan = 0;
                $dataPelayanan->jumlah = $data['jumlah'];
                $dataPelayanan->kdkelompoktransaksi =  1;
                $dataPelayanan->piutangpenjamin = 0;
                $dataPelayanan->piutangrumahsakit = 0;
                $dataPelayanan->produkfk =  $data['idProduk'];
                $dataPelayanan->stock = 1;
                $dataPelayanan->strukorderfk =  $parameter['so_norec'];
                $dataPelayanan->tglpelayanan =  date('Y-m-d H:i:s');
                // $dataPelayanan->harganetto = $data['hargaLayanan'];
                $dataPelayanan->harganetto = 0;
                $dataPelayanan->noregistrasi =  $parameter['noregistrasi'];
                $dataPelayanan->save();


                foreach ($data['pelayananpetugas'] as $items) {
                    foreach ($items['listpegawai'] as $itemsPPP) {
                        $new_PPP = new PelayananPasienPetugas();
                        $new_PPP->norec = $new_PPP->generateNewId();
                        $new_PPP->kdprofile = $this->kdProfile;
                        $new_PPP->statusenabled = true;
                        $new_PPP->nomasukfk = $dataAPDnorec;
                        $new_PPP->objectjenispetugaspefk = $items['objectjenispetugaspefk'];
                        $new_PPP->objectpegawaifk = $itemsPPP['id'];
                        $new_PPP->pelayananpasien = $dataPelayanan->norec;
                        $new_PPP->noregistrasi =  $parameter['noregistrasi'];
                        $new_PPP->save();
                    }
                }
                $PPnorec = $dataPelayanan->norec;
                // if (!isset($data['komponenharga']) || !is_array($data['komponenharga']) || count($data['komponenharga']) === 0) {
                //     $result = [
                //         'message' => 'Komponen Harga masih belum disetting',
                //         'status' => 400
                //     ];
                //     return $this->respond($result, $result['status'], $result['message']);
                // }
                if(!empty($data['komponenharga'])){
                    foreach ($data['komponenharga'] as $itemKomponen) {
                        $PelPasienDetail = new PelayananPasienDetail();
                        $PelPasienDetail->norec = $PelPasienDetail->generateNewId();
                        $PelPasienDetail->kdprofile = $this->kdProfile;
                        $PelPasienDetail->statusenabled = true;
                        $PelPasienDetail->noregistrasifk = $dataAPDnorec;
                        $PelPasienDetail->aturanpakai = '-';
                        $PelPasienDetail->hargadiscount = 0;
                        $PelPasienDetail->hargajual = $itemKomponen['hargasatuan'];
                        $PelPasienDetail->hargasatuan = $itemKomponen['hargasatuan'];
                        $PelPasienDetail->jumlah = 1;
                        $PelPasienDetail->keteranganlain = '-';
                        $PelPasienDetail->keteranganpakai2 = '-';
                        $PelPasienDetail->komponenhargafk = $itemKomponen['objectkomponenhargafk'];
                        $PelPasienDetail->pelayananpasien = $PPnorec;
                        $PelPasienDetail->piutangpenjamin = 0;
                        $PelPasienDetail->piutangrumahsakit = 0;
                        $PelPasienDetail->produkfk =  $itemKomponen['objectprodukfk'];
                        $PelPasienDetail->stock = 1;
                        $PelPasienDetail->strukorderfk =  $parameter['so_norec'];
                        $PelPasienDetail->tglpelayanan = $dataAPDtglPel;
                        // $PelPasienDetail->harganetto = $itemKomponen['hargasatuan'];
                        $PelPasienDetail->harganetto = 0;
                        $PelPasienDetail->noregistrasi =  $parameter['noregistrasi'];
                        $PelPasienDetail->save();
                    }
                }
            }

            DB::commit();
            $result = [
                'message' => 'Data Berhasil disimpan',
                'pelPasien' => $dataPelayanan,
                // 'detailPelayanan' => $PelPasienDetail ? $PelPasienDetail : null,
                'status' => 201
            ];
        } catch (Exception  $e) {
            DB::rollBack();

            $result = [
                'message' => 'Data Gagal Disimpan, Silakan Periksa Kembali Data',
                'status' => $e->getMessage(),
                'hint' => $e->getLine().$e->getMessage(),
                'status' => 400
            ];
        }
        return $this->respond($result, $result['status'], $result['message']);
    }
    public function getDarahVerif(Request $request)
    {

        $datas = DB::table('pelayananpasien_t as pp')
            ->join('produk_m as prd', 'prd.id', 'pp.produkfk')
            ->leftjoin('strukorder_t as so', 'so.norec', '=', 'pp.strukorderfk')
            ->leftjoin('pegawai_m as pe', 'pe.id', 'so.objectpegawaiorderfk')
            ->select(
                'pp.tglpelayanan',
                'pe.namalengkap',
                'pp.hargasatuan',
                'so.tglpelayananawal as tgloperasi',
                DB::raw("(pp.jumlah*pp.hargasatuan) as total"),
                'prd.namaproduk',
                'prd.id',
                'pp.jumlah',
                'so.norec as norec_so'
            )
            ->where('pp.strukorderfk', $request['norec_so'])
            ->where('pp.kdprofile', $this->kdProfile)
            ->where('pp.statusenabled', true)
            ->get();

        return $this->respond($datas);
    }
    // public function updateTindakanDarah(Request $request){
    //     $parameter=$request['data'];
        
            
    //     try {
    //         DB::beginTransaction();
            
    //         $paramsSO = StrukOrder::where('norec', $request['norec_so'])
    //             ->where('statusenabled', true)
    //             ->select('norec')
    //             ->first();

    //         if (!$paramsSO) {
    //             throw new Exception('StrukOrder not found');
    //         }

    //         $dataUpdate = [];
    //        if(is_array($parameter) || is_object($parameter)){
    //         foreach ($parameter as $param) {

    //             $updated = DB::table('pelayananpasien_t')
    //                 ->where('statusenabled', true)
    //                 ->where('strukorderfk', $request['norec_so'])
    //                 ->where('produkfk', $param['idProduk'])
    //                 ->update([
    //                     'jumlah' => $param['jumlah'],
    //                     'statusenabled' => $param['jumlah'] === 0 ? false : true,
    //                 ]);

    //             if ($updated) {
    //                 $dataUpdate[] = $param; 
    //             }
    //         }
    //        }
           
    //         if (!empty($dataUpdate)) {
    //             DB::commit();
    //             $result = [
    //                 'status' => 200,
    //                 'message' => 'Update Success',
    //                 'data' => $dataUpdate
    //             ];
    //         } else {
    //             DB::rollBack();
    //             $result = [
    //                 'status' => 400,
    //                 'hint' => 'No data updated'
    //             ];
    //         }
    //     } catch (Exception $e) {
    //         DB::rollBack();
    //         $result = [
    //             'status' => 400,
    //             'hint' => $e->getMessage() . ' at line ' . $e->getLine()
    //         ];
    //     }

    //     return $this->respond($result);
        
    // //    try {
    // //         DB::beginTransaction();
    // //         $paramsSO=StrukOrder::where('norec',$request['norec_so'])->where('statusenabled',true)->select('norec')->first();
    // //         // $paramsPP=PelayananPasien::where('struorderfk',$paramsSO->norec)->where('statusenabled',true)->select('jumlah')->get();
    // //        if(is_array($parameter) || is_object($parameter)){
    // //         foreach ($parameter as $param) {
    // //             DB::table('pelayananpasien_t')
    // //                 ->where('statusenabled', true)
    // //                 ->where('pp.strukorderfk', $paramsSO->norec) 
    // //                 ->where('pp.produkfk', $param->idProduk) 
    // //                 ->update([
    // //                     'jumlah' => $param['jumlah'],
    // //                     'statusenabled' => $param['jumlah'] === 0 ? false : true,
    // //                 ]);
    // //         }
    // //        }
    // //         if(!empty($dataUpdate)){
    // //             DB::commit();
    // //         }
    // //         else{
    // //             DB::rollBack();
    // //             $result=array(
    // //                 'status'=> 400,
    // //                 'hint' => 'Data Not found'
    // //             );
    // //             return $this->respond($result);
    // //         }
    // //    } catch (Exception $e) {
    // //         DB::rollBack();
    // //         $result=array(
    // //             'status'=> 400,
    // //             'hint' => $e->getMessage().$e->getLine()
    // //         );
    // //         return $this->respond($result);
    // //    }
    // //     $result=array(
    // //         'status'=>200,
    // //         'message'=>'Update Success',
    // //         'data'=>$dataUpdate
    // //     );
    // //     return $this->respond($result);
    // }
    public function updateTindakanDarah(Request $request)
    {
        $parameter = $request->input('data');
        $norec_so = $request->input('norec_so');
    
        try {
            // Start transaction
            DB::beginTransaction();
    
            // Validate the existence of the related StrukOrder
            $paramsSO = StrukOrder::where('norec', $norec_so)
                ->where('statusenabled', true)
                ->select('norec')
                ->first();
    
            if (!$paramsSO) {
                throw new Exception('StrukOrder not found');
            }
    
            // Initialize array to track updated data
            $dataUpdate = [];
    
            
            foreach ($parameter as $param) {
                // Validate input structure
                if (!isset($param['idProduk']) || !isset($param['jumlah'])) {
                    throw new Exception('Invalid parameter structure: ' . json_encode($param));
                }

                // Cast 'jumlah' to integer
                $jumlah = (int) $param['jumlah'];

                // Update the data in the table
                $updated = DB::table('pelayananpasien_t')
                    ->where('statusenabled', true)
                    ->where('strukorderfk', $norec_so)
                    ->where('produkfk', $param['idProduk'])
                    ->update([
                        'jumlah' => $jumlah,
                        'statusenabled' => $jumlah === 0 ? false : true,
                    ]);

                if ($updated > 0) {
                    $dataUpdate[] = $param; // Track successful updates
                }
            }
    
            // Commit transaction if updates are successful
            if (!empty($dataUpdate)) {
                DB::commit();
                $result = [
                    'status' => 200,
                    'message' => 'Update Success',
                    'data' => $dataUpdate,
                ];
            } else {
                DB::rollBack();
                $result = [
                    'status' => 400,
                    'hint' => 'No data updated. Check input or database records.',
                ];
            }
        } catch (Exception $e) {
            // Rollback transaction and return error
            DB::rollBack();
            $result = [
                'status' => 400,
                'hint' => $e->getMessage() . ' at line ' . $e->getLine(),
            ];
        }
    
        return $this->respond($result);
    }
    
    public function getPenunjangPasien(Request $request)
    {
        $idProfile = $this->kdProfile;
        $dateBetween = [$request->tglAwal, $request->tglAkhir];
        $data = DB::table('antrianpasiendiperiksa_t as apd')
            ->leftJoin('pasiendaftar_t as pd', 'pd.norec', '=', 'apd.noregistrasifk')
            ->leftJoin('ruangan_m as ru', 'ru.id', '=', 'apd.objectruanganfk')
            ->leftJoin('departemen_m as dept', 'dept.id', '=', 'ru.objectdepartemenfk')
            ->leftJoin('pasien_m as ps', 'ps.id', '=', 'pd.nocmfk')
            ->leftJoin('jeniskelamin_m as jk', 'jk.id', '=', 'ps.objectjeniskelaminfk')
            ->leftJoin('kelompokpasien_m as kp', 'kp.id', '=', 'pd.objectkelompokpasienlastfk')
            ->leftJoin('rekanan_m as rk', 'rk.id', '=', 'pd.objectrekananfk')
            ->leftJoin('kelas_m as kl', 'kl.id', '=', 'pd.objectkelasfk')
            ->leftJoin('strukpelayanan_t as sp', 'sp.norec', '=', 'pd.nostruklastfk')
            ->leftJoin('alamat_m as alm', 'alm.nocmfk', '=', 'ps.id')
            ->leftJoin('golongandarah_m as gol', 'gol.id', '=', 'ps.objectgolongandarahfk')
            ->leftjoin('strukorder_t as so', 'so.norec', '=', 'apd.objectstrukorderfk')
            ->leftJoin('ruangan_m as ru1', 'ru1.id', '=', 'so.objectruanganfk')
            ->select(
                'apd.norec as norec_apd',
                'ru.id as ruid',
                'ru.namaruangan',
                'ru.objectdepartemenfk',
                'pd.noregistrasi',
                'ps.nocm',
                'ps.nobpjs',
                'ps.noidentitas',
                'pd.nocmfk',
                'ps.namapasien',
                'jk.jeniskelamin',
                'ps.objectjeniskelaminfk',
                'ps.objectgolongandarahfk',
                'kp.kelompokpasien',
                'rk.namarekanan',
                'kl.namakelas',
                'kl.id as klid',
                'pd.tglregistrasi',
                'pd.tglpulang',
                'ps.tgllahir',
                'apd.norec',
                'pd.norec as norec_pd',
                'sp.tglstruk',
                'pd.nostruklastfk',
                'alm.alamatlengkap',
                'gol.golongandarah',
                'apd.tglmasuk',
                'ru1.namaruangan as ruanganasal',
                DB::raw(
                    "'' AS expertise,so.catatanklinis,'' as kddiagnosa,
                CASE WHEN sp.nosbmlastfk IS NULL THEN 'Belum Bayar' ELSE 'Lunas' END AS status
                "
                )
            )
            ->where('apd.kdprofile', $idProfile)
            ->where('apd.statusenabled', '=', 'true')
            ->whereBetween(DB::raw("CAST(apd.updated_at as DATE)"), $dateBetween)
            ->whereIn('ru.objectdepartemenfk', explode(',', $this->settingFix('idDepartemenBankDarah')))
            ->orderBy('apd.tglregistrasi', 'desc');

        if (isset($request['ruanganid']) && $request['ruanganid'] != '') {
            $data = $data->where('ru.id', '=',  $request['ruanganid']);
        }
        if (isset($request['qnama']) && $request['qnama'] != '') {
            $data = $data->where('ps.namapasien', 'ilike', '%' . $request['qnama'] . '%');
        }
        $total = $data->count();
        if (isset($request['limit']) && $request['limit'] != '') {
            $data = $data->limit($request['limit']);
        }
        if (isset($request['offset']) && $request['offset'] != '') {
            $data = $data->offset($request['offset']);
        }
        if (isset($request['search']) && $request['search'] != '') {
            $searchTerm = '%' . $request['search'] . '%';
            $data = $data->where(function ($query) use ($searchTerm) {
                $query->where('ps.namapasien', 'ilike', $searchTerm)
                    ->orWhere('pd.noregistrasi', 'ilike', $searchTerm)
                    ->orWhere('ps.nocm', 'ilike', $searchTerm)
                    ->orWhere('ps.nobpjs', 'ilike', $searchTerm)
                    ->orWhere('ps.noidentitas', 'ilike', $searchTerm);
            });
        }
        if (isset($request['qnocm']) && $request['qnocm'] != '') {
            $data = $data->where('ps.nocm', 'ilike', '%' . $request['qnocm'] . '%');
        }
        if (isset($request['qnoregistrasi']) && $request['qnoregistrasi'] != '') {
            $data = $data->where('pd.noregistrasi', 'ilike', '%' . $request['qnoregistrasi'] . '%');
        }

        $data = $data->get();
        $apdnorec = [];
        foreach ($data as $key => $v) {
            $norecapd = $v->norec_apd;
            $apdnorec[] = $v->norec_apd;
        }
        $hasilLab = DB::table('pelayananpasien_t as pp')
            ->join('hasillaboratorium_t as hh', 'pp.norec', '=', 'hh.norecpelayanan')
            ->distinct()
            ->select('hh.tglhasil as tanggal', 'pp.noregistrasifk as norec_apd')
            ->whereIn('pp.noregistrasifk', $apdnorec)
            ->orderBy('hh.tglhasil', 'desc')
            ->get();
        $i = 0;
        foreach ($data as $key => $v) {
            $data[$i]->expertise = false;
            $data[$i]->tglexpertise = null;
            foreach ($hasilLab as $key2 => $v2) {
                if ($data[$i]->norec_apd ==  $v2->norec_apd) {
                    $data[$i]->expertise = true;
                    $data[$i]->tglexpertise = $v2->tanggal;
                }
            }
            $i = $i + 1;
        }
        $result = array(
            "data" => $data,
            "as" => '@epic',
            "total" => $total
        );
        return $this->respond($result);
    }
    public function LayananLab(Request $r)
    {
        $kdProfile = (int)$this->kdProfile;
        $sDokterPemeriksa = $this->settingFix('jenisPetugasDokterPemeriksa');
        $pd = PasienDaftar::where('kdprofile', $kdProfile)
            ->where('norec', $r['norec_pd'])
            ->first();
        $data = DB::table('pelayananpasien_t as pp')
            ->join('antrianpasiendiperiksa_t as apd', 'apd.norec', '=', 'pp.noregistrasifk')
            ->join('kelas_m as kls', 'kls.id', '=', 'apd.objectkelasfk')
            ->join('produk_m as prd', 'prd.id', '=', 'pp.produkfk')
            ->join('ruangan_m as ru', 'ru.id', '=', 'apd.objectruanganfk')
            ->leftJOIN('strukpelayanan_t as sp', 'sp.norec', '=', 'pp.strukfk')
            ->leftJOIN('strukresep_t as sr', 'sr.norec', '=', 'pp.strukresepfk')
            ->leftJOIN('pegawai_m as pg', 'pg.id', '=', 'sr.penulisresepfk')
            ->leftJOIN('strukorder_t as so', 'so.norec', '=', 'pp.strukorderfk')
            ->leftJOIN('order_lab as lis', function ($join) {
                $join->on('lis.no_lab', '=', 'so.noorder');
                $join->on(DB::raw('lis.kode_test::int'), '=', 'pp.produkfk');
            })
            ->select(
                'pp.norec',
                'apd.norec as norec_apd',
                'prd.namaproduk',
                'kls.namakelas',
                'pp.tglpelayanan',
                'ru.namaruangan',
                'pp.strukresepfk',
                'pp.jumlah',
                'pp.hargasatuan',
                'pg.namalengkap',
                'apd.norec as norec_apd',
                'so.noorder',
                'so.keteranganlainnya',
                'pp.strukfk',
                'pp.produkfk',
                'ru.objectdepartemenfk',
                'lis.no_lab as idbridging',
                'so.namafile',
                'so.norec as norec_so',
                DB::raw("
                case when pp.jasa is not null then pp.jasa else 0 end jasa,
                case when pp.hargadiscount is not null then pp.hargadiscount else 0 end hargadiscount,
                (
                    (pp.hargasatuan  - case when pp.hargadiscount is null then 0 else pp.hargadiscount end)
                     * pp.jumlah)
                + (case when pp.jasa is not null then pp.jasa else 0 end)
                 as total,
                to_char(pp.tglpelayanan,'yyyy-MM-dd')  as tglpelayanan_group ,
                case when pp.strukresepfk is null then 'Layanan' else 'Resep' end as jenis
               ")
            )
            ->where('pp.statusenabled', true)
            ->whereNull('pp.strukresepfk')
            ->where('pp.kdprofile', $kdProfile)
            ->where('ru.objectdepartemenfk', $this->settingFix('idDepartemenBankDarah'))
            ->where('apd.noregistrasifk', $r['norec_pd']);
        if (isset($r['strukfk']) && $r['strukfk'] != '' && $r['strukfk'] == null) {
            $data = $data->whereNull('pp.strukfk');
        }
        $data = $data->orderByDesc('pp.tglpelayanan');
        $data = $data->get();

        $pelayananpetugas = DB::table('pelayananpasienpetugas_t as ptu')
            ->leftjoin('pegawai_m as pg', 'pg.id', '=', 'ptu.objectpegawaifk')
            ->select('ptu.pelayananpasien', 'pg.namalengkap')
            ->where('ptu.kdprofile', $kdProfile)
            ->where('ptu.objectjenispetugaspefk', $sDokterPemeriksa)
            ->where('ptu.noregistrasi', $pd->noregistrasi)
            ->get();

        $result['total'] = 0;
        $result['deposit'] = 0;
        $result['diskon'] = 0;
        $result['dibayar'] = 0;
        $result['sisa'] = 0;

        $sama = false;
        $group  = [];
        foreach ($data as $item) {
            $item->checked = false;
            $result['total']  = $result['total']  + (float) $item->total;
            $result['diskon']  = $result['diskon']  + (float) $item->hargadiscount;
            foreach ($pelayananpetugas as $itemd) {
                if ($itemd->pelayananpasien == $item->norec) {
                    $item->dokterpemeriksa = $itemd->namalengkap;
                }
            }
            $sama = false;
            $i = 0;
            foreach ($group as $itemg) {
                if ($item->tglpelayanan_group == $group[$i]['tglpelayanan_group']) {
                    $sama = true;
                }
                $i = $i + 1;
            }
            if ($sama == false) {
                $group[] = array(
                    'tglpelayanan_group' => $item->tglpelayanan_group,
                    'details' => []
                );
            }
        }
        foreach ($group as $k => $d) {
            foreach ($data as $d2) {
                if ($d['tglpelayanan_group'] == $d2->tglpelayanan_group) {
                    $group[$k]['details'][] = $d2;
                }
            }
        }


        $result['length'] = count($data);
        $result['detail'] = $group;
        $result['list_ruangan'] = AntrianPasienDiperiksa::listRuangan($pd->noregistrasi);
        $result['as'] = '@epic';

        return $this->respond($result);
    }

    public function getCombo(Request $request)
    {

        $result['golongandarah'] = GolonganDarah::mine()->get();
        // $result['jenisdarah'] = JenisDarah::mine()->get();
        $result['rekanan'] = Rekanan::mine()->get();
        $result['kelompokproduk'] = KelompokProduk::mine()->get();
        $result['sumberdana'] = AsalProduk::mine()->get();
        $result['jenisdarah'] = DetailJenisProduk::mine()->whereIn('objectdepartemenfk', explode(',',$this->settingFix('idDepartemenBankDarah')))->get();
        $result['ruangan'] = Ruangan::mine()->whereIn('objectdepartemenfk', explode(',',$this->settingFix('idDepartemenBankDarah')))->get();

        return $this->respond($result);
    }

    public function updateStokDarah(Request $request){
        $data=$request['data'];
        DB::beginTransaction();
        try{
            // pengambilan datanya untuk pengurangan
            $dataStok=DB::table('strukpelayanandetail_t')->where('nokantong',$data['nokantong'])->where('objectprodukfk',$data['prd_id'])->where('norec',$data['norec_spd'])->where('golongandarahfk',$data['goldarah'])->select(
                'norec',
                'nokantong',
                'qtyproduk',
                'golongandarahfk',
                'volume',
                'qtyprodukpermintaan',
                'volumepermintaan'
            )->orderBy('tglkadaluarsa','ASC')->first();

            if((int)$data['qtykantong'] > (int)$dataStok->qtyproduk || (int)$data['volumedarah'] > (int)$dataStok->volume){
                DB::rollBack();
                $response = [
                    'message' => 'Stok Darah / Volume Darah Melebihi, Stok yang ada di database',
                    'status' => 400,
                    'data' => null
                ];
                return $this->respond($response['data'], $response['status'], $response['message']);
            }
            
            //bagian perhitungan datanya
            
            $updateStok=DB::table('strukpelayanandetail_t')->where('norec',$dataStok->norec)->update([

                "qtyproduk"=>(int)$dataStok->qtyproduk - (int)$data['qtykantong'],
                "volume"=>(int) $dataStok->volume - (int) $data['volumedarah'],
                "alasanpengambilandarah"=>$data['alasan'],
                "qtyprodukpermintaan"=>(int)$data['qtykantong'] + (int)$dataStok->qtyprodukpermintaan,
                "volumepermintaan"=>(int)$data['volumedarah'] + (int)$dataStok->volumepermintaan
            ]);

            if(!empty($updateStok)){
                DB::commit();
                $response = [
                    'message' => 'Sukses update data',
                    'status' => 200,
                    'data' => $updateStok
                ];
                return $this->respond($response['data'],$response['status'],$response['message']);
            }

        }
        catch(Exception $e){
            DB::rollBack();
            $response = [
                'message' => 'Simpan Gagal !',
                'status' => 400,
                'data' => $e->getMessage().$e->getLine()
            ];
        }
        return $this->respond($response['data'],$response['status'],$response['message']);
    }

    public function savePenerimaanDarah(Request $request)
    {

        DB::beginTransaction();
        try {
            $noStruk = "";
            if ($request['struk']['nostruk'] == '') {
                $SP = new StrukPelayanan();
                $norecSP = $SP->generateNewId();
                $noStruk = $this->SEQUENCE(new StrukPelayanan, 'nostruk', 13, 'RS/' . $this->getDateTime()->format('ym/'), $this->kdProfile);
                $SP->noterima = $noStruk;
                $SP->norec = $norecSP;
                $SP->kdprofile = $this->kdProfile;
                $SP->statusenabled = true;
                $SP->nostruk = $noStruk;

                $message = 'Data Berhasil Disimpan';
            } else {
                $message = 'Data Berhasil Diupdate';
                //##PENAMBAHAN KEMBALI STOKPRODUKDETAIL
                $dataKembaliStok = DB::select(
                    DB::raw("select spd.norec as norec_spd, sp.norec,spd.qtyproduk,spd.hasilkonversi,sp.objectruanganfk,spd.objectprodukfk,
                              sp.nostruk,pr.namaproduk
                                    from strukpelayanandetail_t as spd
                                    INNER JOIN strukpelayanan_t sp on sp.norec=spd.nostrukfk
                                    INNER JOIN produk_m as pr on pr.id = spd.objectprodukfk
                                    where sp.kdprofile = $this->kdProfile and sp.norec=:norec"),
                    array('norec' => $request['struk']['nostruk'])
                );

                $TambahStok = 0;
                $qtyJumlahSisa = 0;
                foreach ($dataKembaliStok as $item5) {
                    $qtyJumlahSisa = ((float)$item5->qtyproduk) * (float)$item5->hasilkonversi;
                    foreach ($request['details'] as $item) {
                        if ($item['produkDarahfk'] == $item5->objectprodukfk) {
                            $qtyJumlahSisa = ((float)$item5->qtyproduk - 0) * (float)$item5->hasilkonversi;
                        }
                    }
                    $TambahStok = (float)$item5->qtyproduk * (float)$item5->hasilkonversi;
                    $saldoAwal = 0;
                    $dataSaldoAwal = collect(DB::select("
                            select sum(qtyproduk) as qty from stokprodukdetail_t
                            where kdprofile = $this->kdProfile and objectprodukfk=$item5->objectprodukfk
                        "))->first();

                    $saldoKeun = (float)$dataSaldoAwal->qty - $qtyJumlahSisa; //$TambahStok;
                    $saldoAwal = (float)$dataSaldoAwal->qty;
                    if ($request['struk']['norecOrder'] != '') {
                        foreach ($request['details'] as $item) {
                            OrderPelayanan::where('noorderfk', $request['struk']['norecOrder'])
                                ->where('kdprofile', $this->kdProfile)
                                ->where('objectprodukfk', $item['produkfk'])
                                ->update(['qtyterimalast' => (float)$item['jumlah']]);
                        }
                    }

                    $tglnow =  date('Y-m-d H:i:s');
                    $tglUbah = date('Y-m-d H:i:s', strtotime('-10 seconds', strtotime($tglnow)));
                    $this->kartu_STOK(array(
                        "saldoawal" => $saldoAwal,
                        "qtyin" => 0,
                        "qtyout" => $saldoAwal,
                        "saldoakhir" => 0,
                        "keterangan" => 'Ubah Penerimaan No. ' . $item5->nostruk . ', pada produk ' . $item5->namaproduk,
                        "produkfk" => $item5->objectprodukfk,
                        "ruanganfk" => null,
                        "tglinput" => $tglUbah,
                        "tglkejadian" => $tglUbah,
                        "nostrukterimafk" => $request['struk']['nostruk'],
                        "norectransaksi" => $request['struk']['norecOrder'],
                        "stokprodukdetailfk" => $item5->norec_spd,
                        "tabletransaksi" => 'strukpelayanan_t',
                        "flagfk" => null,
                    ));

                    //END##PENAMBAHAN KEMBALI STOKPRODUKDETAIL
                    $SP = StrukPelayanan::where('norec', $request['struk']['nostruk'])->first();
                    // $noStruk = $SP->nostruk;
                    StokProdukDetail::where('nostrukterimafk', $request['struk']['nostruk'])
                        ->where('kdprofile', $this->kdProfile)
                        ->where('objectruanganfk', $item5->objectruanganfk)
                        ->where('objectprodukfk', $item5->objectprodukfk)
                        ->delete();
                    StrukPelayananDetail::where('nostrukfk', $request['struk']['nostruk'])
                        ->where('kdprofile', $this->kdProfile)
                        ->delete();
                }
            }

            $SP->objectkelompoktransaksifk = $this->kelompokTransaksi('PENERIMAAN BARANG DARAH'); // $request['struk']['kelompoktranskasi'];
            $SP->objectrekananfk = $request['struk']['rekananfk'];
            $SP->namarekanan = $request['struk']['namarekanan'];
            $SP->objectruanganfk = $request['struk']['ruanganfk'];
            $SP->keteranganlainnya = 'Penerimaan Barang Darah';
            $SP->objectkelompokprodukfk = $request['struk']['kelompokProduk'];
            $SP->tglstruk = date('Y-m-d H:i:s', strtotime($request['struk']['tglPengambilDarah']));
            $SP->objectpegawaipenerimafk = $request['struk']['pegawaimenerimafk'];
            $SP->namapegawaipenerima = $request['struk']['namapegawaipenerima'];
            $SP->qtyproduk = $request['struk']['qtyproduk'];
            $SP->tgldokumen = date('Y-m-d H:i:s');
            $SP->save();
            $qtyJumlah = 0;
            foreach ($request['details'] as $item) {
                $qtyJumlah = 0;
                $qtyJumlah = (float)$item['qtykantong'] * (float)$item['konversi'];
                $SPD = new StrukPelayananDetail();
                $norecKS = $SPD->generateNewId();
                $SPD->norec = $norecKS;
                $SPD->kdprofile = $this->kdProfile;
                $SPD->statusenabled = true;
                $SPD->nostrukfk = $SP->norec;
                $SPD->objectasalprodukfk = $request['struk']['asalproduk']; //$item['asalprodukfk'];
                $SPD->objectprodukfk = $item['produkDarahfk'];
                $SPD->objectruanganfk = $request['struk']['ruanganfk'];
                $SPD->objectruanganstokfk = $request['struk']['ruanganfk'];
                $SPD->objectsatuanstandarfk = $item['satuanfk'];
                $SPD->golongandarahfk = $item['golonganDarahfk'];
                $SPD->volume = $item['volumeDarah'];
                $SPD->nokantong = $item['nomerKantong'];
                $SPD->detailjenisprodukfk = $item['jenisDarahfk'];
                $SPD->hasilkonversi = $item['konversi'];
                $SPD->namaproduk = $item['produkDarah'];
                $SPD->hargasatuandijamin = 0;
                $SPD->hargasatuanppenjamin = 0;
                $SPD->hargatambahan = 0;
                $SPD->hargasatuanpprofile = 0;
                $SPD->isonsiteservice = 0;
                $SPD->kdpenjaminpasien = 0;
                $SPD->qtyproduk = $item['qtykantong'];
                $SPD->qtyprodukoutext = 0;
                $SPD->qtyprodukoutint = 0;
                $SPD->qtyprodukretur = 0;
                $SPD->satuan = '-';
                $SPD->satuanstandar = $item['satuan'];
                $SPD->hasilreleasedarah = $item['hasilrelease'];
                $SPD->catatandarah = $item['catatan'];
                $SPD->nopermintaan = $item['nopermintaan'];
                $SPD->pengiriman = $item['pengiriman'];
                $SPD->tujuan = $item['tujuan'];
                $SPD->objectsuhufk = $item['suhu'];
                $SPD->tglpelayanan = date('Y-m-d H:i:s', strtotime($request['struk']['tglPengambilDarah'])); //$request['struk']['tglstruk'];
                $SPD->is_terbayar = 0;
                $SPD->linetotal = 0;
                $SPD->tglkadaluarsa = $item['tglkadaluarsa'] ? date('Y-m-d H:i:s', strtotime($item['tglkadaluarsa'])) : null;
                $SPD->tglafteapdarah = $item['tglaftep'] ? date('Y-m-d H:i:s', strtotime($item['tglaftep'])) : null;
                $SPD->tglpengelolahandarah = $item['tglpenglolahan'] ? date('Y-m-d H:i:s', strtotime($item['tglpenglolahan'])) : null;
                $SPD->tglreleasedarah = $item['tglrelease'] ? date('Y-m-d H:i:s', strtotime($item['tglrelease'])) : null;
                $SPD->tglpengiriman = $item['tglpengiriman'] ? date('Y-m-d H:i:s', strtotime($item['tglpengiriman'])) : null;
                $SPD->save();

                $ruanganId = $request['struk']['ruanganfk'];
                $saldoAwalIns = 0;
                $dataSaldoAwal = collect(DB::select("
                        select sum(qtyproduk) as qty from stokprodukdetail_t
                        where kdprofile = $this->kdProfile
                        and objectruanganfk = $ruanganId
                        and objectprodukfk = $item[produkDarahfk]
                    "))->first();

                $dataSort = DB::table('stokprodukdetail_t')
                    ->select(DB::raw("norec,qtyproduk,sort"))
                    ->where('kdprofile', $this->kdProfile)
                    ->where('objectruanganfk', $ruanganId)
                    ->where('objectprodukfk', $item['produkDarahfk'])
                    ->get();


                foreach ($dataSort as $itm) {
                    if ($itm->qtyproduk <= 0) {
                        $norec = $itm->norec;
                    }
                }

                DB::table('stokprodukdetail_t')
                    ->where('kdprofile', $this->kdProfile)
                    ->where('objectruanganfk', $request['struk']['ruanganfk'])
                    ->where('objectprodukfk', $item['produkDarahfk'])
                    ->where('nostrukterimafk', $SPD->norec)
                    ->update(["sort" => null]);
                //## StokProdukDetail
                $StokPD = new StokProdukDetail();
                $norecStokPD = $StokPD->generateNewId();
                $StokPD->norec = $norecKS;
                $StokPD->kdprofile = $this->kdProfile;
                $StokPD->statusenabled = true;
                $StokPD->objectasalprodukfk = $request['struk']['asalproduk']; //$item['asalprodukfk'];
                $StokPD->hargadiscount = 0;
                $StokPD->golongandarahfk = $item['golonganDarahfk'];
                $StokPD->volume = $item['volumeDarah'];
                $StokPD->nokantong = $item['nomerKantong'];
                $StokPD->detailjenisprodukfk = $item['jenisDarahfk'];
                $StokPD->objectprodukfk = $item['produkDarahfk'];
                $StokPD->qtyproduk = $qtyJumlah; //$qtyJumlah;
                $StokPD->qtyprodukonhand = 0;
                $StokPD->qtyprodukoutext = 0;
                $StokPD->qtyprodukoutint = 0;
                $StokPD->objectruanganfk = $request['struk']['ruanganfk'];
                $StokPD->nostrukterimafk = $SP->norec;
                $StokPD->objectstrukpelayanandetail = $SPD->norec;
                $StokPD->tglkadaluarsa = $item['tglkadaluarsa'] ? date('Y-m-d H:i:s', strtotime($item['tglkadaluarsa'])) : null;
                $StokPD->tglpelayanan = date('Y-m-d H:i:s', strtotime($request['struk']['tglPengambilDarah'])); //$request['struk']['tglstruk'];
                $StokPD->sort = 1;
                $StokPD->save();

                //## KartuStok
                $this->kartu_STOK(array(
                    "saldoawal" => $dataSaldoAwal->qty,
                    "qtyin" => $qtyJumlah,
                    "qtyout" => 0,
                    "saldoakhir" => $dataSaldoAwal->qty + $qtyJumlah,
                    "keterangan" => 'Penerimaan Barang. Berupa produk ' .  $item['produkDarah'] . ', No Terima. ' . $noStruk . ', ' . $request['struk']['namarekanan'],
                    "produkfk" => $item['produkDarahfk'],
                    "ruanganfk" => null,
                    "tglinput" => date('Y-m-d H:i:s'),
                    "tglkejadian" => date('Y-m-d H:i:s'),
                    "nostrukterimafk" => $SP->norec,
                    "norectransaksi" => $SP->norec,
                    "tabletransaksi" => 'strukpelayanan_t',
                    "stokprodukdetailfk" => $SPD->norec,
                    "flagfk" => null,
                ));
            }


            $this->LOGGING(
                'Penerimaan Barang',
                $SP->norec,
                'strukpelayanan_t',
                "Penerimaan Barang Produk Darah Sebanyak $ - " . $request['struk']['qtyproduk'] .
                    " - no struk $SP->nostruk"
            );

            DB::commit();

            $response = [
                'message' => $message,
                'status' => 201,
                'datas' => [
                    'strukPelayanan' => $SP,
                    'strukPelayananDetail' => $SPD,
                    'stokPD' => $StokPD,
                ]
            ];
        } catch (Exception $e) {
            DB::rollBack();
            $response = [
                'message' => 'Simpan Gagal !',
                'status' => 400,
                'datas' => $e->getMessage().$e->getLine()
            ];
        }

        return $this->respond($response['datas'], $response['status'], $response['message']);
    }

    public function getDataProdukDetail(Request $request)
    {
        $dataProduk = DB::table('produk_m as pr')
            ->JOIN('detailjenisproduk_m as djp', 'djp.id', '=', 'pr.objectdetailjenisprodukfk')
            ->JOIN('jenisproduk_m as jp', 'jp.id', '=', 'djp.objectjenisprodukfk')
            ->JOIN('kelompokproduk_m as kp', 'kp.id', '=', 'jp.objectkelompokprodukfk')
            ->JOIN('satuanstandar_m as ss', 'ss.id', '=', 'pr.objectsatuanstandarfk')
            ->leftJOIN('stokprodukdetail_t as spd', 'spd.objectprodukfk', '=', 'pr.id')
            ->select('pr.id', 'pr.namaproduk', 'kp.id as kpid', 'kp.kelompokproduk', 'ss.id as ssid', 'ss.satuanstandar', 'pr.spesifikasi')
            ->where('pr.kdprofile', $this->kdProfile)
            ->whereIn('djp.objectdepartemenfk', explode(',',$this->settingFix('idDepartemenBankDarah')))
            ->where('pr.statusenabled', true)
            ->where('kp.id', $request['idkelompokproduk'])
            ->groupBy('pr.id', 'pr.namaproduk', 'ss.id', 'kpid', 'ss.satuanstandar', 'ssid', 'pr.spesifikasi')
            ->orderBy('pr.namaproduk')
            ->get();

        $dataKonversiProduk = DB::table('konversisatuan_t as ks')
            ->JOIN('satuanstandar_m as ss', 'ss.id', '=', 'ks.satuanstandar_asal')
            ->JOIN('satuanstandar_m as ss2', 'ss2.id', '=', 'ks.satuanstandar_tujuan')
            ->select(
                'ks.objekprodukfk',
                'ks.satuanstandar_asal',
                'ss.satuanstandar',
                'ks.satuanstandar_tujuan',
                'ss2.satuanstandar as satuanstandar2',
                'ks.nilaikonversi'
            )
            ->where('ks.kdprofile', $this->kdProfile)
            ->where('ks.statusenabled', true)
            ->get();
        $dataProdukResult = [];

        $suhu= DB::table('suhu_m')->where('statusenabled',true)
        ->whereIn('departemenfk',explode(',',$this->settingFix('idDepartemenBankDarah')))
        ->select(
            'id',
            'namasuhu'
        )
        ->get();

        foreach ($dataProduk as $item) {
            $satuanKonversi = [];
            foreach ($dataKonversiProduk as $item2) {
                if ($item->id == $item2->objekprodukfk) {
                    $satuanKonversi[] = array(
                        'ssid' => $item2->satuanstandar_tujuan,
                        'satuanstandar' => $item2->satuanstandar2,
                        'nilaikonversi' => $item2->nilaikonversi,
                    );
                }
            }

            $dataProdukResult[] = array(
                'id' => $item->id,
                'namaproduk' => $item->namaproduk,
                'satuanstandarfk' => $item->ssid,
                'satuanstandar' => $item->satuanstandar,
                'konversisatuan' => $satuanKonversi,
            );
        }

        $result = array(
            'produk' => $dataProdukResult,
            'suhu' => $suhu,
            'message' => 'as@epic',
        );

        return $this->respond($result);
    }

    public function getStokProduk(Request $request)
    {

        $data = DB::table('stokprodukdetail_t as spd')
            ->join('asalproduk_m as ap', 'ap.id', 'spd.objectasalprodukfk')
            ->selectRaw("
                        spd.objectprodukfk, spd.objectasalprodukfk,spd.nokantong,
                        spd.hargadiscount,sum(spd.qtyproduk) as qtyproduk,spd.objectruanganfk,ap.asalproduk,spd.nostrukterimafk,spd.tglkadaluarsa,
                        spd.norec as norec_spd,spd.golongandarahfk,spd.detailjenisprodukfk,spd.volume
                  ")
            ->where('spd.objectprodukfk', $request['produkfk'])
            ->where('spd.objectruanganfk', $request['ruanganfk'])
            ->groupBy(
                'spd.objectprodukfk',
                'spd.objectasalprodukfk',
                'spd.hargadiscount',
                'spd.objectruanganfk',
                'ap.asalproduk',
                'spd.nostrukterimafk',
                'spd.tglkadaluarsa',
                'spd.norec',
                'spd.nokantong',
                'spd.golongandarahfk',
                'spd.detailjenisprodukfk',
                'spd.volume'
            )
            ->get();


        return $this->respond($data);
    }

    public function  suratPernyataanUTd(Request $r)
    {
        $nocmfk = $r->noregistrasi;
        $profile = $this->profile();
        $kdProfile = (int)$this->kdProfile;
        $norecPd = $r->norec_pd;
        $res = $r;
        $pageWidth = 950;
        $dataReport = DB::connection('mongodb')
            ->table('orderDarah')
            ->where('registrasi.norec_pd', $norecPd)
            ->where('registrasi.noregistrasi', $nocmfk)
            ->first();
        $request['pdf'] = 'true';
        $blade = 'report.utd.suratPernyataan';
        if ($request['pdf'] == 'true') {
            $pdf = App::make('dompdf.wrapper');
            $pdf->setPaper('A4', 'portrait');
            $pdf->loadView(
                $blade,
                array(
                    'dataReport' =>  $dataReport,
                    'pageWidth' => $pageWidth,
                    'profile' => $profile,
                    'res'    => array(
                        'pdf' => true
                    ),
                )
            );
            return $pdf->stream();
        } else {
            return view(
                $blade,
                compact('dataReport', 'pageWidth', 'profile')
            );
        }
    }
    public function  suratPersetujuanUTd(Request $r)
    {
        $nocmfk = $r->noregistrasi;
        $profile = $this->profile();
        $kdProfile = (int)$this->kdProfile;
        $norecPd = $r->norec_pd;
        $res = $r;
        $data = $r;
        $pageWidth = 950;
        $request['pdf'] = 'true';
        $blade = 'report.utd.suratPersetujuan';
        $dataReport =   DB::table('pasiendaftar_t as pd')
            ->JOIN('pasien_m as ps', 'ps.id', '=', 'pd.nocmfk')
            ->leftJoin('antrianpasiendiperiksa_t as apd', 'apd.noregistrasifk', '=', 'pd.norec')
            ->leftJoin('ruangan_m as ru', 'ru.id', '=', 'apd.objectruanganfk')
            ->leftJoin('departemen_m as dp', 'dp.id', '=', 'ru.objectdepartemenfk')
            ->leftJoin('kelompokpasien_m as kp', 'kp.id', '=', 'pd.objectkelompokpasienlastfk')
            ->leftJoin('kelas_m as kl', 'kl.id', '=', 'pd.objectkelasfk')
            ->leftJoin('golongandarah_m as go', 'go.id', '=', 'ps.objectgolongandarahfk')
            ->select(
                'ps.namapasien',
                'ps.nocm',
                'ru.namaruangan',
                'go.golongandarah',
            )
            ->where('pd.kdprofile', (int)$this->kdProfile)
            ->where('pd.statusenabled', true)
            ->where('apd.statusenabled', true)
            ->where('pd.norec', $norecPd)
            ->first();
        if ($request['pdf'] == 'true') {
            $pdf = App::make('dompdf.wrapper');
            $pdf->setPaper('A4', 'portrait');
            $pdf->loadView(
                $blade,
                array(
                    'dataReport' =>  $dataReport,
                    'pageWidth' => $pageWidth,
                    'profile' => $profile,
                    'data' => $data,
                    'res'    => array(
                        'pdf' => true
                    ),
                )
            );
            return $pdf->stream();
        } else {
            return view(
                $blade,
                compact('dataReport', 'pageWidth', 'data', 'profile')
            );
        }
    }
    public function saveHasil(Request $request)
    {
        $data = HasilDarah::where('norec', $request->norec)->first();
        $kdProfile = $this->kdProfile;
        $hh = $request;
        try {
            DB::beginTransaction();
            if ($data) {
                $message = 'Update Berhasil';
                HasilDarah::where('norec', $request->norec)->update([
                    'keterangan' => $hh['keterangan'],
                    'pegawaifk' => $hh['pegawaifk'],
                    'dokterfk' => $hh['dokterfk'],
                    'tanggal' =>  date('Y-m-d H:i:s'),
                    'hasil' => $hh['hasil']
                ]);
                $h =  HasilDarah::where('norec', $request->norec)->first();
            } else {
                $message = 'Simpan Berhasil';
                $h = new HasilDarah();
                $h->norec = $h->generateNewId();
                $h->kdprofile = $kdProfile;
                $h->statusenabled = true;
                $h->tanggalreport = date('Y-m-d H:i:s');
                $h->pegawaifk = $hh['pegawaifk'];
                $h->dokterfk = $hh['dokterfk'];
                $h->hasil = $hh['hasil'];
                $h->keterangan = $hh['keterangan'];
                $h->pelayananpasienfk = $hh['pelayananpasienfk'];
                $h->noregistrasifk = $hh['noregistrasifk'];
                $h->save();
            }
            foreach ($request['details'] as $key => $data) {
                if (isset($data['norec'])) {
                    HasilDarahDetail::where('norec', $data['norec'])->update([
                        'tanggal' => date('Y-m-d H:i:s'),
                        'nomerkantong' => $data['nomerkantong'],
                        'jenisdarah' => $data['jenisdarah'],
                        'golongandarah' => $data['golongandarah'],
                        'pegawaifk' => isset($data['pegawaifk']) ?  $data['pegawaifk']['value'] : null,
                        'pengambil' => $data['pengambil'],
                        'volume' => $data['volume'],
                    ]);
                } else {
                    $d = new HasilDarahDetail();
                    $d->norec = $d->generateNewId();
                    $d->objecthasildarahfk = $h['norec'];
                    $d->tanggal = date('Y-m-d H:i');
                    $d->nomerkantong = $data['nomerkantong'];
                    $d->jenisdarah = $data['jenisdarah'];
                    $d->golongandarah = $data['golongandarah'];
                    $d->pegawaifk = isset($data['pegawaifk']) ?  $data['pegawaifk']['value'] : null;
                    $d->pengambil = $data['pengambil'] ?? null;
                    $d->volume = $data['volume'] ?? null;
                    $d->save();
                }
            }
            $result = $h;
            DB::commit();
            $result = array(
                "status" => 200,
                "result" => 'sukses',
                "message" => $message,
                'data' => $h
            );
        } catch (Exception $e) {
            DB::rollBack();
            $result = array(
                "status" => 400,
                "message" => "Simpan Gagal !",
                "result"  => $e->getMessage() . ' ' . $e->getLine()
            );
        }
        return $this->respond($result, $result['status'], $result['message']);
    }

    public function getDaftarPenerimaanDarah(Request $request)
    {
        $dateBetween = [$request->tglAwal, $request->tglAkhir];
        $data = DB::table('strukpelayanan_t as sp')
            ->JOIN('strukpelayanandetail_t as spd', 'spd.nostrukfk', '=', 'sp.norec')
            ->join('suhu_m as su','su.id','=','spd.objectsuhufk')
            ->join('asalproduk_m as ap', 'ap.id', 'spd.objectasalprodukfk')
            ->leftjoin('riwayatrealisasi_t as rr', 'rr.penerimaanfk', 'sp.norec')
            ->JOIN('rekanan_m as rkn', 'rkn.id', '=', 'sp.objectrekananfk')
            ->LEFTJOIN('pegawai_m as pg', 'pg.id', '=', 'sp.objectpegawaipenerimafk')
            ->JOIN('ruangan_m as ru', 'ru.id', '=', 'sp.objectruanganfk')
            ->LEFTJOIN('strukbuktipengeluaran_t as sbk', 'sbk.norec', '=', 'sp.nosbklastfk')
            ->select(
                DB::raw("
                sp.tglstruk, sp.nostruk,sp.noterima, sp.nostruk_intern,sp.keteranganlainnya,sp.namapengadaan,rkn.namarekanan,rkn.id as rekananfk,ru.id as ruanganfk,pg.namalengkap,
                pg.id as penerimafk,sp.objectruanganasalfk,sp.objectpegawaipenanggungjawabfk,sp.tglspk,sp.nokontrak,ap.asalproduk,ap.id as apid,sp.tgljatuhtempo,sp.tgldokumen,
                ru.namaruangan, sp.norec, sp.nofaktur,rr.noorderintern, sp.objectkelompokprodukfk , sp.tglfaktur,CAST(sp.totalharusdibayar AS FLOAT), sbk.nosbk,
                sp.nosppb, sp.totalppn,sp.totaldiscount,sp.totalhargasatuan, sp.noorderfk, sp.qtyproduk,su.namasuhu
            ")
            )
            ->where('sp.kdprofile', $this->kdProfile)
            ->where('sp.statusenabled', true)
            ->WhereBetween(DB::raw("CAST(sp.tglstruk as DATE)"), $dateBetween)
            // ->where('sp.objectkelompoktransaksifk', $this->kelompokTransaksi('PENERIMAAN BARANG SUPPLIER'))
            ->groupBy(
                'sp.tglstruk',
                'sp.objectkelompoktransaksifk',
                'sp.nostruk',
                'sp.noterima',
                'sp.nostruk_intern',
                'sp.keteranganlainnya',
                'sp.namapengadaan',
                'rkn.namarekanan',
                'rekananfk',
                'pg.namalengkap',
                'penerimafk',
                'sp.nokontrak',
                'sp.objectruanganasalfk',
                'sp.tglspk',
                'rr.noorderintern',
                'ru.namaruangan',
                'sp.objectkelompokprodukfk',
                'ruanganfk',
                'ap.asalproduk',
                'apid',
                'sp.norec',
                'sp.nofaktur',
                'sp.tglfaktur',
                'sp.tgljatuhtempo',
                'sp.totalharusdibayar',
                'sp.objectpegawaipenanggungjawabfk',
                'sbk.nosbk',
                'sp.nosppb',
                'sp.tgldokumen',
                'sp.noorderfk',
                'sp.qtyproduk',
                'sp.totalppn',
                'sp.totaldiscount',
                'sp.totalhargasatuan',
                'su.namasuhu'
            );
        if (isset($request['nodokumen']) && $request['nodokumen'] != "" && $request['nodokumen'] != "undefined") {
            $data = $data->where('sp.nostruk', $request['nostruk']);
        }
        // if (isset($request['nodokumen']) && $request['nodokumen'] != "" && $request['nodokumen'] != "undefined") {
        //     $data = $data->where('sp.nofaktur', $request['nodokumen']);
        // }
        if (isset($request['rekananfk']) && $request['rekananfk'] != "" && $request['rekananfk'] != "undefined") {
            $data = $data->where('rkn.id', $request['rekananfk']);
        }
        $total  = count($data->get());

        if (isset($request['limit']) && $request['limit'] != "" && $request['limit'] != "undefined") {
            $data = $data->limit($request['limit']);
        }
        if (isset($request['offset']) && $request['offset'] != "" && $request['offset'] != "undefined") {
            $data = $data->offset($request['offset']);
        }
        $data = $data->distinct();
        $data = $data->orderBy('sp.nostruk');
        $data = $data->get();

        foreach ($data as $item) {
            $details = DB::select(
                DB::raw("select  pr.namaproduk,pr.id as produkfk,ss.satuanstandar as satuan,ss.id as satuanfk,spd.qtyproduk as jumlah,spd.hasilkonversi,spd.qtyprodukretur,spd.hargasatuan,spd.hargadiscount as hargadiskon,
                    spd.hargappn as nilaippn,spd.persendiscount,spd.persenppn,CAST(((spd.qtyproduk*spd.hargasatuan)-(((spd.persendiscount*spd.hargasatuan)/100)*spd.qtyproduk))+(spd.persenppn*((spd.qtyproduk*spd.hargasatuan)-(((spd.persendiscount*spd.hargasatuan)/100)*spd.qtyproduk))/100) AS FLOAT) AS totalall,
                    spd.tglkadaluarsa,spd.keteranganlainnya,spd.nobatch,CAST(spd.qtyproduk*spd.hargasatuan as float) as subtotal
                    from strukpelayanandetail_t as spd
                    left JOIN produk_m as pr on pr.id=spd.objectprodukfk
                    left JOIN satuanstandar_m as ss on ss.id=spd.objectsatuanstandarfk
                    where spd.kdprofile = $this->kdProfile and nostrukfk=:norec"),
                array(
                    'norec' => $item->norec,
                )
            );

            $result[] = array(
                'tglstruk' => $item->tglstruk,
                'nostruk' => $item->nostruk,
                'noterima' => $item->noterima,
                'nofaktur' => $item->nofaktur,
                'tglfaktur' => $item->tglfaktur,
                'namarekanan' => $item->namarekanan,
                'namapengadaan' => $item->namapengadaan,
                'norec' => $item->norec,
                'asalproduk' => $item->asalproduk,
                'apid' => $item->apid,
                'namaruangan' => $item->namaruangan,
                'nobukti' => $item->nostruk_intern,
                'objectruanganasalfk' => $item->objectruanganasalfk,
                'tglspk' => $item->tglspk,
                'pegawaifkKK' => $item->objectpegawaipenanggungjawabfk,
                'rekananfk' => $item->rekananfk,
                'kelompokprodukfk' => $item->objectkelompokprodukfk,
                'ruanganfk' => $item->ruanganfk,
                'namapenerima' => $item->namalengkap,
                'penerimafk' => $item->penerimafk,
                'totalharusdibayar' => $item->totalharusdibayar,
                'totalppn' => $item->totalppn,
                'totaldiscount' => $item->totaldiscount,
                'totalhargasatuan' => $item->totalhargasatuan,
                'nosbk' => $item->nosbk,
                'nosppb' => $item->nosppb,
                'tgldokumen' => $item->tgldokumen,
                'nokontrak' => $item->nokontrak,
                'noorderintern' => $item->noorderintern,
                'tgljatuhtempo' => $item->tgljatuhtempo,
                'noorderfk' => $item->noorderfk,
                'jmlitem' => $item->qtyproduk,
                'namasuhu' => $item->namasuhu,
                'details' => $details,
            );
        }
        if (count($data) == 0) {
            $result = [];
        }

        $result = array(
            'daftar' => $result,
            'total' => $total,
            'message' => 'as@epic',
        );

        return $this->respond($result);
    }

    public function getHasilDarah(Request $request)
    {
        $norec = $request->norec;
        $norecpp = $request->norec_pp;
        $data = DB::table('hasildarah_t as ar')
            ->leftjoin('pegawai_m as pg', 'pg.id', '=', 'ar.pegawaifk')
            ->leftjoin('pegawai_m as pg2', 'pg2.id', '=', 'ar.dokterfk')
            ->leftJoin('pelayananpasien_t as pp', 'pp.norec', 'ar.pelayananpasienfk')
            ->leftJoin('produk_m as p', 'p.id', 'pp.produkfk')
            ->leftjoin('strukorder_t as so', 'so.norec', '=', 'pp.strukorderfk')
            ->orderBy('tanggal', 'DESC')
            ->select(
                'ar.*',
                'p.namaproduk',
                'so.tglorder',
                'so.status',
                'pg.namalengkap as pegawai',
                'pg2.namalengkap as dokter'
            )
            ->where('ar.kdprofile', $this->kdProfile)
            ->where('ar.statusenabled', true)
            ->when($norec, function ($query)  use ($norec) {
                $query->where('ar.pelayananpasienfk', $norec);
            })
            ->when($norecpp, function ($query)  use ($norecpp) {
                $query->where('so.norec', $norecpp);
            })
            ->first();
        if ($data) {
            $details =  DB::table('hasildarahdetail_t AS hap')
                ->select('hap.*', 'pg.id as pgid', 'pg.namalengkap as namalengkap')
                ->leftJoin('pegawai_m as pg', 'pg.id', '=', 'hap.pegawaifk')
                ->where('hap.objecthasildarahfk', $data->norec)->get();
            $data->details = $details;
        }
        return $this->respond($data);
    }


    public function getDetailPenerimaanDarah(Request $request)
    {
        $dataStruk = DB::table('strukpelayanan_t as sp')
            ->leftJOIN('strukpelayanandetail_t as spd', 'spd.nostrukfk', '=', 'sp.norec')
            ->leftjoin('asalproduk_m as ap', 'ap.id', 'spd.objectasalprodukfk')
            ->leftjoin('kelompokproduk_m as kp', 'kp.id', 'sp.objectkelompokprodukfk')
            ->leftJOIN('pegawai_m as pg', 'pg.id', '=', 'sp.objectpegawaipenerimafk')
            ->leftJOIN('strukretur_t as sr', 'sr.strukterimafk', '=', 'sp.norec')
            ->JOIN('ruangan_m as ru', 'ru.id', '=', 'sp.objectruanganfk')
            ->select(
                'sp.norec',
                'sp.tglstruk',
                'sp.nostruk',
                'pg.id as pgid',
                'pg.namalengkap',
                'ru.id',
                'ru.namaruangan',
                'ru.id as namaruanganfk',
                'sp.nofaktur',
                'sp.tglfaktur',
                'ap.asalproduk',
                'ap.id as asalprodukfk',
                'sp.namarekanan',
                'sp.objectrekananfk',
                'sp.objectkelompokprodukfk',
                'kp.kelompokproduk',
                'sp.keteranganlainnya',
                'sp.noorderfk',
                'sp.objectruanganfk'
            )
            ->where('sp.kdprofile', $this->kdProfile);
        if (isset($request['norec']) && $request['norec'] != "" && $request['norec'] != "undefined") {
            $dataStruk = $dataStruk->where('sp.norec', '=', $request['norec']);
        }

        $dataStruk = $dataStruk->first();


        $data = DB::table('strukpelayanan_t as sp')
            ->JOIN('strukpelayanandetail_t as spd', 'spd.nostrukfk', '=', 'sp.norec')
            ->leftJoin('suhu_m as sh','sh.id','=','spd.objectsuhufk')
            ->JOIN('ruangan_m as ru', 'ru.id', '=', 'sp.objectruanganfk')
            ->JOIN('produk_m as pr', 'pr.id', '=', 'spd.objectprodukfk')
            ->leftJOIN('detailjenisproduk_m as djp', 'djp.id', '=', 'spd.detailjenisprodukfk')
            ->leftJOIN('golongandarah_m as gl', 'gl.id', '=', 'spd.golongandarahfk')
            ->leftJOIN('satuanstandar_m as ss', 'ss.id', '=', 'spd.objectsatuanstandarfk')
            ->leftJOIN('asalproduk_m as ap', 'ap.id', '=', 'spd.objectasalprodukfk')
            ->select(
                'sp.nostruk',
                'spd.qtyproduk',
                'spd.qtyproduk as jumlah',
                'sp.objectruanganfk',
                'ru.namaruangan',
                'spd.objectprodukfk as produkfk',
                'spd.tujuan',
                'spd.hasilreleasedarah',
                'spd.nopermintaan',
                'pr.namaproduk',
                'sh.namasuhu',
                'spd.hasilkonversi as nilaikonversi',
                'sp.objectkelompokprodukfk',
                'spd.objectsatuanstandarfk',
                'djp.detailjenisproduk',
                'spd.detailjenisprodukfk',
                'ss.satuanstandar',
                'spd.objectasalprodukfk',
                'ap.asalproduk',
                'spd.keteranganlainnya',
                'spd.tglkadaluarsa',
                'spd.volume',
                'spd.nokantong',
                'spd.norec as norec_spd',
                'spd.qtyprodukpermintaan as qtyprodukpermintaan',
                'spd.volumepermintaan as volumepermintaan',
                'gl.golongandarah',
                'gl.id as golongandarahfk'
            )
            ->where('sp.kdprofile', $this->kdProfile)
            ->where('pr.objectdetailjenisprodukfk',3087);

        if (isset($request['norec']) && $request['norec'] != "" && $request['norec'] != "undefined") {
            $data = $data->where('sp.norec', '=', $request['norec']);
        }
        $data = $data->get();
        $ruangannfk = $dataStruk->objectruanganfk;

        $norecSP = $request['norec'];
        $datakirim = collect(DB::select("
            select kp.objectprodukfk,sum(kp.qtyproduk) as jml from kirimproduk_t  kp
            INNER JOIN strukkirim_t sk on sk.norec=kp.nokirimfk
            where kp.nostrukterimafk='$norecSP'
            and kp.kdprofile = $this->kdProfile and kp.statusenabled=true
            and sk.objectruanganfk=$ruangannfk
            group by kp.objectprodukfk;
            "));

        $datapp = collect(DB::select("
            select pp.produkfk,sum(pp.jumlah)  as jml from pelayananpasien_t pp
            INNER JOIN strukresep_t sr on sr.norec=pp.strukresepfk
            where pp.kdprofile = $this->kdProfile and pp.statusenabled=true
            and sr.ruanganfk=$ruangannfk
            and pp.strukterimafk='$norecSP'
            group by pp.produkfk;
            "));

        $dataOb = collect(DB::select("
            select spd.objectprodukfk,sum(spd.qtyproduk) as jml
            from strukpelayanandetail_t AS spd
            inner join strukpelayanan_t AS sp ON sp.norec = spd.nostrukfk
            where sp.kdprofile =  $this->kdProfile and sp.statusenabled=true
            and sp.objectruanganfk = $ruangannfk
            and spd.nostrukfk ='$norecSP'
            group by spd.objectprodukfk;
        "));

        $kalkulasidata = DB::table('strukpelayanan_t AS sp')
        ->join('strukpelayanandetail_t AS spd', 'spd.nostrukfk', '=', 'sp.norec')
        ->leftJoin('suhu_m AS sh', 'sh.id', '=', 'spd.objectsuhufk')
        ->join('ruangan_m AS ru', 'ru.id', '=', 'sp.objectruanganfk')
        ->join('produk_m AS pr', 'pr.id', '=', 'spd.objectprodukfk')
        ->leftJoin('detailjenisproduk_m AS djp', 'djp.id', '=', 'spd.detailjenisprodukfk')
        ->leftJoin('golongandarah_m AS gl', 'gl.id', '=', 'spd.golongandarahfk')
        ->leftJoin('satuanstandar_m AS ss', 'ss.id', '=', 'spd.objectsatuanstandarfk')
        ->leftJoin('asalproduk_m AS ap', 'ap.id', '=', 'spd.objectasalprodukfk')
        ->select(
            'sp.objectruanganfk',
            'ru.namaruangan',
            'spd.objectprodukfk AS produkfk',
            'pr.namaproduk',
            'sh.namasuhu',
            'sp.objectkelompokprodukfk',
            'djp.detailjenisproduk',
            'ss.satuanstandar',
            'ap.asalproduk',
            'spd.keteranganlainnya',
            'gl.golongandarah',
            'gl.id AS golongandarahfk',
            DB::raw('SUM(CASE WHEN pr.namaproduk = spd.namaproduk AND gl.id = spd.golongandarahfk THEN spd.qtyproduk ELSE 0 END) AS total_qtyproduk')
        )
        ->where('sp.kdprofile', '=', 1)
        ->where('spd.detailjenisprodukfk', '=', 3087)
        ->groupBy(
            'sp.objectruanganfk',
            'ru.namaruangan',
            'spd.objectprodukfk',
            'pr.namaproduk',
            'sh.namasuhu',
            'sp.objectkelompokprodukfk',
            'djp.detailjenisproduk',
            'ss.satuanstandar',
            'ap.asalproduk',
            'spd.keteranganlainnya',
            'gl.golongandarah',
            'gl.id'
        )
        ->get();

        $pelayananPasien = [];
        $i = 0;
        $jmlDipakai = 0;
        $data2res = [];
        foreach ($datakirim as $itemDua) {
            $data2res[] = array(
                'objectprodukfk' => $itemDua->objectprodukfk,
                'jml' => $itemDua->jml,
            );
        }
        $data1res = [];
        foreach ($datapp as $itemDua) {
            $data1res[] = array(
                'objectprodukfk' => $itemDua->produkfk,
                'jml' => $itemDua->jml,
            );
        }

        $data3res = [];
        foreach ($dataOb as $itemTiga) {
            $data3res[] = array(
                'objectprodukfk' => $itemTiga->objectprodukfk,
                'jml' => $itemTiga->jml,
            );
        }

        foreach ($data as $item) {
            $i = $i + 1;
            $jmlDipakai = 0;
            for ($j = 0; $j < count($data2res); $j++) {
                if ($item->produkfk == $data2res[$j]['objectprodukfk'] && $item->jumlah >= (float)$data2res[$j]['jml']) {
                    $jmlDipakai = $jmlDipakai + (float)$data2res[$j]['jml'];
                    $data2res[$j]['jml'] = 0;
                }
            }
            for ($j = 0; $j < count($data1res); $j++) {
                if ($item->produkfk == $data1res[$j]['objectprodukfk']  && (float)$item->jumlah >= (float)$data1res[$j]['jml']) {
                    $jmlDipakai = $jmlDipakai + (float)$data1res[$j]['jml'];
                    $data1res[$j]['jml'] = 0;
                }
            }
            for ($k = 0; $k < count($data3res); $k++) {
                if ($item->produkfk == $data3res[$k]['objectprodukfk']  && (float)$item->jumlah >= (float)$data3res[$k]['jml']) {
                    $jmlDipakai = $jmlDipakai + (float)$data3res[$k]['jml'];
                    $data3res[$k]['jml'] = 0;
                }
            }
            $pelayananPasien[] = array(
                'no' => $i,
                'nomerKantong' => $item->nokantong,
                'jenisDarah' => $item->detailjenisproduk,
                'jenisDarahfk' => $item->detailjenisprodukfk,
                'golonganDarah' => $item->golongandarah,
                'golonganDarahfk' => $item->golongandarahfk,
                'produkDarahfk' => $item->produkfk,
                'produkDarah' => $item->namaproduk,
                'qtykantong' => $item->qtyproduk,
                'satuanfk' => $item->objectsatuanstandarfk,
                'suhulabel' => $item->namasuhu,
                'hasilrelease' => $item->hasilreleasedarah,
                'nopermintaan' => $item->nopermintaan,
                'tujuan' => $item->tujuan,
                'satuan' => $item->satuanstandar,
                'konversi' => $item->nilaikonversi,
                'tglkadaluarsa' => $item->tglkadaluarsa,
                'volumeDarah' => $item->volume,
                'volumepermintaan' => $item->volumepermintaan,
                'qtyprodukpermintaan' => $item->qtyprodukpermintaan,
                'norec_spd' => $item->norec_spd,
            );
        }

        $result = array(
            'detailterima' => $dataStruk,
            'pelayananPasien' => $pelayananPasien,
            'datakalkulasi' => $kalkulasidata,
            'message' => 'as@epic',
        );

        return $this->respond($result);
    }

    public function DeletePenerimaanDarah(Request $request)
    {
        DB::beginTransaction();
        try {
            $dataKembaliStok = DB::table('strukpelayanandetail_t as spd')
                ->join('strukpelayanan_t as sp', 'sp.norec', 'spd.nostrukfk')
                ->select(
                    'sp.norec',
                    'spd.qtyproduk',
                    'spd.hasilkonversi',
                    'sp.objectruanganfk',
                    'spd.objectprodukfk',
                    'sp.nostruk',
                    'spd.norec as norectransaksi',
                )
                ->where('spd.kdprofile', $this->kdProfile)
                ->where('sp.norec', $request['nostruk'])
                ->get();

            $dataStokSudahKirim = StokProdukDetail::where('nostrukterimafk', $request['nostruk'])
                ->where('kdprofile', $this->kdProfile)
                ->whereNotIn('objectruanganfk', [$dataKembaliStok[0]->objectruanganfk])
                ->where('qtyproduk', '>', 0)
                ->get();

            if (count($dataStokSudahKirim) == 0) {
                foreach ($dataKembaliStok as $item5) {
                    $TambahStok = (float)$item5->qtyproduk * (float)$item5->hasilkonversi;
                    $dataSaldoAwal = collect(DB::select("
                                select sum(qtyproduk) as qty from stokprodukdetail_t
                                where kdprofile = $this->kdProfile
                                and objectruanganfk = $item5->objectruanganfk
                                and objectprodukfk= $item5->objectprodukfk
                        "))->first();
                    $saldoakhir = (float)$dataSaldoAwal->qty - $TambahStok;

                    $dataPenerimaan = DB::table('strukpelayanan_t as sr')
                        ->leftJoin('rekanan_m as rkn', 'rkn.id', '=', 'sr.objectrekananfk')
                        ->select(DB::raw("sr.nostruk,sr.nofaktur,rkn.namarekanan"))
                        ->where('sr.kdprofile', $this->kdProfile)
                        ->where('sr.norec', $request['nostruk'])
                        ->first();

                    KartuStok::where('keterangan',  'Penerimaan Barang Suplier. No Terima. ' . $dataPenerimaan->nostruk . ' ' . $dataPenerimaan->namarekanan)
                        ->where('kdprofile', $this->kdProfile)
                        ->update(['flagfk' => null]);

                    $this->kartu_STOK(array(
                        "saldoawal" => $dataSaldoAwal->qty,
                        "qtyin" => 0,
                        "qtyout" =>  $TambahStok,
                        "saldoakhir" => $saldoakhir,
                        "keterangan" => 'Batal Penerimaan No. ' . $item5->nostruk,
                        "produkfk" => $item5->objectprodukfk,
                        "ruanganfk" => $item5->objectruanganfk,
                        "tglinput" => date('Y-m-d H:i:s'),
                        "tglkejadian" => date('Y-m-d H:i:s'),
                        "nostrukterimafk" => $request['nostruk'],
                        "norectransaksi" => $item5->norectransaksi,
                        "tabletransaksi" => "stokprodukdetail_t",
                        "flagfk" => null,
                    ));

                    // $data = OrderPelayanan::where('noorderfk', $request['nostruk'])
                    //     ->where('kdprofile', $this->kdProfile)
                    //     ->where('objectprodukfk', $item5->objectprodukfk)
                    //     ->get();
                    // return $data;
                    // ->update(['qtyterimalast' => 0]);
                }
                $SP = StrukPelayanan::where('norec', $request['nostruk'])->where('kdprofile', $this->kdProfile)->first();
                $SP->statusenabled = false;
                $SP->save();

                StokProdukDetail::where('nostrukterimafk', $request['nostruk'])
                    ->where('kdprofile', $this->kdProfile)
                    ->delete();

                $kirim = KartuStok::where('ruanganfk', $item5->objectruanganfk)
                    ->where('kdprofile', $this->kdProfile)
                    ->where('produkfk', $item5->objectprodukfk)
                    ->get();


                $kartuStok[] = $kirim;

                $dataSTOKDETAIL[] = DB::select(
                    DB::raw("select qtyproduk as qty,nostrukterimafk,norec from stokprodukdetail_t
                            where kdprofile = $this->kdProfile and objectruanganfk=:ruanganfk and objectprodukfk=:produkfk"),
                    array(
                        'ruanganfk' => $item5->objectruanganfk,
                        'produkfk' => $item5->objectprodukfk,
                    )
                );

                $message =  "Hapus Penerimaan Berhasil";
            } else {
                $message =  "Sudah ada distribusi, tidak dapat di batalkan!!";
            }
            DB::commit();
            $result = [
                'status' => 201,
                'message' => $message,
                'data' => $SP,
            ];
        } catch (Exception $e) {
            DB::rollBack();
            $result = [
                'status' => 400,
                'message' => 'Simpan Gagal !',
                'data' => $e->getMessage(),
            ];
        }

        return $this->respond($result['data'], $result['status'], $result['message']);
    }

    public function PengeluaranProduk(Request $request)
    {
        DB::beginTransaction();
        try {
            //region @SIMPAN PELAYANAN OBAT IEU
            $idProfile = (int) $this->kdProfile;
            $racikanORnonracikan = 'N';
            $SET['depoRajal'] = explode(',', $this->settingFix('kdRuanganDepoRajal'));
            $SET['statusVerif'] = $this->settingFix('statusVerifApotik');
            $SET['statusSelesai'] = $this->settingFix('statusSelesaiApotik');
            $SET['kelTrans'] = $this->settingFix('kelompokTransaksiPelayanan');
            $SET['komponenHargaNetto'] = $this->settingFix('komponenHargaNetto');
            $SET['komponenHargaProfit'] = $this->settingFix('komponenHargaProfit');
            $SET['jenisPetugasDokterPJ'] = $this->settingFix('jenisPetugasDokterPJ');
            $SET['keterangan'] = $this->settingFix('ketPenguranganStok');

            $tglTrans =  date('Y-m-d H:i:s');
            $r_SR = $request['strukresep'];
            if ($r_SR['noorder'] != '' && $r_SR['noorder'] != 'EditResep') {
                $dataOrder = StrukOrder::where('noorder', $r_SR['noorder'])->where('kdprofile', $idProfile)->first();
                $dataOrder->statusorder =  $SET['statusSelesai'];
                $dataOrder->save();
            }
            // $dataOrder = StrukOrder::where('noorder', $r_SR['noorder'])->where('kdprofile', $idProfile)->first();
            // return $dataOrder;
            $namaPasien = $r_SR['nocm'] . ' - ' . $r_SR['namapasien'];
            if ($r_SR['noresep'] == '-') {
                $newSR = new StrukResep;
                $noResep = $this->SEQUENCE(new StrukResep, 'noresep', 12, 'O/' . $this->getDateTime()->format('ym') . '/', $idProfile);
                if ($noResep == '') {
                    $transMessage = "Gagal mengumpukan data, Coba lagi.!";
                    DB::rollBack();
                    $result = array(
                        "status" => 400,
                        "result" => null
                    );
                    return $this->respond($result['result'], $result['status'], $transMessage);
                }
                $norecSR = $newSR->generateNewId();
                /** Obat ALKES */
                $newSR->norec = $norecSR;
            } else {
                $newSR = StrukResep::where('norec', $r_SR['norecResep'])->where('kdprofile', $idProfile)->first();
                $noResep = $newSR->noresep;
                $resepOld = $newSR;
            }
            $newSR->kdprofile = $idProfile;
            $newSR->statusenabled = 1;
            $newSR->noresep = $noResep;
            $newSR->pasienfk = $r_SR['pasienfk'];
            $newSR->penulisresepfk = $r_SR['penulisresepfk'];
            $newSR->penerimafk = $r_SR['penerimafk'];
            $newSR->namalengkapambilresep = $r_SR['namalengkapambilresep'];
            $newSR->ruanganfk = $r_SR['ruanganfk'];
            $newSR->status = $SET['statusVerif'];
            $newSR->tglresep =  $r_SR['tglDiambil'];
            $newSR->noregistrasi =  $r_SR['noregistrasi'];
            $newSR->petugas =  $this->getNamaPegawai();
            $newSR->keterangan =  $SET['keterangan'];
            if (isset($r_SR['cito'])) {
                $newSR->cito =  $r_SR['cito'];
            }
            $newSR->save();
            $norec_SR = $newSR->norec;
            $dokterPenulis =  $newSR->penulisresepfk;

            // if ($r_SR['noorder'] != '' && $r_SR['noorder'] != 'EditResep') {
            //     $DataOrder = StrukOrder::where('norec', $r_SR['noorder'])->where('kdprofile', $idProfile)->first();
            //     $norecOrder = $DataOrder->norec;
            //     StrukResep::where('norec', $norec_SR)->update(['orderfk' => $norecOrder]);
            // }

            // // $TambahStok = 0;
            // if ($r_SR['noresep'] != '-') {

            //     KartuStok::where('keterangan',  'Pelayanan Obat Alkes No. '  . $noResep . ' ' . $namaPasien)
            //         ->where('kdprofile', $idProfile)
            //         ->update(['flagfk' => null]);

            //     $tglUbah = date('Y-m-d H:i:s', strtotime('-5 seconds', strtotime($tglTrans)));

            //     //##PENAMBAHAN KEMBALI STOKPRODUKDETAIL
            //     $dataKembaliStok = collect(DB::select("
            //                 select pp.norec,pp.stokprodukdetailfk,pr.namaproduk,pp.strukterimafk as nostrukterimafk,pp.jumlah,pp.nilaikonversi,sr.ruanganfk,pp.produkfk
            //                 from pelayananpasien_t as pp
            //                 INNER JOIN produk_m as pr on pr.id = pp.produkfk
            //                 INNER JOIN strukresep_t sr on sr.norec=pp.strukresepfk
            //                 where pp.kdprofile = $idProfile
            //                 and sr.kdprofile = $idProfile
            //                 and sr.norec='$norec_SR'
            //         "));


            //     if ($r_SR['ruanganfk'] == $resepOld->ruanganfk) {

            //         foreach ($dataKembaliStok as $item5) {
            //             $saldoAwal = 0;
            //             $saldoAkhir = 0;
            //             $TambahStok = (float)$item5->jumlah;
            //             $dataSaldoAwal = collect(DB::select("
            //                     select sum(qtyproduk) as qty from stokprodukdetail_t
            //                     where kdprofile = $idProfile and objectruanganfk='$resepOld->ruanganfk' and objectprodukfk='$item5->produkfk'"))
            //                 ->first();
            //             $saldoAwal = (float)$dataSaldoAwal->qty;
            //             $saldoAkhir = (float)$dataSaldoAwal->qty + $TambahStok;

            //             DB::table('stokprodukdetail_t')
            //                 ->where('kdprofile', $idProfile)
            //                 ->where('norec', $item5->stokprodukdetailfk)
            //                 ->lockForUpdate()
            //                 ->increment('qtyproduk', (float)$TambahStok);

            //             $this->kartu_STOK(array(
            //                 "saldoawal" => $saldoAwal,
            //                 "qtyin" => (float)$TambahStok,
            //                 "qtyout" => 0,
            //                 "saldoakhir" => $saldoAkhir,
            //                 "keterangan" => 'Ubah Pelayanan Obat Alkes No. '  . $noResep . '. pada produk ' .  $item5->namaproduk . '. Atas pasien ' . $namaPasien,
            //                 "produkfk" => $item5->produkfk,
            //                 "ruanganfk" => $r_SR['ruanganfk'],
            //                 "tglinput" => $tglUbah,
            //                 "tglkejadian" => $tglUbah,
            //                 "nostrukterimafk" => $item5->nostrukterimafk,
            //                 "norectransaksi" => $item5->norec,
            //                 "tabletransaksi" => 'pelayananpasien_t',
            //                 "stokprodukdetailfk" => $item5->stokprodukdetailfk,
            //                 "flagfk" => null,
            //             ));
            //         }
            //     } else {
            //         foreach ($dataKembaliStok as $item5) {
            //             $TambahStok = (float)$item5->jumlah;
            //             $saldoAwal = 0;
            //             $saldoAkhir = 0;

            //             $dataSaldoAwal = collect(DB::select("
            //                 select sum(qtyproduk) as qty from stokprodukdetail_t
            //                 where kdprofile = $idProfile and objectruanganfk='$resepOld->ruanganfk' and objectprodukfk='$item5->produkfk'"))
            //                 ->first();


            //             $saldoAwal = (float)$dataSaldoAwal->qty;
            //             $saldoAkhir = (float)$dataSaldoAwal->qty + $TambahStok;

            //             DB::table('stokprodukdetail_t')
            //                 ->where('kdprofile', $idProfile)
            //                 ->where('norec', $item5->stokprodukdetailfk)
            //                 ->lockForUpdate()
            //                 ->increment('qtyproduk', (float)$TambahStok);

            //             $this->kartu_STOK(array(
            //                 "saldoawal" => $saldoAwal,
            //                 "qtyin" => (float)$TambahStok,
            //                 "qtyout" => 0,
            //                 "saldoakhir" => $saldoAkhir,
            //                 "keterangan" => 'Ubah Resep No. '  . $noResep . '. pada produk ' .  $item5->namaproduk . '. Atas pasien ' . $namaPasien,
            //                 "produkfk" => $item5->produkfk,
            //                 "ruanganfk" => $r_SR['ruanganfk'],
            //                 "tglinput" => $tglUbah,
            //                 "tglkejadian" => $tglUbah,
            //                 "nostrukterimafk" => $item5->nostrukterimafk,
            //                 "norectransaksi" => $item5->norec,
            //                 "tabletransaksi" => 'pelayananpasien_t',
            //                 "stokprodukdetailfk" => $item5->stokprodukdetailfk,
            //                 "flagfk" => null,
            //             ));
            //         }
            //     }

            //     //END##PENAMBAHAN KEMBALI STOKPRODUKDETAIL

            //     //### LOGACC untuk penjurnalan blm ada
            //     $HapusPP = PelayananPasien::where('strukresepfk', $norec_SR)->where('kdprofile', $idProfile)->get();
            //     foreach ($HapusPP as $pp) {
            //         $HapusPPD = PelayananPasienDetail::where('pelayananpasien', $pp['norec'])->where('kdprofile', $idProfile)->delete();
            //         $HapusPPP = PelayananPasienPetugas::where('pelayananpasien', $pp['norec'])->where('kdprofile', $idProfile)->delete();
            //     }
            //     $HpsPP = PelayananPasien::where('strukresepfk', $norec_SR)->where('kdprofile', $idProfile)->delete();
            // }

            //## PelayananPasien
            $r_PP = $request['pelayananpasien'];

            foreach ($r_PP as $r_PPL) {

                $qtyJumlah = (float)$r_PPL['qtyKeluar'] * (float)$r_PPL['konversi'];
                $newPP = new PelayananPasien();
                $norecPP = $newPP->generateNewId();
                $newPP->norec = $norecPP;
                $newPP->kdprofile = $idProfile;
                $newPP->statusenabled = true;
                $newPP->noregistrasifk = $r_SR['noregistrasifk'];
                $newPP->tglregistrasi =  $r_SR['tglDiambil'];
                $newPP->jumlah = $qtyJumlah;
                $newPP->kelasfk = $r_SR['kelasfk'];
                $newPP->kdkelompoktransaksi = $SET['kelTrans'];
                $newPP->produkfk = $r_PPL['produkDarahfk'];
                if (isset($r_PPL['routefk'])) {
                    $newPP->routefk = $r_PPL['routefk'];
                }
                $newPP->stock = $r_PPL['stokproduk'];
                $newPP->tglpelayanan = $r_SR['tglDiambil'];
                $newPP->strukresepfk = $norec_SR;
                $newPP->satuanviewfk = $r_PPL['satuanfk'];
                $newPP->nilaikonversi = $r_PPL['konversi'];
                $newPP->strukterimafk = isset($r_PPL['nostrukterimafk']) ? $r_PPL['nostrukterimafk'] : null;
                if (isset($r_PPL['tglkadaluarsa']) && $r_PPL['tglkadaluarsa'] != 'Invalid date' && $r_PPL['tglkadaluarsa'] != '') {
                    $newPP->tglkadaluarsa = $r_PPL['tglkadaluarsa'];
                }
                $newPP->stokprodukdetailfk = $r_PPL['norec_spd'];
                $newPP->noregistrasi =  $r_SR['noregistrasi'];
                $newPP->save();

                $dataPP[] = $newPP;
                $norec_PP = $newPP->norec;
                //### PelayananPasienDetail
                $dataKomponen[] = array(
                    'komponenfk' => $SET['komponenHargaNetto'],
                    'komponen' => 'Harga Netto',
                );
                $dataKomponen[] = array(
                    'komponenfk' => $SET['komponenHargaProfit'],
                    'komponen' => 'Profit',
                );


                foreach ($dataKomponen as $itemKomponen) {
                    $newPPD = new PelayananPasienDetail();
                    $norecPPD = $newPPD->generateNewId();
                    $newPPD->norec = $norecPPD;
                    $newPPD->kdprofile = $idProfile;
                    $newPPD->statusenabled = true;
                    $newPPD->noregistrasifk = $r_SR['noregistrasifk'];
                    $newPPD->tglregistrasi = $r_SR['tglregistrasi'];
                    $newPPD->jumlah = $qtyJumlah;
                    $newPPD->komponenhargafk = $itemKomponen['komponenfk'];
                    $newPPD->pelayananpasien = $norec_PP;
                    $newPPD->produkfk = $r_PPL['produkDarahfk'];
                    $newPPD->stock = 0;
                    $newPPD->tglpelayanan =  $r_SR['tglDiambil'];
                    $newPPD->noregistrasi =  $r_SR['noregistrasi'];
                    $newPPD->save();
                }
                //## StokProdukDetail

                $jmlPengurang = (float)$qtyJumlah;
                $dataSaldoAwal = collect(DB::select("
                     select sum(qtyproduk) as qty from stokprodukdetail_t
                     where kdprofile = $idProfile
                     and objectruanganfk='$r_SR[ruanganfk]'
                     and objectprodukfk='$r_PPL[produkDarahfk]'"))
                    ->first();

                $namaProduk = $r_PPL['produkDarah'];
                $saldoAwalIn = (float)$dataSaldoAwal->qty;
                $saldoAkhirIn = (float)$dataSaldoAwal->qty - $jmlPengurang;

                $newSPD = StokProdukDetail::where('norec', $r_PPL['norec_spd'])
                    ->where('kdprofile', $idProfile)
                    ->where('qtyproduk', '>=', $jmlPengurang)
                    ->first();

                if (empty($newSPD)) {
                    $transMessage = "Simpan Resep Gagal, cek stok barang " . $namaProduk;
                    DB::rollBack();
                    $result = array(
                        "status" => 400,
                        "result" => null
                    );
                    return $this->respond($result['result'], $result['status'], $transMessage);
                }

                DB::table('stokprodukdetail_t')
                    ->where('kdprofile', $idProfile)
                    ->where('norec',  $r_PPL['norec_spd'])
                    ->lockForUpdate()
                    ->decrement('qtyproduk', (float)$jmlPengurang);

                $dataKS = [];

                if ((float)$dataSaldoAwal->qty == 0 || $jmlPengurang > (float)$dataSaldoAwal->qty) {
                    $transMessage = "Simpan Resep Gagal, Stok Produk " . $namaProduk . ", ada " . (float)$dataSaldoAwal->qty . " Data Stok Kurang Dari Qty Resep !";
                    DB::rollBack();
                    $result = array(
                        "status" => 400,
                        "result" => null
                    );
                    return $this->respond($result['result'], $result['status'], $transMessage);
                }

                $this->kartu_STOK(array(
                    "saldoawal" => $saldoAwalIn,
                    "qtyin" => 0,
                    "qtyout" => (float)$qtyJumlah,
                    "saldoakhir" => (float) $saldoAkhirIn,
                    "keterangan" => 'Pelayanan Bank Darah No. '  . $noResep . '. Pada produk ' . $r_PPL['produkDarah'] . '. Atas pasien ' . $namaPasien,
                    "produkfk" => $r_PPL['produkDarahfk'],
                    "ruanganfk" => $r_SR['ruanganfk'],
                    "tglinput" => date('Y-m-d H:i:s'),
                    "tglkejadian" => $tglTrans,
                    "nostrukterimafk" => null,
                    "norectransaksi" => $norec_PP,
                    "tabletransaksi" => 'pelayananpasien_t',
                    "stokprodukdetailfk" => $r_PPL['norec_spd'],
                    "flagfk" => 7,
                ));

                //## Petugas
                $newP3 = new PelayananPasienPetugas();
                $norecKS = $newP3->generateNewId();
                $newP3->norec = $norecKS;
                $newP3->kdprofile = $idProfile;
                $newP3->statusenabled = true;
                $newP3->nomasukfk = $r_SR['noregistrasifk'];
                $newP3->objectasalprodukfk = $r_PPL['asalprodukfk'];
                $newP3->objectjenispetugaspefk = $SET['jenisPetugasDokterPJ'];
                $newP3->objectprodukfk = $r_PPL['produkDarahfk'];
                $newP3->objectruanganfk = $r_SR['ruanganfk'];
                $newP3->pelayananpasien = $norec_PP;
                $newP3->tglpelayanan = $r_SR['tglDiambil'];
                $newP3->objectpegawaifk = $dokterPenulis;
                $newP3->noregistrasi =  $r_SR['noregistrasi'];
                $newP3->save();
            }

            $responseResep = DB::table('strukresep_t as sr')
                ->leftjoin('antrianapotik_t as aa', 'aa.noresep', '=', 'sr.noresep')
                ->select(
                    'sr.norec',
                    'aa.jenis',
                    'sr.noresep',
                    'sr.noregistrasi',
                    'sr.pasienfk',
                    'sr.penulisresepfk',
                    'sr.petugas',
                    'sr.ruanganfk',
                    'sr.ruanganfk',
                    'sr.status'
                )
                ->where('sr.norec', $norec_SR)
                ->first();

            //endregion
            $transStatus = 'true';
        } catch (\Exception $e) {
            $transStatus = 'false';
        }

        if ($transStatus == 'true') {
            $transMessage = "Simpan Pengeluaran Darah Berhasil";
            DB::commit();
            $result = array(
                "status" => 200,
                "result" => array(
                    "noresep"  => $responseResep,
                    "as" => '@epic',
                ),
            );
        } else {
            $transMessage = "Simpan Pengeluaran Darah Gagal!!";
            DB::rollBack();
            $result = array(
                "status" => 400,
                "result"  => $e->getMessage()
            );
        }

        return $this->respond($result['result'], $result['status'], $transMessage);
    }

    public function getPasienOrderDarah(Request $r)
    {
        $data = DB::table('pasiendaftar_t as pd')
            ->JOIN('ruangan_m as ru', 'ru.id', '=', 'pd.objectruanganlastfk')
            ->JOIN('pasien_m as ps', 'ps.id', '=', 'pd.nocmfk')
            ->leftJoin('jeniskelamin_m as jk', 'jk.id', '=', 'ps.objectjeniskelaminfk')
            ->JOIN('kelompokpasien_m as kp', 'kp.id', '=', 'pd.objectkelompokpasienlastfk')
            ->leftJoin('rekanan_m as rek', 'rek.id', '=', 'pd.objectrekananfk')
            ->JOIN('kelas_m as kl', 'kl.id', '=', 'pd.objectkelasfk')
            ->select(
                'ru.namaruangan',
                'pd.noregistrasi',
                'ps.nocm',
                'ps.namapasien',
                'jk.jeniskelamin',
                'kp.id as kpid',
                'kp.kelompokpasien',
                'kl.namakelas',
                'kl.id as klid',
                'pd.tglregistrasi',
                'pd.tglpulang',
                'ps.tgllahir',
                'pd.nostruklastfk',
                'pd.norec as norec_pd',
                'rek.namarekanan'
            )
            ->where('pd.kdprofile', $this->kdProfile)
            ->where('pd.statusenabled', true)
            ->where('ps.statusenabled', true);
        // ->orderByDesc('pd.noregistrasi');
        if (isset($r['dari']) && $r['dari'] != "" && $r['dari'] != "undefined") {
            $data = $data->where('pd.tglregistrasi', '>=', $r['dari'] . ' 00:00');
        }
        if (isset($r['sampai']) && $r['sampai'] != "" && $r['sampai'] != "undefined") {
            $data = $data->where('pd.tglregistrasi', '<=', $r['sampai'] . ' 23:59');
        }
        if (isset($r['ruid']) && $r['ruid'] != "" && $r['ruid'] != "undefined") {
            $data = $data->where('ru.id', $r['ruid']);
        }
        if (isset($r['dpid']) && $r['dpid'] != "" && $r['dpid'] != "undefined") {
            $data = $data->where('dp.id', $r['dpid']);
        }
        if (isset($r['kpid']) && $r['kpid'] != "" && $r['kpid'] != "undefined") {
            $data = $data->where('kp.id', $r['kpid']);
        }
        if (isset($r['noreg']) && $r['noreg'] != "" && $r['noreg'] != "undefined") {
            $data = $data->where('pd.noregistrasi', 'ilike', '%' . $r['noreg'] . '%');
        }
        if (isset($r['nocm']) && $r['nocm'] != "" && $r['nocm'] != "undefined") {
            $data = $data->where('ps.nocm', 'ilike', '%' . $r['nocm'] . '%');
        }
        if (isset($r['namapasien']) && $r['namapasien'] != "" && $r['namapasien'] != "undefined") {
            $data = $data->where('ps.namapasien', 'ilike', '%' . $r['namapasien'] . '%');
        }
        $total = $data->count();
        if (isset($r['offset']) && $r['offset'] != '') {
            $data = $data->offset($r['offset']);
        }
        if (isset($r['limit']) && $r['limit'] != '') {
            $data = $data->limit($r['limit']);
        }

        $data = $data->get();

        foreach ($data as $d) {
            $d->umur = $this->getAge($d->tgllahir, $d->tglregistrasi);
        }

        // dd(DB::getQueryLog());
        $result = [
            'data' => $data,
            'total' => $total
        ];
        return $this->respond($result);
    }

    public function getPengeluaranDarah(Request $request)
    {

        $ket = $this->settingFix('ketPenguranganStok');

        $data = DB::table('strukresep_t as sk')
            ->leftjoin('pasien_m as ps', 'sk.pasienfk', 'ps.id')
            ->leftjoin('pegawai_m as pg1', 'pg1.id', 'sk.penulisresepfk')
            ->leftjoin('pegawai_m as pg2', 'pg2.id', 'sk.penerimafk')
            ->leftjoin('ruangan_m as ru', 'ru.id', 'sk.ruanganfk')
            ->select('sk.norec', 'ps.namapasien', 'pg1.namalengkap as pegawaiPemberi', 'pg2.namalengkap as pegawaipenerima', 'sk.namalengkapambilresep')
            ->where('sk.kdprofile', $this->kdProfile)
            ->where('sk.statusenabled', true)
            ->where('sk.keterangan', $ket)
            ->get();

        //   $detail = DB::table('pelayananpasien_t as pp')
        //              ->join('')
        return $data;
        // StrukResep::where('norec', $r_SR['norecResep'])->where('kdprofile', $idProfile)->first();
    }
}
