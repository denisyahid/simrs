<template>
  <ConfirmDialog />
  <div>
    <div class="form-layout is-stacked">
      <div class="form-outer" style="margin-top: 15px">
        <div class="form-header stuck-header">
          <div class="form-header-inner">
            <div class="left">
              <h3>Form Perencanaan</h3>
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
          <div style="margin-top:2rem" v-if="sourcePurchOrder.loading">
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
            <div class="columns is-mulitline pl-3 pr-3">
              <div class="column is-3">
                <VField label="No Transaksi">
                  <VControl>
                    <input v-model="item.noTransaksi" type="text" class="input" placeholder="No Transaksi" />
                  </VControl>
                </VField>
              </div>
              <div class="column is-3">
                <VField>
                  <VLabel class="required-field">No Perencanaan</VLabel>
                  <VControl>
                    <input v-model="item.noPerencanaan" type="text" class="input" placeholder="No Perencanaan" />
                  </VControl>
                </VField>
              </div>
              <div class="column is-2 p-0 mt-5">
                <VField>
                  <VControl raw subcontrol>
                    <VCheckbox label="otomatis" @change="noSurat(item)" v-model="item.noOtom" color="info" square />
                  </VControl>
                </VField>
              </div>
              <div class="column is-4">
                <VDatePicker v-model="item.tglPerencanaan" color="green" trim-weeks mode="date">
                  <template #default="{ inputValue, inputEvents }">
                    <VField>
                      <VLabel class="required-field">Tanggal Perencanaan</VLabel>
                      <VControl icon="feather:calendar">
                        <VInput type="text" placeholder="Select a date" :value="inputValue" v-on="inputEvents" />
                      </VControl>
                    </VField>
                  </template>
                </VDatePicker>
              </div>
            </div>
            <div class="columns is-multiline p-3">
              <div class="column is-4">
                <VField class="is-rounded-select is-autocomplete-select">
                  <VLabel class="required-field">Jenis Perencanaan</VLabel>
                  <VControl icon="feather:search" fullwidth class="prime-auto-select">
                    <Dropdown v-model="item.jenisPerencanaan" :options="d_jenisPerencanaan" optionLabel="label"
                      placeholder="Pilih Jenis Perencanaan" style="width: 100%;" :filter="true" />
                  </VControl>
                </VField>
              </div>
              <div class="column is-4">
                <VField class="is-rounded-select is-autocomplete-select">
                  <VLabel>Mata Anggaran</VLabel>
                  <VControl icon="feather:search" fullwidth class="prime-auto-select">
                    <Dropdown v-model="item.mataAnggaran" :options="d_MataAnggaran" optionLabel="label"
                      placeholder="Pilih Mata Anggaran" style="width: 100%;" :filter="true" />
                  </VControl>
                </VField>
              </div>
              <div class="column is-4">
                <VField class="is-rounded-select is-autocomplete-select">
                  <VLabel class="required-field">Sumber Dana</VLabel>
                  <VControl icon="feather:search" fullwidth class="prime-auto-select">
                    <Dropdown v-model="item.sumberdana" :options="d_SumberDana" optionLabel="label"
                      placeholder="Pilih Sumber Dana" style="width: 100%;" :filter="true" />
                  </VControl>
                </VField>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="column is-12 p-0 mt-5">
        <VCard>
          <div class="column is-2 p-0 pb-2" style="margin-left: auto">
            <VButton type="button" icon="fas fa-plus-circle" @click="showModal(item)"
              class="is-fullwidth is-outlined is-primary mt-4" rounded raised>
              Tambah
            </VButton>
          </div>
          <DataTable :value="sourcePurchOrder" :paginator="true" :rows="5" :rowsPerPageOptions="[5, 10, 25]"
            :loading="sourcePurchOrder.loading" class="p-datatable-sm"
            paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
            responsiveLayout="stack" breakpoint="960px" sortMode="multiple"
            currentPageReportTemplate="Showing {first} to {last} of {totalRecords}" showGridlines>
            <Column field="no" header="No"></Column>
            <Column field="tglkebutuhan" header="TGL Kebutuhan"></Column>
            <Column field="namaproduk" header="Nama Produk" />
            <Column field="satuanstandar" header="Satuan" />
            <Column field="jumlah" header="Qty" />
            <Column field="hargasatuan" header="Harga Satuan" style="text-align:right">
              <template #body="slotProps">
                {{ H.formatRupiah(H.roundToDecimal(parseFloat(slotProps.data.hargasatuan), 2), '') }}
              </template>
            </Column>
            <Column field="ppn" header="Harga PPN" style="text-align:right">
              <template #body="slotProps">
                {{ H.formatRupiah(H.roundToDecimal(parseFloat(slotProps.data.ppn), 2), '') }}
              </template>
            </Column>
            <Column field="hargadiscount" header="Harga Diskon" style="text-align:right">
              <template #body="slotProps">
                {{ H.formatRupiah(H.roundToDecimal(parseFloat(slotProps.data.hargadiscount), 2), '') }}
              </template>
            </Column>
            <Column field="total" header="Sub Total" style="text-align:right">
              <template #body="slotProps">
                {{ H.formatRupiah(H.roundToDecimal(parseFloat(slotProps.data.total), 2), '') }}
              </template>
            </Column>
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

      <div class="column is-12">
        <div class="content">
          <div class="is-divider" data-content="Total Keseluruhan" />
        </div>
      </div>

      <div class="column is-12 p-0">
        <div class="columns is-multiline">
          <div class="column is-3">
            <VCardCustom :style="'padding:5px 25px'">
              <div class="label-status primary">
                <i aria-hidden="true" class="fas fa-circle"></i>
                <span class="ml-1">SUB TOTAL</span>
              </div>
              <!-- <small>{{ item.subtotal }}</small> -->
              <small class="text-bold-custom h-100">{{
                H.formatRp(item.totalsub, 'Rp.')
              }}</small>
            </VCardCustom>
          </div>
          <div class="column is-3">
            <VCardCustom :style="'padding:5px 25px'">
              <div class="label-status info">
                <i aria-hidden="true" class="fas fa-circle"></i>
                <span class="ml-1">DISKON</span>
              </div>
              <small class="text-bold-custom h-100">{{
                H.formatRp(item.discount, 'Rp.')
              }}</small>
            </VCardCustom>
          </div>
          <div class="column is-3">
            <VCardCustom :style="'padding:5px 25px'">
              <div class="label-status danger">
                <i aria-hidden="true" class="fas fa-circle"></i>
                <span class="ml-1">PPN</span>
              </div>
              <small class="text-bold-custom h-100">{{
                H.formatRp(item.ppnTotal, 'Rp.')
              }}</small>
            </VCardCustom>
          </div>
          <div class="column is-3">
            <VCardCustom :style="'padding:5px 25px'">
              <div class="label-status" color="danger">
                <i aria-hidden="true" class="fas fa-circle"></i>
                <span class="ml-1">TOTAL</span>
              </div>
              <small class="text-bold-custom h-100">{{
                H.formatRp(item.totalall, 'Rp.')
              }}</small>
            </VCardCustom>
          </div>
        </div>
      </div>

      <div class="content">
        <div class="is-divider" />
      </div>
    </div>
  </div>

  <VModal is="form" :open="modalInput" title="Form Input Produk" size="large" actions="right"
    @close="modalInput = false, clear()">
    <template #content>
      <div class="column">
        <div class="columns is-multiline">
          <div class="column is-5">
            <VField class="is-rounded-select is-autocomplete-select">
              <VLabel class="required-field">Produk</VLabel>
              <VControl icon="feather:search" fullwidth class="prime-auto-select">
                <AutoComplete v-model="item.produk" :suggestions="d_Produk" @complete="produk($event)"
                  :optionLabel="'namaproduk'" :dropdown="true" :minLength="3" :appendTo="'body'"
                  @change="getSatuan(item.produk)" :loadingIcon="'pi pi-spinner'" :field="'namaproduk'" />
              </VControl>
            </VField>
          </div>
          <div class="column is-3">
            <VField class="is-rounded-select is-autocomplete-select">
              <VLabel class="required-field">Satuan</VLabel>
              <VControl icon="feather:search" fullwidth class="prime-auto-select">
                <Dropdown v-model="item.satuan" :options="d_Satuan" optionLabel="label" style="width: 100%;"
                  :filter="true" @change="getKonversi(item.satuan)" />
              </VControl>
            </VField>
          </div>

        </div>
      </div>
      <div class="column">
        <div class="columns is-multiline">
          <div class="column is-3">
            <VField label="Jumlah">
              <VControl>
                <input v-model="item.jumlah" type="text" class="input" placeholder="Jumlah" />
              </VControl>
            </VField>
          </div>
          <div class="column is-3">
            <VField label="Harga">
              <VControl :loading="loadHarga">
                <input v-model="item.hargasatuan" type="text" disabled class="input" placeholder="Harga" />
              </VControl>
            </VField>
          </div>
          <div class="column is-3">
            <VField label="Diskon %">
              <VControl>
                <input v-model="item.persendiscount" type="text" class="input" placeholder="Harga" />
              </VControl>
            </VField>
          </div>
          <div class="column is-3">
            <VField label="Harga Diskon">
              <VControl>
                <input v-model="item.hargaDiskon" type="text" disabled class="input" placeholder="Harga" />
              </VControl>
            </VField>
          </div>
        </div>
      </div>
      <div class="column">
        <div class="columns is-multiline">
          <div class="column is-2">
            <VField label="PPN %">
              <VControl>
                <input v-model="item.persenPPN" type="text" class="input" placeholder="PPN %" />
              </VControl>
            </VField>
          </div>
          <div class="column is-4">
            <VField label="PPN">
              <VControl>
                <input v-model="item.nilaiPPN" type="text" disabled class="input" placeholder="PPN" />
              </VControl>
            </VField>
          </div>
          <div class="column is-6">
            <VField label="Sub Total">
              <VControl>
                <input v-model="item.subTotal" type="text" class="input" placeholder="Sub Total" />
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
  title: 'Form Purchase Order - ' + import.meta.env.VITE_PROJECT,
})
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)
const isLoadingPasien: any = ref(false)

