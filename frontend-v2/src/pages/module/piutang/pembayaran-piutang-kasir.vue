<template>
  <div>
    <div class="form-layout is-stacked">
      <div class="form-outer" style="margin-top:15px">
        <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
          <div class="form-header-inner">
            <div class="left">
              <h3>Pembayaran Piutang Perusahaan</h3>
            </div>
            <div class="right">
              <div class="buttons">
                <VButton icon="lnir lnir-arrow-left rem-100" light dark-outlined @click="kembaliKeun()">
                  Kembali
                </VButton>
                <VButton type="button" rounded outlined color="primary" raised icon="feather:save"
                  :loading="isLoading || item.isLoadingSave" @click="bayar()"> Bayar
                </VButton>
              </div>
            </div>
          </div>
        </div>
        <div class="form-body p-2">
          <div class="business-dashboard hr-dashboard">
            <div class="columns is-multiline">
              <div class="column is-12">
                <VCard custom="card-info">
                  <div class="columns is-multiline">
                    <div class="column is-12">
                      <div class="columns is-multiline">
                        <div class="column is-3">
                          <VField>
                            <VLabel>No Collecting</VLabel>
                            <P class="block-text"> {{ input.noCollecting }}</P>
                          </VField>
                        </div>
                        <div class="column is-3">
                          <VField>
                            <VLabel>Nama Collecting</VLabel>
                            <P class="block-text"> {{ input.namacollector }}</P>
                          </VField>
                        </div>
                        <div class="column is-3">
                          <VField>
                            <VLabel>Perusahaan</VLabel>
                            <P class="block-text"> {{ input.namaRekanan }}</P>
                          </VField>
                        </div>
                      </div>
                    </div>
                  </div>
                </VCard>
              </div>
            </div>
            <div class="flex-list-inner mb-2 mt-5" v-if="isLoading">
              <div class="flex-table-item grid-item mb-1" v-for="key in 5" :key="key">
                <VPlaceloadWrap>
                  <VPlaceload class="mx-2 h-hidden-tablet-p" />
                  <VPlaceload class="mx-2 h-hidden-tablet-p" />
                  <VPlaceload class="mx-2 h-hidden-tablet-p" />
                  <VPlaceload class="mx-2" />
                </VPlaceloadWrap>
              </div>
            </div>
            <div v-if="dataSourceDetail.length == 0">
              <div class="column is-12 text-center">
                <h1 style="font-weight:bold">Belum Ada Pembayaran</h1>
              </div>
            </div>
            <div v-else>
              <VFlexTable :data="dataSourceDetail" :columns="columns" rounded>
                <template #body>
                  <div name="list" tag="div" class="flex-list-inner">
                    <div v-for="(item, index)  in dataSourceDetail" :key="item.id" class="flex-table-item">
                      <VFlexTableCell>
                        <span class="light-text">{{ item.noSbm }}</span>
                      </VFlexTableCell>
                      <VFlexTableCell>
                        <span class="light-text">{{ H.formatRp(parseInt(H.unFormatRupiah(item.jlhPembayaran)), 'Rp')
                        }}</span>
                      </VFlexTableCell>
                      <VFlexTableCell>
                        <span class="light-text">{{ item.tglPembayaran }}</span>
                      </VFlexTableCell>
                    </div>
                  </div>
                </template>
              </VFlexTable>
            </div>
          </div>
        </div>
      </div>
    </div>
    <form class="form-layout is-separate">
      <div class="form-outer">
        <div class="form-body" style="padding:0 !important;">
          <div class="form-section pl-0 pl-3 pr-3 pb-0 mb-0">
            <div class="columns is-multiline">
              <div class="column is-4 ">
                <div class="banking-dashboard banking-dashboard-v2">
                  <div class="dashboard-card is-card-panel">
                    <div class="inner-box">
                      <div class="box-title" style="margin-bottom: 20px;">
                        <h3>Data</h3>
                      </div>
                      <div class="content">
                        <div class="content-balance">
                          <VField horizontal>
                            <VLabel>Total Pasien</VLabel>
                            <VLabel>{{ dataSource.length }}</VLabel>
                          </VField>
                          <VField horizontal>
                            <VLabel>Total Tagihan </VLabel>
                            <VPlaceload class="mx-2 h-hidden-tablet-p" v-if="isLoading" />
                            <VLabel v-else>{{ H.formatRp(input.totalTagihan, 'Rp') }}</VLabel>
                          </VField>
                          <VField horizontal>
                            <VLabel>Total Diskon </VLabel>
                            <VPlaceload class="mx-2 h-hidden-tablet-p" v-if="isLoading" />
                            <VLabel v-else>{{ H.formatRp(input.diskon, 'Rp') }}</VLabel>
                          </VField>
                          <VField horizontal>
                            <VLabel>Sudah Dibayar </VLabel>
                            <VPlaceload class="mx-2 h-hidden-tablet-p" v-if="isLoading" />
                            <VLabel v-else>{{ H.formatRp(input.totalDeposit, 'Rp') }}</VLabel>
                          </VField>
                          <VField horizontal>
                            <VLabel>Sisa Tagihan </VLabel>
                            <VPlaceload class="mx-2 h-hidden-tablet-p" v-if="isLoading" />
                            <VLabel v-else>{{ H.formatRp((input.totalTagihan -
                              input.totalDeposit - input.diskon), 'Rp')
                            }}</VLabel>
                          </VField>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="column is-8 ">
                <VCard style="border-top-left-radius: 11px; border-left: solid hsla(323, 100%, 75%, 0.72) 3px;">
                  <div class="columns is-multiline">
                    <div class="column is-12" v-for="(item, index) in listItem" :key="index">
                      <VCard class="is-grey">
                        <div class="columns is-multiline p-1">
                          <div class="column is-5">
                            <VField label="Cara Bayar" class="is-rounded-select is-autocomplete-select" v-slot="{ id }">
                              <VControl icon="fas fa-credit-card" fullwidth class="prime-auto-select">
                                <Dropdown v-model="item.caraBayar" :options="d_CaraBayar" :optionLabel="'carabayar'"
                                  class="is-rounded" placeholder="Cara Bayar" style="width: 100%;" :filter="true" />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-5">
                            <VField label="Nominal">
                              <VControl class="prime-auto">
                                <VInput type="text" v-model="item.totalBayar" v-mask-currency placeholder="Nominal"
                                  class="is-rounded" v-on:input="changeNomi(item.totalBayar)" />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-1 mt-5">
                            <VIconButton v-if="index > 0" outlined type="button" raised circle class="is-pulled-right"
                              icon="feather:trash" @click="removeItem(index)" color="danger">
                            </VIconButton>
                          </div>
                          <div class="column is-1 mt-5">
                            <VIconButton outlined type="button" raised circle class="is-pulled-right" icon="feather:plus"
                              @click="addNewItem()" color="info" v-tooltip.bubble="'Tambah Cara Bayar'">
                            </VIconButton>
                          </div>
                        </div>
                      </VCard>
                    </div>
                    <div class="column is-12  pt-0 mt-0">
                      <VCard style="border-top-left-radius: 11px; border-left: solid hsla(170, 100%, 75%, 0.72) 3px;">
                        <div class="columns is-multiline">
                          <div class="column is-2 mt-1">
                            <VField>
                              <VLabel class="fs-total-label">TOTAL</VLabel>
                            </VField>
                          </div>
                          <div class="column is-10">
                            <VField>
                              <VLabel class="fs-total" style="font-weight: 700;width:500px !important;
                                font-size: 2.4rem;">{{
                                  H.formatRp(isNaN(item.totalBayarFix) ? 0 : item.totalBayarFix,
                                    'Rp.')
                                }} </VLabel>
                            </VField>
                          </div>
                        </div>
                      </VCard>
                    </div>
                  </div>
                </VCard>
              </div>
            </div>
          </div>
        </div>
      </div>
    </form>
  </div>
