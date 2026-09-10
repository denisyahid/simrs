<template>
  <VCard>
    <h3>Produk</h3>
    <div class="columns is-multiline mt-2">
      <div class="column is-12">
        <div class="columns is-multiline">
          <div class="column is-3">
            <VField label="Filter">
              <VControl icon="feather:search">
                <input v-model="filters" class="input custom-text-filter" placeholder="Filter" />
              </VControl>
            </VField>
          </div>
          <div class="column is-1">
            <VField label="Limit">
              <VControl icon="feather:bar-chart">
                <input v-model="item.limit" class="input custom-text-filter" placeholder="Limit" />
              </VControl>
            </VField>
          </div>
          <div class="column is-5 mt-5">
            <VIconButton circle icon="feather:refresh-cw" raised bold @click="fetchData()" :loading="isLoading"
              color="success" class="mr-2">
            </VIconButton>

            <VButton circle icon="feather:external-link" raised bold color="info" @click="kfa()" outlined class="mr-2">KFA
              Browser (Kamus Farmasi)
            </VButton>

            <VButton circle icon="feather:external-link" raised bold color="info" @click="loinc()" outlined>LOINC Browser
              (loinc.org)
            </VButton>
          </div>
          <div class="column is-3 mt-5 " style="text-align:left">
            <VField class="switch-filter">
              <VControl>
                <InputSwitch v-model="item.obat" @change="fetchData()" />
              </VControl>
              <span class="mt-2-min ml-2 mr-4">Obat Alkes</span>
              <VControl>
                <InputSwitch v-model="item.lab" @change="fetchData()" />
              </VControl>
              <span class="mt-2-min ml-2">Laboratorium</span>
              <VControl>
                <InputSwitch v-model="item.rad" @change="fetchData()" />
              </VControl>
              <span class="mt-2-min ml-2">Radiologi</span>
            </VField>
            
          </div>
        </div>
        <div class="titi tile-grid tile-grid-v1">
          <div class="columns is-multiline">


            <div v-for="keys in 10" :key="keys" class="column is-3 " v-if="isLoading">
              <VPlaceloadWrap>
                <VPlaceloadAvatar size="medium" />
                <VPlaceloadText last-line-width="60%" class="mx-2" />
                <VPlaceload class="mx-2" disabled />
                <VPlaceload class="mx-2 h-hidden-tablet-p" />
                <VPlaceload class="mx-2 h-hidden-tablet-p" />
                <VPlaceload class="mx-2" />
              </VPlaceloadWrap>

            </div>

            <div v-for="(item, key) in dataSource" :key="key" class="column is-6" v-else>
              <div class="tile-grid-item">

                <div class="tile-grid-item-inner">
                  <VAvatar size="small" :color="listCol[key]" :initials="item.initials" />
                  <div class="meta">
                    <span class="dark-inverted">{{ item.namaproduk }}</span>
                    <!-- <span class="elip">{{ item.detailjenisproduk }}</span> -->
                    <VTag :color="item.detailjenisproduk ? 'orange' : 'grey'" :label="item.detailjenisproduk"
                      class="mb-1" />
                    <VTag :color="'info'" :label="'ID SATSET ' + item.ihs_id" v-if="item.ihs_id" class="mb-1 ml-1" />

                  </div>
                  <div style="display: inline-flex;    position: relative; margin-left: auto;">
                    <VIconButton circle spaced right icon="fas fa-edit" raised bold @click="edit(item)"
                      :loading="item.loading" color="info" outlined v-tooltip.bubble="'Edit Data'">
                    </VIconButton>
                    <VIconButton circle spaced right icon="fas fa-paper-plane" raised bold @click="syncSATUSEHAT(item)"
                      :loading="item.loading" color="danger" class="ml-2" outlined v-if="item.ihs_id == null"
                      v-tooltip.bubble="'Mapping Medication'">
                    </VIconButton>
                  </div>
                </div>

                <div class="p-datatable p-component p-datatable-responsive-scroll p-datatable-small mt-2">
                  <div class="p-datatable-wrapper">
                    <table class="w-100 p-datatable-table">
                      <thead class="p-datatable-thead">
                        <tr>
                          <td class="f-1 tab-cus-h">Kode LOINC</td>
                          <td class="f-1 tab-cus-h">Nama LOINC</td>
                          <td class="f-1 tab-cus-h">Kode KFA (PA)</td>
                          <td class="f-1 tab-cus-h">Product Template/Kode KFA (PV)</td>
                          <td class="f-1 tab-cus-h">Bahan Baku Aktif/Zat Aktif/Kode KFA (PA)</td>

                        </tr>
                      </thead>
                      <tbody class="p-datatable-body">
                        <tr v-if="item.ihs_loinc_id != null
                          ||
                          item.ihs_loinc_common_name != null
                          ||
                          item.ihs_kfa_code != null
                          ||
                          item.ihs_kfa_code_brand != null
                          ||
                          item.ihs_kfa_code_kemasan != null">
                          <td class="f-1 tab-cus" style="height:20px">{{ item.ihs_loinc_id }}</td>
                          <td class="f-1 tab-cus">{{ item.ihs_loinc_common_name }} </td>
                          <td class="f-1 tab-cus">{{ item.ihs_kfa_code }}</td>
                          <td class="f-1 tab-cus">{{ item.ihs_kfa_code_brand }}</td>
                          <td class="f-1 tab-cus">{{ item.ihs_kfa_code_kemasan }}</td>
                        </tr>
                        <tr v-else style="text-align:center">
                          <td class="f-1 tab-cus" style="height:20px;" colspan="5">Data Belum dimapping</td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

    </div>
  </VCard>
  <Dialog v-model:visible="modalInput" modal header="Edit " :style="{ width: '50vw' }" maximizable>
    <div class="columns is-multiline">
      <div class="column is-12">
        <VField label="Nama Produk">
          <VControl icon="feather:bookmark">
            <VInput type="text" v-model="item.namaproduk" placeholder="Nama Produk" class="is-rounded" disabled />
          </VControl>
        </VField>
      </div>
      <div class="column is-12">
        <VField>
          <VLabel>Kode Loinc </VLabel>
          <VControl icon="feather:bookmark">
            <VInput type="text" v-model="item.ihs_loinc_id" placeholder="Kode Loinc" class="is-rounded" />
          </VControl>
        </VField>
      </div>
      <div class="column is-12">
        <VField>
          <VLabel>Nama Loinc </VLabel>
          <VControl icon="feather:bookmark">
            <VInput type="text" v-model="item.ihs_loinc_common_name" placeholder="Nama Loinc" class="is-rounded" />
          </VControl>
        </VField>
      </div>
      <div class="column is-12">
        <VField class="is-rounded-select is-autocomplete-select">
          <VLabel>KFA </VLabel>
          <VControl icon="feather:search" class="prime-auto-select">
            <AutoComplete v-model="item.kfa" :suggestions="d_kfa" @complete="fetchProduk($event)" :optionLabel="'name'"
              :dropdown="true" :minLength="3" class="is-rounded" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
              :field="'name'" placeholder="ketik untuk mencari..." @item-select="changeProduk(item.kfa)">
              <template #option="slotProps">
                <!-- <table class="w-100">
                 <tr>
                  <th style="width: 200px;">Name</th>
                  <th style="width: 80px;">KFA</th>
                  <th style="width: 80px;">Product Template</th>
                 </tr>
                 <tr>
                  <td style="width: 200px;">{{ slotProps.option.name }}</td>
                  <td style="width: 80px;">{{ slotProps.option.kfa_code }}</td>
                  <td style="width: 80px;">{{ slotProps.option.product_template.kfa_code }}</td>
                 </tr>
                </table> -->
                <div class="columns is-multiline">
                  <div class="column is-12">
                    <span style="font-weight:bold">{{ slotProps.option.name }}</span>
                  </div>
                  <div class="column is-12 mt-5-min">
                    <!-- KFA Code <b>{{ slotProps.option.kfa_code }}</b> -->

                    <table style="width:50%">
                      <tr>
                        <td style="width: 30%;">KFA Code </td>
                        <td style="width: 5%;">: </td>
                        <td><b>{{ slotProps.option.kfa_code }}</b></td>
                      </tr>
                      <tr>
                        <td>Product Template </td>
                        <td>: </td>
                        <td><b>{{ slotProps.option.product_template.kfa_code + ' - ' +
                          slotProps.option.product_template.name }}</b></td>
                      </tr>
                      <tr>
                        <td>Dosage Form </td>
                        <td>: </td>
                        <td><b>{{ slotProps.option.dosage_form.code + ' - ' + slotProps.option.dosage_form.name }}</b>
                        </td>
                      </tr>
                      <tr>
                        <td>Ingredients </td>
                        <td>: </td>
                        <td>
                          <table style="width:100%">
                            <tr>
                              <th>KFA Code</th>
                              <th>Zat Aktif</th>
                              <th>Kekuatan</th>

                            </tr>
                            <tr v-for="itemsz in slotProps.option.active_ingredients">
                              <td>{{ itemsz.kfa_code }}</td>
                              <td>{{ itemsz.zat_aktif }}</td>
                              <td>{{ itemsz.kekuatan_zat_aktif }}</td>
                            </tr>
                          </table>
                        </td>
                      </tr>
                    </table>
                  </div>
                </div>
              </template>
            </AutoComplete>
          </VControl>
        </VField>
        <VField>
          <VLabel>Kode KFA (PA) </VLabel>
          <VControl icon="feather:bookmark">
            <VInput type="text" v-model="item.ihs_kfa_code" placeholder="Kode KFA (PA)" class="is-rounded" />
          </VControl>
        </VField>
      </div>
      <div class="column is-12">
        <VField>
          <VLabel>Product Template/Kode KFA (PV) </VLabel>
          <VControl icon="feather:bookmark">
            <VInput type="text" v-model="item.ihs_kfa_code_brand" placeholder="Product Template/Kode KFA (PV)"
              class="is-rounded" />
          </VControl>
        </VField>
      </div>
      <div class="column is-12">
        <VField>
          <VLabel>Bahan Baku Aktif/Zat Aktif/Kode KFA (PA) </VLabel>
          <VControl icon="feather:bookmark">
            <VInput type="text" v-model="item.ihs_kfa_code_kemasan" placeholder="Bahan Baku Aktif/Zat Aktif/Kode KFA (PA)"
              class="is-rounded" />
          </VControl>
        </VField>
      </div>
      <div class="column is-12">
        <VField>
          <VLabel>Kode Sediaan </VLabel>
          <VControl icon="feather:bookmark">
            <VInput type="text" v-model="item.ihs_sediaan" placeholder="Kode Sediaan" class="is-rounded" />
          </VControl>
        </VField>
      </div>
    </div>

    <template #footer>
      <VButton icon="lnir lnir-arrow-left rem-100" light dark-outlined @click="modalInput = false">
        Tutup
      </VButton>
      <VButton icon="feather:plus" @click="save()" :loading="isLoading" color="primary" raised>Simpan</VButton>
    </template>
  </Dialog>
