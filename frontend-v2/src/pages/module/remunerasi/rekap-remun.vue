<template>
  <div class="business-dashboard hr-dashboard">
    <div class="columns is-multiline">
      <div class="column is-9">
        <div class="columns is-multiline">
          <div class="column is-12">
            <VCard>
              <div class="columns is-multiline">
                <div class="column is-12">
                  <h3 class="title is-5 mb-2 mr-1">Remunerasi </h3>
                </div>
                <div class="column is-4">
                  <VField v-slot="{ id }" class="is-icon-select mt-1">
                    <VControl>
                      <Multiselect v-model="selectView" :attrs="{ id }" placeholder="Select View" label="name"
                        :options="d_View" :searchable="true" track-by="name" mode="single"
                        @select="changeView(selectView)" autocomplete="off">
                        <template #singlelabel="{ value }">
                          <div class="multiselect-single-label">
                            <div class="select-label-icon-wrap">
                              <i :class="value.icon"></i>
                            </div>
                            <span class="select-label-text">
                              {{ value.name }}
                            </span>
                          </div>
                        </template>
                        <template #option="{ option }">
                          <div class="select-option-icon-wrap">
                            <i :class="option.icon"></i>
                          </div>
                          <span class="select-option-text">
                            {{ option.name }}
                          </span>
                        </template>
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
              </div>

            </VCard>
          </div>
          <div class="column is-12">
            <div class="flex-list-inner">
              <VCard>
                <div class="columns is-multiline">
                  <div class="column is-12">
                    <p style="color:black">Periode {{
                      H.formatDateToLocalString(item.periode[0]) == H.formatDateToLocalString(item.periode[1]) ?
                      H.formatDateToLocalString(item.periode[0]) :
                      H.formatDateToLocalString(item.periode[0]) + ' - ' + (item.periode[1] ?
                        H.formatDateToLocalString(item.periode[1]) : '')
                    }}</p>
                  </div>
                  <div class="column is-12 mt-4-min">
                    <p> Terdapat <b>{{ ds_KendaliDokumen.length }}</b> data</p>
                  </div>

                  <div class="column is-12" v-if="ds_KendaliDokumen.loading">
                    <div class="flex-list-inner mb-4">
                      <div class="flex-table-item grid-item mb-4" v-for="key in 3" :key="key">
                        <VFlexTableCell :column="{ grow: true, media: true }">
                          <VPlaceloadAvatar size="medium" />
                          <VPlaceloadText :lines="2" width="30%" last-line-width="20%" class="mx-2" />
                        </VFlexTableCell>
                        <VFlexTableCell>
                          <VPlaceload width="100%" height="70px" class="mx-1 mt-2" />
                        </VFlexTableCell>
                        <VFlexTableCell>
                          <VPlaceload width="10%" height="20px" class="mx-1 mt-1" />
                        </VFlexTableCell>
                        <VFlexTableCell :column="{ align: 'end' }">
                          <VPlaceload width="10%" class="mx-1" />
                        </VFlexTableCell>
                      </div>
                    </div>
                  </div>
                  <div class="column is-12" v-else-if="ds_KendaliDokumen.length === 0">
                    <VPlaceholderSection title="Data Tidak Ditemukan" subtitle="Silakan Pilih Tanggal Periode Registrasi."
                      class="my-6">
                      <template #image>
                        <img class="light-image" src="/@src/assets/illustrations/placeholders/search-4.svg" alt="" />
                        <img class="dark-image" src="/@src/assets/illustrations/placeholders/search-4-dark.svg" alt="" />
                      </template>
                    </VPlaceholderSection>
                  </div>
                  <div class="column is-12" v-else-if="ds_KendaliDokumen.length > 0">
                    <div class="user-grid user-grid-v2" v-if="selectView == 'list'">
                      <TransitionGroup name="list" tag="div" class="columns is-multiline"
                        style="height: 900px;overflow:auto;">

                        <div v-for="item in ds_KendaliDokumen" :key="item.id" class="column is-4">
                          <div class="grid-item-wrap is-clickable" @click="showDetail(item)">
                            <div class="grid-item-head">
                              <div class="flex-head">
                                <div class="meta" style="height: 25px;">
                                  <span class="dark-inverted">{{ item.namadokter }}</span>
                                  <!-- <span class="dark-inverted">{{ item.namaruangan }}</span>
                                  <span>{{ item.noregistrasi }}</span> -->
                                  <!-- <span>
                                    {{ H.formatDateIndoSimple(item.tglregistrasi) }}
                                  </span> -->
                                </div>
                              </div>

                            </div>
                            <div class="flex-head" style=" display: flex; justify-content: space-between;">
                              <VTag v-if="item.kelompokpasien != null" class="mt-2 ml-2" :label="item.kelompokpasien"
                                :color="item.kelompokpasien == 'BPJS' ? 'green' : 'orange'" rounded />

                            </div>
                            <div class="grid-item">
                              <div class="people" style="padding-bottom:5px;">
                                <VIconBox size="medium" color="primary">
                                  <i class="fa fa-user-md" style="font-size:20px"></i>
                                </VIconBox>
                              </div>
                              <!-- <p style="font-weight:500; font-size:12pt;">{{ H.formatRupiah(item.total,'Rp. ') }}</p> -->
                              <!-- <VTag class="mt-2 ml-2" label="Cari" color="danger" rounded v-if="item.iscari" />
                              <VTag class="mt-2 ml-2" label="Dikirim" color="warning" rounded
                                v-else-if="item.isdikirim" />
                              <VTag class="mt-2 ml-2" label="Kembali" color="info" rounded v-else-if="item.iskembali" />
                              <VTag class="mt-2 ml-2" label="-" color="solid" rounded v-else /> -->
                              <div class="people" style="padding-bottom: 0;">
                                <VSnack :title="H.formatRupiah(item.total, 'Rp. ')" color="info" icon="fa:calculator">
                                </VSnack>
                              </div>
                              <!-- <div class="buttons">
                              </div> -->
                            </div>
                          </div>
                        </div>
                      </TransitionGroup>
                    </div>
                    <div class="" v-else-if="selectView == 'grid'">

                        <div class="columns is-multiline">
                  <div class="column is-12">
                        <DataTable :value="ds_KendaliDokumen" :rows="10"
                          :rowsPerPageOptions="[5, 10, 15, 30, 50, 100, 1000]" :loading="ds_KendaliDokumen.loading"
                          class="p-datatable-sm" breakpoint="960px" selectionMode="single" sortMode="multiple"

                          paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
                          paginator currentPageReportTemplate="Showing {first} to {last} of {totalRecords}">

                          <template #empty style="text-align: center;"> No data found. </template>
                          <Column :exportable="false" header="#" style="width:30px">
                                <template #body="slotProps">
                                    <VIconButton type="button" icon="feather:search" class="mr-3" color="warning" circle
                                        outlined raised v-tooltip-prime="'Detail'" @click="showDetail(slotProps.data)"
                                        :loading="slotProps.data.isLoading">
                                    </VIconButton>
                                </template>
                            </Column>
                          <Column field="no" header="No" style="width: 30px;" />
                          <Column field="kelompokstafmedisgrp" header="KSM" style="width: 300px;" sortable/>
                          <Column field="namadokter" header="NAMA" style="width: 300px;" sortable/>
                          <Column field="" header="NIP" style="width: 100px;" sortable/>
                          <Column field="jkn" header="JKN" style="width: 100px;" >
                            <template #body="slotProps">
                              {{ H.formatRupiah(slotProps.data.jkn, '') }}
                            </template>
                          </Column>
                          <Column field="reguler" header="REGULER"  style="width: 100px;">
                            <template #body="slotProps">
                              {{ H.formatRupiah(slotProps.data.reguler, '') }}
                            </template>
                          </Column>
                          <Column field="execu" header="EXECUTIVE"  style="width: 100px;">
                            <template #body="slotProps">
                              {{ H.formatRupiah(slotProps.data.execu, '') }}
                            </template>
                          </Column>
                          <Column field="total" header="JUMLAH"  style="width: 100px;" sortable>
                            <template #body="slotProps">
                              {{ H.formatRupiah(slotProps.data.total, '') }}
                            </template>
                          </Column>
                          <ColumnGroup type="footer">
                                <Row>
                                    <Column :footer="'TOTAL'" :colspan="6" />
                                    <Column :footer="H.formatRupiah(totalALL, 'Rp.')"  :colspan="3"  style="text-align:right" />
                                </Row>
                            </ColumnGroup>
                        </DataTable>
                        </div>
                        </div>

                    </div>
                  </div>
                </div>
              </VCard>
            </div>

          </div>
        </div>
      </div>
      <div class="column is-3">
        <div class="columns is-multiline">
          <div class="column is-12">
            <VCard>
              <div class="columns is-multiline">
                <div class="column is-12">
                  <h3 class="title is-5 mb-2">Rekap
                  </h3>
                </div>
              </div>
              <div class="columns is-multiline">
                <div class="column is-12">
                  <CardCountRev icon="/images/simrs/icon-search.png" straight :total="H.formatRupiah(item.c_total, '')"
                    label="TOTAL" />
                </div>
                <div class="column is-12">
                  <CardCountRev icon="/images/simrs/icon-send.png" straight :total="H.formatRupiah(item.c_jkn, '')"
                    label="JKN" />
                </div>
                <div class="column is-12">
                  <CardCountRev icon="/images/simrs/icon-registrasi.png" straight :total="H.formatRupiah(item.c_reg, '')"
                    label="REGULER" />
                </div>
                <div class="column is-12">
                  <CardCountRev icon="/images/simrs/icon-antrian.png" straight :total="H.formatRupiah(item.c_eks, '')"
                    label="EXECUTIVE" />
                </div>
              </div>
            </VCard>
          </div>
          <div class="column is-12">
            <VCard>
              <div class="columns is-multiline">
                <div class="column is-6">
                  <h3 class="title is-5 mb-2 mr-1">Filters </h3>
                </div>
                <div class="column is-6">
                  <a @click="clearFilter()" type="button" class="is-pulled-right mr-3" color="info" outlined raised> Clear
                    All </a>
                </div>
                <div class="column is-12">
                  <VField label="Periode">
                    <VControl class="prime-auto">
                      <Calendar inputId="range" v-model="item.periode" selectionMode="range" :manualInput="false"
                        class="w-100 mb-4" :showIcon="true" date-format="dd-mm-yy" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-12">
                  <VField>
                    <VLabel> Nama Dokter</VLabel>
                    <VControl>
                      <VInput type="text" placeholder=" Nama Dokter ..." autocomplete="off" v-model="item.namadokter"
                        v-on:keyup.enter="fetchPasien()" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-12">
                  <VField class=" is-rounded-select is-autocomplete-select">
                    <VLabel>Jenis</VLabel>
                    <VControl icon="feather:search" class="prime-auto">
                      <Dropdown v-model="item.jenis" :options="d_jenis" :optionLabel="'jenis'" placeholder="Jenis"
                        :optionValue="'jenis'" style="width: 100%;" :filter="true" appendTo="body" showClear />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-12">
                  <VButton @click="filter()" :loading="ds_KendaliDokumen.loading" type="button" icon="feather:search"
                    class="is-fullwidth mr-3" color="info" raised> Apply Filters
                  </VButton>
                </div>
                <div class="column is-12">
                  <Divider />
                  <span style="font-size:12px"> Catatan :</span><br>
                  <span style="font-size:12px">• E-Remun ini masih dalam tahap pengembangan simrs. dalam masa ujicoba ini
                    apabila ada ketidakcocokan data maka segera disampaikan kepada : </span><br>
                  <!-- <span class="pl-2" style="font-size:12px">- Dokter Mala 085885979844</span><br> -->
                  <!-- <span class="pl-2" style="font-size:12px">- Dokter Triana 081280243890</span><br> -->
                  <span class="pl-2" style="font-size:12px">- Tim Developer Transmedic</span><br>
                  <span style="font-size:12px">• Apabila ada ketidaksesuaian data maka akan dilakukan
                    perhitungan ulang.</span><br>

                  <!-- <span style="font-size:12px">• ⁠Perhitungan remunerasi ini apabila terdapat kesalahan, dapat dilakukan
                    perbaikan sebagaimana
                    mestinya.</span><br>

                  <span style="font-size:12px">• ⁠Koreksi atau Informasi lebih lanjut hub : Official ISIMRS Help Desk Hp :
                    +6285885979844</span> -->


                </div>
              </div>
            </VCard>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script setup lang="ts">
