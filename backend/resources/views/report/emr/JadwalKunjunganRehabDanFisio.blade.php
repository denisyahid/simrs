@extends('template.layout-emr')
@section('title', 'Jadwal Kunjungan Rehab dan Fisio')
@section('kode', 'RM.1A/RLD/01')
@section('page-style')
    <style>
        body,
        table,
        td {
            font-family: 'Open Sans', sans-serif !important;
        }

        .bold {
            font-weight: bold;
        }

        .center {
            vertical-align: middle;
            text-align: center
        }

        .threeTD {
            width: 33%
        }

        .border {
            border: 1px solid black;
        }

        .list>td {
            padding: 7px;
            border: 1px solid black;
            border-top: none;
            font-size: 10pt;
            text-align: center;
            vertical-align: middle;
        }

        .list2>td {
            padding: 7px;
            border: none;
            font-size: 10pt
        }

        .bTop {
            border-top: none;
        }

        .font {
            font-size: 8pt !important;
        }

        table {
            page-break-inside: auto
        }

        tr {
            page-break-inside: auto;
            page-break-after: avoid
        }
    </style>
@endsection

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

    $imgDefault =
        'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAJYAAACWCAYAAAA8AXHiAAAAAXNSR0IArs4c6QAAAqFJREFUeF7t0jENAAAMw7CVP+mhyOcC6BF5ZwoEBRZ8ulTgwIIgKQBWktUpWAwkBcBKsjoFi4GkAFhJVqdgMZAUACvJ6hQsBpICYCVZnYLFQFIArCSrU7AYSAqAlWR1ChYDSQGwkqxOwWIgKQBWktUpWAwkBcBKsjoFi4GkAFhJVqdgMZAUACvJ6hQsBpICYCVZnYLFQFIArCSrU7AYSAqAlWR1ChYDSQGwkqxOwWIgKQBWktUpWAwkBcBKsjoFi4GkAFhJVqdgMZAUACvJ6hQsBpICYCVZnYLFQFIArCSrU7AYSAqAlWR1ChYDSQGwkqxOwWIgKQBWktUpWAwkBcBKsjoFi4GkAFhJVqdgMZAUACvJ6hQsBpICYCVZnYLFQFIArCSrU7AYSAqAlWR1ChYDSQGwkqxOwWIgKQBWktUpWAwkBcBKsjoFi4GkAFhJVqdgMZAUACvJ6hQsBpICYCVZnYLFQFIArCSrU7AYSAqAlWR1ChYDSQGwkqxOwWIgKQBWktUpWAwkBcBKsjoFi4GkAFhJVqdgMZAUACvJ6hQsBpICYCVZnYLFQFIArCSrU7AYSAqAlWR1ChYDSQGwkqxOwWIgKQBWktUpWAwkBcBKsjoFi4GkAFhJVqdgMZAUACvJ6hQsBpICYCVZnYLFQFIArCSrU7AYSAqAlWR1ChYDSQGwkqxOwWIgKQBWktUpWAwkBcBKsjoFi4GkAFhJVqdgMZAUACvJ6hQsBpICYCVZnYLFQFIArCSrU7AYSAqAlWR1ChYDSQGwkqxOwWIgKQBWktUpWAwkBcBKsjoFi4GkAFhJVqdgMZAUACvJ6hQsBpICYCVZnYLFQFIArCSrU7AYSAqAlWR1ChYDSQGwkqxOwWIgKQBWktUpWAwkBcBKsjp9QYIAl6bSsVAAAAAASUVORK5CYII=';
@endphp

@section('content')
    <tr>
        <td colspan="5" class="border bTop" style="padding: 7px;font-size: 9pt">Diagnosa : {{ $data['TBDiagnosa'] }}</td>
    </tr>
    <tr>
        <td colspan="5" class="border bTop" style="padding: 7px;font-size: 9pt">Permintaan Terapi :
            {{ $data['TAPermintaanTerapi'] }}</td>
    </tr>
    <tr>
        <th class="center bold border bTop" rowspan="2" style="width: 20%">Program</th>
        <th class="center bold border bTop" rowspan="2" style="width: 20%">Tanggal</th>
        <th class="center bold border bTop" colspan="3" style="width: 60%">TTD</th>
    </tr>
    <tr>
        <th class="center bold border bTop" style="width: 20%">Pasien</th>
        <th class="center bold border bTop" style="width: 20%">Dokter</th>
        <th class="center bold border bTop" style="width: 20%">Terapis</th>
    </tr>

    @php
        $cek = 'tidak ada';
    @endphp
    @foreach ($data['details'] as $index => $item)
        @if ($data['parafPasien_' . $loop->index] != $imgDefault && isset($item['tanggal']) && date('d-m-Y', strtotime($item['tanggal'])) != '01-01-1970' && date('Y-m-d', strtotime($data['registrasi']['tglregistrasi'])) >= date('Y-m-d', strtotime($item['tanggal'])))
            @if(date('d-m-Y', strtotime($data['registrasi']['tglregistrasi'])) == date('d-m-Y', strtotime($item['tanggal'])))
            @php
            $cek = 'ada';
            @endphp
            @endif
            <tr class="list">
                <td>{{ $item['program'] ?? '-' }}</td>
                <td>
                    {{ isset($item['tanggal']) ? date('d-m-Y', strtotime($item['tanggal'])) : '-' }}
                </td>
                <td>
                    <img style="width: 100px;height: 100px;" src="{{ $data['parafPasien_' . $loop->index] }}">
                </td>
                <td class="font">
                    <img
                        src="https://api.qrserver.com/v1/create-qr-code/?size=50x50&data={{ $item['parafDokter']['label'] }}"><br />
                </td>
                <td class="font">
                    <img
                        src="https://api.qrserver.com/v1/create-qr-code/?size=50x50&data={{ $item['parafPegawai']['label'] }}"><br />
                </td>
            </tr>
        @endif
    @endforeach
    @if($cek == 'tidak ada')
    <tr class="list">
        <td>{{ $data['details'][0]['program'] ?? '-' }}</td>
        <td>
            {{ date('d-m-Y', strtotime($data['registrasi']['tglregistrasi'])) }}
        </td>
        <td>
            <img style="width: 100px;height: 100px;" src="{{ $data['parafPasien_0'] }}">
        </td>
        <td class="font">
            <img
                src="https://api.qrserver.com/v1/create-qr-code/?size=50x50&data={{ $data['details'][0]['parafDokter']['label'] }}"><br />
        </td>
        <td class="font">
            <img
                src="https://api.qrserver.com/v1/create-qr-code/?size=50x50&data={{ $data['details'][0]['parafPegawai']['label'] }}"><br />
        </td>
    </tr>
    @endif
@endsection
