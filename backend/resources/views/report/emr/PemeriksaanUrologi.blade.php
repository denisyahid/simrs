@extends('template.layout-emr-kop-surat')
@section('title')
    Pemeriksaan Urologi
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
                <u>HASIL PEMERIKSAAN USG UROLOGI</u><br>
            </div>
        </td>
    </tr>
    <tr>
        <td>
            <div class="table-flex">
                <table style="border-spacing: 0 8px;margin-left: 20px;">
                    <tr>
                        <td>Nama</td>
                        <td>: {{ $data['namaPasien'] ?? '-' }}</td>

                    </tr>
                    <tr>
                        <td>Tanggal Lahir / Umur</td>
                        <td>
                            <?php
                            function hitungUmur($tanggalLahir)
                            {
                                $tglLahir = new DateTime($tanggalLahir);
                                $sekarang = new DateTime();
                                $umur = $sekarang->diff($tglLahir);
                                return $umur->y . ' tahun, ' . $umur->m . ' bulan';
                            }
                            echo ':' . $data['pasien']['tgllahir'] . ' / ' . hitungUmur($data['pasien']['tgllahir']);
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td>Jenis Kelamin</td>
                        <td>: {{ $data['jeniskelamin'] ?? '-' }} </td>
                    </tr>
                    <tr>
                        <td>Alamat</td>
                        <td>: {{ $data['pasien']['alamatlengkap'] ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td>Nomor RM</td>
                        <td>: {{ $data['pasien']['nocm'] ?? '-' }}</td>
                    </tr>
                </table>

                <table style="border-spacing: 0 8px;margin-left: 20px; margin-top: 20px;">
                    <tr>
                        <td>Ginjal kanan</td>
                        <td>: {{ $data['ginjalKanan'] ?? '-' }}</td>

                    </tr>

                    <tr>
                        <td>Ginjal kiri</td>
                        <td>: {{ $data['ginjalKiri'] ?? '-' }}</td>
                    </tr>

                    <tr>
                        <td>Buli / Vesica</td>
                        <td>: {{ $data['buliVesica'] ?? '-' }}</td>
                    </tr>

                    @if ($data['jeniskelamin'] !== 'Perempuan')
                        <tr>
                            <td>Prostat</td>
                            <td>: {{ $data['prostat'] ?? '-' }}</td>
                        </tr>
                    @endif

                    <tr>
                        <td>Kesimpulan dan Saran</td>
                        <td>: {{ $data['kesimpulansaran'] ?? '-' }}</td>
                    </tr>

                </table>


                <table width="100%" style="margin-top: 10%">
                    <thead align="center">
                        <tr>
                            <td>Terima Kasih atas Kepercayannya</td>
                        </tr>
                        <tr>
                            <td>Hormat saya,</td>
                        </tr>
                        <tr>
                            <td style="margin-right: 100px;">
                                <img src="data:image/png;base64, {!! $tte !!}">
                            </td>
                        </tr>
                        <tr>
                            <td>{{$data['registrasi']['dokter']}}</td>
                        </tr>
                    </thead>
                </table>
            </div>
        </td>
    </tr>
@endsection
