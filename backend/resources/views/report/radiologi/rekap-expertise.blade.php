@extends('template.layout')
@section('title', 'Expertise Radiologi')
@section('page-style')
<style>
    .infopasien tbody tr td {
        vertical-align: top;
        padding: 2px;
    }
    /* .infopasien tbody tr td:first-child, .infopasien tbody tr td:nth-child(4) {
        font-weight: bold;
    } */
</style>
@endsection

@section('content')
    <tr>
        <td>
            <table width="100%" cellspacing="0" cellpadding="0">
                <tr>
                    <td align="center">
                        {{-- <hr style="border:0.5px solid #000;margin-top:5px" /> --}}
                        <font style="font-size: 14pt;font-weight: bold" color="#000000">LAPORAN REKAPITULASI EXPERTISE</font>
                        <hr style="border:0.5px solid #000;margin-top:5px" />
                    </td>
                </tr>
                <tr>
                    <td align="center">
                        <font style="font-size: 12pt">{{$tglAwal}} s/d {{$tglAkhir}}</font>
                    </td>
                </tr>
                <tr>
                    <td align="center">
                        <font style="font-size: 12pt">Dokter Pemeriksa : <b>{{$dokter ? $dokter->namalengkap : "-"}}</b></font>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td style="padding-top:10px;">
            {{-- {{dd($data)}} --}}
            <table cellspacing="0" cellpadding="0" border="1" width="100%" class="infopasien">
                <tr>
                    <td style="font-size: 12pt">Nama Kelompok</td>
                    <td style="font-size: 12pt">Nama Pelayanan</td>
                    <td style="font-size: 12pt">Qty</td>
                </tr>
                @php
                    $total = 0;
                @endphp
                @foreach($data->groupBy('detailjenisproduk') as $item)
                    {{-- {{dd($item[0])}} --}}
                    {{-- <tr>{{dd($item)}}</tr> --}}
                    <tr>
                        <td rowspan="{{count($item)+1}}" style="font-size: 12pt">{{$item[0]->detailjenisproduk}}</td>
                        @foreach($item as $data)
                        <tr>
                            <td style="font-size: 12pt">{{$data->namaproduk}}</td>
                            <td style="font-size: 12pt">{{$data->qty}}</td>
                        </tr>
                        @php
                            $total += $data->qty;
                        @endphp
                        @endforeach
                    </tr>
                @endforeach
                <tr>
                    <th colspan="2" style="font-size: 11pt; font-weight: 600; text-align: left;">
                        Total
                    </th>
                    <th style="font-size: 11pt;font-weight:600;text-align: left">
                        {{$total}}
                    </th>
                </tr>
            </table>

        </td>
    </tr>


@endsection

