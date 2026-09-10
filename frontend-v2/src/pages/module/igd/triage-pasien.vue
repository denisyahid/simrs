<template>
  <ConfirmDialog />
  <div class="column">
    <VCard>
      <div class="column c-title pt-2 mb-0">
          <div class="column is-10 p-0">
              <label class="title-page">Daftar Triage Pasien</label>
          </div>
      </div>
      <!-- <div class="column is-12">
        <div class="search-widget">
          <div class="field">
            <div class="columns is-multiline">
              <div class="column is-12">
                <h3 class="title is-5 mb-2 mr-1">Daftar Triage Pasien</h3>
              </div>
              <div class="column is-3">
                <VField>
                  <VLabel class="required-field"> Nama Pasien </VLabel>
                  <VControl icon="feather:search">
                    <input
                      v-model="item.qnama"
                      v-on:keyup.enter="fetchData()"
                      type="text"
                      class="input"
                      placeholder="Filter Nama..."
                    />
                  </VControl>
                </VField>
              </div>

              <div class="column is-3">
                  <VField label="Tanggal Masuk">
                    <VControl class="prime-auto">
                      <Calendar :locale="'id'" :dateFormat="H.dateTimeFormat().prime.date" inputId="range" v-model="item.qPeriode" selectionMode="range" :manualInput="false"
                        class="w-100" :showIcon="true" :hideOnRangeSelection="true"  />
                    </VControl>
                  </VField>
                </div>
              <div
                class="column"
                style="margin-top: 25px; margin-left: auto:  !important;"
              >
                <VIconButton
                  type="button"
                  color="success"
                  class="searcv-button"
                  raised
                  icon="fas fa-search"
                  @click="cari()"
                  :loading="isLoadingBtn"
                >
                </VIconButton>
              </div>
            </div>
          </div>
        </div>
      </div>
      <Divider /> -->

      <DataTable :value="dataSource" :paginator="true" :rows="10" :rowsPerPageOptions="[5, 10, 25]" :loading="isLoading" showGridlines>
        <template #header>
           <div class="columns is-multiline pb-3">
              <div class="column is-2" style="padding-top:2rem">
                <VButton color="primary" @click="exportExcel()" outlined icon="fas fa-file-excel" :disabled="dataSource.length == 0"> Export To Excel </VButton>
              </div>
            <div class="column is-10">
          
              <div class="columns is-multiline" style="justify-content: flex-end;">
                  <div class="column is-3 pb-0">
                    <VField label="Periode Tanggal Masuk" style="margin-bottom: 6px;" />
                    <VDatePicker v-model="item.periode" is-range color="pink" trim-weeks>
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
                  <div class="column is-4 pb-0">
                      <VField label="Cari Data">
                          <VControl icon="feather:bookmark">
                              <VInput type="text" v-model="item.search" v-on:keyup.enter="fetchData" placeholder="norm,nama pasien," />
                          </VControl>
                      </VField>
                  </div>
                  <div class="column is-1 btn-search mt-3 pb-0">
                      <VIconButton color="success" icon="fas fa-search" @click="fetchData" :loading="loadSearch" />
                  </div>
              </div>
            </div>
          </div>
        </template>

        <Column field="no" header="No" style="min-width: 10px"></Column>
        <Column field="tglemr" header="Tanggal Masuk" style="min-width: 15px"></Column>
        <Column field="namapasien" header="Nama Pasien" style="min-width: 200px"></Column>
        <Column
          field="jeniskelamin"
          header="Jenis Kelamin"
          style="min-width: 100px"
        ></Column>
        <Column field="nocm" header="No. RM" style="min-width: 50px"></Column>
        <Column field="alamat" header="Alamat" style="min-width: 150px"></Column>
        <Column field="dokter" header="Dokter Jaga" style="min-width: 150px"></Column>
        <Column header="Verifikasi" style="min-width: 10px;text-align:center">
           <template #body="slotProps">
              <i v-if="slotProps.data.isverif == true" class="fas fa-check" aria-hidden="true"></i>
           </template>
        </Column>
        <Column :exportable="false" header="##" style="text-align: center;">
            <template #body="slotProps">
               <VIconButton type="button" icon="fas fa-ellipsis-v" class="mr-2" color="warning" circle outlined
                 raised v-tooltip.top="'Aksi'" @click="toggle($event, slotProps.data)">
               </VIconButton>
            <OverlayPanel ref="op">
               <VButton type="button" icon="fas fa-sign-out-alt" class="mr-2" circle outlined color="info"
                  raised @click="triagePasBaru(selected)">Triage</VButton>
                <VButton type="button" icon="fas fa-check-circle" class="mr-2" color="primary" v-if="selected.isverif != true" circle outlined
                    raised @click="DialogConfirm(selected)">Verifikasi
                </VButton>
                <VButton type="button" icon="fas fa-times-circle" class="mr-2" color="danger" v-else circle outlined
                    raised @click="DialogConfirm(selected)">Batal Verif
                </VButton>
                <VButton type="button" icon="fas fa-paste" class="mr-2" color="primary" circle outlined v-if="selected.nocm && selected.isverif == true"
                    raised @click="registrasi(selected)">Registrasi
                </VButton>
                <VButton type="button" icon="fas fa-user-plus" class="mr-2" color="warning" circle outlined v-if="selected.nocm == '' && selected.isverif == true"
                    raised @click="showModalAuth(selected)">Pasien Baru
                </VButton>
            </OverlayPanel>
          </template>
        </Column>

        <template #footer>
          <div class="columns is-multiline">
            <div class="column is-6">
                Total Pasien = {{ dataSource ? dataSource.length : 0 }}
            </div>
            <div class="column is-6" style="display:flex;justify-content: right;">
              <VButton color="info" icon="fas fa-paste" raised rounded style="margin-left: 20px" @click="modalCollect = true">
                Input Triage Pasien Baru
              </VButton>
            </div>
          </div>
        </template>
      </DataTable>
    </VCard>
  </div>

  <VModal :open="modalCollect" size="big" noclose title="Pasien Baru" actions="center" @close="modalCollect = false, clear()" cancelLabel="Tutup">
    <template #content>
      <div class="column">
        <div class="column is-12">
          <div class="columns is-multiline">
            <div class="column is-3">
              <VControl class="prime-auto">
                <VField>
                  <VLabel>Tanggal & Jam Masuk </VLabel>
                  <Calendar
                    v-model="item.tglemr"
                    selectionMode="single"
                    :manualInput="false"
                    class="w-100" :disabled="true"
                    :showIcon="true"
                    showTime
                    hourFormat="24"
                    :date-format="H.dateTimeFormat().prime.date"
                  />
                </VField>
              </VControl>
            </div>
            <div class="column is-2">
              <VField label="No MR" v-if="item.ceknomr == true">
                <VControl icon="feather:search">
                  <VInput
                    type="text"
                    v-model="item.nocm"
                    v-on:keyup.enter="fetchPasien(item.nocm)"
                    placeholder="No MR"
                  />
                </VControl>
              </VField>
              <VField label="No MR" v-else>
                <VControl icon="feather:search">
                  <VInput type="text" v-model="item.nocm" placeholder="No MR" disabled />
                </VControl>
              </VField>
            </div>
            <div
              class="column is-2 p-0"
              style="margin-top: 3rem; margin-left: px; margin-right: -94px"
            >
              <VField>
                <VControl raw subcontrol>
                  <Checkbox v-model="item.ceknomr" :binary="true" />
                  <label class="ml-2 ingredient1">Ada</label>
                </VControl>
              </VField>
            </div>
            <div class="column is-3">
              <VField>
                <VLabel class="required-field">Nama Pasien</VLabel>
                <VPlaceloadWrap v-if="renderLoader">
                  <VPlaceload height="30px" class="mx-2" />
                </VPlaceloadWrap>
                <VControl v-else icon="feather:bookmark">
                  <VInput
                    type="text"
                    v-model="item.namapasien"
                    placeholder="Nama Lengkap"
                  />
                </VControl>
              </VField>
            </div>

            <div class="column is-2">
              <VControl class="prime-auto">
                <VField>
                  <VLabel class="required-field">Tanggal Lahir</VLabel>
                  <Calendar
                    v-model="item.tgllahir"
                    :locale="'id'"
                    selectionMode="single"
                    :showIcon="true"
                    :manualInput="true"
                    class="w-100"
                    :dateFormat="H.dateTimeFormat().prime.date"
                    :placeholder="H.dateTimeFormat().prime.date"
                  />
                </VField>
              </VControl>
            </div>
            <div class="column is-3">
              <VField>
                <VLabel class="required-field">Jenis Kelamin</VLabel>
                <VControl>
                  <VRadio
                    v-for="items in JenisKelamin"
                    :key="items.id"
                    v-model="item.jeniskelamin"
                    :value="items.label"
                    :label="items.label"
                    name="{{items.id}}"
                    color="primary"
                  />
                </VControl>
              </VField>
            </div>
            <div class="column is-3">
              <VField label="No Telepon">
                <VPlaceloadWrap v-if="renderLoader">
                  <VPlaceload height="30px" class="mx-2" />
                </VPlaceloadWrap>
                <VControl v-else icon="feather:bookmark">
                  <VInput type="text" v-model="item.notlp" placeholder="No Telepon" />
                </VControl>
              </VField>
            </div>
            <div class="column is-6">
              <VField label="Alamat">
                <VPlaceloadWrap v-if="renderLoader">
                  <VPlaceload height="50px" class="mx-2" />
                </VPlaceloadWrap>
                <VControl v-else>
                  <VTextarea v-model="item.alamat" rows="1" placeholder="Alamat Lengkap">
                  </VTextarea>
                </VControl>
              </VField>
            </div>
            <div class="column is-6">
              <VField>
                <VLabel class="required-field">Keluhan</VLabel>
                <VPlaceloadWrap v-if="renderLoader">
                  <VPlaceload height="50px" class="mx-2" />
                </VPlaceloadWrap>
                <VControl v-else>
                  <VTextarea v-model="item.keluhan" rows="2" placeholder="Keluhan">
                  </VTextarea>
                </VControl>
              </VField>
            </div>
            <div class="column is-6 mt-0">
              <VField class="is-autocomplete-select" v-slot="{ id }">
                <VLabel class="required-field">Dokter Jaga</VLabel>
                <VControl icon="feather:search">
                  <AutoComplete
                    v-model="item.dokterJaga"
                    :suggestions="d_Dokter"
                    @complete="fetchDokter($event)"
                    :optionLabel="'label'"
                    :dropdown="true"
                    :minLength="3"
                    :appendTo="'body'"
                    :loadingIcon="'pi pi-spinner'"
                    :field="'label'"
                    placeholder="ketik nama Dokter"
                  />
                </VControl>
              </VField>
            </div>
          </div>
        </div>
      </div>
    </template>

    <template #action>
      <VButton
        icon="feather:save"
        :loading="isLoading"
        @click="triagePasBaru(item)"
        color="primary"
        raised
        >Selanjutnya</VButton
      >
    </template>
  </VModal>

  <VModal :open="modalAuth" size="medium" noclose title="Autentikasi Pasien" actions="right" @close="modalAuth = false, clear()" cancelLabel="Tutup">
    <template #content>
      <div class="column">
        <VTabs align="centered" selected="nama" :tabs="[
            { label: 'Nama Pasien', value: 'nama' },
            { label: 'No Rekam Medis', value: 'nocm' }
          ]"
        >
          <template #tab="{ activeValue }">
            <p v-if="activeValue === 'nama'">
            <div class="columns is-multiline">
              <div class="column is-12">
                <VField label="Nama Pasien">
                    <VControl icon="feather:bookmark">
                      <VInput type="text" v-model="item.qnamapasien" placeholder="Nama Lengkap" />
                    </VControl>
                </VField>
              </div>
              <div class="column is-6">
              <VField>
                      <VLabel class="required-field">Jenis Kelamin</VLabel>
                      <VControl>
                        <VRadio value="1" label="Laki-laki" color="primary" v-model="item.qjenisklm"/>
                        <VRadio value="2" label="Perempuan" color="primary" v-model="item.qjenisklm"/>
                      </VControl>
                    </VField>
              </div>
              <div class="column is-6">
                <VControl class="prime-auto">
                      <VField>
                        <VLabel class="required-field">Tanggal Lahir</VLabel>
                        <Calendar
                          v-model="item.qtgllahir"
                          :locale="'id'"
                          selectionMode="single"
                          :showIcon="true"
                          :manualInput="true"
                          class="w-100"
                          :dateFormat="H.dateTimeFormat().prime.date"
                          :placeholder="H.dateTimeFormat().prime.date"
                        />
                      </VField>
                  </VControl>
              </div>
            </div>
            </p>
            <p v-else-if="activeValue === 'nocm'">
                <div class="column is-12">
                <VField label="No Rekam Medis">
                    <VControl icon="feather:bookmark">
                      <VInput type="text" v-model="item.qnorm" placeholder="Nomer rekam medis" />
                    </VControl>
                </VField>
              </div>
            </p>
          </template>
        </VTabs>
      </div>
    </template>

    <template #action>
      <VButton icon="feather:save" :loading="isLoadingBtn" @click="serchPasien()" color="primary" raised >Selanjutnya</VButton
      >
    </template>
  </VModal>
  
