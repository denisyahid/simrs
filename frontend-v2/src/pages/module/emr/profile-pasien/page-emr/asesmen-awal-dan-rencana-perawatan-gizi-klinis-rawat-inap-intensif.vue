<template>
    <div class="form-layout is-stacked-2">
        <div class="form-outer" style="margin-top:15px">
            <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
                <div class="form-header-inner">
                    <div class="left">
                        <h3>Asesmen Awal Dan Rencana Perawatan Gizi Klinis Rawat Inap Dan Intensif</h3>
                    </div>
                    <div class="right">
                        <ButtonEmr :NOREC_EMRPASIEN="NOREC_EMRPASIEN" :COLLECTION="COLLECTION" :isLoading="isLoading"
                            @simpan="simpan" @simpanTemplate="simpanTemplate" :isHideCetak="true"
                            @kembaliKeun="kembaliKeun" isHideST></ButtonEmr>
                    </div>
                </div>
            </div>

            <!-- form baru -->
            <div class="column">
                <!-- <div class="column is-12 buttons mb-0 mt-0" style="margin:10px;vertical-align:middle">
                    <VButton type="button" rounded outlined color="primary" raised icon="feather:folder"
                        isLoading="false" @click="pilihTemplateFix(index)"> Pilih Template
                    </VButton> -->
                    <!-- <VButton type="button" rounded outlined color="info" raised icon="feather:file-text"
                            isLoading="false" @click="pilihTemplate(index)"> Pilih Riwayat
                        </VButton> -->
                <!-- </div> -->

                <!-- <hr style="border-top: 1px dashed lightgray;background-color:white" class="mt-0 mb-1">

                <div class="column is-12">
                    <h1><b>Nama Template</b>&emsp;&emsp;<span style="color:red">**Hanya diisi jika ingin membuat
                            template</span></h1>
                    <VField>
                        <VControl>
                            <VTextarea v-model="input.namatemplate" rows="1">
                            </VTextarea>
                        </VControl>
                    </VField>
                </div>

                <hr style="border-top: 1px dashed lightgray;background-color:white" class="mt-0 mb-1"> -->

                <div class="column columns is-multiline pb-0">
                    <div class="column is-3">
                        <h1>Tanggal & Jam</h1>
                        <VDatePicker v-model="input.DTanggalMasuk" mode="dateTime" trim-weeks is24hr>
                            <template #default="{ inputValue, inputEvents }">
                                <VControl icon="feather:calendar" fullwidth>
                                    <VInput :value="inputValue" placeholder="Tanggal masuk..." v-on="inputEvents" />
                                </VControl>
                            </template>
                        </VDatePicker>
                    </div>
                </div>

                <hr style="border-top: 1px dashed lightgray;background-color:white" class="mt-0 mb-1">

                <div class="column columns is-multiline pb-0">
                    <div class="column is-12">
                        <h1>Keluhan (*)</h1>
                        <VField>
                            <VTextarea rows="2" v-model="input.TAKeluhan"></VTextarea>
                        </VField>
                    </div>
                </div>

                <hr style="border-top: 1px dashed lightgray;background-color:white" class="mt-0 mb-1">

                <div class="column columns is-multiline pb-0">
                    <div class="column is-12 pb-0">
                        <h1 style="font-size: large;">Antropometri</h1>
                    </div>
                    <div class="column is-2">
                        <h1>BB Aktual</h1>
                        <VField addons>
                            <VControl expanded>
                                <VInput type="text" class="input" v-model="input.TBSBeratBadanAktual" />
                            </VControl>
                            <VControl class="field-addon-body">
                                <VButton static>Kg</VButton>
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-2">
                        <h1>TB/PB</h1>
                        <VField addons>
                            <VControl expanded>
                                <VInput type="text" class="input" v-model="input.TBS_TB_PB" />
                            </VControl>
                            <VControl class="field-addon-body">
                                <VButton static>Cm</VButton>
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-2">
                        <h1>LiLa</h1>
                        <VField addons>
                            <VControl expanded>
                                <VInput type="text" class="input" v-model="input.TBSLiLa" />
                            </VControl>
                            <VControl class="field-addon-body">
                                <VButton static>Cm</VButton>
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-2">
                        <h1>BB Estimasi</h1>
                        <VField addons>
                            <VControl expanded>
                                <VInput type="text" class="input" v-model="input.TBSBB_Estimasi" />
                            </VControl>
                            <VControl class="field-addon-body">
                                <VButton static>Kg</VButton>
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-2">
                        <h1>BMI</h1>
                        <VField addons>
                            <VControl expanded>
                                <VInput type="text" class="input" v-model="input.TBS_BMI" />
                            </VControl>
                            <VControl class="field-addon-body">
                                <VButton static>Kg/m<sup>2</sup></VButton>
                            </VControl>
                        </VField>
                    </div>
                </div>

                <hr style="border-top: 1px dashed lightgray;background-color:white" class="mt-0 mb-1">

                <div class="column is-12">
                    <h1 style="font-size: large;">Riwayat Medis</h1>
                    <VField>
                        <VTextarea rows="2" v-model="input.TARiwayatMedis"></VTextarea>
                    </VField>
                </div>

                <hr style="border-top: 1px dashed lightgray;background-color:white" class="mt-0 mb-1">

                <!-- Table for Jawaban Section -->
                <div class="column">
                    <table class="table">
                        <tr>
                            <th style="text-align: center;vertical-align:middle" rowspan="2">Deskripsi</th>
                            <th style="text-align: center;vertical-align:middle" rowspan="2">Jawaban</th>
                            <th style="text-align: center;vertical-align:middle" colspan="3">Skor GGA</th>
                        </tr>
                        <tr>
                            <th style="text-align: center;vertical-align:middle">A</th>
                            <th style="text-align: center;vertical-align:middle">B</th>
                            <th style="text-align: center;vertical-align:middle">C</th>
                        </tr>
                        <tr>
                            <th colspan="5" style="background-color: lightgray;">1. Perubahan BB biasanya</th>
                        </tr>
                        <tr>
                            <td>• BB Biasanya (kg)</td>
                            <td>
                                <VControl>
                                    <VInput type="text" class="input" v-model="TBBB_Biasanya" />
                                </VControl>
                            </td>
                            <td style="text-align: center"></td>
                            <td style="text-align: center"></td>
                            <td style="text-align: center"></td>
                        </tr>
                        <tr>
                            <td>• BB Awal masuk RS (kg)</td>
                            <td>
                                <VControl>
                                    <VInput type="text" class="input" v-model="TBBB_AwalMasuk_RS" />
                                </VControl>
                            </td>
                            <td style="text-align: center;"></td>
                            <td style="text-align: center;"></td>
                            <td style="text-align: center;"></td>
                        </tr>
                        <tr>
                            <td rowspan="6" style="vertical-align: middle;">
                                Perubahan BB biasanya
                                <br>BB Biasanya - BB sekarang
                                <br>______________________ x 100%
                                <br>BB biasanya
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <VCheckbox class="p-0" color="primary" square true-value="true" label="1. Tidak ada"
                                    v-model="input.CB_1A" />
                            </td>
                            <td style="text-align: center;">A</td>
                            <td style="text-align: center;"></td>
                            <td style="text-align: center;"></td>
                        </tr>
                        <tr>
                            <td>
                                <VCheckbox class="p-0" color="primary" square true-value="true" label="2. < 5%"
                                    v-model="input.CB_1B" />
                            </td>
                            <td style="text-align: center;">A</td>
                            <td style="text-align: center;"></td>
                            <td style="text-align: center;"></td>
                        </tr>
                        <tr>
                            <td>
                                <VCheckbox class="p-0" color="primary" square true-value="true" label="3. < 5%"
                                    v-model="input.CB_1C" />
                            </td>
                            <td style="text-align: center;"></td>
                            <td style="text-align: center;">B</td>
                            <td style="text-align: center;"></td>
                        </tr>
                        <tr>
                            <td>
                                <VCheckbox class="p-0" color="primary" square true-value="true" label="4. > 10%"
                                    v-model="input.CB_1D" />
                            </td>
                            <td style="text-align: center;"></td>
                            <td style="text-align: center;"></td>
                            <td style="text-align: center;">C</td>
                        </tr>
                        <tr>
                            <td>
                                <VCheckbox class="p-0" color="primary" square true-value="true"
                                    label="5. Berat badan turun (pengakuan pasien)" v-model="input.CB_1E" />
                            </td>
                            <td style="text-align: center;"></td>
                            <td style="text-align: center;"></td>
                            <td style="text-align: center;">C</td>
                        </tr>
                        <tr>
                            <th colspan="5" style="background-color: lightgray;">2. Asupan Makanan</th>
                        </tr>
                        <tr>
                            <td rowspan="2">Ada perubahan?</td>
                            <td>
                                <VCheckbox class="p-0" color="primary" square true-value="true" label="1. Ya"
                                    v-model="input.CB_2A" />
                            </td>
                            <td style="text-align: center;"></td>
                            <td style="text-align: center;"></td>
                            <td style="text-align: center;"></td>
                        </tr>
                        <tr>
                            <td>
                                <VCheckbox class="p-0" color="primary" square true-value="true" label="2. Tidak"
                                    v-model="input.CB_2B" />
                            </td>
                            <td style="text-align: center;"></td>
                            <td style="text-align: center;"></td>
                            <td style="text-align: center;"></td>
                        </tr>
                        <tr>
                            <td rowspan="3">Perubahan dan jumlah asupan</td>
                            <td>
                                <VCheckbox class="p-0" color="primary" square true-value="true"
                                    label="1. Asupan cukup dan tidak ada perubahan" v-model="input.CB_2C" />
                            </td>
                            <td style="text-align: center;">A</td>
                            <td style="text-align: center;"></td>
                            <td style="text-align: center;"></td>
                        </tr>
                        <tr>
                            <td>
                                <VCheckbox class="p-0" color="primary" square true-value="true"
                                    label="2. Asupan menurun tapi tahap ringan dari pada sebelum sakit"
                                    v-model="input.CB_2D" />
                            </td>
                            <td style="text-align: center;"></td>
                            <td style="text-align: center;">B</td>
                            <td style="text-align: center;"></td>
                        </tr>
                        <tr>
                            <td>
                                <VCheckbox class="p-0" color="primary" square true-value="true"
                                    label="3. asupan tidak cukup dan menurun tahap berat dari pada sebelum sakit."
                                    v-model="input.CB_2E" />
                            </td>
                            <td style="text-align: center;"></td>
                            <td style="text-align: center;"></td>
                            <td style="text-align: center;">C</td>
                        </tr>
                        <tr>
                            <td rowspan="3">Lamanya dan derajat perubahan asupan makanan</td>
                            <td>
                                <VCheckbox class="p-0" color="primary" square true-value="true"
                                    label="1. < 2 minggu, sedikit atau tanpa perubahan." v-model="input.CB_2F" />
                            </td>
                            <td style="text-align: center;">A</td>
                            <td style="text-align: center;"></td>
                            <td style="text-align: center;"></td>
                        </tr>
                        <tr>
                            <td>
                                <VCheckbox class="p-0" color="primary" square true-value="true"
                                    label="2. >2 minggu, perubahan ringan sampai sedang." v-model="input.CB_2G" />
                            </td>
                            <td style="text-align: center;"></td>
                            <td style="text-align: center;">B</td>
                            <td style="text-align: center;"></td>
                        </tr>
                        <tr>
                            <td>
                                <VCheckbox class="p-0" color="primary" square true-value="true"
                                    label="3. tidak bisa makan, perubahan drastis." v-model="input.CB_2H" />
                            </td>
                            <td style="text-align: center;"></td>
                            <td style="text-align: center;"></td>
                            <td style="text-align: center;">C</td>
                        </tr>
                    </table>

                    <table class="table">
                        <tr>
                            <th style="text-align: center;vertical-align:middle" rowspan="2">Deskripsi</th>
                            <th style="text-align: center;vertical-align:middle" colspan="3" rowspan="2">Lamanya</th>
                            <th style="text-align: center;vertical-align:middle" colspan="3">Skor GGA</th>
                        </tr>
                        <tr>
                            <th style="text-align: center;vertical-align:middle">A</th>
                            <th style="text-align: center;vertical-align:middle">B</th>
                            <th style="text-align: center;vertical-align:middle">C</th>
                        </tr>
                        <tr>
                            <th colspan="7" style="background-color: lightgray;">3. Gejala Gastrointestinal</th>
                        </tr>
                        <tr>
                            <td>Anoreksia</td>
                            <td>
                                <VCheckbox class="p-0" color="primary" square true-value="true_a"
                                    label="a. tidak pernah" v-model="input.CB_3A" />
                            </td>
                            <td>
                                <VCheckbox class="p-0" color="primary" square true-value="true_b"
                                    label="b. 1-3x/ minggu" v-model="input.CB_3A" />
                            </td>
                            <td>
                                <VCheckbox class="p-0" color="primary" square true-value="true_c" label="c. setiap hari"
                                    v-model="input.CB_3A" />
                            </td>
                            <td style="text-align: center;"></td>
                            <td style="text-align: center;"></td>
                            <td style="text-align: center;"></td>
                        </tr>
                        <tr>
                            <td>Mual</td>
                            <td>
                                <VCheckbox class="p-0" color="primary" square true-value="true_a"
                                    label="a. tidak pernah" v-model="input.CB_3B" />
                            </td>
                            <td>
                                <VCheckbox class="p-0" color="primary" square true-value="true_b"
                                    label="b. 1-3x/ minggu" v-model="input.CB_3B" />
                            </td>
                            <td>
                                <VCheckbox class="p-0" color="primary" square true-value="true_c" label="c. setiap hari"
                                    v-model="input.CB_3B" />
                            </td>
                            <td style="text-align: center;"></td>
                            <td style="text-align: center;"></td>
                            <td style="text-align: center;"></td>
                        </tr>
                        <tr>
                            <td>Muntah</td>
                            <td>
                                <VCheckbox class="p-0" color="primary" square true-value="true_a"
                                    label="a. tidak pernah" v-model="input.CB_3C" />
                            </td>
                            <td>
                                <VCheckbox class="p-0" color="primary" square true-value="true_b"
                                    label="b. 1-3x/ minggu" v-model="input.CB_3C" />
                            </td>
                            <td>
                                <VCheckbox class="p-0" color="primary" square true-value="true_c" label="c. setiap hari"
                                    v-model="input.CB_3C" />
                            </td>
                            <td style="text-align: center;"></td>
                            <td style="text-align: center;"></td>
                            <td style="text-align: center;"></td>
                        </tr>
                        <tr>
                            <td>Diare</td>
                            <td>
                                <VCheckbox class="p-0" color="primary" square true-value="true_a"
                                    label="a. tidak pernah" v-model="input.CB_3D" />
                            </td>
                            <td>
                                <VCheckbox class="p-0" color="primary" square true-value="true_b"
                                    label="b. 1-3x/ minggu" v-model="input.CB_3D" />
                            </td>
                            <td>
                                <VCheckbox class="p-0" color="primary" square true-value="true_c" label="c. setiap hari"
                                    v-model="input.CB_3D" />
                            </td>
                            <td style="text-align: center;"></td>
                            <td style="text-align: center;"></td>
                            <td style="text-align: center;"></td>
                        </tr>
                        <tr>
                            <td colspan="4">Keterangan</td>
                            <td style="text-align: center;"></td>
                            <td style="text-align: center;"></td>
                            <td style="text-align: center;"></td>
                        </tr>
                        <tr>
                            <td colspan="4">
                                <VCheckbox class="p-0" color="primary" square true-value="true"
                                    label="1. Jika beberapa gejala, tidak ada gejala, sebentar-sebentar"
                                    v-model="input.CB_3E" />
                            </td>
                            <td style="text-align: center;">A</td>
                            <td style="text-align: center;"></td>
                            <td style="text-align: center;"></td>
                        </tr>
                        <tr>
                            <td colspan="4">
                                <VCheckbox class="p-0" color="primary" square true-value="true"
                                    label="2. Jika ada beberapa gejala > 2 minggu" v-model="input.CB_3F" />
                            </td>
                            <td style="text-align: center;"></td>
                            <td style="text-align: center;">B</td>
                            <td style="text-align: center;"></td>
                        </tr>
                        <tr>
                            <td colspan="4">
                                <VCheckbox class="p-0" color="primary" square true-value="true"
                                    label="3. Jika lebih dari satu atau semua gejala setiap hari/teratur > 2 minggu"
                                    v-model="input.CB_3G" />
                            </td>
                            <td style="text-align: center;"></td>
                            <td style="text-align: center;"></td>
                            <td style="text-align: center;">C</td>
                        </tr>
                    </table>

                    <table class="table">
                        <tr>
                            <th style="text-align: center;vertical-align:middle" rowspan="2">Deskripsi</th>
                            <th style="text-align: center;vertical-align:middle" rowspan="2">Jawaban</th>
                            <th style="text-align: center;vertical-align:middle" colspan="3">Skor GGA</th>
                        </tr>
                        <tr>
                            <th style="text-align: center;vertical-align:middle">A</th>
                            <th style="text-align: center;vertical-align:middle">B</th>
                            <th style="text-align: center;vertical-align:middle">C</th>
                        </tr>
                        <tr>
                            <th colspan="5" style="background-color: lightgray;">4. Kapasitas Fungsional</th>
                        </tr>
                        <tr>
                            <td rowspan="2">• Ada perubahan kekuatan/stamina tubuh?</td>
                            <td>
                                <VCheckbox class="p-0" color="primary" square true-value="true" label="1. Ya"
                                    v-model="input.CB_4A" />
                            </td>
                            <td style="text-align: center"></td>
                            <td style="text-align: center"></td>
                            <td style="text-align: center"></td>
                        </tr>
                        <tr>
                            <td>
                                <VCheckbox class="p-0" color="primary" square true-value="true" label="2. Tidak"
                                    v-model="input.CB_4B" />
                            </td>
                            <td style="text-align: center"></td>
                            <td style="text-align: center"></td>
                            <td style="text-align: center"></td>
                        </tr>
                        <tr>
                            <td rowspan="2">• Bila ada perubahan</td>
                            <td>
                                <VCheckbox class="p-0" color="primary" square true-value="true" label="1. Meningkat"
                                    v-model="input.CB_4C" />
                            </td>
                            <td style="text-align: center"></td>
                            <td style="text-align: center"></td>
                            <td style="text-align: center"></td>
                        </tr>
                        <tr>
                            <td>
                                <VCheckbox class="p-0" color="primary" square true-value="true" label="2. Menurun"
                                    v-model="input.CB_4D" />
                            </td>
                            <td style="text-align: center"></td>
                            <td style="text-align: center"></td>
                            <td style="text-align: center"></td>
                        </tr>
                        <tr>
                            <td rowspan="3">• Deskripsi keadaan fungsi tubuh</td>
                            <td>
                                <VCheckbox class="p-0" color="primary" square true-value="true"
                                    label="1. aktifitas normal, tidak ada kelainan, kekuatan/stamina tetap"
                                    v-model="input.CB_4E" />
                            </td>
                            <td style="text-align: center">A</td>
                            <td style="text-align: center"></td>
                            <td style="text-align: center"></td>
                        </tr>
                        <tr>
                            <td>
                                <VCheckbox class="p-0" color="primary" square true-value="true"
                                    label="2. aktifitas ringan, mengalami hanya sedikit penurunan (tahap ringan)"
                                    v-model="input.CB_4F" />
                            </td>
                            <td style="text-align: center"></td>
                            <td style="text-align: center">B</td>
                            <td style="text-align: center"></td>
                        </tr>
                        <tr>
                            <td>
                                <VCheckbox class="p-0" color="primary" square true-value="true"
                                    label="3. tanpa aktifitas/di tempat tidur, penurunan kekuatan/stamina tahap buruk"
                                    v-model="input.CB_4G" />
                            </td>
                            <td style="text-align: center"></td>
                            <td style="text-align: center"></td>
                            <td style="text-align: center">C</td>
                        </tr>
                        <tr>
                            <th colspan="5" style="background-color: lightgray;">5. Penyakit dan Hubungannya dengan
                                Kebutuhan Gizi
                                Klinik</th>
                        </tr>
                        <tr>
                            <td rowspan="2">• Secara umum ada gangguan stress metabolik akut?</td>
                            <td>
                                <VCheckbox class="p-0" color="primary" square true-value="true" label="1. Ya"
                                    v-model="input.CB_5A" />
                            </td>
                            <td style="text-align: center">A</td>
                            <td style="text-align: center"></td>
                            <td style="text-align: center"></td>
                        </tr>
                        <tr>
                            <td>
                                <VCheckbox class="p-0" color="primary" square true-value="true" label="2. Tidak"
                                    v-model="input.CB_5B" />
                            </td>
                            <td style="text-align: center"></td>
                            <td style="text-align: center"></td>
                            <td style="text-align: center"></td>
                        </tr>
                        <tr>
                            <td rowspan="2">• Secara umum ada gangguan stress metabolik akut?</td>
                            <td>
                                <VCheckbox class="p-0" color="primary" square true-value="true"
                                    label="1. rendah/sedang (mis:infeksi, penyakit jantung kongestif)"
                                    v-model="input.CB_5C" />
                            </td>
                            <td style="text-align: center"></td>
                            <td style="text-align: center">B</td>
                            <td style="text-align: center"></td>
                        </tr>
                        <tr>
                            <td>
                                <VCheckbox class="p-0" color="primary" square true-value="true"
                                    label="2. tinggi (mis: colitis ulseratif, diare, kanker)" v-model="input.CB_5D" />
                            </td>
                            <td style="text-align: center"></td>
                            <td style="text-align: center"></td>
                            <td style="text-align: center">C</td>
                        </tr>
                    </table>

                    <table class="table">
                        <tr>
                            <th style="text-align: center;vertical-align:middle" rowspan="2">Deskripsi</th>
                            <th style="text-align: center;vertical-align:middle" rowspan="2" colspan="3">Jawaban</th>
                            <th style="text-align: center;vertical-align:middle" colspan="3">Skor GGA</th>
                        </tr>
                        <tr>
                            <th style="text-align: center;vertical-align:middle">A</th>
                            <th style="text-align: center;vertical-align:middle">B</th>
                            <th style="text-align: center;vertical-align:middle">C</th>
                        </tr>
                        <tr>
                            <td>1. Kehilangan lemak subkutan ( Bisep, Trisep, Subskapula, Suprailiaka)</td>
                            <td>
                                <VCheckbox class="p-0" color="primary" square true-value="true_a" label="A. Tidak ada"
                                    v-model="input.CB_6A" />
                            </td>
                            <td>
                                <VCheckbox class="p-0" color="primary" square true-value="true_b"
                                    label="B. Beberapa tempat" v-model="input.CB_6A" />
                            </td>
                            <td>
                                <VCheckbox class="p-0" color="primary" square true-value="true_c"
                                    label="C. Semua tempat" v-model="input.CB_6A" />
                            </td>
                            <td style="text-align: center">A</td>
                            <td style="text-align: center">B</td>
                            <td style="text-align: center">C</td>
                        </tr>
                        <tr>
                            <td>2. Kehilangan massa otot pada (pelipis, tulang selangka, tulang belikat, tulang
                                iga,betis, lutut)</td>
                            <td>
                                <VCheckbox class="p-0" color="primary" square true-value="true_a" label="A. Tidak ada"
                                    v-model="input.CB_6B" />
                            </td>
                            <td>
                                <VCheckbox class="p-0" color="primary" square true-value="true_b"
                                    label="B. Beberapa tempat" v-model="input.CB_6B" />
                            </td>
                            <td>
                                <VCheckbox class="p-0" color="primary" square true-value="true_c"
                                    label="C. Semua tempat" v-model="input.CB_6B" />
                            </td>
                            <td style="text-align: center">A</td>
                            <td style="text-align: center">B</td>
                            <td style="text-align: center">C</td>
                        </tr>
                        <tr>
                            <td>3. Edema</td>
                            <td>
                                <VCheckbox class="p-0" color="primary" square true-value="true_a" label="A. Tidak ada"
                                    v-model="input.CB_6C" />
                            </td>
                            <td>
                                <VCheckbox class="p-0" color="primary" square true-value="true_b" label="B. Sedang"
                                    v-model="input.CB_6C" />
                            </td>
                            <td>
                                <VCheckbox class="p-0" color="primary" square true-value="true_c" label="C. Berat"
                                    v-model="input.CB_6C" />
                            </td>
                            <td style="text-align: center">A</td>
                            <td style="text-align: center">B</td>
                            <td style="text-align: center">C</td>
                        </tr>
                        <tr>
                            <td>4. Asites</td>
                            <td>
                                <VCheckbox class="p-0" color="primary" square true-value="true_a" label="A. Tidak ada"
                                    v-model="input.CB_6D" />
                            </td>
                            <td>
                                <VCheckbox class="p-0" color="primary" square true-value="true_b" label="B. Sedang"
                                    v-model="input.CB_6D" />
                            </td>
                            <td>
                                <VCheckbox class="p-0" color="primary" square true-value="true_c" label="C. Berat"
                                    v-model="input.CB_6D" />
                            </td>
                            <td style="text-align: center">A</td>
                            <td style="text-align: center">B</td>
                            <td style="text-align: center">C</td>
                        </tr>
                        <tr>
                            <td colspan="4">
                                <b>Keseluruhan Skor SGA</b><br>
                                A = Gizi Baik/ Normal ( Skor “ A” pada >50% kategori atau ada peningkatan signifikan<br>
                                B = Gizi Kurang – Sedang (tidak terindikasi jelas pada “A” atau “C”<br>
                                C = Gizi Buruk ( skor “C” pada >50% kategori, tanda – tanda fisik signifikan
                            </td>
                            <th colspan="3" style="vertical-align: middle;">
                                <VField>
                                    <VTextarea rows="2" v-model="input.TAKeseluruhanSkor"></VTextarea>
                                </VField>
                            </th>
                        </tr>
                    </table>
                </div>

                <hr style="border-top: 1px dashed lightgray;background-color:white" class="mt-0 mb-1">

                <div class="column is-12">
                    <h1 style="font-size: large;">Laboratorium</h1>
                    <VField>
                        <VTextarea rows="2" v-model="input.TALaboratorium"></VTextarea>
                    </VField>
                </div>

                <hr style="border-top: 1px dashed lightgray;background-color:white" class="mt-0 mb-1">

                <div class="column is-12">
                    <h1 style="font-size: large;">Diagnosis Gizi</h1>
                    <VField>
                        <VTextarea rows="2" v-model="input.TADiagnosisGizi"></VTextarea>
                    </VField>
                </div>

                <hr style="border-top: 1px dashed lightgray;background-color:white" class="mt-0 mb-1">

                <div class="column is-12">
                    <div class="column is-12 pt-0 is-flex" style="justify-content:center">
                        <h1>Rencana Kerja Dokter (Plan Of Care)</h1>
                    </div>
                    <table class="tg">
                        <thead>
                            <tr>
                                <th style="width: 10%;">#</th>
                                <th style="width: 20%;">Daftar Masalah</th>
                                <th style="width: 20%;">Rencana Intervensi</th>
                                <th style="width: 20%;">Target</th>
                                <th style="width: 20%;">Instruksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(item, index) in input.details" :key="index">
                                <td style="vertical-align: inherit">
                                    <div class="column">
                                        <VButtons style="justify-content:space-around">
                                            <VIconButton type="button" raised circle icon="feather:plus"
                                                @click="addNewItem()" color="info" v-tooltip.bubble="'Tambah '">
                                            </VIconButton>
                                            <VIconButton class="mt-1" v-if="index > 0" type="button" raised circle
                                                icon="feather:trash" @click="removeItem(index)" color="danger">
                                            </VIconButton>
                                        </VButtons>
                                    </div>
                                </td>
                                <td>
                                    <VField>
                                        <VTextarea rows="2" v-model="item.TAdaftarMasalah"></VTextarea>
                                    </VField>
                                </td>
                                <td>
                                    <VField>
                                        <VTextarea rows="2" v-model="item.TArencanaIntervensi"></VTextarea>
                                    </VField>
                                </td>
                                <td>
                                    <VField>
                                        <VTextarea rows="2" v-model="item.TAtarget"></VTextarea>
                                    </VField>
                                </td>
                                <td>
                                    <VField>
                                        <VTextarea rows="2" v-model="item.TAInstruksi"></VTextarea>
                                    </VField>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <hr style="border-top: 1px dashed lightgray;background-color:white" class="mt-0 mb-1">

                <div class="column is-12">
                    <table class="table">
                        <tr>
                            <th style="text-align: center;">Pengkaji</th>
                            <th style="text-align: center;">Nama</th>
                            <th style="text-align: center;">Tanda Tangan</th>
                        </tr>
                        <tr>
                            <td style="text-align: center;vertical-align:middle">Dokter Sp. GK</td>
                            <td style="text-align: center;">
                                <VControl class="prime-auto">
                                    <AutoComplete v-model="input.DDDokter" :suggestions="d_Dokter"
                                        @complete="fetchDokter($event)" :optionLabel="'label'" :dropdown="true"
                                        :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                                        :field="'label'" />
                                </VControl>
                            </td>
                            <td style="text-align: center;">
                                <TandaTangan :elemenID="'TTDDokter'" :width="'150'" :height="'150'" class="dek" />
                            </td>
                        </tr>
                        <tr>
                            <td style="text-align: center;vertical-align:middle">Dietisien</td>
                            <td style="text-align: center;">
                                <VControl>
                                    <VInput type="text" class="input" v-model="input.TBDietisien" />
                                </VControl>
                            </td>
                            <td style="text-align: center;">
                                <TandaTangan :elemenID="'TTDDietisien'" :width="'150'" :height="'150'" class="dek" />
                            </td>
                        </tr>
                    </table>
                    <span style="font-weight: bold;"><i>(*) Diisi oleh Dietisien</i></span>
                </div>
            </div>
        </div>
    </div>

    <VModal :open="showModalTemplate" title="Riwayat" :noclose="true" size="large" actions="right"
        @close="showModalTemplate = false">
        <template #content>
            <form class="modal-form">
                <div class="column is-12 pt-0 pb-0">
                    <span style="font-size:9pt;font-weight:bold">List Riwayat</span>
                    <div style="overflow-y:auto;" class="mt-1">
                        <table class="tg table-tg" v-if="listTemplate.length > 0">
                            <thead>
                                <tr>
                                    <td class="tg-0lax text-center" width="25%">Tanggal Input</td>
                                    <td class="tg-0lax text-center" width="25%">Tanggal Registrasi</td>
                                    <td class="tg-0lax text-center" width="25%">No Registrasi</td>
                                    <td class="tg-0lax text-center" width="20%">No EMR</td>
                                    <td class="tg-0lax text-center" width="20%">Dokter</td>
                                    <td class="tg-0lax text-center" width="20%">Section</td>
                                    <td class="tg-0lax text-center" width="5%">#</td>
                                </tr>
                            </thead>
                            <tbody v-for="resep in listTemplate">
                                <tr>
                                    <td style="width:25%;text-align:center">
                                        <span class="mb-2">{{ resep.created_at }}</span><br>
                                    </td>
                                    <td style="width:25%;text-align:center">
                                        <span class="mb-2">{{ resep.registrasi.tglregistrasi }}</span><br>
                                    </td>
                                    <td style="width:25%;text-align:center">
                                        <span class="mb-2">{{ resep.registrasi.noregistrasi }}</span><br>
                                    </td>
                                    <td style="width:20%;text-align:center">
                                        <span class="mb-2">{{ resep.pasien.nocm }}</span><br>
                                    </td>
                                    <td style="width:20%;text-align:center">
                                        <span class="mb-2">{{ resep.dpjpUtama }}</span><br>
                                    </td>
                                    <td style="width:25%;text-align:center">
                                        <span class="mb-2">{{ resep.registrasi.namaruangan }}</span><br>
                                    </td>
                                    <td style="width:5%;text-align:center">
                                        <VIconButton type="button" raised circle icon="fas fa-plus"
                                            @click="addTemplate(resep)" color="info" v-tooltip-prime.top="'Pilih'">
                                        </VIconButton>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </form>
        </template>
    </VModal>

    <VModal :open="showModalTemplateFix" title="Template" :noclose="true" size="large" actions="right"
        @close="showModalTemplateFix = false">
        <template #content>
            <form class="modal-form">
                <div class="column is-12 pt-0 pb-0">
                    <span style="font-size:9pt;font-weight:bold">List Template</span>
                    <div style="overflow-y:auto;" class="mt-1">
                        <table class="tg table-tg" v-if="listTemplateFix.length > 0">
                            <thead>
                                <tr>
                                    <td class="tg-0lax text-center" width="15%">No</td>
                                    <td class="tg-0lax text-center" width="20%">Tanggal Dibuat</td>
                                    <td class="tg-0lax text-center" width="20%">Nama Ruangan</td>
                                    <td class="tg-0lax text-center" width="50%">Nama Template</td>
                                    <td class="tg-0lax text-center" width="15%">#</td>
                                </tr>
                            </thead>
                            <tbody v-for="resep in listTemplateFix">
                                <tr>
                                    <td style="width:15%;text-align:center">
                                        <span class="mb-2">{{ resep.no }}</span><br>
                                    </td>
                                    <td style="width:20%;text-align:center">
                                        <span class="mb-2">{{ resep.created_at }}</span><br>
                                    </td>
                                    <td style="width:20%;text-align:center">
                                        <span class="mb-2">{{ resep.registrasi.namaruangan }}</span><br>
                                    </td>
                                    <td style="width:50%;text-align:center">
                                        <span class="mb-2">{{ resep.namatemplate }}</span><br>
                                    </td>
                                    <td style="width:15%;text-align:center">
                                        <VIconButton type="button" raised circle icon="fas fa-plus"
                                            @click="addTemplate(resep)" color="info" v-tooltip-prime.top="'Pilih'">
                                        </VIconButton>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </form>
        </template>
    </VModal>
</template>
<script setup lang="ts">
import { useWindowScroll } from '@vueuse/core'
import { useApi } from '/@src/composable/useApi'
import { h, reactive, ref, computed, watch, onBeforeMount } from 'vue'
import TandaTangan from '../page-emr-plugins/tanda-tangan.vue'
import { useRoute, useRouter, onBeforeRouteLeave } from 'vue-router'
import { useHead } from '@vueuse/head'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useUserSession } from '/@src/stores/userSession'
import ButtonEmr from '../page-emr-plugins/button-emr.vue'
import * as H from '/@src/utils/appHelper'
import AutoComplete from 'primevue/autocomplete';
import Fieldset from 'primevue/fieldset';

useHead({ title: 'Asesmen Awal Dan Rencana Perawatan Gizi Klinis Rawat Inap Dan Intensif - ' + import.meta.env.VITE_PROJECT })
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)
let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pd as string
let norec_emr = useRoute().query.norec_emr as string
const props = withDefaults(
    defineProps<{
        pasien?: any
        registrasi?: any
        FORM_NAME?: string
        FORM_URL?: string
        COLLECTION?: string
    }>(),
    {
        pasien: {},
        registrasi: {},
        FORM_NAME: '',
        FORM_URL: '',
        COLLECTION: '',
    }
)

const route = useRoute()
const pasien: any = ref({})
const d_Dokter: any = ref([])
const dataTTD: any = ref([])
const COLLECTION: any = ref('AsesmenAwalRencanaPerawatanKlinisGizi') //table mongodb
const NOREC_EMRPASIEN: any = ref('')
const input: any = ref({
    DTanggalMasuk: new Date(),
    details: [{
        no: 1,
    }]
})
const { y } = useWindowScroll()
const isStuck = computed(() => {
    return y.value > 30
})
const isLoading = ref(false)
const isAktive = ref()
const listTemplate: any = ref([])
const showModalTemplate: any = ref(false)
const listTemplateFix: any = ref([])
const showModalTemplateFix: any = ref(false)

const loadRiwayat = async () => {
    let response = await useApi().get(`/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`)
    if (response.length) {
        input.value = response[0] //set ke inputan
        if (NOREC_EMRPASIEN.value == '') {
            NOREC_EMRPASIEN.value = response[0].emrpasienfk
        }
        dataTTD.value = response[0]
        H.tandaTangan().set("TTDDokter", dataTTD.value.TTDDokter)
        H.tandaTangan().set("TTDDietisien", dataTTD.value.TTDDietisien)
    }
}
const filterMenu: any = ref('')


const simpan = () => {
    let ID = input.value.id ? input.value.id : ''
    let object: any = {}

    object = input.value
    object.nocm = pasien.value.nocm

    object['TTDDokter'] = H.tandaTangan().get("TTDDokter");
    object['TTDDietisien'] = H.tandaTangan().get("TTDDietisien");
    object.pasien = H.setObjectPasien(pasien.value)
    object.registrasi = H.setObjectRegistrasi(pasien.value.registrasi)
    let json = {
        'id': ID,
        'norec_emr': NOREC_EMRPASIEN.value,
        'collection': COLLECTION.value,
        'url_form': props.FORM_URL,
        'name_form': props.FORM_NAME,
        'jenis_emr': 'asesmen_medis',
        'data': object
    }

    isLoading.value = true
    useApi().post(`/emr/simpan-emr`, json).then((response: any) => {
        isLoading.value = false
        loadRiwayat()
    }).catch((e: any) => {
        isLoading.value = false
    })
}

const simpanTemplate = () => {
    if(!input.value.namatemplate) {
        H.alert('warning', "Nama Template wajib diisi")
        return;
    }
    let ID = input.id ? input.id : ''
    let object: any = {}

    object = input.value
    object.nocm = pasien.value.nocm

    object.pasien = H.setObjectPasien(pasien.value)
    object.registrasi = H.setObjectRegistrasi(pasien.value.registrasi)
    let json = {
        'id': ID,
        'norec_emr': NOREC_EMRPASIEN.value,
        'collection': COLLECTION.value,
        'url_form': props.FORM_URL,
        'name_form': props.FORM_NAME,
        'jenis_emr': 'asesmen_medis',
        'data': object
    }
    isLoading.value = true

    useApi().post(
        `/emr/simpan-emr-template`, json).then((response: any) => {
            isLoading.value = false
            input.value.namatemplate = null
        }).catch((e: any) => {
            isLoading.value = false
        })
}

const pilihTemplate = async (index: any) => {
    isLoading.value = true
    useApi().get(
        `/emr/get-emr-history-terakhir?nocmfk=${ID_PASIEN}&collection=${COLLECTION.value}`).then((responselast: any) => {
            isLoading.value = false
            if (responselast.length) {
                listTemplate.value = responselast //set ke inputan
                showModalTemplate.value = true
            } else {
                H.alert('warning', 'Data tidak ada')
            }
        })
}

const addTemplate = (response: any) => {
    let lastTTV = {
      td: input.value.tekananDarah ?? '',
      nadi: input.value.nadi ?? '',
      nafas: input.value.nafas ?? '',
      celcius: input.value.celcius ?? '',
      sao2: input.value.sao2 ?? '',
      bb: input.value.beratBadan ?? '',
      tb: input.value.tinggiBadan ?? '',
      e: input.value.gcse ?? '',
      v: input.value.gcsv ?? '',
      m: input.value.gcsm ?? ''
    }
    input.value = response //set ke inputan
    input.value.namatemplate = null
    input.value.tekananDarah =  lastTTV.td;
    input.value.nadi =  lastTTV.nadi;
    input.value.nafas =  lastTTV.nafas;
    input.value.celcius =  lastTTV.celcius;
    input.value.sao2 =  lastTTV.sao2;
    input.value.beratBadan = lastTTV.bb;
    input.value.tinggiBadan = lastTTV.tb;
    input.value.gcse = lastTTV.e;
    input.value.gcsv = lastTTV.v;
    input.value.gcsm = lastTTV.m;
}

const pilihTemplateFix = async (index: any) => {
    isLoading.value = true
    useApi().get(
        `/emr/get-emr-template?collection=${COLLECTION.value}`).then((responselast: any) => {
            isLoading.value = false
            console.log(responselast)
            if (responselast.length) {
                for (var x = 0; x < responselast.length; x++) {
                    responselast[x].no = x + 1
                    responselast[x].id = ''
                }
                listTemplateFix.value = responselast //set ke inputan
                showModalTemplateFix.value = true
            } else {
                H.alert('warning', 'Data tidak ada')
            }
        })
}
const fetchPasien = () => {
    pasien.value = props.pasien
    pasien.value.registrasi = props.registrasi
    NOREC_EMRPASIEN.value = norec_emr ? norec_emr : ''
}

const fetchDokter = async (filter: any) => {
    await useApi().get(`emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&settingdatafix=objectjenispegawaifk,idJenisPegawaiDokter&limit=10&query=${filter.query}`).then((response) => {
        d_Dokter.value = response
    })
}

const removeItem = (index: any) => {
    input.value.details.splice(index, 1)
}
const addNewItem = () => {
    let newItem: any = {}
    newItem = { no: input.value.details[input.value.details.length - 1].no + 1 }
    input.value.details.push(newItem);
}

onBeforeMount(async () => {
    try {
        await loadRiwayat()
        await fetchPasien()
        let cache = H.cacheEMR().get(`TAB~${props.registrasi.noregistrasi}~${route.name}`)
        if (cache) input.value = cache
    } catch (error) {
        console.error('Error mount cache TAB EMR:', error);
    }
});

onBeforeRouteLeave((to, from, next) => {
    try {
        let rouutename = from?.name
        H.cacheEMR().set(`TAB~${props.registrasi.noregistrasi}~${rouutename}`, input.value)
    } catch (error) {
        console.error('Error leave cache TAB EMR:', error);
    }
    next();
});
</script>


<style lang="scss">
h1 {
    font-weight: bold !important;
}

.table {
    border-collapse: collapse !important;
    width: 100% !important;
}

.table td {
    border: 1px solid black !important;
    vertical-align: top !important;
}

.table th {
    // text-align: center !important;
    border: 1px solid black !important;
}

.tg {
    border-collapse: collapse;
    border-spacing: 0;
    width: 100% !important;
}

.tg td {
    border-style: solid;
    border-width: 1px;
    font-family: Arial, sans-serif;
    font-size: 14px;
    overflow: hidden;
    padding: 10px 5px;
    word-break: normal;
}

.tg th {
    text-align: center !important;
    border-style: solid;
    border-width: 1px;
    font-family: Arial, sans-serif;
    font-size: 14px;
    font-weight: bold;
    overflow: hidden;
    background-color: aquamarine;
    vertical-align: middle;
    padding: 10px 5px;
    word-break: normal;
}
</style>