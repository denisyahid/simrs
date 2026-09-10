@extends('template.layout-expertise')
@section('title', 'Expertise Radiologi')
@section('page-style')
    <style>
        .infopasien tbody tr td {
            vertical-align: top;
            padding: 2px;
        }

        .infopasien tbody tr td:first-child,
        .infopasien tbody tr td:nth-child(4) {
            font-weight: bold;
        }

        .border {
            border: 1px solid black;
        }

        .menghilangkan-jarak p {
            margin: 0px;
            padding: 0px;
        }
    </style>
@endsection

@php
    // Mempersingkat Jenis Kelamin Pasien
    if ($raw->jeniskelamin == 'Laki-laki') {
        $raw->jeniskelamin = '(L)';
    } elseif ($raw->jeniskelamin == 'Perempuan') {
        $raw->jeniskelamin = '(P)';
    } else {
        $raw->jeniskelamin = '-';
    }
@endphp

@section('content')
    <tr>
        <td style="padding-top:2px;">
            <table cellspacing="0" cellpadding="0" border="0" width="100%" class="infopasien" class="tabel">
                <tbody>
                    <tr>
                        <td width="17%;" style="font-size: 10pt;">Nomor RM</td>
                        <td width="1%;" style="font-size: 10pt;">:</td>
                        <td width="34%;" style="font-size: 10pt;">{{ $raw->nocm }}</td>
                        <td width="15%;" style="font-size: 8pt;">Tanggal</td>
                        <td width="1%;" style="font-size: 8pt;">:</td>
                        <td width="34%;" style="font-size: 8pt;">
                            {{ App\Traits\Valet::getDateIndo(isset($raw->tglorder) ? $raw->tglorder : date('Y-m-d H:i:s')) }}
                        </td>
                    </tr>
                    <tr>
                        <td width="17%;" style="font-size: 10pt;">Nama</td>
                        <td width="1%;" style="font-size: 10pt;">:</td>
                        <td width="34%;" style="font-size: 10pt;">{{ $raw->namapasien }} {{ $raw->jeniskelamin }}</td>
                        <td width="15%;" style="font-size: 8pt;">Dokter Pengirim</td>
                        <td width="1%;" style="font-size: 8pt;">:</td>
                        <td width="34%;" style="font-size: 8pt;">{{ $raw->perujuk }}</td>
                    </tr>
                    <tr>
                        <td width="17%;" style="font-size: 8pt;">Ruangan</td>
                        <td width="1%;" style="font-size: 8pt;">:</td>
                        <td width="34%;" style="font-size: 8pt;">{{ $raw->namaruangan }}</td>
                        <td width="15%;" style="font-size: 8pt;">Tgl Lahir / Umur</td>
                        <td width="1%;" style="font-size: 8pt;">:</td>
                        <td width="34%;" style="font-size: 8pt;">{{ $raw->tgllahir }} </td>
                    </tr>
                    <tr>
                        <td width="17%;" style="font-size: 8pt;">Alamat</td>
                        <td width="1%;" style="font-size: 8pt;">:</td>
                        <td width="34%;" style="font-size: 8pt;">
                            {{ isset($raw->alamatkedua) ? $raw->alamatkedua : $raw->alamatlengkap }}</td>
                        <td width="17%;" style="font-size: 8pt;">Data Klinik</td>
                        <td width="1%;" style="font-size: 8pt;">:</td>
                        <td width="34%;" style="font-size: 8pt;">
                            {{ isset($raw->catatanklinis) ? $raw->catatanklinis : '-' }}
                        </td>
                    </tr>
                    <tr>
                        <td width="17%;" style="font-size: 8pt;">Jam Masuk</td>
                        <td width="1%;" style="font-size: 8pt;">:</td>
                        <td width="34%;" style="font-size: 8pt;">
                            {{ date('(H:i) d-M-Y', strtotime(isset($raw->tglverif) ? $raw->tglverif : date('Y-m-d H:i:s'))) }}
                        </td>
                        <td width="17%;" style="font-size: 8pt;">Jam Keluar</td>
                        <td width="1%;" style="font-size: 8pt;">:</td>
                        <td width="34%;" style="font-size: 8pt;">
                            {{ date('(H:i) d-M-Y', strtotime(isset($raw->tglexpertise) ? $raw->tglexpertise : date('Y-m-d H:i:s'))) }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <hr style="padding-bottom: 0px;margin-bottom: 0px">
        </td>
    </tr>

    <tr>
        {{-- OLD CODE EXPERTISE  --}}
        <td style="padding:0px">
            @php
                $exper = explode('~', $raw->keterangan);
                //dd($exper);
                $datas = [];
                foreach ($exper as $r) {
                    $datas[] = [
                        'ket' => (string) $r,
                    ];
                }
                // dd($datas);
            @endphp
            <div style="text-align: left; margin-left:7px;padding-top: 10px;padding-bottom: 10px" class="menghilangkan-jarak">
                @foreach ($datas as $e => $val)
                    <p
                        style="margin-block-start: 0px; margin-block-end: 0px; margin-inline-start: 0px; margin-inline-end: 0px;font-size:16px;padding-top: -5px;margin-top: -5px;">
                        {!! nl2br(str_replace('~', '<br/>', $val['ket'])) !!}
                    </p>
                @endforeach
            </div>
            <hr style="padding: 0px;margin: 0px;">
            {{-- <span style="float: right; margin-right:7px;font-size: 12px;color: gray;">
                di edit {{ isset($raw->tanggal) ? $raw->tanggal : '' }}
            </span> --}}
        </td>

        {{-- NEW CODE EXPERTISE --}}
        {{-- @php
                    $processedTextconclusion = nl2br(str_replace('~', '<br/>', $dataBrid[0]->expertise_text_conclusion));
                    $processedTextfinding = nl2br(str_replace('~', '<br/>', $dataBrid[0]->expertise_text_finding));
                    // $dataBridArray = $dataBrid->toArray();
                    
                @endphp
        <td style="padding:0px">
            <div style="text-align: left; margin-left:7px">
                @if (isset($dataBrid) && $dataBrid->count() > 0)
                    @foreach ($dataBrid as $e)
                        <p style="margin-block-start: 0px; margin-block-end: 0px; margin-inline-start: 0px; margin-inline-end: 0px; font-size:14px;">
                            {!! nl2br(e(str_replace('~', '<br/>', $e->expertise_text_finding ?? ''))) !!}
                            <br>
                            <br>
                            {!! nl2br(e(str_replace('~', '<br/>', $e->expertise_text_conclusion ?? ''))) !!}
                        </p>
                        <hr>
                    @endforeach
                @else
                    <p style="margin-block-start: 0px; margin-block-end: 0px; margin-inline-start: 0px; margin-inline-end: 0px; font-size:14px;">
                        {!! nl2br(e($processedTextfinding ?? '')) !!}
                        <br>
                        <br>
                        {!! nl2br(e($processedTextconclusion ?? '')) !!}
                    </p>
                @endif
            </div>
            {{-- <hr> --}}
        </td>
    </tr>

    <tr>
        <td>
            <table width="100%">
                <tr>
                    <td width="65%"></td>
                    <td width="35%" style="text-align: center">
                        <span style="font-size: 10pt;">Tanda Tangan Pemeriksa,</span>
                        <br>
                        @if (!empty($dokterrad_ttd))
                            <img src="{!! $dokterrad_ttd !!}" width="80" height="80">
                        @elseif (!empty($ttde))
                            <img src="data:image/png;base64, {!! $ttde !!}" width="80" height="80">
                        @else
                            <div style="height: 80px;width:80px"></div>
                        @endif
                        <br>
                        {{-- {{-- <span style="font-size: 10pt"><b>{{ '( ' . $dataBridDokter->dokterrad . ' )' }} --}}
                        </b><i>{{ $dataBridDokter != null ? $dataBridDokter : '' }} </i></span>
                    </td>
                </tr>

            </table>
        </td>
    </tr>

@endsection
