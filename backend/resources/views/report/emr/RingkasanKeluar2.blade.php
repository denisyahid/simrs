@php
    function convertToRegularDate($isoDateString)
    {
        $date = new DateTime($isoDateString);
        return $date->format('d-m-Y');
    }

    function convertToRegularTime($isoDateString)
    {
        $date = new DateTime($isoDateString);
        return $date->format('H:i');
    }

    //dd($data[$i]s);

@endphp
@extends('template.layout-emr')
@section('title', 'Ringkasan Keluar Rawat Jalan')
@section('kode', 'RM 3/BPJS/00')
@section('page-style')
    <style>
        body,
        table,
        td,pre {
            font-family: 'Open Sans', sans-serif !important;
        }

        .center {
            vertical-align: middle;
            text-align: center
        }

        .border {
            border: 1px solid black;
        }

        .subJudul {
            vertical-align: middle;
            width: 30%;
            border-right: 1px solid black !important;
            border-bottom: 1px solid black !important;
        }

        .isi {
            width: 70%;
            padding-top: none;
            border-bottom: 1px solid black !important;
        }

        .table {
            width: 100%;
            border-collapse: collapse
        }

        .list2>td {
            padding: 5px;
            border: none;
            font-size: 10pt
        }

        .font {
            font-size: 8pt
        }


        table { page-break-inside:auto }
        tr    { page-break-inside:auto; page-break-after:auto }
    </style>
@endsection

@section('content')