</template>
<script setup lang="ts">
import { useWindowScroll } from '@vueuse/core'
import { useApi } from '/@src/composable/useApi'
import { ref, computed, watch, reactive, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useHead } from '@vueuse/head'
import { useUserSession } from '/@src/stores/userSession'
import ConfirmDialog from 'primevue/confirmdialog'
import { useConfirm } from 'primevue/useconfirm'
import * as H from '/@src/utils/appHelper'
import DataTable from 'primevue/datatable'
import Column from 'primevue/column'
import { useToaster } from '/@src/composable/toaster'
import Dialog from 'primevue/dialog'
import * as XLSX from "xlsx";
import * as XLSXStyle from 'xlsx-js-style';
import AutoComplete from 'primevue/autocomplete'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useThemeColors } from '/@src/composable/useThemeColors'
import OverlayPanel from 'primevue/overlaypanel';
import moment from 'moment'
import TabView from 'primevue/tabview'
import TabPanel from 'primevue/tabpanel'
import Badge from 'primevue/badge'
import Tag from 'primevue/tag'
import Divider from 'primevue/divider'
import sleep from '/@src/utils/sleep'
import Calendar from 'primevue/calendar'
import Checkbox from 'primevue/checkbox'

useHead({
  title: 'Data Pasien - ' + import.meta.env.VITE_PROJECT,
})
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)
const item: any = ref({
  periode: reactive({
    start: new Date(),
    end: new Date(),
  }),
  tglemr: new Date(),
})