import { useRoute, useRouter } from 'vue-router'
import { ref, computed, reactive, watch } from 'vue'
import { useThemeColors } from '/@src/composable/useThemeColors'
import { useApi } from '/@src/composable/useApi'
import { useUserSession } from '/@src/stores/userSession'
import { useHead } from '@vueuse/head'
import { useToaster } from '/@src/composable/toaster'
import moment, { isDate } from 'moment'
import * as H from '/@src/utils/appHelper'
import * as qzService from '/@src/utils/qzTrayService'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import CardCountRev from '/@src/components/partials/widgets/stat/CardCountRev.vue'
import Calendar from 'primevue/calendar';
import Dropdown from 'primevue/dropdown';
import Divider from 'primevue/divider';
import DataTable from 'primevue/datatable'
import ColumnGroup from 'primevue/columngroup';   // optional
import Row from 'primevue/row';
import Column from 'primevue/column'
useHead({
  title: 'Rekap Remun ' + import.meta.env.VITE_PROJECT,
})
useViewWrapper().setPageTitle('E-Remun')
useViewWrapper().setFullWidth(true)
const totalALL:any = ref(0)
const route = useRoute()
const router = useRouter()
const isLoading: any = ref(false)
const userSession = useUserSession()
let listColor: any = ref(Object.keys(useThemeColors()))
let ds_KendaliDokumen: any = ref([])
let d_Instalasi: any = ref([])
let d_KelompokPasien: any = ref([])
let d_Ruangan: any = ref([])
let d_jenis: any = ref([])
const selectView: any = ref()
const currentPage: any = ref({
  limit: 6,
  rows: 50
})
selectView.value = 'grid'
const d_View = [
  {
    name: 'Grid View',
    value: 'grid',
    icon: 'fas fa-id-card-alt',
  },
  {
    name: 'List View',
    value: 'list',
    icon: 'fas fa-list',
  },
]
const item: any = ref({
  periode: [
    new Date('2023-11-01'),
    new Date('2023-11-30'),
  ],
  c_total: 0,
  c_jkn: 0,
  c_reg: 0,
  c_eks: 0,
  total: 0,
})
// let c = H.cacheHelper().get('c_remun-temp');
// if (c != undefined) {
//   item.value.periode.start = new Date(c[0]);
//   item.value.periode.end = new Date(c[1]);
// }
const d_KelompokNoCM = ref([
  { label: 'GENAP', value: 2 },
  { label: 'GANJIL', value: 1 },
])
const d_StatusDokumen = [
  { value: 'isBelumKirim', label: 'Belum Dikirim' },
  { value: 'isdikirim', label: 'Dikirm' },
  { value: 'iscari', label: 'Dicari' },
  { value: 'iskembali', label: 'Kembali' },
]

