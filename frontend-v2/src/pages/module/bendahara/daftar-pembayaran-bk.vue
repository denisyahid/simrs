
<template>
    <ConfirmDialog />
    <div class="column">
        <VCard>
            <div class="column is-12">
                <div class="search-widget">
                    <div class="field">
                        <div class="columns is-multiline">
                            <div class="column is-12">
                                <h3 class="title is-5 mb-2 mr-1">Daftar Pembayaran</h3>
                            </div>
                            <div class="column is-3">
                                <span>Periode</span>
                                <VDatePicker v-model="item.periode" is-range color="pink" trim-weeks class="pt-2">
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
                            <div class="column is-3">
                                <span>Kasir</span>
                                <VField class="is-autocomplete-select pt-2" v-slot="{ id }">
                                    <VControl icon="feather:search">
                                        <AutoComplete v-model="item.Skasir" :suggestions="d_Kasir"
                                            @complete="fetchPegawai($event)" :optionLabel="'label'" :dropdown="true"
                                            :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                                            :field="'label'" placeholder="Kasir" />
                                    </VControl>
                                </VField>
                            </div>
                            <div class="column is-2">
                                <VField>
                                    <span>No SBK</span>
                                    <VControl>
                                        <VInput type="text" placeholder=" No SBK ..." autocomplete="off"
                                            v-model="item.noSBK" v-on:keyup.enter="fetchData()" style="margin-top:5px;" />
                                    </VControl>
                                </VField>
                            </div>
                            <div class="column is-2">
                                <span>Cara Bayar</span>
                                <VField class="is-autocomplete-select pt-2">
                                    <VControl icon="feather:search">
                                        <Multiselect mode="single" v-model="item.ScaraBayar" :options="d_caraBayar"
                                            placeholder="Pilih data" :searchable="true" />
                                    </VControl>
                                </VField>
                            </div>
                            <div class="column" style="margin-top: 25px; margin-left: auto:  !important;">
                                <VIconButton type="button" color="success" class="searcv-button" raised icon="fas fa-search"
                                    @click="cari()" :loading="isLoadingBtn">
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
            <div class="columns is-multiline">

                <!-- <div class="column is-8">
                    <div class="column is-12">
                        <VButton color="success" icon="fas fa-print" raised rounded style="margin-left:20px;"
                            @click="cetakLapHarian()"> Laporan Penerimaan Harian
                        </VButton>

                        <VButton color="warning" icon="fas fa-print" style="margin-left:20px;" raised rounded>
                            Laporan Penerimaan Per Transaksi
                        </VButton>
                    </div>
                </div> -->
            </div>

            <DataTable :value="dataSource" tableStyle="min-width: 50rem" scrollable :paginator="true" :rows="10"
                :rowsPerPageOptions="[5, 10, 25]" :loading="isLoading" showGridlines>
                <template #header>
                    <div class="flex flex-wrap align-items-center justify-content-between gap-2">
                        <span class="text-xl text-900 font-bold">Rincian Pembayaran</span>
                        <!-- <VButton color="info" icon="fas fa-paste" raised rounded style="margin-left:20px;"
                            @click="collectTagihan()"> Collecting Tagihan
                        </VButton> -->
                    </div>
                </template>
                <Column :exportable="false" header="#" style="min-width: 100px" frozen>
                    <template #body="slotProps">
                        <VIconButton type="button" icon="fas fa-trash" class="ml-3" color="danger" circle raised
                            v-tooltip.top="'Batal Bayar'" @click="batalBayar(slotProps.data)">
                        </VIconButton>
                        <!-- <VIconButton type="button" icon="lnil lnil-reload" class="ml-2" color="info" circle raised
                            v-tooltip.left="'Rekonsiliasi'">
                        </VIconButton> -->

                    </template>

                </Column>
                <Column field="nosbk" header="No SBK" style="min-width: 150px" frozen></Column>
                <Column field="tglsbk" header="Tanggal" style="min-width: 200px"></Column>
                <Column field="namalengkap" header="Kasir" style="min-width: 150px"></Column>
                <Column field="carabayar" header="Cara Bayar" style="min-width: 150px; text-align:center;"></Column>
                <Column field="bankaccountnomor" header="Account Number" style="min-width: 150px; text-align:center;"></Column>

                <Column field="totalall" header="Total Tagihan" style="min-width: 150px">
                    <template #body="slotProps">
                        {{ H.formatRp(slotProps.data.totalall, 'Rp. ') }}
                    </template>
                </Column>
                <Column field="totalbiayaadmin" header="Biaya Admin" style="min-width: 150px">
                    <template #body="slotProps">
                        {{ H.formatRp(slotProps.data.totalbiayaadmin, 'Rp. ') }}
                    </template>
                </Column>

                <Column field="totaldibayar" header="Total Dibayar" style="min-width: 150px">
                    <template #body="slotProps">
                        {{ H.formatRp(slotProps.data.totaldibayar, 'Rp. ') }}
                    </template>
                </Column>

                <Column field="keterangan" header="Keterangan" style="min-width: 200px"></Column>
                <!-- <Column field="isrekon" header="Status Rekonsiliasi" style="min-width: 100px; text-align:center"></Column> -->


                <template #footer> Total Transaksi = {{ dataSource ? dataSource.length : 0 }} </template>
            </DataTable>

        </VCard>
    </div>
