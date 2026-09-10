
<template>
  <ConfirmDialog group="positionDialog"></ConfirmDialog>

  <SelectButton v-model="item.tab" :options="d_Tab" aria-labelledby="basic" class="is-pulled-left mb-2" />

  <VCard v-if="item.tab == 'Penerimaan'">
    <div class="columns ">
      <div class="column ">
        <h3 class="title is-5 mb-2 mr-1">Penerimaan Harian Kasir </h3> <span> ( {{ dataSource.total }}
          Results)</span>
      </div>
      <div class="column ">
        <!-- <VIconButton type="button" color="warning" circle raised icon="fas fa-print" @click="cetakLaporanPenerimaan()"
          :loading="isLoading" class="ml-2 is-pulled-right" v-tooltip.bubble="'Total'">
          <i class="iconify" data-icon="feather:printer" aria-hidden="true"></i>
        </VIconButton> -->
        <VIconButton type="button" color="success" circle raised icon="fas fa-search"
          @click="fetchData()" :loading="isLoading" class="ml-2 is-pulled-right"
          v-tooltip.bubble="'Cari'">
          <i class="iconify" data-icon="feather:printer" aria-hidden="true"></i>
        </VIconButton>
        <VIconButton type="button" color="info" circle raised icon="fas fa-print"
          @click="cetakLaporanPenerimaanHarian()" :loading="isLoading" class="ml-2 is-pulled-right"
          v-tooltip.bubble="'Harian'">
          <i class="iconify" data-icon="feather:printer" aria-hidden="true"></i>
        </VIconButton>
      </div>
    </div>
    <div class="columns  all-projects mt-0 ml-3">
      <div class="columns is-multiline  projects-card-grid" style="width: 100%;">
        <div class="column is-12">
          <VCard class="card-grey">
            <div class="columns is-multiline">

              <div class="column is-6">
                <VDatePicker v-model="item.qFilterTgl" is-range color="pink" trim-weeks :max-date="new Date()">
                  <template #default="{ inputValue, inputEvents }">
                    <VField addons>
                      <VControl icon="feather:calendar">
                        <VInput :value="inputValue.start" class="input-calendar" v-on="inputEvents.start" />
                      </VControl>
                      <VControl>
                        <VButton static><i class="fas fa-arrow-right" aria-hidden="true"></i></VButton>
                      </VControl>
                      <VControl icon="feather:calendar">
                        <VInput :value="inputValue.end" class="input-calendar" v-on="inputEvents.end" />
                      </VControl>
                    </VField>
                  </template>
                </VDatePicker>
              </div>
              <div class="column is-6">
                <VField>
                  <VControl icon="feather:search">
                    <input v-model="item.qnama" v-on:keyup.enter="fetchData()" type="text" class="input is-rounded"
                      placeholder="Cari Nama Pasien ..." />
                  </VControl>
                </VField>
              </div>
              <!-- <div class="column is-3">

                <Badge :value="jmlFilter" v-if="jmlFilter > 0" severity="info" class="is-pulled-right"
                  style="margin-left:-10px ;z-index: 100;  position: relative; "></Badge>
                <VIconButton circle class="ml-2  is-pulled-right" icon="fas fa-filter" raised bold
                  @click="modalFilter = true" v-tooltip.bubble="'Filter'">
                </VIconButton>
                <VIconButton type="button" color="success" circle raised icon="fas fa-search" @click="fetchData()"
                  :loading="isLoading" class=" is-pulled-right">
                </VIconButton>
              </div> -->
            </div>

            <div class="columns is-multiline pb-3 pt-3">
              <div class="column is-6">
                <VField class="is-rounded-select is-autocomplete-select">
                  <VControl icon="feather:search" class="prime-auto-select">
                    <AutoComplete v-model="item.qRuangan" :suggestions="d_Ruangan" @complete="fetchRuangan($event)"
                      :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"  @item-select="fetchData()"
                      :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="Nama Ruangan"/>
                  </VControl>
                </VField>
              </div>
              <div class="column is-6">
                <VField class="is-rounded-select is-autocomplete-select">
                  <VControl icon="feather:search" class="prime-auto-select">
                    <AutoComplete v-model="item.qKelompokpasien" :suggestions="d_KelompokPasien" @complete="fetchKelompokPasien($event)"
                      :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"  @item-select="fetchData()"
                      :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="Kelompok Pasien"/>
                  </VControl>
                </VField>
              </div>
            </div>
          </VCard>
        </div>
        <div class="column is-12">
          <div class="flex-list-inner mb-2 mt-5" v-if="isLoading">
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
          <div class="flex-list-inner" v-else-if="dataSourcefiltered.length === 0">
            <VPlaceholderSection :title="H.assets().notFound" :subtitle="H.assets().notFoundSubtitle" class="my-6">
              <template #image>
                <img class="light-image" :src="H.assets().iconNotFound_rev" alt="" />
                <img class="dark-image" src="/@src/assets/illustrations/placeholders/search-4-dark.svg" alt="" />
              </template>
            </VPlaceholderSection>
          </div>
          <div v-else-if="dataSourcefiltered.length > 0">
            <VFlexTable v-if="dataSourcefiltered.length" :data="dataSourcefiltered" :columns="columns" rounded>
              <template #body>
                <div name="list" tag="div" class="flex-list-inner">
                  <!--Table item-->
                  <div v-for="(item, i)  in dataSourcefiltered" :key="item.id" class="flex-table-item">
                    <VFlexTableCell :column="{ media: true }">
                      <VAvatar size="small" :color="listColor[i]" :initials="item.initials" />
                      <div>
                        <span class="item-name dark-inverted"
                          style="  width: 100px;  white-space: nowrap; overflow: hidden !important; text-overflow: ellipsis;">{{
                            item.namapasien }}</span>
                        <span class="item-meta">
                          <span>
                            <i aria-hidden="true" class="iconify" data-icon="feather:user">
                            </i>{{ item.nocm }}</span>
                        </span>
                      </div>
                    </VFlexTableCell>
                    <VFlexTableCell>
                      <span class="light-text">{{ H.formatDateIndoSimpleNoDay(item.tglsbm) }}</span>
                    </VFlexTableCell>
                    <VFlexTableCell :column="{grow: true}">
                      <span class="light-text">{{ item.namaruangan }}</span>
                    </VFlexTableCell>
                    <!-- v-if="!props.hideColumn" -->
                    <VFlexTableCell  v-if="!props.hideColumn" :column="{ media: true }">
                      <div>
                        <span class="light-text">{{ item.nosbm }}</span>
                        <span class="light-text td-label-xx ">{{ item.keteranganlainnya }}</span>
                      </div>
                    </VFlexTableCell>
                    <VFlexTableCell>
                      <VTag rounded style="font-size:0.8rem;font-weight: bold;justify-content: flex-start;">{{ item.kelompokpasien }}</VTag>
                    </VFlexTableCell>
                    <!-- <VFlexTableCell v-if="!props.hideColumn">
                      <span class="light-text">{{ item.keteranganlainnya }}</span>
                    </VFlexTableCell> -->
                    <VFlexTableCell>
                      <!-- style="text-align: right;display: block; padding-top: 11px;" -->
                      <span class="light-text is-pulled-right">{{ H.formatRp(item.totaldibayar, 'Rp. ') }}</span>
                    </VFlexTableCell>
                    <VFlexTableCell :column="{ align: 'end' }">
                      <VDropdown icon="feather:more-vertical" spaced right class="is-pulled-right">
                        <!-- <VDropdown spaced dots right> -->
                        <!-- <template #button="{ open, toggle, close }">
                          <VIconButton icon="feather:more-vertical" class="is-trigger" @mouseenter="open" @focusin="open"
                            @focusout="close" light raised circle @click="toggle" color="info">
                            Aksi
                          </VIconButton>
                        </template> -->
                        <template #content>
                          <a role="menuitem" class="dropdown-item is-media" @click="ubahCaraBayar(item)">
                            <div class="icon">
                              <i class="iconify lnir lnir-users" aria-hidden="true"></i>
                            </div>
                            <div class="meta">
                              <span>Ubah Cara Bayar</span>
                            </div>
                          </a>
                          <a role="menuitem" class="dropdown-item is-media" @click="cetakKwitansi(item)">
                            <div class="icon">
                              <i class="iconify lnir lnir-calender-alt-2" aria-hidden="true"></i>
                            </div>
                            <div class="meta">
                              <span>Cetak Kwitansi</span>
                            </div>
                          </a>
                          <a role="menuitem" class="dropdown-item is-media" @click="batalBayar(item)">
                            <div class="icon">
                              <i class="iconify fas fa-ban" aria-hidden="true"></i>
                            </div>
                            <div class="meta">
                              <span>Batal Bayar</span>
                            </div>
                          </a>
                        </template>
                      </VDropdown>
                    </VFlexTableCell>
                  </div>
                </div>
              </template>
            </VFlexTable>
            <VCard>
              <div class="columns is-multiline">
                <div class="column is-4 is-offset-8">
                  <b>TOTAL : {{ H.formatRp(item.totalAll, 'Rp. ') }}</b>
                </div>
              </div>
            </VCard>
            <VFlexPagination v-model:current-page="currentPage.page" :item-per-page="currentPage.limit"
              :total-items="dataSource.total" :max-links-displayed="5">
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
            <div class="columns is-multiline">
              <div class="column is-4" v-for="(cb, i) in d_CaraBayarFoot" style="margin-top:-10px">
                <VCardCustom :style="'padding:5px 25px;margin:0;background:#fafafa'">
                  <div :class="'label-status ' + listColor[i]">
                    <i aria-hidden="true" class="fas fa-circle"></i>
                    <span class="ml-1">{{ cb.carabayar }}</span>
                  </div>
                  <small class="text-bold-custom">{{
                    H.formatRp(cb.total,
                      '')
                  }}</small>
                </VCardCustom>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

  </VCard>

  <!-- <Dialog v-model:visible="modalCaraBayar" modal header="Cara Bayar" :style="{ width: '20vw' }">
    <VField class="mb-0">
      <p>Nama Pasien</p>
      <p class="text-black">{{ item.namapasien }} </p>
    </VField>
    <VField label="Cara Bayar" class="is-rounded-select is-autocomplete-select
                              mt-0 pt-0" v-slot="{ id }">
      <VControl icon="fas fa-credit-card" fullwidth class="prime-auto-select">
        <Dropdown v-model="item.caraBayar" :options="d_CaraBayar" :optionLabel="'carabayar'" class="is-rounded"
          placeholder="Cara Bayar" style="width: 100%;" :filter="true" showClear />
      </VControl>
    </VField>
    <template #footer>
      <VButton icon="lnir lnir-arrow-left rem-100" light dark-outlined @click="modalCaraBayar = false">
        Tutup
      </VButton>
      <VButton type="button" rounded outlined color="primary" raised icon="feather:save" :loading="isLoading"
        @click="simpanCaraBayar()"> Ubah
      </VButton>
    </template>
  </Dialog>
  <Dialog v-model:visible="modalFilter" modal header="Filter" :style="{ width: '40vw' }">
    <div class="columns is-multiline">
      <div class="column is-6">
        <VField label="No RM">
          <VControl icon="feather:search">
            <input v-model="item.qnocm" type="text" class="input is-rounded" placeholder="No RM" />
          </VControl>
        </VField>
      </div>
      <div class="column is-6">
        <VField label="Nama Pasien">
          <VControl icon="feather:search">
            <input v-model="item.qnama" type="text" class="input is-rounded" placeholder="Nama Pasien" />
          </VControl>
        </VField>
      </div>
      <div class="column is-6">
        <VField label="Kasir" class="is-rounded-select is-autocomplete-select
                              mt-0 pt-0" v-slot="{ id }">
          <VControl icon="fas fa-users" fullwidth class="prime-auto-select">
            <MultiSelect v-model="item.qKasir" display="chip" :options="d_Kasir" optionLabel="namalengkap"
              placeholder="Kasir" optionValue="id" class="is-rounded w-100" :maxSelectedLabels="3" />
          </VControl>
        </VField>
      </div>
      <div class="column is-6">
        <VField label="Instalasi" class="is-rounded-select is-autocomplete-select
                              mt-0 pt-0" v-slot="{ id }">
          <VControl icon="fas fa-archway" fullwidth class="prime-auto-select">
            <Dropdown v-model="item.qInstalasi" :options="d_Departemen" :optionLabel="'namadepartemen'" class="is-rounded"
              placeholder="Instalasi" style="width: 100%;" :filter="true" @change="changeInst($event)" showClear />
          </VControl>
        </VField>
      </div>
      <div class="column is-6">
        <VField label="Ruangan" class="is-rounded-select is-autocomplete-select
                              mt-0 pt-0" v-slot="{ id }">
          <VControl icon="fas fa-home" fullwidth class="prime-auto-select">
            <Dropdown v-model="item.qRuangan" :options="d_Ruangan" :optionLabel="'namaruangan'" class="is-rounded"
              placeholder="Ruangan" style="width: 100%;" :filter="true" showClear />
          </VControl>
        </VField>
      </div>
      <div class="column is-6">
        <VField label="Cara Bayar" class="is-rounded-select is-autocomplete-select
                              mt-0 pt-0" v-slot="{ id }">
          <VControl icon="fas fa-credit-card" fullwidth class="prime-auto-select">
            <Dropdown v-model="item.qCaraBayar" :options="d_CaraBayar" :optionLabel="'carabayar'" class="is-rounded"
              placeholder="Cara Bayar" style="width: 100%;" :filter="true" showClear />
          </VControl>
        </VField>
      </div>
    </div>
    <template #footer>
      <VButton icon="lnir lnir-arrow-left rem-100" light dark-outlined @click="clearFilter()">
        Bersihkan
      </VButton>
      <VButton type="button" rounded outlined color="primary" raised icon="feather:filter" :loading="isLoading"
        @click="terapkanFilter()"> Terapkan
      </VButton>
    </template>
  </Dialog> -->

  <VCard v-if="item.tab == 'Pengeluaran'">
    <DaftarPengeluaran />
  </VCard>
