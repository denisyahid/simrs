<template>
 <VCard>
    <div class="columns column">
      <h3 class="title is-5 mb-2 mr-1">Ruangan Apotik</h3>
    </div>
    <div class="columns is-multiline">
      <div class="column is-9">
        <div class="user-grid-toolbar">
          <VField class="switch-filter">
            <VControl>
                  <InputSwitch v-model="item.aktif" @change="fetchData()"/>
                </VControl>
                <span>Aktif</span>
          </VField>
          <div class="buttons">
            <VField v-slot="{ id }" class="is-icon-select">
              <VControl>
                <Multiselect v-model="selectView" :attrs="{ id }" placeholder="Select View" label="name" :options="d_View"
                  :searchable="true" track-by="name" mode="single" @select="changeView(selectView)" autocomplete="off">
                  <template #singlelabel="{ value }">
                    <div class="multiselect-single-label">
                      <div class="select-label-icon-wrap">
                        <i :class="value.icon"></i>
                      </div>
                      <span class="select-label-text">
                        {{ value.name }}
                      </span>
                    </div>
                  </template>
                  <template #option="{ option }">
                    <div class="select-option-icon-wrap">
                      <i :class="option.icon"></i>
                    </div>
                    <span class="select-option-text">
                      {{ option.name }}
                    </span>
                  </template>
                </Multiselect>
              </VControl>
            </VField>
            <VButton color="primary" raised @click="add()">
              <span class="icon">
                <i aria-hidden="true" class="fas fa-plus"></i>
              </span>
              <span> Tambah Data</span>
            </VButton>
          </div>
        </div>
        <div v-if="isLoading">
          <VPlaceloadWrap>
            <VPlaceload height="500px" width="100%" class="mx-2" />
          </VPlaceloadWrap>
        </div>
        <div class="flex-list-inner" v-else-if="dataSourcefiltered.length == 0">
            <VPlaceholderSection :title="H.assets().notFound" :subtitle="H.assets().notFoundSubtitle" class="my-6">
              <template #image>
                <img class="light-image" :src="H.assets().iconNotFound_rev" alt="" />
                <img class="dark-image" src="/@src/assets/illustrations/placeholders/search-4-dark.svg" alt="" />
              </template>
            </VPlaceholderSection>
        </div>
        <div v-else>
          <div class="user-grid user-grid-v2" v-if="selectView == 'list'">
            <DataTable :value="dataSourcefiltered" :paginator="true" :rows="10" :rowsPerPageOptions="[5, 10, 25]"
              paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
              responsiveLayout="stack" breakpoint="960px" sortMode="multiple"
              currentPageReportTemplate="Showing {first} to {last} of {totalRecords}">

              <Column field="no" header="Kode"></Column>
              <Column field="namaruangan" header="Ruangan" :sortable="true"></Column>
              <Column field="namadepartemen" header="Nama Departemen" :sortable="true"></Column>
              <Column field="kodeapotikonline" header="Kode Aptotik Online" :sortable="true"></Column>
            </DataTable>
          </div>
          <div class="tile-grid tile-grid-v1" v-else-if="selectView == 'grid'">
            <TransitionGroup name="list" tag="div" class="columns is-multiline">
              <!--Grid item-->
              <div v-for="(item, key) in dataSourcefiltered" :key="key" class="column is-6">
                <div class="tile-grid-item">
                  <div class="tile-grid-item-inner">
                    <VAvatar size="medium" :picture="item.icons != null ? item.icons : '/images/avatars/svg/room.svg'"
                      color="primary" squared bordered />
                    <div class="meta">
                      <span class="dark-inverted">{{ item.namaruangan }}</span>
                      <span>{{ item.namadepartemen }}</span>
                      <VTag :color="item.kodeapotikonline == null ? 'danger':'success'" :label="(item.kodeapotikonline?item.kodeapotikonline:'')" />
                    </div>
                  </div>
                </div>
              </div>
            </TransitionGroup>
          </div>
        </div>
      </div>
       <div class="column is-3">
        <div class="columns is-multiline">
          <div class="column is-6">
            <h3 class="title is-5 mb-2 mr-1" style="margin-top: 1rem;">Filter</h3>
          </div>
          <div class="column is-6">
            <img src="/images/avatars/svg/keluar.svg" alt="" srcset="" style="margin-top: -3rem;" />
          </div>
          <div class="column is-12">
            <VField style="margin-top: -1rem;">
              <VControl icon="feather:search">
                <input v-model="filters" class="input custom-text-filter" placeholder="Filter Ruangan" />
              </VControl>
            </VField>
          </div>
          <div class="column is-12">
            <VButton @click="fetchData()" :loading="isLoading" type="button" icon="feather:search" class="is-fullwidth mr-3"
              color="info" raised>
              Pencarian
            </VButton>
          </div>
        </div>
      </div>
    </div>
 </VCard>
  <VModal :open="modalInput" title="Mapping Ruangan Apotik" actions="right" @close="modalInput = false">
      <template #content>
        <form class="modal-form">
          <div class="columns is-multiline">
            <div class="column is-12">
             <VField class="is-rounded-select is-autocomplete-select required-vfield" label="Ruangan Asal">
                <VControl icon="feather:search" class="prime-auto-select">
                  <AutoComplete v-model="item.ruangan" :suggestions="d_Ruangan" @complete="fetchRuangan($event)"
                    :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                    :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder=" Ruangan Asal"  @item-select="fetchPoliBpjs()"/>
                </VControl>
              </VField>
            </div>
            <div class="column " :class="item.id ? 'is-8' : 'is-12'">
              <VField class=" is-rounded-select is-autocomplete-select">
                <VLabel>Departemen</VLabel>
                <VControl icon="fas fa-home" class="prime-auto">
                  <Dropdown v-model="item.poli" :options="d_Poli" :optionLabel="'label'"
                    placeholder="Departemen" style="width: 100%;" :filter="true" appendTo="body" :loading="d_Poli.loading" />
                </VControl>
              </VField>
            </div>
          </div>
        </form>
      </template>
      <template #action>
        <VButton icon="feather:plus" @click="save()" :loading="isLoadingSave" color="primary" raised>Simpan</VButton>
      </template>
    </VModal>
