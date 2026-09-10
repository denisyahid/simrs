<template>
  <div class="columns is-multiline">
    <div class="column is-12">
      <TabView v-model:activeIndex="activeIdxSurkon">
        <TabPanel header="List Rencana Kontrol/RI">
          <div class="columns is-multiline">
            <div class="column is-3">
              <VField class="is-rounded-select is-autocomplete-select
                      mt-0 pt-0" v-slot="{ id }">
                <VLabel>Filter</VLabel>
                <VControl icon="feather:bookmark" fullwidth class="prime-auto-select">
                  <Dropdown v-model="input.filter" :options="d_Filter" :optionLabel="'nama'" class="is-rounded"
                    placeholder="Filter" style="width: 100%;" :filter="true" showClear />
                </VControl>
              </VField>
            </div>
            <div class="column is-2">
              <VField class="is-rounded-select is-autocomplete-select
                      mt-0 pt-0" v-slot="{ id }">
                <VLabel>Tahun</VLabel>
                <VControl icon="feather:bookmark" fullwidth class="prime-auto-select">
                  <Dropdown v-model="input.tahun" :options="d_Tahun" :optionLabel="'nama'" class="is-rounded"
                    placeholder="Tahun" style="width: 100%;" :filter="true" showClear />
                </VControl>
              </VField>
            </div>
            <div class="column is-3">
              <VField class="is-rounded-select is-autocomplete-select
                      mt-0 pt-0" v-slot="{ id }">
                <VLabel>Bulan</VLabel>
                <VControl icon="feather:bookmark" fullwidth class="prime-auto-select">
                  <Dropdown v-model="input.bulan" :options="d_Bulan" :optionLabel="'nama'" class="is-rounded"
                    placeholder="Bulan" style="width: 100%;" :filter="true" showClear />
                </VControl>
              </VField>
            </div>
            <div class="column is-3">
              <VField>
                <VLabel class="required-field">No. Kartu</VLabel>
                <VControl icon="feather:user">
                  <VInput type="text" v-model="item.noKartu" placeholder="No. Kartu" class="is-rounded" />
                </VControl>
              </VField>
            </div>
            <div class="column is-1">
              <VIconButton icon="feather:search" @click="cariSKDP" color="success" raised :loading="isLoadingSKDP" circle
                class="mt-5">
              </VIconButton>
            </div>
          </div>
          <div class="columns is-multiline" v-if="isLoadingSKDP">
            <div v-for="key in 2" :key="key" class="column is-12">
              <div class="columns is-multiline">
                <div class="column is-2">
                  <VPlaceloadText :lines="1" style="margin-top:2.5rem" />
                </div>
                <div class="column is-10">
                  <VCard>
                    <div class="tile-grid-item-inner placeload-wrap is-flex">
                      <VPlaceloadAvatar size="medium" />
                      <VPlaceloadText width="90%" last-line-width="60%" class="mx-2" :lines="4" />
                    </div>
                  </VCard>
                </div>
              </div>
            </div>
          </div>
          <div class="" v-else>
            <div class="update-item is-dark-bordered-12" style="display: block;" v-if="listSurkon.length == 0">
              <VPlaceholderPage :title="H.assets().notFound" larger>
                <template #image>
                  <img class="light-image" :src="H.assets().iconNotFound_rev" alt="" />
                  <img class="dark-image" src="/@src/assets/illustrations/placeholders/search-4-dark.svg" alt="" />
                </template>
              </VPlaceholderPage>
            </div>
            <div class="list-widget" v-if="listSurkon.length > 0">

              <div class="timeline-wrapper" v-if="listSurkon.length > 0">
                <div class="timeline-header"></div>
                <div class="timeline-wrapper-inner pt-0">
                  <div class="timeline-container">
                    <div class="timeline-item is-unread is-clickable" v-for="(item, index)  in listSurkon"
                      :key="item.noSuratKontrol">
                      <div class="date">
                        <span>{{ item.tglRencanaKontrol }}</span>
                      </div>
                      <div :class="'dot is-' + item.color"></div>
                      <div class="content-wrap is-grey">
                        <div class="content-box ">
                          <div class="status"></div>
                          <VIconBox size="small" :color="'warning'" rounded>
                            <i class="fas fa-notes-medical" aria-hidden="true"></i>
                          </VIconBox>
                          <div class="box-text" style="width:70%">
                            <div class="meta-text">
                              <table class="tb-order">
                                <tr>
                                  <td><span class="mt-5">No Surat</span></td>
                                  <td>:</td>
                                  <td class="text-value">
                                    <VButton rounded style="margin-top:-10px" raised circle
                                      v-tooltip.bubble="'Pilih Surat'" @click="setSurat(item)">
                                      {{ item.noSuratKontrol }}
                                    </VButton>
                                  </td>
                                </tr>
                                <tr>
                                  <td colspan="3">
                                    <div class="mt-3"></div>
                                  </td>
                                </tr>
                                <tr class="mt-2">
                                  <td>Kontrol/Inap </td>
                                  <td>:</td>
                                  <td class="text-value">{{ item.namaJnsKontrol }} </td>
                                </tr>
                                <tr class="mt-2">
                                  <td>Tgl Entri </td>
                                  <td>:</td>
                                  <td class="text-value">{{ item.tglTerbitKontrol }} </td>
                                </tr>
                                <tr>
                                  <td>No SEP Asal</td>
                                  <td>:</td>
                                  <td class="text-value">{{ item.noSepAsalKontrol }} </td>
                                </tr>
                                <tr>
                                  <td>Poli Asal</td>
                                  <td>:</td>
                                  <td class="text-value">{{ item.namaPoliAsal }} </td>
                                </tr>
                                <tr>
                                  <td>Poli Tuju</td>
                                  <td>:</td>
                                  <td class="text-value">{{ item.namaPoliTujuan }} </td>
                                </tr>
                                <tr>
                                  <td>DPJP</td>
                                  <td>:</td>
                                  <td class="text-value">{{ item.namaDokter }} </td>
                                </tr>
                                <tr>
                                  <td>No Kartu</td>
                                  <td>:</td>
                                  <td class="text-value">{{ item.noKartu }} </td>
                                </tr>
                                <tr>
                                  <td>Nama Lengkap</td>
                                  <td>:</td>
                                  <td class="text-value">{{ item.nama }} </td>
                                </tr>

                              </table>
                            </div>
                          </div>
                          <div class="box-end" style="width:30%">
                            <div class="columns is-multiline">
                              <div class="column is-12 mt-3">
                                <div class="status is-pulled-right mt-2 ml-2"></div>
                                <VTag :label="'Terbit SEP : ' + item.terbitSEP"
                                  :color="item.terbitSEP == 'Sudah' ? 'danger' : 'success'" rounded
                                  class="is-pulled-right" />
                              </div>
                              <div class="column is-12 ">
                                <VIconButton icon="feather:file-text" @click="setSurat(item)" raised circle
                                  class="mr-2 is-pulled-right" v-tooltip.bubble="'Pilih Surat'">
                                </VIconButton>
                                <VIconButton icon="feather:printer" @click="cetakSurkon(item)" raised color="info" circle
                                  class="mr-2 is-pulled-right" v-tooltip.bubble="'Cetak '">
                                </VIconButton>
                                <VIconButton icon="feather:edit" @click="editSurkon(item)" raised color="warning" circle
                                  class="mr-2 is-pulled-right" v-tooltip.bubble="'Edit '"
                                  v-if="item.terbitSEP != 'Sudah'">
                                </VIconButton>
                                <VIconButton icon="feather:trash" @click="hapusSurkon(item)" :loading="item.loadHapus"
                                  raised color="danger" circle class="mr-2 is-pulled-right"
                                  v-if="item.terbitSEP != 'Sudah'" v-tooltip.bubble="'Hapus'">
                                </VIconButton>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </TabPanel>
        <TabPanel header="Buat Baru">
          <div class="columns is-multiline">
            <div class="column is-6">
              <VControl>
                <VLabel class="required-field">Pilih</VLabel>
                <div class="columns is-multiline">
                  <div class="column is-6">
                    <VRadio v-model="input.jenis" :value="2" :label="'Rencana Kontrol'" name="jenisKntrl" square
                      color="primary" />
                  </div>
                  <div class="column is-6">
                    <VRadio v-model="input.jenis" :value="1" :label="'Rencana Rawat Inap'" name="jenisKntrl" square
                      color="primary" />
                  </div>
                </div>
              </VControl>
            </div>

            <div class="column is-3" v-if="input.jenis == 1">
              <VField>
                <VLabel class="required-field">No. Kartu</VLabel>
                <VControl icon="feather:user">
                  <VInput type="text" v-model="input.noKartu" placeholder="No. Kartu" class="is-rounded_Z" />
                </VControl>
              </VField>
            </div>
            <div class="column is-12" v-if="input.jenis == 2">
              <div class="s-card mt-5-min " style=" border-top: 3px solid var(--danger);">
                <h3 class="title is-5 mt-2-min mb-1" style="font-size:1rem">
                  <span>History Pelayanan</span>
                </h3>
                <HistoryPelayananBPJS :dataSourceHistory="dataSourceHistory" :loadingMon="loadingMon"
                  @pilihSEP="pilihSEPHistory" @cariMon="cariMon">
                </HistoryPelayananBPJS>
              </div>
            </div>
            <!-- <div class="column is-2">
                    <VIconButton icon="feather:search" @click="setJenisPel" color="success" raised :loading="isLoadingPasien2"
                      circle class="mt-5">
                    </VIconButton>
                  </div> -->
            <div class="column is-12">
              <div class="columns is-multiline">

                <div class="column is-12">
                  <div class="s-card mt-0 p-6" style=" border-top: 3px solid var(--orange);">
                    <h3 class="title is-5 head-sep ml-1">
                      <span v-if="noSuratKontrol"> {{ noSuratKontrol }}</span>
                    </h3>

                    <VField horizontal label="No. SEP" required v-if="input.jenis == 2">
                      <VControl icon="fas fa-ambulance" fullwidth>
                        <VInput type="text" placeholder="No. SEP" autocomplete="off" v-model="input.noSEP" />
                      </VControl>
                    </VField>
                    <VField horizontal label="Tgl. Rencana Kontrol / Inap">
                      <Calendar v-model="input.tglRencanaKontrol" selectionMode="single" :manualInput="true"
                        style="width: 50%;" :showIcon="true" :showTime="false" hourFormat="24"
                        :date-format="'yy-mm-dd'" />
                      <!-- <VDatePicker v-model="input.tglRencanaKontrol" style="width: 50%;" trim-weeks mode="date">
                              <template #default="{ inputValue, inputEvents }">
                                <VField>
                                  <VControl icon="feather:calendar" fullwidth>
                                    <VInput :value="inputValue" v-on="inputEvents" />
                                  </VControl>
                                </VField>
                              </template>
                            </VDatePicker> -->
                    </VField>

                    <!-- <VField horizontal label="Pelayanan" required>
                            <VControl icon="fas fa-ambulance" fullwidth>
                              <VInput type="text" placeholder="Pelayanan" autocomplete="off" v-model="input.pelayanan"
                                disabled />
                            </VControl>
                          </VField> -->
                    <VField horizontal label="No. Surat Kontrol" required>
                      <VControl icon="lnir lnir-hospital-alt-2" fullwidth>
                        <VInput type="text" placeholder="No. Surat Kontrol" autocomplete="off"
                          v-model="input.noSuratKontrol" disabled />
                      </VControl>
                    </VField>
                    <VField horizontal label="Sub/Spesialis" required class="is-rounded-select_Z  is-autocomplete-select"
                      v-slot="{ id }">
                      <VControl icon="feather:home" fullwidth class="prime-auto ">
                        <AutoComplete v-model="input.poliKontrol" :suggestions="d_Subspesialis_Sur"
                          @complete="fetchSupspesialisSur($event)" :optionLabel="'nama'" :dropdown="true" :minLength="3"
                          :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'nama'"
                          placeholder="ketik spesialis/subspesialis" @item-select="changeSpe(input.poliKontrol)" />
                        <!-- <Dropdown v-model="input.poliKontrol" :options="d_Subspesialis" :optionLabel="'namaPoli'"
                                placeholder="Sub/Spesialis" style="width: 100%;" :filter="true" @change="changeSpe(input.poliKontrol)" /> -->
                      </VControl>
                    </VField>
                    <VField horizontal label="DPJP Tujuan Kontrol / Inap" required
                      class="is-rounded-select_Z  is-autocomplete-select" v-slot="{ id }">
                      <VControl icon="feather:users" fullwidth class="prime-auto">
                        <Dropdown v-model="input.kodeDokter" :options="d_dpjpLayan_Sur" :optionLabel="'namaDokter'"
                          placeholder="DPJP Tujuan Kontrol / Inap" style="width: 100%;" :filter="true" />
                      </VControl>
                    </VField>
                  </div>
                  <div class="mt-2 is-pulled-right">
                    <VButton icon="lnir lnir-arrow-left rem-100" light dark-outlined @click="batalSurkon()">
                      Batal
                    </VButton>

                    <VButton type="button" class="ml-2" rounded outlined raised icon="feather:file-text"
                      v-if="noSuratKontrol"
                      @click="setSurat({ noSuratKontrol: noSuratKontrol, namaDokter: input.kodeDokter.namaDokter })">
                      Pilih
                      Surkon </VButton>
                    <VButton type="button" color="primary" class="ml-2" rounded outlined raised icon="feather:save"
                      :loading="isLoadingSur" @click="saveSurkon()"> {{ input.noSuratKontrol ? 'Edit' : 'Simpan' }}
                    </VButton>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </TabPanel>
      </TabView>
    </div>
  </div>
