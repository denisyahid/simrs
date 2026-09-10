@extends('template.layout')
@section('title', 'Laporan Pembayaran Piutang')
@section('page-style')
    <style>
        .border tr td {
            border-top: 1px solid #525252;
            padding: 10px;
            margin: 10px;
        }

        .border tr th {
            border-top: 1px solid #525252;
            padding: 10px;
            margin: 10px;
        }

        .border {
            border-top: 1px solid #525252;
            display: flex display: flex;
            align-items: center;
            padding: 0 10px;
        }

        .page-break {
            page-break-before: always;
        }
    </style>
@endsection
@section('content')
    <div style="display: flex;text-align: center">
        <div width="100%" style="text-align: center">
            <font style="font-size: 14pt;font-weight: bold" color="#000000">
                LAPORAN PEMBAYARAN PIUTANG PERUSAHAAN
            </font>
        </div>
    </div>
    <div style="display: flex;text-align: center">
        <div width="100%" style="text-align: center">
            <font style="font-size: 12pt;font-weight: 400" color="#000000">
                Periode : {{ $dataReport['startDate'] }} 00:00:00 s/d {{ $dataReport['endDate'] }} 23:59:59
            </font>
        </div>
    </div>
    <table class="bayangprint" cellspacing="0" cellpadding="0" bgcolor="#FFFFFF" border="0">
        <tr>
            <td width="100%">
                <table width="100%" class="border" cellspacing="0" cellpadding="0">
                    <tr>
                        <td>
                            <font style="font-size: 10pt;font-weight: 600" color="#000000">
                                No
                            </font>
                        </td>
                        <td>
                            <font style="font-size: 10pt;font-weight: 600" color="#000000">
                                No Reg
                            </font>
                        </td>
                        <td>
                            <font style="font-size: 10pt;font-weight: 600" color="#000000">
                                Tgl Bayar
                            </font>
                        </td>
                        <td>
                            <font style="font-size: 10pt;font-weight: 600" color="#000000">
                                Keterangan
                            </font>
                        </td>
                        <td>
                            <font style="font-size: 10pt;font-weight: 600" color="#000000">
                                Nama Perusahaan
                            </font>
                        </td>
                        <td>
                            <font style="font-size: 10pt;font-weight: 600" color="#000000">
                                Adm
                            </font>
                        </td>
                        <td>
                            <font style="font-size: 10pt;font-weight: 400" color="#000000">
                                Subtotal
                            </font>
                        </td>
                    </tr>
                    @php
                        $total = 0;
                    @endphp
                    @foreach ($dataReport['data'] as $key => $collector)
                        <tr>
                            <td colspan="7">
                                <font style="font-size: 9pt;font-weight: 600" color="#000000">
                                    {{ $key }}
                                </font>
                            </td>
                        </tr>
                        @foreach ($collector as $item)
                            @php
                                $total += $item->totaldibayar;
                            @endphp
                            <tr>
                                <td>
                                    <font style="font-size: 10pt;font-weight: 400" color="#000000">
                                        {{ $loop->iteration }}
                                    </font>
                                </td>
                                <td>
                                    <font style="font-size: 10pt;font-weight: 400" color="#000000">
                                        {{ $item->noposting }}
                                    </font>
                                </td>
                                <td>
                                    <font style="font-size: 10pt;font-weight: 400" color="#000000">
                                        {{ $item->tglsbm }}
                                    </font>
                                </td>
                                <td>
                                    <font style="font-size: 10pt;font-weight: 400" color="#000000">
                                        {{ $item->keteranganlainnya }}
                                    </font>
                                </td>
                                <td>
                                    <font style="font-size: 10pt;font-weight: 400" color="#000000">
                                        {{ $item->namarekanan }}
                                    </font>
                                </td>
                                <th>
                                    <font style="font-size: 10pt;font-weight: 400" color="#000000">
                                        {{ number_format(0, 2, '.', ',') }}
                                    </font>
                                </th>
                                <td>
                                    <font style="font-size: 10pt;font-weight: 400" color="#000000">
                                        {{ number_format($item->totaldibayar, 2, '.', ',') }}
                                    </font>
                                </td>
                            </tr>
                            @if ($loop->iteration == 6)
                                <tr class="page-break">
                                    <td colspan="7">
                                    </td>
                                </tr>
                            @elseif ($loop->iteration != 12 && $loop->iteration % 12 == 0)
                                <tr class="page-break">
                                    <td colspan="7">
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                    @endforeach
                    <tr>
                        <td colspan="5">
                        </td>
                        <td>
                            <font style="font-size: 10pt;font-weight: 400" color="#000000">
                                Total
                            </font>
                        </td>
                        <td>
                            <font style="font-size: 10pt;font-weight: 400" color="#000000">
                                {{ number_format($total, 2, '.', ',') }}
                            </font>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td width="100%">
                <table width="100%">
                    <tr>
                        <td width="50%" align="right">
                            <font style="font-size: 11pt;" color="#000000">
                                {!! $profile->namakota !!},
                                &nbsp;&nbsp;{{ date('d/m/Y', strtotime(date('Y-m-d'))) }}
                                <br />
                                Regards
                            </font>
                        </td>
                    </tr>
                    <tr>
                        <td height="20" valign="bottom" height="100"width="25%" align="right">
                            <font style="font-size: 11pt;" color="#000000">
                                <u><b>Mulia Hayati, SE </b></u> <br />
                                Mulia Hayati, SE
                            </font>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
@endsection
