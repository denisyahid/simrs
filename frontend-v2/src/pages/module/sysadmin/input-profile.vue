<route lang="yaml">
    meta:
      requiresAuth: true
    </route>
<template>
    <div class="columns">
        <div class="column is-12 form-layout is-stacked">
            <div class="form-outer">
                <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
                    <div class="form-header-inner">
                        <div class="left">
                            <h3>Rekam Data Profile RS</h3>
                        </div>
                        <div class="right">
                            <div class="buttons">
                                <VButton icon="lnir lnir-arrow-left rem-100" @click="back()" light dark-outlined>
                                    Cancel
                                </VButton>
                                <VButton icon="feather:save" type="submit" color="primary" raised @click="simpanPegawai()"
                                    :loading="isSimpan">
                                    Save
                                </VButton>
                            </div>
                        </div>
                    </div>
                </div>
                <div></div>
                <div class="form-body p-4">
                    <VTabs slider selected="Pribadi" :tabs="[
                        { label: 'Data Pribadi', value: 'Pribadi' },
                        { label: 'Penetapan', value: 'Kepegawaian' },
                        { label: 'Riwayat Jabatan', value: 'Jabatan' },
                    ]">
                        <template #tab="{ activeValue }">
                            <p v-if="activeValue === 'Pribadi'">
                            <div class="columns is-multiline">
                                <div class="column is-12">

                                    <div class="columns is-multiline">


                                        <div class="column is-12">
                                            <VCard>
                                                <div class="columns is-multiline">
                                                    <div class="column is-4">
                                                        <VField>
                                                            <VLabel>Nama Rumah Sakit</VLabel>
                                                            <VControl icon="feather:user">
                                                                <VInput type="text" v-model="item.namalengkap"
                                                                    placeholder="Nama RS" class="is-rounded" />
                                                            </VControl>
                                                        </VField>
                                                    </div>
                                                    <div class="column is-4">
                                                        <VField>
                                                            <VLabel>Jenis Rumah Sakit</VLabel>
                                                            <VControl icon="feather:user">
                                                                <VInput type="text" v-model="item.reportdisplay"
                                                                    placeholder="Nama Panggilan" class="is-rounded" />
                                                            </VControl>
                                                        </VField>
                                                    </div>
                                                    <div class="column is-3">
                                                        <VField class="is-rounded-select is-autocomplete-select">
                                                            <VLabel>Tipe RS</VLabel>
                                                            <VControl icon="feather:search">
                                                                <Multiselect mode="single"
                                                                    v-model="item.objectkelaslevelfk"
                                                                    :options="d_KelasRS" placeholder="Pilih Tipe RS"
                                                                    :searchable="true" />
                                                            </VControl>
                                                        </VField>
                                                    </div>
                                                    <div class="column is-4">
                                                        <VField>
                                                            <VLabel>Nama Pemerintahan</VLabel>
                                                            <VControl icon="feather:credit-card">
                                                                <VInput type="text" v-model="item.namapemerintahan" placeholder="Nama Pemerintahan"
                                                                    class="is-rounded" />
                                                            </VControl>
                                                        </VField>
                                                    </div>
                                                    <div class="column is-4">
                                                        <VField>
                                                            <VLabel>Kota</VLabel>
                                                            <VControl icon="feather:home">
                                                                <VInput type="text" v-model="item.namakota"
                                                                    placeholder="Nama Kota" class="is-rounded" />
                                                            </VControl>
                                                        </VField>
                                                    </div>
                                                    <div class="column is-4">
                                                        <VDatePicker v-model="item.tglakreditasilast" color="green" trim-weeks
                                                            mode="date">
                                                            <template #default="{ inputValue, inputEvents }">
                                                                <VField>
                                                                    <VLabel>Tgl Akreditasi Terakhir</VLabel>
                                                                    <VControl icon="feather:calendar">
                                                                        <VInput type="text" placeholder="Pilih Tanggal"
                                                                            class="is-rounded" :value="inputValue"
                                                                            v-on="inputEvents" :disabled="disTanggal" />
                                                                    </VControl>
                                                                </VField>
                                                            </template>
                                                        </VDatePicker>
                                                    </div>
                                                    <div class="column is-8 pt-0">
                                                        <VField>
                                                            <VLabel>Moto Semboyan</VLabel>
                                                            <VControl icon="feather:home">
                                                                <VInput type="text" v-model="item.motosemboyan"
                                                                    placeholder="Moto Semboyan" class="is-rounded" />
                                                            </VControl>
                                                        </VField>
                                                    </div>
                                                    <div class="column is-4 pt-0">
                                                        <VField>
                                                            <VLabel>Kode RS</VLabel>
                                                            <VControl icon="feather:home">
                                                                <VInput type="text" v-model="item.koders"
                                                                    placeholder="Kode RS" class="is-rounded" />
                                                            </VControl>
                                                        </VField>
                                                    </div>
                                                    <div class="column is-4 pt-0">
                                                        <VField>
                                                            <VLabel>NPWP</VLabel>
                                                            <VControl icon="feather:home">
                                                                <VInput type="text" v-model="item.npwp"
                                                                    placeholder="NPWP" class="is-rounded" />
                                                            </VControl>
                                                        </VField>
                                                    </div>
                                                    <div class="column is-8 pt-0">
                                                        <VField class="is-rounded-select is-autocomplete-select" label="BPKB Atas Nama">
                                                            <VControl icon="feather:search" class="prime-auto-select">
                                                            <AutoComplete v-model="item.objectpemilikprofilefk" :suggestions="d_Pegawai" @complete="fetchPegawai($event)"
                                                                :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                                                                :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="Pilih Pegawai" />
                                                            </VControl>
                                                        </VField>
                                                    </div>
                                                </div>
                                            </VCard>
                                        </div>

                                        <div class="column is-12">
                                            <Fieldset legend="Data Alamat" :toggleable="true" :collapsed="collapsedOps">
                                                <div class="columns is-multiline">

                                                    <div class="column is-12">
                                                        <VField>
                                                            <VLabel>Alamat Lengkap</VLabel>
                                                            <VControl icon="feather:home">
                                                                <VInput type="text" v-model="item.alamatlengkap"
                                                                    placeholder="Alamat Lengkap" class="is-rounded" />
                                                            </VControl>
                                                        </VField>
                                                    </div>
                                                    <div class="column is-2">
                                                        <VField>
                                                            <VLabel> Kode Pos </VLabel>
                                                            <VControl icon="feather:home">
                                                                <VInput type="number" v-model="item.kodepos"
                                                                    placeholder="Kode Pos" class="is-rounded" />
                                                            </VControl>
                                                        </VField>
                                                    </div>
                                                    <div class="column is-3">
                                                        <VField>
                                                            <VLabel> Nomor Telpon </VLabel>
                                                            <VControl icon="feather:phone-call">
                                                                <VInput type="number" v-model="item.fixedphone"
                                                                    placeholder="No. Telepon" class="is-rounded" />
                                                            </VControl>
                                                        </VField>
                                                    </div>
                                                    <div class="column is-4">
                                                        <VField>
                                                            <VLabel>Fax</VLabel>
                                                            <VControl icon="feather:layers">
                                                                <VInput type="text" v-model="item.faksimile" placeholder="Fax"
                                                                    class="is-rounded" />
                                                            </VControl>
                                                        </VField>
                                                    </div>
                                                    <div class="column is-3">
                                                        <VField>
                                                            <VLabel> Email </VLabel>
                                                            <VControl icon="feather:layers">
                                                                <VInput type="text" v-model="item.alamatemail" placeholder="Email"
                                                                    class="is-rounded" />
                                                            </VControl>
                                                        </VField>
                                                    </div>
                                                    <div class="column is-6">
                                                        <VField>
                                                            <VLabel>Website</VLabel>
                                                            <VControl icon="feather:layers">
                                                                <VInput type="text" v-model="item.website"
                                                                    placeholder="Alamat Website" class="is-rounded" />
                                                            </VControl>
                                                        </VField>
                                                    </div>
                                                </div>
                                            </Fieldset>
                                        </div>

                                        <!-- <div class="column is-12">
                                            <Fieldset legend="Data Pendidikan" :toggleable="true" :collapsed="collapsedOps">
                                                <div class="column is-12">
                                                    <Toolbar class="mb-4">
                                                        <template #start>
                                                            <VButton icon="feather:plus" color="info" raised
                                                                @click="addPopUp1()">
                                                                Tambah Riwayat Pendidikan
                                                            </VButton>
                                                        </template>
                                                    </Toolbar>

                                                    <DataTable :value="dataSourcePendidikan" :paginator="true" :rows="10"
                                                        :rowsPerPageOptions="[5, 10, 25]"
                                                        paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
                                                        responsiveLayout="stack" breakpoint="960px" sortMode="multiple"
                                                        currentPageReportTemplate="Showing {first} to {last} of {totalRecords}">
                                                        <Column :exportable="false" header="#" style="width: 8rem">
                                                            <template #body="slotProps">
                                                                <Button icon="pi pi-pencil"
                                                                    class="p-button-rounded p-button-warning mr-2"
                                                                    @click="editRow(slotProps.data)" />
                                                                <Button icon="pi pi-trash"
                                                                    class="p-button-rounded p-button-danger"
                                                                    @click="hapusRow(slotProps.data)" />
                                                            </template>
                                                        </Column>

                                                        <Column field="no" header="No"></Column>
                                                        <Column field="objectpendidikanfk" header="Pendidikan">
                                                        </Column>
                                                        <Column field="namatempatpendidikan" header="Institusi Pendidikan">
                                                        </Column>
                                                        <Column field="alamattempatpendidikan" header="Alamat">
                                                        </Column>
                                                        <Column field="tglmasuk" header="Tahun Masuk"></Column>
                                                        <Column field="tgllulus" header="Tahun Lulus"></Column>
                                                        <template #paginatorstart>
                                                            <Button type="button" icon="pi pi-refresh"
                                                                class="p-button-text" />
                                                        </template>
                                                        <template #paginatorend>
                                                            <Button type="button" icon="pi pi-cloud"
                                                                class="p-button-text" />
                                                        </template>
                                                    </DataTable>
                                                </div>
                                            </Fieldset>
                                        </div>

                                        <div class="column is-12">
                                            <Fieldset legend="Data Keluarga" :toggleable="true" :collapsed="collapsedOps">
                                                <div class="column is-12">
                                                    <Toolbar class="mb-4">
                                                        <template #start>
                                                            <VButton icon="feather:plus" color="info" raised
                                                                @click="addPopUp2()">
                                                                Tambah Data Keluarga
                                                            </VButton>
                                                        </template>
                                                    </Toolbar>

                                                    <DataTable :value="dataSourceKeluarga" :paginator="true" :rows="10"
                                                        :rowsPerPageOptions="[5, 10, 25]"
                                                        paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
                                                        responsiveLayout="stack" breakpoint="960px" sortMode="multiple"
                                                        currentPageReportTemplate="Showing {first} to {last} of {totalRecords}">
                                                        <Column :exportable="false" header="#" style="width: 8rem">
                                                            <template #body="slotProps">
                                                                <Button icon="pi pi-pencil"
                                                                    class="p-button-rounded p-button-warning mr-2" @click="editKeluarga(slotProps.data)" />
                                                                <Button icon="pi pi-trash"
                                                                    class="p-button-rounded p-button-danger" />
                                                            </template>
                                                        </Column>

                                                        <Column field="no" header="No"></Column>
                                                        <Column field="namalengkap" header="Nama"></Column>
                                                        <Column field="hubungankeluarga" header="Hubungan Keluarga">
                                                        </Column>
                                                        <Column field="tgllahir" header="Tanggal Lahir"></Column>
                                                        <Column field="pendidikan" header="Pendidikan"></Column>
                                                        <Column field="pekerjaan" header="Pekerjaan"></Column>
                                                        <template #paginatorstart>
                                                            <Button type="button" icon="pi pi-refresh"
                                                                class="p-button-text" />
                                                        </template>
                                                        <template #paginatorend>
                                                            <Button type="button" icon="pi pi-cloud"
                                                                class="p-button-text" />
                                                        </template>
                                                    </DataTable>
                                                </div>
                                            </Fieldset>

                                        </div> -->
                                    </div>

                                </div>

                            </div>
                            </p>
                            <p v-else-if="activeValue === 'Kepegawaian'">
                            <div class="column is-12">
                                <div class="columns is-multiline">
                                    <div class="column is-6">
                                        <VField class="is-rounded-select is-autocomplete-select">
                                            <VLabel>Unit Kerja</VLabel>
                                            <VControl icon="feather:search">
                                                <Multiselect mode="single" v-model="item.objectunitkerjafk"
                                                    :options="d_departemen" placeholder="Pilih" :searchable="true"
                                                    :disabled="disabledRuangan" />
                                            </VControl>
                                        </VField>
                                    </div>
                                    <div class="column is-6">
                                        <VField class="is-rounded-select is-autocomplete-select">
                                            <VLabel>Status Pegawai</VLabel>
                                            <VControl icon="feather:search">
                                                <Multiselect mode="single" v-model="item.objectstatuspegawaifk"
                                                    :options="d_statuspegawai" placeholder="Pilih" :searchable="true"
                                                    :disabled="disabledRuangan" />
                                            </VControl>
                                        </VField>
                                    </div>
                                    <div class="column is-4">
                                        <VDatePicker v-model="item.tglmasuk" color="green" trim-weeks mode="dateTime">
                                            <template #default="{ inputValue, inputEvents }">
                                                <VField>
                                                    <VLabel>Tanggal Masuk</VLabel>
                                                    <VControl icon="feather:calendar">
                                                        <VInput type="text" placeholder="Pilih Tanggal Masuk"
                                                            class="is-rounded" :value="inputValue" v-on="inputEvents"
                                                            :disabled="disTanggal" />
                                                    </VControl>
                                                </VField>
                                            </template>
                                        </VDatePicker>
                                    </div>
                                    <div class="column is-4">
                                        <VDatePicker v-model="item.tglkeluar" color="green" trim-weeks mode="dateTime">
                                            <template #default="{ inputValue, inputEvents }">
                                                <VField>
                                                    <VLabel>Tanggal Keluar</VLabel>
                                                    <VControl icon="feather:calendar">
                                                        <VInput type="text" placeholder="Pilih Tanggal Keluar"
                                                            class="is-rounded" :value="inputValue" v-on="inputEvents"
                                                            :disabled="disTanggal" />
                                                    </VControl>
                                                </VField>
                                            </template>
                                        </VDatePicker>
                                    </div>
                                    <!-- <div class="column is-4">
                                        <VField>
                                            <VLabel> Masa Kerja </VLabel>
                                            <VControl icon="feather:layers">
                                                <VInput type="text" v-model="item.masakerja" placeholder="Masa Kerja"
                                                    class="is-rounded" />
                                            </VControl>
                                        </VField>
                                    </div> -->
                                    <div class="column is-3">
                                        <VField>
                                            <VLabel> Usia Pensiun </VLabel>
                                            <VControl icon="feather:layers">
                                                <VInput type="text" v-model="item.pensiun" placeholder="Usia Pensiun"
                                                    class="is-rounded" />
                                            </VControl>
                                        </VField>
                                    </div>
                                    <div class="column is-3">
                                        <VDatePicker v-model="item.tglpensiun" color="green" trim-weeks mode="dateTime">
                                            <template #default="{ inputValue, inputEvents }">
                                                <VField>
                                                    <VLabel>Tanggal Pensiun</VLabel>
                                                    <VControl icon="feather:calendar">
                                                        <VInput type="text" placeholder="Pilih Tanggal Pensiun"
                                                            class="is-rounded" :value="inputValue" v-on="inputEvents"
                                                            :disabled="disTanggal" />
                                                    </VControl>
                                                </VField>
                                            </template>
                                        </VDatePicker>
                                    </div>
                                    <div class="column is-3">
                                        <VField class="is-rounded-select is-autocomplete-select">
                                            <VLabel>Kedudukan Pegawai</VLabel>
                                            <VControl icon="feather:search">
                                                <Multiselect mode="single" v-model="item.kedudukanfk" :options="d_kedudukan"
                                                    placeholder="Pilih" :searchable="true" :disabled="disabledRuangan" />
                                            </VControl>
                                        </VField>
                                    </div>
                                    <div class="column is-3">
                                        <VField class="is-rounded-select is-autocomplete-select">
                                            <VLabel>Golongan Pegawai</VLabel>
                                            <VControl icon="feather:search">
                                                <Multiselect mode="single" v-model="item.objectgolonganpegawaifk"
                                                    :options="d_golonganpegawai" placeholder="Pilih" :searchable="true"
                                                    :disabled="disabledRuangan" />
                                            </VControl>
                                        </VField>
                                    </div>
                                    <div class="column is-3">
                                        <VField class="is-rounded-select is-autocomplete-select">
                                            <VLabel>Jabatan Fungsional</VLabel>
                                            <VControl icon="feather:search">
                                                <Multiselect mode="single" v-model="item.objectjabatanfungsionalfk"
                                                    :options="d_jabatan" placeholder="Pilih" :searchable="true"
                                                    :disabled="disabledRuangan" />
                                            </VControl>
                                        </VField>
                                    </div>
                                    <div class="column is-3">
                                        <VField class="is-rounded-select is-autocomplete-select">
                                            <VLabel>Kelompok Jabatan</VLabel>
                                            <VControl icon="feather:search">
                                                <Multiselect mode="single" v-model="item.objectkelompokjabatanfk"
                                                    :options="d_kelompokjabatan" placeholder="Pilih" :searchable="true"
                                                    :disabled="disabledRuangan" />
                                            </VControl>
                                        </VField>
                                    </div>
                                    <div class="column is-3">
                                        <VField class="is-rounded-select is-autocomplete-select">
                                            <VLabel>Eselon</VLabel>
                                            <VControl icon="feather:search">
                                                <Multiselect mode="single" v-model="item.objecteselonfk" :options="d_eselon"
                                                    placeholder="Pilih" :searchable="true" :disabled="disabledRuangan" />
                                            </VControl>
                                        </VField>
                                    </div>
                                    <div class="column is-3">
                                        <VField class="is-rounded-select is-autocomplete-select">
                                            <VLabel>Jenis Pegawai</VLabel>
                                            <VControl icon="feather:search">
                                                <Multiselect mode="single" v-model="item.objectjenispegawaifk"
                                                    :options="d_jenispegawai" placeholder="Pilih" :searchable="true"
                                                    :disabled="disabledRuangan" />
                                            </VControl>
                                        </VField>
                                    </div>
                                    <div class="column is-3">
                                        <VField class="is-rounded-select is-autocomplete-select">
                                            <VLabel>Pola Shift Kerja</VLabel>
                                            <VControl icon="feather:search">
                                                <Multiselect mode="single" v-model="item.objectshiftkerja"
                                                    :options="d_shiftkerja" placeholder="Pilih" :searchable="true"
                                                    :disabled="disabledRuangan" />
                                            </VControl>
                                        </VField>
                                    </div>
                                    <div class="column is-3">
                                        <VField>
                                            <VLabel> ID Finger Print </VLabel>
                                            <VControl icon="feather:layers">
                                                <VInput type="text" v-model="item.fingerprintid" placeholder="Finger Print"
                                                    class="is-rounded" />
                                            </VControl>
                                        </VField>
                                    </div>
                                    <div class="column is-3">
                                        <VField>
                                            <VLabel> Nilai Jabatan </VLabel>
                                            <VControl icon="feather:layers">
                                                <VInput type="text" v-model="item.nilaijabatan" placeholder="Nilai Jabatan"
                                                    class="is-rounded" />
                                            </VControl>
                                        </VField>
                                    </div>
                                    <div class="column is-3">
                                        <VField>
                                            <VLabel> Grade </VLabel>
                                            <VControl icon="feather:layers">
                                                <VInput type="text" v-model="item.grade" placeholder="Grade"
                                                    class="is-rounded" />
                                            </VControl>
                                        </VField>
                                    </div>
                                </div>
                            </div>

                            </p>
                            <p v-else="activeValue === 'Jabatan'"> Zook</p>
                        </template>
                    </VTabs>
                </div>
            </div>
        </div>

    </div>
