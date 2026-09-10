<!DOCTYPE html>
<html>
@php
    $profile = App\Http\Controllers\Controller::static_profile();
@endphp
<head>
    <title>@yield('title')</title>
    <meta name="robots" content="noindex">
    <meta name="googlebot" content="noindex">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    @if ((isset($res['pdf']) && $res['pdf']) || (isset($res['storage']) && $res['storage']))
    @else
        {{-- <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link
            href="https://fonts.googleapis.com/css2?family=Montserrat:ital@0;1&family=Mukta&family=Poppins:wght@400&display=swap"
            rel="stylesheet"> --}}
    @endif

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>

    <style>
        /* @font-face {
            font-family:'Poppins', sans-serif;
            src: url('fonts/Poppins-Bold.ttf');
        } */
        :root {
            /* font-family: 'Montserrat', sans-serif;
            font-family: 'Poppins', sans-serif; */
            /* --font:Tahoma,Verdana,Segoe,sans-serif;  */
            --font: Arial, Helvetica, sans-serif;
        }
    </style>

    @if ((isset($res['pdf']) && $res['pdf']) || (isset($res['storage']) && $res['storage']))
        <link rel="stylesheet" href="css/paper.css ">
        <link rel="stylesheet" href="css/table-v2.css">
        {{-- <link rel="stylesheet" href="css/tabel.css"> --}}
        <link rel="stylesheet" href="css/style.css">
        <style>
            body,
            td,
            th,
            span,
            p {
                font-family: Arial, Helvetica, sans-serif !important;
            }
        </style>
    @else
        {{-- @if (stripos(\Request::url(), 'localhost') !== false || stripos(\Request::url(), '127.0.0.1') !== false) --}}
        <link rel="stylesheet" href="{{ asset('css/paper.css') }} ">
        <link rel="stylesheet" href="{{ asset('css/table-v2.css') }}">
        {{-- <link rel="stylesheet" href="{{ asset('css/tabel.css') }}"> --}}
        <link rel="stylesheet" href="{{ asset('css/style.css') }}">
        {{-- @else
            <link rel="stylesheet" href="{{ asset('service/css/paper.css') }} ">
            <link rel="stylesheet" href="{{ asset('service/css/table-v2.css') }}">
            <link rel="stylesheet" href="{{ asset('service/css/tabel.css') }}">
            <link rel="stylesheet" href="{{ asset('service/css/style.css') }}">
        @endif --}}
    @endif


</head>
<style type="text/css" media="print">
    @media print {
        @page {
            size: 215 mm 140 mm landscape;
            margin: 0;
            /* size: portrait; */
        }

        footer {
            display: none
        }

        header {
            display: none
        }


    }
</style>
<style>
    body,
    td,
    th,
    span,
    p {
        font-family:   Tahoma, Geneva, sans-serif; !important;
        font-size: 11px;
    }

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
        /* border: thin solid #9a9a9a; */
    }

    .text{
        font-size: 9pt;
    }

    .text-biasa{
        font-size: 9pt;
    }

    .text-normal{
        font-size: 9pt;
    }



    .garis6 td {
        padding: 3px !important;
    }
    
</style>

@if ((isset($res['pdf']) && $res['pdf']) || (isset($res['storage']) && $res['storage']))

    <body style="margin: 0">
    @else
        @if (isset($print) && $print == true)

            <body style="background-color: #CCCCCC;margin: 0" onLoad="window.print()">
            @else

                <body style="background-color: #CCCCCC;margin: 0">
        @endif
@endif

