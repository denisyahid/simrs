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
            font-size: 11px;
        }

        footer {
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            text-align: center;
            font-size: 10px;
            padding: 5px;
            /* page-break-after: always; */
        }

        .content {
            /* width: 100%; */
            padding-bottom: 20mm;
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
    </style>
</head>

<body>
    <main align="center">
        <div class="content" align="center">
            <table cellspacing="0" cellpadding="0" bgcolor="#FFFFFF" border="0" width="100%">
                <tbody>
                    <tr>
                        <td width="100%">
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
                        </td>
                    </tr>
                </tbody>
            </table>
            <table cellspacing="0" cellpadding="0" bgcolor="#FFFFFF" border="0" width="100%">
                <thead>
                    <tr>
                        <th colspan="5" align="center" style="font-size: 12pt; font-weight: bold;">
                            HASIL PEMERIKSAAN LABORATORIUM
                        </th>
                    </tr>
                    <tr>
                        <td style="padding-left: 10px; padding-bottom:7px;" width="22%">
                            <font style="font-size: 9pt" color="#000000">Nama Pasien</font>
                        </td>
                        <td style="padding-bottom:7px;" width="27%">
                            <font style="font-size: 9pt" color="#000000">:
                                {{ $dataReport['header']->namapasien ?? '' }}
                            </font>
                        </td>
                        <td style="padding-bottom:7px; padding-left:50px" width="25%">
                            <font style="font-size: 9pt" color="#000000">No Lab</font>
                        </td>
                        <td style="padding-right: 10px;  padding-left:5px;  padding-bottom:7px;" width="26%">
                            <font style="font-size: 9pt;  word-wrap: break-word;" color="#000000">:
                                {{ $dataRegis->noorder ? $dataRegis->noorder : '-' }}
                            </font>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding-left: 10px; padding-bottom:7px;">
                            <font style="font-size: 9pt" color="#000000">No. RM</font>
                        </td>
                        <td style="padding-bottom:7px;">
                            <font style="font-size: 9pt" color="#000000">:
                                {{ $dataReport['header']->nocm ?? '' }}</font>
                        </td>
                        <td style="padding-bottom:7px; padding-left:50px" width="25%">
                            <font style="font-size: 9pt" color="#000000">Diagnosa</font>
                        </td>
                        <td style="padding-right: 10px;  padding-left:5px;  padding-bottom:7px;" width="26%">
                            <font style="font-size: 9pt;  word-wrap: break-word;" color="#000000">:
                                {{ $dataReport['header']->diagnosa ?? '' }}
                            </font>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding-left: 10px; padding-bottom:7px;">
                            <font style="font-size: 9pt" color="#000000">Tgl. Lahir / Umur</font>
                        </td>
                        <td style="padding-bottom:7px;">
                            <font style="font-size: 9pt" color="#000000">:
                                {{ $dataReport['header']->tglkelahiran }} /
                                {{ \Carbon\Carbon::parse($dataReport['header']->tglkelahiran)->age }} Y
                            </font>
                        </td>
                        <td style="padding-bottom:7px; padding-left:50px">
                            <font style="font-size: 9pt" color="#000000">Tgl. Pengambilan Sample</font>
                        </td>
                        <td style="padding-right: 10px; padding-left:5px; padding-bottom:7px;">
                            <font style="font-size: 9pt;  color="#000000">:
                                {{ $dataReport['header']->tglpengambilan ?? '' }}
                            </font>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding-left: 10px; padding-bottom:7px;">
                            <font style="font-size: 9pt" color="#000000">Jenis Kelamin</font>
                        </td>
                        <td style="padding-bottom:7px;">
                            <font style="font-size: 9pt" color="#000000">:
                                {{ $dataReport['header']->jk == 'P' ? 'Perempuan' : 'Laki-Laki' }}</font>
                        </td>
                        <td style="padding-bottom:7px; padding-left:50px">
                            <font style="font-size: 9pt" color="#000000">Tgl. Hasil Selesai</font>
                        </td>
                        <td style="padding-right: 10px; padding-left:5px; padding-bottom:7px;">
                            <font style="font-size: 9pt;  color="#000000">:
                                {{ $tglhasil ? $tglhasil : null }}
                            </font>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding-left: 10px;padding-right: 10px; padding-bottom:7px;">
                            <font style="font-size: 9pt" color="#000000">Alamat</font>
                        </td>
                        <td style="padding-bottom:7px;" rowspan="2">
                            <font style="font-size: 9pt" color="#000000">
                                @php
                                    $alamat = $dataReport['header']->alamatlengkap;
                                    $alamatBaru = wordwrap($alamat, 50, '<br />', true);
                                    echo ': ' . $alamatBaru;
                                @endphp/{{ $dataReport['header']->nohp ? $dataReport['header']->nohp : '' }}
                            </font>
                        </td>
                        <td style="padding-bottom:7px; padding-left:50px">
                            <font style="font-size: 9pt" color="#000000">Asal / Ruangan</font>
                        </td>
                        <td style="padding-right: 10px; padding-left:5px; padding-bottom:7px;">
                            <font style="font-size: 9pt;  color="#000000">:
                                {{ $dataRegis->ruanganasal ? $dataRegis->ruanganasal : '-' }}
                            </font>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2"></td>
                        <td style="padding-bottom:7px; padding-left:50px">
                            <font style="font-size: 9pt" color="#000000">Dokter Pengirim </font>
                        </td>
                        <td style="padding-bottom:7px; padding-left:5px" width="38%">
                            <font style="font-size: 9pt; " color="#000000" style=word-break: break-word;">:
                                {{ $dataRegis->dokterpengirim ? $dataRegis->dokterpengirim : '-' }}
                            </font>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding-left: 10px; padding-bottom:7px;">&nbsp;</td>
                        <td style="padding-bottom:7px;">&nbsp;</td>
                    </tr>
                    <tr>
                        <th align="left"
                            style="padding-top: 1mm; padding-bottom: 1mm; border-top: 1.5px solid black;  padding-left: 3px;">
                        </th>
                        <th style="padding-top: 1mm; padding-bottom: 1mm; border-top: 1.5px solid black; ">
                        </th>
                        <th style="padding-top: 1mm; padding-bottom: 1mm; border-top: 1.5px solid black; ">
                        </th>
                        <th style="padding-top: 1mm; padding-bottom: 1mm; border-top: 1.5px solid black; ">
                        </th>
                    </tr>
                    <th </thead>
                <tbody>
                    @php
                        $note = '';
                    @endphp
                    @forelse ($dataReport['details'] as $group)
                        <tr>
                            <td colspan="5" style="text-align: left; font-weight: bold; padding: 5px;">
                                {{ $group->group ?? '-' }}
                            </td>
                        </tr>
                        <tr>
                            <td colspan="5" style="text-align: left; font-weight: bold; padding: 5px;">
                                {{ $group->namates ?? '-' }}
                            </td>
                        </tr>
                        @foreach ($group->items as $item)
                            <tr>
                                <td style="padding-left: 20px;">{{ $item->detailpemeriksaan ?? '-' }}</td>
                                <td>
                                    {{-- <span>{{ $item->hasil  ?? 'PENDING'}}</span> --}}
                                    <span>
                                        {!! $item->hasil ? nl2br(str_replace('~', '<br/>', $item->hasil)) : 'PENDING' !!}
                                    </span>
                                </td>
                                {{-- <td align="center">{{ $item->satuanstandar ?? '-' }}</td>
                                <td align="center">{{ $item->nilaitext ?? '-' }}</td>
                                <td align="center">{{ $item->metode ?? '-' }}</td> --}}
                            </tr>
                            @php
                                if (!empty($item->comment)) {
                                    $note .= nl2br(str_replace('~', '<br><br>', $item->comment));
                                }
                            @endphp
                        @endforeach
                        <tr>
                            <td colspan="5" style="text-align: left; font-weight: bold; padding: 5px;">
                                <span>Komentar : </span>
                                <br>
                                <span style="font-weight: normal">
                                    {!! $note ? $note : 'Tidak Ada Komentar' !!}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" align="center">Data tidak tersedia</td>
                        </tr>
                    @endforelse
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
                        <span>Diperiksa oleh: {{ $dokterdiperiksa ? $dokterdiperiksa : '-' }}</span><br>
                        <span>Diotorisasi oleh: {{ $diotorisasi ? $diotorisasi : '-' }}</span><br><br>
                        <span>
                            Interpretasi terhadap hasil laboratorium hanya dilakukan oleh dokter/klinisi. Dokumen ini
                            tidak memerlukan tanda tangan basah karena telah divalidasi secara digital.
                        </span>
                    </div>
                </td>
                <td align="center" width="20%">
                    <img src="data:image/png;base64, {{ $tte }}" width="100" height="100"><br>
                    <u>{{ $dataRegis->dokterpemeriksa ?? '-' }}</u><br>
                    Penanggung Jawab
                </td>
            </tr>
        </table>
    </footer>
</body>


</html>
