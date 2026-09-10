<template>
  <TabView v-model:activeIndex="activeIdx">
    <TabPanel header="Instalasi (Organization)">
      <div class="columns is-multiline">
        <div class="column is-12">
          <div class="columns is-multiline">
            <div class="column is-3">
              <VField>
                <VControl icon="feather:search">
                  <input v-model="filters" class="input custom-text-filter" placeholder="Filter Ruangan" />
                </VControl>
              </VField>
            </div>
            <div class="column is-2">
              <VIconButton circle icon="feather:refresh-cw" raised bold @click="fetchData()" :loading="isLoading"
                color="success" class="mr-2">
              </VIconButton>
              <!-- <VIconButton circle icon="feather:plus" raised bold color="info" @click="add()"> </VIconButton> -->

            </div>
            <div class="column is-7 " style="text-align:left">
              <VField class="switch-filter">
                <VControl>
                  <InputSwitch v-model="item.aktif" @change="fetchData()" />
                </VControl>
                <span class="mt-2-min ml-2">Sudah Mapping</span>
              </VField>
            </div>
          </div>
          <div class="tile-grid tile-grid-v1">
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

              <div v-for="(item, key) in dataSourcefiltered" :key="key" class="column is-3" v-else>
                <div class="tile-grid-item">
                  <div class="tile-grid-item-inner">
                    <VAvatar size="medium" :picture="'/images/avatars/svg/departemen.svg'" color="primary" squared
                      bordered />
                    <div class="meta">
                      <span class="dark-inverted">{{ item.namadepartemen }}</span>

                      <VTag v-if="item.ihs_id" :color="item.ihs_id ? 'success' : 'secondary'"
                        :label="item.ihs_id ? 'Sudah Mapping' : ''" />
                    </div>
                    <VIconButton circle outlined spaced right icon="fas fa-paper-plane" raised bold style="display: inline-flex;    position: relative;
    margin-left: auto;" @click="syncSATUSEHAT(item)" :loading="item.loading" color="danger" v-if="!item.ihs_id"
                      v-tooltip.bubble="'Mapping Data ke SATUSEHAT'">
                    </VIconButton>
                    <VIconButton circle outlined spaced right icon="fas fa-edit" raised bold style="display: inline-flex;    position: relative;
    margin-left: auto;" @click="edit(item)" :loading="item.loading" color="warning" v-if="item.ihs_id"
                      v-tooltip.bubble="'Edit ID SATUSEHAT'">
                    </VIconButton>
                    <!-- <VDropdown icon="feather:more-vertical" spaced right>

                    </VDropdown> -->
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

      </div>
    </TabPanel>
    <TabPanel header="Ruangan (Location)">
      <div class="columns is-multiline">
        <div class="column is-12">
          <div class="columns is-multiline">
            <div class="column is-3">
              <VField>
                <VControl icon="feather:search">
                  <input v-model="filtersRu" class="input custom-text-filter" placeholder="Filter" />
                </VControl>
              </VField>
            </div>
            <div class="column is-2">
              <VIconButton circle icon="feather:refresh-cw" raised bold @click="fetchData2()" :loading="isLoading"
                color="success" class="mr-2">
              </VIconButton>
              <!-- <VIconButton circle icon="feather:plus" raised bold color="info" @click="add2()"> </VIconButton> -->
            </div>
            <div class="column is-7 " style="text-align:left">
              <VField class="switch-filter">
                <VControl>
                  <InputSwitch v-model="item.aktif2" @change="fetchData2()" />
                </VControl>
                <span class="mt-2-min ml-2">Sudah Mapping</span>
              </VField>
            </div>
          </div>
          <div class="tile-grid tile-grid-v1">
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
              <div v-for="(item, key) in dataSourcefiltered2" :key="key" class="column is-3" v-else>
                <div class="tile-grid-item">
                  <div class="tile-grid-item-inner">
                    <VAvatar size="medium" :picture="'/images/avatars/svg/room.png'" color="primary" squared bordered />
                    <div class="meta">
                      <span class="dark-inverted">{{ item.namaruangan }}</span>
                      <span style=" width: 120px !important;
  white-space: nowrap;
  overflow: hidden !important;
  text-overflow: ellipsis;
  display: block;">{{ item.namadepartemen }}</span>
                      <VTag v-if="item.ihs_id" :color="item.ihs_id ? 'success' : 'secondary'"
                        :label="item.ihs_id ? 'Sudah Mapping' : ''" />
                    </div>
                    <VIconButton circle outlined spaced right icon="fas fa-paper-plane" raised bold style="display: inline-flex;    position: relative;
    margin-left: auto;" @click="syncSATUSEHAT2(item)" :loading="item.loading" color="danger" v-if="!item.ihs_id"
                      v-tooltip.bubble="'Mapping Data ke SATUSEHAT'">
                    </VIconButton>
                    <VIconButton circle outlined spaced right icon="fas fa-edit" raised bold style="display: inline-flex;    position: relative;
    margin-left: auto;" @click="edit2(item)" :loading="item.loading" color="warning" v-if="item.ihs_id"
                      v-tooltip.bubble="'Edit ID SATUSEHAT'">
                    </VIconButton>
                    <!-- <VDropdown icon="feather:more-vertical" spaced right>

                    </VDropdown> -->
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

      </div>
    </TabPanel>
  </TabView>
  <VModal :open="modalInput2" title="Edit SATUSEHAT ID" actions="right" @close="modalInput2 = false">
    <template #content>
      <form class="modal-form">
        <div class="columns is-multiline">
          <div class="column is-12">
            <VField label="Nama Ruangan">
              <VControl icon="feather:bookmark">
                <VInput type="text" v-model="item.namaruangan" placeholder="Nama Ruangan" class="is-rounded" disabled />
              </VControl>
            </VField>
          </div>
          <div class="column is-12">
            <VField>
              <VLabel>SATUSEHAT ID</VLabel>
              <VControl icon="feather:bookmark">
                <VInput type="text" v-model="item.ihs_id" placeholder="SATUSEHAT ID" class="is-rounded" />
              </VControl>
            </VField>
          </div>
        </div>
      </form>
    </template>
    <template #action>
      <VButton icon="feather:plus" @click="save2()" :loading="isLoading" color="primary" raised>Simpan</VButton>
    </template>
  </VModal>
  <VModal :open="modalInput" title="Edit SATUSEHAT ID" actions="right" @close="modalInput = false">
    <template #content>
      <form class="modal-form">
        <div class="columns is-multiline">
          <div class="column is-12">
            <VField label="Nama Depatemen">
              <VControl icon="feather:bookmark">
                <VInput type="text" v-model="item.namadepartemen" placeholder="Nama Depatemen" class="is-rounded"
                  disabled />
              </VControl>
            </VField>
          </div>
          <div class="column is-12">
            <VField>
              <VLabel>SATUSEHAT ID</VLabel>
              <VControl icon="feather:bookmark">
                <VInput type="text" v-model="item.ihs_id" placeholder="SATUSEHAT ID" class="is-rounded" />
              </VControl>
            </VField>
          </div>
        </div>
      </form>
    </template>
    <template #action>
      <VButton icon="feather:plus" @click="save()" :loading="isLoading" color="primary" raised>Simpan</VButton>
    </template>
  </VModal>
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
const dataSource = ref([])
const dataSource2 = ref([])
const filters = ref('')
const filtersRu = ref('')
const item: any = ref({})
const modalInput = ref(false)
const modalInput2 = ref(false)
const dataSourcefiltered: any = computed(() => {
  if (!filters.value) {
    return dataSource.value
  }

  return dataSource.value.filter((items: any) => {
    return (
      items.namadepartemen.match(new RegExp(filters.value, 'i'))
    )
  })
})

