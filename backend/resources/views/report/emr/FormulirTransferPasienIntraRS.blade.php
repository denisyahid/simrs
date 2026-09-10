<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>EMR - Formulir Transfer Pasien Intra Rumah Sakit</title>
    <?php
    function getYesNoLabel($value, $options) {
        foreach ($options as $option) {
            if ($option['value'] == $value) {
                return $option['label'];
            }
        }
        return ''; // Default to empty if value not found
    }

    $adaTidakOptions = [
        ['value' => 1, 'label' => 'Tidak Ada'],
        ['value' => 2, 'label' => 'Ada'],
    ];
    ?>
    <style>
        @media print {
            td.merah {
                background-color: #d54242 !important;
                -webkit-print-color-adjust: exact;
            }

            td.kuning {
                background-color: #c5d542 !important;
                -webkit-print-color-adjust: exact;
            }

            td.hijau {
                background-color: #42d55b !important;
                -webkit-print-color-adjust: exact;
            }

            td.hitam {
                background-color: #000000 !important;
                -webkit-print-color-adjust: exact;
            }
        }

        @page {
            size: A4;
        }

        /*@media print {*/
        /*    body {margin:0}*/
        /*}*/

        input[type=checkbox]:before {
            font-family: DejaVu Sans !important;
        }

        input[type=checkbox] {
            margin-bottom: -5px;
        }
        .double-border {

            border: 4px solid #000;

        }

        .double-border:before {

            border: 4px solid #fff;

        }

        .box {
            border: 2px solid black;
            /*border-radius: 6px;*/
        }

        .mt-5 {
            margin-top: 5px;
        }

        .garis6 td {
            padding: 3px;
        }

        .padding-y {
            padding-top: 3px;
            padding-bottom: 3px;
        }

        .vt {
            vertical-align: top;
        }

        .bold {
            font-weight: bold;
        }

        .f-s-15 {
            font-size: 12px;
        }

        .half {
            width: 50%;
        }

        .top-height {
            height: 50px;
            vertical-align: text-top;
            width: 15%;
        }

        .text-top {
            vertical-align: text-top;
        }

        .kotak {
            width: 50px;
            height: 20px;
        }

        .merah {
            background-color: #d54242 !important;
        }

        .kuning {
            background-color: #c5d542 !important;
        }

        .hijau {
            background-color: #42d55b !important;
        }

        .hitam {
            background-color: #000000 !important;
        }

        .bmerah {
            border: thin solid #d54242;
        }

        .bkuning {
            border: thin solid #c5d542;
        }

        .bhijau {
            border: thin solid #42d55b;
        }

        .bhitam {
            border: thin solid #000000;
        }

        .border-lr {
            border-collapse: collapse;
        }

        .border-lr td {
            border: thin solid #000;
        }

        .border-doang {
            border-collapse: collapse;
            border: thin solid #000;
            border-top: none;
        }

        .border-doang td {
            padding: 5px;
        }

        .bg-gray {
            background-color: #DCDCDC;
        }

        .bg-blue {
            background-color: #91CEDE;
        }

        .tc {
            text-align: center;
        }

        .no-border {
            border-top: none;
            border-bottom: none;
        }

        .font {
            font-size: 8pt;
        }

        .font-2 {
            font-size: 7pt;
        }

    </style>

    @stack('style')

</head>

