<template>
    <div>
        <div class="form-layout is-stacked-2">
            <div class="form-outer" style="margin-top:15px">
                <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header" style="margin-bottom: 10px" v-if="!hideButtons">
                    <div class="form-header-inner">
                        <div class="left">
                            <h3>Asesmen Awal Keperawatan Gawat Darurat</h3>
                        </div>
                        <div class="right">
                            <ButtonEmr :NOREC_EMRPASIEN="NOREC_EMRPASIEN" :COLLECTION="COLLECTION"
                                :isLoading="isLoading" @simpan="simpan" @simpanTemplate="simpanTemplate"
                                @kembaliKeun="kembaliKeun" :isHideCetak="true">
                            </ButtonEmr>
                        </div>
                    </div>
                </div>

                <!-- form baru -->

                <div class="column is-12 buttons mb-0 mt-0" style="margin:10px;vertical-align:middle" v-if="!hideButtons">
                    <VButton type="button" rounded outlined color="primary" raised icon="feather:folder"
                        isLoading="false" @click="pilihTemplateFix(index)"> Pilih Template
                    </VButton>
                    <VButton type="button" rounded outlined color="info" raised icon="feather:file-text"
                        isLoading="false" @click="pilihTemplate(index)"> Pilih Riwayat
                    </VButton>
                </div>

                <hr style="border-top: 1px dashed lightgray;background-color:white" class="mt-0 mb-1" v-if="!hideButtons">

                <div class="column is-12" v-if="!hideButtons">
                    <h1><b>Nama Template</b>&emsp;&emsp;<span style="color:red">**Hanya diisi jika ingin membuat
                            template</span></h1>
                    <VField>
                        <VControl>
                            <VTextarea v-model="input.namatemplate" rows="1">
                            </VTextarea>
                        </VControl>
                    </VField>
                </div>

                <hr style="border-top: 1px dashed lightgray;background-color:white" class="mt-0 mb-1">

                <div class="column is-12 p-0">
                    <div class="column columns pb-0">
                        <div class="column is-4">
                            <h1>Tanggal</h1>
                            <VDatePicker v-model="input.DTanggalForm" mode="date" trim-weeks :max-date="new Date()">
                                <template #default="{ inputValue, inputEvents }">
                                    <VControl icon="feather:calendar" fullwidth>
                                        <VInput :value="inputValue" placeholder="Tanggal..." v-on="inputEvents" />
                                    </VControl>
                                </template>
                            </VDatePicker>
                        </div>
                        <div class="column is-4">
                            <h1>Jam Masuk</h1>
                            <VDatePicker v-model="input.TJamMasuk" mode="time" :is24hr="true" trim-weeks
                                :max-date="new Date()">
                                <template #default="{ inputValue, inputEvents }">
                                    <VControl icon="feather:clock" fullwidth>
                                        <VInput :value="inputValue" placeholder="Jam masuk..." v-on="inputEvents" />
                                    </VControl>
                                </template>
                            </VDatePicker>
                        </div>
                        <div class="column is-4">
                            <h1>Jam Asesmen Awal</h1>
                            <VDatePicker v-model="input.TJamAsesmenAwal" mode="time" :is24hr="true" trim-weeks
                                :max-date="new Date()">
                                <template #default="{ inputValue, inputEvents }">
                                    <VControl icon="feather:clock" fullwidth>
                                        <VInput :value="inputValue" placeholder="Jam asesmen awal..."
                                            v-on="inputEvents" />
                                    </VControl>
                                </template>
                            </VDatePicker>
                        </div>
                    </div>
                    <div class="columns is-multiline column pt-0">
                        <div class="column is-4 pt-0">
                            <h1>Rujukan</h1>
                            <Multiselect v-model="input.SRujukan" :attrs="{ value }" placeholder="--Pilih--"
                                label="label" :options="d_rujukan" :searchable="true" track-by="label" mode="single"
                                autocomplete="off">
                            </Multiselect>
                        </div>
                        <div class="column is-4 pt-0" v-if="input.SRujukan == 1">
                            <h1>Dari :</h1>
                            <Multiselect v-model="input.STempatRujukan" :attrs="{ value }" placeholder="--Pilih--"
                                label="label" :options="d_tempatRujukan" :searchable="true" track-by="label"
                                mode="single" autocomplete="off">
                            </Multiselect>
                        </div>
                        <div class="column is-4 pt-0" v-if="input.SRujukan == 4">
                            <h1>&nbsp;</h1>
                            <VControl>
                                <VInput type="text" class="input" v-model="input.TBDiantar"
                                    placeholder="Diantar oleh..." />
                            </VControl>
                        </div>
                        <div class="column is-4 pt-0" v-if="input.STempatRujukan == 1 && input.SRujukan == 1">
                            <h1>Rumah Sakit</h1>
                            <VControl>
                                <VInput type="text" class="input" placeholder="Rumah sakit..."
                                    v-model="input.TBRujuk_RS" />
                            </VControl>
                        </div>
                        <div class="column is-4 pt-0" v-if="input.STempatRujukan == 2 && input.SRujukan == 1">
                            <h1>Puskesmas</h1>
                            <VControl>
                                <VInput type="text" class="input" placeholder="Puskesmas..."
                                    v-model="input.TBRujuk_Puskesmas" />
                            </VControl>
                        </div>
                        <div class="column is-4 pt-0" v-if="input.STempatRujukan == 3 && input.SRujukan == 1">
                            <h1>dr.</h1>
                            <VControl>
                                <VInput type="text" class="input" placeholder="dr..." v-model="input.TBRujuk_dr" />
                            </VControl>
                        </div>
                        <div class="column is-4 pt-0" v-if="input.STempatRujukan == 4 && input.SRujukan == 1">
                            <h1>Lainnya</h1>
                            <VControl>
                                <VInput type="text" class="input" placeholder="Lainnya..."
                                    v-model="input.TBRujuk_Lainnya" />
                            </VControl>
                        </div>
                        <div class="column is-4 pt-0">
                            <h1>Dx.rujukan</h1>
                            <VControl>
                                <VInput type="text" class="input" v-model="input.TBDx_rujukan" />
                            </VControl>
                        </div>
                        <div class="column is-4 pt-0">
                            <h1>Alloanamnesis</h1>
                            <VField class="is-autocomplete-select" v-slot="{ id }">
                                <VControl icon="feather:search">
                                    <Multiselect v-model="input.kebpilihanallo" :attrs="{ value }"
                                        placeholder="--Pilih--" label="label" :options="d_allo" :searchable="true"
                                        track-by="label" mode="single" autocomplete="off">
                                    </Multiselect>
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-4 pt-0" v-if="input.kebpilihanallo == 4">
                            <h1>Lainnya</h1>
                            <VControl>
                                <VInput type="text" class="input" v-model="input.TBLainnya_Allo"
                                    placeholder="Lainnya..." />
                            </VControl>
                        </div>
                    </div>
                </div>

                <hr style="border-top: 1px dashed lightgray;background-color:white" class="mt-0 mb-1">

                <div class="column is-12">
                    <div class="column is-12 pt-0 pl-0" style="font-size:large">
                        <h1>Anamnesis</h1>
                    </div>
                    <div class="columns is-multiline">
                        <div class="column is-6">
                            <h1>Keluhan Utama</h1>
                            <VField>
                                <VControl>
                                    <VTextarea v-model="input.keluhanutama" rows="3">
                                    </VTextarea>
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-6">
                            <h1>Riwayat penyakit sekarang</h1>
                            <VField>
                                <VControl>
                                    <VTextarea v-model="input.riwayatpenyakit" rows="3">
                                    </VTextarea>
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-6 pt-0">
                            <h1>Riwayat penyakit terdahulu</h1>
                            <VField>
                                <VControl>
                                    <VTextarea v-model="input.riwayatpenyakitdahulu" rows="3">
                                    </VTextarea>
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-6 pt-0">
                            <h1>Riwayat pengobatan</h1>
                            <VField>
                                <VControl>
                                    <VTextarea v-model="input.riwayatpengobatan" rows="3">
                                    </VTextarea>
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-12 pt-0">
                            <h1>Riwayat penyakit keluarga</h1>
                            <VField>
                                <VControl>
                                    <VTextarea v-model="input.riwayatpenyakitkeluarga" rows="3">
                                    </VTextarea>
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-12 pt-0">
                            <h1>Riwayat alergi</h1>
                            <div class="columns column is-4 is-multiline pb-0 pt-1 pl-0">
                                <div class="column is-6">
                                    <VField vertical>
                                        <VControl>
                                            <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.isalergi"
                                                true-value="YA" label="Ya" color="primary" circle />
                                        </VControl>
                                    </VField>
                                </div>
                                <div class="column is-6">
                                    <VField vertical>
                                        <VControl>
                                            <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.isalergi"
                                                true-value="TIDAK" label="Tidak" color="primary" circle />
                                        </VControl>
                                    </VField>
                                </div>
                            </div>
                        </div>
                        <div class="column is-12 pt-0" v-if="input.isalergi == 'YA'">
                            <h1>Jenis alergi</h1>
                            <div class="columns" style="margin-top:-1px">
                                <div class="column is-4">
                                    <VControl raw subcontrol>
                                        <VCheckbox class="p-0" color="primary" square true-value="Obat" label="Obat"
                                            v-model="input.CBAlergiObat" />
                                    </VControl>
                                    <VControl>
                                        <VInput type="text" class="input" v-model="input.TBAlergiObat"
                                            placeholder="Alergi obat..." />
                                    </VControl>
                                </div>
                                <div class="column is-4">
                                    <VControl raw subcontrol>
                                        <VCheckbox class="p-0" color="primary" square true-value="Makanan"
                                            label="Makanan" v-model="input.CBAlergiMakanan" />
                                    </VControl>
                                    <VControl>
                                        <VInput type="text" class="input" v-model="input.TBAlergiMakanan"
                                            placeholder="Alergi makanan..." />
                                    </VControl>
                                </div>
                                <div class="column is-4">
                                    <VControl raw subcontrol>
                                        <VCheckbox class="p-0" color="primary" square true-value="Lainnya"
                                            label="Lainnya" v-model="input.CBAlergiLainnya" />
                                    </VControl>
                                    <VControl>
                                        <VInput type="text" class="input" v-model="input.TBAlergiLainnya"
                                            placeholder="Alergi..." />
                                    </VControl>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <hr style="border-top: 1px dashed lightgray;background-color:white" class="mt-0 mb-1">

                <div class="column is-12">
                    <div class="column is-12 pt-0 pl-0" style="font-size:large">
                        <h1>Status Fisik</h1>
                    </div>
                    <div class="column columns is-multiline pb-0 pt-0 mb-0">
                        <div class="column is-3">
                            <h1>Keadaan Umum</h1>
                            <Multiselect v-model="input.SKeadaanUmum" :attrs="{ value }" placeholder="--Pilih--"
                                label="label" :options="d_keadaanumum" :searchable="true" track-by="label" mode="single"
                                autocomplete="off">
                            </Multiselect>
                        </div>
                        <div class="column is-5">
                            <h1>GCS</h1>
                            <div class="columns">
                                <div class="column is-4">
                                    <VField addons>
                                        <VControl class="field-addon-body">
                                            <VButton static>E</VButton>
                                        </VControl>
                                        <VControl expanded>
                                            <VControl>
                                                <VInput type="text" class="input" v-model="input.GCSe" maxLength="1" />
                                            </VControl>
                                        </VControl>
                                    </VField>
                                </div>
                                <div class="column is-4">
                                    <VField addons>
                                        <VControl class="field-addon-body">
                                            <VButton static>V</VButton>
                                        </VControl>
                                        <VControl expanded>
                                            <VControl>
                                                <VInput type="text" class="input" v-model="input.GCSv" maxLength="1" />
                                            </VControl>
                                        </VControl>
                                    </VField>
                                </div>
                                <div class="column is-4">
                                    <VField addons>
                                        <VControl class="field-addon-body">
                                            <VButton static>M</VButton>
                                        </VControl>
                                        <VControl expanded>
                                            <VControl>
                                                <VInput type="text" class="input" v-model="input.GCSm" maxLength="1" />
                                            </VControl>
                                        </VControl>
                                    </VField>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="column pt-0 pb-0">
                        <h1>Tanda-tanda vital</h1>
                    </div>
                    <div class="column">
                        <div class="columns is-multiline">
                            <div class="column is-3">
                                <h1>Suhu</h1>
                                <VField addons>
                                    <VControl>
                                        <VInput type="text" class="input" v-model="input.TBcelciusTTV" />
                                    </VControl>
                                    <VControl class="field-addon-body">
                                        <VButton static>°C</VButton>
                                    </VControl>
                                </VField>
                            </div>
                            <div class="column is-3">
                                <h1>Respirasi</h1>
                                <VField addons>
                                    <VControl>
                                        <VInput type="text" class="input" v-model="input.TBPernafasanTTV" />
                                    </VControl>
                                    <VControl class="field-addon-body">
                                        <VButton static>x/mnt</VButton>
                                    </VControl>
                                </VField>
                            </div>
                            <div class="column is-3">
                                <h1>Nadi</h1>
                                <VField addons>
                                    <VControl>
                                        <VInput type="text" class="input" v-model="input.TBnadiTTV" />
                                    </VControl>
                                    <VControl class="field-addon-body">
                                        <VButton static>x/mnt</VButton>
                                    </VControl>
                                </VField>
                            </div>
                            <div class="column is-3">
                                <h1>Tekanan Darah</h1>
                                <VField addons>
                                    <VControl>
                                        <VInput type="text" class="input" v-model="input.TBtekananDarahTTV" />
                                    </VControl>
                                    <VControl class="field-addon-body">
                                        <VButton static>mmHg</VButton>
                                    </VControl>
                                </VField>
                            </div>
                            <div class="column is-3 pt-0">
                                <h1>SaO2</h1>
                                <VField addons>
                                    <VControl>
                                        <VInput type="text" class="input" v-model="input.TBnspo2TTV" />
                                    </VControl>
                                    <VControl class="field-addon-body">
                                        <VButton static>%</VButton>
                                    </VControl>
                                </VField>
                            </div>
                            <div class="column is-3 pt-0">
                                <h1>Berat Badan</h1>
                                <VField addons>
                                    <VControl>
                                        <VInput type="text" class="input" v-model="input.TBberatBadanTTV" />
                                    </VControl>
                                    <VControl class="field-addon-body">
                                        <VButton static>Kg</VButton>
                                    </VControl>
                                </VField>
                            </div>
                            <div class="column is-3 pt-0">
                                <h1>Tinggi Badan</h1>
                                <VField addons>
                                    <VControl>
                                        <VInput type="text" class="input" v-model="input.TBtinggiBadanTTV" />
                                    </VControl>
                                    <VControl class="field-addon-body">
                                        <VButton static>Cm</VButton>
                                    </VControl>
                                </VField>
                            </div>
                        </div>
                    </div>
                    <div class="columns is-multiline is-12 pb-4 mt-2">
                        <VControl>
                            <VCheckbox class="m-0" v-model="input.device" true-value="Device" label="Device" color="primary" circle />
                        </VControl>
                        <template v-if="input.device == 'Device'">
                            <div class="column is-2 pt-0 pb-0">
                              <span>NC</span>
                              <VField addons>
                                <VControl>
                                  <VInput type="text" class="input" v-model="input.NC" />
                                </VControl>
                                <VControl class="field-addon-body">
                                  <VButton static>I/m</VButton>
                                </VControl>
                              </VField>
                            </div>
                          </template>

                          <template v-if="input.device == 'Device'">
                            <div class="column is-2 pt-0 pb-0">
                              <span>SM</span>
                              <VField addons>
                                <VControl>
                                  <VInput type="text" class="input" v-model="input.SM" />
                                </VControl>
                                <VControl class="field-addon-body">
                                  <VButton static>I/m</VButton>
                                </VControl>
                              </VField>
                            </div>
                          </template>

                          <template v-if="input.device == 'Device'">
                            <div class="column is-2 pt-0 pb-0">
                              <span>NRM</span>
                              <VField addons>
                                <VControl>
                                  <VInput type="text" class="input" v-model="input.NRM" />
                                </VControl>
                                <VControl class="field-addon-body">
                                  <VButton static>I/m</VButton>
                                </VControl>
                              </VField>
                            </div>
                          </template>

                          <template v-if="input.device == 'Device'">
                            <div class="column is-2 pt-0 pb-0">
                              <span>CPAP</span>
                              <VField>
                                <VControl>
                                  <VInput type="text" class="input" v-model="input.cpap" />
                                </VControl>
                              </VField>
                            </div>
                          </template>

                          <template v-if="input.device == 'Device'">
                            <div class="column is-2 pt-0 pb-0">
                              <span>VENTI</span>
                              <VField>
                                <VControl>
                                  <VInput type="text" class="input" v-model="input.venti" />
                                </VControl>
                              </VField>
                            </div>
                          </template>
                    </div>
                </div>

                <hr style="border-top: 1px dashed lightgray;background-color:white" class="mt-0 mb-1">

                <div class="column is-12">
                    <div class="column is-12 pt-0 pl-0" style="font-size:large">
                        <h1>Asesmen Nyeri</h1>
                    </div>
                    <div class="columns is-multiline">
                        <div class="column is-3 pb-0">
                            <h1>Skala Nyeri</h1>
                            <Multiselect v-model="input.SSkalaNyeriAN" :attrs="{ value }" placeholder="--Pilih--"
                                label="label" :options="d_skalanyeri" :searchable="true" track-by="label" mode="single"
                                autocomplete="off">
                            </Multiselect>
                        </div>
                        <div class="column is-3 pb-0">
                            <h1>Lokasi Nyeri</h1>
                            <VControl>
                                <VInput type="text" class="input" v-model="input.TBLokasiNyeriAN" />
                            </VControl>
                        </div>
                        <div class="column is-3 pb-0">
                            <h1>Frekuensi Nyeri</h1>
                            <Multiselect v-model="input.SFrekuensiNyeriAN" :attrs="{ value }" placeholder="--Pilih--"
                                label="label" :options="d_frekuensinyeri" :searchable="true" track-by="label"
                                mode="single" autocomplete="off">
                            </Multiselect>
                        </div>
                        <div class="column is-3 pb-0">
                            <h1>Lama Nyeri</h1>
                            <VControl>
                                <VInput type="text" class="input" v-model="input.TBLamaNyeriAN" />
                            </VControl>
                        </div>
                        <div class="column is-3">
                            <h1>Kualitas Nyeri</h1>
                            <Multiselect v-model="input.SKualitasNyeriAN" :attrs="{ value }" placeholder="--Pilih--"
                                label="label" :options="d_kualitasnyeri" :searchable="true" track-by="label"
                                mode="single" autocomplete="off">
                            </Multiselect>
                        </div>
                        <div class="column is-3" v-if="input.SKualitasNyeriAN == 4">
                            <h1>Lainnya</h1>
                            <VControl>
                                <VInput type="text" class="input" v-model="input.TBLainnyaAN_KN" />
                            </VControl>
                        </div>
                        <div class="column is-3">
                            <h1>Faktor yang memperberat nyeri</h1>
                            <VControl>
                                <VInput type="text" class="input" v-model="input.TBFaktorMemperberatAN" />
                            </VControl>
                        </div>
                        <div class="column is-3">
                            <h1>Faktor yang meringankan nyeri</h1>
                            <VControl>
                                <VInput type="text" class="input" v-model="input.TBFaktorMeringankanAN" />
                            </VControl>
                        </div>
                    </div>
                </div>

                <hr style="border-top: 1px dashed lightgray;background-color:white" class="mt-0 mb-1">

                <div class="column is-12">
                    <div class="columns is-multiline">
                        <div class="column is-7">
                            <h1>Skala Nyeri</h1>
                            <div class="columns pt-4">
                                <div class="column" style="text-align: center"
                                    v-for="(image, i) in listImageNyeri.detail">
                                    <VAvatar size="medium" :picture="image.img" style="cursor: pointer !important"
                                        :class="isAktive == i ? 'active' : ''" @click="skor(image, i)" />
                                    <p>{{ image.descNilai }}</p>
                                    <p>{{ image.nama }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="column is-5">
                            <h1>Score</h1>
                            <div class="pt-2">
                                <VField v-for="skor in listSkoringNyeri.detail">
                                    <VControl raw subcontrol class="p-0">
                                        <VCheckbox class="pt-0 pb-1" v-model="input.skoringNyeri"
                                            :true-value="skor.descNilai" :label="skor.nama" color="primary" circle />
                                    </VControl>
                                </VField>
                            </div>
                        </div>
                    </div>
                </div>

                <hr style="border-top: 1px dashed lightgray;background-color:white" class="mt-0 mb-1">

                <div class="column is-12">
                    <div class="column is-12 pt-0 pl-0" style="font-size:large">
                        <h1>Kondisi Psikologi, Sosial, Ekonomi & Spiritual</h1>
                    </div>
                    <div class="column is-12">
                        <VControl>
                        <VCheckbox class="p-0 mb-3" color="primary" square true-value="Dalam Batas Normal"
                            label="Dalam Batas Normal" v-model="input.PsikologisNormal" @click="PsikologisNormal()" />
                        </VControl>
                    </div>
                    <div class="column columns is-multiline">
                        <div class="column is-4 pt-1 pb-1">
                            <h1>Gangguan Psikologis</h1>
                            <Multiselect v-model="input.SKondisiPsikologis" :attrs="{ value }" placeholder="--Pilih--"
                                label="label" :options="d_kondisiPsikologis" :searchable="true" track-by="label"
                                mode="single" autocomplete="off">
                            </Multiselect>
                        </div>
                        <div class="column is-4 pt-1 pb-1">
                            <h1>Masalah Perkawinan</h1>
                            <Multiselect v-model="input.SMasalahPernikahan" :attrs="{ value }" placeholder="--Pilih--"
                                label="label" :options="d_masalahPernikahan" :searchable="true" track-by="label"
                                mode="single" autocomplete="off">
                            </Multiselect>
                        </div>
                        <div class="column is-4 pt-1 pb-1" v-if="input.SMasalahPernikahan == 2">
                            <h1>Jelaskan</h1>
                            <VControl>
                                <VInput type="text" class="input" v-model="input.TBMasalahPernikahan"
                                    placeholder="Masalah perkawinan..." />
                            </VControl>
                        </div>
                        <div class="column is-4 pt-1 pb-1">
                            <h1>Mengalami kekerasan fisik</h1>
                            <Multiselect v-model="input.SMengalamiKekerasanFisik" :attrs="{ value }"
                                placeholder="--Pilih--" label="label" :options="d_mengalamiKekerasanFisik"
                                :searchable="true" track-by="label" mode="single" autocomplete="off">
                            </Multiselect>
                        </div>
                        <div class="column is-4 pt-1 pb-1" v-if="input.SMengalamiKekerasanFisik == 2">
                            <h1>Jelaskan</h1>
                            <VControl>
                                <VInput type="text" class="input" v-model="input.TBMengalamiKekerasanFisik"
                                    placeholder="Mengalami kekerasan fisik..." />
                            </VControl>
                        </div>
                        <div class="column is-4 pt-1 pb-1">
                            <h1>Keyakinan dan nilai pribadi</h1>
                            <VControl>
                                <VInput type="text" class="input" v-model="input.TBKeyakinanDanNilaiPribadi" />
                            </VControl>
                        </div>
                        <div class="column is-4 pt-1 pb-1">
                            <h1>Pembiayaan Kesehatan</h1>
                            <Multiselect v-model="input.SPembiayaanKesehatan" :attrs="{ value }" placeholder="--Pilih--"
                                label="label" :options="d_pembiayaanKesehatan" :searchable="true" track-by="label"
                                mode="single" autocomplete="off">
                            </Multiselect>
                        </div>
                        <div class="column is-4 pt-1 pb-1" v-if="input.SPembiayaanKesehatan == 2">
                            <h1>Asuransi</h1>
                            <VControl>
                                <VInput type="text" class="input" v-model="input.TBAsuransiLainnya"
                                    placeholder="Asuransi..." />
                            </VControl>
                        </div>
                        <div class="column is-4 pt-1 pb-1">
                            <h1>Kebiasaan adat istiadat yang mempengaruhi kesehatan</h1>
                            <VControl>
                                <VInput type="text" class="input" v-model="input.TBKebiasaanAdatIstiadat" />
                            </VControl>
                        </div>
                        <div class="column is-4 pt-1 pb-1">
                            <h1>Perlu rohaniawan</h1>
                            <Multiselect v-model="input.SPerluRohaniawan" :attrs="{ value }" placeholder="--Pilih--"
                                label="label" :options="d_yaTidak" :searchable="true" track-by="label" mode="single"
                                autocomplete="off">
                            </Multiselect>
                        </div>
                    </div>
                </div>

                <hr style="border-top: 1px dashed lightgray;background-color:white" class="mt-0 mb-1">

                <div class="column is-12">
                    <div class="column is-12 pt-0 pl-0 pb-0" style="font-size:large">
                        <h1>Asesmen Kebutuhan Informasi & Edukasi</h1>
                    </div>
                    <div class="column">
                        Lihat pada form kebutuhan informasi dan edukasi
                    </div>
                </div>

                <hr style="border-top: 1px dashed lightgray;background-color:white" class="mt-0 mb-1">

                <div class="column is-12">
                    <div class="column is-12 pt-0 pl-0 pb-0" style="font-size:large">
                        <h1>Skrinning Nutrisi</h1>
                    </div>
                    <div class="column">
                        <div class="columns is-multiline">
                            <div class="column is-4">
                                <h1>Penurunan BB 6 bulan terakhir?</h1>
                                <VField class="is-autocomplete-select">
                                    <VControl>
                                        <Multiselect v-model="input.penurunanbb" :attrs="{ value }"
                                            placeholder="--Pilih--" label="label" :options="d_penurunanbb"
                                            :searchable="true" track-by="label" mode="single" autocomplete="off">
                                        </Multiselect>
                                    </VControl>
                                </VField>
                            </div>
                            <div class="column is-4">
                                <h1>Ya, bila ya berapa penurunan berat badan</h1>
                                <VField class="is-autocomplete-select">
                                    <VControl>
                                        <Multiselect v-model="input.penurunanbbYa" :attrs="{ value }"
                                            placeholder="--Pilih--" label="label" :options="d_penurunanbbYa"
                                            :searchable="true" track-by="label" mode="single" autocomplete="off">
                                        </Multiselect>
                                    </VControl>
                                </VField>
                            </div>
                            <div class="column is-4">
                                <h1>Terjadi penurunan nafsu makan?</h1>
                                <VField class="is-autocomplete-select">
                                    <VControl>
                                        <Multiselect v-model="input.penurunannafsu" :attrs="{ value }"
                                            placeholder="--Pilih--" label="label" :options="d_penurunannafsu"
                                            :searchable="true" track-by="label" mode="single" autocomplete="off">
                                        </Multiselect>
                                    </VControl>
                                </VField>
                            </div>
                            <div class="column is-8"></div>
                            <div class="column is-4 pt-0">
                                <h1>Nilai</h1>
                                <VField addons>
                                    <VControl expanded>
                                        <VInput type="text" class="heightinput input" placeholder=""
                                            v-model="input.nilaiSkrining" disabled />
                                    </VControl>
                                </VField>
                            </div>
                            <div class="column is-4 pt-0 pb-0">
                                <div class="column is-12 pt-0 pb-0">
                                    <h1>Pasien dengan diagnosa khusus?</h1>
                                </div>
                                <div class="column is-12 columns">
                                    <div class="column is-6 pt-0">
                                        <VField>
                                            <VControl>
                                                <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.diagnosakhusus"
                                                    true-value="YA" label="Ya" color="primary" circle />
                                            </VControl>
                                        </VField>
                                    </div>
                                    <div class="column is-6 pt-0">
                                        <VField>
                                            <VControl>
                                                <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.diagnosakhusus"
                                                    true-value="TIDAK" label="Tidak" color="primary" circle />
                                            </VControl>
                                        </VField>
                                    </div>
                                </div>
                            </div>
                            <div class="column is-8 pt-0">
                                <div class="column is-12 pt-0 pb-0">
                                    <h1>Nilai</h1>
                                </div>
                                <div class="column is-12 columns is-multiline">
                                    <div class="column is-4 pt-0 pb-0 pr-0">
                                        <VField>
                                            <VControl>
                                                <VCheckbox class="fontcheckbox" v-model="input.nilai"
                                                    true-value="RISIKO RENDAH (MST 0-1)" label="Risiko rendah (MST 0-1)"
                                                    color="primary" circle disabled />
                                            </VControl>
                                        </VField>
                                    </div>
                                    <div class="column is-4 pt-0 pb-0 pr-0">
                                        <VField>
                                            <VControl>
                                                <VCheckbox class="fontcheckbox" v-model="input.nilai"
                                                    true-value="RISIKO SEDANG (MST 2-3)" label="Risiko sedang (MST 2-3)"
                                                    color="primary" circle disabled />
                                            </VControl>
                                        </VField>
                                    </div>
                                    <div class="column is-4 pt-0 pb-0 pr-0">
                                        <VField>
                                            <VControl>
                                                <VCheckbox class="fontcheckbox" v-model="input.nilai"
                                                    true-value="RISIKO TINGGI (MST 4-5)" label="Risiko tinggi (MST 4-5)"
                                                    color="primary" circle disabled />
                                            </VControl>
                                        </VField>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <hr style="border-top: 1px dashed lightgray;background-color:white" class="mt-0 mb-1">

                <div class="column is-12">
                    <div class="column is-12 pt-0 pl-0 pb-0" style="font-size:large">
                        <h1>Status Fungsional</h1>
                    </div>
                    <div class="column">
                        <div class="columns is-multiline">
                            <div class="column is-3">
                                <h1>Mengontrol BAB</h1>
                                <VField class="is-autocomplete-select">
                                    <VControl>
                                        <Multiselect v-model="input.mengontrolbab" :attrs="{ value }"
                                            placeholder="--Pilih--" label="label" :options="d_mengontrolbab"
                                            :searchable="true" track-by="label" mode="single" autocomplete="off">
                                        </Multiselect>
                                    </VControl>
                                </VField>
                            </div>
                            <div class="column is-3">
                                <h1>Mengontrol BAK</h1>
                                <VField class="is-autocomplete-select">
                                    <VControl>
                                        <Multiselect v-model="input.mengontrolbak" :attrs="{ value }"
                                            placeholder="--Pilih--" label="label" :options="d_mengontrolbak"
                                            :searchable="true" track-by="label" mode="single" autocomplete="off">
                                        </Multiselect>
                                    </VControl>
                                </VField>
                            </div>
                            <div class="column is-3">
                                <h1>Membersihkan diri</h1>
                                <VField class="is-autocomplete-select">
                                    <VControl>
                                        <Multiselect v-model="input.bersihdiri" :attrs="{ value }"
                                            placeholder="--Pilih--" label="label" :options="d_bersihdiri"
                                            :searchable="true" track-by="label" mode="single" autocomplete="off">
                                        </Multiselect>
                                    </VControl>
                                </VField>
                            </div>
                            <div class="column is-3">
                                <h1>Penggunaan toilet</h1>
                                <VField class="is-autocomplete-select">
                                    <VControl>
                                        <Multiselect v-model="input.toilet" :attrs="{ value }" placeholder="--Pilih--"
                                            label="label" :options="d_toilet" :searchable="true" track-by="label"
                                            mode="single" autocomplete="off">
                                        </Multiselect>
                                    </VControl>
                                </VField>
                            </div>
                            <div class="column is-3 pt-0">
                                <h1>Makan</h1>
                                <VField class="is-autocomplete-select">
                                    <VControl>
                                        <Multiselect v-model="input.makan" :attrs="{ value }" placeholder="--Pilih--"
                                            label="label" :options="d_makan" :searchable="true" track-by="label"
                                            mode="single" autocomplete="off">
                                        </Multiselect>
                                    </VControl>
                                </VField>
                            </div>
                            <div class="column is-3 pt-0">
                                <h1>Berpindah dari tempat tidur</h1>
                                <VField class="is-autocomplete-select">
                                    <VControl>
                                        <Multiselect v-model="input.berpindahtt" :attrs="{ value }"
                                            placeholder="--Pilih--" label="label" :options="d_berpindahtt"
                                            :searchable="true" track-by="label" mode="single" autocomplete="off">
                                        </Multiselect>
                                    </VControl>
                                </VField>
                            </div>
                            <div class="column is-3 pt-0">
                                <h1>Mobilisai / Berjalan</h1>
                                <VField class="is-autocomplete-select">
                                    <VControl>
                                        <Multiselect v-model="input.mobilisasi" :attrs="{ value }"
                                            placeholder="--Pilih--" label="label" :options="d_mobilisasi"
                                            :searchable="true" track-by="label" mode="single" autocomplete="off">
                                        </Multiselect>
                                    </VControl>
                                </VField>
                            </div>
                            <div class="column is-3 pt-0">
                                <h1>Berpakaian</h1>
                                <VField class="is-autocomplete-select">
                                    <VControl>
                                        <Multiselect v-model="input.berpakaian" :attrs="{ value }"
                                            placeholder="--Pilih--" label="label" :options="d_berpakaian"
                                            :searchable="true" track-by="label" mode="single" autocomplete="off">
                                        </Multiselect>
                                    </VControl>
                                </VField>
                            </div>
                            <div class="column is-3 pt-0">
                                <h1>Naik turun tangga</h1>
                                <VField class="is-autocomplete-select">
                                    <VControl>
                                        <Multiselect v-model="input.tangga" :attrs="{ value }" placeholder="--Pilih--"
                                            label="label" :options="d_tangga" :searchable="true" track-by="label"
                                            mode="single" autocomplete="off">
                                        </Multiselect>
                                    </VControl>
                                </VField>
                            </div>
                            <div class="column is-3 pt-0">
                                <h1>Mandi</h1>
                                <VField class="is-autocomplete-select">
                                    <VControl>
                                        <Multiselect v-model="input.mandi" :attrs="{ value }" placeholder="--Pilih--"
                                            label="label" :options="d_mandi" :searchable="true" track-by="label"
                                            mode="single" autocomplete="off">
                                        </Multiselect>
                                    </VControl>
                                </VField>
                            </div>
                            <div class="column is-3 pt-0">
                                <h1>Nilai</h1>
                                <VField addons>
                                    <VControl expanded>
                                        <VInput type="text" class="heightinput input" placeholder=""
                                            v-model="input.nilaimandi" disabled />
                                    </VControl>
                                </VField>
                            </div>
                            <div class="columns is-multiline column is-12 pt-0">
                                <div class="column is-12 pb-0">
                                    <h1>Keterangan</h1>
                                </div>
                                <div class="column is-4">
                                    <VControl raw subcontrol>
                                        <VCheckbox class="p-0" color="primary" square
                                            true-value="Ketergantungan total (0-4)" label="Ketergantungan total (0-4)"
                                            v-model="input.CBStatusFungsional" disabled />
                                    </VControl>
                                </div>
                                <div class="column is-4">
                                    <VControl raw subcontrol>
                                        <VCheckbox class="p-0" color="primary" square
                                            true-value="Ketergantungan berat (5-8)" label="Ketergantungan berat (5-8)"
                                            v-model="input.CBStatusFungsional" disabled />
                                    </VControl>
                                </div>
                                <div class="column is-4">
                                    <VControl raw subcontrol>
                                        <VCheckbox class="p-0" color="primary" square
                                            true-value="Ketergantungan sedang (9-11)"
                                            label="Ketergantungan sedang (9-11)" v-model="input.CBStatusFungsional"
                                            disabled />
                                    </VControl>
                                </div>
                                <div class="column is-4">
                                    <VControl raw subcontrol>
                                        <VCheckbox class="p-0" color="primary" square
                                            true-value="Ketergantungan ringan(12-19)"
                                            label="Ketergantungan ringan(12-19)" v-model="input.CBStatusFungsional"
                                            disabled />
                                    </VControl>
                                </div>
                                <div class="column is-4">
                                    <VControl raw subcontrol>
                                        <VCheckbox class="p-0" color="primary" square true-value="Mandiri (20)"
                                            label="Mandiri (20)" v-model="input.CBStatusFungsional" disabled />
                                    </VControl>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <hr style="border-top: 1px dashed lightgray;background-color:white" class="mt-0 mb-1">

                <div class="column is-12">
                    <div class="column is-12 pt-0 pl-0 pb-0" style="font-size:large">
                        <h1>Asesmen Risiko Jatuh</h1>
                    </div>
                    <div class="column is-12">
                        <VControl>
                        <VCheckbox class="p-0 mb-3" color="primary" square true-value="Dalam Batas Normal"
                            label="Dalam Batas Normal" v-model="input.BatasNormalRisikoJatuh" @click="BatasNormalRisikoJatuh()" />
                        </VControl>
                    </div>
                    <div class="columns column is-multiline">
                        <div class="column is-6">
                            <h1>1. Perhatikan cara berjalan pasien saat akan duduk di kursi. Apakah pasien tampak
                                tidak seimbang
                                (sempoyongan/limbung)?</h1>
                            <Multiselect v-model="input.S1_ARJ" :attrs="{ value }" placeholder="--Pilih--" label="label"
                                :options="d_yaTidak" :searchable="true" track-by="label" mode="single"
                                autocomplete="off">
                            </Multiselect>
                        </div>
                        <div class="column is-6">
                            <h1>2. Apakah pasien memegang pinggiran kursi atau meja atau benda lain sebagai benda
                                lain
                                sebagai
                                penopang saat akan duduk?</h1>
                            <Multiselect v-model="input.S2_ARJ" :attrs="{ value }" placeholder="--Pilih--" label="label"
                                :options="d_yaTidak" :searchable="true" track-by="label" mode="single"
                                autocomplete="off">
                            </Multiselect>
                        </div>
                        <div class="column is-6">
                            <h1>Hasil</h1>
                            <Multiselect v-model="input.SHasil_ARJ" :attrs="{ value }" placeholder="--Pilih--"
                                label="label" :options="d_hasil_ARJ" :searchable="true" track-by="label" mode="single"
                                autocomplete="off" disabled>
                            </Multiselect>
                        </div>
                        <div class="column is-6">
                            <h1>Tindakan</h1>
                            <Multiselect v-model="input.STindakan_ARJ" :attrs="{ value }" placeholder="--Pilih--"
                                label="label" :options="d_tindakan_ARJ" :searchable="true" track-by="label"
                                mode="single" autocomplete="off">
                            </Multiselect>
                        </div>
                    </div>
                </div>

                <hr style="border-top: 1px dashed lightgray;background-color:white" class="mt-0 mb-1">

                <div class="column is-12">
                    <div class="columns m-0">
                        <div class="column is-6 pt-0 pl-0 is-flex" style="font-size:large;align-items:center;">
                            <h1>Riwayat Penggunaan Obat</h1>
                        </div>
                        <div class="column is-6 pt-0" align="right">
                            <VButton color="info" rounded raised size="medium" @click="inputObat()"
                                :loading="isLoading">
                                Pilih Riwayat Obat
                            </VButton>
                        </div>
                    </div>
                    <div class="column pt-0 pb-0">
                        <VField>
                            <VTextarea rows="4" v-model="input.TARiwayatPengunaanObat"></VTextarea>
                        </VField>
                    </div>
                </div>

                <hr style="border-top: 1px dashed lightgray;background-color:white" class="mt-0 mb-1">

                <div class="column is-12">
                    <div class="column is-12 pt-0 pl-0 pb-0" style="font-size:large">
                        <h1>Rencana Pemulangan Pasien</h1>
                    </div>
                    <div class="columns is-multiline column">
                        <div class="column is-3 pb-0">
                            <Multiselect v-model="input.S_RPP" :attrs="{ value }" placeholder="--Pilih--" label="label"
                                :options="d_RPP" :searchable="true" track-by="label" mode="single" autocomplete="off">
                            </Multiselect>
                        </div>
                        <div class="column is-12 columns">
                            <div class="column is-3">
                                <VField label="1.">
                                    <VControl>
                                        <VInput type="text" class="input" v-model="input.TBno1RPP" />
                                    </VControl>
                                </VField>
                            </div>
                            <div class="column is-3">
                                <VField label="2.">
                                    <VControl>
                                        <VInput type="text" class="input" v-model="input.TBno2RPP" />
                                    </VControl>
                                </VField>
                            </div>
                            <div class="column is-3">
                                <VField label="3.">
                                    <VControl>
                                        <VInput type="text" class="input" v-model="input.TBno3RPP" />
                                    </VControl>
                                </VField>
                            </div>
                            <div class="column is-3">
                                <VField label="4.">
                                    <VControl>
                                        <VInput type="text" class="input" v-model="input.TBno4RPP" />
                                    </VControl>
                                </VField>
                            </div>
                        </div>
                    </div>
                </div>

                <hr style="border-top: 1px dashed lightgray;background-color:white" class="mt-0 mb-1">

                <!-- Diagnosis Keperawatan Section -->
                <div class="column is-12">
                    <div class="column is-12 pt-0 pl-0 pb-0" style="font-size:large">
                    <h1>Diagnosis Keperawatan</h1>
                    </div>
                    <div class="control">
                    <input type="text" v-model="filterMenuDiagnosis" class="input" placeholder="Search Diagnosis..." />
                    </div>
                    <div class="pt-2">
                        <table class="tg2">
                            <tr v-for="row in filteredDiagnoses.diagnoses" :key="row.group">
                              <td width="200px" v-for="item in row.child" :key="item.uniqueId">
                                <VControl raw subcontrol v-if="item.type == 'checkbox'" class="checkbox-container">
                                  <VCheckbox
                                    class="p-0"
                                    color="primary"
                                    square
                                    :true-value="item.caption"
                                    v-model="input[item.uniqueId]"
                                  />
                                  <span v-html="highlightMatch(item.caption, filterMenuDiagnosis)" class="highlighted-label"></span>
                                </VControl>
                          
                                <VControl raw subcontrol v-if="item.type == 'checkbox2'" class="checkbox-container">
                                  <VTextarea
                                    v-model="input[item.uniqueId + '_textarea']"
                                    placeholder="Diagnosa Keperawatan Lainnya..."
                                  />
                                </VControl>
                              </td>
                            </tr>
                          </table>
                          
                    </div>
                </div>

                <hr style="border-top: 1px dashed lightgray;background-color:white" class="mt-0 mb-1">

                <!-- Rencana Keperawatan Section -->
                <div class="column is-12">
                    <div class="column is-12 pt-0 pl-0 pb-0" style="font-size:large">
                    <h1>Rencana Keperawatan</h1>
                    </div>
                    <div class="control">
                    <input type="text" v-model="filterMenuRencana" class="input" placeholder="Search Rencana..." />
                    </div>
                    <div class="pt-2">
                        <table class="tg2">
                            <tr v-for="row in filteredDiagnoses.rencana" :key="row.group">
                              <td width="200px" v-for="item in row.child" :key="item.uniqueId">
                                <VControl raw subcontrol v-if="item.type == 'checkbox'" class="checkbox-container">
                                  <VCheckbox
                                    class="p-0"
                                    color="primary"
                                    square
                                    :true-value="item.caption"
                                    v-model="input[item.uniqueId]"
                                  />
                                  <span v-html="highlightMatch(item.caption, filterMenuRencana)" class="highlighted-label"></span>
                                </VControl>
                          
                                <VControl raw subcontrol v-if="item.type == 'checkbox2'" class="checkbox-container">
                                  <VTextarea
                                    v-model="input[item.uniqueId + '_textarea']"
                                    placeholder="Diagnosa Keperawatan Lainnya..."
                                  />
                                </VControl>
                              </td>
                            </tr>
                          </table>
                          
                    </div>
                </div>


                <hr style="border-top: 1px dashed lightgray;background-color:white" class="mt-0 mb-1">

                <div class="columns">
                    <div class="column is-8"></div>
                    <div class="column is-4">
                        <VField label="Garut">
                            <VDatePicker v-model="input.DTttd" mode="datetime" trim-weeks :max-date="new Date()" is24hr>
                                <template #default="{ inputValue, inputEvents }">
                                    <VControl icon="feather:calendar" fullwidth>
                                        <VInput :value="inputValue" placeholder="Tanggal" v-on="inputEvents" />
                                    </VControl>
                                </template>
                            </VDatePicker>
                        </VField>
                        <div class="column" style="text-align:center;">
                            <h1>Tanda Tangan Perawat</h1>
                            <TandaTangan :elemenID="'TTDPerawat'" :width="'150'" :height="'150'" class="dek" />
                            <VControl class="prime-auto">
                                <AutoComplete v-model="input.CBPerawat" :suggestions="d_Petugas"
                                    @complete="fetchPetugas($event)" :optionLabel="'label'" :dropdown="true"
                                    :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                                    class="mt-2" />
                            </VControl>
                        </div>
                    </div>
                </div>
                <!-- form baru -->
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
                                    <td style="vertical-align: middle;text-align: center;border: 1px solid black;font-weight: bold;"
                                        width="15%">Tanggal Input</td>
                                    <td style="vertical-align: middle;text-align: center;border: 1px solid black;font-weight: bold;"
                                        width="15%">Tanggal Registrasi</td>
                                    <td style="vertical-align: middle;text-align: center;border: 1px solid black;font-weight: bold;"
                                        width="15%">No Registrasi</td>
                                    <td style="vertical-align: middle;text-align: center;border: 1px solid black;font-weight: bold;"
                                        width="15%">No EMR</td>
                                    <td style="vertical-align: middle;text-align: center;border: 1px solid black;font-weight: bold;"
                                        width="20%">Dokter</td>
                                    <td style="vertical-align: middle;text-align: center;border: 1px solid black;font-weight: bold;"
                                        width="15%">Section</td>
                                    <td style="vertical-align: middle;text-align: center;border: 1px solid black;font-weight: bold;"
                                        width="5%">#</td>
                                </tr>
                            </thead>
                            <tbody v-for="resep in listTemplate">
                                <tr>
                                    <td
                                        style="width:5%;text-align:center;border:1px solid black;vertical-align: middle;">
                                        <span class="mb-2">{{ resep.created_at }}</span><br>
                                    </td>
                                    <td
                                        style="width:5%;text-align:center;border:1px solid black;vertical-align: middle;">
                                        <span class="mb-2">{{ resep.registrasi.tglregistrasi }}</span><br>
                                    </td>
                                    <td
                                        style="width:5%;text-align:center;border:1px solid black;vertical-align: middle;">
                                        <span class="mb-2">{{ resep.registrasi.noregistrasi }}</span><br>
                                    </td>
                                    <td
                                        style="width:5%;text-align:center;border:1px solid black;vertical-align: middle;">
                                        <span class="mb-2">{{ resep.pasien.nocm }}</span><br>
                                    </td>
                                    <td
                                        style="width:5%;text-align:center;border:1px solid black;vertical-align: middle;">
                                        <span class="mb-2">{{ resep.dpjpUtama }}</span><br>
                                    </td>
                                    <td
                                        style="width:5%;text-align:center;border:1px solid black;vertical-align: middle;">
                                        <span class="mb-2">{{ resep.registrasi.namaruangan }}</span><br>
                                    </td>
                                    <td
                                        style="width:5%;text-align:center;border:1px solid black;vertical-align: middle;">
                                        <VIconButton type="button" raised circle icon="fas fa-plus"
                                            @click="addRiwayat(resep)" color="info"
                                            v-tooltip-prime.top="'Pilih'">
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
                        <table style="border: 1px solid black;" v-if="listTemplateFix.length > 0">
                            <thead>
                                <tr>
                                    <td style="vertical-align: middle;text-align: center;border: 1px solid black;font-weight: bold;"
                                        width="5%">No</td>
                                    <td style="vertical-align: middle;text-align: center;border: 1px solid black;font-weight: bold;"
                                        width="15%">Tanggal Dibuat</td>
                                    <td style="vertical-align: middle;text-align: center;border: 1px solid black;font-weight: bold;"
                                        width="20%">Nama Ruangan</td>
                                    <td style="vertical-align: middle;text-align: center;border: 1px solid black;font-weight: bold;"
                                        width="25%">Nama Template</td>
                                    <td style="vertical-align: middle;text-align: center;border: 1px solid black;font-weight: bold;"
                                        width="15%">#</td>
                                </tr>
                            </thead>
                            <tbody v-for="resep in listTemplateFix">
                                <tr>
                                    <td
                                        style="width:5%;text-align:center;border:1px solid black;vertical-align: middle;">
                                        <span class="mb-2">{{ resep.no }}</span><br>
                                    </td>
                                    <td
                                        style="width:15%;text-align:center;border:1px solid black;vertical-align: middle;">
                                        <span class="mb-2">{{ resep.created_at }}</span><br>
                                    </td>
                                    <td
                                        style="width:20%;text-align:center;border:1px solid black;vertical-align: middle;">
                                        <span class="mb-2">{{ resep.registrasi.namaruangan }}</span><br>
                                    </td>
                                    <td
                                        style="width:25%;text-align:center;border:1px solid black;vertical-align: middle;">
                                        <span class="mb-2">{{ resep.namatemplate }}</span><br>
                                    </td>
                                    <td
                                        style="width:15%;text-align:center;border:1px solid black;vertical-align: middle;padding: 3px;">
                                        <VIconButton type="button" raised circle icon="fas fa-plus"
                                            @click="addTemplate(resep)" color="info"
                                            v-tooltip-prime.top="'Pilih'">
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

    <VModal :open="alertMid" actions="center" noscroll noclose title="Terjadi Kesalahan" @close="alertMid = false">
        <template #content>
            <div class="is-flex" style="justify-content: start !important; ">
                <VIconBox size="xl" color="danger" class="alert-nobg">
                    <VIcon icon="lucide:alert-triangle" />
                </VIconBox>
                <VPlaceholderSection title="Data Triage Pasien IGD Tidak Ada"
                    subtitle="Silahkan isi Data Triage Pasien terlebih dahulu"
                    style="justify-content: start !important; " />
            </div>
        </template>
        <template #action>
            <VButton color="primary" raised @click="onTabTriageIGD()">
                Ke Triage
            </VButton>
        </template>
    </VModal>

    <VModal :open="showModalObat" title="Riwayat Obat" :noclose="true" size="large" actions="right"
        @close="showModalObat = false">
        <template #content>
            <DataTable :pt="{
                table: { style: 'min-width: 50rem; min-height: 10rem;' },
                column: {
                    bodycell: ({ state }) => ({
                        class: [{ 'pt-0 pb-0': state['d_editing'] }]
                    })
                }
            }" v-model:selection="ObatSelected" :value="listSIMRSLama" :metaKeySelection="metaKey" :rows="10" paginator
                tableStyle="min-width: 50rem" dataKey="no" :totalRecords="listSIMRSLama.length" responsiveLayout="stack"
                breakpoint="960px">
                <Column selectionMode="multiple" headerStyle="width: 3rem"></Column>
                <Column field="namaobat" header="Nama" :sortable="true">
                    <template #body="slotProps">
                        <span>{{ slotProps.data.namaobat + ' - ' + slotProps.data.jenisobat }}</span>
                    </template>
                </Column>
                <Column field="noorder" header="No Resep" :sortable="true"></Column>
                <Column field="noregistrasi" header="No Registrasi" :sortable="true"></Column>
                <Column field="namalengkap" header="Dokter" :sortable="true" style="width: 150px;;"></Column>
                <Column field="tglorder" header="Tanggal" :sortable="true">
                    <template #body="slotProps">
                        <span>{{ H.formatDateToLocalString(slotProps.data.tglorder) }}</span>
                    </template>
                </Column>
            </DataTable>
        </template>
        <template #action>
            <VButton type="button" color="primary" raised @click="addToInput()">
                Tambah
            </VButton>
        </template>
    </VModal>

