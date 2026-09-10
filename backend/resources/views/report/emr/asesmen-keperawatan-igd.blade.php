@extends('template.head-emr')
@section('title', 'EMR')
@section('about', 'ASESMEN KEPERAWATAN IGD')
@section('content')

    <table width="100%" cellspacing="0">
        <tr>
            <td width="100%" colspan="4"><span style="font-size: 10pt;" class="judulLabel" color="#000000">A. ASESMEN</span>
            </td>
        </tr>
        <tr>
            <td width="100%" colspan="4">
                <table width="100%">
                    <tr>
                        <td width="30%" class="text-top">
                            <span style="font-size: 10pt;" color="#000000">Respon Time</span>
                        </td>
                        <td width="1%" class="text-top">
                            <span style="font-size: 10pt;" color="#000000">:</span>
                        </td>
                        <td width="69%">
                            <span style="font-size: 10pt;"
                                color="#000000">{{ isset($data['ResponTime']) ? $data['ResponTime'] : '-' }}</span>
                        </td>
                    </tr>
                    <tr>
                        <td width="30%" class="text-top">
                            <span style="font-size: 10pt;" color="#000000">Hari/Tanggal</span>
                        </td>
                        <td width="1% " class="text-top">
                            <span style="font-size: 10pt;" color="#000000">:</span>
                        </td>
                        <td width="69%">
                            <span style="font-size: 10pt;"
                                color="#000000">{{ isset($data['waktuPemeriksaan']) ? $hari : '-' }}</span>
                        </td>
                    </tr>
                    <tr>
                        <td width="30%" class="text-top">
                            <span style="font-size: 10pt;" color="#000000">Jam Periksa</span>
                        </td>
                        <td width="1%" class="text-top">
                            <span style="font-size: 10pt;" color="#000000">:</span>
                        </td>
                        <td width="69%">
                            <span style="font-size: 10pt;"
                                color="#000000">{{ isset($data['waktuPemeriksaan']) ? date('H:m:s', strtotime($data['waktuPemeriksaan'])) : '-' }}</span>
                        </td>
                    </tr>
                    <tr>
                        <td width="100%" colspan="4"><span style="font-size: 10pt;" color="#000000">Jenis Kasus</span>
                        </td>
                    </tr>
                    <tr>
                        <td width="100%" colspan="4">
                            <table width="100%">
                                <tr>
                                    <td width="20%">
                                        <input type="checkbox"
                                            {{ isset($data['JenisKasus']) && $data['JenisKasus'] == 'Trauma' ? 'checked' : '' }} />
                                        <span style="font-size: 9pt;" color="#000000">Trauma</span>
                                    </td>
                                    <td width="20%">
                                        <input type="checkbox"
                                            {{ isset($data['JenisKasus']) && $data['JenisKasus'] == 'Non Trauma' ? 'checked' : '' }} />
                                        <span style="font-size: 9pt;" color="#000000">Non-Trauma</span>
                                    </td>
                                    <td width="20%"
                                        {{ isset($data['JenisKasus']) && $data['JenisKasus'] == 'Anak' ? 'checked' : '' }}>
                                        <input type="checkbox" /><span style="font-size: 9pt;" color="#000000">Anak</span>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td width="100%" colspan="4"><span style="font-size: 10pt;" color="#000000">Transportasi ke
                                IGD</span></td>
                    </tr>
                    <tr>
                        <td width="100%" colspan="4">
                            <table width="100%">
                                <tr>
                                    <td width="20%">
                                        <input type="checkbox"
                                            {{ isset($data['transportIGD_1']) && $data['transportIGD_1'] == 'Datang Sendiri' ? 'checked' : '' }} />
                                        <span style="font-size: 9pt;" color="#000000">Datang Sendiri</span>
                                    </td>
                                    <td width="20%">
                                        <input type="checkbox"
                                            {{ isset($data['transportIGD_1']) && $data['transportIGD_1'] == 'Ambulance' ? 'checked' : '' }} />
                                        <span style="font-size: 9pt;" color="#000000">Ambulance</span>
                                    </td>
                                    <td width="60%">
                                        <input type="checkbox"
                                            {{ isset($data['rujukanIGD']) && $data['rujukanIGD'] == 'Rujukan Dari' ? 'checked' : '' }} />
                                        <span style="font-size: 9pt;" color="#000000">Rujukan Dari</span>
                                        <span style="font-size: 10pt;"
                                            color="#000000">{{ isset($data['asalRujukan']) ? $data['asalRujukan'] : '' }}</span>
                                    </td>

                                </tr>
                                <tr>
                                    <td width="20%">
                                        <input type="checkbox"
                                            {{ isset($data['transportIGD_2']) && $data['transportIGD_2'] == 'Auto Anamnesa' ? 'checked' : '' }} />
                                        <span style="font-size: 9pt;" color="#000000">Auto Anamnesa</span>
                                    </td>
                                    <td width="20%">
                                        <input type="checkbox"
                                            {{ isset($data['transportIGD_2']) && $data['transportIGD_2'] == 'Allo Anamnesa' ? 'checked' : '' }} />
                                        <span style="font-size: 9pt;" color="#000000">Allo Anamnesa</span>

                                    </td>
                                    <td width="60%">
                                        <span style="font-size: 10pt;"
                                            color="#000000">{{ isset($data['diantar']) ? $data['diantar'] : '' }}</span>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td width="100%" colspan="4">
                            <span style="font-size: 10pt;" color="#000000">Riwayat Kesehatan</span>
                        </td>
                    </tr>
                    <tr>
                        <td width="30%" class="text-top">
                            <span style="font-size: 10pt;" color="#000000">Keluhan utama</span>
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
                            <span style="font-size: 10pt;" color="#000000">Keluhan saat ini</span>
                        </td>
                        <td width="1%" class="text-top">
                            <span style="font-size: 10pt;" color="#000000">:</span>
                        </td>
                        <td width="69%">
                            <span style="font-size: 10pt;"
                                color="#000000">{{ isset($data['keluhanSaatIni']) ? $data['keluhanSaatIni'] : '-' }}</span>
                        </td>
                    </tr>
                    <tr>
                        <td width="30%" class="text-top">
                            <span style="font-size: 10pt;" color="#000000">Riwayat penyakit dahulu</span>
                        </td>
                        <td width="1%" class="text-top">
                            <span style="font-size: 10pt;" color="#000000">:</span>
                        </td>
                        <td width="69%">
                            <span style="font-size: 10pt;"
                                color="#000000">{{ isset($data['riwayatPenyakit']) ? $data['riwayatPenyakit'] : '-' }}</span>
                        </td>
                    </tr>
                    <tr>
                        <td width="100%" colspan="4"><span style="font-size: 10pt;" color="#000000">Riwayat
                                Alergi</span></td>
                    </tr>
                    <tr>
                        <td width="100%" colspan="4">
                            <table width="100%">
                                <tr>
                                    <td width="20%">
                                        <input type="checkbox"
                                            {{ isset($data['alergi']) && $data['alergi'] == 'Tidak' ? 'checked' : '' }} />
                                        <span style="font-size: 9pt;" color="#000000">Tidak</span>
                                    </td>
                                    <td width="60%">
                                        <input type="checkbox"
                                            {{ isset($data['alergi']) && $data['alergi'] == 'Ya' ? 'checked' : '' }} />
                                        <span style="font-size: 9pt;" color="#000000">Ya</span>
                                        <span style="font-size: 10pt;" color="#000000"></span>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td width="30%" class="text-top">
                            <span style="font-size: 10pt;" color="#000000">Riwayat Penggunaan Obat-obatan</span>
                        </td>
                        <td width="1%" class="text-top">
                            <span style="font-size: 10pt;" color="#000000">:</span>
                        </td>
                        <td width="69%">
                            <span style="font-size: 10pt;"
                                color="#000000">{{ isset($data['riwayatObatObatan']) ? $data['riwayatObatObatan'] : '-' }}</span>
                        </td>
                    </tr>
                    <tr>
                        <td width="100%" colspan="4"><span style="font-size: 10pt;" color="#000000">GCS</span></td>
                    </tr>
                    <tr>
                        <td width="100%" colspan="4">
                            <table width="100%">
                                <tr>
                                    <td width="0.2%" class="text-top">
                                        <span style="font-size: 10pt;" color="#000000">E</span>
                                    </td>
                                    <td width="0.1%" class="text-top">
                                        <span style="font-size: 10pt;" color="#000000">:</span>
                                    </td>
                                    <td width="10%">
                                        <input type="checkbox"
                                            {{ isset($data['kesadaranE']) && $data['kesadaranE'] == 1 ? 'checked' : '' }} />
                                        <span style="font-size: 9pt;" color="#000000">1</span>
                                    </td>
                                    <td width="10%">
                                        <input type="checkbox"
                                            {{ isset($data['kesadaranE']) && $data['kesadaranE'] == 2 ? 'checked' : '' }} />
                                        <span style="font-size: 9pt;" color="#000000">2</span>
                                    </td>
                                    <td width="10%">
                                        <input type="checkbox"
                                            {{ isset($data['kesadaranE']) && $data['kesadaranE'] == 3 ? 'checked' : '' }} />
                                        <span style="font-size: 9pt;" color="#000000">3</span>
                                    </td>
                                    <td width="10%">
                                        <input type="checkbox"
                                            {{ isset($data['kesadaranE']) && $data['kesadaranE'] == 4 ? 'checked' : '' }} />
                                        <span style="font-size: 9pt;" color="#000000">4</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td width="0.2%" class="text-top">
                                        <span style="font-size: 10pt;" color="#000000">M</span>
                                    </td>
                                    <td width="0.1%" class="text-top">
                                        <span style="font-size: 10pt;" color="#000000">:</span>
                                    </td>
                                    <td width="10%">
                                        <input type="checkbox"
                                            {{ isset($data['kesadaranM']) && $data['kesadaranM'] == 1 ? 'checked' : '' }} />
                                        <span style="font-size: 9pt;" color="#000000">1</span>
                                    </td>
                                    <td width="10%">
                                        <input type="checkbox"
                                            {{ isset($data['kesadaranM']) && $data['kesadaranM'] == 2 ? 'checked' : '' }} />
                                        <span style="font-size: 9pt;" color="#000000">2</span>
                                    </td>
                                    <td width="10%">
                                        <input type="checkbox"
                                            {{ isset($data['kesadaranM']) && $data['kesadaranM'] == 3 ? 'checked' : '' }} />
                                        <span style="font-size: 9pt;" color="#000000">3</span>
                                    </td>
                                    <td width="10%">
                                        <input type="checkbox"
                                            {{ isset($data['kesadaranM']) && $data['kesadaranM'] == 4 ? 'checked' : '' }} />
                                        <span style="font-size: 9pt;" color="#000000">4</span>
                                    </td>
                                    <td width="10%">
                                        <input type="checkbox"
                                            {{ isset($data['kesadaranM']) && $data['kesadaranM'] == 5 ? 'checked' : '' }} />
                                        <span style="font-size: 9pt;" color="#000000">5</span>
                                    </td>
                                    <td width="10%">
                                        <input type="checkbox"
                                            {{ isset($data['kesadaranM']) && $data['kesadaranM'] == 6 ? 'checked' : '' }} />
                                        <span style="font-size: 9pt;" color="#000000">6</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td width="0.2%" class="text-top">
                                        <span style="font-size: 10pt;" color="#000000">V</span>
                                    </td>
                                    <td width="0.1%" class="text-top">
                                        <span style="font-size: 10pt;" color="#000000">:</span>
                                    </td>
                                    <td width="10%">
                                        <input type="checkbox"
                                            {{ isset($data['kesadaranV']) && $data['kesadaranV'] == 1 ? 'checked' : '' }} />
                                        <span style="font-size: 9pt;" color="#000000">1</span>
                                    </td>
                                    <td width="10%">
                                        <input type="checkbox"
                                            {{ isset($data['kesadaranV']) && $data['kesadaranV'] == 2 ? 'checked' : '' }} />
                                        <span style="font-size: 9pt;" color="#000000">2</span>
                                    </td>
                                    <td width="10%">
                                        <input type="checkbox"
                                            {{ isset($data['kesadaranV']) && $data['kesadaranV'] == 3 ? 'checked' : '' }} />
                                        <span style="font-size: 9pt;" color="#000000">3</span>
                                    </td>
                                    <td width="10%">
                                        <input type="checkbox"
                                            {{ isset($data['kesadaranV']) && $data['kesadaranV'] == 4 ? 'checked' : '' }} />
                                        <span style="font-size: 9pt;" color="#000000">4</span>
                                    </td>
                                    <td width="10%">
                                        <input type="checkbox"
                                            {{ isset($data['kesadaranV']) && $data['kesadaranV'] == 5 ? 'checked' : '' }} />
                                        <span style="font-size: 9pt;" color="#000000">5</span>
                                    </td>
                                </tr>

                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td width="100%" colspan="4">
                            <table width="100%">
                                <tr>
                                    <td width="1%" class="text-top">
                                        <span style="font-size: 10pt;" color="#000000">Jumlah Total</span>
                                    </td>
                                    <td width="0.1%" class="text-top">
                                        <span style="font-size: 10pt;" color="#000000">:</span>
                                    </td>
                                    <td width="10%">
                                        <span style="font-size: 10pt;"
                                            color="#000000">{{ isset($data['totalKesadaran']) ? $data['totalKesadaran'] : '-' }}</span>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <tr>
                        <td width="100%" colspan="4"><span style="font-size: 10pt;"
                                color="#000000">Keterangan</span></td>
                    </tr>
                    <tr>
                        <td width="100%" colspan="4">
                            <table width="100%">
                                <tr>
                                    <td width="15%" class="text-top">
                                        <input type="checkbox"
                                            {{ isset($data['rangeKesadaran']) && $data['rangeKesadaran']['poin'] == 15 ? 'checked' : '' }} />
                                        <span style="font-size: 9pt;" color="#000000">CMC
                                            (14-15)</span>
                                    </td>
                                    <td width="15%" class="text-top">
                                        <input type="checkbox"
                                            {{ isset($data['rangeKesadaran']) && $data['rangeKesadaran']['poin'] == 13 ? 'checked' : '' }} />
                                        <span style="font-size: 9pt;" color="#000000">Apatis
                                            (12-13)</span>
                                    </td>
                                    <td width="15%" class="text-top">
                                        <input type="checkbox"
                                            {{ isset($data['rangeKesadaran']) && $data['rangeKesadaran']['poin'] == 11 ? 'checked' : '' }} />
                                        <span style="font-size: 9pt;" color="#000000">Somnolen
                                            (10-11)</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td width="15%" class="text-top">
                                        <input type="checkbox"
                                            {{ isset($data['rangeKesadaran']) && $data['rangeKesadaran']['poin'] == 9 ? 'checked' : '' }} />
                                        <span style="font-size: 9pt;" color="#000000">Delirium
                                            (7-9)</span>
                                    </td>
                                    <td width="15%" class="text-top">
                                        <input type="checkbox"
                                            {{ isset($data['rangeKesadaran']) && $data['rangeKesadaran']['poin'] == 6 ? 'checked' : '' }} />
                                        <span style="font-size: 9pt;" color="#000000">Stupor
                                            (4-6)</span>
                                    </td>
                                    <td width="15%" class="text-top">
                                        <input type="checkbox"
                                            {{ isset($data['rangeKesadaran']) && $data['rangeKesadaran']['poin'] == 3 ? 'checked' : '' }} />
                                        <span style="font-size: 9pt;" color="#000000">Koma ( <= 3)</span>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    <tr>

                    <tr>
                        <td width="100%" colspan="4"><span style="font-size: 10pt;" color="#000000">Keadaan
                                Umum</span></td>
                    </tr>
                    <tr>
                        <td width="100%" colspan="4">
                            <table width="100%">
                                <tr>
                                    <td width="20%">
                                        <input type="checkbox"
                                            {{ isset($data['keadaanUmum']) && $data['keadaanUmum'] == 'Sakit Ringan' ? 'checked' : '' }} />
                                        <span style="font-size: 9pt;" color="#000000">Sakit
                                            ringan</span>
                                    </td>
                                    <td width="20%">
                                        <input type="checkbox"
                                            {{ isset($data['keadaanUmum']) && $data['keadaanUmum'] == 'Sakit Sedang' ? 'checked' : '' }} />
                                        <span style="font-size: 9pt;" color="#000000">Sakit
                                            sedang</span>
                                    </td>
                                    <td width="20%">
                                        <input type="checkbox"
                                            {{ isset($data['keadaanUmum']) && $data['keadaanUmum'] == 'Sakit Berat' ? 'checked' : '' }} />
                                        <span style="font-size: 9pt;" color="#000000">Sakit
                                            berat</span>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <tr>
                        <td width="100%" colspan="4"><span style="font-size: 10pt;" color="#000000">Tanda tanda
                                vital</span></td>
                    </tr>
                    <tr>
                        <td width="100%" colspan="4">
                            <table width="100%">
                                <tr>
                                    <td width="1%" class="text-top">
                                        <span style="font-size: 10pt;" color="#000000">TD</span>
                                    </td>
                                    <td width="0.1%" class="text-top">
                                        <span style="font-size: 10pt;" color="#000000">:</span>
                                    </td>
                                    <td width="10%">
                                        <span style="font-size: 10pt;"
                                            color="#000000">{{ isset($data['tekananDarah']) ? $data['tekananDarah'] : '-' }}
                                            mmHg</span>
                                    </td>

                                    <td width="1%" class="text-top">
                                        <span style="font-size: 10pt;" color="#000000">RR</span>
                                    </td>
                                    <td width="0.1%" class="text-top">
                                        <span style="font-size: 10pt;" color="#000000">:</span>
                                    </td>
                                    <td width="10%">
                                        <span style="font-size: 10pt;"
                                            color="#000000">{{ isset($data['RR']) ? $data['RR'] : '-' }} x/min</span>
                                    </td>

                                    <td width="1%" class="text-top">
                                        <span style="font-size: 10pt;" color="#000000">HR</span>
                                    </td>
                                    <td width="0.1%" class="text-top">
                                        <span style="font-size: 10pt;" color="#000000">:</span>
                                    </td>
                                    <td width="10%">
                                        <span style="font-size: 10pt;"
                                            color="#000000">{{ isset($data['nadi']) ? $data['nadi'] : '-' }}</span>
                                    </td>

                                    <td width="1%" class="text-top">
                                        <span style="font-size: 10pt;" color="#000000">Suhu</span>
                                    </td>
                                    <td width="0.1%" class="text-top">
                                        <span style="font-size: 10pt;" color="#000000">:</span>
                                    </td>
                                    <td width="10%">
                                        <span style="font-size: 10pt;"
                                            color="#000000">{{ isset($data['suhu']) ? $data['suhu'] : '-' }} C</span>
                                    </td>
                                </tr>

                                <tr>
                                    <td width="1%" class="text-top">
                                        <span style="font-size: 10pt;" color="#000000">SPO2</span>
                                    </td>
                                    <td width="0.1%" class="text-top">
                                        <span style="font-size: 10pt;" color="#000000">:</span>
                                    </td>
                                    <td width="10%">
                                        <span style="font-size: 10pt;"
                                            color="#000000">{{ isset($data['SPO2']) ? $data['SPO2'] : '-' }}</span>
                                    </td>

                                    <td width="1%" class="text-top">
                                        <span style="font-size: 10pt;" color="#000000">TB</span>
                                    </td>
                                    <td width="0.1%" class="text-top">
                                        <span style="font-size: 10pt;" color="#000000">:</span>
                                    </td>
                                    <td width="10%">
                                        <span style="font-size: 10pt;"
                                            color="#000000">{{ isset($data['tinggiBadan']) ? $data['tinggiBadan'] : '-' }}
                                            Cm</span>
                                    </td>

                                    <td width="1%" class="text-top">
                                        <span style="font-size: 10pt;" color="#000000">BB</span>
                                    </td>
                                    <td width="0.1%" class="text-top">
                                        <span style="font-size: 10pt;" color="#000000">:</span>
                                    </td>
                                    <td width="10%">
                                        <span style="font-size: 10pt;"
                                            color="#000000">{{ isset($data['beratBadan']) ? $data['beratBadan'] : '-' }}
                                            Kg</span>
                                    </td>

                                    <td width="1%" class="text-top">
                                        <span style="font-size: 10pt;" color="#000000">LP</span>
                                    </td>
                                    <td width="0.1%" class="text-top">
                                        <span style="font-size: 10pt;" color="#000000">:</span>
                                    </td>
                                    <td width="10%">
                                        <span style="font-size: 10pt;"
                                            color="#000000">{{ isset($data['lingkarPerut']) ? $data['lingkarPerut'] : '-' }}</span>
                                    </td>
                                </tr>

                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td width="100%" colspan="4" height="10"></td>
                    </tr>
                    <tr>
                        <td width="100%" colspan="4"><span style="font-size: 10pt;" class="judulLabel"
                                color="#000000">B. PENGKAJIAN NYERI</span></td>
                    </tr>
                    <tr>
                        <td width="100%" colspan="4"><span style="font-size: 10pt;" color="#000000">VAS</span></td>
                    </tr>
                    <tr>
                        <td width="100%" colspan="4">
                            <table width="100%">
                                <tr>
                                    <td width="100%" colspan="4">
                                        <span style="font-size: 10pt;"color="#000000">Score</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td width="20%">
                                        <input type="checkbox"
                                            {{ isset($data['vas']) && $data['vas'] == 0 ? 'checked' : '' }} />
                                        <span style="font-size: 9pt;" color="#000000">0 = Tidak
                                            Nyeri</span>
                                    </td>
                                    <td width="20%">
                                        <input type="checkbox"
                                            {{ isset($data['vas']) && $data['vas'] <= 3 ? 'checked' : '' }} />
                                        <span style="font-size: 9pt;" color="#000000">1 - 3 =
                                            Nyeri Ringan</span>
                                    </td>
                                    <td width="20%">
                                        <input type="checkbox"
                                            {{ isset($data['vas']) && $data['vas'] <= 6 ? 'checked' : '' }} />
                                        <span style="font-size: 9pt;" color="#000000">4 - 6 =
                                            Nyeri Sedang</span>
                                    </td>
                                    <td width="20%">
                                        <input type="checkbox"
                                            {{ isset($data['vas']) && $data['vas'] > 6 ? 'checked' : '' }} />
                                        <span style="font-size: 9pt;" color="#000000">7 - 10 =
                                            Nyeri Berat</span>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td width="100%" colspan="4"><span style="font-size: 10pt;" color="#000000">Kualitas
                                Nyeri</span></td>
                    </tr>
                    <tr>
                        <td width="100%" colspan="4">
                            <table width="100%">
                                <tr>
                                    <td width="20%">
                                        <input type="checkbox"
                                            {{ isset($data['kualitasNyeri']) && $data['kualitasNyeri'] == 'Nyeri Tumpul' ? 'checked' : '' }} />
                                        <span style="font-size: 9pt;" color="#000000">Nyeri
                                            Tumpul</span>
                                    </td>
                                    <td width="20%">
                                        <input type="checkbox"
                                            {{ isset($data['kualitasNyeri']) && $data['kualitasNyeri'] == 'Nyeri Tajam' ? 'checked' : '' }} />
                                        <span style="font-size: 9pt;" color="#000000">Nyeri
                                            Tajam</span>
                                    </td>
                                    <td width="20%">
                                        <input type="checkbox"
                                            {{ isset($data['kualitasNyeri']) && $data['kualitasNyeri'] == 'Panas/ Terbakar' ? 'checked' : '' }} />
                                        <span style="font-size: 9pt;" color="#000000">Panas/Terbakar</span>
                                    </td>
                                    <td width="40%">
                                        <input type="checkbox"
                                            {{ isset($data['kualitasNyeri']) && $data['kualitasNyeri'] == 'Lainnya' ? 'checked' : '' }} />
                                        <span style="font-size: 9pt;" color="#000000">Lainnya</span>
                                        <span style="font-size: 10pt;"
                                            color="#000000">{{ isset($data['kualitasLainnya']) ? $data['kualitasLainnya'] : '' }}</span>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td width="100%" colspan="4"><span style="font-size: 10pt;" color="#000000">Menjalar</span>
                        </td>
                    </tr>
                    <tr>
                        <td width="100%" colspan="4">
                            <table width="100%">
                                <tr>
                                    <td width="20%">
                                        <input type="checkbox"
                                            {{ isset($data['menjalar']) && $data['menjalar'] == 'Tidak' ? 'checked' : '' }} />
                                        <span style="font-size: 9pt;"color="#000000">Tidak</span>
                                    </td>
                                    <td width="60%">
                                        <input type="checkbox"
                                            {{ isset($data['menjalar']) && $data['menjalar'] == 'Ya, Sebutkan' ? 'checked' : '' }} />
                                        <span style="font-size: 9pt;" color="#000000">Ya, ke</span>
                                        <span style="font-size: 10pt;"
                                            color="#000000">{{ isset($data['keteranganMenjalar']) ? $data['keteranganMenjalar'] : '' }}</span>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <tr>
                        <td width="100%" colspan="4"><span style="font-size: 10pt;" color="#000000">Frekuensi
                                Nyeri</span></td>
                    </tr>
                    <tr>
                        <td width="100%" colspan="4">
                            <table width="100%">
                                <tr>
                                    <td width="20%">
                                        <input type="checkbox"
                                            {{ isset($data['frekuensiNyeri']) && $data['frekuensiNyeri'] == 'Jarang' ? 'checked' : '' }} />
                                        <span style="font-size: 9pt;" color="#000000">Jarang</span>
                                    </td>
                                    <td width="20%">
                                        <input type="checkbox"
                                            {{ isset($data['frekuensiNyeri']) && $data['frekuensiNyeri'] == 'Hilang / Timbul' ? 'checked' : '' }} />
                                        <span style="font-size: 9pt;" color="#000000">Hilang/Timbul</span>
                                    </td>
                                    <td width="20%">
                                        <input type="checkbox"
                                            {{ isset($data['frekuensiNyeri']) && $data['frekuensiNyeri'] == 'Terus Menerus' ? 'checked' : '' }} />
                                        <span style="font-size: 9pt;" color="#000000">Terus menerus</span>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td width="100%" colspan="4" height="10"></td>
                    </tr>
                    <tr>
                        <td width="100%" colspan="4"><span style="font-size: 10pt;" class="judulLabel"
                                color="#000000">C. SKALA RISIKO JATUH</span></td>
                    </tr>
                    <tr>
                        <td width="100%" colspan="4"><span style="font-size: 10pt;" color="#000000">1.Perhatikan
                                cara berjalan pasien saat akan duduk dikursi, apakah pasien tampak
                                seimbang(sempoyongan/limbung) ?</span></td>
                    </tr>
                    <tr>
                        <td width="100%" colspan="4">
                            <table width="100%">
                                <tr>
                                    <td width="20%">
                                        <input type="checkbox"
                                            {{ isset($data['caraBerjalanNormalatauTidak']) && $data['caraBerjalanNormalatauTidak'] == 'Tidak' ? 'checked' : '' }} />
                                        <span style="font-size: 9pt;" color="#000000">Ya</span>
                                    </td>
                                    <td width="20%"
                                        {{ isset($data['caraBerjalanNormalatauTidak']) && $data['caraBerjalanNormalatauTidak'] == 'Ya' ? 'checked' : '' }}>
                                        <input type="checkbox" /><span style="font-size: 9pt;"
                                            color="#000000">Tidak</span>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td width="100%" colspan="4"><span style="font-size: 10pt;" color="#000000">2.Apakah pasien
                                memegang pinggiran kursi atau meja atau benda lain sebagai penopang saat akan duduk ?</span>
                        </td>
                    </tr>
                    <tr>
                        <td width="100%" colspan="4">
                            <table width="100%">
                                <tr>
                                    <td width="20%">
                                        <input type="checkbox"
                                            {{ isset($data['perluPenompang']) && $data['perluPenompang'] == 'Ya' ? 'checked' : '' }} />
                                        <span style="font-size: 9pt;" color="#000000">Ya</span>
                                    </td>
                                    <td width="20%">
                                        <input type="checkbox"
                                            {{ isset($data['perluPenompang']) && $data['perluPenompang'] == 'Tidak' ? 'checked' : '' }} />
                                        <span style="font-size: 9pt;" color="#000000">Tidak</span>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td width="100%" colspan="4"><span style="font-size: 10pt;" color="#000000">3.Hasil</span>
                        </td>
                    </tr>
                    <tr>
                        <td width="100%" colspan="4">
                            <table width="100%">
                                <tr>
                                    <td width="35%">
                                        <input type="checkbox"
                                            {{ isset($data['hasilResikoJatuh']) && $data['hasilResikoJatuh'] == 'Tidak berisiko (tidak ditemukan a dan b)' ? 'checked' : '' }} />
                                        <span style="font-size: 9pt;" color="#000000">Tidak berisiko (tidak ditemukan a
                                            dan b)</span>
                                    </td>

                                    <td width="35%">
                                        <input type="checkbox"
                                            {{ isset($data['hasilResikoJatuh']) && $data['hasilResikoJatuh'] == 'Risiko tinggi (ditemukan a dan b)' ? 'checked' : '' }} />
                                        <span style="font-size: 9pt;" color="#000000">Risiko tinggi (ditemukan a dan
                                            b)</span>
                                    </td>
                                </tr>

                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td width="100%" colspan="4">
                            <table width="100%">
                                <tr>
                                    <td width="100%" colspan="4">
                                        <input type="checkbox"
                                            {{ isset($data['hasilResikoJatuh']) && $data['hasilResikoJatuh'] == 'Risiko rendah-sedang (ditemukan (a atau b)' ? 'checked' : '' }} />
                                        <span style="font-size: 9pt;" color="#000000">Risiko rendah-sedang (ditemukan a
                                            dan b)</span>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td width="100%" colspan="4" height="10"></td>
                    </tr>
                    <tr>
                        <td width="100%" colspan="4"><span style="font-size: 10pt;" class="judulLabel"
                                color="#000000">D. SKRINING GIZI AWAL</span></td>
                    </tr>
                    <tr>
                        <td width="100%" colspan="4"><span style="font-size: 10pt;" color="#000000">RISIKO
                                NUTRISIONAL</span></td>
                    </tr>
                    <tr>
                        <td width="100%" colspan="4"><span style="font-size: 10pt;" color="#000000">Malnutrition
                                Screening Tools (MST)</span></td>
                    </tr>
                    <tr>
                        <td width="100%" colspan="4"><span style="font-size: 10pt;" color="#000000">1.Apakah ada
                                penurunan berat badan yang tidak diinginkan selama 6 bulan terakhir ?</span></td>
                    </tr>
                    <tr>
                        <td width="100%" colspan="4">
                            <table width="100%">
                                <tr>
                                    <td width="20%">
                                        <input type="checkbox"
                                            {{ isset($data['penurunanBB']) && $data['penurunanBB']['keterangan'] == 'Tidak' ? 'checked' : '' }} />
                                        <span style="font-size: 9pt;" color="#000000">a. Tidak</span>
                                    </td>
                                    <td width="20%">
                                        <span style="font-size: 9pt;" color="#000000">POIN = 0</span>
                                    </td>
                                    <td width="20%">
                                        <input type="checkbox"
                                            {{ isset($data['penurunanBB']) && $data['penurunanBB']['keterangan'] == 'Tidak Yakin' ? 'checked' : '' }} />
                                        <span style="font-size: 9pt;" color="#000000">b. Tidak Yakin</span>
                                    </td>
                                    <td width="20%">
                                        <span style="font-size: 9pt;" color="#000000">POIN = 2</span>
                                    </td>
                                </tr>

                                <tr>
                                    <td width="20%">
                                        <input type="checkbox"
                                            {{ isset($data['penurunanBB']) && $data['penurunanBB']['keterangan'] == 'Ya,1-5 Kg' ? 'checked' : '' }} />
                                        <span style="font-size: 9pt;" color="#000000">c. Ya, 1-5 Kg</span>
                                    </td>
                                    <td width="20%">
                                        <span style="font-size: 9pt;" color="#000000">POIN = 1</span>
                                    </td>
                                    <td width="20%">
                                        <input type="checkbox"
                                            {{ isset($data['penurunanBB']) && $data['penurunanBB']['keterangan'] == '6-10 Kg' ? 'checked' : '' }} />
                                        <span style="font-size: 9pt;" color="#000000">6-10 Kg</span>
                                    </td>
                                    <td width="20%">
                                        <span style="font-size: 9pt;" color="#000000">POIN = 2</span>
                                    </td>
                                </tr>

                                <tr>
                                    <td width="20%">
                                        <input type="checkbox"
                                            {{ isset($data['penurunanBB']) && $data['penurunanBB']['keterangan'] == '11-15 Kg' ? 'checked' : '' }} />
                                        <span style="font-size: 9pt;" color="#000000">11-15 Kg</span>
                                    </td>
                                    <td width="20%">
                                        <span style="font-size: 9pt;" color="#000000">POIN = 3</span>
                                    </td>
                                    <td width="20%">
                                        <input type="checkbox"
                                            {{ isset($data['penurunanBB']) && $data['penurunanBB']['keterangan'] == '> 15 Kg' ? 'checked' : '' }} />
                                        <span style="font-size: 9pt;" color="#000000"> > 15 Kg</span>
                                    </td>
                                    <td width="20%">
                                        <span style="font-size: 9pt;" color="#000000">POIN = 4</span>
                                    </td>
                                </tr>

                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td width="100%" colspan="4"><span style="font-size: 10pt;" color="#000000">2.Apakah asupan
                                makan menurun yang dikarenakan adanya penurunan nafsu makan/kesulitan menerima makan
                                ?</span></td>
                    </tr>
                    <tr>
                        <td width="100%" colspan="4">
                            <table width="100%">
                                <tr>
                                    <td width="20%">
                                        <input type="checkbox"
                                            {{ isset($data['penurunanNafsuMakan']) && $data['penurunanNafsuMakan']['poin'] == 0 ? 'checked' : '' }} />
                                        <span style="font-size: 9pt;" color="#000000">Tidak</span>
                                    </td>
                                    <td width="20%">
                                        <span style="font-size: 9pt;" color="#000000">POIN = 0</span>
                                    </td>
                                    <td width="20%">
                                        <input type="checkbox"
                                            {{ isset($data['penurunanNafsuMakan']) && $data['penurunanNafsuMakan']['poin'] == 1 ? 'checked' : '' }} />
                                        <span style="font-size: 9pt;" color="#000000">Ya</span>
                                    </td>
                                    <td width="20%">
                                        <span style="font-size: 9pt;" color="#000000">POIN = 1</span>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    <tr>
                    <tr>
                        <td width="100%" colspan="4">
                            <table width="100%">
                                <tr>
                                    <td width="1%" class="text-top">
                                        <span style="font-size: 10pt;" color="#000000">TOTAL SCORE</span>
                                    </td>
                                    <td width="0.1%" class="text-top">
                                        <span style="font-size: 10pt;" color="#000000">:</span>
                                    </td>
                                    <td width="10%">
                                        <span style="font-size: 10pt;"
                                            color="#000000">{{ isset($data['totalNilaiMST']) ? $data['totalNilaiMST'] : '-' }}</span>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td width="100%" colspan="4"><span style="font-size: 10pt;"
                                color="#000000">Keterangan</span></td>
                    </tr>
                    <tr>
                        <td width="100%" colspan="4">
                            <table width="100%">
                                <tr>
                                    <td width="13%" class="text-top">
                                        <span style="font-size: 9pt;" color="#000000">Skor 0 - 1 :tidak beresiko</span>
                                    </td>
                                    <td width="22%" class="text-top">
                                        <span style="font-size: 9pt;" color="#000000">Skor 2 - 3 :berisiko (Asuhan Gizi
                                            oleh Dietisen)</span>
                                    </td>
                                    <td width="20%" class="text-top">
                                        <span style="font-size: 9pt;" color="#000000">Skor > 4 :Malnutrisi (Asuhan Gizi
                                            oleh Dietisen)</span>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    <tr>
                    <tr>
                        <td width="100%" colspan="4" height="10"></td>
                    </tr>
                    <tr>
                        <td width="100%" colspan="4"><span style="font-size: 10pt;" class="judulLabel"
                                color="#000000">E. STATUS PSIKOLOGIS</span></td>
                    </tr>
                    <tr>
                        <td width="100%" colspan="4">
                            <table width="100%">
                                <tr>
                                    <td width="20%">
                                        <input type="checkbox"
                                            {{ isset($data['psikologis']) && $data['psikologis'] == 'Depresi' ? 'checked' : '' }} />
                                        <span style="font-size: 9pt;" color="#000000">Depresi</span>
                                    </td>
                                    <td width="20%">
                                        <input type="checkbox"
                                            {{ isset($data['psikologis']) && $data['psikologis'] == 'Takut' ? 'checked' : '' }} />
                                        <span style="font-size: 9pt;" color="#000000">Takut</span>
                                    </td>
                                    <td width="20%">
                                        <input type="checkbox"
                                            {{ isset($data['psikologis']) && $data['psikologis'] == 'Agresif' ? 'checked' : '' }} />
                                        <span style="font-size: 9pt;" color="#000000">Agresif</span>
                                    </td>
                                    <td width="20%">
                                        <input type="checkbox"
                                            {{ isset($data['psikologis']) && $data['psikologis'] == 'Melukai diri sendiri' ? 'checked' : '' }} />
                                        <span style="font-size: 9pt;" color="#000000">Melukai diri sendiri</span>
                                    </td>
                                    <td width="20%">
                                        <input type="checkbox"
                                            {{ isset($data['psikologis']) && $data['psikologis'] == 'Tidak ada gejala' ? 'checked' : '' }} />
                                        <span style="font-size: 9pt;" color="#000000">Tidak ada gejala</span>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td width="100%" colspan="4" height="10"></td>
                    </tr>
                    <tr>
                        <td width="100%" colspan="4"><span style="font-size: 10pt;" class="judulLabel"
                                color="#000000">F. PENGKAJIAN EDUKASI PASIEN DAN KELUARGA</span></td>
                    </tr>
                    <tr>
                        <td width="100%" colspan="4">
                            <span style="font-size: 10pt;" color="#000000">A. Kemampuan dan Kemauan Edukasi</span>
                        </td>
                    </tr>
                    <tr>
                        <td width="100%" colspan="4">
                            <table width="100%">

                                <tr>
                                    <td width="100%" colspan="4" height="10"></td>
                                </tr>
                                <tr>
                                    <td width="24%"><span style="font-size: 9pt;" color="#000000">Nama
                                            Panggilan</span></td>
                                    <td width="1%"><span style="font-size: 9pt;" color="#000000">:</span></td>
                                    <td width="75%"><span style="font-size: 9pt;"
                                            color="#000000">{{ isset($data['namaPanggilan']) ? $data['namaPanggilan'] : '' }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td width="24%"><span style="font-size: 9pt;" color="#000000">Agama</span></td>
                                    <td width="1%"><span style="font-size: 9pt;" color="#000000">:</span></td>
                                    <td width="75%"><span style="font-size: 9pt;"
                                            color="#000000">{{ isset($data['agama']) ? $data['agama'] : '' }}</span></td>
                                </tr>
                                <tr>
                                    <td width="24%"><span style="font-size: 9pt;" color="#000000">Nilai-nilai yang
                                            dianut</span></td>
                                    <td width="1%"><span style="font-size: 9pt;" color="#000000">:</span></td>
                                    <td width="75%"><span style="font-size: 9pt;"
                                            color="#000000">{{ isset($data['nilaiYangdianut']) ? $data['nilaiYangdianut'] : '' }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td width="24%"><span style="font-size: 9pt;" color="#000000">Pendidikan</span>
                                    </td>
                                    <td width="1%"><span style="font-size: 9pt;" color="#000000">:</span></td>
                                    <td width="75%"><span style="font-size: 9pt;"
                                            color="#000000">{{ isset($data['pendidikan']) ? $data['pendidikan'] : '' }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td width="100%" colspan="4">
                                        <span style="font-size: 10pt;" color="#000000">Kesulitan Komunikasi</span>
                                    </td>
                                </tr>
                                <tr>

                                    <td width="100%" colspan="4">
                                        <table width="100%">
                                            <tr>
                                                <td width="15%">
                                                    <input type="checkbox"
                                                        {{ isset($data['kesulitanKomunikasi']) && $data['kesulitanKomunikasi'] == 'Tidak ada' ? 'checked' : '' }} />
                                                    <span style="font-size: 9pt;"color="#000000">Tidak ada</span>
                                                </td>
                                                <td width="15%">
                                                    <input type="checkbox"
                                                        {{ isset($data['kesulitanKomunikasi']) && $data['kesulitanKomunikasi'] == 'Ada' ? 'checked' : '' }} /><span
                                                        style="font-size: 9pt;" color="#000000">Ada, Jelaskan</span>
                                                </td>
                                                <td width="40%">
                                                    <span style="font-size: 9pt;"
                                                        color="#000000">{{ isset($data['ketKesulitanKomunikasi']) ? $data['ketKesulitanKomunikasi'] : '' }}</span>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>

                                <tr>
                                    <td width="100%" colspan="4"><span style="font-size: 10pt;"
                                            color="#000000">Bahasa yang dipakai</span></td>
                                </tr>
                                <tr>

                                    <td width="100%" colspan="4">
                                        <table width="100%">
                                            <tr>
                                                <td width="18%">
                                                    <input type="checkbox"
                                                        {{ isset($data['bahasa']) && $data['bahasa'] == 'Indonesia' ? 'checked' : '' }} />
                                                    <span style="font-size: 9pt;" color="#000000">Indonesia</span>
                                                </td>
                                                <td width="17%">
                                                    <input type="checkbox"
                                                        {{ isset($data['bahasa']) && $data['bahasa'] == 'Mandarin' ? 'checked' : '' }} />
                                                    <span style="font-size: 9pt;" color="#000000">Mandarin</span>
                                                </td>
                                                <td width="15%">
                                                    <input type="checkbox"
                                                        {{ isset($data['bahasa']) && $data['bahasa'] == 'Inggris' ? 'checked' : '' }} />
                                                    <span style="font-size: 9pt;" color="#000000">Inggris</span>
                                                </td>
                                                <td width="15%">
                                                    <input type="checkbox"
                                                        {{ isset($data['bahasa']) && $data['bahasa'] == 'Lainnya' ? 'checked' : '' }} />
                                                    <span style="font-size: 9pt;" color="#000000">Lainnya</span>
                                                </td>
                                                <td>
                                                    <span style="font-size: 9pt;"
                                                        color="#000000">{{ isset($data['ketBahasa']) ? $data['ketBahasa'] : '' }}</span>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>

                                <tr>
                                    <td width="100%" colspan="4"><span style="font-size: 10pt;"
                                            color="#000000">Penterjemah</span></td>
                                </tr>
                                <tr>

                                    <td width="100%" colspan="4">
                                        <table width="100%">
                                            <tr>
                                                <td width="15%">
                                                    <input type="checkbox"
                                                        {{ isset($data['penterjemah']) && $data['penterjemah'] == 'Perlu' ? 'checked' : '' }} /><span
                                                        style="font-size: 9pt;" color="#000000">Perlu</span>
                                                </td>
                                                <td width="15%">
                                                    <input type="checkbox"
                                                        {{ isset($data['penterjemah']) && $data['penterjemah'] == 'Tidak Perlu' ? 'checked' : '' }} /><span
                                                        style="font-size: 9pt;" color="#000000">Tidak Perlu</span>
                                                </td>
                                                <td width="15%">
                                                    <input type="checkbox"
                                                        {{ isset($data['penterjemah']) && $data['penterjemah'] == 'Lainnya' ? 'checked' : '' }} /><span
                                                        style="font-size: 9pt;" color="#000000">Lainnya</span>
                                                </td>
                                                <td width="40%">
                                                    <span style="font-size: 9pt;"
                                                        color="#000000">{{ isset($data['ketPenterjemah']) ? $data['ketPenterjemah'] : '' }}</span>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>

                                <tr>
                                    <td width="100%" colspan="4"><span style="font-size: 10pt;"
                                            color="#000000">Identifikasi dan berikan tanda (V) pada hambatan yang dapat
                                            mempengaruhi proses dan hasil edukasi</span></td>
                                </tr>
                                <tr>

                                    <td width="100%" colspan="4">
                                        <table width="100%">
                                            <tr>
                                                <td width="25%">
                                                    <input type="checkbox"
                                                        {{ isset($data['hambatan_0']) && $data['hambatan_0'] == 'Tidak ada hambatan edukasi' ? 'checked' : '' }} />
                                                    <span style="font-size: 9pt;" color="#000000">Tidak ada hambatan
                                                        edukasi</span>
                                                </td>
                                                <td width="25%">
                                                    <input type="checkbox"
                                                        {{ isset($data['hambatan_1']) && $data['hambatan_1'] == 'Gangguan Penglihatan' ? 'checked' : '' }} />
                                                    <span style="font-size: 9pt;" color="#000000">Gangguan
                                                        penglihatan</span>
                                                </td>
                                                <td width="25%">
                                                    <input type="checkbox"
                                                        {{ isset($data['hambatan_2']) && $data['hambatan_2'] == 'Gangguan Pendengaran' ? 'checked' : '' }} />
                                                    <span style="font-size: 9pt;" color="#000000">Gangguan
                                                        pendengaran</span>
                                                </td>

                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                <tr>
                                    <td width="100%" colspan="4">
                                        <table width="100%">
                                            <tr>
                                                <td width="20%">
                                                    <input type="checkbox"
                                                        {{ isset($data['hambatan_3']) && $data['hambatan_3'] == 'Gangguan Emosional' ? 'checked' : '' }} />
                                                    <span style="font-size: 9pt;" color="#000000">Gangguan
                                                        emosional</span>
                                                </td>
                                                <td width="20%">
                                                    <input type="checkbox"
                                                        {{ isset($data['hambatan_4']) && $data['hambatan_4'] == 'Gangguan Proses Pikir' ? 'checked' : '' }} />
                                                    <span style="font-size: 9pt;" color="#000000">Gangguan proses
                                                        pikir</span>
                                                </td>
                                                <td width="20%">
                                                    <input type="checkbox"
                                                        {{ isset($data['hambatan_5']) && $data['hambatan_5'] == 'Hambatan Bahasa' ? 'checked' : '' }} />
                                                    <span style="font-size: 9pt;" color="#000000">Hambatan bahasa</span>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                <tr>
                                    <td width="100%" colspan="4">
                                        <table width="100%">
                                            <tr>
                                                <td width="20%">
                                                    <input type="checkbox"
                                                        {{ isset($data['hambatan_6']) && $data['hambatan_6'] == 'Kemampuan Membaca' ? 'checked' : '' }} />
                                                    <span style="font-size: 9pt;" color="#000000">Kemampuan membaca</span>
                                                </td>
                                                <td width="20%">
                                                    <input type="checkbox"
                                                        {{ isset($data['hambatan_7']) && $data['hambatan_7'] == 'Motivasi Belajar' ? 'checked' : '' }} />
                                                    <span style="font-size: 9pt;" color="#000000">Motivasi belajar</span>
                                                </td>
                                                <td width="20%">
                                                    <input type="checkbox"
                                                        {{ isset($data['hambatan_8']) && $data['hambatan_8'] == 'Batas Jasmani dan Kognitif' ? 'checked' : '' }} />
                                                    <span style="font-size: 9pt;" color="#000000">Batas jasmani dan
                                                        kognitif</span>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                <tr>
                                    <td width="100%" colspan="4">
                                        <table width="100%">
                                            <tr>
                                                <td width="20%">
                                                    <input type="checkbox"
                                                        {{ isset($data['hambatan_9']) && $data['hambatan_9'] == 'Hambatan Lainnya' ? 'checked' : '' }} />
                                                    <span style="font-size: 10pt;" color="#000000">Hambatan lainnya</span>
                                                </td>
                                                <td width="40%">
                                                    <span style="font-size: 9pt;" color="#000000">
                                                        {{ isset($data['keteranganHambatan']) ? $data['keteranganHambatan'] : '' }}
                                                    </span>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>

                                <tr>
                                    <td width="100%" colspan="4"><span style="font-size: 10pt;"
                                            color="#000000">Kesediaan pasien / keluarga* untuk menerima informasi yang
                                            diberikan</span></td>
                                </tr>
                                <tr>
                                    <td width="100%" colspan="4">
                                        <table width="100%">
                                            <tr>
                                                <td width="15%">
                                                    <input type="checkbox"
                                                        {{ isset($data['kesediaanMenerimaInformasi']) && $data['kesediaanMenerimaInformasi'] == 'Ya' ? 'checked' : '' }} />
                                                    <span style="font-size: 9pt;" color="#000000">Ya</span>
                                                </td>
                                                <td width="15%">
                                                    <input type="checkbox"
                                                        {{ isset($data['kesediaanMenerimaInformasi']) && $data['kesediaanMenerimaInformasi'] == 'Tidak' ? 'checked' : '' }} />
                                                    <span style="font-size: 9pt;" color="#000000">Tidak</span>
                                                </td>
                                                <td width="40%">
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                <tr>
                                    <td width="100%" colspan="4" height="10"></td>
                                </tr>
                                <tr>
                                    <td width="100%" colspan="4">
                                        <span style="font-size: 10pt;" color="#000000">B. Kebutuhan Edukasi</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td width="100%" colspan="4" height="10"></td>
                                </tr>
                                <tr>
                                    <td width="100%" colspan="4">
                                        <span style="font-size: 10pt;" color="#000000">Program Edukasi/Bidang
                                            Disiplin</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td width="100%" colspan="4">
                                        <table width="100%">
                                            <tr>
                                                <td width="30%">
                                                    <input type="checkbox"
                                                        {{ isset($data['kebutuhanEdukasi_0']) && $data['kebutuhanEdukasi_0']['code'] == 'KMD' ? 'checked' : '' }} />
                                                    <span style="font-size: 9pt;" color="#000000">Kondisi medis dan
                                                        diagnosa/Medis</span>
                                                </td>
                                                <td width="30%">
                                                    <input type="checkbox"
                                                        {{ isset($data['kebutuhanEdukasi_1']) && $data['kebutuhanEdukasi_1']['code'] == 'RPM' ? 'checked' : '' }} />
                                                    <span style="font-size: 9pt;" color="#000000">Rencana
                                                        pengobatan/Medis</span>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                <tr>
                                    <td width="100%" colspan="4">
                                        <table width="100%">
                                            <tr>
                                                <td width="30%">
                                                    <input type="checkbox"
                                                        {{ isset($data['kebutuhanEdukasi_2']) && $data['kebutuhanEdukasi_2']['code'] == 'PAM' ? 'checked' : '' }} />
                                                    <span style="font-size: 9pt;" color="#000000">Penggunaan alat
                                                        medis/Keperawatan</span>
                                                </td>
                                                <td width="30%">
                                                    <input type="checkbox"
                                                        {{ isset($data['kebutuhanEdukasi_3']) && $data['kebutuhanEdukasi_3']['code'] == 'POA' ? 'checked' : '' }} />
                                                    <span style="font-size: 9pt;" color="#000000">Penggunaan obat secara
                                                        aman dan efektif/Farmasi</span>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                <tr>
                                    <td width="100%" colspan="4">
                                        <table width="100%">
                                            <tr>
                                                <td width="30%">
                                                    <input type="checkbox"
                                                        {{ isset($data['kebutuhanEdukasi_4']) && $data['kebutuhanEdukasi_4']['code'] == 'RK' ? 'checked' : '' }} />
                                                    <span style="font-size: 9pt;" color="#000000">Rencana
                                                        perawatan/Keperawatan</span>
                                                </td>
                                                <td width="30%">
                                                    <input type="checkbox"
                                                        {{ isset($data['kebutuhanEdukasi_5']) && $data['kebutuhanEdukasi_5']['code'] == 'MK' ? 'checked' : '' }} />
                                                    <span style="font-size: 9pt;" color="#000000">Manajemen
                                                        nyeri/Keperawatan</span>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                <tr>
                                    <td width="100%" colspan="4">
                                        <table width="100%">
                                            <tr>
                                                <td width="30%">
                                                    <input type="checkbox"
                                                        {{ isset($data['kebutuhanEdukasi_6']) && $data['kebutuhanEdukasi_6']['code'] == 'PL' ? 'checked' : '' }} />
                                                    <span style="font-size: 9pt;" color="#000000">Perawatan
                                                        luka/Keperawatan</span>
                                                </td>
                                                <td width="30%">
                                                    <input type="checkbox"
                                                        {{ isset($data['kebutuhanEdukasi_7']) && $data['kebutuhanEdukasi_7']['code'] == 'DN' ? 'checked' : '' }} />
                                                    <span style="font-size: 9pt;" color="#000000">Diet atau nutrisi/Dokter
                                                        Gizi Ahli Gizi</span>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                <tr>
                                    <td width="100%" colspan="4">
                                        <table width="100%">
                                            <tr>
                                                <td width="30%">
                                                    <input type="checkbox"
                                                        {{ isset($data['kebutuhanEdukasi_8']) && $data['kebutuhanEdukasi_8']['code'] == 'IOM' ? 'checked' : '' }} />
                                                    <span style="font-size: 9pt;" color="#000000">Interaksi obat dan
                                                        makanan/Farmasi</span>
                                                </td>
                                                <td width="30%">
                                                    <input type="checkbox"
                                                        {{ isset($data['kebutuhanEdukasi_9']) && $data['kebutuhanEdukasi_9']['code'] == 'TRM' ? 'checked' : '' }} />
                                                    <span style="font-size: 9pt;" color="#000000">Teknik
                                                        rehabilitasi/Rehabilitasi Medis</span>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                <tr>
                                    <td width="100%" colspan="4">
                                        <table width="100%">
                                            <tr>
                                                <td width="30%">
                                                    <input type="checkbox"
                                                        {{ isset($data['kebutuhanEdukasi_10']) && $data['kebutuhanEdukasi_10']['code'] == 'PIC' ? 'checked' : '' }} />
                                                    <span style="font-size: 9pt;" color="#000000">Pengisian inform
                                                        consent/Keperawatan</span>
                                                </td>
                                                <td width="30%">
                                                    <input type="checkbox"
                                                        {{ isset($data['kebutuhanEdukasi_11']) && $data['kebutuhanEdukasi_11']['code'] == 'TCT' ? 'checked' : '' }} />
                                                    <span style="font-size: 9pt;" color="#000000">Teknik cuci
                                                        tangan/Keperawatan</span>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                <tr>
                                    <td width="100%" colspan="4">
                                        <table width="100%">
                                            <tr>
                                                <td width="30%">
                                                    <input type="checkbox"
                                                        {{ isset($data['kebutuhanEdukasi_13']) && $data['kebutuhanEdukasi_13']['code'] == 'ERJ' ? 'checked' : '' }} />
                                                    <span style="font-size: 9pt;" color="#000000">Edukasi Resiko
                                                        Jatuh/Keperawatan</span>
                                                </td>
                                                <td width="30%">
                                                    <input type="checkbox"
                                                        {{ isset($data['kebutuhanEdukasi_14']) && $data['kebutuhanEdukasi_14']['code'] == 'PLP' ? 'checked' : '' }} />
                                                    <span style="font-size: 9pt;" color="#000000">Perawatan lanjutan
                                                        setelah pasien pulang/Keperawatan</span>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                <tr>
                                    <td width="24%"><span style="font-size: 9pt;" color="#000000">Edukasi
                                            lainnya</span></td>
                                    <td width="1%"><span style="font-size: 9pt;" color="#000000">:</span></td>
                                    <td width="75%"><span style="font-size: 9pt;"
                                            color="#000000">{{ isset($data['keterangankebutuhanEdukasi']) ? $data['keterangankebutuhanEdukasi'] : '' }}
                                        </span></td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <tr>
                        <td width="100%" colspan="4"><span style="font-size: 10pt;" class="judulLabel"
                                color="#000000">G. DIAGNOSA KEPERAWATAN</span></td>
                    </tr>
                    <tr>
                        <td width="100%" colspan="4"><span style="font-size: 10pt;" color="#000000">Masalah
                                Keperawatan</span></td>
                    </tr>
                    <tr>
                        <td width="100%" colspan="4">
                            <table width="100%">
                                <tr>
                                    <td width="30%">
                                        <input type="checkbox"
                                            {{ isset($data['masalahKeperawatan_0']) && $data['masalahKeperawatan_0']['code'] == 'PCJ' ? 'checked' : '' }} />
                                        <span style="font-size: 9pt;" color="#000000">Penurunan curah jantung</span>
                                    </td>
                                    <td width="30%">
                                        <input type="checkbox"
                                            {{ isset($data['masalahKeperawatan_1']) && $data['masalahKeperawatan_1']['code'] == 'PJP' ? 'checked' : '' }} />
                                        <span style="font-size: 9pt;" color="#000000">Perfusi jaringan perifer tidak
                                            efektif</span>
                                    </td>
                                    <td width="30%">
                                        <input type="checkbox"
                                            {{ isset($data['masalahKeperawatan_2']) && $data['masalahKeperawatan_2']['code'] == 'PJS' ? 'checked' : '' }} />
                                        <span style="font-size: 9pt;" color="#000000">Perfusi
                                            jaringan serebral tidak efektif</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td width="30%">
                                        <input type="checkbox"
                                            {{ isset($data['masalahKeperawatan_3']) && $data['masalahKeperawatan_3']['code'] == 'GVS' ? 'checked' : '' }} />
                                        <span style="font-size: 9pt;" color="#000000">Gangguan ventilasi spontan</span>
                                    </td>
                                    <td width="30%">
                                        <input type="checkbox"
                                            {{ isset($data['masalahKeperawatan_4']) && $data['masalahKeperawatan_4']['code'] == 'BJN' ? 'checked' : '' }} />
                                        <span style="font-size: 9pt;" color="#000000">Bersihkan jalan nafas tidak
                                            efektif</span>
                                    </td>
                                    <td width="30%">
                                        <input type="checkbox"
                                            {{ isset($data['masalahKeperawatan_5']) && $data['masalahKeperawatan_5']['code'] == 'PNT' ? 'checked' : '' }} />
                                        <span style="font-size: 9pt;" color="#000000">Pola nafas tidak efektif</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td width="30%">
                                        <input type="checkbox"
                                            {{ isset($data['masalahKeperawatan_6']) && $data['masalahKeperawatan_6']['code'] == 'GPG' ? 'checked' : '' }} />
                                        <span style="font-size: 9pt;" color="#000000">Gangguan pertukaran gas</span>
                                    </td>
                                    <td width="30%">
                                        <input type="checkbox"
                                            {{ isset($data['masalahKeperawatan_7']) && $data['masalahKeperawatan_7']['code'] == 'NA' ? 'checked' : '' }} />
                                        <span style="font-size: 9pt;" color="#000000">Nyeri akut</span>
                                    </td>
                                    <td width="30%">
                                        <input type="checkbox"
                                            {{ isset($data['masalahKeperawatan_8']) && $data['masalahKeperawatan_8']['code'] == 'NK' ? 'checked' : '' }} />
                                        <span style="font-size: 9pt;" color="#000000">Nyeri Kronik</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td width="30%">
                                        <input type="checkbox"
                                            {{ isset($data['masalahKeperawatan_9']) && $data['masalahKeperawatan_9']['code'] == 'KVC' ? 'checked' : '' }} />
                                        <span style="font-size: 9pt;" color="#000000">Kekurangan volume cairan</span>
                                    </td>
                                    <td width="30%">
                                        <input type="checkbox"
                                            {{ isset($data['masalahKeperawatan_10']) && $data['masalahKeperawatan_10']['code'] == 'BVC' ? 'checked' : '' }} />
                                        <span style="font-size: 9pt;" color="#000000">Kelebihan volume cairan</span>
                                    </td>
                                    <td width="30%">
                                        <input type="checkbox"
                                            {{ isset($data['masalahKeperawatan_11']) && $data['masalahKeperawatan_11']['code'] == 'MUAL' ? 'checked' : '' }} />
                                        <span style="font-size: 9pt;" color="#000000">Mual</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td width="30%">
                                        <input type="checkbox"
                                            {{ isset($data['masalahKeperawatan_12']) && $data['masalahKeperawatan_12']['code'] == 'HPR' ? 'checked' : '' }} />
                                        <span style="font-size: 9pt;" color="#000000">Hipertermia</span>
                                    </td>
                                    <td width="30%">
                                        <input type="checkbox"
                                            {{ isset($data['masalahKeperawatan_13']) && $data['masalahKeperawatan_13']['code'] == 'HPO' ? 'checked' : '' }} />
                                        <span style="font-size: 9pt;" color="#000000">Hipotermia</span>
                                    </td>
                                    <td width="30%">
                                        <input type="checkbox"
                                            {{ isset($data['masalahKeperawatan_14']) && $data['masalahKeperawatan_14']['code'] == 'KIK' ? 'checked' : '' }} />
                                        <span style="font-size: 9pt;" color="#000000">Kerusakan integritas kulit</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td width="30%">
                                        <input type="checkbox"
                                            {{ isset($data['masalahKeperawatan_15']) && $data['masalahKeperawatan_15']['code'] == 'DIARE' ? 'checked' : '' }} />
                                        <span style="font-size: 9pt;" color="#000000">Diare</span>
                                    </td>
                                    <td width="30%">
                                        <input type="checkbox"
                                            {{ isset($data['masalahKeperawatan_16']) && $data['masalahKeperawatan_16']['code'] == 'KTI' ? 'checked' : '' }} />
                                        <span style="font-size: 9pt;" color="#000000">Konstipasi</span>
                                    </td>
                                    <td width="30%">
                                        <input type="checkbox"
                                            {{ isset($data['masalahKeperawatan_17']) && $data['masalahKeperawatan_17']['code'] == 'ASS' ? 'checked' : '' }} />
                                        <span style="font-size: 9pt;" color="#000000">Ansietas</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td width="30%">
                                        <input type="checkbox"
                                            {{ isset($data['masalahKeperawatan_18']) && $data['masalahKeperawatan_18']['code'] == 'KP' ? 'checked' : '' }} />
                                        <span style="font-size: 9pt;" color="#000000">Kurang Pengetahuan</span>
                                    </td>
                                    <td width="30%">
                                        <input type="checkbox"
                                            {{ isset($data['masalahKeperawatan_19']) && $data['masalahKeperawatan_19']['code'] == 'HGI' ? 'checked' : '' }} />
                                        <span style="font-size: 9pt;" color="#000000">Hiperglikemi</span>
                                    </td>
                                    <td width="30%">
                                        <input type="checkbox"
                                            {{ isset($data['masalahKeperawatan_20']) && $data['masalahKeperawatan_20']['code'] == 'HLI' ? 'checked' : '' }} />
                                        <span style="font-size: 9pt;" color="#000000">Hipoglikemi</span>
                                    </td>
                                </tr>

                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td width="100%" colspan="4">
                            <span style="font-size: 9pt;"
                                color="#000000">{{ isset($data['keteranganmasalahKeperawatan']) ? $data['keteranganmasalahKeperawatan'] : '' }}
                            </span>
                        </td>
                    </tr>

                    <tr>
                        <td width="100%" colspan="4"><span style="font-size: 10pt;"
                                color="#000000">Rencana</span></td>
                    </tr>
                    <tr>
                        <td width="100%" colspan="4"><span style="font-size: 10pt;" color="#000000">A.
                                Mandiri</span></td>
                    </tr>
                    <tr>
                        <td width="100%" colspan="4">
                            <table width="100%">
                                <tr>
                                    <td width="30%">
                                        <input type="checkbox"
                                            {{ isset($data['Rencana_0']) && $data['Rencana_0']['code'] == 'HT' ? 'checked' : '' }} />
                                        <span style="font-size: 9pt;" color="#000000">Head tilt</span>
                                    </td>
                                    <td width="30%">
                                        <input type="checkbox"
                                            {{ isset($data['Rencana_1']) && $data['Rencana_1']['code'] == 'CT' ? 'checked' : '' }} />
                                        <span style="font-size: 9pt;" color="#000000">Chin lift</span>
                                    </td>
                                    <td width="30%">
                                        <input type="checkbox"
                                            {{ isset($data['Rencana_2']) && $data['Rencana_2']['code'] == 'JWT' ? 'checked' : '' }} />
                                        <span style="font-size: 9pt;" color="#000000">Jaw trust</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td width="30%">
                                        <input type="checkbox"
                                            {{ isset($data['Rencana_3']) && $data['Rencana_3']['code'] == 'PP' ? 'checked' : '' }} />
                                        <span style="font-size: 9pt;" color="#000000">Posisikan pasien</span>
                                    </td>
                                    <td width="30%">
                                        <input type="checkbox"
                                            {{ isset($data['Rencana_4']) && $data['Rencana_4']['code'] == 'ABCD' ? 'checked' : '' }} />
                                        <span style="font-size: 9pt;" color="#000000">Kaji airway,breathing,circulatiion
                                            dan disability</span>
                                    </td>
                                    <td width="30%">
                                        <input type="checkbox"
                                            {{ isset($data['Rencana_5']) && $data['Rencana_5']['code'] == 'KN' ? 'checked' : '' }} />
                                        <span style="font-size: 9pt;" color="#000000">Kaji nyeri</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td width="30%">
                                        <input type="checkbox"
                                            {{ isset($data['Rencana_6']) && $data['Rencana_6']['code'] == 'cemas' ? 'checked' : '' }} />
                                        <span style="font-size: 9pt;" color="#000000">Kaji tingkat
                                            kecemasan/ansietas</span>
                                    </td>
                                    <td width="30%">
                                        <input type="checkbox"
                                            {{ isset($data['Rencana_7']) && $data['Rencana_7']['code'] == 'LRJ' ? 'checked' : '' }} />
                                        <span style="font-size: 9pt;" color="#000000">Lakukan resusitasi jantung
                                            paru</span>
                                    </td>
                                    <td width="30%">
                                        <input type="checkbox"
                                            {{ isset($data['Rencana_8']) && $data['Rencana_8']['code'] == 'LPS' ? 'checked' : '' }} />
                                        <span style="font-size: 9pt;" color="#000000">Lakukan penghisapan sekret</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td width="30%">
                                        <input type="checkbox"
                                            {{ isset($data['Rencana_9']) && $data['Rencana_9']['code'] == 'LPL' ? 'checked' : '' }} />
                                        <span style="font-size: 9pt;" color="#000000">Lakukan perawatan luka</span>
                                    </td>
                                    <td width="30%">
                                        <input type="checkbox"
                                            {{ isset($data['Rencana_10']) && $data['Rencana_10']['code'] == 'PIC' ? 'checked' : '' }} />
                                        <span style="font-size: 9pt;" color="#000000">Pantau intake-output cairan</span>
                                    </td>
                                    <td width="30%">
                                        <input type="checkbox"
                                            {{ isset($data['Rencana_11']) && $data['Rencana_11']['code'] == 'PR' ? 'checked' : '' }} />
                                        <span style="font-size: 9pt;" color="#000000">Pasangan restrain (bila
                                            diperlukan)</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td width="30%">
                                        <input type="checkbox"
                                            {{ isset($data['Rencana_12']) && $data['Rencana_12']['code'] == 'MIM' ? 'checked' : '' }} />
                                        <span style="font-size: 9pt;" color="#000000">Beri minum air hangat</span>
                                    </td>
                                    <td width="30%">
                                        <input type="checkbox"
                                            {{ isset($data['Rencana_13']) && $data['Rencana_13']['code'] == 'LKP' ? 'checked' : '' }} />
                                        <span style="font-size: 9pt;" color="#000000">Libatkan keluarga dalam
                                            perencanaan</span>
                                    </td>
                                    <td width="30%">
                                        <input type="checkbox"
                                            {{ isset($data['Rencana_14']) && $data['Rencana_14']['code'] == 'MN' ? 'checked' : '' }} />
                                        <span style="font-size: 9pt;" color="#000000">Manajemen nyeri</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td width="30%">
                                        <input type="checkbox"
                                            {{ isset($data['Rencana_15']) && $data['Rencana_15']['code'] == 'PBS' ? 'checked' : '' }} />
                                        <span style="font-size: 9pt;" color="#000000">Pasang bed site monitor</span>
                                    </td>
                                    <td width="30%">
                                        <input type="checkbox"
                                            {{ isset($data['Rencana_16']) && $data['Rencana_16']['code'] == 'POA' ? 'checked' : '' }} />
                                        <span style="font-size: 9pt;" color="#000000">Pasangan Orophraingeal
                                            Airway</span>
                                    </td>
                                    <td width="30%">
                                        <input type="checkbox"
                                            {{ isset($data['Rencana_17']) && $data['Rencana_17']['code'] == 'PB' ? 'checked' : '' }} />
                                        <span style="font-size: 9pt;" color="#000000">Pasang bidai</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td width="30%">
                                        <input type="checkbox"
                                            {{ isset($data['Rencana_18']) && $data['Rencana_18']['code'] == 'ML' ? 'checked' : '' }} />
                                        <span style="font-size: 9pt;" color="#000000">Membersihkan luka</span>
                                    </td>
                                    {{-- <td width="30%">
                                        <input type="checkbox" {{ isset($data['Rencana_']) && $data['Rencana_']["code"] == "HLI" ? 'checked' : '' }}/>
                                        <span style="font-size: 9pt;" color="#000000">
                                            <span></span>
                                        </span>
                                    </td>
                                    <td width="30%">
                                        <input type="checkbox" {{ isset($data['Rencana_']) && $data['Rencana_']["code"] == "HLI" ? 'checked' : '' }}/>
                                        <span style="font-size: 9pt;" color="#000000">
                                            <span></span>
                                        </span>
                                    </td> --}}
                                </tr>
                                <tr>
                                    <td width="100%" colspan="4" height="10"></td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <tr>
                        <td width="100%" colspan="4"><span style="font-size: 10pt;" class="judulLabel"
                                color="#000000">H. TINDAKAN YANG SUDAH DILAKUKAN DI IGD</span></td>
                    </tr>
                    <tr>
                        <td width="100%" colspan="4">
                            <table width="100%" border="1" bordercolor="#000000" class="garis6">
                                <tr bgcolor="#f08080">
                                    <th style="width: 20%">Tgl / Jam</th>
                                    <th style="width: 50%">Tindakan</th>
                                    <th style="width: 30%">Petugas Pelaksana</th>
                                </tr>
                                @if (isset($data['details']))
                                    @foreach ($data['details'] as $cek)
                                        <tr>
                                            <td>{{ date('d-m-Y', strtotime($cek['tgl'])) }}</td>
                                            <td>{{ $cek['tindakan'] }}</td>
                                            <td>{{ $cek['dokterRawatBersama']['label'] }}</td>
                                        </tr>
                                    @endforeach
                                    
                                @else
                                <tr>
                                    <td colspan="3" style="text-align: center"> Tidak ada data </td>
                                </tr>   
                                @endif


                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td width="100%" colspan="4"><span style="font-size: 10pt;" color="#000000">Kondisi
                                Pasien Saat Keluar IGD</span></td>
                    </tr>
                    <tr>
                        <td width="100%" colspan="4">
                            <table width="100%">
                                <tr>
                                    <td width="15%">
                                        <input type="checkbox"
                                            {{ isset($data['KondisiPasien']) && $data['KondisiPasien'] == 'Membaik' ? 'checked' : '' }} />
                                        <span style="font-size: 9pt;" color="#000000">Membaik</span>
                                    </td>
                                    <td width="15%">
                                        <input type="checkbox"
                                            {{ isset($data['KondisiPasien']) && $data['KondisiPasien'] == 'Memburuk' ? 'checked' : '' }} />
                                        <span style="font-size: 9pt;" color="#000000">Memburuk</span>
                                    </td>
                                    <td width="15%">
                                        <input type="checkbox"
                                            {{ isset($data['KondisiPasien']) && $data['KondisiPasien'] == 'Kritis' ? 'checked' : '' }} />
                                        <span style="font-size: 9pt;" color="#000000">Kritis</span>
                                    </td>
                                    <td width="15%">
                                        <input type="checkbox"
                                            {{ isset($data['KondisiPasien']) && $data['KondisiPasien'] == 'Meninggal' ? 'checked' : '' }} />
                                        <span style="font-size: 9pt;" color="#000000">Meninggal</span>
                                    </td>
                                    <td width="15%">
                                        <input type="checkbox"
                                            {{ isset($data['KondisiPasien']) && $data['KondisiPasien'] == 'Stabil' ? 'checked' : '' }} />
                                        <span style="font-size: 9pt;" color="#000000">Stabil</span>
                                    </td>

                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td width="100%" colspan="4"><span style="font-size: 10pt;" color="#000000">Tindak
                                Lanjut</span></td>
                    </tr>
                    <tr>
                        <td width="100%" colspan="4">
                            <table width="100%">
                                <tr>
                                    <td width="20%">
                                        <input type="checkbox"
                                            {{ isset($data['tindakLanjut']) && $data['tindakLanjut'] == 'Pulang' ? 'checked' : '' }} />
                                        <span style="font-size: 9pt;" color="#000000">Pulang</span>
                                    </td>
                                    <td width="20%">
                                        <input type="checkbox"
                                            {{ isset($data['tindakLanjut']) && $data['tindakLanjut'] == 'Rawat Inap' ? 'checked' : '' }} />
                                        <span style="font-size: 9pt;" color="#000000">Rawat Inap</span>
                                    </td>
                                    <td width="20%">
                                        <input type="checkbox"
                                            {{ isset($data['tindakLanjut']) && $data['tindakLanjut'] == 'Rujuk' ? 'checked' : '' }} />
                                        <span style="font-size: 9pt;" color="#000000">Rujuk</span>
                                    </td>
                                    <td>
                                        <input type="checkbox"
                                            {{ isset($data['tindakLanjut']) && $data['tindakLanjut'] == 'Pulang Paksa' ? 'checked' : '' }} />
                                        <span style="font-size: 9pt;" color="#000000">Pulang Paksa</span>
                                    </td>

                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td width="100%" colspan="4"><span style="font-size: 10pt;" color="#000000">Saat Pasien
                                Keluar Pindah Ke Ruangan:</span></td>
                    </tr>
                    <tr>
                        <td width="100%" colspan="4">
                            <table width="100%">
                                <tr>
                                    <td width="4.3%" class="text-top">
                                        <span style="font-size: 10pt;" color="#000000">Pukul</span>
                                    </td>
                                    <td width="0.1%" class="text-top">
                                        <span style="font-size: 10pt;" color="#000000">:</span>
                                    </td>
                                    <td width="10%">
                                        <span style="font-size: 10pt;"
                                            color="#000000">{{ isset($data['tanggalPasienKeluar']) ? date('h:m:s', strtotime($data['tanggalPasienKeluar'])) : '-' }}</span>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td width="100%" colspan="4">
                            <table width="100%">
                                <tr>
                                    <td width="30%" class="text-top">
                                        <span style="font-size: 10pt;" color="#000000">I. Kondisi Masalah</span>
                                    </td>
                                    <td width="1%" class="text-top">
                                        <span style="font-size: 10pt;" color="#000000">:</span>
                                    </td>
                                    <td width="69%">
                                        <span style="font-size: 10pt;"
                                            color="#000000">{{ isset($data['kondisiMasalah']) ? $data['kondisiMasalah'] : '-' }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td width="30%" class="text-top">
                                        <span style="font-size: 10pt;" color="#000000">II. Tingkat Kesadaran</span>
                                    </td>
                                    <td width="1%" class="text-top">
                                        <span style="font-size: 10pt;" color="#000000">:</span>
                                    </td>
                                    <td width="69%">
                                        <span style="font-size: 10pt;"
                                            color="#000000">{{ isset($data['tingkatKesadaran']) ? $data['tingkatKesadaran'] : '-' }}</span>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td width="100%" colspan="4"><span style="font-size: 10pt;" color="#000000">GCS</span>
                        </td>
                    </tr>
                    <tr>
                        <td width="100%" colspan="4">
                            <table width="100%">
                                <tr>
                                    <td width="0.2%" class="text-top">
                                        <span style="font-size: 10pt;" color="#000000">E</span>
                                    </td>
                                    <td width="0.1%" class="text-top">
                                        <span style="font-size: 10pt;" color="#000000">:</span>
                                    </td>
                                    <td width="10%">
                                        <input type="checkbox"
                                            {{ isset($data['kesadaranE']) && $data['kesadaranE'] == 1 ? 'checked' : '' }} />
                                        <span style="font-size: 9pt;" color="#000000">1</span>
                                    </td>
                                    <td width="10%">
                                        <input type="checkbox"
                                            {{ isset($data['kesadaranE']) && $data['kesadaranE'] == 2 ? 'checked' : '' }} />
                                        <span style="font-size: 9pt;" color="#000000">2</span>
                                    </td>
                                    <td width="10%">
                                        <input type="checkbox"
                                            {{ isset($data['kesadaranE']) && $data['kesadaranE'] == 3 ? 'checked' : '' }} />
                                        <span style="font-size: 9pt;" color="#000000">3</span>
                                    </td>
                                    <td width="10%">
                                        <input type="checkbox"
                                            {{ isset($data['kesadaranE']) && $data['kesadaranE'] == 4 ? 'checked' : '' }} />
                                        <span style="font-size: 9pt;" color="#000000">4</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td width="0.2%" class="text-top">
                                        <span style="font-size: 10pt;" color="#000000">M</span>
                                    </td>
                                    <td width="0.1%" class="text-top">
                                        <span style="font-size: 10pt;" color="#000000">:</span>
                                    </td>
                                    <td width="10%">
                                        <input type="checkbox"
                                            {{ isset($data['kesadaranM']) && $data['kesadaranM'] == 1 ? 'checked' : '' }} />
                                        <span style="font-size: 9pt;" color="#000000">1</span>
                                    </td>
                                    <td width="10%">
                                        <input type="checkbox"
                                            {{ isset($data['kesadaranM']) && $data['kesadaranM'] == 2 ? 'checked' : '' }} />
                                        <span style="font-size: 9pt;" color="#000000">2</span>
                                    </td>
                                    <td width="10%">
                                        <input type="checkbox"
                                            {{ isset($data['kesadaranM']) && $data['kesadaranM'] == 3 ? 'checked' : '' }} />
                                        <span style="font-size: 9pt;" color="#000000">3</span>
                                    </td>
                                    <td width="10%">
                                        <input type="checkbox"
                                            {{ isset($data['kesadaranM']) && $data['kesadaranM'] == 4 ? 'checked' : '' }} />
                                        <span style="font-size: 9pt;" color="#000000">4</span>
                                    </td>
                                    <td width="10%">
                                        <input type="checkbox"
                                            {{ isset($data['kesadaranM']) && $data['kesadaranM'] == 5 ? 'checked' : '' }} />
                                        <span style="font-size: 9pt;" color="#000000">5</span>
                                    </td>
                                    <td width="10%">
                                        <input type="checkbox"
                                            {{ isset($data['kesadaranM']) && $data['kesadaranM'] == 6 ? 'checked' : '' }} />
                                        <span style="font-size: 9pt;" color="#000000">6</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td width="0.2%" class="text-top">
                                        <span style="font-size: 10pt;" color="#000000">V</span>
                                    </td>
                                    <td width="0.1%" class="text-top">
                                        <span style="font-size: 10pt;" color="#000000">:</span>
                                    </td>
                                    <td width="10%">
                                        <input type="checkbox"
                                            {{ isset($data['kesadaranV']) && $data['kesadaranV'] == 1 ? 'checked' : '' }} />
                                        <span style="font-size: 9pt;" color="#000000">1</span>
                                    </td>
                                    <td width="10%">
                                        <input type="checkbox"
                                            {{ isset($data['kesadaranV']) && $data['kesadaranV'] == 2 ? 'checked' : '' }} />
                                        <span style="font-size: 9pt;" color="#000000">2</span>
                                    </td>
                                    <td width="10%">
                                        <input type="checkbox"
                                            {{ isset($data['kesadaranV']) && $data['kesadaranV'] == 3 ? 'checked' : '' }} />
                                        <span style="font-size: 9pt;" color="#000000">3</span>
                                    </td>
                                    <td width="10%">
                                        <input type="checkbox"
                                            {{ isset($data['kesadaranV']) && $data['kesadaranV'] == 4 ? 'checked' : '' }} />
                                        <span style="font-size: 9pt;" color="#000000">4</span>
                                    </td>
                                    <td width="10%">
                                        <input type="checkbox"
                                            {{ isset($data['kesadaranV']) && $data['kesadaranV'] == 5 ? 'checked' : '' }} />
                                        <span style="font-size: 9pt;" color="#000000">5</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td width="100%" colspan="4">
                                        <table width="100%">
                                            <tr>
                                                <td width="5%" class="text-top">
                                                    <span style="font-size: 10pt;" color="#000000">Jumlah Total</span>
                                                </td>
                                                <td width="0.1%" class="text-top">
                                                    <span style="font-size: 10pt;" color="#000000">:</span>
                                                </td>
                                                <td width="10%">
                                                    <span style="font-size: 10pt;"
                                                        color="#000000">{{ isset($data['totalKesadaran']) ? $data['totalKesadaran'] : '-' }}</span>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>

                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td width="100%" colspan="4"><span style="font-size: 10pt;"
                                color="#000000">Keterangan</span></td>
                    </tr>
                    <tr>
                        <td width="100%" colspan="4">
                            <table width="100%">
                                <tr>
                                    <td width="15%" class="text-top">
                                        <input type="checkbox"
                                            {{ isset($data['rangeKesadaran']) && $data['rangeKesadaran']['poin'] == 15 ? 'checked' : '' }} />
                                        <span style="font-size: 9pt;" color="#000000">CMC(14-15)</span>
                                    </td>
                                    <td width="15%" class="text-top">
                                        <input type="checkbox"
                                            {{ isset($data['rangeKesadaran']) && $data['rangeKesadaran']['poin'] == 13 ? 'checked' : '' }} />
                                        <span style="font-size: 9pt;" color="#000000">Apatis(12-13)</span>
                                    </td>
                                    <td width="15%" class="text-top">
                                        <input type="checkbox"
                                            {{ isset($data['rangeKesadaran']) && $data['rangeKesadaran']['poin'] == 11 ? 'checked' : '' }} />
                                        <span style="font-size: 9pt;"color="#000000">Somnolen (10-11)</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td width="15%" class="text-top">
                                        <input type="checkbox"
                                            {{ isset($data['rangeKesadaran']) && $data['rangeKesadaran']['poin'] == 9 ? 'checked' : '' }} />
                                        <span style="font-size: 9pt;"color="#000000">Delirium (7-9)</span>
                                    </td>
                                    <td width="15%" class="text-top">
                                        <input type="checkbox"
                                            {{ isset($data['rangeKesadaran']) && $data['rangeKesadaran']['poin'] == 6 ? 'checked' : '' }} />
                                        <span style="font-size: 9pt;" color="#000000">Stupor(4-6)</span>
                                    </td>
                                    <td width="15%" class="text-top">
                                        <input type="checkbox"
                                            {{ isset($data['rangeKesadaran']) && $data['rangeKesadaran']['poin'] == 3 ? 'checked' : '' }} />
                                        <span style="font-size: 9pt;" color="#000000">Koma ( < 3)</span>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    <tr>
                    <tr>
                        <td width="100%" colspan="4"><span style="font-size: 10pt;" color="#000000">III.</span>
                        </td>
                    </tr>
                    <tr>
                        <td width="100%" colspan="4">
                            <table width="100%">
                                <tr>
                                    <td width="1%" class="text-top">
                                        <span style="font-size: 10pt;" color="#000000">HR</span>
                                    </td>
                                    <td width="1%" class="text-top">
                                        <span style="font-size: 10pt;" color="#000000">:</span>
                                    </td>
                                    <td width="20%">
                                        <span style="font-size: 10pt;"
                                            color="#000000">{{ isset($data['nadi']) ? $data['nadi'] : '-' }}</span>
                                    </td>
                                    <!-- </tr>
                                                    <tr> -->
                                    <td width="1%" class="text-top">
                                        <span style="font-size: 10pt;" color="#000000">RR</span>
                                    </td>
                                    <td width="1%" class="text-top">
                                        <span style="font-size: 10pt;" color="#000000">:</span>
                                    </td>
                                    <td width="20%">
                                        <span style="font-size: 10pt;"
                                            color="#000000">{{ isset($data['pernapasan']) ? $data['pernapasan'] : '-' }}</span>
                                    </td>
                                    <!-- </tr>
                                                    <tr> -->
                                    <td width="1%" class="text-top">
                                        <span style="font-size: 10pt;" color="#000000">SPO2</span>
                                    </td>
                                    <td width="1%" class="text-top">
                                        <span style="font-size: 10pt;" color="#000000">:</span>
                                    </td>
                                    <td width="20%">
                                        <span style="font-size: 10pt;"
                                            color="#000000">{{ isset($data['spo2']) ? $data['spo2'] : '-' }}</span>
                                    </td>
                                    <!-- </tr>
                                                    <tr> -->
                                    <td width="1%" class="text-top">
                                        <span style="font-size: 10pt;" color="#000000">Suhu</span>
                                    </td>
                                    <td width="1%" class="text-top">
                                        <span style="font-size: 10pt;" color="#000000">:</span>
                                    </td>
                                    <td width="20%">
                                        <span style="font-size: 10pt;"
                                            color="#000000">{{ isset($data['suhu']) ? $data['suhu'] : '-' }}</span>
                                    </td>

                                    <td width="1%" class="text-top">
                                        <span style="font-size: 10pt;" color="#000000">TD</span>
                                    </td>
                                    <td width="1%" class="text-top">
                                        <span style="font-size: 10pt;" color="#000000">:</span>
                                    </td>
                                    <td width="20%">
                                        <span style="font-size: 10pt;"
                                            color="#000000">{{ isset($data['tekananDarah']) ? $data['tekananDarah'] : '-' }}</span>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td width="100%" colspan="4" style="text-align: center"><span style="font-size: 10pt;"
                                color="#000000" class="judulLabel">Serah Terima Dokumen Penunjang</span></td>
                    </tr>
                    <tr>
                        <td width="100%" colspan="4">
                            <table width="100%">
                                <tr>
                                    <th colspan="3" style="text-align: left;">Dokumen yang diserahterimakan</th>
                                    <th style="text-align: left;">Keterangan</th>
                                </tr>
                                <tr>
                                    <td width="5%" class="text-top">
                                        <span style="font-size: 10pt;" color="#000000">1.EKG</span>
                                    </td>
                                    <td width="5%" class="text-top">
                                        <input type="checkbox"
                                            {{ isset($data['dokumenEKG']) && $data['dokumenEKG'] == 'Ya' ? 'checked' : '' }} />
                                        <span style="font-size: 10pt;" color="#000000">Ada</span>
                                    </td>
                                    <td width="5%" class="text-top">
                                        <input type="checkbox"
                                            {{ isset($data['dokumenEKG']) && $data['dokumenEKG'] == 'Tidak' ? 'checked' : '' }} />
                                        <span style="font-size: 10pt;" color="#000000">Tidak</span>
                                    </td>
                                    <td width="5%" class="text-top">
                                        <span style="font-size: 10pt;"
                                            color="#000000">{{ isset($data['ketDokumenEKG']) ? $data['ketDokumenEKG'] : '-' }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td width="5%" class="text-top">
                                        <span style="font-size: 10pt;" color="#000000">2.Radiologi</span>
                                    </td>
                                    <td width="5%" class="text-top">
                                        <input type="checkbox"
                                            {{ isset($data['dokumenRadiologi']) && $data['dokumenRadiologi'] == 'Ya' ? 'checked' : '' }} />
                                        <span style="font-size: 10pt;" color="#000000">Ada</span>
                                    </td>
                                    <td width="5%" class="text-top">
                                        <input type="checkbox"
                                            {{ isset($data['dokumenRadiologi']) && $data['dokumenRadiologi'] == 'Tidak' ? 'checked' : '' }} />
                                        <span style="font-size: 10pt;" color="#000000">Tidak</span>
                                    </td>
                                    <td width="5%" class="text-top">
                                        <span style="font-size: 10pt;"
                                            color="#000000">{{ isset($data['ketDokumenRadiologi']) ? $data['ketDokumenRadiologi'] : '-' }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td width="5%" class="text-top">
                                        <span style="font-size: 10pt;" color="#000000">3.Laboratorium</span>
                                    </td>
                                    <td width="5%" class="text-top">
                                        <input type="checkbox"
                                            {{ isset($data['dokumenLaboratorium']) && $data['dokumenLaboratorium'] == 'Ya' ? 'checked' : '' }} />
                                        <span style="font-size: 10pt;" color="#000000">Ada</span>
                                    </td>
                                    <td width="5%" class="text-top">
                                        <input type="checkbox"
                                            {{ isset($data['dokumenLaboratorium']) && $data['dokumenLaboratorium'] == 'Tidak' ? 'checked' : '' }} />
                                        <span style="font-size: 10pt;" color="#000000">Tidak</span>
                                    </td>
                                    <td width="5%" class="text-top">
                                        <span style="font-size: 10pt;"
                                            color="#000000">{{ isset($data['ketDokumenLaboratorium']) ? $data['ketDokumenLaboratorium'] : '-' }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td width="5%" class="text-top">
                                        <span style="font-size: 10pt;" color="#000000">4.Rujukan</span>
                                    </td>
                                    <td width="5%" class="text-top">
                                        <input type="checkbox"
                                            {{ isset($data['dokumenRujukan']) && $data['dokumenRujukan'] == 'Ya' ? 'checked' : '' }} />
                                        <span style="font-size: 10pt;" color="#000000">Ada</span>
                                    </td>
                                    <td width="5%" class="text-top">
                                        <input type="checkbox"
                                            {{ isset($data['dokumenRujukan']) && $data['dokumenRujukan'] == 'Tidak' ? 'checked' : '' }} />
                                        <span style="font-size: 10pt;" color="#000000">Tidak</span>
                                    </td>
                                    <td width="5%" class="text-top">
                                        <span style="font-size: 10pt;"
                                            color="#000000">{{ isset($data['ketDokumenRujukan']) ? $data['ketDokumenRujukan'] : '-' }}</span>
                                    </td>
                                </tr>

                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td width="100%" colspan="4">
                            <table width="100%">
                                <tr>
                                    <td width="9%" class="text-top">
                                        <span style="font-size: 10pt;" color="#000000">5.Lain-lain</span>
                                    </td>
                                    <td width="13%" class="text-top">
                                        <span style="font-size: 10pt;"
                                            color="#000000">{{ isset($data['ketLainlain']) ? $data['ketLainlain'] : '-' }}</span>
                                    </td>

                                    <td width="8%" class="text-top">
                                        <span style="font-size: 10pt;"
                                            color="#000000">{{ isset($data['ketLainLainnya']) ? $data['ketLainLainnya'] : '-' }}</span>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td width="100%" colspan="4" height="10"></td>
                    </tr>
                    <tr>
                        <td width="100%" colspan="4">
                            <table width="100%">
                                <tr>
                                    <td width="100%">
                                        <span style="font-size: 10pt;" color="#000000" class="judulLabel">Jam Keluar
                                            IGD : </span>
                                        <span style="font-size: 9pt;"
                                            color="#000000">{{ isset($data['jamKeluarIGD']) ? date('d-m-Y - H:m:s', strtotime($data['jamKeluarIGD'])) : '-' }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td width="100%">
                                        <span style="font-size: 10pt;" color="#000000" class="judulLabel">Penangung
                                            jawab pasien :</span>
                                        <span style="font-size: 9pt;"
                                            color="#000000">{{ isset($data['penangungJawab']) ? $data['penangungJawab']['label'] : '-' }}</span>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>


                </table>
            </td>
        </tr>


    </table>

@endsection
