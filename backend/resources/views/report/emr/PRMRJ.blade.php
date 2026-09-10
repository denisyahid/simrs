@extends('template.head-emr')
@section('title', 'EMR - PRMRJ')
@section('about', 'PRMRJ')

@push('style')
@endpush

@section('content')

    <table width="100%" cellspacing="0">
        <tr>
            <td width="100%">
                <table width="100%" border="1" bordercolor="#000000" class="garis6">
                    <thead>
                        <tr bgcolor="#f08080">
                            <th>No</th>
                            <th>TGL Kunjung</th>
                            <th>Poli</th>
                            <th>Diagnosis</th>
                            <th>Penatalaksaan</th>
                            <th>Riwayat Rawat Inap/Prosedur Operasi</th>
                            <th>Nama & TTD Petugas Kesehatan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data['details'] as $item)
                            <tr>
                                <td>
                                    <span>{{ $item['no'] }}</span>
                                </td>
                                <td>
                                    <span>{{ isset($item['tglBerkunjung']) ? date('d-m-Y', strtotime($item['tglBerkunjung'])) : '-' }}</span>
                                </td>
                                <td>
                                    <span>{{ isset($item['poli']) ? $item['poli']['label'] : '-' }}</span>
                                </td>
                                <td>
                                    <span>{{ isset($item['diagnosa']) ? $item['diagnosa']['label'] : '-' }}</span>
                                </td>
                                <td>
                                    <span>{{ isset($item['penatalaksaan']) ? $item['penatalaksaan'] : '-' }}</span>
                                </td>
                                <td>
                                    <span>{{ isset($item['prosedurOperasi']) ? $item['prosedurOperasi'] : '-' }}</span>
                                </td>
                                <td>
                                    <span>{{ isset($item['petugas']) ? $item['petugas']['label'] : '-' }}</span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </td>
        </tr>

        <br>
        <tr style="text-align: right;position:relative;top:1rem">
            <td width="100%">
                <span style="font-size: 9pt;font-weight: bold;" color="#000000">Tanggal dan jam</span>
                <br>
                <span style="position: relative;top: 10px;">{{ isset($data['tglKomit']) ? date('d-m-Y - H:m', strtotime($data['tglKomit'])) : '-' }}</span>
            </td>
        </tr>
        <tr style="text-align: right;position:relative;top:2.5rem">
            <td width="100%">
                <span style="font-size: 9pt;font-weight: bold;" color="#000000">Petugas / dokter</span>
                <span style="font-size: 9pt;" color="#000000">
                </span>
                <p>{{ isset($data['petugasOrDokter']) ? $data['petugasOrDokter']['label'] : '-' }}</p>
            </td>
        </tr>

    </table>

@endsection