</template>
<script setup lang="ts">
import { useWindowScroll } from '@vueuse/core'
import { useApi } from '/@src/composable/useApi'
import { ref, computed, watch, PropType } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useHead } from '@vueuse/head'
import { useThemeColors } from '/@src/composable/useThemeColors'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useUserSession } from '/@src/stores/userSession'
import { useConfirm } from "primevue/useconfirm";
import Dialog from 'primevue/dialog';
import Dropdown from 'primevue/dropdown';
import AutoComplete from 'primevue/autocomplete';
import * as H from '/@src/utils/appHelper'
import ConfirmDialog from 'primevue/confirmdialog';
import MultiSelect from 'primevue/multiselect';
import Badge from 'primevue/badge';
import SelectButton from 'primevue/selectbutton';
import * as qzService from '/@src/utils/qzTrayService'
import DaftarPengeluaran from '../kasir/daftar-pengeluaran.vue';
useHead({
  title: 'Daftar Penerimaan - ' + import.meta.env.VITE_PROJECT,
})
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
const dataSource: any = ref([])
const route = useRoute()
const isLoading: any = ref(false)
const confirm = useConfirm();
const modalCaraBayar: any = ref(false)
const modalFilter: any = ref(false)
const jmlFilter: any = ref(0)
const listColor: any = ref(Object.keys(useThemeColors()))
const d_Tab = ref(['Penerimaan', 'Pengeluaran']);
const item: any = ref({
  tab: d_Tab.value[0],
  qFilterTgl: {
    start: new Date(),
    end: new Date()
  },
  qRuangan: ref(''),
  qKelompokpasien: ref(''),
})
const d_CaraBayar: any = ref([])
const d_CaraBayarFoot: any = ref([])
const d_Kasir: any = ref([])
const d_Departemen: any = ref([])
const d_Ruangan: any = ref([])
const dataSourcefiltered: any = ref([])
const d_KelompokPasien: any = ref([])
const d_KelompokTransaksi: any = ref([])
const { y } = useWindowScroll()
const userLogin = useUserSession().getUser()
const isStuck = computed(() => {
  return y.value > 30
})
const props = defineProps({
  hideColumn: {
    type: Boolean
  },
})
let columns: any = ref({})

