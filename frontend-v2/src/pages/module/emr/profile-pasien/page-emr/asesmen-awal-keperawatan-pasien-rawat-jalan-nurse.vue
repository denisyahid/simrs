<template>
    <ConfirmDialog />
    <div>
        <div class="form-layout is-stacked-2">
            <div class="form-outer" style="margin-top:15px">
                <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
                    <div class="form-header-inner">
                        <div class="left">
                            <h3>Nurse Station - RJ</h3>
                        </div>
                        <div class="right">
                            <ButtonEmr :NOREC_EMRPASIEN="NOREC_EMRPASIEN" :COLLECTION="COLLECTION"
                                :isLoading="isLoading" @simpan="simpan" @simpanTemplate="simpanTemplate" isHideCetak
                                @kembaliKeun="kembaliKeun" :isHideST="true" :isLockSimpan="isDisabledInput"></ButtonEmr>
                        </div>
                    </div>
                </div>

                <!-- form baru -->
                <div class="column is-auto" style="display: flex; gap: 5px;">
                    <!-- <VButton type="button" rounded outlined color="primary" raised icon="feather:folder"
                        :isLoading="false" @click="pilihTemplateFix(index)">
                        Pilih Template
                    </VButton> -->
                    <VButton type="button" rounded outlined color="info" raised icon="feather:file-text"
                        :isLoading="false" @click="pilihTemplate(index)">
                        Pilih Riwayat
                    </VButton>
                </div>
                <!-- <hr class="mt-1"> -->
                <div class="column is-12" style="margin-top: 20px;">
                    <div class="columns is-multiline">
                        <div class="column is-12 pt-0">
                            <div class="columns is-multiline">
                                <!-- <div class="column is-12 pt-0">
                                    <h1>Nama Template&emsp;&emsp;<span style="color:red">**Hanya diisi
                                            jika ingin
                                            membuat
                                            template</span></h1>
                                    <VField>
                                        <VControl>
                                            <VTextarea v-model="input.namatemplate" rows="1">
                                            </VTextarea>
                                        </VControl>
                                    </VField>
                                </div> -->
                                <div class="column is-4 pt-0">
                                    <h1 style="font-weight: bold;">Tanggal Kedatangan</h1>
                                    <VField>
                                        <VDatePicker v-model="input.kebtanggalKedatangan" mode="date" trim-weeks
                                            :max-date="new Date()" :disabled="isDisabledInput">
                                            <template #default="{ inputValue, inputEvents }">
                                                <VField>
                                                    <VControl icon="feather:calendar" fullwidth>
                                                        <VInput :value="inputValue" placeholder="Tanggal"
                                                            v-on="inputEvents" class="is-rounded" />
                                                    </VControl>
                                                </VField>
                                            </template>
                                        </VDatePicker>
                                    </VField>
                                </div>
                                <div class="column is-4 pt-0">
                                    <h1 style="font-weight: bold;">Jam Kedatangan</h1>
                                    <VField>
                                        <VDatePicker v-model="input.kebjamKedatangan" color="green" mode="time" is24hr
                                            :disabled="isDisabledInput">
                                            <template #default="{ inputValue, inputEvents }">
                                                <VField>
                                                    <VControl icon="feather:clock">
                                                        <VInput class="input form-timepicker is-rounded"
                                                            :value="inputValue" v-on="inputEvents" />
                                                    </VControl>
                                                </VField>
                                            </template>
                                        </VDatePicker>
                                    </VField>
                                </div>
                                <div class="column is-4 pt-0">
                                    <h1 style="font-weight: bold;">Jam Asesmen Awal</h1>
                                    <VField>
                                        <VDatePicker v-model="input.kebjamAsesmenAwal" color="green" mode="time" is24hr
                                            :disabled="isDisabledInput">
                                            <template #default="{ inputValue, inputEvents }">
                                                <VField>
                                                    <VControl icon="feather:clock">
                                                        <VInput class="input form-timepicker is-rounded"
                                                            :value="inputValue" v-on="inputEvents" />
                                                    </VControl>
                                                </VField>
                                            </template>
                                        </VDatePicker>
                                    </VField>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="column is-12 pt-1">
                    <div class="columns is-multiline">
                        <div class="column is-6">
                            <div class="columns is-multiline">
                                <div class="column is-12 pt-0 pb-0">
                                    <h1 style="font-weight: bold;">Rujukan
                                    </h1>
                                </div>
                                <div class="column is-3">
                                    <VControl>
                                        <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.kebrujukan"
                                            true-value="YA" label="Ya" color="primary" circle
                                            :disabled="isDisabledInput" />
                                    </VControl>
                                </div>
                                <div class="column is-3">
                                    <VControl>
                                        <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.kebrujukan"
                                            true-value="TIDAK" label="Tidak" color="primary" circle
                                            :disabled="isDisabledInput" />
                                    </VControl>
                                </div>
                            </div>
                        </div>
                        <div class="column is-12" v-if="input.kebrujukan == 'YA'">
                            <div class="columns is-multiline">
                                <div class="column is-12 pt-0 pb-0">
                                    <h3 style="font-weight: bold;">
                                        Dari
                                    </h3>
                                </div>
                                <div class="column is-12 pt-0 pb-0">
                                    <VField>
                                        <VControl>
                                            <VInput type="text" class="heightinput input" placeholder="Ket Rujukan"
                                                v-model.number="input.kebketrujukan" :disabled="isDisabledInput" />
                                        </VControl>
                                    </VField>
                                </div>
                            </div>
                        </div>
                        <div class="column is-6" v-else-if="input.kebrujukan == 'TIDAK'">
                            <div class="columns is-multiline">
                                <div class="column is-12 pt-0 pb-0">
                                    <h1 style="font-weight: bold;">Kedatangan
                                    </h1>
                                </div>
                                <div class="column is-3">
                                    <VField>
                                        <VControl>
                                            <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.kebrujuklanjutan"
                                                @change="" true-value="SENDIRI" label="Sendiri" color="primary" circle
                                                :disabled="isDisabledInput" />
                                        </VControl>
                                    </VField>
                                </div>
                                <div class="column is-3">
                                    <VField>
                                        <VControl>
                                            <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.kebrujuklanjutan"
                                                true-value="DIANTARA" label="Diantar" color="primary" circle
                                                :disabled="isDisabledInput" />
                                        </VControl>
                                    </VField>
                                </div>
                            </div>
                        </div>
                        <div class="column is-12"
                            v-if="input.kebrujukan == 'TIDAK' && input.kebrujuklanjutan == 'DIANTAR'">
                            <div class="columns is-multiline">
                                <div class="column is-2 pl-0">
                                    <h3 style="font-weight: bold;">
                                        Diantar Oleh
                                    </h3>
                                </div>
                                <div class="column is-10">
                                    <VField>
                                        <VControl>
                                            <VInput type="text" class="heightinput input" placeholder="Diantar Oleh"
                                                v-model.number="input.kebketrujukan" :disabled="isDisabledInput" />
                                        </VControl>
                                    </VField>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <hr><br>

                <div class="column is-12" style="margin-top: -20px;">
                    <div class="columns is-multiline">
                        <div class="column is-12 pt-1 pb-1" fullwidth>
                            <h1 style="font-weight:bold">ALLOANAMNESIS</h1>
                        </div>
                        <div class="column is-4 pt-1">
                            <VField class="is-autocomplete-select" v-slot="{ id }">
                                <VControl>
                                    <Multiselect v-model="input.kebpilihanallo" :attrs="{ value }"
                                        placeholder="--Pilih--" label="label" :options="d_allo" :searchable="true"
                                        track-by="label" mode="single" autocomplete="off" :disabled="isDisabledInput">
                                    </Multiselect>
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-12" v-if="input.kebpilihanallo == 5">
                            <VField>
                                <VControl>
                                    <VTextarea v-model="input.keballoanamnesis"
                                        placeholder="Ketik Alloanamnesis Lainnya" rows="3" :disabled="isDisabledInput">
                                    </VTextarea>
                                </VControl>
                            </VField>
                        </div>
                    </div>
                </div>

                <hr>

                <div class="column is-12 pt-1">
                    <div class="columns is-multiline">
                        <div class="column is-12 mt-auto">
                            <h1 style="font-weight: bold;">ANAMNESIS</h1>
                            <div class="columns is-multiline pt-3">
                                <div class="column is-6">
                                    <h1>Keluhan Utama</h1>
                                    <VField>
                                        <VControl>
                                            <VTextarea v-model="input.keluhanutama" rows="3"
                                                :disabled="isDisabledInput">
                                            </VTextarea>
                                        </VControl>
                                    </VField>
                                </div>
                                <div class="column is-6">
                                    <h1>Riwayat penyakit sekarang</h1>
                                    <VField>
                                        <VControl>
                                            <VTextarea v-model="input.riwayatpenyakit" rows="3"
                                                :disabled="isDisabledInput">
                                            </VTextarea>
                                        </VControl>
                                    </VField>
                                </div>
                                <div class="column is-6">
                                    <h1>Riwayat penyakit terdahulu</h1>
                                    <VField>
                                        <VControl>
                                            <VTextarea v-model="input.riwayatpenyakitdahulu" rows="3"
                                                :disabled="isDisabledInput">
                                            </VTextarea>
                                        </VControl>
                                    </VField>
                                </div>
                                <div class="column is-6">
                                    <h1>Riwayat pengobatan</h1>
                                    <VField>
                                        <VControl>
                                            <VTextarea v-model="input.riwayatpengobatan" rows="3"
                                                :disabled="isDisabledInput">
                                            </VTextarea>
                                        </VControl>
                                    </VField>
                                </div>
                                <div class="column is-12">
                                    <h1>Riwayat penyakit keluarga</h1>
                                    <VField>
                                        <VControl>
                                            <VTextarea v-model="input.riwayatpenyakitkeluarga" rows="3"
                                                :disabled="isDisabledInput">
                                            </VTextarea>
                                        </VControl>
                                    </VField>
                                </div>
                                <div class="column is-12 pb-0">
                                    <h1>Riwayat alergi</h1>
                                    <div class="columns column is-4 pt-1">
                                        <div class="column is-6">
                                            <VField>
                                                <VControl>
                                                    <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.isalergi"
                                                        true-value="YA" label="Ya" color="primary" circle
                                                        :disabled="isDisabledInput" />
                                                </VControl>
                                            </VField>
                                        </div>
                                        <div class="column is-6">
                                            <VField>
                                                <VControl>
                                                    <VCheckbox class="fontcheckbox pt-1 pb-1" v-model="input.isalergi"
                                                        true-value="TIDAK" label="Tidak" color="primary" circle
                                                        :disabled="isDisabledInput" />
                                                </VControl>
                                            </VField>
                                        </div>
                                    </div>
                                </div>
                                <div class="column is-12 pt-0 columns is-multiline" v-if="input.isalergi == 'YA'">
                                    <div class="column is-6 text-left pt-0 pb-0 is-flex" style="align-items: center;">
                                        <h1>Jenis alergi</h1>
                                    </div>
                                    <div class="column is-6 text-right pt-0">
                                        <VButton color="info" rounded raised size="small" @click="inputObat()"
                                            :loading="isLoading" :disabled="isDisabledInput">
                                            Pilih Alergi Obat
                                        </VButton>
                                    </div>
                                    <div class="column is-12 pt-0">
                                        <VField>
                                            <VControl>
                                                <VTextarea v-model="input.riwayatalergi" placeholder="Jelaskan..."
                                                    rows="3" :disabled="isDisabledInput">
                                                </VTextarea>
                                            </VControl>
                                        </VField>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <hr>

                <div class="column is-12">
                    <div class="columns is-multiline">
                        <div class="column is-12 pt-0 pb-0">
                            <h1 style="font-weight:bold">PEMERIKSAAN FISIK:</h1>
                        </div>
                        <div class="column is-2">
                            <h1 class="mb-12 emr" style="font-weight: bold;">Keadaan Umum</h1>
                            <VField class="is-autocomplete-select">
                                <VControl>
                                    <Multiselect v-model="input.keadaanumumobgyn" :options="d_keadaanumum"
                                        placeholder="--Pilih--" label="label" track-by="label" mode="single"
                                        autocomplete="off" :disabled="isDisabledInput" :searchable="true">
                                        <template #option="{ option }">
                                            <span :style="{ color: option.color }">{{ option.label }}</span>
                                        </template>
                                        <template #selected-item="{ option }">
                                            <span :style="{ color: option.color }">{{ option.label }}</span>
                                        </template>
                                    </Multiselect>
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-2">
                            <h1 style="font-weight: bold;">Tekanan Darah</h1>
                            <VField addons>
                                <VControl expanded>
                                    <VInput type="text" class="input" placeholder="Tekanan Darah"
                                        v-model="input.tekananDarahObgyn" :disabled="isDisabledInput" />
                                </VControl>
                                <VControl class="field-addon-body">
                                    <VButton static disabled>mmHg</VButton>
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-2">
                            <h1 style="font-weight: bold;">Nadi</h1>
                            <VField addons>
                                <VControl expanded>
                                    <VInput type="text" class="input" placeholder="" v-model="input.nadiObgyn"
                                        @keypress="onlyNumber($event)" :disabled="isDisabledInput" />
                                </VControl>
                                <VControl class="field-addon-body">
                                    <VButton static disabled>x/menit</VButton>
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-2">
                            <h1 style="font-weight: bold;">Respirasi</h1>
                            <VField addons>
                                <VControl expanded>
                                    <VInput type="text" class="input" placeholder="" v-model="input.nafasObgyn"
                                        @keypress="onlyNumber($event)" :disabled="isDisabledInput" />
                                </VControl>
                                <VControl class="field-addon-body">
                                    <VButton static disabled>x/menit</VButton>
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-2">
                            <h1 style="font-weight: bold;">Suhu</h1>
                            <VField addons>
                                <VControl expanded>
                                    <VInput type="text" class="input" placeholder="" v-model="input.celciusObgyn"
                                        @keypress="onlyNumber($event)" :disabled="isDisabledInput" />
                                </VControl>
                                <VControl class="field-addon-body">
                                    <VButton static disabled>°C </VButton>
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-2">
                            <h1 style="font-weight: bold;">SpO2</h1>
                            <VField addons>
                                <VControl expanded>
                                    <VInput type="text" class="input" placeholder="" v-model="input.sao2Obgyn"
                                        @keypress="onlyNumber($event)" :disabled="isDisabledInput" />
                                </VControl>
                                <VControl class="field-addon-body">
                                    <VButton static disabled>%</VButton>
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-2">
                            <h1 style="font-weight: bold;">Berat Badan</h1>
                            <VField addons>
                                <VControl expanded>
                                    <VInput type="text" class="input" placeholder="Berat Badan"
                                        v-model="input.beratbadanObgyn" @keypress="onlyNumber($event)"
                                        :disabled="isDisabledInput" />
                                </VControl>
                                <VControl class="field-addon-body">
                                    <VButton static disabled>kg</VButton>
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-2">
                            <h1 style="font-weight: bold;">Tinggi Badan</h1>
                            <VField addons>
                                <VControl expanded>
                                    <VInput type="text" class="input" placeholder="Tinggi Badan"
                                        v-model="input.tinggibadanObgyn" @keypress="onlyNumber($event)"
                                        :disabled="isDisabledInput" />
                                </VControl>
                                <VControl class="field-addon-body">
                                    <VButton static disabled>cm</VButton>
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-8" style="margin-top: -10px;">
                            <h1 class="mt-3 emr" style="font-weight: bold;">GCS</h1>
                            <div class="columns is-multiline">
                                <div class="column is-4">
                                    <VField addons>
                                        <VControl class="field-addon-body">
                                            <VButton static disabled>E</VButton>
                                        </VControl>
                                        <VControl expanded>
                                            <Multiselect v-model="input.gcse" :attrs="{ value }" placeholder="E"
                                                label="label" :options="d_gcse" :searchable="true" track-by="label"
                                                mode="single" autocomplete="off" :disabled="isDisabledInput">
                                            </Multiselect>
                                        </VControl>
                                    </VField>
                                </div>
                                <div class="column is-4">
                                    <VField addons>
                                        <VControl class="field-addon-body">
                                            <VButton static disabled>V</VButton>
                                        </VControl>
                                        <VControl expanded>
                                            <Multiselect v-model="input.gcsv" :attrs="{ value }" placeholder="V"
                                                label="label" :options="d_gcsv" :searchable="true" track-by="label"
                                                mode="single" autocomplete="off" :disabled="isDisabledInput">
                                            </Multiselect>
                                        </VControl>
                                    </VField>
                                </div>
                                <div class="column is-4">
                                    <VField addons>
                                        <VControl class="field-addon-body">
                                            <VButton static disabled>M</VButton>
                                        </VControl>
                                        <VControl expanded>
                                            <Multiselect v-model="input.gcsm" :attrs="{ value }" placeholder="M"
                                                label="label" :options="d_gcsm" :searchable="true" track-by="label"
                                                mode="single" autocomplete="off" :disabled="isDisabledInput">
                                            </Multiselect>
                                        </VControl>
                                    </VField>
                                </div>
                            </div>
                        </div>
                    </div>
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
                        <table class="tg table-tg w-100" v-if="listTemplate.length > 0">
                            <thead>
                                <tr>
                                    <td class="tg-0lax text-center" width="5%">#</td>
                                    <td class="tg-0lax text-center" width="25%">Tanggal Input</td>
                                    <td class="tg-0lax text-center" width="25%">Tanggal Registrasi</td>
                                    <td class="tg-0lax text-center" width="25%">No Registrasi</td>
                                    <td class="tg-0lax text-center" width="20%">No EMR</td>
                                    <td class="tg-0lax text-center" width="20%">Dokter</td>
                                    <td class="tg-0lax text-center" width="20%">Penyakit</td>
                                    <td class="tg-0lax text-center" width="20%">Section</td>
                                </tr>
                            </thead>
                            <tbody v-for="resep in listTemplate">
                                <tr>
                                    <td style="width:5%;text-align:center">
                                        <VIconButton type="button" raised circle icon="fas fa-plus"
                                            :disabled="isDisabledInput" @click="addTemplate(resep)" color="info"
                                            v-tooltip-prime.top="'Pilih'">
                                        </VIconButton>
                                    </td>
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
                                    <td style="width:20%;text-align:center">
                                        <span class="mb-2">{{ resep.riwayatpenyakit }}</span><br>
                                    </td>
                                    <td style="width:25%;text-align:center">
                                        <span class="mb-2">{{ resep.registrasi.namaruangan }}</span><br>
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
        @close="isAlltemplate = false; showModalTemplateFix = false">
        <template #content>
            <DataTable :pt="{
                table: { style: 'min-width: 50rem; min-height: 10rem;' },
                column: {
                    bodycell: ({ state }) => ({
                        class: [{ 'pt-0 pb-0': state['d_editing'] }]
                    })
                }
            }" v-model:filters="filtersTemplate" :value="listTemplateFix" :metaKeySelection="false" :rows="10"
                paginator tableStyle="min-width: 50rem" dataKey="no" :totalRecords="listTemplateFix.length"
                :globalFilterFields="['namatemplate', 'registrasi.namaruangan']" responsiveLayout="stack"
                breakpoint="960px">
                <template #header>
                    <div class="columns is-multiline">
                        <div class="column is-8">
                            <VField>
                                <InputText v-model="filtersTemplate['global'].value"
                                    placeholder="Search Nama Template" />
                            </VField>
                        </div>
                        <div class="column is-4">
                            <VField>
                                <VControl>
                                    <VSwitchBlock v-model="isAlltemplate" color="success" label="Semua Template" />
                                </VControl>
                            </VField>
                        </div>
                    </div>
                </template>
                <template #empty> No customers found. </template>
                <template #loading>
                    <img src="/images/other/loadingspin.gif" alt="Loading..." width="100" />
                    <p style="color:white">Loading data, please wait...</p>
                </template>
                <Column headerStyle="width: 3rem">
                    <template #body="slotProps">
                        <VIconButton type="button" raised circle icon="fas fa-plus" :disabled="isDisabledInput"
                            @click="addTemplate(slotProps.data)" color="info" v-tooltip-prime.top="'Pilih'">
                        </VIconButton>
                    </template>
                </Column>
                <Column field="namatemplate" header="Nama" :sortable="true"></Column>
                <Column field="registrasi.namaruangan" header="Nama Ruangan" :sortable="true">
                    <template #body="slotProps">
                        {{ slotProps.data.registrasi.namaruangan }}
                    </template>
                </Column>
                <Column field="created_at" header="Tanggal" :sortable="true">
                    <template #body="slotProps">
                        <span>{{ H.formatDateToLocalString(slotProps.data.created_at) }}</span>
                    </template>
                </Column>
            </DataTable>
        </template>
    </VModal>
    <VModal :open="showModalObat" title="List Obat" :noclose="true" size="large" actions="right"
        @close="showModalObat = false">
        <template #content>
            <DataTable :pt="{
                table: { style: 'min-width: 50rem; min-height: 10rem;' },
                column: {
                    bodycell: ({ state }) => ({
                        class: [{ 'pt-0 pb-0': state['d_editing'] }]
                    })
                }
            }" v-model:selection="ObatSelected" :value="listObat" v-model:filters="cariObat"
                :metaKeySelection="metaKey" :rows="10" paginator tableStyle="min-width: 50rem" dataKey="id"
                :totalRecords="listObat.length" responsiveLayout="stack" breakpoint="960px">
                <template #header>
                    <div class="column is-12 p-1">
                        <InputText v-model="cariObat['global'].value" placeholder="Cari obat.." />
                    </div>
                </template>
                <Column selectionMode="multiple" headerStyle="width: 3rem"></Column>
                <Column field="namaproduk" header="Nama Obat">
                    <template #body="slotProps">
                        <span>{{ slotProps.data.namaproduk }}</span>
                    </template>
                </Column>
            </DataTable>
        </template>
        <template #action>
            <VButton type="button" color="primary" raised @click="addToInput()" :disabled="isDisabledInput">
                Tambah
            </VButton>
        </template>
    </VModal>

    <Toast position="bottom-center" group="bc" @close="toast.removeGroup('bc');">
    </Toast>
