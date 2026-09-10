<?php

namespace App\Http\Controllers\Laboratorium;

use App\Http\Controllers\Controller;
use App\Models\Master\DetailJenisProduk;
use App\Models\Master\JenisKelamin;
use App\Models\Master\JenisProduk;
use App\Models\Master\ProdukDetailLaboratorium;
use App\Models\Master\ProdukDetailLaboratoriumNilaiNormal;
use App\Models\Master\SatuanStandar;
use App\Traits\Valet;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Mockery\Exception\InvalidOrderException;
use Faker\Factory;

class PendukungPemeriksaanCtrl extends Controller
{
    use Valet;
    public function __construct()
    {
        parent::__construct($is_encrypt = true);
    }

    public function getJenisPemeriksaan(Request $request)
    {
        $kdProfile = $this->kdProfile;
        $jenis  = DB::table('detailjenisproduk_m as djp')
            ->leftjoin('jenisproduk_m as jp', 'jp.id', '=', 'djp.objectjenisprodukfk')
            ->leftjoin('departemen_m as dp', 'dp.id', '=', 'djp.objectdepartemenfk')
            ->select(
                'djp.id',
                'djp.objectjenisprodukfk',
                'jp.jenisproduk',
                'dp.namadepartemen',
                'djp.detailjenisproduk',
                'djp.statusenabled'
            )
            ->where('djp.kdprofile', $kdProfile)
            ->where('djp.objectdepartemenfk', $this->settingFix('idDepartemenLab'));
        if (isset($request['nama']) && $request['nama'] != 'undefined' && $request['nama'] != '') {
            $jenis = $jenis->where('djp.detailjenisproduk', 'ilike', '%' . $request['nama'] . '%');
        }
        if (isset($r['id']) && $r['id'] != '') {
            $jenis = $jenis->where('djp.id', '=',  $r['id']);
        }
        if (isset($r['detailjenisproduk']) && $r['detailjenisproduk'] != '') {
            $jenis = $jenis->where('djp.detailjenisproduk', 'ilike', '%' . $r['statuskeluar'] . '%');
        }
        if (isset($r['statusenabled']) && $r['statusenabled'] != '') {
            $jenis = $jenis->where('djp.statusenabled', '=', $r['statusenabled']);
        }
        // $jenis=  $jenis->limit(50);
        $jenis =  $jenis->get();

        foreach ($jenis as $d) {
            $d->statusenabled;
            $d->status = 'Aktif';
            $d->status_c = 'info';
            if ($d->statusenabled != 'false') {
                $d->status = 'Nonaktif';
                $d->status_c = 'danger';
            }
        }

        $res['data'] =  $jenis;

        return $this->respond($res);
    }

