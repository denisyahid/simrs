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

    .tableHead,td{
        font-size: 12pt
    }

    .tableDetail{
        margin-top: 20px;
    }

    .tableDetail,
    .tableDetail tr th,
    .tableDetail tr td{
        border: 1px solid black;
        border-collapse: collapse;
        font-size: 12pt;
        font-weight: normal
    }
    </style>
@endsection
@section('content')
<tr>
    <td width="100%">
        <table width="100%" cellspacing="0" cellpadding="0">
            <tr>
                <td align="center" colspan="3">
                    {{-- <hr style="border:0.5px solid #000;margin-top:5px" /> --}}
                    <font style="font-size: 14pt;font-weight: bold" color="#000000">{{$dataReport['judul']}}</font>
                    <hr style="border:0.5px solid #000;margin-top:5px; width:100%" />
                </td>
            </tr>
        </table>
        <table width="100%" cellspacing="0" cellpadding="0" class="tableHead">
            <tr>
                <td align="left" width="20%">
                    Dari
                </td>
                <td align="left">
                    :
                </td>
                <td align="left">
                    {{$dataReport['head']->namaruangan}}
                </td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <!-- <td>({{$dataReport['head']->namalengkap}})</td> -->
            </tr>
            <tr>
                <td align="left">
                    Kepada
                </td>
                <td align="left">
                    :
                </td>
                <td align="left">
                    {{$dataReport['head']->namaruangan2}}
                </td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <!-- <td>({{$dataReport['head']->pegawaiorder}})</td> -->
            </tr>
            <tr>
                <td align="left">
                    Tanggal kirim
                </td>
                <td align="left">
                    :
                </td>
                <td align="left">
                    {{$dataReport['head']->tglkirim}}
                </td>
            </tr>
            <tr>
                <td align="left">
                    Keterangan
                </td>
                <td align="left">
                    :
                </td>
                <td align="left">
                    {{$dataReport['head']->keterangan}}
                </td>
            </tr>
            <tr>
                <td align="left">
                    No. Kirim
                </td>
                <td align="left">
                    :
                </td>
                <td align="left">
                    {{$dataReport['head']->nokirim}}
                </td>
                <td align="left">
                    No. Order
                </td>
                <td align="left">
                    :
                </td>
                <td align="left">
                    {{$dataReport['head']->noorder}}
                </td>

            </tr>
        </table>
        <table class="tableDetail" width="100%">
            <tr>
                <th>No</th>
                <th>Kode Produk</th>
                <th>Nama Barang</th>
                <th>Satuan</th>
                <th>Jml Kirim</th>
                <th>Avg Pemakaian</th>
                <th>Sisa Stok</th>
                {{-- <th>Tgl Kadaluarsa</th> --}}
                {{-- <th>Batch</th> --}}
            </tr>
            @php
                $no = 1;
                $total = 0;
            @endphp
            @foreach($dataReport['detail'] as $item)
                <tr>
                    {{-- {{dd($item)}} --}}
                    <td>{{$no}}</td>
                    <td>{{$item['kdproduk']}}</td>
                    <td>{{$item['namaproduk']}}</td>
                    <td>{{$item['satuanview']}}</td>
                    <td>{{$item['jumlah']}}</td>
                    <td>{{$item['avgpemakaian']}}</td>
                    <td>{{$item['sisastok']}}</td>
                    <!-- <td style="text-align:right">{{number_format($item['hargasatuan'],2,",",".")}}</td>
                    <td style="text-align:right">{{number_format($item['total'], 2, ",", ".")}}</td> -->
                    {{-- <td>{{$item['tglkadaluarsa']}}</td> --}}
                    {{-- <td>{{$item['nobatch']}}</td> --}}
                </tr>
                @php
                    $no++;
                    $total += $item['total']
                    @endphp
            @endforeach
            <!-- <tr>
                <td align="center" colspan="2">Jumlah</td>
                <td align="center" colspan="6" style="text-align:right">{{number_format($total, 2, ",", ".")}}</td>
            </tr> -->
        </table>
        <table style="font-size: 12pt; margin-top: 50px;">
            <tr>
                <td width="40%"></td>
                <td>Bali, {{ \Carbon\Carbon::parse($dataReport['head']->tglkirim)->isoFormat('DD MMMM Y')}}</td>
            </tr>
            <tr>
                <td width="30%">Disiapkan Oleh: </td>
                <td width="40%">Diserahkan Oleh:</td>
                <td width="30%">Mengetahui:</td>
            </tr>
            <tr style="height: 50px">
                <td></td>
                <td></td>
            </tr>
            <tr width="75%">
          
                <td width="30%">(...................................)</td>
                <td width="40%">(...................................)</td>
                <td width="30%">(...................................)</td>
              
            </tr>
        </table>

        <table style="font-size: 12pt; margin-top: 50px;">
            <tr>
                <td width="40%">CATATAN :</td>
                <td></td>
            </tr>
            <tr>
                <td width="40%"></td>
                <td></td>
            </tr>
            
        </table>
    </td>
</tr>
@endsection