const dataSourcefiltered2: any = computed(() => {
  if (!filtersRu.value) {
    return dataSource2.value
  }

  return dataSource2.value.filter((items: any) => {
    return (
      items.namaruangan.match(new RegExp(filtersRu.value, 'i'))
      ||
      items.namadepartemen.match(new RegExp(filtersRu.value, 'i'))
    )
  })
})

async function fetchData() {
  isLoading.value = true

  const response = await useApi().get(
    '/sysadmin/master-departemen?statusenabled=true&ihs_id=' + (item.value.aktif == true ? true : '')
  )
  isLoading.value = false
  for (let x = 0; x < response.data.length; x++) {
    const element = response.data[x];
    element.no = x + 1
  }

  dataSource.value = response.data
}
async function fetchData2() {
  isLoading.value = true

  const response = await useApi().get(
    '/sysadmin/master-ruangan?statusenabled=true&ihs_id=' + (item.value.aktif2 == true ? true : '')
  )
  isLoading.value = false
  for (let x = 0; x < response.data.length; x++) {
    const element = response.data[x];
    element.no = x + 1
  }

  dataSource2.value = response.data
}




async function save() {
  let objSave =
  {
    'id': item.value.id,
    'ihs_id': item.value.ihs_id ? item.value.ihs_id : null,
  }

  isLoading.value = true
  await useApi().post(
    `/sysadmin/update-master-departemen`, objSave).then((response: any) => {
      isLoading.value = false
      clear()
      fetchData()
    }, (error) => {
      isLoading.value = false

    })
}

