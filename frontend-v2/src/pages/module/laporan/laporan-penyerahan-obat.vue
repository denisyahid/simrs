
<template>
    <div class="column">
        <VCard>
            <div class="column is-12">
                <div class="search-widget">
                    <div class="field">
                        <div class="columns is-multiline">
                            <div class="column is-6 pt-0 pb-0">
                                <span>Periode</span>
                                <VDatePicker v-model="item.qFilterTgl" is-range color="pink" trim-weeks class="pt-2">
                                    <template #default="{ inputValue, inputEvents }">
                                        <VField addons>
                                            <VControl icon="feather:calendar">
                                                <VInput :value="inputValue.start" class="input-calendar"
                                                    v-on="inputEvents.start" />
                                            </VControl>
                                            <VControl>
                                                <VButton static><i class="fas fa-arrow-right" aria-hidden="true"></i>
                                                </VButton>
                                            </VControl>
                                            <VControl icon="feather:calendar">
                                                <VInput :value="inputValue.end" class="input-calendar"
                                                    v-on="inputEvents.end" />
                                            </VControl>
                                        </VField>
                                    </template>
                                </VDatePicker>
                                <VIconButton type="button" color="success" class="searcv-button pt-2" raised
                                    icon="fas fa-search" @click="fetchData()" :loading="isPlaceLoad">
                                </VIconButton>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </VCard>
    </div>

    <div class="column">
        <VCard>
            <h3 class="title is-5 mb-2">Laporan Kunjungan Tindakan</h3>
            <div class="column" v-if="isPlaceLoad">
                <VPlaceloadWrap v-for="data in 25">
                    <VPlaceload class="mx-2 mb-3" />
                    <VPlaceload class="mx-2" />
                </VPlaceloadWrap>
            </div>
            <div class="column" v-else>
                <VPlaceholderPage v-if="dataSource.length == 0" title="Data Tidak di Temukan."
                    subtitle="Silakan filter pencarian di tanggal lain" larger>
                    <template #image>
                        <img class="light-image" src="/@src/assets/illustrations/placeholders/search-1.svg" alt="" />
                        <img class="dark-image" src="/@src/assets/illustrations/placeholders/search-1-dark.svg" alt="" />
                    </template>
                </VPlaceholderPage>

                <div v-else>
                    <DataTable :value="dataSource" class="p-datatable-sm" :loading="isLoading" :paginator="true" :rows="10"
                        :rowsPerPageOptions="[5, 10, 25]" scrollable
                        paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
                        responsiveLayout="stack" breakpoint="960px" sortMode="multiple"
                        currentPageReportTemplate="Showing {first} to {last} of {totalRecords}" showGridlines>

                        <template #header>
                            <div class="flex flex-wrap align-items-center justify-content-between gap-2">
                                <VButton color="warning" class="mr-4 mb-3" icon="fas fa-file-excel" raised
                                    @click="exportExcel()"> Export
                                    to
                                    Excel </VButton>
                            </div>
                        </template>

                        <Column field="no" header="#" frozen></Column>
                        <Column field="noantri" header="No Antrian" frozen :sortable="true" style="min-width: 100px">
                        </Column>
                        <Column field="nocm" header="No RM" :sortable="true" style="min-width: 100px"></Column>
                        <Column field="namapasien" header="Nama Pasien" :sortable="true" style="min-width: 200px"></Column>
                        <Column field="kelompokpasien" header="Penjamin" :sortable="true" style="min-width: 200px"></Column>
                        <Column field="namaruanganapotik" header="Apotik" :sortable="true" style="min-width: 200px">
                        </Column>
                        <Column field="tglorder" header="Waktu Selesai Skrining" :sortable="true" style="min-width: 100px">
                        </Column>
                        <Column field="tglambilorder" header="Waktu Penyerahan Obat" :sortable="true"
                            style="min-width: 100px"></Column>
                        <Column field="namaruanganrawat" header="Ruang Rawat" :sortable="true" style="min-width: 100px">
                        </Column>
                        <Column field="checkreseppulang" header="Resep Pulang" :sortable="true" style="min-width: 50px">
                            <template #body="slotProps">
                                <span>{{ slotProps.data.checkreseppulang ? 'ada' : '-' }}</span>
                            </template>
                        </Column>
                        <Column field="keterangankeperluan" header="Keterangan" :sortable="true" style="min-width: 100px">
                        </Column>
                    </DataTable>
                </div>
            </div>

        </VCard>
    </div>

    <!-- <div class="column is-12">
          <div class="list-view list-view-v1" style="max-height:550px;overflow: auto; min-height: 400px;">
  
            <VPlaceholderPage :class="[dataHutang.length !== 0 && 'is-hidden']" title="Data Tidak di Temukan."
              subtitle="Silakan filter pencarian di tanggal lain" larger>
              <template #image>
                <img class="light-image" src="/@src/assets/illustrations/placeholders/search-1.svg" alt="" />
                <img class="dark-image" src="/@src/assets/illustrations/placeholders/search-1-dark.svg" alt="" />
              </template>
            </VPlaceholderPage>
            <div class="list-view-inner">
  
              <TransitionGroup name="list-complete" tag="div">
                <div v-for="item in dataTagihanBelumLunas" :key="item.norec" class="list-view-item"
                  @click="bayarTagihan(item)">
                  <div class="list-view-item-inner is-clickable">
                    <VAvatar size="small" picture="/images/avatars/svg/orang.svg" squarred />
                    <div class="meta-left">
                      <h3>{{ item.namapasien }}</h3>
                      <span>
                        <span>{{ item.namaruangan }} </span>
                        <br>
                        <span>Pulang : {{ H.formatDateIndoSimple(item.tglpulang) }} </span>
                      </span>
                    </div>
                    <div class="meta-right">
                      <div class="stats">
                        <div class="stat">
                          <span>{{ item.noregistrasi }}</span>
                          <span>{{ item.kelompokpasien }}</span>
                        </div>
                        <div class="separator"></div>
                        <div class="stat">
                          <span>Rp {{ formatRp(item.totalharusdibayar, '') }}</span>
                          <span>Total Harus Bayar</span>
                        </div>
                      </div>
                    </div>
                    <div class="tags">
                      <VTag :label="item.statusbayar" color="warning" rounded elevated
                        v-if="item.statusbayar == 'Belum Lunas'" />
                      <VTag :label="item.statusbayar" color="success" rounded elevated v-if="item.statusbayar == 'Lunas'" />
                    </div>
                  </div>
                </div>
              </TransitionGroup>
            </div>
          </div>
        </div> -->
