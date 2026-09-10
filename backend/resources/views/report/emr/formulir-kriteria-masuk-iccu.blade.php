<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>EMR - Formulir Kriteria Masuk ICCU</title>
        <link rel="stylesheet" href="'css/report/paper.css'}} ">
        <link rel="stylesheet" href="'css/report/table.css'}}">
        <link rel="stylesheet" href="'css/report/tabel.css'}}">

    @php
        $date = new DateTime($data['tanggal'], new DateTimeZone('UTC')); // Parse the input date as UTC
        $date->setTimezone(new DateTimeZone('Asia/Jakarta')); // Convert to WIB (Asia/Jakarta timezone)
    @endphp

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

        .garis6 td {
            padding: 3px;
        }

        .bold {
            font-weight: bold;
        }

        .f-s-15 {
            font-size: 12px;
        }

        .top-height {
            height: 50px;
            vertical-align: text-top;
            width: 15%;
        }

        .text-top {
            vertical-align: text-top;
        }

        .bg-blue{
            background: #00A1E9;
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

    </style>

    @stack('style')
</head>

<body class="A4" style="font-family:sans-serif;">
    <section style="font-family:sans-serif;" >
        <table width="100%" cellspacing="0" cellpadding="0" border="1">
            <tr>
                <td width="60%" style="text-align:right" colspan=2>
                    <table width="100%" cellspacing="0" cellpadding="0" border="0">
                        <thead>
                            <tr class="bg-blue">
                                <td>
                                    <td width="50%" style="text-align:left; font-size: 14px">RSUD BALI MANDARA</td>
                                    <td width="50%" style="font-size: 14px; text-align: right;">RM 1A/IRIT/01</td>
                                </td>
                            </tr>
                        </thead>
                    </tabel>
                </td>
                <!-- <td width="60%"></td> -->
            </tr>
        </table>

        <table width="100%" cellspacing="0" cellpadding="0" border="0" style="border-top: 1px solid black">
            <tr>
                <td width="10%" style="padding: 5px; border-right: 1px solid black;">
                    <img src="{{ 'img/logo-rs.png' }}" style="width: 90px;">
                </td>
                <td style="text-align: center; border-right: 1px solid black;">
                    <b>
                        <span style="font-size: 16px">KRITERIA MASUK
                        </span><br>
                        <br>
                        <span style="font-size: 16px">ICCU</span>
                    </b>
                </td>
                <td width="60%" style="padding: 10px">
                    <div class="box" style="text-align: left">
                        <table style="padding: 3px;">
                            <tr>
                                <td class="f-s-15 bold  text-top" style="width: 100px">No. RM</td>
                                <td class="f-s-15 bold  text-top">:</td>
                                <td class="f-s-15 bold text-top"><b>{{ $pasien['nocm'] }}</b></td>
                            </tr>
                            <tr>
                                <td class="f-s-15 bold  text-top">Nama</td>
                                <td class="f-s-15 bold  text-top">:</td>
                                <td class="f-s-15 bold  text-top"><b>{{ $pasien['namapasien'] }}</b>
                                </td>
                            </tr>
                            <tr>
                                <td class="f-s-15 bold  text-top">Jenis Kelamin</td>
                                <td class="f-s-15 bold  text-top">:</td>
                                <td class="f-s-15 bold  text-top">
                                    <b>{{ $pasien['jeniskelamin'] }}</b>
                                </td>
                            </tr>
                            <tr>
                                <td class="f-s-15 bold  text-top">Tgl Lahir</td>
                                <td class="f-s-15 bold  text-top">:</td>
                                <td class="f-s-15 bold  text-top">
                                    <b>{{ $pasien['tgllahir'] }}</b>
                                </td>
                            </tr>
                        </table>
                    </div>
                </td>
            </tr>
        </table>
        <table width="100%" cellspacing="0" cellpadding="0" border="1">
            <tr>
                <th width = "70%" style="font-size: 14; text-align: center">Kriteria Fisiologis</th>
                <th width = "15%" style="font-size: 14; text-align: center">YA</th>
                <th width = "15%" style="font-size: 14; text-align: center">TIDAK</th>
            </tr>
            <!-- Kriteria Klinis -->
            <tr>
                <td style="font-size: 9pt; text-align: left" class="bg-blue">&nbsp;1. Kriteria KLINIS</td>
                <td class="bg-blue"></td>
                <td class="bg-blue"></td>
            </tr>
            <tr>
                <td style="font-size: 9pt; text-align: left">&nbsp;&nbsp; a. Nyeri dada khas angina atau ekuivalen angina</td>
                <td style="text-align: center">
                    <input type="checkbox" {{ isset($data['kriA']) && $data['kriA'] == 'Ya' ? 'checked' : '' }}  />
                </td>
                <td style="text-align: center">
                    <input type="checkbox" {{ isset($data['kriA']) && $data['kriA'] == 'Tidak' ? 'checked' : '' }}  />
                </td>
            </tr>
            <tr>
                <td style="font-size: 9pt; text-align: left">&nbsp;&nbsp; b. Sesak nafas yang terjadi saat istirahat, tidak membaik dengan posisi duduk</td>
                <td style="text-align: center">
                    <input type="checkbox" {{ isset($data['kriB']) && $data['kriB'] == 'Ya' ? 'checked' : '' }}  />
                </td>
                <td style="text-align: center">
                    <input type="checkbox" {{ isset($data['kriB']) && $data['kriB'] == 'Tidak' ? 'checked' : '' }}  />
                </td>
            </tr>
            <tr>
                <td style="font-size: 9pt; text-align: left;">&nbsp;&nbsp; c. <i>Angina Class </i> (Sesuai kriteria <i>canadian cardiovascular society</i>) yang memburuk</td>
                <td style="text-align: center">
                    <input type="checkbox" {{ isset($data['kriC']) && $data['kriC'] == 'Ya' ? 'checked' : '' }}  />
                </td>
                <td style="text-align: center">
                    <input type="checkbox" {{ isset($data['kriC']) && $data['kriC'] == 'Tidak' ? 'checked' : '' }}  />
                </td>
            </tr>
            <tr>
                <td style="font-size: 9pt; text-align: left">&nbsp;&nbsp; d. Palpitasi yang menyebabkan gejala ketidakstabilan hemodinamik</td>
                <td style="text-align: center">
                    <input type="checkbox" {{ isset($data['kriD']) && $data['kriD'] == 'Ya' ? 'checked' : '' }}  />
                </td>
                <td style="text-align: center">
                    <input type="checkbox" {{ isset($data['kriD']) && $data['kriD'] == 'Tidak' ? 'checked' : '' }}  />
                </td>
            </tr>
            <tr>
                <td style="font-size: 9pt; text-align: left">&nbsp;&nbsp; e. Sesak nafas atau dyspnoe on effort dengan klas fungsional gagal jantung yang memburuk (sesuai klas fungsional NYHA)</td>
                <td style="text-align: center">
                    <input type="checkbox" {{ isset($data['kriE']) && $data['kriE'] == 'Ya' ? 'checked' : '' }}  />
                </td>
                <td style="text-align: center">
                    <input type="checkbox" {{ isset($data['kriE']) && $data['kriE'] == 'Tidak' ? 'checked' : '' }}  />
                </td>
            </tr>
            <!-- Kriteria Klinis -->

            <!-- Vital Sign -->
            <tr>
                <td style="font-size: 9pt; text-align: left" class="bg-blue">&nbsp;2. Kriteria Vital Sign</td>
                <td class="bg-blue"></td>
                <td class="bg-blue"></td>
            </tr>
            <tr>
                <td style="font-size: 9pt; text-align: left">&nbsp;&nbsp; a. Nadi &lt;40 atau >150kali/menit</td>
                <td style="text-align: center">
                    <input type="checkbox" {{ isset($data['sigA']) && $data['sigA'] == 'Ya' ? 'checked' : '' }}  />
                </td>
                <td style="text-align: center">
                    <input type="checkbox" {{ isset($data['sigA']) && $data['sigA'] == 'Tidak' ? 'checked' : '' }}  />
                </td>
            </tr>
            <tr>
                <td style="font-size: 9pt; text-align: left">&nbsp;&nbsp; b. Tekanan darah sisolik &lt; 90 mmHg atau penurunan 20 mmHg dari tekanan darah sistolik biasanya</td>
                <td style="text-align: center">
                    <input type="checkbox" {{ isset($data['sigB']) && $data['sigB'] == 'Ya' ? 'checked' : '' }}  />
                </td>
                <td style="text-align: center">
                    <input type="checkbox" {{ isset($data['sigB']) && $data['sigB'] == 'Tidak' ? 'checked' : '' }}  />
                </td>
            </tr>
            <tr>
                <td style="font-size: 9pt; text-align: left">&nbsp;&nbsp; c. <i> Mean Arterial Pressure </i> mmHg atau > 150 mmHg</td>
                <td style="text-align: center">
                    <input type="checkbox" {{ isset($data['sigC']) && $data['sigC'] == 'Ya' ? 'checked' : '' }}  />
                </td>
                <td style="text-align: center">
                    <input type="checkbox" {{ isset($data['sigC']) && $data['sigC'] == 'Tidak' ? 'checked' : '' }}  />
                </td>
            </tr>
            <tr>
                <td style="font-size: 9pt; text-align: left">&nbsp;&nbsp; d. Laju respirasi > 35 kali/menit</td>
                <td style="text-align: center">
                    <input type="checkbox" {{ isset($data['sigD']) && $data['sigD'] == 'Ya' ? 'checked' : '' }}  />
                </td>
                <td style="text-align: center">
                    <input type="checkbox" {{ isset($data['sigD']) && $data['sigD'] == 'Tidak' ? 'checked' : '' }}  />
                </td>
            </tr>
            <!-- END Vital Sign -->

            <!-- NILAI LABORATORIUM & RADIOLOGI -->
            <tr>
                <td style="font-size: 9pt; text-align: left" class="bg-blue">&nbsp;3. NILAI LABORATORIUM & RADIOLOGI</td>
                <td class="bg-blue"></td>
                <td class="bg-blue"></td>
            </tr>
            <tr>
                <td style="font-size: 9pt; text-align: left">&nbsp;&nbsp; a. Peningkatan Troponin yang signifikan yang menyokong IMA atau Miokarditis ( Troponin > 100 atau peningkatan 20% dari baseline dalam 6 jam)</td>
                <td style="text-align: center">
                    <input type="checkbox" {{ isset($data['lrA']) && $data['lrA'] == 'Ya' ? 'checked' : '' }}  />
                </td>
                <td style="text-align: center">
                    <input type="checkbox" {{ isset($data['lrA']) && $data['lrA'] == 'Tidak' ? 'checked' : '' }}  />
                </td>
            </tr>
            <tr>
                <td style="font-size: 9pt; text-align: left">&nbsp;&nbsp; b. Diseksi aorta dan aorta kritis dari CT Scan</td>
                <td style="text-align: center">
                    <input type="checkbox" {{ isset($data['lrB']) && $data['lrB'] == 'Ya' ? 'checked' : '' }}  />
                </td>
                <td style="text-align: center">
                    <input type="checkbox" {{ isset($data['lrB']) && $data['lrB'] == 'Tidak' ? 'checked' : '' }}  />
                </td>
            </tr>
            <tr>
                <td style="font-size: 9pt; text-align: left">&nbsp;&nbsp; a. Peningkatan Troponin yang signifikan yang menyokong IMA atau Miokarditis ( Troponin > 100 atau peningkatan 20% dari baseline dalam 6 jam)</td>
                <td style="text-align: center">
                    <input type="checkbox" {{ isset($data['lrC']) && $data['lrC'] == 'Ya' ? 'checked' : '' }}  />
                </td>
                <td style="text-align: center">
                    <input type="checkbox" {{ isset($data['lrC']) && $data['lrC'] == 'Tidak' ? 'checked' : '' }}  />
                </td>
            </tr>
            <!-- END NILAI LABORATORIUM & RADIOLOGI -->

            <!-- NILAI ECG -->
            <tr>
                <td style="font-size: 9pt; text-align: left" class="bg-blue">&nbsp;4. KRITERIA ECG</td>
                <td class="bg-blue"></td>
                <td class="bg-blue"></td>
            </tr>
            <tr>
                <td style="font-size: 9pt; text-align: left">&nbsp;&nbsp; a. ST Elevasi spesifik 1mm pada lead II, III, Avf; V3-V6, I, aVL</td>
                <td style="text-align: center">
                    <input type="checkbox" {{ isset($data['kcgA']) && $data['kcgA'] == 'Ya' ? 'checked' : '' }}  />
                </td>
                <td style="text-align: center">
                    <input type="checkbox" {{ isset($data['kcgA']) && $data['kcgA'] == 'Tidak' ? 'checked' : '' }}  />
                </td>
            </tr>
            <tr>
                <td style="font-size: 9pt; text-align: left">&nbsp;&nbsp; b. ST Depresi horizontal atau downslopping 1mm pada lead yang kompleks bersesuaian pada SKA</td>
                <td style="text-align: center">
                    <input type="checkbox" {{ isset($data['kcgB']) && $data['kcgB'] == 'Ya' ? 'checked' : '' }}  />
                </td>
                <td style="text-align: center">
                    <input type="checkbox" {{ isset($data['kcgB']) && $data['kcgB'] == 'Tidak' ? 'checked' : '' }}  />
                </td>
            </tr>
            <tr>
                <td style="font-size: 9pt; text-align: left">&nbsp;&nbsp; c. S1 Q3 T3 yang menyokong klinis emboli paru</td>
                <td style="text-align: center">
                    <input type="checkbox" {{ isset($data['kcgC']) && $data['kcgC'] == 'Ya' ? 'checked' : '' }}  />
                </td>
                <td style="text-align: center">
                    <input type="checkbox" {{ isset($data['kcgC']) && $data['kcgC'] == 'Tidak' ? 'checked' : '' }}  />
                </td>
            </tr>
            <tr>
                <td style="font-size: 9pt; text-align: left">&nbsp;&nbsp; d. PR depresi yang menyokong klinis pericarditis akut</td>
                <td style="text-align: center">
                    <input type="checkbox" {{ isset($data['kcgD']) && $data['kcgD'] == 'Ya' ? 'checked' : '' }}  />
                </td>
                <td style="text-align: center">
                    <input type="checkbox" {{ isset($data['kcgD']) && $data['kcgD'] == 'Tidak' ? 'checked' : '' }}  />
                </td>
            </tr>
            <tr>
                <td style="font-size: 9pt; text-align: left">&nbsp;&nbsp; e. LBBB pada IMA atau gagal jantung</td>
                <td style="text-align: center">
                    <input type="checkbox" {{ isset($data['kcgE']) && $data['kcgE'] == 'Ya' ? 'checked' : '' }}  />
                </td>
                <td style="text-align: center">
                    <input type="checkbox" {{ isset($data['kcgE']) && $data['kcgE'] == 'Tidak' ? 'checked' : '' }}  />
                </td>
            </tr>
            <tr>
                <td style="font-size: 9pt; text-align: left">&nbsp;&nbsp; f. RBBB dengan gambar saddle back</td>
                <td style="text-align: center">
                    <input type="checkbox" {{ isset($data['kcgF']) && $data['kcgF'] == 'Ya' ? 'checked' : '' }}  />
                </td>
                <td style="text-align: center">
                    <input type="checkbox" {{ isset($data['kcgF']) && $data['kcgF'] == 'Tidak' ? 'checked' : '' }}  />
                </td>
            </tr>
            <tr>
                <td style="font-size: 9pt; text-align: left">&nbsp;&nbsp; g. Syndrome brugada type I dan II</td>
                <td style="text-align: center">
                    <input type="checkbox" {{ isset($data['kcgG']) && $data['kcgG'] == 'Ya' ? 'checked' : '' }}  />
                </td>
                <td style="text-align: center">
                    <input type="checkbox" {{ isset($data['kcgG']) && $data['kcgG'] == 'Tidak' ? 'checked' : '' }}  />
                </td>
            </tr>
            <tr>
                <td style="font-size: 9pt; text-align: left">&nbsp;&nbsp; h. VT stabil</td>
                <td style="text-align: center">
                    <input type="checkbox" {{ isset($data['kcgH']) && $data['kcgH'] == 'Ya' ? 'checked' : '' }}  />
                </td>
                <td style="text-align: center">
                    <input type="checkbox" {{ isset($data['kcgH']) && $data['kcgH'] == 'Tidak' ? 'checked' : '' }}  />
                </td>
            </tr>
            <tr>
                <td style="font-size: 9pt; text-align: left">&nbsp;&nbsp; i. Bradicardi dengan HT &lt; 50x/menit</td>
                <td style="text-align: center">
                    <input type="checkbox" {{ isset($data['kcgI']) && $data['kcgI'] == 'Ya' ? 'checked' : '' }}  />
                </td>
                <td style="text-align: center">
                    <input type="checkbox" {{ isset($data['kcgI']) && $data['kcgI'] == 'Tidak' ? 'checked' : '' }}  />
                </td>
            </tr>
            <tr>
                <td style="font-size: 9pt; text-align: left">&nbsp;&nbsp; j. AF RVR >150x/menit</td>
                <td style="text-align: center">
                    <input type="checkbox" {{ isset($data['kcgJ']) && $data['kcgJ'] == 'Ya' ? 'checked' : '' }}  />
                </td>
                <td style="text-align: center">
                    <input type="checkbox" {{ isset($data['kcgJ']) && $data['kcgJ'] == 'Tidak' ? 'checked' : '' }}  />
                </td>
            </tr>
            <!-- END NILAI ECG -->

            <!-- NILAI Diagnosis -->
            <tr>
                <td style="font-size: 9pt; text-align: left" class="bg-blue">&nbsp;5. KRITERIA DIAGNOSIS</td>
                <td class="bg-blue"></td>
                <td class="bg-blue"></td>
            </tr>
            <tr>
                <td style="font-size: 9pt; text-align: left">&nbsp;&nbsp; a. Syok kardiogenik</td>
                <td style="text-align: center">
                    <input type="checkbox" {{ isset($data['diA']) && $data['diA'] == 'Ya' ? 'checked' : '' }}  />
                </td>
                <td style="text-align: center">
                    <input type="checkbox" {{ isset($data['diA']) && $data['diA'] == 'Tidak' ? 'checked' : '' }}  />
                </td>
            </tr>
            <tr>
                <td style="font-size: 9pt; text-align: left">&nbsp;&nbsp; b. Aritmia jantung yang mengancam jiwa sebagai akibat dari penyakit jantung iskemik, kardiomiopati, penyakit jantung reumatik, gangguan elektrolit, efek obat atau keracunan.</td>
                <td style="text-align: center">
                    <input type="checkbox" {{ isset($data['diB']) && $data['diB'] == 'Ya' ? 'checked' : '' }}  />
                </td>
                <td style="text-align: center">
                    <input type="checkbox" {{ isset($data['diB']) && $data['diB'] == 'Tidak' ? 'checked' : '' }}  />
                </td>
            </tr>
            <tr>
                <td style="font-size: 9pt; text-align: left">&nbsp;&nbsp;c. Edema paru akut yang tidak teratasi dengan terapi awal dan tergantung dari penyakit dasarnya.</td>
                <td style="text-align: center">
                    <input type="checkbox" {{ isset($data['diC']) && $data['diC'] == 'Ya' ? 'checked' : '' }}  />
                </td>
                <td style="text-align: center">
                    <input type="checkbox" {{ isset($data['diC']) && $data['diC'] == 'Tidak' ? 'checked' : '' }}  />
                </td>
            </tr>
            <tr>
                <td style="font-size: 9pt; text-align: left">&nbsp;&nbsp; d. Hipertensi emergency</td>
                <td style="text-align: center">
                    <input type="checkbox" {{ isset($data['diD']) && $data['diD'] == 'Ya' ? 'checked' : '' }}  />
                </td>
                <td style="text-align: center">
                    <input type="checkbox" {{ isset($data['diD']) && $data['diD'] == 'Tidak' ? 'checked' : '' }}  />
                </td>
            </tr>
            <tr>
                <td style="font-size: 9pt; text-align: left">&nbsp;&nbsp; e. Emboli paru masif</td>
                <td style="text-align: center">
                    <input type="checkbox" {{ isset($data['diE']) && $data['diE'] == 'Ya' ? 'checked' : '' }}  />
                </td>
                <td style="text-align: center">
                    <input type="checkbox" {{ isset($data['diE']) && $data['diE'] == 'Tidak' ? 'checked' : '' }}  />
                </td>
            </tr>
            <tr>
                <td style="font-size: 9pt; text-align: left">&nbsp;&nbsp; f. Hipertensi pulmonal</td>
                <td style="text-align: center">
                    <input type="checkbox" {{ isset($data['diF']) && $data['diF'] == 'Ya' ? 'checked' : '' }}  />
                </td>
                <td style="text-align: center">
                    <input type="checkbox" {{ isset($data['diF']) && $data['diF'] == 'Tidak' ? 'checked' : '' }}  />
                </td>
            </tr>
            <!-- END Diagnosis -->
                
            <!-- KESIMPULAN -->
                <tr>
                    @php
$v_petugas = isset($data['CBBidan']) ? $data['CBBidan']['label'] : "";
$tglv_pembuatan = isset($data['DTttd']) ? date('d-M-Y H:i', strtotime($data['DTttd'])) : "";
                    @endphp
                <td style="font-size: 9pt; text-align: left">
                    &nbsp; KESIMPULAN:
                    <br>
                    &nbsp; BERDASARKAN KONDISI DI ATAS MAKA MEMENUHI INDIKASI MASUK ICCU DENGAN PRIORITAS .................
                    <br>
                    &nbsp; Alat transport yang dibutuhkan :
                    &nbsp; <input style="margin-bottom: -5px;" type="checkbox" {{ isset($data['brancard']) && $data['brancard'] == 'Ya' ? 'checked' : '' }}  />
                            <span style="font-size: 10pt;" color="#000000" >Brancard</span>
                    &nbsp; <input style="margin-bottom: -5px;" type="checkbox" {{ isset($data['kursiroda']) && $data['kursiroda'] == 'Tidak' ? 'checked' : '' }}  />
                            <span style="font-size: 10pt;" color="#000000" >Kursi roda</span>
                    <br>
                    &nbsp; Pendamping selama transfer :
                    &nbsp; <input style="margin-bottom: -5px;" type="checkbox" {{ isset($data['docter']) && $data['docter'] == 'Ya' ? 'checked' : '' }}  />
                            <span style="font-size: 10pt;" color="#000000" >Dokter</span>
                    &nbsp; <input style="margin-bottom: -5px;" type="checkbox" {{ isset($data['paramedic']) && $data['paramedic'] == 'Tidak' ? 'checked' : '' }}  />
                            <span style="font-size: 10pt;" color="#000000" >Perawat</span>
                    &nbsp; <input style="margin-bottom: -5px;" type="checkbox" {{ isset($data['caregiver']) && $data['caregiver'] == 'Tidak' ? 'checked' : '' }}  />
                                <span style="font-size: 10pt;" color="#000000" >Asisten perawat</span>
                    <br>
                    &nbsp; Alat medis yang diperlukan selama transfer:
                    <br>
                    &nbsp; <input style="margin-bottom: -5px;" type="checkbox" {{ isset($data['hooh']) && $data['hooh'] == 'Ya' ? 'checked' : '' }}  />
                            <span style="font-size: 10pt;" color="#000000" >Ya, Sebutkan</span>
                    &nbsp; {{ isset($data['textAreaValue']) ? $data['textAreaValue'] : '...' }}
                    <br>
                    &nbsp; <input style="margin-bottom: -5px;" type="checkbox" {{ isset($data['ora']) && $data['ora'] == 'Tidak' ? 'checked' : '' }}  />
                            <span style="font-size: 10pt;" color="#000000" >Tidak</span>
                </td>
                <td colspan="2" style="text-align: center; font-size: 9pt;">
                    <p style="white-space: pre-line; margin-bottom: -15px; margin-top: -10px;">
                        Garut, {{ $date->format('Y-m-d') }} 
                        {{ $date->format('H:i') }} WIB
                    </p>
                    <br>
                    <img src="data:image/png;base64, {!! $qrcode !!}">
                    <br>
                    <p>{{ $data['dokter']['label'] }}</p>
                </td>
                </tr>
            <!-- END KESIMPULAN -->
        </table>
        {{-- <hr style="border:2px solid #000;margin-bottom:0px">
        <hr style="border:0.5px solid #000;margin-top:2px">
        <hr style="border:0.5px solid #000;margin-top:2px"> --}}
    </section>
</body>

</html>
