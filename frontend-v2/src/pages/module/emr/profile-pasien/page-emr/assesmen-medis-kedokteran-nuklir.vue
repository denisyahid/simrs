<template>
    <div class="form-layout is-stacked-2">
        <div class="form-outer" style="margin-top:15px">
            <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
                <div class="form-header-inner">
                    <div class="left">
                        <h3> {{ props.FORM_NAME }}</h3>
                    </div>
                    <div class="right">
                        <ButtonEmr :NOREC_EMRPASIEN="NOREC_EMRPASIEN" :COLLECTION="COLLECTION" :isLoading="isLoading"
                            @simpan="simpan" @simpanTemplate="simpanTemplate" @kembaliKeun="kembaliKeun"
                            isHideCetak="true" isHideST></ButtonEmr>
                    </div>
                </div>
            </div>

            <div class="column is-12">
                <div class="column columns pb-0">
                    <div class="column is-6 pb-0">
                        <h1>Tanggal Masuk</h1>
                        <VDatePicker v-model="input.DTanggalMasuk" mode="dateTime" trim-weeks :max-date="new Date()">
                            <template #default="{ inputValue, inputEvents }">
                                <VControl icon="feather:calendar" fullwidth>
                                    <VInput :value="inputValue" placeholder="Tanggal masuk..." v-on="inputEvents" />
                                </VControl>
                            </template>
                        </VDatePicker>
                    </div>
                    <div class="column is-6 pb-0">
                        <h1>Tanggal Asesmen Awal</h1>
                        <VDatePicker v-model="input.DTanggalAsesmenAwal" mode="dateTime" trim-weeks
                            :max-date="new Date()">
                            <template #default="{ inputValue, inputEvents }">
                                <VControl icon="feather:calendar" fullwidth>
                                    <VInput :value="inputValue" placeholder="Tanggal asesmen awal..."
                                        v-on="inputEvents" />
                                </VControl>
                            </template>
                        </VDatePicker>
                    </div>
                </div>

                <div class="columns is-multiline column pt-0 pb-0">
                    <div class="column is-4">
                        <h1>Rujukan</h1>
                        <Multiselect v-model="input.SRujukan" :attrs="{ value }" placeholder="--Pilih--" label="label"
                            :options="d_rujukan" :searchable="true" track-by="label" mode="single" autocomplete="off">
                        </Multiselect>
                    </div>
                    <div class="column is-4" v-if="input.SRujukan == 1">
                        <h1>Dari :</h1>
                        <Multiselect v-model="input.STempatRujukan" :attrs="{ value }" placeholder="--Pilih--"
                            label="label" :options="d_tempatRujukan" :searchable="true" track-by="label" mode="single"
                            autocomplete="off">
                        </Multiselect>
                    </div>
                    <div class="column is-4" v-if="input.SRujukan == 4">
                        <h1>&nbsp;</h1>
                        <VControl>
                            <VInput type="text" class="input" v-model="input.TBDiantar" placeholder="Diantar oleh..." />
                        </VControl>
                    </div>
                    <div class="column is-4" v-if="input.STempatRujukan == 1 && input.SRujukan == 1">
                        <h1>Rumah Sakit</h1>
                        <VControl>
                            <VInput type="text" class="input" placeholder="Rumah sakit..." v-model="input.TBRujuk_RS" />
                        </VControl>
                    </div>
                    <div class="column is-4" v-if="input.STempatRujukan == 2 && input.SRujukan == 1">
                        <h1>Puskesmas</h1>
                        <VControl>
                            <VInput type="text" class="input" placeholder="Puskesmas..."
                                v-model="input.TBRujuk_Puskesmas" />
                        </VControl>
                    </div>
                    <div class="column is-4" v-if="input.STempatRujukan == 3 && input.SRujukan == 1">
                        <h1>dr.</h1>
                        <VControl>
                            <VInput type="text" class="input" placeholder="dr..." v-model="input.TBRujuk_dr" />
                        </VControl>
                    </div>
                    <div class="column is-4" v-if="input.STempatRujukan == 4 && input.SRujukan == 1">
                        <h1>Lainnya</h1>
                        <VControl>
                            <VInput type="text" class="input" placeholder="Lainnya..."
                                v-model="input.TBRujuk_Lainnya" />
                        </VControl>
                    </div>
                    <div class="column is-4">
                        <h1>Dx.rujukan</h1>
                        <VControl>
                            <VInput type="text" class="input" v-model="input.TBDx_rujukan" />
                        </VControl>
                    </div>
                    <div class="column is-4">
                        <h1>Alloanamnesis</h1>
                        <VField class="is-autocomplete-select" v-slot="{ id }">
                            <VControl icon="feather:search">
                                <Multiselect v-model="input.kebpilihanallo" :attrs="{ value }" placeholder="--Pilih--"
                                    label="label" :options="d_allo" :searchable="true" track-by="label" mode="single"
                                    autocomplete="off">
                                </Multiselect>
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-4" v-if="input.kebpilihanallo == 4">
                        <h1>Lainnya</h1>
                        <VControl>
                            <VInput type="text" class="input" v-model="input.TBLainnya_Allo" placeholder="Lainnya..." />
                        </VControl>
                    </div>
                </div>

                <div class="column pt-0 pb-0">
                    <hr style="border-top: 1px dashed red;background-color:white" class="mt-0 mb-3">
                </div>

                <div class="column pt-0">
                    <div class="columns">
                        <div class="column is-6 is-flex" style="align-items:center">
                            <h1 style="font-size:large">Anamnesis</h1>
                        </div>
                        <div class="column is-6" align="right">
                            <VButton type="button" rounded outlined color="info" icon="feather:link" isLoading="false"
                                @click="batasNormal()"> Batas Normal
                            </VButton>
                        </div>
                    </div>
                </div>

                <div class="column pt-0">
                    <div class="columns is-multiline">
                        <div class="column is-12 pb-0">
                            <h1>1. Riwayat penyakit sebelumnya :</h1>
                        </div>
                        <div class="column is-3">
                            <h1>a. Hipertensi</h1>
                            <Multiselect v-model="input.SHipertensi" :attrs="{ value }" placeholder="--Pilih--"
                                label="label" :options="d_tidakada_ada" :searchable="true" track-by="label"
                                mode="single" autocomplete="off">
                            </Multiselect>
                        </div>
                        <div class="column is-3" v-if="input.SHipertensi == 2">
                            <h1>Jelaskan</h1>
                            <VControl>
                                <VInput type="text" v-model="input.TBHipertensi_RPS" />
                            </VControl>
                        </div>
                        <div class="column is-3" v-else></div>
                        <div class="column is-3">
                            <h1>b. Diabetes melitus</h1>
                            <Multiselect v-model="input.SDiabetesMelitus" :attrs="{ value }" placeholder="--Pilih--"
                                label="label" :options="d_tidakada_ada" :searchable="true" track-by="label"
                                mode="single" autocomplete="off">
                            </Multiselect>
                        </div>
                        <div class="column is-3" v-if="input.SDiabetesMelitus == 2">
                            <h1>Jelaskan</h1>
                            <VControl>
                                <VInput type="text" v-model="input.TBDiabetesMelitus_RPS" />
                            </VControl>
                        </div>
                        <div class="column is-3" v-else></div>
                        <div class="column is-3 pt-1">
                            <h1>c. Penyakit Ginjal</h1>
                            <Multiselect v-model="input.SPenyakitGinjal" :attrs="{ value }" placeholder="--Pilih--"
                                label="label" :options="d_tidakada_ada" :searchable="true" track-by="label"
                                mode="single" autocomplete="off">
                            </Multiselect>
                        </div>
                        <div class="column is-3 pt-1" v-if="input.SPenyakitGinjal == 2">
                            <h1>Jelaskan</h1>
                            <VControl>
                                <VInput type="text" v-model="input.TBPenyakitGinjal_RPS" />
                            </VControl>
                        </div>
                        <div class="column is-3 pt-1" v-else></div>
                        <div class="column is-3 pt-1">
                            <h1>d. Penyakit Jantung</h1>
                            <Multiselect v-model="input.SPenyakitJantung" :attrs="{ value }" placeholder="--Pilih--"
                                label="label" :options="d_tidakada_ada" :searchable="true" track-by="label"
                                mode="single" autocomplete="off">
                            </Multiselect>
                        </div>
                        <div class="column is-3 pt-1" v-if="input.SPenyakitJantung == 2">
                            <h1>Jelaskan</h1>
                            <VControl>
                                <VInput type="text" v-model="input.TBPenyakitJantung_RPS" />
                            </VControl>
                        </div>
                        <div class="column is-3 pt-1" v-else></div>
                        <div class="column is-3 pt-1">
                            <h1>e. Asthma</h1>
                            <Multiselect v-model="input.SAsthma" :attrs="{ value }" placeholder="--Pilih--"
                                label="label" :options="d_tidakada_ada" :searchable="true" track-by="label"
                                mode="single" autocomplete="off">
                            </Multiselect>
                        </div>
                        <div class="column is-3 pt-1" v-if="input.SAsthma == 2">
                            <h1>Jelaskan</h1>
                            <VControl>
                                <VInput type="text" v-model="input.TBAsthma_RPS" />
                            </VControl>
                        </div>
                        <div class="column is-3 pt-1" v-else></div>
                        <div class="column is-3 pt-1">
                            <h1>f. Trauma/Fraktur</h1>
                            <Multiselect v-model="input.STraumaFraktur" :attrs="{ value }" placeholder="--Pilih--"
                                label="label" :options="d_tidakada_ada" :searchable="true" track-by="label"
                                mode="single" autocomplete="off">
                            </Multiselect>
                        </div>
                        <div class="column is-3 pt-1" v-if="input.STraumaFraktur == 2">
                            <h1>Jelaskan</h1>
                            <VControl>
                                <VInput type="text" v-model="input.TBTraumaFraktur_RPS" />
                            </VControl>
                        </div>
                        <div class="column is-3 pt-1" v-else></div>
                        <div class="column is-3 pt-1">
                            <h1>g. Kejang</h1>
                            <Multiselect v-model="input.SKejang" :attrs="{ value }" placeholder="--Pilih--"
                                label="label" :options="d_tidakada_ada" :searchable="true" track-by="label"
                                mode="single" autocomplete="off">
                            </Multiselect>
                        </div>
                        <div class="column is-3 pt-1" v-if="input.SKejang == 2">
                            <h1>Jelaskan</h1>
                            <VControl>
                                <VInput type="text" v-model="input.TBKejang_RPS" />
                            </VControl>
                        </div>
                        <div class="column is-3 pt-1" v-else></div>
                        <div class="column is-12 pt-0 pb-0">
                            <hr style="border-top: 1px dashed lightgray;background-color:white" class="mt-0 mb-1">
                        </div>
                        <div class="column is-4">
                            <h1>2. Riwayat operasi/biopsy sebelumnya : </h1>
                            <Multiselect v-model="input.SRiwayatOperasi" :attrs="{ value }" placeholder="--Pilih--"
                                label="label" :options="d_tidakada_ada" :searchable="true" track-by="label"
                                mode="single" autocomplete="off">
                            </Multiselect>
                        </div>
                        <div class="column is-2" v-if="input.SRiwayatOperasi == 2">
                            <h1>Jelaskan</h1>
                            <VControl>
                                <VInput type="text" v-model="input.TBRiwayatOperasi" />
                            </VControl>
                        </div>
                        <div class="column is-2" v-else></div>
                        <div class="column is-4">
                            <h1>3. Riwayat pengobatan terapi sistemik kanker : </h1>
                            <Multiselect v-model="input.SRiwayatPengobatanKanker" :attrs="{ value }"
                                placeholder="--Pilih--" label="label" :options="d_tidakada_ada" :searchable="true"
                                track-by="label" mode="single" autocomplete="off">
                            </Multiselect>
                        </div>
                        <div class="column is-2" v-if="input.SRiwayatPengobatanKanker == 2">
                            <h1>Jelaskan</h1>
                            <VControl>
                                <VInput type="text" v-model="input.TBRiwayatPengobatanKanker" />
                            </VControl>
                        </div>
                        <div class="column is-2" v-else></div>
                        <div class="column is-4 pt-1">
                            <h1>4. Riwayat terapi radiasi sebelumnya : </h1>
                            <Multiselect v-model="input.SRiwayatTerapiRadiasi" :attrs="{ value }"
                                placeholder="--Pilih--" label="label" :options="d_tidakada_ada" :searchable="true"
                                track-by="label" mode="single" autocomplete="off">
                            </Multiselect>
                        </div>
                        <div class="column is-2" v-if="input.SRiwayatTerapiRadiasi == 2">
                            <h1>Jelaskan</h1>
                            <VControl>
                                <VInput type="text" v-model="input.TBRiwayatTerapiRadiasi" />
                            </VControl>
                        </div>
                        <div class="column is-2" v-else></div>
                        <div class="column is-4 pt-1">
                            <h1>5. Riwayat pemeriksaan dengan zat kontras : </h1>
                            <Multiselect v-model="input.SRiwayatPemeriksaanZatKontras" :attrs="{ value }"
                                placeholder="--Pilih--" label="label" :options="d_tidakada_ada" :searchable="true"
                                track-by="label" mode="single" autocomplete="off">
                            </Multiselect>
                        </div>
                        <div class="column is-2" v-if="input.SRiwayatPemeriksaanZatKontras == 2">
                            <h1>Jelaskan</h1>
                            <VControl>
                                <VInput type="text" v-model="input.TBRiwayatPemeriksaanZatKontras" />
                            </VControl>
                        </div>
                        <div class="column is-2" v-else></div>
                        <div class="column is-4 pt-1">
                            <h1>6. Riwayat claustrophobia : </h1>
                            <Multiselect v-model="input.SRiwayatClaustrophobia" :attrs="{ value }"
                                placeholder="--Pilih--" label="label" :options="d_tidakada_ada" :searchable="true"
                                track-by="label" mode="single" autocomplete="off">
                            </Multiselect>
                        </div>
                        <div class="column is-2" v-if="input.SRiwayatClaustrophobia == 2">
                            <h1>Jelaskan</h1>
                            <VControl>
                                <VInput type="text" v-model="input.TBRiwayatClaustrophobia" />
                            </VControl>
                        </div>
                        <div class="column is-2" v-else></div>
                        <div class="column is-4 pt-1">
                            <h1>7. Riwayat alergi : </h1>
                            <Multiselect v-model="input.SRiwayatAlergi" :attrs="{ value }" placeholder="--Pilih--"
                                label="label" :options="d_tidakada_ada" :searchable="true" track-by="label"
                                mode="single" autocomplete="off">
                            </Multiselect>
                        </div>
                        <div class="column is-2" v-if="input.SRiwayatAlergi == 2">
                            <h1>Jelaskan</h1>
                            <VControl>
                                <VInput type="text" v-model="input.TBRiwayatAlergi" />
                            </VControl>
                        </div>
                        <div class="column is-2" v-else></div>
                        <div class="column is-12 pt-1">
                            <h1>8. Keluhan saat ini : </h1>
                            <VControl>
                                <VInput type="text" v-model="input.TBKeluhanSaatIni" />
                            </VControl>
                        </div>
                        <div class="column is-12 pt-0 pb-0">
                            <hr style="border-top: 1px dashed lightgray;background-color:white" class="mt-0 mb-1">
                        </div>
                        <div class="column is-12 pb-0">
                            <h1>9. Riwayat kesehatan saat ini : </h1>
                            <VControl>
                                <VInput type="text" v-model="input.TBRiwayatKesehatanSaatIni" />
                            </VControl>
                        </div>
                        <div class="column is-4">
                            <h1>a. Adakah obat rutin yang sedang dikonsumsi</h1>
                            <Multiselect v-model="input.SObatRutinSedangDikonsumsi" :attrs="{ value }"
                                placeholder="--Pilih--" label="label" :options="d_tidakada_ada" :searchable="true"
                                track-by="label" mode="single" autocomplete="off">
                            </Multiselect>
                        </div>
                        <div class="column is-2" v-if="input.SObatRutinSedangDikonsumsi == 2">
                            <h1>Jelaskan</h1>
                            <VControl>
                                <VInput type="text" v-model="input.TBObatRutinSedangDikonsumsi" />
                            </VControl>
                        </div>
                        <div class="column is-2" v-else></div>
                        <div class="column is-4">
                            <h1>b. Apakah bisa posisi berbaring selama pemeriksaan</h1>
                            <Multiselect v-model="input.SPosisiBaringSelamaPemeriksaan" :attrs="{ value }"
                                placeholder="--Pilih--" label="label" :options="d_tidakada_ada" :searchable="true"
                                track-by="label" mode="single" autocomplete="off">
                            </Multiselect>
                        </div>
                        <div class="column is-2" v-if="input.SPosisiBaringSelamaPemeriksaan == 2">
                            <h1>Jelaskan</h1>
                            <VControl>
                                <VInput type="text" v-model="input.TBPosisiBaringSelamaPemeriksaan" />
                            </VControl>
                        </div>
                        <div class="column is-2" v-else></div>
                        <div class="column is-4">
                            <h1>c. Apakah ada gangguan BAK</h1>
                            <Multiselect v-model="input.SGangguanBAK" :attrs="{ value }" placeholder="--Pilih--"
                                label="label" :options="d_tidakada_ada" :searchable="true" track-by="label"
                                mode="single" autocomplete="off">
                            </Multiselect>
                        </div>
                        <div class="column is-2" v-if="input.SGangguanBAK == 2">
                            <h1>Jelaskan</h1>
                            <VControl>
                                <VInput type="text" v-model="input.TBGangguanBAK" />
                            </VControl>
                        </div>
                        <div class="column is-2" v-else></div>
                        <div class="column is-4">
                            <h1>d. Apakah terpasang urine bag atau colostomy bag</h1>
                            <Multiselect v-model="input.STerpasangUrineBag" :attrs="{ value }" placeholder="--Pilih--"
                                label="label" :options="d_tidakada_ada" :searchable="true" track-by="label"
                                mode="single" autocomplete="off">
                            </Multiselect>
                        </div>
                        <div class="column is-2" v-if="input.STerpasangUrineBag == 2">
                            <h1>Jelaskan</h1>
                            <VControl>
                                <VInput type="text" v-model="input.TBTerpasangUrineBag" />
                            </VControl>
                        </div>
                        <div class="column is-2" v-else></div>
                        <div class="column is-4">
                            <h1>e. Apakah pasien (wanita) sedang hamil dan menyusui</h1>
                            <Multiselect v-model="input.SSedangHamilMenyusui" :attrs="{ value }" placeholder="--Pilih--"
                                label="label" :options="d_tidakada_ada" :searchable="true" track-by="label"
                                mode="single" autocomplete="off">
                            </Multiselect>
                        </div>
                        <div class="column is-2" v-if="input.SSedangHamilMenyusui == 2">
                            <h1>Jelaskan</h1>
                            <VControl>
                                <VInput type="text" v-model="input.TBSedangHamilMenyusui" />
                            </VControl>
                        </div>
                        <div class="column is-2" v-else></div>
                        <div class="column is-4">
                            <h1>f. Apakah pasien (wanita) menstruasi</h1>
                            <Multiselect v-model="input.SMenstruasi" :attrs="{ value }" placeholder="--Pilih--"
                                label="label" :options="d_tidakada_ada" :searchable="true" track-by="label"
                                mode="single" autocomplete="off">
                            </Multiselect>
                        </div>
                        <div class="column is-2" v-if="input.SMenstruasi == 2">
                            <h1>Jelaskan</h1>
                            <VControl>
                                <VInput type="text" v-model="input.TBMenstruasi" />
                            </VControl>
                        </div>
                        <div class="column is-2" v-else></div>
                        <div class="column is-4">
                            <h1>g. Apakah pasien (wanita) sedang hamil</h1>
                            <Multiselect v-model="input.SHamil" :attrs="{ value }" placeholder="--Pilih--" label="label"
                                :options="d_tidakada_ada" :searchable="true" track-by="label" mode="single"
                                autocomplete="off">
                            </Multiselect>
                        </div>
                        <div class="column is-2" v-if="input.SHamil == 2">
                            <h1>Jelaskan</h1>
                            <VControl>
                                <VInput type="text" v-model="input.TBHamil" />
                            </VControl>
                        </div>
                        <div class="column is-2" v-else></div>
                        <div class="column is-6"></div>
                        <div class="column is-6">
                            <h1>h. Jam terakhir pasien makan dan konsumsi gula</h1>
                            <VDatePicker v-model="input.TJamTerakhirMakan" mode="time" is24hr>
                                <template #default="{ inputValue, inputEvents }">
                                    <VControl icon="feather:clock" fullwidth>
                                        <VInput :value="inputValue" v-on="inputEvents" />
                                    </VControl>
                                </template>
                            </VDatePicker>
                        </div>
                        <div class="column is-6">
                            <h1>i. Jam pengambilan pemeriksaan gula darah sewaktu</h1>
                            <VDatePicker v-model="input.TJamPengambilanPemeriksaanGulaDarah" mode="time" is24hr>
                                <template #default="{ inputValue, inputEvents }">
                                    <VControl icon="feather:clock" fullwidth>
                                        <VInput :value="inputValue" v-on="inputEvents" />
                                    </VControl>
                                </template>
                            </VDatePicker>
                        </div>
                    </div>
                </div>

                <div class="column pt-0 pb-0">
                    <hr style="border-top: 1px dashed red;background-color:white" class="mt-0 mb-1">
                </div>

                <div class="column pt-0 pb-0">
                    <h1 style="font-size:large">Status Generalis/Pemeriksaan Umum</h1>
                </div>

                <div class="column">
                    <div class="columns is-multiline">
                        <div class="column is-12 pb-0">
                            <h1>1. Kesadaran/mental :</h1>
                        </div>
                        <div class="column is-4 pb-0">
                            <h1>&nbsp;</h1>
                            <Multiselect v-model="input.SKesadaranMental" :attrs="{ value }" placeholder="--Pilih--"
                                label="label" :options="d_kesadaranMental" :searchable="true" track-by="label"
                                mode="single" autocomplete="off">
                            </Multiselect>
                        </div>
                        <div class="column is-3 pb-0">
                            <h1>Keadaan Umum</h1>
                            <Multiselect v-model="input.SKeadaanUmum" :attrs="{ value }" placeholder="--Pilih--"
                                label="label" :options="d_keadaanumum" :searchable="true" track-by="label" mode="single"
                                autocomplete="off">
                            </Multiselect>
                        </div>
                        <div class="column is-5 pb-0">
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
                        <div class="column is-2">
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
                        <div class="column is-2">
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
                        <div class="column is-2">
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
                        <div class="column is-2">
                            <h1>Pernapasan</h1>
                            <VField addons>
                                <VControl>
                                    <VInput type="text" class="input" v-model="input.TBPernafasanTTV" />
                                </VControl>
                                <VControl class="field-addon-body">
                                    <VButton static>x/mnt</VButton>
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-2">
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
                        <div class="column is-2">
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
                        <div class="column is-6 pt-0">
                            <h1>Skala Kamosky</h1>
                            <VControl>
                                <VInput type="text" class="input" v-model="input.TBSkalaKamosky" />
                            </VControl>
                        </div>
                        <div class="column is-6 pt-0">
                            <h1>Skala VAS</h1>
                            <VControl>
                                <VInput type="text" class="input" v-model="input.TBSkalaVAS" />
                            </VControl>
                        </div>
                        <div class="column is-12 pb-0">
                            <h1>2. Thorax :</h1>
                        </div>
                        <div class="column is-6 pb-0">
                            <h1>Perkusi, kanan</h1>
                            <Multiselect v-model="input.SPerkusiKanan" :attrs="{ value }" placeholder="--Pilih--"
                                label="label" :options="d_normal_tidak" :searchable="true" track-by="label"
                                mode="single" autocomplete="off">
                            </Multiselect>
                        </div>
                        <div class="column is-6 pb-0">
                            <h1>Perkusi, kiri</h1>
                            <Multiselect v-model="input.SPerkusiKiri" :attrs="{ value }" placeholder="--Pilih--"
                                label="label" :options="d_normal_tidak" :searchable="true" track-by="label"
                                mode="single" autocomplete="off">
                            </Multiselect>
                        </div>
                        <div class="column is-6">
                            <h1>Auskultasi, kanan</h1>
                            <Multiselect v-model="input.SAuskultasiKanan" :attrs="{ value }" placeholder="--Pilih--"
                                label="label" :options="d_normal_tidak" :searchable="true" track-by="label"
                                mode="single" autocomplete="off">
                            </Multiselect>
                        </div>
                        <div class="column is-6">
                            <h1>Auskultasi, kiri</h1>
                            <Multiselect v-model="input.SAuskultasiKiri" :attrs="{ value }" placeholder="--Pilih--"
                                label="label" :options="d_normal_tidak" :searchable="true" track-by="label"
                                mode="single" autocomplete="off">
                            </Multiselect>
                        </div>
                        <div class="column is-12 pb-0">
                            <h1>3. Jantung :</h1>
                        </div>
                        <div class="column is-6">
                            <h1>Bunyi jantung</h1>
                            <Multiselect v-model="input.SBunyiJantung" :attrs="{ value }" placeholder="--Pilih--"
                                label="label" :options="d_bunyijantung" :searchable="true" track-by="label"
                                mode="single" autocomplete="off">
                            </Multiselect>
                        </div>
                        <div class="column is-6">
                            <h1>Lain-lain</h1>
                            <VField>
                                <VTextarea rows="2" v-model="input.TBLainLain_SG"></VTextarea>
                            </VField>
                        </div>
                    </div>
                </div>

                <div class="column pt-0 pb-0">
                    <hr style="border-top: 1px dashed red;background-color:white" class="mt-0 mb-1">
                </div>

                <div class="column is-12">
                    <div class="column is-12 pt-0 is-flex" style="justify-content:center">
                        <ImgDraw elemenID="GambarTubuh" height="460" width="700"
                            imageSrc="/images/simrs/outline-human-body.jpg" />
                    </div>
                    <div class="column is-12 pt-0">
                        <VField>
                            <VTextarea rows="2" v-model="input.TAStatusLokalis"
                                placeholder="Keterangan status lokalis...">
                            </VTextarea>
                        </VField>
                    </div>
                </div>

                <div class="column pt-0 pb-0">
                    <hr style="border-top: 1px dashed red;background-color:white" class="mt-0 mb-1">
                </div>

                <div class="column pt-0 pb-0">
                    <h1 style="font-size:large">Hasil Pemeriksaan Penunjang</h1>
                </div>

                <div class="column is-12">
                    <div class="columns is-multiline">
                        <div class="column is-5">
                            <h1>Patologi anatomi</h1>
                            <VField>
                                <VControl>
                                    <VTextarea v-model="input.patologianatomi" rows="3">
                                    </VTextarea>
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-2">
                            <h1>Tanggal</h1>
                            <VField>
                                <VDatePicker v-model="input.tglpatologianatomi" mode="dateTime" style="width: 100%"
                                    trim-weeks :max-date="new Date()">
                                    <template #default="{ inputValue, inputEvents }">
                                        <VField>
                                            <VControl icon="feather:calendar" fullwidth>
                                                <VInput :value="inputValue" placeholder="Tanggal" v-on="inputEvents" />
                                            </VControl>
                                        </VField>
                                    </template>
                                </VDatePicker>
                            </VField>
                        </div>
                        <div class="column is-5">
                            <h1>Hasil</h1>
                            <VField>
                                <VControl>
                                    <VTextarea v-model="input.hasilpatologianatomi" rows="3">
                                    </VTextarea>
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-5">
                            <h1>Laboratorium</h1>
                            <VField>
                                <VControl>
                                    <VTextarea v-model="input.laboratorium" rows="3">
                                    </VTextarea>
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-2">
                            <h1>Tanggal</h1>
                            <VField>
                                <VDatePicker v-model="input.tgllaboratorium" mode="dateTime" style="width: 100%"
                                    trim-weeks :max-date="new Date()">
                                    <template #default="{ inputValue, inputEvents }">
                                        <VField>
                                            <VControl icon="feather:calendar" fullwidth>
                                                <VInput :value="inputValue" placeholder="Tanggal" v-on="inputEvents" />
                                            </VControl>
                                        </VField>
                                    </template>
                                </VDatePicker>
                            </VField>
                        </div>
                        <div class="column is-5">
                            <h1>Hasil</h1>
                            <VField>
                                <VControl>
                                    <VTextarea v-model="input.hasillaboratorium" rows="3">
                                    </VTextarea>
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-5">
                            <h1>Radiologi</h1>
                            <VField>
                                <VControl>
                                    <VTextarea v-model="input.radiologi" rows="3">
                                    </VTextarea>
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-2">
                            <h1>Tanggal</h1>
                            <VField>
                                <VDatePicker v-model="input.tglradiologi" mode="dateTime" style="width: 100%" trim-weeks
                                    :max-date="new Date()">
                                    <template #default="{ inputValue, inputEvents }">
                                        <VField>
                                            <VControl icon="feather:calendar" fullwidth>
                                                <VInput :value="inputValue" placeholder="Tanggal" v-on="inputEvents" />
                                            </VControl>
                                        </VField>
                                    </template>
                                </VDatePicker>
                            </VField>
                        </div>
                        <div class="column is-5">
                            <h1>Hasil</h1>
                            <VField>
                                <VControl>
                                    <VTextarea v-model="input.hasilradiologi" rows="3">
                                    </VTextarea>
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-5">
                            <h1>Lain-lain</h1>
                            <VField>
                                <VControl>
                                    <VTextarea v-model="input.lainnya" rows="3">
                                    </VTextarea>
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-2">
                            <h1>Tanggal</h1>
                            <VField>
                                <VDatePicker v-model="input.tgllainnya" mode="dateTime" style="width: 100%" trim-weeks
                                    :max-date="new Date()">
                                    <template #default="{ inputValue, inputEvents }">
                                        <VField>
                                            <VControl icon="feather:calendar" fullwidth>
                                                <VInput :value="inputValue" placeholder="Tanggal" v-on="inputEvents" />
                                            </VControl>
                                        </VField>
                                    </template>
                                </VDatePicker>
                            </VField>
                        </div>
                        <div class="column is-5">
                            <h1>Hasil</h1>
                            <VField>
                                <VControl>
                                    <VTextarea v-model="input.hasillainnya" rows="3">
                                    </VTextarea>
                                </VControl>
                            </VField>
                        </div>
                    </div>
                </div>

                <div class="column pt-0 pb-0">
                    <hr style="border-top: 1px dashed red;background-color:white" class="mt-0 mb-1">
                </div>

                <div class="column is-12">
                    <h1>Diagnosa</h1>
                    <VField>
                        <VTextarea v-model="input.TADiagnosa" rows="3">
                        </VTextarea>
                    </VField>
                </div>

                <div class="column pt-0 pb-0">
                    <hr style="border-top: 1px dashed red;background-color:white" class="mt-0 mb-1">
                </div>

                <div class="column pt-0 pb-0">
                    <h1 style="font-size:large">Rencana Kerja Dokter</h1>
                </div>

                <div class="column is-12">
                    <div class="columns is-multiline">
                        <div class="column is-4">
                            <h1>Daftar Masalah</h1>
                            <VField>
                                <VControl>
                                    <VTextarea v-model="input.daftarmasalah" rows="3">
                                    </VTextarea>
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-4">
                            <h1>Rencana Intervensi</h1>
                            <VField>
                                <VControl>
                                    <VTextarea v-model="input.rencanaintervensi" rows="3">
                                    </VTextarea>
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-4">
                            <h1>Traget (kondisi yang diharapkan dan waktu)</h1>
                            <VField>
                                <VControl>
                                    <VTextarea v-model="input.target" rows="3">
                                    </VTextarea>
                                </VControl>
                            </VField>
                        </div>
                    </div>
                </div>

                <div class="column pt-0 pb-0">
                    <hr style="border-top: 1px dashed red;background-color:white" class="mt-0 mb-1">
                </div>

                <div class="column pt-0 pb-0">
                    <h1 style="font-size:large">Intruksi</h1>
                </div>

                <div class="column is-12">
                    <div class="columns is-multiline">
                        <div class="column is-12">
                            <h1>1. Jenis pemeriksaan</h1>
                            <VField>
                                <VTextarea rows="2" v-model="input.TAJenisPemeriksaan"></VTextarea>
                            </VField>
                        </div>
                        <div class="column is-12 pt-0 pb-0">
                            <hr style="border-top: 1px dashed lightgray;background-color:white" class="mt-0 mb-1">
                        </div>
                        <div class="column is-12 pb-0">
                            <h1>2. Tindakan</h1>
                        </div>
                        <div class="column is-12">
                            <h1>a. Pemberian radiofarmaka</h1>
                        </div>
                        <div class="column is-12 pt-0">
                            <h1>1. Dosis yang diberikan</h1>
                            <div class="columns is-multiline">
                                <div class="column is-6">
                                    <VControl raw subcontrol>
                                        <VCheckbox class="p-0" color="primary" square true-value="I-131" label="I-131"
                                            v-model="input.CB131" />
                                    </VControl>
                                    <VField>
                                        <VTextarea rows="1" v-model="input.TA131"></VTextarea>
                                    </VField>
                                </div>
                                <div class="column is-6">
                                    <VControl raw subcontrol>
                                        <VCheckbox class="p-0" color="primary" square true-value="MDP" label="MDP"
                                            v-model="input.CBMDP" />
                                    </VControl>
                                    <VField>
                                        <VTextarea rows="1" v-model="input.TAMDP"></VTextarea>
                                    </VField>
                                </div>
                                <div class="column is-6 pt-1">
                                    <VControl raw subcontrol>
                                        <VCheckbox class="p-0" color="primary" square true-value="DTPA" label="DTPA"
                                            v-model="input.CBDTPA" />
                                    </VControl>
                                    <VField>
                                        <VTextarea rows="1" v-model="input.TADTPA"></VTextarea>
                                    </VField>
                                </div>
                                <div class="column is-6 pt-1">
                                    <VControl raw subcontrol>
                                        <VCheckbox class="p-0" color="primary" square true-value="FDG" label="FDG"
                                            v-model="input.CBFDG" />
                                    </VControl>
                                    <VField>
                                        <VTextarea rows="1" v-model="input.TAFDG"></VTextarea>
                                    </VField>
                                </div>
                                <div class="column is-12 pt-1">
                                    <VControl raw subcontrol>
                                        <VCheckbox class="p-0" color="primary" square true-value="Lainnya"
                                            label="Lainnya" v-model="input.CBLainnya" />
                                    </VControl>
                                    <VField>
                                        <VTextarea rows="1" v-model="input.TALainnya_DYD"></VTextarea>
                                    </VField>
                                </div>
                            </div>
                        </div>
                        <div class="column is-12 pt-0">
                            <h1>2. Cara pemberian</h1>
                            <div class="columns is-multiline">
                                <div class="column is-3">
                                    <VControl raw subcontrol>
                                        <VCheckbox class="p-0" color="primary" square true-value="Oral" label="Oral"
                                            v-model="input.CBOral" />
                                    </VControl>
                                </div>
                                <div class="column is-3">
                                    <VControl raw subcontrol>
                                        <VCheckbox class="p-0" color="primary" square true-value="VI" label="VI"
                                            v-model="input.CBVI" />
                                    </VControl>
                                </div>
                                <div class="column is-6">
                                    <VControl raw subcontrol>
                                        <VCheckbox class="p-0" color="primary" square true-value="Lainnya"
                                            label="Lainnya" v-model="input.CBLainnya" />
                                    </VControl>
                                    <VField>
                                        <VTextarea rows="1" v-model="input.TALainnya"></VTextarea>
                                    </VField>
                                </div>
                            </div>
                        </div>

                        <div class="column is-12 pt-1">
                            <h1>b. Hasil pemeriksaan fungsi ginjal</h1>
                            <div class="columns">
                                <div class="column is-6">
                                    <h1>&nbsp;</h1>
                                    <Multiselect v-model="input.SFungsiGinjal" :attrs="{ value }"
                                        placeholder="--Pilih--" label="label" :options="d_tidakada_ada"
                                        :searchable="true" track-by="label" mode="single" autocomplete="off">
                                    </Multiselect>
                                </div>
                                <div class="column is-6" v-if="input.SFungsiGinjal == 2">
                                    <h1>Sebutkan hasil ureum/kreatinin</h1>
                                    <VControl>
                                        <VInput type="text" class="input" v-model="input.TBFungsiGinjal" />
                                    </VControl>
                                </div>
                            </div>
                        </div>

                        <div class="column is-12 pt-1">
                            <h1>c. Hasil pemeriksaan gula darah sewaktu</h1>
                            <div class="columns">
                                <div class="column is-6">
                                    <h1>&nbsp;</h1>
                                    <Multiselect v-model="input.SGulaDarah" :attrs="{ value }" placeholder="--Pilih--"
                                        label="label" :options="d_tidakada_ada" :searchable="true" track-by="label"
                                        mode="single" autocomplete="off">
                                    </Multiselect>
                                </div>
                                <div class="column is-6" v-if="input.SGulaDarah == 2">
                                    <h1>Sebutkan hasil GDS</h1>
                                    <VControl>
                                        <VInput type="text" class="input" v-model="input.TBGulaDarah" />
                                    </VControl>
                                </div>
                            </div>
                        </div>

                        <div class="column is-6 pt-1">
                            <h1>d. Pemasangan monitor saturasi</h1>
                            <div class="columns">
                                <div class="column is-6">
                                    <h1>&nbsp;</h1>
                                    <Multiselect v-model="input.SMonitorSaturasi" :attrs="{ value }"
                                        placeholder="--Pilih--" label="label" :options="d_tidakada_ada"
                                        :searchable="true" track-by="label" mode="single" autocomplete="off">
                                    </Multiselect>
                                </div>
                                <div class="column is-6" v-if="input.SMonitorSaturasi == 2">
                                    <h1>Sebutkan</h1>
                                    <VControl>
                                        <VInput type="text" class="input" v-model="input.TBMonitorSaturasi" />
                                    </VControl>
                                </div>
                            </div>
                        </div>

                        <div class="column is-6 pt-1">
                            <h1>e. Pemberian oksigenasi</h1>
                            <div class="columns">
                                <div class="column is-6">
                                    <h1>&nbsp;</h1>
                                    <Multiselect v-model="input.SPemberianOksigenasi" :attrs="{ value }"
                                        placeholder="--Pilih--" label="label" :options="d_tidakada_ada"
                                        :searchable="true" track-by="label" mode="single" autocomplete="off">
                                    </Multiselect>
                                </div>
                                <div class="column is-6" v-if="input.SPemberianOksigenasi == 2">
                                    <h1>Sebutkan</h1>
                                    <VControl>
                                        <VInput type="text" class="input" v-model="input.TBPemberianOksigenasi" />
                                    </VControl>
                                </div>
                            </div>
                        </div>

                        <div class="column is-6 pt-1">
                            <h1>f. Tindakan anestesi</h1>
                            <div class="columns">
                                <div class="column is-6">
                                    <h1>&nbsp;</h1>
                                    <Multiselect v-model="input.STindakanAnestesi" :attrs="{ value }"
                                        placeholder="--Pilih--" label="label" :options="d_tidakada_ada"
                                        :searchable="true" track-by="label" mode="single" autocomplete="off">
                                    </Multiselect>
                                </div>
                                <div class="column is-6" v-if="input.STindakanAnestesi == 2">
                                    <h1>Sebutkan</h1>
                                    <VControl>
                                        <VInput type="text" class="input" v-model="input.TBTindakanAnestesi" />
                                    </VControl>
                                </div>
                            </div>
                        </div>

                        <div class="column is-6 pt-1">
                            <h1>g. Diperlukan bowel preparation</h1>
                            <div class="columns">
                                <div class="column is-6">
                                    <h1>&nbsp;</h1>
                                    <Multiselect v-model="input.SDiperlukanBowel" :attrs="{ value }"
                                        placeholder="--Pilih--" label="label" :options="d_tidakada_ada"
                                        :searchable="true" track-by="label" mode="single" autocomplete="off">
                                    </Multiselect>
                                </div>
                                <div class="column is-6" v-if="input.SDiperlukanBowel == 2">
                                    <h1>Sebutkan</h1>
                                    <VControl>
                                        <VInput type="text" class="input" v-model="input.TBDiperlukanBowel" />
                                    </VControl>
                                </div>
                            </div>
                        </div>
                        <div class="column is-6 pt-1">
                            <h1>h. Diperlukan obat diuretik</h1>
                            <div class="columns">
                                <div class="column is-6">
                                    <h1>&nbsp;</h1>
                                    <Multiselect v-model="input.SObatDiuretik" :attrs="{ value }"
                                        placeholder="--Pilih--" label="label" :options="d_tidakada_ada"
                                        :searchable="true" track-by="label" mode="single" autocomplete="off">
                                    </Multiselect>
                                </div>
                                <div class="column is-6" v-if="input.SObatDiuretik == 2">
                                    <h1>Sebutkan</h1>
                                    <VControl>
                                        <VInput type="text" class="input" v-model="input.TBObatDiuretik" />
                                    </VControl>
                                </div>
                            </div>
                        </div>
                        <div class="column is-12 pt-0 pb-0"></div>
                        <div class="column is-3">
                            <h1>i. Injeksi radiofarmaka di</h1>
                            <Multiselect v-model="input.SInjeksiRadiofarmaka" :attrs="{ value }" placeholder="--Pilih--"
                                label="label" :options="d_injeksiradiofarmaka" :searchable="true" track-by="label"
                                mode="single" autocomplete="off">
                            </Multiselect>
                        </div>
                        <div class="column is-3">
                            <h1>j. Whole body scan sampai</h1>
                            <Multiselect v-model="input.SWholeBody" :attrs="{ value }" placeholder="--Pilih--"
                                label="label" :options="d_wholebody" :searchable="true" track-by="label" mode="single"
                                autocomplete="off">
                            </Multiselect>
                        </div>
                        <div class="column is-3">
                            <h1>k. Letak tangan</h1>
                            <Multiselect v-model="input.SLetakTangan" :attrs="{ value }" placeholder="--Pilih--"
                                label="label" :options="d_letaktangan" :searchable="true" track-by="label" mode="single"
                                autocomplete="off">
                            </Multiselect>
                        </div>
                        <div class="column is-3">
                            <h1>l. Dimulai dari</h1>
                            <Multiselect v-model="input.SDimulaiDari" :attrs="{ value }" placeholder="--Pilih--"
                                label="label" :options="d_dimulaidari" :searchable="true" track-by="label" mode="single"
                                autocomplete="off">
                            </Multiselect>
                        </div>
                    </div>
                </div>

                <div class="column pt-0 pb-0">
                    <hr style="border-top: 1px dashed red;background-color:white" class="mt-0 mb-1">
                </div>

                <div class="column is-4" style="margin-left: auto;text-align: center;">
                    <VCard class="border-card">
                        <div class="column">
                            <h1>Dokter</h1>
                        </div>
                        <div class="column">
                            <TandaTangan :elemenID="'TTDDokter'" :width="'150'" :height="'150'" class="dek" />
                        </div>
                        <div class="column">
                            <VField class="is-autocomplete-select" v-slot="{ id }">
                                <VControl icon="feather:search">
                                    <AutoComplete v-model="input.CBDokter" :suggestions="d_Dokter"
                                        @complete="fetchDokter($event)" :optionLabel="'label'" :dropdown="true"
                                        :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'" />
                                </VControl>
                            </VField>
                        </div>
                    </VCard>

                </div>
            </div>

        </div>
    </div>
