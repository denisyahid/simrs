<template>
  <ConfirmDialog />
  <div>
    <div class="form-layout is-stacked">
      <div class="form-outer" style="margin-top: 15px">
        <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
          <div class="form-header-inner">
            <div class="left">
              <h3>Form Produk Kadaluarsa</h3>
            </div>
            <div class="right">
              <div class="buttons">
                <VButton icon="lnir lnir-arrow-left rem-100" light dark-outlined RouterLink
                  :to="{ name: 'module-logistik-daftar-barang-kadaluarsa' }">
                  Kembali
                </VButton>
                <div>
                  <VButton type="button" :loading="isLoadBtnSave" rounded outlined color="primary" raised
                    icon="feather:save" @click="saveKadaluarsa()">
                    Simpan
                  </VButton>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="form-body p-4">
          <div class="columns is-multiline p-3">
            <div class="column is-3">
              <VDatePicker v-model="item.tglKirim" color="green" trim-weeks :input="'YYYY-MM-DD'" mode="date">
                <template #default="{ inputValue, inputEvents }">
                  <VField label="Tanggal Kirim">
                    <VControl icon="feather:calendar">
                      <VInput type="text" placeholder="Select a date" :value="inputValue" v-on="inputEvents"
                        class="is-rounded_Z" />
                    </VControl>
                  </VField>
                </template>
              </VDatePicker>
            </div>
            <div class="column is-3">
              <VField class="is-rounded-select is-autocomplete-select">
                <VLabel class="required-field">Ruangan</VLabel>
                <VControl icon="feather:search" fullwidth class="prime-auto-select">
                  <Dropdown v-model="item.ruangan" :options="d_Ruangan" optionLabel="label" placeholder="Pilih Ruangan"
                    @change="getProduk(item.ruangan)" style="width: 100%;" :filter="true" />
                </VControl>
              </VField>
            </div>
            <div class="column is-3">
              <VField class="is-rounded-select is-autocomplete-select">
                <VLabel class="required-field">Pegawai</VLabel>
                <VControl icon="feather:search" fullwidth class="prime-auto-select">
                  <Dropdown v-model="item.pegawai" :options="d_Pegawai" optionLabel="label" placeholder="Pilih Pegawai"
                    :disabled="d_Pegawai.values.length > 2 ? false : true" style="width: 100%;" :filter="true" />
                </VControl>
              </VField>
            </div>
            <div class="column is-3">
              <VField>
                <VLabel>Keterangan</VLabel>
                <VControl>
                  <input v-model="item.keterangan" type="text" class="input" />
                </VControl>
              </VField>
            </div>
          </div>
        </div>
      </div>

      <div class="column is-12 p-0 mt-5">
        <VCard>
          <div class="column is-2 p-0 pb-2" style="margin-left: auto">
            <VButton type="button" icon="feather:x-circle" @click="modalTambah(item)"
              class="is-fullwidth is-outlined is-primary mt-4" rounded raised>
              Tambah
            </VButton>
          </div>
          <DataTable :value="dataSourceKadaluarsa" :paginator="true" :rows="5" :rowsPerPageOptions="[5, 10, 25]"
            :loading="dataSourceKadaluarsa.loading" class="p-datatable-sm"
            paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
            responsiveLayout="stack" breakpoint="960px" sortMode="multiple"
            currentPageReportTemplate="Showing {first} to {last} of {totalRecords}" showGridlines>
            <Column field="no" header="No"></Column>
            <Column field="namaproduk" header="Nama Produk"></Column>
            <Column field="kdproduk" header="Kd Produk"></Column>
            <Column field="jumlah" header="Qty"></Column>
            <Column field="satuanstandar" header="Satuan"></Column>
            <Column field="jmlstok" header="Stok"></Column>
            <Column :exportable="false" header="Action" width="10%" style="text-align: center">
              <template #body="slotProps">
                <VButtons>
                  <VIconButton color="success" icon="pi pi-pencil"  v-tooltip.top="'Edit'" @click="editData(slotProps.data)"/>
                  <VIconButton color="danger" light icon="fas fa-trash"  v-tooltip.top="'Hapus'"  @click="hapus(slotProps.data)"/>
                </VButtons>
              </template>
            </Column>
          </DataTable>
        </VCard>
      </div>
      <!--
      <div class="content">
        <div class="is-divider" />
      </div> -->
    </div>
  </div>

  <Dialog v-model:visible="modalInput" modal header="Detail Produk Kadaluarsa" :style="{ width: '25vw' }">
    <form class="modal-form">
      <div class="column">
        <VField class="is-rounded-select is-autocomplete-select">
          <VLabel class="required-field">Produk</VLabel>
          <VControl icon="feather:search" fullwidth class="prime-auto-select">
            <Dropdown v-model="item.produkfk" :options="d_Produk" optionLabel="label" placeholder="Pilih Produk"
              @change="conversiSatuan(item.produkfk)" style="width: 100%;" :filter="true" />
          </VControl>
        </VField>
      </div>
      <div class="column">
        <div class="columns is-multiline">
          <div class="column">
            <VField class="is-rounded-select is-autocomplete-select">
              <VLabel class="required-field">Satuan</VLabel>
              <VControl icon="feather:search" fullwidth class="prime-auto-select">
                <Dropdown v-model="item.satuan" :options="d_Satuan" optionLabel="label"
                  @change="getNilaiKonversi(item.satuan)" style="width: 100%;" :filter="true" />
              </VControl>
            </VField>
          </div>
          <div class="column">
            <VField>
              <VLabel>Konversi</VLabel>
              <VControl>
                <input v-model="item.konversi" type="text" class="input" disabled />
              </VControl>
            </VField>
          </div>
          <div class="column">
            <VField>
              <VLabel>Stok</VLabel>
              <VControl>
                <input v-model="item.stok" type="text" class="input" disabled />
              </VControl>
            </VField>
          </div>
          <div class="column">
            <VField>
              <VLabel>Jumalah</VLabel>
              <VControl>
                <input v-model="item.jumlah" type="text" class="input" />
              </VControl>
            </VField>
          </div>
        </div>
      </div>
    </form>

    <template #footer>
      <VButton raised class="mr-3" @click="clear()">Batal</VButton>
      <VButton v-if="item.no" icon="feather:plus" @click="updateData(item)" color="primary" raised>Update</VButton>
      <VButton v-else icon="feather:plus" @click="tambahKadaluarsa(item)" color="primary" raised>Simpan</VButton>
    </template>
  </Dialog>
