<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>EMR - Rujukan Pasien</title>
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

        .mt-5 {
            margin-top: 5px;
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
            background-color: #00B0F0;
        }

        .tc {
            text-align: center;
        }

        .font {
            font-size: 9pt;
        }

    </style>

    @stack('style')

</head>

@php
    $imgDefault = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAJYAAACWCAYAAAA8AXHiAAAAAXNSR0IArs4c6QAAAqFJREFUeF7t0jENAAAMw7CVP+mhyOcC6BF5ZwoEBRZ8ulTgwIIgKQBWktUpWAwkBcBKsjoFi4GkAFhJVqdgMZAUACvJ6hQsBpICYCVZnYLFQFIArCSrU7AYSAqAlWR1ChYDSQGwkqxOwWIgKQBWktUpWAwkBcBKsjoFi4GkAFhJVqdgMZAUACvJ6hQsBpICYCVZnYLFQFIArCSrU7AYSAqAlWR1ChYDSQGwkqxOwWIgKQBWktUpWAwkBcBKsjoFi4GkAFhJVqdgMZAUACvJ6hQsBpICYCVZnYLFQFIArCSrU7AYSAqAlWR1ChYDSQGwkqxOwWIgKQBWktUpWAwkBcBKsjoFi4GkAFhJVqdgMZAUACvJ6hQsBpICYCVZnYLFQFIArCSrU7AYSAqAlWR1ChYDSQGwkqxOwWIgKQBWktUpWAwkBcBKsjoFi4GkAFhJVqdgMZAUACvJ6hQsBpICYCVZnYLFQFIArCSrU7AYSAqAlWR1ChYDSQGwkqxOwWIgKQBWktUpWAwkBcBKsjoFi4GkAFhJVqdgMZAUACvJ6hQsBpICYCVZnYLFQFIArCSrU7AYSAqAlWR1ChYDSQGwkqxOwWIgKQBWktUpWAwkBcBKsjoFi4GkAFhJVqdgMZAUACvJ6hQsBpICYCVZnYLFQFIArCSrU7AYSAqAlWR1ChYDSQGwkqxOwWIgKQBWktUpWAwkBcBKsjoFi4GkAFhJVqdgMZAUACvJ6hQsBpICYCVZnYLFQFIArCSrU7AYSAqAlWR1ChYDSQGwkqxOwWIgKQBWktUpWAwkBcBKsjoFi4GkAFhJVqdgMZAUACvJ6hQsBpICYCVZnYLFQFIArCSrU7AYSAqAlWR1ChYDSQGwkqxOwWIgKQBWktUpWAwkBcBKsjp9QYIAl6bSsVAAAAAASUVORK5CYII=';
@endphp

