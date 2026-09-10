<?php

namespace App\Http\Controllers\Laporan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

use App\Models\Transaksi\KeteranganLahir;
use App\Models\Master\Profile;

use DB, Exception, Validator;

class LaporanKeteranganLahirCtrl extends Controller
{
    public function getLaporanLahir(Request $r)
    {
        if (empty($r->get('nocmfk')) || $r->get('nocmfk') == 'undefined')
            abort(404);

        $query = DB::table('pasien_m as Panak')
            ->select(
                "kl.*",
                "Pibu.namapasien as nama",
                "Panak.namapasien as namaanak",
                "kl.namasuami",
                "jk.jeniskelamin"
            )
            ->join("keteranganlahir_t as kl", "kl.nocmfk", "Panak.id")
            ->leftJoin('pasiendaftar_t as pd', 'pd.nocmfk', 'Panak.id')
            ->leftJoin("pasien_m as Pibu", "Pibu.nocm", "Panak.nocmfkibu")
            ->join("jeniskelamin_m as jk", "jk.id", "Panak.objectjeniskelaminfk")
            ->where("Panak.id", $r->get('nocmfk'))
            ->where("pd.noregistrasi", $r->get('noregis'));

        if (isset($r['dari']) && $r['dari']) {
            $query->where('kl.tanggal', '>=', $r['dari'] . ' 00:00');
        }
        if (isset($r['sampai']) && $r['sampai']) {
            $query->where('kl.tanggal', '<=', $r['sampai'] . ' 23:59');
        }
        $query = $query->get();

        $total = count($query);

        $data = [
            "status" => 200,
            "data" => $query,
            "message" => $total > 0 ? "Data found" : "Data empty",
            "total" => $total
        ];
        return $this->respond($data, $data["status"], $data["message"]);
    }
    public function getPasienSKL(Request $r) {
        $page = (int) $r->get('page', 1);
        $rows = (int) $r->get('rows', 10);
        $offset = ($page - 1) * $rows;

        $query = DB::table('pasien_m as pas')
            ->select(
                'pas.id',
                'pas.nocm',
                'pas.namapasien',
                'pas.tgllahir',
                'jk.jeniskelamin',
                'pas.noidentitas',
                'pas.nohp',
                'pas.namakeluarga',
                'pas.alamatlengkap'
            )
            ->join('jeniskelamin_m as jk', 'jk.id', '=', 'pas.objectjeniskelaminfk')
            ->where('pas.statusenabled', true)
            ->where('pas.kdprofile', 1);

        if (!empty($r->namapasien)) {
            $query = $query->where('pas.namapasien', 'ilike', '%' . $r->namapasien . '%');
        }
        $total = $query->count();
        $query = $query->offset($offset)->limit($rows)->get();
        $data = [
            "status" => 200,
            "data" => $query,
            "message" => $total > 0 ? "Data found" : "Data empty",
            "total" => $total,
            "page" => $page,
            "rows" => $rows,
            "offset" => $offset
        ];

        return $this->respond($data, $data["status"], $data["message"]);
    }