const confirm = useConfirm()
const router = useRouter();
const route = useRoute()

let status = route.query.keterangan as string

let item: any = ref({
  tglPR: new Date(),
  tglKebutuhan: new Date()
})

const d_Pegawai = ref([])
const d_SumberDana = ref([])
const d_kelompokBarang = ref([])
const d_unitPengorder = ref([])
const d_unitTujuan = ref([])
const d_PenanggungJawab = ref([])
const d_komit = ref([])
const d_Gudang = ref([])
const d_suplier = ref([])
const d_Produk = ref([])
const d_Satuan: any = ref([])
const colors: any = ref(Object.keys(useThemeColors()))
const dataProdukDetail: any = ref([])
const modalInput: any = ref(false)
const loadHarga = ref(false)
const listColor: any = ref([])
const nostruk: any = ref()
const noBukti: any = ref()
let sourcePurchOrder: any = ref([])
let loadingBtnEdit: any = ref(false)
let isLoadBtnSave: any = ref(false)
let loadNBK: any = ref(false)
let isLoadProduk: any = ref(false)
const d_MataAnggaran = ref([])
const d_jenisPerencanaan = ref([
  { "id": 1, "label": "Tahunan" },
    { "id": 2, "label": "Bulanan" },
    { "id": 3, "label": "Cito" },
])