</template>
<script setup lang="ts">
import { useWindowScroll } from '@vueuse/core'
import { useApi } from '/@src/composable/useApi'
import { h, reactive, ref, computed, defineComponent, watch, onMounted, watchEffect, inject, provide } from 'vue'
import { useRoute, useRouter, onBeforeRouteUpdate, onBeforeRouteLeave, RouteLocationNormalized, Router, RouterView, } from 'vue-router'
import { useHead } from '@vueuse/head'
import * as H from '/@src/utils/appHelper'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import TabMenu from 'primevue/tabmenu';
import HistoryPelayananBPJS from './history-pelayanan-bpjs.vue'
import sleep from '/@src/utils/sleep'
import AutoComplete from 'primevue/autocomplete';
import TabView from 'primevue/tabview'
import TabPanel from 'primevue/tabpanel'
import Dialog from 'primevue/dialog';
import Checkbox from 'primevue/checkbox';
import Dropdown from 'primevue/dropdown';
import Calendar from 'primevue/calendar';
const props = withDefaults(
  defineProps<{
    items?: any[],
    isSave?: boolean
  }>(),
  {
    items: () => [],
    isSave: false
  }
)

const emit = defineEmits<{
  (e: 'savePulang'): void,
}>()

const d_Bulan: any = ref(H.monthList())
const isSaveRef = ref(props.isSave);
const d_dpjpLayan_Sur: any = ref([])
const pasien: any = ref(props.items)
const bulanAyena = d_Bulan.value[new Date().getMonth()]
const d_Subspesialis_Sur: any = ref([])
const noSuratKontrol = ref()
const activeIdxSurkon: any = ref(0)
const d_Filter: any = ref([{ kode: 2, nama: 'Tgl Rencana Kontrol' }, { kode: 1, nama: 'Tgl Entri' }])
const d_Tahun: any = ref(H.yearList())
const isLoadingSKDP: any = ref(false)
const isLoadingSur: any = ref(false)
const listSurkon: any = ref([])
const setSurat = async (e: any) => {
  item.noSurat = e.noSuratKontrol
  item.namaDPJP = e.namaDokter,
    modalSKDP.value = false
}
const modalSKDP: any = ref(false)
const item: any = reactive({
  tglSep: new Date(),
  noKartu: pasien.value.nobpjs
})
const input: any = ref({
  tanggal: new Date(),
  noSEP: pasien.value.nosep
})

