<template>
  <!-- <div class="form-layout is-stacked-2" style="
    width: 100%;
    max-width: none;">
        <div class="form-outer" style="margin-top:15px">
            <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
                <div class="form-header-inner">
                    <div class="left">
                        <h3> Konversi Harga</h3>
                    </div>
                    <div class="right">
                      <VButton type="button" rounded outlined color="primary" raised icon="feather:save" :loading="isLoading"
                        @click=""> Simpan
                      </VButton>
                                    
                    </div>
                </div>
            </div>

        </div>
    </div> -->
  <div class="personal-dashboard personal-dashboard-v2">
    <div class="columns is-multiline">
      <div class="column is-12" v-if="pasien.namapasien == undefined">
        <VCard>
          <PlaceloadHeader />
        </VCard>
      </div>
      <div class="column is-12 mb-0" v-else-if="pasien.namapasien != undefined">
        <img src="/images/simrs/billing-ico.png" alt="" class="mix">

        <div class="dashboard-header">
          <VAvatar picture="/images/avatars/svg/vuero-1.svg" size="xl" />
          <div class="user-meta is-dark-bordered-12">
            <h3 class="title is-4 is-narrow is-bold">{{ pasien.namapasien }}</h3>
            <p class="light-text"> <strong>{{ pasien.nocm }}</strong></p>
            <p class="light-text"> {{ pasien.nohp }}</p>
            <p class="light-text">{{ pasien.tgllahir }} ({{ pasien.umur }})</p>
          </div>

          <div class="user-action">
            <div class="cta h-hidden-tablet-p" style="background-color:var(--warning)">
              <div class="columns is-multiline mb-2">
                <div class="column is-6">
                  <VField>
                    <VLabelText>Ruangan</VLabelText>
                    <VLabel>{{ pasien.namaruangan }} </VLabel>
                  </VField>
                </div>

                <div class="column is-6">
                  <VField>
                    <VLabelText>Tipe Pembiayaan</VLabelText>
                    <VLabel>{{ pasien.kelompokpasien + ' / ' + pasien.namarekanan }} </VLabel>
                  </VField>
                </div>
              </div>
              <div class="columns is-multiline" v-if="isDetail == true">
                <div class="column is-6">
                  <VField>
                    <VLabelText>No Registrasi</VLabelText>
                    <VLabel>{{ pasien.noregistrasi }}</VLabel>
                  </VField>
                </div>
                <div class="column is-6">
                  <VField>
                    <VLabelText>Kelas</VLabelText>
                    <VLabel>{{ pasien.namakelas }} </VLabel>
                  </VField>
                </div>
                <div class="column is-6">
                  <VField>
                    <VLabelText>Tgl Masuk</VLabelText>
                    <VLabel>{{ H.formatDateIndoSimple(pasien.tglregistrasi) }} </VLabel>
                  </VField>
                </div>
                <div class="column is-6">
                  <VField>
                    <VLabelText>Tgl Keluar</VLabelText>
                    <VLabel>{{ H.formatDateIndoSimple(pasien.tglpulang) }} </VLabel>
                  </VField>
                </div>
                <div class="column is-12">
                  <VField>
                    <VLabelText>Alamat</VLabelText>
                    <VLabel>{{ pasien.alamatlengkap }} </VLabel>
                  </VField>
                </div>
              </div>
              <div class="media-flex inverted-text">
                <i aria-hidden="true" class="lnil lnil-crown-alt-1"></i>
              </div>
              <div class="collapse-icon is-clickable" @click="isDetail = true" v-if="!isDetail">
                <VIcon icon="feather:chevron-down" style="margin-top: 4px;"
                  v-tooltip.danger.bubble="'Tampilkan detail info Pasien'" />
              </div>
              <div class="collapse-icon  is-clickable " open @click="isDetail = false" v-else>
                <VIcon icon="feather:chevron-up" style="margin-top: 4px;" v-tooltip.danger.bubble="'Hide info Pasien'" />
              </div>
              <!-- <a class="link inverted-text">Learn More</a> -->
            </div>
            <!-- <h3 class="title is-2 is-narrow">3</h3>
            <p class="light-text">Tasks are pending review</p>
            <a class="action-link" tabindex="0">View Tasks</a> -->
          </div>

        </div>
      </div>
      <div class="column is-12 mt-0">
        <div class="dashboard-card has-margin-bottom">
          <div class="card-head">
            <h3 class="dark-inverted">TAGIHAN #{{ item.length }} - (Rp. {{ H.formatRp(item.TOTAL, '') }}) </h3>
          </div>
          <div class="active-projects">
            <div class="columns is-multiline">
              <div class="column is-2 mt-3">
                <VControl>
                  <VSwitchBlock v-model="isTanggal" :label="isTanggal ? 'Filter Periode' : 'Semua Periode'"
                    color="danger" />
                </VControl>
              </div>
              <div class="column is-5">
                <VField>
                  <VLabel class="item">Periode</VLabel>
                  <VDatePicker v-model="item.filterTgl" is-range color="pink" trim-weeks>
                    <template #default="{ inputValue, inputEvents }">
                      <VField addons>
                        <VControl icon="feather:calendar">
                          <VInput :value="inputValue.start" v-on="inputEvents.start" :disabled="!isTanggal" />
                        </VControl>
                        <VControl>
                          <VButton static><i class="fas fa-arrow-right" aria-hidden="true"></i></VButton>
                        </VControl>
                        <VControl icon="feather:calendar">
                          <VInput :value="inputValue.end" v-on="inputEvents.end" :disabled="!isTanggal" />
                        </VControl>
                      </VField>
                    </template>
                  </VDatePicker>
                </VField>
              </div>
              <div class="column is-1 mt-5">
                <VIconButton circle color="success" icon="feather:search" raised bold @click="fetchBill"
                  v-tooltip.bubble="'Perbaharui Data '" :loading="isLoadingBill">
                </VIconButton>
              </div>
              <div class="column is-4">
              </div>
              <div class="column is-3">
                <VField class="is-rounded-select is-autocomplete-select">
                  <VLabel class="item">Pembiayaan Tujuan</VLabel>
                  <VControl icon="fas fa-calculator" class="prime-auto-select">
                    <Dropdown v-model="item.kelompokpasien" :options="d_KelompokPasien" :optionLabel="'kelompokpasien'"
                      class="is-rounded" placeholder="Pembiayaan" style="width: 100%;" showClear :filter="true"
                      @select="changeKelompok(item.kelompokpasien)" />
                  </VControl>
                </VField>

              </div>
              <div class="column is-3" v-if="item.kelompokpasien && item.kelompokpasien.id != 1">
                <VField class="is-rounded-select is-autocomplete-select">
                  <VLabel class="item">Penjamin Tujuan</VLabel>
                  <VControl icon="feather:command" class="prime-auto-select">
                    <Dropdown v-model="item.rekanan" :options="d_Rekanan" :optionLabel="'namarekanan'" class="is-rounded"
                      placeholder="Penjamin" style="width: 100%;" showClear :filter="true" />
                  </VControl>
                </VField>
              </div>
              <div class="column is-3">
                <VField class="is-rounded-select is-autocomplete-select">
                  <VLabel class="item">Ruangan Tujuan </VLabel>
                  <VControl icon="feather:home" class="prime-auto-select">
                    <Dropdown v-model="item.ruangan" :options="d_Ruangan" :optionLabel="'namaruangan'" class="is-rounded"
                      placeholder="Ruangan" style="width: 100%;" showClear :filter="true" />
                  </VControl>
                </VField>
              </div>

              <div class="column is-1">
                <VField class="is-rounded-select is-autocomplete-select">
                  <VLabel class="item">Kelas Tujuan </VLabel>
                  <VControl icon="feather:layers" class="prime-auto-select">
                    <Dropdown v-model="item.kelas" :options="d_Kelas" :optionLabel="'namakelas'" class="is-rounded"
                      placeholder="Kelas" style="width: 100%;" showClear :filter="true" />
                  </VControl>
                </VField>
              </div>

              <div class="column is-2 mt-5">
                <VButton icon="feather:plus-circle" raised bold @click="konversiHarga()"
                  :color="PASIEN_AKTIF == true ? 'warning' : 'primary-grey'"
                  v-bind:disabled="PASIEN_AKTIF == false || pasien.noregistrasi == undefined ? true : false"
                  :loading="isLoading">
                  Konversi Harga
                </VButton>
              </div>
              
              <div class="column is-1  mt-5 ">
                <VButton icon="feather:save" raised bold @click="simpan()"
                  :color="PASIEN_AKTIF == true ? 'success' : 'primary-grey'"
                  v-bind:disabled="PASIEN_AKTIF == false || pasien.noregistrasi == undefined ? true : false"
                  :loading="isLoadingSim">
                  Simpan
                </VButton>
              </div>
              <div class="column is-1 mt-5">
                <VButton icon="lnir lnir-arrow-left rem-100" light dark-outlined @click="kembaliKeun()">
                  Kembali
                </VButton>
              </div>
              <div class="column is-12">
                <div class="column is-12">
                  <table class="tb-custom mt-3">
                    <thead>
                      <tr>

                        <th width="30%">URAIAN</th>
                        <th>HARGA SATUAN</th>
                        <th>JUMLAH</th>
                        <th>SUBTOTAL</th>
                        <th>TOTAL KONVERSI</th>
                      </tr>
                    </thead>
                    <tbody v-if="isLoadingBill">
                      <tr>
                        <td colspan="5">
                          <div class="list-view list-view-v1 is-fullwidth">
                            <div class="list-view-inner">
                              <div v-for="key in 6" :key="key" class="list-view-item mt-2">
                                <VPlaceloadWrap>
                                  <VPlaceloadAvatar size="medium" />
                                  <VPlaceloadText last-line-width="60%" class="mx-2" />
                                  <VPlaceload class="mx-2" disabled />
                                  <VPlaceload class="mx-2 h-hidden-tablet-p" />
                                  <VPlaceload class="mx-2 h-hidden-tablet-p" />
                                  <VPlaceload class="mx-2" />
                                </VPlaceloadWrap>
                              </div>
                            </div>
                          </div>
                        </td>
                      </tr>
                    </tbody>
                    <div style="max-height:500px;min-height: 300px; overflow-y: scroll;display: block;">
                      <tbody v-if="!isLoadingBill" v-for="(itemsDet, index)  in dataSource" :key="index">
                        <!-- <tr>
                          <td colspan="5" class="koneng">
                            {{ H.formatDateOnlyLong(items.tglpelayanan_group) }}
                          </td>
                        </tr> -->
                        <tr>

                          <td width="30%">
                            <div class="columns is-multiline">
                              <div class="column is-12">
                                <div class="title-ruangan">{{ itemsDet.namaruangan }}</div>
                                <div class="title-ruangan">{{ itemsDet.tglpelayanan }}</div>
                                <div class="title-layan">{{ itemsDet.namaproduk }} </div>

                                <div class="title-kelas">{{ itemsDet.namakelas }}</div>
                                <div>
                                  <VTag :color="itemsDet.strukresepfk != null ? 'danger' : 'info'"
                                    :label="itemsDet.strukresepfk != null ? 'resep' : 'layanan'" />
                                </div>
                              </div>
                            </div>
                          </td>
                          <td class="center">
                            <div class="columns is-multiline">
                              <div class="column is-12">
                                <div class="title-ruangan">Jasa : {{ H.formatRp(itemsDet.jasa, 'Rp. ') }}</div>
                                <div class="title-layan">{{ H.formatRp(itemsDet.hargasatuan, 'Rp. ') }}</div>

                                <div class="title-kelas">Diskon : {{ H.formatRp(itemsDet.hargadiscount, 'Rp. ') }}</div>
                              </div>
                            </div>
                          </td>
                          <td class="center">{{ itemsDet.jumlah }}</td>
                          <td class="center">{{ H.formatRp(itemsDet.total, 'Rp. ') }}</td>
                          <td class="center"
                            :style="itemsDet.totalkonversi ? 'background-color:var(--success)' : 'background-color:var(--danger)'">
                            <div class="columns is-multiline">
                              <div class="column is-12">
                                <div class="title-ruangan" style="color:var(--white)">Harga : {{
                                  H.formatRp(itemsDet.hargakonversi
                                    ? itemsDet.hargakonversi : 0, 'Rp. ')
                                }}</div>
                                <div class="title-layan" style="color:var(--white)">{{ H.formatRp(itemsDet.totalkonversi ?
                                  itemsDet.totalkonversi :
                                  0,
                                  'Rp. ') }}</div>
                              </div>
                            </div>
                          </td>
                        </tr>
                      </tbody>
                      <div class="search-results-wrapper" v-if="dataSource.length == 0 && isLoadingBill == false">
                        <div class="search-results-body ">
                          <div class="page-placeholder">
                            <div class="placeholder-content">
                              <img class="light-image" style=" max-width: 340px;" :src="H.assets().iconNotFound_rev"
                                alt="" />
                              <img class="dark-image" style=" max-width: 340px;" :src="H.assets().iconNotFound_rev"
                                alt="" />
                              <h3>{{ H.assets().notFound }}</h3>
                              <p class="is-larger">
                                {{ H.assets().notFoundSubtitle }}
                              </p>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </table>
                </div>

              </div>
            </div>
            <div class="content mt-0 mb-0">
              <div class="is-divider mt-3 mb-2" data-content="TOTAL"></div>
            </div>
            <div class="load-more-wrap has-text-centered p-1 mb-3">
              <div class="columns is-multiline">
                <div class="column is-6">
                  <VCardCustom :style="'padding:5px 25px'">
                    <div class="label-status primary">
                      <i aria-hidden="true" class="fas fa-circle"></i>
                      <span class="ml-1">TOTAL BILLING</span>
                    </div>
                    <small class="text-bold-custom">{{
                      H.formatRp(item.TOTAL,
                        'Rp.')
                    }}</small>

                  </VCardCustom>
                </div>
                <div class="column is-6">
                  <VCardCustom :style="'padding:5px 25px'">
                    <div class="label-status warning">
                      <i aria-hidden="true" class="fas fa-circle"></i>
                      <span class="ml-1">TOTAL KONVERSI</span>
                    </div>
                    <small class="text-bold-custom">{{
                      H.formatRp(item.TOTALKONVERSI,
                        'Rp.')
                    }}</small>

                  </VCardCustom>
                </div>



              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script setup lang="ts">
