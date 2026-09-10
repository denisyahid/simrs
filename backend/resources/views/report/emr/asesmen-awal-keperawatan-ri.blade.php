@extends('template.head-emr')
@section('title', 'EMR')
@section('about', 'ASESMEN AWAL KEPERAWATAN RAWAT INAP')
@section('content')

    <table width="100%" cellspacing="0">
        <tr>
            <td colspan="7" style="padding:4px 0px;">
                <span class="judulLabel" color="#000000">Data Umum</span>
            </td>
        </tr>
        <tr>
            <td colspan="7" style="padding:4px 0px;">
                <span class="judulLabel" color="#000000">Kondisi Pasien Masuk</span>
            </td>
        </tr>
        <tr>
            <td width="100%" colspan="4">
                <table width="100%">
                    <tr>
                        <td width="20%">
                            <input type="checkbox"
                                {{ isset($data['konPasienMasuk']) && $data['konPasienMasuk'] == 'Mandiri' ? 'checked' : '' }} />
                            <span style="font-size: 9pt;" color="#000000">Mandiri</span>
                        </td>
                        <td width="20%">
                            <input type="checkbox"
                                {{ isset($data['konPasienMasuk']) && $data['konPasienMasuk'] == 'Dipapah' ? 'checked' : '' }} />
                            <span style="font-size: 9pt;" color="#000000">Dipapah</span>
                        </td>
                        <td width="60%">
                            <span style="font-size: 9pt;" color="#000000">Lainnya :
                                {{ isset($data['kondisiLainnya']) ? $data['kondisiLainnya'] : '-' }}</span> </span>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td colspan="7" style="padding:4px 0px;">
                <span style="font-size: 10pt;font-weight: bold;" color="#000000">Asal Pasien</span>
            </td>
        </tr>
        <tr>
            <td width="100%" colspan="4">
                <table width="100%">
                    <tr>
                        <td width="20%">
                            <input type="checkbox"
                                {{ isset($data['asalPasien']) && $data['asalPasien'] == 'Poliklinik' ? 'checked' : '' }} />
                            <span style="font-size: 9pt;" color="#000000">Poliklinik</span>
                        </td>
                        <td width="20%">
                            <input type="checkbox"
                                {{ isset($data['asalPasien']) && $data['asalPasien'] == 'IGD' ? 'checked' : '' }} />
                            <span style="font-size: 9pt;" color="#000000">IGD</span>
                        </td>
                        <td width="20%">
                            <input type="checkbox"
                                {{ isset($data['asalPasien']) && $data['asalPasien'] == 'Kamar Operasi' ? 'checked' : '' }} />
                            <span style="font-size: 9pt;" color="#000000">Kamar Operasi</span>
                        </td>
                        <td>
                            <span style="font-size: 9pt;" color="#000000">Lainya :
                                {{ isset($data['asalLainnya']) ? $data['asalLainnya'] : '-' }} </span>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td width="100%" colspan="3">
                <table width="100%">
                    <tr>
                        <td width="25%" style="vertical-align: top">
                            <span style="font-size: 9pt;font-weight: bold;" color="#000000">Keluhan Utama</span>
                        </td>
                        <td width="1%" style="vertical-align: top">
                            <span style="font-size: 9pt;" color="#000000">:</span>
                        </td>
                        <td>
                            <span style="font-size: 9pt;" color="#000000">
                                {{ isset($data['keluhanUtama']) ? $data['keluhanUtama'] : '-' }} </span>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td width="100%" colspan="3">
                <table width="100%">
                    <tr>
                        <td width="25%" style="vertical-align: top">
                            <span style="font-size: 9pt;font-weight: bold;" color="#000000">Keluhan Saat Ini
                            </span>
                        </td>
                        <td width="1%" style="vertical-align: top">
                            <span style="font-size: 9pt;" color="#000000">:</span>
                        </td>
                        <td>
                            <span style="font-size: 9pt;" color="#000000">
                                {{ isset($data['keluhanSaatIni']) ? $data['keluhanSaatIni'] : '-' }}</span>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td width="100%" colspan="3">
                <table width="100%">
                    <tr>
                        <td width="25%" style="vertical-align: top">
                            <span style="font-size: 9pt;font-weight: bold;" color="#000000">Riwayat Penyakit
                                Dahulu</span>
                        </td>
                        <td width="1%" style="vertical-align: top">
                            <span style="font-size: 9pt;" color="#000000">:</span>
                        </td>
                        <td>
                            <span style="font-size: 9pt;"
                                color="#000000">{{ isset($data['riwayatPenyakitDahulu']) ? $data['riwayatPenyakitDahulu'] : '-' }}</span>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td width="100%" colspan="3">
                <table width="100%">
                    <tr>
                        <td width="25%">
                            <span style="font-size: 9pt;font-weight: bold;" color="#000000">Dokter DPJP</span>
                        </td>
                        <td width="1%">
                            <span style="font-size: 9pt;" color="#000000">: </span>
                        </td>
                        <td>
                            <span style="font-size: 9pt;"
                                color="#000000">{{ isset($data['dokter']['label']) ? $data['dokter']['label'] : '-' }}</span>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>

        <tr>
            <td colspan="7" style="padding:4px 0px;">
                <span style="font-size: 10pt;font-weight: bold;" color="#000000">Alat Bantu Yang Digunakan</span>
            </td>
        </tr>
        <tr>
            <td width="100%" colspan="5">
                <table width="100%">
                    <tr>
                        <td width="20%">
                            <input type="checkbox"
                                {{ isset($data['alatBantu']) && $data['alatBantu'] == 'Kacamata' ? 'checked' : '' }} />
                            <span style="font-size: 9pt;" color="#000000">Kacamata</span>
                        </td>
                        <td width="20%">
                            <input type="checkbox"
                                {{ isset($data['alatBantu']) && $data['alatBantu'] == 'Lensa Kontak' ? 'checked' : '' }} />
                            <span style="font-size: 9pt;" color="#000000">Lensa Kontak</span>
                        </td>
                        <td width="20%">
                            <input type="checkbox"
                                {{ isset($data['alatBantu']) && $data['alatBantu'] == 'Gigi Palsu' ? 'checked' : '' }} />
                            <span style="font-size: 9pt;" color="#000000">Gigi Palsu</span>
                        </td>
                        <td width="20%">
                            <input type="checkbox"
                                {{ isset($data['alatBantu']) && $data['alatBantu'] == 'Alat Bantu Dengar' ? 'checked' : '' }} />
                            <span style="font-size: 9pt;" color="#000000">Alat Bantu Dengar</span>
                        </td>
                        <td width="20%">
                            <span style="font-size: 9pt;" color="#000000">Lainnya :
                                {{ isset($data['alatBantu']) ? $data['alatBantu'] : '-' }}</span>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td colspan="7" style="padding:4px 0px;">
                <span style="font-size: 10pt;font-weight: bold;" color="#000000">TTV</span>
            </td>
        </tr>
        <tr>
            <td width="100%" colspan="12">
                <table width="100%">
                    <tr>
                        <td width="7%">
                            <span style="font-size: 9pt;" color="#000000">TB</span>
                        </td>
                        <td width="1%">
                            <span style="font-size: 9pt;" color="#000000">:</span>
                        </td>
                        <td width="15%">
                            <span style="font-size: 9pt;"
                                color="#000000">{{ isset($data['tinggiBdn']) ? $data['tinggiBdn'] : '-' }}</span>
                        </td>
                        <td width="7%">
                            <span style="font-size: 9pt;" color="#000000">BB</span>
                        </td>
                        <td width="1%">
                            <span style="font-size: 9pt;" color="#000000">:</span>
                        </td>
                        <td width="15%">
                            <span style="font-size: 9pt;"
                                color="#000000">{{ isset($data['bb']) ? $data['bb'] : '-' }}</span>
                        </td>
                        <td width="7%">
                            <span style="font-size: 9pt;" color="#000000">TD</span>
                        </td>
                        <td width="1%">
                            <span style="font-size: 9pt;" color="#000000">:</span>
                        </td>
                        <td width="20%">
                            <span style="font-size: 9pt;"
                                color="#000000">{{ isset($data['td']) ? $data['td'] : '-' }}</span>
                        </td>
                        <td width="7%">
                            <span style="font-size: 9pt;" color="#000000">Nadi</span>
                        </td>
                        <td width="1%">
                            <span style="font-size: 9pt;" color="#000000">:</span>
                        </td>
                        <td width="15%">
                            <span style="font-size: 9pt;"
                                color="#000000">{{ isset($data['nadi']) ? $data['nadi'] : '-' }}</span>
                        </td>

                    </tr>
                    <tr>
                        <td width="7%">
                            <span style="font-size: 9pt;" color="#000000">Nafas</span>
                        </td>
                        <td width="1%">
                            <span style="font-size: 9pt;" color="#000000">:</span>
                        </td>
                        <td>
                            <span style="font-size: 9pt;"
                                color="#000000">{{ isset($data['nafas']) ? $data['nafas'] : '-' }}</span>
                        </td>
                        <td width="7%">
                            <span style="font-size: 9pt;" color="#000000">Suhu</span>
                        </td>
                        <td width="1%">
                            <span style="font-size: 9pt;" color="#000000">:</span>
                        </td>
                        <td>
                            <span style="font-size: 9pt;"
                                color="#000000">{{ isset($data['suhu']) ? $data['suhu'] : '-' }}</span>
                        </td>
                        <td width="7%">
                            <span style="font-size: 9pt;" color="#000000">SpO2</span>
                        </td>
                        <td width="1%">
                            <span style="font-size: 9pt;" color="#000000">:</span>
                        </td>
                        <td>
                            <span style="font-size: 9pt;"
                                color="#000000">{{ isset($data['sp02']) ? $data['sp02'] : '-' }}</span>
                        </td>

                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td colspan="7" style="padding:4px 0px;">
                <span style="font-size: 10pt;font-weight: bold;" color="#000000">Glasgow Coma Scale Dewasa</span>
            </td>
        </tr>
        <tr>
            <td width="100%" colspan="6">
                <table width="100%">
                    <tr>
                        <td width="15%">
                            <input type="checkbox"
                                {{ isset($data['glasgow']) && $data['glasgow'] == 'Kompos Mentis' ? 'checked' : '' }} />
                            <span style="font-size: 9pt;" color="#000000">Kompos Metis</span>
                        </td>
                        <td width="15%">
                            <input type="checkbox"
                                {{ isset($data['glasgow']) && $data['glasgow'] == 'Apatis' ? 'checked' : '' }} />
                            <span style="font-size: 9pt;" color="#000000">Apatis</span>
                        </td>
                        <td width="15%">
                            <input type="checkbox"
                                {{ isset($data['glasgow']) && $data['glasgow'] == 'Sopor Koma' ? 'checked' : '' }} />
                            <span style="font-size: 9pt;" color="#000000">Sopor Koma</span>
                        </td>
                        <td width="15%">
                            <input type="checkbox"
                                {{ isset($data['glasgow']) && $data['glasgow'] == 'Somnolen' ? 'checked' : '' }} />
                            <span style="font-size: 9pt;" color="#000000">Somnolen</span>
                        </td>
                        <td width="15%">
                            <input type="checkbox"
                                {{ isset($data['glasgow']) && $data['glasgow'] == 'Sopor' ? 'checked' : '' }} />
                            <span style="font-size: 9pt;" color="#000000">Sopor</span>
                        </td>
                        <td width="15%">
                            <input type="checkbox"
                                {{ isset($data['glasgow']) && $data['glasgow'] == 'Koma' ? 'checked' : '' }} />
                            <span style="font-size: 9pt;" color="#000000">Koma</span>
                        </td>

                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td width="100%" colspan="6">
                <table width="100%">
                    <tr>
                        <td width="10%";>
                            <span style="font-size: 9pt;" color="#000000">Nomor</span>
                        </td>
                        <td width="20%";>
                            <span style="font-size: 9pt;" color="#000000">Parameter</span>
                        </td>
                        <td width="60%";>
                            <span style="font-size: 9pt;" color="#000000">Pengkajian</span>
                        </td>
                        <td width="8%";>
                            <span style="font-size: 9pt;" color="#000000">Nilai</span>
                        </td>
                    </tr>
                </table>
                <table width="100%">
                    <tr>
                        <td width="10%">
                            <span style="font-size: 9pt; " color="#000000">1</span>
                        </td>
                        <td width="20%">
                            <span style="font-size: 9pt;" color="#000000">MATA</span>
                        </td>
                        <td width="60%">
                            <span style="font-size: 9pt;" color="#000000">
                                <input type="checkbox"
                                    {{ isset($data['mata']) && $data['mata']['desc'] == 'Terbuka Spontan' ? 'checked' : '' }} />
                                <span style="font-size: 9pt;" color="#000000">Terbuka Spontan</span>
                            </span>
                        </td>
                        <td width="8%">
                            <span style="font-size: 9pt;" color="#000000">4</span>
                        </td>
                    </tr>
                </table>
                <table width="100%">
                    <tr>
                        <td width="10%">
                            <span style="font-size: 9pt; " color="#000000"></span>
                        </td>
                        <td width="20%">
                            <span style="font-size: 9pt;" color="#000000"></span>
                        </td>
                        <td width="60%">
                            <span style="font-size: 9pt;" color="#000000">
                                <input type="checkbox"
                                    {{ isset($data['mata']) && $data['mata']['desc'] == 'Terbuka Saat diperintah/dipanggil' ? 'checked' : '' }} />
                                <span style="font-size: 9pt;" color="#000000">Terbuka Saat Diperintah/Panggil
                                </span>
                            </span>
                        </td>
                        <td width="8%">
                            <span style="font-size: 9pt;" color="#000000">3</span>
                        </td>
                    </tr>
                </table>

                <table width="100%">
                    <tr>
                        <td width="10%" ;">
                            <span style="font-size: 9pt; " color="#000000"></span>
                        </td>
                        <td width="20%">
                            <span style="font-size: 9pt;" color="#000000"></span>
                        </td>
                        <td width="60%">
                            <span style="font-size: 9pt;" color="#000000">
                                <input type="checkbox"
                                    {{ isset($data['mata']) && $data['mata']['desc'] == 'Terbuka terhadap rangsangan nyeri' ? 'checked' : '' }} />
                                <span style="font-size: 9pt;" color="#000000">Terbuka Terhadap Rangsangan Nyeri
                                </span>
                            </span>
                        </td>
                        <td width="8%">
                            <span style="font-size: 9pt;" color="#000000">2</span>
                        </td>
                    </tr>
                </table>
                <table width="100%">
                    <tr>
                        <td width="10%">
                            <span style="font-size: 9pt; " color="#000000"></span>
                        </td>
                        <td width="20%">
                            <span style="font-size: 9pt;" color="#000000"></span>
                        </td>
                        <td width="60%">
                            <span style="font-size: 9pt;" color="#000000">
                                <input type="checkbox"
                                    {{ isset($data['mata']) && $data['mata']['desc'] == 'Tidak Merespon' ? 'checked' : '' }} />
                                <span style="font-size: 9pt;" color="#000000">Tidak Merespon</span>
                            </span>
                        </td>
                        <td width="8%">
                            <span style="font-size: 9pt;" color="#000000">1</span>
                        </td>
                    </tr>
                </table>
                <table width="100%">
                    <tr>
                        <td width="10%">
                            <span style="font-size: 9pt; " color="#000000">2</span>
                        </td>
                        <td width="20%">
                            <span style="font-size: 9pt;" color="#000000">VERBAL</span>
                        </td>
                        <td width="60%">
                            <span style="font-size: 9pt;" color="#000000">
                                <input type="checkbox"
                                    {{ isset($data['verbal']) && $data['verbal']['desc'] == 'Orientasi baik' ? 'checked' : '' }} />
                                <span style="font-size: 9pt;" color="#000000">Orentasi Baik</span>
                            </span>
                        </td>
                        <td width="8%">
                            <span style="font-size: 9pt;" color="#000000">5</span>
                        </td>
                    </tr>
                </table>
                <table width="100%">
                    <tr>
                        <td width="10%">
                            <span style="font-size: 9pt; " color="#000000"></span>
                        </td>
                        <td width="20%">
                            <span style="font-size: 9pt;" color="#000000"></span>
                        </td>
                        <td width="60%">
                            <span style="font-size: 9pt;" color="#000000">
                                <input type="checkbox"
                                    {{ isset($data['verbal']) && $data['mata']['desc'] == 'Disoreintasi/bingung' ? 'checked' : '' }} />
                                <span style="font-size: 9pt;" color="#000000">Disoreintasi/Bingung</span>
                            </span>
                        </td>
                        <td width="8%">
                            <span style="font-size: 9pt;" color="#000000">4</span>
                        </td>
                    </tr>
                </table>
                <table width="100%">
                    <tr>
                        <td width="10%">
                            <span style="font-size: 9pt; " color="#000000"></span>
                        </td>
                        <td width="20%">
                            <span style="font-size: 9pt;" color="#000000"></span>
                        </td>
                        <td width="60%">
                            <span style="font-size: 9pt;" color="#000000">
                                <input type="checkbox"
                                    {{ isset($data['verbal']) && $data['verbal']['desc'] == 'Jawaban tidak sesuai' ? 'checked' : '' }} />
                                <span style="font-size: 9pt;" color="#000000">Jawaban Tidak Sesuai</span>
                            </span>
                        </td>
                        <td width="8%">
                            <span style="font-size: 9pt;" color="#000000">3</span>
                        </td>
                    </tr>
                </table>
                <table width="100%">
                    <tr>
                        <td width="10%">
                            <span style="font-size: 9pt; " color="#000000"></span>
                        </td>
                        <td width="20%">
                            <span style="font-size: 9pt;" color="#000000"></span>
                        </td>
                        <td width="60%">
                            <span style="font-size: 9pt;" color="#000000">
                                <input
                                    type="checkbox"{{ isset($data['verbal']) && $data['verbal']['desc'] == 'Suara yang tidak dapat dimengerti (erangan,teriakan)' ? 'checked' : '' }} />
                                <span style="font-size: 9pt;" color="#000000">Suara yang tidak dapat
                                    dimengerti(erangan/teriakan)</span>
                            </span>
                        </td>
                        <td width="8%">
                            <span style="font-size: 9pt;" color="#000000">2</span>
                        </td>
                    </tr>
                </table>
                <table width="100%">
                    <tr>
                        <td width="10%">
                            <span style="font-size: 9pt; " color="#000000"></span>
                        </td>
                        <td width="20%">
                            <span style="font-size: 9pt;" color="#000000"></span>
                        </td>
                        <td width="60%">
                            <span style="font-size: 9pt;" color="#000000">
                                <input type="checkbox"
                                    {{ isset($data['verbal']) && $data['verbal']['desc'] == 'Tidak Merespon' ? 'checked' : '' }} />
                                <span style="font-size: 9pt;" color="#000000">Tidak respon</span>
                            </span>
                        </td>
                        <td width="8%">
                            <span style="font-size: 9pt;" color="#000000">1</span>
                        </td>
                    </tr>
                </table>
                <table width="100%">
                    <tr>
                        <td width="10%">
                            <span style="font-size: 9pt; " color="#000000">3</span>
                        </td>
                        <td width="20%">
                            <span style="font-size: 9pt;" color="#000000">Pergerakan</span>
                        </td>
                        <td width="60%">
                            <span style="font-size: 9pt;" color="#000000">
                                <input type="checkbox"
                                    {{ isset($data['pergerakan']) && $data['pergerakan']['desc'] == 'Mengikuti diperintah' ? 'checked' : '' }} />
                                <span style="font-size: 9pt;" color="#000000">Mengikuti diperintah</span>
                            </span>
                        </td>
                        <td width="8%">
                            <span style="font-size: 9pt;" color="#000000">6</span>
                        </td>
                    </tr>
                </table>
                <table width="100%">
                    <tr>
                        <td width="10%">
                            <span style="font-size: 9pt; " color="#000000"></span>
                        </td>
                        <td width="20%">
                            <span style="font-size: 9pt;" color="#000000"></span>
                        </td>
                        <td width="60%">
                            <span style="font-size: 9pt;" color="#000000">
                                <input type="checkbox"
                                    {{ isset($data['pergerakan']) && $data['pergerakan']['desc'] == 'Melokalisasi nyeri' ? 'checked' : '' }} />
                                <span style="font-size: 9pt;" color="#000000">Melokalisasi nyeri</span>
                            </span>
                        </td>
                        <td width="8%">
                            <span style="font-size: 9pt;" color="#000000">5</span>
                        </td>
                    </tr>
                </table>
                <table width="100%">
                    <tr>
                        <td width="10%">
                            <span style="font-size: 9pt; " color="#000000"></span>
                        </td>
                        <td width="20%">
                            <span style="font-size: 9pt;" color="#000000"></span>
                        </td>
                        <td width="60%">
                            <span style="font-size: 9pt;" color="#000000">
                                <input type="checkbox"
                                    {{ isset($data['pergerakan']) && $data['pergerakan']['desc'] == 'Menarik diri(withdrawn) dari rangsanga nyeri' ? 'checked' : '' }} />
                                <span style="font-size: 9pt;" color="#000000">Menarik diri(withdrawn) dari
                                    rangsangan nyeri</span>
                            </span>
                        </td>
                        <td width="8%">
                            <span style="font-size: 9pt;" color="#000000">4</span>
                        </td>
                    </tr>
                </table>
                <table width="100%">
                    <tr>
                        <td width="10%">
                            <span style="font-size: 9pt; " color="#000000"></span>
                        </td>
                        <td width="20%">
                            <span style="font-size: 9pt;" color="#000000"></span>
                        </td>
                        <td width="60%">
                            <span style="font-size: 9pt;" color="#000000">
                                <input type="checkbox"
                                    {{ isset($data['pergerakan']) && $data['pergerakan']['desc'] == 'Fleksi abnormal anggota gerak terhadap rangsang' ? 'checked' : '' }} />
                                <span style="font-size: 9pt;" color="#000000">Fleksi abnormal anggota gerak
                                    terahadap rangsang</span>
                            </span>
                        </td>
                        <td width="8%">
                            <span style="font-size: 9pt;" color="#000000">3</span>
                        </td>
                    </tr>
                </table>
                <table width="100%">
                    <tr>
                        <td width="10%">
                            <span style="font-size: 9pt; " color="#000000"></span>
                        </td>
                        <td width="20%">
                            <span style="font-size: 9pt;" color="#000000"></span>
                        </td>
                        <td width="60%">
                            <span style="font-size: 9pt;" color="#000000">
                                <input type="checkbox"
                                    {{ isset($data['pergerakan']) && $data['pergerakan']['desc'] == 'Ekstensi abnormal anggota gerak terhadap rangsang' ? 'checked' : '' }} />
                                <span style="font-size: 9pt;" color="#000000">EKstensi abnormal anggota gerak
                                    terhadap rangsang</span>
                            </span>
                        </td>
                        <td width="8%">
                            <span style="font-size: 9pt;" color="#000000">2</span>
                        </td>
                    </tr>
                </table>
                <table width="100%">
                    <tr>
                        <td width="10%">
                            <span style="font-size: 9pt; " color="#000000"></span>
                        </td>
                        <td width="20%">
                            <span style="font-size: 9pt;" color="#000000"></span>
                        </td>
                        <td width="60%">
                            <span style="font-size: 9pt;" color="#000000">
                                <input type="checkbox"
                                    {{ isset($data['pergerakan']) && $data['pergerakan']['desc'] == 'Tidak merespon' ? 'checked' : '' }} />
                                <span style="font-size: 9pt;" color="#000000">Tidak Merespon</span>
                            </span>
                        </td>
                        <td width="8%">
                            <span style="font-size: 9pt;" color="#000000">1</span>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>

        <tr>
            <td width="100%" colspan="6">
                <table width="100%">
                    <tr>
                        <td width="10%";>
                            <span style="font-size: 9pt;font-weight: bold;" color="#000000">Total</span>
                        </td>
                        <td width="1%";>
                            <span style="font-size: 9pt;" color="#000000"></span>
                        </td>
                        <td width="60%";>
                            <span style="font-size: 9pt;" color="#000000"></span>
                        </td>
                        <td width="8%";>
                            <span style="font-size: 9pt;font-weight: bold;"
                                color="#000000">{{ isset($data['jumlahNilai']) ? $data['jumlahNilai'] : '' }}</span>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>

        <tr>
            <td width="100%" colspan="3" style="padding:4px 0px;">
                <table width="100%">
                    <tr>
                        <td width="20%">
                            <span style="font-size: 9pt;font-weight: bold;" color="#000000">Riwayat Pengobatan / Obat
                                obatan</span>
                        </td>
                        <td width="20%">
                            <span style="font-size: 9pt;" color="#000000">:
                                {{ isset($data['riwayatPengobatan']) ? $data['riwayatPengobatan'] : '-' }} </span>
                        </td>
                        <td width="20%">
                        </td>
                    </tr>
                </table>
            </td>
        </tr>

        <tr>
            <td colspan="7" style="padding:4px 0px;">
                <span style="font-size: 10pt;font-weight: bold;" color="#000000">Dewasa dan Anak > 3 tahun (Wong Baker
                    Faces)</span>
            </td>
        </tr>

        <tr>
            <td width="100%" colspan="4">
                <table width="100%">
                    <tr>
                        <td width="25%";>
                            <input type="checkbox"
                                {{ isset($data['skoringNyeri']) && $data['skoringNyeri'] == 0 ? 'checked' : '' }} />
                            <span style="font-size: 9pt;" color="#000000">0 - 1 Tidak Nyeri</span>
                        </td>
                        <td width="25%";>
                            <input type="checkbox"
                                {{ isset($data['skoringNyeri']) && $data['skoringNyeri'] == 2 ? 'checked' : '' }} />
                            <span style="font-size: 9pt;" color="#000000">2 -3 Sedikit Nyeri</span>
                        </td>
                        <td width="25%";>
                            <input type="checkbox"
                                {{ isset($data['skoringNyeri']) && $data['skoringNyeri'] == 4 ? 'checked' : '' }} />
                            <span style="font-size: 9pt;" color="#000000">4 -5 Cukup Nyeri</span>
                        </td>
                        <td width="25%";>
                            <input type="checkbox"
                                {{ isset($data['skoringNyeri']) && $data['skoringNyeri'] == 6 ? 'checked' : '' }} />
                            <span style="font-size: 9pt;" color="#000000">6 - 7 Lumayan Nyeri</span>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td width="100%" colspan="4">
                <table width="100%">
                    <tr>
                        <td width="25%";>
                            <input type="checkbox"
                                {{ isset($data['skoringNyeri']) && $data['skoringNyeri'] == 8 ? 'checked' : '' }} />
                            <span style="font-size: 9pt;" color="#000000">8 - 9 Sangat Nyeri</span>
                        </td>
                        <td width="25%";>
                            <input type="checkbox"
                                {{ isset($data['skoringNyeri']) && $data['skoringNyeri'] == 10 ? 'checked' : '' }} />
                            <span style="font-size: 9pt;" color="#000000">10 Amat Sanagat Nyeri</span>
                        </td>
                        <td width="25%";>

                        </td>
                        <td width="25%";>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>


        <!-- // 11 13 -->
        {{-- <tr>
        <table width="100%">
            <td colspan="7" style="padding:4px 0px;">
                <span style="font-size: 10pt;font-weight: bold;" color="#000000">B.Riwayat Pasien</span>
            </td>
        </table>
    </tr> --}}
        <tr>
            <table width="100%">
                <td colspan="7" style="padding:4px 0px;">
                    <span style="font-size: 10pt;font-weight: bold;" color="#000000">Riwayat pasien</span>
                </td>
            </table>
        </tr>

        <tr>
            <table width="100%">
                <tr>
                    <td width="20%">
                        <input type="checkbox"
                            {{ isset($data['riwayatPenyakit_0']) && $data['riwayatPenyakit_0'] == 'Penyakit utama' ? 'checked' : '' }} />
                        <span style="font-size: 9pt;" color="#000000">Penyakit utama</span>
                    </td>
                    <td width="20%">
                        <input type="checkbox"
                            {{ isset($data['riwayatPenyakit_1']) && $data['riwayatPenyakit_1'] == 'Operasi' ? 'checked' : '' }} />
                        <span style="font-size: 9pt;" color="#000000">Operasi</span>
                    </td>

                    <td width="20%";>
                        <input type="checkbox"
                            {{ isset($data['riwayatPenyakit_2']) && $data['riwayatPenyakit_2'] == 'Cidera/mayor' ? 'checked' : '' }} />
                        <span style="font-size: 9pt;" color="#000000">Ceder/mayor</span>
                    </td>
                    <td width="20%";>
                        <input type="checkbox"
                            {{ isset($data['riwayatPenyakit_3']) && $data['riwayatPenyakit_3'] == 'Hipertensi' ? 'checked' : '' }} />
                        <span style="font-size: 9pt;" color="#000000">Hipertensi</span>
                    </td>
                </tr>
            </table>
            <table width="100%">
                <tr>
                    <td width="20%";>
                        <input type="checkbox"
                            {{ isset($data['riwayatPenyakit_4']) && $data['riwayatPenyakit_4'] == 'PPOK' ? 'checked' : '' }} />
                        <span style="font-size: 9pt;" color="#000000">PPOK</span>
                    </td>
                    <td width="20%";>
                        <input type="checkbox"
                            {{ isset($data['riwayatPenyakit_5']) && $data['riwayatPenyakit_5'] == 'Diabetes' ? 'checked' : '' }} />
                        <span style="font-size: 9pt;" color="#000000">Diabetes</span>
                    </td>

                    <td width="20%";>
                        <input type="checkbox"
                            {{ isset($data['riwayatPenyakit_6']) && $data['riwayatPenyakit_6'] == 'Kanker' ? 'checked' : '' }} />
                        <span style="font-size: 9pt;" color="#000000">Kanker</span>
                    </td>
                    <td width="20%";>
                        <input type="checkbox"
                            {{ isset($data['riwayatPenyakit_7']) && $data['riwayatPenyakit_7'] == 'Asma' ? 'checked' : '' }} />
                        <span style="font-size: 9pt;" color="#000000">Asma</span>
                    </td>
                </tr>
            </table>
            <table width="100%">
                <tr>
                    <td width="20%";>
                        <input type="checkbox"
                            {{ isset($data['riwayatPenyakit_8']) && $data['riwayatPenyakit_8'] == 'Infrak Miokard' ? 'checked' : '' }} />
                        <span style="font-size: 9pt;" color="#000000">Infrak Miokard</span>
                    </td>
                    <td width="20%";>
                        <input type="checkbox"
                            {{ isset($data['riwayatPenyakit_9']) && $data['riwayatPenyakit_9'] == 'Hepatitis' ? 'checked' : '' }} />
                        <span style="font-size: 9pt;" color="#000000">Hepatitis</span>
                    </td>

                    <td width="20%";>
                        <input type="checkbox"
                            {{ isset($data['riwayatPenyakit_10']) && $data['riwayatPenyakit_10'] == 'Kejang' ? 'checked' : '' }} />
                        <span style="font-size: 9pt;" color="#000000">Kejang</span>
                    </td>
                    <td width="20%";>
                        <input type="checkbox"
                            {{ isset($data['riwayatPenyakit_11']) && $data['riwayatPenyakit_11'] == 'TB' ? 'checked' : '' }} />
                        <span style="font-size: 9pt;" color="#000000">TB</span>
                    </td>
                </tr>
            </table>
            <table width="100%">
                <tr>
                    <td width="20%";>
                        <input type="checkbox"
                            {{ isset($data['riwayatPenyakit_12']) && $data['riwayatPenyakit_12'] == 'Jantung' ? 'checked' : '' }} />
                        <span style="font-size: 9pt;" color="#000000">Jantung</span>
                    </td>
                    <td width="20%";>
                        <input type="checkbox"
                            {{ isset($data['riwayatPenyakit_13']) && $data['riwayatPenyakit_13'] == 'Ulkus' ? 'checked' : '' }} />
                        <span style="font-size: 9pt;" color="#000000">Ulkus</span>
                    </td>

                    <td width="20%";>
                        <input type="checkbox"
                            {{ isset($data['riwayatPenyakit_14']) && $data['riwayatPenyakit_14'] == 'Penyakit ginjal' ? 'checked' : '' }} />
                        <span style="font-size: 9pt;" color="#000000">Penakit ginjal</span>
                    </td>
                    <td width="20%";>
                        <input type="checkbox"
                            {{ isset($data['riwayatPenyakit_15']) && $data['riwayatPenyakit_15'] == 'Ganguan Jiwa' ? 'checked' : '' }} />
                        <span style="font-size: 9pt;" color="#000000">Ganguan jiwa</span>
                    </td>
                </tr>
            </table>
            <table width="100%">
                <tr>
                    <td width="20%";>
                        <input type="checkbox"
                            {{ isset($data['riwayatPenyakit_16']) && $data['riwayatPenyakit_16'] == 'Penyakit paru lainnya' ? 'checked' : '' }} />
                        <span style="font-size: 9pt;" color="#000000">Penyakit paru</span>
                    </td>
                    <td width="20%";>
                        <span style="font-size: 9pt;" color="#000000"></span>
                    </td>

                    <td width="20%";>

                    </td>
                    <td width="20%";>

                    </td>
                </tr>
            </table>
            <table width="100%">
                <tr>
                    <td width="100%";>
                        <span style="font-size: 9pt;font-weight: bold;" color="#000000">Deskripsi penyakit dan
                            operasi yang tidak tercantum diatas</span>
                    </td>
                    <td width="20%">

                    </td>

                    <td width="20%";>
                    </td>
                    <td width="20%";>

                    </td>
                </tr>
            </table>
            <table width="100%">
                <tr>
                    <td width="100%";>
                        <span style="font-size: 9pt;f" color="#000000">Catatan :
                            {{ isset($data['riwayatPenyakitLainnya']) ? $data['riwayatPenyakitLainnya'] : '-' }} </span>
                    </td>
                </tr>
            </table>
        </tr>
        <tr>
            <table width="100%">
                <tr>
                    <td width="50%";>
                        <span style="font-size: 9pt;font-weight: bold;" color="#000000">Riwayat (alergi)</span>
                    </td>
                </tr>
            </table>
            <table width="100%">
                <tr>
                    <td width="20%";>
                        <input type="checkbox"
                            {{ isset($data['riwayatAlergi']) && $data['riwayatAlergi'] == 'Tidak' ? 'checked' : '' }} />
                        <span style="font-size: 9pt;" color="#000000">Tidak</span>
                    </td>
                    <td width="12%";>
                        <span style="font-size: 9pt;" color="#000000">Ya (jelaskan)</span>
                    </td>

                    <td width="50%";>
                        <span style="font-size: 9pt;f" color="#000000">:
                            {{ isset($data['ketRiwayatAlergi']) ? $data['ketRiwayatAlergi'] : '' }}</span>
                    </td>
                </tr>
            </table>
        </tr>
        <tr>
            <table width="100%">
                <tr>
                    <td width="50%";>
                        <span style="font-size: 9pt;font-weight: bold;" color="#000000">Alkohol</span>
                    </td>
                </tr>
            </table>
            <table width="100%">
                <tr>
                    <td width="20%";>
                        <input type="checkbox"
                            {{ isset($data['alkohol/obat']) && $data['alkohol/obat'] == 'Tidak' ? 'checked' : '' }} />
                        <span style="font-size: 9pt;" color="#000000">Tidak</span>
                    </td>
                    <td width="20%";>
                        <input type="checkbox"
                            {{ isset($data['alkohol/obat']) && $data['alkohol/obat'] == 'Berhenti' ? 'checked' : '' }} />
                        <span style="font-size: 9pt;" color="#000000">Berhenti</span>
                    </td>
                    <td width="20%";>
                        <input type="checkbox"
                            {{ isset($data['alkohol/obat']) && $data['alkohol/obat'] == 'Ya' ? 'checked' : '' }} />
                        <span style="font-size: 9pt;" color="#000000">Ya</span>
                    </td>

                    <td width="20%";>
                        <span style="font-size: 9pt;" color="#000000">Jenis :
                            {{ isset($data['jenisAlkohol/obat']) ? $data['jenisAlkohol/obat'] : '-' }}</span>
                    </td>
                    <td width="20%";>
                        <span style="font-size: 9pt;" color="#000000">Jumlah/hari :
                            {{ isset($data['jumlahAlkohol/hari']) ? $data['jumlahAlkohol/hari'] : '-' }}</span>
                    </td>
                </tr>
            </table>
        </tr>
        <tr>
            <table width="100%">
                <tr>
                    <td width="50%";>
                        <span style="font-size: 9pt;font-weight: bold;" color="#000000">Merokok</span>
                    </td>
                </tr>
            </table>
            <table width="100%">
                <tr>
                    <td width="20%";>
                        <input type="checkbox"
                            {{ isset($data['merokok']) && $data['merokok'] == 'Tidak' ? 'checked' : '' }} />
                        <span style="font-size: 9pt;" color="#000000">Tidak</span>
                    </td>
                    <td width="20%";>
                        <input type="checkbox"
                            {{ isset($data['merokok']) && $data['merokok'] == 'Berhenti' ? 'checked' : '' }} />
                        <span style="font-size: 9pt;" color="#000000">Berhenti</span>
                    </td>
                    <td width="20%";>
                        <input type="checkbox"
                            {{ isset($data['merokok']) && $data['merokok'] == 'Ya' ? 'checked' : '' }} />
                        <span style="font-size: 9pt;" color="#000000">Ya</span>
                    </td>

                    <td width="20%";>
                        <span style="font-size: 9pt;" color="#000000">Jenis :
                            {{ isset($data['jenisRokok']) ? $data['jenisRokok'] : '-' }}</span>
                    </td>
                    <td width="20%";>
                        <span style="font-size: 9pt;" color="#000000">Jumlah/hari :
                            {{ isset($data['jmlRokok/hari']) ? $data['jmlRokok/hari'] : '-' }}</span>
                    </td>
                </tr>
            </table>
        </tr>
        <tr>
            <table width="100%">
                <tr>
                    <td width="50%";>
                        <span style="font-size: 9pt;font-weight: bold;" color="#000000">Riwayat Keluarga</span>
                    </td>
                </tr>
            </table>
            <table width="100%">
                <tr>
                    <td width="20%";>
                        <input type="checkbox"
                            {{ isset($data['riwayatKeluarga_0']) && $data['riwayatKeluarga_0'] == 'Penyakit Jantung' ? 'checked' : '' }} />
                        <span style="font-size: 9pt;" color="#000000">Penyakit Jantung</span>
                    </td>
                    <td width="20%";>
                        <input type="checkbox"
                            {{ isset($data['riwayatKeluarga_1']) && $data['riwayatKeluarga_1'] == 'Hipertensi' ? 'checked' : '' }} />
                        <span style="font-size: 9pt;" color="#000000">Hipertensi</span>
                    </td>
                    <td width="20%";>
                        <input type="checkbox"
                            {{ isset($data['riwayatKeluarga_2']) && $data['riwayatKeluarga_2'] == 'Stroke' ? 'checked' : '' }} />
                        <span style="font-size: 9pt;" color="#000000">Stroke</span>
                    </td>

                    <td width="20%";>
                        <input type="checkbox"
                            {{ isset($data['riwayatKeluarga_3']) && $data['riwayatKeluarga_3'] == 'Asma' ? 'checked' : '' }} />
                        <span style="font-size: 9pt;" color="#000000">Asma</span>
                    </td>
                    <td width="20%";>
                        <input type="checkbox"
                            {{ isset($data['riwayatKeluarga_4']) && $data['riwayatKeluarga_4'] == 'Gangguan Jiwa' ? 'checked' : '' }} />
                        <span style="font-size: 9pt;" color="#000000">Gangguan Jiwa</span>
                    </td>
                </tr>
            </table>
            <table width="100%">
                <tr>
                    <td width="20%";>
                        <input type="checkbox"
                            {{ isset($data['riwayatKeluarga_5']) && $data['riwayatKeluarga_5'] == 'Ginjal' ? 'checked' : '' }} />
                        <span style="font-size: 9pt;" color="#000000">Ginjal</span>
                    </td>
                    <td width="20%";>
                        <input type="checkbox"
                            {{ isset($data['riwayatKeluarga_6']) && $data['riwayatKeluarga_6'] == 'TB' ? 'checked' : '' }} />
                        <span style="font-size: 9pt;" color="#000000">TB</span>
                    </td>
                    <td width="20%";>
                        <input type="checkbox"
                            {{ isset($data['riwayatKeluarga_7']) && $data['riwayatKeluarga_7'] == 'Gangguan Hematologi' ? 'checked' : '' }} />
                        <span style="font-size: 9pt;" color="#000000">Gangguan Hematologi</span>
                    </td>

                    <td width="20%";>
                        <input type="checkbox"
                            {{ isset($data['riwayatKeluarga_8']) && $data['riwayatKeluarga_8'] == 'Anestesi' ? 'checked' : '' }} />
                        <span style="font-size: 9pt;" color="#000000">Anestesi</span>
                    </td>
                    <td width="20%";>
                        <input type="checkbox"
                            {{ isset($data['riwayatKeluarga_9']) && $data['riwayatKeluarga_9'] == 'Kanker' ? 'checked' : '' }} />
                        <span style="font-size: 9pt;" color="#000000">Kanker</span>
                    </td>
                </tr>
            </table>
            <table width="100%">
                <tr>
                    <td width="20%";>
                        <input type="checkbox"
                            {{ isset($data['riwayatKeluarga_10']) && $data['riwayatKeluarga_10'] == 'Kejang' ? 'checked' : '' }} />
                        <span style="font-size: 9pt;" color="#000000">Kejang</span>
                    </td>
                    <td width="20%";>
                        <input type="checkbox"
                            {{ isset($data['riwayatKeluarga_11']) && $data['riwayatKeluarga_11'] == 'Diabetes' ? 'checked' : '' }} />
                        <span style="font-size: 9pt;" color="#000000">Diabetes</span>
                    </td>
                    <td width="20%";>
                        <input type="checkbox"
                            {{ isset($data['riwayatKeluarga_12']) && $data['riwayatKeluarga_12'] == 'Tidak ada' ? 'checked' : '' }} />
                        <span style="font-size: 9pt;" color="#000000">Tidak ada</span>
                    </td>

                    <td width="20%";>
                        <input type="checkbox"
                            {{ isset($data['riwayatKeluarga_13']) && $data['riwayatKeluarga_13'] == 'Lainnya' ? 'checked' : '' }} />
                        <span style="font-size: 9pt;" color="#000000">Lainnya</span>
                    </td>
                    <td width="20%";>

                    </td>
                </tr>
            </table>
        </tr>


        <tr>
            <td colspan="7" style="padding:4px 0px;">
                <span style="font-size: 10pt;font-weight: bold;" color="#000000">RESIKO NUTRISIONAL</span>
            </td>
        </tr>
        <tr>
            <td colspan="7" style="padding:4px 0px;">
                <span style="font-size: 10pt;font-weight: bold;" color="#000000">Malnutrition Screening Tools
                    (MST)</span>
            </td>
        </tr>
        <tr>
            <td width="100%" colspan="4">
                <table width="100%">
                    <tr>
                        <td width="10%";>
                            <span style="font-size: 9pt;" color="#000000">Nomor</span>
                        </td>
                        <td width="40%";>
                            <span style="font-size: 9pt;" color="#000000">Parameter</span>
                        </td>
                        <td width="40%";>
                            <span style="font-size: 9pt;" color="#000000"></span>
                        </td>
                        <td width="8%";>
                            <span style="font-size: 9pt;" color="#000000">Nilai</span>
                        </td>
                    </tr>
                </table>
                <table width="100%">
                    <tr>
                        <td width="10%";>
                            <span style="font-size: 9pt;" color="#000000">1</span>
                        </td>
                        <td width="40%";>
                            <span style="font-size: 9pt;" color="#000000">Apakah ada penurunan berat badan yang
                                tidak diinginkan selama 6 bulan terakhir ?</span>
                        </td>
                        <td width="40%";>
                            <input type="checkbox"
                                {{ isset($data['penurunanBB']) && $data['penurunanBB']['keterangan'] == 'Tidak' ? 'checked' : '' }} />
                            <span style="font-size: 9pt;" color="#000000">a. Tidak</span>
                        </td>
                        <td width="8%";>
                            <span style="font-size: 9pt;" color="#000000">0</span>
                        </td>
                    </tr>
                </table>
                <table width="100%">
                    <tr>
                        <td width="10%";>
                            <span style="font-size: 9pt;" color="#000000"></span>
                        </td>
                        <td width="40%";>
                            <span style="font-size: 9pt;" color="#000000"></span>
                        </td>
                        <td width="40%";>
                            <input type="checkbox"
                                {{ isset($data['penurunanBB']) && $data['penurunanBB']['keterangan'] == 'Tidak Yakin' ? 'checked' : '' }} />
                            <span style="font-size: 9pt;" color="#000000">b. Tidak Yakin
                                (Tanda: ukuran baju atau celana menjadi lebih longgar)</span>
                        </td>
                        <td width="8%";>
                            <span style="font-size: 9pt;" color="#000000">2</span>
                        </td>
                    </tr>
                </table>
                <table width="100%">
                    <tr>
                        <td width="10%";>
                            <span style="font-size: 9pt;" color="#000000"></span>
                        </td>
                        <td width="40%";>
                            <span style="font-size: 9pt;" color="#000000"></span>
                        </td>
                        <td width="40%";>
                            <input type="checkbox"
                                {{ isset($data['penurunanBB']) && $data['penurunanBB']['keterangan'] == 'Ya,1-5 Kg' ? 'checked' : '' }} />
                            <span style="font-size: 9pt;" color="#000000">c. Ya, 1-5 Kg</span>
                        </td>
                        <td width="8%";>
                            <span style="font-size: 9pt;" color="#000000">1</span>
                        </td>
                    </tr>
                </table>
                <table width="100%">
                    <tr>
                        <td width="10%";>
                            <span style="font-size: 9pt;" color="#000000"></span>
                        </td>
                        <td width="40%";>
                            <span style="font-size: 9pt;" color="#000000"></span>
                        </td>
                        <td width="40%";>
                            <input type="checkbox"
                                {{ isset($data['penurunanBB']) && $data['penurunanBB']['keterangan'] == '6-10 Kg' ? 'checked' : '' }} />
                            <span style="font-size: 9pt;" color="#000000">6-10 Kg</span>
                        </td>
                        <td width="8%";>
                            <span style="font-size: 9pt;" color="#000000">2</span>
                        </td>
                    </tr>
                </table>
                <table width="100%">
                    <tr>
                        <td width="10%";>
                            <span style="font-size: 9pt;" color="#000000"></span>
                        </td>
                        <td width="40%";>
                            <span style="font-size: 9pt;" color="#000000"></span>
                        </td>
                        <td width="40%";>
                            <input type="checkbox"
                                {{ isset($data['penurunanBB']) && $data['penurunanBB']['keterangan'] == '11-15 Kg' ? 'checked' : '' }} />
                            <span style="font-size: 9pt;" color="#000000">11-15 Kg</span>
                        </td>
                        <td width="8%";>
                            <span style="font-size: 9pt;" color="#000000">3</span>
                        </td>
                    </tr>
                </table>
                <table width="100%">
                    <tr>
                        <td width="10%";>
                            <span style="font-size: 9pt;" color="#000000"></span>
                        </td>
                        <td width="40%";>
                            <span style="font-size: 9pt;" color="#000000"></span>
                        </td>
                        <td width="40%";>
                            <input type="checkbox"
                                {{ isset($data['penurunanBB']) && $data['penurunanBB']['keterangan'] == '> 15 Kg' ? 'checked' : '' }} />
                            <span style="font-size: 9pt;" color="#000000"> >15 Kg</span>
                        </td>
                        <td width="8%";>
                            <span style="font-size: 9pt;" color="#000000">4</span>
                        </td>
                    </tr>
                </table>
                <table width="100%">
                    <tr>
                        <td width="10%";>
                            <span style="font-size: 9pt;" color="#000000">2</span>
                        </td>
                        <td width="40%";>
                            <span style="font-size: 9pt;" color="#000000">Apakah asupan makan menurun yang
                                dikarenakan adanya penurunan nafsu makan/kesulitan menerima makan ?</span>
                        </td>
                        <td width="40%";>
                            <input type="checkbox"
                                {{ isset($data['penurunanNafsuMakan']) && $data['penurunanNafsuMakan']['keterangan'] == 'Tidak' ? 'checked' : '' }} />
                            <span style="font-size: 9pt;" color="#000000">a. Tidak</span>
                        </td>
                        <td width="8%";>
                            <span style="font-size: 9pt;" color="#000000">0</span>
                        </td>
                    </tr>
                </table>
                <table width="100%">
                    <tr>
                        <td width="10%";>
                            <span style="font-size: 9pt;" color="#000000"></span>
                        </td>
                        <td width="40%";>
                            <span style="font-size: 9pt;" color="#000000"></span>
                        </td>
                        <td width="40%";>
                            <input type="checkbox"
                                {{ isset($data['penurunanNafsuMakan']) && $data['penurunanNafsuMakan']['keterangan'] == 'Ya' ? 'checked' : '' }} />
                            <span style="font-size: 9pt;" color="#000000">b. YA</span>
                        </td>
                        <td width="8%";>
                            <span style="font-size: 9pt;" color="#000000">1</span>
                        </td>
                    </tr>
                </table>
                <table width="100%">
                    <tr>
                        <td width="10%";>
                            <span style="font-size: 9pt;font-weight: bold;" color="#000000">Total</span>
                        </td>
                        <td width="40%";>
                            <span style="font-size: 9pt;" color="#000000"></span>
                        </td>
                        <td width="40%";>
                            <span style="font-size: 9pt;" color="#000000"></span>
                        </td>
                        <td width="8%";>
                            <span style="font-size: 9pt;font-weight: bold;"
                                color="#000000">{{ isset($data['totalNilaiMST']) ? $data['totalNilaiMST'] : '' }}</span>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td colspan="7" style="padding:4px 0px;">
                <span style="font-size: 10pt;font-weight: bold;" color="#000000">Pemeriksaan fisik umum</span>
            </td>
        </tr>
        <tr>
            <td colspan="7" style="padding:4px 0px;">
                <span style="font-size: 10pt;font-weight: bold;" color="#000000">Pemeriksaan mata, telinga
                    hidung dan tenggorokan</span>
            </td>
        </tr>
        <tr>
            <td width="100%" colspan="4">
                <table width="100%">
                    <tr>
                        <td width="25%";>
                            <input type="checkbox"
                                {{ isset($data['pmrksFisikMTHT_0']) && $data['pmrksFisikMTHT_0'] == 'Normal' ? 'checked' : '' }} />
                            <span style="font-size: 9pt;" color="#000000">Normal</span>
                        </td>
                        <td width="25%";>
                            <input type="checkbox"
                                {{ isset($data['pmrksFisikMTHT_1']) && $data['pmrksFisikMTHT_1'] == 'Gangguan Visus' ? 'checked' : '' }} />
                            <span style="font-size: 9pt;" color="#000000">Gangguan Visus</span>
                        </td>
                        <td width="25%";>
                            <input type="checkbox"
                                {{ isset($data['pmrksFisikMTHT_2']) && $data['pmrksFisikMTHT_2'] == 'Glaukoma' ? 'checked' : '' }} />
                            <span style="font-size: 9pt;" color="#000000">Glaukoma</span>
                        </td>
                        <td width="25%";>
                            <input type="checkbox"
                                {{ isset($data['pmrksFisikMTHT_3']) && $data['pmrksFisikMTHT_3'] == 'Sulit Mendengar' ? 'checked' : '' }} />
                            <span style="font-size: 9pt;" color="#000000">Sulit Mendengar</span>
                        </td>
                    </tr>
                </table>
                <table width="100%">
                    <tr>
                        <td width="25%";>
                            <input type="checkbox"
                                {{ isset($data['pmrksFisikMTHT_4']) && $data['pmrksFisikMTHT_4'] == 'Gusi' ? 'checked' : '' }} />
                            <span style="font-size: 9pt;" color="#000000">Gusi</span>
                        </td>
                        <td width="25%";>
                            <input type="checkbox"
                                {{ isset($data['pmrksFisikMTHT_5']) && $data['pmrksFisikMTHT_5'] == 'Kemerahan' ? 'checked' : '' }} />
                            <span style="font-size: 9pt;" color="#000000">Kemerahan</span>
                        </td>
                        <td width="25%";>
                            <input type="checkbox"
                                {{ isset($data['pmrksFisikMTHT_6']) && $data['pmrksFisikMTHT_6'] == 'Drainase' ? 'checked' : '' }} />
                            <span style="font-size: 9pt;" color="#000000">Drainase</span>
                        </td>
                        <td width="25%";>
                            <input type="checkbox"
                                {{ isset($data['pmrksFisikMTHT_7']) && $data['pmrksFisikMTHT_7'] == 'Buta' ? 'checked' : '' }} />
                            <span style="font-size: 9pt;" color="#000000">Buta</span>
                        </td>
                    </tr>
                </table>
                <table width="100%">
                    <tr>
                        <td width="25%";>
                            <input
                                type="checkbox"{{ isset($data['pmrksFisikMTHT_8']) && $data['pmrksFisikMTHT_8'] == 'Tuli' ? 'checked' : '' }} />
                            <span style="font-size: 9pt;" color="#000000">Tuli</span>
                        </td>
                        <td width="25%";>
                            <input type="checkbox"
                                {{ isset($data['pmrksFisikMTHT_9']) && $data['pmrksFisikMTHT_9'] == 'Rasa Terbakar' ? 'checked' : '' }} />
                            <span style="font-size: 9pt;" color="#000000">Rasa Terbakar</span>
                        </td>
                        <td width="25%";>
                            <input type="checkbox"
                                {{ isset($data['pmrksFisikMTHT_10']) && $data['pmrksFisikMTHT_10'] == 'Gigi' ? 'checked' : '' }} />
                            <span style="font-size: 9pt;" color="#000000">Gigi</span>
                        </td>
                        <td width="25%";>
                            <input type="checkbox"
                                {{ isset($data['pmrksFisikMTHT_11']) && $data['pmrksFisikMTHT_11'] == 'Luka' ? 'checked' : '' }} />
                            <span style="font-size: 9pt;" color="#000000">Luka</span>
                        </td>
                    </tr>
                </table>
                <table width="100%">
                    <tr>
                        <td width="100%";>
                            <span style="font-size: 9pt;" color="#000000">Lainya :
                                {{ isset($data['hasilMTHTLainnya']) ? $data['hasilMTHTLainnya'] : '-' }}</span>
                        </td>

                    </tr>
                </table>

            </td>
        </tr>

        <tr>
            <td width="100%">
                <table width="100%">
                    <tr>
                        <td width="100%";>
                            <span style="font-size: 9pt;" color="#000000">Catatan :
                                {{ isset($data['catatanPemrksMTHT']) ? $data['catatanPemrksMTHT'] : '-' }} </span>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>

        <tr>
            <td width="100%">
                <table width="100%">
                    <tr>
                        <td width="100%";>
                            <span style="font-size: 10pt;font-weight: bold;" color="#000000">Pemeriksaan paru
                                (kecepatan, kedalaman, pola, suara nafas)</span>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <input type="checkbox"
                                {{ isset($data['pmrksParu0']) && $data['pmrksParu0'] == 'Normal' ? 'checked' : '' }} />
                            <span style="font-size: 9pt;" color="#000000">Normal</span>
                        </td>
                    </tr>
                </table>
            </td>

        </tr>

        <tr>
            <td colspan="7" style="padding:4px 0px;">
                <span style="font-size: 10pt;font-weight: bold;" color="#000000">Kiri</span>
            </td>
        </tr>
        <tr>
            <table width="100%">
                <tr>
                    <td width="25%";>
                        <input type="checkbox"
                            {{ isset($data['pmrksParuKiri_0']) && $data['pmrksParuKiri_0'] == 'Asimetris' ? 'checked' : '' }} />
                        <span style="font-size: 9pt;" color="#000000">Asimetris</span>
                    </td>
                    <td width="25%";>
                        <input type="checkbox"
                            {{ isset($data['pmrksParuKiri_1']) && $data['pmrksParuKiri_1'] == 'Takipnea' ? 'checked' : '' }} />
                        <span style="font-size: 9pt;" color="#000000">Takipnea </span>
                    </td>
                    <td width="25%";>
                        <input type="checkbox"
                            {{ isset($data['pmrksParuKiri_2']) && $data['pmrksParuKiri_2'] == 'Ronki' ? 'checked' : '' }} />
                        <span style="font-size: 9pt;" color="#000000">Ronki</span>
                    </td>
                    <td width="25%";>
                        <input type="checkbox"
                            {{ isset($data['pmrksParuKiri_3']) && $data['pmrksParuKiri_3'] == 'Barrel chest' ? 'checked' : '' }} />
                        <span style="font-size: 9pt;" color="#000000">Barrel chest</span>
                    </td>
                </tr>
            </table>
            <table width="100%">
                <tr>
                    <td width="25%";>
                        <input type="checkbox" type="checkbox"
                            {{ isset($data['pmrksParuKiri_4']) && $data['pmrksParuKiri_4'] == 'Bradipnea' ? 'checked' : '' }} />
                        <span style="font-size: 9pt;" color="#000000">Bradipnea</span>
                    </td>
                    <td width="25%";>
                        <input type="checkbox" type="checkbox"
                            {{ isset($data['pmrksParuKiri_4']) && $data['pmrksParuKiri_4'] == 'Mengi/Wheezing' ? 'checked' : '' }} />
                        <span style="font-size: 9pt;" color="#000000">Mengi/Wheezing</span>
                    </td>
                    <td width="25%";>
                        <input type="checkbox" type="checkbox"
                            {{ isset($data['pmrksParuKiri_5']) && $data['pmrksParuKiri_5'] == 'Sesak' ? 'checked' : '' }} />
                        <span style="font-size: 9pt;" color="#000000">Sesak</span>
                    </td>
                    <td width="25%";>
                        <input type="checkbox"type="checkbox"
                            {{ isset($data['pmrksParuKiri_6']) && $data['pmrksParuKiri_6'] == 'Dangkal' ? 'checked' : '' }} />
                        <span style="font-size: 9pt;" color="#000000">Dangkal</span>
                    </td>
                </tr>
            </table>
            <table width="100%">
                <tr>
                    <td width="25%";>
                        <input type="checkbox" type="checkbox"
                            {{ isset($data['pmrksParuKiri_7']) && $data['pmrksParuKiri_7'] == 'Menghilang' ? 'checked' : '' }} />
                        <span style="font-size: 9pt;" color="#000000">Menghilang</span>
                    </td>
                    <td width="25%";>

                    </td>
                    <td width="25%";>

                    </td>
                    <td width="25%";>

                    </td>
                </tr>
            </table>
        </tr>

        <tr>
            <td colspan="7" style="padding:4px 0px;">
                <span style="font-size: 10pt;font-weight: bold;" color="#000000">Kanan</span>
            </td>
        </tr>

        <tr>
            <table width="100%">
                <tr>
                    <td width="25%";>
                        <input type="checkbox"
                            {{ isset($data['pmrksParuKanan_0']) && $data['pmrksParuKanan_0'] == 'Asimetris' ? 'checked' : '' }} />
                        <span style="font-size: 9pt;" color="#000000">Asimetris</span>
                    </td>
                    <td width="25%";>
                        <input type="checkbox"
                            {{ isset($data['pmrksParuKanan_1']) && $data['pmrksParuKanan_1'] == 'Takipnea' ? 'checked' : '' }} />
                        <span style="font-size: 9pt;" color="#000000">Takipnea</span>
                    </td>
                    <td width="25%";>
                        <input type="checkbox"
                            {{ isset($data['pmrksParuKanan_2']) && $data['pmrksParuKanan_2'] == 'Ronki' ? 'checked' : '' }} />
                        <span style="font-size: 9pt;" color="#000000">Ronki</span>
                    </td>
                    <td width="25%";>
                        <input type="checkbox"
                            {{ isset($data['pmrksParuKanan_3']) && $data['pmrksParuKanan_3'] == 'Barrel chest' ? 'checked' : '' }} />
                        <span style="font-size: 9pt;" color="#000000">Barrel chest</span>
                    </td>
                </tr>
            </table>
            <table width="100%">
                <tr>
                    <td width="25%";>
                        <input type="checkbox"
                            {{ isset($data['pmrksParuKanan_4']) && $data['pmrksParuKanan_4'] == 'Bradipnea' ? 'checked' : '' }} />
                        <span style="font-size: 9pt;" color="#000000">Bradipnea</span>
                    </td>
                    <td width="25%";>
                        <input type="checkbox"
                            {{ isset($data['pmrksParuKanan_5']) && $data['pmrksParuKanan_5'] == 'Mengi/Wheezing' ? 'checked' : '' }} />
                        <span style="font-size: 9pt;" color="#000000">Mengi/Wheezing</span>
                    </td>
                    <td width="25%";>
                        <input type="checkbox"
                            {{ isset($data['pmrksParuKanan_6']) && $data['pmrksParuKanan_6'] == 'Sesak' ? 'checked' : '' }} />
                        <span style="font-size: 9pt;" color="#000000">Sesak</span>
                    </td>
                    <td width="25%";>
                        <input type="checkbox"
                            {{ isset($data['pmrksParuKanan_7']) && $data['pmrksParuKanan_7'] == 'Dangkal' ? 'checked' : '' }} />
                        <span style="font-size: 9pt;" color="#000000">Dangkal</span>
                    </td>
                </tr>
            </table>
            <table width="100%">
                <tr>
                    <td width="25%";>
                        <input type="checkbox"
                            {{ isset($data['pmrksParuKanan_8']) && $data['pmrksParuKanan_8'] == 'Menghilang' ? 'checked' : '' }} />
                        <span style="font-size: 9pt;" color="#000000">Menghilang</span>
                    </td>
                    <td width="25%";>
                        <input type="checkbox"
                            {{ isset($data['pmrksParuKanan_9']) && $data['pmrksParuKanan_9'] == 'Batuk' ? 'checked' : '' }} />
                        <span style="font-size: 9pt;" color="#000000">Batuk</span>
                    </td>
                    <td width="25%";>
                        <input type="checkbox"
                            {{ isset($data['pmrksParuKanan_10']) && $data['pmrksParuKanan_10'] == 'Warna Dahak' ? 'checked' : '' }} />
                        <span style="font-size: 9pt;" color="#000000">Menghilang</span>
                    </td>
                    <td width="25%";>

                    </td>
                </tr>
            </table>
        </tr>
        <tr>
            <td width="100%">
                <table width="100%">
                    <tr>
                        <td width="100%";>
                            <span style="font-size: 9pt;" color="#000000">Lainnya :
                                {{ isset($data['pmrksParuLainnya']) ? $data['pmrksParuLainnya'] : '-' }}</span>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>

        <tr>
            <td width="100%">
                <table width="100%">
                    <tr>
                        <td width="100%";>
                            <span style="font-size: 9pt;" color="#000000">Catatan :
                                {{ isset($data['catatanPemrksParu']) ? $data['catatanPemrksParu'] : '-' }}</span>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>

        </tr>

        <tr>
        <tr>
            <td colspan="7" style="padding:4px 0px;">
                <span style="font-size: 10pt;font-weight: bold;" color="#000000">Pemeriksaan kardiovaskuler
                    (kecepatan, denyut, tekanan darah, sirkulasi, retensi cairan)</span>
            </td>
        </tr>
        <tr>
            <td width="100%" colspan="4">
                <table width="100%">
                    <tr>
                        <td width="25%">
                            <input type="checkbox"
                                {{ isset($data['pmrksKardiovakuler_0']) && $data['pmrksKardiovakuler_0'] == 'Normal' ? 'checked' : '' }} />
                            <span style="font-size: 9pt;" color="#000000">Normal</span>
                        </td>
                        <td width="25%">
                            <input type="checkbox"
                                {{ isset($data['pmrksKardiovakuler_1']) && $data['pmrksKardiovakuler_1'] == 'Takikardi' ? 'checked' : '' }} />
                            <span style="font-size: 9pt;" color="#000000">Takikardi</span>
                        </td>
                        <td width="25%">
                            <input type="checkbox"
                                {{ isset($data['pmrksKardiovakuler_2']) && $data['pmrksKardiovakuler_2'] == 'Ireguler' ? 'checked' : '' }} />
                            <span style="font-size: 9pt;" color="#000000">Ireguler </span>
                        </td>
                        <td width="25%">
                            <input type="checkbox"
                                {{ isset($data['pmrksKardiovakuler_3']) && $data['pmrksKardiovakuler_3'] == 'Tingling' ? 'checked' : '' }} />
                            <span style="font-size: 9pt;" color="#000000">Tingling</span>
                        </td>
                    </tr>
                </table>
                <table width="100%">
                    <tr>
                        <td width="25%";>
                            <input type="checkbox"
                                {{ isset($data['pmrksKardiovakuler_4']) && $data['pmrksKardiovakuler_4'] == 'Edema' ? 'checked' : '' }} />
                            <span style="font-size: 9pt;" color="#000000">Edema</span>
                        </td>
                        <td width="25%";>
                            <input type="checkbox"
                                {{ isset($data['pmrksKardiovakuler_5']) && $data['pmrksKardiovakuler_5'] == 'Denyut nadi lemah' ? 'checked' : '' }} />
                            <span style="font-size: 9pt;" color="#000000">Denyut non lemah</span>
                        </td>
                        <td width="25%";>
                            <input type="checkbox"
                                {{ isset($data['pmrksKardiovakuler_6']) && $data['pmrksKardiovakuler_6'] == 'Bardikardi' ? 'checked' : '' }} />
                            <span style="font-size: 9pt;" color="#000000">Brakardi</span>
                        </td>
                        <td width="25%";>
                            <input type="checkbox"
                                {{ isset($data['pmrksKardiovakuler_7']) && $data['pmrksKardiovakuler_7'] == 'Murmur' ? 'checked' : '' }} />
                            <span style="font-size: 9pt;" color="#000000">Murmur</span>
                        </td>
                    </tr>
                </table>
                <table width="100%">
                    <tr>
                        <td width="25%";>
                            <input type="checkbox"
                                {{ isset($data['pmrksKardiovakuler_8']) && $data['pmrksKardiovakuler_8'] == 'Baal' ? 'checked' : '' }} />
                            <span style="font-size: 9pt;" color="#000000">Baal</span>
                        </td>
                        <td width="25%";>
                            <input type="checkbox"
                                {{ isset($data['pmrksKardiovakuler_9']) && $data['pmrksKardiovakuler_9'] == 'Fatique' ? 'checked' : '' }} />
                            <span style="font-size: 9pt;" color="#000000">Fatique</span>
                        </td>
                        <td width="25%";>
                            <input type="checkbox"
                                {{ isset($data['pmrksKardiovakuler_10']) && $data['pmrksKardiovakuler_10'] == 'Denyut nadi tidak ada' ? 'checked' : '' }} />
                            <span style="font-size: 9pt;" color="#000000">Denyut nadi tidak ada</span>
                        </td>
                        <td width="25%";>
                            <span style="font-size: 9pt;" color="#000000"></span>
                        </td>
                    </tr>
                </table>
                <table width="100%">
                    <tr>
                        <td width="100%";>
                            <span style="font-size: 9pt;" color="#000000">Lainya :
                                {{ isset($data['pmrksKardiovakulerLainnya']) ? $data['pmrksKardiovakulerLainnya'] : '-' }}
                            </span>
                        </td>

                    </tr>

                </table>
                <table width="100%">
                    <tr>
                        <td width="100%";>
                            <span style="font-size: 9pt;" color="#000000">Catatan :
                                {{ isset($data['catatanPemrksKardiovakuler']) ? $data['catatanPemrksKardiovakuler'] : '-' }}
                            </span>
                        </td>
                    </tr>

                </table>

            </td>
        </tr>



        <tr>
        <tr>
            <td colspan="7" style="padding:4px 0px;">
                <span style="font-size: 10pt;font-weight: bold;" color="#000000">Pemeriksaan neurologi
                    (orientasi, verbal, kekuatan)</span>
            </td>
        </tr>

        <tr>
            <table width="100%">
                <tr>
                    <td width="25%";>
                        <input type="checkbox"
                            {{ isset($data['pmrksNeurologi_0']) && $data['pmrksNeurologi_0'] == 'Dalam Sedasi' ? 'checked' : '' }} />
                        <span style="font-size: 9pt;" color="#000000">Dalam Sedasi</span>
                    </td>
                    <td width="25%";>
                        <input type="checkbox"
                            {{ isset($data['pmrksNeurologi_1']) && $data['pmrksNeurologi_1'] == 'Normal' ? 'checked' : '' }} />
                        <span style="font-size: 9pt;" color="#000000">Normal</span>
                    </td>
                    <td width="25%";>
                        <input type="checkbox"
                            {{ isset($data['pmrksNeurologi_2']) && $data['pmrksNeurologi_2'] == 'Vertigo' ? 'checked' : '' }} />
                        <span style="font-size: 9pt;" color="#000000">Vertigo</span>
                    </td>
                    <td width="25%";>
                        <input type="checkbox"
                            {{ isset($data['pmrksNeurologi_3']) && $data['pmrksNeurologi_3'] == 'Afasia' ? 'checked' : '' }} />
                        <span style="font-size: 9pt;" color="#000000">Afasia</span>
                    </td>
                </tr>
            </table>
            <table width="100%">
                <tr>
                    <td width="25%";>
                        <input type="checkbox"
                            {{ isset($data['pmrksNeurologi_4']) && $data['pmrksNeurologi_4'] == 'Tremor' ? 'checked' : '' }} />
                        <span style="font-size: 9pt;" color="#000000">Tremor</span>
                    </td>
                    <td width="25%";>
                        <input type="checkbox"
                            {{ isset($data['pmrksNeurologi_5']) && $data['pmrksNeurologi_5'] == 'Baal' ? 'checked' : '' }} />
                        <span style="font-size: 9pt;" color="#000000">Baal</span>
                    </td>
                    <td width="25%";>
                        <input type="checkbox"
                            {{ isset($data['pmrksNeurologi_6']) && $data['pmrksNeurologi_6'] == 'Tidak Stabil' ? 'checked' : '' }} />
                        <span style="font-size: 9pt;" color="#000000">Tidak Stabil</span>
                    </td>
                    <td width="25%";>
                        <input type="checkbox"
                            {{ isset($data['pmrksNeurologi_7']) && $data['pmrksNeurologi_7'] == 'Letargi' ? 'checked' : '' }} />
                        <span style="font-size: 9pt;" color="#000000">Letargi</span>
                    </td>
                </tr>
            </table>
            <table width="100%">
                <tr>
                    <td width="25%";>
                        <input type="checkbox"
                            {{ isset($data['pmrksNeurologi_8']) && $data['pmrksNeurologi_8'] == 'Sakit Kepala' ? 'checked' : '' }} />
                        <span style="font-size: 9pt;" color="#000000">Sakit Kepala</span>
                    </td>
                    <td width="25%";>
                        <input type="checkbox"
                            {{ isset($data['pmrksNeurologi_9']) && $data['pmrksNeurologi_9'] == 'Bicara tidak jelas' ? 'checked' : '' }} />
                        <span style="font-size: 9pt;" color="#000000">Bicara Tidak Jelas</span>
                    </td>
                    <td width="25%";>
                        <input type="checkbox"
                            {{ isset($data['pmrksNeurologi_10']) && $data['pmrksNeurologi_10'] == 'Semi koma' ? 'checked' : '' }} />
                        <span style="font-size: 9pt;" color="#000000">Semi Koma</span>
                    </td>
                    <td width="25%";>
                        <input type="checkbox"
                            {{ isset($data['pmrksNeurologi_11']) && $data['pmrksNeurologi_11'] == 'Paralisis' ? 'checked' : '' }} />
                        <span style="font-size: 9pt;" color="#000000">Paralisis</span>
                    </td>
                </tr>
            </table>
            <table width="100%">
                <tr>
                    <td width="25%";>
                        <input type="checkbox"
                            {{ isset($data['pmrksNeurologi_12']) && $data['pmrksNeurologi_12'] == 'Pupil tidak efektif' ? 'checked' : '' }} />
                        <span style="font-size: 9pt;" color="#000000">Pupil Tidak Efektif</span>
                    </td>
                    <td width="25%";>
                        <input type="checkbox"
                            {{ isset($data['pmrksNeurologi_13']) && $data['pmrksNeurologi_13'] == 'Kejang' ? 'checked' : '' }} />
                        <span style="font-size: 9pt;" color="#000000">Kejang</span>
                    </td>
                    <td width="25%";>
                        <input type="checkbox"
                            {{ isset($data['pmrksNeurologi_14']) && $data['pmrksNeurologi_14'] == 'Tingling' ? 'checked' : '' }} />
                        <span style="font-size: 9pt;" color="#000000">Tingling</span>
                    </td>
                    <td width="25%";>
                        <input type="checkbox"
                            {{ isset($data['pmrksNeurologi_15']) && $data['pmrksNeurologi_15'] == 'Genggaman lemah' ? 'checked' : '' }} />
                        <span style="font-size: 9pt;" color="#000000">Genggaman Lemah</span>
                    </td>
                </tr>
            </table>
        </tr>
        <tr>
            <td width="100%">
                <table width="100%">
                    <tr>
                        <td width="100%";>
                            <span style="font-size: 9pt;" color="#000000">Lainnya :
                                {{ isset($data['pmrksNeurologiLainnya']) ? $data['pmrksNeurologiLainnya'] : '' }}
                            </span>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>

        <tr>
            <td width="100%">
                <table width="100%">
                    <tr>
                        <td width="100%";>
                            <span style="font-size: 9pt;" color="#000000">Catatan :
                                {{ isset($data['catatanPemrksNeurologi']) ? $data['catatanPemrksNeurologi'] : '' }}
                            </span>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>

        </tr>
        <tr>
        <tr>
            <td colspan="7" style="padding:4px 0px;">
                <span style="font-size: 10pt;font-weight: bold;" color="#000000">Pemeriksaan gastrointestinal
                </span>
            </td>
        </tr>

        <tr>
            <table width="100%">
                <tr>
                    <td width="25%";>
                        <input type="checkbox"
                            {{ isset($data['pmrksGastrointestinal_0']) && $data['pmrksGastrointestinal_0'] == 'Normal' ? 'checked' : '' }} />
                        <span style="font-size: 9pt;" color="#000000">Normal</span>
                    </td>
                    <td width="25%";>
                        <input type="checkbox"
                            {{ isset($data['pmrksGastrointestinal_1']) && $data['pmrksGastrointestinal_1'] == 'Distensi' ? 'checked' : '' }} />
                        <span style="font-size: 9pt;" color="#000000">Distensi</span>
                    </td>
                    <td width="25%";>
                        <input type="checkbox"
                            {{ isset($data['pmrksGastrointestinal_2']) && $data['pmrksGastrointestinal_2'] == 'Bising usus menurun' ? 'checked' : '' }} />
                        <span style="font-size: 9pt;" color="#000000">Bising usus menurun</span>
                    </td>
                    <td width="25%";>
                        <input type="checkbox"
                            {{ isset($data['pmrksGastrointestinal_3']) && $data['pmrksGastrointestinal_3'] == 'Anoreksia' ? 'checked' : '' }} />
                        <span style="font-size: 9pt;" color="#000000">Anoreksia</span>
                    </td>
                </tr>
            </table>
            <table width="100%">
                <tr>
                    <td width="25%";>
                        <input type="checkbox"
                            {{ isset($data['pmrksGastrointestinal_4']) && $data['pmrksGastrointestinal_4'] == 'Disfagia' ? 'checked' : '' }} />
                        <span style="font-size: 9pt;" color="#000000">Disfagia</span>
                    </td>
                    <td width="25%";>
                        <input type="checkbox"
                            {{ isset($data['pmrksGastrointestinal_5']) && $data['pmrksGastrointestinal_5'] == 'Diare' ? 'checked' : '' }} />
                        <span style="font-size: 9pt;" color="#000000">Diare</span>
                    </td>
                    <td width="25%";>
                        <input type="checkbox"
                            {{ isset($data['pmrksGastrointestinal_6']) && $data['pmrksGastrointestinal_6'] == 'Klisma spuit gliserin' ? 'checked' : '' }} />
                        <span style="font-size: 9pt;" color="#000000">Klisma spuit gliserin</span>
                    </td>
                    <td width="25%";>
                        <input type="checkbox"
                            {{ isset($data['pmrksGastrointestinal_7']) && $data['pmrksGastrointestinal_7'] == 'Intolernasi diet' ? 'checked' : '' }} />
                        <span style="font-size: 9pt;" color="#000000">Intoleransi diet</span>
                    </td>
                </tr>
            </table>
            <table width="100%">
                <tr>
                    <td width="25%";>
                        <input type="checkbox"
                            {{ isset($data['pmrksGastrointestinal_8']) && $data['pmrksGastrointestinal_8'] == 'Bising usus meningkat' ? 'checked' : '' }} />
                        <span style="font-size: 9pt;" color="#000000">Bising usus meningkat </span>
                    </td>
                    <td width="25%";>
                        <input type="checkbox"
                            {{ isset($data['pmrksGastrointestinal_9']) && $data['pmrksGastrointestinal_9'] == 'Tegang/Keras' ? 'checked' : '' }} />
                        <span style="font-size: 9pt;" color="#000000">Tegang/keras</span>
                    </td>
                    <td width="25%";>
                        <input type="checkbox"
                            {{ isset($data['pmrksGastrointestinal_10']) && $data['pmrksGastrointestinal_10'] == 'Konstipasi' ? 'checked' : '' }} />
                        <span style="font-size: 9pt;" color="#000000">Konstipasi</span>
                    </td>
                    <td width="25%";>
                        <input type="checkbox"
                            {{ isset($data['pmrksGastrointestinal_11']) && $data['pmrksGastrointestinal_11'] == 'BAB Terakhir' ? 'checked' : '' }} />
                        <span style="font-size: 9pt;" color="#000000">BAB terakhir</span>
                    </td>
                </tr>
            </table>
            <table width="100%">
                <tr>
                    <td width="25%";>
                        <input type="checkbox"
                            {{ isset($data['pmrksGastrointestinal_12']) && $data['pmrksGastrointestinal_12'] == 'Diet Khusus' ? 'checked' : '' }} />
                        <span style="font-size: 9pt;" color="#000000">Diet khusus</span>
                    </td>
                    <td width="25%";>
                        <!-- <input type="checkbox" ng-model="item.obj[3110013]" /> <span style="font-size: 9pt;" color="#000000" >Kejang</span> -->
                    </td>
                    <td width="25%";>
                        <!-- <input type="checkbox" ng-model="item.obj[3110013]" /> <span style="font-size: 9pt;" color="#000000" >Tingling</span> -->
                    </td>
                    <td width="25%";>
                        <!-- <input type="checkbox" ng-model="item.obj[3110013]" /> <span style="font-size: 9pt;" color="#000000" >Gengam Lemah</span> -->
                    </td>
                </tr>
            </table>
        </tr>
        <tr>
            <td width="100%">
                <table width="100%">
                    <tr>
                        <td width="100%";>
                            <span style="font-size: 9pt;" color="#000000">Lainnya :
                                {{ isset($data['pmrksGastrointestinalLain']) ? $data['pmrksGastrointestinalLain'] : '' }}
                            </span>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td width="100%">
                <table width="100%">
                    <tr>
                        <td width="100%";>
                            <span style="font-size: 9pt;" color="#000000">Catatan :
                                {{ isset($data['catatanPemrksGastrointestinal']) ? $data['catatanPemrksGastrointestinal'] : '' }}
                            </span>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        </tr>
        <tr>
        <tr>
            <td colspan="7" style="padding:4px 0px;">
                <span style="font-size: 10pt;font-weight: bold;" color="#000000">Pemeriksaan genitourinaria dan
                    ginekologi</span>
            </td>
        </tr>

        <tr>
            <table width="100%">
                <tr>
                    <td width="25%";>
                        <input type="checkbox"
                            {{ isset($data['pmrksGinekologi_0']) && $data['pmrksGinekologi_0'] == 'Normal' ? 'checked' : '' }} />
                        <span style="font-size: 9pt;" color="#000000">Normal</span>
                    </td>
                    <td width="25%";>
                        <input type="checkbox"
                            {{ isset($data['pmrksGinekologi_1']) && $data['pmrksGinekologi_1'] == 'Folley cateter' ? 'checked' : '' }} />
                        <span style="font-size: 9pt;" color="#000000">Folley cateter</span>
                    </td>
                    <td width="25%";>
                        <input type="checkbox"
                            {{ isset($data['pmrksGinekologi_2']) && $data['pmrksGinekologi_2'] == 'Hesitansi' ? 'checked' : '' }} />
                        <span style="font-size: 9pt;" color="#000000">Hesitansi</span>
                    </td>
                    <td width="25%";>
                        <input type="checkbox"
                            {{ isset($data['pmrksGinekologi_3']) && $data['pmrksGinekologi_3'] == 'Hematuria' ? 'checked' : '' }} />
                        <span style="font-size: 9pt;" color="#000000">Hematuria</span>
                    </td>
                </tr>
            </table>
            <table width="100%">
                <tr>
                    <td width="25%";>
                        <input type="checkbox"
                            {{ isset($data['pmrksGinekologi_4']) && $data['pmrksGinekologi_4'] == 'Menopause' ? 'checked' : '' }} />
                        <span style="font-size: 9pt;" color="#000000">Menopause</span>
                    </td>
                    <td width="25%";>
                        <input type="checkbox"
                            {{ isset($data['pmrksGinekologi_5']) && $data['pmrksGinekologi_5'] == 'Sekret abnormal' ? 'checked' : '' }} />
                        <span style="font-size: 9pt;" color="#000000">Sekret abnormal</span>
                    </td>
                    <td width="25%";>
                        <input type="checkbox"
                            {{ isset($data['pmrksGinekologi_6']) && $data['pmrksGinekologi_6'] == 'Frekuensi' ? 'checked' : '' }} />
                        <span style="font-size: 9pt;" color="#000000">Frekuensi</span>
                    </td>
                    <td width="25%";>
                        <input type="checkbox"
                            {{ isset($data['pmrksGinekologi_7']) && $data['pmrksGinekologi_7'] == 'Urostomi' ? 'checked' : '' }} />
                        <span style="font-size: 9pt;" color="#000000">Urostomi</span>
                    </td>
                </tr>
            </table>
            <table width="100%">
                <tr>
                    <td width="25%";>
                        <input type="checkbox"
                            {{ isset($data['pmrksGinekologi_8']) && $data['pmrksGinekologi_8'] == 'Inkontinensia' ? 'checked' : '' }} />
                        <span style="font-size: 9pt;" color="#000000">Inkontinensia</span>
                    </td>
                    <td width="25%";>
                        <input type="checkbox"
                            {{ isset($data['pmrksGinekologi_9']) && $data['pmrksGinekologi_9'] == 'Nokturia' ? 'checked' : '' }} />
                        <span style="font-size: 9pt;" color="#000000">Nokturia</span>
                    </td>
                    <td width="25%";>
                        <input type="checkbox"
                            {{ isset($data['pmrksGinekologi_10']) && $data['pmrksGinekologi_10'] == 'Disuria' ? 'checked' : '' }} />
                        <span style="font-size: 9pt;" color="#000000">Disuria</span>
                    </td>
                    <td width="25%";>
                        <input type="checkbox"
                            {{ isset($data['pmrksGinekologi_11']) && $data['pmrksGinekologi_11'] == 'Hamil' ? 'checked' : '' }} />
                        <span style="font-size: 9pt;" color="#000000">Hamil</span>
                    </td>
                </tr>
            </table>
        </tr>
        <tr>
            <td width="100%">
                <table width="100%">
                    <tr>
                        <td width="100%";>
                            <span style="font-size: 9pt;" color="#000000">Menstruasi Terakhir :
                                {{ isset($data['pmrksGinekologi_11']) ? $data['pmrksGinekologi_11'] : '-' }}
                            </span>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td width="100%">
                <table width="100%">
                    <tr>
                        <td width="100%";>
                            <span style="font-size: 9pt;" color="#000000">Catatan :
                                {{ isset($data['catatanPemrksGinekologi']) ? $data['catatanPemrksGinekologi'] : '-' }}
                            </span>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        </tr>
        <tr>

        <tr>
        <tr>
            <td colspan="7" style="padding:4px 0px;">
                <span style="font-size: 10pt;font-weight: bold;" color="#000000">Pemeriksaan muskuloskeletal dan
                    kulit (mobilitas, fungsi sendir, warna kulit, turgor)</span>
            </td>
        </tr>
        <tr>
            <table width="100%">
                <td colspan="7" style="padding:4px 0px;">
                    <span style="font-size: 10pt;font-weight: bold;" color="#000000">Mobilitas</span>
                </td>
                <table width="100%">
        </tr>

        <tr>

            <table width="100%">
                <tr>
                    <td width="25%";>
                        <input type="checkbox"
                            {{ isset($data['muskolektalMobilitas']) && $data['muskolektalMobilitas'] == 'Normal' ? 'checked' : '' }} />
                        <span style="font-size: 9pt;" color="#000000">Normal</span>
                    </td>
                    <td width="25%";>
                        <input type="checkbox"
                            {{ isset($data['muskolektalMobilitas']) && $data['muskolektalMobilitas'] == 'Dibantu' ? 'checked' : '' }} />
                        <span style="font-size: 9pt;" color="#000000">Dibantu</span>
                    </td>
                    <td width="25%";>

                    </td>
                    <td width="25%";>

                    </td>
                </tr>
            </table>
        </tr>
        </tr>
        <tr>
        <tr>
            <td colspan="7" style="padding:4px 0px;">
                <span style="font-size: 10pt;font-weight: bold;" color="#000000">Fungsi sendri</span>
            </td>
        </tr>

        <tr>

            <table width="100%">
                <tr>
                    <td width="25%";>
                        <input type="checkbox"
                            {{ isset($data['fungsiSendiri']) && $data['fungsiSendiri'] == 'Normal' ? 'checked' : '' }} />
                        <span style="font-size: 9pt;" color="#000000">Normal</span>
                    </td>
                    <td width="25%";>
                        <input type="checkbox"
                            {{ isset($data['fungsiSendiri']) && $data['fungsiSendiri'] == 'Deformitas/atrofi' ? 'checked' : '' }} />
                        <span style="font-size: 9pt;" color="#000000">Deformitas/atrofi</span>
                    </td>
                    <td width="25%";>

                    </td>
                    <td width="25%";>

                    </td>
                </tr>
            </table>
        </tr>
        <tr>
            <td colspan="7" style="padding:4px 0px;">
                <span style="font-size: 10pt;font-weight: bold;" color="#000000">Ekstremitas</span>
            </td>
        </tr>

        <tr>

            <table width="100%">
                <tr>
                    <td width="25%";>
                        <input type="checkbox"
                            {{ isset($data['extremitas']) && $data['extremitas'] == 'Normal' ? 'checked' : '' }} />
                        <span style="font-size: 9pt;" color="#000000">Normal</span>
                    </td>
                    <td width="25%";>
                        <input type="checkbox"
                            {{ isset($data['extremitas']) && $data['extremitas'] == 'Oedema' ? 'checked' : '' }} />
                        <span style="font-size: 9pt;" color="#000000">Oedema</span>
                    </td>
                    <td width="25%";>

                    </td>
                    <td width="25%";>

                    </td>
                </tr>
            </table>
        </tr>
        </tr>
        <tr>
        <tr>
            <td colspan="7" style="padding:4px 0px;">
                <span style="font-size: 10pt;font-weight: bold;" color="#000000">Warna Kulit</span>
            </td>
        </tr>

        <tr>

            <table width="100%">
                <tr>
                    <td width="25%";>
                        <input type="checkbox"
                            {{ isset($data['warnaKulit']) && $data['warnaKulit'] == 'Normal' ? 'checked' : '' }} />
                        <span style="font-size: 9pt;" color="#000000">Normal</span>
                    </td>
                    <td width="25%";>
                        <input type="checkbox"
                            {{ isset($data['warnaKulit']) && $data['warnaKulit'] == 'Pucat' ? 'checked' : '' }} />
                        <span style="font-size: 9pt;" color="#000000">Pucat</span>
                    </td>
                    <td width="25%";>
                        <input type="checkbox"
                            {{ isset($data['warnaKulit']) && $data['warnaKulit'] == 'Kemerahan' ? 'checked' : '' }} />
                        <span style="font-size: 9pt;" color="#000000">Kemerahan</span>
                    </td>
                    <td width="25%";>

                    </td>

                </tr>
            </table>
        </tr>
        </tr>
        <tr>
        <tr>
            <td colspan="7" style="padding:4px 0px;">
                <span style="font-size: 10pt;font-weight: bold;" color="#000000">Turgor</span>
            </td>
        </tr>

        <tr>

            <table width="100%">
                <tr>
                    <td width="25%";>
                        <input type="checkbox"
                            {{ isset($data['turgar']) && $data['turgar'] == 'Normal' ? 'checked' : '' }} />
                        <span style="font-size: 9pt;" color="#000000">Normal</span>
                    </td>
                    <td width="25%";>
                        <input type="checkbox"
                            {{ isset($data['turgar']) && $data['turgar'] == '> 2 Detik' ? 'checked' : '' }} />
                        <span style="font-size: 9pt;" color="#000000"> >2 Detik</span>
                    </td>
                    <td width="25%";>

                    </td>
                    <td width="25%";>

                    </td>
                </tr>
            </table>
        </tr>
        </tr>
        <tr>
        <tr>
            <td colspan="7" style="padding:4px 0px;">
                <span style="font-size: 10pt;font-weight: bold;" color="#000000">Permukaan Kulit</span>
            </td>
        </tr>

        <tr>

            <table width="100%">
                <tr>
                    <td width="20%";>
                        <input type="checkbox"
                            {{ isset($data['permukaanKulit']) && $data['permukaanKulit'] == 'Normal' ? 'checked' : '' }} />
                        <span style="font-size: 9pt;" color="#000000">Normal</span>
                    </td>
                    <td width="20%";>
                        <input type="checkbox"
                            {{ isset($data['permukaanKulit']) && $data['permukaanKulit'] == 'Lembab' ? 'checked' : '' }} />
                        <span style="font-size: 9pt;" color="#000000"> Lembab</span>
                    </td>
                    <td width="20%";>
                        <input type="checkbox"
                            {{ isset($data['permukaanKulit']) && $data['permukaanKulit'] == 'Dingin' ? 'checked' : '' }} />
                        <span style="font-size: 9pt;" color="#000000"> Dingin</span>
                    </td>
                    <td width="20%";>
                        <input type="checkbox"
                            {{ isset($data['permukaanKulit']) && $data['permukaanKulit'] == 'Panas' ? 'checked' : '' }} />
                        <span style="font-size: 9pt;" color="#000000">Panas</span>
                    </td>
                    <td width="20%";>
                        <input type="checkbox"
                            {{ isset($data['permukaanKulit']) && $data['permukaanKulit'] == 'Kering' ? 'checked' : '' }} />
                        <span style="font-size: 9pt;" color="#000000">Kering</span>
                    </td>
                </tr>
            </table>
        </tr>
        </tr>
        <tr>
        <tr>
            <td colspan="7" style="padding:4px 0px;">
                <span style="font-size: 10pt;font-weight: bold;" color="#000000">Kondisi Luka</span>
            </td>
        </tr>

        <tr>

            <table width="100%">
                <tr>
                    <td width="20%";>
                        <input type="checkbox"
                            {{ isset($data['kondisiKulit']) && $data['kondisiKulit'] == 'Ada' ? 'checked' : '' }} />
                        <span style="font-size: 9pt;" color="#000000">Ada</span>
                    </td>
                    <td width="20%";>
                        <input type="checkbox"
                            {{ isset($data['kondisiKulit']) && $data['kondisiKulit'] == 'Luka bersih' ? 'checked' : '' }} />
                        <span style="font-size: 9pt;" color="#000000">Luka bersih</span>
                    </td>
                    <td width="20%";>
                        <input type="checkbox"
                            {{ isset($data['kondisiKulit']) && $data['kondisiKulit'] == 'Luka Kotor' ? 'checked' : '' }} />
                        <span style="font-size: 9pt;" color="#000000">Luka Kotor</span>
                    </td>
                    <td width="20%";>
                        <input type="checkbox"
                            {{ isset($data['kondisiKulit']) && $data['kondisiKulit'] == 'Jahitan Luka' ? 'checked' : '' }} />
                        <span style="font-size: 9pt;" color="#000000">Jahitan Luka</span>
                    </td>
                    <td width="20%";>
                        <input type="checkbox"
                            {{ isset($data['kondisiKulit']) && $data['kondisiKulit'] == 'Balutan Utuh' ? 'checked' : '' }} />
                        <span style="font-size: 9pt;" color="#000000">Balutan utuh</span>
                    </td>
                </tr>
                <tr>
                    <td width="20%";>
                        <input type="checkbox"
                            {{ isset($data['kondisiKulit']) && $data['kondisiKulit'] == 'Tidak ada' ? 'checked' : '' }} />
                        <span style="font-size: 9pt;" color="#000000">Tidak ada</span>
                    </td>
                    <td width="20%";>
                        <input type="checkbox"
                            {{ isset($data['kondisiKulit']) && $data['kondisiKulit'] == 'Ganti balutan' ? 'checked' : '' }} />
                        <span style="font-size: 9pt;" color="#000000">Ganti balutan</span>
                    </td>
                    <td width="20%";>

                    </td>
                    <td width="20%";>

                    </td>
                    <td width="20%";>

                    </td>
                </tr>
            </table>
        </tr>
        </tr>
        <tr>
        <tr>
            <td colspan="7" style="padding:4px 0px;">
                <span style="font-size: 10pt;font-weight: bold;" color="#000000">Pemeriksaan Norton Scale
                    (Resiko Kulit)</span>
            </td>
        </tr>
        <tr>
        <tr>
            <td width="100%" colspan="4">
                <table width="100%">
                    <tr>
                        <td width="10%";>
                            <span style="font-size: 9pt;" color="#000000">Nomor</span>
                        </td>
                        <td width="40%";>
                            <span style="font-size: 9pt;" color="#000000">Parameter</span>
                        </td>
                        <td width="40%";>
                            <span style="font-size: 9pt;" color="#000000">Pengkajian</span>
                        </td>
                        <td width="8%";>
                            <span style="font-size: 9pt;" color="#000000">Nilai</span>
                        </td>
                    </tr>
                </table>
                <table width="100%">
                    <tr>
                        <td width="10%";>
                            <span style="font-size: 9pt;" color="#000000">1</span>
                        </td>
                        <td width="40%";>
                            <span style="font-size: 9pt;" color="#000000">Kondisi Fisik</span>
                        </td>
                        <td width="40%";>
                            <input type="checkbox"
                                {{ isset($data['kondisiFisik']) && $data['kondisiFisik']['keterangan'] == 'Baik' ? 'checked' : '' }} />
                            <span style="font-size: 9pt;" color="#000000">Baik</span>
                        </td>
                        <td width="8%";>
                            <span style="font-size: 9pt;" color="#000000">4</span>
                        </td>
                    </tr>
                </table>
                <table width="100%">
                    <tr>
                        <td width="10%";>
                            <span style="font-size: 9pt;" color="#000000"></span>
                        </td>
                        <td width="40%";>
                            <span style="font-size: 9pt;" color="#000000"></span>
                        </td>
                        <td width="40%";>
                            <input type="checkbox"
                                {{ isset($data['kondisiFisik']) && $data['kondisiFisik']['keterangan'] == 'cukup' ? 'checked' : '' }} />
                            <span style="font-size: 9pt;" color="#000000">Cukup</span>
                        </td>
                        <td width="8%";>
                            <span style="font-size: 9pt;" color="#000000">3</span>
                        </td>
                    </tr>
                </table>
                <table width="100%">
                    <tr>
                        <td width="10%";>
                            <span style="font-size: 9pt;" color="#000000"></span>
                        </td>
                        <td width="40%";>
                            <span style="font-size: 9pt;" color="#000000"></span>
                        </td>
                        <td width="40%";>
                            <input type="checkbox"
                                {{ isset($data['kondisiFisik']) && $data['kondisiFisik']['keterangan'] == 'Buruk' ? 'checked' : '' }} />
                            <span style="font-size: 9pt;" color="#000000">Buruk</span>
                        </td>
                        <td width="8%";>
                            <span style="font-size: 9pt;" color="#000000">2</span>
                        </td>
                    </tr>
                </table>
                <table width="100%">
                    <tr>
                        <td width="10%";>
                            <span style="font-size: 9pt;" color="#000000"></span>
                        </td>
                        <td width="40%";>
                            <span style="font-size: 9pt;" color="#000000"></span>
                        </td>
                        <td width="40%";>
                            <input type="checkbox"
                                {{ isset($data['kondisiFisik']) && $data['kondisiFisik']['keterangan'] == 'Sangat Buruk' ? 'checked' : '' }} />
                            <span style="font-size: 9pt;" color="#000000">Sangat Buruk</span>
                        </td>
                        <td width="8%";>
                            <span style="font-size: 9pt;" color="#000000">1</span>
                        </td>
                    </tr>
                </table>
                <table width="100%">
                    <tr>
                        <td width="10%";>
                            <span style="font-size: 9pt;" color="#000000">2</span>
                        </td>
                        <td width="40%";>
                            <span style="font-size: 9pt;" color="#000000">Kondisi Mental</span>
                        </td>
                        <td width="40%";>
                            <input type="checkbox"
                                {{ isset($data['kondisiMental']) && $data['kondisiMental']['keterangan'] == 'Kompos Mentis' ? 'checked' : '' }} />
                            <span style="font-size: 9pt;" color="#000000">Kompos Mentis</span>
                        </td>
                        <td width="8%";>
                            <span style="font-size: 9pt;" color="#000000">4</span>
                        </td>
                    </tr>
                </table>
                <table width="100%">
                    <tr>
                        <td width="10%";>
                            <span style="font-size: 9pt;" color="#000000"></span>
                        </td>
                        <td width="40%";>
                            <span style="font-size: 9pt;" color="#000000"></span>
                        </td>
                        <td width="40%";>
                            <input type="checkbox"
                                {{ isset($data['kondisiMental']) && $data['kondisiMental']['keterangan'] == 'Apatis' ? 'checked' : '' }} />
                            <span style="font-size: 9pt;" color="#000000">Apatis</span>
                        </td>
                        <td width="8%";>
                            <span style="font-size: 9pt;" color="#000000">3</span>
                        </td>
                    </tr>
                </table>
                <table width="100%">
                    <tr>
                        <td width="10%";>
                            <span style="font-size: 9pt;" color="#000000"></span>
                        </td>
                        <td width="40%";>
                            <span style="font-size: 9pt;" color="#000000"></span>
                        </td>
                        <td width="40%";>
                            <input type="checkbox"
                                {{ isset($data['kondisiMental']) && $data['kondisiMental']['keterangan'] == 'Delirium' ? 'checked' : '' }} />
                            <span style="font-size: 9pt;" color="#000000">Delirium</span>
                        </td>
                        <td width="8%";>
                            <span style="font-size: 9pt;" color="#000000">2</span>
                        </td>
                    </tr>
                </table>
                <table width="100%">
                    <tr>
                        <td width="10%";>
                            <span style="font-size: 9pt;" color="#000000"></span>
                        </td>
                        <td width="40%";>
                            <span style="font-size: 9pt;" color="#000000"></span>
                        </td>
                        <td width="40%";>
                            <input type="checkbox"
                                {{ isset($data['kondisiMental']) && $data['kondisiMental']['keterangan'] == 'Stupor' ? 'checked' : '' }} />
                            <span style="font-size: 9pt;" color="#000000">Stupor</span>
                        </td>
                        <td width="8%";>
                            <span style="font-size: 9pt;" color="#000000">1</span>
                        </td>
                    </tr>
                </table>
                <table width="100%">
                    <tr>
                        <td width="10%";>
                            <span style="font-size: 9pt;" color="#000000">3</span>
                        </td>
                        <td width="40%";>
                            <span style="font-size: 9pt;" color="#000000">Aktifitas</span>
                        </td>
                        <td width="40%";>
                            <input type="checkbox"
                                {{ isset($data['aktifitas']) && $data['aktifitas']['keterangan'] == 'Mandiri' ? 'checked' : '' }} />
                            <span style="font-size: 9pt;" color="#000000">Mandiri</span>
                        </td>
                        <td width="8%";>
                            <span style="font-size: 9pt;" color="#000000">4</span>
                        </td>
                    </tr>
                </table>
                <table width="100%">
                    <tr>
                        <td width="10%";>
                            <span style="font-size: 9pt;" color="#000000"></span>
                        </td>
                        <td width="40%";>
                            <span style="font-size: 9pt;" color="#000000"></span>
                        </td>
                        <td width="40%";>
                            <input type="checkbox"
                                {{ isset($data['aktifitas']) && $data['aktifitas']['keterangan'] == 'Dipapah' ? 'checked' : '' }} />
                            <span style="font-size: 9pt;" color="#000000">Dipapah</span>
                        </td>
                        <td width="8%";>
                            <span style="font-size: 9pt;" color="#000000">3</span>
                        </td>
                    </tr>
                </table>
                <table width="100%">
                    <tr>
                        <td width="10%";>
                            <span style="font-size: 9pt;" color="#000000"></span>
                        </td>
                        <td width="40%";>
                            <span style="font-size: 9pt;" color="#000000"></span>
                        </td>
                        <td width="40%";>
                            <input type="checkbox"
                                {{ isset($data['aktifitas']) && $data['aktifitas']['keterangan'] == 'Kursi Roda' ? 'checked' : '' }} />
                            <span style="font-size: 9pt;" color="#000000">Kursi Roda</span>
                        </td>
                        <td width="8%";>
                            <span style="font-size: 9pt;" color="#000000">2</span>
                        </td>
                    </tr>
                </table>
                <table width="100%">
                    <tr>
                        <td width="10%";>
                            <span style="font-size: 9pt;" color="#000000"></span>
                        </td>
                        <td width="40%";>
                            <span style="font-size: 9pt;" color="#000000"></span>
                        </td>
                        <td width="40%";>
                            <input type="checkbox"
                                {{ isset($data['aktifitas']) && $data['aktifitas']['keterangan'] == 'Tirah Baring' ? 'checked' : '' }} />
                            <span style="font-size: 9pt;" color="#000000">Tirah Baring</span>
                        </td>
                        <td width="8%";>
                            <span style="font-size: 9pt;" color="#000000">1</span>
                        </td>
                    </tr>
                </table>
                <table width="100%">
                    <tr>
                        <td width="10%";>
                            <span style="font-size: 9pt;" color="#000000">4</span>
                        </td>
                        <td width="40%";>
                            <span style="font-size: 9pt;" color="#000000">Mobilitas</span>
                        </td>
                        <td width="40%";>
                            <input type="checkbox"
                                {{ isset($data['mobilitas']) && $data['mobilitas']['keterangan'] == 'Baik' ? 'checked' : '' }} />
                            <span style="font-size: 9pt;" color="#000000">Baik</span>
                        </td>
                        <td width="8%";>
                            <span style="font-size: 9pt;" color="#000000">4</span>
                        </td>
                    </tr>
                </table>
                <table width="100%">
                    <tr>
                        <td width="10%";>
                            <span style="font-size: 9pt;" color="#000000"></span>
                        </td>
                        <td width="40%";>
                            <span style="font-size: 9pt;" color="#000000"></span>
                        </td>
                        <td width="40%";>
                            <input type="checkbox"
                                {{ isset($data['mobilitas']) && $data['mobilitas']['keterangan'] == 'Agak Terbatas' ? 'checked' : '' }} />
                            <span style="font-size: 9pt;" color="#000000">Agak Terbatas</span>
                        </td>
                        <td width="8%";>
                            <span style="font-size: 9pt;" color="#000000">3</span>
                        </td>
                    </tr>
                </table>
                <table width="100%">
                    <tr>
                        <td width="10%";>
                            <span style="font-size: 9pt;" color="#000000"></span>
                        </td>
                        <td width="40%";>
                            <span style="font-size: 9pt;" color="#000000"></span>
                        </td>
                        <td width="40%";>
                            <input type="checkbox"
                                {{ isset($data['mobilitas']) && $data['mobilitas']['keterangan'] == 'Sangat Terbatas' ? 'checked' : '' }} />
                            <span style="font-size: 9pt;" color="#000000">Sangat Terbatas</span>
                        </td>
                        <td width="8%";>
                            <span style="font-size: 9pt;" color="#000000">2</span>
                        </td>
                    </tr>
                </table>
                <table width="100%">
                    <tr>
                        <td width="10%";>
                            <span style="font-size: 9pt;" color="#000000"></span>
                        </td>
                        <td width="40%";>
                            <span style="font-size: 9pt;" color="#000000"></span>
                        </td>
                        <td width="40%";>
                            <input type="checkbox"
                                {{ isset($data['mobilitas']) && $data['mobilitas']['keterangan'] == 'Imobilisasi' ? 'checked' : '' }} />
                            <span style="font-size: 9pt;" color="#000000">Imobilisasi</span>
                        </td>
                        <td width="8%";>
                            <span style="font-size: 9pt;" color="#000000">1</span>
                        </td>
                    </tr>
                </table>

                <table width="100%">
                    <tr>
                        <td width="10%";>
                            <span style="font-size: 9pt;" color="#000000">5</span>
                        </td>
                        <td width="40%";>
                            <span style="font-size: 9pt;" color="#000000">Inkkontenesia</span>
                        </td>
                        <td width="40%";>
                            <input type="checkbox"
                                {{ isset($data['inkkonteneasi']) && $data['inkkonteneasi']['keterangan'] == 'Tidak' ? 'checked' : '' }} />
                            <span style="font-size: 9pt;" color="#000000">Tidak</span>
                        </td>
                        <td width="8%";>
                            <span style="font-size: 9pt;" color="#000000">4</span>
                        </td>
                    </tr>
                </table>
                <table width="100%">
                    <tr>
                        <td width="10%";>
                            <span style="font-size: 9pt;" color="#000000"></span>
                        </td>
                        <td width="40%";>
                            <span style="font-size: 9pt;" color="#000000"></span>
                        </td>
                        <td width="40%";>
                            <input type="checkbox"
                                {{ isset($data['inkkonteneasi']) && $data['inkkonteneasi']['keterangan'] == 'Terkadang' ? 'checked' : '' }} />
                            <span style="font-size: 9pt;" color="#000000">Terkadang</span>
                        </td>
                        <td width="8%";>
                            <span style="font-size: 9pt;" color="#000000">3</span>
                        </td>
                    </tr>
                </table>
                <table width="100%">
                    <tr>
                        <td width="10%";>
                            <span style="font-size: 9pt;" color="#000000"></span>
                        </td>
                        <td width="40%";>
                            <span style="font-size: 9pt;" color="#000000"></span>
                        </td>
                        <td width="40%";>
                            <input type="checkbox"
                                {{ isset($data['inkkonteneasi']) && $data['inkkonteneasi']['keterangan'] == 'Sering' ? 'checked' : '' }} />
                            <span style="font-size: 9pt;" color="#000000">Sering</span>
                        </td>
                        <td width="8%";>
                            <span style="font-size: 9pt;" color="#000000">2</span>
                        </td>
                    </tr>
                </table>
                <table width="100%">
                    <tr>
                        <td width="10%";>
                            <span style="font-size: 9pt;" color="#000000"></span>
                        </td>
                        <td width="40%";>
                            <span style="font-size: 9pt;" color="#000000"></span>
                        </td>
                        <td width="40%";>
                            <input type="checkbox"
                                {{ isset($data['inkkonteneasi']) && $data['inkkonteneasi']['keterangan'] == 'Selalu' ? 'checked' : '' }} />
                            <span style="font-size: 9pt;" color="#000000">Selalu</span>
                        </td>
                        <td width="8%";>
                            <span style="font-size: 9pt;" color="#000000">1</span>
                        </td>
                    </tr>
                </table>
                <table width="100%">
                    <tr>
                        <td width="10%";>
                            <span style="font-size: 9pt;;font-weight: bold;" color="#000000">Jumlah</span>
                        </td>
                        <td width="40%";>
                            <span style="font-size: 9pt;" color="#000000"></span>
                        </td>
                        <td width="40%";>
                            <span style="font-size: 9pt;" color="#000000"></span>
                        </td>
                        <td width="8%";>
                            <span style="font-size: 9pt;;font-weight: bold;"
                                color="#000000">{{ isset($data['totalNilaiPmrksNortonScale']) ? $data['totalNilaiPmrksNortonScale'] : '' }}</span>
                        </td>
                    </tr>
                </table>
                <table width="100%">
                    <tr>
                        <td width="100%";>
                            <span style="font-size: 9pt;" color="#000000">Catatan :
                                {{ isset($data['catatanResikoKulit']) ? $data['catatanResikoKulit'] : '-' }}
                            </span>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        </tr>
        <tr>
            <td colspan="7" style="padding:4px 0px;">
                <span style="font-size: 10pt;font-weight: bold;" color="#000000">Pemeriksaan Aktivitas Harian
                    Dasar (ADL)</span>
            </td>
        </tr>
        <tr>
        <tr>
            <td width="100%" colspan="4">
                <table width="100%">
                    <tr>
                        <td width="10%";>
                            <span style="font-size: 9pt;" color="#000000">Nomor</span>
                        </td>
                        <td width="40%";>
                            <span style="font-size: 9pt;" color="#000000">Parameter</span>
                        </td>
                        <td width="40%";>
                            <span style="font-size: 9pt;" color="#000000">Pengkajian</span>
                        </td>
                        <td width="8%";>
                            <span style="font-size: 9pt;" color="#000000">Nilai</span>
                        </td>
                    </tr>
                </table>
                <table width="100%">
                    <tr>
                        <td width="10%";>
                            <span style="font-size: 9pt;" color="#000000">1</span>
                        </td>
                        <td width="40%";>
                            <span style="font-size: 9pt;" color="#000000">Makan/Memakai Baju</span>
                        </td>
                        <td width="40%";>
                            <input type="checkbox"
                                {{ isset($data['makanAtauPakaiBaju']) && $data['makanAtauPakaiBaju']['poin'] == 3 ? 'checked' : '' }} />
                            <span style="font-size: 9pt;" color="#000000">75% dibantu</span>
                        </td>
                        <td width="8%";>
                            <span style="font-size: 9pt;" color="#000000">3</span>
                        </td>
                    </tr>
                </table>
                <table width="100%">
                    <tr>
                        <td width="10%";>
                            <span style="font-size: 9pt;" color="#000000"></span>
                        </td>
                        <td width="40%";>
                            <span style="font-size: 9pt;" color="#000000"></span>
                        </td>
                        <td width="40%";>
                            <input type="checkbox"
                                {{ isset($data['makanAtauPakaiBaju']) && $data['makanAtauPakaiBaju']['poin'] == 2 ? 'checked' : '' }} />
                            <span style="font-size: 9pt;" color="#000000">50% dibantu</span>
                        </td>
                        <td width="8%";>
                            <span style="font-size: 9pt;" color="#000000">2</span>
                        </td>
                    </tr>
                </table>
                <table width="100%">
                    <tr>
                        <td width="10%";>
                            <span style="font-size: 9pt;" color="#000000"></span>
                        </td>
                        <td width="40%";>
                            <span style="font-size: 9pt;" color="#000000"></span>
                        </td>
                        <td width="40%";>
                            <input type="checkbox"
                                {{ isset($data['makanAtauPakaiBaju']) && $data['makanAtauPakaiBaju']['poin'] == 1 ? 'checked' : '' }} />
                            <span style="font-size: 9pt;" color="#000000">25% dibantu</span>
                        </td>
                        <td width="8%";>
                            <span style="font-size: 9pt;" color="#000000">1</span>
                        </td>
                    </tr>
                </table>
                <table width="100%">
                    <tr>
                        <td width="10%";>
                            <span style="font-size: 9pt;" color="#000000"></span>
                        </td>
                        <td width="40%";>
                            <span style="font-size: 9pt;" color="#000000"></span>
                        </td>
                        <td width="40%";>
                            <input type="checkbox"
                                {{ isset($data['makanAtauPakaiBaju']) && $data['makanAtauPakaiBaju']['poin'] == 0 ? 'checked' : '' }} />
                            <span style="font-size: 9pt;" color="#000000">Mandiri</span>
                        </td>
                        <td width="8%";>
                            <span style="font-size: 9pt;" color="#000000">0</span>
                        </td>
                    </tr>
                </table>
                <table width="100%">
                    <tr>
                        <td width="10%";>
                            <span style="font-size: 9pt;" color="#000000">2</span>
                        </td>
                        <td width="40%";>
                            <span style="font-size: 9pt;" color="#000000">Berjalan</span>
                        </td>
                        <td width="40%";>
                            <input type="checkbox"
                                {{ isset($data['berjalan']) && $data['berjalan']['poin'] == 3 ? 'checked' : '' }} />
                            <span style="font-size: 9pt;" color="#000000">75% dibantu</span>
                        </td>
                        <td width="8%";>
                            <span style="font-size: 9pt;" color="#000000">3</span>
                        </td>
                    </tr>
                </table>
                <table width="100%">
                    <tr>
                        <td width="10%";>
                            <span style="font-size: 9pt;" color="#000000"></span>
                        </td>
                        <td width="40%";>
                            <span style="font-size: 9pt;" color="#000000"></span>
                        </td>
                        <td width="40%";>
                            <input type="checkbox"
                                {{ isset($data['berjalan']) && $data['berjalan']['poin'] == 2 ? 'checked' : '' }} />
                            <span style="font-size: 9pt;" color="#000000">50% dibantu</span>
                        </td>
                        <td width="8%";>
                            <span style="font-size: 9pt;" color="#000000">2</span>
                        </td>
                    </tr>
                </table>
                <table width="100%">
                    <tr>
                        <td width="10%";>
                            <span style="font-size: 9pt;" color="#000000"></span>
                        </td>
                        <td width="40%";>
                            <span style="font-size: 9pt;" color="#000000"></span>
                        </td>
                        <td width="40%";>
                            <input type="checkbox"
                                {{ isset($data['berjalan']) && $data['berjalan']['poin'] == 1 ? 'checked' : '' }} />
                            <span style="font-size: 9pt;" color="#000000">25% dibantu</span>
                        </td>
                        <td width="8%";>
                            <span style="font-size: 9pt;" color="#000000">1</span>
                        </td>
                    </tr>
                </table>
                <table width="100%">
                    <tr>
                        <td width="10%";>
                            <span style="font-size: 9pt;" color="#000000"></span>
                        </td>
                        <td width="40%";>
                            <span style="font-size: 9pt;" color="#000000"></span>
                        </td>
                        <td width="40%";>
                            <input type="checkbox"
                                {{ isset($data['berjalan']) && $data['berjalan']['poin'] == 0 ? 'checked' : '' }} />
                            <span style="font-size: 9pt;" color="#000000">Mandiri</span>
                        </td>
                        <td width="8%";>
                            <span style="font-size: 9pt;" color="#000000">0</span>
                        </td>
                    </tr>
                </table>
                <table width="100%">
                    <tr>
                        <td width="10%";>
                            <span style="font-size: 9pt;" color="#000000">3</span>
                        </td>
                        <td width="40%";>
                            <span style="font-size: 9pt;" color="#000000">Mandi</span>
                        </td>
                        <td width="40%";>
                            <input type="checkbox"
                                {{ isset($data['mandi']) && $data['mandi']['poin'] == 3 ? 'checked' : '' }} />
                            <span style="font-size: 9pt;" color="#000000">75% dibantu</span>
                        </td>
                        <td width="8%";>
                            <span style="font-size: 9pt;" color="#000000">3</span>
                        </td>
                    </tr>
                </table>
                <table width="100%">
                    <tr>
                        <td width="10%";>
                            <span style="font-size: 9pt;" color="#000000"></span>
                        </td>
                        <td width="40%";>
                            <span style="font-size: 9pt;" color="#000000"></span>
                        </td>
                        <td width="40%";>
                            <input type="checkbox"
                                {{ isset($data['mandi']) && $data['mandi']['poin'] == 2 ? 'checked' : '' }} />
                            <span style="font-size: 9pt;" color="#000000">50% dibantu</span>
                        </td>
                        <td width="8%";>
                            <span style="font-size: 9pt;" color="#000000">2</span>
                        </td>
                    </tr>
                </table>
                <table width="100%">
                    <tr>
                        <td width="10%";>
                            <span style="font-size: 9pt;" color="#000000"></span>
                        </td>
                        <td width="40%";>
                            <span style="font-size: 9pt;" color="#000000"></span>
                        </td>
                        <td width="40%";>
                            <input type="checkbox"
                                {{ isset($data['mandi']) && $data['mandi']['poin'] == 1 ? 'checked' : '' }} />
                            <span style="font-size: 9pt;" color="#000000">25% dibantu</span>
                        </td>
                        <td width="8%";>
                            <span style="font-size: 9pt;" color="#000000">1</span>
                        </td>
                    </tr>
                </table>
                <table width="100%">
                    <tr>
                        <td width="10%";>
                            <span style="font-size: 9pt;" color="#000000"></span>
                        </td>
                        <td width="40%";>
                            <span style="font-size: 9pt;" color="#000000"></span>
                        </td>
                        <td width="40%";>
                            <input type="checkbox"
                                {{ isset($data['mandi']) && $data['mandi']['poin'] == 0 ? 'checked' : '' }} />
                            <span style="font-size: 9pt;" color="#000000">Mandiri</span>
                        </td>
                        <td width="8%";>
                            <span style="font-size: 9pt;" color="#000000">0</span>
                        </td>
                    </tr>
                </table>
                <table width="100%">
                    <tr>
                        <td width="10%";>
                            <span style="font-size: 9pt;;font-weight: bold;" color="#000000">Jumlah</span>
                        </td>
                        <td width="40%";>
                            <span style="font-size: 9pt;" color="#000000"></span>
                        </td>
                        <td width="40%";>
                            <span style="font-size: 9pt;" color="#000000"></span>
                        </td>
                        <td width="8%";>
                            <span style="font-size: 9pt;font-weight: bold;" color="#000000">
                                {{ isset($data['totalNilaiPmrksADL']) ? $data['totalNilaiPmrksADL'] : '-' }}
                            </span>
                        </td>
                    </tr>
                </table>

            </td>
        </tr>
        </tr>
        <tr>
            <td colspan="7" style="padding:4px 0px;">
                <span style="font-size: 10pt;font-weight: bold;" color="#000000">Asesmen Keperawatan Khusus
                </span>
            </td>
        </tr>
        <tr>
            <td colspan="7" style="padding:4px 0px;">
                <span style="font-size: 10pt;font-weight: bold;" color="#000000">1.Asesmen Khusus Lansia (usia
                    >65 Tahun)</span>
            </td>
        </tr>
        <tr>
            <td colspan="7" style="padding:4px 0px;">
                <span style="font-size: 10pt;font-weight: bold;" color="#000000">Kondisi fisik dan mental</span>
            </td>
        </tr>
        <tr>
            <td colspan="7" style="padding:4px 0px;">
                <span style="font-size: 10pt;font-weight: bold;" color="#000000">Pernah Jatuh</span>
            </td>
        </tr>
        <tr>
            <table width="100%">
                <tr>
                    <td width="20%";>
                        <input type="checkbox"
                            {{ isset($data['pernahJatuh']) && $data['pernahJatuh'] == 'Tidak' ? 'checked' : '' }} />
                        <span style="font-size: 9pt;" color="#000000">Tidak</span>
                    </td>
                    <td width="20%";>
                        <span style="font-size: 9pt;" color="#000000">Ya,
                            {{ isset($data['descPernahJatuh']) ? $data['descPernahJatuh'] : '-' }}</span>
                    </td>

                    <td width="20%";>
                        <input type="checkbox"
                            {{ isset($data['pernahJatuh']) && $data['pernahJatuh'] == 'Bulan' ? 'checked' : '' }} />
                        <span style="font-size: 9pt;" color="#000000">Bulan</span>
                    </td>
                    <td width="20%";>
                        <input type="checkbox"
                            {{ isset($data['pernahJatuh']) && $data['pernahJatuh'] == 'Tahun yang lalu' ? 'checked' : '' }} />
                        <span style="font-size: 9pt;" color="#000000">Tahun lalu</span>
                    </td>
                </tr>
            </table>
        </tr>
        <tr>
            <td colspan="7" style="padding:4px 0px;">
                <span style="font-size: 10pt;font-weight: bold;" color="#000000">Kontraktur/Nyeri gerak</span>
            </td>
        </tr>
        <tr>
            <table width="100%">
                <tr>
                    <td width="20%";>
                        <input type="checkbox"
                            {{ isset($data['kontraktur']) && $data['kontraktur'] == 'Tidak' ? 'checked' : '' }} />
                        <span style="font-size: 9pt;" color="#000000">Tidak</span>
                    </td>
                    <td width="20%";>
                        <span style="font-size: 9pt;" color="#000000"><b>Ya,di
                                {{ isset($data['descKontraktur']) ? $data['descKontraktur'] : ' -' }}</b>
                        </span>
                    </td>

                    <td width="20%";>

                    </td>
                    <td width="20%";>

                    </td>
                </tr>
            </table>
        </tr>
        <tr>
            <td colspan="7" style="padding:4px 0px;">
                <span style="font-size: 10pt;font-weight: bold;" color="#000000">Menggunakan alat bantu</span>
            </td>
        </tr>
        <tr>
            <table width="100%">
                <tr>
                    <td width="20%";>
                        <input type="checkbox"
                            {{ isset($data['alatBantu']) && $data['alatBantu'] == 'Tidak' ? 'checked' : '' }} />
                        <span style="font-size: 9pt;" color="#000000">Tidak</span>
                    </td>
                    <td width="20%";>
                        <input type="checkbox"
                            {{ isset($data['alatBantu']) && $data['alatBantu'] == 'Tongkat' ? 'checked' : '' }} />
                        <span style="font-size: 9pt;" color="#000000">Tongkat </span>
                    </td>

                    <td width="20%";>
                        <input type="checkbox"
                            {{ isset($data['alatBantu']) && $data['alatBantu'] == 'Kursi roda' ? 'checked' : '' }} />
                        <span style="font-size: 9pt;" color="#000000">Kursi Roda</span>
                    </td>
                    <td width="20%";>
                        <span style="font-size: 9pt;font-weight: bold;" color="#000000">Lainnya :
                            {{ isset($data['descAlatBantu']) ? $data['descAlatBantu'] : ' -' }}</span>
                        <span style="font-size: 9pt;" color="#000000"></span>
                    </td>
                </tr>
            </table>
        </tr>
        <tr>
            <td colspan="7" style="padding:4px 0px;">
                <span style="font-size: 10pt;font-weight: bold;" color="#000000">Skala depresi</span>
            </td>
        </tr>
        <tr>
            <table width="100%">
                <tr>
                    <td width="20%";>
                        <input type="checkbox"
                            {{ isset($data['skalaDepresi']) && $data['skalaDepresi'] == 'Tidak depresi (1-4)' ? 'checked' : '' }} />
                        <span style="font-size: 9pt;" color="#000000">Tidak depresi (1-4)</span>
                    </td>
                    <td width="20%";>
                        <input type="checkbox"
                            {{ isset($data['skalaDepresi']) && $data['skalaDepresi'] == 'Resiko depresi (5-9)' ? 'checked' : '' }} />
                        <span style="font-size: 9pt;" color="#000000">Resiko depresi (5-9) </span>
                    </td>

                    <td width="20%";>
                        <input type="checkbox"
                            {{ isset($data['skalaDepresi']) && $data['skalaDepresi'] == 'Depresi (>9)' ? 'checked' : '' }} />
                        <span style="font-size: 9pt;" color="#000000">Depresi(>9)</span>
                    </td>
                    <td width="20%";>

                    </td>
                </tr>
            </table>
        </tr>
        <tr>
            <td colspan="7" style="padding:4px 0px;">
                <span style="font-size: 10pt;font-weight: bold;" color="#000000">Memori</span>
            </td>
        </tr>
        <tr>
            <table width="100%">
                <tr>
                    <td width="20%";>
                        <input type="checkbox"
                            {{ isset($data['memori']) && $data['memori'] == 'Baik' ? 'checked' : '' }} />
                        <span style="font-size: 9pt;" color="#000000">Baik</span>
                    </td>
                    <td width="20%";>
                        <input type="checkbox"
                            {{ isset($data['memori']) && $data['memori'] == 'Sering lupa' ? 'checked' : '' }} />
                        <span style="font-size: 9pt;" color="#000000">Sering lupa </span>
                    </td>

                    <td width="20%";>
                        <input type="checkbox"
                            {{ isset($data['memori']) && $data['memori'] == 'Tidak Ingat' ? 'checked' : '' }} />
                        <span style="font-size: 9pt;" color="#000000">Tidak Ingat</span>
                    </td>
                    <td width="20%";>

                    </td>
                </tr>
            </table>
        </tr>
        <tr>
            <td colspan="7" style="padding:4px 0px;">
                <span style="font-size: 10pt;font-weight: bold;" color="#000000">Status minimental</span>
            </td>
        </tr>
        <tr>
            <table width="100%">
                <tr>
                    <td width="20%";>
                        <input type="checkbox"
                            {{ isset($data['statusMinimental']) && $data['statusMinimental'] == 'Normal (24-30)' ? 'checked' : '' }} />
                        <span style="font-size: 9pt;" color="#000000">Normal (24-30)</span>
                    </td>
                    <td width="20%";>
                        <input type="checkbox"
                            {{ isset($data['statusMinimental']) && $data['statusMinimental'] == 'Ringan (17-23)' ? 'checked' : '' }} />
                        <span style="font-size: 9pt;" color="#000000">Ringan (17-23)</span>
                    </td>

                    <td width="20%";>
                        <input type="checkbox"
                            {{ isset($data['statusMinimental']) && $data['statusMinimental'] == 'Pasti (<=16)' ? 'checked' : '' }} />
                        <span style="font-size: 9pt;" color="#000000">Pasti (<=16)< /span>
                    </td>
                    <td width="20%";>

                    </td>
                </tr>
            </table>
        </tr>

        <tr>
            <td width="100%" colspan="3" style="padding:4px 0px;">
                <table width="100%">
                    <tr>
                        <td width="10%">
                            <span style="font-size: 9pt;font-weight: bold;" color="#000000">Edukasi</span>
                        </td>
                        <td width="1%">
                            <span>:</span>
                        </td>
                        <td width="20%">
                            <span style="font-size: 9pt;"
                                color="#000000">{{ isset($data['edukasi']) ? $data['edukasi'] : ' -' }} </span>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>

        <tr>
            <table width="100%">
                <tr>
                    <td width="50%";>
                        <span style="font-size: 9pt;font-weight: bold;" color="#000000">Hambatan edukasi</span>
                    </td>
                </tr>
            </table>
            <table width="100%">
                <tr>
                    <td width="33%";>
                        <span style="font-size: 9pt;" color="#000000">Bahasa</span>
                    </td>
                    <td width="80%";>
                        <span style="font-size: 9pt;" color="#000000"> :
                            {{ isset($data['hambatanEdukasiBhs']) ? $data['hambatanEdukasiBhs'] : ' -' }}</span>
                    </td>

                </tr>
            </table>
            <table width="100%">
                <tr>
                    <td width="33%";>
                        <span style="font-size: 9pt;" color="#000000">Cacat/Fisik/Kognitif(gangguan
                            penglihatan/pendengaran lain)</span>
                    </td>
                    <td width="80%";>
                        <span style="font-size: 9pt;" color="#000000">:
                            {{ isset($data['hambatanEdukasiFisik']) ? $data['hambatanEdukasiFisik'] : ' -' }}</span>
                    </td>

                </tr>
            </table>

        </tr>
        <tr>
            <table width="100%">
                <tr>
                    <td width="50%";>
                        <span style="font-size: 9pt;font-weight: bold;" color="#000000">Psikososial/ekonomi
                        </span>
                    </td>
                </tr>
            </table>
            <table width="100%">

                <tr>
                    <td width="10%";>
                        <span style="font-size: 9pt;" color="#000000">Pendidikan</span>
                    </td>
                    <td width="20%";>
                        <span style="font-size: 9pt;" color="#000000">:
                            {{ isset($data['pendidikan']) ? $data['pendidikan'] : ' -' }}</span>
                    </td>
                </tr>

            </table>

        </tr>
        <tr>
            <table width="100%">

                <tr>
                    <td width="10%";>
                        <span style="font-size: 9pt;" color="#000000">Pekerjaan</span>
                    </td>
                    <td width="20%";>
                        <span style="font-size: 9pt;" color="#000000">:
                            {{ isset($data['pekerjaan']) ? $data['pekerjaan'] : ' -' }}</span>
                    </td>
                </tr>

            </table>

        </tr>
        <tr>
            <table width="100%">

                <tr>
                    <td width="10%";>
                        <span style="font-size: 9pt;" color="#000000">Agama</span>
                    </td>
                    <td width="20%";>
                        <span style="font-size: 9pt;" color="#000000">:
                            {{ isset($data['agama']) ? $data['agama'] : ' -' }}</span>
                    </td>
                </tr>

            </table>


        </tr>

        <tr>
            <table width="100%">
                <tr>
                    <td width="50%";>
                        <span style="font-size: 9pt;font-weight: bold;" color="#000000">Keluarga</span>
                    </td>
                </tr>
            </table>
            <table width="100%">
                <tr>
                    <td width="20%";>
                        <input type="checkbox"
                            {{ isset($data['keluarga']) && $data['keluarga'] == 'Tinggal Sendiri' ? 'checked' : '' }} />
                        <span style="font-size: 9pt;" color="#000000">Tinggal Sendiri</span>
                    </td>
                    <td width="20%";>
                        <input type="checkbox"
                            {{ isset($data['keluarga']) && $data['keluarga'] == 'Tinggal Serumah' ? 'checked' : '' }} />
                        <span style="font-size: 9pt;" color="#000000">Tinggal Serumah</span>
                    </td>
                    <td width="20%";>
                        <!-- <input type="checkbox" ng-model="item.obj[3110013]" /><span style="font-size: 9pt;" color="#000000" >Duda/Janda</span> -->
                    </td>
                    <td width="20%";>
                        <!-- <input type="checkbox" ng-model="item.obj[3110013]" /><span style="font-size: 9pt;" color="#000000" >Hindu</span> -->
                    </td>
                    <td width="20%";>
                        <!-- <input type="checkbox" ng-model="item.obj[3110013]" /><span style="font-size: 9pt;" color="#000000" >Budha</span> -->
                    </td>
                </tr>
            </table>

        </tr>
        <tr>
            <table width="100%">
                <tr>
                    <td width="50%";>
                        <span style="font-size: 9pt;font-weight: bold;" color="#000000">Tempat Tinggal</span>
                    </td>
                </tr>
            </table>
            <table width="100%">
                <tr>
                    <td width="20%";>
                        <input type="checkbox"
                            {{ isset($data['tempatTinggal']) && $data['tempatTinggal'] == 'Rumah' ? 'checked' : '' }} />
                        <span style="font-size: 9pt;" color="#000000">Rumah</span>
                    </td>
                    <td width="20%";>
                        <input type="checkbox"
                            {{ isset($data['tempatTinggal']) && $data['tempatTinggal'] == 'Panti Asuhan' ? 'checked' : '' }} />
                        <span style="font-size: 9pt;" color="#000000">Panti asuhan</span>
                    </td>
                    <td width="8%";>
                        <span style="font-size: 9pt;" color="#000000"><b>Lainnya</b></span>
                    </td>
                    <td width="50%";>
                        <span style="font-size: 9pt;" color="#000000">:
                            {{ isset($data['tempatTinggalLain']) ? $data['tempatTinggalLain'] : ' -' }}
                        </span>

                        <!-- <input type="checkbox" ng-model="item.obj[3110013]" /><span style="font-size: 9pt;" color="#000000" >Hindu</span> -->
                    </td>
                    <td width="20%";>
                        <!-- <input type="checkbox" ng-model="item.obj[3110013]" /><span style="font-size: 9pt;" color="#000000" >Budha</span> -->
                    </td>
                </tr>
            </table>

        </tr>
        <tr>
            <table width="100%">
                <tr>
                    <td width="50%";>
                        <span style="font-size: 9pt;font-weight: bold;" color="#000000">Status Psikologis</span>
                    </td>
                </tr>
            </table>
            <table width="100%">
                <tr>
                    <td width="20%";>
                        <input type="checkbox"
                            {{ isset($data['statusPsikologi']) && $data['statusPsikologi'] == 'Depresi' ? 'checked' : '' }} />
                        <span style="font-size: 9pt;" color="#000000">Depresi</span>
                    </td>
                    <td width="20%";>
                        <input type="checkbox"
                            {{ isset($data['statusPsikologi']) && $data['statusPsikologi'] == 'Takut' ? 'checked' : '' }} />
                        <span style="font-size: 9pt;" color="#000000">Takut</span>
                    </td>
                    <td width="20%";>
                        <input type="checkbox"
                            {{ isset($data['statusPsikologi']) && $data['statusPsikologi'] == 'Agresif' ? 'checked' : '' }} />
                        <span style="font-size: 9pt;" color="#000000">Agresif</span>
                    </td>
                    <td width="20%";>
                        <input type="checkbox"
                            {{ isset($data['statusPsikologi']) && $data['statusPsikologi'] == 'Melukai Diri Sendiri' ? 'checked' : '' }} />
                        <span style="font-size: 9pt;" color="#000000">Melukai diri sendiri</span>
                    </td>
                    <td width="20%";>
                        <input type="checkbox"
                            {{ isset($data['statusPsikologi']) && $data['statusPsikologi'] == 'Tidak ada Gejala' ? 'checked' : '' }} />
                        <span style="font-size: 9pt;" color="#000000">Tidak ada gejala</span>
                    </td>
                </tr>
            </table>

        </tr>

        <tr>
            <td width="100%" colspan="3" style="padding:4px 0px;">
                <table width="100%">
                    <tr>
                        <td width="10%" style="vertical-align: top;">
                            <span style="font-size: 9pt;font-weight: bold; vertikal" color="#000000">Diagnosa
                                Keperawatan
                            </span>
                        </td>
                        <td width="1%">
                            <span color="#000000">:</span>
                        </td>
                        <td width="20%">
                            <span style="font-size: 9pt;"
                                color="#000000">{{ isset($data['diagnosaKeperawatan']) ? $data['diagnosaKeperawatan'] : '' }}</span>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>

        <tr>
            <td width="100%" colspan="3" style="padding:4px 0px;">
                <table width="100%">
                    <tr>
                        <td width="10%" style="vertical-align: top;">
                            <span style="font-size: 9pt;font-weight: bold;" color="#000000">Planning /
                                Intervensi</span>
                        </td>
                        <td width="1%">
                            <span style="margin-left: 11px;" color="#000000">:</span>
                        </td>
                        <td width="20%">
                            <span style="font-size: 9pt;"
                                color="#000000">{{ isset($data['intervensi']) ? $data['intervensi'] : '' }}</span>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td width="100%" colspan="4" height="10"></td>
        </tr>
    </table>
    <table style="padding-top: 3rem;">
        <tbody>
            <tr>
                <td width="60%"></td>
                <td style="text-align: right">
                    <!-- <font style="font-size: 11pt;" color="#000000" ><b>Bandung, Jam :</b> </font> -->

                    <span style="font-size: 11pt;" color="#000000"><b>Bandung</b>, {{ $tgl }}
                    </span>
                    <span style="font-size: 11pt;" color="#000000"><b>Jam</b>: {{ $jam }}</span>
                </td>
            </tr>
            <tr>
                <td width="100%" colspan="4" height="10"></td>
            </tr>
            <tr>
                <td width="60%"></td>
                <td width="50%" style="text-align: right;padding-right: 95px;">
                    <b>
                        <span style="font-size: 11pt;" color="#000000">
                            Perawat </span>
                    </b>
                    <br>
                </td>
            </tr>

            <tr>
                <td width="60%"></td>
                <td width="40%" style="text-align:center">
                    <span style="font-size: 11pt;font-weight: bold;" color="#000000">
                        ( {{ $data['perawat']['label'] }} )</span>
                </td>
            </tr>

        </tbody>
    </table>
    {{-- 
<table>
    <tbody>
        <tr>
            <td style="text-align: right">
                <!-- <span style="font-size: 11pt;" color="#000000" ><b>Bandung, Jam :</b>  </span> -->
                <span style="font-size: 11pt;" color="#000000"><b>Bandung</b>, 
                </span>
                <span style="font-size: 11pt;" color="#000000"><b>Jam</b>: </span>
            </td>
        </tr>
        <tr>
            <td width="100%" colspan="4" height="10"></td>
        </tr>
        <tr>
            <td width="50%" style="text-align: right;padding-right: 95px;">
                <b>
                    <span style="font-size: 11pt;" color="#000000">
                        Perawat </span>
                </b>
                <br>
                




        </td>
    </tr>

    <tr>
        <td width="40%" style="text-align: right;padding-right: 45px;"><b>
                <span style="font-size: 11pt;" color="#000000"></span>
            </b>
        </td>
    </tr>

</tbody> --}}

    {{-- @forelse($dataimg as $d)
                    @if ($d->emrdfk == 1)
                        <img src="{{ $d->image }}" width="75" height="75" alt="TTD" />
                    @break
                @endif
            @empty
                <div style="height:75px"></div>
            @endforelse --}}

@endsection
