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
            $imgDefault = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAJYAAACWCAYAAAA8AXHiAAAAAXNSR0IArs4c6QAAAqFJREFUeF7t0jENAAAMw7CVP+mhyOcC6BF5ZwoEBRZ8ulTgwIIgKQBWktUpWAwkBcBKsjoFi4GkAFhJVqdgMZAUACvJ6hQsBpICYCVZnYLFQFIArCSrU7AYSAqAlWR1ChYDSQGwkqxOwWIgKQBWktUpWAwkBcBKsjoFi4GkAFhJVqdgMZAUACvJ6hQsBpICYCVZnYLFQFIArCSrU7AYSAqAlWR1ChYDSQGwkqxOwWIgKQBWktUpWAwkBcBKsjoFi4GkAFhJVqdgMZAUACvJ6hQsBpICYCVZnYLFQFIArCSrU7AYSAqAlWR1ChYDSQGwkqxOwWIgKQBWktUpWAwkBcBKsjoFi4GkAFhJVqdgMZAUACvJ6hQsBpICYCVZnYLFQFIArCSrU7AYSAqAlWR1ChYDSQGwkqxOwWIgKQBWktUpWAwkBcBKsjoFi4GkAFhJVqdgMZAUACvJ6hQsBpICYCVZnYLFQFIArCSrU7AYSAqAlWR1ChYDSQGwkqxOwWIgKQBWktUpWAwkBcBKsjoFi4GkAFhJVqdgMZAUACvJ6hQsBpICYCVZnYLFQFIArCSrU7AYSAqAlWR1ChYDSQGwkqxOwWIgKQBWktUpWAwkBcBKsjoFi4GkAFhJVqdgMZAUACvJ6hQsBpICYCVZnYLFQFIArCSrU7AYSAqAlWR1ChYDSQGwkqxOwWIgKQBWktUpWAwkBcBKsjoFi4GkAFhJVqdgMZAUACvJ6hQsBpICYCVZnYLFQFIArCSrU7AYSAqAlWR1ChYDSQGwkqxOwWIgKQBWktUpWAwkBcBKsjoFi4GkAFhJVqdgMZAUACvJ6hQsBpICYCVZnYLFQFIArCSrU7AYSAqAlWR1ChYDSQGwkqxOwWIgKQBWktUpWAwkBcBKsjp9QYIAl6bSsVAAAAAASUVORK5CYII=';
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
            <tr class="bg-gray">
                <td width="100%" colspan="2" class="padding-y">
                    <table width="100%" cellspacing="0" cellpadding="0" border="0">
                        <tr>
                            <td>
                                <td colspan="2" width="100%" style="font-size: 14px; text-align: left;">EVALUASI</td>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td colspan="2" class="padding-y">
                    <table class="font-2" width="100%" cellspacing="0" cellpadding="0" border="0">
                        <tr>
                            <td>
                                <span>Keluhan pasien :</span> {{ isset($data['keluhanPasien']) ? $data['keluhanPasien'] : '' }}
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td colspan="2" class="padding-y">
                    <table class="font-2" width="100%" cellspacing="0" cellpadding="0" border="0">
                        <tr>
                            <td>
                                <span>Keadaan umum :</span> <input type="checkbox" {{ isset($data['keadaanUmum']) && $data['keadaanUmum'] == 'keadaanUmumBaik' ? 'checked' : '' }}  /><span>Baik</span> <input type="checkbox" {{ isset($data['keadaanUmum']) && $data['keadaanUmum'] == 'keadaanUmumLemah' ? 'checked' : '' }}  /><span>Lemah</span> <input type="checkbox" {{ isset($data['keadaanUmum']) && $data['keadaanUmum'] == 'keadaanUmumSangatLemah' ? 'checked' : '' }}  /><span>Sangat Lemah</span> 
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td colspan="2" class="padding-y">
                    <table class="font-2" width="100%" cellspacing="0" cellpadding="0" border="0">
                        <tr>
                            <td>
                                <span>Tanda vital :</span> <span>TD :</span> {{ isset($data['tekananDarahEvaluasi']) ? $data['tekananDarahEvaluasi'] : '........' }} mmHg <span>Nadi :</span> {{ isset($data['nadiEvaluasi']) ? $data['nadiEvaluasi'] : '' }} x/Mnt <span>Respirasi :</span> {{ isset($data['respirasiEvaluasi']) ? $data['respirasiEvaluasi'] : '' }} <span>Suhu :</span> {{ isset($data['suhuEvaluasi']) ? $data['suhuEvaluasi'] : '' }} °C <span>Skala nyeri :</span> {{ isset($data['skalaNyeriEvaluasi']) ? $data['skalaNyeriEvaluasi'] : '' }}
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td colspan="2" class="padding-y">
                    <table class="font-2" width="100%" cellspacing="0" cellpadding="0" border="0">
                        <tr>
                            <td>
                                <span>Kondisi Akses Kemoterapi :</span> <input type="checkbox" {{ isset($data['Kondisi']) && $data['Kondisi'] == 'KondisiBaik' ? 'checked' : '' }}  /><span>Baik</span> <input type="checkbox" {{ isset($data['Kondisi']) && $data['Kondisi'] == 'KondisiBengkak' ? 'checked' : '' }}  /><span>Bengkak</span> <input type="checkbox" {{ isset($data['Kondisi']) && $data['Kondisi'] == 'KondisiLainnya' ? 'checked' : '' }}  /><span>Lain-lain :</span> {{ isset($data['kondisiLainnya']) ? $data['kondisiLainnya'] : '' }}
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
                                <td colspan="2" width="100%" style="font-size: 14px; text-align: left;">INSTRUKSI PEMULANGAN PASIEN</td>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td colspan="2" class="padding-y no-border-bottom">
                    <table class="font-2" width="100%" cellspacing="0" cellpadding="0" border="0">
                        <tr>
                            <td>
                                <span>Obat di rumah :</span>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td colspan="2" class="no-border">
                    <table class="font-2" width="100%" cellspacing="0" cellpadding="5" border="1">
                        @for ($n = 1; $n <= 5; $n++)
                        <tr>
                            <td width="50%">
                                <div>
                                    <span>{{ $n }}. {{ isset($data['obatRumah'.$n]['namaproduk']) ? $data['obatRumah'.$n]['namaproduk'] : '' }}</span>
                                </div>
                                <div>
                                    <span>{{ isset($data['obatRumahInstruksi'.$n]) ? 'Instruksi Obat :' . $data['obatRumahInstruksi'.$n] : '................................................................' }}</span>
                                </div>
                            </td>
                            <td width="50%">
                                <div>
                                    <span>{{ $n + 5 }}. {{ isset($data['obatRumah'.($n + 5)]['namaproduk']) ? $data['obatRumah'.($n + 5)]['namaproduk'] : '' }}</span>
                                </div>
                                <div>
                                    <span>{{ isset($data['obatRumahInstruksi'.($n + 5)]) ? 'Instruksi Obat :' . $data['obatRumahInstruksi'.($n + 5)] : '................................................................' }}</span>
                                </div>
                            </td>
                        </tr>
                        @endfor
                        <tr>
                            <td>
                                <div>
                                    <span>Tanggal kontrol kembali :</span>
                                </div>
                                <div>
                                    <span>{{ isset($data['tanggalkontrolKembali']) ? date('d-m-Y', strtotime($data['tanggalkontrolKembali'])) : "" }}</span>
                                </div>
                            </td>
                            <td>
                                <div>
                                    <span>Poli tujuan :</span>
                                </div>
                                <div>
                                    <span>{{ isset($data['poliTujuan']['label']) ? $data['poliTujuan']['label'] : '' }}</span>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <span>Evaluasi diagnosa keperawatan</span>
                            </td>
                        </tr>
                        @for ($n = 1; $n <= 4; $n++)
                        <tr>
                            <td colspan="2" class="padding-y">
                                <div style="white-space: pre-line;"><span style="white-space: pre-line;">{{ $n }}. {{ isset($data['evaluasi'.$n]) ? $data['evaluasi'.$n] : '' }}</span></div>
                            </td>
                        </tr>
                        @endfor
                    </table>
                </td>
            </tr>
            <tr>
                <td colspan="2" class="no-border padding-y">
                    <table class="font-2" style="table-layout: fixed;" width="100%" cellspacing="0" cellpadding="0" border="0">
                        <tr>
                            <td width="70%">
                                
                            </td>
                            <td width="30%" class="tc vt">
                                <span>Nama dan Tanda Tangan Perawat,</span>
                                <br />
                                @if (isset($data['evaluasiDiagnosaTTDPerawat']) && $data['evaluasiDiagnosaTTDPerawat'] != $imgDefault)
                                    <img width="80px" src="{!! isset($data['evaluasiDiagnosaTTDPerawat']) ? $data['evaluasiDiagnosaTTDPerawat'] : '' !!}" alt="Perawat 1">
                                @elseif(isset($data['evaluasiDiagnosaNamaTTDPerawat']['label']))
                                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=80x80&data={{ $data['evaluasiDiagnosaNamaTTDPerawat']['label'] }}">
                                @else
                                    <span></span>
                                @endif
                                <br />
                                {{ isset($data['evaluasiDiagnosaNamaTTDPerawat']['label']) ? $data['evaluasiDiagnosaNamaTTDPerawat']['label'] : '' }}
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </section>
</body>

</html>
