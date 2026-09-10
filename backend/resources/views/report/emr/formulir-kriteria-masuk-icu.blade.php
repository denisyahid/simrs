<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>EMR - Formulir Kriteria Masuk Intensive Care Unit</title>
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

        .border-doang td {
            padding: 5px;
        }

        .bg-gray {
            background-color: #DCDCDC;
        }

        .bg-blue {
            background-color: #91CEDE;
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

        <table width="100%" cellspacing="0" cellpadding="0" border="0">
            <tr>
                <td width="10%" style="padding: 5px; border-right: 1px solid black;">
                    <img src="{{ 'img/logo-rs.png' }}" style="width: 90px;">
                </td>
                <td style="text-align: center; border-right: 1px solid black;">
                    <b>
                        <span style="font-size: 16px">KRITERIA MASUK
                        </span><br>
                        <br>
                        <span style="font-size: 16px">INTENSIVE CARE UNIT</span>
                    </b>
                </td>
                <td width="50%" style="padding: 10px">
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
                    <td style="font-size: 9pt; text-align: left">&nbsp;&nbsp; Nilai pemantauan EWS oranye, merah dengan distres napas dan ancaman gagal napas</td>
                    <td style="text-align: center">
                        <input type="checkbox" {{ isset($data['vitalSign']) && $data['vitalSign'] == 'YA' ? 'checked' : '' }}  />
                    </td>
                    <td style="text-align: center">
                        <input type="checkbox" {{ isset($data['vitalSign']) && $data['vitalSign'] == 'TIDAK' ? 'checked' : '' }}  />
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
                        <input type="checkbox" {{ isset($data['oliguriaAnuria']) && $data['oliguriaAnuria'] == 'YA' ? 'checked' : '' }}  />
                    </td>
                    <td style="text-align: center">
                        <input type="checkbox" {{ isset($data['oliguriaAnuria']) && $data['oliguriaAnuria'] == 'TIDAK' ? 'checked' : '' }}  />
                    </td>
                </tr>
                <tr>
                    <td style="font-size: 9pt; text-align: left">&nbsp;&nbsp; b. Obstruksi saluran napas</td>
                    <td style="text-align: center">
                        <input type="checkbox" {{ isset($data['obstruksiSaluranNapas']) && $data['obstruksiSaluranNapas'] == 'YA' ? 'checked' : '' }}  />
                    </td>
                    <td style="text-align: center">
                        <input type="checkbox" {{ isset($data['obstruksiSaluranNapas']) && $data['obstruksiSaluranNapas'] == 'TIDAK' ? 'checked' : '' }}  />
                    </td>
                </tr>
                <tr>
                    <td style="font-size: 9pt; text-align: left">&nbsp;&nbsp; c. Kejang berulang atau status epileptikus</td>
                    <td style="text-align: center">
                        <input type="checkbox" {{ isset($data['kejangBerulang']) && $data['kejangBerulang'] == 'YA' ? 'checked' : '' }}  />
                    </td>
                    <td style="text-align: center">
                        <input type="checkbox" {{ isset($data['kejangBerulang']) && $data['kejangBerulang'] == 'TIDAK' ? 'checked' : '' }}  />
                    </td>
                </tr>
                <tr>
                    <td style="font-size: 9pt; text-align: left">&nbsp;&nbsp; d. Tamponade jantung atau pneumothorax dengan gangguan hemodinamik</td>
                    <td style="text-align: center">
                        <input type="checkbox" {{ isset($data['tamponadeJantung']) && $data['tamponadeJantung'] == 'YA' ? 'checked' : '' }}  />
                    </td>
                    <td style="text-align: center">
                        <input type="checkbox" {{ isset($data['tamponadeJantung']) && $data['tamponadeJantung'] == 'TIDAK' ? 'checked' : '' }}  />
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
                        <input type="checkbox" {{ isset($data['infarkMiokard']) && $data['infarkMiokard'] == 'YA' ? 'checked' : '' }}  />
                    </td>
                    <td style="text-align: center">
                        <input type="checkbox" {{ isset($data['infarkMiokard']) && $data['infarkMiokard'] == 'TIDAK' ? 'checked' : '' }}  />
                    </td>
                </tr>
                <tr>
                    <td style="font-size: 9pt; text-align: left">&nbsp;&nbsp; b. Ventrikular takikardi atau ventrikular fibrilasi dengan gangguan hemodinamik</td>
                    <td style="text-align: center">
                        <input type="checkbox" {{ isset($data['ventrikularTakikardi']) && $data['ventrikularTakikardi'] == 'YA' ? 'checked' : '' }}  />
                    </td>
                    <td style="text-align: center">
                        <input type="checkbox" {{ isset($data['ventrikularTakikardi']) && $data['ventrikularTakikardi'] == 'TIDAK' ? 'checked' : '' }}  />
                    </td>
                </tr>
                <tr>
                    <td style="font-size: 9pt; text-align: left">&nbsp;&nbsp; c. Blok jantung komplit dengan gangguan hemodinamik</td>
                    <td style="text-align: center">
                        <input type="checkbox" {{ isset($data['blokJantungKomplit']) && $data['blokJantungKomplit'] == 'YA' ? 'checked' : '' }}  />
                    </td>
                    <td style="text-align: center">
                        <input type="checkbox" {{ isset($data['blokJantungKomplit']) && $data['blokJantungKomplit'] == 'TIDAK' ? 'checked' : '' }}  />
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
                    <td style="font-size: 9pt; text-align: left">&nbsp;&nbsp; a. Kadar natrium serum < 110 mmol/Latau > 170 mmol/L</td>
                    <td style="text-align: center">
                        <input type="checkbox" {{ isset($data['kadarNatrium']) && $data['kadarNatrium'] == 'YA' ? 'checked' : '' }}  />
                    </td>
                    <td style="text-align: center">
                        <input type="checkbox" {{ isset($data['kadarNatrium']) && $data['kadarNatrium'] == 'TIDAK' ? 'checked' : '' }}  />
                    </td>
                </tr>
                <tr>
                    <td style="font-size: 9pt; text-align: left">&nbsp;&nbsp; b. Kadar kalium serum < 2,0 mmol/Latau > 7,0 mmol/L</td>
                    <td style="text-align: center">
                        <input type="checkbox" {{ isset($data['kadarKalium']) && $data['kadarKalium'] == 'YA' ? 'checked' : '' }}  />
                    </td>
                    <td style="text-align: center">
                        <input type="checkbox" {{ isset($data['kadarKalium']) && $data['kadarKalium'] == 'TIDAK' ? 'checked' : '' }}  />
                    </td>
                </tr>
                <tr>
                    <td style="font-size: 9pt; text-align: left">&nbsp;&nbsp; c. PaO2 < 50 mmHg pada oksigen ruangan</td>
                    <td style="text-align: center">
                        <input type="checkbox" {{ isset($data['paO2']) && $data['paO2'] == 'YA' ? 'checked' : '' }}  />
                    </td>
                    <td style="text-align: center">
                        <input type="checkbox" {{ isset($data['paO2']) && $data['paO2'] == 'TIDAK' ? 'checked' : '' }}  />
                    </td>
                </tr>
                <tr>
                    <td style="font-size: 9pt; text-align: left">&nbsp;&nbsp; d. pH < 7,1 atau > 7,7 dengan oksigen ruangan</td>
                    <td style="text-align: center">
                        <input type="checkbox" {{ isset($data['ph']) && $data['ph'] == 'YA' ? 'checked' : '' }}  />
                    </td>
                    <td style="text-align: center">
                        <input type="checkbox" {{ isset($data['ph']) && $data['ph'] == 'TIDAK' ? 'checked' : '' }}  />
                    </td>
                </tr>
                <tr>
                    <td style="font-size: 9pt; text-align: left">&nbsp;&nbsp; e. Serum glukosa > 800 mg/dl</td>
                    <td style="text-align: center">
                        <input type="checkbox" {{ isset($data['serumGlukosa']) && $data['serumGlukosa'] == 'YA' ? 'checked' : '' }}  />
                    </td>
                    <td style="text-align: center">
                        <input type="checkbox" {{ isset($data['serumGlukosa']) && $data['serumGlukosa'] == 'TIDAK' ? 'checked' : '' }}  />
                    </td>
                </tr>
                <tr>
                    <td style="font-size: 9pt; text-align: left">&nbsp;&nbsp; f. Serum kalsium > 15 mg/dl</td>
                    <td style="text-align: center">
                        <input type="checkbox" {{ isset($data['serumKalsium']) && $data['serumKalsium'] == 'YA' ? 'checked' : '' }}  />
                    </td>
                    <td style="text-align: center">
                        <input type="checkbox" {{ isset($data['serumKalsium']) && $data['serumKalsium'] == 'TIDAK' ? 'checked' : '' }}  />
                    </td>
                </tr>
                <tr>
                    <td style="font-size: 9pt; text-align: left">&nbsp;&nbsp; g. Kadar obat atau substansi kimia dalam darah telah melebihi dosis toksis yang mengganggu hemodinamik dan status neurologis</td>
                    <td style="text-align: center">
                        <input type="checkbox" {{ isset($data['kadarObat']) && $data['kadarObat'] == 'YA' ? 'checked' : '' }}  />
                    </td>
                    <td style="text-align: center">
                        <input type="checkbox" {{ isset($data['kadarObat']) && $data['kadarObat'] == 'TIDAK' ? 'checked' : '' }}  />
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
                    <td style="font-size: 9pt; text-align: left">&nbsp;&nbsp; a. Gambaran CT Scan abnormal (Perdarahan cerebral, contusion atau perdarahan subarachnoid) dengan penurunan status mental</td>
                    <td style="text-align: center">
                        <input type="checkbox" {{ isset($data['ctScanAbnormal']) && $data['ctScanAbnormal'] == 'YA' ? 'checked' : '' }}  />
                    </td>
                    <td style="text-align: center">
                        <input type="checkbox" {{ isset($data['ctScanAbnormal']) && $data['ctScanAbnormal'] == 'TIDAK' ? 'checked' : '' }}  />
                    </td>
                </tr>
                <tr>
                    <td style="font-size: 9pt; text-align: left">&nbsp;&nbsp; b. Ruptur viscera, blader, liver, varices esofagus, perdarahan saluran cerna dengan gangguan hemodinamik</td>
                    <td style="text-align: center">
                        <input type="checkbox" {{ isset($data['rupturViscera']) && $data['rupturViscera'] == 'YA' ? 'checked' : '' }}  />
                    </td>
                    <td style="text-align: center">
                        <input type="checkbox" {{ isset($data['rupturViscera']) && $data['rupturViscera'] == 'TIDAK' ? 'checked' : '' }}  />
                    </td>
                </tr>
                <tr>
                    <td style="font-size: 9pt; text-align: left">&nbsp;&nbsp; c. Diseksi aorta aneurisma</td>
                    <td style="text-align: center">
                        <input type="checkbox" {{ isset($data['diseksiAorta']) && $data['diseksiAorta'] == 'YA' ? 'checked' : '' }}  />
                    </td>
                    <td style="text-align: center">
                        <input type="checkbox" {{ isset($data['diseksiAorta']) && $data['diseksiAorta'] == 'TIDAK' ? 'checked' : '' }}  />
                    </td>
                </tr>
                <!-- END NILAI RADIOLOGI -->

                 <!-- KEBUTUHAN MONITORING -->
                 <tr class="bg-gray">
                    <td style="font-size: 9pt; text-align: left">&nbsp;6. Kebutuhan monitoring intensif Pasca Pembedahan Sedang atau Pembedahan Mayor</td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td style="font-size: 9pt; text-align: left"> &nbsp;{{ isset($data['kebutuhanMonitoringIntesif1']) ? $data['kebutuhanMonitoringIntesif1'] : ""}} </td>
                    <td colspan = "2" style="font-size: 9pt; text-align: left"> &nbsp;{{isset($data['kebutuhanMonitoringIntesif2']) ? $data['kebutuhanMonitoringIntesif2'] : ""}} </td>
                </tr>
                <!-- END KEBUTUHAN MONITORING -->
                 
                <!-- KESIMPULAN -->
                 <tr>
                        @php
                            $tglv_pembuatan = isset($data['tglPembuatan']) ? date('d-M-Y H:i', strtotime($data['tglPembuatan'])) : "";
                        @endphp
                    <td style="font-size: 9pt; text-align: left">
                        &nbsp; KESIMPULAN:
                        <br>
                        &nbsp; Memenuhi indikasi masuk ICU dengan Prioritas
                        &nbsp; <input style="margin-bottom: -5px;" type="checkbox" {{ isset($data['memenuhiIndikasiMasukICU']) && $data['memenuhiIndikasiMasukICU'] == '1' ? 'checked' : '' }}  />
                                <span style="font-size: 10pt;" color="#000000" >1</span>
                        &nbsp; <input style="margin-bottom: -5px;" type="checkbox" {{ isset($data['memenuhiIndikasiMasukICU']) && $data['memenuhiIndikasiMasukICU'] == '2' ? 'checked' : '' }}  />
                                <span style="font-size: 10pt;" color="#000000" >2</span>
                        &nbsp; <input style="margin-bottom: -5px;" type="checkbox" {{ isset($data['memenuhiIndikasiMasukICU']) && $data['memenuhiIndikasiMasukICU'] == '3' ? 'checked' : '' }}  />
                                <span style="font-size: 10pt;" color="#000000" >3</span>
                        <br>
                        <span style="margin-left: 5px;">
                            {{ isset($data['keteranganMemenuhiIndikasiMasukICU']) ? $data['keteranganMemenuhiIndikasiMasukICU'] : ""}}
                        </span>
                        <br>
                        &nbsp; Alat transport yang dibutuhkan :
                        &nbsp; <input style="margin-bottom: -5px;" type="checkbox" {{ isset($data['alatTransport']) && $data['alatTransport'] == 'Brancard' ? 'checked' : '' }}  />
                                <span style="font-size: 10pt;" color="#000000" >Brancard</span>
                        &nbsp; <input style="margin-bottom: -5px;" type="checkbox" {{ isset($data['alatTransport']) && $data['alatTransport'] == 'Kursi Roda' ? 'checked' : '' }}  />
                                <span style="font-size: 10pt;" color="#000000" >Kursi roda</span>
                        <br>
                        &nbsp; Pendamping selama transport :
                        &nbsp; <input style="margin-bottom: -5px;" type="checkbox" {{ isset($data['PSTDokter']) && $data['PSTDokter'] == 'Dokter' ? 'checked' : '' }}  />
                                <span style="font-size: 10pt;" color="#000000" >Dokter</span>
                        &nbsp; <input style="margin-bottom: -5px;" type="checkbox" {{ isset($data['PSTPerawat']) && $data['PSTPerawat'] == 'Perawat' ? 'checked' : '' }}  />
                                <span style="font-size: 10pt;" color="#000000" >Perawat</span>
                        &nbsp; <input style="margin-bottom: -5px;" type="checkbox" {{ isset($data['PSTCaregiver']) && $data['PSTCaregiver'] == 'Caregiver/POS' ? 'checked' : '' }}  />
                                   <span style="font-size: 10pt;" color="#000000" >Asisten perawat</span>
                        <br>
                        &nbsp; Alat medis yang diperlukan selama transport:
                        &nbsp; <input style="margin-bottom: -5px;" type="checkbox" {{ isset($data['alatMedis']) && $data['alatMedis'] == 'Tidak' ? 'checked' : '' }}  />
                                <span style="font-size: 10pt;" color="#000000" >Tidak</span>
                        &nbsp; <input style="margin-bottom: -5px;" type="checkbox" {{ isset($data['alatMedis']) && $data['alatMedis'] == 'Ya' ? 'checked' : '' }}  />
                                <span style="font-size: 10pt;" color="#000000" >Ya, Sebutkan</span>
                        &nbsp; {{ isset($data['alatMedisDetail']) ? $data['alatMedisDetail'] : '...' }}
                    </td>
                    <td colspan = "2" style="text-align: center; font-size: 12px; border-left: 1px solid black;">
                        Garut, {{ $tglv_pembuatan }} WIB
                        <br>
                        <img src="data:image/png;base64, {!! $qrcode !!}">
                        <br>
                        <p style="font-size: 12px;">
                            @isset($data['dokterBertugas']['label'])
                                {{ $data['dokterBertugas']['label'] }}
                            @endisset 
                        </p>
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
