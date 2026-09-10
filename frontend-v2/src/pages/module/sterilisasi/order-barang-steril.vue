<template>
  <ConfirmDialog />
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
                <VButton icon="lnir lnir-arrow-left rem-100" class="btn-orderBarang"
                  :to="{ name: 'module-sterilisasi-daftar-permintaan-alat-steril' }" light dark-outlined>
                  Batal
                </VButton>
                <VButton icon="feather:save" type="submit" color="primary" raised @click="save()"
                  class="btn-orderBarang" :loading="isSimpan">
                  Simpan
                </VButton>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="form-body">
        <div class="column is-12">
          <VCard>
            <div class="columns is-multiline">
              <div class="column is-3">
                <VField label="No Order">
                  <VInput v-model="item.noOrder" class="is-rounded"></VInput>
                </VField>
              </div>
              <div class="column is-3">
                <VDatePicker v-model="item.tglorder" color="green" trim-weeks mode="dateTime" :max-date="new Date()">
                  <template #default="{ inputValue, inputEvents }">
                    <VField>
                      <VLabel>Tanggal</VLabel>
                      <VControl icon="feather:calendar">
                        <VInput type="text" placeholder="Select a date" class="is-rounded" :value="inputValue"
                          v-on="inputEvents" />
                      </VControl>
                    </VField>
                  </template>
                </VDatePicker>
              </div>
              <div class="column is-3">
                <VField class="is-rounded-select is-autocomplete-select" label="Ruangan Asal">
                  <VControl icon="feather:search" fullwidth class="prime-auto-select">
                    <Dropdown v-model="item.ruangAsal" :options="d_Ruangan" optionLabel="label"
                      placeholder="Pilih Ruangan asal" class="is-rounded" style="width: 100%;" :filter="true" />
                  </VControl>
                </VField>
              </div>
              <div class="column is-3">
                <VField class="is-rounded-select is-autocomplete-select" label="Ruangan Tujuan">
                  <VControl icon="feather:search" fullwidth class="prime-auto-select">
                    <Dropdown v-model="item.ruanganTujuan" :options="d_Ruangan" optionLabel="label"
                      placeholder="Pilih Ruangan Tujuan" :disabled="disabledRuangan" class="is-rounded"
                      style="width: 100%;" :filter="true" />
                  </VControl>
                </VField>
              </div>
              <div class="column is-3">
                <VField class="is-rounded-select is-autocomplete-select" label="Jenis order">
                  <VControl icon="feather:search" fullwidth class="prime-auto-select">
                    <Dropdown v-model="item.jenisOrder" :options="d_JenisOrder" optionLabel="label"
                      placeholder="Jenis Order" class="is-rounded" style="width: 100%;" :filter="true" />
                  </VControl>
                </VField>
              </div>
              <div class="column is-3">
                <VField label="Keterangan">
                  <VInput v-model="item.keterangan" class="is-rounded"></VInput>
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
                  <template #start>
                    <VButton icon="feather:plus" color="info" raised class="btn-orderBarang" @click="addPopUp()">
                      Tambah
                    </VButton>
                  </template>
                </Toolbar>
                <DataTable :value="dataSource" :paginator="true" :rows="10" :rowsPerPageOptions="[5, 10, 25]"
                  paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
                  responsiveLayout="stack" breakpoint="960px" sortMode="multiple"
                  currentPageReportTemplate="Showing {first} to {last} of {totalRecords}">
                  <Column :exportable="false" header="#" style="width:8rem">
                    <template #body="slotProps">
                      <Button icon="pi pi-pencil" class="p-button-rounded p-button-warning mr-2"
                        @click="editRow(slotProps.data)" />
                      <Button icon="pi pi-trash" class="p-button-rounded p-button-danger"
                        @click="hapusRow(slotProps.data)" />
                    </template>
                  </Column>
                  <Column field="no" header="No"></Column>
                  <Column field="namaproduk" header="Produk" :sortable="true"></Column>
                  <Column field="satuanstandar" header="Satuan"></Column>
                  <Column field="nilaikonversi" header="Konversi"></Column>
                  <Column field="stock" header="Stok"></Column>
                  <Column field="jumlah" header="Jumlah"></Column>

                  <template #paginatorstart>
                    <Button type="button" icon="pi pi-refresh" class="p-button-text" />
                  </template>
                  <template #paginatorend>
                    <Button type="button" icon="pi pi-cloud" class="p-button-text" />
                  </template>
                </DataTable>
              </div>
            </div>
          </VCard>
        </div>
      </div>
    </div>
  </div>
  <!-- modal input -->
  <VModal :open="modalInput" title="Order Barang" size="large" actions="right" @close="modalInput = false">
    <template #content>
      <form class="modal-form">
        <div class="columns is-multiline">
          <div class="column is-6">
            <VField class="is-rounded-select is-autocomplete-select">
              <VLabel>Produk</VLabel>
              <VControl icon="feather:search">
                <AutoComplete v-model="item.produk" :suggestions="d_produk" @complete="fetchProduk($event)"
                  :optionLabel="'namaproduk'" :dropdown="true" :minLength="3" class="is-rounded" :appendTo="'body'"
                  :loadingIcon="'pi pi-spinner'" :field="'namaproduk'" placeholder="ketik untuk mencari..."
                  @item-select="changeProduk(item.produk)" />
              </VControl>
            </VField>
          </div>
          <div class="column is-4">
            <VField class="is-rounded-select is-autocomplete-select">
              <VLabel>Satuan</VLabel>
              <VControl icon="feather:search">
                <Dropdown v-model="item.satuan" :options="d_satuan" :optionLabel="'label'" class="is-rounded"
                  placeholder="Pilih data" style="width: 100%;" showClear :filter="true"
                  @change="changeSatuan(item.satuan)" />
              </VControl>
            </VField>
          </div>
          <div class="column is-4">
            <VField>
              <VLabel>Konversi</VLabel>
              <VControl icon="feather:bookmark">
                <VInput type="text" v-model="item.nilaiKonversi" placeholder="konversi" class="is-rounded" disabled />
              </VControl>
            </VField>
          </div>
          <div class="column is-4">
            <VField>
              <VLabel>Stok</VLabel>
              <VControl icon="feather:bookmark" :loading="isLoading">
                <VInput type="text" v-model="item.stok" placeholder="Stok" class="is-rounded" disabled />
              </VControl>
            </VField>
          </div>
          <div class="column is-4">
            <VField>
              <VLabel>Jumlah</VLabel>
              <VControl icon="feather:bookmark">
                <VInput type="number" v-model="item.jumlah" placeholder="Jumlah" class="is-rounded" />
              </VControl>
            </VField>
          </div>
        </div>
      </form>
    </template>
    <template #action>
      <VButton icon="feather:plus" @click="tambah()" color="primary" raised>Tambah</VButton>
    </template>
  </VModal>
  <!-- end modal input -->
  <!-- modal stock -->
  <VModal :open="modalStokProduk" title="Informasi produk yang tersedia" size="medium" actions="right"
    @close="modalStokProduk = false">
    <template #content>
      <form class="modal-form">
        <DataTable :value="dataSourceStok" class="p-datatable-sm" responsiveLayout="stack" breakpoint="960px"
          showGridlines sortMode="multiple">
          <Column field="no" header="No"></Column>
          <Column field="namaruangan" header="Ruangan" :sortable="true"></Column>
          <Column field="qtyproduk" header="Stok"></Column>
        </DataTable>
      </form>
    </template>
  </VModal>
  <!-- end modal stock -->
