@extends('template.layout-emr-v2')
@section('title', 'Surat Penggunaan Obat Khusus Kronis')
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

        .pdl {
            padding-left: 10px !important;
        }

        .pd td {
            padding: 3px;
            text-align: center;
            font-size: 10pt;
        }

        .fnt {
            font-size: 9pt;
        }

        .fnt th {
            border: 1px solid black;
            border-bottom: none;
        }

        .fnt td {
            vertical-align: top;
            padding: 3px;
        }

        input[type=checkbox]:before {
            font-family: DejaVu Sans !important;
        }

        .mid {
            text-align: center !important;
        }

        .border {
            border: 1px solid black;
        }

        .font {
            font-size: 10pt !important;
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
        return $date->format('d-m-Y H:i:s');
    }
@endphp

@section('content')
    @foreach ($data as $index => $d)
        <tr style="{{ $index > 0 ? 'page-break-before: always;' : '' }}">
            <td colspan="3" class="border">
                <table class="fnt" style="padding: 3px">
                    <tr>
                        <td class="bold">Saya yang bertanda tangan dibawah ini :</td>
                    </tr>
                    <table class="pdl">
                        <tr>
                            <td>Nama : {{ isset($d['NamaPetugas']) ? $d['NamaPetugas'] : '-' }}</td>
                        </tr>
                        <tr>
                            <td>Spesialis : {{ isset($d['SpesialisPetugas']) ? $d['SpesialisPetugas'] : '-' }}</td>
                        </tr>
                        <tr>
                            <td>Jabatan : {{ isset($d['JabatanPetugas']) ? $d['JabatanPetugas'] : '-' }}</td>
                        </tr>
                    </table>
                    <tr>
                        <td class="bold">Menerangkan bahwa penderita :</td>
                    </tr>
                    <table class="pdl">
                        <tr>
                            <td>Nomor Rekam Medis : {{ isset($d['RMPasien']) ? $d['RMPasien'] : '-' }}</td>
                        </tr>
                        <tr>
                            <td>Nama : {{ isset($d['NamaPasien']) ? $d['NamaPasien'] : '-' }}</td>
                        </tr>
                        <tr>
                            <td>Tgl Lahir/Umur :
                                {{ isset($d['TanggalLahirPasien']) ? convertToRegularDate($d['TanggalLahirPasien']) : '-' }}
                                /
                                {{ isset($d['UmurPasien']) ? $d['UmurPasien'] : '-' }}</td>
                        </tr>
                        <tr>
                            <td>Jenis Kelamin : {{ isset($d['JenisKelaminPasien']) ? $d['JenisKelaminPasien'] : '-' }}</td>
                        </tr>
                        <tr>
                            <td>Alamat : {{ isset($d['RMPasien']) ? $d['RMPasien'] : '-' }}</td>
                        </tr>
                        <tr>
                            <td>Diagnosa : {{ isset($d['DiagnosaPasien']) ? $d['DiagnosaPasien'] : '-' }}</td>
                        </tr>
                    </table>
                    <tr>
                        <td><b>Memang benar membutuhkan obat :</b>
                            {{ isset($d['MembutuhkanObat']) ? $d['MembutuhkanObat'] : '-' }}</td>
                    </tr>
                    <table class="pdl">
                        <tr>
                            <td>Selama : {{ isset($d['DurasiObat']) ? $d['DurasiObat'] : '-' }} Hari</td>
                        </tr>
                        <tr>
                            <td>Dosis : {{ isset($d['DosisObat']) ? $d['DosisObat'] : '-' }}</td>
                        </tr>
                        <tr>
                            <td>Alasan Pemberian : {{ isset($d['AlasanPemberianObat']) ? $d['AlasanPemberianObat'] : '-' }}
                            </td>
                        </tr>
                    </table>
                    <tr>
                        <td class="bold">Demikian surat ini kami sampaikan , untuk dapat dipergunakan sebagai mana
                            mestinya.
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <table class="fnt">
                                <tr>
                                    <td style="width: 50%"></td>
                                    <td style="width: 50%" class="mid">
                                        Garut, {{ isset($d['tanggal']) ? convertToMakassarDate($d['tanggal']) : '-' }}
                                    </td>
                                </tr>
                                <tr>
                                    <td class="mid">
                                        <span class="bold">Mengetahui<br>Kepala Instalasi Farmasi</span><br>
                                        @if (isset($d['TTDKepalaInstalasiFarmasi']) && $d['TTDKepalaInstalasiFarmasi'] != $imgDefault)
                                            <img style="width: 100px;height: 100px;"
                                                src="{{ $d['TTDKepalaInstalasiFarmasi'] }}">
                                        @else
                                            <br><img src="https://api.qrserver.com/v1/create-qr-code/?size=100x100&data=RIZKI DANIEL, S.Farm., Apt"><br/>
                                        @endif
                                        <br>
                                        <span>RIZKI DANIEL, S.Farm., Apt</span>
                                    </td>
                                    <td class="mid">
                                        <span class="bold"><br>Dokter Yang Merawat</span><br>
                                        @if (isset($d['TTDDokter']) && $d['TTDDokter'] != $imgDefault)
                                            <img style="width: 100px;height: 100px;" src="{{ $d['TTDDokter'] }}">
                                        @else
                                            <br><img src="https://api.qrserver.com/v1/create-qr-code/?size=100x100&data={{ $d['Dokter']['label'] }}"><br/>
                                        @endif
                                        <br>
                                        <span>{{ isset($d['Dokter']['label']) ? $d['Dokter']['label'] : '-' }}</span>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    @endforeach
@endsection
