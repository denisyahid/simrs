<!DOCTYPE html>
<html>

<head>
    <title>@yield('title')</title>
    {{-- @yield('page-style') --}}
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

        .td-pri {
            border: 1px solid black;
        }

        .th-pri {
            border: 1px solid black;
        }

        .table th,
        .table td {
            border: 1px solid black;
            padding: 1px;
            text-align: center;
            vertical-align: middle;
        }

        .table th {
            background-color: #f2f2f2;
        }

        .td-pri span {
            display: inline-block;
            margin: 5px 0;
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

    <table class="table" width="100%" cellspacing="0" cellpadding="0" style="page-break-inside: avoid !important; border: none;">
        <tr>
            <td style="text-align: right; padding: 0px;">
                <span>No RM : {{ isset($data['norm']) ? $data['norm'] : '-' }}</span>
            </td>
        </tr>
    </table>

    <table class="table" width="100%" cellspacing="0" cellpadding="0"
        style="border-collapse: collapse; padding; margin-top: 15px; page-break-inside: avoid !important;">
        <tr>
            <td style="width: 10%;" width="10%">
                <img src="{{ 'img/logo-rs.png' }}" width="100px" height="100px" style="display: block;">
            </td>
            <td style="text-align: center;">
                <span style="font-weight: bold;">
                    RSUD BALI MANDARA
                </span>
                <br>
                <span style="font-weight: bold;">
                    FORMULIR PENCAMPURAN SEDIAAN KEMOTERAPI
                </span>
            </td>
        </tr>
    </table>
    {{-- {{dd($data)}} --}}
    <table class="table" width="100%" cellspacing="0" cellpadding="0"
        style="border-collapse: collapse; padding; margin-top: 15px; page-break-inside: avoid !important; border: 1px solid black;">
        <thead style="background-color: rgb(252, 171, 255);">
            <th class="th-pri">Nama Pasien</th>
            <th class="th-pri">Tanggal Lahir</th>
            <th class="th-pri">BB(Kg)</th>
            <th class="th-pri">TB(cm)</th>
            <th class="th-pri">BSA(m <sup>2</sup>)</th>
            <th class="th-pri">Alergi</th>
            <th class="th-pri">Ruangan</th>
            <th class="th-pri">Cara Bayar</th>
        </thead>
        <tbody>
            <tr>
                <td class="td-pri">
                    <span>{{ isset($data['namaPasien']) ? $data['namaPasien'] : '-' }}</span>
                </td>
                <td class="td-pri">
                    <span>{{ isset($data['tanggalLahirPasien']) ? $data['tanggalLahirPasien'] : '-' }}
                        {{ isset($data['umur']) ? $data['umur'] : '-' }}</span>
                </td>
                <td class="td-pri">
                    <span>{{ isset($data['beratbadanObgyn']) ? $data['beratbadanObgyn'] : '-' }}</span>
                </td>
                <td class="td-pri">
                    <span>{{ isset($data['tinggibadanObgyn']) ? $data['tinggibadanObgyn'] : '-' }}</span>
                </td>
                <td class="td-pri">
                    <span>{{ isset($data['bsa']) ? $data['bsa'] : '-' }}</span>
                </td>
                <td class="td-pri">
                    <span>{{ isset($data['alergi']) ? $data['alergi'] : '-' }}</span>
                </td>
                <td class="td-pri">
                    <span>{{ isset($data['ruangan']) ? $data['ruangan'] : '-' }}</span>
                </td>
                <td class="td-pri">
                    <span>{{ isset($data['caraBayar']) ? $data['caraBayar'] : '-' }}</span>
                </td>
            </tr>
        </tbody>
    </table>

    <table class="table" width="100%" cellspacing="0" cellpadding="0"
        style="border-collapse: collapse; padding; margin-top: 15px; page-break-inside: avoid !important; border: 1px solid black;">
        <thead style="background-color: rgb(252, 171, 255);">
            <th class="th-pri">Diagnosa</th>
            <th class="th-pri">Protokol</th>
            <th class="th-pri">Siklus</th>
            <th class="th-pri">Rencana Kemo Berikutnya</th>
            <th class="th-pri">Nama Dokter (DPJP)</th>
        </thead>
        <tbody>
            <tr>
                <td class="td-pri">
                    <span>{{ isset($data['Diagnosa']) ? $data['Diagnosa'] : '-' }}</span>
                </td>
                <td class="td-pri">
                    <span>{{ isset($data['Protokol']) ? $data['Protokol'] : '-' }}</span>
                </td>
                <td class="td-pri">
                    <span>{{ isset($data['Siklus']) ? $data['Siklus'] : '-' }}</span>
                </td>
                <td class="td-pri">
                    <span>
                        {{ isset($data['tanggalRencanaKemoterapi']) ? \Carbon\Carbon::parse($data['tanggalRencanaKemoterapi'])->translatedFormat('d F Y') : '-' }}
                    </span>
                </td>

                <td class="td-pri">
                    <span>{{ isset($data['dokterPemeriksa']) ? $data['dokterPemeriksa'] : '-' }}</span>
                </td>
            </tr>
        </tbody>
    </table>

    <table class="table" width="100%" cellspacing="0" cellpadding="0"
        style="border-collapse: collapse; padding; margin-top: 15px; page-break-inside: avoid !important; border: 1px solid black;">
        <thead>
            <tr>
                <th class="th-pri" colspan="5">DIISI OLEH DOKTER</th>
                <th class="th-pri" colspan="4">DIISI OLEH FARMASI</th>
                <th class="th-pri" colspan="8">DIISI OLEH DOKTER</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td style="vertical-align: middle;" class="td-pri" rowspan="2">Nama Obat <br> (dosis/kg atau
                    dosis/m²)</td>
                <td style="vertical-align: middle;" class="td-pri" rowspan="2">Dosis</td>
                <td style="vertical-align: middle;" class="td-pri" rowspan="2">Cara pemberian</td>
                <td style="vertical-align: middle;" class="td-pri" rowspan="2">Jenis & vol cairan infus</td>
                <td style="vertical-align: middle;" class="td-pri" rowspan="2">Paraf dokter</td>
                <td style="vertical-align: middle;" class="td-pri" rowspan="2">Pelarut</td>
                <td style="vertical-align: middle;" class="td-pri" rowspan="2">Vol obat yg diambil</td>
                <td style="vertical-align: middle;" class="td-pri" rowspan="2">Volume total</td>
                <td style="vertical-align: middle;" class="td-pri" rowspan="2">Stabilitas</td>
                <td class="td-pri" colspan="8">Tanggal/bulan Permintaan</td>
            </tr>

            <tr>
                @for ($i = 1; $i <= 8; $i++)
                    <td class="td-pri">
                        <span>{{ isset($data['tanggalPermintaan' . $i]) ? \Carbon\Carbon::parse($data['tanggalPermintaan' . $i])->translatedFormat('d F Y') : '-' }}</span>
                    </td>
                @endfor
            </tr>

            @foreach ($data['details'] as $datas)
                <tr>
                    <td class="td-pri"><span>{{ $datas['obat']['label'] ?? '' }}</span></td>
                    <td class="td-pri"><span>{{ $datas['dosis'] ?? '' }}</span></td>
                    <td class="td-pri"><span>{{ $datas['caraPemberian'] ?? '' }}</span></td>
                    <td class="td-pri"><span>{{ $datas['jenisVolCairanInfus'] ?? '' }}</span></td>
                    <!-- Generate QR Code for 'parafDokter' dynamically -->
                    <td class="td-pri">
                        <img src="data:image/svg+xml;base64,{{ base64_encode(QrCode::format('svg')->margin(2)->size(50)->generate($datas['parafDokter']['label'] ?? '')) }}" 
                            alt="QR Code">
                    </td>
                    <td class="td-pri"><span>{{ $datas['pelarut'] ?? '' }}</span></td>
                    <td class="td-pri"><span>{{ $datas['volObatDiambil'] ?? '' }}</span></td>
                    <td class="td-pri"><span>{{ $datas['volumeTotal'] ?? '' }}</span></td>
                    <td class="td-pri"><span>{{ $datas['stabilitas'] ?? '' }}</span></td>

                    @for ($i = 1; $i <= 8; $i++)
                        <td class="td-pri">
                            <span>{{ $datas['tanggalBulanPermintaan' . $i] ?? '' }}</span>
                        </td>
                    @endfor
                </tr>
            @endforeach

            <tr>
                <td colspan="3" class="td-pri">Catatan dokter:</td>
                <td colspan="4" class="td-pri">Catatan farmasi:</td>
                <td style="vertical-align: middle;" colspan="2" class="td-pri">Direview oleh</td>
                @for ($i = 1; $i <= 8; $i++)
                    <td class="td-pri">
                        <span>{{ $data['review-' . $i] ?? '' }}</span>
                    </td>
                @endfor
            </tr>

            <tr>
                <td class="td-pri" colspan="3" rowspan="3">
                    <span>{{ $data['catatanDokter'] ?? '' }}</span>
                </td>
                <td class="td-pri" colspan="4" rowspan="3">
                    <span>{{ $data['catatanFarmasi'] ?? '' }}</span>
                </td>
                <td colspan="2" class="td-pri">Dikerjakan oleh</td>
                @for ($i = 1; $i <= 8; $i++)
                    <td class="td-pri">
                        <span>{{ $data['kerjakan-' . $i] ?? '' }}</span>
                    </td>
                @endfor
            </tr>

            <tr>
                <td colspan="2" class="td-pri">Diserahkan oleh</td>
                @for ($i = 1; $i <= 8; $i++)
                    <td class="td-pri">
                        <span>{{ $data['serahkan-' . $i] ?? '' }}</span>
                    </td>
                @endfor
            </tr>

            <tr>
                <td colspan="2" class="td-pri">Diterima oleh</td>
                @for ($i = 1; $i <= 8; $i++)
                    <td class="td-pri">
                        <span>{{ $data['terima-' . $i] ?? '' }}</span>
                    </td>
                @endfor
            </tr>
        </tbody>
    </table>

    <span>Keterangan:</span> <br>
    <span><i>1. Satu lembar KIO KEMOTERAPI digunakan untuk peresepan 1 (satu) siklus kemoterapi</i></span><br>
    <span><i>2. Kolom yang berwarna abu-abu diisi oleh Farmasi</i></span>

</body>

</html>