const fetchPasien = async () => {
  ds_KendaliDokumen.value = []
  ds_KendaliDokumen.value.loading = true

  let limit: any = currentPage.value.limit
  let offset: any = currentPage.value.page ? currentPage.value.page : 1
  offset = (offset * limit) - limit

  let deptId = '', ruangId = '', kelId = '', noreg = '',
    norm = '', namapasien = '', dari = '', sampai = '',
    qScan = ''

  if (!item.value.periode) {
    useToaster().error("Harap pilih periode registrasi terlebih dahulu !")
    return
  }

  if (item.value.periode[0]) {
    dari = H.formatDate(item.value.periode[0], 'YYYY-MM-DD 00:00')
  }
  if (item.value.periode[1]) {
    sampai = H.formatDate(item.value.periode[1], 'YYYY-MM-DD 23:59')
  } else {
    sampai = H.formatDate(item.value.periode[0], 'YYYY-MM-DD 23:59')
  }
  if (item.value.namadokter) norm = `&namadokter=${item.value.namadokter}`
  if (item.value.qnoreg) noreg = `&noreg=${item.value.qnoreg}`
  if (item.value.qScan) qScan = `&noreg=${item.value.qScan}`
  if (item.value.qnamapasien) namapasien = `&namapasien=${item.value.qnamapasien}`
  if (item.value.deptId) deptId = `&deptId=${item.value.deptId.id}`
  if (item.value.ruangId) ruangId = `&ruangId=${item.value.ruangId.value}`
  if (item.value.statusDokumen) status = `&status=${item.value.statusDokumen.value}`
  if (item.value.jenis) kelId = `&jenis=${item.value.jenis}`
  item.value.c_total = 0
  item.value.c_jkn = 0
  item.value.c_reg = 0
  item.value.c_eks = 0



  let kelompokNoCM = item.value.kelompokNocm ? `&kelompoknocm=${item.value.kelompokNocm.value}` : ''
  isLoading.value = true
  const response = await useApi().get(`/remunerasi/rekap-temp?_total=true&dari=${dari}&sampai=${sampai}${kelompokNoCM}&offset=${offset}&limit=${limit}&rows=${currentPage.value.rows}${norm}${noreg}${namapasien}${deptId}${ruangId}${kelId}${qScan}${status}`)
  isLoading.value = false
  // let c_set = {
  //   0: dari,
  //   1: sampai,
  // }
  // H.cacheHelper().set('c_remun-temp', c_set);
  totalALL.value = 0
  for (let z = 0; z <  response.data.length; z++) {
    const element =  response.data[z];
    element.no = z+1
    totalALL.value = totalALL.value+  parseFloat(element.total)
  }
  ds_KendaliDokumen.value.loading = false
  ds_KendaliDokumen.value = response.data
  for (let x = 0; x < response.jenis.length; x++) {
    const element = response.jenis[x];
    if (element.jenis == 'JKN') {
      item.value.c_jkn = parseFloat(element.total)
    }
    if (element.jenis == 'REGULER') {
      item.value.c_reg = parseFloat(element.total)
    }
    if (element.jenis == 'EXECUTIVE') {
      item.value.c_eks = parseFloat(element.total)
    }
  }
  item.value.c_total = item.value.c_eks + item.value.c_reg + item.value.c_jkn





}

const updateStatusDok = async (item, cond) => {
  isLoading.value = true
  var json = {
    noregistrasifk: item.norec,
    update: cond,
  }
  await useApi()
    .post(`/rekammedis/update-status-kendali-dokumen-rm`, json)
    .then((response: any) => {
      isLoading.value = false
      fetchPasien()
    })
}
const fetchdDropdown = async () => {
  const response = await useApi().get(`/remunerasi/dropdown-temp`)
  d_jenis.value = response.jenis
  // d_Instalasi.value = response.departemen.map((e: any) => { return { id: e.id, namadepartemen: e.namadepartemen, count: 0 } })
  // d_Instalasi.value = response.departemen.map((e: any) => { return { id: e.id, namadepartemen: e.namadepartemen, count: 0 } })
  // d_KelompokPasien.value = response.kelompokpasien.map((e: any) => { return { id: e.id, kelompokpasien: e.kelompokpasien, count: 0 } })
}

const getRuanganBydeptId = async (e) => {
  if (e == undefined) return
  const response = await useApi().get(`/rekammedis/get-ruangan-by-departement/${e.id}`)
  d_Ruangan.value = response.ruangan.map((e: any) => { return { label: e.namaruangan, value: e.id, default: e } })
}

const filter = async () => {
  fetchPasien()
}

const changeView = (e: any) => {
  selectView.value = e
}
const clearFilter = () => {
  delete item.value.deptId
  delete item.value.ruangId
  delete item.value.kelId
}

const setKelompokNoCM = () => {
  userSession.setKelompokNoCM(item.value.kelompokNocm.value)
}

