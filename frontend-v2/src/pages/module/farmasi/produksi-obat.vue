<template>
  <ConfirmDialog />
  <div>
    <div class="form-layout is-stacked">
      <div class="form-outer" style="margin-top: 15px">
        <div class="form-header stuck-header">
          <div class="form-header-inner">
            <div class="left">
              <h3>Form Produksi Obat</h3>
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

            <div class="columns is-multiline pl-3 pr-3 pt-3">
              <div class="column is-3">
                <VField label="Kode produksi">
                  <VControl>
                    <input v-model="item.kodeProduksi" type="text" class="input" placeholder="Keterangan" />
                  </VControl>
                </VField>
              </div>
              <div class="column is-3">
                 <VDatePicker v-model="item.tglKadaluarsa" color="green" trim-weeks mode="date">
                  <template #default="{ inputValue, inputEvents }">
                    <VField label="Tanggal Kadaluarsa">
                      <VControl icon="feather:calendar">
                        <VInput type="text" placeholder="Tanggal Kadaluarsa" :value="inputValue"
                          v-on="inputEvents" />
                      </VControl>
                    </VField>
                  </template>
                </VDatePicker>
              </div>
              <div class="column is-3">
                <VField class="is-rounded-select is-autocomplete-select">
                <VLabel class="required-field">Ruangan</VLabel>
                <VControl icon="feather:search" fullwidth class="prime-auto-select">
                  <Dropdown v-model="item.ruangan" :options="d_Ruangan" optionLabel="label" style="width: 100%;"
                    :filter="true"/>
                </VControl>
              </VField>
              </div>
              <div class="column is-3">
                <VField class="is-rounded-select is-autocomplete-select">
                <VLabel class="required-field">Pegawai</VLabel>
                <VControl icon="feather:search" fullwidth class="prime-auto-select">
                  <Dropdown v-model="item.pegawai" :options="d_PenanggungJawab" optionLabel="label" style="width: 100%;"
                    :filter="true"/>
                </VControl>
              </VField>
              </div>
            </div>

            <div class="columns is-multiline pl-3 pr-3 pt-1">
              <div class="column is-5 pt-0">
                 <VField class="is-rounded-select is-autocomplete-select">
                  <VLabel class="required-field">Produk</VLabel>
                  <VControl icon="feather:search" fullwidth class="prime-auto-select">
                    <Dropdown v-model="item.produk" :options="d_Produk" optionLabel="namaproduk" style="width: 100%;"
                      :filter="true" @change="getDetail(item.produk)" />
                  </VControl>
                </VField>
              </div>
              <div class="column is-3 pt-0">
                 <VField class="is-rounded-select is-autocomplete-select">
                  <VLabel class="required-field">Satuan</VLabel>
                  <VControl icon="feather:search" fullwidth class="prime-auto-select">
                    <Dropdown v-model="item.satuan" :options="d_Satuan" optionLabel="label" style="width: 100%;" disabled 
                      :filter="true"/>
                  </VControl>
                </VField>
              </div>

              <div class="column pt-0">
                <VField label="Qty Produksi">
                  <VControl>
                    <input v-model="item.qtyProduksi" type="text" class="input" />
                  </VControl>
                </VField>
              </div>
            </div>

            <div class="columns is-multiline pl-3 pr-3 pt-1">
               <!-- <div class="column is-4">
                 <VDatePicker v-model="item.tglKadaluarsa" color="green" trim-weeks mode="date">
                  <template #default="{ inputValue, inputEvents }">
                    <VField label="Tanggal Kadaluarsa">
                      <VControl icon="feather:calendar">
                        <VInput type="text" placeholder="Tanggal Produksi" :value="inputValue"
                          v-on="inputEvents" />
                      </VControl>
                    </VField>
                  </template>
                </VDatePicker>
              </div> -->
              <div class="column is-12">
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
          <DataTable :value="sourceProduksiObat" :paginator="true" :rows="5" :rowsPerPageOptions="[5, 10, 25]"
            :loading="loadingSource" class="p-datatable-sm"
            paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
            responsiveLayout="stack" breakpoint="960px" sortMode="multiple"
            currentPageReportTemplate="Showing {first} to {last} of {totalRecords}" showGridlines>
            <Column field="no" header="No"></Column>
            <Column field="namaproduk" header="Deskripsi"></Column>
            <Column field="satuanstandar" header="Satuan"></Column>
            <Column field="jumlah" header="Qty Bahan" />
            <Column field="jmlstok" header="Stok" />
            <Column field="hargasatuan" header="Harga Satuan" style="text-align:right"/>
            <Column field="totalHarga" header="Total" style="text-align:right">
               <template #body="slotProps">
                  {{ H.formatRupiah(H.roundToDecimal(parseFloat(slotProps.data.totalHarga), 2), '') }}
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

      <div class="columns is-multiline">
        <div class="column is-3 p-0">
          <VCardCustom :style="'padding:5px 25px'">
            <div class="label-status" color="danger">
             <i aria-hidden="true" class="fas fa-circle"></i>
              <span class="ml-1">Sub Total</span>
            </div>
            <small class="text-bold-custom h-100">{{ H.formatRp(item.subTotal, 'Rp.')}}</small>
          </VCardCustom>
        </div>
        <div class="column is-3 p-0" style="margin-left: auto;">
          <VCardCustom :style="'padding:5px 25px'">
            <div class="label-status" color="primary">
             <i aria-hidden="true" class="fas fa-circle"></i>
              <span class="ml-1">Harga Satuan</span>
            </div>
            <small class="text-bold-custom h-100">{{ H.formatRp(item.hargaSatuan, 'Rp.')}}</small>
          </VCardCustom>
        </div>
      </div>

    </div>
  </div>

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