</template>
<script setup lang="ts">
import { useRoute, useRouter } from 'vue-router'
import { ref, computed, watch, reactive } from 'vue'
import DataTable from 'primevue/datatable'
import Column from 'primevue/column'
import { useWindowScroll } from '@vueuse/core'
import * as H from '/@src/utils/appHelper'
import Dropdown from 'primevue/dropdown'
import { useApi } from '/@src/composable/useApi';
import { useHead } from '@vueuse/head'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import sleep from '/@src/utils/sleep'
useHead({
  title: 'Pembayaran Piutang Perusahaan- ' + import.meta.env.VITE_PROJECT,
})
const { y } = useWindowScroll();
const dataSourceDetail = ref([]);
const dataSource = ref([]);
const dataSourceCollect = ref([]);
const dataBayar = ref([]);
const dataPasien = ref([]);
const d_CaraBayar = ref([]);
const columns: any = ref({});
const isLoading: any = ref(false);
const modalInput = ref(false);
const input: any = ref({});
const item: any = reactive({
});
let posting = useRoute().query.posting as string
const isStuck = computed(() => {
  return y.value > 30
});
columns.value = {
  no: 'Nomer SBM',
  tglsbm: 'Tanggal Pembayaran',
  carabayar: 'Jumlah Pembayaran'
};
const listItem: any = ref([
  {
    caraBayar: null,
    totalBayar: 0,
    nominal: 0,
  }
])
const changeDiskon = () => {
  input.value.diskon = parseInt(H.unFormatRupiah(item.diskon))
}
function changeNomi(e: any) {
  e = H.unFormatRupiah(e)
  setTotal()
}
const isMozilla = () => {
    return navigator.userAgent.indexOf('Firefox') !== -1;
}