    public function LoadPendukung(Request $request)
    {
        $data = DB::table('mapruangantoproduk_m as mpr')
            ->join('ruangan_m as ru', 'ru.id', '=', 'mpr.objectruanganfk')
            ->join('departemen_m as dp', 'ru.objectdepartemenfk', 'dp.id')
            ->join('produk_m as prd', 'prd.id', '=', 'mpr.objectprodukfk')
            ->select(
                'mpr.objectprodukfk as id',
                'prd.namaproduk',
                'mpr.objectruanganfk',
                'prd.namaproduk'
            )
            ->where('mpr.kdprofile', $this->kdProfile)
            ->whereIN('dp.id', explode(',', $this->settingFix('idDepartemenLab')))
            ->where('mpr.statusenabled', true);

        if (
            isset($r['name']) &&
            $r['name'] != "" &&
            $r['name'] != "undefined"
        ) {
            $data = $data
                ->where('prd.namaproduk', 'ilike', '%' . $r['name'] . '%');
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
        $res['data'] = $data;
        $res['jenisproduk'] = JenisProduk::mine()->get();

        return $this->respond($res);
    }

    public function saveJenisPemeriksaan(Request $request)
    {
        DB::beginTransaction();
        try {
            $departemen = $this->settingFix('idDepartemenLab');
            $faker = Factory::create();
            $count = DetailJenisProduk::count();
            $PSN =  $request['detailjenisproduk'];
            $codeUnique = $count < 1 ? 1 : $count + 1;

            if ($PSN['id'] == '') {
                $data = new DetailJenisProduk();
                $data->id = $this->SEQUENCE_MASTER(new DetailJenisProduk(), 'id', $this->kdProfile); ///(string)Uuid::uuid4();
                $data->kodeexternal = $faker->regexify('[A-Z]{2}-') . $codeUnique;
                $data->statusenabled = true;
                $data->qdetailjenisproduk = $codeUnique;
                $transMessage = "Proses Simpan Data Berhasil";
            } else {
                $data = DetailJenisProduk::where('id', $PSN['id'])->first();
                $data->statusenabled = $PSN['statusenabled'];
                $transMessage = "Proses Update Data Berhasil";
                $data->id;
            }
            $data->kdprofile = $this->kdProfile;
            $data->detailjenisproduk =  $PSN['detailjenisproduk'];
            $data->objectjenisprodukfk =  $PSN['objectjenisprodukfk'];
            $data->objectdepartemenfk =  $departemen;
            $data->reportdisplay =  $PSN['detailjenisproduk'];
            $data->save();

            DB::commit();

            $result = [
                "statusCode" => 201,
                "message" => $transMessage,
                "result" => [
                    "data"  => $data,
                    "as" => '@epic',
                ],
            ];
        } catch (InvalidOrderException $e) {
            DB::rollBack();
            $result = array(
                "statusCode" => $e->getCode(),
                "message" => "Simpan Gagal",
                "result"  => $e->getMessage()

            );
        }

        return $this->respond($result['result'], $result['statusCode'], $result['message']);
    }

    public function deleteJenisPemeriksaan(Request $r)
    {
        DB::beginTransaction();
        try {
            $data = DetailJenisProduk::where('id', $r['id'])
                ->update([
                    'statusenabled' => false
                ]);
            DB::commit();
            $result = [
                "statusCode" => 201,
                "message" => "Proses Hapus Data Berhasil",
                "result" => [
                    "data"  => $data,
                    "as" => '@epic',
                ]
            ];
        } catch (InvalidOrderException $e) {
            DB::rollBack();
            $result = [
                "statusCode" => $e->getCode(),
                "message" => "Something Went Wrong",
                "result"  => [
                    "messageError" => $e->getMessage(),
                ]
            ];
        }
        return $this->respond($result['result'], $result['statusCode'], $result['message']);
    }

    public function getSatuanHasil(Request $r)
    {
        $data = DB::table('satuanstandar_m as ss')
            ->leftjoin('departemen_m as dp', 'dp.id', 'ss.objectdepartemenfk')
            ->select(
                'ss.id',
                'ss.satuanstandar',
                'ss.statusenabled',
                'ss.objectdepartemenfk',
            )
            ->where('ss.kdprofile', $this->kdProfile)
            ->where('ss.objectdepartemenfk', $this->settingFix('idDepartemenLab'));

        if (isset($r['satuanstandar']) && $r['satuanstandar'] != '') {
            $data = $data->where('ss.satuanstandar', 'ilike', '%' . $r['satuanstandar'] . '%');
        }

        if (isset($r['statusenabled']) && $r['statusenabled'] != '') {
            $data = $data->where('ss.statusenabled', '=', $r['statusenabled']);
        }

        $data = $data->orderByDesc('ss.satuanstandar');
        $data = $data->get();
        foreach ($data as $d) {
            $d->statusenabled;
            $d->status = 'Aktif';
            $d->status_c = 'info';
            if ($d->statusenabled != 'false') {
                $d->status = 'Nonaktif';
                $d->status_c = 'danger';
            }
        }

        $res['data'] = $data;
        return $this->respond($res);
    }

    public function saveSatuanHasil(Request $request)
    {
        DB::beginTransaction();
        try {
            $faker = Factory::create();
            $count = SatuanStandar::count();
            $idDep = $this->settingFix('idDepartemenLab');
            $PSN =  $request['satuanstandar'];
            $codeUnique = $count < 1 ? 1 : $count + 1;

            if ($PSN['id'] == '') {
                $data = new SatuanStandar();
                $data->id = $this->SEQUENCE_MASTER(new SatuanStandar(), 'id', $this->kdProfile); //(string)Uuid::uuid4();
                $data->kodeexternal = $faker->regexify('[A-Z]{2}-') . $codeUnique;
                $data->statusenabled = true;
                $transMessage = "Proses Simpan Data Berhasil";
            } else {
                $data = SatuanStandar::where('id', $PSN['id'])->first();
                $data->statusenabled = $PSN['statusenabled'];
                $transMessage = "Proses Update Data Berhasil";
                $id =  $data->id;
            }
            $data->kdprofile = (int)$this->kdProfile;
            $data->satuanstandar =  $PSN['satuanstandar'];
            $data->namaexternal =  $PSN['satuanstandar'];
            $data->reportdisplay =  $PSN['satuanstandar'];
            $data->objectdepartemenfk = $idDep;
            $data->save();

            DB::commit();

            $result = [
                "status" => 200,
                "result" => [
                    "data"  => $data,
                    "as" => '@epic',
                ],
            ];
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

    public function deleteSatuanHasil(Request $r)
    {
        DB::beginTransaction();
        try {
            $data = SatuanStandar::where('id', $r['id'])
                ->update([
                    'statusenabled' => false
                ]);
            DB::commit();
            $result = [
                "statusCode" => 201,
                "message" => "Proses Hapus Data Berhasil",
                "result" => [
                    "data"  => $data,
                    "as" => '@epic',
                ]
            ];
        } catch (InvalidOrderException $e) {
            DB::rollBack();
            $result = [
                "statusCode" => $e->getCode(),
                "message" => "Something Went Wrong",
                "result"  => [
                    "messageError" => $e->getMessage(),
                ]
            ];
        }
        return $this->respond($result['result'], $result['statusCode'], $result['message']);
    }
    public function getNilaiNormal(Request $r)
    {
        $data = DB::table('nilainormal_m as nn')
            ->leftjoin('kelompokumur_m as kpu', 'kpu.id', 'nn.kelompokumurfk')
            ->leftjoin('jeniskelamin_m as jk', 'jk.id', 'nn.objectjeniskelaminfk')
            ->select(
                'nn.id',
                'nn.nilaitext',
                'nn.nilaimin',
                'nn.nilaimax',
                'nn.objectjeniskelaminfk',
                'kpu.kelompokumur',
                'jk.jeniskelamin',
                'nn.statusenabled',
                'nn.kelompokumurfk'
            )
            ->where('nn.kdprofile', $this->kdProfile);

        if (isset($r['nilaitext']) && $r['nilaitext'] != '') {
            $data = $data->where('nn.nilaitext', 'ilike', '%' . $r['nilaitext'] . '%');
        }

        if (isset($r['statusenabled']) && $r['statusenabled'] != '') {
            $data = $data->where('nn.statusenabled', '=', $r['statusenabled']);
        }
        $data =  $data->limit(50);

        $data = $data->orderByDesc('nn.nilaimin');
        $data = $data->get();
        foreach ($data as $d) {
            $d->statusenabled;
            $d->status = 'Aktif';
            $d->status_c = 'info';
            if ($d->statusenabled != 'false') {
                $d->status = 'Nonaktif';
                $d->status_c = 'danger';
            }
        }

        $res['data'] = $data;
        return $this->respond($res);
    }

    public function getMapHasilLab(Request $request)
    {
        $kdProfile = $this->kdProfile;
        $jenis  = DB::table('produkdetaillaboratorium_m as map')

            ->join('produk_m as prd', 'prd.id', '=', 'map.produkfk')
            ->leftjoin('detailjenisproduk_m as djp', 'djp.id', '=', 'prd.objectdetailjenisprodukfk')
            ->leftjoin('satuanstandar_m as ss', 'ss.id', '=', 'map.satuanstandarfk')
            ->select('map.*', 'prd.namaproduk', 'ss.satuanstandar', 'djp.detailjenisproduk')
            ->where('map.kdprofile', $kdProfile)
            ->where('map.statusenabled', true);

        if (isset($request['detailPem']) && $request['detailPem'] != 'undefined' && $request['detailPem'] != '') {
            $jenis = $jenis->where('map.detailpemeriksaan', 'ilike', '%' . $request['detailPem'] . '%');
        }
        if (isset($request['produk']) && $request['produk'] != "" && $request['produk'] != "undefined") {
            $jenis = $jenis->where('map.produkfk', $request['produk']);
        }

        $jenis =  $jenis->limit(50);
        $jenis =  $jenis->get();

        foreach ($jenis as $key => $value) {
            $id =  $value->id;
            $detail = DB::select(DB::raw("select maps.*,jk.jeniskelamin
                 from produkdetaillaboratoriumnilainormal_m as maps
                join jeniskelamin_m as jk on jk.id =maps.jeniskelaminfk
                where maps.produkdetaillabfk ='$id'"));
            $value->details = $detail;
        }
        $result =  array(
            'data' => $jenis,
        );
        return $this->respond($result);
    }

    public function getLayananDD(Request $request)
    {
        $datas = DB::table('mapruangantoproduk_m as mpr')

            ->leftjoin('produk_m as prd', 'prd.id', '=', 'mpr.objectprodukfk')
            ->leftjoin('ruangan_m as ru', 'ru.id', '=', 'mpr.objectruanganfk')
            ->leftjoin('departemen_m as dp', 'dp.id', '=', 'ru.objectdepartemenfk')
            ->select(
                'prd.id as idpro',
                'prd.namaproduk',
            )
            ->where('prd.namaproduk', 'ILIKE', '%' . $request['namaproduk'] . '%')
            ->where('mpr.kdprofile', $this->kdProfile)
            ->whereIN('ru.objectdepartemenfk', explode(',', $this->settingFix('idDepartemenLab')))
            ->where('mpr.statusenabled', true)
            ->distinct()
            // ->limit(20)
            ->orderBy('prd.namaproduk','asc');
        $datas = $datas->get();

        $res['satuanstandar'] = SatuanStandar ::mine()->get();
        $res['jeniskelamin'] = JenisKelamin ::mine()->get();

        $res['layanan'] = $datas;

        return $this->respond($res);
    }

    public function saveDetailPemeriksaan(Request $request)
    {
        DB::beginTransaction();
        try {
            $PSN =  $request['produkdetaillaborat'];

            if ($PSN['id'] == '') {
                $data = new ProdukDetailLaboratorium();
                $data->id = $this->SEQUENCE_MASTER(new ProdukDetailLaboratorium(), 'id', $this->kdProfile); ///(string)Uuid::uuid4();
                $data->kdprofile = $this->kdProfile;
                $transMessage = "Proses Simpan Data Berhasil";
            } else {
                $data = ProdukDetailLaboratorium::where('id', $PSN['id'])->first();
                $data->statusenabled = $PSN['statusenabled'];
                $transMessage = "Proses Update Data Berhasil";
                $data->id;
            }
            $data->statusenabled = true;
            $data->kdprofile = $this->kdProfile;
            $data->produkfk =  $PSN['produkfk'];
            $data->detailpemeriksaan = $PSN['detailpemeriksaan'];
            $data->satuanstandarfk = $PSN['satuanstandarfk'];
            $data->tipedatahasil =  $PSN['tipedatahasil'];
            $data->nourut =  $PSN['nourut'];
            $data->save();

            foreach ($PSN['details'] as $item) {
                $dataOP = new ProdukDetailLaboratoriumNilaiNormal();
                $dataOP->id = $this->SEQUENCE_MASTER(new ProdukDetailLaboratoriumNilaiNormal(), 'id', $this->kdProfile);
                $dataOP->kdprofile = $this->kdProfile;
                $dataOP->statusenabled = true;
                $dataOP->produkdetaillabfk = $data['id'];
                $dataOP->jeniskelaminfk = $item['jeniskelaminfk'];
                $dataOP->rangemin = $item['rangemin'];
                $dataOP->rangemax = $item['rangemax'];

                $dataOP->agemin = $item['agemin'];
                $dataOP->agemax = $item['agemax'];
                $dataOP->ageunit = $item['ageunit'];
                // return ( $data['id']);
                $dataOP->save();
            }

            DB::commit();

            $result = [
                "statusCode" => 201,
                "message" => $transMessage,
                "result" => [
                    "data"  => $data,
                    "as" => '@epic',
                ],
            ];
        } catch (Exception $e) {
            DB::rollBack();
            $result = array(
                "statusCode" => 400,
                "message" => "Simpan Gagal",
                "result"  => $e->getMessage()

            );
        }

        return $this->respond($result['result'], $result['statusCode'], $result['message']);
    }
}
