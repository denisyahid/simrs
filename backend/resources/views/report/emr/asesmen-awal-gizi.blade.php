@extends('template.head-emr')
@section('title', 'EMR')
@section('about', 'ASESMEN AWAL GIZI')

@push('style')
    <style>
        .judulLabel {
            font-weight: bold;
        }
    </style>
@endpush
@section('content')
    <table width="100%" cellspacing="0">

        <tr>
            <td width="100%" colspan="4">
                <table width="100%">
                    <tr>
                        <td width="15%" class="text-top">
                            <span style="font-size: 10pt;" color="#000000" class="judulLabel">Diagnosa Medis</span>
                        </td>
                        <td width="1%" class="text-top">
                            <span style="font-size: 10pt;" color="#000000">:</span>
                        </td>
                        <td width="69%">
                            <span style="font-size: 10pt;" color="#000000">{{  isset($data['diagnosaMedis']) ? $data['diagnosaMedis'] : '-'}}</span>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>

        <tr>
            <td width="100%" colspan="4">
                <table width="100%">
                    <tr>
                        <td width="15%" class="text-top">
                            <span style="font-size: 10pt;" color="#000000" class="judulLabel">Diagnosa Gizi</span>
                        </td>
                        <td width="1%" class="text-top">
                            <span style="font-size: 10pt;" color="#000000">:</span>
                        </td>
                        <td width="69%">
                            <table width="100%">
                                <tr>
                                    <td>
                                        <span style="font-size: 10pt;" color="#000000">
                                            {{ isset($data['diagnosaGizi_1']['nama']) ? $data['diagnosaGizi_1']['nama'] : '-' }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <span style="font-size: 10pt;" color="#000000">
                                            {{ isset($data['diagnosaGizi_2']['nama']) ? $data['diagnosaGizi_2']['nama'] : '-' }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <span style="font-size: 10pt;" color="#000000">
                                            {{ isset($data['diagnosaGizi_3']['nama']) ? $data['diagnosaGizi_3']['nama'] : '-' }}</span>
                                    </td>
                                </tr>

                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>

        <tr>
            <td width="100%" colspan="4"><span style="font-size: 10pt;" class="judulLabel" color="#000000">Asesmen
                    Gizi/Pengkajian</span></td>
        </tr>
        <tr>
            <td width="100%" colspan="6">
                <table width="100%" class="text-top">
                    <tr>
                        <td width="10%">
                            <span style="font-size: 10pt;" color="#000000">BB</span>
                        </td>
                        <td width="1%">
                            <span style="font-size: 10pt;" color="#000000">:</span>
                        </td>
                        <td width="20%">
                            <span style="font-size: 10pt;" color="#000000">
                                {{ isset($data['BB']) ? $data['BB'] : '-' }}
                            </span>
                            <span style="font-size: 10pt;" color="#000000">Kg</span>
                        </td>

                        <td width="10%">
                            <span style="font-size: 10pt;" color="#000000">TB</span>
                        </td>
                        <td width="1%">
                            <span style="font-size: 10pt;" color="#000000">:</span>
                        </td>
                        <td width="20%">
                            <span style="font-size: 10pt;" color="#000000">
                                {{ isset($data['TB']) ? $data['TB'] : '-' }}
                            </span>
                            <span style="font-size: 10pt;" color="#000000">cm</span>
                        </td>

                        <td width="10%">
                            <span style="font-size: 10pt;" color="#000000">IMT</span>
                        </td>
                        <td width="1%">
                            <span style="font-size: 10pt;" color="#000000">:</span>
                        </td>
                        <td width="20%">
                            <span style="font-size: 10pt;" color="#000000">
                                {{ isset($data['IMT']) ? $data['IMT'] : '-' }}
                            </span>
                        </td>

                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td width="100%" colspan="6">
                <table width="100%" class="text-top">
                    <tr>
                        <td width="10%">
                            <span style="font-size: 10pt;" color="#000000">Tinggi Lutut</span>
                        </td>
                        <td width="1%">
                            <span style="font-size: 10pt;" color="#000000">:</span>
                        </td>
                        <td width="20%">
                            <span style="font-size: 10pt;" color="#000000" id="id_3104324">
                                {{ isset($data['TL']) ? $data['TL'] : '-' }}
                            </span>
                        </td>

                        <td width="10%">
                            <span style="font-size: 10pt;" color="#000000">LLA</span>
                        </td>
                        <td width="1%">
                            <span style="font-size: 10pt;" color="#000000">:</span>
                        </td>
                        <td width="20%">
                            <span style="font-size: 10pt;" color="#000000" id="id_3104325">
                                {{ isset($data['LLA']) ? $data['LLA'] : '-' }}
                            </span>
                        </td>

                        <td width="10%">
                            <span style="font-size: 10pt;" color="#000000">&nbsp;</span>
                        </td>
                        <td width="1%">
                            <span style="font-size: 10pt;" color="#000000">&nbsp;</span>
                        </td>
                        <td width="20%">
                            <span style="font-size: 10pt;" color="#000000" id="id_3104325">&nbsp;</span>
                        </td>

                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td width="100%" colspan="4">
                <table width="100%">
                    <tr>
                        <td width="15%" class="text-top">
                            <span style="font-size: 10pt;" color="#000000">Biokimia</span>
                        </td>
                        <td width="1%" class="text-top">
                            <span style="font-size: 10pt;" color="#000000">:</span>
                        </td>
                        <td width="69%">
                            <span style="font-size: 10pt;" color="#000000" id="id_3104326">
                                {{ isset($data['Biokimia']) ? $data['Biokimia'] : '-' }}
                            </span>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td width="100%" colspan="4">
                <table width="100%">
                    <tr>
                        <td width="15%" class="text-top">
                            <span style="font-size: 10pt;" color="#000000">Klinik/Fisik</span>
                        </td>
                        <td width="1%" class="text-top">
                            <span style="font-size: 10pt;" color="#000000">:</span>
                        </td>
                        <td width="69%">
                            <span style="font-size: 10pt;" color="#000000" id="id_3104327">
                                {{ isset($data['fisik']) ? $data['fisik'] : '-' }}
                            </span>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td width="100%" colspan="4"><span style="font-size: 10pt;" class="judulLabel" color="#000000">Riwayat
                    Gizi</span></td>
        </tr>
        <tr>
            <td width="100%" colspan="4">
                <table width="100%">
                    <tr>
                        <td width="15%" class="text-top">
                            <span style="font-size: 10pt;" color="#000000">Pola Makan</span>
                        </td>
                        <td width="1%" class="text-top">
                            <span style="font-size: 10pt;" color="#000000">:</span>
                        </td>
                        <td width="69%">
                            <span style="font-size: 10pt;" color="#000000" id="id_3104328">
                                {{ isset($data['polaMakan']) ? $data['polaMakan'] : '-' }}
                            </span>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td width="100%" colspan="4">
                <table width="100%">
                    <tr>
                        <td width="15%" class="text-top">
                            <span style="font-size: 10pt;" color="#000000" class="judulLabel">Asupan Makan</span>
                        </td>
                        <td width="1%" class="text-top">
                            <span style="font-size: 10pt;" color="#000000">:</span>
                        </td>
                        <td width="69%">
                            <span style="font-size: 10pt;" color="#000000">
                                {{ isset($data['asupanMakan']) ? $data['asupanMakan'] : '-' }}
                            </span>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td width="100%" colspan="4"><span style="font-size: 10pt;" class="judulLabel" color="#000000">Riwayat
                    Personal</span></td>
        </tr>
        <tr>
            <td width="100%" colspan="4">
                <table width="100%">
                    <tr>
                        <td width="100%">
                            <span style="font-size: 10pt;" color="#000000" id="id_3104330">
                                {{ isset($data['riwayatPersonal']) ? $data['riwayatPersonal'] : '-' }}
                            </span>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>

        <tr>
            <td width="100%" colspan="4"><span style="font-size: 10pt;" class="judulLabel"
                    color="#000000">Diagnosis Gizi/Masalah</span></td>
        </tr>
        <tr>
            <td width="100%" colspan="4">
                <table width="100%">
                    <tr>
                        <td width="100%">
                            <span style="font-size: 10pt;" color="#000000">
                                {{ isset($data['diagnosaGiziAtauMasalah']) ? $data['diagnosaGiziAtauMasalah'] : '-' }}
                            </span>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>

        <tr>
            <td width="100%" colspan="4"><span style="font-size: 10pt;" class="judulLabel"
                    color="#000000">Intervensi Gizi</span></td>
        </tr>
        <tr>
            <td width="100%" colspan="4">
                <table width="100%">
                    <tr>
                        <td width="100%">
                            <span style="font-size: 10pt;" color="#000000">
                                {{ isset($data['intervensiGizi']) ? $data['intervensiGizi'] : '-' }}
                            </span>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>

        <tr>
            <td width="100%" colspan="4"><span style="font-size: 10pt;" class="judulLabel"
                    color="#000000">Monitoring dan Evaluasi</span></td>
        </tr>
        <tr>
            <td width="100%" colspan="4">
                <table width="100%">
                    <tr>
                        <td width="100%">
                            <span style="font-size: 10pt;" color="#000000">
                                {{ isset($data['monitoring&Evaluasi']) ? $data['monitoring&Evaluasi'] : '-' }}
                            </span>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>

    </table>

@endsection
