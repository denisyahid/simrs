<!DOCTYPE html>
<html>

<head>
    <title>@yield('title')</title>
    @yield('page-style')
    <style>
        html,
        body {
            page-break-inside: avoid !important;
            font-family: Arial, Helvetica, sans-serif;
        }

        .kesimpulan-wrapper {
            width: 100%;
            border-collapse: collapse !important;
            page-break-inside: avoid !important;
        }
    </style>
</head>

<body>
    @php
        function translateDateToEnglish($date)
        {
            $months = [
                'Januari' => 'January',
                'Februari' => 'February',
                'Maret' => 'March',
                'April' => 'April',
                'Mei' => 'May',
                'Juni' => 'June',
                'Juli' => 'July',
                'Agustus' => 'August',
                'September' => 'September',
                'Oktober' => 'October',
                'November' => 'November',
                'Desember' => 'December',
            ];
            foreach ($months as $indonesian => $english) {
                if (strpos($date, $indonesian) !== false) {
                    $date = str_replace($indonesian, $english, $date);
                    break;
                }
            }
            return $date;
        }
        function convertDayToIndonesian($dateString)
        {
            $dayMap = [
                '01' => 'satu',
                '02' => 'dua',
                '03' => 'tiga',
                '04' => 'empat',
                '05' => 'lima',
                '06' => 'enam',
                '07' => 'tujuh',
                '08' => 'delapan',
                '09' => 'sembilan',
                '10' => 'sepuluh',
                '11' => 'sebelas',
                '12' => 'dua belas',
                '13' => 'tiga belas',
                '14' => 'empat belas',
                '15' => 'lima belas',
                '16' => 'enam belas',
                '17' => 'tujuh belas',
                '18' => 'delapan belas',
                '19' => 'sembilan belas',
                '20' => 'dua puluh',
                '21' => 'dua puluh satu',
                '22' => 'dua puluh dua',
                '23' => 'dua puluh tiga',
                '24' => 'dua puluh empat',
                '25' => 'dua puluh lima',
                '26' => 'dua puluh enam',
                '27' => 'dua puluh tujuh',
                '28' => 'dua puluh delapan',
                '29' => 'dua puluh sembilan',
                '30' => 'tiga puluh',
                '31' => 'tiga puluh satu',
            ];
            $day = date('d', strtotime($dateString));
            return $dayMap[$day];
        }
        function convertDayToEnglish($dateString)
        {
            $dayMap = [
                '01' => 'one',
                '02' => 'two',
                '03' => 'three',
                '04' => 'four',
                '05' => 'five',
                '06' => 'six',
                '07' => 'seven',
                '08' => 'eight',
                '09' => 'nine',
                '10' => 'ten',
                '11' => 'eleven',
                '12' => 'twelve',
                '13' => 'thirteen',
                '14' => 'fourteen',
                '15' => 'fifteen',
                '16' => 'sixteen',
                '17' => 'seventeen',
                '18' => 'eighteen',
                '19' => 'nineteen',
                '20' => 'twenty',
                '21' => 'twenty-one',
                '22' => 'twenty-two',
                '23' => 'twenty-three',
                '24' => 'twenty-four',
                '25' => 'twenty-five',
                '26' => 'twenty-six',
                '27' => 'twenty-seven',
                '28' => 'twenty-eight',
                '29' => 'twenty-nine',
                '30' => 'thirty',
                '31' => 'thirty-one',
            ];
            $day = date('d', strtotime($dateString));
            return $dayMap[$day];
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
        function convertMonthToEnglish($dateString)
        {
            $monthMap = [
                '01' => 'January',
                '02' => 'February',
                '03' => 'March',
                '04' => 'April',
                '05' => 'May',
                '06' => 'June',
                '07' => 'July',
                '08' => 'August',
                '09' => 'September',
                '10' => 'October',
                '11' => 'November',
                '12' => 'December',
            ];
            $month = date('m', strtotime($dateString));
            return $monthMap[$month];
        }
        function convertMonthToIndonesian($dateString)
        {
            $monthMap = [
                '01' => 'Januari',
                '02' => 'Februari',
                '03' => 'Maret',
                '04' => 'April',
                '05' => 'Mei',
                '06' => 'Juni',
                '07' => 'Juli',
                '08' => 'Agustus',
                '09' => 'September',
                '10' => 'Oktober',
                '11' => 'November',
                '12' => 'Desember',
            ];
            $month = date('m', strtotime($dateString));
            return $monthMap[$month] ?? '-';
        }
        function convertYearToIndonesian($year)
        {
            $yearConvert = preg_replace('/\s+/', ' ', trim($year));
            $parts = explode(' ', $yearConvert);
            if (count($parts) === 3) {
                $year = $parts[2];
            }

            $units = [
                '0' => 'nol',
                '1' => 'satu',
                '2' => 'dua',
                '3' => 'tiga',
                '4' => 'empat',
                '5' => 'lima',
                '6' => 'enam',
                '7' => 'tujuh',
                '8' => 'delapan',
                '9' => 'sembilan',
            ];
            $yearString = str_pad((string) $year, 4, '0', STR_PAD_LEFT);
            $words = [];
            if ($yearString[0] != '0') {
                $words[] = $units[$yearString[0]] . ' ribu';
            }
            if ($yearString[1] != '0') {
                $words[] = $units[$yearString[1]] . ' ratus';
            }
            if ($yearString[2] != '0') {
                $words[] = $units[$yearString[2]] . ' puluh';
            }
            if ($yearString[3] != '0') {
                $words[] = $units[$yearString[3]];
            }
            return implode(' ', $words);
        }
        function convertYearToEnglish($dateString)
        {
            $year = date('Y', strtotime($dateString));
            $yearMap = [
                '2020' => 'Two Thousand Twenty',
                '2021' => 'Two Thousand Twenty-One',
                '2022' => 'Two Thousand Twenty-Two',
                '2023' => 'Two Thousand Twenty-Three',
                '2024' => 'Two Thousand Twenty-Four',
                '2025' => 'Two Thousand Twenty-Five',
                '2026' => 'Two Thousand Twenty-Six',
                // Add more years as needed
            ];
            return $yearMap[$year] ?? 'Unknown Year';
        }

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
        function formatDateEnglish($date)
        {
            $months = [
                '01' => 'January',
                '02' => 'February',
                '03' => 'March',
                '04' => 'April',
                '05' => 'May',
                '06' => 'June',
                '07' => 'July',
                '08' => 'August',
                '09' => 'September',
                '10' => 'October',
                '11' => 'November',
                '12' => 'December',
            ];
            $formattedDate =
                date('d', strtotime($date)) .
                ' ' .
                $months[date('m', strtotime($date))] .
                ' ' .
                date('Y', strtotime($date));
            return $formattedDate;
        }
        // dd($data)
    @endphp

    {{-- {{dd($data)}} --}}

    <table class="table" width="100%" cellspacing="0" cellpadding="0"
        style="border-bottom: 1px solid black; border-collapse: collapse; padding: 0; page-break-inside: avoid !important;">
        <tr>
            <td style="text-align: center; padding: 15px;">
                <img src="{{ 'img/logo-cetakan-obgyn.png' }}" width="100px" height="100px" style="display: block;">
            </td>
            <td style="text-align: center;">
                <span style="font-weight: bold;">PEMERINTAH PROVINSI BALI </span><br>
                <span style="font-weight: bold;">DINAS KESEHATAN</span><br>
                <span style="font-weight: bold;">RUMAH SAKIT UMUM DAERAH BALI MANDARA</span><br>
                <span>Jalan ByPass Ngurah Rai No.548 Sanur, Garut-Bali</span><br>
                <span>No.Telp: (0361) 4490566, E-mail: rsud.balimandara@gmail.com</span>
            </td>
            <td style="text-align: center; padding: 15px;">
                <img src="{{ 'img/logo-rs.png' }}" width="100px" height="100px" style="display: block;">
            </td>
        </tr>
    </table>

    <table class="table" width="100%" cellspacing="0" cellpadding="0"
        style="border-collapse: collapse; padding: 0; page-break-inside: avoid !important;">
        <tr>
            <td style="text-align: center;">
                <span style="font-weight: bold;">ECHOCARDIOGRAPHY REPORT</span>
            </td>
        </tr>
    </table>

    <table class="table" width="100%" cellspacing="0" cellpadding="8"
        style="border-collapse: collapse; page-break-inside: avoid !important;">
        <thead>
            {{-- <tr>
                <th colspan="2" style="text-align: left; padding: 8px; font-weight: bold;"></th>
                <th colspan="2" style="text-align: left; padding: 8px; font-weight: bold;"></th>
            </tr> --}}
        </thead>
        <tbody>
            <tr style="font-size:9.5pt;">
                <td style="width: 30%; padding: 8px; vertical-align: top; font-weight: bold;">No RM</td>
                <td style="width: 30%; padding: 8px; vertical-align: top;">:
                    {{ isset($data['norm']) ? $data['norm'] : '-' }}</td>
                <td style="width: 30%; padding: 8px; vertical-align: top; font-weight: bold;">Tanggal Eksaminasi</td>
                <td style="width: 30%; padding: 8px; vertical-align: top;">:
                    {{ isset($data['tglEksaminasi'])
                        ? \Carbon\Carbon::parse($data['tglEksaminasi'])->setTimezone('Asia/Jakarta')->format('d-m-Y H:i')
                        : '-' }}
                </td>
            </tr>
            <tr style="font-size:9.5pt;">
                <td style="padding: 8px; vertical-align: top; font-weight: bold;">Nama Pasien</td>
                <td style="padding: 8px; vertical-align: top;">:
                    {{ isset($data['namaPasien']) ? $data['namaPasien'] : '-' }}</td>
                <td style="padding: 8px; vertical-align: top; font-weight: bold;">Tanggal Lahir</td>
                <td style="padding: 8px; vertical-align: top;">:
                    {{ isset($data['tanggalLahirPasien']) ? $data['tanggalLahirPasien'] : '-' }}
                    {{ isset($data['umur']) ? $data['umur'] : '-' }}</td>
            </tr>
            <tr style="font-size:9.5pt;">
                <td style="padding: 8px; vertical-align: top; font-weight: bold;">Study Type</td>
                <td style="padding: 8px; vertical-align: top;">: Trans Torachal Echo</td>
                <td style="padding: 8px; vertical-align: top; font-weight: bold;">Indikasi Pasien</td>
                <td style="padding: 8px; vertical-align: top;">:
                    {{ isset($data['indikasi']) ? $data['indikasi'] : '-' }}</td>
            </tr>
            <tr>
                <td style="padding: 8px; vertical-align: top; font-weight: bold;">Sonographer</td>
                @if (isset($data['DDDokter']['label']) != null)
                    <td style="padding: 8px; vertical-align: top;font-size: 12px;">:
                        {{$data['DDDokter']['label']}}</td>
                @elseif(isset($data['DDDokter']) != null)
                    <td style="padding: 8px; vertical-align: top;font-size: 12px;">:
                        {{$data['DDDokter']}}</td>
                @else
                    <span style="font-weight: bold;">
                        {{ '-' }}
                    </span> <br>
                @endif
                <td style="padding: 8px; vertical-align: top; font-weight: bold;">Reviewer</td>
                @if (isset($data['DDDokter']['label']) != null)
                    <td style="padding: 8px; vertical-align: top;font-size: 12px;">:
                        {{$data['DDDokter']['label']}}</td>
                @elseif(isset($data['DDDokter']) != null)
                    <td style="padding: 8px; vertical-align: top;font-size: 12px;">:
                        {{$data['DDDokter']}}</td>
                @else
                    <span style="font-weight: bold;">
                        {{ '-' }}
                    </span> <br>
                @endif
            </tr>
        </tbody>
    </table>

    <table width="100%" style="background-color: green;">
        <tr>
            <td width="100%" align="center">
                <span style="font-weight: bold; color: white;">RESULT</span>
            </td>
        </tr>
    </table>

    {{-- <table width="100%">
        <thead>
            <tr style="text-align: start; width: 50%; font-size:9.5pt;">
                <td style="width: 100px; font-weight: bold;">Ao Diameter</td>
                <td style="width: 10px;">:</td>
                <td>{{ $data['aoDiameter'] ?? '-' }}</td>
                <td>mm</td>

                <td style="width: 100px; font-weight: bold;">LA Diameter</td>
                <td>: {{ $data['laDiameter'] ?? '-' }}</td>
                <td>mm</td>

                <td style="width: 100px; font-weight: bold;">EF Biplane</td>
                <td>: {{ $data['efBiplane'] ?? '-' }}</td>
                <td>%</td>
            </tr>
            <tr style="text-align: start; width: 50%; font-size:9.5pt;;">
                <td style="width: 100px; font-weight: bold;">IVC Min</td>
                <td style="width: 10px;">:</td>
                <td>{{ $data['ivcMin'] ?? '-' }}</td>
                <td>mm</td>

                <td style="width: 100px; font-weight: bold;">IVSd</td>
                <td>: {{ $data['ivsd'] ?? '-' }}</td>
                <td>mm</td>

                <td style="width: 100px; font-weight: bold;">LVIDd</td>
                <td>: {{ $data['lvidd'] ?? '-' }}</td>
                <td>mm</td>
            </tr>
            <tr style="text-align: start; width: 50%; font-size:9.5pt;;">
                <td style="width: 100px; font-weight: bold;">LVPWD</td>
                <td style="width: 10px;">:</td>
                <td>{{ $data['lvpwd'] ?? '-' }}</td>
                <td>mm</td>

                <td style="width: 100px; font-weight: bold;">EIF TEICH</td>
                <td>: {{ $data['eifTEICH'] ?? '-' }}</td>
                <td>%</td>

                <td style="width: 100px; font-weight: bold;">MV/EA Ratio</td>
                <td>: {{ $data['mvEaRatio'] ?? '-' }}</td>
                <td></td>
            </tr>
            <tr style="text-align: start; width: 50%; font-size:9.5pt;;">
                <td style="width: 100px; font-weight: bold;">MV e spetal</td>
                <td style="width: 10px;">:</td>
                <td>{{ $data['mvESpetal'] ?? '-' }}</td>
                <td>cm/s</td>

                <td style="width: 100px; font-weight: bold;">MV e Lateral</td>
                <td>: {{ $data['mvELateral'] ?? '-' }}</td>
                <td>cm/s</td>

                <td style="width: 100px; font-weight: bold;">E/e¹</td>
                <td>: {{ $data['ee'] ?? '-' }}</td>
                <td></td>
            </tr>
        </thead>
    </table> --}}

    <table width="100%">
        <thead>
            <tr style="text-align: start; width: 50%; font-size:9.5pt;">
                <td style="width: 100px; font-weight: bold;">Ao Diameter</td>
                <td style="width: 10px;">:</td>
                <td>{{ $data['aoDiameter'] ?? '-' }}</td>
                <td>mm</td>

                <td style="width: 100px; font-weight: bold;">IVSD</td>
                <td>: {{ $data['ivsd'] ?? '-' }}</td>
                <td>mm</td>

                <td style="width: 100px; font-weight: bold;">MV/EA Ratio</td>
                <td>: {{ $data['mvEaRatio'] ?? '-' }}</td>
                <td></td>
            </tr>
            <tr style="text-align: start; width: 50%; font-size:9.5pt;">
                <td style="width: 100px; font-weight: bold;">LA Diameter</td>
                <td style="width: 10px;">:</td>
                <td>{{ $data['laDiameter'] ?? '-' }}</td>
                <td>mm</td>

                <td style="width: 100px; font-weight: bold;">LVIDd</td>
                <td>: {{ $data['lvidd'] ?? '-' }}</td>
                <td>mm</td>

                <td style="width: 100px; font-weight: bold;">MV e spetal</td>
                <td>: {{ $data['mvESpetal'] ?? '-' }}</td>
                <td>cm/s</td>
            </tr>
            <tr style="text-align: start; width: 50%; font-size:9.5pt;">
                <td style="width: 100px; font-weight: bold;">EF Biplane</td>
                <td style="width: 10px;">:</td>
                <td>{{ $data['efBiplane'] ?? '-' }}</td>
                <td>%</td>

                <td style="width: 100px; font-weight: bold;">LVPWD</td>
                <td>: {{ $data['lvpwd'] ?? '-' }}</td>
                <td>mm</td>

                <td style="width: 100px; font-weight: bold;">MV e Lateral </td>
                <td>: {{ $data['mvELateral'] ?? '-' }}</td>
                <td></td>
            </tr>
            <tr style="text-align: start; width: 50%; font-size:9.5pt;">
                <td style="width: 100px; font-weight: bold;">IVC Min</td>
                <td style="width: 10px;">:</td>
                <td>{{ $data['ivcMin'] ?? '-' }}</td>
                <td>mm</td>

                <td style="width: 100px; font-weight: bold;">EIF TEICH</td>
                <td>: {{ $data['eifTEICH'] ?? '-' }}</td>
                <td>%</td>

                <td style="width: 100px; font-weight: bold;">E/e¹</td>
                <td>: {{ $data['ee'] ?? '-' }}</td>
                <td></td>
            </tr>
            <tr style="text-align: start; width: 50%; font-size:9.5pt;">
                <td style="width: 100px; font-weight: bold;">IVC Max</td>
                <td style="width: 10px;">:</td>
                <td>{{ $data['ivcMax'] ?? '-' }}</td>
                <td>mm</td>

                <td style="width: 100px; font-weight: bold;">LV MASS</td>
                <td>: {{ $data['lvmass'] ?? '-' }}</td>
                <td>g</td>

                <td style="width: 100px; font-weight: bold;">PV ACT</td>
                <td>: {{ $data['pvAct'] ?? '-' }}</td>
                <td>m/s</td>
            </tr>
            <tr style="text-align: start; width: 50%; font-size:9.5pt;">
                <td style="width: 100px; font-weight: bold;">Tapse</td>
                <td style="width: 10px;">:</td>
                <td>{{ $data['tapse'] ?? '-' }}</td>
                <td>mm</td>

                <td style="width: 100px; font-weight: bold;">RWT</td>
                <td>: {{ $data['lpwd'] ?? '-' }}</td>
                <td>mm</td>

                <td style="width: 100px; font-weight: bold;">AO VMax</td>
                <td>: {{ $data['aoVMax'] ?? '-' }}</td>
                <td>m/s</td>
            </tr>
        </thead>
    </table>

    <table width="100%" style="background-color: green;">
        <tr>
            <td width="100%" align="center">
                <span style="font-weight: bold; color: white;">FINDING</span>
            </td>
        </tr>
    </table>

    {{-- <table width="100%">
        <thead>
            <tr style="text-align: start; width: 50%; font-size:9.5pt;;">
                <td style="width: 100px; font-weight: bold;">Cardiac Chamber Dimension</td>
                <td style="width: 10px;">:</td>
                <td>{{ $data['cardiacChamber'] ?? '-' }}</td>

                <td style="width: 100px; font-weight: bold;">LVH</td>
                <td>: {{ $data['lvh'] ?? '-' }}</td>

                <td style="width: 100px; font-weight: bold;">Systolic LV Function</td>
                <td>: {{ $data['systolicLv'] ?? '-' }}</td>
            </tr>
        </thead>
        <thead>
            <tr style="text-align: start; width: 50%; font-size:9.5pt;;">
                <td style="width: 100px; font-weight: bold;">Diastolic LV Function</td>
                <td style="width: 10px;">:</td>
                <td>{{ $data['diastolicLv'] ?? '-' }}</td>

                <td style="width: 100px; font-weight: bold;">Heart Wave</td>
                <td>: {{ $data['heartWave'] ?? '-' }}</td>

                <td style="width: 100px; font-weight: bold;">Aortic Valve</td>
                <td>: {{ $data['aorticValve'] ?? '-' }}</td>
            </tr>
        </thead>
        <thead>
            <tr style="text-align: start; width: 50%; font-size:9.5pt;;">
                <td style="width: 100px; font-weight: bold;">Mitral Valve</td>
                <td style="width: 10px;">:</td>
                <td>{{ $data['mitralValve'] ?? '-' }}</td>

                <td style="width: 100px; font-weight: bold;">Tricuspid Valve</td>
                <td>: {{ $data['tricuspidValve'] ?? '-' }}</td>

                <td style="width: 100px; font-weight: bold;">Pulmonary Valve</td>
                <td>: {{ $data['pulmonaryValve'] ?? '-' }}</td>
            </tr>
        </thead>
    </table> --}}
    <table width="100%">
        <thead>
            <tr style="text-align: start; font-size:9.5pt;">
                <td style="font-weight: bold; width: 200px;">Cardiac Chamber Dimension</td>
                <td>: {{ $data['cardiacChamber'] ?? '-' }}</td>
            </tr>
            <tr style="text-align: start; font-size:9.5pt;">
                <td style="font-weight: bold; width: 200px;">LVH</td>
                <td>: {{ $data['lvh'] ?? '-' }}</td>
            </tr>
            <tr style="text-align: start; font-size:9.5pt;">
                <td style="font-weight: bold; width: 200px;">Systolic LV Function</td>
                <td>: {{ $data['systolicLv'] ?? '-' }}</td>
            </tr>
            <tr style="text-align: start; font-size:9.5pt;">
                <td style="font-weight: bold; width: 200px;">Diastolic LV Function</td>
                <td>: {{ $data['diastolicLv'] ?? '-' }}</td>
            </tr>
            <tr style="text-align: start; font-size:9.5pt;">
                <td style="font-weight: bold; width: 200px;">RV Contractility</td>
                <td>: {{ $data['rvContractility'] ?? '-' }}</td>
            </tr>
            <tr style="text-align: start; font-size:9.5pt;">
                <td style="font-weight: bold; width: 200px;">LV Wall Motion</td>
                <td>: {{ $data['lvWallMotion'] ?? '-' }}</td>
            </tr>
            <tr style="text-align: start; font-size:9.5pt;">
                <td style="font-weight: bold; width: 200px;">Heart Wave</td>
                <td>: {{ $data['heartWave'] ?? '-' }}</td>
            </tr>
            <tr style="text-align: start; font-size:9.5pt;">
                <td style="font-weight: bold; width: 200px;">Aortic Valve</td>
                <td>: {{ $data['aorticValve'] ?? '-' }}</td>
            </tr>
            <tr style="text-align: start; font-size:9.5pt;">
                <td style="font-weight: bold; width: 200px;">Mitral Valve</td>
                <td>: {{ $data['mitralValve'] ?? '-' }}</td>
            </tr>
            <tr style="text-align: start; font-size:9.5pt;">
                <td style="font-weight: bold; width: 200px;">Tricuspid Valve</td>
                <td>: {{ $data['tricuspidValve'] ?? '-' }}</td>
            </tr>
            <tr style="text-align: start; font-size:9.5pt;">
                <td style="font-weight: bold; margin-left: 30px; width: 200px;">Pulmonary Valve</td>
                <td>: {{ $data['pulmonaryValve'] ?? '-' }}</td>
            </tr>
            <tr style="text-align: start; font-size:9.5pt;">
                <td style="font-weight: bold; width: 200px;">Pericardium</td>
                <td>: {{ $data['pericardium'] ?? '-' }}</td>
            </tr>
        </thead>
    </table>


    <table width="100%" style="background-color: green;">
        <tr>
            <td width="100%" align="center">
                <span style="font-weight: bold; color: white;">CONCLUSION</span>
            </td>
        </tr>
    </table>

    <table width="100%">
        <tr style="font-size:9.5pt;;">
            <td width="100%" align="start">
                {{ $data['conclusion'] ?? '-' }}
            </td>
        </tr>
    </table>


    <table class="no-page-break" width="100%" cellspacing="0" cellpadding="0"
        style="border-collapse: collapse; padding; page-break-inside: avoid; font-size:10pt;">
        <tr>
            <td width="60%"></td>
            <td style="text-align: center" width="40%">
                <br>
                {{-- <span style="font-weight: bold;">Garut, {{ translateDateToEnglish($data['registrasi']['tglregistrasi']) }}</span> --}}
                {{-- <span style="font-weight: bold;">{{ \Carbon\Carbon::parse($data['registrasi']['tglregistrasi'])->format('d F Y') }}</span> <br> --}}
                <img src="data:image/png;base64, {!! $tte !!}"> <br>
                {{-- {{if($data['DDDokter']['label'] != null) { {{ $data['DDDokter']['label'] }} } else { {{ '-' }} } }} --}}
                {{-- @if (isset($data['DDDokter']['label']) != null)
                    <span style="font-weight: bold;">
                        {{ $data['DDDokter']['label'] }}
                    </span> <br>
                @endif

                @if(isset($data['DDDokter']) != null)
                    <span style="font-weight: bold;">
                        {{ $data['DDDokter'] }}
                    </span> <br>
                @endif --}}
                <span style="font-weight: bold;">
                    {{ isset($data['DDDokter'])
                    ? (is_array($data['DDDokter'])
                        ? $data['DDDokter']['label']
                        : $data['DDDokter'])
                    : '-' }}
                </span> <br>

                @if (isset($data['nip']->nip))
                    <span style="font-weight: bold;">
                        NIP :
                        @if (isset($data['nip']->nip))
                            {{ $data['nip']->nip }}
                        @else
                            {{ '-' }}
                        @endif
                    </span>
                @endif
            </td>
        </tr>
    </table>

</body>

</html>
