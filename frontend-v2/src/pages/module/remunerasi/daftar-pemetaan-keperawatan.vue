<template>
  <section>
  <div class="column is-12">
    <VCard style="padding-bottom: 0px">
      <div class="column c-title pt-2 mb-0">
        <label class="title-page">Map Kelompok Penghasil</label>
      </div>
      <div class="column">
        <VPlaceload height="20rem" width="100%" class="mx-2" v-if="loadSearch" />
        <DataTable dataKey="id" :rows="10" :value="dataSource" v-model:selection="selectedData" v-else
          :rowsPerPageOptions="[5, 10, 15]" class="p-datatable-sm" breakpoint="960px" sortMode="multiple" showGridlines
          tableStyle="min-width: 30rem"
          paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
          paginator currentPageReportTemplate="Showing {first} to {last} of {totalRecords}">
          <template #header>
            <div class="columns is-multiline">
              <div class="column is-4">
                  <VField label="Periode" style="margin-bottom: 6px;" />
                  <VDatePicker v-model="item.filterTgl" is-range color="pink" trim-weeks>
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
              <div class="column is-4">
                <VField class="is-rounded-select is-autocomplete-select" label="Ruangan">
                  <VControl icon="feather:search" class="prime-auto-select">
                    <AutoComplete v-model="item.qruangan" :suggestions="d_Ruangan" @complete="fetchRuangan($event)"
                      :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                      :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="Ruangan..." />
                  </VControl>
                </VField>
              </div>
              <div class="column is-3">
                <VField class="is-rounded-select is-autocomplete-select" label="Pegawai">
                  <VControl icon="feather:search" class="prime-auto-select">
                    <AutoComplete v-model="item.qpegawai" :suggestions="d_Pegawai" @complete="fetchPegawai($event)"
                      :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                      :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="Pegawai..." />
                  </VControl>
                </VField>
              </div>
              <div class="column btn-search mt-3">
                <VIconButton color="success" icon="fas fa-search" @click="dataMapping" :loading="loadSearch" />
              </div>
            </div>
          </template>
          <Column selectionMode="multiple" style="text-align:center" />
          <Column field="namaruangan" header="Ruangan" />
          <Column field="namalengkap" header="Nama Pegawai" />
          <Column header="Dari">
            <template #body="slotProps">
              {{ H.formatDateToLocalString(slotProps.data.tglawal) }}
            </template>
          </Column>
          <Column header="Sampai">
            <template #body="slotProps">
              {{ H.formatDateToLocalString(slotProps.data.tglakhir) }}
            </template>
          </Column>
          <template #footer>
            <div class="column pt-0 pb-0" style="text-align:right">
              <VButtons style="justify-content: flex-end">
                <VButton class="mr-4" color="danger" icon="fas fa-trash-alt" raised @click="hapus(selectedData)">
                  Hapus
                </VButton>
                <VButton class="mr-4" color="primary" outlined icon="fas fa-plus-square"
                  style="padding-right: 2rem;padding-left: 2rem;" @click="modalTambah = true">Tambah</VButton>
              </VButtons>
            </div>
          </template>
        </DataTable>
      </div>

    </VCard>
  </div>

  <VModal :open="modalTambah" title="Tambah Data Pemetaan Keperawatan" noclose size="big" actions="right"
    @close="modalTambah = false, clear()" cancelLabel="Tutup">
    <template #content>
      <div class="column is-12">
        <div class="columns is-mulitline">
          <div class="column is-4">
            <VField class="is-rounded-select is-autocomplete-select" label="Ruangan">
              <VControl icon="feather:search" class="prime-auto-select">
                <AutoComplete v-model="item.ruanganfk" :suggestions="d_Ruangan" @complete="fetchRuangan($event)"
                  :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                  :field="'label'" placeholder="Ruangan..." />
              </VControl>
            </VField>
          </div>
          <div class="column is-3">
            <VDatePicker v-model="item.tglDari" color="green" trim-weeks>
              <template #default="{ inputValue, inputEvents }">
                <VField label="Tanggal Dari">
                  <VControl icon="feather:calendar">
                    <VInput type="text" placeholder="Select a date" :value="inputValue" v-on="inputEvents" />
                  </VControl>
                </VField>
              </template>
            </VDatePicker>
          </div>
          <div class="column is-3">
            <VDatePicker v-model="item.tglSampai" color="green" trim-weeks>
              <template #default="{ inputValue, inputEvents }">
                <VField label="Tanggal Sampai">
                  <VControl icon="feather:calendar">
                    <VInput type="text" placeholder="Select a date" :value="inputValue" v-on="inputEvents" />
                  </VControl>
                </VField>
              </template>
            </VDatePicker>
          </div>
        </div>
      </div>

      <div class="column is-12">
        <Fieldset class="p-fieldsets" legend="List Pegawai" :toggleable="true">
          <div class="column is-6">
            <VField>
              <h3 class="title is-6 mb-2 mr-1"> Pilih Pegawai </h3>
              <VControl icon="feather:search">
                <input v-model="filters" class="input custom-text-filter" placeholder="Search..." />
              </VControl>
            </VField>
          </div>
          <!-- <p> Data terpilih <b></b></p> -->
          <div class="columns is-multiline pl-3 pt-3" style="max-height:500px;overflow: auto;">
            <!-- <div class="column is-6 mt-2" v-for="data in 18" v-if="isLoadingPegawai">
                <VPlaceload class="mx-2" />
              </div> -->
            <div class="column is-4" :key="items.id" v-for="items in dataSourcefiltered">
              <VField>
                <VControl raw subcontrol>
                  <VCheckbox v-model="checkboxPegawai[items.id]" :value="items.id" :label="items.namalengkap" color="info"
                    square :class="checkboxPegawai[items.id] == true ? 'is-solid p-0' : 'p-0'" @change="clickPegawai()" />
                </VControl>
              </VField>
            </div>
          </div>

        </Fieldset>
      </div>
    </template>

    <template #action>
      <VButton icon="feather:save" :loading="isLoadingSave" @click="saveMapKelompokPenghasil()" color="primary" raised>
        Simpan</VButton>
    </template>

  </VModal>
