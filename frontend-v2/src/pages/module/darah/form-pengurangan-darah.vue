<template>
  <ConfirmDialog />
  <div>
    <div class="columns">
      <div class="column is-12 form-layout is-stacked">
        <div class=" form-outer">
          <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
            <div class="form-header-inner">
              <div class="left">
                <h3>{{ TITLE_PAGE }}</h3>
              </div>
              <div class="right">
                <div class="buttons">
                  <!-- <VButton type="button" rounded outlined color="info" raised icon="feather:list"
                    @click="riwayatResep()"> Riwayat
                  </VButton> -->
                  <VButton icon="lnir lnir-arrow-left rem-100" @click="back()" light dark-outlined>
                    Cancel
                  </VButton>
                  <VButton type="button" rounded outlined color="primary" raised icon="feather:save" :loading="isSimpan"
                    @click="save()"> Simpan
                  </VButton>

                </div>
              </div>
            </div>
          </div>
          <div class="form-body">
            <div class="columns is-multiline">
              <div class="column is-12">
                <div class="columns is-multiline">
                  <div class="column is-12">
                    <VCard>
                      <h3 class="title is-5 mb-2">Data Stok Darah</h3>
                      <div class="columns is-multiline">
                        <div class="column is-4">
                          <VDatePicker v-model="item.tglDiambil" color="green" trim-weeks mode="dateTime"
                            :max-date="new Date()">
                            <template #default="{ inputValue, inputEvents }">
                              <VField>
                                <VLabel class="item">Tanggal Diambil</VLabel>
                                <VControl icon="feather:calendar">
                                  <VInput type="text" placeholder="Select a date" :value="inputValue"
                                    v-on="inputEvents" />
                                </VControl>
                              </VField>
                            </template>
                          </VDatePicker>
                        </div>
                        <div class="column is-8">
                          <VField class="is-rounded-select is-autocomplete-select">
                            <VLabel class="required-field">Gudang</VLabel>
                            <VControl icon="feather:search" fullwidth class="prime-auto-select">
                              <Dropdown v-model="item.ruangan" :options="d_Gudang" optionLabel="label"
                                placeholder="Pilih Jenis Darah" style="width: 100%;" :filter="true"
                                :disabled="sourceDarah.length != 0" />
                            </VControl>
                          </VField>
                        </div>
                      </div>

                      <div class="columns is-multiline">
                        <div class="column is-4 pt-0">
                          <VField label="Pegawai Mengeluarkan" class="is-rounded-select is-autocomplete-select">
                            <VControl icon="fa:user" class="prime-auto-cus">
                              <AutoComplete v-model="item.pegawaiMengeluarkan" :suggestions="d_Pegawai"
                                :optionLabel="'label'" @complete="fetchPegawai($event)" :dropdown="true" :minLength="3"
                                :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                                placeholder="Pegawai Penerima" />
                            </VControl>
                          </VField>
                        </div>
                        <div class="column is-4 pt-0">
                          <VField label="Pegawai Penerima" class="is-rounded-select is-autocomplete-select">
                            <VControl icon="fa:user" class="prime-auto-cus">
                              <AutoComplete v-model="item.pegawaiPenerima" :suggestions="d_Pegawai"
                                :optionLabel="'label'" @complete="fetchPegawai($event)" :dropdown="true" :minLength="3"
                                :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                                placeholder="Pegawai Penerima" />
                            </VControl>
                          </VField>
                        </div>
                        <div class="column is-4 pt-0">
                          <VField label="Wali Pasien">
                            <VControl>
                              <input v-model="item.waliPasien" type="text" class="input" placeholder="wali Pasien" />
                            </VControl>
                          </VField>
                        </div>
                      </div>
                    </VCard>
                  </div>
                  <div class="column is-12">
                    <VCard>
                      <div class="columns is-multiline">
                        <div class="column is-12">
                          <Toolbar class="mb-4">
                            <template #end>
                              <VButton icon="feather:plus" color="info" raised @click="showModal(item)">
                                Tambah
                              </VButton>
                            </template>
                          </Toolbar>


                        </div>
                      </div>
                    </VCard>
                  </div>

                  <div class="column is-12">
                    <DataTable :value="sourceDarah" :paginator="true" :rows="5" :rowsPerPageOptions="[5, 10, 25]"
                      :loading="sourceDarah.loading" class="p-datatable-sm"
                      paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
                      responsiveLayout="stack" breakpoint="960px" sortMode="multiple"
                      currentPageReportTemplate="Showing {first} to {last} of {totalRecords}" showGridlines>
                      <Column field="no" header="No"></Column>
                      <Column field="noKantong" header="No Kantong"></Column>
                      <Column field="jenisDarah" header="Jenis Darah" />
                      <Column field="produkDarah" header="Produk" style="min-width:120px" />
                      <Column field="golonganDarah" header="Golongan" />
                      <Column field="satuan" header="Satuan" />
                      <Column field="stokproduk" header="QTY Stok" />
                      <Column field="volumeDarah" header="Volume" />
                      <Column field="qtyKeluar" header="Qty Keluar" />
                      <Column field="tglkadaluarsa" header="TGL Kadaluarsa">
                        <template #body="slotProps">
                          {{ H.formatDate(slotProps.data.tglkadaluarsa, 'YYYY-MM-DD') }}
                        </template>
                      </Column>
                      <Column :exportable="false" header="Action" style="text-align: center;min-width: 100px;">
                        <template #body="slotProps">
                          <VIconButton type="button" icon="feather:edit" class="mr-3" color="info" circle outlined
                            raised :loading="loadingBtnEdit" v-tooltip.top="'Edit'" @click="showModal(slotProps.data)">
                          </VIconButton>
                          <VIconButton type="button" icon="fas fa-trash" color="danger" circle outlined raised
                            v-tooltip.top="'Hapus'" @click="dialogConfirm(slotProps.data)">
                          </VIconButton>
                        </template>
                      </Column>
                    </DataTable>
                  </div>
                </div>
              </div>

            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <VModal is="form" :open="modalInput" title="Form Input Detail" :cancel-label="'Tutup'" size="large" actions="right"
    @close="modalInput = false, clear()">
    <template #content>
      <div class="columns is-multiline">
        <div class="column is-4">
          <VField class="is-rounded-select is-autocomplete-select">
            <VLabel class="required-field">Produk Darah</VLabel>
            <VControl icon="feather:search" fullwidth class="prime-auto-select">
              <Dropdown v-model="item.produkDarah" :options="d_Produk" optionLabel="label"
                @change="fetchStok(item.produkDarah)" placeholder="Pilih Produk Darah" style="width: 100%;"
                :filter="true" />
            </VControl>
          </VField>
        </div>
        <div class="column is-4">
          <VField label="Nomer Kantong">
            <VControl>
              <input v-model="item.noKantong" type="text" class="input" placeholder="Nomer Kantong" disabled />
            </VControl>
          </VField>
        </div>
        <div class="column is-4">
          <VDatePicker v-model="item.tglkadaluarsa" color="green" trim-weeks mode="date">
            <template #default="{ inputValue, inputEvents }">
              <VField>
                <VLabel class="required-field" style="text-overflow:unset">Tanggal Kadaluarsa</VLabel>
                <VControl icon="feather:calendar">
                  <VInput type="text" placeholder="Tanggal Kadaluarsa" :value="inputValue" v-on="inputEvents" />
                </VControl>
              </VField>
            </template>
          </VDatePicker>
        </div>
      </div>

      <div class="columns is-multiline">
        <div class="column is-6">
          <VField class="is-rounded-select is-autocomplete-select">
            <VLabel class="required-field">Jenis Darah</VLabel>
            <VControl icon="feather:search" fullwidth class="prime-auto-select">
              <Dropdown v-model="item.jenisDarah" :options="d_JenisDarah" optionLabel="label"
                placeholder="Pilih Jenis Darah" style="width: 100%;font-weight:bold" :filter="true" disabled />
            </VControl>
          </VField>
        </div>
        <div class="column is-6">
          <VField class="is-rounded-select is-autocomplete-select">
            <VLabel class="required-field">Golongan Darah</VLabel>
            <VControl icon="feather:search" fullwidth class="prime-auto-select">
              <Dropdown v-model="item.golonganDarah" :options="d_GolonganDarah" optionLabel="label"
                placeholder="Pilih Golongan Darah" style="width: 100%;font-weight:bold" :filter="true" disabled />
            </VControl>
          </VField>
        </div>
      </div>

      <div class="columns is-multiline">
        <div class="column is-3">
          <VField class="is-rounded-select is-autocomplete-select">
            <VLabel class="required-field">Satuan</VLabel>
            <VControl icon="feather:search" fullwidth class="prime-auto-select">
              <Dropdown v-model="item.satuan" :options="d_Satuan" optionLabel="label" placeholder="Pilih Produk Darah"
                style="width: 100%;font-weight:bold" :filter="true" />
            </VControl>
          </VField>
        </div>
        <div class="column is-2">
          <VField label="Konversi">
            <VControl>
              <input v-model="item.konversi" type="text" class="input" placeholder="Konversi" disabled />
            </VControl>
          </VField>
        </div>
        <div class="column is-2">
          <VField label="Volume">
            <VControl>
              <input v-model="item.volumeDarah" type="text" class="input" placeholder="Volume" disabled />
            </VControl>
          </VField>
        </div>
        <div class="column is-2">
          <VField label="Stok">
            <VControl>
              <input v-model="item.stokproduk" type="text" class="input" placeholder="Stok" disabled />
            </VControl>
          </VField>
        </div>
        <div class="column is-3">
          <VField label="Qty Dikeluarkan">
            <VControl>
              <input v-model="item.qtyKeluar" type="text" class="input" placeholder="Qty Dikeluarkan" />
            </VControl>
          </VField>
        </div>
      </div>

    </template>

    <template #action>
      <VButton color="primary" raised @click="addData(item)" :loading="isLoadAdd">Simpan</VButton>
    </template>
  </VModal>
