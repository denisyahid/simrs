<template>
    <div class="business-dashboard hr-dashboard">
        <div class="columns is-multiline">
            <div class="column is-8">
                <div class="columns is-multiline">
                    <div class="column is-12">
                        <VCard>
                            <div class="columns is-multiline">
                                <div class="column is-12">
                                    <h3 class="title is-5 mb-2 mr-1">Kendali Dokumen Rekam Medis </h3>
                                </div>
                            </div>
                            <div class="columns is-multiline">
                                <div class="column is-4">
                                    <VField>
                                        <VControl fullwidth>
                                            <VInput type="text" autofocus placeholder="Scan Barcode ..." autocomplete="off" v-model="item.qScan" v-on:keyup.enter="fetchPasien()" class="is-rounded" />
                                        </VControl>
                                    </VField>
                                </div>
                                <div class="column is-4">
                                    <VField>
                                        <VControl fullwidth>
                                            <VInput type="text" placeholder="Filter No RM ..." autocomplete="off" v-model="item.qnocm" v-on:keyup.enter="fetchPasien()" class="is-rounded" />
                                        </VControl>
                                    </VField>
                                </div>
                                <div class="column is-4">
                                    <VField>
                                        <VControl fullwidth>
                                            <VInput type="text" placeholder="Filter Nama ..." autocomplete="off" v-model="item.qnamapasien" v-on:keyup.enter="fetchPasien()" class="is-rounded" />
                                        </VControl>
                                    </VField>
                                </div>
                            </div>
                        </VCard>
                    </div>
                    <div class="column is-12">
                        <div class="flex-list-inner">
                            <VCard>
                                <div class="columns is-multiline">
                                    <div class="column is-4">
                                        <VField v-slot="{ id }" class="is-icon-select mt-1">
                                            <VControl>
                                                <Multiselect v-model="selectView" :attrs="{ id }"
                                                    placeholder="Select View" label="name" :options="d_View"
                                                    :searchable="true" track-by="name" mode="single"
                                                    @select="changeView(selectView)" autocomplete="off">
                                                    <template #singlelabel="{ value }">
                                                        <div class="multiselect-single-label">
                                                            <div class="select-label-icon-wrap">
                                                                <i :class="value.icon"></i>
                                                            </div>
                                                            <span class="select-label-text">
                                                                {{ value.name }}
                                                            </span>
                                                        </div>
                                                    </template>
                                                    <template #option="{ option }">
                                                        <div class="select-option-icon-wrap">
                                                            <i :class="option.icon"></i>
                                                        </div>
                                                        <span class="select-option-text">
                                                            {{ option.name }}
                                                        </span>
                                                    </template>
                                                </Multiselect>
                                            </VControl>
                                        </VField>
                                    </div>
                                    <div class="column is-12" v-if="ds_KendaliDokumen.loading">
                                        <div class="flex-list-inner mb-4">
                                            <div class="flex-table-item grid-item mb-4" v-for="key in 3" :key="key">
                                            <VFlexTableCell :column="{ grow: true, media: true }">
                                                <VPlaceloadAvatar size="medium" />
                                                <VPlaceloadText :lines="2" width="30%" last-line-width="20%" class="mx-2" />
                                            </VFlexTableCell>
                                            <VFlexTableCell>
                                                <VPlaceload width="100%" height="70px" class="mx-1 mt-2" />
                                            </VFlexTableCell>
                                            <VFlexTableCell>
                                                <VPlaceload width="10%" height="20px" class="mx-1 mt-1" />
                                            </VFlexTableCell>
                                            <VFlexTableCell :column="{ align: 'end' }">
                                                <VPlaceload width="10%" class="mx-1" />
                                            </VFlexTableCell>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="column is-12" v-else-if="ds_KendaliDokumen.length === 0">
                                        <VPlaceholderSection title="Data Tidak Ditemukan" subtitle="Silakan Pilih Tanggal Periode Registrasi." class="my-6">
                                            <template #image>
                                                <img class="light-image" src="/@src/assets/illustrations/placeholders/search-4.svg" alt="" />
                                                <img class="dark-image" src="/@src/assets/illustrations/placeholders/search-4-dark.svg" alt="" />
                                            </template>
                                        </VPlaceholderSection>
                                    </div>
                                    <div class="column is-12" v-else-if="ds_KendaliDokumen.length > 0">
                                        <div class="user-grid user-grid-v2" v-if="selectView == 'grid'">
                                            <TransitionGroup name="list" tag="div" class="columns is-multiline">

                                                <div v-for="item in ds_KendaliDokumen" :key="item.id" class="column is-4">
                                                    <div class="grid-item-wrap is-clickable">
                                                        <div class="grid-item-head">
                                                            <div class="flex-head">
                                                                <div class="meta">
                                                                    <span class="dark-inverted">{{ item.namapasien }}</span>
                                                                    <span class="dark-inverted">{{ item.namaruangan }}</span>
                                                                    <span>{{ item.noregistrasi }}</span>
                                                                    <span>
                                                                        {{  H.formatDateIndoSimple(item.tglregistrasi) }}
                                                                    </span>
                                                                </div>
                                                            </div>

                                                        </div>
                                                        <div class="flex-head" style=" display: flex; justify-content: space-between;">
                                                            <VTag v-if="item.kelompokpasien != null" class="mt-2 ml-2"
                                                                :label="item.kelompokpasien"
                                                                :color="item.kelompokpasien == 'BPJS' ? 'green' : 'orange'"
                                                                rounded />
                                                            <VDropdown icon="feather:more-vertical" spaced right>
                                                                <template #content>
                                                                    <!-- //cetaktracer -->
                                                                    <a @click="cetakTracerMedik(item.noregistrasi,item.nocm)" class="dropdown-item is-media">
                                                                        <div class="icon">
                                                                            <i aria-hidden="true" class="lnil lnil-search-alt"></i>
                                                                        </div>
                                                                        <div class="meta">
                                                                            <span>Print</span>
                                                                            <span>Cetak Label Tracer</span>
                                                                        </div>
                                                                    </a>
                                                                    <a @click="updateStatusDok(item, 'cari')" class="dropdown-item is-media">
                                                                        <div class="icon">
                                                                            <i aria-hidden="true" class="lnil lnil-search-alt"></i>
                                                                        </div>
                                                                        <div class="meta">
                                                                            <span>Cari</span>
                                                                            <span>Update Status Dokumen Dicari</span>
                                                                        </div>
                                                                    </a>
                                                                    <a @click="updateStatusDok(item, 'kirim')" class="dropdown-item is-media">
                                                                        <div class="icon">
                                                                            <i aria-hidden="true" class="lnil lnil-pointer"></i>
                                                                        </div>
                                                                        <div class="meta">
                                                                            <span>Kirim</span>
                                                                            <span>Update Status Dokumen Dikirim</span>
                                                                        </div>
                                                                    </a>
                                                                    <a @click="updateStatusDok(item, 'kembali')" class="dropdown-item is-media">
                                                                        <div class="icon">
                                                                            <i aria-hidden="true" class="lnil lnil-file-download"></i>
                                                                        </div>
                                                                        <div class="meta">
                                                                            <span>Kembali</span>
                                                                            <span>Update Status Dokumen Kembali</span>
                                                                        </div>
                                                                    </a>
                                                                </template>
                                                            </VDropdown>
                                                        </div>
                                                        <div class="grid-item">
                                                            <div class="people" style="padding-bottom:5px;">
                                                                <VIconBox size="medium" color="primary">
                                                                    <i class="lnil lnil-files-alt"></i>
                                                                </VIconBox>
                                                            </div>
                                                            <p style="font-weight:500; font-size:12pt;">{{ item.nocm }}</p>
                                                            <!-- <VTag class="mt-2 ml-2" label="Cari" color="danger" rounded v-if="item.iscari" />
                                                            <VTag class="mt-2 ml-2" label="Dikirim" color="warning" rounded v-else-if="item.isdikirim" />
                                                            <VTag class="mt-2 ml-2" label="Kembali" color="info" rounded  v-else-if="item.iskembali" /> -->
                                                            <VTag class="mt-1 ml-1" :label="item.status" :color="item.status === 'Cari' ? 'danger' : (item.status === 'Kembali' ? 'warning' : 'info')" rounded v-if="item.status"/>
                                                            <VTag class="mt-2 ml-2" label="-" color="solid" rounded v-else/>
                                                            <div class="people">
                                                                <VSnack :title="item.namadokter == null ? '-' : item.namadokter" color="info" icon="fa:user-md">
                                                                </VSnack>
                                                            </div>
                                                            <div class="buttons">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </TransitionGroup>
                                        </div>
                                        <div class="list-view list-view-v1" v-else-if="selectView  == 'list'">
                                            <div class="list-view-inner">
                                                <TransitionGroup name="list-complete" tag="div">
                                                    <div v-for="(item, key) in ds_KendaliDokumen" :key="key" class="list-view-item">
                                                        <div class="list-view-item-inner is-clickable">
                                                            <VIconBox size="medium" color="primary">
                                                                <i class="lnil lnil-files-alt"></i>
                                                            </VIconBox>
                                                            <div class="meta-left">
                                                                <h3>{{ item.namapasien }}</h3>
                                                                <span class="mt-1" style="font-weight:500; font-size:12pt;">{{ item.nocm }}</span>
                                                            </div>
                                                            <div class="meta-right">

                                                                <div class="stats">
                                                                    <div class="stat">
                                                                        <!-- <VTag class="mt-1 ml-1" label="Cari" color="danger" v-if="item.iscari" rounded />
                                                                        <VTag class="mt-1 ml-1" label="Dikirim" color="warning" v-if="item.isdikirim" rounded />
                                                                        <VTag class="mt-1 ml-1" label="Kembali" color="info" v-if="item.iskembali" rounded /> -->
                                                                        <VTag class="mt-1 ml-1" :label="item.status" :color="item.status === 'Cari' ? 'danger' : (item.status === 'Kembali' ? 'warning' : 'info')" rounded v-if="item.status"/>
                                                                        <VTag class="mt-2 ml-2" label="-" color="solid" rounded v-else/>
                                                                    </div>
                                                                    <div class="separator"></div>
                                                                    <div class="stat">
                                                                        <span>{{ item.namaruangan }}</span>
                                                                        <span>Ruangan</span>
                                                                    </div>
                                                                    <div class="separator"></div>
                                                                    <div class="stat">
                                                                        <span>{{ item.namadokter == null ? "-" : item.namadokter }}</span>
                                                                        <span>Dokter</span>
                                                                    </div>
                                                                    <div class="separator"></div>
                                                                    <div class="stat">
                                                                        <span>{{ item.noregistrasi }}</span>
                                                                        <span>No Registrasi</span>
                                                                    </div>
                                                                    <div class="separator"></div>
                                                                    <div class="stat">
                                                                        <span>{{ item.kelompokpasien }}</span>
                                                                    </div>
                                                                </div>

                                                                <VDropdown icon="feather:more-vertical" spaced right>
                                                                    <template #content>

                                                                        <!-- cetaktracer -->
                                                                        <a role="menuitem" @click="updateStatusDok(item, 'cari')"
                                                                            class="dropdown-item is-media">
                                                                            <div class="icon">
                                                                                <i aria-hidden="true"
                                                                                    class="lnil lnil-search-alt"></i>
                                                                            </div>
                                                                            <div class="meta">
                                                                                <span>Print</span>
                                                                                <span>Cetak Label Tracer</span>
                                                                            </div>
                                                                        </a>

                                                                        <a role="menuitem" @click="updateStatusDok(item, 'cari')"
                                                                            class="dropdown-item is-media">
                                                                            <div class="icon">
                                                                                <i aria-hidden="true"
                                                                                    class="lnil lnil-search-alt"></i>
                                                                            </div>
                                                                            <div class="meta">
                                                                                <span>Cari</span>
                                                                                <span>Update Status Dokumen Dicari</span>
                                                                            </div>
                                                                        </a>

                                                                        <a role="menuitem" @click="updateStatusDok(item, 'kirim')"
                                                                            class="dropdown-item is-media">
                                                                            <div class="icon">
                                                                                <i aria-hidden="true"
                                                                                    class="lnil lnil-pointer"></i>
                                                                            </div>
                                                                            <div class="meta">
                                                                                <span>Kirim</span>
                                                                                <span>Update Status Dokumen Dikirim</span>
                                                                            </div>
                                                                        </a>

                                                                        <a role="menuitem" @click="updateStatusDok(item, 'kembali')"
                                                                            class="dropdown-item is-media">
                                                                            <div class="icon">
                                                                                <i aria-hidden="true"
                                                                                    class="lnil lnil-file-download"></i>
                                                                            </div>
                                                                            <div class="meta">
                                                                                <span>Kembali</span>
                                                                                <span>Update Status Dokumen Kembali</span>
                                                                            </div>
                                                                        </a>
                                                                    </template>
                                                                </VDropdown>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </TransitionGroup>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </VCard>
                        </div>
                        <VFlexPagination v-model:current-page="currentPage.page" :item-per-page="currentPage.limit"
                            :total-items="item.total" :max-links-displayed="6">
                            <template #before-pagination>
                            </template>
                            <template #before-navigation>
                            <VFlex class="mr-4 mt-1" column-gap="1rem">
                                <VField>

                                </VField>
                                <VField>
                                <VControl>
                                    <div class="select is-rounded">
                                    <select v-model="currentPage.limit">
                                        <option :value="1">1 results per page</option>
                                        <option :value="6">6 results per page</option>
                                        <option :value="12">12 results per page</option>
                                        <option :value="24">24 results per page</option>
                                        <option :value="48">48 results per page</option>
                                        <option :value="96">96 results per page</option>
                                    </select>
                                    </div>
                                </VControl>
                                </VField>
                            </VFlex>
                            </template>
                        </VFlexPagination>
                    </div>
                </div>
            </div>
            <div class="column is-4">
                <div class="columns is-multiline">
                    <div class="column is-12">
                        <VCard>
                            <div class="columns is-multiline">
                                <div class="column is-12">
                                    <h3 class="title is-5 mb-2">Rekap
                                    </h3>
                                </div>
                            </div>
                            <div class="columns is-multiline">
                                <div class="column is-6">
                                    <CardCountRev icon="/images/simrs/icon-search.png" straight :total="item.c_cari"
                                        label="Di Cari" />
                                </div>
                                <div class="column is-6">
                                    <CardCountRev icon="/images/simrs/icon-send.png" straight :total="item.c_kirim"
                                        label="Di Kirim" />
                                </div>
                                <div class="column is-6">
                                    <CardCountRev icon="/images/simrs/icon-registrasi.png" straight :total="item.c_terima"
                                        label="Di Terima" />
                                </div>
                                <div class="column is-6">
                                    <CardCountRev icon="/images/simrs/icon-antrian.png" straight :total="item.c_total"
                                        label="Total Pasien" />
                                </div>
                            </div>
                        </VCard>
                    </div>
                    <div class="column is-12">
                        <VCard>
                            <div class="columns is-multiline">
                                <div class="column is-6">
                                    <h3 class="title is-5 mb-2 mr-1">Filters </h3>
                                    </div>
                                    <div class="column is-6">
                                    <a @click="clearFilter()" type="button" class="is-pulled-right mr-3" color="info" outlined raised> Clear
                                        All </a>
                                </div>
                                <div class="column is-12">
                                    <VField>
                                        <VLabel> Periode Registrasi </VLabel>
                                        <VDatePicker v-model="item.periode" is-range color="pink" trim-weeks>
                                        <template #default="{ inputValue, inputEvents }">
                                            <VField addons>
                                            <VControl icon="feather:calendar">
                                                <VInput :value="inputValue.start" v-on="inputEvents.start" />
                                            </VControl>
                                            <VControl>
                                                <VButton static icon="feather:arrow-right" />
                                            </VControl>
                                            <VControl subcontrol icon="feather:calendar">
                                                <VInput :value="inputValue.end" v-on="inputEvents.end" />
                                            </VControl>
                                            </VField>
                                        </template>
                                        </VDatePicker>
                                    </VField>
                                </div>
                                <div class="column is-12">
                                <VField>
                                    <VLabel> No Registrasi</VLabel>
                                        <VControl >
                                            <VInput type="text" placeholder=" No Registrasi ..." autocomplete="off" v-model="item.qnoreg" v-on:keyup.enter="fetchPasien()"  />
                                        </VControl>
                                    </VField>
                                </div>
                                <div class="column is-12">
                                    <VField class=" is-rounded-select is-autocomplete-select">
                                        <VLabel>Kelompok Pasien</VLabel>
                                        <VControl icon="feather:search" class="prime-auto">
                                        <Dropdown v-model="item.kelId" :options="d_KelompokPasien" :optionLabel="'kelompokpasien'" style="width: 100%;" :filter="true" appendTo="body" />
                                        </VControl>
                                    </VField>
                                </div>

                                <div class="column is-12">
                                    <VField class=" is-rounded-select is-autocomplete-select">
                                        <VLabel>Kelompok Pasien</VLabel>
                                        <VControl icon="feather:search" class="prime-auto">
                                        <Dropdown v-model="item.kelId" :options="d_KelompokPasien" :optionLabel="'kelompokpasien'" style="width: 100%;" :filter="true" appendTo="body" />
                                        </VControl>
                                    </VField>
                                </div>
                                <div class="column is-12">
                                    <VField class=" is-rounded-select is-autocomplete-select">
                                        <VLabel>Kelompok NO CM</VLabel>
                                        <VControl icon="feather:search" class="prime-auto">
                                        <Dropdown v-model="item.kelompokNocm" :options="d_KelompokNoCM" :optionLabel="'label'" style="width: 100%;" :filter="true" appendTo="body" @change="setKelompokNoCM" />
                                        </VControl>
                                    </VField>
                                </div>
                                <div class="column is-12">
                                    <VField class=" is-rounded-select is-autocomplete-select">
                                        <VLabel>Instalasi</VLabel>
                                        <VControl icon="feather:search" class="prime-auto">
                                        <Dropdown v-model="item.deptId" :options="d_Instalasi" :optionLabel="'namadepartemen'" style="width: 100%;" :filter="true" appendTo="body" @change="getRuanganBydeptId(item.deptId)" />
                                        </VControl>
                                    </VField>
                                </div>
                                <div class="column is-12">
                                    <VField class=" is-rounded-select is-autocomplete-select">
                                        <VLabel>Ruangan</VLabel>
                                        <VControl icon="feather:search" class="prime-auto">
                                        <Dropdown v-model="item.ruangId" :options="d_Ruangan" :optionLabel="'label'" style="width: 100%;" :filter="true" appendTo="body" />
                                        </VControl>
                                    </VField>
                                </div>
                                <div class="column is-12">
                                    <VField class=" is-rounded-select is-autocomplete-select">
                                        <VLabel>Status Dokumen</VLabel>
                                        <VControl icon="feather:search" class="prime-auto">
                                        <Dropdown v-model="item.statusDokumen" :options="d_StatusDokumen" :optionLabel="'label'" style="width: 100%;" :filter="true" appendTo="body" />
                                        </VControl>
                                    </VField>
                                </div>
                                <div class="column is-12">
                                    <VButton @click="filter()" :loading="ds_KendaliDokumen.loading" type="button" icon="feather:search"
                                        class="is-fullwidth mr-3" color="info" raised> Apply Filters
                                    </VButton>
                                </div>
                            </div>
                        </VCard>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script setup lang="ts">
