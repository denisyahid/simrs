<?php

namespace App\Http\Controllers\Sysadmin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\EMR\TindakanCtrl;
use App\Models\Master\JenisPelayanan;
use App\Models\Master\KelompokPasien;
use App\Models\Master\Produk;
use App\Models\Master\Rekanan;
use App\Models\Master\Ruangan;
use App\Models\Transaksi\MapRuanganToAdministrasi;
use App\Models\Transaksi\MapRuanganToAkomodasi;
use App\Models\Transaksi\PelayananPasien;
use App\Models\Transaksi\PelayananPasienDetail;
use App\Models\Transaksi\PelayananPasienPetugas;
use App\Traits\Valet;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Mockery\Exception\InvalidOrderException;
use Exception;

class MapAkomodasiCtrl extends Controller
{
    use Valet;
    protected $tindakanCtrl;
    public function __construct(TindakanCtrl $tindakanCtrl)
    {
        parent::__construct($is_encrypt = true);
        $this->tindakanCtrl = $tindakanCtrl;
    }

    public function getListComboakomodasi()
    {
        $res['ruangan'] = Ruangan::mine()->whereIN('objectdepartemenfk', explode(',', $this->settingFix('kdDepartemenRanapFix')))->get();
        $res['jenispelayanan'] = JenisPelayanan::mine()->get();
        $res['kelompokpasien'] = KelompokPasien::mine()->get();
        return $this->respond($res);
    }

    public function getProdukakomodasi(Request $r)
    {
        $data = DB::table('mapruangantoproduk_m as mpr')
            ->join('produk_m as prd', 'prd.id', '=', 'mpr.objectprodukfk')
            ->select(
                'mpr.objectprodukfk as id',
                'prd.namaproduk',
                'mpr.objectruanganfk',
                'prd.namaproduk'
            )->distinct()
            ->where('mpr.kdprofile', $this->kdProfile)
            ->where('mpr.statusenabled', true)
            ->where('prd.statusenabled', true);

        if (
            isset($r['idProduk']) &&
            $r['idProduk'] != "" &&
            $r['idProduk'] != "undefined"
        ) {
            $data = $data
                ->where('mpr.objectprodukfk', $r['idProduk']);
        }
        if (
            isset($r['idRuangan']) &&
            $r['idRuangan'] != "" &&
            $r['idRuangan'] != "undefined"
        ) {
            $data = $data
                ->where('mpr.objectruanganfk', $r['idRuangan']);
        }
        if (
            isset($r['limit']) &&
            $r['limit'] != "" &&
            $r['limit'] != "undefined"
        ) {
            $data = $data->take($r['limit']);
        }
        $data = $data->orderBy('prd.namaproduk', 'ASC');
        $data = $data->get();
        $result['data'] = $data;
        $result['as'] = '@epic';

        return $this->respond($result);
    }

    public function getTindakanKomponen(Request $request)
    {
        $idProfile = (int) $this->kdProfile;

        $re = new Request();
        $re['idRuangan'] = $request['idRuangan'];
        $re['idProduk'] = $request['idProduk'];
        $re['idJenisPelayanan'] = $request['idJenisPelayanan'];
        $re['idKelas'] = (int) $this->settingFix('idNonKelas');

        $result = $this->tindakanCtrl->listTindakanKomponen($re, true);
        return $this->respond($result);
    }

    public function saveMapAkomodasi(Request $request)
    {
        DB::beginTransaction();
        try {
            $PSN = $request['mapping'];

            if ($PSN['id'] == '') {
                $data = new MapRuanganToAkomodasi();
                $data->id = $this->SEQUENCE_MASTER(new MapRuanganToAkomodasi(), 'id', $this->kdProfile); ///(string)Uuid::uuid4();
                $data->statusenabled = true;
                $transMessage = "Proses Simpan Data Berhasil";
            } else {
                $data = MapRuanganToAkomodasi::where('id', $PSN['id'])->first();

                $transMessage = "Proses Update Data Berhasil";
                $data->id;
            }
            $data->kdprofile = $this->kdProfile;
            $data->objectruanganfk = $PSN['objectruanganfk'];
            $data->objectprodukfk = $PSN['objectprodukfk'];
            $data->jenispelayananfk = $PSN['jenispelayananfk'];
            $data->rekananfk = isset($PSN['rekananfk']) ? $PSN['rekananfk'] : null;
            $data->israwatgabung = isset($PSN['rg']) ? $PSN['rg'] : null;

            $data->save();

            DB::commit();

            $result = [
                "status" => 200,
                "message" => $transMessage,
                "result" => [
                    "data" => $data,
                    "as" => '@epic',
                ],
            ];
        } catch (InvalidOrderException $e) {
            DB::rollBack();
            $result = array(
                "status" => $e->getCode(),
                "message" => "Simpan Gagal",
                "result" => $e->getMessage()

            );
        }

        return $this->respond($result['result'], $result['status'], $result['message']);
    }

