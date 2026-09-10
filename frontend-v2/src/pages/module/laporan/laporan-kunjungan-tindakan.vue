
<template>
    <div class="column">
        <VCard>
            <div class="column is-12">
                <div class="search-widget">
                    <div class="field">
                        <div class="columns is-multiline">
                            <div class="column is-4 pt-0 pb-0">
                                <span>Periode Registrasi</span>
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
                            </div>
                            <div class="column pt-0 pb-0 is-2">
                                <span>Dokter</span>
                                <VField class="is-autocomplete-select pt-2" v-slot="{ id }">
                                    <VControl icon="feather:search">
                                        <AutoComplete v-model="item.dokterfk" :suggestions="d_Dokter"
                                            @complete="fetchDokter($event)" :optionLabel="'label'" :dropdown="true"
                                            :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                                            :field="'label'" />
                                    </VControl>
                                </VField>
                            </div>
                            <div class="column is-2 pt-0 pb-0">
                                <span>Ruangan</span>
                                <VField class="is-autocomplete-select pt-2" v-slot="{ id }">
                                    <VControl icon="feather:search">
                                        <AutoComplete v-model="item.ruanganfk" :suggestions="d_Ruangan"
                                            @complete="fetchRuangan($event)" :optionLabel="'label'" :dropdown="true"
                                            :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                                            :field="'label'" />
                                    </VControl>
                                </VField>
                            </div>
                            <div class="column is-2 pt-0 pb-0">
                                <span>Kelompok</span>
                                <VField class="is-autocomplete-select pt-2" v-slot="{ id }">
                                    <VControl icon="feather:search">
                                        <AutoComplete v-model="item.kelompokpasien" :suggestions="d_KelompokPasien"
                                            @complete="fetchKelompokPasien($event)" :optionLabel="'label'" :dropdown="true"
                                            :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                                            :field="'label'" />
                                    </VControl>
                                </VField>
                            </div>
                        </div>
                    </div>
                    <div class="field">
                        <div class="control">
                            <div class="columns is-multiline">
                                <div class="column is-11 ">
                                    <input type="text" v-model="item.namaPasien" v-on:keyup.enter="fetchData()"
                                        class="input" placeholder="Search..." />
                                </div>
                                <div class="column" style="margin-left: auto:  !important;">
                                    <VIconButton type="button" color="success" class="searcv-button" raised
                                        icon="fas fa-search" @click="fetchData()" :loading="isPlaceLoad">
                                    </VIconButton>
                                </div>
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

                        <Column field="nocm" header="No Rekam medis"></Column>
                        <Column field="tglregistrasi" header="Tanggal" :sortable="true" style="min-width: 200px">
                            <template #body="slotProps">
                                <span>{{ H.formatDateToLocalString(slotProps.data.tglregistrasi) }}</span>
                            </template>
                        </Column>
                        <Column field="tglregistrasi" header="Jam Periksa" :sortable="true" style="min-width: 100px">
                            <template #body="slotProps">
                                <span>{{ H.getTime(slotProps.data.tglregistrasi, slotProps.data.tglregistrasi) }}</span>
                            </template>
                        </Column>
                        <Column field="namaproduk" header="Nama Produk" :sortable="true" style="min-width: 150px">
                        </Column>
                        <Column field="namapasien" header="Nama Pasien" :sortable="true" style="min-width: 150px">
                        </Column>
                        <Column field="nohp" header="No Hp" :sortable="true" style="min-width: 100px"></Column>
                        <Column field="tgllahir" header="Tanggal Lahir" :sortable="true" style="min-width: 200px">
                            <template #body="slotProps">
                                <span>{{ H.formatDateToLocalString(slotProps.data.tgllahir) }}</span>
                            </template>
                        </Column>
                        <Column field="umur" header="Umur" :sortable="true" style="min-width: 50px">
                            <template #body="slotProps">
                                <span>{{ slotProps.data.tahun }} Thn {{ slotProps.data.bulan }} Bulan {{ slotProps.data.hari
                                }} Hari</span>
                            </template>
                        </Column>
                        <Column field="jeniskelamin" header="JK" :sortable="true" style="min-width: 100px"></Column>
                        <Column field="agama" header="Agama" :sortable="true" style="min-width: 100px"></Column>
                        <Column field="pendidikan" header="Pendidikan" :sortable="true" style="min-width: 100px">
                        </Column>
                        <Column field="pekerjaan" header="Pekerjaan" :sortable="true" style="min-width: 100px"></Column>
                        <Column field="alamatlengkap" header="Alamat" :sortable="true" style="min-width: 200px"></Column>
                        <Column field="namadesakelurahan" header="Kelurahan" :sortable="true" style="min-width: 100px">
                        </Column>
                        <Column field="namakotakabupaten" header="Kota" :sortable="true" style="min-width: 100px">
                        </Column>
                        <Column field="statusperkawinan" header="Status Kawin" :sortable="true" style="min-width: 100px">
                        </Column>
                        <Column field="kelompokpasien" header="Jenis Pasien" :sortable="true" style="min-width: 100px">
                        </Column>
                        <Column field="namalengkap" header="Dokter" :sortable="true" style="min-width: 100px">
                        </Column>
                        <Column field="petugaspelayanan" header="Nakes" :sortable="true" style="min-width: 100px">
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
let d_KelompokPasien: any = ref([])
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
    let kelompokpasien = item.value.kelompokpasien ? `&kelId=${item.value.kelompokpasien.value}` : ''

    await useApi().get(`pelayanan/get-laporan-pengunjung-tindakan?${tglAwal}${tglAkhir}${ruanganfk}${dokter}${nama}${kelompokpasien}`).then((response: any) => {
        response.data.forEach((element: any, i: any) => {
            element.no = i + 1
        });
        dataSource.value = response.data
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

const fetchKelompokPasien = async (filter: any) => {

    await useApi().get(
        `emr/dropdown/kelompokpasien_m?select=id,kelompokpasien&param_search=kelompokpasien&query=${filter.query}&limit=10`
    ).then((response) => {
        d_KelompokPasien.value = response
    })
}

const exportExcel = () => {
    remakeData.value = dataSource.value.map((e: any) => {
        return {
            'No Rekam Medis': e.nocm, 'Tanggal': H.formatDateToLocalString(e.tglregistrasi), 'Jam Periksa': H.getTime(e.tglregistrasi, e.tglregistrasi), 'Nama Pasien': e.namaproduk,
            'Nama Pasien': e.namapasien, 'No Hp': e.nohp, 'Tanggal Lahir': H.formatDateToLocalString(e.tgllahir), 'Umur': e.tahun + 'Thn' + e.bulan + 'Bln' + e.hari + 'Hari',
            'Jenis Kelamin': e.jeniskelamin, 'Agama': e.agama, 'Pendidikan': e.pendidikan, 'Pekerjaan': e.pekerjaan, 'Alamat': e.alamatlengkap,
            'Kelurahan': e.namadesakelurahan, 'Kota': e.namakotakabupaten, 'Status Kawin': e.statusperkawinan, 'Jenis Pasien': e.kelompokpasien, 'Dokter': e.namalengkap,
            'Nakes': e.petugaspelayanan
        }
    })

    const worksheet = XLSX.utils.json_to_sheet(remakeData.value)

    // Customize column widths (adjust the width values as needed)
    const columnWidths = [
        { wch: 14.50 }, // Kolom A
        { wch: 14 }, // Kolom B
        { wch: 10 }, // Kolom C
        { wch: 11.50 }, // Kolom D
        { wch: 12 }, // Kolom E
        { wch: 12 }, // Kolom F
        { wch: 13.50 }, // Kolom G
        { wch: 9 }, // Kolom H
        { wch: 9 }, // Kolom I
        { wch: 9 }, // Kolom J
        { wch: 12 }, // Kolom K
        { wch: 15 }, // Kolom L
        { wch: 10 }, // Kolom M
        { wch: 10 }, // Kolom N
        { wch: 12 }, // Kolom O
        { wch: 12 }, // Kolom P
        { wch: 12 }, // Kolom Q
        { wch: 15 }, // Kolom R
    ];

    worksheet['!cols'] = columnWidths;

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
  
