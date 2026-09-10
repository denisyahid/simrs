<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>EMR - Formulir Kriteria Trombolisis</title>
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

        font {
            margin-left: 5px;
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

        .bg-blue {
            background-color: #00A1E9;
        }
        .bg-gray {
            background-color: #D9D9D9;
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

        .bg-head {
            background-color: #D5DCE4;
        }

    </style>

    @stack('style')

</head>

<body class="A4" style="font-family:Tahoma;height: auto" onload="window.print()">
    <section class="sheet padding-10mm" style="font-family:Tahoma;height: auto;overflow: hidden;">
        <table width="100%" cellspacing="0" cellpadding="0" border="0">
            <tr>
                <td colspan="3" style="text-align: center; border-bottom: none;">
                    <img src="{{ 'img/kop-surat.jpg' }}" alt="kop" width="600px">
                </td>
            </tr>
            <tr>
                <td colspan="3" style="text-align: center; border-top: none; border-bottom: none;">
                    <span style="white-space: pre-line; vertical-align: top;">
                        KRITERIA TROMBOLISIS
                        RUMAH SAKIT UMUM DAERAH BALI MANDARA
                        PROVINSI BALI
                    </span>                    
                    <br>
                </td>
            </tr>
            <tr>
                <td colspan="3" style="text-align: center; border-top: none;">
                    NOMOR:<span style="color: #FF0000;">B.37.188.4/46707/HHP/RSBM</span>
                </td>
            </tr>
            <tr>
                <td colspan="3" style="text-align: center; border-top: none;">
                    <br>
                </td>
            </tr>
        </table>






        <table width="100%" cellspacing="0" cellpadding="0" border="1">
            <tbody>
                <tr class="tr">
                    <td class="td bg-head" width="6%" height="42"
                        style="text-align: center; vertical-align: middle;">
                        <font style="font-size: 18px"><strong>NO</strong>
                        </font>
                    </td>
                    <td class="td bg-head" height="42" colspan="2"
                        style="text-align: center; vertical-align: middle;">
                        <font style="font-size: 18px">
                            <strong>Kriteria Trombolisis </strong>
                        </font>
                    </td>
                    <td class="td bg-head" width="11%" height="42"
                        style="text-align: center; vertical-align: middle;">
                        <font style="font-size: 18px">
                            <strong>Ceklist</strong>
                        </font>
                    </td>
                </tr>

                <?php
                
                $checked_1 = '';
                if (isset($data['kriteriaTrombolisisCheck'])) {
                    if ($data['kriteriaTrombolisisCheck'] == 'true') {
                        $checked_1 = 'checked';
                    }
                }
                
                $checked_2 = '';
                if (isset($data['onsetCheck'])) {
                    if ($data['onsetCheck'] == 'true') {
                        $checked_2 = 'checked';
                    }
                }
                
                $checked_3 = '';
                if (isset($data['strokeDeficitNeurologisCheck'])) {
                    if ($data['strokeDeficitNeurologisCheck'] == 'true') {
                        $checked_3 = 'checked';
                    }
                }
                
                $checked_4 = '';
                if (isset($data['gambaranPerdarahanIntrakranialCtScanCheck'])) {
                    if ($data['gambaranPerdarahanIntrakranialCtScanCheck'] == 'true') {
                        $checked_4 = 'checked';
                    }
                }
                
                $checked_5 = '';
                if (isset($data['persetujuanGeneralConsentCheck'])) {
                    if ($data['persetujuanGeneralConsentCheck'] == 'true') {
                        $checked_5 = 'checked';
                    }
                }
                
                $checked_5 = '';
                if (isset($data['persetujuanGeneralConsentCheck'])) {
                    if ($data['persetujuanGeneralConsentCheck'] == 'true') {
                        $checked_5 = 'checked';
                    }
                }
                
                $checked_6 = '';
                if (isset($data['defisitNeurologisSedangBeratCheck'])) {
                    if ($data['defisitNeurologisSedangBeratCheck'] == 'true') {
                        $checked_6 = 'checked';
                    }
                }
                
                $checked_7 = '';
                if (isset($data['traumaStrokeIskemik3BulanTerakhirCheck'])) {
                    if ($data['traumaStrokeIskemik3BulanTerakhirCheck'] == 'true') {
                        $checked_7 = 'checked';
                    }
                }
                
                $checked_8 = '';
                if (isset($data['gambaranInfarkMultilobarCheck'])) {
                    if ($data['gambaranInfarkMultilobarCheck'] == 'true') {
                        $checked_8 = 'checked';
                    }
                }
                
                $checked_9 = '';
                if (isset($data['kejangSaatOnsetStrokeCheck'])) {
                    if ($data['kejangSaatOnsetStrokeCheck'] == 'true') {
                        $checked_9 = 'checked';
                    }
                }
                
                $checked_10 = '';
                if (isset($data['riwayatTumorIntracranialIntraaxialCheck'])) {
                    if ($data['riwayatTumorIntracranialIntraaxialCheck'] == 'true') {
                        $checked_10 = 'checked';
                    }
                }
                
                $checked_11 = '';
                if (isset($data['riwayatPembedahanMayorTraumaBeratDll3BulanTerakhirCheck'])) {
                    if ($data['riwayatPembedahanMayorTraumaBeratDll3BulanTerakhirCheck'] == 'true') {
                        $checked_11 = 'checked';
                    }
                }
                
                ?>

                <tr class="tr">
                    <td class="td" width="6%" style="text-align: center; vertical-align: middle;">
                        <font style="font-size: 14px">1
                        </font>
                    </td>
                    <td class="td bg-gray" width="19%" rowspan="3"
                        style="text-align: center; vertical-align: middle;">
                        <font style="font-size: 14px">
                            Kriteria Code Stroke
                        </font>
                    </td>
                    <td class="td" width="64%" style="text-align: left; vertical-align: middle;">
                        <font style="font-size: 14px"> Kriteria Trombolisis</font>
                    </td>
                    <td class="td" style="text-align: center; vertical-align: middle;">
                        <input type="checkbox" name="" value="" <?php echo $checked_1; ?>>
                    </td>
                </tr>

                <tr>
                    <td class="td" width="6%" style="text-align: center; vertical-align: middle;">2</td>
                    <td class="td" width="64%" style="text-align: left; vertical-align: middle;">
                        <font style="font-size: 14px">
                            Onset ≤ 4,5 jam (serangan stroke pertama kali)
                        </font>
                    </td>
                    <td class="td" style="text-align: center;">
                        <input type="checkbox" name="" value="" <?php echo $checked_2; ?>>
                    </td>
                </tr>

                <tr>
                    <td class="td" width="6%" style="text-align: center; vertical-align: middle;">3</td>
                    <td class="td" width="64%">
                        <font style="font-size: 14px">
                            Diagnosis klinis stroke dengan deficit neurologis yang jelas
                        </font>
                    </td>
                    <td class="td" style="text-align: center;">
                        <input type="checkbox" name="" value="" <?php echo $checked_3; ?>>
                    </td>
                </tr>

                <tr>
                    <td class="td" width="6%" style="text-align: center; vertical-align: middle;">4</td>
                    <td class="td" colspan="2" style="text-align: left; vertical-align:;">
                        <font style="font-size: 14px">
                            Tidak ada gambaran perdarahan intrakranial pada ct scan
                        </font>
                    </td>
                    <td class="td" style="text-align: center;">
                        <input type="checkbox" name="" value="" <?php echo $checked_4; ?>>
                    </td>
                </tr>

                <tr>
                    <td class="td" width="6%" style="text-align: center; vertical-align: middle;">5</td>
                    <td class="td" colspan="2">
                        <font style="font-size: 14px">
                            Pasien dan keluarga menyetujui inform concent secara tertulis
                        </font>
                    </td>
                    <td class="td" style="text-align: center;">
                        <input type="checkbox" name="" value="" <?php echo $checked_5; ?>>
                    </td>
                </tr>

                <tr>
                    <td class="td" width="6%" style="text-align: center; vertical-align: middle;">6</td>
                    <td class="td" colspan="2">
                        <font style="font-size: 14px">
                            Defisit neurologis sedang – berat (score NIHSS ≥ 5)
                        </font>
                    </td>
                    <td class="td" style="text-align: center;">
                        <input type="checkbox" name="" value="" <?php echo $checked_6; ?>>
                    </td>
                </tr>
                <tr>
                    <td class="td" width="6%" style="text-align: center; vertical-align: middle;">7</td>
                    <td class="td" colspan="2">
                        <font style="font-size: 14px">
                            Tidak ada riwayat trauma kepala/stroke iskemik 3 bulan terakhir
                        </font>
                    </td>
                    <td class="td" style="text-align: center;">
                        <input type="checkbox" name="" value="" <?php echo $checked_7; ?>>
                    </td>
                </tr>

                <tr>
                    <td class="td" width="6%" style="text-align: center; vertical-align: middle;">8</td>
                    <td class="td" colspan="2">
                        <font style="font-size: 14px">
                            Tidak ada gambaran infark multilobar
                        </font>
                    </td>
                    <td class="td" style="text-align: center;">
                        <input type="checkbox" name="" value="" <?php echo $checked_8; ?>>
                    </td>
                </tr>

                <tr>
                    <td class="td" width="6%" style="text-align: center; vertical-align: middle;">9</td>
                    <td class="td" colspan="2">
                        <font style="font-size: 14px">
                            Tidak ada kejang pada saat onset stroke
                        </font>
                    </td>
                    <td class="td" style="text-align: center;">
                        <input type="checkbox" name="" value="" <?php echo $checked_9; ?>>
                    </td>
                </tr>

                <tr>
                    <td class="td" width="6%" style="text-align: center; vertical-align: middle;">
                        <font style="font-size: 14px">10
                        </font>
                    </td>
                    <td class="td" colspan="2">
                        <font style="font-size: 14px">
                            Tidak ada riwayat tumor intracranial/intraaxial
                        </font>
                    </td>
                    <td style="text-align: center;">
                        <input type="checkbox" name="" value="" <?php echo $checked_10; ?>>
                    </td>
                </tr>

                <tr>
                    <td class="td" width="6%" style="text-align: center; vertical-align: middle;">
                        <font style="font-size: 14px">11
                        </font>
                    </td>
                    <td class="td" colspan="2">
                        <font style="font-size: 14px">
                            Tidak ada riwayat pembedahan mayor, trauma berat, perdarahan GIT/traktus urinarius,
                            perdarahan intracranial dalam 3 bulan terakhir
                        </font>
                    </td>
                    <td style="text-align: center;">
                        <input type="checkbox" name="" value="" <?php echo $checked_11; ?>>
                    </td>
                </tr>
                <tr>
                    <td class="td">&nbsp;</td>
                    <td class="td" colspan="2">&nbsp;</td>
                    <td class="td">&nbsp;</td>
                </tr>
            </tbody>
        </table>


        <table width="100%" border="0">

            <?php
            
            $tgl_pembuatan = '-';
            if (isset($data['tglPembuatanFormulirKriteriaTrombolisis'])) {
                if ($data['tglPembuatanFormulirKriteriaTrombolisis'] != '' and $data['tglPembuatanFormulirKriteriaTrombolisis'] != '0000-00-00' and $data['tglPembuatanFormulirKriteriaTrombolisis'] != '1970-01-01') {
                    $tgl_pembuatan = date('d-m-y', strtotime($data['tglPembuatanFormulirKriteriaTrombolisis']));
                }
            }
            
            $dokter_pelaksana = '-';
            $qr_ttd_dokter_pelaksana = '';
            if (isset($data['dokterPelaksana'])) {
                if ($data['dokterPelaksana']['label'] != '') {
                    $dokter_pelaksana = $data['dokterPelaksana']['label'];
                    $qr_ttd_dokter_pelaksana = 'https://api.qrserver.com/v1/create-qr-code/?size=60x60&data={{ $dokter_pelaksana }}';
                }
            }
            
            $img_ttd_dokter_pelaksana = '';
            if (isset($data['ttdDokterPelaksana'])) {
                if ($data['ttdDokterPelaksana'] != '') {
                    $img_ttd_dokter_pelaksana = $data['ttdDokterPelaksana'];
                }
            }
            
            ?>


            <tbody>
                <tr>
                    <td width="61%" height="38">&nbsp;</td>
                    <td width="39%" style="text-align: center; vertical-align: middle;">
                        <font style="font-size: 14px">Garut, 31 Desember 2024 </font>
                    </td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                    <td width="39%" style="text-align: center; vertical-align: middle;">
                        <font style="font-size: 14px">Dokter Pelaksana</font>
                    </td>
                </tr>
                <tr>
                    <td height="65">&nbsp;</td>
                    <td style="text-align:center; font-size:14px;">
                        <img src="data:image/png;base64, {!! $tte !!}">
                        <br>
                        @isset($data['dokterPelaksana']['label'])
                        {{ $data['dokterPelaksana']['label'] }}
                        @endisset
                    </td>
                </tr>
            </tbody>
        </table>

        {{-- <hr style="border:2px solid #000;margin-bottom:0px">
      <hr style="border:0.5px solid #000;margin-top:2px">
      <hr style="border:0.5px solid #000;margin-top:2px"> --}}
    </section>
</body>

</html>