</template>

<script setup lang="ts">
import { useWindowScroll } from '@vueuse/core'
import { useApi } from '/@src/composable/useApi'
import { h, reactive, ref, computed, defineComponent, watch, onMounted, onBeforeMount } from 'vue'
import { useRoute, useRouter, onBeforeRouteLeave } from 'vue-router'
import { useHead } from '@vueuse/head'
import * as H from '/@src/utils/appHelper'
import AutoComplete from 'primevue/autocomplete';
import ButtonEmr from '../page-emr-plugins/button-emr.vue'
import TandaTangan from '../page-emr-plugins/tanda-tangan.vue'
import ImgDraw from '../page-emr-plugins/img-draw.vue'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useUserSession } from '/@src/stores/userSession'

const d_tidakada_ada: any = ref([{ value: 1, label: 'Tidak ada' }, { value: 2, label: 'Ada' }]) //type 1
const d_tidakada_ya: any = ref([{ value: 1, label: 'Tidak ada' }, { value: 2, label: 'Ya' }]) //type 2
const d_rujukan: any = ref([{ value: 1, label: 'Ya' }, { value: 2, label: 'Tidak' }, { value: 3, label: 'Datang Sendiri' }, { value: 4, label: 'Diantar' }])
const d_allo: any = ref([{ value: 1, label: 'Suami/Istri' }, { value: 2, label: 'Orang Tua' }, { value: 3, label: 'Anak' }, { value: 4, label: 'Lainnya' }])
const d_keadaanumum: any = ref([{ value: 1, label: 'Baik' }, { value: 2, label: 'Sedang' }, { value: 3, label: 'Lemah' }, { value: 4, label: 'Jelek' }])
const d_kesadaranMental: any = ref(([
    { value: 1, label: 'Compas mentis' },
    { value: 2, label: 'Delirium' },
    { value: 3, label: 'Somnolen' }
]))
const d_bunyijantung: any = ref([{ value: 1, label: 'Normal' }, { value: 2, label: 'Tidak' }])
const d_normal_tidak: any = ref([{ value: 1, label: 'Normal' }, { value: 2, label: 'Tidak' }])
const d_injeksiradiofarmaka: any = ref([
    { value: 1, label: 'Tangan Kanan' },
    { value: 2, label: 'Tangan Kiri' },
    { value: 3, label: 'Kaki Kanan' },
    { value: 4, label: 'Kaki Kiri' }
])
const d_wholebody: any = ref([
    { value: 1, label: 'Atas Lutut' },
    { value: 2, label: 'Bawah Lutut' },
    { value: 3, label: 'Ujung Kaki' }
])
const d_letaktangan: any = ref([
    { value: 1, label: 'Di Atas' },
    { value: 2, label: 'Samping' }
])
const d_dimulaidari: any = ref([
    { value: 1, label: 'Inferior' },
    { value: 2, label: 'Superior' }
])
const isResumeMedis: any = ref(false);
let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pd as string
let norec_emr = useRoute().query.norec_emr as string
const props = withDefaults(
    defineProps<{
        pasien?: any
        registrasi?: any
        FORM_NAME?: string
        FORM_URL?: string
    }>(),
    {
        pasien: {},
        registrasi: {},
        FORM_NAME: '',
        FORM_URL: '',
    }
)
const user = useUserSession().getUser().pegawai;
const route = useRoute()
const { y } = useWindowScroll()
const isStuck = computed(() => { return y.value > 30 })
const isLoading: any = ref(false)
const d_Ruangan: any = ref([])
const item: any = reactive({
    NOREC_PD: NOREC_PD != undefined ? NOREC_PD : '',
    NOREC_APD: '',
    registrasi: {},
    pegawaiOrder: useUserSession().getUser().id,
    selectedMenu: [false]
})
const COLLECTION: any = ref('AsesmenMedisKedokteranNuklir') //table mongodb
const NOREC_EMRPASIEN: any = ref('')
const input: any = ref({
    DTanggalMasuk: props.registrasi.tglregistrasi,
    DTanggalAsesmenAwal: new Date(),
    TJamTerakhirMakan: new Date(),
    TJamPengambilanPemeriksaanGulaDarah: new Date(),
    RBatasNormal: 'Batas Normal'
})
const d_Dokter = ref([])
const dataTTD: any = ref([])
const setView = () => {
    useHead({ title: props.FORM_NAME + ' - ' + import.meta.env.VITE_PROJECT })
    useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
    useViewWrapper().setFullWidth(true)
}
const loadGambar = async (element_id: string, value: string) => {
    let sigCanvas: any = document.getElementById(element_id);
    if (sigCanvas) {
        let context = sigCanvas.getContext("2d");
        context.clearRect(0, 0, sigCanvas.width, sigCanvas.height);
        let imagess = value
        let background = new Image();
        background.src = imagess
        background.onload = function () {
            context.drawImage(background, 0, 0, 700, 460);
        }
    }
}
const loadRiwayat = async () => {
    isLoading.value = true
    let responsex = await useApi().get(`/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`)
    isLoading.value = false
    if (responsex.length) {
        input.value = responsex[0];
        if (NOREC_EMRPASIEN.value == '') {
            NOREC_EMRPASIEN.value = responsex[0].emrpasienfk
        }
        dataTTD.value = responsex[0]
        await loadGambar("GambarTubuh", dataTTD.value.GambarTubuh)
        H.tandaTangan().set("TTDDokter", dataTTD.value.TTDDokter)
    } else {
        input.value.CBDokter = { label: user.namaLengkap, value: user.id }
    }
}
const kelompokUser = useUserSession().getUser().kelompokUser.kelompokUser
function checkResume() {
    // if not dokter just end
    console.log(`res Check Resume kelompok`, kelompokUser);
    // if (kelompokUser && kelompokUser.toUpperCase() == 'DOKTER' || (props.registrasi.namaruangan.toUpperCase().indexOf('FISIO') > -1 && kelompokUser.toUpperCase() == 'PERAWAT')) {
    let params = `?norec_pd=${props.registrasi.norec_pd}&norec_apd=${props.registrasi.norec_apd}`
    let uri = `/emr/check-resume-medis${params}`;
    useApi().get(uri).then((res) => {
        console.log(`res Check Resume`, res);
        if (res) {
            isResumeMedis.value = true;
        }
    })
    // } else {
    //   return;
    // }
}

