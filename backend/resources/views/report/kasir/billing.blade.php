<!DOCTYPE html>
<html>
@php
    $profile = App\Http\Controllers\Controller::static_profile();
@endphp
<head>
    <title>Billing</title>
    <meta name="robots" content="noindex">
    <meta name="googlebot" content="noindex">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>
    @if (stripos(\Request::url(), 'localhost') !== false || stripos(\Request::url(), '127.0.0.1') !== false || stripos(\Request::url(), 'rsudbali') !== false)
    <link rel="stylesheet" href="{{ asset('css/paper.css') }} ">
    <link rel="stylesheet" href="{{ asset('css/table-v2.css') }}">
    {{-- <link rel="stylesheet" href="{{ asset('css/tabel.css') }}"> --}}
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    @else
        <link rel="stylesheet" href="{{ asset('service/css/paper.css') }} ">
        <link rel="stylesheet" href="{{ asset('service/css/table-v2.css') }}">
        {{-- <link rel="stylesheet" href="{{ asset('service/css/tabel.css') }}"> --}}
        <link rel="stylesheet" href="{{ asset('service/css/style.css') }}">
    @endif
    {{-- <link rel="stylesheet" href="css/paper.css ">
    <link rel="stylesheet" href="css/table-v2.css">
    <link rel="stylesheet" href="css/style.css"> --}}
    <style>
        body,
        td,
        th,
        span,
        p {
            font-family: Arial, Helvetica, sans-serif !important;
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
            -webkit-print-color-adjust:exact;
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
        tr {
            page-break-inside: avoid;
            page-break-after: auto;
        }
        thead {
            display: table-header-group;
        }
        tfoot {
            display: table-footer-group;
        }
        .avoid-page-break {
            page-break-inside: avoid;
        }
    </style>
    <style type="text/css" media="print">
        @media print {
            @page {
                size: 215 mm 297 mm landscape;
                padding: 30px;
                /* size: portrait; */
            }
            body,
            td,
            th,
            span,
            p {
                font-family: monospace, monospace;
                font-size: 11px;
                -webkit-print-color-adjust:exact;
            }
            .text-normal-1 {
                font-size: 14px !important;
            }
            .text-biasa {
                font-size: 15px !important;
            }
        }
        body,
        td,
        th,
        span,
        p {
            font-family: monospace, monospace;
            font-size: 11px;
            -webkit-print-color-adjust:exact;
        }
        .text-normal-1 {
            font-size: 14px !important;
        }
        .text-biasa {
            font-size: 15px !important;
        }
        .text-normal {
            font-size: 14px !important;
        }
    </style>
</head>

<body>
@php
    $tr = new GoogleTranslate();
    $tr->setSource();
    $indo = $res['indo'];
@endphp
<div align="center">
    <table cellspacing="0" cellpadding="0" bgcolor="#FFFFFF" border="0" style="padding:20px;"
    width="{{ $pageWidth }}">
        <tbody>
            <tr>
                <td>
                    <table width="100%" cellspacing="0" cellpadding="0" border="0">
                        <tbody>
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
                                </td>s
                            </tr>
                            <!-- <tr>
                                <td class="text-center" style="text-align: center">
                                    <span style="font-size: 16pt;font-weight: 600;letter-spacing: 0px;color:#000000">
                                        {!! strtoupper($profile->namalengkap) !!}

                                    </span>
                                </td>
                            </tr> -->
                            <tr>
                                <td class="text-center" style="text-align: center">
                                    <span style="font-size: 12pt;color:#000000">
                                        {!! $profile->alamatlengkap !!}<br>

                                        {{ $profile->fixedphone }}<br>

                                        Email : <a href="#"> {!! $profile->alamatemail !!} </a>
                                    </span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <hr class="baris1">
                    <hr class="baris2">
                </td>
                {{-- <td>
                </td> --}}
            </tr>
        </tbody>
    </table>
    <table cellspacing="0" cellpadding="0" bgcolor="#FFFFFF" border="0" style="padding:20px;"
    width="{{ $pageWidth }}">
        <tbody>
            <tr>
                <td>
                    <table width="100%" cellspacing="0" cellpadding="0" style="border-bottom:2px solid black;">
                        <tr>
                            <td class="text-center">
                                <span class="text-judul">
                                    {{ $indo ? 'RINCIAN BIAYA' : 'COST DETAIL' }}
                                </span>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td>
                    <table width="100%" cellspacing="0" cellpadding="0" class="tr-center">
                        <tr>
        
                            <td width="15%">
                                <span class="text-normal">
                                    {{ $indo ? 'No. Registrasi' : 'Registration Number' }}
                                </span>
                            </td>
                            <td width="35%">
                                <span class="text-normal">:
                                    {{ $res['identitas']->noregistrasi }}</span>
                            </td>
        
                            <td width="15%">
                                <span class="text-normal">Unit</span>
                            </td>
                            <td width="35%">
                                <span class="text-normal">:
                                    {{ $res['identitas']->namadepartemen }}</span>
                            </td>
                        </tr>
                        <tr>
        
                            <td width="15%">
                                <span class="text-normal">
                                    {{ $indo ? 'No. RM / Umur' : 'NMR / Age' }}
                                </span>
                            </td>
                            <td width="35%">
                                <span class="text-normal">: 
                                    {{ $res['identitas']->nocm }} / {{ $indo ? $res['identitas']->umur : $tr->translate($res['identitas']->umur)  }}
                                </span>
                            </td>
        
        
                            <td width="15%">
                                <span class="text-normal">
                                    {{ $indo ? "Ruang" : $tr->translate('Ruang')  }}
                                </span>
                            </td>
                            <td width="35%">
                                <span class="text-normal">:
                                    {{ $res['identitas']->namaruangan }}
                                </span>
                            </td>
                        </tr>
                        <tr>
        
                            <td width="15%">
                                <span class="text-normal">
                                    {{ $indo ? "Nama Pasien" : $tr->translate('Nama Pasien')  }}
                                </span>
                            </td>
                            <td width="35%">
                                <span class="text-normal">:
                                    {{ $res['identitas']->namapasien . ' (' . ($indo ? $res['identitas']->jeniskelamin : $tr->translate($res['identitas']->jeniskelamin)). ')' }}</span>
                            </td>
        
                            <td width="15%">
                                <span class="text-normal">
                                    {{ $indo ? 'Tgl Masuk' : 'Date of Entry'}}
                                </span>
                            </td>
                            <td width="35%">
                                <span class="text-normal">:
                                    {{ date_format(date_create($res['identitas']->tglregistrasi), 'd/m/Y') }}</span>
                            </td>
                        </tr>
                        <tr>
                            <td width="15%">
                                <span class="text-normal">
                                    {{ $indo ? 'DPJP' : $tr->translate('Dokter')}}
                                </span>
                            </td>
                            <td width="35%">
                                <span class="text-normal">:
                                    {{ $res['identitas']->namalengkap }}</span>
                            </td>
        
                            <td width="15%">
                                <span class="text-normal">{{ $indo ? 'Tgl Pulang' : 'Date of Discharge'}}</span>
                            </td>
                            <td width="35%">
                                <span class="text-normal">:
                                    {{ date_format(date_create($res['identitas']->tglpulang), 'd/m/Y') }}</span>
                            </td>
                        </tr>
                        <tr>
                            <td width="15%">
                                <span class="text-normal">No SEP</span>
                            </td>
                            <td width="35%">
                                <span class="text-normal">:
                                    {{ $res['identitas']->nosep }}</span>
                            </td>
        
        
        
                            <td width="15%">
                                <span class="text-normal">Type</span>
                            </td>
                            <td width="35%">
                                <span class="text-normal">:
                                    {{ $res['identitas']->kelompokpasien }}</span>
                            </td>
                        </tr>
                        <tr>
        
                            {{-- <td width="15%">
                                    <span class="text-normal">No SEP</span>
                                </td>
                                <td width="35%">
                                    <span class="text-normal">:
                                        {{ $res['identitas']->nosep }}</span>
                                </td> --}}
        
                            <td width="15%">
                                <span class="text-normal">
                                    {{ $indo ? 'No Kartu' : $tr->translate('Nomor Kartu')}}
                                </span>
                            </td>
                            <td width="35%">
                                <span class="text-normal">:
                                    {{ $res['identitas']->nobpjs }}</span>
                            </td>
        
                            <td width="15%">
                                <span class="text-normal">
                                    {{ $indo ? 'Penjamin' : $tr->translate('Penjamin')}}
                                </span>
                            </td>
                            <td width="35%">
                                <span class="text-normal">:
                                    {{ $res['identitas']->namarekanan }}</span>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>
    <table cellspacing="0" cellpadding="0" bgcolor="#FFFFFF" border="0" style="padding:20px;"
        width="{{ $pageWidth }}">
        <thead style="font-size: 12pt">
            <tr>
                <th class="th-class text-left">
                    <span class="text-normal-1">No</span>
                </th>
                {{-- <th class="th-class  text-center">
                    <span class="text-normal-1">Tanggal</span>
                </th> --}}
                <th class="th-class text-left">
                    <span class="text-normal-1">
                        {{ $indo ? 'Deskripsi' : $tr->translate('Deskripsi')}}
                    </span>
                </th>
                {{-- <th class="th-class text-left">
                    <span class="text-normal-1">Section</span>
                </th> --}}
                {{-- <th class="th-class text-left">
                    <span class="text-normal-1">Dokter</span>
                </th> --}}
                <th class="th-class  text-left" width="">
                    <span class="text-normal-1">
                        {{ $indo ? 'Tarif' : 'Cost' }}
                    </span>
                </th>
                <th class="th-class  text-left">
                    <span class="text-normal-1">Qty</span>
                </th>
                <th class="th-class  text-right">
                    <span class="text-normal-1">Sub Total</span>
                </th>
            </tr>
        </thead>
        <tbody style="font-size: 14pt;">
            @php
                $nomor = 1;
                $totaltagihan = 0;
                $totaldiskon = 0;
                $jumlahbill = 0;
                $totaldiklaim = 0;
            @endphp
            @foreach ($res['billing'] as $ruangan)
                <tr>
                    <td colspan="10">
                        <span class="text-normal-1 bold">
                            <b> {{ $indo ? strtoupper($ruangan[0]->namaruangan) : (($ruangan[0]->namaruanganenglish == 'Medicine' || $ruangan[0]->namaruanganenglish == 'Laboratory' || $ruangan[0]->namaruanganenglish == 'Radiology' || $ruangan[0]->namaruanganenglish == 'Polyclinic/IGD' || $ruangan[0]->namaruanganenglish == 'Treatment') ? strtoupper($ruangan[0]->namaruanganenglish) : strtoupper($tr->translate($ruangan[0]->namaruangan))) }}</b>
                        </span>
                    </td>

                </tr>
                @php
                    $total = 0;
                    $diskon = 0;
                @endphp
                @foreach ($ruangan as $item)
                    <tr>
                        <td class="text-top text-left">
                            <span class="text-normal-1">{{ $nomor }}</span>
                        </td>
                        {{-- <td class="text-top text-center">
                            <span class="text-normal-1">
                                {{ date_format(date_create($data->tglpelayanan), 'd/m/Y') }}</span>
                        </td> --}}
                        <td class="text-top text-left">
                            <span class="text-normal-1">
                                {{ $indo ? $item->namaproduk : $tr->translate($item->namaproduk) }}{{ $ruangan[0]->namaruangan == 'Sewa Kamar' ? ' '.$item->namakelaspp : '' }}
                            </span>
                        </td>
                        {{-- <td class="text-top text-left">
                            <span class="text-normal-1">
                                {{ $item->ruang_group }}
                            </span>
                        </td> --}}
                        <td class="text-top text-left">
                            <span class="text-normal-1">
                                {{ number_format($item->hargasatuan, 0, '.', ',') }}</span>
                        </td>
                        <td class="text-top text-left">
                            <span class="text-normal-1">x{{ $item->jumlah }}</span>
                        </td>
                        {{-- <td class="text-top text-center">
                            <span class="text-normal-1">{{ number_format($item->diskon, 0, '.', ',') }}</span>
                        </td>
                        <td class="text-top text-center">
                            <span class="text-normal-1">{{ number_format($item->jasa, 0, '.', ',') }}</span> --}}
                        </td>
                        <td class="text-top text-right">
                            <span class="text-normal-1"> {{ number_format($item->total, 0, '.', ',') }}</span>
                        </td>
                    </tr>
                    @php
                        $nomor = $nomor + 1;
                        $total = $total + $item->total;
                        $diskon = $diskon + $item->diskon;
                    @endphp
                @endforeach
                @php
                    $totaltagihan = $totaltagihan + $total;
                    $totaldiskon = $totaldiskon + $diskon;
                    $jumlahbill = $totaltagihan - $totaldiskon;
                @endphp
                <tr>
                    <td class="text-right" colspan="10" style="border-top: 1px solid black;">
                        <span class="text-normal-1">
                            {{ number_format($total, 0, '.', ',') }}
                        </span>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <table cellspacing="0" cellpadding="0" bgcolor="#FFFFFF" border="0" style="padding:20px;"
        width="{{ $pageWidth }}">
        <tbody>
            <tr>
                <td style="padding-top:10px">
                    <table width="100%" cellspacing="0" cellpadding="0" style="border-top:2px solid black;">
                        <tr>
                            <td width="50%"></td>
                            <td width="20%" class="text-left">
                                <span class="text-biasa">
                                    {{ $indo ? 'TOTAL TAGIHAN' : $tr->translate('TOTAL TAGIHAN')}}
                                </span>
                            </td>
                            <td class="text-center">
                                <span class="text-biasa">:</span>
                            </td>
                            <td class="text-right">
                                <span class="text-biasa">
                                    {{ number_format($res['total'], 0, '.', ',') }}</span>
                            </td>
                        </tr>
                        <tr>
                            <td width="50%"></td>
                            <td width="20%" class="text-left">
                                <span class="text-biasa">{{ $indo ? 'DEPOSIT - UANG MUKA' : 'Down Payment'}}</span>
                            </td>
                            <td class="text-center">
                                <span class="text-biasa">:</span>
                            </td>
                            <td class="text-right">
                                <span class="text-biasa bold">
                                    {{ number_format($res['deposit'], 0, '.', ',') }}</span>
                            </td>
                        </tr>
                        @if ($res['pengembalian'] > 0)
                            <tr>
                                <td width="50%"></td>
                                <td width="20%" class="text-left">
                                    <span class="text-biasa">
                                        {{ $indo ? 'PENGEMBALIAN DEPOSIT' : $tr->translate('PENGEMBALIAN DEPOSIT')}}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <span class="text-biasa">:</span>
                                </td>
                                <td class="text-right">
                                    <span class="text-biasa bold">
                                        {{ number_format($res['pengembalian'], 0, '.', ',') }}</span>
                                </td>
                            </tr>
                        @endif
                        {{-- <tr>
                                <td width="50%"></td>
                                <td width="20%" class="text-left">
                                    <span class="text-biasa">DISKON</span>
                                </td>
                                <td class="text-center">
                                    <span class="text-biasa">:</span>
                                </td>
                                <td class="text-right">
                                    <span class="text-biasa ">
                                        {{ number_format($totaldiskon, 0, '.', ',') }}</span>
                                </td>
                            </tr> --}}
                        <tr>
                            <td width="50%"></td>
                            <td width="20%" class="text-left">
                                <span class="text-biasa">
                                    {{ $indo ? 'TOTAL DIKLAIM' : $tr->translate('TOTAL DIKLAIM') }}
                                </span>
                            </td>
                            <td class="text-center">
                                <span class="text-biasa">:</span>
                            </td>
                            <td class="text-right">
                                <span class="text-biasa">
                                    {{ number_format($res['klaim'], 0, '.', ',') }}</span>
                            </td>
                        </tr>
                        <tr>
                            <td width="50%"></td>
                            <td width="20%" class="text-left">
                                <span class="text-biasa">
                                    {{ $indo ? 'DISKON' : $tr->translate('DISKON') }}
                                </span>
                            </td>
                            <td class="text-center">
                                <span class="text-biasa">:</span>
                            </td>
                            <td class="text-right">
                                <span class="text-biasa">
                                    {{ number_format($res['diskon'], 0, '.', ',') }}</span>
                            </td>
                        </tr>
                        <tr>
                            <td width="50%"></td>
                            <td width="20%" class="text-left">
                                <span class="text-biasa">
                                    {{ $indo ? 'TOTAL BAYAR' : $tr->translate('TOTAL BAYAR') }}
                                </span>
                            </td>
                            <td class="text-center">
                                <span class="text-biasa">:</span>
                            </td>
                            <td class="text-right">
                                <span class="text-biasa">
                                    {{ number_format($res['dibayar'], 0, '.', ',') }}</span>
                            </td>
                        </tr>
                        
                        
                        @if ($res['iurbayar'] != 0)
                        <tr>
                            <td width="50%"></td>
                            <td width="20%" class="text-left">
                                <span class="text-biasa">
                                    {{ $indo ? 'SISA HARUS BAYAR' : $tr->translate('SISA HARUS BAYAR') }}
                                </span>
                            </td>
                            <td class="text-center">
                                <span class="text-biasa">:</span>
                            </td>
                            <td class="text-right">
                                <span class="text-biasa bold">
                                    {{ number_format($res['sisa'], 0, '.', ',') }}</span>
                            </td>
                        </tr>
                            <tr>
                                <td width="50%"></td>
                                <td width="20%" class="text-left">
                                    <span class="text-biasa">
                                        {{ $indo ? 'IUR BAYAR' : $tr->translate('IUR BAYAR') }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <span class="text-biasa">:</span>
                                </td>
                                <td class="text-right">
                                    <span class="text-biasa bold">
                                        {{ number_format($res['iurbayar'], 0, '.', ',') }}</span>
                                </td>
                            </tr>
                        @endif
                    </table>
                </td>
            </tr>
            <tr>
                <td style="padding-top:20px">
                    <table width="100%" cellspacing="0" cellpadding="0">
                        <tr>
                            <td>
                                <span class="text-biasa">
                                    {{ $indo ? 'TERBILANG' : $tr->translate('TERBILANG') }}&nbsp;:
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span class="text-biasa"><i>#
                                        {{ $indo ? strtoupper(App\Traits\Valet::static_terbilang($res['total'])) : $tr->translate(strtoupper(App\Traits\Valet::static_terbilang($res['total']))) }} #</i>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            @if ($res['ismultipenjamin'] == true)
                <tr>
                    <td>
                        <span class="text-biasa">
                            {{ $indo ? 'Multi Penjamin' : $tr->translate('Multi Penjamin') }}&nbsp;:
                    </td>
                </tr>
                <tr>
                    <td style="padding-top:20px">
                        <table width="100%" cellspacing="0" cellpadding="0" border="1">
                            <tr class="garisatasbawah">
                                <th class="text-center"><span class="text-biasa">
                                    {{ $indo ? 'Penjamin' : $tr->translate('Penjamin') }}
                                </span>
                                </th>
                                <th class="text-center"><span class="text-biasa">
                                    {{ $indo ? 'Jumlah' : $tr->translate('Jumlah') }}
                                </span>
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
                                            class="text-biasa">{{ number_format($item->totalppenjamin, 0, '.', ',') }}</span>
                                    </td>
                                </tr>
                            @endforeach
                            <tr>
                                <td class="text-right">
                                    <span class="text">
                                        <b>{{ $indo ? 'JUMLAH' : $tr->translate('JUMLAH') }}</b>
                                    </span>
                                </td>
                                <td class="text-right">
                                    <span class="text">
                                        {{ number_format($total, 0, '.', ',') }}
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
                                    &nbsp;{{ date_format(date_create($res['identitas']->tglpulang), 'd/m/Y') }}</span>
                            </td>
                        </tr>
                        <tr>
                            <td width="50%" class="text-center"><span class="text-biasa">
                                {{ $indo ? 'Penanggung Biaya' : $tr->translate('Penanggung Biaya') }}
                            </span>
                            </td>
                            <td width="50%" class="text-center"><span class="text-biasa">
                                {{ $indo ? 'Kasir' : $tr->translate('Kasir') }}
                            </span>
                            </td>
                        </tr>
                        <tr>
                            <td height="80" valign="bottom" height="100" width="25%" class="text-center">
                                <span class="text-biasa">
                                    {{ str_replace('( Laki-laki )', '', str_replace('( Perempuan )', '', $res['identitas']->namapasien)) }}
                                </span>
                            </td>
                            <td height="80" valign="bottom" height="100"width="25%" class="text-center">
                                <span class="text-biasa">{{ $res['closer'] }}</span>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>
</div>

<script type='text/javascript'>
    window.print();
    window.onafterprint=()=>window.close();
</script>

</body>

</html>