</template>

<script setup lang="ts">
import { useWindowScroll } from '@vueuse/core'
import { useApi } from '/@src/composable/useApi'
import { h, reactive, ref, computed, watch, onBeforeMount, onMounted, watchEffect } from 'vue'
import { useRoute, useRouter, onBeforeRouteLeave } from 'vue-router'
import { useHead } from '@vueuse/head'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useUserSession } from '/@src/stores/userSession'
import ButtonEmr from '../page-emr-plugins/button-emr.vue'
import TandaTangan from '../page-emr-plugins/tanda-tangan.vue'
import * as H from '/@src/utils/appHelper'
import AutoComplete from 'primevue/autocomplete';
import Fieldset from 'primevue/fieldset';
import * as EMR from '../page-emr-plugins/asesmen-awal-keperawatan-igd'
import * as EMR2 from '../page-emr-plugins/asesmen-fisioterapi.ts'
import DataTable from 'primevue/datatable';
import Column from 'primevue/column'
import moment from 'moment'

// Loopingan
let detailSkriningNutrisi = ref(EMR.detailSkriningNutrisi())
let detailStatusFungsional = ref(EMR.detailStatusFungsional())
let detailDiagnosisKeperawatan = ref(EMR.detailDiagnosisKeperawatan())
let detailRencanaKeperawatan = ref(EMR.detailRencanaKeperawatan())
let statusFungsional: any = ref(EMR.statusFungsional())
let listImageNyeri: any = ref(EMR2.imgNyeri())
let listSkoringNyeri: any = ref(EMR2.skoringNyeri())

