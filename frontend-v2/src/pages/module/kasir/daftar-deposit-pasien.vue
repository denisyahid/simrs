<template>
    <section>
        <div class="column is-12 p-0 pt-5">
            <VCard>
                <div class="column c-title">
                    <div class="column is-10 pt-0">
                        <label class="title-page">Daftar Deposit Pasien</label>
                        <label for="">List Deposit Pasien</label>
                    </div>
                </div>


                <div class="column is-12">
                    <VPlaceload height="20rem" width="100%" class="mx-2 mt-4" v-if="loadData" />
                    <DataTable v-else :value="dataSource" :paginator="true" :rows="10" :rowsPerPageOptions="[5, 10, 25]"
                        class="p-datatable-sm" filterDisplay="menu" v-model:expanded-rows="expandedRows"
                        paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
                        responsiveLayout="stack" sortMode="multiple" showGridlines selectionMode="single"
                        currentPageReportTemplate="Showing {first} to {last} of {totalRecords}"
                        :globalFilterFields="['noresep', 'namaproduk', 'ruangandepo', 'namaruangan']"
                        :loading="dataSource.loading">
                        <template #header>
                            <div class="columns is-multiline">
                                <div class="column is-4 pb-0 mb-0">
                                    <VField label="Periode" />
                                            <VDatePicker v-model="item.periode" is-range color="pink" trim-weeks>
                                                <template #default="{ inputValue, inputEvents }">
                                                    <VField addons>
                                                        <VControl icon="feather:calendar">
                                                            <VInput :value="inputValue.start"
                                                                v-on="inputEvents.start" />
                                                        </VControl>
                                                        <VControl>
                                                            <VButton static><i class="fas fa-arrow-right"
                                                                    aria-hidden="true"></i>
                                                            </VButton>
                                                        </VControl>
                                                        <VControl icon="feather:calendar">
                                                            <VInput :value="inputValue.end" v-on="inputEvents.end" />
                                                        </VControl>
                                                    </VField>
                                                </template>
                                            </VDatePicker>
                                    <!-- <VButton color="primary" @click="exportExcel(activeTab)" outlined
                                        icon="fas fa-file-excel">
                                        Export To Excel
                                    </VButton> -->
                                </div>

                                <div class="column is-8 pb-0 mb-3">
                                    <div class="columns is-multiline" style="justify-content: right;">
                                        <div class="column is-5">
                                            <VField>
                                                <VLabel>Pencarian</VLabel>
                                                <VControl icon="feather:search" class="mt-3">
                                                    <input v-model="item.search" class="input"
                                                        placeholder="No Registrasi,No RM, Nama Pasien " />
                                                </VControl>
                                            </VField>
                                        </div>
                                        <div class="column is-1" style="padding-top: 38px;text-align:center">
                                            <VIconButton color="success" icon="fas fa-search" @click="fetchData()"
                                                :loading="loadSearch" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </template>
                         <Column expander/>
                        <Column field="no" header="No" style="min-width:50px"></Column>
                        <Column field="tglRegis" header="Tgl Registrasi" style="min-width:100px"></Column>
                        <Column field="tglstruk" header="Tgl Deposit" style="min-width:100px"></Column>
                        <Column field="noregistrasi" header="No Registrasi" style="min-width:100px"></Column>
                        <Column field="namapasien" header="Nama Pasien" style="min-width:150px"></Column>
                        <Column field="nocm" header="No RM" style="min-width:100px"></Column>
                        <Column field="statuspengembalian" header="Pengembalian" />
                        <!-- <Column field="tglSbm" header="TGL SBM" style="min-width:100px"></Column> -->
                        <Column field="totaldibayar" header="Total" style="min-width:100px;text-align:end">
                           <template #body="slotProps">
                                        {{ H.formatRupiah(H.roundToDecimal(parseFloat(slotProps.data.totaldibayar), 2), '') }}
                           </template>
                        </Column>
                        <template #expansion="slotProps">
                        <div class="p-3">
                            <DataTable :value="slotProps.data.details" :rows="10" showGridlines class="p-datatable-sm"
                                responsiveLayout="stack" breakpoint="960px" sortMode="multiple">
                                <Column field="no" header="No" />
                                <Column field="jenis" header="Jenis" />
                                <Column field="nosbm" header="No SBM" style="text-align: center;" />
                                <Column field="nostruk" header="No Struk" />
                                <Column field="tglsbm" header="Tanggal SBM" />
                                <Column field="totaldibayar" style="text-align: end;" header="Sub Total" >
                                    <template #body="slotProps">
                                        {{ H.formatRupiah(H.roundToDecimal(parseFloat(slotProps.data.totaldibayar), 2), '') }}
                                    </template>
                                </Column>
                                <Column :exportable="false" header="##" style="text-align: center;">
                                    <template #body="slotProps">
                                        <VIconButton v-if="slotProps.data.jenis == 'penerimaan'" type="button" icon="fas fa-backward" class="mr-2" color="danger" circle outlined
                                        raised  v-tooltip.bubble="'Pengembalian Deposit'" @click="pengembalian(slotProps.data)">
                                        </VIconButton>
                                        <VIconButton v-if="slotProps.data.jenis == 'penerimaan'" type="button" icon="fas fa-trash" class="mr-2" color="danger" circle outlined
                                        raised  v-tooltip.bubble="'Hapus Deposit'" @click="hapus(slotProps.data)">
                                        </VIconButton>
                                        <VIconButton type="button" icon="lnir lnir-printer" class="mr-2" color="info" circle outlined
                                        raised  v-tooltip.bubble="'Cetak Kwitansi'" @click="cetakKwitansi(slotProps.data)">
                                        <VIconButton type="button" icon="lnir lnir-printer" class="mr-2" color="primary" circle outlined
                                        raised  v-tooltip.bubble="'Cetak Invoice'" @click="cetakInvoice(slotProps.data)"></VIconButton>
                                        </VIconButton>
                                    </template>
                                </Column>                            
                            </DataTable>
                        </div>
                    </template>
                    </DataTable>
                </div>

            </VCard>
        </div>
    </section>