const noSurat = (e: any) => {
  if (e.noOtom) {
    createNoFaktur()
  } else {
    delete item.value.noFaktur
  }
}

const createNoFaktur = () => {
  /* Format No Faktur PB/BLN-THN/APT/NO URUT (APT = BLU, BG = Hibah,  KK = Kas  Kecil) */
  let nows = moment(new Date()).format('MM-YY')
  item.value.noPerencanaan = 'PB/' + nows + '/APT/____'
}

for (let i = 0; i < colors.value.length; i++) {
  const element = colors.value[i]
  if (i <= 9 && element != 'primary') listColor.value.push(element)
}
const { y } = useWindowScroll()
const isStuck = computed(() => {
  return y.value > 30
})

const saveData = async () => {

  if(status == 'verif'){
    item.value.statusOrder = 1
  }else{
    item.value.statusOrder = 0
  }

  if (sourcePurchOrder.value.length == 0) {
    H.alert('error', 'Data Tidak Tersedia')
    return
  }

  if (!item.value.tglPR) {
    H.alert('error', 'Tanggal PO Tidak Boleh Kosong')
    return
  }

  if (!item.value.tglPerencanaan) {
    H.alert('error', 'Tanggal Perencanaan Tidak Boleh Kosong')
    return
  }


  isLoadBtnSave.value = true

  let strukOrder = {
    tglUsulan: H.formatDate(item.value.tglPR, 'YYYY-MM-DD HH:mm:ss'),
    tglDibutuhkan: H.formatDate(new Date(), 'YYYY-MM-DD HH:mm:ss'),
    norec: item.value.norec ? item.value.norec : '',
    norecrealisasi: item.value.norecrealisasi ? item.value.norecrealisasi : '',
    notransaksi: item.value.noTransaksi ? item.value.noTransaksi : '',
    noperencanaan: item.value.noPerencanaan ? item.value.noPerencanaan : '',
    tglperencanaan: H.formatDate(item.value.tglPerencanaan, 'YYYY-MM-DD HH:mm:ss'),
    jenisperencanaan: item.value.jenisPerencanaan ? item.value.jenisPerencanaan.label : '',
    mataanggaran: item.value.mataAnggaran.value ? item.value.mataAnggaran.value : '',
    sumberdana: item.value.sumberdana.value ? item.value.sumberdana.value : '',
    totalharga: item.value.totalsub,
    totaldiskon: item.value.discount,
    totalppn: item.value.ppnTotal,
    grandtotal: item.value.totalall,
    statusorder: item.value.statusOrder ? item.value.statusOrder : 0,
  }
  let objSave = {
    strukorder: strukOrder,
    details: sourcePurchOrder.value
  }

  await useApi().post('logistik/save-data-rencana-usulan', objSave).then((response) => {
    goToPageDaftar()
  })
  isLoadBtnSave.value = false
}