import { useRoute, useRouter } from 'vue-router'
import { ref, computed, reactive, watch } from 'vue'
import { useThemeColors } from '/@src/composable/useThemeColors'
import { useApi } from '/@src/composable/useApi'
import { useUserSession } from '/@src/stores/userSession'
import { useHead } from '@vueuse/head'
import { useToaster } from '/@src/composable/toaster'
import moment, { isDate } from 'moment'
import * as H from '/@src/utils/appHelper'
import * as qzService from '/@src/utils/qzTrayService'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import CardCountRev from '/@src/components/partials/widgets/stat/CardCountRev.vue'
import Calendar from 'primevue/calendar';
import Dropdown from 'primevue/dropdown';

useHead({
    title: 'Kendali Dokumen Rekam Medis ' + import.meta.env.VITE_PROJECT,
})
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)

const route = useRoute()
const router = useRouter()
const userSession = useUserSession()
let listColor: any = ref(Object.keys(useThemeColors()))
let ds_KendaliDokumen: any = ref([])
let d_Instalasi: any = ref([])
let d_KelompokPasien: any = ref([])
let d_Ruangan: any = ref([])
const selectView: any = ref()
let isLoading: any = ref(false)
const currentPage: any = ref({
  limit: 6,
  rows: 50
})
selectView.value = 'grid'
const d_View = [
    {
        name: 'Grid View',
        value: 'grid',
        icon: 'fas fa-id-card-alt',
    },
    {
        name: 'List View',
        value: 'list',
        icon: 'fas fa-list',
    },
]
const item: any = ref({
    periode: reactive({
        start: new Date(),
        end: new Date(),
    }),
    c_cari: 0,
    c_kirim: 0,
    c_terima: 0,
    c_total: 0,
    total: 0,
})

