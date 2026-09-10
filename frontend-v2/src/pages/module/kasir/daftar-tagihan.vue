<template>
  <VCard>
    <div class="columns is-multiline">
      <div class="column is-12">
        <div class="search-widget">
          <div class="field">
            <div class="columns is-multiline">
              <div class="column is-6 mt-3">
                <VField>
                  <VLabelText style="width: 100%;font-size: 1rem;">Filter
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
                  <input type="text" v-model="item.search" v-on:keyup.enter="fetchData" class="input"
                    placeholder="Cari Nama Pasien, No Registrasi, No RM, Atau NIK" />
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
      <div class="column is-12">
        <div class="list-view list-view-v1" style="max-height:550px;overflow: auto; min-height: 400px;">

          <VPlaceholderPage :class="[dataHutang.length !== 0 && 'is-hidden']" title="Data Tidak di Temukan."
            subtitle="Silakan filter pencarian di tanggal lain" larger>
            <template #image>
              <img class="light-image" src="/@src/assets/illustrations/placeholders/search-1.svg" alt="" />
              <img class="dark-image" src="/@src/assets/illustrations/placeholders/search-1-dark.svg" alt="" />
            </template>
          </VPlaceholderPage>
          <div class="list-view-inner">

            <TransitionGroup name="list-complete" tag="div">
              <div v-for="item in dataTagihanBelumLunas" :key="item.norec" class="list-view-item"
                @click="bayarTagihan(item)">
                <div class="list-view-item-inner is-clickable">
                  <VAvatar size="small" picture="/images/avatars/svg/orang.svg" squarred />
                  <div class="meta-left">
                    <h3>{{ item.namapasien }}</h3>
                    <span>
                      <span>{{ item.namaruangan }} </span>
                      <br>
                      <span>Pulang : {{ H.formatDateIndoSimple(item.tglpulang) }} </span>
                    </span>
                  </div>
                  <div class="meta-right">
                    <div class="stats">
                      <div class="stat">
                        <span>{{ item.noregistrasi }}</span>
                        <span>{{ item.kelompokpasien }}</span>
                      </div>
                      <div class="separator"></div>
                      <div class="stat">
                        <span>Rp {{ formatRp(item.totalharusdibayar, '') }}</span>
                        <span>Total Harus Bayar</span>
                      </div>
                    </div>
                  </div>
                  <div class="tags mt-auto mb-auto">
                    <VTag :label="item.statusbayar" color="warning" rounded elevated
                      v-if="item.statusbayar == 'Belum Lunas'" />
                    <VTag :label="item.statusbayar" color="success" rounded elevated
                      v-if="item.statusbayar == 'Lunas'" />
                  </div>
                  <VButton type="button" size="small" icon="lnir lnir-file-name" class="ml-3 pr-3 pl-3" color="info"
                    outlined raised rounded @click.stop="billing(item)">
                    Billing </VButton>
                </div>
              </div>
            </TransitionGroup>
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
import { ref, computed, watch, reactive } from 'vue'
import { useThemeColors } from '/@src/composable/useThemeColors'
import * as H from '/@src/utils/appHelper'
import { formatRp } from '/@src/utils/appHelper'
import { useApi } from '/@src/composable/useApi'
import { useUserSession } from '/@src/stores/userSession'
import moment from 'moment'
import { useHead } from '@vueuse/head'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import Calendar from 'primevue/calendar';
useHead({
  title: 'Dashboard Kasir - ' + import.meta.env.VITE_PROJECT,
})
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)

const modalFilter: any = ref(false)
const themeColors = useThemeColors()
const userLogin = useUserSession().getUser()
const total = ref(0)
const router = useRouter()
const modalInput = ref(false)
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
  c_antrian: 0,
  c_dilayani: 0,
  c_registrasi: 0,
  c_reservasi: 0,
})


const currentPage: any = ref({
  limit: 5,
  rows: 50,
})
let dataSource: any = ref([])
let dataSourcePulang: any = ref([])
let dataHutang: any = ref([])
let isLoading: any = ref(false)
const filters = ref('')

const dataTagihanBelumLunas = computed(() => {
  if (!item.value.qFilter) {
    return dataHutang.value
  }

  return dataHutang.value.filter((items: any) => {
    return (
      items.namapasien.match(new RegExp(item.value.qFilter, 'i')) ||
      items.noRegistrasi.match(new RegExp(item.value.qFilter, 'i'))
    )
  })
})

function billing(e: any) {
  useApi().post('/general/save-jurnal-pelayananpasien_t-noreg', { 'noregistrasi': e.noRegistrasi })
  router.push({
    name: 'module-kasir-billing',
    query: {
      norec_pasien_daftar: e.norec_pd,
    },
  })
}

async function fetchData() {

  let dari = ''
  if (item.value.qFilterTgl) {
    dari = H.formatDate(item.value.qFilterTgl.start, 'YYYY-MM-DD')
  }
  let sampai = ''
  if (item.value.qFilterTgl) {
    sampai = H.formatDate(item.value.qFilterTgl.end, 'YYYY-MM-DD')
  }
  let search = ''
  if (item.value.search) search = `&search=${item.value.search}`


  isLoading.value = true
  dataHutang.value = []
  const response = await useApi().get(
    '/dashboard/kasir/list-tagihan-pasien?'
    + '&dari=' + dari
    + '&sampai=' + sampai
    + '&search=' + search
  )
  isLoading.value = false
  dataHutang.value = response
  dataHutang.value.total = response.length
  let objSave = {
    tglAwal: dari + ' 00:00',
    tglAkhir: sampai + ' 23:59',
  }
  useApi().postNoMessage('/general/save-jurnal-verifikasi_tarek', objSave)
  useApi().postNoMessage('/akuntansi/postingjurnal-pemakaian-deposit', objSave)
}
function bayarTagihan(e: any) {
  if (e.statusbayar == 'Lunas')
    return
  router.push({
    name: 'module-kasir-pembayaran-tagihan',
    query: {
      nocmfk: e.nocmfk ?? '',
      norec_sp: e.norec,
      norec_pd: e.norec_pd,
      pageFrom: 'tagihanPasien'
    },
  })
}
fetchData()
</script>
