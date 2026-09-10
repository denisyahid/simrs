<!DOCTYPE html>
<html lang="en">

<head>
    <title>
        Surat Keterangan Sakit
    </title>
    @if ((isset($res['pdf']) && $res['pdf']) || (isset($res['storage']) && $res['storage']))
        <link rel="stylesheet" href="css/report/paper.css">
        <link rel="stylesheet" href="css/report/table.css">
        <link rel="stylesheet" href="css/report/tabel.css">
    @else
        <link rel="stylesheet" href="{{ asset('css/report/paper.css') }} ">
        <link rel="stylesheet" href="{{ asset('css/report/table.css') }}">
        <link rel="stylesheet" href="{{ asset('css/report/tabel.css') }}">
    @endif
</head>
<style type="text/css" media="print">
    @media print {
        @page {
            size: auto;
            margin: 0;
            /* size: portrait; */
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
</style>
{{-- onLoad="window.print()" --}}
@php
    //$data = $dataReport['identitas'];
    //dd($data);
@endphp

<body style="background-color: #CCCCCC;margin: 0" onLoad="window.print()">
    <div style="text-align:center">
        <table class="bayangprint" style="width:{{ $pageWidth }};background-color:#FFFFFF;padding:25px; border:0">
            <caption style="display: none"></caption>
            <tbody>
                <tr style="display: none">
                    <th scope="col"></th>
                </tr>
                <tr>
                    <td>
                        <table style="width:100%;border:0px">
                            <caption style="display: none"></caption>
                            <tr style="display: none">
                                <th scope="col"></th>
                            </tr>
                            <tr>
                                <td rowspan="5">
                                    <p style="text-align:right">
                                        @if ((isset($res['pdf']) && $res['pdf']) || (isset($res['storage']) && $res['storage']))
                                            <img src="{{ 'img/logo-rs.png' }}" width="80px" border="0">
                                        @else
                                            <img src="{{ asset('img/logo-rs.png') }}" width="80px" border="0">
                                        @endif
                                    </p>
                                </td>
                                <td style=" text-align: center">
                                    <span style="font-size: 14pt;font-weight: 600;letter-spacing: 4px;" color="#000000">
                                        {!! strtoupper($profile->namapemerintahan) !!}
                                    </span>
                                </td>
                                <td rowspan="5">
                                    <div style="width: 80px;">
                                </td>
                            </tr>
                            <tr>
                                <td style=" text-align: center">
                                    <span style="font-size: 16pt;font-weight: 600;letter-spacing: 2px;" color="#000000">
                                        {!! strtoupper($profile->namalengkap) !!}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td style=" text-align: center">
                                    <span style="font-size: 12pt;" color="#000000">
                                        {!! $profile->alamatlengkap !!}
                                        Telp. {{ $profile->fixedphone }} <br> Fax. {{ $profile->faksimile }} Whatsapp.
                                        <span> {!! $profile->whatsapp !!} </span>
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td style=" text-align: center">
                                    <span style="font-size: 12pt;" color="#000000">
                                        {{-- {!! $profile->alamatemail !!} --}}
                                        Email : <a>{{ $profile->alamatemail }}</a>
                                        Website : <a href="https://{{ $profile->website }}" target="_blank">
                                            {!! $profile->website !!} </a>

                                    </span>
                                </td>
                            </tr>
                        </table>
                        <hr class="baris1">
                        <hr class="baris2">
                    </td>
                </tr>
                <tr>
                    <td style="padding-top:10px">
                        <table style="width:100%;border:0px">
                            <caption style="display: none"></caption>
                            <tr style="display: none">
                                <th scope="col"></th>
                            </tr>
                            <tr>
                                <td style=" text-align: center">
                                    <span style="font-size: 14pt;font-weight: 600;text-decoration: underline;"
                                        color="#000000">
                                        SURAT KETERANGAN SAKIT
                                    </span>
                                    <br>
                                    <span style="font-size: 12pt;font-weight: 600;" color="#000000">
                                        {{ $dataReport['identitas']->nosurat }}
                                    </span>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td style="padding-top:10px">
                        <table style="width:90%; border:0;">
                            <caption style="display: none"></caption>
                            <tr style="display: none">
                                <th scope="col"></th>
                            </tr>
                            <tr>
                                <td colspan="3" style="width:100%;">
                                    <span style="font-size: 11pt;" color="#000000">
                                        Yang bertanda tangan dibawah ini, menerangkan bahwa:
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td style="width:100%; height:20px" colspan="3"></td>
                            </tr>
                            <tr>
                                <td style="width:25%;text-align:left"><span style="font-size: 11pt;" color="#000000">Nama</span></td>
                                <td style="width: 1%"><span style="font-size: 11pt;" color="#000000">:</span></td>
                                <td style="width:74%;text-align:left"><span style="font-size: 11pt;"
                                        color="#000000">{{ $dataReport['identitas']->namapasien }}</span></td>
                            </tr>
                            <tr>
                                <td style="width:25%;text-align:left"><span style="font-size: 11pt;" color="#000000">No. Rekam
                                        Medis</span></td>
                                <td style="width: 1%"><span style="font-size: 11pt;" color="#000000">:</span></td>
                                <td style="width:74%;text-align:left"><span style="font-size: 11pt;"
                                        color="#000000">{{ $dataReport['identitas']->nocm }}</span></td>
                            </tr>
                            <tr>
                                <td style="width:25%;text-align:left"><span style="font-size: 11pt;" color="#000000">Tempat / Tanggal
                                        Lahir</span></td>
                                <td style="width: 1%"><span style="font-size: 11pt;" color="#000000">:</span></td>
                                <td style="width:74%;text-align:left"><span style="font-size: 11pt;"
                                        color="#000000">{{ $dataReport['identitas']->tempatlahir .
                                            ' / ' .
                                            App\Traits\Valet::getDateIndo($dataReport['identitas']->tgllahir) }}</span>
                                </td>
                            </tr>
                            <tr>
                                <td style="width:25%;text-align:left"><span style="font-size: 11pt;" color="#000000">Jenis
                                        Kelamin</span></td>
                                <td style="width: 1%"><span style="font-size: 11pt;" color="#000000">:</span></td>
                                <td style="width:74%;text-align:left"><span style="font-size: 11pt;"
                                        color="#000000">{{ $dataReport['identitas']->jeniskelamin }}</span></td>
                            </tr>
                            <tr>
                                @php
                                    $umur = App\Http\Controllers\Report\ReportCtrl::getUmurna($dataReport['identitas']->tgllahir, $dataReport['identitas']->tglregistrasi);
                                @endphp
                                <td style="width:25%;text-align:left"><span style="font-size: 11pt;" color="#000000">Umur</span></td>
                                <td style="width: 1%"><span style="font-size: 11pt;" color="#000000">:</span></td>
                                <td style="width:74%;text-align:left"><span style="font-size: 11pt;"
                                        color="#000000">{{ $umur['umurtahun'] . ' ' . $umur['umurbulan'] }}</span>
                                </td>
                            </tr>
                            <tr>
                                <td style="width:25%;text-align:left"><span style="font-size: 11pt;" color="#000000">Pekerjaan</span>
                                </td>
                                <td style="width: 1%"><span style="font-size: 11pt;" color="#000000">:</span></td>
                                <td style="width:74%;text-align:left"><span style="font-size: 11pt;"
                                        color="#000000">{{ $dataReport['identitas']->pekerjaan ?  $dataReport['identitas']->pekerjaan  : '-'}}</span></td>
                            </tr>
                            <tr>
                                <td class="ontop" style="width:25%;text-align:left"><span style="font-size: 11pt;"
                                        color="#000000">Alamat</span></td>
                                <td class="ontop" style="width:1%"><span style="font-size: 11pt;"
                                        color="#000000">:</span></td>
                                <td style="width:74%;text-align:left"><span style="font-size: 11pt;"
                                        color="#000000">{{ $dataReport['identitas']->alamatlengkap }}</span></td>
                            </tr>
                            <tr>
                                <td style="width:100%; height:20px" colspan="3"></td>
                            </tr>
                            <tr>
                                <td colspan="3" style="width:100%;text-align:left">
                                    <span style="font-size: 11pt;" color="#000000">
                                        Telah berobat di {!! $profile->namalengkap !!} pada tanggal
                                        {{ App\Traits\Valet::getDateIndo($dataReport['identitas']->tglregistrasi) }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="3" style="width:100%;text-align:left">
                                    <span style="font-size: 11pt;" color="#000000">
                                        Karena sakitnya, yang bersangkutan diharapkan
                                        {{ $dataReport['identitas']->hasilpemeriksaan }} dari tanggal
                                        {{ App\Traits\Valet::getDateIndo($dataReport['identitas']->tglawal) }} sampai
                                        {{ App\Traits\Valet::getDateIndo($dataReport['identitas']->tglakhir) }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td class="ontop" style="width:25%;text-align:left"><span style="font-size: 11pt;"
                                        color="#000000">Catatan</span></td>
                                <td class="ontop" style="width:1%"><span style="font-size: 11pt;"
                                        color="#000000">:</span></td>
                                <td style="width:74%;text-align:left"><span style="font-size: 11pt;"
                                        color="#000000">{{ $dataReport['identitas']->keterangan }}</span></td>
                            </tr>
                            <tr>
                                <td class="ontop" style="width:25%;text-align:left"><span style="font-size: 11pt;"
                                        color="#000000">Diagnosa</span></td>
                                <td class="ontop" style="width:1%"><span style="font-size: 11pt;"
                                        color="#000000">:</span></td>
                                <td style="width:74%;text-align:left"><span style="font-size: 11pt;"
                                        color="#000000">{{ $dataReport['identitas']->diagnosa }}</span></td>
                            </tr>
                            <tr>
                                <td class="ontop" style="width:25%;text-align:left"><span style="font-size: 11pt;"
                                        color="#000000">Indikasi Kembali ke RS</span></td>
                                <td class="ontop" style="width:1%"><span style="font-size: 11pt;"
                                        color="#000000">:</span></td>
                                <td style="width:74%;text-align:left"><span style="font-size: 11pt;"
                                        color="#000000">{{ $dataReport['identitas']->indikasi }}</span></td>
                            </tr>
                            <tr>
                                <td style="width:100%; height:20px" colspan="3">

                                </td>
                            </tr>
                            <tr>
                                <td colspan="3" style="width:100%;">
                                    <span style="font-size: 11pt;" color="#000000">
                                        Demikian saya sampaikan, untuk dapat dipergunakan seperlunya.
                                    </span>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td style="padding-top:25px">
                        <table style="width:90%; border:0; text-align:center">
                            <caption style="display: none"></caption>
                            <tr style="display: none">
                                <th scope="col"></th>
                            </tr>
                            <tr>
                                <td style="width:50%"></td>
                                <td style="width:50%; text-align:center">
                                    <span style="font-size: 11pt;" color="#000000">{!! $profile->namakota !!},
                                        {{ App\Traits\Valet::getDateIndo(date('Y-m-d')) }}</span>
                                    <br><br>
                                    <span style="font-size: 11pt;" color="#000000">Dokter Pemeriksa</span>
                                </td>
                            </tr>
                            <tr>
                                <td style="width:50%; height:100px"></td>
                                <td style="text-align: center" width="50%">
                                    {{-- <br> --}}
                                    <img src="data:image/png;base64, {!! $qrcode !!}" width="150">
                                    {{-- <br><br> --}}
                                </td>
                                {{-- <td style="width:50%; height:100px"></td> --}}
                            </tr>
                            <tr>
                                <td style="width:50%"></td>
                                <td style="width:50%; text-align:center">
                                    <span style="font-size: 11pt;font-weight:bold;text-decoration: underline;"
                                        color="#000000">{{ $dataReport['identitas']->dokterdpjp }}</span>
                                    <br>
                                    <span style="font-size: 11pt;"
                                        color="#000000">{{ $dataReport['identitas']->nosip }}</span>
                                </td>
                            </tr>
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