<body class="A4" style="font-family:DejaVu Sans, sans-serif;;height: auto" onload="window.print()">
    {{-- {{dd($datas) }} --}}
    @foreach ($datas as $data)
        <section class="sheet padding-10mm" style="font-family:DejaVu Sans, sans-serif;;height: auto;overflow: hidden;">
            @php
                $tglv_pemindahan = isset($data['tglPemindahan']) ? date('d-m-Y H:i', strtotime($data['tglPemindahan'])) : "";
                $tglv_pemasanganCVC = isset($data['tanggalPemasanganInfus']) ? date('d-m-Y', strtotime($data['tanggalPemasanganInfus'])) : "";
                $tglv_pemasanganNGT = isset($data['tanggalPemasanganNGT']) ? date('d-m-Y', strtotime($data['tanggalPemasanganNGT'])) : "";
                $tglv_pemasanganKateter = isset($data['tanggalPemasanganKateter']) ? date('d-m-Y', strtotime($data['tanggalPemasanganKateter'])) : "";
            @endphp
            <table width="100%" cellspacing="0" cellpadding="0" border="1">
                <thead>
                    <tr>
                        <td width="100%" style="text-align:right" colspan=2>
                            <table width="100%" cellspacing="0" cellpadding="0" border="0">
                                <tr class="bg-blue">
                                    <td>
                                        <td width="50%" style="text-align:left; font-size: 14px">RSUD BALI MANDARA</td>
                                        <td width="50%" style="font-size: 14px; text-align: right;">RM.1A/SIR/00</td>
                                    </td>
                                </tr>
                            </table>
                        </td>
                        <!-- <td width="60%"></td> -->
                    </tr>
                </thead>
                <tr>
                    <td colspan="2">
                        <table width="100%" cellspacing="0" cellpadding="0" border="0">
                            <tr>
                                <td width="10%" style="padding: 5px; border-right: 1px solid black;">
                                    <img src="{{ 'img/logo-rs.png' }}" style="width: 90px;">
                                </td>
                                <td style="text-align: center; border-right: 1px solid black;">
                                    <b>
                                        <span style="font-size: 14px">FORMULIR TRANSFER PASIEN INTRA RUMAH SAKIT
                                    </b>
                                </td>
                                <td width="50%" style="padding: 10px">
                                    <div class="box" style="text-align: left">
                                        <table style="padding: 3px; font-size: 10px;">
                                            <tr>
                                                <td class="bold  text-top" style="width: 100px">No. RM</td>
                                                <td class="bold  text-top">:</td>
                                                <td class="bold text-top"><b>{{ $pasien['nocm'] }}</b></td>
                                            </tr>
                                            <tr>
                                                <td class="bold  text-top">Nama</td>
                                                <td class="bold  text-top">:</td>
                                                <td class="bold  text-top"><b>{{ $pasien['namapasien'] }}</b>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="bold  text-top">Jenis Kelamin</td>
                                                <td class="bold  text-top">:</td>
                                                <td class="bold  text-top">
                                                    <b>{{ $pasien['jeniskelamin'] }}</b>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="bold  text-top">Tgl Lahir</td>
                                                <td class="bold  text-top">:</td>
                                                <td class="bold  text-top">
                                                    <b>{{ $pasien['tgllahir'] }}</b>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <table width="100%" cellspacing="0" cellpadding="0" border="0">
                            <tr class="font">
                                <td colspan="3" width="75%">
                                    <span>
                                        &nbsp;
                                    </span>
                                </td>
                                <td width="25%">
                                    <span>&nbsp; Lembar ke : {{ isset($data['lembarKe']) ? $data['lembarKe'] : '' }}</span>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" style="border-bottom: none;">
                        <table class="font-2" width="100%" cellspacing="0" cellpadding="0" border="0">
                            <tr>
                                <td class="padding-y" colspan="4" width="100%">
                                    <span>
                                        &nbsp;Indikasi Rawat Inap/Pindah : {{ isset($data['indikasiRawatInap']) ? $data['indikasiRawatInap'] : '' }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td class="padding-y" colspan="2" width="40%">
                                    <span>
                                        &nbsp;Pemindahan Pasien :
                                        Tanggal dan Pukul: {{ isset($tglv_pemindahan) ? $tglv_pemindahan : '' }}
                                    </span>
                                </td>
                                <td width="30%">
                                    <span>
                                        &nbsp;Dari Ruang:
                                        {{ isset($data['dariRuangan']['label']) ? $data['dariRuangan']['label'] : '' }}
                                    </span>
                                </td>
                                <td width="30%">
                                    <span>
                                        &nbsp;Ke Ruang:
                                        {{ isset($data['keRuangan']['label']) ? $data['keRuangan']['label'] : '' }}
                                    </span>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" class="no-border padding-y">
                        <table class="font-2" width="100%" cellspacing="0" cellpadding="0" border="0">
                            <tr>
                                <td colspan="4">
                                    <span>
                                        &nbsp;DPJP :
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td width="25%">
                                    <span>
                                        &nbsp; 1. {{ isset($data['dokter1']['label']) ? $data['dokter1']['label'] : '' }}
                                    </span>
                                </td>
                                <td width="25%">
                                    <span>
                                        &nbsp;2. {{ isset($data['dokter2']['label']) ? $data['dokter2']['label'] : '' }}
                                    </span>
                                </td>
                                <td width="25%">
                                    <span>
                                        &nbsp;3. {{ isset($data['dokter3']['label']) ? $data['dokter3']['label'] : '' }}
                                    </span>
                                </td>
                                <td width="25%">
                                    <span>
                                        &nbsp;4. {{ isset($data['dokter4']['label']) ? $data['dokter4']['label'] : '' }}
                                    </span>
                                </td>
                            </tr>
                            @php $count = 5; @endphp
                            {{-- @foreach ($data['dokterList'] as $index => $item)
                                @if (($count - 5) % 4 == 0) <!-- Start a new row for every 4 doctors -->
                                    <tr>
                                @endif

                                <td width="25%">
                                    <span>
                                        &nbsp;{{ $count }}. {{ isset($item['value']['label']) ? $item['value']['label'] : '' }}
                                    </span>
                                </td>

                                @php $count++; @endphp

                                @if (($count - 5) % 4 == 0) <!-- Close row after 4 items -->
                                    </tr>
                                @endif
                            @endforeach --}}
                            @if (isset($data['dokterList']))
                                @foreach ($data['dokterList'] as $index => $item)
                                    @if (($count - 5) % 4 == 0) <!-- Start a new row for every 4 doctors -->
                                        <tr>
                                    @endif
                                    <td width="25%">
                                        <span>
                                            &nbsp;{{ $count }}. {{ isset($item['value']['label']) ? $item['value']['label'] : '' }}
                                        </span>
                                    </td>
                                    @php $count++; @endphp
                                    @if (($count - 5) % 4 == 0) <!-- Close row after 4 items -->
                                        </tr>
                                    @endif
                                @endforeach
                            @else
                                <tr>
                                    <td width="25%">
                                        <span>
                                            &nbsp;{{ $count }}. -
                                        </span>
                                    </td>
                                    @php $count++; @endphp
                                </tr>
                            @endif

                            <!-- Ensure last row closes properly if the number of items is not a multiple of 4 -->
                            @if (($count - 5) % 4 != 0)
                                @while (($count - 5) % 4 != 0)
                                    <td width="25%"></td> <!-- Add empty cells to complete the row -->
                                    @php $count++; @endphp
                                @endwhile
                                </tr>
                            @endif
                        </table>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" class="no-border padding-y">
                        <table class="font-2" width="100%" cellspacing="0" cellpadding="0" border="0">
                            <tr>
                                <td width="100%">
                                    <span>
                                        &nbsp;Diagnosis Masuk : {{ isset($data['diagnosisMasuk']) ? $data['diagnosisMasuk'] : '' }}
                                    </span>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" class="no-border padding-y">
                        <table class="font-2" width="100%" cellspacing="0" cellpadding="0" border="0">
                            <tr>
                                <td width="100%">
                                    <span>
                                        &nbsp;Diagnosis Sekarang : {{ isset($data['diagnosisSekarang']) ? $data['diagnosisSekarang'] : '' }}
                                    </span>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" class="no-border padding-y" style="padding-bottom: 12px;">
                        <table class="font-2" width="100%" cellspacing="0" cellpadding="0" border="0">
                            <tr>
                                <td width="100%">
                                    &nbsp;Kewaspadaan / precaution : {{ isset($data['kewaspadaan']) ? $data['kewaspadaan'] : '' }}
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" class="padding-y">
                        <table class="font-2" width="100%" cellspacing="0" cellpadding="3" border="0">
                            <tr>
                                <td colspan="2" width="100%">
                                    <span>
                                        I. RINGKASAN RIWAYAT PASIEN
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td width="5%" class="no-border" style="text-align: right; vertical-align: top;">
                                    <span>
                                        A.
                                    </span>
                                </td>
                                <td width="95%" class="no-border">
                                    <table class="font-2" width="100%" cellspacing="0" cellpadding="0" border="0">
                                        <tr>
                                            <td>ANAMNESIS</td>
                                        </tr>
                                        <tr>
                                            <td style="white-space: pre-line;" class="padding-y">Keluhan Utama :{{ isset($data['anamnesis']) ? $data['anamnesis'] : '' }}</td>
                                        </tr>
                                        <tr>
                                            <td style="white-space: pre-line;" class="padding-y">Riwayat Penyakit : {{ isset($data['riwayatpenyakit']) ? $data['riwayatpenyakit'] : '' }}</td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <table width="100%" cellspacing="0" cellpadding="0" border="0">
                                                    <tr>
                                                        <td>
                                                            <span>Riwayat Alergi :</span> <br />
                                                            <span>{{ isset($data['SRiwayatAlergi']) ? getYesNoLabel($data['SRiwayatAlergi'], $adaTidakOptions) : '' }}</span>
                                                        </td>
                                                        <td>
                                                            <input type="checkbox" {{ isset($data['CBAlergiObat']) && $data['CBAlergiObat'] == 'Obat' ? 'checked' : '' }}  /><span>Obat :</span><br />
                                                            <span>{{ isset($data['TBAlergiObat']) ? $data['TBAlergiObat'] : '' }}</span>
                                                        </td>
                                                        <td>
                                                            <input type="checkbox" {{ isset($data['CBAlergiMakanan']) && $data['CBAlergiMakanan'] == 'Makanan' ? 'checked' : '' }}  /><span>Makanan :</span><br />
                                                            <span>{{ isset($data['TBAlergiMakanan']) ? $data['TBAlergiMakanan'] : '' }}</span>
                                                        </td>
                                                        <td>
                                                            <input type="checkbox" {{ isset($data['CBAlergiLainnya']) && $data['CBAlergiLainnya'] == 'Lainnya' ? 'checked' : '' }}  /><span>Lainnya :</span><br />
                                                            <span>{{ isset($data['TBAlergiLainnya']) ? $data['TBAlergiLainnya'] : '' }}</span>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                            {{-- <td style="white-space: pre-line" class="padding-y"><span>Riwayat alergi / reaksi obat :</span><input type="checkbox" {{ isset($data['isalergi']) && $data['isalergi'] == 'TIDAK' ? 'checked' : '' }}  /><span>Tidak</span><input type="checkbox" {{ isset($data['isalergi']) && $data['isalergi'] == 'YA' ? 'checked' : '' }}  /><span>Ya,</span><span>nama obat:</span><span>{{ isset($data['TBAlergiObat']) ? $data['TBAlergiObat'] : '' }}</span></td> --}}
                                        </tr>
                                        <tr>
                                            <td class="padding-y" style="white-space: pre-line">Jenis reaksi : {{ isset($data['jenisReaksiText']) ? $data['jenisReaksiText'] : '' }}</td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <td width="5%" class="no-border" style="text-align: right; vertical-align: top;">
                                    <span>
                                        B.
                                    </span>
                                </td>
                                <td width="95%" class="no-border">
                                    <table class="font-2" width="100%" cellspacing="0" cellpadding="0" border="0">
                                        <tr>
                                            <td>PEMERIKSAAN FISIK</td>
                                        </tr>
                                        <tr>
                                            <td class="padding-y">
                                                <span>GCS: E: {{ isset($data['gcse']) ? $data['gcse'] : '  ' }} V: {{ isset($data['gcsv']) ? $data['gcsv'] : '  ' }} M: {{ isset($data['gcsm']) ? $data['gcsm'] : '  ' }}, TD: {{ isset($data['tekananDarah']) ? $data['tekananDarah'] : '    /    ' }} mmHg, N: {{ isset($data['nadi']) ? $data['nadi'] : '  ' }} x/menit, Suhu {{ isset($data['celcius']) ? $data['celcius'] : '  ' }}°C</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="padding-y">
                                                <span>Skala nyeri: {{ isset($data['skalaNyeri']) ? $data['skalaNyeri'] : '            ' }}, SpO2: {{ isset($data['sao2']) ? $data['sao2'] : '   ' }}</span> <input type="checkbox" {{ isset($data['roomAir']) && $data['roomAir'] == 'room air' ? 'checked' : '' }}  /><span> room air </span><input type="checkbox" {{ isset($data['roomAir']) && $data['roomAir'] == 'dengan' ? 'checked' : '' }}  /><span> dengan </span><span>{{ isset($data['roomAirText']) ? $data['roomAirText'] : '    ' }}, Skor EWS : {{ isset($data['skorews']) ? $data['skorews'] : '    ' }}</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <span>
                                                    Keadaan Umum :
                                                </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="white-space: pre-line;" class="padding-y">{{ isset($data['keadaanUmum']) ? $data['keadaanUmum'] : '    ' }}</td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <span>BAB: </span><input type="checkbox" {{ isset($data['bab']) && $data['bab'] == 'Normal' ? 'checked' : '' }}  /><span>Normal</span> <input type="checkbox" {{ isset($data['bab']) && $data['bab'] == 'Illeustomy/colostomy' ? 'checked' : '' }}  /><span>Illeustomy/colostomy</span> <input type="checkbox" {{ isset($data['bab']) && $data['bab'] == 'Inkontinensia alvi' ? 'checked' : '' }}  /><span>Inkontinensia alvi</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <span>BAK: </span><input type="checkbox" {{ isset($data['BAK']) && $data['BAK'] == 'Normal' ? 'checked' : '' }}  /><span>Normal</span> <input type="checkbox" {{ isset($data['BAK']) && $data['BAK'] == 'Inkontinensia' ? 'checked' : '' }}  /><span>Inkontinensia</span> <input type="checkbox" {{ isset($data['BAK']) && $data['BAK'] == 'Kateter' ? 'checked' : '' }}  /><span>Kateter</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <span>Mobilisasi: </span><input type="checkbox" {{ isset($data['Mobilisasi']) && $data['Mobilisasi'] == 'Jalan' ? 'checked' : '' }}  /><span>Jalan</span> <input type="checkbox" {{ isset($data['Mobilisasi']) && $data['Mobilisasi'] == 'Tirah Baring' ? 'checked' : '' }}  /><span>Tirah Baring</span> <input type="checkbox" {{ isset($data['Mobilisasi']) && $data['Mobilisasi'] == 'Duduk' ? 'checked' : '' }}  /><span>Duduk</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <span>Luka/perawatan decubitus : </span><input type="checkbox" {{ isset($data['Luka']) && $data['Luka'] == 'Tidak' ? 'checked' : '' }}  /><span>Tidak</span> <input type="checkbox" {{ isset($data['Luka']) && $data['Luka'] == 'Ya' ? 'checked' : '' }}  /><span>Ya, </span><span>Kondisi {{ isset($data['Kondisi']) ? $data['Kondisi'] : '' }} </span><span>Lokasi {{ isset($data['Lokasi']) ? $data['Lokasi'] : '' }} </span><span>Ukuran {{ isset($data['Ukuran']) ? $data['Ukuran'] : '' }}</span>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" class="no-border">
                        <table class="font-2" width="100%" cellspacing="0" cellpadding="3" border="0">
                            <tr>
                                <td>
                                    <span>II. PEMERIKSAAN PENUNJANG YANG SUDAH DILAKUKAN</span>
                                </td>
                            </tr>
                            <tr>
                                <td class="padding-y">
                                    <span style="white-space: pre-line;">
                                        {{ isset($data['pemeriksaanPenunjang']) ? $data['pemeriksaanPenunjang'] : '' }}
                                    </span>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <tr>
                        <td colspan="2" style="border-bottom: none;">
                            <table class="font-2" width="100%" cellspacing="0" cellpadding="3" border="0">
                                <tr>
                                    <td colspan="3">
                                        <span>III. PROSEDUR/TINDAKAN YANG SUDAH DILAKUKAN</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <span>Infus/CVC PIVAS Score : {{ isset($data['InfusCVC']) ? $data['InfusCVC'] : '' }}</span>
                                    </td>
                                    <td colspan="2">
                                        <span>
                                            Tanggal Pemasangan : {{ isset($tglv_pemasanganCVC) ? $tglv_pemasanganCVC : '' }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <span>NGT ukuran : {{ isset($data['NGTUkuran']) ? $data['NGTUkuran'] : '' }}</span>
                                    </td>
                                    <td>
                                        <span>
                                            Tanggal Pemasangan : {{ isset($tglv_pemasanganNGT) ? $tglv_pemasanganNGT : '' }}
                                        </span>
                                    </td>
                                    <td>
                                        <span>
                                            Ket. : {{ isset($data['keteranganNGT']) ? $data['keteranganNGT'] : '' }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <span>
                                            Jenis kateter : {{ isset($data['jenisKateter']) ? $data['jenisKateter'] : '' }}
                                        </span>
                                    </td>
                                    <td>
                                        <span>
                                            No. Kateter : {{ isset($data['noKateter']) ? $data['noKateter'] : '' }}
                                        </span>
                                    </td>
                                    <td>
                                        <span>
                                            Tgl. Pemasangan : {{ isset($tglv_pemasanganKateter) ? $tglv_pemasanganKateter : '' }}
                                        </span>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </tr>
                <tr style="page-break-after: always;">
                    <td colspan="2" class="no-border padding-y">
                        <table class="font-2" width="100%" cellspacing="0" cellpadding="0" border="0">
                            <tr>
                                <td style="white-space: pre-line">
                                    {{ isset($data['KeteranganLainnyaKateter']) ? $data['KeteranganLainnyaKateter'] : '' }}
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" class="padding-y">
                        <table class="font-2" width="100%" cellspacing="0" cellpadding="3" border="0">
                            <tr>
                                <td>
                                    <span>IV. TERAPI YANG SUDAH DIBERIKAN (Infus, Injeksi, Oral, dan Diet)</span>
                                </td>
                            </tr>
                            <tr>
                                <td style="white-space: pre-line;">
                                    {{ isset($data['terapiYangSudahDilakkukan']) ? $data['terapiYangSudahDilakkukan'] : '' }}
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr class="bg-blue">
                    <td width="100%" colspan="2" class="padding-y">
                        <table width="100%" cellspacing="0" cellpadding="0" border="0">
                            <tr>
                                <td>
                                    <td colspan="2" width="100%" style="font-size: 14px; text-align: center;">KEADAAN PASIEN SAAT PINDAH</td>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" class="no-border padding-y">
                        <table class="font-2" width="100%" cellspacing="0" cellpadding="3" border="0">
                            <tr>
                                <td class="padding-y">
                                    <span>GCS: E: {{ isset($data['gcseSaatPindah']) ? $data['gcseSaatPindah'] : '  ' }} V: {{ isset($data['gcsvSaatPindah']) ? $data['gcsvSaatPindah'] : '  ' }} M: {{ isset($data['gcsmSaatPindah']) ? $data['gcsmSaatPindah'] : '  ' }}, TD: {{ isset($data['tekananDarahSaatPindah']) ? $data['tekananDarahSaatPindah'] : '    /    ' }} mmHg, N: {{ isset($data['nadiSaatPindah']) ? $data['nadiSaatPindah'] : '  ' }} x/menit, Suhu {{ isset($data['celciusSaatPindah']) ? $data['celciusSaatPindah'] : '  ' }}°C</span>
                                </td>
                            </tr>
                            <tr>
                                <td class="padding-y">
                                    <span>Skala nyeri: {{ isset($data['skalaNyeriSaatPindah']) ? $data['skalaNyeriSaatPindah'] : '            ' }}, SpO2: {{ isset($data['sao2SaatPindah']) ? $data['sao2SaatPindah'] : '   ' }}</span> <input type="checkbox" {{ isset($data['roomAirSaatPindah']) && $data['roomAirSaatPindah'] == 'room air' ? 'checked' : '' }}  /><span> room air </span><input type="checkbox" {{ isset($data['roomAirSaatPindah']) && $data['roomAirSaatPindah'] == 'dengan' ? 'checked' : '' }}  /><span> dengan </span><span>{{ isset($data['roomAirTextSaatPindah']) ? $data['roomAirTextSaatPindah'] : '    ' }}, Skor EWS : {{ isset($data['skorewsSaatPindah']) ? $data['skorewsSaatPindah'] : '    ' }}</span>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <span>
                                        Keadaan Umum :
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td style="white-space: pre-line; {{ empty($data['keadaanUmumSaatPindah']) ? 'padding-bottom: 2em;' : '' }}" class="padding-y">{{ isset($data['keadaanUmumSaatPindah']) ? $data['keadaanUmumSaatPindah'] : '' }}</td>
                            </tr>
                            <tr>
                                <td>
                                    <span>
                                        Diagnosis Keperawatan :
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td style="white-space: pre-line {{ empty($data['diagnosisSaatPindah']) ? 'padding-bottom: 2em;' : '' }}">{{ isset($data['diagnosisSaatPindah']) ? $data['diagnosisSaatPindah'] : '' }}</td>
                            </tr>
                            <tr>
                                <td>
                                    <span>
                                        Rencana Terapi :
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td style="white-space: pre-line; {{ empty($data['rencanaSaatPindah']) ? 'padding-bottom: 2em;' : '' }}">{{ isset($data['rencanaSaatPindah']) ? $data['rencanaSaatPindah'] : '' }}</td>
                            </tr>
                            <tr>
                                <td>
                                    <span>
                                        Rencana Pemeriksaan Penunjang :
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td style="white-space: pre-line; {{ empty($data['rencanaPemeriksaanPenunjang']) ? 'padding-bottom: 2em;' : '' }}">{{ isset($data['rencanaPemeriksaanPenunjang']) ? $data['rencanaPemeriksaanPenunjang'] : '' }}</td>
                            </tr>
                            <tr>
                                <td>
                                    <span>
                                        Rencana Prosedur / Tindakan :
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td style="white-space: pre-line; {{ empty($data['rencanaProsedur']) ? 'padding-bottom: 2em;' : '' }}">{{ isset($data['rencanaProsedur']) ? $data['rencanaProsedur'] : '' }}</td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" class="padding-y">
                        <table class="font-2" width="100%" cellspacing="0" cellpadding="3" border="0">
                            <tr>
                                <td colspan="4">
                                    <span style="font-weight: bold; font-style: italic;">
                                        Note: Jumlah obat, barang dan dokumen yang disertakan
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <span>Hasil lab {{ isset($data['hasilLab']) ? $data['hasilLab'] : ' ' }} lembar</span>
                                </td>
                                <td>
                                    <span>Foto rontgent {{ isset($data['fotoRontgen']) ? $data['fotoRontgen'] : ' ' }} lembar</span>
                                </td>
                                <td>
                                    <span>Hasil USG {{ isset($data['hasilUsg']) ? $data['hasilUsg'] : ' ' }} lembar</span>
                                </td>
                                <td>
                                    <span>Hasil EKG {{ isset($data['hasilEKG']) ? $data['hasilEKG'] : ' ' }} lembar</span>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <span>MRI {{ isset($data['hasilMRI']) ? $data['hasilMRI'] : ' ' }} lembar</span>
                                </td>
                                <td>
                                    <span>MRA {{ isset($data['hasilMRA']) ? $data['hasilMRA'] : ' ' }} lembar</span>
                                </td>
                                <td>
                                    <span>CT SCAN {{ isset($data['hasilCt']) ? $data['hasilCt'] : ' ' }} lembar</span>
                                </td>
                                <td>
                                    <span>Hasil Echo {{ isset($data['hasilEcho']) ? $data['hasilEcho'] : ' ' }} lembar</span>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <span>Gigi Palsu {{ isset($data['gigiPalsu']) ? $data['gigiPalsu'] : ' ' }}</span>
                                </td>
                                <td>
                                    <span>Kaca Mata {{ isset($data['kacaMata']) ? $data['kacaMata'] : ' ' }}</span>
                                </td>
                                <td colspan="2">
                                    <span>Alat Bantu Dengar {{ isset($data['alatBantuDengar']) ? $data['alatBantuDengar'] : ' ' }}</span>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="4">
                                    <span>Rekam medis lama: </span><input type="checkbox" {{ isset($data['rekamMedisLama']) && $data['rekamMedisLama'] == 'Ada' ? 'checked' : ' ' }}  />
                                    <span>Ada</span> <input type="checkbox" {{ isset($data['rekamMedisLama']) && $data['rekamMedisLama'] == 'Tidak' ? 'checked' : ' ' }}  />
                                    <span>Tidak</span> <span>Lain-lain {{ isset($data['rekamMedisLamaKeterangan']) ? $data['rekamMedisLamaKeterangan'] : ' ' }}</span>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="4">
                                    <span>Gelang Identitas : </span>
                                    <span>{{ isset($data['gelangIdentitas']) ? $data['gelangIdentitas'] : '' }}</span>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" style="padding: 10px;">
                        <table class="font-2" width="100%" cellspacing="0" cellpadding="0" border="1">
                            <tr>
                                <td class="tc vt" width="25%" style="{{ empty($data['namaPJ']) ? 'padding-bottom: 10em;' : '' }}">
                                    <span>Disetujui</span>
                                    <br>
                                    <!-- Qrcode ceritanya -->
                                    @if (isset($data['disetujui']) && $data['disetujui'] != '')
                                        <img src="data:image/png;base64, {!! $qrcode2 !!}">
                                    @endif
                                    <br>
                                    <span>{{ isset($data['disetujui']) ? $data['disetujui'] : ' ' }}</span>
                                </td>
                                <td class="tc vt" width="25%">
                                    <span>Diserahkan</span>
                                    <br>
                                    <!-- Qrcode ceritanya -->
                                    @if (isset($data['disetujuiPerawat']['label']))
                                        <img src="data:image/png;base64, {!! $qrcode !!}">
                                    @endif
                                    <br>
                                    <span>{{ isset($data['disetujuiPerawat']['label']) ? $data['disetujuiPerawat']['label'] : ' ' }}</span>
                                </td>
                                <td class="tc vt" width="25%">
                                    <span>Diterima</span>
                                    <br>
                                    <!-- Qrcode ceritanya -->
                                    @if (isset($data['diterimaPerawat']['label']))
                                        <img src="data:image/png;base64, {!! $qrcode3 !!}">
                                    @endif
                                    <br>
                                    <span>{{ isset($data['diterimaPerawat']['label']) ? $data['diterimaPerawat']['label'] : ' ' }}</span>
                                </td>
                                <td class="tc vt" width="25%">
                                    <span>Diregistrasi</span>
                                    <br>
                                    <!-- Qrcode ceritanya -->
                                    @if (isset($data['petugasRegistrasi']['label']))
                                        <img src="data:image/png;base64, {!! $qrcode4 !!}">
                                    @endif
                                    <br>
                                    <span>{{ isset($data['petugasRegistrasi']['label']) ? $data['petugasRegistrasi']['label'] : ' ' }}</span>
                                </td>
                            </tr>
                            <tr>
                                <td class="tc padding-y">
                                    <span>Pasien/penanggung jawab</span>
                                </td>
                                <td class="tc padding-y">
                                    <span>Perawat</span>
                                </td>
                                <td class="tc padding-y">
                                    <span>Perawat</span>
                                </td>
                                <td class="tc padding-y">
                                    <span>Billing</span>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </section>
    @endforeach


</body>

</html>
