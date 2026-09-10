<template>
    <div>
        <div class="form-layout is-stacked-2">
            <div class="form-outer" style="margin-top:15px">
                <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
                    <div class="form-header-inner">
                        <div class="left">
                            <h3>Asesmen Awal Keperawatan Pasien Anak Rawat Inap</h3>
                        </div>
                        <div class="right">
                            <ButtonEmr :NOREC_EMRPASIEN="NOREC_EMRPASIEN" :COLLECTION="COLLECTION"
                                :isLoading="isLoading" @simpan="simpan" @simpanTemplate="simpanTemplate"
                                @kembaliKeun="kembaliKeun" :isHideCetak="true"></ButtonEmr>
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

                <!-- form baru -->

                <div class="column">
                    <div class="column is-12 buttons mb-0 mt-0" style="margin:10px;vertical-align:middle">
                        <VButton type="button" rounded outlined color="primary" raised icon="feather:folder"
                            isLoading="false" @click="pilihTemplateFix(index)"> Pilih Template
                        </VButton>
                        <VButton type="button" rounded outlined color="info" raised icon="feather:file-text"
                            isLoading="false" @click="pilihTemplate(index)"> Pilih Riwayat
                        </VButton>
                    </div>

                    <hr style="border-top: 1px dashed lightgray;background-color:white" class="mt-0 mb-1">

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

                    <hr style="border-top: 1px dashed red;background-color:white" class="mt-0 mb-1">

                    <div class="column columns pb-0">
                        <div class="column is-6">
                            <h1>Tanggal Masuk</h1>
                            <VDatePicker v-model="input.DTanggalMasuk" mode="dateTime" trim-weeks
                                :max-date="new Date()">
                                <template #default="{ inputValue, inputEvents }">
                                    <VControl icon="feather:calendar" fullwidth>
                                        <VInput :value="inputValue" placeholder="Tanggal masuk..." v-on="inputEvents" />
                                    </VControl>
                                </template>
                            </VDatePicker>
                        </div>
                        <div class="column is-6">
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
                        <div class="column is-4 pt-1">
                            <h1>Rujukan</h1>
                            <Multiselect v-model="input.SRujukan" :attrs="{ value }" placeholder="--Pilih--"
                                label="label" :options="d_rujukan" :searchable="true" track-by="label" mode="single"
                                autocomplete="off">
                            </Multiselect>
                        </div>
                        <div class="column is-4 pt-1" v-if="input.SRujukan == 1">
                            <h1>Dari :</h1>
                            <Multiselect v-model="input.STempatRujukan" :attrs="{ value }" placeholder="--Pilih--"
                                label="label" :options="d_tempatRujukan" :searchable="true" track-by="label"
                                mode="single" autocomplete="off">
                            </Multiselect>
                        </div>
                        <div class="column is-4 pt-1" v-if="input.SRujukan == 4">
                            <h1>&nbsp;</h1>
                            <VControl>
                                <VInput type="text" class="input" v-model="input.TBDiantar"
                                    placeholder="Diantar oleh..." />
                            </VControl>
                        </div>
                        <div class="column is-4 pt-1" v-if="input.STempatRujukan == 1 && input.SRujukan == 1">
                            <h1>Rumah Sakit</h1>
                            <VControl>
                                <VInput type="text" class="input" placeholder="Rumah sakit..."
                                    v-model="input.TBRujuk_RS" />
                            </VControl>
                        </div>
                        <div class="column is-4 pt-1" v-if="input.STempatRujukan == 2 && input.SRujukan == 1">
                            <h1>Puskesmas</h1>
                            <VControl>
                                <VInput type="text" class="input" placeholder="Puskesmas..."
                                    v-model="input.TBRujuk_Puskesmas" />
                            </VControl>
                        </div>
                        <div class="column is-4 pt-1" v-if="input.STempatRujukan == 3 && input.SRujukan == 1">
                            <h1>dr.</h1>
                            <VControl>
                                <VInput type="text" class="input" placeholder="dr..." v-model="input.TBRujuk_dr" />
                            </VControl>
                        </div>
                        <div class="column is-4 pt-1" v-if="input.STempatRujukan == 4 && input.SRujukan == 1">
                            <h1>Lainnya</h1>
                            <VControl>
                                <VInput type="text" class="input" placeholder="Lainnya..."
                                    v-model="input.TBRujuk_Lainnya" />
                            </VControl>
                        </div>
                        <div class="column is-4 pt-1">
                            <h1>Dx.rujukan</h1>
                            <VControl>
                                <VInput type="text" class="input" v-model="input.TBDx_rujukan" />
                            </VControl>
                        </div>
                        <div class="column is-12 pt-1">
                            <h1>Alamat Pasien</h1>
                            <VField>
                                <VTextarea rows="2" v-model="input.alamatPasien"></VTextarea>
                            </VField>
                        </div>
                    </div>

                    <div class="columns is-multiline column pb-0">
                        <div class="column is-12 pt-0 pb-0">
                            <hr style="border-top: 1px dashed red;background-color:white" class="mt-0 mb-1">
                        </div>
                        <div class="column is-12">
                            <h1>ANAMNESIS</h1>
                        </div>
                        <div class="column is-4 pt-1">
                            <VControl raw subcontrol>
                                <VCheckbox class="p-0" color="primary" square true-value="Auto" label="Auto"
                                    v-model="input.auto" />
                            </VControl>
                            <VControl v-if="input.auto == 'Auto'">
                                <VInput type="text" class="input" v-model="input.autoDetail" />
                            </VControl>
                        </div>
                        <div class="column is-12 p-0"></div>
                        <div class="column is-4 pt-1">
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
                        <div class="column is-4 pt-1" v-if="input.kebpilihanallo == 4">
                            <h1>Lainnya</h1>
                            <VControl>
                                <VInput type="text" class="input" v-model="input.TBLainnya_Allo"
                                    placeholder="Lainnya..." />
                            </VControl>
                        </div>
                        <div class="column is-12 p-0"></div>
                        <div class="column is-6">
                            <h1>Keluhan Utama :</h1>
                            <VField>
                                <VTextarea rows="2" v-model="input.TAKeluhanUtama"></VTextarea>
                            </VField>
                        </div>
                        <div class="column is-6">
                            <h1>Riwayat Penyakit Sekarang :</h1>
                            <VField>
                                <VTextarea rows="2" v-model="input.TARiwayatPenyakitSekarang"></VTextarea>
                            </VField>
                        </div>
                        <div class="column is-12 pt-1">
                            <h1>Riwayat Penyakit Dahulu :</h1>
                            <div class="columns is-multiline">
                                <div class="column is-3">
                                    a. Riwayat MRS sebelumnya :
                                </div>
                                <div class="column is-2">
                                    <Multiselect v-model="input.SRPD_1" :attrs="{ value }" placeholder="--Pilih--"
                                        label="label" :options="d_tidakYa" :searchable="true" track-by="label"
                                        mode="single" autocomplete="off">
                                    </Multiselect>
                                </div>
                                <div class="column is-7">
                                    <div class="columns is-multiline">
                                        <div class="column is-6 p-1">
                                            <span>Lamanya</span>
                                            <VControl>
                                                <VInput type="text" class="input" v-model="input.lamanyaRMRS" />
                                            </VControl>
                                        </div>
                                        <div class="column is-6 p-1">
                                            <span>Alasan</span>
                                            <VControl>
                                                <VInput type="text" class="input" v-model="input.alasanRMRS" />
                                            </VControl>
                                        </div>
                                    </div>
                                </div>
                                <div class="column is-3">
                                    b. Riwayat dioperasi :
                                </div>
                                <div class="column is-2">
                                    <Multiselect v-model="input.SRPD_2" :attrs="{ value }" placeholder="--Pilih--"
                                        label="label" :options="d_tidakYa" :searchable="true" track-by="label"
                                        mode="single" autocomplete="off">
                                    </Multiselect>
                                </div>
                                <div class="column is-7 pl-1">
                                    <span>Jelaskan</span>
                                    <VField>
                                        <VTextarea rows="1" v-model="input.jelaskan2"></VTextarea>
                                    </VField>
                                </div>
                                <div class="column is-3">
                                    c. Riwayat kelainan bawaan :
                                </div>
                                <div class="column is-2">
                                    <Multiselect v-model="input.SRPD_3" :attrs="{ value }" placeholder="--Pilih--"
                                        label="label" :options="d_tidakYa" :searchable="true" track-by="label"
                                        mode="single" autocomplete="off">
                                    </Multiselect>
                                </div>
                                <div class="column is-7 pl-1">
                                    <span>Jelaskan</span>
                                    <VField>
                                        <VTextarea rows="1" v-model="input.jelaskan3"></VTextarea>
                                    </VField>
                                </div>
                            </div>
                            <!-- <VField>
                                <VTextarea rows="2" v-model="input.TARiwayatPenyakitDahulu"></VTextarea>
                            </VField> -->
                        </div>
                        <div class="column is-12 p-0"></div>
                        <div class="column is-6 pt-1">
                            <h1>Riwayat Pengobatan :</h1>
                            <VField>
                                <VTextarea rows="2" v-model="input.TARiwayatPenyakitPengobatan"></VTextarea>
                            </VField>
                        </div>
                        <div class="column is-6 pt-1">
                            <h1>Riwayat Penyakit Keluarga :</h1>
                            <VField>
                                <VTextarea rows="2" v-model="input.TARiwayatPenyakitKeluarga"></VTextarea>
                            </VField>
                        </div>
                        <div class="column is-12 pt-1">
                            <div class="columns">
                                <div class="column is-4">
                                    <h1>Riwayat Alergi</h1>
                                    <Multiselect v-model="input.SRiwayatAlergi" :attrs="{ value }"
                                        placeholder="--Pilih--" label="label" :options="d_riwayatG" :searchable="true"
                                        track-by="label" mode="single" autocomplete="off">
                                    </Multiselect>
                                </div>
                                <div class="column is-8" v-if="input.SRiwayatAlergi == 2">
                                    <div class="columns">
                                        <div class="column is-4">
                                            <VControl raw subcontrol>
                                                <VCheckbox class="p-0" color="primary" square true-value="Obat"
                                                    label="Obat" v-model="input.CBAlergiObat" />
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
                        <div class="column is-12 pt-1">
                            <div class="columns is-multiline">
                                <div class="column is-12 pt-1 pb-0">
                                    <span style="font-weight: bold;">Riwayat Imunisasi</span>
                                </div>
                                <div class="column is-2 pt-1">
                                    <VControl raw subcontrol>
                                        <VCheckbox class="p-0" color="primary" square true-value="Polio" label="Polio"
                                            v-model="input.polio" />
                                    </VControl>
                                    <VField addons>
                                        <VControl>
                                            <VInput type="text" class="input" v-model="input.polioDetail" />
                                        </VControl>
                                        <VControl class="field-addon-body">
                                            <VButton static>x</VButton>
                                        </VControl>
                                    </VField>
                                </div>
                                <div class="column is-2 pt-1">
                                    <VControl raw subcontrol>
                                        <VCheckbox class="p-0" color="primary" square true-value="BCG" label="BCG"
                                            v-model="input.bcg" />
                                    </VControl>
                                </div>
                                <div class="column is-2 pt-1">
                                    <VControl raw subcontrol>
                                        <VCheckbox class="p-0" color="primary" square true-value="Hepatitis B"
                                            label="Hepatitis B" v-model="input.hepatitisB" />
                                    </VControl>
                                    <VField addons>
                                        <VControl>
                                            <VInput type="text" class="input" v-model="input.hepatitisBDetail" />
                                        </VControl>
                                        <VControl class="field-addon-body">
                                            <VButton static>x</VButton>
                                        </VControl>
                                    </VField>
                                </div>
                                <div class="column is-2 pt-1">
                                    <VControl raw subcontrol>
                                        <VCheckbox class="p-0" color="primary" square true-value="DPT" label="DPT"
                                            v-model="input.dpt" />
                                    </VControl>
                                    <VField addons>
                                        <VControl>
                                            <VInput type="text" class="input" v-model="input.dptDetail" />
                                        </VControl>
                                        <VControl class="field-addon-body">
                                            <VButton static>x</VButton>
                                        </VControl>
                                    </VField>
                                </div>
                                <div class="column is-2 pt-1">
                                    <VControl raw subcontrol>
                                        <VCheckbox class="p-0" color="primary" square true-value="PCV" label="PCV"
                                            v-model="input.pcv" />
                                    </VControl>
                                    <VField addons>
                                        <VControl>
                                            <VInput type="text" class="input" v-model="input.pcvDetail" />
                                        </VControl>
                                        <VControl class="field-addon-body">
                                            <VButton static>x</VButton>
                                        </VControl>
                                    </VField>
                                </div>
                                <div class="column is-2 pt-1">
                                    <VControl raw subcontrol>
                                        <VCheckbox class="p-0" color="primary" square true-value="Campak" label="Campak"
                                            v-model="input.campak" />
                                    </VControl>
                                    <VField addons>
                                        <VControl>
                                            <VInput type="text" class="input" v-model="input.campakDetail" />
                                        </VControl>
                                        <VControl class="field-addon-body">
                                            <VButton static>x</VButton>
                                        </VControl>
                                    </VField>
                                </div>
                                <div class="column is-2 pt-1">
                                    <VControl raw subcontrol>
                                        <VCheckbox class="p-0" color="primary" square true-value="Lainnya"
                                            label="Lainnya" v-model="input.lainnya" />
                                    </VControl>
                                    <VControl>
                                        <VInput type="text" class="input" v-model="input.lainnyaDetail" />
                                    </VControl>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="column pt-0 pb-0">
                        <hr style="border-top: 1px dashed lightgray;background-color:white" class="mt-0 mb-1">
                    </div>

                    <div class="column columns is-multiline">
                        <div class="columns is-multiline m-0">
                            <div class="column is-12 pb-0">
                                <h1>Status Fisik</h1>
                            </div>
                            <div class="column is-3 pb-0">
                                <h1>Keadaan Umum</h1>
                                <Multiselect v-model="input.SKeadaanUmum" :attrs="{ value }" placeholder="--Pilih--"
                                    label="label" :options="d_keadaanumum" :searchable="true" track-by="label"
                                    mode="single" autocomplete="off">
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
                                                    <VInput type="text" class="input" v-model="input.GCSe"
                                                        maxLength="1" />
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
                                                    <VInput type="text" class="input" v-model="input.GCSv"
                                                        maxLength="1" />
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
                                                    <VInput type="text" class="input" v-model="input.GCSm"
                                                        maxLength="1" />
                                                </VControl>
                                            </VControl>
                                        </VField>
                                    </div>
                                </div>
                            </div>
                            <div class="column is-4">
                                <h1>Kesadaran</h1>
                                <Multiselect v-model="input.kesadaran" :attrs="{ value }" placeholder="--Pilih--"
                                    label="label" :options="d_Kesadaran" :searchable="true" track-by="label"
                                    mode="single" autocomplete="off">
                                </Multiselect>
                            </div>
                            <div class="column is-3">
                                <h1>Tekanan Darah</h1>
                                <VField addons>
                                    <VControl style="width: 100%;">
                                        <VInput type="text" class="input" v-model="input.TBStekananDarah" />
                                    </VControl>
                                    <VControl class="field-addon-body">
                                        <VButton static>mmHg</VButton>
                                    </VControl>
                                </VField>
                            </div>
                            <div class="column is-3">
                                <h1>MAP</h1>
                                <VField addons>
                                    <VControl style="width: 100%;">
                                        <VInput type="text" class="input" v-model="input.TBS_MAP" />
                                    </VControl>
                                    <VControl class="field-addon-body">
                                        <VButton static>mmHg</VButton>
                                    </VControl>
                                </VField>
                            </div>
                            <div class="column is-3">
                                <h1>Nadi</h1>
                                <VField addons>
                                    <VControl style="width: 100%;">
                                        <VInput type="text" class="input" v-model="input.TBSnadi" />
                                    </VControl>
                                    <VControl class="field-addon-body">
                                        <VButton static>x/mnt</VButton>
                                    </VControl>
                                </VField>
                            </div>
                            <div class="column is-3">
                                <h1>Kualitas Nadi</h1>
                                <Multiselect v-model="input.kualitasNadi" :attrs="{ value }" placeholder="--Pilih--"
                                    label="label" :options="d_kualitasNadi" :searchable="true" track-by="label"
                                    mode="single" autocomplete="off">
                                </Multiselect>
                            </div>
                            <div class="column is-3">
                                <h1>Respirasi</h1>
                                <VField addons>
                                    <VControl style="width: 100%;">
                                        <VInput type="text" class="input" v-model="input.TBSrespirasi" />
                                    </VControl>
                                    <VControl class="field-addon-body">
                                        <VButton static>x/mnt</VButton>
                                    </VControl>
                                </VField>
                            </div>
                            <div class="column is-3">
                                <h1>Pola Nafas</h1>
                                <Multiselect v-model="input.polaNafas" :attrs="{ value }" placeholder="--Pilih--"
                                    label="label" :options="d_polaNafas" :searchable="true" track-by="label"
                                    mode="single" autocomplete="off">
                                </Multiselect>
                            </div>
                            <div class="column is-3">
                                <h1>Suhu</h1>
                                <VField addons>
                                    <VControl style="width: 100%;">
                                        <VInput type="text" class="input" v-model="input.TBSsuhu" />
                                    </VControl>
                                    <VControl class="field-addon-body">
                                        <VButton static>°C</VButton>
                                    </VControl>
                                </VField>
                            </div>
                            <div class="column is-3">
                                <h1>SaO<sub>2</sub></h1>
                                <VField addons>
                                    <VControl style="width: 100%;">
                                        <VInput type="text" class="input" v-model="input.TBSSaO2" />
                                    </VControl>
                                    <VControl class="field-addon-body">
                                        <VButton static>%</VButton>
                                    </VControl>
                                </VField>
                            </div>
                            <div class="column is-12 pt-0 pb-0">
                                <h1>Antopometri</h1>
                            </div>
                            <div class="column is-3 pb-0">
                                <h1>Berat Badan</h1>
                                <VField addons>
                                    <VControl style="width: 100%;">
                                        <VInput type="text" class="input" v-model="input.TBSBeratBadan" />
                                    </VControl>
                                    <VControl class="field-addon-body">
                                        <VButton static>gram</VButton>
                                    </VControl>
                                </VField>
                            </div>
                            <div class="column is-3 pb-0">
                                <h1>PB</h1>
                                <VField addons>
                                    <VControl style="width: 100%;">
                                        <VInput type="text" class="input" v-model="input.TBS_PB" />
                                    </VControl>
                                    <VControl class="field-addon-body">
                                        <VButton static>cm</VButton>
                                    </VControl>
                                </VField>
                            </div>
                            <div class="column is-3 pb-0">
                                <h1>LK</h1>
                                <VField addons>
                                    <VControl style="width: 100%;">
                                        <VInput type="text" class="input" v-model="input.TBS_LK" />
                                    </VControl>
                                    <VControl class="field-addon-body">
                                        <VButton static>cm</VButton>
                                    </VControl>
                                </VField>
                            </div>
                            <div class="column is-3 pb-0">
                                <h1>LD</h1>
                                <VField addons>
                                    <VControl style="width: 100%;">
                                        <VInput type="text" class="input" v-model="input.TBS_LD" />
                                    </VControl>
                                    <VControl class="field-addon-body">
                                        <VButton static>cm</VButton>
                                    </VControl>
                                </VField>
                            </div>
                            <div class="column is-12 pb-0">
                                <h1>Kepala</h1>
                            </div>
                            <div class="column is-3 pt-1">
                                <span>&nbsp;</span>
                                <Multiselect v-model="input.kepala" :attrs="{ value }" placeholder="--Pilih--"
                                    label="label" :options="d_Kepala" :searchable="true" track-by="label" mode="single"
                                    autocomplete="off">
                                </Multiselect>
                            </div>
                            <div class="column is-3 pt-1">
                                <span>Warna Rambut</span>
                                <VControl>
                                    <VInput type="text" class="input" v-model="input.warnaRambut" />
                                </VControl>
                            </div>
                            <div class="column is-12 pb-0 pt-1">
                                <h1>Mata</h1>
                            </div>
                            <div class="column is-3 pt-1">
                                <span>Konjungtiva</span>
                                <Multiselect v-model="input.konjungtiva" :attrs="{ value }" placeholder="--Pilih--"
                                    label="label" :options="d_Konjungtiva" :searchable="true" track-by="label"
                                    mode="single" autocomplete="off">
                                </Multiselect>
                            </div>
                            <div class="column is-3 pt-1">
                                <span>Sklera</span>
                                <Multiselect v-model="input.sklera" :attrs="{ value }" placeholder="--Pilih--"
                                    label="label" :options="d_Sklera" :searchable="true" track-by="label" mode="single"
                                    autocomplete="off">
                                </Multiselect>
                                <VControl v-if="input.sklera == '3'">
                                    <VInput type="text" class="input" v-model="input.lainLainSklera"
                                        placeholder='Lain-lain' />
                                </VControl>
                            </div>
                            <div class="column is-12 pb-0 pt-1">
                                <h1>Leher</h1>
                            </div>
                            <div class="column is-3 pt-1">
                                <span>Bentuk</span>
                                <Multiselect v-model="input.bentukLeher" :attrs="{ value }" placeholder="--Pilih--"
                                    label="label" :options="d_BentukLeher" :searchable="true" track-by="label"
                                    mode="single" autocomplete="off">
                                </Multiselect>
                            </div>
                            <div class="column is-3 pt-1">
                                <span>Kelainan</span>
                                <Multiselect v-model="input.kelainanLeher" :attrs="{ value }" placeholder="--Pilih--"
                                    label="label" :options="d_tidakYa" :searchable="true" track-by="label" mode="single"
                                    autocomplete="off">
                                </Multiselect>
                                <VControl v-if="input.kelainanLeher == 2">
                                    <VInput type="text" class="input" v-model="input.kelainanLeherDetail"
                                        placeholder="Jelaskan..." />
                                </VControl>
                            </div>
                            <div class="column is-12 pb-0 pt-1">
                                <h1>Dada</h1>
                            </div>
                            <div class="column is-3 pt-1">
                                <span>Bentuk</span>
                                <Multiselect v-model="input.bentukDada" :attrs="{ value }" placeholder="--Pilih--"
                                    label="label" :options="d_BentukDada" :searchable="true" track-by="label"
                                    mode="single" autocomplete="off">
                                </Multiselect>
                            </div>
                            <div class="column is-3 pt-1">
                                <span>Kelainan</span>
                                <Multiselect v-model="input.kelainanDada" :attrs="{ value }" placeholder="--Pilih--"
                                    label="label" :options="d_tidakYa" :searchable="true" track-by="label" mode="single"
                                    autocomplete="off">
                                </Multiselect>
                                <VControl v-if="input.kelainanDada == 2">
                                    <VInput type="text" class="input" v-model="input.kelainanDadaDetail"
                                        placeholder="Jelaskan..." />
                                </VControl>
                            </div>
                            <div class="column is-3 pt-1">
                                <span>Kelainan Irama Jantung</span>
                                <Multiselect v-model="input.kelainanIramaJantung" :attrs="{ value }"
                                    placeholder="--Pilih--" label="label" :options="d_tidakYa" :searchable="true"
                                    track-by="label" mode="single" autocomplete="off">
                                </Multiselect>
                                <VControl v-if="input.kelainanIramaJantung == 2">
                                    <VInput type="text" class="input" v-model="input.kelainanIramaJantungDetail"
                                        placeholder="Jelaskan..." />
                                </VControl>
                            </div>
                            <div class="column is-3 pt-1">
                                <span>Irama Nafas</span>
                                <Multiselect v-model="input.iramaNafas" :attrs="{ value }" placeholder="--Pilih--"
                                    label="label" :options="d_iramaNafas" :searchable="true" track-by="label"
                                    mode="single" autocomplete="off">
                                </Multiselect>
                            </div>
                            <div class="column is-3 pt-1">
                                <span>Suara Nafas</span>
                                <Multiselect v-model="input.suaraNafas" :attrs="{ value }" placeholder="--Pilih--"
                                    label="label" :options="d_suaraNafas" :searchable="true" track-by="label"
                                    mode="single" autocomplete="off">
                                </Multiselect>
                            </div>
                            <div class="column is-3 pt-1">
                                <span>Batuk</span>
                                <Multiselect v-model="input.batuk" :attrs="{ value }" placeholder="--Pilih--"
                                    label="label" :options="d_tidakYa" :searchable="true" track-by="label" mode="single"
                                    autocomplete="off">
                                </Multiselect>
                            </div>
                            <div class="column is-3 pt-1">
                                <span>Jenis Nafas</span>
                                <Multiselect v-model="input.jenisNafas" :attrs="{ value }" placeholder="--Pilih--"
                                    label="label" :options="d_jenisNafas" :searchable="true" track-by="label"
                                    mode="single" autocomplete="off">
                                </Multiselect>
                            </div>
                            <div class="column is-3 pt-1">
                                <span>Retraksi</span>
                                <Multiselect v-model="input.retraksi" :attrs="{ value }" placeholder="--Pilih--"
                                    label="label" :options="d_tidakYa" :searchable="true" track-by="label" mode="single"
                                    autocomplete="off">
                                </Multiselect>
                            </div>
                            <div class="column is-3 pt-1">
                                <span>Sekret</span>
                                <Multiselect v-model="input.sekret" :attrs="{ value }" placeholder="--Pilih--"
                                    label="label" :options="d_tidakAda" :searchable="true" track-by="label"
                                    mode="single" autocomplete="off">
                                </Multiselect>
                                <div v-if="input.sekret == 2">
                                    <span>Warna/Jumlah</span>
                                    <VControl>
                                        <VInput type="text" class="input" v-model="input.sekretDetail"
                                            placeholder=".../..." />
                                    </VControl>
                                </div>
                            </div>
                            <div class="column is-12 pb-0 pt-1">
                                <h1>Abdomen</h1>
                            </div>
                            <div class="column is-3 pt-1">
                                <span>Kembung</span>
                                <Multiselect v-model="input.kembung" :attrs="{ value }" placeholder="--Pilih--"
                                    label="label" :options="d_tidakYa" :searchable="true" track-by="label" mode="single"
                                    autocomplete="off">
                                </Multiselect>
                            </div>
                            <div class="column is-3 pt-1">
                                <span>Bising Usus</span>
                                <VField addons>
                                    <VControl expanded>
                                        <VInput type="text" class="input" v-model="input.bisingUsus" />
                                    </VControl>
                                    <VControl class="field-addon-body">
                                        <VButton static>x/menit</VButton>
                                    </VControl>
                                </VField>
                            </div>
                            <div class="column is-3 pt-1">
                                <span>Hepatomegaly</span>
                                <Multiselect v-model="input.hepatomegaly" :attrs="{ value }" placeholder="--Pilih--"
                                    label="label" :options="d_tidakYa" :searchable="true" track-by="label" mode="single"
                                    autocomplete="off">
                                </Multiselect>
                            </div>
                            <div class="column is-3 pt-1">
                                <span>Distensi Kandung Kemih</span>
                                <Multiselect v-model="input.distensiKandungKemih" :attrs="{ value }"
                                    placeholder="--Pilih--" label="label" :options="d_tidakYa" :searchable="true"
                                    track-by="label" mode="single" autocomplete="off">
                                </Multiselect>
                            </div>
                            <div class="column is-12 pb-0 pt-1">
                                <h1>Ekstremitas</h1>
                            </div>
                            <div class="column is-3 pt-1">
                                <span>Akral</span>
                                <Multiselect v-model="input.akral" :attrs="{ value }" placeholder="--Pilih--"
                                    label="label" :options="d_akral" :searchable="true" track-by="label" mode="single"
                                    autocomplete="off">
                                </Multiselect>
                            </div>
                            <div class="column is-3 pt-1">
                                <span>Pergerakan</span>
                                <Multiselect v-model="input.Pergerakan" :attrs="{ value }" placeholder="--Pilih--"
                                    label="label" :options="d_Pergerakan" :searchable="true" track-by="label"
                                    mode="single" autocomplete="off">
                                </Multiselect>
                            </div>
                            <div class="column is-3 pt-1">
                                <span>Kekuatan Otot</span>
                                <Multiselect v-model="input.kekuatanOtot" :attrs="{ value }" placeholder="--Pilih--"
                                    label="label" :options="d_kekuatanOtot" :searchable="true" track-by="label"
                                    mode="single" autocomplete="off">
                                </Multiselect>
                            </div>
                            <div class="column is-3 pt-1">
                                <span>Edema</span>
                                <Multiselect v-model="input.edema" :attrs="{ value }" placeholder="--Pilih--"
                                    label="label" :options="d_tidakYa" :searchable="true" track-by="label" mode="single"
                                    autocomplete="off">
                                </Multiselect>
                            </div>
                            <div class="column is-3 pt-1">
                                <span>CRT</span>
                                <Multiselect v-model="input.crt" :attrs="{ value }" placeholder="--Pilih--"
                                    label="label" :options="d_crt" :searchable="true" track-by="label" mode="single"
                                    autocomplete="off">
                                </Multiselect>
                            </div>
                            <div class="column is-3 pt-1">
                                <span>Kelainan</span>
                                <Multiselect v-model="input.kelainanExtremitas" :attrs="{ value }"
                                    placeholder="--Pilih--" label="label" :options="d_tidakYa" :searchable="true"
                                    track-by="label" mode="single" autocomplete="off">
                                </Multiselect>
                                <VControl v-if="input.kelainanExtremitas == 2">
                                    <VInput type="text" class="input" v-model="input.kelainanExtremitasDetail"
                                        placeholder='Jelaskan...' />
                                </VControl>
                            </div>
                            <div class="column is-12 pb-0 pt-1">
                                <h1>Kulit</h1>
                            </div>
                            <div class="column is-3 pt-1">
                                <span>Warna</span>
                                <Multiselect v-model="input.warnaKulit" :attrs="{ value }" placeholder="--Pilih--"
                                    label="label" :options="d_warnaKulit" :searchable="true" track-by="label"
                                    mode="single" autocomplete="off">
                                </Multiselect>
                            </div>
                            <div class="column is-3 pt-1">
                                <span>Membran Mukosa</span>
                                <Multiselect v-model="input.membranMukosa" :attrs="{ value }" placeholder="--Pilih--"
                                    label="label" :options="d_membranMukosa" :searchable="true" track-by="label"
                                    mode="single" autocomplete="off">
                                </Multiselect>
                                <Multiselect v-if="input.membranMukosa == 3" v-model="input.stomatitisTurgorKulit"
                                    :attrs="{ value }" placeholder="--Pilih--" label="label" :options="d_turgorKulit"
                                    :searchable="true" track-by="label" mode="single" autocomplete="off">
                                </Multiselect>
                            </div>
                            <div class="column is-3 pt-1">
                                <span>Hematome</span>
                                <Multiselect v-model="input.hematome" :attrs="{ value }" placeholder="--Pilih--"
                                    label="label" :options="d_tidakYa" :searchable="true" track-by="label" mode="single"
                                    autocomplete="off">
                                </Multiselect>
                            </div>
                            <div class="column is-3 pt-1">
                                <span>Luka</span>
                                <Multiselect v-model="input.luka" :attrs="{ value }" placeholder="--Pilih--"
                                    label="label" :options="d_tidakYa" :searchable="true" track-by="label" mode="single"
                                    autocomplete="off">
                                </Multiselect>
                                <VControl v-if="input.luka == 2">
                                    <VInput type="text" class="input" v-model="input.lukaDetail"
                                        placeholder="Jelaskan..." />
                                </VControl>
                            </div>
                            <div class="column is-5 pt-1">
                                <div class="columns is-multiline">
                                    <div class="column is-3 pt-1">
                                        <span>Deformitas</span>
                                        <Multiselect v-model="input.Deformitas" :attrs="{ value }"
                                            placeholder="--Pilih--" label="label" :options="d_yaTidak"
                                            :searchable="true" track-by="label" mode="single" autocomplete="off">
                                        </Multiselect>
                                    </div>
                                    <div class="column is-9 pt-1">
                                        <span>Lokasi</span>
                                        <VControl>
                                            <VInput type="text" class="input" v-model="input.lokasiDeformitas" />
                                        </VControl>
                                    </div>
                                    <div class="column is-3 pt-1">
                                        <span>Contusio</span>
                                        <Multiselect v-model="input.Contusio" :attrs="{ value }" placeholder="--Pilih--"
                                            label="label" :options="d_yaTidak" :searchable="true" track-by="label"
                                            mode="single" autocomplete="off">
                                        </Multiselect>
                                    </div>
                                    <div class="column is-9 pt-1">
                                        <span>Lokasi</span>
                                        <VControl>
                                            <VInput type="text" class="input" v-model="input.lokasiContusio" />
                                        </VControl>
                                    </div>
                                    <div class="column is-3 pt-1">
                                        <span>Abrasi</span>
                                        <Multiselect v-model="input.Abrasi" :attrs="{ value }" placeholder="--Pilih--"
                                            label="label" :options="d_yaTidak" :searchable="true" track-by="label"
                                            mode="single" autocomplete="off">
                                        </Multiselect>
                                    </div>
                                    <div class="column is-9 pt-1">
                                        <span>Lokasi</span>
                                        <VControl>
                                            <VInput type="text" class="input" v-model="input.lokasiAbrasi" />
                                        </VControl>
                                    </div>
                                    <div class="column is-3 pt-1">
                                        <span>Laserasi</span>
                                        <Multiselect v-model="input.Laserasi" :attrs="{ value }" placeholder="--Pilih--"
                                            label="label" :options="d_yaTidak" :searchable="true" track-by="label"
                                            mode="single" autocomplete="off">
                                        </Multiselect>
                                    </div>
                                    <div class="column is-9 pt-1">
                                        <span>Lokasi</span>
                                        <VControl>
                                            <VInput type="text" class="input" v-model="input.lokasiLaserasi" />
                                        </VControl>
                                    </div>
                                    <div class="column is-3 pt-1">
                                        <span>Edema</span>
                                        <Multiselect v-model="input.Edema" :attrs="{ value }" placeholder="--Pilih--"
                                            label="label" :options="d_yaTidak" :searchable="true" track-by="label"
                                            mode="single" autocomplete="off">
                                        </Multiselect>
                                    </div>
                                    <div class="column is-9 pt-1">
                                        <span>Lokasi</span>
                                        <VControl>
                                            <VInput type="text" class="input" v-model="input.lokasiEdema" />
                                        </VControl>
                                    </div>
                                    <div class="column is-3 pt-1">
                                        <span>Dekubitus</span>
                                        <Multiselect v-model="input.Dekubitus" :attrs="{ value }"
                                            placeholder="--Pilih--" label="label" :options="d_yaTidak"
                                            :searchable="true" track-by="label" mode="single" autocomplete="off">
                                        </Multiselect>
                                    </div>
                                    <div class="column is-9 pt-1">
                                        <span>Lokasi</span>
                                        <VControl>
                                            <VInput type="text" class="input" v-model="input.lokasiDekubitus" />
                                        </VControl>
                                    </div>
                                    <div class="column is-3 pt-1">
                                        <span>Luka Bakar</span>
                                        <Multiselect v-model="input.LukaBakar" :attrs="{ value }"
                                            placeholder="--Pilih--" label="label" :options="d_yaTidak"
                                            :searchable="true" track-by="label" mode="single" autocomplete="off">
                                        </Multiselect>
                                    </div>
                                    <div class="column is-9 pt-1">
                                        <span>Lokasi</span>
                                        <VControl>
                                            <VInput type="text" class="input" v-model="input.lokasiLukaBakar" />
                                        </VControl>
                                    </div>
                                    <div class="column is-6 pt-1">
                                        <span>Grade</span>
                                        <VControl>
                                            <VInput type="text" class="input" v-model="input.grade" />
                                        </VControl>
                                    </div>
                                    <div class="column is-6 pt-1">
                                        <span>Presentase</span>
                                        <VControl>
                                            <VInput type="text" class="input" v-model="input.presentase" />
                                        </VControl>
                                    </div>
                                </div>
                            </div>
                            <div class="column is-7 pt-1" style="text-align: center;width:100% !important">
                                <ImgDraw elemenID="GambarTubuh" height="460" width="700"
                                    imageSrc="/images/simrs/outline-human-body.jpg" />
                            </div>
                            <div class="column is-4 pt-1">
                                <span>Tanda Kompartmen/DVT</span>
                                <Multiselect v-model="input.DVT" :attrs="{ value }" placeholder="--Pilih--"
                                    label="label" :options="d_tidakAda_ada" :searchable="true" track-by="label"
                                    mode="single" autocomplete="off">
                                </Multiselect>
                                <div v-if="input.DVT == 2">
                                    <span>Ditandai</span>
                                    <Multiselect v-model="input.DVT_ditandai" :attrs="{ value }" placeholder="--Pilih--"
                                        label="label" :options="d_DVT" :searchable="true" track-by="label" mode="single"
                                        autocomplete="off">
                                    </Multiselect>
                                </div>
                            </div>
                            <div class="column is-4 pt-1">
                                <span>Drop Foot</span>
                                <Multiselect v-model="input.dropFoot" :attrs="{ value }" placeholder="--Pilih--"
                                    label="label" :options="d_ada_tidakAda" :searchable="true" track-by="label"
                                    mode="single" autocomplete="off">
                                </Multiselect>
                            </div>
                            <div class="column is-12 pt-0 pb-0"></div>
                            <div class="column is-4 pt-1">
                                <h1>Anus & Genetalia</h1>
                                <span>Kelainan/masalah</span>
                                <Multiselect v-model="input.kelainanAnus" :attrs="{ value }" placeholder="--Pilih--"
                                    label="label" :options="d_tidakYa" :searchable="true" track-by="label" mode="single"
                                    autocomplete="off">
                                </Multiselect>
                                <VControl v-if="input.kelainanAnus == 2">
                                    <VInput type="text" class="input" v-model="input.kelainanAnusDetail"
                                        placeholder="Jelaskan..." />
                                </VControl>
                            </div>
                        </div>
                    </div>

                    <div class="column is-12 pt-0 pb-0">
                        <hr style="border-top: 1px dashed red;background-color:white" class="mb-1">
                    </div>

                    <div class="column">
                        <div class="columns is-multiline">
                            <div class="column is-12 pb-0">
                                <h1>ASESMEN NYERI</h1>
                            </div>
                            <div class="column is-4">
                                <h1>Skala nyeri (NRS/WBS/FLACC)</h1>
                                <VControl>
                                    <VInput type="text" class="input" v-model="input.TBSkalaNyeri_AN" />
                                </VControl>
                            </div>
                            <div class="column is-4">
                                <h1>Lokasi</h1>
                                <VControl>
                                    <VInput type="text" class="input" v-model="input.TBLokasi_AN" />
                                </VControl>
                            </div>
                            <div class="column is-4">
                                <h1>Frekuensi nyeri</h1>
                                <Multiselect v-model="input.SFrekuensiNyeri_AN" :attrs="{ value }"
                                    placeholder="--Pilih--" label="label" :options="d_frekuensiNyeri_AN"
                                    :searchable="true" track-by="label" mode="single" autocomplete="off">
                                </Multiselect>
                            </div>
                            <div class="column is-4 pt-1">
                                <h1>Lama nyeri</h1>
                                <VControl>
                                    <VInput type="text" class="input" v-model="input.TBLamaNyeri_AN" />
                                </VControl>
                            </div>
                            <div class="column is-4 pt-1">
                                <h1>Kualitas nyeri</h1>
                                <Multiselect v-model="input.SKualitasNyeri_AN" :attrs="{ value }"
                                    placeholder="--Pilih--" label="label" :options="d_kualitasNyeri_AN"
                                    :searchable="true" track-by="label" mode="single" autocomplete="off">
                                </Multiselect>
                            </div>
                            <div class="column is-4 pt-1" v-if="input.SKualitasNyeri_AN == 4">
                                <h1>&nbsp;</h1>
                                <VControl>
                                    <VInput type="text" class="input" v-model="input.TBLamaNyeriLainLain_AN"
                                        placeholder="Lain-lain..." />
                                </VControl>
                            </div>
                            <div class="column is-6 pt-1">
                                <h1>Faktor yang memperberat nyeri</h1>
                                <VControl>
                                    <VInput type="text" class="input" v-model="input.TBFaktorYangMemperberat_AN" />
                                </VControl>
                            </div>
                            <div class="column is-6 pt-1">
                                <h1>Faktor yang meringankan nyeri</h1>
                                <VControl>
                                    <VInput type="text" class="input" v-model="input.TBFaktorYangMeringankan_AN" />
                                </VControl>
                            </div>
                            <div class="column is-12 pt-1">
                                <h1>Fungsi Panca Indra</h1>
                            </div>
                            <div class="column is-3 pt-1">
                                <span>Penglihatan</span>
                                <Multiselect v-model="input.Penglihatan" :attrs="{ value }" placeholder="--Pilih--"
                                    label="label" :options="d_Penglihatan" :searchable="true" track-by="label"
                                    mode="single" autocomplete="off">
                                </Multiselect>
                            </div>
                            <div class="column is-3 pt-1">
                                <span>Pendengaran</span>
                                <Multiselect v-model="input.Pendengaran" :attrs="{ value }" placeholder="--Pilih--"
                                    label="label" :options="d_Pendengaran" :searchable="true" track-by="label"
                                    mode="single" autocomplete="off">
                                </Multiselect>
                            </div>
                            <div class="column is-3 pt-1">
                                <span>Pengecapan</span>
                                <Multiselect v-model="input.Pengecapan" :attrs="{ value }" placeholder="--Pilih--"
                                    label="label" :options="d_Pengecapan" :searchable="true" track-by="label"
                                    mode="single" autocomplete="off">
                                </Multiselect>
                            </div>
                            <div class="column is-3 pt-1">
                                <span>Penghidu</span>
                                <Multiselect v-model="input.Penghidu" :attrs="{ value }" placeholder="--Pilih--"
                                    label="label" :options="d_Penghidu" :searchable="true" track-by="label"
                                    mode="single" autocomplete="off">
                                </Multiselect>
                            </div>
                            <div class="column is-3 pt-1">
                                <span>Perasa</span>
                                <Multiselect v-model="input.Perasa" :attrs="{ value }" placeholder="--Pilih--"
                                    label="label" :options="d_Perasa" :searchable="true" track-by="label"
                                    mode="single" autocomplete="off">
                                </Multiselect>
                            </div>
                            <div class="column is-3 pt-1">
                                <span>Kemampuan Bicara</span>
                                <Multiselect v-model="input.KemampuanBicara" :attrs="{ value }" placeholder="--Pilih--"
                                    label="label" :options="d_KemampuanBicara" :searchable="true" track-by="label"
                                    mode="single" autocomplete="off">
                                </Multiselect>
                            </div>
                            <div class="column is-3 pt-1">
                                <span>Kemampuan Membaca</span>
                                <Multiselect v-model="input.KemampuanMembaca" :attrs="{ value }" placeholder="--Pilih--"
                                    label="label" :options="d_KemampuanMembaca" :searchable="true" track-by="label"
                                    mode="single" autocomplete="off">
                                </Multiselect>
                            </div>
                            <div class="column is-3 pt-1">
                                <span>Gangguan Memori</span>
                                <Multiselect v-model="input.GangguanMemori" :attrs="{ value }" placeholder="--Pilih--"
                                    label="label" :options="d_yaTidak" :searchable="true" track-by="label" mode="single"
                                    autocomplete="off">
                                </Multiselect>
                            </div>
                            <div class="column is-3 pt-1">
                                <span>Disorientasi Tempat</span>
                                <Multiselect v-model="input.DisorientasiTempat" :attrs="{ value }"
                                    placeholder="--Pilih--" label="label" :options="d_yaTidak" :searchable="true"
                                    track-by="label" mode="single" autocomplete="off">
                                </Multiselect>
                            </div>
                            <div class="column is-3 pt-1">
                                <span>Disorientasi Waktu</span>
                                <Multiselect v-model="input.DisorientasiWaktu" :attrs="{ value }"
                                    placeholder="--Pilih--" label="label" :options="d_yaTidak" :searchable="true"
                                    track-by="label" mode="single" autocomplete="off">
                                </Multiselect>
                            </div>
                            <div class="column is-3 pt-1">
                                <span>Disorientasi Orang</span>
                                <Multiselect v-model="input.DisorientasiOrang" :attrs="{ value }"
                                    placeholder="--Pilih--" label="label" :options="d_yaTidak" :searchable="true"
                                    track-by="label" mode="single" autocomplete="off">
                                </Multiselect>
                            </div>
                        </div>
                    </div>

                    <div class="column is-12 pt-0 pb-0">
                        <hr style="border-top: 1px dashed red;background-color:white" class="mb-1">
                    </div>

                    <div class="column">
                        <div class="columns is-multiline">
                            <div class="column is-12 pb-0">
                                <h1>KONDISI PSIKOLOGI, SOSIAL, EKONOMI DAN SPIRITUAL</h1>
                            </div>
                            <div class="column is-12 pb-0">
                                <h1>Gangguan Psikologis</h1>
                                <div class="columns is-multiline mt-0">
                                    <div v-for="item in d_kondisiPsikologis" :key="item.label" class="column is-3 pt-0">
                                        <VControl raw subcontrol>
                                            <VCheckbox class="p-0" color="primary" square :label="item.label"
                                                v-model="input[item.label + '_GP']" />
                                        </VControl>
                                    </div>
                                </div>
                                <!-- <Multiselect v-model="input.SKondisiPsikologis" :attrs="{ value }"
                                    placeholder="--Pilih--" label="label" :options="d_kondisiPsikologis"
                                    :searchable="true" track-by="label" mode="single" autocomplete="off">
                                </Multiselect> -->
                            </div>
                            <div class="column is-4">
                                <h1>Mengalami kekerasan fisik</h1>
                                <Multiselect v-model="input.SMengalamiKekerasanFisik" :attrs="{ value }"
                                    placeholder="--Pilih--" label="label" :options="d_mengalamiKekerasanFisik"
                                    :searchable="true" track-by="label" mode="single" autocomplete="off">
                                </Multiselect>
                            </div>
                            <div class="column is-4" v-if="input.SMengalamiKekerasanFisik == 2">
                                <h1>Jelaskan</h1>
                                <VControl>
                                    <VInput type="text" class="input" v-model="input.TBMengalamiKekerasanFisik" />
                                </VControl>
                            </div>
                            <div class="column is-4">
                                <h1>Keyakinan dan nilai pribadi</h1>
                                <VControl>
                                    <VInput type="text" class="input" v-model="input.TBKeyakinanDanNilaiPribadi" />
                                </VControl>
                            </div>
                            <div class="column is-4">
                                <h1>Pembiayaan Kesehatan</h1>
                                <Multiselect v-model="input.SPembiayaanKesehatan" :attrs="{ value }"
                                    placeholder="--Pilih--" label="label" :options="d_pembiayaanKesehatan"
                                    :searchable="true" track-by="label" mode="single" autocomplete="off">
                                </Multiselect>
                            </div>
                            <div class="column is-4" v-if="input.SPembiayaanKesehatan == 2">
                                <h1>&nbsp;</h1>
                                <VControl>
                                    <VInput type="text" class="input" v-model="input.TBAsuransiLainnya"
                                        placeholder="Asuransi..." />
                                </VControl>
                            </div>
                            <div class="column is-4">
                                <h1>Kebiasaan adat istiadat yang mempengaruhi kesehatan</h1>
                                <VControl>
                                    <VInput type="text" class="input" v-model="input.TBKebiasaanAdatIstiadat" />
                                </VControl>
                            </div>
                            <div class="column is-4">
                                <h1>Perlu rohaniawan</h1>
                                <Multiselect v-model="input.SPerluRohaniawan" :attrs="{ value }" placeholder="--Pilih--"
                                    label="label" :options="d_yaTidak" :searchable="true" track-by="label" mode="single"
                                    autocomplete="off">
                                </Multiselect>
                            </div>
                        </div>
                    </div>

                    <div class="column is-12 pt-0 pb-0">
                        <hr style="border-top: 1px dashed red;background-color:white" class="mt-0 mb-1">
                    </div>

                    <div class="column">
                        <div class="columns is-multiline">
                            <div class="column is-12 pb-0">
                                <h1>ASESMEN KEBUTUHAN INFORMASI DAN EDUKASI</h1>
                            </div>
                            <div class="column">
                                <h1>Lihat pada form kebutuhan informasi dan edukasi</h1>
                            </div>
                        </div>
                    </div>

                    <div class="column is-12 pt-0 pb-0">
                        <hr style="border-top: 1px dashed red;background-color:white" class="mb-1">
                    </div>

                    <div class="column">
                        <div class="columns is-multiline">
                            <div class="column is-12 pb-0">
                                <h1>SKRINNING NUTRISI & POLA NUTRISI METABOLIK</h1>
                            </div>
                            <div class="column is-3 pt-1">
                                <span>BB Sebelum Sakit</span>
                                <VField addons>
                                    <VControl expanded>
                                        <VInput type="text" class="input" v-model="input.BBSebelumSakit" />
                                    </VControl>
                                    <VControl class="field-addon-body">
                                        <VButton static>Kg</VButton>
                                    </VControl>
                                </VField>
                            </div>
                            <div class="column is-3 pt-1">
                                <span>BB Saat Sakit</span>
                                <VField addons>
                                    <VControl expanded>
                                        <VInput type="text" class="input" v-model="input.BBSaatSakit" />
                                    </VControl>
                                    <VControl class="field-addon-body">
                                        <VButton static>Kg</VButton>
                                    </VControl>
                                </VField>
                            </div>
                            <div class="column is-3 pt-1">
                                <span>Nutrisi Susu</span>
                                <Multiselect v-model="input.NutrisiSusu" :attrs="{ value }" placeholder="--Pilih--"
                                    label="label" :options="d_NutrisiSusu" :searchable="true" track-by="label"
                                    mode="single" autocomplete="off">
                                </Multiselect>
                                <VControl v-if="input.NutrisiSusu == 3">
                                    <VInput type="text" class="input" v-model="input.NutrisiSusuDetail" />
                                </VControl>
                            </div>
                            <div class="column is-3 pt-1">
                                <span>Kebiasaan Makan</span>
                                <Multiselect v-model="input.KebiasaanMakan" :attrs="{ value }" placeholder="--Pilih--"
                                    label="label" :options="d_KebiasaanMakan" :searchable="true" track-by="label"
                                    mode="single" autocomplete="off">
                                </Multiselect>
                            </div>
                            <div class="column is-3 pt-1">
                                <span>Makanan</span>
                                <Multiselect v-model="input.Makanan" :attrs="{ value }" placeholder="--Pilih--"
                                    label="label" :options="d_Makanan" :searchable="true" track-by="label" mode="single"
                                    autocomplete="off">
                                </Multiselect>
                                <VControl v-if="input.Makanan == 1">
                                    <VInput type="text" class="input" v-model="input.MakananDisukai"
                                        placeholder="Makanan disukai..." />
                                </VControl>
                                <VControl v-if="input.Makanan == 2">
                                    <VInput type="text" class="input" v-model="input.MakananTidakDisukai"
                                        placeholder="Makanan tidak disukai..." />
                                </VControl>
                            </div>
                            <div class="column is-3 pt-1">
                                <span>Nyeri Menelan</span>
                                <Multiselect v-model="input.NyeriMenelan" :attrs="{ value }" placeholder="--Pilih--"
                                    label="label" :options="d_adaTidak" :searchable="true" track-by="label"
                                    mode="single" autocomplete="off" class="mb-1">
                                </Multiselect>
                                <span>Sulit Menelan</span>
                                <Multiselect v-model="input.SulitMenelan" :attrs="{ value }" placeholder="--Pilih--"
                                    label="label" :options="d_adaTidak" :searchable="true" track-by="label"
                                    mode="single" autocomplete="off">
                                </Multiselect>
                            </div>
                            <div class="column is-3 pt-1">
                                <span>Diet Khusus</span>
                                <Multiselect v-model="input.DietKhusus" :attrs="{ value }" placeholder="--Pilih--"
                                    label="label" :options="d_tidakAda_ada" :searchable="true" track-by="label"
                                    mode="single" autocomplete="off" class="mb-1">
                                </Multiselect>
                                <VControl v-if="input.DietKhusus">
                                    <VInput type="text" class="input" v-model="input.DietKhususDetail" />
                                </VControl>
                            </div>
                            <div class="column is-3 pt-1">
                                <span>Makanan Pantangan</span>
                                <Multiselect v-model="input.MakananPantangan" :attrs="{ value }" placeholder="--Pilih--"
                                    label="label" :options="d_tidakAda_ada" :searchable="true" track-by="label"
                                    mode="single" autocomplete="off" class="mb-1">
                                </Multiselect>
                                <VControl v-if="input.MakananPantangan">
                                    <VInput type="text" class="input" v-model="input.MakananPantanganDetail" />
                                </VControl>
                            </div>
                            <div class="column is-3 pt-1">
                                <span>Mual</span>
                                <Multiselect v-model="input.Mual" :attrs="{ value }" placeholder="--Pilih--"
                                    label="label" :options="d_yaTidak" :searchable="true" track-by="label" mode="single"
                                    autocomplete="off" class="mb-1">
                                </Multiselect>
                                <VField addons>
                                    <VControl expanded>
                                        <VInput type="text" class="input" v-model="input.MualFrekuensi" />
                                    </VControl>
                                    <VControl class="field-addon-body">
                                        <VButton static>x/hari</VButton>
                                    </VControl>
                                </VField>
                            </div>
                            <div class="column is-3 pt-1">
                                <span>Muntah</span>
                                <Multiselect v-model="input.Muntah" :attrs="{ value }" placeholder="--Pilih--"
                                    label="label" :options="d_yaTidak" :searchable="true" track-by="label" mode="single"
                                    autocomplete="off" class="mb-1">
                                </Multiselect>
                                <VField addons>
                                    <VControl expanded>
                                        <VInput type="text" class="input" v-model="input.MuntahFrekuensi" />
                                    </VControl>
                                    <VControl class="field-addon-body">
                                        <VButton static>x/hari</VButton>
                                    </VControl>
                                </VField>
                            </div>
                            <div class="column is-12 pt-0 pb-0">
                                <hr style="border-top: 1px dashed lightgray;background-color:white" class="mt-0 mb-1">
                            </div>
                            <div class="column">
                                <div class="columns is-multiline">
                                    <div class="column is-4">
                                        <h1>Penurunan BB 6 bulan terakhir?</h1>
                                        <VField class="is-autocomplete-select">
                                            <VControl>
                                                <Multiselect v-model="input.penurunanbb" :attrs="{ value }"
                                                    placeholder="--Pilih--" label="label" :options="d_penurunanbb"
                                                    :searchable="true" track-by="label" mode="single"
                                                    autocomplete="off">
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
                                                    :searchable="true" track-by="label" mode="single"
                                                    autocomplete="off">
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
                                                    :searchable="true" track-by="label" mode="single"
                                                    autocomplete="off">
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
                                    <div class="column is-12 pt-0 pb-0">
                                        <div class="column is-12 pt-0 pb-0">
                                            <h1>Pasien dengan diagnosa khusus?</h1>
                                        </div>
                                        <div class="column is-4 columns pt-0">
                                            <div class="column is-6">
                                                <VField>
                                                    <VControl>
                                                        <VCheckbox class="fontcheckbox pt-1 pb-1"
                                                            v-model="input.diagnosakhusus" true-value="YA" label="Ya"
                                                            color="primary" circle />
                                                    </VControl>
                                                </VField>
                                            </div>
                                            <div class="column is-6">
                                                <VField>
                                                    <VControl>
                                                        <VCheckbox class="fontcheckbox pt-1 pb-1"
                                                            v-model="input.diagnosakhusus" true-value="TIDAK"
                                                            label="Tidak" color="primary" circle />
                                                    </VControl>
                                                </VField>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="column is-12 pt-0">
                                        <div class="column is-12 pt-0">
                                            <h1>Nilai</h1>
                                        </div>
                                        <div class="column is-12 pt-0 columns is-multiline">
                                            <div class="column is-4 p-0">
                                                <VField>
                                                    <VControl>
                                                        <VCheckbox class="fontcheckbox" v-model="input.nilai"
                                                            true-value="RISIKO RENDAH (MST 0-1)"
                                                            label="Risiko rendah (MST 0-1)" color="primary" circle
                                                            disabled />
                                                    </VControl>
                                                </VField>
                                            </div>
                                            <div class="column is-4 p-0">
                                                <VField>
                                                    <VControl>
                                                        <VCheckbox class="fontcheckbox" v-model="input.nilai"
                                                            true-value="RISIKO SEDANG (MST 2-3)"
                                                            label="Risiko sedang (MST 2-3)" color="primary" circle
                                                            disabled />
                                                    </VControl>
                                                </VField>
                                            </div>
                                            <div class="column is-4 p-0">
                                                <VField>
                                                    <VControl>
                                                        <VCheckbox class="fontcheckbox" v-model="input.nilai"
                                                            true-value="RISIKO TINGGI (MST 4-5)"
                                                            label="Risiko tinggi (MST 4-5)" color="primary" circle
                                                            disabled />
                                                    </VControl>
                                                </VField>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="column is-12 pt-0 pb-0">
                        <hr style="border-top: 1px dashed red;background-color:white" class="mt-0 mb-1">
                    </div>

                    <div class="column">
                        <div class="columns is-multiline">
                            <div class="column is-12 pb-0">
                                <h1>STATUS FUNGSIONAL</h1>
                            </div>
                            <div class="column is-12 pt-1">
                                <h1>1. Pola Aktivitas & Latihan</h1>
                            </div>
                            <div class="column is-3 pt-1">
                                <span>Rutinitas Mandi</span>
                                <Multiselect v-model="input.RutinitasMandi" :attrs="{ value }" placeholder="--Pilih--"
                                    label="label" :options="d_yaTidak" :searchable="true" track-by="label" mode="single"
                                    autocomplete="off">
                                </Multiselect>
                            </div>
                            <div class="column is-3 pt-1">
                                <span>Kebersihan Sehari-hari</span>
                                <Multiselect v-model="input.KebersihanSeharihari" :attrs="{ value }"
                                    placeholder="--Pilih--" label="label" :options="d_KebersihanSeharihari"
                                    :searchable="true" track-by="label" mode="single" autocomplete="off">
                                </Multiselect>
                            </div>
                            <div class="column is-3 pt-1">
                                <span>Kemampuan Perawatan Diri</span>
                                <Multiselect v-model="input.KemampuanPerawatanDiri" :attrs="{ value }"
                                    placeholder="--Pilih--" label="label" :options="d_KemampuanPerawatanDiri"
                                    :searchable="true" track-by="label" mode="single" autocomplete="off">
                                </Multiselect>
                            </div>
                            <div class="column is-3 pt-1" style="text-align: center;">
                                <span>Kekuatan Otot</span><br>
                                <!-- <TandaTangan :elemenID="'KekuatanOtot'" :width="'150'" :height="'150'" class="dek" /> -->
                                <ImgDraw elemenID="KekuatanOtot" height="160" width="170"
                                imageSrc="/images/simrs/kekuatan-otot.png" />
                                <VControl class="mt-1">
                                    <VInput type="text" class="input" v-model="input.KekuatanOtotDetail" />
                                </VControl>
                            </div>
                            <div class="column is-12 pt-0 pb-0">
                                <hr style="border-top: 1px dashed lightgray;background-color:white" class="mt-0 mb-1">
                            </div>
                            <div class="column is-12">
                                <div class="columns is-multiline">
                                    <div class="column is-3">
                                        <h1>Mengontrol BAB</h1>
                                        <VField class="is-autocomplete-select">
                                            <VControl>
                                                <Multiselect v-model="input.mengontrolbab" :attrs="{ value }"
                                                    placeholder="--Pilih--" label="label" :options="d_mengontrolbab"
                                                    :searchable="true" track-by="label" mode="single"
                                                    autocomplete="off">
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
                                                    :searchable="true" track-by="label" mode="single"
                                                    autocomplete="off">
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
                                                    :searchable="true" track-by="label" mode="single"
                                                    autocomplete="off">
                                                </Multiselect>
                                            </VControl>
                                        </VField>
                                    </div>
                                    <div class="column is-3">
                                        <h1>Penggunaan toilet</h1>
                                        <VField class="is-autocomplete-select">
                                            <VControl>
                                                <Multiselect v-model="input.toilet" :attrs="{ value }"
                                                    placeholder="--Pilih--" label="label" :options="d_toilet"
                                                    :searchable="true" track-by="label" mode="single"
                                                    autocomplete="off">
                                                </Multiselect>
                                            </VControl>
                                        </VField>
                                    </div>
                                    <div class="column is-3 pt-0">
                                        <h1>Makan</h1>
                                        <VField class="is-autocomplete-select">
                                            <VControl>
                                                <Multiselect v-model="input.makan" :attrs="{ value }"
                                                    placeholder="--Pilih--" label="label" :options="d_makan"
                                                    :searchable="true" track-by="label" mode="single"
                                                    autocomplete="off">
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
                                                    :searchable="true" track-by="label" mode="single"
                                                    autocomplete="off">
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
                                                    :searchable="true" track-by="label" mode="single"
                                                    autocomplete="off">
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
                                                    :searchable="true" track-by="label" mode="single"
                                                    autocomplete="off">
                                                </Multiselect>
                                            </VControl>
                                        </VField>
                                    </div>
                                    <div class="column is-3 pt-0">
                                        <h1>Naik turun tangga</h1>
                                        <VField class="is-autocomplete-select">
                                            <VControl>
                                                <Multiselect v-model="input.tangga" :attrs="{ value }"
                                                    placeholder="--Pilih--" label="label" :options="d_tangga"
                                                    :searchable="true" track-by="label" mode="single"
                                                    autocomplete="off">
                                                </Multiselect>
                                            </VControl>
                                        </VField>
                                    </div>
                                    <div class="column is-3 pt-0">
                                        <h1>Mandi</h1>
                                        <VField class="is-autocomplete-select">
                                            <VControl>
                                                <Multiselect v-model="input.mandi" :attrs="{ value }"
                                                    placeholder="--Pilih--" label="label" :options="d_mandi"
                                                    :searchable="true" track-by="label" mode="single"
                                                    autocomplete="off">
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
                                    <div class="columns is-multiline column is-12">
                                        <div class="column is-12 pb-0">
                                            <h1>Keterangan</h1>
                                        </div>
                                        <div class="column is-4">
                                            <VControl raw subcontrol>
                                                <VCheckbox class="p-0" color="primary" square
                                                    true-value="Ketergantungan total (0-4)"
                                                    label="Ketergantungan total (0-4)"
                                                    v-model="input.CBStatusFungsional" disabled />
                                            </VControl>
                                        </div>
                                        <div class="column is-4">
                                            <VControl raw subcontrol>
                                                <VCheckbox class="p-0" color="primary" square
                                                    true-value="Ketergantungan berat (5-8)"
                                                    label="Ketergantungan berat (5-8)"
                                                    v-model="input.CBStatusFungsional" disabled />
                                            </VControl>
                                        </div>
                                        <div class="column is-4">
                                            <VControl raw subcontrol>
                                                <VCheckbox class="p-0" color="primary" square
                                                    true-value="Ketergantungan sedang (9-11)"
                                                    label="Ketergantungan sedang (9-11)"
                                                    v-model="input.CBStatusFungsional" disabled />
                                            </VControl>
                                        </div>
                                        <div class="column is-4">
                                            <VControl raw subcontrol>
                                                <VCheckbox class="p-0" color="primary" square
                                                    true-value="Ketergantungan ringan(12-19)"
                                                    label="Ketergantungan ringan(12-19)"
                                                    v-model="input.CBStatusFungsional" disabled />
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
                            <div class="column is-12 pt-0 pb-0">
                                <hr style="border-top: 1px dashed lightgray;background-color:white" class="mt-0 mb-1">
                            </div>
                            <div class="column is-12 pt-1">
                                <h1>2. Pola Istirahat & Tidur</h1>
                            </div>
                            <div class="column is-3 pt-1">
                                <span>Rutinitas Tidur</span>
                                <Multiselect v-model="input.RutinitasTidur" :attrs="{ value }" placeholder="--Pilih--"
                                    label="label" :options="d_RutinitasTidur" :searchable="true" track-by="label"
                                    mode="single" autocomplete="off">
                                </Multiselect>
                            </div>
                            <div class="column is-3 pt-1">
                                <span>Waktu Tidur</span>
                                <Multiselect v-model="input.WaktuTidur" :attrs="{ value }" placeholder="--Pilih--"
                                    label="label" :options="d_WaktuTidur" :searchable="true" track-by="label"
                                    mode="single" autocomplete="off">
                                </Multiselect>
                            </div>
                            <div class="column is-3 pt-1">
                                <span>Kualitas Tidur</span>
                                <Multiselect v-model="input.KualitasTidur" :attrs="{ value }" placeholder="--Pilih--"
                                    label="label" :options="d_KualitasTidur" :searchable="true" track-by="label"
                                    mode="single" autocomplete="off">
                                </Multiselect>
                            </div>
                            <div class="column is-3 pt-1">
                                <span>Insomnia</span>
                                <Multiselect v-model="input.Insomnia" :attrs="{ value }" placeholder="--Pilih--"
                                    label="label" :options="d_adaTidak" :searchable="true" track-by="label"
                                    mode="single" autocomplete="off">
                                </Multiselect>
                            </div>
                            <div class="column is-3 pt-1">
                                <span>Merasa Lelah</span>
                                <Multiselect v-model="input.MerasaLelah" :attrs="{ value }" placeholder="--Pilih--"
                                    label="label" :options="d_yaTidak" :searchable="true" track-by="label" mode="single"
                                    autocomplete="off">
                                </Multiselect>
                            </div>
                            <div class="column is-12 pt-1">
                                <h1>3. Poli Eliminasi</h1>
                            </div>
                            <div class="column is-12 pt-1 pb-0">
                                <h1>Kebiasaan BAB</h1>
                            </div>
                            <div class="column is-3 pt-1">
                                <span>Frekuensi</span>
                                <VField addons>
                                    <VControl expanded>
                                        <VInput type="text" class="input" v-model="input.Frekuensi_BAB" />
                                    </VControl>
                                    <VControl class="field-addon-body">
                                        <VButton static>kali/hari</VButton>
                                    </VControl>
                                </VField>
                            </div>
                            <div class="column is-3 pt-1">
                                <span>Konsitensi</span>
                                <VControl>
                                    <VInput type="text" class="input" v-model="input.Konsitensi_BAB" />
                                </VControl>
                            </div>
                            <div class="column is-3 pt-1">
                                <span>Kesulitan BAB</span>
                                <Multiselect v-model="input.KesulitanBAB" :attrs="{ value }" placeholder="--Pilih--"
                                    label="label" :options="d_KesulitanBAB" :searchable="true" track-by="label"
                                    mode="single" autocomplete="off">
                                </Multiselect>
                                <VControl v-if="input.KesulitanBAB == 3">
                                    <VInput type="text" class="input" v-model="input.KesulitanBABDetail"
                                        placeholder="Sebutkan..." />
                                </VControl>
                            </div>
                            <div class="column is-3 pt-1">
                                <span>Penggunaan Obat Pencahar</span>
                                <Multiselect v-model="input.POP" :attrs="{ value }" placeholder="--Pilih--"
                                    label="label" :options="d_tidakAda_ada" :searchable="true" track-by="label"
                                    mode="single" autocomplete="off">
                                </Multiselect>
                                <div v-if="input.POP == 2">
                                    <span>Sebutkan</span>
                                    <VControl>
                                        <VInput type="text" class="input mb-1" v-model="input.POPDetail" />
                                    </VControl>
                                    <span>Frekuensi</span>
                                    <VControl>
                                        <VInput type="text" class="input" v-model="input.POPDetail" />
                                    </VControl>
                                </div>
                            </div>
                            <div class="column is-12 pt-1 pb-0">
                                <h1>Kebiasaan BAK</h1>
                            </div>
                            <div class="column is-3 pt-1">
                                <span>Frekuensi</span>
                                <VField addons>
                                    <VControl expanded>
                                        <VInput type="text" class="input" v-model="input.Frekuensi_BAK" />
                                    </VControl>
                                    <VControl class="field-addon-body">
                                        <VButton static>kali/hari</VButton>
                                    </VControl>
                                </VField>
                            </div>
                            <div class="column is-3 pt-1">
                                <span>Volume</span>
                                <VField addons>
                                    <VControl expanded>
                                        <VInput type="text" class="input" v-model="input.Volume_BAK" />
                                    </VControl>
                                    <VControl class="field-addon-body">
                                        <VButton static>ml</VButton>
                                    </VControl>
                                </VField>
                            </div>
                            <div class="column is-3 pt-1">
                                <span>Bau</span>
                                <VControl>
                                    <VInput type="text" class="input" v-model="input.Bau_BAK" />
                                </VControl>
                            </div>
                            <div class="column is-3 pt-1">
                                <span>Warna</span>
                                <Multiselect v-model="input.WarnaBAK" :attrs="{ value }" placeholder="--Pilih--"
                                    label="label" :options="d_WarnaBAK" :searchable="true" track-by="label"
                                    mode="single" autocomplete="off">
                                </Multiselect>
                            </div>
                            <div class="column is-3 pt-1">
                                <span>Kesulitan BAK</span>
                                <Multiselect v-model="input.KesulitanBAK" :attrs="{ value }" placeholder="--Pilih--"
                                    label="label" :options="d_KesulitanBAK" :searchable="true" track-by="label"
                                    mode="single" autocomplete="off">
                                </Multiselect>
                            </div>
                            <div class="column is-12 pt-1">
                                <h1>4. Pola seksual-reproduksi</h1>
                            </div>
                            <div class="column is-3 pt-1 pb-1">
                                <span>Masalah Menstruasi</span>
                                <Multiselect v-model="input.MasalahMenstruasi" :attrs="{ value }"
                                    placeholder="--Pilih--" label="label" :options="d_tidakYa" :searchable="true"
                                    track-by="label" mode="single" autocomplete="off">
                                </Multiselect>
                            </div>
                        </div>
                    </div>

                    <div class="column is-12 pt-0 pb-0">
                        <hr style="border-top: 1px dashed red;background-color:white" class="mt-0 mb-1">
                    </div>

                    <div class="column">
                        <div class="columns is-multiline">
                            <div class="column is-12 pb-0">
                                <h1>ASESMEN RISIKO JATUH</h1>
                            </div>
                            <!-- <div class="column">
                                <h1>Morse Fall Scale</h1>
                                <div class="columns is-multiline">
                                    <div class="column is-4">
                                        <VControl raw subcontrol>
                                            <VCheckbox class="p-0" color="primary" square true-value="Resiko Rendah"
                                                label="Resiko Rendah : 0-7" v-model="input.CBResikoJatuh" />
                                        </VControl>
                                    </div>
                                    <div class="column is-4">
                                        <VControl raw subcontrol>
                                            <VCheckbox class="p-0" color="primary" square true-value="Resiko Sedang"
                                                label="Resiko Sedang : 8-13" v-model="input.CBResikoJatuh" />
                                        </VControl>
                                    </div>
                                    <div class="column is-4">
                                        <VControl raw subcontrol>
                                            <VCheckbox class="p-0" color="primary" square true-value="Resiko Tinggi"
                                                label="Resiko Tinggi : > 14" v-model="input.CBResikoJatuh" />
                                        </VControl>
                                    </div>
                                </div>
                            </div> -->
                            <div class="column">
                                <h1>Humty Dumpty</h1>
                                <div class="columns is-multiline">
                                    <div class="column is-4">
                                        <VControl raw subcontrol>
                                            <VCheckbox class="p-0" color="primary" square true-value="Resiko Rendah"
                                                label="Resiko Rendah : 7-11" v-model="input.CBResiko_HD" />
                                        </VControl>
                                    </div>
                                    <div class="column is-4">
                                        <VControl raw subcontrol>
                                            <VCheckbox class="p-0" color="primary" square true-value="Resiko Tinggi"
                                                label="Resiko Tinggi : ≥ 12" v-model="input.CBResiko_HD" />
                                        </VControl>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="column is-12 pt-0 pb-0">
                        <hr style="border-top: 1px dashed red;background-color:white" class="mt-0 mb-1">
                    </div>

                    <div class="column">
                        <div class="columns is-multiline">
                            <div class="column is-12 pb-0">
                                <h1>RIWAYAT PENGGUNAAN OBAT</h1>
                            </div>
                            <div class="column pt-1">
                                <div class="columns is-multiline mt-0">
                                    <div class="column is-4 pt-1">
                                        <VField>
                                            <VTextarea rows="1" v-model="input.TARiwayatPenggunaanObat_1"
                                                placeholder="1...">
                                            </VTextarea>
                                        </VField>
                                    </div>
                                    <div class="column is-4 pt-1">
                                        <VField>
                                            <VTextarea rows="1" v-model="input.TARiwayatPenggunaanObat_2"
                                                placeholder="2...">
                                            </VTextarea>
                                        </VField>
                                    </div>
                                    <div class="column is-4 pt-1">
                                        <VField>
                                            <VTextarea rows="1" v-model="input.TARiwayatPenggunaanObat_3"
                                                placeholder="3...">
                                            </VTextarea>
                                        </VField>
                                    </div>
                                    <div class="column is-4 pt-1">
                                        <VField>
                                            <VTextarea rows="1" v-model="input.TARiwayatPenggunaanObat_4"
                                                placeholder="4...">
                                            </VTextarea>
                                        </VField>
                                    </div>
                                    <div class="column is-4 pt-1">
                                        <VField>
                                            <VTextarea rows="1" v-model="input.TARiwayatPenggunaanObat_5"
                                                placeholder="5...">
                                            </VTextarea>
                                        </VField>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="column is-12 pt-0 pb-0">
                        <hr style="border-top: 1px dashed red;background-color:white" class="mt-0 mb-1">
                    </div>

                    <div class="column">
                        <div class="columns is-multiline">
                            <div class="column is-12 pb-0">
                                <h1>RENCANA PEMULANGAN PASIEN</h1>
                            </div>
                            <div class="column">
                                <div class="columns is-multiline">
                                    <div class="column is-3">
                                        <Multiselect v-model="input.SRencanaPP" :attrs="{ value }"
                                            placeholder="--Pilih--" label="label" :options="d_perluTidakPerlu"
                                            :searchable="true" track-by="label" mode="single" autocomplete="off">
                                        </Multiselect>
                                    </div>
                                    <div class="column is-3" v-if="input.SRencanaPP == 1">
                                        <Multiselect v-model="input.SPerlu_RPP" :attrs="{ value }"
                                            placeholder="--Pilih--" label="label" :options="d_Perlu_RPP"
                                            :searchable="true" track-by="label" mode="single" autocomplete="off">
                                        </Multiselect>
                                    </div>
                                    <div class="column is-6" v-if="input.SPerlu_RPP == 1">
                                        <VControl>
                                            <VInput type="text" class="input" v-model="input.Keterangan_UL"
                                                placeholder="Keterangan..." />
                                        </VControl>
                                    </div>
                                    <div class="column is-6" v-if="input.SPerlu_RPP == 2">
                                        <VControl>
                                            <VInput type="text" class="input" v-model="input.Keterangan_Keterbatasan"
                                                placeholder="Keterangan..." />
                                        </VControl>
                                    </div>
                                    <div class="column is-6" v-if="input.SPerlu_RPP == 3">
                                        <VControl>
                                            <VInput type="text" class="input" v-model="input.Keterangan_MP"
                                                placeholder="Keterangan..." />
                                        </VControl>
                                    </div>
                                    <div class="column is-6" v-if="input.SPerlu_RPP == 4">
                                        <VControl>
                                            <VInput type="text" class="input" v-model="input.Keterangan_MB"
                                                placeholder="Keterangan..." />
                                        </VControl>
                                    </div>
                                    <div class="column is-12 pt-0 pb-0"></div>
                                    <!-- <div class="column is-6">
                                        <VControl>
                                            <VInput type="text" class="input" v-model="input.TBRPP_1"
                                                placeholder="1..." />
                                        </VControl>
                                    </div>
                                    <div class="column is-6">
                                        <VControl>
                                            <VInput type="text" class="input" v-model="input.TBRPP_2"
                                                placeholder="2..." />
                                        </VControl>
                                    </div>
                                    <div class="column is-6">
                                        <VControl>
                                            <VInput type="text" class="input" v-model="input.TBRPP_3"
                                                placeholder="3..." />
                                        </VControl>
                                    </div>
                                    <div class="column is-6">
                                        <VControl>
                                            <VInput type="text" class="input" v-model="input.TBRPP_4"
                                                placeholder="4..." />
                                        </VControl>
                                    </div> -->
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="column is-12 pt-0 pb-0">
                        <hr style="border-top: 1px dashed red;background-color:white" class="mt-0 mb-1">
                    </div>

                    <div class="column">
                        <div class="columns is-multiline">
                            <div class="column is-12 pb-0">
                                <h1>DIAGNOSA KEPERAWATAN</h1>
                            </div>
                            <div class="column">
                                <div class="columns is-multiline">
                                    <div class="column is-4 pt-1">
                                        <VField>
                                            <VTextarea rows="1" v-model="input.TADiagnosaKeperawatan_1"
                                                placeholder="1...">
                                            </VTextarea>
                                        </VField>
                                    </div>
                                    <div class="column is-4 pt-1">
                                        <VField>
                                            <VTextarea rows="1" v-model="input.TADiagnosaKeperawatan_2"
                                                placeholder="2...">
                                            </VTextarea>
                                        </VField>
                                    </div>
                                    <div class="column is-4 pt-1">
                                        <VField>
                                            <VTextarea rows="1" v-model="input.TADiagnosaKeperawatan_3"
                                                placeholder="3...">
                                            </VTextarea>
                                        </VField>
                                    </div>
                                    <div class="column is-4 pt-1">
                                        <VField>
                                            <VTextarea rows="1" v-model="input.TADiagnosaKeperawatan_4"
                                                placeholder="4...">
                                            </VTextarea>
                                        </VField>
                                    </div>
                                    <div class="column is-4 pt-1">
                                        <VField>
                                            <VTextarea rows="1" v-model="input.TADiagnosaKeperawatan_5"
                                                placeholder="5...">
                                            </VTextarea>
                                        </VField>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="column is-12 pt-0 pb-0">
                        <hr style="border-top: 1px dashed red;background-color:white" class="mt-0 mb-1">
                    </div>

                    <div class="column">
                        <div class="columns is-multiline">
                            <div class="column is-12 pb-0">
                                <h1>RENCANA KEPERAWATAN</h1>
                            </div>
                            <div class="column">
                                <h1>Lihat pada form Rencana keperawatan</h1>
                            </div>
                        </div>
                    </div>

                    <div class="column is-12 pt-0 pb-0">
                        <hr style="border-top: 1px dashed red;background-color:white" class="mt-0 mb-1">
                    </div>

                    <div class="column">
                        <div class="columns is-multiline">
                            <div class="column is-12 pb-0">
                                <h1>PROSEDUR INVASIF</h1>
                            </div>
                            <div class="column">
                                <div class="columns">
                                    <div class="column is-4">
                                        <VControl raw subcontrol>
                                            <VCheckbox class="p-0" color="primary" square true-value="Infus Intra Vena"
                                                label="Infus Intra Vena" v-model="input.CBInfusIntraVena" />
                                        </VControl>
                                    </div>
                                    <div class="column is-4">
                                        <h1>Dipasang di</h1>
                                        <VControl>
                                            <VInput type="text" class="input" v-model="input.TBInfusIntraVena" />
                                        </VControl>
                                    </div>
                                    <div class="column is-4">
                                        <h1>Tanggal</h1>
                                        <VDatePicker v-model="input.DInfusIntraVena" mode="date" trim-weeks
                                            :max-date="new Date()">
                                            <template #default="{ inputValue, inputEvents }">
                                                <VControl icon="feather:calendar" fullwidth>
                                                    <VInput :value="inputValue" v-on="inputEvents" />
                                                </VControl>
                                            </template>
                                        </VDatePicker>
                                    </div>
                                </div>
                                <div class="columns">
                                    <div class="column is-4">
                                        <VControl raw subcontrol>
                                            <VCheckbox class="p-0" color="primary" square
                                                true-value="Central Line (CVC)" label="Central Line (CVC)"
                                                v-model="input.CBCentralLine" />
                                        </VControl>
                                    </div>
                                    <div class="column is-4">
                                        <h1>Dipasang di</h1>
                                        <VControl>
                                            <VInput type="text" class="input" v-model="input.TBCentralLine" />
                                        </VControl>
                                    </div>
                                    <div class="column is-4">
                                        <h1>Tanggal</h1>
                                        <VDatePicker v-model="input.DCentralLine" mode="date" trim-weeks
                                            :max-date="new Date()">
                                            <template #default="{ inputValue, inputEvents }">
                                                <VControl icon="feather:calendar" fullwidth>
                                                    <VInput :value="inputValue" v-on="inputEvents" />
                                                </VControl>
                                            </template>
                                        </VDatePicker>
                                    </div>
                                </div>
                                <div class="columns">
                                    <div class="column is-4">
                                        <VControl raw subcontrol>
                                            <VCheckbox class="p-0" color="primary" square true-value="Dower chateter"
                                                label="Dower chateter" v-model="input.CBDowerChateter" />
                                        </VControl>
                                    </div>
                                    <div class="column is-4">
                                        <h1>Dipasang di</h1>
                                        <VControl>
                                            <VInput type="text" class="input" v-model="input.TBDowerChateter" />
                                        </VControl>
                                    </div>
                                    <div class="column is-4">
                                        <h1>Tanggal</h1>
                                        <VDatePicker v-model="input.DDowerChateter" mode="date" trim-weeks
                                            :max-date="new Date()">
                                            <template #default="{ inputValue, inputEvents }">
                                                <VControl icon="feather:calendar" fullwidth>
                                                    <VInput :value="inputValue" v-on="inputEvents" />
                                                </VControl>
                                            </template>
                                        </VDatePicker>
                                    </div>
                                </div>
                                <div class="columns">
                                    <div class="column is-4">
                                        <VControl raw subcontrol>
                                            <VCheckbox class="p-0" color="primary" square true-value="Selang NGT"
                                                label="Selang NGT" v-model="input.CBSelangNGT" />
                                        </VControl>
                                    </div>
                                    <div class="column is-4">
                                        <h1>Dipasang di</h1>
                                        <VControl>
                                            <VInput type="text" class="input" v-model="input.TBSelangNGT" />
                                        </VControl>
                                    </div>
                                    <div class="column is-4">
                                        <h1>Tanggal</h1>
                                        <VDatePicker v-model="input.DSelangNGT" mode="date" trim-weeks
                                            :max-date="new Date()">
                                            <template #default="{ inputValue, inputEvents }">
                                                <VControl icon="feather:calendar" fullwidth>
                                                    <VInput :value="inputValue" v-on="inputEvents" />
                                                </VControl>
                                            </template>
                                        </VDatePicker>
                                    </div>
                                </div>
                                <div class="columns">
                                    <div class="column is-4">
                                        <VControl raw subcontrol>
                                            <VCheckbox class="p-0" color="primary" square true-value="Trakeostomy"
                                                label="Trakeostomy" v-model="input.CBTrakeostomy" />
                                        </VControl>
                                    </div>
                                    <div class="column is-4">
                                        <h1>Dipasang di</h1>
                                        <VControl>
                                            <VInput type="text" class="input" v-model="input.TBTrakeostomy" />
                                        </VControl>
                                    </div>
                                    <div class="column is-4">
                                        <h1>Tanggal</h1>
                                        <VDatePicker v-model="input.DTrakeostomy" mode="date" trim-weeks
                                            :max-date="new Date()">
                                            <template #default="{ inputValue, inputEvents }">
                                                <VControl icon="feather:calendar" fullwidth>
                                                    <VInput :value="inputValue" v-on="inputEvents" />
                                                </VControl>
                                            </template>
                                        </VDatePicker>
                                    </div>
                                </div>
                                <div class="columns">
                                    <div class="column is-4">
                                        <VControl raw subcontrol>
                                            <VCheckbox class="p-0" color="primary" square true-value="EET/Ventilator"
                                                label="EET/Ventilator" v-model="input.CBVentilator" />
                                        </VControl>
                                    </div>
                                    <div class="column is-4">
                                        <h1>Dipasang di</h1>
                                        <VControl>
                                            <VInput type="text" class="input" v-model="input.TBVentilator" />
                                        </VControl>
                                    </div>
                                    <div class="column is-4">
                                        <h1>Tanggal</h1>
                                        <VDatePicker v-model="input.DVentilator" mode="date" trim-weeks
                                            :max-date="new Date()">
                                            <template #default="{ inputValue, inputEvents }">
                                                <VControl icon="feather:calendar" fullwidth>
                                                    <VInput :value="inputValue" v-on="inputEvents" />
                                                </VControl>
                                            </template>
                                        </VDatePicker>
                                    </div>
                                </div>
                                <div class="columns">
                                    <div class="column is-4">
                                        <VControl raw subcontrol>
                                            <VCheckbox class="p-0" color="primary" square true-value="Lain-lain"
                                                label="Lain-lain" v-model="input.CBLainlain_PI" />
                                        </VControl>
                                        <VControl>
                                            <VInput type="text" class="input" v-model="input.TBLainlain_PI" />
                                        </VControl>
                                    </div>
                                    <div class="column is-4">
                                        <h1>Dipasang di</h1>
                                        <VControl>
                                            <VInput type="text" class="input" v-model="input.TBLainlain_dipasang_PI" />
                                        </VControl>
                                    </div>
                                    <div class="column is-4">
                                        <h1>Tanggal</h1>
                                        <VDatePicker v-model="input.DLainlain_PI" mode="date" trim-weeks
                                            :max-date="new Date()">
                                            <template #default="{ inputValue, inputEvents }">
                                                <VControl icon="feather:calendar" fullwidth>
                                                    <VInput :value="inputValue" v-on="inputEvents" />
                                                </VControl>
                                            </template>
                                        </VDatePicker>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="column is-12 pt-0">
                        <hr style="border-top: 1px dashed red;background-color:white" class="mt-0 mb-1">
                    </div>

                    <div class="columns">
                        <div class="column is-8"></div>
                        <div class="column is-4">
                            <VField label="Garut">
                                <VDatePicker v-model="input.DTttd" mode="datetime" trim-weeks :max-date="new Date()">
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
                                    <AutoComplete v-model="input.DDPerawat" :suggestions="d_Pegawai"
                                        @complete="fetchPegawai($event)" :optionLabel="'label'" :dropdown="true"
                                        :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                                        :field="'label'" class="mt-2" />
                                </VControl>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script setup lang="ts">