</template>

<script setup lang="ts">

import { useWindowScroll } from '@vueuse/core'
import { useApi } from '/@src/composable/useApi'
import {
  ref,
  computed,
  defineComponent,
  watch,
  nextTick,
  onMounted,
  reactive,
  watchEffect
} from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useHead } from '@vueuse/head'
import { formatRp } from '/@src/utils/appHelper'
import { useToaster } from '/@src/composable/toaster'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import PrimeVue from 'primevue/config';
import * as H from '/@src/utils/appHelper'
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import Toolbar from 'primevue/toolbar'
import Dropdown from 'primevue/dropdown';
import Button from 'primevue/button';
import Dialog from 'primevue/dialog';
import AutoComplete from 'primevue/autocomplete';
import moment from 'moment'
import { useThemeColors } from '/@src/composable/useThemeColors'
import { useConfirm } from "primevue/useconfirm"
import ConfirmDialog from 'primevue/confirmdialog'
import { useToast } from "primevue/usetoast";

const toast = useToast();

const TITLE_PAGE = 'Form Pengurangan Darah'
useHead({
  title: TITLE_PAGE + ' ' + import.meta.env.VITE_PROJECT,
})
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)

useViewWrapper().setFullWidth(true)

let NOORDER: any = useRoute().query.noorder as string
let NOREC_RESEP: any = useRoute().query.norec_sr as string

