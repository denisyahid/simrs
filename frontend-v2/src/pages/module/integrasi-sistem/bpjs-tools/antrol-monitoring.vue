<template>
    <div class="columns is-multiline">
        <div class="column is-12">
            <div class="py-2">
                <h1 style="font-weight:bold">Monitorin Antrian Online</h1>
            </div>
        </div>
        <div class="column is-12">
            <VCard>
                <div class="columns is-multiline">
                    <div class="column is-4">
                        <VDatePicker v-model="item.filterDate" is-range color="pink" trim-weeks
                            :max-date="new Date()">
                            <template #default="{ inputValue, inputEvents }">
                                <VField addons>
                                    <VControl icon="feather:calendar">
                                        <VInput :value="inputValue.start" v-on="inputEvents.start" />
                                    </VControl>
                                    <VControl>
                                        <VButton static><i class="fas fa-arrow-right" aria-hidden="true"></i></VButton>
                                    </VControl>
                                    <VControl icon="feather:calendar">
                                        <VInput :value="inputValue.end" v-on="inputEvents.end" />
                                    </VControl>
                                </VField>
                            </template>
                        </VDatePicker>
                    </div>
                    <div class="column is-3">
                        <!-- <VField class="is-autocomplete-select" v-slot="{ id }">
                            <VControl icon="feather:search">
                                <AutoComplete v-model="item.ruangan" :suggestions="d_Ruangan" @complete="fetchRuangan($event)"
                                :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                                :loadingIcon="'pi pi-spinner'" :field="'label'"  placeholder="Pilih Ruangan" />
                            </VControl>
                        </VField> -->

                        <VField v-slot="{ id }" class="is-autocomplete-select">
                            <VControl icon="feather:search">
                            <Multiselect
                                v-model="item.ruangan"
                                :attrs="{ id }"
                                :options="d_Ruangan"
                                placeholder="Pilih Ruangan"
                                :searchable="true"
                            />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-3">
                        <VField v-slot="{ id }" class="is-autocomplete-select">
                            <VControl icon="feather:search">
                            <Multiselect
                                v-model="item.statusantrean"
                                :attrs="{ id }"
                                :options="optionsStatusAntrean"
                                placeholder="Pilih Status"
                                :searchable="true"
                            />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-2 mt-2">
                        <VIconButton circle icon="feather:search" raised bold @click="fecthData"
                            :loading="isLoading" v-tooltip.bubble="'Cari'" class="mt-2-min mr-2">
                        </VIconButton>
                    </div>
                </div>
            </VCard>
        </div>
        <!-- <div class="column is-12" v-if="isLoading">
            <VPlaceloadWrap>
                <VPlaceload height="500px" width="100%" class="mx-2" />
            </VPlaceloadWrap>
        </div> -->
        <div class="column is-12">
            <DataTable v-model:filters="filters" :value="dataSource" paginator :rows="10" :rowsPerPageOptions="[5, 10, 20, 50, 100]" dataKey="no"
                filterDisplay="row" :globalFilterFields="['peserta']['nama']"
                class="p-datatable-sm"
                paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
                responsiveLayout="stack" breakpoint="960px" sortMode="multiple"
                currentPageReportTemplate="Showing {first} to {last} of {totalRecords}">
                <template #header>
                    <div class="flex justify-content-end">
                        <VButton icon="feather:edit" color="danger" outlined class="mr-2" @click="validasiData" :loading="isLoadingValidasi"> Validasi Data </VButton>
                        <!-- <VButton icon="feather:refresh-cw" color="success" outlined class="mr-2"> Kirim Ulang </VButton> -->
                        <span class="p-input-icon-left">
                            <i class="pi pi-search" />
                            <InputText v-model="filters['global'].value" placeholder="Search" />
                        </span>
                    </div>
                </template>
                <template #empty style="text-align: center;"> No data found. </template>
                <ColumnGroup type="header">
                    <Row>
                        <Column :rowspan="2" header="#"></Column>
                        <Column :rowspan="2" header="Tgl Registrasi"></Column>
                        <Column :rowspan="2" header="Kode Booking"></Column>
                        <Column :rowspan="2" header="No RM"></Column>
                        <Column :rowspan="2" header="Nama Pasien"></Column>
                        <Column :rowspan="2" header="Ruangan"></Column>
                        <Column :colspan="2" header="Waktu Tunggu Admisi"></Column>
                        <Column :colspan="2" header="Waktu Layanan Admisi"></Column>
                        <Column :colspan="2" header="Waktu Tunggu Poli"></Column>
                        <Column :colspan="2" header="Waktu Layan Poli"></Column>
                        <Column :colspan="2" header="Waktu Tunggu Farmasi/Selesai Layan Poli"></Column>
                        <Column :colspan="2" header="Waktu Layan Farmasi"></Column>
                        <Column :colspan="2" header="Waktu Obat Selesai"></Column>
                    </Row>
                    <Row>
                        <Column field="taksid_1" header="Waktu"></Column>
                        <Column field="status_1" header="Status"></Column>
                        <Column field="taksid_2" header="Waktu"></Column>
                        <Column field="status_2" header="Status"></Column>
                        <Column field="taksid_3" header="Waktu"></Column>
                        <Column field="status_3" header="Status"></Column>
                        <Column field="taksid_4" header="Waktu"></Column>
                        <Column field="status_4" header="Status"></Column>
                        <Column field="taksid_5" header="Waktu"></Column>
                        <Column field="status_5" header="Status"></Column>
                        <Column field="taksid_6" header="Waktu"></Column>
                        <Column field="status_6" header="Status"></Column>
                        <Column field="taksid_7" header="Waktu"></Column>
                        <Column field="status_7" header="Status"></Column>
                    </Row>
                </ColumnGroup>
                <Column header="#" frozen style="min-width: 150px">
                    <template #body="{ data }">
                        <VIconButton light circle icon="feather:info" class="mr-2" v-tooltip="`Logging`" @click="openLog(data)" />
                        <VIconButton color="info" light circle icon="feather:database" class="mr-2" v-tooltip="`Validasi data`" @click="openDataAntrean(data)" :loading="isLoading" />
                        <VIconButton color="primary" light circle icon="feather:send" class="mr-2" v-tooltip="`Kirim data`" @click="saveTaksId(data)" :loading="isLoading" />
                    </template>
                </Column>
                <Column field="tglregistrasi" header="Tgl Registrasi" frozen :sortable="true" style="min-width: 150px"></Column>
                <Column field="noregistrasi" header="Kode Booking" frozen :sortable="true" style="min-width: 150px"></Column>
                <Column field="norm" header="No RM" frozen :sortable="true" style="min-width: 100px"></Column>
                <Column field="namapasien" header="Nama Pasien" frozen :sortable="true" style="min-width: 300px"></Column>
                <Column field="namaruangan" header="Ruangan" frozen :sortable="true" style="min-width: 300px"></Column>
                <Column field="taksid_1" header="Waktu" style="min-width: 150px">
                    <template #body="slotProps"> {{ slotProps.data.taksid_1 }} </template>
                </Column>
                <Column field="status_1" header="Status" style="min-width: 100px">
                    <template #body="slotProps"> 
                        <VIconButton color="primary" light circle icon="feather:check-circle" class="mr-2" v-if="slotProps.data.status_1" />
                        <VIconButton color="danger" light circle icon="feather:x-circle" class="mr-2" v-else />
                    </template>
                </Column>
                <Column field="taksid_2" header="Waktu" style="min-width: 150px">
                    <template #body="slotProps"> {{ slotProps.data.taksid_2 }} </template>
                </Column>
                <Column field="status_2" header="Status" style="min-width: 100px">
                    <template #body="slotProps">
                        <VIconButton color="primary" light circle icon="feather:check-circle" class="mr-2" v-if="slotProps.data.status_2" />
                        <VIconButton color="danger" light circle icon="feather:x-circle" class="mr-2" v-else />
                    </template>
                </Column>
                <Column field="taksid_3" header="Waktu" style="min-width: 150px">
                    <template #body="slotProps"> {{ slotProps.data.taksid_3 }} </template>
                </Column>
                <Column field="status_3" header="Status" style="min-width: 100px">
                    <template #body="slotProps">
                        <VIconButton color="primary" light circle icon="feather:check-circle" class="mr-2" v-if="slotProps.data.status_3" />
                        <VIconButton color="danger" light circle icon="feather:x-circle" class="mr-2" v-else />
                    </template>
                </Column>
                <Column field="taksid_4" header="Waktu" style="min-width: 150px">
                    <template #body="slotProps"> {{ slotProps.data.taksid_4 }} </template>
                </Column>
                <Column field="status_4" header="Status" style="min-width: 100px">
                    <template #body="slotProps">
                        <VIconButton color="primary" light circle icon="feather:check-circle" class="mr-2" v-if="slotProps.data.status_4" />
                        <VIconButton color="danger" light circle icon="feather:x-circle" class="mr-2" v-else />
                    </template>
                </Column>
                <Column field="taksid_5" header="Waktu" style="min-width: 150px">
                    <template #body="slotProps"> {{ slotProps.data.taksid_5 }} </template>
                </Column>
                <Column field="status_5" header="Status" style="min-width: 100px">
                    <template #body="slotProps">
                        <VIconButton color="primary" light circle icon="feather:check-circle" class="mr-2" v-if="slotProps.data.status_5" />
                        <VIconButton color="danger" light circle icon="feather:x-circle" class="mr-2" v-else />
                    </template>
                </Column>
                <Column field="taksid_6" header="Waktu" style="min-width: 150px">
                    <template #body="slotProps"> {{ slotProps.data.taksid_6 }} </template>
                </Column>
                <Column field="status_6" header="Status" style="min-width: 100px">
                    <template #body="slotProps">
                        <VIconButton color="primary" light circle icon="feather:check-circle" class="mr-2" v-if="slotProps.data.status_6" />
                        <VIconButton color="danger" light circle icon="feather:x-circle" class="mr-2" v-else />
                    </template>
                </Column>
                <Column field="taksid_7" header="Waktu" style="min-width: 150px">
                    <template #body="slotProps"> {{ slotProps.data.taksid_7 }} </template>
                </Column>
                <Column field="status_7" header="Status" style="min-width: 100px">
                    <template #body="slotProps">
                        <VIconButton color="primary" light circle icon="feather:check-circle" class="mr-2" v-if="slotProps.data.status_7" />
                        <VIconButton color="danger" light circle icon="feather:x-circle" class="mr-2" v-else />
                    </template>
                </Column>
            </DataTable>
        </div>
        <div class="column is-12">
            <div class="columns is-multiline">
                <div class="column is-3">
                    <VCardCustom :style="'padding:5px 25px'">
                        <div class="label-status danger">
                            <i aria-hidden="true" class="fas fa-circle"></i>
                            <span class="ml-1">Jumlah Antean Belum Lengkap</span>
                        </div>
                        <small class="text-bold-custom h-100" style="font-size:1.5rem;"> {{ item.jlmAntreanB }}</small>
                    </VCardCustom>
                </div>
                <div class="column is-3">
                    <VCardCustom :style="'padding:5px 25px'">
                        <div class="label-status success">
                            <i aria-hidden="true" class="fas fa-circle"></i>
                            <span class="ml-1">Jumlah Antean Lengkap</span>
                        </div>
                        <small class="text-bold-custom h-100" style="font-size:1.5rem;"> {{ item.jlmAntreanL }}</small>

                    </VCardCustom>
                </div>
                <div class="column is-3">
                    <VCardCustom :style="'padding:5px 25px'">
                        <div class="label-status info">
                            <i aria-hidden="true" class="fas fa-circle"></i>
                            <span class="ml-1">Total Antean</span>
                        </div>
                        <small class="text-bold-custom h-100" style="font-size:1.5rem;"> {{ item.totalAntrean }}</small>

                    </VCardCustom>
                </div>
                <div class="column is-3">
                    <VCardCustom :style="'padding:5px 25px'">
                        <div class="label-status" :class="{'success': item.qualityAntrean >= 80, 'danger': item.qualityAntrean < 80 }">
                            <i aria-hidden="true" class="fas fa-circle"></i>
                            <span class="ml-1">Quality Rate</span>
                        </div>
                        <small class="text-bold-custom h-100 label-status" :class="{'success': item.qualityAntrean >= 80, 'danger': item.qualityAntrean < 80 }" style="font-size:1.4rem;"> {{ item.qualityAntrean }} %</small>
                    </VCardCustom>
                </div>
            </div>
        </div>
    </div>

    <!-- start modal log antrian online  -->
    <VModal is="form"
    :open="modalLoging"
    title="Logging pengiriman Antrol"
    size="medium"
    actions="right"
    @close="modalLoging = false">
        <template #content>
            <div class="modal-form">
                <div class="field">
                    <div class="control">
                        <textarea class="textarea" rows="4" v-model="item.logging"></textarea>
                    </div>
                </div>
            </div>
        </template>
    </VModal>
    <!-- end modal  -->

    <!-- start modal validasi antrian online  -->
    <VModal is="form"
    :open="modalDataAntrean"
    title="Data Antrean"
    size="medium"
    actions="right"
    @close="modalDataAntrean = false">
        <template #content>
            <div class="modal-form">
                <div class="columns is-multiline">
                    <div class="column is-6">
                        <div class="field">
                            <label>kode booking </label>
                            <div class="control">
                                <input type="text" class="input" placeholder="kodebooking" v-model="item.antr_kodebooking" />
                            </div>
                        </div>
                    </div>
                    
                    <div class="column is-6">
                        <div class="field">
                            <label>jenis pasien </label>
                            <div class="control">
                                <input type="text" class="input" placeholder="jenispasien" v-model="item.antr_jenispasien" />
                            </div>
                        </div>
                    </div>
                    
                    <div class="column is-6">
                        <div class="field">
                            <label>nomor kartu </label>
                            <div class="control">
                                <input type="text" class="input" placeholder="nomorkartu" v-model="item.antr_nomorkartu" />
                            </div>
                        </div>
                    </div>
                    
                    <div class="column is-6">
                        <div class="field">
                            <label>nik </label>
                            <div class="control">
                                <input type="text" class="input" placeholder="nik" v-model="item.antr_nik" />
                            </div>
                        </div>
                    </div>
                    
                    <div class="column is-6">
                        <div class="field">
                            <label>nohp </label>
                            <div class="control">
                                <input type="text" class="input" placeholder="nohp" v-model="item.antr_nohp" />
                            </div>
                        </div>
                    </div>
                    
                    <div class="column is-6">
                        <div class="field">
                            <label>Poli </label>
                            <div class="control">
                                <VField v-slot="{ id }" class="is-autocomplete-select">
                                    <VControl icon="feather:search">
                                    <Multiselect
                                        v-model="item.antr_poli"
                                        :attrs="{ id }"
                                        :options="d_Ruangan"
                                        placeholder="Pilih Ruangan"
                                        :searchable="true"
                                    />
                                    </VControl>
                                </VField>
                            </div>
                        </div>
                    </div>
                    
                    <!-- <div class="column is-6">
                        <div class="field">
                            <label>nama poli </label>
                            <div class="control">
                                <input type="text" class="input" placeholder="nama poli" v-model="item.antr_namapoli" />
                            </div>
                        </div>
                    </div> -->
                    
                    <div class="column is-6">
                        <div class="field">
                            <label>pasien baru </label>
                            <div class="control">
                                <input type="text" class="input" placeholder="Ya = 1, Tidak = 0" v-model="item.antr_pasienbaru" />
                            </div>
                        </div>
                    </div>
                    
                    <div class="column is-6">
                        <div class="field">
                            <label>norm </label>
                            <div class="control">
                                <input type="text" class="input" placeholder="norm" v-model="item.antr_norm" />
                            </div>
                        </div>
                    </div>
                    
                    <div class="column is-6">
                        <div class="field">
                            <label>tanggal periksa </label>
                            <div class="control">
                                <input type="text" class="input" placeholder="tanggal periksa" v-model="item.antr_tanggalperiksa" />
                            </div>
                        </div>
                    </div>
                    
                    <div class="column is-6">
                        <div class="field">
                            <label>dokter </label>
                            <div class="control">
                                <VField v-slot="{ id }" class="is-autocomplete-select">
                                    <VControl icon="feather:search">
                                    <Multiselect
                                        v-model="item.antr_dokter"
                                        :attrs="{ id }"
                                        :options="d_Dokter"
                                        placeholder="Pilih Dokter"
                                        :searchable="true"
                                    />
                                    </VControl>
                                </VField>
                            </div>
                        </div>
                    </div>
                    
                    <!-- <div class="column is-6">
                        <div class="field">
                            <label>nama dokter </label>
                            <div class="control">
                                <input type="text" class="input" placeholder="nama dokter" v-model="item.antr_namadokter" />
                            </div>
                        </div>
                    </div> -->
                    
                    <div class="column is-6">
                        <div class="field">
                            <label>jam praktek </label>
                            <div class="control">
                                <input type="text" class="input" placeholder="jam praktek" v-model="item.antr_jampraktek" />
                            </div>
                        </div>
                    </div>
                    
                    <div class="column is-6">
                        <div class="field">
                            <label>jenis kunjungan </label>
                            <div class="control">
                                <!-- <input type="text" class="input" placeholder="jenis kunjungan" v-model="item.antr_jeniskunjungan" /> -->
                                <VField v-slot="{ id }" class="is-autocomplete-select">
                                    <VControl icon="feather:search">
                                    <Multiselect
                                        v-model="item.antr_jeniskunjungan"
                                        :attrs="{ id }"
                                        :options="optionsJenisKunjungan"
                                        placeholder="Pilih Kunjungan"
                                        :searchable="true"
                                    />
                                    </VControl>
                                </VField>
                            </div>
                        </div>
                    </div>
                    
                    <div class="column is-6">
                        <div class="field">
                            <label>nomor referensi </label>
                            <div class="control">
                                <input type="text" class="input" placeholder="nomor referensi" v-model="item.antr_nomorreferensi" />
                            </div>
                        </div>
                    </div>
                    
                    <div class="column is-6">
                        <div class="field">
                            <label>nomor antrean </label>
                            <div class="control">
                                <input type="text" class="input" placeholder="nomor antrean" v-model="item.antr_nomorantrean" />
                            </div>
                        </div>
                    </div>
                    
                    <div class="column is-6">
                        <div class="field">
                            <label>angka antrean </label>
                            <div class="control">
                                <input type="text" class="input" placeholder="angka antrean" v-model="item.antr_angkaantrean" />
                            </div>
                        </div>
                    </div>
                    
                    <div class="column is-6">
                        <div class="field">
                            <label>estimasi dilayani </label>
                            <div class="control">
                                <input type="text" class="input" placeholder="estimasi dilayani" v-model="item.antr_estimasidilayani" />
                            </div>
                        </div>
                    </div>
                    
                    <div class="column is-6">
                        <div class="field">
                            <label>sisa kuota jkn </label>
                            <div class="control">
                                <input type="text" class="input" placeholder="sisa kuota jkn" v-model="item.antr_sisakuotajkn" />
                            </div>
                        </div>
                    </div>
                    
                    <div class="column is-6">
                        <div class="field">
                            <label>kuota jkn </label>
                            <div class="control">
                                <input type="text" class="input" placeholder="kuota jkn" v-model="item.antr_kuotajkn" />
                            </div>
                        </div>
                    </div>
                    
                    <div class="column is-6">
                        <div class="field">
                            <label>sisa kuota nonjkn </label>
                            <div class="control">
                                <input type="text" class="input" placeholder="sisa kuota nonjkn" v-model="item.antr_sisakuotanonjkn" />
                            </div>
                        </div>
                    </div>
                    
                    <div class="column is-6">
                        <div class="field">
                            <label>kuota nonjkn </label>
                            <div class="control">
                                <input type="text" class="input" placeholder="kuota nonjkn" v-model="item.antr_kuotanonjkn" />
                            </div>
                        </div>
                    </div>
                    
                    <div class="column is-12">
                        <div class="field">
                            <label>keterangan </label>
                            <div class="control">
                                <input type="text" class="input" placeholder="keterangan" v-model="item.antr_keterangan" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </template>
        <template #action>
            <VButton type="button" color="success" @click="saveDataAntrean" :loading="isLoading" raised>Save</VButton>
        </template>
    </VModal>
    <!-- end modal  -->

