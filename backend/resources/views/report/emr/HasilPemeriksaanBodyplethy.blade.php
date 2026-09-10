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

    @if ($cekWargaNegaraWNA == true)
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
                        @if ($cekWargaNegaraWNA)
                            Bodyplethysmograph Examination Results
                        @else
                            Hasil Pemeriksaan Bodyplethysmograph
                        @endif
                    </span>
                </td>
            </tr>
        </table>

        <table class="table" width="100%" cellspacing="0" cellpadding="0"
            style="border-collapse: collapse; padding; margin-top: 15px; page-break-inside: avoid !important;">
            <tr>
                <td style="border: 1px solid black; width: 15%; padding: 10px;">
                    <span>Medical Report Number</span>
                </td>
                <td style="border: 1px solid black; padding-left: 10px;">
                    <span>{{ isset($data['norm']) ? $data['norm'] : '-' }}</span>
                </td>
                <td style="border: 1px solid black; padding: 10px;">
                    <span>Date / Time</span>
                </td>
                <td style="border: 1px solid black; padding-left: 10px;">
                    <span>
                        {{ isset($data['created_at'])
                            ? \Carbon\Carbon::parse($data['created_at'])->setTimezone('Asia/Jakarta')->format('d-m-Y H:i')
                            : '-' }}
                    </span>
                </td>
            </tr>
            <tr>
                <td style="border: 1px solid black; width: 15%; padding: 10px; width: 20%;">
                    <span>Patient Name</span>
                </td>
                <td style="border: 1px solid black; padding-left: 10px;">
                    <span>{{ isset($data['namaPasien']) ? $data['namaPasien'] : '-' }}</span>
                </td>
                <td style="border: 1px solid black; padding: 10px; width: 20%;">
                    <span>Doctor</span>
                </td>
                <td style="border: 1px solid black; padding-left: 10px;">
                    <span>{{ isset($data['dokterPemeriksa']) ? $data['dokterPemeriksa'] : '-' }}</span>
                </td>
            </tr>
            <tr>
                <td style="border: 1px solid black; width: 15%; padding: 10px;">
                    <span>Address</span>
                </td>
                <td style="border: 1px solid black; padding-left: 10px;">
                    <span>{{ isset($data['pasien']['alamatlengkap']) ? $data['pasien']['alamatlengkap'] : '-' }}</span>
                </td>
                <td style="border: 1px solid black; width: 15%; padding: 10px;">
                    <span>Date of Birth</span>
                </td>
                <td style="border: 1px solid black; padding-left: 10px;">
                    <span>{{ isset($data['tanggalLahirPasien']) ? $data['tanggalLahirPasien'] : '-' }}
                        {{ isset($data['umur']) ? $data['umur'] : '-' }}</span>
                </td>
            </tr>

        </table>

        <table class="kesimpulan-wrapper" width="100%" cellspacing="0" cellpadding="0"
            style="border-collapse: collapse; padding; margin-top: 15px; page-break-inside: avoid !important;">
            <tr>
                <td width="10%">Examination Result</td>
                <td width="70%">: </td>
            </tr>
            <tr>
                <td width="20%"></td>
                <td width="70%">
                    <table style="width: 100%;border-spacing: 10px;">
                        <tr>
                            <td style="width: 50%">VC Max :
                                {{ isset($data['TAVCmax']) ? $data['TAVCmax'] : '-' }}%</td>
                            <td style="width: 50%">FVC/pred :
                                {{ isset($data['TAFVCpred']) ? $data['TAFVCpred'] : '-' }}%</td>
                        </tr>
                        <tr>
                            <td style="width: 50%">FEV<sub>1</sub>/FVC :
                                {{ isset($data['TAFEV1FVCpred']) ? $data['TAFEV1FVCpred'] : '-' }}%</td>
                            <td style="width: 50%">FEF<sub>25/50/75</sub>/pred :
                                {{ isset($data['FEF255075pred']) ? $data['FEF255075pred'] : '-' }}%</td>
                        </tr>
                        <tr>
                            <td style="width: 50%">FEV<sub>1</sub>/pred :
                                {{ isset($data['TAFEV1pred']) ? $data['TAFEV1pred'] : '-' }}%
                            </td>
                            <td style="width: 50%">PEF/pred :
                                {{ isset($data['TAPEFpred']) ? $data['TAPEFpred'] : '-' }}%</td>
                        </tr>
                        <tr>
                            <td style="width: 50%">TCC :
                                {{ isset($data['TCC']) ? $data['TCC'] : '-' }}%
                            </td>
                            <td style="width: 50%">DLCCO :
                                {{ isset($data['DLCCO']) ? $data['DLCCO'] : '-' }}%
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

        <div class="kesimpulan-wrapper" style="page-break-inside: avoid; margin-top: 15px;">
            <table class="table" width="100%" cellspacing="0" cellpadding="0" style="border-collapse: collapse;">
                <tbody>
                    <tr>
                        <td width="20%">Conclusion :</td>
                        <td>
                            {{ isset($data['TAKesimpulan']) ? $data['TAKesimpulan'] : '-' }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <table class="no-page-break" width="100%" cellspacing="0" cellpadding="0"
            style="border-collapse: collapse; padding; margin-top: 15px; page-break-inside: avoid;">
            <tr>
                <td width="60%">Weight : {{ isset($data['TBSBeratBadan']) ? $data['TBSBeratBadan'] : '-' }} Kg</td>
                <td style="text-align: center" width="40%">
                    <br>
                    Garut, {{ translateDateToEnglish($identitas['dateNow']) }}
                </td>
            </tr>
            <tr>
                <td width="60%">Height : {{ isset($data['TBSTinggiBadan']) ? $data['TBSTinggiBadan'] : '-' }} Cm
                </td>
                <td style="text-align: center" width="40%">
                    Examining Doctor
                </td>
            </tr>
            <tr>
                <td width="60%"></td>
                <td style="text-align: center" width="40%">
                    <br>
                    <img src="data:image/png;base64, {!! $tte !!}">
                    <br><br>
                </td>
            </tr>
            <tr>
                <td width="60%"></td>
                <td style="text-align: center;font-weight: bold;font-size:8pt" width="40%">
                    @if (is_array($data['DDDokter']) && array_key_exists('label', $data['DDDokter']))
                        {{ $data['DDDokter']['label'] ?? '-' }}
                    @else
                        {{ $data['DDDokter'] ?? '-' }}
                    @endif
                </td>
            </tr>
            <tr>
                <td width="60%"></td>
                <td style="text-align: center;font-weight: bold;font-size:8pt" width="40%">
                    NIP :
                    @if (isset($data['nip']->nip))
                        {{ $data['nip']->nip }}
                    @else
                        {{ '-' }}
                    @endif
                </td>
            </tr>
        </table>
    @else
        <table class="table" width="100%" cellspacing="0" cellpadding="0"
            style="border-bottom: 1px solid black;border-collapse: collapse; padding; page-break-inside: avoid !important;">
            <tr>
                <td style="text-align: center; padding: 15px;">
                    <img src="{{ 'img/logo-cetakan-obgyn.png' }}" width="100px" height="100px"
                        style="display: block;">
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
                        @if ($cekWargaNegaraWNA)
                            Bodyplethysmograph Examination Results
                        @else
                            Hasil Pemeriksaan Bodyplethysmograph
                        @endif
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
                    <span>Tanggal / Jam Periksa</span>
                </td>
                <td style="border: 1px solid black; padding-left: 10px;">
                    <span>
                        {{ isset($data['created_at'])
                            ? \Carbon\Carbon::parse($data['created_at'])->setTimezone('Asia/Jakarta')->format('d-m-Y H:i')
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
                <td style="border: 1px solid black; padding: 10px; width: 20%;">
                    <span>Dokter Pemeriksa</span>
                </td>
                <td style="border: 1px solid black; padding-left: 10px;">
                    <span>{{ isset($data['dokterPemeriksa']) ? $data['dokterPemeriksa'] : '-' }}</span>
                </td>
            </tr>
            <tr>
                <td style="border: 1px solid black; width: 15%; padding: 10px;">
                    <span>Alamat Pasien</span>
                </td>
                <td style="border: 1px solid black; padding-left: 10px;">
                    <span>{{ isset($data['pasien']['alamatlengkap']) ? $data['pasien']['alamatlengkap'] : '-' }}</span>
                </td>
                <td style="border: 1px solid black; width: 15%; padding: 10px;">
                    <span>Tanggal Lahir</span>
                </td>
                <td style="border: 1px solid black; padding-left: 10px;">
                    <span>{{ isset($data['tanggalLahirPasien']) ? $data['tanggalLahirPasien'] : '-' }}
                        {{ isset($data['umur']) ? $data['umur'] : '-' }}</span>
                </td>
            </tr>

        </table>

        <table class="kesimpulan-wrapper" width="100%" cellspacing="0" cellpadding="0"
            style="border-collapse: collapse; padding; margin-top: 15px; page-break-inside: avoid !important;">
            <tr>
                <td width="10%">Hasil Pemeriksaan</td>
                <td width="70%">: </td>
            </tr>
            <tr>
                <td width="20%"></td>
                <td width="70%">
                    <table style="width: 100%;border-spacing: 10px;">
                        <tr>
                            <td style="width: 50%">VC Max :
                                {{ isset($data['TAVCmax']) ? $data['TAVCmax'] : '-' }}%</td>
                            <td style="width: 50%">FVC/pred :
                                {{ isset($data['TAFVCpred']) ? $data['TAFVCpred'] : '-' }}%</td>
                        </tr>
                        <tr>
                            <td style="width: 50%">FEV<sub>1</sub>/FVC :
                                {{ isset($data['TAFEV1FVCpred']) ? $data['TAFEV1FVCpred'] : '-' }}%</td>
                            <td style="width: 50%">FEF<sub>25/50/75</sub>/pred :
                                {{ isset($data['FEF255075pred']) ? $data['FEF255075pred'] : '-' }}%</td>
                        </tr>
                        <tr>
                            <td style="width: 50%">FEV<sub>1</sub>/pred :
                                {{ isset($data['TAFEV1pred']) ? $data['TAFEV1pred'] : '-' }}%
                            </td>
                            <td style="width: 50%">PEF/pred :
                                {{ isset($data['TAPEFpred']) ? $data['TAPEFpred'] : '-' }}%</td>
                        </tr>
                        <tr>
                            <td style="width: 50%">TCC :
                                {{ isset($data['TCC']) ? $data['TCC'] : '-' }}%
                            </td>
                            <td style="width: 50%">DLCCO :
                                {{ isset($data['DLCCO']) ? $data['DLCCO'] : '-' }}%
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

        <div class="kesimpulan-wrapper" style="page-break-inside: avoid; margin-top: 15px;">
            <table class="table" width="100%" cellspacing="0" cellpadding="0"
                style="border-collapse: collapse;">
                <tbody>
                    <tr>
                        <td width="20%">Kesimpulan :</td>
                        <td>
                            {{ isset($data['TAKesimpulan']) ? $data['TAKesimpulan'] : '-' }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <table class="no-page-break" width="100%" cellspacing="0" cellpadding="0"
            style="border-collapse: collapse; padding; margin-top: 15px; page-break-inside: avoid;">
            <tr>
                <td width="60%">Weight : {{ isset($data['TBSBeratBadan']) ? $data['TBSBeratBadan'] : '-' }} Kg</td>
                <td style="text-align: center" width="40%">
                    <br>
                    Garut, {{ translateDateToEnglish($identitas['dateNow']) }}
                </td>
            </tr>
            <tr>
                <td width="60%">Height : {{ isset($data['TBSTinggiBadan']) ? $data['TBSTinggiBadan'] : '-' }} Cm
                </td>
                <td style="text-align: center" width="40%">
                    Examining Doctor
                </td>
            </tr>
            <tr>
                <td width="60%"></td>
                <td style="text-align: center" width="40%">
                    <br>
                    <img src="data:image/png;base64, {!! $tte !!}">
                    <br><br>
                </td>
            </tr>
            <tr>
                <td width="60%"></td>
                <td style="text-align: center;font-weight: bold;font-size:8pt" width="40%">
                    @if (is_array($data['DDDokter']) && array_key_exists('label', $data['DDDokter']))
                        {{ $data['DDDokter']['label'] ?? '-' }}
                    @else
                        {{ $data['DDDokter'] ?? '-' }}
                    @endif
                </td>
            </tr>
            <tr>
                <td width="60%"></td>
                <td style="text-align: center;font-weight: bold;font-size:8pt" width="40%">
                    NIP :
                    @if (isset($data['nip']->nip))
                        {{ $data['nip']->nip }}
                    @else
                        {{ '-' }}
                    @endif
                </td>
            </tr>
        </table>
    @endif
</body>

</html>