// Judul
useHead({ title: 'Asesmen Awal Keperawatan Gawat Darurat - ' + import.meta.env.VITE_PROJECT })

useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)
let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pd as string
let norec_emr = useRoute().query.norec_emr as string
const metaKey = ref(true);
const user = useUserSession().getUser().pegawai;
const listSIMRSLama: any = ref([])
const showModalObat: any = ref(false);
const ObatSelected: any = ref()
const filterMenu: any = ref('')
const dataTTD: any = ref([])
const route = useRoute()
const pasien: any = ref({})
const d_Petugas: any = ref([])
const loadData: any = ref(true)
const COLLECTION: any = ref('AsesmenAwalKeperawatanGawatDarurat') //table mongodb
const TAB_DEFAULT = ref('')
const TAB_ACTIVE: any = ref('Dashboard');
const TAB_URL = ref('')
const TAB_ACTIVE_ROUTER: any = ref(null)
const TAB_ROUTER_DEFAULT = ref('module-emr-profile-pasien-page-emr-not-found')
const TAB_ITEMS: any = ref([]);
const NOREC_EMRPASIEN: any = ref('')
const { y } = useWindowScroll()
const router = useRouter()
const isLoading = ref(false)
const isAktive = ref()
const listTemplate: any = ref([])
const showModalTemplate: any = ref(false)
const listTemplateFix: any = ref([])
const showModalTemplateFix: any = ref(false)
const alertMid = ref(false);
const isStuck = computed(() => {
    return y.value > 30
})

