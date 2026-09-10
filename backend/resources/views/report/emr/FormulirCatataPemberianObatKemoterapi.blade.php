@extends('template.layout-emr-v2')
@section('title', 'Formulir Catatan Pemberian Obat Kemoterapi')
{{-- @section('kode', 'RM.1A/RLD/01') --}}
@section('page-style')
    <style>
        * {
            font-family: Arial, Helvetica, sans-serif;
        }

        .bold {
            font-weight: bold;
        }

        table {
            border-collapse: collapse !important;
            width: 100%;
        }

        input[type=checkbox]:before {
            font-family: DejaVu Sans !important;
        }

        .border {
            border: 1px solid black;
        }

        .font {
            font-size: 14px !important;
        }
    </style>
@endsection

@php
    // Check IMG Default
    $imgDefault =
        'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAJYAAACWCAYAAAA8AXHiAAAAAXNSR0IArs4c6QAAAqFJREFUeF7t0jENAAAMw7CVP+mhyOcC6BF5ZwoEBRZ8ulTgwIIgKQBWktUpWAwkBcBKsjoFi4GkAFhJVqdgMZAUACvJ6hQsBpICYCVZnYLFQFIArCSrU7AYSAqAlWR1ChYDSQGwkqxOwWIgKQBWktUpWAwkBcBKsjoFi4GkAFhJVqdgMZAUACvJ6hQsBpICYCVZnYLFQFIArCSrU7AYSAqAlWR1ChYDSQGwkqxOwWIgKQBWktUpWAwkBcBKsjoFi4GkAFhJVqdgMZAUACvJ6hQsBpICYCVZnYLFQFIArCSrU7AYSAqAlWR1ChYDSQGwkqxOwWIgKQBWktUpWAwkBcBKsjoFi4GkAFhJVqdgMZAUACvJ6hQsBpICYCVZnYLFQFIArCSrU7AYSAqAlWR1ChYDSQGwkqxOwWIgKQBWktUpWAwkBcBKsjoFi4GkAFhJVqdgMZAUACvJ6hQsBpICYCVZnYLFQFIArCSrU7AYSAqAlWR1ChYDSQGwkqxOwWIgKQBWktUpWAwkBcBKsjoFi4GkAFhJVqdgMZAUACvJ6hQsBpICYCVZnYLFQFIArCSrU7AYSAqAlWR1ChYDSQGwkqxOwWIgKQBWktUpWAwkBcBKsjoFi4GkAFhJVqdgMZAUACvJ6hQsBpICYCVZnYLFQFIArCSrU7AYSAqAlWR1ChYDSQGwkqxOwWIgKQBWktUpWAwkBcBKsjoFi4GkAFhJVqdgMZAUACvJ6hQsBpICYCVZnYLFQFIArCSrU7AYSAqAlWR1ChYDSQGwkqxOwWIgKQBWktUpWAwkBcBKsjoFi4GkAFhJVqdgMZAUACvJ6hQsBpICYCVZnYLFQFIArCSrU7AYSAqAlWR1ChYDSQGwkqxOwWIgKQBWktUpWAwkBcBKsjp9QYIAl6bSsVAAAAAASUVORK5CYII=';

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

    function convertToMakassarDate($isoDateString)
    {
        $date = new DateTime($isoDateString, new DateTimeZone('UTC'));
        $date->setTimezone(new DateTimeZone('Asia/Jakarta'));
        return $date->format('d-m-Y');
    }
@endphp