</template>

<script setup lang="ts">
import { useHead } from '@vueuse/head'
import { reactive, ref, computed, watch, onMounted } from 'vue'
import { jobs } from '/@src/data/dashboards/jobs'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useApi } from '/@src/composable/useApi'
import { useRoute, useRouter } from 'vue-router'
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import * as XLSX from "xlsx";
import * as XLSXStyle from 'xlsx-js-style';
import * as H from '/@src/utils/appHelper'
import moment from 'moment'
export type Job = 'web-developer' | 'uiux-designer' | 'backend-developer'
useHead({
    title: 'Daftar Deposit Pasien - ' + import.meta.env.VITE_PROJECT,
})
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)
const route = useRoute()
const router = useRouter()

const item: any = ref({
    periode: reactive({
        start: new Date(),
        end: new Date(),
    })
})
const dataSource: any = ref([])
const currentPage: any = ref({
    limit: 10,
    rows: 50
})
const expandedRows = ref();
const loadData:any = ref(true)
const loadSearch:any = ref(false)
const checkboxProduk: any = ref([])
const listChecked: any = ref([])
const d_ListDefault: any = ref([])
const d_departemen: any = ref([])
const d_ruangan: any = ref([])
const d_ruanganTemp: any = ref([])
const d_kelompokpasien: any = ref([])
const d_departemen_f: any = ref([])
let c = H.cacheHelper().get('c_daftar-registrasi');
if (c != undefined) {
    item.value.periode.start = new Date(c[0]);
    item.value.periode.end = new Date(c[1]);
}

