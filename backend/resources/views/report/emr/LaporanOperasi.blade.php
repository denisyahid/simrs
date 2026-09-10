<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>EMR - Laporan Operasi</title>
    <style>
        @page {
            size: A4;
        }

        .page-break {
            page-break-after: always;
        }

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
</head>

<body class="A4" style="font-family:DejaVu Sans, sans-serif;;height: auto" onload="window.print()">
    @foreach ($data as $key => $d)
        @if ($key > 0)
            <div class="page-break"></div>
        @endif
        <section class="sheet padding-10mm" style="font-family:DejaVu Sans, sans-serif;;height: auto;overflow: hidden;">
            @php
                $tglv_pembuatan = isset($d['tglOperasi']) ? date('d-m-Y H:i', strtotime($d['tglOperasi'])) : '';
            @endphp
            <table width="100%" cellspacing="0" cellpadding="0" border="1">
                <tr>
                    <td width="60%" style="text-align:right" colspan=2>
                        <table width="100%" cellspacing="0" cellpadding="0" border="0">
                            <tr class="bg-blue">
                                <td>
                                <td width="50%" style="text-align:left; font-size: 14px">RSMY</td>
                                <td width="50%" style="font-size: 14px; text-align: right;">RM 10</td>
                    </td>
                </tr>
            </table>

            <table width="100%" cellspacing="0" cellpadding="0" border="0">
                <tr>
                    <td width="10%" style="padding: 5px; border-right: 1px solid black;">
                        <img src="{{ 'img/logo-rs.png' }}" style="width: 90px;">
                    </td>
                    <td style="text-align: center; border-right: 1px solid black;">
                        <b>
                            <span style="font-size: 16px">
                                LAPORAN OPERASI<br>
                                <span>Halaman Ke-{{ $d['index_tabs'] }}</span>
                            </span>
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
                <tr class="font">
                    <td colspan="2" style="border-top: 1px solid black; border-right: 1px solid black;">
                        <span>
                            &nbsp;Ruangan :
                            {{ isset($d['kamaroperasi']['namakamarok']) ? $d['kamaroperasi']['namakamarok'] : '' }}
                        </span>
                    </td>
                    <td style="border-top: 1px solid black;">
                        <span>&nbsp; Tanggal & Jam : {{ isset($tglv_pembuatan) ? $tglv_pembuatan : '' }}</span>
                    </td>
                </tr>
            </table>
            <table width="100%" cellspacing="0" cellpadding="2" border="1" class="font">
                <colgroup>
                    <col style="width: 25%;">
                    <col style="width: 25%;">
                    <col style="width: 25%;">
                    <col style="width: 25%;">
                </colgroup>
                <tr>
                    <td colspan="2" class="font">
                        <span>&nbsp;Nama operator : </span>
                        <br>
                        {{ isset($d['dokterBedah']['label']) ? $d['dokterBedah']['label'] : '' }}
                    </td>
                    <td colspan="2" class="font">
                        <span>&nbsp;Nama asisten : </span>
                        <br>
                        {{ isset($d['asistenBedahI']['label']) ? $d['asistenBedahI']['label'] : '' }}
                    </td>
                </tr>
                <tr>
                    <td colspan="2" class="font">
                        <span>&nbsp;Nama ahli anestesi : </span>
                        <br>
                        {{ isset($d['dokterAnestesi']['label']) ? $d['dokterAnestesi']['label'] : '' }}
                    </td>
                    <td colspan="2" class="font">
                        <span>&nbsp;Nama asisten : </span>
                        <br>
                        {{ isset($d['asistenAnestesi']['label']) ? $d['asistenAnestesi']['label'] : '' }}
                    </td>
                </tr>
                <tr>
                    <td colspan="1">
                        <span>&nbsp;Jenis anestesi</span>
                    </td>
                    <td colspan="3">
                        <input type="checkbox" {{ isset($d['Umum']) && $d['Umum'] == 'Umum' ? 'checked' : '' }} />
                        <span>Umum</span>
                        <input type="checkbox"
                            {{ isset($d['Spinal']) && $d['Spinal'] == 'Spinal' ? 'checked' : '' }} />
                        <span>Spinal</span>
                        <input type="checkbox"
                            {{ isset($d['Epidural']) && $d['Epidural'] == 'Epidural' ? 'checked' : '' }} />
                        <span>Epidural</span>
                        <input type="checkbox" {{ isset($d['BSP']) && $d['BSP'] == 'BSP' ? 'checked' : '' }} />
                        <span>BSP</span>
                        <input type="checkbox" {{ isset($d['CSE']) && $d['CSE'] == 'CSE' ? 'checked' : '' }} />
                        <span>CSE</span>
                        <input type="checkbox" {{ isset($d['Lokal']) && $d['Lokal'] == 'Lokal' ? 'checked' : '' }} />
                        <span>Lokal</span>
                    </td>
                </tr>
                <tr>
                    <td colspan="1">
                        <span>&nbsp;Golongan operasi</span>
                    </td>
                    <td colspan="3" style="justify-content: space-evenly">
                        <input type="checkbox"
                            {{ isset($d['Khusus']) && $d['Khusus'] == 'Khusus' ? 'checked' : '' }} />
                        <span>Khusus</span>
                        <input type="checkbox" {{ isset($d['Mayor']) && $d['Mayor'] == 'Mayor' ? 'checked' : '' }} />
                        <span>Mayor</span>
                        <input type="checkbox"
                            {{ isset($d['Medium']) && $d['Medium'] == 'Medium' ? 'checked' : '' }} />
                        <span>Medium</span>
                        <input type="checkbox" {{ isset($d['odc']) && $d['odc'] == 'ODC' ? 'checked' : '' }} />
                        <span>One Day Care</span>
                        <br>
                        <input type="checkbox" {{ isset($d['Minor']) && $d['Minor'] == 'Minor' ? 'checked' : '' }} />
                        <span>Minor</span>
                        <input type="checkbox"
                            {{ isset($d['Emergensi']) && $d['Emergensi'] == 'Emergensi' ? 'checked' : '' }} />
                        <span>Emergensi</span>
                        <input type="checkbox"
                            {{ isset($d['Elektif']) && $d['Elektif'] == 'Elektif' ? 'checked' : '' }} />
                        <span>Elektif</span>
                    </td>
                </tr>
                <tr>
                    <td colspan="1">
                        <span>&nbsp;Diagnosis pra bedah</span>
                    </td>
                    <td colspan="3">
                        {{ isset($d['DiagnosaPraBedah']) ? $d['DiagnosaPraBedah'] : '' }}
                    </td>
                </tr>
                <tr>
                    <td colspan="1">
                        <span>&nbsp;Diagnosis pasca bedah</span>
                    </td>
                    <td colspan="3">
                        {{ isset($d['DiagnosaPascaBedah']) ? $d['DiagnosaPascaBedah'] : '' }}
                    </td>
                </tr>
                <tr>
                    <td colspan="1">
                        <span>&nbsp;Indikasi Operasi</span>
                    </td>
                    <td colspan="3">
                        {{ isset($d['indikasiOperasi']) ? $d['indikasiOperasi'] : '' }}
                    </td>
                </tr>
                <tr>
                    <td colspan="1">
                        <span>&nbsp;Nama Operasi</span>
                    </td>
                    <td colspan="3">
                        {{ isset($d['namaOperasi']) ? $d['namaOperasi'] : '' }}
                    </td>
                </tr>
                <tr>
                    <td colspan="1">
                        <span>&nbsp;Jaringan yang dieksisi</span>
                    </td>
                    <td colspan="3">
                        <span>&nbsp;Pemeriksaan PA :</span>
                        <input type="checkbox" {{ isset($d['yaPA']) && $d['yaPA'] == 'Ya' ? 'checked' : '' }} />
                        <span>Ya</span>
                        <input type="checkbox"
                            {{ isset($d['tidakPA']) && $d['tidakPA'] == 'Tidak' ? 'checked' : '' }} />
                        <span>Ya</span>
                        <br>
                        <span>&nbsp;Kultur :</span>
                        <input type="checkbox"
                            {{ isset($d['yaKultur']) && $d['yaKultur'] == 'Ya' ? 'checked' : '' }} />
                        <span>Ya</span>
                        <input type="checkbox"
                            {{ isset($d['tidakKultur']) && $d['tidakKultur'] == 'Tidak' ? 'checked' : '' }} />
                        <span>Ya</span>
                    </td>
                </tr>
                <tr>
                    <td colspan="1" class="tc">
                        <span>Tanggal Operasi</span>
                        <br>
                        {{ isset($d['tgloperasi']) ? date('d-m-Y', strtotime($d['tgloperasi'])) : '' }}
                    </td>
                    <td colspan="1" class="tc">
                        <span>Jam operasi dimulai</span>
                        <br>
                        {{ isset($d['jamStartOperasi']) ? date('H:i', strtotime($d['jamStartOperasi'])) : '' }}
                    </td>
                    <td colspan="1" class="tc">
                        <span>Jam operasi selesai</span>
                        <br>
                        {{ isset($d['jamEndOperasi']) ? date('H:i', strtotime($d['jamEndOperasi'])) : '' }}
                    </td>
                    <td colspan="1" class="tc">
                        <span>Durasi operasi</span>
                        <br>
                        {{ isset($d['lamaOperasi']) ? $d['lamaOperasi'] : '' }}
                    </td>
                </tr>
                <tr>
                    <td colspan="1">
                        <span>&nbsp;Komplikasi/penyulit operasi</span>
                    </td>
                    <td colspan="3">
                        {{ isset($d['penyulitOperasi']) ? $d['penyulitOperasi'] : '' }}
                    </td>
                </tr>
                <tr>
                    <td colspan="1">
                        <span>&nbsp;Jumlah perdarahan keluar</span>
                    </td>
                    <td colspan="1">
                        {{ isset($d['jumlahDarah']) ? $d['jumlahDarah'] : '' }}
                    </td>
                    <td colspan="1">
                        <span>&nbsp;Jumlah transfusi</span>
                    </td>
                    <td colspan="1">
                        {{ isset($d['jumlahTransfusi']) ? $d['jumlahTransfusi'] : '' }}
                    </td>
                </tr>
                <tr>
                    <td colspan="1">
                        <span>&nbsp;Pemakaian Implant</span>
                    </td>
                    <td colspan="1">
                        {{ isset($d['pemakaianImplant']) ? $d['pemakaianImplant'] : '' }}
                    </td>
                    <td colspan="1">
                        <span>&nbsp;Catatan Khusus</span>
                    </td>
                    <td colspan="1">
                        {{ isset($d['catatanKhususImplant']) ? $d['catatanKhususImplant'] : '' }}
                    </td>
                </tr>
                <tr>
                    <td colspan="1">
                        <span>&nbsp;Perawatan pasca operasi</span>
                    </td>
                    <td colspan="3">
                        <input type="checkbox"
                            {{ isset($d['RuanganPasca']) && $d['RuanganPasca'] == 'Ruangan' ? 'checked' : '' }} />
                        <span>Ruangan :</span>
                        <input type="checkbox"
                            {{ isset($d['PICUNICU']) && $d['PICUNICU'] == 'PICU/NICU' ? 'checked' : '' }} />
                        <span>PICU/NICU</span>
                        <input type="checkbox" {{ isset($d['HCU']) && $d['HCU'] == 'HCU' ? 'checked' : '' }} />
                        <span>HCU</span>
                        <input type="checkbox" {{ isset($d['RTI']) && $d['RTI'] == 'RTI' ? 'checked' : '' }} />
                        <span>RTI</span>
                    </td>
                </tr>
                <tr>
                    <td colspan="4">
                        &nbsp;Prosedur operasi yang dilakukan dan rincian temuan
                        <br>
                        {{ isset($d['laporan']) ? $d['laporan'] : '' }}
                        <br><br><br>
                    </td>
                </tr>
            </table>
            <table width="100%" cellspacing="0" cellpadding="2" border="1" class="font">
                <tr>
                    <td width="70%">
                        &nbsp;Nomor pendaftaran dari alat yang dipasang :
                        <br>
                        {{ isset($d['nomorPendaftaran']) ? $d['nomorPendaftaran'] : '' }}
                    </td>
                    <td width="30%" class="tc">
                        &nbsp;Operator,
                        <br>
                        <img src="data:image/png;base64, {!! $qrcode !!}">
                        <br>
                        <p style="font-size: 12px;">
                            @isset($d['dokterBedah']['label'])
                                {{ $d['dokterBedah']['label'] }}
                            @endisset
                        </p>
                    </td>
                </tr>
            </table>
            <table width="100%" style="border-collapse: collapse;border: none">
                @foreach ($d['fotopendukung'] as $index => $d)
                    @if ($index == 0)
                        <tr>
                            <td style="border: none">
                                <div class="page-break"></div>
                            </td>
                        </tr>
                    @endif
                    @php
                        $url = storage_path('app/public/' . $d['file']);
                    @endphp
                    <tr>
                        <td style="border: none;border-top: 1px solid black">
                            <span style="font-weight: bold">{{ $d['nama'] }}</span><br>
                            <img src="{{ $url }}" width="100%" height="33%">
                        </td>
                    </tr>
                    @if (($index + 1) % 3 == 0)
                        <tr>
                            <td style="border: none">
                                <div class="page-break"></div>
                            </td>
                        </tr>
                    @endif
                @endforeach
            </table>
        </section>
    @endforeach
</body>

</html>