const cetakTracerMedik = (noreg: any, nocm: any) => {
  qzService.printData(`registrasi/cetak-tracer?pdf=true&noregistrasi=${noreg}`, 'TRACER GENAP', 1)
  // if(parseInt(nocm) % 2 === 0){
  //     qzService.printData(`registrasi/cetak-tracer?pdf=true&noregistrasi=${noreg}`,'TRACER GENAP', 1)

  // }else{
  //     qzService.printData(`registrasi/cetak-tracer?pdf=true&noregistrasi=${noreg}`,'TRACER GANJIL', 1)
  // }
  //  H.printBlade(`registrasi/cetak-tracer?pdf=true&noregistrasi=${noreg}`);
}
const showDetail = (e: any) => {
  let dari = H.formatDate(item.value.periode[0], 'YYYY-MM-DD 00:00')
  let sampai = H.formatDate(item.value.periode[1], 'YYYY-MM-DD 00:00')

  router.push({
    name: 'module-remunerasi-detail-layanan',
    query: {
      dok: e.namadokter,
      dari: dari,
      sampai: sampai,

    },
  })
}
watch(currentPage.value, () => {
  fetchPasien()
})


watch(
  () => item.value.qScan,
  async (newValue, oldValue) => {
    if (newValue != oldValue) {
      if (newValue.length == 10) {
        await fetchPasien()
        for (let x = 0; x < ds_KendaliDokumen.value.length; x++) {
          const element = ds_KendaliDokumen.value[x];
          if (element.noregistrasi == newValue) {
            H.alert('info', 'Data Pasien ' + element.namapasien + ' sedang dikirim')
            await updateStatusDok(element, 'kirim')
            break
          }
        }

      }
    }
  }
)

fetchPasien()
fetchdDropdown()
</script>

<style lang="scss">
@import '/@src/scss/abstracts/all';
@import '/@src/scss/custom/config';

.user-grid-v2 {
  .columns {
    margin-left: -0.5rem !important;
    margin-right: -0.5rem !important;
    margin-top: -0.5rem !important;
  }

  .column {
    padding: 0.5rem !important;
  }

  .grid-item {
    @include vuero-s-card;

    text-align: center;

    >.v-avatar {
      display: block;
      margin: 0 auto 4px;
    }

    h3 {
      font-family: var(--font-alt);
      font-size: 1.1rem;
      font-weight: 600;
      color: var(--dark-text);
    }

    p {
      font-size: 0.85rem;
    }

    .people {
      display: flex;
      justify-content: center;
      padding: 8px 0 30px;

      .v-avatar {
        margin: 0 4px;
      }
    }

    .buttons {
      display: flex;
      justify-content: space-between;

      .button {
        width: calc(50% - 4px);
        color: var(--light-text);

        &:hover,
        &:focus {
          border-color: var(--fade-grey-dark-4);
          color: var(--primary);
          box-shadow: var(--light-box-shadow);
        }
      }
    }
  }

  .grid-item-wrap {
    border: 1px solid var(--fade-grey-dark-3);
    border-radius: var(--radius-large);
    transition: all 0.3s; // transition-all test

    .grid-item-head {
      background: #fafafa;
      border-radius: var(--radius-large) 6px 0 0;
      padding: 20px;

      .flex-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 12px;

        .meta {
          span {
            display: flex;

            &:first-child {
              font-family: var(--font-alt);
              font-weight: 600;
              font-size: 0.95rem;
              color: var(--dark-text);
            }

            &:nth-child(2) {
              font-size: 0.9rem;
              color: var(--light-text);
            }
          }
        }

        .status-icon {
          height: 28px;
          width: 28px;
          min-width: 28px;
          border-radius: var(--radius-rounded);
          border: 1px solid var(--fade-grey-dark-3);
          display: flex;
          align-items: center;
          justify-content: center;

          &.is-success {
            background: var(--success);
            border-color: var(--success);
            color: var(--white);
          }

          &.is-warning {
            background: var(--orange);
            border-color: var(--orange);
            color: var(--white);
          }

          &.is-danger {
            background: var(--danger);
            border-color: var(--danger);
            color: var(--white);
          }

          i {
            font-size: 8px;
          }
        }
      }

      .buttons {
        display: flex;
        justify-content: space-between;
        margin-bottom: 0;

        .button,
        .v-button {
          width: calc(50% - 4px);
          color: var(--light-text);
          margin-bottom: 0;

          &:hover,
          &:focus {
            border-color: var(--fade-grey-dark-4);
            color: var(--primary);
            box-shadow: var(--light-box-shadow);
          }
        }
      }
    }

    .grid-item {
      border-top-left-radius: 0;
      border-top-right-radius: 0;
      border: none;
    }
  }
}

.is-dark {
  .user-grid {
    .grid-item {
      @include vuero-card--dark;
    }
  }

  .user-grid-v2 {
    .grid-item-wrap {
      border-color: var(--dark-sidebar-light-12);

      .grid-item-head {
        background: var(--dark-sidebar-light-4);
      }
    }
  }
}

@media only screen and (min-width: 768px) and (max-width: 1024px) and (orientation: portrait) {
  .user-grid-v2 {
    .columns {
      display: flex;

      .column {
        min-width: 50% !important;
      }
    }
  }
}

@media only screen and (min-width: 768px) and (max-width: 1024px) and (orientation: landscape) {
  .user-grid-v2 {
    .columns {
      .column {
        min-width: 33.3% !important;
      }
    }
  }
}

// .option {
//   position: relative;
// }

// .option input {
//   position: absolute;
//   top: 0;
//   inset-inline-start: 0;
//   height: 100%;
//   width: 100%;
//   z-index: 1;
//   opacity: 0;
//   cursor: pointer;
// }

// .option input:checked ~ .indicator {
//   transform: scale(1);
// }

// .option .indicator {
//   position: absolute;
// //   position: inherit;
//   top: 1rem;
//   inset-inline-end: 1rem;
//   display: flex;
//   justify-content: center;
//   align-items: center;
//   height: 20px;
//   width: 20px;
//   color: var(--white);
//   background: var(--primary);
//   border-radius: 50%;
//   transform: scale(0);
//   transition: transform 0.3s;
// }
// .option input:checked{
//   border-color: var(--primary);
//   box-shadow: var(--light-box-shadow);
// }
// .option {
//   background: var(--white);
//   border: 1px solid var(--border);
//   border-radius: 0.5rem;
//   transition: border 0.3s, box-shadow 0.3s;
// }
// .user-grid-v2 .grid-item-wrap .grid-item-head {
//   padding-top: 10px;
// }

