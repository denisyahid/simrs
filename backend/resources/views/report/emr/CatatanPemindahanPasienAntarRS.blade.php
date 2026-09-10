<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>EMR - Catatan Pemindahan Pasien Antar RS</title>
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

        .no-border-bottom {
            border-bottom: none;
        }

        .no-border-top {
            border-top: none;
        }

        .font {
            font-size: 8pt;
        }

        .font-2 {
            font-size: 8pt;
        }

    </style>

    @stack('style')

</head>

<body class="A4" style="font-family:DejaVu Sans, sans-serif;;height: auto" onload="window.print()">
    <section class="sheet padding-10mm" style="font-family:DejaVu Sans, sans-serif;;height: auto;overflow: hidden;">
        @php
            $tglv_pemindahan = isset($data['tanggal']) ? date('d-m-Y H:i', strtotime($data['tanggal'])) : "";
            $tglv_prosedur = isset($data['TanggalProsedur']) ? date('d-m-Y h:i', strtotime($data['TanggalProsedur'])) : "";
            $tglv_observasiTerakhir = isset($data['observasiTerakhir']) ? date('h:i', strtotime($data['observasiTerakhir'])) : "";
            $tglv_pemasanganKateter = isset($data['tanggalPemasangan']) ? date('d-m-Y', strtotime($data['tanggalPemasangan'])) : "";
            $tglv_pemasanganLP = isset($data['tglPemasangan_LP']) ? date('d-m-Y', strtotime($data['tglPemasangan_LP'])) : "";
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
                                    <span style="font-size: 14px">CATATAN PEMINDAHAN PASIEN ANTAR RUMAH SAKIT
                                </b>
                            </td>
                            <td width="45%" style="padding: 10px">
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
            <tr class="bg-gray">
                <td width="100%" colspan="2" class="padding-y">
                    <table width="100%" cellspacing="0" cellpadding="0" border="0">
                        <tr>
                            <td>
                                <td colspan="2" width="100%" style="font-size: 14px; text-align: center; font-weight: bold;">SITUATION</td>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td colspan="2" class="no-border-bottom">
                    <table class="font-2" width="100%" cellspacing="0" cellpadding="0" border="0">
                        <tr>
                            <td class="padding-y">
                                <span>Pemindahan Pasien: Tanggal dan Pukul {{ isset($tglv_pemindahan) ? $tglv_pemindahan : '      ' }}</span>
                            </td>
                            <td style="text-align: right">
                                <span>Dari RS : {{ isset($data['dariRS']) ? $data['dariRS'] : '     ' }}</span>&nbsp;&nbsp;&nbsp;&nbsp; <span>Ke RS:{{ isset($data['keRS']) ? $data['keRS'] : '     ' }} </span>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" class="padding-y">
                                <span>(contact person {{ isset($data['contactPerson']) }}                   no HP: {{ isset($data['noHp']) ? $data['noHp'] : '     ' }})</span>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td colspan="2" class="no-border">
                    <table class="font-2" width="100%" cellspacing="0" cellpadding="0" border="0">
                        <tr>
                            <td colspan="3" class="padding-y">
                                Dokter yang merawat :
                            </td>
                        </tr>
                        <tr>
                            <td>
                                1. {{ isset($data['dokter1']['label']) ? $data['dokter1']['label'] : '' }}
                            </td>
                            <td>
                                2. {{ isset($data['dokter2']['label']) ? $data['dokter2']['label'] : '' }}
                            </td>
                            <td>
                                3. {{ isset($data['dokter3']['label']) ? $data['dokter3']['label'] : '' }}
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td colspan="2" class="no-border">
                    <table class="font-2" width="100%" cellspacing="0" cellpadding="0" border="0">
                        <tr>
                            <td colspan="3" class="padding-y">
                                <span>Diagnosis Medis :</span>
                            </td>
                        </tr>
                        <tr>
                            <td style="white-space: pre-line">1. {{ isset($data['diagnosis1']) ? $data['diagnosis1'] : '' }}</td>
                            <td style="white-space: pre-line">2. {{ isset($data['diagnosis2']) ? $data['diagnosis2'] : '' }}</td>
                            <td style="white-space: pre-line">3. {{ isset($data['diagnosis3']) ? $data['diagnosis3'] : '' }}</td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td colspan="2" class="no-border">
                    <table class="font-2" width="100%" cellspacing="0" cellpadding="0" border="0">
                        <tr>
                            <td class="padding-y">
                                <span>Pasien / Keluarga sudah dijelaskan mengenai diagnosis : </span>
                                <input type="checkbox" {{ isset($data['dijelaskanMengenaiDiagnosis']) && $data['dijelaskanMengenaiDiagnosis'] == 'Ya' ? 'checked' : '' }}  /><span>Ya</span> <input type="checkbox" {{ isset($data['dijelaskanMengenaiDiagnosis']) && $data['dijelaskanMengenaiDiagnosis'] == 'Tidak' ? 'checked' : '' }}  /><span>Tidak</span>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td colspan="2" class="no-border padding-y">
                    <table class="font-2" width="100%" cellspacing="0" cellpadding="0" border="0">
                        <tr>
                            <td>
                                <span>Prosedur pembedahan / invasif yang dilakukan: {{ isset($data['ProsedurPembedahan']) ? $data['ProsedurPembedahan'] : '' }}</span>
                            </td>
                            <td>
                                <span>Tanggal : {{ isset($tglv_prosedur) ? $tglv_prosedur : '' }}</span>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td colspan="2" class="no-border padding-y">
                    <table class="font-2" width="100%" cellspacing="0" cellpadding="0" border="0">
                        <tr>
                            <td>
                                <span>Masalah keperawatan utama saat ini : {{ isset($data['masalahKeperawatan_utama']) ? $data['masalahKeperawatan_utama'] : '' }}</span>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td colspan="2" class="no-border padding-y">
                    <table class="font-2" width="100%" cellspacing="0" cellpadding="0" border="0">
                        <tr>
                            <td>
                                <span>Kondisi Pasien saat dipindahkan : {{ isset($data['kondisi_pasienSaat_dipindahkan']) ? $data['kondisi_pasienSaat_dipindahkan'] : '' }}</span>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr class="bg-gray">
                <td width="100%" colspan="2" class="padding-y">
                    <table width="100%" cellspacing="0" cellpadding="0" border="0">
                        <tr>
                            <td>
                                <td colspan="2" width="100%" style="font-size: 14px; text-align: center; font-weight: bold;">BACKGROUND</td>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td colspan="2" class="no-border-bottom padding-y">
                    <table class="font-2" width="100%" cellspacing="0" cellpadding="0" border="0">
                        <tr>
                            <td>
                                <span>Riwayat alergi / reaksi obat: <input type="checkbox" {{ isset($data['riwayatAlergi']) && $data['riwayatAlergi'] == 'Tidak' ? 'checked' : '' }}  /><span>Tidak</span></span> <input type="checkbox" {{ isset($data['riwayatAlergi']) && $data['riwayatAlergi'] == 'Ya' ? 'checked' : '' }}  /><span>Ya, nama obat :</span> {{ isset($data['riwayatAlergiDetail']) && isset($data['riwayatAlergi']) == 'Ya' ? $data['riwayatAlergiDetail'] : '' }}
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td colspan="2" class="no-border padding-y">
                    <table class="font-2" width="100%" cellspacing="0" cellpadding="0" border="0">
                        <tr>
                            <td>
                                <span>Riwayat reaksi : {{ isset($data['riwayatReaksi']) ? $data['riwayatReaksi'] : '' }}</span>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td colspan="2" class="no-border padding-y">
                    <table class="font-2" width="100%" cellspacing="0" cellpadding="0" border="0">
                        <tr>
                            <td>
                                <span>Intervensi medik / keperawatan : {{ isset($data['intervensiMedik']) ? $data['intervensiMedik'] : '' }}</span>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td colspan="2" class="no-border padding-y">
                    <table class="font-2" width="100%" cellspacing="0" cellpadding="0" border="0">
                        <tr>
                            <td>
                                <span>Hasil investigasi abnormal : {{ isset($data['hasilInvestigasiAbnormal']) ? $data['hasilInvestigasiAbnormal'] : '' }}</span>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td colspan="2" class="no-border padding-y">
                    <table class="font-2" width="100%" cellspacing="0" cellpadding="0" border="0">
                        <tr>
                            <td>
                                <span>Kewaspadaan / precaution : {{ isset($data['kewaspadaanSelect']) ? $data['kewaspadaanSelect'] : '' }}</span>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr class="bg-gray">
                <td width="100%" colspan="2" class="padding-y">
                    <table width="100%" cellspacing="0" cellpadding="0" border="0">
                        <tr>
                            <td>
                                <td colspan="2" width="100%" style="font-size: 14px; text-align: center; font-weight: bold;">ASSESSMENT</td>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td colspan="2" class="no-border padding-y">
                    <table class="font-2" width="100%" cellspacing="0" cellpadding="0" border="0">
                        <tr>
                            <td>
                                <span>Observasi terakhir Pukul : {{ isset($tglv_observasiTerakhir) ? $tglv_observasiTerakhir : '     ' }}</span> <span>Tingkat kesadaran : {{ isset($data['tingkatKesadaran']) ? $data['tingkatKesadaran'] : '' }}</span>
                            </td>
                            <td>
                                <input type="checkbox" {{ isset($data['tingkatKesadaranSelect']) && $data['tingkatKesadaranSelect'] == 'Depresi' ? 'checked' : '' }}  /><span>Depresi </span><input type="checkbox" {{ isset($data['tingkatKesadaranSelect']) && $data['tingkatKesadaranSelect'] == 'Demensia' ? 'checked' : '' }}  /><span>Demensia </span><input type="checkbox" {{ isset($data['tingkatKesadaranSelect']) && $data['tingkatKesadaranSelect'] == 'Confuse' ? 'checked' : '' }}  /><span>Confuse</span>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td colspan="2" class="no-border padding-y">
                    <table class="font-2" width="100%" cellspacing="0" cellpadding="0" border="0">
                        <tr>
                            <td width="30%">
                                <span>GCS: E {{ isset($data['gcse']) ? $data['gcse'] : '  ' }} V {{ isset($data['gcsv']) ? $data['gcsv'] : '  ' }} M {{ isset($data['gcsm']) ? $data['gcsm'] : '  ' }}</span>
                            </td>
                            <td width="70%">
                                <span>Pupil & Reaksi cahaya : Kanan {{ isset($data['pupilKanan']) ? $data['pupilKanan'] : '   ' }} Kiri {{ isset($data['pupilKiri']) ? $data['pupilKiri'] : '  ' }}</span>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td colspan="2" class="no-border padding-y">
                    <table class="font-2" width="100%" cellspacing="0" cellpadding="0" border="0">
                        <tr>
                            <td>
                                <span>TD : {{ isset($data['tekananDarah']) ? $data['tekananDarah'] : '    /    ' }} mmHg, N: {{ isset($data['nadi']) ? $data['nadi'] : '   ' }} x/mnt, {{ isset($data['teraturTidakteratur']) ? $data['teraturTidakteratur'] : '    ' }}, RR: {{ isset($data['respirasi']) ? $data['respirasi'] : '   ' }} x/mnt, Suhu: {{ isset($data['suhu']) ? $data['suhu'] : '   ' }}°C, Skala nyeri: {{ isset($data['skalaNyeri']) ? $data['skalaNyeri'] : '  ' }}</span>
                                {{-- <span>TD : {{ isset($data['tekananDarah']) ? $data['tekananDarah'] : '    /    ' }} mmHg, N: {{ isset($data['nadi']) }} x/mnt, {{ isset($data['teraturTidakteratur']) ? $data['teraturTidakteratur'] : '    ' }}, RR: {{ isset($data['respirasi']) }} x/mnt, Suhu: {{ isset($data['suhu']) ? $data['suhu'] : '   ' }}°C, Skala nyeri: {{ isset($data['skalaNyeri']) ? $data['skalaNyeri'] : '  ' }}</span> --}}
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td colspan="2" class="no-border padding-y">
                    <table class="font-2" width="100%" cellspacing="0" cellpadding="0" border="0">
                        <tr>
                            <td>
                                <span>Diet / Nutrisi: <input type="checkbox" {{ isset($data['oralDiet']) && $data['oralDiet'] == 'Oral' ? 'checked' : '' }}  /><span>Oral&nbsp;</span></span><input type="checkbox" {{ isset($data['ngtDiet']) && $data['ngtDiet'] == 'NGT' ? 'checked' : '' }}  /><span>NGT&nbsp;</span><input type="checkbox" {{ isset($data['batasanCairanDiet']) && $data['batasanCairanDiet'] == 'Batasan Cairan' ? 'checked' : '' }}  /><span>Batasan Cairan {{ isset($data['batasanCairanDietDetail']) ? $data['batasanCairanDietDetail'] : '   ' }} cc</span> <input type="checkbox" {{ isset($data['dietKhususDiet']) && $data['dietKhususDiet'] == 'Diet Khusus' ? 'checked' : '' }}  /><span>Diet Khusus, jelaskan {{ isset($data['dietKhususDietDetail']) ? $data['dietKhususDietDetail'] : '     ' }}</span>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td colspan="2" class="no-border padding-y">
                    <table class="font-2" width="100%" cellspacing="0" cellpadding="0" border="0">
                        <tr>
                            <td>
                                <span>BAB: <input type="checkbox" {{ isset($data['normalBAB']) && $data['normalBAB'] == 'Normal' ? 'checked' : '' }}  /><span>Normal</span></span>
                                <input type="checkbox" {{ isset($data['ileustomyBAB']) && $data['ileustomyBAB'] == 'Ileustomy/colostomy' ? 'checked' : '' }}  /><span>Ileustomy/colostomy</span>
                                <input type="checkbox" {{ isset($data['inkontinensiaUrinBAB']) && $data['inkontinensiaUrinBAB'] == 'Inkontinensia urin' ? 'checked' : '' }}  /><span>Inkontinensia urin</span>
                                <input type="checkbox" {{ isset($data['inkontinensiaAlviBAB']) && $data['inkontinensiaAlviBAB'] == 'Inkontinensia alvi' ? 'checked' : '' }}  /><span>Inkontinensia alvi</span>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td colspan="2" class="no-border padding-y">
                    <table class="font-2" width="100%" cellspacing="0" cellpadding="0" border="0">
                        <tr>
                            <td>
                                <span>BAK: <input type="checkbox" {{ isset($data['normalBAK']) && $data['normalBAK'] == 'Normal' ? 'checked' : '' }}  /><span>Normal</span></span>
                                <input type="checkbox" {{ isset($data['kateterBAK']) && $data['kateterBAK'] == 'Kateter' ? 'checked' : '' }}  /><span>Kateter</span>
                                <span>Jenis Kateter: {{ isset($data['jenisKateter']) ? $data['jenisKateter'] : '         ' }} No Kateter: {{ isset($data['noKateter']) ? $data['noKateter'] : '    ' }} Tgl. Pemasangan: {{ isset($tglv_pemasanganKateter) ? $tglv_pemasanganKateter : '' }}</span>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td colspan="2" class="no-border padding-y">
                    <table class="font-2" width="100%" cellspacing="0" cellpadding="0" border="0">
                        <tr>
                            <td>
                                <span>Mobilisasi: <input type="checkbox" {{ isset($data['jalanMobilisasi']) && $data['jalanMobilisasi'] == 'Jalan' ? 'checked' : '' }}  /><span>Jalan</span></span>
                                <input type="checkbox" {{ isset($data['tirahBaringMobilisasi']) && $data['tirahBaringMobilisasi'] == 'Tirah baring' ? 'checked' : '' }}  /><span>Tirah baring</span>
                                <input type="checkbox" {{ isset($data['dudukMobilisasi']) && $data['dudukMobilisasi'] == 'Duduk' ? 'checked' : '' }}  /><span>Duduk</span>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td colspan="2" class="no-border padding-y">
                    <table class="font-2" width="100%" cellspacing="0" cellpadding="0" border="0">
                        <tr>
                            <td>
                                <span>Transfer / mobilisasi: <input type="checkbox" {{ isset($data['mandiriTransfer']) && $data['mandiriTransfer'] == 'Mandiri' ? 'checked' : '' }}  /><span>Mandiri</span></span>
                                <input type="checkbox" {{ isset($data['dibantuSebagianTransfer']) && $data['dibantuSebagianTransfer'] == 'Dibantu sebagian' ? 'checked' : '' }}  /><span>Dibantu sebagian</span>
                                <input type="checkbox" {{ isset($data['dibantuSebagianPenuh']) && $data['dibantuSebagianPenuh'] == 'Dibantu penuh' ? 'checked' : '' }}  /><span>Dibantu penuh</span>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td colspan="2" class="no-border padding-y">
                    <table class="font-2" width="100%" cellspacing="0" cellpadding="0" border="0">
                        <tr>
                            <td>
                                <span>Alat bantu yang digunakan: <input type="checkbox" {{ isset($data['tanpaAlatABYD']) && $data['tanpaAlatABYD'] == 'Tanpa Alat Bantu' ? 'checked' : '' }}  /><span>Tanpa Alat Bantu</span></span>
                                <input type="checkbox" {{ isset($data['gigiPalsuABYD']) && $data['gigiPalsuABYD'] == 'Gigi palsu' ? 'checked' : '' }}  /><span>Gigi palsu</span>
                                <input type="checkbox" {{ isset($data['kacamataABYD']) && $data['kacamataABYD'] == 'Kacamata' ? 'checked' : '' }}  /><span>Kacamata</span>
                                <input type="checkbox" {{ isset($data['alatBantuDengarABYD']) && $data['alatBantuDengarABYD'] == 'Alat bantu dengar' ? 'checked' : '' }}  /><span>Alat bantu dengar</span>
                                <input type="checkbox" {{ isset($data['lainLainABYD']) && $data['lainLainABYD'] == 'Lain-lain' ? 'checked' : '' }}  /><span>Lain-lain: {{ isset($data['lainLainABYDDetail']) ? $data['lainLainABYDDetail'] : '     ' }}</span>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td colspan="2" class="no-border padding-y">
                    <table class="font-2" width="100%" cellspacing="0" cellpadding="0" border="0">
                        <tr>
                            <td>
                                <span>Luka Perawatan / Decubitus: </span><input type="checkbox" {{ isset($data['lukaPerawatan']) && $data['lukaPerawatan'] == 'Tidak' ? 'checked' : '' }}  /><span>Tidak</span><input type="checkbox" {{ isset($data['lukaPerawatan']) && $data['lukaPerawatan'] == 'Ya' ? 'checked' : '' }}  /><span>Ya</span><span>Kondisi {{ isset($data['kondisiLP']) ? $data['kondisiLP'] : '  ' }} Lokasi {{ isset($data['lokasiLP']) ? $data['lokasiLP'] : '   ' }} Ukuran {{ isset($data['ukuranLP']) ? $data['ukuranLP'] : '    ' }}</span>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td colspan="2" class="no-border padding-y">
                    <table class="font-2" width="100%" cellspacing="0" cellpadding="0" border="0">
                        <tr>
                            <td>
                                <input type="checkbox" {{ isset($data['infusCVC_LP']) && $data['infusCVC_LP'] == 'Infus/CVC' ? 'checked' : '' }}  /><span>Infus/CVC</span>
                                <input type="checkbox" {{ isset($data['pivasScore_LP']) && $data['pivasScore_LP'] == 'Pivas Score' ? 'checked' : '' }}  /><span>Pivas Score {{ isset($data['pivasScoreDetail_LP']) ? $data['pivasScoreDetail_LP'] : '   ' }}</span> <span>Tanggal Pemasangan : {{ isset($tglv_pemasanganLP) ? $tglv_pemasanganLP : '    ' }}</span>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td colspan="2" class="no-border padding-y">
                    <table class="font-2" width="100%" cellspacing="0" cellpadding="0" border="0">
                        <tr>
                            <td>
                                <span>Tindakan / kebutuhan khusus: </span>
                                <input type="checkbox" {{ isset($data['protokolResikoPJ']) && $data['protokolResikoPJ'] == 'Protokol resiko pasien jatuh' ? 'checked' : '' }}  /><span>Protokol resiko pasien jatuh</span>
                                <input type="checkbox" {{ isset($data['protokolRestrain']) && $data['protokolRestrain'] == 'Protokol restrain' ? 'checked' : '' }}  /><span>Protokol restrain</span>
                                <input type="checkbox" {{ isset($data['perawatanLuka']) && $data['perawatanLuka'] == 'Perawatan luka' ? 'checked' : '' }}  /><span>Perawatan luka</span>
                                <input type="checkbox" {{ isset($data['hygiene']) && $data['hygiene'] == 'Hygiene' ? 'checked' : '' }}  /><span>Hygiene</span>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr style="page-break-after: always;">
                <td colspan="2" class="no-border-top padding-y">
                    <table class="font-2" width="100%" cellspacing="0" cellpadding="0" border="0">
                        <tr>
                            <td class="vt" width="30%">
                                <span>Peralatan khusus yang diperlukan</span>
                            </td>
                            <td class="vt">
                                1. <span style="white-space: pre-line">{{ isset($data['satu_PKYD']) ? $data['satu_PKYD'] : '' }}</span> <br />
                            </td>
                            <td class="vt">
                                Lama Penggunaan : <span style="white-space: pre-line">{{ isset($data['satu_LP_PKYD']) ? $data['satu_LP_PKYD'] : '' }}</span> <br />
                            </td>
                        </tr>
                        <tr>
                            <td class="vt"></td>
                            <td class="vt">
                                2. <span style="white-space: pre-line">{{ isset($data['dua_PKYD']) ? $data['dua_PKYD'] : '' }}</span> <br />
                            </td>
                            <td class="vt">
                                Lama Penggunaan : <span style="white-space: pre-line">{{ isset($data['dua_LP_PKYD']) ? $data['dua_LP_PKYD'] : '' }}</span> <br />
                            </td>
                        </tr>
                        <tr>
                            <td class="vt"></td>
                            <td class="vt">
                                3. <span style="white-space: pre-line">{{ isset($data['tiga_PKYD']) ? $data['tiga_PKYD'] : '' }}</span>
                            </td>
                            <td class="vt">
                                Lama Penggunaan : <span style="white-space: pre-line">{{ isset($data['tiga_LP_PKYD']) ? $data['tiga_LP_PKYD'] : '' }}</span>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td colspan="2" class="no-border padding-y">
                    <table class="font-2" width="100%" cellspacing="0" cellpadding="0" border="0">
                        <tr>
                            <td>
                                <span>Hal-hal istimewa yang berhubungan dengan kondisi pasien :</span>
                            </td>
                        </tr>
                        <tr>
                            <td style="white-space: pre-line;">
                                {{ isset($data['halIstimewa']) ? $data['halIstimewa'] : '' }}
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td colspan="2" class="no-border padding-y">
                    <table class="font-2" width="100%" cellspacing="0" cellpadding="0" border="0">
                        <tr>
                            <td width="50%">
                                <span>Diagnosis Keperawatan :</span>
                            </td>
                            <td width="25%" class="tc">
                                <span>Sudah teratasi :</span>
                            </td>
                            <td width="25%" class="tc">
                                <span>Belum teratasi :</span>
                            </td>
                        </tr>
                        @if(isset($data['details']) && is_array($data['details']))
                        @foreach ($data['details'] as $item)
                            <tr>
                                <td width="50%">
                                    {{ $loop->iteration }}.
                                    {{ isset($item['diagnosisKeperawatan']) ? $item['diagnosisKeperawatan'] : '' }}
                                </td>
                                <td width="25%" class="tc">
                                    <input type="checkbox" {{ isset($item['teratasi']) && $item['teratasi'] == 'Sudah' ? 'checked' : '' }}  /><span>&nbsp;</span>
                                </td>
                                <td width="25%" class="tc">
                                    <input type="checkbox" {{ isset($item['teratasi']) && $item['teratasi'] == 'Belum' ? 'checked' : '' }}  /><span>&nbsp;</span>
                                </td>
                            </tr>
                        @endforeach
                        @endif
                    </table>
                </td>
            </tr>
            <tr class="bg-gray">
                <td width="100%" colspan="2" class="padding-y">
                    <table width="100%" cellspacing="0" cellpadding="0" border="0">
                        <tr>
                            <td>
                                <td colspan="2" width="100%" style="font-size: 14px; text-align: center; font-weight: bold;">RECOMENDATIONS</td>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td colspan="2" class="no-border padding-y">
                    <table class="font-2" width="100%" cellspacing="0" cellpadding="0" border="0">
                        <tr>
                            <td>
                                <span>Konsultasi <span style="white-space: pre-line">{{ isset($data['konsultasi']) ? $data['konsultasi'] : '' }}</span></span>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span>Rencana Pemeriksaan Lab/Radiologi <span style="white-space: pre-line">{{ isset($data['rencanaPemeriksaanLR']) ? $data['rencanaPemeriksaanLR'] : '' }}</span></span>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span>Therapy <span style="white-space: pre-line">{{ isset($data['therapy']) ? $data['therapy'] : '' }}</span></span>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span>Fisioterapi/mobilisasi <span style="white-space: pre-line">{{ isset($data['fisioterapi']) ? $data['fisioterapi'] : '' }}</span></span>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span>Persiapan Pulang <span style="white-space: pre-line">{{ isset($data['persiapanPulang']) ? $data['persiapanPulang'] : '' }}</span></span>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span>Rencana tindakan lebih lanjut <span style="white-space: pre-line">{{ isset($data['rencanaTindakanLL']) ? $data['rencanaTindakanLL'] : '' }}</span></span>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td colspan="2" class="no-border padding-y">
                    <table class="font-2" width="100%" cellspacing="0" cellpadding="0" border="0">
                        <tr>
                            <td colspan="3">
                                <span>Note: Obat, barang dan dokumen yang disertakan</span>
                            </td>
                        </tr>
                        <tr>
                            <td width="33%">
                                <span>MRI {{ isset($data['mri']) ? $data['mri'] : '   ' }} Lembar</span>
                            </td>
                            <td width="33%">
                                <span>ECHO {{ isset($data['echo']) ? $data['echo'] : '   ' }} Lembar</span>
                            </td>
                            <td width="33%">
                            </td>
                        </tr>
                        <tr>
                            <td width="33%">
                                <span>Hasil Lab {{ isset($data['hasilLAB']) ? $data['hasilLAB'] : '   ' }} Lembar</span>
                            </td>
                            <td width="33%">
                                <span>MRA {{ isset($data['mra']) ? $data['mra'] : '   ' }} Lembar</span>
                            </td>
                            <td width="33%">
                                <span>Gigi Palsu {{ isset($data['gigiPalsu']) ? $data['gigiPalsu'] : '   ' }}</span>
                            </td>
                        </tr>
                        <tr>
                            <td width="33%">
                                <span>Foto Rontgen {{ isset($data['fotoRontgen']) ? $data['fotoRontgen'] : '   ' }} Lembar</span>
                            </td>
                            <td width="33%">
                                <span>Hasil USG {{ isset($data['hasilUSG']) ? $data['hasilUSG'] : '   ' }} Lembar</span>
                            </td>
                            <td width="33%">
                                <span>Kacamata {{ isset($data['kacamata']) ? $data['kacamata'] : '   ' }} </span>
                            </td>
                        </tr>
                        <tr>
                            <td width="33%">
                                <span>CT Scan {{ isset($data['ctScan']) ? $data['ctScan'] : '   ' }} Lembar</span>
                            </td>
                            <td width="33%">
                                <span>Hasil EKG {{ isset($data['hasilEKG']) ? $data['hasilEKG'] : '   ' }} Lembar</span>
                            </td>
                            <td width="33%">
                                <span>Alat bantu dengar {{ isset($data['alatBantuDengar']) ? $data['alatBantuDengar'] : '   ' }}</span>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="3">
                                <span>Obat-obatan : <span style="white-space: pre-line;">{{ isset($data['obatObatan']) ? $data['obatObatan'] : '' }}</span></span>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td colspan="2" class="no-border padding-y">
                    <table class="font-2" width="100%" cellspacing="0" cellpadding="0" border="0">
                        <tr>
                            <td>
                                <span>Perubahan kondisi selama transport : </span>
                                <input type="checkbox" {{ isset($data['perubahanKondisiST']) && $data['perubahanKondisiST'] == 'Tidak' ? 'checked' : '' }}  /><span>Tidak</span>
                                <input type="checkbox" {{ isset($data['perubahanKondisiST']) && $data['perubahanKondisiST'] == 'Ya' ? 'checked' : '' }}  /><span>Ya</span>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span>
                                    Bila ya, sebutkan perubahan yang terjadi dan penanganan yang diberikan kepada pasien:
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span style="white-space: pre-line;">{{ isset($data['perubahanKondisiDetail']) ? $data['perubahanKondisiDetail'] : '' }}</span>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td colspan="2" class="no-border padding-y">
                    <table class="font-2" width="100%" cellspacing="0" cellpadding="0" border="0">
                        <tr>
                            <td>
                                <span>
                                    Lain-lain
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span style="white-space: pre-line;">{{ isset($data['lainLain']) ? $data['lainLain'] : '' }}</span>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td colspan="2" class="no-border padding-y" style="padding: 10px;">
                    <table class="font-2" width="100%" cellspacing="0" cellpadding="0" border="1">
                        <tr>
                            <td class="tc vt" width="20%" style="{{ empty($data['namaPJ']) ? 'padding-bottom: 10em;' : '' }}">
                                <span>Disetujui</span>
                                <br>
                                <!-- Qrcode ceritanya -->
                                @if (isset($data['namaPJ']) && $data['namaPJ'] != '')
                                    <img src="data:image/png;base64, {!! $qrcode2 !!}">
                                @endif
                                <br>
                                <span>{{ isset($data['namaPJ']) ? $data['namaPJ'] : ' ' }}</span>
                            </td>
                            <td class="tc vt" width="20%" style="{{ empty($data['dokterYangMerawat']) ? 'padding-bottom: 2em;' : '' }}">
                                <span>Mengetahui</span>
                                <br>
                                <!-- Qrcode ceritanya -->
                                @if (isset($data['dokterYangMerawat']['label']))
                                    <img src="data:image/png;base64, {!! $qrcode !!}">
                                @endif
                                <br>
                                <span>{{ isset($data['dokterYangMerawat']['label']) ? $data['dokterYangMerawat']['label'] : ' ' }}</span>
                            </td>
                            <td class="tc vt" width="20%" style="{{ empty($data['perawatDiserahkan']) ? 'padding-bottom: 2em;' : '' }}">
                                <span>Diserahkan</span>
                                <br>
                                <!-- Qrcode ceritanya -->
                                @if (isset($data['perawatDiserahkan']['label']))
                                    <img src="data:image/png;base64, {!! $qrcode3 !!}">
                                @endif
                                <br>
                                <span>{{ isset($data['perawatDiserahkan']['label']) ? $data['perawatDiserahkan']['label'] : ' ' }}</span>
                            </td>
                            <td class="tc vt" width="20%" style="{{ empty($data['perawatDiterima']) ? 'padding-bottom: 2em;' : '' }}">
                                <span>Diterima</span>
                                <br>
                                <!-- Qrcode ceritanya -->
                                @if (isset($data['perawatDiterima']['label']))
                                    <img src="data:image/png;base64, {!! $qrcode4 !!}">
                                @endif
                                <br>
                                <span>{{ isset($data['perawatDiterima']['label']) ? $data['perawatDiterima']['label'] : ' ' }}</span>
                            </td>
                            <td class="tc vt" width="20%" style="{{ empty($data['wardClerk_dibukukan']) ? 'padding-bottom: 2em;' : '' }}">
                                <span>Dibukukan</span>
                                <br>
                                <!-- Qrcode ceritanya -->
                                @if (isset($data['wardClerk_dibukukan']['label']))
                                    <img src="data:image/png;base64, {!! $qrcode5 !!}">
                                @endif
                                <br>
                                <span>{{ isset($data['wardClerk_dibukukan']['label']) ? $data['wardClerk_dibukukan']['label'] : ' ' }}</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="tc padding-y">
                                <span>Pasien/penanggung jawab</span> <br />
                                <span>Contact No: {{ isset($data['contactPJ']) ? $data['contactPJ'] : '' }}</span>
                            </td>
                            <td class="tc padding-y">
                                <span>Dokter yang merawat</span>
                            </td>
                            <td class="tc padding-y">
                                <span>Perawat/Incharge</span>
                            </td>
                            <td class="tc padding-y">
                                <span>Perawat/Incharge</span>
                            </td>
                            <td class="tc padding-y">
                                <span>Ward Clerk</span>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </section>
</body>

</html>
