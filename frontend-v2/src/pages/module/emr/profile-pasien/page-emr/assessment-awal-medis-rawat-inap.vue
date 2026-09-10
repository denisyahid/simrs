<template>
    <div class="form-layout is-stacked-2">
        <div class="form-outer" style="margin-top:15px">
            <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
                <div class="form-header-inner">
                    <div class="left">
                        <h3> {{ props.FORM_NAME }}</h3>
                    </div>
                    <div class="right">
                        <div class="buttons">
                            <VButton icon="lnir lnir-arrow-left rem-100" light dark-outlined @click="kembaliKeun()">
                                Kembali
                            </VButton>
                            <VButton type="button" rounded outlined color="warning" raised icon="lnir lnir-printer"
                                @click="print()"> Cetak
                            </VButton>
                            <VButton type="button" rounded outlined color="primary" raised icon="feather:save"
                                :loading="isLoadingBtn" @click="simpan()"> Simpan
                            </VButton>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <div class="columns is-multiline p-2">
        <VPlaceload height="37rem" width="100%" class="mx-2" v-if="loadData" />
        <div class="column is-12">
            <VCard>
                <div class="column is-12">
                    <Fieldset :toggleable="true" legend="A. Anamnesis (S)">
                        <div class="column is-12" v-for="(datas) in anamnesis">
                            <h1 class="emr">{{ datas.title }}</h1>
                            <div class="columns is-multiline">
                                <div class="column" v-for="(data) in datas.value">
                                    <VField v-if="data.type == 'textarea'">
                                        <VControl>
                                            <VTextarea v-model="input[data.model]" rows="3">
                                            </VTextarea>
                                        </VControl>
                                    </VField>
                                    <VField v-if="data.type == 'checkBox'">
                                        <VControl raw subcontrol>
                                            <VCheckbox v-model="input[data.model]" :true-value="data.subTitle"
                                                :label="data.subTitle" class="pb-0" color="primary" circle />
                                        </VControl>
                                    </VField>
                                    <VField :label="data.subTitle" v-else-if="data.type == 'textBox'" class="p-0">
                                        <VControl raw subcontrol>
                                            <input v-model="input[data.model]" class="input p-0" />
                                        </VControl>
                                    </VField>
                                </div>
                            </div>

                        </div>
                    </Fieldset>
                </div>

                <div class="column is-12">
                    <Fieldset :toggleable="true" legend="B. Pemeriksaan Fisik (O)">
                        <div class="column is-12" v-for="(datas) in pemeriksaanFisik">
                            <h1 class="emr">{{ datas.title }}</h1>

                            <div class="columns is-multiline" v-if="datas.type == 'checkBox'">
                                <div class="column" v-for="(data) in datas.value">
                                    <VField>
                                        <VControl raw subcontrol>
                                            <VCheckbox v-model="input[data.model]" :true-value="data.subTitle"
                                                :label="data.subTitle" class="pb-0" color="primary" circle />
                                        </VControl>
                                    </VField>
                                </div>
                            </div>
                            <div class="columns is-multiline" v-if="datas.type == 'textBox'">
                                <div class="column is-2 m-4" v-for="(data) in datas.value">
                                    <h1 class="emr mb-2">{{ data.subTitle }}</h1>
                                    <VField addons v-if="data.satuan">
                                        <VControl expanded>
                                            <VInput type="text" class="input" placeholder="Suhu"
                                                v-model="input[data.model]" />
                                        </VControl>
                                        <VControl class="field-addon-body">
                                            <VButton static>{{ data.satuan }}</VButton>
                                        </VControl>
                                    </VField>
                                    <VField v-else>
                                        <VControl raw subcontrol>
                                            <input v-model="input[data.subTitle]" class="input" />
                                        </VControl>
                                    </VField>
                                </div>
                            </div>

                        </div>

                        <div class="column is-12">
                            <Fieldset :toggleable="true" legend="4. Kesadaran">
                                <div class="columns p-3">
                                    <div class="column is-6 pb-0">
                                        <div class="columns">
                                            <div class="column is-1">
                                                <h1 class="mb-3 emr" style="text-align: center;">No</h1>
                                            </div>
                                            <div class="column is-one-quarter" style="text-align: center;">
                                                <h1 class="mb-3 emr">Parameter</h1>
                                            </div>
                                            <div class="column is-5" style="text-align: center;">
                                                <h1 class="mb-3 emr">Nilai</h1>
                                            </div>
                                        </div>
                                        <div class="columns pb-4" v-for="(data) in kesadaran">
                                            <div class="column is-1 pt-0 " style="text-align: center;">
                                                <h1 class="mb-3 emr"> {{ data.no }}</h1>
                                            </div>
                                            <div class="column  is-one-quarter pt-0" style="text-align: center;">
                                                <h1 class="mb-3 emr">{{ data.parameter }}</h1>
                                            </div>
                                            <div class="column is-5" style="text-align: end;">
                                                <div class="columns is-multiline pb-5">
                                                    <div class="column is-4 pt-0" v-for="(pilihan) in data.nilai">
                                                        <VField>
                                                            <VControl raw subcontrol>
                                                                <VCheckbox class="p-0" :true-value="pilihan.poin"
                                                                    v-model="input[pilihan.model]" :label="pilihan.poin"
                                                                    color="primary" />
                                                            </VControl>
                                                        </VField>
                                                    </div>
                                                </div>

                                            </div>

                                        </div>
                                    </div>
                                    <div class="column is-6">
                                        <VCard>
                                            <p>KETERANGAN</p>
                                            <div class="column p-0" v-for="(data) in descRangeKesadaran">
                                                <VField>
                                                    <VControl raw subcontrol>
                                                        <VCheckbox class="pb-0" :true-value="data.value"
                                                            v-model="input[data.model]" :label="data.des" color="primary"
                                                            disabled />
                                                    </VControl>
                                                </VField>
                                            </div>
                                        </VCard>
                                    </div>
                                </div>
                                <div class="column is-4 pt-0" style="margin-left: auto;">
                                    <VField>
                                        <h1 style="font-weight: bold;" class="mb-2">JUMLAH TOTAL</h1>
                                        <VControl>
                                            <input v-model="input.totalKesadaran" class="input" placeholder="Jumlah"
                                                disabled />
                                        </VControl>
                                    </VField>
                                </div>
                                <div class="column is-12">
                                    <VField>
                                        <VControl>
                                            <VTextarea v-model="input.keteranganKesadaran" rows="3">
                                            </VTextarea>
                                        </VControl>
                                    </VField>
                                </div>
                            </Fieldset>
                        </div>


                        <div class="column is-12" v-for="(datas) in pemeriksaanFisik2">
                            <h1>{{ datas.title }}</h1>
                            <div class="column is-12 pb-0" v-for="(detail) in datas.value">
                                <h1 class="ml-3 mb-3">{{ detail.subTitle }}</h1>
                                <div class="columns is-multiline ml-3">
                                    <div class="column pb-0" v-for="(data) in detail.item"
                                        :class="detail.item.length < 4 ? 'is-3' : 'is-3 pr-5'">
                                        <VField v-if="data == 'ketRonki' || data == 'ketWheezing'">
                                            <VControl raw subcontrol>
                                                <input v-model="input[data]" class="input p-0" />
                                            </VControl>
                                        </VField>
                                        <VField v-else>
                                            <VControl raw subcontrol>
                                                <VCheckbox v-model="input[detail.model]" :true-value="data" :label="data"
                                                    class="p-0" color="primary" circle />
                                            </VControl>
                                        </VField>
                                    </div>
                                </div>
                                <div class="column is-12 pt-0" v-if="detail.optional">
                                    <VField class="p-0" v-for="(descrip) in detail.optional">
                                        <p class="mb-3">{{ descrip.keterangan }}</p>
                                        <VControl raw subcontrol>
                                            <input v-model="input[descrip.model]" class="input p-0" />
                                        </VControl>
                                    </VField>
                                </div>
                            </div>
                        </div>

                        <div class="column is-12" v-for="(data) in pemeriksaanFisik3">
                            <h1>{{ data.title }}</h1>
                            <div class="columns is-multiline p-3">
                                <div class="column mb-0 ml-3" v-for="(value) in data.value">
                                    <VField>
                                        <VControl raw subcontrol>
                                            <VCheckbox v-model="input[value.model]" :true-value="value.name"
                                                :label="value.name" class="p-0" color="primary" circle />
                                        </VControl>
                                    </VField>
                                </div>
                            </div>
                            <div class="column is-12 pt-0 ml-3" v-if="data.description">
                                <VField class="p-0">
                                    <VControl raw subcontrol>
                                        <input v-model="input[data.description]" class="input p-0" />
                                    </VControl>
                                </VField>
                            </div>
                        </div>

                        <div class="column is-12">
                            <h1 class="mb-3 emr">15. Leher</h1>
                            <div class="column is-8">
                                <VField>
                                    <VControl icon="feather:bookmark">
                                        <VInput v-model.number="input.leher" placeholder="Tulis Angka " />
                                    </VControl>
                                </VField>
                                <Slider v-model="input.leher" />
                            </div>
                        </div>

                    </Fieldset>
                </div>

                <div class="column is-12">
                    <Fieldset :toggleable="true" legend="C. Status Lokalis">
                        <div class="columns is-multiline pl-5">
                            <div class="column is-3">
                                <img src="/images/emr/asesmen_medis.png" style="width: 12rem !important;" />
                            </div>
                            <div class="column is-6" style="margin-top: 4rem">
                                <VField>
                                    <VControl>
                                        <VTextarea v-model="input.statusLokalis" rows="3">
                                        </VTextarea>
                                    </VControl>
                                </VField>
                            </div>
                        </div>

                    </Fieldset>
                </div>

                <div class="column is-12">
                    <Fieldset :toggleable="true" legend="D. Pemeriksaan Penunjang">
                        <div class="columns is-multiline">
                            <div class="column" v-for="(data) in pemeriksaanPenunjang"
                                :class="data.type == 'checkBox' ? 'is-2' : 'is-10'">
                                <VField v-if="data.type == 'checkBox'" style="position: relative;top: 2rem;">
                                    <VControl raw subcontrol>
                                        <VCheckbox v-model="input[data.model]" :true-value="data.title" :label="data.title"
                                            class="p-0" color="primary" circle />
                                    </VControl>
                                </VField>
                                <VField v-else>
                                    <VControl>
                                        <VTextarea v-model="input[data.model]" rows="3">
                                        </VTextarea>
                                    </VControl>
                                </VField>
                            </div>
                        </div>
                        <div class="column is-12">
                            <VField>
                                <VControl>
                                    <VTextarea v-model="input.penunjangLainnya" rows="3">
                                    </VTextarea>
                                </VControl>
                            </VField>
                        </div>
                    </Fieldset>
                </div>

                <div class="column is-12">
                    <Fieldset :toggleable="true" legend="E. Diagnosa">
                        <div class="column is-12">
                            <VTabs selected="icd9" :tabs="[
                                { label: 'Diagnosa ICD 9', value: 'icd9' },
                                { label: 'Diagnosa ICD 10', value: 'icd10' },
                            ]">
                                <template #tab="{ activeValue }">
                                    <p v-if="activeValue === 'icd9'">
                                        <DataTable :value="dataSourceICD9" class="p-datatable-sm" :loading="isLoading"
                                            :paginator="true" :rows="5" :rowsPerPageOptions="[5, 10, 25]"
                                            selectionMode="single"
                                            paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
                                            responsiveLayout="stack" breakpoint="960px" sortMode="multiple"
                                            currentPageReportTemplate="Showing {first} to {last} of {totalRecords}"
                                            showGridlines>

                                            <Column field="no" header="#"></Column>
                                            <Column field="noregistrasi" header="No Registrasi"></Column>
                                            <Column field="kddiagnosatindakan" header="Kode ICD 9"></Column>
                                            <Column field="namadiagnosatindakan" header="Nama ICD 9"></Column>
                                            <Column field="namaruangan" header="Rungan"></Column>
                                            <Column field="tglInput" header="Tgl Input"></Column>
                                        </DataTable>
                                    </p>
                                    <p v-else-if="activeValue === 'icd10'">
                                        <DataTable :value="dataSourceICD10" class="p-datatable-sm" :loading="isLoading"
                                            :paginator="true" :rows="5" :rowsPerPageOptions="[5, 10, 25]"
                                            selectionMode="single"
                                            paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
                                            responsiveLayout="stack" breakpoint="960px" sortMode="multiple"
                                            currentPageReportTemplate="Showing {first} to {last} of {totalRecords}"
                                            showGridlines>

                                            <Column field="no" header="#"></Column>
                                            <Column field="noregistrasi" header="No Registrasi" />
                                            <Column field="jenisdiagnosa" header="Jenis Diagnosa" />
                                            <Column field="kddiagnosa" header="Kode ICD 10" />
                                            <Column field="namadiagnosa" header="Nama ICD 10" />
                                            <Column field="namaruangan" header="Ruangan" />
                                            <Column field="tglInput" header="TGL Input" />
                                        </DataTable>
                                    </p>
                                </template>
                            </VTabs>
                        </div>
                    </Fieldset>
                </div>

                <div class="column is-12">
                    <Fieldset :toggleable="true" legend="F. Tatalaksana">
                        <div class="column is-4">
                            <h1 class="emr mb-3">Tanggal dan Jam</h1>
                            <VField>
                                <VDatePicker v-model="input.waktuTatalaksana" mode="dateTime" style="width: 100%" trim-weeks
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
                        <div class="column is-8 pt-0">
                            <VField>
                                <VControl>
                                    <VTextarea v-model="input.ketTataLaksana" rows="3">
                                    </VTextarea>
                                </VControl>
                            </VField>
                        </div>
                    </Fieldset>
                </div>

                <div class="column is-12">
                    <Fieldset :toggleable="true" legend="G. Prognosis">
                        <div class="columns is-multiline">
                            <div class="column" v-for="(data) in prognosis">
                                <VField>
                                    <VControl raw subcontrol>
                                        <VCheckbox v-model="input.prognosis" :true-value="data" :label="data" class="p-0"
                                            color="primary" circle />
                                    </VControl>
                                </VField>
                            </div>
                        </div>
                    </Fieldset>
                </div>

                <div class="column is-12">
                    <Fieldset :toggleable="true" legend="H. Rencana Selanjutnya">
                        <div class="column is-12 pt-0">
                            <VField>
                                <VControl>
                                    <VTextarea v-model="input.rencanaSelanjutnya" rows="3">
                                    </VTextarea>
                                </VControl>
                            </VField>
                        </div>
                    </Fieldset>
                </div>

                <div class="column is-7">
                    <VCard class="border-card pink">
                        <div class="column">
                            <h1 class="emr mb-3">Garut,Tanggal dan Jam</h1>
                            <VField>
                                <VDatePicker v-model="input.waktuDibuat" mode="dateTime" style="width: 100%" trim-weeks
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
                        <div class="column">
                            <VField class="is-autocomplete-select" v-slot="{ id }">
                                <VControl icon="feather:search">
                                    <AutoComplete v-model="input.komiteMedis" :suggestions="d_Dokter"
                                        @complete="fetchDokter($event)" :optionLabel="'label'" :dropdown="true"
                                        :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                                        placeholder="Cari komite medis" />
                                </VControl>
                            </VField>
                        </div>
                    </VCard>

                </div>
            </VCard>
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
import Fieldset from 'primevue/fieldset';
import AutoComplete from 'primevue/autocomplete';
import { useViewWrapper } from '/@src/stores/viewWrapper'
import Slider from 'primevue/slider';
import { useUserSession } from '/@src/stores/userSession'