function ubahData(number, type) {
    switch (type) {
        case 1:
            if (number == 1) return 'Tidak Ada';
            else if (number == 2) return 'Ada';
            break;
        case 2:
            if (number == 1) return 'Tidak Ada';
            else if (number == 2) return 'Ya';
            break;
        default:
            break;
    }
}

async function makeRingkasanData(json: any) {
    return new Promise((resolve, reject) => {
        try {
            let tanggaldatang = '';
            let dpjpUtamas: any = {
                value: json.data.user_input ? json.data.user_input.pegawaifk : json.data.registrasi.objectpegawaifk,
                label: json.data.user_input ? json.data.user_input.namalengkap : json.data.registrasi.dokter,
            };

            let fisik = '';
            fisik += json.data.GCSe ? `GCSe : ${json.data.GCSe} °C\n` : 'GCSe : -\n'
            fisik += json.data.GCSv ? `GCSv : ${json.data.GCSv} °C\n` : 'GCSv : -\n'
            fisik += json.data.GCSm ? `GCSm : ${json.data.GCSm} °C\n` : 'GCSm : -\n'
            fisik += json.data.TBtekananDarahTTV ? `Tekanan Darah : ${json.data.TBtekananDarahTTV} mmHg\n` : 'Tekanan Darah : -n\n'
            fisik += json.data.TBnadiTTV ? `Nadi : ${json.data.TBnadiTTV} x/mnt\n` : 'Nadi : -\n'
            fisik += json.data.TBcelciusTTV ? `Suhu : ${json.data.TBcelciusTTV} °C\n` : 'Suhu : -\n'
            fisik += json.data.TBPernafasanTTV ? `Pernafasan : ${json.data.TBPernafasanTTV} x/mnt\n` : 'Pernafasan : -\n'
            fisik += json.data.TBtinggiBadanTTV ? `Tinggi Badan : ${json.data.TBtinggiBadanTTV} Cm\n` : 'Tinggi Badan : -\n'
            fisik += json.data.TBberatBadanTTV ? `Berat Badan : ${json.data.TBberatBadanTTV} Kg\n` : 'Berat Badan : -\n'

            let anamnesis = '';
            let d = input.value
            anamnesis += '1. Riwayat penyakit sebelumnya \n';
            anamnesis += `  a. Hipertensi : ${d.SHipertensi == 2 ? ubahData(d.SHipertensi, 1) + `, ${d.TBHipertensi_RPS}` : ubahData(d.SHipertensi, 1) } \n`;
            anamnesis += `  b. Diabetes melitus : ${d.SDiabetesMelitus == 2 ? ubahData(d.SDiabetesMelitus, 1) + `, ${d.TBDiabetesMelitus_RPS}` : ubahData(d.SDiabetesMelitus, 1) } \n`;
            anamnesis += `  c. Penyakit Ginjal : ${d.SPenyakitGinjal == 2 ? ubahData(d.SPenyakitGinjal, 1) + `, ${d.TBPenyakitGinjal_RPS}` : ubahData(d.SPenyakitGinjal, 1) } \n`;
            anamnesis += `  d. Penyakit Jantung : ${d.SPenyakitJantung == 2 ? ubahData(d.SPenyakitJantung, 1) + `, ${d.TBPenyakitJantung_RPS}` : ubahData(d.SPenyakitJantung, 1) } \n`;
            anamnesis += `  e. Asthma : ${d.SAsthma == 2 ? ubahData(d.SAsthma, 1) + `, ${d.TBAsthma_RPS}` : ubahData(d.SAsthma, 1) } \n`;
            anamnesis += `  f. Trauma/Fraktur : ${d.STraumaFraktur == 2 ? ubahData(d.STraumaFraktur, 1) + `, ${d.TBTraumaFraktur_RPS}` : ubahData(d.STraumaFraktur, 1) } \n`;
            anamnesis += `  g. Kejang : ${d.SKejang == 2 ? ubahData(d.SKejang, 1) + `, ${d.TBKejang_RPS}` : ubahData(d.SKejang, 1) } \n`;
            anamnesis += `2. Riwayat operasi/biopsy sebelumnya : ${d.SRiwayatOperasi == 2 ? ubahData(d.SRiwayatOperasi, 1) + `, ${d.TBRiwayatOperasi}` : ubahData(d.SRiwayatOperasi, 1) } \n`;
            anamnesis += `3. Riwayat pengobatan terapi sistemik kanker : ${d.SRiwayatPengobatanKanker == 2 ? ubahData(d.SRiwayatPengobatanKanker, 1) + `, ${d.TBRiwayatPengobatanKanker}` : ubahData(d.SRiwayatPengobatanKanker, 1) } \n`;
            anamnesis += `4. Riwayat terapi radiasi sebelumnya : ${d.SRiwayatTerapiRadiasi == 2 ? ubahData(d.SRiwayatTerapiRadiasi, 1) + `, ${d.TBRiwayatTerapiRadiasi}` : ubahData(d.SRiwayatTerapiRadiasi, 1) } \n`;
            anamnesis += `5. Riwayat pemeriksaan dengan zat kontras : ${d.SRiwayatPemeriksaanZatKontras == 2 ? ubahData(d.SRiwayatPemeriksaanZatKontras, 1) + `, ${d.TBRiwayatPemeriksaanZatKontras}` : ubahData(d.SRiwayatPemeriksaanZatKontras, 1) } \n`;
            anamnesis += `6. Riwayat claustrophobia : ${d.SRiwayatClaustrophobia == 2 ? ubahData(d.SRiwayatClaustrophobia, 1) + `, ${d.TBRiwayatClaustrophobia}` : ubahData(d.SRiwayatClaustrophobia, 1) } \n`;
            anamnesis += `7. Riwayat alergi : ${d.SRiwayatAlergi == 2 ? ubahData(d.SRiwayatAlergi, 1) + `, ${d.TBRiwayatAlergi}` : ubahData(d.SRiwayatAlergi, 1) } \n`;
            // anamnesis += `8. Keluhan saat ini : ${ubahData(d.TBKeluhanSaatIni, 1)} \n`;
            anamnesis += json.data.TBberatBadanTTV ? `8. Keluhan saat ini : ${json.data.TBKeluhanSaatIni} \n` : '8. Keluhan saat ini : \n'
            // anamnesis += `9. Riwayat kesehatan saat ini : ${ubahData(d.TBRiwayatKesehatanSaatIni, 1)} \n`;
            anamnesis += json.data.TBberatBadanTTV ? `9. Riwayat kesehatan saat ini : ${json.data.TBRiwayatKesehatanSaatIni} \n` : '9. Riwayat kesehatan saat ini : \n'
            anamnesis += `  a. Adakah obat rutin yang sedang dikonsumsi : ${d.SObatRutinSedangDikonsumsi == 2 ? ubahData(d.SObatRutinSedangDikonsumsi, 1) + `, ${d.TBObatRutinSedangDikonsumsi}` : ubahData(d.SObatRutinSedangDikonsumsi, 1) } \n`;
            anamnesis += `  b. Apakah bisa posisi berbaring selama pemeriksaan : ${d.SPosisiBaringSelamaPemeriksaan == 2 ? ubahData(d.SPosisiBaringSelamaPemeriksaan, 1) + `, ${d.TBPosisiBaringSelamaPemeriksaan}` : ubahData(d.SPosisiBaringSelamaPemeriksaan, 1) } \n`;
            anamnesis += `  c. Apakah ada gangguan BAK : ${d.SGangguanBAK == 2 ? ubahData(d.SGangguanBAK, 1) + `, ${d.TBGangguanBAK}` : ubahData(d.SGangguanBAK, 1) } \n`;
            anamnesis += `  d. Apakah terpasang urine bag atau colostomy bag : ${d.STerpasangUrineBag == 2 ? ubahData(d.STerpasangUrineBag, 1) + `, ${d.TBTerpasangUrineBag}` : ubahData(d.STerpasangUrineBag, 1) } \n`;
            anamnesis += `  e. Apakah pasien (wanita) sedang hamil dan menyusui : ${d.SSedangHamilMenyusui == 2 ? ubahData(d.SSedangHamilMenyusui, 1) + `, ${d.TBSedangHamilMenyusui}` : ubahData(d.SSedangHamilMenyusui, 1) } \n`;
            anamnesis += `  f. Apakah pasien (wanita) menstruasi : ${d.SMenstruasi == 2 ? ubahData(d.SMenstruasi, 1) + `, ${d.TBMenstruasi}` : ubahData(d.SMenstruasi, 1) } \n`;
            anamnesis += `  g. Apakah pasien (wanita) sedang hamil : ${d.SHamil == 2 ? ubahData(d.SHamil, 1) + `, ${d.TBHamil}` : ubahData(d.SHamil, 1) } \n`;
            // anamnesis += `  h. Jam terakhir pasien makan dan konsumsi gula : ${ubahData(d.TJamTerakhirMakan, 1)} \n`;
            anamnesis += json.data.TBberatBadanTTV ? `  h. Jam terakhir pasien makan dan konsumsi gula : ${json.data.TJamTerakhirMakan} \n` : 'h. Jam terakhir pasien makan dan konsumsi gula : \n'
            // anamnesis += `  i. Jam pengambilan pemeriksaan gula darah sewaktu : ${ubahData(d.TJamPengambilanPemeriksaanGulaDarah, 1)} \n`;
            anamnesis += json.data.TBberatBadanTTV ? `  i. Jam pengambilan pemeriksaan gula darah sewaktu : ${json.data.TJamPengambilanPemeriksaanGulaDarah} \n` : 'i. Jam pengambilan pemeriksaan gula darah sewaktu : \n'
            // anamnesis += `  h. Jam terakhir pasien makan dan konsumsi gula : ${d.SHamil == 2 ? ubahData(d.SHamil, 1) + `, ${d.TBHamil}` : ubahData(d.SHamil, 1) } \n`;
            // anamnesis += '2. Riwayat operasi/biopsy sebelumnya \n';
            
            console.log(anamnesis);
            // return;
            // let anamnesis = '';
            // anamnesis += json.data.SDiabetesMelitus ? `a. Hipertensi : ${json.data.SHipertensi}` : 'Hipertensi : -\n'
            // console.log(anamnesis);

            let penunjang = '';
            penunjang += `Patologi anatomi: ${json.data.patologianatomi || '-'} | Tanggal: ${json.data.tglpatologianatomi || '-'} | Hasil: ${json.data.hasilpatologianatomi || '-'} \n`;
            penunjang += `Laboratorium: ${json.data.laboratorium || '-'} | Tanggal: ${json.data.tgllaboratorium || '-'} | Hasil: ${json.data.hasillaboratorium || '-'} \n`;
            penunjang += `Radiologi: ${json.data.radiologi || '-'} | Tanggal: ${json.data.tglradiologi || '-'} | Hasil: ${json.data.hasilradiologi || '-'} \n`;
            penunjang += `Lain-lain: ${json.data.lainnya || '-'} | Tanggal: ${json.data.tgllainnya || '-'} | Hasil: ${json.data.hasillainnya || '-'} \n`;

            // return;
            let object = {
                "detailDS": [
                    {
                        "no": 1,
                        "TADiagnosaSekunder": ""
                    }
                ],
                "detailDT": [
                    {
                        "no": 1,
                        "TADeskripsiTindakan": ""
                    }
                ],
                "pemeriksaanfisik": fisik,
                "hasilpemeriksaanpenunjang": penunjang,
                "TADiagnosisPrimer": json.data.TADiagnosa ?? null,
                "intruksi": json.data.rencanaintervensi ?? '',
                "gcse": json.data.GCSe ?? '',
                "gcsv": json.data.GCSv ?? '',
                "gcsm": json.data.GCSm ?? '',
                "kesanUmum": json.data.SKeadaanUmum ?? '',
                "nadi": json.data.TBnadiTTV ?? '',
                "nafas": json.data.TBPernafasanTTV ?? '',
                "celcius": json.data.TBcelciusTTV ?? '',
                "tekananDarah": json.data.TBtekananDarahTTV ?? '',
                "anamnesis": anamnesis,
                "waktuTataLaksana": json.data.jamKedatangan,
                "waktuKontrol": json.data.jamKedatangan,
                "jamKedatangan": json.data.jamKedatangan,
                "jamAsesmenAwal": json.data.jamKedatangan,
                "riwayatkeluar": json.data.riwayatkeluar,
                "statuskeluar": json.data.statuskeluar,
                "perlukontrol": json.data.perlukontrol,
                "tanggalKedatangan": json.data.jamKedatangan,
                "dpjpUtama": dpjpUtamas,
                "TAKondisiSaatMasuk": '',
                "sumber": "AsmedNuklir"
            }
            object.nocm = json.data.pasien.nocm
            object.pasien = json.data.pasien
            object.registrasi = json.data.registrasi
            let sendData = {
                'id': '',
                'norec_emr': '',
                'collection': 'RingkasanKeluar',
                'url_form': 'module-emr-profile-pasien-page-emr-ringkasan-keluar',
                'name_form': 'Ringkasan Keluar',
                'jenis_emr': 'asesmen_medis',
                'data': object
            }

            useApi().postNoMessage(
                `/emr/simpan-emr`, sendData).then(async (response: any) => {
                    isLoading.value = false
                    H.alert('success', 'Ringkasan keluar berhasil dibuat');
                    return resolve(true)
                }).catch((e: any) => {
                    isLoading.value = true
                    H.alert('error', 'Ringkasan keluar gagal dibuat');
                    return resolve(false)
                })
        } catch (error) {
            return reject(error);
        }
    })
}

