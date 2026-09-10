<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EMR - Formulir Asuhan dan Observasi Keperawatan Pasien Kemoterapi Rawat Jalan</title>
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

        body {
            page-break-inside: avoid !important;
        }

        /* Ensure Page 5 starts on a new page */
        .page-break {
            page-break-before: always;
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
            padding-top: 6px;
            padding-bottom: 6px;
        }

        .padding-x {
            padding-right: 6px;
            padding-left: 6px;
        }

        .vt {
            vertical-align: top;
        }

        .ws {
            white-space: pre-line;
        }

        .vc {
            vertical-align: center;
        }

        .nowrap {
            white-space: nowrap; /* Prevents small fields from wrapping */
        }

        .wrap {
            word-wrap: break-word;
            white-space: normal;
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

        .logo {
            font-family: DejaVu Sans !important;
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
        .tr {
            text-align: right;
        }
        .tl {
            text-align: left;
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
            // $tglv_pemindahan = isset($data['tanggal']) ? date('d-m-Y H:i', strtotime($data['tanggal'])) : "";
            // $tglv_prosedur = isset($data['TanggalProsedur']) ? date('d-m-Y h:i', strtotime($data['TanggalProsedur'])) : "";
            // $tglv_observasiTerakhir = isset($data['observasiTerakhir']) ? date('h:i', strtotime($data['observasiTerakhir'])) : "";
            // $tglv_pemasanganKateter = isset($data['tanggalPemasangan']) ? date('d-m-Y', strtotime($data['tanggalPemasangan'])) : "";
            // $tglv_pemasanganLP = isset($data['tglPemasangan_LP']) ? date('d-m-Y', strtotime($data['tglPemasangan_LP'])) : "";
            // $tglv_kunjungan = isset($data['tanggalKunjunganPasien']) ? date('d-m-Y H:i', strtotime($data['tanggalKunjunganPasien'])) : "";
        @endphp
        <table width="100%" cellspacing="0" cellpadding="0" border="1">
            <thead>
                <tr>
                    <td width="100%" style="text-align:right" colspan=2>
                        <table width="100%" cellspacing="0" cellpadding="0" border="0">
                            <tr class="bg-blue">
                                <td>
                                    <td width="50%" style="text-align:left; font-size: 14px">RSUD BALI MANDARA</td>
                                    <td width="50%" style="font-size: 14px; text-align: right;">RM 15A/KEMO/00</td>
                                </td>
                            </tr>
                        </table>
                    </td>
                    <!-- <td width="60%"></td> -->
                </tr>
            </thead>
            <tr class="bg-gray">
                <td width="100%" colspan="2" class="padding-y">
                    <table width="100%" cellspacing="0" cellpadding="0" border="0">
                        <tr>
                            <td>
                                <td colspan="2" width="100%" style="font-size: 14px; text-align: left;">PELAKSANAAN DAN MONITORING PEMBERIAN</td>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td colspan="2" class="padding-y padding-x font-2">
                    <table width="100%" cellspacing="0" cellpadding="3" border="1" style="table-layout: fixed;">
                        <tr>
                            <td class="ws vt tc" width="5%">Jam</td>
                            <td class="ws vt tc" width="5%">Suhu 
                                (°C)
                            </td>
                            <td class="ws vt tc" width="5%">Nadi 
                                (X/Mnt)
                            </td>
                            <td class="ws vt tc" width="5%">Tensi 
                                (mmHg)
                            </td>
                            <td class="ws vt tc" width="5%">Respirasi 
                                (X/Mnt)
                            </td>
                            <td class="ws vt tc" width="5%">Nyeri</td>
                            <td class="ws vt tc" width="14%">Obat dan Cairan Yang Diberikan</td>
                            <td class="ws vt tc" width="6%">Volume 
                                (CC)
                            </td>
                            <td class="ws vt tc" width="10%">Blood Return</td>
                            <td class="ws vt tc" width="10%">Reaksi Hiper sensifitas</td>
                            <td class="ws vt tc" width="5%">Makan 
                                (Porsi)
                            </td>
                            <td class="ws vt tc" width="5%">Minum 
                                (CC)
                            </td>
                            <td class="ws vt tc" width="3%">BAB / BAK</td>
                            <td class="ws vt tc" width="14%">Masalah</td>
                        </tr>
                        @foreach ($data['pelaksanaanDanMonitoring'] as $item)
                        <tr>
                            <td class="vt tc nowrap" > <!-- Increased by 1% -->
                                {{ isset($item['jamPelaksanaanDanMonitoring']) ? date('H:i', strtotime($item['jamPelaksanaanDanMonitoring'])) : '' }}
                            </td>
                            <td class="vt tc nowrap" > <!-- Increased by 1% -->
                                {{ isset($item['suhuMonitoring']) ? $item['suhuMonitoring'] : '' }}
                            </td>
                            <td class="vt tc nowrap" > <!-- Increased by 1% -->
                                {{ isset($item['nadiMonitoring']) ? $item['nadiMonitoring'] : '' }}
                            </td>
                            <td class="vt tc nowrap" > <!-- Increased by 1% -->
                                {{ isset($item['tensiMonitoring']) ? $item['tensiMonitoring'] : '' }}
                            </td>
                            <td class="vt tc nowrap" > <!-- Increased by 1% -->
                                {{ isset($item['respirasiMonitoring']) ? $item['respirasiMonitoring'] : '' }}
                            </td>
                            <td class="vt tc nowrap" > <!-- Increased by 1% -->
                                {{ isset($item['nyeriMonitoring']) ? $item['nyeriMonitoring'] : '' }}
                            </td>
                            <td class="vt tc nowrap" > <!-- Increased by 1% -->
                                {{ isset($item['obatCairan']) ? $item['obatCairan'] : '' }}
                            </td>
                            <td class="vt tc nowrap" > <!-- Increased by 1% -->
                                {{ isset($item['VolumeMonitoring']) ? $item['VolumeMonitoring'] : '' }}
                            </td>
                            <td class="vt tc nowrap" > <!-- Increased by 1% -->
                                <input type="checkbox" {{ isset($item['bloodReturn']) && $item['bloodReturn'] == 'ada' ? 'checked' : '' }}  /><span>Ada</span>&nbsp;
                                <input type="checkbox" {{ isset($item['bloodReturn']) && $item['bloodReturn'] == 'tidakAda' ? 'checked' : '' }}  /><span>Tidak Ada</span>
                            </td>
                            <td class="vt tc nowrap" > <!-- Increased by 1% -->
                                <input type="checkbox" {{ isset($item['reaksiHiper']) && $item['reaksiHiper'] == 'ada' ? 'checked' : '' }}  /><span>Ada</span>&nbsp;
                                <input type="checkbox" {{ isset($item['reaksiHiper']) && $item['reaksiHiper'] == 'tidakAda' ? 'checked' : '' }}  /><span>Tidak Ada</span>
                            </td>
                            <td class="vt tc nowrap" > <!-- Increased by 1% -->
                                {{ isset($item['MakanMonitoring']) ? $item['MakanMonitoring'] : '' }}
                            </td>
                            <td class="vt tc nowrap" > <!-- Increased by 1% -->
                                {{ isset($item['MinumCCMonitoring']) ? $item['MinumCCMonitoring'] : '' }}
                            </td>
                            <td class="vt tc nowrap" > <!-- Increased by 1% -->
                                {{ isset($item['BuangKotoranMonitoring']) ? $item['BuangKotoranMonitoring'] : '' }}
                            </td>
                            <td class="vt tc wrap" > <!-- Increased by 1% -->
                                <span >
                                    {{ isset($item['MasalahMonitoring']) ? $item['MasalahMonitoring'] : '' }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                        <tr>
                            <td class="tr" colspan="8">Balance Cairan</td>
                            <td class="tc vc" colspan="2">
                                Cairan masuk :
                                <br />
                                {{ isset($data['rataRataCairanMasuk']) ? $data['rataRataCairanMasuk'] : '..........' }}&nbsp;CC
                            </td>
                            <td class="tc vc" colspan="3">
                                Cairan keluar :
                                <br />
                                {{ isset($data['rataRataCairanKeluar']) ? $data['rataRataCairanKeluar'] : '..........' }}&nbsp;CC
                            </td>
                            <td>

                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </section>
</body>

</html>