import * as H from '/@src/utils/appHelper'
import { useWindowScroll } from '@vueuse/core'
import { useApi } from '/@src/composable/useApi'
import { h, reactive, ref, computed, watch, onBeforeMount } from 'vue'
import { useRoute, useRouter, onBeforeRouteLeave } from 'vue-router'
import { useHead } from '@vueuse/head'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useUserSession } from '/@src/stores/userSession'
import TandaTangan from '../page-emr-plugins/tanda-tangan.vue'
import ButtonEmr from '../page-emr-plugins/button-emr.vue'
import AutoComplete from 'primevue/autocomplete';
import ImgDraw from '../page-emr-plugins/img-draw.vue'

useHead({ title: 'Asesmen Awal Keperawatan Pasien Anak Rawat Inap - ' + import.meta.env.VITE_PROJECT })
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)
let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pd as string
let norec_emr = useRoute().query.norec_emr as string
const route = useRoute()
const user = useUserSession().getUser().pegawai;
const pasien: any = ref({})
const dataTTD: any = ref([])
const d_Pegawai: any = ref([])
const loadData: any = ref(true)
const listTemplate: any = ref([])
const showModalTemplate: any = ref(false)
const listTemplateFix: any = ref([])
const showModalTemplateFix: any = ref(false)
const isLoading = ref(false)
const isAktive = ref()
const router = useRouter()
const selectedRegistrasi: any = ref({})
const isRemoveTAB: any = ref(false)
const TAB_ACTIVE: any = ref('Dashboard');
const TAB_URL = ref('')
const TAB_ACTIVE_ROUTER: any = ref(null)
const TAB_ROUTER_DEFAULT = ref('module-emr-profile-pasien-page-emr-not-found')
const COLLECTION: any = ref('AsesmenAwalKeperawatanAnakRawatInap') //table mongodb
const NOREC_EMRPASIEN: any = ref('')
const { y } = useWindowScroll()
const isStuck = computed(() => {
    return y.value > 30
})

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
const item: any = reactive({})
const input: any = ref({
    DTanggalMasuk: props.registrasi.tglregistrasi,
    DTttd: new Date(),
    DTanggalAsesmenAwal: new Date(),
    alamatPasien: props.pasien.alamatlengkap
})
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