//? Array Input
// === Array Default ===
const d_yaTidak: any = ref([{ value: 1, label: 'Ya' }, { value: 2, label: 'Tidak' }])
// =====================

const d_rujukan: any = ref([{ value: 1, label: 'Ya' }, { value: 2, label: 'Tidak' }, { value: 3, label: 'Datang Sendiri' }, { value: 4, label: 'Diantar' }])
const d_tempatRujukan: any = ref([{ value: 1, label: 'RS' }, { value: 2, label: 'Puskesmas' }, { value: 3, label: 'dr.' }, { value: 4, label: 'Lainnya' }])

const d_allo: any = ref([{ value: 1, label: 'Suami/Istri' }, { value: 2, label: 'Orang Tua' }, { value: 3, label: 'Anak' }, { value: 4, label: 'Lainnya' }])
const d_keadaanumum: any = ref([{ value: 1, label: 'Baik' }, { value: 2, label: 'Sedang' }, { value: 3, label: 'Buruk' }])

const d_skalanyeri: any = ref([{ value: 1, label: 'NRS' }, { value: 2, label: 'WBS' }, { value: 3, label: 'FLACC' }])
const d_frekuensinyeri: any = ref([{ value: 1, label: 'Jarang' }, { value: 2, label: 'Hilang timbul' }, { value: 3, label: 'Terus menerus' }])
const d_kualitasnyeri: any = ref([{ value: 1, label: 'Tumpul' }, { value: 2, label: 'Tajam' }, { value: 3, label: 'Panas/Terbakar' }, { value: 4, label: 'Lainnya' }])

