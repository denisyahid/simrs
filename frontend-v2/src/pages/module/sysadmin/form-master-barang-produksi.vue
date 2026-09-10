<template>
  <ConfirmDialog />
  <div>
    <div class="form-layout is-stacked">
      <div class="form-outer" style="margin-top: 15px">
        <div class="form-header stuck-header">
          <div class="form-header-inner">
            <div class="left">
              <h3>Form Input Master Produksi</h3>
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
          <div style="margin-top:2rem" v-if="sourceProduksiObat.loading">
            <VPlaceloadWrap class="mt-5">
              <VPlaceload height="30px" width="40%" class="mx-2" />
              <VPlaceload height="30px" width="30%" class="mx-2" />
              <VPlaceload height="30px" width="40%" class="mx-2" />
            </VPlaceloadWrap>
            <VPlaceloadWrap class="mt-5">
              <VPlaceload height="30px" width="100%" class="mx-2" />
            </VPlaceloadWrap>
          </div>

          <div v-else>
            <div class="columns is-multiline p-3">
              <div class="column is-6">
                <VField class="is-rounded-select is-autocomplete-select">
                  <VLabel class="required-field">Nama barang produksi</VLabel>
                  <VControl icon="feather:search" fullwidth class="prime-auto-select">
                    <AutoComplete v-model="item.barangProduksi" :suggestions="d_Produk" @complete="produk($event)"
                      @item-select=getSatuanBP(item.barangProduksi) :optionLabel="'namaproduk'" :dropdown="true" :minLength="3" :appendTo="'body'"
                       :loadingIcon="'pi pi-spinner'" :field="'namaproduk'" />
                  </VControl>
                </VField>
              </div>
              <div class="column is-3">
                <VField class="is-rounded-select is-autocomplete-select">
                <VLabel class="required-field">Satuan</VLabel>
                <VControl icon="feather:search" fullwidth class="prime-auto-select">
                  <Dropdown v-model="item.satuanBP" :options="d_SatuanBP" optionLabel="label" style="width: 100%;"
                    :filter="true" @change="getKonversi(item.satuanBP)" />
                </VControl>
              </VField>
              </div>
              <div class="column is-3">
                <VField label="QTY Produksi">
                  <VControl>
                    <input v-model="item.qtyProduksi" type="text" class="input" />
                  </VControl>
                </VField>
              </div>
            </div>

            <div class="columns is-multiline pl-3 pr-3 pt-0">
              <div class="column is-12 pt-0">
                <VField label="Keterangan">
                  <VControl>
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
          <DataTable :value="sourceProduksiObat" :paginator="true" :rows="5" :rowsPerPageOptions="[5, 10, 25]"
            :loading="sourceProduksiObat.loading" class="p-datatable-sm"
            paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
            responsiveLayout="stack" breakpoint="960px" sortMode="multiple"
            currentPageReportTemplate="Showing {first} to {last} of {totalRecords}" showGridlines>
            <Column field="no" header="No"></Column>
            <Column field="namaproduk" header="Deskripsi"></Column>
            <Column field="satuanstandar" header="Satuan"></Column>
            <Column field="jumlah" header="Qty Bahan" />
            <Column :exportable="false" header="Action" style="text-align: center;min-width: 100px;">
              <template #body="slotProps">
                <VIconButton type="button" icon="feather:edit" class="mr-3" color="info" circle outlined raised
                  :loading="slotProps.data.isLoading" v-tooltip.top="'Edit'" @click="showModal(slotProps.data)">
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
            <VField label="Jumlah">
              <VControl>
                <input v-model="item.jumlah" type="text" class="input" placeholder="Jumlah" />
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
  title: 'Form Master Barang Produksi - ' + import.meta.env.VITE_PROJECT,
})
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)
const isLoadingPasien: any = ref(false)

const confirm = useConfirm()
const router = useRouter();
const route = useRoute()

const PRODUKFK = route.query.produkproduksifk as string

