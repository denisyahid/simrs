
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
                                <VButton icon="lnir lnir-arrow-left rem-100" @click="back" light dark-outlined>
                                    Batal
                                </VButton>
                                <VButton icon="feather:save" type="submit" color="primary" raised @click="DialogConfirm(e)"
                                    :loading="isSimpan" :disabled="idDisabledBtn">
                                    Simpan
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
                                        <div class="columns is-multiline">
                                            <div class="column is-3">
                                                <VDatePicker v-model="item.tglkirim" color="green" trim-weeks
                                                    mode="dateTime">
                                                    <template #default="{ inputValue, inputEvents }">
                                                        <VField>
                                                            <VLabel>Tanggal</VLabel>
                                                            <VControl icon="feather:calendar">
                                                                <VInput type="text" placeholder="Select a date"
                                                                    class="is-rounded" :value="inputValue"
                                                                    v-on="inputEvents" :disabled="disTanggal" />
                                                            </VControl>
                                                        </VField>
                                                    </template>
                                                </VDatePicker>
                                            </div>

                                            <div class="column is-4">
                                                <VField class="is-rounded-select is-autocomplete-select">
                                                    <VLabelText>Ruangan</VLabelText>
                                                    <VControl icon="feather:search">
                                                        <Multiselect 
                                                            mode="single" 
                                                            v-model="item.ruanganPengirim"
                                                            :options="d_ruangan" 
                                                            :placeholder="item.loading ? 'Memuat data...' : 'Pilih data'" 
                                                            :searchable="true"
                                                            :disabled="disabledRuangan || item.loading" />
                                                    </VControl>
                                                </VField>
                                            </div>
                                        <div class="column is-4"  v-show="isVisible">
                                                <VField class="is-rounded-select is-autocomplete-select">
                                                    <VLabelText>Jenis Kirim</VLabelText>
                                                    <VControl icon="feather:search">
                                                        <Multiselect mode="single" v-model="item.jenisKirim"
                                                            :options="d_jenisKirim" placeholder="Pilih data"
                                                            :searchable="true"
                                                            :disabled="NOREC_ORDER || PRIHAL ? true : false" />
                                                    </VControl>
                                                </VField>
                                            </div>
                                            <div class="column is-4">
                                                <VField>
                                                    <VLabelText>Keterangan</VLabelText>
                                                    <VControl>
                                                        <input v-model="item.keterangan" type="text"
                                                            class="input is-rounded" placeholder="keterangan..." />
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
                                                <Toolbar class="mb-4" v-if="!ISEDIT_KIRIM && PRIHAL != 'verifikasi'">
                                                    <template #start>
                                                        <VButton icon="feather:plus" color="info" raised
                                                            @click="addPopUp()">
                                                            Tambah
                                                        </VButton>
                                                    </template>
                                                </Toolbar>

                                            <DataTable :value="dataSource" showGridlines editMode="cell" class="p-datatable-sm"
                                                @cell-edit-complete="getSelisih" tableClass="editable-cells-table"
                                                paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
                                                currentPageReportTemplate="Showing {first} to {last} of {totalRecords} "
                                                :paginator="true" :rows="5" :rowsPerPageOptions="[5, 10, 25]"> 
                                                    <Column :exportable="false" header="#" style="width:8rem" v-if="PRIHAL != 'verifikasi'">
                                                        <template #body="slotProps">
                                                            <Button icon="pi pi-trash" :disabled="ISEDIT_KIRIM ? true : false" 
                                                                class="p-button-rounded p-button-danger"
                                                                @click="hapusRow(slotProps.data)" />
                                                        </template>
                                                    </Column>

                                                    <Column field="no" header="No"></Column>
                                                    <Column field="kdproduk" header="Kode Produk" :sortable="true"></Column>
                                                    <Column field="namaproduk" header="Produk" :sortable="true"></Column>
                                                    <Column field="satuanstandar" header="Satuan"></Column>
                                                    <Column field="nilaikonversi" header="Konversi" :hidden="true"></Column>
                                                    <Column field="stock" header="Stok"></Column>
                                                    <Column style="text-align: end;" header="Qty Penggunaan">
                                                    <template #body="slotProps">
                                                        {{ slotProps.data.jumlah }}
                                                    </template>
                                                    <template #editor="{ data, field }">
                                                    <input v-model="data[field]" autofocus />
                                                    </template>
                                                    </Column>
                                                    <Column field="qtyprodukkonfirmasi" header="Qty Terlayani" :hidden="true">
                                                        <template #body="slotProps">
                                                            {{ slotProps.data.jumlah }}
                                                        </template>
                                                        <template #editor="{ data, field }">
                                                        <input v-model="data[field]" autofocus />
                                                        </template>
                                                    
                                                    
                                                    </Column>
                                                      

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
                </div>
            </div>
        </div>


        <VModal :open="modalInput" title="Pilih Item" size="medium" actions="right" @close="modalInput = false">
        <template #content>
        <form class="modal-form">
              <div class="group-header">
                                          <VField>
                                            <div class="column is-12">
                                              <div class="column is-12">
                                                <VControl icon="feather:search">
                                                    <input v-model="item.namaprodukserch" type="text" class="input is-rounded"
                                                      placeholder="Cari nama produk..." />
                                                 </VControl> 
                                                </div> 
                                                <div class="column is-12">
                                                    <VControl icon="feather:search">
                                                  
                                                    <input v-model="item.kodeprodukserch" type="text" class="input is-rounded"
                                                      placeholder="Cari kode produk..." />
                                                    </VControl>    
                                                </div>    
                                            </div> 
                                             
                                             
                                            <div class="column is-12">
                                                <VButton @click="filterProdukna()" :loading="isLoading" type="button" icon="feather:search"
                                                  class="is-fullwidth mr-3" color="info" raised> Cari Data
                                                </VButton>
                                            </div>
                                          
                                          </VField>
                                          
               </div>
         </form>
        <form class="modal-form">
        <div class="column is-12 mt-0">
                                <div class="form-section pt-0 pl-0">
                                    <div class="form-section-inner">
                                        <div class="column is-12 h-400-o">
                                          <div class="columns is-multiline mb-3" :loading="isLoading">
                                              <div class="column is-12"  v-for="items in filteredLayanan" :key="items.id"  >
                                              
                                                <VField>
                                                    <VControl raw subcontrol>
                                                        <table>
                                                            <tr>
                                                                <td rowspan="2">
                                                                    <VCheckbox v-model="item.produkCeklis[items.id]" color="info"
                                                                    @change="getSelected()"></VCheckbox>
                                                                </td>
                                                                <td>
                                                                    Produk &nbsp;
                                                                </td>
                                                                <td>: {{ items.namaproduk }}</td>
                                                            </tr>
                                                                <tr>
                                                                    <td>
                                                                        Stok: 
                                                                    </td>
                                                                    <td>: {{ items.qtyproduk }}</td>
                                                                </tr>
                                                            
                                                        </table>
                                                    </VControl>
                                                    </VField>
                                                </div>
                                          </div>
                                      </div>
                                    </div>
                                </div>
                            </div>
                        </form>       
      
         </template>
      <template #action>
     <VButton icon="feather:plus" @click="tambah()" color="primary" raised :disabled="isLoading">Tambah Item</VButton>
      </template>
    </VModal>
    </div>
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
import AutoComplete from 'primevue/autocomplete';
import PrimeVue from 'primevue/config';
import DataTable from 'primevue/datatable';
import Dropdown from 'primevue/dropdown';
import Column from 'primevue/column';
import Toolbar from 'primevue/toolbar'
import Button from 'primevue/button';
import moment from 'moment'
import ConfirmDialog from 'primevue/confirmdialog'
import { useConfirm } from 'primevue/useconfirm'
import * as H from '/@src/utils/appHelper'