.is-dark {
  .options {
    .option {
      .indicator {
        background: var(--primary);
      }

      input {
        &:checked {
          ~.indicator {
            transform: scale(1);
          }

          ~.option-inner {
            border-color: var(--primary) !important;

            i {
              color: var(--primary);
            }
          }
        }
      }

      .option-inner {
        background-color: var(--dark-sidebar-light-2) !important;
        border-color: var(--dark-sidebar-light-12) !important;

        h4 {
          color: var(--dark-dark-text);
        }
      }
    }
  }
}

.options {
  width: 100%;
  display: block;
  flex-wrap: wrap;
  margin-left: -0.5rem;
  margin-right: -0.5rem;

  .option {
    position: relative;
    // width: calc(33.3% - 1rem);
    margin: 0.5rem;

    &:focus-within {
      border-radius: 4px;
      outline-offset: var(--accessibility-focus-outline-offset);
      outline-width: var(--accessibility-focus-outline-width);
      outline-style: var(--accessibility-focus-outline-style);
      outline-color: var(--accessibility-focus-outline-color);
    }

    input {
      position: absolute;
      top: 0;
      left: 0;
      height: 100%;
      width: 100%;
      z-index: 1;
      opacity: 0;
      cursor: pointer;

      &:checked {
        ~.indicator {
          transform: scale(1);
        }

        ~.option-inner {
          border-color: var(--primary);
          box-shadow: var(--light-box-shadow);

          i {
            color: var(--primary);
          }
        }
      }
    }

    .indicator {
      position: absolute;
      top: 1rem;
      right: 1rem;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 20px;
      width: 20px;
      color: var(--white);
      background: var(--primary);
      border-radius: 50%;
      transform: scale(0);
      transition: transform 0.3s;

      svg {
        height: 14px;
        width: 14px;
        stroke-width: 3px;
      }
    }

    .option-inner {
      padding: 1.5rem;
      background: var(--white);
      border: 1px solid var(--border);
      border-radius: 0.5rem;
      transition: border 0.3s, box-shadow 0.3s;

      h4 {
        color: var(--dark-text);
        font-weight: 600;
        font-family: var(--font-alt);
      }

      p {
        font-size: 0.9rem;
      }

      i {
        font-size: 2.25rem;
        color: var(--light-text);
        margin-bottom: 0.25rem;
      }
    }
  }
}

.user-grid-v2 .grid-item-wrap .grid-item-head .flex-head .meta span:nth-child(3) {
  font-size: 0.9rem;
  color: var(--light-text);
}

.h-toggle {
  margin-top: 15px;
  height: 30px;
  text-align: center;
  margin-right: 0;
}

.h-toggle .toggler .inactive {
  background: var(--white);
  border-color: var(--light-text);
  color: var(--light-text);
  opacity: 1;
  z-index: 1;
}

.h-toggle .toggler .active {
  background: var(--white);
  border-color: var(--success);
  color: var(--success);
  opacity: 0;
  z-index: 0;
}

.inbox-widget-2 .sender-block .exerpt h5 {
  color: var(--danger) !important;
}

.media-flex-center .flex-meta span:nth-child(2),
.media-flex-center .flex-meta>a:nth-child(2) {

  color: var(--dark);
  font-weight: bold;
}

.snack .snack-text {
  font-size: 0.8rem;
}

.s-card .card-inner {
  padding-top: 0.75rem;
}

.media-flex-center .flex-meta span:first-child,
.media-flex-center .flex-meta>a:first-child {
  text-overflow: ellipsis;
  overflow: hidden;
  width: 250px;
  height: 1.2rem;
  white-space: nowrap;
}

.hr-dashboard {
  .block-header {
    display: flex;
    border-radius: 16px;
    padding: 50px;
    background: var(--primary);
    font-family: var(--font);
    box-shadow: var(--primary-box-shadow);

    .left,
    .right {
      width: 30%;
    }

    .center {
      display: flex;
      flex-direction: column;
      width: 40%;
      padding-right: 30px;
      margin-right: 30px;
      border-right: 1px solid var(--primary-light-10);

      .block-text {
        margin-bottom: 16px;
      }

      .candidates {
        margin-top: auto;

        >.v-avatar {
          margin-right: 10px;
        }

        button {
          height: 40px;
          width: 40px;
          display: inline-flex;
          justify-content: center;
          align-items: center;
          border-radius: 10px;
          background: var(--white);
          color: var(--light-text);
          border: none;
          cursor: pointer;
          transition: all 0.3s; // transition-all test

          svg {
            height: 18px;
            width: 18px;
          }
        }
      }
    }

    .left {
      display: flex;
      justify-content: center;
      align-items: center;

      .current-user {
        .v-avatar {
          margin-bottom: 1rem;
        }

        h3 {
          font-family: var(--font-alt);
          font-weight: 700;
          font-size: 1.8rem;
          color: var(--white);
          line-height: 1.2;
        }
      }
    }

    .right {
      display: flex;
      flex-direction: column;

      .button {
        margin-top: auto;
      }
    }

    .block-heading {
      font-family: var(--font-alt);
      font-weight: 600;
      font-size: 1.1rem;
      color: var(--white);
      margin-bottom: 4px;
    }

    .block-text {
      font-family: var(--font);
      font-size: 0.9rem;
      color: var(--white);
      margin-bottom: 16px;
    }

    .header-meta {
      margin-left: 0;
      padding-right: 30px;

      h3 {
        color: var(--smoke-white);
        font-family: var(--font-alt);
        font-weight: 700;
        font-size: 1.3rem;
        max-width: 280px;
      }

      p {
        font-weight: 400;
        color: var(--smoke-white-dark-2);
        margin-bottom: 16px;
        max-width: 320px;
      }

      .action-link {
        span {
          font-size: 0.8rem;
          text-transform: uppercase;
          margin-right: 6px;
        }

        i {
          font-size: 12px;
        }
      }
    }
  }

  .tabs-wrapper.is-slider .tabs,
  .tabs-wrapper-alt.is-slider .tabs {
    position: relative;
    background: var(--fade-grey-light-2);
    border: 1px solid var(--fade-grey);
    max-width: 300px;
    height: 35px;
    border-bottom: none;

  }

  .search-menu {
    height: 56px;
    white-space: nowrap;
    display: flex;
    flex-shrink: 0;
    align-items: center;
    background-color: white;
    border-radius: 8px;
    width: 100%;
    padding-left: 0.75rem;

    >div:not(:last-of-type) {
      border-right: 1px solid var(--search-border-color);
    }

    .search-bar {
      height: 55px;
      width: 100%;
      position: relative;
      display: flex;
      align-items: center;
      padding-right: 1.5rem;

      .field {
        width: 100%;
      }

      .multiselect-tags {
        padding-left: 2.5rem;
      }
    }

    .search-location,
    .search-job,
    .search-salary {
      display: flex;
      align-items: center;
      width: 50%;
      font-size: 14px;
      font-weight: 500;
      padding: 0 25px;
      height: 100%;
      font-family: var(--font);

      input {
        width: 100%;
        height: 90%;
        display: block;
        font-family: var(--font);
        color: var(--input-color);
        background-color: transparent;
        border: none;
      }

      svg {
        margin-right: 0.5rem;
        width: 18px;
        color: var(--primary);
        flex-shrink: 0;
      }
    }

    .search-button {
      background-color: var(--primary);
      min-width: 100px;
      height: 56px;
      border: none;
      font-weight: 500;
      font-family: var(--font);
      padding: 0 1rem;
      border-radius: 0 0.75rem 0.75rem 0;
      color: white;
      cursor: pointer;
      margin-left: auto;
    }
  }

  .search-widget {
    flex: 1;
    display: inline-block;
    width: 100%;
    padding: 12px;
    background-color: var(--white);
    border-radius: 16px;
    border: 1px solid var(--fade-grey-dark-3);
    transition: all 0.3s;
  }

  .feed-settings {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 20px 0;

    h3 {
      font-family: var(--font-alt);
      font-size: 1.1rem;
      font-weight: 600;
      color: var(--dark-text);
    }

    .button {
      font-size: 0.8rem;
      border-radius: 8px;
      margin-right: 4px;

      &.is-selected {
        background: var(--primary);
        color: var(--white);
        border-color: var(--primary);
        box-shadow: var(--primary-box-shadow);
      }
    }
  }

  .side-text {
    h3 {
      font-family: var(--font-alt);
      font-size: 1.1rem;
      font-weight: 600;
      color: var(--dark-text);
      margin-bottom: 8px;
    }

    p {
      font-size: 0.95rem;
      margin-bottom: 8px;
    }

    .action-link {
      font-size: 0.9rem;
    }
  }

  .recent-rookies {
    .recent-rookies-header {
      display: flex;
      align-items: center;
      justify-content: space-between;
      margin-bottom: 20px;

      h3 {
        font-family: var(--font-alt);
        font-size: 1.1rem;
        font-weight: 600;
        color: var(--dark-text);
      }
    }

    .user-grid {
      &.user-grid-v4 {
        .grid-item {
          @include vuero-l-card;
        }
      }
    }
  }
}