</template>
<script setup lang="ts">
import { useRoute, useRouter } from 'vue-router'
import { ref, computed, watch, reactive } from 'vue'
import * as H from '/@src/utils/appHelper'
import { useApi } from '/@src/composable/useApi'
import { useHead } from '@vueuse/head'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import TabView from 'primevue/tabview';
import TabPanel from 'primevue/tabpanel';
import { FilterMatchMode } from 'primevue/api'
import InputSwitch from 'primevue/inputswitch'
import { useThemeColors } from '/@src/composable/useThemeColors'
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import ColumnGroup from 'primevue/columngroup';   // optional
import Row from 'primevue/row';
import AutoComplete from 'primevue/autocomplete';
import Dialog from 'primevue/dialog';
// import MasterRuangan from '../../sysadmin/master-ruangan.vue'
useHead({
  title: import.meta.env.VITE_PROJECT,
})
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)
const activeIdx: any = ref(0)
const d_departemens = ref([])
const d_Subspesialis: any = ref([])
const isLoading = ref(false)
const dataSource: any = ref([])
const dataSource2 = ref([])
const filters = ref('')
const filtersRu = ref('')
const d_kfa: any = ref([])
const item: any = ref({
  limit: 100
})
const modalInput = ref(false)
const modalInput2 = ref(false)
let listColor: any = ref(Object.keys(useThemeColors()))
const listCol = ref([])