// ==================== Component form Data  ========================

let anamnesis = ref([
    {
        "title": "Keluhan Utama",
        "value": [
            {
                "subTitle": "",
                "type": "textarea",
                "model": "keluhanUtama",
                "value": "",
            }
        ]
    },
    {
        "title": "Riwayat Penyakit Sekarang",
        "value": [
            {
                "subTitle": "",
                "type": "textarea",
                "model": "riwayatPenyakitSekarang",
            }
        ]
    },
    {
        "title": "Riwayat Penyakit Dahulu",
        "value": [
            {
                "subTitle": "",
                "type": "textarea",
                "model": "riwayatPenyakitDahulu",
            }
        ]
    },
    {
        "title": "Hipertensi",
        "value": [
            {
                "subTitle": "Ya",
                "type": "checkBox",
                "model": "hipertensi",
            },
            {
                "subTitle": "Terkontrol",
                "type": "checkBox",
                "model": "hipertensi",
            },
            {
                "subTitle": "Tidak Terkontrol",
                "type": "checkBox",
                "model": "hipertensi",
            },
            {
                "subTitle": "Tidak",
                "type": "checkBox",
                "model": "hipertensi",
            },
        ]
    },
    {
        "title": "Merokok",
        "value": [
            {
                "subTitle": "Ya (berapa pack/hari,berapa lama, berhenti kapan)",
                "type": "checkBox",
                "model": "merokok",
            },
            {
                "subTitle": "",
                "type": "textBox",
                "model": "ketMerokok",
            },
            {
                "subTitle": "Tidak",
                "type": "checkBox",
                "model": "merokok",
            },
        ]
    },
    {
        "title": "Diabetes Melitus",
        "value": [
            {
                "subTitle": "Ya",
                "type": "checkBox",
                "model": "diabetesMelitus",
            },
            {
                "subTitle": "Terkontrol",
                "type": "checkBox",
                "model": "diabetesMelitus",
            },
            {
                "subTitle": "Tidak Terkontrol",
                "type": "checkBox",
                "model": "diabetesMelitus",
            },
            {
                "subTitle": "Tidak",
                "type": "checkBox",
                "model": "diabetesMelitus",
            },
        ]
    },
    {
        "title": "Dyslipidemia (kelainan kolestrol darah)",
        "value": [
            {
                "subTitle": "Ya",
                "type": "checkBox",
                "model": "dyslipidemia",
            },
            {
                "subTitle": "Terkontrol",
                "type": "checkBox",
                "model": "dyslipidemia",
            },
            {
                "subTitle": "Tidak Terkontrol",
                "type": "checkBox",
                "model": "dyslipidemia",
            },
            {
                "subTitle": "Tidak",
                "type": "checkBox",
                "model": "dyslipidemia",
            },
        ]
    },
    {
        "title": "Riwayat serangan jantung dini pada orang tua (pria <55 tahun atau wanita <65 tahun)",
        "value": [
            {
                "subTitle": "Ya",
                "type": "checkBox",
                "model": "riwayatSeranganJantung",
            },
            {
                "subTitle": "Tidak",
                "type": "checkBox",
                "model": "riwayatSeranganJantung",
            },
        ]
    },
    {
        "title": "Riwayat Alergi",
        "value": [
            {
                "subTitle": "",
                "type": "textarea",
                "model": "riwayatAlergi",
            },
        ]
    },
    {
        "title": "Riwayat Pengobatan",
        "value": [
            {
                "subTitle": "",
                "type": "textarea",
                "model": "riwayatPengobatan",
            },
        ]
    },
])