const d_kondisiPsikologis: any = ref([{ value: 0, label: 'Tidak ada' }, { value: 1, label: 'Gelisah' }, { value: 2, label: 'Takut' }, { value: 3, label: 'Sedih' }, { value: 4, label: 'Rendah diri' }, { value: 5, label: 'Acuh tak acuh' }, { value: 6, label: 'Mudah tersinggung' }, { value: 7, label: 'Menarik diri' }])
const d_masalahPernikahan: any = ref([{ value: 1, label: 'Tidak ada' }, { value: 2, label: 'Ada' }])
const d_mengalamiKekerasanFisik: any = ref([{ value: 1, label: 'Tidak ada' }, { value: 2, label: 'Ada' }])
const d_pembiayaanKesehatan: any = ref([{ value: 1, label: 'Biaya sendiri/keluarga' }, { value: 2, label: 'Asuransi lainnya' }])

const d_mengontrolbab: any = ref([{ value: 0, label: 'Inkontinen/tidak teratur (perlu enema)' }, { value: 1, label: 'Kadang inkontinen (1xseminggu)' }, { value: 2, label: 'Kontinen teratur' }])
const d_mengontrolbak: any = ref([{ value: 0, label: 'Inkontinen/pakai kateter dan tidak terkontrol' }, { value: 1, label: 'Kadang inkontinen (max 1x24 jam)' }, { value: 2, label: 'Mandiri' }])
const d_bersihdiri: any = ref([{ value: 0, label: 'Butuh pertolongan orang lain' }, { value: 1, label: 'Mandiri' }])
const d_toilet: any = ref([{ value: 0, label: 'Tergantung pertolongan orang lain' }, { value: 1, label: 'Perlu pertolongan pada beberapa aktivitas terapi dan dapat mengerjakan sendiri beberapa aktivitas lain' }, { value: 2, label: 'Mandiri' }])
const d_makan: any = ref([{ value: 0, label: 'Tidak mampu' }, { value: 1, label: 'Perlu seseorang menolong memotong makanan' }, { value: 2, label: 'Mandiri' }])
const d_berpindahtt: any = ref([{ value: 0, label: 'Tidak Mampu' }, { value: 1, label: 'Perlu banyak bantuan untuk bisa duduk (2 orang)' }, { value: 2, label: 'Bantuan 1 orang' }, { value: 3, label: 'Mandiri' }])
const d_mobilisasi: any = ref([{ value: 0, label: 'Tidak Mampu' }, { value: 1, label: 'Dengan kursi roda' }, { value: 2, label: 'Bantuan 1 orang' }, { value: 3, label: 'Mandiri' }])
const d_berpakaian: any = ref([{ value: 0, label: 'Tergantung orang lain' }, { value: 1, label: 'Sebagian dibantu (misal mengancing baju)' }, { value: 2, label: 'Mandiri' }])
const d_tangga: any = ref([{ value: 0, label: 'Tidak Mampu' }, { value: 1, label: 'Butuh Pertolongan' }, { value: 2, label: 'Mandiri' }])
const d_mandi: any = ref([{ value: 0, label: 'Teragantung orang lain' }, { value: 1, label: 'Mandiri' }])