import { ref, reactive, computed, watch } from 'vue'
import { useHead } from '@vueuse/head'
import * as H from '/@src/utils/appHelper'
import { useRoute, useRouter } from 'vue-router'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useApi } from '/@src/composable/useApi'
import { useUserSession } from '/@src/stores/userSession'
import SpeedDial from 'primevue/speeddial'
import TabView from 'primevue/tabview'
import TabPanel from 'primevue/tabpanel'
import { useToaster } from '/@src/composable/toaster'
import { useConfirm } from "primevue/useconfirm";
import ConfirmDialog from 'primevue/confirmdialog';
import OverlayPanel from 'primevue/overlaypanel';
import { useWindowScroll } from '@vueuse/core'
import Dropdown from 'primevue/dropdown';
useHead({
  title: 'Konversi Harga - ' + import.meta.env.VITE_PROJECT,
})
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)
let NOREC_PD = useRoute().query.norec_pasien_daftar as string
let NOREGISTRASI = useRoute().query.noregistrasi as string
let PASIEN_AKTIF = ref(true)
if (useRoute().query.isaktif != undefined
  && useRoute().query.isaktif == 'false') {
  PASIEN_AKTIF.value = false
}
const { y } = useWindowScroll()
const isStuck = computed(() => { return y.value > 30 })
const op = ref();
const isTanggal: any = ref(false)
const PARAMETER: any = ref('')
const isLoadingPasien: any = ref(false)
const isLoadingBill: any = ref(false)
const isLoading: any = ref(false)
const isLoadingSim: any = ref(false)
const pasien: any = ref({})
const dataSource: any = ref([])
const d_Ruangan: any = ref([])