let pemeriksaanFisik = ref([
    {
        "title": "1. Keadaan Umum",
        "type": "checkBox",
        "value": [
            {
                "subTitle": "Baik",
                "model": "keadaanUmum",
                "satuan": ""
            },
            {
                "subTitle": "Sakit ringan",
                "model": "keadaanUmum",
                "satuan": ""
            },
            {
                "subTitle": "Sakit Sedang",
                "model": "keadaanUmum",
                "satuan": ""
            },
            {
                "subTitle": "Sakit Berat",
                "model": "keadaanUmum",
                "satuan": ""
            },
        ]
    },
    {
        "title": "2. Tanda Vital",
        "type": "textBox",
        "value": [
            {
                "subTitle": "Takanan Darah",
                "model": "tekananDarah",
                "satuan": "mmHg"
            },
            {
                "subTitle": "Frekuensi Nadi",
                "model": "frekuensiNadi",
                "satuan": "x/menit"
            },
            {
                "subTitle": "Suhu",
                "model": "suhu",
                "satuan": "°C"
            },
            {
                "subTitle": "Frekuensi Nafas",
                "model": "frekuensiNafas",
                "satuan": "x/menit"
            },
            {
                "subTitle": "Skor Nyeri",
                "model": "skorNyeri",
                "satuan": ""
            },
        ]
    },
    {
        "title": "3. Antropometri",
        "type": "textBox",
        "value": [
            {
                "subTitle": "Berat Badan",
                "model": "beratBadan",
                "satuan": "Kg"
            },
            {
                "subTitle": "Tinggi Badan",
                "model": "tinggiBadan",
                "satuan": "Cm"
            },
            {
                "subTitle": "Lingkar Perut",
                "model": "lingkarPerut",
                "satuan": "Cm"
            },
            {
                "subTitle": "IMT",
                "model": "imt",
                "satuan": ""
            },
        ]
    },
])

