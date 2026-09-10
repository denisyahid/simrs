<template>
  <div class="page-content-inner">
    <div class="is-navbar">
      <div class="form-layout">
        <div class="form-outer">
          <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
            <div class="form-header-inner">
              <div class="left">
                <h3>Mapping Ruangan To EMR</h3>
              </div>
              <div class="right">
                <div class="buttons">
                  <VButton icon="lnir lnir-arrow-left rem-100"
                    :to="{ name: 'module-sysadmin-master-map-ruangan-to-produk' }" light dark-outlined rounded>
                    Batal
                  </VButton>
                  <VButton type="button" icon="feather:save" :loading="isLoading" color="primary" raised rounded outlined
                    @click="saveMapRuanganToProduk()"> Simpan
                  </VButton>
                </div>
              </div>
            </div>
          </div>
          <div class="form-body">
            <div class="form-fieldset">
              <TabView class="tabview-custom mt-3" :scrollable="true" @tab-click="klikTab($event)">
                <TabPanel>
                  <template #header>
                    <i class="pi pi-pencil mr-2"></i>
                    <span>Mapping Baru</span>
                  </template>
                  <VCard>
                    <div class="columns is-multiline">
                      <div class="column is-8">
                        <VField label="Ruangan" class="is-rounded-select is-autocomplete-select mt-0 pt-0"
                          v-slot="{ id }">
                          <VControl icon="fas fa-search" fullwidth class="prime-auto-select">
                            <Dropdown v-model="item.namaruangan" :options="d_Ruangan" :optionLabel="'label'"
                              class="is-rounded" placeholder="Ruangan" style="width: 100%;" :filter="true" showClear
                              @change="handleChangeProduk($event)">
                            </Dropdown>
                          </VControl>
                        </VField>
                      </div>
                      <Divider align="center">
                        <span class="p-tag">Form EMR</span>
                      </Divider>
                      <div class="column is-12 mt-0 pt-0" v-if="listChecked.length > 0">
                        <VCard>
                          <div class="form-section pr-0 mt-0 pt-0">
                            <div class="form-section-inner has-padding-bottom h-700-o ">
                              <h3 class="has-text-centered">Data Terpilih ({{ listChecked.length }})
                                <VIconButton circle class="mr-2 is-pulled-right" icon="fas fa-trash"
                                  v-tooltip.danger.bubble="'Hapus Terpilih'" color="danger" raised bold
                                  @click="clearSelection()">
                                </VIconButton>
                              </h3>
                              <div class="columns is-multiline mt-2">
                                <div class="column is-4" v-for="items in listChecked" :key="items.label">
                                  <VTag color="purple" :label="items.label" />
                                </div>
                              </div>
                            </div>
                          </div>
                        </VCard>
                      </div>
                      <div class="column is-12">
                        <VCard>
                          <div class="column is-6">
                            <VField>
                              <h3 class="title is-6 mb-2 mr-1"> Pilih EMR </h3>
                              <VControl icon="feather:search">
                                <input v-model="filters" class="input custom-text-filter" placeholder="Search..." />
                              </VControl>
                            </VField>
                          </div>
                          <p> Data terpilih <b>{{ listChecked.length }}</b></p>
                          <div class="columns is-multiline p-2" style="max-height:500px;overflow: auto;">
                            <div class="column is-4" :key="items.value" v-for="items in dataSourcefiltered"
                              style="padding:0">
                              <VField>
                                <VControl raw subcontrol>
                                  <VCheckbox v-model="checkboxProduk[items.value]" :value="items.value"
                                    :label="items.label" color="info" square
                                    :class="checkboxProduk[items.value] == true ? 'is-solid' : ''"
                                    @change="clickProduk()" />
                                </VControl>
                              </VField>
                            </div>
                          </div>
                        </VCard>
                      </div>
                    </div>
                  </VCard>
                </TabPanel>

                <TabPanel>
                  <template #header>
                    <i class="pi pi-list mr-2"></i>
                    <span>List Mapping</span>
                  </template>
                  <VCard>
                    <div class="columns is-multiline">
                      <div class="column is-12">
                        <DataTable    v-model:filters="item"  :value="dataTable" rowGroupMode="subheader" groupRowsBy="namaruangan" sortMode="single"
                          sortField="namaruangan" :sortOrder="1" scrollable scrollHeight="400px"
                          :globalFilterFields="['namaruangan', 'label', 'collection', 'url_form']"
                          filterDisplay="menu"
                          dataKey="id"
                          tableStyle="min-width: 50rem">
                          <template #header>
                            <div class="flex justify-content-end">
                              <span class="p-input-icon-left">
                                <VField>
                                  <VControl icon="feather:search">
                                    <VInput type="text" v-model="item.global.value" placeholder="Filter"
                                      class="is-rounded" />
                                  </VControl>
                                </VField>
                              </span>
                            </div>
                          </template>
                          <Column field="namaruangan" header="Ruangan"></Column>
                          <Column field="label" header="Form EMR" style="min-width: 200px"></Column>
                          <Column field="collection" header="Collection" style="min-width: 200px"></Column>
                          <Column field="url_form" header="URL Form" style="min-width: 200px"></Column>
                          <template #groupheader="slotProps">
                            <div class="flex align-items-center font-bold gap-2">
                              <img :alt="slotProps.data.namaruangan" :src="`/images/avatars/svg/room.svg`" width="25"
                                style="vertical-align: middle;margin-top:-5px" />
                              <span>{{ slotProps.data.namaruangan }}</span>
                            </div>
                          </template>

                          <template #groupfooter="slotProps">
                            <div class="flex justify-content-end font-bold w-full">Total : {{
                              calculateCustomerTotal(slotProps.data.namaruangan) }}</div>
                          </template>
                        </DataTable>
                      </div>
                    </div>
                  </VCard>
                </TabPanel>
              </TabView>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script setup lang="ts">
