
<template>
  <ConfirmDialog group="positionDialog"></ConfirmDialog>
  <VCard>
    <div class="columns is-multiline">
      <div class="column is-12">
        <div class="search-widget">
          <div class="field">
            <div class="columns is-multiline">
              <div class="column mt-3">
                <VField>
                  <VLabelText style="width: 100%;font-size: 1rem;">Tagihan Non Layanan
                  </VLabelText>
                </VField>
              </div>

              <div class="column is-6">
                <VDatePicker v-model="item.qFilterTgl" is-range color="pink" trim-weeks>
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
            </div>
          </div>
          <div class="field">
            <div class="control">
              <div class="columns is-multiline">
                <div class="column is-11 ">
                  <input type="text"  v-model="item.search" v-on:keyup.enter="fetchData" class="input" placeholder="Cari Nama Pasien, No Registrasi, No RM, Atau NIK" />
                </div>
                <div class="column is-1 ">
                  <VIconButton type="button" color="success" class="searcv-button" raised icon="fas fa-search"
                    @click="fetchData()" :loading="isLoading">
                  </VIconButton>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="column is-12 is-multiline">
        <VButton color="info" icon="feather:plus" raised outlined @click="tambahNonLayanan(item)"
          class="btn-slim mr-1 is-pulled-right">
          Tambah
        </VButton>
      </div>
      <div class="column is-12">
        <div class="list-view list-view-v1" style="max-height:550px;overflow: auto; min-height: 400px;">

          <VPlaceholderPage :class="[dataSource.length !== 0 && 'is-hidden']" title="Data Tidak di Temukan."
            subtitle="Silakan filter pencarian di tanggal lain" larger>
            <template #image>
              <img class="light-image" src="/@src/assets/illustrations/placeholders/search-1.svg" alt="" />
              <img class="dark-image" src="/@src/assets/illustrations/placeholders/search-1-dark.svg" alt="" />
            </template>
          </VPlaceholderPage>
          <div class="list-view-inner">

            <TransitionGroup name="list-complete" tag="div">
              <div v-for="item in dataSourceFiltered" :key="item.norec" class="list-view-item">
                <div class="list-view-item-inner is-clickable">

                  <VAvatar size="small" picture="/images/avatars/svg/orang.svg" squarred />
                  <div class="meta-left">
                    <h3>{{ item.namapasien_klien }}</h3>
                    <span>
                      <span>No Telp : {{ item.noteleponfaks }} </span>
                      <br>
                      <span>No Struk : {{ item.nostruk }} </span>
                      <br>
                      <span>Tgl : {{ H.formatDateIndoSimple(item.tglstruk) }} </span>
                    </span>

                  </div>
                  <div class="meta-right">
                    <div class="stats">
                      <div class="stat">
                        <span>{{ item.kelompokpasien }}</span>
                        <span>{{ item.jenistagihan }}</span>
                      </div>
                      <div class="separator"></div>
                      <div class="stat">
                        <span>Rp {{ formatRp(item.totalharusdibayar, '') }}</span>
                        <span>Total Harus Bayar</span>
                      </div>
                    </div>
                  </div>
                  <div class="tags">
                    <VTag style="width: 80px;" :label="item.statusbayar" color="warning" rounded elevated
                      v-if="item.statusbayar == 'Belum Bayar'" />
                    <VTag style="width: 80px;" :label="item.statusbayar" color="success" rounded elevated
                      v-if="item.statusbayar == 'Lunas'" />
                  </div>
                  <VDropdown icon="feather:more-vertical" spaced right class="is-pulled-right mb-5">
                    <template #content>
                      
                      <a role="menuitem" @click="bayarTagihan(item)" class="dropdown-item is-media"
                        v-if="item.statusbayar == 'Belum Bayar'">
                        <div class="icon">
                          <i aria-hidden="true" class="lnir lnir-checkmark-circle"></i>
                        </div>
                        <div class="meta">
                          <span>Bayar Tagihan</span>
                        </div>
                      </a>
                      <a role="menuitem" @click="openBill(item)" class="dropdown-item is-media"
                        v-if="item.statusbayar == 'Lunas'">
                        <div class="icon">
                          <i aria-hidden="true" class="fa fa-history"></i>
                        </div>
                        <div class="meta">
                          <span>Open Bill</span>
                        </div>
                      </a>
                      <a role="menuitem" @click="cetakKwitansi(item)" class="dropdown-item is-media"
                        v-if="item.statusbayar == 'Lunas'">
                        <div class="icon">
                          <i aria-hidden="true" class="fas fa-print"></i>
                        </div>
                        <div class="meta">
                          <span>Cetak Kwitansi</span>
                        </div>
                      </a>
                      <a role="menuitem" @click="detail(item)" class="dropdown-item is-media">
                        <div class="icon">
                          <i class="lnir lnir-thumbnail" aria-hidden="true"></i>
                        </div>
                        <div class="meta">
                          <span>Detail</span>
                        </div>
                      </a>
                      <a role="menuitem" @click="ubah(item)" class="dropdown-item is-media">
                        <div class="icon">
                          <i aria-hidden="true" class="lnir lnir-pencil"></i>
                        </div>
                        <div class="meta">
                          <span>Ubah</span>
                        </div>
                      </a>
                      <hr class="dropdown-divider" />
                      <a role="menuitem" @click="hapus(item)" class="dropdown-item is-media">
                        <div class="icon">
                          <i aria-hidden="true" class="lnil lnil-trash"></i>
                        </div>
                        <div class="meta">
                          <span>Hapus</span>
                        </div>
                      </a>
                    </template>
                  </VDropdown>
                </div>

              </div>
            </TransitionGroup>

          </div>
        </div>
        <div class="columns is-multiline">
          <div class="column">
            <VCardCustom :style="'padding:5px 25px'">
              <div class="label-status danger">
                <i aria-hidden="true" class="fas fa-circle"></i>
                <span class="ml-1" style="font-size: 11px;">Total Tagihan</span>
              </div>
              <VPlaceload  v-if="isLoadNominal" class="mx-2 mt-3" />
              <small v-else class="text-bold-custom h-100" style="font-size: 1.3rem;">{{
                H.formatRp(item.totalTagihan, 'Rp.')
              }}</small>
            </VCardCustom>
          </div>
          <div class="column">
            <VCardCustom :style="'padding:5px 25px'">
              <div class="label-status info">
                <i aria-hidden="true" class="fas fa-circle"></i>
                <span class="ml-1" style="font-size: 11px;">Total Bayar</span>
              </div>
              <VPlaceload  v-if="isLoadNominal" class="mx-2 mt-3" />
              <small v-else class="text-bold-custom h-100" style="font-size: 1.3rem;">{{
                H.formatRp(item.totalDibayar, 'Rp.')
              }}</small>
            </VCardCustom>
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
import { useRoute, useRouter } from 'vue-router'
import { ref, computed, watch, reactive, onMounted } from 'vue'
import { useThemeColors } from '/@src/composable/useThemeColors'
import * as H from '/@src/utils/appHelper'
import { formatRp } from '/@src/utils/appHelper'
import { useApi } from '/@src/composable/useApi'
import { useUserSession } from '/@src/stores/userSession'
import { useHead } from '@vueuse/head'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useConfirm } from "primevue/useconfirm";
import ConfirmDialog from 'primevue/confirmdialog';
useHead({
  title: 'Dashboard Kasir - ' + import.meta.env.VITE_PROJECT,
})
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)

