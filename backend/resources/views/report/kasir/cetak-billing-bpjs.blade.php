<!DOCTYPE html>
<html>
@php
    $profile = App\Http\Controllers\Controller::static_profile();
@endphp
<head>
    <title>Invoice</title>
    <meta name="robots" content="noindex">
    <meta name="googlebot" content="noindex">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>

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
                /* font-family: Verdana, Geneva, Tahoma, sans-serif !important; */
                /* font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif !important; */
                font-family: Tahoma !important;
                font-size: 11px;
                font-weight: 200;
            }
        </style>
    @else
        @if (stripos(\Request::url(), 'localhost') !== false || stripos(\Request::url(), '127.0.0.1') !== false || stripos(\Request::url(), 'rsudbali.local') !== false)
            <link rel="stylesheet" href="{{ asset('css/paper.css') }} ">
            <link rel="stylesheet" href="{{ asset('css/table-v2.css') }}">
            {{-- <link rel="stylesheet" href="{{ asset('css/tabel.css') }}"> --}}
            <link rel="stylesheet" href="{{ asset('css/style.css') }}">
        @else 
            <link rel="stylesheet" href="{{ asset('service/css/paper.css') }} ">
            <link rel="stylesheet" href="{{ asset('service/css/table-v2.css') }}">
            {{-- <link rel="stylesheet" href="{{ asset('css/tabel.css') }}"> --}}
            <link rel="stylesheet" href="{{ asset('service/css/style.css') }}">
        @endif
    @endif


