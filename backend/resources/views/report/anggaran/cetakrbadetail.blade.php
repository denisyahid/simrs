<!DOCTYPE html>
<html lang="en">

<head>
    <title>
        Report
    </title>
    @if (stripos(\Request::url(), 'localhost') !== false || stripos(\Request::url(), 'transmedika') !== false)
        <link rel="stylesheet" href="{{ asset('css/paper.css') }} ">
        <link rel="stylesheet" href="{{ asset('css/table-v2.css') }}">
        <link rel="stylesheet" href="{{ asset('css/tabel.css') }}">
        {{-- <link  rel="stylesheet" href="{{ asset('css/style.css') }}"> --}}
    @else
        <link rel="stylesheet" href="{{ asset('service/css/paper.css') }} ">
        <link rel="stylesheet" href="{{ asset('service/css/table-v2.css') }}">
        <link rel="stylesheet" href="{{ asset('service/css/tabel.css') }}">
        {{-- <link  rel="stylesheet" href="{{ asset('service/css/style.css') }}"> --}}
    @endif
</head>
<style type="text/css" media="print">
    @media print {
        /* @page {
            size: auto;
            margin: 0;
            size: portrait;
        } */
        @page {
            margin: 10mm 5mm 35mm 17mm;
            /* top right bottom left */
            size: auto;
        }

        @page :first {
            margin: 10mm 5mm 35mm 17mm;
            size: auto;
        }

        footer {
            display: none
        }

        header {
            display: none
        }

        body {
            -webkit-print-color-adjust: exact !important;
        }
    }
</style>
<style>
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

    body {
        font-family: Tahoma, Geneva, sans-serif;
    }

    .ontop {
        vertical-align: top;
    }

    @page {
        size: A4
    }

    .garis6 td {
        padding: 3px !important;
    }
    table tr td{
        page-break-inside: avoid;
    }
</style>
{{-- onLoad="window.print()" --}}
@php
    //$data = $dataReport['identitas'];
    //dd($data);
@endphp