const d_KelompokNoCM = ref([
  { label: 'GENAP',  value: 2},
  { label: 'GANJIL', value : 1},
])
const d_StatusDokumen = [
  { value: 'isBelumKirim', label: 'Belum Dikirim' },
  { value: 'isdikirim', label: 'Dikirm' },
  { value: 'iscari', label: 'Dicari' },
  { value: 'iskembali', label: 'Kembali' },
]

const fetchPasien = async () => {
    ds_KendaliDokumen.value = []
    ds_KendaliDokumen.value.loading = true

    let limit: any = currentPage.value.limit
    let offset: any = currentPage.value.page ? currentPage.value.page : 1
    offset = (offset * limit) - limit

    let deptId = '', ruangId = '', kelId = '', noreg = '',
        norm = '', namapasien = '', dari = '', sampai = '',
        qScan = ''

    if(!item.value.periode) {
        useToaster().error("Harap pilih periode registrasi terlebih dahulu !")
        return
    }


    dari = H.formatDate(item.value.periode.start, 'YYYY-MM-DD 00:00')
    sampai = H.formatDate(item.value.periode.end, 'YYYY-MM-DD 23:59')

    if (item.value.qnocm) norm = `&norm=${item.value.qnocm}`
    if (item.value.qnoreg) noreg = `&noreg=${item.value.qnoreg}`
    if (item.value.qScan) qScan = `&noreg=${item.value.qScan}`
    if (item.value.qnamapasien) namapasien = `&namapasien=${item.value.qnamapasien}`
    if (item.value.deptId) deptId = `&deptId=${item.value.deptId.id}`
    if (item.value.ruangId) ruangId = `&ruangId=${item.value.ruangId.value}`
    if (item.value.statusDokumen) status = `&status=${item.value.statusDokumen.value}`
    if (item.value.kelId) kelId = `&kelId=${item.value.kelId.id}`
    item.value.c_cari = 0
    item.value.c_kirim = 0
    item.value.c_terima = 0
    item.value.c_total = 0

    if(useUserSession().kelompokNoCM != ''){
        d_KelompokNoCM.value.forEach(element => {
            if(element.value == useUserSession().kelompokNoCM ){
                item.value.kelompokNocm = element
            }
        });
    }
    let kelompokNoCM = item.value.kelompokNocm ? `&kelompoknocm=${item.value.kelompokNocm.value}` : ''
    isLoading.value = true
    const response = await useApi().get(`/rekammedis/get-data-kendali-dokumen-rm?_total=true&dari=${dari}&sampai=${sampai}${kelompokNoCM}&offset=${offset}&limit=${limit}&rows=${currentPage.value.rows}${norm}${noreg}${namapasien}${deptId}${ruangId}${kelId}${qScan}${status}`)
    ds_KendaliDokumen.value.loading = false
    ds_KendaliDokumen.value = response.data
    item.value.total = response.total

    for (let i = 0; i < response.rekap.length; i++) {
        const element = response.rekap[i];
        if(element.iscari) item.value.c_cari = item.value.c_cari + 1
        if(element.isdikirim) item.value.c_kirim = item.value.c_kirim + 1
        if(element.iskembali) item.value.c_terima = item.value.c_terima + 1

        item.value.c_total = item.value.c_total + 1

    }
    isLoading.value = false

}