const saveKlaimSEP = async () => {
    isLoading.value = true
    if (props.registrasi.objectdepartemenfk == 18) {
        await useApi().post('/bridging/inacbgs/collect-dokumen', {
            'norec_pd': props.registrasi.norec_pd,
            'documentklaimfk': 207,
            'namafile': "resume_medis_rj",
            'tglregistrasi': props.registrasi.tglregistrasi,
            'api': "EMR-ReportEMRCtrl@cetakEMR-RingkasanKeluar"
        }).then((r) => {
            isLoading.value = false
        }).catch((er) => {
            isLoading.value = false
        })
    } else if (props.registrasi.objectdepartemenfk == 9) {
        await useApi().post('/bridging/inacbgs/collect-dokumen', {
            'norec_pd': props.registrasi.norec_pd,
            'documentklaimfk': 208,
            'namafile': "resume_igd",
            'tglregistrasi': props.registrasi.tglregistrasi,
            'api': "EMR-ReportEMRCtrl@cetakEMR-RingkasanKeluar"
        }).then((r) => {
            isLoading.value = false
        }).catch((er) => {
            isLoading.value = false
        })
    }
}

const simpan = () => {
    let ID = input.value.id ? input.value.id : ''
    let object: any = {}

    if (!input.value.TBKeluhanSaatIni || input.value.TBKeluhanSaatIni && input.value.TBKeluhanSaatIni.replace(/\s/g, "").length < 4) {
        H.alert('error', 'Keluhan, ' + 'diisi minimal 4 karakter');
        return;
    }
    if (!input.value.TADiagnosa) {
        H.alert('warning', 'Diagnosa wajib diisi!')
        return;
    }

    object = input.value
    object.pasien = H.setObjectPasien(props.pasien)
    object.registrasi = H.setObjectRegistrasi(props.registrasi)
    object['GambarTubuh'] = H.tandaTangan().get("GambarTubuh");
    object['TTDDokter'] = H.tandaTangan().get("TTDDokter");
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
        `/emr/simpan-emr`, json).then(async (response: any) => {
            // BENTARRRRR
            // await makeRingkasanData(json);
            if (isResumeMedis.value) {
                makeRingkasanData(json).then((res) => {
                isLoading.value = false
                saveKlaimSEP();
                }).catch((err) => {
                console.log('ERR RINGKASAN', err)
                H.alert('warning', 'gagal membuat ringkasan keluar');
                isLoading.value = false
                });
            }
            isLoading.value = false
            NOREC_EMRPASIEN.value = response.norec_emr
            input.value.id = response.id
        }).catch((e: any) => {
            isLoading.value = false
            console.log(e);
            // NOREC_EMRPASIEN.value = response.norec_emr
        })
}

