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

        .table-sub {
            padding: 5px;
            border: 1px black;
            border-collapse: collapse;
            width: 100%;
        }

        .tr-sub,
        .th-sub {
            /* padding:3px; */
            text-align: center;
            border: 1px solid black;
        }

        .th-ex,
        td.ex {
            border: 1px solid black;
            padding: 2px;
            padding-top:0px;
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
    </style>

    @stack('style')

</head>

<body class="A4" style="font-family:Tahoma;height: auto" onload="window.print()">
    <section class="sheet padding-10mm" style="font-family:Tahoma;height: auto;overflow: hidden;">
        <tr>
            <td>
                <table style="width:100%;border:0px">
                    <caption style="display: none"></caption>
                    <tr style="display: none">
                        <th scope="col"></th>
                    </tr>
                    <tr>
                        <td rowspan="5">
                            <p style="text-align:right">
                                @if ((isset($res['pdf']) && $res['pdf']) || (isset($res['storage']) && $res['storage']))
                                    <img src="{{ 'img/logo-rs.png' }}" width="80px" border="0">
                                @else
                                    <img src="{{ asset('img/logo-rs.png') }}" width="80px" border="0">
                                @endif
                            </p>
                        </td>
                        <td style=" text-align: center">
                            <span style="font-size: 14pt;font-weight: 600;letter-spacing: 4px;" color="#000000">
                                {!! strtoupper($profile->namapemerintahan) !!}
                            </span>
                        </td>
                        <td rowspan="5">
                            <div style="width: 80px;">
                        </td>
                    </tr>
                    <tr>
                        <td style=" text-align: center">
                            <span style="font-size: 16pt;font-weight: 600;letter-spacing: 2px;" color="#000000">
                                {!! strtoupper($profile->namalengkap) !!}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td style=" text-align: center">
                            <span style="font-size: 12pt;" color="#000000">
                                {!! $profile->alamatlengkap !!}
                                Telp. {{ $profile->fixedphone }} <br> Fax. {{ $profile->faksimile }} Whatsapp.
                                <span> {!! $profile->whatsapp !!} </span>
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td style=" text-align: center">
                            <span style="font-size: 12pt;" color="#000000">
                                {{-- {!! $profile->alamatemail !!} --}}
                                Email : <a>{{ $profile->alamatemail }}</a>
                                Website : <a href="https://{{ $profile->website }}" target="_blank">
                                    {!! $profile->website !!} </a>

                            </span>
                        </td>
                    </tr>
                </table>
                <hr class="baris1">
                <hr class="baris2">
            </td>
        </tr>
        <hr style="border:2px solid #000;margin:unset">
        <table>
            <thead>
                <tr>
                    <th style="padding:5px;">
                        <span style="font-size: 12pt;font-weight: 500;letter-spacing: 1px;text-transform: uppercase"
                            color="#000000">
                            lembar masuk dan keluar
                        </span>
                    </th>
                </tr>
            </thead>
        </table>
        <table class="table-ex">
            <tr>
                <th width="50%" class="th-ex">
                    <table class="table-ex">
                        <tr>
                            <th width="30%">
                                <span style="font-size: 11pt; color:#000000;font-weight: 400">NAMA PASIEN</span>
                            </th>
                            <th>
                                <span style="font-size: 11pt; color:#000000;font-weight: 400">:</span>
                            </th>
                            <th>
                                <span
                                    style="font-size: 11pt; color:#000000">{{ $dataReport['identitas']->namapasien }}</span>
                            </th>
                        </tr>
                    </table>
                </th>
                <th width="50%" class="th-ex">
                    <table class="table-ex">
                        <tr>
                            <th width="10%">
                                <span style="font-size: 13px; color:#000000;font-weight: 400">NO CM</span>
                            </th>
                            <th width="1%">
                                <span style="font-size: 13px; color:#000000;font-weight: 400">:</span>
                            </th>
                            <th width="15%">
                                <span
                                    style="font-size: 13px; color:#000000">{{ $dataReport['identitas']->nocm }}</span>
                            </th>
                            <th width="15%">
                                <span style="font-size: 13px; color:#000000;font-weight: 400">No. Regis</span>
                            </th>
                            <th width="1%">
                                <span style="font-size: 13px; color:#000000;font-weight: 400">:</span>
                            </th>
                            <th width="15%">
                                <span
                                    style="font-size: 13px; color:#000000">{{ $dataReport['identitas']->noregistrasi }}</span>
                            </th>
                        </tr>
                    </table>
                </th>
            </tr>
            <tr>
                <th rowspan="2" class="th-ex">
                    <table class="table-ex">
                        <tr>
                            <th width="10%" style="vertical-align: baseline;padding-top: 5px;">
                                <span style="font-size: 13px; color:#000000;font-weight: 400">UMUR</span>
                            </th>
                            <th width="30%">
                                <table style="border: 1px solid black;border-collapse: collapse;margin-top:2px">
                                    <tr>
                                        <th class="th-sub">
                                            <span style="font-weight: normal;text-transform:uppercase;font-size:13px">Tahun</span>
                                        </th>
                                        <th class="th-sub">
                                            <span style="font-weight: normal;text-transform:uppercase;font-size:13px">Bulan</span>
                                        </th>
                                        <th class="th-sub">
                                            <span style="font-weight: normal;text-transform:uppercase;font-size:13px">Hari</span>
                                        </th>
                                    </tr>
                                    <tr>
                                        <td class="th-sub" style="padding-bottom: 5px">
                                            <span class="label-sub">{{ $dataReport['identitas']->tahun }}</span>
                                        </td>
                                        <td class="th-sub" style="padding-bottom: 5px">
                                            <span class="label-sub">{{ $dataReport['identitas']->bulan }}</span>
                                        </td>
                                        <td class="th-sub" style="padding-bottom: 5px">
                                            <span class="label-sub">{{ $dataReport['identitas']->hari }}</span>
                                        </td>
                                    </tr>
                                </table>
                            </th>
                            <th width="25%">
                                <table>
                                    <tr style="text-align: center">
                                        <th>
                                            <span
                                                style="font-size: 13px; color:#000000;font-weight: 400">KELAMIN:</span>
                                        </th>
                                    </tr>
                                    <tr style="text-align: center">
                                        <td>
                                            <span
                                                style="font-weight: 100">{{ $dataReport['identitas']->jeniskelamin }}</span>
                                        </td>
                                    </tr>
                                    <tr style="text-align: center">
                                        <td>
                                            <span
                                                style="font-weight: 100">{{ date('d/m/Y', strtotime($dataReport['identitas']->tgllahir)) }}</span>
                                        </td>
                                    </tr>
                                </table>
                            </th>
                        </tr>
                    </table>
                </th>
                <th class="th-ex">
                    <table>
                        <tr>
                            <td width="20%" style="font-weight: normal">Pekerjaan</td>
                            <td width="2%" style="font-weight: normal">:</td>
                            <td>{{ $dataReport['identitas']->pekerjaan }}</td>
                        </tr>
                    </table>
                </th>
            </tr>
            <tr>
                <th class="th-ex">
                    <table>
                        <tr>
                            <td width="20%" style="font-weight: normal">Agama</td>
                            <td width="2%" style="font-weight: normal">:</td>
                            <td>{{$dataReport['identitas']->agama}}</td>
                        </tr>
                    </table>
                </th>
            </tr>

            <tr>
                <th class="th-ex">
                    <table>
                        <tr>
                            <td width="40%" style="font-weight: normal">Nama Ayah</td>
                            <td width="2%" style="font-weight: normal">:</td>
                            <td>{{ $dataReport['identitas']->namaayah }}</td>
                        </tr>
                        <tr>
                            <td width="40%" style="font-weight: normal">Nama Ibu</td>
                            <td width="2%" style="font-weight: normal">:</td>
                            <td>{{ $dataReport['identitas']->namaibu }}</td>
                        </tr>
                        <tr>
                            <td width="40%" style="font-weight: normal">Nama Suami / Istri</td>
                            <td width="2%" style="font-weight: normal">:</td>
                            <td>{{ $dataReport['identitas']->namasuamiistri }}</td>
                        </tr>
                    </table>
                </th>
                <th class="th-ex">
                    <table>
                        <tr>
                            <td width="25%" style="font-weight: normal">Pendidikan</td>
                            <td width="2%" style="font-weight: normal">:</td>
                            <td>{{ $dataReport['identitas']->pendidikan }}</td>
                        </tr>
                        <tr>
                            <td width="25%" style="font-weight: normal">Cara Masuk RS</td>
                            <td width="2%" style="font-weight: normal">:</td>
                            <td width="30%" style="font-weight: normal">
                                <input type="checkbox" />
                                <span style="font-size: 11px" color="#000000">Darurat</span>
                            </td>
                            <td width="30%" style="font-weight: normal">
                                <input type="checkbox" />
                                <span style="font-size: 11px" color="#000000">Biasa</span>
                            </td>
                        </tr>
                    </table>
                </th>
            </tr>

            <tr>
                <th class="th-ex">
                    <table>
                        <tr>
                            <td width="40%" style="font-weight: normal">Status Perkawinan</td>
                            <td width="2%" style="font-weight: normal">:</td>
                            <td>{{ $dataReport['identitas']->statusperkawinan }}</td>
                        </tr>
                        {{-- <tr>
                            <td width="40%" style="font-weight: normal">Nama Ibu</td>
                            <td width="2%" style="font-weight: normal">:</td>
                            <td>{{$dataReport['identitas']->namaibu}}</td>
                        </tr>
                        <tr>
                            <td width="40%" style="font-weight: normal">Nama Suami / Istri</td>
                            <td width="2%" style="font-weight: normal">:</td>
                            <td>Weri</td>
                        </tr> --}}
                    </table>
                </th>
                <th class="th-ex">
                    <table>
                        <tr>
                            <td width="40%" style="font-weight: normal">Kasus Polisi</td>
                            <td width="2%" style="font-weight: normal">:</td>
                            <td width="30%" style="font-weight: normal">
                                <input type="checkbox" />
                                <span style="font-size: 11px" color="#000000">Ya</span>
                            </td>
                            <td width="40%" style="font-weight: normal">
                                <input type="checkbox" />
                                <span style="font-size: 11px" color="#000000">Tidak</span>
                            </td>
                        </tr>
                        <tr>
                            <td width="25%" style="font-weight: normal">Peserta PHB / ASKES</td>
                            <td width="2%" style="font-weight: normal">:</td>
                            <td width="30%" style="font-weight: normal">
                                <input type="checkbox" />
                                <span style="font-size: 11px" color="#000000">Ya</span>
                            </td>
                            <td width="30%" style="font-weight: normal">
                                <input type="checkbox" />
                                <span style="font-size: 11px" color="#000000">Tidak</span>
                            </td>
                        </tr>
                    </table>
                </th>
            </tr>

            <tr>
                <th class="th-ex">
                    <table>
                        <tr>
                            <td width="40%" style="font-weight: normal">Alamat</td>
                            <td width="2%" style="font-weight: normal">:</td>
                            <td>{{ $dataReport['identitas']->alamatlengkap }}</td>
                        </tr>
                        <tr>
                            <td width="40%" style="font-weight: normal">Kampung</td>
                            <td width="2%" style="font-weight: normal">:</td>
                            <td>{{ $dataReport['identitas']->namadesakelurahan }}</td>
                        </tr>
                        <tr>
                            <td width="40%" style="font-weight: normal">Desa</td>
                            <td width="2%" style="font-weight: normal">:</td>
                            <td>{{ $dataReport['identitas']->namakecamatan }}</td>
                        </tr>
                        <tr>
                            <td width="40%" style="font-weight: normal">Kota</td>
                            <td width="2%" style="font-weight: normal">:</td>
                            <td style="text-transform: uppercase">{{ $dataReport['identitas']->namakotakabupaten }}
                            </td>
                        </tr>
                    </table>
                </th>
                <th class="th-ex">
                    <table>
                        <tr>
                            <td width="10%" style="font-weight: normal">Dirujuk Oleh</td>
                            <td width="2%" style="font-weight: normal">:</td>
                        </tr>
                        <tr>
                            <td width="10%" style="font-weight: normal">
                                <input type="checkbox" />
                                <span style="font-size: 11px" color="#000000">Rumah Sakit</span>
                            </td>
                            <td width="20%" style="font-weight: normal">
                                <input type="checkbox" />
                                <span style="font-size: 11px" color="#000000">Puskesmas</span>
                            </td>
                            <td width="20%" style="font-weight: normal">
                                <input type="checkbox" />
                                <span style="font-size: 11px" color="#000000">Dokter</span>
                            </td>
                        </tr>
                        <tr>
                            <td width="30%" style="font-weight: normal">
                                <input type="checkbox" />
                                <span style="font-size: 11px" color="#000000">Paramedis</span>
                            </td>
                            <td width="20%" style="font-weight: normal;position: absolute;">
                                <input type="checkbox"
                                    {{ $dataReport['identitas']->asalrujukan == 'Datang Sendiri' ? 'checked' : '' }} />
                                <span style="font-size: 11px" color="#000000">Kemauan Sendiri</span>
                            </td>
                        </tr>
                    </table>
                </th>
            </tr>
            <tr>
                <th class="th-ex" rowspan="2">
                    <table>
                        <tr>
                            <td width="40%" style="font-weight: normal">Nama Penangung Jawab</td>
                            <td width="2%" style="font-weight: normal">:</td>
                            <td>{{ $dataReport['identitas']->penanggungjawab }}</td>
                        </tr>
                        {{-- <tr>
                            <td width="40%" style="font-weight: normal">Pekerjaan</td>
                            <td width="2%" style="font-weight: normal">:</td>
                            <td></td>
                        </tr> --}}
                        <tr>
                            <td width="40%" style="font-weight: normal">Alamat</td>
                            <td width="2%" style="font-weight: normal">:</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td width="40%" style="font-weight: normal">Desa</td>
                            <td width="2%" style="font-weight: normal">:</td>
                            <td style="text-transform: uppercase"></td>
                        </tr>
                        <tr>
                            <td width="40%" style="font-weight: normal">Kecamatan</td>
                            <td width="2%" style="font-weight: normal">:</td>
                            <td style="text-transform: uppercase"></td>
                        </tr>
                        <tr>
                            <td width="40%" style="font-weight: normal">Kota</td>
                            <td width="2%" style="font-weight: normal">:</td>
                            <td style="text-transform: uppercase"></td>
                        </tr>
                    </table>
                </th>
                <th class="th-ex">
                    <table>
                        <tr>
                            <td width="10%" style="font-weight: normal">Tanggal Masuk</td>
                            <td width="2%" style="font-weight: normal">:</td>
                            <td width="20%" style="font-weight: normal">
                                {{ isset($dataReport['identitas']->tglmasuk) ? \Carbon\Carbon::parse($dataReport['identitas']->tglmasuk)->format('d-m-Y H:i:s')  : '' }}
                            </td>
                        </tr>
                    </table>
                </th>
            </tr>
            <tr>
                <th class="th-ex">
                    <table>
                        <tr>
                            <td width="10%" style="font-weight: normal">Tanggal Keluar</td>
                            <td width="2%" style="font-weight: normal">:</td>
                            <td width="20%" style="font-weight: normal">
                                {{ isset($dataReport['identitas']->tglpulang) ? \Carbon\Carbon::parse($dataReport['identitas']->tglpulang)->format('d-m-Y HH:i:s') : '' }}
                            </td>
                        </tr>
                    </table>
                </th>
            </tr>
            {{-- <tr>
                <th class="th-ex">
                    <table>
                        <tr>
                            <td width="8%" style="font-weight: normal">Tanggal Meninggal</td>
                            <td width="2%" style="font-weight: normal">:</td>
                            <td width="20%" style="font-weight: normal">{{ isset($dataReport['identitas']->tglmeninggal) ? date('d/m/Y', strtotime($dataReport['identitas']->tglmeninggal)) : ''}}</td>
                        </tr>
                    </table>
                </th>
            </tr> --}}

            <tr>
                <th class="th-ex">
                    <table>
                        <tr>
                            <td width="10%" style="font-weight: normal">Ruang</td>
                            <td width="2%" style="font-weight: normal">:</td>
                            <td>{{ $dataReport['identitas']->namaruangan }}</td>
                        </tr>
                    </table>
                </th>
                <th class="th-ex">
                    <table>
                        <tr>
                            <td width="33%" style="font-weight: normal">Tanggal Meninggal</td>
                            <td width="2%" style="font-weight: normal">:</td>
                            <td>{{ isset($dataReport['identitas']->tglmeninggal) ? date('d/m/Y', strtotime($dataReport['identitas']->tglmeninggal)) : '' }}
                            </td>
                        </tr>
                    </table>
                </th>
            </tr>
            <tr>
                <th class="th-ex">
                    <table>
                        <tr>
                            <td width="10%" style="font-weight: normal">Kelas</td>
                            <td width="2%" style="font-weight: normal">:</td>
                            <td>{{ $dataReport['identitas']->namakelas }}</td>
                        </tr>
                        <tr>
                            <td width="10%" style="font-weight: normal">UPP</td>
                            <td width="2%" style="font-weight: normal">:</td>
                            <td></td>
                        </tr>
                    </table>
                </th>
                <th class="th-ex">
                    <table>
                        <tr>
                            <td width="40%" style="font-weight: normal">Lama Dirawat</td>
                            <td width="20%"></td>
                            <td width="20%" style="font-weight: normal">Hari</td>
                            <td width="30%"></td>
                            <td style="font-weight: normal">Jam</td>
                        </tr>
                    </table>
                </th>
            </tr>
            <tr>
                <th class="th-ex">
                    <table>
                        <tr>
                            <td style="font-weight: normal">Diagnosa Masuk</td>
                        </tr>
                    </table>
                    <table>
                        <tr>
                            <td width="20%" style="font-weight: normal">Utama</td>
                            <td width="2%" style="font-weight: normal">:</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td width="20%" style="font-weight: normal">Tambahan</td>
                            <td width="2%" style="font-weight: normal">:</td>
                            <td></td>
                        </tr>
                    </table>
                </th>
                <th class="th-ex" style="margin: 0px;padding:0px">
                    <table width="100%" style="border-collapse: collapse;">
                        <tr>
                            <td rowspan="2" colspan="2" width="33%"
                                style="border-right: 1px solid black;padding: 8px;">
                                <span>Kepala Ruangan</span>
                            </td>
                            <td
                                style="border-right: 1px solid black;text-align:center;border-bottom: 1px solid black;">
                                <span>Nama</span>
                            </td>
                            <td style="text-align:center;border-bottom: 1px solid black;">
                                <span>Paraf</span>
                            </td>
                        </tr>
                        <tr>
                            <td style="border-right: 1px solid black;padding:5px">
                                <span></span>
                            </td>
                            <td style="padding:5px">
                                <span></span>
                            </td>
                        </tr>
                    </table>
                </th>

            </tr>

        </table>

        <table width="100%"
            style="border-collapse: collapse; border-left:1px solid black;border-right:1px solid black;border-bottom:1px solid black">
            <tr>
                <th rowspan="3" width="30%" style="border-right:1px solid black">
                    <table>
                        <tr>
                            <td>
                                <span style="font-weight: normal;text-transform: uppercase">Diagnosa Akhir</span>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span style="font-weight: normal">(Jangan Disingkat, Ditulis dengan huruf CETAK)</span>
                            </td>
                        </tr>
                    </table>
                </th>
                <th width="40%" style="border-right:1px solid black;border-bottom:1px solid black">
                    <table>
                        <tr>
                            <td width="20%" style="font-weight: normal;text-align: left;">Utama</td>
                            <td width="2%" style="font-weight: normal">:</td>
                            <td style="text-align: left"></td>
                        </tr>
                    </table>
                </th>
                <th style="border-bottom:1px solid black">
                    <table>
                        <tr>
                            <td width="28%" style="font-weight: normal">No Code</td>
                            <td width="2%" style="font-weight: normal">:</td>
                            <td></td>
                        </tr>
                    </table>
                </th>
            </tr>
            <tr>
                <th width="40%" style="border-right:1px solid black;border-bottom:1px solid black">
                    <table>
                        <tr>
                            <td width="20%" style="font-weight: normal;vertical-align: top;">Tambahan</td>
                            <td width="2%" style="font-weight: normal;vertical-align: top;">:</td>
                            <td style="text-align: left">
                                <table>
                                    <tr>
                                        <td style="text-align: left;font-weight: normal">1</td>
                                    </tr>
                                    <tr>
                                        <td style="text-align: left;font-weight: normal">2</td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                </th>
                <th style="border-bottom:1px solid black">

                </th>
            </tr>
            <tr>
                <th width="40%" style="border-right:1px solid black;border-bottom:1px solid black">
                    <table>
                        <tr>
                            <td width="20%" style="font-weight: normal;vertical-align: top;">Komplikasi</td>
                            <td width="2%" style="font-weight: normal;vertical-align: top;">:</td>
                            <td style="text-align: left">
                                <table>
                                    <tr>
                                        <td style="text-align: left;font-weight: normal"></td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                </th>
                <th style="border-bottom:1px solid black">

                </th>
            </tr>
        </table>

        <table width="100%"
            style="border-collapse: collapse; border-left:1px solid black;border-right:1px solid black;border-bottom:1px solid black">
            <tr>
                <td style="border-right: 1px solid black;padding:3px">
                    <span style="font-weight: normal;text-transform: uppercase">Nama Operasi Biopsi</span>
                </td>
                <td style="border-right: 1px solid black;text-align:center;padding:3px">
                    <span style="font-weight: normal;text-transform: uppercase;">tanggal</span>
                </td>
                <td>
                    <span style="font-weight: normal;padding:3px">No. Code</span>
                </td>
            </tr>
        </table>
        <table width="100%"
            style="border-collapse: collapse; border-left:1px solid black;border-right:1px solid black;border-bottom:1px solid black">
            <tr>
                <td style="padding:10px;border-bottom:1px solid black">
                    <span>1</span>
                </td>
            </tr>
            <tr>
                <td style="padding:10px;">
                    <span>2</span>
                </td>
            </tr>
        </table>

        <table width="100%"
            style="border-collapse: collapse; border-left:1px solid black;border-right:1px solid black;border-bottom:1px solid black">
            <tr>
                <th style="border-right:1px solid black;text-align:left;padding:8px;vertical-align: top;"
                    width="40%">
                    <p style="font-weight: normal;text-transform: uppercase;font-size:11px;margin:0px">Keadaan Keluar
                    </p>
                    <div style="display:flex;justify-content: space-between;padding-top: 5px;">
                        <span style="font-weight: normal;text-transform: uppercase;font-size:11px">1. Sembuh</span>
                        <span style="font-weight: normal;text-transform: uppercase;font-size:11px">4. Meninggal <48 Jam
                                </span>
                    </div>
                    <div style="display:flex;justify-content: space-between;">
                        <span style="font-weight: normal;text-transform: uppercase;font-size:11px">2. Berobat
                            Jalan</span>
                        <span style="font-weight: normal;text-transform: uppercase;font-size:11px">5. Meninggal >48
                            Jam</span>
                    </div>
                    <div style="display:flex;justify-content: space-between;">
                        <span style="font-weight: normal;text-transform: uppercase;font-size:11px">3. Cacat</span>
                        <span style="font-weight: normal;text-transform: uppercase;font-size:11px">6. Lain-lain</span>
                    </div>
                </th>

                <th style="border-right:1px solid black;text-align:left;padding:8px;vertical-align: top;"
                    width="25%">
                    <p style="font-weight: normal;text-transform: uppercase;font-size:11px;display:block;margin:0px">
                        Cara Keluar</p>
                    <span
                        style="font-weight: normal;text-transform: uppercase;font-size:11px;display:block;padding-top: 5px;">1.
                        Izin Dokter</span>
                    <span style="font-weight: normal;text-transform: uppercase;font-size:11px;display:block">2.
                        Permintaan Sendiri</span>
                    <span style="font-weight: normal;text-transform: uppercase;font-size:11px">3. Dirujuk ke RS
                        Lain</span>
                </th>
                <th style="text-align:left;padding:8px" width="35%">
                    <p style="font-weight: normal;text-transform: uppercase;font-size:11px;display:block;margin:0px">
                        Catatan Khusus</p>
                    <span
                        style="font-weight: normal;text-transform: uppercase;font-size:11px;display:block;padding-top: 5px;">1.
                        Alergi</span>
                    <span style="font-weight: normal;text-transform: uppercase;font-size:11px;display:block">2.
                        Transfusi : Tidak / Ya</span>
                    <span style="font-weight: normal;text-transform: uppercase;font-size:11px;display:block">3. Gol
                        Darah</span>
                    <span style="font-weight: normal;text-transform: uppercase;font-size:11px;display:block">4.
                        Penyakit Berat</span>
                    <span style="font-weight: normal;text-transform: uppercase;font-size:11px;display:block">5.
                        Penyakit Menular</span>
                    <span style="font-weight: normal;text-transform: uppercase;font-size:11px;display:block">6.
                        DLL</span>
                </th>
            </tr>
        </table>

        <table width="100%"
            style="border-collapse: collapse; border-left:1px solid black;border-right:1px solid black;border-bottom:1px solid black">
            <tr>
                <td style="padding:10px;">
                    <span>Nama Dokter Tanggung Jawab : {{$dataReport['identitas']->namalengkap ? $dataReport['identitas']->namalengkap : ''}} </span>
                </td>
                <td style="padding:10px;" width="30%">
                    <span>Tanda Tangan</span>
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
