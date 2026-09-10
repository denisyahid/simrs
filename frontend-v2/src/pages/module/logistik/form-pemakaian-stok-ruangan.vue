<template>
  <ConfirmDialog />
  <div>
    <div class="form-layout is-stacked">
      <div class="form-outer" style="margin-top: 15px">
        <div class="form-header stuck-header">
          <div class="form-header-inner">
            <div class="left">
              <h3>Pemakaian Stok Ruangan</h3>
            </div>
            <div class="right">
              <div class="buttons">
                <VButton icon="lnir lnir-arrow-left rem-100" @click="back()" light dark-outlined RouterLink>
                  Kembali
                </VButton>
                <div>
                  <VButton type="button" rounded outlined color="primary"  raised icon="feather:save" @click="dialogConfirm('','simpan')"
                    :loading="isLoadBtnSave">
                    Simpan
                  </VButton>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="form-body p-4">
          <div style="margin-top:2rem" v-if="dataSource.loading">
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
            <div class="columns is-multiline pt-3 pr-3 pl-3 pb-0">
              <div class="column is-3">
                <VDatePicker v-model="item.tgl" color="green" trim-weeks mode="datetime" :max-date="new Date()">
                  <template #default="{ inputValue, inputEvents }">
                    <VField>
                      <VLabel class="required-field">Tanggal</VLabel>
                      <VControl icon="feather:calendar">
                        <VInput type="text" placeholder="Select a date" :value="inputValue" v-on="inputEvents" />
                      </VControl>
                    </VField>
                  </template>
                </VDatePicker>
              </div>

              <div class="column is-4">
                <VField class="is-rounded-select is-autocomplete-select">
                    <VLabel class="required-field">Ruangan</VLabel>
                     <VControl icon="feather:search" class="prime-auto-select" :class="dataSource.length >= 1 ? 'is-bold' : ''">
                         <AutoComplete v-model="item.ruanganfk" :suggestions="d_Ruangan" :disabled="dataSource.length >= 1"
                            @complete="fetchRuangan($event)" :optionLabel="'label'" :dropdown="true" :class="dataSource.length >= 1 ? 'is-bold' : ''"
                            :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                            placeholder="Pilih Ruangan" />
                      </VControl>
                </VField>
              </div>

              <div class="column is-5">
                <VField class="is-rounded-select is-autocomplete-select">
                  <VLabel class="required-field">Penanggung Jawab</VLabel>
                  <VControl icon="feather:search" fullwidth class="prime-auto-select">
                    <Dropdown v-model="item.penanggungJawab" :options="d_PenanggungJawab" optionLabel="label"
                      placeholder="Pilih Penanggung Jawab" style="width: 100%;" :filter="true" />
                  </VControl>
                </VField>
              </div>
            </div>

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

      <div class="column is-12 p-0 mt-5">
        <VCard>
          <div class="column is-2 p-0 pb-2" style="margin-left: auto">
            <VButton type="button" icon="fas fa-plus-circle" @click="showModal(item)"
              class="is-fullwidth is-outlined is-primary mt-4" rounded raised>
              Tambah
            </VButton>
          </div>
          <DataTable :value="dataSource" :paginator="true" :rows="5" :rowsPerPageOptions="[5, 10, 25]"
            :loading="dataSource.loading" class="p-datatable-sm"
            paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
            responsiveLayout="stack" breakpoint="960px" sortMode="multiple"
            currentPageReportTemplate="Showing {first} to {last} of {totalRecords}" showGridlines>
            <Column field="no" header="No"></Column>
            <Column field="namaproduk" header="Nama Produk"></Column>
            <Column field="satuanstandar" header="Satuan" />
            <Column field="jumlah" header="Qty" />
            <Column field="hargasatuan" header="Harga Satuan"  style="text-align:right">
               <template #body="slotProps">
                {{ H.formatRupiah(H.roundToDecimal(parseFloat(slotProps.data.hargasatuan), 2), '') }}
              </template>
            </Column>
            <!-- <Column field="keterangan" header="Keterangan" /> -->
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
                  v-tooltip.top="'Hapus'" @click="dialogConfirm(slotProps.data,'hapus')">
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

      <div class="columns is-multiline" style="justify-content: right;">
        <div class="column is-3">
          <VCardCustom :style="'padding:5px 25px'">
            <div class="label-status primary">
              <i aria-hidden="true" class="fas fa-circle"></i>
                <span class="ml-1">TOTAL</span>
              </div>
                <!-- <small>{{ item.subtotal }}</small> -->
              <small class="text-bold-custom h-100">{{H.formatRp(item.totalAll, 'Rp.')}}</small>
          </VCardCustom>
        </div>
      </div>
    </div>
  </div>


  <VModal is="form" :open="modalInput" title="Form Input Produk" size="large" actions="right"
    @close="modalInput = false, clear()">
    <template #content>

      <div class="columns is-multiline">
          <div class="column is-5">
            <VField class="is-rounded-select is-autocomplete-select">
              <VLabel class="required-field">Produk</VLabel>
              <VControl icon="feather:search" fullwidth class="prime-auto-select">
                <AutoComplete v-model="item.produk" :suggestions="d_Produk" @complete="fetchProduk($event)"
                  :optionLabel="'namaproduk'" :dropdown="true" :minLength="3" :appendTo="'body'"
                  @item-select="changeProduk(item.produk)"  :loadingIcon="'pi pi-spinner'" :field="'namaproduk'" />
              </VControl>
            </VField>
          </div>
          <div class="column is-3">
            <VField class="is-rounded-select is-autocomplete-select">
              <VLabel class="required-field">Satuan</VLabel>
              <VControl icon="feather:search" fullwidth class="prime-auto-select">
                <Dropdown v-model="item.satuan" :options="d_Satuan" optionLabel="label" style="width: 100%;"
                  :filter="true" @change="getNilaiKon(item.satuan)" />
              </VControl>
            </VField>
          </div>

          <div class="column is-2">
            <VField label="Konversi">
              <VControl>
                <input v-model="item.konversi" type="text" class="input" placeholder="Konversi" disabled style="font-weight:bold"  />
              </VControl>
            </VField>
          </div>
          <div class="column is-2">
            <VField label="Stok">
              <VControl :loading="loadHarga">
                <input v-model="item.stok" type="text" class="input" placeholder="stok" disabled style="font-weight:bold"  />
              </VControl>
            </VField>
          </div>
      </div>

      <div class="columns is-multiline">
          <div class="column is-2">
            <VField label="Jumlah">
              <VControl>
                <input v-model="item.jumlah" type="number" class="input" placeholder="Jumlah" />
              </VControl>
            </VField>
          </div>
          <div class="column is-4">
            <VField label="Harga">
              <VControl :loading="loadHarga">
                <input v-model="item.hargasatuan" type="text" disabled class="input" placeholder="Harga" style="font-weight:bold" />
              </VControl>
            </VField>
          </div>
          <div class="column is-4">
            <VField label="Sub Total">
              <VControl>
                <input v-model="item.subTotal" type="text" disabled class="input" placeholder="Sub Total" style="font-weight:bold"  />
              </VControl>
            </VField>
          </div>
        </div>
      <div class="column p-0" style="display:none">
        <VField label="Spesifikasi / Keterangan">
          <VControl>
            <input v-model="item.spesifikasi" type="text" class="input" />
          </VControl>
        </VField>
      </div>

      <div class="column is-12" v-if="dataSource.length >= 1">
        <div class="content">
          <div class="is-divider" data-content="List Produk Ditambahkan" />
        </div>
      </div>
      <div class="column is-12" v-if="dataSource.length >= 1">
       <DataTable :value="dataSource" :paginator="true" :rows="5" :rowsPerPageOptions="[5, 10, 25]"
            :loading="dataSource.loading" class="p-datatable-sm"
            paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
            responsiveLayout="stack" breakpoint="960px" sortMode="multiple"
            currentPageReportTemplate="Showing {first} to {last} of {totalRecords}" showGridlines>
            <Column field="no" header="No"></Column>
            <Column field="namaproduk" header="Nama Produk"></Column>
            <Column field="satuanstandar" header="Satuan" />
            <Column field="jumlah" header="Qty" />
            <Column field="hargasatuan" header="Harga Satuan"  style="text-align:right">
               <template #body="slotProps">
                {{ H.formatRupiah(H.roundToDecimal(parseFloat(slotProps.data.hargasatuan), 2), '') }}
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
                  v-tooltip.top="'Hapus'" @click="dialogConfirm(slotProps.data,'hapus')">
                </VIconButton>
              </template>
            </Column>
          </DataTable>
      </div>

      <div class="column is-12" v-if="dataSourceStokProduk.length >= 1">
        <div class="content">
          <div class="is-divider" :data-content="infoStok" />
        </div>
      </div>

      <div class="column is-12" v-if="dataSourceStokProduk.length >= 1">
        <DataTable :value="dataSourceStokProduk" :paginator="true" :rows="5"
          :rowsPerPageOptions="[5, 10, 25]"
          paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
          responsiveLayout="stack" breakpoint="960px" sortMode="multiple"
          currentPageReportTemplate="Showing {first} to {last} of {totalRecords}" showGridlines>
          <Column field="no" header="No"></Column>
          <Column field="namaruangan" header="ruangan"></Column>
          <Column field="stok" header="Stok"></Column>
        </DataTable>
      </div>

    </template>
    <template #action>
      <VButton color="primary" raised @click="addItem(item)" :disabled="btnDisabled">Tambah</VButton>
      <VButton color="info" icon="fas fa-save" outlined @click="dialogConfirm('','simpan')" v-if="dataSource.length >= 1">Simpan</VButton>
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

