<!DOCTYPE html>
<html lang="en">

<head>
    <title>
        Label
    </title>
    @if ((isset($res['pdf']) && $res['pdf']) || (isset($res['storage']) && $res['storage']))
        <link rel="stylesheet" href="css/paper.css">
        {{-- <link rel="stylesheet" href="css/table-v2.css">
        <link rel="stylesheet" href="css/tabel.css"> --}}
    @else
        <link rel="stylesheet" href="{{ asset('css/paper.css') }} ">
        <link rel="stylesheet" href="{{ asset('css/table-v2.css') }}">
        <link rel="stylesheet" href="{{ asset('css/tabel.css') }}">
    @endif
</head>
<style>
    /* .page-break {
        page-break-after: always;
    } */

    body,
    td,
    th,
    span,
    p {
        font-family: Tahoma, Geneva, sans-serif !important;
        font-size: 11pt;
    }

    @page {
        size: auto;

    }

    @media print {
        table.receipt {
            width: 58mm
        }
    }

    /* fix for Chrome */
</style>


<body>
    @foreach($dataReport as $d)
    <div style="border-top:1.5px solid black;border-bottom:1.5px solid black;">
        <table width="100%">
            <tr>
                <td width="70%" border="1">
                    <table style="margin:0px;padding:0px" width="100%">
                        <tr>
                            <td width="13%">
                                <span>Pasien</span>
                            </td>
                            <td width="5px">
                                <span>:</span>
                            </td>
                            <td>
                                <span width="85%" style="font-weight:bold">{{$d->namapasien}}</span>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span>Obat</span>
                            </td>
                            <td>
                                <span>:</span>
                            </td>
                            <td>
                                <span style="font-weight:bold">{{$d->namaproduk}}</span>
                            </td>
                        </tr>
                    </table>
                    
                    <table style="margin:0px;padding:0px">
                        <tr>
                            <td>
                                <span>Tgl/Jam Pembuatan</span>
                            </td>
                            <td>
                                <span>:</span>
                            </td>
                            <td>
                                <span>{{$d->tglresep}}</span>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span>Tgl Kadaluarsa</span>
                            </td>
                            <td>
                                <span>:</span>
                            </td>
                            <td>
                                <span>{{$d->tglkadaluarsa ?  date('Y-m-d', strtotime($d->tglkadaluarsa)) :'-'}}</span>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span>Penyimpanan</span>
                            </td>
                            <td>
                                <span>:</span>
                            </td>
                            <td>
                                <span>-</span>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span>Batas Penggunaan Obat</span>
                            </td>
                            <td>
                                <span>:</span>
                            </td>
                            <td>
                                <span>  Hari</span>
                            </td>
                        </tr>
                    </table>
                </td>
                <td width="30%" border="1" style="vertical-align: top">
                    <table>
                        <tr>
                            <td>
                                <span>TGL Lahir</span>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span>{{$d->tgllahir}}</span>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span>Rute</span>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span>{{$d->route}}</span>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </div>
    @endforeach
</body>

</html>