async function save2() {
  let objSave =
  {
    'id': item.value.id,
    'ihs_id': item.value.ihs_id ? item.value.ihs_id : null,
  }

  isLoading.value = true
  await useApi().post(
    `/sysadmin/update-master-ruangan`, objSave).then((response: any) => {
      isLoading.value = false
      clear()
      fetchData2()
    }, (error) => {
      isLoading.value = false

    })
}

const add = () => {
  clear()
  modalInput.value = true
}
const add2 = () => {
  clear()
  modalInput2.value = true
}
const clear = () => {

  item.value.id = ''
  item.value.namaruangan = ''
  item.value.ihs_id = ''

  item.value.id = ''
  item.value.namaruangan = ''
  item.value.namadepartemen = ''
  item.value.ihs_id = ''
  delete item.value.iocns

  modalInput.value = false
}

const edit = (e: any) => {

  item.value.id = e.id
  item.value.namadepartemen = e.namadepartemen
  item.value.ihs_id = e.ihs_id

  modalInput.value = true
}

const edit2 = (e: any) => {

  item.value.id = e.id
  item.value.namaruangan = e.namaruangan
  item.value.ihs_id = e.ihs_id

  modalInput2.value = true
}
const syncSATUSEHAT = async (e: any) => {
  e.loading = true
  let json = {
    "id_dept": e.id
  }
  await useApi().postSATUSEHAT(
    `/bridging/satusehat/Organization`, json).then((response: any) => {
      e.ihs_id = response.id
      e.loading = false
    }, (error) => {
      e.loading = false

    })
}

const syncSATUSEHAT2 = async (e: any) => {
  e.loading = true
  let json = {
    "id_ru": e.id
  }
  await useApi().postSATUSEHAT(
    `/bridging/satusehat/Location`, json).then((response: any) => {
      e.ihs_id = response.id
      e.loading = false
    }, (error) => {
      e.loading = false

    })
}
watch(
  () => activeIdx.value,
  (newValue, oldValue) => {
    if (newValue == 0) {
      fetchData()
    }
    if (newValue == 1) {
      fetchData2()
    }
    if (newValue == 2) {

    }
  }
)

fetchData()
</script>

<style lang="scss">
@import '/@src/scss/abstracts/all';

@import '/@src/scss/components/forms-outer';
@import '/@src/scss/custom/config';
@import '/@src/scss/module/sysadmin/master-data.scss';
</style>
