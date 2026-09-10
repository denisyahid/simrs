@extends('template.head-emr')
@section('title', 'EMR - Lembar Monitoring Rehabilitasi Medik')
@section('about', 'Lembar Monitoring Rehabilitasi Medik')

@push('style')
    {{-- <style>
        span {
            font-size: 11pt;
        }
    </style> --}}
@endpush

@section('content')

    <table width="100%" cellspacing="0">

    <tr>
            <td width="100%">
                <table width="100%" border="1" bordercolor="#000000" class="garis6">

                    <tr bgcolor="#f08080">
                        <th rowspan="2">Tujuan Layanan</th>
                        <th rowspan="2">Kunjungan per minggu</th>
                        <th colspan="5">Jadwal Terapi</th>
                        <th rowspan="2">Keterangan</th>
                    </tr>
                    <tr bgcolor="#f08080">
                        <th>Senin</th>
                        <th>Selasa</th>
                        <th>Rabu</th>
                        <th>Kamis</th>
                        <th>Jumat</th>
                    </tr>
                    <tr bgcolor="">
                        <td align="left">Tindakan Dokter</td>
                        <td align="center">
                            {{isset($data['kunjunganPerMinggu0']) ? $data['kunjunganPerMinggu0'] : '-'}}
                        </td>
                        <td align="left">
                            <input type="checkbox" {{ isset($data['senin_0']) && $data['senin_0'] == true ? 'checked' : '' }}/>
                            <span style="font-size: 9pt;" color="#000000">{{isset($data['senPegawai_0']) ? $data['senPegawai_0']['label'] : ''}} </span>
                        </td>
                        <td align="left">
                            <input type="checkbox" {{ isset($data['selasa_0']) && $data['selasa_0'] == true ? 'checked' : '' }}/>
                            <span style="font-size: 9pt;" color="#000000">{{isset($data['selPegawai_0']) ? $data['selPegawai_0']['label'] : ''}}</span>
                        </td>
                        <td align="left">
                            <input type="checkbox" {{ isset($data['rabu_0']) && $data['rabu_0'] == true ? 'checked' : '' }}/>
                            <span style="font-size: 9pt;" color="#000000">{{isset($data['rabPegawai_0']) ? $data['rabPegawai_0']['label'] : ''}}</span>
                        </td>
                        <td align="left">
                            <input type="checkbox" {{ isset($data['kamis_0']) && $data['kamis_0'] == true ? 'checked' : '' }}/>
                            <span style="font-size: 9pt;" color="#000000">{{isset($data['kamPegawai_0']) ? $data['kamPegawai_0']['label'] : ''}}</span>
                        </td>
                        <td align="left">
                            <input type="checkbox" {{ isset($data['jumat_0']) && $data['jumat_0'] == true ? 'checked' : '' }}/>
                            <span style="font-size: 9pt;" color="#000000">{{isset($data['jumPegawai_0']) ? $data['jumPegawai_0']['label'] : ''}}</span>
                        </td>
                        <td align="left">
                            {{isset($data['keterangan_0']) ? $data['keterangan_0'] : '-'}}
                        </td>
                    </tr>
                    <tr bgcolor="">
                        <td align="left">Tindakan Fisioterapi</td>
                        <td align="center">
                            {{isset($data['kunjunganPerMinggu1']) ? $data['kunjunganPerMinggu1'] : '-'}}
                        </td>
                        <td align="left">
                            <input type="checkbox" {{ isset($data['senin_1']) && $data['senin_1'] == true ? 'checked' : '' }}/>
                            <span style="font-size: 9pt;" color="#000000">{{isset($data['senPegawai_1']) ? $data['senPegawai_1']['label'] : ''}}</span>
                        </td>
                        <td align="left">
                            <input type="checkbox" {{ isset($data['selasa_1']) && $data['selasa_1'] == true ? 'checked' : '' }}/>
                            <span style="font-size: 9pt;" color="#000000">{{isset($data['selPegawai_1']) ? $data['selPegawai_1']['label'] : ''}}</span>
                        </td>
                        <td align="left">
                            <input type="checkbox" {{ isset($data['rabu_1']) && $data['rabu_1'] == true ? 'checked' : '' }}/>
                            <span style="font-size: 9pt;" color="#000000">{{isset($data['rabPegawai_1']) ? $data['rabPegawai_1']['label'] : ''}}</span>
                        </td>
                        <td align="left">
                            <input type="checkbox" {{ isset($data['kamis_1']) && $data['kamis_1'] == true ? 'checked' : '' }}/>
                            <span style="font-size: 9pt;" color="#000000">{{isset($data['kamPegawai_1']) ? $data['kamPegawai_1']['label'] : ''}}</span>
                        </td>
                        <td align="left">
                            <input type="checkbox" {{ isset($data['jumat_1']) && $data['jumat_1'] == true ? 'checked' : '' }}/>
                            <span style="font-size: 9pt;" color="#000000">{{isset($data['jumPegawai_1']) ? $data['jumPegawai_1']['label'] : ''}}</span>
                        </td>
                        <td align="left">
                            {{isset($data['keterangan_1']) ? $data['keterangan_1'] : '-'}}
                        </td>
                    </tr>
                    <tr bgcolor="">
                        <td align="left">Spesialis Lain / Pemeriksa penunjang</td>
                        <td align="center">
                            {{isset($data['kunjunganPerMinggu2']) ? $data['kunjunganPerMinggu2'] : '-'}}
                        </td>
                        <td align="left">
                            <input type="checkbox" {{ isset($data['senin_2']) && $data['senin_2'] == true ? 'checked' : '' }}/>
                            <span style="font-size: 9pt;" color="#000000">{{isset($data['senPegawai_2']) ? $data['senPegawai_2']['label'] : ''}}</span>
                        </td>
                        <td align="left">
                            <input type="checkbox" {{ isset($data['selasa_2']) && $data['selasa_2'] == true ? 'checked' : '' }}/>
                            <span style="font-size: 9pt;" color="#000000">{{isset($data['selPegawai_2']) ? $data['selPegawai_2']['label'] : ''}}</span>
                        </td>
                        <td align="left">
                            <input type="checkbox" {{ isset($data['rabu_2']) && $data['rabu_2'] == true ? 'checked' : '' }}/>
                            <span style="font-size: 9pt;" color="#000000">{{isset($data['rabPegawai_2']) ? $data['rabPegawai_2']['label'] : ''}}</span>
                        </td>
                        <td align="left">
                            <input type="checkbox" {{ isset($data['kamis_2']) && $data['kamis_2'] == true ? 'checked' : '' }}/>
                            <span style="font-size: 9pt;" color="#000000">{{isset($data['kamPegawai_2']) ? $data['kamPegawai_2']['label'] : ''}}</span>
                        </td>
                        <td align="left">
                            <input type="checkbox" {{ isset($data['jumat_2']) && $data['jumat_2'] == true ? 'checked' : '' }}/>
                            <span style="font-size: 9pt;" color="#000000">{{isset($data['jumPegawai_2']) ? $data['jumPegawai_2']['label'] : ''}}</span>
                        </td>
                        <td align="left">
                            {{isset($data['keterangan_2']) ? $data['keterangan_2'] : '-'}}
                        </td>
                    </tr>
                    <tr bgcolor="">
                        <td align="left">Tanggal evaluasi</td>
                        <td align="center" colspan="7">
                            {{ isset($data['tglEvaluasi']) ? date('d-m-Y - H:m', strtotime($data['tglEvaluasi'])) : '-' }}
                        </td>

                    </tr>

                </table>
            </td>
        </tr>
    </table>


@endsection