let NOREC_ORDER: any = useRoute().query.norec_order as string
let NOREC_KIRIM: any = useRoute().query.norec as string
let ISEDIT_KIRIM: any = useRoute().query.iseditkirim as string
let PRIHAL: any = useRoute().query.prihal as string

const TITLE_PAGE = PRIHAL ? 'Verifikasi' : 'Pemakaian Floor Stok'
useHead({
    title: `${TITLE_PAGE} - Transmedic`,
})
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)

const modalInput = ref(false)

let item: any = reactive({
  header: {},
  totalAll: 0,
  jumlah: 0,
  tglkirim: new Date(),
  produkCeklis: [],
})
const confirm = useConfirm();
const { y } = useWindowScroll()
const isStuck = computed(() => {
  return y.value > 30
})
const isEdit:any = ref(false)
const d_ruangan: any = ref([])
const d_rutu: any = ref([])
const d_produk: any = ref([])
const d_satuan: any = ref([])
const d_jenisKirim: any = ref([])
const d_satuanResep: any = ref([])
const dataSource: any = ref([])
const data2: any = ref([])
const isLoading: any = ref(false)
const isSimpan: any = ref(false)
const tarifJasa: any = ref(0)
const hrg1: any = ref(0)
const hrgsdk: any = ref(0)
const dataProdukDetail: any = ref([])
const disabledRuangan: any = ref(false)
const disabledJenis: any = ref(false)
const isNull: any = ref(false)
const idDisabledBtn: any = ref(false)
const btnEditLoad: any = ref(false)
const dataSelected: any = ref({})
const disTanggal: any = ref(false)
const isReady: any = ref(false)
const d_Produk: any = ref([])
const d_ProdukDef: any = ref([])
const filterLayanan: any = ref('')
const listChecked: any = ref([])

