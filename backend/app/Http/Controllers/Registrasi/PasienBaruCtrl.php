<?php

namespace App\Http\Controllers\Registrasi;

use App\Http\Controllers\Controller;
use App\Models\Master\Agama;
use App\Models\Master\Alamat;
use App\Models\Master\DesaKelurahan;
use App\Models\Master\GolonganDarah;
use App\Models\Master\HubunganKeluarga;
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
use App\Models\Transaksi\PasienDaftar;
use App\Models\Master\Suku;
use App\Models\Transaksi\AntrianPasienRegistrasi;
use App\Models\Transaksi\EMRPasien;
use App\Traits\Valet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class PasienBaruCtrl extends Controller
{
    use Valet;
    public function __construct()
    {
        parent::__construct($is_encrypt = true);
    }
    public function listDesaKelurahanPaging(Request $req)
    {

        if (isset($req['namadesakelurahan'])) {
            $explode = explode(',', $req['namadesakelurahan']);
            if (count($explode) > 1) {
                $namaDesa = $explode[0];
                $namaKec = $explode[1];
            }
        }
        $Desa = DB::table('desakelurahan_m as ds')
            ->join('kecamatan_m as kc', 'ds.objectkecamatanfk', '=', 'kc.id')
            ->join('kotakabupaten_m as kk', 'ds.objectkotakabupatenfk', '=', 'kk.id')
            ->join('propinsi_m as pp', 'ds.objectpropinsifk', '=', 'pp.id')
            ->select(DB::raw("ds.id
                             ,UPPER(ds.namadesakelurahan) as namadesakelurahan
                             ,ds.kodepos
                             ,ds.objectkecamatanfk
                             ,ds.objectkotakabupatenfk
                             ,ds.objectpropinsifk
                             ,UPPER(kc.namakecamatan) as namakecamatan
                             ,UPPER(kk.namakotakabupaten) as namakotakabupaten
                             ,UPPER( pp.namapropinsi) as namapropinsi"))
            ->where('ds.statusenabled', true)
            ->where('kc.statusenabled', true)
            ->where('kk.statusenabled', true)
            ->where('pp.statusenabled', true)
            ->where('ds.kdprofile', $this->kdProfile)
            ->orderBy('ds.namadesakelurahan');

        // if (
        //     isset($req['namadesakelurahan']) &&
        //     $req['namadesakelurahan'] != "" &&
        //     $req['namadesakelurahan'] != "undefined"
        // ) {
        //     $Desa = $Desa->where('ds.namadesakelurahan', 'ilike', '%' . $req['namadesakelurahan'] . '%');
        // };
        if (
            isset($req['namakecamatan']) &&
            $req['namakecamatan'] != "" &&
            $req['namakecamatan'] != "undefined"
        ) {
            $Desa = $Desa->where('kc.namakecamatan', 'ilike', '%' . $req['namakecamatan'] . '%');
        };
        if (
            isset($req['iddesakelurahan']) &&
            $req['iddesakelurahan'] != "" &&
            $req['iddesakelurahan'] != "undefined"
        ) {
            $Desa = $Desa->where('ds.id', $req['iddesakelurahan']);
        };
        if (
            isset($req['namadesakelurahan']) &&
            $req['namadesakelurahan'] != "" &&
            $req['namadesakelurahan'] != "undefined"
        ) {
            if (isset($namaDesa) && isset($namaKec) && $namaDesa != '' && $namaKec != '') {
                $Desa = $Desa
                    ->where('ds.namadesakelurahan', 'ilike', '%' . $namaDesa . '%')
                    ->where('kc.namakecamatan', 'ilike', '%' . $namaKec . '%');
            }
            if (isset($namaDesa) && !isset($namaKec)) {
                $Desa = $Desa
                    ->where('ds.namadesakelurahan', 'ilike', '%' . $namaDesa . '%');
            }
            if (!isset($namaDesa) && !isset($namaKec)) {
                $Desa = $Desa
                    ->where('ds.namadesakelurahan', 'ilike', '%' . $req['namadesakelurahan'] . '%');
            }
        }

        $Desa = $Desa->take(10);
        $Desa = $Desa->get();
        $tempDesa = [];
        if (count($Desa) != 0) {
            foreach ($Desa as $item) {
                $tempDesa[] = array(
                    'id' => $item->id,
                    'namadesakelurahan' => $item->namadesakelurahan,
                    'kodepos' => $item->kodepos,
                    'namakecamatan' => $item->namakecamatan,
                    'namakotakabupaten' => $item->namakotakabupaten,
                    'namapropinsi' => $item->namapropinsi,
                    'desa' => $item->namadesakelurahan . ', ' . $item->namakecamatan . ',  ' . $item->namakotakabupaten . ', ' .
                        $item->namapropinsi,
                    'objectkecamatanfk' => $item->objectkecamatanfk,
                    'objectkotakabupatenfk' => $item->objectkotakabupatenfk,
                    'objectpropinsifk' => $item->objectpropinsifk,
                );
            }
        }
        return $this->respond($tempDesa);
    }

    public function listKecamatanPaging(Request $req)
    {
        $kecamatan = DB::table('kecamatan_m as ru')
            ->select(DB::raw("ru.id, UPPER(ru.namakecamatan) as namakecamatan"))
            // ->where('ru.statusenabled', true)
            ->orderBy('ru.namakecamatan');

        if (
            isset($req['namakecamatan']) &&
            $req['namakecamatan'] != "" &&
            $req['namakecamatan'] != "undefined"
        ) {
            $kecamatan = $kecamatan->where('ru.namakecamatan', 'ilike', '%' .  $req['namakecamatan'] . '%');
        };


        $kecamatan = $kecamatan->take(10);
        $kecamatan = $kecamatan->get();
        return $this->respond($kecamatan);
    }
    public function listDropdown(Request $r)
    {
        $res['jk'] = JenisKelamin::mine()->get();
        $res['agama'] = Agama::mine()->get();
        $res['statusperkawinan'] = StatusPerkawinan::mine()->get();
        $res['golongandarah'] = GolonganDarah::mine()->get();
        $res['pendidikan'] = Pendidikan::mine()->get();
        $res['pekerjaan'] = Pekerjaan::mine()->get();
        $res['etnis'] = Suku::mine()->get();
        $res['hubunganpasien'] = HubunganKeluarga::mine()->get();
        $res['kebangsaan'] = Kebangsaan::mine()->get();
        $res['negara'] = Negara::mine()->get();
        // $res['kotakabupaten'] = KotaKabupaten::mine()->get();
        $res['provinsi'] = Provinsi::mine()->orderBy('namapropinsi')->get();
        // $res['desaKelurahan'] = DesaKelurahan::mine()->get();


        return $this->respond($res);
    }


    public function listDesaNew(Request $r)
    {
        $data = DB::table('desakelurahan_m as dk')
            ->join('kecamatan_m as kc', 'kc.id', '=', 'dk.objectkecamatanfk')
            ->join('kotakabupaten_m as kk', 'kk.id', '=', 'dk.objectkotakabupatenfk')
            ->join('propinsi_m as prop', 'prop.id', '=', 'dk.objectpropinsifk')
            ->select(
                'dk.id as id_dk',
                'dk.namadesakelurahan',
                'dk.objectkecamatanfk',
                'dk.objectkotakabupatenfk',
                'dk.objectpropinsifk',
                'kc.namakecamatan',
                'kk.namakotakabupaten',
                'prop.namapropinsi'
            )
            ->where('dk.statusenabled', true);

        if (!empty($r['id'])) {
            $data = $data->where('dk.id', $r['id']);
        }
        if (isset($r['limit']) && $r['limit'] != '') {
            $data = $data->limit($r['limit']);
        }
        if (isset($r['namadesakelurahan']) && $r['namadesakelurahan'] != '') {
            $searchTerm = '%' . $r['namadesakelurahan'] . '%';
            $data = $data->where(function ($query) use ($searchTerm) {
                $query->where('dk.namadesakelurahan', 'ilike', $searchTerm);
            });
        }
        $data = $data->get();
        if (isset($r['json_encode']) && $r['json_encode'] == true) {
            return response()->json($data);  // Encode the result in JSON format
        }

        return $this->respond($data);  // Normal response without encoding
    }
    public function listProvinsiNew(Request $r)
    {
        $data = DB::table('propinsi_m as prop')
            ->select(
                'prop.namapropinsi', 'prop.id'
            )
            ->where('prop.statusenabled', true);

        if (isset($r['limit']) && $r['limit'] != '') {
            $data = $data->limit($r['limit']);
        }
        if (isset($r['namapropinsi']) && $r['namapropinsi'] != '') {
            $searchTerm = '%' . $r['namapropinsi'] . '%';
            $data = $data->where(function ($query) use ($searchTerm) {
                $query->where('prop.namapropinsi', 'ilike', $searchTerm);
            });
        }
        $data = $data->get();
        if (isset($r['json_encode']) && $r['json_encode'] == true) {
            return response()->json($data);  // Encode the result in JSON format
        }

        return $this->respond($data);  // Normal response without encoding
    }

    public function listKabupatenNew(Request $r)
    {
        $data = DB::table('kotakabupaten_m as kk')
            ->select('kk.id', 'kk.namakotakabupaten')
            ->where('kk.statusenabled', true);

        if (!empty($r['id'])) {
            $data = $data->where('kk.id', $r['id']);
        }
        if (isset($r['namakotakabupaten']) && $r['namakotakabupaten'] != '') {
            $searchTerm = '%' . $r['namakotakabupaten'] . '%';
            $data = $data->where(function ($query) use ($searchTerm) {
                $query->where('kk.namakotakabupaten', 'ilike', $searchTerm);
            });
        }
        $data = $data->get();
        if (isset($r['json_encode']) && $r['json_encode'] == true) {
            return response()->json($data);  // Encode the result in JSON format
        }

        return $this->respond($data);  // Normal response without encoding
    }
    public function listKecamatanNew(Request $r)
    {
        $data = DB::table('kecamatan_m as kc')
            ->select('kc.id','kc.namakecamatan')
            ->where('kc.statusenabled', true);

        if (!empty($r['id'])) {
            $data = $data->where('kc.id', $r['id']);
        }
        if (isset($r['limit']) && $r['limit'] != '') {
            $data = $data->limit($r['limit']);
        }
        if (isset($r['namakecamatan']) && $r['namakecamatan'] != '') {
            $searchTerm = '%' . $r['namakecamatan'] . '%';
            $data = $data->where(function ($query) use ($searchTerm) {
                $query->where('kc.namakecamatan', 'ilike', $searchTerm);
            });
        }
        $data = $data->get();
        if (isset($r['json_encode']) && $r['json_encode'] == true) {
            return response()->json($data);  // Encode the result in JSON format
        }

        return $this->respond($data);  // Normal response without encoding
    }
    public function listKotaKab(Request $r)
    {
        $res['kotakabupaten'] = KotaKabupaten::mine();
        if (isset($r['provfk']) && $r['provfk'] != '') {
            $res['kotakabupaten'] = $res['kotakabupaten']->where('objectpropinsifk', $r['provfk']);
        }
        $res['kotakabupaten'] = $res['kotakabupaten']
            ->orderby('namakotakabupaten')
            ->get();
        return $this->respond($res);
    }
    public function listKecamatan(Request $r)
    {
        $res['kecamatan'] = Kecamatan::mine();
        if (isset($r['kotafk']) && $r['kotafk'] != '') {
            $res['kecamatan'] = $res['kecamatan']->where('objectkotakabupatenfk', $r['kotafk']);
        }
        $res['kecamatan'] = $res['kecamatan']
            ->orderby('namakecamatan')
            ->get();
        return $this->respond($res);
    }
    public function listDesa(Request $r)
    {
        $res['desa'] = DesaKelurahan::mine();
        if (isset($r['kecfk']) && $r['kecfk'] != '') {
            $res['desa'] = $res['desa']->where('objectkecamatanfk', $r['kecfk']);
        }
        $res['desa'] = $res['desa']
            ->orderby('namadesakelurahan')
            ->get();
        return $this->respond($res);
    }
    public function savePasien(Request $r)
    {
        if(isset($r['pasien']['isjkn']) && $r['pasien']['isjkn'] == true) {
        }else{
        DB::beginTransaction();
        }

        try {
            //region Save Pasien
            $SET['idRunningNumberPasien'] = $this->settingFix('idRunningNumberPasien');
            $SET['idRunningNumberPasienBayi'] = $this->settingFix('idRunningNumberPasienBayi');
            $SET['HubunganKeluargaSendiri'] = $this->settingFix('HubunganKeluargaSendiri');
            $SET['jenisAlamatRumah'] = $this->settingFix('jenisAlamatRumah');

            $PSN =  $r['pasien'];
            $ALM =  $r['alamat'];
            $idProfile = $this->kdProfile;
            // dd($r->all());
            // return $PSN;
            if ($PSN['id'] == '') {
                if (
                    isset($PSN['noidentitas'])
                    && $PSN['noidentitas'] != '-'
                    && $PSN['noidentitas'] != ''
                    && $PSN['noidentitas'] != '9999999999999999'
                ) {
                    $cek = Pasien::where('noidentitas', $PSN['noidentitas'])
                        ->where('statusenabled', true)->first();
                    if (!empty($cek)) {
                        $transMessage = "NIK ini sudah terdaftar sebagai " . $cek->namapasien . ' (' . $cek->nocm . ')';
                        DB::rollBack();
                        $result = array(
                            "status" => 400,
                            "result"  => null,
                        );
                        return $this->respond($result['result'], $result['status'], $transMessage);
                    }
                }
                if (
                    isset($PSN['norm'])
                    && $PSN['norm'] != '-'
                    && $PSN['norm'] != ''
                    && $PSN['norm'] != '999999'
                ) {
                    $cek = Pasien::where('nocm', $PSN['norm'])
                        ->where('statusenabled', true)->first();
                    if (!empty($cek)) {
                        $transMessage = "NoRM ini sudah terdaftar sebagai " . $cek->namapasien . ' (' . $cek->nocm . ')';
                        DB::rollBack();
                        $result = array(
                            "status" => 400,
                            "result"  => null,
                        );
                        return $this->respond($result['result'], $result['status'], $transMessage);
                    }
                }

                //? Generate Nomor RM
                if (isset($PSN['isPenunjang']) &&  $PSN['isPenunjang'] == true
                ) {
                    $noCm = $this->SEQUENCE(new Pasien, 'nocm_penunjang', 9, 'P', $idProfile);
                }
                else if ( isset($PSN['isJenazah']) &&  $PSN['isJenazah'] == true
                ) {
                    $noCm = $this->SEQUENCE(new Pasien, 'nocm_jenazah', 9, 'J', $idProfile);
                }
                else if (isset($PSN['isTelemedicine'])  &&  $PSN['isTelemedicine'] == true) {
                    $noCm = $this->SEQUENCE(new Pasien, 'nocm_telemedicine', 9, 'T', $idProfile);
                }
                else if (isset($PSN['norm']) && $PSN['norm'] != '-' && $PSN['norm'] != '' && $PSN['norm'] != '999999') {
                    $noCm = str_replace('.','',$PSN['norm']);
                } else {
                    $idRunningNumber = $SET['idRunningNumberPasien'];
                    $runningNumber = RunningNumber::where('id', $idRunningNumber)->where('kdprofile', $idProfile)->first();
                    $noCm = $this->SEQUENCE(new Pasien, 'nocm',  (float)$runningNumber->length, $runningNumber->prefix, $idProfile);
                    RunningNumber::where('id', $idRunningNumber)
                        ->where('kdprofile', $idProfile)
                        ->update(
                            [
                                'nomer_terbaru' => $noCm
                            ]
                        );
                }

                $dataPS = new Pasien();
                $newId = $dataPS->generateNewId();
                $dataPS->id = $newId;
                $dataPS->kdprofile = (int)$this->kdProfile;
                $dataPS->statusenabled = true;
                $dataPS->norec = $newId;
                $dataPS->istelemedicine = isset($PSN['isTelemedicine']) ? $PSN['isTelemedicine'] : false;
                $dataPS->nocm = $noCm;
            } else {
                $dataPS = Pasien::where('id', $PSN['id'])
                    ->where('statusenabled', true)
                    ->first();
                if (
                    isset($PSN['norm'])
                    && $PSN['norm'] != '-'
                    && $PSN['norm'] != ''
                    && $PSN['norm'] != '999999'
                ) {
                    $cek = Pasien::where('nocm', $PSN['norm'])
                        ->where('statusenabled', true)->first();
                    if (!empty($cek)) {
                        $transMessage = "NoRM ini sudah terdaftar sebagai " . $cek->namapasien . ' (' . $cek->nocm . ')';
                        DB::rollBack();
                        $result = array(
                            "status" => 400,
                            "result"  => null,
                        );
                        return $this->respond($result['result'], $result['status'], $transMessage);
                    }
                    $noCm = $PSN['norm'];
                }else {
                    $noCm = $dataPS->nocm;
                }
            }

            $dataPS->namaexternal =  $PSN['namapasien'];
            $dataPS->reportdisplay =  $PSN['namapasien'];
            $dataPS->objectagamafk = $PSN['objectagamafk'];
            $dataPS->objectjeniskelaminfk = $PSN['objectjeniskelaminfk'];
            $dataPS->namapasien = $PSN['namapasien'];
            $dataPS->objectpekerjaanfk = $PSN['objectpekerjaanfk'];
            $dataPS->objectpendidikanfk = $PSN['objectpendidikanfk'];
            $dataPS->qpasien = 1;
            $dataPS->nocmfkibu =  $PSN['nocmfkibu'];
            $dataPS->objectstatusperkawinanfk = $PSN['objectstatusperkawinanfk'];
            $dataPS->tgldaftar = date('Y-m-d H:i:s');
            $dataPS->tgllahir = isset($PSN['tgllahir']) ? $PSN['tgllahir'] : null;
            $dataPS->namaibu = $PSN['namaibu'];
            $dataPS->notelepon = $PSN['notelepon'];
            $dataPS->noidentitas = $PSN['noidentitas'];
            $dataPS->noaditional = $PSN['noaditional'];
            $dataPS->objectkebangsaanfk = $PSN['objectkebangsaanfk'];
            $dataPS->objectnegarafk = $PSN['objectnegarafk'];
            $dataPS->namaayah = $PSN['namaayah'];
            $dataPS->namasuamiistri = isset($PSN['namasuamiistri']) ? $PSN['namasuamiistri'] : null;
            $dataPS->noasuransilain = $PSN['noaditional'];
            $dataPS->nobpjs = $PSN['nobpjs'];
            $dataPS->paspor = isset($PSN['paspor']) ? $PSN['paspor'] : null;
            $dataPS->nohp = isset($PSN['nohp']) ? $PSN['nohp'] : null;
            $dataPS->tempatlahir = $PSN['tempatlahir'];
            $dataPS->namakeluarga =  isset($PSN['namakeluarga']) ? $PSN['namakeluarga'] : null;
            $dataPS->jamlahir =  isset($PSN['jamlahir']) ? $PSN['jamlahir'] : null;
            $dataPS->objectgolongandarahfk = $PSN['objectgolongandarahfk'];
            $dataPS->objectsukufk = $PSN['objectsukufk'];
            $dataPS->penanggungjawab = isset($PSN['penanggungjawab']) ? $PSN['penanggungjawab'] : null;
            $dataPS->hubungankeluargapj = isset($PSN['hubungankeluargapj']) ? $PSN['hubungankeluargapj'] : null;
            $dataPS->pekerjaanpenangggungjawab = isset($PSN['pekerjaanpenangggungjawab']) ? $PSN['pekerjaanpenangggungjawab'] : null;
            $dataPS->telponpenanggungjawab = isset($PSN['telponpenanggungjawab']) ? $PSN['telponpenanggungjawab'] : null;
            $dataPS->jeniskelaminpenanggungjawab = isset($PSN['jeniskelaminpenanggungjawab']) ? $PSN['jeniskelaminpenanggungjawab'] : null;
            $dataPS->bahasa = isset($PSN['bahasa']) ? $PSN['bahasa'] : null;
            $dataPS->umurpenanggungjawab = isset($PSN['umurpenanggungjawab']) ? $PSN['umurpenanggungjawab'] : null;
            // $dataPS->pekerjaanpenangggungjawab = isset($PSN['pekerjaanpenangggungjawab']) ? $PSN['pekerjaanpenangggungjawab'] : null;
            $dataPS->alamatrmh = isset($PSN['alamatrmh']) ? $PSN['alamatrmh'] : null;
            $dataPS->rujukanrs = isset($PSN['rujukanrs']) ? $PSN['rujukanrs'] : null;
            $dataPS->penjaminanrs = isset($PSN['penjaminanrs']) ? $PSN['penjaminanrs'] : null;
            $dataPS->riwayattambahan = isset($PSN['riwayattambahan']) ? $PSN['riwayattambahan'] : null;
            $dataPS->alamatlengkap = isset($ALM['alamatlengkap']) ? $ALM['alamatlengkap'] : null;
            $dataPS->isjkn = isset($PSN['isjkn']) ? $PSN['isjkn'] : null;
            $dataPS->nomorkk = isset($PSN['nomorkk']) ? $PSN['nomorkk'] : null;
            $dataPS->foto = isset($PSN['foto']) ? $PSN['foto'] : null;
            $dataPS->email = isset($PSN['email']) ? $PSN['email'] : null;
            $dataPS->progress = isset($PSN['progress']) ? (int) $PSN['progress'] : 0;
            $dataPS->nocmpj = isset($PSN['nocmpj']) ? $PSN['nocmpj'] : null;
            if (
                isset($PSN['norm'])
                && $PSN['norm'] != '-'
                && $PSN['norm'] != ''
                && $PSN['norm'] != '999999'
            ) {
                $dataPS->nocm = $noCm;
            }

            $dataPS->save();
            $nocmfk = $dataPS->id;
            if (isset($PSN['isReservasi']) && $PSN['isReservasi'] == true) {
                AntrianPasienRegistrasi::where('norec' ,$PSN['antrianpasienregistrasifk'])->update(['nocmfk' => $nocmfk]);
            }
            if (isset($PSN['isIGD']) && $PSN['isIGD'] == true) {
                EMRPasien::where('norec' ,$PSN['norecEMR'])->update([
                    'nocm' => $noCm,
                    'nocmfk' => $nocmfk
                ]);
            }
            if (isset($PSN['kode_pasien_baru']) && $PSN['kode_pasien_baru'] == true) {
                PasienDaftar::where('kode_pasien_baru' ,$PSN['kode_pasien_baru'])->update(['nocmfk' => $nocmfk]);
            }
            if ($PSN['id'] == '') {
                $dataAL = new Alamat();
                $idAlamat = $dataAL->generateNewId();
                $dataAL->id = $idAlamat;
                $dataAL->kdprofile = (int)$idProfile;;
                $dataAL->statusenabled = true;
                // $dataAL->kodeexternal = $idAlamat;
                $dataAL->norec = $idAlamat;
            } else {
                $dataAL = Alamat::where('nocmfk', $nocmfk)->first();
                $idAlamat = $dataAL->id;
            }

            $dataAL->alamatlengkap = $ALM['alamatlengkap'];
            $dataAL->rtrw = $ALM['rtrw'];
            $dataAL->objecthubungankeluargafk =  $SET['HubunganKeluargaSendiri'];
            $dataAL->objectdesakelurahanfk = $ALM['objectdesakelurahanfk'];
            $dataAL->objectjenisalamatfk = $SET['jenisAlamatRumah'];
            $dataAL->kdalamat = $idAlamat;
            $dataAL->objectkecamatanfk = $ALM['objectkecamatanfk'];
            $dataAL->kodepos = $ALM['kodepos'];
            $dataAL->objectkotakabupatenfk = $ALM['objectkotakabupatenfk'];
            $dataAL->objectnegarafk = $PSN['objectnegarafk'];
            $dataAL->nocmfk = $nocmfk;
            $dataAL->objectpegawaifk = $this->getPegawai()->id;
            $dataAL->objectpropinsifk = $ALM['objectpropinsifk'];
            $dataAL->save();
            //endregion

            $transStatus = 'true';
        } catch (\Exception $e) {
            $transStatus = 'false';
        }

        if ($transStatus == 'true') {
            $transMessage = "Sukses";
            if(isset($r['pasien']['isjkn']) && $r['pasien']['isjkn'] == true) {
            }else{
            DB::commit();
            }
            if (isset($PSN['isjkn']) && $PSN['isjkn'] == true) {
                $result = array(
                    "response" => array(
                        "norm" => $dataPS->nocm
                    ),
                    "metadata" =>
                    array(
                        'message' => "Harap datang ke admisi untuk melengkapi data rekam medis",
                        'code' => '200',
                    )
                );
                return response()->json($result);
            } else {
                $result = array(
                    "status" => 200,
                    "result" => array(
                        "data"  => $dataPS,
                        "as" => '@epic',
                    ),
                );
            }
        } else {
            $transMessage = "Simpan Gagal";
            if(isset($r['pasien']['isjkn']) && $r['pasien']['isjkn'] == true) {
            }else{
            DB::rollBack();
            }
            if (isset($PSN['isjkn']) && $PSN['isjkn'] == true) {
                Log::info("Simpan Pasien Baru JKN gagal ". $e->getMessage(). " ". $e->getLine() );
                $result = array(
                    "response" => null,
                    "metadata" => array(
                        "code" => 201,
                        // "e" => $e->getMessage() . ' ' . $e->getLine(),
                        "message" => "Terjadi Kesalahan"
                    )
                );
                return response()->json($result);
            } else {
                $result = array(
                    "status" => 400,
                    "result"  => array(
                        "e"  => $e->getLine() . ' ' . $e->getMessage(),
                    ),

                );
            }
        }
        return $this->respond($result['result'], $result['status'], $transMessage);
    }

    public function savePasienPJ(Request $r)
    {

        try {
            $PSN =  $r['pasien'];

            $dataPS = Pasien::where('id', $PSN['id'])
                ->where('statusenabled', true)
                ->first();
            $dataPS->penanggungjawab = isset($PSN['penanggungjawab']) ? $PSN['penanggungjawab'] : null;
            $dataPS->hubungankeluargapj = isset($PSN['hubungankeluargapj']) ? $PSN['hubungankeluargapj'] : null;
            $dataPS->pekerjaanpenangggungjawab = isset($PSN['pekerjaanpenangggungjawab']) ? $PSN['pekerjaanpenangggungjawab'] : null;
            $dataPS->telponpenanggungjawab = isset($PSN['telponpenanggungjawab']) ? $PSN['telponpenanggungjawab'] : null;
            $dataPS->jeniskelaminpenanggungjawab = isset($PSN['jeniskelaminpenanggungjawab']) ? $PSN['jeniskelaminpenanggungjawab'] : null;
            $dataPS->bahasa = isset($PSN['bahasa']) ? $PSN['bahasa'] : null;
            $dataPS->umurpenanggungjawab = isset($PSN['umurpenanggungjawab']) ? $PSN['umurpenanggungjawab'] : null;
            $dataPS->pekerjaanpenangggungjawab = isset($PSN['pekerjaanpenangggungjawab']) ? $PSN['pekerjaanpenangggungjawab'] : null;
            $dataPS->alamatrmh = isset($PSN['alamatrmh']) ? $PSN['alamatrmh'] : null;
            $dataPS->objectkebangsaanfk = isset($PSN['objectkebangsaanfk']) ? $PSN['objectkebangsaanfk'] : null;
            $dataPS->save();

            $transStatus = 'true';
        } catch (\Exception $e) {
            $transStatus = 'false';
        }

        if ($transStatus == 'true') {
            $transMessage = "Sukses";
            $result = array(
                "status" => 200,
                "result" => array(
                    "data"  => $dataPS,
                    "as" => '@epic',
                ),
            );
        } else {
            $transMessage = "Simpan Gagal";
            $result = array(
                "status" => 400,
                "result"  => array(
                    "e"  => $e->getLine() . ' ' . $e->getMessage(),
                ),
            );
        }
        return $this->respond($result['result'], $result['status'], $transMessage);
    }

    public function savePasienKartu(Request $r)
    {

        try {

            $dataPS = Pasien::where('id', $r['id'])
                ->where('statusenabled', true)
                ->first();
            $dataPS->nobpjs = $r['nokartu'];
            $dataPS->save();

            $transStatus = 'true';
        } catch (\Exception $e) {
            $transStatus = 'false';
        }

        if ($transStatus == 'true') {
            $transMessage = "Sukses";
            $result = array(
                "status" => 200,
                "result" => array(
                    "data"  => $dataPS,
                    "as" => '@epic',
                ),
            );
        } else {
            $transMessage = "Simpan Gagal";
            $result = array(
                "status" => 400,
                "result"  => array(
                    "e"  => $e->getLine() . ' ' . $e->getMessage(),
                ),
            );
        }
        return $this->respond($result['result'], $result['status'], $transMessage);
    }

    public function pasienByID(Request $r)
    {
        $data = DB::table('pasien_m as ps')
            ->JOIN('alamat_m as alm', 'alm.nocmfk', '=', 'ps.id')
            ->leftjoin('jeniskelamin_m as jk', 'jk.id', '=', 'ps.objectjeniskelaminfk')
            ->leftjoin('pendidikan_m as pdd', 'ps.objectpendidikanfk', '=', 'pdd.id')
            ->leftjoin('pekerjaan_m as pk', 'ps.objectpekerjaanfk', '=', 'pk.id')
            ->leftjoin('agama_m as agm', 'agm.id', '=', 'ps.objectagamafk')
            ->leftjoin('statusperkawinan_m as sp', 'sp.id', '=', 'ps.objectstatusperkawinanfk')
            ->leftjoin('kebangsaan_m as kb', 'kb.id', '=', 'ps.objectkebangsaanfk')
            ->leftjoin('negara_m as ng', 'ng.id', '=', 'alm.objectnegarafk')
            ->leftjoin('desakelurahan_m as dsk', 'dsk.id', '=', 'alm.objectdesakelurahanfk')
            ->leftjoin('kecamatan_m as kcm', 'kcm.id', '=', 'alm.objectkecamatanfk')
            ->leftjoin('kotakabupaten_m as kkb', 'kkb.id', '=', 'alm.objectkotakabupatenfk')
            ->leftjoin('propinsi_m as prp', 'prp.id', '=', 'alm.objectpropinsifk')
            ->leftjoin('pekerjaan_m as pk1', 'ps.pekerjaanpenangggungjawab', '=', 'pk1.pekerjaan')
            ->leftjoin('suku_m as sk', 'sk.id', '=', 'ps.objectsukufk')
            ->leftjoin('golongandarah_m as gol', 'gol.id', '=', 'ps.objectgolongandarahfk')
            ->leftjoin('jeniskelamin_m as jk1', 'jk1.jeniskelamin', '=', 'ps.jeniskelaminpenanggungjawab')
            ->select(
                'ps.nocm',
                'ps.id as nocmfk',
                'ps.namapasien',
                'ps.tgllahir',
                'ps.tempatlahir',
                'ps.objectjeniskelaminfk',
                'jk.jeniskelamin',
                'ps.objectagamafk',
                'agm.agama',
                'ps.objectstatusperkawinanfk',
                'sp.statusperkawinan',
                'ps.objectpendidikanfk',
                'pdd.pendidikan',
                'ps.objectpekerjaanfk',
                'pk.pekerjaan',
                'ps.objectkebangsaanfk',
                'kb.name as kebangsaan',
                'alm.objectnegarafk',
                'ng.namanegara',
                'ps.noidentitas',
                'ps.nobpjs',
                'ps.noasuransilain',
                'alm.alamatlengkap',
                'ps.telponpenanggungjawab',
                'alm.rtrw',
                'alm.kodepos',
                'alm.objectdesakelurahanfk',
                'dsk.namadesakelurahan',
                'alm.objectkecamatanfk',
                'kcm.namakecamatan',
                'alm.objectkotakabupatenfk',
                'kkb.namakotakabupaten',
                'alm.objectpropinsifk',
                'prp.namapropinsi',
                'ps.notelepon',
                'ps.nohp',
                'ps.namaayah',
                'ps.namaibu',
                'ps.namakeluarga',
                'ps.namasuamiistri',
                'ps.penanggungjawab',
                'ps.hubungankeluargapj',
                'ps.pekerjaanpenangggungjawab',
                'ps.ktppenanggungjawab',
                'ps.alamatrmh',
                'ps.alamatktr',
                'pk1.id as idpek',
                'ps.photo',
                'ps.foto',
                'ps.objectgolongandarahfk',
                'gol.golongandarah',
                'ps.objectsukufk',
                'sk.suku',
                'ps.bahasa',
                'ps.dokterpengirim',
                'ps.alamatdokterpengirim',
                'jk1.id as jkidpenanggungjawab',
                'ps.jeniskelaminpenanggungjawab',
                'ps.umurpenanggungjawab',
                'ps.paspor',
                'ps.email',
                'ps.isfoto',
                'ps.rujukanrs',
                'ps.penjaminanrs',
                'ps.riwayattambahan',
                'ps.filename'
            )
            ->where('ps.kdprofile', (int)$this->kdProfile)
            ->where('ps.statusenabled', true);

        if (isset($r['noCm']) && $r['noCm'] != '' && $r['noCm'] != 'undefined') {
            $data = $data->where('ps.nocm', $r['noCm']);
        }
        if (isset($r['id']) && $r['id'] != '' && $r['id'] != 'undefined') {
            $data = $data->where('ps.id', $r['id']);
        }

        $data = $data->first();
        if(!empty($data)){
            $data->umur =  $this->getAge($data->tgllahir, date('Y-m-d H:i:s'));
        }
        $result = array(
            'pasien' => $data,
            'as' => '@epic',
        );
        return $this->respond($result);
    }
    public function deletePasien(Request $r)
    {
        DB::beginTransaction();
        try {
            Pasien::where('id', $r['id'])->update(
                ['statusenabled' => false]
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
                    "as" => '@epic',
                ),
            );
        } else {
            $transMessage = "Hapus Gagal";
            DB::rollBack();

            $result = array(
                "status" => 400,
                "result"  => null

            );
        }
        return $this->respond($result['result'], $result['status'], $transMessage);
    }

    public function riwayatRegistrasi(Request $r)
    {
        $kdProfile = $this->kdProfile;
        $data = DB::table('pasien_m as ps')
            ->join('pasiendaftar_t as pd', 'pd.nocmfk', '=', 'ps.id')
            ->join('ruangan_m as ru', 'ru.id', '=', 'pd.objectruanganlastfk')
            ->join('kelompokpasien_m as kp', 'kp.id', '=', 'pd.objectkelompokpasienlastfk')
            ->join('profile_m as pro', 'pro.id', '=', 'pd.kdprofile')
            ->leftjoin('pegawai_m as pg', 'pg.id', '=', 'pd.objectpegawaifk')
            ->select(DB::raw("pd.norec,pd.tglregistrasi,ps.nocm,pd.noregistrasi,ps.namapasien,pd.objectruanganlastfk,kp.kelompokpasien,ru.namaruangan,
                              pd.objectpegawaifk,pg.namalengkap as namadokter,
                              pd.tglpulang,ru.objectdepartemenfk,
                              pro.namalengkap as namaprofile,ru.nocounter"))
            ->where('pd.statusenabled', true)
            ->where('ps.statusenabled', true)
            ->where('ps.kdprofile', (int)$kdProfile);

        if (isset($r['norm']) && $r['norm'] != "" && $r['norm'] != "undefined") {
            $data = $data->where('ps.nocm', 'ilike', '%' . $r['norm'] . '%');
        };
        if (isset($r['id']) && $r['id'] != "" && $r['id'] != "undefined") {
            $data = $data->where('ps.id', '=',  $r['id']);
        };
        if (isset($r['namaPasien']) && $r['namaPasien'] != "" && $r['namaPasien'] != "undefined") {
            $data = $data->where('ps.namapasien', 'ilike', '%' . $r['namaPasien'] . '%');
        };
        if (isset($r['noReg']) && $r['noReg'] != "" && $r['noReg'] != "undefined") {
            $data = $data->where('pd.noregistrasi', '=', $r['noReg']);
        };
        if (isset($r['idRuangan']) && $r['idRuangan'] != "" && $r['idRuangan'] != "undefined") {
            $data = $data->where('pd.objectruanganlastfk', '=', $r['idRuangan']);
        };
        $data = $data->orderBy('pd.tglregistrasi','desc');
        $data = $data->get();
        foreach ($data as $d) {
            $d->lamarawat =  $this->getAge($d->tglregistrasi, $d->tglpulang ? $d->tglpulang : date('Y-m-d H:i:s'));
        }

        return $this->respond($data);
    }
    public function savePasienBayi(Request $r)
    {
        DB::beginTransaction();
        // return $r->all();
        try {
            //region Save Pasien
            $SET['idRunningNumberPasien'] = $this->settingFix('idRunningNumberPasien');
            // $SET['idRunningNumberPasienBayi'] = $this->settingFix('idRunningNumberPasienBayi');
            $SET['HubunganKeluargaSendiri'] = $this->settingFix('HubunganKeluargaSendiri');
            $SET['jenisAlamatRumah'] = $this->settingFix('jenisAlamatRumah');

            // return
            $PSN =  $r['pasien'];
            $ALM =  $r['alamat'];
            $idProfile = $this->kdProfile;
            //validasi no rm ibu menghindari kolom nocmfkibu null
            if (!empty($PSN['isbayi']) && $PSN['isbayi'] == true) {
                if (!isset($PSN['nocmfkibu']) || empty($PSN['nocmfkibu'])) {
                    DB::rollBack();
                    return $this->respond(null, 400, 'nocmfkibu wajib diisi untuk registrasi bayi.');
                }
            }
            if ($PSN['id'] == '')
            {
                if (
                    isset($PSN['noidentitas'])
                    && $PSN['noidentitas'] != '-'
                    && $PSN['noidentitas'] != ''
                ) {
                    $cek = Pasien::where('id', $PSN['noidentitas'])
                        ->where('statusenabled', true)->first();
                    if (!empty($cek)) {
                        $transMessage = "NIK ini sudah terdaftar sebagai " . $cek->namapasien . ' (' . $cek->nocm . ')';
                        DB::rollBack();
                        $result = array(
                            "status" => 400,
                            "result"  => null,
                        );
                        return $this->respond($result['result'], $result['status'], $transMessage);
                    }
                }

                if (
                    isset($PSN['isPenunjang'])
                    &&  $PSN['isPenunjang'] == true
                ) {
                    $noCm = $this->SEQUENCE(new Pasien, 'nocm_penunjang', 9, 'P', $idProfile);

                }
                else if (isset($PSN['isJenazah']) &&  $PSN['isJenazah'] == true) {
                    $noCm = $this->SEQUENCE(new Pasien, 'nocm_jenazah', 9, 'J', $idProfile);
                }
                else if (isset($PSN['isTelemedicine'])  &&  $PSN['isTelemedicine'] == true) {
                    $noCm = $this->SEQUENCE(new Pasien, 'nocm_telemedicine', 9, 'T', $idProfile);
                } else {
                    $idRunningNumber = $SET['idRunningNumberPasien'] ;//$SET['idRunningNumberPasienBayi'];
                    // $idRunningNumber = $SET['idRunningNumberPasien'];
                    // if ($PSN['isbayi'] == true) {
                    //
                    // }
                    $runningNumber = RunningNumber::where('id', $idRunningNumber)->where('kdprofile', $idProfile)->first();

                    $noCm = $this->SEQUENCE(new Pasien, 'nocm',  (float)$runningNumber->length, $runningNumber->prefix, $idProfile);

                    RunningNumber::where('id', $idRunningNumber)
                        ->where('kdprofile', $idProfile)
                        ->update(
                            [
                                'nomer_terbaru' => $noCm
                            ]
                        );
                }

                $dataPS = new Pasien();
                $newId = $dataPS->generateNewId();
                $dataPS->id = $newId;
                $dataPS->kdprofile = (int)$this->kdProfile;
                $dataPS->statusenabled = true;
                $dataPS->isbayi = true;
                // $dataPS->kodeexternal = $newId;
                $dataPS->norec = $newId;
                $dataPS->istelemedicine = isset($PSN['isTelemedicine']) ? $PSN['isTelemedicine'] : false;

                $prefixNoCM = substr($noCm, 0, 2). ".".
                substr($noCm, 2, 2). ".".
                substr($noCm, 4, 2);
                $dataPS->nocm = $prefixNoCM;
            } else {
                $dataPS = Pasien::where('id', $PSN['id'])
                    ->where('statusenabled', true)
                    ->first();
                $noCm = $dataPS->nocm;
            }

            $dataPS->namaexternal =  $PSN['namapasien'];
            $dataPS->reportdisplay =  $PSN['namapasien'];
            $dataPS->objectagamafk = $PSN['objectagamafk'];
            $dataPS->objectjeniskelaminfk = $PSN['objectjeniskelaminfk'];
            $dataPS->namapasien = $PSN['namapasien'];
            $dataPS->objectpekerjaanfk = $PSN['objectpekerjaanfk'];
            $dataPS->objectpendidikanfk = $PSN['objectpendidikanfk'];
            $dataPS->qpasien = 1;
            $dataPS->nocmfkibu =  $PSN['nocmfkibu'];
            $dataPS->objectstatusperkawinanfk = $PSN['objectstatusperkawinanfk'];
            $dataPS->tgldaftar = date('Y-m-d H:i:s');
            $dataPS->tgllahir = $PSN['tgllahir'];
            $dataPS->namaibu = $PSN['namaibu'];
            $dataPS->notelepon = $PSN['notelepon'];
            $dataPS->noidentitas = $PSN['noidentitas'];
            $dataPS->noaditional = $PSN['noaditional'];
            $dataPS->objectkebangsaanfk = $PSN['objectkebangsaanfk'];
            $dataPS->objectnegarafk = $PSN['objectnegarafk'];
            $dataPS->namaayah = $PSN['namaayah'];
            $dataPS->namasuamiistri = isset($PSN['namasuamiistri']) ? $PSN['namasuamiistri'] : null;
            $dataPS->noasuransilain = $PSN['noaditional'];
            $dataPS->nobpjs = $PSN['nobpjs'];
            $dataPS->paspor = isset($PSN['paspor']) ? $PSN['paspor'] : null;
            $dataPS->nohp = $PSN['nohp'];
            $dataPS->tempatlahir = $PSN['tempatlahir'];
            $dataPS->namakeluarga =  isset($PSN['namakeluarga']) ? $PSN['namakeluarga'] : null;
            $dataPS->jamlahir =  isset($PSN['jamlahir']) ? $PSN['jamlahir'] : null;
            $dataPS->objectgolongandarahfk = $PSN['objectgolongandarahfk'];
            $dataPS->objectsukufk = $PSN['objectsukufk'];
            $dataPS->penanggungjawab = isset($PSN['penanggungjawab']) ? $PSN['penanggungjawab'] : null;
            $dataPS->hubungankeluargapj = isset($PSN['hubungankeluargapj']) ? $PSN['hubungankeluargapj'] : null;
            $dataPS->pekerjaanpenangggungjawab = isset($PSN['pekerjaanpenangggungjawab']) ? $PSN['pekerjaanpenangggungjawab'] : null;
            $dataPS->telponpenanggungjawab = isset($PSN['telponpenanggungjawab']) ? $PSN['telponpenanggungjawab'] : null;
            $dataPS->jeniskelaminpenanggungjawab = isset($PSN['jeniskelaminpenanggungjawab']) ? $PSN['jeniskelaminpenanggungjawab'] : null;
            $dataPS->bahasa = isset($PSN['bahasa']) ? $PSN['bahasa'] : null;
            $dataPS->umurpenanggungjawab = isset($PSN['umurpenanggungjawab']) ? $PSN['umurpenanggungjawab'] : null;
            $dataPS->pekerjaanpenangggungjawab = isset($PSN['pekerjaanpenangggungjawab']) ? $PSN['pekerjaanpenangggungjawab'] : null;
            $dataPS->alamatrmh = isset($PSN['alamatrmh']) ? $PSN['alamatrmh'] : null;
            $dataPS->isjkn = isset($PSN['isjkn']) ? $PSN['isjkn'] : null;
            $dataPS->nomorkk = isset($PSN['nomorkk']) ? $PSN['nomorkk'] : null;
            $dataPS->foto = isset($PSN['foto']) ? $PSN['foto'] : null;
            $dataPS->email = isset($PSN['email']) ? $PSN['email'] : null;
            $dataPS->progress = isset($PSN['progress']) ? (int) $PSN['progress'] : 0;

            // adding Prefix

            // $dataPS->nocm = $noCm;
            $dataPS->save();

            $nocmfk = $dataPS->id;

            if ($PSN['id'] == '') {
                $dataAL = new Alamat();
                $idAlamat = $dataAL->generateNewId();
                $dataAL->id = $idAlamat;
                $dataAL->kdprofile = (int)$idProfile;;
                $dataAL->statusenabled = true;
                // $dataAL->kodeexternal = $idAlamat;
                $dataAL->norec = $idAlamat;
            } else {
                $dataAL = Alamat::where('nocmfk', $nocmfk)->first();
                $idAlamat = $dataAL->id;
            }

            $dataAL->alamatlengkap = $ALM['alamatlengkap'];
            $dataAL->objecthubungankeluargafk =  $SET['HubunganKeluargaSendiri'];
            $dataAL->objectdesakelurahanfk = $ALM['objectdesakelurahanfk'];
            $dataAL->objectjenisalamatfk = $SET['jenisAlamatRumah'];
            $dataAL->kdalamat = $idAlamat;
            $dataAL->objectkecamatanfk = $ALM['objectkecamatanfk'];
            $dataAL->kodepos = $ALM['kodepos'];
            $dataAL->objectkotakabupatenfk = $ALM['objectkotakabupatenfk'];
            $dataAL->objectnegarafk = $PSN['objectnegarafk'];
            $dataAL->nocmfk = $nocmfk;
            $dataAL->objectpegawaifk = $this->getPegawai()->id;
            $dataAL->objectpropinsifk = $ALM['objectpropinsifk'];
            $dataAL->save();
            //endregion

            $transStatus = 'true';
        } catch (\Exception $e) {
            $transStatus = 'false';
        }

        if ($transStatus == 'true') {
            $transMessage = "Sukses";
            DB::commit();
            if (isset($PSN['isjkn']) && $PSN['isjkn'] == true) {
                $result = array(
                    "response" => array(
                        "norm" => $dataPS->nocm
                    ),
                    "metadata" =>
                    array(
                        'message' => "Harap datang ke admisi untuk melengkapi data rekam medis",
                        'code' => '200',
                    )
                );
                return response()->json($result);
            } else {
                $result = array(
                    "status" => 200,
                    "result" => array(
                        "data"  => $dataPS,
                        "as" => '@epic',
                    ),
                );
            }
        } else {
            $transMessage = "Simpan Gagal";
            DB::rollBack();
            if (isset($PSN['isjkn']) && $PSN['isjkn'] == true) {
                $result = array(
                    "response" => null,
                    "metadata" => array(
                        "code" => 201,
                        "e" => $e->getMessage() . ' ' . $e->getLine(),
                        "message" => "Terjadi Kesalahan"
                    )
                );
                return response()->json($result);
            } else {
                $result = array(
                    "status" => 400,
                    "result"  => array(
                        "e"  => $e->getLine() . ' ' . $e->getMessage(),
                    ),

                );
            }
        }
        return $this->respond($result['result'], $result['status'], $transMessage);
    }
    public function savePasienFoto(Request $r)
    {
        DB::beginTransaction();
        try {

            $uploadBerkasPasien = $r->file('file');
            $path = 'foto_pasien/' . $r['id'];
            $extension = $uploadBerkasPasien->getClientOriginalExtension();
            $filename =  $r['id']. '.' . $extension;
            Pasien::where('id',$r['id'])->update(['isfoto' => true,
            'filename' => $filename]);


            $r->fPut(
                $path . '/' . $filename,
                File::get($r->file('file')->getRealPath()),
                'public'
            );
            DB::commit();
            $transMessage = 'Sukses';
            $result = array(
                "status" => 200,
                "result" => array(
                    "as" => '@epic',
                ),
            );
        } catch (\Exception $e) {
            $transMessage = 'Simpan Gagal';
            DB::rollBack();
            $result = array(
                "status" => 400,
                "result"  => null
            );
        }

        return $this->respond($result['result'], $result['status'], $transMessage);
    }

}