const d_KelompokPasien: any = ref([])
const d_Rekanan: any = ref([])
const d_Kelas: any = ref([])
const item: any = reactive({
  NOREC_PD: NOREC_PD != undefined ? NOREC_PD : '',
  NOREC_APD: '',
  registrasi: {},
  TOTAL: 0,
  TOTALKONVERSI: 0,
  filterTgl: reactive({
    start: new Date(),
    end: new Date(),
  }),
})

const isDetail: any = ref(false)

async function headerPasien() {
  isLoadingPasien.value = true

  item.TOTAL = 0
  await useApi().get(
    `/general/header-pasien-first?norec_pd=${NOREC_PD}`).then(async (response: any) => {
      pasien.value = response.registrasi
      isLoadingPasien.value = false
    })

  await fetchBill()
}
async function fetchBill() {
  isLoadingBill.value = true
  dataSource.value = []
  let params = ''
  if (isTanggal.value) {
    params = `&tglawal=${H.formatDate(item.value.filterTgl.start, 'YYYY-MM-DD 00:00:00')}&tglakhir=${H.formatDate(item.value.filterTgl.end, 'YYYY-MM-DD 23:59:59')}`
  }
  await useApi().get(
    `/kasir/billing/tagihan-konversi?norec_pd=${NOREC_PD}${params}`).then(async (response: any) => {
      item.TOTAL = 0
      item.TOTALKONVERSI = 0
      for (let x = 0; x < response.details.length; x++) {
        const element = response.details[x];
        // element.hargakonversi = 0
        // element.totalkonversi = 0
        item.TOTAL = element.total + item.TOTAL
      }
      dataSource.value = response.details
      d_Ruangan.value = groupRuang(response.details)
      isLoadingBill.value = false
    })
}
const groupRuang = (result: any) => {
  let sama = false
  let arrGroup: any = [];
  for (let i = 0; i < result.length; i++) {
    sama = false
    for (let x = 0; x < arrGroup.length; x++) {
      if (arrGroup[x].namaruangan == result[i].namaruangan) {
        sama = true;
      }
    }
    if (sama == false) {
      let data = {
        'id': result[i].objectruanganfk,
        'namaruangan': result[i].namaruangan,
      }
      arrGroup.push(data)
    }
  }

  return arrGroup
}
const changeKelompok = async (e: any) => {
  d_Rekanan.value = []
  delete item.rekanan
  if (e) {
    isLoadingTT.value = true
    await useApi().get(
      `/registrasi/penjamin-by-kelompokpasien?id=${e}`)
      .then((response: any) => {
        isLoadingTT.value = false
        if (response.length > 0) {
          d_Rekanan.value = response
          if (response.length == 1) {
            item.rekanan = response[0].id
          } else {
            if (item.idKelompokPasienBPJS == e) {
              for (let z = 0; z < d_Rekanan.value.length; z++) {
                const element = d_Rekanan.value[z];
                if (element.label.toLowerCase().indexOf('kesehatan') > -1) {
                  item.rekanan = element.value
                  break
                }
              }
            }
          }
        }
      })
      .catch((error: any) => { isLoadingTT.value = false })
  }

}
const dropdown = () => {
  useApi().get(
    `/kasir/billing/tagihan-konversi-dropdown`).then(async (response: any) => {
      d_KelompokPasien.value = response.kelompokpasien
      d_Kelas.value = response.kelas
    })
}
const konversiHarga = () => {
  if (!item.kelompokpasien) {
    H.alert('error', 'Pembiayaan Tujuan  harus di isi')
    return
  }
  if (!item.ruangan) {
    H.alert('error', 'Ruangan tujuan harus di isi')
    return
  }
  if (!item.kelas) {
    H.alert('error', 'Kelas tujuan harus di isi')
    return
  }
  let params = ''
  if (isTanggal.value) {
    params = `&tglawal=${H.formatDate(item.value.filterTgl.start, 'YYYY-MM-DD 00:00:00')}&tglakhir=${H.formatDate(item.value.filterTgl.end, 'YYYY-MM-DD 23:59:59')}`
  }
  let jsonSave = {
    'norec_pd': pasien.value.norec_pd,
    'noregistrasi': pasien.value.noregistrasi,
    'idKelas': item.kelas.id,
    'idKelPasien': item.kelompokpasien.id,
    'idRuangan': item.ruangan.id,
    'idPenjamin': item.rekanan == undefined ? null : item.rekanan.id,
    'tglawal': isTanggal.value ? H.formatDate(item.value.filterTgl.start, 'YYYY-MM-DD 00:00:00') : null,
    'tglakhir': isTanggal.value ? H.formatDate(item.value.filterTgl.end, 'YYYY-MM-DD 23:59:59') : null,
    'data': dataSource.value
  }
  isLoading.value = true;
  useApi().post("kasir/billing/update-harga-konversi", jsonSave).then(async (response: any) => {
    isLoading.value = false;
    item.TOTAL = 0
    item.TOTALKONVERSI = 0
    for (let x = 0; x < response.details.length; x++) {
      const element = response.details[x];
      item.TOTAL = element.total + item.TOTAL
      item.TOTALKONVERSI = element.totalkonversi + item.TOTALKONVERSI
    }
    dataSource.value = response.details
  }).catch(e => {
    isLoading.value = false;
  })
}
const simpan = async () => {
  if (dataSource.length == 0) {
    H.alert('error', 'Tidak ada obat yang akan dikonversi')
    return
  }
  if (!item.kelompokpasien) {
    H.alert('error', 'Pembiayaan Tujuan  harus di isi')
    return
  }
  if (!item.ruangan) {
    H.alert('error', 'Ruangan tujuan harus di isi')
    return
  }
  if (!item.kelas) {
    H.alert('error', 'Kelas tujuan harus di isi')
    return
  }
  let jsonSave = {
    'norec_pd': pasien.value.norec_pd,
    'noregistrasi': pasien.value.noregistrasi,
    'idKelas': item.kelas.id,
    'idKelPasien': item.kelompokpasien.id,
    'idPenjamin': item.rekanan == undefined ? null : item.rekanan.id,
    'tglawal':isTanggal.value ? H.formatDate(item.value.filterTgl.start, 'YYYY-MM-DD 00:00:00') : null,
    'tglakhir':  isTanggal.value ? H.formatDate(item.value.filterTgl.end, 'YYYY-MM-DD 23:59:59') : null,
    'data': dataSource.value
  }
  isLoadingSim.value = true;
  useApi().post("kasir/billing/simpan-harga-konversi", jsonSave).then(async (response: any) => {
    isLoadingSim.value = false;
    fetchBill()
  }).catch(e => {
    isLoading.value = false;
  })
}
watch(
  () => isTanggal.value,
  (newValue, oldValue) => {
    if (isTanggal.value) {

    }
  }
)
headerPasien()
dropdown()
</script>
<style lang="scss">
@import '/@src/scss/main';
@import '/@src/scss/module/kasir/billing';</style>