    public function getMapAkomodasi(Request $request)
    {
        $data = DB::table('mapruangantoakomodasi_t as mpa')
            ->leftjoin('ruangan_m as ru', 'ru.id', 'mpa.objectruanganfk')
            ->leftjoin('kelompokpasien_m as re', 're.id', 'mpa.rekananfk')
            ->leftjoin('jenispelayanan_m as jp', 'jp.id', 'mpa.jenispelayananfk')
            ->leftjoin('produk_m as pr', 'pr.id', 'mpa.objectprodukfk')
            ->select(
                'mpa.id',
                'mpa.objectruanganfk',
                'mpa.objectprodukfk',
                'mpa.rekananfk',
                'mpa.jenispelayananfk',
                'pr.namaproduk',
                'ru.namaruangan',
                'jp.jenispelayanan',
                're.kelompokpasien',
                'mpa.israwatgabung'
            )
            ->where('mpa.kdprofile', $this->kdProfile)
            ->where('mpa.statusenabled', true);

        if (isset($request['idruangan']) && $request['idruangan'] != '') {
            $data = $data->where('mpa.objectruanganfk', '=', $request['idruangan']);
        }
        if (isset($request['idproduk']) && $request['idproduk'] != '') {
            $data = $data->where('mpa.objectprodukfk', '=', $request['idproduk']);
        }
        if (isset($request['idrekanan']) && $request['idrekanan'] != '') {
            $data = $data->where('mpa.rekananfk', '=', $request['idrekanan']);
        }
        // if (isset($r['namaproduk']) && $r['namaproduk'] != '') {
        //     $data = $data->where('pr.namaproduk', 'ilike', '%' . $r['namaproduk'] . '%');
        // }

        if (isset($r['limit']) && $r['limit'] != '') {
            $data = $data->limit($r['limit']);
        }

        $data = $data->get();

        $res['data'] = $data;
        return $this->respond($res);
    }

