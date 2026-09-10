@extends('template.layout')
@section('title', 'Rekap Pembayaran Piutang')
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

        .page-break {
            page-break-before: always;
        }
    </style>
@endsection
@section('content')
    <div style="display: flex;text-align: center">
        <div width="100%" style="text-align: center">
            <font style="font-size: 14pt;font-weight: bold" color="#000000">
                REKAPITULASI PEMBAYARAN PIUTANG PERUSAHAAN
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
    <table class="bayangprint" cellspacing="0" cellpadding="0" bgcolor="#FFFFFF" border="0" width="100%">
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
                                TANGGAL PEMBAYARAN
                            </font>
                        </td>
                        <td>
                            <font style="font-size: 10pt;font-weight: 600" color="#000000">
                                ADMINISTRASI
                            </font>
                        </td>
                        <td>
                            <font style="font-size: 10pt;font-weight: 600" color="#000000">
                                JUMLAH BAYAR
                            </font>
                        </td>
                    </tr>
                    @php
                        $total = 0;
                    @endphp
                    @foreach ($dataReport['data'] as $key => $item)
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
                                    {{ $item->tglbayar }}
                                </font>
                            </td>
                            <td>
                                <font style="font-size: 10pt;font-weight: 400" color="#000000">
                                    {{ $item->adm }}
                                </font>
                            </td>
                            <td>
                                <font style="font-size: 10pt;font-weight: 400" color="#000000">
                                    {{ number_format($item->totaldibayar, 2, '.', ',') }}
                                </font>
                            </td>
                        </tr>
                        @if ($loop->iteration == 6)
                            <tr class="page-break">
                                <td colspan="4">
                                </td>
                            </tr>
                        @elseif ($loop->iteration != 14 && $loop->iteration % 14 == 0)
                            <tr class="page-break">
                                <td colspan="4">
                                </td>
                            </tr>
                        @endif
                    @endforeach
                    <tr>
                        <td colspan="2">
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
