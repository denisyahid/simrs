<template>
  <ConfirmDialog />
  <div>
    <div class="form-layout is-stacked">
      <div class="form-outer" style="margin-top: 15px">
        <div class="form-header stuck-header">
          <div class="form-header-inner">
            <div class="left">
              <h3>Form Identifikasi Resiko</h3>
            </div>
            <div class="right">
              <div class="buttons">
                <VButton icon="lnir lnir-arrow-left rem-100" @click="back()" light dark-outlined RouterLink>
                  Kembali
                </VButton>
                <div>
                  <VButton type="button" rounded outlined color="primary" raised icon="feather:save" @click="saveData()"
                    :loading="isLoadBtnSave">
                    Simpan
                  </VButton>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="form-body p-4">
          <div style="margin-top:2rem" v-if="loadData">
            <VPlaceloadWrap class="mt-5">
              <VPlaceload height="30px" width="40%" class="mx-2" />
              <VPlaceload height="30px" width="30%" class="mx-2" />
              <VPlaceload height="30px" width="40%" class="mx-2" />
            </VPlaceloadWrap>
            <VPlaceloadWrap class="mt-5">
              <VPlaceload height="30px" width="50%" class="mx-2" />
              <VPlaceload height="30px" width="30%" class="mx-2" />
              <VPlaceload height="30px" width="20%" class="mx-2" />
            </VPlaceloadWrap>
            <VPlaceloadWrap class="mt-5">
              <VPlaceload height="30px" width="50%" class="mx-2" />
              <VPlaceload height="30px" width="30%" class="mx-2" />
              <VPlaceload height="30px" width="20%" class="mx-2" />
            </VPlaceloadWrap>
          </div>

          <div v-else>
            <div class="columns is-multiline">
              <div class="column is-4">
                <VField class="is-rounded-select is-autocomplete-select" label="Unit Kerja">
                  <VControl icon="feather:search" class="prime-auto-select">
                    <AutoComplete v-model="item.unitKerjafk" :suggestions="d_Departement"
                      @complete="fetchDepartement($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                      :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="Pilih Unit Kerja" />
                  </VControl>
                </VField>
              </div>

              <div class="column is-3">
                <VDatePicker v-model="item.tanggal" color="green" trim-weeks mode="date" :max-date="new Date()">
                  <template #default="{ inputValue, inputEvents }">
                    <VField>
                      <VLabel class="required-field">Tanggal</VLabel>
                      <VControl icon="feather:calendar">
                        <VInput type="text" placeholder="Select a date" :value="inputValue" v-on="inputEvents" />
                      </VControl>
                    </VField>
                  </template>
                </VDatePicker>
              </div>

              <div class="column is-5">
                <VField class="is-rounded-select is-autocomplete-select">
                  <VLabel class="required-field">Kategori Resiko</VLabel>
                  <VControl icon="feather:search" fullwidth class="prime-auto-select">
                    <Dropdown v-model="item.kategoriresiko" :options="d_KategoryResiko" optionLabel="label"
                      placeholder="Pilih Kategori Resiko" style="width: 100%;" :filter="true" />
                  </VControl>
                </VField>
              </div>
            </div>

            <div class="columns is-multiline">
              <div class="column is-4">
                <VField label="Ka Instalasi">
                  <VControl class="prime-auto">
                    <AutoComplete v-model="item.kaInstalasi" :suggestions="d_Pegawai" @complete="fetchPegawai($event)"
                      :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                      :loadingIcon="'pi pi-spinner'" :field="'label'" />
                  </VControl>
                </VField>
              </div>
              <div class="column is-4">
                <VField label="Kepala Bidang">
                  <VControl class="prime-auto">
                    <AutoComplete v-model="item.kepalaBidang" :suggestions="d_Pegawai" @complete="fetchPegawai($event)"
                      :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                      :loadingIcon="'pi pi-spinner'" :field="'label'" />
                  </VControl>
                </VField>
              </div>
              <div class="column is-4">
                <VField label="Direktur">
                  <VControl class="prime-auto">
                    <AutoComplete v-model="item.direktur" :suggestions="d_Pegawai" @complete="fetchPegawai($event)"
                      :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                      :loadingIcon="'pi pi-spinner'" :field="'label'" />
                  </VControl>
                </VField>
              </div>
            </div>

            <!-- <div class="columns is-multiline pl-3 pr-3">
              <div class="column is-3">
                <VDatePicker v-model="item.tglPR" color="green" trim-weeks mode="date" :max-date="new Date()">
                  <template #default="{ inputValue, inputEvents }">
                    <VField>
                      <VLabel class="required-field">Tanggal PR</VLabel>
                      <VControl icon="feather:calendar">
                        <VInput type="text" placeholder="Select a date" :value="inputValue" v-on="inputEvents" />
                      </VControl>
                    </VField>
                  </template>
                </VDatePicker>
              </div>
              <div class="column is-5">
                <VField class="is-rounded-select is-autocomplete-select">
                  <VLabel class="required-field">Unit Tujuan</VLabel>
                  <VControl icon="feather:search" fullwidth class="prime-auto-select">
                    <AutoComplete v-model="item.unitTujuan" :suggestions="d_unitTujuan"
                      @complete="fetchUnitTujuan($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                      :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'" />
                  </VControl>
                </VField>
              </div>
              <div class="column">
                <VDatePicker v-model="item.tglJatuhTempo" color="green" trim-weeks mode="date">
                  <template #default="{ inputValue, inputEvents }">
                    <VField>
                      <VLabel class="required-field">Tanggal Jatuh Tempo</VLabel>
                      <VControl icon="feather:calendar">
                        <VInput type="text" placeholder="Select a date" :value="inputValue" v-on="inputEvents" />
                      </VControl>
                    </VField>
                  </template>
                </VDatePicker>
              </div>
            </div>

            <div class="columns is-mulitline pl-3 pr-3">
              <div class="column is-12">
                <VField label="Keterangan">
                  <VControl>
                    <input v-model="item.keterangan" type="text" class="input" placeholder="Keterangan" />
                  </VControl>
                </VField>
              </div>
            </div> -->

          </div>
        </div>

      </div>

      <div class="column is-12 p-0 mt-5">
        <VCard>
          <div class="column is-2 p-0 pb-2" style="margin-left: auto">
            <VButton type="button" icon="fas fa-plus-circle" @click="showModal(item)"
              class="is-fullwidth is-outlined is-primary mt-4" rounded raised :loading="loadData">
              Tambah
            </VButton>
          </div>
          <DataTable :value="sourceResiko" :paginator="true" :rows="5" :rowsPerPageOptions="[5, 10, 25]"
            :loading="sourceResiko.loading" class="p-datatable-sm"
            paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
            responsiveLayout="stack" breakpoint="960px" sortMode="multiple"
            currentPageReportTemplate="Showing {first} to {last} of {totalRecords}" showGridlines>
            <Column field="no" header="No"></Column>
            <Column field="jenisrisiko" header="Jenis Resiko"></Column>
            <Column field="keparahan" header="Keparahan" />
            <Column field="kemungkinan" header="Kemungkinan" />
            <Column field="skor" header="Skor" />
            <Column field="rangkingrisiko" header="Rangking Rasio" />
            <Column field="pengendalian" header="Pengendalian" />
            <Column field="rangkingaction" header="Rangking For Action" />
            <Column :exportable="false" header="Action" style="text-align: center;min-width: 100px;">
              <template #body="slotProps">
                <VIconButton type="button" icon="feather:edit" class="mr-3" color="info" circle outlined raised
                  :loading="loadingBtnEdit" v-tooltip.top="'Edit'" @click="showModal(slotProps.data)">
                </VIconButton>
                <VIconButton type="button" icon="fas fa-trash" color="danger" circle outlined raised
                  v-tooltip.top="'Hapus'" @click="dialogConfirm(slotProps.data)">
                </VIconButton>
              </template>
            </Column>
          </DataTable>
        </VCard>
      </div>

    </div>
  </div>

  <VModal is="form" :open="modalInput" title="Form Input" size="large" actions="right"
    @close="modalInput = false, clear()">
    <template #content>
      <div class="column is-12">
        <div class="columns is-multiline">
          <div class="column is-12">
            <VField label="Jenis Resiko">
              <VControl>
                <VTextarea v-model="item.jenisResiko" rows="3">
                </VTextarea>
              </VControl>
            </VField>
          </div>
        </div>

        <div class="columns is-multiline">
          <div class="column is-4">
            <VField class="is-rounded-select is-autocomplete-select" label="Keparahan">
              <VControl icon="feather:search" fullwidth class="prime-auto-select">
                <Dropdown v-model="item.keparahan" :options="d_Poin" :optionLabel="'label'" placeholder="Pilih Keparahan"
                  style="width: 100%;" :filter="true" />
              </VControl>
            </VField>
          </div>
          <div class="column is-4">
            <VField class="is-rounded-select is-autocomplete-select" label="Kemungkinan">
              <VControl icon="feather:search" fullwidth class="prime-auto-select">
                <Dropdown v-model="item.kemungkinan" :options="d_Poin" :optionLabel="'label'"
                  placeholder="Pilih Kemungkinan " style="width: 100%;" :filter="true" />
              </VControl>
            </VField>
          </div>
          <div class="column is-4">
            <VField label="Skor">
              <VControl>
                <VInput type="text" class="input" v-model="item.skor" disabled style="font-weight:bold" />
              </VControl>
            </VField>
          </div>
        </div>

        <div class="columns is-multiline">
          <div class="column is-4">
            <VField label="Rangking Risiko">
              <VControl>
                <VTextarea v-model="item.rangkingResiko" rows="3">
                </VTextarea>
              </VControl>
            </VField>
          </div>
          <div class="column is-4">
            <VField label="Pengendalian">
              <VControl>
                <VTextarea v-model="item.pengendalian" rows="3">
                </VTextarea>
              </VControl>
            </VField>
          </div>
          <div class="column is-4">
            <VField label="Rangking For Action">
              <VControl>
                <VTextarea v-model="item.rangkingAction" rows="3">
                </VTextarea>
              </VControl>
            </VField>
          </div>
        </div>

      </div>
    </template>
    <template #action>
      <VButton color="primary" raised @click="addData(item)">Simpan</VButton>
    </template>
  </VModal>