let kesadaran = ref([
    {
        "no": 1,
        "parameter": "E",
        "nilai": [
            {
                "model": "kesadaranE",
                "poin": "1"
            },
            {
                "model": "kesadaranE",
                "poin": "2"
            },
            {
                "model": "kesadaranE",
                "poin": "3"
            },
            {
                "model": "kesadaranE",
                "poin": "4"
            },
        ]
    },
    {
        "no": 2,
        "parameter": "M",
        "nilai": [
            {
                "model": "kesadaranM",
                "poin": "1"
            },
            {
                "model": "kesadaranM",
                "poin": "2"
            },
            {
                "model": "kesadaranM",
                "poin": "3"
            },
            {
                "model": "kesadaranM",
                "poin": "4"
            },
            {
                "model": "kesadaranM",
                "poin": "5"
            },
            {
                "model": "kesadaranM",
                "poin": "6"
            },
        ]
    },
    {
        "no": 3,
        "parameter": "V",
        "nilai": [
            {
                "model": "kesadaranV",
                "poin": "5"
            },
            {
                "model": "kesadaranV",
                "poin": "4"
            },
            {
                "model": "kesadaranV",
                "poin": "3"
            },
            {
                "model": "kesadaranV",
                "poin": "2"
            },
            {
                "model": "kesadaranV",
                "poin": "1"
            },
        ]
    },
])

