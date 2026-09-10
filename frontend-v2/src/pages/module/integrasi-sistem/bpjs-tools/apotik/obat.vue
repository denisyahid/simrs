<template>
  <div class="columns is-multiline">
    <div class="column is-12">
      <div class="py-4">
        <h1 style="font-weight:bold">Mapping Obat BPJS</h1>
      </div>
      <div class="column is-12">
        <VCard>
          <div class="columns is-multiline p-1">
            <div class="column is-6">
              <VField class="is-rounded-select is-autocomplete-select" label="Obat BPJS">
                <VControl icon="feather:search" class="prime-auto-select" :loading="isLoadingSelect">
                  <Dropdown v-model="item.obatBpjs" :options="d_ObatBPJS" :optionLabel="'label'"
                    placeholder="Pilih data" style="width: 100%;" showClear :filter="true" />
                </VControl>
              </VField>
            </div>
            <div class="column is-6">
              <VField class="is-rounded-select is-autocomplete-select" label="Obat RS">
                <VControl icon="feather:search" class="prime-auto-select">
                  <AutoComplete v-model="item.obatRs" :suggestions="d_ObatRS" @complete="fetchObat($event)"
                    :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                    :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="ketik untuk mencari..." />
                </VControl>
              </VField>
            </div>
          </div>
          <div class="column is-4">
            <div class="columns is-multiline p-1">
              <div class="column is-4">
                <VButton type="button" color="success" class="searcv-button" raised icon="fas fa-save" @click="simpan()"
                  :loading="isLoadingSave">Simpan
                </VButton>
              </div>
              <div class="column is-4">
                <VButton type="button" color="danger" class="searcv-button" raised icon="fas fa-times-circle"
                  @click="clear()">Batal
                </VButton>
              </div>
              <div class="column is-4">
                <VButton type="button" color="info" class="searcv-button" raised icon="fas fa-search"
                  @click="fetchData()" :loading="isLoadingSearch">Cari
                </VButton>
              </div>
            </div>
          </div>
        </VCard>
      </div>
       <div class="column is-12 px-4">
        <VCard>
          <div class="flex-list-inner mb-2 mt-5" v-if="isLoading">
            <div class="flex-table-item grid-item mb-1" v-for="key in 5" :key="key">
              <VPlaceloadWrap>
                <VPlaceload class="mx-2 h-hidden-tablet-p" />
                <VPlaceload class="mx-2 h-hidden-tablet-p" />
                <VPlaceload class="mx-2 h-hidden-tablet-p" />
                <VPlaceload class="mx-2 h-hidden-tablet-p" />
                <VPlaceload class="mx-2" />
              </VPlaceloadWrap>
            </div>
          </div>
          <div class="flex-list-inner" v-else-if="dataSource.length == 0">
            <VPlaceholderSection :title="H.assets().notFound" :subtitle="H.assets().notFoundSubtitle" class="my-6">
              <template #image>
                <img class="light-image" :src="H.assets().iconNotFound_rev" alt="" />
                <img class="dark-image" src="/@src/assets/illustrations/placeholders/search-4-dark.svg" alt="" />
              </template>
            </VPlaceholderSection>
          </div>
          <div v-else>
            <VFlexTable :data="dataSource" :columns="columns" rounded>
              <template #body>
                <div name="list" tag="div" class="flex-list-inner">
                  <div v-for="(item, index)  in dataSource" :key="item.id" class="flex-table-item">
                    <VFlexTableCell>
                      <span class="light-text">{{ (index + 1) * currentPage.page }}</span>
                    </VFlexTableCell>
                    <VFlexTableCell>
                      <span class="light-text">{{ item.kdproduk }}</span>
                    </VFlexTableCell>
                    <VFlexTableCell>
                      <span class="light-text">{{ item.kdobatbpjs }}</span>
                    </VFlexTableCell>
                    <VFlexTableCell :column="{ align: 'end' }">
                      <span class="light-text">{{ item.namaproduk }}</span>
                    </VFlexTableCell>
                    <VFlexTableCell :column="{ align: 'end' }">
                      <VDropdown icon="feather:more-vertical" spaced right class="is-pulled-right">
                        <template #content>
                          <a role="menuitem" class="dropdown-item is-media" @click="deleteObat(item)">
                            <div class="icon">
                              <i class="lnil lnil-trash" aria-hidden="true"></i>
                            </div>
                            <div class="meta">
                              <span>Hapus</span>
                            </div>
                          </a>
                          <a role="menuitem" class="dropdown-item is-media" @click="editData(item)">
                            <div class="icon">
                              <i class="lnil lnil-pencil" aria-hidden="true"></i>
                            </div>
                            <div class="meta">
                              <span>Ubah</span>
                            </div>
                          </a>
                        </template>
                      </VDropdown>
                    </VFlexTableCell>
                  </div>
                </div>
              </template>
            </VFlexTable>
          </div>
        </VCard>
        <VFlexPagination v-model:current-page="currentPage.page" :item-per-page="currentPage.limit"
          :total-items="dataSource.total" :max-links-displayed="5">
          <template #before-pagination>
          </template>
          <template #before-navigation>
            <VFlex class="mr-4 mt-1" column-gap="1rem">
              <VField>
              </VField>
              <VField>
                <VControl>
                  <div class="select is-rounded">
                    <select v-model="currentPage.limit">
                      <option :value="1">1 results per page</option>
                      <option :value="5">5 results per page</option>
                      <option :value="10">10 results per page</option>
                      <option :value="15">15 results per page</option>
                      <option :value="25">25 results per page</option>
                      <option :value="50">50 results per page</option>
                    </select>
                  </div>
                </VControl>
              </VField>
            </VFlex>
          </template>
        </VFlexPagination>
       </div>
    </div>
  </div>
