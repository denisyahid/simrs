@extends('template.head-emr')
@section('title', 'EMR - Pola Nafas Tidak Efektif')
@section('about', 'Pola Nafas Tidak Efektif')
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
                            <div><span>Inspirasi dan/atau ekspirasi yang tidak memberikan ventilasi adekuat</span>
                            </div>
                            <div style="margin-top:10px"><span style="font-weight: bold;">Berhubungan dengan :</span></div>
                            <div style="margin: 5px 0px 5px 0px;">
                                <input type="checkbox" {{ isset($data['hubunganDengan_0']) && $data['hubunganDengan_0']['code'] == 'DPP' ? 'checked' : '' }}{{ isset($data['hubunganDengan_']) && $data['hubunganDengan_']['code'] == 'DPP' ? 'checked' : '' }}>
                                <label class="k-checkbox-label" >
                                    Depresi pusat pernafasan</label>
                            </div>
                            <div style="margin: 5px 0px 5px 0px;">
                                <input type="checkbox" {{ isset($data['hubunganDengan_1']) && $data['hubunganDengan_1']['code'] == 'HUN' ? 'checked' : '' }}>
                                <label class="k-checkbox-label" >
                                    Hambatan upaya nafas (nyeri saat bernafas kelemahan otot, pernafasan)</label>
                            </div>
                            <div style="margin: 5px 0px 5px 0px;">
                                <input type="checkbox" {{ isset($data['hubunganDengan_2']) && $data['hubunganDengan_2']['code'] == 'DDD' ? 'checked' : '' }}>
                                <label class="k-checkbox-label" >
                                    Deformitas dinding dada</label>
                            </div>
                            <div style="margin: 5px 0px 5px 0px;">
                                <input type="checkbox" {{ isset($data['hubunganDengan_3']) && $data['hubunganDengan_3']['code'] == 'DTD' ? 'checked' : '' }}>
                                <label class="k-checkbox-label" >
                                    Deformitas tulang dada</label>
                            </div>
                            <div style="margin: 5px 0px 5px 0px;">
                                <input type="checkbox" {{ isset($data['hubunganDengan_4']) && $data['hubunganDengan_4']['code'] == 'GN' ? 'checked' : '' }}>
                                <label class="k-checkbox-label" >
                                    Gangguan neuromuscular</label>
                            </div>
                            <div style="margin: 5px 0px 5px 0px;">
                                <input type="checkbox" {{ isset($data['hubunganDengan_5']) && $data['hubunganDengan_5']['code'] == 'GN-EEG' ? 'checked' : '' }}>
                                <label class="k-checkbox-label" >
                                    Gangguan neurologis, misalnya elektroensepalogram (EEG)</label>
                            </div>
                            <div style="margin: 5px 0px 5px 0px;">
                                <input type="checkbox" {{ isset($data['hubunganDengan_6']) && $data['hubunganDengan_6']['code'] == 'IN' ? 'checked' : '' }}>
                                <label class="k-checkbox-label" >
                                    Imaturitas neurologis</label>
                            </div>
                            <div style="margin: 5px 0px 5px 0px;">
                                <input type="checkbox" {{ isset($data['hubunganDengan_7']) && $data['hubunganDengan_7']['code'] == 'PE' ? 'checked' : '' }}>
                                <label class="k-checkbox-label" >
                                    Penurunan energy</label>
                            </div>
                            <div style="margin: 5px 0px 5px 0px;">
                                <input type="checkbox" {{ isset($data['hubunganDengan_8']) && $data['hubunganDengan_8']['code'] == 'OBS' ? 'checked' : '' }}>
                                <label class="k-checkbox-label" >
                                    Obesitas</label>
                            </div>
                            <div style="margin: 5px 0px 5px 0px;">
                                <input type="checkbox" {{ isset($data['hubunganDengan_9']) && $data['hubunganDengan_9']['code'] == 'PTM' ? 'checked' : '' }}>
                                <label class="k-checkbox-label" >
                                    Posisi tubuh yang menghambat ekspansi paru</label>
                            </div>
                            <div style="margin: 5px 0px 5px 0px;">
                                <input type="checkbox" {{ isset($data['hubunganDengan_10']) && $data['hubunganDengan_10']['code'] == 'SH' ? 'checked' : '' }}>
                                <label class="k-checkbox-label" >
                                    Sindrom hipoventilasi</label>
                            </div>
                            <div style="margin: 5px 0px 5px 0px;">
                                <input type="checkbox" {{ isset($data['hubunganDengan_11']) && $data['hubunganDengan_11']['code'] == 'KID' ? 'checked' : '' }}>
                                <label class="k-checkbox-label" >
                                    Kerusakan innervasi diafragma (kerusakan saraf C5 ke atas)</label>
                            </div>
                            <div style="margin: 5px 0px 5px 0px;">
                                <input type="checkbox" {{ isset($data['hubunganDengan_12']) && $data['hubunganDengan_12']['code'] == 'CMS' ? 'checked' : '' }}>
                                <label class="k-checkbox-label" >
                                    Cedera pada medulla spinalis</label>
                            </div>
                            <div style="margin: 5px 0px 5px 0px;">
                                <input type="checkbox" {{ isset($data['hubunganDengan_13']) && $data['hubunganDengan_13']['code'] == 'EAF' ? 'checked' : '' }}>
                                <label class="k-checkbox-label" >
                                    Efek agen farmakologis</label>
                            </div>
                            <div style="margin: 5px 0px 5px 0px;">
                                <input type="checkbox" {{ isset($data['hubunganDengan_14']) && $data['hubunganDengan_14']['code'] == 'KCN' ? 'checked' : '' }}>
                                <label class="k-checkbox-label" >
                                    Kecemasan</label>
                            </div>


                            <div style="margin-top:10px"><span style="font-weight: bold;">Data sujektif :</span></div>
                            <div style="margin: 5px 0px 5px 0px;">
                                <input type="checkbox" {{ isset($data['dataSubjektif_0']) && $data['dataSubjektif_0']['code'] == 'DSP' ? 'checked' : '' }}>
                                <label class="k-checkbox-label" >
                                    Dispanea</label>
                            </div>
                            <div style="margin: 5px 0px 5px 0px;">
                                <input type="checkbox" {{ isset($data['dataSubjektif_1']) && $data['dataSubjektif_1']['code'] == 'OTA' ? 'checked' : '' }}>
                                <label class="k-checkbox-label" >
                                    Orthopnea</label>
                            </div>
                            <div style="margin: 5px 0px 5px 0px;">
                                <input type="checkbox" {{ isset($data['dataSubjektif_2']) && $data['dataSubjektif_2']['code'] == 'CL' ? 'checked' : '' }}>
                                <label class="k-checkbox-label" >
                                    Cepeat lelah</label>
                            </div>
                            <div style="margin: 5px 0px 5px 0px;">
                                <input type="checkbox" >
                                <label class="k-checkbox-label" >
                                    </label>
                                <span style="font-size: 10pt;" color="#000000">{{isset($data['subjektifLainnya']) ? $data['subjektifLainnya'] : '-'}}</span>
                                <!-- <input c-text-box type="text" filter="" class="k-textbox"
                                 /> -->
                            </div>

                            <div style="margin-top:10px"><span style="font-weight: bold;">Data objektif :</span></div>
                            <div style="margin: 5px 0px 5px 0px;">
                                <input type="checkbox" {{ isset($data['dataObjektif_0']) && $data['dataObjektif_0']['code'] == 'PBN' ? 'checked' : '' }}>
                                <label class="k-checkbox-label" >
                                    Pengeluaran otot bantu pernafasan</label>
                            </div>
                            <div style="margin: 5px 0px 5px 0px;">
                                <input type="checkbox" {{ isset($data['dataObjektif_1']) && $data['dataObjektif_1']['code'] == 'FEM' ? 'checked' : '' }}>
                                <label class="k-checkbox-label" >
                                    Fase ekspirasi memanjang</label>
                            </div>
                            <div style="margin: 5px 0px 5px 0px;">
                                <input type="checkbox" {{ isset($data['dataObjektif_2']) && $data['dataObjektif_2']['code'] == 'PNA' ? 'checked' : '' }}>
                                <label class="k-checkbox-label" >
                                    Pola nafas abnormal (takipnea, bradipnea, hiperventlasi, kussmaul,
                                    cheyne-stokes)</label>
                            </div>
                            <div style="margin: 5px 0px 5px 0px;">
                                <input type="checkbox" {{ isset($data['dataObjektif_3']) && $data['dataObjektif_3']['code'] == 'PP' ? 'checked' : '' }}>
                                <label class="k-checkbox-label" >
                                    Pernafasan pursed-lip</label>
                            </div>
                            <div style="margin: 5px 0px 5px 0px;">
                                <input type="checkbox" {{ isset($data['dataObjektif_4']) && $data['dataObjektif_4']['code'] == 'PCH' ? 'checked' : '' }}>
                                <label class="k-checkbox-label" >
                                    Pernafasan cuping hidung</label>
                            </div>
                            <div style="margin: 5px 0px 5px 0px;">
                                <input type="checkbox" {{ isset($data['dataObjektif_5']) && $data['dataObjektif_5']['code'] == 'DTA' ? 'checked' : '' }}>
                                <label class="k-checkbox-label" >
                                    Diameter thoraks anterior-posterior meningkat</label>
                            </div>
                            <div style="margin: 5px 0px 5px 0px;">
                                <input type="checkbox" {{ isset($data['dataObjektif_6']) && $data['dataObjektif_6']['code'] == 'VSM' ? 'checked' : '' }}>
                                <label class="k-checkbox-label" >
                                    Ventilasi semenit menurun</label>
                            </div>
                            <div style="margin: 5px 0px 5px 0px;">
                                <input type="checkbox" {{ isset($data['dataObjektif_7']) && $data['dataObjektif_7']['code'] == 'KVM' ? 'checked' : '' }}>
                                <label class="k-checkbox-label" >
                                    Kapasitas vital menurun</label>
                            </div>
                            <div style="margin: 5px 0px 5px 0px;">
                                <input type="checkbox" {{ isset($data['dataObjektif_8']) && $data['dataObjektif_8']['code'] == 'TEM' ? 'checked' : '' }}>
                                <label class="k-checkbox-label" >
                                    Tekanan ekspirasi menurun</label>
                            </div>
                            <div style="margin: 5px 0px 5px 0px;">
                                <input type="checkbox" {{ isset($data['dataObjektif_9']) && $data['dataObjektif_9']['code'] == 'TIM' ? 'checked' : '' }} >
                                <label class="k-checkbox-label" >
                                    Tekanan inspirasi menurun</label>
                            </div>
                            <div style="margin: 5px 0px 5px 0px;">
                                <input type="checkbox" {{ isset($data['dataObjektif_10']) && $data['dataObjektif_10']['code'] == 'EDB' ? 'checked' : '' }} >
                                <label class="k-checkbox-label" >
                                    Ekskursi dada berubah</label>
                            </div>

                            <div style="margin: 5px 0px 5px 0px;">
                                {{-- <input type="checkbox"> --}}
                                <label class="k-checkbox-label" >
                                    </label>
                                <span style="font-size: 10pt;" color="#000000">{{isset($data['objektifLainnya']) ? $data['objektifLainnya'] : '-'}}</span>
                                <!-- <input c-text-box type="text" filter="" class="k-textbox"
                                 /> -->
                            </div>


                            <div style="margin-top:10px">
                                {{-- <input type="checkbox" {{ isset($data['hubunganDengan_']) && $data['hubunganDengan_']['code'] == 'DPP' ? 'checked' : '' }} --}}
                            
                                <label class="k-checkbox-label" >
                                    Tanda-tanda vital :</label>

                            </div>

                            <div style="margin: 5px 0px 5px 0px;">
                                <div class="grid_12">
                                    <span>TD :</span><span>
                                        <span style="font-size: 10pt;" color="#000000">{{isset($data['tekananDarah']) ? $data['tekananDarah'] : '-'}}</span>
                                    </span>
                                </div>

                            </div>
                            <div style="margin: 5px 0px 5px 0px;">
                                <div class="grid_12">
                                    <span>HR :</span><span>
                                        <span style="font-size: 10pt;" color="#000000">
                                            {{isset($data['HR']) ? $data['HR'] : '-'}}
                                        </span>
                                    </span>
                                </div>

                            </div>
                            <div style="margin: 5px 0px 5px 0px;">
                                <div class="grid_12">
                                    <span>RR :</span><span>
                                        <span style="font-size: 10pt;" color="#000000">{{isset($data['RR']) ? $data['RR'] : '-'}}</span>
                                    </span>
                                </div>

                            </div>
                            <div style="margin: 5px 0px 5px 0px;">
                                <div class="grid_12">
                                    <span>S :</span><span>
                                        <span style="font-size: 10pt;" color="#000000">{{isset($data['suhu']) ? $data['suhu'] : '-'}}</span>
                                    </span>
                                </div>
                            </div>
                            <div style="margin: 5px 0px 5px 0px;">
                                <div class="grid_12">
                                    <span>SpO2 :</span><span>
                                        <span style="font-size: 10pt;" color="#000000">{{isset($data['spo2']) ? $data['spo2'] : '-'}}</span>
                                    </span>
                                </div>
                            </div>
                            <span></span>
                            <div style="margin: 5px 0px 5px 0px;">
                                {{-- <input type="checkbox" {{ isset($data['hubunganDengan_']) && $data['hubunganDengan_']['code'] == 'DPP' ? 'checked' : '' }}                                      /> --}}
                                <label class="k-checkbox-label" >
                                    Pemeriksaan Laboratorium</label>
                                <span style="font-size: 10pt;" color="#000000">{{isset($data['pemeriksaanLaboratorium']) ? $data['pemeriksaanLaboratorium'] : '-'}}</span>
                                <!-- <input c-text-box type="text" filter="" class="k-textbox"
                                 /> -->
                            </div>
                            <div style="margin: 5px 0px 5px 0px;">
                                <div class="gird_2">
                                    X Foto thorax
                                </div>
                                <div class="gird_10">
                                    <span style="font-size: 10pt;" color="#000000">{{isset($data['fotoThoraxs']) ? $data['fotoThoraxs'] : '-'}}</span>
                                    <!-- <input c-text-box type="text" filter="" class="k-textbox"
                                     /> -->
                                </div>
                            </div>
                            <div style="margin: 5px 0px 5px 0px;">
                                <div class="gird_2">
                                    Laboratorium
                                </div>
                                <div class="gird_10">
                                    <span style="font-size: 10pt;" color="#000000">{{isset($data['laboratorium']) ? $data['laboratorium'] : '-'}}</span>
                                    <!-- <input c-text-box type="text" filter="" class="k-textbox"
                                     /> -->
                                </div>

                            </div>
                        </td>


                        <td style="font-weight: 100;text-align: left;vertical-align: text-top;">
                            <div><span style="font-weight: bold;">Pola Nafas (L.01004)</span></div>
                            <div><span style="font-weight: bold;">Ventilasi adekuat :</span></div>
                            <div style="margin-top: 10px"><span style="font-weight: bold;">Kriteria hasil:</span></div>
                            <div style="margin: 5px 0px 5px 0px;">
                                <div class="grid_8">
                                    1. Dispnea atau penggunaan otot bantu nafas :
                                </div>
                                <div class="grid_4">
                                    membaik
                                </div>

                                <div class="grid_8">
                                    2. Ortopnea :
                                </div>
                                <div class="grid_4">
                                    Menurun
                                </div>

                                <div class="grid_6">
                                    3. Frekuensi Nafas :
                                </div>
                                <div class="grid_6">
                                    Menurun
                                </div>

                                <div class="grid_6">
                                    4. Kedalaman Nafas :
                                </div>
                                <div class="grid_6">
                                    Membaik
                                </div>

                                <div class="grid_12">
                                    <span style="font-size: 10pt;" color="#000000">{{isset($data['kriteriaHasil']) ? $data['kriteriaHasil'] : '-'}}</span>
                                    <!-- <input c-text-box type="text" filter="" class="k-textbox"
                                     /> -->
                                </div>
                                <div class="grid_12">
                                    <span style="font-size: 10pt;" color="#000000"></span>
                                    <!-- <input c-text-box type="text" filter="" class="k-textbox"
                                     /> -->
                                </div>

                            </div>

                        </td>
                        <td style="font-weight: 100;text-align: left;vertical-align: text-top;">
                            <div><span style="font-weight: bold;">Pemantauan Respirasi :</span></div>

                            <div style="margin-top:10px"><span style="font-weight: bold;">Observasi :</span></div>
                            <div style="margin: 5px 0px 5px 0px;">
                                <input type="checkbox" {{ isset($data['dataObservasi_0']) && $data['dataObservasi_0']['code'] == "MPN" ? 'checked' : '' }} >
                                <label class="k-checkbox-label" >
                                    Monitor Pola Nafas, monitor saturasi oksigen</label>
                            </div>
                            <div style="margin: 5px 0px 5px 0px;">
                                <input type="checkbox" {{ isset($data['dataObservasi_1']) && $data['dataObservasi_1']['code'] == 'MFI' ? 'checked' : '' }} >
                                <label class="k-checkbox-label" >
                                    Monitor frekuensi, irama, kedalaman dan upaya nafas</label>
                            </div>
                            <div style="margin: 5px 0px 5px 0px;">
                                <input type="checkbox" {{ isset($data['dataObservasi_2']) && $data['dataObservasi_2']['code'] == 'MAS' ? 'checked' : '' }} >
                                <label class="k-checkbox-label" >Monitor adanya sumbatan jalan nafas</label>
                            </div>
                            <div style="margin: 5px 0px 5px 0px;">
                                <input type="checkbox" {{ isset($data['dataObservasi_3']) && $data['dataObservasi_3']['code'] == 'MKG' ? 'checked' : '' }} >
                                <label class="k-checkbox-label" >Monitor kecepatan gerak oksigen</label>
                            </div>
                            <div style="margin: 5px 0px 5px 0px;">
                                <input type="checkbox" {{ isset($data['dataObservasi_4']) && $data['dataObservasi_4']['code'] == 'MPT' ? 'checked' : '' }} >
                                <label class="k-checkbox-label" >Monitor posisi alat terapi oksigen</label>
                            </div>
                            <div style="margin: 5px 0px 5px 0px;">
                                <input type="checkbox" {{ isset($data['dataObservasi_5']) && $data['dataObservasi_5']['code'] == 'MTH' ? 'checked' : '' }} >
                                <label class="k-checkbox-label" >"Monitor tanda tanda hopiventilasi</label>
                            </div>
                            <div style="margin: 5px 0px 5px 0px;">
                                <input type="checkbox" {{ isset($data['dataObservasi_6']) && $data['dataObservasi_6']['code'] == 'MIM' ? 'checked' : '' }} >
                                <label class="k-checkbox-label" >Monitor integritas mukosa hidung akibat pemasangan oksigen</label>
                            </div>
                            <div style="margin: 5px 0px 5px 0px;">
                                <span style="font-size: 10pt;" color="#000000">{{isset($data['keteranganObservasi']) ? $data['keteranganObservasi'] : '-'}}</span>
                            </div>



                            <div style="margin-top:10px"><span style="font-weight: bold;">Edukasi :</span></div>
                            <div style="margin: 5px 0px 5px 0px;">
                                <input type="checkbox" {{ isset($data['dataEdukasi_0']) && $data['dataEdukasi_0']['code'] == 'IHP' ? 'checked' : '' }} >
                                <label class="k-checkbox-label" >
                                    Jelaskan tujuan dan prosedur pemantauan</label>
                            </div>
                            <div style="margin: 5px 0px 5px 0px;">
                                <input type="checkbox" {{ isset($data['dataEdukasi_1']) && $data['dataEdukasi_1']['code'] == 'TO' ? 'checked' : '' }} >
                                <label class="k-checkbox-label" >
                                    Informasikan hasil pemantauan, jika perlu</label>
                            </div>
                            <div style="margin: 5px 0px 5px 0px;">
                                <input type="checkbox" {{ isset($data['hubunganDengan_2']) && $data['hubunganDengan_2']['code'] == 'AKC' ? 'checked' : '' }} >
                                <label class="k-checkbox-label" >
                                    Ajarkan keluarga cara menggunakan O2 dirumah</label>
                            </div>
                            <div style="margin: 5px 0px 5px 0px;">
                                {{-- <input type="checkbox" {{ isset($data['dataEdukasi_2']) && $data['dataEdukasi_2']['code'] == 'AKC' ? 'checked' : '' }} > --}}
                                {{-- <label class="k-checkbox-label" ></label> --}}
                                <span style="font-size: 10pt;" color="#000000">{{isset($data['keteranganEdukasi']) ? $data['keteranganEdukasi'] : '-'}}</span>
                                <!-- <input c-text-box type="text" filter="" class="k-textbox"
                                 /> -->
                            </div>
                            <div style="margin: 5px 0px 5px 0px;">
                                <div class="gird_2">
                                    Terapi Oksigen
                                </div>
                                <div class="gird_10">
                                    {{-- <span style="font-size: 10pt;" color="#000000">{{isset($data['keteranganEdukasi']) ? $data['keteranganEdukasi'] : '-'}}</span> --}}
                                    <!-- <input c-text-box type="text" filter="" class="k-textbox"
                                     /> -->
                                </div>
                            </div>

                            <div style="margin-top:10px"><span style="font-weight: bold;">Terapeutik :</span></div>
                            <div style="margin: 5px 0px 5px 0px;">
                                <input type="checkbox" {{ isset($data['dataTerapeutik_0']) && $data['dataTerapeutik_0']['code'] == 'BSM' ? 'checked' : '' }}>
                                <label class="k-checkbox-label" >
                                    Bersihkan secret pada mulut, hidung, dan trachea jika perlu</label>
                            </div>
                            <div style="margin: 5px 0px 5px 0px;">
                                <input type="checkbox" {{ isset($data['dataTerapeutik_1']) && $data['dataTerapeutik_1']['code'] == 'PKN' ? 'checked' : '' }}>
                                <label class="k-checkbox-label" >
                                    Pertahankan kepatenan jalan nafas</label>
                            </div>
                            <div style="margin: 5px 0px 5px 0px;">
                                <input type="checkbox" {{ isset($data['dataTerapeutik_2']) && $data['dataTerapeutik_2']['code'] == 'BOP' ? 'checked' : '' }}>
                                <label class="k-checkbox-label" >
                                    Berikan oksigen jika perlu</label>
                            </div>
                            <div style="margin: 5px 0px 5px 0px;">
                                {{-- <input type="checkbox" {{ isset($data['dataTerapeutik_3']) && $data['dataTerapeutik_3']['code'] == 'DPP' ? 'checked' : '' }} >
                                <label class="k-checkbox-label" >
                                    </label> --}}
                                <span style="font-size: 10pt;" color="#000000">{{isset($data['keteranganTerapeutik']) ? $data['keteranganTerapeutik'] : '-'}}</span>
                                <!-- <input c-text-box type="text" filter="" class="k-textbox"
                                 /> -->
                            </div>

                            <div style="margin-top:10px"><span style="font-weight: bold;">Kolaborasi :</span></div>
                            <div style="margin: 5px 0px 5px 0px;">
                                <input type="checkbox" {{ isset($data['dataKolaborasi_0']) && $data['dataKolaborasi_0']['code'] == 'PD' ? 'checked' : '' }}>
                                <label class="k-checkbox-label">
                                    Penentuan dosis O2</label>
                            </div>
                            <div style="margin: 5px 0px 5px 0px;">
                                <input type="checkbox" {{ isset($data['dataKolaborasi_1']) && $data['dataKolaborasi_1']['code'] == 'PT' ? 'checked' : '' }}>
                                <label class="k-checkbox-label">
                                    Pemberian terapi</label>
                            </div>
                            <div style="margin: 5px 0px 5px 0px;">
                                {{-- <input type="checkbox" {{ isset($data['dataKolaborasi_2']) && $data['dataKolaborasi_2']['code'] == 'DPP' ? 'checked' : '' }}> --}}
                                <label class="k-checkbox-label">
                                    </label>
                                <span style="font-size: 10pt;" color="#000000">{{isset($data['keteranganKolaborasi']) ? $data['keteranganKolaborasi'] : '-'}}</span>
                                <!-- <input c-text-box type="text" filter="" class="k-textbox"
                                ng-model="item.obj[2100001795]" /> -->
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

                        <td width="70%" style="text-align: center;border: none;">
                                                    </td>
                        <td width="30%" style="text-align: center;border: none;">
                            {{-- @forelse($dataimg as $d)
                                @if ($d->emrdfk == 1)
                                    <img src="{{ $d->image }}" width="75" height="75" alt="TTD" />
                                @break
                            @endif
                        @empty
                            <div style="height:75px"></div>
                        @endforelse --}}
                        <br>
                        <b>
                            <span style="font-size: 9pt;" color="#000000">
                                Nama Perawat </span>
                        </b>
                        <br>
                        <b>
                            <span style="font-size: 9pt;" color="#000000">{{isset($data['pegawai']) ? $data['pegawai']['label'] : '-'}}</span>
                        </b>
                    </td>

                </tr>
            </table>

        </td>

    </tr>
</table>

@endsection
