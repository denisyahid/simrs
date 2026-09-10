<template>
    <section>
        <div class="column is-12">
            <VCard>
                <div class="column c-title pt-2 mb-0">
                    <div class="column is-10 p-0">
                        <label class="title-page">Map Kelompok Laporan</label>
                    </div>
                </div>

                <div class="columns is-multiline pt-5">
                    <div class="column is-6">
                        <VCard class="card-round-1 pt-2">
                            <div class="column is-10 p-0 mb-3">
                                <label class="title-page">List Laporan</label>
                            </div>
                            <div class="column is-12">
                                <VField class="is-rounded-select is-autocomplete-select">
                                    <VLabel class="required-field">Jenis Laporan</VLabel>
                                    <VControl icon="feather:search" fullwidth class="prime-auto-select">
                                        <Dropdown v-model="item.jenislaporan" :options="d_JenisLaporan" optionLabel="label"
                                            placeholder="Pilih Jenis Laporan" style="width: 100%;" :filter="true"
                                            @change="fetchMapping(item.jenislaporan)" />
                                    </VControl>
                                </VField>
                            </div>

                            <div class="column is-12">
                                <DataTable :value="dataSource" :paginator="true" :rows="5"
                                    :rowsPerPageOptions="[5, 10, 25]" :loading="isLoadingSource" class="p-datatable-sm"
                                    paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
                                    responsiveLayout="stack" breakpoint="960px" sortMode="multiple"
                                    currentPageReportTemplate="Showing {first} to {last} of {totalRecords}" showGridlines>

                                    <Column field="no" header="#"></Column>
                                    <Column field="kelompoklaporan" header="Kelompok Laporan" :sortable="true" />
                                    <Column :exportable="false" header="Action" style="text-align: center;">
                                        <template #body="slotProps">
                                            <VIconButton type="button" icon="fas fa-arrow-right" class="mr-3" color="info" circle
                                                outlined raised @click="fetchDetail(slotProps.data)">
                                            </VIconButton>
                                        </template>
                                    </Column>
                                </DataTable>
                            </div>
                        </VCard>
                    </div>
                    <div class="column is-6">
                        <VCard class="card-round-3 pt-2">
                            <div class="column is-10 p-0">
                                <label class="title-page">List Mapping</label>
                            </div>

                            <div class="columns is-multiline pt-4 pl-3">
                                <div class="column is-6">
                                    <VField class="is-autocomplete-select" label="Produk">
                                        <VControl icon="feather:search">
                                            <AutoComplete v-model="item.produk" :suggestions="d_Produk"
                                                @complete="fetchProduk($event)" :optionLabel="'label'" :dropdown="true"
                                                :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                                                :field="'label'" placeholder="Cari Nama Produk" />
                                        </VControl>
                                    </VField>
                                </div>
                                <div class="column is-6">
                                    <VField class="is-rounded-select is-autocomplete-select required-vfield" label="Ruangan Asal">
                                        <VControl icon="feather:search" class="prime-auto-select">
                                        <AutoComplete v-model="item.ruangan" :suggestions="d_Ruangan" @complete="fetchRuangan($event)"
                                            :optionLabel="'namaruangan'" :dropdown="true" :minLength="3" :appendTo="'body'"
                                            :loadingIcon="'pi pi-spinner'" :field="'namaruangan'" placeholder=" Cari Nama Ruangan" />
                                        </VControl>
                                    </VField>
                                </div>
                                <div class="column is-4">
                                    <VField class="is-rounded-select is-autocomplete-select">
                                        <VLabel class="required-field">Konten</VLabel>
                                        <VControl icon="feather:search" fullwidth class="prime-auto-select">
                                            <Dropdown v-model="item.kelompoklaporan" :options="d_KelompokLaporan"
                                                optionLabel="label" placeholder="Pilih Kelompok Laporan"
                                                style="width: 100%;" :filter="true" />
                                        </VControl>
                                    </VField>
                                </div>
                                <div class="column mt-4 pt-5 ml-4">
                                    <VButton color="primary" @click="save()" :loading="isLoadSave"><i class="fas fa-plus-circle mr-3" aria-hidden="true" ></i>Tambah</VButton>
                                </div>
                            </div>
                            <div class="column is-12 pt-0">
                                <DataTable :value="dataSourceMapping" :paginator="true" :rows="5"
                                    :rowsPerPageOptions="[5, 10, 25]" :loading="isLoadingData" class="p-datatable-sm"
                                    paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
                                    responsiveLayout="stack" breakpoint="960px" sortMode="multiple"
                                    currentPageReportTemplate="Showing {first} to {last} of {totalRecords}" showGridlines>

                                    <Column field="no" header="#"></Column>
                                    <Column field="idproduk" header="Kode Produk" :sortable="true" />
                                    <Column field="namaproduk" header="Nama Produk" :sortable="true" />
                                    <Column field="namaruangan" header="Nama Ruangan" :sortable="true" />
                                    <Column :exportable="false" header="Action" style="text-align: center;">
                                        <template #body="slotProps">
                                            <VIconButton type="button" icon="fas fa-pencil-alt" class="mr-3" color="info" circle
                                                outlined raised v-tooltip.top="'Edit'" @click="Edit(slotProps.data)">
                                            </VIconButton>
                                            <VIconButton type="button" icon="fas fa-trash-alt" class="mr-3" color="danger" circle
                                                outlined raised v-tooltip.top="'Hapus'" @click="hapus(slotProps.data)">
                                            </VIconButton>
                                        </template>
                                    </Column>
                                </DataTable>
                            </div>
                        </VCard>
                    </div>
                </div>

                <!-- <VPlaceload height="20rem" width="100%" class="mx-2 mt-4" v-if="loadData" /> -->
                <!-- <DataTable v-else :rows="5" :value="dataSource" :loading="loadSearch" :rowsPerPageOptions="[5, 10, 15]"
                    class="p-datatable-sm mt-4" breakpoint="960px" selectionMode="single" sortMode="multiple" showGridlines
                    tableStyle="min-width: 30rem"
                    paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
                    paginator currentPageReportTemplate="Showing {first} to {last} of {totalRecords}">
                    <template #header>
                        <div class="columns is-multiline">
                            <div class="column is-2" style="margin-top:23px">
                                <VButton color="primary" @click="exportExcel()" outlined icon="fas fa-file-excel">
                                    Export To Excel
                                </VButton>
                            </div>
                            <div class="column is-10">
                                <div class="columns is-multiline" style="justify-content: right;">
                                    <div class="column is-4">
                                        <VField label="Periode" style="margin-bottom: 6px;" />
                                        <VDatePicker v-model="item.filterTgl" is-range color="pink" trim-weeks>
                                            <template #default="{ inputValue, inputEvents }">
                                                <VField addons>
                                                    <VControl icon="feather:calendar">
                                                        <VInput :value="inputValue.start" v-on="inputEvents.start" />
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
                                    </div>
                                     <div class="column is-1 btn-search mt-3" style="justify-content:center">
                                        <VIconButton color="success" icon="fas fa-search" @click="fetchData"
                                            :loading="loadSearch" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </template>
                    <Column field="koders" header="Kode RS" />
                    <Column field="kodeprov" header="Kode Provinsi" />
                    <Column field="kota" header="Kab / Kota" />
                    <Column field="tahun" header="Tahun"/>
                    <Column field="bor" header="BOR" />
                    <Column field="alos" header="LOS" />
                    <Column field="bto" header="BTO" />
                    <Column field="toi" header="TOI" />
                    <Column field="ndr" header="NDR" />
                    <Column field="gdr" header="GDR" />
                    <Column field="ratarataperhari" header="Rata-rata Kunjungan/Hari" />
                </DataTable> -->
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
import MultiSelect from 'primevue/multiselect';