</template>
<script setup lang="ts">
import { useRoute, useRouter } from 'vue-router'
import { ref, computed, watch, reactive } from 'vue'
import * as H from '/@src/utils/appHelper'
import moment from 'moment'
import { useApi } from '/@src/composable/useApi'
import { useHead } from '@vueuse/head'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useToaster } from '/@src/composable/toaster'
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import ColumnGroup from 'primevue/columngroup';   // optional
import Row from 'primevue/row';
import InputText from 'primevue/inputtext';
import TabView from 'primevue/tabview';
import TabPanel from 'primevue/tabpanel';
import { FilterMatchMode } from 'primevue/api'
import Calendar from 'primevue/calendar';
import AutoComplete from 'primevue/autocomplete';
useHead({
    title: import.meta.env.VITE_PROJECT,
})
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)
const modalLoging: boolean = ref(false);
const modalDataAntrean: boolean = ref(false);
const isLoading: any = ref(false)
const isLoadingValidasi: any = ref(false)
const d_Dokter: any = ref({})
const d_Ruangan: any = ref({})
const item: any = reactive({
    filterDate: {
        start: new Date(),
        end: new Date()
    },
    jlmAntreanB: 0,
    jlmAntreanL: 0,
    totalAntrean: 0,
    qualityAntrean: 0,
})
const optionsStatusAntrean: any = [
    { value: 1, label:'Antrean Lengkap' },
    { value: 2, label:'Antrean Belum Lengkap' },
]
const optionsJenisKunjungan: any = [
    { value: 1, label:'Rujukan FKTP' },
    { value: 2, label:'Rujukan Internal' },
    { value: 3, label:'Kontrol' },
    { value: 4, label:'Rujukan Antar RS' },
]
const dataSource = ref([])
const filters = ref({
    global: { value: null, matchMode: FilterMatchMode.CONTAINS },

});

