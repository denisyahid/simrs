<!DOCTYPE html>
<html lang="en">

<head>
    <title>
        Cetak Keterangan Lahir
    </title>
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
</head>
<style type="text/css" media="print">
    @media print {
        @page {
            size: auto;
            margin: 0;
            /* size: portrait; */
        }

        footer {
            display: none
        }

        header {
            display: none
        }

        /* body {
            -webkit-print-color-adjust: exact !important;
            background-image: url(asset('img/skl-lakik.png'));
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center;
            z-index: -1;
        } */

    }

    tr td {
        /*padding:2px 4px 2px 4px;*/
    }

    .borderss {
        border-bottom: 1px solid black;
    }

    body {
        font-family: Tahoma, Geneva, sans-serif;
        margin: 0;
        padding: 0;
        background-image: url({{ $background }});
        background-size: cover;
        background-repeat: no-repeat;
        background-position: center;
    }
</style>

<body style="margin: 0;">
    <table cellspacing="0" cellpadding="0" bgcolor="#FFFFFF" border="0" style="width:100%;border:0px; height: 10%">
        {{-- {{dd($dataLahir)}} --}}
        <tbody>
            <tr>
                <td
                    style="padding: 30px; padding-bottom: 0;text-align: left; background-image: url({{ $background }}); background-size: cover; background-repeat: no-repeat; background-position: center; bottom: -50px;">
                    <table width="100%" cellspacing="0" cellpadding="0" style="margin-top: -30px;">
                        {{-- <tr>
                            <img alt="" src="{{ $background }}" border="0"
                                style="width: 500px; z-index: 0;" alt="">
                        </tr> --}}
                        <tr>
                            <p style="text-align: center;">
                                <img alt="" src="img/logo-rs.png" border="0"
                                    style="width: 70px; text-align:center;" alt="">
                            </p>
                        </tr>
                        <tr>
                            <td class="label-strong" style="text-align:center; font-weight: bold;">
                                <font face="sans-serif" style="font-size: 13pt">
                                    <b>{!! strtoupper($profile->namalengkap) !!}</b>
                                </font>
                            </td>
                        </tr>
                        <tr>
                            <td class="label-normal" style="text-align:center; padding-top: 3px">
                                <font style="font-size: 11pt;" face="sans-serif"><b>{{ $profile->alamatlengkap }}</b>
                                </font>
                            </td>
                        </tr>
                        <tr>
                            <td class="label-normal" style="text-align:center; padding-top: 7px">
                                <font style="font-size: 11pt;" face="sans-serif">
                                    <b>
                                        <a href="mailto:{{ $profile->alamatemail }}"
                                            style="text-decoration: none; color:inherit">
                                            {{ $profile->alamatemail }}
                                        </a>
                                    </b>
                                </font>
                            </td>
                        </tr>
                        <tr>
                            <td class="label-normal" style="text-align:center; padding-top: 10px">
                                <font style="font-size: 24pt;" face="Tahoma">
                                    <b>
                                        <span style="text-decoration: none; color:inherit;">
                                            Surat Keterangan Kelahiran
                                        </span>
                                    </b>
                                </font>
                                <br>
                                <font style="font-weight: bold; font-size: 11px" face="sans-serif">
                                    <i>
                                        BIRTH STATEMENT
                                    </i>
                                </font>
                            </td>
                        </tr>
                        <tr>
                            <td class="label-normal" style="text-align:center;">
                                <font style="font-size: 13pt;" face="sans-serif">
                                    <b>
                                        {{ $dataLahir->noskl }}
                                    </b>
                                </font>
                            </td>
                        </tr>
                        </th>
                    </table>
                    <table cellspacing="0" cellpadding="0" border="0" width="100%" align="center"
                        style="margin-top: 10px;">
                        <tr>
                            <td class="label-normal" style="text-align:left;">
                                <font style="font-size: 9pt;font-weight: 400" face="sans-serif">
                                    <b>
                                        Yang bertanda tangan di bawah ini menerangkan dengan sebenarnya, bahwa :
                                    </b>
                                </font>
                                <br>
                                <font style="font-size: 9pt;font-weight: 400" face="sans-serif">
                                    <b>
                                        <i>
                                            The undersigned below here with truly state that :
                                        </i>
                                    </b>
                                </font>
                            </td>
                        </tr>
                    </table>
                    <table cellspacing="0" cellpadding="0" border="0" width="100%" align="center"
                        style="margin: 6px 0px 6px 20px;">
                        <tr>
                            <td height="5" width="27%">
                                <font style="font-size: 9pt;font-weight: 400" color="#sans-serif" face="Tahoma">
                                    <b>
                                        Hari /<i>Day</i>
                                    </b>
                                </font>
                            </td>
                            <td height="5" width="1%">
                                <font size="1">:</font>
                            </td>
                            <td height="5" width="72%">
                                <font style="font-size: 9pt;font-weight: 400" color="#sans-serif" face="Tahoma">
                                    <b>
                                        {{ $dataLahir->hariindo }}&nbsp;
                                        /<i>{{ \Carbon\Carbon::parse($dataLahir->tanggal)->locale('es_ES')->format('l') }}</i>
                                    </b>
                                </font>
                            </td>
                        </tr>
                        <tr>
                            <td height="5" width="27%">
                                <font style="font-size: 9pt;font-weight: 400" color="#sans-serif" face="Tahoma">
                                    <b>
                                        Tanggal /<i>Date</i>
                                    </b>
                                </font>
                            </td>
                            <td height="5" width="1%">
                                <font size="1">:</font>
                            </td>
                            <td height="5" width="72%">
                                <font style="font-size: 9pt;font-weight: 400" color="#sans-serif" face="Tahoma">
                                    <b>
                                        {{ $dataLahir->tglindo }}&nbsp;
                                        /<i>{{ \Carbon\Carbon::parse($dataLahir->tanggal)->locale('es_ES')->format('j F Y') }}</i>
                                    </b>
                                </font>
                            </td>
                        </tr>
                        <tr>
                            <td height="5" width="27%">
                                <font style="font-size: 9pt;font-weight: 400" color="#sans-serif" face="Tahoma">
                                    <b>
                                        Jam /<i>Time</i>
                                    </b>
                                </font>
                            </td>
                            <td height="5" width="1%">
                                <font size="1">:</font>
                            </td>
                            <td height="5" width="72%">
                                <font style="font-size: 9pt;font-weight: 400" color="#sans-serif" face="Tahoma">
                                    <b>
                                        {{ \Carbon\Carbon::parse($dataLahir->tanggal)->locale('id_ID')->format('H:i') }}&nbsp;
                                        /<i>{{ \Carbon\Carbon::parse($dataLahir->tanggal)->locale('es_ES')->format('h:i A') }}</i>
                                    </b>
                                </font>
                            </td>
                        </tr>
                    </table>
                    <table cellspacing="0" cellpadding="0" border="0" width="100%" align="center"
                        style="margin-top: 10px;">
                        <tr>
                            <td class="label-normal" style="text-align:left;">
                                <font style="font-size: 9pt;font-weight: 400" face="sans-serif">
                                    <b>
                                        Di Rumah Sakit Umum Daerah BALI MANDARA
                                    </b>
                                </font>
                                <br>
                                <font style="font-size: 9pt;font-weight: 400" face="sans-serif">
                                    <b>
                                        <i>
                                            In BALI MANDARA General Hospital
                                        </i>
                                    </b>
                                </font>
                            </td>
                        </tr>
                    </table>
                    <table cellspacing="0" cellpadding="0" border="0" width="100%" align="center"
                        style="margin-top: 10px;">
                        <tr>
                            <td class="label-normal" style="text-align:left;">
                                <font style="font-size: 9pt;font-weight: 400" face="sans-serif">
                                    <b>
                                        Telah lahir seorang anak :
                                    </b>
                                </font>
                                <br>
                                <font style="font-size: 9pt;font-weight: 400" face="sans-serif">
                                    <b>
                                        <i>
                                            A baby was born :
                                        </i>
                                    </b>
                                </font>
                            </td>
                        </tr>
                    </table>
                    <table cellspacing="0" cellpadding="0" border="0" width="100%" align="center"
                        style="margin: 6px 0px 6px 20px;">
                        <tr>
                            <td height="5" width="27%">
                                <font style="font-size: 9pt;font-weight: 400" color="#sans-serif" face="Tahoma">
                                    <b>
                                        Nama Anak /<i>Child`s Name</i>
                                    </b>
                                </font>
                            </td>
                            <td height="5" width="1%">
                                <font size="1">:</font>
                            </td>
                            <td height="5" width="72%">
                                <font style="font-size: 9pt;font-weight: 400" color="#sans-serif" face="Tahoma">
                                    <b>
                                        {{ strtoupper($dataLahir->namaanak) }}
                                    </b>
                                </font>
                            </td>
                        </tr>
                        <tr>
                            <td height="5" width="27%">
                                <font style="font-size: 9pt;font-weight: 400" color="#sans-serif" face="Tahoma">
                                    <b>
                                        Jenis Kelamin /<i>Sex</i>
                                    </b>
                                </font>
                            </td>
                            <td height="5" width="1%">
                                <font size="1">:</font>
                            </td>
                            <td height="5" width="72%">
                                <font style="font-size: 9pt;font-weight: 400" color="#sans-serif" face="Tahoma">
                                    <b>
                                        {{ $dataLahir->jeniskelamin }}&nbsp;
                                        /<i>{{ strtoupper($dataLahir->jeniskelamin) == 'PEREMPUAN' ? 'Female' : 'Male' }}</i>
                                    </b>
                                </font>
                            </td>
                        </tr>
                        <tr>
                            <td height="5" width="27%">
                                <font style="font-size: 9pt;font-weight: 400" color="#sans-serif" face="Tahoma">
                                    <b>
                                        Berat Badan Lahir /<i>Weight</i>
                                    </b>
                                </font>
                            </td>
                            <td height="5" width="1%">
                                <font size="1">:</font>
                            </td>
                            <td height="5" width="72%">
                                <font style="font-size: 9pt;font-weight: 400" color="#sans-serif" face="Tahoma">
                                    <b>
                                        {{ number_format((float) $dataLahir->berat, 2, '.', '') }} gram&nbsp;
                                        /<i>{{ number_format((float) $dataLahir->berat, 2, '.', '') }} grams</i>
                                    </b>
                                </font>
                            </td>
                        </tr>
                        <tr>
                            <td height="5" width="27%">
                                <font style="font-size: 9pt;font-weight: 400" color="#sans-serif" face="Tahoma">
                                    <b>
                                        Panjang Badan /<i>Length</i>
                                    </b>
                                </font>
                            </td>
                            <td height="5" width="1%">
                                <font size="1">:</font>
                            </td>
                            <td height="5" width="72%">
                                <font style="font-size: 9pt;font-weight: 400" color="#sans-serif" face="Tahoma">
                                    <b>
                                        {{ number_format((float) $dataLahir->tinggi, 2, '.', '') }} cm&nbsp;
                                        /<i>{{ number_format((float) $dataLahir->tinggi, 2, '.', '') }} centimeters</i>
                                    </b>
                                </font>
                            </td>
                        </tr>
                    </table>
                    <table cellspacing="0" cellpadding="0" border="0" width="100%" align="center"
                        style="margin-top: 10px;">
                        <tr>
                            <td class="label-normal" style="text-align:left;">
                                <font style="font-size: 9pt;font-weight: 400" face="sans-serif">
                                    <b>
                                        Dilahirkan Oleh Ibu :
                                    </b>
                                </font>
                                <br>
                                <font style="font-size: 9pt;font-weight: 400" face="sans-serif">
                                    <b>
                                        <i>
                                            Delivered by the Mother :
                                        </i>
                                    </b>
                                </font>
                            </td>
                        </tr>
                    </table>
                    <table cellspacing="0" cellpadding="0" border="0" width="100%" align="center"
                        style="margin: 6px 0px 6px 20px;">
                        <tr>
                            <td height="5" width="28%">
                                <font style="font-size: 9pt;font-weight: 400" color="#sans-serif" face="Tahoma">
                                    <b>
                                        Nama /<i>Name</i>
                                    </b>
                                </font>
                            </td>
                            <td height="5" width="1%">
                                <font size="1">:</font>
                            </td>
                            <td height="5" width="73%">
                                <font style="font-size: 9pt;font-weight: 400" color="#sans-serif" face="Tahoma">
                                    <b>
                                        {{-- @if ($dataLahir != null)
                                            {{ strtoupper($dataLahir->namaibuuk ?? $dataIbu->namapasien) }}
                                        @else
                                            {{ strtoupper($dataIbu->namapasien) }}
                                        @endif --}}
                                        @if ($dataLahir->namaibuuk != null)
                                            {{ strtoupper($dataLahir->namaibuuk) }}
                                        @else
                                            {{ strtoupper($dataLahir->namaibuForReal) }}
                                        @endif
                                    </b>
                                </font>
                            </td>
                        </tr>
                        <tr>
                            <td height="5" width="27%">
                                <font style="font-size: 9pt;font-weight: 400" color="#sans-serif" face="Tahoma">
                                    <b>
                                        No Identitas /<i>Identity Number</i>
                                    </b>
                                </font>
                            </td>
                            <td height="5" width="1%">
                                <font size="1">:</font>
                            </td>
                            <td height="5" width="72%">
                                <font style="font-size: 9pt;font-weight: 400" color="#sans-serif" face="Tahoma">
                                    <b>
                                        {{-- @if (!empty($dataLahir))
                                            {{ $dataLahir->noidentitas ?? $dataIbu->noidentitas }}
                                        @endif --}}
                                        @if ($dataLahir->noidentitas != null)
                                            {{ strtoupper($dataLahir->noidentitas) }}
                                        @else
                                            {{ strtoupper($dataLahir->noidentitasibuForReal) }}
                                        @endif
                                    </b>
                                </font>
                            </td>
                        </tr>
                        <tr>
                            <td height="5" width="27%">
                                <font style="font-size: 9pt;font-weight: 400" color="#sans-serif" face="Tahoma">
                                    <b>
                                        Alamat /<i>Address</i>
                                    </b>
                                </font>
                            </td>
                            <td height="5" width="1%">
                                <font size="1">:</font>
                            </td>
                            <td height="5" width="72%">
                                <font style="font-size: 9pt;font-weight: 400" color="#sans-serif" face="Tahoma">
                                    <b>
                                        {{-- Use normIbu's alamatlengkap if nocmfkibu is empty --}}
                                        {{-- @if (!empty($dataLahir))
                                            {{ $dataLahir->alamatlengkap ?? $dataIbu->alamatlengkap }}
                                        @endif --}}
                                        @if ($dataLahir->alamat_normibu != null)
                                            {{ strtoupper($dataLahir->alamat_normibu) }}
                                        @else
                                            {{ strtoupper($dataLahir->alamatIbuForReal) }}
                                        @endif
                                    </b>
                                </font>
                            </td>
                        </tr>
                        <tr>
                            <td height="5" width="27%">
                                <font style="font-size: 9pt;font-weight: 400" color="#sans-serif" face="Tahoma">
                                    <b>
                                        Usia /<i>Age</i>
                                    </b>
                                </font>
                            </td>
                            <td height="5" width="1%">
                                <font size="1">:</font>
                            </td>
                            <td height="5" width="72%">
                                <font style="font-size: 9pt;font-weight: 400" color="#sans-serif" face="Tahoma">
                                    {{-- @if (!empty($dataLahir))
                                        @php
                                            $datetime = new \DateTime($dataLahir->tgllahiribu);
                                            $calculateYears = $datetime
                                                ->diff(new \DateTime(date('Y-m-d')))
                                                ->format('%y');
                                        @endphp
                                        <b>
                                            {{ $calculateYears }} Tahun&nbsp;
                                            /<i>{{ $calculateYears }} Years Old</i>
                                        </b>
                                    @else
                                        @php
                                            $datetime = new \DateTime($dataIbu->tgllahir);
                                            $calculateYears = $datetime
                                                ->diff(new \DateTime(date('Y-m-d')))
                                                ->format('%y');
                                        @endphp
                                        <b>
                                            {{ $calculateYears }} Tahun&nbsp;
                                            /<i>{{ $calculateYears }} Years Old</i>
                                        </b>
                                    @endif --}}
                                    @if ($dataLahir->tgllahiribu != null)
                                        @php
                                            $datetime = new \DateTime($dataLahir->tgllahiribu);
                                            $calculateYears = $datetime
                                                ->diff(new \DateTime(date('Y-m-d')))
                                                ->format('%y');
                                        @endphp
                                        <b>
                                            {{ $calculateYears }} Tahun&nbsp;
                                            /<i>{{ $calculateYears }} Years Old</i>
                                        </b>
                                    @else
                                        @php
                                            $datetime = new \DateTime($dataLahir->tgllahiribuForReal);
                                            $calculateYears = $datetime
                                                ->diff(new \DateTime(date('Y-m-d')))
                                                ->format('%y');
                                        @endphp
                                        <b>
                                            {{ $calculateYears }} Tahun&nbsp;
                                            /<i>{{ $calculateYears }} Years Old</i>
                                        </b>
                                    @endif
                                </font>
                            </td>
                        </tr>
                    </table>

                    <table cellspacing="0" cellpadding="0" border="0" width="100%" align="center"
                        style="margin-top: 10px">
                        <tr>
                            <td height="5" width="31%">
                            </td>
                            <td></td>
                            <td height="5" width="69%">
                                <font style="font-size: 9pt;font-weight: 400" color="#sans-serif" face="sans-serif">
                                    <b>
                                        Garut, {{ $dataLahir->tglcetakindo }}
                                    </b>
                                </font>
                            </td>
                        </tr>
                        <tr style="padding: 50px !important;">
                            <td height="5" width="31%">
                            </td>
                            <td></td>
                            <td height="5" width="69%">
                                <font style="font-size: 9pt;font-weight: 400" color="#sans-serif" face="sans-serif">
                                    <b>
                                        <i>Garut,
                                            {{ date_format(date_create($dataLahir->created_at), 'F d, Y') }}</i>
                                    </b>
                                </font>
                            </td>
                        </tr>
                    </table>
                    <table cellspacing="0" cellpadding="0" border="0" width="100%" align="center"
                        style="margin-top: 10px">
                        <tr>
                            <td height="5" width="31%">
                            </td>
                            <td></td>
                            <td height="5" width="69%">
                                <img src="data:image/jpeg;base64,{{ $qrcode }}" width="140px" border="0">
                            </td>
                        </tr>
                    </table>
                    <table cellspacing="0" cellpadding="0" border="0" width="100%" align="center"
                        style="margin-top: 10px">
                        <tr>
                            <td height="5" width="31%">
                            </td>
                            <td></td>
                            <td height="5" width="69%">
                                <font style="font-size: 9pt;font-weight: 400" color="#sans-serif" face="Tahoma">
                                    <b>
                                        {{ $dataLahir->dokterPenolong }}
                                    </b>
                                </font>
                            </td>
                        </tr>
                        <tr>
                            <td height="5" width="31%">
                            </td>
                            <td></td>
                            <td height="5" width="69%">
                                <font style="font-size: 9pt;font-weight: 400" color="#sans-serif" face="Tahoma">
                                    <b>
                                        Penolong Persalinan
                                    </b>
                                </font>
                            </td>
                        </tr>
                    </table>
                    <table cellspacing="0" cellpadding="0" border="0" width="100%" align="center"
                        style="margin-top: 10px">
                        <tr>
                            <td height="5" width="31%">
                            </td>
                            <td></td>
                            <td height="5" width="69%">
                                <font style="font-size: 9pt;font-weight: 400" color="#sans-serif" face="Tahoma">
                                    <b>
                                        <i>Attending physicien</i>
                                    </b>
                                </font>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>

    <script>
        window.onload = function() {
            window.print();
        }
    </script>
</body>
