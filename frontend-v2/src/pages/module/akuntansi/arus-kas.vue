<template>
    <section>
        <ConfirmDialog />
        <div class="columns is-multiline">
            <VCard style="padding-bottom: 0px">
                <div class="column c-title-x">
                    <h3 class="title is-5 mb-2 mr-1">{{ title }}</h3>
                </div>
                <div class="column is-12">
                    <div class="columns is-multiline">

                        <div class="column is-9">
                        </div>
                        <div class="column is-2 is-pulled-right">
                            <VField label="Periode">
                                <VControl class="prime-auto">
                                    <Calendar inputId="range" v-model="item.bulan" :manualInput="false" class="w-100 mb-4 "
                                        :showIcon="true" view="month" dateFormat="MM-yy" />
                                </VControl>
                            </VField>

                        </div>
                        <div class="column is-1 mt-5 ">
                            <VIconButton type="button" color="success" circle raised icon="fas fa-search"
                                @click="fetchData()" :loading="isLoading">
                            </VIconButton>
                        </div>

                        <div class="column is-12 mt-5-min">
                            <VCard class="card-round-1">

                                <DataTable v-model:filters="filtersTrans" :value="dataSource" paginator :rows="50"
                                    dataKey="id" filterDisplay="row" :rowsPerPageOptions="[5, 10, 25,50, 100, 1000]"
                                    :globalFilterFields="['namamap', 'nomap']" :class="`p-datatable-small`">
                                    <template #header>
                                        <div class="columns is-multiline">
                                            <div class="column is-5">
                                                <VButton type="button" icon="pi pi-file-excel" class="mr-3" color="info"
                                                    outlined circle raised v-tooltip-prime="'Export'"
                                                    @click="exportExcel()">
                                                    Export Excel
                                                </VButton>


                                            </div>
                                            <div class="column is-3 is-offset-4">
                                                <VField>
                                                    <VControl icon="feather:search">
                                                        <input v-model="filtersTrans['global'].value"
                                                            v-on:keyup.enter="fetchData()" type="text"
                                                            class="input is-rounded" placeholder="Search" />
                                                    </VControl>
                                                </VField>
                                            </div>
                                        </div>
                                    </template>
                                    <template #empty style="text-align: center;"> No data found. </template>

                                    <Column field="kdmap" header="No" />
                                    <Column field="namamap" header="Uraian" sortable />
                                    <Column field="reff" header="Reff" sortable />
                                    <Column field="total" :header="tittlebefore" sortable>
                                        <template #body="slotProps">
                                            {{ H.formatRupiah(slotProps.data.total, '') }}
                                        </template>
                                    </Column>
                                    <Column field="total2" :header="tittlenow" sortable>
                                        <template #body="slotProps">
                                            {{ H.formatRupiah(slotProps.data.total2, '') }}
                                        </template>
                                    </Column>

                                </DataTable>

                            </VCard>
                        </div>

                    </div>
                </div>
            </VCard>
        </div>
    </section>