</template>
<script setup lang="ts">
import { useWindowScroll } from '@vueuse/core'
import { useApi } from '/@src/composable/useApi'
import { ref, computed, watch, reactive, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useHead } from '@vueuse/head'
import { useUserSession } from '/@src/stores/userSession'
import ConfirmDialog from 'primevue/confirmdialog'
import { useConfirm } from 'primevue/useconfirm'
import * as H from '/@src/utils/appHelper'
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import { useToaster } from '/@src/composable/toaster'
import Dialog from 'primevue/dialog';
import AutoComplete from 'primevue/autocomplete';
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useThemeColors } from '/@src/composable/useThemeColors'
import moment from 'moment'
import TabView from 'primevue/tabview';
import TabPanel from 'primevue/tabpanel';
import Badge from 'primevue/badge';
import Tag from 'primevue/tag';
import Divider from 'primevue/divider';

useHead({
    title: 'Daftar Pembayaran - ' + import.meta.env.VITE_PROJECT,
})
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)
const item: any = ref({
    periode: reactive({
        start: new Date(),
        end: new Date(),
    }),
    tglsetor: new Date(),
    tglbayar: new Date(),
    totalbayarawal: 0,
    tglCollect: new Date(),
})

const listColor: any = ref(Object.keys(useThemeColors()))

const activeTab = ref(0)
const router = useRouter()
const route = useRoute()
const { y } = useWindowScroll()
const d_Ruangan = ref([])
const sourceOrder = ref([])
const dataSource = ref([])
const dataBayar = ref([])
const dataDetail = ref([])
const d_Kasir = ref([])
const modalInput = ref(false)
const modalBayar = ref(false)
const modalConfirm = ref(false)
const modelCheck: any = ref([]);
const isLoadingBtn = ref(false)
const isLoadingBB = ref(false)
const isLoading = ref(false)
const d_CaraBayarFoot: any = ref([])
const d_caraBayar: any = ref([])
const d_caraSetor: any = ref([])
const confirm = useConfirm()
const modalCollect = ref(false)
const totalCollect: any = ref(0);
const totalTagihan: any = ref(0);
const totalKlaim: any = ref(0);
const totalPrice: any = ref(0);
const totalPPN: any = ref(0);
const DiskonTotal: any = ref(0);
const listChecked: any = ref([]);
const d_Setor = [
    {
        label: 'BELUM LUNAS',
        value: '1',
    },
    {
        label: 'LUNAS',
        value: '2',
    },
]

const currentPage: any = ref({
    limit: 5,
    rows: 50
})

currentPage.value.page = computed(() => {
    try {
        return Number.parseInt(route.query.page as string) || 1
    } catch { }
    return 1
})

const fetchData = async () => {
    isLoading.value = true;
    let tglAwal = `?tglAwal=${moment(item.value.periode.start).format('YYYY-MM-DD')}`
    let tglAkhir = `&tglAkhir=${moment(item.value.periode.end).format('YYYY-MM-DD')}`
    let noSBK = item.value.noSBK ? `&noSBK=${item.value.noSBK}` : ''
    let Skasir = item.value.Skasir ? `&Skasir=${item.value.Skasir.value}` : ''
    let ScaraBayar = item.value.ScaraBayar ? `&ScaraBayar=${item.value.ScaraBayar.value}` : ''
   
    item.value.sisaHutang = 0
    await useApi().get(`bendahara/daftar-pembayaran-bk${tglAwal}${tglAkhir}${noSBK}${Skasir}${ScaraBayar}`).then((response) => {
        response.daftar.forEach((element: any, i: any) => {
            element.no = i + 1
            element.totalall = parseFloat(element.totaldibayar) + parseFloat(element.totalbiayaadmin)
            if (element.statusrekon == true) {
                element.isrekon = "✔";
            } else {
                element.isrekon = "✘";
            }
            if (element.bankaccountnomor == null) {
                element.bankaccountnomor = '-'
            }
        });
        isLoading.value = false;
        dataSource.value = response.daftar
    })
}
const fetchDropdown = () => {
    useApi().get(
        `/bendahara/get-list-bayar`).then((response: any) => {
            d_caraBayar.value = response.carabayar.map((e: any) => { return { label: e.carabayar, value: e.id, default: e } })
            d_caraSetor.value = response.carasetor.map((e: any) => { return { label: e.carasetor, value: e.id, default: e } })

        })
}