</template>

<script setup lang="ts">
import { useWindowScroll } from '@vueuse/core'
import { useApi } from '/@src/composable/useApi'
import { h, reactive, ref, computed, watch, onBeforeMount, onMounted } from 'vue'
import { useRoute, useRouter, onBeforeRouteLeave } from 'vue-router'
import { useHead } from '@vueuse/head'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useUserSession } from '/@src/stores/userSession'
import ButtonEmr from '../page-emr-plugins/button-emr.vue'
import * as H from '/@src/utils/appHelper'
import AutoComplete from 'primevue/autocomplete';
import Fieldset from 'primevue/fieldset';
import * as EMR from '../page-emr-plugins/asesmen-awal-keper-rj'
import ConfirmDialog from 'primevue/confirmdialog'
import { useConfirm } from "primevue/useconfirm"
import { useToast } from "primevue/usetoast";
import DataTable from 'primevue/datatable';
import Column from 'primevue/column'
import { FilterMatchMode } from 'primevue/api';
import InputText from 'primevue/inputtext';
import moment from 'moment'

useHead({ title: 'Nurse Station - ' + import.meta.env.VITE_PROJECT })
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)
let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pd as string
let norec_emr = useRoute().query.norec_emr as string
let kelompokQuery = useRoute().query.kelompokuser as string
let isfromCPPT = useRoute().query.iscppt as boolean

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