const updateStatusDok = async (item, cond) => {
    isLoading.value = true
    var json = {
        noregistrasifk: item.norec,
        update: cond,
    }
    await useApi()
    .post(`/rekammedis/update-status-kendali-dokumen-rm`, json)
    .then((response: any) => {
        isLoading.value = false
        fetchPasien()
    })
}
const fetchdDropdown = async () => {
    const response = await useApi().get(`/rekammedis/dropdown`)
    d_Instalasi.value = response.departemen.map((e: any) => { return { id: e.id, namadepartemen: e.namadepartemen, count: 0 } })
    d_KelompokPasien.value = response.kelompokpasien.map((e: any) => { return { id: e.id, kelompokpasien: e.kelompokpasien, count: 0 } })
}

const getRuanganBydeptId = async (e) => {
    let depId = '';
    if (e == undefined) {
      depId = '';
    } else {
      depId = e.id;
    }
    const response = await useApi().get(`/rekammedis/get-ruangan-by-departement?idDepartement=${e.id}`)
    d_Ruangan.value = response.ruangan.map((e: any) => { return { label: e.namaruangan, value: e.id, default: e } })
}

const filter = async () => {
    fetchPasien()
}

const changeView = (e: any) => {
    selectView.value = e
}
const clearFilter = () => {
    delete item.value.deptId
    delete item.value.ruangId
    delete item.value.kelId
}

