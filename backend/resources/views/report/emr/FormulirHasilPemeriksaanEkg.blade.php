{{-- @extends('template.layout-emr-kop-surat')
@section('title')
    @if (isset($data['r_penunjang']) && $data['r_penunjang'] == 'IGD')
        EKG IGD
    @else
        Penunjang Poli Jantung
    @endif
@endsection
@section('kode', '')
@section('page-style')
    <style>
        body,
        table,
        td {
            font-family: 'Open Sans', sans-serif !important;
        }

        td {
            font-size: 10pt;
        }

        .table-flex {
            display: flex;
        }
    </style>
@endsection

@php
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
    function translateGender($gender)
    {
        if ($gender === 'Laki-laki') {
            return 'Male';
        } elseif ($gender === 'Perempuan') {
            return 'Female';
        } else {
            return '-';
        }
    }
@endphp

@section('content')
    <tr>
        <td align="center">
            <div style="font-size: 15pt; font-weight: bold; color: #000000;padding:8px">
                <u>ECG REPORT
                    @if (isset($data['r_penunjang']) && $data['r_penunjang'] == 'IGD')
                        {{ $data['r_penunjang'] }}
                    @endif
                </u><br>
            </div>
        </td>
    </tr>
    <tr>
        <td>
            <div class="table-flex">
                <table style="border-spacing: 0 8px;margin-left: 20px;">
                    <tr>
                        <td>Name</td>
                        <td>: {{ $data['namaPasien'] ?? '-' }}</td>

                    </tr>
                    <tr>
                        <td>Birthdate / Age</td>
                        <td>
                            <?php
                            function hitungUmur($tanggalLahir)
                            {
                                $tglLahir = new DateTime($tanggalLahir);
                                $sekarang = new DateTime();
                                $umur = $sekarang->diff($tglLahir);
                                return $umur->y . ' tahun, ' . $umur->m . ' bulan';
                            }
                            echo ': ' . $data['pasien']['tgllahir'] . ' / ' . hitungUmur($data['pasien']['tgllahir']);
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td>Sex</td>
                        <td>: {{ $data['jeniskelamin'] ?? '-' }} </td>
                    </tr>
                    <tr>
                        <td>Addres</td>
                        <td>: {{ $data['pasien']['alamatlengkap'] ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td>MR Number</td>
                        <td>: {{ $data['pasien']['nocm'] ?? '-' }}</td>
                    </tr>
                </table>

                <table width="100%" style="background-color: green;">
                    <tr>
                        <td width="100%" align="center">
                            <span style="font-size: 14pt; font-weight: bold;">RESULT</span>
                        </td>
                    </tr>
                </table>

                <table width="100%" style="margin-left: 20px;">
                    <thead>
                        <tr style="text-align: start; width: 50%;">
                            <td style="width: 100px;">P Wave</td>
                            <td style="width: 10px;">:</td>
                            <td>{{ isset($data['Pwave']) ? $data['Pwave'] : '-' }}</td>
                            <td></td>

                            <td style="width: 100px;">PR Interval</td>
                            <td>: {{ isset($data['PRInterval']) ? $data['PRInterval'] : '-' }}</td>
                            <td></td>

                            <td style="width: 100px;">QRS</td>
                            <td>: {{ isset($data['QRS']) ? $data['QRS'] : '-' }}</td>
                            <td></td>
                        </tr>

                        <tr style="text-align: start; width: 50%;">
                            <td style="width: 100px;">ST Segment</td>
                            <td style="width: 10px;">:</td>
                            <td>{{ isset($data['STSegment']) ? $data['STSegment'] : '-' }}</td>
                            <td></td>

                            <td style="width: 100px;">QT Interval</td>
                            <td>: {{ isset($data['QTInterval']) ? $data['QTInterval'] : '-' }}</td>
                            <td></td>

                            <td style="width: 100px;">T Wave</td>
                            <td>: {{ isset($data['Twave']) ? $data['Twave'] : '-' }}</td>
                            <td></td>
                        </tr>
                        <tr style="text-align: start; width: 50%;">
                            <td style="width: 100px;">HR</td>
                            <td style="width: 10px;">:</td>
                            <td>{{ isset($data['HR']) ? $data['HR'] : '-' }}</td>
                            <td></td>

                            <td style="width: 100px;">Axis</td>
                            <td>: {{ isset($data['Axis']) ? $data['Axis'] : '-' }}</td>
                            <td></td>

                            <td style="width: 100px;">Other</td>
                            <td>: {{ isset($data['Other']) ? $data['Other'] : '-' }}</td>
                            <td></td>
                        </tr>
                    </thead>
                </table>

                <table width="100%" style="background-color: green;">
                    <tr>
                        <td width="100%" align="center">
                            <span style="font-size: 14pt; font-weight: bold;">CONCLUSION</span>
                        </td>
                    </tr>
                </table>
                <table width="100%" style="margin-left: 20px;">
                    <tr>
                        <td width="100%" align="start">
                            <span>{{ isset($data['Conclusion']) ? $data['Conclusion'] : '-' }}</span>
                        </td>
                    </tr>
                </table>

            </div>
            <table width="100%" style="border-top: 1px solid black">
                <thead align="center">
                    <tr>
                        <td>
                            <img src="data:image/png;base64, {!! $tte !!}">
                        </td>
                    </tr>
                    <tr>
                        <td style="text-align: center;font-weight: bold;font-size:8pt" width="40%">
                            {{$data['dokterPemeriksa']}}
                        </td>
                    </tr>
                    <tr>
                        <td style="text-align: center;font-weight: bold;font-size:8pt" width="40%">
                            NIP :
                            @if (isset($data['nip']->nip))
                                {{ $data['nip']->nip }}
                            @else
                                {{ '-' }}
                            @endif
                        </td>
                    </tr>
                </thead>
            </table>

        </td>
    </tr>
@endsection --}}
<!DOCTYPE html>
<html>

<head>
    <title>@yield('title')</title>
    @yield('page-style')
    <style>
        html,
        body {
            page-break-inside: avoid !important;
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

    <table class="table" width="100%" cellspacing="0" cellpadding="0"
        style="border-bottom: 1px solid black;border-collapse: collapse; padding; page-break-inside: avoid !important;">
        <tr>
            <td style="text-align: center; padding: 15px;">
                <img src="{{ 'img/logo-cetakan-obgyn.png' }}" width="100px" height="100px" style="display: block;">
            </td>

            <td style="text-align: center;">
                <span style="font-weight: bold;">PEMERINTAH PROVINSI BALI </span><br>
                <span style="font-weight: bold;">DINAS KESEHATAN</span> <br>
                <span style="font-weight: bold;">RUMAH SAKIT UMUM DAERAH BALI MANDARA</span> <br>
                <span>Jalan ByPass Ngurah Rai No.548 Sanur,Garut-Bali</span>
                <span>No.Telp : (0361) 4490566, E-mail : rsud.balimandara@gmail.com</span>
            </td>
            <td style="text-align: center; padding: 15px;">
                <img src="{{ 'img/logo-rs.png' }}" width="100px" height="100px" style="display: block;">
            </td>
        </tr>
    </table>

    <table class="table" width="100%" cellspacing="0" cellpadding="0"
        style="border-collapse: collapse; padding; margin-top: 15px; page-break-inside: avoid !important;">
        <tr>
            <td style="text-align: center;">
                <span style="font-weight: bold;">
                    ECG REPORT
                </span>
            </td>
        </tr>
    </table>

    <table class="table" width="100%" cellspacing="0" cellpadding="0"
        style="border-collapse: collapse; padding; margin-top: 15px; page-break-inside: avoid !important;">
        <tr>
            <td style="border: 1px solid black; width: 15%; padding: 10px;">
                <span>No RM</span>
            </td>
            <td style="border: 1px solid black; padding-left: 10px;">
                <span>{{ isset($data['norm']) ? $data['norm'] : '-' }}</span>
            </td>
            <td style="border: 1px solid black; padding: 10px;">
                <span>Tanggal Eksaminasi</span>
            </td>
            <td style="border: 1px solid black; padding-left: 10px;">
                <span>
                    {{ isset($data['tglEksaminasi'])
                        ? \Carbon\Carbon::parse($data['tglEksaminasi'])->setTimezone('Asia/Jakarta')->format('d-m-Y H:i')
                        : '-' }}
                </span>
            </td>
        </tr>
        <tr>
            <td style="border: 1px solid black; width: 15%; padding: 10px; width: 20%;">
                <span>Nama Pasien</span>
            </td>
            <td style="border: 1px solid black; padding-left: 10px;">
                <span>{{ isset($data['namaPasien']) ? $data['namaPasien'] : '-' }}</span>
            </td>
            <td style="border: 1px solid black; width: 15%; padding: 10px;">
                <span>Tanggal Lahir</span>
            </td>
            <td style="border: 1px solid black; padding-left: 10px;">
                <span>{{ isset($data['tanggalLahirPasien']) ? $data['tanggalLahirPasien'] : '-' }}
                    {{ isset($data['umur']) ? $data['umur'] : '-' }}</span>
            </td>
        </tr>
        <tr>
            <td style="border: 1px solid black; width: 15%; padding: 10px;">
                <span>Alamat Pasien</span>
            </td>
            <td style="border: 1px solid black; padding-left: 10px;">
                <span>: {{ isset($data['pasien']['alamatlengkap']) ? $data['pasien']['alamatlengkap'] : '-' }}</span>
            </td>
            <td style="border: 1px solid black; padding: 10px; width: 20%;">
                <span>Indikasi Pasien</span>
            </td>
            <td style="border: 1px solid black; padding-left: 10px;">
                <span>{{ isset($data['indikasi']) ? $data['indikasi'] : '-' }}</span>
            </td>
        </tr>
        {{-- <tr>
            <td style="border: 1px solid black; width: 15%; padding: 10px;">
                <span>Sonographer</span>
            </td>
            <td style="border: 1px solid black; padding-left: 10px;">
                <span>{{ isset($data['dokterPemeriksa']) ? $data['dokterPemeriksa'] : '-' }}</span>
            </td>
            <td style="border: 1px solid black; padding: 10px; width: 20%;">
                <span>Reviewer</span>
            </td>
            <td style="border: 1px solid black; padding-left: 10px;">
                <span>{{ isset($data['dokterPemeriksa']) ? $data['dokterPemeriksa'] : '-' }}</span>
            </td>
        </tr> --}}
        <tr>
            <td style="padding: 8px; vertical-align: top; font-weight: bold;">Sonographer</td>
            @if (isset($data['DDDokter']['label']) != null)
                <td style="padding: 8px; vertical-align: top;">:
                    {{$data['DDDokter']['label']}}</td>
            @elseif(isset($data['DDDokter']) != null)
                <td style="padding: 8px; vertical-align: top;">:
                    {{$data['DDDokter']}}</td>
            @else
                <span style="font-weight: bold;">
                    {{ '-' }}
                </span> <br>
            @endif
            <td style="padding: 8px; vertical-align: top; font-weight: bold;">Reviewer</td>
            @if (isset($data['DDDokter']['label']) != null)
                <td style="padding: 8px; vertical-align: top;">:
                    {{$data['DDDokter']['label']}}</td>
            @elseif(isset($data['DDDokter']) != null)
                <td style="padding: 8px; vertical-align: top;">:
                    {{$data['DDDokter']}}</td>
            @else
                <span style="font-weight: bold;">
                    {{ '-' }}
                </span> <br>
            @endif
        </tr>
    </table>

    <table width="100%" style="background-color: green; margin-top: 15px;">
        <tr>
            <td width="100%" align="center">
                <span style="font-size: 14pt; font-weight: bold;">RESULT</span>
            </td>
        </tr>
    </table>

    <table width="100%" style="margin-left: 20px;">
        <thead>
            <tr style="text-align: start; width: 50%;">
                <td style="width: 100px;">P Wave</td>
                <td style="width: 10px;">:</td>
                <td>{{ isset($data['PWave']) ? $data['PWave'] : '-' }}</td>
                <td></td>

                <td style="width: 100px;">PR Interval</td>
                <td>: {{ isset($data['PRInterval']) ? $data['PRInterval'] : '-' }}</td>
                <td></td>

                <td style="width: 100px;">QRS</td>
                <td>: {{ isset($data['QRS']) ? $data['QRS'] : '-' }}</td>
                <td></td>
            </tr>

            <tr style="text-align: start; width: 50%;">
                <td style="width: 100px;">ST Segment</td>
                <td style="width: 10px;">:</td>
                <td>{{ isset($data['STSegment']) ? $data['STSegment'] : '-' }}</td>
                <td></td>

                <td style="width: 100px;">QT Interval</td>
                <td>: {{ isset($data['QTInterval']) ? $data['QTInterval'] : '-' }}</td>
                <td></td>

                <td style="width: 100px;">T Wave</td>
                <td>: {{ isset($data['TWave']) ? $data['TWave'] : '-' }}</td>
                <td></td>
            </tr>
            <tr style="text-align: start; width: 50%;">
                <td style="width: 100px;">HR</td>
                <td style="width: 10px;">:</td>
                <td>{{ isset($data['HR']) ? $data['HR'] : '-' }}</td>
                <td></td>

                <td style="width: 100px;">Axis</td>
                <td>: {{ isset($data['Axis']) ? $data['Axis'] : '-' }}</td>
                <td></td>

                <td style="width: 100px;">Other</td>
                <td>: {{ isset($data['Other']) ? $data['Other'] : '-' }}</td>
                <td></td>
            </tr>
        </thead>
    </table>

    <table width="100%" style="background-color: green;">
        <tr>
            <td width="100%" align="center">
                <span style="font-size: 14pt; font-weight: bold;">CONCLUSION</span>
            </td>
        </tr>
    </table>
    <table width="100%" style="margin-left: 20px;">
        <tr>
            <td width="100%" align="start">
                <span>{{ isset($data['Conclusion']) ? $data['Conclusion'] : '-' }}</span>
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
                <span style="font-weight: bold;">{{ \Carbon\Carbon::parse($data['registrasi']['tglregistrasi'])->format('d F Y') }}</span> <br>
                <img src="data:image/png;base64, {!! $tte !!}"> <br>
                {{-- {{if($data['DDDokter']['label'] != null) { {{ $data['DDDokter']['label'] }} } else { {{ '-' }} } }} --}}
                @if (isset($data['DDDokter']['label']) != null)
                    <span style="font-weight: bold;">
                        {{ $data['DDDokter']['label'] }}
                    </span> <br>

                @elseif(isset($data['DDDokter']) != null)
                    <span style="font-weight: bold;">
                        {{ $data['DDDokter'] }}
                    </span> <br>
                @else
                    <span style="font-weight: bold;">
                        {{ '-' }}
                    </span> <br>
                @endif

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
