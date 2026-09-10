<?php

namespace App\Http\Controllers\Sysadmin;

use Faker\Factory;
use App\Traits\Valet;
use Ramsey\Uuid\Uuid;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Master\AsuransiPasien;
use App\Models\Master\GolonganAsuransi;
use App\Models\Master\HubunganPesertaAsuransi;
use App\Models\Master\JenisKelamin;
use App\Models\Master\Pegawai;

class MasterAsuransiPasienCtrl extends Controller
{
    use Valet;

    public function __construct()
    {
        parent::__construct($is_encrypt = true);
    }

    public function index(Request $r)
    {
        $data = DB::table('asuransipasien_m as ap')
            ->leftJoin('golonganasuransi_m as gs', 'ap.objectgolonganasuransifk', '=', 'gs.id')
            ->leftJoin('hubunganpesertaasuransi_m as hp', 'ap.objecthubunganpesertafk', '=', 'hp.id')
            ->leftJoin('jeniskelamin_m as jk', 'ap.objectjeniskelaminfk', '=', 'jk.id')
            ->leftJoin('pegawai_m as peg', 'ap.objectpegawaifk', '=', 'peg.id')
            ->leftJoin('pasien_m as pas', 'ap.nocmfk', '=', 'pas.nocm')
            ->leftJoin('kelas_m as kls', 'ap.objectkelasdijaminfk', '=', 'kls.id')
            ->select(
                'ap.id',
                'ap.kdprofile',
                'ap.statusenabled',
                'ap.namaexternal',
                'ap.reportdisplay',
                'ap.kodeexternal',
                'ap.alamatlengkap',
                'ap.objectgolonganasuransifk',
                'gs.golonganasuransi',
                'ap.objecthubunganpesertafk',
                'hp.hubunganpeserta',
                'ap.objectjeniskelaminfk',
                'jk.jeniskelamin',
                'ap.objectpegawaifk',
                'peg.namalengkap',
                'ap.kdinstitusiasal',
                'ap.nocmfk',
                'pas.namapasien',
                'pas.nocm',
                'ap.objectkelasdijaminfk',
                'kls.namakelas',
                'ap.kdlastunitbagian',
                'ap.kdpenjaminpasien',
                'ap.lastunitbagian',
                'ap.namapeserta',
                'ap.nikinstitusiasal',
                'ap.nippns',
                'ap.noasuransi',
                'ap.noasuransihead',
                'ap.nocmfk',
                'ap.tgllahir',
                'ap.noidentitas',
                'ap.notelpfixed',
                'ap.notelpmobile',
                'ap.tglmulaiberlakulast',
                'ap.tglakhirberlakulast',
                'ap.qasuransi',
                'ap.jenispeserta',
                'ap.kdprovider',
                'ap.nmprovider',
            )
            ->where('ap.kdprofile', $this->kdProfile)
            ->orderByDesc('ap.nmprovider');

        if (isset($r['id']) && $r['id'] != '') {
            $data = $data->where('id', '=',  $r['id']);
        }
        if (isset($r['nmprovider']) && $r['nmprovider'] != '') {
            $data = $data->where('ap.nmprovider', 'ilike', '%' . $r['nmprovider'] . '%');
        }
        if (isset($r['objectgolonganasuransifk']) && $r['objectgolonganasuransifk'] != '') {
            $data = $data->where('ap.objectgolonganasuransifk', 'ilike', '%' . $r['objectgolonganasuransifk']);
        }
        if (isset($r['objecthubunganpesertafk']) && $r['objecthubunganpesertafk'] != '') {
            $data = $data->where('ap.objecthubunganpesertafk', 'ilike', '%' . $r['objecthubunganpesertafk']);
        }
        if (isset($r['objectjeniskelaminfk']) && $r['objectjeniskelaminfk'] != '') {
            $data = $data->where('ap.objectjeniskelaminfk', 'ilike', '%' . $r['objectjeniskelaminfk']);
        }
        if (isset($r['objectpegawaifk']) && $r['objectpegawaifk'] != '') {
            $data = $data->where('ap.objectpegawaifk', 'ilike', '%' . $r['objectpegawaifk']);
        }
        if (isset($r['statusenabled']) && $r['statusenabled'] != '') {
            $data = $data->where('ap.statusenabled', '=', $r['statusenabled']);
        }

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

        $res = [
            'data' => $data,
            'count' => $data->count()
        ];
        return $this->respond($res);
    }