currentPage.value.page = computed(() => {
    try {
        return Number.parseInt(route.query.page as string) || 1
    } catch { }
    return 1
})
watch(currentPage.value, () => {
    fetchData()
})
async function fetchDropdown() {

    await useApi().get(`/registrasi/daftar-registrasi-dropdown`).then((response: any) => {
        d_departemen.value = response.departemen.map((e: any) => { return { label: e.namadepartemen, value: e.id, default: e } })
        d_ruanganTemp.value = response.ruangan
        d_departemen_f.value = response.departemen_filter
        d_ListDefault.value = response.kelompokpasien

        d_kelompokpasien.value = response.kelompokpasien

    }).catch((e: any) => {
    })
}

async function fetchData() {
    
    dataSource.value.loading = true
    loadSearch.value = true
    let awal = `tglAwal=${H.formatDate(item.value.periode.start, 'YYYY-MM-DD')}`
    let akhir = `&tglAkhir=${H.formatDate(item.value.periode.end, 'YYYY-MM-DD')}`
    let search = item.value.search ? `&search=${item.value.search}` : ''

    H.cacheHelper().set('filterItem', item.value);

    await useApi().get(`/kasir/daftar-deposit-pasien?${awal}${akhir}${search}`).then((respond) => {
        respond.forEach((element: any, i: any) => {
            element.no = i + 1
            element.tglRegis = H.formatDate(element.tglregistrasi, 'DD-MMM-YYYY')
            element.tglstruk = H.formatDate(element.tglstruk, 'DD-MMM-YYYY')
            expandedRows.value = element.details.forEach((data: any, i: any) => {
                data.no = i + 1
                data.tglSbm = H.formatDate(data.tglsbm, 'DD-MMM-YYYY')
                // datas.total = parsefloat(data.totaldibayar) + parsefloat(datas.total)
            })
            
        });
        dataSource.value = respond
        dataSource.value.loading = false
    }).catch((e) => {
        dataSource.value.loading = false
    })
    loadData.value = false
    loadSearch.value = false
}

const hapus = (e:any)=>{
  let objectSave ={
    norec : e.norec_sp,
  }
  loadSearch.value = true
  useApi().post('/kasir/delete-deposit', objectSave).then((response: any) => {
    fetchData()
    loadSearch.value = false
  })
}


const pengembalian = (e:any)=>{
  if (e.nosbklastfk != null) {
    H.alert('error', 'Tidak bisa dikembalikan sudah dibayar')
    return
  }

  router.push({
    name: 'module-kasir-pembayaran-tagihan',
    query: {
      norec_pd: e.norec_pd,
      nocmfk: e.nocmfk,
      totalBayar: - parseFloat(e.totaldibayar),
      pageFrom: 'pengembalianDeposit'
    },
  })
  
}
//   if (e.nosbklastfk != null) {
//     H.alert('error', 'Tidak bisa dikembalikan sudah dibayar')
//     return
//   }

//   router.push({
//     name: 'module-kasir-pembayaran-tagihan',
//     query: {
//       norec_pd: e.norec_pd,
//       totalBayar: - parseFloat(e.totaldibayar),
//       pageFrom: 'depositPasien'
//     },
//   })
// })

function rekamMedis(e: any) {
    H.cacheHelper().set('xxx_cache_menu', undefined)
    router.push({
        name: 'module-emr-profile-pasien',
        query: {
            nocmfk: e.nocmfk,
            norec_pd: e.norec,
        },
    })
}
function changeDep(e: any) {
    d_ruangan.value = []
    for (let i = 0; i < d_ruanganTemp.value.length; i++) {
        const element = d_ruanganTemp.value[i];
        if (element.objectdepartemenfk == e) {
            d_ruangan.value.push({ label: element.namaruangan, value: element.id, default: element })

        }
    }
}
function editRegistrasi(e: any) {
    router.push({
        name: 'module-registrasi-registrasi-ruangan',
        query: {
            nocmfk: e.nocmfk,
            norec_pd: e.norec,
            norec_apd: e.norec_apd,
        },
    })
}