    public function getAllSKLahir(Request $r) {
        // $query = DB::table('keteranganlahir_t as kl')
        //     ->select(
        //         'kl.norec',
        //         'kl.namaanak',
        //         'kl.namasuami',
        //         'kl.noregistrasifk',
        //         'kl.norm',
        //         'kl.berat',
        //         'kl.tinggi',
        //         'kl.tanggal',
        //         'kl.created_at',
        //         'kl.noskl',
        //         'kl.dokterPenolong',
        //         'apd.noregistrasi as nomorregistrasi',
        //         'apdd.objectpegawaifk',
        //         'pg.namalengkap as dokter',
        //         'apd.tglpulang',
        //         'apd.tglcetak',
        //         'p.namaibu',
        //         'ibu.alamatlengkap',
        //         'p.alamatrmh',
        //         'ibu.tgllahir as tgllahiribu',
        //         'p.namapasien as namaanak',
        //         'ibu.namapasien as namaibuuk',
        //         'p.nocmfkibu',
        //         'jk.jeniskelamin',
        //         'ibu.noidentitas'
        //     )
        //     ->leftJoin('pasien_m as p', 'p.id', '=', 'kl.nocmfk')
        //     ->leftJoin('pasiendaftar_t as pd', 'pd.nocmfk', '=', 'kl.nocmfk')
        //     ->leftJoin('antrianpasiendiperiksa_t as apdd', 'apdd.noregistrasi', '=', 'kl.noregistrasifk')
        //     ->leftJoin('pegawai_m as pg', 'pg.id', '=', 'apdd.objectpegawaifk')
        //     ->join('jeniskelamin_m as jk', 'jk.id', '=', 'kl.objekjeniskelaminfk')
        //     ->leftJoin('pasiendaftar_t as apd', 'apd.noregistrasi', '=', 'kl.noregistrasifk')
        //     ->leftJoin('alamat_m as al', 'al.nocmfk', '=', 'p.id')
        //     ->leftJoin('pasien_m as ibu', 'ibu.nocm', '=', 'p.nocmfkibu')
        //     ->leftJoin('alamat_m as alibu', 'alibu.nocmfk', '=', 'ibu.id');
        //     // ->limit(10);
        //     // ->get();
        $query = DB::table('pasien_m as Panak')
            ->select(
                'kl.norec',
                'kl.noskl',
                'kl.namaanak',
                'kl.dokterPenolong',
                'kl.norm',
                'Pibu.namapasien as namaibu',
                'Panak.namapasien as namaanak',
                'Panak.nocmfkibu',
                'kl.namasuami',
                'jk.jeniskelamin',
                'IbuForReal.namapasien as namaIBUForReal',
                'IbuForReal.nocm as nocmIBUForReal',
                'kl.normIbu',
                'r.namaruangan',
                'kl.created_at'
            )
            ->join('keteranganlahir_t as kl', 'kl.nocmfk', '=', 'Panak.id')
            ->leftJoin('pasiendaftar_t as pd', 'pd.nocmfk', '=', 'Panak.id')
            ->leftJoin('pasien_m as Pibu', 'Pibu.nocm', '=', 'Panak.nocmfkibu')
            ->LeftJoin('pasien_m as IbuForReal', 'IbuForReal.nocm', '=', 'kl.normIbu')
            ->LeftJoin('pasiendaftar_t as apd', 'apd.noregistrasi', '=', 'kl.noregistrasifk')
            ->LeftJoin('ruangan_m as r', 'r.id', '=', 'apd.objectruanganasalfk')
            ->join('jeniskelamin_m as jk', 'jk.id', '=', 'Panak.objectjeniskelaminfk')
            ->orderBy('kl.created_at', 'asc')
            ->where('kl.statusenabled', true)
            ->distinct();

        if (isset($r['dari']) && $r['dari']) {
            $query->where('kl.tanggal', '>=', $r['dari'] . ' 00:00');
        }
        if (isset($r['sampai']) && $r['sampai']) {
            $query->where('kl.tanggal', '<=', $r['sampai'] . ' 23:59');
        }
        if (isset($r['namaanak']) && $r['namaanak'] != "" && $r['namaanak'] != "undefined") {
            $query = $query->Where('p.namaanak', 'ilike', '%' . $r['nama'] . '%');
        }
        if (isset($r['dokterPenolong']) && $r['dokterPenolong'] != "" && $r['dokterPenolong'] != "undefined") {
            $query = $query->Where('kl.dokterPenolong', 'ilike', '%' . $r['dokterPenolong'] . '%');
        }
        if (isset($r['norm']) && $r['norm'] != "" && $r['norm'] != "undefined") {
            $query = $query->Where('kl.norm', 'ilike', '%' . $r['norm'] . '%');
        }
        if (isset($r['nocmfkibu']) && $r['nocmfkibu'] != "" && $r['nocmfkibu'] != "undefined") {
            $query = $query->Where('p.nocmfkibu', 'ilike', '%' . $r['nocmfkibu'] . '%');
        }

        $query = $query->get();

        $total = count($query);

        $data = [
            "status" => 200,
            "data" => $query,
            "message" => $total > 0 ? "Data found" : "Data empty",
            "total" => $total
        ];
        return $this->respond($data, $data["status"], $data["message"]);
    }

