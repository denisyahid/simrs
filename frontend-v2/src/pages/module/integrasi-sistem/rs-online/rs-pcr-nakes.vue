<template>
    <VCard>
        <h3>Rekap Pemeriksaan PCR Untuk Nakes</h3>
        <div class="columns is-multiline mt-2">
            <div class="column is-12">
                <div class="columns is-multiline">
                    <div class="column is-4">
                        <span>Periode</span>
                        <VDatePicker v-model="item.filterDate" is-range color="pink" trim-weeks :max-date="new Date()">
                            <template #default="{ inputValue, inputEvents }">
                                <VField addons>
                                    <VControl icon="feather:calendar">
                                        <VInput :value="inputValue.start" v-on="inputEvents.start" />
                                    </VControl>

                                </VField>
                            </template>
                        </VDatePicker>
                    </div>

                    <div class="column is-2" style="margin-top: 30px">
                        <VIconButton circle icon="feather:refresh-cw" raised bold @click="SearchData()"
                            :loading="isLoading" v-tooltip.bubble="'Cari'" class="mt-2-min">
                        </VIconButton>
                    </div>
                </div>

                <div class="column is-12" v-if="isLoading">
                    <VPlaceloadWrap>
                        <VPlaceload height="500px" width="100%" class="mx-2" />
                    </VPlaceloadWrap>
                </div>
                <div class="column is-12" v-else>

                    <DataTable :value="dataSource" dataKey="no" class="p-datatable-sm" :paginator="true" :rows="10"
                        :rowsPerPageOptions="[5, 10, 25]" scrollable
                        paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
                        responsiveLayout="stack" breakpoint="960px" sortMode="multiple"
                        currentPageReportTemplate="Showing {first} to {last} of {totalRecords}" showGridlines>

                        <template #header>
                            <div class="flex justify-content-between">

                                <VButton color="success" class="mr-4 mb-3" icon="fas fa-plus" raised @click="tambahData()">
                                    Tambah Data </VButton>
                            </div>
                        </template>

                        <ColumnGroup type="header">
                            <Row>
                                <Column field="tanggal" header="Tanggal" :rowspan="3" style="min-width: 200px" />
                                <Column header="Dokter Umum" :colspan="3" />
                                <Column header="Dokter Spesialis" :colspan="3" />
                                <Column header="Dokter Gigi" :colspan="3" />
                                <Column header="Residen" :colspan="3" />
                                <Column header="Perawat" :colspan="3" />
                                <Column header="Apoteker" :colspan="3" />
                                <Column header="Analisis Lab" :colspan="3" />
                                <Column header="COASS" :colspan="3" />
                                <Column header="Internship" :colspan="3" />
                                <Column header="Nakes Lainnya" :colspan="3" />
                            </Row>

                            <Row>
                                <Column field="jumlah_tenaga_dokter_umum" header="Jumlah Tenaga" style="min-width: 100px" />
                                <Column field="sudah_periksa_dokter_umum" header="Yang Sudah Diperiksa Swab PCR"
                                    style="min-width: 100px" />
                                <Column field="hasil_pcr_dokter_umum" header="Hasil PCR Terkonfirmasi"
                                    style="min-width: 100px" />

                                <Column field="jumlah_tenaga_dokter_spesialis" header="Jumlah Tenaga"
                                    style="min-width: 100px" />
                                <Column field="sudah_periksa_dokter_spesialis" header="Yang Sudah Diperiksa Swab PCR"
                                    style="min-width: 100px" />
                                <Column field="hasil_pcr_dokter_spesialis" header="Hasil PCR Terkonfirmasi"
                                    style="min-width: 100px" />

                                <Column field="jumlah_tenaga_dokter_gigi" header="Jumlah Tenaga" style="min-width: 100px" />
                                <Column field="sudah_periksa_dokter_gigi" header="Yang Sudah Diperiksa Swab PCR"
                                    style="min-width: 100px" />
                                <Column field="hasil_pcr_dokter_gigi" header="Hasil PCR Terkonfirmasi"
                                    style="min-width: 100px" />

                                <Column field="jumlah_tenaga_residen" header="Jumlah Tenaga" style="min-width: 100px" />
                                <Column field="sudah_periksa_residen" header="Yang Sudah Diperiksa Swab PCR"
                                    style="min-width: 100px" />
                                <Column field="hasil_pcr_residen" header="Hasil PCR Terkonfirmasi"
                                    style="min-width: 100px" />

                                <Column field="jumlah_tenaga_perawat" header="Jumlah Tenaga" style="min-width: 100px" />
                                <Column field="sudah_periksa_perawat" header="Yang Sudah Diperiksa Swab PCR"
                                    style="min-width: 100px" />
                                <Column field="hasil_pcr_perawat" header="Hasil PCR Terkonfirmasi"
                                    style="min-width: 100px" />

                                <Column field="jumlah_tenaga_bidan" header="Jumlah Tenaga" style="min-width: 100px" />
                                <Column field="sudah_periksa_bidan" header="Yang Sudah Diperiksa Swab PCR"
                                    style="min-width: 100px" />
                                <Column field="hasil_pcr_bidan" header="Hasil PCR Terkonfirmasi" style="min-width: 100px" />

                                <Column field="jumlah_tenaga_apoteker" header="Jumlah Tenaga" style="min-width: 100px" />
                                <Column field="sudah_periksa_apoteker" header="Yang Sudah Diperiksa Swab PCR"
                                    style="min-width: 100px" />
                                <Column field="hasil_pcr_apoteker" header="Hasil PCR Terkonfirmasi"
                                    style="min-width: 100px" />

                                <Column field="jumlah_tenaga_radiografer" header="Jumlah Tenaga" style="min-width: 100px" />
                                <Column field="sudah_periksa_radiografer" header="Yang Sudah Diperiksa Swab PCR"
                                    style="min-width: 100px" />
                                <Column field="hasil_pcr_radiografer" header="Hasil PCR Terkonfirmasi"
                                    style="min-width: 100px" />

                                <Column field="jumlah_tenaga_analis_lab" header="Jumlah Tenaga" style="min-width: 100px" />
                                <Column field="sudah_periksa_analis_lab" header="Yang Sudah Diperiksa Swab PCR"
                                    style="min-width: 100px" />
                                <Column field="hasil_pcr_analis_lab" header="Hasil PCR Terkonfirmasi"
                                    style="min-width: 100px" />

                                <Column field="jumlah_tenaga_co_ass" header="Jumlah Tenaga" style="min-width: 100px" />
                                <Column field="sudah_periksa_co_ass" header="Yang Sudah Diperiksa Swab PCR"
                                    style="min-width: 100px" />
                                <Column field="hasil_pcr_co_ass" header="Hasil PCR Terkonfirmasi"
                                    style="min-width: 100px" />

                                <Column field="jumlah_tenaga_internship" header="Jumlah Tenaga" style="min-width: 100px" />
                                <Column field="sudah_periksa_internship" header="Yang Sudah Diperiksa Swab PCR"
                                    style="min-width: 100px" />
                                <Column field="hasil_pcr_internship" header="Hasil PCR Terkonfirmasi"
                                    style="min-width: 100px" />

                                <Column field="jumlah_tenaga_nakes_lainnya" header="Jumlah Tenaga"
                                    style="min-width: 100px" />
                                <Column field="sudah_periksa_nakes_lainnya" header="Yang Sudah Diperiksa Swab PCR"
                                    style="min-width: 100px" />
                                <Column field="hasil_pcr_nakes_lainnya" header="Hasil PCR Terkonfirmasi"
                                    style="min-width: 100px" />
                            </Row>

                        </ColumnGroup>

                    </DataTable>
                </div>
            </div>

        </div>

    </VCard>

    <Dialog v-model:visible="modalInput" modal header="Laporan Pemeriksaan SWAB PCR untuk Nakes" :style="{ width: '80vw' }">

        <div class="column is-4">
            <VDatePicker class="pt-0 pb-0 pl-0" v-model="item.tanggal" color="green" trim-weeks mode="dateTime"
                :max-date="new Date()">
                <template #default="{ inputValue, inputEvents }">
                    <VField>
                        <VLabel class="required-field">Tanggal</VLabel>
                        <VControl icon="feather:calendar">
                            <VInput type="text" placeholder="Select a date" :value="inputValue" v-on="inputEvents"
                                class="is-rounded" />
                        </VControl>
                    </VField>
                </template>
            </VDatePicker>
        </div>
        <div class="columns is-multiline">

            <div class="column is-3">
                <Fieldset legend="Dokter Umum" :toggleable="true">
                    <div class="column is-12">
                        <VField>
                            <VLabel>Jumlah Tenaga</VLabel>
                            <VControl icon="feather:bookmark">
                                <input v-model="item.jumlah_dokter_umum" type="number" class="input is-rounded"
                                    placeholder=" " />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-12">
                        <VField>
                            <VLabel>Yang Sudah Diperiksa Swab PCR</VLabel>
                            <VControl icon="feather:bookmark">
                                <input v-model="item.sudah_dokter_umum" type="number" class="input is-rounded"
                                    placeholder=" " />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-12">
                        <VField>
                            <VLabel>Hasil PCR Terkonfirmas</VLabel>
                            <VControl icon="feather:bookmark">
                                <input v-model="item.hasil_dokter_umum" type="number" class="input is-rounded"
                                    placeholder=" " />
                            </VControl>
                        </VField>
                    </div>
                </Fieldset>
            </div>
            <div class="column is-3">
                <Fieldset legend="Dokter Spesialis" :toggleable="true">
                    <div class="column is-12">
                        <VField>
                            <VLabel>Jumlah Tenaga</VLabel>
                            <VControl icon="feather:bookmark">
                                <input v-model="item.jumlah_dokter_spesialis" type="number" class="input is-rounded"
                                    placeholder=" " />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-12">
                        <VField>
                            <VLabel>Yang Sudah Diperiksa Swab PCR</VLabel>
                            <VControl icon="feather:bookmark">
                                <input v-model="item.sudah_dokter_spesialis" type="number" class="input is-rounded"
                                    placeholder=" " />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-12">
                        <VField>
                            <VLabel>Hasil PCR Terkonfirmas</VLabel>
                            <VControl icon="feather:bookmark">
                                <input v-model="item.hasil_dokter_spesialis" type="number" class="input is-rounded"
                                    placeholder=" " />
                            </VControl>
                        </VField>
                    </div>
                </Fieldset>
            </div>
            <div class="column is-3">
                <Fieldset legend="Dokter Gigi" :toggleable="true">
                    <div class="column is-12">
                        <VField>
                            <VLabel>Jumlah Tenaga</VLabel>
                            <VControl icon="feather:bookmark">
                                <input v-model="item.jumlah_dokter_gigi" type="number" class="input is-rounded"
                                    placeholder=" " />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-12">
                        <VField>
                            <VLabel>Yang Sudah Diperiksa Swab PCR</VLabel>
                            <VControl icon="feather:bookmark">
                                <input v-model="item.sudah_dokter_gigi" type="number" class="input is-rounded"
                                    placeholder=" " />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-12">
                        <VField>
                            <VLabel>Hasil PCR Terkonfirmas</VLabel>
                            <VControl icon="feather:bookmark">
                                <input v-model="item.hasil_dokter_gigi" type="number" class="input is-rounded"
                                    placeholder=" " />
                            </VControl>
                        </VField>
                    </div>
                </Fieldset>
            </div>
            <div class="column is-3">
                <Fieldset legend="Residen" :toggleable="true">
                    <div class="column is-12">
                        <VField>
                            <VLabel>Jumlah Tenaga</VLabel>
                            <VControl icon="feather:bookmark">
                                <input v-model="item.jumlah_residen" type="number" class="input is-rounded"
                                    placeholder=" " />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-12">
                        <VField>
                            <VLabel>Yang Sudah Diperiksa Swab PCR</VLabel>
                            <VControl icon="feather:bookmark">
                                <input v-model="item.sudah_residen" type="number" class="input is-rounded"
                                    placeholder=" " />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-12">
                        <VField>
                            <VLabel>Hasil PCR Terkonfirmas</VLabel>
                            <VControl icon="feather:bookmark">
                                <input v-model="item.hasil_residen" type="number" class="input is-rounded"
                                    placeholder=" " />
                            </VControl>
                        </VField>
                    </div>
                </Fieldset>
            </div>

            <div class="column is-3">
                <Fieldset legend="Perawat" :toggleable="true">
                    <div class="column is-12">
                        <VField>
                            <VLabel>Jumlah Tenaga</VLabel>
                            <VControl icon="feather:bookmark">
                                <input v-model="item.jumlah_perawat" type="number" class="input is-rounded"
                                    placeholder=" " />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-12">
                        <VField>
                            <VLabel>Yang Sudah Diperiksa Swab PCR</VLabel>
                            <VControl icon="feather:bookmark">
                                <input v-model="item.sudah_perawat" type="number" class="input is-rounded"
                                    placeholder=" " />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-12">
                        <VField>
                            <VLabel>Hasil PCR Terkonfirmas</VLabel>
                            <VControl icon="feather:bookmark">
                                <input v-model="item.hasil_perawat" type="number" class="input is-rounded"
                                    placeholder=" " />
                            </VControl>
                        </VField>
                    </div>
                </Fieldset>
            </div>
            <div class="column is-3">
                <Fieldset legend="Bidan" :toggleable="true">
                    <div class="column is-12">
                        <VField>
                            <VLabel>Jumlah Tenaga</VLabel>
                            <VControl icon="feather:bookmark">
                                <input v-model="item.jumlah_bidan" type="number" class="input is-rounded" placeholder=" " />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-12">
                        <VField>
                            <VLabel>Yang Sudah Diperiksa Swab PCR</VLabel>
                            <VControl icon="feather:bookmark">
                                <input v-model="item.sudah_bidan" type="number" class="input is-rounded" placeholder=" " />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-12">
                        <VField>
                            <VLabel>Hasil PCR Terkonfirmas</VLabel>
                            <VControl icon="feather:bookmark">
                                <input v-model="item.hasil_bidan" type="number" class="input is-rounded" placeholder=" " />
                            </VControl>
                        </VField>
                    </div>
                </Fieldset>
            </div>

            <div class="column is-3">
                <Fieldset legend="Apoteker" :toggleable="true">
                    <div class="column is-12">
                        <VField>
                            <VLabel>Jumlah Tenaga</VLabel>
                            <VControl icon="feather:bookmark">
                                <input v-model="item.jumlah_apoteker" type="number" class="input is-rounded"
                                    placeholder=" " />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-12">
                        <VField>
                            <VLabel>Yang Sudah Diperiksa Swab PCR</VLabel>
                            <VControl icon="feather:bookmark">
                                <input v-model="item.sudah_apoteker" type="number" class="input is-rounded"
                                    placeholder=" " />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-12">
                        <VField>
                            <VLabel>Hasil PCR Terkonfirmas</VLabel>
                            <VControl icon="feather:bookmark">
                                <input v-model="item.hasil_apoteker" type="number" class="input is-rounded"
                                    placeholder=" " />
                            </VControl>
                        </VField>
                    </div>
                </Fieldset>
            </div>

            <div class="column is-3">
                <Fieldset legend="Radiografer" :toggleable="true">
                    <div class="column is-12">
                        <VField>
                            <VLabel>Jumlah Tenaga</VLabel>
                            <VControl icon="feather:bookmark">
                                <input v-model="item.jumlah_radiografer" type="number" class="input is-rounded"
                                    placeholder=" " />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-12">
                        <VField>
                            <VLabel>Yang Sudah Diperiksa Swab PCR</VLabel>
                            <VControl icon="feather:bookmark">
                                <input v-model="item.sudah_radiografer" type="number" class="input is-rounded"
                                    placeholder=" " />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-12">
                        <VField>
                            <VLabel>Hasil PCR Terkonfirmas</VLabel>
                            <VControl icon="feather:bookmark">
                                <input v-model="item.hasil_radiografer" type="number" class="input is-rounded"
                                    placeholder=" " />
                            </VControl>
                        </VField>
                    </div>
                </Fieldset>
            </div>

            <div class="column is-3">
                <Fieldset legend="Analisis Lab" :toggleable="true">
                    <div class="column is-12">
                        <VField>
                            <VLabel>Jumlah Tenaga</VLabel>
                            <VControl icon="feather:bookmark">
                                <input v-model="item.jumlah_analis_lab" type="number" class="input is-rounded"
                                    placeholder=" " />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-12">
                        <VField>
                            <VLabel>Yang Sudah Diperiksa Swab PCR</VLabel>
                            <VControl icon="feather:bookmark">
                                <input v-model="item.sudah_analis_lab" type="number" class="input is-rounded"
                                    placeholder=" " />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-12">
                        <VField>
                            <VLabel>Hasil PCR Terkonfirmas</VLabel>
                            <VControl icon="feather:bookmark">
                                <input v-model="item.hasil_analis_lab" type="number" class="input is-rounded"
                                    placeholder=" " />
                            </VControl>
                        </VField>
                    </div>
                </Fieldset>
            </div>

            <div class="column is-3">
                <Fieldset legend="COAS" :toggleable="true">
                    <div class="column is-12">
                        <VField>
                            <VLabel>Jumlah Tenaga</VLabel>
                            <VControl icon="feather:bookmark">
                                <input v-model="item.jumlah_co_ass" type="number" class="input is-rounded"
                                    placeholder=" " />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-12">
                        <VField>
                            <VLabel>Yang Sudah Diperiksa Swab PCR</VLabel>
                            <VControl icon="feather:bookmark">
                                <input v-model="item.sudah_co_ass" type="number" class="input is-rounded" placeholder=" " />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-12">
                        <VField>
                            <VLabel>Hasil PCR Terkonfirmas</VLabel>
                            <VControl icon="feather:bookmark">
                                <input v-model="item.hasil_co_ass" type="number" class="input is-rounded" placeholder=" " />
                            </VControl>
                        </VField>
                    </div>
                </Fieldset>
            </div>

            <div class="column is-3">
                <Fieldset legend="Internship" :toggleable="true">
                    <div class="column is-12">
                        <VField>
                            <VLabel>Jumlah Tenaga</VLabel>
                            <VControl icon="feather:bookmark">
                                <input v-model="item.jumlah_internship" type="number" class="input is-rounded"
                                    placeholder=" " />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-12">
                        <VField>
                            <VLabel>Yang Sudah Diperiksa Swab PCR</VLabel>
                            <VControl icon="feather:bookmark">
                                <input v-model="item.sudah_internship" type="number" class="input is-rounded"
                                    placeholder=" " />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-12">
                        <VField>
                            <VLabel>Hasil PCR Terkonfirmas</VLabel>
                            <VControl icon="feather:bookmark">
                                <input v-model="item.hasil_internship" type="number" class="input is-rounded"
                                    placeholder=" " />
                            </VControl>
                        </VField>
                    </div>
                </Fieldset>
            </div>

            <div class="column is-3">
                <Fieldset legend="Nakes Lainnya" :toggleable="true">
                    <div class="column is-12">
                        <VField>
                            <VLabel>Jumlah Tenaga</VLabel>
                            <VControl icon="feather:bookmark">
                                <input v-model="item.jumlah_nakes_lainnya" type="number" class="input is-rounded"
                                    placeholder=" " />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-12">
                        <VField>
                            <VLabel>Yang Sudah Diperiksa Swab PCR</VLabel>
                            <VControl icon="feather:bookmark">
                                <input v-model="item.sudah_nakes_lainnya" type="number" class="input is-rounded"
                                    placeholder=" " />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-12">
                        <VField>
                            <VLabel>Hasil PCR Terkonfirmas</VLabel>
                            <VControl icon="feather:bookmark">
                                <input v-model="item.hasil_nakes_lainnya" type="number" class="input is-rounded"
                                    placeholder=" " />
                            </VControl>
                        </VField>
                    </div>
                </Fieldset>
            </div>




        </div>
        <template #footer>
            <VButton color="danger" icon="pi pi-times" outlined raised @click="modalInput = false"> Batal </VButton>
            <VButton color="primary" icon="pi pi-check" raised @click="simpanKebutuhan()" :loading="isRouteLoading"> Simpan
            </VButton>
        </template>
    </Dialog>
