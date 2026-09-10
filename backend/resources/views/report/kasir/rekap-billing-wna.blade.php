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
        @if ((isset($res['pdf']) && $res['pdf']) || (isset($res['storage']) && $res['storage']))
            <table cellspacing="0" cellpadding="0" bgcolor="#FFFFFF" border="0" style="padding:25px">
            @else
                <table cellspacing="0" cellpadding="0" bgcolor="#FFFFFF" border="0" style="padding:25px"
                    width="{{ $pageWidth }}">
        @endif
        <tbody>
            <tr>
                <td>
                    
                    <table width="100%" cellspacing="0" cellpadding="0">
                        <tr>
                            <th>
                                <img src="{{ asset('img/logo-rs.png') }}" width="80px">
                            </th>
                            <th width="90%">
                                <table width="100%"  style="position:relative">
                                    <tr>
                                        <td class="label-strong" style="text-align:left; font-size: 16pt;">
                                            <font>{{ strtoupper($profile->namalengkap) }}</font>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="label-normal" style="text-align:left">
                                            <font style="font-size: 10pt;">{{$profile->alamatlengkap}} <br> {{ $profile->alamatemail }}</font>
                                        </td>
                                    </tr>
                                </table>
                            </th>
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
                            <td colspan="4" style="padding-bottom: 20px;" align="center">
                                <span class="text-normal">
                                    <b>INVOICE</b>
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td width="15%">
                                <span class="text-normal">No. Invoice</span>
                            </td>
                            <td width="35%">
                                <span class="text-normal">:
                                    {{ $data[0]->nosbm }}</span>
                            </td>

                            <td width="15%">
                                <span class="text-normal">Ward</span>
                            </td>
                            <td width="35%">
                                <span class="text-normal">:
                                    {{ $res['identitas']->namaruangan }}</span>
                            </td>
                        </tr>
                        <tr>

                            <td width="15%">
                                <span class="text-normal">No. NRM</span>
                            </td>
                            <td width="35%">
                                <span class="text-normal">:
                                    {{ $res['identitas']->nocm }}</span>
                            </td>

                            <td width="15%">
                                <span class="text-normal">Class</span>
                            </td>
                            <td width="35%">
                                <span class="text-normal">:
                                    {{ $res['identitas']->namakelas }}</span>
                            </td>
                        </tr>
                        <tr>

                            <td width="15%">
                                <span class="text-normal">No. Reg</span>
                            </td>
                            <td width="35%">
                                <span class="text-normal">: {{ $res['identitas']->noregistrasi }}
                                </span>
                            </td>


                            <td width="15%">
                                <span class="text-normal">Room</span>
                            </td>
                            <td width="35%">
                                <span class="text-normal">:</span>
                            </td>
                        </tr>
                        <tr>

                            <td width="15%">
                                <span class="text-normal">Pasien</span>
                            </td>
                            <td width="35%">
                                <span class="text-normal">:
                                    {{ $res['identitas']->namapasien }}</span>
                            </td>

                            <td width="15%">
                                <span class="text-normal">Reg Date</span>
                            </td>
                            <td width="35%">
                                <span class="text-normal">:
                                {{ date_format(date_create($res['identitas']->tglregistrasi), 'j-F-Y') }}</span>
                            </td>
                        </tr>
                        <tr>

                            <td width="15%">
                                <span class="text-normal">Address</span>
                            </td>
                            <td width="35%">
                                <span class="text-normal">:
                                    {{ $res['identitas']->alamatlengkap }}</span>
                            </td>

                            <td width="15%">
                                <span class="text-normal">Member Card</span>
                            </td>
                            <td width="35%">
                                <span class="text-normal">:</span>
                            </td>
                        </tr>
                        <tr>

                            <td width="15%">
                                <span class="text-normal">Company</span>
                            </td>
                            <td width="35%">
                                <span class="text-normal">:</span>
                            </td>

                            <td width="15%">
                                <span class="text-normal">From Date</span>
                            </td>
                            <td width="35%">
                                <span class="text-normal">:
                                {{ date_format(date_create($res['identitas']->tglregistrasi), 'j F Y') }} s/d {{ date_format(date_create($res['identitas']->tglpulang), 'j F Y') }}</span>
                            </td>
                        </tr>
                        <tr>
                            <td width="15%">
                                <span class="text-normal">Age</span>
                            </td>
                            <td width="35%">
                                <span class="text-normal">: {{ $res['identitas']->umur }}</span>
                            </td>
                            <td width="15%">
                                <span class="text-normal">Doctor</span>
                            </td>
                            <td width="35%">
                                <span class="text-normal">: {{ $res['identitas']->namalengkap }}</span>
                            </td>
                        </tr>
                        <tr>
                            <td width="15%">
                                <span class="text-normal">Employee</span>
                            </td>
                            <td width="35%">
                                <span class="text-normal">: {{ $res['identitas']->namapasien }}</span>
                            </td>
                            <td width="15%">
                                <span class="text-normal"></span>
                            </td>
                            <td width="35%">
                                <span class="text-normal"></span>
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
                            <td class="text-left">
                                <span class="text-judul" style="font-size: 11pt;">DESCRIPTIONS</span>
                            </td>
                        </tr>
                    </table>
                    <hr class="baris1">
                    <hr class="baris2">
                </td>
            </tr>
            <tr>
                <td style="padding-top:10px">
                    <table width="100%" cellspacing="0" cellpadding="1" border="0">
                        @php
                            $nomor = 1;
                            $nomors = 1;
                            $totaltagihan = 0;
                            $totaldiskon = 0;
                            $jumlahbill = 0;
                            $totaldiklaim = 0;
                        @endphp
                        @foreach ($res['billing'] as $ruangan)
                        
                            <tr>
                                <td colspan="9">
                                    <span class="text-normal bold">
                                        <b>   {{$nomors}}. {{ ucwords($ruangan[0]->namaruangan) }}</b>
                                    </span>
                                </td>
                            </tr>
                            @php
                            $n = 1;
                            $totalSUB = 0;
                            $nomors++;
                            @endphp
                            @foreach ($ruangan as $k => $item)
                            
                                @php
                                    $total = 0;
                                    $diskon = 0;
                                    $jml = 0;
                                @endphp
                                @foreach ($item as $data)
                                    @php
                                    $nomor = $nomor + 1;
                                    $total = $total + $item->total;
                                    $jml = $jml + $item->jumlah;
                                    $diskon = $diskon + $item->diskon;
                                    @endphp
                                @endforeach
                                <tr>
                                    <td class="text-top text-left" width="5%"></td>
                                    <td class="text-top text-left" width="55%">
                                        <span class="text">{{ App\Traits\Valet::english($item->namaproduk) }}</span>
                                    </td>
                                
                                    <td class="text-top text-right" width="15%">
                                        <span class="text">{{ number_format($item->hargasatuan, 2, '.', ',') }}</span>
                                    </td>
                                    <td class="text-top text-center" width="5%">
                                        <span class="text"> x </span>
                                    </td>
                                    <td class="text-top text-right" width="5%">
                                        <span class="text">{{ number_format($item->jumlah, 2, '.', '.')}}</span>
                                    </td>
                                    <td class="text-top text-left" width="5%">
                                        <span class="text"> = </span>
                                    </td>
                                
                                    <td class="text-top text-right" width="15%">
                                        <span class="text"> {{ number_format($item->hargasatuan*$item->jumlah, 0, '.', ',') }}</span>
                                    </td>
                                </tr>
                                @php
                            
                                    $n = $n+1;
                                    $totalSUB = $totalSUB + ($item->hargasatuan*$item->jumlah);
                                    $totaltagihan = $totaltagihan + $total;
                                    $totaldiskon = $totaldiskon + $diskon;
                                    $jumlahbill = $totaltagihan - $totaldiskon;
                                @endphp
                            @endforeach
                            <tr>
                                <td class="text-right" colspan="6"></td>
                                <td class="text-right" style="border-top: 1px solid black;">
                                    <span class="text">
                                        {{ number_format($totalSUB, 0, '.', ',') }}
                                    </span>
                                </td>
                            </tr> 
                        @endforeach
                    </table>
                </td>
            </tr>
            <tr>
                <td style="padding-top:10px">
                    <table width="100%" cellspacing="0" cellpadding="0" style="border-top:2px solid black; border-bottom:2px solid black;">
                        <tr>
                            <td width="50%"></td>
                            <td width="20%" class="text-left">
                                <span class="text-biasa">SUB TOTAL</span>
                            </td>
                            <td class="text-center">
                                <span class="text-biasa">=</span>
                            </td>
                            <td class="text-right">
                                <span class="text-biasa">
                                    {{ number_format( $res['total'], 0, '.', ',') }}</span>
                            </td>
                        </tr>
                        <tr>
                            <td width="50%"></td>
                            <td width="20%" class="text-left">
                                <span class="text-biasa">GRAND TOTAL</span>
                            </td>
                            <td class="text-center">
                                <span class="text-biasa">=</span>
                            </td>
                            <td class="text-right">
                                <span class="text-biasa">
                                    {{ number_format( $res['total'], 0, '.', ',') }}</span>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td style="padding-top:20px">
                    <table width="100%" cellspacing="0" cellpadding="0" style="border-bottom:2px solid black; margin-top: -20px;">
                        <tr>
                            <td>
                                <span class="text-biasa">To Word :
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span class="text-biasa"><i>#
                                        {{ strtoupper(App\Traits\Valet::english(App\Traits\Valet::static_terbilang($res['total']))) }}S #</i>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td style="padding-top:20px">
                    <table width="100%" cellspacing="0" cellpadding="0">
                        <tr>
                            <td width="35%" class="text-center">
                                <span class="text-biasa" class="text-center"></span>
                            </td>
                            <td width="30%" class="text-center">
                                <span class="text-biasa" class="text-center"></span>
                            </td>
                            <td width="35%" class="text-center">
                                <span class="text-biasa">{!! $profile->namakota !!},&nbsp;&nbsp;{{ date('d/m/Y H:i') }}</span>
                            </td>
                        </tr>
                        <tr>
                            <td width="35%" class="text-center"><span class="text-biasa"></span>
                            </td>
                            <td width="30%" class="text-center"><span class="text-biasa"></span>
                            </td>
                            <td width="35%" class="text-center"><span class="text-biasa"></span>
                            </td>
                        </tr>
                        <tr>
                            <td height="80" valign="bottom" height="100" width="35%" class="text-center"></td>
                            <td height="80" valign="bottom" height="100"width="30%" class="text-center"></td>
                            <td height="80" valign="bottom" height="100"width="35%" class="text-center">
                                <span class="text-biasa">(&emsp;{{$user->namalengkap}}&emsp;)<br><u>Cashier</u></span>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>

        </tbody>
        </table>
    </div>
</body>

</html>

