const loadCombo = async () => {
    await useApi().get('bridging/antrol/getComboMonitoring').then((response) => {
        let ruangan = []
        for (let i = 0; i < response.ruangan.length; i++) {
            const element = response.ruangan[i];
            ruangan.push({
                value: element.kdsubspesialisbpjs,
                label: element.namaruangan,
                kdspesialisbpjs: element.kdspesialisbpjs,
                kdsubspesialisbpjs: element.kdsubspesialisbpjs,
            })
            
        }
        d_Ruangan.value = ruangan
        let dokter = []
        for (let i = 0; i < response.dokter.length; i++) {
            const element = response.dokter[i];
            dokter.push({
                value: element.kddokterbpjs,
                label: element.namalengkap,
                kddokterbpjs: element.kddokterbpjs,
            })
            
        }
        d_Dokter.value = dokter
    })
}

const fecthData = async () => {
    let dari = H.formatDate(item.filterDate.start, 'YYYY-MM-DD') + " 00:00"
    let sampai = H.formatDate(item.filterDate.end, 'YYYY-MM-DD') + " 23:59"
    let ruangan = ""
    if(item.ruangan) {
        ruangan = `&ruangId=${item.ruangan}`
    }
    
    item.jlmAntreanB = 0
    item.jlmAntreanL = 0
    item.totalAntrean = 0
    item.qualityAntrean = 0
    isLoading.value = true
    const data = await useApi().get(`/bridging/antrol/getMonitoringWaktu?tglAwal=${dari}&tglAkhir=${sampai}${ruangan}`)
    isLoading.value = false
    let result = []
    for (let i = 0; i < data.length; i++) {
        const element = data[i];
        if((element.status_1 == true
        && element.status_2 == true
        && element.status_3 == true
        && element.status_4 == true
        && element.status_5 == true)
        || (element.status_3 == true && element.status_4 == true && element.status_5 == true)
        ) {
            if(item.statusantrean && item.statusantrean == 1)
                result.push(element)

            item.jlmAntreanL = item.jlmAntreanL + 1
        } else {
            if(item.statusantrean && item.statusantrean == 2)
                result.push(element)

            item.jlmAntreanB = item.jlmAntreanB + 1
        }
        item.totalAntrean = item.totalAntrean + 1

        if(!item.statusantrean)
            result.push(element)
    }
    dataSource.value = result
    item.qualityAntrean = item.jlmAntreanL / item.totalAntrean * 100
    item.qualityAntrean = item.qualityAntrean.toFixed(2)
}