</template>
<script setup lang="ts">
import { useWindowScroll } from '@vueuse/core'
import { useApi } from '/@src/composable/useApi'
import {
    ref,
    computed,
    defineComponent,
    watch,
    nextTick,
    onMounted,
    reactive,
    watchEffect,
} from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useHead } from '@vueuse/head'
import { formatRp } from '/@src/utils/appHelper'
import { useToaster } from '/@src/composable/toaster'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import PrimeVue from 'primevue/config'
import DataTable from 'primevue/datatable'
import Column from 'primevue/column'
import Toolbar from 'primevue/toolbar'
import Button from 'primevue/button'
import Fieldset from 'primevue/fieldset'
import moment from 'moment'
const TITLE_PAGE = 'Pegawai'

import * as H from '/@src/utils/appHelper'
useHead({
    title: `${TITLE_PAGE} - Transmedic`,
})
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
let ID_HARGANETTO = useRoute().query.id as string
let ID_HARGANETTO_SET = ref()
let ID_PEGAWAI = useRoute().query.id as string
let NOREC_APD = useRoute().query.norec_apd as string
const modalInputPendidikan = ref(false)
const modalInputKeluarga = ref(false)

let item: any = reactive({
    header: {}
})
const TOTAL: any = ref(0)
const { y } = useWindowScroll()
const isStuck = computed(() => {
    return y.value > 30
})
const d_Pegawai: any = ref([])
const d_KelasRS: any = ref([])
const d_hubungan: any = ref([])
const dataSource: any = ref([])
const dataSourcePendidikan: any = ref([])
const dataSourceKeluarga: any = ref([])
const data2: any = ref([])
const data3: any = ref([])
const collapsedOps: any = ref(true)
const isLoading: any = ref(false)
const isSimpan: any = ref(false)
const isHRIS: any = ref(false)
let isRegistrasi = ref(false)
const disabledRuangan: any = ref(false)
const dataSelected: any = ref({})
const disTanggal: any = ref(false)