if (props.hideColumn == true) {
  columns.value = {
    pasien: {
      label: 'PASIEN',
      grow: true,
      media: true,
    },
    tglsbm: 'TANGGAL',
    carabayar: 'CARA BAYAR',
    // keteranganlainnya: 'KET',
    totaldibayar: {
      label: 'SUBTOTAL',
      cellClass: 'h-hidden-tablet-p',
    },
    actions: {
      label: 'Aksi',
      align: 'end',
    },
  }

} else {
  columns.value = {
    picture: {
      label: 'PASIEN',
      grow: true,
      media: true,
    },
    customer: {
      label: 'TANGGAL',
    },
    room: {
      label: 'RUANGAN',
      grow: true
    },
    industry: {
      label: 'NO SBM',
      // grow: true
    },
    status: 'KELOMPOK PASIEN',
    team: {
      label: 'SUBTOTAL',
      cellClass: 'h-hidden-tablet-p',
    },
    actions: {
      label: 'Aksi',
      align: 'end',
    },
  }

}

const currentPage: any = ref({
  limit: 5,
  rows: 50
})
const filters = ref('')
// const dataSourcefiltered = computed(() => {
//   if (!filters.value) {
//     return dataSource.value
//   }

//   return dataSource.value.filter((items: any) => {
//     return (
//       items.namapasien.match(new RegExp(filters.value, 'i')) ||
//       items.nocm.match(new RegExp(filters.value, 'i'))
//     )
//   })
// })


