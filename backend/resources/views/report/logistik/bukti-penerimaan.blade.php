@extends('template.layout')
@section('title',  $dataReport['judul'] )
@section('page-style')
    <style>
    .class-2 {
        border-top:1px solid black;border-bottom:1px solid black;
    }
    .class-3 {
        border-top:1px dashed black;border-bottom:1px dashed black;
    }
    .th-init{
        padding-top: 5px !important;
        padding-bottom: 5px !important;
    }
    .td-init{
        padding-top: 5px !important;
    }
    </style>
@endsection
@section('content')
    <tr>
        <td>
            <table width="100%" cellspacing="0" cellpadding="0">
                <tr>
                    <td align="center" height="80px">
                        <font style="font-size: 16pt;font-weight: bold" color="#000000">{{ $dataReport['judul'] }}</font>
                    </td>
                </tr>
            </table>
        </td>
    </tr>

    <tr>
        <td>
            <table width="100%" cellspacing="0" cellpadding="0">
                <tr>
                    <td width="13%"><font style="font-size: 11pt;" color="#000000">No. PO</font></td>
                    <td width="45%"><font style="font-size: 11pt;" color="#000000">: {{ $dataReport['datas']->nosppb }} </font></td>
                    <td width="13%"><font style="font-size: 11pt;" color="#000000">No. Faktur Penjualan</font></td>
                    <td width="35%"><font style="font-size: 11pt;" color="#000000">: {{ $dataReport['datas']->nofaktur }}</font></td>
                </tr>
                <tr>
                    <td width="13%"><font style="font-size: 11pt;" color="#000000">No. Kontrak</font></td>
                    <td width="45%"><font style="font-size: 11pt;" color="#000000">: {{ $dataReport['datas']->nokontrak }}</font></td>
                    <td width="13%"><font style="font-size: 11pt;" color="#000000">Tgl. Faktur Penjualan</font></td>
                    <td width="35%"><font style="font-size: 11pt;" color="#000000">: {{ $dataReport['datas']->tglfaktur }} </font></td>
                </tr>
                <tr>
                    <td width="13%"><font style="font-size: 11pt;" color="#000000">Tgl. Kontrak</font></td>
                    <td width="45%"><font style="font-size: 11pt;" color="#000000">: {{ $dataReport['datas']->tglkontrak }}</font></td>
                    <td width="13%"><font style="font-size: 11pt;" color="#000000">No. Terima</font></td>
                    <td width="35%"><font style="font-size: 11pt;" color="#000000">: {{ $dataReport['datas']->nostruk }}</font></td>
                </tr>
                <tr>
                    <td width="13%"><font style="font-size: 11pt;" color="#000000">Kode Gudang</font></td>
                    <td width="45%"><font style="font-size: 11pt;" color="#000000">: {{ $dataReport['datas']->gudang }}</font></td>
                    <td width="13%"><font style="font-size: 11pt;" color="#000000">Kode Supplier</font></td>
                    <td width="35%"><font style="font-size: 11pt;" color="#000000">: {{ $dataReport['datas']->rekanan }}</font></td>
                </tr>
                <tr>
                    <td width="13%"><font style="font-size: 11pt;" color="#000000">Sumber Dana</font></td>
                    <td width="45%"><font style="font-size: 11pt;" color="#000000">: {{ $dataReport['datas']->asalproduk }}</font></td>
                    <td width="13%"><font style="font-size: 11pt;" color="#000000">Keterangan</font></td>
                    <td width="35%"><font style="font-size: 11pt;" color="#000000">: {{ $dataReport['datas']->keteranganambil ? $dataReport['datas']->asalproduk : '-' }} </font></td>
                </tr>
            </table>
        </td>     
    </tr>

    <tr>
        <td style="padding-top:2rem">
            <table width="100%" cellspacing="0" cellpadding="0" align="center" >
                <tr>
                    <th class="class-2 th-init"><span>No</span></th>
                    <th class="class-2 th-init" style="text-align: left;"><span>Kode Barang</span></th>
                    <th class="class-2 th-init"><span>Nama Barang</span></th>
                    <th class="class-2 th-init" style="text-align: left;"><span>Jenis Barang</span></th>
                    <th class="class-2 th-init" style="text-align: left;"><span>Satuan</span></th>
                    <th class="class-2 th-init" style="text-align: left;"><span>Tgl Kadaluarsa</span></th>
                    <th class="class-2 th-init" style="text-align: left;"><span>No Batch</span></th>
                    <th class="class-2 th-init" style="text-align: center;"><span>Qty</span></th>
                    <th class="class-2 th-init" style="text-align: center;"><span>Harga Satuan</span></th>
                    <th class="class-2 th-init" style="text-align: center;"><span>Diskon</span></th>
                    <th class="class-2 th-init" style="text-align: center;"><span>PPN</span></th>
                    <th class="class-2 th-init" style="text-align: center;"><span>Total</span></th> 
                </tr>
                @foreach ($dataReport['detail'] as $item)
                    <tr style="text-align: left;">
                        <td class="td-init"><span class="text-biasa" >{{$loop->iteration}}</span></td> 
                        <td class="td-init" width="10%"><span class="text-biasa">{{$item->kdproduk}}</span></td>    
                        <td class="td-init" width="15%"><span class="text-biasa">{{$item->namaproduk}}</span></td>
                        <td class="td-init" width="10%"><span class="text-biasa">{{$item->detailjenisproduk}}</span></td>     
                        <td class="td-init" width="10%"><span class="text-biasa">{{$item->satuanstandar}}</span></td>   
                        <td class="td-init" width="12%">
                            <span class="text-biasa">
                                {{ \Carbon\Carbon::parse($item->tglkadaluarsa)->format('d/m/Y') }}
                            </span>
                        </td>  
                        <td class="td-init" width="6%"><span class="text-biasa">{{$item->nobatch}}</span></td>  
                        <td class="td-init" style="text-align: right;"><span class="text-biasa">{{$item->qtyproduk}}</span></td>   
                        <td class="td-init" style="text-align: right;"><span class="text-biasa">{{ number_format($item->hargasatuan, 2, ',', '.') }}</span></td>   
                        <td class="td-init" style="text-align: right;"><span class="text-biasa">{{ number_format($item->hargadiskon, 2, ',', '.') }}</span></td>   
                        <td class="td-init" style="text-align: right;"><span class="text-biasa">{{ number_format($item->hargappn, 2, ',', '.') }}</span></td>   
                        <td class="td-init" style="text-align: right;"><span class="text-biasa">{{ number_format($item->totalall, 2, ',', '.') }}</span></td>    
                    </tr>                    
                @endforeach

            </table>
        </td>
    </tr>

    <tr>
        <td>
            <table  width="100%" cellspacing="0" cellpadding="0">
                <tr style="text-align: end;">
                    <th class="class-3 th-init"><span>JUMLAH</span></th>
                    <th class="class-3 th-init" style="padding-right:10px !important"><span>{{ number_format($dataReport['datas']->totalharusdibayar, 2, '.', ',') }}</span></th>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td style="padding-top:20px">
            <table width="100%" cellspacing="0" cellpadding="0">
                <tr>
                    <td>
                        <span class="text-biasa">TERBILANG :
                    </td>
                </tr>
                <tr>
                    <td>
                        <span class="text-biasa"><i>#
                                {{ strtoupper(App\Traits\Valet::static_terbilang($dataReport['datas']->totalharusdibayar)) }} #</i>
                    </td>
                </tr>
            </table>
        </td>
    </tr>

    <tr>
        <td style="padding-top:3rem">
            <table width="100%" style="border: 0" cellspacing="0" cellpadding="0">
                <tr style="height: 100px;vertical-align: top;">
                    <td width="33%" style="text-align:center;">
                        <font style="font-size: 11pt;" color="#000000">
                            Yang Menyerahkan<br />
                        </font>
                    </td>
                    <td width="33%" style="text-align:center;">
                        <font style="font-size: 11pt;" color="#000000">
                            Mengetahui<br />
                        </font>
                    </td>
                    <td width="33%" style="text-align:center">
                        <font style="font-size: 11pt;" color="#000000">
                            Yang Menerima<br />
                        </font>
                    </td>
                </tr>                                              
                <tr style="height: 100px;vertical-align: top;">
                    <td width="33%" style="text-align:center;">
                        <font style="font-size: 11pt;" color="#000000">
                            {{ $dataReport['pegawaipenyerah'] }}<br />
                            {{ $dataReport['nippenyerah'] }} 
                        </font></td>
                    <td width="33%" style="text-align:center;"><font style="font-size: 11pt;" color="#000000">
                        <font style="font-size: 11pt;" color="#000000">
                            {{ $dataReport['pegawaimengetahui'] }}<br />
                            {{ $dataReport['nipmengetahui'] }} 
                        </font></td>
                    </td>
                    <td width="33%" style="text-align:center">
                        <font style="font-size: 11pt;" color="#000000">
                            {{ $dataReport['pegawaipenerima'] }}<br />
                            {{ $dataReport['nippenerima'] }}
                        </font>
                    </td>
                </tr>
            </table>
        </td>
    </tr>    
    
@endsection