// const onInit = ()=>{
//     loadDrop()
// }

const fetchPegawai = async (filter: any) => {
  await useApi().get(
    `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`
  ).then((response) => {
    d_Pegawai.value = response
  })
}

const loadDrop = async ()=>{
    await useApi().get(`/sysadmin/get-profile-dropdown`).then((response)=>{
      d_KelasRS.value = response.tipeRs.map((e:any)=>{
        return {label : e.kelasrs , value : e.id}
      })
    })
}

function back() {
    window.history.back()
}

loadDrop()

</script>
    
<style lang="scss">
@import '/@src/scss/abstracts/all';
@import '/@src/scss/components/forms-outer';
@import '/@src/scss/custom/config';

//     .tabs-wrapper.is-slider .tabs,
// .tabs-wrapper-alt.is-slider .tabs {
//   position: relative;
//   background: var(--fade-grey-light-2);
//   border: 1px solid var(--fade-grey);
//   max-width: 100%;
//   height: 35px;
//   border-bottom: none;

// }

.tabs-wrapper.is-triple-slider .tabs li a,
.tabs-wrapper-alt.is-triple-slider .tabs li a {
    color: hsl(0deg, 0%, 4%);
    font-family: var(--font);
    font-weight: 400;
    height: 40px;
    border-bottom: none;
    position: relative;
    z-index: 5;
}

.field>label {
    font-family: var(--font);
    font-size: 0.9rem;
    color: hsl(0deg, 0%, 4%) !important;
    font-weight: 400;
}
</style>
    