let item: any = reactive({
  tglDiambil: new Date(),
  totalAll: 0,
  jumlah: 0,
  persenDiskon: 0,
  // aturanPakai: [],
  hargadiskon: 0,
  tglAwal: new Date(),
  rke: 1,
  resep: '-',
})
const modalInput = ref(false)
const modalRiwayat = ref(false)
const TOTAL: any = ref(0)
const { y } = useWindowScroll()
const isStuck = computed(() => {
  return y.value > 30
})
const confirm = useConfirm();
const d_penulisResep: any = ref([])
const d_ruangan: any = ref([])
const d_produk: any = ref([])
const d_satuan: any = ref([])
const d_aturanPakai: any = ref([])
const d_kemasan: any = ref([])
const d_jenisRacikan: any = ref([])
const d_route: any = ref([])
const d_tglKadaluarsa: any = ref([])
const d_satuanResep: any = ref([])
const d_asalProduk: any = ref([])

const d_JenisDarah: any = ref([])
const d_GolonganDarah: any = ref([])
const d_KelompokProduk: any = ref([])
const d_Gudang: any = ref([])
const d_Produk: any = ref([])
const d_Pegawai: any = ref([])
const d_Satuan: any = ref([])
const sourceDarah: any = ref([])
const dataSourceRiwayat: any = ref([])
const listRiwayat = ref([])
const data2: any = ref([])
const dataOK: any = ref([])
const norecSPD: any = ref('')
const norecTerima: any = ref('')
const isLoading: any = ref(false)
const isLoadInput: any = ref(false)
const isLoadAdd: any = ref(false)
const isSimpan: any = ref(true)
const tarifJasa: any = ref(0)
const hrg1: any = ref(0)
const hrgsdk: any = ref(0)
const dataProdukDetail: any = ref([])
const disabledRuangan: any = ref(false)
const showGridKronis: any = ref(false)
const dataGridKronis: any = ref([])
const dataSelected: any = ref({})
const isPemakaianObatAlkes: any = ref(false)
const isLoadingRiw: any = ref(false)
const disTanggal: any = ref(false)
const checkResepPulang: any = ref(false)
const isEdit: any = ref(false)
const showRacikanDose: any = ref(false)
const showTgl = ref(false)
const colors: any = ref(Object.keys(useThemeColors()))
const listColor: any = ref([])
for (let i = 0; i < colors.value.length; i++) {
  const element = colors.value[i];
  if (i <= 9 && element != 'primary')
    listColor.value.push(element)
}
const listColor2: any = ref(['primary', 'info', 'orange', 'yellow', 'success'])

