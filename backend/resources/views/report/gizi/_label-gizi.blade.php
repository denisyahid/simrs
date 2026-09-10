<!DOCTYPE html>
<html lang="en">

<head>
    <title>Cetak Label Gizi</title>
    @if ((isset($res['pdf']) && $res['pdf']) || (isset($res['storage']) && $res['storage']))
        {{-- <link rel="stylesheet" href="css/paper.css">
        <link rel="stylesheet" href="css/table-v2.css">
        <link rel="stylesheet" href="css/tabel.css"> --}}
    @else
        {{-- <link rel="stylesheet" href="{{ asset('css/paper.css') }} ">
        <link rel="stylesheet" href="{{ asset('css/table-v2.css') }}">
        <link rel="stylesheet" href="{{ asset('css/tabel.css') }}"> --}}
    @endif
    <style>
        @page {
            size: auto;
            margin: 0;
            text-align: center;
            display: flex;
            justify-content: center;
            align-content: center size: auto;
        }

        @media print {
            .receipt {
                width: 58mm
            }
        }

        table td {
            font-size: 12px;
        }

        .border {
            border: 1px solid black
        }
    </style>
</head>

<body>
    @if (!empty($dataResult))
        @foreach ($dataResult['data'] as $data)
            <div class="container">
                <table class="receipt" style="background-color:#FFFFFF;padding:10px; width:100%">
                    <thead>
                        <tr>
                            <th colspan="3">
                                <hr style="padding: 0px;margin:0px;">
                            </th>
                        </tr>
                        <tr>
                            <th colspan="3">
                                <table style="width: 100%">
                                    <tr>
                                        <th rowspan="2">
                                            <img src="{{ 'img/logo-rs.png' }}" width="35px" height="35px"
                                                style="display: block;">
                                        </th>
                                        <th style="text-align: center; font-size:12pt;">
                                            <span style="text-transform:uppercase">
                                                @php $waktu = str_ireplace('Snack', '', $data->waktu); @endphp
                                                ETIKET {{ $data->kategorydiet }} {{ $waktu }}
                                            </span>
                                        </th>
                                    </tr>
                                    <tr>
                                        <th style="text-align: center; font-size:7pt;font-weight:light">
                                            <span>Sertifikat Laik Higiene Jasa Boga : 400.7.11.4 / 0561 / Dikes</span>
                                        </th>
                                    </tr>
                                </table>
                            </th>
                        </tr>
                    </thead>
                    <tbody style="font-size: 50px;">
                        <tr>
                            <td style="text-align: left;" width="19%">
                                <span>Tgl</span>
                            </td>
                            <td style="text-align: left;" width="69%">
                                @if ($data->waktu == 'Pagi' || $data->waktu == 'Snack Pagi')
                                    : {{ \Carbon\Carbon::now()->addDay()->format('d M Y') }}
                                @else
                                    : {{ date('d M Y') }}
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td style="text-align: left;" width="19%">
                                <span>Ruang</span>
                            </td>
                            <td style="text-align: left;" width="69%">
                                @php
                                    $ruangan = $data->ruangan;
                                    if($data->ruangan == 'RAWAT INAP KEDOKTERAN NUKLIR')  {
                                        $ruangan = 'RANAP RIRA';
                                    }
                                @endphp
                                <span style="font-size:smaller">: {{ isset($ruangan) ? $ruangan : '-' }} &nbsp;&nbsp;&nbsp;
                                    {{ isset($data->nobed) ? $data->nobed : '-' }}
                                    &nbsp;&nbsp;&nbsp; {{ $data->kelas }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td style="text-align: left;" width="19%">
                                <span>Nama</span>
                            </td>
                            <td style="text-align: left;" width="69%">
                                <span style="font-size:smaller; white-space: nowrap;">: {{ $data->nama }}</span>
                            </td>
                        </tr>
                        <tr>
                            <td style="text-align: left;" width="19%">
                                <span>Tgl Lahir</span>
                            </td>
                            <td style="text-align: left;" width="69%">
                                <span>: {{ $data->tgllahir }}</span>
                            </td>
                        </tr>
                        <tr>
                            <td style="text-align: left;" width="19%">
                                <span>No. RM</span>
                            </td>
                            <td style="text-align: left;" width="69%">
                                <span>: {{ $data->nocm }}</span>
                            </td>
                        </tr>
                        <tr>
                            <td style="text-align: left;" width="19%">
                                <span style="font-weight: bold">DIET</span>
                            </td>
                            <td style="text-align: left;" width="69%">
                                <span>: {{ isset($data->jenisdiet) ? $data->jenisdiet : '-' }}</span>
                            </td>
                        </tr>
                        @if (isset($data->jdexternal))
                            <tr>
                                <td style="text-align: left;" width="19%">
                                    <span style="font-weight: bold">DIET LAINNYA</span>
                                </td>
                                <td style="text-align: left;" width="69%">
                                    <span>: {{ isset($data->jdexternal) ? $data->jdexternal : '-' }}</span>
                                </td>
                            </tr>
                        @endif
                        <tr>
                            <td style="text-align: left;" width="19%">
                                <span style="font-weight: bold">Keterangan</span>
                            </td>
                            <td style="text-align: left;" width="69%">
                                <span>: {{ $data->keterangan }}</span>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" style="height: 10px">
                        </tr>
                        <tr>
                            <td style="text-align: left;" colspan="2">
                                <span style="font-weight: bold">Batas Aman Dikonsumsi</span>
                            </td>
                        </tr>
                        <tr>
                            <td style="text-align: left;" width="19%">
                                <span style="font-weight: bold">Jam</span>
                            </td>
                            <td style="text-align: left;" width="69%">
                                <span>:
                                    @switch($data->waktu)
                                        @case('Pagi')
                                            06:30 - 08-30
                                        @break

                                        @case('Snack Pagi')
                                            09:00 - 11-00
                                        @break

                                        @case('Siang')
                                            11:00 - 13-00
                                        @break

                                        @case('Snack Sore')
                                            14:30 - 16-30
                                        @break

                                        @case('Sore')
                                            17:00 - 19-00
                                        @break

                                        @default
                                            ???
                                    @endswitch
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <th colspan="3">
                                <hr style="padding: 0px;margin:0px;">
                            </th>
                        </tr>
                    </tbody>
                </table>
            </div>
        @endforeach
    @else
        {{-- <div class="container">
            <table class="receipt" style="background-color:#FFFFFF;padding:10px; width:100%">
                <thead>
                    <tr>
                        <th colspan="3">
                            <hr style="padding: 0px;margin:0px;">
                        </th>
                    </tr>
                    <tr>
                        <th colspan="3">
                            <table style="width: 100%">
                                <tr>
                                    <th rowspan="2">
                                        <img src="{{ 'img/logo-rs.png' }}" width="35px" height="35px"
                                            style="display: block;">
                                    </th>
                                    <th style="text-align: center; font-size:12pt;">
                                        <span style="text-transform:uppercase">
                                            ETIKET {{ $data->kategorydiet }} {{ $waktu }}
                                        </span>
                                    </th>
                                </tr>
                                <tr>
                                    <th style="text-align: center; font-size:7pt;font-weight:light">
                                        <span>Sertifikat Laik Higiene Jasa Boga : 374/212/1640/DS/DPMPTSP/2020</span>
                                    </th>
                                </tr>
                            </table>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td style="text-align: left" width="19%">
                            <span>Tgl</span>
                        </td>
                        <td style="text-align: left" width="69%">
                            <span>
                                @if ($waktu == 'Pagi' || $waktu == 'Snack Pagi')
                                    : {{ \Carbon\Carbon::now()->addDay()->format('d M Y') }}
                                @else
                                    : {{ date('d M Y') }}
                                @endif
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td style="text-align: left" width="19%">
                            <span>Ruang</span>
                        </td>
                        <td style="text-align: left" width="69%">
                            <span>: {{ $data->ruangan }} &nbsp;&nbsp;&nbsp; {{ $data->nobed }} &nbsp;&nbsp;&nbsp;
                                {{ $data->kelas }}</span>
                        </td>
                    </tr>
                    <tr>
                        <td style="text-align: left" width="19%">
                            <span>Nama</span>
                        </td>
                        <td style="text-align: left" width="69%">
                            <span>: {{ $data->nama }}</span>
                        </td>
                    </tr>
                    <tr>
                        <td style="text-align: left" width="19%">
                            <span>Tgl Lahir</span>
                        </td>
                        <td style="text-align: left" width="69%">
                            <span>: {{ $data->tgllahir }}</span>
                        </td>
                    </tr>
                    <tr>
                        <td style="text-align: left" width="19%">
                            <span>No. RM</span>
                        </td>
                        <td style="text-align: left" width="69%">
                            <span>: {{ $data->nocm }}</span>
                        </td>
                    </tr>
                    <tr>
                        <td style="text-align: left" width="19%">
                            <span style="font-weight: bold">DIET</span>
                        </td>
                        <td style="text-align: left" width="69%">
                            <span>: {{ isset($data->arrjenisdiet) ? $data->arrjenisdiet : '-' }}</span>
                        </td>
                    </tr>
                    @if (isset($data->jdexternal))
                        <tr>
                            <td style="text-align: left" width="19%">
                                <span style="font-weight: bold">DIET LAINNYA</span>
                            </td>
                            <td style="text-align: left" width="69%">
                                <span>: {{ isset($data->jdexternal) ? $data->jdexternal : '-' }}</span>
                            </td>
                        </tr>
                    @endif
                    <tr>
                        <td style="text-align: left" width="19%">
                            <span style="font-weight: bold">Keterangan</span>
                        </td>
                        <td style="text-align: left" width="69%">
                            <span>: {{ $data->keterangan }}</span>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" style="height: 10px">
                    </tr>
                    <tr>
                        <td style="text-align: left" colspan="2">
                            <span style="font-weight: bold">Batas Aman Dikonsumsi</span>
                        </td>
                    </tr>
                    <tr>
                        <td style="text-align: left" width="19%">
                            <span style="font-weight: bold">Jam</span>
                        </td>
                        <td style="text-align: left" width="69%">
                            <span>:
                                @switch($waktu)
                                    @case('Pagi')
                                        06:30 - 08-30
                                    @break

                                    @case('Snack Pagi')
                                        09:00 - 11-00
                                    @break

                                    @case('Siang')
                                        11:00 - 13-00
                                    @break

                                    @case('Snack Sore')
                                        14:30 - 16-30
                                    @break

                                    @case('Sore')
                                        17:00 - 19-00
                                    @break

                                    @default
                                        ???
                                @endswitch
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <th colspan="3">
                            <hr style="padding: 0px;margin:0px;">
                        </th>
                    </tr>
                </tbody>
            </table>
        </div> --}}
    @endif
</body>

</html>