</head>
<style type="text/css" media="print">
    @media print {
        @page {
            size: 215 mm 140 mm landscape;
            margin: 0;
            /* size: portrait; */
            margin-bottom: 30px;
            margin-top: 30px;
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
        /* font-family: Verdana, Geneva, Tahoma, sans-serif !important; */
        /* font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif !important; */
        font-family: Tahoma !important;
        font-size: 11px;
        font-weight: 200;
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
        font-size: 12pt;
    }

    .text-biasa{
        font-size: 12pt;
    }

    .text-normal{
        font-size: 12pt;
    }



    .garis6 td {
        padding: 3px !important;
    }

</style>

@if ((isset($res['pdf']) && $res['pdf']) || (isset($res['storage']) && $res['storage']))

    <body style="margin: 0">
    @else
        @if (isset($print) && $print == true)

            <body style="background-color: #CCCCCC;margin: 0" onload="window.print()">
            @else

                <body style="background-color: #CCCCCC;margin: 0">
        @endif
@endif

<body onload="window.print()">
@php
    $tr = new GoogleTranslate();
    $tr->setSource();
    $indo = $res['indo'];
@endphp
    <div align="center">
        @if ((isset($res['pdf']) && $res['pdf']) || (isset($res['storage']) && $res['storage']))
            <table cellspacing="0" cellpadding="0" bgcolor="#FFFFFF" border="0" style="margin-left: 0px; padding-left: 5px; margin-top:12px; padding-top:25px">
        @else
            <table cellspacing="0" cellpadding="0" bgcolor="#FFFFFF" border="0" style="margin-left: 0px; padding-left: 5px; margin-top:12px; padding-top:25px"
                width="{{ $pageWidth }}">
        @endif
        <thead>
            <tr>
                <td>
                    <table width="50%" cellspacing="0" cellpadding="0" border="0">
                        <tr>
                            <td class="text-center" style="text-align: center">
                                <span style="font-size: 13pt;font-weight: 600;letter-spacing: 0px;color:#000000">
                                    {!! $indo ? strtoupper($profile->namalengkap) : "BALI MANDARA HOSPITAL" !!}
    
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-center" style="text-align: center">
                                <span style="font-size: 11pt;color:#000000">
                                    <b>{!! $profile->alamatlengkap !!}</b>
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-center" style="text-align: center">
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
                                    <b>INVOICE NO. {{$res['identitas']->nostruk}}</b><br> ({{$res['antrian'] ?? '-'}})
                                </span>
                            </div>
                        </td>
                    </tr>
                </td>
            </tr>
            <tr>
                <td>
                    <table width="50%" cellspacing="0" cellpadding="0" style=" border: 1px solid black; border-collapse: collapse;">
    
                        <tr>
                            <td width="25%">
                                <span class="text-normal">No. / {{ $indo ? 'Tgl' : 'Date' }}. Reg</span>
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
                                <span class="text-normal">{{ $indo ? 'NRM' : 'NMR' }}</span>
                            </td>
                            <td width="40%" align="left" colspan="2">
                                <span class="text-normal">: {{$res['identitas']->nocm}}</span>
                            </td>
                        </tr>
                        <tr>
                            <td width="25%">
                                <span class="text-normal">{{ $indo ? 'Nama' : 'Name' }}</span>
                            </td>
                            <td width="40%" align="left" colspan="2">
                                <span class="text-normal">: {{$res['identitas']->namapasien}}</span>
                            </td>
                        </tr>
                        <tr>
                            <td width="25%">
                                <span class="text-normal">{{ $indo ? 'Alamat' : 'Address' }}</span>
                            </td>
                            <td width="40%" align="left" colspan="2">
                                <span class="text-normal">: {{$res['identitas']->alamatlengkap}}</span>
                            </td>
                        </tr>
                        <tr>
                            <td width="25%">
                                <span class="text-normal">{{ $indo ? 'Ruangan' : 'Room' }}</span>
                            </td>
                            <td width="40%" align="left">
                                <span class="text-normal">: {{$res['identitas']->namaruangan}}</span>
                            </td>
                            <td width="35%" align="right">
                                <span class="text-normal"><b>{{ $indo ? $res['identitas']->umur : $tr->translate($res['identitas']->umur) }}</b></span>
                            </td>
                        </tr>
                        <tr>
                            <td width="25%">
                                <span class="text-normal">{{ $indo ? 'Dokter' : $tr->translate('Dokter') }}</span>
                            </td>
                            <td width="40%" align="left" colspan="2">
                                <span class="text-normal">: {{$res['identitas']->namalengkap}}</span>
                            </td>
                        </tr>
                        {{-- {{dd($res['identitas'])}} --}}
                        <tr>
                            <td width="25%">
                                <span class="text-normal">Company</span>
                            </td>
                            <td width="40%" align="left">
                                {{-- @if(str_contains($res['identitas']->kelompokpasien, 'BPJS') == true) --}}
                                {{-- @if($res['identitas']->kelompokpasien == 'BPJS')
                                <span class="text-normal">: BPJS Kesehatan</span>
                                @else
                                <span class="text-normal">: {{$res['identitas']->namarekanan}}</span>
                                @endif --}}
                                <span class="text-normal">: {{$res['identitas']->namarekanan}}</span>
                            </td>
                            <td width="35%" align="right">
                                <span class="text-normal">/ {{$res['identitas']->nobpjs}}</span>
                            </td>
                        </tr>
                        <tr>
                            <td width="25%">
                                <span class="text-normal">{{ $indo ? 'Penanggung' : $tr->translate('Penanggung') }}</span>
                            </td>
                            <td width="40%" align="left" colspan="2">
                                <span class="text-normal">: {{$res['identitas']->namapasien}}</span>
                            </td>
                        </tr>
                        <tr>
                            <td width="25%">
                                <span class="text-normal"><b>{{ $indo ? 'Nomor' : $tr->translate('Nomor') }} VA</b></span>
                            </td>
                            <td width="40%" align="left" colspan="2">
                                <span class="text-normal">: </span>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>
                    <table width="50%" cellspacing="0" cellpadding="0">
                        <tr>
                            <td>
                                <span class="text-judul" style="font-size: 11pt;">{{ $indo ? 'RINCIAN BIAYA' : $tr->translate('RINCIAN BIAYA') }}</span>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td style="padding-top:10px">
                    <table width="50%" cellspacing="0" cellpadding="1" border="0">
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
                                    <span class="text-normal bold">
                                        <b><u>
                                            {{ $indo ? strtoupper($ruangan[0]->ruang_group) : $tr->translate(strtoupper($ruangan[0]->ruang_group)) }}
                                        </u></b>
                                    </span>
                                </td>

                            </tr>
                            @foreach ($ruangan->groupBy('detailjenisproduk') as $item)
                                <tr  style="font-style:italic">
                                    <td colspan="9">
                                        <span class="text-normal">
                                            <b>
                                                {{ $indo ? ucwords($item[0]->detailjenisproduk) : $tr->translate(ucwords($item[0]->detailjenisproduk)) }}
                                            </b>
                                        </span>
                                    </td>
                                </tr>
                                @php
                                    $total = 0;
                                    $diskon = 0;
                                @endphp
                                @foreach ($item as $data)
                                    <tr>
                                        <td class="text-top text-left">
                                            <span class="text">
                                                {{ $indo ? $data->namaproduk : $tr->translate($data->namaproduk) }}
                                            </span>
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
                    <table width="50%" cellspacing="0" cellpadding="0" style="border-top:2px solid black;">
                        <tr>
                            <td width="50%">
                                <span class="text-biasa"><b>{{ $indo ? 'Terbilang' : $tr->translate('Terbilang') }}</b> : <br> # {{ $indo ? ucwords(App\Traits\Valet::static_terbilang($jumlahbill)) : $tr->translate(ucwords(App\Traits\Valet::static_terbilang($jumlahbill))) }} #</span>

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
                                    @if($res['identitas']->nostruk != null)
                                    <b>{{ number_format( $res['total'] - $res['identitas']->totaldiskon ) }}</b>
                                    @else
                                    <b>0</b>
                                    @endif
                                </span>
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
                <table width="50%" cellspacing="0" cellpadding="0">
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
                    <span class="text-biasa">{{ $indo ? 'Multi Penjamin' : $tr->translate('Multi Penjamin') }} :
                </td>
            </tr>
            <tr>
                <td style="padding-top:20px">
                    <table width="50%" cellspacing="0" cellpadding="0" border="1">
                        <tr class="garisatasbawah">
                            <th class="text-center"><span class="text-biasa">
                                {{ $indo ? 'Penjamin' : $tr->translate('Penjamin') }}
                            </span>
                            </th>
                            <th class="text-center"><span class="text-biasa">{{ $indo ? 'Jumlah' : $tr->translate('Jumlah') }}</span>
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
                                    <b>{{ $indo ? 'JUMLAH' : $tr->translate('JUMLAH') }}</b>
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
                    <table width="50%" cellspacing="0" cellpadding="0">
                        <tr>
                            <td width="50%" class="text-center">
                                <span class="text-biasa" class="text-center"></span>
                            </td>
                            <td width="50%" class="text-center">
                                <span class="text-biasa">{!! $profile->namakota !!},
                                    {{-- &nbsp;&nbsp;{{ date('j-F-Y') }}  --}}
                                    &nbsp;&nbsp;{{ date('j-F-Y', strtotime($res['identitas']->tglpulang ?? '')) }}
                                </span>
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
                                <span class="text-biasa">Pasien</span>
                            </td>
                            <td valign="bottom"width="25%" class="text-center">
                                <span class="text-biasa">Kasir</span>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            {{-- <tr>
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
            </tr> --}}

        </tbody>
        </table>
    </div>
</body>
<script type='text/javascript'>
    window.print();
    window.onafterprint=()=>window.close();
</script>

<script type="text/javascript">
    window.onload = function() {
        window.print();
    };
</script>

</html>