const confirmPosition = (position: any, e: any) => {
  confirm.require({
    group: 'positionDialog',
    message: 'Yakin mau batal bayar?',
    header: 'Info ',
    icon: 'pi pi-info-circle',
    acceptClass: 'p-button-danger',
    position: position,
    accept: () => {

      var objSave = {
        norec_sbmcr: e.norec_sbmcr,
        norec: e.norec,
        norec_sp: e.norec_sp,
        namapasien: e.namapasien,
        nocm: e.nocm,
        nosbm: e.nosbm,
        isdeposit: e.keteranganlainnya == 'Pembayaran Deposit Pasien'
      }
      isLoading.value = true
      useApi().post(
        `/kasir/daftar-penerimaan/batal-bayar`, objSave).then((response: any) => {
          isLoading.value = false
          fetchData()
        }).catch((e: any) => {
          isLoading.value = false
        })
    },
    reject: () => {
    }
  });
};
currentPage.value.page = computed(() => {
  try {
    return Number.parseInt(route.query.page as string) || 1
  } catch { }
  return 1
})
for (var i = listColor.value.length - 1; i >= 0; i--) {
  const element = listColor.value[i];
  if (element == 'primary') {
    listColor.value.splice(i, 1);
  }
}
watch(currentPage.value, () => {
  fetchData()
})