</template>
  
<script setup lang="ts">
import { useRoute, useRouter } from 'vue-router'
import { ref, computed, watch, reactive } from 'vue'
import DataTable from 'primevue/datatable';
import Column from 'primevue/column'
import AutoComplete from 'primevue/autocomplete';
import { useThemeColors } from '/@src/composable/useThemeColors'
import * as H from '/@src/utils/appHelper'
import { formatRp } from '/@src/utils/appHelper'
import { useApi } from '/@src/composable/useApi'
import { useUserSession } from '/@src/stores/userSession'
import moment from 'moment'
import { useHead } from '@vueuse/head'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import Calendar from 'primevue/calendar';
import * as XLSX from "xlsx";
useHead({
    title: 'Laporan Kunjungan - ' + import.meta.env.VITE_PROJECT,
})
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)

const modalFilter: any = ref(false)
const themeColors = useThemeColors()
const userLogin = useUserSession().getUser()
const total = ref(0)
const router = useRouter()
const modalInput = ref(false)
const item: any = ref({
    qFilterTgl: {
        start: new Date(),
        end: new Date()
    },
})


const currentPage: any = ref({
    limit: 5,
    rows: 50,
})
const remakeData: any = ref([])
let dataSource: any = ref([])
let dataSourcePulang: any = ref([])
let dataHutang: any = ref([])
let d_Ruangan: any = ref([])
let d_Dokter: any = ref([])
let isLoading: any = ref(false)
let isPlaceLoad: any = ref(false)
const filters = ref('')

const dataTagihanBelumLunas = computed(() => {
    if (!item.value.qFilter) {
        return dataHutang.value
    }
    return dataHutang.value.filter((items: any) => {
        return (
            items.namapasien.match(new RegExp(item.value.qFilter, 'i')) ||
            items.noRegistrasi.match(new RegExp(item.value.qFilter, 'i'))
        )
    })
})

const fetchData = async () => {
    isPlaceLoad.value = true
    let tglAwal = 'tglAwal=' + moment(item.value.qFilterTgl.start).format('YYYY-MM-DD')
    let tglAkhir = '&tglAkhir=' + moment(item.value.qFilterTgl.end).format('YYYY-MM-DD')
    let ruanganfk = item.value.ruanganfk ? `&ruanganId=${item.value.ruanganfk.value}` : ''
    let dokter = item.value.dokterfk ? `&dokter=${item.value.dokterfk.value}` : ''
    let nama = item.value.namaPasien ? `&nama=${item.value.namaPasien}` : ''

    await useApi().get(`report/get-laporan-penyerahan-obat?${tglAwal}${tglAkhir}${ruanganfk}${dokter}${nama}`).then((response: any) => {
        response.daftar.forEach((element: any, i: any) => {
            element.no = i + 1
        });
        dataSource.value = response.daftar
    })
    isPlaceLoad.value = false

}

const fetchRuangan = async (filter: any) => {
    await useApi().get(
        `emr/dropdown/ruangan_m?select=id,namaruangan&param_search=namaruangan&query=${filter.query}&limit=10`
    ).then((response) => {
        d_Ruangan.value = response
    })
}

const fetchDokter = async (filter: any) => {
    await useApi().get(
        `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&settingdatafix=objectjenispegawaifk,idJenisPegawaiDokter&limit=10`
    ).then((response) => {
        d_Dokter.value = response
    })
}

const exportExcel = () => {
    console.log(dataSource.value)
    remakeData.value = dataSource.value.map((e: any) => {
        return {
            'No Antrian': e.noantri, 'NO RM': e.nocm, 'Nama Pasien': e.namapasien,
            'Penjamin': e.kelompokpasien, 'Apotik': e.namaruanganapotik, 'Tanggal Order': e.tglorder, 'Tanggal Ambil': e.tglambilorder, 'Ruang Rawat': e.namaruanganrawat,
            'Resep Pulang': e.checkreseppulang, 'Keterangan': e.keterangankeperluan
        }
    })
    const worksheet = XLSX.utils.json_to_sheet(remakeData.value)
    const workbook = { Sheets: { data: worksheet }, SheetNames: ['data'] };
    const excelBuffer: any = XLSX.write(workbook, { bookType: 'xlsx', type: 'array' });
    saveAsExcelFile(excelBuffer, 'products');
}

const saveAsExcelFile = (buffer: any, fileName: string) => {
    let EXCEL_TYPE = 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;charset=UTF-8';
    let EXCEL_EXTENSION = '.xlsx';
    const data: Blob = new Blob([buffer], {
        type: EXCEL_TYPE
    });
    const _url = window.URL.createObjectURL(data)
    window.open(_url, EXCEL_EXTENSION).focus();
    // window.open(_url,EXCEL_EXTENSION).focus()
    // exportFilename.saveAs(data, fileName + '_export_' + new Date().getTime() + EXCEL_EXTENSION);
}

fetchData()
</script>
  
