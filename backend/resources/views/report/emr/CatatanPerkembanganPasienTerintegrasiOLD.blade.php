@extends('template.'.$header_use )
@section('title', 'EMR')
@section('koderme', 'XXX.XX/FORM/X/RMIK/2023/Rev. XX')
@section('about', 'CATATAN PERKEMBANGAN PASIEN TERINTEGRASI')
@push('style')
<style>
.table {
    border-collapse: collapse;
    width: 100%;
    border: 1px solid black; /* Border untuk elemen table */
}

.table th {
    border: 1px solid black; /* Border untuk seluruh sel dalam tabel */
    padding: 4px;
    font-size: 10pt;
}
.table td {
    border: 1px solid black; /* Border untuk seluruh sel dalam tabel */
    padding: 4px;
    font-size: 8pt;
    vertical-align: top;
}
.table td p {
    vertical-align: top;
}
</style>
@endpush
@section('content')
    <table width="100%" cellspacing="0" class="table">
        <tr>
            <th rowspan="2">Tgl/Jam</th>
            <th rowspan="2">Tenaga Medis</th>
            <th colspan="4">Hasil Asesmen Pasien dan Pemberian Pelayanan</th>
            <th rowspan="2">Verifikasi DPJP</th>
        </tr>
        <tr>
            <th>S</th>
            <th>O</th>
            <th>A</th>
            <th>P</th>
        </tr>
        @foreach ($data['details'] as $item)
            <tr>
                <td align="center">{{ isset($item['tgl']) ? date('Y-m-d H:i', strtotime($item['tgl'])) : ""  }}</td>
                <td align="center">
                @if(isset($item['tenagaMedis']))
                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=60x60&data={{ $item['tenagaMedis']['label'] }}"><br>
                @endif
                {{ isset($item['tenagaMedis']) ? $item['tenagaMedis']['label'] : "" }}
                </td>
                <td>{{ isset($item['S']) ? $item['S'] : ""  }}</td>
                <td>{{ isset($item['O']) ? $item['O'] : ""  }}</td>
                <td>{{ isset($item['A']) ? $item['A'] : ""  }}</td>
                <td>{{ isset($item['P']) ? $item['P'] : ""  }}</td>
                @php
                    $v_dpjp = isset($item['dokterRawatBersama']) ? $item['dokterRawatBersama']['label'] : "";
                    $tglv_dpjp = isset($item['tglVerifikasi']) ? date('Y-m-d H:i', strtotime($item['tglVerifikasi'])) : "";
                @endphp
                <td align="center">
                @if(isset($item['dokterRawatBersama']))
                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=60x60&data={{ $v_dpjp }}"><br>
                @endif
                {{ $v_dpjp  }}<br>{{ $tglv_dpjp }}
                </td>
            </tr>
        @endforeach
    </table>
@endsection