const formName = ref(props.FORM_NAME);

const filtersTemplate = ref({
    global: { value: null, matchMode: FilterMatchMode.CONTAINS }
});
const cariObat = ref({
    global: { value: null, matchMode: FilterMatchMode.CONTAINS }
});
const route = useRoute()
const router = useRouter()
const pasien: any = ref({})
const d_pegawai: any = ref([])
const loadData: any = ref(true)
const isAlltemplate: any = ref(false);
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
    airway: [],
    disability: []

})

console.log('form_name', props.FORM_NAME)


const COLLECTION: any = ref('AsesmenAwalKeperawatanPasienRawatJalanNurse') //table mongodb
const NOREC_EMRPASIEN: any = ref('')
const input: any = ref({
    kebjamKedatangan: new Date(),
    kebjamAsesmenAwal: new Date(),
    kebtanggalKedatangan: new Date(),
    keadaanumumobgyn: 1,
    gcse: 4,
    gcsv: 5,
    gcsm: 6
})
const { y } = useWindowScroll()
const isStuck = computed(() => {
    return y.value > 30
})
const isLoading = ref(false)
const isAktive = ref()
const d_allo: any = ref([{ value: 'Suami/Istri', label: 'Suami/Istri' }, { value: 'Orang Tua', label: 'Orang Tua' }, { value: 'Anak', label: 'Anak' }, { value: 'Pasien', label: 'Pasien' }, { value: 'Lainnya', label: 'Lainnya' }])
const d_mata: any = ref([{ value: 1, label: 'Ya' }, { value: 2, label: 'Tidak' }])
const d_gcse: any = ref([{ value: 1, label: '1' }, { value: 2, label: '2' }, { value: 3, label: '3' }, { value: 4, label: '4' }])
const d_gcsv: any = ref([{ value: 1, label: '1' }, { value: 2, label: '2' }, { value: 3, label: '3' }, { value: 4, label: '4' }, { value: 5, label: '5' }])
const d_gcsm: any = ref([{ value: 1, label: '1' }, { value: 2, label: '2' }, { value: 3, label: '3' }, { value: 4, label: '4' }, { value: 5, label: '5' }, { value: 6, label: '6' }])
const d_keadaanumum: any = ref([{ value: 1, label: 'Baik', color: 'green' }, { value: 2, label: 'Sedang', color: 'orange' }, { value: 3, label: 'Buruk', color: 'red' }])
const d_freknyeri: any = ref([{ value: 1, label: 'Jarang' }, { value: 2, label: 'Hilang Timbul' }, { value: 3, label: 'Terus Menerus' }])
const d_kualitasnyeri: any = ref([{ value: 1, label: 'Tumpul' }, { value: 2, label: 'Tajam' }, { value: 3, label: 'Panas/Terbakar' }, { value: 4, label: 'Lain-lain' }])
const d_gangguanpsikologis: any = ref([{ value: 1, label: 'Tidak Ada' }, { value: 2, label: 'Gelisah' }, { value: 3, label: 'Takut' }, { value: 4, label: 'Sedih' }, { value: 5, label: 'Rendah diri' }, { value: 6, label: 'Acuh tak acuh' }, { value: 7, label: 'Mudah tersinggung' }, { value: 8, label: 'Menarik diri' }])
const d_masalahkawin: any = ref([{ value: 1, label: 'Tidak Ada' }, { value: 2, label: 'Ada' }])
const d_kekerasan: any = ref([{ value: 1, label: 'Tidak Ada' }, { value: 2, label: 'Ada' }])
const d_pembiayaankesehatan: any = ref([{ value: 1, label: 'Biaya sendiri/keluarga' }, { value: 2, label: 'Asuransi Lainnya' }])
const d_rohaniawan: any = ref([{ value: 1, label: 'Ya' }, { value: 2, label: 'Tidak' }])
const d_penurunanbb: any = ref([{ value: 1, label: 'Tidak' }, { value: 2, label: 'Tidak Yakin' }, { value: 3, label: '1-5 kg' }, { value: 4, label: '6-10 kg' }, { value: 5, label: '11-15 kg' }, { value: 6, label: '>15 kg' }])
const d_penurunannafsu: any = ref([{ value: 1, label: 'Ya' }, { value: 2, label: 'Tidak' }])
const d_diagnosakhusus: any = ref([{ value: 1, label: 'Ya' }, { value: 2, label: 'Tidak' }])
const d_mengontrolbab: any = ref([{ value: 1, label: 'Inkontinen/tidak teratur (perlu enema)' }, { value: 2, label: 'Kadang inkontinen (1xseminggu)' }, { value: 3, label: 'Kontinen teratur' }])
const d_mengontrolbak: any = ref([{ value: 1, label: 'Inkontinen/pakai kateter dan tidak terkontrol' }, { value: 2, label: 'Kadang inkontinen (max 1x24 jam)' }, { value: 3, label: 'Mandiri' }])
const d_bersihdiri: any = ref([{ value: 1, label: 'Butuh pertolongan orang lain' }, { value: 2, label: 'Mandiri' }])
const d_toilet: any = ref([{ value: 1, label: 'Tergantung pertolongan orang lain' }, { value: 2, label: 'Perlu pertolongan pada beberapa aktivitas terapi dan dapat mengerjakan sendiri beberapa aktivitas lain' }, { value: 3, label: 'Mandiri' }])
const d_makan: any = ref([{ value: 1, label: 'Tidak mampu' }, { value: 2, label: 'Perlu seseorang menolong memotong makanan' }, { value: 3, label: 'Mandiri' }])
const d_berpindahtt: any = ref([{ value: 1, label: 'Tidak Mampu' }, { value: 2, label: 'Perlu banyak bantuan untuk bisa duduk (2 orang)' }, { value: 3, label: 'Bantuan 1 orang' }, { value: 4, label: 'Mandiri' }])
const d_mobilisasi: any = ref([{ value: 1, label: 'Tidak Mampu' }, { value: 2, label: 'Dengan kursi roda' }, { value: 3, label: 'Bantuan 1 orang' }, { value: 4, label: 'Mandiri' }])
const d_berpakaian: any = ref([{ value: 1, label: 'Tergantung orang lain' }, { value: 2, label: 'Sebagian dibantu (misal mengancing baju)' }, { value: 3, label: 'Mandiri' }])
const d_tangga: any = ref([{ value: 1, label: 'Tidak Mampu' }, { value: 2, label: 'Butuh Pertolongan' }, { value: 3, label: 'Mandiri' }])
const d_mandi: any = ref([{ value: 1, label: 'Teragantung orang lain' }, { value: 2, label: 'Mandiri' }])
const d_caraduduk: any = ref([{ value: 1, label: 'Ya' }, { value: 2, label: 'Tidak' }])
const d_kursi: any = ref([{ value: 1, label: 'Ya' }, { value: 2, label: 'Tidak' }])
const d_tindakan: any = ref([{ value: 1, label: 'Tidak ada tindakan' }, { value: 2, label: 'Edukasi' }, { value: 3, label: 'Pasang penanda resiko jatuh' }])
const listTemplate: any = ref([])
const listTemplateFix: any = ref([])
const listObat: any = ref([])
const showModalObat: any = ref(false);
const showModalTemplate: any = ref(false)
const showModalTemplateFix: any = ref(false)
const confirm = useConfirm();
const bigAlert = useToast();
const kelompokUser = useUserSession().getUser().kelompokUser.kelompokUser
const showBigAlert = ref(false);
const ObatSelected: any = ref()
const isDisabledInput: any = ref(false);

