<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Surat Keluar Masuk</title>
    @if (stripos(\Request::url(), 'localhost') !== false || stripos(\Request::url(), '127.0.0.1') !== false)
        <link rel="stylesheet" href="{{ asset('css/paper.css') }} ">
        <link rel="stylesheet" href="{{ asset('css/table-v2.css') }}">
        <link rel="stylesheet" href="{{ asset('css/tabel.css') }}">
        <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    @else
        <link rel="stylesheet" href="{{ asset('service/css/paper.css') }} ">
        <link rel="stylesheet" href="{{ asset('service/css/table-v2.css') }}">
        <link rel="stylesheet" href="{{ asset('service/css/tabel.css') }}">
        <link rel="stylesheet" href="{{ asset('service/css/style.css') }}">
    @endif

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

            @page {
                size: 215mm 275mm;
            }

                {
                width: 215mm 275mm;
            }

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

        table {
            width: 100%;
            height: 100%;
        }

        .hitam {
            background-color: #000000 !important;
        }

        .table-ex {
            /* border: 1px solid black; */
            border-collapse: collapse !important;
            width: 100%;
        }

        .tr-sub,
        .td-sub {
            /* padding:3px; */
            text-align: center;
        }

        .th-ex,
        .td-ex {
            border: 1px solid black;
            padding: 2px 4px;
            text-align: left;
        }

        .table-first {
            border: 1px solid black;
            border-collapse: collapse;
        }

        .th-seccond {
            text-align: left;
            font-weight: normal;
            font-size: 9pt;
        }
    </style>

    @stack('style')

</head>