const loadGambar2 = async (element_id: string, value: string) => {
    let sigCanvas: any = document.getElementById(element_id);
    if (sigCanvas) {
        let context = sigCanvas.getContext("2d");
        context.clearRect(0, 0, sigCanvas.width, sigCanvas.height);
        let imagess = value
        let background = new Image();
        background.src = imagess
        background.onload = function () {
            context.drawImage(background, 0, 0, 170, 160);
        }
    }
}
const loadRiwayat = async () => {
    isLoading.value = true
    let response = await useApi().get(`/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`)
    if (response.length) {
        input.value = response[0] //set ke inputan
        if (NOREC_EMRPASIEN.value == '') {
            NOREC_EMRPASIEN.value = response[0].emrpasienfk
        }
        dataTTD.value = response[0]
        H.tandaTangan().set("TTDPerawat", dataTTD.value.TTDPerawat)
        H.tandaTangan().set("KekuatanOtot", dataTTD.value.KekuatanOtot)
        await loadGambar("GambarTubuh", dataTTD.value.GambarTubuh)
        await loadGambar2("KekuatanOtot", dataTTD.value.KekuatanOtot)
    } else {
        input.value.DDPerawat = { label: user.namaLengkap, value: user.id }
        const ttv = await useApi().get(`emr/get-data-exist?nocmfk=${ID_PASIEN}&norec_pd=${NOREC_PD}`)
        const rj = await useApi().get("emr/auto-fill?nocmfk=" + ID_PASIEN + "&norec_pd=" + NOREC_PD + "&collection=PengkajianResikoJatuhDewasa" + `&field=jumlahNilaiSN`)
        if (ttv) {
            input.value.GCSe = ttv.GCSe;
            input.value.GCSv = ttv.GCSv;
            input.value.GCSm = ttv.GCSm;
            input.value.TBStekananDarah = ttv.tekananDarah;
            input.value.TBSnadi = ttv.nadi
            input.value.TBSrespirasi = ttv.pernapasan
            input.value.TBSsuhu = ttv.suhu
            input.value.TBSSaO2 = ttv.SPO2
            input.value.TBSBeratBadan = ttv.beratBadan
            input.value.TBStinggiBadan = ttv.tinggiBadan
        }

        if (rj) {
            let data = rj.jumlahNilaiSN;
            if (data <= 7 && data >= 0) {
                input.value.CBResikoJatuh = 'Resiko Rendah'
            } else if (data <= 13 && data >= 8) {
                input.value.CBResikoJatuh = 'Resiko Sedang'
            } else if (data >= 14) {
                input.value.CBResikoJatuh = 'Resiko Tinggi'
            }
        }
    }
    isLoading.value = false
}

