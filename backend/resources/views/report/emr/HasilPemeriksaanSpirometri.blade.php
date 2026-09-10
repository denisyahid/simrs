@extends('template.layout-emr-kop-surat')
@section('title')
    @if ($cekWargaNegaraWNA)
        Spirometry Examination Results
    @else
        Hasil Pemeriksaan Spirometri
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
    </style>
@endsection

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

@section('content')
    @if ($cekWargaNegaraWNA == true)
        <tr>
            <td align="center">
                <div style="font-size: 15pt; font-weight: bold; color: #000000;padding:8px">
                    <u> SPIROMETRY EXAMINATION RESULTS </u><br>
                </div>
            </td>
        </tr>
        <tr>
            <td>
                <div style="padding:7px">
                    <div style="margin-left: 20px;margin-top: 10px">
                        <table width="100%" style="border-spacing: 0 8px;">
                            <tr>
                                <td width="30%">Name</td>
                                <td width="70%">: {{ $data['TBNamaPasien'] ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td width="30%">Gender / Age</td>
                                <td width="70%">
                                    :
                                    {{ isset($data['TBJenisKelaminPasien']) ? App\Traits\Valet::english($data['TBJenisKelaminPasien']) : '-' }}
                                    /
                                    {{ isset($data['TBSUmurPasien']) ? formatDateIndonesian($data['TBSUmurPasien']) : '-' }}
                                </td>
                            </tr>
                            <tr>
                                <td width="30%">NRM</td>
                                <td width="70%">:
                                    {{ isset($data['norm']) ? $data['norm'] : '-' }}
                                </td>
                            </tr>
                            <tr>
                                <td width="30%">Address</td>
                                <td width="70%">:
                                    {{ isset($data['TAAlamatPasien']) ? $data['TAAlamatPasien'] : '-' }}
                                </td>
                            </tr>
                            <tr>
                                <td width="30%">Height</td>
                                <td width="70%">:
                                    {{ isset($data['TBSTinggiBadan']) ? $data['TBSTinggiBadan'] : '-' }} Cm
                                </td>
                            </tr>
                            <tr>
                                <td width="30%">Weight</td>
                                <td width="70%">:
                                    {{ isset($data['TBSBeratBadan']) ? $data['TBSBeratBadan'] : '-' }} Kg
                                </td>
                            </tr>
                            <tr>
                                <td width="30%">Inspection Result</td>
                                <td width="70%">: </td>
                            </tr>
                            <tr>
                                <td width="30%"></td>
                                <td width="70%">
                                    <table style="width: 100%;border-spacing: 10px;">
                                        <tr>
                                            <td style="width: 50%">SVC/pred :
                                                {{ isset($data['TASVCpred']) ? $data['TASVCpred'] : '-' }}%</td>
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
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <td width="30%">Conclusion</td>
                                <td width="70%">:
                                    {{ isset($data['TAKesimpulan']) ? App\Traits\Valet::english($data['TAKesimpulan']) : '-' }}
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div style="margin-top: 10px;">
                        <table width="100%">
                            <tr>
                                <td width="60%"></td>
                                <td style="text-align: center" width="40%">
                                    <br>
                                    Garut, {{ translateDateToEnglish($identitas['dateNow']) }}
                                </td>
                            </tr>
                            <tr>
                                <td width="60%"></td>
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
                    </div>
                </div>
            </td>
        </tr>
    @else
        <tr>
            <td align="center">
                <div style="font-size: 15pt; font-weight: bold; color: #000000;padding:8px">
                    <u> HASIL PEMERIKSAAN SPIROMETRI </u><br>
                </div>
            </td>
        </tr>
        <tr>
            <td>
                <div style="padding:7px">
                    <div style="margin-left: 20px;margin-top: 10px">
                        <table width="100%" style="border-spacing: 0 8px;">
                            <tr>
                                <td width="30%">Nama</td>
                                <td width="70%">: {{ $data['TBNamaPasien'] ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td width="30%">Jenis Kelamin / Umur</td>
                                <td width="70%">:
                                    {{ isset($data['TBJenisKelaminPasien']) ? $data['TBJenisKelaminPasien'] : '-' }} /
                                    {{ isset($data['TBSUmurPasien']) ? $data['TBSUmurPasien'] : '-' }}
                                </td>
                            </tr>
                            <tr>
                                <td width="30%">NRM</td>
                                <td width="70%">:
                                    {{ isset($data['norm']) ? $data['norm'] : '-' }}
                                </td>
                            </tr>
                            <tr>
                                <td width="30%">Alamat</td>
                                <td width="70%">:
                                    {{ isset($data['TAAlamatPasien']) ? $data['TAAlamatPasien'] : '-' }}
                                </td>
                            </tr>
                            <tr>
                                <td width="30%">Tinggi Badan</td>
                                <td width="70%">:
                                    {{ isset($data['TBSTinggiBadan']) ? $data['TBSTinggiBadan'] : '-' }} Cm
                                </td>
                            </tr>
                            <tr>
                                <td width="30%">Berat Badan</td>
                                <td width="70%">:
                                    {{ isset($data['TBSBeratBadan']) ? $data['TBSBeratBadan'] : '-' }} Kg
                                </td>
                            </tr>
                            <tr>
                                <td width="30%">Hasil Pemeriksaan</td>
                                <td width="70%">: </td>
                            </tr>
                            <tr>
                                <td width="30%"></td>
                                <td width="70%">
                                    <table style="width: 100%;border-spacing: 10px;">
                                        <tr>
                                            <td style="width: 50%">SVC/pred :
                                                {{ isset($data['TASVCpred']) ? $data['TASVCpred'] : '-' }}%</td>
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
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <td width="30%">Kesimpulan</td>
                                <td width="70%">:
                                    {{ isset($data['TAKesimpulan']) ? $data['TAKesimpulan'] : '-' }}
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div style="margin-top: 10px;">
                        <table width="100%">
                            <tr>
                                <td width="60%"></td>
                                <td style="text-align: center" width="40%">
                                    <br>
                                    Garut, {{ \Carbon\Carbon::parse($data['registrasi']['tglregistrasi'])->format('d F Y') }}
                                </td>
                            </tr>
                            <tr>
                                <td width="60%"></td>
                                <td style="text-align: center" width="40%">
                                    Dokter Pemeriksa
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
                                    {{-- @if (is_array($data['DDDokter']) && array_key_exists('label', $data['DDDokter']))
                                        {{ $data['DDDokter']['label'] ?? '-' }}
                                    @else
                                        {{ $data['DDDokter'] ?? '-' }}
                                    @endif --}}
                                    @if (is_array($data['user_input']) && array_key_exists('namalengkap', $data['user_input']))
                                        {{ $data['user_input']['namalengkap'] ?? '-' }}
                                    @else
                                        {{ $data['user_input'] ?? '-' }}
                                    @endif
                                </td>
                            </tr>
                            @if (isset($data['nip']->nip))
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
                            @endif
                        </table>
                    </div>
                </div>
            </td>
        </tr>
    @endif
@endsection