const listColor: any = ref(Object.keys(useThemeColors()))

const op = ref();

const pizza = ref();
const selected: any = ref({})
const activeTab = ref(0)
const router = useRouter()
const route = useRoute()
const { y } = useWindowScroll()
const sourceOrder = ref([])
const d_Dokter: any = ref([])
const dataSource = ref([])
const dataDetail = ref([])
const showLama = ref(false)
const modalAuth = ref(false)
const modalInput = ref(false)
const modalVerif = ref(false)
const modalConfirm = ref(false)
const modelCheck: any = ref([])
const isLoadingBtn = ref(false)
const isLoadingBB = ref(false)
const loadSearch = ref(false)
const isLoading = ref(false)
const renderLoader: any = ref(false)

const confirm = useConfirm()
const modalCollect = ref(false)

const currentPage: any = ref({
  limit: 20,
  rows: 50,
})

const JenisKelamin: any = ref([
  { label: 'Laki-laki', value: 'Laki-laki' },
  { label: 'Perempuan', value: 'Perempuan' },
])

currentPage.value.page = computed(() => {
  try {
    return Number.parseInt(route.query.page as string) || 1
  } catch {}
  return 1
})

const fetchPasien = async (nocm: any) => {
  renderLoader.value = true

  await useApi()
    .get(`/farmasi/get-pasien?nocm=${nocm}`)
    .then((response) => {
      if (response) {
        item.value.namapasien = response.namapasien
        item.value.notlp = response.notelepon ? response.notelepon : '-'
        item.value.tgllahir = response.tgllahir
        item.value.alamat = response.alamatlengkap
        item.value.jeniskelamin = response.jeniskelamin
      } else {
        useToaster().error('Pasien Tidak Ditemukan')
      }
      renderLoader.value = false
    })
}

