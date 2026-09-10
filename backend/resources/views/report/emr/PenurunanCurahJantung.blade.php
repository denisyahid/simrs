@extends('template.head-emr')
@section('title', 'EMR - Penurunan Curah Jantung')
@section('about', 'Penurunan Curah Jantung')
@section('content')

    <table width="100%" cellspacing="0">
        <tr>
            <td width="100%">
                <table width="100%" border="1" bordercolor="#000000" class="garis6">
                    <tr>
                        <th style="font-weight: bold;text-align: center;">Standar Diagnosa Keperawatan Indonesia (SDKI)</th>
                        <th style="font-weight: bold;text-align: center;">Standar Luar Keperawatan Indonesia (SLKI)</th>
                        <th style="font-weight: bold;text-align: center">Standar Intervensi Keperawatan Indonesia (SIKI)</th>
                        <th style="font-weight: bold;text-align: center">Evaluasi</th>
                    </tr>

                    <tr>
                        <td style="font-weight: 100;text-align: left;">
                            <div><span style="font-weight: bold;">Definisi :</span></div>
                            <div><span>Ketidakkuatan jantung </br>
                                    memompa darah untuk memenuhi </br>
                                    kebutuhan metabolism tubuh.</span>
                            </div>
                            <div style="margin-top:10px"><span style="font-weight: bold;">Berhubungan dengan :</span></div>
                            <div style="margin: 5px 0px 5px 0px;">
                                <input type="checkbox" class="k-checkbox" {{ isset($data['hubunganDengan_0']) && $data['hubunganDengan_0']['code'] == 'PIJ' ? 'checked' : '' }}>
                                <label class="k-checkbox-label" >Perubahan irama jantung</label>
                            </div>
                            <div style="margin: 5px 0px 5px 0px;">
                                <input type="checkbox" class="k-checkbox" {{ isset($data['hubunganDengan_1']) && $data['hubunganDengan_1']['code'] == 'PFJ' ? 'checked' : '' }} >
                                <label class="k-checkbox-label" >Perubahan frekuensi jantung</label>
                            </div>
                            <div style="margin: 5px 0px 5px 0px;">
                                <input type="checkbox" class="k-checkbox" {{ isset($data['hubunganDengan_2']) && $data['hubunganDengan_2']['code'] == 'PK' ? 'checked' : '' }} >
                                <label class="k-checkbox-label" >Perubahan kontraktilitas</label>
                            </div>
                            <div style="margin: 5px 0px 5px 0px;">
                                <input type="checkbox" class="k-checkbox" {{ isset($data['hubunganDengan_3']) && $data['hubunganDengan_3']['code'] == 'PPR' ? 'checked' : '' }} >
                                <label class="k-checkbox-label" >Perubahan preload</label>
                                <span style="font-size: 10pt;" color="#000000"></span>
                            </div>
                            <div style="margin: 5px 0px 5px 0px;">
                                <input type="checkbox" class="k-checkbox" {{ isset($data['hubunganDengan_4']) && $data['hubunganDengan_4']['code'] == 'PAF' ? 'checked' : '' }} >
                                <label class="k-checkbox-label" >Perubahan afterload</label>
                                <span style="font-size: 10pt;" color="#000000"></span>
                            </div>
                            <div style="margin: 5px 0px 5px 0px;">
                                <label class="k-checkbox-label" >Lainnya : </label>
                                <span style="font-size: 10pt;" color="#000000"></span>
                            </div>

                            <div style="margin-top:10px"><span style="font-weight: bold;">Data sujektif :</span></div>
                            <div style="margin: 5px 0px 5px 0px;">
                                <input type="checkbox" class="k-checkbox" {{ isset($data['dataSubjektif_0']) && $data['dataSubjektif_0']['code'] == 'PAL' ? 'checked' : '' }} >
                                <label class="k-checkbox-label" >Perubahan irama jantung (palpitasi)</label>
                            </div>
                            <div style="margin: 5px 0px 5px 0px;">
                                <input type="checkbox" class="k-checkbox" {{ isset($data['dataSubjektif_1']) && $data['dataSubjektif_1']['code'] == 'PP' ? 'checked' : '' }} >
                                <label class="k-checkbox-label" >Perubahan preload (lelah)</label>
                            </div>
                            <div style="margin: 5px 0px 5px 0px;">
                                <input type="checkbox" class="k-checkbox" {{ isset($data['dataSubjektif_2']) && $data['dataSubjektif_2']['code'] == 'PA' ? 'checked' : '' }} >
                                <label class="k-checkbox-label" >Perubahan afterload (dyspnea)</label>
                            </div>
                            <div style="margin: 5px 0px 5px 0px;">
                                <input type="checkbox" class="k-checkbox" {{ isset($data['dataSubjektif_3']) && $data['dataSubjektif_3']['code'] == 'PND' ? 'checked' : '' }} >
                                <label class="k-checkbox-label" >Perubahan kontraktilitas (paroksimal nocturnal dyspnea(PND) , Ortopnea , batuk)</label>
                            </div>
                            <div style="margin: 5px 0px 5px 0px;">
                                <label class="k-checkbox-label" >Lainnya : {{ isset($data['subjektifLainnya']) ? $data['subjektifLainnya'] : '-' }}</label>
                                <span style="font-size: 10pt;" color="#000000"></span>
                            </div>
                            <div style="margin-top:10px"><span style="font-weight: bold;">Data objektif :</span></div>
                            <div style="margin: 5px 0px 5px 0px;">
                                <input type="checkbox" class="k-checkbox" {{ isset($data['dataObjektif_0']) && $data['dataObjektif_0']['code'] == 'BR' ? 'checked' : '' }}>
                                <label class="k-checkbox-label" >Bradikardi</label>
                            </div>
                            <div style="margin: 5px 0px 5px 0px;">
                                <input type="checkbox" class="k-checkbox" {{ isset($data['dataObjektif_1']) && $data['dataObjektif_1']['code'] == 'TKK' ? 'checked' : '' }}>
                                <label class="k-checkbox-label" >Takikardi</label>
                            </div>
                            <div style="margin: 5px 0px 5px 0px;">
                                <input type="checkbox" class="k-checkbox" {{ isset($data['dataObjektif_2']) && $data['dataObjektif_2']['code'] == 'GEA' ? 'checked' : '' }}>
                                <label class="k-checkbox-label" >Gambaran EKG aritmia</label>
                            </div>
                            <div style="margin: 5px 0px 5px 0px;">
                                <input type="checkbox" class="k-checkbox" {{ isset($data['dataObjektif_3']) && $data['dataObjektif_3']['code'] == 'ED' ? 'checked' : '' }}>
                                <label class="k-checkbox-label" >Edema</label>
                            </div>
                            <div style="margin: 5px 0px 5px 0px;">
                                <input type="checkbox" class="k-checkbox" {{ isset($data['dataObjektif_4']) && $data['dataObjektif_4']['code'] == 'DVJ' ? 'checked' : '' }}>
                                <label class="k-checkbox-label" >Distensi vena jugularis</label>
                            </div>
                            <div style="margin: 5px 0px 5px 0px;">
                                <input type="checkbox" class="k-checkbox" {{ isset($data['dataObjektif_5']) && $data['dataObjektif_5']['code'] == 'CVP+' ? 'checked' : '' }}>
                                <label class="k-checkbox-label" >Central venous pressure (CVP) meningkat</label>
                            </div>
                            <div style="margin: 5px 0px 5px 0px;">
                                <input type="checkbox" class="k-checkbox" {{ isset($data['dataObjektif_6']) && $data['dataObjektif_6']['code'] == 'CVP-' ? 'checked' : '' }}>
                                <label class="k-checkbox-label" >Central venous pressure (CVP) menurun</label>
                            </div>
                            <div style="margin: 5px 0px 5px 0px;">
                                <input type="checkbox" class="k-checkbox" {{ isset($data['dataObjektif_7']) && $data['dataObjektif_7']['code'] == 'TD-' ? 'checked' : '' }}>
                                <label class="k-checkbox-label" >Tekanan darah meningkat atau menurun</label>
                            </div>
                            <div style="margin: 5px 0px 5px 0px;">
                                <input type="checkbox" class="k-checkbox" {{ isset($data['dataObjektif_8']) && $data['dataObjektif_8']['code'] == 'NPT' ? 'checked' : '' }}>
                                <label class="k-checkbox-label" >Nadi perifer teraba lemah</label>
                            </div>
                            <div style="margin: 5px 0px 5px 0px;">
                                <input type="checkbox" class="k-checkbox" {{ isset($data['dataObjektif_9']) && $data['dataObjektif_9']['code'] == 'CRT' ? 'checked' : '' }}>
                                <label class="k-checkbox-label" >Capillary refill time >3 detik</label>
                            </div>
                            <div style="margin: 5px 0px 5px 0px;">
                                <input type="checkbox" class="k-checkbox" {{ isset($data['dataObjektif_10']) && $data['dataObjektif_10']['code'] == 'OL' ? 'checked' : '' }}>
                                <label class="k-checkbox-label" >Oliguria </label>
                            </div>
                            <div style="margin: 5px 0px 5px 0px;">
                                <input type="checkbox" class="k-checkbox" {{ isset($data['dataObjektif_11']) && $data['dataObjektif_11']['code'] == 'WKP' ? 'checked' : '' }}>
                                <label class="k-checkbox-label" >Warna kulit pucat /sianosis</label>
                            </div>
                            <div style="margin: 5px 0px 5px 0px;">
                                <label class="k-checkbox-label" >Lainnya : {{ isset($data['objektifLainnya']) ? $data['objektifLainnya'] : '-' }}</label>
                                <span style="font-size: 10pt;" color="#000000"></span>
                            </div>
                            <div style="margin-top:10px"><span style="font-weight: bold;">Tanda-tanda vital :</span></div>
                            <div style="margin: 5px 0px 5px 0px;">
                                <div class="grid_2">
                                    <span>TD</span>:<span>
                                        <span style="font-size: 10pt;" color="#000000">{{ isset($data['tekananDarah']) ? $data['tekananDarah'] : '-' }}</span>
                                    </span>
                                </div>
                                <div class="grid_10">
                                </div>
                            </div>
                            <div style="margin: 5px 0px 5px 0px;">
                                <div class="grid_2">
                                    <span>HR</span>:<span>
                                        <span style="font-size: 10pt;" color="#000000"> {{ isset($data['nadi']) ? $data['nadi'] : '-' }}</span>
                                    </span>

                                </div>
                                <div class="grid_10">
                                </div>
                            </div>
                            <div style="margin: 5px 0px 5px 0px;">
                                <div class="grid_2">
                                    <span>RR</span>:<span>
                                        <span style="font-size: 10pt;" color="#000000">{{ isset($data['pernapasan']) ? $data['pernapasan'] : '-' }}</span>
                                    </span>

                                </div>
                                <div class="grid_10">
                                </div>
                            </div>
                            <div style="margin: 5px 0px 5px 0px;">
                                <div class="grid_2">
                                    <span>S</span>:<span>
                                        <span style="font-size: 10pt;" color="#000000">{{ isset($data['suhu']) ? $data['suhu'] : '-' }}</span>
                                    </span>

                                </div>
                                <div class="grid_10">
                                  
                                </div>
                            </div>
                            <div style="margin: 5px 0px 5px 0px;">
                                <div class="grid_2">
                                    <span>SPO2</span>:<span>
                                        <span style="font-size: 10pt;" color="#000000">{{ isset($data['spo2']) ? $data['spo2'] : '-' }}</span>
                                    </span>
                                </div>
                                <div class="grid_10">
                                </div>
                            </div>
                            <div style="margin: 5px 0px 5px 0px;">
                                <div class="grid_12">
                                    Pemeriksaan penunjang
                                </div>
                                <div class="grid_12">
                                    <span style="font-size: 10pt;" color="#000000">{{ isset($data['pemeriksaanPenunjang']) ? $data['pemeriksaanPenunjang'] : '-' }}</span>
                                </div>
                            </div>
                        </td>
                        <td style="font-weight: 100;text-align: left;vertical-align: text-top;">
                            <div><span style="font-weight: bold;">Curah jantung (L.02008)</span></div>
                            <div>
                                <span>Jantung kuat untuk memenuhi kebutuhan metabolism tubuh</span>
                            </div>
                            <div style="margin-top: 10px"><span style="font-weight: bold;">Kriteria hasil:</span></div>
                            <div style="margin: 5px 0px 5px 0px;">
                                <div class="grid_6">
                                    1. Kekuatan nadi perifer :
                                </div>
                                <div class="grid_6">
                                    Meningkat
                                </div>
                            </div>
                            <div style="margin: 5px 0px 5px 0px;">
                                <div class="grid_12">
                                    2. Ejection Fraction (EF) :
                                </div>
                                <div class="grid_12">
                                    Meningkat

                                </div>
                                <div class="grid_12">
                                    3. Tidak ada Bradikardi
                                </div>
                                <div class="grid_12">
                                    4. Tidak ada Takikardi
                                </div>
                                <div class="grid_12">
                                    5. Distensi vena jugularis menurun
                                </div>
                                <div class="grid_12">
                                    6. Tekanan darah Membaik
                                </div>
                                <div class="grid_12">
                                    <span style="font-size: 10pt;" color="#000000">Kriteria Hasil : </span>
                                </div>
                                <div class="grid_12">
                                    <span style="font-size: 10pt;" color="#000000">{{ isset($data['kriteriaHasil']) ? $data['kriteriaHasil'] : '-' }}</span>
                                </div>
                            </div>

                        </td>
                        <td style="font-weight: 100;text-align: left;vertical-align: text-top;">
                            <div class="grid_12">
                                Penuruan curah jantung
                            </div>
                            <div class="grid_12">
                                Perawatan jantung
                            </div>
                            <div style="margin-top:10px"><span style="font-weight: bold;">Observasi :</span></div>
                            <div style="margin: 5px 0px 5px 0px;">
                                <input type="checkbox" class="k-checkbox" {{ isset($data['Observasi_0']) && $data['Observasi_0']['code'] == 'MTV' ? 'checked' : '' }}>
                                <label class="k-checkbox-label" >Monitoring tanda vital</label>
                            </div>
                            <div style="margin: 5px 0px 5px 0px;">
                                <input type="checkbox" class="k-checkbox" {{ isset($data['Observasi_1']) && $data['Observasi_1']['code'] == 'MKN' ? 'checked' : '' }}>
                                <label class="k-checkbox-label" >Monitor keluhan nyeri dada</label>
                            </div>
                            <div style="margin: 5px 0px 5px 0px;">
                                <input type="checkbox" class="k-checkbox" {{ isset($data['Observasi_2']) && $data['Observasi_2']['code'] == 'MGI' ? 'checked' : '' }}>
                                <label class="k-checkbox-label" >Monitor gambaran irama jantung</label>
                            </div>
                            <div style="margin: 5px 0px 5px 0px;">
                                <input type="checkbox" class="k-checkbox" {{ isset($data['Observasi_3']) && $data['Observasi_3']['code'] == 'OEE' ? 'checked' : '' }}>
                                <label class="k-checkbox-label" >Observasi edema ekstremitas</label>
                            </div>
                            <div style="margin: 5px 0px 5px 0px;">
                                <label class="k-checkbox-label" >Lainnya : </label>
                                <span style="font-size: 10pt;" color="#000000">{{ isset($data['keteranganObservasi']) ? $data['keteranganObservasi'] : '-' }}</span>
                            </div>

                            <div style="margin-top:10px"><span style="font-weight: bold;">Terapeutik :</span></div>
                            <div style="margin: 5px 0px 5px 0px;">
                                <input type="checkbox" class="k-checkbox" {{ isset($data['Terapeutik_0']) && $data['Terapeutik_0']['code'] == 'PPF' ? 'checked' : '' }}>
                                <label class="k-checkbox-label" >Posisikan pasien semi fowler</label>
                            </div>
                            <div style="margin: 5px 0px 5px 0px;">
                                <input type="checkbox" class="k-checkbox" {{ isset($data['Terapeutik_1']) && $data['Terapeutik_1']['code'] == 'PPT' ? 'checked' : '' }}>
                                <label class="k-checkbox-label" >Posisikan pasien trendelenburg</label>
                            </div>
                            <div style="margin: 5px 0px 5px 0px;">
                                <input type="checkbox" class="k-checkbox" {{ isset($data['Terapeutik_2']) && $data['Terapeutik_2']['code'] == 'BDJ' ? 'checked' : '' }}>
                                <label class="k-checkbox-label" >Berikan diet jantung yang sesuai order</label>
                            </div>
                            <div style="margin: 5px 0px 5px 0px;">
                                <input type="checkbox" class="k-checkbox" {{ isset($data['Terapeutik_3']) && $data['Terapeutik_3']['code'] == 'BTR' ? 'checked' : '' }}>
                                <label class="k-checkbox-label" >Berikan terapi relaksasi untuk mengurangi stress</label>
                            </div>
                            <div style="margin: 5px 0px 5px 0px;">
                                <input type="checkbox" class="k-checkbox" {{ isset($data['Terapeutik_4']) && $data['Terapeutik_4']['code'] == 'BDE' ? 'checked' : '' }}>
                                <label class="k-checkbox-label" >Berikan dukungan emosional dan spiritual</label>
                            </div>
                            <div style="margin: 5px 0px 5px 0px;">
                                <input type="checkbox" class="k-checkbox" {{ isset($data['Terapeutik_5']) && $data['Terapeutik_5']['code'] == 'BTO' ? 'checked' : '' }}>
                                <label class="k-checkbox-label" >Berikan terapi oksigen</label>
                            </div>
                            <div style="margin: 5px 0px 5px 0px;">
                                <input type="checkbox" class="k-checkbox" {{ isset($data['Terapeutik_6']) && $data['Terapeutik_6']['code'] == 'MIOC' ? 'checked' : '' }}>
                                <label class="k-checkbox-label" >Monitor intake dan output cairan</label>
                            </div>
                            <div style="margin: 5px 0px 5px 0px;">
                                <label class="k-checkbox-label" >Lainnya : </label>
                                <span style="font-size: 10pt;" color="#000000">{{ isset($data['keteranganTerapeutik']) ? $data['keteranganTerapeutik'] : '-' }}</span>
                            </div>

                            <div style="margin-top:10px"><span style="font-weight: bold;">Edukasi :</span></div>
                            <div style="margin: 5px 0px 5px 0px;">
                                <input type="checkbox" class="k-checkbox" {{ isset($data['Edukasi_0']) && $data['Edukasi_0']['code'] == 'FPK' ? 'checked' : '' }}>
                                <label class="k-checkbox-label" >Fasilitasi pasien dan keluarga untuk memotivasi gaya hidup sehat</label>
                            </div>
                            <div style="margin: 5px 0px 5px 0px;">
                                <input type="checkbox" class="k-checkbox" {{ isset($data['Edukasi_1']) && $data['Edukasi_1']['code'] == 'ABF' ? 'checked' : '' }}>
                                <label class="k-checkbox-label" >Anjurkan beraktifitas fisik sesuai toleransi secara bertahap</label>
                            </div>
                            <div style="margin: 5px 0px 5px 0px;">
                                <input type="checkbox" class="k-checkbox" {{ isset($data['Edukasi_2']) && $data['Edukasi_2']['code'] == 'ABM' ? 'checked' : '' }}>
                                <label class="k-checkbox-label" >Anjurkan berhenti merokok</label>
                            </div>
                            <div style="margin: 5px 0px 5px 0px;">
                                <input type="checkbox" class="k-checkbox" {{ isset($data['Edukasi_3']) && $data['Edukasi_3']['code'] == 'APK' ? 'checked' : '' }}>
                                <label class="k-checkbox-label" >Anjurkan pasien dan keluarga mengukur berat badan </label>
                            </div>
                            <div style="margin: 5px 0px 5px 0px;">
                                <label class="k-checkbox-label" >Lainnya : </label>
                                <span style="font-size: 10pt;" color="#000000">{{ isset($data['keteranganEdukasi']) ? $data['keteranganEdukasi'] : '-' }}</span>
                            </div>

                            <div style="margin-top:10px"><span style="font-weight: bold;">Kolaborasi :</span></div>
                            <div style="margin: 5px 0px 5px 0px;">
                                <input type="checkbox" class="k-checkbox" {{ isset($data['Kolaborasi_0']) && $data['Kolaborasi_0']['code'] == 'PKA' ? 'checked' : '' }}>
                                <label class="k-checkbox-label" >Pemberian terapi antiaritmia, jika perlu</label>
                            </div>
                            <div style="margin: 5px 0px 5px 0px;">
                                <input type="checkbox" class="k-checkbox" {{ isset($data['Kolaborasi_1']) && $data['Kolaborasi_1']['code'] == 'PRM' ? 'checked' : '' }}>
                                <label class="k-checkbox-label" >Program rehabilitasi medik</label>
                            </div>

                            <div style="margin: 5px 0px 5px 0px;">
                                <label class="k-checkbox-label" >Lainnya : </label>
                                <span style="font-size: 10pt;" color="#000000">{{ isset($data['keteranganKolaborasi']) ? $data['keteranganKolaborasi'] : '-' }}</span>
                            </div>

                        </td>


                        <td style="font-weight: 100;text-align: left;vertical-align: text-top;">
                            <div class="grid_12" style="font-weight: bold">
                                Masalah teratasi
                            </div>
                            <div class="grid_12" style="margin: 5px 0px 5px 0px;">Tanggal dan jam</div>
                            <div class="grid_12" style="margin: 5px 0px 5px 0px;">
                                <span style="font-size: 10pt;" color="#000000">{{ isset($data['tanggalSelesai']) ? date('d-m-Y - H:m', strtotime($data['tanggalSelesai'])) : '-' }}</span>
                            </div>
                        </td>
                    </tr>


                </table>
            </td>
        </tr>
    </table>

    <table style="margin-top: 5px">
        <tr>
            <td colspan="4" style="border:0">
                <table>
                    <tr>

                        {{-- <td width="70%" style="text-align: center;border: none;">
                            &nbsp
                        </td> --}}
                        <td width="30%" style="text-align: center;border: none;">
                            {{-- @forelse($dataimg as $d)
                                @if ($d->emrdfk == 1)
                                    <img src="{{ $d->image }}" width="75" height="75" alt="TTD" />
                                @break
                            @endif
                        @empty
                            <div style="height:75px"></div>
                        @endforelse
                        <br> --}}
                        <b>
                            <span style="font-size: 9pt;" color="#000000">
                                Nama Perawat </span>
                        </b>
                        <br>
                        <b>
                            <span style="font-size: 9pt;" color="#000000">
                                ( {{isset($data['pegawai']) ? $data['pegawai']['label'] : '-'}} )</span>
                        </b>
                    </td>

                </tr>
            </table>

        </td>

    </tr>
</table>


@endsection
