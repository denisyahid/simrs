@extends('template.head-emr')
@section('title', 'Asesmen Medis Rawat Jalan')
@section('about', 'Asesmen Medis Pasien Rawat Jalan')

@push('style')
    <style>
        .child {
            position: relative;
            left: 15px;
            right: 15px;
        }

        .child-2 {
            position: relative;
            left: 28px;
            right: 28px;
        }

        .child-3 {
            position: relative;
            left: 37px;
            right: 37px;
        }
    </style>
@endpush

@section('content')

    <table width="100%" cellspacing="0">
        <tr>
            <td width="100%" colspan="4">
                <span style="font-size: 10pt;" class="judulLabel" color="#000000">A. Anamnesis (S)</span>
            </td>
        </tr>
        <tr class="child">
            <td width="100%" colspan="4">
                <table width="100%">
                    <tr>
                        <td width="30%" class="text-top">
                            <span style="font-size: 10pt;" color="#000000">Keluhan</span>
                        </td>
                        <td width="1%" class="text-top">
                            <span style="font-size: 10pt;" color="#000000">:</span>
                        </td>
                        <td width="69%">
                            <span style="font-size: 10pt;"
                                color="#000000">{{ isset($data['keluhanUtama']) ? $data['keluhanUtama'] : '-' }}</span>

                        </td>
                    </tr>
                    <tr>
                        <td width="30%" class="text-top">
                            <span style="font-size: 10pt;" color="#000000">Riwayat Penyakit Sekarang</span>
                        </td>
                        <td width="1% " class="text-top">
                            <span style="font-size: 10pt;" color="#000000">:</span>
                        </td>
                        <td width="69%">
                            <span style="font-size: 10pt;"
                                color="#000000">{{ isset($data['riwayatPenyakitSekarang']) ? $data['riwayatPenyakitSekarang'] : '-' }}</span>
                        </td>
                    </tr>
                    <tr>
                        <td width="30%" class="text-top">
                            <span style="font-size: 10pt;" color="#000000">Riwayat Penyakit Dahulu</span>
                        </td>
                        <td width="1%" class="text-top">
                            <span style="font-size: 10pt;" color="#000000">:</span>
                        </td>
                        <td width="69%">
                            <span style="font-size: 10pt;"
                                color="#000000">{{ isset($data['riwayatPenyakitDahulu']) ? $data['riwayatPenyakitDahulu'] : '-' }}</span>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr class="child">
            <td width="100%" colspan="4">
                <span style="font-size: 9pt;" class="judulLabel" color="#000000">Hipertensi</span>
            </td>
        </tr>
        <tr class="child">
            <td width="100%" colspan="4">
                <table width="100%">
                    <tr>
                        <td width="20%">
                            <input type="checkbox"
                                {{ isset($data['hipertensi']) && $data['hipertensi'] == 'Ya' ? 'checked' : '' }} />
                            <span style="font-size: 9pt;" color="#000000">Ya</span>
                        </td>
                        <td width="20%">
                            <input type="checkbox"
                                {{ isset($data['hiperKontrol']) && $data['hiperKontrol'] == 'Terkontrol' ? 'checked' : '' }} />
                            <span style="font-size: 9pt;" color="#000000">Terkontrol</span>
                        </td>
                        <td width="20%">
                            <input type="checkbox"
                                {{ isset($data['hiperKontrol']) && $data['hiperKontrol'] == 'Tidak Terkontrol' ? 'checked' : '' }} />
                            <span style="font-size: 9pt;" color="#000000">Tidak Terkontrol</span>
                        </td>
                        <td width="20%">
                            <input type="checkbox"
                                {{ isset($data['hipertensi']) && $data['hipertensi'] == 'Tidak' ? 'checked' : '' }} />
                            <span style="font-size: 9pt;" color="#000000">Tidak</span>
                        </td>

                    </tr>
                </table>
            </td>
        </tr>

        <tr class="child">
            <td width="100%" colspan="4">
                <span style="font-size: 9pt;" class="judulLabel" color="#000000">Merokok</span>
            </td>
        </tr>
        <tr class="child">
            <td width="100%" colspan="4">
                <table width="100%">
                    <tr>
                        <td width="60%">
                            <input type="checkbox"
                                {{ isset($data['merokok']) && $data['merokok'] == 'Ya' ? 'checked' : '' }} />
                            <span style="font-size: 9pt;" color="#000000">Ya, (berapa pack/hari, berapa lama,berhenti
                                kapan), </span>
                            <span style="font-size: 9pt;"
                                color="#000000">{{ isset($data['ketMerokok']) ? $data['ketMerokok'] : '-' }}</span>
                        </td>
                        <td width="20%">
                            <input type="checkbox"
                                {{ isset($data['merokok']) && $data['merokok'] == 'Tidak' ? 'checked' : '' }} />
                            <span style="font-size: 9pt;" color="#000000">Tidak</span>
                        </td>

                    </tr>
                </table>
            </td>
        </tr>
        <tr class="child">
            <td width="100%" colspan="4">
                <span style="font-size: 9pt;" class="judulLabel" color="#000000">Diabetes Melitus</span>
            </td>
        </tr>
        <tr class="child">
            <td width="100%" colspan="4">
                <table width="100%">
                    <tr>
                        <td width="20%">
                            <input type="checkbox"
                                {{ isset($data['diabetes']) && $data['diabetes'] == 'Ya' ? 'checked' : '' }} />
                            <span style="font-size: 9pt;" color="#000000">Ya</span>
                        </td>
                        <td width="20%">
                            <input type="checkbox"
                                {{ isset($data['diabetesKontrol']) && $data['diabetesKontrol'] == 'Terkontrol' ? 'checked' : '' }} />
                            <span style="font-size: 9pt;" color="#000000">Terkontrol</span>
                        </td>
                        <td width="20%">
                            <input type="checkbox"
                                {{ isset($data['diabetesKontrol']) && $data['diabetesKontrol'] == 'Tidak Terkontrol' ? 'checked' : '' }} />
                            <span style="font-size: 9pt;" color="#000000">Tidak Terkontrol</span>
                        </td>
                        <td width="20%">
                            <input type="checkbox"
                                {{ isset($data['diabetes']) && $data['diabetes'] == 'Tidak' ? 'checked' : '' }} />
                            <span style="font-size: 9pt;" color="#000000">Tidak</span>
                        </td>

                    </tr>
                </table>
            </td>
        </tr>
        <tr class="child">
            <td width="100%" colspan="4">
                <span style="font-size: 9pt;" class="judulLabel" color="#000000">Dyslipidemia (kelainan kolestrol darah)
                </span>
            </td>
        </tr>
        <tr class="child">
            <td width="100%" colspan="4">
                <table width="100%">
                    <tr>
                        <td width="20%">
                            <input type="checkbox"
                                {{ isset($data['dyslipidemia']) && $data['dyslipidemia'] == 'Ya' ? 'checked' : '' }} />
                            <span style="font-size: 9pt;" color="#000000">Ya</span>
                        </td>
                        <td width="20%">
                            <input type="checkbox"
                                {{ isset($data['dyslipidemiaKontrol']) && $data['dyslipidemiaKontrol'] == 'Terkontrol' ? 'checked' : '' }} />
                            <span style="font-size: 9pt;" color="#000000">Terkontrol</span>
                        </td>
                        <td width="20%">
                            <input type="checkbox"
                                {{ isset($data['dyslipidemiaKontrol']) && $data['dyslipidemiaKontrol'] == 'Tidak Terkontrol' ? 'checked' : '' }} />
                            <span style="font-size: 9pt;" color="#000000">Tidak Terkontrol</span>
                        </td>
                        <td width="20%">
                            <input type="checkbox"
                                {{ isset($data['dyslipidemia']) && $data['dyslipidemia'] == 'Tidak' ? 'checked' : '' }} />
                            <span style="font-size: 9pt;" color="#000000">Tidak</span>
                        </td>

                    </tr>
                </table>
            </td>
        </tr>
        <tr class="child">
            <td width="100%" colspan="4">
                <span style="font-size: 9pt;" class="judulLabel" color="#000000">Riwayat serangan jantung dini pada orang
                    tua (pria <55 tahun atau wanita <65 tahun)</span>
            </td>
        </tr>
        <tr class="child">
            <td width="100%" colspan="4">
                <table width="100%">
                    <tr>
                        <td width="20%">
                            <input type="checkbox"
                                {{ isset($data['riwayatJantung']) && $data['riwayatJantung'] == 'Ya' ? 'checked' : '' }} />
                            <span style="font-size: 9pt;" color="#000000">Ya</span>
                        </td>
                        <td width="20%">
                            <input type="checkbox"
                                {{ isset($data['riwayatJantung']) && $data['riwayatJantung'] == 'Tidak' ? 'checked' : '' }} />
                            <span style="font-size: 9pt;" color="#000000">Tidak</span>
                        </td>
                        <td width="20%">

                        </td>
                        <td width="20%">

                        </td>

                    </tr>
                </table>
            </td>
        </tr>

        <tr class="child">
            <td width="100%" colspan="4">
                <table width="100%" class="text-top">
                    <tr style="vertical-align: top;">
                        <td width="20%">
                            <span style="font-size: 10pt;" color="#000000">Riwayat Pengobatan</span>
                        </td>
                        <td width="1%">
                            <span style="font-size: 10pt;" color="#000000">:</span>
                        </td>
                        <td width="69%">
                            <span style="font-size: 10pt;"
                                color="#000000">{{ isset($data['riwayatPengobatan']) ? $data['riwayatPengobatan'] : '-' }}</span>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <table width="100%" cellspacing="0">
        <tr>
            <td width="100%" colspan="4">
                <span style="font-size: 10pt;" class="judulLabel" color="#000000">B. Pemeriksaan Fisik (O)</span>
            </td>
        </tr>
        <tr class="child">
            <td width="100%" colspan="4">
                <span style="font-size: 10pt;" class="judulLabel" color="#000000">1. Keadaan Umum</span>
            </td>
        </tr>
        <tr class="child-2">
            <td width="100%" colspan="4">
                <table width="100%">
                    <tr>
                        <td width="20%">
                            <input type="checkbox"
                                {{ isset($data['keadaanUmum']) && $data['keadaanUmum'] == 'Baik' ? 'checked' : '' }} />
                            <span style="font-size: 9pt;" color="#000000">Baik</span>
                        </td>
                        <td width="20%">
                            <input type="checkbox"
                                {{ isset($data['keadaanUmum']) && $data['keadaanUmum'] == 'Sakit Ringan' ? 'checked' : '' }} />
                            <span style="font-size: 9pt;" color="#000000">Sakit Ringan</span>
                        </td>
                        <td width="20%">
                            <input type="checkbox"
                                {{ isset($data['keadaanUmum']) && $data['keadaanUmum'] == 'Sakit Sedang' ? 'checked' : '' }} />
                            <span style="font-size: 9pt;" color="#000000">Sakit Sedang</span>
                        </td>
                        <td width="20%">
                            <input type="checkbox"
                                {{ isset($data['keadaanUmum']) && $data['keadaanUmum'] == 'Sakit Berat' ? 'checked' : '' }} />
                            <span style="font-size: 9pt;" color="#000000">Sakit Berat</span>
                        </td>

                    </tr>
                </table>
            </td>
        </tr>
        <tr class="child">
            <td width="100%" colspan="4">
                <span style="font-size: 10pt;" class="judulLabel" color="#000000">2. Tanda Vital</span>
            </td>
        </tr>
        <tr class="child-2">
            <td width="100%" colspan="6">
                <table width="100%" class="text-top">
                    <tr>
                        <td width="10%">
                            <span style="font-size: 9pt;" color="#000000">Tekanan Darah</span>
                        </td>
                        <td width="1%">
                            <span style="font-size: 9pt;" color="#000000">:</span>
                        </td>
                        <td width="20%">
                            <span style="font-size: 9pt;" color="#000000">
                                {{ isset($data['tekananDarah']) ? $data['tekananDarah'] : '-' }} mmHg</span>
                        </td>

                        <td width="10%">
                            <span style="font-size: 9pt;" color="#000000">Frekuensi Nadi</span>
                        </td>
                        <td width="1%">
                            <span style="font-size: 9pt;" color="#000000">:</span>
                        </td>
                        <td width="20%">
                            <span style="font-size: 9pt;"
                                color="#000000">{{ isset($data['frekuensiNadi']) ? $data['frekuensiNadi'] : '-' }} </span>
                            <span style="font-size: 9pt;" color="#000000">x/menit</span>
                        </td>


                    </tr>
                </table>
                <table width="100%" class="text-top">
                    <tr>
                        <td width="10%">
                            <span style="font-size: 9pt;" color="#000000">Suhu</span>
                        </td>
                        <td width="1%">
                            <span style="font-size: 9pt;" color="#000000">:</span>
                        </td>
                        <td width="20%">
                            <span style="font-size: 9pt;"
                                color="#000000">{{ isset($data['suhu']) ? $data['suhu'] : '-' }}</span>
                            <span style="font-size: 9pt;" color="#000000">°C</span>
                        </td>

                        <td width="10%">
                            <span style="font-size: 9pt;" color="#000000">Frekuensi Nafas</span>
                        </td>
                        <td width="1%">
                            <span style="font-size: 9pt;" color="#000000">:</span>
                        </td>
                        <td width="20%">
                            <span style="font-size: 9pt;"
                                color="#000000">{{ isset($data['frekuensiNafas']) ? $data['frekuensiNafas'] : '-' }}</span>
                            <span style="font-size: 9pt;" color="#000000">x/menit</span>
                        </td>

                    </tr>
                </table>
                <table width="100%" class="text-top">
                    <tr>
                        <td width="10%">
                            <span style="font-size: 9pt;" color="#000000">Skor Nyeri</span>
                        </td>
                        <td width="1%">
                            <span style="font-size: 9pt;" color="#000000">:</span>
                        </td>
                        <td width="20%">
                            <span style="font-size: 9pt;"
                                color="#000000">{{ isset($data['skorNyeri']) ? $data['skorNyeri'] : '-' }}</span>

                        </td>

                        <td width="10%">
                            <span style="font-size: 9pt;" color="#000000"></span>
                        </td>
                        <td width="1%">
                            <span style="font-size: 9pt;" color="#000000"></span>
                        </td>
                        <td width="20%">
                            <span style="font-size: 9pt;" color="#000000"></span>
                        </td>

                    </tr>
                </table>
            </td>
        </tr>
        <tr class="child">
            <td width="100%" colspan="4">
                <span style="font-size: 10pt;" class="judulLabel" color="#000000">3. Antropometri</span>
            </td>
        </tr>
        <tr class="child-2">
            <td width="100%" colspan="6">
                <table width="100%" class="text-top">
                    <tr>
                        <td width="10%">
                            <span style="font-size: 9pt;" color="#000000">Berat Badan</span>
                        </td>
                        <td width="1%">
                            <span style="font-size: 9pt;" color="#000000">:</span>
                        </td>
                        <td width="20%">
                            <span style="font-size: 9pt;"
                                color="#000000">{{ isset($data['beratBadan']) ? $data['beratBadan'] : '-' }}</span>
                            <span style="font-size: 9pt;" color="#000000">Kg</span>
                        </td>

                        <td width="10%">
                            <span style="font-size: 9pt;" color="#000000">Tinggi Badan</span>
                        </td>
                        <td width="1%">
                            <span style="font-size: 9pt;" color="#000000">:</span>
                        </td>
                        <td width="20%">
                            <span style="font-size: 9pt;"
                                color="#000000">{{ isset($data['tinggiBadan']) ? $data['tinggiBadan'] : '-' }}</span>
                            <span style="font-size: 9pt;" color="#000000">cm</span>
                        </td>


                    </tr>
                </table>
                <table width="100%" class="text-top">
                    <tr>
                        <td width="10%">
                            <span style="font-size: 9pt;" color="#000000">Lingkar Perut</span>
                        </td>
                        <td width="1%">
                            <span style="font-size: 9pt;" color="#000000">:</span>
                        </td>
                        <td width="20%">
                            <span style="font-size: 9pt;"
                                color="#000000">{{ isset($data['lingkarPerut']) ? $data['lingkarPerut'] : '-' }}</span>
                        </td>

                        <td width="10%">
                            <span style="font-size: 9pt;" color="#000000">IMT</span>
                        </td>
                        <td width="1%">
                            <span style="font-size: 9pt;" color="#000000">:</span>
                        </td>
                        <td width="20%">
                            <span style="font-size: 9pt;"
                                color="#000000">{{ isset($data['IMT']) ? $data['IMT'] : '-' }}</span>
                        </td>

                    </tr>
                </table>
            </td>
        </tr>
        <tr class="child">
            <td width="100%" colspan="4">
                <span style="font-size: 10pt;" class="judulLabel" color="#000000">4. Kesadaran</span>
            </td>
        </tr>
        <tr class="child-2">
            <td width="100%" colspan="4">
                <span style="font-size: 10pt;" class="judulLabel" color="#000000">1. E</span>
            </td>
        </tr>
        <tr class="child-3">
            <td width="100%" colspan="6">
                <table width="100%">
                    <tr>
                        <td width="15%">
                            <input type="checkbox"
                                {{ isset($data['kesadaranE']) && $data['kesadaranE'] == 4 ? 'checked' : '' }} />
                            <span style="font-size: 9pt;" color="#000000">4</span>
                        </td>
                        <td width="15%">
                            <input type="checkbox"
                                {{ isset($data['kesadaranE']) && $data['kesadaranE'] == 3 ? 'checked' : '' }} />
                            <span style="font-size: 9pt;" color="#000000">3</span>
                        </td>
                        <td width="15%">
                            <input type="checkbox"
                                {{ isset($data['kesadaranE']) && $data['kesadaranE'] == 2 ? 'checked' : '' }} />
                            <span style="font-size: 9pt;" color="#000000">2</span>
                        </td>
                        <td width="15%">
                            <input type="checkbox"
                                {{ isset($data['kesadaranE']) && $data['kesadaranE'] == 1 ? 'checked' : '' }} />
                            <span style="font-size: 9pt;" color="#000000">1</span>
                        </td>
                        <td width="15%">

                        </td>
                        <td width="15%">

                        </td>

                    </tr>
                </table>
            </td>
        </tr>
        <tr class="child-2">
            <td width="100%" colspan="4">
                <span style="font-size: 10pt;" class="judulLabel" color="#000000">2. M</span>
            </td>
        </tr>
        <tr class="child-3">
            <td width="100%" colspan="6">
                <table width="100%">
                    <tr>
                        <td width="15%">
                            <input type="checkbox"
                                {{ isset($data['kesadaranM']) && $data['kesadaranM'] == 6 ? 'checked' : '' }} />
                            <span style="font-size: 9pt;" color="#000000">6</span>
                        </td>
                        <td width="15%">
                            <input type="checkbox"
                                {{ isset($data['kesadaranM']) && $data['kesadaranM'] == 5 ? 'checked' : '' }} />
                            <span style="font-size: 9pt;" color="#000000">5</span>
                        </td>
                        <td width="15%">
                            <input type="checkbox"
                                {{ isset($data['kesadaranM']) && $data['kesadaranM'] == 4 ? 'checked' : '' }} />
                            <span style="font-size: 9pt;" color="#000000">4</span>
                        </td>
                        <td width="15%">
                            <input type="checkbox"
                                {{ isset($data['kesadaranM']) && $data['kesadaranM'] == 3 ? 'checked' : '' }} />
                            <span style="font-size: 9pt;" color="#000000">3</span>
                        </td>
                        <td width="15%">
                            <input type="checkbox"
                                {{ isset($data['kesadaranM']) && $data['kesadaranM'] == 2 ? 'checked' : '' }} />
                            <span style="font-size: 9pt;" color="#000000">2</span>
                        </td>
                        <td width="15%">
                            <input type="checkbox"
                                {{ isset($data['kesadaranM']) && $data['kesadaranM'] == 1 ? 'checked' : '' }} />
                            <span style="font-size: 9pt;" color="#000000">1</span>
                        </td>

                    </tr>
                </table>
            </td>
        </tr>
        <tr class="child-2">
            <td width="100%" colspan="4">
                <span style="font-size: 10pt;" class="judulLabel" color="#000000">3. V</span>
            </td>
        </tr>
        <tr class="child-3">
            <td width="100%" colspan="6">
                <table width="100%">
                    <tr>

                        <td width="15%">
                            <input type="checkbox"
                                {{ isset($data['kesadaranV']) && $data['kesadaranV'] == 5 ? 'checked' : '' }} />
                            <span style="font-size: 9pt;" color="#000000">5</span>
                        </td>
                        <td width="15%">
                            <input type="checkbox"
                                {{ isset($data['kesadaranV']) && $data['kesadaranV'] == 4 ? 'checked' : '' }} />
                            <span style="font-size: 9pt;" color="#000000">4</span>
                        </td>
                        <td width="15%">
                            <input type="checkbox"
                                {{ isset($data['kesadaranV']) && $data['kesadaranV'] == 3 ? 'checked' : '' }} />
                            <span style="font-size: 9pt;" color="#000000">3</span>
                        </td>
                        <td width="15%">
                            <input type="checkbox"
                                {{ isset($data['kesadaranV']) && $data['kesadaranV'] == 2 ? 'checked' : '' }} />
                            <span style="font-size: 9pt;" color="#000000">2</span>
                        </td>
                        <td width="15%">
                            <input type="checkbox"
                                {{ isset($data['kesadaranV']) && $data['kesadaranV'] == 1 ? 'checked' : '' }} />
                            <span style="font-size: 9pt;" color="#000000">1</span>
                        </td>
                        <td width="15%">

                        </td>

                    </tr>
                </table>
            </td>
        </tr>


        <tr class="child-2">
            <td width="100%" colspan="3">
                <table width="100%">
                    <tr>

                        <td width="10%">
                            <span style="font-size: 10pt;" class="judulLabel" color="#000000">Jumlah</span>
                        </td>
                        <td width="5%">
                            <span style="font-size: 9pt;"
                                color="#000000">{{ isset($data['totalKesadaran']) ? $data['totalKesadaran'] : '-' }}</span>
                        </td>
                        <td width="85%">

                        </td>
                    </tr>
                    <tr>

                        <td width="10%">
                            <span style="font-size: 10pt;" class="judulLabel" color="#000000">Catatan</span>
                        </td>
                        <td width="5%">
                            <span style="font-size: 9pt;"
                                color="#000000">{{ isset($data['keteranganKesadaran']) ? $data['keteranganKesadaran'] : '-' }}</span>
                        </td>
                        <td width="85%">

                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr class="child">
            <td width="100%" colspan="6">
                <table width="100%">
                    <tr>
                        <td width="15%">
                            <input type="checkbox"
                                {{ isset($data['rangeKesadaran']) && $data['rangeKesadaran']['poin'] == 15 ? 'checked' : '' }} />
                            <span style="font-size: 9pt;" color="#000000">CMC (14-15)</span>
                        </td>
                        <td width="15%">
                            <input type="checkbox"
                                {{ isset($data['rangeKesadaran']) && $data['rangeKesadaran']['poin'] == 13 ? 'checked' : '' }} />
                            <span style="font-size: 9pt;" color="#000000">Apatis (12-13)</span>
                        </td>
                        <td width="15%">
                            <input type="checkbox"
                                {{ isset($data['rangeKesadaran']) && $data['rangeKesadaran']['poin'] == 11 ? 'checked' : '' }} />
                            <span style="font-size: 9pt;" color="#000000">Somnolen (10-11)</span>
                        </td>
                    </tr>
                    <tr>
                        <td width="15%">
                            <input type="checkbox"
                                {{ isset($data['rangeKesadaran']) && $data['rangeKesadaran']['poin'] == 9 ? 'checked' : '' }} />
                            <span style="font-size: 9pt;" color="#000000">Delerium (7-9)</span>
                        </td>
                        <td width="15%">
                            <input type="checkbox"
                                {{ isset($data['rangeKesadaran']) && $data['rangeKesadaran']['poin'] == 6 ? 'checked' : '' }} />
                            <span style="font-size: 9pt;" color="#000000">Stupor (4-6)</span>
                        </td>
                        <td width="15%">
                            <input type="checkbox"
                                {{ isset($data['rangeKesadaran']) && $data['rangeKesadaran']['poin'] < 3 ? 'checked' : '' }} />
                            <span style="font-size: 9pt;" color="#000000">Koma (<3)< /font>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr class="child">
            <td width="100%" colspan="4">
                <span style="font-size: 10pt;" class="judulLabel" color="#000000">5. Mata</span>
            </td>
        </tr>
        <tr class="child-2">
            <td width="100%" colspan="4">
                <span style="font-size: 10pt;" class="judulLabel" color="#000000">Konjungtiva</span>
            </td>
        </tr>
        <tr class="child-3">
            <td width="100%" colspan="6">
                <table width="100%">
                    <tr>
                        <td width="10%">
                            <input type="checkbox"
                                {{ isset($data['mataKonjungtiva']) && $data['mataKonjungtiva'] == 'Normal' ? 'checked' : '' }} />
                            <span style="font-size: 9pt;" color="#000000">Normal</span>
                        </td>

                        <td width="10%">
                            <input type="checkbox"
                                {{ isset($data['mataKonjungtiva']) && $data['mataKonjungtiva'] == 'Anemis' ? 'checked' : '' }} />
                            <span style="font-size: 9pt;" color="#000000">Anemis</span>
                        </td>
                        <td width="50%">
                            <input type="checkbox"
                                {{ isset($data['mataKonjungtiva']) && $data['mataKonjungtiva'] == 'Lainnya' ? 'checked' : '' }} />
                            <span style="font-size: 9pt;" color="#000000">Lainnya, <span style="font-size: 9pt;"
                                    color="#000000">{{ isset($data['ketKonjungtiva']) ? $data['ketKonjungtiva'] : '-' }}</span>
                            </span>
                            {{-- <span style="font-size: 9pt;" color="#000000"></span> --}}
                        </td>

                    </tr>
                </table>
            </td>
        </tr>
        <tr class="child-2">
            <td width="100%" colspan="4">
                <span style="font-size: 10pt;" class="judulLabel" color="#000000">Sklera</span>
            </td>
        </tr>
        <tr class="child-3">
            <td width="100%" colspan="6">
                <table width="100%">
                    <tr>
                        <td width="10%">
                            <input type="checkbox"
                                {{ isset($data['mataSklera']) && $data['mataSklera'] == 'Normal' ? 'checked' : '' }} />
                            <span style="font-size: 9pt;" color="#000000">Normal</span>
                        </td>

                        <td width="10%">
                            <input type="checkbox"
                                {{ isset($data['mataSklera']) && $data['mataSklera'] == 'Ikterik' ? 'checked' : '' }} />
                            <span style="font-size: 9pt;" color="#000000">Ikterik</span>
                        </td>
                        <td width="50%">
                            <input type="checkbox"
                                {{ isset($data['mataSklera']) && $data['mataSklera'] == 'Lainnya' ? 'checked' : '' }} />
                            <span style="font-size: 9pt;" color="#000000">Lainnya, </span>
                            <span style="font-size: 9pt;"
                                color="#000000">{{ isset($data['ketSklera']) ? $data['ketSklera'] : '-' }}</span>
                        </td>

                    </tr>
                </table>
            </td>
        </tr>
        <tr class="child">
            <td width="100%" colspan="4">
                <span style="font-size: 10pt;" class="judulLabel" color="#000000">6. Hidung</span>
            </td>
        </tr>

        <tr class="child-2">
            <td width="100%" colspan="6">
                <table width="100%">
                    <tr>
                        <td width="10%">
                            <input type="checkbox"
                                {{ isset($data['hidung']) && $data['hidung'] == 'Normal' ? 'checked' : '' }} />
                            <span style="font-size: 9pt;" color="#000000">Normal</span>
                        </td>

                        <td width="20%">
                            <input type="checkbox"
                                {{ isset($data['hidung']) && $data['hidung'] == 'Nafas Cuping Hidung' ? 'checked' : '' }} />
                            <span style="font-size: 9pt;" color="#000000">Nafas Cuping Hidung</span>
                        </td>
                        <td width="50%">
                            <input type="checkbox"
                                {{ isset($data['hidung']) && $data['hidung'] == 'Lainnya' ? 'checked' : '' }} />
                            <span style="font-size: 9pt;" color="#000000">Lainnya, </span>
                            <span style="font-size: 9pt;"
                                color="#000000">{{ isset($data['ketHidung']) ? $data['ketHidung'] : '-' }}</span>
                        </td>

                    </tr>
                </table>
            </td>
        </tr>
        <tr class="child">
            <td width="100%" colspan="4">
                <span style="font-size: 10pt;" class="judulLabel" color="#000000">7. Bibir</span>
            </td>
        </tr>

        <tr class="child-2">
            <td width="100%" colspan="6">
                <table width="100%">
                    <tr>
                        <td width="10%">
                            <input type="checkbox"
                                {{ isset($data['bibir']) && $data['bibir'] == 'Normal' ? 'checked' : '' }} />
                            <span style="font-size: 9pt;" color="#000000">Normal</span>
                        </td>

                        <td width="10%">
                            <input type="checkbox"
                                {{ isset($data['bibir']) && $data['bibir'] == 'Sianosis' ? 'checked' : '' }} />
                            <span style="font-size: 9pt;" color="#000000">Sianosis</span>
                        </td>
                        <td width="50%">
                            <input type="checkbox"
                                {{ isset($data['bibir']) && $data['bibir'] == 'Lainnya' ? 'checked' : '' }} />
                            <span style="font-size: 9pt;" color="#000000">Lainnya, </span>
                            <span style="font-size: 9pt;"
                                color="#000000">{{ isset($data['ketBibir']) ? $data['ketBibir'] : '-' }}</span>
                        </td>

                    </tr>
                </table>
            </td>
        </tr>
        <tr class="child">
            <td width="100%" colspan="4">
                <span style="font-size: 10pt;" class="judulLabel" color="#000000">8. Leher</span>
            </td>
        </tr>

        <tr class="child-2">
            <td width="100%" colspan="4">
                <span style="font-size: 10pt;" class="judulLabel" color="#000000">JVP</span>
            </td>
        </tr>
        <tr class="child-2">
            <td width="100%" colspan="6">
                <table width="100%">
                    <tr>
                        <td width="10%">
                            <span style="font-size: 9pt;" color="#000000">Jumlah</span>
                        </td>

                        <td width="1%">
                            <span style="font-size: 9pt;" color="#000000">:</span>
                        </td>
                        <td width="50%">
                            <span style="font-size: 9pt;"
                                color="#000000">{{ isset($data['leher']) ? $data['leher'] : '-' }}</span>

                        </td>

                    </tr>
                </table>
            </td>
        </tr>
        <tr class="child">
            <td width="100%" colspan="4">
                <span style="font-size: 10pt;" class="judulLabel" color="#000000">9. Thorax</span>
            </td>
        </tr>

        <tr class="child-2">
            <td width="100%" colspan="4">
                <table width="100%">
                    <tr>
                        <td width="25%">
                            <input type="checkbox"
                                {{ isset($data['thorax']) && $data['thorax'] == 'Simetris' ? 'checked' : '' }} />
                            <span style="font-size: 9pt;" color="#000000">Simetris</span>
                        </td>

                        <td width="25%">
                            <input type="checkbox"
                                {{ isset($data['thorax']) && $data['thorax'] == 'Tidak Simetris' ? 'checked' : '' }} />
                            <span style="font-size: 9pt;" color="#000000">Tidak Simetris</span>
                        </td>
                        <td width="25%">
                            <input type="checkbox"
                                {{ isset($data['thorax']) && $data['thorax'] == 'Barell Chest' ? 'checked' : '' }} />
                            <span style="font-size: 9pt;" color="#000000">Barell Chest</span>
                        </td>
                        <td width="25%">
                            <input type="checkbox"
                                {{ isset($data['thorax']) && $data['thorax'] == 'Pigeon Chest' ? 'checked' : '' }} />
                            <span style="font-size: 9pt;" color="#000000">Pigeon Chest</span>
                        </td>


                    </tr>
                    <tr>
                        <td width="25%">
                            <input type="checkbox"
                                {{ isset($data['thorax']) && $data['thorax'] == 'Picuts Excavatum' ? 'checked' : '' }} />
                            <span style="font-size: 9pt;" color="#000000">Pictus Excavatum</span>
                        </td>
                        <td width="%25%">
                            <input type="checkbox"
                                {{ isset($data['thorax']) && $data['thorax'] == 'Retraksi Dinding Dada' ? 'checked' : '' }} />
                            <span style="font-size: 9pt;" color="#000000">Reaksi Dinding Dada</span>
                        </td>

                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td width="100%" colspan="4">
                <span style="font-size: 10pt;" color="#000000"></span>
            </td>
        </tr>

        <tr class="child">
            <td width="100%" colspan="4">
                <span style="font-size: 10pt;" class="judulLabel" color="#000000">10. COR</span>
            </td>
        </tr>
        <tr class="child-2">
            <td width="100%" colspan="4">
                <span style="font-size: 10pt;" class="judulLabel" color="#000000">Inpeksi</span>
            </td>
        </tr>
        <tr class="child-3">
            <td width="100%" colspan="3">
                <table width="100%">
                    <tr>
                        <td width="25%">
                            <input type="checkbox"
                                {{ isset($data['corInspeksi']) && $data['corInspeksi'] == 'Ictus cordis tidak tampak' ? 'checked' : '' }} />
                            <span style="font-size: 9pt;" color="#000000">Ictus cordis tidak tampak</span>
                        </td>

                        <td width="20%">
                            <input type="checkbox"
                                {{ isset($data['corInspeksi']) && $data['corInspeksi'] == 'Ictus cordis tampak' ? 'checked' : '' }} />
                            <span style="font-size: 9pt;" color="#000000">Ictus cordis tampak</span>
                        </td>
                        <td width="55%">
                            <input type="checkbox"
                                {{ isset($data['corInspeksi']) && $data['corInspeksi'] == 'Lainnya' ? 'checked' : '' }} />
                            <span style="font-size: 9pt;" color="#000000">Lainnya, </span>
                            <span style="font-size: 9pt;"
                                color="#000000">{{ isset($data['ketCorInspeksi']) ? $data['ketCorInspeksi'] : '-' }}</span>
                        </td>

                    </tr>
                </table>
            </td>
        </tr>
        <tr class="child-2">
            <td width="100%" colspan="4">
                <span style="font-size: 10pt;" class="judulLabel" color="#000000">Palpis</span>
            </td>
        </tr>
        <tr class="child-3">
            <td width="100%" colspan="3">
                <table width="100%">
                    <tr>
                        <td width="25%">
                            <input type="checkbox"
                                {{ isset($data['corPalpasi']) && $data['corPalpasi'] == 'Ictus cordis tidak teraba' ? 'checked' : '' }} />
                            <span style="font-size: 9pt;" color="#000000">Ictus cordis tidak teraba</span>
                        </td>

                        <td width="20%">
                            <input type="checkbox"
                                {{ isset($data['corPalpasi']) && $data['corPalpasi'] == 'Ictus cordis teraba' ? 'checked' : '' }} />
                            <span style="font-size: 9pt;" color="#000000">Ictus cordis teraba</span>
                        </td>
                        <td width="55%">
                            <input type="checkbox"
                                {{ isset($data['corPalpasi']) && $data['corPalpasi'] == 'Lainnya' ? 'checked' : '' }} />
                            <span style="font-size: 9pt;" color="#000000">Lainnya</span>
                            <span style="font-size: 9pt;"
                                color="#000000">{{ isset($data['ketCorPalpasi']) ? $data['ketCorPalpasi'] : '-' }}</span>
                        </td>

                    </tr>
                </table>
            </td>

        </tr>
        <tr class="child-2">
            <td width="100%" colspan="3">
                <table width="100%">
                    <tr>
                        <td width="10%">
                            <span class="judulLabel">Perkusi</span>
                        </td>

                        <td width="1%">
                            <span style="font-size: 9pt;" color="#000000">:</span>
                        </td>
                        <td width="70%">
                            <span style="font-size: 9pt;"
                                color="#000000">{{ isset($data['ketCorPerkusi']) ? $data['ketCorPerkusi'] : '-' }}</span>
                        </td>

                    </tr>
                </table>
            </td>
        </tr>

        <tr class="child-2">
            <td width="100%" colspan="4">
                <span style="font-size: 10pt;" class="judulLabel" color="#000000">Auskultasi</span>
            </td>
        </tr>
        <tr class="child-3">
            <td width="100%" colspan="3">
                <table width="100%">
                    <tr>
                        <td width="20%">
                            <input type="checkbox"
                                {{ isset($data['corAuskultasi']) && $data['corAuskultasi'] == 'S1 dan S2 Reguler' ? 'checked' : '' }} />
                            <span style="font-size: 9pt;" color="#000000">S1 dan S2 Reguler</span>
                        </td>

                        <td width="20%">
                            <input type="checkbox"
                                {{ isset($data['corAuskultasi']) && $data['corAuskultasi'] == 'Murmur' ? 'checked' : '' }} />
                            <span style="font-size: 9pt;" color="#000000">Murmur</span>
                        </td>
                        <td width="60%">
                            <input type="checkbox"
                                {{ isset($data['corAuskultasi']) && $data['corAuskultasi'] == 'Lainnya' ? 'checked' : '' }} />
                            <span style="font-size: 9pt;" color="#000000">Lainnya,</span>
                            <span
                                style="font-size: 9pt;">{{ isset($data['corAuskultasi']) ? $data['corAuskultasi'] : '-' }}</span>
                        </td>

                    </tr>
                </table>
            </td>
        </tr>
        <tr class="child">
            <td width="100%" colspan="4">
                <span style="font-size: 10pt;" class="judulLabel" color="#000000">11. Paru</span>
            </td>
        </tr>
        <tr class="child-2">
            <td width="100%" colspan="4">
                <span style="font-size: 10pt;" class="judulLabel" color="#000000">a. Inpeksi</span>
            </td>
        </tr>
        <tr class="child-3">
            <td width="100%" colspan="4">
                <table width="100%">
                    <tr>
                        <td width="20%">
                            <input type="checkbox"
                                {{ isset($data['paruInspeksi']) && $data['paruInspeksi'] == 'Normal' ? 'checked' : '' }} />
                            <span style="font-size: 9pt;" color="#000000"> Normal</span>
                        </td>

                        <td width="25%">
                            <input type="checkbox"
                                {{ isset($data['paruInspeksi']) && $data['paruInspeksi'] == 'Retraksi Dinding Dada' ? 'checked' : '' }} />
                            <span style="font-size: 9pt;" color="#000000">Retraksi dingding dada</span>
                        </td>
                        <td width="20%">
                            <input type="checkbox"
                                {{ isset($data['paruInspeksi']) && $data['paruInspeksi'] == 'Alat Bantu Nafas' ? 'checked' : '' }} />
                            <span style="font-size: 9pt;" color="#000000">Alat bantu nafas</span>
                        </td>
                        <td width="35%">
                            <input type="checkbox"
                                {{ isset($data['paruInspeksi']) && $data['paruInspeksi'] == 'Lainnya' ? 'checked' : '' }} />
                            <span style="font-size: 9pt;" color="#000000">Lainnya</span>
                            <span style="font-size: 9pt;"
                                color="#000000">{{ isset($data['paruInspeksi']) ? $data['paruInspeksi'] : '-' }}</span>
                        </td>

                    </tr>
                </table>
            </td>
        </tr>
        <tr class="child-2">
            <td width="100%" colspan="4">
                <span style="font-size: 10pt;" class="judulLabel" color="#000000">b. Palpis</span>
            </td>
        </tr>
        <tr class="child-3">

            <td width="100%" colspan="4">
                <table width="100%">

                    <tr>
                        <td width="23%">
                            <input type="checkbox"
                                {{ isset($data['paruPalpasi']) && $data['paruPalpasi'] == 'Fremitus Kiri dan Kanan Sama' ? 'checked' : '' }} />
                            <span style="font-size: 9pt;" color="#000000">Fremitus kiri dan kanan sama</span>
                        </td>

                        <td width="20%">
                            <input type="checkbox"
                                {{ isset($data['paruPalpasi']) && $data['paruPalpasi'] == 'Fremitus Kiri Menurun' ? 'checked' : '' }} />
                            <span style="font-size: 9pt;" color="#000000">Fremitus kiri menurun</span>
                        </td>
                        <td width="30%">
                            <input type="checkbox"
                                {{ isset($data['paruPalpasi']) && $data['paruPalpasi'] == 'Fremitus Kiri Meningkat' ? 'checked' : '' }} />
                            <span style="font-size: 9pt;" color="#000000">Fremitus kiri meningkat</span>
                        </td>

                    </tr>
                </table>
            </td>
        </tr>
        <tr class="child-3">

            <td width="100%" colspan="4">
                <table width="100%">

                    <tr>
                        <td width="23%">
                            <input type="checkbox"
                                {{ isset($data['paruPalpasi']) && $data['paruPalpasi'] == 'Fremitus Kanan Menurun' ? 'checked' : '' }} />
                            <span style="font-size: 9pt;" color="#000000">Fremitus kanan menurun</span>
                        </td>
                        <td width="20%">
                            <input type="checkbox"
                                {{ isset($data['paruPalpasi']) && $data['paruPalpasi'] == 'Fremitus Kanan Meningkat' ? 'checked' : '' }} />
                            <span style="font-size: 9pt;" color="#000000">Fremitus kanan meningkat</span>
                        </td>

                        <td width="30%">
                            <input type="checkbox"
                                {{ isset($data['paruPalpasi']) && $data['paruPalpasi'] == 'Lainnya' ? 'checked' : '' }} />
                            <span style="font-size: 9pt;" color="#000000">Lainnya</span>
                        </td>

                    </tr>
                </table>
            </td>
        </tr>

        <tr class="child-2">
            <td width="100%" colspan="4">
                <span style="font-size: 10pt;" class="judulLabel" color="#000000">c. Prekusi</span>
            </td>
        </tr>
        <tr class="child-3">
            <td width="100%" colspan="4">
                <table width="100%">

                    <tr>
                        <td width="25%">
                            <input type="checkbox"
                                {{ isset($data['paruPerkusi']) && $data['paruPerkusi'] == 'Sonor' ? 'checked' : '' }} />
                            <span style="font-size: 9pt;" color="#000000">Sonor</span>
                        </td>

                        <td width="25%">
                            <input type="checkbox"
                                {{ isset($data['paruPerkusi']) && $data['paruPerkusi'] == 'Hipersonor' ? 'checked' : '' }} />
                            <span style="font-size: 9pt;" color="#000000">HiperSonor</span>
                        </td>
                        <td width="50%">
                            <input type="checkbox"
                                {{ isset($data['paruPerkusi']) && $data['paruPerkusi'] == 'Lainnya' ? 'checked' : '' }} />
                            <span style="font-size: 9pt;" color="#000000">Lainnya,</span>
                            <span style="font-size: 9pt;"
                                color="#000000">{{ isset($data['paruPerkusi']) ? $data['paruPerkusi'] : '-' }}</span>
                        </td>
                        <td width="0%">

                        </td>

                    </tr>
                </table>
            </td>
        </tr>
        <tr class="child-2">
            <td width="100%" colspan="4">
                <span style="font-size: 10pt;" class="judulLabel" color="#000000">d. Akultasi</span>
            </td>
        </tr>
        <tr class="child-3">
            <td width="100%" colspan="4"> <input type="checkbox"
                    {{ isset($data['paruAusRonki']) && $data['paruAusRonki'] == 'Ronki' ? 'checked' : '' }} />
                <span style="font-size: 10pt;" class="judulLabel" color="#000000"> Ronki</span>
            </td>
        </tr>
        <tr class="child-3">
            <td>
                <span style="font-size: 10pt;"
                    color="#000000">{{ isset($data['auskultasiKetRonki']) ? $data['auskultasiKetRonki'] : '-' }}</span>
            </td>

        </tr>

        <tr class="child-3">
            <td width="100%" colspan="4">
                <input type="checkbox"
                    {{ isset($data['paruAusWheezing']) && $data['paruAusWheezing'] == 'Wheezing' ? 'checked' : '' }} />
                <span style="font-size: 10pt;" class="judulLabel" color="#000000"> Wheezing</span>
            </td>
        </tr>
        <tr class="child-3">
            <td>
                <span style="font-size: 10pt;"
                    color="#000000">{{ isset($data['auskultasiKetWheezing']) ? $data['auskultasiKetWheezing'] : '-' }}</span>
            </td>

        </tr>
        <tr class="child-2">
            <td>
                <span style="font-size: 10pt;"
                    color="#000000">{{ isset($data['ketLainAuskultasi']) ? $data['ketLainAuskultasi'] : '' }}</span>
            </td>

        </tr>

        <tr class="child">
            <td width="100%" colspan="4">
                <span style="font-size: 10pt;" class="judulLabel" color="#000000">12. Abdomen</span>
            </td>
        </tr>

        <tr class="child-2">
            <td width="100%" colspan="4">
                <span style="font-size: 10pt;" class="judulLabel" color="#000000">a. Inpeksi</span>
            </td>
        </tr>
        <tr class="child-3">
            <td width="100%" colspan="4">
                <table width="100%">

                    <tr>
                        <td width="25%">
                            <input type="checkbox"
                                {{ isset($data['abdomenInspeksi']) && $data['abdomenInspeksi'] == 'Datar' ? 'checked' : '' }} />
                            <span style="font-size: 9pt;" color="#000000">Datar</span>
                        </td>

                        <td width="25%">
                            <input type="checkbox"
                                {{ isset($data['abdomenInspeksi']) && $data['abdomenInspeksi'] == 'Cekung' ? 'checked' : '' }} />
                            <span style="font-size: 9pt;" color="#000000">Cekung</span>
                        </td>
                        <td width="50%">
                            <input type="checkbox"
                                {{ isset($data['abdomenInspeksi']) && $data['abdomenInspeksi'] == 'Lainnya' ? 'checked' : '' }} />
                            <span style="font-size: 9pt;" color="#000000">Lainnya,</span>
                            <span style="font-size: 9pt;"
                                color="#000000">{{ isset($data['ketInspeksi']) ? $data['ketInspeksi'] : '' }}</span>
                        </td>
                        <td width="0%">

                        </td>

                    </tr>
                </table>
            </td>
        </tr>
        <tr class="child-2">
            <td width="100%" colspan="4">
                <span style="font-size: 10pt;" class="judulLabel" color="#000000">b. Palpasi</span>
            </td>
        </tr>
        <tr class="child-3">
            <td width="100%" colspan="4">
                <table width="100%">

                    <tr>
                        <td width="25%">
                            <input type="checkbox"
                                {{ isset($data['abdomenPalpasi']) && $data['abdomenPalpasi'] == 'Normal' ? 'checked' : '' }} />
                            <span style="font-size: 9pt;" color="#000000">Normal</span>
                        </td>

                        <td width="25%">
                            <input type="checkbox"
                                {{ isset($data['abdomenPalpasi']) && $data['abdomenPalpasi'] == 'Nyeri Tekan' ? 'checked' : '' }} />
                            <span style="font-size: 9pt;" color="#000000">Nyeri Tekan</span>
                            <span style="font-size: 9pt;"
                                color="#000000">{{ isset($data['ketAbdomenPalpasi']) ? $data['ketAbdomenPalpasi'] : '' }}</span>
                        </td>
                        <td width="0%">

                        </td>
                        <td width="0%">

                        </td>

                    </tr>
                </table>
            </td>
        </tr>
        <tr class="child-2">
            <td width="100%" colspan="4">
                <table width="100%">

                    <tr>
                        <td width="20%">
                            <span style="font-size: 9pt;" color="#000000">Hepar</span>
                        </td>

                        <td width="1%">
                            <span style="font-size: 9pt;" color="#000000">:</span>
                        </td>
                        <td width="79%">
                            <span style="font-size: 9pt;"
                                color="#000000">{{ isset($data['ketAbdomenHepar']) ? $data['ketAbdomenHepar'] : '-' }}</span>
                        </td>
                        <td width="0%">

                        </td>

                    </tr>
                </table>
            </td>
        </tr>
        <tr class="child-2">
            <td width="100%" colspan="4">
                <table width="100%">

                    <tr>
                        <td width="20%">
                            <span style="font-size: 9pt;" color="#000000">Limpa</span>
                        </td>

                        <td width="1%">
                            <span style="font-size: 9pt;" color="#000000">:</span>
                        </td>
                        <td width="79%">
                            <span style="font-size: 9pt;"
                                color="#000000">{{ isset($data['ketAbdomenLimpa']) ? $data['ketAbdomenLimpa'] : '-' }}</span>
                        </td>
                        <td width="0%">

                        </td>

                    </tr>
                </table>
            </td>
        </tr>
        <tr class="child-2">
            <td width="100%" colspan="4">
                <span style="font-size: 10pt;" class="judulLabel" color="#000000">c. Perkusi</span>
            </td>
        </tr>
        <tr class="child-3">
            <td width="100%" colspan="4">
                <table width="100%">

                    <tr>
                        <td width="25%">
                            <input type="checkbox"
                                {{ isset($data['abdomenPerkusi']) && $data['abdomenPerkusi'] == 'Timpani' ? 'checked' : '' }} />
                            <span style="font-size: 9pt;" color="#000000">Timpani</span>
                        </td>
                        <td width="25%">
                            <input type="checkbox"
                                {{ isset($data['abdomenPerkusi']) && $data['abdomenPerkusi'] == 'Hipertimpani' ? 'checked' : '' }} />
                            <span style="font-size: 9pt;" color="#000000">Hipertimpani</span>
                        </td>
                        <td width="25%">
                            <input type="checkbox"
                                {{ isset($data['abdomenPerkusi']) && $data['abdomenPerkusi'] == 'Pekak' ? 'checked' : '' }} />
                            <span style="font-size: 9pt;" color="#000000">Pekak</span>
                        </td>

                        <td width="0%">

                        </td>

                    </tr>
                </table>
            </td>
        </tr>
        <tr class="child-2">
            <td width="100%" colspan="4">
                <span style="font-size: 10pt;"
                    color="#000000">{{ isset($data['ketAbdomenPerkusi']) ? $data['ketAbdomenPerkusi'] : '' }}</span>
            </td>
        </tr>
        <tr class="child">
            <td width="100%" colspan="4">
                <span style="font-size: 10pt;" class="judulLabel" color="#000000">13. Ekstremitas</span>
            </td>
        </tr>
        <tr class="child-2">
            <td width="100%" colspan="5">
                <table width="100%">

                    <tr>
                        <td width="20%">
                            <input type="checkbox"
                                {{ isset($data['ekstrmitas_1']) && $data['ekstrmitas_1'] == 'Akral Hangat' ? 'checked' : '' }} />
                            <span style="font-size: 9pt;" color="#000000">Akar Hangat</span>
                        </td>
                        <td width="20%">
                            <input type="checkbox"
                                {{ isset($data['ekstrmitas_1']) && $data['ekstrmitas_1'] == 'Akral Dingin' ? 'checked' : '' }} />
                            <span style="font-size: 9pt;" color="#000000">Akar Dingin</span>
                        </td>
                        <td width="20%">
                            <input type="checkbox"
                                {{ isset($data['ekstrmitas_2']) && $data['ekstrmitas_2'] == 'CRT < 2 Detik' ? 'checked' : '' }} />
                            <span style="font-size: 9pt;" color="#000000">CRT <2 Detik</span>
                        </td>
                        <td width="20%">
                            <input type="checkbox"
                                {{ isset($data['ekstrmitas_2']) && $data['ekstrmitas_2'] == 'CRT > 2 Detik' ? 'checked' : '' }} />
                            <span style="font-size: 9pt;" color="#000000">CRT >2 Detik</span>
                        </td>

                        <td width="20%">
                            <input type="checkbox"
                                {{ isset($data['ekstrmitas_3']) && $data['ekstrmitas_3'] == 'Pitting Edem' ? 'checked' : '' }} />
                            <span style="font-size: 9pt;" color="#000000">Pittng Edem</span>
                        </td>

                    </tr>
                </table>
            </td>
        </tr>
        <tr class="child-2">
            <td width="100%" colspan="4">
                <span style="font-size: 10pt;"
                    color="#000000">{{ isset($data['ketEkstrmitas']) ? $data['ketEkstrmitas'] : '' }}</span>
            </td>
        </tr>
        <tr class="child">
            <td width="100%" colspan="4">
                <span style="font-size: 10pt;" class="judulLabel" color="#000000">14. Kekuatan Otot</span>
            </td>
        </tr>
        <tr class="child-2">
            <td width="100%" colspan="4">
                <span style="font-size: 10pt;"
                    color="#000000">{{ isset($data['KekuatanOtot']) ? $data['KekuatanOtot'] : '-' }}</span>
            </td>
        </tr>

        <tr class="child">
            <td width="100%" colspan="4">
                <span style="font-size: 10pt;" class="judulLabel" color="#000000">15. Pemeriksaan Neurologi</span>
            </td>
        </tr>
        <tr class="child-2">
            <td width="100%" colspan="4">
                <span style="font-size: 10pt;"
                    color="#000000">{{ isset($data['pemeriksaanNeurologi']) ? $data['pemeriksaanNeurologi'] : '-' }}</span>
            </td>
        </tr>

        <tr>
            <td width="100%" colspan="4">
                <span style="font-size: 10pt;" class="judulLabel" color="#000000">C. Status Lokasi</span>
            </td>
        </tr>
        <tr class="child">
            <td width="100%" colspan="4">
                <span style="font-size: 10pt;"
                    color="#000000">{{ isset($data['statusLokalis']) ? $data['statusLokalis'] : '-' }}</span>
            </td>
        </tr>
        <tr>
            <td width="100%" colspan="4">
                <span style="font-size: 10pt;" class="judulLabel" color="#000000">D. Pemeriksaan Penunjang</span>
            </td>
        </tr>
        <tr class="child">
            <td>
                <input type="checkbox"
                    {{ isset($data['pemeriksaanEkg']) && $data['pemeriksaanEkg'] == 'Ya' ? 'checked' : '' }} />
                <span style="font-size: 9pt;" color="#000000">EKG, </span>
                <span style="font-size: 9pt;"
                    color="#000000">{{ isset($data['ketEKG']) ? $data['ketEKG'] : '-' }}</span>
            </td>
        </tr>
        <tr class="child">
            <td>
                <input type="checkbox"
                    {{ isset($data['pemeriksaanEchocardiography']) && $data['pemeriksaanEchocardiography'] == 'Ya' ? 'checked' : '' }} />
                <span style="font-size: 9pt;" color="#000000">Echocardiography, </span>
                <span style="font-size: 9pt;"
                    color="#000000">{{ isset($data['ketEchocardiography']) ? $data['ketEchocardiography'] : '-' }}</span>
            </td>
        </tr>
        <tr class="child">
            <td>
                <input type="checkbox"
                    {{ isset($data['pemeriksaanLaboratorium']) && $data['pemeriksaanLaboratorium'] == 'Ya' ? 'checked' : '' }} />
                <span style="font-size: 9pt;" color="#000000">Laboratorium, </span>
                <span style="font-size: 9pt;"
                    color="#000000">{{ isset($data['ketLaboratorium']) ? $data['ketLaboratorium'] : '-' }}</span>
            </td>
        </tr>
        <tr class="child">
            <td>
                <input type="checkbox"
                    {{ isset($data['pemeriksaanRadiologi']) && $data['pemeriksaanRadiologi'] == 'Ya' ? 'checked' : '' }} />
                <span style="font-size: 9pt;" color="#000000">Radiologi, </span>
                <span style="font-size: 9pt;"
                    color="#000000">{{ isset($data['ketRadiologi']) ? $data['ketRadiologi'] : '-' }}</span>
        </tr>
        <tr class="child">
            <td>
                <span style="font-size: 9pt;"
                    color="#000000">{{ isset($data['pemeriksaanPenunjang']) ? $data['pemeriksaanPenunjang'] : '-' }}</span>
            </td>
        </tr>

        <tr>
            <td width="100%" colspan="4">
                <span style="font-size: 10pt;" class="judulLabel" color="#000000">E. Diagnosa</span>
            </td>
        </tr>
        <tr>
            <td width="100%">
                <table width="100%" border="1" bordercolor="#000000" class="garis6">
                    <tr bgcolor="#f08080">
                        <th colspan='8'>Diagnosis ICD 9</th>
                    </tr>
                    <tr bgcolor="#f08080">

                        <th>No Registrasi</th>
                        <th>Kode ICD 9</th>
                        <th>Nama ICD 9</th>
                        <th>Ruangan</th>
                        <th>Penginput</th>
                        <th>Tgl Input</th>
                    </tr>
                    @foreach ($icd9 as $item)
                        <tr style="background: transparent">
                            <td align="left">
                                {{ $item->noregistrasi }}
                            </td>
                            <td align="left">
                                {{ $item->kddiagnosatindakan }}
                            </td>
                            <td align="left">
                                {{ $item->namadiagnosatindakan }}
                            </td>
                            <td align="left">
                                {{ $item->namaruangan }}
                            </td>
                            <td align="left">
                                {{ $item->namalengkap }}
                            </td>
                            <td align="left">
                                {{ $item->tglinputdiagnosa }}
                            </td>
                        </tr>
                        @if (!$icd9)
                            <tr>
                                <td colspan="8"> Tidak ada data </td>
                            </tr>
                        @endif
                    @endforeach
                </table>
            </td>
        </tr>

        <tr>
            <td width="100%">
                <table width="100%" border="1" bordercolor="#000000" class="garis6" style="margin-top: 10px;">
                    <tr bgcolor="#f08080">
                        <th colspan='8' style="text-align: center">Diagnosis ICD 10</th>
                    </tr>
                    <tr bgcolor="#f08080">

                        <th>No Registrasi</th>
                        <th>Jenis Diagnosis</th>
                        <th>Kode ICD 10</th>
                        <th>Nama ICD 10</th>
                        <th>Ruangan</th>
                        <th>Penginput</th>
                        <th>Tgl Input</th>
                    </tr>
                    @foreach ($icd10 as $item)
                        <tr bgcolor="">
                            <td align="left">
                                {{ $item->noregistrasi }}
                            </td>
                            <td align="left">
                                {{ $item->jenisdiagnosa }}
                            </td>
                            <td align="left">
                                {{ $item->kddiagnosa }}
                            </td>
                            <td align="left">
                                {{ $item->namadiagnosa }}
                            </td>
                            <td align="left">
                                {{ $item->namaruangan }}
                            </td>
                            <td align="left">
                                {{ $item->namalengkap }}
                            </td>
                            <td align="left">
                                {{ $item->tglinputdiagnosa }}
                            </td>
                        </tr>
                        @if (!$icd10)
                            <tr>
                                <td colspan="8" style="text-align: center"> Tidak ada data </td>
                            </tr>
                        @endif
                    @endforeach
                </table>
            </td>
        </tr>

        <tr>
            <td width="100%" colspan="4">
                <span style="font-size: 10pt;" class="judulLabel" color="#000000">F. Tata Laksana</span>
            </td>
        </tr>
        <tr class="child">
            <td width="100%" colspan="3">
                <table width="100%">

                    <tr>
                        <td width="10%">
                            <span style="font-size: 9pt;" color="#000000">Tanggal</span>
                        </td>
                        <td width="1%">
                            <span style="font-size: 9pt;" color="#000000">:</span>
                        </td>

                        <td width="69%">
                            <span style="font-size: 9pt;"
                                color="#000000">{{ isset($data['waktuTataLaksana']) ? date('d-m-Y', strtotime($data['waktuTataLaksana'])) : '-' }}</span>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
        <tr class="child">
            <td width="100%" colspan="3">
                <table width="100%">

                    <tr>
                        <td width="10%">

                        </td>
                        <td width="1%">

                        </td>

                        <td width="69%">
                            <span style="font-size: 9pt;"
                                color="#000000">{{ isset($data['ketTataLaksana']) ? $data['ketTataLaksana'] : '-' }}</span>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
        <tr>
            <td width="100%" colspan="4">
                <span style="font-size: 10pt;" class="judulLabel" color="#000000">G. Prognosis</span>
            </td>
        </tr>
        <tr class="child">
            <td width="100%" colspan="5">
                <table width="100%">

                    <tr>
                        <td width="20%">
                            <input
                                type="checkbox"{{ isset($data['prognosis']) && $data['prognosis'] == 'Bonam' ? 'checked' : '' }} />
                            <span style="font-size: 9pt;" color="#000000">Bonam</span>
                        </td>
                        <td width="20%">
                            <input type="checkbox"
                                {{ isset($data['prognosis']) && $data['prognosis'] == 'Dubia ad Bonam' ? 'checked' : '' }} />
                            <span style="font-size: 9pt;" color="#000000">Dubia ad Bonam</span>
                        </td>
                        <td width="25%">
                            <input type="checkbox"
                                {{ isset($data['prognosis']) && $data['prognosis'] == 'Dubia ad Malam' ? 'checked' : '' }} />
                            <span style="font-size: 9pt;" color="#000000">Dubia ad Malam <2 Detik </span>
                        </td>
                        <td width="20%">
                            <input type="checkbox"
                                {{ isset($data['prognosis']) && $data['prognosis'] == 'Malam' ? 'checked' : '' }} />
                            <span style="font-size: 9pt;" color="#000000">Malam</span>
                        </td>

                        <td width="15%">

                        </td>

                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td width="100%" colspan="4">
                <span style="font-size: 10pt;" class="judulLabel" color="#000000">H. Rencana Selanjutnya</span>
            </td>
        </tr>
        <tr class="child">
            <td width="100%" colspan="4">
                <table width="100%">

                    <tr>
                        <td width="20%">
                            <span style="font-size: 9pt;" color="#000000">Kontrol Tanggal</span>
                        </td>
                        <td width="1%">
                            <span style="font-size: 9pt;" color="#000000">:</span>
                        </td>
                        <td width="20%">
                            <span style="font-size: 9pt;"
                                color="#000000">{{ isset($data['waktuKontrol']) ? date('d-m-Y', strtotime($data['waktuKontrol'])) : '-' }}</span>
                        </td>
                        <td width="20%">
                        </td>
                    </tr>
                    <tr>
                        <td width="20%">
                            <span style="font-size: 9pt;" color="#000000">Rawat</span>
                        </td>
                        <td width="1%">
                            <span style="font-size: 9pt;" color="#000000">:</span>
                        </td>
                        <td width="20%">
                            <span style="font-size: 9pt;"
                                color="#000000">{{ isset($data['rawat']) ? $data['rawat'] : '-' }}</span>
                        </td>
                        <td width="20%">
                        </td>
                    </tr>
                    <tr>
                        <td width="20%">
                            <span style="font-size: 9pt;" color="#000000">Rujuk</span>
                        </td>
                        <td width="1%">
                            <span style="font-size: 9pt;" color="#000000">:</span>
                        </td>
                        <td width="20%">
                            <span style="font-size: 9pt;"
                                color="#000000">{{ isset($data['rujuk']) ? $data['rujuk'] : '-' }}</span>
                        </td>
                        <td width="20%">
                        </td>
                    </tr>
                    <td width="100%" colspan="4" height="10"></td>
                </table>
            </td>
        </tr>

        <tr>
            <td width="100%" colspan="4">
                <table width="100%">
                    <tr>
                        <td width="100%">
                            <span style="font-size: 11pt;" color="#000000"><b>Bandung</b>,
                                {{ isset($data['waktuSimpan']) ? date('d-m-Y', strtotime($data['waktuSimpan'])) : '-' }}
                            </span>
                            <span style="font-size: 11pt;" color="#000000"><b>Jam</b>:
                                {{ isset($data['waktuSimpan']) ? date('H:i:s', strtotime($data['waktuSimpan'])) : '-' }}</span>
                            {{-- <span style="font-size: 10pt;" color="#000000" class="judulLabel">Bandung, Tanggal dan jam
                                : </span>
                            <span style="font-size: 9pt;" color="#000000"></span> --}}
                        </td>
                    </tr>
                    <tr>
                        <td width="100%">
                            <span style="font-size: 10pt;" color="#000000" class="judulLabel">Dokter : </span>
                            <span style="font-size: 9pt;"
                                color="#000000">{{ isset($data['penangungJawab']) ? $data['penangungJawab']['label'] : '-' }}</span>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>

    </table>

@endsection
