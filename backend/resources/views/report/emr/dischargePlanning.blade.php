@extends('template.head-emr')
@section('title', 'EMR - Discharge Planning')
@section('about', 'Discharge Planning')

@push('style')
    <style>
        .header {
            font-size: 10pt !important;
            padding: 5px;
        }

        .td.th {
            border: 1px solid;
        }
    </style>
@endpush

@section('content')

    <table width="100%" cellspacing="0">
        <tr>
            <td colspan="7" style="padding:4px 0px;">
                <span style="font-size: 11pt;font-weight: bold;" color="#000000">A. Keadaan Saat Masuk</span>
            </td>
        </tr>

        <tr>
            <td width="100%" colspan="3">
                <table width="100%">
                    <tr>
                        <td width="15%">
                            <span style="font-size: 10pt;" color="#000000">1. Indikasi Rawat Inap</span>
                        </td>
                        <td width="1%">
                            <span style="font-size: 10pt;" color="#000000">:</span>
                        </td>
                        <td width="20%">
                            <span style="font-size: 10pt;"
                                color="#000000">{{ isset($data['indikasiRanap']) ? $data['indikasiRanap'] : '' }}</span>
                        </td>


                    </tr>
                </table>
            </td>
        </tr>

        <tr>
            <td width="100%" colspan="2">
                <table width="100%">
                    <tr>
                        <td width="15%">
                            <span style="font-size: 10pt;" color="#000000">2. Pemeriksaan Fisik</span>
                        </td>
                        <td width="1%">
                            <span style="font-size: 10pt;" color="#000000">:</span>
                        </td>
                        <td width="20%">
                            <span style="font-size: 10pt;"
                                color="#000000">{{ isset($data['pemeriksaanFisik']) ? $data['pemeriksaanFisik'] : '' }}</span>
                        </td>


                    </tr>
                </table>
            </td>
        </tr>

        <tr>
            <td width="100%" colspan="2">
                <table width="100%">
                    <tr>
                        <td width="15%">
                            <span style="font-size: 10pt;" color="#000000">3. Pemeriksaan Penunjang</span>
                        </td>
                        <td width="1%">
                            <span style="font-size: 10pt;" color="#000000">:</span>
                        </td>
                        <td width="20%">
                            <span style="font-size: 10pt;"
                                color="#000000">{{ isset($data['pemeriksaanPenunjang']) ? $data['pemeriksaanPenunjang'] : '' }}</span>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>

        <tr>
            <td width="100%" colspan="2">
                <table width="100%">
                    <tr>
                        <td width="15%">
                            <span style="font-size: 10pt;" color="#000000">4. Diet</span>
                        </td>
                        <td width="1%">
                            <span style="font-size: 10pt;" color="#000000">:</span>
                        </td>
                        <td width="20%">
                            <span style="font-size: 10pt;"
                                color="#000000">{{ isset($data['diet']) ? $data['diet'] : '' }}</span>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>

        <tr>
            <td width="100%" colspan="3">
                <table width="100%">
                    <tr>
                        <td width="15%">
                            <span style="font-size: 10pt;" color="#000000">5. Terapi/Pengobatan yang diberikan</span>
                        </td>
                        <td width="1%">
                            <span style="font-size: 10pt;position: relative;right: 2px;" color="#000000">:</span>
                        </td>
                        <td width="20%">
                            <span style="font-size: 10pt;"
                                color="#000000">{{ isset($data['terapiPengobatan']) ? $data['terapiPengobatan'] : '' }}</span>
                        </td>


                    </tr>
                </table>
            </td>
        </tr>

        <tr>
            <td width="100%" colspan="2">
                <table width="100%">
                    <tr>
                        <td width="15%">
                            <span style="font-size: 10pt;" color="#000000">6. Alergi
                                <span>
                        </td>
                        <td width="1%">
                            <span style="font-size: 10pt;" color="#000000">:</span>
                        </td>
                        <td width="20%">
                            <input type="checkbox"
                                {{ isset($data['riwayatAlergi']) && $data['riwayatAlergi'] == 'Ya' ? 'checked' : '' }} />
                            <span style="font-size: 10pt;" color="#000000">Ya, </span>
                            <span style="font-size: 10pt;" color="#000000">{{isset($data['keteranganAlergi']) ? $data['keteranganAlergi'] : ''}}</span>
                        </td>


                    </tr>
                </table>
            </td>
        </tr>

        <tr>
            <td width="100%" colspan="2">
                <table width="100%">
                    <tr>
                        <td width="15%">
                            <span style="font-size: 10pt;" color="#000000"><span>
                        </td>
                        <td width="1%">
                            <span style="font-size: 10pt;" color="#000000"></span>
                        </td>
                        <td width="20%">
                            <input type="checkbox"
                                {{ isset($data['riwayatAlergi']) && $data['riwayatAlergi'] == 'Tidak' ? 'checked' : '' }} />
                            <span style="font-size: 10pt;" color="#000000">Tidak</span>
                        </td>


                    </tr>
                </table>
            </td>
        </tr>

        <tr>
            <td width="100%" colspan="2">
                <table width="100%">
                    <tr>
                        <td width="15%" style="padding-left: 15px">
                            <span style="font-size: 10pt;" color="#000000">Reaksi<span>
                        </td>
                        <td width="1%">
                            <span style="font-size: 10pt;" color="#000000">:</span>
                        </td>
                        <td width="20%">
                            <span style="font-size: 10pt;"
                                color="#000000">{{ isset($data['keteranganResiko']) ? $data['keteranganResiko'] : '' }}</span>
                        </td>


                    </tr>
                </table>
            </td>
        </tr>

        <tr>
            <td width="100%" colspan="2">
                <table width="100%">
                    <tr>
                        <td width="15%">
                            <span style="font-size: 10pt;" color="#000000">7. Prosedur Operasi yang sudah dilakukan</span>
                        </td>
                        <td width="1%">
                            <span style="font-size: 10pt;" color="#000000">:</span>
                        </td>
                        <td width="20%">
                            <span style="font-size: 10pt;"
                                color="#000000">{{ isset($data['tindakanSudahdilakukan']) ? $data['tindakanSudahdilakukan'] : '' }}</span>
                        </td>


                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <table width="100%" cellspacing="0">
        <tr>
            <td colspan="7">
                <span style="font-size: 11pt;font-weight: bold;" color="#000000">B. Keadaan Saat Pulang</span>
            </td>
        </tr>

        <tr>
            <td width="100%" colspan="3">
                <table width="100%">
                    <tr>
                        <td width="10%" style="vertical-align: top">
                            <span style="font-size: 10pt;" color="#000000">1. Kondisi
                                Pasien</span>
                        </td>
                        <td width="1%" style="vertical-align: top">
                            <span style="font-size: 10pt;" color="#000000">:</span>
                        </td>
                        <td width="40%">
                            <span style="font-size: 10pt;"
                                color="#000000">{{ isset($data['ku_Pasien']) ? $data['ku_Pasien'] : '' }}</span>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>

        <tr>
            <td>
                <span style="font-size: 10pt;margin-left: 1.2rem;" color="#000000">Tanda Vital</span>
            </td>
        </tr>
        <tr>
            <td>
                <table style=" width:95%;position: relative;left: 27px;">
                    <tr style="">
                        <td style="padding-bottom:8px; width:13%">
                            <span style="font-size: 10pt;">Tensi</span>
                        </td>
                        <td style="padding-bottom:8px; width:1%">
                            <span style="font-size: 10pt;">:</span>
                        </td>
                        <td style="padding-bottom:8px; width: 20%;">
                            <span style="font-size: 10pt;">{{ isset($data['tekananDarah']) ? $data['tekananDarah'] : '-' }} mmHg</span>
                        </td>

                        <td style="padding-bottom:8px; width:13%">
                            <span style="font-size: 10pt;">Berat Badan</span>
                        </td>
                        <td style="padding-bottom:8px; width:1%">
                            <span style="font-size: 10pt;">:</span>
                        </td>
                        <td style="padding-bottom:8px; width: 20%;">
                            <span style="font-size: 10pt;">{{ isset($data['beratBadan']) ? $data['beratBadan'] : '-' }} Kg</span>
                        </td>

                        <td style="padding-bottom:8px; width:13%">
                            <span style="font-size: 10pt;">Tinggi Badan</span>
                        </td>
                        <td style="padding-bottom:8px; width:1%">
                            <span style="font-size: 10pt;">:</span>
                        </td>
                        <td style="padding-bottom:8px; width: 20%;">
                            <span style="font-size: 10pt;">{{ isset($data['tinggiBadan']) ? $data['tinggiBadan'] : '-' }} Cm</span>
                        </td>
                    </tr>
                    <tr style="">
                        <td style="padding-bottom:8px; width:10%">
                            <span style="font-size: 10pt;">Nadi</span>
                        </td>
                        <td style="padding-bottom:8px; width:1%">
                            <span style="font-size: 10pt;">:</span>
                        </td>
                        <td style="padding-bottom:8px; width: 20%;">
                            <span style="font-size: 10pt;">{{ isset($data['nadi']) ? $data['nadi'] : '-' }}  x/menitmHg</span>
                        </td>

                        <td style="padding-bottom:8px; width:10%">
                            <span style="font-size: 10pt;">Pernafasan</span>
                        </td>
                        <td style="padding-bottom:8px; width:1%">
                            <span style="font-size: 10pt;">:</span>
                        </td>
                        <td style="padding-bottom:8px; width: 20%;">
                            <span style="font-size: 10pt;">{{ isset($data['pernapasan']) ? $data['pernapasan'] : '-' }} x/menit</span>
                        </td>

                        <td style="padding-bottom:8px; width:10%">
                            <span style="font-size: 10pt;">Suhu</span>
                        </td>
                        <td style="padding-bottom:8px; width:1%">
                            <span style="font-size: 10pt;">:</span>
                        </td>
                        <td style="padding-bottom:8px; width: 20%;">
                            <span style="font-size: 10pt;">{{ isset($data['suhu']) ? $data['suhu'] : '-' }} C</span>
                        </td>
                    </tr>
                    <tr style="">
                        <td style="padding-bottom:8px; width:10%">
                            <span style="font-size: 10pt;">Skor Nyeri</span>
                        </td>
                        <td style="padding-bottom:8px; width:1%">
                            <span style="font-size: 10pt;">:</span>
                        </td>
                        <td style="padding-bottom:8px;">
                            <span style="font-size: 10pt;">{{ isset($data['nyeri']) ? $data['nyeri'] : '-' }}</span>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>

       
        <tr>
            <td width="100%" colspan="3">
                <table width="100%">
                    <tr>
                        <td width="10%" style="vertical-align: top">
                            <span style="font-size: 10pt;" color="#000000">2. Edukasi</span>
                        </td>
                        <td width="1%" style="vertical-align: top">
                            <span style="font-size: 10pt;" color="#000000">:</span>
                        </td>
                        <td width="40%">
                            <span style="font-size: 10pt;"
                                color="#000000">{{ isset($data['edukasi0']) ? $data['edukasi0'] : '-' }}</span>
                        </td>


                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td width="100%" colspan="3">
                <table width="100%" style="margin-top: 8px;">
                    <tr>
                        <td width="15%">
                            <span style="font-size: 10pt;" color="#000000">3. Tindakan Lanjutan</span>
                        </td>
                        <td width="1%">
                            <span style="font-size: 10pt;" color="#000000"></span>
                        </td>
                        <td width="20%">
                            <span style="font-size: 10pt;" color="#000000"></span>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td>
                <table style="width: 95%;position: relative;left: 1.3rem;">
                    <tbody>
                        <tr>
                            <td style="width: 25.2% ">
                                <input type="checkbox"
                                    {{ isset($data['tindakanLanjutan_0']) && $data['tindakanLanjutan_0'] == 'Pulang' ? 'checked' : '' }} />
                                <span style="font-size: 10pt;" color="#000000">Pulang</span>
                            </td>
                            <td style="width: 40.8%;">
                                <input type="checkbox"
                                    {{ isset($data['tindakanLanjutan_1']) && $data['tindakanLanjutan_1'] == 'Pulag Atas Permintaan Sendiri' ? 'checked' : '' }} />
                                <span style="font-size: 10pt;" color="#000000">Pulag Atas Permintaan Sendiri</span>
                            </td>
                            <td style="width:34%;">
                                <input type="checkbox"
                                    {{ isset($data['tindakanLanjutan_2']) && $data['tindakanLanjutan_2'] == 'Sembuh' ? 'checked' : '' }} />
                                <span style="font-size: 10pt;" color="#000000">Sembuh</span>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <input type="checkbox"
                                    {{ isset($data['tindakanLanjutan_3']) && $data['tindakanLanjutan_3'] == 'Rujuk' ? 'checked' : '' }} />
                                <span style="font-size: 10pt;" color="#000000">Rujuk</span>
                            </td>
                            <td>
                                <input type="checkbox"
                                    {{ isset($data['tindakanLanjutan_4']) && $data['tindakanLanjutan_4'] == 'Berobat Jalan / Kontrol' ? 'checked' : '' }} />
                                <span style="font-size: 10pt;" color="#000000">Berobat Jalan / Kontrol</span>
                            </td>
                            <td>
                                <input type="checkbox"
                                    {{ isset($data['tindakanLanjutan_5']) && $data['tindakanLanjutan_5'] == 'Pindah RS Lain' ? 'checked' : '' }} />
                                <span style="font-size: 10pt;" color="#000000">Pindah RS Lain</span>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <input type="checkbox"
                                    {{ isset($data['tindakanLanjutan_6']) && $data['tindakanLanjutan_6'] == 'Meninggal' ? 'checked' : '' }} />
                                <span style="font-size: 10pt;" color="#000000">Meninggal</span>
                            </td>
                            <td colspan="2">
                                <input type="checkbox"
                                    {{ isset($data['tindakanLanjutan_7']) && $data['tindakanLanjutan_7'] == 'Meninggalkan RS tanpa Ijin' ? 'checked' : '' }} />
                                <span style="font-size: 10pt;" color="#000000">Meninggalkan RS tanpa Ijin</span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </td>
        </tr>

        <tr style="margin-top: 8px;">
            <td width="100%" colspan="3">
                <table width="100%" style="margin-top: 8px;">
                    <tr>
                        <td width="15%">
                            <span style="font-size: 10pt;" color="#000000">4. Berkas yang akan dibawakan</span>
                        </td>
                        <td width="1%">
                            <span style="font-size: 10pt;" color="#000000"></span>
                        </td>
                        <td width="20%">
                            <span style="font-size: 10pt;" color="#000000"></span>
                        </td>


                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td>
                <table style="width: 95%;position: relative;left: 1.3rem;">
                    <tbody>
                        <tr>
                            <td>
                                <input type="checkbox"
                                    {{ isset($data['berkasPulang_0']) && $data['berkasPulang_0'] == 'Surat Kontrol' ? 'checked' : '' }} />
                                <span style="font-size: 10pt;" color="#000000">Surat Kontrol</span>
                            </td>
                            <td>
                                <input type="checkbox"
                                    {{ isset($data['berkasPulang_1']) && $data['berkasPulang_1'] == 'Laboratorium' ? 'checked' : '' }} />
                                <span style="font-size: 10pt;" color="#000000">Laboratorium</span>
                            </td>
                            <td>
                                <input type="checkbox"
                                    {{ isset($data['berkasPulang_2']) && $data['berkasPulang_2'] == 'Surat Keterangan Sakit' ? 'checked' : '' }} />
                                <span style="font-size: 10pt;" color="#000000">Surat Keterangan Sakit</span>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <input type="checkbox"
                                    {{ isset($data['berkasPulang_3']) && $data['berkasPulang_3'] == 'Dower Catheter' ? 'checked' : '' }} />
                                <span style="font-size: 10pt;" color="#000000">Dower Catheter</span>
                            </td>
                            <td>
                                <input type="checkbox"
                                    {{ isset($data['berkasPulang_4']) && $data['berkasPulang_4'] == 'EKG' ? 'checked' : '' }} />
                                <span style="font-size: 10pt;" color="#000000">EKG</span>
                            </td>
                            <td>
                                <input type="checkbox"
                                    {{ isset($data['berkasPulang_5']) && $data['berkasPulang_5'] == 'NGT' ? 'checked' : '' }} />
                                <span style="font-size: 10pt;" color="#000000">NGT</span>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <input type="checkbox"
                                    {{ isset($data['berkasPulang_6']) && $data['berkasPulang_6'] == 'Radiologi' ? 'checked' : '' }} />
                                <span style="font-size: 10pt;" color="#000000">Radiologi</span>
                            </td>
                            <td>
                                <input type="checkbox"
                                    {{ isset($data['berkasPulang_7']) && $data['berkasPulang_7'] == 'Surat Keterangan Meninggal' ? 'checked' : '' }} />
                                <span style="font-size: 10pt;" color="#000000">Surat Keterangan Meninggal</span>
                            </td>
                            <td>
                                <input type="checkbox"
                                    {{ isset($data['berkasPulang_8']) && $data['berkasPulang_8'] == 'Lain-lain' ? 'checked' : '' }} />
                                <span style="font-size: 10pt;" color="#000000">Lain-lain</span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </td>
        </tr>

        <tr>
            <td width="100%" colspan="3">
                <table width="100%" style="margin-top: 8px;">
                    <tr>
                        <td width="15%">
                            <span style="font-size: 10pt;" color="#000000">5. Obat yang
                                dilanjutkan</span>
                        </td>
                        <td width="1%">
                            <span style="font-size: 10pt;" color="#000000"></span>
                        </td>
                        <td width="20%">
                            <span style="font-size: 10pt;" color="#000000"></span>
                        </td>


                    </tr>
                </table>
            </td>
        </tr>

        <tr>
            <td>
                <table style="width: 95%;position: relative;left: 1.3rem;" border="1" bordercolor="#000000"
                    class="garis6">
                    <tr bgcolor="#f08080">
                        <th style="width: 5%">No</th>
                        <th style="width: 30%">Nama Obat</th>
                        <th style="width: 15%">Frekuensi</th>
                        <th style="width: 15%">Tanggal</th>
                        <th style="width: 50%">Keterangan</th>
                    </tr>

                    @if (isset($data['details']))
                        @foreach ($data['details'] as $cek)
                            <tr>
                                <td>{{ $cek['no'] }}</td>
                                <td>{{ isset($cek['namaObat']) ? $cek['namaObat'] : '-' }}</td>
                                <td>{{ isset($cek['frekuensi']) ? $cek['frekuensi'] : '-' }}</td>
                                <td>{{ isset($cek['tgl']) ? date('d-m-Y', strtotime($cek['tgl'])) : '-' }}</td>
                                <td>{{ isset($cek['keterangan']) ? $cek['keterangan'] : '-' }}</td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="5" style="text-align: center"> Tidak ada data </td>
                        </tr>
                    @endif


                </table>
            </td>
        </tr>


        <table width="100%" cellspacing="0">

            <tr>
                <td colspan="8">
                    <table width="100%">
                        <tr>
                            <td width="33%">
                                <span style="font-size: 10pt;" color="#000000"></span>
                            </td>
                            <td width="33%">
                                <span style="font-size: 10pt;" color="#000000"></span>
                            </td>
                            <td width="33%" align="center">
                                <span style="font-size: 10pt;" color="#000000">Bandung, {{ isset($data['created_at']) ? date('d-m-Y', strtotime($data['created_at'])) : '-' }}</span>
                            </td>
                        </tr>
                        <tr>
                            <td width="33%" align="center">
                                <span style="font-size: 10pt;" color="#000000">DPJP</span>
                            </td>
                            <td width="33%" align="center">
                                <span style="font-size: 10pt;" color="#000000">Perawat</span>
                            </td>
                            <td width="33%" align="center">
                                <span style="font-size: 10pt;" color="#000000">Petugas/Keluarga Penerima*</span>
                            </td>
                        </tr>
                        {{-- <tr>
                    <td width="33%" align="center" height="50">
                        @forelse($dataimg as $d)
                            @if ($d->emrdfk == 1)
                                <img src="{{ $d->image }}" width="170" height="140"
                                    alt="TTD" />
                            @break
                        @endif
                    @empty
                        <div style="height:75px"></div>
                    @endforelse
                </td>
                <td width="33%" align="center" height="50">
                    @forelse($dataimg as $d)
                        @if ($d->emrdfk == 2)
                            <img src="{{ $d->image }}" width="170" height="140"
                                alt="TTD" />
                        @break
                    @endif
                @empty
                    <div style="height:75px"></div>
                @endforelse
            </td>
            <td width="33%" align="center" height="50">
                @forelse($dataimg as $d)
                    @if ($d->emrdfk == 3)
                        <img src="{{ $d->image }}" width="170" height="140"
                            alt="TTD" />
                    @break
                @endif
            @empty
                <div style="height:75px"></div>
            @endforelse
           </td> --}}
                </td>
            </tr>

            <tr>
                <td width="33%" align="center">
                    <span style="font-size: 10pt;" color="#000000">( {{isset($data['dokter']) ? $data['dokter'] : '-'}} )</span>
                    <br>
                    {{-- <span style="font-size: 10pt;" color="#000000">Tanda tangan & Nama Terang</span> --}}
                </td>
                <td width="33%" align="center">
                    <span style="font-size: 10pt;" color="#000000">( {{isset($data['perawat']) ? $data['perawat']['label'] : '-'}} )</span>
                    <br>
                    {{-- <span style="font-size: 10pt;" color="#000000">Tanda tangan & Nama Terang</span> --}}
                </td>
                <td width="33%" align="center">
                    <span style="font-size: 10pt;" color="#000000">( {{isset($data['namaKeluarga']) ? $data['namaKeluarga'] : '-'}} )</span>
                    <br>
                    {{-- <span style="font-size: 10pt;" color="#000000">Tanda tangan & Nama Terang</span> --}}
                </td>
            </tr>
        </table>

    @endsection
