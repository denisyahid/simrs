
<template>
    <div class="column">
        <VCard>
            <div class="columns is-multiline">
                <div class="column is-12">
                    <h3 class="title is-5 mb-2 mr-1">Laporan Pendapatan </h3>
                </div>
                <div class="column is-4 pt-0 pb-0">
                    <span>Periode</span>
                    <VDatePicker v-model="item.periode" is-range color="pink" trim-weeks class="pt-2">
                        <template #default="{ inputValue, inputEvents }">
                            <VField addons>
                                <VControl icon="feather:calendar">
                                    <VInput :value="inputValue.start" class="input-calendar" v-on="inputEvents.start" />
                                </VControl>
                                <VControl>
                                    <VButton static><i class="fas fa-arrow-right" aria-hidden="true"></i>
                                    </VButton>
                                </VControl>
                                <VControl icon="feather:calendar">
                                    <VInput :value="inputValue.end" class="input-calendar" v-on="inputEvents.end" />
                                </VControl>
                            </VField>
                        </template>
                    </VDatePicker>
                </div>
                <div class="column" style="margin-top:18px; margin-left: auto:  !important;">
                    <VIconButton type="button" color="success" class="searcv-button" raised icon="fas fa-search"
                        @click="cari()" :loading="isLoadingBtn">
                    </VIconButton>
                </div>
            </div>
            <DataTable v-model:filters="filters" :value="dataSource" paginator :rows="10" dataKey="no" filterDisplay="row"
                :globalFilterFields="['namaruangan']" :class="`p-datatable-small`" :loading="isLoading" showGridlines>
                <template #header>
                    <div class="flex justify-content-between">
                        <span class="p-input-icon-left">
                            <InputText v-model="filters['global'].value" placeholder="Cari Data" />
                        </span>
                        <VButton color="info" icon="fas fa-file-excel" raised rounded style="margin-left:20px;"
                            @click="exportExcel()"> Export Excel
                        </VButton>
                    </div>
                   
                </template>
                <Column field="no" header="No" frozen></Column>
                <Column field="namaruangan" header="Unit Layanan" style="min-width: 150px" frozen></Column>
                <Column field="jumlah" header="Tunai Ret" style="min-width: 150px"></Column>
                <Column field="totalp" header="Pendaftaran" style="min-width: 150px"></Column>
                <Column field="totalt" header="Tindakan" style="min-width: 150px">
                    <template #body="slotProps">
                        {{ H.formatRp(slotProps.data.totalt, 'Rp. ') }}
                    </template>
                </Column>
                <Column field="jaspelp" header="JS RET/RI" style="min-width: 100px">
                    <template #body="slotProps">
                        {{ H.formatRp(slotProps.data.jaspelp, 'Rp. ') }}
                    </template>
                </Column>
                <Column field="jassarp" header="JS TIND" style="min-width: 150px">
                    <template #body="slotProps">
                        {{ H.formatRp(slotProps.data.jassarp, 'Rp. ') }}
                    </template>
                </Column>
                <Column field="jaspelt" header="JS RET/RI" style="min-width: 100px">
                    <template #body="slotProps">
                        {{ H.formatRp(slotProps.data.jaspelt, 'Rp. ') }}
                    </template>
                </Column>
                <Column field="jassart" header="JS TIND" style="min-width: 150px">
                    <template #body="slotProps">
                        {{ H.formatRp(slotProps.data.jassart, 'Rp. ') }}
                    </template>
                </Column>
                <template #footer> Total Data = {{ dataSource ? dataSource.length : 0 }} </template>
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
import { FilterMatchMode } from 'primevue/api'
import InputText from 'primevue/inputtext';
import * as XLSX from "xlsx";
import * as XLSXStyle from 'xlsx-js-style';

useHead({
    title: 'Laporan Pendapatan - ' + import.meta.env.VITE_PROJECT,
})
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)
const item: any = ref({
    periode: reactive({
        start: new Date(),
        end: new Date(),
    }),
    tglsetor: new Date(),
    isnotverif: true
})
const filters = ref({
    global: { value: null, matchMode: FilterMatchMode.CONTAINS },

});