    public function editLaporanLahir(Request $r, $norec)
    {
        $result = [
            "status" => 400,
            "message" => "Terjadi kesalahan atau data tidak ditemukan"
        ];

        // Validate input data
        $validator = Validator::make($r->all(), [
            // "namasuami" => "required",
            // "nocmanak" => "required",
            // "pekerjaan" => "required",
            // "normanak" => "required",
            "jeniskelamin" => "required",
            "tglLahir" => "required",
            "tinggianak" => "required",
            "beratanak" => "required",
            "dokterPenolong" => "required",
            "normIbu" => "required"
        ]);

        if ($validator->fails()) {
            $result = [
                "status" => 422, // Use 422 for validation errors
                "message" => "Terdapat form yang belum terisi",
                "errors" => $validator->errors()
            ];
        } else {
            try {
                DB::beginTransaction();
                $data = KeteranganLahir::findOrFail($norec);
                $data->objekjeniskelaminfk = $r['jeniskelamin'];
                $data->pekerjaan = $r['pekerjaan'];
                $data->tinggi = $r['tinggianak'];
                $data->berat = $r['beratanak'];
                $data->dokterPenolong = $r['dokterPenolong'];
                $data->normIbu = $r['normIbu'];
                $data->tanggal = $r['tglLahir']; // Or automatic date(Y-m-d H:i:s);
                $data->save();
                DB::commit();

                $result = [
                    "status" => 200,
                    "message" => "Data berhasil dirubah",
                    "data" => $data
                ];
            } catch (\Exception $e) {
                DB::rollback();
                $result = [
                    "status" => 500,
                    "message" => "Terjadi kesalahan: " . $e->getMessage(),
                    "line" => $e->getLine()
                ];
            }
        }

        return $this->respond($result, $result['status'], $result['message']);
    }


    public function createLaporanLahir(Request $r)
    {
        $result = [
            "status" => 400,
            "message" => "Terjadi kesalahan"
        ];

        // $getBabyInfo = DB::table("pasien_m as panak")
        //     ->select(
        //         "panak.id",
        //         "panak.nocm",
        //         "panak.nocmfkibu",
        //         "panak.namapasien"

        //     )
        //     ->leftJoin("pasien_m as pibu", "pibu.nocm", "panak.nocmfkibu")
        //     ->where("panak.id", $r['nocmanak'])
        //     ->first();
        $getBabyInfo = DB::table("pasien_m")
        ->where('norec',$r['nocmanak'])
        ->first();

        if (empty($getBabyInfo)) {
            return $this->respond($result, $result['status'], $result["message"]);
        }

        // Preventing outside and null request data
        $validator = Validator::make($r->all(), [
            "namasuami" => "nullable",
            "nocmanak" => "required",
            "pekerjaan" => "nullable",
            "normanak" => "required",
            "jeniskelamin" => "required",
            "tglLahir" => "required",
            "tinggianak" => "required",
            "beratanak" => "required",
            "dokterPenolong" => "required",
            "normIbu" => "required"
        ]);

        if ($validator->fails()) {
            $result["message"] = "Terdapat form yang kosong";
            $result["errors"] = $validator->errors();
        } else {
            try {

                DB::beginTransaction();
                $app = new KeteranganLahir;
                $app->norec = $app->generateNewId();
                $app->noskl = $app->generateSKL();
                $app->namasuami = $r->filled('namasuami') ? $r['namasuami'] : null;
                $app->namaibu = $r->filled('namaibu') ? $r['namaibu'] : null;
                $app->nocmfk = $getBabyInfo->id;
                $app->objekjeniskelaminfk = $r['jeniskelamin'];
                $app->dokterPenolong = $r['dokterPenolong'];
                $app->namaanak = $getBabyInfo->namapasien;
                $app->noregistrasifk = $r['noregis'];
                $app->pekerjaan = $r->filled('pekerjaan') ? $r['pekerjaan'] : null;
                $app->norm = $r['normanak'];
                $app->tinggi = $r['tinggianak'];
                $app->berat = $r['beratanak'];
                $app->normIbu = $r['normIbu'];
                $app->statusenabled = true;
                $app->tanggal = $r['tglLahir']; // Or automatic date(Y-m-d H:i:s);
                $app->save();
                DB::commit();

                $result = [
                    "status" => 201,
                    "message" => "Data berhasil ditambahkan",
                    "data" => $app
                ];

            } catch (Exception $e) {
                DB::rollback();
                // Devmode
                $result["message"] = $e->getMessage() . ' ' . $e->getLine();
            }
        }

        return $this->respond($result, $result['status'], $result['message']);
    }