const setKelompokNoCM = ()=>{
    userSession.setKelompokNoCM(item.value.kelompokNocm.value)
}

const cetakTracerMedik = (noreg: any,nocm:any) => {
    qzService.printData(`registrasi/cetak-tracer?pdf=true&noregistrasi=${noreg}`,'TRACER GENAP', 1)
    // if(parseInt(nocm) % 2 === 0){
    //     qzService.printData(`registrasi/cetak-tracer?pdf=true&noregistrasi=${noreg}`,'TRACER GENAP', 1)

    // }else{
    //     qzService.printData(`registrasi/cetak-tracer?pdf=true&noregistrasi=${noreg}`,'TRACER GANJIL', 1)
    // }
    //  H.printBlade(`registrasi/cetak-tracer?pdf=true&noregistrasi=${noreg}`);
}

watch(currentPage.value, () => {
    fetchPasien()
})


watch(
    () => item.value.qScan,
    async(newValue, oldValue) => {
        if(newValue!=oldValue){
            if(newValue.length == 10 ){
                await fetchPasien()
                for (let x = 0; x < ds_KendaliDokumen.value.length; x++) {
                    const element = ds_KendaliDokumen.value[x];
                    if(element.noregistrasi == newValue){
                        H.alert('info','Data Pasien '+element.namapasien + ' sedang dikirim')
                        await updateStatusDok(element, 'kirim')
                        break
                    }
                }

            }
        }
    }
)

fetchPasien()
fetchdDropdown()
getRuanganBydeptId({id : ""});
</script>

<style lang="scss">
@import '/@src/scss/abstracts/all';
@import '/@src/scss/custom/config';
@import '/@src/scss/module/rekammedis/kendalidokumenrm.scss';
</style>