onMounted(() => {
    checkResume()
});

const fetchDokter = async (filter: any) => {
    await useApi().get(`emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&settingdatafix=objectjenispegawaifk,idJenisPegawaiDokter&limit=10`).then((response) => {
        d_Dokter.value = response
    })
}

const getDataExist = async () => {
    useApi().get("emr/auto-fill?norec_pd=" + props.registrasi.norec_pd + "&collection=AsesmenAwalKeperawatanPasienRawatJalanNurse" + "&field=tekananDarahObgyn,nafasObgyn,keadaanumumobgyn,keadaanumum,celciusObgyn,nadiObgyn,sao2Obgyn,gcse,gcsv,gcsm,kebpilihanallo,tinggibadanObgyn,beratbadanObgyn").then((response) => {
        input.value.GCSe = response.gcse
        input.value.GCSv = response.gcsv
        input.value.GCSm = response.gcsm
        input.value.TBtekananDarahTTV = response.tekananDarahObgyn
        input.value.TBnadiTTV = response.nadiObgyn
        input.value.TBcelciusTTV = response.celciusObgyn
        input.value.TBPernafasanTTV = response.nafasObgyn
        input.value.TBtinggiBadanTTV = response.tinggibadanObgyn
        input.value.TBberatBadanTTV = response.beratbadanObgyn
    })
}