const batalBayar = (e: any) => {
    if (e.status == 'LUNAS') {
        H.alert('warning', 'Tagihan Sudah Dibayar')
        return
    }
    confirm.require({
        message: 'Apakah anda yakin Batal Bayar Tagihan ?',
        header: 'Konfirmasi Batal Bayar Tagihan',
        icon: 'pi pi-info-circle',
        acceptClass: 'p-button-danger',
        accept: () => {
            deleteCollect(e)
        },
        reject: () => { },
    })
}

const deleteCollect = async (e: any) => {

    await useApi().post('/bendahara/batal-bayar-sup', { 'norec_sbk': e.norec_sbk }).then((response) => {
        fetchData()
    }).catch((err: any) => {

    })

}


const kembaliKeun = () => {
    modalBayar.value = false
}





const fetchPegawai = async (filter: any) => {
    await useApi().get(
        `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&settingdatafix=objectjenispegawaifk,idJenisPegawaiKasir&limit=10`
    ).then((response) => {
        d_Kasir.value = response
    })
}




const cari = () => {
    fetchData()
}



fetchData()
fetchDropdown()



watch(currentPage.value, () => {
    fetchData()
})



</script>
  
  
  
<style lang="scss">
@import '/@src/scss/abstracts/all';
@import '/@src/scss/components/forms-outer';
@import '/@src/scss/custom/timeline-css';



.fs-075 {
    font-size: 0.9rem;
}


.is-navbar {
    .form-layout {
        margin-top: 30px;
    }
}

.form-layout {
    // max-width: 740px;
    margin: 0 auto;

    &.is-separate {
        // max-width: 1040px;

        .form-outer {
            background: none;
            border: none;

            .form-body {
                display: flex;

                .form-section {
                    flex-grow: 2;
                    padding: 10px;
                    width: 50%;

                    .form-section-inner {
                        @include vuero-s-card;

                        padding: 40px;

                        &.has-padding-bottom {
                            padding-bottom: 60px;
                            height: 100%;
                        }

                        >h3 {
                            font-family: var(--font-alt);
                            font-size: 1.2rem;
                            font-weight: 600;
                            color: var(--dark-text);
                            margin-bottom: 30px;
                        }

                        .columns {
                            .column {
                                padding-top: 0.25rem;
                                padding-bottom: 0.25rem;
                            }
                        }

                        .radio-boxes {
                            display: flex;
                            justify-content: space-between;
                            margin-left: -8px;
                            margin-right: -8px;

                            .radio-box {
                                position: relative;
                                width: calc(50% - 16px);
                                margin: 8px;

                                &:focus-within {
                                    border-radius: 3px;
                                    outline-offset: var(--accessibility-focus-outline-offset);
                                    outline-width: var(--accessibility-focus-outline-width);
                                    outline-style: var(--accessibility-focus-outline-style);
                                    outline-color: var(--primary);
                                }

                                input {
                                    position: absolute;
                                    top: 0;
                                    left: 0;
                                    height: 100%;
                                    width: 100%;
                                    opacity: 0;
                                    cursor: pointer;

                                    &:checked {
                                        +.radio-box-inner {
                                            background: var(--primary);
                                            border-color: var(--primary);
                                            box-shadow: var(--primary-box-shadow);

                                            .fee,
                                            p {
                                                color: var(--smoke-white);
                                            }
                                        }
                                    }
                                }

                                .radio-box-inner {
                                    background: var(--white);
                                    border: 1px solid var(--fade-grey-dark-3);
                                    text-align: center;
                                    border-radius: var(--radius);
                                    font-family: var(--font);
                                    font-weight: 600;
                                    font-size: 0.9rem;
                                    transition: color 0.3s, background-color 0.3s, border-color 0.3s,
                                        height 0.3s, width 0.3s;
                                    padding: 30px 20px;

                                    .fee {
                                        font-family: var(--font);
                                        font-weight: 700;
                                        color: var(--dark-text);
                                        font-size: 2.4rem;
                                        line-height: 1;

                                        span {
                                            &::after {
                                                content: '$';
                                                position: relative;
                                                top: -10px;
                                                font-size: 1.5rem;
                                            }
                                        }
                                    }

                                    p {
                                        font-family: var(--font-alt);
                                    }
                                }
                            }
                        }

                        .control {
                            >p {
                                padding-top: 12px;

                                >span {
                                    display: block;
                                    font-size: 0.9rem;

                                    span {
                                        font-weight: 500;
                                        color: var(--dark-text);
                                    }
                                }
                            }
                        }
                    }

                    .form-section-outer {
                        .checkboxes {
                            padding: 16px 0;

                            .checkbox {
                                padding: 0;
                                font-size: 0.9rem;
                            }
                        }

                        .button-wrap {
                            .button {
                                min-height: 60px;
                                font-size: 1.05rem;
                                font-weight: 600;
                                font-family: var(--font-alt);
                            }
                        }
                    }
                }
            }
        }
    }
}

