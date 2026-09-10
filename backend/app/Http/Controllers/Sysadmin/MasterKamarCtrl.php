<?php

namespace App\Http\Controllers\Sysadmin;

use Faker\Factory;
use App\Traits\Valet;
use Ramsey\Uuid\Uuid;
use App\Models\Master\Kamar;
use App\Models\Master\Kelas;
use Illuminate\Http\Request;
use App\Models\Master\Ruangan;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class MasterKamarCtrl extends Controller
{
    use Valet;

    public function __construct()
    {
        parent::__construct($is_encrypt = true);
    }

    public function index(Request $request)
    {
        $data = DB::table('kamar_m as kmr')
                    ->leftjoin('kelas_m as kls', 'kmr.objectkelasfk','=','kls.id')
                    ->leftjoin('ruangan_m as rg','kmr.objectruanganfk','=','rg.id')
                    ->leftjoin('kasuspenyakit_m as kst','kmr.objectkasuspenyakitfk','=','kst.id')
                    ->select(
                        'kmr.id',
                        'kmr.qkamar',
                        'kmr.statusenabled',
                        'kmr.namaexternal',
                        'kmr.reportdisplay',
                        'kmr.kodeexternal',
                        'kmr.kdkamar',
                        'kmr.objectkelasfk',
                        'kmr.objectruangperawatankemenkesfk',
                        'kmr.objectruanganfk',
                        'kmr.objectkasuspenyakitfk',
                        'kls.namakelas',
                        'rg.namaruangan',
                        'kst.kasuspenyakit',
                        'kmr.namakamar',
                        'kmr.tglupdate',
                        'kmr.keterangan',
                        'kmr.jumlakamarisi',
                        'kmr.jumlakamarkosong',
                        'kmr.statusenabled',
                        'kmr.qtybed',
                        'kmr.kodesirs',
                    )
                    ->where('kmr.kdprofile',$this->kdProfile)
                    ->orderByDesc('kmr.namakamar');

        if (isset($request['id']) && $request['id'] != '') {
            $data = $data->where('kmr.id', '=',  $request['id']);
        }
        if (isset($request['qnama']) && $request['qnama'] != '') {
            $data = $data->where('kmr.namakamar', 'ilike', '%' . $request['qnama'] . '%');
        }
        if (isset($request['statusenabled']) && $request['statusenabled'] != '') {
            $data = $data->where('kmr.statusenabled', '=', $request['statusenabled']);
        }
        // if (isset($request['limit']) && $request['limit'] != '') {
        //     $data = $data->limit($request['limit']);
        // }
        // if (isset($request['offset']) && $request['offset'] != '') {
        //     $data = $data->offset($request['offset']);
        // }
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

        $result = [
            'data' => $data,
            'count' => $data->count()
        ];

        return $this->respond($result);
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            //region Save Jenis Kelamin
            $faker = Factory::create();
            $count = Kamar::count();
            $PSN =  $request['kamar'];
            $codeUnique = $count < 1 ? 1 : $count + 1 ;

            if ($PSN['id'] == '') {
                $data = new Kamar();
                $data->id = $this->SEQUENCE_MASTER(new Kamar(),'id',$this->kdProfile);//(string)Uuid::uuid4();
                $data->statusenabled = true;
                $data->kodeexternal = $faker->regexify('[A-Z]{2}-').$codeUnique;
                $data->kdkamar =  $codeUnique;
                $data->qkamar = $codeUnique;
                $transMessage = "Proses Simpan Data Berhasil";
            } else {
                $data = Kamar::where('id', $PSN['id'])->first();
                $data->statusenabled = $PSN['statusenabled'];
                $data->tglupdate = $PSN['tglupdate'];
                $transMessage = "Proses Update Data Berhasil";
                $id =  $data->id;
            }
            $data->kdprofile = (int)$this->kdProfile;
            $data->namakamar =  $PSN['namakamar'];
            $data->namaexternal =  $PSN['namakamar'];
            $data->reportdisplay =  $PSN['namakamar'];
            $data->objectkelasfk =  $PSN['objectkelasfk'];
            $data->objectruanganfk =  $PSN['objectruanganfk'];
            // $data->objectkasuspenyakitfk =  $PSN['objectkasuspenyakitfk'];
            $data->qtybed = $PSN['qtybed'];
            $data->jumlakamarisi = $PSN['jumlakamarisi'];
            $data->jumlakamarkosong = $PSN['jumlakamarkosong'];
            $data->keterangan = $PSN['keterangan'];
            $data->kodesirs = $PSN['kodesirs'];
            $data->save();
            DB::commit();
            // return $this->respond($data);
            $result = [
                "status" => 200,
                "message" => $transMessage,
                "result" => [
                    "data"  => $data,
                    "as" => '@epic',
                ],
            ];

        } catch (\Exception $e) {
            DB::rollBack();
            $result = array(
                "status" => 400,
                "message" => "Simpan Gagal !",
                "result"  => $e->getMessage(),
            );
        }

        return $this->respond($result['result'], $result['status'], $result['message']);
    }

    public function delete(Request $r)
    {
        DB::beginTransaction();
        try {
            $dataPS = Kamar::where('id', $r['id'])
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
                "result"  => [
                    "e"  => $e->getLine() . ' ' . $e->getMessage(),
                    ]
                ];
        }
        return $this->respond($result['result'], $result['status'], $transMessage);
    }

    public function dropdownItem()
    {
        $res['namakelas'] = Kelas::mine()->get();
        $res['namaruangan'] = Ruangan::mine()->get();

        return $this->respond($res);
    }

}
