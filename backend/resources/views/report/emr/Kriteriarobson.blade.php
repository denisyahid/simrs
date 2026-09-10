<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>EMR - Laporan Operasi</title>
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
        .th-pri{
            border: 1px solid;
        }
        .td-pri{
            border: 1px solid;
        }

    </style>

    @stack('style')

</head>

<body class="A4" style="font-family:DejaVu Sans, sans-serif;;height: auto" onload="window.print()">
    <section class="sheet padding-10mm" style="font-family:DejaVu Sans, sans-serif;;height: auto;overflow: hidden;">
        <table width="100%" cellspacing="0" cellpadding="0" border="1">
            <tr>
                <td width="60%" style="text-align:right" colspan=2>
                    <table width="100%" cellspacing="0" cellpadding="0" border="0">
                        <tr class="bg-blue">
                            <td>
                                <td width="50%" style="text-align:left; font-size: 14px">RSUD BALI MANDARA</td>
                                <td width="50%" style="font-size: 14px; text-align: right;">RM.7c/IBSA/01</td>
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
                        <span style="font-size: 16px">Kriteria Robson </span>
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

        <table width="100%" cellspacing="0" cellpadding="0" border="0">
            <thead>
                <th class="th-pri">Nomor</th>
                <th class="th-pri">Indikasi Proses</th>
                <th class="th-pri">Ya</th>
                <th class="th-pri">Tidak</th>
                <th class="th-pri">Keterangan</th>
            </thead>
            <tbody>
                <tr>
                    <td class="td-pri" style="text-align: center">1</td>
                    <td class="td-pri">Pasien melakukan ANC minimal 3X di rumah sakit tersebut</td>
                    <td class="td-pri" style="text-align: center">
                        @if (isset($data['anc']) && $data['anc'] == 'Ya')
                            <input type="checkbox" checked/>
                        @else
                            <input type="checkbox" />
                        @endif
                    </td>
                    <td class="td-pri" style="text-align: center">
                        @if (isset($data['cekanc']) && $data['cekanc'] == 'Tidak')
                            <input type="checkbox" checked/>
                        @else
                            <input type="checkbox" />
                        @endif
                    </td>
                    <td style="text-align: center" class="td-pri">
                        {{isset($data['textanc']) ? $data['textanc'] : ''}}
                    </td>
                </tr>

                <tr>
                    <td class="td-pri" style="text-align: center">2</td>
                    <td class="td-pri">Pasien memiliki dan membawa buku pink KIA sebelum SC</td>
                    <td class="td-pri" style="text-align: center">
                        @if (isset($data['kia']) && $data['kia'] == 'Ya')
                            <input type="checkbox" checked/>
                        @else
                            <input type="checkbox" />
                        @endif
                    </td>
                    <td class="td-pri" style="text-align: center">
                        @if (isset($data['kis']) && $data['kis'] == 'Tidak')
                            <input type="checkbox" checked/>
                        @else
                            <input type="checkbox" />
                        @endif
                    </td>
                    <td style="text-align: center" class="td-pri">
                        {{isset($data['kos']) ? $data['kos'] : ''}}
                    </td>
                </tr>

                <tr>
                    <td class="td-pri" style="text-align: center">3</td>
                    <td class="td-pri">Pasien datang dengan KU baik sebelum tindakan SC</td>
                    <td class="td-pri" style="text-align: center">
                        @if (isset($data['ku']) && $data['ku'] == 'Ya')
                            <input type="checkbox" checked/>
                        @else
                            <input type="checkbox" />
                        @endif
                    </td>
                    <td class="td-pri" style="text-align: center">
                        @if (isset($data['ka']) && $data['ka'] == 'Tidak')
                            <input type="checkbox" checked/>
                        @else
                            <input type="checkbox" />
                        @endif
                    </td>
                    <td style="text-align: center" class="td-pri">
                        {{isset($data['okeh']) ? $data['okeh'] : ''}}
                    </td>
                </tr>

                <tr>
                    <td class="td-pri" style="text-align: center">4</td>
                    <td class="td-pri">Pasien datang dengan KU baik sebelum tindakan SC</td>
                    <td class="td-pri" style="text-align: center">
                        @if (isset($data['kusetelah']) && $data['kusetelah'] == 'Ya')
                            <input type="checkbox" checked/>
                        @else
                            <input type="checkbox" />
                        @endif
                    </td>
                    <td class="td-pri" style="text-align: center">
                        @if (isset($data['kesetelah']) && $data['kesetelah'] == 'Tidak')
                            <input type="checkbox" checked/>
                        @else
                            <input type="checkbox" />
                        @endif
                    </td>
                    <td style="text-align: center" class="td-pri">
                        {{isset($data['scsetealh']) ? $data['scsetealh'] : ''}}
                    </td>
                </tr>

                <tr>
                    <td class="td-pri" style="text-align: center">5</td>
                    <td class="td-pri">Pasien datang dengan GCS normal (14-15) sebelum SC</td>
                    <td class="td-pri" style="text-align: center">
                        @if (isset($data['gcs']) && $data['gcs'] == 'Ya')
                            <input type="checkbox" checked/>
                        @else
                            <input type="checkbox" />
                        @endif
                    </td>
                    <td class="td-pri" style="text-align: center">
                        @if (isset($data['sgc']) && $data['sgc'] == 'Tidak')
                            <input type="checkbox" checked/>
                        @else
                            <input type="checkbox" />
                        @endif
                    </td>
                    <td style="text-align: center" class="td-pri">
                        {{isset($data['cgs']) ? $data['cgs'] : ''}}
                    </td>
                </tr>

                <tr>
                    <td class="td-pri" style="text-align: center">6</td>
                    <td class="td-pri">Pasien mengalami perubahan TD sistolik > 30 mmHg sebelum dan setelah SC disertai gejala syok</td>
                    <td class="td-pri" style="text-align: center">
                        @if (isset($data['td']) && $data['td'] == 'Ya')
                            <input type="checkbox" checked/>
                        @else
                            <input type="checkbox" />
                        @endif
                    </td>
                    <td class="td-pri" style="text-align: center">
                        @if (isset($data['dt']) && $data['dt'] == 'Tidak')
                            <input type="checkbox" checked/>
                        @else
                            <input type="checkbox" />
                        @endif
                    </td>
                    <td style="text-align: center" class="td-pri">
                        {{isset($data['dr']) ? $data['dr'] : ''}}
                    </td>
                </tr>

                <tr>
                    <td class="td-pri" style="text-align: center">7</td>
                    <td class="td-pri">Pasien diperiksa darah lengkap sebelum SC (Hb, Leukosit,Trombosit, Ht)</td>
                    <td class="td-pri" style="text-align: center">
                        @if (isset($data['hb']) && $data['hb'] == 'Ya')
                            <input type="checkbox" checked/>
                        @else
                            <input type="checkbox" />
                        @endif
                    </td>
                    <td class="td-pri" style="text-align: center">
                        @if (isset($data['bh']) && $data['bh'] == 'Tidak')
                            <input type="checkbox" checked/>
                        @else
                            <input type="checkbox" />
                        @endif
                    </td>
                    <td style="text-align: center" class="td-pri">
                        {{isset($data['hhb']) ? $data['hhb'] : ''}}
                    </td>
                </tr>

                <tr>
                    <td class="td-pri" style="text-align: center">8</td>
                    <td class="td-pri">Pasien diperiksa darah lengkap setelah SC (Hb, Leukosit,Trombosit, Ht)</td>
                    <td class="td-pri" style="text-align: center">
                        @if (isset($data['leukosit']) && $data['leukosit'] == 'Ya')
                            <input type="checkbox" checked/>
                        @else
                            <input type="checkbox" />
                        @endif
                    </td>
                    <td class="td-pri" style="text-align: center">
                        @if (isset($data['leu']) && $data['leu'] == 'Tidak')
                            <input type="checkbox" checked/>
                        @else
                            <input type="checkbox" />
                        @endif
                    </td>
                    <td style="text-align: center" class="td-pri">
                        {{isset($data['eul']) ? $data['eul'] : ''}}
                    </td>
                </tr>

                <tr>
                    <td class="td-pri" style="text-align: center">9</td>
                    <td class="td-pri">Pasien yang diperiksa: <br>A. PT/APTT <br>B. CT/BT sebelum SC</td>
                    <td class="td-pri" style="text-align: center">
                        @if (isset($data['pt']) && $data['pt'] == 'Ya')
                            <input type="checkbox" checked/>
                        @else
                            <input type="checkbox" />
                        @endif
                    </td>
                    <td class="td-pri" style="text-align: center">
                        @if (isset($data['ct']) && $data['ct'] == 'Tidak')
                            <input type="checkbox" checked/>
                        @else
                            <input type="checkbox" />
                        @endif
                    </td>
                    <td style="text-align: center" class="td-pri">
                        {{isset($data['bt']) ? $data['bt'] : ''}}
                    </td>
                </tr>

                <tr>
                    <td class="td-pri" style="text-align: center">10</td>
                    <td class="td-pri">Pasien dilakukan transfusi darah sesuai indikasi dan/atau memiliki Hb  8 g/dLsebelum SC</td>
                    <td class="td-pri" style="text-align: center">
                        @if (isset($data['darah']) && $data['darah'] == 'Ya')
                            <input type="checkbox" checked/>
                        @else
                            <input type="checkbox" />
                        @endif
                    </td>
                    <td class="td-pri" style="text-align: center">
                        @if (isset($data['transui']) && $data['transui'] == 'Tidak')
                            <input type="checkbox" checked/>
                        @else
                            <input type="checkbox" />
                        @endif
                    </td>
                    <td style="text-align: center" class="td-pri">
                        {{isset($data['hb']) ? $data['hb'] : ''}}
                    </td>
                </tr>

                <tr>
                    <td class="td-pri" style="text-align: center">11</td>
                    <td class="td-pri">Pasien diperiksa golongan darah sebelum SC</td>
                    <td class="td-pri" style="text-align: center">
                        @if (isset($data['darah']) && $data['darah'] == 'Ya')
                            <input type="checkbox" checked/>
                        @else
                            <input type="checkbox" />
                        @endif
                    </td>
                    <td class="td-pri" style="text-align: center">
                        @if (isset($data['transui']) && $data['transui'] == 'Tidak')
                            <input type="checkbox" checked/>
                        @else
                            <input type="checkbox" />
                        @endif
                    </td>
                    <td style="text-align: center" class="td-pri">
                        {{isset($data['hb']) ? $data['hb'] : ''}}
                    </td>
                </tr>

                <tr>
                    <td class="td-pri" style="text-align: center">12</td>
                    <td class="td-pri">Pasien diperiksa urinalis sebelum tindakan SC</td>
                    <td class="td-pri" style="text-align: center">
                        @if (isset($data['urin']) && $data['urin'] == 'Ya')
                            <input type="checkbox" checked/>
                        @else
                            <input type="checkbox" />
                        @endif
                    </td>
                    <td class="td-pri" style="text-align: center">
                        @if (isset($data['lis']) && $data['lis'] == 'Tidak')
                            <input type="checkbox" checked/>
                        @else
                            <input type="checkbox" />
                        @endif
                    </td>
                    <td style="text-align: center" class="td-pri">
                        {{isset($data['nalis']) ? $data['nalis'] : ''}}
                    </td>
                </tr>

                <tr>
                    <td class="td-pri" style="text-align: center">13</td>
                    <td class="td-pri">Pasien memiliki data USG sebelum SC</td>
                    <td class="td-pri" style="text-align: center">
                        @if (isset($data['ush']) && $data['ush'] == 'Ya')
                            <input type="checkbox" checked/>
                        @else
                            <input type="checkbox" />
                        @endif
                    </td>
                    <td class="td-pri" style="text-align: center">
                        @if (isset($data['ugs']) && $data['ugs'] == 'Tidak')
                            <input type="checkbox" checked/>
                        @else
                            <input type="checkbox" />
                        @endif
                    </td>
                    <td style="text-align: center" class="td-pri">
                        {{isset($data['ags']) ? $data['nalis'] : ''}}
                    </td>
                </tr>

                <tr>
                    <td class="td-pri" style="text-align: center">14</td>
                    <td class="td-pri">Pasien memiliki data laboratorium HIV sebelum SC</td>
                    <td class="td-pri" style="text-align: center">
                        @if (isset($data['hiv']) && $data['hiv'] == 'Ya')
                            <input type="checkbox" checked/>
                        @else
                            <input type="checkbox" />
                        @endif
                    </td>
                    <td class="td-pri" style="text-align: center">
                        @if (isset($data['hip']) && $data['hip'] == 'Tidak')
                            <input type="checkbox" checked/>
                        @else
                            <input type="checkbox" />
                        @endif
                    </td>
                    <td style="text-align: center" class="td-pri">
                        {{isset($data['hipe']) ? $data['hipe'] : ''}}
                    </td>
                </tr>
                <tr>
                    <td class="td-pri" style="text-align: center">15</td>
                    <td class="td-pri">Pasien memiliki data laboratorium Hepatitis sebelum SC</td>
                    <td class="td-pri" style="text-align: center">
                        @if (isset($data['lab']) && $data['lab'] == 'Ya')
                            <input type="checkbox" checked/>
                        @else
                            <input type="checkbox" />
                        @endif
                    </td>
                    <td class="td-pri" style="text-align: center">
                        @if (isset($data['bal']) && $data['bal'] == 'Tidak')
                            <input type="checkbox" checked/>
                        @else
                            <input type="checkbox" />
                        @endif
                    </td>
                    <td style="text-align: center" class="td-pri">
                        {{isset($data['hepati']) ? $data['hepati'] : ''}}
                    </td>
                </tr>
                <tr>
                    <td class="td-pri" style="text-align: center">16</td>
                    <td class="td-pri">Asesmen persalinan pasien menggunakan partograf ditulis lengkap sebelum SC</td>
                    <td class="td-pri" style="text-align: center">
                        @if (isset($data['parto']) && $data['parto'] == 'Ya')
                            <input type="checkbox" checked/>
                        @else
                            <input type="checkbox" />
                        @endif
                    </td>
                    <td class="td-pri" style="text-align: center">
                        @if (isset($data['graf']) && $data['graf'] == 'Tidak')
                            <input type="checkbox" checked/>
                        @else
                            <input type="checkbox" />
                        @endif
                    </td>
                    <td style="text-align: center" class="td-pri">
                        {{isset($data['ases']) ? $data['ases'] : ''}}
                    </td>
                </tr>

                <tr>
                    <td class="td-pri" style="text-align: center">17</td>
                    <td class="td-pri">Pasien dilakukan SC sesuai dengan indikasi :</td>
                    <td class="td-pri" style="text-align: center">
                        @if (isset($data['indi']) && $data['indi'] == 'Ya')
                            <input type="checkbox" checked/>
                        @else
                            <input type="checkbox" />
                        @endif
                    </td>
                    <td class="td-pri" style="text-align: center">
                        @if (isset($data['svi']) && $data['svi'] == 'Tidak')
                            <input type="checkbox" checked/>
                        @else
                            <input type="checkbox" />
                        @endif
                    </td>
                    <td style="text-align: center" class="td-pri">
                        {{isset($data['dsc']) ? $data['dsc'] : ''}}
                    </td>
                </tr>
                <tr>
                    <td class="td-pri" style="text-align: center">A</td>
                    <td class="td-pri">PEB</td>
                    <td class="td-pri" style="text-align: center">
                        @if (isset($data['peb']) && $data['peb'] == 'Ya')
                            <input type="checkbox" checked/>
                        @else
                            <input type="checkbox" />
                        @endif
                    </td>
                    <td class="td-pri" style="text-align: center">
                        @if (isset($data['bep']) && $data['bep'] == 'Tidak')
                            <input type="checkbox" checked/>
                        @else
                            <input type="checkbox" />
                        @endif
                    </td>
                    <td style="text-align: center" class="td-pri">
                        {{isset($data['ebp']) ? $data['ebp'] : ''}}
                    </td>
                </tr>
                <tr>
                    <td class="td-pri" style="text-align: center">A</td>
                    <td class="td-pri">PEB</td>
                    <td class="td-pri" style="text-align: center">
                        @if (isset($data['peb']) && $data['peb'] == 'Ya')
                            <input type="checkbox" checked/>
                        @else
                            <input type="checkbox" />
                        @endif
                    </td>
                    <td class="td-pri" style="text-align: center">
                        @if (isset($data['bep']) && $data['bep'] == 'Tidak')
                            <input type="checkbox" checked/>
                        @else
                            <input type="checkbox" />
                        @endif
                    </td>
                    <td style="text-align: center" class="td-pri">
                        {{isset($data['ebp']) ? $data['ebp'] : ''}}
                    </td>
                </tr>
            </tbody>
        </table>

    </section>
</body>

</html>
