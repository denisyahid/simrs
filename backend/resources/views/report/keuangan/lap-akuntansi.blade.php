@extends('template.layout')
@section('title', 'Cetak Laporan Auntansi')
@section('page-style')
    <style>
        .border {
            border: 1px solid #202020;
        }

        .borderr tr th {
            border-bottom: 1px solid #202020;
        }
    </style>
@endsection
@section('content')
    <table class="bayangprint" width="{{ $pageWidth }}" cellspacing="0" cellpadding="0" bgcolor="#FFFFFF" border="0"
        style="padding:0px">
        <tbody>
            <tr>
                <td width="100%">
                    <table width="100%" cellspacing="0" cellpadding="0">
                        <tr>
                            <td align="center" height="80px">
                                <span style="font-size: 14pt;" color="#000000">
                                    <b>{{$request['judul']}}</b>
                                    <br />
                                    <span style="font-size:12pt;">
                                        Untuk Periode {!! $request['blnstring'] !!}
                                        {{-- Untuk Periode yang Berakhir {!! strtoupper($request['tglAkhir']) !!} --}}
                                    </span>
                                </span>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td style="padding:30px;padding-top:0">
                    <table width="100%" cellspacing="0" cellpadding="0" border="1" align="center" bgcolor="#FFFFFF">
                        <tr class="garisatasbawah">
                            <th align="center" style="width:10%">
                                <font style="font-size: 11pt;" color="#000000">Kode Akun</font>
                            </th>
                            <th align="center" style="width:40%">
                                <font style="font-size: 11pt;" color="#000000">Nama Akun</font>
                            </th>
                            <th align="center" style="width:25%">
                                <font style="font-size: 11pt;" color="#000000">Debit</font>
                            </th>
                            <th align="center" style="width:25%">
                                <font style="font-size: 11pt;" color="#000000">Kredit</font>
                            </th>
                        </tr>
                        @php
                            $nomor = 1;
                            $total = 0;
                            $totalAll = 0;

                            $totalKe = 0;
                            $totalKeAll = 0;
                            $kdmap = '';
                        @endphp
                        @foreach ($result as $item)
                            <tr>
                                <td align="left">
                                    @if ($item['type'] == 0)
                                        <font style="font-size: 11pt;" color="#000000">
                                            <b>{{ str_replace('0', '', $item['kdmap']) }}</b>
                                        </font>
                                    @else
                                        <font style="font-size: 11pt;" color="#000000">
                                            {{ str_replace('0', '', $item['kdmap']) }}
                                        </font>
                                    @endif
                                </td>
                                <td align="left">
                                    @if ($item['type'] == 0)
                                        <font style="font-size: 11pt;" color="#000000">
                                            <b>{{ $item['namamap'] }}</b>
                                        </font>
                                    @else
                                        <font style="font-size: 11pt;" color="#000000">
                                            {!! str_replace('-', '&nbsp;&nbsp;', $item['namamap']) !!}
                                        </font>
                                    @endif
                                </td>
                                <td align="right">
                                    <font style="font-size: 11pt;" color="#000000">
                                        {{ number_format($item['debet'], 2, '.', ',') }}
                                    </font>
                                </td>
                                <td align="right">
                                    <font style="font-size: 11pt;" color="#000000">
                                        {{ number_format($item['kredit'], 2, '.', ',') }}
                                    </font>
                                </td>
                            </tr>
                            @php
                                $total = $total + $item['debet'];
                                $totalAll = $totalAll + $item['kredit'];
                            @endphp
                        @endforeach
                        @if( $request['judul'] == 'LAPORAN OPERASIONAL / LABA - RUGI')
                        <tr class="garisatasbawah" >
                            <th align="right" colspan="2" style="height: 20px">
                                <font style="font-size: 11pt;" color="#000000"></font>
                            </th>

                            <th align="right">

                            </th>
                            <th align="right">

                            </th>
                        </tr>

                        <tr class="garisatasbawah">
                            <th colspan="2" align="left">
                                <font style="font-size: 11pt;" color="#000000"> PENDAPATAN</font>
                            </th>
                            <th align="right" colspan="2">
                                <font style="font-size: 11pt;" color="#000000">
                                    {{ number_format($totalAll, 2, '.', ',') }}</font>
                            </th>
                        </tr>
                        <tr class="garisatasbawah">
                            <th colspan="2" align="left">
                                <font style="font-size: 11pt;" color="#000000"> BEBAN</font>
                            </th>
                            <th align="right" colspan="2">
                                <font style="font-size: 11pt;" color="#000000">
                                    {{ number_format($total, 2, '.', ',') }}</font>
                            </th>
                        </tr>
                        <tr class="garisatasbawah">
                            <th colspan="2" align="left">
                                <font style="font-size: 11pt;" color="#000000">TOTAL</font>
                            </th>
                            <th align="right" colspan="2">
                                <font style="font-size: 11pt;" color="#000000">
                                    {{ number_format($totalAll - $total, 2, '.', ',') }}</font>
                            </th>
                        </tr>
                        @endif
                    </table>
                </td>
            </tr>
        </tbody>
    </table>
@endsection
