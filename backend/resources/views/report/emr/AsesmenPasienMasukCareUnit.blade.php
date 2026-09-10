@extends('template.head-emr')
@section('title', 'EMR-Resume medis rawat jalan')
@section('about', 'Asesmen Pasien Masuk Care Unit')

@push('style')
    <style>
        span {
            font-size: 11pt;
        }
    </style>
@endpush

@section('content')

    <table width="100%">
        <tr>
            <td class="text-top" width="5%">
                <span style="font-size: 10pt;" color="#000000">Yth</span>
            </td>
            <td class="text-top">
                <span style="font-size: 10pt;" color="#000000">:</span>
            </td>
            <td>
                <span style="font-size: 10pt;" color="#000000">
                    <b>{{isset($data['dokter']) ? $data['dokter']['label'] : '-'}}</b>
                </span>
            </td>
        </tr>
    </table>

    <table width="100%">
        <tr>
            <td class="text-top" width="70%">
                <span style="font-size: 10pt;" color="#000000">Mohon perawatan pasien selanjutnya di ruangan</span>
            </td>
            <td class="text-top" width="1%">
                <span style="font-size: 10pt;" color="#000000">:</span>
            </td>
            <td style="text-align: center;">
                <span style="font-size: 10pt;" color="#000000">
                    {{isset($data['namaRuangan']) ? $data['namaRuangan'] : '-'}}
                </span>
            </td>
        </tr>
    </table>
    <table width="100%">
        <tr>
            <td width="30%" class="text-top">
                <span style="font-size: 10pt;" color="#000000">Keluhan saat ini</span>
            </td>
            <td width="1%" class="text-top">
                <span style="font-size: 10pt;" color="#000000">:</span>
            </td>
            <td width="69%">
                <span style="font-size: 10pt;" color="#000000">
                    {{isset($data['Keluhan']) ? $data['Keluhan'] : '-'}}
                </span>

            </td>
        </tr>

        <tr>
            <td width="30%" class="text-top">
                <span style="font-size: 10pt;" color="#000000">Pemeriksaan Penunjang</span>
            </td>
            <td width="1%" class="text-top">
                <span style="font-size: 10pt;" color="#000000">:</span>
            </td>
            <td width="69%">
                <span style="font-size: 10pt;" color="#000000">
                    {{isset($data['pemeriksaanPenunjang']) ? $data['pemeriksaanPenunjang'] : '-'}}
                </span>

            </td>
        </tr>

        <tr>
            <td width="30%" class="text-top">
                <span style="font-size: 10pt;" color="#000000">Diagnosa</span>
            </td>
            <td width="1%" class="text-top">
                <span style="font-size: 10pt;" color="#000000">:</span>
            </td>
            <td width="69%">
                <span style="font-size: 10pt;" color="#000000">
                    {{isset($data['namadiagnosa']) ? $data['namadiagnosa'] : '-'}}
                </span>

            </td>
        </tr>

        <tr>
            <td width="30%" class="text-top">
                <span style="font-size: 10pt;" color="#000000">Tindakan yang dilakukan diruangan</span>
            </td>
            <td width="1%" class="text-top">
                <span style="font-size: 10pt;" color="#000000">:</span>
            </td>
            <td width="69%">
                <span style="font-size: 10pt;" color="#000000">
                    {{isset($data['tindakan']) ? $data['tindakan'] : '-'}}
                </span>

            </td>
        </tr>



    </table>

    <table width="100%" cellspacing="0">

        <tr>
            <td width="100%">
                <table width="100%" border="1" bordercolor="#000000" class="garis6">
                    <tr bgcolor="#f08080">
                        <th colspan="4">Terapi / pengobatan yang diberikan</th>
                    </tr>
                    <tr bgcolor="#f08080">
                        <th>No</th>
                        <th>Nama Obat</th>
                        <th>Aturan Pakai</th>
                        <th>Rute Pemberian</th>
                    </tr>
                    @if (isset($data['details']))
                        @foreach($data['details'] as $item)
                        <tr bgcolor="">
                            <td align="center">{{$item['no']}}</td>
                            <td align="left">
                                {{isset($item['namaObat']) ? $item['namaObat'] : '-'}}
                            </td>
                            <td align="left">
                                {{isset($item['aturanPakai']) ? $item['aturanPakai'] : '-'}}
                            </td>
                            <td align="center">
                                {{isset($item['rutePemberian']) ? $item['rutePemberian'] : '-'}}
                            </td>
                        </tr>
                        @endforeach    
                    @else
                    <tr>
                        <td colspan="4" align="center">Terapi / Pengobatan belum diberikan</td>
                    </tr>
                    @endif
                    
                </table>
            </td>
        </tr>

    </table>
    <table width="100%">
        <tr>
            <td width="20%" class="text-top">
                <span style="font-size: 10pt;" color="#000000">Alasan masuk ICU</span>
            </td>
            <td width="1%" class="text-top">
                <span style="font-size: 10pt;" color="#000000">:</span>
            </td>
            <td width="69%">
                <span style="font-size: 10pt;" color="#000000">
                    {{isset($data['alasanMasukICU']) ? $data['alasanMasukICU'] : '-'}}
                </span>
            </td>
        </tr>
    </table>

    <table width="100%">

        <tr>
            <td>
                <span style="font-size: 10pt;" color="#000000">PRIORITAS I</span>
            </td>
        </tr>
        <tr>

            <td width="30%">
                <input type="checkbox" {{ isset($data['prioritas_0']) && $data['prioritas_0']['code'] == 'SKR' ? 'checked' : '' }}/>
                <span style="font-size: 10pt;" color="#000000">Sindrom Koroner Akut</span>
            </td>
            <td width="30%">
                <input type="checkbox" {{ isset($data['prioritas_1']) && $data['prioritas_1']['code'] == 'ACM' ? 'checked' : '' }}/>
                <span style="font-size: 10pt;" color="#000000">Aritimia Cardis Malignan</span>
            </td>
            <td width="30%">
                <input type="checkbox" {{ isset($data['prioritas_2']) && $data['prioritas_2']['code'] == 'GN' ? 'checked' : '' }}/>
                <span style="font-size: 10pt;" color="#000000">Gagal Nafas</span>
            </td>
        </tr>
        <tr>
            <td width="30%">
                <input type="checkbox" {{ isset($data['prioritas_3']) && $data['prioritas_3']['code'] == 'GS' ? 'checked' : '' }}/>
                <span style="font-size: 10pt;" color="#000000">Gagal Sirkulasi</span>
            </td>
            <td width="30%">
                <input type="checkbox" {{ isset($data['prioritas_4']) && $data['prioritas_4']['code'] == 'IO' ? 'checked' : '' }}/>
                <span style="font-size: 10pt;" color="#000000">Intoksikasi Obat/Drug Over Dosis</span>
            </td>

        </tr>

    </table>
    <table width="100%">
        <tr>
            <td width="20%" class="text-top">
                <span style="font-size: 10pt;" color="#000000">Alasan masuk HCU</span>
            </td>
            <td width="1%" class="text-top">
                <span style="font-size: 10pt;" color="#000000">:</span>
            </td>
            <td width="69%">
                <span style="font-size: 10pt;" color="#000000">
                    {{isset($data['alasanMasukHCU']) ? $data['alasanMasukHCU'] : '-'}}
                </span>
            </td>
        </tr>
        <tr>
            <td width="20%" class="text-top">
                <span style="font-size: 10pt;" color="#000000">Prioritas II</span>
            </td>
            <td width="1%" class="text-top">
                <span style="font-size: 10pt;" color="#000000">:</span>
            </td>
            <td width="69%">
                <input type="checkbox" {{ isset($data['prioritas2_0']) && $data['prioritas2_0']['code'] == 'MMK' ? 'checked' : '' }}/>
                <span style="font-size: 10pt;" color="#000000">Memerlukan monitoring ketat karena ancaman</span>
            </td>
        </tr>
        <tr>
            <td width="20%" class="text-top">
                <span style="font-size: 10pt;" color="#000000">Prioritas III</span>
            </td>
            <td width="1%" class="text-top">
                <span style="font-size: 10pt;" color="#000000">:</span>
            </td>
            <td width="69%">
                <input type="checkbox" {{ isset($data['prioritas3_0']) && $data['prioritas3_0']['code'] == 'PKS' ? 'checked' : '' }}/>
                <span style="font-size: 10pt;" color="#000000">Penyakit kronis yang stabil</span>
            </td>
        </tr>
        <tr>
            <td width="100%" colspan="4" height="10"></td>
        </tr>
    </table>

    <table width="100%">
        <tr>
            <td class="text-top" width="20%">
                <span style="font-size: 10pt;" color="#000000">Dokter pengirim</span>
            </td>
            <td class="text-top" width="1%">
                <span style="font-size: 10pt;" color="#000000">:</span>
            </td>
            <td width="69%">
                <span style="font-size: 10pt;" color="#000000">
                    {{isset($data['dokterPengirim']) ? $data['dokterPengirim']['label'] : '-'}}
                </span>
            </td>
        </tr>
        <tr>
            <td class="text-top" width="20%">
                <span style="font-size: 10pt;" color="#000000">DPJP</span>
            </td>
            <td class="text-top" width="1%">
                <span style="font-size: 10pt;" color="#000000">:</span>
            </td>
            <td width="69%">
                <span style="font-size: 10pt;" color="#000000">
                    {{isset($data['dokterDPJP']) ? $data['dokterDPJP'] : '-'}}
                </span>
            </td>
        </tr>
        <tr>
            <td class="text-top" width="20%">
                <span style="font-size: 10pt;" color="#000000">Petugas penerima</span>
            </td>
            <td class="text-top" width="1%">
                <span style="font-size: 10pt;" color="#000000">:</span>
            </td>
            <td width="69%">
                <span style="font-size: 10pt;" color="#000000">
                    {{isset($data['petugasPE']) ? $data['petugasPE']['label'] : '-'}}
                </span>
            </td>
        </tr>
    </table>

@endsection