const d_penurunanbb: any = ref([
    { value: 0, label: 'Tidak' },
    { value: 2, label: 'Tidak Yakin' }
]);
const d_penurunannafsu: any = ref([
    { value: 1, label: 'Ya' },
    { value: 0, label: 'Tidak' }
]);
const d_penurunanbbYa: any = ref([
    { value: 1, label: '1-5 kg' },
    { value: 2, label: '6-10 kg' },
    { value: 3, label: '11-15 kg' },
    { value: 4, label: '>15 kg' }
]);

const d_hasil_ARJ: any = ref([
    { value: 1, label: 'Tidak berisiko (tidak ditemukan a dan b)' },
    { value: 2, label: 'Risiko rendah ( a atau b ditemukan)' },
    { value: 3, label: 'Risiko tinggi ( a dan b ditemukan)' },
])
const d_tindakan_ARJ: any = ref([
    { value: 1, label: 'Tidak ada tindakan' },
    { value: 2, label: 'Edukasi' },
    { value: 3, label: 'Pasang penanda risiko jatuh' },
])

const d_RPP: any = ref([{ value: 1, label: 'Perlu' }, { value: 2, label: 'Tidak Perlu' }])

const input: any = ref({
    DTttd: new Date(),
    DTanggalForm: new Date(),
    TJamMasuk: new Date(),
    TJamAsesmenAwal: new Date(),
    nilaiSkrining: 0,
    penurunanbb: 0,
    penurunannafsu: 0,
    penurunanbbYa: 0,
    nilai: "RISIKO RENDAH (MST 0-1)",
    CBKetergantunganTotal: "Ketergantungan total (0-4)"
})
const props = withDefaults(
    defineProps<{
        pasien?: any
        registrasi?: any
        FORM_NAME?: string
        FORM_URL?: string
        COLLECTION?: string
        hideButtons?: boolean
    }>(),
    {
        pasien: {},
        registrasi: {},
        FORM_NAME: '',
        FORM_URL: '',
        COLLECTION: '',
        hideButtons: false,
    }
)
const item: any = reactive({
    NOREC_PD: NOREC_PD != undefined ? NOREC_PD : '',
    NOREC_APD: props.registrasi.norec_apd,
    RUANGAN_LAST: props.registrasi.objectruanganlastfk,
    registrasi: {},
    pegawaiOrder: useUserSession().getUser().id,
    date: {
        tanggal: new Date,
        jam: new Date
    },
    filter: '',
    airway: [],
    disability: []
})

//? Function
const fetchPetugas = async (filter: any) => {
    await useApi().get(`emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&limit=10&query=${filter.query}`).then((response) => {
        d_Petugas.value = response
    })
}

function PsikologisNormal() {
  if (input.value.PsikologisNormal == false) {
    input.value.SKondisiPsikologis = 0;
    input.value.SMasalahPernikahan = 1;
    input.value.SMengalamiKekerasanFisik = 1;
    input.value.TBKeyakinanDanNilaiPribadi = 'Tidak Ada';
    input.value.SPembiayaanKesehatan = 2;
    input.value.TBAsuransiLainnya = 'BPJS';
    input.value.TBKebiasaanAdatIstiadat = 'Tidak Ada';
    input.value.SPerluRohaniawan = 2;
  } else {
    input.value.SKondisiPsikologis = null;
    input.value.SMasalahPernikahan = null;
    input.value.SMengalamiKekerasanFisik = null;
    input.value.TBKeyakinanDanNilaiPribadi = null;
    input.value.SPembiayaanKesehatan = null;
    input.value.TBAsuransiLainnya = null;
    input.value.TBKebiasaanAdatIstiadat = null;
    input.value.SPerluRohaniawan = null;
  }
}
function BatasNormalRisikoJatuh() {
  if (input.value.BatasNormalRisikoJatuh == false) {
    input.value.S1_ARJ = 2;
    input.value.S2_ARJ = 2;
    input.value.STindakan_ARJ = 1;
  } else {
    input.value.S1_ARJ = null;
    input.value.S2_ARJ = null;
    input.value.STindakan_ARJ = null;
  }
}

