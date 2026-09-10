@extends('template.layout-emr-kop-surat')
@section('title')
   Penunjang Poli THT
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
                <u>Hasil Audiometri</u><br>
            </div>
        </td>
    </tr>
    <tr>
        <td>
            <div style="padding:7px">
                <span>Yang bertanda tangan dibawah ini menerangkan dengan sebenarnya bahwa :</span>
                <div style="margin-left: 20px;">
                    <table width="100%" style="border-spacing: 0 8px;">
                        <tr>
                            <td width="30%">Nama</td>
                            <td width="70%">: {{ $data['namaPasien'] ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td width="30%">Tanggal lahir / Umur</td>
                            <td width="70%">
                                <?php
                                    function hitungUmur($tanggalLahir) {
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
                            <td width="30%">Jenis Kelamin</td>
                            <td width="70%">: {{ $data['jeniskelamin'] ?? '-' }} </td>
                        </tr>
                        <tr>
                            <td width="30%">Alamat</td>
                            <td width="70%">: {{ $data['pasien']['alamatlengkap'] ?? '-' }}</td>
                        </tr>
                    </table>
                </div>
                <span>Telinga Kanan</span>
                <div style="margin-left: 20px;">
                    <table width="100%" style="border-spacing: 0 8px;">
                        <tr>
                            <td width="30%">AC</td>
                            <td width="70%">: {{ $data['acTelingakanan'] ?? '-' }} dB</td>
                        </tr>
                        <tr>
                            <td width="30%">BC</td>
                            <td width="70%">
                                : {{ $data['bcTelingakanan'] ?? '-' }} dB
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span>Keterangan: {{isset($data['keteranganTelingaKanan']) ? $data['keteranganTelingaKanan'] : '-' }}</span>
                            </td>
                        </tr>
                    </table>
                </div>
                <span>Telinga Kiri</span>
                <div style="margin-left: 20px;">
                    <table width="100%" style="border-spacing: 0 8px;">
                        <tr>
                            <td width="30%">AC</td>
                            <td width="70%">: {{ $data['acTelingaKiri'] ?? '-' }} dB</td>
                        </tr>
                        <tr>
                            <td width="30%">BC</td>
                            <td width="70%">: {{ $data['bcTelingaKiri'] ?? '-' }} dB</td>
                        </tr>
                        <tr>
                            <td>
                                <span>Keterangan: {{isset($data['keteranganTelingaKiri']) ? $data['keteranganTelingaKiri'] : '-' }}</span>
                            </td>
                        </tr>
                    </table>
                </div>
                <table width="100%">
                    <thead align="center">
                        <tr>
                            <td style="margin-right: 100px;">
                                <img src="data:image/png;base64, {!! $tte !!}">
                            </td>
                        </tr>
                        <tr>
                            <td width="100%">
                                <span style="font-weight: bold;">
                                    {{ isset($data['DDDokter'])
                                    ? (is_array($data['DDDokter'])
                                        ? $data['DDDokter']['label']
                                        : $data['DDDokter'])
                                    : '-' }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td>
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
            </div>
        </td>
    </tr>
@endsection
