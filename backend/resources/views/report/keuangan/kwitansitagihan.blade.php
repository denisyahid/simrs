@extends('template.layout')
@section('title', 'Cetak Kwitansi Tagihan')
@section('page-style')
    <style>
        tr td {
            padding: 2px 4px 2px 4px;
        }

        .borderss {
            border-bottom: 1px solid black;
        }

        .baris1 {
            border: 2px solid #000000;
        }

        .baris2 {
            border: 1px solid #000000;
        }

        .garishalus {
            border: 0.01em solid #9a9a9a;
        }

        .garishalus tr td {
            border: 0.01em solid #9a9a9a;
        }

        body {
            font-family: Tahoma, Geneva, sans-serif;
        }
    </style>
@endsection
@section('content')
    <table class="bayangprint" cellspacing="0" cellpadding="0" bgcolor="#FFFFFF" border="0" width="{{ $pageWidth }}"
        style="padding:25px">
        <tbody>
            <tr>
                <td>
                    <table width="50%" cellspacing="0" cellpadding="0">
                        <tr>
                            <td width="100%" align="center" height="80px">
                                <font style="font-size: 16pt;font-weight: bold" color="#000000">KWITANSI</font>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            @php
                function weirdRounding($num)
                {
                    if ($num - floor($num) >= 0.5) {
                        return ceil($num);
                    } else {
                        $str = (string) $num;
                        $str2 = explode('.', $str);
                        $str2[1] = '00';
                        $str3 = implode('.', $str2);
                        return $str3;
                    }
                }
            @endphp
            <tr>
                <td>
                    <table width="100%" cellspacing="0" cellpadding="0" border="0">
                        <tr>
                            <td width="20%">
                                <font style="font-size: 11pt;" color="#000000">No. Kwitansi</font>
                            </td>
                            <td width="80%">
                                <font style="font-size: 11pt;font-weight: bold;" color="#000000">:
                                    {{ $dataReport['datas'][0]->noposting }}</font>
                            </td>
                        </tr>
                        <tr>
                            <td width="20%">
                                <font style="font-size: 11pt;" color="#000000">Sudah Terima Dari</font>
                            </td>
                            <td width="80%">
                                <font style="font-size: 11pt;font-weight: bold;" color="#000000">:
                                    {{ $dataReport['datas'][0]->namarekanan }}</font>
                            </td>
                        </tr>
                        <tr>
                            <td width="20%">
                                <font style="font-size: 11pt;" color="#000000">Jumlah Rp.</font>
                            </td>
                            <td width="80%" colspan="3">
                                <font style="font-size: 11pt;font-weight: bold;" color="#000000">: Rp.
                                    {{ number_format(weirdRounding($dataReport['datas'][0]->totalbiaya), 2, '.', ',') }}
                                </font>
                            </td>
                        </tr>
                        <tr>
                            <td width="20%">
                                <font style="font-size: 11pt;" color="#000000">Banyaknya uang</font>
                            </td>
                            <td width="80%" colspan="3">
                                <font style="font-size: 11pt;font-weight: bold;">: </font>
                                <font style="font-size: 11pt;font-weight: bold;font-style:italic;" color="#000000">
                                    {{ strtoupper($dataReport['terbilang']) }}</font>
                            </td>
                        </tr>
                        <tr>
                            <td width="20%">
                                <font style="font-size: 11pt;" color="#000000">Untuk Pembayaran</font>
                            </td>
                            <td width="80%" colspan="3">
                                <font style="font-size: 11pt;font-weight: bold;" color="#000000">:
                                    {{ $dataReport['datas'][0]->keterangan }}</font>
                            </td>
                        </tr>
                        <tr>
                            <td width="20%">
                                <font style="font-size: 11pt;" color="#000000">Periode tanggal</font>
                            </td>
                            <td width="80%" colspan="3">
                                <font style="font-size: 11pt;font-weight: bold;" color="#000000">:
                                    {{ $dataReport['periode'] }}</font>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td style="padding-top:20px">
                    <table width="100%" cellspacing="0" cellpadding="0">
                        <tr>
                            <td width="50%" align="right">
                                <font style="font-size: 11pt;" color="#000000"; align="right"></font>
                            </td>
                            <td width="50%" align="center">
                                <font style="font-size: 11pt;" color="#000000">
                                    {!! $profile->namakota !!}, &nbsp;&nbsp;{{ $dataReport['tglsekarang'] }} <br />
                                    Regards
                                </font>
                            </td>
                        </tr>
                        <tr>
                            <td width="50%" align="right"></td>
                            <td width="50%" align="right"></td>
                        </tr>
                        <tr>
                            <td height="80" valign="bottom" height="100" width="25%" align="right">
                            </td>
                            <td height="80" valign="bottom" height="100"width="25%" align="center">
                                <font style="font-size: 11pt;" color="#000000">
                                    <u><b>Eriza Safitri Zain,S.E</b></u> <br />
                                    Kepala Bagian Keuangan & SDM
                                </font>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="4" style="padding-bottom: 20px;">
                                <font style="font-size: 10pt;" color="#000000">
                                    Print by : {{ $dataReport['user'] }} &nbsp;&nbsp;&nbsp;
                                    Print date :{{ date('d/m/Y H:i') }}
                                </font>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>
@endsection
