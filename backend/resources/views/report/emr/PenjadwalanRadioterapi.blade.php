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
@endphp

<!DOCTYPE html>
<html>

<head>
    <title>Penjadwalan Radioterapi</title>
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
            font-size: 8pt
        }

        table {
            page-break-inside: auto
        }

        tr {
            page-break-inside: auto;
            page-break-after: avoid
        }
    </style>
</head>

<body>
    <table width="100%" cellspacing="0" cellpadding="0" style="border-collapse: collapse;">
        <thead>
            <tr>
                <td colspan="5">
                    <table width="100%" style="border-collapse: collapse;border-bottom: none" class="border">
                        <th
                            style="background-color: lightblue; padding:5px; font-weight:bold; width: 50%;text-align: left">
                            RSUD BALI MANDARA
                        </th>
                        <th
                            style="background-color: lightblue; padding:5px; font-weight:bold; width: 50%; text-align: right;">
                            RM.1A/RLD/01
                        </th>
                    </table>
                </td>
            </tr>
            <tr>
                <td colspan="5">
                    <table width="100%" style="border-collapse: collapse;">
                        <td width="10%" style="text-align: center;padding: 10px" class="border">
                            <img src="{{ 'img/logo-rs.png' }}" width="80px" height="80px" style="display: block;">
                        </td>
                        <td width="40%"
                            style="vertical-align: middle;text-align: center;font-weight: bold;font-size: large"
                            class="border">
                            Penjadwalan Radioterapi
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
            <tr>
                <td colspan="5" class="border bTop" style="padding: 7px;font-size: 9pt">Diagnosa :
                    {{ isset($data['TBDiagnosa']) ? $data['TBDiagnosa'] : '-' }}</td>
            </tr>
            <tr>
                <td colspan="5" class="border bTop" style="padding: 7px;font-size: 9pt">Permintaan Terapi :
                    {{ isset($data['TAPermintaanTerapi']) ? $data['TAPermintaanTerapi'] : '-' }}</td>
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
        </thead>
        <tbody>
            @foreach ($data['details'] as $index => $item)
                @if (date('d-m-Y', strtotime($item['tanggal'])) != '01-01-1970')
                    <tr class="list">
                        <td>{{ $item['program'] ?? '-' }}</td>
                        <td>
                            {{ isset($item['tanggal']) ? date('d-m-Y', strtotime($item['tanggal'])) : '-' }}
                        </td>
                        <td>
                            @php
                                $maxIndex = $loop->count - 1;
                            @endphp
                            <img style="width: 100px;height: 100px;" src="{{ $data['parafPasien_' . $maxIndex] }}">
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
        </tbody>
    </table>
</body>

</html>
