<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Cetak Identitas Pasien</title>
    <meta name="robots" content="noindex">
    <meta name="googlebot" content="noindex">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>
    <style>
        :root {
            font-family: 'Tahoma', 'sans-serif';
        }

        font {
            font-family: 'Tahoma', 'sans-serif';
        }

        @font-face {
            font-family: 'Tahoma', 'sans-serif';
        }

        .table-bordered tr td {
            border: 1px solid #444444;
        }

        .label-strong {
            text-align: center;
            font-size: 13.4pt;
        }

        .label-normal {
            font-weight: 400;
            text-align: left;
            font-size: 13.4pt;
        }

        .label-right {
            text-align: right;
            font-size: 13.4pt;
            font-weight: normal;
        }

        .label-left {
            text-align: left;
            font-size: 13.4pt;
            font-weight: normal;
        }

        body {
            font-family: Tahoma, sans-serif;
        }
    </style>
</head>

<body>
    <table width="100%" cellspacing="0" cellpadding="0" style="margin-top: -30px">
        <tr>
            <th>
                <img src="{{ 'img/logo-rs.png' }}" width="70px">
            </th>
            <th width="90%">
                <table width="100%" style="position:relative">
                    <tr>
                        <td class="label-strong" style="text-align:left; margin-left: 20px;">
                            <font>{{ strtoupper($profile->namalengkap) }}</font>
                        </td>
                    </tr>
                    <tr>
                        <td class="label-normal" style="text-align:left; margin-left: 20px;">
                            <font style="font-size: 10pt;"><b>{{ strtoupper($profile->alamatlengkap) }}</b> <br>
                                {{ $profile->alamatemail }}</font>
                        </td>
                    </tr>
                </table>
            </th>
        </tr>
    </table>
    <hr style="margin: 0px">
    <table width="100%" cellspacing="0" cellpadding="0">
        <tr>
            <th width="90%">
                <table width="100%" style="position:relative">
                    <tr>
                        <td class="label-strong" style="text-align:center" colspan="2">
                            <font>Lembar Identitas Pasien</font>
                        </td>
                    </tr>
                    <tr>
                        <td class="label-strong" style="text-align:center" colspan="2" height="20"></td>
                    </tr>
                    @php
                        $norm = $dataReport['data']->nocm;
                        $imagePath = 'foto_pasien/' . $dataReport['data']->id . '/' . $dataReport['data']->filename;
                        $imageUrl = storage_path('app/public/' . $imagePath);
                        $isImageExist = file_exists($imageUrl);
                        set_time_limit(120);

                    @endphp
                    <tr>
                        <td style="text-align: start" width="1%">
                            {{-- <img src="{{ $imageUrl }}" width="100px" height="50px;"> --}}
                            @if($isImageExist)
                                <img src="{{ $imageUrl }}" style="width: 120px; height: 100px;">
                            @endif
                        </td>
                        <td class="label-strong" style="text-align:start" width="20%">
                            <img src='https://barcode.tec-it.com/barcode.ashx?data={{ $norm }}&code=Code39&dpi=96&dataseparator='
                                style=" height: 40px;width: 200px;-webkit-user-select: none;cursor:pointer" />
                        </td>
                    </tr>
                    <tr>
                        <td class="label-strong" style="text-align:left; font-size: 11pt;" width="80%">
                            <font>Biodata Pasien</font>
                        </td>
                        <td class="label-strong" style="text-align:center" width="20%"></td>
                    </tr>
                </table>
            </th>
        </tr>
    </table>
    <hr style="margin: 0px">
    <table width="100%" cellspacing="0" cellpadding="0">
        <tr>
            <th width="90%">
                <table width="100%" style="position:relative">
                    <tr>
                        <td width="20%">
                            <font style="font-size: 11pt; font-weight: normal;">No. Rekam Medis</font>
                        </td>
                        <td width="5%">
                            <font style="font-size: 11pt; font-weight: normal;">:</font>
                        </td>
                        <td width="25%">
                            <font style="font-size: 11pt; font-weight: normal;">{{ $dataReport['data']->nocm }}</font>
                        </td>
                        <td width="15%">
                            <font style="font-size: 11pt; font-weight: normal;">Nama Pasien</font>
                        </td>
                        <td width="5%">
                            <font style="font-size: 11pt; font-weight: normal;">:</font>
                        </td>
                        <td width="30%">
                            <font style="font-size: 11pt; font-weight: normal;">{{ $dataReport['data']->namapasien }}
                            </font>
                        </td>
                    </tr>
                    <tr>
                        <td width="20%">
                            <font style="font-size: 11pt; font-weight: normal;">Tempat Lahir</font>
                        </td>
                        <td width="5%">
                            <font style="font-size: 11pt; font-weight: normal;">:</font>
                        </td>
                        <td width="25%">
                            <font style="font-size: 11pt; font-weight: normal;">{{ $dataReport['data']->tempatlahir }}
                            </font>
                        </td>
                        <td width="15%">
                            <font style="font-size: 11pt; font-weight: normal;">Tgl.Lahir/Umur</font>
                        </td>
                        <td width="5%">
                            <font style="font-size: 11pt; font-weight: normal;">:</font>
                        </td>
                        <td width="30%">
                            <font style="font-size: 11pt; font-weight: normal;">{{ $dataReport['data']->tanggal_lahir }}
                                / {{ $dataReport['data']->umur }}</font>
                        </td>
                    </tr>
                    <tr>
                        <td width="20%">
                            <font style="font-size: 11pt; font-weight: normal;">Jenis Kelamin</font>
                        </td>
                        <td width="5%">
                            <font style="font-size: 11pt; font-weight: normal;">:</font>
                        </td>
                        <td width="25%">
                            <font style="font-size: 11pt; font-weight: normal;">{{ $dataReport['data']->jeniskelamin }}
                            </font>
                        </td>
                        <td width="15%">
                            <font style="font-size: 11pt; font-weight: normal;">Status Perkawinan</font>
                        </td>
                        <td width="5%">
                            <font style="font-size: 11pt; font-weight: normal;">:</font>
                        </td>
                        <td width="30%">
                            <font style="font-size: 11pt; font-weight: normal;">
                                {{ $dataReport['data']->statusperkawinan }}</font>
                        </td>
                    </tr>
                    <tr>
                        <td width="20%">
                            <font style="font-size: 11pt; font-weight: normal;">Agama</font>
                        </td>
                        <td width="5%">
                            <font style="font-size: 11pt; font-weight: normal;">:</font>
                        </td>
                        <td width="25%">
                            <font style="font-size: 11pt; font-weight: normal;">{{ $dataReport['data']->agama }}
                            </font>
                        </td>
                        <td width="15%">
                            <font style="font-size: 11pt; font-weight: normal;">Kewarganegaraan</font>
                        </td>
                        <td width="5%">
                            <font style="font-size: 11pt; font-weight: normal;">:</font>
                        </td>
                        <td width="30%">
                            <font style="font-size: 11pt; font-weight: normal;">{{ $dataReport['data']->namanegara }}
                                - {{ $dataReport['data']->kebangsaan }}</font>
                        </td>
                    </tr>
                    <tr>
                        <td width="20%">
                            <font style="font-size: 11pt; font-weight: normal;">Pekerjaan</font>
                        </td>
                        <td width="5%">
                            <font style="font-size: 11pt; font-weight: normal;">:</font>
                        </td>
                        <td width="25%">
                            <font style="font-size: 11pt; font-weight: normal;">{{ $dataReport['data']->pekerjaan }}
                            </font>
                        </td>
                        <td width="15%">
                            <font style="font-size: 11pt; font-weight: normal;">Pendidikan</font>
                        </td>
                        <td width="5%">
                            <font style="font-size: 11pt; font-weight: normal;">:</font>
                        </td>
                        <td width="30%">
                            <font style="font-size: 11pt; font-weight: normal;">{{ $dataReport['data']->pendidikan }}
                            </font>
                        </td>
                    </tr>
                    <tr>
                        <td width="20%" colspan="6" height="20"></td>
                    </tr>
                    <tr>
                        <td width="20%" colspan="6">
                            <font style="font-size: 11pt; font-weight: bold;">Alamat Pasien</font>
                        </td>
                    </tr>
                </table>
            </th>
        </tr>
    </table>
    <hr style="margin: 0px">
    <table width="100%" cellspacing="0" cellpadding="0">
        <tr>
            <th width="90%">
                <table width="100%" style="position:relative">
                    <tr>
                        <td width="20%">
                            <font style="font-size: 11pt; font-weight: normal;">Alamat</font>
                        </td>
                        <td width="5%">
                            <font style="font-size: 11pt; font-weight: normal;">:</font>
                        </td>
                        <td width="25%" colspan="4">
                            <font style="font-size: 11pt; font-weight: normal;">
                                {{ $dataReport['data']->alamatlengkap }}</font>
                        </td>
                    </tr>
                    <tr>
                        <td width="20%">
                            <font style="font-size: 11pt; font-weight: normal;">Propinsi</font>
                        </td>
                        <td width="5%">
                            <font style="font-size: 11pt; font-weight: normal;">:</font>
                        </td>
                        <td width="25%">
                            <font style="font-size: 11pt; font-weight: normal;">
                                {{ $dataReport['data']->namapropinsi }}</font>
                        </td>
                        <td width="15%">
                            <font style="font-size: 11pt; font-weight: normal;">Kodya/Kab</font>
                        </td>
                        <td width="5%">
                            <font style="font-size: 11pt; font-weight: normal;">:</font>
                        </td>
                        <td width="30%">
                            <font style="font-size: 11pt; font-weight: normal;">
                                {{ $dataReport['data']->namakotakabupaten }}</font>
                        </td>
                    </tr>
                    <tr>
                        <td width="20%">
                            <font style="font-size: 11pt; font-weight: normal;">Kecamatan</font>
                        </td>
                        <td width="5%">
                            <font style="font-size: 11pt; font-weight: normal;">:</font>
                        </td>
                        <td width="25%">
                            <font style="font-size: 11pt; font-weight: normal;">
                                {{ $dataReport['data']->namakecamatan }}</font>
                        </td>
                        <td width="15%">
                            <font style="font-size: 11pt; font-weight: normal;">Kelurahan</font>
                        </td>
                        <td width="5%">
                            <font style="font-size: 11pt; font-weight: normal;">:</font>
                        </td>
                        <td width="30%">
                            <font style="font-size: 11pt; font-weight: normal;">
                                {{ $dataReport['data']->namadesakelurahan }}</font>
                        </td>
                    </tr>
                    <tr>
                        <td width="20%" colspan="6" height="20"></td>
                    </tr>
                    <tr>
                        <td width="20%" colspan="6">
                            <font style="font-size: 11pt; font-weight: bold;">Kontak Pasien</font>
                        </td>
                    </tr>
                </table>
            </th>
        </tr>
    </table>
    <hr style="margin: 0px">
    <table width="100%" cellspacing="0" cellpadding="0">
        <tr>
            <th width="90%">
                <table width="100%" style="position:relative">
                    <tr>
                        <td width="20%">
                            <font style="font-size: 11pt; font-weight: normal;">No. Telpon</font>
                        </td>
                        <td width="5%">
                            <font style="font-size: 11pt; font-weight: normal;">:</font>
                        </td>
                        <td width="25%" colspan="4">
                            <font style="font-size: 11pt; font-weight: normal;">{{ $dataReport['data']->nohp }}</font>
                        </td>
                    </tr>
                    <tr>
                        <td width="20%" colspan="6" height="20"></td>
                    </tr>
                    <tr>
                        <td width="20%" colspan="6">
                            <font style="font-size: 11pt; font-weight: bold;">Kunjungan Pertama</font>
                        </td>
                    </tr>
                </table>
            </th>
        </tr>
    </table>
    <hr style="margin: 0px">
    <table width="100%" cellspacing="0" cellpadding="0">
        <tr>
            <th width="90%">
                <table width="100%" style="position:relative">
                    <tr>
                        <td width="20%">
                            <font style="font-size: 11pt; font-weight: normal;">Tgl Masuk</font>
                        </td>
                        <td width="5%">
                            <font style="font-size: 11pt; font-weight: normal;">:</font>
                        </td>
                        <td width="25%">
                            <font style="font-size: 11pt; font-weight: normal;">
                                {{ $dataReport['data']->tglregistrasi }}</font>
                        </td>
                        <td width="15%">
                            <font style="font-size: 11pt; font-weight: normal;">Kelas</font>
                        </td>
                        <td width="5%">
                            <font style="font-size: 11pt; font-weight: normal;">:</font>
                        </td>
                        <td width="30%">
                            <font style="font-size: 11pt; font-weight: normal;">
                                {{ isset($dataReport['kelas']) ? $dataReport['kelas'] : 'NON KELAS' }}</font>
                        </td>
                    </tr>
                    <tr>
                        <td width="20%">
                            <font style="font-size: 11pt; font-weight: normal;">Cara Bayar</font>
                        </td>
                        <td width="5%">
                            <font style="font-size: 11pt; font-weight: normal;">:</font>
                        </td>
                        <td width="25%">
                            <font style="font-size: 11pt; font-weight: normal;">
                                {{ $dataReport['data']->kelompokpasien }}</font>
                        </td>
                        <td width="15%">
                            <font style="font-size: 11pt; font-weight: normal;">No. Kartu</font>
                        </td>
                        <td width="5%">
                            <font style="font-size: 11pt; font-weight: normal;">:</font>
                        </td>
                        <td width="30%">
                            <font style="font-size: 11pt; font-weight: normal;">{{ $dataReport['data']->nobpjs }}
                            </font>
                        </td>
                    </tr>
                    <tr>
                        <td width="20%">
                            <font style="font-size: 11pt; font-weight: normal;">Instalasi</font>
                        </td>
                        <td width="5%">
                            <font style="font-size: 11pt; font-weight: normal;">:</font>
                        </td>
                        <td width="25%">
                            <font style="font-size: 11pt; font-weight: normal;">{{ $dataReport['data']->namaruangan }}
                            </font>
                        </td>
                        <td width="15%">
                            <font style="font-size: 11pt; font-weight: normal;">Detail</font>
                        </td>
                        <td width="5%">
                            <font style="font-size: 11pt; font-weight: normal;">:</font>
                        </td>
                        <td width="30%">
                            <font style="font-size: 11pt; font-weight: normal;">{{ @$dataReport['statusbpjs'] }}
                            </font>
                        </td>
                    </tr>
                    <tr>
                        <td width="20%" colspan="6" height="20"></td>
                    </tr>
                    <tr>
                        <td width="20%" colspan="6">
                            <font style="font-size: 11pt; font-weight: bold;">Penanggung Jawab</font>
                        </td>
                    </tr>
                </table>
            </th>
        </tr>
    </table>
    <hr style="margin: 0px">
    <table width="100%" cellspacing="0" cellpadding="0">
        <tr>
            <th width="90%">
                <table width="100%" style="position:relative">
                    <tr>
                        <td width="20%">
                            <font style="font-size: 11pt; font-weight: normal;">Nama</font>
                        </td>
                        <td width="5%">
                            <font style="font-size: 11pt; font-weight: normal;">:</font>
                        </td>
                        <td width="25%" colspan="4">
                            <font style="font-size: 11pt; font-weight: normal;">
                                {{ $dataReport['data']->penanggungjawab }}</font>
                        </td>
                    </tr>
                    <tr>
                        <td width="20%">
                            <font style="font-size: 11pt; font-weight: normal;">Alamat</font>
                        </td>
                        <td width="5%">
                            <font style="font-size: 11pt; font-weight: normal;">:</font>
                        </td>
                        <td width="25%" colspan="4">
                            <font style="font-size: 11pt; font-weight: normal;">{{ $dataReport['data']->alamatrmh }}
                            </font>
                        </td>
                    </tr>
                    <tr>
                        <td width="20%">
                            <font style="font-size: 11pt; font-weight: normal;">No. Telp</font>
                        </td>
                        <td width="5%">
                            <font style="font-size: 11pt; font-weight: normal;">:</font>
                        </td>
                        <td width="25%" colspan="4">
                            <font style="font-size: 11pt; font-weight: normal;">
                                {{ $dataReport['data']->telponpenanggungjawab }}</font>
                        </td>
                    </tr>
                    <tr>
                        <td width="20%">
                            <font style="font-size: 11pt; font-weight: normal;">Hubungan</font>
                        </td>
                        <td width="5%">
                            <font style="font-size: 11pt; font-weight: normal;">:</font>
                        </td>
                        <td width="25%" colspan="4">
                            <font style="font-size: 11pt; font-weight: normal;">
                                {{ $dataReport['data']->hubungankeluarga }}</font>
                        </td>
                    </tr>
                </table>
            </th>
        </tr>
    </table>
</body>

</html>
