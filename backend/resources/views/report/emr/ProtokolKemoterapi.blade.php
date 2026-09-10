@php
    function convertToMakassarTime($isoDateString)
    {
        $date = new DateTime($isoDateString, new DateTimeZone('UTC'));
        $date->setTimezone(new DateTimeZone('Asia/Jakarta'));
        return $date->format('d-m-Y');
    }

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
@endphp

<!DOCTYPE html>
<html>

<head>
    <title>Protokol Kemoterapi</title>
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

        .pd td {
            padding: 3px;
            text-align: center;
            font-size: 10pt;
        }

        pre {
            font-size: 10pt;
            font-family: Arial, Helvetica, sans-serif;
            margin-top: 0px;
            white-space: pre-wrap;
            word-wrap: break-word;
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
            vertical-align: middle !important;
        }

        .border {
            border: 1px solid black;
        }

        .font {
            font-size: 10pt !important;
        }
    </style>
</head>

<body>
    <table width="100%" cellspacing="0" cellpadding="0" style="border-collapse: collapse;">
        <thead>
            <tr>
                <td colspan="2">
                    <table width="100%" style="border-collapse: collapse;border-bottom: none" class="border">
                        <th
                            style="background-color: lightblue; padding:5px; font-weight:bold; width: 50%;text-align: left">
                            RSUD BALI MANDARA
                        </th>
                        <th
                            style="background-color: lightblue; padding:5px; font-weight:bold; width: 50%; text-align: right;">
                            
                        </th>
                    </table>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <table width="100%" style="border-collapse: collapse;">
                        <td width="10%" style="text-align: center;padding: 10px" class="border">
                            <img src="{{ 'img/logo-rs.png' }}" width="80px" height="80px" style="display: block;">
                        </td>
                        <td width="40%"
                            style="vertical-align: middle;text-align: center;font-weight: bold;font-size: large"
                            class="border">
                            Protokol Kemoterapi
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
                    </table>
                </td>
            </tr>
        </thead>
        <tbody style="border: 1px solid black; padding: 10px">
            <tr>
                <td style="border: 1px solid black; width: 51%">
                    @if (isset($data['MRS_ODC']) && $data['MRS_ODC'] == 'MRS')
                        MRS {{ isset($data['tglMRS']) ? ': ' . convertToMakassarTime($data['tglMRS']) : ': -' }}
                    @elseif (isset($data['MRS_ODC']) && $data['MRS_ODC'] == 'ODC')
                        ODC {{ isset($data['tglODC']) ? ': ' . convertToMakassarTime($data['tglODC']) : ': -' }}
                    @endif
                </td>
                <td style="border: 1px solid black; width: 49%">
                    Ruangan
                    {{ isset($data['namaruangan']['label']) ? $data['namaruangan']['label'] : '' }}
                </td>
            </tr>
            <tr>
                <td colspan="2" style="text-align: left;">
                    <span>Diagnosis :</span>
                    <pre>{{ isset($data['diagnosis']) ? $data['diagnosis'] : ' -' }}</pre>
                </td>
            </tr>
            <tr>
                <td colspan="2" style="text-align:left">
                    <span>Regimen Kemoterapi :</span>
                    <pre>{{ isset($data['regimen']) ? $data['regimen'] : ' -' }}</pre>
                </td>
            </tr>
            <tr>
                <td colspan="2" style="text-align:left">
                    <span>Premedikasi :</span>
                    <pre>{{ isset($data['premedikasi']) ? $data['premedikasi'] : ' -' }}</pre>
                </td>
            </tr>
            <tr>
                <td colspan="2" style="text-align:left">
                    <span>Hidrasi Sebelum :</span>
                    <pre>{{ isset($data['hidrasiSebelum']) ? $data['hidrasiSebelum'] : ' -' }}</pre>
                </td>
            </tr>
            <tr>
                <td colspan="2" style="text-align:left">
                    <span>Kemoterapi :</span>
                    <pre>{{ isset($data['kemoterapi']) ? $data['kemoterapi'] : ' -' }}</pre>
                </td>
            </tr>
            <tr>
                <td colspan="2" style="text-align:left">
                    <span>Hidrasi Sesudah :</span>
                    <pre>{{ isset($data['hidrasiSesudah']) ? $data['hidrasiSesudah'] : ' -' }}</pre>
                </td>
            </tr>
            <tr>
                <td colspan="2" style="text-align:left">
                    <span>Keterangan :</span>
                    <pre>{{ isset($data['keterangan']) ? $data['keterangan'] : ' -' }}</pre>
                </td>
            </tr>
            <tr>
                <td style="width: 70%"></td>
                <td style="width: 30%;text-align:center">
                    DPJP<br>
                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=80x80&data={{ $data['DDDokter']['label'] }}"
                        style="padding:5px"><br />
                    {{ isset($data['DDDokter']) ? $data['DDDokter']['label'] : '-' }}
                </td>
            </tr>
        </tbody>
    </table>
</body>

</html>

