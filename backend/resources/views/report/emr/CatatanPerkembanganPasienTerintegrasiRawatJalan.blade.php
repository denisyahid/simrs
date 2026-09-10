@extends('template.'.$header_use)
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

@php
// dd($data);
@endphp
@section('content')
    <table width="100%" cellspacing="0" class="table">


        @foreach ($data['details'] as $item)
            <tr>
                <th>Tgl/ Jam</th>
                <td align="center">{{ isset($item['tgl']) ? date('Y-m-d H:i', strtotime($item['tgl'])) : ""  }}</td>
            </tr>
            <tr>
                <th ><span style="font-size:20pt;font-weight:bold">S</span></th>
                <td>{{ isset($item['hasilSub']) ? $item['hasilSub'] : ""  }}</td>
            </tr>
            <tr>
                <th ><span style="font-size:20pt;font-weight:bold">O</span></th>
                <td>{{ isset($item['hasilObj']) ? $item['hasilObj'] : ""  }}</td>
            </tr>
            <tr>
                <th ><span style="font-size:20pt;font-weight:bold">A</span></th>
                <td>{{ isset($item['diagnosaKeperawatan']) ? $item['diagnosaKeperawatan'] : ""  }}</td>
            </tr>
            <tr>
                <th ><span style="font-size:20pt;font-weight:bold">P</span></th>
                <td>{{ isset($item['hasilPe']) ? $item['hasilPe'] : ""  }}</td>
            </tr>
            @php
            $v_dpjp = isset($item['dokterDPJP']) ? $item['dokterDPJP']['label'] : "";
            $v_tenagaMedis =  isset($item['tenagaMedis']) ? $item['tenagaMedis']['label'] : "";
            $tglv_dpjp = isset($item['tglVerifikasi']) ? date('Y-m-d H:i', strtotime($item['tglVerifikasi'])) : "";
           @endphp
            <tr>
                <th>DPJP</th>
                <td style="text-align: center">
                    @if(isset($item['dokterDPJP']))
                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=60x60&data={{ $v_dpjp }}"><br>
                    @endif
                    {{ $v_dpjp  }}
               </td>
            </tr>

            <tr>
                <th>Perawat</th>
                <td style="text-align: center">
                    @if(isset($item['dokterDPJP']))
                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=60x60&data={{ $v_dpjp }}"><br>
                    @endif
                    {{ $v_tenagaMedis  }}
                 </td>
            </tr>







            </tr>
        @endforeach
    </table>
@endsection