const onInit = async () => {
    loadDrop()
    item.loading = false
    item.loading = true
    if(NOREC_KIRIM || NOREC_ORDER){
        loadEdit()
    }
}

const loadDrop = async () => {
    item.loading = true;
    disabledRuangan.value = true;
    const response = await useApi().get(`/logistik/list-order-cbo`)
    d_ruangan.value = response.ruangan.map((e: any) => { return { label: e.namaruangan, value: e.id } })
    d_jenisKirim.value = response.jenis.map((e: any) => { return { label: e.jenis, value: e.id } })
    item.jenisKirim = response.jenis[0].id
    d_satuanResep.value = response.satuanresep.map((e: any) => { return { label: e.satuanresep, value: e } })
    d_rutu.value = response.rutu.map((e: any) => { return { label: e.namaruangan, value: e.id } })
    item.loading = false;
    disabledRuangan.value = false;
}



const isVisible = ref(false);

const DialogConfirm = (e: any) => {
    confirm.require({
        message: 'Apakah Anda Yakin ?',
        header: 'Konfirmasi Penggunaan Barang',
        icon: 'pi pi-info-circle',
        acceptClass: 'p-button-danger',
        accept: () => {
            save()
        },
        reject: () => { },
    })
}

const loadEdit = async () => {
    if (NOREC_KIRIM && !NOREC_ORDER) {
        dataSource.value = ''
        await useApi().get(`/logistik/distribusi-detail?norec=${NOREC_KIRIM}`).then((response: any) => {
            item.jenisKirim = response.head.jenispermintaanfk
            item.ruanganPengirim = response.head.id
            item.keterangan = response.head.keterangan != '' ? response.head.keterangan : '-'
            item.tglkirim = new Date(response.head.tglkirim)
            disabledRuangan.value = true;
            disTanggal.value = true;
            response.detail.forEach((element: any, i: any) => {
                element.no = i + 1
                element.norec_spd = element.norec_spd ? element.norec_spd : null

            });

            data2.value = response.detail
            dataSource.value = response.detail
        })
        console.log(item.ruanganPengirim)
    }
    if (NOREC_ORDER) {
        isSimpan.value = true;
        isLoading.value = true
        dataSource.value = ''
        let norekkirim = NOREC_KIRIM ? `&noreckirim=${NOREC_KIRIM}` : ''
        await useApi().get(`/logistik/get-detail-kirim-order?norec=${NOREC_ORDER}${norekkirim}`).then((response: any) => {
            let head = response.strukorder
            response.order.forEach((element: any, i: any) => {
                element.no = i + 1
                element.qtyprodukkonfirmasi = element.qtykonfirmasi ? element.qtykonfirmasi : element.jumlah
                element.qtysebelumnya = element.qtykonfirmasi ? element.qtykonfirmasi : element.jumlah
                console.log( element.qtysebelumnya)
            });
            isLoading.value = false
            isSimpan.value = false;

            item.jenisKirim = head.jenisid
            item.ruanganPengirim = head.ruidasal
            item.keterangan = head.keterangan
            disabledRuangan.value = true;
            disTanggal.value = true;
            data2.value = response.order
            dataSource.value = data2.value

        })
    }
}