</template>
<script setup lang="ts">
import { useWindowScroll } from '@vueuse/core'
import { useApi } from '/@src/composable/useApi'
import { ref, computed, watch } from 'vue'
import { useRouter, useRoute } from 'vue-router'
import { useHead } from '@vueuse/head'
import { useToaster } from '/@src/composable/toaster'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useThemeColors } from '/@src/composable/useThemeColors'
import { useConfirm } from 'primevue/useconfirm'
import ConfirmDialog from 'primevue/confirmdialog'
import moment from 'moment'
import Checkbox from 'primevue/checkbox';
import AutoComplete from 'primevue/autocomplete';
import * as H from '/@src/utils/appHelper'
import Dialog from 'primevue/dialog';
import DataTable from 'primevue/datatable'
import Dropdown from 'primevue/dropdown'
import Column from 'primevue/column'
useHead({
  title: 'Form Identifikasi Resiko - ' + import.meta.env.VITE_PROJECT,
})
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)
const isLoadingPasien: any = ref(false)

const confirm = useConfirm()
const router = useRouter();
const route = useRoute()

let NOREC = useRoute().query.norec as string

const item: any = ref({
  tanggal: new Date(),
})

const d_Pegawai = ref([])
const d_KategoryResiko = ref([])
const d_Departement = ref([])
const modalInput: any = ref(false)
const loadData = ref(true)

