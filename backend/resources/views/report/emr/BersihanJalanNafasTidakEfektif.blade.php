@extends('template.head-emr')
@section('title', 'EMR - Bersihan Jalan Nafas Tidak Efektif')
@section('about', 'Bersihan Jalan Nafas Tidak Efektif')
@section('content')

    <table width="100%" cellspacing="0">
        <tr>
            <td width="100%">
                <table width="100%" border="1" bordercolor="#000000" class="garis6">
                    <tr>
                        <th style="font-weight: bold;text-align: center;">Standar Diagnosa Keperawatan Indonesia
                            (SDKI)</th>
                        <th style="font-weight: bold;text-align: center;">Standar Luar Keperawatan Indonesia (SLKI)
                        </th>
                        <th style="font-weight: bold;text-align: center">Standar Intervensi Keperawatan Indonesia
                            (SIKI)</th>
                        <th style="font-weight: bold;text-align: center">Evaluasi</th>
                    </tr>
                    <tr>
                        <td style="font-weight: 100;text-align: left;">
                            <div><span style="font-weight: bold;">Definisi :</span></div>
                            <div><span>Ketidakmampuan membersihkan</br>
                                    secret atau obstruksi jalan napas untuk</br>
                                    mempertahankan jalan nafas tetap paten</span>
                            </div>
                            <div style="margin-top:10px"><span style="font-weight: bold;">Berhubungan dengan
                                    :</span></div>
                            <div style="margin: 5px 0px 5px 0px;">
                                <input type="checkbox" class="k-checkbox" {{ isset($data['hubunganDengan_0']) && $data['hubunganDengan_0']['code'] == 'SJN' ? 'checked' : '' }} >
                                <label class="k-checkbox-label" >Spasme jalan nafas</label>
                            </div>
                            <div style="margin: 5px 0px 5px 0px;">
                                <input type="checkbox" class="k-checkbox" {{ isset($data['hubunganDengan_1']) && $data['hubunganDengan_1']['code'] == 'HJN' ? 'checked' : '' }} >
                                <label class="k-checkbox-label" >Hipersekresi jalan nafas</label>
                            </div>
                            <div style="margin: 5px 0px 5px 0px;">
                                <input type="checkbox" class="k-checkbox" {{ isset($data['hubunganDengan_2']) && $data['hubunganDengan_2']['code'] == 'DN' ? 'checked' : '' }} >
                                <label class="k-checkbox-label" >Disfungsi neuromuskuler</label>
                            </div>
                            <div style="margin: 5px 0px 5px 0px;">
                                <input type="checkbox" class="k-checkbox" {{ isset($data['hubunganDengan_3']) && $data['hubunganDengan_3']['code'] == 'BAD' ? 'checked' : '' }} >
                                <label class="k-checkbox-label" >Benda asing dalam jalan nafas</label>

                            </div>
                            <div style="margin: 5px 0px 5px 0px;">
                                <input type="checkbox" class="k-checkbox" {{ isset($data['hubunganDengan_4']) && $data['hubunganDengan_4']['code'] == 'AJN' ? 'checked' : '' }} >
                                <label class="k-checkbox-label" >Adanya jalan nafas buatan</label>

                            </div>
                            <div style="margin: 5px 0px 5px 0px;">

                                <input type="checkbox" class="k-checkbox" {{ isset($data['hubunganDengan_5']) && $data['hubunganDengan_5']['code'] == 'ST' ? 'checked' : '' }} >
                                <label class="k-checkbox-label" >Sekresi yang tertahan</label>

                            </div>
                            <div style="margin: 5px 0px 5px 0px;">
                                <input type="checkbox" class="k-checkbox" {{ isset($data['hubunganDengan_6']) && $data['hubunganDengan_6']['code'] == 'HA' ? 'checked' : '' }} >
                                <label class="k-checkbox-label" >Hyperlasia</label>
                            </div>
                            <div style="margin: 5px 0px 5px 0px;">
                                <input type="checkbox" class="k-checkbox" {{ isset($data['hubunganDengan_7']) && $data['hubunganDengan_7']['code'] == 'PI' ? 'checked' : '' }} >
                                <label class="k-checkbox-label" >Proses infeksi</label>
                            </div>
                            <div style="margin: 5px 0px 5px 0px;">
                                <input type="checkbox" class="k-checkbox" {{ isset($data['hubunganDengan_8']) && $data['hubunganDengan_8']['code'] == 'RA' ? 'checked' : '' }} >
                                <label class="k-checkbox-label" >Respon alergi</label>
                            </div>
                            <div style="margin: 5px 0px 5px 0px;">
                                <input type="checkbox" class="k-checkbox" {{ isset($data['hubunganDengan_9']) && $data['hubunganDengan_9']['code'] == 'EF' ? 'checked' : '' }} >
                                <label class="k-checkbox-label" >Efek agen farmakologis (mis. Anestesi)</label>
                            </div>

                            <div style="margin-top:10px"><span style="font-weight: bold;">Data sujektif :</span>
                            </div>
                            <div style="margin: 5px 0px 5px 0px;">
                                <input type="checkbox" class="k-checkbox" {{ isset($data['dataSubjektif_0']) && $data['dataSubjektif_0']['code'] == 'DA' ? 'checked' : '' }} >
                                <label class="k-checkbox-label" >Dispanea</label>
                            </div>
                            <div style="margin: 5px 0px 5px 0px;">
                                <input type="checkbox" class="k-checkbox" {{ isset($data['dataSubjektif_1']) && $data['dataSubjektif_1']['code'] == 'OR' ? 'checked' : '' }} >
                                <label class="k-checkbox-label" >Othopnea</label>
                            </div>
                            <div style="margin: 5px 0px 5px 0px;">
                                <input type="checkbox" class="k-checkbox" {{ isset($data['dataSubjektif_2']) && $data['dataSubjektif_2']['code'] == 'SB' ? 'checked' : '' }} >
                                <label class="k-checkbox-label" >Sulit Bicara</label>
                            </div>

                            <div style="margin-top:10px"><span style="font-weight: bold;">Data objektif :</span>
                            </div>
                            <div style="margin: 5px 0px 5px 0px;">
                                <input type="checkbox" class="k-checkbox" {{ isset($data['dataObjektif_0']) && $data['dataObjektif_0']['code'] == 'BTE' ? 'checked' : '' }} >
                                <label class="k-checkbox-label" >Batuk tidak efektif atau tidak mampu batuk</label>
                            </div>
                            <div style="margin: 5px 0px 5px 0px;">
                                <input type="checkbox" class="k-checkbox" {{ isset($data['dataObjektif_1']) && $data['dataObjektif_1']['code'] == 'SB' ? 'checked' : '' }} >
                                <label class="k-checkbox-label" >Sputum berlebih</label>
                            </div>
                            <div style="margin: 5px 0px 5px 0px;">
                                <input type="checkbox" class="k-checkbox" {{ isset($data['dataObjektif_2']) && $data['dataObjektif_2']['code'] == 'OJN' ? 'checked' : '' }} >
                                <label class="k-checkbox-label" >Obstruksi jalan nafas</label>
                            </div>
                            <div style="margin: 5px 0px 5px 0px;">
                                <input type="checkbox" class="k-checkbox" {{ isset($data['dataObjektif_3']) && $data['dataObjektif_3']['code'] == 'MW' ? 'checked' : '' }} >
                                <label class="k-checkbox-label" >Mengi/wheezing</label>
                            </div>
                            <div style="margin: 5px 0px 5px 0px;">
                                <input type="checkbox" class="k-checkbox" {{ isset($data['dataObjektif_4']) && $data['dataObjektif_4']['code'] == 'RK' ? 'checked' : '' }} >
                                <label class="k-checkbox-label" >Rongki</label>
                            </div>


                            <div style="margin-top:10px">
                                <label class="k-checkbox-label" >Tanda-tanda vital :</label>

                            </div>
                            <div style="margin: 5px 0px 5px 0px;">
                                <div class="grid_12">
                                    <span>TD :</span>&nbsp;<span>
                                        <span style="font-size: 10pt;" color="#000000">
                                            {{isset($data['tekananDarah']) ? $data['tekananDarah'] : '-'}} 
                                        </span>
                                    </span>
                                </div>
                            </div>
                            <div style="margin: 5px 0px 5px 0px;">
                                <div class="grid_12">
                                    <span>N :</span>&nbsp;<span>
                                        <span style="font-size: 10pt;" color="#000000"> 
                                            {{isset($data['nadi']) ? $data['nadi'] : '-'}} 
                                        </span>
                                    </span>
                                </div>
                            </div>
                            <div style="margin: 5px 0px 5px 0px;">
                                <div class="grid_12">
                                    <span>RR :</span>&nbsp;<span>
                                        <span style="font-size: 10pt;" color="#000000">
                                            {{isset($data['pernapasan']) ? $data['pernapasan'] : '-'}}  
                                        </span>
                                    </span>
                                </div>
                            </div>
                            <div style="margin: 5px 0px 5px 0px;">
                                <div class="grid_12">
                                    <span>S :</span>&nbsp;<span>
                                        <span style="font-size: 10pt;" color="#000000"> 
                                            {{isset($data['suhu']) ? $data['suhu'] : '-'}} 
                                        </span>
                                    </span>
                                </div>
                            </div>
                            <div style="margin: 5px 0px 5px 0px;">
                                <div class="grid_12">
                                    <span>SPO2 :</span>&nbsp;<span>
                                        <span style="font-size: 10pt;" color="#000000"> 
                                            {{isset($data['spo2']) ? $data['spo2'] : '-'}} 
                                        </span>
                                    </span>
                                </div>
                            </div>
                            <div style="margin: 5px 0px 5px 0px;">
                                <input type="checkbox" class="k-checkbox" >
                                <label class="k-checkbox-label" >Hasil penunjang</label>
                                <span style="font-size: 10pt;" color="#000000">
                                    {{isset($data['hasilPenunjang']) ? $data['hasilPenunjang'] : '-'}} 
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
                                        {{isset($data['fotoThoraxs']) ? $data['fotoThoraxs'] : '-'}} 
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
                                        {{isset($data['laboratorium']) ? $data['laboratorium'] : '-'}} 
                                    </span>
                                    <!-- <input c-text-box type="text" filter="" class="k-textbox"
                                 /> -->
                                </div>

                            </div>
                        </td>


                        <td style="font-weight: 100;text-align: left;vertical-align: text-top;">
                            <div><span style="font-weight: bold;">Bersihan jalan nafas tidak efektif
                                    (L.01001)</span></div>

                            <div style="margin-top: 10px"><span style="font-weight: bold;">Kriteria hasil:</span>
                            </div>
                            <div style="margin: 5px 0px 5px 0px;">
                                <div class="grid_6">
                                    1. Batuk efektif :
                                </div>
                                <div class="grid_6">
                                    Meningkat
                                </div>

                                <div class="grid_6">
                                    2. Produksi sputum :
                                </div>
                                <div class="grid_6">
                                    Membaik
                                </div>

                                <div class="grid_6">
                                    3. Dispnea/Ortopnea :
                                </div>
                                <div class="grid_6">
                                    Menurun
                                </div>

                                <div class="grid_6">
                                    4. Frekuensi nafas :
                                </div>
                                <div class="grid_6">
                                    Membaik
                                </div>

                                <div class="grid_12">
                                    <span style="font-size: 10pt;" color="#000000">
                                      Hasil Kriteria : {{isset($data['kriteriaHasil']) ? $data['kriteriaHasil'] : '-'}} 
                                    </span>
                                    <!-- <input c-text-box type="text" filter="" class="k-textbox"
                                 /> -->
                                </div>
                                {{-- <div class="grid_12">
                                    <span style="font-size: 10pt;" color="#000000"> </span>
                                    <!-- <input c-text-box type="text" filter="" class="k-textbox"
                                 /> -->
                                </div> --}}

                            </div>

                        </td>
                        <td style="font-weight: 100;text-align: left;vertical-align: text-top;">
                            <div><span style="font-weight: bold;">Manajemen Jalan Napas (I.01011)</span></div>

                            <div style="margin-top:10px"><span style="font-weight: bold;">Observasi :</span></div>
                            <div style="margin: 5px 0px 5px 0px;">
                                <input type="checkbox" class="k-checkbox" {{ isset($data['dataObservasi_0']) && $data['dataObservasi_0']['code'] == 'MPN' ? 'checked' : '' }} >
                                <label class="k-checkbox-label" >Monitor pola napas (frekuensi,kedalaman,usaha napas)</label>
                            </div>
                            <div style="margin: 5px 0px 5px 0px;">
                                <input type="checkbox" class="k-checkbox" {{ isset($data['dataObservasi_1']) && $data['dataObservasi_1']['code'] == 'MB' ? 'checked' : '' }} >
                                <label class="k-checkbox-label" >Monitor bunyi napas tambahan (mis.gurgling,mengi, wheezing, ronchi
                                    kering)</label>
                            </div>
                            <div style="margin: 5px 0px 5px 0px;">
                                <input type="checkbox" class="k-checkbox" {{ isset($data['dataObservasi_2']) && $data['dataObservasi_2']['code'] == 'MS' ? 'checked' : '' }} >
                                <label class="k-checkbox-label" >Monitor sputum (jumlah, warna, aroma)</label>
                            </div>
                            <div style="margin: 5px 0px 5px 0px;">
                                <input type="checkbox" class="k-checkbox" {{ isset($data['dataObservasi_3']) && $data['dataObservasi_3']['code'] == 'MPR' ? 'checked' : '' }}>
                                <label class="k-checkbox-label">Masukkan pemantauan respirasi</label>
                            </div>
                            <div style="margin: 5px 0px 5px 0px;">
                                <label class="k-checkbox-label">Lainnya :  </label>
                                <span style="font-size: 10pt;" color="#000000">
                                    {{isset($data['keteranganObservasi']) ? $data['keteranganObservasi'] : '-'}}
                                </span>
                                <!-- <input c-text-box type="text" filter="" class="k-textbox"
                             /> -->
                            </div>

                            <div style="margin-top:10px"><span style="font-weight: bold;">Terapeutik :</span>
                            </div>
                            <div style="margin: 5px 0px 5px 0px;">
                                <input type="checkbox" class="k-checkbox" {{ isset($data['dataTerapeutik_0']) && $data['dataTerapeutik_0']['code'] == 'PKJ' ? 'checked' : '' }} >
                                <label class="k-checkbox-label" >Pertahankan kepatenan jalan napas dengan head- tilt dan chin-lift
                                    (jaw-thrust jika curiga trauma servical)</label>
                            </div>
                            <div style="margin: 5px 0px 5px 0px;">
                                <input type="checkbox" class="k-checkbox" {{ isset($data['dataTerapeutik_1']) && $data['dataTerapeutik_1']['code'] == 'PSF' ? 'checked' : '' }} >
                                <label class="k-checkbox-label" >Posisikan semi-fowler atau fowler</label>
                            </div>
                            <div style="margin: 5px 0px 5px 0px;">
                                <input type="checkbox" class="k-checkbox" {{ isset($data['dataTerapeutik_2']) && $data['dataTerapeutik_2']['code'] == 'BM' ? 'checked' : '' }} >
                                <label class="k-checkbox-label" >Berikan minuman hangat</label>
                            </div>
                            <div style="margin: 5px 0px 5px 0px;">
                                <input type="checkbox" class="k-checkbox" {{ isset($data['dataTerapeutik_3']) && $data['dataTerapeutik_3']['code'] == 'LFD' ? 'checked' : '' }} >
                                <label class="k-checkbox-label" >Lakukan fisioterapi dada, jika perlu</label>
                            </div>
                            <div style="margin: 5px 0px 5px 0px;">
                                <input type="checkbox" class="k-checkbox" {{ isset($data['dataTerapeutik_4']) && $data['dataTerapeutik_4']['code'] == 'LPL' ? 'checked' : '' }} >
                                <label class="k-checkbox-label" >Lakukan penghisapan lendir</label>
                            </div>
                            <div style="margin: 5px 0px 5px 0px;">
                                <input type="checkbox" class="k-checkbox" {{ isset($data['dataTerapeutik_5']) && $data['dataTerapeutik_5']['code'] == 'LHS' ? 'checked' : '' }} >
                                <label class="k-checkbox-label" >Lakukan hiperoksigenasi sebelum penghisapan endotrakeal</label>
                            </div>
                            <div style="margin: 5px 0px 5px 0px;">
                                <input type="checkbox" class="k-checkbox" {{ isset($data['dataTerapeutik_6']) && $data['dataTerapeutik_6']['code'] == 'KSB' ? 'checked' : '' }} >
                                <label class="k-checkbox-label" >Keluarkan sumbatan benda padat dengan forsep McGill</label>
                            </div>
                            <div style="margin: 5px 0px 5px 0px;">
                                <input type="checkbox" class="k-checkbox" {{ isset($data['dataTerapeutik_7']) && $data['dataTerapeutik_7']['code'] == 'BO' ? 'checked' : '' }} >
                                <label class="k-checkbox-label" >Berikan oksigen, jika perlu</label>

                            </div>
                            <div style="margin: 5px 0px 5px 0px;">
                                <input type="checkbox" class="k-checkbox" {{ isset($data['dataTerapeutik_8']) && $data['dataTerapeutik_8']['code'] == 'MFI' ? 'checked' : '' }} >
                                <label class="k-checkbox-label" >Monitor frekuensi, irama, kedalam dan upaya napas</label>
                            </div>
                            <div style="margin: 5px 0px 5px 0px;">
                                <input type="checkbox" class="k-checkbox" {{ isset($data['dataTerapeutik_9']) && $data['dataTerapeutik_9']['code'] == 'MPS' ? 'checked' : '' }} >
                                <label class="k-checkbox-label" >Monitor pola napas</label>
                            </div>
                            <div style="margin: 5px 0px 5px 0px;">
                                <input type="checkbox" class="k-checkbox" {{ isset($data['dataTerapeutik_10']) && $data['dataTerapeutik_10']['code'] == 'MKB' ? 'checked' : '' }} >
                                <label class="k-checkbox-label" >Monitor kemapuan batuk efektif</label>
                            </div>
                            <div style="margin: 5px 0px 5px 0px;">
                                <input type="checkbox" class="k-checkbox" {{ isset($data['dataTerapeutik_11']) && $data['dataTerapeutik_11']['code'] == 'MAP' ? 'checked' : '' }} >
                                <label class="k-checkbox-label" >Monitor adanya produksi sputum</label>
                            </div>
                            <div style="margin: 5px 0px 5px 0px;">
                                <input type="checkbox" class="k-checkbox" {{ isset($data['dataTerapeutik_12']) && $data['dataTerapeutik_12']['code'] == 'MAS' ? 'checked' : '' }} >
                                <label class="k-checkbox-label" >Monitor adanya sumbatan jalan napas</label>
                            </div>
                            <div style="margin: 5px 0px 5px 0px;">
                                <input type="checkbox" class="k-checkbox" {{ isset($data['dataTerapeutik_13']) && $data['dataTerapeutik_13']['code'] == 'ABN' ? 'checked' : '' }} >
                                <label class="k-checkbox-label" >Auskultasi bunyi napas</label>
                            </div>
                            <div style="margin: 5px 0px 5px 0px;">
                                <input type="checkbox" class="k-checkbox" {{ isset($data['dataTerapeutik_14']) && $data['dataTerapeutik_14']['code'] == 'MSO' ? 'checked' : '' }} >
                                <label class="k-checkbox-label" >Monitor saturasi oksigen</label>
                            </div>
                            <div style="margin: 5px 0px 5px 0px;">
                                <input type="checkbox" class="k-checkbox" {{ isset($data['dataTerapeutik_15']) && $data['dataTerapeutik_15']['code'] == 'MD' ? 'checked' : '' }} >
                                <label class="k-checkbox-label" >Monitor AGD</label>
                            </div>
                            <div style="margin: 5px 0px 5px 0px;">
                                {{-- <input type="checkbox" class="k-checkbox" {{ isset($data['dataTerapeutik_']) && $data['dataTerapeutik_']['code'] == 'MPR' ? 'checked' : '' }} > --}}
                                <label class="k-checkbox-label" ></label>
                            </div>



                            <div style="margin-top:10px"><span style="font-weight: bold;">Edukasi :</span></div>
                            <div style="margin: 5px 0px 5px 0px;">
                                <input type="checkbox" class="k-checkbox" {{ isset($data['dataEdukasi_0']) && $data['dataEdukasi_0']['code'] == 'AM' ? 'checked' : '' }} >
                                <label class="k-checkbox-label" >Anjurkan minuman air hangat sesuai kebutuhan</label>
                            </div>
                            <div style="margin: 5px 0px 5px 0px;">
                                <input type="checkbox" class="k-checkbox" {{ isset($data['dataEdukasi_1']) && $data['dataEdukasi_1']['code'] == 'AT' ? 'checked' : '' }} >
                                <label class="k-checkbox-label" >Ajarkan tehnik batuk efektif</label>
                            </div>
                            <div style="margin: 5px 0px 5px 0px;">
                                <input type="checkbox" class="k-checkbox" {{ isset($data['dataEdukasi_2']) && $data['dataEdukasi_2']['code'] == 'LK' ? 'checked' : '' }} >
                                <label class="k-checkbox-label" >Libatkan keluarga</label>
                            </div>


                            <div style="margin-top:10px"><span style="font-weight: bold;">Kolaborasi :</span>
                            </div>
                            <div style="margin: 5px 0px 5px 0px;">
                                <input type="checkbox" class="k-checkbox" {{ isset($data['dataKolaborasi']) && $data['dataKolaborasi']['code'] == 'KPB' ? 'checked' : '' }} >
                                <label class="k-checkbox-label" >Kolaborasi pemberian bronkodilator, ekspektoran, mukolitik, jika
                                    perlu</label>
                            </div>


                        </td>


                        <td style="font-weight: 100;text-align: left;vertical-align: text-top;">
                            <div class="grid_12" style="font-weight: bold">
                                Masalah teratasi
                            </div>
                            <div class="grid_12" style="margin: 5px 0px 5px 0px;">Tanggal dan jam</div>
                            <div class="grid_12" style="margin: 5px 0px 5px 0px;">
                                <span style="font-size: 10pt;" color="#000000"></span>
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
                        @endforelse
                        <br> --}}
                        <b>
                            <span style="font-size: 9pt;" color="#000000">
                                Nama Perawat </span>
                        </b>
                        <br>
                        <b>
                            <span style="font-size: 9pt;" color="#000000">
                                (  )</span>
                        </b>
                        </td>

                    </tr>
                </table>

            </td>

        </tr>
</table>

</table>


@endsection