</template>
<script setup lang="ts">
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
import InputSwitch from 'primevue/inputswitch'
import DataTable from 'primevue/datatable'
import Column from 'primevue/column'
const setView = () => {
  useHead({
    title: 'Mapping KD Apotik - ' + import.meta.env.VITE_PROJECT,
  })
  useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
  useViewWrapper().setFullWidth(true)
}

const d_View = [
  {
    name: 'Grid View',
    value: 'grid',
    icon: 'fas fa-id-card-alt',
  },
  {
    name: 'List View',
    value: 'list',
    icon: 'fas fa-list',
  },
]
const selectView: any = ref()
selectView.value = 'grid'
let item: any = reactive({

})
const filters :any = ref('')
const isLoadingSearch :any = ref(false)
const isLoadingSave :any = ref(false)
const isLoading :any = ref(false)
const modalInput:any = ref(false)
const d_Poli:any = ref([])
const dataSourcefiltered = computed(() => {
  if (!filters.value) {
    return dataSource.value
  }

  return dataSource.value.filter((items: any) => {
    return (
      items.namaruangan.match(new RegExp(filters.value, 'i'))
    )
  })
})

const input: any = ref({})
const dataSource :any = ref([])
const d_Ruangan :any = ref([])
const fetchData = async()=>{
  isLoading.value = true;
  const response = await useApi().get(`/medifirst2000/bridging/bpjs/apotik/ruangan`)
  dataSource.value = response.data;
  isLoading.value = false;
  dataSource.value.total = response.total
}
function add() {
  clear()
  modalInput.value = true
}
function clear(){

}
function changeView(e: any) {
  selectView.value = e
}
const save = async ()=>{
if (!item.ruangan) {
    H.alert('warning', 'Ruangan Harus Disis !');
    return;
  }
  if (!item.poli) {
    H.alert('warning', 'Kode Poli Harus Disis !');
    return;
  }
  let json = {
    'ruanganId': item.ruangan.value,
    'kodeApotikOnline': item.poli.value
  }
  isLoadingSave.value = true;
  await useApi().post(
    `/medifirst2000/bridging/bpjs/apotik/ruangan`, json
  ).then((response) => {
    isLoadingSave.value = false;
  }).catch((exception) => {
    isLoadingSave.value = false;
  })
  clear();
  fetchData();
}
const fetchRuangan = async (filter: any) => {
  const response = await useApi().get(
    `/emr/dropdown/ruangan_m?select=id,namaruangan&param_search=namaruangan&query=${filter.query}&limit=10`)
  d_Ruangan.value = response
}
const fetchPoliBpjs = async () => {
  d_Poli.value.loading = true
  var json = {
    "url": `/referensi/poli/a`,
    "jenis": "apotik",
    "method": "GET",
    "data": null
  }
  const {response} = await useApi().postBPJS(`/bridging/bpjs/tools`, json)
  d_Poli.value = response.list.map((res:any) => ({
    label: res.nama,
    value: res.kode
  }));
  modalInput.value = false
  d_Poli.value.loading = false
}
fetchData();
</script>
<style lang="scss">
@import '/@src/scss/abstracts/all';

@import '/@src/scss/components/forms-outer';
@import '/@src/scss/custom/config';
@import '/@src/scss/module/sysadmin/master-data.scss';
</style>