    public function deleteLaporanLahir($norec)
    {
        try {
            DB::beginTransaction();
            $data = KeteranganLahir::findorFail($norec);
            $data->statusenabled = false;
            $data->save();
            DB::commit();
            $result = [
                "status" => 200,
                "message" => "Data berhasil dihapus"
            ];
        } catch (Exception $e) {
            DB::commit();
            $result = [
                "status" => 400,
                "message" => $e->getMessage() . " " . $e->getLine()
            ];
        }

        return $this->respond($result);
    }

    public function cetakKelahiran(Request $r)
    {
        set_time_limit(120);
        //new
        $dataLahir = DB::table("keteranganlahir_t as kl")
            ->select(
                "kl.normIbu",
                "kl.norec",
                "kl.namaanak",
                "kl.namasuami",
                "kl.noregistrasifk",
                "kl.norm",
                "kl.berat",
                "kl.tinggi",
                "kl.tanggal",
                "kl.created_at",
                "kl.noskl",
                "kl.dokterPenolong",
                "apd.noregistrasi as nomorregistrasi",
                "apdd.objectpegawaifk",
                "pg.namalengkap as dokter",
                "apd.tglpulang",
                "apd.tglcetak",
                "p.namaibu",
                "ibu.alamatlengkap",
                "p.alamatrmh",
                "ibu.tgllahir as tgllahiribu",
                "p.namapasien as namaanak",
                "ibu.namapasien as namaibuuk",
                "p.nocmfkibu",
                "jk.jeniskelamin",
                "ibu.noidentitas",
                "alibu.alamatlengkap as alamat_normibu",
                "forRealIbu.namapasien as namaibuForReal",
                "forRealIbu.noidentitas as noidentitasibuForReal",
                "forRealIbu.tgllahir as tgllahiribuForReal",
                "alforRealIbu.alamatlengkap as alamatIbuForReal"
            )
            ->leftJoin("pasien_m as p", "p.id", "kl.nocmfk")
            ->leftJoin("keteranganlahir_t as woilah", "woilah.normIbu", "p.nocm")
            ->leftJoin("pasiendaftar_t as pd", "pd.nocmfk", "kl.nocmfk")
            ->leftJoin("antrianpasiendiperiksa_t as apdd", "apdd.noregistrasi", "kl.noregistrasifk")
            ->leftJoin("pegawai_m as pg", "pg.id", "apdd.objectpegawaifk")
            ->join("jeniskelamin_m as jk", "jk.id", "kl.objekjeniskelaminfk")
            ->leftJoin("pasiendaftar_t as apd", "apd.noregistrasi", "kl.noregistrasifk")
            ->leftJoin("alamat_m as al", "al.nocmfk", "p.id")
            ->leftJoin("pasien_m as ibu", "ibu.nocm", "p.nocmfkibu")
            ->leftJoin("alamat_m as alibu", "alibu.nocmfk", "ibu.id")
            ->leftJoin("pasien_m as forRealIbu", "forRealIbu.nocm", "kl.normIbu")
            ->leftJoin("alamat_m as alforRealIbu", "alforRealIbu.nocmfk", "forRealIbu.id")
            ->where("kl.nocmfk", $r->get("nocmfk"))
            ->where("kl.noregistrasifk", $r->get("noregis"))
            ->where("kl.norm", $r->get("norm"))
            ->orWhere("kl.norec", $r->get("norec"))
            ->first();


        $background = '';
        if ($dataLahir->jeniskelamin === 'Laki-laki') {
            $background = 'img/skl-lakik.png';
        } else {
            $background = 'img/skl-cewek.png';
        }



        // $dataIbu = DB::table("pasien_m as ps")
        //     ->select('ps.*', 'alm.alamatlengkap')
        //     ->leftJoin('alamat_m as alm', 'alm.nocmfk', 'ps.id')
        //     ->leftJoin('keteranganlahir_t as kl', 'kl.normIbu', 'ps.nocm')
        //     ->first();

        $profile = Profile::find($this->kdProfile);
        $pageWidth = 950;

        // Generate QR Code
        $key = route('dokumen.signature.pg', ['key' => base64_encode($dataLahir->norec)]);
        $qrcode = base64_encode(
            QrCode::format('svg')
                ->size(200)
                ->errorCorrection('H')
                ->generate($key)
        );

        // Formatting dates
        $tgllahiranak = $this->tanggal_indonesia(date('Y-m-d', strtotime($dataLahir->tanggal)));
        $hari = $this->hari_indonesia(date('Y-m-d', strtotime($dataLahir->tanggal)));
        $tglcetak = $this->tanggal_indonesia(date("Y-m-d", strtotime($dataLahir->created_at)));

        $dataLahir->hariindo = $hari;
        $dataLahir->tglindo = $tgllahiranak;
        $dataLahir->tglcetakindo = $tglcetak;

        $blade = 'report.registrasi.surat-keterangan-lahir';
        if (true) { // Default to PDF
            $pdf = App::make('dompdf.wrapper');
            $pdf->loadView(
                $blade,
                [
                    'profile' => $profile,
                    'print' => false,
                    'qrcode' => $qrcode,
                    // 'dataIbu' => $dataIbu,
                    'dataLahir' => $dataLahir,
                    'background' => $background
                ]
            );
            $pdf->setPaper('A4', 'portrait');
            return $pdf->stream();
        }

        return view($blade, compact('pageWidth'));
    }

