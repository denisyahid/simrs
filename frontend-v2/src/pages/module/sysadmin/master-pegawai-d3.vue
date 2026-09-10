<template>
  <VCard radius="rounded">
    <div class="columns column">
      <h3 class="title is-5 mb-2 mr-1">Pegawai Minimal D3 </h3> <span> ( {{ ds_PEGAWAI.total }}
        Results)</span>
    </div>
    <div class="columns  all-projects m-3 mt-0">
      <div class="columns is-multiline  projects-card-grid">
        <div class="column is-9">
          <div class="column is-12" v-if="ds_PEGAWAI.loading">
            <div class="flex-table-item grid-item mb-1" v-for="key in 5" :key="key">
              <VPlaceloadWrap>
                <VPlaceloadAvatar size="small" />
                <VPlaceloadText last-line-width="60%" class="mx-2" />
                <VPlaceload class="mx-2" disabled />
                <VPlaceload class="mx-2 h-hidden-tablet-p" />
                <VPlaceload class="mx-2 h-hidden-tablet-p" />
                <VPlaceload class="mx-2" />
              </VPlaceloadWrap>
            </div>
          </div>
          <div class="flex-list-inner" v-else-if="ds_PEGAWAI.length == 0">
            <VPlaceholderSection title="Not found" subtitle="There is no data that match your query." class="my-6">
              <template #image>
                <img class="light-image" src="/@src/assets/illustrations/placeholders/search-4.svg" alt="" />
                <img class="dark-image" src="/@src/assets/illustrations/placeholders/search-4-dark.svg" alt="" />
              </template>
            </VPlaceholderSection>
          </div>
          <div v-else-if="ds_PEGAWAI.length > 0">
            <VFlexTable v-if="ds_PEGAWAI.length" :data="ds_PEGAWAI" :columns="columns" rounded>
              <template #body>
                <div name="list" tag="div" class="flex-list-inner">
                  <div v-for="(item, i)  in ds_PEGAWAI" :key="item.id" class="flex-table-item">
                    <VFlexTableCell :column="{ media: true }">
                      <VAvatar size="small" :color="listColor[i]" :initials="item.initials" />
                      <div>
                        <span class="item-name dark-inverted"
                          style=" width: 200px;  white-space: nowrap; overflow: hidden !important; text-overflow: ellipsis;">
                          {{ item.namalengkap }}
                        </span>
                        <span class="item-meta">
                          <span>
                            <i aria-hidden="true" class="iconify" data-icon="feather:user">
                            </i>{{ item.nippns }}</span>
                        </span>
                      </div>
                    </VFlexTableCell>
                    <VFlexTableCell>
                      <span class="light-text">{{ item.jenispegawai }}</span>
                    </VFlexTableCell>
                    <VFlexTableCell>
                      <span class="light-text is-pulled-right">{{ item.pendidikan }}</span>
                    </VFlexTableCell>
                  </div>
                </div>
              </template>
            </VFlexTable>
          </div>
          <VFlexPagination v-model:current-page="currentPage.page" :item-per-page="currentPage.limit"
            :total-items="ds_PEGAWAI.total" :max-links-displayed="5">
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
        <div class="column is-3">
          <div class="columns is-multiline">
            <div class="column is-12">
              <VField>
                <VControl icon="feather:search">
                  <input v-model="item.qnama" v-on:keyup.enter="filter()" type="text" class="input is-rounded"
                    placeholder="Filter Nama..." />
                </VControl>
              </VField>
            </div>
            <div class="column is-6">
              <h3 class="title is-5 mb-2 mr-1">Filter </h3>
            </div>
            <div class="column is-6">
              <a @click="clearFilter()" type="button" class="is-pulled-right mr-3" color="info" outlined raised> Kembali
              </a>
            </div>
            <div class="column is-12">
              <VField>
                <VLabel>NIK</VLabel>
                <VControl icon="feather:book">
                  <VInput type="text" v-model="item.qnik" v-on:keyup.enter="filter()" placeholder="NIK" />
                </VControl>
              </VField>
            </div>
            <div class="column is-12">
              <VButton @click="filter()" :loading="ds_PEGAWAI.loading" type="button" icon="feather:search"
                class="is-fullwidth mr-3" color="info" raised> Cari Data
              </VButton>
            </div>
          </div>
        </div>
      </div>
    </div>
  </VCard>
</template>
<route lang="yaml">
meta:
  requiresAuth: true
</route>
<script setup lang="ts">
import { useWindowScroll } from '@vueuse/core'
import { useApi } from '/@src/composable/useApi'
import { ref, computed, watch } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useHead } from '@vueuse/head'
import { useToaster } from '/@src/composable/toaster'
import { useThemeColors } from '/@src/composable/useThemeColors'
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import { FilterMatchMode, FilterOperator } from "primevue/api";
import { useViewWrapper } from '/@src/stores/viewWrapper'
useHead({
  title: 'Pegawai - ' + import.meta.env.VITE_PROJECT,
})

useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)
const listColor: any = ref(Object.keys(useThemeColors()))
const total = ref(0)
const date = ref(new Date())
const item: any = {}
let ds_PEGAWAI: any = ref([])
let dataSelect: any = ref({})
const modalRiw = ref(false)
const dataSourceRiwayat: any = ref([])
const route = useRoute()
const router = useRouter()
const { y } = useWindowScroll()
const filters = ref({
  global: { value: null, matchMode: FilterMatchMode.CONTAINS },

});
const isStuck = computed(() => {
  return y.value > 30
})
let columns: any = ref({})
columns.value = {
  pasien: {
    label: 'Pegawai',
    grow: true,
    media: true,
  },
  tglsbm: 'Jenis Pegawai',
  totaldibayar: {
    label: 'Pendidikan Terakhir',
    cellClass: 'h-hidden-tablet-p',
  }
}
const currentPage: any = ref({
  limit: 5,
  rows: 50
})
for (var i = listColor.value.length - 1; i >= 0; i--) {
  const element = listColor.value[i];
  if (element == 'primary') {
    listColor.value.splice(i, 1);
  }
}
currentPage.value.page = computed(() => {
  try {
    return Number.parseInt(route.query.page as string) || 1
  } catch { }
  return 1
})
watch(currentPage.value, () => {
  fetchPegawai()
})

async function fetchPegawai() {
  ds_PEGAWAI.value.loading = true

  let searchQuery = `&q=`
  let limit: any = currentPage.value.limit
  let offset: any = route.query.page ? route.query.page : 1
  offset = (offset * limit) - limit
  let namalengkap = ''
  let jenispegawai = ''
  let nobpjs = ''
  let nik = ''
  let alamat = ''
  if (item.qnama) namalengkap = `&namalengkap=${item.qnama}`
  if (item.qjenis) jenispegawai = `&objectjenispegawaifk=${item.qjenis}`
  if (item.qnik) nik = `&noidentitas=${item.qnik}`
  if (item.qbpjs) nobpjs = `&nobpjs=${item.qbpjs}`
  if (item.qalamat) alamat = `&alamat=${item.qalamat}`
  const response = await useApi().get(`/sysadmin/master-pegawai-d3?offset=${offset}&limit=${limit}&rows=${currentPage.value.rows}${namalengkap}${nik}${nobpjs}${alamat}`)
  for (let x = 0; x < response.data.length; x++) {
    const element = response.data[x];
    let ini = element.namalengkap.split(' ')
    let init = element.namalengkap.substr(0, 1)
    if (ini.length > 1) {
      init = init + ini[1].substr(0, 1)
    }
    element.initials = init
  }
  ds_PEGAWAI.value.loading = false
  ds_PEGAWAI.value = response.data
  ds_PEGAWAI.value.total = response.total
  route.query.page = '1'
}


// async function listDropdown() {
//   const response = await useApi().get(
//     `/registrasi/list-dropdown`)
//   listJK.value = []
//   for (let x = 0; x < response.jk.length; x++) {
//     const element = response.jk[x];
//     if (element.jeniskelamin != '-') {
//       listJK.value.push(element)
//     }
//   }
//   listAgama.value = response.agama.map((e: any) => { return { label: e.agama, value: e.id } })
//   listGolonganDarah.value = response.golongandarah.map((e: any) => { return { label: e.golongandarah, value: e.id } })
//   listStatusPerkawinan.value = response.statusperkawinan.map((e: any) => { return { label: e.statusperkawinan, value: e.id } })
//   listPendidikan.value = response.pendidikan.map((e: any) => { return { label: e.pendidikan, value: e.id } })
//   listPekerjaan.value = response.pekerjaan.map((e: any) => { return { label: e.pekerjaan, value: e.id } })
//   listEtnis.value = response.etnis.map((e: any) => { return { label: e.suku, value: e.id } })
//   listKebangsaan.value = response.kebangsaan.map((e: any) => { return { label: e.name, value: e.id } })
//   listNegara.value = response.negara.map((e: any) => { return { label: e.namanegara, value: e.id } })

// }
// function savePasien() {
//   if (!item.nik) {
//     useToaster().error('NIK harus di isi')
//     return
//   }
//   if (!item.nobpjs) {
//     useToaster().error('No BPJS harus di isi')
//     return
//   }
//   if (!item.namalengkap) {
//     useToaster().error('Nama harus di isi')
//     return
//   }
// }
function resetForm() {

}
function editPegawai(e: any) {
  router.push({
    name: 'module-sysadmin-pegawai-baru',
    query: {
      id: e.id,
    },
  })
}
function hapusPegawai(e: any) {
  useApi().post(
    `/sysadmin/delete-pegawai`, { 'id': e.id }).then((response: any) => {
      fetchPegawai()
    }).catch((e: any) => {

    })
}
function detailPegawai(e: any) {
  dataSelect.value = e
  router.push({
    name: 'module-sysadmin-detail-pegawai',
    query: {
      id: e.id,
    },
  })
  modalRiw.value = true
  dataSourceRiwayat.value.loading = true
  useApi().get(
    `/sysadmin/master-pegawai?id=${e.id}`).then((response: any) => {
      for (let x = 0; x < response.length; x++) {
        const element = response[x];
        element.no = x + 1
      }
      dataSourceRiwayat.value = response
      dataSourceRiwayat.value.loading = false
    }).catch((e: any) => {

    })
}

function clearFilter() {
  delete item.qnama
  delete item.qnik
  delete item.qbpjs
  delete item.qalamat
  fetchPegawai()
}
function filter() {
  fetchPegawai()
}
fetchPegawai()


</script>
<style lang="scss">
@import '/@src/scss/abstracts/all';

.all-projects .projects-card-grid .grid-item {
  min-height: 20px !important;
}

@import '/@src/scss/components/forms-outer';
@import '/@src/scss/custom/config';
@import '/@src/scss/module/sysadmin/m-rekanan.scss';
</style>
