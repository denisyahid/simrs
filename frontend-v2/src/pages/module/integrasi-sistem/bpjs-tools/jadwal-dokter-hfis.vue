<template>
  <section>
    <VCard>
      <h3 class="font-bold">Jadwal Dokter HFIS</h3>
      <div class="columns is-multiline mt-4">
        <div class="column is-12">
          <div class="columns is-multiline">
            <div class="column is-3">
              <VField class="is-rounded-select is-autocomplete-select" label="Poli">
                <VControl
                  icon="feather:search"
                  class="prime-auto-select"
                  :loading="isLoadingSelect"
                >
                  <Dropdown
                    v-model="item.poli"
                    :options="d_DokterBPJS"
                    :optionLabel="'nmsubspesialis'"
                    placeholder="Pilih data"
                    style="width: 100%"
                    showClear
                    :filter="true"
                  />
                </VControl>
              </VField>
            </div>
            <div class="column is-3">
              <VField class="is-rounded-select is-autocomplete-select">
                <VLabel>Tanggal</VLabel>
                <VControl>
                  <Calendar
                    v-model="item.tgl"
                    :showTime="false"
                    dateFormat="dd-mm-yy"
                    style="width: 100%"
                    showIcon
                    iconDisplay="input"
                  />
                </VControl>
              </VField>
            </div>

            <div class="column is-1" style="margin-top: 27px">
              <VIconButton
                circle
                icon="feather:search"
                raised
                bold
                color="primary"
                @click="fecthData"
                :loading="isLoading"
                v-tooltip.bubble="'Cari'"
                class="mt-2-min"
              >
              </VIconButton>
            </div>
          </div>
        </div>

        <div class="column is-12">
          <DataTable
            v-model:filters="filters"
            :expandedRows="expandedRows"
            :value="dataSource"
            paginator
            :rows="10"
            dataKey="no"
            filterDisplay="row"
            :loading="isLoading"
            :globalFilterFields="['peserta', 'nama']"
            :class="`p-datatable-small`"
            @rowExpand="onRowExpand"
            @rowCollapse="onRowCollapse"
          >
            <template #header>
              <div class="flex justify-content-between">
                <span class="p-input-icon-left">
                  <InputText v-model="filters['global'].value" placeholder="Search" />
                </span>
              </div>
            </template>
            <template #empty style="text-align: center"> No data found. </template>
            <Column header="#" style="width: 80px">
              <template #body="slotProps">
                <VIconButton
                  icon="feather:edit"
                  @click="edit(slotProps.data)"
                  color="warning"
                  raised
                  circle
                  class="mr-2"
                >
                </VIconButton>
              </template>
            </Column>
            <Column field="no" header="No"></Column>
            <Column field="namapoli" header="Poli"></Column>
            <Column field="namasubspesialis" header="Subspesialis"></Column>
            <Column field="namadokter" header="Dokter"> </Column>
            <Column field="namahari" header="Hari"></Column>
            <Column field="jadwal" header="Jadwal"></Column>
            <Column field="kapasitaspasien" header="Kapasitas Pasien"> </Column>
          </DataTable>
          <div>
            <p><b>Catatan:</b></p>
            <p>
              - Data yang berhasil disimpan menunggu aproval dari BPJS atau otomatis
              approve jadwal dokter oleh sistem, misal pengajuan perubahan jadwal oleh RS
              diantara jam 00.00 - 20.00 , kemudian alokasi approve manual oleh
              BPJS/cabang di jam 20.01-00.00. Jika pukul 00.00 belum dilakukan aproval
              oleh kantor cabang, maka otomatis approve by sistem akan dilaksanakan
              setelah jam 00.00 dan yang berubahnya esoknya (H+1). <br />
            </p>
          </div>
        </div>
      </div>
    </VCard>
    <Dialog
      v-model:visible="modal"
      modal
      header="Update Jadwal Dokter"
      :style="{ width: '60vw' }"
    >
      <div class="columns is-multiline">
        <div class="column is-4">
          <VCardCustom :style="'padding:5px 25px'">
            <div class="label-status">
              <i aria-hidden="true" class="fas fa-circle"></i>
              <span class="ml-1">Poli</span>
            </div>
            <small class="text-bold-custom h-100">
              {{ selected.namapoli }}
            </small>
          </VCardCustom>
        </div>
        <div class="column is-4">
          <VCardCustom :style="'padding:5px 25px'">
            <div class="label-status">
              <i aria-hidden="true" class="fas fa-circle"></i>
              <span class="ml-1">Subspesialis</span>
            </div>
            <small class="text-bold-custom h-100">
              {{ selected.namasubspesialis }}
            </small>
          </VCardCustom>
        </div>
        <div class="column is-4">
          <VCardCustom :style="'padding:5px 25px'">
            <div class="label-status">
              <i aria-hidden="true" class="fas fa-circle"></i>
              <span class="ml-1">Dokter</span>
            </div>
            <small class="text-bold-custom h-100">
              {{ selected.namadokter }}
            </small>
          </VCardCustom>
        </div>
        <div class="column is-12" v-for="(item, index) in listItem" :key="index">
          <VCard class="is-grey">
            <div class="columns is-multiline p-1">
              <div class="column is-3">
                <VField
                  label="Hari"
                  class="is-rounded-select is-autocomplete-select"
                  v-slot="{ id }"
                >
                  <VControl icon="feather:plus-circle" fullwidth>
                    <Dropdown
                      v-model="item.hari"
                      :options="d_Hari"
                      :optionLabel="'namahari'"
                      class="is-rounded"
                      placeholder="Pilih data"
                      style="width: 100%"
                      showClear
                      :filter="true"
                    />
                  </VControl>
                </VField>
              </div>

              <div class="column is-2">
                <VField class="is-rounded-select is-autocomplete-select" v-slot="{ id }">
                  <VLabel>Buka</VLabel>
                  <VControl fullwidth class="prime-auto-select">
                    <Calendar
                      v-model="item.buka"
                      showIcon
                      iconDisplay="input"
                      timeOnly
                      inputId="templatedisplay"
                      placeholder="HH:mm"
                    >
                      <template #inputicon="{ clickCallback }">
                        <InputIcon
                          class="pi pi-clock cursor-pointer"
                          @click="clickCallback"
                        />
                      </template>
                    </Calendar>
                  </VControl>
                </VField>
              </div>
              <div class="column is-2">
                <VField class="is-rounded-select is-autocomplete-select" v-slot="{ id }">
                  <VLabel>Buka</VLabel>
                  <VControl fullwidth class="prime-auto-select">
                    <Calendar
                      v-model="item.tutup"
                      showIcon
                      iconDisplay="input"
                      timeOnly
                      inputId="templatedisplay"
                      placeholder="HH:mm"
                    >
                      <template #inputicon="{ clickCallback }">
                        <InputIcon
                          class="pi pi-clock cursor-pointer"
                          @click="clickCallback"
                        />
                      </template>
                    </Calendar>
                  </VControl>
                </VField>
              </div>
              <div class="column is-1 mt-5">
                <VIconButton
                  v-if="index > 0"
                  outlined
                  type="button"
                  raised
                  circle
                  class="is-pulled-right"
                  icon="feather:trash"
                  @click="removeItem(index)"
                  color="danger"
                >
                </VIconButton>
              </div>
              <div class="column is-1 is-flex mt-5">
                <VButton
                  type="button"
                  rounded
                  outlined
                  color="info"
                  raised
                  icon="feather:plus"
                  @click="addNewItem()"
                >
                  Tambah
                </VButton>
              </div>
            </div>
          </VCard>
        </div>
      </div>
      <template #footer>
        <VButton
          icon="feather:refresh-cw rem-100"
          light
          dark-outlined
          @click="modal = false"
        >
          Batal
        </VButton>
        <VButton
          type="button"
          rounded
          outlined
          color="primary"
          raised
          icon="feather:save"
          :loading="isSave"
          @click="simpan()"
        >
          Simpan
        </VButton>
      </template>
    </Dialog>
  </section>