const NOREC_PRODUKSI = route.query.norec as string

const item: any = ref({
  tglProduksi: new Date(),
  kodeProduksi : 'FP/' + moment(new Date()).format('YYMM') + '____'
})

const d_Pegawai = ref([])
const d_SumberDana = ref([])
const d_kelompokBarang = ref([])
const d_unitPengorder = ref([])
const d_unitTujuan = ref([])
const d_PenanggungJawab = ref([])
const d_Ruangan = ref([])
const d_Produk = ref([])
const d_Satuan: any = ref([])
const colors: any = ref(Object.keys(useThemeColors()))
const dataProdukDetail: any = ref([])
const modalInput: any = ref(false)
const loadHarga = ref(false)
const listColor: any = ref([])
const nostruk: any = ref()
const noBukti: any = ref()
let sourceProduksiObat: any = ref([])
let loadingSource: any = ref(false)
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

  if (!item.value.produk) {
    H.alert('error', 'Produk Tidak Boleh Kosong')
    return
  }

  if (item.value.qtyProduksi <= 0) {
    H.alert('error', 'Qty Produksi Tidak Boleh Kosong')
    return
  }

  isLoadBtnSave.value = true

  let struk = {
    objectruanganfk : item.value.ruangan.value,
    hargasatuan : item.value.hargaSatuan,
    jumlahproduksi: item.value.qtyProduksi,
    objectpegawaiygmengetahuifk: item.value.pegawai.value,
    objectprodukfk: item.value.produk.id,
    kodeproduksi: item.value.kodeProduksi ? item.value.kodeProduksi : '',
    satuan: item.value.satuan.value,
    namaproduk :  item.value.produk.namaproduk,
    spesifikasi: item.value.keterangan,
    tanggalexpired:  H.formatDate(item.value.tglKadaluarsa, 'YYYY-MM-DD HH:mm:ss'),
    norecProduksi: NOREC_PRODUKSI ? NOREC_PRODUKSI : '' ,
  }

  let objSave = {
    struk: struk,
    details: sourceProduksiObat.value
  }

  await useApi().post('sysadmin/save-produksi-obat-non-steril', objSave).then((response) => {
  //   // goToPageDaftar()
  })
  isLoadBtnSave.value = false
}

const clear = () => {
  delete item.value.no
}

const listData = async () => {
  await useApi().get('sysadmin/produksi-obat/combo').then((response) => {
      d_PenanggungJawab.value = response.pegawailogin.map((e: any) => {
        return { label: e.namalengkap, value: e.id }
      })
      d_Ruangan.value = response.mapruangan.map((e: any) => {
        return { label: e.namaruangan, value: e.id }
      })
      d_Produk.value = response.produk
    })

     item.value.pegawai = d_PenanggungJawab.value[0] 
     item.value.ruangan = d_Ruangan.value[0] 
}

const getDetail = async(e:any)=>{

  loadingSource.value = true
  let response = await useApi().get(`sysadmin/get-detail-produksi-obat?idprodukhasil=${e.id}&ruid=${item.value.ruangan.value}`)
  let header = response.head[0]
  let details = response.details

  d_Satuan.value = [{ label : header.satuanview, value : header.ssid }]
  item.value.satuan = d_Satuan.value[0]
  item.value.qtyProduksi = header.qtyhasil
  item.value.keterangan = header.keterangan
 
  item.value.subTotal = 0
  item.value.hargaSatuan = 0
  details.forEach((element:any) => {
    element.totalHarga =  parseFloat(element.jumlah) * parseFloat(element.hargasatuan)
    item.value.subTotal = parseFloat(element.totalHarga) + parseFloat(item.value.subTotal)
    item.value.hargaSatuan = parseFloat(item.value.subTotal) / parseFloat(item.value.qtyProduksi)
  });
  console.log(item.value.hargaSatuan)
  sourceProduksiObat.value = details
  loadingSource.value = false

}


const back = () => {
  window.history.back()
}

listData()

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