const validasiData = async () => {
    isLoadingValidasi.value = true
    for (let i = 0; i < dataSource.value.length; i++) {
        const element = dataSource.value[i];
        let jsontask = {
            "url": "antrean/getlisttask",
            "jenis": "antrean",
            "method": "POST",
            "data": {
                "kodebooking": element.noregistrasi
            }
        }

        const resgl = await useApi().postNoMessage(`/bridging/bpjs/tools`, jsontask)
        if (resgl == '') continue;
        for (let j = 0; j < resgl.response.length; j++) {
            const element2 = resgl.response[j];
            const waktu = moment(element2.wakturs.replace(' WIB',''), "DD/MM/YYYY HH:mm")._d;
            let jsonsm = {
                "noregistrasifk": element.noregistrasifk,
                "taskid": element2.taskid,
                "waktu": waktu.getTime(),
                "statuskirim": true
            }
            const ressm = await useApi().postNoMessage(`/bridging/antrol/saveMonitoringTaksId`, jsonsm)
        }
    }
    isLoadingValidasi.value = false
    await fecthData()
    
}

const openLog = (e: any) => {
    modalLoging.value = true
    item.logging = e.log
}

const openDataAntrean = async (e: any) => {
    clearantr()
    const jsonantrean = {
        "url": `antrean/pendaftaran/kodebooking/${e.noregistrasi}`,
        "jenis": "antrean",
        "method": "GET",
        "data": null
    }

    isLoading.value = true
    const resantrean = await useApi().postNoMessage(`/bridging/bpjs/tools`, jsonantrean)
    isLoading.value = false
    if(resantrean != '' && resantrean.metaData.code == 200) {
        H.alert('warning', 'Data Antrean Sudah Terkirim ke BPJS !');
        return;
    }

    item.antr_noregistrasifk =  e.noregistrasifk
    if(e.dataantrean) {
        item.antr_nik =  e.dataantrean.nik
        item.antr_nohp =  e.dataantrean.nohp
        item.antr_norm =  e.dataantrean.norm
        item.antr_kuotajkn =  e.dataantrean.kuotajkn
        for (let h = 0; h < d_Ruangan.value.length; h++) {
            const element = d_Ruangan.value[h];
            if(element.kdsubspesialisbpjs == e.dataantrean.kodepoli) {
                item.antr_poli = element.value
                break;
            }
        }
        // item.antr_kodepoli =  e.dataantrean.kodepoli
        // item.antr_namapoli =  e.dataantrean.namapoli
        item.antr_jampraktek =  e.dataantrean.jampraktek
        item.antr_keterangan =  e.dataantrean.keterangan
        for (let i = 0; i < d_Dokter.value.length; i++) {
            const element = d_Dokter.value[i];
            if(element.kddokterbpjs == e.dataantrean.kodedokter) {
                item.antr_dokter = element.value
                break;
            }
        }
        // item.antr_kodedokter =  e.dataantrean.kodedokter
        // item.antr_namadokter =  e.dataantrean.namadokter
        item.antr_nomorkartu =  e.dataantrean.nomorkartu
        item.antr_pasienbaru =  e.dataantrean.pasienbaru
        item.antr_jenispasien =  e.dataantrean.jenispasien
        item.antr_kodebooking =  e.dataantrean.kodebooking
        item.antr_kuotanonjkn =  e.dataantrean.kuotanonjkn
        item.antr_angkaantrean =  e.dataantrean.angkaantrean
        item.antr_nomorantrean =  e.dataantrean.nomorantrean
        item.antr_sisakuotajkn =  e.dataantrean.sisakuotajkn
        item.antr_jeniskunjungan =  e.dataantrean.jeniskunjungan
        item.antr_nomorreferensi =  e.dataantrean.nomorreferensi
        item.antr_tanggalperiksa =  e.dataantrean.tanggalperiksa
        item.antr_sisakuotanonjkn =  e.dataantrean.sisakuotanonjkn
        item.antr_estimasidilayani =  H.formatDate(e.dataantrean.estimasidilayani, "YYYY-MM-DD HH:mm")
        modalDataAntrean.value = true
    } else {
        isLoading.value = true
        const resdataantrean = await useApi().get(`/bridging/antrol/getDataAntrean?norec_pd=${e.noregistrasifk}`)
        isLoading.value = false
        if(resdataantrean) {
            item.antr_nik =  resdataantrean.nik
            item.antr_nohp =  resdataantrean.nohp
            item.antr_norm =  resdataantrean.norm
            item.antr_kuotajkn =  resdataantrean.kuotajkn
            for (let h = 0; h < d_Ruangan.value.length; h++) {
                const element = d_Ruangan.value[h];
                if(element.kdsubspesialisbpjs == resdataantrean.kodepoli) {
                    item.antr_poli = element.value
                    break;
                }
            }
            // item.antr_kodepoli =  resdataantrean.kodepoli
            // item.antr_namapoli =  resdataantrean.namapoli
            item.antr_jampraktek =  resdataantrean.jampraktek
            item.antr_keterangan =  resdataantrean.keterangan
            for (let i = 0; i < d_Dokter.value.length; i++) {
                const element = d_Dokter.value[i];
                if(element.kddokterbpjs == resdataantrean.kodedokter) {
                    item.antr_dokter = element.value
                    break;
                }
            }
            // item.antr_kodedokter =  resdataantrean.kodedokter
            // item.antr_namadokter =  resdataantrean.namadokter
            item.antr_nomorkartu =  resdataantrean.nomorkartu
            item.antr_pasienbaru =  resdataantrean.pasienbaru
            item.antr_jenispasien =  resdataantrean.jenispasien
            item.antr_kodebooking =  resdataantrean.kodebooking
            item.antr_kuotanonjkn =  resdataantrean.kuotanonjkn
            item.antr_angkaantrean =  resdataantrean.angkaantrean
            item.antr_nomorantrean =  resdataantrean.nomorantrean
            item.antr_sisakuotajkn =  resdataantrean.sisakuotajkn
            for (let j = 0; j < optionsJenisKunjungan.length; j++) {
                const element = optionsJenisKunjungan[j];
                if(element.value == resdataantrean.jeniskunjungan) {
                    item.antr_jeniskunjungan = element.value
                    break;
                }
            }
            // item.antr_jeniskunjungan =  resdataantrean.jeniskunjungan
            item.antr_nomorreferensi =  resdataantrean.nomorreferensi
            item.antr_tanggalperiksa =  resdataantrean.tanggalperiksa
            item.antr_sisakuotanonjkn =  resdataantrean.sisakuotanonjkn
            item.antr_estimasidilayani =  H.formatDate(resdataantrean.estimasidilayani, "YYYY-MM-DD HH:mm")
        } 
        modalDataAntrean.value = true
    }
    
}

