<template>
    <section>
        <div class="columns is-multiline">
            <div class="column is-12">
                <VCard style="padding-bottom: 0px">
                    <div class="column c-title-x">
                        <h3 class="title is-5 mb-2 mr-1">Laporan Jurnal Pelunasan Tagihan</h3>
                    </div>
                    <div class="column is-12">
                        <div class="columns is-multiline">
                            <div class="column is-9">
                            </div>
                            <div class="column is-2 is-pulled-right">
                                <VField label="Periode">
                                    <VControl class="prime-auto">
                                        <Calendar inputId="range" v-model="item.qFilterTgl" selectionMode="range"
                                            :manualInput="false" class="w-100 mb-4 " :showIcon="true" :showTime="false"
                                            date-format="dd-mm-yy" />
                                    </VControl>
                                </VField>
                            </div>
                            <div class="column is-1 mt-5 ">
                                <VIconButton type="button" color="success" circle raised icon="fas fa-search"
                                    @click="cari()" :loading="isLoading">
                                </VIconButton>
                            </div>
                        </div>
                        <TabView class="tabview-custom " :scrollable="true" @tab-click="klikTab($event)">
                            <TabPanel>
                                <template #header>
                                    <i class="fas fa-users mr-2" aria-hidden="true"></i>
                                    <span>REKAP PELUNASAN PIUTANG</span>
                                    <Badge :value="dataSource.length" v-if="dataSource.length > 0" severity="danger"
                                        class="ml-2" />
                                </template>


                                <div class="columns is-multiline">
                                    <div class="column is-12 ">
                                        <VCard class="card-round-1">
                                            <DataTable v-model:filters="filtersTrans" :value="dataSource" paginator
                                                :rows="10" dataKey="id" filterDisplay="row"
                                                :rowsPerPageOptions="[5, 10, 25, 50, 100, 1000]"
                                                :globalFilterFields="['namaproduktransaksi', 'keterangan']"
                                                :class="`p-datatable-small`" :loading="isLoading">
                                                <template #header>
                                                    <div class="columns is-multiline">
                                                        <div class="column is-5">
                                                            <VButton type="button" icon="pi pi-file-excel" class="mr-3"
                                                                color="solid" outlined circle raised
                                                                v-tooltip-prime="'Export'"
                                                                @click="exportExcel(dataSource, 'rekappelunasan')">
                                                                Export Excel
                                                            </VButton>
                                                            <!-- <VButton type="button" icon="pi pi-file-pdf" class="mr-3" color="solid"
                                                        outlined circle raised v-tooltip-prime="'Export'"
                                                        @click="exportPDF()">
                                                        Export PDF
                                                    </VButton> -->

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
                                                <Column v-for="col in columnGrid" :field="col.field" :header="col.title"
                                                    :style="'width:' + col.width + ';text-align:' + (col.template != undefined ? 'right' : '')">
                                                    <template #body="slotProps">
                                                        <span v-if="col.tag == undefined">{{ col.template != undefined ?
                                                            H.formatRupiah(slotProps.data[col.field], '')
                                                            : slotProps.data[col.field] }}</span>
                                                        <span v-else>
                                                            <VTag class="mr-1 mb-1" :color="'success'"
                                                                :label="slotProps.data[col.field]" />
                                                        </span>
                                                    </template>
                                                </Column>
                                                <ColumnGroup type="footer">
                                                    <Row>
                                                        <Column :footer="'TOTAL'" :colspan="3" />
                                                        <Column :footer="H.formatRupiah(item.ttlDebet, 'Debit : Rp. ')" />
                                                        <Column :footer="H.formatRupiah(item.ttlKredit, 'Kredit : Rp. ')" />
                                                        <Column :footer="''" :colspan="2" />
                                                    </Row>

                                                </ColumnGroup>
                                            </DataTable>
                                        </VCard>
                                    </div>
                                </div>

                            </TabPanel>
                            <TabPanel>
                                <template #header>
                                    <i class="fas fa-users mr-2" aria-hidden="true"></i>
                                    <span>RINCIAN PELUNASAN PIUTANG</span>
                                    <Badge :value="dataSource2.length" v-if="dataSource2.length > 0" severity="danger"
                                        class="ml-2" />
                                </template>
                                <div class="columns is-multiline">
                                    <div class="column is-12">
                                        <VCard class="card-round-2">
                                            <!-- <DataTable v-model:filters="filtersTrans" :value="dataSource2" paginator
                                                :rows="50" dataKey="id" filterDisplay="row"
                                                :rowsPerPageOptions="[5, 10, 25, 50, 100, 1000]"
                                                :globalFilterFields="['namapasien', 'keterangan']"
                                                :class="`p-datatable-small`" :loading="isLoading"
                                                rowGroupMode="subheader"> -->
                                            <DataTable v-model:expandedRowGroups="expandedRowGroups"
                                                tableStyle="min-width: 50rem" :class="`p-datatable-small`"
                                                :value="dataSource2" v-model:filters="filtersTrans"
                                                :globalFilterFields="['keterangan', 'namapasien']" rowGroupMode="subheader"
                                                :rowsPerPageOptions="[5, 10, 25, 100]" :rows="10" paginator
                                                groupRowsBy="keterangan" @rowgroup-expand="onRowGroupExpand"
                                                @rowgroup-collapse="onRowGroupCollapse" sortMode="single"
                                                sortField="keterangan" :sortOrder="1">
                                                <template #header>
                                                    <div class="columns is-multiline">
                                                        <div class="column is-5">
                                                            <VButton type="button" icon="pi pi-file-excel" class="mr-3"
                                                                color="solid" outlined circle raised
                                                                v-tooltip-prime="'Export'"
                                                                @click="exportExcel(dataSource2, 'rincianpelunasan')">
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
                                                <template #empty style="text-align: center;"> No data found.
                                                </template>
                                                <template #groupheader="slotProps">
                                                    <span class="vertical-align-middle ml-2 font-bold line-height-3">{{
                                                        slotProps.data.keterangan
                                                    }}</span>
                                                </template>
                                                <Column v-for="col in columnGridBelum" :field="col.field"
                                                    :header="col.title"
                                                    :style="'width:' + col.width + ';text-align:' + (col.template != undefined ? 'right' : '')">
                                                    <template #body="slotProps">
                                                        <span v-if="col.tag == undefined">{{ col.template !=
                                                            undefined ?
                                                            H.formatRupiah(slotProps.data[col.field], '')
                                                            : slotProps.data[col.field] }}</span>
                                                        <span v-else>
                                                            <VTag class="mr-1 mb-1" :color="'success'"
                                                                :label="slotProps.data[col.field]" />
                                                        </span>
                                                    </template>
                                                </Column>
                                                <template #groupfooter="slotProps">
                                                    <div class="flex justify-content-end font-bold w-full">
                                                        <!-- {{ H.formatRupiah(calculatePenjamin(slotProps.data.keterangan), 'Rp. ') }} -->

                                                        {{ H.formatRupiah(calculatePenjaminHarga(slotProps.data.keterangan),
                                                            'Rp. ') }}
                                                    </div>
                                                </template>
                                            </DataTable>
                                        </VCard>

                                    </div>
                                </div>
                            </TabPanel>
                        </TabView>
                    </div>
                </VCard>
            </div>
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
import TabView from 'primevue/tabview';
import TabPanel from 'primevue/tabpanel';
const title = 'Laporan Akuntansi'
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
const isLoading2: any = ref(false)
const isLoading3: any = ref(false)
const isLoading4: any = ref(false)
const route = useRoute()
const router = useRouter()
const dataSource: any = ref([])
const dataSource2: any = ref([])
const dataSource3: any = ref([])
const dataSource4: any = ref([])
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
const expandedRowGroups = ref();
const onRowGroupExpand = (event) => {

};
const onRowGroupCollapse = (event) => {

};
const item: any = reactive({
    qFilterTgl: [
        new Date(),
        new Date()
    ],
    ttlDebet: 0,
    ttlKredit: 0,
    bulan: new Date()

})
const activeTab: any = ref(0)
const d_Jenis = [
    { id: 'RI', name: 'Rawat Inap' },
    { id: 'RJ', name: 'Rawat Jalan' }
]
const currentPage: any = ref({
    limit: 20
})
const tittlenow: any = ref('')
const tittlebefore: any = ref('')
const getLastDayOfMonth = (year, month) => {
    // Create a Date object set to the next month's first day
    let firstDayOfNextMonth = new Date(year, month, 1);

    // Subtract one day to get the last day of the current month
    let lastDayOfMonth = new Date(firstDayOfNextMonth - 1);

    return lastDayOfMonth.getDate();
}
const columnGrid = ref([
    {
        "field": "no",
        "title": "No",
        "width": "20px"
    },
    {
        "field": "tglbuktitransaksi",
        "title": "Tgl Posting",
        "width": "60px"
    },
    {
        "field": "namaaccount",
        "title": "Perkiraan",
        "width": "130px"
    },
    // {
    //     "field": "namaproduktransaksi",
    //     "title": "Keterangan",
    //     "width": "100px"
    // },
    {
        "field": "hargasatuand",
        "title": "Debit",
        "width": "70px",
        template: "<span class='style-right'>{{formatRupiah('#: hargasatuand #', '')}}</span>"
    },
    {
        "field": "hargasatuank",
        "title": "Kredit",
        "width": "70px",
        template: "<span class='style-right'>{{formatRupiah('#: hargasatuank #', '')}}</span>"

    },
    {
        "field": "keterangan",
        "title": "Keterangan",
        "width": "80px"
    }

])
const columnGridBelum = ref([

    {
        "field": "notagihan",
        "title": "No Tagihan",
        "width": "130px"
    },
    {
        "field": "noregistrasi",
        "title": "No Registrasi",
        "width": "100px"
    },
    {
        "field": "namapasien",
        "title": "Nama Pasien",
        "width": "100px"
    },
    {
        "field": "tglpulang",
        "title": "Tgl Pulang",
        "width": "100px"
    },
    {
        "field": "keterangan",
        "title": "Nama Penjamin",
        "width": "100px"
    },
    {
        "field": "totalppenjamin",
        "title": "Jml Hutang",
        "width": "70px",
        template: "<span class='style-right'>{{formatRupiah('#: totalppenjamin #', '')}}</span>"
    },
    {
        "field": "hargasatuank",
        "title": "Nilai Pelunasan",
        "width": "70px",
        template: "<span class='style-right'>{{formatRupiah('#: hargasatuank #', '')}}</span>"
    }

])
const cari = () => {
    if (activeTab.value == 0) {
        fetchData()
    }
    if (activeTab.value == 1) {
        fetchDetail()
    }
}
const fetchData = async () => {

    let dari = '', sampai = '', Jra = ''

    if (item.qFilterTgl[0]) {
        dari = H.formatDate(item.qFilterTgl[0], 'YYYY-MM-DD 00:00:00')
    }
    if (item.qFilterTgl[1]) {
        sampai = H.formatDate(item.qFilterTgl[1], 'YYYY-MM-DD 23:59:59')
    } else {
        sampai = H.formatDate(item.qFilterTgl[0], 'YYYY-MM-DD 23:59:59')
    }

    if (item.jenis != undefined) {
        Jra = item.jenis.id
    }
    isLoading.value = true
    const response = await useApi().get(
        '/akuntansi/get-jurnal-pelunasan-piutang?tglAwal=' + dari
        + '&tglAkhir=' + sampai +
        "&jenis=" + Jra
    )

    isLoading.value = false


    var debetX = 0
    var kreditX = 0
    for (var i = response.length - 1; i >= 0; i--) {
        response[i].no = i + 1
        debetX = parseFloat(debetX) + parseFloat(response[i].hargasatuand)
        kreditX = parseFloat(kreditX) + parseFloat(response[i].hargasatuank)
    }
    item.ttlDebet = debetX
    item.ttlKredit = kreditX


    dataSource.value = response
    let c_set = {
        0: dari,
        1: sampai,
    }
    H.cacheHelper().set('c_jurnal_pendapatan_hutang', c_set);

}
const fetchDetail = async () => {

    let dari = '', sampai = '', Jra = ''

    if (item.qFilterTgl[0]) {
        dari = H.formatDate(item.qFilterTgl[0], 'YYYY-MM-DD 00:00:00')
    }
    if (item.qFilterTgl[1]) {
        sampai = H.formatDate(item.qFilterTgl[1], 'YYYY-MM-DD 23:59:59')
    } else {
        sampai = H.formatDate(item.qFilterTgl[0], 'YYYY-MM-DD 23:59:59')
    }

    if (item.jenis != undefined) {
        Jra = item.jenis.id
    }
    isLoading.value = true


    const response2 = await useApi().get(
        '/akuntansi/get-jurnal-pelunasan-detail-piutang?tglAwal=' + dari
        + '&tglAkhir=' + sampai +
        "&jenis=" + Jra
    )



    var debetX = 0
    var kreditX = 0
    for (var i = response2.length - 1; i >= 0; i--) {
        response2[i].no = i + 1
        debetX = parseFloat(debetX) + parseFloat(response2[i].hargasatuand)
        kreditX = parseFloat(kreditX) + parseFloat(response2[i].hargasatuank)
    }
    item.ttlDebetBv = debetX
    item.ttlKreditBv = kreditX


    isLoading.value = false
    dataSource2.value = response2
    let c_set = {
        0: dari,
        1: sampai,
    }
    H.cacheHelper().set('c_jurnal_pendapatan', c_set);

}
const calculatePenjamin = (name) => {
    let total = 0;

    if (dataSource2.value) {
        for (let customer of dataSource2.value) {
            if (customer.keterangan === name) {
                total = total + parseFloat(customer.totalppenjamin);
            }
        }
    }

    return total;
};
const calculatePenjaminHarga = (name) => {
    let total = 0;

    if (dataSource2.value) {
        for (let customer of dataSource2.value) {
            if (customer.keterangan === name) {
                total = total + parseFloat(customer.hargasatuank);
            }
        }
    }

    return total;
};
const exportExcel = (data, filename) => {
    H.exportExcel(data, filename)
}

const klikTab = (e: any) => {
    activeTab.value = e.index
    if (activeTab.value == 0) {
        fetchData()
    }
    if (activeTab.value == 1) {
        fetchDetail()
    }
}
let c = H.cacheHelper().get('c_jurnal_pendapatan_hutang');
if (c != undefined) {
    item.qFilterTgl[0] = new Date(c[0]);
    item.qFilterTgl[1] = new Date(c[1]);
}

fetchData()

</script>
<style lang="scss"></style>
  