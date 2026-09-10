@extends('template.layout')
@section('title', 'Cetak Kartu Piutang Perusahaan')
@section('page-style')
    <style>
        .border {
            border: 1px solid #202020;
        }

        .borderr tr th {
            border-bottom: 1px solid #202020;
        }

        .borderr tr td {
            border-bottom: 1px solid #202020;
            vertical-align: middle;
        }

        .page-break-before {
            page-break-before: always;
            display: none !important;
        }

        .page-break-after {
            page-break-after: always;
            display: none !important;
        }

        .border-top {
            border-top: 1px solid #202020;
        }

        .break {
            page-break-after: always;
            border-bottom: 1px solid #202020;

        }
    </style>

@endsection
@section('content')
    <table class="bayangprint" width="100%" cellspacing="0" cellpadding="0" bgcolor="#FFFFFF" border="0"
        style="padding:25 1px">
        <tbody>
            <tr>
                <td width="100%">
                    <table width="100%" cellspacing="0" cellpadding="0">
                        <tr>
                            <td align="center" height="20px">
                                <font style="font-size: 16pt;font-weight: bold" color="#000000">KARTU PIUTANG PERUSAHAAN
                                </font>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td width="100%">
                    <table width="100%" cellspacing="0" cellpadding="0" class="border">
                        <tr>
                            <td width="40%">
                                <font style="font-size: 10pt;text-align: left;" color="#000000">KODE</font>
                            </td>
                            <td width="1%">
                                <font style="font-size: 10pt;text-align: left;" color="#000000">:</font>
                            </td>
                            <td width="59%">
                                <font style="font-size: 10pt;text-align: left;" color="#000000">
                                    {{ $dataReport['data'][0]->idrekanan ?? '' }}</font>
                            </td>
                        </tr>
                        <tr>
                            <td width="40%">
                                <font style="font-size: 10pt;text-align: right;" color="#000000">NAMA</font>
                            </td>
                            <td width="1%">
                                <font style="font-size: 10pt;text-align: right;" color="#000000">:</font>
                            </td>
                            <td width="59%">
                                <font style="font-size: 10pt;text-align: right;" color="#000000">
                                    {{ $dataReport['profile']->namaexternal }}</font>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td width="100%" height="10px">
                </td>
            </tr>
            <tr>
                <td>
                    <table width="100%" cellspacing="0" cellpadding="0" class="border" style="padding: 25px 1px">
                        <tbody>
                            <tr>
                                <th>
                                    <font style="font-size: 10pt;text-align: center;" color="#000000">No</font>
                                </th>
                                <th>
                                    <font style="font-size: 10pt;text-align: center;" color="#000000">No Reg
                                    </font>
                                </th>
                                <th>
                                    <font style="font-size: 10pt;text-align: center;" color="#000000">Keluar
                                    </font>
                                </th>
                                <th>
                                    <font style="font-size: 10pt;text-align: center;" color="#000000">Keterangan
                                    </font>
                                </th>
                                <th>
                                    <font style="font-size: 10pt;text-align: center;" color="#000000">Piutang
                                    </font>
                                </th>
                                <th>
                                    <font style="font-size: 10pt;text-align: center;" color="#000000">Bayar
                                    </font>
                                </th>
                                <th>
                                    <font style="font-size: 9pt;text-align: center;" color="#000000">Admin
                                    </font>
                                </th>
                                <th>
                                    <font style="font-size: 9pt;text-align: center;" color="#000000">Saldo
                                    </font>
                                </th>
                            </tr>
                            @php
                                $page = 1;
                            @endphp
                            @foreach ($dataReport['data'] as $key => $data)
                                @if ($page != 1)
                                    <tr height="10px">
                                        <td colspan="8"></td>
                                    </tr>
                                @endif
                                <tr
                                    class="{{ ($page == 2 && $key > 0 && $key % 12 == 0) || ($page > 2 && $key > 0 && $key % 20 == 0) ? 'border-top' : '' }}">
                                    <td>
                                        <font style="font-size: 10pt;text-align: center; padding :40px 220x";margin: 2px 9px
                                            color="#000000">
                                            {{ $loop->iteration }}
                                        </font>
                                    </td>
                                    <td>
                                        <font style="font-size: 10pt;text-align: center; padding :10px 820x";margin: 2px 9px
                                            color="#000000">
                                            {{ $data->noposting }}
                                        </font>
                                    </td>
                                    <td>
                                        <font
                                            style="font-size: 10pt;text-align: center;  padding :10px 20px;margin: 2px 9px"
                                            color="#000000">
                                            {{ $data->tanggal }}
                                        </font>
                                    </td>
                                    <td>
                                        <font
                                            style="font-size: 10pt;text-align: center;  padding :10px 20px;margin: 2px 9px"
                                            color="#000000">
                                            {{ $data->keterangan }}
                                        </font>
                                    </td>
                                    <td>
                                        <font
                                            style="font-size: 10pt;text-align: center;  padding :10px 20px;margin: 2px 9px"
                                            color="#000000">
                                            {{ number_format($data->totalpenjamin, 0, '.', ',') }}
                                        </font>
                                    </td>
                                    <td>
                                        <font
                                            style="font-size: 10pt;text-align: center;  padding :10px 20px;margin: 2px 9px"
                                            color="#000000">
                                            {{ number_format($data->sumtotalsudahdibayar, 0, '.', ',') }}
                                        </font>
                                    </td>
                                    <td>
                                        <font
                                            style="font-size: 10pt;text-align: center;  padding :10px 20px;margin: 2px 9px"
                                            color="#000000">
                                            {{ number_format(0, 0, '.', ',') }}
                                        </font>
                                    </td>
                                    <td>
                                        <font
                                            style="font-size: 10pt;text-align: center;  padding :10px 20px;margin: 2px 9px"
                                            color="#000000">
                                            {{ number_format($data->totalpenjamin - $data->sumtotalsudahdibayar, 0, '.', ',') }}
                                        </font>
                                    </td>
                                </tr>
                                @if ($page == 1 ? $key > 0 && $key % 11 == 0 : $key > 0 && $key % 19 == 0)
                                    @php
                                        $page += 1;
                                    @endphp
                                    <tr class="break">
                                        <td colspan="8" height="20"></td>
                                    </tr>
                                @endif
                            @endforeach
                            <tr class="border-top">
                                <th colspan="2">
                                    <font style="font-size: 9pt;text-align: center;" color="#000000">Total
                                    </font>
                                </th>
                                <th colspan="6" style="text-align: right;margin-right: 20px">
                                    <font style="font-size: 9pt;margin-right: 50px;" color="#000000">
                                        {{ number_format($dataReport['total'], 0, '.', ',')  }} </font>
                                </th>
                            </tr>
                            <tr class="border-top">
                                <th colspan="2">
                                    <font style="font-size: 9pt;text-align: center;" color="#000000">Terbilang
                                    </font>
                                </th>
                                <th colspan="6">
                                    <font style="font-size: 9pt;text-align: center;" color="#000000">
                                        # {{ $dataReport['terbilang'] }} #</font>
                                </th>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>

@endsection
