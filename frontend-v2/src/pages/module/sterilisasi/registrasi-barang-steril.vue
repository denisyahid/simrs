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
                  :to="{ name: 'module-sterilisasi-registrasi-barang-steril' }" light dark-outlined>
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
                <VField label="No Kirim">
                  <VInput v-model="item.noKirim" class="is-rounded"></VInput>
                </VField>
              </div>
              <div class="column is-3">
                <VDatePicker v-model="item.tglAwal" color="green" trim-weeks mode="dateTime" :max-date="new Date()">
                  <template #default="{ inputValue, inputEvents }">
                    <VField>
                      <VLabel>Tanggal registrasi barang</VLabel>
                      <VControl icon="feather:calendar">
                        <VInput type="text" placeholder="Select a date" class="is-rounded" :value="inputValue"
                          v-on="inputEvents" />
                      </VControl>
                    </VField>
                  </template>
                </VDatePicker>
              </div>
              <div class="column is-3">
                <VField class="is-rounded-select is-autocomplete-select" label="Ruangan Tujuan">
                  <VControl icon="feather:search" fullwidth class="prime-auto-select">
                    <Dropdown v-model="item.ruanganTujuan" :options="d_Ruangan" optionLabel="label"
                      placeholder="Pilih Ruangan Tujuan" class="is-rounded" style="width: 100%;" :filter="true" />
                  </VControl>
                </VField>
              </div>
              <div class="column is-3">
                <VField class="is-rounded-select is-autocomplete-select" label="Keterangan">
                  <VInput class="is-rounded" v-model="item.keterangan"></VInput>
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
                  <Column field="jumlah" header="Qty"></Column>
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
</template>
<script setup lang="ts">
import { useRoute, useRouter } from 'vue-router'
import { ref, computed, reactive, watch } from 'vue'
import { useThemeColors } from '/@src/composable/useThemeColors'
import { useApi } from '/@src/composable/useApi'
import { useUserSession } from '/@src/stores/userSession'
import { useHead } from '@vueuse/head'
import { useToaster } from '/@src/composable/toaster'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import Dropdown from 'primevue/dropdown'
import DataTable from 'primevue/datatable'
import Column from 'primevue/column'
import Toolbar from 'primevue/toolbar'
import Button from 'primevue/button'
import AutoComplete from 'primevue/autocomplete'
import ConfirmDialog from 'primevue/confirmdialog'
import { useConfirm } from 'primevue/useconfirm'
import * as H from '/@src/utils/appHelper'
import moment from 'moment'

const TITLE_PAGE = "Registrasi Barang Instalasi CSSD"
useHead({
  title: TITLE_PAGE + import.meta.env.VITE_PROJECT,
})
const item: any = reactive({
  tglAwal: new Date(),
  keterangan: 'Registrasi Barang Steril'

})

const d_Ruangan: any = ref([])
const d_produk: any = ref([])
const disabledRuangan: any = ref(false)
const dataSource: any = ref([])
const isLoading: any = ref(false)
const d_satuan: any = ref([])
const modalInput: any = ref(false)
const data2: any = ref([])
const norecTerima: any = ref('')
const dataSelected: any = ref([])
const isSimpan: any = ref(false)
const confirm = useConfirm();

let norecOrder = useRoute().query.norecOrder as string