let descRangeKesadaran = ref([
    {
        "des": "CMC (14-15)",
        "model": "rangeKesadaran",
        "value": {
            "keterangan": "CMC (14-15)",
            "poin": 15
        },
    },
    {
        "des": "Apatis (12-13)",
        "model": "rangeKesadaran",
        "value": {
            "keterangan": "Apatis (12-13)",
            "poin": 13
        },
    },
    {
        "des": "Somnolen (10-11)",
        "model": "rangeKesadaran",
        "value": {
            "keterangan": "Somnolen (10-11)",
            "poin": 11
        },
    },
    {
        "des": "Delirium (7-9)",
        "model": "rangeKesadaran",
        "value": {
            "keterangan": "Delirium (7-9)",
            "poin": 9
        },
    },
    {
        "des": "Stupar (4-6)",
        "model": "rangeKesadaran",
        "value": {
            "keterangan": "Stupar (4-6)",
            "poin": 6
        },
    },
    {
        "des": "Koma ( <= 3)",
        "model": "rangeKesadaran",
        "value": {
            "keterangan": "Koma ( <= 3)",
            "poin": 3
        },
    },
])

let pemeriksaanFisik2 = ref([
    {
        "title": "5. Mata",
        "value": [
            {
                "subTitle": "Konjungtiva",
                "model": "mataKonjungtiva",
                "item": ["Normal", "Anemis", "Lainnya"],
                "optional": [{
                    "keterangan": "",
                    'model': "ketKonjungtiva"
                }]
            },
            {
                "subTitle": "Sklera",
                "model": "mataSklera",
                "item": ["Normal", "Ikterik", "Lainnya"],
                "optional": [{
                    "keterangan": "",
                    'model': "ketSklera"
                }]
            },
        ]
    },
    {
        "title": "6. Hidung",
        "value": [
            {
                "subTitle": "",
                "model": "hidung",
                "item": ["Normal", "Nafas Cuping Hidung", "Lainnya"],
                "optional": [{
                    "keterangan": "",
                    'model': "ketHidung"
                }]
            },
        ]
    },
    {
        "title": "7. Bibir",
        "value": [
            {
                "subTitle": "",
                "model": "bibir",
                "item": ["Normal", "Sianosis", "Lainnya"],
                "optional": [{
                    "keterangan": "",
                    'model': "ketBibir"
                }]
            },
        ]
    },
    {
        "title": "8. Thorax",
        "value": [
            {
                "subTitle": "",
                "model": "thorax",
                "item": ["Simetris", "Tidak Simetris", "Barel Chest", "Pigeon Chest", "Pictus Excavatum",
                    "Pigeon Chest", "Picuts Excavatum", "Retraksi Dinding Dada",
                ],
                "optional": [{
                    "keterangan": "",
                    'model': "ketThorax"
                }]
            },
        ]
    },
    {
        "title": "9. Cor",
        "value": [
            {
                "subTitle": "Inspeksi",
                "model": "corInspeksi",
                "item": [" Ictus cordis tidak tampak", "Ictus cordis tampak", "Lainnya"],
                "optional": [{
                    "keterangan": "",
                    'model': "ketCorInspeksi"
                }]
            },
            {
                "subTitle": "Palpasi",
                "model": "corPalpasi",
                "item": ["Ictus cordis tidak teraba", "Ictus cordis teraba", "Lainnya"],
                "optional": [{
                    "keterangan": "",
                    'model': "ketCorPalpasi"
                }]
            },
            {
                "subTitle": "Perkusi",
                "model": "corPerkusi",
                "item": "",
                "optional": [{
                    "keterangan": "",
                    'model': "ketCorPerkusi"
                }]
            },
            {
                "subTitle": "Auskultasi",
                "model": "corAuskultasi",
                "item": ["S1 dan S2 Reguler", "Murmur", "Lainnya"],
                "optional": [{
                    "keterangan": "",
                    'model': "ketCorAuskultasi"
                }]
            },
        ]
    },
    {
        "title": "10. Paru",
        "value": [
            {
                "subTitle": "Inspeksi",
                "model": "paruInspeksi",
                "item": ["Normal", "Retraksi Dinding Dada", "Alat Bantu Nafas", "Lainnya"],
                "optional": [{
                    "keterangan": "",
                    'model': "ketParuInspeksi"
                }]
            },
            {
                "subTitle": "Palpasi",
                "model": "paruPalpasi",
                "item": ["Fermitus Kiri dan Kanan Sama", "Fermitus Kiri Menurun", "Fermitus Kiri Meningkat",
                    "Fermitus Kanan Menurun", "Fermitus Kanan Meningkat", "Lainnya"],
                "optional": [{
                    "keterangan": "",
                    'model': "ketParuPalpasi"
                }]
            },
            {
                "subTitle": "Perkusi",
                "model": "paruPerkusi",
                "item": ["Sonor", "Hipersonor", "Lainnya"],
                "optional": [{
                    "keterangan": "",
                    'model': "ketParuPerkusi"
                }]
            },
            {
                "subTitle": "Auskultasi",
                "model": "paruAuskultasi",
                "item": ["Ronki", "ketRonki", "Wheezing", "ketWheezing"],
                "optional": [{
                    "keterangan": "",
                    'model': "ketParuAuskultasi"
                }]
            },
        ]
    },
    {
        "title": "11. Abdomen",
        "value": [
            {
                "subTitle": "a. Inspeksi",
                "model": "abdomenInspeksi",
                "item": ["Datar", "Cekung", "Lainnya"],
                "optional": [{
                    "keterangan": "",
                    'model': "ketAbdomenInspeksi"
                }]
            },
            {
                "subTitle": "b. Palpasi",
                "model": "abdomenPalpasi",
                "item": ["Normal", "Nyeri Tekan"],
                "optional": [
                    {
                        "keterangan": "",
                        'model': "ketAbdomenPalpasi",
                    },
                    {
                        "keterangan": "Hepar",
                        'model': "ketAbdomenHepar",
                    },
                    {
                        "keterangan": "Limpa",
                        'model': "ketAbdomenLimpa",
                    },

                ]
            },
            {
                "subTitle": "c. Perkusi",
                "model": "abdomenPerkusi",
                "item": ["Timpani", "Hipertimpani", "Pekak"],
                "optional": [{
                    "keterangan": "",
                    'model': "ketAbdomenPerkusi"
                }]
            },
        ]
    },
])