const item: any = ref({
  tgl: new Date(),
  tglKebutuhan: new Date(),
  norec : route.query.norecOrder
})

const hrg1: any = ref(0)
const hrgsdk: any = ref(0)
const d_Pegawai = ref([])
const d_PenanggungJawab = ref([])
const d_Produk = ref([])
const d_Satuan: any = ref([])
const infoStok: any = ref('List Informasi Stok')
const dataProdukDetail: any = ref([])
const dataSourceStokProduk : any = ref([])
const modalInput: any = ref(false)
const norecTerima: any = ref('')
const norecSPD: any = ref('')
const btnDisabled = ref(false)
const loadHarga = ref(false)
const d_Ruangan: any = ref([])
let dataSource: any = ref([])
let loadingBtnEdit: any = ref(false)
let isLoadBtnSave: any = ref(false)
let isLoadProduk: any = ref(false)

const { y } = useWindowScroll()
const isStuck = computed(() => { return y.value > 30 })

const saveData = () => {

  if (dataSource.value.length == 0) {
    H.alert('error', 'Data Tidak Tersedia')
    return
  }

  if (!item.value.penanggungJawab) {
    H.alert('error', 'Penanggung Jawab Tidak Boleh Kosong')
    return
  }

  if (!item.value.ruanganfk) {
    H.alert('error', 'Ruangan Tidak Boleh Kosong')
    return
  }
  // if (!item.value.tgl) {
  //   H.alert('error', 'Tanggal Tidak Boleh Kosong')
  //   return
  // }

  isLoadBtnSave.value = true

  let strukOrder = {
      nostruk: route.query.norec ?  route.query.norec : '',
      ruanganfk: item.value.ruanganfk.value,
      namaruangan:item.value.ruanganfk.label,
      tglstruk: H.formatDate(item.value.tgl, 'YYYY-MM-DD HH:mm:ss'),
      pegawaimenerimafk: item.value.penanggungJawab.value,
      namapegawaipenerima: item.value.penanggungJawab.label,
      keterangan: item.value.keterangan ? item.value.keterangan : '',
      qtyproduk: dataSource.value.length,
      total: item.value.totalAll
  }
  let objSave = {
    struk: strukOrder,
    details: dataSource.value
  }
  useApi().post('logistik/save-pemakaian-stok-ruangan', objSave).then((response) => {
    // goToPageDaftar()
      isLoadBtnSave.value = false
  })
}