const addData = (e: any) => {
  if (!e.produk) {
    useToaster().error('Produk Tidak Boleh Kosong')
    return
  }
  if (!e.jumlah) {
    useToaster().error('Jumlah Tidak Boleh Kosong')
    return
  }

  let datas: any = {}
  if (e.no) {
    sourcePurchOrder.value.forEach((element: any, i: any) => {
      if (element.no == e.no) {
        datas.no = element.no,
          datas.namaproduk = e.produk.namaproduk,
          datas.produk = e.produk,
          datas.produkfk = e.produk.id,
          datas.satuanstandar = e.satuan.label,
          datas.satuanstandarfk = e.satuan.value,
          datas.jumlah = e.jumlah,
          datas.hargasatuan = e.hargasatuan,
          datas.nilaikonversi = item.value.konversi,
          datas.total = parseFloat(e.subTotal),
          datas.spesifikasi = e.spesifikasi,
          datas.persendiscount = e.persendiscount ? e.persendiscount : 0,
          datas.hargadiscount = e.hargaDiskon ? e.hargaDiskon : 0,
          datas.persenppn = e.persenPPN ? e.persenPPN : 0,
          datas.ppn = e.nilaiPPN ? e.nilaiPPN : 0,
          datas.tglkebutuhan = e.tglKebutuhan ? H.formatDate(e.tglKebutuhan, 'YYYY-MM-DD HH:mm:ss') : '',
          sourcePurchOrder.value[i] = datas
      }
    });
  } else {
    datas = {
      no: sourcePurchOrder.value.length == 0 ? 1 : sourcePurchOrder.value.length + 1,
      hargasatuan: parseFloat(e.hargasatuan),
      jumlah: e.jumlah,
      namaproduk: e.produk.namaproduk,
      nilaikonversi: item.value.konversi,
      persendiscount: e.persendiscount ? e.persendiscount : 0,
      hargadiscount: e.hargaDiskon ? e.hargaDiskon : 0,
      persenppn: e.persenPPN ? e.persenPPN : 0,
      ppn: e.nilaiPPN ? e.nilaiPPN : 0,
      produkfk: e.produk.id,
      produk: e.produk,
      satuanstandar: e.satuan.label,
      satuanstandarfk: e.satuan.value,
      spesifikasi: e.spesifikasi,
      total: e.subTotal,
      tglkebutuhan: e.tglKebutuhan ? H.formatDate(e.tglKebutuhan, 'YYYY-MM-DD HH:mm:ss') : '',
    }
    sourcePurchOrder.value.push(datas)
  }
  if (sourcePurchOrder.value.length > 0) {
    clear()
  }
  modalInput.value = false
  count()
}

const showModal = async (e: any) => {

  if (e.no) {
    loadingBtnEdit.value = true
    let filter = { query: e.namaproduk }
    await produk(filter)
    d_Produk.value.forEach(element => {
      item.value.produk = element
      getSatuan(element)
    });
    item.value.no = e.no
    item.value.tglKebutuhan = e.tglkebutuhan
    item.value.produkfk = e.produkfk
    item.value.satuan = { label: e.satuanstandar, value: e.satuanstandarfk }
    item.value.satuanstandarfk = e.satuanstandarfk
    item.value.jumlah = e.jumlah
    item.value.hargasatuan = e.hargasatuan
    item.value.konversi = e.nilaikonversi
    item.value.total = e.subTotal
    item.value.spesifikasi = e.spesifikasi
    item.value.persendiscount = e.persendiscount
    item.value.hargaDiskon = e.hargadiskon
    item.value.persenPPN = e.persenppn
    item.value.nilaiPPN = e.ppn
    item.value.suplier = {label : e.namarekanan  , value : e.rekananfk}
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
      sourcePurchOrder.value.forEach((element: any, i: any) => {
        if (element.no == e.no) {
          sourcePurchOrder.value.splice(i, 1)
        }
        element.no - 1
      })
      count()
      clear()
    },
    reject: () => { },
  })
}