</template>


<script setup lang="ts">
import { useRoute, useRouter } from 'vue-router'
import { ref, computed, watch, reactive } from 'vue'
import * as H from '/@src/utils/appHelper'
import { useApi } from '/@src/composable/useApi'
import { useHead } from '@vueuse/head'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useToaster } from '/@src/composable/toaster'
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import AutoComplete from 'primevue/autocomplete';
import MultiSelect from 'primevue/multiselect';
import Row from 'primevue/row';
import InputText from 'primevue/inputtext';
import Dialog from 'primevue/dialog';
import ColumnGroup from 'primevue/columngroup';
import { FilterMatchMode } from 'primevue/api'
import Calendar from 'primevue/calendar';
import Fieldset from 'primevue/fieldset';
import moment from 'moment'

useHead({
    title: import.meta.env.VITE_PROJECT,
})
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)
const isLoading: any = ref(false)

const item: any = reactive({
    filterDate: {
        start: new Date(),
        end: new Date()
    },
    tanggal: new Date(),
    limit: 10,
    start: 1,
})
const dataSource = ref([])
const dataRS = ref([])

const isLoadingTT: any = ref(false)
const isLoadingSave: any = ref(false)
const btnLoadSimpan = ref(false)
const isRouteLoading = ref(false)
const listKebutuhan = ref([])
const modalInput = ref(false)

