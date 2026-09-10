<!DOCTYPE html>
<html lang="en">

<head>
    <title>
        Report
    </title>
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
</head>
<style type="text/css" media="print">
    @media print {
        @page {
            size: auto;
            margin: 0;
            /* size: portrait; */
        }

        footer {
            display: none
        }

        header {
            display: none
        }

        body {
            -webkit-print-color-adjust: exact !important;
        }
    }

    tr td {
        /*padding:2px 4px 2px 4px;*/
    }

    .borderss {
        border-bottom: 1px solid black;
    }

    body {
        font-family: Tahoma, Geneva, sans-serif;
    }
</style>

<body style="margin: 0">
@if(isset($israd) && $israd == true)
    @php $loopCount = 1; @endphp
@else
    @php $loopCount = 10; @endphp
@endif

    @for($i = 0; $i < $loopCount; $i++)
        <table cellspacing="0" cellpadding="0" bgcolor="#FFFFFF" border="0" width="350">
            <tbody>
                <tr>
                    <td style="padding: 5px;text-align: left">
                        <table ccellspacing="0" cellpadding="0" border="0" width="100%" align="center">
                            <tr>
                                <td height="5" width="30%">
                                    <font style="font-size: 10pt;" color="#000000" face="Tahoma">Tanggal</font>
                                </td>
                                <td height="5" width="2%">
                                    <font size="1">:</font>
                                </td>
                                <td height="5" width="68%">
                                    <font style="font-size: 10pt;" color="#000000" face="Tahoma">
                                        {{ $datas[0]->tglregistrasi }}</font>
                                </td>
                            </tr>
                            <tr>
                                <td height="5" width="30%">
                                    <font style="font-size: 10pt;" color="#000000" face="Tahoma">NRM / Nama</font>
                                </td>
                                <td height="5" width="2%">
                                    <font size="1">:</font>
                                </td>
                                <td height="5" width="68%">
                                    <font style="font-size: 10pt;" color="#000000" face="Tahoma">
                                        {{ $datas[0]->nocm }} / {{ $datas[0]->namapasien }}</font>
                                </td>
                            </tr>
                            <tr>
                                <td height="5" width="30%">
                                    <font style="font-size: 10pt;" color="#000000" face="Tahoma">Umur</font>
                                </td>
                                <td height="5" width="2%">
                                    <font size="1">:</font>
                                </td>
                                <td height="5" width="68%">
                                    <font style="font-size: 10pt;" color="#000000" face="Tahoma">
                                        {{ $datas[0]->umur }}</font>
                                </td>
                            </tr>
                            <tr>
                                <td height="5" width="30%">
                                    <font style="font-size: 10pt;" color="#000000" face="Tahoma">Dokter Asal</font>
                                </td>
                                <td height="5" width="2%">
                                    <font size="1">:</font>
                                </td>
                                <td height="5">
                                    <font style="font-size: 10pt;" color="#000000" face="Tahoma">
                                        {{ $datas[0]->dokterorder }}</font>
                                </td>
                            </tr>
                            <tr>
                                <td height="5" width="30%">
                                    <font style="font-size: 10pt;" color="#000000" face="Tahoma">Asal Pasien</font>
                                </td>
                                <td height="5" width="2%">
                                    <font size="1">:</font>
                                </td>
                                <td height="5" width="68%">
                                    <font style="font-size: 10pt;" color="#000000" face="Tahoma">
                                        {{ $datas[0]->ruanganasal }}</font>
                                </td>
                            </tr>
                            <tr>
                                <td height="5" width="30%">
                                    <font style="font-size: 10pt;" color="#000000" face="Tahoma">Tipe Pasien</font>
                                </td>
                                <td height="5" width="2%">
                                    <font size="1">:</font>
                                </td>
                                <td height="5" width="68%">
                                    <font style="font-size: 10pt;" color="#000000" face="Tahoma">
                                        {{ $datas[0]->kelompokpasien }} / {{ $datas[0]->kebangsaan }}</font>
                                </td>
                            </tr>
                            <tr>
                                <td height="5" width="30%">
                                    <font style="font-size: 10pt;" color="#000000" face="Tahoma">Obyek</font>
                                </td>
                                <td height="5" width="2%">
                                    <font size="1">:</font>
                                </td>
                                <td height="5" width="68%">
                                    <font style="font-size: 10pt;" color="#000000" face="Tahoma">
                                        {{ $datas[0]->modality }}</font>
                                </td>
                            </tr>
                            @php
                                $norm = $datas[0]->nocm;
                            @endphp
                            <!-- <tr>
                                <td height="5" width="30%">
                                    <font style="font-size: 8pt;" color="#000000" face="Tahoma">Barcode</font>
                                </td>
                                <td height="5" width="2%">
                                    <font size="1">:</font>
                                </td>
                                <td height="5" width="68%">
                                    <img src='https://barcode.tec-it.com/barcode.ashx?data={{$norm}}&code=Code39&dpi=96&dataseparator='
                                    style=" height: 30px;width: 120px;-webkit-user-select: none;cursor:pointer"/>
                                </td>
                            </tr> -->
                        </table>
                    </td>
                </tr>
            </tbody>
        </table>
    @endfor
</body>
<script>
window.onload = function() { window.print(); }
    </script>
</html>