const clear = () => {
  delete item.value.no
  delete item.value.tglKebutuhan
  delete item.value.produk
  delete item.value.satuan
  delete item.value.jumlah
  delete item.value.hargasatuan
  delete item.value.persendiscount
  delete item.value.hargaDiskon
  delete item.value.persenPPN
  delete item.value.suplier
  delete item.value.nilaiPPN
  delete item.value.spesifikasi
  delete item.value.subTotal
}

const updateDataSupplier = (e: any) => {
  if (!e.satuan) {
    useToaster().error('Satuan Produk Tidak Boleh Kosong')
    return
  }
  if (!e.jumlah) {
    useToaster().error('Jumlah Tidak Boleh Kosong')
    return
  }

  let data: any = {}
  sourcePurchOrder.value.forEach((element: any, i: any) => {
    if (element.no == e.no) {
      data.no = element.no
      data.produk = e.produk
      data.asalproduk = e.sumberdana
      data.produkfk = e.produk.value.id
      data.namaproduk = e.produk.value.namaproduk
      data.satuan = e.satuan.label
      data.ssid = e.satuan.value.ssid
      data.satuanstandarfk = e.satuan.value.ssid
      data.listSatuan = e.satuan
      data.nilaikonversi = e.konversi
      data.jumlah = e.jumlah
      data.jumlahdipakai = 0
      data.sisa = e.jumlah
      data.hargasatuan = e.hargasatuan
      data.persendiscount = e.persendiscount ? e.persendiscount : '0'
      data.hargadiskon = e.hargadiskon ? e.hargadiskon : '0'
      data.nobatch = e.nobatch ? e.nobatch : '-'
      data.keterangan = e.keterangan ? e.keterangan : ''
      data.persenppn = e.persenppn
      data.nilaippn = e.nilaippn ? e.nilaippn : '0'
      data.subtotal = parseFloat(e.hargasatuan) * parseFloat(e.jumlah)
      data.totalall = e.subtotal
      data.tglkadaluarsa = e.tglkadaluarsa ? H.formatDate(e.tglkadaluarsa, 'YYYY-MM-DDTHH:mm:ss') : null
      sourcePurchOrder.value[i] = data
    }
  })
  count()
  modalInput.value = false
}

const listData = async () => {
  await useApi()
    .get('logistik/penerimaan-barang/get-data-combo')
    .then((response) => {
      d_komit.value = response.pembuatkomit.map((e: any) => {
        return { label: e.namalengkap, value: e.id }
      })
      d_PenanggungJawab.value = response.pegawai.map((e: any) => {
        return { label: e.namalengkap, value: e.id }
      })
      d_SumberDana.value = response.sumberdana.map((e: any) => {
        return { label: e.asalproduk, value: e.id }
      })
      d_kelompokBarang.value = response.kelompokbarang.map((e: any) => {
        return { label: e.kelompokproduk, value: e.id }
      })
      d_unitPengorder.value = response.ruangan.map((e: any) => {
        return { label: e.namaruangan, value: e.id }
      })
      d_suplier.value = response.suplier.map((e: any) => {
        return { label: e.namarekanan, value: e.id }
      })
      d_MataAnggaran.value = response.mataanggaran.map((e: any) => {
        return { label: e.namamataanggaran, value: e.id }
      })

    })
}

const produk = async (e: any) => {
  let search = e.query ? `?namaproduk=${e.query}` : ''
  await useApi().get(`logistik/get-combo-barang-logistik${search}`).then((response) => {
    d_Produk.value = response
  })
}

const getSatuan = (e: any) => {

  if (e.id != undefined) {
    if (e.konversisatuan) {
      if (e.konversisatuan.length != 0) {
        d_Satuan.value = e.konversisatuan.map((element: any) => {
          return { label: element.satuanstandar, value: e.ssid }
        })
        d_Satuan.value.forEach((data: any) => {
          if (data.value == e.ssid) {
            item.value.satuan = data
            item.value.konversi = data.konversi
          }
        });
      } else {
        d_Satuan.value = [{ label: e.satuanstandar, value: e.ssid }]
        item.value.konversi = e.konversisatuan.length == 0 ? 1 : e.konversisatuan
        d_Satuan.value.forEach((data: any) => {
          if (data.value == e.ssid) {
            item.value.satuan = data
          }
        })
      }
      getHargaProduk(e.id)
    }
  }
}

