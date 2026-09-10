<template>
  <ConfirmDialog />
  <div>
    <div class="form-layout is-stacked">
      <div class="form-outer" style="margin-top: 15px">
        <div class="form-header stuck-header">
          <div class="form-header-inner">
            <div class="left">
              <h3>Form Pemakaian Barang</h3>
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
          <div style="margin-top:2rem" v-if="sourceData.loading">
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

            <div class="columns is-multiline">
              <div class="column is-3">
                <VField label="No Kirim">
                  <VControl icon="feather:bookmark">
                    <input v-model="item.nokirim" type="text" class="input" placeholder="No Kirim" />
                  </VControl>
                </VField>
              </div>
              <div class="column is-2">
                <VDatePicker v-model="item.tglkirim" color="green" trim-weeks mode="date" :max-date="new Date()">
                  <template #default="{ inputValue, inputEvents }">
                    <VField>
                      <VLabel>Tanggal</VLabel>
                      <VControl icon="feather:calendar">
                        <VInput type="text" placeholder="Select a date" :value="inputValue" v-on="inputEvents" />
                      </VControl>
                    </VField>
                  </template>
                </VDatePicker>
              </div>
              <div class="column is-3">
                <VField class="is-rounded-select is-autocomplete-select">
                  <VLabel class="required-field">Ruangan Stok</VLabel>
                  <VControl icon="feather:search" fullwidth class="prime-auto-select">
                    <Dropdown v-model="item.ruanganStok" :options="d_RuanganStok" optionLabel="label"
                      style="width: 100%;" :filter="true" placeholder="Cari Ruangan Stok" />
                  </VControl>
                </VField>
              </div>
              <div class="column is-4">
                <VField label="Ruangan Pemakai" class="is-rounded-select is-autocomplete-select">
                  <VControl icon="feather:search" fullwidth class="prime-auto-select">
                    <AutoComplete v-model="item.ruanganpemakai" :suggestions="d_Ruangan" @complete="fetchRuangan($event)"
                      :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                      :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="Cari Ruangan Pemakai" />
                  </VControl>
                </VField>
              </div>
              <div class="column is-3 pt-1">
                <VField class="is-rounded-select is-autocomplete-select">
                  <VLabel class="required-field">Jenis Kirim</VLabel>
                  <VControl icon="feather:search" fullwidth class="prime-auto-select">
                    <Dropdown v-model="item.jeniskirim" :options="d_JenisKirim" optionLabel="label"
                      style="width: 100%;font-weight: bold;" disabled :filter="true" />
                  </VControl>
                </VField>
              </div>
              <div class="column is-9 pt-1">
                <VField label="Keterangan">
                  <VControl icon="feather:bookmark">
                    <input v-model="item.keterangan" type="text" class="input" placeholder="Keterangan" />
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
          <DataTable :value="sourceData" :paginator="true" :rows="5" :rowsPerPageOptions="[5, 10, 25]"
            :loading="sourceData.loading" class="p-datatable-sm"
            paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
            responsiveLayout="stack" breakpoint="960px" sortMode="multiple"
            currentPageReportTemplate="Showing {first} to {last} of {totalRecords}" showGridlines>
            <Column field="no" header="No"></Column>
            <Column field="kdproduk" header="KD Produk"></Column>
            <Column field="namaproduk" header="Nama Produk" />
            <Column field="satuanstandar" header="Satuan" />
            <Column field="jmlstok" header="Stok" />
            <Column field="jumlah" header="Qty" />

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

    </div>
  </div>

  <VModal is="form" :open="modalInput" title="Form Input Produk" size="large" actions="right"
    @close="modalInput = false, clear()">
    <template #content>
      <div class="column">
        <div class="columns is-multiline">
          <div class="column is-6">
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
          <div class="column is-3">
            <VField label="Konversi">
              <VControl>
                <input v-model="item.konversi" type="text" class="input" placeholder="Konversi" disabled
                  style="font-weight:bold" />
              </VControl>
            </VField>
          </div>
        </div>
      </div>

      <div class="column">
        <div class="columns is-multiline">
          <div class="column is-4">
            <VField label="Stok">
              <VControl>
                <input v-model="item.stok" type="text" class="input" placeholder="Stok" disabled
                  style="font-weight:bold" />
              </VControl>
            </VField>
          </div>
          <div class="column">
            <div class="columns is-multiline">
              <div class="column is-4">
                <VField label="Satuan Harga">
                  <VControl :loading="loadHarga">
                    <input v-model="item.hargasatuan" type="text" class="input" placeholder="Harga Satuan" disabled
                      style="font-weight:bold" />
                  </VControl>
                </VField>
              </div>
              <div class="column is-8">
                <VField label="Jumlah">
                  <VControl>
                    <input v-model="item.jumlah" type="text" class="input" placeholder="Jumlah" />
                  </VControl>
                </VField>
              </div>
            </div>
          </div>
          <div class="column is-12">
            <div class="content">
              <div class="is-divider" :data-content="infoStok" />
            </div>
          </div>

          <div class="column is-12 pt-0">
            <DataTable :value="dataSourceStokProduk" :paginator="true" :rows="5" :rowsPerPageOptions="[5, 10, 25]"
              paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
              responsiveLayout="stack" breakpoint="960px" sortMode="multiple"
              currentPageReportTemplate="Showing {first} to {last} of {totalRecords}" showGridlines>

              <Column field="no" header="No"></Column>
              <Column field="namaruangan" header="ruangan"></Column>
              <Column field="stok" header="Stok"></Column>
            </DataTable>

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
  title: 'Form Pemakaian Barang - ' + import.meta.env.VITE_PROJECT,
})
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)