let listHipertensi: any = ref(EMR.hipertensi())
let listDiabetes: any = ref(EMR.diabetes())
let listDyslipidemia: any = ref(EMR.dyslipidemia())
let listDuaPilihan: any = ref(EMR.duaPilihan())
let listAgama: any = ref(EMR.agama())
let listStatus: any = ref(EMR.status())
let listKeluarga: any = ref(EMR.keluarga())
let listTempatTinggal: any = ref(EMR.tempatTinggal())
let listPsikologis: any = ref(EMR.psikologis())
let listMore: any = ref(EMR.more())
let listImageNyeri: any = ref(EMR.imgNyeri())
let listSkoringNyeri: any = ref(EMR.skoringNyeri())
let resikoNutrisional: any = ref(EMR.resikoNutrisional())
let fungsionalPertama: any = ref(EMR.fungsionalPertama())
let listRangeNilaiPoin: any = ref(EMR.nilaiPoin())
let listDESCNilai: any = ref(EMR.descNilai())
let pertanyaanA: any = ref(EMR.pertanyaanA())
let pertanyaanB: any = ref(EMR.pertanyaanB())
let pertanyaanC: any = ref(EMR.pertanyaanC())
let dropdownAllo: any = ref([
    "Suami/Istri",
    "Orang tua",
    "Anak",
    "Lainnya"
])