const editSurkon = async (e: any) => {
  if (e.terbitSEP == 'Sudah') {
    H.alert('error', 'SEP sudah terbit tidak bisa di edit');
    return
  }

  activeIdxSurkon.value = 1
  await sleep(2000)
  if (e.namaJnsKontrol == 'SPRI') {
    input.value.jenis = 1
    input.value.noKartu = e.noKartu
  } else {
    input.value.jenis = 2
    input.value.noSEP = e.noSepAsalKontrol
    input.value.noKartu = e.noKartu
  }


  input.value.noSuratKontrol = e.noSuratKontrol
  noSuratKontrol.value = e.noSuratKontrol
  input.value.tglRencanaKontrol = new Date(e.tglRencanaKontrol)

  await fetchSupspesialisSur({ query: e.namaPoliTujuan })
  for (let x = 0; x < d_Subspesialis_Sur.value.length; x++) {
    const element = d_Subspesialis_Sur.value[x];
    if (element.nama == e.namaPoliTujuan) {
      input.value.poliKontrol = element
      await changeSpe(element)
      break
    }
  }
  d_dpjpLayan_Sur.value.forEach((element: any) => {
    if (element.kodeDokter == e.kodeDokter) {
      input.value.kodeDokter = element
    }
  });

  setJenisPel()
}
const fetchSupspesialisSur = async (filter: any) => {
  if (!filter.query) return
  if (filter.query.length < 3) return
  let json = {
    "url": "referensi/poli/" + encodeURI(filter.query),
    "method": "GET",
    "data": null
  }
  const res = await useApi().postBPJS(
    `/bridging/bpjs/tools`, json)
  if (res.metaData.code == 200) {
    d_Subspesialis_Sur.value = res.response.poli

  } else {
    H.alert('error', res.metaData.message)
  }
}