const isLoadingPasien: any = ref(false)
const confirm = useConfirm()
const router = useRouter();
const route = useRoute()

const item: any = ref({
  tglPO: new Date(),
  tglKebutuhan: new Date()
})

const d_JenisKirim = ([
  {
    label : 'Amprahan',
    value : 1,
  },
  {
    label: 'Transfer',
    value: 2,
  }
])
const d_Ruangan = ref([])
const d_RuanganStok = ref([])
const d_PenanggungJawab = ref([])
const d_Produk = ref([])
const d_Satuan: any = ref([])
const colors: any = ref(Object.keys(useThemeColors()))
const dataSourceStokProduk: any = ref([])
const dataProdukDetail: any = ref([])
const modalInput: any = ref(false)
const infoStok = ref('List Informasi Stok')
const loadHarga = ref(false)
const listColor: any = ref([])
const nostruk: any = ref()
const noBukti: any = ref()
let sourceData: any = ref([])
let loadingBtnEdit: any = ref(false)
let isLoadBtnSave: any = ref(false)
let loadNBK: any = ref(false)
let isLoadProduk: any = ref(false)

for (let i = 0; i < colors.value.length; i++) {
  const element = colors.value[i]
  if (i <= 9 && element != 'primary') listColor.value.push(element)
}
const { y } = useWindowScroll()
const isStuck = computed(() => {
  return y.value > 30
})

const saveData = () => {

  if (!item.value.ruanganStok) {
    H.alert('error','Ruangan Stok Tidak Boleh Kosong')
    return
  }
  if (!item.value.ruanganpemakai) {
    H.alert('error', 'Ruangan Pemakai Tidak Boleh Kosong')
    return
  }
  if (!item.value.keterangan) {
    H.alert('error', 'Keterangan Tidak Boleh Kosong')
    return
  }

  if (sourceData.value.length == 0) {
    H.alert('error', 'Data Tidak Tersedia')
    return
  }
  
  isLoadBtnSave.value = true

  var strukkirim = {
    objectpegawaipengirimfk: H.pegawaiLogin().id,
    objectruanganfk: item.value.ruanganStok.value,
    objectruangantujuanfk: item.value.ruanganpemakai.value,
    jenispermintaanfk: item.value.jeniskirim.value,
    keteranganlainnyakirim: item.value.keterangan ? item.value.keterangan : 'Kirim Barang',
    qtydetailjenisproduk: 0,
    qtyproduk: sourceData.value.length,
    tglkirim: H.formatDate(item.value.tglkirim,'YYYY-MM-DD HH:mm:ss'),
    totalhargasatuan: 0,
    norecOrder: item.value.noorderfk ? item.value.noorderfk : '',
    noreckirim: item.value.noreckirim ? item.value.noreckirim : '',
    norec_apd: 0
  }
  var objSave =
  {
    strukkirim: strukkirim,
    details: sourceData.value
  }


  useApi().post('/iprs/kirim-produk-pemakaian-barang', objSave).then((response) => {

  })
  isLoadBtnSave.value = false
}