async function fetchData() {
  let limit: any = currentPage.value.limit
  let offset: any = route.query.page ? route.query.page : 1
  offset = (offset * limit) - limit

  let dari = ''
  if (item.value.qFilterTgl) {
    dari = H.formatDate(item.value.qFilterTgl.start, 'YYYY-MM-DD')
  }
  let sampai = ''
  if (item.value.qFilterTgl) {
    sampai = H.formatDate(item.value.qFilterTgl.end, 'YYYY-MM-DD')
  }
  let namapasien = ''
    , nocm = ''
    , noreg = ''
    , carabayar = ''
    , kasir = ''
    , inst = ''
    , ruang = ''
    , kelompokpasien = ''

  if (item.value.qnoreg) noreg = item.value.qnoregistrasi
  jmlFilter.value = 0
  if (item.value.qnocm) {
    nocm = item.value.qnocm
    jmlFilter.value += 1
  }
  if (item.value.qnama) {
    namapasien = item.value.qnama
    jmlFilter.value += 1
  }
  if (item.value.qKasir && item.value.qKasir.length) {
    kasir = item.value.qKasir.join(',')
    jmlFilter.value += 1
  }
  if (item.value.qRuangan) {
    ruang = item.value.qRuangan.value
    jmlFilter.value += 1
  }
  if (item.value.qInstalasi) {
    inst = item.value.qInstalasi.id
    jmlFilter.value += 1
  }
  if (item.value.qCaraBayar) {
    carabayar = item.value.qCaraBayar.id
    jmlFilter.value += 1
  }
  if(item.value.qKelompokpasien){
    kelompokpasien = item.value.qKelompokpasien.value
  }

  item.value.totalAll = 0
  isLoading.value = true
  dataSource.value = []
  const response = await useApi().get(
    '/kasir/daftar-penerimaan?'
    + '&dari=' + dari
    + '&sampai=' + sampai
    + '&qnama=' + item.value.qnama
    + '&carabayar=' + carabayar
    + '&ins=' + inst
    + '&ruang=' + ruang
    + '&kelompokpasien=' + kelompokpasien
    + '&kasirArr=' + kasir
    + '&offset=' + offset
    + '&limit=' + limit
    + '&idPegawai=' + userLogin.pegawai.id
    + '&rows=' + currentPage.value.rows
  )
  isLoading.value = false
  for (let x = 0; x < response.data.length; x++) {
    const element = response.data[x];
    let ini = element.namapasien.split(' ')
    let init = element.namapasien.substr(0, 1)
    if (ini.length > 1) {
      init = init + ini[1].substr(0, 1)
    }
    element.initials = init

  }
  for (var p = response.carabayar.length - 1; p >= 0; p--) {
    const elementq = response.carabayar[p];
    if (elementq.total == 0) {
      response.carabayar.splice(p, 1)
    }
  }
  d_CaraBayarFoot.value = response.carabayar
  item.value.totalAll = response.total

  dataSourcefiltered.value = response.data
  dataSource.value.idKasir = response.idkasir
  dataSource.value.total = response.count
}
async function fetchDropdown() {
  await useApi().get(
    `/kasir/daftar-penerimaan/dropdown`).then((response: any) => {

      d_CaraBayar.value = response.carabayar
      d_Departemen.value = response.departemen
      d_Kasir.value = response.kasir
      d_KelompokTransaksi.value = response.kelompoktransaksi
    })
}
function batalBayar(e: any) {
  confirmPosition('top', e)
}
function cetakKwitansi(e: any) {
  qzService.printData(`kasir/daftar-penerimaan/report/kwitansi?pdf=true&norec=${e.norec}`,'KWITANSI',1)
  // H.printBlade('kasir/daftar-penerimaan/report/kwitansi?pdf=true&norec=' + e.norec)
}
function cetakLaporanPenerimaan() {

  const dari = H.formatDate(item.value.qFilterTgl.start, 'YYYY-MM-DD')
  const sampai = H.formatDate(item.value.qFilterTgl.end, 'YYYY-MM-DD')

  H.printBlade('report/kasir/laporan-penerimaan?pdf=true&tglAwal=' + dari + '&tglAkhir=' + sampai + '&idKasir=' + item.value.qKasir + '&idRuangan=' + item.value.qRuangan);
}
function cetakLaporanPenerimaanHarian() {

  const dari = H.formatDate(item.value.qFilterTgl.start, 'YYYY-MM-DD')
  const sampai = H.formatDate(item.value.qFilterTgl.end, 'YYYY-MM-DD')
  const ruangan = item.value.qRuangan.value
  const kelompokpasien = item.value.qKelompokpasien.value
  const idKasir = userLogin.pegawai.id
  const nama = item.value.qnama

  H.printBlade('report/kasir/laporan-penerimaan-harian?pdf=true&tglAwal=' + dari + '&tglAkhir=' + sampai + '&idKasir=' + idKasir + '&idRuangan=' + ruangan + '&idKelompokPasien=' + kelompokpasien + '&nama=' + nama);
}