const getKonversi = (e: any) => {
  item.value.konversi = e.konversi
}

const getHargaProduk = async (e: any) => {
  loadHarga.value = true
  await useApi().get(`logistik/get-harga-produk?produkfk=${e}`).then((response: any) => {
    item.value.hargasatuan = response.detail[0].harga
  })
  loadHarga.value = false
}

const count = () => {

  let totalsub = 0
  let discount = 0
  let ppn = 0
  let total = 0
  let totalAll = 0;
  sourcePurchOrder.value.forEach((element: any) => {
    totalsub += element.hargasatuan * element.jumlah
    discount = element.hargadiscount === '' ? discount : discount + parseFloat(element.hargadiscount)
    ppn = element.ppn === '' ? ppn : ppn + parseFloat(element.ppn)
    total = totalsub - discount + ppn
  })
  item.value.totalsub = totalsub
  item.value.discount = discount
  item.value.ppnTotal = ppn
  item.value.totalall = total
}

const fetchUnitTujuan = async (filter: any) => {
  await useApi().get(
    `emr/dropdown/ruangan_m?select=id,namaruangan&param_search=namaruangan&query=${filter.query}&limit=10`
  ).then((response) => {
    d_unitTujuan.value = response
  })
}

const goToPageDaftar = () => {
  router.push({ name: 'module-logistik-daftar-form-perencanaan' })
}

const isEditPO = () => {
  const norec = route.query.norec
  if (norec) {
    sourcePurchOrder.value.loading = true
    useApi().get(`logistik/get-detail-perencanaan?norecOrder=${norec}`).then((response) => {
      let header = response.daftar
      let details = response.daftar.details
      item.value.notransaksi = header.noorder
      item.value.noPerencanaan = header.noorderintern,
      item.value.tglPerencanaan = header.tglorder,
      item.value.jenisperencanaan = d_jenisPerencanaan.value[0],
      item.value.mataanggaran = d_MataAnggaran.value[0],
      item.value.sumberdana = d_SumberDana.value[0],
      item.value.kelompokBarang = { label: header.kelompokbarang, value: header.kelompokbarangfk }
      item.value.unitPengorder = { label: header.unitpengusul, value: header.idunitpengusul }
      item.value.penanggungJawab = { label: header.petugas, value: header.petugasid }
      item.value.unitTujuan = { label: header.unittujuan, value: header.idunittujuan }
      item.value.suplier = { label: header.namarekanan, value: header.namarekananid }
      item.value.tglPR = header.tglorder
      item.value.tglJatuhTempo = header.tglrencana
      item.value.keterangan = header.keterangan
      item.value.norec = norec
      item.value.norecrealisasi = header.norecrealisasi
      item.value.norecrrusulan = header.norecrrusulan
      sourcePurchOrder.value = details
      sourcePurchOrder.value.loading = false
      count()
    })
      .catch((e: any) => {
        console.log(e)
      })
  }
}

watch(
  () => [
    item.value.persenPPN,
    item.value.hargasatuan,
    item.value.persendiscount,
    item.value.jumlah,
  ],
  () => {

    if (item.value.jumlah === undefined || item.value.jumlah === '') {
      item.value.subTotal = ''
    } else {
      const diskon: any = item.value.persendiscount == '' ? delete item.value.persendiscount : item.value.persendiscount / 100
      const nilaiDiskon: any = parseFloat(item.value.jumlah) * parseFloat(item.value.hargasatuan) * diskon
      const resultHarga: any = nilaiDiskon ? parseFloat(item.value.jumlah) * parseFloat(item.value.hargasatuan) - parseFloat(nilaiDiskon)
        : parseFloat(item.value.jumlah) * parseFloat(item.value.hargasatuan)
      const ppn: any = item.value.persenPPN / 100
      const nilaippn = resultHarga * ppn
      item.value.hargaDiskon = diskon ? nilaiDiskon : ''
      item.value.nilaiPPN = ppn ? nilaippn : ''
      item.value.subTotal = nilaippn ? parseFloat(resultHarga) + nilaippn : resultHarga
    }
  }
)

const back = () => {
  window.history.back()
}

listData()
isEditPO()

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