const loadRiwayat = async () => {
    isLoading.value = true
    let responsex = await useApi().get(`/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${NOREC_PD}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`)
    isLoading.value = false
    if (responsex.length) {
        input.value = responsex[0] //set ke inputan
        if (NOREC_EMRPASIEN.value == '') {
            NOREC_EMRPASIEN.value = responsex[0].emrpasienfk
        }
    } else {
        isLoading.value = true
        let responsetgl = await useApi().get(`/emr/get-emr-tgl-terakhir?nocmfk=${ID_PASIEN}&collection=${COLLECTION.value}`)
        isLoading.value = false

        if (responsetgl.length) {
            confirm.require({
                message: 'Nurse Station terakhir tanggal ' + H.formatDate(responsetgl[0].created_at, 'DD-MM-YYYY HH:mm:ss') + ', apakah mau mengambil data sebelumnya?',
                header: 'Riwayat Terakhir',
                icon: 'pi pi-info-circle',
                acceptClass: 'p-button-danger',
                accept: () => {
                    isLoading.value = true
                    useApi().get(`/emr/get-emr-history-terakhir?nocmfk=${ID_PASIEN}&collection=${COLLECTION.value}`).then((responselast: any) => {
                        isLoading.value = false
                        if (responselast.length) {
                            input.value = responselast[0]
                            let d = input.value
                            delete d['_id']
                            delete d['emrpasienfk']
                            delete d['user_input']
                            delete d['created_at']
                            d.id = ''
                            d.keadaanumumobgyn = 1
                            d.gcse = 4
                            d.gcsv = 5
                            d.gcsm = 6
                            d.kebtanggalKedatangan = new Date()
                            d.kebjamKedatangan = new Date()
                            d.kebjamAsesmenAwal = new Date()
                            d.tekananDarahObgyn = ''
                            d.nadiObgyn = ''
                            d.nafasObgyn = ''
                            d.celciusObgyn = ''
                            d.sao2Obgyn = ''
                            // d.beratbadanObgyn = ''
                            // d.tinggibadanObgyn = ''
                            d.namatemplate = ''
                        } else {
                            H.alert('warning', 'Data tidak ada')
                        }
                    })
                },
                reject: () => { },
            })
        }
    }
}