const changeSpe = async (e: any) => {
  let json = {
    "url": `/RencanaKontrol/JadwalPraktekDokter/JnsKontrol/${input.value.jenis}/KdPoli/${e.kode}/TglRencanaKontrol/${H.formatDate(input.value.tglRencanaKontrol, 'YYYY-MM-DD')}`,
    "method": "GET",
    "data": null
  }
  await useApi().postBPJS('/bridging/bpjs/tools', json).then((x) => {
    if (x.metaData.code == 200) {
      d_dpjpLayan_Sur.value = x.response.list
    } else {
      H.alert('error', x.metaData.message)
    }
  })
}
const setJenisPel = () => {
  if (input.value.jenis == 1) {
    input.value.pelayanan = 'Rawat Inap'
  } else {
    input.value.pelayanan = 'Rawat Jalan'
  }
}
const cariSKDP = async () => {
  listSurkon.value = []
  isLoadingSKDP.value = true
  modalSKDP.value = true
  const res: any = await apiSuratKontrol()
  isLoadingSKDP.value = false

  if (res.metaData.code == 200) {
    listSurkon.value = res.response.list
  } else {
    H.alert('error', res.metaData.message)
  }
}
const cetakSurkon = async (e: any) => {

  let json = {
    "url": `Peserta/nokartu/${e.noKartu}/tglSEP/${H.formatDate(new Date(), 'YYYY-MM-DD')}`,
    "method": "GET",
    "data": null
  }
  e.isLoading = true
  let response = await useApi().postBPJS('/bridging/bpjs/tools', json)
  e.isLoading = false
  let nosuratkontrol = e.noSuratKontrol
  let tglrencanakontrol = e.tglRencanaKontrol
  let txttglentrirencanakontrol = e.tglTerbitKontrol
  let noka = e.noKartu
  let nama = e.nama
  let tgllahir = response.response.peserta.tglLahir

  let namaPoliTujuan = e.namaPoliTujuan
  let jeniskelamin = response.response.peserta.sex
  let jnsKontrol = e.jnsKontrol
  let namaDokter = e.namaDokter
  let kddx = '-'
  let nmdpjpsepasal = '-';// e.namaDokter ? e.namaDokter : '-'
  let iddok = 'null'
  let dxawal = '-'

  if (e.noSepAsalKontrol != null) {
    let json = {
      "url": "sep/" + e.noSepAsalKontrol,
      "method": "GET",
      "data": null
    }
    useApi().postBPJS('/bridging/bpjs/tools', json).then((x) => {
      if (x.metaData.code == 200) {
        dxawal = x.response.diagnosa

        cetakBladeSKDP(nosuratkontrol, tglrencanakontrol, txttglentrirencanakontrol, noka,
          nama, tgllahir, SETTING.value.BPJS_namaPPKRujukan, namaPoliTujuan, jeniskelamin, dxawal,
          jnsKontrol, kddx, namaDokter, nmdpjpsepasal, iddok);


      } else {
        H.alert('error', x.metaData.message);
      }
    })

  } else {
    dxawal = '-'
    cetakBladeSKDP(nosuratkontrol, tglrencanakontrol, txttglentrirencanakontrol, noka,
      nama, tgllahir, SETTING.value.BPJS_namaPPKRujukan, namaPoliTujuan, jeniskelamin, dxawal,
      jnsKontrol, kddx, namaDokter, nmdpjpsepasal, iddok);

  }
}
const hapusSurkon = (e: any) => {
  let json = {
    "url": `/RencanaKontrol/Delete`,
    "method": "DELETE",
    "data": {
      "request": {
        "t_suratkontrol": {
          "noSuratKontrol": e.noSuratKontrol,
          "user": H.namaPegawai()
        }
      }
    }
  }

  e.loadHapus = true
  useApi().postBPJS('/bridging/bpjs/tools', json).then((x) => {
    e.loadHapus = false

    if (x.metaData.code == 200) {
      H.alert('success', x.metaData.message)
      delete noSuratKontrol.value
      batalSurkon()
      cariSKDP()
    } else {
      H.alert('error', x.metaData.message)
    }
  })
}
const cetakBladeSKDP = (nosuratkontrol: any, tglrencanakontrol: any, txttglentrirencanakontrol: any, noka: any,
  nama: any, tgllahir: any, namappkRumahSakit: any, namaPoliTujuan: any, jeniskelamin: any, dxawal: any, jnsKontrol: any, kddx: any, namaDokter: any, nmdpjpsepasal: any, iddok: any) => {


  H.printBlade('emr/cetak-spri?nosuratkontrol='
    + nosuratkontrol + '&tglrencanakontrol=' + tglrencanakontrol + '&txttglentrirencanakontrol=' + txttglentrirencanakontrol
    + '&noka=' + noka
    + '&tgllahir=' + tgllahir
    + '&namappkRumahSakit=' + namappkRumahSakit
    + '&namaPoliTujuan=' + namaPoliTujuan
    + '&jeniskelamin=' + jeniskelamin
    + '&dxawal=' + dxawal
    + '&jnsKontrol=' + jnsKontrol
    + '&kddx=' + kddx
    + '&namaDokter=' + namaDokter
    + '&nmdpjpsepasal=' + nmdpjpsepasal
    + '&iddok=' + iddok
    + '&nama=' + nama);
}
const batalSurkon = () => {
  delete input.value.kodeDokter
  delete input.value.poliKontrol
  delete noSuratKontrol.value
  input.value = {
    filterTgl: {
      start: new Date(),
      end: new Date(),
    },
    noKartu: item.noKartu,
    noSEP: item.noSep,
    filter: d_Filter.value[0],
    jenis: 1,
    tglRencanaKontrol: new Date(),
    tahun: {
      kode: new Date().getFullYear(),
      nama: new Date().getFullYear(),
    },
    bulan: bulanAyena,
  }
}
const apiSuratKontrol = async () => {

  let bulan = input.value.bulan.kode + 1

  bulan = bulan.toString().length == 1 ? '0' + bulan.toString() : bulan
  let year = input.value.tahun.kode
  // var bulan = moment(new Date()).format('MM');
  // var year = moment(new Date()).format('YYYY');

  var json = {
    "url": `RencanaKontrol/ListRencanaKontrol/Bulan/${bulan}/Tahun/${year}/Nokartu/${item.noKartu}/filter/${input.value.filter.kode}`,
    "method": "GET",
    "data": null
  }

  const resSurkon = await useApi().postBPJS(
    `/bridging/bpjs/tools`, json)
  return resSurkon
}
const saveSurkon = () => {
  // if (input.value.jenis == 1) {
  //   insertSPRI()
  // } else {
  //   insertRencanaKontrol()
  // }
  emit('savePulang');

}
const insertRencanaKontrol = () => {
  let json = {}

  if (input.value.noSuratKontrol) {
    json = {
      "url": `/RencanaKontrol/Update`,
      "method": "PUT",
      "data": {
        "request": {
          "noSuratKontrol": input.value.noSuratKontrol,
          "noSEP": input.value.noSEP,
          "kodeDokter": input.value.kodeDokter.kodeDokter,
          "poliKontrol": input.value.poliKontrol.kode,
          "tglRencanaKontrol": H.formatDate(input.value.tglRencanaKontrol, 'YYYY-MM-DD'),
          "user": H.namaPegawai()
        }
      }
    }
  } else {
    json = {
      "url": `/RencanaKontrol/insert`,
      "method": "POST",
      "data": {
        "request": {
          "noSEP": input.value.noSEP,
          "kodeDokter": input.value.kodeDokter.kodeDokter,
          "poliKontrol": input.value.poliKontrol.kode,
          "tglRencanaKontrol": H.formatDate(input.value.tglRencanaKontrol, 'YYYY-MM-DD'),
          "user": H.namaPegawai()
        }
      }
    }
  }
  isLoadingSur.value = true
  useApi().postBPJS('/bridging/bpjs/tools', json).then((x) => {
    isLoadingSur.value = false
    if (x.metaData.code == 200) {
      props.isSave = true
      H.alert('success', x.metaData.message)
      noSuratKontrol.value = x.response.noSuratKontrol
    } else {
      H.alert('error', x.metaData.message)
    }
  })
}
</script>
<style lang="scss"></style>
