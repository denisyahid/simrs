<!DOCTYPE html>
<html>
@php
    $profile = App\Http\Controllers\Controller::static_profile();
@endphp

<head>
    <title>Cetak Hasil Lab</title>
    <meta name="robots" content="noindex">
    <meta name="googlebot" content="noindex">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>
    <link rel="stylesheet" href="{{ asset('css/paper.css') }} ">
    <link rel="stylesheet" href="{{ asset('css/table-v2.css') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}"> --}}
    <style>
        :root {
            --font: Arial, Helvetica, sans-serif;
        }

        body,
        td,
        th,
        span,
        p {
            font-family: Tahoma, Geneva, sans-serif !important;
            !important;
            font-size: 11px;
        }

        @page {
            margin: 5mm;
            size: A4;
        }

        footer {
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            text-align: center;
            font-size: 10px;
            padding: 5px;
        }

        .content {
            padding-bottom: 40mm;
        }

        thead{
            display: table-header-group;
        }

        main {
            background: url({{ 'img/logo-rs.png' }}) no-repeat fixed center;
            background-size: 700px 800px;
            background-position: center;
            opacity: 0.10;
        }

        @media print {
            header:after {
                content: "Header Text";
                display: block;
                text-align: center;
            }

            footer:after {
                content: "Footer Text";
                display: block;
                text-align: center;
            }
        }

        header {
            display: none
        }

        /* .new-page {
            page-break-before: always;
        } */

        .new-page {
            page-break-before: always;
            break-before: page; /* modern syntax */
        }

        .new-page:first-child {
            page-break-before: auto;
            break-before: auto;
        }
    </style>
</head>

