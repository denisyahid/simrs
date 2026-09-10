<?php

namespace App\Http\Controllers\Sysadmin;

use Exception;
use Faker\Factory;
use App\Traits\Valet;
use Ramsey\Uuid\Uuid;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Master\MapRuanganToEMR;
use App\Models\Transaksi\EMR;
use Mockery\Exception\InvalidOrderException;

class MasterEMRCtrl extends Controller
{
    use Valet;

    public function __construct()
    {
        parent::__construct($is_encrypt = true);
    }

    public function index(Request $r)
    {

        $dataRaw = DB::table('emr_t as emr')
            ->where('emr.kdprofile', $this->kdProfile)
            ->where('emr.statusenabled', true)
            ->where('emr.namaemr', $r['namaemr'])
            ->select('emr.*')
            ->orderBy('emr.nourut');

        $dataRaw = $dataRaw->get();

        $dataraw3 = [];
        foreach ($dataRaw as $dataRaw2) {
            $dataraw3[] = array(
                'key' => $dataRaw2->id,
                'label' => $dataRaw2->caption,
                'icon' => 'pi pi-fw pi-folder',
                // 'expandedIcon' => 'pi pi-pw pi-folder-open',
                // 'collapsedIcon' => 'pi pi-fw pi-folder',
                'nourut' => $dataRaw2->nourut,
                'url_form' => $dataRaw2->url_form,
                'collection' => $dataRaw2->collection,
                'headfk' => $dataRaw2->headfk,
                'headbundlingfk' => $dataRaw2->headbundlingfk,
                'data' => $dataRaw2
            );
        }
        $data = $dataraw3;

        function recursiveElements($data)
        {
            $elements = [];
            $tree = [];
            foreach ($data as &$element) {
                $id = $element['key'];
                $parent_id = $element['headfk'];

                $elements[$id] = &$element;
                if (isset($elements[$parent_id])) {
                    $elements[$parent_id]['children'][] = &$element;
                } else {
                    if ($parent_id <= 10) {
                        $tree[] = &$element;
                    }
                }
            }
            return $tree;
        }

        $data = recursiveElements($data);

        $result = array(
            'data' => $data,
            'total' => count($dataRaw),
            'message' => '@epic',
        );

        return $this->respond($result);
    }

    public function save(Request $r)
    {
        DB::beginTransaction();
        try {
            if ($r->input('id') == '') {
                $data = new EMR();
                $data->id = $this->SEQUENCE_MASTER(new EMR(), 'id', $this->kdProfile);
                $data->statusenabled = true;
                $data->kdprofile = $this->kdProfile;
                if ($r['url_form'] != null) {
                    $cek = EMR::where('url_form', $r['url_form'])->first();
                    if (!empty($cek)) {
                        DB::rollback();
                        $transMessage = "URL Form sudah ada di " . $cek->caption;
                        $result = array(
                            "status" => 400,
                            "result" => array(
                                "as" => '@epic',
                            ),
                        );
                        return $this->respond($result['result'], $result['status'], $transMessage);
                    }
                }
                if ($r['collection'] != null) {
                    $cek = EMR::where('collection', $r['collection'])->first();
                    if (!empty($cek)) {
                        DB::rollback();
                        $transMessage = "Collection sudah ada di " . $cek->caption;
                        $result = array(
                            "status" => 400,
                            "result" => array(
                                "as" => '@epic',
                            ),
                        );
                        return $this->respond($result['result'], $result['status'], $transMessage);
                    }
                }
            } else {
                $data = EMR::where('id', $r->input('id'))->first();
                $data->reportdisplay = $data->reportdisplay;
            }

            $data->namaemr =  $r['namaemr'];
            $data->caption = $r['caption'];
            $data->nourut = $r['nourut'];
            $data->url_form = $r['url_form'];
            $data->collection = $r['collection'];
            $data->headfk = $r['headfk'];
            $data->headbundlingfk = $r['headbundlingfk'];
            $data->icon = 'fas fa-file';
            $data->save();

            DB::commit();
            $transMessage = "Sukses ";
            $result = array(
                "status" => 200,
                "result" => array(
                    "as" => '@epic',
                ),
            );
        } catch (Exception $e) {
            DB::rollback();
            $transMessage = "Simpan Gagal";
            $result = array(
                "status" => 400,
                "result" => array(
                    "as" => '@epic',
                ),
            );
        }
        return $this->respond($result['result'], $result['status'], $transMessage);
    }

