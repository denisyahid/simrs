<template>
  <ConfirmDialog group="positionDialog"></ConfirmDialog>

  <SelectButton v-model="item.tab" :options="d_Tab" aria-labelledby="basic" class="is-pulled-left mb-2" />

  <VCard class="card-grey" v-if="item.tab == 'Penerimaan'">
    <div class="columns is-multiline">
      <div class="column is-12">
        <h3 class="title is-5 mb-2 mr-1">Filter </h3> 
      </div>
      <div class="column is-2">
        <VDatePicker v-model="item.qFilterTglAwal" color="pink" :max-date="new Date()">
          <template #default="{ inputValue, inputEvents }">
            <VField>
              <VControl icon="lucide:calendar">
                <input class="input v-input" type="text" :value="inputValue" v-on="inputEvents">
              </VControl>
            </VField>
          </template>
        </VDatePicker>
      </div>
      <div class="column is-2">
        <VDatePicker v-model="item.qFilterTglAkhir" color="pink" :max-date="new Date()">
          <template #default="{ inputValue, inputEvents }">
            <VField>
              <VControl icon="lucide:calendar">
                <input class="input v-input" type="text" :value="inputValue" v-on="inputEvents">
              </VControl>
            </VField>
          </template>
        </VDatePicker>
      </div>
      <div class="column is-3">
        <!-- <VField class="is-autocomplete-select">
            <VControl>
              <Multiselect v-model="item.tipekasir" :attrs="{ value }" placeholder="--Pilih Kasir--" label="label"
                :options="d_listKasir" :searchable="true" track-by="label" mode="single" autocomplete="off">
              </Multiselect>
            </VControl>
          </VField> -->
          <VField class="is-rounded-select is-autocomplete-select
                              mt-0 pt-0" v-slot="{ id }">
            <VControl icon="fas fa-home" fullwidth class="prime-auto-select">
              <Dropdown v-model="item.tipekasir" :options="d_RuanganKasir" :optionLabel="'namaruangan'" class="is-rounded"
                placeholder="Ruangan" style="width: 100%;" :filter="true" showClear />
            </VControl>
          </VField>
      </div>
      <div class="column is-3">
        <VField>
          <VControl>
            <AutoComplete v-model="item.userKasir" :suggestions="d_pegawai"
              @complete="getPegawai($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
              :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
              placeholder="Pilih Pegawai"/>
          </VControl>
        </VField>
      </div>
      <div class="column is-2">
        <VField>
          <VControl>
            <VField>
              <VControl>
                <VSelect v-model="item.exporttype">
                  <VOption value="">
                    -- Pilih Tipe Export --
                  </VOption>
                  <VOption value="PDF">
                    PDF
                  </VOption>
                  <VOption value="EXCEL">
                    EXCEL
                  </VOption>
                </VSelect>
              </VControl>
            </VField>
            <!-- <AutoComplete v-model="item.userKasir" :suggestions="d_pegawai"
              @complete="getPegawai($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
              :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
              placeholder="Pilih Pegawai"/> -->
          </VControl>
        </VField>
      </div>
      <div class="column is-12">
        <h3 class="title is-5 mb-2 mr-1">Jenis Laporan </h3> 
      </div>
      <div class="column is-4">
        <VButton type="button" icon="fas fa-notes-medical" class="is-fullwidth mr-3" color="info" outlined raised @click="cetakLaporanPenerimaanHarian()">
          Laporan Harian Pemasukan FO</VButton>
      </div>
      <div class="column is-4">
        <VButton type="button" icon="fas fa-notes-medical" class="is-fullwidth mr-3" color="info" outlined raised @click="cetakLaporanPenerimaan()">
          Laporan Pendapatan Unit Rekap</VButton>
      </div>
      <div class="column is-4">
        <VButton type="button" icon="fas fa-notes-medical" class="is-fullwidth mr-3" color="info" outlined raised @click="cetakLaporanPenerimaanPerunit()">
          Laporan Pendapatan Unit</VButton>
      </div>
      <div class="column is-4">
        <VButton type="button" icon="fas fa-notes-medical" class="is-fullwidth mr-3" color="info" outlined raised @click="cetakLaporanLost()">
          Laporan Pasien LOST</VButton>
      </div>
      <div class="column is-4">
        <VButton type="button" icon="fas fa-notes-medical" class="is-fullwidth mr-3" color="info" outlined raised @click="cetakLaporanObatBebas()">
          Laporan Pasien Obat Bebas</VButton>
      </div>
    </div>
  </VCard>


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
            <Dropdown v-model="item.qInstalasi" :options="d_Departemen" :optionLabel="'namadepartemen'"
              class="is-rounded" placeholder="Instalasi" style="width: 100%;" :filter="true"
              @change="changeInst($event)" showClear />
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
      <div class="column is-6">
        <VField label="Ruangan" class="is-rounded-select is-autocomplete-select
                              mt-0 pt-0" v-slot="{ id }">
          <VControl icon="fas fa-home" fullwidth class="prime-auto-select">
            <Dropdown v-model="item.qRuanganK" :options="d_RuanganKasir" :optionLabel="'namaruangan'" class="is-rounded"
              placeholder="Ruangan" style="width: 100%;" :filter="true" showClear />
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
  </Dialog>

  <VCard v-if="item.tab == 'Pengeluaran'">
    <DaftarPengeluaran />
  </VCard>