let pemeriksaanFisik3 = ref([
    {
        "title": "12. Extremitas",
        "value": [
            {
                "name": "Akral Hangat",
                "model": "extremitas_1",
            },
            {
                "name": "Akral Dingin",
                "model": "extremitas_1",
            },
            {
                "name": "CRT < 2 detik",
                "model": "extremitas_2",
            },
            {
                "name": "CRT > 2 detik",
                "model": "extremitas_2",
            },
            {
                "name": "Petting Edem",
                "model": "extremitas_3",
            },
        ],
        "description": "ketExtremitas"
    },
    {
        "title": "13. Kekuatan Otot",
        "value": [],
        "description": "ketKekuatanOtot"
    },
    {
        "title": "14. Pemeriksaan Neurologi",
        "value": [],
        "description": "ketPemeriksaanNeurologi"
    }
])

let pemeriksaanPenunjang = ref([
    {
        "type": "checkBox",
        "model": "ekg",
        "title": "EKG",
    },
    {
        "type": "textarea",
        "model": "deskripsiEkg",
        "title": "",
    },
    {
        "type": "checkBox",
        "model": "echocardiography",
        "title": "Echocardiography",
    },
    {
        "type": "textarea",
        "model": "deskripsiEchocardiography",
        "title": "",
    },
    {
        "type": "checkBox",
        "model": "laboratorium",
        "title": "Laboratorium",
    },
    {
        "type": "textarea",
        "model": "deskripsiLaborat",
        "title": "",
    },
    {
        "type": "checkBox",
        "model": "radiologi",
        "title": "Radiologi",
    },
    {
        "type": "textarea",
        "model": "deskripsiRadiologi",
        "title": "",
    }
])

