<?php

namespace App\Http\Controllers\General;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SysAdminCtrl extends Controller
{

    public function listMenu(Request $r)
    {
        $dataraw3 = [];
        $dataRaw = DB::table('objekmodulaplikasi_s as oma')
            ->join('mapobjekmodulaplikasitomodulaplikasi_s as acdc', 'acdc.objekmodulaplikasiid', '=', 'oma.id')
            ->join(
                'maploginusertomodulaplikasi_s as maps',
                function ($join) {
                    $join->on('maps.objectmodulaplikasifk', '=', 'acdc.modulaplikasiid');
                    //					$join->on('maps.objectmodulaplikasifk', '=', 'acdc.modulaplikasiid');
                }
            )
            ->join('modulaplikasi_s as ma', 'ma.id', '=', 'acdc.modulaplikasiid')
            ->where('oma.statusenabled', true)
            ->where('ma.reportdisplay', 'Menu')
            ->where('maps.statusenabled', true)
            ->where('maps.objectloginuserfk', $r['idUser'])
            ->select(
                'oma.id',
                'oma.kdobjekmodulaplikasihead',
                'oma.objekmodulaplikasi',
                'oma.icon',
                'oma.alamaturlform',
                'ma.modulaplikasi',
                'acdc.modulaplikasiid',
                'oma.kodeexternal',
                'oma.ishide'
            )
            ->groupBy(
                'oma.id',
                'oma.kdobjekmodulaplikasihead',
                'oma.objekmodulaplikasi',
                'oma.alamaturlform',
                'ma.modulaplikasi',
                'acdc.modulaplikasiid',
                'oma.kodeexternal',
                'oma.nourut',
                'oma.icon'
            )
            ->orderBy('oma.nourut');
        $dataRaw = $dataRaw->get();
        foreach ($dataRaw as $dataRaw2) {
            //                if ((integer)$dataRaw2->id < 100) {
            if ($dataRaw2->kdobjekmodulaplikasihead == null) {
                if ($dataRaw2->alamaturlform != null || $dataRaw2->alamaturlform != '') {
                    $dataraw3[] = array(
                        'id' => $dataRaw2->id,
                        'parent_id' => 0,
                        'name' => $dataRaw2->objekmodulaplikasi,
                        'link' => $dataRaw2->alamaturlform,
                        'icon' => $dataRaw2->icon != null ? $dataRaw2->icon : 'chevron-right',
                        'ishide' => $dataRaw2->ishide
                    );
                } else {
                    $dataraw3[] = array(
                        'id' => $dataRaw2->id,
                        'parent_id' => 0,
                        'name' => $dataRaw2->objekmodulaplikasi,
                        'icon' => $dataRaw2->icon != null ? $dataRaw2->icon : 'chevron-right',
                        'ishide' => $dataRaw2->ishide
                    );
                }
            } else {
                if ($dataRaw2->kdobjekmodulaplikasihead != null) {
                    if ($dataRaw2->alamaturlform != null || $dataRaw2->alamaturlform != '') {
                        $dataraw3[] = array(
                            'id' => $dataRaw2->id,
                            'parent_id' => $dataRaw2->kdobjekmodulaplikasihead,
                            'name' => $dataRaw2->objekmodulaplikasi,
                            'link' => $dataRaw2->alamaturlform,
                            'icon' => $dataRaw2->icon != null ? $dataRaw2->icon : 'chevron-right',
                            'ishide' => $dataRaw2->ishide
                        );
                    } else {
                        $dataraw3[] = array(
                            'id' => $dataRaw2->id,
                            'parent_id' => $dataRaw2->kdobjekmodulaplikasihead,
                            'name' => $dataRaw2->objekmodulaplikasi,
                            'icon' => $dataRaw2->icon != null ? $dataRaw2->icon : 'chevron-right',
                            'ishide' => $dataRaw2->ishide
                            // 'link' => $dataRaw2->alamaturlform
                        );
                    }
                } else {
                    if ($dataRaw2->modulaplikasiid == $r['id']) {
                        if ($dataRaw2->alamaturlform != null || $dataRaw2->alamaturlform != '') {
                            $dataraw3[] = array(
                                'id' => $dataRaw2->id,
                                'parent_id' => $dataRaw2->kdobjekmodulaplikasihead,
                                'name' => $dataRaw2->objekmodulaplikasi,
                                'link' => $dataRaw2->alamaturlform,
                                'icon' => $dataRaw2->icon != null ? $dataRaw2->icon : 'chevron-right',
                                'ishide' => $dataRaw2->ishide
                            );
                        } else {
                            $dataraw3[] = array(
                                'id' => $dataRaw2->id,
                                'parent_id' => $dataRaw2->kdobjekmodulaplikasihead,
                                'name' => $dataRaw2->objekmodulaplikasi,
                                'icon' => $dataRaw2->icon != null ? $dataRaw2->icon : 'chevron-right',
                                'ishide' => $dataRaw2->ishide
                            );
                        }
                    }
                }
            }
        }
        $data = $dataraw3;
        function recursiveElements($data)
        {
            $elements = [];
            $tree = [];
            foreach ($data as &$element) {
                $id = $element['id'];
                $parent_id = $element['parent_id'];

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


        return $this->respond($data);
    }
}