<body>
    @yield('page-style')
    <div align="center">
    <table width="100%" cellspacing="0" cellpadding="0" bgcolor="#FFFFFF" border="0" style="padding:25px">
        <tbody>
            <tr>
                <td>
                    <table width="100%" cellspacing="0" cellpadding="0">
                        <tr>
                            <td align="center">
                                <span class="text-judul" style="font-size: 11pt;">KWITANSI</span>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td>
                    <table width="100%" cellspacing="0" cellpadding="0">
                        <tr>
                            <td>
                                <table width="100%" cellspacing="0" cellpadding="0">
                                    <tr>
                                        <td width="100%">
                                            <span style="font-size: 9pt;"><i>No.</i>&emsp;&emsp;{{ $data[0]->nostruk }}</span>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <table width="100%" cellspacing="0" cellpadding="0" style="margin-top: -10px;">
                                    <tr>
                                        <td width="20%">
                                            <span style="font-size: 9pt;"><i>Telah terima dari</i></span>
                                        </td>
                                        <td width="2%">
                                            <span style="font-size: 9pt;">:</span>
                                        </td>
                                        <td width="78%">
                                            <p style="width: 100%; display: table;">
                                                <span style="display: table-cell; border-bottom: 1px solid black;"><i>RSUD BALI MANDARA</i></span>
                                            </p>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <table width="100%" cellspacing="0" cellpadding="0" style="margin-top: -15px;">
                                    <tr>
                                        <td width="20%">
                                            <span style="font-size: 9pt;"><i>Uang sejumlah</i></span>
                                        </td>
                                        <td width="2%">
                                            <span style="font-size: 9pt;">:</span>
                                        </td>
                                        <td width="78%">
                                            <p style="width: 100%; display: table;">
                                                <span style="display: table-cell; border-bottom: 1px solid black;"><i>{{ ucwords(App\Traits\Valet::static_terbilang($data[0]->totaldibayar)) }}</i></span>
                                            </p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td width="20%">
                                            <span style="font-size: 9pt;"></span>
                                        </td>
                                        <td width="2%">
                                            <span style="font-size: 9pt;"></span>
                                        </td>
                                        <td width="78%">
                                            <p style="width: 100%; display: table;">
                                                <span style="display: table-cell; border-bottom: 1px solid black;"></span>
                                            </p>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <table width="100%" cellspacing="0" cellpadding="0" style="margin-top: -15px;">
                                    <tr>
                                        <td width="20%">
                                            <span style="font-size: 9pt;"><i>Untuk pembayaran</i></span>
                                        </td>
                                        <td width="2%">
                                            <span style="font-size: 9pt;">:</span>
                                        </td>
                                        <td width="78%">
                                            <p style="width: 100%; display: table;">
                                                <span style="display: table-cell; border-bottom: 1px solid black;"><i>{{$data[0]->namapasien}} ({{$data[0]->nocm}})</i></span>
                                            </p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="3">
                                            <p style="width: 100%; display: table;">
                                                <span style="display: table-cell; border-bottom: 1px solid black;"></span>
                                            </p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="3">
                                            <p style="width: 100%; display: table;">
                                                <span style="display: table-cell; border-bottom: 1px solid black;"></span>
                                            </p>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td>
                    <table width="100%" cellspacing="0" cellpadding="0">
                        <tr>
                            <td>
                                <table width="100%" cellspacing="0" cellpadding="0">
                                    <tr>
                                        <td width="50%" class="text-center">
                                            <span class="text-biasa" class="text-center"></span>
                                        </td>
                                        <td width="50%" class="text-center">
                                            <p style="width: 100%; display: table;">
                                                <span style="display: table-cell; border-bottom: 1px solid black;"></span>
                                            </p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td width="50%" class="text-center"><span class="text-biasa"></span>
                                        </td>
                                        <td width="50%" class="text-center"><span class="text-biasa"></span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td height="65" valign="bottom" height="100" width="25%" height="25px" class="text-center">
                                            <p style="width: 80%; display: table;">
                                                <span style="display: table-cell; border-bottom: 1px solid black; border-top: 1px solid black;"><br>Terbilang Rp. {{number_format($data[0]->totaldibayar, 2, '.', ',')}}<br><br></span>
                                            </p>
                                        </td>
                                        <td height="80" valign="bottom" height="100"width="25%" class="text-center">
                                            <p style="width: 100%; display: table;">
                                                <span style="display: table-cell; border-bottom: 1px solid black;"></span>
                                            </p>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </tbody>
        </table>
    </div>
</body>
<script type="text/javascript">
        window.onload = function() {
            window.print();
        }
     </script>

</html>