    public function cetakKelahiranKlaim(Request $r)
    {
        set_time_limit(120);
        //new
        $dataLahir = DB::table("keteranganlahir_t as kl")
            ->select(
                "kl.normIbu",
                "kl.norec",
                "kl.namaanak",
                "kl.namasuami",
                "kl.noregistrasifk",
                "kl.norm",
                "kl.berat",
                "kl.tinggi",
                "kl.tanggal",
                "kl.created_at",
                "kl.noskl",
                "kl.dokterPenolong",
                "apd.noregistrasi as nomorregistrasi",
                "apdd.objectpegawaifk",
                "pg.namalengkap as dokter",
                "apd.tglpulang",
                "apd.tglcetak",
                "p.namaibu",
                "ibu.alamatlengkap",
                "p.alamatrmh",
                "ibu.tgllahir as tgllahiribu",
                "p.namapasien as namaanak",
                "ibu.namapasien as namaibuuk",
                "p.nocmfkibu",
                "jk.jeniskelamin",
                "ibu.noidentitas",
                "alibu.alamatlengkap as alamat_normibu",
                "forRealIbu.namapasien as namaibuForReal",
                "forRealIbu.noidentitas as noidentitasibuForReal",
                "forRealIbu.tgllahir as tgllahiribuForReal",
                "alforRealIbu.alamatlengkap as alamatIbuForReal"
            )
            ->leftJoin("pasien_m as p", "p.id", "kl.nocmfk")
            ->leftJoin("keteranganlahir_t as woilah", "woilah.normIbu", "p.nocm")
            ->leftJoin("pasiendaftar_t as pd", "pd.nocmfk", "kl.nocmfk")
            ->leftJoin("antrianpasiendiperiksa_t as apdd", "apdd.noregistrasi", "kl.noregistrasifk")
            ->leftJoin("pegawai_m as pg", "pg.id", "apdd.objectpegawaifk")
            ->join("jeniskelamin_m as jk", "jk.id", "kl.objekjeniskelaminfk")
            ->leftJoin("pasiendaftar_t as apd", "apd.noregistrasi", "kl.noregistrasifk")
            ->leftJoin("alamat_m as al", "al.nocmfk", "p.id")
            ->leftJoin("pasien_m as ibu", "ibu.nocm", "p.nocmfkibu")
            ->leftJoin("alamat_m as alibu", "alibu.nocmfk", "ibu.id")
            ->leftJoin("pasien_m as forRealIbu", "forRealIbu.nocm", "kl.normIbu")
            ->leftJoin("alamat_m as alforRealIbu", "alforRealIbu.nocmfk", "forRealIbu.id")
            ->where("kl.noregistrasifk", $r->get("noregistrasi"))
            ->first();


        $background = '';
        if ($dataLahir->jeniskelamin === 'Laki-laki') {
            $background = 'img/skl-lakik.png';
        } else {
            $background = 'img/skl-cewek.png';
        }



        $dataIbu = DB::table("pasien_m as ps")
            ->select('ps.*', 'alm.alamatlengkap')
            ->leftJoin('alamat_m as alm', 'alm.nocmfk', 'ps.id')
            ->leftJoin('keteranganlahir_t as kl', 'kl.normIbu', 'ps.nocm')
            ->first();

        $profile = Profile::find($this->kdProfile);
        $pageWidth = 950;

        // Generate QR Code
        $key = route('dokumen.signature.pg', ['key' => base64_encode($dataLahir->norec)]);
        $qrcode = base64_encode(
            QrCode::format('svg')
                ->size(200)
                ->errorCorrection('H')
                ->generate($key)
        );

        // Formatting dates
        $tgllahiranak = $this->tanggal_indonesia(date('Y-m-d', strtotime($dataLahir->tanggal)));
        $hari = $this->hari_indonesia(date('Y-m-d', strtotime($dataLahir->tanggal)));
        $tglcetak = $this->tanggal_indonesia(date("Y-m-d", strtotime($dataLahir->created_at)));

        $dataLahir->hariindo = $hari;
        $dataLahir->tglindo = $tgllahiranak;
        $dataLahir->tglcetakindo = $tglcetak;

        $blade = 'report.registrasi.surat-keterangan-lahir';
        if (true) { // Default to PDF
            $pdf = App::make('dompdf.wrapper');
            $pdf->loadView(
                $blade,
                [
                    'profile' => $profile,
                    'print' => false,
                    'qrcode' => $qrcode,
                    'dataIbu' => $dataIbu,
                    'dataLahir' => $dataLahir,
                    'background' => $background
                ]
            );
            $pdf->setPaper('A4', 'portrait');
            return $pdf;
        }

        return view($blade, compact('pageWidth'));
    }