</template>
<script setup lang="ts">
import { useRoute, useRouter } from 'vue-router'
import { ref, computed, watch, reactive } from 'vue'
import * as H from '/@src/utils/appHelper'
import { useApi } from '/@src/composable/useApi'
import { useHead } from '@vueuse/head'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useToaster } from '/@src/composable/toaster'
import DataTable from 'primevue/datatable'
import Column from 'primevue/column'
import ColumnGroup from 'primevue/columngroup' // optional
import Row from 'primevue/row'
import InputText from 'primevue/inputtext'
import TabView from 'primevue/tabview'
import TabPanel from 'primevue/tabpanel'
import { FilterMatchMode } from 'primevue/api'
import Calendar from 'primevue/calendar'
import Dropdown from 'primevue/dropdown'
import Dialog from 'primevue/dialog'
useHead({
  title: import.meta.env.VITE_PROJECT,
})
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)
const isLoading: any = ref(false)
const isSave: any = ref(false)
const d_DokterBPJS: any = ref([])
const item: any = reactive({
  tgl: new Date(),
})
const d_Hari = [
  { id: 1, namahari: 'SENIN' },
  { id: 2, namahari: 'SELASA' },
  { id: 3, namahari: 'RABU' },
  { id: 4, namahari: 'KAMIS' },
  { id: 5, namahari: "JUM'AT" },
  { id: 6, namahari: 'SABTU' },
  { id: 7, namahari: 'MINGGU' },
]
const dataSource = ref([])
const modal = ref(false)
const expandedRows = ref([])
let totalDisetujui = ref(0)
let totalTarifRs = ref(0)
let surplusDefisit = ref(0)
let selected: any = ref({})
const filters = ref({
  global: { value: null, matchMode: FilterMatchMode.CONTAINS },
})
const listItem: any = ref([
  {
    hari: null,
    buka: null,
    tutup: null,
  },
])
const edit = (e: any) => {
  modal.value = true
  selected.value = e
  let hari = {}
  for (let x = 0; x < d_Hari.length; x++) {
    const element = d_Hari[x]
    if ((element.id = e.hari)) {
      hari = element
      break
    }
  }
  let now = H.formatDate(new Date(), 'YYYY-MM-DD')
  let jam = e.jadwal.split('-')
  listItem.value = [
    {
      hari: hari,
      buka: new Date(now + ' ' + jam[0]),
      tutup: new Date(now + ' ' + jam[1]),
    },
  ]
}
const fecthData = async () => {
  if (!item.poli) {
    useToaster().error('Harap pilih poli terlebih dahulu !')
    return
  }

  const tgl = H.formatDate(item.tgl, 'YYYY-MM-DD')

  dataSource.value = []
  isLoading.value = true

  var json = {
    url: `jadwaldokter/kodepoli/${item.poli.kdpoli}/tanggal/${tgl}`,
    method: 'GET',
    jenis: 'antrean',
    data: null,
  }
  await useApi()
    .postNoMessage(`/bridging/bpjs/tools`, json)
    .then((response: any) => {
      if (response.metaData.code == 200 && response.response) {
        for (let x = 0; x < response.response.length; x++) {
          const element = response.response[x]
          element.no = x + 1
        }
        dataSource.value = response.response
      }
      isLoading.value = false
    })
    .catch((err) => {
      console.log(err)
    })
}
const onRowExpand = (event) => {
  expandedRows.value = dataSource.value.filter((p) => p.no == event.data.no)
}
const onRowCollapse = (event) => {
  expandedRows.value = null
}
const fetchDokterBPJS = async (filter: any) => {
  let json = {
    url: 'ref/poli',
    jenis: 'antrean',
    method: 'GET',
    data: null,
  }
  const res = await useApi().postBPJS(`/bridging/bpjs/tools`, json)
  d_DokterBPJS.value = res.response
}
const addNewItem = () => {
  if (
    listItem.value[listItem.value.length - 1].hari == undefined ||
    listItem.value[listItem.value.length - 1].hari == null
  ) {
    H.alert('error', 'Pilih hari')
    return
  }
  listItem.value.push({
    hari: null,
    buka: null,
    tutup: null,
  })
}
const removeItem = (index: any) => {
  listItem.value.splice(index, 1)
}
const getDatesInMonth = (year: number, month: number) => {
  const dates: Date[] = []
  const startDate = new Date(year, month - 1, 1) // Month dalam JavaScript dimulai dari 0

  while (startDate.getMonth() === month - 1) {
    dates.push(H.formatDate(new Date(startDate), 'YYYY-MM-DD'))
    startDate.setDate(startDate.getDate() + 1)
  }

  return dates
}
const simpan = async () => {
  let datas: any = []
  for (let i = 0; i < listItem.value.length; i++) {
    const element = listItem.value[i]
    datas.push({
      hari: element.hari.id,
      buka: H.formatDate(element.buka, 'HH:mm'),
      tutup: H.formatDate(element.tutup, 'HH:mm'),
    })
  }
  let json = {
    url: 'jadwaldokter/updatejadwaldokter',
    jenis: 'antrean',
    method: 'POST',
    data: {
      kodepoli: selected.value.kodepoli,
      kodesubspesialis: selected.value.kodesubspesialis,
      kodedokter: selected.value.kodedokter,
      jadwal: datas,
    },
  }
  isSave.value = true
  await useApi()
    .postBPJS(`/bridging/bpjs/tools`, json)
    .then((response: any) => {
      if (response.metaData.code == 200) {
        H.alert('success', response.metaData.message)
      } else {
        H.alert('error', response.metaData.message)
      }
      isSave.value = false
    })
    .catch((err) => {
      isSave.value = false
      console.log(err)
    })
}
fetchDokterBPJS()
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