</template>
<script setup lang="ts">
import { useRoute, useRouter } from 'vue-router'
import { ref, computed, reactive, watch } from 'vue'
import { useThemeColors } from '/@src/composable/useThemeColors'
import { useApi } from '/@src/composable/useApi'
import { useUserSession } from '/@src/stores/userSession'
import { useHead } from '@vueuse/head'
import { useToaster } from '/@src/composable/toaster'
import moment from 'moment'
import * as H from '/@src/utils/appHelper'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import * as XLSX from "xlsx"
import * as XLSXStyle from 'xlsx-js-style'
import AutoComplete from 'primevue/autocomplete'
import Dropdown from 'primevue/dropdown'
import Calendar from 'primevue/calendar'
import { FilterMatchMode } from 'primevue/api'
import DataTable from 'primevue/datatable'
import Column from 'primevue/column'
import { useWindowScroll } from '@vueuse/core'
import { useConfirm } from 'primevue/useconfirm'
import ConfirmDialog from 'primevue/confirmdialog'
import Toolbar from 'primevue/toolbar'
import Button from 'primevue/button'
const TITLE_PAGE = "Order Barang Steril"
useHead({
  title: TITLE_PAGE + import.meta.env.VITE_PROJECT,
})
let NOREC_ORDER: any = useRoute().query.norec as string
let NOREC_KIRIM: any = useRoute().query.norec_kirim as string
const confirm = useConfirm();
const { y } = useWindowScroll()
const isStuck = computed(() => {
  return y.value > 30
})
const item: any = reactive({
  tglorder: new Date(),
  keterangan: 'Order Barang Steril'

})
const d_JenisOrder: any = [
  {
    label: 'Transfer',
    value: 2
  }
]
const isLoading: any = ref(false)
const data2: any = ref([])
const isReady: any = ref(false)
const modalStokProduk: any = ref(false)
const d_Ruangan: any = ref([])
const modalInput: any = ref(false)
const dataSource: any = ref([])
const dataSourceStok: any = ref([])
const dataProdukDetail: any = ref([])
const d_produk: any = ref([])
const dataSelected: any = ref({})
const d_satuan: any = ref([])
const d_AsalProduk: any = ref([])
const norecSPD: any = ref('')
const disabledRuangan: any = ref(false)
const norecTerima: any = ref('')
const hrg1: any = ref(0)
const hrgsdk: any = ref(0)
const tarifJasa: any = ref(0)
const isSimpan: any = ref(false)