const saveDataAntrean = async () => {
    if(!item.antr_kodebooking) { H.alert('error', 'Harap isi kode booking terlebih dahulu !'); return; }
    if(!item.antr_jenispasien) { H.alert('error', 'Harap isi jenis pasien terlebih dahulu !'); return; }
    if(!item.antr_nomorkartu) { H.alert('error', 'Harap isi nomor kartu terlebih dahulu !'); return; }
    if(!item.antr_nik) { H.alert('error', 'Harap isi nik terlebih dahulu !'); return; }
    if(!item.antr_nohp) { H.alert('error', 'Harap isi nohp terlebih dahulu !'); return; }
    // if(!item.antr_kodepoli) { H.alert('error', 'Harap isi terlebih dahulu !'); return; }
    if(!item.antr_poli) { H.alert('error', 'Harap isi poli terlebih dahulu !'); return; }
    if(!item.antr_pasienbaru.toString()) { H.alert('error', 'Harap isi pasien baru terlebih dahulu !'); return; }
    if(!item.antr_norm) { H.alert('error', 'Harap isi norm terlebih dahulu !'); return; }
    if(!item.antr_tanggalperiksa) { H.alert('error', 'Harap isi tanggal periksa terlebih dahulu !'); return; }
    // if(!item.antr_kodedokter) { H.alert('error', 'Harap isi terlebih dahulu !'); return; }
    if(!item.antr_dokter) { H.alert('error', 'Harap isi dokter terlebih dahulu !'); return; }
    if(!item.antr_jampraktek) { H.alert('error', 'Harap isi jam praktek terlebih dahulu !'); return; }
    if(!item.antr_jeniskunjungan) { H.alert('error', 'Harap isi jenis kunjungan terlebih dahulu !'); return; }
    if(!item.antr_nomorreferensi) { H.alert('error', 'Harap isi nomor referensi terlebih dahulu !'); return; }
    if(!item.antr_nomorantrean) { H.alert('error', 'Harap isi nomor antrean terlebih dahulu !'); return; }
    if(!item.antr_angkaantrean) { H.alert('error', 'Harap isi angka antrean terlebih dahulu !'); return; }
    if(!item.antr_estimasidilayani) { H.alert('error', 'Harap isi estimasi dilayani terlebih dahulu !'); return; }
    if(!item.antr_sisakuotajkn.toString()) { H.alert('error', 'Harap isi sisa kuota jkn terlebih dahulu !'); return; }
    if(!item.antr_kuotajkn.toString()) { H.alert('error', 'Harap isi kuota jkn terlebih dahulu !'); return; }
    if(!item.antr_sisakuotanonjkn.toString()) { H.alert('error', 'Harap isi sisa kuota nonjkn terlebih dahulu !'); return; }
    if(!item.antr_kuotanonjkn.toString()) { H.alert('error', 'Harap isi kuota nonjkn terlebih dahulu !'); return; }
    if(!item.antr_keterangan) { H.alert('error', 'Harap isi keterangan terlebih dahulu !'); return; }

    let json = {
        "nik": item.antr_nik,
        "nohp": item.antr_nohp,
        "norm": item.antr_norm,
        "kodepoli": item.antr_poli,
        "kuotajkn": item.antr_kuotajkn,
        "namapoli": d_Ruangan.value.filter(x => x.value == item.antr_poli).map(x => x.label)[0],//item.antr_poli.label,
        "jampraktek": item.antr_jampraktek,
        "keterangan": item.antr_keterangan,
        "kodedokter": item.antr_dokter,
        "namadokter": d_Dokter.value.filter(x => x.value == item.antr_dokter).map(x => x.label)[0],// item.antr_dokter.label,
        "nomorkartu": item.antr_nomorkartu,
        "pasienbaru": item.antr_pasienbaru,
        "jenispasien": item.antr_jenispasien,
        "kodebooking": item.antr_kodebooking,
        "kuotanonjkn": item.antr_kuotanonjkn,
        "angkaantrean": item.antr_angkaantrean,
        "nomorantrean": item.antr_nomorantrean,
        "sisakuotajkn": item.antr_sisakuotajkn,
        "jeniskunjungan": item.antr_jeniskunjungan,
        "nomorreferensi": item.antr_nomorreferensi,
        "tanggalperiksa": item.antr_tanggalperiksa,
        "sisakuotanonjkn": item.antr_sisakuotanonjkn,
        "estimasidilayani": new Date(item.antr_estimasidilayani).getTime(),
    }
    let jsonantrean = {
        "url": `antrean/add`,
        "jenis": "antrean",
        "method": "POST",
        "data": json
    }

    isLoading.value = true
    const resantrean = await useApi().postNoMessage(`/bridging/bpjs/tools`, jsonantrean)
    isLoading.value = false
    if(resantrean.metaData.code == 200) {
        H.alert('success', 'Data Antrean Terkirim');
    } else {
        jsonantrean.data.jeniskunjungan = 3
        jsonantrean.data.nomorreferensi = jsonantrean.data.nomorreferensi.replace("Y", "Z").replace("K", "Z")
        const jsonulang = jsonantrean
        const resantreanulang = await useApi().postNoMessage(`/bridging/bpjs/tools`, jsonulang)
        if(resantreanulang.metaData.code == 200) {
            H.alert('success', 'Data Antrean Terkirim');
        } else {
            H.alert('success', `Data Gagal Terkirim ${resantrean.metaData.message}`);
        }
    }

    const jsonsaveantrean = {
        "noregistrasifk": item.antr_noregistrasifk,
        "json": json
    }
    await useApi().postNoMessage(`/bridging/antrol/updateDataAntrean`, jsonsaveantrean)
    await fecthData()
    modalDataAntrean.value = false
}