const changeNominal =async () => {
  if (isMozilla()) { await sleep(1000) }
  item.terbilang = H.terbilang(parseInt(H.unFormatRupiah(item.nominal)));
}
const fetchData = async () => {
  isLoading.value = true;
  await useApi().get(`piutang/collected-piutang-layanan/${posting}`).then((response: any) => {
    dataSource.value = response
    let totalTagihan = 0;
    response.forEach((element: any, index: number) => {
      totalTagihan += parseFloat(H.unFormatRupiah(element.totalKlaim))
    })
    input.value.noCollecting = response[0].noPosting
    input.value.namacollector = response[0].collector
    input.value.namaRekanan = response[0].namarekanan
    input.value.totalTagihan = totalTagihan
    input.value.diskon = 0
    item.jumlahHarusDibayar = totalTagihan
    dataSourceCollect.value = response

  })
  fetchDataDetail();
  isLoading.value = false
}

const fetchDataDetail = async () => {
  isLoading.value = true;
  await useApi().get(`piutang/detail-piutang-pasien-collect/${posting}`).then((response: any) => {
    dataSourceDetail.value = response.detailPembayaran
    item.noregistrasi = response.noRecSPP
    let totalsbm = 0
    let diskon = 0;
    response.detailPembayaran.forEach((element: any, index: number) => {
      totalsbm += parseFloat(H.unFormatRupiah(element.jlhPembayaran)) + parseFloat(H.unFormatRupiah(element.diskon))
      diskon += parseFloat(H.unFormatRupiah(element.diskon))
    })
    input.value.diskon = diskon
    input.value.totalDeposit = totalsbm
    listItem.value[0].totalBayar = H.formatRupiah(parseFloat(input.value.totalTagihan) - parseFloat(totalsbm)  - parseFloat(diskon))
    // listItem.value[0].totalBayar = 500000
    setTotal()
  })
  isLoading.value = false
}
const fetchCaraBayar = async () => {
  await useApi().get(`kasir/cara-bayar`).then((response: any) => {
    d_CaraBayar.value = response
  })
}
fetchData();
fetchCaraBayar();


const kembaliKeun = () => {
  window.history.back()
}

