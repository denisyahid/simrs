@extends('template.head-emr')
@section('title', 'EMR')
@section('about', 'Asesmen Awal Keperawatan Pasien Rawat Jalan')
@section('content')

<table width="100%" cellspacing="0">
    <tr>
        <td width="100%" colspan="4"><span style="font-size: 10pt;" class="judulLabel" color="#000000" >Anamnesa</span></td>
    </tr>
    <tr>
        <td width="100%" colspan="4">
            <table width="100%">
                <tr>
                    <td width="30%" class="text-top">
                        <span style="font-size: 10pt;" color="#000000" >Keluhan Utama</span>
                    </td>
                    <td width="1%" class="text-top">
                        <span style="font-size: 10pt;" color="#000000" >:</span>
                    </td>
                    <td width="69%">
                        <span style="font-size: 10pt;" color="#000000" >{{isset($data['keluahanUtama']) ? $data['keluahanUtama'] : '-'}}</span>
                    </td>                 
                </tr>
                <tr>
                    <td width="30%" class="text-top">
                        <span style="font-size: 10pt;" color="#000000" >Riwayat Penyakit Dahulu</span>
                    </td>
                    <td width="1%" class="text-top">
                        <span style="font-size: 10pt;" color="#000000" >:</span>
                    </td>
                    <td width="69%">
                        <span style="font-size: 10pt;" color="#000000" >{{isset($data['riwayatPenyakitDahulu']) ? $data['riwayatPenyakitDahulu'] : '-'}}</span>
                    </td>                 
                </tr>
                {{-- <tr>
                    <td width="30%" class="text-top">
                        <span style="font-size: 10pt;" color="#000000" >Alergi</span>
                    </td>
                    <td width="1%" class="text-top">
                        <span style="font-size: 10pt;" color="#000000" >:</span>
                    </td>
                    <td width="69%">
                        <span style="font-size: 10pt;" color="#000000" ></span>
                    </td>                 
                </tr> --}}
                <tr>
                    <td width="30%" class="text-top">
                        <span style="font-size: 10pt;" color="#000000" >Riwayat Alergi</span>
                    </td>
                    <td width="1%" class="text-top">
                        <span style="font-size: 10pt;" color="#000000" >:</span>
                    </td>
                    <td width="69%">
                        <span style="font-size: 10pt;" color="#000000" >{{isset($data['riwayatAlergi']) ? $data['riwayatAlergi'] : '-'}}</span>
                    </td>                 
                </tr>
                <tr>
                    <td width="100%" colspan="4" height="10"></td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td width="100%" colspan="4"><span style="font-size: 10pt;" class="judulLabel" color="#000000" >Faktor Risiko Penyakit Jantung</span></td>
    </tr>
    <tr>
        <td width="100%" colspan="4"><span style="font-size: 10pt;" color="#000000" >Hipertensi</span></td>
    </tr>
    <tr>
        <td width="100%" colspan="4">
            <table width="100%">
                <tr>
                    <td width="20%">
                        <input type="checkbox" {{ isset($data['hipertensi']) && $data['hipertensi'] == 'Ya' ? 'checked' : '' }} />
                        <span style="font-size: 9pt;" color="#000000" >Ya</span>
                    </td>
                </tr>
                <tr>
                    <td width="20%">
                        <input type="checkbox" {{ isset($data['hiperKontrol']) && $data['hiperKontrol'] == 'Terkontrol' ? 'checked' : '' }}/>
                        <span style="font-size: 9pt;" color="#000000" >Terkontrol</span>
                    </td>
                    <td width="20%">
                        <input type="checkbox" {{ isset($data['hiperKontrol']) && $data['hiperKontrol'] == 'Tidak Terkontrol' ? 'checked' : '' }} />
                        <span style="font-size: 9pt;" color="#000000" >Tidak Terkontrol</span>
                    </td>
                </tr>
                <tr>
                    <td width="20%">
                        <input type="checkbox" {{ isset($data['hipertensi']) && $data['hipertensi'] == 'Tidak' ? 'checked' : '' }} />
                        <span style="font-size: 9pt;" color="#000000" >Tidak</span>
                    </td>
                </tr>
            </table>
        </td>                      
    </tr>
    <tr>
        <td width="100%" colspan="4"><span style="font-size: 10pt;" color="#000000" >Merokok</span></td>
    </tr>
    <tr>
        <td width="100%" colspan="4">
            <table width="100%">
                <tr>
                    <td width="30%">
                        <input type="checkbox" {{ isset($data['merokok']) && $data['merokok'] == 'Ya' ? 'checked' : '' }}/>
                        <span style="font-size: 9pt;" color="#000000" >Ya, (beberapa pack/hari,berapa lama,berhenti kapan)</span>
                        <span style="font-size: 10pt;" color="#000000" >{{isset($data['ketMerokok']) ? $data['ketMerokok'] : '-'}}</span>
                    </td>
                    <td width="20%">
                        <input type="checkbox" {{ isset($data['merokok']) && $data['merokok'] == 'Tidak' ? 'checked' : '' }} />
                        <span style="font-size: 9pt;" color="#000000" >Tidak</span>
                    </td>
                </tr>
            </table>
        </td>                      
    </tr>
    <tr>
        <td width="100%" colspan="4"><span style="font-size: 10pt;" color="#000000" >Diabetes Melitus</span></td>
    </tr>
    <tr>
        <td width="100%" colspan="4">
            <table width="100%">
                <tr>
                    <td width="20%">
                        <input type="checkbox" {{ isset($data['diabetes']) && $data['diabetes'] == 'Ya' ? 'checked' : '' }} />
                        <span style="font-size: 9pt;" color="#000000" >Ya</span>
                    </td>
                </tr>
                <tr>
                    <td width="20%">
                        <input type="checkbox" {{ isset($data['diabetesKontrol']) && $data['diabetesKontrol'] == 'Terkontrol' ? 'checked' : '' }} />
                        <span style="font-size: 9pt;" color="#000000" >Terkontrol</span>
                    </td>
                    <td width="20%">
                        <input type="checkbox" {{ isset($data['diabetesKontrol']) && $data['diabetesKontrol'] == 'Tidak Terkontrol' ? 'checked' : '' }}/>
                        <span style="font-size: 9pt;" color="#000000" >Tidak Terkontrol</span>
                    </td>
                </tr>
                <tr>
                    <td width="20%">
                        <input type="checkbox" {{ isset($data['diabetes']) && $data['diabetes'] == 'Tidak' ? 'checked' : '' }} />
                        <span style="font-size: 9pt;" color="#000000" >Tidak</span>
                    </td>
                </tr>
            </table>
        </td>                      
    </tr>
    <tr>
        <td width="100%" colspan="4"><span style="font-size: 10pt;" color="#000000" >Dyslipidemia (Kelainan Kolestrol Darah)</span></td>
    </tr>
    <tr>
        <td width="100%" colspan="4">
            <table width="100%">
                <tr>
                    <td width="20%">
                        <input type="checkbox" {{ isset($data['dyslipidemia']) && $data['dyslipidemia'] == 'Ya' ? 'checked' : '' }} />
                        <span style="font-size: 9pt;" color="#000000" >Ya</span>
                    </td>
                </tr>
                <tr>
                    <td width="20%">
                        <input type="checkbox" {{ isset($data['dyslipidemiaKontrol']) && $data['dyslipidemiaKontrol'] == 'Terkontrol' ? 'checked' : '' }} />
                        <span style="font-size: 9pt;" color="#000000" >Terkontrol</span>
                    </td>
                    <td width="20%">
                        <input type="checkbox" {{ isset($data['dyslipidemiaKontrol']) && $data['dyslipidemiaKontrol'] == 'Tidak Terkontrol' ? 'checked' : '' }} />
                        <span style="font-size: 9pt;" color="#000000" >Tidak Terkontrol</span>
                    </td>
                </tr>
                <tr>
                    <td width="20%">
                        <input type="checkbox" {{ isset($data['dyslipidemia']) && $data['dyslipidemia'] == 'Tidak' ? 'checked' : '' }} />
                        <span style="font-size: 9pt;" color="#000000" >Tidak</span>
                    </td>
                </tr>
            </table>
        </td>                      
    </tr>
    <tr>
        <td width="100%" colspan="4"><span style="font-size: 10pt;" color="#000000" >Riwayat serangan jantung dini pada orang tua (pria < 55 tahun atau wanita < 65 tahun)</span></td>
    </tr>
    <tr>
        <td width="100%" colspan="4">
            <table width="100%">
                <tr>
                    <td width="20%">
                        <input type="checkbox" {{ isset($data['riwayatSeranganJantung']) && $data['riwayatSeranganJantung'] == 'Ya' ? 'checked' : '' }} />
                        <span style="font-size: 9pt;" color="#000000" >Ya</span>
                    </td>
                    <td width="20%">
                        <input type="checkbox" {{ isset($data['riwayatSeranganJantung']) && $data['riwayatSeranganJantung'] == 'Tidak' ? 'checked' : '' }}/>
                        <span style="font-size: 9pt;" color="#000000" >Tidak</span>
                    </td>
                </tr>
            </table>
        </td>                      
    </tr>
    {{-- <tr>
        <td width="100%" colspan="4">
            <table width="100%" border="1" bordercolor="#000000" class="garis6">
                <tr bgcolor="#f08080">
                    <th style="text-align: center;">Nama Obat</th>
                    <th style="text-align: center;">Frekuensi</th>
                    <th style="text-align: center;">Sediaan</th>
                    <th style="text-align: center;">Rutin/Tidak Rutin</th>
                </tr>
                <tr ng-if="item.obj[3103751]">
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr ng-if="item.obj[3103755]">
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr ng-if="item.obj[3103759]">
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr ng-if="item.obj[3103763]">
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr ng-if="item.obj[3103767]">
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
            </table>
        </td>                      
    </tr> --}}
    <tr>
        <td width="100%" colspan="4"><span style="font-size: 10pt;" color="#000000" class="judulLabel" >Tanda tanda vital</span></td>
    </tr>
            <tr>
                <td width="100%" colspan="4">
                    <table width="100%">
                        <tr>
                            <td width="20%" class="text-top">
                                <span style="font-size: 10pt;" color="#000000" >Tekanan Darah</span>
                            </td>
                            <td width="0.1%" class="text-top">
                                <span style="font-size: 10pt;" color="#000000" >:</span>
                            </td>
                            <td width="10%">
                                <span style="font-size: 10pt;" color="#000000" >{{isset($data['tekananDarah']) ? $data['tekananDarah'] : '-'}} mmHg</span>
                            </td>

                            <td width="20%" class="text-top">
                                <span style="font-size: 10pt;" color="#000000" >Frekuensi Nadi</span>
                            </td>
                            <td width="0.1%" class="text-top">
                                <span style="font-size: 10pt;" color="#000000" >:</span>
                            </td>
                            <td width="10%">
                                <span style="font-size: 10pt;" color="#000000" >{{isset($data['nadi']) ? $data['nadi'] : '-'}}  x/menit</span>
                            </td>
                        </tr>

                        <tr>
                            <td width="20%" class="text-top">
                                <span style="font-size: 10pt;" color="#000000" >Suhu</span>
                            </td>
                            <td width="0.1%" class="text-top">
                                <span style="font-size: 10pt;" color="#000000" >:</span>
                            </td>
                            <td width="10%">
                                <span style="font-size: 10pt;" color="#000000" >{{isset($data['suhu']) ? $data['suhu'] : '-'}} C</span>
                            </td>

                            <td width="20%" class="text-top">
                                <span style="font-size: 10pt;" color="#000000" >Frekuensi Nafas</span>
                            </td>
                            <td width="0.1%" class="text-top">
                                <span style="font-size: 10pt;" color="#000000" >:</span>
                            </td>
                            <td width="10%">
                                <span style="font-size: 10pt;" color="#000000" >{{isset($data['pernapasan']) ? $data['pernapasan'] : '-'}} x/menit</span>
                            </td>
                        </tr>
                        <tr>
                            <td width="20%" class="text-top">
                                <span style="font-size: 10pt;" color="#000000" >Skor Nyeri</span>
                            </td>
                            <td width="0.1%" class="text-top">
                                <span style="font-size: 10pt;" color="#000000" >:</span>
                            </td>
                            <td width="10%">
                                <span style="font-size: 10pt;" color="#000000" >{{isset($data['skorNyeri']) ? $data['skorNyeri'] : '-'}}</span>
                            </td>
                        </tr>

                    </table>
                </td>
            </tr>
                    <tr>
                        <td width="100%" colspan="4"><span style="font-size: 10pt;" color="#000000" class="judulLabel" >Skoring nyeri (Wong Baker Faces)</span></td>
                    </tr>
                    <tr>
                        <td width="100%" colspan="4"><span style="font-size: 10pt;" color="#000000" >Score</span></td>
                    </tr>
                    <tr>
                        <td width="100%" colspan="4">
                            <table width="100%">
                                <tr>
                                    <td width="20%">
                                        <input type="checkbox" {{ isset($data['skoringNyeri']) && $data['skoringNyeri'] == 1 ? 'checked' : '' }} />
                                        <span style="font-size: 9pt;" color="#000000" >0-1 = Tidak Ada Nyeri</span>
                                    </td>
                                    <td width="20%">
                                        <input type="checkbox" {{ isset($data['skoringNyeri']) && $data['skoringNyeri'] == 2 ? 'checked' : '' }}/>
                                        <span style="font-size: 9pt;" color="#000000" >2-3 = Sedikit Nyeri</span>
                                    </td>
                                    <td width="20%">
                                        <input type="checkbox" {{ isset($data['skoringNyeri']) && $data['skoringNyeri'] == 4 ? 'checked' : '' }} />
                                        <span style="font-size: 9pt;" color="#000000" >4-5 = Cukup Nyeri</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td width="20%">
                                        <input type="checkbox" {{ isset($data['skoringNyeri']) && $data['skoringNyeri'] == 6 ? 'checked' : '' }} />
                                        <span style="font-size: 9pt;" color="#000000" >6-7 = Lumayan Nyeri</span>
                                    </td>
                                    <td width="20%">
                                        <input type="checkbox" {{ isset($data['skoringNyeri']) && $data['skoringNyeri'] == 8 ? 'checked' : '' }} />
                                        <span style="font-size: 9pt;" color="#000000" >8-9 = Sangat Nyeri</span>
                                    </td>
                                    <td width="20%">
                                        <input type="checkbox" {{ isset($data['skoringNyeri']) && $data['skoringNyeri'] == 10 ? 'checked' : '' }} />
                                        <span style="font-size: 9pt;" color="#000000" >10 = Amat Sangat Nyeri</span>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td width="100%" colspan="4"><span style="font-size: 10pt;" color="#000000" class="judulLabel" >Skala resiko jatuh</span></td>
                    </tr>
                    <tr>
                        <td width="100%" colspan="4">
                            <table width="100%">
                                <tr>
                                    <td width="100%" colspan="4"><span style="font-size: 10pt;" color="#000000" >A. Perhatikan cara berjalan pasien saat akan duduk di kursi, apakah pasien tampa seimbang (sempoyongan/limbung) ?</span></td>
                                </tr>
                                <tr>
                                    <td width="20%">
                                        <input type="checkbox" {{ isset($data['sempoyongan']) && $data['sempoyongan']['jawab'] == 'Ya' ? 'checked' : '' }} />
                                        <span style="font-size: 9pt;" color="#000000" >Ya</span>
                                    </td>
                                    <td width="20%">
                                        <input type="checkbox" {{ isset($data['sempoyongan']) && $data['sempoyongan']['jawab'] == 'Tidak' ? 'checked' : '' }} />
                                        <span style="font-size: 9pt;" color="#000000" >Tidak</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td width="100%" colspan="4"><span style="font-size: 10pt;" color="#000000" >B. Apakah pasien memegang pinggiran kursi atau meja atau benda lain sebagai penopang saat akan duduk ?</span></td>
                                </tr>
                                <tr>
                                    <td width="20%">
                                        <input type="checkbox" {{ isset($data['saatDuduk']) && $data['saatDuduk']['jawab'] == 'Ya' ? 'checked' : '' }} />
                                        <span style="font-size: 9pt;" color="#000000" >Ya</span>
                                    </td>
                                    <td width="20%">
                                        <input type="checkbox" {{ isset($data['saatDuduk']) && $data['saatDuduk']['jawab'] == 'Tidak' ? 'checked' : '' }} />
                                        <span style="font-size: 9pt;" color="#000000" >Tidak</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td width="100%" colspan="4"><span style="font-size: 10pt;" color="#000000" >C. Hasil</span></td>
                                </tr>
                                <tr>
                                    <td width="20%">
                                        <input type="checkbox" {{ isset($data['hasil']) && $data['hasil'] == 'Tidak beresiko' ? 'checked' : '' }} />
                                        <span style="font-size: 9pt;" color="#000000" >Tidak beresiko (tidak ditemukan a dan b)</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td width="20%">
                                        <input type="checkbox" {{ isset($data['hasil']) && $data['hasil'] == 'Risiko tinggi' ? 'checked' : '' }} />
                                        <span style="font-size: 9pt;" color="#000000" >Risiko tinggi (ditemukan a dan b)</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td width="20%">
                                        <input type="checkbox" {{ isset($data['hasil']) && $data['hasil'] == 'Risiko Rendah Sedang' ? 'checked' : '' }} />
                                        <span style="font-size: 9pt;" color="#000000" >Risiko rendah-sedang (ditemukan (a atau b))</span>
                                    </td>
                                </tr>
                                    
                            </table>
                        </td>
                    </tr>

                    <tr>
                        <td width="100%" colspan="4"><span style="font-size: 10pt;" color="#000000" class="judulLabel" >Antropometri</span></td>
                    </tr>
                    <tr>
                        <td width="100%" colspan="4">
                            <table width="100%">
                                <tr>
                                    <td width="20%" class="text-top">
                                        <span style="font-size: 10pt;" color="#000000" >Berat badan</span>
                                    </td>
                                    <td width="0.1%" class="text-top">
                                        <span style="font-size: 10pt;" color="#000000" >:</span>
                                    </td>
                                    <td width="10%">
                                        <span style="font-size: 10pt;" color="#000000" >{{isset($data['beratBadan']) ? $data['beratBadan'] : '-'}} Kg</span>
                                    </td>

                                    <td width="20%" class="text-top">
                                        <span style="font-size: 10pt;" color="#000000" >Tinggi badan</span>
                                    </td>
                                    <td width="0.1%" class="text-top">
                                        <span style="font-size: 10pt;" color="#000000" >:</span>
                                    </td>
                                    <td width="10%">
                                        <span style="font-size: 10pt;" color="#000000" >{{isset($data['tinggiBadan']) ? $data['tinggiBadan'] : '-'}} Cm</span>
                                    </td>
                                </tr>

                                <tr>
                                    <td width="20%" class="text-top">
                                        <span style="font-size: 10pt;" color="#000000" >Lingkar Perut</span>
                                    </td>
                                    <td width="0.1%" class="text-top">
                                        <span style="font-size: 10pt;" color="#000000" >:</span>
                                    </td>
                                    <td width="10%">
                                        <span style="font-size: 10pt;" color="#000000" >{{isset($data['lingkarPerut']) ? $data['lingkarPerut'] : '-'}}</span>
                                    </td>

                                    <td width="20%" class="text-top">
                                        <span style="font-size: 10pt;" color="#000000" >IMT</span>
                                    </td>
                                    <td width="0.1%" class="text-top">
                                        <span style="font-size: 10pt;" color="#000000" >:</span>
                                    </td>
                                    <td width="10%">
                                        <span style="font-size: 10pt;" color="#000000" >{{isset($data['IMT']) ? $data['IMT'] : '-'}}</span>
                                    </td>
                                
                                </tr>
                                
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td width="100%" colspan="4"><span style="font-size: 10pt;" color="#000000" >RISIKO NUTRISIONAL</span></td>
                    </tr>
                    <tr>
                        <td width="100%" colspan="4"><span style="font-size: 10pt;" color="#000000" >Malnutrition Screening Tools (MST)</span></td>
                    </tr>
                    <tr>
                        <td width="100%" colspan="4"><span style="font-size: 10pt;" color="#000000" >1.Apakah ada penurunan berat badan yang tidak diinginkan selama 6 bulan terakhir ?</span></td>
                    </tr>
                <tr>
                <td width="100%" colspan="4">
                    <table width="100%">
                        <tr>
                            <td width="20%">
                                <input type="checkbox" {{ isset($data['penurunanBB']) && $data['penurunanBB']['keterangan'] == 'Tidak' ? 'checked' : '' }} />
                                <span style="font-size: 9pt;" color="#000000" >Tidak</span>
                            </td>
                            <td width="20%">
                                <span style="font-size: 9pt;" color="#000000" >POIN = 0</span>
                            </td>
                            <td width="20%">
                                <input type="checkbox" {{ isset($data['penurunanBB']) && $data['penurunanBB']['keterangan'] == 'Tidak Yakin' ? 'checked' : '' }} />
                                <span style="font-size: 9pt;" color="#000000" >Tidak Yakin</span>
                            </td> 
                            <td width="20%">
                                <span style="font-size: 9pt;" color="#000000" >POIN = 2</span>
                            </td> 
                        </tr>

                        <tr>
                            <td width="20%">
                                <input type="checkbox"  {{ isset($data['penurunanBB']) && $data['penurunanBB']['keterangan'] == 'Ya,1-5 Kg' ? 'checked' : '' }} />
                                <span style="font-size: 9pt;" color="#000000" >Ya, 1-5 Kg</span>
                            </td>
                            <td width="20%">
                                <span style="font-size: 9pt;" color="#000000" >POIN = 1</span>
                            </td>
                            <td width="20%">
                                <input type="checkbox" {{ isset($data['penurunanBB']) && $data['penurunanBB']['keterangan'] == '6-10 Kg' ? 'checked' : '' }} />
                                <span style="font-size: 9pt;" color="#000000" >6-10 Kg</span>
                            </td> 
                            <td width="20%">
                                <span style="font-size: 9pt;" color="#000000" >POIN = 2</span>
                            </td> 
                        </tr>

                        <tr>
                            <td width="20%">
                                <input type="checkbox" {{ isset($data['penurunanBB']) && $data['penurunanBB']['keterangan'] == '11-15 Kg' ? 'checked' : '' }} />
                                <span style="font-size: 9pt;" color="#000000" >11-15 Kg</span>
                            </td>
                            <td width="20%">
                                <span style="font-size: 9pt;" color="#000000" >POIN = 3</span>
                            </td>
                            <td width="20%">
                                <input type="checkbox" {{ isset($data['penurunanBB']) && $data['penurunanBB']['keterangan'] == '> 15 Kg' ? 'checked' : '' }} />
                                <span style="font-size: 9pt;" color="#000000" > > 15 Kg</span>
                            </td> 
                            <td width="20%">
                                <span style="font-size: 9pt;" color="#000000" >POIN = 4</span>
                            </td> 
                        </tr>

                    </table>
                </td>
            </tr>
            <tr>
                <td width="100%" colspan="4"><span style="font-size: 10pt;" color="#000000" >2.Apakah asupan makan menurun yang dikarenakan adanya penurunan nafsu makan/kesulitan menerima makan ?</span></td>
            </tr>
            <tr>
                <td width="100%" colspan="4">
                    <table width="100%">
                        <tr>
                            <td width="20%">
                                <input type="checkbox" {{ isset($data['penurunanNafsuMakan']) && $data['penurunanNafsuMakan']['keterangan'] == 'Tidak' ? 'checked' : '' }}/>
                                <span style="font-size: 9pt;" color="#000000" >Tidak</span>
                            </td>
                            <td width="20%">
                                <span style="font-size: 9pt;" color="#000000" >POIN = 0</span>
                            </td>
                            <td width="20%">
                                <input type="checkbox" {{ isset($data['penurunanNafsuMakan']) && $data['penurunanNafsuMakan']['keterangan'] == 'Ya' ? 'checked' : '' }}/>
                                <span style="font-size: 9pt;" color="#000000" >Ya</span>
                            </td> 
                            <td width="20%">
                                <span style="font-size: 9pt;" color="#000000" >POIN = 1</span>
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
                                <span style="font-size: 10pt;" color="#000000" >TOTAL SCORE</span>
                            </td>
                            <td width="0.1%" class="text-top">
                                <span style="font-size: 10pt;" color="#000000" >:</span>
                            </td>
                            <td width="10%">
                                <span style="font-size: 10pt;" color="#000000" >{{isset($data['totalNilaiMST']) ? $data['totalNilaiMST'] : '-'}}</span>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td width="100%" colspan="4"><span style="font-size: 10pt;" color="#000000" >Keterangan</span></td>
            </tr>
            <tr>
                <td width="100%" colspan="4">
                    <table width="100%">
                        <tr>
                            <td width="10%" class="text-top">
                                <span style="font-size: 9pt;" color="#000000" >Skor 0 - 1 :tidak beresiko</span>
                            </td>
                            <td width="15%" class="text-top">
                                <span style="font-size: 9pt;" color="#000000" >Skor 2 - 3 :berisiko (Asuhan Gizi oleh Dietisen)</span>
                            </td>
                            <td width="15%" class="text-top">
                                <span style="font-size: 9pt;" color="#000000" >Skor > 4 :Malnutrisi (Asuhan Gizi oleh Dietisen)</span>
                            </td>
                        </tr>
                    </table>
                </td>
            <tr>

            <tr>
                <td width="100%" colspan="4"><span style="font-size: 10pt;" class="judulLabel" color="#000000" >Fungsional</span></td>
            </tr>
            <tr>
                <td width="100%" colspan="4">
                    <table width="100%">
                        <tr>
                            <td width="30%" class="text-top">
                                <span style="font-size: 10pt;" color="#000000" >1. Alat bantu</span>
                            </td>
                            <td width="1%" class="text-top">
                                <span style="font-size: 10pt;" color="#000000" >:</span>
                            </td>
                            <td width="69%">
                                <span style="font-size: 10pt;" color="#000000" >{{isset($data['alatBantu']) ? $data['alatBantu'] : '-'}}</span>
                            </td>
                        </tr>
                        <tr>
                            <td width="30%" class="text-top">
                                <span style="font-size: 10pt;" color="#000000" >2. ADL</span>
                            </td>
                            <td width="1%" class="text-top">
                                <span style="font-size: 10pt;" color="#000000" >:</span>
                            </td>
                            <td width="69%">
                                <span style="font-size: 10pt;" color="#000000" >{{isset($data['adl']) ? $data['adl'] : '-'}}</span>
                            </td>
                        </tr>
                        <tr>
                           <td width="30%" class="text-top">
                                <span style="font-size: 10pt;" color="#000000" >3. Pendidikan</span>
                            </td>
                            <td width="1%" class="text-top">
                                <span style="font-size: 10pt;" color="#000000" >:</span>
                            </td>
                            <td width="69%">
                                <span style="font-size: 10pt;" color="#000000" >{{isset($data['pendidikan']) ? $data['pendidikan'] : '-'}}</span>
                            </td> 
                        </tr>
                        <tr>
                             <td width="30%" class="text-top">
                                <span style="font-size: 10pt;" color="#000000" >4. Pekerjaan</span>
                            </td>
                            <td width="1%" class="text-top">
                                <span style="font-size: 10pt;" color="#000000" >:</span>
                            </td>
                            <td width="69%">
                                <span style="font-size: 10pt;" color="#000000" >{{isset($data['pekerjaan']) ? $data['pekerjaan'] : '-'}}</span>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>

            <tr>
                <td width="100%" colspan="4">
                    <table width="100%">
                        <tr>
                            <td width="100%" colspan="4"><span style="font-size: 10pt;" color="#000000" >Agama</span></td>
                        </tr>
                        <tr>
                            <td width="20%">
                                <input type="checkbox" {{ isset($data['agama']) && $data['agama'] == 'Islam' ? 'checked' : '' }} />
                                <span style="font-size: 9pt;" color="#000000" >Islam</span>
                            </td>
                            <td width="20%">
                                <input type="checkbox" {{ isset($data['agama']) && $data['agama'] == 'Katholik' ? 'checked' : '' }} />
                                <span style="font-size: 9pt;" color="#000000" >Katholik</span>
                            </td>
                            <td width="20%">
                                <input type="checkbox" {{ isset($data['agama']) && $data['agama'] == 'Kristen' ? 'checked' : '' }} />
                                <span style="font-size: 9pt;" color="#000000" >Kristen</span>
                            </td>
                            
                        </tr>
                        <tr>
                            <td width="20%">
                                <input type="checkbox" {{ isset($data['agama']) && $data['agama'] == 'Hindu' ? 'checked' : '' }} />
                                <span style="font-size: 9pt;" color="#000000" >Hindu</span>
                            </td>
                            <td width="20%">
                                <input type="checkbox" {{ isset($data['agama']) && $data['agama'] == 'Budha' ? 'checked' : '' }} />
                                <span style="font-size: 9pt;" color="#000000" >Budha</span>
                            </td>
                            {{-- <td width="20%">
                                <input type="checkbox" /><span style="font-size: 9pt;" color="#000000" >Lainnya</span>
                                <span style="font-size: 9pt;" color="#000000" ></span>
                            </td> --}}
                        </tr>
                    </table>
                </td>
            </tr>

            <tr>
                <td width="100%" colspan="4">
                    <table width="100%">
                        <tr>
                            <td width="30%" class="text-top">
                                <span style="font-size: 10pt;" color="#000000" >Nilai-nilai yang dianut</span>
                            </td>
                            <td width="1%" class="text-top">
                                <span style="font-size: 10pt;" color="#000000" >:</span>
                            </td>
                            <td width="69%">
                                <span style="font-size: 10pt;" color="#000000" >{{isset($data['nilaiDianut']) ? $data['nilaiDianut'] : '-'}}</span>
                            </td>
                        </tr>
                      
                    </table>
                </td>
            </tr>

            <tr>
                <td width="100%" colspan="4">
                    <table width="100%">
                        <tr>
                            <td width="100%" colspan="4"><span style="font-size: 10pt;" color="#000000" >Status Perkawinan</span></td>
                        </tr>
                        <tr>
                            <td width="20%">
                                <input type="checkbox" {{ isset($data['statusPerkawinan']) && $data['statusPerkawinan'] == 'Menikah' ? 'checked' : '' }} />
                                <span style="font-size: 9pt;" color="#000000" >Menikah</span>
                            </td>
                            <td width="20%">
                                <input type="checkbox" {{ isset($data['statusPerkawinan']) && $data['statusPerkawinan'] == 'Belum Menikah' ? 'checked' : '' }} />
                                <span style="font-size: 9pt;" color="#000000" >Belum menikah</span>
                            </td>
                            <td width="20%">
                                <input type="checkbox" {{ isset($data['statusPerkawinan']) && $data['statusPerkawinan'] == 'Duda/Janda' ? 'checked' : '' }} />
                                <span style="font-size: 9pt;" color="#000000" >Duda/janda</span>
                            </td>
                            
                        </tr>
                      
                    </table>
                </td>
            </tr>

            <tr>
                <td width="100%" colspan="4">
                    <table width="100%">
                        <tr>
                            <td width="100%" colspan="4"><span style="font-size: 10pt;" color="#000000" >Keluarga</span></td>
                        </tr>
                        <tr>
                            <td width="25%">
                                <input type="checkbox" {{ isset($data['keluarga']) && $data['keluarga'] == 'Tinggal Sendiri' ? 'checked' : '' }} />
                                <span style="font-size: 9pt;" color="#000000" >Tinggal Sendiri</span>
                            </td>
                            <td width="50%">
                                <input type="checkbox" {{ isset($data['keluarga']) && $data['keluarga'] == 'Tinggal Serumah' ? 'checked' : '' }} />
                                <span style="font-size: 9pt;" color="#000000" >Tinggal Serumah</span>
                            </td>
                            
                        </tr>
                      
                    </table>
                </td>
            </tr>
            
            <tr>
                <td width="100%" colspan="4">
                    <table width="100%">
                        <tr>
                            <td width="100%" colspan="4"><span style="font-size: 10pt;" color="#000000" >Tempat Tinggal</span></td>
                        </tr>
                        <tr>
                            <td width="20%">
                                <input type="checkbox" {{ isset($data['tempatTinggal']) && $data['tempatTinggal'] == 'Rumah' ? 'checked' : '' }} />
                                <span style="font-size: 9pt;" color="#000000" >Rumah</span>
                            </td>
                            <td width="20%">
                                <input type="checkbox" {{ isset($data['tempatTinggal']) && $data['tempatTinggal'] == 'Panti Asuhan' ? 'checked' : '' }} />
                                <span style="font-size: 9pt;" color="#000000" >Panti Asuhan</span>
                            </td>
                            <td width="20%">
                                <input type="checkbox" {{ isset($data['tempatTinggal']) && $data['tempatTinggal'] == 'Lainnya' ? 'checked' : '' }} />
                                <span style="font-size: 9pt;" color="#000000" >Lainnya</span>
                                <span style="font-size: 9pt;" color="#000000" >{{isset($data['keteranganTempat']) ? $data['keteranganTempat'] : '-'}}</span>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>

            <tr>
                <td width="100%" colspan="4">
                    <table width="100%">
                        <tr>
                            <td width="100%" colspan="4"><span style="font-size: 10pt;" color="#000000" >Status Psikologis</span></td>
                        </tr>
                        <tr>
                            <td width="20%">
                                <input type="checkbox" {{ isset($data['statusPsikologis']) && $data['statusPsikologis'] == 'Depresi' ? 'checked' : '' }} />
                                <span style="font-size: 9pt;" color="#000000" >Depresi</span>
                            </td>
                            <td width="20%">
                                <input type="checkbox" {{ isset($data['statusPsikologis']) && $data['statusPsikologis'] == 'Takut' ? 'checked' : '' }} />
                                <span style="font-size: 9pt;" color="#000000" >Takut</span>
                            </td>
                            <td width="20%">
                                <input type="checkbox" {{ isset($data['statusPsikologis']) && $data['statusPsikologis'] == 'Agresif' ? 'checked' : '' }} />
                                <span style="font-size: 9pt;" color="#000000" >Agresif</span>
                            </td>
                            <td width="20%">
                                <input type="checkbox" {{ isset($data['statusPsikologis']) && $data['statusPsikologis'] == 'Melukai diri sendiri' ? 'checked' : '' }}/>
                                <span style="font-size: 9pt;" color="#000000" >Melukai diri sendiri</span>
                            </td>
                            <td width="20%">
                                <input type="checkbox" {{ isset($data['statusPsikologis']) && $data['statusPsikologis'] == 'Tidak ada gejala' ? 'checked' : '' }} />
                                <span style="font-size: 9pt;" color="#000000" >Tidak ada gejala</span>
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
                                <span style="font-size: 10pt;" color="#000000" >Edukasi</span>
                            </td>
                            <td width="1%" class="text-top">
                                <span style="font-size: 10pt;" color="#000000" >:</span>
                            </td>
                            <td width="69%">
                                <span style="font-size: 10pt;" color="#000000" >{{isset($data['edukasi']) ? $data['edukasi'] : '-'}}</span>
                            </td>
                        </tr>
                      
                    </table>
                </td>
            </tr>

            <tr>
                <td width="100%" colspan="4">
                    <table width="100%">
                        <tr>
                            <td width="20%"><span style="font-size: 10pt;" color="#000000" >Hambatan edukasi</span></td>
                        </tr>
                        <tr>
                            <td width="10%" class="text-top">
                                <input type="checkbox" {{ isset($data['hambatanEdukasi']) && $data['hambatanEdukasi'] == 'Bahasa' ? 'checked' : '' }} />
                                <span style="font-size: 9pt;" color="#000000" >Bahasa</span>
                            </td>
                            <td width="1%">
                                <span style="font-size: 9pt;" color="#000000" >:</span>
                            </td>
                        <!-- </tr>
                        <tr> -->
                            <td width="69%">
                                <span style="font-size: 10pt;" color="#000000" >{{isset($data['keteranganBahasa']) ? $data['keteranganBahasa'] : '-'}}</span>
                            </td>
                        </tr>

                 
                      
                    </table>
                </td>
            </tr>

            <tr>
                <td width="100%" colspan="4">
                    <table width="100%">

                        <tr>
                            <td width="55%" class="text-top">
                                <input type="checkbox" {{ isset($data['hambatanEdukasi']) && $data['hambatanEdukasi'] == 'Cacat/fisik/kognitif' ? 'checked' : '' }} />
                                <span style="font-size: 9pt;" color="#000000" >Catat/fisik/kognitif (gangguan penglihatan / pendengaran lain) :</span>
                            </td>
                            <td width="40%">
                                <span style="font-size: 10pt;" color="#000000" >{{isset($data['keteranganCacat']) ? $data['keteranganCacat'] : '-'}}</span>
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
                        <span style="font-size: 10pt;" color="#000000" >Diagnosa Keperawatan</span>
                    </td>
                    <td width="1%" class="text-top">
                        <span style="font-size: 10pt;" color="#000000" >:</span>
                    </td>
                    <td width="69%">
                        <span style="font-size: 10pt;" color="#000000" >{{isset($data['diagnosa']) ? $data['diagnosa'] : '-'}}</span>
                    </td>                 
                </tr>
                <tr>
                    <td width="30%" class="text-top">
                        <span style="font-size: 10pt;" color="#000000" >Intervensi Keperawatan</span>
                    </td>
                    <td width="1%" class="text-top">
                        <span style="font-size: 10pt;" color="#000000" >:</span>
                    </td>
                    <td width="69%">
                        <span style="font-size: 10pt;" color="#000000" >{{isset($data['intervensiKeperawatan']) ? $data['intervensiKeperawatan'] : '-'}}</span>
                    </td>                 
                </tr>
                <tr>
                    <td width="30%" class="text-top">
                        <span style="font-size: 10pt;" color="#000000" >Implementasi Keperawatan</span>
                    </td>
                    <td width="1%" class="text-top">
                        <span style="font-size: 10pt;" color="#000000" >:</span>
                    </td>
                    <td width="69%">
                        <span style="font-size: 10pt;" color="#000000" >{{isset($data['implementasiKeperawatan']) ? $data['implementasiKeperawatan'] : '-'}}</span>
                    </td>                 
                </tr>
                <tr>
                <td width="100%" colspan="4" height="10"></td>
            </tr>
            </table>
        </td>
    </tr>

    <tr>
        <td width="100%" colspan="4">
            <table width="100%">
                    <tr>
                    <td width="100%">
                        <span style="font-size: 10pt;" color="#000000" class="judulLabel" >Tanggal dan jam : </span>
                        <span style="font-size: 9pt;" color="#000000" >{{isset($data['waktuDibuat']) ? date('d-m-Y H:i:s',strtotime($data['waktuDibuat'])) : '-'}}</span>
                    </td>   
                </tr>
                <tr> 
                    <td width="100%">
                        <span style="font-size: 10pt;" color="#000000" class="judulLabel" >Perawat : </span> 
                        <span style="font-size: 9pt;" color="#000000" >{{isset($data['perawat']) ? $data['perawat']['label'] : '-'}}</span>
                    </td>                                                   
                </tr>
            </table>
        </td>
    </tr>
            
                    
                        



</table>

@endsection
