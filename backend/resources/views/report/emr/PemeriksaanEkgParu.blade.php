@extends('template.layout-emr-kop-surat')
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
@endsection