@section('content')
    <tr>
        <td colspan="3" class="font border" style="padding: 3px">
            <table>
                <tr>
                    <td>
                        Berat Badan : {{ $data['beratBadan'] ?? '-' }} Kg
                    </td>
                    <td>
                        Tinggi Badan : {{ $data['tinggiBadan'] ?? '-' }} Cm
                    </td>
                    <td>
                        BSA : {{ $data['BSA'] ?? '-' }} BSA
                    </td>
                    <td>
                        Alergi : {{ $data['alergi'] ?? '-' }}
                    </td>
                    <td>
                        {{-- Lembar Ke: {{ $data['lembarKe'] ?? '-' }} --}}
                    </td>
                </tr>
                <tr>
                    <td>
                        Nama DPJP :
                        @if (isset($data['dokterDPJP']['label']))
                            {{ $data['dokterDPJP']['label'] ?? '-' }}
                        @else
                            {{ $data['dokterDPJP'] ?? '-' }}
                        @endif
                    </td>
                    <td colspan="2">
                        Diagnosis : {{ $data['TA_Diagnosis'] ?? '-' }}
                    </td>
                    <td>
                        Regimen: {{ $data['regimen'] ?? '-' }}
                    </td>
                    <td>
                        Serial: {{ $data['seri'] ?? '-' }}
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td colspan="3" class="border font">
            {{-- <table>
                <tr>
                    <td colspan="24" style="border: 1px solid black; width: 65.2%; padding-left: 10px;">
                        RUANGAN: {{ $data['ruangan'] ?? '-' }}
                    </td>
                    <td colspan="8" style="border: 1px solid black; font-weight: bold; text-align: center; ">
                        TANGGAL PEMBERIAN OBAT
                    </td>
                </tr>
                <tr>
                    <td colspan="24"
                        style="border: 1px solid black; width: 65.2%; padding-left: 10px; font-weight: bold;">
                        PREMEDIKASI
                    </td>
                    <td colspan="2" style="border: 1px solid black; text-align: center; ">
                        Tanggal & Jam
                        <br>
                        {{ isset($data['tanggalKunjunganPasien1']) ? convertToMakassarDate($data['tanggalKunjunganPasien1']) : '-' }}
                    </td>
                    <td colspan="2" style="border: 1px solid black; text-align: center; ">
                        Tanggal & Jam
                        <br>
                        {{ isset($data['tanggalKunjunganPasien2']) ? convertToMakassarDate($data['tanggalKunjunganPasien2']) : '-' }}
                    </td>
                    <td colspan="2" style="border: 1px solid black; text-align: center; ">
                        Tanggal & Jam
                        <br>
                        {{ isset($data['tanggalKunjunganPasien3']) ? convertToMakassarDate($data['tanggalKunjunganPasien3']) : '-' }}
                    </td>
                    <td colspan="2" style="border: 1px solid black; text-align: center; ">
                        Tanggal & Jam
                        <br>
                        {{ isset($data['tanggalKunjunganPasien4']) ? convertToMakassarDate($data['tanggalKunjunganPasien4']) : '-' }}
                    </td>
                </tr>
                <tr>
                    <td style="border: 1px solid black; width: 3%; padding-left: 10px;text-align: center">
                        No
                    </td>
                    <td colspan="7" style="border: 1px solid black; text-align: center; ">
                        Nama Obat
                    </td>
                    <td colspan="4" style="border: 1px solid black; text-align: center; ">
                        Dosis
                    </td>
                    <td colspan="4" style="border: 1px solid black; text-align: center; ">
                        Pelarut
                    </td>
                    <td colspan="4" style="border: 1px solid black; text-align: center; ">
                        Rute
                    </td>
                    <td colspan="4" style="border: 1px solid black; text-align: center; ">
                        Lama Pemberian
                    </td>
                    <td style="border: 1px solid black; text-align: center; ">
                        P1
                    </td>
                    <td style="border: 1px solid black; text-align: center; ">
                        P2
                    </td>
                    <td style="border: 1px solid black; text-align: center; ">
                        P1
                    </td>
                    <td style="border: 1px solid black; text-align: center; ">
                        P2
                    </td>
                    <td style="border: 1px solid black; text-align: center; ">
                        P1
                    </td>
                    <td style="border: 1px solid black; text-align: center; ">
                        P2
                    </td>
                    <td style="border: 1px solid black; text-align: center; ">
                        P1
                    </td>
                    <td style="border: 1px solid black; text-align: center; ">
                        P2
                    </td>
                </tr>
                @for ($i = 1; $i <= 6; $i++)
                    <tr>
                        <td style="border: 1px solid black; text-align: center;">
                            {{ $i }}
                        </td>
                        <td colspan="7" style="border: 1px solid black; text-align: left;padding: 3px">
                            {{ $data["obat-{$i}"]['namaproduk'] ?? '-' }}
                        </td>
                        <td colspan="4" style="border: 1px solid black; text-align: center;">
                            {{ $data["jumlahObat-{$i}"] ?? '-' }}
                        </td>
                        <td colspan="4" style="border: 1px solid black; text-align: center;">
                            {{ $data["pelarut-{$i}"] ?? '-' }}
                        </td>
                        <td colspan="4" style="border: 1px solid black; text-align: center;">
                            {{ $data["rute-{$i}"] ?? '-' }}
                        </td>
                        <td colspan="4" style="border: 1px solid black; text-align: center;">
                            {{ $data["lamaPemberian-{$i}"] ?? '-' }}
                        </td>
                        <td style="border: 1px solid black; text-align: center;">
                            {{ $data["perawat1_1-{$i}"]['label'] ?? '-' }}
                        </td>
                        <td style="border: 1px solid black; text-align: center;">
                            {{ $data["perawat2_1-{$i}"]['label'] ?? '-' }}
                        </td>
                        <td style="border: 1px solid black; text-align: center;">
                            {{ $data["perawat1_2-{$i}"]['label'] ?? '-' }}
                        </td>
                        <td style="border: 1px solid black; text-align: center;">
                            {{ $data["perawat2_2-{$i}"]['label'] ?? '-' }}
                        </td>
                        <td style="border: 1px solid black; text-align: center;">
                            {{ $data["perawat1_3-{$i}"]['label'] ?? '-' }}
                        </td>
                        <td style="border: 1px solid black; text-align: center;">
                            {{ $data["perawat2_3-{$i}"]['label'] ?? '-' }}
                        </td>
                        <td style="border: 1px solid black; text-align: center;">
                            {{ $data["perawat1_4-{$i}"]['label'] ?? '-' }}
                        </td>
                        <td style="border: 1px solid black; text-align: center;">
                            {{ $data["perawat2_4-{$i}"]['label'] ?? '-' }}
                        </td>
                    </tr>
                @endfor
                <tr>
                    <td colspan="16" style="vertical-align: top;padding: 3px">
                        Nama Dokter :
                        @if (isset($data['dokterDPJPPremadikasi']['label']))
                            {{ $data['dokterDPJPPremadikasi']['label'] ?? '-' }}
                        @else
                            {{ $data['dokterDPJPPremadikasi'] ?? '-' }}
                        @endif
                        <br>
                        NIP :
                        @if (isset($data['nip']->nip))
                            {{ $data['nip']->nip }}
                        @else
                            {{ '-' }}
                        @endif
                    </td>
                    <td colspan="8" class="border" style="text-align: center;vertical-align: top">
                        <img src="data:image/png;base64, {!! $tte !!}">
                    </td>
                    <td colspan="8" style="vertical-align: top; padding: 3px">
                        Catatan: {{ $data['catatanPremadikasi'] ?? '-' }}
                    </td>
                </tr>
            </table> --}}
            <table>
                <tr>
                    <td colspan="24" style="border: 1px solid black; width: 65.2%; padding-left: 10px;">
                        RUANGAN: {{ $data['ruangan'] ?? '-' }}
                    </td>
                    <td colspan="12" style="border: 1px solid black; font-weight: bold; text-align: center;">
                        TANGGAL PEMBERIAN OBAT
                    </td>
                </tr>
                <tr>
                    <td colspan="24"
                        style="border: 1px solid black; width: 65.2%; padding-left: 10px; font-weight: bold;">
                        PREMEDIKASI
                    </td>
                    <td colspan="3" style="border: 1px solid black; text-align: center;">
                        Tanggal & Jam
                        <br>
                        {{ isset($data['tanggalKunjunganPasien1']) ? convertToMakassarDate($data['tanggalKunjunganPasien1']) : '-' }}
                    </td>
                    <td colspan="3" style="border: 1px solid black; text-align: center;">
                        Tanggal & Jam
                        <br>
                        {{ isset($data['tanggalKunjunganPasien2']) ? convertToMakassarDate($data['tanggalKunjunganPasien2']) : '-' }}
                    </td>
                    <td colspan="3" style="border: 1px solid black; text-align: center;">
                        Tanggal & Jam
                        <br>
                        {{ isset($data['tanggalKunjunganPasien3']) ? convertToMakassarDate($data['tanggalKunjunganPasien3']) : '-' }}
                    </td>
                    <td colspan="3" style="border: 1px solid black; text-align: center;">
                        Tanggal & Jam
                        <br>
                        {{ isset($data['tanggalKunjunganPasien4']) ? convertToMakassarDate($data['tanggalKunjunganPasien4']) : '-' }}
                    </td>
                </tr>
                <tr>
                    <td style="border: 1px solid black; width: 3%; padding-left: 10px;text-align: center">
                        No
                    </td>
                    <td colspan="7" style="border: 1px solid black; text-align: center;">
                        Nama Obat
                    </td>
                    <td colspan="4" style="border: 1px solid black; text-align: center;">
                        Dosis
                    </td>
                    <td colspan="4" style="border: 1px solid black; text-align: center;">
                        Pelarut
                    </td>
                    <td colspan="4" style="border: 1px solid black; text-align: center;">
                        Rute
                    </td>
                    <td colspan="4" style="border: 1px solid black; text-align: center;">
                        Lama Pemberian
                    </td>
                    <td style="border: 1px solid black; text-align: center;">
                        Jam
                    </td>
                    <td style="border: 1px solid black; text-align: center;">
                        P1
                    </td>
                    <td style="border: 1px solid black; text-align: center;">
                        P2
                    </td>
                    <td style="border: 1px solid black; text-align: center;">
                        Jam
                    </td>
                    <td style="border: 1px solid black; text-align: center;">
                        P1
                    </td>
                    <td style="border: 1px solid black; text-align: center;">
                        P2
                    </td>
                    <td style="border: 1px solid black; text-align: center;">
                        Jam
                    </td>
                    <td style="border: 1px solid black; text-align: center;">
                        P1
                    </td>
                    <td style="border: 1px solid black; text-align: center;">
                        P2
                    </td>
                    <td style="border: 1px solid black; text-align: center;">
                        Jam
                    </td>
                    <td style="border: 1px solid black; text-align: center;">
                        P1
                    </td>
                    <td style="border: 1px solid black; text-align: center;">
                        P2
                    </td>
                </tr>
                @for ($i = 1; $i <= 6; $i++)
                    <tr>
                        <td style="border: 1px solid black; text-align: center;">
                            {{ $i }}
                        </td>
                        <td colspan="7" style="border: 1px solid black; text-align: left;padding: 3px">
                            {{ $data["obat-{$i}"]['namaproduk'] ?? '-' }}
                        </td>
                        <td colspan="4" style="border: 1px solid black; text-align: center;">
                            {{ $data["jumlahObat-{$i}"] ?? '-' }}
                        </td>
                        <td colspan="4" style="border: 1px solid black; text-align: center;">
                            {{ $data["pelarut-{$i}"] ?? '-' }}
                        </td>
                        <td colspan="4" style="border: 1px solid black; text-align: center;">
                            {{ $data["rute-{$i}"] ?? '-' }}
                        </td>
                        <td colspan="4" style="border: 1px solid black; text-align: center;">
                            {{ $data["lamaPemberian-{$i}"] ?? '-' }}
                        </td>
                        <td style="border: 1px solid black; text-align: center;">
                            {{ $data["jam1-{$i}"] ?? '-' }}
                            {{-- {{ isset($data["tanggalKunjunganPasien1"]) ? \Carbon\Carbon::parse($data["tanggalKunjunganPasien1"])->format('H:i') : '-' }} --}}

                        </td>
                        <td style="border: 1px solid black; text-align: center;">
                            {{ $data["perawat1_1-{$i}"]['label'] ?? '-' }}
                        </td>
                        <td style="border: 1px solid black; text-align: center;">
                            {{ $data["perawat2_1-{$i}"]['label'] ?? '-' }}
                        </td>
                        <td style="border: 1px solid black; text-align: center;">
                            {{ $data["jam2-{$i}"] ?? '-' }}
                        </td>
                        <td style="border: 1px solid black; text-align: center;">
                            {{ $data["perawat1_2-{$i}"]['label'] ?? '-' }}
                        </td>
                        <td style="border: 1px solid black; text-align: center;">
                            {{ $data["perawat2_2-{$i}"]['label'] ?? '-' }}
                        </td>
                        <td style="border: 1px solid black; text-align: center;">
                            {{ $data["jam3-{$i}"] ?? '-' }}
                        </td>
                        <td style="border: 1px solid black; text-align: center;">
                            {{ $data["perawat1_3-{$i}"]['label'] ?? '-' }}
                        </td>
                        <td style="border: 1px solid black; text-align: center;">
                            {{ $data["perawat2_3-{$i}"]['label'] ?? '-' }}
                        </td>
                        <td style="border: 1px solid black; text-align: center;">
                            {{ $data["jam4-{$i}"] ?? '-' }}
                        </td>
                        <td style="border: 1px solid black; text-align: center;">
                            {{ $data["perawat1_4-{$i}"]['label'] ?? '-' }}
                        </td>
                        <td style="border: 1px solid black; text-align: center;">
                            {{ $data["perawat2_4-{$i}"]['label'] ?? '-' }}
                        </td>
                    </tr>
                @endfor
                <tr>
                    <td colspan="16" style="vertical-align: top;padding: 3px">
                        Nama Dokter :
                        @if (isset($data['dokterDPJPPremadikasi']['label']))
                            {{ $data['dokterDPJPPremadikasi']['label'] ?? '-' }}
                        @else
                            {{ $data['dokterDPJPPremadikasi'] ?? '-' }}
                        @endif
                        <br>
                        NIP :
                        @if (isset($data['nip']->nip))
                            {{ $data['nip']->nip }}
                        @else
                            {{ '-' }}
                        @endif
                    </td>
                    <td colspan="8" class="border" style="text-align: center;vertical-align: top">
                        <img src="data:image/png;base64, {!! $tte !!}">
                    </td>
                    <td colspan="12" style="vertical-align: top; padding: 3px">
                        Catatan: {{ $data['catatanPremadikasi'] ?? '-' }}
                    </td>
                </tr>
            </table>

        </td>
    </tr>
    <tr>
        <td colspan="3" class="font border">
            <table>
                <tr>
                    <td colspan="24"
                        style="border: 1px solid black; width: 65.2%; padding-left: 10px; font-weight: bold;">
                        KEMOTERAPI
                    </td>
                    <td colspan="2" style="border: 1px solid black; text-align: center; ">
                        Tanggal & Jam
                        <br>
                        {{ isset($data['tanggalKemoterapi1']) ? convertToMakassarDate($data['tanggalKemoterapi1']) : '-' }}
                    </td>
                    <td colspan="2" style="border: 1px solid black; text-align: center; ">
                        Tanggal & Jam
                        <br>
                        {{ isset($data['tanggalKemoterapi2']) ? convertToMakassarDate($data['tanggalKemoterapi2']) : '-' }}
                    </td>
                    <td colspan="2" style="border: 1px solid black; text-align: center; ">
                        Tanggal & Jam
                        <br>
                        {{ isset($data['tanggalKemoterapi3']) ? convertToMakassarDate($data['tanggalKemoterapi3']) : '-' }}
                    </td>
                    <td colspan="2" style="border: 1px solid black; text-align: center; ">
                        Tanggal & Jam
                        <br>
                        {{ isset($data['tanggalKemoterapi4']) ? convertToMakassarDate($data['tanggalKemoterapi4']) : '-' }}
                    </td>
                </tr>
                <tr>
                    <td style="border: 1px solid black; width: 3%; padding-left: 10px;text-align: center">
                        No
                    </td>
                    <td colspan="7" style="border: 1px solid black; text-align: center; ">
                        Nama Obat
                    </td>
                    <td colspan="4" style="border: 1px solid black; text-align: center; ">
                        Dosis
                    </td>
                    <td colspan="4" style="border: 1px solid black; text-align: center; ">
                        Pelarut
                    </td>
                    <td colspan="4" style="border: 1px solid black; text-align: center; ">
                        Rute
                    </td>
                    <td colspan="4" style="border: 1px solid black; text-align: center; ">
                        Lama Pemberian
                    </td>

                    <td style="border: 1px solid black; text-align: center;">
                        Jam
                    </td>
                    <td style="border: 1px solid black; text-align: center; ">
                        P1
                    </td>
                    <td style="border: 1px solid black; text-align: center; ">
                        P2
                    </td>

                    <td style="border: 1px solid black; text-align: center;">
                        Jam
                    </td>
                    <td style="border: 1px solid black; text-align: center; ">
                        P1
                    </td>
                    <td style="border: 1px solid black; text-align: center; ">
                        P2
                    </td>

                    <td style="border: 1px solid black; text-align: center;">
                        Jam
                    </td>
                    <td style="border: 1px solid black; text-align: center; ">
                        P1
                    </td>
                    <td style="border: 1px solid black; text-align: center; ">
                        P2
                    </td>

                    <td style="border: 1px solid black; text-align: center;">
                        Jam
                    </td>
                    <td style="border: 1px solid black; text-align: center; ">
                        P1
                    </td>
                    <td style="border: 1px solid black; text-align: center; ">
                        P2
                    </td>
                </tr>
                @for ($i = 1; $i <= 6; $i++)
                    <tr>
                        <td style="border: 1px solid black; text-align: center;">
                            {{ $i }}
                        </td>
                        <td colspan="7" style="border: 1px solid black; text-align: left;">
                            {{ $data["obatKemoterapi-{$i}"]['namaproduk'] ?? '-' }}
                        </td>
                        <td colspan="4" style="border: 1px solid black; text-align: center;">
                            {{ $data["jumlahObatKemoterapi-{$i}"] ?? '-' }}
                        </td>
                        <td colspan="4" style="border: 1px solid black; text-align: center;">
                            {{ $data["pelarutKemoterapi-{$i}"] ?? '-' }}
                        </td>
                        <td colspan="4" style="border: 1px solid black; text-align: center;">
                            {{ $data["ruteKemoterapi-{$i}"] ?? '-' }}
                        </td>
                        <td colspan="4" style="border: 1px solid black; text-align: center;">
                            {{ $data["lamaPemberianKemoterapi-{$i}"] ?? '-' }}
                        </td>
                        <td style="border: 1px solid black; text-align: center;">
                            {{ $data["jam1LamaKemo-{$i}"] ?? '-' }}
                            {{-- {{ isset($data["tanggalKunjunganPasien1"]) ? \Carbon\Carbon::parse($data["tanggalKunjunganPasien1"])->format('H:i') : '-' }} --}}

                        </td>
                        <td style="border: 1px solid black; text-align: center;">
                            {{ $data["perawat_kemoterapi_1_1-{$i}"]['label'] ?? '-' }}
                        </td>
                        <td style="border: 1px solid black; text-align: center;">
                            {{ $data["perawat_kemoterapi_2_1-{$i}"]['label'] ?? '-' }}
                        </td>

                        <td style="border: 1px solid black; text-align: center;">
                            {{ $data["jam2LamaKemo-{$i}"] ?? '-' }}
                            {{-- {{ isset($data["tanggalKunjunganPasien1"]) ? \Carbon\Carbon::parse($data["tanggalKunjunganPasien1"])->format('H:i') : '-' }} --}}

                        </td>
                        <td style="border: 1px solid black; text-align: center;">
                            {{ $data["perawat_kemoterapi_1_2-{$i}"]['label'] ?? '-' }}
                        </td>
                        <td style="border: 1px solid black; text-align: center;">
                            {{ $data["perawat_kemoterapi_2_2-{$i}"]['label'] ?? '-' }}
                        </td>

                        <td style="border: 1px solid black; text-align: center;">
                            {{ $data["jam3LamaKemo-{$i}"] ?? '-' }}
                            {{-- {{ isset($data["tanggalKunjunganPasien1"]) ? \Carbon\Carbon::parse($data["tanggalKunjunganPasien1"])->format('H:i') : '-' }} --}}

                        </td>
                        <td style="border: 1px solid black; text-align: center;">
                            {{ $data["perawat_kemoterapi_1_3-{$i}"]['label'] ?? '-' }}
                        </td>
                        <td style="border: 1px solid black; text-align: center;">
                            {{ $data["perawat_kemoterapi_2_3-{$i}"]['label'] ?? '-' }}
                        </td>

                        <td style="border: 1px solid black; text-align: center;">
                            {{ $data["jam4LamaKemo-{$i}"] ?? '-' }}
                            {{-- {{ isset($data["tanggalKunjunganPasien1"]) ? \Carbon\Carbon::parse($data["tanggalKunjunganPasien1"])->format('H:i') : '-' }} --}}

                        </td>
                        <td style="border: 1px solid black; text-align: center;">
                            {{ $data["perawat_kemoterapi_1_4-{$i}"]['label'] ?? '-' }}
                        </td>
                        <td style="border: 1px solid black; text-align: center;">
                            {{ $data["perawat_kemoterapi_2_4-{$i}"]['label'] ?? '-' }}
                        </td>
                    </tr>
                @endfor
                <tr>
                    <td colspan="16" style="border: 1px solid black;vertical-align: top;padding: 3px">
                        Nama Dokter :
                        @if (isset($data['dokterDPJPKemoterapi']['label']))
                            {{ $data['dokterDPJPKemoterapi']['label'] ?? '-' }}
                        @else
                            {{ $data['dokterDPJPKemoterapi'] ?? '-' }}
                        @endif
                        <br>
                        NIP :
                        @if (isset($data['nip2']->nip))
                            {{ $data['nip2']->nip }}
                        @else
                            {{ '-' }}
                        @endif
                    </td>
                    <td colspan="8"
                        style="border: 1px solid black;vertical-align: top;padding: 3px;text-align: center">
                        <img src="data:image/png;base64, {!! $tte !!}">
                    </td>
                    <td colspan="12" style="border: 1px solid black;vertical-align: top;padding: 3px">
                        Catatan: {{ $data['catatanKemoterapi'] ?? '-' }}
                    </td>
                </tr>
            </table>
        </td>
    </tr>
@endsection
