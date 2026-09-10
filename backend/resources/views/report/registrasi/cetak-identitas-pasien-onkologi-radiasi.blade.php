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

        .center {
            text-align: center;
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

        .text {
            font-size: 10pt;
        }

        .text-2 {
            font-size: 9pt;
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

        .border-2 {
            border-left: 1px #444444 solid;
            border-right: 1px #444444 solid;
        }

        .border-3 {
            border-left: 1px #444444 solid;
            border-right: 1px #444444 solid;
            border-bottom: 1px #444444 solid;
        }

        .no-border {
            border: none;
        }

        body {
            font-family: Tahoma, sans-serif;
        }

        .bg-gray {
            background-color: #DCDCDC;
        }
    </style>
</head>

<body>
    <table width="100%" cellspacing="0" border="1" cellpadding="0" style="margin-top: -30px">
        <tr>
            <td colspan="2" class="bg-gray" style="text-align: right">
                <font class="text">
                    RM 14Q/RDT/00
                </font>
            </td>
        </tr>
        <tr>
            <th>
                <img src="{{ 'img/logo-rs.png' }}" width="90px">
            </th>
            <th width="90%">
                <table width="100%" style="position:relative" border="0">
                    <tr>
                        <td rowspan="2" class="label-strong center" style="vertical-align: middle;">
                            <font style="white-space: pre-line">FORMULIR IDENTITAS DAN INFORMASI
                                TENTANG PASIEN
                                UNIT PELAYANAN ONKOLOGI RADIASI
                            </font>
                        </td>
                    </tr>
                </table>
            </th>
        </tr>
    </table>
    <table width="100%" cellspacing="0" border="1" cellpadding="4">
        <tr>
            <td width="70%">
                <table width="100%" cellspacing="0" border="0" cellpadding="5">
                    <tr>
                        <td width="30%">
                            <font class="text">Nama Pasien</font>
                        </td>
                        <td width="3%">
                            <font class="text">
                                :
                            </font>
                        </td>
                        <td width="67%">
                            <font class="text">
                                {{ $dataReport['data']->namapasien }}
                            </font>
                        </td>
                    </tr>
                    <tr>
                        <td width="30%">
                            <font class="text">Nomor Rekam Medis</font>
                        </td>
                        <td width="3%">
                            <font class="text">
                                :
                            </font>
                        </td>
                        <td width="67%">
                            <font class="text">
                                {{ $dataReport['data']->nocm }}
                            </font>
                        </td>
                    </tr>
                    <tr>
                        <td width="30%">
                            <font class="text">Nomor Register</font>
                        </td>
                        <td width="3%">
                            <font class="text">
                                :
                            </font>
                        </td>
                        <td width="67%">
                            <font class="text">
                                {{ $dataReport['data']->noregistrasi }}
                            </font>
                        </td>
                    </tr>
                    <tr>
                        <td width="30%">
                            <font class="text">Jenis Kelamin</font>
                        </td>
                        <td width="3%">
                            <font class="text">
                                :
                            </font>
                        </td>
                        <td width="67%">
                            <font class="text">
                                {{ $dataReport['data']->jeniskelamin }}
                            </font>
                        </td>
                    </tr>
                    <tr>
                        <td width="30%">
                            <font class="text">Tanggal Lahir</font>
                        </td>
                        <td width="3%">
                            <font class="text">
                                :
                            </font>
                        </td>
                        <td width="67%">
                            <font class="text">
                                {{ $dataReport['data']->tanggal_lahir }}
                            </font>
                        </td>
                    </tr>
                    <tr>
                        <td width="30%">
                            <font class="text">Umur</font>
                        </td>
                        <td width="3%">
                            <font class="text">
                                :
                            </font>
                        </td>
                        <td width="67%">
                            <font class="text">
                                {{ $dataReport['data']->umur }}
                            </font>
                        </td>
                    </tr>
                    <tr>
                        <td width="30%">
                            <font class="text">Tanggal Masuk</font>
                        </td>
                        <td width="3%">
                            <font class="text">
                                :
                            </font>
                        </td>
                        <td width="67%">
                            <font class="text">
                                {{ $dataReport['data']->tglregistrasi }}
                            </font>
                        </td>
                    </tr>
                </table>
            </td>
            <td width="30%" class="center" style="vertical-align: middle">
                @php
                    $norm = $dataReport['data']->nocm;
                    $imagePath = 'foto_pasien/' . $dataReport['data']->id . '/' . $dataReport['data']->filename;
                    $imageUrl = storage_path('app/public/' . $imagePath);
                    $isImageExist = file_exists($imageUrl);
                    set_time_limit(120);

                @endphp
                @if($isImageExist)
                    <img src="{{ $imageUrl }}" style="max-width:100%">
                @endif
            </td>
        </tr>
    </table>
    <table width="100%" cellspacing="0" border="0" cellpadding="4">
        <tr class="border-2">
            <td colspan="3">
                <font style="font-weight: bold" class="text">INFORMASI UMUM</font>
            </td>
        </tr>
        <tr class="border-2">
            <td width="20%">
                <font class="text-2">
                    Alamat
                </font>
                <br>
                <font class="text-2">
                   <i>(sesuai identitas)</i>
                </font>
            </td>
            <td width="1%">
                <font class="text-2">
                 :
                </font>
            </td>
            <td width="79%">
                <font class="text-2">
                 {{ $dataReport['data']->alamatlengkap }}
                </font>
            </td>
        </tr>
        <tr class="border-2">
            <td width="20%">
                <font class="text-2">
                    Alamat
                </font>
            </td>
            <td width="1%">
                <font class="text-2">
                 :
                </font>
            </td>
            <td width="79%">
                <font class="text-2">
                 {{ $dataReport['data']->alamatlengkap }}
                </font>
            </td>
        </tr>
        <tr class="border-2">
            <td width="20%">
                <font class="text-2">
                    Pekerjaan
                </font>
            </td>
            <td width="1%">
                <font class="text-2">
                 :
                </font>
            </td>
            <td width="79%">
                <font class="text-2">
                 {{ $dataReport['data']->pekerjaan }}
                </font>
            </td>
        </tr>
        <tr class="border-2">
            <td width="20%">
                <font class="text-2">
                    Dokter Pengirim
                </font>
            </td>
            <td width="1%">
                <font class="text-2">
                 :
                </font>
            </td>
            <td width="79%">
            </td>
        </tr>
        <tr class="border-2">
            <td width="20%">
                <font class="text-2">
                    RS/Klinik Pengirim
                </font>
            </td>
            <td width="1%">
                <font class="text-2">
                 :
                </font>
            </td>
            <td width="79%">
            </td>
        </tr>
        <tr class="border-2">
            <td width="20%">
                <font class="text-2">
                    Dokter Radioterapi
                </font>
            </td>
            <td width="1%">
                <font class="text-2">
                 :
                </font>
            </td>
            <td width="79%">
                <br>
            </td>
        </tr>
    </table>
    <table width="100%" cellspacing="0" border="0" cellpadding="4">
        <tr class="border-2">
            <td colspan="3">
                <font style="font-weight: bold" class="text">INFORMASI PENYAKIT</font>
            </td>
        </tr>
        <tr class="border-2">
            <td width="30%">
                <font class="text">
                    DIAGNOSIS
                </font>
            </td>
            <td width="1%">
                <font class="text">
                 :
                </font>
            </td>
            <td width="69%">
            </td>
        </tr>
        <tr class="border-2">
            <td width="30%">
                <font class="text">
                    STADIUN/TNM
                </font>
            </td>
            <td width="1%">
                <font class="text">
                 :
                </font>
            </td>
            <td width="69%">
                <br><br><br><br><br><br><br>
            </td>
        </tr>
        <tr class="border-2">
            <td width="30%">
                <font class="text">
                    PATOLOGI ANATOMI
                </font>
            </td>
            <td width="1%">
                <font class="text">
                 :
                </font>
            </td>
            <td width="69%">
                <br><br><br><br><br><br>
            </td>
        </tr>
        <tr class="border-2">
            <td width="30%">
                <font class="text">
                </font>
            </td>
            <td width="1%">
                <font class="text">
                </font>
            </td>
            <td width="69%">
                <br><br><br>
            </td>
        </tr>
        <tr class="border-2">
            <td width="30%">
                <font class="text">
                    Kode ICD-10
                </font>
            </td>
            <td width="1%">
                <font class="text">
                    :
                </font>
            </td>
            <td width="69%">
            </td>
        </tr>
        <tr class="border-2">
            <td width="30%">
                <font class="text">
                    Kode ICD-0
                </font>
            </td>
            <td width="1%">
                <font class="text">
                    :
                </font>
            </td>
            <td width="69%">
            </td>
        </tr>
        <tr class="border-2">
            <td width="30%">
                <font class="text">
                    Kode ICD-9CM
                </font>
            </td>
            <td width="1%">
                <font class="text">
                    :
                </font>
            </td>
            <td width="69%">
            </td>
        </tr>
        <tr class="border-2">
            <td width="30%">
                <font class="text">
                    Tanggal Keluar / Meninggal
                </font>
            </td>
            <td width="1%">
                <font class="text">
                    :
                </font>
            </td>
            <td width="69%">
                <br><br>
            </td>
        </tr>
    </table>
    <table width="100%" cellspacing="0" border="1" cellpadding="4">

    </table>
</body>

</html>
