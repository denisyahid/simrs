@extends('template.head-emr')
@section('title', 'EMR - Gangguan Pertukaran Gas')
@section('about', 'Gangguan Pertukaran Gas')

@push('style')
@endpush

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
                            <div><span>Kelebihan atau kekurangan oksigenasi dan eliminasi karbondioksida pada membran
                                    alveolus - kapiler</span>
                            </div>
                            <div style="margin-top:10px"><span style="font-weight: bold;">Berhubungan dengan :</span></div>
                            <div style="margin: 5px 0px 5px 0px;">
                                <input type="checkbox" class="k-checkbox" {{ isset($data['berhubunganDengan_0']) && $data['berhubunganDengan_0']['code'] == 'KVP' ? 'checked' : '' }}>
                                <label class="k-checkbox-label" >Ketidakseimbangan ventilasi - perfusi</label>
                            </div>
                            <div style="margin: 5px 0px 5px 0px;">
                                <input type="checkbox" class="k-checkbox" {{ isset($data['berhubunganDengan_1']) && $data['berhubunganDengan_1']['code'] == 'KMK' ? 'checked' : '' }}>
                                <label class="k-checkbox-label" >Perubahan membran alveolus - kapiler </label>
                            </div>

                            <div style="margin-top:10px"><span style="font-weight: bold;">Data sujektif :</span></div>
                            <div style="margin: 5px 0px 5px 0px;">
                                <input type="checkbox" class="k-checkbox" {{ isset($data['dataSubjektif_0']) && $data['dataSubjektif_0'] == 'Dispanea' ? 'checked' : '' }}>
                                <label class="k-checkbox-label" >Dispanea</label>
                            </div>
                            <div style="margin: 5px 0px 5px 0px;">
                                <input type="checkbox" class="k-checkbox" {{ isset($data['dataSubjektif_1']) && $data['dataSubjektif_1'] == 'Pusing' ? 'checked' : '' }}>
                                <label class="k-checkbox-label" >Pusing</label>
                            </div>
                            <div style="margin: 5px 0px 5px 0px;">
                                <input type="checkbox" class="k-checkbox" {{ isset($data['dataSubjektif_2']) && $data['dataSubjektif_2'] == 'Penglihatan Kabur' ? 'checked' : '' }}>
                                <label class="k-checkbox-label" >Penglihatan kabur</label>
                            </div>

                            <div style="margin-top:10px"><span style="font-weight: bold;">Data objektif :</span></div>
                            <div style="margin: 5px 0px 5px 0px;">
                                <input type="checkbox" class="k-checkbox" {{ isset($data['dataObjektif_0']) && $data['dataObjektif_0'] == 'PCO2' ? 'checked' : '' }}>
                                <label class="k-checkbox-label" >PCO2 Meingkat/Menurun</label>
                            </div>
                            <div style="margin: 5px 0px 5px 0px;">
                                <input type="checkbox" class="k-checkbox" {{ isset($data['dataObjektif_1']) && $data['dataObjektif_1'] == 'PO2' ? 'checked' : '' }}>
                                <label class="k-checkbox-label" >PO2 Menurun</label>
                            </div>
                            <div style="margin: 5px 0px 5px 0px;">
                                <input type="checkbox" class="k-checkbox" {{ isset($data['dataObjektif_2']) && $data['dataObjektif_2'] == 'TKD' ? 'checked' : '' }}>
                                <label class="k-checkbox-label" >Takikardi</label>
                            </div>
                            <div style="margin: 5px 0px 5px 0px;">
                                <input type="checkbox" class="k-checkbox" {{ isset($data['dataObjektif_3']) && $data['dataObjektif_3'] == 'PAM' ? 'checked' : '' }}>
                                <label class="k-checkbox-label" >PH Arteri Meingkat atau menurun</label>
                            </div>
                            <div style="margin: 5px 0px 5px 0px;">
                                <input type="checkbox" class="k-checkbox" {{ isset($data['dataObjektif_4']) && $data['dataObjektif_4'] == 'BNT' ? 'checked' : '' }}>
                                <label class="k-checkbox-label" >Bunyi nafas tambahan</label>
                            </div>
                            <div style="margin: 5px 0px 5px 0px;">
                                <input type="checkbox" class="k-checkbox" {{ isset($data['dataObjektif_5']) && $data['dataObjektif_5'] == 'SS' ? 'checked' : '' }}>
                                <label class="k-checkbox-label" >Sianosis</label>
                            </div>
                            <div style="margin: 5px 0px 5px 0px;">
                                <input type="checkbox" class="k-checkbox" {{ isset($data['dataObjektif_6']) && $data['dataObjektif_6'] == 'DS' ? 'checked' : '' }}>
                                <label class="k-checkbox-label" >Diaphoresis</label>
                            </div>
                            <div style="margin: 5px 0px 5px 0px;">
                                <input type="checkbox" class="k-checkbox" {{ isset($data['dataObjektif_7']) && $data['dataObjektif_7'] == 'GH' ? 'checked' : '' }}>
                                <label class="k-checkbox-label" >Gelisah</label>
                            </div>
                            <div style="margin: 5px 0px 5px 0px;">
                                <input type="checkbox" class="k-checkbox" {{ isset($data['dataObjektif_8']) && $data['dataObjektif_8'] == 'NCH' ? 'checked' : '' }}>
                                <label class="k-checkbox-label" >Nafas cuping hidung</label>
                            </div>
                            <div style="margin: 5px 0px 5px 0px;">
                                <input type="checkbox" class="k-checkbox" {{ isset($data['dataObjektif_9']) && $data['dataObjektif_9'] == 'PNA' ? 'checked' : '' }}>
                                <label class="k-checkbox-label" >Pola nafas abnormal (cepat atau lambat,regulasi/irregural,dalam/dangkal)</label>
                            </div>
                            <div style="margin: 5px 0px 5px 0px;">
                                <input type="checkbox" class="k-checkbox" {{ isset($data['dataObjektif_10']) && $data['dataObjektif_10'] == 'WKA' ? 'checked' : '' }}>
                                <label class="k-checkbox-label" >Warna kulit abnormal (pucat/kebiruan)</label>
                            </div>
                            <div style="margin: 5px 0px 5px 0px;">
                                <input type="checkbox" class="k-checkbox" {{ isset($data['dataObjektif_11']) && $data['dataObjektif_11'] == 'KM' ? 'checked' : '' }}>
                                <label class="k-checkbox-label" >Kesedaran menurun</label>
                            </div>

                            <div style="margin-top:10px">
                                <label class="k-checkbox-label" >Tanda-tanda vital :</label>
                            </div>

                            <div style="margin: 5px 0px 5px 0px;">
                                <div class="grid_12">
                                    <span>TD :</span><span>
                                        <span style="font-size: 10pt;" color="#000000"> {{ isset($data['tekananDarah']) ? $data['tekananDarah'] : '-' }}</span>
                                    </span>
                                </div>

                            </div>
                            <div style="margin: 5px 0px 5px 0px;">
                                <div class="grid_12">
                                    <span>HR :</span>&nbsp;<span>
                                        <span style="font-size: 10pt;" color="#000000">
                                            {{ isset($data['HR']) ? $data['HR'] : '-' }}
                                        </span>
                                    </span>
                                </div>

                            </div>
                            <div style="margin: 5px 0px 5px 0px;">
                                <div class="grid_12">
                                    <span>RR :</span>&nbsp;<span>
                                        <span style="font-size: 10pt;" color="#000000">
                                            {{ isset($data['RR']) ? $data['RR'] : '-' }}
                                        </span>
                                    </span>
                                </div>

                            </div>
                            <div style="margin: 5px 0px 5px 0px;">
                                <div class="grid_12">
                                    <span>S :</span>&nbsp;<span>
                                        <span style="font-size: 10pt;" color="#000000">
                                            {{ isset($data['suhu']) ? $data['suhu'] : '-' }}
                                        </span>
                                    </span>
                                </div>

                            </div>
                            <div style="margin: 5px 0px 5px 0px;">
                                <div class="grid_12">
                                    <span>SpO2 :</span>&nbsp;<span>
                                        <span style="font-size: 10pt;" color="#000000">
                                            {{ isset($data['spo2']) ? $data['spo2'] : '-' }}
                                        </span>
                                    </span>
                                </div>

                            </div>
                            <span>&nbsp;</span>
                            <div style="margin: 5px 0px 5px 0px;">
                                <input type="checkbox" class="k-checkbox"
                                 />
                                <label class="k-checkbox-label" >Pemeriksaan Penunjang</label>
                                <span style="font-size: 10pt;" color="#000000">
                                    {{ isset($data['pemeriksaanPenunjang']) ? $data['pemeriksaanPenunjang'] : '-' }}
                                </span>
                                <!-- <input c-text-box type="text" filter="" class="k-textbox"
                             /> -->
                            </div>
                            <div style="margin: 5px 0px 5px 0px;">
                                <div class="gird_2">
                                    X Foto thorax
                                </div>
                                <div class="gird_10">
                                    <span style="font-size: 10pt;" color="#000000">
                                        {{ isset($data['fotoThoraxs']) ? $data['fotoThoraxs'] : '-' }}
                                    </span>
                                    <!-- <input c-text-box type="text" filter="" class="k-textbox"
                                 /> -->
                                </div>
                            </div>
                            <div style="margin: 5px 0px 5px 0px;">
                                <div class="gird_2">
                                    Laboratorium
                                </div>
                                <div class="gird_10">
                                    <span style="font-size: 10pt;" color="#000000">
                                        {{ isset($data['laboratorium']) ? $data['laboratorium'] : '-' }}
                                    </span>
                                    <!-- <input c-text-box type="text" filter="" class="k-textbox"
                                 /> -->
                                </div>

                            </div>
                        </td>


                        <td style="font-weight: 100;text-align: left;vertical-align: text-top;">
                            <div><span style="font-weight: bold;">Pertukaran gas (L.01003)</span></div>

                            <div style="margin-top: 10px"><span style="font-weight: bold;">Kriteria hasil:</span></div>
                            <div style="margin: 5px 0px 5px 0px;">
                                <div class="grid_8">
                                    1. Tingkat kesadaran :
                                </div>
                                <div class="grid_4">
                                    Meningkat
                                </div>

                                <div class="grid_8">
                                    2. Dispnea / bunyi nafas tambahan :
                                </div>
                                <div class="grid_4">
                                    Menurun
                                </div>

                                <div class="grid_6">
                                    3. Pusing :
                                </div>
                                <div class="grid_6">
                                    Menurun
                                </div>

                                <div class="grid_6">
                                    4. Hasil PCO2 :
                                </div>
                                <div class="grid_6">
                                    Membaik
                                </div>

                                <div class="grid_12">
                                    <span style="font-size: 10pt;" color="#000000">Hasil : </span>
                                    <!-- <input c-text-box type="text" filter="" class="k-textbox"
                                 /> -->
                                </div>
                                <div class="grid_12">
                                    <span style="font-size: 10pt;" color="#000000">
                                        {{ isset($data['kriteriaHasil']) ? $data['kriteriaHasil'] : '-' }}
                                    </span>
                                    <!-- <input c-text-box type="text" filter="" class="k-textbox"
                                 /> -->
                                </div>

                            </div>

                        </td>
                        <td style="font-weight: 100;text-align: left;vertical-align: text-top;">
                            <div><span style="font-weight: bold;">PEMANTAUAN RESPIRASI (I.01014)</span></div>

                            <div style="margin-top:10px"><span style="font-weight: bold;">Observasi :</span></div>
                            <div style="margin: 5px 0px 5px 0px;">
                                <input type="checkbox" class="k-checkbox" {{ isset($data['observasi_0']) && $data['observasi_0'] == 'MPS' ? 'checked' : '' }}>
                                <label class="k-checkbox-label" >Monitor pola napas (frekuensi,kedalaman,usaha napas)</label>
                            </div>
                            <div style="margin: 5px 0px 5px 0px;">
                                <input type="checkbox" class="k-checkbox" {{ isset($data['observasi_1']) && $data['observasi_1'] == 'MPN' ? 'checked' : '' }}>
                                <label class="k-checkbox-label" >Monitor pola napas (seperti bradipnea, takipnea, hiperventilasi, Kussmaul,
                                    Cheyne-Stokes, Biot, ataksik)</label>
                            </div>
                            <div style="margin: 5px 0px 5px 0px;">
                                <input type="checkbox" class="k-checkbox" {{ isset($data['observasi_2']) && $data['observasi_2'] == 'MKBE' ? 'checked' : '' }}>
                                <label class="k-checkbox-label" >Monitor kemampuan batuk efektif</label>
                            </div>
                            <div style="margin: 5px 0px 5px 0px;">
                                <input type="checkbox" class="k-checkbox" {{ isset($data['observasi_3']) && $data['observasi_3'] == 'MAPS' ? 'checked' : '' }}>
                                <label class="k-checkbox-label" >Monitor adanya produksi sputum</label>
                            </div>
                            <div style="margin: 5px 0px 5px 0px;">
                                <input type="checkbox" class="k-checkbox" {{ isset($data['observasi_4']) && $data['observasi_4'] == 'MASN' ? 'checked' : '' }}>
                                <label class="k-checkbox-label" >Monitor adanya sumbatan jalan napas</label>
                            </div>
                            <div style="margin: 5px 0px 5px 0px;">
                                <input type="checkbox" class="k-checkbox" {{ isset($data['observasi_5']) && $data['observasi_5'] == 'PKEP' ? 'checked' : '' }}>
                                <label class="k-checkbox-label" >Palpasi kesimetrisan ekspansi paru</label>
                            </div>
                            <div style="margin: 5px 0px 5px 0px;">
                                <input type="checkbox" class="k-checkbox" {{ isset($data['observasi_6']) && $data['observasi_6'] == 'ABN' ? 'checked' : '' }}>
                                <label class="k-checkbox-label" >Auskultasi bunyi napas</label>
                            </div>
                            <div style="margin: 5px 0px 5px 0px;">
                                <input type="checkbox" class="k-checkbox" {{ isset($data['observasi_7']) && $data['observasi_7'] == 'MSO' ? 'checked' : '' }}>
                                <label class="k-checkbox-label" >Monitor saturasi oksigen</label>
                            </div>
                            <div style="margin: 5px 0px 5px 0px;">
                                <input type="checkbox" class="k-checkbox" {{ isset($data['observasi_8']) && $data['observasi_8'] == 'MNA' ? 'checked' : '' }}>
                                <label class="k-checkbox-label" >Monitor nilai AGD</label>
                            </div>
                            <div style="margin: 5px 0px 5px 0px;">
                                <input type="checkbox" class="k-checkbox" {{ isset($data['observasi_9']) && $data['observasi_9'] == 'MHT' ? 'checked' : '' }}>
                                <label class="k-checkbox-label" >Monitor hasil x-ray toraks</label>
                            </div>


                            <div style="margin-top:10px"><span style="font-weight: bold;">Terapeutik :</span></div>
                            <div style="margin: 5px 0px 5px 0px;">
                                <input type="checkbox" class="k-checkbox" {{ isset($data['terapeutik_0']) && $data['terapeutik_0'] == 'PKP' ? 'checked' : '' }}>
                                <label class="k-checkbox-label" >Atur interval waktu pemantauan respirasi sesuai kondisi pasien</label>
                            </div>
                            <div style="margin: 5px 0px 5px 0px;">
                                <input type="checkbox" class="k-checkbox" {{ isset($data['terapeutik_1']) && $data['terapeutik_1'] == 'DHP' ? 'checked' : '' }}>
                                <label class="k-checkbox-label" >Dokumentasikan hasil pemantauan</label>
                            </div>




                            <div style="margin-top:10px"><span style="font-weight: bold;">Edukasi :</span></div>
                            <div style="margin: 5px 0px 5px 0px;">
                                <input type="checkbox" class="k-checkbox" {{ isset($data['edukasi_0']) && $data['edukasi_0'] == 'KM' ? 'checked' : '' }}>
                                <label class="k-checkbox-label" >Jelaskan tujuan dan prosedur pemantauan</label>
                            </div>
                            <div style="margin: 5px 0px 5px 0px;">
                                <input type="checkbox" class="k-checkbox" {{ isset($data['edukasi_1']) && $data['edukasi_1'] == 'KM' ? 'checked' : '' }}>
                                <label class="k-checkbox-label" >Informasikan hasil pemantauan, jika perlu</label>
                            </div>



                        </td>


                        <td style="font-weight: 100;text-align: left;vertical-align: text-top;">
                            <div class="grid_12" style="font-weight: bold">
                                Masalah teratasi
                            </div>
                            <div class="grid_12" style="margin: 5px 0px 5px 0px;">Tanggal dan jam</div>
                            <div class="grid_12" style="margin: 5px 0px 5px 0px;">
                                <span style="font-size: 10pt;" color="#000000">
                                    {{ isset($data['tanggalSelesai']) ? date('d-m-Y - H:m', strtotime($data['tanggalSelesai'])) : '-' }}
                                </span>
                                <!-- <input style="width: 100%" k-ng-model="item.obj[21008803]" kendo-datetimepicker
                                placeholer="yyyy-MM-dd HH:mm" k-format="'yyyy-MM-dd HH:mm'"
                                parsedate="yyyy-MM-dd HH:mm" /> -->
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
                            &nbsp
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
                            <span style="font-size: 9pt;" color="#000000">
                                ( {{isset($data['petugas']) ? $data['petugas'] : '-'}} )</span>
                        </b>
                    </td>

                </tr>
            </table>

        </td>

    </tr>
</table>


@endsection
