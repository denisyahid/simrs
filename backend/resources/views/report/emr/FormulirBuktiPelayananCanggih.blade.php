<!DOCTYPE html>
<html>

<head>
    <title>Formulir Bukti Pelayanan Canggih</title>
    @yield('page-style')
    <style>
        html,
        body {
            page-break-inside: avoid !important;
            font-family: Arial, Helvetica, sans-serif;
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
    @endphp


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

    <table width="100%" cellspacing="0" cellpadding="0" style="text-align: center; padding-bottom: 20px;">
        <tr>
            <span style="font-weight: bold;">BUKTI PELAYANAN CANGGIH HEMODIALISIS</span>
        </tr>
    </table>

    <table width="100%" cellspacing="0" cellpadding="0">
        <tr>
            <span>Saya yang bertanda-tangan dibawah ini :</span>
        </tr>
    </table>

    <table width="100%" cellspacing="0" cellpadding="0" style="margin-left: 30px; margin-top: 5px;">
        <tr style="text-align: start;">
            <td style="width: 100px;"><span>Nama</span></td>
            <td style="width: 10px;">:</td>
            <td>
                <span>{{ isset($data['CBDokter']) ? (is_array($data['CBDokter']) ? $data['CBDokter']['label'] : $data['CBDokter']) : '-' }}</span>
            </td>
        </tr>
        <tr>
            <td><span>Spesialis</span></td>
            <td>:</td>
            <td><span>{{ isset($data['spesialisBertandaTangan']) ? $data['spesialisBertandaTangan'] : 'Dokter Penyakit Dalam' }}</span>
            </td>
        </tr>
        <tr>
            <td><span>Jabatan</span></td>
            <td>:</td>
            <td><span>{{ isset($data['spesialisBertandaTangan']) ? $data['jabatanBertandaTangan'] : 'Dokter Penanggungjawab Hemodialisis' }}</span>
            </td>
        </tr>
    </table>

    <table width="100%" cellspacing="0" cellpadding="0">
        <tr>
            <span color="#000000">
                Memang Benar Telah Memberikan Pelayanan Canggih <span
                    style="text-decoration: underline; font-weight: 600;">HEMODIALISIS</span>
                Kepada :
            </span>
        </tr>
    </table>

    <table width="100%" cellspacing="0" cellpadding="0" style="margin-left: 30px; margin-top: 5px;">
        <tr>
            <td style="width: 200px;"><span>Nomor Rekam Medis:</span></td>
            <td style="width: 20px;">:</td>
            <td><span>{{ isset($data['norm']) ? $data['norm'] : '-' }}</span></td>
        </tr>
        <tr>
            <td><span>Nama Pasien:</span></td>
            <td>:</td>
            <td><span>{{ isset($data['namaPasien']) ? $data['namaPasien'] : '-' }}</span></td>
        </tr>
        <tr>
            <td><span>Tanggal Lahir/Umur:</span></td>
            <td>:</td>
            <td>
                <span>
                    <?php
                    function hitungUmur($tanggalLahir)
                    {
                        $tglLahir = new DateTime($tanggalLahir);
                        $sekarang = new DateTime();
                        $umur = $sekarang->diff($tglLahir);
                        return $umur->y . ' tahun, ' . $umur->m . ' bulan';
                    }
                    echo $data['pasien']['tgllahir'] . ' / ' . hitungUmur($data['pasien']['tgllahir']);
                    ?>
                </span>
            </td>
        </tr>
        <tr>
            <td><span>Jenis Kelamin:</span></td>
            <td>:</td>
            <td><span>{{ $data['pasien']['jeniskelamin'] }}</span></td>
        </tr>
        <tr>
            <td><span>Alamat:</span></td>
            <td>:</td>
            <td><span>{{ $data['pasien']['alamatlengkap'] }}</span></td>
        </tr>
        <tr>
            <td><span>Diagnosa:</span></td>
            <td>:</td>
            <td><span>{{ isset($data['diagnosa']) ? $data['diagnosa'] : '-' }}</span></td>
        </tr>
        <tr>
            <td><span>Ket:</span></td>
            <td>:</td>
            <td><span style="font-style: italic">{{ isset($data['keterangan']) ? $data['keterangan'] : '-' }}</span>
            </td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td>
                <span>
                    Mulai HD: Pukul
                    <?php
                    $waktu = new DateTime($data['jamKunjungan']);
                    $waktuWITA = $waktu->setTimezone(new DateTimeZone('Asia/Jakarta'));
                    echo $waktuWITA->format('H:i');
                    ?>
                </span>
                WIB
            </td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td>
                <span>
                    Selesai HD: Pukul
                    <?php
                    $waktu = new DateTime($data['jamSelesai']);
                    $waktuWITA = $waktu->setTimezone(new DateTimeZone('Asia/Jakarta'));
                    echo $waktuWITA->format('H:i');
                    ?>
                </span>
                WIB
            </td>
        </tr>
        <tr>
            <td colspan="3">
                <span>Demikian surat ini kami sampaikan untuk dapat dipergunakan sebagaimana mestinya.</span>
            </td>
        </tr>
    </table>
    <table class="no-page-break" width="100%" cellspacing="0" cellpadding="0"
        style="border-collapse: collapse; padding; page-break-inside: avoid; font-size:10pt;">
        <tr>
            <td width="40%"></td>
            <td style="text-align: center" width="40%">
                <span>Garut,
                    {{ isset($data['created_at'])
                        ? \Carbon\Carbon::parse($data['created_at'])->setTimezone('Asia/Jakarta')->format('d-m-Y')
                        : '-' }}</span>
            </td>
        </tr>
    </table>

    <div style="display: flex;">
        <table width="100%;">
            <tr>
                <td width="50%" style="text-align: center;">
                    @if (isset($data['TTDpasien']))
                        <span>Pasien Keluarga / Pasien</span>
                        <br>
                        {{-- <div style="width: 500px; height: 500px;">
                        </div> --}}
                        {{-- {!! base64_decode($qrcode2) !!} --}}
                        <img src="data:image/png;base64,{{ $qrcode2 }}" width="80px" border="0"
                            style="margin-top: 30px; margin-bottom: 20px;">
                        <br>
                        <span>{{ $data['pasien']['namapasien'] }}</span>
                    @endif
                </td>
                <td width="50%" style="text-align: center">
                    <span>Dokter yang Merawat</span>
                    <br>
                    <img src="data:image/png;base64, {!! $tte !!}">
                    <br>
                    <span>
                    dr. NI WAYAN INDAH ELYANI, Sp.PD
                    </span> <br>

                    <span>
                        @if (!empty($data['nip']->nip))
                                NIP : {{ $data['nip']->nip }}
                        @elseif (!empty($data['nosip']->nosip))
                            SIP : {{ $data['nosip']->nosip }}
                        @elseif (!empty($data['noskp']->noskp))
                            SKP : {{ $data['noskp']->noskp }}
                        @elseif (!empty($data['nipppk']->nipppk))
                            NIPPPK : {{ $data['nipppk']->nipppk }}
                        @else
                            {{ 'NIP: 197911182024212001' }}
                        @endif
                    </span>
                </td>
            </tr>
        </table>
    </div>

</body>

</html>