<body>
    @forelse ($dataReport['details'] as $group)
        <main align="center" class="new-page">
            <div class="content" align="center">
                <table cellspacing="0" bgcolor="#FFFFFF" border="0" width="100%">
                    <thead>
                        <tr>
                            <th width="100%">
                                <table width="100%" cellspacing="0" cellpadding="0" style="margin-top: 5px; margin-bottom: -10px;">
                                    <tr>
                                        <td style="text-align:center" width="10%" rowspan="3">
                                            @if ((isset($res['pdf']) && $res['pdf']) || (isset($res['storage']) && $res['storage']))
                                                <img src="{{ 'img/logo-rs.png' }}" width="80px" border="0" style="margin-top: -20px;">
                                            @else
                                                <img src="{{ asset('img/logo-rs.png') }}" width="80px" border="0" style="margin-top: -20px;">
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <span style="font-size: 30pt;font-weight: 600;letter-spacing: 0px;color:#000000">
                                                {{ strtoupper($profile->namapemerintah) }}
                                            </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-center">
                                            <span style="font-size: 11pt;font-weight: 600;letter-spacing: 0px;color:#000000">
                                                {{$profile->alamatlengkap}} <br> {{$profile->fixedphone}} <br> email : rsud.balimandara@gmail.com
                                            </span>
                                        </td>
                                    </tr>
                                </table>
                                <hr class="baris2">
                            </th>
                        </tr>
                    </thead>
                    <thead>
                        <tr>
                            <th colspan="6" align="center" style="font-size: 12pt; font-weight: bold;">
                                HASIL PEMERIKSAAN LABORATORIUM
                            </th>
                        </tr>
                        <tr>
                            <th style="padding-left: 10px; padding-bottom:7px;" width="22%" align="left">
                                <font style="font-size: 9pt" color="#000000">Nama Pasien</font>
                            </th>
                            <th style="padding-bottom:7px;" width="27%" colspan="2" align="left">
                                <font style="font-size: 9pt" color="#000000">:
                                    {{ $dataReport['header']->namapasien ?? '' }}
                                </font>
                            </th>
                            <th style="padding-bottom:7px; padding-left:50px" width="25%" align="left">
                                <font style="font-size: 9pt" color="#000000">No Lab</font>
                            </th>
                            <th style="padding-right: 10px;  padding-left:5px;  padding-bottom:7px;" width="26%" colspan="2" align="left">
                                <font style="font-size: 9pt;  word-wrap: break-word;" color="#000000">:
                                    {{ isset($group->ONO) ? $group->ONO : '-' }}
                                </font>
                            </th>
                        </tr>

                        <tr>
                            <th style="padding-left: 10px; padding-bottom:7px;" align="left">
                                <font style="font-size: 9pt" color="#000000">No. RM</font>
                            </th>
                            <th style="padding-bottom:7px;" align="left">
                                <font style="font-size: 9pt" color="#000000">:
                                    {{ $dataReport['header']->nocm ?? '' }}</font>
                            </th>
                        </tr>
                        <tr>
                            <th style="padding-left: 10px; padding-bottom:7px;" align="left">
                                <font style="font-size: 9pt" color="#000000">Tgl. Lahir / Umur</font>
                            </th>
                            <th style="padding-bottom:7px;" colspan="2" align="left">
                                <font style="font-size: 9pt" color="#000000">:
                                    {{ $dataReport['header']->tglkelahiran }} /
                                    {{ \Carbon\Carbon::parse($dataReport['header']->tglkelahiran)->age }} Y
                                </font>
                            </th>
                            <th style="padding-bottom:7px; padding-left:50px" align="left">
                                <font style="font-size: 9pt" color="#000000">Tgl. Hasil Selesai</font>
                            </th>
                            <th style="padding-right: 10px; padding-left:5px; padding-bottom:7px;" colspan="2" align="left">
                                <font style="font-size: 9pt;  color="#000000">:
                                @if($daftar[0]->objectdepartemenfk == 16)
                                    {{ $group->tglhasil ? $group->tglhasil : null }}
                                @else
                                    {{ $tglhasil ? $tglhasil : null }}
                                @endif
                                </font>
                            </th>
                        </tr>
                        <tr>
                            <th style="padding-left: 10px; padding-bottom:7px;" align="left">
                                <font style="font-size: 9pt" color="#000000">Jenis Kelamin</font>
                            </th>
                            <th style="padding-bottom:7px;" colspan="2" align="left">
                                <font style="font-size: 9pt" color="#000000">:
                                    {{ $dataReport['header']->jk == 'P' ? 'Perempuan' : 'Laki-Laki' }}</font>
                            </th>
                            <th style="padding-bottom:7px; padding-left:50px" align="left">
                                <font style="font-size: 9pt" color="#000000">Asal / Ruangan</font>
                            </th>
                            <th style="padding-right: 10px; padding-left:5px; padding-bottom:7px;" colspan="2" align="left">
                                <font style="font-size: 9pt;  color="#000000">:
                                    {{ isset($group->ruanganasal) ? $group->ruanganasal : '-' }}
                                </font>
                            </th>
                        </tr>
                        <tr>
                            <th style="padding-left: 10px;padding-right: 10px; padding-bottom:7px;" align="left">
                                <font style="font-size: 9pt" color="#000000">Alamat</font>
                            </th>
                            <th style="padding-bottom:7px;" colspan="2" align="left">
                                <font style="font-size: 9pt" color="#000000">
                                    @php
                                        $alamat = $dataReport['header']->alamatlengkap;
                                        $alamatBaru = wordwrap($alamat, 50, '<br />', true);
                                        echo ': ' . $alamatBaru;
                                    @endphp/{{ $dataReport['header']->nohp ? $dataReport['header']->nohp : '' }}
                                </font>
                            </th>
                            <th style="padding-bottom:7px; padding-left:50px" align="left">
                                <font style="font-size: 9pt" color="#000000">Dokter Pengirim </font>
                            </th>
                            <th style="padding-bottom:7px; padding-left:5px" width="38%" colspan="2" align="left">
                                <font style="font-size: 9pt; " color="#000000" style=word-break: break-word;">:
                                    {{ isset($group->dokter_pengirim) ? $group->dokter_pengirim : '-' }}
                                </font>
                            </th>
                        </tr>
                    </thead>
                    <thead>
                    <tr>
                        <th align="left"
                            style="padding-top: 1mm; padding-bottom: 1mm; border-top: 1.5px solid black;border-bottom: 1.5px solid black;padding-left: 3px;">
                            PEMERIKSAAN</th>
                        <th
                            style="padding-top: 1mm; padding-bottom: 1mm; border-top: 1.5px solid black;border-bottom: 1.5px solid black;">
                            HASIL</th>
                        <th
                            style="padding-top: 1mm; padding-bottom: 1mm; border-top: 1.5px solid black;border-bottom: 1.5px solid black;">
                            SATUAN</th>
                        <th
                            style="padding-top: 1mm; padding-bottom: 1mm; border-top: 1.5px solid black;border-bottom: 1.5px solid black;">
                            NILAI NORMAL</th>
                        <th
                            style="padding-top: 1mm; padding-bottom: 1mm; border-top: 1.5px solid black;border-bottom: 1.5px solid black;">
                            KETERANGAN</th>
                        <th
                            style="padding-top: 1mm; padding-bottom: 1mm; border-top: 1.5px solid black;border-bottom: 1.5px solid black;">
                            METODE</th>
                    </tr>
                    </thead>
                    <tbody>
                        @foreach ($group->groups as $groupData)
                            <tr>
                                <td colspan="6" style="text-align: left; font-weight: bold; padding: 5px;">
                                    {{ $groupData->test_group ?? '-' }}
                                </td>
                            </tr>
                            @foreach ($groupData->items as $item)
                                <tr>
                                    <td style="padding-left: 10px; word-wrap:break-word; padding-bottom:5px;">
                                        {{ $item->detailpemeriksaan ?? '-' }}</td>
                                    <td align="left"
                                        style="display: flex; align-items: left; gap: 2px;padding-left:25px; word-wrap:break-word;">
                                        @if ($item->flag === 'H')
                                            <span style="color: red; ">H</span>
                                        @elseif($item->flag === 'L' || $item->flag === 'LL')
                                            <span style="color: blue; ">{{ $item->flag }}</span>
                                        @elseif($item->flag === 'HH')
                                            <span style="color: red; ">HH</span>
                                        @elseif($item->flag === '*')
                                            <span style="color: red; ">*</span>
                                        @elseif($item->flag === 'N')
                                            <span style="color: black; ">N</span>
                                        @endif
                                        <span
                                            style="text-align:left;{{ $item->flag === 'H' || $item->flag === 'HH' || $item->flag === '*' ? 'color: red; ' : ($item->flag === 'L' || $item->flag === 'LL' ? 'color: blue; ' : '') }}">
                                            {{ $item->hasil }}
                                        </span>
                                    </td>

                                    <td align="center" style="word-wrap:break-word; padding-bottom:5px;">
                                        {{ $item->satuanstandar ?? '-' }}
                                    </td>
                                    <td align="center" style="word-wrap:break-word; padding-bottom:5px;">
                                        {{ $item->nilaitext ?? '-' }}
                                    </td>
                                    <td align="center" style="padding-bottom:5px;">
                                        @if ($item->flag === 'H')
                                            Tinggi
                                        @elseif($item->flag === 'LL' || $item->flag === 'L')
                                            Rendah
                                        @endif
                                    </td>
                                    <td align="center" style="word-wrap:break-word; padding-bottom:5px;">
                                        {{ $item->metode ?? '-' }}
                                    </td>
                                </tr>
                            @endforeach
                        @endforeach
                    </tbody>
                </table>
            </div>
        </main>
        <footer>
            <hr>
            <table cellspacing="0" cellpadding="0" bgcolor="#FFFFFF" border="0" width="100%">
                <tr>
                    <td>
                        <div>
                            <span>Diperiksa oleh:
                                {{ isset($group->diotorisasi) ? $group->diotorisasi : '-' }}</span><br>
                            <span>Diotorisasi oleh:
                                {{ isset($group->dokter_pemeriksa) ? $group->dokter_pemeriksa : '-' }}</span><br><br>
                            <span>
                                Interpretasi terhadap hasil laboratorium hanya dilakukan oleh dokter/klinisi. Dokumen
                                ini tidak memerlukan tanda tangan basah karena telah divalidasi secara digital.
                            </span>
                        </div>
                    </td>
                    <td align="center" width="20%">
                        <img src="data:image/png;base64, {{ $tte }}" width="100" height="100"><br>
                        <u>{{ isset($group->dokter_pemeriksa) ? $group->dokter_pemeriksa : '-' }}</u><br>
                        Penanggung Jawab
                    </td>
                </tr>
            </table>
        </footer>
    @empty
        <tr>
            <td colspan="5" align="center">Data tidak tersedia</td>
        </tr>
    @endforelse
</body>

</html>