const loadRiwayat = async () => {
    isLoading.value = true
    const response = await useApi().get(`/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${NOREC_PD}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`)
    isLoading.value = false
    if (response.length) {
        input.value = response[0] //set ke inputan
        if (NOREC_EMRPASIEN.value == '') {
            NOREC_EMRPASIEN.value = response[0].emrpasienfk
        }
        let data = input.value;
        if (data.riwayatpenyakit == null || data.riwayatpenyakitdahulu == null || data.riwayatpengobatan == null || data.keluhanutama == null) {
            const response_AsmedIGD = await useApi().get("emr/auto-fill?nocmfk=" + ID_PASIEN + "&norec_pd=" + NOREC_PD + "&collection=AsesmenAwalMedisGawatDarurat" + `&field=TARiwayatPenyakitSekarang,TARPS,TARiwayatPenyakitDahulu,TARiwayatPenggunaanObat,isalergi,CBAlergiObat,TBAlergiObat,CBAlergiMakanan,TBAlergiMakanan,CBAlergiLainnya,TBAlergiLainnya,TAKeluhanUtama`)
            if (response_AsmedIGD != null) {
                input.value.riwayatpenyakit = response_AsmedIGD.TARPS;
                input.value.riwayatpenyakitdahulu = response_AsmedIGD.TARiwayatPenyakitDahulu;
                input.value.riwayatpengobatan = response_AsmedIGD.TARiwayatPenggunaanObat;
                input.value.keluhanutama = response_AsmedIGD.TAKeluhanUtama;
                input.isalergi = response_AsmedIGD.isalergi ?? null;
                if (input.isalergi == 'YA') {
                    input.value.CBAlergiObat = response_AsmedIGD.CBAlergiObat ?? null;
                    input.value.TBAlergiObat = response_AsmedIGD.TBAlergiObat ?? null;
                    input.value.CBAlergiMakanan = response_AsmedIGD.CBAlergiMakanan ?? null;
                    input.value.TBAlergiMakanan = response_AsmedIGD.TBAlergiMakanan ?? null;
                    input.value.CBAlergiLainnya = response_AsmedIGD.CBAlergiLainnya ?? null;
                    input.value.TBAlergiLainnya = response_AsmedIGD.TBAlergiLainnya ?? null;
                }
            }
        }
        dataTTD.value = response[0]
        H.tandaTangan().set("TTDPerawat", dataTTD.value.TTDPerawat)
    } else {
        input.value.mengontrolbab = 2
        input.value.mengontrolbak = 2
        input.value.bersihdiri = 1
        input.value.toilet = 2
        input.value.makan = 2
        input.value.berpindahtt = 3
        input.value.mobilisasi = 3
        input.value.berpakaian = 2
        input.value.tangga = 2
        input.value.mandi = 1
        // input.value.nilaimandi = 0
        input.value.CBPerawat = { label: user.namaLengkap, value: user.id }
        const response_TPI = await useApi().get("emr/auto-fill?nocmfk=" + ID_PASIEN + "&norec_pd=" + NOREC_PD + "&collection=TriagePasienIGD" + `&field=keadaanumum,TBeGCS,TBvGCS,TBmGCS,TBcelciusTTV,TBPernafasanTTV,TBnadiTTV,TBtekananDarahTTV,TBtinggiBadanTTV,TBnspo2TTV,TBberatBadanTTV,DTanggalKedatangan,TjamKedatangan,created_at`)
        const response_AsmedIGD = await useApi().get("emr/auto-fill?nocmfk=" + ID_PASIEN + "&norec_pd=" + NOREC_PD + "&collection=AsesmenAwalMedisGawatDarurat" + `&field=TARiwayatPenyakitSekarang,TARPS,TARiwayatPenyakitDahulu,TARiwayatPenggunaanObat,isalergi,CBAlergiObat,TBAlergiObat,CBAlergiMakanan,TBAlergiMakanan,CBAlergiLainnya,TBAlergiLainnya,TAKeluhanUtama,DTanggalKedatangan,TjamKedatangan,TjamTriage,keadaanumum,TBeGCS,TBvGCS,TBmGCS,TBTekananDarahTTV,TBNadiTTV,TBRespirasiTTV,TBCelciusTTV,TBnsao2TTV,TARPS`)
        if (response_TPI != null) {
            input.value.DTanggalForm = response_TPI.DTanggalKedatangan;
            input.value.TJamMasuk = response_TPI.TjamKedatangan;
            input.value.TJamAsesmenAwal = response_TPI.created_at;
            input.value.SKeadaanUmum = response_TPI.keadaanumum;
            input.value.GCSe = response_TPI.TBeGCS;
            input.value.GCSv = response_TPI.TBvGCS;
            input.value.GCSm = response_TPI.TBmGCS;
            input.value.TBtekananDarahTTV = response_TPI.TBtekananDarahTTV;
            input.value.TBnadiTTV = response_TPI.TBnadiTTV;
            input.value.TBPernafasanTTV = response_TPI.TBPernafasanTTV;
            input.value.TBcelciusTTV = response_TPI.TBcelciusTTV;
            input.value.TBnspo2TTV = response_TPI.TBnspo2TTV;
            input.value.TBberatBadanTTV = response_TPI.TBberatBadanTTV;
            input.value.TBtinggiBadanTTV = response_TPI.TBtinggiBadanTTV;
        } else {
          H.alert('warning', 'Data Triage Pasien IGD belum diisi');
        }
        if (response_AsmedIGD != null) {
            input.value.riwayatpenyakitdahulu = response_AsmedIGD.TARiwayatPenyakitDahulu;
            input.value.riwayatpengobatan = response_AsmedIGD.TARiwayatPenggunaanObat;
            input.value.riwayatpenyakit = response_AsmedIGD.TARPS;
            input.value.keluhanutama = response_AsmedIGD.TAKeluhanUtama;
            input.value.isalergi = response_AsmedIGD.isalergi ?? null;
            if (response_AsmedIGD.isalergi == 'YA') {
                input.value.CBAlergiObat = response_AsmedIGD.CBAlergiObat ?? null;
                input.value.TBAlergiObat = response_AsmedIGD.TBAlergiObat ?? null;
                input.value.CBAlergiMakanan = response_AsmedIGD.CBAlergiMakanan ?? null;
                input.value.TBAlergiMakanan = response_AsmedIGD.TBAlergiMakanan ?? null;
                input.value.CBAlergiLainnya = response_AsmedIGD.CBAlergiLainnya ?? null;
                input.value.TBAlergiLainnya = response_AsmedIGD.TBAlergiLainnya ?? null;
                input.value.SKeadaanUmum = response_AsmedIGD.keadaanumum ?? null;
                input.value.GCSe = response_AsmedIGD.TBeGCS ?? null;
                input.value.GCSv = response_AsmedIGD.TBvGCS ?? null;
                input.value.GCSm = response_AsmedIGD.TBmGCS ?? null;
                input.value.TBcelciusTTV = response_AsmedIGD.TBCelciusTTV ?? null;
                input.value.TBPernafasanTTV = response_AsmedIGD.TBRespirasiTTV ?? null;
                input.value.TBnadiTTV = response_AsmedIGD.TBNadiTTV ?? null;
                input.value.TBtekananDarahTTV = response_AsmedIGD.TBTekananDarahTTV ?? null;
                input.value.TBnspo2TTV = response_AsmedIGD.TBnsao2TTV ?? null;
                input.value.TBberatBadanTTV = response_AsmedIGD.tinggiBadanTTV ?? null;
                input.value.TBtinggiBadanTTV = response_AsmedIGD.beratBadanTTV ?? null;
            }
        }
        H.alert('info', 'Data berhasil dimuat')
        isLoading.value = false
    }
}
const simpan = async () => {
    let ID = input.value.id ? input.value.id : ''
    let object: any = {}
    object = input.value
    if (object.hasOwnProperty('namatemplate')) {
        delete object.namatemplate
    }
    object.pasien = H.setObjectPasien(props.pasien)
    object['TTDPerawat'] = H.tandaTangan().get("TTDPerawat");
    object.registrasi = H.setObjectRegistrasi(props.registrasi)
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
        NOREC_EMRPASIEN.value = response.norec_emr
        input.value.id = response.id
        loadRiwayat();
    }).catch((e: any) => {
        isLoading.value = false
    })
}


