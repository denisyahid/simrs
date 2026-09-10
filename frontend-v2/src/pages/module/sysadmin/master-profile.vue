<template>
    <section>
        <div class="column is-12">
            <VCard>
                <div class="column c-title pt-2 mb-0">
                    <div class="column is-10 p-0">
                        <label class="title-page">Profile</label>
                        <label for="">PROFILE LENGKAP RUMAH SAKIT</label>
                    </div>
                </div>

                <VPlaceload height="20rem" width="100%" class="mx-2 mt-4" v-if="loadData" />
                <DataTable v-else :rows="5" :value="dataSource" :loading="loadSearch" :rowsPerPageOptions="[5, 10, 15]"
                    class="p-datatable-sm mt-4" breakpoint="960px" selectionMode="single" sortMode="multiple" showGridlines
                    tableStyle="min-width: 30rem"
                    paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
                    paginator currentPageReportTemplate="Showing {first} to {last} of {totalRecords}">
                    <Column field="id" header="NO KODE RS" />
                    <Column field="namalengkap" header="NAMA RUMAH SAKIR" />
                    <Column field="reportdisplay" header="JENIS RS" />
                    <Column field="jumlahtersedia" header="KELAS RS" />
                    <Column field="jumlahfortersedia" header="KOTA / KABUPATEN" />
                    <Column field="jumlahfortersedia" header="KODE POS" />
                    <Column field="jumlahfortersedia" header="TELEPON" />
                    <Column field="jumlahfortersedia" header="FAX" />
                    <Column field="jumlahfortersedia" header="EMAIL" />
                    <Column field="jumlahfortersedia" header="WEBSITE" />
                    <Column header="ACTION"  :exportable="false" style="text-align: center;">
                         <template #body="slotProps">
                            <VButtons>
                            <VIconButton type="button" icon="fas fa-ellipsis-v" class="mr-2" color="warning" circle outlined
                                raised v-tooltip.top="'EDIT'" @click="showEdit()">
                            </VIconButton>
                            <VIconButton type="button" icon="fas fa-ellipsis-v" class="mr-2" color="primary" circle outlined
                                raised v-tooltip.top="'DETAIL'" @click="showDetail()">
                            </VIconButton>
                        </VButtons>
                        </template>
                    </Column>
                </DataTable>
            </VCard>
        </div>
    </section>
</template>
<script  setup lang="ts">
import { useApi } from '/@src/composable/useApi'
import { ref, reactive } from 'vue'
import { useConfirm } from 'primevue/useconfirm'
import { useHead } from '@vueuse/head'
import DataTable from 'primevue/datatable'
import Dropdown from 'primevue/dropdown';
import AutoComplete from 'primevue/autocomplete';
import ColumnGroup from 'primevue/columngroup';
import Row from 'primevue/row';
import * as XLSX from "xlsx";
import * as XLSXStyle from 'xlsx-js-style';
import * as H from '/@src/utils/appHelper'
import Column from 'primevue/column'
import { useViewWrapper } from '/@src/stores/viewWrapper'

// app.directive('tooltip', Tooltip);

useHead({
    title: 'Laporan Kegiatan Radiologi - ' + import.meta.env.VITE_PROJECT,
})

useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)

const item: any = ref({
    aktif: true,
    isKK: false,
    tglPelayanan: new Date(),
    filterTgl: reactive({
        start: new Date(),
        end: new Date(),
    }),
})

const confirm = useConfirm()
const dataSource: any = ref([])
const modalDetail: any = ref(false)
const modalEdit: any = ref(false)
let d_KelompokPasien: any = ref([])
let loadSearch: any = ref(false)
let loadData: any = ref(true)
let loadSave: any = ref(false)
let isLoading: any = ref(false)

const fetchData = async () => {

    item.value.Ttotal = 0
    loadSearch.value = true
    await useApi().get(`sysadmin/get-profile`).then((response) => {
        dataSource.value = response
    })
    loadData.value = false
    loadSearch.value = false
}


const showDetail = ()=>{
    
}

const showEdit = ()=>{
    
}

fetchData()

</script>
<style lang="scss">
@import '/@src/scss/abstracts/all';

@import '/@src/scss/components/forms-outer';
@import '/@src/scss/custom/config';
@import '/@src/scss/module/sysadmin/master-data.scss';

.title-page {
    position: relative;
    font-size: 17px;
    display: block;
    margin-bottom: 3px;
    margin-top: 0px;
    font-weight: 600;
}

.btn-search {
    display: flex;
    align-items: center;
}
</style>