let sourceResiko: any = ref([])
let loadingBtnEdit: any = ref(false)
let isLoadBtnSave: any = ref(false)

const { y } = useWindowScroll()
const isStuck = computed(() => {
  return y.value > 30
})

const d_Poin = ref([
  { label: '1', value: 1 },
  { label: '2', value: 2 },
  { label: '3', value: 3 },
  { label: '4', value: 4 },
  { label: '5', value: 5 },
])

const fetchDepartement = async (filter: any) => {

  await useApi().get(`emr/dropdown/departemen_m?select=id,namadepartemen&param_search=namadepartemen&query=${filter.query}&limit=10`
  ).then((response) => {
    d_Departement.value = response
  })
}

const loadRiwayat = async (e:any)=>{

  if(e){
    loadData.value = true
    let response = await useApi().get(`/pmkp/get-daftar-laporan-identifikasi-risiko?norec=${e}`)
    let data = response[0]
    item.value.tanggal = data.tanggal
    item.value.unitKerjafk = { label: data.namadepartemen, value: data.departemenfk }
    item.value.kategoriresiko = { label: data.kategoryrisiko, value: data.kategoririsikofk }
    item.value.kaInstalasi = { label: data.kainstalasi, value: data.kainstalasifk }
    item.value.kepalaBidang = { label: data.kabidang, value: data.kepalabidangfk }
    item.value.direktur = { label: data.direktur, value: data.direkturfk }
    data.details.forEach((elem: any, e: any) => {
      elem.no = e + 1
    })
    sourceResiko.value = data.details
    loadData.value = false
  }
}

const saveData = async () => {
  if (sourceResiko.value.length == 0) {
    H.alert('error', 'Data Kosong')
    return
  }
  if (!item.value.tanggal) {
    H.alert('error', 'Tanggal Tidak Boleh Kosong')
    return
  }
  let objSave = {
    'norec': NOREC ? NOREC : '',
    'kategoririsikofk': item.value.kategoriresiko ? item.value.kategoriresiko.value : null,
    'departemenfk': item.value.unitKerjafk ? item.value.unitKerjafk.value : null,
    'tanggal': item.value.tanggal ? H.formatDate(item.value.tanggal, 'YYYY-MM-DD HH:mm:ss') : null,
    'instalasi': item.value.kaInstalasi ? item.value.kaInstalasi.value : null,
    'kplabidang': item.value.kepalaBidang ? item.value.kepalaBidang.value : null,
    'direktur': item.value.direktur ? item.value.direktur.value : null,
    'details': sourceResiko.value,
  }
  console.log(objSave)

  isLoadBtnSave.value = true
  await useApi().post('pmkp/save-identifikasi-resiko', objSave).then((response) => {
    back()
  })
  isLoadBtnSave.value = false
}