const filters = ref({
    global: { value: null, matchMode: FilterMatchMode.CONTAINS },

});

const fetchData = async () => {
    let json = {
        "url": "Pasien/pcr_nakes",
        "method": "GET",
        "jenis": "sirsonlinev3",
        "data": null
    }
    const res = await useApi().postNoMessage(
        `/bridging/kemenkes/tools`, json)
    if (res.metaData.code == 200) {
        res.response.forEach((element: any) => {
            element.no = element + 1
        });
        dataSource.value = res.response
    } else {
        H.alert('error', res.metaData.message)
    }
}

const tambahData = () => {
    modalInput.value = true
}

const simpanKebutuhan = () => {

    let jumlah_dokter_umum = item.jumlah_dokter_umum == undefined ? 0 : item.jumlah_dokter_umum
    let sudah_dokter_umum = item.sudah_dokter_umum == undefined ? 0 : item.sudah_dokter_umum
    let hasil_dokter_umum = item.hasil_dokter_umum == undefined ? 0 : item.hasil_dokter_umum
    let jumlah_dokter_spesialis = item.jumlah_dokter_spesialis == undefined ? 0 : item.jumlah_dokter_spesialis
    let sudah_dokter_spesialis = item.sudah_dokter_spesialis == undefined ? 0 : item.sudah_dokter_spesialis
    let hasil_dokter_spesialis = item.hasil_dokter_spesialis == undefined ? 0 : item.hasil_dokter_spesialis
    let jumlah_dokter_gigi = item.jumlah_dokter_gigi == undefined ? 0 : item.jumlah_dokter_gigi
    let sudah_dokter_gigi = item.sudah_dokter_gigi == undefined ? 0 : item.sudah_dokter_gigi
    let hasil_dokter_gigi = item.hasil_dokter_gigi == undefined ? 0 : item.hasil_dokter_gigi
    let jumlah_residen = item.jumlah_residen == undefined ? 0 : item.jumlah_residen
    let sudah_residen = item.sudah_residen == undefined ? 0 : item.sudah_residen
    let hasil_residen = item.hasil_residen == undefined ? 0 : item.hasil_residen
    let jumlah_perawat = item.jumlah_perawat == undefined ? 0 : item.jumlah_perawat
    let sudah_perawat = item.sudah_perawat == undefined ? 0 : item.sudah_perawat
    let hasil_perawat = item.hasil_perawat == undefined ? 0 : item.hasil_perawat
    let jumlah_bidan = item.jumlah_bidan == undefined ? 0 : item.jumlah_bidan
    let sudah_bidan = item.sudah_bidan == undefined ? 0 : item.sudah_bidan
    let hasil_bidan = item.hasil_bidan == undefined ? 0 : item.hasil_bidan
    let jumlah_apoteker = item.jumlah_apoteker == undefined ? 0 : item.jumlah_apoteker
    let sudah_apoteker = item.sudah_apoteker == undefined ? 0 : item.sudah_apoteker
    let hasil_apoteker = item.hasil_apoteker == undefined ? 0 : item.hasil_apoteker
    let jumlah_radiografer = item.jumlah_radiografer == undefined ? 0 : item.jumlah_radiografer
    let sudah_radiografer = item.sudah_radiografer == undefined ? 0 : item.sudah_radiografer
    let hasil_radiografer = item.hasil_radiografer == undefined ? 0 : item.hasil_radiografer
    let jumlah_analis_lab = item.jumlah_analis_lab == undefined ? 0 : item.jumlah_analis_lab
    let sudah_analis_lab = item.sudah_analis_lab == undefined ? 0 : item.sudah_analis_lab
    let hasil_analis_lab = item.hasil_analis_lab == undefined ? 0 : item.hasil_analis_lab
    let jumlah_co_ass = item.jumlah_co_ass == undefined ? 0 : item.jumlah_co_ass
    let sudah_co_ass = item.sudah_co_ass == undefined ? 0 : item.sudah_co_ass
    let hasil_co_ass = item.hasil_co_ass == undefined ? 0 : item.hasil_co_ass
    let jumlah_internship = item.jumlah_internship == undefined ? 0 : item.jumlah_internship
    let sudah_internship = item.sudah_internship == undefined ? 0 : item.sudah_internship
    let hasil_internship = item.hasil_internship == undefined ? 0 : item.hasil_internship
    let jumlah_nakes_lainnya = item.jumlah_nakes_lainnya == undefined ? 0 : item.jumlah_nakes_lainnya
    let sudah_nakes_lainnya = item.sudah_nakes_lainnya == undefined ? 0 : item.sudah_nakes_lainnya
    let hasil_nakes_lainnya = item.hasil_nakes_lainnya == undefined ? 0 : item.hasil_nakes_lainnya
    let rekap_jumlah_tenaga = parseInt(jumlah_dokter_umum) + parseInt(jumlah_dokter_spesialis) + parseInt(jumlah_dokter_gigi) + parseInt(jumlah_residen) + parseInt(jumlah_perawat) + parseInt(jumlah_bidan) + parseInt(jumlah_apoteker) + parseInt(jumlah_radiografer) + parseInt(jumlah_analis_lab) + parseInt(jumlah_co_ass) + parseInt(jumlah_internship) + parseInt(jumlah_nakes_lainnya)
    let rekap_jumlah_sudah_diperiksa = parseInt(sudah_dokter_umum) + parseInt(sudah_dokter_spesialis) + parseInt(sudah_dokter_gigi) + parseInt(sudah_residen) + parseInt(sudah_perawat) + parseInt(sudah_bidan) + parseInt(sudah_apoteker) + parseInt(sudah_radiografer) + parseInt(sudah_analis_lab) + parseInt(sudah_co_ass) + parseInt(sudah_internship) + parseInt(sudah_nakes_lainnya)
    let rekap_jumlah_hasil_pcr = parseInt(hasil_dokter_umum) + parseInt(hasil_dokter_spesialis) + parseInt(hasil_dokter_gigi) + parseInt(hasil_residen) + parseInt(hasil_perawat) + parseInt(hasil_bidan) + parseInt(hasil_apoteker) + parseInt(hasil_radiografer) + parseInt(hasil_analis_lab) + parseInt(hasil_co_ass) + parseInt(hasil_internship) + parseInt(hasil_nakes_lainnya)
    let tgl = moment(item.tanggal).format("YYYY-MM-DD");

    let json = {
        "url": "Pasien/pcr_nakes",
        "method": "POST",
        "jenis": "sirsonlinev3",
        "head": "x-tanggal: " + tgl,
        "data": {
            "tanggal": tgl,
            "jumlah_tenaga_dokter_umum": jumlah_dokter_umum,
            "sudah_periksa_dokter_umum": sudah_dokter_umum,
            "hasil_pcr_dokter_umum": hasil_dokter_umum,
            "jumlah_tenaga_dokter_spesialis": jumlah_dokter_spesialis,
            "sudah_periksa_dokter_spesialis": sudah_dokter_spesialis,
            "hasil_pcr_dokter_spesialis": hasil_dokter_spesialis,
            "jumlah_tenaga_dokter_gigi": jumlah_dokter_gigi,
            "sudah_periksa_dokter_gigi": sudah_dokter_gigi,
            "hasil_pcr_dokter_gigi": hasil_dokter_gigi,
            "jumlah_tenaga_residen": jumlah_residen,
            "sudah_periksa_residen": sudah_residen,
            "hasil_pcr_residen": hasil_residen,
            "jumlah_tenaga_perawat": jumlah_perawat,
            "sudah_periksa_perawat": sudah_perawat,
            "hasil_pcr_perawat": hasil_perawat,
            "jumlah_tenaga_bidan": jumlah_bidan,
            "sudah_periksa_bidan": sudah_bidan,
            "hasil_pcr_bidan": hasil_bidan,
            "jumlah_tenaga_apoteker": jumlah_apoteker,
            "sudah_periksa_apoteker": sudah_apoteker,
            "hasil_pcr_apoteker": hasil_apoteker,
            "jumlah_tenaga_radiografer": jumlah_radiografer,
            "sudah_periksa_radiografer": sudah_radiografer,
            "hasil_pcr_radiografer": hasil_radiografer,
            "jumlah_tenaga_analis_lab": jumlah_analis_lab,
            "sudah_periksa_analis_lab": sudah_analis_lab,
            "hasil_pcr_analis_lab": hasil_analis_lab,
            "jumlah_tenaga_co_ass": jumlah_co_ass,
            "sudah_periksa_co_ass": sudah_co_ass,
            "hasil_pcr_co_ass": hasil_co_ass,
            "jumlah_tenaga_internship": jumlah_internship,
            "sudah_periksa_internship": sudah_internship,
            "hasil_pcr_internship": hasil_internship,
            "jumlah_tenaga_nakes_lainnya": jumlah_nakes_lainnya,
            "sudah_periksa_nakes_lainnya": sudah_nakes_lainnya,
            "hasil_pcr_nakes_lainnya": hasil_nakes_lainnya,
            "rekap_jumlah_tenaga": rekap_jumlah_tenaga,
            "rekap_jumlah_sudah_diperiksa": rekap_jumlah_sudah_diperiksa,
            "rekap_jumlah_hasil_pcr": rekap_jumlah_hasil_pcr,
        }
    }
    isRouteLoading.value = true
    const data = useApi().postNoMessage('/bridging/kemenkes/tools', json)
        .then((data: any) => {
            if (data.metaData.code == 200) {
                H.alert('success', data.metaData.message);
            } else {
                H.alert('error', data.metaData.message)
            }
            isRouteLoading.value = false;
            modalInput.value = false
            fetchData()
            clear()
        })
}