for (let x = 0; x < 1000; x++) {
  for (let z = 0; z < listColor.value.length; z++) {
    const element = listColor.value[z];
    listCol.value.push(element)
  }
}

const dataSourcefiltered: any = computed(() => {
  if (!filters.value) {
    return dataSource.value
  }
  return dataSource.value.filter((items: any) => {
    return (
      items.namaproduk.match(new RegExp(filters.value, 'i'))
    )
  })
})

async function fetchData() {
  isLoading.value = true
  let limit = ''
  if (item.value.limit) {
    limit = '&limit=' + item.value.limit
  }
  let namaproduk = ''
  if (filters.value) {
    namaproduk = '&namaproduk=' + filters.value
  }
  const response = await useApi().get(
    '/sysadmin/master-produk-satset?obat='
    + (item.value.obat == true ? true : '')
    + '&lab=' + (item.value.lab == true ? true : '')
    + '&rad=' + (item.value.rad == true ? true : '')
    + '&nonobat=' + (item.value.nonobat == true ? true : '' + limit + namaproduk)
  )
  isLoading.value = false
  for (let x = 0; x < response.data.length; x++) {
    const element = response.data[x];
    element.no = x + 1
    let ini = element.namaproduk.split(' ')
    let init = element.namaproduk.substr(0, 1)
    if (ini.length > 1) {
      init = init + ini[1].substr(0, 1)
    }
    element.initials = init
  }
  dataSource.value = response.data
}