.user-grid {
  .columns {
    margin-left: -0.5rem !important;
    margin-right: -0.5rem !important;
    margin-top: -0.5rem !important;
  }

  .column {
    padding: 0.5rem !important;
  }

  .grid-item {
    position: relative;
    @include vuero-s-card;

    text-align: center;

    &:hover,
    &:focus {
      .button-wrap {
        >div {
          a {
            opacity: 1;
            pointer-events: all;
          }
        }
      }
    }

    .dropdown {
      position: absolute;
      top: 10px;
      right: 10px;
      text-align: left;
    }

    >.v-avatar {
      display: block;
      margin: 0 auto 4px;
    }

    h3 {
      font-family: var(--font-alt);
      font-size: 1.1rem;
      font-weight: 600;
      color: var(--dark-text);
    }

    p {
      font-size: 0.85rem;
    }

    .button-wrap {
      margin: 20px 0 0;

      .v-button {
        width: 100%;
        max-width: 140px;
        margin: 0 auto;
      }

      >div {
        margin: 6px 0 0;

        a {
          opacity: 0;
          pointer-events: none;
          color: var(--light-text);
          font-weight: 500;
          font-size: 0.9rem;
          transition: opacity 0.3s, color 0.3s;

          &:hover,
          &:focus {
            color: var(--primary);
          }
        }
      }
    }
  }
}

.user-grid .grid-item h3 {
  font-family: var(--font-alt);
  font-size: 0.8rem;
  font-weight: 600;
  color: var(--dark-text);
}

.is-dark {
  .user-grid {
    .grid-item {
      @include vuero-card--dark;
    }
  }

  .hr-dashboard {
    .block-header {
      background: var(--dark-sidebar);
      box-shadow: none;

      .center {
        border-color: var(--dark-sidebar-light-10);

        .candidates {
          button {
            background: var(--dark-sidebar-light-10);
            border: 1px solid transparent;
            transition: all 0.3s; // transition-all test

            &:hover {
              border-color: var(--primary);

              svg {
                color: var(--primary);
              }
            }
          }
        }
      }
    }

    .feed-settings {
      .button {
        &.is-selected {
          background: var(--primary) !important;
          border-color: var(--primary) !important;
          box-shadow: var(--primary-box-shadow) !important;
          color: var(--white) !important;
        }
      }
    }

    .recent-rookies {
      .user-grid {
        &.user-grid-v4 {
          .grid-item {
            @include vuero-card--dark;
          }
        }
      }
    }
  }
}

.list-view-v1 {
  .list-view-item {
    @include vuero-r-card;

    margin-bottom: 16px;
    padding: 16px;

    .list-view-item-inner {
      display: flex;
      align-items: center;

      .meta-left {
        margin-left: 16px;

        h3 {
          font-family: var(--font-alt);
          color: var(--dark-text);
          font-weight: 600;
          font-size: 1rem;
          line-height: 1;
        }

        >span:not(.tag) {
          font-size: 0.9rem;
          color: var(--light-text);

          svg {
            height: 12px;
            width: 12px;
          }
        }
      }

      .meta-right {
        margin-left: auto;
        display: flex;
        justify-content: flex-end;
        align-items: center;

        .tags {
          margin-right: 30px;
          margin-bottom: 0;

          .tag {
            margin-bottom: 0;
          }
        }

        .stats {
          display: flex;
          align-items: center;
          margin-right: 30px;

          .stat {
            display: flex;
            align-items: center;
            flex-direction: column;
            text-align: center;
            color: var(--light-text);

            >span {
              font-family: var(--font);

              &:first-child {
                font-size: 1.2rem;
                font-weight: 600;
                color: var(--dark-text);
                line-height: 1.4;
              }

              &:nth-child(2) {
                text-transform: uppercase;
                font-family: var(--font-alt);
                font-size: 0.75rem;
              }
            }

            svg {
              height: 16px;
              width: 16px;
            }

            i {
              font-size: 1.4rem;
            }
          }

          .separator {
            height: 25px;
            width: 2px;
            border-right: 1px solid var(--fade-grey-dark-3);
            margin: 0 16px;
          }
        }

        .network {
          display: flex;
          justify-content: flex-end;
          align-items: center;
          min-width: 145px;

          >span {
            font-family: var(--font);
            font-size: 0.9rem;
            color: var(--light-text);
            margin-left: 6px;
          }
        }

        .dropdown {
          margin-left: 30px;
        }
      }
    }
  }
}

