@extends('template.head-emr')
@section('title', 'EMR-Resume medis rawat jalan')
@section('about', 'Tindakan Rehabilitasi Medik')

@push('style')
    <style>
        span {
            font-size: 11pt;
        }
    </style>
@endpush

@section('content')

    <table width="100%" border="1" bordercolor="#000000" class="garis6">
        <tr>
            <th style="font-weight: bold;text-align: center;">No</th>
            <th style="font-weight: bold;text-align: center;">Tanggal dan Jam</th>
            <th style="font-weight: bold;text-align: center">Tindakan Rehabilitasi Medik</th>
            <th style="font-weight: bold;text-align: center">Nama Petugas</th>
        </tr>
        @if ($data['details'])
            @foreach ($data['details'] as $item)
                <tr>
                    <td>
                        <span>{{ $item['no'] }}</span>
                    </td>
                    <td>
                        <span>{{ isset($item['tanggalPemeriksaan']) ? date('d-m-Y - H:m', strtotime($item['tanggalPemeriksaan'])) : '-' }}</span>
                    </td>
                    <td>
                        <span>{{ isset($item['ket']) ? $item['ket'] : '-' }}</span>
                    </td>
                    <td>
                        <span>{{ isset($item['dokterPemeriksa']) ? $item['dokterPemeriksa']['label'] : '-' }}</span>
                    </td>
                </tr>
            @endforeach
        @else
        <tr>
            <td colspan="4" style="text-align: center">
                <span>Data Tidak Tersedia</span>
            </td>
        </tr>
        @endif

    </table>
@endsection