async function save() {

  let objSave =
  {
    'id': item.value.id,
    'ihs_loinc_id': item.value.ihs_loinc_id ? item.value.ihs_loinc_id : null,
    'ihs_loinc_common_name': item.value.ihs_loinc_common_name ? item.value.ihs_loinc_common_name : null,
    'ihs_kfa_code': item.value.ihs_kfa_code ? item.value.ihs_kfa_code : null,
    'ihs_kfa_code_brand': item.value.ihs_kfa_code_brand ? item.value.ihs_kfa_code_brand : null,
    'ihs_kfa_code_kemasan': item.value.ihs_kfa_code_kemasan ? item.value.ihs_kfa_code_kemasan : null,
    'response_kfa': item.value.kfa ? item.value.kfa : null,
    'ihs_sediaan': item.value.ihs_sediaan ? item.value.ihs_sediaan : null,
    'ihs_sediaan_display': item.value.ihs_sediaan_display ? item.value.ihs_sediaan_display : null,

  }
  isLoading.value = true
  await useApi().post(
    `/sysadmin/update-produk`, objSave).then((response: any) => {
      isLoading.value = false
      clear()
      fetchData()
    }, (error) => {
      isLoading.value = false
    })
}

const add = () => {
  clear()
  modalInput.value = true
}

const clear = () => {
  item.value.id = ''
  delete item.value.namaproduk
  delete item.value.ihs_loinc_id
  delete item.value.ihs_loinc_common_name
  delete item.value.ihs_kfa_code
  delete item.value.ihs_kfa_code_brand
  delete item.value.ihs_kfa_code_kemasan
  delete item.value.kfa
  delete item.value.ihs_sediaan
  delete item.value.ihs_sediaan_display
  modalInput.value = false
}