<body style="background-color: #CCCCCC;margin: 0" onLoad="window.print()">
    <div style="text-align:center">
        <table class="bayangprint" style="width:{{ $pageWidth }};background-color:#FFFFFF;padding:25px;" border="0">
            <caption style="display: none"></caption>
            <tbody>
                <tr style="display: none"><th scope="col"></th></tr>
                <tr>
                    <td>
                        <table style="width:100%; border-collapse: collapse;" border="1">
                            <caption style="display: none"></caption>
                            <tr style="display: none"><th scope="col"></th></tr>
                            <tr>
                                <td style="width: 15%;">
                                    <p style="text-align:center">
                                        @if (stripos(\Request::url(), 'localhost') !== false
                                        || stripos(\Request::url(), 'transmedika') !== false)
                                            <img alt="" src="{{ asset('img/logo-rs.png') }}" border="0"
                                                style="width: 30%;" alt="">
                                        @else
                                            <img alt="" src="{{ asset('img/logo-rs.png') }}" border="0"
                                                style="width: 30%;" alt="">
                                        @endif
                                    </p>
                                </td>
                                <td style="text-align: center;width: 65%;">
                                    <span style="font-size: 15pt;font-weight: 600;letter-spacing: 4px;" color="#000000">
                                        RENCANA BISNIS DAN ANGGARAN<br>
                                        BADAN LAYANAN UMUM DAERAH<br>
                                        {!! strtoupper($settingrba->pengelolakeuanganblud) !!}
                                    </span>
                                </td>
                                <td align="center" style="width: 60%;">
                                    <b style="font-size: 20px;">
                                        RBA
                                    </b><br>
                                    {{$head->tahap}}
                                </td>
                            </tr>
                            <tr>
                                <td colspan="3" align="center" style="width: 100%; font-weight: bold">
                                    REKAP ANGGARAN TIAP SUB KEGIATAN (JENIS ANGGARAN) <br>
                                        TAHUN ANGGARAN {{$head->tahun}}
                                </td>
                            </tr>
                            <tr>
                                <td colspan="3" align="center" style="width: 100%;">
                                    <table width="100%">
                                        <tr>
                                            <td>Program</td>
                                            <td>:</td>
                                            <td>{{$head->program}}</td>
                                        </tr>
                                        <tr>
                                            <td>Kegiatan</td>
                                            <td>:</td>
                                            <td>{{$head->kegiatan}}</td>
                                        </tr>
                                        <tr>
                                            <td>Sub Kegiatan</td>
                                            <td>:</td>
                                            <td>{{$head->subkegiatan}}</td>
                                        </tr>
                                        <tr>
                                            <td>Sub Sub Kegiatan</td>
                                            <td>:</td>
                                            <td>{{$head->subsubkegiatan}}</td>
                                        </tr>
                                        <tr>
                                            <td>Waktu Pelaksanaan</td>
                                            <td>:</td>
                                            <td>{{$head->tahun}}</td>
                                        </tr>
                                        <tr>
                                            <td>Lokasi Kegiatan</td>
                                            <td>:</td>
                                            <td>{{$settingrba->pengelolakeuanganblud}}</td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="3" style="width: 100%;border-collapse: collapse; padding:0" >
                                    <table width="100%" style="border-collapse: collapse;page-break-inside: avoid;">
                                        <tr>
                                            <th style="border-right: 1px solid;border-bottom: 1px solid">Indikator</th>
                                            <th style="border-right: 1px solid;border-bottom: 1px solid">Tolok Ukur</th>
                                            <th style="border-bottom: 1px solid">Target Kinerja</th>
                                        </tr>
                                        <tr align="left">
                                            <td style="border-right: 1px solid;border-bottom: 1px solid">Masukan</td>
                                            <td style="white-space: pre-line;border-right: 1px solid;border-bottom: 1px solid">{{$head->indikatormasuk}}</td>
                                            <td style="white-space: pre-line;border-bottom: 1px solid">{{$head->targetmasuk}}</td>
                                        </tr>
                                        <tr align="left">
                                            <td style="border-right: 1px solid;border-bottom: 1px solid">Keluaran</td>
                                            <td style="white-space: pre-line;border-right: 1px solid;border-bottom: 1px solid">{{$head->indikatorkeluaran}}</td>
                                            <td style="white-space: pre-line;border-bottom: 1px solid">{{$head->targetkeluaran}}</td>
                                        </tr>
                                        <tr align="left">
                                            <td style="border-right: 1px solid">Hasil</td>
                                            <td style="white-space: pre-line;border-right: 1px solid;" >{{$head->indikatorhasil}}</td>
                                            <td style="white-space: pre-line;">{{$head->targethasil}}</td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                            {{-- <tr>
                                <td colspan="3">
                                    
                                </td>
                            </tr> --}}
                        </table>
                        <table width="100%" border="1" style="border-collapse: collapse; padding: 10px;">
                            <tr>
                                <th rowspan="2" colspan="4">No Rekening</th>
                                <th rowspan="2">Komponen Biaya</th>
                                <th colspan="3">RINCIAN BIAYA</th>
                                <th colspan="{{count($asalproduk)}}">JUMLAH ANGGARAN (SUMBER DANA)</th>
                            </tr>
                            <tr>
                                <th>Jumlah</th>
                                <th>Satuan</th>
                                <th>Harga Satuan</th>
                                <th>Danais</th>
                                <th>SiLPA</th>
                                <th>APBD</th>
                                <th>Jasa Layanan</th>
                                <th>DAK</th>
                            </tr>
                            @php
                                $subdiv1 = 0;
                                $subdiv2 = 0;
                                $subdiv3 = 0;
                            @endphp
                            @foreach ($all as $item)
                                <tr style="font-weight: {{$item["div"] ==1 || $item["div"] ==2 || $item["div"] ==3 || ($item["div"] ==4 &&  !isset($item["rownum"]))   ? "bold" : "none" }}">
                                    <td>{{$item['kode1']}}</td>
                                    <td>{{$item['kode2']}}</td>
                                    <td>{{$item['kode3']}}</td>
                                    <td>{{isset($item['rownum']) ?  '' : $item['kode4']}}</td>
                                    <td align="left">{{isset($item['rownum'])? $item['keteranganbelanja'] : $item['namamataanggaran'] }}</td>
                                    <td>{{isset($item['rownum']) ? $item['jml'] : ''}}</td>
                                    <td>{{isset($item['rownum']) ? $item['satuan'] : ''}}</td>
                                    <td align="right">{{$item['hargasatuan'] =='' || !isset($item['rownum']) ? '' : number_format((float)$item['hargasatuan'],0,",",".")}}</td>
                                    <td align="right">
                                        @if ($item['id_ap'] == 15 && $item['div'] == 4 && isset($item['rownum']))
                                            {{number_format((float)$item['subtotal'],0,",",".")}}
                                        @endif
                                    </td>
                                    <td align="right">
                                        @if ($item['id_ap'] == 16 && $item['div'] == 4 && isset($item['rownum']))
                                            {{number_format((float)$item['subtotal'],0,",",".")}}
                                        @endif
                                    </td>
                                    <td align="right">
                                        @if ($item['id_ap'] == 17 && $item['div'] == 4 && isset($item['rownum']))
                                            {{number_format((float)$item['subtotal'],0,",",".")}}
                                        @endif
                                    </td>
                                    <td align="right">
                                    @if ($item['id_ap'] == 18 && $item['div'] == 4 && isset($item['rownum']))
                                        {{number_format((float)$item['subtotal'],0,",",".")}}
                                    @endif
                                    </td>
                                    <td align="right"> @if ($item['id_ap'] == 19 && $item['div'] == 4 && isset($item['rownum']))
                                        {{number_format((float)$item['subtotal'],0,",",".")}}
                                    @endif</td>
                                </tr>
                            @endforeach
                        </table>
                        <table cellpadding="0" cellspacing="0" width="100%"  border="0">
                            <thead>
                                <tr>
                                    <td width="400">
                                    </td>
                                    <td>
                                    </td>
                                    <td>
                                        <p align="center" style="margin-top: 50px;">
                                            <font style="font-size: 12pt; " color="#000000" face="Arial">
                                                    Bandung, {{ $tanggalcetak }}
                                            </font>
                                        </p>
                                    </td>
                                </tr>
                                <tr>
                                    <td width="400">
                                    </td>
                                    <td>
                                    </td>
                                    <td>
                                        <p align="center" style="margin-bottom:50px">
                                            <font style="font-size: 12pt; " color="#000000" face="Arial">
                                                    Direktur
                                            </font>
                                        </p>
                                    </td>
                                </tr>
                                <tr></tr>
                                <tr></tr>
                                <tr></tr>
                                <tr></tr>
                                <tr>
                                    <td width="400">
                                    </td>
                                    <td>
                                    </td>
                                    <td>
                                        <p align="center" style="margin-top: 50px;">
                                            <font style="font-size: 12pt; " color="#000000" face="Arial">
                                                    <u>{{$settingrba->namalengkap}}</u>
                                            </font>
                                        </p>
                                    </td>
                                </tr>
                                <tr>
                                    <td width="400">
                                    </td>
                                    <td>
                                    </td>
                                    <td>
                                        <p align="center">
                                            <font style="font-size: 12pt; " color="#000000" face="Arial">
                                            NIP. {{$settingrba->nippns}}
                                            </font>
                                        </p>
                                    </td>
                                </tr>
                            </thead>
                        </table>
                    </td>
                </tr>
               
            </tbody>
        </table>
    </div>
</body>
<script>
    document.addEventListener('contextmenu', function(e) {
        e.preventDefault();
    });
</script>

</html>
