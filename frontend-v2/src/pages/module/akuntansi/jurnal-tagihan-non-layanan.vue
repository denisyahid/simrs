<template>
    <section>
        <div class="columns is-multiline">
            <VCard style="padding-bottom: 0px">
                <div class="column c-title-x">
                    <h3 class="title is-5 mb-2 mr-1">{{ title }}</h3>
                </div>
                <div class="column is-12">
                    <div class="columns is-multiline">
                        <div class="column is-3">
                            <VDatePicker v-model="item.qFilterTgl" is-range color="pink" trim-weeks :max-date="new Date()">
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
                                            <VInput :value="inputValue.end" class="input-calendar" v-on="inputEvents.end" />
                                        </VControl>
                                    </VField>
                                </template>
                            </VDatePicker>
                        </div>
                        <div class="column is-5">
                            <VField>
                                <VControl icon="feather:search">
                                    <input v-model="item.filter" v-on:keyup.enter="fetchData()" type="text"
                                        class="input is-rounded" placeholder="No Struk" />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-2">
                            <VIconButton type="button" color="success" circle raised icon="fas fa-search"
                                @click="fetchData()" :loading="isLoading" class=" is-pulled-">
                            </VIconButton>
                            <VIconButton circle class="ml-2  is-pulled-" icon="fas fa-filter" raised bold
                                @click="modalFilter = true" v-tooltip.bubble="'Filter'">
                            </VIconButton>
                            <Badge :value="jmlFilter" v-if="jmlFilter > 0" severity="info" class="is-pulled-"
                                style="margin-left:-10px ;z-index: 100;  position: relative; "></Badge>
                        </div>

                        <div class="column is-12">
                            <VCard class="card-round-2">
                                <p class="title-c"> TRANSAKSI {{ item.tittlegridtransaksi ? item.tittlegridtransaksi
                                    : ''
                                }}</p>
                                <DataTable v-model:filters="filtersTrans" :value="dataSourceTrans" paginator :rows="10"
                                    dataKey="id" filterDisplay="row" :rowsPerPageOptions="[5, 10, 25, 100]"
                                    :globalFilterFields="['namaproduk']" :class="`p-datatable-small`">
                                    <template #header>
                                        <div class="flex justify-content-end">
                                            <span class="p-input-icon-left">
                                                <InputText v-model="filtersTrans['global'].value" placeholder="Search" />
                                            </span>
                                        </div>
                                    </template>
                                    <template #empty style="text-align: center;"> No data found. </template>
                                    <Column :exportable="false" header="Mapping" style="width:30px">
                                        <template #body="slotProps">
                                            <VIconButton type="button" icon="pi pi-sitemap" class="mr-3" color="info" circle
                                                outlined raised v-tooltip-prime="'Lihat Map Akun/Jurnal'"
                                                @click="loadMap(slotProps.data)" :loading="slotProps.data.isLoading">
                                            </VIconButton>
                                        </template>
                                    </Column>
                                    <Column v-for="col in columnTrans" :field="col.field" :header="col.title" sortable
                                        :style="'width:' + col.width">
                                        <template #body="slotProps">
                                            <span v-if="col.tag == undefined">{{ col.template != undefined ?
                                                H.formatRupiah(slotProps.data[col.field], '')
                                                : slotProps.data[col.field] }}</span>
                                            <span v-else>
                                                <VTag class="mr-1 mb-1"
                                                    :color="slotProps.data[col.field] == null ? 'danger' : 'success'"
                                                    :label="slotProps.data[col.field]" />
                                            </span>
                                        </template>
                                    </Column>

                                    <Column :exportable="false" header="Update" style="width:50px">
                                        <template #body="slotProps">
                                            <VIconButton type="button" icon="feather:edit" class="mr-3" color="success"
                                                circle outlined raised v-tooltip-prime="'Update Jurnal'"
                                                @click="updateJurnal(slotProps.data)" :loading="slotProps.data.isLoading2">
                                            </VIconButton>
                                        </template>
                                    </Column>
                                    <ColumnGroup type="footer">
                                        <Row>
                                            <Column :footer="'Terdapat ' + dataSourceTrans.length + ' data.'"
                                                :colspan="columnTrans.length + 2" />
                                        </Row>
                                    </ColumnGroup>
                                </DataTable>
                            </VCard>
                        </div>
                        <div class="column is-12">
                            <VCard class="card-round-3">
                                <p class="title-c"> MAP AKUN/JURNAL {{ item.tittlegridmap ? item.tittlegridmap : '' }}</p>

                                <DataTable v-model:filters="filtersMap" :value="dataSourceMap" paginator :rows="10"
                                    dataKey="id" filterDisplay="row" :rowsPerPageOptions="[5, 10, 25, 100]"
                                    :globalFilterFields="['jenistransaksi', 'namaproduk', 'nmdebet', 'nmkredit', 'kddebet', 'kdkredit']"
                                    :class="`p-datatable-small`">
                                    <template #header>

                                        <div class="">
                                            <VIconButton type="button" icon="feather:plus" class="mr-3" color="success"
                                                circle raised v-tooltip-prime="'Tambah'" @click="addMap()">
                                            </VIconButton>
                                            <span class="p-input-icon-left is-pulled-right">
                                                <InputText v-model="filtersMap['global'].value" placeholder="Search"
                                                    style="width:500px" />
                                            </span>
                                        </div>
                                    </template>
                                    <template #empty style="text-align: center;"> No data found. </template>
                                    <Column :exportable="false" header="Mapping" style="width:30px">
                                        <template #body="slotProps">
                                            <VIconButton type="button" icon="feather:edit" class="mr-3" color="warning"
                                                circle outlined raised v-tooltip-prime="'Edit'"
                                                @click="editMap(slotProps.data)" :loading="slotProps.data.isLoading">
                                            </VIconButton>
                                            <VIconButton type="button" icon="feather:trash" class="mr-3" color="danger"
                                                circle outlined raised v-tooltip-prime="'Hapus'"
                                                @click="disMap(slotProps.data)" :loading="slotProps.data.loadingHapus">
                                            </VIconButton>
                                        </template>
                                    </Column>
                                    <Column v-for="col in columnDataMapCOA" :field="col.field" :header="col.title"
                                        :style="'width:' + col.width">
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
                                            <Column :footer="'Terdapat ' + dataSourceMap.length + ' data.'"
                                                :colspan="columnDataMapCOA.length + 1" />
                                        </Row>
                                    </ColumnGroup>
                                </DataTable>

                            </VCard>
                        </div>
                    </div>
                </div>
            </VCard>
        </div>
        <Sidebar v-model:visible="modalFilter" header="Filter" position="right" :style="{ width: '20vw' }">
            <div class="columns is-multiline">
                <div class="column is-12">
                    <VField label="Jenis Pembiayaan" class="is-rounded-select is-autocomplete-select
                              mt-0 pt-0" v-slot="{ id }">
                        <VControl icon="fas fa-users" fullwidth class="prime-auto-select">
                            <MultiSelect v-model="item.qKelompok" display="chip" :options="d_KelompokPasien"
                                optionLabel="kelompokpasien" placeholder="Jenis Pembiayaan" optionValue="id"
                                class="is-rounded w-100" :maxSelectedLabels="3" />

                        </VControl>
                    </VField>
                </div>
                <div class="column is-12">
                    <VField label="Instalasi" class="is-rounded-select is-autocomplete-select
                              mt-0 pt-0" v-slot="{ id }">
                        <VControl icon="fas fa-archway" fullwidth class="prime-auto-select">
                            <Dropdown v-model="item.qInstalasi" :options="d_Departemen" :optionLabel="'namadepartemen'"
                                class="is-rounded" placeholder="Instalasi" style="width: 100%;" :filter="true"
                                @change="changeInst($event)" showClear />
                        </VControl>
                    </VField>
                </div>
                <div class="column is-12">
                    <VField label="Ruangan" class="is-rounded-select is-autocomplete-select
                              mt-0 pt-0" v-slot="{ id }">
                        <VControl icon="fas fa-home" fullwidth class="prime-auto-select">
                            <Dropdown v-model="item.qRuangan" :options="d_Ruangan" :optionLabel="'namaruangan'"
                                class="is-rounded" placeholder="Ruangan" style="width: 100%;" :filter="true" showClear />
                        </VControl>
                    </VField>
                </div>

                <div class="column is-12 ">
                    <VButton type="button" rounded outlined color="primary" raised icon="feather:filter"
                        class="ml-3 is-pulled-right" :loading="isLoading" @click="terapkanFilter()"> Terapkan
                    </VButton>
                    <VButton icon="lnir lnir-arrow-left rem-100" class="is-pulled-right" light dark-outlined
                        @click="clearFilter()">
                        Bersihkan
                    </VButton>
                </div>
            </div>
        </Sidebar>
        <Dialog v-model:visible="modalJurnal" modal
            :header="'Entry Jurnal' + (item.tittlegridmap ? item.tittlegridmap : '')" :style="{ width: '70vw' }">
            <div class="columns is-multiline">
                <div class="column is-6">
                    <VCard class="card-round-4">
                        <p class="title-c"> DEBIT</p>

                        <DataTable v-model:filters="filtersCoaDebet" :value="dataSourceCoaDebet" paginator :rows="5"
                            dataKey="id" filterDisplay="row" :rowsPerPageOptions="[5, 10, 25, 100]"
                            :globalFilterFields="['namaaccount', 'kdaccount']" :class="`p-datatable-small`">
                            <template #header>
                                <div class="flex justify-content-end">
                                    <span class="p-input-icon-left ">
                                        <InputText v-model="filtersCoaDebet['global'].value" placeholder="Search" />
                                    </span>
                                </div>
                            </template>
                            <template #empty style="text-align: center;"> No data found. </template>
                            <Column :exportable="false" header="#" style="width:30px">
                                <template #body="slotProps">
                                    <VIconButton type="button" icon="feather:check" class="mr-3" color="warning" circle
                                        outlined raised v-tooltip-prime="'Pilih'" @click="klikDebet(slotProps.data)"
                                        :loading="slotProps.data.isLoading">
                                    </VIconButton>
                                </template>
                            </Column>
                            <Column v-for="col in columnCoaDebet"
                                :field="col.template ? H.formatRupiah(col.field, '') : col.field" :header="col.title"
                                :style="'width:' + col.width"></Column>
                            <ColumnGroup type="footer">
                                <Row>
                                    <Column :footer="'Terdapat ' + dataSourceCoaDebet.length + ' data.'"
                                        :colspan="columnCoaDebet.length + 1" />
                                </Row>
                            </ColumnGroup>
                        </DataTable>
                        <div class="columns is-multiline mb-2">
                            <div class="column is-2 mt-2">
                                <VField>
                                    <VLabel>Junal Debit</VLabel>
                                </VField>
                            </div>
                            <div class="column is-4">
                                <VField>
                                    <VControl icon="feather:arrow-left">
                                        <input v-model="item.coaNoDebet" type="text" class="input is-rounded"
                                            placeholder="Kode" />
                                    </VControl>
                                </VField>
                            </div>
                            <div class="column is-6">
                                <VField>
                                    <VControl icon="feather:arrow-right">
                                        <input v-model="item.coaNmDebet" type="text" class="input is-rounded"
                                            placeholder="Nama Akun" />
                                    </VControl>
                                </VField>
                            </div>
                        </div>
                    </VCard>
                </div>
                <div class="column is-6">
                    <VCard class="card-round-5">
                        <p class="title-c"> KREDIT</p>
                        <DataTable v-model:filters="filtersCoaKredit" :value="dataSourceCoaKredit" paginator :rows="5"
                            dataKey="id" filterDisplay="row" :rowsPerPageOptions="[5, 10, 25, 100]"
                            :globalFilterFields="['namaaccount', 'kdaccount']" :class="`p-datatable-small`" :size="'small'">
                            <template #header>
                                <div class="flex justify-content-end">
                                    <span class="p-input-icon-left ">
                                        <InputText v-model="filtersCoaKredit['global'].value" placeholder="Search" />
                                    </span>
                                </div>
                            </template>
                            <template #empty style="text-align: center;"> No data found. </template>
                            <Column :exportable="false" header="#" style="width:30px">
                                <template #body="slotProps">
                                    <VIconButton type="button" icon="feather:check" class="mr-3" color="warning" circle
                                        outlined raised v-tooltip-prime="'Pilih'" @click="klikKredit(slotProps.data)"
                                        :loading="slotProps.data.isLoading">
                                    </VIconButton>
                                </template>
                            </Column>
                            <Column v-for="col in columnCoaKredit"
                                :field="col.template ? H.formatRupiah(col.field, '') : col.field" :header="col.title"
                                :style="'width:' + col.width"></Column>
                            <ColumnGroup type="footer">
                                <Row>
                                    <Column :footer="'Terdapat ' + dataSourceCoaKredit.length + ' data.'"
                                        :colspan="columnCoaKredit.length + 1" />
                                </Row>
                            </ColumnGroup>
                        </DataTable>
                        <div class="columns is-multiline mb-2">
                            <div class="column is-2 mt-2">
                                <VField>
                                    <VLabel>Junal Kredit</VLabel>
                                </VField>
                            </div>
                            <div class="column is-4">
                                <VField>
                                    <VControl icon="feather:arrow-left">
                                        <input v-model="item.coaNoKredit" type="text" class="input is-rounded"
                                            placeholder="Kode" v-on:keyup.enter="SearchEnterKredit()" />
                                    </VControl>
                                </VField>
                            </div>
                            <div class="column is-6">
                                <VField>
                                    <VControl icon="feather:arrow-right">
                                        <input v-model="item.coaNmKredit" type="text" class="input is-rounded"
                                            placeholder="Nama Akun" v-on:keyup.enter="SearchEnterKredit()" />
                                    </VControl>
                                </VField>
                            </div>
                        </div>
                    </VCard>
                </div>
                <div class="column is-4">
                    <VField label="Jenis Jurnal" class="is-rounded-select is-autocomplete-select
                              mt-0 pt-0" v-slot="{ id }">
                        <VControl icon="fas fa-book" fullwidth class="prime-auto-select">
                            <Dropdown v-model="item.jenisjurnal" :options="d_JenisJurnal" :optionLabel="'jenistransaksi'"
                                class="is-rounded" placeholder="Jenis Jurnal" style="width: 100%;" :filter="true"
                                showClear />
                        </VControl>
                    </VField>
                </div>
                <div class="column is-4">
                    <VField label="Instalasi" class="is-rounded-select is-autocomplete-select
                              mt-0 pt-0" v-slot="{ id }">
                        <VControl icon="fas fa-archway" fullwidth class="prime-auto-select">
                            <Dropdown v-model="item.departemen" :options="d_Departemen" :optionLabel="'namadepartemen'"
                                class="is-rounded" placeholder="Instalasi" style="width: 100%;" :filter="true"
                                @change="changeInst($event)" showClear />
                        </VControl>
                    </VField>
                </div>
                <div class="column is-4">
                    <VField label="Ruangan" class="is-rounded-select is-autocomplete-select
                              mt-0 pt-0" v-slot="{ id }">
                        <VControl icon="fas fa-home" fullwidth class="prime-auto-select">
                            <Dropdown v-model="item.jenisjurnal" :options="d_Ruangan" :optionLabel="'namaruangan'"
                                class="is-rounded" placeholder="Ruangan" style="width: 100%;" :filter="true" showClear />
                        </VControl>
                    </VField>
                </div>
                <div class="column is-4">
                    <VField label="Kelompok Pasien" class="is-rounded-select is-autocomplete-select
                              mt-0 pt-0" v-slot="{ id }">
                        <VControl icon="fas fa-pie-chart" fullwidth class="prime-auto-select">
                            <Dropdown v-model="item.kelompokPasien" :options="d_KelompokPasien"
                                :optionLabel="'kelompokpasien'" class="is-rounded" placeholder="Kelompok Pasien"
                                style="width: 100%;" :filter="true" showClear />
                        </VControl>
                    </VField>
                </div>
                <div class="column is-4">
                    <VField label="Rekanan" class="is-rounded-select is-autocomplete-select
                              mt-0 pt-0" v-slot="{ id }">
                        <VControl icon="fa fa-line-chart" fullwidth class="prime-auto-select">

                            <AutoComplete v-model="item.rekanan" :suggestions="d_Rekanan" @complete="fetchRekanan($event)"
                                :optionLabel="'namarekanan'" :dropdown="true" :minLength="3" :appendTo="'body'"
                                class="is-rounded" :loadingIcon="'pi pi-spinner'" :field="'namarekanan'"
                                placeholder="ketik Rekanan" showClear />
                        </VControl>
                    </VField>
                </div>
                <div class="column is-4">
                    <VField label="Cara Bayar" class="is-rounded-select is-autocomplete-select
                              mt-0 pt-0" v-slot="{ id }">
                        <VControl icon="fas fa-calculator" fullwidth class="prime-auto-select">
                            <Dropdown v-model="item.caraBayar" :options="d_Carabayar" :optionLabel="'carabayar'"
                                class="is-rounded" placeholder="Cara Bayar" style="width: 100%;" :filter="true" showClear />
                        </VControl>
                    </VField>
                </div>

            </div>
            <template #footer>
                <VButton icon="lnir lnir-arrow-left rem-100" light dark-outlined @click="modalJurnal = false">
                    Batal
                </VButton>
                <VButton type="button" rounded outlined color="primary" raised icon="feather:save" :loading="isLoading"
                    @click="saveSetting()"> Simpan
                </VButton>
            </template>
        </Dialog>
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
import AutoComplete from 'primevue/autocomplete';
const title = 'Tagihan Non Layanan Detail Jurnal'
useHead({
    title: title + import.meta.env.VITE_PROJECT,
})
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)
const jmlFilter: any = ref(0)
const modalFilter: any = ref(false)
const isLoading: any = ref(false)
const route = useRoute()
const router = useRouter()
const dataSourceReg: any = ref([])
const dataSourceTrans: any = ref([])
const dataSourceMap: any = ref([])
const dataSourceCoaDebet: any = ref([])
const dataSourceCoaKredit: any = ref([])
const modalJurnal: any = ref(false)
const selectedRegis: any = ref({})
const filters = ref({
    global: { value: null, matchMode: FilterMatchMode.CONTAINS },
});
const filtersTrans = ref({
    global: { value: null, matchMode: FilterMatchMode.CONTAINS },
});
const filtersMap = ref({
    global: { value: null, matchMode: FilterMatchMode.CONTAINS },
});
const filtersCoaKredit = ref({
    global: { value: null, matchMode: FilterMatchMode.CONTAINS },
});
const filtersCoaDebet = ref({
    global: { value: null, matchMode: FilterMatchMode.CONTAINS },
});
const d_KelompokPasien: any = ref([])
const d_Departemen: any = ref([])
const d_Ruangan: any = ref([])
const d_Carabayar: any = ref([])
const d_DetailJenis: any = ref([])
const d_JenisJurnal: any = ref([])
const d_Rekanan: any = ref(false)
const item: any = reactive({
    qFilterTgl: {
        start: new Date(),
        end: new Date()
    },
})
const currentPage: any = ref({
    limit: 20
})

