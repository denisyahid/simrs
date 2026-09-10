<template>
  <VCard>
    <h3>Tenaga Kesehatan (Practitioner)</h3>
    <div class="columns is-multiline">
      <div class="column is-12">
        <div class="columns is-multiline">
          <div class="column is-3">
            <VField>
              <VControl icon="feather:search">
                <input v-model="filters" class="input custom-text-filter" placeholder="Filter" />
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
              <div class="content mr-5">
                <div class="is-divider mt-0 mb-2" data-content="Info Warna"></div>
                <VTag color="success" label="Sudah Mapping" />
                <VTag color="grey" label="Belum Mapping" class="ml-3 mt-3" outlined />
              </div>
              <VControl>
                <InputSwitch v-model="item.aktif" @change="fetchData()" />
              </VControl>
              <span class="mt-2-min ml-2">Filter Sudah Mapping</span>
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
            <div v-for="(item, key) in dataSourcefiltered" :key="key" class="column is-4" v-else>
              <div class="tile-grid-item" :class="item.ihs_id != null? 'is-success':''">
                <div class="tile-grid-item-inner">
                  <VAvatar size="small" :color="listCol[key]" :initials="item.initials" />
                  <div class="meta">
                    <span class="dark-inverted">{{ item.namalengkap }}</span>
                    <span class="elip">{{ item.jenispegawai }}</span>
                    <VTag  :color="item.noidentitas ? 'orange' : 'grey'"
                      :label="item.noidentitas" class="mb-1" />
                    <!-- <span class="elip2 " style="color: var(--light-text);">NIK :  {{  item.noidentitas?item.noidentitas:'-' }}</span> -->
                    <!-- <VTag v-if="item.ihs_id" :color="item.ihs_id ? 'success' : 'secondary'"
                      :label="item.ihs_id ? 'Sudah Mapping' : ''" /> -->
                  </div>
                  <VIconButton circle  spaced right icon="fas fa-paper-plane" raised bold style="display: inline-flex;    position: relative;
    margin-left: auto;" @click="syncSATUSEHAT(item)" :loading="item.loading" color="danger" v-if="!item.ihs_id"
                    v-tooltip.bubble="'Mapping Data ke SATUSEHAT'"
                    :class="item.ihs_id != null? '':'is-outlined'">
                  </VIconButton>
                  <VIconButton circle  spaced right icon="fas fa-edit" raised bold style="display: inline-flex;    position: relative;
    margin-left: auto;" @click="edit(item)" :loading="item.loading" color="warning" v-if="item.ihs_id"
    :class="item.ihs_id != null? '':'is-outlined'"
                    v-tooltip.bubble="'Edit ID SATUSEHAT'">
                  </VIconButton>
                  <VIconButton circle outlined spaced right icon="fas fa-address-card" raised bold style="display: inline-flex;    position: relative;
    margin-left: auto;" @click="edit(item)" :loading="item.loading" color="purple" v-if="item.noidentitas == null"
                    class="ml-2" v-tooltip.bubble="'ISI NIK PEGAWAI'">
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
  </VCard>

  <VModal :open="modalInput" title="Edit Pegawai" actions="right" @close="modalInput = false">
    <template #content>
      <form class="modal-form">
        <div class="columns is-multiline">
          <div class="column is-12">
            <VField label="Nama Pegawai">
              <VControl icon="feather:bookmark">
                <VInput type="text" v-model="item.namalengkap" placeholder="Nama Pegawai" class="is-rounded" disabled />
              </VControl>
            </VField>
          </div>
          <div class="column is-12">
            <VField>
              <VLabel class="required-field">NIK </VLabel>
              <VControl icon="feather:bookmark">
                <VInput type="text" v-model="item.noidentitas" placeholder="NIK" class="is-rounded" />
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
import { useThemeColors } from '/@src/composable/useThemeColors'
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
      items.namalengkap.match(new RegExp(filters.value, 'i'))
      ||   items.jenispegawai.match(new RegExp(filters.value, 'i'))
    )
  })
})

async function fetchData() {
  isLoading.value = true
  const response = await useApi().get(
    '/sysadmin/master-pegawai-satset?ihs_id=' + (item.value.aktif == true ? true : '')
  )
  isLoading.value = false
  for (let x = 0; x < response.data.length; x++) {
    const element = response.data[x];
    element.no = x + 1
    let ini = element.namalengkap.split(' ')
    let init = element.namalengkap.substr(0, 1)
    if (ini.length > 1) {
      init = init + ini[1].substr(0, 1)
    }
    element.initials = init
  }
  dataSource.value = response.data
}

async function save() {
  if (!item.value.noidentitas) {
    H.alert('error', 'NIK harus di isi')
    return
  }
  let objSave =
  {
    'id': item.value.id,
    'ihs_id': item.value.ihs_id ? item.value.ihs_id : null,
    'noidentitas': item.value.noidentitas ? item.value.noidentitas : null,
  }
  isLoading.value = true
  await useApi().post(
    `/sysadmin/update-pegawai`, objSave).then((response: any) => {
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
  item.value.namalengkap = ''
  item.value.ihs_id = ''
  modalInput.value = false
}

const edit = (e: any) => {

  item.value.id = e.id
  item.value.namalengkap = e.namalengkap
  item.value.ihs_id = e.ihs_id
  modalInput.value = true
}

const syncSATUSEHAT = async (e: any) => {
  e.loading = true
  let json = {
    "id_nakes": e.id
  }
  await useApi().postSATUSEHAT(
    `/bridging/satusehat/Practitioner`, json).then((response: any) => {
      e.ihs_id = response.id
      fetchData()
      e.loading = false
    }, (error) => {
      e.loading = false

    })
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
</style>
