@extends('template.layout')
@section('title', 'Surat Piutang')
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
    <table class="bayangprint" cellspacing="0" cellpadding="0" bgcolor="#FFFFFF" border="0" width="100%"
        style="padding:25px">
        <tr>
            <td width="60%">
                <table>
                    <tr>
                        <td width="20%">
                            <font style="font-size: 10pt;font-weight: 400px" color="#000000">No.</font>
                        </td>
                        <td width="1%">
                            <font style="font-size: 10pt;font-weight: 400px" color="#000000">:</font>
                        </td>
                        <td width="79%">
                            <font style="font-size: 10pt;font-weight: 400px" color="#000000"></font>
                        </td>
                    </tr>
                    <tr>
                        <td width="20%">
                            <font style="font-size: 10pt;font-weight: 400px" color="#000000">Lampiran.</font>
                        </td>
                        <td width="1%">
                            <font style="font-size: 10pt;font-weight: 400px" color="#000000">:</font>
                        </td>
                        <td width="79%">
                            <font style="font-size: 10pt;font-weight: 400px" color="#000000"> 1 (Satu) Berkas</font>
                        </td>
                    </tr>
                    <tr>
                        <td width="20%">
                            <font style="font-size: 10pt;font-weight: 400px" color="#000000">Perihal.</font>
                        </td>
                        <td width="1%">
                            <font style="font-size: 10pt;font-weight: 400px" color="#000000">:</font>
                        </td>
                        <td width="79%">
                            <font style="font-size: 10pt;font-weight: 600" color="#000000">{{$dataReport['data']->keterangan}}</font>
                        </td>
                    </tr>
                </table>
            </td>
            <td width="40%">
                <table width="100%">
                    <tr>
                        <td width="100%">
                            <font style="font-size: 10pt;font-weight: 400px" color="#000000"> Kepada Yth.</font>
                        </td>
                    </tr>
                    <tr>
                        <td width="100%">
                            <font style="font-size: 10pt;font-weight: 600" color="#000000"> MANDIRI INHEALTH.</font>
                        </td>
                    </tr>
                    <tr>
                        <td width="100%">
                            <font style="font-size: 10pt;font-weight: 400px" color="#000000"> di-</font>
                        </td>
                    </tr>
                    <tr>
                        <td width="100%">
                            <font style="font-size: 10pt;font-weight: 400px" color="#000000"> tempat</font>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td colspan="2" height="25px"></td>
        </tr>
        <tr>
            <td colspan="2">
                <font style="font-size: 10pt;font-weight: 400px" color="#000000"> Dengan Hormat,</font>
            </td>
        </tr>
        <tr>
            <td colspan="2" height="2px"></td>
        </tr>
        <tr>
            <td colspan="2">
                <font style="font-size: 12pt;font-weight: 400" color="#000000"> Berdasarkan surat jaminan, bersama ini
                    kami sampaikan berkas biaya tagihan {{$dataReport['data']->keterangan}}
                    adalah senilai <br />
                    <span style="font-size: 12pt;font-weight: 600"> Rp.
                        {{ $dataReport['data']->totalppenjamin }}</span>
                </font>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <font style="font-size: 12pt;font-weight: 600" color="#000000"> # {{ $dataReport['terbilang'] }} #
                </font>
            </td>
        </tr>
        <tr>
            <td colspan="2" height="10px"></td>
        </tr>
        <tr>
            <td colspan="2">
                <font style="font-size: 12pt;font-weight: 400" color="#000000"> Atas perhatian dan kerjasama, kami ucapkan
                    terima kasih.
                </font>
            </td>
        </tr>
        <tr>
            <td colspan="2" height="30px"></td>
        </tr>
        <tr>
            <td width="60%">
                <table width="100%">
                    <tr>
                        <td>
                            <font style="font-size: 12pt;font-weight: 400" color="#000000"> Pembayaran Transfer</font>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <font style="font-size: 12pt;font-weight: 400" color="#000000"> Bank BNI Cab. Perintis
                                Kemerdekaan Bandung</font>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <font style="font-size: 12pt;font-weight: 400" color="#000000"> An. PT. GLOBAL SEKAWAN KREASI
                                <br />
                                No. Rek. 1010105222
                            </font>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" height="5px"></td>
                    </tr>
                    <tr>
                        <td>
                            <font style="font-size: 12pt;font-weight: 400" color="#000000"> Bukti Transfer Pembayaran Mohon
                                di Email ke <br />
                                keuangan@rsjpparamarta.com</font>
                        </td>
                    </tr>
                </table>
            </td>
            <td width="40%">
                <table>
                    <tr>
                        <td align="center">
                            <font style="font-size: 12pt;font-weight: 400" color="#000000"> Bandung,
                                {{ date('d-m-Y H:i:s') }}</font>
                        </td>
                    </tr>
                    <tr>
                        <td align="center">
                            <font style="font-size: 12pt;font-weight: 400" color="#000000"> Regards</font>
                        </td>
                    </tr>
                    <tr>
                        <td height="80" valign="bottom" align="center">
                            <font style="font-size: 11pt;" color="#000000">
                                <u><b>Eriza Safitri Zain,S.E</b></u> <br />
                                Kepala Bagian Keuangan & SDM
                            </font>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
@endsection
