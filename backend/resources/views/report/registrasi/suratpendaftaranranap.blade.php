<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Surat Pendaftaran Ranap</title>

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
                                            <span class="normal-text sm">{{$dataReport['profile']->namapemerintahan}}</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="text-align: center; padding:0px">
                                            <span class="normal-text sm">{{$dataReport['profile']->reportdisplay}}</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="text-align: center; padding:0px">
                                            <span style="font-size: x-small">{{$dataReport['profile']->namalengkap}}</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="text-align: center; padding:0px">
                                            <span class="normal-text sm">{{$dataReport['profile']->alamatlengkap}}. Tlp{{$dataReport['profile']->fixedphone}},
                                                Fax{{$dataReport['profile']->faksimile}}</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="text-align: center; padding:0px">
                                            <span class="sm italic">Email : {{$dataReport['profile']->alamatemail}}</span>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                    <table width="100%" style="margin:18px 0px">
                        <tr>
                            {{-- <th style="text-align: center;text-transform:uppercase">{{ $profile->website}}</th> --}}
                            <th style="text-align: center;text-transform:uppercase">Data Umum Pasien</th>
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
                                <span>{{ $dataReport['identitas']->nocm }}</span>
                            </td>
                        </tr>
                        <tr>
                            <td width="40%" class="th-sub">
                                <span>Tanggal Pendaftaran</span>
                            </td>
                            <td width="3%" class="th-sub">
                                <span>:</span>
                            </td>
                            <td width="50%" class="th-sub dot">
                                <span>{{ date('d-m-Y', strtotime($dataReport['identitas']->tglregistrasi)) }}</span>
                            </td>
                        </tr>
                        <tr>
                            <td width="40%" class="th-sub">
                                <span>Jam</span>
                            </td>
                            <td width="3%" class="th-sub">
                                <span>:</span>
                            </td>
                            <td width="50%" class="th-sub dot">
                                <span> {{ date('H:i', strtotime($dataReport['identitas']->tglregistrasi)) }}</span>
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
                                <span style="border-bottom: 1px">{{ $dataReport['identitas']->namapasien }}</span>
                            </td>
                        </tr>
                        <tr>
                            <td width="40%" class="th-sub">
                                <span>Ruang Rawat</span>
                            </td>
                            <td width="3%" class="th-sub">
                                <span>:</span>
                            </td>
                            <td width="50%" class="th-sub dot">
                                <span>{{ $dataReport['identitas']->namaruangan }}</span>
                            </td>
                        </tr>
                    </table>
                </th>
            </tr>
        </table>
        <table class="table-eex" style="margin-top: 3px">
            <tr>
                <th class="th-ex" style="padding:5px 5px 10px 5px; text-transform: uppercase;text-align:center">
                    <span>Pendaftaran Rawat Inap</span>
                </th>
            </tr>
            <tr>
                <th class="th-ex" style="padding:5px 5px 10px 5px">
                    <table class="table-eex">
                        <tr>
                            <td width="21%">
                                <span style="font-weight:normal">Pernah di rawat di RS sebelumnya</span>
                            </td>
                            <td width="2%">
                                <span style="font-weight:normal">:</span>
                            </td>
                            <td width="8%" style="font-weight: normal">
                                <input type="checkbox" {{ $dataReport['isRanap'] == false ? 'checked' : '' }} />
                                <span style="font-size: 10pt" color="#000000">Tidak</span>
                            </td>
                            <td width="5%" style="font-weight: normal">
                                <input type="checkbox" {{ $dataReport['isRanap'] == true ? 'checked' : '' }} />
                                <span style="font-size: 10pt" color="#000000">Ya</span>
                            </td>
                            <td width="8%" style="font-weight:normal">
                                <span>, Rawat di</span>
                            </td>
                            <td width="33%" class="th-sub dot">
                                <span></span>
                            </td>
                        </tr>
                    </table>
                </th>
            </tr>
            <tr>
                <th class="th-ex" style="padding:5px 5px 10px 5px">
                    <table class="table-eex">
                        <tr>
                            <td width="21%">
                                <span style="font-weight:normal">Dikirim / rujuk dari</span>
                            </td>
                            <td width="2%">
                                <span style="font-weight:normal">:</span>
                            </td>
                            <td class="th-sub dot" style="font-weight:normal">
                                <span>{{ $dataReport['identitas']->asalrujukan }}</span>
                            </td>
                        </tr>
                    </table>
                </th>
            </tr>
            <tr>
                <th class="th-ex" style="padding:5px 5px 10px 5px">
                    <table class="table-eex" style="margin-bottom: 7px;">
                        <tr>
                            <td width="21%">
                                <span style="font-weight:normal">Nama Pasien</span>
                            </td>
                            <td width="2%">
                                <span style="font-weight:normal">:</span>
                            </td>
                            <td class="th-sub dot">
                                <span style="font-weight:normal">{{ $dataReport['identitas']->namapasien }}</span>
                            </td>
                        </tr>
                    </table>
                    <table class="table-eex" style="margin-bottom: 7px;">
                        <tr>
                            <td width="21%">
                                <span style="font-weight:normal">Nama Keluarga / Marga</span>
                            </td>
                            <td width="2%">
                                <span style="font-weight:normal">:</span>
                            </td>
                            <td class="th-sub dot">
                                <span
                                    style="font-weight:normal">{{ isset($dataReport['identitas']->namakeluarga) ? $dataReport['identitas']->namakeluarga : '' }}</span>
                            </td>
                        </tr>
                    </table>
                    <table class="table-eex" style="margin-bottom: 7px;">
                        <tr>
                            <td width="21%">
                                <span style="font-weight:normal">Jenis Kelamin</span>
                            </td>
                            <td width="2%">
                                <span style="font-weight:normal">:</span>
                            </td>
                            <td class="th-sub">
                                <input type="checkbox" {{ $dataReport['identitas']->jkid == 1 ? 'checked' : '' }} />
                                <span style="font-size: 10pt;font-weight:normal" color="#000000">Laki</span>
                            </td>
                            <td class="th-sub">
                                <input type="checkbox" {{ $dataReport['identitas']->jkid == 2 ? 'checked' : '' }} />
                                <span style="font-size: 10pt;font-weight:normal" color="#000000">Perempuan</span>
                            </td>
                        </tr>
                    </table>
                    <table class="table-eex" style="margin-bottom: 7px;">
                        <tr>
                            <td width="21%">
                                <span style="font-weight:normal">Tempat / Tanggal Lahir</span>
                            </td>
                            <td width="2%">
                                <span style="font-weight:normal">:</span>
                            </td>
                            <td class="th-sub dot">
                                <span style="font-weight:normal">{{ $dataReport['identitas']->tempatlahir }}</span>
                            </td>
                            <td class="th-sub dot normal-text">
                                <span>/ {{ date('d-m-Y', strtotime($dataReport['identitas']->tgllahir)) }}</span>
                            </td>
                        </tr>
                    </table>
                    <table class="table-eex" style="margin-bottom: 7px;">
                        <tr>
                            <td width="21%">
                                <span style="font-weight:normal">Umur</span>
                            </td>
                            <td width="2%">
                                <span style="font-weight:normal">:</span>
                            </td>
                            <td class="th-sub dot">
                                <span style="font-weight:normal">{{ $dataReport['identitas']->tahun }}</span>
                            </td>
                        </tr>
                    </table>
                    <table class="table-eex" style="margin-bottom: 7px;">
                        <tr>
                            <td width="21%">
                                <span style="font-weight:normal">Agama</span>
                            </td>
                            <td width="2%">
                                <span style="font-weight:normal">:</span>
                            </td>
                            <td style="font-weight: normal">
                                <input type="checkbox"
                                    {{ $dataReport['identitas']->agama == 'ISLAM' ? 'checked' : '' }} />
                                <span style="font-size: 10pt" color="#000000">Islam</span>
                            </td>
                            <td style="font-weight: normal">
                                <input type="checkbox"
                                    {{ $dataReport['identitas']->agama == 'KRISTEN PROTESTAN' ? 'checked' : '' }} />
                                <span style="font-size: 10pt" color="#000000">Kristen Protestan</span>
                            </td>
                            <td style="font-weight: normal">
                                <input type="checkbox"
                                    {{ $dataReport['identitas']->agama == 'KRISTEN KATOLIK' ? 'checked' : '' }} />
                                <span style="font-size: 10pt" color="#000000">Khatolik</span>
                            </td>
                            <td style="font-weight: normal">
                                <input type="checkbox"
                                    {{ $dataReport['identitas']->agama == 'HINDU' ? 'checked' : '' }} />
                                <span style="font-size: 10pt" color="#000000">Hindu</span>
                            </td>
                            <td style="font-weight: normal">
                                <input type="checkbox"
                                    {{ $dataReport['identitas']->agama == 'BUDHA' ? 'checked' : '' }} />
                                <span style="font-size: 10pt" color="#000000">Budha</span>
                            </td>
                            <td style="font-weight: normal">
                                <input type="checkbox"
                                    {{ $dataReport['identitas']->agama == 'LAIN-LAIN' ? 'checked' : '' }} />
                                <span style="font-size: 10pt" color="#000000">Lain-lain</span>
                            </td>
                        </tr>
                    </table>
                    <table class="table-eex" style="margin-bottom: 7px;">
                        <tr>
                            <td width="21%">
                                <span style="font-weight:normal">Alamat Tempat Tinggal</span>
                            </td>
                            <td width="2%">
                                <span style="font-weight:normal">:</span>
                            </td>
                            <td class="th-sub dot">
                                <span style="font-weight:normal">{{isset($dataReport['identitas']->alamatlengkap) ? $dataReport['identitas']->alamatlengkap : '' }}</span>
                            </td>
                        </tr>
                    </table>
                    <table class="table-eex" style="margin-bottom: 7px;">
                        <tr>
                            <td width="21%" style="vertical-align:top;padding-top:7px">
                                <span style="font-weight:normal;">Alamat KTP</span>
                            </td>
                            <td width="2%" style="vertical-align:top;padding-top:7px">
                                <span style="font-weight:normal;">:</span>
                            </td>
                            <td>
                                <table>
                                    <tr>
                                        <td width="1%">
                                            <span style="font-weight:normal">Jl</span>
                                        </td>
                                        <td class="th-sub dot" width="20%">
                                            <span
                                                style="font-weight:normal">{{ isset($dataReport['identitas']->alamatlengkap) ?  $dataReport['identitas']->alamatlengkap  : '' }}</span>
                                        </td>
                                        {{-- <td width="2%">
                                            <span style="font-weight:normal">No : </span>
                                        </td>
                                        <td class="th-sub dot" width="10%">
                                            <span style="font-weight:normal"></span>
                                        </td> --}}
                                        <td width="2%">
                                            <span style="font-weight:normal">RT/RW : </span>
                                        </td>
                                        <td class="th-sub dot" width="10%">
                                            <span
                                                style="font-weight:normal">{{ $dataReport['identitas']->rtrw }}</span>
                                        </td>
                                    </tr>
                                </table>
                                <table style="margin-top:8px;">
                                    <tr>
                                        <td width="6%">
                                            <span style="font-weight:normal">Kelurahan / Kecamatan : </span>
                                        </td>
                                        <td class="th-sub dot" width="20%">
                                            <span
                                                style="font-weight:normal">{{ $dataReport['identitas']->namadesakelurahan }}
                                                / {{ $dataReport['identitas']->namakecamatan }} </span>
                                        </td>
                                    </tr>
                                </table>
                                <table style="margin-top:8px;">
                                    <tr>
                                        <td width="5%">
                                            <span style="font-weight:normal">Kota / Kode Post : </span>
                                        </td>
                                        <td class="th-sub dot" width="20%">
                                            <span
                                                style="font-weight:normal">{{ isset($dataReport['identitas']->namakotakabupaten) ? $dataReport['identitas']->namakotakabupaten : '' }}
                                                /
                                                {{ isset($dataReport['identitas']->kodepos) ? $dataReport['identitas']->kodepos : '' }}</span>
                                        </td>
                                    </tr>
                                </table>
                                <table style="margin-top:8px;">
                                    <tr>
                                        <td width="3%">
                                            <span style="font-weight:normal">Telepon / HP : </span>
                                        </td>
                                        <td class="th-sub dot" width="20%">
                                            <span
                                                style="font-weight:normal">{{ isset($dataReport['identitas']->nohp) ? $dataReport['identitas']->nohp : $dataReport['identitas']->notelepon }}</span>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>

                    <table class="table-eex" style="margin-bottom: 7px;">
                        <tr>
                            <td width="21%">
                                <span style="font-weight:normal">Suku / Bangsa / Notion</span>
                            </td>
                            <td width="2%">
                                <span style="font-weight:normal">:</span>
                            </td>
                            <td class="th-sub dot">
                                <span style="font-weight:normal">
                                    {{ isset($dataReport['identitas']->suku) ? $dataReport['identitas']->suku : '-' }} /
                                    {{isset($dataReport['identitas']->kebangsaan) ?  $dataReport['identitas']->kebangsaan  : ''}} </span>
                            </td>
                        </tr>
                    </table>
                    <table class="table-eex" style="margin-bottom: 7px;">
                        <tr>
                            <td width="21%" style="vertical-align:top">
                                <span style="font-weight:normal">Pendidikan</span>
                            </td>
                            <td width="2%" style="vertical-align:top">
                                <span style="font-weight:normal">:</span>
                            </td>
                            <td>
                                <table>
                                    <tr>
                                        <td style="font-weight: normal">
                                            <input type="checkbox"
                                                {{ isset($dataReport['identitas']->objectpendidikanfk) && $dataReport['identitas']->objectpendidikanfk == 0 ? 'checked' : '' }} />
                                            <span style="font-size: 10pt" color="#000000">Tidak Sekolah</span>
                                        </td>
                                        <td style="font-weight: normal">
                                            <input type="checkbox"
                                                {{isset($dataReport['identitas']->objectpendidikanfk) && $dataReport['identitas']->objectpendidikanfk == 17 ? 'checked' : '' }} />
                                            <span style="font-size: 10pt" color="#000000">Belum Sekolah</span>
                                        </td>
                                        <td style="font-weight: normal">
                                            <input type="checkbox"
                                                {{isset($dataReport['identitas']->objectpendidikanfk) && $dataReport['identitas']->objectpendidikanfk == 1 ? 'checked' : '' }} />
                                            <span style="font-size: 10pt" color="#000000">TK</span>
                                        </td>
                                        <td style="font-weight: normal">
                                            <input type="checkbox"
                                                {{isset($dataReport['identitas']->objectpendidikanfk) && $dataReport['identitas']->objectpendidikanfk == 2 ? 'checked' : '' }} />
                                            <span style="font-size: 10pt" color="#000000">SD</span>
                                        </td>
                                        <td style="font-weight: normal">
                                            <input type="checkbox"
                                                {{isset($dataReport['identitas']->objectpendidikanfk) && $dataReport['identitas']->objectpendidikanfk == 3 ? 'checked' : '' }} />
                                            <span style="font-size: 10pt" color="#000000">SLTP</span>
                                        </td>
                                        <td style="font-weight: normal">
                                            <input type="checkbox"
                                                {{isset($dataReport['identitas']->objectpendidikanfk) && $dataReport['identitas']->objectpendidikanfk == 4 ? 'checked' : '' }} />
                                            <span style="font-size: 10pt" color="#000000">SLTA</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="font-weight: normal">
                                            <input type="checkbox"
                                                {{isset($dataReport['identitas']->objectpendidikanfk) && $dataReport['identitas']->objectpendidikanfk == 24 ? 'checked' : '' }} />
                                            <span style="font-size: 10pt" color="#000000">Akademi</span>
                                        </td>
                                        <td style="font-weight: normal">
                                            <input type="checkbox"
                                                {{isset($dataReport['identitas']->objectpendidikanfk) && $dataReport['identitas']->objectpendidikanfk == 9 ? 'checked' : '' }} />
                                            <span style="font-size: 10pt" color="#000000">S1</span>
                                        </td>
                                        <td style="font-weight: normal">
                                            <input type="checkbox"
                                                {{isset($dataReport['identitas']->objectpendidikanfk) && $dataReport['identitas']->objectpendidikanfk == 10 ? 'checked' : '' }} />
                                            <span style="font-size: 10pt" color="#000000">S2</span>
                                        </td>
                                        <td style="font-weight: normal">
                                            <input type="checkbox"
                                                {{isset($dataReport['identitas']->objectpendidikanfk) && $dataReport['identitas']->objectpendidikanfk == 11 ? 'checked' : '' }} />
                                            <span style="font-size: 10pt" color="#000000">S3</span>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>

                    <table class="table-eex" style="margin-bottom: 7px;">
                        <tr>
                            <td width="21%">
                                <span style="font-weight:normal">Pekerjaan</span>
                            </td>
                            <td width="2%">
                                <span style="font-weight:normal">:</span>
                            </td>
                            <td class="th-sub dot">
                                <span style="font-weight:normal"> {{ isset($dataReport['identitas']->pekerjaan) ? $dataReport['identitas']->pekerjaan : '' }}</span>
                            </td>
                        </tr>
                    </table>

                    <table class="table-eex" style="margin-bottom: 7px;">
                        <tr>
                            <td width="21%">
                                <span style="font-weight:normal">Status Perkawinan</span>
                            </td>
                            <td width="2%">
                                <span style="font-weight:normal">:</span>
                            </td>
                            <td style="font-weight: normal">
                                <input type="checkbox"
                                    {{ isset($dataReport['identitas']->statuskawinid) && $dataReport['identitas']->statuskawinid == 1 ? 'checked' : '' }} />
                                <span style="font-size: 10pt" color="#000000">Belum Kawin</span>
                            </td>
                            <td style="font-weight: normal">
                                <input type="checkbox"
                                    {{ isset($dataReport['identitas']->statuskawinid) && $dataReport['identitas']->statuskawinid == 2 ? 'checked' : '' }} />
                                <span style="font-size: 10pt" color="#000000">Kawin</span>
                            </td>
                            <td style="font-weight: normal">
                                <input type="checkbox"
                                    {{ isset($dataReport['identitas']->statuskawinid) && $dataReport['identitas']->statuskawinid == 3 ? 'checked' : '' }} />
                                <span style="font-size: 10pt" color="#000000">Janda</span>
                            </td>
                            <td style="font-weight: normal">
                                <input type="checkbox"
                                    {{ isset($dataReport['identitas']->statuskawinid) && $dataReport['identitas']->statuskawinid == 4 ? 'checked' : '' }} />
                                <span style="font-size: 10pt" color="#000000">Duda</span>
                            </td>
                        </tr>
                    </table>
                    <table class="table-eex" style="margin-bottom: 7px;">
                        <tr>
                            <td width="21%">
                                <span style="font-weight:normal">Bahasa yang Digunakan</span>
                            </td>
                            <td width="2%">
                                <span style="font-weight:normal">:</span>
                            </td>
                            <td class="th-sub dot">
                                <span>{{ isset($dataReport['identitas']->bahasa) ? $dataReport['identitas']->bahasa : '' }}</span>
                            </td>
                            <td width="15%" style="padding-left:7px">
                                <span style="font-weight:normal">Perlu Penerjemah</span>
                            </td>
                            <td class="th-sub dot">
                                <span>{{ isset($dataReport['identitas']->bantuanpenerjemah) ? $dataReport['identitas']->bantuanpenerjemah : '' }}</span>
                            </td>
                        </tr>
                    </table>
                    <table class="table-eex" style="margin-bottom: 7px;">
                        <tr>
                            <td width="60%">
                                <span style="font-weight:normal">Apakah memerlukan pelayanan / bimbingan rohani selama
                                    dirawat</span>
                            </td>
                            <td width="2%">
                                <span style="font-weight:normal">:</span>
                            </td>
                            <td style="font-weight: normal">
                                <input type="checkbox"
                                    {{ isset($dataReport['identitas']->bantuanpelayanan) && $dataReport['identitas']->bantuanpelayanan == 'Tidak' ? 'checked' : '' }} />
                                <span style="font-size: 10pt" color="#000000">Tidak</span>
                            </td>
                            <td style="font-weight: normal">
                                <input type="checkbox"
                                    {{ isset($dataReport['identitas']->bantuanpelayanan) && $dataReport['identitas']->bantuanpelayanan == 'Ya' ? 'checked' : '' }} />
                                <span style="font-size: 10pt" color="#000000">Ya</span>
                            </td>
                        </tr>
                    </table>
                    <table class="table-eex" style="margin-bottom: 7px;">
                        <tr>
                            <td width="60%">
                                <span style="font-weight:normal">Apakah selama dirawat ada keinginan untuk dikunjungi
                                    oleh orang tertentu</span>
                            </td>
                            <td width="2%">
                                <span style="font-weight:normal">:</span>
                            </td>
                            <td style="font-weight: normal">
                                <input type="checkbox"
                                    {{ isset($dataReport['identitas']->dikunjungi) && $dataReport['identitas']->dikunjungi == 'Tidak' ? 'checked' : '' }} />
                                <span style="font-size: 10pt" color="#000000">Tidak</span>
                            </td>
                            <td style="font-weight: normal">
                                <input type="checkbox"
                                    {{ isset($dataReport['identitas']->dikunjungi) && $dataReport['identitas']->dikunjungi == 'Ya' ? 'checked' : '' }} />
                                <span style="font-size: 10pt" color="#000000">Ya</span>
                            </td>
                        </tr>
                    </table>
                </th>
            </tr>
        </table>
    </section>

    <section class="sheet padding-10mm" style="font-family:Tahoma;height: auto;overflow: hidden;">
        <table class="table-ex">
            <tr>
                <th style="text-align:left;padding:0 .4rem;" class="th-ex" colspan="2">
                    <span style="font-weight: 400;font-size:15px">PENANGUNG JAWAB</span>
                </th>
            </tr>
            <tr>
                <th class="th-ex" colspan="2">
                    <table>
                        <tr>
                            <td style="font-weight: normal;" width="20%">
                                <span>Nama</span>
                            </td>
                            <td width="2%">
                                <span style="font-weight:normal;">:</span>
                            </td>
                            <td class="dot">
                                <span style="font-weight:normal">{{ isset($dataReport['identitas']->penanggungjawab) ? $dataReport['identitas']->penanggungjawab : '' }}</span>
                            </td>
                        </tr>
                        <tr>
                            <td style="font-weight: normal" width="20%">
                                <span>Nama Keluarga / Marga</span>
                            </td>
                            <td width="2%">
                                <span style="font-weight:normal;">:</span>
                            </td>
                            <td class="dot">
                                <span></span>
                            </td>
                        </tr>
                        <tr>
                            <td style="font-weight: normal" width="20%">
                                <span>Jenis Kelamin</span>
                            </td>
                            <td width="2%">
                                <span style="font-weight:normal;">:</span>
                            </td>
                            <td>
                                <table>
                                    <tr>
                                        <td style="font-weight: normal" width="25%">
                                            <input type="checkbox" {{ isset($dataReport['identitas']->jeniskelaminpenanggungjawab) && $dataReport['identitas']->jeniskelaminpenanggungjawab == 1 ? 'checked' : '' }} />
                                            <span style="font-size: 10pt" color="#000000">Laki</span>
                                        </td>
                                        <td style="font-weight: normal">
                                            <input type="checkbox" {{ isset($dataReport['identitas']->jeniskelaminpenanggungjawab) && $dataReport['identitas']->jeniskelaminpenanggungjawab == 2 ? 'checked' : '' }}  />
                                            <span style="font-size: 10pt" color="#000000">Perempuan</span>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td style="font-weight: normal" width="20%">
                                <span>Hubungan dengan Pasien</span>
                            </td>
                            <td width="2%">
                                <span style="font-weight:normal;">:</span>
                            </td>
                            <td>
                                <table>
                                    <tr>
                                        <td style="font-weight: normal">
                                            <input type="checkbox" {{ isset($dataReport['identitas']->hubungankeluargapj) && $dataReport['identitas']->hubungankeluargapj == 2 ? 'checked' : '' }} />
                                            <span style="font-size: 10pt" color="#000000">Orang Tua</span>
                                        </td>
                                        <td style="font-weight: normal">
                                            <input type="checkbox" {{ isset($dataReport['identitas']->hubungankeluargapj) && $dataReport['identitas']->hubungankeluargapj == 3 ? 'checked' : '' }} />
                                            <span style="font-size: 10pt" color="#000000">Anak</span>
                                        </td>
                                        <td style="font-weight: normal">
                                            <input type="checkbox" {{ isset($dataReport['identitas']->hubungankeluargapj) && $dataReport['identitas']->hubungankeluargapj == 4 ? 'checked' : '' }} />
                                            <span style="font-size: 10pt" color="#000000">Suami / Istri</span>
                                        </td>
                                        <td style="font-weight: normal">
                                            <input type="checkbox" {{ isset($dataReport['identitas']->hubungankeluargapj) && $dataReport['identitas']->hubungankeluargapj == 5 ? 'checked' : '' }} />
                                            <span style="font-size: 10pt" color="#000000">Keluarga</span>
                                        </td>
                                        <td style="font-weight: normal">
                                            <input type="checkbox" {{ isset($dataReport['identitas']->hubungankeluargapj) && $dataReport['identitas']->hubungankeluargapj == 7 ? 'checked' : '' }} />
                                            <span style="font-size: 10pt" color="#000000">Teman</span>
                                        </td>
                                        <td style="font-weight: normal">
                                            <input type="checkbox"  {{ isset($dataReport['identitas']->hubungankeluargapj) && $dataReport['identitas']->hubungankeluargapj == 6 ? 'checked' : '' }}  />
                                            <span style="font-size: 10pt" color="#000000">Lain-lain</span>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                </th>
            </tr>
            <tr>
                <th class="th-ex" colspan="2">
                    <table>
                        <tr>
                            <td style="font-weight: normal" width="20%">
                                <span style="font-weight:normal;">Alamat Tempat Tinggal</span>
                            </td>
                            <td width="2%">
                                <span style="font-weight:normal;">:</span>
                            </td>
                            <td class="dot">
                                <span style="font-weight:normal">{{ isset($dataReport['identitas']->alamatrmh) ? $dataReport['identitas']->alamatrmh : ''}}</span>
                            </td>
                        </tr>
                    </table>

                    <table>
                        <tr>
                            <td width="20%" style="vertical-align:top;">
                                <span style="font-weight:normal;">Alamat KTP</span>
                            </td>
                            <td width="2%" style="vertical-align:top;">
                                <span style="font-weight:normal;">:</span>
                            </td>
                            <td>
                                <table>
                                    <tr>
                                        <td width="1%">
                                            <span style="font-weight:normal">Jl</span>
                                        </td>
                                        <td class="th-sub dot" width="20%">
                                            <span style="font-weight:normal"></span>
                                        </td>
                                        <td width="2%">
                                            <span style="font-weight:normal">RT/RW : </span>
                                        </td>
                                        <td class="th-sub dot" width="10%">
                                            <span style="font-weight:normal"></span>
                                        </td>
                                    </tr>
                                </table>
                                <table style="margin-top:8px;">
                                    <tr>
                                        <td width="6%">
                                            <span style="font-weight:normal">Kelurahan / Kecamatan : </span>
                                        </td>
                                        <td class="th-sub dot" width="20%">
                                            <span style="font-weight:normal"></span>
                                        </td>
                                    </tr>
                                </table>
                                <table style="margin-top:8px;">
                                    <tr>
                                        <td width="5%">
                                            <span style="font-weight:normal">Kota / Kode Post : </span>
                                        </td>
                                        <td class="th-sub dot" width="20%">
                                            <span style="font-weight:normal"></span>
                                        </td>
                                    </tr>
                                </table>
                                <table style="margin-top:8px;">
                                    <tr>
                                        <td width="3%">
                                            <span style="font-weight:normal">Telepon / HP : </span>
                                        </td>
                                        <td class="th-sub dot" width="20%">
                                            <span style="font-weight:normal"></span>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>

                    <table style="margin-bottom: 7px;">
                        <tr>
                            <td width="20%" style="vertical-align:top">
                                <span style="font-weight:normal">Pendidikan</span>
                            </td>
                            <td width="2%" style="vertical-align:top">
                                <span style="font-weight:normal">:</span>
                            </td>
                            <td>
                                <table>
                                    <tr>
                                        <td style="font-weight: normal">
                                            <input type="checkbox"
                                                {{ isset($dataReport['identitas']->objectpendidikanfk) && $dataReport['identitas']->objectpendidikanfk == 0 ? 'checked' : '' }} />
                                            <span style="font-size: 10pt" color="#000000">Tidak Sekolah</span>
                                        </td>
                                        <td style="font-weight: normal">
                                            <input type="checkbox"
                                                {{ isset($dataReport['identitas']->objectpendidikanfk) && $dataReport['identitas']->objectpendidikanfk == 17 ? 'checked' : '' }} />
                                            <span style="font-size: 10pt" color="#000000">Belum Sekolah</span>
                                        </td>
                                        <td style="font-weight: normal">
                                            <input type="checkbox"
                                                {{ isset($dataReport['identitas']->objectpendidikanfk) && $dataReport['identitas']->objectpendidikanfk == 1 ? 'checked' : '' }} />
                                            <span style="font-size: 10pt" color="#000000">TK</span>
                                        </td>
                                        <td style="font-weight: normal">
                                            <input type="checkbox"
                                                {{ isset($dataReport['identitas']->objectpendidikanfk) && $dataReport['identitas']->objectpendidikanfk == 2 ? 'checked' : '' }} />
                                            <span style="font-size: 10pt" color="#000000">SD</span>
                                        </td>
                                        <td style="font-weight: normal">
                                            <input type="checkbox"
                                                {{ isset($dataReport['identitas']->objectpendidikanfk) && $dataReport['identitas']->objectpendidikanfk == 3 ? 'checked' : '' }} />
                                            <span style="font-size: 10pt" color="#000000">SLTP</span>
                                        </td>
                                        <td style="font-weight: normal">
                                            <input type="checkbox"
                                                {{ isset($dataReport['identitas']->objectpendidikanfk) && $dataReport['identitas']->objectpendidikanfk == 4 ? 'checked' : '' }} />
                                            <span style="font-size: 10pt" color="#000000">SLTA</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="font-weight: normal">
                                            <input type="checkbox"
                                                {{ isset($dataReport['identitas']->objectpendidikanfk) && $dataReport['identitas']->objectpendidikanfk == 24 ? 'checked' : '' }} />
                                            <span style="font-size: 10pt" color="#000000">Akademi</span>
                                        </td>
                                        <td style="font-weight: normal">
                                            <input type="checkbox"
                                                {{ isset($dataReport['identitas']->objectpendidikanfk) && $dataReport['identitas']->objectpendidikanfk == 9 ? 'checked' : '' }} />
                                            <span style="font-size: 10pt" color="#000000">S1</span>
                                        </td>
                                        <td style="font-weight: normal">
                                            <input type="checkbox"
                                                {{ isset($dataReport['identitas']->objectpendidikanfk) && $dataReport['identitas']->objectpendidikanfk == 10 ? 'checked' : '' }} />
                                            <span style="font-size: 10pt" color="#000000">S2</span>
                                        </td>
                                        <td style="font-weight: normal">
                                            <input type="checkbox"
                                                {{ isset($dataReport['identitas']->objectpendidikanfk) && $dataReport['identitas']->objectpendidikanfk == 11 ? 'checked' : '' }} />
                                            <span style="font-size: 10pt" color="#000000">S3</span>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>

                    <table style="margin-bottom: 7px;">
                        <tr>
                            <td width="20%">
                                <span style="font-weight:normal">Pekerjaan</span>
                            </td>
                            <td width="2%">
                                <span style="font-weight:normal">:</span>
                            </td>
                            <td class="th-sub dot">
                                <span style="font-weight:normal"> {{ isset($dataReport['identitas']->pekerjaan) ? $dataReport['identitas']->pekerjaan : '' }}</span>
                            </td>
                        </tr>
                    </table>
                </th>
            </tr>

            <tr>
                <th class="th-ex" style="text-align: center;padding:0 2rem;" colspan="2">
                    <span style="text-transform: uppercase;font-size:15px">nama orang yang ditunjuk untuk bertanggung
                        jawab terhadap informasi yang diberikan oleh petugas kesehatan {{$profile->namalengkap}} :</span>
                </th>
            </tr>

            <tr>
                <th class="th-ex" width="50%">
                    <table>
                        <tr>
                            <td>
                                <span>1.</span>
                            </td>
                        </tr>
                        <tr style="height: 16px;">
                            <td></td>
                        </tr>
                        <tr>
                            <td>
                                <span style="font-weight: normal">Hubungan dengan Pasien :</span>
                            </td>
                        </tr>
                    </table>
                </th>
                <th class="th-ex" width="50%">
                    <table>
                        <tr>
                            <td>
                                <span>2.</span>
                            </td>
                        </tr>
                        <tr style="height: 16px;">
                            <td></td>
                        </tr>
                        <tr>
                            <td>
                                <span style="font-weight: normal">Hubungan dengan Pasien :</span>
                            </td>
                        </tr>
                    </table>
                </th>
            </tr>

            <tr>
                <th class="th-ex" style="text-align: left;padding:0 .4rem;" colspan="2">
                    <span style="text-transform: uppercase;font-size:15px">Data sosial, ekonomi dan fungsional</span>
                </th>
            </tr>
            <tr>
                <th class="th-ex" colspan="2">
                    <table>
                        <tr>
                            <td style="font-weight: normal;vertical-align: top;padding-top:5px" width="24%">
                                <span>Cara Pembayaran</span>
                            </td>
                            <td width="2%" style="vertical-align: top;padding-top:5px">
                                <span style="font-weight:normal;">:</span>
                            </td>
                            <td>
                                <table>
                                    <tr>
                                        <td style="font-weight: normal" width="40%">
                                            <input type="checkbox" {{ isset($dataReport['identitas']->objectkelompokpasienlastfk) && $dataReport['identitas']->objectkelompokpasienlastfk == 1 ? 'checked' : '' }} />
                                            <span style="font-size: 10pt" color="#000000">Umum</span>
                                        </td>
                                        <td style="font-weight: normal">
                                            <input type="checkbox" />
                                            <span style="font-size: 10pt" color="#000000">Asuransi</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="font-weight: normal">
                                            <input type="checkbox"  {{ isset($dataReport['identitas']->objectkelompokpasienlastfk) && $dataReport['identitas']->objectkelompokpasienlastfk == 2 ? 'checked' : '' }} />
                                            <span style="font-size: 10pt" color="#000000">BPJS Kesehatan</span>
                                        </td>
                                        <td style="font-weight: normal">
                                            <input type="checkbox"  {{ isset($dataReport['identitas']->objectkelompokpasienlastfk) && $dataReport['identitas']->objectkelompokpasienlastfk == 21 ? 'checked' : '' }} />
                                            <span style="font-size: 10pt" color="#000000">SKTM</span>
                                        </td>
                                    <tr>
                                        <td style="font-weight: normal">
                                            <input type="checkbox"  {{ isset($dataReport['identitas']->objectkelompokpasienlastfk) && $dataReport['identitas']->objectkelompokpasienlastfk == 18 ? 'checked' : '' }} />
                                            <span style="font-size: 10pt" color="#000000">BPJS Ketenagakerjaan</span>
                                        </td>
                                        <td style="font-weight: normal" width="20%">
                                            <input type="checkbox"  {{ isset($dataReport['identitas']->objectkelompokpasienlastfk) && $dataReport['identitas']->objectkelompokpasienlastfk == 3 ? 'checked' : '' }} />
                                            <span style="font-size: 10pt" color="#000000">Kontraktor</span>
                                        </td>
                                        <td class="dot">
                                            <span>:</span>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td style="font-weight: normal;vertical-align: top;padding-top:5px" width="24%">
                                <span>Tinggal dengan siapa</span>
                            </td>
                            <td width="2%" style="vertical-align: top;padding-top:5px">
                                <span style="font-weight:normal;">:</span>
                            </td>
                            <td>
                                <table>
                                    <tr>
                                        <td style="font-weight: normal" width="40%">
                                            {{-- <input type="checkbox" {{ $dataReport['identitas']->hubunganPasien == 'Orang Tua' ? 'checked' : '' }} /> --}}
                                            <input type="checkbox" />
                                            <span style="font-size: 10pt" color="#000000">Orang Tua</span>
                                        </td>
                                        <td style="font-weight: normal">
                                            <input type="checkbox" />
                                            <span style="font-size: 10pt" color="#000000">Suami / Istri</span>
                                        </td>
                                        <td style="font-weight: normal">
                                            <input type="checkbox" />
                                            <span style="font-size: 10pt" color="#000000">Sendiri</span>
                                        </td>
                                    </tr>
                                </table>
                                <table>
                                    <tr>
                                        <td style="font-weight: normal" width="15%">
                                            <input type="checkbox" />
                                            <span style="font-size: 10pt" color="#000000">Lain-lain</span>
                                        </td>
                                        <td class="dot">
                                            <span>:</span>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>

                        <tr>
                            <td style="font-weight: normal;vertical-align: top;padding-top:5px" width="24%">
                                <span>Menggunakan alat bantu diri</span>
                            </td>
                            <td width="2%" style="vertical-align: top;padding-top:5px">
                                <span style="font-weight:normal;">:</span>
                            </td>
                            <td>
                                <table>
                                    <tr>
                                        <td style="font-weight: normal" width="20%">
                                            {{-- <input type="checkbox" {{ $dataReport['identitas']->hubunganPasien == 'Orang Tua' ? 'checked' : '' }} /> --}}
                                            <input type="checkbox" />
                                            <span style="font-size: 10pt" color="#000000">Tidak</span>
                                        </td>
                                        <td style="font-weight: normal">
                                            <input type="checkbox" />
                                            <span style="font-size: 10pt" color="#000000">Alat bantu dengar</span>
                                        </td>
                                        <td style="font-weight: normal">
                                            <input type="checkbox" />
                                            <span style="font-size: 10pt" color="#000000">Kacamata / Kontak
                                                lens</span>
                                        </td>
                                    </tr>
                                </table>
                                <table>
                                    <tr>
                                        <td style="font-weight: normal" width="20%">
                                            <input type="checkbox" />
                                            <span style="font-size: 10pt" color="#000000">Kawat Gigi</span>
                                        </td>
                                        <td style="font-weight: normal">
                                            <input type="checkbox" />
                                            <span style="font-size: 10pt" color="#000000">Implant</span>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                </th>
            </tr>

            <tr>
                <th class="th-ex" colspan="2" style="padding-left:6px">
                    <span style="font-weight: normal;font-size:13px">Data ini Saya buat dengan sesungguhnya tanpa ada yang disembunyikan atau paksaan dari pihak manapun, 
                        karena Saya paham dan mengerti bahwa data tersebut diperlukan dalam proses perawatan Saya.</span>
                </th>
            </tr>
        </table>

        <br>
        <table width="100%">
            <tr>
                <td width="60%"></td>
                <td style="width: 7%;">
                    <span>{{$profile->namakota ? $profile->namakota : 'Bandung'}},</span>
                </td>
                <td class="dot" style="width:20%"></td>
                <td style="width: 3%;">
                    <span>Jam</span>
                </td>
                <td class="dot" style="width: 8%"></td>
            </tr>
        </table>
        <br>
        <br>
        <table style="text-align: center" width="100%">
            <tr>
                <td  width="50%">
                    <span>Petugas</span>
                </td>
                <td  width="50%">
                    <span>Keluarga Pasien</span>
                </td>
            </tr>
            <tr style="height: 8rem;">
                <td  width="50%">
                    <span>(...................................)</span>
                </td>
                <td  width="50%">
                    <span>(...................................)</span>
                </td>
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