function ubahCaraBayar(e: any) {
  for (let x = 0; x < d_CaraBayar.value.length; x++) {
    const element = d_CaraBayar.value[x];
    if (element.id == e.objectcarabayarfk) {
      item.value.caraBayar = element
      break
    }
  }
  item.value.namapasien = e.namapasien
  item.value.nocm = e.nocm
  item.value.nosbm = e.nosbm
  item.value.norec_sbmcr = e.norec_sbmcr
  modalCaraBayar.value = true
}
function simpanCaraBayar() {
  if (item.value.caraBayar == undefined) {
    H.alert('error', 'Isi Cara Bayar dulu')
    return
  }
  var objSave = {
    norec: item.value.norec_sbmcr,
    carabayar: item.value.caraBayar.id,
    carabayarname: item.value.caraBayar.carabayar,
    namapasien: item.value.namapasien,
    nocm: item.value.nocm,
    nosbm: item.value.nosbm,
  }
  isLoading.value = true
  useApi().post(
    `/kasir/daftar-penerimaan/ubah-cara-bayar`, objSave).then((response: any) => {
      isLoading.value = false
      modalCaraBayar.value = false
      fetchData()
    }).catch((e: any) => {
      isLoading.value = false
    })
}
const fetchRuangan = async (filter: any) => {
    await useApi().get(
        `emr/dropdown/ruangan_m?select=id,namaruangan,objectdepartemenfk&param_search=namaruangan&query=${filter.query}`
    ).then((response) => {
        // Filter the response to include only items with objectdepartemenfk = 73
        const filteredResponse = response.filter(item => item.objectdepartemenfk === 73);
        d_Ruangan.value = filteredResponse;
    });
}