const simpan = () => {
    let ID = input.value.id ? input.value.id : ''
    let object: any = {}
    if (input.value.kebrujukan == 'TIDAK') {
        if (input.value.kebrujuklanjutan == 'DIANTAR') {
            input.value.kebketrujukan = input.value.kebketrujukan;
        }
    }

    if (input.value.kebpilihanallo == 'Lainnya') {
        input.value.kebpilihanallo = input.value.keballoanamnesis;
    }

    if (input.value.kualitasnyeri == 'LAINNYA') {
        input.value.kualitasnyeri = input.value.kualitasnyerilain
    }

    if (input.value.pembiayaankesehatan == 'ASURANSI') {
        input.value.pembiayaankesehatan = input.value.ketpembiayaankesehatan
    }

    if (!input.value.keadaanumumobgyn) {
        H.alert('error', 'Keadaan Umum wajib diisi');
        return
    }

    if (!input.value.tekananDarahObgyn) {
        H.alert('error', 'Tekanan Darah wajib diisi');
        return
    }
    const tekananDarahRegex = /^\d+\/\d+$/; // Hanya menerima format angka/angka
    if (!tekananDarahRegex.test(input.value.tekananDarahObgyn)) {
        H.alert('error', 'Tekanan Darah harus dalam format angka/angka, contoh: 120/80');
        return;
    }

    if (!input.value.nadiObgyn) {
        H.alert('error', 'Nadi wajib diisi');
        return
    }

    if (!input.value.nafasObgyn) {
        H.alert('error', 'Respirasi wajib diisi');
        return
    }

    if (!input.value.celciusObgyn) {
        H.alert('error', 'Suhu wajib diisi');
        return
    }

    if (!input.value.sao2Obgyn) {
        H.alert('error', 'SaO2 wajib diisi');
        return
    }

    // if (!input.value.beratbadanObgyn) {
    //     H.alert('error', 'Berat Badan wajib diisi');
    //     return
    // }

    // if (!input.value.tinggibadanObgyn) {
    //     H.alert('error', 'Tinggi Badan wajib diisi');
    //     return
    // }

    if (!input.value.keluhanutama || input.value.keluhanutama && input.value.keluhanutama.replace(/\s/g, "").length < 4) {
        H.alert('error', 'Keluhan Utama, ' + 'diisi minimal 4 karakter');
        return;
    }

    if (!input.value.gcse) {
        H.alert('error', 'GCS wajib diisi');
        return
    }

    if (!input.value.gcsv) {
        H.alert('error', 'GCS wajib diisi');
        return
    }

    if (!input.value.gcsm) {
        H.alert('error', 'GCS wajib diisi');
        return
    }

    object = input.value
    object.nocm = pasien.value.nocm
    object.pasien = H.setObjectPasien(pasien.value)
    object.registrasi = H.setObjectRegistrasi(pasien.value.registrasi)

    if (object.hasOwnProperty('namatemplate')) {
        delete object.namatemplate
    }

    let json = {
        'id': ID,
        'norec_emr': NOREC_EMRPASIEN.value,
        'collection': COLLECTION.value,
        'url_form': props.FORM_URL,
        'name_form': 'Nurse Station',
        'jenis_emr': 'asesmen_medis',
        'data': object
    }

    isLoading.value = true
    useApi().postNoMessage(`/emr/simpan-emr`, json).then((response: any) => {
        if (typeof response != 'string') {
            showBiggerAlert('success', response.metaData.message, 'Berhasil simpan data');
        } else {
            showBiggerAlert();
        }
        isLoading.value = false
        NOREC_EMRPASIEN.value = response.response.norec_emr
        input.value.id = response.response.id

        const user = useUserSession().getUser()
        let lockRoute = H.cacheHelper().get('lockedRoute');
        if (kelompokQuery && kelompokQuery.toUpperCase().indexOf('NURSE') > -1) {
            if (lockRoute != null || lockRoute != undefined) {
                console.log("LOCK")
                router.push({
                    name: lockRoute,
                })
            } else {
                console.log("KELOMPOK USER MENU", user.kelompokUser.menu)
                router.push({
                    name: user.kelompokUser.menu,
                })
            }
        }

        // const urlParams = new URLSearchParams(window.location.search);
        // const kelompokuser = urlParams.get('kelompokuser');
        // console.log("Kelompokuser:", kelompokuser);

        // const baseUrl = window.location.origin;
        // console.log(baseUrl)
        // window.location.href = `${baseUrl}/module/emr/profile-pasien?nocmfk=${pasien.value.registrasi.nocmfk}&norec_pasien_daftar=${pasien.value.registrasi.norec_pd}&norec_pd=${pasien.value.registrasi.norec_pd}&norec_apd=${pasien.value.registrasi.norec_apd}&norec_emr=${NOREC_EMRPASIEN.value}&jenisinterna&jenistrauma&jenisobgyn&kelompokuser=${kelompokuser}`;
    }).catch((e: any) => {
        isLoading.value = false
    })
}

