<template>
    <section>
        <div class="columns is-multiline">
            <div class="column is-6">
                <VCard style="padding-bottom: 0px">
                    <div class="column c-title pt-2 mb-2">
                        <label class="title-page">List Pegawai</label>
                    </div>
                    <div class="columns is-multiline p-2">
                        <div class="column">
                            <span style="font-weight: 500;">Pegawai</span>
                            <VField class="is-autocomplete-select pt-3">
                                <VControl icon="feather:search">
                                    <AutoComplete v-model="item.pegawaifk" :suggestions="d_Pegawai"
                                        @complete="fetchPegawai($event)" :optionLabel="'label'" :dropdown="true"
                                        :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                                        placeholder="ketik Nama Perawat" />
                                </VControl>
                            </VField>
                        </div>
                    </div>

                    <div class="column p-0 mb-3">
                        <VPlaceload height="20rem" width="100%" class="mx-2" v-if="loadSearch" />
                        <DataTable v-else :rows="5" :value="dataSource" :rowsPerPageOptions="[5, 10, 15]"
                            class="p-datatable-sm" breakpoint="960px" selectionMode="single" sortMode="multiple"
                            showGridlines tableStyle="min-width: 30rem"
                            paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
                            paginator currentPageReportTemplate="Showing {first} to {last} of {totalRecords}">

                            <Column field="tglstruk" header="Nama Pegawai" />
                            <Column field="tglstruk" header="Jabatan" />
                            <Column field="tglstruk" header="Golongan" />
                        </DataTable>
                    </div>
                </VCard>
            </div>
            <div class="column is-6">
                <VCard style="padding-bottom: 0px">
                    <div class="column c-title pt-2 mb-2">
                        <label class="title-page">Map Jenis Pagu</label>
                    </div>
                    <div class="columns is-multiline p-2">
                        <div class="column">
                            <span style="font-weight: 500;">Jenis Pagu</span>
                            <VField class="is-autocomplete-select pt-3">
                                <VControl icon="feather:search" class="prime-auto">
                                    <Dropdown v-model="item.jenis" :options="d_JenisPagu" :optionLabel="'label'"
                                        @change="choiceJenisPagu(item.jenis)" placeholder="Jenis" :optionValue="'value'"
                                        style="width: 100%;" :filter="true" appendTo="body" showClear />
                                </VControl>
                                <!-- <VControl icon="feather:search">
                                    <AutoComplete v-model="item.jenisPagu" :suggestions="d_JenisPagu"
                                        @complete="dataCombo()" :optionLabel="'label'" :dropdown="true" :minLength="3"
                                        :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                                        placeholder="ketik Nama Perawat" />
                                </VControl> -->
                            </VField>
                        </div>
                        <div class="column">
                            <span style="font-weight: 500;">Detail Jenis Pagu</span>
                            <VField class="is-autocomplete-select pt-3">
                                <VControl icon="feather:search" class="prime-auto" :loading="isLoading">
                                    <Dropdown v-model="item.detailJenisPagu" :options="d_DetailJenisPagu"
                                        :optionLabel="'label'" placeholder="Jenis" :optionValue="'value'"
                                        style="width: 100%;" :filter="true" appendTo="body" showClear />
                                </VControl>
                            </VField>
                        </div>
                    </div>
                    <div class="column p-0 mb-3">
                        <VPlaceload height="20rem" width="100%" class="mx-2" v-if="loadSearch" />
                        <DataTable v-else :rows="5" :value="dataSource" :rowsPerPageOptions="[5, 10, 15]"
                            class="p-datatable-sm" breakpoint="960px" selectionMode="single" sortMode="multiple"
                            showGridlines tableStyle="min-width: 30rem"
                            paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
                            paginator currentPageReportTemplate="Showing {first} to {last} of {totalRecords}">

                            <Column field="tglstruk" header="Nama Pegawai" />
                            <Column field="tglstruk" header="Jabatan" />
                            <Column field="tglstruk" header="Golongan" />
                        </DataTable>
                    </div>
                </VCard>
            </div>
        </div>
    </section>
</template>

<script  setup lang="ts">
import { useApi } from '/@src/composable/useApi'
import { ref, reactive, computed } from 'vue'
import { useRouter, useRoute, RouterLink } from 'vue-router';
import { useConfirm } from 'primevue/useconfirm'
import { useHead } from '@vueuse/head'
import AutoComplete from 'primevue/autocomplete';
import TabView from 'primevue/tabview';
import TabPanel from 'primevue/tabpanel';
import DataTable from 'primevue/datatable'
import ColumnGroup from 'primevue/columngroup';   // optional
import Row from 'primevue/row';
import * as H from '/@src/utils/appHelper'
import Dialog from 'primevue/dialog';
import Column from 'primevue/column'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import Dropdown from 'primevue/dropdown';
import Divider from 'primevue/divider';
useHead({
    title: 'Mapping Jasa Pelayanan to Pegawai - ' + import.meta.env.VITE_PROJECT,
})

useViewWrapper().setPageTitle('E-Remun')
useViewWrapper().setFullWidth(true)

const d_JenisPagu: any = ref([])
const item: any = ref({
    aktif: true,
})

const router = useRouter()
const activeTab = ref(0);
const dataSource: any = ref([])
const selected: any = ref({})
let d_KelompokPasien: any = ref([])
let d_Pegawai: any = ref([])
let d_DetailJenisPagu: any = ref([])
let loadSearch: any = ref(true)
let isLoading: any = ref(false)
let isAngka: any = ref(false)

const dataCombo = async () => {
    await useApi().get('remunerasi/get-combo-idx').then((response) => {
        d_JenisPagu.value = response.jenispagu.map((e: any) => {
            return { label: e.jenispagu, value: e.id }
        })
    })
}

const fetchPegawai = async (filter: any) => {

    await useApi().get(`emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`
    ).then((response) => {
        d_Pegawai.value = response
    })
}

const choiceJenisPagu = async (e: any) => {
    isLoading.value = true
    await useApi().get(`remunerasi/get-pegawai-by-jenis-pagu?jpid=${e}`).then((response: any) => {
        d_DetailJenisPagu.value = response.detailjenispagu.map((e: any) => {
            return { label: e.detailjenispagu, value: e.id }
        })
    })
    isLoading.value = false
}
dataCombo()

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
    margin-top: 24px;
}

.control.has-icon.prime-auto .form-icon {
    top: 0px;
}
</style>
