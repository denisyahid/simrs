<!DOCTYPE html>
<html>
@php
    use Illuminate\Support\Str;
    $profile = App\Http\Controllers\Controller::static_profile();
@endphp

<head>
    <title>Cetak Hasil Lab</title>
    <meta name="robots" content="noindex">
    <meta name="googlebot" content="noindex">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
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
            <table cellspacing="0" bgcolor="#FFFFFF" border="0" width="100%">
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
                                {{ $nobilling ? $nobilling : '-' }}
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
            </table>

            <table cellspacing="0" bgcolor="#FFFFFF" border="0" width="100%">
                <tr>
                    <th align="left"
                        style="padding-top: 1mm; padding-bottom: 1mm; border-top: 1.5px solid black;border-bottom: 1.5px solid black;padding-left: 3px;">
                        PEMERIKSAAN</th>
                    <th
                        style="padding-top: 1mm; padding-bottom: 1mm; border-top: 1.5px solid black;border-bottom: 1.5px solid black;">
                        HASIL</th>
                    @if ($FT != 'FT')
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
                @endif
                </thead>
                <tbody>
                    @php
                        $note = '';
                    @endphp
                    @forelse ($dataReport['details'] as $group)
                        <tr>
                            <td colspan="6" style="text-align: left; font-weight: bold; padding: 5px;">
                                {{ $group->group ?? '-' }}
                            </td>
                        </tr>

                        @foreach ($group->items as $item)
                            @if ($showHIV == true)
                                <tr>
                                    <td style="padding-left: 10px; white-space: nowrap; padding-bottom:5px;">
                                        {{ $item->detailpemeriksaan ?? '-' }}
                                    </td>

                                    <!-- Kolom Flag dan Hasil dalam satu div -->
                                    <td align="left"
                                        style=" {{ $FT == 'FT' ? '' : 'display:flex;' }} align-items: center; gap: 2px;padding-left:85px; white-space: {{ $FT == 'FT' ? 'normal' : 'nowrap' }};">
                                        @if ($item->flag === 'H' && (!empty($item->hasil) || $item->hasil == 0))
                                            <span style="color: red; ">H</span>
                                        @elseif($item->flag === 'L' || ($item->flag === 'LL' && (!empty($item->hasil) || $item->hasil == 0)))
                                            <span style="color: blue; ">{{ $item->flag }}</span>
                                        @elseif($item->flag === 'HH' && (!empty($item->hasil) || $item->hasil == 0))
                                            <span style="color: red; ">HH</span>
                                        @elseif($item->flag === '*' && (!empty($item->hasil) || $item->hasil == 0))
                                            <span style="color: red; ">*</span>
                                        @elseif($item->flag === 'N' && (!empty($item->hasil) || $item->hasil == 0))
                                            <span style="color: black; ">N</span>
                                        @endif

                                        <!-- Angka Hasil -->
                                        @if (!empty($item->result_ft))
                                            <span
                                                style="text-align:center;{{ $item->flag === 'H' || $item->flag === 'HH' || $item->flag === '*' ? 'color: red; ' : ($item->flag === 'L' || $item->flag === 'LL' ? 'color: blue; ' : '') }}">
                                                {{ $item->result_ft }}
                                            </span>
                                        @else
                                            <span
                                                style="text-align:center;{{ $item->flag === 'H' || $item->flag === 'HH' || $item->flag === '*' ? 'color: red; ' : ($item->flag === 'L' || $item->flag === 'LL' ? 'color: blue; ' : '') }}">
                                                {{ $item->hasil }}
                                            </span>
                                        @endif
                                    </td>
                                    @if (!empty($item->hasil) || $item->hasil == 0)
                                        <td align="center" style="white-space: nowrap; padding-bottom:5px;">
                                            {{ $item->satuanstandar ?? '-' }}
                                        </td>
                                        <td align="center" style="white-space: nowrap; padding-bottom:5px;">
                                            {{ $item->nilaitext ?? '-' }}
                                        </td>
                                        <td align="center" style="padding-bottom:5px;">
                                            @if ($item->flag === 'H')
                                                Tinggi
                                            @elseif($item->flag === 'LL' || $item->flag === 'L')
                                                Rendah
                                            @endif
                                        </td>
                                        <td align="center" style="white-space: nowrap; padding-bottom:5px;">
                                            {{ $item->metode ?? '-' }}
                                        </td>
                                    @endif
                                </tr>
                            @else
                                @if (!Str::contains(Str::upper($item->detailpemeriksaan), 'HIV'))
                                    <tr>
                                        <td style="padding-left: 10px; white-space: nowrap; padding-bottom:5px;">
                                            {{ $item->detailpemeriksaan ?? '-' }}
                                        </td>

                                        <!-- Kolom Flag dan Hasil dalam satu div -->
                                        <td align="left"
                                            style=" {{ $FT == 'FT' ? '' : 'display:flex;' }} align-items: center; gap: 2px;padding-left:85px; white-space: {{ $FT == 'FT' ? 'normal' : 'nowrap' }};">
                                            @if ($item->flag === 'H' && (!empty($item->hasil) || $item->hasil == 0))
                                                <span style="color: red; ">H</span>
                                            @elseif($item->flag === 'L' || ($item->flag === 'LL' && (!empty($item->hasil) || $item->hasil == 0)))
                                                <span style="color: blue; ">{{ $item->flag }}</span>
                                            @elseif($item->flag === 'HH' && (!empty($item->hasil) || $item->hasil == 0))
                                                <span style="color: red; ">HH</span>
                                            @elseif($item->flag === '*' && (!empty($item->hasil) || $item->hasil == 0))
                                                <span style="color: red; ">*</span>
                                            @elseif($item->flag === 'N' && (!empty($item->hasil) || $item->hasil == 0))
                                                <span style="color: black; ">N</span>
                                            @endif

                                            <!-- Angka Hasil -->
                                            @if (!empty($item->result_ft))
                                                <span
                                                    style="text-align:center;{{ $item->flag === 'H' || $item->flag === 'HH' || $item->flag === '*' ? 'color: red; ' : ($item->flag === 'L' || $item->flag === 'LL' ? 'color: blue; ' : '') }}">
                                                    {{ $item->result_ft }}
                                                </span>
                                            @else
                                                <span
                                                    style="text-align:center;{{ $item->flag === 'H' || $item->flag === 'HH' || $item->flag === '*' ? 'color: red; ' : ($item->flag === 'L' || $item->flag === 'LL' ? 'color: blue; ' : '') }}">
                                                    {{ $item->hasil }}
                                                </span>
                                            @endif
                                        </td>
                                        @if (!empty($item->hasil) || $item->hasil == 0)
                                            <td align="center" style="white-space: nowrap; padding-bottom:5px;">
                                                {{ $item->satuanstandar ?? '-' }}
                                            </td>
                                            <td align="center" style="white-space: nowrap; padding-bottom:5px;">
                                                {{ $item->nilaitext ?? '-' }}
                                            </td>
                                            <td align="center" style="padding-bottom:5px;">
                                                @if ($item->flag === 'H')
                                                    Tinggi
                                                @elseif($item->flag === 'LL' || $item->flag === 'L')
                                                    Rendah
                                                @endif
                                            </td>
                                            <td align="center" style="white-space: nowrap; padding-bottom:5px;">
                                                {{ $item->metode ?? '-' }}
                                            </td>
                                        @endif
                                    </tr>
                                @endif
                            @endif

                            @php
                                if (!empty($item->comment)) {
                                    $note .= nl2br(str_replace('~', '', $item->comment));
                                }
                            @endphp
                            @if (!empty($note))
                                <tr>
                                    <td colspan="6" style="text-align: left; font-weight: bold; padding: 5px;">
                                        <span>Note:</span>
                                        <span style="font-weight: normal">
                                            {{ $note }}
                                        </span>
                                    </td>
                                </tr>
                                @php
                                    $note = ''; // Reset note
                                @endphp
                            @endif
                        @endforeach
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
                        <span>Diperiksa oleh: {{ $diotorisasi ? $diotorisasi : '-' }}</span><br>
                        <span>Diotorisasi oleh: {{ $dokterdiperiksa ? $dokterdiperiksa : '-' }}</span><br><br>
                        <span>
                            Interpretasi terhadap hasil laboratorium hanya dilakukan oleh dokter/klinisi. Dokumen ini
                            tidak memerlukan tanda tangan basah karena telah divalidasi secara digital.
                        </span>
                    </div>
                </td>
                <td align="center" width="20%">
                    <img src="data:image/png;base64, {{ $tte }}" width="100" height="100"><br>
                    <u>{{ $dataRegis->dokterpemeriksa ? $dataRegis->dokterpemeriksa : '-' }}</u><br>
                    Penanggung Jawab
                </td>
            </tr>
        </table>
    </footer>
</body>

</html>
