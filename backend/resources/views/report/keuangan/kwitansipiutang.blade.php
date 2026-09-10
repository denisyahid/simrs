@extends('template.layout')
@section('title', 'Cetak Kwitansi Tagihan')
@section('page-style')
@endsection
@section('content')
    <div align="center">
        <table class="bayangprint" cellspacing="0" cellpadding="0" bgcolor="#FFFFFF" border="0" width="{{ $pageWidth }}"
            style="padding:25px">
            <tbody>
                <tr>
                    <td>
                        <table width="50%" cellspacing="0" cellpadding="0">
                            <tr>
                                <td width="80%" align="center" height="80px">
                                    <font style="font-size: 16pt;font-weight: bold" color="#000000">KWITANSI</font>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td>
                        <table width="100%" cellspacing="0" cellpadding="0" border="0">
                            <tr>
                                <td width="20%">
                                    <font style="font-size: 11pt;" color="#000000">No. Kwitansi</font>
                                </td>
                                <td width="45%">
                                    <font style="font-size: 11pt;" color="#000000">:
                                        {{ $dataReport['datas'][0]->nomorsurat }}</font>
                                </td>
                                <td width="15%">
                                    <font style="font-size: 11pt;text-align: right;" color="#000000">ASLI</font>
                                </td>
                            </tr>
                            <tr>
                                <td width="20%">
                                    <font style="font-size: 11pt;" color="#000000">Jumlah Rp.</font>
                                </td>
                                <td width="80%" colspan="3">
                                    <font style="font-size: 11pt;" color="#000000">:
                                        {{ number_format($dataReport['datas'][0]->nominal, 2, '.', ',') }} </font>
                                </td>
                            </tr>
                            <tr>
                                <td width="20%">
                                    <font style="font-size: 11pt;" color="#000000">Banyaknya uang</font>
                                </td>
                                <td width="80%" colspan="3">
                                    <font style="font-size: 11pt;">:</font>
                                    <font style="font-size: 11pt;font-weight: bold;font-style:italic;" color="#000000">
                                        {{ strtoupper($dataReport['terbilang']) }}</font>
                                </td>
                            </tr>
                            <tr>
                                <td width="20%">
                                    <font style="font-size: 11pt;" color="#000000">Untuk Pembayaran</font>
                                </td>
                                <td width="80%" colspan="3">
                                    <font style="font-size: 11pt;" color="#000000">:
                                        {{ strtoupper($dataReport['datas'][0]->keterangan) }}</font>
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
                                <td width="50%" align="right">
                                    <font style="font-size: 11pt;" color="#000000">
                                        {!! $profile->namakota !!},
                                        &nbsp;&nbsp;{{ date('d/m/Y', strtotime($dataReport['datas'][0]->tanggal)) }} <br />
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
                                <td height="80" valign="bottom" height="100"width="25%" align="right">
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
    </div>
@endsection