const random = (min, max) => Math.floor(Math.random() * (max - min)) + min;
const saveTaksId = async (e: any) => {
    const jsonantrean = {
        "url": `antrean/pendaftaran/kodebooking/${e.noregistrasi}`,
        "jenis": "antrean",
        "method": "GET",
        "data": null
    }

    isLoading.value = true
    const resantrean = await useApi().postNoMessage(`/bridging/bpjs/tools`, jsonantrean)
    isLoading.value = false
    if(resantrean == '' || resantrean.metaData.code != 200) {
        H.alert('error', 'Data Antrean Belum Terkirim Harap validasi Data !');
        return;
    }

    const taksId = [
        { Id: 1, waktu: random(0, 0) }, // 
        { Id: 2, waktu: random(300000, 500000) }, // 
        { Id: 3, waktu: random(600000, 900000) },  // 5 ke 6 range waktu 10 - 15 menit
        { Id: 4, waktu: random(1320000, 2100000) }, // 4 ke 5 range waktu 7 - 20 menit
        { Id: 5, waktu: random(3000000, 3300000) }, // 3 ke 4 range waktu 15 - 20 menit 
        { Id: 6, waktu: random(3600000, 3900000) }, // 2 ke 3 range waktu 5 - 10 menit 
        { Id: 7, waktu: random(7500000, 9300000) }, // 1 ke 2 range waktu 60 - 90 menit 
    ]
    let waktuid = []
    let tglregistrasi = new Date(e.tglregistrasi).getTime();
    let taksid_1 = e.taksid_1 == "-" ? tglregistrasi + taksId[0].waktu : new Date(e.taksid_1).getTime()
    let taksid_2 = e.taksid_2 == "-" ? taksid_1 + taksId[1].waktu : new Date(e.taksid_2).getTime()
    let taksid_3 = e.taksid_3 == "-" ? taksid_2 + taksId[2].waktu : new Date(e.taksid_3).getTime()
    let taksid_4 = e.taksid_4 == "-" ? taksid_3 + taksId[3].waktu : new Date(e.taksid_4).getTime()
    let taksid_5 = e.taksid_5 == "-" ? taksid_4 + taksId[4].waktu : new Date(e.taksid_5).getTime()
    let taksid_6 = e.taksid_6 == "-" ? taksid_5 + taksId[5].waktu : new Date(e.taksid_6).getTime()
    let taksid_7 = e.taksid_7 == "-" ? taksid_6 + taksId[6].waktu : new Date(e.taksid_7).getTime()

    taksid_1 = taksid_1 < tglregistrasi ? tglregistrasi + taksId[0].waktu : taksid_1
    taksid_2 = taksid_2 < taksid_1 ? taksid_1 + taksId[1].waktu : taksid_2
    taksid_3 = taksid_3 < taksid_2 ? taksid_2 + taksId[2].waktu : taksid_3
    taksid_4 = taksid_4 < taksid_3 ? taksid_3 + taksId[3].waktu : taksid_4
    taksid_5 = taksid_5 < taksid_4 ? taksid_4 + taksId[4].waktu : taksid_5
    taksid_6 = taksid_6 < taksid_5 ? taksid_5 + taksId[5].waktu : taksid_6
    taksid_7 = taksid_7 < taksid_6 ? taksid_6 + taksId[6].waktu : taksid_7

    waktuid.push({ id: 1, waktu: taksid_1 })
    waktuid.push({ id: 2, waktu: taksid_2 })
    waktuid.push({ id: 3, waktu: taksid_3 })
    waktuid.push({ id: 4, waktu: taksid_4 })
    waktuid.push({ id: 5, waktu: taksid_5 })
    waktuid.push({ id: 6, waktu: taksid_6 })
    waktuid.push({ id: 7, waktu: taksid_7 })
    
    for (let i = 0; i < waktuid.length; i++) {
        const element = waktuid[i];
        const jsonsendtaksid = {
            "noregistrasifk": e.noregistrasifk,
            "taskid": element.id,
            "waktu": element.waktu,
        }
        isLoading.value = true
        const ressentaksid = await useApi().postNoMessage(`/bridging/antrol/sendTaskId`, jsonsendtaksid)
        H.alert("info", ressentaksid.metaData.message, `Kirim Taks id ${element.id}`)
        isLoading.value = true
    }
    await fecthData()
}

const clearantr = () => {
    delete item.antr_nik
    delete item.antr_nohp
    delete item.antr_norm
    delete item.antr_kuotajkn
    delete item.antr_poli
    delete item.antr_kodepoli
    delete item.antr_namapoli
    delete item.antr_jampraktek
    delete item.antr_keterangan
    delete item.antr_dokter
    delete item.antr_kodedokter
    delete item.antr_namadokter
    delete item.antr_nomorkartu
    delete item.antr_pasienbaru
    delete item.antr_jenispasien
    delete item.antr_kodebooking
    delete item.antr_kuotanonjkn
    delete item.antr_angkaantrean
    delete item.antr_nomorantrean
    delete item.antr_sisakuotajkn
    delete item.antr_jeniskunjungan
    delete item.antr_nomorreferensi
    delete item.antr_tanggalperiksa
    delete item.antr_sisakuotanonjkn
    delete item.antr_estimasidilayani
}

loadCombo()
</script>