const serchPasien = async () => {
  
  console.log(item.value.qnorm)
  if(!item.value.qnorm){
    if(!item.value.qnamapasien){
      H.alert('error','Pencarian Nama Pasien tidak boleh kosong')
      return
    }
    if(!item.value.qtgllahir){
      H.alert('error','Pencarian Tanggal Lahir tidak boleh kosong')
      return
    }
    if(!item.value.qjenisklm){
      H.alert('error','Pencarian Jenis Kelamin tidak boleh kosong')
      return
    }
  }else{
    if(item.value.qnorm == ''){
      H.alert('error','Pencarian No RM tidak boleh kosong')
      return
    }
  }
  let dateString = item.value.qtgllahir;
  let stringToDate =  moment(dateString, "DD/MM/YYYY").toDate();
  let nocm = item.value.qnorm ? `?nocm=${item.value.qnorm}` : '?nocm='
  let namapasien = item.value.qnamapasien ? `&namapasien=${item.value.qnamapasien}` : ''
  let tgllahir = item.value.qtgllahir ? `&tgllahir=${H.formatDate(stringToDate, 'YYYY-MM-DD')}` : ''
  let jenisKlm = item.value.qjenisklm ? `&jeniskelaminfk=${item.value.qjenisklm}` : ''

  let objPasien = {
    tgllahir : H.formatDate(stringToDate, 'YYYY-MM-DD'),
    namapasien : item.value.qnamapasien,
    jenisKlm : item.value.qjenisklm,
  }

  isLoadingBtn.value = true
  await useApi()
    .get(`/farmasi/get-pasien${nocm}${namapasien}${tgllahir}${jenisKlm}`)
    .then((response) => {
      isLoadingBtn.value = false
      if (response) {
        registrasi(response)
      } else {
        confirmNextStep(objPasien)
      }
    })
}