const item: any = ref({
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
const d_SatuanBP: any = ref([])
const colors: any = ref(Object.keys(useThemeColors()))
const dataProdukDetail: any = ref([])
const modalInput: any = ref(false)
const loadHarga = ref(false)
const listColor: any = ref([])
const nostruk: any = ref()
const noBukti: any = ref()
let sourceProduksiObat: any = ref([])
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

const saveData = async () => {

  if (!item.value.barangProduksi) {
    H.alert('error', 'Barang Produksi Tidak Boleh Kosong')
    return
  }

  if (!item.value.satuanBP) {
    H.alert('error', 'Satuan Tidak Boleh Kosong')
    return
  }

  if (!item.value.qtyProduksi) {
    H.alert('error', 'QTY Produksi Tidak Boleh Kosong')
    return
  }


  isLoadBtnSave.value = true

  let objSave = {
    produkproduksifk : item.value.barangProduksi.id,
    satuanproduksi: item.value.satuan,
    jumlahproduksi: item.value.qtyProduksi,
    keterangan: item.value.keterangan ?  item.value.keterangan : '',
    paketobat : sourceProduksiObat.value
  }

  // let objSave = {
  //   strukorder: strukOrder,
  //   details: sourceProduksiObat.value
  // }

  await useApi().post('sysadmin/save-produksi-obat', objSave).then((response) => {
    // goToPageDaftar()
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
    sourceProduksiObat.value.forEach((element: any, i: any) => {
      if (element.no == e.no) {
        datas.no = element.no,
          datas.namaproduk = e.produk.namaproduk,
          datas.produk = e.produk,
          datas.produkfk = e.produk.id,
          datas.satuanstandar = e.satuan.label,
          datas.satuanstandarfk = e.satuan.value,
          datas.satuanviewfk = e.satuan.value,
          datas.satuanview = e.satuan.label,
          datas.jumlah = e.jumlah,
          datas.jumlahobat = e.jumlah,
          datas.keterangan = e.keterangan,
          sourceProduksiObat.value[i] = datas
      }
    });
  } else {
    datas = {
      no: sourceProduksiObat.value.length == 0 ? 1 : sourceProduksiObat.value.length + 1,
      produkfk: e.produk.id,
      namaproduk: e.produk.namaproduk,
      satuanstandar: e.satuan.label,
      satuanstandarfk: e.satuan.value,
      satuanviewfk: e.satuan.value,
      satuanview: e.satuan.label,
      jumlah: e.jumlah,
      jumlahobat: e.jumlah,
      keterangan: item.value.keterangan ? item.value.keterangan : null,
    }
    sourceProduksiObat.value.push(datas)
  }
  if (sourceProduksiObat.value.length > 0) {
    clear()
  }
  modalInput.value = false
  // count()
}

const showModal = async (e: any) => {
  if (e.no) {
    e.isLoading = true
    loadingBtnEdit.value = true
    let filter = { query: e.namaproduk }
    await produk(filter)
    d_Produk.value.forEach(element => {
      item.value.produk = element
      getSatuan(element)
    });
    item.value.no = e.no
    item.value.jumlah = e.jumlah

    modalInput.value = true
    loadingBtnEdit.value = false
    e.isLoading = false
  } else {
    e.isLoading = false
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
      sourceProduksiObat.value.forEach((element: any, i: any) => {
        if (element.no == e.no) {
          sourceProduksiObat.value.splice(i, 1)
        }
        element.no - 1
      })
      clear()
    },
    reject: () => { },
  })
}

const clear = () => {
  delete item.value.no
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
  sourceProduksiObat.value.forEach((element: any, i: any) => {
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
      sourceProduksiObat.value[i] = data
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
      if (e.konversisatuan.length != 0) {
        d_Satuan.value = e.konversisatuan.map((element: any) => {
          return { label: element.satuanstandar, value: e.ssid ,nilaikonversi : element.nilaikonversi }
        })
        d_Satuan.value.forEach((data: any) => {
          if (data.value == e.ssid) {
            item.value.satuan = data
          }
        });
      } else {
        d_Satuan.value = [{ label: e.satuanstandar, value: e.ssid, nilaikonversi : e.nilaikonversi }]
        item.value.konversi = e.konversisatuan.length == 0 ? 1 : e.konversisatuan
        d_Satuan.value.forEach((data: any) => {
          if (data.value == e.ssid) {
            item.value.satuan = data
          }
        })
      }
      item.value.konversi = 1
      // getHargaProduk(e.id)
    }
}

const getSatuanBP = (e: any) => {
  if (e.id != undefined) {
      if (e.konversisatuan.length != 0) {
        d_SatuanBP.value = e.konversisatuan.map((element: any) => {
          return { label: element.satuanstandar, value: e.ssid ,nilaikonversi : element.nilaikonversi }
        })
        d_SatuanBP.value.forEach((data: any) => {
          if (data.value == e.ssid) {
            item.value.satuanBP = data
          }
        });
      } else {
        d_SatuanBP.value = [{ label: e.satuanstandar, value: e.ssid, nilaikonversi : e.nilaikonversi }]
        item.value.konversi = e.konversisatuan.length == 0 ? 1 : e.konversisatuan
        d_SatuanBP.value.forEach((data: any) => {
          if (data.value == e.ssid) {
            item.value.satuanBP = data
          }
        })
      }
      item.value.konversi = 1
      // getHargaProduk(e.id)
    }
}

const getKonversi = (e: any) => {
  item.value.konversi = item.value.satuan &&  item.value.satuan.nilaiKonversi != undefined ? item.value.satuan.nilaiKonversi : 1
}

const getKonversiBP = (e: any) => {
  item.value.konversi = item.value.satuanBP &&  item.value.satuanBP.nilaiKonversi != undefined ? item.value.satuanBP.nilaiKonversi : 1
}

// const getHargaProduk = async (e: any) => {
//   loadHarga.value = true
//   await useApi().get(`logistik/get-harga-produk?produkfk=${e}`).then((response: any) => {
//     item.value.hargasatuan = H.formatRupiah(Math.round(response.detail[0].harga).toString(),'')
//   })
//   loadHarga.value = false
// }

// const count = () => {

//   let totalsub = 0
//   let discount = 0
//   let ppn = 0
//   let total = 0
//   sourceProduksiObat.value.forEach((element: any) => {
//     totalsub = totalsub + H.unFormatRupiah(parseFloat(element.total))
//     discount = element.hargadiscount === '' ? discount : discount + H.unFormatRupiah(parseFloat(element.hargadiscount))
//     ppn = element.ppn === '' ? ppn : ppn + H.unFormatRupiah(parseFloat(element.ppn))
//     total = totalsub - discount + ppn
//   })
//   item.value.totalsub = totalsub
//   item.value.discount = discount
//   item.value.ppnTotal = ppn
//   item.value.totalall = total
// }

// const fetchUnitTujuan = async (filter: any) => {
//   await useApi().get(
//     `emr/dropdown/ruangan_m?select=id,namaruangan&param_search=namaruangan&query=${filter.query}&limit=10`
//   ).then((response) => {
//     d_unitTujuan.value = response
//   })
// }

const goToPageDaftar = () => {
  router.push({ name: 'module-logistik-daftar-purchase-request' })
}

const isEditPO = async () => {
  if (PRODUKFK) {
    sourceProduksiObat.value.loading = true
    let header
    let detail
    await useApi().get(`sysadmin/master-produksi-obat?produkProduksiId=${PRODUKFK}`).then((response) => {
    header = response.data[0]
    header.details.forEach((element:any,i:any) => {element.no = i + 1});  
    detail = header.details 
    })

    await produk({query: header.namaprodukproduksi})
      d_Produk.value.forEach(element => {
      item.value.barangProduksi = element
      getSatuanBP(element)
    })
    item.value.qtyProduksi = header.qtyhasil
    item.value.keterangan = header.keteranganlainnya
    sourceProduksiObat.value = detail
    sourceProduksiObat.value.loading = false
  }
}

const back = () => {
  window.history.back()
}

// listData()
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