const addData = (e: any) => {
  console.log(item.value.keparahan)
  let datas: any = {}
  if (e.no) {
    sourceResiko.value.forEach((element: any, i: any) => {
      if (element.no == e.no) {
        datas.no = element.no,
        datas.jenisrisiko = item.value.jenisResiko,
        datas.keparahan = item.value.keparahan.label,
        datas.kemungkinan = item.value.kemungkinan.label,
        datas.skor = item.value.skor,
        datas.rangkingrisiko = item.value.rangkingResiko,
        datas.pengendalian = item.value.pengendalian,
        datas.rangkingaction = item.value.rangkingAction,
        sourceResiko.value[i] = datas
      }
    });
  } else {
    datas = {
      no: sourceResiko.value.length == 0 ? 1 : sourceResiko.value.length + 1,
      norec: item.value.norec ? item.value.norec : '',
      jenisrisiko: item.value.jenisResiko,
      keparahan: item.value.keparahan.label,
      kemungkinan: item.value.kemungkinan.label,
      skor: item.value.skor,
      rangkingrisiko: item.value.rangkingResiko,
      pengendalian: item.value.pengendalian,
      rangkingaction: item.value.rangkingAction ? item.value.rangkingAction : ''
    }
    sourceResiko.value.push(datas)
  }
  if (sourceResiko.value.length > 0) {
    clear()
  }
  modalInput.value = false
}

const showModal = async (e: any) => {

  if (e.no) {
    loadingBtnEdit.value = true
    item.value.no = e.no
    item.value.norec = e.norec
    item.value.jenisResiko = e.jenisrisiko
    d_Poin.value.forEach(element => {
      if(element.label == e.keparahan){
         item.value.keparahan = element
      }
      if(element.label == e.kemungkinan){
         item.value.kemungkinan = element
      }
    });
    // item.value.keparahan = { label: e.keparahan, value: e.keparahanfk }
    // item.value.kemungkinan = { label: e.kemungkinan, value: e.kemungkinanfk }
    item.value.skor = e.skor
    item.value.rangkingResiko = e.rangkingrisiko
    item.value.pengendalian = e.pengendalian
    item.value.rangkingAction = e.rangkingaction
    modalInput.value = true
    loadingBtnEdit.value = false
  } else {
    modalInput.value = true
  }
}

const dialogConfirm = (e: any) => {
  confirm.require({
    message: 'Apakah anda serius menghapus data ini ?',
    header: 'Konfirmasi Hapus Data',
    icon: 'pi pi-info-circle',
    acceptClass: 'p-button-danger',
    accept: () => {
      sourceResiko.value.forEach((element: any, i: any) => {
        if (element.no == e.no) {
          sourceResiko.value.splice(i, 1)
        }
        element.no - 1
      })
    },
    reject: () => { },
  })
}

const clear = () => {
  delete item.value.no
  delete item.value.norec
  delete item.value.jenisResiko
  delete item.value.keparahan
  delete item.value.keparahanfk
  delete item.value.kemungkinan
  delete item.value.kemungkinanfk
  delete item.value.skor
  delete item.value.rangkingResiko
  delete item.value.pengendalian
  delete item.value.rangkingAction
}

const getCombo = async () => {
  let response = await useApi().get('pmkp/get-data-combo-pmkp')
  d_KategoryResiko.value = response.kategoryrisiko.map((e: any) => {
    return { label: e.kategoryrisiko, value: e.id }
  })
  loadData.value = false

}

const fetchPegawai = async (filter: any) => {
  await useApi().get(
    `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`
  ).then((response) => {
    d_Pegawai.value = response
  })
}

const back = () => {
  window.history.back()
}

watch(
  () => [
    item.value.keparahan,
    item.value.kemungkinan,
  ],
  () => {
    let keparahan = item.value.keparahan ? parseInt(item.value.keparahan.label) : 0
    let kemungkinan = item.value.kemungkinan ? parseInt(item.value.kemungkinan.label) : 0

    let poin = keparahan && kemungkinan != 0 ? keparahan * kemungkinan : 0
    item.value.skor = poin
  }
)

getCombo()
loadRiwayat(NOREC)

</script>


<style lang="scss">
@import '/@src/scss/abstracts/all';
@import '/@src/scss/components/forms-outer';
@import '/@src/scss/custom/config';

.border-style {
  border-style: solid;
  border-width: 1px;
  color: #0398e2;
  border-radius: 10px;
}

.p-dialog-content {
  overflow-y: unset;
}
</style>