const fetchData = async () => {

  isLoading.value = true
  let limit: any = currentPage.value.limit
  let offset: any = route.query.page ? route.query.page : 1
  offset = offset * limit - limit

  let dari = `?dari=${H.formatDate(item.value.periode.start, 'YYYY-MM-DD')}`
  let sampai = `&sampai=${H.formatDate(item.value.periode.end, 'YYYY-MM-DD')}`
  let search = item.value.search ? `&search=${item.value.search}` : ''

  if (item.value.qnama) namapasien = `&namapasien=${item.value.qnama}`
  
  loadSearch.value = true

  await useApi().get(`igd/data-pasien${dari}${sampai}${search}`)
    .then((response) => {
      response.forEach((element: any, i: any) => {
        element.no = i + 1
      })
      dataSource.value = response
    })
  loadSearch.value = false
  isLoading.value = false

}

const fetchDokter = async (filter: any) => {
  await useApi()
    .get(`igd/dokter-igd`)
    .then((response) => {
      d_Dokter.value = response.data.map((e: any) => {
        return { label: e.namalengkap, value: e.objectpegawaifk }
      })
    })
}

const DialogConfirm = (e: any) => {
  console.log(e)
  let message = e.isverif == true ? 'Apakah anda yakin akan Batal Verif data ini ?' : 'Apakah anda yakin akan memverifikasi data ini ?'
  let header = e.isverif == true ? 'Batal Verifikasi Triage Pasien !' : 'Verifikasi Triage Pasien'
    
  confirm.require({
    message: message,
    header: header,
    icon: 'pi pi-info-circle',
    acceptClass: 'p-button-danger',
    accept: () => {
      verifTriage(e)
    },
    reject: () => {},
  })
}

const confirmNextStep = (e: any) => {
  confirm.require({
    message: 'Data pasien tidak ditemukan, Lanjut registrasi pasien baru ?',
    header: 'Konfirmasi data pasien',
    icon: 'pi pi-info-circle',
    acceptClass: 'p-button-danger',
    accept: () => {
      pasienBaru(e)
    },
    reject: () => {},
  })
}

const showModalAuth = (e:any) => {
  item.value.qtgllahir = H.formatDate(e.tgllahir, 'DD/MM/YYYY')
  item.value.qnamapasien = e.namapasien
  item.value.qjenisklm = e.jeniskelamin == 'Laki-laki' ? 1 : 2
  modalAuth.value = true
}

const toggle = (event: any, e: any) => {
    console.log(event)
    op.value.toggle(event);
    selected.value = e
}

const verifTriage = async (e: any) => {
  isLoading.value = true

  let objSave = {
    norec : e.norec,
    isverif : e.isverif == true ? null : true
  }
  await useApi()
    .post(`igd/update-triage`, objSave)
    .then(
      (response: any) => {
        clear()
        fetchData()
        isLoading.value = false
      },
      (error) => {
        console.log(error)
      }
    )
}

const isMozilla = () => {
  return navigator.userAgent.indexOf('Firefox') !== -1
}