const edit = async (e: any) => {

  item.value.id = e.id
  item.value.namaproduk = e.namaproduk
  item.value.ihs_loinc_id = e.ihs_loinc_id
  item.value.ihs_loinc_common_name = e.ihs_loinc_common_name
  item.value.ihs_kfa_code = e.ihs_kfa_code
  item.value.ihs_kfa_code_brand = e.ihs_kfa_code_brand
  item.value.ihs_kfa_code_kemasan = e.ihs_kfa_code_kemasan
  item.value.ihs_sediaan = e.ihs_sediaan
  item.value.ihs_sediaan_display = e.ihs_sediaan_display
  if (e.response_kfa != null) {
    e.response_kfa = JSON.parse(e.response_kfa)
    let json = {
      "url": `products/all?page=1&size=20&product_type=farmasi&template_code=${e.response_kfa.product_template.kfa_code}`,
      "method": "GET",
      "data": null,
      "jenis": "kfa"
    }
    const response = await useApi().postSATUSEHAT(`/bridging/satusehat/tools`, json)

    d_kfa.value = response.items.data

    for (let x = 0; x < d_kfa.value.length; x++) {
      const element = d_kfa.value[x];

      if (element.kfa_code == e.response_kfa.kfa_code) {
        item.value.kfa = element
        break
      }
    }

  }

  modalInput.value = true

}

const syncSATUSEHAT = async (e: any) => {
  e.loading = true
  let json = {
    "id": e.id
  }
  await useApi().postSATUSEHAT(
    `/bridging/satusehat/Medication`, json).then((response: any) => {
      e.ihs_id = response.id
      fetchData()
      e.loading = false
    }, (error) => {
      e.loading = false

    })
}
const fetchProduk = async (filter: any) => {

  let json = {
    "url": `products/all?page=1&size=100&product_type=farmasi&keyword=${encodeURI(filter.query)}`,
    "method": "GET",
    "data": null,
    "jenis": "kfa"
  }
  const response = await useApi().postSATUSEHAT(`/bridging/satusehat/tools`, json)
  if (response.total > 0) {
    d_kfa.value = response.items.data
  }


}
const changeProduk = (e: any) => {
  item.value.ihs_kfa_code = e.kfa_code
  item.value.ihs_kfa_code_brand = e.product_template.kfa_code
  item.value.ihs_kfa_code_kemasan = e.active_ingredients[0].kfa_code
  item.value.ihs_sediaan = e.dosage_form.code
  item.value.ihs_sediaan_display = e.dosage_form.name
}

const loinc = () => {
  window.open('https://loinc.org/search/?t=1&s=', '_blank')
}
const kfa = () => {
  window.open('https://satusehat.kemkes.go.id/kfa-browser/', '_blank')
}
fetchData()
</script>

<style lang="scss">
@import '/@src/scss/abstracts/all';

@import '/@src/scss/components/forms-outer';
@import '/@src/scss/custom/config';
@import '/@src/scss/module/sysadmin/master-data.scss';

.elip {
  width: 120px !important;
  white-space: nowrap;
  overflow: hidden !important;
  text-overflow: ellipsis;
  display: block;
}

.elip2 {
  width: 180px !important;
  white-space: nowrap;
  overflow: hidden !important;
  text-overflow: ellipsis;
  display: block;
}

.titi.tile-grid-v1 .tile-grid-item.is-success .tile-grid-item-inner .meta span:first-child {
  color: var(--white);
}

.titi.tile-grid-v1 .tile-grid-item.is-success .tile-grid-item-inner .meta span:nth-child(2) {
  color: var(--white);
}

.f-1 {
  font-size: 0.8rem;
}

.tab-cus {
  text-align: left;
  border: 1px solid #e9ecef;
  border-width: 0 0 1px 0;
}

.tab-cus-h {

  text-align: left;
  padding: 0.3rem 0.3rem;
  border: 1px solid #e9ecef;
  border-width: 0 0 1px 0;
  font-weight: 600;
  color: #495057;
  background: #f8f9fa;
  transition: box-shadow 0.2s;
}
</style>