const showModal = async (e: any) => {

  if(!item.value.ruanganfk){
    H.alert('error','Ruangan Mesti Dipilih Terlebih Dulu')
    return
  }

  if (e.no) {
    loadingBtnEdit.value = true
    let filter = { query: e.namaproduk }
    await fetchProduk(filter)
    d_Produk.value.forEach(element => {
      item.value.produk = element
      changeProduk(item.value.produk)
      // getSatuan(element)
    });
    item.value.no = e.no
    item.value.tglKebutuhan = e.tglkebutuhan
    item.value.produkfk = e.produkfk
    // item.value.satuan = { label: e.satuanstandar, value: e.satuanstandarfk }
    item.value.satuanstandarfk = e.satuanstandarfk
    item.value.jumlah = e.jumlah
    // item.value.hargasatuan = e.hargasatuan
    // item.value.konversi = e.nilaikonversi
    item.value.total = e.total
    item.value.spesifikasi = e.keterangan
    modalInput.value = true
    loadingBtnEdit.value = false
  } else {
    modalInput.value = true
  }
}

const addItem = (e:any)=>{
  if (!e.produk) {
    H.alert('error','Produk Tidak Boleh Kosong')
    return
  }
  if (e.stok == 0 || !e.stok) {
    H.alert('error','Produk Tidak Boleh Kosong')
    return
  }
  if (!e.jumlah) {
    H.alert('error','Jumlah Tidak Boleh Kosong')
    return
  }

    let data: any = {}

    if(e.no){
      dataSource.value.forEach((element:any,i:any) => {
        if(element.no == e.no){
            data.no = element.no
            data.hargasatuan = parseFloat(e.hargasatuan)
            data.nostrukterimafk = norecTerima.value
            data.ruanganfk = item.value.ruanganfk.value
            data.asalprodukfk = item.value.asal.id
            data.asalproduk = item.value.asal.asalproduk
            data.produkfk = item.value.produk.id
            data.namaproduk = item.value.produk.namaproduk
            data.nilaikonversi = item.value.konversi
            data.satuanstandarfk = item.value.satuan.value.ssid
            data.satuanstandar = item.value.satuan.label
            data.satuanviewfk = item.value.satuan.value.ssid
            data.satuanview = item.value.satuan.label
            data.jumlah = parseFloat(item.value.jumlah)
            data.total = parseFloat(item.value.subTotal)
            data.keterangan = item.value.spesifikasi
            dataSource.value[i] = data;
          }
      });
      
    }else{
      data = {
        no : dataSource.value.length == 0 ? 1 : dataSource.value.length + 1,
        hargasatuan: parseFloat(e.hargasatuan),
        nostrukterimafk: norecTerima.value,
        ruanganfk: item.value.ruanganfk.value,
        asalprodukfk: item.value.asal.id,
        asalproduk: item.value.asal.asalproduk,
        produkfk: item.value.produk.id,
        namaproduk: item.value.produk.namaproduk,
        nilaikonversi: item.value.konversi,
        satuanstandarfk: item.value.satuan.value.ssid,
        satuanstandar: item.value.satuan.label,
        satuanviewfk: item.value.satuan.value.ssid,
        satuanview: item.value.satuan.label,
        jumlah: parseFloat(item.value.jumlah),
        total: parseFloat(item.value.subTotal),
        keterangan: item.value.spesifikasi,
      }
      dataSource.value.push(data)
    }
   count()
   clear()
}

