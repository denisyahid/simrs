<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Asesmen Medis Rawat Inap</title>
    <?php
    $peristaltikOptions = [['value' => 1, 'label' => 'Normal'], ['value' => 2, 'label' => 'Meningkat'], ['value' => 3, 'label' => 'Menurun']];

    function getPeristaltikLabel($value, $options)
    {
        foreach ($options as $option) {
            if ($option['value'] === $value) {
                return $option['label'];
            }
        }
        return ''; // Default to empty if value not found
    }

    $kehilanganbbGizi = [['value' => 0, 'label' => 'Tidak Ada'], ['value' => 1, 'label' => '< 10% BB Biasa'], ['value' => 2, 'label' => '> 10% BB Biasa']];

    $asupanterakhirGizi = [['value' => 0, 'label' => 'Tidak Berubah'], ['value' => 1, 'label' => 'Menurun < 50%'], ['value' => 2, 'label' => 'Menurun > 50%']];

    $gangguansaluranGizi = [['value' => 0, 'label' => 'Tidak Ada'], ['value' => 1, 'label' => 'Mual, ANOREKSIA'], ['value' => 2, 'label' => 'Muntah, Diare']];

    $kapasitasfungsiGizi = [['value' => 0, 'label' => 'Tidak Berubah'], ['value' => 1, 'label' => 'Menurun/sub-optimal 2 Minggu'], ['value' => 2, 'label' => 'Bedridden 2 Minggu/lebih']];

    $stressMetabolikGizi = [['value' => 0, 'label' => 'Ringan'], ['value' => 1, 'label' => 'Sedang'], ['value' => 2, 'label' => 'Berat']];

    $pemeriksafisikkGizi = [['value' => 0, 'label' => 'Tidak Ada'], ['value' => 1, 'label' => 'Ringan: +1'], ['value' => 2, 'label' => 'Sedang: +2'], ['value' => 3, 'label' => 'Berat: +3']];

    $sgaGizi = [['value' => 0, 'label' => 'A(0) status gizi baik'], ['value' => 1, 'label' => 'B(1-2) malnutrisi ringan'], ['value' => 2, 'label' => 'C(>2) malnutrisi berat']];

    $imtGizi = [['value' => 0, 'label' => '18,5-25'], ['value' => 1, 'label' => '25,1-30'], ['value' => 2, 'label' => '<18,5 atau>30']];

    $albuminGizi = [['value' => 0, 'label' => '>3,4'], ['value' => 1, 'label' => '2,5-3,4'], ['value' => 2, 'label' => '< 2,5']];

    $tclGizi = [['value' => 0, 'label' => '>1500'], ['value' => 1, 'label' => '900-1500'], ['value' => 2, 'label' => '< 1500']];

    $resikoMalnutrisiGizi = [['value' => 0, 'label' => 'Rendah'], ['value' => 1, 'label' => 'Sedang -> kontrol kembali, mengikuti penyakit utamanya'], ['value' => 2, 'label' => 'Tinggi -> kontrol setiap minggu']];

    $listPenapisanInsomnia = [
        ['caption' => 'Sulit memulai tidur', 'cb0' => '(0) tidak ada', 'cb1' => '(1) ringan', 'cb2' => '(2) sedang', 'cb3' => '(3) berat', 'cb4' => '(4) sangat berat'],
        ['caption' => 'Sulit mempertahankan tidur', 'cb0' => '(0) tidak ada', 'cb1' => '(1) ringan', 'cb2' => '(2) sedang', 'cb3' => '(3) berat', 'cb4' => '(4) sangat berat'],
        ['caption' => 'Bangun dari tidur terlalu awal', 'cb0' => '(0) tidak ada', 'cb1' => '(1) ringan', 'cb2' => '(2) sedang', 'cb3' => '(3) berat', 'cb4' => '(4) sangat berat'],
        ['caption' => 'Kepuasan terhadap pola tidur saat ini', 'cb0' => '(0) sangat puas', 'cb1' => '(1) Puas', 'cb2' => '(2) Sedikit puas', 'cb3' => '(3) Tidak puas', 'cb4' => '(4) Sangat tidak puas'],
        ['caption' => 'Apakah gangguan tidur ini mempengaruhi kualitas hidup anda', 'cb0' => '(0) Tidak jelas', 'cb1' => '(1) Sedikit', 'cb2' => '(2) Kadang-kadang', 'cb3' => '(3) Jelas', 'cb4' => '(4) Sangat jelas'],
        ['caption' => 'Apakah anda mengkhawatirkan gangguan tidur anda saat ini', 'cb0' => '(0) tidak', 'cb1' => '(1) Sedikit', 'cb2' => '(2) Kadang-kadang', 'cb3' => '(3) Khawatir', 'cb4' => '(4) Sangat khawatir'],
        ['caption' => 'Apakah gangguan tidur anda mempengaruhi aktivitas/fungsi anda sehari-hari', 'cb0' => '(0) tidak', 'cb1' => '(1) Sedikit', 'cb2' => '(2) Kadang-kadang', 'cb3' => '(3) Banyak mengganggu', 'cb4' => '(4) Sangat mengganggu'],
    ];

    $listPDVT = [['caption' => 'Kanker aktif (dalam terapi atau paliatif) (1)', 'value' => '1'], ['caption' => 'Paralisis, paresis, atau imobilisasi ekstremitas bawah (1)', 'value' => '1'], ['caption' => 'Tirah baring lebih dari 3 hari karena pembedahan (dalam 4 bulan) (1)', 'value' => '1'], ['caption' => 'Nyeri tekan terlokalisasi sepanjang distribusi vena dalam (1)', 'value' => '1'], ['caption' => 'Pembengkakan seluruh tungkai (1)', 'value' => '1'], ['caption' => 'Bengkak pada betis unilateral lebih dari 3 cm (di bawah tuberositas tibia) (1)', 'value' => '1'], ['caption' => 'Edema pitting unilateral (1)', 'value' => '1'], ['caption' => 'Kolateral vena superfisial (1)', 'value' => '1'], ['caption' => 'Ada diagnosis alternatif lain selain DVT dengan kemungkinan sama atau lebih (-2)', 'value' => '-2']];

    $ListPSF = [['caption' => '1. Mengontrol BAB'], ['caption' => '2. Mengontrol BAK'], ['caption' => '3. Membersihkan diri'], ['caption' => '4. Penggunaan toilet'], ['caption' => '5. Makan'], ['caption' => '6. Berpindah dari tidur ke duduk'], ['caption' => '7. Berjalan'], ['caption' => '8. Berpakaian'], ['caption' => '9. Naik turun tangga'], ['caption' => '10. Mandi'], ['caption' => 'Total']];

    $listPI = [['caption' => 'Apakah anda mengompol atau BAB tanpa disadari'], ['caption' => 'Tidak pernah (0)'], ['caption' => 'Kadang-kadang kehilangan kontrol berkemih/menggunakan alat bantu untuk berkemih & BAB (1)'], ['caption' => 'Kehilangan kontrol berkemih sedikitnya sekali dalam sebulan (2,5)'], ['caption' => 'Kehilangan kontrol berkemih sedikitnya 2 kali sebulan/kadang-kadang kehilangan kontrol BAB (4)'], ['caption' => 'Kehilangan kontrol BAB sedikitnya sekali dalam sebulan (5)'], ['caption' => 'Kehilangan kontrol berkemih sedikitnya sekali dalam seminggu (5,5)'], ['caption' => 'Kehilangan kontrol BAB sedikitnya 2 kali sebulan (6,5)'], ['caption' => 'Kehilangan kontrol BAB sedikitnya sekali seminggu/kehilangan kontrol berkemih sedikitnya sekali setiap hari (8)'], ['caption' => 'Kehilangan kontrol BAB sedikitnya sekali sehari (10)'], ['caption' => 'Tidak bisa mengontrol fungsi berkemih sama sekali (10,5)'], ['caption' => 'Tidak bisa mengontrol BAB sama sekali (11,5)']];

    $yesNoOptions = [['value' => 1, 'label' => 'Ya'], ['value' => 2, 'label' => 'Tidak']];

    $baikTidakOptions = [['value' => 1, 'label' => 'Baik'], ['value' => 2, 'label' => 'Tidak']];

    $normalOptions = [['value' => 1, 'label' => 'Normal'], ['value' => 2, 'label' => 'Abnormal']];
    $vulvaOptions = [['value' => 1, 'label' => 'Normal'], ['value' => 2, 'label' => 'Condiloma'], ['value' => 3, 'label' => 'Lesi']];
    $vaginaOptions = [['value' => 1, 'label' => 'Normal'], ['value' => 2, 'label' => 'Inlamasi'], ['value' => 3, 'label' => 'Discharge']];
    $cervixOptions = [['value' => 1, 'label' => 'Normal'], ['value' => 2, 'label' => 'Inlamasi']];
    $uterusOptions = [['value' => 1, 'label' => 'Normal'], ['value' => 2, 'label' => 'Fibroid']];
    $adnexaOptions = [['value' => 1, 'label' => 'Normal'], ['value' => 2, 'label' => 'Massa']];

    $ListPenapisanDepresi = [
        ['caption' => 'Apakah Anda sebenarnya puas dengan kehidupan anda?', 'nilai0' => 'Ya', 'nilai1' => 'Tidak'],
        ['caption' => 'Apakah Anda telah meninggalkan banyak kegiatan dan minat atau kesenangan Anda?', 'nilai0' => 'Tidak', 'nilai1' => 'Ya'],
        ['caption' => 'Apakah Anda merasa bahwa hidup Anda kosong?', 'nilai0' => 'Tidak', 'nilai1' => 'Ya'],
        ['caption' => 'Apakah Anda sering merasa bosan?', 'nilai0' => 'Tidak', 'nilai1' => 'Ya'],
        ['caption' => 'Apakah Anda sangat berharap terhadap masa depan?', 'nilai0' => 'Ya', 'nilai1' => 'Tidak'],
        ['caption' => 'Apakah Anda merasa terganggu dengan pikiran bahwa Anda tidak dapat keluar dari pikiran Anda?', 'nilai0' => 'Tidak', 'nilai1' => 'Ya'],
        ['caption' => 'Apakah Anda merasa mempunyai semangat yang baik setiap saat?', 'nilai0' => 'Ya', 'nilai1' => 'Tidak'],
        ['caption' => 'Apakah Anda merasa takut bahwa sesuatu yang buruk akan terjadi pada diri Anda?', 'nilai0' => 'Tidak', 'nilai1' => 'Ya'],
        ['caption' => 'Apakah Anda merasa bahagia untuk sebagian besar hidup anda?', 'nilai0' => 'Ya', 'nilai1' => 'Tidak'],
        ['caption' => 'Apakah Anda sering merasa tidak berdaya?', 'nilai0' => 'Tidak', 'nilai1' => 'Ya'],
        ['caption' => 'Apakah Anda sering merasa resah dan gelisah?', 'nilai0' => 'Tidak', 'nilai1' => 'Ya'],
        ['caption' => 'Apakah Anda lebih senang berada di rumah daripada keluar dan melakukan hal-hal baru?', 'nilai0' => 'Tidak', 'nilai1' => 'Ya'],
        ['caption' => 'Apakah Anda sering merasa khawatir dengan masa depan?', 'nilai0' => 'Tidak', 'nilai1' => 'Ya'],
        ['caption' => 'Apakah Anda merasa memiliki lebih banyak masalah dengan daya ingat dibandingkan kebanyakan orang?', 'nilai0' => 'Tidak', 'nilai1' => 'Ya'],
        ['caption' => 'Apakah menurut Anda hidup Anda saat ini menyenangkan?', 'nilai0' => 'Ya', 'nilai1' => 'Tidak'],
        ['caption' => 'Apakah Anda sering merasa sedih?', 'nilai0' => 'Tidak', 'nilai1' => 'Ya'],
        ['caption' => 'Apakah saat ini Anda merasa tidak berharga?', 'nilai0' => 'Tidak', 'nilai1' => 'Ya'],
        ['caption' => 'Apakah Anda merasa khawatir tentang masa lalu Anda?', 'nilai0' => 'Tidak', 'nilai1' => 'Ya'],
        ['caption' => 'Apakah Anda merasa hidup ini sangat menarik dan menyenangkan?', 'nilai0' => 'Ya', 'nilai1' => 'Tidak'],
        ['caption' => 'Apakah sulit bagi Anda untuk memulai sesuatu hal yang baru?', 'nilai0' => 'Tidak', 'nilai1' => 'Ya'],
        ['caption' => 'Apakah Anda merasa penuh semangat?', 'nilai0' => 'Ya', 'nilai1' => 'Tidak'],
        ['caption' => 'Apakah Anda merasa bahwa keadaan Anda sekarang tidak ada harapan?', 'nilai0' => 'Tidak', 'nilai1' => 'Ya'],
        ['caption' => 'Apakah Anda merasa orang lain memiliki keadaan yang lebih baik dari Anda?', 'nilai0' => 'Tidak', 'nilai1' => 'Ya'],
        ['caption' => 'Apakah Anda sering merasa sedih atas hal-hal kecil?', 'nilai0' => 'Tidak', 'nilai1' => 'Ya'],
        ['caption' => 'Apakah Anda sering merasa ingin menangis?', 'nilai0' => 'Tidak', 'nilai1' => 'Ya'],
        ['caption' => 'Apakah Anda memiliki kesulitan berkonsentrasi?', 'nilai0' => 'Tidak', 'nilai1' => 'Ya'],
        ['caption' => 'Apakah Anda senang ketika bangun di pagi hari?', 'nilai0' => 'Ya', 'nilai1' => 'Tidak'],
        ['caption' => 'Apakah Anda lebih memilih menghindari pertemuan sosial atau bermasyarakat?', 'nilai0' => 'Tidak', 'nilai1' => 'Ya'],
        ['caption' => 'Apakah mudah bagi Anda untuk membuat keputusan?', 'nilai0' => 'Ya', 'nilai1' => 'Tidak'],
        ['caption' => 'Apakah pikiran Anda secerah biasanya?', 'nilai0' => 'Ya', 'nilai1' => 'Tidak'],
    ];

    function getYesNoLabel($value, $options)
    {
        foreach ($options as $option) {
            if ($option['value'] == $value) {
                return $option['label'];
            }
        }
        return ''; // Default to empty if value not found
    }

    $extremitasOptions = [['value' => 1, 'label' => 'Hangat'], ['value' => 2, 'label' => 'Dingin']];

    function getExtremitasLabel($value, $options)
    {
        foreach ($options as $option) {
            if ($option['value'] === $value) {
                return $option['label'];
            }
        }
        return ''; // Default to empty if value not found
    }

    $selectedValue = isset($input['optionsnapza']) ? $input['optionsnapza'] : '';

    ?>
    <style>
        table {
            table-layout: fixed;
            border-collapse: collapse !important;
            width: 100%;
        }

        input[type=checkbox]:before {
            font-family: DejaVu Sans !important;
        }

        input[type=checkbox] {
            margin-bottom: -5px;
        }

        .bg-cyan {
            background-color: #7FFFD4;
        }

        .padding-y {
            padding-top: 3px;
            padding-bottom: 3px;
        }

        .fc {
            text-align: center;
        }

        .fl {
            text-align: left;
        }

        .fr {
            text-align: right;
        }

        .logo {
            font-family: Dejavu Sans
        }

        .vac {
            vertical-align: center;
        }

        .mt-7 {
            margin-top: 7px;
        }

        .bg-ijo {
            background-color: #7FFFD4;
        }

        .image-container {
            position: relative;
            display: flex;
            justify-content: center;
            align-items: center;
            width: 690px;
            height: 350px;
        }

        .image-container-2 {
            position: relative;
            display: flex;
            justify-content: center;
            align-items: center;
            width: 500px;
            height: 500px;
        }

        .image-container-3 {
            position: relative;
            display: flex;
            justify-content: center;
            align-items: center;
            width: 690px;
            height: 201px;
        }

        .image-container-4 {
            position: relative;
            display: flex;
            justify-content: center;
            justify-items: center;
            width: 300px;
            height: 360px;
        }

        .image-container-5 {
            position: relative;
            display: flex;
            justify-content: center;
            justify-items: center;
            width: 300px;
            height: 360px;
        }

        .image-container-6 {
            position: relative;
            display: flex;
            justify-content: center;
            justify-items: center;
            width: 190px;
            height: 320px;
        }

        .image-container-7 {
            position: relative;
            display: flex;
            justify-content: center;
            justify-items: center;
            width: 250px;
            height: 250px;
            transform: translateX(200px)
        }

        .tc {
            text-align: center;
        }

        .image-top,
        .image-bottom,
        .image-top-3 {
            position: absolute;
            width: 100%;
            height: 100%;
        }

        .image-bottom {
            z-index: 1;
        }

        .image-top {
            z-index: 2;
            transform: translateX(2px), translateY(-2px);
        }

        .image-top-3 {
            z-index: 2;
        }

        .image-top-2 {
            position: absolute;
            z-index: 2;
            width: 100%;
            height: 181px;
            transform: translateX(2px), translateY(-8px);
        }

        .ml-5 {
            margin-left: 5px;
        }

        .mb-5 {
            margin-bottom: 5px;
        }

        td {
            vertical-align: top !important;
        }

        .bt {
            border-top: 1px solid black;
        }

        .btl {
            border-top: 1px dashed gray;
        }

        .btg {
            border-top: 1px solid gray;
        }


        .mid {
            text-align: center !important;
        }

        .border {
            border: 1px solid black;
        }

        .border-x {
            border-left: 1px solid black;
            border-right: 1px solid black;
        }

        .btl2 {
            border-top: 1px dashed gray;
            margin-left: 3px;
            margin-right: 3px;
        }

        .font {
            font-size: 8pt !important;
        }

        .font-2 {
            font-size: 7pt !important;
        }

        .fontg {
            font-size: 9pt !important;
            color: gray;
        }

        /* Allow page breaks between table rows */
        tr {
            page-break-inside: avoid;
        }

        /* Force page breaks after specific rows if needed */
        tr.break-after {
            page-break-after: always;
        }
    </style>
</head>

@php
    $dt = $data;
@endphp