    public function delete(Request $r)
    {
        DB::beginTransaction();
        try {
            EMR::where('id', $r->input('id'))->update([
                'statusenabled' => false
            ]);
            DB::commit();

            $transMessage = "Sukses ";
            $result = array(
                "status" => 200,
                "result" => array(
                    "as" => '@epic',
                ),
            );
        } catch (Exception $e) {
            DB::rollback();
            $transMessage = "Hapus Gagal";
            $result = array(
                "status" => 400,
                "result" => array(
                    "as" => '@epic',
                ),
            );
        }
        return $this->respond($result['result'], $result['status'], $transMessage);
    }

    public function nourut(Request $r)
    {

        if ($r->input('id')) {
            $data = EMR::where('headfk', $r->input('id'))
                ->where('statusenabled', true)
                ->where('kdprofile', $this->kdProfile)
                ->orderByDesc('nourut')
                ->get();
            if (count($data) > 0) {
                $data = $data[0]->nourut + 1;
            } else {
                $data = EMR::where('id', $r->input('id'))
                    ->first()->nourut + 1;
            }
        } else {
            $data = EMR::where('namaemr', 'asesmen')
                ->where('statusenabled', true)
                ->whereNotNull('nourut')
                ->whereNull('headfk')
                ->where('kdprofile', $this->kdProfile)
                ->orderByDesc('nourut')
                ->first()->nourut + 1;
        }

        return $this->respond($data);
    }
    public function saveMap(Request $r)
    {
        DB::beginTransaction();
        try {
            //region Save Ruangan To Produk

            $delete = MapRuanganToEMR::where('objectruanganfk', $r['objectruanganfk'])
            ->update(['statusenabled'=> false]);
            foreach($r['detail'] as $item){
                $dataPS = new MapRuanganTOEMR();
                $dataPS->id = $this->SEQUENCE_MASTER(new MapRuanganToEMR(),'id',$this->kdProfile);//$this->Uuid4();
                $dataPS->kdprofile = (int)$this->kdProfile;
                $dataPS->statusenabled = true;
                $dataPS->objectruanganfk =  $r['objectruanganfk'];
                $dataPS->objectdepartemenfk = $r['objectdepartemenfk'];
                $dataPS->emrfk = $item['emrfk'];
                $dataPS->save();
            }
              
            //endregion

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
                    "data"  => $dataPS,
                    "as" => '@epic',
                ),
            );
        } else {
            $transMessage = "Simpan Gagal";
            DB::rollBack();

            $result = array(
                "status" => 400,
                "result"  => $e->getMessage()

            );
        }
        return $this->respond($result['result'], $result['status'], $transMessage);
    }
    public function getMap(Request $r)
    {
        $data  = DB::table('mapruangantoemr_t as mp')
        ->join('emr_t as pr', 'mp.emrfk', '=', 'pr.id')
        ->join('ruangan_m as ru', 'mp.objectruanganfk', '=', 'ru.id')
        ->select(
            'mp.*',
            'pr.caption as label',
            'pr.id as value',
            'ru.namaruangan',
            'pr.url_form',
            'pr.collection',
        )
            ->where('mp.statusenabled', true)
            ->where('mp.kdprofile', $this->kdProfile);
     
        if (isset($r['objectruanganfk']) && $r['objectruanganfk'] != '') {
            $data = $data->where('mp.objectruanganfk', '=', $r['objectruanganfk'] );
        }
        if (isset($r['emrfk']) && $r['emrfk'] != '') {
            $data = $data->where('mp.emrfk', '=', $r['emrfk'] );
        }
        if (isset($r['_total']) && $r['_total'] != '') {
        }
        if (isset($r['offset']) && $r['offset'] != '') {
            $data = $data->offset($r['offset']);
        }
    
        if (isset($r['rows']) && $r['rows'] != '') {
            $data = $data->limit($r['rows']);
        }
        $data = $data->get();

        $res['data'] = $data;
        return $this->respond($res);
    }
}