const loadDataOrder = async ()=>{
  const response = await useApi().get(`/bank-darah/get-order-darah?noorder=${NOORDER}`)
  dataSelected.value = response[0]
}

const save = async () => {
  // console.log(item.pegawaiPenerima)
  if (sourceDarah.value.length == 0){
    H.alert('error','Darah Yang Dikeluarkan Tidak Tersedia')
  }
  if (!item.waliPasien){
    H.alert('error','Nama Wali Tidak Boleh Kosong')
  }
  let objSave = {
    'strukresep': {
      'noregistrasifk': dataSelected.value.pd_norec,
      'tglDiambil': moment(item.tglDiambil).format('YYYY-MM-DD HH:mm:ss'),
      'norecResep': NOREC_RESEP ? NOREC_RESEP : '',
      'tglregistrasi': dataSelected.value.tglregistrasi,
      'noregistrasi': dataSelected.value.noregistrasi,
      'kelasfk': dataSelected.value.objectkelasfk,
      'pasienfk': dataSelected.value.nocmfk,
      'nocm': dataSelected.value.nocm,
      'namapasien': dataSelected.value.namapasien,
      'petugas': item.pegawaiPenerima.label,
      'penerimafk': item.pegawaiPenerima.value,
      'namapemberi': item.pegawaiMengeluarkan.label,
      'penulisresepfk': item.pegawaiMengeluarkan.value,
      'ruanganfk': item.ruangan.value,
      'noorder': NOORDER,
      'noresep': item.resep ? item.resep : "-",
      'namalengkapambilresep': item.waliPasien,
      'pegawaiPenerima': item.pegawaiPenerima.label,
    },
    'pelayananpasien': sourceDarah.value
  }
  // console.log(objSave)
  await useApi().post('bank-darah/save-pengeluaran-produk', objSave)
}