let prognosis = ref(["Bonam", "Dubia ad Bonam", "Dubia ad Malam", "Malam"])


// ================= End Component form Data  =======================



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

const route = useRoute()
const { y } = useWindowScroll()
const isStuck = computed(() => { return y.value > 30 })
const isLoading: any = ref(false)
const loadData: any = ref(true)
const isLoadingBtn: any = ref(false)
const item: any = reactive({
    NOREC_PD: NOREC_PD != undefined ? NOREC_PD : '',
    NOREC_APD: '',
    registrasi: {},
    pegawaiOrder: useUserSession().getUser().id,
    selectedMenu: [false]
})
const COLLECTION: any = ref('AssessmentAwalMedisRawatInap') //table mongodb
const NOREC_EMRPASIEN: any = ref('')
const input: any = ref({})
const dataSourceICD9 = ref([])
const dataSourceICD10 = ref([])
const d_Dokter = ref([])

const setView = () => {
    useHead({
        title: props.FORM_NAME + ' - ' + import.meta.env.VITE_PROJECT,
    })
    useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
    useViewWrapper().setFullWidth(true)
}

const loadRiwayat = () => {
    // if (NOREC_EMRPASIEN.value == '') return
    useApi().get(
        `/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`).then((response: any) => {
            if (response.length) {
                input.value = response[0] //set ke inputan 
                if (NOREC_EMRPASIEN.value == '') {
                    NOREC_EMRPASIEN.value = response[0].emrpasienfk
                }
            }
        })
}