</template>
<script setup lang="ts">
import { useWindowScroll } from '@vueuse/core'
import { useApi } from '/@src/composable/useApi'
import { ref, computed, watch, PropType, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useHead } from '@vueuse/head'
import { useThemeColors } from '/@src/composable/useThemeColors'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useConfirm } from "primevue/useconfirm";
import Dialog from 'primevue/dialog';
import Dropdown from 'primevue/dropdown';
import * as H from '/@src/utils/appHelper'
import ConfirmDialog from 'primevue/confirmdialog';
import MultiSelect from 'primevue/multiselect';
import Badge from 'primevue/badge';
import SelectButton from 'primevue/selectbutton';
import * as qzService from '/@src/utils/qzTrayService'
import DaftarPengeluaran from '../kasir/daftar-pengeluaran.vue';
import AutoComplete from 'primevue/autocomplete';
import { useUserSession } from '/@src/stores/userSession'

useHead({
  title: 'Daftar Penerimaan - ' + import.meta.env.VITE_PROJECT,
})
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
const dataSource: any = ref([])
const route = useRoute()
const isLoading: any = ref(false)
const confirm = useConfirm();
const modalFilter: any = ref(false)
const jmlFilter: any = ref(0)
const listColor: any = ref(Object.keys(useThemeColors()))
const d_Tab = ref(['Penerimaan']);
const d_pegawai = ref([]);
const userLogin = useUserSession().getUser()
const item: any = ref({
  tab: d_Tab.value[0],
  qFilterTglAwal: new Date(),
  qFilterTglAkhir: new Date(),
})
const d_CaraBayar: any = ref([])
const d_CaraBayarFoot: any = ref([])
const d_Kasir: any = ref([])
const d_Departemen: any = ref([])
const d_Ruangan: any = ref([])
const d_RuanganKasir: any = ref([])
const d_listKasir: any = ref([
  { value: 'rajal', label: 'Kasir Rawat Jalan' },
  { value: 'ranap', label: 'Kasir Rawat Inap' },
  { value: 'igd', label: 'Kasir Gawat Darurat' },
  { value: 'kanker', label: 'Kasir Gedung Kanker'}
])