const triagePasBaru = (e: any) => {
  
  if(!e.norec){
    if(!e.namapasien){
      H.alert('error','Nama Pasien tidak boleh kosong')
      return
    }
    if(!e.jeniskelamin){
      H.alert('error','Jenis Kelamin tidak boleh kosong')
      return
    }
    if(!e.keluhan){
      H.alert('error','Keluhan tidak boleh kosong')
      return
    }
    if(!e.dokterJaga){
      H.alert('error','DPJP tidak boleh kosong')
      return
    }
  }
  if (item.value.nocm) {
    router.push({
      name: 'module-igd-input-triage',
      query: {
        nocm: e.nocm,
        namapasien: e.namapasien,
        alamat: e.alamat,
        noemr: e.noemr,
        tgllahir: H.formatDate(e.tgllahir, 'DD-MMM-YYYY'),
        notelepon: e.notlp,
        tglemr: H.formatDate(e.tglemr, 'DD-MMM-YYYY HH:MM:SS'),
        keluhan : e.keluhan,
        jeniskelamin : e.jeniskelamin
      },
    })
  } else {
    router.push({
      name: 'module-igd-input-triage',
      query: {
        namapasien: e.namapasien,
        alamat: e.alamat,
        noemr: e.noemr,
        tgllahir: H.formatDate(e.tgllahir, 'DD-MMM-YYYY'),
        notelepon: e.notlp,
        tglemr: H.formatDate(e.tglemr, 'DD-MMM-YYYY HH:MM:SS'),
        keluhan : e.keluhan,
        jeniskelamin : e.jeniskelamin
      },
    })
  }

}

const pasienBaru = (e:any) => {
  router.push({
    name: 'module-registrasi-pasien-baru',
    query: {
      namapasien: e.namapasien,
      alamat: selected.alamat,
      jeniskelamin: e.jenisKlm,
      tgllahir: e.tgllahir,
      isIGD: true
    },
  })
}

const registrasi = async (e: any) => {
  
  loadSearch.value = true
  let response = await useApi().get(`/registrasi/cek-pasien-pulang?id=${e.nocmfk}`)
  if (response != null) {
    useToaster().error('Pasien belum dipulangkan')
    return
  }
  router.push({
    name: 'module-registrasi-registrasi-ruangan',
    query: {
      nocmfk: e.nocmfk,
      statuspasien: "BARU",
    },
  })
  loadSearch.value = false
}

function klikTab(e: any) {
  activeTab.value = e.index
}

const exportExcel = (e: any) => {
    const workbook = XLSX.utils.book_new();
    const worksheet = XLSX.utils.aoa_to_sheet([
        ['Daftar Pasien Triage'],
        [],
        ['NO', 'TANGGAL MASUK', 'NAMA PASIEN', 'JENIS KELAMIN', 'NO RM', 'ALAMAT', 'DOKTER JAGA', 'STATUS VERIF'],
        ...dataSource.value.map((e: any) => [
            e.no,
            e.tglemr,
            e.namapasien,
            e.jeniskelamin,
            e.nocm,
            e.alamat,
            e.dokter,
            e.isverif == true ? 'Terverifikasi' : 'Belum',
        ]),
    ]);
    // Mendefinisikan style untuk header(centered)
    const headerStyle = {
        alignment: {
            horizontal: 'center',
            vertical: 'center'
        },
        font: {
            color: { rgb: 'FFFFFF' }
        },
        fill: { fgColor: { rgb: '807C7C' } }
    };

    // Mendefinisikan range header
    const headerRange = XLSX.utils.decode_range(worksheet['!ref']);
    for (let col = headerRange.s.c; col <= headerRange.e.c; col++) {
        const headerCell = XLSX.utils.encode_cell({ r: 2, c: col });
        worksheet[headerCell].s = headerStyle;
    }

    const columnWidths = [5, 15, 30, 15, 15, 30, 25, 20];

    for (let col = headerRange.s.c; col <= headerRange.e.c; col++) {
        worksheet['!cols'] = worksheet['!cols'] || [];
        worksheet['!cols'][col] = { wch: columnWidths[col] };
    }

    // Centering the text in cell A1
    const titleCell = XLSX.utils.encode_cell({ r: 0, c: 0 });
    worksheet[titleCell].s = {
        alignment: {
            horizontal: 'center',
            vertical: 'center'
        },
        font: {
            bold: true,
            sz: 18
        }
    };

    // Menggabungkan dua baris pertama
    const mergeTitle = { s: { r: 0, c: 0 }, e: { r: 1, c: 7 } };
    worksheet['!merges'] = [mergeTitle];

    XLSX.utils.book_append_sheet(workbook, worksheet, 'Remun Jabatan', true);
    XLSXStyle.writeFile(workbook, 'Daftar Remun Jabatan.xlsx');
}