<body class="A4" style="font-family:DejaVu Sans, sans-serif;;height: auto" onload="window.print()">
    <section class="sheet padding-10mm" style="font-family:DejaVu Sans, sans-serif;;height: auto;overflow: hidden;">
        @php
            $tglv_pembuatan = isset($data['tanggal']) ? date('d-m-Y', strtotime($data['tanggal'])) : "";
        @endphp
        <table width="100%" cellspacing="0" cellpadding="0" border="1">
            <tr>
                <td width="60%" style="text-align:right" colspan=2>
                    <table width="100%" cellspacing="0" cellpadding="0" border="0">
                        <tr class="bg-blue">
                            <td>
                                <td width="50%" style="text-align:left; font-size: 14px">RSUD BALI MANDARA</td>
                                <td width="50%" style="font-size: 14px; text-align: right;">RM.19/SIR/00</td>
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
                        <span style="font-size: 16px">RUJUKAN PASIEN
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
        <table width="100%" cellspacing="0" cellpadding="2" border="1">
            <tr>
                <td colspan="4" class="font" style="border-bottom: none;">
                    <span>&nbsp;Kepada Yth :</span>
                    <br>
                    <span>&nbsp;Teman Sejawati/Dr/Bag</span>
                    <br>
                    &nbsp;{{ isset($data['yangTerhomat']) ? $data['yangTerhomat'] : '' }}
                    <br>
                    <span>&nbsp;Di</span>
                    <br>
                    &nbsp;{{ isset($data['tempatdokter']) ? $data['tempatdokter'] : '' }}
                    <br>
                    <span>&nbsp;Dengan hormat,</span>
                    <br>
                    <span>&nbsp;Mohon pemeriksaan/perawatan/pengobatan lebih lanjut pasien :</span>
                </td>
            </tr>
            <tr>
                <td colspan="4" style="border-top: none; border-bottom: none;">
                    <table class="font" width="100%" cellspacing="0" cellpadding="0" border="0">
                        <tr>
                            <td>
                                <span>&nbsp;Nama</span>   
                            </td>
                            <td>
                                <span>&nbsp;: {{ isset($data['namapasien']) ? $data['namapasien'] : '' }}</span>   
                            </td>
                            <td>
                                <span>&nbsp;Jenis Kelamin</span>
                            </td>
                            <td>
                                &nbsp;: {{ isset($data['jeniskelamin']) ? $data['jeniskelamin'] : '' }}
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span>&nbsp;Umur</span>   
                            </td>
                            <td colspan="3">
                                <span>&nbsp;: {{ isset($data['umur']) ? $data['umur'] : '' }}</span>   
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span>&nbsp;Alamat</span>   
                            </td>
                            <td colspan="3">
                                <span>&nbsp;: {{ isset($data['alamatpasien']) ? $data['alamatpasien'] : '' }}</span>   
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span>&nbsp;Diagnosa Kerja</span>   
                            </td>
                            <td colspan="3">
                                <span>&nbsp;: {{ isset($data['diagnosapasien']) ? $data['diagnosapasien'] : '' }}</span>   
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span>&nbsp;Riwayat Penyakit</span>   
                            </td>
                            <td colspan="3">
                                <span>&nbsp;: {{ isset($data['riwayatpenyait']) ? $data['riwayatpenyait'] : '' }}</span>   
                            </td>
                        </tr>
                        <tr>
                            <td colspan="4">&nbsp;</td>
                        </tr>
                        <tr>
                            <td colspan="4">&nbsp;</td>
                        </tr>
                        <tr>
                            <td>
                                <span>&nbsp;Pemeriksaan Fisik</span>   
                            </td>
                            <td colspan="3">
                                <span>&nbsp;: {{ isset($data['pemeriksaanfisik']) ? $data['pemeriksaanfisik'] : '' }}</span>   
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span>&nbsp;Penunjang</span>   
                            </td>
                            <td colspan="3">
                                <span>&nbsp;: {{ isset($data['penunjang']) ? $data['penunjang'] : '' }}</span>   
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <span>&nbsp;Terapi/Prosedur/Intervensi yang telah diberikan</span>   
                            </td>
                            <td colspan="2">
                                <span>&nbsp;: {{ isset($data['prosedur']) ? $data['prosedur'] : '' }}</span>   
                            </td>
                        </tr>
                        <tr>
                            <td colspan="4">&nbsp;</td>
                        </tr>
                        <tr>
                            <td colspan="4">&nbsp;</td>
                        </tr>
                        <tr>
                            <td colspan="1">
                                <span>&nbsp;Alasan Rujuk</span>   
                            </td>
                            <td colspan="3">
                                <span>&nbsp;: {{ isset($data['alasanrujuk']) ? $data['alasanrujuk'] : '' }}</span>   
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <span>&nbsp;Kebutuhan Pelayanan Lanjutan di RS Rujukan</span>   
                            </td>
                            <td colspan="2">
                                <span>&nbsp;: {{ isset($data['pelayanan']) ? $data['pelayanan'] : '' }}</span>   
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <span>&nbsp;Nama petugas yang menyetujui rujukan di RS yang dituju</span>   
                            </td>
                            <td colspan="2">
                                <table class="font" width="100%" cellpadding="2" cellspacing="0" border="0">
                                    <tr>
                                        <td>
                                            <span>&nbsp;: {{ isset($data['petugas']['label']) ? $data['petugas']['label'] : '' }}</span>   
                                        </td>
                                        <td>
                                            &nbsp;No. HP : {{ isset($data['nohp']) ? $data['nohp'] : '' }}
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td colspan="2" style="border-top: none; border-right:none; border-bottom: none;">&nbsp;</td>
                <td colspan="2" class="font tc" style="border-top: none; border-left:none; border-bottom: none;">
                    &nbsp;Garut, {{ $tglv_pembuatan }}
                    <br>
                    <span>Dokter yang merawat</span>
                    <br>
                    @isset($qrcode)
                    <img src="data:image/png;base64, {!! $qrcode !!}">
                    @endisset
                    <br>
                    @isset($data['dokterYangMerawat']['label'])
                        {{ $data['dokterYangMerawat']['label'] }}
                    @endisset
                </td>
            </tr>
            <tr>
                <td colspan="4" style="border-top: none;">
                    <table class="font" width="100%" cellspacing="0" cellpadding="0" border="1">
                        <tr>
                            <td class="tc" style="width: 33%;vertical-align: top">
                                <span>Disetujui</span><br>
                                @if(isset($data['TTDMenyetujui']) && $data['TTDMenyetujui'] != $imgDefault)
                                    <br><img style="width: 80px;height: 80px;" src="{{ $data['TTDMenyetujui'] }}"><br>
                                @else
                                    <br><br><br>
                                @endif
                                <br>
                                ( {{ isset($data['pasienMenyetujui']) ? $data['pasienMenyetujui'] : '' }} )
                                <br>
                                <span>Pasien/Penanggung jawab</span>
                            </td>
                            <td class="tc" style="width: 33%;vertical-align: top">
                                <span>Diserahkan</span><br>
                                @if(isset($data['diserahkanOleh']['label']))
                                    <br><img src="https://api.qrserver.com/v1/create-qr-code/?size=80x80&data={{ $data['diserahkanOleh']['label'] }}"><br/>
                                @elseif (isset($data['diserahkanOleh']))
                                    <br><img src="https://api.qrserver.com/v1/create-qr-code/?size=80x80&data={{ $data['diserahkanOleh'] }}"><br/>
                                @else
                                    <br><br><br>
                                @endif
                                <br>
                                ( {{ isset($data['diserahkanOleh']['label']) ? $data['diserahkanOleh']['label'] : (isset($data['diserahkanOleh']) ? $data['diserahkanOleh'] : '') }} )
                                <br>
                                <span>Petugas</span>
                            </td>
                            <td class="tc" style="width: 33%;vertical-align: top">
                                <span>Diterima</span><br>
                                @if(isset($data['TTDPegawaiTerima']) && $data['TTDPegawaiTerima'] != $imgDefault)
                                    <br><img style="width: 80px;height: 80px;" src="{{ $data['TTDPegawaiTerima'] }}"><br>
                                @else
                                    <br><br><br>
                                @endif
                                <br>
                                ( {{ isset($data['pegawaiterima']['label']) ? $data['pegawaiterima']['label'] : (isset($data['pegawaiterima']) ? $data['pegawaiterima'] : '') }} )
                                <br>
                                <span></span>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            
        {{-- <hr style="border:2px solid #000;margin-bottom:0px">
        <hr style="border:0.5px solid #000;margin-top:2px">
        <hr style="border:0.5px solid #000;margin-top:2px"> --}}
    </section>
</body>

</html>