.is-dark {
    .form-layout {
        &.is-separate {
            .form-outer {
                background: none !important;

                .form-body {
                    .form-section {
                        .form-section-inner {
                            @include vuero-card--dark;

                            >h3 {
                                color: var(--dark-dark-text);
                            }

                            .radio-boxes {
                                .radio-box {
                                    input:checked+.radio-box-inner {
                                        background: var(--primary);
                                        border-color: var(--primary);
                                        box-shadow: var(--primary-box-shadow);

                                        .fee,
                                        p {
                                            color: var(--smoke-white);
                                        }
                                    }

                                    .radio-box-inner {
                                        background: var(--dark-sidebar-light-2);
                                        border-color: var(--dark-sidebar-light-12);

                                        .fee {
                                            color: var(--dark-dark-text);
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    }
}

@media only screen and (max-width: 767px) {
    .form-layout {
        &.is-separate {
            .form-outer {
                .form-body {
                    padding-left: 0;
                    padding-right: 0;
                    flex-direction: column;

                    .form-section {
                        width: 100%;

                        .form-section-inner {
                            padding: 30px;
                        }
                    }
                }
            }
        }
    }
}

@media only screen and (min-width: 768px) and (max-width: 1024px) and (orientation: portrait) {
    .form-layout {
        &.is-separate {
            .form-outer {
                .form-body {
                    padding-left: 0;
                    padding-right: 0;

                    // flex-direction: column;

                    .form-section {
                        // width: 100%;

                        .form-section-inner {
                            padding: 30px;
                        }
                    }
                }
            }
        }
    }
}

.all-projects {
    .all-projects-header {
        display: flex;
        padding: 20px;
        background: var(--white);
        border: 1px solid var(--fade-grey-dark-3);
        border-radius: var(--radius-large);
        margin-bottom: 1.5rem;

        .header-item {
            width: 25%;
            border-right: 1px solid var(--fade-grey-dark-3);

            &:last-child {
                border-right: none;
            }

            .item-inner {
                text-align: center;

                .lnil,
                .lnir {
                    font-size: 2.2rem;
                    margin-bottom: 6px;
                    color: var(--primary);
                }

                span {
                    display: block;
                    font-family: var(--font);
                    font-weight: 600;
                    font-size: 1.4rem;
                    color: var(--dark-text);
                }

                p {
                    font-family: var(--font-alt);
                }
            }
        }
    }

    .projects-card-grid {
        .grid-item {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            min-height: 220px;
            padding: 20px;
            background: var(--white);
            border: 1px solid var(--fade-grey-dark-3);
            border-radius: var(--radius-large);

            .top-section {
                .head {
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                    margin-bottom: 8px;

                    h3 {
                        font-size: 1rem;
                        font-family: var(--font-alt);
                        color: var(--dark-text);
                        font-weight: 600;
                    }
                }

                .body {
                    p {
                        font-family: var(--font);
                        color: var(--light-text);
                    }
                }
            }

            .bottom-section {
                display: flex;

                .foot-block {
                    margin-right: 30px;

                    .heading {
                        font-family: var(--font-alt);
                        font-size: 0.75rem;
                        color: var(--light-text-dark-22);
                    }

                    >p {
                        padding-top: 5px;
                    }

                    .developers {
                        display: flex;

                        .v-avatar {
                            margin-right: 6px;
                        }
                    }
                }
            }
        }
    }
}

.heading {
    font-family: var(--font-alt);
    font-size: 0.75rem;
    color: var(--light-text-dark-22);
}

.is-dark {
    .all-projects {
        .all-projects-header {
            background: var(--dark-sidebar-light-6);
            border-color: var(--dark-sidebar-light-12);

            .header-item {
                border-color: var(--dark-sidebar-light-18);

                span {
                    color: var(--dark-dark-text);
                }

                i {
                    color: var(--primary) !important;
                }
            }
        }

        .projects-card-grid {
            .grid-item {
                background: var(--dark-sidebar-light-6);
                border-color: var(--dark-sidebar-light-12);

                .top-section {
                    .head {
                        h3 {
                            color: var(--dark-dark-text);
                        }
                    }
                }

                .bottom-section {
                    .foot-block {
                        .heading {
                            color: var(--light-text-dark-12);
                        }
                    }
                }
            }
        }
    }
}

.tabs-wrapper.is-slider .tabs,
.tabs-wrapper-alt.is-slider .tabs {
    max-width: 30% !important;
}
</style>
  