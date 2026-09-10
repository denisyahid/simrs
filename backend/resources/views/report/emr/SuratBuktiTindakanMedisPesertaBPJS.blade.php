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

        /* span {
            font-size: 13px;
        } */
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
                    SURAT BUKTI TINDAKAN MEDIS PESERTA BPJS
                </span>
            </td>
        </tr>
    </table>

    <table width="100%" class="fnt">
        <tr>
            <td style="font-weight: bold;">Yang bertanda tangan di bawah ini :</td>
        </tr>
    </table>

    <table width="100%" class="fnt" style="margin-left: 15px;border-collapse: collapse">
        <thead>
            <tr style="text-align: start;">
                <td style="width:18%;font-weight: bold;">Nama Operator</td>
                <td style="width:32%;">: {{ isset($data['petugas']) ? $data['petugas']['label'] : $data['petugas'] }}
                </td>
            </tr>
            <tr>
                <td style="width:18%;font-weight: bold;">Bagian SMF</td>
                <td style="width:32%;">: {!! isset($data['bagian']) ? nl2br(e($data['bagian'])) : '-' !!}</td>
            </tr>
        </thead>
    </table>

    <table width="100%" class="fnt">
        <tr>
            <td style="font-weight: bold;">Menerangkan bahwa penderita :</td>
        </tr>
    </table>

    <table width="100%" class="fnt" style="margin-left: 15px;border-collapse: collapse">
        <thead>
            <tr style="text-align: start;">
                <td style="width:18%;font-weight: bold;">Nomor Rekam Medis</td>
                <td style="width:32%;">: {!! isset($data['norm']) ? nl2br(e($data['norm'])) : '-' !!}</td>
            </tr>
            <tr>
                <td style="width:18%;font-weight: bold;">Nama Pasien</td>
                <td style="width:32%;">: {!! isset($data['namaPasien']) ? nl2br(e($data['namaPasien'])) : '-' !!}</td>
            </tr>
            <tr style="text-align: start;">
                <td style="font-weight: bold;width:18%;">Tanggal Lahir</td>
                <td style="width:32%;">:
                    {{ isset($data['tanggalLahirPasien'])
                        ? \Carbon\Carbon::parse($data['tanggalLahirPasien'])->setTimezone('Asia/Jakarta')->format('H:i')
                        : '-' }}
                </td>
            </tr>
            <tr>
                <td style="width:18%;;font-weight: bold;">Jenis Kelamin</td>
                <td style="width:32%;">: {!! isset($data['jeniskelamin']) ? nl2br(e($data['jeniskelamin'])) : '-' !!}</td>
            </tr>
            <tr>
                <td style="width:18%;;font-weight: bold;">Diagnosa</td>
                <td style="width:32%;">: {!! isset($data['diagnosa']) ? nl2br(e($data['diagnosa'])) : '-' !!}</td>
            </tr>
        </thead>
    </table>

    <table width="100%" class="fnt">
        <tr>
            <td style="font-weight: bold;">Memang benar dilakukan tindakan operasi :</td>
        </tr>
    </table>

    <table width="100%" class="fnt" style="margin-left: 15px;border-collapse: collapse">
        <thead>
            <tr style="text-align: start;">
                <td style="width:18%;font-weight: bold;">Nama Tindakan</td>
                <td style="width:32%;">: {!! isset($data['namaTindakan']) ? nl2br(e($data['namaTindakan'])) : '-' !!}</td>
            </tr>
            <tr>
                <td style="width:18%;font-weight: bold;">Jenis Anestesi</td>
                <td style="width:32%;">: {!! isset($data['jenisAnestesi']) ? nl2br(e($data['jenisAnestesi'])) : '-' !!}</td>
            </tr>
            <tr style="text-align: start;">
                <td style="font-weight: bold;width:18%;">Tanggal</td>
                <td style="width:32%;">:
                    {{ isset($data['tanggal'])
                        ? \Carbon\Carbon::parse($data['tanggal'])->setTimezone('Asia/Jakarta')->format('H:i')
                        : '-' }}
                </td>
            </tr>
        </thead>
    </table>

    <table class="no-page-break" width="100%" cellspacing="0" cellpadding="0"
        style="border-collapse: collapse; padding; page-break-inside: avoid; font-size:10pt;">
        <tr>
            {{-- <td width="60%"></td> --}}
            <td style="text-align: center" width="40%">
                <table width="100%" cellspacing="0" cellpadding="0" style="border-collapse: collapse;">
                    <tr>
                        <!-- Kolom pertama untuk barcode -->
                        <td style="text-align: center; width: 50%;">
                            @if (!empty($data['ttdPasien']) && Str::startsWith($data['ttdPasien'], 'data:image'))
                                <img src="{{ $data['ttdPasien'] }}"
                                    style="width: 100px; height: 100px; display: block;">
                            @else
                                <!-- Fallback jika data tidak valid -->
                                <span>TTD tidak tersedia</span>
                            @endif
                        </td>
                        <!-- Kolom kedua untuk barcode yang sama -->
                        <td style="text-align: center; width: 50%;">
                            <img src="data:image/png;base64, {!! $tte !!}" style="max-width: 100%;"> <br>
                        </td>
                    </tr>
                    <tr>
                        <!-- Informasi dokter di bawah barcode pertama -->
                        <td style="text-align: center;">

                            <span style="font-weight: bold;">
                                {{ $data['namaPasien'] }}
                            </span> <br>

                        </td>
                        <!-- Informasi dokter di bawah barcode kedua -->
                        <td style="text-align: center;">
                            @if (isset($data['DDDokter']['label']) != null)
                                <span style="font-weight: bold;">
                                    {{ $data['DDDokter']['label'] }}
                                </span> <br>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <!-- NIP di bawah barcode pertama -->
                        <td style="text-align: center;">
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
                        <!-- NIP di bawah barcode kedua -->
                        <td style="text-align: center;">
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
            </td>
        </tr>
    </table>

</body>

</html>