const dropdown = async () => {
  await useApi().get(`/stelilisasi/combo`).then((response: any) => {
    d_Ruangan.value = response.ruangan.map((element: any) => {
      return {
        label: element.namaruangan,
        value: element.id
      }
    })
    disabledRuangan.value = false;
    d_AsalProduk.value = response.sumberDana.map((element: any) => {
      return {
        label: element.asalProduk,
        value: element.id
      }
    })
    if (d_Ruangan.value.length == 1) {
      item.ruangAsal = d_Ruangan.value[0]
      item.ruanganTujuan = d_Ruangan.value[0]
    }
  })
}
const editRow = async (e: any) => {
  console.log(JSON.stringify(e));
  await fetchProduk({ query: e.namaproduk })
  item.no = e.no
  console.log(e.konversisatuan);
  item.produk = { id: e.id, namaproduk: e.namaproduk, ssid: e.ssid, satuanstandar: e.satuanstandar, konversisatuan: e.konversisatuan, djp: e.djp }
  d_produk.value.forEach((element: any) => {
    if (element.id == e.produkfk) {
      item.produk = element
      return
    }
  });
  d_satuan.value.forEach(element => {
    if (element.value.ssid == e.satuanstandarfk) {
      item.satuan = element
      return
    }
  });
  dataSelected.value = e
  modalInput.value = true
  await GETKONVERSI()

}
const hapusRow = (e: any) => {
  for (var i = data2.value.length - 1; i >= 0; i--) {
    if (data2.value[i].no == e.no) {
      data2.value.splice(i, 1);
    }
  }
  dataSource.value = data2.value
  clearInput()
}
const addPopUp = () => {
  if (!item.ruanganTujuan) {
    H.alert('error', 'Ruangan Tujuan Tidak Boleh Kosong')
    return
  }
  clearInput()
  isLoading.value = false
  modalInput.value = true
}

