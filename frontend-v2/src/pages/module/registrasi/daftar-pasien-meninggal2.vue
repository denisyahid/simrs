
<template>
  <VCard radius="rounded">
    <div class="columns column">
      <h3 class="title is-5 mb-2 mr-1">Pasien </h3> <span> ( {{ ds_PASIEN.total }}
        Results)</span>
    </div>
    <div class="column">
      <div class="columns is-multiine">
        <div class="column is-5">
          <VField class="mt-3">
            <VDatePicker v-model="item.rangeDate" is-range color="pink" trim-weeks :max-date="new Date()">
              <template #default="{ inputValue, inputEvents }">
                <VField addons>
                  <VControl icon="feather:calendar">
                    <VInput :value="inputValue.start" v-on="inputEvents.start" />
                  </VControl>
                  <VControl>
                    <VButton static icon="feather:arrow-right" />
                  </VControl>
                  <VControl subcontrol icon="feather:calendar">
                    <VInput :value="inputValue.end" v-on="inputEvents.end" />
                  </VControl>
                </VField>
              </template>
            </VDatePicker>
          </VField>
        </div>

        <div class="column is-2">
          <VField>
            <VLabel>No Registrasi:</VLabel>
            <VControl>
              <VInput v-model="item.noReg" type="text" />
            </VControl>
          </VField>
        </div>
        <div class="column is-2">
          <VField>
            <VLabel>No RM:</VLabel>
            <VControl>
              <VInput v-model="item.noCm" type="text" />
            </VControl>
          </VField>
        </div>
        <div class="column is-2">
          <VField>
            <VLabel>Nama Pasien:</VLabel>
            <VControl>
              <VInput v-model="item.namaPasien" type="text" />
            </VControl>
          </VField>
        </div>

        <div class="column is-2">
          <button @click="filter" class="button is-primary" style="margin-top:24px;"><i class="iconify"
              data-icon="feather:search" aria-hidden="true"></i></button>
        </div>
      </div>
    </div>
    <div class="column">
      <div class="columns is-multiine">
        <table class="table is-hoverable is-fullwidth">
          <thead>
            <tr>
              <th scope="col">Tgl Meninggal</th>
              <th scope="col">No Reg</th>
              <th scope="col">No Rm</th>
              <th scope="col">Nama Pasien</th>
              <th scope="col">Penyebab Kematian</th>
              <th scope="col">Ruangan Terakhir</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="item in ds_PASIEN" :key="item.id">
              <td>{{ item.tglmeninggal }}</td>
              <td>{{ item.noregistrasi }}</td>
              <td>{{ item.nocm }}</td>
              <td>{{ item.namapasien }}</td>
              <td>{{ item.penyeb }}</td>
              <td>{{ item.namaruangan }}</td>
            </tr>
          </tbody>
        </table>
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
import { ref, reactive, computed, watch } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useHead } from '@vueuse/head'
import { useToaster } from '/@src/composable/toaster'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useThemeColors } from '/@src/composable/useThemeColors'
import * as H from '/@src/utils/appHelper'
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import { FilterMatchMode, FilterOperator } from "primevue/api";
useHead({
  title: 'Pasien - ' + import.meta.env.VITE_PROJECT,
})
useViewWrapper().setPageTitle('Pasien Lama')
useViewWrapper().setFullWidth(true)
const total = ref(0)
const date = ref(new Date())
const item: any = ref({
  rangeDate: reactive({
    start: new Date(),
    end: new Date(),
  }),
});
let listJK: any = ref([])
let listAgama: any = ref([])
let listGolonganDarah: any = ref([])
let listStatusPerkawinan: any = ref([])
let listPendidikan: any = ref([])
let listPekerjaan: any = ref([])
let listEtnis: any = ref([])
let listKebangsaan: any = ref([])
let listNegara: any = ref([])
let ds_PASIEN: any = ref([])
let listColor: any = ref(Object.keys(useThemeColors()))
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
  fetchPasien()
})