const bayar = async () => {
  if (listItem.value.length == 0) {
    H.alert('error', 'Isi Cara Bayar dulu')
    return
  }
  // new flow

  let xTotalKlaim = 0;
  listItem.value.map((element :any ,index:number)=>{
    xTotalKlaim += parseFloat(element.nominal)
  })
  if(xTotalKlaim == 0){
    H.alert('error', 'Jumlah Pembayaran Tidak Boleh Nol !')
    return
  }
  let ttlKlaim = 0;
  dataSourceCollect.value.forEach((element: any, index: number) => {
    ttlKlaim += parseFloat(element.totalKlaim);
  })
  let persen = parseFloat((item.totalBayarFix * 100) / ttlKlaim);
  dataSourceCollect.value.forEach((element: any, index: number) => {
    let ttlKlaimPasien = parseFloat(element.totalKlaim);
    let ttlKlaimPerPasien = parseFloat(element.totalKlaim * persen) / 100;
    let pembulatan = Math.round(ttlKlaimPerPasien, -1);
    var obj = {
      noRecSPP: element.noRec,
      klaim: ttlKlaimPasien,
      bayarKlaim: xTotalKlaim + item.totalBayarFix
    }
    dataPasien.value.push(obj)
  })
  let total = 0
  for (let x = 0; x < listItem.value.length; x++) {
    const element = listItem.value[x];
    element.nominal = H.unFormatRupiah(element.nominal)
    total += parseFloat(element.nominal)
    if (element.caraBayar == null) {
      H.alert('error', 'Isi Cara Bayar dulu')
      return
    }
  }
  var arrObjPembayaran = [];
  listItem.value.forEach((element: any, index: number) => {
    var objPembayaran = {};
    objPembayaran.nominal = element.nominal;
    objPembayaran.caraBayar = {
      id: element.caraBayar.id
    };
    arrObjPembayaran.push(objPembayaran);
  })
  let json = {
    parameterTambahan: "cicilanPasienCollect",
    parameter: {
      noRecStrukPelayanan: item.noregistrasi,
      tipePembayaran: "cicilanPasienCollect",
      jumlahBayar: item.totalBayarFix
    },
    jumlahBayar: item.totalBayarFix,
    biayaAdministrasi: 0,
    diskon: 0,
    detailSPP: dataPasien.value,
    pembayaran: arrObjPembayaran,
    jumlahdiskon: input.diskon ?? 0
  }
  item.isLoadingSave = true
  console.log(JSON.stringify(json));
  await useApi().post(
    `/kasir/pembayaran-tagihan/simpan`, json).then((response: any) => {
      item.isLoadingSave = false
      fetchData();
      fetchDataDetail();
    }).catch((e: any) => {
      item.isLoadingSave = false
      fetchData();
      fetchDataDetail();
    })
}
function removeItem(index: any) {
  listItem.value.splice(index, 1)
}
function addNewItem() {
  let total = countTotal()
  let last = parseFloat(input.value.totalTagihan - input.value.totalDeposit - input.value.diskon) - total
  if (last == 0) {
    H.alert('error', 'Silahkan ubah nominal untuk SPLIT BAYAR')
    return
  }
  listItem.value.push({
    caraBayar: null,
    totalBayar: H.formatRupiah(last),
    nominal: parseFloat(last)
  });
  setTotal();
}
function countTotal() {
  let total = 0;

  for (let x = 0; x < listItem.value.length; x++) {
    const element = listItem.value[x];
    element.nominal = H.unFormatRupiah(element.totalBayar);
    total += parseFloat(H.unFormatRupiah(element.totalBayar));
  }

  return total;
}

function setTotal() {
  let total = countTotal()
  item.totalBayarFix = total
}

</script>

<style lang="scss">
@import '/@src/scss/module/piutang/piutang.scss';
@import '/@src/scss/components/forms-outer';

.form-layout .form-outer .form-body {
  padding: 0;
}

@import '/@src/scss/abstracts/all';
@import '/@src/scss/module/ext/banking-dashboard';
@import '/@src/scss/module/kasir/pembayaran-tagihan';
</style>