const fetchProduk = async (filter: any) => {
  useApi().get(`stelilisasi/get-produk?namaproduk=${filter.query}&limit=10`).then((response: any) => {
    d_produk.value = response
  })

}
const changeProduk = (e: any) => {
  if (e != null) {
    GETKONVERSI()
  }
}

const GETKONVERSI = async () => {
  isLoading.value = true
  console.log(item.produk);
  if (item.produk.konversisatuan.length == 0) {
    d_satuan.value = [
      {
        label: item.produk.satuanstandar, value:
          { ssid: item.produk.ssid, satuanstandar: item.produk.satuanstandar }
      }]
  } else {
    d_satuan.value = item.produk.konversisatuan.map((e: any) => {
      return { label: e.satuanstandar, value: e }
    })
  }
  d_satuan.value.forEach(element => {
    if (item.produk.ssid == element.value.ssid) {
      item.satuan = element
    }
  });
  isReady.value = true
  isLoading.value = true
  item.nilaiKonversi = 1
  dataProdukDetail.value = []
  let ruanganfk = item.ruanganTujuan ? item.ruanganTujuan.value : ''
  await useApi().get(
    '/farmasi/get-produkdetail?produkfk=' + item.produk.id +
    '&ruanganfk=' + ruanganfk).then(function (response: any) {
      if (response.detail.length > 0) {
        if (dataSelected.value.no != undefined) {
          item.jumlah = dataSelected.value.jumlah
          item.qtykonfirmasi = dataSelected.value.jumlah
          item.nilaiKonversi = dataSelected.value.nilaikonversi
        }
        dataProdukDetail.value = response.detail
        item.stok = response.jmlstok / item.nilaiKonversi
        if (response.kekuatan == undefined || response.kekuatan == 0) {
          response.kekuatan = 1
        }
        item.kekuatan = response.kekuatan
        item.sediaan = response.sediaan
        item.tglKadaluarsa = response.detail[0]
      } else {
        dialogConfirm(item.produk.id)
        item.stok = 0
        item.hargaSatuan = 0
        item.hargadiskon = 0
        item.hargaNetto = 0
        item.total = 0
      }
    });
  isLoading.value = false
  isReady.value = false
}
const getStokProduk = async (e: any) => {
  await useApi().get(`dashboard/logistik/get-informasi-stok?produkfk=${e}`).then((response) => {
    modalStokProduk.value = true
    response.infostok.forEach((element: any, i: any) => {
      element.no = i + 1
    });
    dataSourceStok.value = response.infostok
  })
}