<body class="A4" style="font-family:Tahoma;height: auto;background-color:none" onload="window.print()">
    <section class="sheet padding-10mm" style="font-family:Tahoma;height: auto;overflow: hidden;">

        <table class="table-first">
            <tr>
                <th class="th-ex" width="60%" colspan="2">
                    <table>
                        <tr>
                            <td class="td-sub" width="20%" rowspan="5">
                                <img src="{{ asset('img/logo-rs.png') }}" width="60px">
                            </td>
                            <td class="td-sub">
                                <span
                                    style="text-transform:uppercase;font-weight:normal">{{ $profile->namapemerintahan }}</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="td-sub">
                                <span style="text-transform:uppercase;font-weight:normal">Dinas Kesehatan</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="td-sub">
                                <span style="text-transform:uppercase">{{ $profile->namaexternal }}</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="td-sub">
                                <span style="font-weight:normal">{{$profile->alamatlengkap}} - Tlp. {{ $profile->fixedphone }} -
                                    202444, Fax {{ $profile->faksimile }}</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="td-sub">
                                <span style="font-weight:normal">Email : {{ $profile->alamatemail }}</span>
                            </td>
                        </tr>
                    </table>
                </th>
                <th class="th-ex" width="40%" rowspan="2">
                    <table>
                        <tr>
                            <td width="35%" style="font-weight:normal">
                                <span>Nomor RM</span>
                            </td>
                            <td width="5%" style="font-weight:normal">
                                <span>:</span>
                            </td>
                            <td>
                                <span>B0038372</span>
                            </td>
                        </tr>
                        <tr>
                            <td width="35%" style="font-weight:normal">
                                <span>Nama</span>
                            </td>
                            <td width="5%" style="font-weight:normal">
                                <span>:</span>
                            </td>
                            <td>
                                <span></span>
                            </td>
                        </tr>
                        <tr>
                            <td width="35%" style="font-weight:normal">
                                <span>Tanggal Lahir</span>
                            </td>
                            <td width="5%" style="font-weight:normal">
                                <span>:</span>
                            </td>
                            <td>
                                <span>B0038372</span>
                            </td>
                        </tr>
                        <tr>
                            <td width="35%" style="font-weight:normal">
                                <span>Jenis Kelamin</span>
                            </td>
                            <td width="5%" style="font-weight:normal">
                                <span>:</span>
                            </td>
                            <td>
                                <span>B0038372</span>
                            </td>
                        </tr>
                    </table>
                </th>
            </tr>
            <tr>
                <th class="th-ex" colspan="2" style="text-align: center">
                    <span>RESUME MEDIS</span>
                </th>
            </tr>
            <tr>
                <th class="th-ex" style="font-weight: normal;">
                    <span style="font-size:9pt;display:block;margin-bottom:3px">Tanggal Masuk :</span>
                    <span style="font-size:9pt;display:block">12-13-2021</span>
                </th>
                <th class="th-ex" style="font-weight: normal;">
                    <span style="font-size:9pt;display:block;margin-bottom:3px">Tanggal Keluar / Meninggal :</span>
                </th>
                <th class="th-ex" style="font-weight: normal;">
                    <span style="font-size:9pt;display:block;margin-bottom:3px">Ruang Rawat Terakhir :</span>
                </th>
            </tr>
            <tr>
                <th class="th-ex" style="font-weight: normal;">
                    <span style="font-size:9pt;">Penanggung Pembayaran : BPJS</span>
                </th>
                <th class="th-ex" style="font-weight: normal;" colspan="2">
                    <span style="font-size:9pt;">Diagnosis/Masalah waktu Masuk : </span>
                </th>
            </tr>
            <tr>
                <th class="th-ex" style="font-weight: normal;" colspan="3">
                    <span style="font-size:9pt;">Asal Rujukan</span>
                </th>
            </tr>
        </table>

        <hr style="margin-top: 1.3rem;border: 1px solid;">
        <table>
            <tr>
                <th class="th-seccond" width="20%">
                    <span>Ringkasan Riwayat Penyakit</span>
                </th>
                <th class="th-seccond" width="2%">
                    <span>:</span>
                </th>
                <th class="th-seccond" width="60%">
                    <span></span>
                </th>
            </tr>
        </table>
        <hr style="border: 1px solid;">
        <table>
            <tr>
                <th class="th-seccond" width="20%">
                    <span>Pemeriksaan Fisik</span>
                </th>
                <th class="th-seccond" width="2%">
                    <span>:</span>
                </th>
                <th class="th-seccond" width="60%">
                    <table>
                        <tr>
                            <td>
                                <span>TD</span>
                            </td>
                            <td style="th-last">
                                <span>135/80</span>
                            </td>
                            <td style="padding-right: 15px;text-align:center;">
                                <span>mmhg</span>
                            </td>
                            <td>
                                <span>Nadi</span>
                            </td>
                            <td>
                                <span>135/80</span>
                            </td>
                            <td style="padding-right: 15px;text-align:center;">
                                <span>x/mnt</span>
                            </td>
                            <td>
                                <span>Pernapasan</span>
                            </td>
                            <td>
                                <span>135/80</span>
                            </td>
                            <td>
                                <span>x/mnt</span>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span>Suhu</span>
                            </td>
                            <td>
                                <span>33</span>
                            </td>
                            <td style="padding-right: 15px;text-align:center;">
                                <span>c</span>
                            </td>
                            <td>
                                <span>TB</span>
                            </td>
                            <td>
                                <span>180</span>
                            </td>
                            <td style="padding-right: 15px;text-align:center;">
                                <span>cm</span>
                            </td>
                            <td>
                                <span>BB</span>
                            </td>
                            <td>
                                <span>80</span>
                            </td>
                            <td style="padding-right: 15px;text-align:center;">
                                <span>kg</span>
                            </td>
                        </tr>
                    </table>
                </th>
            </tr>
        </table>
        <hr style="border: 1px solid;">
        <table>
            <tr>
                <th class="th-seccond" width="20%">
                    <span>Pemeriksaan Fisik Lainnya</span>
                </th>
                <th class="th-seccond" width="2%">
                    <span>:</span>
                </th>
                <th class="th-seccond" width="60%">
                    <span></span>
                </th>
            </tr>
        </table>
        <hr style="border: 1px solid;">
        <table border="1px">

            @php
                $value = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10];
            @endphp
            <tr>
                <th class="th-seccond" width="20%" style="vertical-align: baseline">
                    <span>Pemeriksaan Penunjang / Diagnostik Terpenting</span>
                </th>
                <th class="th-seccond" width="2%" style="vertical-align: baseline">
                    <span>:</span>
                </th>
                <th class="th-seccond" width="60%">
                    <table border="1px">
                        @foreach ($value as $item)
                            <tr>
                                <td width="27%">
                                    <span>05/10/2023 10:40:35</span>
                                </td>
                                <td>
                                    <span>Anti HCV Negatif Normal:Negatif</span>
                                </td>
                            </tr>
                        @endforeach
                    </table>
                </th>
            </tr>
        </table>

        <table border="1px" style="margin-top: .5rem">
            <tr>
                <th class="th-seccond" width="20%" style="vertical-align: baseline"></th>
                <th class="th-seccond" width="2%" style="vertical-align: baseline"></th>
                <th class="th-seccond" width="60%">
                    <table border="1px">
                        <tr>
                            <td>
                                <span>Expertise Radiologi Tgl : 05/10/2023 17:28:34</span>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span>Cor Membesar</span>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span>Sinuses dan diafragma normal</span>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span>Pulmo : </span>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span>Hili Normal</span>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span>Hili Normal</span>
                            </td>
                        </tr>
                    </table>
                </th>
            </tr>
        </table>
    </section>
</body>

</html>
<script type="text/javascript">
    $(document).ready(function() {
        window.print();
    });
</script>
