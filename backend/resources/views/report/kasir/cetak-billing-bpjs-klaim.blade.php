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
            size: 215 mm 300 mm landscape;
            margin: 0;
            /* size: portrait; */
        }

        footer {
            display: none
        }

        header {
            display: none
        }
        table { page-break-inside:auto }
        tr    { page-break-inside:auto; page-break-after:avoid }


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
        font-size: 8pt;
    }

    .text-normal{
        font-size: 8pt;
    }



    .garis6 td {
        padding: 3px !important;
    }
    table { page-break-inside:auto }
        tr    { page-break-inside:auto; page-break-after:avoid }
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
            <table cellspacing="0" cellpadding="0" bgcolor="#FFFFFF" border="0" style="padding:25px; padding-top:25px; padding-left: 7px">
            @else
                <table cellspacing="0" cellpadding="0" bgcolor="#FFFFFF" border="0" style="padding:25px; padding-top:25px; padding-left: 7px"
                    width="{{ $pageWidth }}">
        @endif
        <tbody>
            <tr>
                <td>
                    <table width="100%" cellspacing="0" cellpadding="0" border="0">
                        <tr>
                            <td rowspan="3">
                                <p class="text-right">
                                    @if((isset( $res['pdf']) &&  $res['pdf']) || (isset( $res['storage']) && $res['storage']))
                                        <img src="{{'img/logo-rs.png'}}" width="70px" border="0">
                                    @else
                                        <img src="{{ asset('img/logo-rs.png') }}" width="70px" border="0">
                                    @endif
                                </p>
                            </td>
                            <td class="text-left" style="text-align: left">
                                <span style="font-size: 13pt;font-weight: 600;letter-spacing: 0px;color:#000000">
                                    {!! strtoupper($profile->namalengkap) !!}

                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-left" style="text-align: left">
                                <span style="font-size: 11pt;color:#000000">
                                    <b>{!! $profile->alamatlengkap !!}</b>
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-left" style="text-align: left">
                                <span style="font-size: 11pt; color:#000000">
                                    <b>{!! $profile->alamatemail !!}</b>
                                </span>
                            </td>
                        </tr>
                    </table>
                    <tr class="text-center" style="text-align: center;">
                        <td class="text-center" style="text-align: center">
                            <div style="width: 50%">
                                <span style="font-size: 12pt; color:#000000">
                                    <b>INVOICE NO.</b><br>{{$res['identitas']->nostruk}}
                                </span>
                            </div>
                        </td>
                    </tr>
                </td>
            </tr>
        <tr>
            <td>
                <table width="100%" cellspacing="0" cellpadding="0" style=" border: 1px solid black; border-collapse: collapse;">
                    
                    <tr>
                        <td width="25%">
                            <span class="text-normal">No. / Tgl. Reg</span>
                        </td>
                        <td width="40%" align="left">
                            <span class="text-normal">: {{$res['identitas']->noregistrasi}}</span>
                        </td>
                        <td width="35%" align="right">
                            <span class="text-normal">/ {{ date('d-m-Y', strtotime($res['identitas']->tglregistrasi ?? '')) }}</span>
                        </td>
                    </tr>
                    <tr>
                        <td width="25%">
                            <span class="text-normal">NRM</span>
                        </td>
                        <td width="40%" align="left" colspan="2">
                            <span class="text-normal">: {{$res['identitas']->nocm}}</span>
                        </td>
                    </tr>
                    <tr>
                        <td width="25%">
                            <span class="text-normal">Nama</span>
                        </td>
                        <td width="40%" align="left" colspan="2">
                            <span class="text-normal">: {{$res['identitas']->namapasien}}</span>
                        </td>
                    </tr>
                    <tr>
                        <td width="25%">
                            <span class="text-normal">Alamat</span>
                        </td>
                        <td width="40%" align="left" colspan="2">
                            <span class="text-normal">: {{$res['identitas']->alamatlengkap}}</span>
                        </td>
                    </tr>
                    <tr>
                        <td width="25%">
                            <span class="text-normal">Ruangan</span>
                        </td>
                        <td width="40%" align="left">
                            <span class="text-normal">: {{$res['identitas']->namaruangan}}</span>
                        </td>
                        <td width="35%" align="right">
                            <span class="text-normal"><b>{{$res['identitas']->umur}}</b></span>
                        </td>
                    </tr>
                    <tr>
                        <td width="25%">
                            <span class="text-normal">Dokter</span>
                        </td>
                        <td width="40%" align="left" colspan="2">
                            <span class="text-normal">: {{$res['identitas']->namalengkap}}</span>
                        </td>
                    </tr>
                    <tr>
                        <td width="25%">
                            <span class="text-normal">Company</span>
                        </td>
                        <td width="40%" align="left">
                            @if($res['identitas']->kelompokpasien == 'BPJS')
                            <span class="text-normal">: BPJS Kesehatan</span>
                            @else
                            <span class="text-normal">: {{$res['identitas']->namarekanan}}</span>
                            @endif
                        </td>
                        <td width="35%" align="right">
                            <span class="text-normal">/ {{$res['identitas']->nobpjs}}</span>
                        </td>
                    </tr>
                    <tr>
                        <td width="25%">
                            <span class="text-normal">Penanggung</span>
                        </td>
                        <td width="40%" align="left" colspan="2">
                            <span class="text-normal">: {{$res['identitas']->namapasien}}</span>
                        </td>
                    </tr>
                    <tr>
                        <td width="25%">
                            <span class="text-normal"><b>Nomor VA</b></span>
                        </td>
                        <td width="40%" align="left" colspan="2">
                            <span class="text-normal">: </span>
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
                            <span class="text-judul" style="font-size: 11pt;">RINCIAN BIAYA</span>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td style="padding-top:10px">
                <table width="100%" cellspacing="0" cellpadding="1" border="0">
                    @php
                        $nomor = 1;
                        $totaltagihan = 0;
                        $totaldiskon = 0;
                        $jumlahbill = 0;
                        $totaldiklaim = 0;
                    @endphp
                    @foreach ($res['billing'] as $ruangan)
                        <tr>
                            <td colspan="9">
                                <span class="text-normal bold" style="color: #d60d0d;">
                                    <b><u>{{ strtoupper($ruangan[0]->ruang_group) }}</u></b>
                                </span>
                            </td>

                        </tr>
                        @foreach ($ruangan->groupBy('detailjenisproduk') as $item)
                            <tr  style="font-style:italic">
                                <td colspan="9">
                                    <span class="text-normal">
                                        <b>     {{ ucwords($item[0]->detailjenisproduk) }}</b>
                                    </span>
                                </td>
                            </tr>
                            @php
                                $total = 0;
                                $diskon = 0;
                            @endphp
                            @foreach ($item as $data)
                                <tr>
                                    <td class="text-top text-left" width="20%">
                                        <span class="text">{{ date('d-m-Y', strtotime($data->tglpelayanan_group)) }}</span>
                                    </td>
                                    <td class="text-top text-left">
                                        <span class="text">{{ $data->namaproduk }}</span>
                                    </td>
                                    
                                    <td class="text-top text-right">
                                        <span class="text"> {{ number_format($data->hargasatuan) }}</span>
                                    </td>
                                    <td class="text-top text-center">
                                        <span class="text">X</span>
                                    </td>
                                    <td class="text-top text-right">
                                        <span class="text">{{ $data->jumlah }}</span>
                                    </td>
                                    <td class="text-top text-center">
                                        <span class="text">=</span>
                                    </td>
                                    <td class="text-top text-right">
                                        <span class="text"> {{ number_format($data->total) }}</span>
                                    </td>
                                </tr>
                                @php
                                    $nomor = $nomor + 1;
                                    $total = $total + $data->total;
                                    $diskon = $diskon + $data->diskon;
                                @endphp
                            @endforeach
                            <tr>
                                <td class="text-right" colspan="9">
                                    <span class="text">
                                        {{ number_format($total) }}
                                    </span>
                                </td>
                            </tr>
                            @php
                                $totaltagihan = $totaltagihan + $total;
                                $totaldiskon = $totaldiskon + $diskon;
                                $jumlahbill = $totaltagihan - $totaldiskon;
                            @endphp
                        @endforeach
                    @endforeach
                </table>
            </td>
        </tr>
        <tr>
            <td style="padding-top:10px">
                <table width="100%" cellspacing="0" cellpadding="0" style="border-top:2px solid black;">
                    <tr>
                        <td width="50%">
                            <span class="text-biasa"><b>TERBILANG</b> : <br> # {{ ucwords(App\Traits\Valet::static_terbilang($jumlahbill)) }} #</span>

                        </td>
                        <td width="20%" class="text-left">
                            <span class="text-biasa"><b>GRAND TOTAL</b></span>
                        </td>
                        <td class="text-center">
                            <span class="text-biasa">:</span>
                        </td>
                        <td class="text-right">
                            <span class="text-biasa">
                                <b>{{ number_format( $res['total'] ) }}</b></span>
                        </td>
                    </tr>
                    <tr>
                            <td width="50%"></td>
                            <td width="20%" class="text-left">
                                <span class="text-biasa"><b>DISKON</b></span>
                            </td>
                            <td class="text-center">
                                <span class="text-biasa">:</span>
                            </td>
                            <td class="text-right">
                                <span class="text-biasa">
                                    <b>{{ number_format( $res['identitas']->totaldiskon ) }}</b></span>
                            </td>
                        </tr>
                        <tr>
                            <td width="50%"></td>
                            <td width="20%" class="text-left">
                                <span class="text-biasa"><b>TOTAL DIBAYAR</b></span>
                            </td>
                            <td class="text-center">
                                <span class="text-biasa">:</span>
                            </td>
                            <td class="text-right">
                                <span class="text-biasa">
                                    <b>{{ number_format( $res['total'] - $res['identitas']->totaldiskon ) }}</b></span>
                            </td>
                        </tr>
                    <tr>
                        <td width="50%"></td>
                        <td width="50%" colspan="3">
                            <hr class="baris1" style="margin-top:0px">
                            <hr class="baris1" style="margin-top:2.5px">
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        {{-- <tr>
            <td style="padding-top:20px">
                <table width="100%" cellspacing="0" cellpadding="0">
                    <tr>
                        <td>
                            <span class="text-biasa">TERBILANG :
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <span class="text-biasa"><i>#
                                    {{ strtoupper(App\Traits\Valet::static_terbilang($jumlahbill)) }} #</i>
                        </td>
                    </tr>
                </table>
            </td>
        </tr> --}}
        @if ($res['ismultipenjamin'] == true)
            <tr>
                <td>
                    <span class="text-biasa">Multi Penjamin :
                </td>
            </tr>
            <tr>
                <td style="padding-top:20px">
                    <table width="100%" cellspacing="0" cellpadding="0" border="1">
                        <tr class="garisatasbawah">
                            <th class="text-center"><span class="text-biasa">Penjamin</span>
                            </th>
                            <th class="text-center"><span class="text-biasa">Jumlah</span>
                            </th>
                        </tr>
                        @php
                            $total = 0;
                        @endphp
                        @foreach ($res['multipenjamin'] as $item)
                            @php
                                $total = $total + $item->totalppenjamin;
                            @endphp
                            <tr>
                                <td class="text-left"><span class="text-biasa">{{ $item->namarekanan }}</span>
                                </td>
                                <td class="text-right"><span
                                        class="text-biasa">{{ number_format($item->totalppenjamin) }}</span>
                                </td>
                            </tr>
                        @endforeach
                        <tr>
                            <td class="text-right">
                                <span class="text">
                                    <b>JUMLAH</b>
                                </span>
                            </td>
                            <td class="text-right">
                                <span class="text">
                                    {{ number_format($total) }}
                                </span>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        @endif
            <tr>
                <td style="padding-top:20px">
                    <table width="100%" cellspacing="0" cellpadding="0">
                        <tr>
                            <td width="50%" class="text-center">
                                <span class="text-biasa" class="text-center"></span>
                            </td>
                            <td width="50%" class="text-center">
                                <span class="text-biasa">{!! $profile->namakota !!},
                                    &nbsp;&nbsp;{{ date('j-F-Y', strtotime($res['identitas']->tglregistrasi)) }}</span>
                            </td>
                        </tr>
                        <tr>
                            <td width="50%" class="text-center"><span class="text-biasa"></span>
                                <span class="text-biasa">
                                    @if($res['identitas']->id_kelompokpasien == 2)
                                    <img src="data:image/jpeg;base64,{{ $qrcode2 }}" width="60px" border="0" style="margin-top: 30px; margin-bottom: -70px;">
                                    @endif
                                </span>
                            </td>
                            <td width="50%" class="text-center">
                                <span class="text-biasa">
                                    <img src="data:image/jpeg;base64,{{ $qrcode }}" width="60px" border="0" style="margin-top: 30px; margin-bottom: -70px;">
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td height="80" valign="bottom" height="100" width="25%" class="text-center">
                                <span class="text-biasa">(<u>{{$res['identitas']->namapasien}}</u>)</span>
                            </td>
                            <td height="80" valign="bottom" height="100"width="25%" class="text-center">
                                @if(isset($res['identitas']->kasir))
                                <span class="text-biasa">(<u>{{$res['identitas']->kasir}}</u>)</span>
                                @else
                                <span class="text-biasa">(<u>NI PUTU GUSTINI PUTRI</u>)</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td valign="bottom" width="25%" class="text-center">
                                <span class="text-biasa">Pasien</span>
                            </td>
                            <td valign="bottom"width="25%" class="text-center">
                                <span class="text-biasa">Kasir</span>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td style="padding-top:20px">
                    <table width="100%" cellspacing="0" cellpadding="0">
                        <tr>
                            <td width="50%" align="left">
                                <span class="text-normal" style="font-size: 9px;">
                                    Printed on : &nbsp;&nbsp;&nbsp;
                                    {{ date('d/m/Y H:i', strtotime($res['identitas']->tglregistrasi)) }}
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

</html>