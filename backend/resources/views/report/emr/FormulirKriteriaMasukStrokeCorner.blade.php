<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>EMR - Formulir Kriteria Masuk Stroke Corner</title>
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
            background-color: #91CEDE;
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

<body class="A4" style="font-family:DejaVu Sans, sans-serif;;height: auto" onload="window.print()">
    <section class="sheet padding-10mm" style="font-family:DejaVu Sans, sans-serif;;height: auto;overflow: hidden;">
        @php
            $tglv_pembuatan = isset($data['tanggal']) ? date('d-m-Y H:i', strtotime($data['tanggal'])) : "";
        @endphp
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
                        <span style="font-size: 16px">STROKE CORNER</span>
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
                <td colspan="4" class="font">
                    <span>&nbsp;Nama Ruangan : {{ isset($data['ruangan']['label']) ? $data['ruangan']['label'] : '' }}</span>
                </td>
            </tr>
            <tr>
                <td width = "5%" style="font-size: 14; text-align: center">No</td>
                <td width = "65%" style="font-size: 14; text-align: center">KRITERIA</td>
                <td width = "15%" style="font-size: 14; text-align: center">YA</td>
                <td width = "15%" style="font-size: 14; text-align: center">TIDAK</td>
            </tr>
            <tr>
                <td class="font tc">
                    1.
                </td>
                <td class="font">
                    Post terapi trombolisis intravena.
                </td>
                <td class="tc">
                    <input type="checkbox" {{ isset($data['postTerapiTrombolidIntravena']) && $data['postTerapiTrombolidIntravena'] == 'YA' ? 'checked' : '' }}  />
                </td>
                <td class="tc">
                    <input type="checkbox" {{ isset($data['postTerapiTrombolidIntravena']) && $data['postTerapiTrombolidIntravena'] == 'TIDAK' ? 'checked' : '' }}  />
                </td>
            </tr>
            <tr>
                <td class="font tc">
                    2.
                </td>
                <td class="font">
                    Stroke akut tanpa ancaman gagal nafas: saturasi O₂ ≤ 84%, respirasi ≥ 30, PaO₂ < 50 mmHg, PaCO₂ > 50 mmHg, GCS ≥ 8
                </td>
                <td class="tc">
                    <input type="checkbox" {{ isset($data['strokeAkutTanpaAncamanGagalNafas']) && $data['strokeAkutTanpaAncamanGagalNafas'] == 'YA' ? 'checked' : '' }}  />
                </td>
                <td class="tc">
                    <input type="checkbox" {{ isset($data['strokeAkutTanpaAncamanGagalNafas']) && $data['strokeAkutTanpaAncamanGagalNafas'] == 'TIDAK' ? 'checked' : '' }}  />
                </td>
            </tr>
            <tr>
                <td class="font tc">
                    3.
                </td>
                <td class="font">
                    Defisit neurologi tidak stabil atau progesif
                </td>
                <td class="tc">
                    <input type="checkbox" {{ isset($data['defisitNeurologi']) && $data['defisitNeurologi'] == 'YA' ? 'checked' : '' }}  />
                </td>
                <td class="tc">
                    <input type="checkbox" {{ isset($data['defisitNeurologi']) && $data['defisitNeurologi'] == 'TIDAK' ? 'checked' : '' }}  />
                </td>
            </tr>
            <tr>
                <td class="font tc">
                    4.
                </td>
                <td class="font">
                    Membutuhkan rehabilitasi dini atau memerlukan penanganan multidisipliner ilmu
                </td>
                <td class="tc">
                    <input type="checkbox" {{ isset($data['penangananMultiDisiplinerIlmu']) && $data['penangananMultiDisiplinerIlmu'] == 'YA' ? 'checked' : '' }}  />
                </td>
                <td class="tc">
                    <input type="checkbox" {{ isset($data['penangananMultiDisiplinerIlmu']) && $data['penangananMultiDisiplinerIlmu'] == 'TIDAK' ? 'checked' : '' }}  />
                </td>
            </tr>
            <tr>
                <td colspan="4" class="font">
                    &nbsp; KESIMPULAN:
                        <br>
                        &nbsp; Berdasarkan kondisi di atas maka pasien memenuhi kriteria masuk Ruang Stroke Corner.
                        <br>
                        &nbsp; Alat transport yang dibutuhkan :
                        &nbsp; <input style="margin-bottom: -5px;" type="checkbox" {{ isset($data['alatTransport']) && $data['alatTransport'] == 'Brancard' ? 'checked' : '' }}  />
                                <span style="font-size: 10pt;" color="#000000" >Brancard</span>
                        &nbsp; <input style="margin-bottom: -5px;" type="checkbox" {{ isset($data['alatTransport']) && $data['alatTransport'] == 'Kursi Roda' ? 'checked' : '' }}  />
                                <span style="font-size: 10pt;" color="#000000" >Kursi roda</span>
                        <br>
                        &nbsp; Pendamping selama transport :
                        &nbsp; <input style="margin-bottom: -5px;" type="checkbox" {{ isset($data['pendampingSaatTransfer1']) && $data['pendampingSaatTransfer1'] == 'Dokter' ? 'checked' : '' }}  />
                                <span style="font-size: 10pt;" color="#000000" >Dokter</span>
                        &nbsp; <input style="margin-bottom: -5px;" type="checkbox" {{ isset($data['pendampingSaatTransfer2']) && $data['pendampingSaatTransfer2'] == 'Perawat' ? 'checked' : '' }}  />
                                <span style="font-size: 10pt;" color="#000000" >Perawat</span>
                        &nbsp; <input style="margin-bottom: -5px;" type="checkbox" {{ isset($data['pendampingSaatTransfer3']) && $data['pendampingSaatTransfer3'] == 'Asisten Perawat' ? 'checked' : '' }}  />
                                   <span style="font-size: 10pt;" color="#000000" >Asisten perawat</span>
                        <br>
                        &nbsp; Alat medis yang diperlukan selama transport:
                        <br>
                        &nbsp; <input style="margin-bottom: -5px;" type="checkbox" {{ isset($data['alatMedisOption']) && $data['alatMedisOption'] == 'Tidak' ? 'checked' : '' }}  />
                                <span style="font-size: 10pt;" color="#000000" >Tidak</span>
                                <br>
                        &nbsp; <input style="margin-bottom: -5px;" type="checkbox" {{ isset($data['alatMedisOption']) && $data['alatMedisOption'] == 'Ya' ? 'checked' : '' }}  />
                                <span style="font-size: 10pt;" color="#000000" >Ya, Sebutkan :</span>
                        &nbsp; {{ isset($data['alatMedis']) ? $data['alatMedis'] : '...' }}
                </td>
            </tr>
        </table>
        <table width="100%" cellspacing="0" cellpadding="0" border="1">
            <tr>
                <td class="tc half font mt-5">
                    DPJP,
                    <br>
                    <img src="data:image/png;base64, {!! $qrcode2 !!}">
                    <br>
                    <p style="font-size: 12px;">
                        @isset($data['dokterDPJP']['label'])
                            {{ $data['dokterDPJP']['label'] }}
                        @endisset 
                    </p>
                </td>
                <td class="tc half font">
                    Garut, {{ $tglv_pembuatan }} WIB
                    <br>
                    <img src="data:image/png;base64, {!! $qrcode !!}">
                    <br>
                    <p style="font-size: 12px;">
                        @isset($data['dokterPemeriksa']['label'])
                            {{ $data['dokterPemeriksa']['label'] }}
                        @endisset 
                    </p>
                </td>
            </tr>
        </table>
        {{-- <hr style="border:2px solid #000;margin-bottom:0px">
        <hr style="border:0.5px solid #000;margin-top:2px">
        <hr style="border:0.5px solid #000;margin-top:2px"> --}}
    </section>
</body>

</html>