const addData = (e: any) => {

  if (!item.produkDarah) {
    H.alert('error', 'Produk Darah Tidak Boleh Kosong')
    return
  }
  if (!item.produkDarah) {
    H.alert('error', 'Produk Darah Tidak Boleh Kosong')
    return
  }
  if (e.stokproduk == 0 || e.stokproduk == null) {
    H.alert('error', 'Stok Produk Tidak Tersedia')
    return
  }
  if (e.qtyKeluar < e.stokproduk == null) {
    H.alert('error', 'Stok Produk Tidak Cukup')
    return
  }

  let datas: any = {}
  if (e.no) {
    sourceDarah.value.forEach((element: any, i: any) => {
      if (element.no == e.no) {
        datas.no = element.no,
          datas.noKantong = e.noKantong,
          datas.norec_spd = e.norec_spd,
          datas.jenisDarah = e.jenisDarah.label,
          datas.jenisDarahfk = e.jenisDarah.value,
          datas.golonganDarah = e.golonganDarah.label,
          datas.golonganDarahfk = e.golonganDarah.value,
          datas.produkDarah = e.produkDarah.label,
          datas.produkDarahfk = e.produkDarah.value,
          datas.asalprodukfk = e.asalProdukfk,
          datas.satuanfk = e.satuan.value,
          datas.satuan = e.satuan.label,
          datas.konversi = e.konversi,
          datas.volumeDarah = e.volumeDarah ? e.volumeDarah : null,
          datas.stokproduk = e.stokproduk,
          datas.qtyKeluar = e.qtyKeluar,
          datas.tglkadaluarsa = H.formatDate(e.tglkadaluarsa, 'YYYY-MM-DD HH:mm:ss'),
          sourceDarah.value[i] = datas
      }
    });
  } else {
    datas = {
      no: sourceDarah.value.length == 0 ? 1 : sourceDarah.value.length + 1,
      noKantong: e.noKantong,
      norec_spd: e.norec_spd,
      jenisDarah: e.jenisDarah.label,
      jenisDarahfk: e.jenisDarah.value,
      golonganDarah: e.golonganDarah.label,
      golonganDarahfk: e.golonganDarah.value,
      produkDarah: e.produkDarah.label,
      produkDarahfk: e.produkDarah.value,
      asalprodukfk: e.asalProdukfk,
      satuanfk: e.satuan.value,
      satuan: e.satuan.label,
      konversi: e.konversi,
      volumeDarah: e.volumeDarah ? e.volumeDarah : null,
      stokproduk: e.stokproduk,
      qtyKeluar: e.qtyKeluar,
      tglkadaluarsa: H.formatDate(e.tglkadaluarsa, 'YYYY-MM-DD HH:mm:ss'),
    }
    sourceDarah.value.push(datas)
  }
  if (sourceDarah.value.length > 0) {
    clear()
  }
}

const dialogConfirm = (e: any) => {
  confirm.require({
    message: 'Apakah anda serius menghapus data ini ?',
    header: 'Konfirmasi Hapus Data',
    icon: 'pi pi-info-circle',
    acceptClass: 'p-button-danger',
    accept: () => {
      sourceDarah.value.forEach((element: any, i: any) => {
        if (element.no == e.no) {
          sourceDarah.value.splice(i, 1)
        }
        element.no - 1
      })
      clear()
    },
    reject: () => { },
  })
}

const listData = async () => {
  let response = await useApi().get('bank-darah/get-combo')

  d_GolonganDarah.value = response.golongandarah.map((e: any) => {
    return { label: e.golongandarah, value: e.id }
  })

  d_JenisDarah.value = response.jenisdarah.map((e: any) => {
    return { label: e.detailjenisproduk, value: e.id }
  })

  d_KelompokProduk.value = response.kelompokproduk.map((e: any) => {
    return { label: e.kelompokproduk, value: e.id }
  })

  d_Gudang.value = response.ruangan.map((e: any) => {
    return { label: e.namaruangan, value: e.id }
  })

  d_KelompokProduk.value.forEach((element: any) => {
    if (element.label == 'Pelayanan Bank Darah') {
      console.log(element)
      item.kelompokProduk = { label: element.label, value: element.value }
    }
  });
  fetchProduk(item.kelompokProduk)
  isSimpan.value = false

}

const fetchProduk = async (e: any) => {
  let response = await useApi().get(`/bank-darah/get-produk?idkelompokproduk=${e.value}`)
  d_Produk.value = response.produk.map((e: any) => {
    return { label: e.namaproduk, value: e.id, default: e }
  })
}