var normal = 1;
function batasNormal() {
    if (normal == 1) {
        input.value.SHipertensi = 1
        input.value.SDiabetesMelitus = 1
        input.value.SPenyakitGinjal = 1
        input.value.SPenyakitJantung = 1
        input.value.SAsthma = 1
        input.value.STraumaFraktur = 1
        input.value.SKejang = 1
        input.value.SBunyiJantung = 1
        input.value.SPerkusiKanan = 1
        input.value.SPerkusiKiri = 1
        input.value.SAuskultasiKanan = 1
        input.value.SAuskultasiKiri = 1
        input.value.SRiwayatOperasi = 1
        input.value.SRiwayatPengobatanKanker = 1
        input.value.SRiwayatTerapiRadiasi = 1
        input.value.SRiwayatPemeriksaanZatKontras = 1
        input.value.SRiwayatClaustrophobia = 1
        input.value.SRiwayatAlergi = 1
        normal = normal - 1;
        return normal;
    } else {
        input.value.SHipertensi = undefined
        input.value.SDiabetesMelitus = undefined
        input.value.SPenyakitGinjal = undefined
        input.value.SPenyakitJantung = undefined
        input.value.SAsthma = undefined
        input.value.STraumaFraktur = undefined
        input.value.SKejang = undefined
        input.value.SBunyiJantung = undefined
        input.value.SPerkusiKanan = undefined
        input.value.SPerkusiKiri = undefined
        input.value.SAuskultasiKanan = undefined
        input.value.SAuskultasiKiri = undefined
        input.value.SRiwayatOperasi = undefined
        input.value.SRiwayatPengobatanKanker = undefined
        input.value.SRiwayatTerapiRadiasi = undefined
        input.value.SRiwayatPemeriksaanZatKontras = undefined
        input.value.SRiwayatClaustrophobia = undefined
        input.value.SRiwayatAlergi = undefined
        normal = normal + 1
        return normal;
    }
}

onBeforeMount(async () => {
    try {
        await setView()
        await loadRiwayat()
        await getDataExist()
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
</style>