</section></template>
<script  setup lang="ts">
import { useApi } from '/@src/composable/useApi'
import { ref, reactive, computed, watch } from 'vue'
import { useConfirm } from 'primevue/useconfirm'
import { useHead } from '@vueuse/head'
import AutoComplete from 'primevue/autocomplete';
import DataTable from 'primevue/datatable'
import Fieldset from 'primevue/fieldset';
import Row from 'primevue/row';
import * as H from '/@src/utils/appHelper'
import Dialog from 'primevue/dialog';
import Column from 'primevue/column'
import { useViewWrapper } from '/@src/stores/viewWrapper'

useHead({
  title: 'Daftar Pemetaan Pegawai - ' + import.meta.env.VITE_PROJECT,
})

useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)

const item: any = ref({
  aktif: true,
  isKK: false,
  tglPelayanan: new Date(),
  filterTgl: reactive({
    start: new Date(),
    end: new Date(),
  }),
})

const confirm = useConfirm()
const selectedData = ref();
const dataSource: any = ref([])
const listChecked: any = ref([])
const checkboxPegawai: any = ref([])
let jumlahCeklis: any = ref(0)
const modalTambah: any = ref(false)
let d_Ruangan: any = ref([])
let d_Pegawai: any = ref([])
let dataPegawai: any = ref([])
let loadSearch: any = ref(true)
let isLoadingSave: any = ref(false)
let isLoading: any = ref(false)

const filters = ref('')

const dataSourcefiltered = computed(() => {
  if (!filters.value) {
    return dataPegawai.value
  }
  return dataPegawai.value.filter((items: any) => {
    return (
      items.namalengkap.match(new RegExp(filters.value, 'i'))
    )
  })
})

const dataCombo = async () => {
  await useApi().get('remunerasi/get-combo-idx').then((response) => {
    dataPegawai.value = response.pegawai
  })
}

const fetchRuangan = async (filter: any) => {
  await useApi().get(
    `emr/dropdown/ruangan_m?select=id,namaruangan&param_search=namaruangan&query=${filter.query}&limit=10`
  ).then((response) => {
    d_Ruangan.value = response
  })
}

const fetchPegawai = async (filter: any) => {
  await useApi().get(
    `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`
  ).then((response) => {
    d_Pegawai.value = response
  })
}

const clickPegawai = () => {
  jumlahCeklis.value = 0
  let objectK = Object.keys(checkboxPegawai.value)
  let jumlah = 0
  for (let x = 0; x < objectK.length; x++) {
    const element = objectK[x];
    if (checkboxPegawai.value[element] == true) {
      for (var i = 0; i < dataPegawai.value.length; i++) {
        const element2 = dataPegawai.value[i];
        if (element2.id == element) {
          for (var z = 0; z < listChecked.value.length; z++) {
            const element3 = listChecked.value[z];
            if (element3.namalengkap == element2.namalengkap) {
              listChecked.value.splice(z, 1)
            }
          }
          listChecked.value.push({ namalengkap: element2.namalengkap })
        }
      }
      jumlah = jumlah + 1

    } else {
      for (var i = 0; i < dataPegawai.value.length; i++) {
        const element2 = dataPegawai.value[i];
        if (element2.id == element) {
          for (var z = 0; z < listChecked.value.length; z++) {
            const element3 = listChecked.value[z];
            if (element3.namalengkap == element2.namalengkap) {
              listChecked.value.splice(z, 1)
            }
          }
        }
      }
    }
  }
  jumlahCeklis.value = jumlah
}