const dialogConfirm = (e: any) => {
  confirm.require({
    message: 'Tampilkan Informasi Produk ?',
    header: 'Stok Tidak Tersedia',
    icon: 'pi pi-info-circle',
    acceptClass: 'p-button-danger',
    accept: () => {
      getStokProduk(e)
    },
    reject: () => { },
  })
}
const clearInput = () => {
  delete item.produk
  delete item.asal
  delete item.satuan
  delete item.no
  delete item.satuanresep
  delete dataSelected.value
  delete item.tglKadaluarsa
  item.qty = 1
  modalInput.value = false
  item.nilaiKonversi = 0
  item.stok = 0
  item.jumlah = 0
  item.jumlahbulat = item.jumlah
}
const tambah = () => {
  console.log(item.stok);
  if (!item.produk) {
    useToaster().error('Produk harus di isi')
    return
  }
  if (!item.jumlah || item.jumlah == 0) {
    useToaster().error('Jumlah harus di isi')
    return
  }

  if (item.stok == 0) {
    useToaster().error('Stok tidak ada')
    return
  }
  if (!item.satuan) {
    useToaster().error('Satuan harus di isi')
    return
  }

  let nomor = 0
  if (data2.value.length == 0) {
    nomor = 1
  } else {
    nomor = data2.value.length + 1
  }

  // var qtyOK: any = 0;
  var qtyCetak = 0;
  var total = 0;
  var jumlahreal = 0;

  let jmlbulat = 0;
  let jml = 0;

  jmlbulat = item.jumlahbulat;
  jml = item.jumlah;
  isLoading.value = true
  disabledRuangan.value = true;
  var data: any = {};
  if (item.no != undefined) {
    for (let x = 0; x < data2.value.length; x++) {
      const element = data2.value[x];
      if (element.no == item.no) {
        data.no = item.no
        data.hargajual = String(item.hargaSatuan)
        data.stock = String(item.stok)
        data.harganetto = String(item.hargaSatuan)
        data.nostrukterimafk = norecTerima.value
        data.ruanganfk = item.ruanganTujuan.value ? item.ruanganTujuan.value : element.ruanganfk
        data.asalprodukfk = item.asal.id
        data.asalproduk = item.asal.asalproduk
        data.produkfk = item.produk.value
        data.namaproduk = item.produk.namaproduk
        data.nilaikonversi = item.nilaiKonversi
        data.satuanstandarfk = item.satuan.ssid ? item.satuan.ssid : element.satuanstandarfk
        data.satuanstandar = item.satuan.satuanstandar ? item.satuan.satuanstandar : element.satuanstandar
        data.satuanviewfk = item.satuan.ssid ? item.satuan.ssid : element.satuanstandarfk
        data.satuanview = item.satuan.satuanstandar ? item.satuan.satuanstandar : element.satuanstandarfk
        data.jmlstok = String(item.stok)
        data.jumlah = item.jumlah
        data.qtyorder = 0
        data.hargasatuan = String(item.hargaSatuan)
        data.hargadiscount = String(item.hargadiskon)
        data.total = item.total
        data2.value[x] = data;
      }
    }

  } else {
    data = {
      no: nomor,
      hargajual: String(item.hargaSatuan),
      stock: String(item.stok),
      harganetto: String(item.hargaSatuan),
      nostrukterimafk: norecTerima.value,
      ruanganfk: item.ruanganTujuan.value,
      asalprodukfk: item.asal.id,
      asalproduk: item.asal.asalproduk,
      produkfk: item.produk.id,
      namaproduk: item.produk.namaproduk,
      nilaikonversi: item.nilaiKonversi,
      satuanstandarfk: item.satuan.value.ssid,
      satuanstandar: item.satuan.value.satuanstandar,
      satuanviewfk: item.satuan.value.ssid,
      satuanview: item.satuan.value.satuanstandar,
      jmlstok: String(item.stok),
      jumlah: item.jumlah,
      qtyorder: 0,
      hargasatuan: String(item.hargaSatuan),
      hargadiscount: String(item.hargadiskon),
      total: item.total
    }
    data2.value.push(data)

  }
  dataSource.value = data2.value
  isLoading.value = false
  clearInput()
}
const setNorecSPD = async () => {
  if (item.stok == 0) {
    item.jumlah = 0
    return;
  }

  var ada = false;
  for (var i = 0; i < dataProdukDetail.value.length; i++) {
    ada = false
    const element = dataProdukDetail.value[i]

    if (parseFloat(item.jumlah) * parseFloat(item.nilaiKonversi) <= parseFloat(element.qtyproduk)) {
      hrg1.value = Math.round(parseFloat(element.hargajual) * parseFloat(item.nilaiKonversi))
      hrgsdk.value = parseFloat(element.hargadiscount) * parseFloat(item.nilaiKonversi)
      item.hargaSatuan = hrg1.value
      item.hargaNetto = Math.round(parseFloat(element.harganetto) * parseFloat(item.nilaiKonversi))
      if (item.hargadiskon == 0) {
        item.hargadiskon = hrgsdk.value
      } else {
        hrgsdk.value = item.hargadiskon
      }
      item.total = (parseFloat(item.jumlahbulat) * (hrg1.value - hrgsdk.value)) + parseFloat(tarifJasa.value)
      norecTerima.value = element.norec
      norecSPD.value = element.norec_spd
      item.asal = { id: element.objectasalprodukfk, asalproduk: element.asalproduk }
      ada = true;
      break;
    }
  }
  if (ada == false) {
    item.hargaSatuan = 0
    item.hargadiskon = 0
    item.hargaNetto = 0
    item.total = 0

    norecSPD.value = ''
    norecTerima.value = ''
    if (dataProdukDetail.value.length > 1) {
      var stt = 'false'
      if (confirm('Struk Penerimaan berbeda, merge/satukan stok? ')) {
        var objSave =
        {
          produkfk: item.produk.id,
          ruanganfk: item.ruangan.id
        }

        isSimpan.value = true;
        useApi().post(
          '/farmasi/save-stock-merger', objSave).then(function (response: any) {
            clearInput()
            isSimpan.value = false
          })
      } else {
        stt = 'false'
      }

    }
  }
  if (item.jumlah == 0) {
    item.hargaSatuan = 0
    item.hargaNetto = 0
  }
}
const save = async () => {

  if (data2.value.length == 0) {
    useToaster().error('Produk belum di pilih')
    return
  }
  if (!item.jenisOrder) {
    useToaster().error('Jenis Order belum di pilih')
    return
  }
  var Keterangan = 'Order Barang'
  if (item.keterangan != undefined || item.keterangan != '') {
    Keterangan = item.keterangan
  }
  for (var i = data2.value.length - 1; i >= 0; i--) {
    if (parseFloat(data2.value[i].jmlstok) < parseFloat(data2.value[i].jumlah)) {
      useToaster().error("Terdapat obat dengan jumlah melebihi STOK !! " + data2.value[i].namaproduk)
      return
    }
  }
  console.log(item.ruangAsal);
  var strukorder = {
    objectruanganfk: item.ruangAsal ? item.ruangAsal.value : '',
    objectruangantujuanfk: item.ruanganTujuan ? item.ruanganTujuan.value : '',
    jenispermintaanfk: item.jenisOrder ? item.jenisOrder.value : '',
    keteranganorder: Keterangan,
    qtydetailjenisproduk: 0,
    qtyjenisproduk: data2.value.length,
    tglorder: moment(item.tglorder).format('YYYY-MM-DD HH:mm:ss'),
    totalhargasatuan: 0,
    norecorder: NOREC_ORDER ? NOREC_ORDER : '',
    noreckirim: NOREC_KIRIM ? NOREC_KIRIM : '',
  }
  var objSave =
  {
    strukorder: strukorder,
    details: data2.value
  }

  isSimpan.value = true
  await useApi().post(
    `/logistik/save-order-barang`, objSave).then((response: any) => {
      isSimpan.value = false
      window.history.back();
    }, (error) => {
      isSimpan.value = false
    })
}
watch(
  () => item.jumlah,
  (newValue, oldValue) => {
    if (newValue != oldValue) {
      setNorecSPD()
    }
  }
)
dropdown();
</script>
<style lang="scss">
@import '/@src/scss/abstracts/all';
@import '/@src/scss/components/forms-outer';

.form-layout .form-outer {
  border: 1px solid transparent;
  background-color: transparent;
}

.btn-orderBarang {
  padding: 8px 22px !important;
  height: 38px !important;
  line-height: 1.1 !important;
  font-size: 0.95rem !important;
  font-family: var(--font) !important;
  transition: all 0.3s !important;
}
</style>