    public function getPasienIstri(Request $req)
    {
        $data = DB::table('pasien_m as p')
            ->select('p.id as id', DB::raw("p.namapasien || ' - ' || p.nocm as display"), 'p.namapasien as nama', 'p.nocm', 'p.namaayah')
            ->join('jeniskelamin_m as jk', 'jk.id', 'p.objectjeniskelaminfk')
            ->where('jk.statusenabled', true)
            ->where('jk.jeniskelamin', 'Perempuan')
            ->when(!empty($req->get('search')), function ($q) use ($req) {
                return $q->whereRaw("UPPER(p.namapasien) LIKE '%" . strtoupper($req->get('search')) . "%'");
            })->get();

        return $this->respond($data);
    }

    public function getJenisKelamin()
    {
        $data = DB::table("jeniskelamin_m")
            ->select("id", "jeniskelamin")
            ->where('statusenabled', true)
            ->get();

        return $this->respond($data);
    }

    public function checkIsBaby(Request $r)
    {
        $result = [
            "status" => 400,
            "message" => "Terjadi kesalahan atau data tidak ditemukan"
        ];
        if (!empty($r->get('nocmfk'))) {
            $checkBayi = DB::table("pasien_m")->find($r->get('nocmfk'));
            // $checkBayi = DB::table("pasien_m")->where('id', $r->get('nocmfk'));
            if (empty($checkBayi)) {
                return $this->respond($result);
            }

            if ($checkBayi->isbayi !== true) {
                $result['message'] = $checkBayi->namapasien . " bukan bayi.";
                return $this->respond($result);
            }

            $result = [
                "status" => 200,
                "message" => "Pasien is valid, good to go"
            ];
        }
        return $this->respond($result);
    }

    public function hari_indonesia($tanggal)
    {
        //dd(date('l',strtotime($tanggal)));

        $hari = date('l', strtotime($tanggal));
        $namahari = '';
        if ($hari == "Sunday") {
            $namahari = 'Minggu';
        } else if ($hari == "Monday") {
            $namahari = 'Senin';
        } else if ($hari == "Tuesday") {
            $namahari = 'Selasa';
        } else if ($hari == "Wednesday") {
            $namahari = 'Rabu';
        } else if ($hari == "Thursday") {
            $namahari = 'Kamis';
        } else if ($hari == "Friday") {
            $namahari = 'Jumat';
        } else if ($hari == "Saturday") {
            $namahari = 'Sabtu';
        }

        return $namahari;
    }

    public function tanggal_indonesia($tanggal)
    {
        $bulan = array(
            1 => 'Januari',
            'Februari',
            'Maret',
            'April',
            'Mei',
            'Juni',
            'Juli',
            'Agustus',
            'September',
            'Oktober',
            'November',
            'Desember'
        );

        $pecahkan = explode('-', $tanggal);

        return $pecahkan[2] . ' ' . $bulan[(int) $pecahkan[1]] . ' ' . $pecahkan[0];
    }
}
