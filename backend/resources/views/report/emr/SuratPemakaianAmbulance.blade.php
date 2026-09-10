<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>EMR - Surat Pemakaian Ambulance</title>
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

        .text-middle {
            vertical-align: middle;
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
            $tglv_pembuatan = isset($data['tanggal1']) ? date('d-m-Y H:i', strtotime($data['tanggal1'])) : "";
            $tglv_diserahkan = isset($data['tglDiserahkan']) ? date('d-m-Y H:i', strtotime($data['tglDiserahkan'])) : "";
            $tglv_diterima = isset($data['tglDiterima']) ? date('d-m-Y H:i', strtotime($data['tglDiterima'])) : "";
        @endphp
        <table width="100%" cellspacing="0" cellpadding="0" border="1">
            <tr>
                <td width="60%" style="text-align:right" colspan=2>
                    <table width="100%" cellspacing="0" cellpadding="0" border="0">
                        <tr class="bg-blue">
                            <td>
                                <td width="50%" style="text-align:left; font-size: 14px">RSUD BALI MANDARA</td>
                                <td width="50%" style="font-size: 14px; text-align: right;">RM 10/IGD/00</td>
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
                        <span style="font-size: 16px">SURAT PEMAKAIAN AMBULANCE
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
        <table width="100%" cellspacing="0" cellpadding="2" border="1" class="font">
            <colgroup>
                <col style="width: 20%;">
                <col style="width: 30%;">
                <col style="width: 30%;">
                <col style="width: 20%;">
            </colgroup>
            <tr>
                <td style="width: 20%;">
                    <span>&nbsp;Tanggal</span>
                </td>
                <td colspan="3">
                    {{ isset($tglv_pembuatan) ? $tglv_pembuatan : '' }}
                </td>
            </tr>
            <tr>
                <td>
                    <span>&nbsp;Mobil ambulance</span>
                </td>
                <td colspan="3">
                    {{ isset($data['ambulanRSUDB']) ? $data['ambulanRSUDB'] : 'Ambulance RSUD Bali Mandara : DK' }}
                </td>
            </tr>
            <tr>
                <td>
                    <span>&nbsp;Faskes tujuan / pengirim</span>
                </td>
                <td colspan="3">
                    {{ isset($data['Tujuanpengirim']) ? $data['Tujuanpengirim'] : '' }}
                </td>
            </tr>
            <tr>
                <td>
                    <span>&nbsp;Kilometer</span>
                </td>
                <td colspan="2">
                    <table width="100%" cellspacing="0" cellpadding="2" border="0" class="font">
                        <tr>
                            <td width="30%" style="border-right: 1px black solid">
                                <span>&nbsp;Berangkat</span>
                            </td>
                            <td width="70%" style="border-left: 1px black solid;">
                                {{ isset($data['Berangkat']) ? $data['Berangkat'] : '' }}
                            </td>
                        </tr>
                        <tr>
                            <td style="border-top: 1px black solid; border-right: 1px black solid;">
                                <span>&nbsp;Tiba</span>
                            </td>
                            <td style="border-top: 1px black solid;">
                                {{ isset($data['Tiba']) ? $data['Tiba'] : '' }}
                            </td>
                        </tr>
                    </table>
                </td>
                <td class="tc text-top">
                    <span>Sellisih Kilometer</span>
                    <br />
                    {{ isset($data['Selisihkilometer']) ? $data['Selisihkilometer'] : '' }}
                </td>
            </tr>
            <tr>
                <td>
                    <br />
                    <br />
                    <br />
                    <span>&nbsp;Diagnosa</span>
                    <br />
                    <br />
                    <br />
                    <br />
                </td>
                <td colspan="3">
                    {{ isset($data['Diagnosa']) ? $data['Diagnosa'] : '' }}
                </td>
            </tr>
            <tr>
                <td>
                    <br />
                    <br />
                    <span>&nbsp;Kondisi saat dirujuk</span>
                    <br />
                    <br />
                    <br />
                </td>
                <td colspan="3">
                    {{ isset($data['Kondisisaatdirujuk']) ? $data['Kondisisaatdirujuk'] : '' }}
                </td>
            </tr>
            <tr>
                <td>
                    <br />
                    <br />
                    <span>&nbsp;Alasan dirujuk</span>
                    <br />
                    <br />
                    <br />
                </td>
                <td colspan="3">
                    {{ isset($data['Alasandirujuk']) ? $data['Alasandirujuk'] : '' }}
                </td>
            </tr>
        </table>
        <table width="100%" cellspacing="0" cellpadding="2" border="1" class="font">
            <tr>
                <td width="50%">
                    <br />
                    <span>
                        &nbsp;Diserahkan, pukul:
                    </span>
                    {{ isset($tglv_diserahkan) ? $tglv_diserahkan : '' }}
                    <br />
                    <br />
                </td>
                <td width="50%">
                    <br />
                    <span>
                        &nbsp;Diterima, pukul:
                    </span>
                    {{ isset($tglv_diterima) ? $tglv_diterima : '' }}
                    <br />
                    <br />
                </td>
            </tr>
        </table>
        <table width="100%" cellspacing="0" cellpadding="2" border="1" class="font">
            <tr>
                <td width="25%" class="tc">
                    <span>
                        Petugas pengirim
                    </span>
                </td>
                <td width="25%" class="tc">
                    <span>
                        Petugas penerima
                    </span>
                </td>
                <td width="25%" class="tc">
                    <span>
                        Keluarga pasien
                    </span>
                </td>
                <td width="25%" class="tc">
                    <span>
                        Sopir Ambulance
                    </span>
                </td>
            </tr>
            <tr>
                <td width="25%" class="tc">
                    @isset($data['namaPetugasPengirim']['label'])
                    <span>
                        @isset($qrcode)
                        <img src="data:image/png;base64, {!! $qrcode !!}">
                        @endisset
                        <br>
                        <p style="font-size: 12px;">
                            @isset($data['namaPetugasPengirim']['label'])
                            {{ $data['namaPetugasPengirim']['label'] }}
                            @endisset
                        </p>
                    </span>
                    @endisset
                </td>
                <td width="25%" class="tc">
                    <span>
                        @isset($data['namaPetugasPenerima']['label'])
                            @isset($qrcode2)
                            <img src="data:image/png;base64, {!! $qrcode2 !!}">
                            @endisset
                        @endisset
                        <br>
                        <p style="font-size: 12px;">
                            @isset($data['namaPetugasPenerima']['label'])
                            {{ $data['namaPetugasPenerima']['label']}}
                        @endisset
                    </p>
                    </span>
                </td>
                <td width="25%" class="tc" style="{{ empty($data['KeluargaPasien']) ? 'padding-bottom: 10em;' : '' }}">
                    <span>
                    {{-- @isset($data['KeluargaPasien'])
                            @isset($qrcode3)
                            <img src="data:image/png;base64, {!! $qrcode3 !!}">
                            @endisset
                    @endisset --}}
                    <img src="{{ $data['ttdPasien'] }}" alt="" style="background-image: url('img/keluarga.png')" width="100%">
                    <br>
                    <p style="font-size: 12px;">
                        @isset($data['KeluargaPasien'])
                            {{ $data['KeluargaPasien']}}
                        @endisset
                    </p>
                    </span>
                </td>
                <td width="25%" class="tc">
                    <span>
                    @isset($data['SopirAmbulance'])
                        @isset($qrcode4)
                        <img src="data:image/png;base64, {!! $qrcode4 !!}">
                        @endisset
                    @endisset
                    <br>
                    <p style="font-size: 12px;">
                        @isset($data['SopirAmbulance'])
                            {{ $data['SopirAmbulance']}}
                        @endisset
                    </p>
                    </span>
                </td>
            </tr>
        </table>


        {{-- <hr style="border:2px solid #000;margin-bottom:0px">
        <hr style="border:0.5px solid #000;margin-top:2px">
        <hr style="border:0.5px solid #000;margin-top:2px"> --}}
    </section>
</body>

</html>