const cetakKwitansi = async (data) => {
    // H.printBlade(`report/bukti-kwitansi?noregistrasi=${data.noregistrasi}&norec_sp=${data.norec_sp}`)
    H.printBlade(`report/bukti-layanan-carabayar?noregistrasi=${data.noregistrasi}&norec_sp=${data.norec_sp}&bangsa=WNI`)
}
const cetakInvoice = async (data) => {
    H.printBlade(`report/bukti-layanan-bpjs?noregistrasi=${data.noregistrasi}`)
}

const exportExcel = (e: any) => {
    const workbook = XLSX.utils.book_new();
    const worksheet = XLSX.utils.aoa_to_sheet([
        ['Daftar Deposit Pasien'],
        [],
        ['NO', 'TANGGAL REGISTRASI', 'NO REGISTRASI', 'NO RM', 'NAMA PASIEN', 'TANGGAL SBM', 'NO SBM', 'TOTAL'],
        ...dataSource.value.map((e: any) => [
            e.no,
            e.tglRegis,
            e.noregistrasi,
            e.nocm,
            e.namapasien,
            e.tglSbm,
            e.nosbm,
            e.totaldibayar ? H.roundToDecimal(parseFloat(e.totaldibayar), 2) : 0,
        ]),
    ]);
    // Mendefinisikan style untuk header(centered)
    const headerStyle = {
        alignment: {
            horizontal: 'center',
            vertical: 'center'
        },
        font: {
            color: { rgb: 'FFFFFF' }
        },
        fill: { fgColor: { rgb: '807C7C' } }
    };

    // Mendefinisikan range header
    const headerRange = XLSX.utils.decode_range(worksheet['!ref']);
    for (let col = headerRange.s.c; col <= headerRange.e.c; col++) {
        const headerCell = XLSX.utils.encode_cell({ r: 2, c: col });
        worksheet[headerCell].s = headerStyle;
    }

    const columnWidths = [5, 13, 18, 10, 20, 13, 15, 18];

    for (let col = headerRange.s.c; col <= headerRange.e.c; col++) {
        worksheet['!cols'] = worksheet['!cols'] || [];
        worksheet['!cols'][col] = { wch: columnWidths[col] };
    }

    // Centering the text in cell A1
    const titleCell = XLSX.utils.encode_cell({ r: 0, c: 0 });
    worksheet[titleCell].s = {
        alignment: {
            horizontal: 'center',
            vertical: 'center'
        },
        font: {
            bold: true,
            sz: 18
        }
    };

    // Menggabungkan dua baris pertama
    const mergeTitle = { s: { r: 0, c: 0 }, e: { r: 1, c: 7 } };
    worksheet['!merges'] = [mergeTitle];

    XLSX.utils.book_append_sheet(workbook, worksheet, 'Pasien Deposit', true);
    XLSXStyle.writeFile(workbook, 'Daftar Deposit Pasien.xlsx');
}

onMounted(() => {
    fetchDropdown()
    if(H.cacheHelper().get('filterItem')) {
        item.value = H.cacheHelper().get('filterItem');
    }
    fetchData()
})



</script>
<style lang="scss">
@import '/@src/scss/abstracts/all';
@import '/@src/scss/module/sysadmin/master-data.scss';

.title-page {
    position: relative;
    font-size: 17px;
    display: block;
    margin-bottom: 3px;
    margin-top: 8px;
    font-weight: 600;
}

.is-dark {
    .user-grid {
        .grid-item {
            @include vuero-card--dark;
        }
    }
}

.user-grid-v1 {

    .columns {
        margin-left: -0.5rem !important;
        margin-right: -0.5rem !important;
        margin-top: -0.5rem !important;
    }

    .column {
        padding: 0.5rem !important;
    }

    .grid-item {
        @include vuero-s-card;

        text-align: center;

        >.v-avatar {
            display: block;
            margin: 0 auto 4px;
        }

        h3 {
            font-family: var(--font-alt);
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--dark-text);
        }

        p {
            font-size: 0.85rem;
        }

        .people {
            display: flex;
            justify-content: center;
            padding: 8px 0 30px;

            .v-avatar {
                margin: 0 4px;
            }
        }

        .buttons {
            display: flex;
            justify-content: space-between;

            .button {
                width: calc(50% - 4px);
                color: var(--light-text);

                &:hover,
                &:focus {
                    border-color: var(--fade-grey-dark-4);
                    color: var(--primary);
                    box-shadow: var(--light-box-shadow);
                }
            }
        }
    }
}

