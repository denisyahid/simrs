<!DOCTYPE html>
<html lang="en">

<head>
    <title>
        Label Obat
    </title>
    @if ((isset($res['pdf']) && $res['pdf']) || (isset($res['storage']) && $res['storage']))
    <link rel="stylesheet" href="css/paper.css">
    <link rel="stylesheet" href="css/table-v2.css">
    <link rel="stylesheet" href="css/tabel.css">
    @else
    <link rel="stylesheet" href="{{ asset('css/paper.css') }} ">
    <link rel="stylesheet" href="{{ asset('css/table-v2.css') }}">
    <link rel="stylesheet" href="{{ asset('css/tabel.css') }}">
    @endif
</head>
<style>
    body,
    td,
    th,
    span,
    p {
        font-family: Tahoma, Geneva, sans-serif !important;
        font-size: 9pt;
    }

    @page {
        size: auto;

    }

    @media print {
        table.receipt {
            width: 45mm
        }
    }

    /* fix for Chrome */
</style>


<body>
    <div style="margin-top:.6cm">
        @foreach($dataReport as $d)
        <table style="background-color:#FFFFFF; border-bottom:1px solid black; margin-left: 10px; margin-right: 10px;" width="{{ $pageWidth }}">
            <tr>
                <td width="70%" border="1">
                    <table style="margin: 0px; padding: 0px;" width="100%">
                        <tr>
                            <td width="13%" style="line-height: 1;">
                                <span style="font-size: 12px;">Pasien</span>
                            </td>
                            <td width="5px" style="line-height: 1;">
                                <span style="font-size: 12px;">:</span>
                            </td>
                            <td style="line-height: 1;">
                                <span width="85%" style="font-weight: bold; font-size: 12px;">{{ $d->namapasien }}</span>
                            </td>
                        </tr>
                        <tr>
                            <td style="line-height: 1;">
                                <span style="font-size: 12px;">Obat</span>
                            </td>
                            <td style="line-height: 1;">
                                <span style="font-size: 12px;">:</span>
                            </td>
                            <td style="line-height: 1;">
                                <span style="font-weight: bold; font-size: 12px;">{{ $d->namaproduk }}</span>
                            </td>
                        </tr>
                    </table>


                    <table style="margin:0px;padding:0px">
                        <tr>
                            <td>
                                <span style="font-size: 12px;">Tgl Pembuatan</span>
                            </td>
                            <td>
                                <span style="font-size: 12px;">:</span>
                            </td>
                            <td>
                                <span style="font-size: 12px;">{{ date('Y-m-d', strtotime($d->tglresep)) }}</span>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span style="font-size: 12px;">Tgl Kadaluarsa</span>
                            </td>
                            <td>
                                <span style="font-size: 12px;">:</span>
                            </td>
                            <td>
                                <span style="font-size: 12px;">{{ $d->tglkadaluarsa ? date('Y-m-d', strtotime($d->tglkadaluarsa)) : '-' }}</span>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span style="font-size: 12px;">Penyimpanan</span>
                            </td>
                            <td>
                                <span style="font-size: 12px;">:</span>
                            </td>
                            <td>
                                <span style="font-size: 12px;">-</span>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span style="font-size: 12px;">Batas Penggunaan Obat</span>
                            </td>
                            <td>
                                <span style="font-size: 12px;">:</span>
                            </td>
                            <td style="padding-left: .5cm">
                                <span style="font-size: 12px;"> Hari</span>
                            </td>
                        </tr>
                    </table>
                </td>
                <td width="30%" border="1" style="vertical-align: top">
                    <table>
                        <tr>
                            <td>
                                <span style="font-size: 12px;">TGL Lahir</span>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span style="font-size: 12px;">{{ $d->tgllahir }}</span>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span style="font-size: 12px;">Rute</span>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span style="font-size: 12px;">{{ $d->route }}</span>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
        @endforeach
    </div>
    {{-- <div class="page-break" style="margin-top:20px"></div> --}}

</body>

</html>