const SearchData = async () => {
    if (item.periode.start == undefined) {
        fetchData()
    } else {
        var tgl = moment(item.periode.start).format("YYYY-MM-DD");
        var json = {
            "url": "Pasien/pcr_nakes",
            "method": "GET",
            "jenis": "sirsonlinev3",
            "head": "x-tanggal: " + tgl,
            "data": null
        }
        isRouteLoading.value = true
        const res = await useApi().postNoMessage(
            `/bridging/kemenkes/tools`, json)
        if (res.metaData.code == 200) {
            res.response.forEach((element: any) => {
                element.no = element + 1
            });
            dataSource.value = res.response
        } else {
            H.alert('error', res.metaData.message)
        }
    }
}


const clear = () => {

    delete item.jumlah_dokter_umum
    delete item.sudah_dokter_umum
    delete item.hasil_dokter_umum
    delete item.jumlah_dokter_spesialis
    delete item.sudah_dokter_spesialis
    delete item.hasil_dokter_spesialis
    delete item.jumlah_dokter_gigi
    delete item.sudah_dokter_gigi
    delete item.hasil_dokter_gigi
    delete item.jumlah_residen
    delete item.sudah_residen
    delete item.hasil_residen
    delete item.jumlah_perawat
    delete item.sudah_perawat
    delete item.hasil_perawat
    delete item.jumlah_bidan
    delete item.sudah_bidan
    delete item.hasil_bidan
    delete item.jumlah_apoteker
    delete item.sudah_apoteker
    delete item.hasil_apoteker
    delete item.jumlah_radiografer
    delete item.sudah_radiografer
    delete item.hasil_radiografer
    delete item.jumlah_analis_lab
    delete item.sudah_analis_lab
    delete item.hasil_analis_lab
    delete item.jumlah_co_ass
    delete item.sudah_co_ass
    delete item.hasil_co_ass
    delete item.jumlah_internship
    delete item.sudah_internship
    delete item.hasil_internship
    delete item.jumlah_nakes_lainnya
    delete item.sudah_nakes_lainnya
    delete item.hasil_nakes_lainnya

}


fetchData()


</script>
<style lang="scss">
.control.has-icon {
    position: relative;
    width: 100%;
}

.field:not(:last-child) {
    margin-bottom: 0px !important;
}
</style>