const d_KelompokTransaksi: any = ref([])
const { y } = useWindowScroll()
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
    tglsbm: 'TGL',
    carabayar: 'CARA BAYAR',
    // keteranganlainnya: 'KET',
    totaldibayar: {
      label: 'SUBTOTAL',
      cellClass: 'h-hidden-tablet-p',
    },
    // ubahnama: 'UBAH NAMA',
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
    customer: 'TGL',
    industry: 'NO SBM',
    status: 'CARA BAYAR',
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
const dataSourcefiltered = computed(() => {
  if (!filters.value) {
    return dataSource.value
  }

  return dataSource.value.filter((items: any) => {
    return (
      items.namapasien.match(new RegExp(filters.value, 'i')) ||
      items.nocm.match(new RegExp(filters.value, 'i'))
    )
  })
})


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
  let sampai = ''
  if (item.value.qFilterTglAwal && item.value.qFilterTglAkhir) {
    dari = H.formatDate(item.value.qFilterTglAwal, 'YYYY-MM-DD')
    sampai = H.formatDate(item.value.qFilterTglAkhir, 'YYYY-MM-DD')
  }
  // if (item.value.qFilterTgl) {
  //   sampai = H.formatDate(item.value.qFilterTgl, 'YYYY-MM-DD')
  // }
  let namapasien = ''
    , nocm = ''
    , noreg = ''
    , carabayar = ''
    , kasir = ''
    , inst = ''
    , ruang = ''
    , ruang2 = ''
    , search = ''


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
    ruang = item.value.qRuangan.id
    jmlFilter.value += 1
  }
  if (item.value.qRuanganK) {
    ruang2 = item.value.qRuanganK.id
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
  if (item.value.search) {
    search = item.value.search
    jmlFilter.value += 1
  }

  item.value.totalAll = 0
  isLoading.value = true
  dataSource.value = []
  const response = await useApi().get(
    '/kasir/daftar-penerimaan?'
    + '&dari=' + dari
    + '&sampai=' + sampai
    + '&namapasien=' + namapasien
    + '&nocm=' + nocm
    + '&noreg=' + noreg
    + '&carabayar=' + carabayar
    + '&ins=' + inst
    + '&ruang=' + ruang
    + '&ruangkasir=' + ruang2
    + '&search=' + search
    + '&kasirArr=' + kasir
    + '&offset=' + offset
    + '&limit=' + limit
    + '&rows=' + currentPage.value.rows
  )
  isLoading.value = false
  if(response.penerimaan.length > 0) {
    for (let x = 0; x < response.penerimaan.length; x++) {
      const element = response.penerimaan[x];
      let ini = element.namapasien.split(' ')
      let init = element.namapasien.substr(0, 1)
      if (ini.length > 1) {
        init = init + ini[1].substr(0, 1)
      }
      element.initials = init
  
    }
  }

  // BUKA AJA SEMUA KALO BUTUHH
  // for (var p = response.carabayar.length - 1; p >= 0; p--) {
  //   const elementq = response.carabayar[p];
  //   if (elementq.total == 0) {
  //     response.carabayar.splice(p, 1)
  //   }
  // }
  // d_CaraBayarFoot.value = response.carabayar
  // item.value.totalAll = response.total

  // dataSource.value = response.data
  // dataSource.value.total = response.count

  var objSave = {
    tglAwal: dari + ' 00:00:00',
    tglAkhir: sampai + ' 23:59:59',
  }
  useApi().postNoMessage('/general/save-jurnal-pembayaran_tagihan', objSave)
  // set page to 1
  route.query.page = 1

}
async function fetchDropdown() {
  await useApi().get(
    `/kasir/daftar-penerimaan/dropdown`).then((response: any) => {

      d_CaraBayar.value = response.carabayar
      d_Departemen.value = response.departemen
      d_Kasir.value = response.kasir
      d_RuanganKasir.value = response.ruangankasir
      
      d_KelompokTransaksi.value = response.kelompoktransaksi
      for (let index = 0; index < response.ruangankasir; index++) {
        const element = response.ruangankasir[index];
        if(kelompokUser == 'kasir-rajal' && element.id == 359){
          item.value.tipekasir = element
          break;
        } else if(kelompokUser == 'kasir-igd' && element.id == 361){
          item.value.tipekasir = element
          break;
        } else if(kelompokUser == 'kasir-ranap' && element.id == 360){
          item.value.tipekasir = element
          break;
        }else if(kelompokUser == 'kasir-kanker' && element.id == 247) {
          item.value.tipekasir = element
          break;
        }
      }
    })
}
function batalBayar(e: any) {
  confirmPosition('top', e)
}
function cetakKwitansi(e: any) {
  // qzService.printData(`kasir/daftar-penerimaan/report/kwitansi?pdf=true&norec=${e.norec}`,'KWITANSI',1)
  //H.printBlade('kasir/daftar-penerimaan/report/kwitansi?pdf=true&norec=' + e.norec)
  H.printBlade('kasir/daftar-penerimaan/report/kwitansi-rajal-wna?noregistrasi=' + e.noregistrasi + '&rekap=true');
  //H.printBlade('kasir/daftar-penerimaan/report/kwitansi-rajal-wna?pdf=true&noregistrasi=' + item.value.qnoregistrasi)
}
function cetakKwitansiRanap(e: any) {
  H.printBlade('kasir/daftar-penerimaan/report/kwitansi-ranap-wna?noregistrasi=' + e.noregistrasi + '&rekap=true');
}
function cetakLaporanPenerimaan() {
  console.log('TIPE KASIR', item.value.tipekasir);
  if(!item.value.userKasir) {
    H.alert('warning', 'Kasir tidak boleh kosong');
    return;
  }
  if(!item.value.tipekasir) {
    H.alert('warning', 'Tipe Kasir tidak boleh kosong');
    return;
  }
  if(!item.value.exporttype) {
    H.alert('warning', 'Tipe Export tidak boleh kosong');
    return;
  }

  const dari = H.formatDate(item.value.qFilterTglAwal, 'YYYY-MM-DD')
  const sampai = H.formatDate(item.value.qFilterTglAkhir, 'YYYY-MM-DD')
  let tipe = item.value.tipekasir.namaruangan;
  let ispdf = true;
  if(item.value.exporttype == 'EXCEL') {
    ispdf = false;
  }

  H.printBlade('report/kasir/laporan-penerimaan?pdf=true&tglAwal=' + dari + '&tglAkhir=' + sampai + '&idKasir=' + item.value.userKasir?.value + '&namaKasir=' + item.value.userKasir?.label + '&idRuangan=' + (item.value.qRuanganK ? item.value.qRuanganK.id : '') + '&tipe=' + tipe + '&ruanganfk=' + item.value.tipekasir.id);
}
function cetakLaporanPenerimaanPerunit() {
  if(!item.value.userKasir) {
    H.alert('warning', 'Kasir tidak boleh kosong');
    return;
  }
  if(!item.value.tipekasir) {
    H.alert('warning', 'Tipe Kasir tidak boleh kosong');
    return;
  }
  if(!item.value.exporttype) {
    H.alert('warning', 'Tipe Export tidak boleh kosong');
    return;
  }

  const dari = H.formatDate(item.value.qFilterTglAwal, 'YYYY-MM-DD')
  const sampai = H.formatDate(item.value.qFilterTglAkhir, 'YYYY-MM-DD')
  let tipe = item.value.tipekasir.namaruangan;
  let ispdf = true;
  if(item.value.exporttype == 'EXCEL') {
    ispdf = false;
  }

  H.printBlade('report/kasir/laporan-penerimaan-perunit?pdf=true&tglAwal=' + dari + '&tglAkhir=' + sampai + '&idKasir=' + item.value.userKasir?.value + '&namaKasir=' + item.value.userKasir?.label + '&idRuangan=' + (item.value.qRuanganK ? item.value.qRuanganK.id : '') + '&tipe=' + tipe + '&ruanganfk=' + item.value.tipekasir.id);
}

