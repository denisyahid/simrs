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

    <style>
        :root {
            --font: Arial, Helvetica, sans-serif;
        }
    </style>
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
</head>

<body>
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
                                    </td>
                                    <td rowspan="5">
                                        <div style="width: 80px;"></div>
                                    </td>
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
                                <td class="text-center" style="text-align:center; justify-content: center; font-weight: bold">
                                    <span class="text-judul" style="text-align:center; justify-content: center; font-weight: bold">
                                        INVOICE
                                    </span>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td>
                        <table width="100%" cellspacing="0" cellpadding="0">
                            <thead>
                                <tr>
                    
                                    <td width="15%">
                                        <span class="text-normal">No. Registrasi</span>
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
                                        <span class="text-normal">No. RM / Umur</span>
                                    </td>
                                    <td width="35%">
                                        <span class="text-normal">: {{ $res['identitas']->nocm }} / {{ $res['identitas']->umur }}
                                        </span>
                                    </td>
                    
                    
                                    <td width="15%">
                                        <span class="text-normal">Ruang</span>
                                    </td>
                                    <td width="35%">
                                        <span class="text-normal">:
                                            {{ $res['identitas']->namaruangan }}</span>
                                    </td>
                                </tr>
                                <tr>
                    
                                    <td width="15%">
                                        <span class="text-normal">Nama Pasien</span>
                                    </td>
                                    <td width="35%">
                                        <span class="text-normal">:
                                            {{ $res['identitas']->namapasien . ' (' . $res['identitas']->jeniskelamin . ')' }}</span>
                                    </td>
                                    <td width="15%">
                                        <span class="text-normal">Tgl Masuk</span>
                                    </td>
                                    <td width="35%">
                                        <span class="text-normal">:
                                            {{ date_format(date_create($res['identitas']->tglregistrasi), 'd/m/Y') }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td width="15%">
                                        <span class="text-normal">DPJP</span>
                                    </td>
                                    <td width="35%">
                                        @if($res['identitas']->iddept != 16)
                                        <span class="text-normal">:
                                            {{ $res['identitas']->namalengkap }}</span>
                                        @else
                                        <span class="text-normal">:
                                            {{ $res['identitas']->dokterspesialis }}</span>
                                        @endif
                                    </td>
                                    <td width="15%">
                                        <span class="text-normal">Tgl Pulang</span>
                                    </td>
                                    <td width="35%">
                                        <span class="text-normal">:
                                            {{ date_format(date_create($res['identitas']->tglpulang), 'd/m/Y') }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td width="15%">
                                        <span class="text-normal">Tipe</span>
                                    </td>
                                    <td width="35%">
                                        <span class="text-normal">:
                                            {{ $res['identitas']->kelompokpasien }}</span>
                                    </td>
                                    
                                    <td width="15%">
                                        <span class="text-normal">Penjamin</span>
                                    </td>
                                    <td width="35%">
                                        <span class="text-normal">:
                                            {{ $res['identitas']->namarekanan }}</span>
                                    </td>
                                </tr>
                                {{-- <tr>
                    
                                    <td width="15%">
                                        <span class="text-normal">No Kartu</span>
                                    </td>
                                    <td width="35%">
                                        <span class="text-normal">:
                                            {{ $res['identitas']->nobpjs }}</span>
                                    </td>
                    
                                    <td width="15%">
                                        <span class="text-normal">Penjamin</span>
                                    </td>
                                    <td width="35%">
                                        <span class="text-normal">:
                                            {{ $res['identitas']->namarekanan }}</span>
                                    </td>
                                </tr> --}}
                            </thead>
                        </table>
                    </td>
                </tr>
            </tbody>
        </table>
        <table cellspacing="0" cellpadding="0" bgcolor="#FFFFFF" border="0" style="padding:20px; page-break-inside: always !important;"
            width="{{ $pageWidth }}">
            <thead style="font-size: 12pt;">
                <tr>
                    <th class="th-class text-left">
                        <span class="text-normal-1">No</span>
                    </th>
                    <th class="th-class text-left">
                        <span class="text-normal-1">Jenis Biaya</span>
                    </th>
                    <th class="th-class  text-center">
                        <span class="text-normal-1">Qty</span>
                    </th>
                    <th class="th-class  text-center">
                        <span class="text-normal-1">Tarif</span>
                    </th>
                    <th class="th-class  text-right">
                        <span class="text-normal-1">Sub Total</span>
                    </th>
                </tr>
            </thead>
            <tbody style="font-size: 12pt;">
                @php
                    $nomor = 0;
                    $totaltagihan = 0;
                    $totaldiskon = 0;
                    $jumlahbill = 0;
                    $totaldiklaim = 0;
                @endphp
                @foreach ($res['billing'] as $ruangan)
                    @php
                        $subtotals = 0;
                    @endphp
                        @foreach ($ruangan->groupBy('layanan_group') as $keyly => $lygroup)
                            @php
                                $total = 0;
                                $diskon = 0;
                            @endphp
                            <tr>
                                <td colspan="5">
                                    <span class="text-normal-1">
                                        <b> {{ $keyly }} </b>
                                    </span>
                                </td>
                            </tr>
                            @foreach ($lygroup as $data)
                                @php
                                    $total = $total + ($data->jumlah * $data->hargasatuan);
                                    $diskon = 0;
                                    $nomor = $nomor + 1;
                                    $subtotals = $subtotals + ($data->jumlah * $data->hargasatuan);
                                @endphp
                                <tr>
                                    <td class="text-top text-left">
                                        <span class="text-normal-1">{{ $nomor }}</span>
                                    </td>
                                    <td class="text-top text-left">
                                        <span class="text-normal-1">{{ $data->namaproduk }}</span>
                                     </td>
                                    
                                    <td class="text-top text-center">
                                        <span class="text-normal-1">x{{ $data->jumlah }}</span>
                                    </td>
                                    <td class="text-top text-right">
                                        <span class="text-normal-1">
                                            {{ number_format($data->hargasatuan, 0, '.', ',') }}</span>
                                    </td>
                                    <td class="text-top text-right">
                                        <span class="text-normal-1"> {{ number_format($data->jumlah*$data->hargasatuan, 0, '.', ',') }}</span>
                                    </td>
                                </tr>
                            @endforeach
                            <tr>
                                <td class="text-right" style="text-align: right; border-top: 1px solid black" colspan="5">
                                    <span class="text-normal-1">
                                        {{ number_format($total, 0, '.', ',') }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    @php
                        $totaltagihan = $totaltagihan + $total;
                        $totaldiskon = $totaldiskon + $diskon;
                        $jumlahbill = $totaltagihan - $totaldiskon;
                    @endphp
                    <tr>
                        <td class="text-right" style="text-align: right;" colspan="5">
                            <span class="text-normal-1">
                                Sub Total : {{ number_format($subtotals, 0, '.', ',') }}
                            </span>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <table cellspacing="0" cellpadding="0" bgcolor="#FFFFFF" border="0" style="padding:20px;"
        width="{{ $pageWidth }}">
            <tr>
                <td style="padding-top:10px">
                    <table width="100%" cellspacing="0" cellpadding="0" style="border-top:2px solid black;">
                        <tr>
                            <td width="50%"></td>
                            <td width="20%" class="text-left">
                                <span class="text-biasa">TOTAL TAGIHAN</span>
                            </td>
                            <td class="text-center">
                                <span class="text-biasa">:</span>
                            </td>
                            <td class="text-right">
                                <span class="text-biasa">
                                    {{ number_format($res['total'], 0, '.', ',') }}</span>
                            </td>
                        </tr>
                        {{-- <tr>
                            <td width="50%"></td>
                            <td width="20%" class="text-left">
                                <span class="text-biasa">DEPOSIT - UANG MUKA</span>
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
                                    <span class="text-biasa">PENGEMBALIAN DEPOSIT</span>
                                </td>
                                <td class="text-center">
                                    <span class="text-biasa">:</span>
                                </td>
                                <td class="text-right">
                                    <span class="text-biasa bold">
                                        {{ number_format($res['pengembalian'], 0, '.', ',') }}</span>
                                </td>
                            </tr>
                        @endif --}}
                        @if ($res['ismultipenjamin'] == true)
                            @foreach ($res['multipenjamin'] as $item)
                                @php
                                    $total = $total + $item->totalppenjamin;
                                @endphp
                                <tr>
                                    <td width="50%"></td>
                                    <td width="20%" class="text-left">
                                        <span class="text-biasa">{{ $item->namarekanan }}</span>
                                    </td>
                                    <td class="text-center">
                                        <span class="text-biasa">:</span>
                                    </td>
                                    <td class="text-right">
                                        <span class="text-biasa bold">
                                            {{ number_format($item->totalppenjamin, 0, '.', ',') }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
        
                        <tr>
                            <td width="50%"></td>
                            <td width="20%" class="text-left">
                                <span class="text-biasa">TOTAL BAYAR </span>
                            </td>
                            <td class="text-center">
                                <span class="text-biasa">:</span>
                            </td>
                            <td class="text-right">
                                <span class="text-biasa">
                                    {{ number_format($res['dibayar'], 0, '.', ',') }}</span>
                            </td>
                        </tr>
                        <tr>
                            <td width="50%"></td>
                            <td width="20%" class="text-left">
                                <span class="text-biasa">TOTAL DIKLAIM</span>
                            </td>
                            <td class="text-center">
                                <span class="text-biasa">:</span>
                            </td>
                            <td class="text-right">
                                <span class="text-biasa">
                                    {{ number_format($res['klaim'], 0, '.', ',') }}</span>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
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
                                        {{ strtoupper(App\Traits\Valet::static_terbilang($res['klaim'])) }} #</i>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td style="padding-top:20px">
                    <table width="100%" cellspacing="0" cellpadding="0">
                        <tr>
                            <td width="50%" class="text-center">
                                <span class="text-biasa" class="text-center"></span>
                            </td>
                            <td width="50%" class="text-center">
                                <span class="text-biasa">{!! $profile->namakota !!},
                                    &nbsp;&nbsp;{{ date('d/m/Y', strtotime($res['identitas']->tglpulang)) }}</span>
                            </td>
                        </tr>
                        <tr>
                            <td width="50%" class="text-center"><span class="text-biasa">Penanggung Biaya</span>
                            </td>
                            <td width="50%" class="text-center"><span class="text-biasa">Kasir</span>
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
                                <span class="text-biasa">
                                    {{ str_replace('( Laki-laki )', '', str_replace('( Perempuan )', '', $res['identitas']->namapasien)) }}
                                </span>
                            </td>
                            <td height="80" valign="bottom" height="100"width="25%" class="text-center">
                                <span class="text-biasa">{{ $data->pegawaiverif }}</span>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </div>
</body>

</html>
