<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FORMULIR KAJIAN RIWAYAT MENYUSUI</title>

    <!-- <link rel="stylesheet" href="{{ asset('css/paper.css') }} "> -->
    <link rel="stylesheet" href="{{ asset('css/table-v2.css') }}">
    <link rel="stylesheet" href="{{ asset('css/tabel.css') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <style>

        .table-ex {
            /* border: 1px solid black; */
            border-collapse: collapse !important;
            width: 100%;
        }

        /* table, td, th, tr {
            vertical-align: middle;
        } */

        html,
        body {
            font-family: sans-serif;
            font-weight: normal;
            page-break-inside: avoid !important;
        }

        .table {
            width: 100%;
            border-collapse: collapse !important;
            border: 1px solid #000;
            background-color: transparent;
            page-break-inside: avoid !important;
        }

        .table thead td,
        .table thead th {
            border-bottom-width: 1px;
        }

        .table thead th {
            vertical-align: bottom;
        }

        .table-borderless {
            width: 100%;
            border-collapse: collapse !important;
            background-color: transparent;
            /* border: 1px solid black; */
            page-break-inside: avoid !important;
        }

        .table-borderless thead th {
            vertical-align: bottom;
        }

        .table-borderless tbody,
        .table-borderless td,
        .table-borderless th,
        .table-borderless thead th {
            background-color: transparent;
            border: 0 !important;
            padding: 5px 0 !important;
        }

        .table-borderless.no-pad tbody,
        .table-borderless.no-pad td,
        .table-borderless.no-pad th,
        .table-borderless.no-pad thead th {
            padding: 3px 0 !important;
        }


        .table td,
        .table th {
            border: 1px solid #000;
        }

        .table td,
        .table th {
            padding: .75rem;
            vertical-align: top;
            border-top: 1px solid #000;
        }

        .table-borderless td,
        .table-borderless th {
            padding: .75rem;
            vertical-align: top;
        }

        th {
            text-align: inherit;
        }

        i {
            font-size: 9pt;
        }


        .table-sub {
            padding: 5px;
            border: 1px black;
            border-collapse: collapse;
            width: 100%;
        }

        .th-sub {
            /* padding:3px; */
            text-align: center;
            border: 1px solid black;
        }

        /* th,
    td {
        padding: 2px;
        padding-top: 0px;
        text-align: left;
    } */

        .d-flex {
            display: flex;
        }

        .ml-auto {
            margin-left: auto !important;
        }

        .mr-auto {
            margin-right: auto !important;
        }

        .th-primary {
            background-color: #2997d6 !important;
        }

        .fordots {
            display: flex;
        }

        .fordots .first,
        .fordots .end {
            flex: 1 0 auto;
        }

        .fordots .dots {
            flex: 0 1 auto;
            margin: 0 5px;
            overflow: hidden;
        }

        .dots::before {
            display: block;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: clip;
            content:
                "...................."
                "...................."
                "...................."
                "...................."
        }

        .text-center {
            text-align: center !important;
        }

        .table.table-nested {
            border: none !important;
        }

        .table.table-nested tr,
        .table.table-nested td,
        .table.table-nested th {
            padding: 0;
            border: 0;
            border-bottom: 1px solid black;
            text-align: center;
            /* border-right: 1px solid black; */
        }

        .table.table-nested tr:last-child td,
        .table.table-nested tr:last-child th,
        .table.table-nested tr:last-child {
            padding: 0;
            border: 0;
            border-bottom: 1px solid black;
            /* border-right: 1px solid black; */
        }

        .table.table-nested-sec {
            border: none !important;
        }

        .table.table-nested-sec tr,
        .table.table-nested-sec td,
        .table.table-nested-sec th {
            padding: 0;
            border: 0;
            border-bottom: 1px solid black;
            text-align: left;
            padding-left: 2px;
            font-weight: normal;
        }

        .paraf {
            font-size: 12px;
            vertical-align: middle !important;
        }
        .text-small {
            font-size: 9px;
        }
        tr.p-0>td {
            padding: 0.3rem;
        }

        tbody.break>tr:last-child {
            page-break-after: avoid;
            page-break-before: avoid;
            page-break-inside: avoid;
        }
        /* thead { display: table-header-group; } */
        /* tbody { display: table-header-group; } */
        .table.table-nested-sec tr:last-child td,
        .table.table-nested-sec tr:last-child th,
        .table.table-nested-sec tr:last-child {
            padding: 0;
            border: 0;
            padding-left: 2px;
            border-bottom: 1px solid black;
            border-right: 1px solid black;
        }

        .text-right {
            text-align: right;
        }

        .p-0 {
            padding: 0!important;
        }

        .col {
            max-width: 100%;
            width: 100%;
            clear: both;
            padding: 10px; 0;
        }

        .checkbox {
            display: table-cell;
            vertical-align: middle;
            height: 50px;
            border: 1px solid red;
        }

        .float-left {
            float: left !important;
        }

        .float-right {
            float: right !important;
        }

        .w-100 {
            width: 100% !important;
        }
        .w-95 {
            width: 95% !important;
        }
        .w-90 {
            width: 90% !important;
        }
        .w-85 {
            width: 85% !important;
        }
        .w-80 {
            width: 80% !important;
        }
        .w-75 {
            width: 75% !important;
        }
        .w-70 {
            width: 70% !important;
        }
        .w-65 {
            width: 65% !important;
        }
        .w-60 {
            width: 60% !important;
        }
        .w-55 {
            width: 55% !important;
        }
        .w-50 {
            width: 50% !important;
        }
        .w-45 {
            width: 45% !important;
        }
        .w-40 {
            width: 40% !important;
        }
        .w-35 {
            width: 35% !important;
        }
        .w-30 {
            width: 30% !important;
        }
        .w-25 {
            width: 25% !important;
        }
        .w-20 {
            width: 20% !important;
        }
        .w-15 {
            width: 15% !important;
        }
        .w-10 {
            width: 10% !important;
        }
        .w-5 {
            width: 5% !important;
        }

    </style>
</head>

