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
        font-size: 14px; 
        font-weight: normal;
        padding-top: -20px;
        padding-bottom: -20px;
    }

    table {
    line-height: 0px;
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
@foreach ($dataReport['details'] as $item)
<body>
    @yield('page-style')
    <div align="center">
        @if ((isset($res['pdf']) && $res['pdf']) || (isset($res['storage']) && $res['storage']))
            <table cellspacing="0" cellpadding="0" bgcolor="#FFFFFF" border="0" style="padding:25px">
            @else
                <table cellspacing="0" cellpadding="0" bgcolor="#FFFFFF" border="0" style="padding:25px"
                    width="{{ $pageWidth }}">
        @endif
        <tbody>
            <tr>
                <td>
                    <table width="100%" cellspacing="0" cellpadding="0" border="0">
                        <tr>
                            <td rowspan="5">
                                <p class="text-right">
                                    @if ((isset($res['pdf']) && $res['pdf']) || (isset($res['storage']) && $res['storage']))
                                        <img src="{{ 'img/logo-rs.png' }}" width="80px" border="0">
                                    @else
                                        {{-- @if (stripos(\Request::url(), 'localhost') !== false || stripos(\Request::url(), '127.0.0.1') !== false) --}}
                                        <img src="{{ asset('img/logo-rs.png') }}" width="80px" border="0">
                                        {{-- @else
                                            <img src="{{ asset('service/img/logo-rs.png') }}" width="80px" border="0">
                                        @endif --}}
                                    @endif
                                </p>
                            </td>
                            <td class="text-center" style="text-align: center">
                                <span style="font-size: 14pt;font-weight: 600;letter-spacing: 0px;color:#000000">
                                    {!! strtoupper($profile->namapemerintahan) !!}
                                </span>
                            </td>
                            <td rowspan="5">
                                <div style="width: 80px;">
                            </td>
                        </tr>
                        {{-- <tr>
                            <td class="text-center">
                                <span style="font-size: 14pt;font-weight: 600;color:#000000">PEMERINTAH KOTA BANDUNG
                                </span>
                            </td>
                        </tr> --}}
                        <tr>
                            <td class="text-center" style="text-align: center">
                                <span style="font-size: 16pt;font-weight: 600;letter-spacing: 0px;color:#000000">
                                    {!! strtoupper($profile->namalengkap) !!}

                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-center" style="text-align: center">
                                <span style="font-size: 12pt;color:#000000">
                                    {!! $profile->alamatlengkap !!}

                                    {{ $profile->fixedphone }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-center" style="text-align: center">
                                <span style="font-size: 12pt; color:#000000">
                                    Email : <a href="#"> {!! $profile->alamatemail !!} </a>
                                    Website : <a href="#"> {!! $profile->website !!} </a>
                                </span>
                            </td>
                        </tr>
                    </table>
                    <hr class="baris1">
                    <hr class="baris2">
                </td>
            </tr>
            <tr>
                <td>
                    <table width="100%" cellspacing="0" cellpadding="0">
                        <tr>
                            <td align="center" height="80px">
                                @if($item->idruangantujuan == 331)
                                <font style="font-size: 16pt;font-weight: bold" color="#000000">FORMULIR PERMINTAAN DIAGNOSTIK IN VIVO</font>
                                @elseif($item->idruangantujuan == 389)
                                <font style="font-size: 16pt;font-weight: bold" color="#000000">FORMULIR PERMINTAAN TERAPI</font>
                                @elseif($item->idruangantujuan == 338)
                                <font style="font-size: 16pt;font-weight: bold" color="#000000">FORMULIR PERMINTAAN IN VITRO</font>
                                @endif
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td>
                    <table width="100%" cellspacing="0" cellpadding="0" border="1">
                        <tr>
                            <td  width="50%" align="left">
                                <span><h3 class="teks">NAMA PASIEN / NO RM</h3></span>
                            </td>
                            <td  width="50%" align="left">
                                <span><h3 class="teks">{{$item->namapasien}} / {{$item->nocm}}</h3></span>
                            </td>
                        </tr>
                        <tr>
                            <td  width="50%" align="left">
                                <span><h3 class="teks">TB / BB</h3></span>
                            </td>
                            <td  width="50%" align="left">
                                <span><h3 class="teks">{{$item->tb}} / {{$item->bb}}</h3></span>
                            </td>
                        </tr>
                        <tr>
                            <td  width="50%" align="left">
                                <span><h3 class="teks">TANGGAL LAHIR PASIEN</h3></span>
                            </td>
                            <td  width="50%" align="left">
                                <span><h3 class="teks">{{  date('d-m-Y', strtotime($item->tgllahir)) }}</h3></span>
                            </td>
                        </tr>
                        @if($item->idruangantujuan != 338)
                        <tr>
                            <td  width="50%" align="left">
                                <span><h3 class="teks">JENIS RADIONUKLIDA</h3></span>
                            </td>
                            <td  width="50%" align="left">
                                <span><h3 class="teks">{{$item->radionuklida}}</h3></span>
                            </td>
                        </tr>
                        @endif
                        @if($item->idruangantujuan == 331)
                        <tr>
                            <td  width="50%" align="left">
                                <span><h3 class="teks">JENIS FARMAKA</h3></span>
                            </td>
                            <td  width="50%" align="left">
                                @if($item->catatanfarmaka != null)
                                <span><h3 class="teks">{{$item->catatanfarmaka}}</h3></span>
                                @else
                                <span><h3 class="teks">{{$item->farmaka}}</h3></span>
                                @endif
                            </td>
                        </tr>
                        @endif
                        @if($item->idruangantujuan != 338)
                        <tr>
                            <td  width="50%" align="left">
                                <span><h3 class="teks">DOSIS</h3></span>
                            </td>
                            <td  width="50%" align="left">
                                @if($item->idruangantujuan == 331)
                                <span><h3 class="teks">{{$item->terapiradiofarmaka}}</h3></span>
                                @else
                                <span><h3 class="teks">{{$item->terapiiodium}}</h3></span>
                                @endif
                            </td>
                        </tr>
                        @endif
                        <tr>
                            <td  width="50%" align="left">
                                <span><h3 class="teks">TANGGAL DAN JAM</h3></span>
                            </td>
                            <td  width="50%" align="left">
                                <span><h3 class="teks">{{  date('d-m-Y', strtotime($item->tglregistrasi)) }}</h3></span>
                            </td>
                        </tr>
                        <tr>
                            <td  width="50%" align="left">
                                <span><h3 class="teks">NAMA DPJP</h3></span>
                            </td>
                            <td  width="50%" align="left">
                                <span><h3 class="teks">{{  $item->dpjp }}</h3></span>
                            </td>
                        </tr>
                        <tr>
                            <td  width="50%" align="left">
                                <span><h3 class="teks">PARAF RADIOFARMASIS</h3></span>
                            </td>
                            <td  width="50%" align="left">
                                <span><h3 class="teks"></h3></span>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </tbody>
        </table>
    </div>
</body>
@endforeach
</html>