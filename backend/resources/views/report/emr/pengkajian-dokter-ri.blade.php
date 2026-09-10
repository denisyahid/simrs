<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Pengkajian Dokter Rawat Inap</title>

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
    {{-- @if ((isset($res['pdf']) && $res['pdf']) || (isset($res['storage']) && $res['storage']))
        <link rel="stylesheet" href="css/report/paper.css">
        <link rel="stylesheet" href="css/report/table.css">
        <link rel="stylesheet" href="css/report/tabel.css">
    @else
        <link rel="stylesheet" href="{{ asset('css/report/paper.css') }} ">
    <link rel="stylesheet" href="{{ asset('css/report/table.css') }}">
    <link rel="stylesheet" href="{{ asset('css/report/tabel.css') }}">
    @endif --}}

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
            size: F4;
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

        .table-eex {
            /* border: 1px solid black; */
            border-collapse: collapse !important;
            width: 100%;
        }

        .table-sub {
            padding: 5px;
            border: 1px black;
            border-collapse: collapse;
            width: 100%;
        }

        .th-sub {
            padding: 5px;
            /* text-align: center; */
            /* border: 1px solid black; */
        }

        .dot {
            border-bottom: 1px dotted rgba(0, 0, 0, .6)
        }

        .th-ex,
        .td-ex {
            border: 1px solid black;
            padding: 3px;
            text-align: left;
        }

        .label {
            font-size: 11px;
            color: #000000;
        }

        .label-sub {
            font-size: 13px;
            font-weight: 600;
            color: #000000;
        }

        .normal-text {
            font-weight: normal;
            text-transform: uppercase;
        }

        .sm {
            font-size: xx-small;
        }

        .italic {
            font-style: italic;
            font-weight: normal;
        }
    </style>

    @stack('style')

</head>

<body class="F4" style="font-family:Tahoma;height: auto" onload="window.print()">
    <section class="sheet padding-10mm" style="font-family:Tahoma;height: auto;overflow: hidden;">
        <table class="table-ex">
            <tr>
                <th class="th-ex" width="50%" style="padding: 0px;">
                    <table width="100%" style="border-collapse: collapse;border-bottom:1px solid black">
                        <tr>
                            <td width="25%" style="text-align: left;padding:7px">
                                @if (stripos(\Request::url(), 'localhost') !== false || stripos(\Request::url(), '127.0.0.1') !== false)
                                <img src="{{ asset('img/logo-rs.png') }}" width="70px" border="0">
                                @else
                                <img src="{{ asset('service/img/logo-rs.png') }}" width="70px" border="0">
                                @endif
                            </td>
                            <td width="75%">
                                <table style="border-collapse: collapse">
                                    <tr>
                                        <td style="text-align: center;padding:0px">
                                            <span class="normal-text sm">{{$profile->namapemerintahan}}</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="text-align: center; padding:0px">
                                            <span class="normal-text sm">{{$profile->reportdisplay}}</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="text-align: center; padding:0px">
                                            <span style="font-size: x-small">{{$profile->namalengkap}}</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="text-align: center; padding:0px">
                                            <span class="normal-text sm">{{$profile->alamatlengkap}} . Tlp.{{$profile->fixedphone}}, Fax.${{$profile->faksimile}}</span>
                                    </tr>
                                    <tr>
                                        <td style="text-align: center; padding:0px">
                                            <span class="normal-text sm">{{$profile->alamatemail}}</span>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                    <table width="100%" style="margin:18px 0px">
                        <tr>
                            {{-- <th style="text-align: center;text-transform:uppercase">{{ $profile->website}}
                </th> --}}
                <th style="text-align: center;text-transform:uppercase">Pengkajian Dokter Rawat Inap</th>
            </tr>
        </table>
        </th>
        <th class="th-ex" width="50%">
            <table class="table-ex">
                <tr>
                    <td width="40%" class="th-sub">
                        <span>Nomor RM</span>
                    </td>
                    <td width="3%" class="th-sub">
                        <span>:</span>
                    </td>
                    <td width="50%" class="th-sub dot">
                        <span>{{ $pasien['nocm'] }}</span>
                    </td>
                </tr>
                <tr>
                    <td width="40%" class="th-sub">
                        <span>Nama Pasien</span>
                    </td>
                    <td width="3%" class="th-sub">
                        <span>:</span>
                    </td>
                    <td width="50%" class="th-sub dot">
                        <span style="border-bottom: 1px">{{ $pasien['namapasien'] }}</span>
                    </td>
                </tr>
                <tr>
                    <td width="40%" class="th-sub">
                        <span>Tanggal Lahir</span>
                    </td>
                    <td width="3%" class="th-sub">
                        <span>:</span>
                    </td>
                    <td width="50%" class="th-sub dot">
                        <span style="border-bottom: 1px">{{ $pasien['tgllahir'] }}</span>
                    </td>
                </tr>
                <tr>
                    <td width="40%" class="th-sub">
                        <span>Jenis Kelamin</span>
                    </td>
                    <td width="3%" class="th-sub">
                        <span>:</span>
                    </td>
                    <td width="50%" class="th-sub dot">
                        <span>{{ $pasien['jeniskelamin'] }}</span>
                    </td>
                </tr>
            </table>
        </th>
        </tr>
        </table>

        <table class="table-eex" width="100%" style="border: 1px solid black !important;">
            <tr style="border: 1px solid black !important;">
                <td  width="100%" style="padding:3px">
                    <span style="font-weight:bold">A. ANAMNESIS</span>
                </td>
            </tr>
            <tr style="border: 1px solid black !important;">
                <td  style="padding:3px 5px; max-width: 300px;">
                    1. Keluhan Utama : <span style="white-space: nowrap; word-wrap: break-all;">{{ isset($data['KeluhanUtama']) ? $data['KeluhanUtama'] : '-' }}</span>
                </td>
            </tr>   
            <tr>
                <td width="100%" style="padding:3px 5px">
                    <span>Riwayat Penyakit Sekarang : </span>
                </td>
            </tr>
            <tr>
                <td width="100%" style="padding:0px 10px">
                    <span>Lorem ipsum dolor sit, amet consectetur adipisicing elit. Nesciunt voluptatibus cum atque quis, quibusdam pariatur quos expedita tenetur, quasi commodi quod vero nulla minima quam fugit corrupti officiis. Atque esse ullam incidunt? Numquam doloremque temporibus nulla assumenda cum expedita distinctio velit? Corporis earum quam in voluptates sint placeat excepturi repudiandae.</span>
                </td>
            </tr>
            <!-- <tr>
                <td>
                    <span>Keluhan Utama : </span>
                </td>
            </tr> -->
            <!-- <tr>
                <td>
                    <span>Keluhan Utama : </span>
                </td>
            </tr> -->
        </table>
    </section>
</body>

</html>
<script type="text/javascript">
    $(document).ready(function() {
        window.print();
    });
</script>