const clear = () => {
  delete item.value.isverif
  delete item.value.qnocm
  delete item.value.qtgllahir
  delete item.value.qnamapasien
  delete item.value.qjenisklm
}

fetchData()


watch(
    () => item.value.qnorm,
    () => {
      delete item.value.qnamapasien
      delete item.value.qjenisklm
      delete item.value.qtgllahir
    }
)

watch(
    () => item.value.qnamapasien,
    () => {
      delete item.value.qnorm
      console.log(item.value.qtgllahir)
    }
)


</script>

<style lang="scss">
@import '/@src/scss/abstracts/all';
@import '/@src/scss/components/forms-outer';
@import '/@src/scss/custom/timeline-css';

.c-title {
  margin-left: -21px;
  padding-top: 21px;
  padding-top: 18px;
  margin-top: -21px;
  border-top-left-radius: 11px;
  border-left: solid hsl(19deg 100% 75% / 72%) 3px;
  padding-bottom: 0px;
  margin-bottom: 2rem;
}

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

.fs-075 {
  font-size: 0.9rem;
}

.is-navbar {
  .form-layout {
    margin-top: 30px;
  }
}

.form-layout {
  // max-width: 740px;
  margin: 0 auto;

  &.is-separate {
    // max-width: 1040px;

    .form-outer {
      background: none;
      border: none;

      .form-body {
        display: flex;

        .form-section {
          flex-grow: 2;
          padding: 10px;
          width: 50%;

          .form-section-inner {
            @include vuero-s-card;

            padding: 40px;

            &.has-padding-bottom {
              padding-bottom: 60px;
              height: 100%;
            }

            > h3 {
              font-family: var(--font-alt);
              font-size: 1.2rem;
              font-weight: 600;
              color: var(--dark-text);
              margin-bottom: 30px;
            }

            .columns {
              .column {
                padding-top: 0.25rem;
                padding-bottom: 0.25rem;
              }
            }

            .radio-boxes {
              display: flex;
              justify-content: space-between;
              margin-left: -8px;
              margin-right: -8px;

              .radio-box {
                position: relative;
                width: calc(50% - 16px);
                margin: 8px;

                &:focus-within {
                  border-radius: 3px;
                  outline-offset: var(--accessibility-focus-outline-offset);
                  outline-width: var(--accessibility-focus-outline-width);
                  outline-style: var(--accessibility-focus-outline-style);
                  outline-color: var(--primary);
                }

                input {
                  position: absolute;
                  top: 0;
                  left: 0;
                  height: 100%;
                  width: 100%;
                  opacity: 0;
                  cursor: pointer;

                  &:checked {
                    + .radio-box-inner {
                      background: var(--primary);
                      border-color: var(--primary);
                      box-shadow: var(--primary-box-shadow);

                      .fee,
                      p {
                        color: var(--smoke-white);
                      }
                    }
                  }
                }

                .radio-box-inner {
                  background: var(--white);
                  border: 1px solid var(--fade-grey-dark-3);
                  text-align: center;
                  border-radius: var(--radius);
                  font-family: var(--font);
                  font-weight: 600;
                  font-size: 0.9rem;
                  transition: color 0.3s, background-color 0.3s, border-color 0.3s,
                    height 0.3s, width 0.3s;
                  padding: 30px 20px;

                  .fee {
                    font-family: var(--font);
                    font-weight: 700;
                    color: var(--dark-text);
                    font-size: 2.4rem;
                    line-height: 1;

                    span {
                      &::after {
                        content: '$';
                        position: relative;
                        top: -10px;
                        font-size: 1.5rem;
                      }
                    }
                  }

                  p {
                    font-family: var(--font-alt);
                  }
                }
              }
            }

            .control {
              > p {
                padding-top: 12px;

                > span {
                  display: block;
                  font-size: 0.9rem;

                  span {
                    font-weight: 500;
                    color: var(--dark-text);
                  }
                }
              }
            }
          }

          .form-section-outer {
            .checkboxes {
              padding: 16px 0;

              .checkbox {
                padding: 0;
                font-size: 0.9rem;
              }
            }

            .button-wrap {
              .button {
                min-height: 60px;
                font-size: 1.05rem;
                font-weight: 600;
                font-family: var(--font-alt);
              }
            }
          }
        }
      }
    }
  }
}