</template>
<script setup lang="ts">
import { useWindowScroll } from '@vueuse/core'
import { useApi } from '/@src/composable/useApi'
import { ref, computed, watch } from 'vue'
import { useRouter, useRoute } from 'vue-router'
import { useHead } from '@vueuse/head'
import { useToaster } from '/@src/composable/toaster'
import AutoComplete from 'primevue/autocomplete';
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useThemeColors } from '/@src/composable/useThemeColors'
import { useConfirm } from 'primevue/useconfirm'
import ConfirmDialog from 'primevue/confirmdialog'
import moment from 'moment'
import Checkbox from 'primevue/checkbox';
import * as H from '/@src/utils/appHelper'
import Dialog from 'primevue/dialog';
import DataTable from 'primevue/datatable'
import Dropdown from 'primevue/dropdown'
import Column from 'primevue/column'
useHead({
  title: 'Penerimaan Barang Supplier - ' + import.meta.env.VITE_PROJECT,
})
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)

const isLoadingPasien: any = ref(false)
const confirm = useConfirm()
const router = useRouter();
const route = useRoute()

const item: any = ref({})

const d_Pegawai = ref([])
const d_Ruangan = ref([])
const d_suplier = ref([])
const d_Produk = ref([])
const d_Satuan: any = ref([])
const colors: any = ref(Object.keys(useThemeColors()))
const dataProdukDetail: any = ref([])
const modalInput = ref(false)
const listColor: any = ref([])
const nostruk: any = ref()
const noBukti: any = ref()
let dataSourceKadaluarsa: any = ref([])
let isLoadingBtn: any = ref(false)
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

