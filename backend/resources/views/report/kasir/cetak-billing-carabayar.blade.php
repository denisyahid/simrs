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
        /* @page {
            size: 215 mm 140 mm landscape;
            margin: 0;
            /* size: portrait; */
        /* }  */

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
        font-size: 10pt;
    }

    .text-normal{
        font-size: 10pt;
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
@php
    $tr = new GoogleTranslate();
    $tr->setSource();
    $indo = $res['indo'];
@endphp
    @yield('page-style')
    <div align="center">
        @if ((isset($res['pdf']) && $res['pdf']) || (isset($res['storage']) && $res['storage']))
            <table cellspacing="0" cellpadding="0" bgcolor="#FFFFFF" border="0" style="margin-left:15px; padding-top:25px">
            @else
                <table cellspacing="0" cellpadding="0" bgcolor="#FFFFFF" border="0" style="margin-left: 15px; padding-top:25px"
                    width="{{ $pageWidth }}">
        @endif
        <tbody>
            <tr>
                <td>
                    <table width="50%" cellspacing="0" cellpadding="0" border="0">
                        <tr>

                            <td class="text-center" style="text-align: center">
                                <div style="width: 80%">
                                    <span style="font-size: 13pt;font-weight: 600;letter-spacing: 0px;color:#000000">
                                        {!! $indo ? strtoupper($profile->namalengkap) : 'BALI MANDARA HOSPITAL' !!}
    
                                    </span>
                                </div>

                            </td>
                        </tr>
                        <tr>
                            <td class="text-center" style="text-align: center">
                                <div style="width: 80%">
                                    <span style="font-size: 11pt;color:#000000">
                                        <b>{!! $profile->alamatlengkap !!}</b>
                                    </span>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-center" style="text-align: center">
                                <div style="width: 80%">
                                    <span style="font-size: 11pt; color:#000000">
                                        <a href="#"><b>{!! $profile->alamatemail !!}</b></a>
                                    </span>
                                </div>

                            </td>
                        </tr>
                        {{-- <tr>
                            <td>
                                <hr class="baris1" style="margin-top:0px">
                                <hr class="baris1" style="margin-top:2.5px">
                            </td>
                        </tr> --}}
                    </table>
                    <div style="width: 40%">
                        <hr class="baris1">
                        <hr class="baris2">
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    <table width="50%" cellspacing="0" cellpadding="0">
                        <tr>
                            <td align="center">
                                <div style="width: 80%">
                                    <span class="text-judul" style="font-size: 11pt;">
                                        {{ $indo ? 'KWITANSI' : $tr->translate('KWITANSI') }}
                                    </span>
                                </div>
                                
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td>
                    <table width="50%" cellspacing="0" cellpadding="0">
                        <tr>
                            <td>
                                
                                <span style="font-size: 9pt;">{{ $indo ? 'Sudah terima dari' : $tr->translate('Sudah terima dari') }} : {{ $indo ? 'Pasien' : $tr->translate('Pasien') }} / {{ strtoupper($res['identitas']->namapasien) }}</span>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span style="font-size: 9pt;">
                                    {{ $indo ? 'Jumlah Uang' : $tr->translate('Jumlah Uang') }} <b><u>{{ number_format($res['dibayar'] - $res['identitas']->totaldiskon, 0, '.', ',') }}</u></b>
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span style="font-size: 9pt;">
                                    {{ $indo ? 'Untuk Pembayaran, Pelunasan Biaya Perawatan Rumah Sakit' : $tr->translate('Untuk Pembayaran, Pelunasan Biaya Perawatan Rumah Sakit') }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span style="font-size: 9pt;">No. Invoice : {{$res['identitas']->nosbm}}</span>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        <tr>
            <td>
                <table width="50%" cellspacing="0" cellpadding="0">
                    <tr>
                        <td width="15%"></td>
                        <td width="20%">
                            <span class="text-normal">
                                
                                {{ $indo ? 'No. RM' : 'No. MR' }}
                            </span>
                        </td>
                        <td width="65%" align="left">
                            <span class="text-normal">: {{$res['identitas']->nocm}}</span>
                        </td>
                    </tr>
                    <tr>
                        <td width="15%"></td>
                        <td width="20%">
                            <span class="text-normal">No. Reg</span>
                        </td>
                        <td width="65%" align="left">
                            <span class="text-normal">: {{$res['identitas']->noregistrasi}}</span>
                        </td>
                    </tr>
                    <tr>
                        <td width="15%"></td>
                        <td width="20%">
                            <span class="text-normal">
                                {{ $indo ? 'Pasien' : $tr->translate('Pasien') }}
                            </span>
                        </td>
                        <td width="65%" align="left">
                            <span class="text-normal">: {{$res['identitas']->namapasien}}</span>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
            <tr>
                <td style="padding-top:20px">
                    <table width="40%" cellspacing="0" cellpadding="0" style="border-bottom: 1px solid black;">
                        <tr>
                            <td width="50%" class="text-left">
                                <span class="text-biasa" class="text-center">{{ $indo ? 'Dengan Jenis Pembayaran' : $tr->translate('Dengan Jenis Pembayaran') }} :</span>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            @php
                $nomor = 0;
            @endphp
            @foreach($data as $item)
                @php
                    $nomor++;
                @endphp
                <tr>
                    <td style="padding-top:20px">
                        <table width="40%" cellspacing="0" cellpadding="0" style="border-bottom: 1px solid black; margin-top: -20px;">
                            <tr>
                                <td width="10%" class="text-left">
                                    <span class="text-biasa" class="text-center"><b>{{ $nomor }}</b></span>
                                </td>
                                <td width="60%" class="text-left">
                                    <span class="text-biasa"><b>
                                        {{ $indo ? $item->carabayar : $tr->translate($item->carabayar) }}
                                    </b></span>
                                </td>
                                <td width="30%" class="text-right">
                                    <span class="text-biasa"><b>{{number_format($item->totalharusdibayar, 2, '.', ',')}}</b></span>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            @endforeach
            <tr>
                <td style="padding-top:20px">
                    <table width="40%" cellspacing="0" cellpadding="0" style="margin-top: -10px;">
                        <tr>
                            <td width="75%" class="text-right">
                                <span class="text-biasa" class="text-center"><b>TOTAL</b></span>
                            </td>
                            <td width="25%" class="text-right">
                                <span class="text-biasa" class="text-center"><b>{{ number_format($res['dibayar'], 0, '.', ',') }}</b></span>
                            </td>
                        </tr>
                        <tr>
                            <td width="75%" class="text-right">
                                <span class="text-biasa" class="text-center"><b>DISKON</b></span>
                            </td>
                            <td width="25%" class="text-right">
                                <span class="text-biasa" class="text-center"><b>{{ number_format($res['identitas']->totaldiskon, 0, '.', ',') }}</b></span>
                            </td>
                        </tr>
                        <tr>
                            <td width="75%" class="text-right">
                                <span class="text-biasa" class="text-center"><b>TOTAL DIBAYAR</b></span>
                            </td>
                            <td width="25%" class="text-right">
                                <span class="text-biasa" class="text-center"><b>{{ number_format($res['dibayar'] - $res['identitas']->totaldiskon, 0, '.', ',') }}</b></span>
                            </td>
                        </tr>
                        <tr>
                            <td width="50%" class="text-left">
                                <span class="text-biasa" class="text-center">{{ $indo ? 'Terbilang' : $tr->translate('Terbilang') }} <br> # {{ $indo ? ucwords(App\Traits\Valet::static_terbilang($res['dibayar'] - $res['identitas']->totaldiskon)) : $tr->translate(ucwords(App\Traits\Valet::static_terbilang($res['dibayar'] - $res['identitas']->totaldiskon))) }}</span>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td style="padding-top:20px">
                    <table width="40%" cellspacing="0" cellpadding="0">
                        <tr>
                            <td width="50%" class="text-center">
                                <span class="text-biasa" class="text-center">
                                    ( {{ $indo ? 'Pasien' : $tr->translate('Pasien') }} / {{ $indo ? 'Keluarga' : $tr->translate('Keluarga') }})
                                </span>
                            </td>
                            <td width="50%" class="text-center">
                                <span class="text-biasa">{!! $profile->namakota !!},
                                    {{-- &nbsp;&nbsp;{{ date('j-F-Y') }}</span> --}}
                                    &nbsp;&nbsp; {{ $res['identitas']->tglclosing == null ? date('j-F-Y') : date('j-F-Y', strtotime($res['identitas']->tglclosing)) }}
                            </td>
                        </tr>
                        <tr>
                            <td width="50%" class="text-center"><span class="text-biasa"></span>
                            </td>
                            <td width="50%" class="text-center"><span class="text-biasa"></span>
                            </td>
                        </tr>
                        <tr>
                            <td height="80" valign="bottom" height="100" width="25%" class="text-center">
                                <span class="text-biasa">(<u>{{$res['identitas']->namapasien}}</u>)</span>
                            </td>
                            <td height="80" valign="bottom" height="100"width="25%" class="text-center">
                                <span class="text-biasa">(<u>{{$res['user']}}</u>)</span>
                            </td>
                        </tr>
                        <tr>
                            <td valign="bottom" width="25%" class="text-center">
                                <span class="text-biasa">{{ $indo ? 'Pasien' : $tr->translate('Pasien') }}</span>
                            </td>
                            <td valign="bottom"width="25%" class="text-center">
                                <span class="text-biasa">{{ $indo ? 'Kasir' : $tr->translate('Kasir') }}</span>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td>
                    <table width="50%" cellspacing="0" cellpadding="0">
                        <tr>
                            <td width="50%" align="left">
                                <span class="text-normal" style="font-size: 9px;">
                                    Printed on : &nbsp;&nbsp;&nbsp;
                                    {{ date('d/m/Y H:i') }}
                                </span>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>

        </tbody>
        </table>
    </div>
</body>

<script type='text/javascript'>
    window.print();
    window.onafterprint=()=>window.close();
</script>

</html>