.is-dark {
  .form-layout {
    &.is-separate {
      .form-outer {
        background: none !important;

        .form-body {
          .form-section {
            .form-section-inner {
              @include vuero-card--dark;

              > h3 {
                color: var(--dark-dark-text);
              }

              .radio-boxes {
                .radio-box {
                  input:checked + .radio-box-inner {
                    background: var(--primary);
                    border-color: var(--primary);
                    box-shadow: var(--primary-box-shadow);

                    .fee,
                    p {
                      color: var(--smoke-white);
                    }
                  }

                  .radio-box-inner {
                    background: var(--dark-sidebar-light-2);
                    border-color: var(--dark-sidebar-light-12);

                    .fee {
                      color: var(--dark-dark-text);
                    }
                  }
                }
              }
            }
          }
        }
      }
    }
  }
}

@media only screen and (max-width: 767px) {
  .form-layout {
    &.is-separate {
      .form-outer {
        .form-body {
          padding-left: 0;
          padding-right: 0;
          flex-direction: column;

          .form-section {
            width: 100%;

            .form-section-inner {
              padding: 30px;
            }
          }
        }
      }
    }
  }
}

@media only screen and (min-width: 768px) and (max-width: 1024px) and (orientation: portrait) {
  .form-layout {
    &.is-separate {
      .form-outer {
        .form-body {
          padding-left: 0;
          padding-right: 0;

          // flex-direction: column;

          .form-section {
            // width: 100%;

            .form-section-inner {
              padding: 30px;
            }
          }
        }
      }
    }
  }
}

.all-projects {
  .all-projects-header {
    display: flex;
    padding: 20px;
    background: var(--white);
    border: 1px solid var(--fade-grey-dark-3);
    border-radius: var(--radius-large);
    margin-bottom: 1.5rem;

    .header-item {
      width: 25%;
      border-right: 1px solid var(--fade-grey-dark-3);

      &:last-child {
        border-right: none;
      }

      .item-inner {
        text-align: center;

        .lnil,
        .lnir {
          font-size: 2.2rem;
          margin-bottom: 6px;
          color: var(--primary);
        }

        span {
          display: block;
          font-family: var(--font);
          font-weight: 600;
          font-size: 1.4rem;
          color: var(--dark-text);
        }

        p {
          font-family: var(--font-alt);
        }
      }
    }
  }

  .projects-card-grid {
    .grid-item {
      display: flex;
      flex-direction: column;
      justify-content: space-between;
      min-height: 220px;
      padding: 20px;
      background: var(--white);
      border: 1px solid var(--fade-grey-dark-3);
      border-radius: var(--radius-large);

      .top-section {
        .head {
          display: flex;
          justify-content: space-between;
          align-items: center;
          margin-bottom: 8px;

          h3 {
            font-size: 1rem;
            font-family: var(--font-alt);
            color: var(--dark-text);
            font-weight: 600;
          }
        }

        .body {
          p {
            font-family: var(--font);
            color: var(--light-text);
          }
        }
      }

      .bottom-section {
        display: flex;

        .foot-block {
          margin-right: 30px;

          .heading {
            font-family: var(--font-alt);
            font-size: 0.75rem;
            color: var(--light-text-dark-22);
          }

          > p {
            padding-top: 5px;
          }

          .developers {
            display: flex;

            .v-avatar {
              margin-right: 6px;
            }
          }
        }
      }
    }
  }
}

.heading {
  font-family: var(--font-alt);
  font-size: 0.75rem;
  color: var(--light-text-dark-22);
}

.is-dark {
  .all-projects {
    .all-projects-header {
      background: var(--dark-sidebar-light-6);
      border-color: var(--dark-sidebar-light-12);

      .header-item {
        border-color: var(--dark-sidebar-light-18);

        span {
          color: var(--dark-dark-text);
        }

        i {
          color: var(--primary) !important;
        }
      }
    }

    .projects-card-grid {
      .grid-item {
        background: var(--dark-sidebar-light-6);
        border-color: var(--dark-sidebar-light-12);

        .top-section {
          .head {
            h3 {
              color: var(--dark-dark-text);
            }
          }
        }

        .bottom-section {
          .foot-block {
            .heading {
              color: var(--light-text-dark-12);
            }
          }
        }
      }
    }
  }
}

.tabs-wrapper.is-slider .tabs,
.tabs-wrapper-alt.is-slider .tabs {
  max-width: 30% !important;
}
</style>
