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
    function convertToMakassarTime($isoDateString)
    {
        $date = new DateTime($isoDateString, new DateTimeZone('UTC'));
        $date->setTimezone(new DateTimeZone('Asia/Jakarta'));
        return $date->format('H:i:s');
    }

    function isChecked($data, $key)
    {
        return isset($data[$key]) && $data[$key] != '' ? 'checked' : '';
    }
@endphp

<head>
    <title>Asuhan Keperawatan Dan Observasi Pasien Hemodialisa</title>
    <style>
        * {
            font-family: Arial, Helvetica, sans-serif;
        }

        .bold {
            font-weight: bold;
        }

        table {
            border-collapse: collapse !important;
            width: 100% !important;
            page-break-inside: auto;
        }

        /* .table-1 {
            border: 0px;
            width: 100% !important;
            page-break-inside: auto;
        } */

        tr {
            page-break-inside: auto;
            page-break-after: avoid
        }

        .pd td {
            padding: 3px;
            text-align: center;
            font-size: 10pt;
        }

        .fnt {
            font-size: 8pt;
        }

        .fnt th {
            padding: 5px;
        }

        .bt {
            border-top: 1px solid black;
        }

        .fnt td {
            border-top: 1px solid black;
            vertical-align: top;
            padding: 3px;
        }

        input[type=checkbox]:before {
            font-family: DejaVu Sans !important;
            margin-bottom: -5px;
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
        .logo {
            font-family: DejaVu Sans !important;
        }
    </style>
</head>

<body>
    @foreach ($data as $index => $d)
        <table width="100%" cellspacing="0" cellpadding="0" style="border-collapse: collapse;">
            <thead>
                <tr>
                    <th colspan="2"
                        style="background-color: lightblue; padding:5px; font-weight:bold; width: 50%;text-align: left;border-top: 1px solid black;border-left:1px solid black">
                        RSUD BALI MANDARA
                    </th>
                    <th
                        style="background-color: lightblue; padding:5px; font-weight:bold; width: 50%; text-align: right;border-top: 1px solid black;border-right:1px solid black">
                        Halaman-{{ $index + 1 }}
                    </th>
                </tr>
                <tr>
                    <td width="10%" style="text-align: center;padding: 10px" class="border">
                        <img src="{{ 'img/logo-rs.png' }}" width="65px" height="65px" style="display: block;">
                    </td>
                    <td width="40%"
                        style="vertical-align: middle;text-align: center;font-weight: bold;font-size: large"
                        class="border">
                        Asuhan Keperawatan Dan Observasi Pasien Hemodialisa
                    </td>
                    <td width="40%" style="padding: 5px" class="border">
                        <table style="width: 100%">
                            <tr style="font-size: 10pt">
                                <td style="text-align:left;width: 40%">Nama</td>
                                <td style="text-align:left;width: 10%;text-align: center">:</td>
                                <td style="text-align:left;width: 50%">{{ $pasien['namapasien'] }}</td>
                            </tr>
                            <tr style="font-size: 10pt">
                                <td style="text-align:left;width: 40%">Tanggal Lahir</td>
                                <td style="text-align:left;width: 10%;text-align: center">:</td>
                                <td style="text-align:left;width: 50%">{{ $pasien['tgllahir'] }}</td>
                            </tr>
                            <tr style="font-size: 10pt">
                                <td style="text-align:left;width: 40%">Jenis Kelamin</td>
                                <td style="text-align:left;width: 10%;text-align: center">:</td>
                                <td style="text-align:left;width: 50%">{{ $pasien['jeniskelamin'] }}</td>
                            </tr>
                            <tr style="font-size: 10pt">
                                <td style="text-align:left;width: 40%">No. Rekam Medis</td>
                                <td style="text-align:left;width: 10%;text-align: center">:</td>
                                <td style="text-align:left;width: 50%">{{ $pasien['nocm'] }}</td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td colspan="3" class="border">
                        <table class="fnt">
                            <tr>
                                <th style="text-align:left;background-color:rgb(170, 168, 168)">
                                    <b>PENGKAJIAN</b> 
                                </th>
                            </tr>
                            <tr>
                                <td style="padding: 0px">
                                    <table class="table-1">
                                        <tr>
                                            <td>
                                                <b>Tanggal :</b>
                                                {{ isset($d['tanggalKunjunganPasien']) ? convertToMakassarDate($d['tanggalKunjunganPasien']) : '-' }}<br>
                                            </td>
                                            <td style="border-bottom: 0px">
                                                <b>Jam :</b>
                                                {{ isset($d['tanggalKunjunganPasien']) ? convertToMakassarTime($d['tanggalKunjunganPasien']) : '-' }}<br>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2" style="border-top: 0px">
                                                <b>Jenis Pasien :</b>
                                                <input type="checkbox" style="vertical-align: bottom;"
                                                {{ isset($d['jenispasien']) && $d['jenispasien'] == 'RawatJalan' ? 'checked' : '' }} />
                                                <span style="font-size: 9pt; color: #000000; vertical-align: bottom;">Rawat Jalan</span>
                                                <input type="checkbox" style="vertical-align: bottom;"
                                                {{ isset($d['jenispasien']) && $d['jenispasien'] == 'RawatInap' ? 'checked' : '' }} />
                                                <span style="font-size: 9pt; color: #000000; vertical-align: bottom;">Rawat Inap</span>
                                                <input type="checkbox" style="vertical-align: bottom;"
                                                {{ isset($d['jenispasien']) && $d['jenispasien'] == 'Cito' ? 'checked' : '' }} />
                                                <span style="font-size: 9pt; color: #000000; vertical-align: bottom;">Cito</span>
                                                <input type="checkbox" style="vertical-align: bottom;"
                                                {{ isset($d['jenispasien']) && $d['jenispasien'] == 'TravellingHD' ? 'checked' : '' }} />
                                                <span style="font-size: 9pt; color: #000000; vertical-align: bottom;">Travelling HD</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2" style="border-top: 0px">
                                                <b>Jenis Tindakan Hemodialisis :</b>
                                                <input type="checkbox" style="vertical-align: bottom;"
                                                {{ isset($d['jenishd']) && $d['jenishd'] == 'Cito' ? 'checked' : '' }} />
                                                <span style="font-size: 9pt; color: #000000; vertical-align: bottom;">Cito</span>
                                                <input type="checkbox" style="vertical-align: bottom;"
                                                {{ isset($d['jenishd']) && $d['jenishd'] == 'Reguler' ? 'checked' : '' }} />
                                                <span style="font-size: 9pt; color: #000000; vertical-align: bottom;">Reguler</span>
                                                <input type="checkbox" style="vertical-align: bottom;"
                                                {{ isset($d['jenishd']) && $d['jenishd'] == 'Elektif' ? 'checked' : '' }} />
                                                <span style="font-size: 9pt; color: #000000; vertical-align: bottom;">Elektif</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2" style="border-top: 0px">
                                                <b>ANAMNESA</b><br>
                                                <b>Keluhan Utama :</b>
                                                {{ isset($d['keluhanUtama']) ? $d['keluhanUtama'] : '-' }}<br>
                                                <b>Keluhan Saat Dikaji :</b>
                                                {{ isset($d['keluhanSaatDikaji']) ? $d['keluhanSaatDikaji'] : '-' }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2" style="text-align:left;background-color:rgb(170, 168, 168)">
                                                <b>PEMERIKSAAN FISIK</b>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <td style="padding: 0px">
                                    <table>
                                        <tr>
                                            <td style="border-right:1px solid black;vertical-align: middle;">
                                               Kesadaran
                                            </td>
                                            <td style="border-right:1px solid black">
                                                <input type="checkbox" style="vertical-align: bottom;"
                                                {{ isset($d['kesadaran']) && $d['kesadaran'] == 'Sadar' ? 'checked' : '' }} />
                                                <span style="font-size: 9pt; color: #000000; vertical-align: bottom;">Sadar</span>
                                            </td>
                                            <td style="border-right:1px solid black">
                                                <input type="checkbox" style="vertical-align: bottom;"
                                                {{ isset($d['kesadaran']) && $d['kesadaran'] == 'Tidak Sadar' ? 'checked' : '' }} />
                                                <span style="font-size: 9pt; color: #000000; vertical-align: bottom;">Tidak Sadar</span>
                                            </td>
                                            <td style="border-right:1px solid black">
                                                <input type="checkbox" style="vertical-align: bottom;"
                                                {{ isset($d['kesadaran']) && $d['kesadaran'] == 'Apatis' ? 'checked' : '' }} />
                                                <span style="font-size: 9pt; color: #000000; vertical-align: bottom;">Apatis</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="border-right:1px solid black;vertical-align: middle;">
                                                kesadaran Umum
                                            </td>
                                            <td style="border-right:1px solid black">
                                                <input type="checkbox" style="vertical-align: bottom;"
                                                {{ isset($d['kesadaranUmum']) && $d['kesadaranUmum'] == 'Baik' ? 'checked' : '' }} />
                                                <span style="font-size: 9pt; color: #000000; vertical-align: bottom;">Baik</span>
                                            </td>
                                            <td style="border-right:1px solid black">
                                                <input type="checkbox" style="vertical-align: bottom;"
                                                {{ isset($d['kesadaranUmum']) && $d['kesadaranUmum'] == 'Lemah' ? 'checked' : '' }} />
                                                <span style="font-size: 9pt; color: #000000; vertical-align: bottom;">Lemah</span>
                                            </td>
                                            <td style="border-right:1px solid black">
                                                <input type="checkbox" style="vertical-align: bottom;"
                                                {{ isset($d['kesadaranUmum']) && $d['kesadaranUmum'] == 'Sangat Lemah' ? 'checked' : '' }} />
                                                <span style="font-size: 9pt; color: #000000; vertical-align: bottom;">Sangat Lemah</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2" style="border-right:1px solid black">
                                                Tekanan Darah :
                                                {{ isset($d['tekananDarah']) ? $d['tekananDarah'] : '.......' }}mmHg
                                            </td>
                                            <td colspan="2" style="border-right:1px solid black">
                                                Suhu :
                                                {{ isset($d['suhu']) ? $d['suhu'] : '.......' }}°C
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2" style="border-right:1px solid black;vertical-align: middle;">
                                                Nadi :
                                                {{ isset($d['nadi']) ? $d['nadi'] : '.......' }}x/menit
                                            </td>
                                            <td style="border-right:1px solid black">
                                                <input type="checkbox" style="vertical-align: bottom;"
                                                {{ isset($d['nadikeadaan']) && $d['nadikeadaan'] == 'Reguler' ? 'checked' : '' }} />
                                                <span style="font-size: 9pt; color: #000000; vertical-align: bottom;">Reguler</span>
                                            </td>
                                            <td style="border-right:1px solid black">
                                                <input type="checkbox" style="vertical-align: bottom;"
                                                {{ isset($d['nadikeadaan']) && $d['nadikeadaan'] == 'Irreguler' ? 'checked' : '' }} />
                                                <span style="font-size: 9pt; color: #000000; vertical-align: bottom;">Irreguler</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td  style="border-right:1px solid black;vertical-align: middle;">
                                                Respirasi
                                            </td>
                                            <td style="border-right:1px solid black;vertical-align: middle;">
                                                Frekuensi :
                                                {{ isset($d['Respirasi']) ? $d['Respirasi'] : '.......' }}x/menit
                                            </td>
                                            <td colspan="2" style="border-right:1px solid black">
                                                <input type="checkbox" style="vertical-align: bottom;"
                                                {{ isset($d['respirasiKeadaan']) && $d['respirasiKeadaan'] == 'Ronchi' ? 'checked' : '' }} />
                                                <span style="font-size: 9pt; color: #000000; vertical-align: bottom;">Ronchi</span>
                                                <input type="checkbox" style="vertical-align: bottom;"
                                                {{ isset($d['respirasiKeadaan']) && $d['respirasiKeadaan'] == 'Dispnea' ? 'checked' : '' }} />
                                                <span style="font-size: 9pt; color: #000000; vertical-align: bottom;">Dispnea</span>
                                                <input type="checkbox" style="vertical-align: bottom;"
                                                {{ isset($d['respirasiKeadaan']) && $d['respirasiKeadaan'] == 'Kusmaul' ? 'checked' : '' }} />
                                                <span style="font-size: 9pt; color: #000000; vertical-align: bottom;">Kusmaul</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="border-right:1px solid black;vertical-align: middle;">
                                                Konjungtiva
                                            </td>
                                            <td style="border-right:1px solid black">
                                                <input type="checkbox" style="vertical-align: bottom;"
                                                {{ isset($d['Konjungtiva']) && $d['Konjungtiva'] == 'Anemis' ? 'checked' : '' }} />
                                                <span style="font-size: 9pt; color: #000000; vertical-align: bottom;">Anemis</span>
                                                <input type="checkbox" style="vertical-align: bottom;"
                                                {{ isset($d['Konjungtiva']) && $d['Konjungtiva'] == 'TidakAnemis' ? 'checked' : '' }} />
                                                <span style="font-size: 9pt; color: #000000; vertical-align: bottom;">Tidak Anemis</span>
                                            </td>
                                            <td style="border-right:1px solid black">
                                                <input type="checkbox" style="vertical-align: bottom;"
                                                {{ isset($d['Konjungtiva']) && $d['Konjungtiva'] == 'PalpebraEdema' ? 'checked' : '' }} />
                                                <span style="font-size: 9pt; color: #000000; vertical-align: bottom;">Palpebra Edema</span>
                                            </td>
                                            <td style="border-right:1px solid black">
                                                <input type="checkbox" style="vertical-align: bottom;"
                                                {{ isset($d['Konjungtiva']) && $d['Konjungtiva'] == 'SkleraIkterik' ? 'checked' : '' }} />
                                                <span style="font-size: 9pt; color: #000000; vertical-align: bottom;">Sklera Ikterik</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="border-right:1px solid black;vertical-align: middle;">
                                                Ekstremitas
                                            </td>
                                            <td style="border-right:1px solid black">
                                                <input type="checkbox" style="vertical-align: bottom;"
                                                {{ isset($d['Ekstremitas']) && $d['Ekstremitas'] == 'TidakEdema' ? 'checked' : '' }} />
                                                <span style="font-size: 9pt; color: #000000; vertical-align: bottom;">Tidak Edema</span>
                                            </td>
                                            <td style="border-right:1px solid black">
                                                <input type="checkbox" style="vertical-align: bottom;"
                                                {{ isset($d['Ekstremitas']) && $d['Ekstremitas'] == 'Edema' ? 'checked' : '' }} />
                                                <span style="font-size: 9pt; color: #000000; vertical-align: bottom;">Edema</span>
                                            </td>
                                            <td style="border-right:1px solid black">
                                                <input type="checkbox" style="vertical-align: bottom;"
                                                {{ isset($d['Ekstremitas']) && $d['Ekstremitas'] == 'Pucat/Dingin' ? 'checked' : '' }} />
                                                <span style="font-size: 9pt; color: #000000; vertical-align: bottom;">Pucat/Dingin</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="border-right:1px solid black;vertical-align: middle;">
                                                Akses Vaskular
                                            </td>
                                            <td style="border-right:1px solid black">
                                                <input type="checkbox" style="vertical-align: bottom;"
                                                {{ isset($d['Fistula']) && $d['Fistula'] == 'Fistula' ? 'checked' : '' }} />
                                                <span style="font-size: 9pt; color: #000000; vertical-align: bottom;">Fistula</span>
                                                <input type="checkbox" style="vertical-align: bottom;"
                                                {{ isset($d['Sinistra']) && $d['Sinistra'] == 'Sinistra' ? 'checked' : '' }} />
                                                <span style="font-size: 9pt; color: #000000; vertical-align: bottom;">Sinistra</span>
                                                <input type="checkbox" style="vertical-align: bottom;"
                                                {{ isset($d['Dextra']) && $d['Dextra'] == 'Dextra' ? 'checked' : '' }} />
                                                <span style="font-size: 9pt; color: #000000; vertical-align: bottom;">Dextra</span>
                                            </td>
                                            <td rowspan="2" style="border-right:1px solid black">
                                                <input type="checkbox" style="vertical-align: bottom;"
                                                {{ isset($d['DoubleLument']) && $d['DoubleLument'] == 'Double Lument' ? 'checked' : '' }} />
                                                <span style="font-size: 9pt; color: #000000; vertical-align: bottom;">Double Lument</span>
                                            </td>
                                            <td rowspan="2" style="border-right:1px solid black">
                                                <input type="checkbox" style="vertical-align: bottom;"
                                                {{ isset($d['Junggularis']) && $d['Junggularis'] == 'Junggularis' ? 'checked' : '' }} />
                                                <span style="font-size: 9pt; color: #000000; vertical-align: bottom;">Junggularis</span>
                                                <input type="checkbox" style="vertical-align: bottom;"
                                                {{ isset($d['Femoral']) && $d['Femoral'] == 'Femoral' ? 'checked' : '' }} />
                                                <span style="font-size: 9pt; color: #000000; vertical-align: bottom;">Femoral</span>
                                                <input type="checkbox" style="vertical-align: bottom;"
                                                {{ isset($d['Subclavia']) && $d['Subclavia'] == 'Subclavia' ? 'checked' : '' }} />
                                                <span style="font-size: 9pt; color: #000000; vertical-align: bottom;">Subclavia</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="border-right:1px solid black;vertical-align: middle;">
                                            </td>

                                            <td style="border-right:1px solid black">
                                                <input type="checkbox" style="vertical-align: bottom;"
                                                {{ isset($d['Femoral']) && $d['Femoral'] == 'Femoral' ? 'checked' : '' }} />
                                                <span style="font-size: 9pt; color: #000000; vertical-align: bottom;">Femoral</span>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <td style="padding: 0px">
                                    <table>
                                        <tr>
                                            <th style="text-align:left;background-color:rgb(170, 168, 168)">
                                                <b>PENGKAJIAN RESIKO JATUH</b> 
                                            </th>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <td style="padding: 0px">
                                    <table>
                                        <tr>
                                            <td style="vertical-align: middle;">
                                                Resiko jatuh :
                                            </td>
                                            <td >
                                                <input type="checkbox" style="vertical-align: bottom;"
                                                {{ isset($d['SkalaMorse']) && $d['SkalaMorse'] == 'SkalaMorse' ? 'checked' : '' }} />
                                                <span style="font-size: 9pt; color: #000000; vertical-align: bottom;">Skala Morse</span>    
                                            </td>
                                            <td >
                                                <input type="checkbox" style="vertical-align: bottom;"
                                                {{ isset($d['rendah']) && $d['rendah'] == 'Rendah0-7' ? 'checked' : '' }} />
                                                <span style="font-size: 9pt; color: #000000; vertical-align: bottom;">Rendah 0-7</span>
                                            </td>
                                            <td >
                                                <input type="checkbox" style="vertical-align: bottom;"
                                                {{ isset($d['sedang']) && $d['sedang'] == 'Sedang8-13' ? 'checked' : '' }} />
                                                <span style="font-size: 9pt; color: #000000; vertical-align: bottom;">Sedang 8-13</span>
                                            </td>
                                            <td >
                                                <input type="checkbox" style="vertical-align: bottom;"
                                                {{ isset($d['tinggi']) && $d['tinggi'] == 'Tinggi≥14' ? 'checked' : '' }} />
                                                <span class="logo" style="font-size: 9pt; color: #000000; vertical-align: bottom;">Tinggi ≥ 14</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="vertical-align: middle;border:top: 0px;">
                                            </td>
                                            <td style="border-top: 0px">
                                                <input type="checkbox" style="vertical-align: bottom;"
                                                {{ isset($d['SkalaMumptyDumpty']) && $d['SkalaMumptyDumpty'] == 'SkalaMumptyDumpty' ? 'checked' : '' }} />
                                                <span style="font-size: 9pt; color: #000000; vertical-align: bottom;">Skala Mumpty Dumpty</span>   
                                            </td>
                                            <td style="border-top: 0px">
                                                <input type="checkbox" style="vertical-align: bottom;"
                                                {{ isset($d['rendahMumpty']) && $d['rendahMumpty'] == 'Rendah7-11' ? 'checked' : '' }} />
                                                <span style="font-size: 9pt; color: #000000; vertical-align: bottom;">Rendah 7-11</span>
                                            </td>
                                            <td style="border-top: 0px"> 
                                                <input type="checkbox" style="vertical-align: bottom;"
                                                {{ isset($d['tinggiMumpty']) && $d['tinggiMumpty'] == 'Tinggi≥12' ? 'checked' : '' }} />
                                                <span class="logo" style="font-size: 9pt; color: #000000; vertical-align: bottom;">Tinggi ≥ 12</span>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <td style="padding: 0px">
                                    <table>
                                        <tr>
                                            <th style="text-align:left;background-color:rgb(170, 168, 168)">
                                                <b>PENILAIAN NYERI</b> 
                                            </th>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <td style="padding: 0px">
                                    <table border="0">
                                        <tr>
                                            <td width="6%" style="vertical-align: middle;border-top:0px;">
                                                Nyeri:
                                            </td>
                                            <td  width="10%" style="border-top: 0px">
                                                <input type="checkbox" style="vertical-align: bottom;"
                                                {{ isset($d['penilaianNyeriTidak']) && $d['penilaianNyeriTidak'] == 'Tidak Nyeri' ? 'checked' : '' }} />
                                                <span style="font-size: 9pt; color: #000000; vertical-align: bottom;">Tidak</span>    
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="vertical-align: middle;border-top:0px">
                                            </td>
                                            <td style="border-top: 0px">
                                                <input type="checkbox" style="vertical-align: bottom;"
                                                {{ isset($d['penilaianNyeriYa']) && $d['penilaianNyeriYa'] == 'Benar Nyeri' ? 'checked' : '' }} />
                                                <span style="font-size: 9pt; color: #000000; vertical-align: bottom;">Ya:</span>    
                                            </td>
                                            <td width="20%" style="vertical-align: middle;border-top:0px">
                                                Skala Nyeri(NRS/WBS/VAS) :
                                                {{ isset($d['skalaNyeri']) ? $d['skalaNyeri'] : '...' }}
                                            </td>
                                            <td width="15%" style="vertical-align: middle;border-top:0px">
                                                Lokasi :
                                                {{ isset($d['lokasiNyeri']) ? $d['lokasiNyeri'] : '...' }}
                                            </td>
                                            <td width="20%" rowspan="4" style="border-top: 0px">
                                                <img src="{{ 'img/skalanyeri/wongBaker-2.png' }}" alt="Pain Rating" style="width: 200px;height:100px; padding-bottom: 25px;">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="border-top: 0px"></td>
                                            <td style="vertical-align: middle;border-top:0px">jenis :</td>
                                            <td style="border-top: 0px">
                                                <input type="checkbox" style="vertical-align: bottom;"
                                                {{ isset($d['jenisNyeriAkut']) && $d['jenisNyeriAkut'] == 'Akut' ? 'checked' : '' }} />
                                                <span style="font-size: 9pt; color: #000000; vertical-align: bottom;">Akut</span>    
                                                <input type="checkbox" style="vertical-align: bottom;"
                                                {{ isset($d['jenisNyeriKronis']) && $d['jenisNyeriKronis'] == 'Kronis' ? 'checked' : '' }} />
                                                <span style="font-size: 9pt; color: #000000; vertical-align: bottom;">Kronis</span>    
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="border-top: 0px"></td>
                                            <td style="vertical-align: middle;border-top:0px;">Frekuensi Nyeri :</td>
                                            <td colspan="2" style="padding: 0px;border-top:0px;">
                                                <table width="100%">
                                                    <tr>
                                                        <td style="border-top: 0px">
                                                            <input type="checkbox" style="vertical-align: bottom;"
                                                            {{ isset($d['frekuensiNyeriJarang']) && $d['frekuensiNyeriJarang'] == 'Jarang' ? 'checked' : '' }} />
                                                            <span style="font-size: 9pt; color: #000000; vertical-align: bottom;">Jarang</span>    
                                                        </td>
                                                        <td style="border-top: 0px">

                                                            <input type="checkbox" style="vertical-align: bottom;"
                                                            {{ isset($d['frekuensiNyeriHilangTimbul']) && $d['frekuensiNyeriHilangTimbul'] == 'Hilang Timbul' ? 'checked' : '' }} />
                                                            <span style="font-size: 9pt; color: #000000; vertical-align: bottom;">Hilang Timbul</span>    
                                                        </td>
                                                        <td style="border-top: 0px">
                                                            <input type="checkbox" style="vertical-align: bottom;"
                                                            {{ isset($d['frekuensiNyeriTerusMenerus']) && $d['frekuensiNyeriTerusMenerus'] == 'Terus Menerus' ? 'checked' : '' }} />
                                                            <span style="font-size: 9pt; color: #000000; vertical-align: bottom;">Terus Menerus</span>  
                                                        </td>  
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="border-top: 0px"></td>
                                            <td colspan="2" style="vertical-align: middle;border-top:0px">
                                                Lama Nyeri :
                                                {{ isset($d['lamaNyeri']) ? $d['lamaNyeri'] : '...' }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="border-top: 0px"></td>
                                            <td style="vertical-align: middle;border-top:0px">Menjalar :</td>
                                            <td style="border-top: 0px;">
                                                <input type="checkbox" style="vertical-align: bottom;"
                                                {{ isset($d['menjalarTida']) && $d['menjalarTida'] == 'Tidak' ? 'checked' : '' }} />
                                                <span style="font-size: 9pt; color: #000000; vertical-align: bottom;">Tidak</span>    
                                                <input type="checkbox" style="vertical-align: bottom;"
                                                {{ isset($d['menjalarYa']) && $d['menjalarYa'] == 'Ya' ? 'checked' : '' }} />
                                                <span style="font-size: 9pt; color: #000000; vertical-align: bottom;">Ya</span>    
                                            </td>
                                            <td style="border-top: 0px;vertical-align: middle;">
                                                Ke :
                                                {{ isset($d['keteranganMenjalar']) ? $d['keteranganMenjalar'] : '......' }}
                                            </td>
                                            <td rowspan="4" style="padding:0px;border-top: 0px;">
                                                <table style="border: 1px solid black; border-collapse: collapse; padding: 4px;">
                                                    <tr>
                                                      <td style="border: none; padding: 4px;">Skor:</td>
                                                      <td style="border: none; padding: 4px;">0 = Tidak nyeri</td>
                                                      <td style="border: none; padding: 4px;">1-3 = Nyeri ringan</td>
                                                    </tr>
                                                    <tr>
                                                      <td style="border: none;"></td>
                                                      <td style="border: none; padding: 4px;">4-6 = Nyeri sedang</td>
                                                      <td style="border: none; padding: 4px;">7-10 = Nyeri berat</td>
                                                    </tr>
                                                  </table>                                                  
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="border-top: 0px"></td>
                                            <td style="vertical-align: middle;border-top:0px">Kualitas Nyeri :</td>
                                            <td colspan="2" style="padding: 0px;border-top:0px">
                                                <table>
                                                    <tr>
                                                        <td style="border-top: 0px">
                                                            <input type="checkbox" style="vertical-align: bottom;"
                                                            {{ isset($d['kualitasNyeriTajam']) && $d['kualitasNyeriTajam'] == 'Nyeri Tajam' ? 'checked' : '' }} />
                                                            <span style="font-size: 9pt; color: #000000; vertical-align: bottom;">Nyeri Tajam</span>    
                                                        </td>
                                                        <td style="border-top: 0px">
                                                            <input type="checkbox" style="vertical-align: bottom;"
                                                            {{ isset($d['kualitasNyeriTumpul']) && $d['kualitasNyeriTumpul'] == 'Nyeri Tumpul' ? 'checked' : '' }} />
                                                            <span style="font-size: 9pt; color: #000000; vertical-align: bottom;">Nyeri Tumpul</span>    
                                                        </td>
                                                        <td style="border-top: 0px">
                                                            <input type="checkbox" style="vertical-align: bottom;"
                                                            {{ isset($d['kualitasNyeriPanas']) && $d['kualitasNyeriPanas'] == 'Rasa panas/terbakar' ? 'checked' : '' }} />
                                                            <span style="font-size: 9pt; color: #000000; vertical-align: bottom;">Rasa panas/terbakar</span>    
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="border-top: 0px"></td>
                                            <td colspan="3" style="border-top: 0px">
                                                Yang menyebabkan nyeri bertambah :
                                                {{ isset($d['yangMenyebabkanNyeriBertambah']) ? $d['yangMenyebabkanNyeriBertambah'] : '.......' }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="border-top: 0px"></td>
                                            <td colspan="3" style="border-top: 0px">
                                                Yang menyebabkan nyeri berkurang :
                                                {{ isset($d['yangMenyebabkanNyeriBertambah']) ? $d['yangMenyebabkanNyeriBertambah'] : '.......' }}
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <td style="padding: 0px;border-top: 0px" >
                                    <table>
                                        <tr>
                                            <td>
                                                <b>BB Kering :</b>
                                                {{ isset($d['bbKering']) ? $d['bbKering'] : '...................' }} <b>Kg</b>
                                            </td>
                                            <td>
                                                <b>BB Pra hemodialisis :</b>
                                                {{ isset($d['bbPraHemodialisis']) ? $d['bbPraHemodialisis'] : '...................' }} <b>Kg</b>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td colspan="3" class="border">
                        <table class="fnt">
                            <tr>
                                <td style="padding: 0px;border-top: 0px">
                                    <table>
                                        <tr>
                                            <td width="15%" style="vertical-align:middle;border-top: 0px">
                                                Parameter mesin:
                                            </td>
                                            <td style="border-top: 0px">
                                                Cunductivity
                                                {{ isset($d['conductivity']) ? $d['conductivity'] : '......' }}mS/cm
                                            </td>
                                            <td style="border-top: 0px">
                                                Suhu mesin
                                                {{ isset($d['suhuMesin']) ? $d['suhuMesin'] : '......' }}ºC
                                            </td>
                                            <td style="border-top: 0px">
                                                Dialisat flow
                                                {{ isset($d['dialisatFlow']) ? $d['dialisatFlow'] : '......' }}mL/mnt
                                            </td>
                                        </tr>
                                        <tr>
                                            <td width="15%" style="vertical-align:middle;border-top: 0px">
                                                Data dialiser:
                                            </td>
                                            <td width="20%" style="vertical-align:middle;border-top: 0px">
                                                Luas Membram
                                                {{ isset($d['luasMembram']) ? $d['luasMembram'] : '......' }}m2
                                            </td>
                                            <td style="vertical-align:middle;border-top: 0px">
                                                Volume priming
                                                {{ isset($d['volumePriming']) ? $d['volumePriming'] : '......' }}mL
                                            </td>
                                            <td style="border-top: 0px">
                                                Jenis Membram
                                                <input type="checkbox" style="vertical-align: bottom;"
                                                {{ isset($d['jenisMembram']) && $d['jenisMembram'] == 'High Flux' ? 'checked' : '' }} />
                                                <span style="font-size: 9pt; color: #000000; vertical-align: bottom;">High Flux</span>
                                                <input type="checkbox" style="vertical-align: bottom;"
                                                {{ isset($d['jenisMembram']) && $d['jenisMembram'] == 'Low Flux' ? 'checked' : '' }} />
                                                <span style="font-size: 9pt; color: #000000; vertical-align: bottom;">Low Flux</span>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <td style="padding: 0px;border-top: 0px">
                                    <table>
                                        <tr>
                                            <td width="15%" style="vertical-align: middle;border-top: 0px">
                                                Anti koagulan :
                                            </td>
                                            <td style="border-top: 0px">
                                                <input type="checkbox" style="vertical-align: bottom;"
                                                {{ isset($d['antiKoagulan']) && $d['antiKoagulan'] == 'Tanpa heparin' ? 'checked' : '' }} />
                                                <span style="font-size: 9pt; color: #000000; vertical-align: bottom;">Tanpa heparin</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td width="15%" style="vertical-align: middle;border-top: 0px">
                                            </td>
                                            <td style="border-top: 0px">
                                                <input type="checkbox" style="vertical-align: bottom;"
                                                {{ isset($d['antiKoagulan']) && $d['antiKoagulan'] == 'Heparin' ? 'checked' : '' }} />
                                                <span style="font-size: 9pt; color: #000000; vertical-align: bottom;">Heparin Dosis awal: </span>
                                                {{ isset($d['heparinDosisawal']) ? $d['heparinDosisawal'] : '......' }}Internasional unit
                                            </td>
                                            <td style="vertical-align: middle;border-top: 0px">
                                                Dosis Pemeliharaan:
                                                {{ isset($d['heparinDosisPemeliharaan']) ? $d['heparinDosisPemeliharaan'] : '......' }}International unit/jam
                                            </td>
                                        </tr>
                                        <tr>
                                            <td width="15%" style="vertical-align: middle;border-top: 0px">
                                            </td>
                                            <td style="border-top: 0px">
                                                <input type="checkbox" style="vertical-align: bottom;"
                                                {{ isset($d['antiKoagulan']) && $d['antiKoagulan'] == 'lowMolecularWeightHeparin' ? 'checked' : '' }} />
                                                <span style="font-size: 9pt; color: #000000; vertical-align: bottom;">low Molecular Weight Heparin, </span>
                                                Jenis
                                                {{ isset($d['lowMolecularWeightHeparinJenis']) ? $d['lowMolecularWeightHeparinJenis'] : '......' }}
                                            </td>
                                            <td style="vertical-align: middle;border-top: 0px">
                                                Dosis :
                                                {{ isset($d['lowMolecularWeightHeparinDosis']) ? $d['lowMolecularWeightHeparinDosis'] : '......' }}International unit/jam
                                            </td>
                                        </tr>
                                        <tr>
                                            <td width="15%" style="vertical-align: middle;border-top: 0px">
                                            </td>
                                            <td style="border-top: 0px">
                                                (Nama perawat I/paraf :<span>{{ isset($d['perawatParaf1']['label']) ? $d['perawatParaf1']['label'] : '......../....' }}</span>)
                                            </td>
                                            <td style="border-top: 0px">
                                                (Nama perawat I/paraf :<span>{{ isset($d['perawatParaf2']['label']) ? $d['perawatParaf2']['label'] : '......../....' }}</span>)
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <td style="padding: 0px">
                                    <table border="1">
                                        <tr>
                                            <th colspan="3" style="text-align:left;background-color:rgb(170, 168, 168)">
                                                <b>DIAGNOSA KEPERAWATAN</b> 
                                            </th>
                                        </tr>
                                        <tr>
                                            <td>
                                                <input type="checkbox" style="vertical-align: bottom;"
                                                {{ isset($d['Hipervolemia']) && $d['Hipervolemia'] == 'Hipervolemia' ? 'checked' : '' }} />
                                                <span style="font-size: 9pt; color: #000000; vertical-align: bottom;">Hipervolemia </span><br>
                                                <input type="checkbox" style="vertical-align: bottom;"
                                                {{ isset($d['resikoPendarahan']) && $d['resikoPendarahan'] == 'Resiko Pendarahan' ? 'checked' : '' }} />
                                                <span style="font-size: 9pt; color: #000000; vertical-align: bottom;">Resiko Pendarahan </span><br>
                                                <input type="checkbox" style="vertical-align: bottom;"
                                                {{ isset($d['resikoInfeksi']) && $d['resikoInfeksi'] == 'Resiko Infeksi' ? 'checked' : '' }} />
                                                <span style="font-size: 9pt; color: #000000; vertical-align: bottom;">Resiko Infeksi </span>
                                            </td>
                                            <td>
                                                <input type="checkbox" style="vertical-align: bottom;"
                                                {{ isset($d['resikoKetidakSeimbanganVolumeCairan']) && $d['resikoKetidakSeimbanganVolumeCairan'] == 'Resiko Ketidak Seimbangan Volume Cairan' ? 'checked' : '' }} />
                                                <span style="font-size: 9pt; color: #000000; vertical-align: bottom;">Resiko Ketidak Seimbangan Volume Cairan </span><br>
                                                <input type="checkbox" style="vertical-align: bottom;"
                                                {{ isset($d['resikoKetidakSeimbanganElektrolit']) && $d['resikoKetidakSeimbanganElektrolit'] == 'Resiko Ketidak Seimbangan Elektrolit' ? 'checked' : '' }} />
                                                <span style="font-size: 9pt; color: #000000; vertical-align: bottom;">Resiko Ketidak Seimbangan Elektrolit </span><br>
                                                <input type="checkbox" style="vertical-align: bottom;"
                                                {{ isset($d['keteranganDiagnosaKeperawatan1']) && $d['keteranganDiagnosaKeperawatan1'] != null ? 'checked' : '' }} />
                                                <span style="font-size: 9pt; color: #000000; vertical-align: bottom;"></span>
                                                {{ isset($d['keteranganDiagnosaKeperawatan1']) ? $d['keteranganDiagnosaKeperawatan1'] : '.............' }}
                                            </td>
                                            <td>
                                                <input type="checkbox" style="vertical-align: bottom;"
                                                {{ isset($d['resikoDefisitNutrisi']) && $d['resikoDefisitNutrisi'] == 'Resiko Defisit Nutrisi' ? 'checked' : '' }} />
                                                <span style="font-size: 9pt; color: #000000; vertical-align: bottom;">Resiko Defisit Nutrisi </span><br>
                                                <input type="checkbox" style="vertical-align: bottom;"
                                                {{ isset($d['Ansietas']) && $d['Ansietas'] == 'Ansietas' ? 'checked' : '' }} />
                                                <span style="font-size: 9pt; color: #000000; vertical-align: bottom;">Ansietas </span><br>
                                                <input type="checkbox" style="vertical-align: bottom;"
                                                {{ isset($d['keteranganDiagnosaKeperawatan2']) && $d['keteranganDiagnosaKeperawatan2'] != null ? 'checked' : '' }} />
                                                <span style="font-size: 9pt; color: #000000; vertical-align: bottom;"></span>
                                                {{ isset($d['keteranganDiagnosaKeperawatan2']) ? $d['keteranganDiagnosaKeperawatan2'] : '.............' }}
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <td style="padding: 0px">
                                    <table border="1">
                                        <tr>
                                            <th colspan="3" style="text-align:left;background-color:rgb(170, 168, 168)">
                                                <b>RENCANA KEPERAWATAN</b> 
                                            </th>
                                        </tr>
                                        <tr>
                                            <td>
                                                <input type="checkbox" style="vertical-align: bottom;"
                                                {{ isset($d['pemantauanTandaVital']) && $d['pemantauanTandaVital'] == 'Pemantauan tanda vital' ? 'checked' : '' }} />
                                                <span style="font-size: 9pt; color: #000000; vertical-align: bottom;">1. Pemantauan tanda vital </span><br>
                                                <input type="checkbox" style="vertical-align: bottom;"
                                                {{ isset($d['pemantauanCairan']) && $d['pemantauanCairan'] == 'Pemantauan cairan' ? 'checked' : '' }} />
                                                <span style="font-size: 9pt; color: #000000; vertical-align: bottom;">2. Pemantauan cairan</span><br>
                                                <input type="checkbox" style="vertical-align: bottom;"
                                                {{ isset($d['pemantauanElektrolit']) && $d['pemantauanElektrolit'] == 'Pemantauan elektrolit' ? 'checked' : '' }} />
                                                <span style="font-size: 9pt; color: #000000; vertical-align: bottom;">3. Pemantauan elektrolit</span><br>
                                                <input type="checkbox" style="vertical-align: bottom;"
                                                {{ isset($d['pencegahanPerdarahan']) && $d['pencegahanPerdarahan'] == 'Pencegahan perdarahan' ? 'checked' : '' }} />
                                                <span style="font-size: 9pt; color: #000000; vertical-align: bottom;">4. Pencegahan perdarahan</span><br>
                                                <input type="checkbox" style="vertical-align: bottom;"
                                                {{ isset($d['pencegahanInfeksi']) && $d['pencegahanInfeksi'] == 'Pencegahan infeksi' ? 'checked' : '' }} />
                                                <span style="font-size: 9pt; color: #000000; vertical-align: bottom;">5. Pencegahan infeksi</span>
                                            </td>
                                            <td>
                                                <input type="checkbox" style="vertical-align: bottom;"
                                                {{ isset($d['pencegahanSyok']) && $d['pencegahanSyok'] == 'Pencegahan syok' ? 'checked' : '' }} />
                                                <span style="font-size: 9pt; color: #000000; vertical-align: bottom;">6. Pencegahan syok</span><br>
                                                <input type="checkbox" style="vertical-align: bottom;"
                                                {{ isset($d['manajemenHipervolemia']) && $d['manajemenHipervolemia'] == 'Manajemen hipervolemia' ? 'checked' : '' }} />
                                                <span style="font-size: 9pt; color: #000000; vertical-align: bottom;">7. Manajemen hipervolemia</span><br>
                                                <input type="checkbox" style="vertical-align: bottom;"
                                                {{ isset($d['manajemenHemodialis']) && $d['manajemenHemodialis'] == 'Manajemen hemodialis' ? 'checked' : '' }} />
                                                <span style="font-size: 9pt; color: #000000; vertical-align: bottom;">8. Manajemen hemodialis</span><br>
                                                <input type="checkbox" style="vertical-align: bottom;"
                                                {{ isset($d['perawatanDialisis']) && $d['perawatanDialisis'] == 'Perawatan dialisis' ? 'checked' : '' }} />
                                                <span style="font-size: 9pt; color: #000000; vertical-align: bottom;">9. Perawatan dialisis</span><br>
                                                <input type="checkbox" style="vertical-align: bottom;"
                                                {{ isset($d['manajemenLingkungan']) && $d['manajemenLingkungan'] == 'Manajemen lingkungan' ? 'checked' : '' }} />
                                                <span style="font-size: 9pt; color: #000000; vertical-align: bottom;">10. Manajemen lingkungan</span>
                                            </td>
                                            <td>
                                                <input type="checkbox" style="vertical-align: bottom;"
                                                {{ isset($d['reduksiAnsietas']) && $d['reduksiAnsietas'] == 'Reduksi ansietas' ? 'checked' : '' }} />
                                                <span style="font-size: 9pt; color: #000000; vertical-align: bottom;">11. Reduksi ansietas</span><br>
                                                <input type="checkbox" style="vertical-align: bottom;"
                                                {{ isset($d['manajemenNutrisi']) && $d['manajemenNutrisi'] == 'Manajemen nutrisi' ? 'checked' : '' }} />
                                                <span style="font-size: 9pt; color: #000000; vertical-align: bottom;">12. Manajemen nutrisi</span><br>
                                                <input type="checkbox" style="vertical-align: bottom;"
                                                {{ isset($d['tranfusiDarah']) && $d['tranfusiDarah'] == 'Tranfusi darah' ? 'checked' : '' }} />
                                                <span style="font-size: 9pt; color: #000000; vertical-align: bottom;">13. Tranfusi darah</span><br>
                                                <input type="checkbox" style="vertical-align: bottom;"
                                                {{ isset($d['edukasi']) && $d['edukasi'] == 'Edukasi' ? 'checked' : '' }} />
                                                <span style="font-size: 9pt; color: #000000; vertical-align: bottom;">14. Edukasi</span><br>
                                                {{ isset($d['keteranganRencanaTindakanKeperawatan']) ? $d['keteranganRencanaTindakanKeperawatan'] : '................................................' }}
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td colspan="3" class="border">
                        <table class="fnt">
                            <tr>
                                <td style="padding: 0px">
                                    <table border="1">
                                        <tr>
                                            <th style="text-align:left;background-color:rgb(170, 168, 168)">
                                                <b>PELAKSANAAN DAN PEMANTAUAN HEMODIALISIS</b> 
                                            </th>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <table border="1">
                                        <tr>
                                            <th class="th-pri" width="10%" style="vertical-align: inherit; text-align: center" rowspan="2">
                                              TANGGAL & JAM
                                            </th>
                                            <th class="th-pri" width="10%" style="vertical-align: inherit; text-align: center" colspan="2">
                                              PASIEN
                                            </th>
                                            <th class="th-pri" width="10%" style="vertical-align: inherit; text-align: center" colspan="5">
                                              MESIN
                                            </th>
                                            <th class="th-pri" width="10%" style="vertical-align: inherit; text-align: center" rowspan="2">
                                              MASALAH/TINDAKAN
                                            </th>
                                            <th class="th-pri" width="10%" style="vertical-align: inherit; text-align: center" rowspan="2">
                                              PETUGAS
                                            </th>
                                          </tr>
                                          <tr style="text-align: center; vertical-align: inherit">
                                            <th width="10%" class="th-pri">Tekanan Darah (mmHG)</th>
                                            <th width="10%" class="th-pri">Nadi (X/menit)</th>
                                            <th width="10%" class="th-pri">Blood Flow Rate (mL/menit)</th>
                                            <th width="10%" class="th-pri">Vena Pressure (mmHg)</th>
                                            <th width="10%" class="th-pri">Ultra Filtrasi Goal (Liter)</th>
                                            <th width="10%" class="th-pri">Ultra Filtrasi Rate (mL/jam)</th>
                                            <th width="10%" class="th-pri">Ultra Filtrasi Removed (Liter)</th>
                                          </tr>
                                          @foreach ($d['detailPelaksanaan'] as $key => $item)
                                          <tr style="text-align: center">
                                            <td>
                                                {{ isset($item['tanggalPelaksanaan']) ? convertToMakassarDate($item['tanggalPelaksanaan']) : ' ' }}
                                            </td>
                                            <td>
                                                {{ isset($item['tekananDarahPelaksanaan']) ? $item['tekananDarahPelaksanaan'] : '/' }}  
                                            </td>
                                            <td>
                                                {{ isset($item['nadiPelaksanaan']) ? $item['nadiPelaksanaan'] : ' ' }}  
                                            </td>
                                            <td>
                                                {{ isset($item['bloodFlowRatePelaksanaan']) ? $item['bloodFlowRatePelaksanaan'] : ' ' }}  
                                            </td>
                                            <td>
                                                {{ isset($item['venaPressurePelaksanaan']) ? $item['venaPressurePelaksanaan'] : ' ' }}  
                                            </td>
                                            <td>
                                                {{ isset($item['ultraFiltrasiGoalPelaksanaan']) ? $item['ultraFiltrasiGoalPelaksanaan'] : ' ' }}  
                                            </td>
                                            <td>
                                                {{ isset($item['ultraFiltrasiRatePelaksanaan']) ? $item['ultraFiltrasiRatePelaksanaan'] : ' ' }}  
                                            </td>
                                            <td>
                                                {{ isset($item['ultraFiltrasiRemovedPelaksanaan']) ? $item['ultraFiltrasiRemovedPelaksanaan'] : ' ' }}  
                                            </td>
                                            <td>
                                                {{ isset($item['masalahPelaksanaan']) ? $item['masalahPelaksanaan'] : ' ' }}  
                                            </td>
                                            <td>
                                                <span>{{ isset($item['perawat']['label']) ? $item['perawat']['label'] : ' ' }}</span> 
                                            </td>
                                          </tr>
                                          @endforeach
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <td style="padding: 0px;border-bottom: 0px;">
                                    <table border="0">
                                        <tr>
                                            <th style="text-align:left;">
                                                <b>Pemberian Terapi Intra Hemodialis :</b> 
                                            </th>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <td style="border-top: 0px">
                                    <table border="1" style="border-collapse: collapse;">
                                        <tr class="tr-pri">
                                          <th class="th-pri" rowspan="2" style="vertical-align: inherit; text-align: center; padding: 1px;">
                                            Nama Obat
                                          </th>
                                          <th class="th-pri" rowspan="2" style="vertical-align: inherit; text-align: center; padding: 1px;">
                                            Dosis
                                          </th>
                                          <th class="th-pri" rowspan="2" style="vertical-align: inherit; text-align: center; padding: 1px;">
                                            Cara Pemberian
                                          </th>
                                          <th class="th-pri" rowspan="2" style="vertical-align: inherit; text-align: center; padding: 1px;">
                                            Waktu
                                          </th>
                                          <th class="th-pri" colspan="2" style="text-align: center; padding: 1px;">
                                            Nama Petugas
                                          </th>
                                        </tr>
                                        <tr class="tr-pri">
                                          <th class="th-pri" style="padding: 1px;">Perawat 1</th>
                                          <th class="th-pri" style="padding: 1px;">Perawat 2</th>
                                        </tr>
                                        @foreach ($d['detailObatResep'] as $key => $item)
                                        <tr>
                                          <td style="padding: 1px;">
                                            {{ isset($item['obat']) ? $item['obat'] : ' ' }}
                                          </td>
                                          <td style="padding: 1px;">
                                            {{ isset($item['dosis']) ? $item['dosis'] : ' ' }}
                                          </td>
                                          <td style="padding: 1px;">
                                            {{ isset($item['caraPemberian']) ? $item['caraPemberian'] : ' ' }}
                                          </td>
                                          <td style="padding: 1px;">
                                            {{ isset($item['waktu']) ? $item['waktu'] : ' ' }}
                                          </td>
                                          <td style="padding: 1px;">
                                            <span>{{ isset($item['perawatParafTerapiIntra']['label']) ? $item['perawatParafTerapiIntra']['label'] : ' ' }}</span>
                                          </td>
                                          <td style="padding: 1px;">
                                            <span>{{ isset($item['perawatParafTerapiIntra2']['label']) ? $item['perawatParafTerapiIntra2']['label'] : ' ' }}</span>
                                          </td>
                                        </tr>
                                        @endforeach
                                      </table>
                                      
                                </td>
                            </tr>
                            <tr>
                                <td style="padding: 0px;">
                                    <table>
                                        <tr>
                                            <th colspan="4" style="text-align:left;background-color:rgb(170, 168, 168)">
                                                <b>EVALUASI</b> 
                                            </th>
                                        </tr>
                                        <tr>
                                            <td colspan="4">
                                                Keluhan:
                                                {{ isset($item['keluhanEvaluasi']) ? $item['keluhanEvaluasi'] : ' ' }} 
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="4" style="border-top: 0px">
                                                Keadaan Umum :
                                                <input type="checkbox" style="vertical-align: bottom;"
                                                {{ isset($d['keadaanUmumEvaluasi']) && $d['keadaanUmumEvaluasi'] == 'Baik' ? 'checked' : '' }} />
                                                <span style="font-size: 9pt; color: #000000; vertical-align: bottom;">Baik</span>
                                                <input type="checkbox" style="vertical-align: bottom;"
                                                {{ isset($d['keadaanUmumEvaluasi']) && $d['keadaanUmumEvaluasi'] == 'Lemah' ? 'checked' : '' }} />
                                                <span style="font-size: 9pt; color: #000000; vertical-align: bottom;">Lemah</span>
                                                <input type="checkbox" style="vertical-align: bottom;"
                                                {{ isset($d['keadaanUmumEvaluasi']) && $d['keadaanUmumEvaluasi'] == 'Sangat Lemah' ? 'checked' : '' }} />
                                                <span style="font-size: 9pt; color: #000000; vertical-align: bottom;">Sangat Lemah</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td width="25%" style="border-top:0px">
                                                Tekanan Darah :
                                                {{ isset($d['tekananDarahEvaluasi']) ? $d['tekananDarahEvaluasi'] : '.......' }}mmHg
                                            </td>
                                            <td width="25%" style="border-top:0px">
                                                Nadi :
                                                {{ isset($d['nadiEvaluasi']) ? $d['nadiEvaluasi'] : '.......' }}X/menit
                                            </td>
                                            <td width="25%" style="border-top:0px">
                                                Respirasi :
                                                {{ isset($d['respirasiEvaluasi']) ? $d['respirasiEvaluasi'] : '.......' }}X/menit
                                            </td>
                                            <td width="25%" style="border-top:0px">
                                                Suhu :
                                                {{ isset($d['suhuEvaluasi']) ? $d['suhuEvaluasi'] : '.......' }}°C
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="4" style="border-top: 0px ">
                                                <table border="1">
                                                    <tr>
                                                        <th><b>Cairan Masuk (mL)</b></th>
                                                        <th style="border-right: 1px "><b>Cairan Masuk (mL)</b></th>
                                                        <td rowspan="7">
                                                            <table  style="border: 1px solid black; border-collapse: collapse; padding: 4px;">
                                                                <tr>
                                                                    <td  style="border: none; padding: 4px;">
                                                                        <i>Total Blood Time:</i>
                                                                        {{ isset($d['totalBloodTime']) ? $d['totalBloodTime'] : '.......' }}menit
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td  style="border: none; padding: 4px;">
                                                                        <i>Total Blood Volume:</i>
                                                                        {{ isset($d['totalBloodVolume']) ? $d['totalBloodVolume'] : '.......' }}Liter
                                                                    </td>
                                                                </tr>
                                                                
                                                            </table>
                                                            <table>
                                                                <tr>
                                                                    <td  style="border: none; padding: 4px;">
                                                                        <b>BB Post Hemodialisis:</b>
                                                                        {{ isset($d['bbPostHemodialisis']) ? $d['bbPostHemodialisis'] : '.......' }}Kg
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            Sisa priming :
                                                            {{ isset($d['sisaPriming']) ? $d['sisaPriming'] : '' }}
                                                        </td>
                                                        <td>
                                                            Ultrafiltrasi :
                                                            {{ isset($d['ultrafiltrasi']) ? $d['ultrafiltrasi'] : '' }}
                                                        </td>
                                                        
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            Transfusi/Obat :
                                                            {{ isset($d['TransfusiAtauObat']) ? $d['TransfusiAtauObat'] : '' }}
                                                        </td>
                                                        <td>
                                                            Kencing :
                                                            {{ isset($d['kencing']) ? $d['kencing'] : '' }}
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            Wash Out :
                                                            {{ isset($d['washOut']) ? $d['washOut'] : '' }}
                                                        </td>
                                                        <td>
                                                            Muntah :
                                                            {{ isset($d['muntah']) ? $d['muntah'] : '' }}
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            Minum :
                                                            {{ isset($d['minum']) ? $d['minum'] : '' }}
                                                        </td>
                                                        <td>
                                                            Drain :
                                                            {{ isset($d['drain']) ? $d['drain'] : '' }}
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <b>Jumlah</b> :
                                                            {{ isset($d['jumlahCairanMasuk']) ? $d['jumlahCairanMasuk'] : '' }}
                                                        </td>
                                                        <td>
                                                            <b>Jumlah</b> :
                                                            {{ isset($d['jumlahCairanKeluar']) ? $d['jumlahCairanKeluar'] : '' }}
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="2">
                                                            <b>Total balance:</b>
                                                            {{ isset($d['totalBalance']) ? $d['totalBalance'] : '' }}mL
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th colspan="4" style="text-align:left;background-color:rgb(170, 168, 168)">
                                                <b>KRITERIAPEMULANGAN PASIEN : (KHUSUS PASIEN RAWAT JALAN)</b> 
                                            </th>
                                        </tr>
                                        <tr>
                                            <td colspan="4" style="padding: 0px">
                                                <table>
                                                    <tr>
                                                        <td width="60%">
                                                            Permasalahan besar antar atau komplikasi intra & post hemodialisis yang menetap
                                                        </td>
                                                        <td style="padding: 1px;">
                                                            <input type="checkbox" style="vertical-align: bottom;"
                                                            {{ isset($d['permasalahanHes']) && $d['permasalahanHes'] == 'Ya' ? 'checked' : '' }} />
                                                            <span style="font-size: 9pt; color: #000000; vertical-align: bottom;">Ya</span>
                                                            <input type="checkbox" style="vertical-align: bottom;"
                                                            {{ isset($d['permasalahanHes']) && $d['permasalahanHes'] == 'Tidak' ? 'checked' : '' }} />
                                                            <span style="font-size: 9pt; color: #000000; vertical-align: bottom;">Tidak</span>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            Perdarahan akses
                                                        </td>
                                                        <td style="padding: 1px;">
                                                           
                                                            <input type="checkbox" style="vertical-align: bottom;"
                                                            {{ isset($d['perdarahanAkses']) && $d['perdarahanAkses'] == 'Ya' ? 'checked' : '' }} />
                                                            <span style="font-size: 9pt; color: #000000; vertical-align: bottom;">Ya</span>
                                                            <input type="checkbox" style="vertical-align: bottom;"
                                                            {{ isset($d['perdarahanAkses']) && $d['perdarahanAkses'] == 'Tidak' ? 'checked' : '' }} />
                                                            <span style="font-size: 9pt; color: #000000; vertical-align: bottom;">Tidak</span>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="2">
                                                            Kriteria pasien pulang terpenuhi
                                                            <input type="checkbox" style="vertical-align: bottom;"
                                                            {{ isset($d['kriteriaPasienPulang']) && $d['kriteriaPasienPulang'] == 'Ya' ? 'checked' : '' }} />
                                                            <span style="font-size: 9pt; color: #000000; vertical-align: bottom;">Ya</span>
                                                            <input type="checkbox" style="vertical-align: bottom;"
                                                            {{ isset($d['kriteriaPasienPulang']) && $d['kriteriaPasienPulang'] == 'Tidak' ? 'checked' : '' }} />
                                                            <span style="font-size: 9pt; color: #000000; vertical-align: bottom;">Tidak</span>
                                                            Lapor DPJP
                                                            {{ isset($d['keteranganLaporDPJP']) ? $d['keteranganLaporDPJP'] : '...' }}
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="2" >
                                                            Kondisi saat pulang:
                                                            {{ isset($d['kondisiSaatPulang']) ? $d['kondisiSaatPulang'] : '...................................' }}
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            Nama dan tanda tangan perawat yang melakukan kanulasi akses
                                                        </td>
                                                        <td>:
                                                            {{-- @if(!empty($d['namaPerawatKanulasiAkses']['label']))
                                                                <img src="https://api.qrserver.com/v1/create-qr-code/?size=50x50&data={{ $d['namaPerawatKanulasiAkses']['label'] }}">
                                                            @endif --}}
                                                            <span>{{ isset($d['namaPerawatKanulasiAkses']['label']) ? $d['namaPerawatKanulasiAkses']['label'] : '........................' }}
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            Nama dan tanda tangan perawat yang melakukan terminasi
                                                        </td>
                                                        <td>:
                                                            {{-- @if(!empty($d['namaPerawatTerminasi']['label']))
                                                            <img src="https://api.qrserver.com/v1/create-qr-code/?size=50x50&data={{ $d['namaPerawatTerminasi']['label'] }}">
                                                            @endif --}}
                                                            <span>{{ isset($d['namaPerawatTerminasi']['label']) ? $d['namaPerawatTerminasi']['label'] : '........................' }}
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            Nama dan tanda tangan perawat penanggung jawab
                                                        </td>
                                                        <td>:
                                                            {{-- @if(!empty($d['namaPerawatPenanggungJawab']['label']))
                                                                <img src="https://api.qrserver.com/v1/create-qr-code/?size=50x50&data={{ $d['namaPerawatPenanggungJawab']['label'] }}">
                                                            @endif --}}
                                                            <span>{{ isset($d['namaPerawatPenanggungJawab']['label']) ? $d['namaPerawatPenanggungJawab']['label'] : '........................' }}
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                           
                        </table>
                    </td>
                </tr>
               
                       
                

            </tbody>
        </table>
    @endforeach
</body>