const saveMapKelompokPenghasil = async () => {

  if (jumlahCeklis.value == 0) { H.alert('warning', 'Pegawai harus di pilih'); return }
  if (!item.value.ruanganfk) { H.alert('warning', 'Ruangan harus di pilih'); return }
  if (!item.value.tglDari) { H.alert('warning', 'Tanggal Dari harus di pilih'); return }
  if (!item.value.tglSampai) { H.alert('warning', 'Tanggal Sampai harus di pilih'); return }
  let arrPegawai = []
  let objectK = Object.keys(checkboxPegawai.value)
  for (let x = 0; x < objectK.length; x++) {
    const element = objectK[x];
    if (checkboxPegawai.value[element] == true) {
      arrPegawai.push({ 'pegawai': element })
    }

  }
  let json = {
    'ruanganfk': item.value.ruanganfk.value,
    'tglawal': H.formatDate(item.value.tglDari, 'YYYY-MM-DD hh:mm:ss'),
    'tglakhir': H.formatDate(item.value.tglSampai, 'YYYY-MM-DD hh:mm:ss'),
    'detail': arrPegawai
  }
  isLoadingSave.value = true
  await useApi().post('sysadmin/save-map-remun-kelompok', json).then((response: any) => {
    isLoadingSave.value = false
    dataMapping()
    modalTambah.value = false

  }).catch((e: any) => {
    isLoadingSave.value = false
    modalTambah.value = false
  })

}

const dataMapping = async () => {
  console.log(item.value.lastDate)
  loadSearch.value = true
  let tglAwal = H.formatDate(item.value.filterTgl.start, 'YYYY-MM-DD')
  let tglAkhir = H.formatDate(item.value.filterTgl.end, 'YYYY-MM-DD')
  let ruangan = item.value.qruangan ? `&ruanganfk=${item.value.qruangan.value}` : ''
  let pegawai = item.value.qpegawai ? `&pegawaifk=${item.value.qpegawai.value}` : ''
  await useApi().get(`sysadmin/get-mapping-remun-kelompok?tglawal=${tglAwal}&tglakhir=${tglAkhir}${ruangan}${pegawai}`).then((response) => {
    dataSource.value = response
  })
  loadSearch.value = false
}

const listDataByDate = async () => {
  await useApi().get(`sysadmin/get-data-by-date?ruanganfk=${item.value.ruanganfk.value}&tglawal=${H.formatDate(item.value.tglDari, 'YYYY-MM-DD')}&tglakhir=${H.formatDate(item.value.tglSampai, 'YYYY-MM-DD')}`)
    .then((response) => {
      response.forEach((element: any) => {
        checkboxPegawai.value[element.objectpegawaifk] = true
      })
      clickPegawai()
    })
}

const hapus = async (e: any) => {
  let items: any = []
  if (!e) {
    H.alert('error', 'Checklist Data Terlebih dulu')
    return
  }
  e.forEach((element: any) => {
    items = [...new Set([...items, element.id])]
  });

  await useApi().post('sysadmin/delete-map-remun-kelompok', { 'idkelompok': items }).then((response) => {
    dataMapping()
  })

}

const clear = () => {
  delete item.value.tglDari
  delete item.value.tglSampai
  delete item.value.ruanganfk
  checkboxPegawai.value = []
}

const cek = () => {
  console.log(item)
}


dataCombo()
dataMapping()

watch(
  () => [
    item.value.tglSampai
  ], () => {
    if (item.value.ruanganfk && item.value.tglDari && item.value.tglSampai) {
      listDataByDate()
    }

  }
)

</script>
<style lang="scss">
@import '/@src/scss/abstracts/all';

@import '/@src/scss/components/forms-outer';
@import '/@src/scss/custom/config';
@import '/@src/scss/module/sysadmin/master-data.scss';

.title-page {
  position: relative;
  font-size: 17px;
  display: block;
  margin-bottom: 3px;
  margin-top: 0px;
  font-weight: 600;
}

.btn-search {
  display: flex;
  align-items: center;
}


.checkbox.is-outlined.is-info.is-solid input+span::after {
  color: var(--white);
}
</style>
