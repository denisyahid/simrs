<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>EMR - Formulir Kriteria Masuk High Care Unit</title>
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

        .bg-blue {
            background-color: #91CEDE;
        }

        .bg-gray {
            background-color: #CCCCCC;
        }

        .border-doang td {
            padding: 5px;
        }

    </style>

    @stack('style')

</head>

<body class="A4" style="font-family:Tahoma;height: auto" onload="window.print()">
    <section class="sheet padding-10mm" style="font-family:Tahoma;height: auto;overflow: hidden;">
        <table width="100%" cellspacing="0" cellpadding="0" border="1">
            <tr>
                <td width="60%" style="text-align:right" colspan=2>
                    <table width="100%" cellspacing="0" cellpadding="0" border="0">
                        <tr class="bg-blue">
                            <td>
                                <td width="50%" style="text-align:left; font-size: 14px">RSUD BALI MANDARA</td>
                                <td width="50%" style="font-size: 14px; text-align: right;">RM 1A/IRIT/01</td>
                            </td>
                        </tr>
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
                <td style="text-align: center" style="border-right: 1px solid black;">
                    <b>
                        <span style="font-size: 16px">KRITERIA MASUK
                        </span><br>
                        <br>
                        <span style="font-size: 16px">HIGH CARE UNIT</span>
                    </b>
                </td>
                <td width="50%" style="padding: 10px; border-left: 1px solid black;">
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
            <thead>
                <tr>
                    <th width = "70%" style="font-size: 14; text-align: center">Kriteria Fisiologis</th>
                    <th width = "15%" style="font-size: 14; text-align: center">YA</th>
                    <th width = "15%" style="font-size: 14; text-align: center">TIDAK</th>
                </tr>
            </thead>
            <tbody>
                <!-- Vital Sign -->
                <tr class="bg-gray">
                    <td style="font-size: 9pt; text-align: left">&nbsp;1 Vital Sign</td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td style="font-size: 9pt; text-align: left">&nbsp;&nbsp; Nilai pemantauan EWS oranye, merah dengan distres napas</td>
                    <td style="text-align: center">
                        <input type="checkbox" {{ isset($data['EWS']) && $data['EWS'] == 'Ya' ? 'checked' : '' }}  />
                    </td>
                    <td style="text-align: center">
                        <input type="checkbox" {{ isset($data['EWS']) && $data['EWS'] == 'Tidak' ? 'checked' : '' }}  />
                    </td>
                </tr>
                <!-- END Vital Sign -->

                <!-- PEMERIKSAAN FISIK -->
                 <tr class="bg-gray">
                    <td style="font-size: 9pt; text-align: left">&nbsp;2. PEMERIKSAAN FISIK</td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td style="font-size: 9pt; text-align: left">&nbsp;&nbsp; a. Oliguria - Anuria</td>
                    <td style="text-align: center">
                        <input type="checkbox" {{ isset($data['oliguria']) && $data['oliguria'] == 'Ya' ? 'checked' : '' }}  />
                    </td>
                    <td style="text-align: center">
                        <input type="checkbox" {{ isset($data['oliguria']) && $data['oliguria'] == 'Tidak' ? 'checked' : '' }}  />
                    </td>
                </tr>
                <tr>
                    <td style="font-size: 9pt; text-align: left">&nbsp;&nbsp; b. Klinis dehidrasi berat</td>
                    <td style="text-align: center">
                        <input type="checkbox" {{ isset($data['dehidrasi']) && $data['dehidrasi'] == 'Ya' ? 'checked' : '' }}  />
                    </td>
                    <td style="text-align: center">
                        <input type="checkbox" {{ isset($data['dehidrasi']) && $data['dehidrasi'] == 'Tidak' ? 'checked' : '' }}  />
                    </td>
                </tr>
                <tr>
                    <td style="font-size: 9pt; text-align: left">&nbsp;&nbsp; c. Kejang berulang atau status epileptikus</td>
                    <td style="text-align: center">
                        <input type="checkbox" {{ isset($data['kejang']) && $data['kejang'] == 'Ya' ? 'checked' : '' }}  />
                    </td>
                    <td style="text-align: center">
                        <input type="checkbox" {{ isset($data['kejang']) && $data['kejang'] == 'Tidak' ? 'checked' : '' }}  />
                    </td>
                </tr>
                <tr>
                    <td style="font-size: 9pt; text-align: left">&nbsp;&nbsp; d. Tamponade jantung atau pneumothorax dengan gangguan hemodinamik</td>
                    <td style="text-align: center">
                        <input type="checkbox" {{ isset($data['tamponade']) && $data['tamponade'] == 'Ya' ? 'checked' : '' }}  />
                    </td>
                    <td style="text-align: center">
                        <input type="checkbox" {{ isset($data['tamponade']) && $data['tamponade'] == 'Tidak' ? 'checked' : '' }}  />
                    </td>
                </tr>
                <tr>
                    <td style="font-size: 9pt; text-align: left">&nbsp;&nbsp; e. Overload cairan</td>
                    <td style="text-align: center">
                        <input type="checkbox" {{ isset($data['overload']) && $data['overload'] == 'Ya' ? 'checked' : '' }}  />
                    </td>
                    <td style="text-align: center">
                        <input type="checkbox" {{ isset($data['overload']) && $data['overload'] == 'Tidak' ? 'checked' : '' }}  />
                    </td>
                </tr>
                <!-- END PEMERIKSAAN FISIK -->

                <!-- ECG -->
                <tr class="bg-gray">
                    <td style="font-size: 9pt; text-align: left">&nbsp;3. ECG</td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td style="font-size: 9pt; text-align: left">&nbsp;&nbsp; a. Infark miokard dengan aritmia kompleks, gagal jantung kongestif dengan gangguan hemodinamik</td>
                    <td style="text-align: center">
                        <input type="checkbox" {{ isset($data['Infark']) && $data['Infark'] == 'Ya' ? 'checked' : '' }}  />
                    </td>
                    <td style="text-align: center">
                        <input type="checkbox" {{ isset($data['Infark']) && $data['Infark'] == 'Tidak' ? 'checked' : '' }}  />
                    </td>
                </tr>
                <!-- END ECG -->

                <!-- NILAI LABOR -->
                <tr class="bg-gray">
                    <td style="font-size: 9pt; text-align: left">&nbsp;4. NILAI LABORATORIUM</td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td style="font-size: 9pt; text-align: left">&nbsp;&nbsp; a. Kadar natrium darah < 125 mmol/L, Hipernatremi > 155 mmol/L</td>
                    <td style="text-align: center">
                        <input type="checkbox" {{ isset($data['Kadar']) && $data['Kadar'] == 'Ya' ? 'checked' : '' }}  />
                    </td>
                    <td style="text-align: center">
                        <input type="checkbox" {{ isset($data['Kadar']) && $data['Kadar'] == 'Tidak' ? 'checked' : '' }}  />
                    </td>
                </tr>
                <tr>
                    <td style="font-size: 9pt; text-align: left">&nbsp;&nbsp; b. Kadar kalium serum < 2,5 mmol/L, Hiperkalemi > 5 mmol/L</td>
                    <td style="text-align: center">
                        <input type="checkbox" {{ isset($data['kalium']) && $data['kalium'] == 'Ya' ? 'checked' : '' }}  />
                    </td>
                    <td style="text-align: center">
                        <input type="checkbox" {{ isset($data['kalium']) && $data['kalium'] == 'Tidak' ? 'checked' : '' }}  />
                    </td>
                </tr>
                <tr>
                    <td style="font-size: 9pt; text-align: left">&nbsp;&nbsp; c. PaO2  50 mmHg dengan oksigen ruangan</td>
                    <td style="text-align: center">
                        <input type="checkbox" {{ isset($data['oksigen']) && $data['oksigen'] == 'Ya' ? 'checked' : '' }}  />
                    </td>
                    <td style="text-align: center">
                        <input type="checkbox" {{ isset($data['oksigen']) && $data['oksigen'] == 'Tidak' ? 'checked' : '' }}  />
                    </td>
                </tr>
                <tr>
                    <td style="font-size: 9pt; text-align: left">&nbsp;&nbsp; d. pH  7,1 atau > 7,7 dengan oksigen ruangan</td>
                    <td style="text-align: center">
                        <input type="checkbox" {{ isset($data['ruangan']) && $data['ruangan'] == 'Ya' ? 'checked' : '' }}  />
                    </td>
                    <td style="text-align: center">
                        <input type="checkbox" {{ isset($data['ruangan']) && $data['ruangan'] == 'Tidak' ? 'checked' : '' }}  />
                    </td>
                </tr>
                <tr>
                    <td style="font-size: 9pt; text-align: left">&nbsp;&nbsp; e. Serum glukosa > 800 mg/dl</td>
                    <td style="text-align: center">
                        <input type="checkbox" {{ isset($data['Serum']) && $data['Serum'] == 'Ya' ? 'checked' : '' }}  />
                    </td>
                    <td style="text-align: center">
                        <input type="checkbox" {{ isset($data['Serum']) && $data['Serum'] == 'Tidak' ? 'checked' : '' }}  />
                    </td>
                </tr>
                <tr>
                    <td style="font-size: 9pt; text-align: left">&nbsp;&nbsp; f. Serum kalsium > 15 mg/dl</td>
                    <td style="text-align: center">
                        <input type="checkbox" {{ isset($data['kalsium']) && $data['kalsium'] == 'Ya' ? 'checked' : '' }}  />
                    </td>
                    <td style="text-align: center">
                        <input type="checkbox" {{ isset($data['kalsium']) && $data['kalsium'] == 'Tidak' ? 'checked' : '' }}  />
                    </td>
                </tr>
                <tr>
                    <td style="font-size: 9pt; text-align: left">&nbsp;&nbsp; g. Kadar keton urin > + 3</td>
                    <td style="text-align: center">
                        <input type="checkbox" {{ isset($data['urin']) && $data['urin'] == 'Ya' ? 'checked' : '' }}  />
                    </td>
                    <td style="text-align: center">
                        <input type="checkbox" {{ isset($data['urin']) && $data['urin'] == 'Tidak' ? 'checked' : '' }}  />
                    </td>
                </tr>
                <tr>
                    <td style="font-size: 9pt; text-align: left">&nbsp;&nbsp; h. Kadar BUN/SC darah yang membutuhkan RRTsegera</td>
                    <td style="text-align: center">
                        <input type="checkbox" {{ isset($data['darah']) && $data['darah'] == 'Ya' ? 'checked' : '' }}  />
                    </td>
                    <td style="text-align: center">
                        <input type="checkbox" {{ isset($data['darah']) && $data['darah'] == 'Tidak' ? 'checked' : '' }}  />
                    </td>
                </tr>
                <tr>
                    <td style="font-size: 9pt; text-align: left">&nbsp;&nbsp; i. Kadar obat atau substansi kimia dalam darah telah melebihi dosis toksis yang mengganggu hemodinamik dan berpotensi mengancam nyawa (alergi/anafilaksis)</td>
                    <td style="text-align: center">
                        <input type="checkbox" {{ isset($data['obat']) && $data['obat'] == 'Ya' ? 'checked' : '' }}  />
                    </td>
                    <td style="text-align: center">
                        <input type="checkbox" {{ isset($data['obat']) && $data['obat'] == 'Tidak' ? 'checked' : '' }}  />
                    </td>
                </tr>
                <!-- NILAI LABOR -->

                <!-- NILAI RADIOLOGI -->
                <tr class="bg-gray">
                    <td style="font-size: 9pt; text-align: left">&nbsp;5. NILAI RADIOLOGI</td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td style="font-size: 9pt; text-align: left">&nbsp;&nbsp; a. Gambaran CT Scan abnormal yang berpotensi mengancam nyawa (perdarahan cerebral, contusion atau perdarahan subarachnoid) dengan atau tanpa penurunan status mental</td>
                    <td style="text-align: center">
                        <input type="checkbox" {{ isset($data['CT']) && $data['CT'] == 'Ya' ? 'checked' : '' }}  />
                    </td>
                    <td style="text-align: center">
                        <input type="checkbox" {{ isset($data['CT']) && $data['CT'] == 'Tidak' ? 'checked' : '' }}  />
                    </td>
                </tr>
                <tr>
                    <td style="font-size: 9pt; text-align: left">&nbsp;&nbsp; b. Ruptur viscera, blader, liver, varices esofagus, perdarahan dengan atau tanpa gangguan hemodinamik</td>
                    <td style="text-align: center">
                        <input type="checkbox" {{ isset($data['blader']) && $data['blader'] == 'Ya' ? 'checked' : '' }}  />
                    </td>
                    <td style="text-align: center">
                        <input type="checkbox" {{ isset($data['blader']) && $data['blader'] == 'Tidak' ? 'checked' : '' }}  />
                    </td>
                </tr>
                <!-- END NILAI RADIOLOGI -->

                 <!-- KEBUTUHAN MONITORING -->
                 <tr class="bg-gray">
                    <td style="font-size: 9pt; text-align: left">&nbsp;6. Pasien dengan keperluan persiapan dan pemantauan pra-intra-paska operasi</td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td>
                        &nbsp;
                        <span style="font-size: 9pt;">{{ isset($data['persiapanText1']) ? $data['persiapanText1'] : '' }}</span>
                    </td>
                    <td colspan="2">
                        &nbsp;
                        <span style="font-size: 9pt;">{{ isset($data['persiapanText2']) ? $data['persiapanText2'] : '' }}</span>
                    </td>
                </tr>
                <!-- END KEBUTUHAN MONITORING -->
                 
                <!-- KESIMPULAN -->
                 <tr>
                        @php
                            $v_petugas = isset($data['CBBidan']) ? $data['CBBidan']['label'] : "";
                            $tglv_pembuatan = isset($data['tanggal']) ? date('d-M-Y', strtotime($data['tanggal'])) : "";
                            $jamv_pembuatan = isset($data['tanggal']) ? date('H:i', strtotime($data['tanggal'])) : "";
                            @endphp
                    <td style="font-size: 9pt; text-align: left">
                        &nbsp; KESIMPULAN:
                        <br>
                        &nbsp; Memenuhi indikasi masuk HCU
                        <br>
                        &nbsp; {{ isset($data['hcu']) ? $data['hcu'] : '' }}
                        <br>
                        &nbsp; Alat transport yang dibutuhkan :
                        &nbsp; <input style="margin-bottom: -5px;" type="checkbox" {{ isset($data['alat']) && $data['alat'] == 'Ya' ? 'checked' : '' }}  />
                                <span style="font-size: 10pt;" color="#000000" >Brancard</span>
                        &nbsp; <input style="margin-bottom: -5px;" type="checkbox" {{ isset($data['alat']) && $data['alat'] == 'Tidak' ? 'checked' : '' }}  />
                                <span style="font-size: 10pt;" color="#000000" >Kursi roda</span>
                        <br>
                        &nbsp; Pendamping selama transport :
                        &nbsp; <input style="margin-bottom: -5px;" type="checkbox" {{ isset($data['pendampingDokter']) && $data['pendampingDokter'] == 'Ya' ? 'checked' : '' }}  />
                                <span style="font-size: 10pt;" color="#000000" >Dokter</span>
                        &nbsp; <input style="margin-bottom: -5px;" type="checkbox" {{ isset($data['pendampingPerawat']) && $data['pendampingPerawat'] == 'Tidak' ? 'checked' : '' }}  />
                                <span style="font-size: 10pt;" color="#000000" >Perawat</span>
                        &nbsp; <input style="margin-bottom: -5px;" type="checkbox" {{ isset($data['pendampingCG']) && $data['pendampingCG'] == 'Tidak' ? 'checked' : '' }}  />
                                   <span style="font-size: 10pt;" color="#000000" >Asisten perawat</span>
                        <br>
                        &nbsp; Alat medis yang diperlukan selama transport:
                        <br>
                        &nbsp; <input style="margin-bottom: -5px;" type="checkbox" {{ isset($data['perluAlatMedis']) && $data['perluAlatMedis'] == 'Ya' ? 'checked' : '' }}  />
                                <span style="font-size: 10pt;" color="#000000" >Ya, Sebutkan :</span>
                        &nbsp; {{ isset($data['perluAlatMedisDetail']) ? $data['perluAlatMedisDetail'] : '...' }}
                        <br>
                        &nbsp; <input style="margin-bottom: -5px;" type="checkbox" {{ isset($data['perluAlatMedis']) && $data['perluAlatMedis'] == 'Tidak' ? 'checked' : '' }}  />
                                <span style="font-size: 10pt;" color="#000000" >Tidak</span>
                    </td>
                    <td colspan = "2" style="text-align: center; font-size:14px;">
                        <p style="white-space: pre-line; margin: 0;">
                            Garut, {{ $tglv_pembuatan }}
                            Jam {{ $jamv_pembuatan }} WIB
                        </p>
                        <br>
                        <img src="data:image/png;base64, {!! $qrcode !!}">
                        <br>
                        <p style="font-size: 12px;">{{ $data['pegawai']['label'] }}</p>
                        {{ $v_petugas }}
                    </td>
                 </tr>
                <!-- END KESIMPULAN -->

            </tbody>
        </table>
        {{-- <hr style="border:2px solid #000;margin-bottom:0px">
        <hr style="border:0.5px solid #000;margin-top:2px">
        <hr style="border:0.5px solid #000;margin-top:2px"> --}}
    </section>
</body>

</html>