</template>
<script setup lang="ts">
import { ref, computed, reactive, watch } from 'vue'
import { useHead } from '@vueuse/head'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import Sidebar from 'primevue/sidebar';
import Dropdown from 'primevue/dropdown';
import MultiSelect from 'primevue/multiselect';
import { useRoute, useRouter } from 'vue-router'
import * as H from '/@src/utils/appHelper'
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import ProgressBar from 'primevue/progressbar';
import ColumnGroup from 'primevue/columngroup';   // optional
import Row from 'primevue/row';
import InputText from 'primevue/inputtext';
import { FilterMatchMode } from 'primevue/api'
import { useApi } from '/@src/composable/useApi'
import Panel from 'primevue/panel';
import Dialog from 'primevue/dialog';
import Badge from 'primevue/badge';
import FileUpload from 'primevue/fileupload';
import Calendar from 'primevue/calendar';
import moment from 'moment';
import sleep from '/@src/utils/sleep'
import Divider from 'primevue/divider';
import ConfirmDialog from 'primevue/confirmdialog'
import { useConfirm } from 'primevue/useconfirm'
import * as XLSX from "xlsx";
import { useUserSession } from '/@src/stores/userSession'
import Button from 'primevue/button';
const title = 'Arus Kas'
useHead({
    title: title + ' - ' + import.meta.env.VITE_PROJECT,
})
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)
const jmlFilter: any = ref(0)
const isLoadingUpload: any = ref(false)
const totalSize = ref(0);
const totalSizePercent = ref(0);
const confirm = useConfirm();
const modalFilter: any = ref(false)
const isLoading: any = ref(false)
const route = useRoute()
const router = useRouter()
const dataSource: any = ref([])
const isClosing2: any = ref(false)
const isClosing: any = ref(false)
const filtersTrans = ref({ global: { value: null, matchMode: FilterMatchMode.CONTAINS }, });
const filtersDetail = ref({ global: { value: null, matchMode: FilterMatchMode.CONTAINS }, });
const dataDetailJurnal: any = ref([])
const dataPopUp: any = ref([])
const data2: any = ref([])
const isLoadingpop: any = ref(false)
const modalJurnalPost: any = ref(false)
const isPostingJurnal: any = ref(false)
const valueProgress: any = ref(0)
const dataExcel: any = ref({})
const modalJurnalEntry: any = ref(false)
const item: any = reactive({
    qFilterTgl: [
        new Date(),
        new Date()
    ],
    ttlDebet: 0,
    ttlKredit: 0,
    bulan: new Date()

})
const currentPage: any = ref({
    limit: 20
})
const tittlenow:any =ref('')
const tittlebefore:any =ref('')
const getLastDayOfMonth = (year, month) => {
    // Create a Date object set to the next month's first day
    let firstDayOfNextMonth = new Date(year, month, 1);

    // Subtract one day to get the last day of the current month
    let lastDayOfMonth = new Date(firstDayOfNextMonth - 1);

    return lastDayOfMonth.getDate();
}
const fetchData = async () => {

    var sDebetAkhir = 0
    var sKreditAkhir = 0

    var bulan = H.formatDate(item.bulan, "YYYY-MM")
    var tglAwal1 = bulan + "-01"
    var tglAkhir1 = bulan + "-" + getLastDayOfMonth(bulan.substr(0, 4), bulan.substr(5, 2))
    var level = "&namalaporan='1'"
    tittlebefore.value = bulan.substr(0, 4) - 1
    var tgltgl = tittlebefore.value  + "01"
    tittlenow.value = bulan.substr(0, 4)

    isLoading.value = true
    const dat = await useApi().get(
        '/akuntansi/get-data-aruskas?tglAwal=' + tglAwal1
        + '&tglAkhir=' + tglAkhir1
        + "&tgltgl=" + tgltgl
        + "&reportdisplay=aruskas" + level
    )
    isLoading.value = false

    dataSource.value = dat


}

const exportExcel = () => {
    let judul = 'Arus Kas'
    let column = ['No ', 'Uraian ', 'Reff', tittlebefore.value, tittlenow.value]
    const worksheet = XLSX.utils.aoa_to_sheet([
        [judul],
        [],
        column,
        ...dataSource.value.map((e: any) => [
            e.nomap,
            e.namamap,
            '',
            parseFloat((e.total?e.total:0)).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,"),
            parseFloat((e.total2?e.total2:0)).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,"),
            '',
        ]),
        [],

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
    ];
    worksheet['!cols'] = columnWidths;
    const cellRef = XLSX.utils.encode_cell({ r: 0, c: 0 });
    worksheet[cellRef] = { v: judul, s: { alignment: { horizontal: 'center', vertical: 'center' } } };

    const mergeTitle = { s: { r: 0, c: 0 }, e: { r: 0, c: 8 } };

    const mergeSubtitle1 = { s: { r: 1, c: 0 }, e: { r: 1, c: 8 } };
    worksheet['!merges'] = [mergeTitle, mergeSubtitle1];
    const workbook = XLSX.utils.book_new();
    XLSX.utils.book_append_sheet(workbook, worksheet, 'data');

    const excelBuffer: any = XLSX.write(workbook, { bookType: 'xlsx', type: 'array' });
    H.saveAsExcelFile(excelBuffer, 'aruskas');
}


fetchData()

</script>
<style lang="scss"></style>