    public function deletMappingAkomodasi(Request $r)
    {
        DB::beginTransaction();
        try {
            $dataPS = MapRuanganToAkomodasi::where('id', $r['id'])->update(['statusenabled' => false]);

            DB::commit();

            $result = [
                'status' => 201,
                'message' => 'Proses Hapus Data Berhasil',
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
    public function saveAkomodasiAuto(Request $request, $lokal = false, $kdProfilee = null)
    {

        DB::beginTransaction();
        try {
            $kdProfile = $kdProfilee == null ? $this->kdProfile : $kdProfilee;
            $inap = $this->settingFix('kdDepartemenRanapFix');

            //* Get data APD
            $data2 = DB::select(
                DB::raw("select apd.tglmasuk,apd.tglkeluar,apd.norec as norec_apd,pd.tglregistrasi,apd.objectpegawaifk as dokter,
                    pd.noregistrasi,pd.jenispelayanan,ps.namapasien,ps.nocm, ps.objectkebangsaanfk
                    from pasiendaftar_t as pd
                    INNER JOIN antrianpasiendiperiksa_t as apd on apd.noregistrasifk=pd.norec
                    INNER JOIN ruangan_m as ru_pd on ru_pd.id=apd.objectruanganfk
                    INNER JOIN pasien_m as ps on ps.id=pd.nocmfk
                    where ru_pd.objectdepartemenfk in ($inap)
                    and pd.noregistrasi=:noregistrasi
                    and pd.tglpulang is null
                    and pd.statusenabled=true
                    and pd.kdprofile =:kdprofile
                    order by apd.tglmasuk
                    "),
                array(
                    'noregistrasi' => $request['noregistrasi'],
                    'kdprofile' => $kdProfile
                )
            );

            $x = 0;
            foreach ($data2 as $dateAPD) {
                $tglMasuk = $dateAPD->tglmasuk;
                if (is_null($dateAPD->tglkeluar) == true) {
                    $tglKeluar = date('Y-m-d 23:59:59');
                } else {
                    $tglKeluar = $dateAPD->tglkeluar;
                }
                $tglMetu = Carbon::parse($tglKeluar)->subDays(1);
                $arrDate = $this->dateRange($tglMasuk, $tglKeluar);

                //* Get data ruangan
                $dataDong = DB::select(
                    DB::raw("select apd.objectkelasfk,
                    apd.objectruanganfk,apd.israwatgabung ,apd.norec as norec_apd,pd.tglregistrasi,pd.objectrekananfk
                    from pasiendaftar_t as pd
                    INNER JOIN antrianpasiendiperiksa_t as apd on apd.noregistrasifk=pd.norec
                    INNER JOIN ruangan_m as ru_pd on ru_pd.id=apd.objectruanganfk
                    where pd.tglpulang is null and  ru_pd.objectdepartemenfk in ($inap)
                    and pd.noregistrasi=:noregistrasi and apd.norec=:norec_apd
                    and pd.statusenabled=true
                    and pd.kdprofile =:kdprofile"),
                    array(
                        'noregistrasi' => $request['noregistrasi'],
                        'norec_apd' => $dateAPD->norec_apd,
                        'kdprofile' => $kdProfile
                    )
                );
                $x = 0;
                foreach ($arrDate as $itemDate) {
                    $tglAwal = $itemDate . ' 00:00';
                    $tglAkhir = $itemDate . ' 23:59';

                    //* Get data tindakan
                    $data = DB::select(
                        DB::raw("select pp.tglpelayanan,apd.objectkelasfk,
                           apd.objectruanganfk,apd.israwatgabung,pp.produkfk
                            from pasiendaftar_t as pd
                            INNER JOIN antrianpasiendiperiksa_t as apd on apd.noregistrasifk=pd.norec
                            INNER JOIN pelayananpasien_t as pp on pp.noregistrasifk=apd.norec
                            INNER JOIN produk_m as pr on pr.id=pp.produkfk
                            INNER JOIN ruangan_m as ru_pd on ru_pd.id=pd.objectruanganlastfk
                            where pd.tglpulang is null  and ru_pd.objectdepartemenfk in ($inap)
                            and pp.tglpelayanan between '$tglAwal' and '$tglAkhir' and pp.keteranganlain = 'akomodasi kamar'
                            and pd.kdprofile =$kdProfile
                            and pd.statusenabled=true
                            and pd.noregistrasi='$request[noregistrasi]' ;")
                    );
                    $jenispelayananfk = " and hett.objectjenispelayananfk = " . $dateAPD->jenispelayanan;
                    // if ($dataDong[0]->objectrekananfk == 'null' || $dataDong[0]->objectrekananfk == null || !isset($dataDong[0]->objectrekananfk)) {

                    if (isset($dataDong[0]->objectrekananfk) && $dataDong[0]->objectrekananfk != null && $dataDong[0]->objectrekananfk == 2551) {
                        $objectrekananfk = "  AND hett.objectpenjaminfk= " . $dataDong[0]->objectrekananfk;
                    } else {
                        $objectrekananfk = "  AND hett.objectpenjaminfk IS NULL";
                    }
                    //     $objectrekananfk = "  AND hett.objectpenjaminfk=";

                    //* Start Akomodasi Otomatis
                    $ruanganfk = $dataDong[0]->objectruanganfk;
                    $kelasfk = $dataDong[0]->objectkelasfk;
                    if ($dataDong[0]->israwatgabung == 1) {
                        $sirahMacan = DB::select(
                            DB::raw("select distinct hett.* from mapruangantoakomodasi_t as map
                            INNER JOIN harganettoprodukbykelas_m as hett on hett.objectprodukfk=map.objectprodukfk
                            where map.objectruanganfk= $ruanganfk and hett.objectkelasfk=$kelasfk and map.israwatgabung=1
                            and map.kdprofile =$kdProfile
                            and map.statusenabled=true
                            and hett.statusenabled=true
                            and hett.objectkebangsaanfk=$dateAPD->objectkebangsaanfk
                             $objectrekananfk
                             $jenispelayananfk")
                        );
                    } else {
                        $sirahMacan = DB::select(
                            DB::raw("select distinct hett.* from mapruangantoakomodasi_t as map
                            INNER JOIN harganettoprodukbykelas_m as hett on hett.objectprodukfk=map.objectprodukfk
                            where map.objectruanganfk=$ruanganfk and hett.objectkelasfk=$kelasfk and map.israwatgabung is null
                            and map.kdprofile =$kdProfile
                            and map.statusenabled=true
                            and hett.statusenabled=true
                            and hett.objectkebangsaanfk=$dateAPD->objectkebangsaanfk
                            $objectrekananfk
                            $jenispelayananfk
                            ")
                        );
                        // return $dateAPD;
                        // return $sirahMacan;
                    }

                    // if (count($sirahMacan) == 0) {
                    //     DB::rollBack();
                    //     $result = array(
                    //         "status" => 400,
                    //         "message" => "Simpan Gagal",
                    //         "result" => "Master tarif tidak ada "
                    //     );
                    //     $this->LOGGING(
                    //         'Akomodasi Otomatis',
                    //         $data2[0]->noregistrasi,
                    //         'pasiendaftar_t',
                    //         'Akomodasi Otomatis gagal Master tarif tidak ada pada Pasien ' . $data2[0]->namapasien . ' (' . $data2[0]->nocm . ') - ' . $data2[0]->noregistrasi
                    //     );
                    //     if ($lokal) {
                    //         return $result;
                    //     }
                    //     return $this->respond($result['result'], $result['status'], $result['message']);
                    // }

                    $diskon = 0;
                    $tglAwalDiskon = $itemDate . ' 23:59';
                    $start = new Carbon($dateAPD->tglregistrasi);
                    $end = new Carbon($tglAwalDiskon);
                    $tglRegis = date('Y-m-d', strtotime($dateAPD->tglregistrasi));
                    $selisihjam = $start->diff($end)->format('%H');
                    // if ($tglRegis == $itemDate){
                    //     if ((int)$selisihjam <= 6 ){
                    //         $diskon = ((float)$sirahMacan[0]->hargasatuan * 50)/100;
                    //     }
                    // }
                    // ## END ##
                    if ($dataDong[0]->israwatgabung == 1) {
                        $buntutMacan = DB::select(
                            DB::raw("select hett.* from mapruangantoakomodasi_t as map
                            INNER JOIN harganettoprodukbykelasd_m as hett on hett.objectprodukfk=map.objectprodukfk
                            where map.objectruanganfk=:ruanganid and hett.objectkelasfk=:kelasid  and map.israwatgabung=1
                            and map.kdprofile =:kdprofile
                            and map.statusenabled=true
                            and hett.statusenabled=true
                            and hett.objectkebangsaanfk=$dateAPD->objectkebangsaanfk
                            $objectrekananfk
                            $jenispelayananfk"),
                            array(
                                'ruanganid' => $dataDong[0]->objectruanganfk,
                                'kelasid' => $dataDong[0]->objectkelasfk,
                                'kdprofile' => $kdProfile
                            )
                        );
                    } else {
                        $buntutMacan = DB::select(
                            DB::raw("select hett.* from mapruangantoakomodasi_t as map
                            INNER JOIN harganettoprodukbykelasd_m as hett on hett.objectprodukfk=map.objectprodukfk
                            where map.objectruanganfk=:ruanganid and hett.objectkelasfk=:kelasid  and map.israwatgabung is null
                            and map.kdprofile =:kdprofile
                            and map.statusenabled=true
                            and hett.statusenabled=true
                            and hett.objectkebangsaanfk=$dateAPD->objectkebangsaanfk
                            $objectrekananfk
                            $jenispelayananfk"),
                            array(
                                'ruanganid' => $dataDong[0]->objectruanganfk,
                                'kelasid' => $dataDong[0]->objectkelasfk,
                                'kdprofile' => $kdProfile

                            )
                        );
                        // return $buntutMacan;
                    }
                    //* END Akomodasi Otomatis
                    // return $data;
                    if (count($data) == 0) {
                        foreach ($sirahMacan as $sir) {
                            $x++;
                            $PelPasien = new PelayananPasien();
                            $PelPasien->norec = $PelPasien->generateNewId();
                            $PelPasien->kdprofile = $kdProfile;
                            $PelPasien->statusenabled = true;
                            $PelPasien->noregistrasifk = $dateAPD->norec_apd;
                            $PelPasien->tglregistrasi = $dataDong[0]->tglregistrasi;
                            $PelPasien->hargadiscount = 0;
                            $PelPasien->hargajual = $sir->hargasatuan;
                            $PelPasien->hargasatuan = $sir->hargasatuan;
                            $PelPasien->jumlah = 1;
                            $PelPasien->kelasfk = $dataDong[0]->objectkelasfk;
                            $PelPasien->kdkelompoktransaksi = 1;
                            $PelPasien->piutangpenjamin = 0;
                            $PelPasien->piutangrumahsakit = 0;
                            $PelPasien->produkfk = $sir->objectprodukfk;
                            $PelPasien->stock = 1;
                            $PelPasien->tglpelayanan = $tglAwal;
                            $PelPasien->keteranganlain = 'akomodasi kamar';
                            $PelPasien->harganetto = $sir->harganetto1;
                            $PelPasien->noregistrasi = $dateAPD->noregistrasi;
                            $PelPasien->objecthargaprodukfk = $sir->id;
                            $PelPasien->save();
                            $PPnorec = $PelPasien->norec;
                            $namaprod = Produk::where('id', $sir->objectprodukfk)->first()->namaproduk;
                            $this->LOGGING(
                                'Akomodasi Otomatis',
                                $PPnorec,
                                'pasiendaftar_t',
                                'Akomodasi Otomatis pada Pasien ' . $data2[0]->namapasien . ' (' . $data2[0]->nocm . ') - ' . $data2[0]->noregistrasi . ' - ' . $namaprod
                            );

                            $PelPasienPetugas = new PelayananPasienPetugas();
                            $PelPasienPetugas->norec = $PelPasienPetugas->generateNewId();
                            $PelPasienPetugas->kdprofile = $kdProfile;
                            $PelPasienPetugas->statusenabled = true;
                            $PelPasienPetugas->nomasukfk = $dateAPD->norec_apd;
                            $PelPasienPetugas->objectpegawaifk = $dateAPD->dokter;
                            $PelPasienPetugas->objectjenispetugaspefk = $this->settingFix('jenisPetugasDokterPemeriksa');
                            $PelPasienPetugas->pelayananpasien = $PPnorec;
                            $PelPasienPetugas->noregistrasi = $dateAPD->noregistrasi;
                            $PelPasienPetugas->save();

                            foreach ($buntutMacan as $itemKomponen) {
                                if ($itemKomponen->objectprodukfk == $sir->objectprodukfk) {
                                    $PelPasienDetail = new PelayananPasienDetail();
                                    $PelPasienDetail->norec = $PelPasienDetail->generateNewId();
                                    $PelPasienDetail->kdprofile = $kdProfile;
                                    $PelPasienDetail->statusenabled = true;
                                    $PelPasienDetail->noregistrasifk = $dateAPD->norec_apd;
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
                                    $PelPasienDetail->tglpelayanan = $tglAwal;
                                    $PelPasienDetail->harganetto = $itemKomponen->harganetto1;
                                    $PelPasienDetail->noregistrasi = $dateAPD->noregistrasi;
                                    $PelPasienDetail->save();

                                    $diskon = 0;
                                }
                            }
                        }
                    } else {
                        if (count($sirahMacan) != count($data)) {
                            foreach ($data as $dd) {
                                foreach ($sirahMacan as $sir) {
                                    if ($dd->produkfk == $sir->objectprodukfk) {
                                        continue;
                                    }
                                    $x++;
                                    $PelPasien = new PelayananPasien();
                                    $PelPasien->norec = $PelPasien->generateNewId();
                                    $PelPasien->kdprofile = $kdProfile;
                                    $PelPasien->statusenabled = true;
                                    $PelPasien->noregistrasifk = $dateAPD->norec_apd;
                                    $PelPasien->tglregistrasi = $dataDong[0]->tglregistrasi;
                                    $PelPasien->hargadiscount = 0;
                                    $PelPasien->hargajual = $sir->hargasatuan;
                                    $PelPasien->hargasatuan = $sir->hargasatuan;
                                    $PelPasien->jumlah = 1;
                                    $PelPasien->kelasfk = $dataDong[0]->objectkelasfk;
                                    $PelPasien->kdkelompoktransaksi = 1;
                                    $PelPasien->piutangpenjamin = 0;
                                    $PelPasien->piutangrumahsakit = 0;
                                    $PelPasien->produkfk = $sir->objectprodukfk;
                                    $PelPasien->stock = 1;
                                    $PelPasien->tglpelayanan = $tglAwal;
                                    $PelPasien->keteranganlain = 'akomodasi kamar';
                                    $PelPasien->harganetto = $sir->harganetto1;
                                    $PelPasien->noregistrasi = $dateAPD->noregistrasi;
                                    $PelPasien->save();
                                    $PPnorec = $PelPasien->norec;
                                    $namaprod = Produk::where('id', $sir->objectprodukfk)->first()->namaproduk;
                                    $this->LOGGING(
                                        'Akomodasi Otomatis',
                                        $PPnorec,
                                        'pasiendaftar_t',
                                        'Akomodasi Otomatis pada Pasien ' . $data2[0]->namapasien . ' (' . $data2[0]->nocm . ') - ' . $data2[0]->noregistrasi . ' - ' . $namaprod
                                    );

                                    $PelPasienPetugas = new PelayananPasienPetugas();
                                    $PelPasienPetugas->norec = $PelPasienPetugas->generateNewId();
                                    $PelPasienPetugas->kdprofile = $kdProfile;
                                    $PelPasienPetugas->statusenabled = true;
                                    $PelPasienPetugas->nomasukfk = $dateAPD->norec_apd;
                                    $PelPasienPetugas->objectpegawaifk = $dateAPD->dokter;
                                    $PelPasienPetugas->objectjenispetugaspefk = $this->settingFix('jenisPetugasDokterPemeriksa');
                                    $PelPasienPetugas->pelayananpasien = $PPnorec;
                                    $PelPasienPetugas->noregistrasi = $dateAPD->noregistrasi;
                                    $PelPasienPetugas->save();

                                    foreach ($buntutMacan as $itemKomponen) {
                                        if ($itemKomponen->objectprodukfk == $sir->objectprodukfk) {
                                            $PelPasienDetail = new PelayananPasienDetail();
                                            $PelPasienDetail->norec = $PelPasienDetail->generateNewId();
                                            $PelPasienDetail->kdprofile = $kdProfile;
                                            $PelPasienDetail->statusenabled = true;
                                            $PelPasienDetail->noregistrasifk = $dateAPD->norec_apd;
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
                                            $PelPasienDetail->tglpelayanan = $tglAwal;
                                            $PelPasienDetail->harganetto = $itemKomponen->harganetto1;
                                            $PelPasienDetail->noregistrasi = $dateAPD->noregistrasi;
                                            $PelPasienDetail->save();

                                            $diskon = 0;
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }

            DB::commit();

            $result = [
                "status" => 200,
                "message" => 'Sukses',
                "result" => [
                    "count" => $x,
                    "as" => '@epic',
                ],
            ];
        } catch (Exception $e) {
            DB::rollBack();
            $result = array(
                "status" => 400,
                "message" => "Simpan Gagal",
                "result" => $e->getMessage() . ' ' . $e->getLine()
            );
        }
        if ($lokal) {
            return $result;
        }
        return $this->respond($result['result'], $result['status'], $result['message']);
    }
}