const simpan = () => {
    let ID = input.value.id ? input.value.id : ''
    let object: any = {}

    object = input.value
    object.nocm = pasien.value.nocm
    if (object.hasOwnProperty('namatemplate')) {
        delete object.namatemplate
    }
    object['TTDPerawat'] = H.tandaTangan().get("TTDPerawat");
    object['KekuatanOtot'] = H.tandaTangan().get("KekuatanOtot");
    object['GambarTubuh'] = H.tandaTangan().get("GambarTubuh");
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
    useApi().post(`/emr/simpan-emr`, json).then((response: any) => {
        isLoading.value = false
        loadRiwayat();
    }).catch((e: any) => {
        isLoading.value = false
    })
}
const fetchPasien = () => {
    pasien.value = props.pasien
    pasien.value.registrasi = props.registrasi
    NOREC_EMRPASIEN.value = norec_emr ? norec_emr : ''
}
const fetchPegawai = async (filter: any) => {
    await useApi().get(`emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`).then((response) => {
        d_Pegawai.value = response
    })
}

const simpanTemplate = () => {
    if (!input.value.namatemplate) {
        H.alert('warning', "Nama Template wajib diisi")
        console.log()
        return;
    }
    let ID = input.id ? input.id : ''
    let object: any = {}

    object = input.value
    object.pasien = H.setObjectPasien(props.pasien)
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

    useApi().post(`/emr/simpan-emr-template`, json).then((response: any) => {
        isLoading.value = false
        input.value.namatemplate = null
    }).catch((e: any) => {
        isLoading.value = false
    })
}
const pilihTemplate = async (index: any) => {
    isLoading.value = true
    useApi().get(`/emr/get-emr-history-terakhir?nocmfk=${ID_PASIEN}&collection=${COLLECTION.value}`).then((responselast: any) => {
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
    input.value = response
    delete input.value['id']
    delete input.value['_id']
    input.value.namatemplate = null
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
    useApi().get(`/emr/get-emr-template?collection=${COLLECTION.value}`).then((responselast: any) => {
        isLoading.value = false
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

onBeforeMount(async () => {
    try {
        await loadRiwayat()
        await fetchPasien()
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

// ===== ARRAY =====
const d_Perlu_RPP: any = ref([
    { value: 1, label: 'Usia lanjut (> 60 tahun) dengan gangguan daya ingat Bayi BBLR,' },
    { value: 2, label: 'Keterbatasan / gangguan mobilitas' },
    { value: 3, label: 'Memerlukan pertolongan untuk melanjutkan terapi dan perawatan terus menerus' },
    { value: 4, label: 'Memerlukan bantuan melakukan kegiatan sehari-hari' }
])
const d_perluTidakPerlu: any = ref([
    { value: 1, label: 'Perlu' },
    { value: 2, label: 'Tidak Perlu' }
])
const d_tidakAda: any = ref([
    { value: 1, label: 'Tidak' },
    { value: 2, label: 'Ada' }
])
const d_adaTidak: any = ref([
    { value: 1, label: 'Ada' },
    { value: 2, label: 'Tidak' }
])
const d_tidakYa: any = ref([
    { value: 1, label: 'Tidak' },
    { value: 2, label: 'Ya' }
])
const d_yaTidak: any = ref([
    { value: 1, label: 'Ya' },
    { value: 2, label: 'Tidak' }
])
const d_tidakAda_ada: any = ref([
    { value: 1, label: 'Tidak ada' },
    { value: 2, label: 'Ada' }
])
const d_ada_tidakAda: any = ref([
    { value: 1, label: 'Ada' },
    { value: 2, label: 'Tidak ada' }
])
const d_tempatRujukan: any = ref([
    { value: 1, label: 'Rumah Sakit' },
    { value: 2, label: 'Puskesmas' },
    { value: 3, label: 'dr.' },
    { value: 4, label: 'Lainnya' }
]);
const d_kualitasNadi: any = ref([
    { value: 1, label: 'Kuat' },
    { value: 2, label: 'Lemah' },
    { value: 3, label: 'Tidak teraba' }
]);
const d_penurunanbb: any = ref([
    { value: 0, label: 'Tidak' },
    { value: 2, label: 'Tidak Yakin' }
]);
const d_polaNafas: any = ref([
    { value: 1, label: 'Tidak Teratur' },
    { value: 2, label: 'Teratur' }
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
const d_Kesadaran: any = ref([
    { value: 1, label: 'Composmentis' },
    { value: 2, label: 'Apatis' },
    { value: 3, label: 'Somnolen' },
    { value: 4, label: '>Somnolen' },
    { value: 5, label: 'Coma' }
]);
const d_mengontrolbab: any = ref([
    { value: 0, label: 'Inkontinen/tidak teratur (perlu enema)' },
    { value: 1, label: 'Kadang inkontinen (1xseminggu)' },
    { value: 2, label: 'Kontinen teratur' }
]);
const d_mengontrolbak: any = ref([
    { value: 0, label: 'Inkontinen/pakai kateter dan tidak terkontrol' },
    { value: 1, label: 'Kadang inkontinen (max 1x24 jam)' },
    { value: 2, label: 'Mandiri' }
]);
const d_bersihdiri: any = ref([
    { value: 0, label: 'Butuh pertolongan orang lain' },
    { value: 1, label: 'Mandiri' }
]);
const d_toilet: any = ref([
    { value: 0, label: 'Tergantung pertolongan orang lain' },
    { value: 1, label: 'Perlu pertolongan pada beberapa aktivitas terapi dan dapat mengerjakan sendiri beberapa aktivitas lain' },
    { value: 2, label: 'Mandiri' }
]);
const d_makan: any = ref([
    { value: 0, label: 'Tidak mampu' },
    { value: 1, label: 'Perlu seseorang menolong memotong makanan' },
    { value: 2, label: 'Mandiri' }
]);
const d_berpindahtt: any = ref([
    { value: 0, label: 'Tidak Mampu' },
    { value: 1, label: 'Perlu banyak bantuan untuk bisa duduk (2 orang)' },
    { value: 2, label: 'Bantuan 1 orang' },
    { value: 3, label: 'Mandiri' }
]);
const d_mobilisasi: any = ref([
    { value: 0, label: 'Tidak Mampu' },
    { value: 1, label: 'Dengan kursi roda' },
    { value: 2, label: 'Bantuan 1 orang' },
    { value: 3, label: 'Mandiri' }
]);
const d_berpakaian: any = ref([
    { value: 0, label: 'Tergantung orang lain' },
    { value: 1, label: 'Sebagian dibantu (misal mengancing baju)' },
    { value: 2, label: 'Mandiri' }
]);
const d_tangga: any = ref([
    { value: 0, label: 'Tidak Mampu' },
    { value: 1, label: 'Butuh Pertolongan' },
    { value: 2, label: 'Mandiri' }
]);
const d_mandi: any = ref([
    { value: 0, label: 'Teragantung orang lain' },
    { value: 1, label: 'Mandiri' }
]);
const d_keadaanumum: any = ref([
    { value: 1, label: 'Baik' },
    { value: 2, label: 'Sedang' },
    { value: 3, label: 'Lemah' },
    { value: 4, label: 'Jelek' }
])
const d_rujukan: any = ref([
    { value: 1, label: 'Ya' },
    { value: 2, label: 'Tidak' },
    { value: 3, label: 'Datang Sendiri' },
    { value: 4, label: 'Diantar' }
])
const d_allo: any = ref([
    { value: 1, label: 'Suami/Istri' },
    { value: 2, label: 'Orang Tua' },
    { value: 3, label: 'Anak' },
    { value: 4, label: 'Pasien' },
    { value: 5, label: 'Lainnya' }
])
const d_kualitasNyeri_AN: any = ref([
    { value: 1, label: 'Tumpul' },
    { value: 2, label: 'Tajam' },
    { value: 3, label: 'Panas/terbakar' },
    { value: 4, label: 'Lain-lain' }
])
const d_frekuensiNyeri_AN: any = ref([
    { value: 1, label: 'Jarang' },
    { value: 2, label: 'Hilang timbul' },
    { value: 3, label: 'Terus menerus' }
])
const d_kondisiPsikologis: any = ref([
    { value: 1, label: 'Gelisah' },
    { value: 2, label: 'Takut' },
    { value: 3, label: 'Sedih' },
    { value: 4, label: 'Rendah diri' },
    { value: 5, label: 'Acuh tak acuh' },
    { value: 6, label: 'Mudah tersinggung' },
    { value: 7, label: 'Menarik diri' }
])
const d_statusPernikahan: any = ref([
    { value: 1, label: 'Singel' },
    { value: 2, label: 'Menikah' },
    { value: 3, label: 'Bercerai' }
])
const d_pembiayaanKesehatan: any = ref([
    { value: 1, label: 'Biaya sendiri/keluarga' },
    { value: 2, label: 'Asuransi lainnya' }
])
const d_dukunganSosial: any = ref([
    { value: 1, label: 'Suami' },
    { value: 2, label: 'Orang tua' },
    { value: 3, label: 'Keluarga' },
    { value: 4, label: 'Lainnya' }
])
const d_kebiasaanIbu: any = ref([
    { value: 1, label: 'Merokok' },
    { value: 2, label: 'Minum alkohol' },
    { value: 3, label: 'Lainnya' }
])
const d_riwayatG: any = ref([
    { value: 1, label: 'Tidak ada' },
    { value: 2, label: 'Ada' }
])
const d_rpp = ref([
    { value: 1, label: 'Perlu' },
    { value: 2, label: 'Tidak Perlu' }
]);
// === Bikin Sendiri ===
const d_teraturSiklus: any = ref([
    { value: 1, label: 'Teratur' },
    { value: 2, label: 'Tidak Teratur' }
])
const d_ANC: any = ref([
    { value: 1, label: 'Dokter Kandungan' },
    { value: 2, label: 'Dokter Umum' },
    { value: 3, label: 'Bidan' },
    { value: 4, label: 'Lainnya' }
])
const d_frekuensi: any = ref([
    { value: 1, label: '1x' },
    { value: 2, label: '2x' },
    { value: 3, label: '3x' },
    { value: 4, label: '4x' }
])
const d_KSH: any = ref([
    { value: 1, label: 'Mual' },
    { value: 2, label: 'Muntah' },
    { value: 3, label: 'Perdarahan' },
    { value: 4, label: 'Pusing' },
    { value: 5, label: 'Sakit Kepala' },
    { value: 6, label: 'Lainnya' }
])
const d_RPK: any = ref([
    { value: 1, label: 'Hipertensi' },
    { value: 2, label: 'HIV' },
    { value: 3, label: 'Kencing Manis' },
    { value: 4, label: 'Jantung' },
    { value: 5, label: 'Jiwa' },
    { value: 6, label: 'Varises' },
    { value: 7, label: 'Lain-lain' },
])
const d_masalahPerkemihan: any = ref([
    { value: 1, label: 'Retensi Urine' },
    { value: 2, label: 'Inkontinensia Urine' },
    { value: 3, label: 'Dialysis' },
    { value: 4, label: 'Lainnya' }
])
const d_warnaUrine: any = ref([
    { value: 1, label: 'Kuning Jernih' },
    { value: 2, label: 'Keruh' },
    { value: 3, label: 'Kemerahan' }
]);
const d_masalahDefekasi: any = ref([
    { value: 1, label: 'Stoma' },
    { value: 2, label: 'Atresia ani' },
    { value: 3, label: 'Konstipasi' },
    { value: 4, label: 'Diare' },
    { value: 5, label: 'Inkontinensia alvi' },
    { value: 6, label: 'Lainnya' }
]);
const d_warnaFaeces: any = ref([
    { value: 1, label: 'Kuning' },
    { value: 2, label: 'Kecoklatan' },
    { value: 3, label: 'Kehitaman' },
    { value: 4, label: 'Perdarahan' }
]);
const d_masalahPernikahan: any = ref([
    { value: 1, label: 'Tidak ada' },
    { value: 2, label: 'Ada' }
])
const d_mengalamiKekerasanFisik: any = ref([
    { value: 1, label: 'Tidak ada' },
    { value: 2, label: 'Ada' }
])
const d_Konjungtiva: any = ref([
    { value: 1, label: 'Merah muda' },
    { value: 2, label: 'Pucat' }
])
const d_Sklera: any = ref([
    { value: 1, label: 'Normal' },
    { value: 2, label: 'Ikterus' },
    { value: 3, label: 'Lain-lain' }
])
const d_BentukLeher: any = ref([
    { value: 1, label: 'Normal' }
])
const d_BentukDada: any = ref([
    { value: 1, label: 'Simetris' }
])
const d_iramaNafas: any = ref([
    { value: 1, label: 'Reguler' },
    { value: 2, label: 'Irreguler' }
])
const d_akral: any = ref([
    { value: 1, label: 'Hangat' },
    { value: 2, label: 'Dingin' }
])
const d_jenisNafas: any = ref([
    { value: 1, label: 'Dyspnea' },
    { value: 2, label: 'Kusmaul' },
    { value: 3, label: 'Cyene Stoke' }
])
const d_suaraNafas: any = ref([
    { value: 1, label: 'Normal' },
    { value: 2, label: 'Wheezing' },
    { value: 3, label: 'Ronchi' }
])
const d_Pergerakan: any = ref([
    { value: 1, label: 'Aktif' },
    { value: 2, label: 'Pasif' }
])
const d_kekuatanOtot: any = ref([
    { value: 1, label: 'Kuat' },
    { value: 2, label: 'Lemah' }
])
const d_crt: any = ref([
    { value: 1, label: '≤ 2detik' },
    { value: 2, label: '≥ 2detik' }
])
const d_warnaKulit: any = ref([
    { value: 1, label: 'Normal' },
    { value: 2, label: 'Ikterus' },
    { value: 3, label: 'Sianosis' },
    { value: 4, label: 'Pucat' }
])
const d_membranMukosa: any = ref([
    { value: 1, label: 'Lembab' },
    { value: 2, label: 'Kering' },
    { value: 3, label: 'Stomatitis Turgor Kulit' },
])
const d_turgorKulit: any = ref([
    { value: 1, label: 'Elastis' },
    { value: 2, label: 'Lambat' }
])
const d_DVT: any = ref([
    { value: 1, label: 'Bengkak' },
    { value: 2, label: 'Nadi bagian distal tidak teraba' }
])
const d_Penglihatan: any = ref([
    { value: 1, label: 'Baik' },
    { value: 2, label: 'Gangguan' },
    { value: 3, label: 'Buta Warna' }
])
const d_Pendengaran: any = ref([
    { value: 1, label: 'Baik' },
    { value: 2, label: 'Kurang Baik' },
    { value: 3, label: 'Alat bantu dengar' }
])
const d_Pengecapan: any = ref([
    { value: 1, label: 'Baik' },
    { value: 2, label: 'Kurang Baik' }
])
const d_Penghidu: any = ref([
    { value: 1, label: 'Baik' },
    { value: 2, label: 'Kurang Baik' }
])
const d_Perasa: any = ref([
    { value: 1, label: 'Baik' },
    { value: 2, label: 'Kurang Baik' }
])
const d_KemampuanBicara: any = ref([
    { value: 1, label: 'Baik' },
    { value: 2, label: 'Pelo' },
    { value: 3, label: 'Lainnya' }
])
const d_KemampuanMembaca: any = ref([
    { value: 1, label: 'Baik' },
    { value: 2, label: 'Buta Aksara' }
])
const d_NutrisiSusu: any = ref([
    { value: 1, label: 'ASI' },
    { value: 2, label: 'Sufor' },
    { value: 3, label: 'Lainnya' }
])
const d_KebiasaanMakan: any = ref([
    { value: 1, label: 'Reguler' },
    { value: 2, label: 'Tidak Reguler' },
    { value: 3, label: 'Diet' }
])
const d_Makanan: any = ref([
    { value: 1, label: 'Disukai' },
    { value: 2, label: 'Tidak Disukai' }
])
const d_JumlahMinum: any = ref([
    { value: 1, label: '< 8 Gelas' },
    { value: 2, label: '≥ 8 Gelas' }
])
const d_RutinitasTidur: any = ref([
    { value: 1, label: 'Rutin' },
    { value: 2, label: 'Tidak' },
    { value: 3, label: 'Lama' }
])
const d_WaktuTidur: any = ref([
    { value: 1, label: '< 6 Jam' },
    { value: 2, label: '6-8 Jam' },
    { value: 3, label: '> 8 Jam' }
])
const d_KualitasTidur: any = ref([
    { value: 1, label: 'Baik' },
    { value: 2, label: 'Sering Terbangun' }
])
const d_KesulitanBAB: any = ref([
    { value: 1, label: 'Tidak ada' },
    { value: 2, label: 'Ada darah' },
    { value: 3, label: 'Ada' }
])
const d_WarnaBAK: any = ref([
    { value: 1, label: 'Kekuningan' },
    { value: 2, label: 'Bening' },
    { value: 3, label: 'Kemerahan' },
    { value: 4, label: 'Seperti Teh' }
])
const d_KesulitanBAK: any = ref([
    { value: 1, label: 'Disuria' },
    { value: 2, label: 'Nokturia' },
    { value: 3, label: 'Inkontinensia' },
    { value: 4, label: 'Oliguria' },
    { value: 5, label: 'Unuria' }
])
const d_Kepala: any = ref([
    { value: 1, label: 'Normosefali' },
    { value: 2, label: 'Mikrosefali' },
    { value: 3, label: 'Hidrosefali' }
])

const d_KebersihanSeharihari = ref([
    { value: 'Terawat', label: "Terawat" },
    { value: 'Tidak Terawat', label: "Tidak Terawat" }
]);

const d_KemampuanPerawatanDiri = ref([
    { value: 'Tidak Mampu', label: "Tidak Mampu" },
    { value: 'Mandiri', label: "Mandiri" }
]);
</script>


<style lang="scss">
h1 {
    font-weight: bold !important;
}

.tg {
    border-collapse: collapse;
    border-spacing: 0;
    width: 100%;
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