async function fetchPasien() {
  ds_PASIEN.value.loading = true;

  let searchQuery = `&q=`;

  // Append filter parameters to the searchQuery
  if (item.value.rangeDate.start) searchQuery += `&tglAwal=${H.formatDate(item.value.rangeDate.start, 'YYYY-MM-DD')}`;
  if (item.value.rangeDate.end) searchQuery += `&tglAkhir=${H.formatDate(item.value.rangeDate.end, 'YYYY-MM-DD')}`;
  if (item.noReg) searchQuery += `&noReg=${item.noReg}`;
  if (item.noCm) searchQuery += `&noCm=${item.noCm}`;
  if (item.namaPasien) searchQuery += `&namaPasien=${item.namaPasien}`;

  const { data: pasien } = await useApi().get(
    `registrasi/get-daftar-pasien-meninggal2?rows=${currentPage.value.rows}${searchQuery}`
  );

  for (let x = 0; x < pasien.length; x++) {
    const element = pasien[x];
    let ini = element.namapasien.split(' ')
    let init = element.namapasien.substr(0, 1)
    if (ini.length > 1) {
      init = init + ini[1].substr(0, 1)
    }
    element.initials = init
  }

  ds_PASIEN.value.loading = false;
  ds_PASIEN.value = pasien;
  ds_PASIEN.value.total = pasien.length;
}


async function listDropdown() {
  const response = await useApi().get(
    `/registrasi/list-dropdown`)
  listJK.value = []
  for (let x = 0; x < response.jk.length; x++) {
    const element = response.jk[x];
    if (element.jeniskelamin != '-') {
      listJK.value.push(element)
    }
  }
  listAgama.value = response.agama.map((e: any) => { return { label: e.agama, value: e.id } })
  listGolonganDarah.value = response.golongandarah.map((e: any) => { return { label: e.golongandarah, value: e.id } })
  listStatusPerkawinan.value = response.statusperkawinan.map((e: any) => { return { label: e.statusperkawinan, value: e.id } })
  listPendidikan.value = response.pendidikan.map((e: any) => { return { label: e.pendidikan, value: e.id } })
  listPekerjaan.value = response.pekerjaan.map((e: any) => { return { label: e.pekerjaan, value: e.id } })
  listEtnis.value = response.etnis.map((e: any) => { return { label: e.suku, value: e.id } })
  listKebangsaan.value = response.kebangsaan.map((e: any) => { return { label: e.name, value: e.id } })
  listNegara.value = response.negara.map((e: any) => { return { label: e.namanegara, value: e.id } })

}
function savePasien() {
  if (!item.nik) {
    useToaster().error('NIK harus di isi')
    return
  }
  if (!item.nobpjs) {
    useToaster().error('No BPJS harus di isi')
    return
  }
  if (!item.namapasien) {
    useToaster().error('Nama harus di isi')
    return
  }
}
function resetForm() {

}
function editPasien(e: any) {
  router.push({
    name: 'module-registrasi-pasien-baru',
    query: {
      id: e.id,
    },
  })
}
function hapusPasien(e: any) {
  useApi().post(
    `/registrasi/delete-pasien`, { 'id': e.id }).then((response: any) => {
      fetchPasien()
    }).catch((e: any) => {

    })
}
function riwayatPasien(e: any) {
  dataSelect.value = e
  router.push({
    name: 'module-registrasi-riwayat-registrasi',
    query: {
      nocmfk: e.id,
    },
  })
  // modalRiw.value = true
  // dataSourceRiwayat.value.loading = true
  // useApi().get(
  //   `/registrasi/riwayat-registrasi?id=${e.id}`).then((response: any) => {
  //     for (let x = 0; x < response.length; x++) {
  //       const element = response[x];
  //       element.no = x + 1
  //     }
  //     dataSourceRiwayat.value = response
  //     dataSourceRiwayat.value.loading = false
  //   }).catch((e: any) => {

  //   })
}
function detailPasien(e: any) {

}
function registrasi(e: any) {
  router.push({
    name: 'module-registrasi-registrasi-ruangan',
    query: {
      nocmfk: e.id,
    },
  })
}
function clearFilter() {
  delete item.qnama
  delete item.qnocm
  delete item.qnik
  delete item.qbpjs
  delete item.qalamat
  fetchPasien()
}
function filter() {
  fetchPasien()
}
fetchPasien()
listDropdown()

</script>
<style lang="scss">
@import '/@src/scss/abstracts/all';

@import '/@src/scss/components/forms-outer';
@import '/@src/scss/custom/config';
@import '/@src/scss/custom/listview';
</style>