// const saveDataPenerimaan = async (e: any) => {

//   if (!item.value.tglTerima) {
//     useToaster().error('Tanggal Terima Tidak Boleh Kosong')
//     return
//   }
//   if (!item.value.gudang) {
//     useToaster().error('Gudang Tidak Boleh Kosong')
//     return
//   }
//   if (!item.value.penerimaan) {
//     useToaster().error('Pegawai Penerima Tidak Boleh Kosong')
//     return
//   }
//   if (!item.value.kelompokbarang) {
//     useToaster().error('kelompok Barang Tidak Boleh Kosong')
//     return
//   }
//   if (!item.value.sumberdana) {
//     useToaster().error('Sumber Dana Tidak Boleh Kosong')
//     return
//   }

//   if (item.value.sumberdana == 7) {
//     if (!item.value.noBuktiKK) {
//       useToaster().error('No Bukti Tidak Boleh Kosong')
//       return
//     }
//     if (!item.value.tanggalKK) {
//       useToaster().error('Tanggal Kas Kecil Tidak Boleh Kosong')
//       return
//     }
//     if (!item.value.ruanganKK) {
//       useToaster().error('Ruangan Kas Kecil Tidak Boleh Kosong')
//       return
//     }
//     if (!item.value.pegawaiKK) {
//       useToaster().error('pegawai Kas Kecil Tidak Boleh Kosong')
//       return
//     }
//   }

//   if (!item.value.tglFaktur) {
//     useToaster().error('Tanggal Faktur Tidak Boleh Kosong')
//     return
//   }
//   if (!item.value.noFaktur) {
//     useToaster().error('No Faktur Tidak Boleh Kosong')
//     return
//   }
//   if (!item.value.supplier) {
//     useToaster().error('Supplier Tidak Boleh Kosong')
//     return
//   }

//   if (dataSourceKadaluarsa.value.length == 0) {
//     useToaster().error('Data Yang Disimpan Tidak Tersedia')
//     return
//   }