const dialogConfirm = (e: any,info:any) => {
  let pesan = info == 'simpan' ? 'Apakah anda serius akan Menyimapan data ini ?' : 'Apakah anda serius menghapus data ini ?'
  let head = info == 'simpan' ? 'Konfirmasi Simpan Data' : 'Konfirmasi Hapus Data'
  confirm.require({
    message: pesan,
    header: head,
    icon: 'pi pi-info-circle',
    acceptClass: 'p-button-danger',
    accept: () => {
      if(info == 'simpan'){
        saveData()
      }else{
         dataSource.value.forEach((element: any, i: any) => {
            if (element.no == e.no) {
              dataSource.value.splice(i, 1)
            }
            element.no - 1
          })
           count()
           clear()
      }
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
  delete item.value.jumlah
  delete item.value.hargasatuan
  delete item.value.subTotal
}

const listData = async () => {
  await useApi()
    .get('logistik/penerimaan-barang/get-data-combo')
    .then((response) => {
      d_PenanggungJawab.value = response.pegawai.map((e: any) => {
        return { label: e.namalengkap, value: e.id }
      })
    })

   if(d_PenanggungJawab.value.length == 1){
    d_PenanggungJawab.value.forEach(element => {
      item.value.penanggungJawab = element
    });
   } 
}

const fetchProduk = async (filter: any) => {
    dataSourceStokProduk.value = []
    const response = await useApi().get(`/logistik/distribusi-barang-produk?namaproduk=${filter.query}&limit=10`)
    d_Produk.value = response.produk
}

const fetchRuangan = async (filter: any) => {
  const response = await useApi().get(`/emr/dropdown/ruangan_m?select=id,namaruangan&param_search=namaruangan&query=${filter.query}&limit=10`)
  d_Ruangan.value = response
}

const changeProduk = (e: any) => {
    if (e != null) {
        GETKONVERSI()
        // getHargaProduk(e.id)
        // getProdukListStok(e.id)
    }
}

const GETKONVERSI = async () => {
    if (item.value.produk.konversisatuan.length == 0) {
        d_Satuan.value = [
            {
                label: item.value.produk.satuanstandar, value:
                    { ssid: item.value.produk.ssid, satuanstandar: item.value.produk.satuanstandar }
            }]
        item.value.konversi = 1
    } else {
        d_Satuan.value = item.value.produk.konversisatuan.map((e: any) => {
            return { label: e.satuanstandar, value: e }
        })
    }
    d_Satuan.value.forEach(element => {
        if (item.value.produk.ssid == element.value.ssid) {
            item.value.satuan = element
        }
    });
    loadHarga.value = true
    await useApi().get('/farmasi/get-produkdetail?produkfk=' + item.value.produk.id + '&ruanganfk=' + item.value.ruanganfk.value).then(function (response: any) {
      if(response.detail.length > 0){
        dataProdukDetail.value = response.detail
        setNorecSPD()
      }
       item.value.stok = response.jmlstok
       loadHarga.value = false
    });

}

const setNorecSPD = async () => {
    if (item.value.stok == 0) {
        item.value.jumlah = 0
        return;
    }
    console.log(item.value.jumlah)
    for (var i = 0; i < dataProdukDetail.value.length; i++) {
        const element = dataProdukDetail.value[i]
        if (parseFloat(item.value.jumlah) * parseFloat(item.value.konversi) <= parseFloat(element.qtyproduk)) {
            hrg1.value = Math.round(parseFloat(element.hargajual) * parseFloat(item.value.konversi))
            item.value.hargasatuan = hrg1.value
            item.value.hargaNetto = Math.round(parseFloat(element.harganetto) * parseFloat(item.value.konversi))
            norecTerima.value = element.norec
            norecSPD.value = element.norec_spd
            item.value.asal = { id: element.objectasalprodukfk, asalproduk: element.asalproduk }
            break;
        }
    }
}


const getNilaiKon = (e:any)=>{
  console.log(e)
  item.value.konversi = e.value.nilaikonversi
}

const getProdukListStok = async (e:any)=>{

    let namaproduk:any
    await useApi().get(`farmasi/get-stok-produk-by-ruangan?produkfk=${e}`).then((response)=>{
        response.forEach((element:any,i:any) => {
            element.no = i + 1
            namaproduk = element.namaproduk
        });
        dataSourceStokProduk.value = response
        infoStok.value = `List Stok Produk ${namaproduk} Per Ruangan`

    })

}

const count = () => {

  let totalsub = 0

  dataSource.value.forEach((element: any) => {
    totalsub = totalsub + parseFloat(element.total)
  })
  item.value.totalAll = totalsub
}

const goToPageDaftar = () => {
  router.push({ name: 'module-logistik-daftar-purchase-order' })
}

const isEditPO = () => {
  const norec = route.query.norec
  if (norec) {
    dataSource.value.loading = true
    useApi().get(`logistik/get-detail-pemakaian-ruangan?norec=${norec}`).then((response)=>{
      let header = response.struk
      let details = response.details
      item.value.penanggungJawab = {label: header.namalengkap, value : header.pgid }
      item.value.ruanganfk = {label: header.namaruangan, value : header.objectruanganfk }
      item.value.tgl = header.tglstruk
      item.value.nostruk = header.nostruk
      item.value.norec = norec
      item.value.totalAll = header.total
      item.value.keterangan = header.keteranganlainnya
      dataSource.value.loading = false

      dataSource.value = details
      count()
    })
    .catch((e:any)=>{
      console.log(e)
    })
  }
}

watch(
    () => item.value.jumlah,
    (newValue, oldValue) => {
        if (newValue != oldValue) {
          if(!item.value.no){
           setNorecSPD()  
          }
        }
    }
)

watch(
  () => [
    item.value.hargasatuan,
    item.value.jumlah,
  ],
  () => {

    if (item.value.jumlah === undefined || item.value.jumlah === '') {
      item.value.subTotal = ''
    } else {
      const resultHarga: any = item.value.hargasatuan ? parseFloat(item.value.jumlah) * parseFloat(item.value.hargasatuan) : 0
      item.value.subTotal = resultHarga
    }
  }
)

watch(
  () => item.value.stok,
  () => {
    if(item.value.stok == 0){
      getProdukListStok(item.value.produk.id)
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
.field > label {
    text-overflow: ellipsis;
    overflow: hidden;
    max-width: fit-content;
    height: 1.2rem;
    white-space: nowrap;
}

.is-bold{
  font-weight:bold;
}
</style>