const listColor: any = ref(Object.keys(useThemeColors()))

const activeTab = ref(0)
const router = useRouter()
const route = useRoute()
const { y } = useWindowScroll()
const d_Ruangan = ref([])
const dataSource = ref([])
const d_Dokter = ref([])
const modalInput = ref(false)
const isLoadingBtn = ref(false)
const isLoadingBB = ref(false)
const isLoading = ref(false)



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
    let dari = `?dari=${moment(item.value.periode.start).format('YYYY-MM-DD')}`
    let sampai = `&sampai=${moment(item.value.periode.end).format('YYYY-MM-DD')}`
    await useApi().get(`bendahara/get-laporan-pendapatan${dari}${sampai}`).then((response) => {
        response.data.forEach((element: any, i: any) => {
            element.no = i + 1
        });

        isLoading.value = false;
        dataSource.value = response.data

    })
}

const cari = () => {

    fetchData()

}

const fetchRuangan = async (filter: any) => {
    await useApi().get(
        `emr/dropdown/ruangan_m?select=id,namaruangan&param_search=namaruangan&query=${filter.query}&limit=10`
    ).then((response) => {
        d_Ruangan.value = response
    })
}



const exportExcel = () => {
    const worksheet = XLSX.utils.aoa_to_sheet([

        ['Laporan Pendapatan Bendahara Penerimaan'],
        [],
        ['No', 'Unit Layanan','Tunai Ret', 'Pendaftaran', 'Tindakan', 'JS RET/RI', 'JS TIND', 'JS RET/RI', 'JS TIND'],
        ...dataSource.value.map((e: any) => [
            e.no,
            e.namaruangan,
            e.jumlah,
            e.totalp ? H.roundToDecimal(parseFloat(e.totalp), 2) : 0,
            e.totalt ? H.roundToDecimal(parseFloat(e.totalt), 2) : 0,
            e.jaspelp ? H.roundToDecimal(parseFloat(e.jaspelp), 2) : 0,
            e.jassarp ? H.roundToDecimal(parseFloat(e.jassarp), 2) : 0,
            e.jaspelt ? H.roundToDecimal(parseFloat(e.jaspelt), 2) : 0,
            e.jassart ? H.roundToDecimal(parseFloat(e.jassart), 2) : 0,
        ]),

    ]);

    const columnWidths = [
        { wch: 14 },
        { wch: 20 },
        { wch: 25 },
        { wch: 10 },
        { wch: 10 },
        { wch: 10 },
        { wch: 10 },
        { wch: 10 },
        { wch: 10 },
    ];
    worksheet['!cols'] = columnWidths;
    const cellRef = XLSX.utils.encode_cell({ r: 0, c: 0 });
    worksheet[cellRef] = { v: 'Laporan Pendapatan Bendahara Penerimaan', s: { alignment: { horizontal: 'center', vertical: 'center' } } };

    const mergeTitle = { s: { r: 0, c: 0 }, e: { r: 0, c: 8 } };

    const mergeSubtitle1 = { s: { r: 1, c: 0 }, e: { r: 1, c: 8 } };

    worksheet['!merges'] = [mergeTitle, mergeSubtitle1];
    const workbook = XLSX.utils.book_new();
    XLSX.utils.book_append_sheet(workbook, worksheet, 'data');

    const excelBuffer: any = XLSX.write(workbook, { bookType: 'xlsx', type: 'array' });
    saveAsExcelFile(excelBuffer, 'products');
}

const saveAsExcelFile = (buffer: any, fileName: string) => {
    let EXCEL_TYPE = 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;charset=UTF-8';
    let EXCEL_EXTENSION = '.xlsx';
    const data: Blob = new Blob([buffer], {
        type: EXCEL_TYPE
    });
    // window.open(_url, EXCEL_EXTENSION).focus();
    const desiredFileName = 'Laporan-Pendapatan-Bendahara-Penerimaan' + EXCEL_EXTENSION;
    const link = document.createElement('a');
    link.href = window.URL.createObjectURL(data);
    link.download = desiredFileName;
    link.click();
    window.URL.revokeObjectURL(link.href);
}

fetchData()


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
  