.is-dark {
  .list-view-v1 {
    .list-view-item {
      @include vuero-card--dark;

      .list-view-item-inner {
        .meta-left {
          h3 {
            color: var(--dark-dark-text) !important;
          }
        }

        .meta-right {
          .stats {
            .stat {
              span {
                &:first-child {
                  color: var(--dark-dark-text);
                }
              }
            }

            .separator {
              border-color: var(--dark-sidebar-light-16) !important;
            }
          }
        }
      }
    }
  }
}

.list-view-v3 {
  .list-view-item {
    @include vuero-r-card;

    margin-bottom: 16px;
    padding: 16px;

    .list-view-item-inner {
      display: flex;
      align-items: center;

      >img {
        width: 100%;
        max-width: 60px;
        min-width: 60px;
        max-height: 60px;
        min-height: 60px;
        border-radius: var(--radius-rounded);
        border: 1px solid var(--fade-grey);
      }

      .meta-left {
        margin-left: 16px;

        h3 {
          font-family: var(--font-alt);
          color: var(--dark-text);
          font-weight: 500;
          font-size: 1.1rem;
          line-height: 1;
        }

        >span:not(.tag) {
          font-size: 0.9rem;
          color: var(--light-text);

          svg {
            position: relative;
            top: 1px;
            height: 12px;
            width: 12px;
          }

          .icon-separator {
            position: relative;
            top: -3px;
            font-size: 5px;
            color: var(--light-text);
            padding: 0 8px;
          }

          .iconify {
            margin-right: 0.25rem;
          }
        }
      }

      .meta-right {
        margin-left: auto;
        display: flex;
        align-items: center;
        justify-content: flex-end;

        .buttons {
          margin-bottom: 0;
          margin-right: 10px;
        }
      }
    }
  }
}

.illustration-header-2 {
  display: flex;
  align-items: center;
  padding: 10px;
  border-radius: 16px;
  background: var(--primary-dark-24);
  font-family: var(--font);
  box-shadow: var(--primary-box-shadow);

  .header-image {
    position: relative;
    height: 175px;
    width: 320px;

    img {
      position: absolute;
      top: -20px;
      left: -40px;
      display: block;
      pointer-events: none;
    }
  }

  .header-meta {
    margin-left: 0;
    padding-right: 30px;

    h3 {
      color: var(--smoke-white);
      font-family: var(--font-alt);
      font-weight: 700;
      font-size: 1.3rem;
      max-width: 280px;
    }

    p {
      font-weight: 400;
      color: var(--smoke-white-dark-2);
      margin-bottom: 16px;
      max-width: 320px;
    }

    .action-link {
      span {
        font-size: 0.8rem;
        text-transform: uppercase;
        margin-right: 6px;
      }

      i {
        font-size: 12px;
      }
    }
  }
}

.is-dark {
  .list-view-v3 {
    .list-view-item {
      @include vuero-card--dark;

      .list-view-item-inner {
        >img {
          border-color: var(--dark-sidebar-light-12);
        }

        .meta-left {
          h3 {
            color: var(--dark-dark-text) !important;
          }
        }

        .meta-right {
          .buttons {
            .button {
              &:nth-child(2) {
                background: var(--dark-sidebar-light-2);
                border-color: var(--dark-sidebar-light-8);
                color: var(--dark-dark-text);
                transition: color 0.3s, background-color 0.3s, border-color 0.3s,
                  height 0.3s, width 0.3s;

                &:hover,
                &:focus {
                  border-color: var(--primary);
                  color: var(--primary);
                }
              }
            }
          }
        }
      }
    }
  }
}

.tile-grid-v2 {
  .tile-grid-item {
    @include vuero-s-card;

    border-radius: 14px;
    padding: 16px;
    cursor: pointer;

    &:hover,
    &:focus {
      border-color: var(--primary);
      box-shadow: var(--light-box-shadow);
    }

    .tile-grid-item-inner {
      display: flex;
      align-items: center;

      >img {
        display: block;
        width: 50px;
        height: 50px;
        min-width: 50px;
      }

      .meta {
        margin-left: 10px;
        line-height: 1.4;

        span {
          display: block;
          font-family: var(--font);

          &:first-child {
            color: var(--dark-text);
            font-family: var(--font-alt);
            font-weight: 600;
            font-size: 0.8rem;
          }

          &:nth-child(2) {
            display: flex;
            align-items: center;

            span {
              display: inline-block;
              color: var(--light-text);
              font-size: 0.5rem;
              font-weight: 400;
            }

            .icon-separator {
              position: relative;
              font-size: 4px;
              color: var(--light-text);
              padding: 0 6px;
            }
          }
        }
      }

      .dropdown {
        margin-left: auto;
      }
    }
  }
}

.is-dark {
  .tile-grid {
    .tile-grid-item {
      @include vuero-card--dark;
    }
  }

  .tile-grid-v2 {
    .tile-grid-item {
      @include vuero-card--dark;

      &:hover,
      &:focus {
        border-color: var(--primary) !important;
      }
    }
  }
}

.speeddial-tooltip-demo .p-speeddial-direction-up.speeddial-left {
  left: 0;
  bottom: 0;
}

.speeddial-tooltip-demo .p-speeddial-direction-up.speeddial-right {
  right: 0;
  bottom: 0;
}


.speeddial-delay-demo .p-speeddial-direction-up {
  left: calc(50% - 2rem);
  bottom: 0;
}

.speeddial-mask-demo .p-speeddial-direction-up {
  right: 0;
  bottom: 0;
}


@media only screen and (min-width: 900px) {
  .illustration-header-2.large-screen {
    min-height: 235px;
  }

  .illustration-header-2 .header-image {
    width: 370px;
  }

  .illustration-header-2 {
    .header-image {
      img {
        top: -35px
      }
    }
  }
}

@media only screen and (max-width: 767px) {
  .hr-dashboard {
    .block-header {
      flex-direction: column;
      padding: 30px;

      .left,
      .center,
      .right {
        width: 100%;
      }

      .left {
        justify-content: flex-start;
        margin-bottom: 20px;
      }

      .center {
        padding-right: 0;
        margin-right: 0;
        border-right: none;
        margin-bottom: 20px;
      }
    }

    .feed-settings {
      flex-direction: column;

      h3 {
        margin-bottom: 16px;
      }
    }
  }
}

@media only screen and (min-width: 768px) and (max-width: 1024px) and (orientation: portrait) {
  .hr-dashboard {
    .block-header {
      padding: 40px;
    }

    .side-text {
      display: none;
    }
  }
}

@media only screen and (min-width: 768px) and (max-width: 1024px) and (orientation: landscape) {
  .hr-dashboard {
    .block-header {
      padding: 40px;

      .left {
        .current-user {
          h3 {
            font-size: 1.5rem;
          }
        }
      }

      .center {
        .candidates {
          .v-avatar {
            &:nth-child(3) {
              display: none;
            }
          }
        }
      }
    }

    .column {
      &.is-7 {
        &.is-offset-1 {
          margin-left: 2% !important;
          width: 64.3333% !important;
        }
      }
    }
  }
}

