<!DOCTYPE html>
<html>

<head>
    <title>@yield('title')</title>
    <style>
        * {
            font-family: Arial, Helvetica, sans-serif;
        }
        .checkbox-wrapper {
            white-space: nowrap
        }

        .checkbox {
            vertical-align: top;
            display: inline-block
        }

        .checkbox-label {
            white-space: normal display:inline-block
        }
        .kop-surat-line1 {
        height: 0;
        border-top: 1px solid black;
        border-bottom: 1px solid black;
        }
        .kop-surat-line2 {
        height: 0;
        border-top: 1px solid black;
        border-bottom: 3px solid black;
        margin-top: -5px;
        }
        .td-height {
            height: 60px;
            min-height: 60px;
            font-size: 10pt;
            width: 40%;
        }
        .td2 {
            font-size: 10pt;
            width: 30%;
        }
        .page-break {
            page-break-before: always;
        }
</style>


    </style>
</head>

<body>
    @php
        use Carbon\Carbon;
    @endphp
    <style>
    .page-break {
        page-break-before: always;
    }
</style>
    @php
        $chunks = array_chunk($data['details'], 5);
    @endphp

@foreach ($chunks as $index => $chunk)
<div class="{{ $index > 0 ? 'page-break' : '' }}">
    
    {{-- HEADER KOP SURAT --}}
    <table width="100%" cellspacing="0" cellpadding="0">
        <tr>
            <td style="padding:5px;width: 50%;"></td>
            <td style="padding:5px;width: 50%; text-align: right;font-size:10pt">
                RM.13.10/HD/00
            </td>
        </tr>
    </table>
    <table width="100%" cellspacing="0" cellpadding="0">
        <tr>
            <td style="width: 20%;text-align:center">
                <img src="{{ public_path('img/logo-rs.png') }}" width="100px" height="100px">
            </td>
            <td style="text-align: center; width: 60%;">
                <span style="font-weight: bold">PEMERINTAH PROVINSI BALI <br>RSUD BALI MANDARA</span><br>
                <span style="font-size: 9pt">Jalan By Pass Ngurah Rai No. 548, Garut - Bali<br>
                    Telp.:(0361)4990966 | Email : rsud.balimandara@gmail.com</span>
            </td>
            <td style="width: 20%;"></td>
        </tr>
        <tr>
            <td colspan="3">
                <hr class="kop-surat-line1">
                <hr class="kop-surat-line2">
            </td>
        </tr>
    </table>

    {{-- JUDUL DOKUMEN --}}
    <table width="100%">
        <tr>
            <td align="center">
                <div style="font-size: 15pt; font-weight: bold; color: #000000;padding:8px">
                    <u>PERESEPAN HEMODIALISA RAWAT JALAN</u>
                </div>
            </td>
        </tr>
    </table>

    {{-- INFO PASIEN --}}
    <table width="100%">
        <tr>
            <td width="10%">Nama</td>
            <td width="2%">:</td>
            <td width="60%">{{ $data['pasien']['namapasien'] ?? '-' }}</td>
            <td width="10%">JK</td>
            <td width="2%">:</td>
            <td width="16%">
                {!! $data['pasien']['jeniskelamin'] == 'Laki-laki' ? 'L' : '<s>L</s>' !!}
                /
                {!! $data['pasien']['jeniskelamin'] == 'Perempuan' ? 'P' : '<s>P</s>' !!}
            </td>
        </tr>
        <tr>
            <td>Tgl Lahir</td>
            <td>:</td>
            <td>{{ $data['pasien']['tgllahir'] ?? '-' }}</td>
            <td>Umur</td>
            <td>:</td>
            <td>{{ $data['pasien']['umur'] ?? '-' }}</td>
        </tr>
    </table>

    {{-- TABEL UTAMA PER 5 DATA --}}
    <table width="100%" cellspacing="0" cellpadding="0" style="border: 1px solid black; border-collapse: collapse" border="1">
        <tr>
            <td class="td-height">TANGGAL</td>
            @foreach ($chunk as $item)
            <td class="td2">{{ isset($item['tanggal']) ? Carbon::parse($item['tanggal'])->translatedFormat('d F Y') : ' ' }}</td>
            @endforeach
        </tr>
        <tr>
            <td class="td-height">BB Kering (kg)</td>
            @foreach ($chunk as $item)
            <td class="td2">{{ $item['bbkering'] ?? ' ' }}</td>
            @endforeach
        </tr>
        <tr>
            <td class="td-height">FREKUENSI HD PER MINGGU</td>
            @foreach ($chunk as $item)
            <td class="td2">{{ $item['frekuensihd'] ?? ' ' }}</td>
            @endforeach
        </tr>
        <tr>
            <td class="td-height">LAMA HD (JAM)</td>
            @foreach ($chunk as $item)
            <td class="td2">{{ $item['lamahd'] ?? ' ' }}</td>
            @endforeach
        </tr>
        <tr>
            <td class="td-height">LUAS MEMBRAN DIALISER (m<sup>2</sup>)</td>
            @foreach ($chunk as $item)
            <td class="td2">{{ $item['luasmembran'] ?? ' ' }}</td>
            @endforeach
        </tr>
        <tr>
            <td class="td-height">FLOW DIALISAT (ML/mnt)</td>
            @foreach ($chunk as $item)
            <td class="td2">{{ $item['flowdialisat'] ?? ' ' }}</td>
            @endforeach
        </tr>
        <tr>
            <td class="td-height">JENIS AKSES VASKULAR</td>
            @foreach ($chunk as $item)
            <td class="td2">{{ $item['jenisakses'] ?? ' ' }}</td>
            @endforeach
        </tr>
        <tr>
            <td class="td-height">UKURAN JARUM VISTULA</td>
            @foreach ($chunk as $item)
            <td class="td2">{{ $item['ukuranjarum'] ?? ' ' }}</td>
            @endforeach
        </tr>
        <tr>
            <td class="td-height">HEPARIN AWAL (unit)</td>
            @foreach ($chunk as $item)
            <td class="td2">{{ $item['heparinawal'] ?? ' ' }}</td>
            @endforeach
        </tr>
        <tr>
            <td class="td-height">HEPARIN PEMELIHARAAN (unit)</td>
            @foreach ($chunk as $item)
            <td class="td2">{{ $item['heparinpemeliharaan'] ?? ' ' }}</td>
            @endforeach
        </tr>
        <tr>
            <td class="td-height">NAMA dan TTD DOKTER</td>
            @foreach ($chunk as $item)
            <td class="td2" style="{{ empty($item['dokterParaf']) ? 'padding-bottom: 2em;' : '' }};text-align:center;">
                @if (isset($item['dokterParaf']['label']))
                    <img src="data:image/png;base64, {!! $qrcode !!}">
                @endif
                <br>
                <span>{{ $item['dokterParaf']['label'] ?? ' ' }}</span>
            </td>
            @endforeach
        </tr>
    </table>
</div>
@endforeach

</body>

</html>
