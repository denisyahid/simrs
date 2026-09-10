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

        .padding-x {
            padding-right: 3px;
            padding-left: 3px;
        }

        .vt {
            vertical-align: top;
        }

        .vc {
            vertical-align: center;
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
            $tglv_pemindahan = isset($data['tanggal']) ? date('d-m-Y H:i', strtotime($data['tanggal'])) : "";
            $tglv_prosedur = isset($data['TanggalProsedur']) ? date('d-m-Y h:i', strtotime($data['TanggalProsedur'])) : "";
            $tglv_observasiTerakhir = isset($data['observasiTerakhir']) ? date('h:i', strtotime($data['observasiTerakhir'])) : "";
            $tglv_pemasanganKateter = isset($data['tanggalPemasangan']) ? date('d-m-Y', strtotime($data['tanggalPemasangan'])) : "";
            $tglv_pemasanganLP = isset($data['tglPemasangan_LP']) ? date('d-m-Y', strtotime($data['tglPemasangan_LP'])) : "";
            $tglv_kunjungan = isset($data['tanggalKunjunganPasien']) ? date('d-m-Y H:i', strtotime($data['tanggalKunjunganPasien'])) : "";
            $imgDefault = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAJYAAACWCAYAAAA8AXHiAAAAAXNSR0IArs4c6QAAAqFJREFUeF7t0jENAAAMw7CVP+mhyOcC6BF5ZwoEBRZ8ulTgwIIgKQBWktUpWAwkBcBKsjoFi4GkAFhJVqdgMZAUACvJ6hQsBpICYCVZnYLFQFIArCSrU7AYSAqAlWR1ChYDSQGwkqxOwWIgKQBWktUpWAwkBcBKsjoFi4GkAFhJVqdgMZAUACvJ6hQsBpICYCVZnYLFQFIArCSrU7AYSAqAlWR1ChYDSQGwkqxOwWIgKQBWktUpWAwkBcBKsjoFi4GkAFhJVqdgMZAUACvJ6hQsBpICYCVZnYLFQFIArCSrU7AYSAqAlWR1ChYDSQGwkqxOwWIgKQBWktUpWAwkBcBKsjoFi4GkAFhJVqdgMZAUACvJ6hQsBpICYCVZnYLFQFIArCSrU7AYSAqAlWR1ChYDSQGwkqxOwWIgKQBWktUpWAwkBcBKsjoFi4GkAFhJVqdgMZAUACvJ6hQsBpICYCVZnYLFQFIArCSrU7AYSAqAlWR1ChYDSQGwkqxOwWIgKQBWktUpWAwkBcBKsjoFi4GkAFhJVqdgMZAUACvJ6hQsBpICYCVZnYLFQFIArCSrU7AYSAqAlWR1ChYDSQGwkqxOwWIgKQBWktUpWAwkBcBKsjoFi4GkAFhJVqdgMZAUACvJ6hQsBpICYCVZnYLFQFIArCSrU7AYSAqAlWR1ChYDSQGwkqxOwWIgKQBWktUpWAwkBcBKsjoFi4GkAFhJVqdgMZAUACvJ6hQsBpICYCVZnYLFQFIArCSrU7AYSAqAlWR1ChYDSQGwkqxOwWIgKQBWktUpWAwkBcBKsjoFi4GkAFhJVqdgMZAUACvJ6hQsBpICYCVZnYLFQFIArCSrU7AYSAqAlWR1ChYDSQGwkqxOwWIgKQBWktUpWAwkBcBKsjp9QYIAl6bSsVAAAAAASUVORK5CYII=';
        @endphp
        @php
        $imgNyeri = [
                'nama' => 'Hurts',
                'detail' => [
                    [
                        'nama' => 'No Hurt',
                        'descNilai' => 0,
                        'img' => 'img/skalanyeri/1.png',
                    ],
                    [
                        'nama' => 'Hurts Little Bit',
                        'descNilai' => 2,
                        'img' => 'img/skalanyeri/2.png',
                    ],
                    [
                        'nama' => 'Hurts Little More',
                        'descNilai' => 4,
                        'img' => 'img/skalanyeri/3.png',
                    ],
                    [
                        'nama' => 'Hurts Even More',
                        'descNilai' => 6,
                        'img' => 'img/skalanyeri/4.png',
                    ],
                    [
                        'nama' => 'Hurts Whole Lot',
                        'descNilai' => 8,
                        'img' => 'img/skalanyeri/5.png',
                    ],
                    [
                        'nama' => 'Hurts Worst',
                        'descNilai' => 10,
                        'img' => 'img/skalanyeri/6.png',
                    ],
                ],
            ];
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
            <tr>
                <td colspan="2">
                    <table width="100%" cellspacing="0" cellpadding="0" border="0">
                        <tr>
                            <td width="10%" style="padding: 5px; border-right: 1px solid black;">
                                <img src="{{ 'img/logo-rs.png' }}" style="width: 90px;">
                            </td>
                            <td style="text-align: center; border-right: 1px solid black;">
                                <b>
                                    <span style="font-size: 14px">Asuhan dan Observasi Keperawatan Pasien Kemoterapi Rawat Jalan
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
            <tr>
                <td colspan="2" class="padding-y">
                    <table class="font-2" width="100%" cellspacing="0" cellpadding="0" border="0">
                        <tr>
                            <td width="30%">
                                Tanggal dan Jam : {{ isset($data['$tglv_kunjungan']) ? $data['$tglv_kunjungan'] : '' }}
                            </td>
                            <td width="70%">
                                <span>Sumber data :</span>
                                <input type="checkbox" {{ isset($data['Pasien']) && $data['Pasien'] == 'Pasien' ? 'checked' : '' }}  /><span>Pasien</span>
                                <input type="checkbox" {{ isset($data['Keluarga']) && $data['Keluarga'] == 'Keluarga' ? 'checked' : '' }}  /><span>Keluarga</span>
                                <input type="checkbox" {{ isset($data['Lainnya']) && $data['Lainnya'] == 'Lainnya' ? 'checked' : '' }}  /><span>Lainnya</span>
                                <span>{{ isset($data['LainnyaDetail']) ? $data['LainnyaDetail'] : '' }}</span>
                                <br>
                                <span>Ruangan : {{ isset($data['namaruangan']) ? $data['namaruangan'] : '' }}</span>
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
                                <td colspan="2" width="100%" style="font-size: 14px; text-align: left;">&nbsp;PENGKAJIAN</td>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td colspan="2" class="no-border padding-y">
                    <table class="font-2" width="100%" cellspacing="0" cellpadding="3" border="0">
                        <tr>
                            <td width="50%">
                                <span>TB : {{ isset($data['tinggiBadan']) ? $data['tinggiBadan'] : '     ' }} Cm</span>
                                <span>BB : {{ isset($data['beratBadan']) ? $data['beratBadan'] : '     ' }} Kg</span>
                                <span>BSA : {{ isset($data['BSA']) ? $data['BSA'] : '     ' }} </span>
                            </td>
                            <td width="50%">
                                <span>Diagnosis Medis : {{ isset($data['diagnosaMedis']) ? $data['diagnosaMedis'] : '     ' }} </span>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td colspan="2" class="no-border padding-y">
                    <table class="font-2" width="100%" cellspacing="0" cellpadding="3" border="1">
                        <tr>
                            <td width="30%" rowspan="2">
                                <span>Hasil Pemeriksaan Penunjang / PA :</span>
                            </td>
                            <td width="70%">
                                &nbsp;1. {{ isset($data['HasilSatu']) ? $data['HasilSatu'] : '' }}
                            </td>
                        </tr>
                        <tr>
                            <td>
                                &nbsp;2. {{ isset($data['HasilDua']) ? $data['HasilDua'] : '' }}
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td colspan="2" class="no-border padding-y">
                    <table class="font-2" width="100%" cellspacing="0" cellpadding="3" border="0">
                        <tr>
                            <td width="60%">
                                <span>Regimen Kemoterapi : {{ isset($data['regimenKemoterapi']) ? $data['regimenKemoterapi'] : '' }}</span>
                            </td>
                            <td width="20%">
                                <span>Siklus Ke : {{ isset($data['siklusKe']) ? $data['siklusKe'] : '' }}</span>
                            </td>
                            <td width="20%">
                                <span>Hari Ke : {{ isset($data['hariKe']) ? $data['hariKe'] : '' }}</span>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td colspan="2" class="no-border padding-y">
                    <table class="font-2" width="100%" cellspacing="0" cellpadding="3" border="1">
                        <tr>
                            <td rowspan="4" width="30%">
                                Protokol Kemoterapi :
                            </td>
                            <td width="70%">
                                &nbsp;1. {{ isset($data['obat1']) ? $data['obat1'] : '' }}
                            </td>
                        </tr>
                        <tr>
                            <td>
                                &nbsp;2. {{ isset($data['obat2']) ? $data['obat2'] : '' }}
                            </td>
                        </tr>
                        <tr>
                            <td>
                                &nbsp;3. {{ isset($data['obat3']) ? $data['obat3'] : '' }}
                            </td>
                        </tr>
                        <tr>
                            <td>
                                &nbsp;4. {{ isset($data['obat4']) ? $data['obat4'] : '' }}
                            </td>
                        </tr>
                        <tr>
                            <td>
                                Keluhan Utama :
                            </td>
                            <td>
                                {{ isset($data['keluhanUtama']) ? $data['keluhanUtama'] : '' }}
                            </td>
                        </tr>
                        <tr>
                            <td>
                                Keluhan Saat Pengkajian :
                            </td>
                            <td>
                                {{ isset($data['keluhanSaatPengkajian']) ? $data['keluhanSaatPengkajian'] : '' }}
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
                                <td colspan="2" width="100%" style="font-size: 14px; text-align: left;">&nbsp;PEMERIKSAAN FISIK</td>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td colspan="2" class="no-border padding-y">
                    <table class="font-2" width="100%" cellspacing="0" cellpadding="3" border="0">
                        <tr>
                            <td>
                                <span>Keadaan Umum : {{ isset($data['keadaanumum']) ? $data['keadaanumum'] : '' }}</span>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td colspan="2" class="no-border-bottom padding-y">
                    <table class="font-2" width="100%" cellspacing="0" cellpadding="3" border="0">
                        <tr>
                            <td width="25%">
                                <span>Tanda Vital : TD : {{ isset($data['tekananDarah']) ? $data['tekananDarah'] : '' }} mmHg</span>
                            </td>
                            <td width="25%">
                                <span>Nadi : {{ isset($data['nadi']) ? $data['nadi'] : '' }} x/mnt</span>
                            </td>
                            <td width="25%">
                                <span>Respirasi : {{ isset($data['respirasi']) ? $data['respirasi'] : '' }} x/mnt</span>
                            </td>
                            <td width="25%">
                                <span>Suhu : {{ isset($data['suhu']) ? $data['suhu'] : '' }} °C</span>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td colspan="2" class="no-border padding-y">
                    <table class="font-2" width="100%" cellspacing="0" cellpadding="3" border="1">
                        <tr>
                            <td width="25%" rowspan="2">
                                <span>PENILAIAN NYERI :</span>    
                            </td>
                            <td width="75%">
                                <b>Wong Backer (WBS) dan Numeric Pain Scale (NPS)</b>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                Nyeri : <input type="checkbox" {{ isset($data['tidakNyeri']) && $data['tidakNyeri'] == 'Tidak Nyeri' ? 'checked' : '' }}  /><span>Tidak</span>&nbsp;&nbsp;&nbsp;<input type="checkbox" {{ isset($data['benarNyeri']) && $data['benarNyeri'] == 'Benar Nyeri' ? 'checked' : '' }}  /><span>Ya, Skala :</span> {{ isset($data['skoringNyeri']) ? $data['skoringNyeri'] : '' }}
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr style="page-break-after: always;">
                <td colspan="2" class="no-border padding-y">
                    <table class="font-2" width="100%" cellspacing="0" cellpadding="5" border="1">
                        <tr>
                            <td width="50%" rowspan="7">
                                <img src="{{ 'img/skalanyeri/numericPainRatingScale2.png' }}" alt="Pain Rating" style="width: 340px; padding-bottom: 25px;">
                                @foreach ($imgNyeri['detail'] as $image)
                                    <div style="text-align: center; display: inline-block; max-width: 50px; height: 100px;">
                                        <img src="{{ $image['img'] }}" alt="{{ $image['nama'] }}" style="cursor: pointer; width: 40px;">
                                        <p>{{ $image['descNilai'] }}</p>
                                        <p style="font-size: 6pt;">{{ $image['nama'] }}</p>
                                    </div>
                                @endforeach
                            </td>
                            <td width="50%">
                                <span>Lokasi Nyeri : {{ isset($data['lokasiNyeri']) ? $data['lokasiNyeri'] : '' }}</span>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span>Frekuensi Nyeri : <input type="checkbox" {{ isset($data['frekuensiNyeri']) && $data['frekuensiNyeri'] == 'Jarang' ? 'checked' : '' }}  /><span>Jarang</span></span> <input type="checkbox" {{ isset($data['frekuensiNyeri']) && $data['frekuensiNyeri'] == 'Hilang timbul' ? 'checked' : '' }}  /><span>Hilang timbul</span> <input type="checkbox" {{ isset($data['frekuensiNyeri']) && $data['frekuensiNyeri'] == 'Terus-menerus' ? 'checked' : '' }}  /><span>Terus-menerus</span>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span>Lama nyeri : {{ isset($data['lamaNyeri']) ? $data['lamaNyeri'] : '' }}</span>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span>Menjalar : </span><input type="checkbox" {{ isset($data['menjalar']) && $data['menjalar'] == 'tidakmenjalar' ? 'checked' : '' }}  /><span>Tidak</span> <input type="checkbox" {{ isset($data['menajalar']) && $data['menajalar'] == 'menjalar' ? 'checked' : '' }}  /><span>Ya, Ke :</span> {{ isset($data['detailMenjalar']) ? $data['detailMenjalar'] : '' }}
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span>Kualitas Nyeri : </span><input type="checkbox" {{ isset($data['Tumpul']) && $data['Tumpul'] == 'Tumpul' ? 'checked' : '' }}  /><span>Tumpul</span> <input type="checkbox" {{ isset($data['Tajam']) && $data['Tajam'] == 'Tajam' ? 'checked' : '' }}  /><span>Tajam</span> <input type="checkbox" {{ isset($data['Panas']) && $data['Panas'] == 'Panas/Terbakar' ? 'checked' : '' }}  /><span>Panas/Terbakar</span> <input type="checkbox" {{ isset($data['Lainnya']) && $data['Lainnya'] == 'Lainnya' ? 'checked' : '' }}  /><span>Lainnya</span>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div><span>Faktor pemicu/yang memperberat :</span></div>
                                {{ isset($data['faktorPemicu']) ? $data['faktorPemicu'] : '' }}
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div><span>Faktor yang mengurangi/menghilangkan nyeri :</span></div>
                                {{ isset($data['faktorMengurangi']) ? $data['faktorMengurangi'] : '' }}
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
                                <span>Mulut : Oral muscositis :</span><input type="checkbox" {{ isset($data['salahOralMucositis']) && $data['salahOralMucositis'] == 'Tidak' ? 'checked' : '' }}  /><span>Tidak</span>&nbsp;&nbsp;&nbsp;<input type="checkbox" {{ isset($data['benarOralMucositis']) && $data['benarOralMucositis'] == 'Ya' ? 'checked' : '' }}  /><span>Ya, jelaskan :</span> {{ isset($data['detailOralMucositis']) ? $data['detailOralMucositis'] : '' }}
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span>Leher : Bentuk : </span><input type="checkbox" {{ isset($data['NormalLeher']) && $data['NormalLeher'] == 'Normal' ? 'checked' : '' }}  /><span>Normal</span> <input type="checkbox" {{ isset($data['KelainanLeher']) && $data['KelainanLeher'] == 'Kelainan' ? 'checked' : '' }}  /><span>Kelainan :</span> <input type="checkbox" {{ isset($data['TidakLeher']) && $data['TidakLeher'] == 'Tidak' ? 'checked' : '' }}  /><span>Tidak</span> <input type="checkbox" {{ isset($data['yaLeher']) && $data['yaLeher'] == 'Ya, jelaskan' ? 'checked' : '' }}  /><span>Ya, jelaskan :</span> {{ isset($data['detailbentukLeher']) ? $data['detailbentukLeher'] : '' }}
                            </td> 
                        </tr>
                        <tr>
                            <td>
                                <span>Dada : Bentuk :</span> <input type="checkbox" {{ isset($data['SimetrisDada']) && $data['SimetrisDada'] == 'Simetris' ? 'checked' : '' }}  /><span>Simetris</span> <input type="checkbox" {{ isset($data['KelainanDada']) && $data['KelainanDada'] == 'Kelainan' ? 'checked' : '' }}  /><span>Kelainan :</span> <input type="checkbox" {{ isset($data['TidakDada']) && $data['TidakDada'] == 'Tidak' ? 'checked' : '' }}  /><span>Tidak</span> <input type="checkbox" {{ isset($data['yaDada']) && $data['yaDada'] == 'Ya, jelaskan' ? 'checked' : '' }}  /><span>Ya, jelaskan :</span> {{ isset($data['detailbentukDada']) ? $data['detailbentukDada'] : '' }}
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span>Irama nafas :</span> <input type="checkbox" {{ isset($data['iramaReguler']) && $data['iramaReguler'] == 'reguler' ? 'checked' : '' }}  /><span>Reguler</span> <input type="checkbox" {{ isset($data['iramaIrreguler']) && $data['iramaIrreguler'] == 'irreguler' ? 'checked' : '' }}  /><span>Irreguler</span>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span>Suara nafas :</span> <input type="checkbox" {{ isset($data['suaraNafasNormal']) && $data['suaraNafasNormal'] == 'normal' ? 'checked' : '' }}  /><span>Normal</span> <span>Wheezing :</span> <input type="checkbox" {{ isset($data['suaraNafasW']) && $data['suaraNafasW'] == 'wheezingTidak' ? 'checked' : '' }}  /><span>Tidak</span> <input type="checkbox" {{ isset($data['suaraNafasW']) && $data['suaraNafasW'] == 'wheezingYa' ? 'checked' : '' }}  /><span>Ya, Batuk :</span> <input type="checkbox" {{ isset($data['batuk']) && $data['batuk'] == 'batukTidak' ? 'checked' : '' }}  /><span>Tidak</span> <input type="checkbox" {{ isset($data['batuk']) && $data['batuk'] == 'batukYa' ? 'checked' : '' }}  /><span>Ya, Retraksi :</span> <input type="checkbox" {{ isset($data['retraksi']) && $data['retraksi'] == 'retraksiTidak' ? 'checked' : '' }}  /><span>Tidak</span> <input type="checkbox" {{ isset($data['retraksi']) && $data['retraksi'] == 'retraksiYa' ? 'checked' : '' }}  /><span>Ya</span> 
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span>Sekret :</span> <input type="checkbox" {{ isset($data['sekretTidak']) && $data['sekretTidak'] == 'Tidak' ? 'checked' : '' }}  /><span>Tidak ada</span> <input type="checkbox" {{ isset($data['sekretAda']) && $data['sekretAda'] == 'Ada' ? 'checked' : '' }}  /><span>Ada, warna/jumlah :</span> {{ isset($data['keteranganTerdapatSekret']) ? $data['keteranganTerdapatSekret'] : '' }}
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span>Abdomen : Kembung :</span> <input type="checkbox" {{ isset($data['kembung']) && $data['kembung'] == 'tidak' ? 'checked' : '' }}  /><span>Tidak</span> <input type="checkbox" {{ isset($data['kembung']) && $data['kembung'] == 'ya' ? 'checked' : '' }}  /><span>Ya</span>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span>Bising usus :</span> <input type="checkbox" {{ isset($data['Bisingusus']) && $data['Bisingusus'] == 'BisingususNormal' ? 'checked' : '' }}  /><span>Normal</span> <input type="checkbox" {{ isset($data['Bisingusus']) && $data['Bisingusus'] == 'BisingususUbnormal' ? 'checked' : '' }}  /><span>Abnormal, jelaskan :</span> {{ isset($data['keteranganTerdapatBisingusus']) ? $data['keteranganTerdapatBisingusus'] : '' }}
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span>Ekstremitas : Akral :</span> <input type="checkbox" {{ isset($data['akral']) && $data['akral'] == 'Hangat' ? 'checked' : '' }}  /><span>Hangat</span> <input type="checkbox" {{ isset($data['akral']) && $data['akral'] == 'Dingin' ? 'checked' : '' }}  /><span>Dingin, Pergerakan :</span> <input type="checkbox" {{ isset($data['Pergerakan']) && $data['Pergerakan'] == 'Aktif' ? 'checked' : '' }}  /><span>Aktif</span> <input type="checkbox" {{ isset($data['Pergerakan']) && $data['Pergerakan'] == 'Pasif' ? 'checked' : '' }}  /><span>Pasif, Kekuatan otot :</span> <input type="checkbox" {{ isset($data['kekuatanOtot']) && $data['kekuatanOtot'] == 'Kuat' ? 'checked' : '' }}  /><span>Kuat</span> <input type="checkbox" {{ isset($data['kekuatanOtot']) && $data['kekuatanOtot'] == 'Lemah' ? 'checked' : '' }}  /><span>Lemah</span>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span>Kelainan :</span> <input type="checkbox" {{ isset($data['kelainan']) && $data['kelainan'] == 'Tidak' ? 'checked' : '' }}  /><span>Tidak</span> <input type="checkbox" {{ isset($data['kelainan']) && $data['kelainan'] == 'Ya' ? 'checked' : '' }}  /><span>Ya, Jelaskan :</span> {{ isset($data['kelainanKeterangan']) ? $data['kelainanKeterangan'] : '' }}
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td colspan="2" class="padding-y">
                    <table class="font-2" width="100%" cellspacing="0" cellpadding="0" border="0">
                        <tr>
                            <td width="20%">
                                <span>Peripheral Neuropathy :</span>
                            </td>
                            <td width="80%">
                                <input type="checkbox" {{ isset($data['PeripheralNeropathy']) && $data['PeripheralNeropathy'] == 'Kesemutan ringan di tangan dan ujung kaki' ? 'checked' : '' }}  /><span>Kesemutan ringan di tangan dan ujung kaki</span><br>
                                <input type="checkbox" {{ isset($data['kesemutanSampaiTidakNyaman']) && $data['kesemutanSampaiTidakNyaman'] == 'Kesemutan sampai merasa tidak nyaman' ? 'checked' : '' }}  /><span>Kesemutan sampai merasa tidak nyaman</span><br>
                                <input type="checkbox" {{ isset($data['nyeriSampaiPengaruhADL']) && $data['nyeriSampaiPengaruhADL'] == 'Nyeri sampai mempengaruhi ADL' ? 'checked' : '' }}  /><span>Nyeri sampai mempengaruhi ADL</span><br>
                                <input type="checkbox" {{ isset($data['sangatNyeriJalanAktivitas']) && $data['sangatNyeriJalanAktivitas'] == 'Sangat nyeri saat jalan dan aktivitas' ? 'checked' : '' }}  /><span>Sangat nyeri saat jalan dan aktivitas</span>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td colspan="2" class="no-border padding-y">
                    <table class="font-2" width="100%" cellspacing="0" cellpadding="0" border="0">
                        <tr>
                            <td width="28%">
                                <span>Kulit</span>
                            </td>
                            <td width="2%">
                                :
                            </td>
                            <td width="70%">
                                <span>Warna :</span> <input type="checkbox" {{ isset($data['Warna']) && $data['Warna'] == 'Normal' ? 'checked' : '' }}  /><span>Normal</span> <input type="checkbox" {{ isset($data['Warna']) && $data['Warna'] == 'Ikterus' ? 'checked' : '' }}  /><span>Ikterus</span> <input type="checkbox" {{ isset($data['Warna']) && $data['Warna'] == 'Sianosis' ? 'checked' : '' }}  /><span>Sianosis</span>
                            </td>
                        </tr>
                        <tr>
                            <td width="28%">
                                <span>Membran mukosa</span>
                            </td>
                            <td width="2%" style="text-align: right">
                                :
                            </td>
                            <td width="70%">
                                <input type="checkbox" {{ isset($data['Membran mukosa']) && $data['Membran mukosa'] == 'Lembab' ? 'checked' : '' }}  /><span>Lembab</span> <input type="checkbox" {{ isset($data['Membran mukosa']) && $data['Membran mukosa'] == 'Kering' ? 'checked' : '' }}  /><span>Kering</span> <input type="checkbox" {{ isset($data['Membran mukosa']) && $data['Membran mukosa'] == 'Stomatitis' ? 'checked' : '' }}  /><span>Stomatitis</span>
                            </td>
                        </tr>
                        <tr>
                            <td width="28%">
                                <span>Luka</span>
                            </td>
                            <td width="2%" style="text-align: right">
                                :
                            </td>
                            <td width="70%">
                                <input type="checkbox" {{ isset($data['Luka']) && $data['Luka'] == 'Tidak' ? 'checked' : '' }}  /><span>Tidak</span>
                                <input type="checkbox" {{ isset($data['Luka']) && $data['Luka'] == 'Ya' ? 'checked' : '' }}  /><span>Ya, jelaskan</span>
                                {{ isset($data['keteranganTerdapatLuka']) ? $data['keteranganTerdapatLuka'] : '' }}
                            </td>
                        </tr>
                        <tr>
                            <td width="28%">
                                <span>Perdarahan/lebam di kulit</span>
                            </td>
                            <td width="2%" style="text-align: right">
                                :
                            </td>
                            <td width="70%">
                                <input type="checkbox" {{ isset($data['Perdarahan/lebam di kulit']) && $data['Perdarahan/lebam di kulit'] == 'adaPetique' ? 'checked' : '' }}  /><span>Ada petique di kulit</span>
                                <input type="checkbox" {{ isset($data['Perdarahan/lebam di kulit']) && $data['Perdarahan/lebam di kulit'] == 'adaPerdarahanRingan' ? 'checked' : '' }}  /><span>Ada perdarahan ringan</span>
                            </td>
                        </tr>
                        <tr>
                            <td width="28%">
                                <span>Masalah integritas kulit</span>
                            </td>
                            <td width="2%" style="text-align: right">
                                :
                            </td>
                            <td width="70%">
                                <input type="checkbox" {{ isset($data['Masalah integritas kulit']) && $data['Masalah integritas kulit'] == 'Tidak' ? 'checked' : '' }}  /><span>Tidak</span>
                                <input type="checkbox" {{ isset($data['Masalah integritas kulit']) && $data['Masalah integritas kulit'] == 'Ya' ? 'checked' : '' }}  /><span>Ya, jelaskan :</span> {{ isset($data['keteranganMasalahIntegritasKulit']) ? $data['keteranganMasalahIntegritasKulit'] : '' }}
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
                                <td colspan="2" width="100%" style="font-size: 14px; text-align: left;">SKALA AKTIVITAS</td>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td colspan="2" class="no-border padding-y">
                    <table class="font-2" width="100%" cellspacing="0" cellpadding="3" border="0">
                        <tr>
                            <td width="20%">
                                <span>ECOG SCORE</span>
                            </td>
                            <td width="80%">
                                <input type="checkbox" {{ isset($data['ecogScore']) && $data['ecogScore'] == 0 ? 'checked' : '' }}  /><span>0 Masih sepenuhnya aktif</span><br>
                                <input type="checkbox" {{ isset($data['ecogScore']) && $data['ecogScore'] == 1 ? 'checked' : '' }}  /><span>1 Hanya mampu melakukan pekerjaan ringan</span><br>
                                <input type="checkbox" {{ isset($data['ecogScore']) && $data['ecogScore'] == 2 ? 'checked' : '' }}  /><span>2 Hanya mampu melakukan perawatan diri sendiri dan 50% aktivitas di atas tempat tidur</span><br>
                                <input type="checkbox" {{ isset($data['ecogScore']) && $data['ecogScore'] == 3 ? 'checked' : '' }}  /><span>3 Hanya mampu melakukan perawatan diri yang terbatas, 50% aktivitas dilakukan diatas tempat tidur/kursi roda</span><br>
                                <input type="checkbox" {{ isset($data['ecogScore']) && $data['ecogScore'] == 4 ? 'checked' : '' }}  /><span>4 Tidak mampu melakukan perawatan diri dan ambulasi secara total di tempat tidur/kursi roda</span><br>
                                <input type="checkbox" {{ isset($data['ecogScore']) && $data['ecogScore'] == 5 ? 'checked' : '' }}  /><span>5 Meninggal</span><br>
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
                                <td colspan="2" width="100%" style="font-size: 14px; text-align: left;">PENGKAJIAN SIMPTOM (EDMONTON SYMPTOM ASSESMENT SCALE = ESAS)</td>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td colspan="2" class="no-border padding-y">
                    <table class="font-2" width="100%" cellspacing="0" cellpadding="0" border="0">
                        @php
                            $skalaPenilaian = [
                                [
                                    'nama' => 'Nyeri',
                                    'deskripsi' => 'Tidak nyeri',
                                    'deskripsiAkhir' => 'Nyeri Hebat',
                                ],
                                [
                                    'nama' => 'Kelelahan',
                                    'deskripsi' => 'Tidak lelah',
                                    'deskripsiAkhir' => 'Perasaan Lelah Hebat',
                                ],
                                [
                                    'nama' => 'Mual',
                                    'deskripsi' => 'Tidak mual',
                                    'deskripsiAkhir' => 'Mual Hebat',
                                ],
                                [
                                    'nama' => 'Depresi',
                                    'deskripsi' => 'Tidak Depresi',
                                    'deskripsiAkhir' => 'Depresi Berat',
                                ],
                                [
                                    'nama' => 'Kecemasan',
                                    'deskripsi' => 'Tidak cemas',
                                    'deskripsiAkhir' => 'Cemas Berat',
                                ],
                                [
                                    'nama' => 'Mengantuk',
                                    'deskripsi' => 'Tidak mengantuk',
                                    'deskripsiAkhir' => 'Mengantuk Berat',
                                ],
                                [
                                    'nama' => 'Nafsu Makan',
                                    'deskripsi' => 'Nafsu makan',
                                    'deskripsiAkhir' => 'Tidak Nafsu Makan',
                                ],
                                [
                                    'nama' => 'Kesehatan',
                                    'deskripsi' => 'Merasa sehat & segar bugar',
                                    'deskripsiAkhir' => 'Perasaan Tidak Berdaya',
                                ],
                                [
                                    'nama' => 'Sesak Nafas',
                                    'deskripsi' => 'Tidak sesak nafas',
                                    'deskripsiAkhir' => 'Sesak Nafas Hebat',
                                ],
                                [
                                    'nama' => 'Masalah',
                                    'deskripsi' => 'Tidak ada masalah',
                                    'deskripsiAkhir' => 'Masalah Berat',
                                ]
                            ];
                        @endphp
                        @foreach ($skalaPenilaian as $skala)
                        <tr>
                            <td class="tr">
                                <span>{{ $skala['deskripsi'] }} &nbsp;</span>
                            </td>
                            @for ($i = 0; $i <= 10; $i++)
                                <td>
                                    <input type="checkbox" {{ isset($data[$skala['nama']]) && $data[$skala['nama']] === $i ? 'checked' : '' }} />
                                    <span>{{ $i }}</span>
                                </td>
                            @endfor
                            <td class="tl">
                                <span>{{ $skala['deskripsiAkhir'] }}</span>
                            </td>
                        </tr>
                    @endforeach
                    <tr>
                        <td colspan="12">
                            <br />
                            <span>Keterangan : Ringan <span class="logo">≤</span> 3&nbsp;&nbsp; Sedang : 4 - 6 &nbsp;&nbsp; Berat <span class="logo">≥</span> 7</span>
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
                                <td colspan="2" width="100%" style="font-size: 14px; text-align: left;">RESIKO JATUH</td>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td colspan="2" class="no-border">
                    <table class="font-2" width="100%" cellspacing="0" cellpadding="0" border="0">
                        <tr>
                            <td>
                                <span>a. Perhatikan cara berjalan pasien saat akan duduk di kursi. Apakah pasien tampak tidak seimbang</span> <br>
                                &nbsp;&nbsp;&nbsp;(sempoyongan/limbung) ? <input type="checkbox" {{ isset($data['pilihan']) && $data['pilihan'] == 'Ya' ? 'checked' : '' }}  /><span>Ya</span>&nbsp;&nbsp;&nbsp; <input type="checkbox" {{ isset($data['pilihan']) && $data['pilihan'] == 'Tidak' ? 'checked' : '' }}  /><span>Tidak</span>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span>b. Apakah pasien memegang pinggiran kursi atau meja atau benda lain sebagai penopang saat akan
                                    duduk ?</span> <br>
                                    &nbsp;&nbsp;&nbsp;<input type="checkbox" {{ isset($data['pegangKursisaatDuduk']) && $data['pegangKursisaatDuduk'] == 'Ya' ? 'checked' : '' }}  /><span>Ya</span>&nbsp;&nbsp;&nbsp; <input type="checkbox" {{ isset($data['pegangKursisaatDuduk']) && $data['pegangKursisaatDuduk'] == 'Tidak' ? 'checked' : '' }}  /><span>Tidak</span>
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
                                <td colspan="2" width="100%" style="font-size: 14px; text-align: left;">EVALUASI AKSES KEMOTERAPI</td>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr style="page-break-after: always;">
                <td colspan="2" class="no-border padding-y">
                    <table class="font-2" width="100%" cellspacing="0" cellpadding="0" border="0">
                        <tr>
                            <td>
                                <b>Akses Kemoterapi :</b> <input type="checkbox" {{ isset($data['aksesKemoterapi']) && $data['aksesKemoterapi'] == 'Perifer' ? 'checked' : '' }}  /><span>Perifer</span> <input type="checkbox" {{ isset($data['aksesKemoterapi']) && $data['aksesKemoterapi'] == 'Chemoport' ? 'checked' : '' }}  /><span>Chemoport</span> <input type="checkbox" {{ isset($data['aksesKemoterapi']) && $data['aksesKemoterapi'] == 'Lain-lainnya' ? 'checked' : '' }}  /><span>Lain-lainnya</span>
                                <br>
                                <span>Kondisi akses kemoterapi saat ini :</span> <input type="checkbox" {{ isset($data['aksesKemoterapiKeterangan']) && $data['aksesKemoterapiKeterangan'] == 'Baik' ? 'checked' : '' }}  /><span>Baik</span> <input type="checkbox" {{ isset($data['aksesKemoterapiKeterangan']) && $data['aksesKemoterapiKeterangan'] == 'Bermasalah' ? 'checked' : '' }}  /><span>Bermasalah</span>
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
                                <td colspan="2" width="100%" style="font-size: 14px; text-align: left;">DIAGNOSA KEPERAWATAN</td>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            @php
                $diagnosaKeperawatan = [
                    [
                        'deskripsi' => "Bersihan jalan nafas b.d. spasme jalan nafas, hipersekresi jalan nafas, disfungsi neuromuskuler, adanya jalan nafas buatan, sekresi yang tertahan, hiperflasia dinding jalan nafas, proses infeksi, respon alergi",
                        'model' => "bersihanJalanNafas"
                    ],
                    [
                        'deskripsi' => "Nyeri akut b.d agen pencedera fisiologis, agen pencedera kimiawi, agen pencedera fisik",
                        'model' => "nyeriAgenPencedera"
                    ],
                    [
                        'deskripsi' => "Nyeri kronis b.d kondisi muskuloskeletal kronis, kerusakan sistem saraf, penekanan saraf, infiltrasi tumor",
                        'model' => "nyeriKronis"
                    ],
                    [
                        'deskripsi' => "Nausea b.d gangguan biokimiawi, gangguan pada esofagus, iritasi lambung, distensi lambung, tumor terlokalisasi, efek agen farmakologis, efek toksin",
                        'model' => "nyeriNausea"
                    ],
                    [
                        'deskripsi' => "Ansietas b.d krisis situasional, ancaman terhadap kematian, kekhawatiran mengalami kegagalan, disfungsi fungsi keluarga, kurang terpapar informasi",
                        'model' => "nyeriAnsietas"
                    ],
                    [
                        'deskripsi' => "Risiko gangguan integritas kulit/jaringan b.d perubahan sirkulasi, perubahan status nutrisi, kekurangan/kelebihan volume cairan, penurunan mobilitas, bahan kimia iritatif, suhu lingkungan yang ekstrem, faktor mekanis (penekanan pada tonjolan tulang, gesekan), efek samping terapi radiasi, kelembaban",
                        'model' => "nyeriRisikoGangguanIntegritasKulitJaringan"
                    ],
                    [
                        'deskripsi' => "Gangguan citra tubuh b.d perubahan struktur/bentuk tubuh, perubahan fungsi tubuh, perubahan fungsi kognitif, efek tindakan/pengobatan (mis. pembedahan, kemoterapi, terapi radiasi)",
                        'model' => "nyeriGangguanCitraTubuh"
                    ],
                    [
                        'deskripsi' => "Keletihan b.d gangguan tidur, kondisi fisiologis (mis. penyakit kronis, penyakit terminal, anemia, malnutrisi), program perawatan/pengobatan jangka panjang, stres berlebihan, depresi",
                        'model' => "keletihan"
                    ],
                    [
                        'deskripsi' => "Risiko infeksi b.d penyakit kronis, efek prosedur invasif, malnutrisi, peningkatan paparan organisme patogen, ketidakadekuatan pertahan tubuh primer, ketidakkuatan pertahanan tubuh sekunder",
                        'model' => "resikoInfeksi"
                    ],
                    [
                        'deskripsi' => "Risiko alergi b.d terpapar zat alergen (mis. zat kimia, agen farmakologis)",
                        'model' => "risikoAlergi"
                    ]
                ];
            @endphp
            @foreach ($diagnosaKeperawatan as $diagnosa)
                <tr>
                    <td colspan="2" class="padding-y">
                        <table class="font-2" width="100%" cellspacing="0" cellpadding="0" border="0">
                            <tr>
                                <td>
                                    <input type="checkbox" {{ isset($data[$diagnosa['model']]) && $data[$diagnosa['model']] == $diagnosa['model'] ? 'checked' : '' }} />
                                    <span>{{ $diagnosa['deskripsi'] }}</span>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            @endforeach
            <tr class="bg-gray">
                <td width="100%" colspan="2" class="padding-y">
                    <table width="100%" cellspacing="0" cellpadding="0" border="0">
                        <tr>
                            <td>
                                <td colspan="2" width="100%" style="font-size: 14px; text-align: left;">RENCANATINDAKAN KEPERAWATAN</td>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            @php
                $rencanaTindakanKeperawatan = [
                    ["model" => "monitor", "deskripsi" => "1. Monitor tanda-tanda vital", "model2" => "rencanaTindakanKeperawatan", "deskripsi2" => "11. Berikan edukasi sesuai kebutuhan pasien"],
                    ["model" => "aturPosisi", "deskripsi" => "2. Atur posisi semifowler/fowler", "model2" => "latihanKegiatan", "deskripsi2" => "12. Latih kegiatan pengalihan untuk mengurangi ansietas"],
                    ["model" => "ajarkanTeknik", "deskripsi" => "3. Ajarkan teknik nonfarmakologi (distraksi, relaksasi,guided imagery)", "model2" => "monitorTanda", "deskripsi2" => "13. Monitor tanda dan gejala alergi"],
                    ["model" => "kendalikanFaktor", "deskripsi" => "4. Kendalikan faktor lingkungan penyebab mual", "model2" => "motivasiUntukMenguatkan", "deskripsi2" => "14. Motivasi untuk menguatkan dukungan keluarga atau orang terdekat"],
                    ["model" => "berikanOksigen", "deskripsi" => "5. Berikan oksigen", "model2" => "anjurkanTirahBaring", "deskripsi2" => "15. Anjurkan tirah baring"],
                    ["model" => "kolaborasiDalamPemberianTerapi", "deskripsi" => "6. Kolaborasi dalam pemberian terapi", "model2" => "anjurkanAktivitas", "deskripsi2" => "16. Anjurkan melakukan aktivitas secara bertahap"],
                    ["model" => "lakukanProsedurPemberianObat", "deskripsi" => "7. Lakukan prosedur pemberian obat yang aman dan tepat", "model2" => "lainnya", "deskripsi2" => "17. "],
                    ["model2" => "kosong1", "deskripsi2" => "18. ", "model" => "lakukanPemantauanAksesVaskuler", "deskripsi" => "8. Lakukan pemantauan akses vaskuler"],
                    ["model2" => "kosong2", "deskripsi2" => "19. ", "model" => "awasiTanda", "deskripsi" => "9. Awasi tanda dan gejala ekstravasasi"],
                    ["model2" => "kosong3", "deskripsi2" => "20. ", "model" => "berikanKompres", "deskripsi" => "10. Berikan kompres dingin atau hangat"],
                ];
            @endphp

            <tr>
                <td colspan="2" class="padding-y">
                    <table class="font-2" width="100%" cellspacing="0" cellpadding="0" border="1">
                        @foreach ($rencanaTindakanKeperawatan as $key => $item)
                            @if ($key < 10)
                                <tr>
                                    <td width="50%" class="vt">
                                        <input type="checkbox" {{ isset($data[$item['model']]) && $data[$item['model']] == $item['model'] ? 'checked' : '' }} />
                                        <span>{{ $item['deskripsi'] }}</span>
                                    </td>
                                    <td width="50%" class="vt">
                                        <input type="checkbox" {{ isset($data[$item['model2']]) && $data[$item['model2']] == $item['model2'] ? 'checked' : '' }} />
                                        <span>{{ $item['deskripsi2'] }}</span>
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                    </table>
                </td>
            </tr>
            <tr class="bg-gray">
                <td width="100%" colspan="2" class="padding-y">
                    <table width="100%" cellspacing="0" cellpadding="0" border="0">
                        <tr>
                            <td>
                                <td colspan="2" width="100%" style="font-size: 14px; text-align: left;">PEMBERIAN PREMEDIKASI / THERAPI INTRAKEMOTERAPI</td>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td colspan="2" class="padding-y padding-x">
                    <table class="font-2" width="100%" cellspacing="0" cellpadding="3" border="1">
                        <tr>
                            <td rowspan="2" class="vc tc">
                                Nama Obat
                            </td>
                            <td rowspan="2" class="vc tc">
                                Dosis
                            </td>
                            <td rowspan="2" class="vc tc">
                                Cara Pemberian
                            </td>
                            <td rowspan="2" class="vc tc">
                                Waktu
                            </td>
                            <td colspan="4" class="vc tc">
                                Nama Petugas
                            </td>
                        </tr>
                        <tr>
                            <td class="vc tc">
                                Perawat 1
                            </td>
                            <td class="vc tc">
                                Paraf
                            </td>
                            <td class="vc tc">
                                Perawat 2
                            </td>
                            <td class="vc tc">
                                Paraf
                            </td>
                        </tr>
                        @foreach ($data['detailObatResep'] as $key => $item)
                            <tr>
                                <td class="vt tc">
                                    {{ isset($item['obat']['namaproduk']) ? $item['obat']['namaproduk'] : '' }}
                                </td>
                                <td class="vt tc">
                                    {{ isset($item['dosis']) ? $item['dosis'] : '' }}
                                </td>
                                <td class="vt tc">
                                    {{ isset($item['caraPemberian']) ? $item['caraPemberian'] : '' }}
                                </td>
                                <td class="vt tc">
                                    {{ isset($item['waktu']) ? $item['waktu'] : '' }}
                                </td>
                                <td class="vt tc">
                                    {{ isset($item['perawat']['label']) ? $item['perawat']['label'] : '' }}
                                </td>
                                <td class="vt tc">
                                    @if (isset($item['signatures']['TTDperawat1']) && $item['signatures']['TTDperawat1'] != $imgDefault)
                                        <img width="80px" src="{!! isset($item['signatures']['TTDperawat1']) ? $item['signatures']['TTDperawat1'] : '' !!}" alt="Perawat 1">
                                    @elseif(isset($item['perawat']['label']))
                                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=80x80&data={{ $item['perawat']['label'] }}">
                                    @else
                                        <span></span>
                                    @endif
                                </td>
                                <td class="vt tc">
                                    {{ isset($item['perawat2']['label']) ? $item['perawat2']['label'] : '' }}
                                </td>
                                <td class="vt tc">
                                    @if (isset($item['signatures']['TTDperawat2']) && $item['signatures']['TTDperawat2'] != $imgDefault)
                                        <img width="80px" src="{!! isset($item['signatures']['TTDperawat2']) ? $item['signatures']['TTDperawat2'] : '' !!}" alt="Perawat 1">
                                    @elseif(isset($item['perawat2']['label']))
                                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=80x80&data={{ $item['perawat2']['label'] }}">
                                    @else
                                        <span></span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </table>
                </td>
            </tr>
            <tr class="bg-gray" style="page-break-before: always;">
                <td width="100%" colspan="2" class="padding-y">
                    <table width="100%" cellspacing="0" cellpadding="0" border="0">
                        <tr>
                            <td>
                                <td colspan="2" width="100%" style="font-size: 14px; text-align: left;">TIME OUT KEMOTERAPI</td>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr style="page-break-after: always;">
                <td colspan="2" class="padding-y padding-x">
                    <table class="font-2" width="100%" cellspacing="0" cellpadding="3" border="1">
                        <tr>
                            <td width="25%" class="vt tc">
                                Kriteria
                            </td>
                            <td width="15%" class="vt tc">
                                Obat 1
                                <br />
                                {{ isset($data['obat1']) ? $data['obat1'] : '' }}
                            </td>
                            <td width="15%" class="vt tc">
                                Obat 2
                                <br />
                                {{ isset($data['obat2']) ? $data['obat2'] : '' }}
                            </td>
                            <td width="15%" class="vt tc">
                                Obat 3
                                <br />
                                {{ isset($data['obat3']) ? $data['obat3'] : '' }}
                            </td>
                            <td width="15%" class="vt tc">
                                Obat 4
                                <br />
                                {{ isset($data['obat4']) ? $data['obat4'] : '' }}
                            </td>
                            <td width="15%" class="vt tc">
                                Obat 5
                                <br />
                                {{ isset($data['obat5']) ? $data['obat5'] : '' }}
                            </td>
                        </tr>
                        @php
                            $kriteria = [
                                [
                                    'nama' => 'Benar Nama obat',
                                    'detail' => [
                                        [
                                            'deskripsi' => 'Ya',
                                            'model' => 'CB_nama_obat',
                                            'type' => 'checkBox',
                                        ],
                                        [
                                            'deskripsi' => 'Tidak',
                                            'model' => 'CB_nama_obat',
                                            'type' => 'checkBox',
                                        ],
                                    ],
                                ],
                                [
                                    'nama' => 'Benar dosis obat',
                                    'detail' => [
                                        [
                                            'deskripsi' => 'Ya',
                                            'model' => 'CB_benar_dosis_obat',
                                            'type' => 'checkBox',
                                        ],
                                        [
                                            'deskripsi' => 'Tidak',
                                            'model' => 'CB_benar_dosis_obat',
                                            'type' => 'checkBox',
                                        ],
                                    ],
                                ],
                                [
                                    'nama' => 'Benar cara / rute pemberian',
                                    'detail' => [
                                        [
                                            'deskripsi' => 'Ya',
                                            'model' => 'CB_cara_pemberian_obat',
                                            'type' => 'checkBox',
                                        ],
                                        [
                                            'deskripsi' => 'Tidak',
                                            'model' => 'CB_cara_pemberian_obat',
                                            'type' => 'checkBox',
                                        ],
                                    ],
                                ],
                                [
                                    'nama' => 'Benar tanggal dan jam kadaluwarsa obat',
                                    'detail' => [
                                        [
                                            'deskripsi' => 'Ya',
                                            'model' => 'CB_kadaluwarsa',
                                            'type' => 'checkBox',
                                        ],
                                        [
                                            'deskripsi' => 'Tidak',
                                            'model' => 'CB_kadaluwarsa',
                                            'type' => 'checkBox',
                                        ],
                                    ],
                                ],
                                [
                                    'nama' => 'Identitas pada etiket obat sama dengan identitas pada gelang pasien',
                                    'detail' => [
                                        [
                                            'deskripsi' => 'Ya',
                                            'model' => 'CB_identitas_obat',
                                            'type' => 'checkBox',
                                        ],
                                        [
                                            'deskripsi' => 'Tidak',
                                            'model' => 'CB_identitas_obat',
                                            'type' => 'checkBox',
                                        ],
                                    ],
                                ],
                                [
                                    'nama' => 'Kecepatan tetesan infus',
                                    'detail' => [
                                        [
                                            'deskripsi' => 'tts/Mnt',
                                            'model' => 'kecepatanTetesan',
                                            'type' => 'input',
                                        ],
                                    ],
                                ],
                                [
                                    'nama' => 'Nama dan tanda tangan perawat',
                                    'detail' => [
                                        [
                                            'deskripsi' => 'TandaTangan',
                                            'model' => 'tandaTanganPerawat',
                                            'type' => 'TandaTangan',
                                        ],
                                    ],
                                ],
                                [
                                    'nama' => 'Nama dan tanda tangan pasien/keluarga',
                                    'detail' => [
                                        [
                                            'deskripsi' => 'TandaTangan',
                                            'model' => 'tandaTanganPasienKeluarga',
                                            'type' => 'TandaTangan',
                                        ],
                                    ],
                                ],
                            ];
                        @endphp
                        @foreach ($kriteria as $index => $item)
                            <tr>
                                <td>{{ $item['nama'] }}</td>
                                @php
                                    $t = 0;
                                @endphp
                                @for ($n = 1; $n <= 5; $n++)
                                    <td class="vc">
                                        @if (count($item['detail']) > 0)
                                            <div>
                                                @foreach ($item['detail'] as $i => $detail)
                                                    @if ($detail['type'] === 'checkBox')
                                                        <div style="display: inline-flex; align-items: center; vertical-align:bottom;">
                                                            <!-- Checkbox Condition -->
                                                            <input type="checkbox" 
                                                                {{ isset($data[$detail['model'].'-'.$n]) && $data[$detail['model'].'-'.$n] == $detail['deskripsi'].'-'.$n ? 'checked' : '' }}  
                                                            />
                                                            <span>{{ isset($detail['deskripsi']) ? $detail['deskripsi'] : '' }}</span>
                                                        </div>
                                                        @endif
                                                        @if ($detail['type'] === 'input')
                                                            <!-- Input Field Condition -->
                                                            {{ isset($data[$detail['model'].'-'.$n]) ? $data[$detail['model'].'-'.$n] : '.............' }}&nbsp; {{ isset($detail['deskripsi']) ? $detail['deskripsi'] : '' }}
                                                        @endif
                
                                                        @if ($detail['model'] === 'tandaTanganPerawat')
                                                        <div class="tc">
                                                            <!-- Signature & Autocomplete for Nurse -->
                                                            <span>Perawat 1</span>
                                                            <br />
                                                            {{ isset($data['namaPerawat'.'-'.$n]['label']) ? $data['namaPerawat'.'-'.$n]['label'] : '' }}
                                                            <br />
                                                            @isset($data['namaPerawat'.'-'.$n]['label'])
                                                            <img src="data:image/svg+xml;base64,{{ base64_encode(QrCode::format('svg')->margin(2)->size(50)->generate($data['namaPerawat'.'-'.$n]['label'] ?? '')) }}" 
                                                            alt="QR Code">
                                                            @endisset
                                                            <br />
                                                            <span>Perawat 2</span>
                                                            <br />
                                                            {{ isset($data['namaPerawat2'.'-'.$n]['label']) ? $data['namaPerawat2'.'-'.$n]['label'] : '' }}
                                                            <br />
                                                            @isset($data['namaPerawat2'.'-'.$n]['label'])
                                                            <img src="data:image/svg+xml;base64,{{ base64_encode(QrCode::format('svg')->margin(2)->size(50)->generate($data['namaPerawat2'.'-'.$n]['label'] ?? '')) }}" 
                                                            alt="QR Code">
                                                            @endisset
                                                        </div>
                                                        @endif
                                                        @if ($detail['model'] === 'tandaTanganPasienKeluarga')
                                                            <!-- Signature & Input for Patient/Keluarga -->
                                                            <img width="80px" src="{!! $data[$detail['model']][$t] !!}" alt="TTD Pasien">
                                                        @endif
                                                @endforeach
                                            </div>
                                        @endif
                                    </td>
                                    @php
                                        $t++
                                    @endphp
                                @endfor
                            </tr>
                        @endforeach
                    </table>
                </td>
            </tr>
        </table>
    </section>
</body>

</html>