    public function store(Request $r)
    {
        DB::beginTransaction();
        try {
            $faker = Factory::create();
            $count = AsuransiPasien::count();
            $RQS =  $r['asuransipasien'];
            $codeUnique = $count < 1 ? 1 : $count + 1;

            if ($RQS['id'] == '') {
                $data = new AsuransiPasien();
                $data->id = (string)Uuid::uuid4();
                $data->statusenabled = true;
                $data->qasuransi = $codeUnique;
                $data->kodeexternal = $faker->regexify('[A-Z]{2}-') . $codeUnique;
                $transMessage = "Proses Simpan Data Berhasil";
            } else {
                $data = AsuransiPasien::where('id', $RQS['id'])->first();
                $data->statusenabled = $RQS['statusenabled'];
                $transMessage = "Proses Update Data Berhasil";
                $id =  $data->id;
            }
            $data->kdprovider = $RQS['kdprovider'];
            $data->kdprofile = (int)$this->kdProfile;
            $data->nmprovider =   $RQS['nmprovider'];
            $data->namaexternal =   $RQS['nmprovider'];
            $data->reportdisplay =   $RQS['nmprovider'];
            $data->tglakhirberlakulast =  $RQS['tglakhirberlakulast'];
            $data->tglmulaiberlakulast =  $RQS['tglmulaiberlakulast'];
            $data->notelpmobile =  $RQS['notelpmobile'];
            $data->notelpfixed =  $RQS['notelpfixed'];
            $data->tgllahir =  $RQS['tgllahir'];
            // $data->nocmfk =  $RQS['nocmfk'];
            $data->noasuransihead =  $RQS['noasuransihead'];
            $data->noasuransi =  $RQS['noasuransi'];
            $data->nippns =  $RQS['nippns'];
            $data->nikinstitusiasal =  $RQS['nikinstitusiasal'];
            $data->namapeserta =  $RQS['namapeserta'];
            $data->lastunitbagian =  $RQS['lastunitbagian'];
            $data->kdpenjaminpasien =  $RQS['kdpenjaminpasien'];
            $data->kdlastunitbagian =  $RQS['kdlastunitbagian'];
            $data->kdinstitusiasal =  $RQS['kdinstitusiasal'];
            $data->objectpegawaifk =  $RQS['objectpegawaifk'];
            $data->objectjeniskelaminfk =  $RQS['objectjeniskelaminfk'];
            $data->objecthubunganpesertafk =  $RQS['objecthubunganpesertafk'];
            $data->objectgolonganasuransifk =  $RQS['objectgolonganasuransifk'];
            $data->alamatlengkap =  $RQS['alamatlengkap'];
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
            $transMessage = "Something Went Wrong";
            DB::rollBack();
            $result = [
                "status" => 400,
                "result"  =>null
            ];
        }

        return $this->respond($result['result'], $result['status'], $transMessage);
    }

    public function asuransiPasienByID(Request $r)
    {
        $data = DB::table('asuransipasien_m as ap')
            ->leftJoin('golonganasuransi_m as gs', 'ap.objectgolonganasuransifk', '=', 'gs.id')
            ->leftJoin('hubunganpesertaasuransi_m as hp', 'ap.objecthubunganpesertafk', '=', 'hp.id')
            ->leftJoin('jeniskelamin_m as jk', 'ap.objectjeniskelaminfk', '=', 'jk.id')
            ->leftJoin('pegawai_m as peg', 'ap.objectpegawaifk', '=', 'peg.id')
            ->leftJoin('pasien_m as pas', 'ap.nocmfk', '=', 'pas.nocm')
            ->leftJoin('kelas_m as kls', 'ap.objectkelasdijaminfk', '=', 'kls.id')
            ->select(
                'ap.id',
                'ap.kdprofile',
                'ap.statusenabled',
                'ap.namaexternal',
                'ap.reportdisplay',
                'ap.kodeexternal',
                'ap.alamatlengkap',
                'ap.objectgolonganasuransifk',
                'gs.golonganasuransi',
                'ap.objecthubunganpesertafk',
                'hp.hubunganpeserta',
                'ap.objectjeniskelaminfk',
                'jk.jeniskelamin',
                'ap.objectpegawaifk',
                'peg.namalengkap',
                'ap.kdinstitusiasal',
                'ap.nocmfk',
                'pas.namapasien',
                'pas.nocm',
                'ap.objectkelasdijaminfk',
                'kls.namakelas',
                'ap.kdlastunitbagian',
                'ap.kdpenjaminpasien',
                'ap.lastunitbagian',
                'ap.namapeserta',
                'ap.nikinstitusiasal',
                'ap.nippns',
                'ap.noasuransi',
                'ap.noasuransihead',
                'ap.nocmfk',
                'ap.tgllahir',
                'ap.noidentitas',
                'ap.notelpfixed',
                'ap.notelpmobile',
                'ap.tglmulaiberlakulast',
                'ap.tglakhirberlakulast',
                'ap.qasuransi',
                'ap.jenispeserta',
                'ap.kdprovider',
                'ap.nmprovider',
            )
            ->where('ap.kdprofile', (int)$this->kdProfile)
            ->where('ap.statusenabled', true);
        if (isset($r['id']) && $r['id'] != '' && $r['id'] != 'undefined') {
            $data = $data->where('ap.id', $r['id']);
        }
        if (isset($r['id']) && $r['id'] != '' && $r['id'] != 'undefined') {
            $data = $data->where('ap.id', $r['id']);
        }
        $result = array(
            'asuransipasien' => $data,
            'as' => '@epic',
        );
        return $this->respond($result);
    }

    public function dropdownItem()
    {
        $res['jeniskelamin'] = JenisKelamin::mine()->get();
        $res['golonganasuransi'] = GolonganAsuransi::mine()->get();
        $res['hubunganpeserta'] = HubunganPesertaAsuransi::mine()->get();
        $res['pegawai'] = Pegawai::mine()->get();

        return $this->respond($res);
    }

    public function delete(Request $r)
    {
        DB::beginTransaction();
        try {
            $dataPS = AsuransiPasien::where('id', $r['id'])
                ->update([
                    'statusenabled' => false
                ]);
            DB::commit();
            $result = [
                "status" => 200,
                "result" => [
                    "data"  => $dataPS,
                    "as" => '@epic',
                ]
            ];
            $transMessage = "Proses Hapus Data Berhasil";
        } catch (\Exception $e) {
            DB::rollBack();
            $transMessage = "Something Went Wrong";
            $result = [
                "status" => 400,
                "result"  =>null
            ];
        }
        return $this->respond($result['result'], $result['status'], $transMessage);
    }
}