const simpanTemplate = () => {
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
        'name_form': formName.value,
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

const pilihTemplateFix = async (index: any) => {
    isLoading.value = true
    useApi().get(`/emr/get-emr-template?collection=${COLLECTION.value}`).then((responselast: any) => {
        isLoading.value = false
        console.log(responselast)
        if (responselast.length) {
            for (var x = 0; x < responselast.length; x++) {
                responselast[x].no = x + 1
                // responselast[x].id = ''
            }
            listTemplateFix.value = responselast //set ke inputan
            showModalTemplateFix.value = true
        } else {
            H.alert('warning', 'Data tidak ada')
        }
    })
}

const kembaliKeun = () => {
    window.history.back()
}
const fetchPasien = () => {
    pasien.value = props.pasien
    pasien.value.registrasi = props.registrasi
    NOREC_EMRPASIEN.value = norec_emr ? norec_emr : ''
    console.log(norec_emr)
}

const fetchDokter = async (filter: any) => {

    await useApi().get(
        `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`
    ).then((response) => {
        d_pegawai.value = response
    })
}

const skor = (e: any, i: any) => {

    let listSkor = listSkoringNyeri.value.detail

    listSkor.forEach((element: any) => {
        if (element.descNilai == e.descNilai) {
            input.value.skoringNyeri = e.descNilai
        }
    });
    isAktive.value = i

}

// const getDataExist = async () => {
//     await useApi().get(`emr/get-data-exist?nocmfk=${ID_PASIEN}`).then((response) => {
//         if (response != null || response != undefined) {
//             input.value.beratbadanObgyn = response.beratBadan
//             input.value.tinggibadanObgyn = response.tinggiBadan
//             input.value.IMT = response.IMT
//             input.value.lingkarPerut = response.lingkarPerut
//             input.value.nadiObgyn = response.nadi
//             input.value.celciusObgyn = response.suhu
//             input.value.tekananDarahObgyn = response.tekananDarah
//             input.value.nafasObgyn = response.pernapasan
//             input.value.sao2Obgyn = response.SPO2
//         }
//     })
// }

const deleteTemplate = (idTemplate) => {
    console.log(idTemplate);
    isLoading.value = true
    let json = {
        'id': idTemplate,
        'collection': COLLECTION.value
    }
    useApi().post(
        `/emr/hapus-template`, json).then((response: any) => {
            if (response.status !== 500) {
                isLoading.value = false
                isAlltemplate.value = false;
                H.alert('sucess', response.message);
                pilihTemplateFix();
            } else {
                H.alert('danger', response.message);
            }
        }).catch((e: any) => {
            isLoading.value = false
            H.alert('danger', e);
        })
}

const addTemplate = (response: any) => {
    console.log(response)
    input.value = response //set ke inputan
    input.value.namatemplate = null
    showModalTemplateFix.value = false
    isAlltemplate.value = false;
    H.alert('success', 'Berhasil di tambahkan');
}

const handlerRujukanChange = (val: any) => {
    console.log(val);
    if (val === "YA") {
    }
}

const print = async () => {
    H.printBlade(`emr/cetak-asesmen-keper-rj?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`)
}

const inputObat = async (filter: any) => {
    listObat.value = []
    let nomor = 0;
    isLoading.value = true
    let response = await useApi().get(`emr/get-master-obat`)
    isLoading.value = false
    if (response.length > 0) {
        listObat.value = response
        showModalObat.value = true;
    } else {
        H.alert('warning', 'Data Obat Tidak Ada!')
    }
}
const addToInput = (event) => {
    console.log("obat selected", ObatSelected)
    let inputss = input.value.riwayatalergi == undefined ? '' : input.value.riwayatalergi;
    if (ObatSelected.value.length > 0) {
        ObatSelected.value.forEach((obt, ind) => {
            inputss += ` # ${obt.namaproduk} `
        })
    }
    input.value.riwayatalergi = inputss
    showModalObat.value = false;
}

function onlyNumber(evt: any) {
    evt = (evt) ? evt : window.event;
    var charCode = (evt.which) ? evt.which : evt.keyCode;
    if ((charCode > 31 && (charCode < 48 || charCode > 57)) && charCode !== 46) {
        evt.preventDefault();
    } else {
        return true;
    }
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

const showBiggerAlert = (type: string = 'error', header: string = 'Gagal Simpan', message: string = 'Internal Server Error') => {
    // long notif karena user selalu mengeluh bahwa
    // jika error dimunculkan notif yang dimana notif muncul namun tak terlihat oleh user
    // maka jika ada error, notif di munculkan lama 10 detik atau bisa di close manual
    bigAlert.add({ severity: type, summary: header, detail: message, group: `bc`, life: type == 'error' ? 10000 : 4000 });
}

watch(isAlltemplate, (newValue) => {
    pilihTemplateFix()
})

onMounted(() => {
    if (isfromCPPT) {
        H.alert('error', 'Silahkan isi Nurse Station Terlebih Dahulu !');
        formName.value = 'Assesmen Keperawatan'
    }
    fetchPasien()
})


watch(() => [
    input.value.penurunanBB,
    input.value.penurunanNafsuMakan,
], () => {
    let poin1 = input.value.penurunanBB ? parseInt(input.value.penurunanBB.poin) : 0
    let poin2 = input.value.penurunanNafsuMakan ? parseInt(input.value.penurunanNafsuMakan.poin) : 0
    const total = poin1 + poin2
    input.value.totalNilaiMST = total

})

</script>


<style lang="scss">
@import '/@src/scss/abstracts/all';
@import '/@src/scss/components/forms-outer';
@import '/@src/scss/custom/config';
@import '/@src/scss/custom/timeline-css';
@import '/@src/scss/module/emr/asesmen-awal.scss';

.v-avatar.is-medium.active {
    padding: 3px;
    background: var(--success);
    display: inline-table !important;
}

.p-fieldset-legend {
    margin-left: 14px;
}

.p-fieldset .p-fieldset-content {
    background: none;
}

// .p-fieldset.p-component{
//     border-left: ;
// }

table.assesment {
    border-collapse: collapse;
    width: 100%;
}


.assesment th {
    text-align: center !important;
    border-bottom: 1px solid black;
    // border: 1px solid black;
}

.assesment th,
.assesment td {
    padding: 8px;
    vertical-align: middle !important;
}

hr {
    background-color: hsl(0deg 6.81% 88.68%);
    border: none;
    display: block;
    height: 2px;
    margin: 1rem 0;
}

.table.is-borderless {
    border: none !important;
    background-color: transparent;
}

.tg .tg-0lax {
    text-align: left;
    vertical-align: middle;
    padding: 5px;
    padding-bottom: 10px;
}

.tg .tg-td0 {
    text-align: left;
    vertical-align: middle;
    padding: 3px;
}

.table.is-borderless th,
tr,
td {
    border: none !important;
    background-color: transparent !important;
}

[disabled].radio,
[disabled].checkbox,
fieldset[disabled] .radio,
fieldset[disabled] .checkbox,
.radio input[disabled],
.checkbox input[disabled] {
    color: hsl(0deg, 0%, 48%);
    cursor: auto !important;
}

// tr:hover {
//     background-color: #f5f5f5;
// }</style>