// app.directive('tooltip', Tooltip);

useHead({
    title: 'Laporan Indikator Pelayanan Rumah Sakit - ' + import.meta.env.VITE_PROJECT,
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
const dataSourceMapping: any = ref([])
const modalDetail: any = ref(false)
const modalEdit: any = ref(false)
const d_Ruangan: any = ref([])
const d_RuanganRI: any = ref([])
const d_RuanganRJ: any = ref([])
let d_KelompokLaporan: any = ref([])
let d_JenisLaporan: any = ref([])
let d_Produk: any = ref([])
let loadSearch: any = ref(false)
let loadData: any = ref(true)
let isLoadSave: any = ref(false)
let isLoading: any = ref(false)
let isLoadingSource: any = ref(false)
let sourceRuangan: any = ref([])



const fetchCombo = async () => {

    let response = await useApi().get('sysadmin/get-combo-map-laporan')

    d_JenisLaporan.value = response.jenislaporan.map((e: any) => {
        return { label: e.jenislaporan, value: e.id }
    })

    d_KelompokLaporan.value = response.kelompoklaporan.map((e: any) => {
        return { label: e.kelompoklaporan, value: e.id }
    })
    d_Ruangan.value = response.ruangan.map((e: any) => {
        return { label: e.ruangan, value: e.id }
    })

}

const fetchProduk = async (filter: any) => {
    const response = await useApi().get(`/laporan/get-produk-mapping?namaproduk=${filter.query}`)
    d_Produk.value = response.map((e: any) => {
        return { label: e.namaproduk, value: e.idproduk }
    })
  
}

const fetchMapping = async (e: any) => {
    isLoadingSource.value = true
    let response = await useApi().get(`sysadmin/get-map-laporan-rl?idjenislaporan=${e.value}`)
    response.forEach((element,i) => {
        element.no = i + 1
    });
    dataSource.value = response
    isLoadingSource.value = false
}

const fetchDetail = async (e: any) => {
    // isLoadingSource.value = true
    let response = await useApi().get(`sysadmin/get-map-laporan-rl?idkonten=${e.idkelompoklaporan}`)
    response.forEach((element,i) => {
        element.no = i + 1
    });
    dataSourceMapping.value = response
    // isLoadingSource.value = false
}
const fetchRuangan = async (filter: any) => {
  const response = await useApi().get(`/emr/dropdown/ruangan_m?select=id,namaruangan&param_search=namaruangan&query=${filter.query}&limit=10`)
  d_Ruangan.value = response.map((e: any) => { return { id: e.value, namaruangan: e.label } })
}


const save = async () => {
    if (!item?.value?.jenislaporan) {
        H.alert('error', 'Jenis Laporan Wajib Diisi');
        return;
    }

    if (!item?.value?.kelompoklaporan) {
        H.alert('error', 'Kelompok Laporan Wajib Diisi');
        return;
    }

    if (!item?.value?.ruangan) {
        H.alert('error', 'Ruangan Wajib Diisi');
        return;
    }

    // Siapkan objek dengan pemeriksaan aman untuk setiap properti
    let objSave = {
        norec: item?.value?.norec || '',
        objectjenislaporanfk: item?.value?.jenislaporan?.value || null,
        objectkelompokkontenfk: item?.value?.kelompoklaporan?.value || null,
        produkfk: item?.value?.produk?.value || null,
        ruanganfk: item?.value?.ruangan?.id || null,
    };

    isLoadSave.value = true;

    try {
        await useApi().post('sysadmin/save-map-laporan-rl', objSave);
        isLoadSave.value = false;
        fetchMapping(item.value.jenislaporan);
    } catch (ex) {
        isLoadSave.value = false;
        console.error(ex); // Debug untuk mengetahui error
    }
};


const hapus = async (e: any) => {
    await useApi().post('sysadmin/delete-map-laporan-rl',{'norec' : e.norec})
}

const Edit = async(e:any) => {
    item.value.norec = e.norec
    item.value.produk = {label : e.namaproduk , value : e.idproduk}
    item.value.kelompoklaporan = {label : e.kelompoklaporan , value : e.idkelompoklaporan}
    // item.value.ruangan = {label : e.ruangan , value : e.ruanganid}
}

const clear = ()=>{
    delete item.value.norec
    delete item.value.produk
    delete item.value.kelompoklaporan
}

fetchCombo()

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