//   isLoadBtnSave.value = true
//   const objSave = {
//     details: dataSourceKadaluarsa.value,
//     struk: {
//       nosppb: '',
//       nostruk: nostruk.value == null ? '' : nostruk.value,
//       norec: route.query.norec ? route.query.norec : '',
//       norecsppb: '',
//       norecrealisasi: item.value.norecrealisasi ? item.value.norecrealisasi : '',
//       objectmataanggaranfk: '',
//       norecOrder: null,
//       jenissusulan: 'Medis',
//       jenissusulanfk: 2,
//       noorder: item.value.nosppb ? item.value.nosppb : '-',
//       noBuktiKK: item.value.noBuktiKK ? item.value.noBuktiKK : '',
//       pegawaiKK: item.value.pegawaiKK ? item.value.pegawaiKK.value.id : null,
//       ruanganfkKK: item.value.ruanganKK ? item.value.ruanganKK.value : '',
//       ruanganfk: item.value.gudang.value,
//       asalproduk: item.value.sumberdana.value,
//       pegawaikomit: item.value.pembuatkomit ? item.value.pembuatkomit.namalengkap : '',
//       namapegawaipenerima: item.value.penerimaan.value.namalengkap,
//       namarekanan: item.value.supplier.label,
//       namapengadaan: item.value.namapengadaan ? item.value.namapengadaan : '',
//       ketTerima: item.value.ketTerima ? item.value.ketTerima : '',
//       nokontrak: item.value.nokontrak ? item.value.nokontrak : '',
//       pegawaimenerimafk: item.value.penerimaan.value.id,
//       pegawaikomitfk: item.value.pembuatkomit ? item.value.pembuatkomit.id : null,
//       rekananfk: item.value.supplier.value.id,
//       nofaktur: item.value.noFaktur,
//       kelompokprodukfk: item.value.kelompokbarang.value,
//       nousulan: item.value.nousulan ? item.value.nousulan : '',
//       qtyproduk: dataSourceKadaluarsa.value.length,
//       tglSppb: null,
//       tglKK: item.value.tanggalKK
//         ? H.formatDate(item.value.tanggalKK, 'YYYY-MM-DDTHH:mm:ss')
//         : null,
//       tglTempo: item.value.tglTempo
//         ? H.formatDate(item.value.tglTempo, 'YYYY-MM-DDTHH:mm:ss')
//         : null,
//       tglfaktur: H.formatDate(item.value.tglFaktur, 'YYYY-MM-DDTHH:mm:ss'),
//       tglstruk: H.formatDate(item.value.tglTerima, 'YYYY-MM-DDTHH:mm:ss'),
//       tglorder: item.value.tanggalPo ? H.formatDate(item.value.tanggalPo, 'YYYY-MM-DDTHH:mm:ss') : null,
//       tglkontrak: H.formatDate(item.value.tanggalPo, 'YYYY-MM-DDTHH:mm:ss'),
//       tglrealisasi: H.formatDate(item.value.tglFaktur, 'YYYY-MM-DDTHH:mm:ss'),
//       totaldiscount: item.value.discount ? item.value.discount : 0,
//       totalhargasatuan: item.value.totalsub,
//       totalharusdibayar: item.value.totalall,
//       totalppn: item.value.ppnTotal ? item.value.ppnTotal : 0,
//     },
//   }
//   console.log(objSave)
//   await useApi()
//     .post('/logistik/penerimaan-barang/save-penerimaan-suplier', objSave)
//     .then((response) => {
//       isLoadBtnSave.value = false
//     })
//     .catch((err) => {
//       isLoadBtnSave.value = false
//       console.log(err)
//     })
// }

const saveKadaluarsa = async () => {
  const objSave = {
    details: dataSourceKadaluarsa.value,
    strukkirim: {
      norec: "",
      keterangan: "Barang Kadaluarsa",
      objectpegawaipengirimfk: item.value.pegawai.id,
      objectruanganfk: item.value.ruangan.value,
      tglkadaluarsa: H.formatDate(item.value.tglKirim, 'YYYY-MM-DD'),
    },
  }
  await useApi()
    .post('/dashboard/logistik/save-barang-kadaluarsa', objSave)
    .then((response) => {
      isLoadBtnSave.value = false
    })
    .catch((err) => {
      isLoadBtnSave.value = false
      console.log(err)
    })

  console.log(objSave)
}

const tambahKadaluarsa = (e: any) => {
  if(!e.produkfk){
    H.alert("error","Produk Tidak Boleh Kosong")
    return
  }
  if(!e.satuan){
    H.alert("error","Satuan Tidak Boleh Kosong")
    return
  }

  let datas = {
    no: dataSourceKadaluarsa.value.length + 1,
    asalproduk: "as@epic",
    asalprodukfk: 1,
    hargadiscount: 0,
    hargajual: 0,
    harganetto: 0,
    hargasatuan: 0,
    jmlstok: e.stok,
    jumlah: e.jumlah,
    kdproduk: e.produkfk.kdproduk,
    namaproduk: e.produkfk.label,
    nilaikonversi: e.konversi,
    nostrukterimafk: "as@epic",
    produkfk: e.produkfk.value,
    qtyorder: 0,
    ruanganfk: e.ruangan.value,
    satuanstandar: e.satuan.label,
    satuanstandarfk: e.satuan.value,
    stock: e.stok,
    total: 1,
  }
  dataSourceKadaluarsa.value.push(datas)
  clear()
}