const simpan = () => {

    let ID = input.value.id ? input.value.id : ''

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
    isLoadingBtn.value = true
    useApi().post(
        `/emr/simpan-emr`, json).then((response: any) => {
            isLoadingBtn.value = false
            NOREC_EMRPASIEN.value = response.norec_emr
        }).catch((e: any) => {
            isLoadingBtn.value = false
        })
}

const diagnosa = async () => {
    isLoading.value = true
    await useApi().get(`emr/get-diagnosa-pasien-icd9?nocmfk=${ID_PASIEN}`).then((response) => {
        response.forEach((element: any, i: any) => {
            element.no = i + 1
            element.tglInput = H.formatDate(element.tglinputdiagnosa, 'DD-MM-YYYY')
        });
        dataSourceICD9.value = response
        isLoading.value = false
        // console.log(response)
    })
    await useApi().get(`emr/get-diagnosa-pasien-icd10?nocmfk=${ID_PASIEN}`).then((response) => {
        response.forEach((element: any, i: any) => {
            element.no = i + 1
            element.tglInput = H.formatDate(element.tglinputdiagnosa, 'DD-MM-YYYY')
        });
        dataSourceICD10.value = response
        isLoading.value = false
    })
}

const fetchDokter = async (filter: any) => {

    await useApi().get(
        `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&settingdatafix=objectjenispegawaifk,idJenisPegawaiDokter&limit=10`
    ).then((response) => {
        d_Dokter.value = response
    })
}

const getDataExist = async () => {
    await useApi().get(
        `emr/get-data-exist?nocmfk=${ID_PASIEN}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`
    ).then((response) => {

        input.value.beratBadan = response[0].beratBadan
        input.value.tinggiBadan = response[0].tinggiBadan
        input.value.lingkarPerut = response[0].lingkarPerut
        input.value.imt = response[0].imt
    })
}

getDataExist()

const kembaliKeun = () => {
    window.history.back()
}

const countRangeNilai = (e: any) => {

    let cmc = {
        "keterangan": "CMC (14-15)",
        "poin": 15
    }
    let apatis = {
        "keterangan": "Apatis (12-13)",
        "poin": 13
    }
    let somnolen = {
        "keterangan": "Somnolen (10-11)",
        "poin": 11
    }
    let delirium = {
        "keterangan": "Delirium (7-9)",
        "poin": 9
    }
    let stupar = {
        "keterangan": "Stupar (4-6)",
        "poin": 6
    }
    let koma = {
        "keterangan": "Koma ( <= 3)",
        "poin": 3
    }

    descRangeKesadaran.value.forEach((elements: any) => {
        if (e <= 3 && e <= elements.value.poin) {
            input.value.rangeKesadaran = koma
        }
        else if (e <= 6 && e <= elements.value.poin) {
            input.value.rangeKesadaran = stupar
        }
        else if (e <= 9 && e <= elements.value.poin) {
            input.value.rangeKesadaran = delirium
        }
        else if (e <= 11 && e <= elements.value.poin) {
            input.value.rangeKesadaran = somnolen
        }
        else if (e <= 13 && e <= elements.value.poin) {
            input.value.rangeKesadaran = apatis
        }
        else if (e > 13 && e > elements.value.poin) {
            input.value.rangeKesadaran = cmc
        }
    })
}

const print = async () => {
    H.printBlade(`emr/cetak-asesmen-medis-ri?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`)
}

diagnosa()
setView()

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


watch(() => [
    input.value.kesadaranE,
    input.value.kesadaranM,
    input.value.kesadaranV,
    // input.value.point2
], () => {
    let poin1 = input.value.kesadaranE ? parseInt(input.value.kesadaranE) : 0
    let poin2 = input.value.kesadaranM ? parseInt(input.value.kesadaranM) : 0
    let poin3 = input.value.kesadaranV ? parseInt(input.value.kesadaranV) : 0
    const jumlahNilai = poin1 + poin2 + poin3
    countRangeNilai(jumlahNilai)
    input.value.totalKesadaran = jumlahNilai
})


</script>

<style lang="scss">
h1 {
    font-weight: bold;
}

.auto-left {
    margin-left: auto;
}
</style>