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

    .class-2 {
        border-top:1px solid black;border-bottom:1px solid black;
    }
    .text-biasa{
        font-size: 10pt;
        font-weight: 500;
    }

    .garis6 td {
        padding: 3px !important;
    }

    .teks{
        font-size: 12px; 
        font-weight: normal;
        padding-top: -20px;
        padding-bottom: -20px;
    }

    .tabels{
        border-spacing: 0px;
            table-layout: fixed;
            margin-left: auto;
            margin-right: auto;
    }

    .tabels tr td{
        /* overflow: hidden;
  text-overflow: ellipsis;
  white-space: nowrap; */

  /* overflow-wrap:break-word */

  word-wrap: break-word;
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
        @if ((isset($res['pdf']) && $res['pdf']) || (isset($res['storage']) && $res['storage']))
            <table cellspacing="0" cellpadding="0" bgcolor="#FFFFFF" border="0" style="padding:25px">
            @else
                <table cellspacing="0" cellpadding="0" bgcolor="#FFFFFF" border="0" style="padding:25px"
                    >
        @endif
        <tbody>
            <tr>
                <td>
                    <table width="100%" cellspacing="0" cellpadding="0" border="1" class="tabels">
                        <tr style="background-color: #f74fa3;">
                            <td align="center"><span><h5 class="teks">NO</h5></span></td>
                            <td align="center"><span><h5 class="teks">TGL TERJADWAL</h5></span></td>
                            <td align="center"><span><h5 class="teks">NO RM</h5></span></td>
                            <td align="center"><span><h5 class="teks">NAMA PASIEN</h5></span></td>
                            <td align="center"><span><h5 class="teks">JENIS KELAMIN</h5></span></td>
                            <td align="center"><span><h5 class="teks">TGL LAHIR</h5></span></td>
                            <td align="center"><span><h5 class="teks">DIAGNOSA</h5></span></td>
                            <td align="center"><span><h5 class="teks">DIAGNOSTIK / TERAPI</h5></span></td>
                            <td align="center"><span><h5 class="teks">JENIS PEMERIKSAAN</h5></span></td>
                            <td align="center"><span><h5 class="teks">JAM PEMERIKSAAN</h5></span></td>
                            <td align="center"><span><h5 class="teks">JENIS RADIONUKLIDA</h5></span></td>
                            <td align="center"><span><h5 class="teks">JENIS RADIOFARMAKA</h5></span></td>
                            <td align="center"><span><h5 class="teks">DOSIS</h5></span></td>
                            <td align="center"><span><h5 class="teks">RUTE PEMBERIAN</h5></span></td>
                            <td align="center"><span><h5 class="teks">BB</h5></span></td>
                            <td align="center"><span><h5 class="teks">TB</h5></span></td>
                            <td align="center"><span><h5 class="teks">DPJP</h5></span></td>
                            <td align="center"><span><h5 class="teks">KETERANGAN</h5></span></td>
                        </tr>
                        @php
                            $no = 0;
                        @endphp
                        @foreach ($dataReport['details'] as $item)
                        @php
                            $no++;
                        @endphp
                        <tr>
                            <td align="center"><span><h5 class="teks">{{ $no }}</h5></span></td>
                            <td align="center"><span><h5 class="teks">{{  $item->tglregistrasi }}</h5></span></td>
                            <td align="center"><span><h5 class="teks">{{  $item->nocm }}</h5></span></td>
                            <td align="center"><span><h5 class="teks">{{  $item->namapasien }}</h5></span></td>
                            <td align="center"><span><h5 class="teks">{{  $item->jeniskelamin }}</h5></span></td>
                            <td align="center"><span><h5 class="teks">{{  $item->tgllahir }}</h5></span></td>
                            <td align="center"><span><h5 class="teks"></h5></span></td>
                            <td align="center"><span><h5 class="teks">{{  $item->ruangantujuan }}</h5></span></td>
                            @if($item->terapiradioaktif == null)
                            <td align="center"><span><h5 class="teks">{{  $item->tindakan }}</h5></span></td>
                            @else
                            <td align="center"><span><h5 class="teks">{{  $item->terapiradioaktif }}</h5></span></td>
                            @endif
                            <td align="center"><span><h5 class="teks">{{  date('H:i', strtotime($item->jampermintaan)) }}</h5></span></td>
                            <td align="center"><span><h5 class="teks">{{  $item->radionuklida }}</h5></span></td>
                            @if($item->catatanfarmaka != null)
                            <td align="center"><span><h5 class="teks">{{  $item->catatanfarmaka }}</h5></span></td>
                            @else
                            <td align="center"><span><h5 class="teks">{{  $item->farmaka }}</h5></span></td>
                            @endif
                            @if($item->terapiiodium != null)
                            <td align="center"><span><h5 class="teks">{{  $item->terapiiodium }}</h5></span></td>
                            @else
                            <td align="center"><span><h5 class="teks">{{  $item->terapiradiofarmaka }}</h5></span></td>
                            @endif
                            <td align="center"><span><h5 class="teks">{{  $item->rutelokasisuntik }}</h5></span></td>
                            <td align="center"><span><h5 class="teks">{{  $item->bb }}</h5></span></td>
                            <td align="center"><span><h5 class="teks">{{  $item->tb }}</h5></span></td>
                            <td align="center"><span><h5 class="teks">{{  $item->dpjp }}</h5></span></td>
                            <td align="center"><span><h5 class="teks">{{  $item->keterangan }}</h5></span></td>
                        </tr>
                        @endforeach
                    </table>
                </td>
            </tr>
        </tbody>
        </table>
    </div>
</body>
</html>