const fetchStok = async (e: any) => {
  isLoadAdd.value = true
  let response = await useApi().get(`/bank-darah/get-stok-produk?ruanganfk=${item.ruangan.value}&produkfk=${e.value}`)
  item.noKantong = response[0].nokantong
  item.asalProdukfk = response[0].objectasalprodukfk
  item.norec_spd = response[0].norec_spd
  item.qtyproduk = response[0].qtyproduk
  item.tglkadaluarsa = response[0].tglkadaluarsa
  item.stokproduk = response[0].qtyproduk
  item.volumeDarah = response[0].volume
  d_JenisDarah.value.forEach((elem: any) => {
    if (elem.value == response[0].detailjenisprodukfk) {
      item.jenisDarah = elem
      return
    }
  });
  d_GolonganDarah.value.forEach((elem: any) => {
    if (elem.value == response[0].golongandarahfk) {
      item.golonganDarah = elem
      return
    }
  });
  getSatuan(e)
  isLoadAdd.value = false
}

const getSatuan = (e: any) => {

  let el = e.default
  if (el.konversisatuan != 0) {
    d_Satuan.value = el.konversisatuan.map((element: any) => {
      return { label: element.satuanstandar.toUpperCase(), value: element.satuanstandarfk, konversi: element.nilaikonversi }
    })
    d_Satuan.value.forEach((data: any) => {
      if (data.value == el.satuanstandarfk) {
        item.satuan = data
        item.konversi = data.konversi
        return
      }
    })
  } else {
    d_Satuan.value = [{ label: el.satuanstandar.toUpperCase(), value: el.satuanstandarfk }]
    item.satuan = { label: el.satuanstandar.toUpperCase(), value: el.satuanstandarfk }
    item.konversi = 1
  }

}

const getKonversi = (e: any) => {
  item.value.konversi = e.konversi
}


const fetchPegawai = async (filter: any) => {
  await useApi().get(
    `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`
  ).then((response) => {
    d_Pegawai.value = response
  })
}

const showModal = (e: any) => {
  if (!item.ruangan) {
    H.alert('error', 'Ruangan Tidak Boleh Kosong')
    return
  }
  console.log(e)
  if (e.no) {
    item.no = e.no
    item.noKantong = e.noKantong,
      item.jenisDarah = { label: e.jenisDarah, value: e.jenisDarahfk }
    item.golonganDarah = { label: e.golonganDarah, value: e.golonganDarahfk },
      item.satuan = { label: e.satuan, value: e.satuanfk }
    item.konversi = e.konversi
    item.volumeDarah = e.volumeDarah
    item.stokproduk = e.stokproduk
    item.tglkadaluarsa = e.tglkadaluarsa
    item.qtyKeluar = e.qtyKeluar
    item.asalprodukfk = e.asalprodukfk
    item.norec_spd = e.norec_spd
    d_Produk.value.forEach(element => {
      if (element.value == e.produkDarahfk) {
        item.produkDarah = element
      }
    });
  }
  modalInput.value = true
}

const clear = () => {
  delete item.no
  delete item.norec_spd
  delete item.noKantong
  delete item.tglkadaluarsa
  delete item.jenisDarah
  delete item.golonganDarah
  delete item.satuan
  delete item.konversi
  delete item.volumeDarah
  delete item.stokproduk
  delete item.qtyKeluar
  delete item.asalprodukfk
}

loadDataOrder()
listData()

</script>

<style lang="scss">
@import '/@src/scss/abstracts/all';
@import '/@src/scss/components/forms-outer';
// @import '/@src/scss/custom/config';

.form-layout .form-outer {
  border: 1px solid transparent;
  background-color: transparent;
}


.txt-input {
  align-items: center;
  border: 1px solid transparent;
  border-radius: var(--radius);
  box-shadow: none;
  display: inline-flex;
  font-size: 1rem;
  height: 2.5em;
  justify-content: flex-start;
  line-height: 1.5;
  position: relative;
  vertical-align: top;
  background-color: hsl(0deg, 0%, 96%);
  border-color: hsl(0deg, 0%, 96%);
  box-shadow: none;
  color: hsl(0deg, 0%, 48%);
  height: 38px;
  width: 100%;
  transition: all 0.3s;
  border-radius: var(--radius-rounded);

  padding-left: 38px;
  padding-right: calc(calc(0.75em - 1px) + 0.375em);

}
</style>