@for($i = 0; $i < count($data); $i++)
<div style="page-break-after: always;">
<table width="100%">
    <tr>
        <td>
            <table class="table">
                <tr class="list2">
                    <td class="subJudul">Kondisi Saat Masuk</td>
                    <td class="isi">
                        {{-- {{dd($data[$i])}} --}}
                        @if(isset($data[$i]['anamnesis']))
                            {{-- {{dd($data[$i]['anamnesis'])}} --}}
                            @if(strlen($data[$i]['anamnesis']) <= 1000)
                                <pre style="margin: 0px;padding:0px; white-space: normal; font-size: 10pt;">
                                {{ str_contains($data[$i]['anamnesis'], 'Keluhan : -') == 1 ? 'Melanjutkan terapi' : ($data[$i]['anamnesis'] ? $data[$i]['anamnesis'] : '-')  }}
                                </pre>
                            @else
                                <pre style="margin: 0px;padding:0px; white-space: normal; font-size: 9pt;">
                                {{ str_contains($data[$i]['anamnesis'], 'Keluhan : -') == 1 ? 'Melanjutkan terapi' : ($data[$i]['anamnesis'] ? $data[$i]['anamnesis'] : '-')  }}
                                </pre>
                            @endif
                            @elseif(isset($data[$i]['keluhanUtama']))
                                @if(strlen($data[$i]['keluhanUtama']) <= 1000)
                                <pre style="margin: 0px;padding:0px; white-space: normal; font-size: 10pt;">
                                {{ str_contains($data[$i]['keluhanUtama'], 'Keluhan : -') == 1 ? 'Melanjutkan terapi' : ($data[$i]['keluhanUtama'] ? $data[$i]['keluhanUtama'] : '-') }}
                                </pre>
                            @else
                                <pre style="margin: 0px;padding:0px; white-space: normal; font-size: 9pt;">
                                {{ str_contains($data[$i]['keluhanUtama'], 'Keluhan : -') == 1 ? 'Melanjutkan terapi' : ($data[$i]['keluhanUtama'] ? $data[$i]['keluhanUtama'] : '-') }}
                                </pre>
                            @endif
                        @elseif ($data[$i]['registrasi']['objectruanganfk'] == 245)
                            <pre>{{$data[$i]['keluhanUtama']}}</pre>
                        @endif
                        {{-- {{$data[$i]['keluhanUtama']}} --}}

                        {{-- {{dd($data[$i])}} --}}
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td>
            <table class="table">
                <tr class="list2">
                    <td class="subJudul">Pemeriksaan Fisik</td>
                    <td class="isi">
                        <p style="padding:0px;margin: 0px">
                            Kesan Umum :
                            <span>
                                @if (isset($data[$i]['kesanUmum']))
                                    @switch($data[$i]['kesanUmum'])
                                        @case(1)
                                            Baik
                                        @break

                                        @case(2)
                                            Sedang
                                        @break

                                        @case(3)
                                            Buruk
                                        @break

                                        @default
                                            -
                                    @endswitch
                                @endif
                            </span>
                        </p>
                        <p style="padding:0; margin: 0; margin-top: 10px">
                            <table style="width: 100%; padding:0px !important; margin:0 !important">
                                <tr>
                                    <td colspan="3">
                                        Tanda Vital :
                                    </td>
                                </tr>
                                <tr>
                                    {{-- <td style="width: 25%; padding:0px !important;"></td> --}}
                                    <td style="width: 25%; padding:0px !important;">
                                        TD : {{ isset($data[$i]['tekananDarah']) ? $data[$i]['tekananDarah'] : '-' }} mmHg
                                    </td>
                                    <td style="width: 25%">
                                        Nadi :{{ isset($data[$i]['nadi']) ? $data[$i]['nadi'] : '-' }} x/menit
                                    </td>
                                    <td style="width: 25%">
                                        RR : {{ isset($data[$i]['nafas']) ? $data[$i]['nafas'] : '-' }} x/menit
                                    </td>
                                </tr>
                                <tr>
                                    {{-- <td style="width: 25%; padding:0px !important;"></td> --}}
                                    <td style="width: 25%; padding:0px !important;">
                                        Suhu : {{ isset($data[$i]['celcius']) ? $data[$i]['celcius'] : '-' }} °C
                                    </td>
                                    <td style="width: 50%" colspan="2">GCS :
                                        E {{ isset($data[$i]['gcse']) ? $data[$i]['gcse'] : '-' }}
                                        V {{ isset($data[$i]['gcsv']) ? $data[$i]['gcsv'] : '-' }}
                                        M {{ isset($data[$i]['gcsm']) ? $data[$i]['gcsm'] : '-' }}
                                    </td>
                                </tr>
                            </table>
                            {{-- <span>{{ isset($data[$i]['pemeriksaanfisik']) ? $data[$i]['pemeriksaanfisik'] : '-' }}</span> --}}
                        </p>

                        <p>
                            Pemeriksaan fisik lainnya yang ditemukan :
                            <span>{{ isset($data[$i]['pemeriksaanfisik']) ? $data[$i]['pemeriksaanfisik'] : '-' }}</span>
                        </p>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td>
            <table class="table">
                <tr class="list2">
                    <td class="subJudul">Pemeriksaan Penunjang</td>
                    <td class="isi">
                        <pre style="margin: 0px;padding:0px; white-space: normal; font-size: 9pt;">{{ isset($data[$i]['hasilpemeriksaanpenunjang']) ? $data[$i]['hasilpemeriksaanpenunjang'] : '-' }}</pre>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td>
            <table class="table">
                <tr class="list2">
                    <td class="subJudul">Diagnosis Primer</td>
                    <td class="isi">
                        @if($data[$i]['TADiagnosisPrimer'] != null)
                        {{ isset($data[$i]['TADiagnosisPrimer']) ? $data[$i]['TADiagnosisPrimer'] : '-' }}
                        @elseif($data[$i]['diagnosisFisioterapi'] != null)
                        {{ isset($data[$i]['diagnosisFisioterapi']) ? $data[$i]['diagnosisFisioterapi'] : '-' }}
                        @endif
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td>
            <table class="table">
                <tr class="list2">
                    <td class="subJudul">Diagnosis Sekunder</td>
                    <td class="isi">
                        @if (isset($data[$i]['detailDS']))
                            <table style="width: 100%">
                                @foreach ($data[$i]['detailDS'] as $item)
                                    <tr>
                                        <td style="width: 70%">
                                            {{ $item['no'] }}.
                                            {{ isset($item['TADiagnosaSekunder']) ? $item['TADiagnosaSekunder'] : '-' }}
                                        </td>
                                        <td style="width: 30%">
                                            ICD : {{ isset($item['TBicdDS']) ? $item['TBicdDS'] : '-' }}
                                        </td>
                                    </tr>
                                @endforeach
                            </table>
                        @else
                            -
                        @endif
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td>
            <table class="table">
                <tr class="list2">
                    <td class="subJudul">Tindakan</td>
                    <td class="isi">
                        @if (isset($data[$i]['detailDT']))
                            <table style="width: 100%">
                                @foreach ($data[$i]['detailDT'] as $item)
                                    <tr>
                                        <td style="width: 70%">
                                            {{ $item['no'] }}.
                                            {{ isset($item['TADeskripsiTindakan']) ? $item['TADeskripsiTindakan'] : '-' }}
                                        </td>
                                        <td style="width: 30%">
                                            ICD 9 CM : {{ isset($item['TBicdDT']) ? $item['TBicdDT'] : '-' }}
                                        </td>
                                    </tr>
                                @endforeach
                            </table>
                        @else
                            -
                        @endif
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td>
            <table class="table">
                <tr class="list2">
                    <td class="subJudul">Terapi</td>
                    <td class="isi">
                        <pre style="margin: 0px;padding:0px; white-space: normal;">{{ isset($data[$i]['intruksi']) ? $data[$i]['intruksi'] : '-' }}</pre>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td>
            <table class="table">
                <tr class="list2">
                    <td class="subJudul">Kondisi Saat Keluar</td>
                    <td class="isi">
                        <table class="table">
                            <tr>
                                <td style="width: 30%">
                                    <label for="ckid" style="word-wrap:break-word;vertical-align:middle">
                                        {{-- <br> --}}
                                        <input id="ckid" type="checkbox" checked style="vertical-align:middle !important;"/>
                                        {{-- asd --}}
                                        <span style="font-size: 8pt; margin-top: auto !important; margin-bottom: auto !important; padding-bottom: 100px" color="#000000">{{$data[$i]['riwayatkeluar'] ?? 'Membaik'}}</span>
                                    </label>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td>
            <div>
                <table width="100%">
                    <tr>
                        <td class="font" width="33%" rowspan="5" style="text-align: left; vertical-align: bottom">
                            RSUD Bali Mandara <br>
                            Jl. By Pass Ngurah Rai No. 548 Sanur, Garut
                        </td>
                        <td class="font" width="33%" rowspan="5" style="text-align: center;vertical-align: bottom"></td>
                        <td class="font" style="text-align: center;" width="33%">
                            <br>
                            Garut, {{ date('j-F-Y', strtotime($data[$i]['registrasi']['tglregistrasi'])) }}
                        </td>
                    </tr>
                    <tr>
                        <td class="font" style="text-align: center" width="33%" colspan="3">
                            Dokter
                        </td>
                    </tr>
                    <tr>
                        <td class="font" style="text-align: center" width="33%" colspan="3">
                            <img src="data:image/png;base64, {!! $tte !!}">
                        </td>
                    </tr>
                    <tr>
                        <td class="font" style="text-align: center;font-weight: bold;font-size:8pt" width="33%">
                            @if($data[$i]['registrasi']['namaruangan'] == 'POLI FISIOTERAPI' || $data[$i]['registrasi']['namaruangan'] == 'POLI REHAB MEDIK')
                                dr. COKORDA GDE BAYU BASKARA PUTRA, Sp.KFR
                            @else
                                {{ $data[$i]['user_input']['namalengkap'] }}
                            @endif
                        </td>
                    </tr>
                </table>
            </div>
        </td>
    </tr>
    </table>
    </div>
@endfor
@endsection