const fetchKelompokPasien = async (filter: any) => {
    await useApi().get(`emr/dropdown/kelompokpasien_m?select=id,kelompokpasien&param_search=kelompokpasien&query=${filter.query}&limit=10`
    ).then((response) => {
        d_KelompokPasien.value = response
    })
}

function terapkanFilter() {
  fetchData()
  modalFilter.value = false
}
function clearFilter() {
  delete item.value.qKasir
  delete item.value.qRuangan
  delete item.value.qInstalasi
  delete item.value.qCaraBayar
  delete item.value.qnama
  delete item.value.qnocm
  fetchData()
  modalFilter.value = false
}
function changeInst(e: any) {
  d_Ruangan.value = e.value ? e.value.ruangan : []
}
watch(
  () => item.value.qFilterTgl,
  (newValue, oldValue) => {
    if(newValue != oldValue){
      fetchData()
    }
  },
)
fetchDropdown()
fetchData()

</script>
<style lang="scss">
@import '/@src/scss/abstracts/all';
@import '/@src/scss/components/forms-outer';
@import '/@src/scss/module/kasir/billing';
@import '/@src/scss/custom/listview';

.all-projects .projects-card-grid .grid-item {
  min-height: 100%;
}

.label-status {
  font-size: 0.6rem;
}

.field>label {
  color: var(--light-text) !important;
}
</style>