<body>
    <table class="table" style="page-break-inside: avoid !important;">
        <thead>
            <tr>
                <th colspan="12" class="th-primary">
                    <span>RSUD BALI MANDARA</span>
                    <span class="ml-auto" style="font-weight: bold; float: right">RM.6/RAJAL/00</span>
                </th>
            </tr>
        
            <tr style="border-bottom: 1px solid black">
                <th colspan="2" style="text-align: center; vertical-align: center; border-bottom: 1px solid black">
                    <img alt="" src="{{ asset('img/logo-rs.png') }}" border="0" style="width: 70px; text-align:center vertical-align: center" 
                        alt="">
                </th>
                <th colspan="6" style="text-align: center; margin-top: auto !important;border-bottom: 1px solid black;">
                    <div style="text-align: center !important;">
                        <p>
                            FORMULIR KAJIAN RIWAYAT MENYUSUI
                        </p>
                    </div>
                </th>
                <th colspan="4" style="border-bottom: 1px solid black">
                    <table class="table-borderless" cellspacing="0" cellpadding="0" border="0" width="90%" align="center"
                        style="page-break-inside: auto;">
                        <tr>
                            <td width="30%">
                                Nama
                            </td>
                            <td width="1%">
                                :
                            </td>
                            <td width="75%">
                                &nbsp; {{$pasien['namapasien']}}
                            </td>
                        </tr>
                        <tr>
                            <td width="30%">
                                Tgl.Lahir
                            </td>
                            <td width="1%">
                                :
                            </td>
                            <td width="75%">
                                &nbsp; {{ $pasien['tgllahir'] }} {{ $pasien['jeniskelamin'] == "Laki-laki" ? "Lk" : "Pr" }}
                            </td>
                        </tr>
                        <tr>
                            <td width="30%">
                                No.RM
                            </td>
                            <td width="1%">
                                :
                            </td>
                            <td width="75%">
                                &nbsp; {{ $pasien['nocm'] }}
                            </td>
                        </tr>
                    </table>
                </th>
            </tr>
        </thead>
        <td colspan="12" style="height: 850px;">
            <div class="">
                <div class="w-100">
                    <div class="w-65 text-right">
                        <span style="font-weight: bold; font-size: 12px; text-align: center;">
                            CATATAN MEDIK
                        </span>
                    </div>
                </div>
                <div class="col">
                    <div class="float-left w-50">
                        <input type="checkbox" name="" id="" style="padding: 0;" {{ $data['layananperawatan'] == "ANC" ? "checked" : '' }}>
                        <span style="position: absolute; padding: 0; font-size: 11px; margin-top: 1px;">
                            ANC
                        </span>
                    </div>
                    <div class="float-left w-50">
                        <span style="font-size: 12px;">
                            Kunjungan pertama tanggal
                            <span style="text-decoration: underline">
                              {{ $data['tanggalpertama'] ? date("l, d F Y",strtotime($data['tanggalpertama'])) : '' }}
                            </span>
                        </span>
                    </div>
                </div>
                <div class="col" style="margin-top: 10px;">
                    <div class="float-left w-50">
                        <input type="checkbox" name="" id="" style="padding: 0;" {{ $data['layananperawatan'] == "PNC" ? "checked" : '' }}>
                        <span style="position: absolute; padding: 0; font-size: 11px; margin-top: 1px;">
                            PNC
                        </span>
                    </div>
                    <div class="float-left w-50">
                        <span style="font-size: 12px;">
                            No. RM
                            <span style="text-decoration: underline">
                               {{ $data['norm'] ? $data['norm'] : '' }}
                            </span>
                        </span>
                    </div>
                </div>
            </div>
            <div class="" style="margin-top: 1rem;">
                <div class="col" style="margin-bottom: 0!important; padding-bottom: 0px!important;">
                    <div class="w-100">
                        <span style="font-weight: bold; font-size: 12px;">
                            IDENTITAS
                        </span>
                    </div>
                </div>
                <div class="col" style="padding-left: 10px; padding-right:10px; padding-top: 1px;">
                    <div class="w-50 float-left">
                        <div class="w-100">
                            <span style="font-weight: bold; font-size: 12px;">
                                IBU
                            </span>
                        </div>
                        <div class="w-100">
                            <table class="table-borderless">
                                <tr>
                                    <td>
                                        <span style="font-size: 12px;">
                                            Nama
                                        </span>
                                    </td>
                                    <td style="width: 1%">:&nbsp;</td>
                                    <td>
                                    <span style="text-decoration: underline">
                                        {{ $pasien['namapasien'] }}
                                     </span>
                                    </td>
                                </tr>
                                @php
                                    $now = date('Y-m-d H:i:s');
                                    $datetime = new \DateTime(date($pasien['tgllahir']));
                                    $getUmur  = $datetime->diff(new \DateTime($now))->format('%y');
                                @endphp
                                <tr>
                                    <td>
                                        <span style="font-size: 12px;">
                                            Umur
                                        </span>
                                    </td>
                                    <td style="width: 1%">:&nbsp;</td>
                                    <td>
                                        <span style="text-decoration: underline">
                                            {{ $getUmur }} Tahun
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <span style="font-size: 12px;">
                                            Pendidikan
                                        </span>
                                    </td>
                                    <td style="width: 1%">:&nbsp;</td>
                                    <td>
                                        <span style="text-decoration: underline">
                                            {{ !empty($pasien['pendidikan']) ? $pasien['pendidikan'] : ''}}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <span style="font-size: 12px;">
                                            Agama
                                        </span>
                                    </td>
                                    <td style="width: 1%">:&nbsp;</td>
                                    <td>
                                        <span style="text-decoration: underline">
                                            {{ !empty($pasien['agama']) ? $pasien['agama'] : '' }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <span style="font-size: 12px;">
                                            Pekerjaan
                                        </span>
                                    </td>
                                    <td style="width: 1%">:&nbsp;</td>
                                    <td>
                                        <span style="text-decoration: underline">
                                            {{ !empty($pasien['pekerjaan']) ? $pasien['pekerjaan'] : '' }}
                                        </span>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <div class="w-50 float-left">
                        <div class="w-100">
                            <span style="font-weight: bold; font-size: 12px;">
                                SUAMI
                            </span>
                        </div>
                        <div class="w-100">
                            <table class="table-borderless">
                                <tr>
                                    <td>
                                        <span style="font-size: 12px;">
                                            Nama
                                        </span>
                                    </td>
                                    <td style="width: 1%">:&nbsp;</td>
                                    <td>
                                        <span style="text-decoration: underline">
                                            {{ !empty($data['namaayah']) ? $data['namaayah'] : '' }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <span style="font-size: 12px;">
                                            Umur
                                        </span>
                                    </td>
                                    <td style="width: 1%">:&nbsp;</td>
                                    <td>
                                        <span style="text-decoration: underline">
                                            {{ !empty($data['umurayah']) ? $data['umurayah'] : '' }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <span style="font-size: 12px;">
                                            Pendidikan
                                        </span>
                                    </td>
                                    <td style="width: 1%">:&nbsp;</td>
                                    <td>
                                        <span style="text-decoration: underline">
                                            {{ !empty($data['pendidikanayah']) ? $data['pendidikanayah'] : '' }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <span style="font-size: 12px;">
                                            Agama
                                        </span>
                                    </td>
                                    <td style="width: 1%">:&nbsp;</td>
                                    <td>
                                        <span style="text-decoration: underline">
                                            {{ !empty($data['agamaayah']) ? $data['agamaayah'] : '' }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <span style="font-size: 12px;">
                                            Pekerjaan
                                        </span>
                                    </td>
                                    <td style="width: 1%">:&nbsp;</td>
                                    <td>
                                        <span style="text-decoration: underline">
                                            {{ !empty($data['pekerjaanayah']) ? $data['pekerjaanayah'] : '' }}
                                        </span>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <div style="margin-top: 145px;">
                        <table class="table-borderless">
                            <tr>
                                <td style="width: 25.8%;">
                                    <span style="font-size: 12px;">
                                        Alamat
                                    </span>
                                </td>
                                <td style="width: 1%">:&nbsp;</td>
                                <td>
                                    <span style="text-decoration: underline">
                                        {{ !empty($data['alamat']) ? $data['alamat'] : '' }}
                                    </span>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div class="col" style="padding-left: 10px; padding-right:10px;">
                    <div class="w-100" style="margin-bottom: 10px;">
                        <span style="font-weight: bold; font-size: 12px;">
                            Cara Kunjungan
                        </span>
                    </div>
                    <div class="w-50 float-left">
                        <div class="w-100">
                            <input type="checkbox" name="" id="" style="padding: 0;" {{ $data['kunjungan'] == "1" ? 'checked' : '' }}>
                            <span style="position: absolute; padding: 0; font-size: 11px; margin-top: 1px;">
                                Datang Sendiri
                            </span>
                        </div>
                        <div class="w-100">
                            <input type="checkbox" name="" id="" style="padding: 0;" {{ $data['kunjungan'] == "2" ? 'checked' : '' }}>
                            <span style="position: absolute; padding: 0; font-size: 11px; margin-top: 1px;">
                                Dikirim dari Poli ANC
                            </span>
                        </div>
                        <div class="w-100">
                            <input type="checkbox" name="" id="" style="padding: 0;" {{ $data['kunjungan'] == "3" ? 'checked' : '' }}>
                            <span style="position: absolute; padding: 0; font-size: 11px; margin-top: 1px;">
                                Dikirim dari RB / RS : 
                                <span style="text-decoration: underline">
                                    {{ $data['kunjungan'] == "3" ? $data['ketkunjungan'] : '' }}
                                </span>
                            </span>
                        </div>
                    </div>
                    <div class="w-50 float-left">
                        <div class="w-100">
                            <input type="checkbox" name="" id="" style="padding: 0;" {{ $data['kunjungan'] == "4" ? 'checked' : '' }}>
                            <span style="position: absolute; padding: 0; font-size: 11px; margin-top: 1px;">
                                Dikirim dari dr. SpOG :
                                <span style="text-decoration: underline">
                                    {{ $data['kunjungan'] == "4" ? $data['ketkunjungan'] : '' }}
                                </span>
                            </span>
                        </div>
                        <div class="w-100">
                            <input type="checkbox" name="" id="" style="padding: 0;" {{ $data['kunjungan'] == "5" ? 'checked' : '' }}>
                            <span style="position: absolute; padding: 0; font-size: 11px; margin-top: 1px;">
                                Dikirim dari dr. SpA :
                                <span style="text-decoration: underline">
                                    {{ $data['kunjungan'] == "5" ? $data['ketkunjungan'] : '' }}
                                </span>
                            </span>
                        </div>
                        <div class="w-100">
                            <input type="checkbox" name="" id="" style="padding: 0;" {{ $data['kunjungan'] == "6" ? 'checked' : '' }}>
                            <span style="position: absolute; padding: 0; font-size: 11px; margin-top: 1px;">
                                Dikirim dari Kader ASI :
                                <span style="text-decoration: underline">
                                    {{ $data['kunjungan'] == "6" ? $data['ketkunjungan'] : '' }}
                                </span>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="col" style="padding-left: 10px; padding-right:10px;">
                    <div class="w-100">
                        <span style="font-weight: bold; font-size: 12px;">
                            Keluhan Utama
                        </span>
                    </div>
                    <div class="col w-95">
                        <textarea name="" id="" cols="20" rows="10" style="border-radius: 8px; font-size: 12px;">{{ !empty($data['keluhanutama']) ? $data['keluhanutama'] : '' }}</textarea>
                    </div>
                </div>
                <div class="col" style="padding-left: 10px; padding-right:10px;">
                    <div class="w-100" style="margin-bottom: 10px;">
                        <span style="font-weight: bold; font-size: 12px;">
                            RIWAYAT KEHAMILAN, PERSALINAN DAN LAKTASI SEBELUMNYA
                        </span>
                    </div>
                    <div class="col w-100" style="margin-bottom: 20px;">
                        <div class="w-30 float-left">
                            <span style="font-size: 11px; margin-top: 1px;">
                                G : 
                                <span style="text-decoration: underline">
                                    {{ !empty($data['riwayathamilg']) ? $data['riwayathamilg'] : '' }}
                                </span>
                            </span>
                        </div>
                        <div class="w-30 float-left">
                            <span style="font-size: 11px; margin-top: 1px;">
                                P : 
                                <span style="text-decoration: underline">
                                    {{ !empty($data['riwayathamilp']) ? $data['riwayathamilp'] : '' }}
                                </span>
                            </span>
                        </div>
                        <div class="w-30 float-left">
                            <span style="font-size: 11px; margin-top: 1px;">
                                A : 
                                <span style="text-decoration: underline">
                                    {{ !empty($data['riwayathamila']) ? $data['riwayathamila'] : '' }}
                                </span>
                            </span>
                        </div>
                    </div>
                    <div class="w-95">
                        <table class="table">
                            <tr>
                                <th style="width: 5%; font-weight: normal; font-size: 12px;text-align: center">
                                    Anak Ke
                                </th>
                                <th style="width: 5%; font-weight: normal; font-size: 12px;text-align: center">
                                    L/P
                                </th>
                                <th style="width: 10%; font-weight: normal; font-size: 12px;text-align: center">
                                    Umur/Tanggal Lahir
                                </th>
                                <th style="width: 10%; font-weight: normal; font-size: 12px;text-align: center">
                                    Menyusui Ekslusif
                                </th>
                                <th style="width: 10%; font-weight: normal; font-size: 12px;text-align: center">
                                    Umur Disapih
                                </th>
                                <th style="width: 60%; font-weight: normal; font-size: 12px;text-align: center">
                                    Masalah Dalam Menyusui
                                </th>
                            </tr>
                            @foreach ($data['riwayatanak'] as $riwayatanak)
                                <tr style="text-align: center !important;">
                                    <td>{{ $riwayatanak['no'] ?? "" }}</td>
                                    <td>{{ $riwayatanak['jeniskelamin'] ?? "" }}</td>
                                    <td>{{ date("d F Y",strtotime($riwayatanak['tgllahir'])) }}</td>
                                    <td>{{ $riwayatanak['isekslusif'] == "1" ? "Ya" : "Tidak" }}</td>
                                    <td>{{ $riwayatanak['umurdisapih'] ?? "" }}</td>
                                    <td>{{ $riwayatanak['masalahmenyusui'] ?? "" }}</td>
                                </tr>
                            @endforeach
                        </table>
                    </div>
                </div>
            </div>
            {{-- HALAMAN 2 --}}
            <div style="page-break-after: always"></div>
            <div class="" style="margin-top: 1rem;">
                <div class="col">
                    <div class="w-100">
                        <span style="font-weight: bold; font-size: 12px;">
                            KEHAMILAN DAN PERSALINAN TERAKHIR
                        </span>
                    </div>
                </div>
                <div class="col" style="padding-left: 10px; padding-right:10px; padding-top: 1px;">
                    <div class="w-100">
                        <table class="table-borderless no-pad">
                            <tr>
                                <td width="20%">Kehamilan Ini</td>
                                <td width="1%">:</td>
                                <td width="30%">
                                    <input type="checkbox" name="" id="" style="padding: 0;" {{ $data['keinginanhamil'] == '1' ? 'checked' : '' }}>
                                    <span style="position:absolute; padding: 0; font-size: 11px; margin-top: 1px !important;">
                                        Diharapkan
                                    </span>
                                </td>
                                <td width="30%">
                                    <input type="checkbox" name="" id="" style="padding: 0;" {{ $data['keinginanhamil'] == '2' ? 'checked' : '' }}>
                                    <span style="position:absolute; padding: 0; font-size: 11px; margin-top: 1px !important;">
                                        Tidak diharapkan
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td width="20%">Usaha Pengguguran</td>
                                <td width="1%">:</td>
                                <td width="30%">
                                    <input type="checkbox" name="" id="" style="padding: 0;" {{ $data['usahapenguguran'] == '1' ? 'checked' : '' }}>
                                    <span style="position:absolute; padding: 0; font-size: 11px; margin-top: 1px !important;">
                                        Tidak ada
                                    </span>
                                </td>
                                <td width="30%">
                                    <input type="checkbox" name="" id="" style="padding: 0;" {{ $data['usahapenguguran'] == '2' ? 'checked' : '' }}>
                                    <span style="position:absolute; padding: 0; font-size: 11px; margin-top: 1px !important;">
                                        Ada, dengan cara
                                        <span style="text-decoration: underline">
                                            {{ !empty($data['ketusahapenguguran']) ? $data['ketusahapenguguran'] : '' }}
                                        </span>
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td width="20%">Keadaan sekarang</td>
                                <td width="1%">:</td>
                                <td width="30%">
                                    <input type="checkbox" name="" id="" style="padding: 0;" {{ $data['keadaan'] == '1' ? 'checked' : '' }}>
                                    <span style="position:absolute; padding: 0; font-size: 11px; margin-top: 1px !important;">
                                        Masih hamil
                                        <span style="text-decoration: underline">
                                            {{ $data['keadaan'] == '1' && !empty($data['ketkeadaan']) ? $data['ketkeadaan'] : '' }}
                                        </span>
                                    </span>
                                </td>
                                <td width="30%">
                                    <input type="checkbox" name="" id="" style="padding: 0;" {{ $data['keadaan'] == '2' ? 'checked' : '' }}>
                                    <span style="position:absolute; padding: 0; font-size: 11px; margin-top: 1px !important;">
                                        Sudah melahirkan, tanggal
                                        <span style="text-decoration: underline">
                                            {{ !empty($data['ketkeadaan']) ? date('d F Y',strtotime($data['ketkeadaan'])) : '' }}
                                        </span>
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td width="20%">ANC</td>
                                <td width="1%">:</td>
                                <td width="30%">
                                    <input type="checkbox" name="" id="" style="padding: 0;" {{ $data['isanc'] == '1' ? 'checked' : '' }}>
                                    <span style="position:absolute; padding: 0; font-size: 11px; margin-top: 1px !important;">
                                        Tidak
                                    </span>
                                </td>
                                <td width="30%">
                                    <input type="checkbox" name="" id="" style="padding: 0;" {{ $data['isanc'] == '2' ? 'checked' : '' }}>
                                    <span style="position:absolute; padding: 0; font-size: 11px; margin-top: 1px !important;">
                                        Ya, di
                                        <span style="text-decoration: underline">
                                            {{ !empty($data['ketisanc']) ? $data['ketisanc'] : '' }}
                                        </span>
                                    </span>
                                </td>
                            </tr>
                        </table>
                        <table class="table-borderless">
                            <tr>
                                <td width="26.8%">Penyakit Ibu</td>
                                <td width="1%">:</td>
                                <td width="80%">
                                    <span style="text-decoration: underline">
                                        {{ !empty($data['penyakitibu']) ? $data['penyakitibu'] : '' }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td width="26.8%">Penyakit Kehamilan</td>
                                <td width="1%">:</td>
                                <td width="80%">
                                    <span style="text-decoration: underline">
                                        {{ !empty($data['penyakitkehamilan']) ? $data['penyakitkehamilan'] : '' }}
                                    </span>
                                </td>
                            </tr>
                        </table>
                        <table class="table-borderless no-pad" style="margin-top: 1px;margin-bottom: 1px;">
                            <tr>
                                <td width="20%">Melakukan Perawatan</td>
                                <td width="1%">:</td>
                                <td width="30%">
                                    <input type="checkbox" name="" id="" style="padding: 0;" {{ $data['isperawatan'] == '1' ? 'checked' : '' }}>
                                    <span style="position:absolute; padding: 0; font-size: 11px; margin-top: 1px !important;">
                                        Tidak
                                    </span>
                                </td>
                                <td width="30%">
                                    <input type="checkbox" name="" id="" style="padding: 0;" {{ $data['isperawatan'] == '2' ? 'checked' : '' }}>
                                    <span style="position:absolute; padding: 0; font-size: 11px; margin-top: 1px !important;">
                                        Ya
                                    </span>
                                </td>
                            </tr>
                        </table>
                        <table class="table-borderless" style="padding-top: 0px !important; margin-top: 1px !important;">
                            <tr style="padding-top: 0px !important; margin-top: 0px !important;">
                                <td width="26.8%">Puting Susu</td>
                                <td width="1%">:</td>
                                <td width="80%">
                                    <span style="text-decoration: underline">
                                        {{ !empty($data['putingsusu']) ? $data['putingsusu'] : '' }}
                                    </span>
                                </td>
                            </tr>
                        </table>
                        <table class="table-borderless no-pad" style="margin-top: 1px;">
                            <tr>
                                <td width="20%">Kelainan Payudara</td>
                                <td width="1%">:</td>
                                <td width="30%">
                                    <input type="checkbox" name="" id="" style="padding: 0;" {{ $data['kelainanpayudara'] == '1' ? 'checked' : '' }}>
                                    <span style="position:absolute; padding: 0; font-size: 11px; margin-top: 1px !important;">
                                        Tidak ada
                                    </span>
                                </td>
                                <td width="30%">
                                    <input type="checkbox" name="" id="" style="padding: 0;" {{ $data['kelainanpayudara'] == '2' ? 'checked' : '' }}>
                                    <span style="position:absolute; padding: 0; font-size: 11px; margin-top: 1px !important;">
                                        Ada, usaha perbaikan
                                        <span style="text-decoration: underline">
                                            {{ !empty($data['ketkelainanpayudara']) ? $data['ketkelainanpayudara'] : '' }}
                                        </span>
                                    </span>
                                    
                                </td>
                            </tr>
                        </table>
                        <table class="table-borderless">
                            <tr>
                                <td width="26.8%">Obat selama hamil</td>
                                <td width="1%">:</td>
                                <td width="80%">
                                    <span style="text-decoration: underline">
                                        {{ !empty($data['obathamil']) ? $data['obathamil'] : '' }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td width="26.8%">Jamu selama hamil</td>
                                <td width="1%">:</td>
                                <td width="80%">
                                    <span style="text-decoration: underline">
                                        {{ !empty($data['jamuhamil']) ? $data['jamuhamil'] : '' }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td width="26.8%">Obat untuk kelancaran menyusui</td>
                                <td width="1%">:</td>
                                <td width="80%">
                                    <span style="text-decoration: underline">
                                        {{ !empty($data['obatkelancaranmenyusui']) ? $data['obatkelancaranmenyusui'] : '' }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td width="26.8%">Jamu untuk kelancaran menyusui</td>
                                <td width="1%">:</td>
                                <td width="80%">
                                    <span style="text-decoration: underline">
                                        {{ !empty($data['jamukelancaranmenyusui']) ? $data['jamukelancaranmenyusui'] : '' }}
                                    </span>
                                </td>
                            </tr>
                        </table>
                        <table class="table-borderless no-pad">
                            <tr>
                                <td width="20%">Tempat Persalinan</td>
                                <td width="1%">:</td>
                                <td width="30%">
                                    <input type="checkbox" name="" id="" style="padding: 0;" {{ $data['tempatsalin'] == '1' ? 'checked' : '' }}>
                                    <span style="position:absolute; padding: 0; font-size: 11px; margin-top: 1px !important;">
                                        RS
                                        <span style="text-decoration: underline">
                                            {{ $data['tempatsalin'] == '1' && !empty($data['kettempatsalin']) ? $data['kettempatsalin'] : '' }}
                                        </span>
                                    </span>
                                </td>
                                <td width="30%">
                                    <input type="checkbox" name="" id="" style="padding: 0;" {{ $data['tempatsalin'] == '2' ? 'checked' : '' }}>
                                    <span style="position:absolute; padding: 0; font-size: 11px; margin-top: 1px !important;">
                                        RB
                                        <span style="text-decoration: underline">
                                            {{ $data['tempatsalin'] == '2' && !empty($data['kettempatsalin']) ? $data['kettempatsalin'] : '' }}
                                        </span>
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td width="20%"></td>
                                <td width="1%"></td>
                                <td width="30%">
                                    <input type="checkbox" name="" id="" style="padding: 0;" {{ $data['tempatsalin'] == '3' ? 'checked' : '' }}>
                                    <span style="position:absolute; padding: 0; font-size: 11px; margin-top: 1px !important;">
                                        Rumah
                                    </span>
                                </td>
                                <td width="30%">
                                    <input type="checkbox" name="" id="" style="padding: 0;" {{ $data['tempatsalin'] == '4' ? 'checked' : '' }}>
                                    <span style="position:absolute; padding: 0; font-size: 11px; margin-top: 1px !important;">
                                        Lainnya
                                        <span style="text-decoration: underline">
                                            {{ $data['tempatsalin'] == '4' && !empty($data['kettempatsalin']) ? $data['kettempatsalin'] : '' }}
                                        </span>
                                    </span>
                                </td>
                            </tr>
                        </table>
                        <table class="table-borderless no-pad">
                            <tr>
                                <td width="27%">Cara Lahir</td>
                                <td width="1%">:</td>
                                <td width="30%">
                                    <input type="checkbox" name="" id="" style="padding: 0;" {{ $data['caralahir'] == '1' ? 'checked' : '' }}>
                                    <span style="position:absolute; padding: 0; font-size: 11px; margin-top: 1px !important;">
                                        Spontan
                                    </span>
                                </td>
                                <td width="20%">
                                    <input type="checkbox" name="" id="" style="padding: 0;" {{ $data['caralahir'] == '2' ? 'checked' : '' }}>
                                    <span style="position:absolute; padding: 0; font-size: 11px; margin-top: 1px !important;">
                                        Sungsang
                                    </span>
                                </td>
                                <td width="30%">
                                    <input type="checkbox" name="" id="" style="padding: 0;" {{ $data['caralahir'] == '3' ? 'checked' : '' }}>
                                    <span style="position:absolute; padding: 0; font-size: 11px; margin-top: 1px !important;">
                                        Ekstraksi Vakum
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td width="27%"></td>
                                <td width="1%"></td>
                                <td width="30%">
                                    <input type="checkbox" name="" id="" style="padding: 0;" {{ $data['caralahir'] == '4' ? 'checked' : '' }}>
                                    <span style="position:absolute; padding: 0; font-size: 11px; margin-top: 1px !important;">
                                        Forceps
                                    </span>
                                </td>
                                <td width="20%">
                                    <input type="checkbox" name="" id="" style="padding: 0;" {{ $data['caralahir'] == '5' ? 'checked' : '' }}>
                                    <span style="position:absolute; padding: 0; font-size: 11px; margin-top: 1px !important;">
                                        SC
                                    </span>
                                </td>
                                <td width="30%">
                                    <input type="checkbox" name="" id="" style="padding: 0;" {{ $data['caralahir'] == '6' ? 'checked' : '' }}>
                                    <span style="position:absolute; padding: 0; font-size: 11px; margin-top: 1px !important;">
                                        Lainnya
                                        <span style="text-decoration: underline">
                                            {{ !empty($data['ketcaralahir']) ? $data['ketcaralahir'] : '' }}
                                        </span>
                                    </span>
                                </td>
                            </tr>
                        </table>               
                        <table class="table-borderless no-pad">
                            <tr>
                                <td width="26.8%">Keadaan Bayi</td>
                                <td width="1%">:</td>
                                <td width="80%">
                                    <span style="text-decoration: underline">
                                        {{ !empty($data['keadaanbayi']) ? $data['keadaanbayi'] : '' }}
                                    </span>
                                </td>
                            </tr>
                        </table>
                        <table class="table-borderless no-pad">
                            <tr>
                                <td width="26.8%">Skor APGAR</td>
                                <td width="1%">:</td>
                                <td width="80%">
                                    <input type="checkbox" name="" id="" style="padding: 0;" {{ $data['apgar'] == '1' ? 'checked' : '' }}>
                                    <span style="position:absolute; padding: 0; font-size: 11px; margin-top: 1px !important;">
                                        Tidak Tahu
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td width="20%"></td>
                                <td width="1%"></td>
                                <td width="30%">
                                    <input type="checkbox" name="" id="" style="padding: 0;" {{ $data['apgar'] == '2' ? 'checked' : '' }}>
                                    <span style="position:absolute; padding: 0; font-size: 11px; margin-top: 1px !important;">
                                        Tahu &nbsp;
                                        1 Menit 
                                        <span style="text-decoration: underline">
                                            {{ $data['apgar'] == '2' && !empty($data['ketapgarsatu']) ? $data['ketapgarsatu'] : '' }}
                                        </span>
                                        5 Menit
                                        <span style="text-decoration: underline">
                                            {{ $data['apgar'] == '2' && !empty($data['ketapgarlima']) ? $data['ketapgarlima'] : '' }}
                                        </span>
                                    </span>
                                </td>
                            </tr>
                        </table>
                        <table class="table-borderless no-pad">
                            <tr>
                                <td width="20%">Jumlah Bayi</td>
                                <td width="1%">:</td>
                                <td width="30%">
                                    <input type="checkbox" name="" id="" style="padding: 0;" {{ $data['jumlahbayi'] == '1' ? 'checked' : '' }}>
                                    <span style="position:absolute; padding: 0; font-size: 11px; margin-top: 1px !important;">
                                        Tunggal
                                    </span>
                                </td>
                                <td width="30%">
                                    <input type="checkbox" name="" id="" style="padding: 0;" {{ $data['jumlahbayi'] == '2' ? 'checked' : '' }}>
                                    <span style="position:absolute; padding: 0; font-size: 11px; margin-top: 1px !important;">
                                        Kembar
                                    </span>
                                </td>
                            </tr>
                        </table>
                        <table class="table-borderless">
                            <tr>
                                <td width="26.8%">Berat Bayi</td>
                                <td width="1%">:</td>
                                <td width="80%">
                                    <span style="text-decoration: underline">
                                        {{ !empty($data['beratlahir']) ? $data['beratlahir'] : '' }}
                                    </span>
                                    gram
                                </td>
                            </tr>
                            <tr>
                                <td width="26.8%">Panjang Lahir</td>
                                <td width="1%">:</td>
                                <td width="80%">
                                    <span style="text-decoration: underline">
                                        {{ !empty($data['panjang']) ? $data['panjang'] : '' }}
                                    </span>
                                    cm
                                </td>
                            </tr>
                            <tr>
                                <td width="26.8%">Kelainan bawaan</td>
                                <td width="1%">:</td>
                                <td width="80%">
                                    <span style="text-decoration: underline">
                                        {{ !empty($data['kelainanbawaan']) ? $data['kelainanbawaan'] : '' }}
                                    </span>
                                </td>
                            </tr>
                        </table>
                        <table class="table-borderless no-pad" style="margin-top: 1px;">
                            <tr>
                                <td width="20%">IMD</td>
                                <td width="1%">:</td>
                                <td width="30%">
                                    <input type="checkbox" name="" id="" style="padding: 0;" {{ $data['imd'] == '1' ? 'checked' : '' }}>
                                    <span style="position:absolute; padding: 0; font-size: 11px; margin-top: 1px !important;">
                                        Ya
                                    </span>
                                </td>
                                <td width="30%">
                                    <input type="checkbox" name="" id="" style="padding: 0;" {{ $data['imd'] == '2' ? 'checked' : '' }}>
                                    <span style="position:absolute; padding: 0; font-size: 11px; margin-top: 1px !important;">
                                        Tidak, alasan
                                        <span style="text-decoration: underline">
                                            {{ !empty($data['ketimd']) ? $data['ketimd'] : '' }}
                                        </span>
                                    </span>
                                    
                                </td>
                            </tr>
                            <tr>
                                <td width="20%">Rawat Gabung</td>
                                <td width="1%">:</td>
                                <td width="30%">
                                    <input type="checkbox" name="" id="" style="padding: 0;" {{ $data['rawatgabung'] == '1' ? 'checked' : '' }}>
                                    <span style="position:absolute; padding: 0; font-size: 11px; margin-top: 1px !important;">
                                        Ya
                                    </span>
                                </td>
                                <td width="30%">
                                    <input type="checkbox" name="" id="" style="padding: 0;" {{ $data['rawatgabung'] == '2' ? 'checked' : '' }}>
                                    <span style="position:absolute; padding: 0; font-size: 11px; margin-top: 1px !important;">
                                        Tidak, alasan
                                        <span style="text-decoration: underline">
                                            {{ !empty($data['ketrawatgabung']) ? $data['ketrawatgabung'] : '' }}
                                        </span>
                                    </span>
                                </td>
                            </tr>
                        </table>
                        <table class="table-borderless">
                            <tr>
                                <td width="26.8%">Mulai menyusui</td>
                                <td width="1%">:</td>
                                <td width="80%">
                                    <span style="text-decoration: underline">
                                        {{ !empty($data['mulaimenyusui']) ? $data['mulaimenyusui'] : '' }}
                                    </span>
                                    jam sesudah berakhir
                                </td>
                            </tr>
                        </table>
                        <table class="table-borderless no-pad" style="margin-top: 1px;">
                            <tr>
                                <td width="20%">Minuman Bayi</td>
                                <td width="1%">:</td>
                                <td width="30%">
                                    <input type="checkbox" name="" id="" style="padding: 0;" {{ $data['minumanbayi'] == '1' ? 'checked' : '' }}>
                                    <span style="position:absolute; padding: 0; font-size: 11px; margin-top: 1px !important;">
                                      Susu formula
                                    </span>
                                </td>
                                <td width="30%">
                                    <input type="checkbox" name="" id="" style="padding: 0;" {{ $data['minumanbayi'] == '2' ? 'checked' : '' }}>
                                    <span style="position:absolute; padding: 0; font-size: 11px; margin-top: 1px !important;">
                                        Air gula
                                    </span>
                                    
                                </td>
                            </tr>
                            <tr>
                                <td width="20%">Selain ASI</td>
                                <td width="1%">:</td>
                                <td width="30%">
                                    <input type="checkbox" name="" id="" style="padding: 0;" {{ $data['selainasi'] == '1' ? 'checked' : '' }}>
                                    <span style="position:absolute; padding: 0; font-size: 11px; margin-top: 1px !important;">
                                        Air putih
                                    </span>
                                </td>
                                <td width="30%">
                                    <input type="checkbox" name="" id="" style="padding: 0;" {{ $data['selainasi'] == '2' ? 'checked' : '' }}>
                                    <span style="position:absolute; padding: 0; font-size: 11px; margin-top: 1px !important;">
                                        Lainnya
                                        <span style="text-decoration: underline">
                                            {{ !empty($data['ketselainasi']) ? $data['ketselainasi'] : '' }}
                                        </span>
                                    </span>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            {{-- Halaman 3 --}}
            <div style="page-break-after: always"></div>
            <div class="" style="margin-top: 1rem;">
                <div class="col" style="padding: 1px !important;">
                    <div class="w-100">
                        <span style="font-weight: bold; font-size: 12px;">
                            PEMERIKSAAN FISIK
                        </span>
                    </div>
                </div>
                <div class="col" style="padding: 1px !important">
                    <div class="col" style="padding: 1px !important;">
                        <div class="w-100">
                            <span style="font-weight: bold; font-size: 12px;">
                                IBU
                            </span>
                        </div>
                    </div>
                    <div class="w-100">
                        <table class="table-borderless">
                            <tr>
                                <td width="26.8%">Keadaan umum</td>
                                <td width="1%">:</td>
                                <td width="80%">
                                    <span style="text-decoration: underline">
                                        {{ !empty($data['keadaanumumibu']) ? $data['keadaanumumibu'] : '' }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td width="26.8%">Berat (Kg)/Tinggi Badan (cm)</td>
                                <td width="1%">:</td>
                                <td width="80%">
                                    <span style="text-decoration: underline">
                                        {{ !empty($data['beratibu']) ? $data['beratibu'] : '' }}
                                    </span>
                                    Kg/
                                    <span style="text-decoration: underline">
                                        {{ !empty($data['tinggiibu']) ? $data['tinggiibu'] : '' }}
                                    </span>
                                    cm
                                </td>
                            </tr>
                            <tr>
                                <td width="26.8%">Psikis kesan</td>
                                <td width="1%">:</td>
                                <td width="80%">
                                    <span style="text-decoration: underline">
                                        {{ !empty($data['psikiskesanibu']) ? $data['psikiskesanibu'] : '' }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td width="26.8%">Keadaan Payudara</td>
                                <td width="1%">:</td>
                                <td width="80%">
                                    <span style="text-decoration: underline">
                                        {{ !empty($data['keadaanpayudaraibu']) ? $data['keadaanpayudaraibu'] : '' }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td width="26.8%">Kelainan Payudara</td>
                                <td width="1%">:</td>
                                <td width="80%">
                                    <span style="text-decoration: underline">
                                        {{ !empty($data['kelainanpayudaraibu']) ? $data['kelainanpayudaraibu'] : '' }}
                                    </span>
                                </td>
                            </tr>
                        </table>
                        <table class="table-borderless no-pad">
                            <tr>
                                <td width="20%">Kesulitan Menyusui</td>
                                <td width="1%">:</td>
                                <td width="30%">
                                    <input type="checkbox" name="" id="" style="padding: 0;" {{ $data['kesulitanmenyusui'] == '1' ? 'checked' : '' }}>
                                    <span style="position:absolute; padding: 0; font-size: 11px; margin-top: 1px !important;">
                                        Tidak ada
                                    </span>
                                </td>
                                <td width="30%">
                                    <input type="checkbox" name="" id="" style="padding: 0;" {{ $data['kesulitanmenyusui'] == '2' ? 'checked' : '' }}>
                                    <span style="position:absolute; padding: 0; font-size: 11px; margin-top: 1px !important;">
                                        Ada,
                                        Sebab
                                        <span style="text-decoration: underline">
                                            {{ !empty($data['ketkesulitanmenyusui']) ? $data['ketkesulitanmenyusui'] : '' }}
                                        </span>
                                    </span>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="col" style="padding: 1px !important;">
                        <div class="w-100">
                            <span style="font-weight: bold; font-size: 12px;">
                                BAYI
                            </span>
                        </div>
                    </div>
                    <div class="w-100" style="padding-top: 1px !important; padding-bottom: 1px !important;">
                        <table class="table-borderless">
                            <tr>
                                <td width="26.8%">Jenis Kelamin</td>
                                <td width="1%">:</td>
                                <td width="80%">
                                    <span style="text-decoration: underline">
                                        {{ !empty($data['jeniskelaminbayi']) && $data['jeniskelaminbayi'] == "L" ? "Laki - Laki" : 'Perempuan' }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td width="26.8%">Umur</td>
                                <td width="1%">:</td>
                                <td width="80%">
                                    <span style="text-decoration: underline">
                                        {{ !empty($data['bulanumurbayi']) ? $data['bulanumurbayi'] : '' }}
                                    </span>
                                    bulan
                                    <span style="text-decoration: underline">
                                        {{ $data['hariumurbayi'] ? $data['hariumurbayi'] : '' }}
                                    </span>
                                    hari
                                </td>
                            </tr>
                            <tr>
                                <td width="26.8%">Keadaan Umum</td>
                                <td width="1%">:</td>
                                <td width="80%">
                                    <span style="text-decoration: underline">
                                        {{ !empty($data['keadaanumumbayi']) ? $data['keadaanumumbayi'] : '' }}
                                    </span>
                                </td>
                            </tr>
                        </table>
                        <table class="table-borderless no-pad">
                            <tr>
                                <td width="20%">Refleks menghisap</td>
                                <td width="1%">:</td>
                                <td width="30%">
                                    <input type="checkbox" name="" id="" style="padding: 0;" {{ $data['reflekshisapbayi'] == '1' ? 'checked' : '' }}>
                                    <span style="position:absolute; padding: 0; font-size: 11px; margin-top: 1px !important;">
                                        Baik
                                    </span>
                                </td>
                                <td width="30%">
                                    <input type="checkbox" name="" id="" style="padding: 0;" {{ $data['reflekshisapbayi'] == '2' ? 'checked' : '' }}>
                                    <span style="position:absolute; padding: 0; font-size: 11px; margin-top: 1px !important;">
                                        Tidak
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td width="20%">Refleks menelan</td>
                                <td width="1%">:</td>
                                <td width="30%">
                                    <input type="checkbox" name="" id="" style="padding: 0;" {{ $data['refleksnelanbayi'] == '1' ? 'checked' : '' }}>
                                    <span style="position:absolute; padding: 0; font-size: 11px; margin-top: 1px !important;">
                                        Baik
                                    </span>
                                </td>
                                <td width="30%">
                                    <input type="checkbox" name="" id="" style="padding: 0;" {{ $data['refleksnelanbayi'] == '2' ? 'checked' : '' }}>
                                    <span style="position:absolute; padding: 0; font-size: 11px; margin-top: 1px !important;">
                                        Tidak
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td width="20%">Kelainan</td>
                                <td width="1%">:</td>
                                <td width="30%">
                                    <input type="checkbox" name="" id="" style="padding: 0;" {{ $data['kelainanbayi'][0] !== null ? 'checked' : '' }}>
                                    <span style="position:absolute; padding: 0; font-size: 11px; margin-top: 1px !important;">
                                        Stomatitis
                                    </span>
                                </td>
                                <td width="30%">
                                    <input type="checkbox" name="" id="" style="padding: 0;" {{ $data['kelainanbayi'][1] !== null  ? 'checked' : '' }}>
                                    <span style="position:absolute; padding: 0; font-size: 11px; margin-top: 1px !important;">
                                        Muntah
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td width="20%"></td>
                                <td width="1%"></td>
                                <td width="30%">
                                    <input type="checkbox" name="" id="" style="padding: 0;" {{ $data['kelainanbayi'][2] !== null ? 'checked' : '' }}>
                                    <span style="position:absolute; padding: 0; font-size: 11px; margin-top: 1px !important;">
                                        Labio/gnato/palatoskizis
                                    </span>
                                </td>
                                <td width="30%">
                                    <input type="checkbox" name="" id="" style="padding: 0;" {{ $data['kelainanbayi'][3] !== null ? 'checked' : '' }}>
                                    <span style="position:absolute; padding: 0; font-size: 11px; margin-top: 1px !important;">
                                        Panas
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td width="20%"></td>
                                <td width="1%"></td>
                                <td width="30%">
                                    <input type="checkbox" name="" id="" style="padding: 0;" {{ $data['kelainanbayi'][4] !== null ? 'checked' : '' }}>
                                    <span style="position:absolute; padding: 0; font-size: 11px; margin-top: 1px !important;">
                                        Sindrom down
                                    </span>
                                </td>
                                <td width="30%">
                                    <input type="checkbox" name="" id="" style="padding: 0;" {{ $data['kelainanbayi'][5] !== null ? 'checked' : '' }}>
                                    <span style="position:absolute; padding: 0; font-size: 11px; margin-top: 1px !important;">
                                        Diare
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td width="20%"></td>
                                <td width="1%"></td>
                                <td width="30%">
                                    <input type="checkbox" name="" id="" style="padding: 0;" {{ $data['kelainanbayi'][6] !== null ? 'checked' : '' }}>
                                    <span style="position:absolute; padding: 0; font-size: 11px; margin-top: 1px !important;">
                                        Kelainan congenetial lain
                                    </span>
                                </td>
                                <td width="30%">
                                    <input type="checkbox" name="" id="" style="padding: 0;" {{ $data['kelainanbayi'][7] !== null ? 'checked' : '' }}>
                                    <span style="position:absolute; padding: 0; font-size: 11px; margin-top: 1px !important;">
                                        Tongue tie
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td width="20%"></td>
                                <td width="1%"></td>
                                <td width="30%">
                                    <input type="checkbox" name="" id="" style="padding: 0;" {{ $data['kelainanbayi'][8] !== null ? 'checked' : '' }}>
                                    <span style="position:absolute; padding: 0; font-size: 11px; margin-top: 1px !important;">
                                        Ikterus
                                    </span>
                                </td>
                                <td width="30%">
                                    <input type="checkbox" name="" id="" style="padding: 0;" {{ $data['kelainanbayi'][9] !== null ? 'checked' : '' }}>
                                    <span style="position:absolute; padding: 0; font-size: 11px; margin-top: 1px !important;">
                                        BBLR
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td width="20%"></td>
                                <td width="1%"></td>
                                <td width="30%">
                                    <input type="checkbox" name="" id="" style="padding: 0;" {{ $data['kelainanbayi'][10] !== null ? 'checked' : '' }}>
                                    <span style="position:absolute; padding: 0; font-size: 11px; margin-top: 1px !important;">
                                        Lainnya
                                        <span style="text-decoration: underline">
                                            {{ !empty($data['ketkelainanbayi']) ? $data['ketkelainanbayi'] : '' }}
                                        </span>
                                    </span>
                                </td>
                                <td width="30%">

                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="col" style="padding: 1px !important;">
                        <div class="w-100">
                            <span style="font-weight: bold; font-size: 12px;">
                                OBSERVASI TEKNIK MENYUSUI
                            </span>
                        </div>
                    </div>
                    <div class="w-100">
                        <table class="table-borderless no-pad">
                            <tr>
                                <td width="20%">Teknik Menyusui</td>
                                <td width="1%">:</td>
                                <td width="30%">
                                    <input type="checkbox" name="" id="" style="padding: 0;" {{ $data['teknikmenyusuiibu'] == '1' ? 'checked' : '' }}>
                                    <span style="position:absolute; padding: 0; font-size: 11px; margin-top: 1px !important;">
                                        Baik
                                    </span>
                                </td>
                                <td width="30%">
                                    <input type="checkbox" name="" id="" style="padding: 0;" {{ $data['teknikmenyusuiibu'] == '2' ? 'checked' : '' }}>
                                    <span style="position:absolute; padding: 0; font-size: 11px; margin-top: 1px !important;">
                                        Kurang
                                    </span>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="col" style="padding: 1px !important;">
                        <div class="w-100">
                            <span style="font-weight: bold; font-size: 12px;">
                                Catatan ( Jelaskan kurangnya )
                            </span>
                        </div>
                        <div class="col w-95">
                            <textarea name="" id="" cols="20" rows="10" style="border-radius: 8px">{{ !empty($data['catatan']) ? $data['catatan'] : '' }}</textarea>
                        </div>
                    </div>
                    <div class="col w-100" style="padding: 1px !important;">
                        <span style="font-weight: bold; font-size: 12px;">
                            RENCANA TINDAK LANJUT
                        </span>
                    </div>
                    <div class="w-100">
                        <table class="table-borderless no-pad">
                            <tr>
                                <td>
                                    <input type="checkbox" name="" id="" style="padding: 0;" {{ $data['rencanatindak'] == '1' ? 'checked' : '' }}>
                                    <span style="position:absolute; padding: 0; font-size: 11px; margin-top: 1px !important;">
                                        Bimbingan Langsung
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <input type="checkbox" name="" id="" style="padding: 0;" {{ $data['rencanatindak'] == '2' ? 'checked' : '' }}>
                                    <span style="position:absolute; padding: 0; font-size: 11px; margin-top: 1px !important;">
                                        Dirujuk langsung, ke
                                        <span style="text-decoration: underline">
                                            {{ $data['rencanatindak'] == '2' && !empty($data['ketrencanatindak']) ? $data['ketrencanatindak'] : '' }}
                                        </span>
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <input type="checkbox" name="" id="" style="padding: 0;" {{ $data['rencanatindak'] == '3' ? 'checked' : '' }}>
                                    <span style="position:absolute; padding: 0; font-size: 11px; margin-top: 1px !important;">
                                        Pengobatan
                                        <span style="text-decoration: underline">
                                            {{ $data['rencanatindak'] == '3' && !empty($data['ketrencanatindak']) ? $data['ketrencanatindak'] : '' }}
                                        </span>
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <input type="checkbox" name="" id="" style="padding: 0;" {{ $data['rencanatindak'] == '4' ? 'checked' : '' }}>
                                    <span style="position:absolute; padding: 0; font-size: 11px; margin-top: 1px !important;">
                                        Kunjungan ulang, tanggal
                                        <span style="text-decoration: underline">
                                            {{ $data['rencanatindak'] == '4' && !empty($data['ketrencanatindak']) ? $data['ketrencanatindak'] : '' }}
                                        </span>
                                    </span>
                                </td>
                            </tr>

                        </table>
                    </div>
                </div>
            </div>
            {{-- Halaman 4 --}}
            <div style="page-break-after: always"></div>
            <div class="" style="margin-top: 1rem;">
                <div class="col">
                    <div class="w-100">
                        <span style="font-weight: bold; font-size: 12px;">
                            Lembaran Tindak Lanjut
                        </span>
                    </div>
                </div>
                <div class="col" style="padding: 1px !important">
                    <div class="w-100">
                        <table class="table">
                            <tr style="font-size: 12px">
                                <th style="font-weight: normal">Tanggal</th>
                                <th style="font-weight: normal; text-align: center;">Keluhan Ibu hamil / menyusui keadaan payudara</th>
                                <th style="font-weight: normal">Penanggulangan</th>
                            </tr>
                            @foreach ($data['lembarantindaklanjut'] as $rencanatindak)
                                <tr>
                                    <td>
                                        {{ date("d F Y", strtotime($rencanatindak['tgltindaklanjut'])) }}
                                    </td>
                                    <td>
                                        {{ $rencanatindak['keluhanmenyusui'] }}
                                    </td>
                                    <td>
                                        {{ $rencanatindak['penanggulangan'] }}
                                    </td>
                                </tr>
                            @endforeach
                        </table>
                    </div>
                </div>
            </div>
        </td>
    </table>
</body>
</html>