const dropdown = async () => {
  await useApi().get(`/stelilisasi/combo`).then((response: any) => {
    d_Ruangan.value = response.ruangan.map((element: any) => {
      return {
        label: element.namaruangan,
        value: element.id
      }
    })
    disabledRuangan.value = false;
    if (d_Ruangan.value.length == 1) {
      item.ruanganTujuan = d_Ruangan.value[0]
    }
  })
}
const addPopUp = () => {
  modalInput.value = true;
}
const editRow = async (e: any) => {
  item.no = e.no
  item.satuan = { value: e.satuanstandarfk, label: e.satuanstandar }
  await fetchProduk({ query: e.namaproduk })
  d_produk.value.forEach((element: any) => {
    if (element.id == e.produkfk) {
      item.produk = element
      return
    }
  });
  GETKONVERSI(e.jumlah)
  dataSelected.value = e
  modalInput.value = true
}
const hapusRow = (item: any) => {
  for (var i = data2.value.length - 1; i >= 0; i--) {
    if (data2.value[i].no == item.no) {
      data2.value.splice(i, 1);
    }
  }
  dataSource.value = data2.value
  clearInput()
}
const fetchProduk = async (filter: any) => {
  useApi().get(`stelilisasi/get-produk?namaproduk=${filter.query}&limit=10`).then((response: any) => {
    d_produk.value = response
  })
}
const changeProduk = async (item: any) => {
  if (item != null) {
    var satuans = [{ value: item.ssid, label: item.satuanstandar }]
    d_satuan.value = satuans
    GETKONVERSI(0)
  }
}
const GETKONVERSI = async (jumlah: any) => {
  item.nilaiKonversi = 1
  item.jumlah = jumlah
}
const changeSatuan = async (e: any) => {
  item.nilaiKonversi = 1
}
const tambah = async () => {
  if (!item.produk) {
    H.alert('warning', 'Produk harus di isi')
    return
  }
  let nomor = 0
  if (data2.value.length == 0) {
    nomor = 1
  } else {
    nomor = data2.value.length + 1
  }
  var data: any = {};
  isLoading.value = true;
  if (item.no != undefined) {
    for (let x = 0; x < data2.value.length; x++) {
      const element = data2.value[x];
      if (element.no == item.no) {
        data.no = item.no
        data.hargajual = String(item.hargaSatuan)
        data.stock = String(item.stok)
        data.harganetto = String(item.hargaSatuan)
        data.nostrukterimafk = norecTerima.value
        data.produkfk = item.produk.id
        data.namaproduk = item.produk.namaproduk
        data.nilaikonversi = item.nilaiKonversi
        data.satuanstandarfk = item.satuan.value
        data.satuanstandar = item.satuan.label
        data.satuanviewfk = item.satuan.value
        data.satuanview = item.satuan.label
        data.jumlah = item.jumlah
        data.qtyorder = element.qtyorder
        data.hargasatuan = String(item.hargaSatuan)
        data.hargadiscount = String(item.hargadiskon)
        data.total = item.total
        var subTotal = 0;
        for (var i = data2.value.length - 1; i >= 0; i--) {
          subTotal = subTotal + parseFloat(data2.value[i].total)
        }
        item.totalSubTotal = parseFloat(subTotal).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,")
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
      produkfk: item.produk.id,
      namaproduk: item.produk.namaproduk,
      nilaikonversi: item.nilaiKonversi,
      satuanstandarfk: item.satuan.value,
      satuanstandar: item.satuan.label,
      satuanviewfk: item.satuan.value,
      satuanview: item.satuan.label,
      jumlah: item.jumlah,
      qtyorder: 0,
      hargasatuan: String(item.hargaSatuan),
      hargadiscount: String(item.hargadiskon),
      total: item.total
    }
    data2.value.push(data)
    var subTotal = 0;
    for (var i = data2.value.length - 1; i >= 0; i--) {
      subTotal = subTotal + parseFloat(data2.value[i].total)
    }
    item.totalSubTotal = parseFloat(subTotal).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,")
  }
  dataSource.value = data2.value
  isLoading.value = false
  clearInput()
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
const save = async () => {
  if (!item.ruanganTujuan) {
    H.alert("warning", "Pilih Ruanganan Tujuan!!")
    return
  }
  if (!item.keterangan) {
    H.alert("warning", "Keterangan Masih Kosong!!")
    return
  }

  if (data2.value.length == 0) {
    H.alert("warning", "Pilih Produk terlebih dahulu!!")
    return
  }
  dialogConfirm();
}
const saveKirimBarang = async () => {
  var strukkirim = {
    objectruangantujuanfk: item.ruanganTujuan ? item.ruanganTujuan.value : '',
    keteranganlainnyakirim: item.keterangan,
    qtydetailjenisproduk: 0,
    qtyproduk: data2.value.length,
    tglkirim: moment(item.tglAwal).format('YYYY-MM-DD HH:mm:ss'),
    totalhargasatuan: 0,
    norecOrder: norecOrder,
    noreckirim: item.noKirim ?? null,
    norec_apd: 0
  }
  var objSave = {
    strukkirim: strukkirim,
    details: data2.value
  }
  isSimpan.value = true
  const response = await useApi().post("/stelilisasi/save-registrasi-barang", objSave)
  isSimpan.value = false
  clearInput()
}
const dialogConfirm = (e: any) => {
  confirm.require({
    message: 'Apakah anda yakin akan kirim barang ?',
    header: 'Konfirmasi Kirim Barang',
    icon: 'pi pi-info-circle',
    acceptClass: 'p-button-danger',
    accept: () => {
      saveKirimBarang()
    },
    reject: () => { },
  })
}
dropdown()
watch(
  () => item.jumlah,
  (newValue, oldValue) => {
    if (newValue != oldValue) {
      item.hargaSatuan = 0
      item.hargadiskon = 0
      item.total = 1
      norecTerima.value = 'as@epic'
    }
  }
)
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