const confirm = useConfirm();
const router = useRouter()
const dataSource: any = ref([])
const isLoading: any = ref(false)
const isLoadNominal: any = ref(false)
const item: any = ref({
  qAktif: true,
  filterDate: new Date(),
  qFilterTgl: {
    start: new Date(),
    end: new Date()
  },
  qPeriode: [
    new Date(),
    new Date()
  ],
  totalPending: 0,
  totalSelesai: 0,
  totalTagihan: 0,
  totalDibayar: 0,
  c_antrian: 0,
  c_dilayani: 0,
  c_registrasi: 0,
  c_reservasi: 0,
})

const currentPage: any = ref({
  limit: 5,
  rows: 50,
})

const dataSourceFiltered = computed(() => {
  if (!item.value.qFilter) {
    return dataSource.value
  }

  return dataSource.value.filter((items: any) => {
    return (
      items.namapasien_klien.match(new RegExp(item.value.qFilter, 'i'))
    )
  })
})

const fetchData = async () => {
  // let dari = ''
  // if (item.value.qFilterTgl) {
  //   dari = H.formatDate(item.value.qFilterTgl.start, 'YYYY-MM-DD')
  // }
  // let sampai = ''
  // if (item.value.qFilterTgl) {
  //   sampai = H.formatDate(item.value.qFilterTgl.end, 'YYYY-MM-DD')
  // }
  let search = ''
  if (item.value.search) search = `&search=${item.value.search}`
  H.cacheHelper().set('filterNonLayan', item.value);
  
  console.log("FILTER NON LAYAN SET", H.cacheHelper().get('filterNonLayan'));
  isLoading.value = true
  isLoadNominal.value = true
  dataSource.value = []
  await useApi().get(
    '/kasir/daftar-tagihan-non-layanan?'
    + '&dari=' + H.formatDate(item.value.qFilterTgl.start, 'YYYY-MM-DD')
    + '&sampai=' + H.formatDate(item.value.qFilterTgl.end, 'YYYY-MM-DD')
    + '&search=' + search

  ).then((response) => {
    isLoading.value = false
    dataSource.value = response.data
    dataSource.value.total = response.total
  })
  fetchNominal()
}
const bayarTagihan = (e: any) => {
  if (e.statusbayar == 'Lunas') {
    H.alert('error', 'SUDAH BAYAR')
    return
  }

  router.push({
    name: 'module-kasir-pembayaran-tagihan',
    query: {
      norec_sp: e.norec,
      nocmfk: e.nocmfk ?? '',
      pageFrom: 'tagihanNonLayanan'
    },
  })
}
const tambahNonLayanan = () => {
  router.push({
    name: 'module-kasir-tagihan-non-layanan',
  })
}
const hapus = (e: any) => {
  if (e.statusbayar == 'Lunas') {
    H.alert('error', 'SUDAH BAYAR')
    return
  }
  confirm.require({
    group: 'positionDialog',
    message: H.alertHapus(),
    header: 'Info ',
    icon: 'pi pi-info-circle',
    acceptClass: 'p-button-danger',
    position: 'top',
    accept: () => {
      isLoading.value = true
      useApi().post(
        `/kasir/daftar-tagihan-non-layanan/hapus`, {
        norec: e.norec
      }).then((response: any) => {
        isLoading.value = false
        fetchData()
      }).catch((e: any) => {
        isLoading.value = false
      })
    },
    reject: () => {
    }
  });

}
const ubah = (e: any) => {
  if (e.statusbayar == 'Lunas') {
    H.alert('error', 'SUDAH BAYAR')
    return
  }
  router.push({
    name: 'module-kasir-tagihan-non-layanan',
    query: {
      norec_sp: e.norec,
    },
  })
}
const detail = (e: any) => {
  router.push({
    name: 'module-kasir-tagihan-non-layanan',
    query: {
      norec_sp: e.norec,
      detail: true,
    },
  })
}