function cetakLaporanLost() {
  if(!item.value.userKasir) {
    H.alert('warning', 'Kasir tidak boleh kosong');
    return;
  }
  if(!item.value.tipekasir) {
    H.alert('warning', 'Tipe Kasir tidak boleh kosong');
    return;
  }
  if(!item.value.exporttype) {
    H.alert('warning', 'Tipe Export tidak boleh kosong');
    return;
  }

  const dari = H.formatDate(item.value.qFilterTglAwal, 'YYYY-MM-DD')
  const sampai = H.formatDate(item.value.qFilterTglAkhir, 'YYYY-MM-DD')
  let tipe = item.value.tipekasir.namaruangan;
  let ispdf = true;
  if(item.value.exporttype == 'EXCEL') {
    ispdf = false;
  }

  H.printBlade('report/kasir/laporan-penerimaan-pasien-lost?pdf=true&tglAwal=' + dari + '&tglAkhir=' + sampai + '&idKasir=' + item.value.userKasir?.value + '&namaKasir=' + item.value.userKasir?.label + '&idRuangan=' + (item.value.qRuanganK ? item.value.qRuanganK.id : '') + '&tipe=' + tipe + '&ruanganfk' + item.value.tipekasir);
}
function cetakLaporanObatBebas() {
  if(!item.value.userKasir) {
    H.alert('warning', 'Kasir tidak boleh kosong');
    return;
  }
  if(!item.value.tipekasir) {
    H.alert('warning', 'Tipe Kasir tidak boleh kosong');
    return;
  }
  if(!item.value.exporttype) {
    H.alert('warning', 'Tipe Export tidak boleh kosong');
    return;
  }

  const dari = H.formatDate(item.value.qFilterTglAwal, 'YYYY-MM-DD')
  const sampai = H.formatDate(item.value.qFilterTglAkhir, 'YYYY-MM-DD')
  let tipe = item.value.tipekasir.namaruangan;
  let ispdf = true;
  if(item.value.exporttype == 'EXCEL') {
    ispdf = false;
  }

  H.printBlade('report/kasir/laporan-penerimaan-obat-bebas?pdf='+ispdf+'&tglAwal=' + dari + '&tglAkhir=' + sampai + '&idKasir=' + item.value.userKasir?.value + '&namaKasir=' + item.value.userKasir?.label + '&idRuangan=' + (item.value.qRuanganK ? item.value.qRuanganK.id : '') + '&tipe=' + tipe + '&ruanganfk=' + item.value.tipekasir.id);
}
function cetakLaporanPenerimaanHarian() {
  console.log("TIPE KASIR", item.value.tipekasir);
  if(!item.value.userKasir) {
    H.alert('warning', 'Kasir tidak boleh kosong');
    return;
  }
  if(!item.value.tipekasir) {
    H.alert('warning', 'Tipe Kasir tidak boleh kosong');
    return;
  }
  if(!item.value.exporttype) {
    H.alert('warning', 'Tipe Export tidak boleh kosong');
    return;
  }

  const dari = H.formatDate(item.value.qFilterTglAwal, 'YYYY-MM-DD')
  const sampai = H.formatDate(item.value.qFilterTglAkhir, 'YYYY-MM-DD')
  let tipe = item.value.tipekasir.namaruangan;
  let ispdf = true;
  if(item.value.exporttype == 'EXCEL') {
    ispdf = false;
  }
  H.printBlade('report/kasir/laporan-penerimaan-harian?pdf='+ispdf+'&tglAwal=' + dari + '&tglAkhir=' + sampai + '&idKasir=' + item.value.userKasir?.value + '&namaKasir=' + item.value.userKasir?.label + '&idRuangan=' + (item.value.qRuanganK ? item.value.qRuanganK.id : '') + '&tipe=' + tipe + '&ruanganfk=' + item.value.tipekasir.id);
}

function billing(e: any) {
  useApi().post('/general/save-jurnal-pelayananpasien_t-noreg', { 'noregistrasi': e.noRegistrasi })
  router.push({
    name: 'module-kasir-billing',
    query: {
      norec_pasien_daftar: e.norec_pd,
    },
  })
}

function getPegawai(filter: any) {
  // console.log("UserLogin", userLogin);
  useApi().get(
    `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter?.query}`
  ).then((response) => {
    d_pegawai.value = response
  })
}

function terapkanFilter() {
  fetchData()
  modalFilter.value = false
}
function clearFilter() {
  delete item.value.qKasir
  delete item.value.qRuangan
  delete item.value.qRuanganK
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
  () => item.value.tab,
  (newValue, oldValue) => {

  }
)

function autofill() {
  item.value.userKasir = {
    value: userLogin?.pegawai.id,
    label: userLogin?.pegawai.namaLengkap
  }
}

onMounted(() => {
  fetchDropdown()
  fetchData()
  getPegawai()
  autofill();
})

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