const simpanTemplate = () => {
    if (!input.value.namatemplate) {
        H.alert('warning', "Nama Template wajib diisi")
        return;
    }
    let ID = input.id ? input.id : ''
    let object: any = {}

    object = input.value
    object.nocm = pasien.value.nocm

    object.pasien = H.setObjectPasien(pasien.value)
    object.registrasi = H.setObjectRegistrasi(props.registrasi)
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

const onTabTriageIGD = () => {
    COLLECTION.value = 'Triage IGD'
    TAB_URL.value = `module-emr-profile-pasien-page-emr-triage-pasien-igd`
    TAB_ACTIVE.value = 'Triage IGD'
    TAB_ACTIVE_ROUTER.value = `module-emr-profile-pasien-page-emr-triage-pasien-igd`

    setRoutingEMR(TAB_ACTIVE_ROUTER.value, '')

    alertMid.value = false;
}

const setRoutingEMR = (form: any, norec_emr: any) => {
    let query: any = {}
    let params: any = {}
    console.log("DATA ITEM", item);

    if (NOREC_EMRPASIEN.value != '') {
        query = {
            nocmfk: pasien.value.nocmfk,
            norec_pasien_daftar: item.NOREC_PD,
            norec_pd: item.NOREC_PD,
            norec_apd: item.NOREC_APD,
            jenisobgyn: '',
            norec_emr: NOREC_EMRPASIEN.value,
        }
    } else {
        query = {
            nocmfk: pasien.value.nocmfk,
            norec_pasien_daftar: item.NOREC_PD,
            norec_pd: item.NOREC_PD,
            norec_apd: item.NOREC_APD,
            jenisobgyn: '',
        }
    }

    console.log(query)
    if (form.indexOf('index_tab') > -1) {
        params = {
            index_tabs: 1
        }
    }
    router.push({
        name: form,
        query: query,
        params: params
    })
}

const filterMenuDiagnosis = ref('');
const filterMenuRencana = ref('');

const escapeRegex = (str) => str.replace(/[-\/\\^$*+?.()|[\]{}]/g, '\\$&');

const filteredDiagnoses = computed(() => {
  const termDiagnosis = (filterMenuDiagnosis.value ?? '').trim().toLowerCase();
  const termRencana = (filterMenuRencana.value ?? '').trim().toLowerCase();

  const formatUniqueId = (caption = '', prefix = '') => {
    return `${prefix}_${caption
      .toLowerCase()
      .trim()
      .split(/\s+/) // Pisah berdasarkan spasi
      .slice(0, 3) // Ambil maksimal 3 kata pertama
      .join('_')   // Gabungkan dengan underscore
      .replace(/[^\w]/g, '') // Hapus karakter selain huruf, angka, dan underscore
    }`;
  };

  const filterItems = (items, term, prefix) => {
    if (!Array.isArray(items)) return [];

    return items
      .map(group => ({
        ...group,
        child: (group.child ?? [])
          .filter(diagnosis => {
            if (!diagnosis?.caption) return true;

            const regex = new RegExp(escapeRegex(term), 'gi');
            return regex.test(diagnosis.caption.toLowerCase());
          })
          .map(diagnosis => ({
            ...diagnosis,
            uniqueId: formatUniqueId(diagnosis.caption, prefix) // 🔥 Gunakan fungsi baru
          }))
      }))
      .filter(group => group.child.length > 0);
  };

  return {
    diagnoses: filterItems(detailDiagnosisKeperawatan.value ?? [], termDiagnosis, 'checkboxDK'),
    rencana: filterItems(detailRencanaKeperawatan.value ?? [], termRencana, 'checkboxRK')
  };
});

watchEffect(() => {
  const newInputValue = { ...input.value }; // Clone current input values

  // Function to migrate old checkboxes to new format
  const migrateCheckboxValues = (prefix, maxIndex, targetList) => {
    for (let i = 0; i <= maxIndex; i++) {
      for (let j = 0; j <= 1; j++) {
        const oldKey = `${prefix}_${i}_${j}`;

        if (input.value[oldKey]) {
          const oldValue = input.value[oldKey]; // Get the old checkbox value

          // Find matching new uniqueId
          targetList.forEach(group => {
            group.child.forEach(item => {
              if (oldValue.includes(item.caption)) {
                newInputValue[item.uniqueId] = oldValue; // Assign the old value to the new key
              }
            });
          });

          // Remove old checkbox key after migration
          delete newInputValue[oldKey];
        }
      }
    }
  };

  // Migrate checkboxDK values
  migrateCheckboxValues('checkboxDK', 12, filteredDiagnoses.value.diagnoses);

  // Migrate checkboxRK values
  migrateCheckboxValues('checkboxRK', 13, filteredDiagnoses.value.rencana);

  // Update input.value after migration
  input.value = newInputValue;
});




function highlightMatch(text, term) {
  if (!term || !text) return text;

  // Escape special characters in term to avoid regex errors
  const escapedTerm = term.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');

  // Create a safe regex pattern
  const regex = new RegExp(`(${escapedTerm})`, 'gi');

  return text.replace(regex, '<span style="background-color: yellow;">$1</span>'); // HIGHLIGHT MATCHED TEXT
}


const inputObat = async () => {
    if (listSIMRSLama.value.length > 0) {
        ObatSelected.value = [];
        showModalObat.value = true;
    } else {
        listSIMRSLama.value = []
        let lokal = false;
        let riwayat1 = []
        isLoading.value = true

        let responseX = await useApi().get(`/farmasi/riwayat-order-resep?norec_pd=${NOREC_PD}`)

        isLoading.value = false
        let nomor = 0;
        if (responseX.length > 0) {
            for (let x = 0; x < responseX.length; x++) {
                const element = responseX[x];
                for (let d = 0; d < element.details.length; d++) {
                    nomor++;
                    const detail = element.details[d];
                    riwayat1.push({
                        'no': nomor,
                        'namalengkap': element.namalengkap,
                        'noregistrasi': element.noregistrasi,
                        'noorder': element.noorder,
                        'tglorder': moment(element.tglorder).format('DD-MM-YYYY'),
                        'namaobat': detail.namaproduk,
                        'jenisobat': detail.jeniskemasan,
                        'simslama': true,
                    })
                }
            }
            listSIMRSLama.value = riwayat1
            showModalObat.value = true;
        } else {
            H.alert('warning', 'Pasien belum mempunyai riwayat obat')
        }
    }

}
const addToInput = (event) => {
    console.log("obat selected", ObatSelected)
    let inputss = input.value.TARiwayatPengunaanObat == undefined ? '' : input.value.TARiwayatPengunaanObat;
    if (ObatSelected.value.length > 0) {
        ObatSelected.value.forEach((obt, ind) => {
            inputss += ` # ${obt.namaobat} `
        })
    }
    input.value.TARiwayatPengunaanObat = inputss
    showModalObat.value = false;
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
    const skipKeys = [
        'id', '_id', 'namatemplate',
        'SKeadaanUmum', 'GCSe', 'GCSv', 'GCSm',
        'TBcelciusTTV', 'TBPernafasanTTV', 'TBnadiTTV', 'TBtekananDarahTTV',
        'TBnspo2TTV', 'TBberatBadanTTV', 'TBtinggiBadanTTV',
        'device', 'NC', 'SM', 'NRM', 'cpap', 'venti'
    ];

    for (const key in response) {
        if (!skipKeys.includes(key)) {
            input.value[key] = response[key]; // Only update allowed keys
        }
    }
    showModalTemplateFix.value = false
    H.alert('info', 'Template berhasil ditambahkan')
}
const addRiwayat = (response: any) => {
    input.value = response //set ke inputan
    delete input.value.namatemplate;
    delete input.value['_id'];
    showModalTemplate.value = false
    showModalTemplateFix.value = false
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

onBeforeMount(async () => {
    try {
        await loadRiwayat()
        let cache = H.cacheEMR().get(`TAB~${props.registrasi.noregistrasi}~${route.name}`)
        if (cache) input.value = cache
        loadData.value = false
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

watch(() => [input.value.penurunanbb, input.value.penurunannafsu, input.value.penurunanbbYa], ([newValuePenurunanBB, newValuePenurunanBBYa, newValuePenurunanNafsu]) => {
    let totalNilaiSkriningKalkulasi
    //? Mencegah value checbox dari undefined
    newValuePenurunanBB = newValuePenurunanBB ?? 0;
    newValuePenurunanBBYa = newValuePenurunanBBYa ?? 0;
    newValuePenurunanNafsu = newValuePenurunanNafsu ?? 0;

    //? Calculate total Skrining Nutrisi
    totalNilaiSkriningKalkulasi = newValuePenurunanBB + newValuePenurunanBBYa + newValuePenurunanNafsu
    input.value.nilaiSkrining = totalNilaiSkriningKalkulasi

    if (totalNilaiSkriningKalkulasi >= 0 && totalNilaiSkriningKalkulasi <= 1) {
        input.value.nilai = "RISIKO RENDAH (MST 0-1)";
    } else if (totalNilaiSkriningKalkulasi >= 2 && totalNilaiSkriningKalkulasi <= 3) {
        input.value.nilai = "RISIKO SEDANG (MST 2-3)";
    } else if (totalNilaiSkriningKalkulasi >= 4) {
        input.value.nilai = "RISIKO TINGGI (MST 4-5)";
    }
});

watch(() => [input.value.S1_ARJ, input.value.S2_ARJ], ([newValueS1_ARJ, newValueS2_ARJ]) => {
    //? Mencegah value checbox dari undefined
    newValueS1_ARJ = newValueS1_ARJ ?? 0;
    newValueS2_ARJ = newValueS2_ARJ ?? 0;

    if (newValueS1_ARJ == 2 && newValueS2_ARJ == 2) {
        input.value.SHasil_ARJ = 1;
    } else if ((newValueS1_ARJ == 1 && newValueS2_ARJ == 2) || newValueS1_ARJ == 2 && newValueS2_ARJ == 1) {
        input.value.SHasil_ARJ = 2;
    } else if (newValueS1_ARJ == 1 && newValueS2_ARJ == 1) {
        input.value.SHasil_ARJ = 3;
    }
});

watch(() => [
    input.value.mengontrolbab,
    input.value.mengontrolbak,
    input.value.bersihdiri,
    input.value.toilet,
    input.value.makan,
    input.value.berpindahtt,
    input.value.mobilisasi,
    input.value.berpakaian,
    input.value.tangga,
    input.value.mandi,
], ([
    newValueMengontrolBab,
    newValueMengontrolBak,
    newValueBersihDiri,
    newValueToilet,
    newValueMakan,
    newValueBerpindahTT,
    newValueMobilisasi,
    newValueBerpakaian,
    newValueTangga,
    newValueMandi,
]) => {
    let totalNilaiStatusFungsional;
    //? Mencegah dari undefined
    newValueMengontrolBab = newValueMengontrolBab ?? 0;
    newValueMengontrolBak = newValueMengontrolBak ?? 0;
    newValueBersihDiri = newValueBersihDiri ?? 0;
    newValueToilet = newValueToilet ?? 0;
    newValueMakan = newValueMakan ?? 0;
    newValueBerpindahTT = newValueBerpindahTT ?? 0;
    newValueMobilisasi = newValueMobilisasi ?? 0;
    newValueBerpakaian = newValueBerpakaian ?? 0;
    newValueTangga = newValueTangga ?? 0;
    newValueMandi = newValueMandi ?? 0;

    //? Calculate Status Fungsional
    totalNilaiStatusFungsional = newValueMengontrolBab + newValueMengontrolBak + newValueBersihDiri + newValueToilet + newValueMakan + newValueBerpindahTT + newValueMobilisasi + newValueBerpakaian + newValueTangga + newValueMandi
    input.value.nilaimandi = totalNilaiStatusFungsional

    if (totalNilaiStatusFungsional >= 0 && totalNilaiStatusFungsional <= 4) {
        input.value.CBStatusFungsional = "Ketergantungan total (0-4)"
    } else if (totalNilaiStatusFungsional >= 5 && totalNilaiStatusFungsional <= 8) {
        input.value.CBStatusFungsional = "Ketergantungan berat (5-8)"
    } else if (totalNilaiStatusFungsional >= 9 && totalNilaiStatusFungsional <= 11) {
        input.value.CBStatusFungsional = "Ketergantungan sedang (9-11)"
    } else if (totalNilaiStatusFungsional >= 12 && totalNilaiStatusFungsional <= 19) {
        input.value.CBStatusFungsional = "Ketergantungan ringan(12-19)"
    } else if (totalNilaiStatusFungsional >= 20) {
        input.value.CBStatusFungsional = "Mandiri (20)"
    }
});
</script>

<style lang="scss">
.tg {
    border-collapse: collapse;
    border-spacing: 0;
    width: 150%;
}

.tg2 {
    border-collapse: collapse;
    border-spacing: 0;
    width: 100% !important;
    border: 1px solid black;
}

.tg2 td {
    border-style: solid;
    border-width: 1px;
    border-color: black;
    font-family: Arial, sans-serif;
    font-size: 14px;
    overflow: hidden;
    padding: 10px 5px;
    word-break: normal;
}

.tg2 th {
    border-style: solid;
    border-width: 1px;
    font-family: Arial, sans-serif;
    font-size: 14px;
    border-color: black;
    font-weight: normal;
    overflow: hidden;
    padding: 10px 5px;
    word-break: normal;
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
    border-style: solid;
    border-width: 1px;
    font-family: Arial, sans-serif;
    font-size: 14px;
    font-weight: normal;
    overflow: hidden;
    padding: 10px 5px;
    word-break: normal;
}

.tg .tg-0lax {
    text-align: left;
    vertical-align: middle
}

h1 {
    font-weight: bold;
}

label {
    color: black !important;
}

hr {
    background-color: hsl(0deg 6.81% 88.68%);
    border: none;
    display: block;
    height: 2px;
    margin: 0px;
}

.alert-nobg {
    background: none !important;
    height: 5em;
    width: 5em;

}

.p-fieldset-content {
    background: white !important;
}

input::-webkit-outer-spin-button,
input::-webkit-inner-spin-button {
    -webkit-appearance: none;
    margin: 0;
}
</style>