const editData = (e:any)=>{
  console.log(e)
  modalInput.value = true
  item.value.no = e.no
  d_Produk.value.forEach((element:any)=>{
    if(e.produkfk == element.value){
      item.value.produkfk = element
      item.value.satuan = element
    }
  })
  item.value.konversi =  e.nilaikonversi
  item.value.stok =  e.jmlstok
  item.value.jumlah =  e.jumlah
}

const updateData = (e: any) => {
    let data: any = {}
    dataSourceKadaluarsa.value.forEach((element:any,i:any) => {
      if(element.no == e.no){
        data.no = element.no
        data.asalproduk =  "as@epic",
        data.asalprodukfk = 1,
        data.hargadiscount = 0,
        data.hargajual =  0,
        data.harganetto = 0,
        data.hargasatuan = 0,
        data.jmlstok = e.stok,
        data.jumlah = e.jumlah,
        data.kdproduk = e.produkfk.kdproduk,
        data.namaproduk = e.produkfk.label,
        data.nilaikonversi = e.konversi,
        data.nostrukterimafk = "as@epic",
        data.produkfk = e.produkfk.value,
        data.qtyorder = 0,
        data.ruanganfk = e.ruangan.value,
        data.satuanstandar = e.satuan.label,
        data.satuanstandarfk = e.satuan.value,
        data.stock = e.stok,
        data.total = 1
        dataSourceKadaluarsa.value[i] = data
      }
    });
    clear()
  }

const hapus = (e:any)=>{
  dataSourceKadaluarsa.value.forEach((element:any,i:any) => {
    if(element.no == e.no){
      dataSourceKadaluarsa.value.splice(i, 1)
    }
  });
}

const modalTambah = () => {
  if(!item.value.ruangan){
    H.alert("error","Ruangan Wajib Dipilih dulu")
    return
  }
  modalInput.value = true
}

const getCombo = () => {
  useApi().get('logistik/penerimaan-barang/get-data-combo').then((response) => {
    d_Pegawai.value = response.pegawai.map((e: any) => {
      return { label: e.namalengkap, value: e }
    })

    if (d_Pegawai.value.length == 1) {
      d_Pegawai.value.forEach((element: any) => {
        item.value.pegawai = element
        return
      })
    }
    d_Ruangan.value = response.ruangan.map((e: any) => {
      return { label: e.namaruangan, value: e.id }
    })
  })
}


const getProduk = async (element: any) => {
  delete item.value.konversi
  await useApi().get(`dashboard/logistik/get-combo-produk?objectruangan=` + element.value).then((response) => {
    d_Produk.value = response.map((e: any) => {
      return { label: e.namaproduk, value: e.id, more: e, kdproduk: e.kdproduk }
    })
  })
}

const conversiSatuan = async (e: any) => {
  delete item.value.konversi
  delete item.value.satuan
  console.log(e)
  d_Satuan.value = ''
  let more = e.more
  if (more.konversisatuan.length > 1) {
    d_Satuan.value = more.konversisatuan.map((e: any) => {
      return { label: e.satuanstandar, value: e.ssid, nilai: e.nilaikonversi }
    })
  } else {
    d_Satuan.value = [{ label: more.satuanstandar, value: more.ssid, nilai: 1 }]
  }

  item.value.stok = more.qtyproduk
}

const getNilaiKonversi = (e: any) => {
  item.value.konversi = e.nilai
}

const clear = () => {
  modalInput.value = false
  delete item.value.produkfk
  delete item.value.satuan
  delete item.value.konversi
  delete item.value.jumlah
  delete item.value.stok
}


// const fetchRuangan = async (filter: any) => {
//   await useApi().get(
//     `emr/dropdown/ruangan_m?select=id,namaruangan&param_search=namaruangan&query=${filter.query}&limit=10`
//   ).then((response) => {
//     d_Ruangan.value = response
//   })
// }

getCombo()

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