@media only screen and (min-width: 768px) and (max-width: 1024px) and (orientation: portrait) {
    .user-grid-v1 {
        .columns {
            display: flex;

            .column {
                min-width: 50% !important;
            }
        }
    }
}

@media only screen and (min-width: 768px) and (max-width: 1024px) and (orientation: landscape) {
    .user-grid-v1 {
        .columns {
            .column {
                min-width: 33.3% !important;
            }
        }
    }
}

:root {
    --header-bg-color: #fff;
    --search-border-color: #efefef;
    --subtitle-color: #83838e;
    --button-color: var(--white);
    --input-color: var(--subtitle-color);
}

.is-dark {
    --header-bg-color: var(--dark-sidebar-light-2);
    --search-border-color: var(--dark-sidebar-light-8);
    --input-color: var(--white);
}

.jobs-dashboard {
    display: flex;
    flex-direction: column;
    margin: 0 auto;
    overflow: hidden;

    .jobs-dashboard-wrapper {
        width: 100%;
        display: flex;
        flex-direction: column;
        flex-grow: 1;
        scroll-behavior: smooth;
        overflow: hidden;
    }

    .search-menu {
        height: 56px;
        white-space: nowrap;
        display: flex;
        flex-shrink: 0;
        align-items: center;
        background-color: var(--header-bg-color);
        border-radius: 8px;
        width: 100%;
        padding-left: 0.75rem;

        >div:not(:last-of-type) {
            border-right: 1px solid var(--search-border-color);
        }

        .search-bar {
            height: 55px;
            width: 100%;
            position: relative;
            display: flex;
            align-items: center;
            padding-right: 1.5rem;

            .field {
                width: 100%;
            }

            .multiselect-tags {
                padding-left: 2.5rem;
            }
        }

        .search-location,
        .search-job,
        .search-salary {
            display: flex;
            align-items: center;
            width: 50%;
            font-size: 14px;
            font-weight: 500;
            padding: 0 25px;
            height: 100%;
            font-family: var(--font);

            input {
                width: 100%;
                height: 100%;
                display: block;
                font-family: var(--font);
                color: var(--input-color);
                background-color: transparent;
                border: none;
            }

            svg {
                margin-right: 0.5rem;
                width: 18px;
                color: var(--primary);
                flex-shrink: 0;
            }
        }

        .search-button {
            background-color: var(--primary);
            min-width: 120px;
            height: 55px;
            border: none;
            font-weight: 500;
            font-family: var(--font);
            padding: 0 1rem;
            border-radius: 0 0.75rem 0.75rem 0;
            color: var(--button-color);
            cursor: pointer;
            margin-left: auto;
        }
    }

    .main-container {
        display: flex;
        flex-grow: 1;
        padding-top: 2rem;

        .search-type {
            width: 270px;
            display: flex;
            flex-direction: column;
            height: 100%;
            flex-shrink: 0;
        }

        .alert {
            background-color: var(--widget-grey);
            padding: 1.75rem;
            border-radius: 8px;

            .alert-title {
                font-size: 1rem;
                font-family: var(--font-alt);
                font-weight: 600;
                color: var(--dark-text);
                margin-bottom: 0.75rem;
            }

            .alert-subtitle {
                font-size: 13px;
                font-family: var(--font);
                color: var(--subtitle-color);
                margin-bottom: 1.5rem;
            }

            input {
                border-radius: 6px;
            }
        }

        .job-time {
            padding-top: 1.75rem;

            .job-wrapper {
                padding-top: 1.75rem;
            }

            .job-time-title {
                font-size: 0.95rem;
                font-family: var(--font-alt);
                font-weight: 600;
                color: var(--dark-text);
            }

            .type-container {
                display: flex;
                align-items: center;
                color: var(--subtitle-color);
                font-size: 13px;

                label {
                    margin-left: 2px;
                    display: flex;
                    align-items: center;
                    cursor: pointer;
                }

                +.type-container {
                    margin-top: 10px;
                }

                .job-number {
                    margin-left: auto;
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    height: 25px;
                    min-width: 25px;
                    background-color: var(--white);
                    color: var(--subtitle-color);
                    font-size: 0.8rem;
                    font-family: var(--font);
                    font-weight: 500;
                    padding: 0 0.25rem;
                    border-radius: 50rem;
                }
            }
        }

        .searched-jobs {
            display: flex;
            flex-direction: column;
            flex-grow: 1;
            padding-left: 2.5rem;
        }

        .searched-bar {
            display: flex;
            align-items: center;
            justify-content: space-between;

            .searched-count {
                font-family: var(--font-alt);
                font-size: 1rem;
                font-weight: 600;
                color: var(--dark-text);
            }
        }

        .job-cards {
            padding-top: 20px;
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            grid-column-gap: 1.5rem;
            grid-row-gap: 1.5rem;

            @media screen and (max-width: 1212px) {
                grid-template-columns: repeat(2, 1fr);
            }

            @media screen and (max-width: 930px) {
                grid-template-columns: repeat(1, 1fr);
            }
        }

        .job-card {
            @include vuero-l-card;

            cursor: pointer;
            transition: 0.2s;

            &:hover,
            &:focus {
                transform: translateY(-5px);
            }

            .job-card-header {
                // display: flex;
                // align-items: flex-start;
                display: flex;
                justify-content: center;
                align-items: center;
            }

            .job-card-logo {
                width: 80px;
                height: 80px;
                margin-right: -40px;
            }

            .job-card-title {
                font-family: var(--font-alt);
                font-weight: 600;
                color: var(--dark-text);
                margin-bottom: 0.75rem;
                display: flex;
                justify-content: center;
                align-items: center;

                max-height: 42px;
                overflow: hidden;
                -webkit-box-orient: vertical;
                -webkit-line-clamp: 1;
                display: -webkit-box;
                text-align: center;
            }

            .job-card-subtitle {
                color: var(--subtitle-color);
                font-family: var(--font);
                font-size: 0.95rem;
                line-height: 1.6em;
                // margin-bottom: 1rem;
                margin-top: -5px;
                display: flex;
                justify-content: center;
                align-items: center;
            }

            .job-card-buttons {
                margin-top: 1rem;

                .buttons {
                    justify-content: space-between;

                    .v-button {
                        width: 48%;
                    }
                }
            }
        }
    }
}

.is-dark {
    .jobs-dashboard {
        .job-card {
            @include vuero-card--dark;
        }

        .main-container {
            .alert {
                @include vuero-card--dark;
            }

            .job-time {
                .job-number {
                    background: var(--dark-sidebar-light-2);
                }
            }
        }
    }
}

@media screen and (max-width: 620px) {
    .job-cards {
        grid-template-columns: repeat(1, 1fr);
    }
}

@media screen and (max-width: 730px) {
    .job-cards {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media screen and (max-width: 767px) {
    .jobs-dashboard {
        .search-menu {
            flex-direction: column;
            height: auto;
            padding: 1rem;

            >div:not(:last-of-type) {
                border-right: none;
            }

            .search-bar {
                padding: 0;
            }

            .search-location,
            .search-job,
            .search-salary {
                width: 100%;
                height: 44px;
                padding: 0;
            }

            .search-button {
                width: 100%;
                border-radius: 0.75rem;
            }
        }

        .main-container {
            .search-type {
                display: none;
            }

            .searched-jobs {
                padding-left: 0;
            }
        }
    }
}
</style>