</template>
<script setup lang="ts">
import { useRoute, useRouter } from 'vue-router'
import { ref, computed, watch, reactive } from 'vue'
import { useThemeColors } from '/@src/composable/useThemeColors'
import { onceImageErrored } from '/@src/utils/via-placeholder'
import { useToaster } from '/@src/composable/toaster'
import * as H from '/@src/utils/appHelper'
import { useApi } from '/@src/composable/useApi'
import { useUserSession } from '/@src/stores/userSession'
import { useHead } from '@vueuse/head'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import AutoComplete from 'primevue/autocomplete';
import Dropdown from 'primevue/dropdown';
const setView = () => {
  useHead({
    title: 'Obat - ' + import.meta.env.VITE_PROJECT,
  })
  useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
  useViewWrapper().setFullWidth(true)
}
let item: any = reactive({
  pencarian: 'nobpjs'
})
const input: any = ref({})
const columns: any = ref({})
const d_ObatBPJS :any = ref([]);
const d_ObatRS :any = ref([]);
const isLoadingSearch:any = ref(false);
const isLoadingSave:any = ref(false);
const isLoading:any = ref(false);
const dataSource :any = ref([]);
columns.value = {
  no: 'No',
  tglsbm: 'Kode Obat BPJS',
  carabayar: 'Kode Obat RS',
  totaldibayar: 'Nama Obat',
  actions: {
    label: 'Aksi',
    align: 'end',
  },
}
// d_ObatBPJS.value = [
//   {
//     label : "Obat 1",
//     value : "111"
//   },
//   {
//     label : "Obat 2",
//     value : "222"
//   }
// ]
const route = useRoute()
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
const fetchObatBpjs = async () => {
  var json = {
    "url": "/referensi/dpho",
    "jenis": "apotik",
    "method": "GET",
    "data": null
  }
  const {response} = await useApi().postBPJS(`/bridging/bpjs/tools`, json)
  d_ObatBPJS.value = response.list.map((res:any) => ({
    label: res.namaobat,
    value: res.kodeobat
  }));
}
const fetchObat = async (filter :any) =>{
  const response = await useApi().get(`/farmasi/dropdown-obat?namaproduk=${filter.query}&limit=10`)
  response.map((element : any) =>{
    element.label  = element.productname,
    element.value  = element.id
  })
  d_ObatRS.value = response
}
const simpan = async ()=>{
 if (!item.obatRs) {
    H.alert('warning', 'Data obat rumah sakit harus diisi !');
    return;
  }
  if (!item.obatBpjs) {
    H.alert('warning', 'Data obat bpjs harus diisi !');
    return;
  }
  let json = {
    'idProduk': item.obatRs.value,
    'kodeObatBpjs': item.obatBpjs.value
  }
  isLoadingSave.value = true;
  await useApi().post(
    `/medifirst2000/bridging/bpjs/apotik/obat`, json
  ).then((response) => {
    isLoadingSave.value = false;
  }).catch((exception) => {
    isLoadingSave.value = false;
  })
  clear();
  fetchData();
}
const clear = ()=>{
  delete item.obatBpjs;
  delete item.obatRs;
}
const editData = (e: any) => {
  item.obatRs = { label: e.namaproduk, value: e.id };
  if (e.kdobatbpjs) {
    d_ObatBPJS.value.forEach((element:any) => {
      if (element.value == e.kdobatbpjs) {
        item.obatBpjs = element
        return
      }
    });
  }
}
const fetchData = async ()=>{
  isLoadingSearch.value = true;
  const response = await useApi().get(`/medifirst2000/bridging/bpjs/apotik/obat`)
  dataSource.value = response.data;
  isLoadingSearch.value = false;
  dataSource.value.total = response.total
}
const deleteObat = async (item: any) => {
  isLoading.value = true;
  let json = {
    id: item.id
  }
  await useApi().post(
    `/medifirst2000/bridging/bpjs/apotik/delete-obat`, json,
  ).then((response) => {
    isLoading.value = false;
  }).catch((exception) => {
    isLoading.value = false;
  })
  fetchData()
}
watch(currentPage.value, () => {
  fetchData()
})
fetchObatBpjs();
fetchData();
</script>