const addPopUp = () => {
  if (!item.ruanganPengirim) {
    H.alert('error', 'Ruangan Tujuan Tidak Boleh Kosong')
    return
  }
  clearInput()

  isLoading.value = false
  modalInput.value = true
  fetchTindakan()
}

const tambah = () => 
{

  if (item.produkCeklis == undefined || item.produkCeklis.length == 0) {
        H.alert('error', 'Pilih layanan terlebih dahulu')
        return
    }

  var dataceklina = listChecked.value;
  var arrobj = Object.keys(item.produkCeklis)
  var datana = []
    for (var i =0;  i < dataceklina.length; i++) {
      for(var d =0; d < arrobj.length; d++)
      {
        if (dataceklina[i]['id']== parseInt(arrobj[d])) {
          let nomor = 0
          if (data2.value.length == 0) {
            nomor = 1
          } else {
            nomor = data2.value.length + 1
          } 
          var qtyCetak = 0;
          var total = 0;
          var jumlahreal = 0;

          let jmlbulat = 0;
          let jml = 0;

          jmlbulat =1;
          jml = 1;
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
                data.ruanganfk = item.ruanganPengirim.id ? item.ruanganPengirim.id : element.ruanganfk
                data.namaruanganpengirim = item.namaruanganpengirim
                data.namaruangantujuan = item.namaruangantujuan
                data.produkfk = item.produk.kdproduk
                data.namaproduk = item.produk.namaproduk
                data.productname = item.produk.productname
                data.nilaikonversi = item.nilaiKonversi
                data.satuanstandarfk = item.satuan.ssid ?  item.satuan.ssid : element.satuanstandarfk
                data.satuanstandar = item.satuan.satuanstandar ? item.satuan.satuanstandar : element.satuanstandar
                data.satuanviewfk = item.satuan.ssid ?  item.satuan.ssid : element.satuanstandarfk
                data.satuanview = item.satuan.satuanstandar ? item.satuan.satuanstandar : element.satuanstandarfk
                data.jmlstok = String(item.stok)
                data.jumlah = item.jumlah
                data.qtyorder = 0
                data.hargasatuan = String(item.hargaSatuan)
                data.hargadiscount = String(item.hargadiskon)
                data.total = item.total
                data.qtyprodukkonfirmasi = NOREC_ORDER ? item.qtyprodukkonfirmasi : item.jumlah
                data2.value[x] = data;
              }
            }
            
          } else {
            let nomor = data2.value.length > 0 ? data2.value[data2.value.length - 1].no + 1 : 1;
            data = {
              no: nomor,
              stock:  dataceklina[i]['qtyproduk'],
              stockpengirim:  dataceklina[i]['qtyprodukpengirim'],
              ruanganfk: item.ruanganTujuan,
              produkfk: dataceklina[i]['id'],
              kdproduk: dataceklina[i]['kdproduk'],
              namaproduk: dataceklina[i]['namaproduk'],
              productname: dataceklina[i]['namaproduk'],
              nilaikonversi: dataceklina[i]['nilaikonversi']?dataceklina[i]['nilaikonversi']:1,
              satuanstandarfk:dataceklina[i]['satuanstandarfk'],
              satuanstandar: dataceklina[i]['satuanstandar'],
              satuanviewfk:dataceklina[i]['satuanstandarfk'],
              satuanview: dataceklina[i]['satuanstandar'],
              jmlstok:  dataceklina[i]['qtyproduktujuanpengirim'],
              jumlah: 1,
              qtyorder: 0,
              qtyprodukkonfirmasi: NOREC_ORDER ? item.qtyprodukkonfirmasi : item.jumlah,
              total: parseFloat( dataceklina[i]['qtyproduk'])* parseFloat(dataceklina[i]['harganetto'])
            }
            
            

          }
        }

      }
        data2.value.push(data)
    }

    console.log('aku disini', data2.value)

  dataSource.value = data2.value
  isLoading.value = false
  listChecked.value =[]
  item.produkCeklis =[]
  clearInput()
}