import { useWindowScroll } from '@vueuse/core'
import { useApi } from '/@src/composable/useApi'
import { h, reactive, ref, computed, defineComponent, watch } from 'vue'
import Divider from 'primevue/divider';
import { useRoute, useRouter } from 'vue-router'
import { useHead } from '@vueuse/head'
import * as H from '/@src/utils/appHelper'
import Fieldset from 'primevue/fieldset';
import { useViewWrapper } from '/@src/stores/viewWrapper'
import AutoComplete from 'primevue/autocomplete';
import { useToaster } from '/@src/composable/toaster'
import { elements } from '/@src/data/landing/components'
import Dropdown from 'primevue/dropdown';
import TabView from 'primevue/tabview';
import TabPanel from 'primevue/tabpanel';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import ColumnGroup from 'primevue/columngroup';   // optional
import Row from 'primevue/row';
import { FilterMatchMode } from 'primevue/api';
useHead({
  title: 'Map Ruangan To EMR - ' + import.meta.env.VITE_PROJECT,
})
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
let ID_PRODUK = useRoute().query.id as string
let ID_RUANGAN = useRoute().query.objectruanganfk as string
let ID_PRODUK_SET = ref()
let d_Ruangan: any = ref([])
let d_Departemen: any = ref([])
let d_Produk: any = ref([])
let d_KelompokProduk: any = ref([])
let isLoading = ref(false)
let jumlahCeklis: any = ref(0)
const listChecked: any = ref([])
const d_ListDefault: any = ref([])
const date = ref(new Date())
const item: any = ref({
  'global': { value: null, matchMode: FilterMatchMode.CONTAINS },
})
const checkboxProduk: any = ref([])
const items: any = ref([])
const dataSource: any = ref([])
const { y } = useWindowScroll()
const router = useRouter()
const isStuck = computed(() => { return y.value > 30 })
const activeTab = ref(0)
const d_EMR: amy = ref([])
const filters = ref('')
const dataTable: any = ref([])

const dataSourcefiltered = computed(() => {
  if (!filters.value) {
    return d_EMR.value
  }
  return d_EMR.value.filter((items: any) => {
    return (
      items.label.match(new RegExp(filters.value, 'i'))
    )
  })
})


async function listDropdown() {
  await useApi().get(
    `emr/dropdown/emr_t?select=id,caption&param_search=caption&query=&limit=1000&orderby=caption`
  ).then((response) => {
    response.map((element:any)=>{
      element.label = element.caption
      element.value =element.id
    })
    d_EMR.value =  response
    d_ListDefault.value = response
  })

  await useApi().get(
    `emr/dropdown/ruangan_m?select=id,namaruangan,objectdepartemenfk&param_search=namaruangan&query=&limit=1000`
  ).then((response) => {
    d_Ruangan.value = response
  })

}




