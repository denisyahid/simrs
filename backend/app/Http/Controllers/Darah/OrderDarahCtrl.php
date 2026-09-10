<?php

namespace App\Http\Controllers\Darah;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Laboratorium\OrderLaboratoriumCtrl;
use App\Models\Transaksi\StrukOrder;
use App\Traits\Valet;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderDarahCtrl extends Controller
{
    use Valet;
    public function listDropdown(Request $request)
    {
        $res['golonganDarah'] = DB::table('golongandarah_m')->select('id', 'golongandarah')->where('kdprofile', $this->kdProfile)
            ->where('statusenabled', true)->get();
        $res['jenis'] = DB::table('detailjenisproduk_m as dt')->select('dt.id', 'dt.detailjenisproduk')->where('dt.objectdepartemenfk', $this->settingFix('idDepartemenBankDarah'))->where('dt.statusenabled', true)->get();
        return $this->respond($res);
    }

    public function  simpanOrderLab(Request $request)
    {
        $request['objectruangantujuanfk'] = $this->settingFix('ruanganBankDarah');
        $request['departemenfk'] = $this->settingFix('idDepartemenBankDarah');
        return (new OrderLaboratoriumCtrl())->simpanOrderLab($request);
    }

    public function listRiwayatOrder(Request $r)
    {
        $kdProfile  =  $this->kdProfile;
        $depLab     =  $this->settingFix('idDepartemenBankDarah');
        $nocmfk = '';
        $norec_pd = '';
        if (isset($r['nocmfk']) && $r['nocmfk'] != '') {
            $nocmfk = " and pd.nocmfk='" . $r['nocmfk'] . "'";
        }
        if (isset($r['norec_pd']) && $r['norec_pd'] != '') {
            $norec_pd = " and pd.norec='" . $r['norec_pd'] . "'";
        }
        $data = collect(DB::select("select so.tglorder,so.noorder,
        pr.id,pr.namaproduk,op.qtyproduk,so.norec,
        so.tglpelayananawal as tgloperasi,so.estimasiwaktuoperasi,
        ru.namaruangan as ruanganasal,p.namalengkap as dokter,
        case when so.statusorder = 1 then 'verifikasi'
        when so.statusorder = 2 then 'selesai'
        else 'pending' end as status,
        case when so.statusorder = 1 then 'info'
        when so.statusorder = 2 then 'success'
        else 'warning' end as color_status,
        op.norec as norec_op,go.detailjenisproduk as golongandarah,so.qtyproduk as qty
        from strukorder_t as so
        left join orderpelayanan_t as op on op.noorderfk = so.norec
        inner join pasiendaftar_t as pd on pd.norec=so.noregistrasifk
        inner join pasien_m as ps on ps.id=pd.nocmfk
        left join produk_m as pr on pr.id=op.objectprodukfk
        inner join ruangan_m as ru on ru.id=so.objectruanganfk
        inner join ruangan_m as ru2 on ru2.id=so.objectruangantujuanfk
        left join pegawai_m as p on p.id=so.objectpegawaiorderfk
        left join detailjenisproduk_m as go on go.id=so.golongandarahfk
        where
        pd.kdprofile=$kdProfile
        and pd.statusenabled=true
        and so.statusenabled=true
        $norec_pd
        $nocmfk
        and ru2.objectdepartemenfk  IN ($depLab)
        union all

        select pp.tglpelayanan as tglorder,null as noorder,
        pr.id,pr.namaproduk,pp.jumlah as qtyproduk ,pp.norec,
        null as tgloperasi, null as estimasiwaktuoperasi,
        ru2.namaruangan as ruanganasal,p.namalengkap as dokter,
        'selesai' as status,'success' as color_status,
        null as norec_op,null  as jenisoperasi,null as qty
        from pelayananpasien_t as pp
        inner join antrianpasiendiperiksa_t as apd on apd.norec=pp.noregistrasifk
        inner join pasiendaftar_t as pd on pd.norec=apd.noregistrasifk
        inner join ruangan_m as ru on ru.id=apd.objectruanganfk
        inner join ruangan_m as ru2 on ru2.id=pd.objectruanganlastfk
        inner join pasien_m as ps on ps.id=pd.nocmfk
        inner join produk_m as pr on pr.id=pp.produkfk
        left join pegawai_m as p on p.id=apd.objectpegawaifk
        where
        pd.kdprofile=$kdProfile
        and pd.statusenabled=true
        and pp.strukresepfk is NULL
        and pp.strukorderfk is null
        and ru2.objectdepartemenfk  IN ($depLab)
        $norec_pd
        $nocmfk
        "));

        $sama = false;
        $group  = [];
        foreach ($data as $item) {
            $sama = false;
            $i = 0;
            foreach ($group as $itemg) {
                if ($item->norec == $group[$i]['norec']) {
                    $sama = true;
                }
                $i = $i + 1;
            }
            if ($sama == false) {
                $dataDetail0 = [];
                foreach ($data as $gg) {
                    if ($gg->norec == $item->norec) {
                        $dataDetail0[] = array(
                            'namaproduk' =>  $gg->namaproduk,
                        );
                    };
                }
                $group[] = array(
                    'tglorder' => $item->tglorder,
                    'noorder' => $item->noorder,
                    'norec' => $item->norec,
                    'ruanganasal' => $item->ruanganasal,
                    'dokter' => $item->dokter,
                    'color_status' => $item->color_status,
                    'golongandarah' => $item->golongandarah,
                    'status' => $item->status,
                    'qty' => $item->qty,
                    'details' => $dataDetail0
                );
            }
        }
        return $this->respond($group);
    }
    public function hapusOrderDarah(Request $request)
    {
        DB::beginTransaction();
        try {
            StrukOrder::where('noorder', $request->noorder)
                ->where('kdprofile', $this->kdProfile)
                ->delete();

            $transStatus = 'true';
        } catch (Exception $e) {
            $transStatus = 'false';
        }
        if ($transStatus == 'true') {
            $transMessage = "Sukses";
            DB::commit();
            $result = array(
                "status" => 200,
                "result" => array(
                    "data" => null,
                    "as" => '@epic',
                ),
            );
        } else {
            $transMessage = "Hapus Data Gagal";
            DB::rollBack();
            $result = array(
                "status" => 400,
                "result" => null
            );
        }
        return $this->respond($result['result'], $result['status'], $transMessage);
    }
    public function hasilOrderDarah(Request $request)
    {
        $laboratRad = DB::table('orderpelayanan_t as op')
            ->join('strukorder_t as so', 'op.noorderfk', '=', 'so.norec')
            ->join('produk_m as pr', 'pr.id', '=', 'op.objectprodukfk')
            ->join('pasiendaftar_t as pd', 'pd.norec', '=', 'so.noregistrasifk')
            ->join('ruangan_m as ru', 'ru.id', '=', 'so.objectruangantujuanfk')
            ->where('so.kdprofile', $this->kdProfile)
            ->leftjoin('hasildarah_t as ar', 'ar.pelayananpasienfk', '=', 'ar.pegawaifk')
            ->where('so.noregistrasifk', $request['norec_pd'])
            ->get();
    }
}