const editRow = async (e: any) => {
  e.LoadBtnEdit = true
  await fetchProduk({ query: e.namaproduk })
  item.no = e.no
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
  GETKONVERSI()
  modalInput.value = true
  e.LoadBtnEdit = false
  // item.nilaiKonversi = e.nilaikonversi
}

const hapusRow = (e: any) => {
  for (var i = data2.value.length - 1; i >= 0; i--) {
    if (data2.value[i].no == e.no) {
      data2.value.splice(i, 1);
      // dataOK.value.splice(i, 1);
    }
  }
  dataSource.value = data2.value
  // dataGridKronis.value = dataOK.value

  clearInput()
}
const save = async () => {

    if (!NOREC_KIRIM) {
        if (data2.value.length == 0) {
            useToaster().error('Produk belum di pilih')
            return
        }
    }

    let Keterangan = 'Kirim Barang'
    if (item.keterangan != undefined && item.keterangan != '') {
        Keterangan = item.keterangan
    }

    if (!NOREC_KIRIM && !NOREC_ORDER) {
        for (let i = data2.value.length - 1; i >= 0; i--) {
            if (parseFloat(data2.value[i].jmlstok) < parseFloat(data2.value[i].jumlah)) {
                useToaster().error("Terdapat obat dengan jumlah melebihi STOK !! " + data2.value[i].namaproduk)
                return
            }
        }
    }

    console.log('apasih',dataSource.value)
    let strukkirim = {
        objectruanganfk: item.ruanganPengirim,
        jenispermintaanfk: item.jenisKirim,
        keteranganlainnyakirim: Keterangan,
        qtydetailjenisproduk: 0,
        qtyproduk: data2.value.length,
        tglkirim: moment(item.tglkirim).format('YYYY-MM-DD HH:mm:ss'),
        totalhargasatuan: 0,
        norecOrder: NOREC_ORDER ? NOREC_ORDER : '',
        noreckirim: NOREC_KIRIM ? NOREC_KIRIM : '',
        prihal : PRIHAL,
        statuskirim : NOREC_KIRIM && PRIHAL ? 2 : 1,
    }
    let objSave =
    {
        strukkirim: strukkirim,
        details: dataSource.value
    }

    isSimpan.value = true

    if(NOREC_ORDER){
       await checkProduk(objSave)
    }

    let url = '/logistik/distribusi-barang-floor-stok'
    if(isNull.value == false){
        await useApi().post(url, objSave).then((response: any) => {
        idDisabledBtn.value = true
        isSimpan.value = false
     }).catch((e)=>{
        isSimpan.value = false
     })
    }


}
const changeProduk = (e: any) => {
    if (e != null) {
        GETKONVERSI()
    }
}

