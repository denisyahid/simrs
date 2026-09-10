<template>
    <VCard>
        <h3>Data Pasien</h3>
        <div class="columns is-multiline mt-2">
            <div class="column is-12">
                <div class="columns is-multiline">
                    <div class="column is-4">
                        <span>Periode</span>
                        <VDatePicker v-model="item.filterDate" is-range color="pink" trim-weeks :max-date="new Date()">
                            <template #default="{ inputValue, inputEvents }">
                                <VField addons>
                                    <VControl icon="feather:calendar">
                                        <VInput :value="inputValue.start" v-on="inputEvents.start" />
                                    </VControl>
                                    <VControl>
                                        <VButton static><i class="fas fa-arrow-right" aria-hidden="true"></i></VButton>
                                    </VControl>
                                    <VControl icon="feather:calendar">
                                        <VInput :value="inputValue.end" v-on="inputEvents.end" />
                                    </VControl>
                                </VField>
                            </template>
                        </VDatePicker>
                    </div>
                    <div class="column is-3 pt-1">
                        <span>Ruangan</span>
                        <VField class="is-autocomplete-select pt-2">
                            <VControl icon="feather:search">
                                <AutoComplete v-model="item.ruangan" :suggestions="d_Ruangan"
                                    @complete="fetchRuangan($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                                    :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                                    placeholder="Ruangan" />
                            </VControl>
                        </VField>
                    </div>

                    <div class="column is-2" style="margin-top: 30px">
                        <VIconButton circle icon="feather:refresh-cw" raised bold @click="fetchDataRS()"
                            :loading="isLoading" v-tooltip.bubble="'Cari'" class="mt-2-min">
                        </VIconButton>
                    </div>
                </div>
                <div class="column is-12" v-if="isLoading">
                    <VPlaceloadWrap>
                        <VPlaceload height="500px" width="100%" class="mx-2" />
                    </VPlaceloadWrap>
                </div>
                <div class="column is-12" v-else>
                    <DataTable :value="dataSource" dataKey="no" v-model:selection="selectData" class="p-datatable-sm"
                        :paginator="true" :rows="10" :rowsPerPageOptions="[5, 10, 25]" scrollable
                        paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
                        responsiveLayout="stack" breakpoint="960px" sortMode="multiple"
                        currentPageReportTemplate="Showing {first} to {last} of {totalRecords}" showGridlines>
                        <Column selectionMode="multiple" headerStyle="width: 3rem"></Column>
                        <Column field="no" header="No" style="text-align: center;"></Column>
                        <Column field="nocm" header="No. RM"></Column>
                        <Column field="noidentitas" header="No. Identitas" style="min-width: 200px"></Column>
                        <Column field="inisial" header="Inisial" style="min-width: 200px"></Column>
                        <Column field="email" header="Email" style="min-width: 100px"></Column>
                        <Column field="nohp" header="No. HP" style="min-width: 100px"></Column>
                        <Column field="jeniskelamin" header="Jenis Kelamin" style="min-width: 100px"></Column>
                        <Column field="usia" header="Usia" style="min-width: 100px">
                        </Column>
                        <Column field="tglregistrasi" header="Tanggal Masuk" style="min-width: 200px">
                        </Column>
                        <Column field="namaruangan" header="Ruangan" style="min-width: 200px">
                        </Column>
                        <Column field="namapropinsi" header="Provinsi" style="min-width: 200px">
                        </Column>
                        <Column field="namakotakabupaten" header="Kabupaten" style="min-width: 200px">
                        </Column>
                        <Column field="namakecamatan" header="Kecamatan" style="min-width: 200px">
                        </Column>
                        <Column field="lastupdate" header="Tgl Update" style="min-width: 200px"></Column>
                        <Column :exportable="false" header="Action">
                        </Column>
                    </DataTable>
                </div>
            </div>
        </div>
    </VCard>
</template>


<script setup lang="ts">
import { useRoute, useRouter } from 'vue-router'
import { ref, computed, watch, reactive } from 'vue'
import * as H from '/@src/utils/appHelper'
import { useApi } from '/@src/composable/useApi'
import { useHead } from '@vueuse/head'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useToaster } from '/@src/composable/toaster'
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import AutoComplete from 'primevue/autocomplete';
import Row from 'primevue/row';
import InputText from 'primevue/inputtext';

import { FilterMatchMode } from 'primevue/api'
import Calendar from 'primevue/calendar';
useHead({
    title: import.meta.env.VITE_PROJECT,
})
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)
const isLoading: any = ref(false)
const isSave: any = ref(false)
const isDelet: any = ref(false)
const item: any = reactive({
    filterDate: {
        start: new Date(),
        end: new Date()
    },
    limit: 10,
    start: 1,
})
const dataSource = ref([])
const dataRS = ref([])

const isLoadingTT: any = ref(false)
const isLoadingSave: any = ref(false)
const selectData = ref();
const d_Ruangan = ref([])

const expandedRows = ref([]);

const filters = ref({
    global: { value: null, matchMode: FilterMatchMode.CONTAINS },

});

const fetchRuangan = async (filter: any) => {
  let search = ''
  if(filter){
    search = filter.query
  }
  useApi().get(
        `emr/dropdown/ruangan_m?select=id,namaruangan,id&param_search=namaruangan&query=${search}`
    ).then((response) => {
        d_Ruangan.value = response.map((e: any) => { return { label: e.label, value: e.value, default: e } })
    })
}

const fetchDataRS = async () => {

    let tglawal = H.formatDate(item.filterDate.start, 'YYYY-MM-DD')
    let tglakhir = H.formatDate(item.filterDate.end, 'YYYY-MM-DD')

    isLoading.value = true

    await useApi().get(`/bridging/rsonline/get-pasien?tglawal=${tglawal}&tglakhir=${tglakhir}`).then((response) => {
        response.forEach((element: any, i: any) => {
            element.no = i + 1
        });
        isLoading.value = false
        dataSource.value = response
    })
}


fetchDataRS()
// fetchData()
</script>
<style lang="scss">
.control.has-icon {
    position: relative;
    width: 100%;
}

.field:not(:last-child) {
    margin-bottom: 0px !important;
}
</style>