async function saveMapRuanganToProduk() {
  if (!item.value.namaruangan) { H.alert('warning', 'Nama Ruangan harus di pilih'); return }
  if (jumlahCeklis.value == "0") { H.alert('warning', 'Data EMR harus di pilih '); return }
  let arrProduk = []
  let objectK = Object.keys(checkboxProduk.value)
  for (let x = 0; x < objectK.length; x++) {
    const element = objectK[x];
    if (checkboxProduk.value[element] == true) {
      arrProduk.push({ 'emrfk': element })
    }

  }
  let json = {
    'objectruanganfk': item.value.namaruangan.value,
    'objectdepartemenfk': item.value.namaruangan.objectdepartemenfk,
    'detail': arrProduk

  }
  console.log(json)
  isLoading.value = true
  await useApi().post(
    `/sysadmin/master-emr/save-map`, json).then((response: any) => {
      isLoading.value = false

    }).catch((e: any) => {
      isLoading.value = false

    })

}

const clearSelection = () => {
  var arrobj = Object.keys(checkboxProduk.value)
  for (let x = 0; x < arrobj.length; x++) {
    const element2 = arrobj[x];
    checkboxProduk.value[element2] = false
  }
  clickProduk()
}
const clickProduk = () => {

  jumlahCeklis.value = 0
  let objectK = Object.keys(checkboxProduk.value)
  let jumlah = 0
  for (let x = 0; x < objectK.length; x++) {
    const element = objectK[x];
    if (checkboxProduk.value[element] == true) {
      for (var i = 0; i < d_ListDefault.value.length; i++) {
        const element2 = d_ListDefault.value[i];
        if (element2.value == element) {
          for (var z = 0; z < listChecked.value.length; z++) {
            const element3 = listChecked.value[z];
            if (element3.label == element2.label) {
              listChecked.value.splice(z, 1)
            }
          }
          listChecked.value.push({ label: element2.label })
        }
      }
      jumlah = jumlah + 1

    } else {
      for (var i = 0; i < d_ListDefault.value.length; i++) {
        const element2 = d_ListDefault.value[i];
        if (element2.value == element) {
          for (var z = 0; z < listChecked.value.length; z++) {
            const element3 = listChecked.value[z];
            if (element3.label == element2.label) {
              listChecked.value.splice(z, 1)
            }
          }
        }
      }
    }
  }
  jumlahCeklis.value = jumlah
}


const resetForm = () => {

}

const handleChangeProduk = (e: any) => {

  checkboxProduk.value = []
  jumlahCeklis.value = 0
  if (e.value == null) return
  useApi().get(
    `/sysadmin/master-emr/get-map?objectruanganfk=${e.value.value}`).then((response: any) => {
      isLoading.value = false
      jumlahCeklis.value = response.data.length
      for (let x = 0; x < response.data.length; x++) {
        const element = response.data[x];
        checkboxProduk.value[element.value] = true
      }
      clickProduk()
    }).catch((e: any) => {
      isLoading.value = false
    })

  checkboxProduk.value.total = checkboxProduk.value.length
}
const klikTab = (e: any) => {
  activeTab.value = e.index
  if (activeTab.value == 0) {

  } else {
    loadTable()
  }
}
const calculateCustomerTotal = (name:any) => {
  let total = 0;

  if (dataTable.value) {
    for (let customer of dataTable.value) {
      if (customer.namaruangan === name) {
        total++;
      }
    }
  }

  return total;
};
const loadTable =async () => {
  dataTable.value = []
  await useApi().get(
    `/sysadmin/master-emr/get-map`).then((response: any) => {
      dataTable.value = response.data
      console.log(JSON.stringify(response.data));
    })
    console.log(JSON.stringify(dataTable.value));

}
listDropdown()


</script>
<style lang="scss">
@import '/@src/scss/abstracts/all';
@import '/@src/scss/components/forms-outer';


.checkbox.is-outlined.is-info.is-solid input+span::after {
  color: var(--white);
}

.form-fieldset {
  padding: 20px 0;
  max-width: 980px;
  margin: 0 auto;
}
</style>