<body>
    @foreach ($dt as $data)
        @php
            $ruangan = isset($data['section_SL']) ? $data['section_SL']['label'] : $ruangan;
        @endphp
        <section style="page-break-after: page;">
            <table>
                <tr>
                    <td colspan="2">
                        <table class="border">
                            <td
                                style="background-color: lightblue; padding:5px; font-weight:bold; width: 50%;text-align: left">
                                RSUD BALI MANDARA
                            </td>
                            <td
                                style="background-color: lightblue; padding:5px; font-weight:bold; width: 50%; text-align: right;">
                                @yield('kode')
                            </td>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <table>
                            <td width="10%" style="text-align: center;padding: 10px" class="border">
                                <img src="{{ 'img/logo-rs.png' }}" width="80px" height="80px"
                                    style="display: block;">
                            </td>
                            <th width="40%"
                                style="vertical-align: middle;text-align: center;font-weight: bold;font-size: large;padding:10px"
                                class="border">
                                @if (!isset($ruangan) || stripos($ruangan, 'ANESTESI') != false)
                                    ASSESMEN PRA ANESTESI
                                @else
                                    ASESMEN MEDIS RAWAT INAP
                                @endif
                            </th>
                            <td width="40%" style="padding: 5px;font-size:10pt" class="border">
                                <table>
                                    <tr>
                                        <td style="text-align:left;width: 40%">Nama</td>
                                        <td style="text-align:left;width: 10%;text-align: center">:</td>
                                        <td style="text-align:left;width: 50%">{{ $pasien['namapasien'] }}</td>
                                    </tr>
                                    <tr>
                                        <td style="text-align:left;width: 40%">Tanggal Lahir</td>
                                        <td style="text-align:left;width: 10%;text-align: center">:</td>
                                        <td style="text-align:left;width: 50%">{{ $pasien['tgllahir'] }}</td>
                                    </tr>
                                    <tr>
                                        <td style="text-align:left;width: 40%">Jenis Kelamin</td>
                                        <td style="text-align:left;width: 10%;text-align: center">:</td>
                                        <td style="text-align:left;width: 50%">{{ $pasien['jeniskelamin'] }}</td>
                                    </tr>
                                    <tr>
                                        <td style="text-align:left;width: 40%">No. Rekam Medis</td>
                                        <td style="text-align:left;width: 10%;text-align: center">:</td>
                                        <td style="text-align:left;width: 50%">{{ $pasien['nocm'] }}</td>
                                    </tr>
                                </table>
                            </td>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <table class="border font">
                            <tr>
                                <td style="padding-left: 3px;padding-right: 3px;width:33%;">
                                    Tanggal Kedatangan :
                                    {{ isset($data['tanggalKedatangan']) ? \Carbon\Carbon::parse($data['tanggalKedatangan'])->format('d-m-Y') : '-' }}
                                </td>
                                <td style="padding-left: 3px;padding-right: 3px;width:33%" class="mid">
                                    Jam Kedatangan :
                                    {{ isset($data['jamKedatangan'])
                                        ? \Carbon\Carbon::parse($data['jamKedatangan'])->setTimezone('Asia/Jakarta')->format('H:i')
                                        : '-' }}
                                </td>
                                <td style="padding-left: 3px;padding-right: 3px;width:33%" class="mid">
                                    Jam Asesmen Awal :
                                    {{ isset($data['jamAsesmenAwal'])
                                        ? \Carbon\Carbon::parse($data['jamAsesmenAwal'])->setTimezone('Asia/Jakarta')->format('H:i')
                                        : '-' }}
                                </td>
                            </tr>
                            <tr>
                                <td colspan="3" style="padding-left: 3px;padding-right: 3px" class="bt">
                                    <table>
                                        <tr>
                                            <td
                                                style="width:50% word-wrap:break-word; white-space: normal; text-align:justify;;">
                                                Alloanamnesis :
                                                {{ isset($data['kebpilihanallo']) ? $data['kebpilihanallo'] : '-' }}<br>
                                                @php
                                                    if (isset($data['pilihanallo'])) {
                                                        switch ($data['pilihanallo']) {
                                                            case 1:
                                                                $data['pilihanallo'] = 'Suami/Istri';
                                                                break;
                                                            case 2:
                                                                $data['pilihanallo'] = 'Orang Tua';
                                                                break;
                                                            case 3:
                                                                $data['pilihanallo'] = 'Anak';
                                                                break;
                                                            case 4:
                                                                $data['pilihanallo'] = 'Pasien';
                                                                break;
                                                            case 5:
                                                                $data['pilihanallo'] = 'Lainnya';
                                                                break;
                                                            default:
                                                                $data['pilihanallo'] = '-';
                                                                break;
                                                        }
                                                    }
                                                @endphp
                                                {{ isset($data['pilihanallo']) ? $data['pilihanallo'] : '-' }}
                                            </td>
                                            <td
                                                style="width:50% word-wrap:break-word; white-space: normal; text-align:justify;;">
                                                Anamnesis :
                                                {{ isset($data['anamnesis']) ? $data['anamnesis'] : '-' }}
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="3" class="bt">
                                    <table>
                                        <tr>
                                            <td style="width: 25%">
                                                @php
                                                    if (isset($data['keadaanumum'])) {
                                                        switch ($data['keadaanumum']) {
                                                            case 1:
                                                                $data['keadaanumum'] = 'Baik';
                                                                break;
                                                            case 2:
                                                                $data['keadaanumum'] = 'Sedang';
                                                                break;
                                                            case 3:
                                                                $data['keadaanumum'] = 'Buruk';
                                                                break;
                                                            default:
                                                                $data['keadaanumum'] = '-';
                                                                break;
                                                        }
                                                    }
                                                @endphp
                                                Keadaan Umum :
                                                {{ isset($data['keadaanumum']) ? $data['keadaanumum'] : '-' }}
                                            </td>
                                            <td style="width: 25%">
                                                Tekanan Darah :
                                                {{ isset($data['tekananDarah']) ? $data['tekananDarah'] : '-' }} mmHg
                                            </td>
                                            <td style="width: 25%">
                                                PR : {{ isset($data['nadi']) ? $data['nadi'] : '-' }} x/menit
                                            </td>
                                            <td style="width: 25%">
                                                RR : {{ isset($data['nafas']) ? $data['nafas'] : '-' }} x/menit
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="width: 25%">
                                                Suhu : {{ isset($data['celcius']) ? $data['celcius'] : '-' }} °C
                                            </td>
                                            <td style="width: 25%">
                                                SaO2 : {{ isset($data['sao2']) ? $data['sao2'] : '-' }} %
                                            </td>
                                            <td style="width: 25%">
                                                Tinggi Badan : {{ isset($data['tt']) ? $data['tt'] : '-' }} cm
                                            </td>
                                            <td style="width: 25%">
                                                Berat Badan : {{ isset($data['bb']) ? $data['bb'] : '-' }} gram
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="4" style="margin-bottom: 5px;">
                                                GCS : E {{ isset($data['gcse']) ? $data['gcse'] : '-' }} V
                                                {{ isset($data['gcsv']) ? $data['gcsv'] : '-' }} M
                                                {{ isset($data['gcsm']) ? $data['gcsm'] : '-' }}
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <!-- START:PEMERIKSAAN FISIK -->
                @if (
                    !isset($ruangan) ||
                        (stripos($ruangan, 'PSIKOLOGI') === false &&
                            stripos($ruangan, 'OBGYN') === false &&
                            stripos($ruangan, 'TRADISIONAL') === false &&
                            stripos($ruangan, 'ONKOLOGI RADIASI') === false))
                    <tr>
                        <td colspan="2">
                            <table class="border font">
                                <tr>
                                    <td colspan="3">
                                        <table>
                                            <tr>
                                                <td colspan="4">
                                                    Pemeriksaan Fisik
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="4" class="btl">
                                                    1. Kepala
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="width: 25%">
                                                    <input type="checkbox"
                                                        {{ isset($data['kepala']) ? 'checked' : '' }} />
                                                    <span>Kepala
                                                        {{ isset($data['ketKepala']) ? ': ' . $data['ketKepala'] : '' }}</span>
                                                </td>
                                                <td style="width: 25%">
                                                    <input type="checkbox"
                                                        {{ isset($data['ubunubun']) ? 'checked' : '' }} />
                                                    <span>Ubun-ubun besar
                                                        {{ isset($data['ketUbunubun']) ? ': ' . $data['ketUbunubun'] : '' }}</span>
                                                </td>
                                                <td style="width: 25%">
                                                    <input type="checkbox"
                                                        {{ isset($data['normal']) ? 'checked' : '' }} />
                                                    <span>Normal</span>
                                                </td>
                                                <td style="width: 25%">
                                                    <input type="checkbox"
                                                        {{ isset($data['mikrosefali']) ? 'checked' : '' }} />
                                                    <span>Mikrosefali</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="width: 25%">
                                                    <input type="checkbox"
                                                        {{ isset($data['lingkarkepala']) ? 'checked' : '' }} />
                                                    <span>Lingkar Kepala
                                                        {{ isset($data['ketLingkarkepala']) ? ': ' . $data['ketLingkarkepala'] : '' }}</span>
                                                </td>
                                                <td style="width: 25%">
                                                    <input type="checkbox"
                                                        {{ isset($data['lingkarlainnya']) ? 'checked' : '' }} />
                                                    <span>Lainnya
                                                        {{ isset($data['ketLingkarlainnya']) ? ': ' . $data['ketLingkarlainnya'] : '' }}</span>
                                                </td>
                                                <td style="width: 25%">
                                                    <input type="checkbox"
                                                        {{ isset($data['makrosefali']) ? 'checked' : '' }} />
                                                    <span>Makrosefali</span>
                                                </td>
                                                <td style="width: 25%"></td>
                                            </tr>
                                            <tr>
                                                <td colspan="4" class="btl">
                                                    2. Mata
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="width: 25%">
                                                    <input type="checkbox"
                                                        {{ isset($data['anemis']) ? 'checked' : '' }} />
                                                    <span>Anemis
                                                        {{ isset($data['ketAnemis']) ? ': ' . $data['ketAnemis'] : '' }}</span>
                                                </td>
                                                <td style="width: 25%">
                                                    <input type="checkbox"
                                                        {{ isset($data['konjungtiva']) ? 'checked' : '' }} />
                                                    <span>Konjungtiva Pucat
                                                        {{ isset($data['ketKonjungtiva']) && $data['ketKonjungtiva'] == 1 ? ': ' . 'Ya' : ': Tidak' }}</span>
                                                </td>
                                                <td style="width: 25%">
                                                    <input type="checkbox"
                                                        {{ isset($data['pupil']) ? 'checked' : '' }} />
                                                    <span>Pupil Isokor
                                                        {{ isset($data['ketPupil']) && $data['ketPupil'] == 1 ? ': ' . 'Ya' : ': Tidak' }}</span>
                                                </td>
                                                <td style="width: 25%">
                                                    <input type="checkbox"
                                                        {{ isset($data['ikterus']) ? 'checked' : '' }} />
                                                    <span>Ikterus
                                                        {{ isset($data['ketIkterus']) ? ': ' . $data['ketIkterus'] : '-' }}
                                                    </span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="width: 25%">
                                                    <input type="checkbox"
                                                        {{ isset($data['hiperemi']) ? 'checked' : '' }} />
                                                    <span>Hiperemi
                                                        {{ isset($data['ketHiperemi']) && $data['ketHiperemi'] == 1 ? ': ' . 'Ya' : ': Tidak' }}</span>
                                                </td>
                                                <td style="width: 25%">
                                                    <input type="checkbox"
                                                        {{ isset($data['refleks']) ? 'checked' : '' }} />
                                                    <span>Refleks Cahaya
                                                        {{ isset($data['ketRefleks']) ? ': ' . $data['ketRefleks'] : '-' }}</span>
                                                </td>
                                                <td style="width: 25%">
                                                    <input type="checkbox"
                                                        {{ isset($data['refpupil']) ? 'checked' : '' }} />
                                                    <span>Refleks Pupil
                                                        {{ isset($data['ketIkterus']) ? ': ' . $data['ketIkterus'] : '-' }}
                                                </td>
                                                <td style="width: 25%">
                                                    <input type="checkbox"
                                                        {{ isset($data['secret']) ? 'checked' : '' }} />
                                                    <span>Secret
                                                        {{ isset($data['ketSecret']) && $data['ketSecret'] == 1 ? ': ' . 'Ya' : ': Tidak' }}</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="width: 25%">
                                                    <input type="checkbox"
                                                        {{ isset($data['oedema']) ? 'checked' : '' }} />
                                                    <span>Oedema
                                                        {{ isset($data['ketOedema']) && $data['ketOedema'] == 1 ? ': ' . 'Ya' : ': Tidak' }}</span>
                                                </td>
                                                <td style="width: 25%">
                                                    <input type="checkbox"
                                                        {{ isset($data['oedemapal']) ? 'checked' : '' }} />
                                                    <span>
                                                        Oedema Palpebrae
                                                        {{ isset($data['ketOedemapal']) ? ': ' . $data['ketOedemapal'] : '-' }}
                                                    </span>
                                                </td>
                                                <td style="width: 25%">
                                                    <input type="checkbox"
                                                        {{ isset($data['skleraik']) ? 'checked' : '' }} />
                                                    <span>Sklera Ikteris
                                                        {{ isset($data['ketSkleraik']) && $data['ketSkleraik'] == 1 ? ': ' . 'Ya' : ': Tidak' }}</span>
                                                </td>
                                                <td style="width: 25%"></td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <table class="border font">
                                <tr>
                                    <td colspan="3">
                                        <table>
                                            <tr>
                                                <td colspan="4">
                                                    3. THT
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="width: 25%">
                                                    <input type="checkbox"
                                                        {{ isset($data['tonsil']) ? 'checked' : '' }} />
                                                    <span>
                                                        Tonsil :
                                                        {{ isset($data['ketTonsil']) ? $data['ketTonsil'] : '' }}
                                                    </span>
                                                </td>
                                                <td style="width: 25%">
                                                    <input type="checkbox"
                                                        {{ isset($data['hidung']) ? 'checked' : '' }} />
                                                    <span>
                                                        Hidung :
                                                        {{ isset($data['ketHidung']) ? $data['ketHidung'] : '' }}
                                                    </span>
                                                </td>
                                                <td style="width: 25%">
                                                    <input type="checkbox"
                                                        {{ isset($data['lainlain']) ? 'checked' : '' }} />
                                                    <span>
                                                        Lain-lain :
                                                        {{ isset($data['ketLain']) ? $data['ketLain'] : '' }}
                                                    </span>
                                                </td>
                                                <td style="width: 25%">
                                                    <input type="checkbox"
                                                        {{ isset($data['pharing']) ? 'checked' : '' }} />
                                                    <span>
                                                        Pharing :
                                                        {{ isset($data['ketPharing']) ? $data['ketPharing'] : '' }}
                                                    </span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="width: 25%">
                                                    <input type="checkbox"
                                                        {{ isset($data['bibir']) ? 'checked' : '' }} />
                                                    <span>
                                                        Bibir : {{ isset($data['ketBibir']) ? $data['ketBibir'] : '' }}
                                                    </span>
                                                </td>
                                                <td style="width: 25%">
                                                    <input type="checkbox"
                                                        {{ isset($data['lidah']) ? 'checked' : '' }} />
                                                    <span>
                                                        Lidah : {{ isset($data['ketLidah']) ? $data['ketLidah'] : '' }}
                                                    </span>
                                                </td>
                                                <td style="width: 25%">
                                                    <input type="checkbox"
                                                        {{ isset($data['telinga']) ? 'checked' : '' }} />
                                                    <span>
                                                        Telinga :
                                                        {{ isset($data['ketTelinga']) ? $data['ketTelinga'] : '' }}
                                                    </span>
                                                </td>
                                                <td style="width: 25%"></td>
                                            </tr>
                                            <tr>
                                                <td colspan="4" class="btl">
                                                    4. Leher
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="width: 25%">
                                                    <input type="checkbox"
                                                        {{ isset($data['jvp']) ? 'checked' : '' }} />
                                                    <span>
                                                        JVP : {{ isset($data['ketJVP']) ? $data['ketJVP'] : '' }}
                                                    </span>
                                                </td>
                                                <td style="width: 25%">
                                                    <input type="checkbox"
                                                        {{ isset($data['hidung']) ? 'checked' : '' }} />
                                                    <span>
                                                        Kaku Kuduk
                                                    </span>
                                                </td>
                                                <td style="width: 25%">
                                                    <input type="checkbox"
                                                        {{ isset($data['tunggal']) ? 'checked' : '' }} />
                                                    <span>
                                                        Tunggal
                                                    </span>
                                                </td>
                                                <td style="width: 25%">
                                                    <input type="checkbox"
                                                        {{ isset($data['multiple']) ? 'checked' : '' }} />
                                                    <span>
                                                        Multiple
                                                    </span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="width: 25%">
                                                    <input type="checkbox"
                                                        {{ isset($data['lainnya']) ? 'checked' : '' }} />
                                                    <span>
                                                        Lainnya :
                                                        {{ isset($data['ketLainnya']) ? $data['ketLainnya'] : '' }}
                                                    </span>
                                                </td>
                                                <td style="width: 50%" colspan="2">
                                                    <input type="checkbox"
                                                        {{ isset($data['kelenjar']) ? 'checked' : '' }} />
                                                    <span>
                                                        Pharing :
                                                        {{ isset($data['ketKelenjar']) ? $data['ketKelenjar'] : '' }}
                                                    </span>
                                                </td>
                                                <td style="width: 25%"></td>
                                            </tr>
                                            <tr>
                                                <td colspan="4" class="btl">
                                                    5. Thorax
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="width: 50%" colspan="2">
                                                    <input type="checkbox"
                                                        {{ isset($data['simetris']) ? 'checked' : '' }} />
                                                    <span>
                                                        Simetris / Asimetris :
                                                        {{ isset($data['ketSimetris']) ? $data['ketSimetris'] : '' }}
                                                    </span>
                                                </td>
                                                <td style="width: 50%" colspan="2">
                                                    <input type="checkbox"
                                                        {{ isset($data['retraksi']) ? 'checked' : '' }} />
                                                    <span>
                                                        Retraksi
                                                        {{ isset($data['ketRetraksi']) ? ': ' . $data['ketRetraksi'] : '' }}
                                                    </span>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <table class="border font">
                                <tr>
                                    <td colspan="3">
                                        <table>
                                            <tr>
                                                <td colspan="4">
                                                    6. Cor
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="width: 50%" colspan="2">
                                                    <b>Inspeksi Iktus Kordis</b>
                                                    <table>
                                                        <tr>
                                                            <td style="width: 50%">
                                                                <input type="checkbox"
                                                                    {{ isset($data['normalcor']) ? 'checked' : '' }} />
                                                                <span>
                                                                    Normal
                                                                </span>
                                                            </td>
                                                            <td style="width: 50%">
                                                                <input type="checkbox"
                                                                    {{ isset($data['melebar']) ? 'checked' : '' }} />
                                                                <span>
                                                                    Melebar
                                                                    {{ isset($data['ketMelebar']) ? ': ' . $data['ketMelebar'] : '' }}
                                                                </span>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="2">
                                                                Lokasi
                                                                {{ isset($data['ketLokasi']) ? ': ' . $data['ketLokasi'] : '' }}
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                                <td style="width: 50%" colspan="2">
                                                    <b>Palpasi Iktus Kordis</b>
                                                    <table>
                                                        <tr>
                                                            <td style="width: 33%">
                                                                <input type="checkbox"
                                                                    {{ isset($data['palpasinormal']) ? 'checked' : '' }} />
                                                                <span>
                                                                    Normal
                                                                </span>
                                                            </td>
                                                            <td style="width: 33%">
                                                                <input type="checkbox"
                                                                    {{ isset($data['kuatangkat']) ? 'checked' : '' }} />
                                                                <span>
                                                                    Kuat Angkat
                                                                </span>
                                                            </td>
                                                            <td style="width: 33%">
                                                                <input type="checkbox"
                                                                    {{ isset($data['meluas']) ? 'checked' : '' }} />
                                                                <span>
                                                                    Meluas
                                                                </span>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="3">
                                                                Lokasi
                                                                {{ isset($data['ketLokasiPalpasi']) ? ': ' . $data['ketLokasiPalpasi'] : '' }}
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="width: 50%" colspan="2">
                                                    <b>Inspeksi Pulsasi</b>
                                                    <table>
                                                        <tr>
                                                            <td style="width: 33%">
                                                                <input type="checkbox"
                                                                    {{ isset($data['apex']) ? 'checked' : '' }} />
                                                                <span>
                                                                    Normal
                                                                </span>
                                                            </td>
                                                            <td style="width: 33%">
                                                                <input type="checkbox"
                                                                    {{ isset($data['prekordium']) ? 'checked' : '' }} />
                                                                <span>
                                                                    Prekordium
                                                                </span>
                                                            </td>
                                                            <td style="width: 33%">
                                                                <input type="checkbox"
                                                                    {{ isset($data['epigastrium']) ? 'checked' : '' }} />
                                                                <span>
                                                                    Epigastrium
                                                                </span>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td style="width: 33%">
                                                                <input type="checkbox"
                                                                    {{ isset($data['corlainnya']) ? 'checked' : '' }} />
                                                                <span>
                                                                    Lainnya
                                                                </span>
                                                            </td>
                                                            <td colspan="2">
                                                                {{ isset($data['ketLainnya']) ? ': ' . $data['ketLainnya'] : '' }}
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                                <td style="width: 50%" colspan="2">
                                                    <b>Palpasi Thrill</b>
                                                    <table>
                                                        <tr>
                                                            <td style="width: 50%">
                                                                <input type="checkbox"
                                                                    {{ isset($data['sistolik']) ? 'checked' : '' }} />
                                                                <span>
                                                                    Sistolik
                                                                </span>
                                                            </td>
                                                            <td style="width: 50%">
                                                                <input type="checkbox"
                                                                    {{ isset($data['diastolik']) ? 'checked' : '' }} />
                                                                <span>
                                                                    Diatolik
                                                                </span>
                                                            </td>
                                                        </tr>
                                                        <b>Suara Jantung Tambahan</b>
                                                        <tr>
                                                            <td style="width: 50%">
                                                                <input type="checkbox"
                                                                    {{ isset($data['murmur']) ? 'checked' : '' }} />
                                                                <span>
                                                                    Murmur
                                                                </span>
                                                            </td>
                                                            <td style="width: 50%">
                                                                {{ isset($data['ketMurmur']) ? ': ' . $data['ketMurmur'] : '' }}
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="width: 50%" colspan="2">
                                                    <b>Suara Jantung utama</b>
                                                    <table>
                                                        <tr>
                                                            <td style="width: 50%">
                                                                <input type="checkbox"
                                                                    {{ isset($data['s1s2']) ? 'checked' : '' }} />
                                                                <span>
                                                                    S1, S2
                                                                </span>
                                                            </td>
                                                            <td style="width: 50%">
                                                                {{ isset($data['ketTunggal']) ? ': ' . $data['ketTunggal'] : '' }}
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td style="width: 50%">
                                                                <input type="checkbox"
                                                                    {{ isset($data['regular']) ? 'checked' : '' }} />
                                                                <span>
                                                                    Regular
                                                                </span>
                                                            </td>
                                                            <td style="width: 50%">
                                                                <input type="checkbox"
                                                                    {{ isset($data['iregular']) ? 'checked' : '' }} />
                                                                <span>
                                                                    Iregular
                                                                </span>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td style="width: 50%">
                                                                <input type="checkbox"
                                                                    {{ isset($data['systole']) ? 'checked' : '' }} />
                                                                <span>
                                                                    Extra Systole
                                                                </span>
                                                            </td>
                                                            <td style="width: 50%">
                                                                <input type="checkbox"
                                                                    {{ isset($data['gallop']) ? 'checked' : '' }} />
                                                                <span>
                                                                    Gallop
                                                                </span>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="width: 50%" colspan="2">
                                                    <b>Perkusi</b>
                                                    <table>
                                                        <tr>
                                                            <td style="width: 50%">
                                                                <input type="checkbox"
                                                                    {{ isset($data['batasatas']) ? 'checked' : '' }} />
                                                                <span>
                                                                    Batas Atas
                                                                </span>
                                                            </td>
                                                            <td style="width: 50%">
                                                                {{ isset($data['ketBatasatas']) ? ': ' . $data['ketBatasatas'] : '' }}
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td style="width: 50%">
                                                                <input type="checkbox"
                                                                    {{ isset($data['batasbawah']) ? 'checked' : '' }} />
                                                                <span>
                                                                    Batas Bawah
                                                                </span>
                                                            </td>
                                                            <td style="width: 50%">
                                                                {{ isset($data['ketBatasbawah']) ? ': ' . $data['ketBatasbawah'] : '' }}
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td style="width: 50%">
                                                                <input type="checkbox"
                                                                    {{ isset($data['bataskanan']) ? 'checked' : '' }} />
                                                                <span>
                                                                    Batas Kanan
                                                                </span>
                                                            </td>
                                                            <td style="width: 50%">
                                                                {{ isset($data['ketBataskanan']) ? ': ' . $data['ketBataskanan'] : '' }}
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td style="width: 50%">
                                                                <input type="checkbox"
                                                                    {{ isset($data['bataskiri']) ? 'checked' : '' }} />
                                                                <span>
                                                                    Batas Kiri
                                                                </span>
                                                            </td>
                                                            <td style="width: 50%">
                                                                {{ isset($data['ketBataskiri']) ? ': ' . $data['ketBataskiri'] : '' }}
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="4" class="btl">
                                                    7. Pulmo
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="width: 100%" colspan="4">
                                                    <table style="width:100%">
                                                        <tr>
                                                            <td style="width: 33%">
                                                                <table>
                                                                    <tr>
                                                                        <td style="width: 60%">
                                                                            <input type="checkbox"
                                                                                {{ isset($data['statis']) ? 'checked' : '' }} />
                                                                            <span>
                                                                                Inspeksi Statis
                                                                            </span>
                                                                        </td>
                                                                        <td style="width: 40%;">
                                                                            {{ isset($data['ketStatis']) ? ': ' . $data['ketStatis'] : '' }}
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td style="width: 60%">
                                                                            <input type="checkbox"
                                                                                {{ isset($data['dinamis']) ? 'checked' : '' }} />
                                                                            <span>
                                                                                Inspeksi Dinamis
                                                                            </span>
                                                                        </td>
                                                                        <td style="width: 40%;">
                                                                            {{ isset($data['ketDinamis']) ? ': ' . $data['ketDinamis'] : '' }}
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td style="width: 60%">
                                                                            <input type="checkbox"
                                                                                {{ isset($data['palpasi']) ? 'checked' : '' }} />
                                                                            <span>
                                                                                Palpasi: SF
                                                                            </span>
                                                                        </td>
                                                                        <td style="width: 40%;">
                                                                            {{ isset($data['ketPalpasi']) ? ': ' . $data['ketPalpasi'] : '' }}
                                                                        </td>
                                                                    </tr>
                                                                </table>
                                                            </td>
                                                            <td style="width: 33%">
                                                                <table>
                                                                    <tr>
                                                                        <td style="width: 60%">
                                                                            <input type="checkbox"
                                                                                {{ isset($data['perkusi']) ? 'checked' : '' }} />
                                                                            <span>
                                                                                Perkusi
                                                                            </span>
                                                                        </td>
                                                                        <td style="width: 40%;">
                                                                            {{ isset($data['ketPerkusi']) ? ': ' . $data['ketPerkusi'] : '' }}
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td style="width: 60%">
                                                                            <input type="checkbox"
                                                                                {{ isset($data['ronchi']) ? 'checked' : '' }} />
                                                                            <span>
                                                                                Ronchi
                                                                            </span>
                                                                        </td>
                                                                        <td style="width: 40%;">
                                                                            {{ isset($data['ketRonchi']) ? ': ' . $data['ketRonchi'] : '' }}
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td style="width: 60%">
                                                                            <input type="checkbox"
                                                                                {{ isset($data['wheezing']) ? 'checked' : '' }} />
                                                                            <span>
                                                                                Auskultasi Wheezing
                                                                            </span>
                                                                        </td>
                                                                        <td style="width: 40%;">
                                                                            {{ isset($data['ketWheezing']) ? ': ' . $data['ketWheezing'] : '' }}
                                                                        </td>
                                                                    </tr>

                                                                </table>
                                                            </td>
                                                            <td style="width: 33%">
                                                                <table>
                                                                    <tr>
                                                                        <td style="width: 60%">
                                                                            <input type="checkbox"
                                                                                {{ isset($data['auskultasi']) ? 'checked' : '' }} />
                                                                            <span>
                                                                                Auskultasi Vesikuler
                                                                            </span>
                                                                        </td>
                                                                        <td style="width: 40%;">
                                                                            {{ isset($data['ketAuskultasi']) ? ': ' . $data['ketAuskultasi'] : '' }}
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td style="width: 60%">
                                                                            <input type="checkbox"
                                                                                {{ isset($data['nafas']) ? 'checked' : '' }} />
                                                                            <span>
                                                                                Suara Nafas
                                                                            </span>
                                                                        </td>
                                                                        <td style="width: 40%;">
                                                                            {{ isset($data['ketNafas']) ? ': ' . $data['ketNafas'] : '' }}
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td style="width: 60%">
                                                                            <input type="checkbox"
                                                                                {{ isset($data['lainnyapulmo']) ? 'checked' : '' }} />
                                                                            <span>
                                                                                Lain-lain
                                                                            </span>
                                                                        </td>
                                                                        <td style="width: 40%;">
                                                                            {{ isset($data['ketLainnyapulmo']) ? ': ' . $data['ketLainnyapulmo'] : '' }}
                                                                        </td>
                                                                    </tr>
                                                                </table>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <table class="border font">
                                <tr>
                                    <td colspan="3">
                                        <table>
                                            <tr>
                                                <td colspan="4">
                                                    8. Abdomen
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="width: 100%" colspan="4">
                                                    <table style="width:100%">
                                                        <tr>
                                                            <td style="width: 33%">
                                                                <table>
                                                                    <tr>
                                                                        <td style="width: 60%">
                                                                            <span>
                                                                                Peristaltik
                                                                                @if (isset($data['ketPeristaltik']))
                                                                                    :
                                                                                    {{ getPeristaltikLabel($data['ketPeristaltik'], $peristaltikOptions) }}
                                                                                @endif
                                                                            </span>
                                                                        </td>
                                                                        <td style="width: 40%;">
                                                                            {{ isset($data['ketStatis']) ? ': ' . $data['ketStatis'] : '' }}
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td style="width: 60%">
                                                                            <input type="checkbox"
                                                                                {{ isset($data['souffle']) ? 'checked' : '' }} />
                                                                            <span>
                                                                                Souffle
                                                                            </span>
                                                                        </td>
                                                                        <td style="width: 40%;">
                                                                            {{ isset($data['ketSouffle']) ? ': ' . $data['ketSouffle'] : '' }}
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td style="width: 60%">
                                                                            <input type="checkbox"
                                                                                {{ isset($data['nyeri']) ? 'checked' : '' }} />
                                                                            <span>
                                                                                Nyeri Tekan Lokasi
                                                                            </span>
                                                                        </td>
                                                                        <td style="width: 40%;">
                                                                            {{ isset($data['ketNyeri']) ? ': ' . $data['ketNyeri'] : '' }}
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td style="width: 60%">
                                                                            <input type="checkbox"
                                                                                {{ isset($data['hepar']) ? 'checked' : '' }} />
                                                                            <span>
                                                                                Hepar
                                                                            </span>
                                                                        </td>
                                                                        <td style="width: 40%;">
                                                                            {{ isset($data['ketHepar']) ? ': ' . $data['ketHepar'] : '' }}
                                                                        </td>
                                                                    </tr>
                                                                </table>
                                                            </td>
                                                            <td style="width: 33%">
                                                                <table>
                                                                    <tr>
                                                                        <td style="width: 60%">
                                                                            <input type="checkbox"
                                                                                {{ isset($data['distensi']) ? 'checked' : '' }} />
                                                                            <span>
                                                                                Distensi
                                                                            </span>
                                                                        </td>
                                                                        <td style="width: 40%;">
                                                                            {{ isset($data['ketDistensi']) ? ': ' . $data['ketDistensi'] : '' }}
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td style="width: 60%">
                                                                            <input type="checkbox"
                                                                                {{ isset($data['ascites']) ? 'checked' : '' }} />
                                                                            <span>
                                                                                Ascites
                                                                            </span>
                                                                        </td>
                                                                        <td style="width: 40%;">
                                                                            {{ isset($data['ketAscites']) ? ': ' . $data['ketAscites'] : '' }}
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td style="width: 60%">
                                                                            <input type="checkbox"
                                                                                {{ isset($data['lien']) ? 'checked' : '' }} />
                                                                            <span>
                                                                                Lien
                                                                            </span>
                                                                        </td>
                                                                        <td style="width: 40%;">
                                                                            {{ isset($data['ketLien']) ? ': ' . $data['ketLien'] : '' }}
                                                                        </td>
                                                                    </tr>

                                                                </table>
                                                            </td>
                                                            <td style="width: 33%">
                                                                <table>
                                                                    <tr>
                                                                        <td style="width: 60%">
                                                                            <input type="checkbox"
                                                                                {{ isset($data['meteorismus']) ? 'checked' : '' }} />
                                                                            <span>
                                                                                Meteorismus
                                                                            </span>
                                                                        </td>
                                                                        <td style="width: 40%;">
                                                                            {{ isset($data['ketMeteorismus']) ? ': ' . $data['ketMeteorismus'] : '' }}
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td style="width: 60%">
                                                                            <input type="checkbox"
                                                                                {{ isset($data['turgor']) ? 'checked' : '' }} />
                                                                            <span>
                                                                                Turgor
                                                                            </span>
                                                                        </td>
                                                                        <td style="width: 40%;">
                                                                            {{ isset($data['ketTurgor']) ? ': ' . $data['ketTurgor'] : '' }}
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td style="width: 60%">
                                                                            <input type="checkbox"
                                                                                {{ isset($data['massa']) ? 'checked' : '' }} />
                                                                            <span>
                                                                                Massa
                                                                            </span>
                                                                        </td>
                                                                        <td style="width: 40%;">
                                                                            {{ isset($data['ketMassa']) ? ': ' . $data['ketMassa'] : '' }}
                                                                        </td>
                                                                    </tr>
                                                                </table>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="4" class="btl">
                                                    9. Extremitas
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="width: 50%" colspan="2">
                                                    <table>
                                                        <tr>
                                                            <td style="width: 50%;">
                                                                <span>
                                                                    Extremitas
                                                                </span>
                                                            </td>
                                                            <td style="width: 50%">
                                                                <span>
                                                                    @if (isset($data['ketExtremitas']))
                                                                        :
                                                                        {{ getExtremitasLabel($data['ketExtremitas'], $extremitasOptions) }}
                                                                    @endif
                                                                </span>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td style="width: 50%">
                                                                <input type="checkbox"
                                                                    {{ isset($data['capillary']) ? 'checked' : '' }} />
                                                                <span>
                                                                    Capillary Refill Time
                                                                </span>
                                                            </td>
                                                            <td style="width: 50%;">
                                                                {{ isset($data['ketCapillary']) ? ': ' . $data['ketCapillary'] : '' }}
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                                <td style="width: 50%" colspan="2">
                                                    <table>
                                                        <tr>
                                                            <td style="width: 50%">
                                                                <input type="checkbox"
                                                                    {{ isset($data['odema']) ? 'checked' : '' }} />
                                                                <span>
                                                                    Odema
                                                                </span>
                                                            </td>
                                                            <td style="width: 50%">
                                                                {{ isset($data['ketOdema']) ? ': ' . $data['ketOdema'] : '' }}
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td style="width: 50%">
                                                                <input type="checkbox"
                                                                    {{ isset($data['lanlan']) ? 'checked' : '' }} />
                                                                <span>
                                                                    Lain-lain
                                                                </span>
                                                            </td>
                                                            <td style="width: 50%">
                                                                {{ isset($data['ketLanlan']) ? ': ' . $data['ketLanlan'] : '' }}
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="4" class="btl">
                                                    Lainnya
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="width: 100%" colspan="4">
                                                    <table style="width:100%">
                                                        <tr>
                                                            <td style="width: 33%">
                                                                <table>
                                                                    <tr>
                                                                        <td style="width: 65%">
                                                                            <input type="checkbox"
                                                                                {{ isset($data['Kulit']) ? 'checked' : '' }} />
                                                                            <span>
                                                                                Kulit
                                                                            </span>
                                                                        </td>
                                                                        <td style="width: 35%;">
                                                                            {{ isset($data['ketKulit']) ? ': ' . $data['ketKulit'] : '' }}
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td style="width: 65%">
                                                                            <input type="checkbox"
                                                                                {{ isset($data['genetalia']) ? 'checked' : '' }} />
                                                                            <span>
                                                                                Genetalia Eloxterna
                                                                            </span>
                                                                        </td>
                                                                        <td style="width: 35%;">
                                                                            {{ isset($data['ketGenetalia']) ? ': ' . $data['ketGenetalia'] : '' }}
                                                                        </td>
                                                                    </tr>
                                                                </table>
                                                            </td>
                                                            <td style="width: 33%">
                                                                <table>
                                                                    <tr>
                                                                        <td style="width: 65%">
                                                                            <input type="checkbox"
                                                                                {{ isset($data['pubertasp']) ? 'checked' : '' }} />
                                                                            <span>
                                                                                Pubertas Perempuan
                                                                            </span>
                                                                        </td>
                                                                        <td style="width: 35%;">
                                                                            {{ isset($data['ketPubertasp']) ? ': ' . $data['ketPubertasp'] : '' }}
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td style="width: 65%">
                                                                            <input type="checkbox"
                                                                                {{ isset($data['pubertasl']) ? 'checked' : '' }} />
                                                                            <span>
                                                                                Pubertas Laki-Laki
                                                                            </span>
                                                                        </td>
                                                                        <td style="width: 35%;">
                                                                            {{ isset($data['ketPubertasl']) ? ': ' . $data['ketPubertasl'] : '' }}
                                                                        </td>
                                                                    </tr>
                                                                </table>
                                                            </td>
                                                            <td style="width: 33%">
                                                                <table>
                                                                    <tr>
                                                                        <td style="width: 65%">
                                                                            <input type="checkbox"
                                                                                {{ isset($data['lala']) ? 'checked' : '' }} />
                                                                            <span>
                                                                                Lain-lain
                                                                            </span>
                                                                        </td>
                                                                        <td style="width: 35%;">
                                                                            {{ isset($data['ketLala']) ? ': ' . $data['ketLala'] : '' }}
                                                                        </td>
                                                                    </tr>
                                                                </table>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <!-- END:PEMERIKSAAN FISIK -->
                @endif

                @if (
                    !isset($ruangan) ||
                        stripos($ruangan, 'GIGI') != false ||
                        stripos($ruangan, 'MULUT') != false ||
                        stripos($ruangan, 'ENDODONSIA') != false)
                    <tr>
                        <td colspan="3">
                            <table class="border font">
                                <tr>
                                    <td colspan="2">
                                        Status Lokalis Gigi
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2">
                                        <div class="image-container">
                                            <img src="{{ $data['Gambar'] }}" width="690px" height="350px"
                                                alt="Gambar" class="image-top">
                                            <img src="{{ 'img/odon.png' }}" width="690px" height="370px"
                                                alt="Gambar Odon" class="image-bottom">
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                @endif

                @if (
                    !isset($ruangan) ||
                        stripos($ruangan, 'BEDAH MULUT') !== false ||
                        (isset($data['jenisTrauma']) && $data['jenisTrauma'] == 'Non Trauma'))
                    <tr>
                        <td colspan="3">
                            <table class="border font">
                                <tr>
                                    <td>
                                        <div class="image-container-4">
                                            <img src="{{ $data['Gambar'] }}" width="500px" height="130px"
                                                alt="Gambar" class="image-top">
                                            <img src="{{ 'img/outline-only-body.jpg' }}" width="500px"
                                                height="150px" alt="Gambar Tubuh" class="image-bottom">
                                        </div>
                                        <div style="text-align: center">
                                            Status Lokalis
                                        </div>
                                        <div style="text-align: center">
                                            {{ isset($data['lokalisNonTrauma']) ? ' ' . $data['lokalisNonTrauma'] : '' }}
                                        </div>
                                    </td>
                                    <td>
                                        <div style="text-align: center">
                                            Skema
                                        </div>
                                        <div style="text-align: center">
                                            {{ isset($data['skemaNonTrauma']) ? ' ' . $data['skemaNonTrauma'] : '' }}
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                @endif
                @if (
                    !isset($ruangan) ||
                        stripos($ruangan, 'GIGI') != false ||
                        stripos($ruangan, 'MULUT') != false ||
                        stripos($ruangan, 'ENDODONSIA') != false)
                    <tr>
                        <td colspan="2">
                            <table class="border font">
                                <tr>
                                    <td colspan="3">
                                        <table>
                                            <tr>
                                                <td colspan="3" class="bt">
                                                    <table>
                                                        <tr>
                                                            <td>
                                                                <div style="margin: 5px;">
                                                                    Elemen :
                                                                </div>
                                                                {{ isset($data['elemen']) ? '' . $data['elemen'] : '' }}
                                                            </td>
                                                            <td>
                                                                <div style="margin: 5px;">
                                                                    Elemen :
                                                                </div>
                                                                {{ isset($data['elemen2']) ? '' . $data['elemen2'] : '' }}
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                <div style="margin: 5px;">
                                                                    Karies :
                                                                </div>
                                                                {{ isset($data['karies']) ? '' . $data['karies'] : '' }}
                                                            </td>
                                                            <td>
                                                                <div style="margin: 5px;">
                                                                    Karies :
                                                                </div>
                                                                {{ isset($data['karies2']) ? '' . $data['karies2'] : '' }}
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                <div style="margin: 5px;">
                                                                    Sondasi :
                                                                </div>
                                                                {{ isset($data['sondasi']) ? '' . $data['sondasi'] : '' }}
                                                            </td>
                                                            <td>
                                                                <div style="margin: 5px;">
                                                                    Sondasi :
                                                                </div>
                                                                {{ isset($data['sondasi2']) ? '' . $data['sondasi2'] : '' }}
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                <div style="margin: 5px;">
                                                                    Perkusi :
                                                                </div>
                                                                {{ isset($data['perkusi']) ? '' . $data['perkusi'] : '' }}
                                                            </td>
                                                            <td>
                                                                <div style="margin: 5px;">
                                                                    Perkusi :
                                                                </div>
                                                                {{ isset($data['perkusi2']) ? '' . $data['perkusi2'] : '' }}
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                <div style="margin: 5px;">
                                                                    Druk :
                                                                </div>
                                                                {{ isset($data['druk']) ? '' . $data['druk'] : '' }}
                                                            </td>
                                                            <td>
                                                                <div style="margin: 5px;">
                                                                    Druk :
                                                                </div>
                                                                {{ isset($data['druk2']) ? '' . $data['druk2'] : '' }}
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                <div style="margin: 5px;">
                                                                    Ce :
                                                                </div>
                                                                {{ isset($data['ce']) ? '' . $data['ce'] : '' }}
                                                            </td>
                                                            <td>
                                                                <div style="margin: 5px;">
                                                                    Ce :
                                                                </div>
                                                                {{ isset($data['ce2']) ? '' . $data['ce2'] : '' }}
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                <div style="margin: 5px;">
                                                                    RO Foto :
                                                                </div>
                                                                {{ isset($data['rofoto']) ? '' . $data['rofoto'] : '' }}
                                                            </td>
                                                            <td>
                                                                <div style="margin: 5px;">
                                                                    RO Foto :
                                                                </div>
                                                                {{ isset($data['rofoto2']) ? '' . $data['rofoto2'] : '' }}
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                @endif

                <!-- START:THT -->
                @if (!isset($ruangan) || stripos($ruangan, 'THT') != false)
                    <tr>
                        <td colspan="3" class="bt">
                            <table class="border font">
                                <tr>
                                    <td>
                                        Status Lokalis
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="image-container">
                                            <img src="{{ $data['Gambar'] }}" width="500px" height="500px"
                                                alt="Gambar" class="image-top">
                                            <img src="{{ 'img/tht-full.png' }}" width="500px" height="500px"
                                                alt="Gambar THT" class="image-bottom">
                                        </div>
                                    </td>
                                </tr>
                                <tr class="bt">
                                    <td>
                                        <div style="margin: 5px;">
                                            <b>Tes Suara Bisik</b>
                                        </div>
                                        {{ isset($data['suarabisik']) ? '' . $data['suarabisik'] : '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <table>
                                            <tr>
                                                <td colspan="1" style="width: 20%">
                                                    <b>#</b>
                                                </td>
                                                <td colspan="1" style="width: 40%">
                                                    <b>Telinga Kiri</b>
                                                </td>
                                                <td colspan="1" style="width: 40%">
                                                    <b>Telinga Kanan</b>
                                                </td>
                                            </tr>
                                            <tr class="btg">
                                                <td colspan="1" style="width: 20%">
                                                    <span class="fontg">Rinne</span>
                                                </td>
                                                <td colspan="1" style="width: 40%">
                                                    <span>
                                                        {{ isset($data['rinnekiri']) ? '' . $data['rinnekiri'] : '' }}
                                                    </span>
                                                </td>
                                                <td colspan="1" style="width: 40%">
                                                    <span>
                                                        {{ isset($data['rinnekanan']) ? '' . $data['rinnekanan'] : '' }}
                                                    </span>
                                                </td>
                                            </tr>
                                            <tr class="btg">
                                                <td colspan="1" style="width: 20%">
                                                    <span class="fontg">Weber</span>
                                                </td>
                                                <td colspan="1" style="width: 40%">
                                                    <span>
                                                        {{ isset($data['weberkiri']) ? '' . $data['weberkiri'] : '' }}
                                                    </span>
                                                </td>
                                                <td colspan="1" style="width: 40%">
                                                    <span>
                                                        {{ isset($data['weberkanan']) ? '' . $data['weberkanan'] : '' }}
                                                    </span>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                @endif
                <!-- END:THT -->
                <!-- START:BEDAH UROLOGI -->
                @if (!isset($ruangan) || stripos($ruangan, 'BEDAH UROLOGI') != false)
                    <tr>
                        <td colspan="3" class="bt">
                            <table class="border font" style="margin-bottom: 5px;">
                                <tr>
                                    <td colspan="3">
                                        Status Lokalis
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div style="margin: 5px;">
                                            <b>Flank</b>
                                        </div>
                                        {{ isset($data['flank']) ? '' . $data['flank'] : '' }}
                                    </td>
                                    <td>
                                        <div style="margin: 5px;">
                                            <b>Suprapubik</b>
                                        </div>
                                        {{ isset($data['suprabolic']) ? '' . $data['suprabolic'] : '' }}
                                    </td>
                                    <td>
                                        <div style="margin: 5px;">
                                            <b>Genetalia Externa</b>
                                        </div>
                                        {{ isset($data['genetaliaexterna']) ? '' . $data['genetaliaexterna'] : '' }}
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                @endif
                <!-- END:BEDAH UROLOGI -->
                <!-- START:SARAF -->
                @if (!isset($ruangan) || (stripos($ruangan, 'SARAF') != false && stripos($ruangan, 'BEDAH SARAF') === false))
                    <tr>
                        <td colspan="3" class="bt">
                            <table class="border font" style="margin-bottom: 5px;">
                                <tr>
                                    <td>
                                        Status Lokalis
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div style="margin: 5px;">
                                            <b>C. Pemeriksaan Neurologik</b>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="border-top:1px ridge gray ">
                                        <div style="margin: 5px;">
                                            1. Kranium <small style="font-weight: bold">(Inspeksi, Palpasi, Perkusi,
                                                Arskultasi, Transluminasi, dll)
                                            </small>
                                        </div>
                                        <div style="margin-bottom: 5px;">
                                            {{ isset($data['kranium']) ? '' . $data['kranium'] : '' }}
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="border-top:1px ridge gray ">
                                        <div style="margin: 5px;">
                                            2. Korpus Vertebra <small style="font-weight: bold">(Inspeksi, Palpasi,
                                                Perkusi, Mobilitas, dll)
                                            </small>
                                        </div>
                                        <div style="margin-bottom: 5px;">
                                            {{ isset($data['vertabra']) ? '' . $data['vertabra'] : '' }}
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="border-top:1px ridge gray ">
                                        <div style="margin: 5px;">
                                            3. Tanda tanda perangsang selaput otak
                                        </div>
                                        <div style="margin-bottom: 5px;">
                                            {{ isset($data['selaputotak']) ? '' . $data['selaputotak'] : '' }}
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="border-top:1px ridge gray ">
                                        <div style="margin: 5px;">
                                            4. Saraf Otak (I-XII) (Kanan/Kiri)
                                        </div>
                                        <div style="margin-bottom: 5px;">
                                            {{ isset($data['sarafotak']) ? '' . $data['sarafotak'] : '' }}
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="border-top:1px ridge gray ">
                                        <div style="margin: 5px;">
                                            5. Motorik <small style="font-weight: bold">( Tenaga, Tonus, Koordinasi,
                                                Gerakan Involunter, Langkah dan
                                                Gaya Jalan ) (Kanan/Kiri) </small>
                                        </div>
                                        <div style="margin-bottom: 5px;">
                                            {{ isset($data['motorik']) ? '' . $data['motorik'] : '' }}
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="border-top:1px ridge gray ">
                                        <div style="margin: 5px;">
                                            6. Refleks <small style="font-weight: bold">( Fisiologik, Patologik )
                                                (Kanan/Kiri) </small>
                                        </div>
                                        <div style="margin-bottom: 5px;">
                                            {{ isset($data['refleks']) ? '' . $data['refleks'] : '' }}
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="border-top:1px ridge gray ">
                                        <div style="margin: 5px;">
                                            7. Sensorik <small style="font-weight: bold">( Permukaan, dalam )
                                                (Kanan/Kiri) </small>
                                        </div>
                                        <div style="margin-bottom: 5px;">
                                            {{ isset($data['sensorik']) ? '' . $data['sensorik'] : '' }}
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="border-top:1px ridge gray ">
                                        <div style="margin: 5px;">
                                            8. Vegetatif
                                        </div>
                                        <div style="margin-bottom: 5px;">
                                            {{ isset($data['vegetatif']) ? '' . $data['vegetatif'] : '' }}
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="border-top:1px ridge gray ">
                                        <div style="margin: 5px;">
                                            9. Fungsi Luhur <small style="font-weight: bold">( Kesadaran, reaksi emosi,
                                                fungsi intelek, proses
                                                berfikir, fungsi psikomotorik, fungsi psikosensorik, fungsi bicara dan
                                                bahasa ) </small>
                                        </div>
                                        <div style="margin-bottom: 5px;">
                                            {{ isset($data['luhur']) ? '' . $data['luhur'] : '' }}
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="border-top:1px ridge gray ">
                                        <div style="margin: 5px;">
                                            10. Tanda tanda kemunduran mental <small style="font-weight: bold">(Reflek
                                                Memegang, Refleks Menetek,
                                                Refleks Snout, Reflek Glabela, Refleks Palmomental, Refleks
                                                Korneomandibuler, dll) </small>
                                        </div>
                                        <div style="margin-bottom: 5px;">
                                            {{ isset($data['tandamental']) ? '' . $data['tandamental'] : '' }}
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="border-top:1px ridge gray ">
                                        <div style="margin: 5px;">
                                            11. Nyeri Tekan Saraf
                                        </div>
                                        <div style="margin-bottom: 5px;">
                                            {{ isset($data['nyeritekansaraf']) ? '' . $data['nyeritekansaraf'] : '' }}
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="border-top:1px ridge gray ">
                                        <div style="margin: 5px;">
                                            12. Tanda Lasegue
                                        </div>
                                        <div style="margin-bottom: 5px;">
                                            {{ isset($data['lasegue']) ? '' . $data['lasegue'] : '' }}
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="border-top:1px ridge gray ">
                                        <div style="margin: 5px;">
                                            13. Lain Lain
                                        </div>
                                        <div style="margin-bottom: 5px;">
                                            {{ isset($data['lainlain']) ? '' . $data['lainlain'] : '' }}
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                @endif
                <!-- END:SARAF -->
                <!-- START:JIWA -->
                @if (!isset($ruangan) || stripos($ruangan, 'JIWA') != false)
                    <tr>
                        <td colspan="3" class="bt">
                            <table class="border font" style="margin-bottom: 5px;">
                                <tr>
                                    <td colspan="2">
                                        Status Lokalis
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div style="margin: 5px;">
                                            <b>GENOGRAM</b>
                                        </div>
                                        {{ isset($data['genogram']) ? '' . $data['genogram'] : '' }}
                                    </td>
                                    <td>
                                        <div style="margin: 5px;">
                                            <b>Fungsi kerja / sosial</b>
                                        </div>
                                        <div>
                                            {{ isset($data['fungsikerja']) ? '' . $data['fungsikerja'] : '' }}
                                        </div>
                                        <div style="margin: 5px;">
                                            <b>Fungsi premorbid</b>
                                        </div>
                                        <div>
                                            {{ isset($data['fungsipremorbid']) ? '' . $data['fungsipremorbid'] : '' }}
                                        </div>
                                        <div style="margin: 5px;">
                                            <b>Faktor organik</b>
                                        </div>
                                        <div>
                                            {{ isset($data['faktororganik']) ? '' . $data['faktororganik'] : '' }}
                                        </div>
                                    </td>
                                </tr>
                                <tr class="btl">
                                    <td colspan="2">
                                        <div style="margin: 5px;">
                                            <b>Faktor pencetus / penyebab</b>
                                        </div>
                                        <div>
                                            {{ isset($data['faktorpenyebab']) ? '' . $data['faktorpenyebab'] : '' }}
                                        </div>
                                        <div style="margin: 5px;">
                                            <b>Faktor keluarga</b>
                                        </div>
                                        <div>
                                            {{ isset($data['faktorkeluarga']) ? '' . $data['faktorkeluarga'] : '' }}
                                        </div>
                                    </td>
                                </tr>
                                <tr class="btl">
                                    <td colspan="2">
                                        <div style="margin-bottom: 10px">
                                            <b>Riwayat NAPZA</b> :
                                            {{ isset($data['optionsnapza']) ? '' . $data['optionsnapza'] : '' }}
                                        </div>
                                        <div style="margin-left: 10px;">
                                            <div style="margin: 1px;">
                                                <b>Lama pemakaian</b>
                                                {{ isset($data['lamapemakaian']) ? ': ' . $data['lamapemakaian'] : '' }}
                                            </div>
                                            <div style="margin: 1px;">
                                                <b>Jenis zat</b>
                                                {{ isset($data['jeniszat']) ? ': ' . $data['jeniszat'] : '' }}
                                            </div>
                                            <div style="margin: 1px;">
                                                <b>Cara pemakaian</b>
                                                {{ isset($data['carapemakaian']) ? ': ' . $data['carapemakaian'] : '' }}
                                            </div>
                                            <div style="margin: 1px;">
                                                <b>Latar belakang pemakaian</b>
                                                {{ isset($data['latarpemakaian']) ? ': ' . $data['latarpemakaian'] : '' }}
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr class="btl">
                                    <td colspan="2">
                                        <div style="margin-bottom: 10px">
                                            <b>STATUS PSIKIATRI</b>
                                        </div>
                                        <div style="margin: 2px;">
                                            <b>Deskripsi umum</b>
                                        </div>
                                        {{ isset($data['deskripsiumum']) ? '' . $data['deskripsiumum'] : '' }}
                                        <div style="margin: 2px;">
                                            <b>Kesadaran</b>
                                        </div>
                                        {{ isset($data['kesadaran']) ? '' . $data['kesadaran'] : '' }}
                                        <div style="margin: 2px;">
                                            <b>Kontak</b>
                                        </div>
                                        {{ isset($data['kontak']) ? '' . $data['kontak'] : '' }}
                                        <div style="margin: 2px;">
                                            <b>Bicara</b>
                                        </div>
                                        {{ isset($data['bicara']) ? '' . $data['bicara'] : '' }}
                                        <div style="margin: 2px;">
                                            <b>Orientasi</b>
                                        </div>
                                        {{ isset($data['orientasi']) ? '' . $data['orientasi'] : '' }}

                                        <div style="margin: 2px;">
                                            <b>Mood/Afek</b>
                                        </div>
                                        {{ isset($data['mood']) ? '' . $data['mood'] : '' }}

                                        <div style="margin: 2px;">
                                            <b>Proses Berfikir</b>
                                        </div>
                                        {{ isset($data['berfikir']) ? '' . $data['berfikir'] : '' }}

                                        <div style="margin: 2px;">
                                            <b>Persepsi</b>
                                        </div>
                                        {{ isset($data['persepsi']) ? '' . $data['persepsi'] : '' }}

                                        <div style="margin: 2px;">
                                            <b>Kognisi dan Sensorium</b>
                                        </div>
                                        {{ isset($data['kognisi']) ? '' . $data['kognisi'] : '' }}

                                        <div style="margin: 2px;">
                                            <b>Daya Nilai dan Tilikan</b>
                                        </div>
                                        {{ isset($data['dayanilai']) ? '' . $data['dayanilai'] : '' }}

                                        <div style="margin: 2px;">
                                            <b>Psikomotor</b>
                                        </div>
                                        {{ isset($data['psikomotor']) ? '' . $data['psikomotor'] : '' }}

                                        <div style="margin: 2px;">
                                            <b>Pengendalian Impuls</b>
                                        </div>
                                        {{ isset($data['impuls']) ? '' . $data['impuls'] : '' }}
                                    </td>
                                </tr>
                                <tr class="btl">
                                    <td>
                                        <div style="margin: 5px;">
                                            <b>STATUS NEUROLOGIS</b>
                                        </div>
                                        <div>
                                            {{ isset($data['statusneurologis']) ? '' . $data['statusneurologis'] : '' }}
                                        </div>
                                    </td>
                                    <td>
                                        <div style="margin: 5px;">
                                            <b>STATUS INTERNA</b>
                                        </div>
                                        <div>
                                            {{ isset($data['statusinterna']) ? '' . $data['statusinterna'] : '' }}
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                @endif
                <!-- END:JIWA -->
                <!-- START:OBGYN -->
                @if (!isset($ruangan) || (stripos($ruangan, 'OBGYN') != false && $data['jenisObgyn'] == 'Obstetri'))
                    <tr>
                        <td colspan="3" class="bt">
                            <table class="border font" style="margin-bottom: 5px;">
                                <tr>
                                    <td colspan="3">
                                        Pemeriksaan Fisik
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="3">
                                        <b>PENAPISAN MASALAH GENETIK (Termasuk pasien, suami, dan anggota keluarga)</b>
                                    </td>
                                </tr>
                                <tr class="btl">
                                    <td colspan="3">
                                        <b>Masalah Genetik</b>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div style="margin-bottom: 10px">
                                            <b>Usia ibu di atas 35 tahun :</b>
                                        </div>
                                        {{ isset($data['usiaibu']) ? getYesNoLabel($data['usiaibu'], $yesNoOptions) : '' }}
                                    </td>
                                    <td>
                                        <div style="margin-bottom: 10px">
                                            <b>Penyakit Haematologi :</b>
                                        </div>
                                        {{ isset($data['haematologi']) ? getYesNoLabel($data['haematologi'], $yesNoOptions) : '' }}
                                    </td>
                                    <td>
                                        <div style="margin-bottom: 10px">
                                            <b>Neural tube defect (Meningomylecel, Spina Bifida, Anencephali) :</b>
                                        </div>
                                        {{ isset($data['neural']) ? getYesNoLabel($data['neural'], $yesNoOptions) : '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div style="margin-bottom: 10px">
                                            <b>Down Syndrome :</b>
                                        </div>
                                        {{ isset($data['ds']) ? getYesNoLabel($data['ds'], $yesNoOptions) : '' }}
                                    </td>
                                    <td>
                                        <div style="margin-bottom: 10px">
                                            <b>Huntington Chorea :</b>
                                        </div>
                                        {{ isset($data['huntington']) ? getYesNoLabel($data['huntington'], $yesNoOptions) : '' }}
                                    </td>
                                    <td>
                                        <div style="margin-bottom: 10px">
                                            <b>Retardasi Mental :</b>
                                        </div>
                                        {{ isset($data['reterdasi']) ? getYesNoLabel($data['reterdasi'], $yesNoOptions) : '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div style="margin-bottom: 10px">
                                            <b>Kelaianan Kromosom :</b>
                                        </div>
                                        {{ isset($data['kromosom']) ? getYesNoLabel($data['kromosom'], $yesNoOptions) : '' }}
                                    </td>
                                    <td>
                                        <div style="margin-bottom: 10px">
                                            <b>Riwayat Lahir Cacat :</b>
                                        </div>
                                        {{ isset($data['lahircacat']) ? getYesNoLabel($data['lahircacat'], $yesNoOptions) : '' }}
                                    </td>
                                    <td>
                                        <div style="margin-bottom: 10px">
                                            <b>Riwayat Abortus pada trimester I dan KJDR :</b>
                                        </div>
                                        {{ isset($data['abortus']) ? getYesNoLabel($data['abortus'], $yesNoOptions) : '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div style="margin-bottom: 10px">
                                            <b>Riwayat Narkoba :</b>
                                        </div>
                                        {{ isset($data['narkoba']) ? getYesNoLabel($data['narkoba'], $yesNoOptions) : '' }}
                                    </td>
                                    <td>
                                        <div style="margin-bottom: 10px">
                                            <b>Lainnya :</b>
                                        </div>
                                        {{ isset($data['genetiklainnya']) ? '' . $data['genetiklainnya'] : '' }}
                                    </td>
                                </tr>
                                <tr class="btl">
                                    <td colspan="3">
                                        <b>Riwayat Infeksi</b>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div style="margin-bottom: 10px">
                                            <b>Resiko Tinggi HIV :</b>
                                        </div>
                                        {{ isset($data['hiv']) ? getYesNoLabel($data['hiv'], $yesNoOptions) : '' }}
                                    </td>
                                    <td>
                                        <div style="margin-bottom: 10px">
                                            <b>Resiko Tinggi Hepatitis B :</b>
                                        </div>
                                        {{ isset($data['hepatitisb']) ? getYesNoLabel($data['hepatitisb'], $yesNoOptions) : '' }}
                                    </td>
                                    <td>
                                        <div style="margin-bottom: 10px">
                                            <b>Tinggal bersama penderita / infeksi kronis :</b>
                                        </div>
                                        {{ isset($data['penderita']) ? getYesNoLabel($data['penderita'], $yesNoOptions) : '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div style="margin-bottom: 10px">
                                            <b>Riwayat Penyakit Menular Seksual :</b>
                                        </div>
                                        {{ isset($data['pms']) ? getYesNoLabel($data['pms'], $yesNoOptions) : '' }}
                                    </td>
                                    <td>
                                        <div style="margin-bottom: 10px">
                                            <b>Riwayat Penyakit Menular Seksual Pasangan :</b>
                                        </div>
                                        {{ isset($data['pmspasangan']) ? getYesNoLabel($data['pmspasangan'], $yesNoOptions) : '' }}
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3">
                            <table class="border font" style="margin-bottom: 5px;">
                                <tr class="btl">
                                    <td colspan="4">
                                        <b>Kehamilan Tidak Diinginkan</b>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div style="margin-bottom: 10px">
                                            <b>Gagal KB :</b>
                                        </div>
                                        {{ isset($data['gagalkb']) ? getYesNoLabel($data['gagalkb'], $yesNoOptions) : '' }}
                                    </td>
                                    <td>
                                        <div style="margin-bottom: 10px">
                                            <b>Korban Pemerkosaan :</b>
                                        </div>
                                        {{ isset($data['korban']) ? getYesNoLabel($data['korban'], $yesNoOptions) : '' }}
                                    </td>
                                    <td>
                                        <div style="margin-bottom: 10px">
                                            <b>Usia Menggugurkan :</b>
                                        </div>
                                        {{ isset($data['menggugurkan']) ? '' . $data['menggugurkan'] : '' }}
                                    </td>
                                    <td>
                                        <div style="margin-bottom: 10px">
                                            <b>Lainnya :</b>
                                        </div>
                                        {{ isset($data['kehamilanlainnya']) ? '' . $data['kehamilanlainnya'] : '' }}
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3">
                            <table class="border font" style="margin-bottom: 5px;">
                                <tr class="btl">
                                    <td>
                                        <b>Status General</b>
                                    </td>
                                    <td>
                                        <b>Keterangan</b>
                                    </td>
                                    <td>
                                        <b>Pemeriksaan Luar</b>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <table>
                                            <tr>
                                                <td>
                                                    <div style="margin-bottom: 5px">
                                                        <b>Kepala :</b>
                                                    </div>
                                                    {{ isset($data['kepalageneral']) ? getYesNoLabel($data['kepalageneral'], $normalOptions) : '' }}
                                                </td>
                                                <td>
                                                    <div style="margin-bottom: 5px">
                                                        <b>Mata :</b>
                                                    </div>
                                                    {{ isset($data['matageneral']) ? getYesNoLabel($data['matageneral'], $normalOptions) : '' }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <div style="margin-bottom: 5px">
                                                        <b>Gigi :</b>
                                                    </div>
                                                    {{ isset($data['gigigeneral']) ? getYesNoLabel($data['gigigeneral'], $normalOptions) : '' }}
                                                </td>
                                                <td>
                                                    <div style="margin-bottom: 5px">
                                                        <b>Tiroid :</b>
                                                    </div>
                                                    {{ isset($data['tiroidgeneral']) ? getYesNoLabel($data['tiroidgeneral'], $normalOptions) : '' }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <div style="margin-bottom: 5px">
                                                        <b>Payudara :</b>
                                                    </div>
                                                    {{ isset($data['payudarageneral']) ? getYesNoLabel($data['payudarageneral'], $normalOptions) : '' }}
                                                </td>
                                                <td>
                                                    <div style="margin-bottom: 5px">
                                                        <b>Jantung :</b>
                                                    </div>
                                                    {{ isset($data['jantunggeneral']) ? getYesNoLabel($data['jantunggeneral'], $normalOptions) : '' }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <div style="margin-bottom: 5px">
                                                        <b>Paru :</b>
                                                    </div>
                                                    {{ isset($data['parugeneral']) ? getYesNoLabel($data['parugeneral'], $normalOptions) : '' }}
                                                </td>
                                                <td>
                                                    <div style="margin-bottom: 5px">
                                                        <b>Perut :</b>
                                                    </div>
                                                    {{ isset($data['perutgeneral']) ? getYesNoLabel($data['perutgeneral'], $normalOptions) : '' }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <div style="margin-bottom: 5px">
                                                        <b>Pelvic :</b>
                                                    </div>
                                                    {{ isset($data['pelvicgeneral']) ? getYesNoLabel($data['pelvicgeneral'], $normalOptions) : '' }}
                                                </td>
                                                <td>
                                                    <div style="margin-bottom: 5px">
                                                        <b>Tungkai Atas :</b>
                                                    </div>
                                                    {{ isset($data['tungkaigeneral']) ? getYesNoLabel($data['tungkaigeneral'], $normalOptions) : '' }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <div style="margin-bottom: 5px">
                                                        <b>Tungkai Bawah :</b>
                                                    </div>
                                                    {{ isset($data['tungkaibawahgeneral']) ? getYesNoLabel($data['tungkaibawahgeneral'], $normalOptions) : '' }}
                                                </td>
                                                <td>
                                                    <div style="margin-bottom: 5px">
                                                        <b>Kelenjar Limfe :</b>
                                                    </div>
                                                    {{ isset($data['limfegeneral']) ? getYesNoLabel($data['limfegeneral'], $normalOptions) : '' }}
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                    <td>
                                        {{ isset($data['keterangangeneral']) ? '' . $data['keterangangeneral'] : '' }}
                                    </td>
                                    <td>
                                        <table>
                                            <tr>
                                                <td>
                                                    <div style="margin-bottom: 5px">
                                                        <b>Tinggi fundus uteri :</b>
                                                    </div>
                                                    {{ isset($data['tinggifundus']) ? '' . $data['tinggifundus'] : '' }}
                                                </td>
                                                <td>
                                                    <div style="margin-bottom: 5px">
                                                        <b>Letak anak :</b>
                                                    </div>
                                                    {{ isset($data['letakanak']) ? '' . $data['letakanak'] : '' }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <div style="margin-bottom: 5px">
                                                        <b>Denyut jantung janin :</b>
                                                    </div>
                                                    {{ isset($data['denyutjantung']) ? '' . $data['denyutjantung'] : '' }}
                                                </td>
                                                <td>
                                                    <div style="margin-bottom: 5px">
                                                        <b>His :</b>
                                                    </div>
                                                    {{ isset($data['his']) ? '' . $data['his'] : '' }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="2">
                                                    <div style="margin-bottom: 5px">
                                                        <b>Lainnya :</b>
                                                    </div>
                                                    {{ isset($data['luarlainnya']) ? '' . $data['luarlainnya'] : '' }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="2">
                                                    <div style="margin-bottom: 5px">
                                                        <b>Pemeriksaan Dalam :</b>
                                                    </div>
                                                    {{ isset($data['dalamlainnya']) ? '' . $data['dalamlainnya'] : '' }}
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="3">
                                        <table>
                                            <tr class="btl">
                                                <td colspan="4">
                                                    <b>Pemeriksaan Panggul</b>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <div style="margin-bottom: 5px">
                                                        <b>Promontorium</b>
                                                    </div>
                                                    {{ isset($data['Promontorium']) ? '' . $data['Promontorium'] : '' }}
                                                </td>
                                                <td>
                                                    <div style="margin-bottom: 5px">
                                                        <b>Linea Innominata</b>
                                                    </div>
                                                    {{ isset($data['Innominata']) ? '' . $data['Innominata'] : '' }}
                                                </td>
                                                <td>
                                                    <div style="margin-bottom: 5px">
                                                        <b>Conjungata Diagonalis</b>
                                                    </div>
                                                    {{ isset($data['Conjungata']) ? '' . $data['Conjungata'] : '' }}
                                                </td>
                                                <td>
                                                    <div style="margin-bottom: 5px">
                                                        <b>Spina Ischiadica</b>
                                                    </div>
                                                    {{ isset($data['Spina']) ? '' . $data['Spina'] : '' }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <div style="margin-bottom: 5px">
                                                        <b>Distansia Interspinosus</b>
                                                    </div>
                                                    {{ isset($data['Distansia']) ? '' . $data['Distansia'] : '' }}
                                                </td>
                                                <td>
                                                    <div style="margin-bottom: 5px">
                                                        <b>Side Walls</b>
                                                    </div>
                                                    {{ isset($data['Walls']) ? '' . $data['Walls'] : '' }}
                                                </td>
                                                <td>
                                                    <div style="margin-bottom: 5px">
                                                        <b>Acrus Pubis</b>
                                                    </div>
                                                    {{ isset($data['Pubis']) ? '' . $data['Pubis'] : '' }}
                                                </td>
                                                <td>
                                                    <div style="margin-bottom: 5px">
                                                        <b>Sacrum</b>
                                                    </div>
                                                    {{ isset($data['Sacrum']) ? '' . $data['Sacrum'] : '' }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <div style="margin-bottom: 5px">
                                                        <b>Distansia Intertuberosum</b>
                                                    </div>
                                                    {{ isset($data['Intertuberosum']) ? '' . $data['Intertuberosum'] : '' }}
                                                </td>
                                                <td>
                                                    <div style="margin-bottom: 5px">
                                                        <b>Kesan Panggul</b>
                                                    </div>
                                                    {{ isset($data['Kesan']) ? '' . $data['Kesan'] : '' }}
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                @endif
                @if (!isset($ruangan) || (stripos($ruangan, 'OBGYN') != false && $data['jenisObgyn'] == 'Ginekologi'))
                    <tr>
                        <td colspan="3" class="bt">
                            <table class="border font" style="margin-bottom: 5px;">
                                <tr>
                                    <td colspan="3">
                                        Pemeriksaan Fisik
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3">
                            <table class="border font" style="margin-bottom: 5px;">
                                <tr class="btl">
                                    <td>
                                        <b>Status General</b>
                                    </td>
                                    <td>
                                        <b>Keterangan</b>
                                    </td>
                                    <td>
                                        <b>Pemeriksaan Ginekologi</b>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <table>
                                            <tr>
                                                <td>
                                                    <div style="margin-bottom: 5px">
                                                        <b>Kepala :</b>
                                                    </div>
                                                    {{ isset($data['kepalageneralgin']) ? getYesNoLabel($data['kepalageneralgin'], $normalOptions) : '' }}
                                                </td>
                                                <td>
                                                    <div style="margin-bottom: 5px">
                                                        <b>Mata :</b>
                                                    </div>
                                                    {{ isset($data['matageneralgin']) ? getYesNoLabel($data['matageneralgin'], $normalOptions) : '' }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <div style="margin-bottom: 5px">
                                                        <b>Gigi :</b>
                                                    </div>
                                                    {{ isset($data['gigigeneralgin']) ? getYesNoLabel($data['gigigeneralgin'], $normalOptions) : '' }}
                                                </td>
                                                <td>
                                                    <div style="margin-bottom: 5px">
                                                        <b>Tiroid :</b>
                                                    </div>
                                                    {{ isset($data['tiroidgeneralgin']) ? getYesNoLabel($data['tiroidgeneralgin'], $normalOptions) : '' }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <div style="margin-bottom: 5px">
                                                        <b>Payudara :</b>
                                                    </div>
                                                    {{ isset($data['payudarageneralgin']) ? getYesNoLabel($data['payudarageneralgin'], $normalOptions) : '' }}
                                                </td>
                                                <td>
                                                    <div style="margin-bottom: 5px">
                                                        <b>Jantung :</b>
                                                    </div>
                                                    {{ isset($data['jantunggeneralgin']) ? getYesNoLabel($data['jantunggeneralgin'], $normalOptions) : '' }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <div style="margin-bottom: 5px">
                                                        <b>Paru :</b>
                                                    </div>
                                                    {{ isset($data['parugeneralgin']) ? getYesNoLabel($data['parugeneralgin'], $normalOptions) : '' }}
                                                </td>
                                                <td>
                                                    <div style="margin-bottom: 5px">
                                                        <b>Perut :</b>
                                                    </div>
                                                    {{ isset($data['perutgeneralgin']) ? getYesNoLabel($data['perutgeneralgin'], $normalOptions) : '' }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <div style="margin-bottom: 5px">
                                                        <b>Pelvic :</b>
                                                    </div>
                                                    {{ isset($data['pelvicgeneralgin']) ? getYesNoLabel($data['pelvicgeneralgin'], $normalOptions) : '' }}
                                                </td>
                                                <td>
                                                    <div style="margin-bottom: 5px">
                                                        <b>Tungkai Atas :</b>
                                                    </div>
                                                    {{ isset($data['tungkaigeneralgin']) ? getYesNoLabel($data['tungkaigeneralgin'], $normalOptions) : '' }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <div style="margin-bottom: 5px">
                                                        <b>Tungkai Bawah :</b>
                                                    </div>
                                                    {{ isset($data['tungkaibawahgeneralgin']) ? getYesNoLabel($data['tungkaibawahgeneralgin'], $normalOptions) : '' }}
                                                </td>
                                                <td>
                                                    <div style="margin-bottom: 5px">
                                                        <b>Kelenjar Limfe :</b>
                                                    </div>
                                                    {{ isset($data['limfegeneralgin']) ? getYesNoLabel($data['limfegeneralgin'], $normalOptions) : '' }}
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                    <td>
                                        {{ isset($data['keterangangeneral']) ? '' . $data['keterangangeneral'] : '' }}
                                    </td>
                                    <td>
                                        <table>
                                            <tr>
                                                <td>
                                                    <div style="margin-bottom: 5px">
                                                        <b>Vulva :</b>
                                                    </div>
                                                    {{ isset($data['vulva']) ? getYesNoLabel($data['vulva'], $vulvaOptions) : '' }}
                                                </td>
                                                <td>
                                                    <div style="margin-bottom: 5px">
                                                        <b>Vagina :</b>
                                                    </div>
                                                    {{ isset($data['vagina']) ? getYesNoLabel($data['vagina'], $vaginaOptions) : '' }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <div style="margin-bottom: 5px">
                                                        <b>Cervix :</b>
                                                    </div>
                                                    {{ isset($data['cervix']) ? getYesNoLabel($data['cervix'], $cervixOptions) : '' }}
                                                </td>
                                                <td>
                                                    <div style="margin-bottom: 5px">
                                                        <b>Uterus :</b>
                                                    </div>
                                                    {{ isset($data['uterus']) ? getYesNoLabel($data['uterus'], $uterusOptions) : '' }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <div style="margin-bottom: 5px">
                                                        <b>Adnexa :</b>
                                                    </div>
                                                    {{ isset($data['adnexa']) ? getYesNoLabel($data['adnexa'], $adnexaOptions) : '' }}
                                                </td>
                                                <td>
                                                    <div style="margin-bottom: 5px">
                                                        <b>Rectum :</b>
                                                    </div>
                                                    {{ isset($data['rectum']) ? getYesNoLabel($data['rectum'], $normalOptions) : '' }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="2">
                                                    <div style="margin-bottom: 5px;">
                                                        <b>Lainnya :</b>
                                                    </div>
                                                    {{ isset($data['ginekologilainnya']) ? '' . $data['ginekologilainnya'] : '' }}
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="3">
                                        <table>
                                            <tr class="btl">
                                                <td>
                                                    <div style="margin-bottom: 5px;">
                                                        <b>Extremitas</b>
                                                    </div>
                                                    {{ isset($data['extremitas']) ? '' . $data['extremitas'] : '' }}
                                                </td>
                                                <td>
                                                    <div style="margin-bottom: 5px;">
                                                        <b>Rectal Toucher</b>
                                                    </div>
                                                    {{ isset($data['rectal']) ? '' . $data['rectal'] : '' }}
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="3">
                                        <table>
                                            <tr class="bt">
                                                <td>
                                                    <b>Status Lokalis</b>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <div class="image-container">
                                                        <img src="{{ $data['Gambar'] }}" width="500px"
                                                            height="130px" alt="Gambar" class="image-top">
                                                        <img src="{{ 'img/ginekologifix.png' }}" width="500px"
                                                            height="150px" alt="Gambar Ginekologi"
                                                            class="image-bottom">
                                                    </div>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                @endif
                <!-- END:OBGYN -->
                <!-- START:KULIT -->
                @if (!isset($ruangan) || stripos($ruangan, 'KULIT') != false)
                    <tr>
                        <td colspan="3" class="bt">
                            <table class="border font">
                                <tr>
                                    <td colspan="3">
                                        <b>Status Lokalis</b>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="3">
                                        <div class="image-container-4">
                                            <img src="{{ $data['Gambar'] }}" width="500px" height="130px"
                                                alt="Gambar" class="image-top">
                                            <img src="{{ 'img/outline-only-body.jpg' }}" width="500px"
                                                height="150px" alt="Gambar Tubuh" class="image-bottom">
                                        </div>
                                    </td>
                                </tr>
                                <tr class="btl">
                                    <td colspan="3">
                                        <b>STATUS DERMATOLOGI / VENEREOLOGI</b>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div style="margin-left: 5px;">
                                            <b>Lokasi</b>
                                        </div>
                                        <div class="font-2">
                                            <small>
                                                <b>(Jabarkan lokasi sesuai dengan regio anatomis)</b>
                                            </small>
                                        </div>
                                        <div style="margin-bottom: 5px;">
                                            {{ isset($data['lokasiKulit']) ? '' . $data['lokasiKulit'] : '' }}
                                        </div>
                                    </td>
                                    <td>
                                        <div style="margin-left: 5px;">
                                            <b>Bentuk Kelainan Kulit</b>
                                        </div>
                                        <div class="font-2">
                                            <small>
                                                <b>(Eflorisensi)</b>
                                            </small>
                                        </div>
                                        <div style="margin-bottom: 5px;">
                                            {{ isset($data['kelainanKulit']) ? '' . $data['kelainanKulit'] : '' }}
                                        </div>
                                    </td>
                                    <td>
                                        <div style="margin-left: 5px;">
                                            <b>Stigmata Atopi</b>
                                        </div>
                                        <div class="font-2">
                                            <small>
                                                <b>(Pit. Alba, Ikhtiosis, Keratosis, Denie-Morgagni, dll)</b>
                                            </small>
                                        </div>
                                        <div style="margin-bottom: 5px;">
                                            {{ isset($data['stigmata']) ? '' . $data['stigmata'] : '' }}
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div style="margin-left: 5px;">
                                            <b>Mukosa</b>
                                        </div>
                                        <div style="margin-bottom: 5px;">
                                            {{ isset($data['mukosa']) ? '' . $data['mukosa'] : '' }}
                                        </div>
                                    </td>
                                    <td>
                                        <div style="margin-left: 5px;">
                                            <b>Rambut</b>
                                        </div>
                                        <div style="margin-bottom: 5px;">
                                            {{ isset($data['rambut']) ? '' . $data['rambut'] : '' }}
                                        </div>
                                    </td>
                                    <td>
                                        <div style="margin-left: 5px;">
                                            <b>Kuku</b>
                                        </div>
                                        <div style="margin-bottom: 5px;">
                                            {{ isset($data['kuku']) ? '' . $data['kuku'] : '' }}
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div style="margin-left: 5px;">
                                            <b>Fungsi Kelenjar Keringat</b>
                                        </div>
                                        <div class="font-2">
                                            <small>
                                                <b>(Hiperhidrosis, Anhidrosis)</b>
                                            </small>
                                        </div>
                                        <div style="margin-bottom: 5px;">
                                            {{ isset($data['kelenjarKeringat']) ? '' . $data['kelenjarKeringat'] : '' }}
                                        </div>
                                    </td>
                                    <td>
                                        <div style="margin-left: 5px;">
                                            <b>Kelenjar Limfe</b>
                                        </div>
                                        <div style="margin-bottom: 5px;">
                                            {{ isset($data['kelenjarLimfe']) ? '' . $data['kelenjarLimfe'] : '' }}
                                        </div>
                                    </td>
                                    <td>
                                        <div style="margin-left: 5px;">
                                            <b>Saraf</b>
                                        </div>
                                        <div class="font-2">
                                            <small>
                                                <b>(Penebalan saraf perfer, parestesi, makula-anestesi)</b>
                                            </small>
                                        </div>
                                        <div style="margin-bottom: 5px;">
                                            {{ isset($data['saraf']) ? '' . $data['saraf'] : '' }}
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                @endif
                <!-- END:KULIT -->
                <!-- START:ANAK -->
                @if (!isset($ruangan) || stripos($ruangan, 'ANAK') != false || stripos($ruangan, 'KEMBANG') != false)
                    <tr>
                        <td colspan="3" class="bt">
                            <table class="border font">
                                <tr>
                                    <td colspan="3">
                                        <b>Status Lokalis</b>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="3">
                                        <div style="margin-left: 5px;">
                                            <b>Pemeriksaan Khusus</b>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="3">
                                        <p>{{ isset($data['pemeriksaankhususanak']) ? '' . $data['pemeriksaankhususanak'] : '' }}
                                        </p>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                @endif
                <!-- END:ANAK -->
                <!-- START:REHAB MEDIK -->
                @if (!isset($ruangan) || stripos($ruangan, 'REHAB') != false)
                    <tr>
                        <td colspan="3" class="bt">
                            <table class="border font">
                                <tr>
                                    <td colspan="2">
                                        <b>Status Lokalis</b>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="ml-5">
                                            <b>Postur :</b>
                                        </div>
                                        <div class="mb-5">
                                            <p>{{ isset($data['posturrehab']) ? '' . $data['posturrehab'] : '' }}</p>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="ml-5">
                                            <b>Ambulansi :</b>
                                        </div>
                                        <div class="mb-5">
                                            <p>{{ isset($data['ambulansirehab']) ? '' . $data['ambulansirehab'] : '' }}
                                            </p>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="ml-5">
                                            <b>Penilaian Nyeri :</b>
                                        </div>
                                        <div class="mb-5">
                                            <p>{{ isset($data['penilaiannyerirehab']) ? '' . $data['penilaiannyerirehab'] : '' }}
                                            </p>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="ml-5">
                                            <b>Resiko Jatuh :</b>
                                        </div>
                                        <div class="mb-5">
                                            <p>{{ isset($data['resikojatuhrehab']) ? '' . $data['resikojatuhrehab'] : '' }}
                                            </p>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="ml-5">
                                            <b>Status Musculoskeletal dan Neuromuscular :</b>
                                        </div>
                                        <div class="mb-5">
                                            <p>{{ isset($data['statusmn']) ? '' . $data['statusmn'] : '' }}</p>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="ml-5">
                                            <b>Pemeriksaan Khusus Fisik Lain :</b>
                                        </div>
                                        <div class="mb-5">
                                            <p>{{ isset($data['pfrehab']) ? '' . $data['pfrehab'] : '' }}</p>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="ml-5">
                                            <b>Balance :</b>
                                        </div>
                                        <div class="mb-5">
                                            <p>{{ isset($data['balancerehab']) ? '' . $data['balancerehab'] : '' }}
                                            </p>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="ml-5">
                                            <b>Coordlation :</b>
                                        </div>
                                        <div class="mb-5">
                                            <p>{{ isset($data['coordlationrehab']) ? '' . $data['coordlationrehab'] : '' }}
                                            </p>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="ml-5">
                                            <b>Transfer :</b>
                                        </div>
                                        <div class="mb-5">
                                            <p>{{ isset($data['transferrehab']) ? '' . $data['transferrehab'] : '' }}
                                            </p>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="ml-5">
                                            <b>Pemeriksaan Fungsional :</b>
                                        </div>
                                        <div class="mb-5">
                                            <p>{{ isset($data['pfungrehab']) ? '' . $data['pfungrehab'] : '' }}</p>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                @endif
                <!-- END:REHAB MEDIK -->
                <!-- START:INTERNA -->
                @if (!isset($ruangan) || stripos($ruangan, 'INTERNA') != false || stripos($ruangan, 'GERIATRI') != false)
                    <tr>
                        <td colspan="3" class="bt">
                            <table class="border font">
                                <tr>
                                    <td>
                                        <b>Status Lokalis</b>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="image-container">
                                            <img src="{{ $data['Gambar'] }}" width="500px" height="130px"
                                                alt="Gambar" class="image-top">
                                            <img src="{{ 'img/internafix.png' }}" width="500px" height="150px"
                                                alt="Gambar Interna" class="image-bottom">
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                @endif
                @if (
                    !isset($ruangan) ||
                        (isset($data['jenisInterna']) &&
                            (stripos($ruangan, 'INTERNA') !== false || stripos($ruangan, 'MEDICAL') !== false) &&
                            $data['jenisInterna'] == 'Obstetri') ||
                        stripos($ruangan, 'GERIATRI') !== false)
                    <tr>
                        <td colspan="3" class="btl">
                            <table class="border font">
                                <tr>
                                    <td colspan="4">
                                        <b>1. PENAPISAN STATUS FUNGSIONAL (ACTIVITY DAILY LIVING BARTHEL INDEX)</b>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="4">
                                        <table border="1">
                                            <tr>
                                                <td class="fc vac bg-ijo">Aspek</td>
                                                <td class="fc vac bg-ijo">Sebelum MRS</td>
                                                <td class="fc vac bg-ijo">Saat MRS</td>
                                                <td class="fc vac bg-ijo">Keterangan</td>
                                            </tr>
                                            @foreach ($ListPSF as $index => $item)
                                                <tr>
                                                    <td
                                                        style="text-align:start !important; vertical-align:middle !important;">
                                                        {{ $item['caption'] }}
                                                    </td>
                                                    <td class="fc vac">
                                                        {{ isset($data['TBsebelumMRS_' . $index]) ? $data['TBsebelumMRS_' . $index] : '' }}
                                                    </td>
                                                    <td class="fc vac">
                                                        {{ isset($data['TBsaatMRS_' . $index]) ? $data['TBsaatMRS_' . $index] : '' }}
                                                    </td>
                                                    @if ($index === 0)
                                                        <td rowspan="{{ count($ListPSF) }}"
                                                            style="font-weight:bold; text-align:left; vertical-align:middle;">
                                                            Mandiri (20)<br>
                                                            Ketergantungan ringan (12-19)<br>
                                                            Ketergantungan sedang (9-11)<br>
                                                            Ketergantungan berat (5-8)<br>
                                                            Ketergantungan total (0-4)
                                                        </td>
                                                    @endif
                                                </tr>
                                            @endforeach
                                        </table>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="4">
                                        <b>2. PENAPISAN SINDROM DELIRIUM (CONFUSION ASSESSMENT METHOD)</b>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div>
                                            <p>1. Onset akut dan fluktuatif</p>
                                        </div>
                                    </td>
                                    <td>
                                        <table class="mt-7">
                                            <tr>
                                                <td>
                                                    <div>
                                                        <input type="checkbox"
                                                            {{ isset($data['CBOnsetAkutdanFluktuatif']) && $data['CBOnsetAkutdanFluktuatif'] == 'Ya' ? 'checked' : '' }} />
                                                        <span>Ya</span>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div>
                                                        <input type="checkbox"
                                                            {{ isset($data['CBOnsetAkutdanFluktuatif']) && $data['CBOnsetAkutdanFluktuatif'] == 'Tidak' ? 'checked' : '' }} />
                                                        <span>Tidak</span>
                                                    </div>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                    <td>
                                        <div>
                                            <p>3. Pikiran tidak terorganisir &ge;</p>
                                        </div>
                                    </td>
                                    <td>
                                        <table class="mt-7">
                                            <tr>
                                                <td>
                                                    <div>
                                                        <input type="checkbox"
                                                            {{ isset($data['CBPikiranTidakTerorganisir']) && $data['CBPikiranTidakTerorganisir'] == 'Ya' ? 'checked' : '' }} />
                                                        <span>Ya</span>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div>
                                                        <input type="checkbox"
                                                            {{ isset($data['CBPikiranTidakTerorganisir']) && $data['CBPikiranTidakTerorganisir'] == 'Tidak' ? 'checked' : '' }} />
                                                        <span>Tidak</span>
                                                    </div>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div>
                                            <p>2. Inatensi</p>
                                        </div>
                                    </td>
                                    <td>
                                        <table class="mt-7">
                                            <tr>
                                                <td>
                                                    <div>
                                                        <input type="checkbox"
                                                            {{ isset($data['CBInatensi']) && $data['CBInatensi'] == 'Ya' ? 'checked' : '' }} />
                                                        <span>Ya</span>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div>
                                                        <input type="checkbox"
                                                            {{ isset($data['CBInatensi']) && $data['CBInatensi'] == 'Tidak' ? 'checked' : '' }} />
                                                        <span>Tidak</span>
                                                    </div>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                    <td>
                                        <div>
                                            <p>4. Pikiran tidak terorganisir</p>
                                        </div>
                                    </td>
                                    <td>
                                        <table class="mt-7">
                                            <tr>
                                                <td>
                                                    <div>
                                                        <input type="checkbox"
                                                            {{ isset($data['CBPikiranTidakTerorganisir2']) && $data['CBPikiranTidakTerorganisir2'] == 'Ya' ? 'checked' : '' }} />
                                                        <span>Ya</span>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div>
                                                        <input type="checkbox"
                                                            {{ isset($data['CBPikiranTidakTerorganisir2']) && $data['CBPikiranTidakTerorganisir2'] == 'Tidak' ? 'checked' : '' }} />
                                                        <span>Tidak</span>
                                                    </div>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div>
                                            <p>Delirium</p>
                                        </div>
                                    </td>
                                    <td colspan="3">
                                        <table class="mt-7">
                                            <tr>
                                                <td>
                                                    <div>
                                                        <input type="checkbox"
                                                            {{ isset($data['CBDelirium']) ? 'checked' : '' }} />
                                                        <span>Ya (poin 1 dan 2 plus salah satu dari 3 atau 4)</span>
                                                    </div>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                @endif

                <!-- END:INTERNA -->
                <!-- START:ANESTESI -->
                @if (!isset($ruangan) || stripos($ruangan, 'ANESTESI') != false)
                    <tr>
                        <td colspan="3" class="bt">
                            <table class="border font">
                                <tr>
                                    <td colspan="4">
                                        <span>
                                            <b>Status Lokalis</b>
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="4">
                                        <b>Jalan nafas/gigi geligi/leher :</b>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="4">
                                        {{ isset($data['jalannafas']) ? $data['jalannafas'] : '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2">
                                        <div>
                                            <b>Rencana Tindakan :</b>
                                        </div>
                                        {{ isset($data['rencanatindakan']) ? $data['rencanatindakan'] : '' }}
                                    </td>
                                    <td colspan="2">
                                        <div>
                                            <b>Riwayat Kesehatan / Penyakit :</b>
                                        </div>
                                        {{ isset($data['riwayatkesehatan']) ? $data['riwayatkesehatan'] : '' }}
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3">
                            <table class="border font-2 btl">
                                <tr>
                                    <td width="28%">
                                        <b>Alergi Obat</b>
                                    </td>
                                    <td width="22%">
                                        <input type="checkbox"
                                            {{ isset($data['alergianestesi']) && $data['alergianestesi'] == 'YA' ? 'checked' : '' }} />
                                        <span>Ya</span>
                                        <input type="checkbox"
                                            {{ isset($data['alergianestesi']) && $data['alergianestesi'] == 'TIDAK' ? 'checked' : '' }} />
                                        <span>Tidak</span>
                                    </td>
                                    <td width="28%">
                                        <b>Alergi Makanan</b>
                                    </td>
                                    <td width="22%">
                                        <input type="checkbox"
                                            {{ isset($data['makanananestesi']) && $data['makanananestesi'] == 'YA' ? 'checked' : '' }} />
                                        <span>Ya</span>
                                        <input type="checkbox"
                                            {{ isset($data['makanananestesi']) && $data['makanananestesi'] == 'TIDAK' ? 'checked' : '' }} />
                                        <span>Tidak</span>
                                    </td>
                                </tr>
                                @if (
                                    (isset($data['alergianestesi']) && $data['alergianestesi'] == 'YA') ||
                                        (isset($data['makanananestesi']) && $data['makanananestesi'] == 'YA'))
                                    <tr>
                                        @if (isset($data['alergianestesi']) && $data['alergianestesi'] == 'YA')
                                            <td colspan="2">
                                                <b>Sebutkan :</b>
                                                {{ isset($data['alergianestesiText']) ? $data['alergianestesiText'] : '' }}
                                            </td>
                                        @else
                                            <td colspan="2"></td>
                                        @endif

                                        @if (isset($data['makanananestesi']) && $data['makanananestesi'] == 'YA')
                                            <td colspan="2">
                                                <b>Sebutkan :</b>
                                                {{ isset($data['makanananestesiText']) ? $data['makanananestesiText'] : '' }}
                                            </td>
                                        @else
                                            <td colspan="2"></td>
                                        @endif
                                    </tr>
                                @endif
                                <tr>
                                    <td>
                                        <b>Operasi / anastesi sebelumnya</b>
                                    </td>
                                    <td>
                                        <input type="checkbox"
                                            {{ isset($data['anestesisebelumnya']) && $data['anestesisebelumnya'] == 'YA' ? 'checked' : '' }} />
                                        <span>Ya</span>
                                        <input type="checkbox"
                                            {{ isset($data['anestesisebelumnya']) && $data['anestesisebelumnya'] == 'TIDAK' ? 'checked' : '' }} />
                                        <span>Tidak</span>
                                    </td>
                                    <td>
                                        <b>Sedang mengkonsumsi obat</b>
                                    </td>
                                    <td>
                                        <input type="checkbox"
                                            {{ isset($data['mengkonsumsiobat']) && $data['mengkonsumsiobat'] == 'YA' ? 'checked' : '' }} />
                                        <span>Ya</span>
                                        <input type="checkbox"
                                            {{ isset($data['mengkonsumsiobat']) && $data['mengkonsumsiobat'] == 'TIDAK' ? 'checked' : '' }} />
                                        <span>Tidak</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <b>Riwayat anasthesi dan komplikasi</b>
                                    </td>
                                    <td>
                                        <input type="checkbox"
                                            {{ isset($data['riwayatkomplikasi']) && $data['riwayatkomplikasi'] == 'YA' ? 'checked' : '' }} />
                                        <span>Ya</span>
                                        <input type="checkbox"
                                            {{ isset($data['riwayatkomplikasi']) && $data['riwayatkomplikasi'] == 'TIDAK' ? 'checked' : '' }} />
                                        <span>Tidak</span>
                                    </td>
                                    <td>
                                        <b>Kebiasaan</b>
                                    </td>
                                    <td>
                                        <input type="checkbox"
                                            {{ isset($data['merokok']) && $data['merokok'] == 'Merokok' ? 'checked' : '' }} />
                                        <span>Merokok</span>
                                        <input type="checkbox"
                                            {{ isset($data['alkohol']) && $data['alkohol'] == 'Alkohol' ? 'checked' : '' }} />
                                        <span>Minum Alkohol</span>
                                    </td>
                                </tr>
                                <tr class="btl">
                                    <td colspan="4">
                                        <b>Respiratory</b>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="4">
                                        <input type="checkbox"
                                            {{ isset($data['asthma']) && $data['asthma'] == 'Asthma' ? 'checked' : '' }} />
                                        <span>Asthma</span>
                                        <input type="checkbox"
                                            {{ isset($data['bronchitis']) && $data['bronchitis'] == 'Bronchitis' ? 'checked' : '' }} />
                                        <span>Bronchitis</span>
                                        <input type="checkbox"
                                            {{ isset($data['recent']) && $data['recent'] == 'Recent URI' ? 'checked' : '' }} />
                                        <span>Recent URI</span>
                                        <input type="checkbox"
                                            {{ isset($data['copd']) && $data['copd'] == 'COPD' ? 'checked' : '' }} />
                                        <span>COPD</span>
                                        <input type="checkbox"
                                            {{ isset($data['sob']) && $data['sob'] == 'SOB' ? 'checked' : '' }} />
                                        <span>SOB</span>
                                        <input type="checkbox"
                                            {{ isset($data['dyspepsia']) && $data['dyspepsia'] == 'Duspepsia' ? 'checked' : '' }} />
                                        <span>Duspepsia</span>
                                        <input type="checkbox"
                                            {{ isset($data['wnlrespiratory']) && $data['wnlrespiratory'] == 'WNLRespiratory' ? 'checked' : '' }} />
                                        <span>WNL</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="4">
                                        <input type="checkbox"
                                            {{ isset($data['tuberculosis']) && $data['tuberculosis'] == 'Tuberculosis' ? 'checked' : '' }} />
                                        <span>Tuberculosis</span>
                                        <input type="checkbox"
                                            {{ isset($data['arthopnea']) && $data['arthopnea'] == 'Arthopnea' ? 'checked' : '' }} />
                                        <span>Arthopnea</span>
                                        <input type="checkbox"
                                            {{ isset($data['pneumonia']) && $data['pneumonia'] == 'Pneumonia' ? 'checked' : '' }} />
                                        <span>Pneumonia</span>
                                        <input type="checkbox"
                                            {{ isset($data['productive']) && $data['productive'] == 'Productive Cough' ? 'checked' : '' }} />
                                        <span>Productive Cough</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="4">
                                        <b>Cardiovascular</b>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="4">
                                        <input type="checkbox"
                                            {{ isset($data['hypertensi']) && $data['hypertensi'] == 'Hypertensi' ? 'checked' : '' }} />
                                        <span>Hypertensi</span>
                                        <input type="checkbox"
                                            {{ isset($data['angina']) && $data['angina'] == 'Angina' ? 'checked' : '' }} />
                                        <span>Angina</span>
                                        <input type="checkbox"
                                            {{ isset($data['mi']) && $data['mi'] == 'MI' ? 'checked' : '' }} />
                                        <span>MI</span>
                                        <input type="checkbox"
                                            {{ isset($data['ashd']) && $data['ashd'] == 'ASHD' ? 'checked' : '' }} />
                                        <span>ASHD</span>
                                        <input type="checkbox"
                                            {{ isset($data['murmur']) && $data['murmur'] == 'Murmur' ? 'checked' : '' }} />
                                        <span>Murmur</span>
                                        <input type="checkbox"
                                            {{ isset($data['chf']) && $data['chf'] == 'CHF' ? 'checked' : '' }} />
                                        <span>CHF</span>
                                        <input type="checkbox"
                                            {{ isset($data['wnlcardiovascular']) && $data['wnlcardiovascular'] == 'WNLCardiovascular' ? 'checked' : '' }} />
                                        <span>WNL</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="4">
                                        <input type="checkbox"
                                            {{ isset($data['pacemaker']) && $data['pacemaker'] == 'Pacemaker' ? 'checked' : '' }} />
                                        <span>Pacemaker</span>
                                        <input type="checkbox"
                                            {{ isset($data['dysrhythmia']) && $data['dysrhythmia'] == 'Dysrhythmia' ? 'checked' : '' }} />
                                        <span>Dysrhythmia</span>
                                        <input type="checkbox"
                                            {{ isset($data['rheumatic']) && $data['rheumatic'] == 'Rheumatic' ? 'checked' : '' }} />
                                        <span>Rheumatic Fever</span>
                                        <input type="checkbox"
                                            {{ isset($data['abnormal']) && $data['abnormal'] == 'Abnormal' ? 'checked' : '' }} />
                                        <span>Abnormal EKG</span>
                                        <input type="checkbox"
                                            {{ isset($data['exercise']) && $data['exercise'] == 'Exercise' ? 'checked' : '' }} />
                                        <span>Exercise/tolerance valvular discase</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="4">
                                        <b>Hepato</b>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="4">
                                        <input type="checkbox"
                                            {{ isset($data['bowel']) && $data['bowel'] == 'Bowel' ? 'checked' : '' }} />
                                        <span>Bowel obstruction</span>
                                        <input type="checkbox"
                                            {{ isset($data['hiatal']) && $data['hiatal'] == 'Hiatal' ? 'checked' : '' }} />
                                        <span>Hiatal hernia / reflux</span>
                                        <input type="checkbox"
                                            {{ isset($data['ucer']) && $data['ucer'] == 'Ucer' ? 'checked' : '' }} />
                                        <span>Ucer</span>
                                        <input type="checkbox"
                                            {{ isset($data['chrrosis']) && $data['chrrosis'] == 'Chrrosis' ? 'checked' : '' }} />
                                        <span>Chrrosis</span>
                                        <input type="checkbox"
                                            {{ isset($data['nausea']) && $data['nausea'] == 'Nausea & Vormiting' ? 'checked' : '' }} />
                                        <span>Nausea & Vormiting</span>
                                        <input type="checkbox"
                                            {{ isset($data['jaundice']) && $data['jaundice'] == 'Jaundice' ? 'checked' : '' }} />
                                        <span>Hepatitis/Jaundice</span>
                                        <input type="checkbox"
                                            {{ isset($data['wnlhepato']) && $data['wnlhepato'] == 'WNLHepato' ? 'checked' : '' }} />
                                        <span>WNL</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="4">
                                        <b>Renal</b>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="4">
                                        <input type="checkbox"
                                            {{ isset($data['diabetes']) && $data['diabetes'] == 'Diabetes' ? 'checked' : '' }} />
                                        <span>Diabetes</span>
                                        <input type="checkbox"
                                            {{ isset($data['thyroid']) && $data['thyroid'] == 'Thyroid' ? 'checked' : '' }} />
                                        <span>Thyroid Disease</span>
                                        <input type="checkbox"
                                            {{ isset($data['renal']) && $data['renal'] == 'Renal' ? 'checked' : '' }} />
                                        <span>Renal Failure / Dalysis</span>
                                        <input type="checkbox"
                                            {{ isset($data['weight']) && $data['weight'] == 'Weight Loss/ Gain' ? 'checked' : '' }} />
                                        <span>Weight Loss/ Gain</span>
                                        <input type="checkbox"
                                            {{ isset($data['dysrythmiarenal']) && $data['dysrythmiarenal'] == 'Dysrythmia' ? 'checked' : '' }} />
                                        <span>Dysrythmia</span>
                                        <input type="checkbox"
                                            {{ isset($data['urinary']) && $data['urinary'] == 'Urinary tract infection' ? 'checked' : '' }} />
                                        <span>Urinary tract infection</span>
                                        <input type="checkbox"
                                            {{ isset($data['wnlrenal']) && $data['wnlrenal'] == 'WNLRenal' ? 'checked' : '' }} />
                                        <span>WNL</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="4">
                                        <b>Neuro/Musculoskeletal</b>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="4">
                                        <input type="checkbox"
                                            {{ isset($data['arteitis']) && $data['arteitis'] == 'Arteitis' ? 'checked' : '' }} />
                                        <span>Arteitis</span>
                                        <input type="checkbox"
                                            {{ isset($data['muscule']) && $data['muscule'] == 'Muscule' ? 'checked' : '' }} />
                                        <span>Muscule</span>
                                        <input type="checkbox"
                                            {{ isset($data['weaknes']) && $data['weaknes'] == 'Weaknes' ? 'checked' : '' }} />
                                        <span>Weaknes</span>
                                        <input type="checkbox"
                                            {{ isset($data['DJD']) && $data['DJD'] == 'DJD' ? 'checked' : '' }} />
                                        <span>DJD</span>
                                        <input type="checkbox"
                                            {{ isset($data['seizures']) && $data['seizures'] == 'Seizures' ? 'checked' : '' }} />
                                        <span>Seizures</span>
                                        <input type="checkbox"
                                            {{ isset($data['backproblems']) && $data['backproblems'] == 'Back problems' ? 'checked' : '' }} />
                                        <span>Back problems</span>
                                        <input type="checkbox"
                                            {{ isset($data['wnlback']) && $data['wnlback'] == 'WNLBack' ? 'checked' : '' }} />
                                        <span>WNL</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="4">
                                        <input type="checkbox"
                                            {{ isset($data['neuromuscular']) && $data['neuromuscular'] == 'Neuromuscular' ? 'checked' : '' }} />
                                        <span>Neuromuscular dis paralys</span>
                                        <input type="checkbox"
                                            {{ isset($data['cva']) && $data['cva'] == 'CVA / strok / VIA' ? 'checked' : '' }} />
                                        <span>CVA / strok / VIA</span>
                                        <input type="checkbox"
                                            {{ isset($data['paresthesia']) && $data['paresthesia'] == 'Paresthesia' ? 'checked' : '' }} />
                                        <span>Paresthesia</span>
                                        <input type="checkbox"
                                            {{ isset($data['syncope']) && $data['syncope'] == 'Syncope' ? 'checked' : '' }} />
                                        <span>Syncope</span>
                                        <input type="checkbox"
                                            {{ isset($data['headaches']) && $data['headaches'] == 'Headaches' ? 'checked' : '' }} />
                                        <span>Headaches/ICP</span>
                                        <input type="checkbox"
                                            {{ isset($data['consiousness']) && $data['consiousness'] == 'Loss of consiousness' ? 'checked' : '' }} />
                                        <span>Loss of consiousness</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="4">
                                        <b>Other</b>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="4">
                                        <input type="checkbox"
                                            {{ isset($data['anemia']) && $data['anemia'] == 'Anemia' ? 'checked' : '' }} />
                                        <span>Anemia</span>
                                        <input type="checkbox"
                                            {{ isset($data['immunosuppresed']) && $data['immunosuppresed'] == 'Immunosuppresed' ? 'checked' : '' }} />
                                        <span>Immunosuppresed</span>
                                        <input type="checkbox"
                                            {{ isset($data['bleeding']) && $data['bleeding'] == 'Bleeding tendencies' ? 'checked' : '' }} />
                                        <span>Bleeding tendencies</span>
                                        <input type="checkbox"
                                            {{ isset($data['pregnancy']) && $data['pregnancy'] == 'Pregnancy' ? 'checked' : '' }} />
                                        <span>Pregnancy</span>
                                        <input type="checkbox"
                                            {{ isset($data['cancer']) && $data['cancer'] == 'Cancer' ? 'checked' : '' }} />
                                        <span>Cancer</span>
                                        <input type="checkbox"
                                            {{ isset($data['sickie']) && $data['sickie'] == 'Sickie cell dis / trait' ? 'checked' : '' }} />
                                        <span>Sickie cell dis / trait</span>
                                        <input type="checkbox"
                                            {{ isset($data['wnlother']) && $data['wnlother'] == 'WNLOther' ? 'checked' : '' }} />
                                        <span>WNL</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="4">
                                        <input type="checkbox"
                                            {{ isset($data['chemotherapy']) && $data['chemotherapy'] == 'Chemotherapy' ? 'checked' : '' }} />
                                        <span>Chemotherapy</span>
                                        <input type="checkbox"
                                            {{ isset($data['steroids']) && $data['steroids'] == 'Recent steroids' ? 'checked' : '' }} />
                                        <span>Recent steroids</span>
                                        <input type="checkbox"
                                            {{ isset($data['dehydration']) && $data['dehydration'] == 'Dehydration' ? 'checked' : '' }} />
                                        <span>Dehydration</span>
                                        <input type="checkbox"
                                            {{ isset($data['transfusion']) && $data['transfusion'] == 'Tranfusion history' ? 'checked' : '' }} />
                                        <span>Tranfusion history</span>
                                        <input type="checkbox"
                                            {{ isset($data['hemophilia']) && $data['hemophilia'] == 'Hemophilia' ? 'checked' : '' }} />
                                        <span>Hemophilia</span>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                @endif
                <!-- END:ANESTESI -->

                @if (!isset($ruangan) || stripos($ruangan, 'INTERNA') != false || stripos($ruangan, 'GERIATRI') != false)
                    <tr>
                        <td colspan="2">
                            <table class="border font">
                                <tr>
                                    <td colspan="3">
                                        <table>
                                            <tr>
                                                <td colspan="4">
                                                    <b>3. PENILAIAN STATUS NUTRISI (MINI NUTRITIONAL ASSESSMENT)</b>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="4">
                                                    <p>1. IMT (Kg/M<sup>2</sup>)</p>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <div>
                                                        <input type="checkbox"
                                                            {{ isset($data['CBimt']) && $data['CBimt'] == '(0) < 19' ? 'checked' : '' }} />
                                                        <span>(0) &lt; 19</span>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div>
                                                        <input type="checkbox"
                                                            {{ isset($data['CBimt']) && $data['CBimt'] == '(1) 19-21' ? 'checked' : '' }} />
                                                        <span>(1) 19-21</span>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div>
                                                        <input type="checkbox"
                                                            {{ isset($data['CBimt']) && $data['CBimt'] == '(2) 21-23' ? 'checked' : '' }} />
                                                        <span>(2) 21-23</span>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div>
                                                        <input type="checkbox"
                                                            {{ isset($data['CBimt']) && $data['CBimt'] == '(3) >23' ? 'checked' : '' }} />
                                                        <span>(3) >23</span>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="4">
                                                    <p>2. Lingkar lengan atas (Cm)</p>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <div>
                                                        <input type="checkbox"
                                                            {{ isset($data['CBlingkarLenganAtas']) && $data['CBlingkarLenganAtas'] == '(0) <21' ? 'checked' : '' }} />
                                                        <span>(0) &lt; 21</span>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div>
                                                        <input type="checkbox"
                                                            {{ isset($data['CBlingkarLenganAtas']) && $data['CBlingkarLenganAtas'] == '(0,5) 21-22' ? 'checked' : '' }} />
                                                        <span>(0,5) 21-22</span>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div>
                                                        <input type="checkbox"
                                                            {{ isset($data['CBlingkarLenganAtas']) && $data['CBlingkarLenganAtas'] == '(1) >22' ? 'checked' : '' }} />
                                                        <span>(1) >22</span>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="4">
                                                    <p>3. Lingkar betis (Cm)</p>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <div>
                                                        <input type="checkbox"
                                                            {{ isset($data['CBlingkarBetis']) && $data['CBlingkarBetis'] == '(0) ≤31' ? 'checked' : '' }} />
                                                        <span>(0) <span class="logo">≤</span>31</span>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div>
                                                        <input type="checkbox"
                                                            {{ isset($data['CBlingkarBetis']) && $data['CBlingkarBetis'] == '(1) >31' ? 'checked' : '' }} />
                                                        <span>(1) >31</span>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="4">
                                                    <p>4. BB selama 3 bulan terakhir</p>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <div>
                                                        <input type="checkbox"
                                                            {{ isset($data['CBbbSelama3bulan']) && $data['CBbbSelama3bulan'] == '(0) Kehilangan > 3kg' ? 'checked' : '' }} />
                                                        <span>(0) Kehilangan > 3kg</span>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div>
                                                        <input type="checkbox"
                                                            {{ isset($data['CBbbSelama3bulan']) && $data['CBbbSelama3bulan'] == '(1) Tidak tahu' ? 'checked' : '' }} />
                                                        <span>(1) Tidak tahu</span>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div>
                                                        <input type="checkbox"
                                                            {{ isset($data['CBbbSelama3bulan']) && $data['CBbbSelama3bulan'] == '(2) Kehilangan antara 1-3kg' ? 'checked' : '' }} />
                                                        <span>(2) Kehilangan antara 1-3kg</span>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div>
                                                        <input type="checkbox"
                                                            {{ isset($data['CBbbSelama3bulan']) && $data['CBbbSelama3bulan'] == '(3) Tidak kehilangan BB' ? 'checked' : '' }} />
                                                        <span>(3) Tidak kehilangan BB</span>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="4">
                                                    <p>5. Hidup tidak tergantung (tidak di tempat perawatan/RS)</p>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <div>
                                                        <input type="checkbox"
                                                            {{ isset($data['CBhidupTidakTergantung']) && $data['CBhidupTidakTergantung'] == '(0) Ya' ? 'checked' : '' }} />
                                                        <span>(0) Ya</span>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div>
                                                        <input type="checkbox"
                                                            {{ isset($data['CBhidupTidakTergantung']) && $data['CBhidupTidakTergantung'] == '(1) Tidak' ? 'checked' : '' }} />
                                                        <span>(1) Tidak</span>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="4">
                                                    <p>6. Menggunakan lebih dari 3 jenis obat per hari</p>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <div>
                                                        <input type="checkbox"
                                                            {{ isset($data['CBmenggunakanLebihDari3JenisObat']) && $data['CBmenggunakanLebihDari3JenisObat'] == '(0) Ya' ? 'checked' : '' }} />
                                                        <span>(0) Ya</span>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div>
                                                        <input type="checkbox"
                                                            {{ isset($data['CBmenggunakanLebihDari3JenisObat']) && $data['CBmenggunakanLebihDari3JenisObat'] == '(1) Tidak' ? 'checked' : '' }} />
                                                        <span>(1) Tidak</span>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="4">
                                                    <p>7. Mengalami stress psikologis atau penyakit akut dalam 3 bulan
                                                        terakhir</p>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <div>
                                                        <input type="checkbox"
                                                            {{ isset($data['CBmengalamiStressPsikologis']) && $data['CBmengalamiStressPsikologis'] == '(0) Ya' ? 'checked' : '' }} />
                                                        <span>(0) Ya</span>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div>
                                                        <input type="checkbox"
                                                            {{ isset($data['CBmengalamiStressPsikologis']) && $data['CBmengalamiStressPsikologis'] == '(1) Tidak' ? 'checked' : '' }} />
                                                        <span>(1) Tidak</span>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="4">
                                                    <p>8. Mobilitas</p>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <div>
                                                        <input type="checkbox"
                                                            {{ isset($data['CBmobilitas']) && $data['CBmobilitas'] == '(0) Hanya terbaring/di atas kursi roda' ? 'checked' : '' }} />
                                                        <span>(0) Hanya terbaring/di atas kursi roda</span>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div>
                                                        <input type="checkbox"
                                                            {{ isset($data['CBmobilitas']) && $data['CBmobilitas'] == '(1) Bisa bangkit dari tempat tidur tapi tidak keluar rumah' ? 'checked' : '' }} />
                                                        <span>(1) Bisa bangkit dari tempat tidur tapi tidak keluar
                                                            rumah</span>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div>
                                                        <input type="checkbox"
                                                            {{ isset($data['CBmobilitas']) && $data['CBmobilitas'] == '(2) Bisa keluar rumah' ? 'checked' : '' }} />
                                                        <span>(2) Bisa keluar rumah</span>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="4">
                                                    <p>9. Masalah neuropsikologis</p>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <div>
                                                        <input type="checkbox"
                                                            {{ isset($data['CBmasalahNeuropsikologis']) && $data['CBmasalahNeuropsikologis'] == '(0) Demensia berat dan depresi' ? 'checked' : '' }} />
                                                        <span>(0) Demensia berat dan depresi</span>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div>
                                                        <input type="checkbox"
                                                            {{ isset($data['CBmasalahNeuropsikologis']) && $data['CBmasalahNeuropsikologis'] == '(1) Demensia ringan' ? 'checked' : '' }} />
                                                        <span>(1) Demensia ringan</span>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div>
                                                        <input type="checkbox"
                                                            {{ isset($data['CBmasalahNeuropsikologis']) && $data['CBmasalahNeuropsikologis'] == '(2) Tidak ada masalah psikologis' ? 'checked' : '' }} />
                                                        <span>(2) Tidak ada masalah psikologis</span>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="4">
                                                    <p>10. Nyeri tekan/luka kulit</p>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <div>
                                                        <input type="checkbox"
                                                            {{ isset($data['CBnyeriTekanLukaKulit']) && $data['CBnyeriTekanLukaKulit'] == '(0) Ya' ? 'checked' : '' }} />
                                                        <span>(0) Ya</span>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div>
                                                        <input type="checkbox"
                                                            {{ isset($data['CBnyeriTekanLukaKulit']) && $data['CBnyeriTekanLukaKulit'] == '(1) Tidak' ? 'checked' : '' }} />
                                                        <span>(1) Tidak</span>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="4">
                                                    <p>11. Jumlah daging yang dikonsumsi setiap hari</p>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <div>
                                                        <input type="checkbox"
                                                            {{ isset($data['CBjumlahDagingYangDikonsumsi']) && $data['CBjumlahDagingYangDikonsumsi'] == '(0) 1x makan' ? 'checked' : '' }} />
                                                        <span>(0) 1x makan</span>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div>
                                                        <input type="checkbox"
                                                            {{ isset($data['CBjumlahDagingYangDikonsumsi']) && $data['CBjumlahDagingYangDikonsumsi'] == '(1) 2x makan' ? 'checked' : '' }} />
                                                        <span>(1) 2x makan</span>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div>
                                                        <input type="checkbox"
                                                            {{ isset($data['CBjumlahDagingYangDikonsumsi']) && $data['CBjumlahDagingYangDikonsumsi'] == '(2) 3x makan' ? 'checked' : '' }} />
                                                        <span>(2) 3x makan</span>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="4">
                                                    <p>12. Asupan protein terpilih</p>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="2">
                                                    <div>
                                                        a. Minimal 1x penyajian produk susu olahan per hari
                                                    </div>
                                                </td>
                                                <td>
                                                    <div>
                                                        <input type="checkbox"
                                                            {{ isset($data['CBminimal1xPenyajian']) && $data['CBminimal1xPenyajian'] == '(0) Tidak' ? 'checked' : '' }} />
                                                        <span>(0) Tidak</span>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div>
                                                        <input type="checkbox"
                                                            {{ isset($data['CBminimal1xPenyajian']) && $data['CBminimal1xPenyajian'] == '(1) Ya' ? 'checked' : '' }} />
                                                        <span>(1) Ya</span>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="2">
                                                    <div>
                                                        b. Dua atau lebih penyajian produk kacang-kacangan dan telur per
                                                        minggu
                                                    </div>
                                                </td>
                                                <td>
                                                    <div>
                                                        <input type="checkbox"
                                                            {{ isset($data['CBduaAtauLebihPenyajian']) && $data['CBduaAtauLebihPenyajian'] == '(0) Tidak' ? 'checked' : '' }} />
                                                        <span>(0) Tidak</span>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div>
                                                        <input type="checkbox"
                                                            {{ isset($data['CBduaAtauLebihPenyajian']) && $data['CBduaAtauLebihPenyajian'] == '(1) Ya' ? 'checked' : '' }} />
                                                        <span>(1) Ya</span>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="2">
                                                    <div>
                                                        c. Daging, ikan, unggas tiap hari
                                                    </div>
                                                </td>
                                                <td>
                                                    <div>
                                                        <input type="checkbox"
                                                            {{ isset($data['CBdagingIkanUnggas']) && $data['CBdagingIkanUnggas'] == '(0) Tidak' ? 'checked' : '' }} />
                                                        <span>(0) Tidak</span>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div>
                                                        <input type="checkbox"
                                                            {{ isset($data['CBdagingIkanUnggas']) && $data['CBdagingIkanUnggas'] == '(1) Ya' ? 'checked' : '' }} />
                                                        <span>(1) Ya</span>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="4">
                                                    <div>
                                                        13. Konsumsi 2 atau lebih penyajian sayur atau buah-buahan per
                                                        hari
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <div>
                                                        <input type="checkbox"
                                                            {{ isset($data['CBKonsumsi2ataulebihPenyajianSayur']) && $data['CBKonsumsi2ataulebihPenyajianSayur'] == '(0) Tidak' ? 'checked' : '' }} />
                                                        <span>(0) Tidak</span>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div>
                                                        <input type="checkbox"
                                                            {{ isset($data['CBKonsumsi2ataulebihPenyajianSayur']) && $data['CBKonsumsi2ataulebihPenyajianSayur'] == '(1) Ya' ? 'checked' : '' }} />
                                                        <span>(1) Ya</span>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="4">
                                                    <div>
                                                        14. Asupan makanan dalam 3 bulan terakhir (kehilangan nafsu
                                                        makan)
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <div>
                                                        <input type="checkbox"
                                                            {{ isset($data['CBAsupanMakananDalam3BulanTerakhir']) && $data['CBAsupanMakananDalam3BulanTerakhir'] == '(0) Berat' ? 'checked' : '' }} />
                                                        <span>(0) Berat</span>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div>
                                                        <input type="checkbox"
                                                            {{ isset($data['CBAsupanMakananDalam3BulanTerakhir']) && $data['CBAsupanMakananDalam3BulanTerakhir'] == '(1) Sedang' ? 'checked' : '' }} />
                                                        <span>(1) Sedang</span>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div>
                                                        <input type="checkbox"
                                                            {{ isset($data['CBAsupanMakananDalam3BulanTerakhir']) && $data['CBAsupanMakananDalam3BulanTerakhir'] == '(2) Ringan' ? 'checked' : '' }} />
                                                        <span>(2) Ringan</span>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="4">
                                                    <div>
                                                        15. Jumlah cairan yang dikonsumsi per hari
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <div>
                                                        <input type="checkbox"
                                                            {{ isset($data['CBJumlahCairanYangDikonsumsi']) && $data['CBJumlahCairanYangDikonsumsi'] == '(0) <3 cangkir' ? 'checked' : '' }} />
                                                        <span>(0) &lt;3 cangkir</span>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div>
                                                        <input type="checkbox"
                                                            {{ isset($data['CBJumlahCairanYangDikonsumsi']) && $data['CBJumlahCairanYangDikonsumsi'] == '(0,5) 3-5 cangkir' ? 'checked' : '' }} />
                                                        <span>(0,5) 3-5 cangkir</span>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div>
                                                        <input type="checkbox"
                                                            {{ isset($data['CBJumlahCairanYangDikonsumsi']) && $data['CBJumlahCairanYangDikonsumsi'] == '(1) >5 cangkir' ? 'checked' : '' }} />
                                                        <span>(1) >5 cangkir</span>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="4">
                                                    <div>
                                                        16. Pola makan
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <div>
                                                        <input type="checkbox"
                                                            {{ isset($data['CBpolaMakan']) && $data['CBpolaMakan'] == '(0) Tidak bisa makan tanpa bantuan' ? 'checked' : '' }} />
                                                        <span>(0) Tidak bisa makan tanpa bantuan</span>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div>
                                                        <input type="checkbox"
                                                            {{ isset($data['CBpolaMakan']) && $data['CBpolaMakan'] == '(1) Makan sendiri dengan sedikit kesulitan' ? 'checked' : '' }} />
                                                        <span>(1) Makan sendiri dengan sedikit kesulitan</span>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div>
                                                        <input type="checkbox"
                                                            {{ isset($data['CBpolaMakan']) && $data['CBpolaMakan'] == '(2) Makan sendiri tanpa kesulitan' ? 'checked' : '' }} />
                                                        <span>(2) Makan sendiri tanpa kesulitan</span>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="4">
                                                    <div>
                                                        17. Apakah pasien merasakan memiliki masalah gizi
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <div>
                                                        <input type="checkbox"
                                                            {{ isset($data['CBapakahPasienMerasakanMasalahGizi']) && $data['CBapakahPasienMerasakanMasalahGizi'] == '(0) Malntrisi' ? 'checked' : '' }} />
                                                        <span>(0) Malntrisi</span>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div>
                                                        <input type="checkbox"
                                                            {{ isset($data['CBapakahPasienMerasakanMasalahGizi']) && $data['CBapakahPasienMerasakanMasalahGizi'] == '(1) Tidak tahu/malnutrisi sedang' ? 'checked' : '' }} />
                                                        <span>(1) Tidak tahu/malnutrisi sedang</span>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div>
                                                        <input type="checkbox"
                                                            {{ isset($data['CBapakahPasienMerasakanMasalahGizi']) && $data['CBapakahPasienMerasakanMasalahGizi'] == '(2) Tidak ada masalah gizi' ? 'checked' : '' }} />
                                                        <span>(2) Tidak ada masalah gizi</span>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="4">
                                                    <div>
                                                        18. Penilaian pasien terhadap kesehatannya bil dibandingkan
                                                        dengan kelompok usia yang sama
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <div>
                                                        <input type="checkbox"
                                                            {{ isset($data['CBpenilaianPasienTerhadapKesehatannya']) && $data['CBpenilaianPasienTerhadapKesehatannya'] == '(0) tidak baik' ? 'checked' : '' }} />
                                                        <span>(0) tidak baik</span>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div>
                                                        <input type="checkbox"
                                                            {{ isset($data['CBpenilaianPasienTerhadapKesehatannya']) && $data['CBpenilaianPasienTerhadapKesehatannya'] == '(0,5) Tidak tahu' ? 'checked' : '' }} />
                                                        <span>(0,5) Tidak tahu</span>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div>
                                                        <input type="checkbox"
                                                            {{ isset($data['CBpenilaianPasienTerhadapKesehatannya']) && $data['CBpenilaianPasienTerhadapKesehatannya'] == '(1) Sama baik' ? 'checked' : '' }} />
                                                        <span>(1) Sama baik</span>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div>
                                                        <input type="checkbox"
                                                            {{ isset($data['CBpenilaianPasienTerhadapKesehatannya']) && $data['CBpenilaianPasienTerhadapKesehatannya'] == '(2) Lebih baik' ? 'checked' : '' }} />
                                                        <span>(2) Lebih baik</span>
                                                    </div>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="3">
                                        <table>
                                            <tr>
                                                <td>
                                                    <div>
                                                        <input type="checkbox"
                                                            {{ isset($data['CBtotalSkorPenilaianStatusNutrisi']) && $data['CBtotalSkorPenilaianStatusNutrisi'] == 'Normal (Skor Penapisan ≥24)' ? 'checked' : '' }} />
                                                        <span>Normal (Skor Penapisan <span class="logo">&ge;</span>
                                                            24)<span>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div>
                                                        <input type="checkbox"
                                                            {{ isset($data['CBtotalSkorPenilaianStatusNutrisi']) && $data['CBtotalSkorPenilaianStatusNutrisi'] == 'Berisiko Malnutrisi (Skor Pengkajian 17-23,5)  ' ? 'checked' : '' }} />
                                                        <span>Berisiko Malnutrisi (Skor Pengkajian 17-23,5) </span>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div>
                                                        <input type="checkbox"
                                                            {{ isset($data['CBtotalSkorPenilaianStatusNutrisi']) && $data['CBtotalSkorPenilaianStatusNutrisi'] == 'Malnutrisi (Skor Pengkajian <17)' ? 'checked' : '' }} />
                                                        <span>Malnutrisi (Skor Pengkajian &lt;17)</span>
                                                    </div>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3">
                            <table class="border font">
                                <tr>
                                    <td colspan="4">
                                        <b>4. PENAPISAN KOGNITIF (MI NI MENTAL STATE EXAMINATION)</b>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="4">
                                        <b>1. ORIENTASI</b>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="4">
                                        <span style="white-space: pre-line;">(5) Sekarang
                                            (hari),(tanggal),(bulan),(tahun) berapa,(musim) apa?
                                            (5) Sekarang kita berada di mana ? (jalan),(nomor
                                            rumah),(kota),(kabupaten),(propinsi)
                                        </span>
                                        <div>
                                            <b>2. REGISTRASI</b>
                                        </div>
                                        <span>
                                            (3) Pasien diminta untuk mengulang tiga kata yang disebutkan oleh pemeriksa
                                            (bola, kursi, sepatu)
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="4">
                                        <span>
                                            Jumlah Percobaan :
                                        </span>
                                        {{ isset($data['TBjumlahPercobaan']) ? $data['TBjumlahPercobaan'] : '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="4">
                                        <div>
                                            <b>3. ATENSI dan KALKULASI</b>
                                        </div>
                                        <span>
                                            (5) Hitunglah berturut-turut selang 7 mulai dari 100 ke bawah. Berilah 1
                                            angka untuk tiap jawaban yang benar. Berhenti setelah 5 hitungan
                                            (93,86,79,72,65). Kemungkinan lain, ejalah kata “dunia” dari akhir ke awal
                                            (a-i-n-u-d)
                                        </span>
                                        <div>
                                            <b>4. MENGINGAT</b>
                                        </div>
                                        <span>
                                            (3) Tanyalah kembali nama ke 3 benda yang telah disebutkan di atas. Berilah
                                            1 angka untuk tiap jawaban yang benar.
                                        </span>
                                        <div>
                                            <b>
                                                5. BAHASA
                                            </b>
                                        </div>
                                        <span style="white-space: pre-line;">
                                            (2) Apakah nama benda-benda ini? Perlihatkan pensil dan arloji
                                            (1) Ulanglah kalimat berikut : “ Jika tidak, dan Atau Tapi ”.
                                            (3) Laksanakan 3 buah perintah ini : “ Peganglah selembar kertas dengan
                                            tangan kananmu,
                                            lipatlah kertas itu pada pertengahan dan letakkanlah di lantai”.
                                            (1) Bacalah dan laksanakan perintah berikut “PEJAMKAN MATA ANDA”
                                            (1) Tulislah sebuah kalimat
                                            (1) Tirulah gambar ini (di samping gambar tersebut)</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="4">
                                        <img src="{{ 'img/AAM_Geriatri.png' }}" width="200"
                                            alt="Gambar Geriatri">
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <input type="checkbox"
                                            {{ isset($data['CBSkorPenapisanKognitif']) && $data['CBSkorPenapisanKognitif'] == 'Normal (25 – 30)' ? 'checked' : '' }} />
                                        <span>Normal (25 – 30)</span>
                                    </td>
                                    <td>
                                        <input type="checkbox"
                                            {{ isset($data['CBSkorPenapisanKognitif']) && $data['CBSkorPenapisanKognitif'] == 'Gangguan Kognitif ringan (MCI) (20 – 25)' ? 'checked' : '' }} />
                                        <span>Gangguan Kognitif ringan (MCI) (20 – 25)</span>
                                    </td>
                                    <td>
                                        <input type="checkbox"
                                            {{ isset($data['CBSkorPenapisanKognitif']) && $data['CBSkorPenapisanKognitif'] == 'Gangguan kognitif pasti (< 20)' ? 'checked' : '' }} />
                                        <span>Gangguan kognitif pasti (<span class="logo">&lt;</span> 20)</span>
                                    </td>
                                    <td>
                                        <input type="checkbox"
                                            {{ isset($data['CBSkorPenapisanKognitif']) && $data['CBSkorPenapisanKognitif'] == 'Tidak dapat dievaluasi' ? 'checked' : '' }} />
                                        <span>Tidak dapat dievaluasi</span>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3">
                            <table class="border font">
                                <tr>
                                    <td colspan="4">
                                        <b>5. PENAPISAN DEPRESI(GERIATRIC DEPRESSION SCALE)</b>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="4">
                                        <table border="1">
                                            <tr class="bg-cyan">
                                                <td width="5%">
                                                    No
                                                </td>
                                                <td width="80%">
                                                    Deskripsi
                                                </td>
                                                <td width="7%">
                                                    (0)
                                                </td>
                                                <td width="7%">
                                                    (1)
                                                </td>
                                            </tr>
                                            @if ($ListPenapisanDepresi)
                                                @foreach ($ListPenapisanDepresi as $index => $item)
                                                    <tr>
                                                        <td style="text-align:center">{{ $index + 1 }}</td>
                                                        <td>{{ $item['caption'] }}</td>
                                                        <td style="text-align:center">
                                                            <input type="checkbox"
                                                                {{ isset($data['CBnilai0_' . $index]) && $data['CBnilai0_' . $index] == $item['nilai0'] ? 'checked' : '' }} />
                                                            <span>{{ $item['nilai0'] }}</span>
                                                        </td>
                                                        <td style="text-align:center">
                                                            <input type="checkbox"
                                                                {{ isset($data['CBnilai1_' . $index]) && $data['CBnilai1_' . $index] == $item['nilai1'] ? 'checked' : '' }} />
                                                            <span>{{ $item['nilai1'] }}</span>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @endif
                                        </table>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <input type="checkbox"
                                            {{ isset($data['CBnormalPenapisanDepresi']) && $data['CBnormalPenapisanDepresi'] == 'Normal (0-9)' ? 'checked' : '' }} />
                                        <span>Normal (0-9)</span>
                                    </td>
                                    <td>
                                        <input type="checkbox"
                                            {{ isset($data['CBdepresiRinganPenapisanDepresi']) && $data['CBdepresiRinganPenapisanDepresi'] == 'Depresi Ringan (10 – 19)' ? 'checked' : '' }} />
                                        <span>Depresi Ringan (10 – 19)</span>
                                    </td>
                                    <td>
                                        <input type="checkbox"
                                            {{ isset($data['CBdepresiBeratPenapisanDepresi']) && $data['CBdepresiBeratPenapisanDepresi'] == 'Depresi berat (20 – 30)' ? 'checked' : '' }} />
                                        <span>Depresi berat (20 – 30)</span>
                                    </td>
                                    <td>
                                        <input type="checkbox"
                                            {{ isset($data['CBtidakDapatDievaluasiPenapisanDepresi']) && $data['CBtidakDapatDievaluasiPenapisanDepresi'] == 'Tidak dapat dievaluasi' ? 'checked' : '' }} />
                                        <span>Tidak dapat dievaluasi</span>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3">
                            <table class="border font">
                                <tr>
                                    <td colspan="4">
                                        <b>6. PENAPISAN INKONTINENSIA</b>
                                    </td>
                                </tr>
                                @foreach ($listPI as $index => $item)
                                    <tr>
                                        <td colspan="4">
                                            <input type="checkbox"
                                                {{ isset($data['CBPI_' . $index]) && $data['CBPI_' . $index] == $item['caption'] ? 'checked' : '' }} />
                                            <span>{{ $item['caption'] }}</span>
                                        </td>
                                    </tr>
                                @endforeach
                                <tr>
                                    <td>
                                        <input type="checkbox"
                                            {{ isset($data['CBtotalSkorPI']) && $data['CBtotalSkorPI'] == 'Tidak ada inkontinensia (0)' ? 'checked' : '' }} />
                                        <span>Tidak ada inkontinensia (0)</span>
                                    </td>
                                    <td>
                                        <input type="checkbox"
                                            {{ isset($data['CBtotalSkorPI']) && $data['CBtotalSkorPI'] == 'Inkontinensia ringan (1 – 2.5)' ? 'checked' : '' }} />
                                        <span>Inkontinensia ringan (1 – 2.5)</span>
                                    </td>
                                    <td>
                                        <input type="checkbox"
                                            {{ isset($data['CBtotalSkorPI']) && $data['CBtotalSkorPI'] == 'I. sedang (4 – 6.5)' ? 'checked' : '' }} />
                                        <span>I. sedang (4 – 6.5)</span>
                                    </td>
                                    <td>
                                        <input type="checkbox"
                                            {{ isset($data['CBtotalSkorPI']) && $data['CBtotalSkorPI'] == 'I. berat (≥ 8)' ? 'checked' : '' }} />
                                        <span>I. berat (<span class="logo">≥</span> 8)</span>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3">
                            <table class="border font">
                                <tr>
                                    <td colspan="4">
                                        <b>
                                            7. PENAPISAN DEEP VEIN THROMBOSIS (WELLS SCORE SYSTEM)
                                        </b>
                                    </td>
                                </tr>
                                @foreach ($listPDVT as $index => $item)
                                    <tr>
                                        <td colspan="4">
                                            <input type="checkbox"
                                                {{ isset($data['CBPDVT_' . $index]) && $data['CBPDVT_' . $index] == $item['caption'] ? 'checked' : '' }} />
                                            <span>{{ $item['caption'] }}</span>
                                        </td>
                                    </tr>
                                @endforeach
                                <tr>
                                    <td colspan="4">
                                        {{ isset($data['TBtotalSkorPDVT']) ? $data['TBtotalSkorPDVT'] : '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <input type="checkbox"
                                            {{ isset($data['CBtotalSkorPDVT']) && $data['CBtotalSkorPDVT'] == 'Risiko rendah (< 1)' ? 'checked' : '' }} />
                                        <span>Risiko rendah (<span class="logo">
                                                << /span> 1)
                                            </span>
                                    </td>
                                    <td>
                                        <input type="checkbox"
                                            {{ isset($data['CBtotalSkorPDVT']) && $data['CBtotalSkorPDVT'] == 'Risiko sedang (1 – 2)' ? 'checked' : '' }} />
                                        <span>Risiko sedang (1 – 2)</span>
                                    </td>
                                    <td>
                                        <input type="checkbox"
                                            {{ isset($data['CBtotalSkorPDVT']) && $data['CBtotalSkorPDVT'] == 'Risiko tinggi (> 3)' ? 'checked' : '' }} />
                                        <span>Risiko tinggi (<span class="logo">&gt;</span> 3)</span>
                                    </td>
                                    <td>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3">
                            <table class="border font">
                                <tr>
                                    <td colspan="4">
                                        <b>
                                            8. ULKUS DEKUBITUS
                                        </b>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="4">
                                        <span style="white-space: pre-line;">
                                            Tidak ada Ada (dilanjutkan dengan klarifikasi She)
                                            Stadium I : Eritema nonblanchable pada kulit yang masih utuh atau perubahan
                                            warna kulit yang hangat, edema, dan berindurasi pada pasien dengan kulit
                                            gelap
                                            Stadium II : Sudah terjadi kehilangan lapisan kulit epidermis dan/atau
                                            dermis
                                            Stadium III: Ulkus sudah berkembang ke jaringan lunak dan ke lapisan fasia
                                            dalam
                                            Stadium IV : Jaringan otot dan tulang sudah terlibat
                                        </span>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3">
                            <table class="border font">
                                <tr>
                                    <td colspan="5">
                                        <b>9. PENAPISAN INSOMNIA (INSOMNIA SEVERITY INDEX)</b>
                                    </td>
                                </tr>
                                @foreach ($listPenapisanInsomnia as $index => $item)
                                    <tr>
                                        <td colspan="5">{{ $item['caption'] }}</td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <input type="checkbox"
                                                {{ isset($data['CB0PenapisanInsomnia_' . $index]) && $data['CB0PenapisanInsomnia_' . $index] == $item['cb0'] ? 'checked' : '' }} />
                                            <span>{{ $item['cb0'] }}</span>
                                        </td>
                                        <td>
                                            <input type="checkbox"
                                                {{ isset($data['CB1PenapisanInsomnia_' . $index]) && $data['CB1PenapisanInsomnia_' . $index] == $item['cb1'] ? 'checked' : '' }} />
                                            <span>{{ $item['cb1'] }}</span>
                                        </td>
                                        <td>
                                            <input type="checkbox"
                                                {{ isset($data['CB2PenapisanInsomnia_' . $index]) && $data['CB2PenapisanInsomnia_' . $index] == $item['cb2'] ? 'checked' : '' }} />
                                            <span>{{ $item['cb2'] }}</span>
                                        </td>
                                        <td>
                                            <input type="checkbox"
                                                {{ isset($data['CB3PenapisanInsomnia_' . $index]) && $data['CB3PenapisanInsomnia_' . $index] == $item['cb3'] ? 'checked' : '' }} />
                                            <span>{{ $item['cb3'] }}</span>
                                        </td>
                                        <td>
                                            <input type="checkbox"
                                                {{ isset($data['CB4PenapisanInsomnia_' . $index]) && $data['CB4PenapisanInsomnia_' . $index] == $item['cb4'] ? 'checked' : '' }} />
                                            <span>{{ $item['cb4'] }}</span>
                                        </td>
                                    </tr>
                                @endforeach
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3">
                            <table class="border font">
                                <tr>
                                    <td>
                                        <input type="checkbox"
                                            {{ isset($data['CBSkorPenapisanInsomnia']) && $data['CBSkorPenapisanInsomnia'] == 'Tidak Insomnia (0-7)' ? 'checked' : '' }} />
                                        <span>Tidak Insomnia (0-7)</span>
                                    </td>
                                    <td>
                                        <input type="checkbox"
                                            {{ isset($data['CBSkorPenapisanInsomnia']) && $data['CBSkorPenapisanInsomnia'] == 'Borderline Insomnia (8-14)' ? 'checked' : '' }} />
                                        <span>Borderline Insomnia (8-14)</span>
                                    </td>
                                    <td>
                                        <input type="checkbox"
                                            {{ isset($data['CBSkorPenapisanInsomnia']) && $data['CBSkorPenapisanInsomnia'] == 'Insomnia Sedang (15-21)' ? 'checked' : '' }} />
                                        <span>Insomnia Sedang (15-21)</span>
                                    </td>
                                    <td>
                                        <input type="checkbox"
                                            {{ isset($data['CBSkorPenapisanInsomnia']) && $data['CBSkorPenapisanInsomnia'] == 'Insomnia Berat (22-28)' ? 'checked' : '' }} />
                                        <span>Insomnia Berat (22-28)</span>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3">
                            <table class="border font">
                                <tr>
                                    <td colspan="5">
                                        <b>
                                            10. IDENTIFIKASI FALLS DAN RISIKO JATUH (SKALA MORSE)
                                        </b>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <span>
                                            1. Falls :
                                        </span>
                                    </td>
                                    <td>
                                        <input type="checkbox"
                                            {{ isset($data['CBFallsIFRJ']) && $data['CBFallsIFRJ'] == '≥ 3 kali' ? 'checked' : '' }} />
                                        <span><span class="logo">≥</span> 3 kali</span>
                                    </td>
                                    <td>
                                        <input type="checkbox"
                                            {{ isset($data['CBFallsIFRJ']) && $data['CBFallsIFRJ'] == '1-2 kali' ? 'checked' : '' }} />
                                        <span>1-2 kali</span>
                                    </td>
                                    <td>
                                        <input type="checkbox"
                                            {{ isset($data['CBFallsIFRJ']) && $data['CBFallsIFRJ'] == 'saat ini' ? 'checked' : '' }} />
                                        <span>saat ini</span>
                                    </td>
                                    <td>
                                        <input type="checkbox"
                                            {{ isset($data['CBFallsIFRJ']) && $data['CBFallsIFRJ'] == 'tidak pernah' ? 'checked' : '' }} />
                                        <span>tidak pernah</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <span>
                                            2. Total skor skala Morse :
                                        </span>
                                    </td>
                                    <td>
                                        <input type="checkbox"
                                            {{ isset($data['CBTotalSkorMorseIFRJ']) && $data['CBTotalSkorMorseIFRJ'] == 'Risiko rendah (0-7)' ? 'checked' : '' }} />
                                        <span>Risiko rendah (0-7)</span>
                                    </td>
                                    <td>
                                        <input type="checkbox"
                                            {{ isset($data['CBTotalSkorMorseIFRJ']) && $data['CBTotalSkorMorseIFRJ'] == 'Risiko tinggi (8-13)' ? 'checked' : '' }} />
                                        <span>Risiko tinggi (8-13)</span>
                                    </td>
                                    <td>
                                        <input type="checkbox"
                                            {{ isset($data['CBTotalSkorMorseIFRJ']) && $data['CBTotalSkorMorseIFRJ'] == 'Risiko sangat tinggi (≥14)' ? 'checked' : '' }} />
                                        <span>Risiko sangat tinggi (<span class="logo">≥</span>14)</span>
                                    </td>
                                    <td></td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3">
                            <table class="border font">
                                <tr>
                                    <td colspan="4">
                                        <b>
                                            11. IDENTIFIKASI FRAILTY
                                        </b>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <input type="checkbox"
                                            {{ isset($data['CBPenurunanbbIF']) && $data['CBPenurunanbbIF'] == 'Penurunan berat badan yang progresif (1)' ? 'checked' : '' }} />
                                        <span>Penurunan berat badan yang progresif (1)</span>
                                    </td>
                                    <td>
                                        <input type="checkbox"
                                            {{ isset($data['CBifSkor']) && $data['CBifSkor'] == 'Non frail (0)' ? 'checked' : '' }} />
                                        <span>Non frail (0)</span>
                                    </td>
                                    <td>
                                        <input type="checkbox"
                                            {{ isset($data['CBifSkor']) && $data['CBifSkor'] == 'Pre frail (1-2)' ? 'checked' : '' }} />
                                        <span>Pre frail (1-2)</span>
                                    </td>
                                    <td>
                                        <input type="checkbox"
                                            {{ isset($data['CBifSkor']) && $data['CBifSkor'] == 'Frailty (≥3)' ? 'checked' : '' }} />
                                        <span>Frailty (<span class="logo">≥</span>3)</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="4">
                                        <input type="checkbox"
                                            {{ isset($data['CBEnergidanEnduranceIF']) && $data['CBEnergidanEnduranceIF'] == 'Energi dan endurance yang lemah (1)' ? 'checked' : '' }} />
                                        <span>Energi dan endurance yang lemah (1)</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="4">
                                        <input type="checkbox"
                                            {{ isset($data['CBKecepatanBerjalanMelambatIF']) && $data['CBKecepatanBerjalanMelambatIF'] == 'Kecepatan berjalan melambat (1)' ? 'checked' : '' }} />
                                        <span>Kecepatan berjalan melambat (1)</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="4">
                                        <input type="checkbox"
                                            {{ isset($data['CBKeletihanatauDayatahanMenurunIF']) && $data['CBKeletihanatauDayatahanMenurunIF'] == 'Keletihan atau daya tahan menurun (1)' ? 'checked' : '' }} />
                                        <span>Keletihan atau daya tahan menurun (1)</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="4">
                                        <input type="checkbox"
                                            {{ isset($data['CBTingkatAktivitasFisikyangRendahIF']) && $data['CBTingkatAktivitasFisikyangRendahIF'] == 'Tingkat aktivitas fisik yang rendah (1)' ? 'checked' : '' }} />
                                        <span>Tingkat aktivitas fisik yang rendah (1)</span>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3">
                            <table class="border font">
                                <tr>
                                    <td colspan="3">
                                        <b>
                                            12. IDENTIFIKASI FAILURE TO THRIVE
                                        </b>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <input type="checkbox"
                                            {{ isset($data['CBPenurunanbbIFTT']) && $data['CBPenurunanbbIFTT'] == 'Penurunan berat badan >5% dari berat badan awal (1)' ? 'checked' : '' }} />
                                        <span>Penurunan berat badan <span class="logo">></span>5% dari berat badan
                                            awal (1)</span>
                                    </td>
                                    <td>
                                        <input type="checkbox"
                                            {{ isset($data['CBifttSkor']) && $data['CBifttSkor'] == 'Tidak (<4)' ? 'checked' : '' }} />
                                        <span>Tidak (<span class="logo">
                                                << /span>4)
                                            </span>
                                    </td>
                                    <td>
                                        <input type="checkbox"
                                            {{ isset($data['CBifttSkor']) && $data['CBifttSkor'] == 'FailureTo Thrive (4)' ? 'checked' : '' }} />
                                        <span>FailureTo Thrive (4)</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="3">
                                        <input type="checkbox"
                                            {{ isset($data['CBPenurunanNafsuIFTT']) && $data['CBPenurunanNafsuIFTT'] == 'Penurunan nafsu makan (1)' ? 'checked' : '' }} />
                                        <span>Penurunan nafsu makan (1)</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="3">
                                        <input type="checkbox"
                                            {{ isset($data['CBMalnutrisiIFTT']) && $data['CBMalnutrisiIFTT'] == 'Malnutrisi (1)' ? 'checked' : '' }} />
                                        <span>Malnutrisi (1)</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="3">
                                        <input type="checkbox"
                                            {{ isset($data['CBImobilitasIFTT']) && $data['CBImobilitasIFTT'] == 'Imobilitas (1)' ? 'checked' : '' }} />
                                        <span>Imobilitas (1)</span>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3">
                            <table class="border font">
                                <tr>
                                    <td colspan="3">
                                        <b>
                                            13. IDENTIFIKASI RISIKO FRAKTUR (FRAX)
                                        </b>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <span>
                                            1. Usia
                                        </span>
                                    </td>
                                    <td colspan="2">
                                        {{ isset($data['TBUsiaIRF']) ? $data['TBUsiaIRF'] : '' }}
                                        <span>Tahun</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <span>
                                            2. Jenis kelamin
                                        </span>
                                    </td>
                                    <td>
                                        <input type="checkbox"
                                            {{ isset($data['CBjenisKelaminIRF']) && $data['CBjenisKelaminIRF'] == 'Pria' ? 'checked' : '' }} />
                                        <span>Pria</span>
                                    </td>
                                    <td>
                                        <input type="checkbox"
                                            {{ isset($data['CBjenisKelaminIRF']) && $data['CBjenisKelaminIRF'] == 'Wanita' ? 'checked' : '' }} />
                                        <span>Wanita</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <span>
                                            3. Berat badan
                                        </span>
                                    </td>
                                    <td colspan="2">
                                        {{ isset($data['TBberatBadanIRF']) ? $data['TBberatBadanIRF'] : '' }}
                                        <span>Kg</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <span>
                                            4. Tinggi badan
                                        </span>
                                    </td>
                                    <td colspan="2">
                                        {{ isset($data['TBtinggiBadanIRF']) ? $data['TBtinggiBadanIRF'] : '' }}
                                        <span>cm</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <span>
                                            5. Riwayat patah tulang
                                        </span>
                                    </td>
                                    <td>
                                        <input type="checkbox"
                                            {{ isset($data['CBriwayatPatahTulangIRF']) && $data['CBriwayatPatahTulangIRF'] == 'Ya' ? 'checked' : '' }} />
                                        <span>Ya</span>
                                    </td>
                                    <td>
                                        <input type="checkbox"
                                            {{ isset($data['CBriwayatPatahTulangIRF']) && $data['CBriwayatPatahTulangIRF'] == 'Tidak' ? 'checked' : '' }} />
                                        <span>Tidak</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <span>
                                            6. Riwayat patah tulang femur pada orang tua
                                        </span>
                                    </td>
                                    <td>
                                        <input type="checkbox"
                                            {{ isset($data['CBriwayatPatahTulangFemurIRF']) && $data['CBriwayatPatahTulangFemurIRF'] == 'Ya' ? 'checked' : '' }} />
                                        <span>Ya</span>
                                    </td>
                                    <td>
                                        <input type="checkbox"
                                            {{ isset($data['CBriwayatPatahTulangFemurIRF']) && $data['CBriwayatPatahTulangFemurIRF'] == 'Tidak' ? 'checked' : '' }} />
                                        <span>Tidak</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <span>
                                            7. Perokok
                                        </span>
                                    </td>
                                    <td>
                                        <input type="checkbox"
                                            {{ isset($data['CBperokokIRF']) && $data['CBperokokIRF'] == 'Ya' ? 'checked' : '' }} />
                                        <span>Ya</span>
                                    </td>
                                    <td>
                                        <input type="checkbox"
                                            {{ isset($data['CBperokokIRF']) && $data['CBperokokIRF'] == 'Tidak' ? 'checked' : '' }} />
                                        <span>Tidak</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <span>
                                            8. Glukokortikoid
                                        </span>
                                    </td>
                                    <td>
                                        <input type="checkbox"
                                            {{ isset($data['CBglukokortikoidIRF']) && $data['CBglukokortikoidIRF'] == 'Ya' ? 'checked' : '' }} />
                                        <span>Ya</span>
                                    </td>
                                    <td>
                                        <input type="checkbox"
                                            {{ isset($data['CBglukokortikoidIRF']) && $data['CBglukokortikoidIRF'] == 'Tidak' ? 'checked' : '' }} />
                                        <span>Tidak</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <span>
                                            9. Artritis rheumatoid
                                        </span>
                                    </td>
                                    <td>
                                        <input type="checkbox"
                                            {{ isset($data['CBartritisRheumatoidIRF']) && $data['CBartritisRheumatoidIRF'] == 'Ya' ? 'checked' : '' }} />
                                        <span>Ya</span>
                                    </td>
                                    <td>
                                        <input type="checkbox"
                                            {{ isset($data['CBartritisRheumatoidIRF']) && $data['CBartritisRheumatoidIRF'] == 'Tidak' ? 'checked' : '' }} />
                                        <span>Tidak</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <span>
                                            10. Osteoporosis sekunder
                                        </span>
                                    </td>
                                    <td>
                                        <input type="checkbox"
                                            {{ isset($data['CBOsteoporosisSekunderIRF']) && $data['CBOsteoporosisSekunderIRF'] == 'Ya' ? 'checked' : '' }} />
                                        <span>Ya</span>
                                    </td>
                                    <td>
                                        <input type="checkbox"
                                            {{ isset($data['CBOsteoporosisSekunderIRF']) && $data['CBOsteoporosisSekunderIRF'] == 'Tidak' ? 'checked' : '' }} />
                                        <span>Tidak</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <span>
                                            11. Alkohol 3 unit atau lebih per hari
                                        </span>
                                    </td>
                                    <td>
                                        <input type="checkbox"
                                            {{ isset($data['CBAlkohol3UnitIRF']) && $data['CBAlkohol3UnitIRF'] == 'Ya' ? 'checked' : '' }} />
                                        <span>Ya</span>
                                    </td>
                                    <td>
                                        <input type="checkbox"
                                            {{ isset($data['CBAlkohol3UnitIRF']) && $data['CBAlkohol3UnitIRF'] == 'Tidak' ? 'checked' : '' }} />
                                        <span>Tidak</span>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3">
                            <table class="border font">
                                <tr>
                                    <td colspan="3">
                                        <table>
                                            <tr>
                                                <td>
                                                    <fieldset>
                                                        <legend>Nilai FRAX</legend>
                                                        <table>
                                                            <tr>
                                                                <td>
                                                                    <span style="white-space: pre-line;">
                                                                        <b>Osteoporosis Mayor :</b>
                                                                        a. Risiko berat (<span
                                                                            class="logo">≥</span>20%)
                                                                        b. Risiko sedang (10-20%)
                                                                        c. Risiko ringan (&lt;10%)
                                                                    </span>
                                                                </td>
                                                                <td>
                                                                    <span style="white-space: pre-line;">
                                                                        <b>Hip fracture :</b>
                                                                        a. Risiko berat (<span
                                                                            class="logo">≥</span>3%)
                                                                        b. Risiko sedang (1,5-3%)
                                                                        c. Risiko ringan (&lt;1,5%)
                                                                    </span>
                                                                </td>
                                                            </tr>
                                                        </table>
                                                    </fieldset>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3">
                            <table class="border font">
                                <tr>
                                    <td colspan="3">
                                        <b>14. IMPAIRMENT LAINNYA</b>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <span>1. Impair of vision</span>
                                    </td>
                                    <td>
                                        <input type="checkbox"
                                            {{ isset($data['CBimpairOFvision']) && $data['CBimpairOFvision'] == 'Ya' ? 'checked' : '' }} />
                                        <span>Ya</span>
                                    </td>
                                    <td>
                                        <input type="checkbox"
                                            {{ isset($data['CBimpairOFvision']) && $data['CBimpairOFvision'] == 'Tidak' ? 'checked' : '' }} />
                                        <span>Tidak</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <span>2. Impair of hearing</span>
                                    </td>
                                    <td>
                                        <input type="checkbox"
                                            {{ isset($data['CBimpairOFhearing']) && $data['CBimpairOFhearing'] == 'Ya' ? 'checked' : '' }} />
                                        <span>Ya</span>
                                    </td>
                                    <td>
                                        <input type="checkbox"
                                            {{ isset($data['CBimpairOFhearing']) && $data['CBimpairOFhearing'] == 'Tidak' ? 'checked' : '' }} />
                                        <span>Tidak</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="3">
                                        <div>
                                            <span>Lain-lain</span>
                                        </div>
                                        {{ isset($data['TAlainlainIL']) ? $data['TAlainlainIL'] : '' }}
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                @endif
                @if (!isset($ruangan) || (stripos($ruangan, 'OBGYN') != false && $data['jenisObgyn'] == 'Obstetri'))
                    <tr>
                        <td colspan="2">
                            <table class="border font">
                                <tr>
                                    <td colspan="3">
                                        <table>
                                            <tr>
                                                <td>
                                                    <b>Status Lokalis</b>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <div class="image-container-3">
                                                        @if (isset($data['Gambar']))
                                                            <img src="{{ $data['Gambar'] }}" width="500px"
                                                                height="130px" alt="Gambar" class="image-top-2">
                                                        @endif
                                                        <img src="{{ 'img/obstetrifix.png' }}" width="500px"
                                                            height="150px" alt="Gambar Obstetri"
                                                            class="image-bottom">
                                                    </div>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                @endif
                @if (!isset($ruangan) || stripos($ruangan, 'ANESTESI') != false)
                    <tr>
                        <td colspan="2">
                            <table class="border font">
                                <tr>
                                    <td colspan="3">
                                        <table>
                                            <tr>
                                                <td colspan="2">
                                                    <b>Diagnostic Studies</b>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <div style="margin-bottom: 10px;">
                                                        <b>EKG</b>
                                                    </div>
                                                    {{ isset($data['ekgdiagnostic']) ? '' . $data['ekgdiagnostic'] : '' }}
                                                </td>
                                                <td>
                                                    <div style="margin-bottom: 10px;">
                                                        <b>HB/Hct/CBC</b>
                                                    </div>
                                                    {{ isset($data['hbdiagnostic']) ? '' . $data['hbdiagnostic'] : '' }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <div style="margin-bottom: 10px;">
                                                        <b>Pulmonary Studies</b>
                                                    </div>
                                                    {{ isset($data['pulmonarydiagnostic']) ? '' . $data['pulmonarydiagnostic'] : '' }}
                                                </td>
                                                <td>
                                                    <div style="margin-bottom: 10px;">
                                                        <b>Electrolit</b>
                                                    </div>
                                                    {{ isset($data['electrolitdiagnostic']) ? '' . $data['electrolitdiagnostic'] : '' }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <div style="margin-bottom: 10px;">
                                                        <b>X-ray</b>
                                                    </div>
                                                    {{ isset($data['xraydiagnostic']) ? '' . $data['xraydiagnostic'] : '' }}
                                                </td>
                                                <td>
                                                    <div style="margin-bottom: 10px;">
                                                        <b>Urinalisis</b>
                                                    </div>
                                                    {{ isset($data['urindiagnostic']) ? '' . $data['urindiagnostic'] : '' }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <div style="margin-bottom: 10px;">
                                                        <b>Lain-Lain</b>
                                                    </div>
                                                    {{ isset($data['lainlaindiagnostic']) ? '' . $data['lainlaindiagnostic'] : '' }}
                                                </td>
                                                <td>
                                                    <div style="margin-bottom: 10px;">
                                                        <b>Lain-Lain</b>
                                                    </div>
                                                    {{ isset($data['lainlainlaboratory']) ? '' . $data['lainlainlaboratory'] : '' }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <div style="margin-bottom: 10px;">
                                                        <b>Masalah / Diagnostik</b>
                                                    </div>
                                                    {{ isset($data['masalaahdiagnostik']) ? '' . $data['masalaahdiagnostik'] : '' }}
                                                </td>
                                                <td>
                                                    <div style="margin-bottom: 10px;">
                                                        <b>Premidikasi</b>
                                                    </div>
                                                    {{ isset($data['premidikasi']) ? '' . $data['premidikasi'] : '' }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <div style="margin-bottom: 10px;">
                                                        <b>Rencana Anastesi</b>
                                                    </div>
                                                    {{ isset($data['rencanaanastesi']) ? '' . $data['rencanaanastesi'] : '' }}
                                                </td>
                                                <td>
                                                    <div style="margin-bottom: 10px;">
                                                        <b>PS ASA</b>
                                                    </div>
                                                    {{ isset($data['psasa']) ? '' . $data['psasa'] : '' }}
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                @endif
                <!-- START:PSIKOLOGI -->
                @if (!isset($ruangan) || stripos($ruangan, 'PSIKOLOGI') != false)
                    <tr>
                        <td colspan="3">
                            <table class="border font">
                                <tr>
                                    <td colspan="4">
                                        <span>
                                            Status Lokalis
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="4">
                                        <b>A. DATA AWAL</b>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="4">
                                        <b>Rujukan</b>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="1">
                                        <input type="checkbox"
                                            {{ isset($data['kebrujukan']) && $data['kebrujukan'] == 'YA' ? 'checked' : '' }} />
                                        <span>Ya</span>
                                        <input type="checkbox"
                                            {{ isset($data['kebrujukan']) && $data['kebrujukan'] == 'TIDAK' ? 'checked' : '' }} />
                                        <span>Tidak</span>
                                    </td>
                                    <td colspan="3"></td>
                                </tr>
                                <tr>
                                    <td colspan="4">
                                        <div>
                                            <b>Dari</b>
                                        </div>
                                        {{ isset($data['TBKetRujukanDari']) ? $data['TBKetRujukanDari'] : '' }}
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3">
                            <table class="border font">
                                <tr>
                                    <td colspan="4">
                                        <b>
                                            B. STATUS PSIKOLOGIS
                                        </b>
                                        <div>
                                            <b>
                                                OBSERVASI
                                            </b>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div>
                                            <span>
                                                Penampilan :
                                            </span>
                                        </div>
                                        {{ isset($data['TApenampilanSP']) ? $data['TApenampilanSP'] : '' }}
                                    </td>
                                    <td>
                                        <div>
                                            <span>
                                                Ekspresi wajah :
                                            </span>
                                        </div>
                                        {{ isset($data['TAExpresiSP']) ? $data['TAExpresiSP'] : '' }}
                                    </td>
                                    <td>
                                        <div>
                                            <span>
                                                Perasaan (Mood) :
                                            </span>
                                        </div>
                                        {{ isset($data['TAperasaanSP']) ? $data['TAperasaanSP'] : '' }}
                                    </td>
                                    <td>
                                        <div>
                                            <span>
                                                Fungsi Umum :
                                            </span>
                                        </div>
                                        {{ isset($data['TAfungsiUmumSP']) ? $data['TAfungsiUmumSP'] : '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div>
                                            <span>
                                                Intelektual :
                                            </span>
                                        </div>
                                        {{ isset($data['TAintelektualSP']) ? $data['TAintelektualSP'] : '' }}
                                    </td>
                                    <td>
                                        <div>
                                            <span>
                                                Lain-lain :
                                            </span>
                                        </div>
                                        {{ isset($data['TAlainlainSP']) ? $data['TAlainlainSP'] : '' }}
                                    </td>
                                    <td>
                                        <div>
                                            <span>
                                                Isi Pikir :
                                            </span>
                                        </div>
                                        {{ isset($data['TAisiPikirSP']) ? $data['TAisiPikirSP'] : '' }}
                                    </td>
                                    <td>
                                        <div>
                                            <span>
                                                Afeksi :
                                            </span>
                                        </div>
                                        {{ isset($data['TAafeksiSP']) ? $data['TAafeksiSP'] : '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="4">
                                        <div>
                                            <b>
                                                C. TES PSIKOLOGIS
                                            </b>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div>
                                            <span>
                                                Tes Psikologi yang diberikan :
                                            </span>
                                        </div>
                                        {{ isset($data['TAtpydTP']) ? $data['TAtpydTP'] : '' }}
                                    </td>
                                    <td>
                                        <div>
                                            <span>
                                                Hasil Tes Psikologis :
                                            </span>
                                        </div>
                                        {{ isset($data['TAExpresiTP']) ? $data['TAExpresiTP'] : '' }}
                                    </td>
                                    <td>
                                        <div>
                                            <span>
                                                Target Intervensi :
                                            </span>
                                        </div>
                                        {{ isset($data['TAperasaanTP']) ? $data['TAperasaanTP'] : '' }}
                                    </td>
                                    <td>
                                        <div>
                                            <span>
                                                Intervensi yang dilakukan sekarang :
                                            </span>
                                        </div>
                                        {{ isset($data['TAfungsiUmumTP']) ? $data['TAfungsiUmumTP'] : '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="4">
                                        <div>
                                            <span>
                                                Intervensi Selanjutnya :
                                            </span>
                                        </div>
                                        {{ isset($data['TAintelektualTP']) ? $data['TAintelektualTP'] : '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="4">
                                        <b>
                                            E. PROSES PSIKOTERAPI / KONSELING
                                        </b>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="4">
                                        <span>
                                            {{ isset($data['TAppk']) ? $data['TAppk'] : '' }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="4">
                                        <b>
                                            F. PROGNOSIS
                                        </b>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="4">
                                        <span>
                                            {{ isset($data['TAprognosis']) ? $data['TAprognosis'] : '' }}
                                        </span>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3">
                            <table class="border font">
                                <tr>
                                    <td colspan="3">
                                        <b>
                                            G. TINDAK LANJUT
                                        </b>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div>
                                            <span>
                                                Pertemuan Selanjutnya :
                                            </span>
                                        </div>
                                        {{ isset($data['TAps']) ? $data['TAps'] : '' }}
                                    </td>
                                    <td>
                                        <div>
                                            <span>
                                                Dirujuk kepada :
                                            </span>
                                        </div>
                                        {{ isset($data['TAdk']) ? $data['TAdk'] : '' }}
                                    </td>
                                    <td>
                                        <div>
                                            <span>
                                                Diakhiri tanggal :
                                            </span>
                                        </div>
                                        {{ isset($data['DdiakhiriTanggal']) ? $data['DdiakhiriTanggal'] : '' }}
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                @endif
                <!-- END:PSIKOLOGI -->
                <!-- START:KESTRAD -->
                @if (!isset($ruangan) || stripos($ruangan, 'TRADISIONAL') != false)
                    <tr>
                        <td colspan="3">
                            <table class="border font">
                                <tr>
                                    <td colspan="2">
                                        <span>
                                            Pemeriksaan Fisik
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div>
                                            <b>Kepala :</b>
                                        </div>
                                        {{ isset($data['kepalakestrad']) ? $data['kepalakestrad'] : '' }}
                                    </td>
                                    <td>
                                        <div>
                                            <b>Jantung :</b>
                                        </div>
                                        {{ isset($data['jantungkestrad']) ? $data['jantungkestrad'] : '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div>
                                            <b>Mata :</b>
                                        </div>
                                        {{ isset($data['matakestrad']) ? $data['matakestrad'] : '' }}
                                    </td>
                                    <td>
                                        <div>
                                            <b>Paru :</b>
                                        </div>
                                        {{ isset($data['parukestrad']) ? $data['parukestrad'] : '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div>
                                            <b>THT :</b>
                                        </div>
                                        {{ isset($data['thtkestrad']) ? $data['thtkestrad'] : '' }}
                                    </td>
                                    <td>
                                        <div>
                                            <b>Perut :</b>
                                        </div>
                                        {{ isset($data['perutkestrad']) ? $data['perutkestrad'] : '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div>
                                            <b>Leher :</b>
                                        </div>
                                        {{ isset($data['leherkestrad']) ? $data['leherkestrad'] : '' }}
                                    </td>
                                    <td>
                                        <div>
                                            <b>Extremitas :</b>
                                        </div>
                                        {{ isset($data['extremitaskestrad']) ? $data['extremitaskestrad'] : '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div>
                                            <b>Thoraks :</b>
                                        </div>
                                        {{ isset($data['thorakskestrad']) ? $data['thorakskestrad'] : '' }}
                                    </td>
                                    <td>
                                        <div>
                                            <b>Lain :</b>
                                        </div>
                                        {{ isset($data['lainkestrad']) ? $data['lainkestrad'] : '' }}
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3">
                            <table class="border font">
                                <tr>
                                    <td>
                                        Status Lokalis
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="image-container-5">
                                            @if (isset($data['Gambar']))
                                                <img src="{{ $data['Gambar'] }}" width="300px" height="540px"
                                                    alt="Gambar" class="image-top-3">
                                            @endif
                                            <img src="{{ 'img/kestrad.png' }}" width="300px" height="540px"
                                                alt="Gambar Kesehatan Tradisional" class="image-bottom">
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                @endif
                <!-- END:KESTRAD -->
                <!-- START:MATA -->
                @if (!isset($ruangan) || stripos($ruangan, 'MATA') != false)
                    <tr>
                        <td colspan="3">
                            <table class="border font">
                                <tr>
                                    <td colspan="4">
                                        Status Lokalis
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="4">
                                        <b>
                                            Status Opthalmologi
                                        </b>
                                    </td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td class="fc">
                                        <b>
                                            UVCA
                                        </b>
                                    </td>
                                    <td></td>
                                    <td class="fc">
                                        <b>
                                            BCVA
                                        </b>
                                    </td>
                                </tr>
                                <tr>
                                    <td width="20%">
                                        <b>Visus Awal OD</b>
                                    </td>
                                    <td width="30%">
                                        {{ isset($data['visusawalodu']) ? $data['visusawalodu'] : '' }}
                                    </td>
                                    <td width="20%">
                                        <b>Visus Awal OD</b>
                                    </td>
                                    <td width="30%">
                                        {{ isset($data['visusawalodb']) ? $data['visusawalodb'] : '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <td width="20%">
                                        <b>Visus Awal OS</b>
                                    </td>
                                    <td width="30%">
                                        {{ isset($data['visusawalosu']) ? $data['visusawalosu'] : '' }}
                                    </td>
                                    <td width="20%">
                                        <b>Visus Awal OS</b>
                                    </td>
                                    <td width="30%">
                                        {{ isset($data['visusawalosb']) ? $data['visusawalosb'] : '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <td width="20%">
                                        <b>Kacamata OD</b>
                                    </td>
                                    <td width="30%">
                                        {{ isset($data['kacamataodu']) ? $data['kacamataodu'] : '' }}
                                    </td>
                                    <td width="20%">
                                        <b>Kacamata OD</b>
                                    </td>
                                    <td width="30%">
                                        {{ isset($data['kacamataodb']) ? $data['kacamataodb'] : '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <td width="20%">
                                        <b>Kacamata OS</b>
                                    </td>
                                    <td width="30%">
                                        {{ isset($data['kacamataosu']) ? $data['kacamataosu'] : '' }}
                                    </td>
                                    <td width="20%">
                                        <b>Kacamata OS</b>
                                    </td>
                                    <td width="30%">
                                        {{ isset($data['kacamataosb']) ? $data['kacamataosb'] : '' }}
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3">
                            <table class="border font">
                                <tr>
                                    <td colspan="3" class="fc">
                                        <b>Nystagmus</b>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="3" class="fc">
                                        <b>Posisi/Hirschberg</b>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="fr" width="40%">
                                        {{ isset($data['od']) ? $data['od'] : '' }}
                                    </td>
                                    <td class="fc" width="20%">
                                        <b>OD &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; OS</b>
                                    </td>
                                    <td class="fl" width="40%">
                                        {{ isset($data['os']) ? $data['os'] : '' }}
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3">
                            <table class="border font">
                                <tr>
                                    <td class="fc" width="36%">
                                        <b>Mata Kanan</b>
                                    </td>
                                    <td class="fc" width="30%">
                                    </td>
                                    <td class="fc" width="34%">
                                        <b>Mata Kiri</b>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="fc">
                                        <table>
                                            <tr>
                                                <td style="padding-bottom: 13px;">
                                                    <b>Palpebra :</b>
                                                </td>
                                                <td>
                                                    <span>{{ isset($data['palpebran']) ? $data['palpebran'] : '' }}</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding-bottom: 13px;">
                                                    <b>Konjungtiva :</b>
                                                </td>
                                                <td>
                                                    <span>{{ isset($data['konjungtivan']) ? $data['konjungtivan'] : '' }}</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding-bottom: 13px;">
                                                    <b>Kornea :</b>
                                                </td>
                                                <td>
                                                    <span>{{ isset($data['kornean']) ? $data['kornean'] : '' }}</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding-bottom: 13px;">
                                                    <b>Bilik Mata :</b>
                                                </td>
                                                <td>
                                                    <span>{{ isset($data['bilikmatan']) ? $data['bilikmatan'] : '' }}</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding-bottom: 13px;">
                                                    <b>Iris :</b>
                                                </td>
                                                <td>
                                                    <span>{{ isset($data['irisn']) ? $data['irisn'] : '' }}</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding-bottom: 13px;">
                                                    <b>Pupil :</b>
                                                </td>
                                                <td>
                                                    <span>{{ isset($data['pupiln']) ? $data['pupiln'] : '' }}</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding-bottom: 13px;">
                                                    <b>Lensa :</b>
                                                </td>
                                                <td>
                                                    <span>{{ isset($data['lensan']) ? $data['lensan'] : '' }}</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding-bottom: 13px;">
                                                    <b>Vitreus :</b>
                                                </td>
                                                <td>
                                                    <span>{{ isset($data['vitreusn']) ? $data['vitreusn'] : '' }}</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding-bottom: 13px;">
                                                    <b>Funduskopi :</b>
                                                </td>
                                                <td>
                                                    <span>{{ isset($data['funduskopin']) ? $data['funduskopin'] : '' }}</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding-bottom: 13px;">
                                                    <b>Schiotz :</b>
                                                </td>
                                                <td>
                                                    <span>{{ isset($data['schiotzn']) ? $data['schiotzn'] : '' }}</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding-bottom: 13px;">
                                                    <b>Aplanasi :</b>
                                                </td>
                                                <td>
                                                    <span>{{ isset($data['aplanasin']) ? $data['aplanasin'] : '' }}</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding-bottom: 13px;">
                                                    <b>NCT :</b>
                                                </td>
                                                <td>
                                                    <span>{{ isset($data['nctn']) ? $data['nctn'] : '' }}</span>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                    <td class="fc">
                                        <div class="image-container-6 fc">
                                            @if (isset($data['canvasmata']))
                                                <img src="{{ $data['canvasmata'] }}" width="380px"
                                                    height="640x" alt="canvasmata" class="image-top-3">
                                            @endif
                                            <img src="{{ 'img/matafix-mirror.png' }}" width="380px"
                                                height="640x" alt="Gambar Mata" class="image-bottom">
                                        </div>
                                    </td>
                                    <td>
                                        <table>
                                            <tr>
                                                <td style="padding-bottom: 13px;">
                                                    <b>Palpebra :</b>
                                                </td>
                                                <td>
                                                    <span>{{ isset($data['palpebrai']) ? $data['palpebrai'] : '' }}</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding-bottom: 13px;">
                                                    <b>Konjungtiva :</b>
                                                </td>
                                                <td>
                                                    <span>{{ isset($data['konjungtivai']) ? $data['konjungtivai'] : '' }}</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding-bottom: 13px;">
                                                    <b>Kornea :</b>
                                                </td>
                                                <td>
                                                    <span>{{ isset($data['korneai']) ? $data['korneai'] : '' }}</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding-bottom: 13px;">
                                                    <b>Bilik Mata :</b>
                                                </td>
                                                <td>
                                                    <span>{{ isset($data['bilikmatai']) ? $data['bilikmatai'] : '' }}</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding-bottom: 13px;">
                                                    <b>Iris :</b>
                                                </td>
                                                <td>
                                                    <span>{{ isset($data['irisi']) ? $data['irisi'] : '' }}</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding-bottom: 13px;">
                                                    <b>Pupil :</b>
                                                </td>
                                                <td>
                                                    <span>{{ isset($data['pupili']) ? $data['pupili'] : '' }}</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding-bottom: 13px;">
                                                    <b>Lensa :</b>
                                                </td>
                                                <td>
                                                    <span>{{ isset($data['lensai']) ? $data['lensai'] : '' }}</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding-bottom: 13px;">
                                                    <b>Vitreus :</b>
                                                </td>
                                                <td>
                                                    <span>{{ isset($data['vitreusi']) ? $data['vitreusi'] : '' }}</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding-bottom: 13px;">
                                                    <b>Funduskopi :</b>
                                                </td>
                                                <td>
                                                    <span>{{ isset($data['funduskopii']) ? $data['funduskopii'] : '' }}</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding-bottom: 13px;">
                                                    <b>Schiotz :</b>
                                                </td>
                                                <td>
                                                    <span>{{ isset($data['schiotzi']) ? $data['schiotzi'] : '' }}</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding-bottom: 13px;">
                                                    <b>Aplanasi :</b>
                                                </td>
                                                <td>
                                                    <span>{{ isset($data['aplanasii']) ? $data['aplanasii'] : '' }}</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding-bottom: 13px;">
                                                    <b>NCT :</b>
                                                </td>
                                                <td>
                                                    <span>{{ isset($data['ncti']) ? $data['ncti'] : '' }}</span>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3">
                            <table class="border font">
                                <tr>
                                    <td width="20%">
                                        <b>Test Anel</b>
                                    </td>
                                    <td width="80%">
                                        {{ isset($data['testanel']) ? $data['testanel'] : '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <td width="20%">
                                        <b>Test Buta Warna</b>
                                    </td>
                                    <td width="80%">
                                        {{ isset($data['testbutawarna']) ? $data['testbutawarna'] : '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <td width="20%">
                                        <b>Test Fluoresin</b>
                                    </td>
                                    <td width="80%">
                                        {{ isset($data['testfluoresin']) ? $data['testfluoresin'] : '' }}
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3">
                            <table class="border font">
                                <tr>
                                    <td class="fc">
                                        <input type="checkbox"
                                            {{ isset($data['optionsmata']) && $data['optionsmata'] == 'jauh' ? 'checked' : '' }} /><span>Jauh</span>&nbsp;
                                        <input type="checkbox"
                                            {{ isset($data['optionsmata']) && $data['optionsmata'] == 'dekat' ? 'checked' : '' }} /><span>Dekat</span>&nbsp;
                                        <input type="checkbox"
                                            {{ isset($data['optionsmata']) && $data['optionsmata'] == 'progresif' ? 'checked' : '' }} /><span>Progresif</span>&nbsp;
                                        <input type="checkbox"
                                            {{ isset($data['optionsmata']) && $data['optionsmata'] == 'tidakada' ? 'checked' : '' }} /><span>Tidak
                                            ada</span>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3">
                            <table class="border font">
                                <tr>
                                    <td width="20%">
                                        <b>Resep Kacamata</b>
                                    </td>
                                    <td width="30%">
                                        <b>R/</b>
                                    </td>
                                    <td width="20%">

                                    </td>
                                    <td width="30%">

                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2">
                                        <b>OD</b>
                                    </td>
                                    <td colspan="2">
                                        <b>OS</b>
                                    </td>
                                </tr>
                                <tr>
                                    <td width="20%" style="padding-bottom:5px;">
                                        <b>Spheris</b>
                                    </td>
                                    <td width="30%">
                                        {{ isset($data['spherisd']) ? $data['spherisd'] : '' }}
                                    </td>
                                    <td width="20%" style="padding-bottom:5px;">
                                        <b>Spheris</b>
                                    </td>
                                    <td width="30%">
                                        {{ isset($data['spheriss']) ? $data['spheriss'] : '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <td width="20%" style="padding-bottom:5px;">
                                        <b>Cylinder</b>
                                    </td>
                                    <td width="30%">
                                        {{ isset($data['cylinderd']) ? $data['cylinderd'] : '' }}
                                    </td>
                                    <td width="20%" style="padding-bottom:5px;">
                                        <b>Cylinder</b>
                                    </td>
                                    <td width="30%">
                                        {{ isset($data['cylinders']) ? $data['cylinders'] : '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <td width="20%" style="padding-bottom:5px;">
                                        <b>Prisma</b>
                                    </td>
                                    <td width="30%">
                                        {{ isset($data['prismad']) ? $data['prismad'] : '' }}
                                    </td>
                                    <td width="20%" style="padding-bottom:5px;">
                                        <b>Prisma</b>
                                    </td>
                                    <td width="30%">
                                        {{ isset($data['prismas']) ? $data['prismas'] : '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <td width="20%" style="padding-bottom:5px;">
                                        <b>Axis</b>
                                    </td>
                                    <td width="30%">
                                        {{ isset($data['axisd']) ? $data['axisd'] : '' }}
                                    </td>
                                    <td width="20%" style="padding-bottom:5px;">
                                        <b>Axis</b>
                                    </td>
                                    <td width="30%">
                                        {{ isset($data['axiss']) ? $data['axiss'] : '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="1">
                                        <b>Addition</b>
                                    </td>
                                    <td colspan="3">
                                        {{ isset($data['addition']) ? $data['addition'] : '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="1">
                                        <b>Pupil Distance</b>
                                    </td>
                                    <td colspan="3">
                                        {{ isset($data['pupil']) ? $data['pupil'] : '' }} mm
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                @endif
                <!-- END:MATA -->
                <!-- START:GIZI -->
                @if (!isset($ruangan) || stripos($ruangan, 'GIZI') != false)
                    <tr>
                        <td colspan="3">
                            <table class="border font">
                                <tr>
                                    <td colspan="2">
                                        Status Lokalis
                                    </td>
                                </tr>
                                <tr>
                                    <td class="padding-y">
                                        <div><span>Kehilangan BB 6 BI terakhir :</span></div>
                                        {{ isset($data['kehilanganbbGizi']) ? getYesNoLabel($data['kehilanganbbGizi'], $kehilanganbbGizi) : '' }}
                                    </td>
                                    <td class="padding-y">
                                        <div><span>Asupan Makanan 5 Hari Terakhir :</span></div>
                                        {{ isset($data['asupanterakhirGizi']) ? getYesNoLabel($data['asupanterakhirGizi'], $asupanterakhirGizi) : '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <td class="padding-y">
                                        <div><span>Gangguan Saluran cerna persisten 2 Minggu terakhir :</span></div>
                                        {{ isset($data['gangguansaluranGizi']) ? getYesNoLabel($data['gangguansaluranGizi'], $gangguansaluranGizi) : '' }}
                                    </td>
                                    <td class="padding-y">
                                        <div><span>Kapasitas Fungsional :</span></div>
                                        {{ isset($data['kapasitasfungsiGizi']) ? getYesNoLabel($data['kapasitasfungsiGizi'], $kapasitasfungsiGizi) : '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <td class="padding-y">
                                        <div><span>Penyakit Stress Metabolik :</span></div>
                                        {{ isset($data['stressMetabolikGizi']) ? getYesNoLabel($data['stressMetabolikGizi'], $stressMetabolikGizi) : '' }}
                                    </td>
                                    <td class="padding-y">
                                        <div><span>Pemeriksaan Fisik : &lt; lemak subkutan & muscle wasting :</span>
                                        </div>
                                        {{ isset($data['pemeriksafisikkGizi']) ? getYesNoLabel($data['pemeriksafisikkGizi'], $pemeriksafisikkGizi) : '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <td class="padding-y">
                                        <div><span>SGA :</span></div>
                                        {{ isset($data['sgaGizi']) ? getYesNoLabel($data['sgaGizi'], $sgaGizi) : '' }}
                                    </td>
                                    <td class="padding-y">
                                        <div><span>IMT(kg/m2) :</span></div>
                                        {{ isset($data['imtGizi']) ? getYesNoLabel($data['imtGizi'], $imtGizi) : '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <td class="padding-y">
                                        <div><span>Kadar Albumin (g/dl) :</span></div>
                                        {{ isset($data['albuminGizi']) ? getYesNoLabel($data['albuminGizi'], $albuminGizi) : '' }}
                                    </td>
                                    <td class="padding-y">
                                        <div><span>TLC :</span></div>
                                        {{ isset($data['tclGizi']) ? getYesNoLabel($data['tclGizi'], $tclGizi) : '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <td class="padding-y">
                                        <div><span>Total Score :</span></div>
                                        {{ isset($data['totalScoreGizi']) ? $data['totalScoreGizi'] : '' }}
                                    </td>
                                    <td class="padding-y">
                                        <div><span>Tingkat Resiko Malnutrisi :</span></div>
                                        {{ isset($data['malnutrisiGizi']) ? $data['malnutrisiGizi'] : '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2" class="padding-y">
                                        <b>Kondisi</b>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2" class="padding-y">
                                        <div><span>Diagnosis Klinis :</span></div>
                                        {{ isset($data['diagnosisGizi']) ? $data['diagnosisGizi'] : '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2" class="padding-y">
                                        <div><span>Status Metabolisme :</span></div>
                                        {{ isset($data['metabolismeGizi']) ? $data['metabolismeGizi'] : '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2" class="padding-y">
                                        <div><span>Status Saluran Cerna :</span></div>
                                        {{ isset($data['saluranGizi']) ? $data['saluranGizi'] : '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2" class="padding-y">
                                        <div><span>Resiko Malnutrisi :</span></div>
                                        {{ isset($data['resikoMalnutrisiGizi']) ? getYesNoLabel($data['resikoMalnutrisiGizi'], $resikoMalnutrisiGizi) : '' }}
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                @endif
                <!-- END:GIZI -->
                <!-- START:VCT -->
                @if (!isset($ruangan) || stripos($ruangan, 'VCT') != false)
                    <tr>
                        <td colspan="3">
                            <table class="border font">
                                <tr>
                                    <td colspan="4" class="fc">
                                        <div class="image-container-7 fc">
                                            @if (isset($data['Gambar']))
                                                <img src="{{ $data['Gambar'] }}" width="250px" height="250px"
                                                    alt="Gambar" class="image-top-3">
                                            @endif
                                            <img src="{{ 'img/paru-paru.jpeg' }}" width="250px" height="250px"
                                                alt="Gambar Paru-Paru" class="image-bottom">
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="4">
                                        <b>Keluhan</b>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <input type="checkbox"
                                            {{ (isset($data['kondisiVCT']) && $data['kondisiVCT'] == 'BB Turun') || (isset($data['kondisiVCT0']) && $data['kondisiVCT0'] == 'BB Turun') ? 'checked' : '' }} /><span>BB
                                            Turun</span>
                                    </td>
                                    <td>
                                        <input type="checkbox"
                                            {{ (isset($data['kondisiVCT']) && $data['kondisiVCT'] == 'Diare') || (isset($data['kondisiVCT1']) && $data['kondisiVCT1'] == 'Diare') ? 'checked' : '' }} /><span>Diare</span>
                                    </td>
                                    <td>
                                        <input type="checkbox"
                                            {{ (isset($data['kondisiVCT']) && $data['kondisiVCT'] == 'Badan Panas') || (isset($data['kondisiVCT2']) && $data['kondisiVCT2'] == 'Badan Panas') ? 'checked' : '' }} /><span>Badan
                                            Panas</span>
                                    </td>
                                    <td>
                                        <input type="checkbox"
                                            {{ (isset($data['kondisiVCT']) && $data['kondisiVCT'] == 'Jamur dimulut') || (isset($data['kondisiVCT3']) && $data['kondisiVCT3'] == 'Jamur dimulut') ? 'checked' : '' }} /><span>Jamur
                                            dimulut</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <input type="checkbox"
                                            {{ (isset($data['kondisiVCT']) && $data['kondisiVCT'] == 'Sulit menelan') || (isset($data['kondisiVCT4']) && $data['kondisiVCT4'] == 'Sulit menelan') ? 'checked' : '' }} />
                                        <span>Sulit menelan</span>
                                    </td>
                                    <td>
                                        <input type="checkbox"
                                            {{ (isset($data['kondisiVCT']) && $data['kondisiVCT'] == 'Batuk') || (isset($data['kondisiVCT5']) && $data['kondisiVCT5'] == 'Batuk') ? 'checked' : '' }} />
                                        <span>Batuk</span>
                                    </td>
                                    <td>
                                        <input type="checkbox"
                                            {{ (isset($data['kondisiVCT']) && $data['kondisiVCT'] == 'Gatal pada kulit') || (isset($data['kondisiVCT6']) && $data['kondisiVCT6'] == 'Gatal pada kulit') ? 'checked' : '' }} />
                                        <span>Gatal pada kulit</span>
                                    </td>
                                    <td>
                                        <input type="checkbox"
                                            {{ (isset($data['kondisiVCT']) && $data['kondisiVCT'] == 'Kelainan kulit') || (isset($data['kondisiVCT7']) && $data['kondisiVCT7'] == 'Kelainan kulit') ? 'checked' : '' }} />
                                        <span>Kelainan kulit</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <input type="checkbox"
                                            {{ (isset($data['kondisiVCT']) && $data['kondisiVCT'] == 'Gangguan visus') || (isset($data['kondisiVCT8']) && $data['kondisiVCT8'] == 'Gangguan visus') ? 'checked' : '' }} />
                                        <span>Gangguan visus</span>
                                    </td>
                                    <td>
                                        <input type="checkbox"
                                            {{ (isset($data['kondisiVCT']) && $data['kondisiVCT'] == 'Herpes simplex') || (isset($data['kondisiVCT9']) && $data['kondisiVCT9'] == 'Herpes simplex') ? 'checked' : '' }} />
                                        <span>Herpes simplex</span>
                                    </td>
                                    <td>
                                        <input type="checkbox"
                                            {{ (isset($data['kondisiVCT']) && $data['kondisiVCT'] == 'Herpes zoster') || (isset($data['kondisiVCT10']) && $data['kondisiVCT10'] == 'Herpes zoster') ? 'checked' : '' }} />
                                        <span>Herpes zoster</span>
                                    </td>
                                    <td>
                                        <input type="checkbox"
                                            {{ (isset($data['kondisiVCT']) && $data['kondisiVCT'] == 'ISPA berulang') || (isset($data['kondisiVCT11']) && $data['kondisiVCT11'] == 'ISPA berulang') ? 'checked' : '' }} />
                                        <span>ISPA berulang</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <input type="checkbox"
                                            {{ (isset($data['kondisiVCT']) && $data['kondisiVCT'] == 'Nyeri kepala') || (isset($data['kondisiVCT12']) && $data['kondisiVCT12'] == 'Nyeri kepala') ? 'checked' : '' }} />
                                        <span>Nyeri kepala</span>
                                    </td>
                                    <td>
                                        <input type="checkbox"
                                            {{ (isset($data['kondisiVCT']) && $data['kondisiVCT'] == 'TB paru') || (isset($data['kondisiVCT13']) && $data['kondisiVCT13'] == 'TB paru') ? 'checked' : '' }} />
                                        <span>TB paru</span>
                                    </td>
                                    <td>
                                        <input type="checkbox"
                                            {{ (isset($data['kondisiVCT']) && $data['kondisiVCT'] == 'IMS') || (isset($data['kondisiVCT14']) && $data['kondisiVCT14'] == 'IMS') ? 'checked' : '' }} />
                                        <span>IMS</span>
                                    </td>
                                    <td>
                                        <input type="checkbox"
                                            {{ (isset($data['kondisiVCT']) && $data['kondisiVCT'] == 'Infeksi paru non TB') || (isset($data['kondisiVCT15']) && $data['kondisiVCT15'] == 'Infeksi paru non TB') ? 'checked' : '' }} />
                                        <span>Infeksi paru non TB</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <input type="checkbox"
                                            {{ (isset($data['kondisiVCT']) && $data['kondisiVCT'] == 'Kesadaran menurun') || (isset($data['kondisiVCT16']) && $data['kondisiVCT16'] == 'Kesadaran menurun') ? 'checked' : '' }} />
                                        <span>Kesadaran menurun</span>
                                    </td>
                                    <td colspan="3">
                                        <input type="checkbox"
                                            {{ (isset($data['kondisiVCT']) && $data['kondisiVCT'] == 'Pembesaran KGB') || (isset($data['kondisiVCT17']) && $data['kondisiVCT17'] == 'Pembesaran KGB') ? 'checked' : '' }} />
                                        <span>Pembesaran KGB</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2" class="padding-y">
                                        <div><span>Kebutuhan Lain</span></div>
                                        {{ isset($data['kebutuhanlainVCT']) ? $data['kebutuhanlainVCT'] : '' }}
                                    </td>
                                    <td colspan="2" class="padding-y">
                                        <div><span>Lokasi KGB</span></div>
                                        {{ isset($data['lokasiKGBVCT']) ? $data['lokasiKGBVCT'] : '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="4">
                                        <b>AIDS Defening illnes</b>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <input type="checkbox"
                                            {{ (isset($data['aidsVCT']) && $data['aidsVCT'] == 'Lymphoma Burkitt`s') || (isset($data['aidsVCT0']) && $data['aidsVCT0'] == 'Lymphoma Burkitt`s') ? 'checked' : '' }} /><span>Lymphoma
                                            Burkitt`s</span>
                                    </td>
                                    <td>
                                        <input type="checkbox"
                                            {{ (isset($data['aidsVCT']) && $data['aidsVCT'] == 'Cocodiodomycosis') || (isset($data['aidsVCT1']) && $data['aidsVCT1'] == 'Cocodiodomycosis') ? 'checked' : '' }} /><span>Cocodiodomycosis</span>
                                    </td>
                                    <td>
                                        <input type="checkbox"
                                            {{ (isset($data['aidsVCT']) && $data['aidsVCT'] == 'Cytomegalovirus') || (isset($data['aidsVCT2']) && $data['aidsVCT2'] == 'Cytomegalovirus') ? 'checked' : '' }} /><span>Cytomegalovirus</span>
                                    </td>
                                    <td>
                                        <input type="checkbox"
                                            {{ (isset($data['aidsVCT']) && $data['aidsVCT'] == 'Herpes simplex') || (isset($data['aidsVCT3']) && $data['aidsVCT3'] == 'Herpes simplex') ? 'checked' : '' }} /><span>Herpes
                                            simplex</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <input type="checkbox"
                                            {{ (isset($data['aidsVCT']) && $data['aidsVCT'] == 'Kandidiasis Esofagus') || (isset($data['aidsVCT4']) && $data['aidsVCT4'] == 'Kandidiasis Esofagus') ? 'checked' : '' }} /><span>Kandidiasis
                                            Esofagus</span>
                                    </td>
                                    <td>
                                        <input type="checkbox"
                                            {{ (isset($data['aidsVCT']) && $data['aidsVCT'] == 'Cryptococcosis') || (isset($data['aidsVCT5']) && $data['aidsVCT5'] == 'Cryptococcosis') ? 'checked' : '' }} /><span>Cryptococcosis</span>
                                    </td>
                                    <td>
                                        <input type="checkbox"
                                            {{ (isset($data['aidsVCT']) && $data['aidsVCT'] == 'Sarkoma Kaposi') || (isset($data['aidsVCT6']) && $data['aidsVCT6'] == 'Sarkoma Kaposi') ? 'checked' : '' }} /><span>Sarkoma
                                            Kaposi</span>
                                    </td>
                                    <td>
                                        <input type="checkbox"
                                            {{ (isset($data['aidsVCT']) && $data['aidsVCT'] == 'Histoplasmosis') || (isset($data['aidsVCT7']) && $data['aidsVCT7'] == 'Histoplasmosis') ? 'checked' : '' }} /><span>Histoplasmosis</span>
                                    </td>
                                </tr>

                                <tr>
                                    <td>
                                        <input type="checkbox"
                                            {{ (isset($data['aidsVCT']) && $data['aidsVCT'] == 'Ca Cervix invasif') || (isset($data['aidsVCT8']) && $data['aidsVCT8'] == 'Ca Cervix invasif') ? 'checked' : '' }} /><span>Ca
                                            Cervix invasif</span>
                                    </td>
                                    <td>
                                        <input type="checkbox"
                                            {{ (isset($data['aidsVCT']) && $data['aidsVCT'] == 'Cryptosporidiosis') || (isset($data['aidsVCT9']) && $data['aidsVCT9'] == 'Cryptosporidiosis') ? 'checked' : '' }} /><span>Cryptosporidiosis</span>
                                    </td>
                                    <td>
                                        <input type="checkbox"
                                            {{ (isset($data['aidsVCT']) && $data['aidsVCT'] == 'HIV encephalopathy') || (isset($data['aidsVCT10']) && $data['aidsVCT10'] == 'HIV encephalopathy') ? 'checked' : '' }} /><span>HIV
                                            encephalopathy</span>
                                    </td>
                                    <td>
                                        <input type="checkbox"
                                            {{ (isset($data['aidsVCT']) && $data['aidsVCT'] == 'Isosporiasis') || (isset($data['aidsVCT11']) && $data['aidsVCT11'] == 'Isosporiasis') ? 'checked' : '' }} /><span>Isosporiasis</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <input type="checkbox"
                                            {{ (isset($data['aidsVCT']) && $data['aidsVCT'] == 'HIV Wasting syndrome') || (isset($data['aidsVCT12']) && $data['aidsVCT12'] == 'HIV Wasting syndrome') ? 'checked' : '' }} /><span>HIV
                                            Wasting syndrome</span>
                                    </td>
                                    <td>
                                        <input type="checkbox"
                                            {{ (isset($data['aidsVCT']) && $data['aidsVCT'] == 'Penicilliosis') || (isset($data['aidsVCT13']) && $data['aidsVCT13'] == 'Penicilliosis') ? 'checked' : '' }} /><span>Penicilliosis</span>
                                    </td>
                                    <td>
                                        <input type="checkbox"
                                            {{ (isset($data['aidsVCT']) && $data['aidsVCT'] == 'Reccurent pneumonia') || (isset($data['aidsVCT14']) && $data['aidsVCT14'] == 'Reccurent pneumonia') ? 'checked' : '' }} /><span>Reccurent
                                            pneumonia</span>
                                    </td>
                                    <td>
                                        <input type="checkbox"
                                            {{ (isset($data['aidsVCT']) && $data['aidsVCT'] == 'Lymphoma Otak') || (isset($data['aidsVCT15']) && $data['aidsVCT15'] == 'Lymphoma Otak') ? 'checked' : '' }} /><span>Lymphoma
                                            Otak</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <input type="checkbox"
                                            {{ (isset($data['aidsVCT']) && $data['aidsVCT'] == 'Salmonella septicemia') || (isset($data['aidsVCT16']) && $data['aidsVCT16'] == 'Salmonella septicemia') ? 'checked' : '' }} /><span>Salmonella
                                            septicemia</span>
                                    </td>
                                    <td>
                                        <input type="checkbox"
                                            {{ (isset($data['aidsVCT']) && $data['aidsVCT'] == 'PCP') || (isset($data['aidsVCT17']) && $data['aidsVCT17'] == 'PCP') ? 'checked' : '' }} /><span>PCP</span>
                                    </td>
                                    <td>
                                        <input type="checkbox"
                                            {{ (isset($data['aidsVCT']) && $data['aidsVCT'] == 'M. Tuberculosis complex') || (isset($data['aidsVCT18']) && $data['aidsVCT18'] == 'M. Tuberculosis complex') ? 'checked' : '' }} /><span>M.
                                            Tuberculosis complex</span>
                                    </td>
                                    <td>
                                        <input type="checkbox"
                                            {{ (isset($data['aidsVCT']) && $data['aidsVCT'] == 'Toxoplasmosis') || (isset($data['aidsVCT19']) && $data['aidsVCT19'] == 'Toxoplasmosis') ? 'checked' : '' }} /><span>Toxoplasmosis</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <input type="checkbox"
                                            {{ (isset($data['aidsVCT']) && $data['aidsVCT'] == 'Lymphoma Immunoblaastic') || (isset($data['aidsVCT20']) && $data['aidsVCT20'] == 'Lymphoma Immunoblaastic') ? 'checked' : '' }} /><span>Lymphoma
                                            Immunoblaastic</span>
                                    </td>
                                    <td>
                                        <input type="checkbox"
                                            {{ (isset($data['aidsVCT']) && $data['aidsVCT'] == 'Cytomegalovirus retintis') || (isset($data['aidsVCT21']) && $data['aidsVCT21'] == 'Cytomegalovirus retintis') ? 'checked' : '' }} /><span>Cytomegalovirus
                                            retintis</span>
                                    </td>
                                    <td>
                                        <input type="checkbox"
                                            {{ (isset($data['aidsVCT']) && $data['aidsVCT'] == 'Kandidiasis trakea/broncus/paru') || (isset($data['aidsVCT22']) && $data['aidsVCT22'] == 'Kandidiasis trakea/broncus/paru') ? 'checked' : '' }} /><span>Kandidiasis
                                            trakea/broncus/paru</span>
                                    </td>
                                    <td>
                                        <input type="checkbox"
                                            {{ (isset($data['aidsVCT']) && $data['aidsVCT'] == 'Progressive Multifocal Leukeuncephalopathy') || (isset($data['aidsVCT23']) && $data['aidsVCT23'] == 'Progressive Multifocal Leukeuncephalopathy') ? 'checked' : '' }} /><span>Progressive
                                            Multifocal Leukeuncephalopathy</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="4">
                                        <input type="checkbox"
                                            {{ (isset($data['aidsVCT']) && $data['aidsVCT'] == 'Mycobacterium non TBC') || (isset($data['aidsVCT24']) && $data['aidsVCT24'] == 'Mycobacterium non TBC') ? 'checked' : '' }} /><span>Mycobacterium
                                            non TBC</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="4">
                                        <b>Terapi Infeksi Opportunistik</b>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2" class="padding-y">
                                        <div><span>OAT</span></div>
                                        {{ isset($data['terapiOatVCT']) ? $data['terapiOatVCT'] : '' }}
                                    </td>
                                    <td colspan="2" class="padding-y">
                                        <div><span>Antitoxoplasmosis</span></div>
                                        {{ isset($data['antitoxopVCT']) ? $data['antitoxopVCT'] : '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2" class="padding-y">
                                        <div><span>Terapi PCP</span></div>
                                        {{ isset($data['pcpVCT']) ? $data['pcpVCT'] : '' }}
                                    </td>
                                    <td colspan="2" class="padding-y">
                                        <div><span>Anti jamur</span></div>
                                        {{ isset($data['jamurVCT']) ? $data['jamurVCT'] : '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="4" class="padding-y">
                                        <div><span>Terapi lain</span></div>
                                        {{ isset($data['terapilainVCT']) ? $data['terapilainVCT'] : '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="4">
                                        <b>Obat Profilaksis Infeksi Oportunistik</b>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <input type="checkbox"
                                            {{ isset($data['profilaksisVCT']) && $data['profilaksisVCT'] == 'INH Primer' ? 'checked' : '' }} /><span>INH
                                            Primer</span>
                                    </td>
                                    <td>
                                        <input type="checkbox"
                                            {{ isset($data['profilaksisVCT']) && $data['profilaksisVCT'] == 'INH Sekunder' ? 'checked' : '' }} /><span>INH
                                            Sekunder</span>
                                    </td>
                                    <td>
                                        <input type="checkbox"
                                            {{ isset($data['profilaksisVCT']) && $data['profilaksisVCT'] == 'Fansidar Primer' ? 'checked' : '' }} /><span>Fansidar
                                            Primer</span>
                                    </td>
                                    <td>
                                        <input type="checkbox"
                                            {{ isset($data['profilaksisVCT']) && $data['profilaksisVCT'] == 'Fansidar Sekunder' ? 'checked' : '' }} /><span>Fansidar
                                            Sekunder</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <input type="checkbox"
                                            {{ isset($data['profilaksisVCT']) && $data['profilaksisVCT'] == 'Klindamisin' ? 'checked' : '' }} /><span>Klindamisin</span>
                                    </td>
                                    <td>
                                        <input type="checkbox"
                                            {{ isset($data['profilaksisVCT']) && $data['profilaksisVCT'] == 'Fluconazol Primer' ? 'checked' : '' }} /><span>Fluconazol
                                            Primer</span>
                                    </td>
                                    <td>
                                        <input type="checkbox"
                                            {{ isset($data['profilaksisVCT']) && $data['profilaksisVCT'] == 'Fluconazol Sekunder' ? 'checked' : '' }} /><span>Fluconazol
                                            Sekunder</span>
                                    </td>
                                    <td>
                                        <input type="checkbox"
                                            {{ isset($data['profilaksisVCT']) && $data['profilaksisVCT'] == 'Cotrimoxazol Primer' ? 'checked' : '' }} /><span>Cotrimoxazol
                                            Primer</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <input type="checkbox"
                                            {{ isset($data['profilaksisVCT']) && $data['profilaksisVCT'] == 'Cotrimoxazol Sekunder' ? 'checked' : '' }} /><span>Cotrimoxazol
                                            Sekunder</span>
                                    </td>
                                    <td colspan="3">
                                        <input type="checkbox"
                                            {{ isset($data['profilaksisVCT']) && $data['profilaksisVCT'] == 'Pirimetamin' ? 'checked' : '' }} /><span>Pirimetamin</span>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3">
                            <table class="border font">
                                <tr>
                                    <td colspan="6">
                                        <b>Terapi Infeksi Opportunistik</b>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="6" class="padding-y">
                                        <div><span>Obat lain</span></div>
                                        {{ isset($data['obatLainVCT']) ? $data['obatLainVCT'] : '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="6" class="padding-y">
                                        <div><span>Keterangan lain-lain</span></div>
                                        {{ isset($data['keteranganLainVCT']) ? $data['keteranganLainVCT'] : '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="6" class="padding-y">
                                        <div><span>Dokter Pemeriksa</span></div>
                                        {{ isset($data['dokterpemeriksaVCT']) ? $data['dokterpemeriksaVCT'] : '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="6">
                                        <b>Riwayat Terapi Antiretroviral</b>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="6">
                                        <div>
                                            <span>Pernah menerima ART ?</span>
                                        </div>
                                        <input type="checkbox"
                                            {{ isset($data['profilaksisVCT']) && $data['profilaksisVCT'] == 'Ya' ? 'checked' : '' }} /><span>Ya</span>&nbsp;&nbsp;
                                        <input type="checkbox"
                                            {{ isset($data['profilaksisVCT']) && $data['profilaksisVCT'] == 'Tidak' ? 'checked' : '' }} /><span>Tidak</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="6">
                                        <b>Jika ya</b>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <input type="checkbox"
                                            {{ isset($data['PMTCT_VCT']) ? 'checked' : '' }} /><span>PMTCT</span>
                                    </td>
                                    <td>
                                        <input type="checkbox"
                                            {{ isset($data['ART_VCT']) ? 'checked' : '' }} /><span>PMTCT</span>
                                    </td>
                                    <td colspan="4">
                                        <input type="checkbox"
                                            {{ isset($data['PPP_VCT']) ? 'checked' : '' }} /><span>PMTCT</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="6">
                                        <b>Tempat ART Dulu</b>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <input type="checkbox"
                                            {{ isset($data['RSPEM_VCT']) ? 'checked' : '' }} /><span>RS. Pem</span>
                                    </td>
                                    <td>
                                        <input type="checkbox"
                                            {{ isset($data['RSSwasta_VCT']) ? 'checked' : '' }} /><span>RS.
                                            Swasta</span>
                                    </td>
                                    <td colspan="4">
                                        <input type="checkbox"
                                            {{ isset($data['PKM_VCT']) ? 'checked' : '' }} /><span>PKM</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="6" class="padding-y">
                                        <div><span>Nama Dosis ART dan lama penggunaannya</span></div>
                                        {{ isset($data['namaDosisART_VCT']) ? $data['namaDosisART_VCT'] : '' }}
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                @endif
                <!-- END:VCT -->
                <!-- START:ONKOLOGI RADIASI -->
                @if (!isset($ruangan) || stripos($ruangan, 'ONKOLOGI RADIASI') != false)
                    <tr>
                        <td colspan="3">
                            <table class="border font">
                                <tr>
                                    <td colspan="4" class="padding-y">
                                        <span>Pemeriksaan Fisik</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="4">
                                        <b>1. Kulit atau Selaput Lendir :</b>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="padding-y">
                                        <div><span>Anemi :</span></div>
                                        {{ isset($data['Anemi']) ? $data['Anemi'] : '' }}
                                    </td>
                                    <td class="padding-y">
                                        <div><span>Sianosis :</span></div>
                                        {{ isset($data['Sianosis']) ? $data['Sianosis'] : '' }}
                                    </td>
                                    <td class="padding-y">
                                        <div><span>Icterus :</span></div>
                                        {{ isset($data['Icterus']) ? $data['Icterus'] : '' }}
                                    </td>
                                    <td class="padding-y">
                                        <div><span>Turgor :</span></div>
                                        {{ isset($data['Turgor']) ? $data['Turgor'] : '' }}
                                    </td>
                                </tr>
                                <tr class="btl">
                                    <td colspan="4">
                                        <b>2. Mata :</b>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="padding-y">
                                        <div><span>Penglihatan Ka. :</span></div>
                                        {{ isset($data['PenglihatanKa']) ? $data['PenglihatanKa'] : '' }}
                                    </td>
                                    <td class="padding-y">
                                        <div><span>Penglihatan Ki :</span></div>
                                        {{ isset($data['PenglihatanKi']) ? $data['PenglihatanKi'] : '' }}
                                    </td>
                                    <td class="padding-y">
                                        <div><span>Refleksi Pupil Ka. :</span></div>
                                        {{ isset($data['refleksiPupilKa']) ? $data['refleksiPupilKa'] : '' }}
                                    </td>
                                    <td class="padding-y">
                                        <div><span>Refleksi Pupil Ki :</span></div>
                                        {{ isset($data['refleksiPupilKi']) ? $data['refleksiPupilKi'] : '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <td class="padding-y">
                                        <div><span>Pupil :</span></div>
                                        {{ isset($data['pupil']) ? $data['pupil'] : '' }}
                                    </td>
                                    <td colspan="3" class="padding-y">
                                        <div><span>Diplopia :</span></div>
                                        {{ isset($data['diplopia']) ? $data['diplopia'] : '' }}
                                    </td>
                                </tr>
                                <tr class="btl">
                                    <td colspan="4">
                                        <b>3. Telinga :</b>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="padding-y">
                                        <div><span>Telinga Ka. :</span></div>
                                        {{ isset($data['TelingaKa']) ? $data['TelingaKa'] : '' }}
                                    </td>
                                    <td colspan="3" class="padding-y">
                                        <div><span>Telinga Ki :</span></div>
                                        {{ isset($data['TelingaKi']) ? $data['TelingaKi'] : '' }}
                                    </td>
                                </tr>
                                <tr class="btl">
                                    <td colspan="4">
                                        <b>4. Hidung :</b>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="padding-y">
                                        <div><span>Obstetri Ka. :</span></div>
                                        {{ isset($data['ObstetriKa']) ? $data['ObstetriKa'] : '' }}
                                    </td>
                                    <td colspan="3" class="padding-y">
                                        <div><span>Obstetri Ki :</span></div>
                                        {{ isset($data['ObstetriKi']) ? $data['ObstetriKi'] : '' }}
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3">
                            <table class="border font">
                                <tr>
                                    <td colspan="3">
                                        <b>5. Mulut :</b>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="padding-y">
                                        <div><span>Gigi geligi. :</span></div>
                                        {{ isset($data['GigiGeligi']) ? $data['GigiGeligi'] : '' }}
                                    </td>
                                    <td class="padding-y">
                                        <div><span>Keterangan :</span></div>
                                        {{ isset($data['KeteranganMulut']) ? $data['KeteranganMulut'] : '' }}
                                    </td>
                                    <td class="padding-y">
                                        <div><span>Mukosa :</span></div>
                                        {{ isset($data['MukosaMulut']) ? $data['MukosaMulut'] : '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <td class="padding-y">
                                        <div><span>Lidah. :</span></div>
                                        {{ isset($data['LidahOR']) ? $data['LidahOR'] : '' }}
                                    </td>
                                    <td class="padding-y">
                                        <div><span>Keterangan :</span></div>
                                        {{ isset($data['KeteranganLidah']) ? $data['KeteranganLidah'] : '' }}
                                    </td>
                                    <td class="padding-y">
                                        <div><span>Thyroid :</span></div>
                                        {{ isset($data['Thyroid']) ? $data['Thyroid'] : '' }}
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3">
                            <table class="border font">
                                <tr>
                                    <td colspan="2">
                                        <b>6. Tonsil :</b>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="padding-y">
                                        <div><span>Tonsil Ka.</span></div>
                                        {{ isset($data['TonsilKa']) ? $data['TonsilKa'] : '' }}
                                    </td>
                                    <td class="padding-y">
                                        <div><span>Tonsil Ki.</span></div>
                                        {{ isset($data['TonsilKi']) ? $data['TonsilKi'] : '' }}
                                    </td>
                                </tr>
                                <tr class="btl">
                                    <td colspan="2">
                                        <b>7. Leher :</b>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2" class="padding-y">
                                        <div><span>Tekanan Vena juguler.</span></div>
                                        {{ isset($data['TekananVena']) ? $data['TekananVena'] : '' }}
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3">
                            <table class="border font">
                                <tr>
                                    <td colspan="3">
                                        <b>8. Thoraks :</b>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="padding-y">
                                        <div><span>Pergerakan Pernafasan Ka.</span></div>
                                        {{ isset($data['pergerakanPernafasanKa']) ? $data['pergerakanPernafasanKa'] : '' }}
                                    </td>
                                    <td class="padding-y">
                                        <div><span>Pergerakan Pernafasan Ki.</span></div>
                                        {{ isset($data['pergerakanPernafasanKi']) ? $data['pergerakanPernafasanKi'] : '' }}
                                    </td>
                                    <td class="padding-y">
                                        <div><span>Pergerakan Pernafasan Ka & Ki.</span></div>
                                        {{ isset($data['pergerakanPernafasanKaKi']) ? $data['pergerakanPernafasanKaKi'] : '' }}
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3">
                            <table class="border font">
                                <tr>
                                    <td class="padding-y">
                                        <div><span>Perkusi Kanan</span></div>
                                        {{ isset($data['perkusiKanan']) ? $data['perkusiKanan'] : '' }}
                                    </td>
                                    <td class="padding-y">
                                        <div><span>Perkusi Kiri</span></div>
                                        {{ isset($data['perkusiKiri']) ? $data['perkusiKiri'] : '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <td class="padding-y">
                                        <div><span>Austulkasi Kanan</span></div>
                                        {{ isset($data['AustulkasiKanan']) ? $data['AustulkasiKanan'] : '' }}
                                    </td>
                                    <td class="padding-y">
                                        <div><span>Austulkasi Kiri</span></div>
                                        {{ isset($data['AustulkasiKiri']) ? $data['AustulkasiKiri'] : '' }}
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3">
                            <table class="border font">
                                <tr>
                                    <td colspan="3">
                                        <b>9. Jantung :</b>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <span>
                                            <div>Membesar. :</div>
                                        </span>
                                        {{ isset($data['membesar']) ? $data['membesar'] : '' }}
                                    </td>
                                    <td>
                                        <span>
                                            <div>Bunyi Jantung :</div>
                                        </span>
                                        {{ isset($data['bunyiJantung']) ? $data['bunyiJantung'] : '' }}
                                    </td>
                                    <td>
                                        <span>
                                            <div>Auskultasi :</div>
                                        </span>
                                        {{ isset($data['auskultasiJantung']) ? $data['auskultasiJantung'] : '' }}
                                    </td>
                                </tr>
                                <tr class="btl">
                                    <td colspan="3">
                                        <b>10. Abdomen :</b>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <span>
                                            <div>Nyeri :</div>
                                        </span>
                                        {{ isset($data['Nyeri']) ? $data['Nyeri'] : '' }}
                                    </td>
                                    <td>
                                        <span>
                                            <div>Ascites :</div>
                                        </span>
                                        {{ isset($data['Ascites']) ? $data['Ascites'] : '' }}
                                    </td>
                                    <td>
                                        <span>
                                            <div>Tumor :</div>
                                        </span>
                                        {{ isset($data['Tumor']) ? $data['Tumor'] : '' }}
                                    </td>
                                </tr>
                                <tr class="btl">
                                    <td colspan="3">
                                        <b>11. Hati / Limpa :</b>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="3">
                                        <span>
                                            <div>Hati / Limva :</div>
                                        </span>
                                        {{ isset($data['hatiLimva']) ? $data['hatiLimva'] : '' }}
                                    </td>
                                </tr>
                                <tr class="btl">
                                    <td colspan="3">
                                        <b>12. Tulang Punggung :</b>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <span>
                                            <div>Kifosis :</div>
                                        </span>
                                        {{ isset($data['Kifosis']) ? $data['Kifosis'] : '' }}
                                    </td>
                                    <td>
                                        <span>
                                            <div>Skollosis :</div>
                                        </span>
                                        {{ isset($data['Skollosis']) ? $data['Skollosis'] : '' }}
                                    </td>
                                    <td>
                                        <span>
                                            <div>Nyeri Tekanan atau Ketut :</div>
                                        </span>
                                        {{ isset($data['NyeriTekanantulangPunggung']) ? $data['NyeriTekanantulangPunggung'] : '' }}
                                    </td>
                                </tr>
                                <tr class="btl">
                                    <td colspan="3">
                                        <b>13. Pelvis :</b>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="3">
                                        <span>
                                            <div>Nyeri Tekanan atau Ketut :</div>
                                        </span>
                                        {{ isset($data['NyeriTekananPelvis']) ? $data['NyeriTekananPelvis'] : '' }}
                                    </td>
                                </tr>
                                <tr class="btl">
                                    <td colspan="3">
                                        <b>14. Extremitas :</b>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <span>
                                            <div>Edema :</div>
                                        </span>
                                        {{ isset($data['Edema']) ? $data['Edema'] : '' }}
                                    </td>
                                    <td>
                                        <span>
                                            <div>Varices :</div>
                                        </span>
                                        {{ isset($data['Varices']) ? $data['Varices'] : '' }}
                                    </td>
                                    <td>
                                        <span>
                                            <div>Nyeri Tekanan atau Ketut :</div>
                                        </span>
                                        {{ isset($data['NyeriTekananExtermitas']) ? $data['NyeriTekananExtermitas'] : '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="3">
                                        <span>
                                            <div>Reflek Patologis :</div>
                                        </span>
                                        {{ isset($data['ReflekPatologis']) ? $data['ReflekPatologis'] : '' }}
                                    </td>
                                </tr>
                                <tr class="btl">
                                    <td colspan="3">
                                        <b>15. HPHT :</b>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        {{ isset($data['HPHT']) ? $data['HPHT'] : '' }}
                                    </td>
                                    <td colspan="2">
                                        {{ isset($data['ketHpht']) ? $data['ketHpht'] : '' }}
                                    </td>
                                </tr>
                                <tr class="btl">
                                    <td colspan="3">
                                        <b>16. Lain - lain :</b>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="3">
                                        {{ isset($data['lainLain']) ? $data['lainLain'] : '' }}
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3">
                            <table class="border font">
                                <tr>
                                    <td colspan="6">
                                        Status Lokalis
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2" class="padding-y">
                                        <b>STATUS LOKALIS :</b>
                                    </td>
                                    <td colspan="4" class="padding-y">
                                        <span
                                            style="white-space: pre-line;">{{ isset($data['statusLokasiOnkologi']) ? $data['statusLokasiOnkologi'] : '' }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2" class="padding-y">
                                        <b>KELENJAR GETAH BENING REGIONAL :</b>
                                    </td>
                                    <td colspan="4" class="padding-y">
                                        <span
                                            style="white-space: pre-line;">{{ isset($data['kelenjarGetahBening']) ? $data['kelenjarGetahBening'] : '' }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2" class="padding-y">
                                        <b>KELENJAR GETAH BENING LAINNYA :</b>
                                    </td>
                                    <td colspan="4" class="padding-y">
                                        <span
                                            style="white-space: pre-line;">{{ isset($data['kelenjarGetahBeningLain']) ? $data['kelenjarGetahBeningLain'] : '' }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2">
                                        <b>Patologi Anatomi :</b>
                                    </td>
                                    <td>
                                        <b>Tanggal :</b>
                                    </td>
                                    <td colspan="3">
                                        <b>Hasil :</b>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2" class="padding-y">
                                        {{ isset($data['patologiAnatomi']) ? $data['patologiAnatomi'] : '' }}
                                    </td>
                                    <td class="padding-y">
                                        {{ isset($data['tanggalOnkrad']) ? date('d-m-Y', strtotime($data['tanggalOnkrad'])) : '' }}
                                    </td>
                                    <td colspan="3" class="padding-y">
                                        {{ isset($data['hasil']) ? $data['hasil'] : '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2">
                                        <b>Hasil Laboratorium :</b>
                                    </td>
                                    <td>
                                        <b>Tanggal :</b>
                                    </td>
                                    <td colspan="3">
                                        <b>Hasil :</b>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2" class="padding-y">
                                        {{ isset($data['hasilLabonkologi']) ? $data['hasilLabonkologi'] : '' }}
                                    </td>
                                    <td class="padding-y">
                                        {{ isset($data['tanggalOnkrad2']) ? date('d-m-Y', strtotime($data['tanggalOnkrad2'])) : '' }}
                                    </td>
                                    <td colspan="3" class="padding-y">
                                        {{ isset($data['keteranganHasilLab']) ? $data['keteranganHasilLab'] : '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2">
                                        <b>Hasil Radiologi :</b>
                                    </td>
                                    <td>
                                        <b>Tanggal :</b>
                                    </td>
                                    <td colspan="3">
                                        <b>Hasil :</b>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2" class="padding-y">
                                        {{ isset($data['hasilRadonkologi']) ? $data['hasilRadonkologi'] : '' }}
                                    </td>
                                    <td class="padding-y">
                                        {{ isset($data['tanggalOnkrad3']) ? date('d-m-Y', strtotime($data['tanggalOnkrad3'])) : '' }}
                                    </td>
                                    <td colspan="3" class="padding-y">
                                        {{ isset($data['keteranganHasilRad']) ? $data['keteranganHasilRad'] : '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2">
                                        <b>Lain - Lain :</b>
                                    </td>
                                    <td>
                                        <b>Tanggal :</b>
                                    </td>
                                    <td colspan="3">
                                        <b>Hasil :</b>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2" class="padding-y">
                                        {{ isset($data['lainLainHasil']) ? $data['lainLainHasil'] : '' }}
                                    </td>
                                    <td class="padding-y">
                                        {{ isset($data['tanggalOnkrad4']) ? date('d-m-Y', strtotime($data['tanggalOnkrad4'])) : '' }}
                                    </td>
                                    <td colspan="3" class="padding-y">
                                        {{ isset($data['keteranganHasilLain']) ? $data['keteranganHasilLain'] : '' }}
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                @endif
                <!-- END:ONKOLOGI RADIASI -->

                <tr>
                    <td colspan="2">
                        <table class="border font">
                            <tr>
                                <td colspan="3">
                                    <table>
                                        <tr>
                                            <td>
                                                <div style="margin: 5px; margin-bottom:10px;">
                                                    <b>Pemeriksaan Penunjang</b>
                                                </div>
                                                {{ isset($data['hasilpemeriksaanpenunjang']) ? '' . $data['hasilpemeriksaanpenunjang'] : '' }}
                                            </td>
                                            <td>
                                                <div style="margin: 5px; margin-bottom:10px;">
                                                    <b>Instruksi</b>
                                                </div>
                                                {{ isset($data['instruksiAsesmen']) ? '' . $data['instruksiAsesmen'] : '' }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2">
                                                <div>
                                                    <b>Diagnosa</b>
                                                    {{ isset($data['diagnosaIcd10']['label']) ? '' . $data['diagnosaIcd10']['label'] : '' }}
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2">
                                                <div>
                                                    {{ isset($data['TADiagnosa']) ? '' . $data['TADiagnosa'] : '' }}
                                                </div>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="3">
                                    <table border="1">
                                        <tr>
                                            <th>Daftar Masalah</th>
                                            <th>Rencana Intervensi</th>
                                            <th>Target</th>
                                        </tr>
                                        @if (isset($data['details']) && count($data['details']) > 0)
                                            @foreach ($data['details'] as $detail)
                                                <tr>
                                                    <td>{!! $detail['daftarMasalah'] ?? '&nbsp;' !!}</td>
                                                    <td>{!! $detail['rencanaIntervensi'] ?? '&nbsp;' !!}</td>
                                                    <td>{!! $detail['target'] ?? '&nbsp;' !!}</td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td>&nbsp;dsadasdasdads</td>
                                                <td>&nbsp;dsadasdasdads</td>
                                                <td>&nbsp;dsadasdasdads</td>
                                            </tr>
                                        @endif
                                    </table>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <table class="border font">
                            <tr>
                                <td colspan="3" class="btl">
                                    <table>
                                        <tr>
                                            <td colspan="4" style="margin: 5px;">
                                                <b>Kondisi Keluar RS</b>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="4">
                                                <b>Riwayat Keluar RS</b>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <input type="checkbox"
                                                    {{ isset($data['riwayatkeluar']) && $data['riwayatkeluar'] == 'Sembuh' ? 'checked' : '' }} />
                                                <span>Sembuh</span>
                                            </td>
                                            <td>
                                                <input type="checkbox"
                                                    {{ isset($data['riwayatkeluar']) && $data['riwayatkeluar'] == 'Membaik' ? 'checked' : '' }} />
                                                <span>Membaik</span>
                                            </td>
                                            <td>
                                                <input type="checkbox"
                                                    {{ isset($data['riwayatkeluar']) && $data['riwayatkeluar'] == 'Belum Sembuh' ? 'checked' : '' }} />
                                                <span>Belum Sembuh</span>
                                            </td>
                                            <td>
                                                <input type="checkbox"
                                                    {{ isset($data['riwayatkeluar']) && $data['riwayatkeluar'] == 'Tidak Ada Perkembangan' ? 'checked' : '' }} />
                                                <span>Tidak Ada Perkembangan</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <input type="checkbox"
                                                    {{ isset($data['riwayatkeluar']) && $data['riwayatkeluar'] == 'Meninggal >= 48 Jam' ? 'checked' : '' }} />
                                                <span>Meninggal > 48 Jam</span>
                                            </td>
                                            <td>
                                                <input type="checkbox"
                                                    {{ isset($data['riwayatkeluar']) && $data['riwayatkeluar'] == 'Meninggal <= 48 Jam' ? 'checked' : '' }} />
                                                <span>Meninggal <= 48 Jam</span>
                                            </td>
                                            <td>
                                                <input type="checkbox"
                                                    {{ isset($data['riwayatkeluar']) && $data['riwayatkeluar'] == 'DOA' ? 'checked' : '' }} />
                                                <span>DOA</span>
                                            </td>
                                            <td>
                                                <input type="checkbox"
                                                    {{ isset($data['riwayatkeluar']) && $data['riwayatkeluar'] == 'Rawat Inap' ? 'checked' : '' }} />
                                                <span>Rawat Inap</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="4">
                                                <b>Status Keluar RS</b>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <input type="checkbox"
                                                    {{ isset($data['statuskeluar']) && $data['statuskeluar'] == 'Belum Keluar RS' ? 'checked' : '' }} />
                                                <span>Belum Keluar RS</span>
                                            </td>
                                            <td>
                                                <input type="checkbox"
                                                    {{ isset($data['statuskeluar']) && $data['statuskeluar'] == 'Tidak' ? 'checked' : '' }} />
                                                <span>Diijinkan Pulang</span>
                                            </td>
                                            <td>
                                                <input type="checkbox"
                                                    {{ isset($data['statuskeluar']) && $data['statuskeluar'] == 'Pulang Paksa' ? 'checked' : '' }} />
                                                <span>Pulang Paksa</span>
                                            </td>
                                            <td>
                                                <input type="checkbox"
                                                    {{ isset($data['statuskeluar']) && $data['statuskeluar'] == 'Dirujuk' ? 'checked' : '' }} />
                                                <span>Dirujuk</span>
                                                @if (isset($data['statuskeluar']) && $data['statuskeluar'] == 'Dirujuk')
                                                    <div>
                                                        <b>
                                                            Tujuan
                                                        </b>
                                                    </div>
                                                    {{ isset($data['tujuan_skrs']) ? $data['tujuan_skrs'] : '' }}
                                                    <div>
                                                        <b>
                                                            Alasan
                                                        </b>
                                                    </div>
                                                    {{ isset($data['alasan_skrs']) ? $data['alasan_skrs'] : '' }}
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="4">
                                                <b>Perlu Kontrol</b>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <input type="checkbox"
                                                    {{ isset($data['perlukontrol']) && $data['perlukontrol'] == 'Ya' ? 'checked' : '' }} />
                                                <span>Ya</span>
                                            </td>
                                            <td>
                                                <input type="checkbox"
                                                    {{ isset($data['perlukontrol']) && $data['perlukontrol'] == 'Tidak' ? 'checked' : '' }} />
                                                <span>Tidak</span>
                                            </td>
                                            <td>
                                                <input type="checkbox"
                                                    {{ isset($data['perlukontrol']) && $data['perlukontrol'] == 'Rujuk Balik' ? 'checked' : '' }} />
                                                <span>Rujuk Balik</span>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>

                @if (!isset($ruangan) || stripos($ruangan, 'ANESTESI') != false)
                    <tr>
                        <td colspan="3">
                            <table class="border font">
                                <tr>
                                    <td width="25%">
                                    </td>
                                    <td width="25%">
                                    </td>
                                    <td width="25%">
                                    </td>
                                    <td width="25%" class="tc">
                                        @isset($data2['author'])
                                            <img src="data:image/svg+xml;base64,{{ base64_encode(QrCode::format('svg')->margin(2)->size(70)->generate($data2['author'] ?? '')) }}"
                                                alt="QR Code"> <br>
                                            <span
                                                style="font-size: 12px;">{{ isset($data2['author']) ? $data2['author'] : '' }}</span>
                                        @endisset
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                @else
                    <tr>
                        <td colspan="3">
                            <table class="border font">
                                <tr>
                                    <td width="25%">
                                    </td>
                                    <td width="25%">
                                    </td>
                                    {{-- @isset($data2['author'])
                                                <img src="data:image/png;base64, {!! $tte !!}" style="height: 100px;"> <br>
                                            <span
                                                style="font-size: 12px;">{{ isset($data2['author']) ? $data2['author'] : '' }}</span>
                                        @endisset --}}
                                    {{-- <pre>{{$data}}</pre> --}}
                                    {{-- {{dd($data)}} --}}
                                    <td width="25%" class="tc">
                                        <img src="data:image/svg+xml;base64,{{ base64_encode(QrCode::format('svg')->margin(2)->size(70)->generate($data['user_input']['namalengkap'])) }}"
                                            alt="QR Code"> <br>
                                        <span>{{$data['user_input']['namalengkap']}}</span>
                                        {{-- <span>{{$data['registrasi']['dokter']}}</span> --}}
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                @endif
            </table>
        </section>
    @endforeach
</body>

</html>
