<?php
function formatDateIndonesian($date)
{
    $timestamp = strtotime($date);
    if ($timestamp === false) {
        return '-';
    }
    $day = date('d', $timestamp);
    $month = getIndonesianMonth(date('n', $timestamp));
    $year = date('Y', $timestamp);
    return "$day $month $year";
}
function getIndonesianMonth($monthNumber)
{
    $months = [
        1 => 'Januari',
        2 => 'Februari',
        3 => 'Maret',
        4 => 'April',
        5 => 'Mei',
        6 => 'Juni',
        7 => 'Juli',
        8 => 'Agustus',
        9 => 'September',
        10 => 'Oktober',
        11 => 'November',
        12 => 'Desember',
    ];
    return $months[$monthNumber] ?? '';
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>EMR - formulir Lower Extermity Duplex Ultrasound</title>
</head>

<body>
    <table width="100%" cellspacing="0" cellpadding="0" style="border: 1px solid black;border-collapse: collapse;">
        <tr>
            <td style="background-color: lightblue; padding:5px; font-weight:bold; width: 50%;">
                RSUD BALI MANDARA
            </td>
            <td style="background-color: lightblue; padding:5px; font-weight:bold; width: 50%; text-align: right;">
                @yield('kode')
            </td>
        </tr>
        <tr>
            <td colspan="2" style="border: 1px solid black">
                <table width="100%" style="border-collapse: collapse">
                    <tr style="border: ">
                        <td width="15%" style="text-align: center;padding: 10px">
                            <img src="{{ asset('img/provinsi-rs.svg') }}" width="80px" height="80px"
                                style="display: block;">
                        </td>
                        <td width="70%" style="text-align: center;">
                            <span>
                                <b>PEMERINTAH PROVINSI BALI</b><br>
                                <b>RUMAH SAKIT UMUM DAERAH BALI MANDARA</b><br>
                                Jalan ByPass Ngurah Rai No.548 Sanur,Garut-Bali<br>
                                No.Telp : (0361) 4490566, E-mail : rsud.balimandara@gmail.com
                            </span>
                        </td>
                        <td width="15%" style="text-align: center;padding: 10px">
                            <img src="{{ asset('img/logo-rs.png') }}" width="80px" height="80px"
                                style="display: block;">
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <table width="100%" cellspacing="0" cellpadding="0" style="border-collapse: collapse;">
                    <table width="100%">
                        <tr>
                            <td width="100%" align="center">
                                <span style="font-size: 14pt; font-weight: bold;">FORMULIR Lower Extermity Duplex Ultrasound</span>
                            </td>
                        </tr>
                    </table>
                    <div style="display: flex;">
                        <table width="100%">
                            <thead>
                                <tr style="text-align: start; width: 50%;">
                                    <td style="width: 100px;">Name</td>
                                    <td style="width: 10px;">:</td>
                                    <td style="font-size: 10pt; font-weight: bold;">{{ $pasien['namapasien'] }}</td>
                                </tr>
                                <tr>
                                    <td>Birthdate</td>
                                    <td>:</td>
                                    <td style="font-size: 10pt; font-weight: bold;">{{ $pasien['tgllahir'] }}</td>
                                </tr>
                                <tr>
                                    <td>Sex</td>
                                    <td>:</td>
                                    <td style="font-size: 10pt; font-weight: bold;">{{ $pasien['jeniskelamin'] }}</td>
                                </tr>
                                <tr>
                                    <td>MR Number</td>
                                    <td>:</td>
                                    <td style="font-size: 10pt; font-weight: bold;">{{ $pasien['nocm'] }}</td>
                                </tr>
                                <tr>
                                    <td>INDICATION</td>
                                    <td>:</td>
                                    <td style="font-size: 10pt; font-weight: bold;">{{ $data['indikasi'] }}</td>
                                </tr>
                            </thead>
                        </table>
                        <table width="100%">
                            <thead>
                                <tr style="text-align: start; width: 50%;">
                                    <td style="width: 100px;">Sonogrpaher</td>
                                    <td style="width: 10px;">:</td>
                                    <td style="font-size: 10pt; font-weight: bold;">{{ $data['sonographer']['label'] }}
                                    </td>
                                </tr>
                                <tr>
                                    <td>Reviewer</td>
                                    <td>:</td>
                                    <td style="font-size: 10pt; font-weight: bold;">{{ $data['reviewer']['label'] }}
                                    </td>
                                </tr>
                                <tr>
                                    <td>Exmanination Date</td>
                                    <td>:</td>
                                    <td style="font-size: 10pt; font-weight: bold;">
                                        {{ date('d-m-Y - H:m', strtotime($data['tglEksaminasi'])) }}</td>
                                </tr>
                                <tr>
                                    <td>Study Type</td>
                                    <td>:</td>
                                    <td style="font-size: 10pt; font-weight: bold;">{{ $data['studyType'] }}</td>
                                </tr>
                            </thead>
                        </table>
                    </div>

                    <table width="100%" style="background-color: green;">
                        <tr>
                            <td width="100%" align="center">
                                <span style="font-size: 14pt; font-weight: bold;">FINDING</span>
                            </td>
                        </tr>
                    </table>
                    <table width="100%">
                        <tr>
                            <td width="100%" align="start">
                                <span style="font-size: 14pt; font-weight: bold;">{{ $data['finding'] }}</span>
                            </td>
                        </tr>
                    </table>
                    <table width="100%" style="background-color: green;">
                        <tr>
                            <td width="100%" align="center">
                                <span style="font-size: 14pt; font-weight: bold;">CONCLUSION</span>
                            </td>
                        </tr>
                    </table>
                    <table width="100%">
                        <tr>
                            <td width="100%" align="start">
                                <span style="font-size: 14pt; font-weight: bold;">{{ $data['conclusion'] }}</span>
                            </td>
                        </tr>
                    </table>
                </table>
                <br>
                <br>
                <br>
                <br>
                <br>
                <br>
                <div style="display: flex;">
                    <table width="100%">

                    </table>
                    <table width="100%">
                        <thead>
                            <tr style="text-align: start; width: 50%;">
                                <td style="font-size: 10pt; font-weight: bold;">{{ $data['sonographer']['label'] }}
                                </td>
                            </tr>
                        </thead>
                    </table>
                </div>
            </td>
        </tr>
    </table>
</body>

</html>