const columnTrans: any = [
    {
        "field": "tglstruk",
        "title": "Tgl Struk",
        "width": "50px"
    },
    {
        "field": "noregistrasi",
        "title": "NoRegistrasi",
        "width": "50px"
    },
    {
        "field": "namapasien_klien",
        "title": "Pelanggan",
        "width": "80px"
    },
    {
        "field": "namaproduk",
        "title": "Produk",
        "width": "50px"
    },
    {
        "field": "satuanstandar",
        "title": "Satuan",
        "width": "50px"
    },
    {
        "field": "namaruangan",
        "title": "Ruangan",
        "width": "50px"
    },
    {
        "field": "hargatotal",
        "title": "Total Tagihan",
        "width": "50px",
        "template": "<span class='style-right'>{{formatRupiah('#: hargatotal #', '')}}</span>"
    },
    {
        "field": "nojurnal_intern",
        "title": "NoJurnal",
        "width": "50px",
        "tag": true
    },
    {
        "field": "jurnal",
        "title": "Jurnal",
        "width": "120px"
    },

];
const columnDataMapCOA: any = [
    {
        "field": "jenistransaksi",
        "title": "Jenis",
        "width": "50px"
    },
    {
        "field": "kddebet",
        "title": "KdDebet",
        "width": "50px"
    },
    {
        "field": "nmdebet",
        "title": "Nm Debet",
        "width": "50px"
    },
    {
        "field": "kdkredit",
        "title": "KdKredit",
        "width": "50px"
    },
    {
        "field": "nmkredit",
        "title": "NmKredit",
        "width": "50px"
    },
    {
        "field": "namadepartemen",
        "title": "Instalasi",
        "width": "50px"
    },
    {
        "field": "namaruangan",
        "title": "Ruangan",
        "width": "50px"
    },
    {
        "field": "namaproduk",
        "title": "Pelayanan",
        "width": "50px"
    },
    {
        "field": "namarekanan",
        "title": "Rekanan",
        "width": "50px"
    },
    {
        "field": "kelompokpasien",
        "title": "KelompokPasien",
        "width": "50px"
    },
    {
        "field": "carabayar",
        "title": "Carabayar",
        "width": "50px"
    },
    {
        "field": "bank",
        "title": "Bank",
        "width": "50px"
    }
];
const columnCoaDebet: any = [
    {
        "field": "id",
        "title": "ID",
        "width": "30px"
    },
    {
        "field": "kdaccount",
        "title": "Kode",
        "width": "80px"
    },
    {
        "field": "namaaccount",
        "title": "Nama Akun",
        "width": "150px"
    }
];
const columnCoaKredit: any = [
    {
        "field": "id",
        "title": "ID",
        "width": "30px"
    },
    {
        "field": "kdaccount",
        "title": "Kode",
        "width": "80px"
    },
    {
        "field": "namaaccount",
        "title": "Nama Akun",
        "width": "150px"
    }
];
const fetchDropdown = async () => {
    await useApi().get('/akuntansi/get-data-combo-map-coa').then((r) => {
        d_Departemen.value = r.departemen
        d_KelompokPasien.value = r.kelompokpasien
        d_Carabayar.value = r.carabayar
        d_DetailJenis.value = r.detailjenisproduk
        d_JenisJurnal.value = r.jenistrxjurnal
        dataSourceCoaKredit.value = r.coa
        dataSourceCoaDebet.value = r.coa
    })
}
const changeInst = (e: any) => {
    d_Ruangan.value = e.value ? e.value.ruangan : []
}
const klikDebet = (e: any) => {
    item.coaIdDebet = e.id
    item.coaNoDebet = e.kdaccount
    item.coaNmDebet = e.namaaccount
}
const klikKredit = (e: any) => {
    item.coaIdKredit = e.id
    item.coaNoKredit = e.kdaccount
    item.coaNmKredit = e.namaaccount
}
const fetchData = async () => {
    let limit: any = currentPage.value.limit
    let page: any = route.query.page ? route.query.page : 1

    let dari = ''
    if (item.qFilterTgl) {
        dari = H.formatDate(item.qFilterTgl.start, 'YYYY-MM-DD 00:00:00')
    }
    let sampai = ''
    if (item.qFilterTgl) {
        sampai = H.formatDate(item.qFilterTgl.end, 'YYYY-MM-DD 23:59:59')
    }
    let namapasien = ''
        , nocm = ''
        , search = ''
        , inacbg_status = ''
        , kelompok = ''
        , inst = ''
        , ruang = ''
        , statusPasien = ''


    if (item.filter) {
        search = item.filter
    }
    jmlFilter.value = 0

    if (item.qKelompok && item.qKelompok.length) {
        kelompok = item.qKelompok.join(',')
        jmlFilter.value += 1
    }
    if (item.qRuangan) {
        ruang = item.qRuangan.id
        jmlFilter.value += 1
    }
    if (item.qInstalasi) {
        inst = item.qInstalasi.id
        jmlFilter.value += 1
    }
    isLoading.value = true
    const response = await useApi().get(
        '/akuntansi/get-detail-nonlayanan?tglAwal=' + dari
        + '&tglAkhir=' + sampai
        + '&nostruk=' + search
        + '&deptId=' + inst
        + '&ruangId=' + ruang
        + '&kelId=' + kelompok

    )
    isLoading.value = false

    dataSourceTrans.value = response

    let c_set = {
        0: dari,
        1: sampai,
    }
    H.cacheHelper().set('c_junralpelayanan_supob', c_set);
    // set page to 1
    route.query.page = 1
}
const loadPelayanan = async (e: any) => {
    selectedRegis.value = e
    e.isLoading = true
    item.tittlegridtransaksi = ' : ' + e.noregistrasi + ', ' + e.namapasien + ', ' + e.kelompokpasien;
    item.objectNoregistrasi = e.noregistrasi;
    item.objectNamaPasien = e.namapasien;
    const response = await useApi().get("/akuntansi/get-detail-pelayanan-pasien-by-noregistrasi?noregistrasi=" + e.noregistrasi + "&idproduk=")
    e.isLoading = false
    dataSourceTrans.value = response
}
const loadMap = async (dataItem) => {

   item.tittlegridmap = ' : ' + dataItem.namaproduk
   item.objectprodukfkPilih = dataItem.objectprodukfk;


    dataItem.isLoading = true
    await loadDataMapJurnal()
    dataItem.isLoading = false
}
const loadDataMapJurnal = async () => {
    const response = await useApi().get("/akuntansi/get-detail-map-coa-by-produkid?produkid=" + item.objectprodukfkPilih)
    dataSourceMap.value = response
}
const fetchRekanan = async (filter: any) => {
    let query = ''
    if (filter) {
        query = filter.query
    }
    const response = await useApi().get(`/akuntansi/get-datacombo-rekanan?name= ${query}&limit=10`)
    d_Rekanan.value = response
}
const addMap = () => {
    item.coaNorec = undefined;
    if (!item.objectprodukfkPilih) {
        H.alert('error', 'Pilih Produk')
        return
    }
    modalJurnal.value = true
}
const editMap = (e: any) => {
    item.coaNorec = e.norec;

    item.coaIdDebet = e.objectcoadebetfk;
    item.coaNoDebet = e.kddebet;
    item.coaNmDebet = e.nmdebet;

    item.coaIdKredit = e.objectcoakreditfk;
    item.coaNoKredit = e.kdkredit;
    item.coaNmKredit = e.nmkredit;

    if (e.objectjenistrxfk) {
        d_JenisJurnal.value.forEach(element => {
            if (element.id == e.objectjenistrxfk) {
                item.jenisjurnal = element
                return
            }
        });
    }
    if (e.objectdepartemenfk) {
        d_Departemen.value.forEach(element => {
            if (element.id == e.objectdepartemenfk) {
                item.departemen = element
                return
            }
        });
    }
    if (e.objectruanganfk) {
        d_Ruangan.value.forEach(element => {
            if (element.id == e.objectruanganfk) {
                item.ruangan = element
                return
            }
        });
    }
    if (e.objectkelompokpasienfk) {
        d_KelompokPasien.value.forEach(element => {
            if (element.id == e.objectkelompokpasienfk) {
                item.kelompokPasien = element
                return
            }
        });
    }
    if (e.objectcarabayarfk) {
        d_Carabayar.value.forEach(element => {
            if (element.id == e.objectcarabayarfk) {
                item.caraBayar = element
                return
            }
        });
    }

    modalJurnal.value = true
}
const disMap = async (e: any) => {
    let objSave = {
        "norec": e.norec == undefined ? '-' : e.norec
    };
    e.loadingHapus = true
    const response = await useApi().post("/akuntansi/hapus-map-jurnal", objSave)
    e.loadingHapus = false
    loadDataMapJurnal()

}
const updateJurnal = async (e: any) => {
    
    e.isLoading2 = true
    var objSave = {
        "tglAwal": e.tglstruk.substr(0,10) + ' 00:00:00',
        "tglAkhir":e.tglstruk.substr(0,10) + ' 23:59:59',
        "nostruk":e.nostruk
    };

    await useApi().post("/general/save-jurnal-pelayananpasien_tob", objSave)
        .then((r) => {
            e.isLoading2 = false
            fetchData()
        })
        .catch((xx => {
            e.isLoading2 = false
        }))

}
const terapkanFilter = () => {
    fetchData()
    modalFilter.value = false
}
const backPage = () => {
    window.history.back()
}
const saveSetting = async () => {
    if (item.coaIdDebet == undefined) {
        H.alert('error', "Jurnal Debet belum dipilih");
        return;
    }
    if (item.coaIdKredit == undefined) {
        H.alert('error', "Jurnal Kredit belum dipilih");
        return;
    }


    let objSave = {
        "norec": item.coaNorec == undefined ? '-' : item.coaNorec,
        "objectjenistrxfk": item.jenisjurnal == undefined ? null : item.jenisjurnal.id,
        "objectcoadebetfk": item.coaIdDebet == undefined ? null : item.coaIdDebet,
        "objectcoakreditfk": item.coaIdKredit == undefined ? null : item.coaIdKredit,
        "objectdepartemenfk": item.departemen == undefined ? null : item.departemen.id,
        "objectruanganfk": item.ruangan == undefined ? null : item.ruangan.id,
        "objectkelompokpasienfk": item.kelompokPasien == undefined ? null : item.kelompokPasien.id,
        "objectrekananfk": item.rekanan == undefined ? null : item.rekanan.id,
        "objectprodukfk":item.objectprodukfkPilih == undefined ? null : item.objectprodukfkPilih,
    };
    isLoading.value = true
    await useApi().post("/akuntansi/save-map-jurnal", objSave)
        .then((res) => {
            isLoading.value = false
            loadDataMapJurnal()
            clearMap()
        })
        .catch((e => {
            isLoading.value = false
        }))

}
const clearMap = ()=>{
    item.coaNorec = undefined
    item.jenisjurnal = undefined
    item.coaIdDebet = undefined
    item.coaIdKredit = undefined
    item.departemen = undefined
    item.ruangan = undefined
    item.kelompokPasien = undefined
    item.objectprodukfkygdipilih = undefined
}
let c = H.cacheHelper().get('c_junralpelayanan_supob');
if (c != undefined) {
    item.qFilterTgl.start = new Date(c[0]);
    item.qFilterTgl.end = new Date(c[1]);
}
currentPage.value.page = computed(() => {
    try {
        return Number.parseInt(route.query.page as string) || 1
    } catch { }
    return 1
})
watch(
    () => currentPage.value.page,
    (newValue, oldValue) => {
        if (newValue != oldValue) {
            fetchData()
        }
    }
)
watch(
    () => currentPage.value.limit,
    (newValue, oldValue) => {
        if (newValue != oldValue) {
            fetchData()
        }
    }
)

fetchDropdown()
</script>
<style lang="scss"></style>