const addData = (e: any) => {

  if (!item.value.hargasatuan) {
    H.alert('error', 'Harga Satuan Tidak Boleh Kosong')
    return
  }

  if (!item.value.jumlah) {
    H.alert('error', 'Jumlah Tidak Boleh Kosong')
    return
  }
  let datas: any = {}
  if (e.no) {
    sourceData.value.forEach((element: any, i: any) => {
      if (element.no == e.no) {
        data.no = element.no
        data.hargajual = item.value.hargasatuan
        data.stock = item.value.stok
        data.harganetto = item.value.hargasatuan
        data.nostrukterimafk = null
        data.ruanganfk = item.value.ruanganStok.value
        data.asalprodukfk = 1
        data.asalproduk = 'Badan Layanan Umum Daerah',
        data.produkfk = item.value.produk.id
        data.kdproduk = iitem.value.kdproduk
        data.namaproduk = item.value.produk.namaproduk
        data.nilaikonversi = item.value.konversi
        data.satuanstandarfk = item.value.satuan.value
        data.satuanstandar = item.value.satuan.label
        data.satuanviewfk = item.value.satuan.value
        data.satuanview = item.value.satuan.label
        data.jmlstok = item.value.stok
        data.jumlah = item.value.jumlah
        data.qtyorder = 0
        data.hargasatuan = item.value.hargasatuan
        data.hargadiscount = 0
        data.total = parseFloat(item.value.jumlah) * parseFloat(item.value.hargasatuan)
        sourceData.value[i] = datas
      }
    });
  } else {
    datas = {
      no: sourceData.value.length == 0 ? 1 : sourceData.value.length + 1,
      hargajual: item.value.hargasatuan,
      stock: item.value.stok,
      harganetto: item.value.hargasatuan,
      nostrukterimafk: null,
      ruanganfk: item.value.ruanganStok.value,
      asalprodukfk: 1,
      asalproduk: 'Badan Layanan Umum Daerah',
      produkfk: item.value.produk.id,
      kdproduk: item.value.kdproduk,
      namaproduk: item.value.produk.namaproduk,
      nilaikonversi: item.value.konversi,
      satuanstandarfk: item.value.satuan.value,
      satuanstandar: item.value.satuan.label,
      satuanviewfk: item.value.satuan.value,
      satuanview: item.value.satuan.label,
      jmlstok: item.value.stok,
      jumlah: item.value.jumlah,
      qtyorder: 0,
      hargasatuan: item.value.hargasatuan,
      hargadiscount: 0,
      total: parseFloat(item.value.jumlah) * parseFloat(item.value.hargasatuan)
    }
    sourceData.value.push(datas)
  }
  console.log(sourceData.value)
  if (sourceData.value.length > 0) {
    clear()
  }
  modalInput.value = false
  count()
}

const showModal = async (e: any) => {

  if (!item.value.ruanganStok){
    H.alert('error','Ruangan Stok Belum Dipilih')
    return 
  }

  if (e.no) {
    loadingBtnEdit.value = true
    let filter = { query: e.namaproduk }
    await produk(filter)
    d_Produk.value.forEach(element => {
      item.value.produk = element
      getSatuan(element)
    });
    item.value.satuan = { label: e.satuanstandar, value: e.satuanstandarfk }
    item.value.no = e.no
    item.value.konversi = e.nilaikonversi
    item.value.stok = e.jmlstok
    item.value.hargasatuan = e.hargasatuan
    item.value.jumlah = e.jumlah
    
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
      sourceData.value.forEach((element: any, i: any) => {
        if (element.no == e.no) {
          sourceData.value.splice(i, 1)
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
  delete item.value.produk
  delete item.value.satuan
  delete item.value.konversi
  delete item.value.stok
  delete item.value.hargasatuan
  delete item.value.jumlah
}

const listCombo = async (filter: any) => {
  await useApi().get('iprs/combo').then((response) => {
    d_RuanganStok.value = response.ruangan.map((e: any) => {
      return { label: e.namaruangan, value: e.id }
    })
    item.value.ruanganStok = d_RuanganStok.value[0]
  })
  
  item.value.jeniskirim = {label : 'Transfer' , value : 2}
}

const fetchRuangan = async (filter: any) => {
  const response = await useApi().get(`/emr/dropdown/ruangan_m?select=id,namaruangan&param_search=namaruangan&query=${filter.query}&limit=10`)
  d_Ruangan.value = response
}

const produk = async (e: any) => {
  let search = e.query ? `&namaproduk=${e.query}` : ''
  await useApi().get(`iprs/get-daftar-produk?ruanganfk=${item.value.ruanganStok.value}${search}`).then((response) => {
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
      getProdukListStok(e.id)
      item.value.stok = e.stok
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
    item.value.kdproduk = response.detail[0].kdproduk
    loadHarga.value = false
  })

}

const getProdukListStok = async (e: any) => {

  let namaproduk
  let isDonasi = item.checkisDonasi ? `&isdonasi=${item.checkisDonasi}` : ''
  await useApi().get(`farmasi/get-stok-produk-by-ruangan?produkfk=${e}${isDonasi}`).then((response) => {
    response.forEach((element: any, i: any) => {
      element.no = i + 1
      namaproduk = element.namaproduk
    });
    dataSourceStokProduk.value = response
    infoStok.value = `List Stok Produk ${namaproduk} Per Ruangan`
  })

}


const count = () => {

  let totalsub = 0
  let discount = 0
  let ppn = 0
  let total = 0
  sourceData.value.forEach((element: any) => {
    totalsub = totalsub + parseFloat(element.total)
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
  router.push({ name: 'module-logistik-daftar-purchase-order' })
}

const back = () => {
  window.history.back()
}

listCombo()

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