const cetakTagihan = (e: any) => {
  H.printBlade(`report/bukti-layanan-bpjs-nonlayanan?norec_sp=${e.norec}`);
  // await qzService.printData(`report/bukti-layanan-bpjs-nonlayanan?norec_sp=${NOREC_SP}&user=${userLogin.pegawai.namaLengkap}`, 'KWITANSI RAJAL', 1)
}

const cetakKwitansi = (e: any) => {
  H.printBlade(`report/bukti-layanan-carabayar-nonlayanan?noregistrasi=${e.noregistrasi}&norec_sp=${e.norec}`)
}

const fetchNominal = async () => {

  let dari = `?dari=${H.formatDate(item.value.qFilterTgl.start, 'YYYY-MM-DD')}`
  let sampai = `&sampai=${H.formatDate(item.value.qFilterTgl.end, 'YYYY-MM-DD')}`

  await useApi().get(`kasir/jumlah-nominal${dari}${sampai}`).then((response) => {
    item.value.totalDibayar = response.dibayar.totaldibayar
    item.value.totalTagihan = response.tagihan.totaltagihan
    isLoadNominal.value = false
  });

}

const openBill = async (e: any) => {
  var return_value= prompt("Sandi :");
  if( return_value === "rsbm") {
    console.log(e)
    if(e.nosbmlastfk != null){
      await batalBayar(e)
    }
  }else {
    alert("Kata sandi salah")
    return;
  }
}

async function batalBayar(e: any) {
  var objSave = {
    norec_sbmcr: e.norec_sbmcr,
    norec: e.nosbmlastfk,
    norec_sp: e.norec,
    namapasien: e.namapasien,
    nocm: e.nocm,
  }
  isLoading.value = true
  useApi().post(
    `/kasir/daftar-penerimaan/batal-bayar`, objSave).then((response: any) => {
      isLoading.value = false
      fetchData();
    }).catch((e: any) => {
      isLoading.value = false
    })
}

onMounted(() => {
  console.log("FILTER NON LAYAN", H.cacheHelper().get('filterNonLayan'));
  if(H.cacheHelper().get('filterNonLayan')) {
    item.value = H.cacheHelper().get('filterNonLayan');
  }
  fetchData()
})
</script>