const GETKONVERSI = async () => {
  isLoading.value = true
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
  await useApi().get(
    '/farmasi/get-produkdetail?produkfk=' + item.produk.id +
    '&ruanganfk=' + item.ruanganTujuan).then(function (response: any) {
      if (response.detail.length > 0) {
        if (dataSelected.value.no != undefined) {
          console.log(dataSelected.value)
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
        if (dataSelected.value.no != undefined) {
          console.log(dataSelected.value)
          item.jumlah = dataSelected.value.jumlah
          item.stok = dataSelected.value.jmlstok
          item.kdproduk = dataSelected.value.kdproduk
          item.qtykonfirmasi = dataSelected.value.jumlah
          item.nilaiKonversi = dataSelected.value.nilaikonversi
        }else{
          item.stok = 0
          item.hargaSatuan = 0
          item.hargadiskon = 0
          item.hargaNetto = 0
          item.total = 0
        }
        // dialogConfirm(item.produk.id)
      }
    });
  isLoading.value = false
  isReady.value = false
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

const changeSatuan = (e: any) => {
  item.nilaiKonversi = item.satuan.value.nilaikonversi
}

const fetchProduk = async (filter: any) => {
  const response = await useApi().get(`/logistik/distribusi-barang-produk?namaproduk=${filter.query}&ruanganfk=${item.ruanganTujuan}&limit=10`)
  d_produk.value = response.produk
}


function back() {
  window.history.back()
}
onInit()



watch(
  () => item.nilaiKonversi,
  (newValue, oldValue) => {
    if (newValue != oldValue) {
      if (item.stok > 0) {
        item.stok = parseFloat(item.stok) * (parseFloat(oldValue) / parseFloat(newValue))
        item.jumlahbulat = 0;
        item.jumlah = 0
        item.hargaSatuan = 0
        item.hargadiskon = 0
        item.hargaNetto = 0
        item.total = 0
      }
    }
  }
)

const getSelected = () => {
                  if (item.produkCeklis.length > 0) {
                      var arrobj = Object.keys(item.produkCeklis)
                      for (var x = 0; x < arrobj.length; x++) {
                          const element = arrobj[x];
                          if (item.produkCeklis[parseInt(element)] == true) {
                              for (var i = 0; i < d_ProdukDef.value.length; i++) {
                                  const element2 = d_ProdukDef.value[i];
                                  if (element2.id == element) {
                                      for (var z = 0; z < listChecked.value.length; z++) {
                                          const element3 = listChecked.value[z];
                                          if (element3.namaproduk == element2.namaproduk) {
                                              listChecked.value.splice(z, 1)
                                          }
                                      }
                                      
                                      listChecked.value.push({ 
                                                               norec:element2.norec, 
                                                               namaproduk: element2.namaproduk,
                                                               id: element2.id,
                                                               tgl:element2.tgl, 
                                                               objectasalprodukfk: element2.objectasalprodukfk,
                                                               asalproduk: element2.asalproduk,
                                                               harganetto:element2.harganetto, 
                                                               hargadiscount: element2.hargadiscount,
                                                               hargajual: element2.hargajual,
                                                               persenhargajualproduk:element2.persenhargajualproduk, 
                                                               qtyproduk: element2.qtyproduk,
                                                               qtyprodukpengirim: element2.qtyprodukpengirim,
                                                               objectruanganfk: element2.objectruanganfk,
                                                               nostrukterimafk:element2.nostrukterimafk, 
                                                               tglkadaluarsa: element2.tglkadaluarsa,
                                                               persenup: element2.persenup,
                                                               norec_spd:element2.norec_spd, 
                                                               kdproduk: element2.kdproduk,
                                                               satuanstandar:element2.satuanstandar,
                                                              nilaikonversi: element2.nilaikonversi,
                                                              satuanstandarfk: element2.satuanstandarfk,
                                                              namaprodukuse: element2.namaprodukuse,

                                       })
                                       console.log('aku list', listChecked.value);
                                       
                                  }
                              }
                          } else {
                            
                              for (var i = 0; i < d_ProdukDef.value.length; i++) {
                                  const element2 = d_ProdukDef.value[i];
                                  
                                  if (element2.id == element) {
                                      for (var z = 0; z < listChecked.value.length; z++) {
                                          const element3 = listChecked.value[z];
                                          console.log('el',element3);
                                          
                                          if (element3.namaproduk == element2.namaproduk) {
                                              listChecked.value.splice(z, 1)
                                          }
                                      }
                                  }
                              }
                          }
                      }

                  }
              }
        
              const filteredLayanan = computed(() => {
                
                  var filtered: any = [];
                  for (let i = 0; i < d_Produk.value.length; i++) {
                      const element2 = d_Produk.value[i];
                      
                      
                              filtered.push({
                                                                  norec:element2.norec, 
                                                                  namaproduk: element2.namaproduk,
                                                                  id: element2.id,
                                                                  tgl:element2.tgl, 
                                                                  objectasalprodukfk: element2.objectasalprodukfk,
                                                                  asalproduk: element2.asalproduk,
                                                                  harganetto:element2.harganetto, 
                                                                  hargadiscount: element2.hargadiscount,
                                                                  hargajual: element2.hargajual,
                                                                  persenhargajualproduk:element2.persenhargajualproduk, 
                                                                  qtyproduk: element2.qtyproduk,
                                                                  objectruanganfk: element2.objectruanganfk,
                                                                  nostrukterimafk:element2.nostrukterimafk, 
                                                                  tglkadaluarsa: element2.tglkadaluarsa,
                                                                  persenup: element2.persenup,
                                                                  norec_spd:element2.norec_spd,
                                                                  satuanstandar:element2.satuanstandar,
                                                                  nilaikonversi: element2.nilaikonversi,
                                                                  satuanstandarfk: element2.satuanstandarfk,
                                                                  namaprodukuse: element2.namaprodukuse,
                                        })
                      
                        
                    
                     
                  }
                  return filtered;
                  
                  
                  })

      const clearSelection = () => {
                var arrobj = Object.keys(item.produkCeklis)
                for (let x = 0; x < arrobj.length; x++) {
                    const element2 = arrobj[x];
                    item.produkCeklis[element2] = false
                }
                getSelected()
            }
      const clearSelectionItem = (select: any) => {
          var arrobj = Object.keys(item.produkCeklis)
          for (let x = 0; x < arrobj.length; x++) {
              const element2 = arrobj[x];
              if (element2 == select.id) {
                  item.produkCeklis[element2] = false
              }
          }
          getSelected()
      }
      const fetchTindakan = (e: any) => {
            isLoading.value = true
           var filterSerch =""
           var filterSerch2 =""

           if(e !=undefined)
           {
              filterSerch =  e[0].nama,
              filterSerch2 =  e[0].id
           }
           useApi().get(
                `farmasi/get-produkdetail-ceklis2?ruanganfk=${item.ruanganPengirim}&ruanganPemesanfk=${item.ruanganPengirim}&limit=10&namaproduk=${filterSerch}`)
                .then((response: any) => {
                isLoading.value = false
                d_ProdukDef.value = response
                d_Produk.value = response
                })
        }
        const filterProdukna = (e: any) => {
             var filteredDatana: any = [];
             if(item.namaprodukserch != undefined)
             {
                    filteredDatana.push({
                      nama:item.namaprodukserch,
                      id:""
                    })
             }else if(item.kodeprodukserch != undefined)
             {
              filteredDatana.push({
                      nama: "",
                      id:item.kodeprodukserch
                    })
             }else if(item.kodeprodukserch != undefined && item.namaprodukserch != undefined){
                  filteredDatana.push({
                      nama: item.namaprodukserch,
                      id:item.kodeprodukserch
                    })
             }else{
              filteredDatana.push({
                      nama: "",
                      id:""
                    })
             }
             fetchTindakan( filteredDatana)
            }
const getSelisih = (event: any) => {
    let { data, field, newValue } = event
        data.jumlah = newValue;
        for (let x = 0; x < data2.value.length; x++) {
              const element = data2.value[x];
              if (element.no == data.no) {
                element.jumlah = newValue
                element.qtyprodukkonfirmasi = newValue
              }
            }
}
</script>

<style lang="scss">
@import '/@src/scss/abstracts/all';
@import '/@src/scss/components/forms-outer';

.form-layout .form-outer {
    border: 1px solid transparent;
    background-color: transparent;
}

.button.v-button {
    padding: 8px 22px !important;
    height: 38px !important;
    line-height: 1.1 !important;
    font-size: 0.95rem !important;
    font-family: var(--font) !important;
    transition: all 0.3s !important;
}
</style>