.f-text .multiselect-single-label-text {
  color: var(--white);
}

.multiselect.f-text .multiselect-search {
  background: var(--primary-dark-24);
}

.p-button {
  background: var(--placeholder);

  border: 1px solid var(--placeholder);
}

.jobs-dashboard {
  display: flex;
  flex-direction: column;
  margin: 0 auto;
  overflow: hidden;

  .jobs-dashboard-wrapper {
    width: 100%;
    display: flex;
    flex-direction: column;
    flex-grow: 1;
    scroll-behavior: smooth;
    overflow: auto;
  }

  .search-menu {
    height: 56px;
    white-space: nowrap;
    display: flex;
    flex-shrink: 0;
    align-items: center;
    background-color: var(--header-bg-color);
    border-radius: 8px;
    width: 100%;
    padding-left: 0.75rem;

    >div:not(:last-of-type) {
      border-right: 1px solid var(--search-border-color);
    }

    .search-bar {
      height: 55px;
      width: 100%;
      position: relative;
      display: flex;
      align-items: center;
      padding-right: 1.5rem;

      .field {
        width: 100%;
      }

      .multiselect-tags {
        padding-left: 2.5rem;
      }
    }

    .search-location,
    .search-job,
    .search-salary {
      display: flex;
      align-items: center;
      width: 50%;
      font-size: 14px;
      font-weight: 500;
      padding: 0 25px;
      height: 100%;
      font-family: var(--font);

      input {
        width: 100%;
        height: 100%;
        display: block;
        font-family: var(--font);
        color: var(--input-color);
        background-color: transparent;
        border: none;
      }

      svg {
        margin-right: 0.5rem;
        width: 18px;
        color: var(--primary);
        flex-shrink: 0;
      }
    }

    .search-button {
      background-color: var(--primary);
      min-width: 120px;
      height: 55px;
      border: none;
      font-weight: 500;
      font-family: var(--font);
      padding: 0 1rem;
      border-radius: 0 0.75rem 0.75rem 0;
      color: var(--button-color);
      cursor: pointer;
      margin-left: auto;
    }
  }

  .main-container {
    display: flex;
    flex-grow: 1;
    // padding-top: 2rem;
    padding-top: 0;

    .search-type {
      // width: 270px;
      width: 100%;
      display: flex;
      flex-direction: column;
      height: 100%;
      flex-shrink: 0;
    }

    .alert {
      background-color: var(--widget-grey);
      padding: 1.75rem;
      border-radius: 8px;

      .alert-title {
        font-size: 1rem;
        font-family: var(--font-alt);
        font-weight: 600;
        color: var(--dark-text);
        margin-bottom: 0.75rem;
      }

      .alert-subtitle {
        font-size: 13px;
        font-family: var(--font);
        color: var(--subtitle-color);
        margin-bottom: 1.5rem;
      }

      input {
        border-radius: 6px;
      }
    }

    .job-time {
      // padding-top: 1.75rem;
      padding-top: 0;


      .job-time-title {
        font-size: 0.95rem;
        font-family: var(--font-alt);
        font-weight: 600;
        color: var(--dark-text);
      }

      .type-container {
        display: flex;
        align-items: center;
        color: var(--subtitle-color);
        font-size: 13px;

        label {
          font-size: 0.75rem;
          margin-left: 2px;
          display: flex;
          align-items: center;
          cursor: pointer;
        }

        +.type-container {
          margin-top: 10px;
        }

        .job-number {
          margin-left: auto;
          display: flex;
          justify-content: center;
          align-items: center;
          height: 25px;
          min-width: 25px;
          background-color: var(--white);
          color: var(--subtitle-color);
          font-size: 0.8rem;
          font-family: var(--font);
          font-weight: 500;
          padding: 0 0.25rem;
          border-radius: 50rem;
        }
      }
    }

    .searched-jobs {
      display: flex;
      flex-direction: column;
      flex-grow: 1;
      padding-left: 2.5rem;
    }

    .searched-bar {
      display: flex;
      align-items: center;
      justify-content: space-between;

      .searched-count {
        font-family: var(--font-alt);
        font-size: 1rem;
        font-weight: 600;
        color: var(--dark-text);
      }
    }

    .job-cards {
      padding-top: 20px;
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      grid-column-gap: 1.5rem;
      grid-row-gap: 1.5rem;

      @media screen and (max-width: 1212px) {
        grid-template-columns: repeat(2, 1fr);
      }

      @media screen and (max-width: 930px) {
        grid-template-columns: repeat(1, 1fr);
      }
    }

    .job-card {
      @include vuero-l-card;

      cursor: pointer;
      transition: 0.2s;

      &:hover,
      &:focus {
        transform: translateY(-5px);
      }

      .job-card-header {
        // display: flex;
        // align-items: flex-start;
        display: flex;
        justify-content: center;
        align-items: center;
      }

      .job-card-logo {
        width: 80px;
        height: 80px;
        margin-right: -40px;
      }

      .job-card-title {
        font-family: var(--font-alt);
        font-weight: 600;
        color: var(--dark-text);
        margin-bottom: 0.75rem;
        display: flex;
        justify-content: center;
        align-items: center;

        max-height: 42px;
        overflow: hidden;
        -webkit-box-orient: vertical;
        -webkit-line-clamp: 1;
        display: -webkit-box;
        text-align: center;
      }

      .job-card-subtitle {
        color: var(--subtitle-color);
        font-family: var(--font);
        font-size: 0.95rem;
        line-height: 1.6em;
        // margin-bottom: 1rem;
        margin-top: -5px;
        display: flex;
        justify-content: center;
        align-items: center;
      }

      .job-card-buttons {
        margin-top: 1rem;

        .buttons {
          justify-content: space-between;

          .v-button {
            width: 48%;
          }
        }
      }
    }
  }
}

.is-dark {
  .jobs-dashboard {
    .job-card {
      @include vuero-card--dark;
    }

    .main-container {
      .alert {
        @include vuero-card--dark;
      }

      .job-time {
        .job-number {
          background: var(--dark-sidebar-light-2);
        }
      }
    }
  }
}

@media screen and (max-width: 620px) {
  .job-cards {
    grid-template-columns: repeat(1, 1fr);
  }
}

@media screen and (max-width: 730px) {
  .job-cards {
    grid-template-columns: repeat(2, 1fr);
  }
}

.user-grid-v2 .grid-item-wrap .grid-item-head.is-registrasi {
  background: var(--success) !important
}

.control.has-icon.prime-auto .form-icon {
  top: 0px;
}
</style>
