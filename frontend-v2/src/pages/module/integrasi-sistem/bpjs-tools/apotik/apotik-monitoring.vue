<template>
    <VCard>
        <h3>Monitoring</h3>
        <div class="columns is-multiline mt-2">
          <div class="column is-12">
            <TabView>
              <TabPanel header="Riwayat Pelayanan Obat">
                <div class="columns is-multiline">
                  <div class="column is-12">
                    <div class="columns is-multiline">
                      <div class="column is-3">
                        <VField class=" is-rounded-select is-autocomplete-select">
                          <VLabel>Periode</VLabel>
                            <VControl>
                              <Calendar :locale="'id'" :dateFormat="H.dateTimeFormat().prime.date" inputId="range" v-model="item.qPeriodePelayanan" selectionMode="range" :manualInput="false"
                                class="w-100" :showIcon="true" :hideOnRangeSelection="true" />
                            </VControl>
                        </VField>
                      </div>
                      <div class="column is-3">
                        <VField>
                          <VLabel>No Kartu</VLabel>
                          <VControl>
                            <VInput v-model="item.noKartu" type="text" placeholder="No kartu BPJS" />
                          </VControl>
                        </VField>
                      </div>
                      <div class="column mt-5">
                        <VIconButton color="primary" circle icon="fas fa-search" outlined raised
                          @click="fetchRiwayatPelayananObat()" :loading="dataSourcePelayananObat.loading" v-tooltip.bottom.left="'Cari'">
                        </VIconButton>
                      </div>
                    </div>
                  </div>
                  <div class="column is-12" v-if="dataSourcePelayananObat.loading">
                    <VPlaceloadWrap>
                      <VPlaceload height="500px" width="100%" class="mx-2" />
                    </VPlaceloadWrap>
                  </div>
                  <div class="column is-12" v-else>
                    <DataTable v-model:filters="filters" :expandedRows="expandedRows" :value="dataSourcePelayananObat" paginator :rows="10" dataKey="no"
                      filterDisplay="row" :globalFilterFields="['peserta']['nama']" :class="`p-datatable-small`"
                      @rowExpand="onRowExpand" @rowCollapse="onRowCollapse">
                      <template #header>
                        <div class="flex justify-content-between">
                          <span class="p-input-icon-left">
                            <InputText v-model="filters['global'].value" placeholder="Search" />
                          </span>
                        </div>
                      </template>
                      <template #empty >  {{ H.assets().notFound }} </template>
                      <Column expander style="width: 5rem" />
                      <Column field="no" header="No"></Column>
                      <Column field="nokartu" style="min-width: 10px" header="No Kartu"></Column>
                      <Column field="namapeserta" style="min-width: 200px" header="Nama Pasien"></Column>
                      <Column field="tgllhr" header="Tanggal Lahir" style="min-width: 60px">
                        <template #body="slotProps">
                          {{  H.formatDate(slotProps.data.tgllhr,H.dateTimeFormat().genaral.date)  }}
                        </template>
                      </Column>
                      <template #expansion="slotProps">
                        <div class="p-3">
                          <DataTable :value="slotProps.data.history">
                            <Column field="nosjp" header="NO SEP"></Column>
                            <Column header="Tanggal Pelayanan">
                              <template #body="slotProps">
                                {{  H.formatDate(slotProps.data.tglpelayanan,H.dateTimeFormat().genaral.date)  }}
                              </template>
                            </Column>
                            <Column field="noresep" header="No Resep"></Column>
                            <Column field="kodeobat" header="Kode Obat"></Column>
                            <Column field="namaobat" header="Nama Obat"></Column>
                            <Column header="Jumlah Obat">
                              <template #body="slotProps">
                                {{  parseFloat(slotProps.data.jmlobat)  }}
                              </template>
                            </Column>
                            </DataTable>
                        </div>
                      </template>
                    </DataTable>
                  </div>
                </div>
              </TabPanel>
              <TabPanel header="Daftar Resep">
                <div class="columns is-multiline">
                  <div class="column is-12">
                    <div class="columns is-multiline">
                      <div class="column is-3">
                        <VField class=" is-rounded-select is-autocomplete-select">
                          <VLabel>Periode</VLabel>
                            <VControl>
                              <Calendar :locale="'id'" :dateFormat="H.dateTimeFormat().prime.date" inputId="range" v-model="item.qPeriodeResep" selectionMode="range" :manualInput="false"
                                class="w-100" :showIcon="true" :hideOnRangeSelection="true" />
                            </VControl>
                        </VField>
                      </div>
                      <div class="column is-3">
                        <VField>
                          <VLabel>Jenis</VLabel>
                          <VControl>
                            <VRadio v-model="item.jenisObat" value="1" label="Racikan" color="primary" />
                            <VRadio v-model="item.jenisObat" value="2" label="Non Racikan" color="primary" />
                          </VControl>
                        </VField>
                      </div>
                      <div class="column mt-5">
                        <VIconButton color="primary" circle icon="fas fa-search" outlined raised
                          @click="fetchDaftarResep()" v-tooltip.bottom.left="'Cari'" :loading="dataSourceResep.loading">
                        </VIconButton>
                      </div>
                    </div>
                  </div>
                  <div class="column is-12" v-if="dataSourceResep.loading">
                    <VPlaceloadWrap>
                      <VPlaceload height="500px" width="100%" class="mx-2" />
                    </VPlaceloadWrap>
                  </div>
                   <div class="column is-12" v-else>
                    <DataTable v-model:filters="filters" dataKey="no"
                      :globalFilterFields="['peserta']['nama']"  :value="dataSourceResep"
                       class="p-datatable-sm"
                        :paginator="true" :rows="10" :rowsPerPageOptions="[5, 10, 25]" scrollable
                        paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
                        responsiveLayout="stack" breakpoint="960px" sortMode="multiple"
                        currentPageReportTemplate="Showing {first} to {last} of {totalRecords}" showGridlines
                      >
                      <template #header>
                        <div class="flex justify-content-between">
                          <span class="p-input-icon-left">
                            <InputText v-model="filters['global'].value" placeholder="Search" />
                          </span>
                        </div>
                      </template>
                      <template #empty >  {{ H.assets().notFound }} </template>
                      <Column field="no" header="No"></Column>
                      <Column header="Tanggal Resep" style="min-width: 50px">
                        <template #body="slotProps">
                        {{  H.formatDate(slotProps.data.TGLRESEP,H.dateTimeFormat().genaral.date)  }}
                        </template>
                      </Column>
                      <Column header="Tanggal Entry" style="min-width: 50px">
                        <template #body="slotProps">
                        {{  H.formatDate(slotProps.data.TGLENTRY,H.dateTimeFormat().genaral.date)  }}
                        </template>
                      </Column>
                      <Column field="NORESEP" style="min-width: 20px" header="No Resep"></Column>
                      <Column header="Tanggal SEP" style="min-width: 50px">
                        <template #body="slotProps">
                        {{  H.formatDate(slotProps.data.TGLPELRSP,H.dateTimeFormat().genaral.date)  }}
                        </template>
                      </Column>
                      <Column field="NOAPOTIK" style="min-width: 20px" header="No Apotik"></Column>
                      <Column field="NOSEP_KUNJUNGAN" style="min-width: 30px" header="NO SEP"></Column>
                      <Column field="NOKARTU" style="min-width: 30px" header="NO Kartu"></Column>
                      <Column field="NAMA" style="min-width: 40px" header="Nama Pasien"></Column>
                      <Column header="BYTAGRSP" style="min-width: 30px">
                        <template #body="slotProps">
                        {{  H.formatRp(slotProps.data.BYTAGRSP,'Rp')  }}
                        </template>
                      </Column>
                      <Column header="BYVERRSP" style="min-width: 30px">
                        <template #body="slotProps">
                        {{  H.formatRp(slotProps.data.BYVERRSP,'Rp')  }}
                        </template>
                      </Column>
                      <Column field="KDJNSOBAT" style="min-width: 20px" header="Kode Jenis Obat"></Column>
                      <Column field="FASKESASAL" style="min-width: 20px" header="Faskes Asal"></Column>
                    </DataTable>
                   </div>
                </div>
              </TabPanel>
              <TabPanel header="Daftar Pelayanan Obat">
                <div class="columns is-multiline">
                  <div class="column is-12">
                    <div class="columns is-multiline">
                    <div class="column is-12">
                      <div class="columns is-multiline">
                        <div class="column is-3">
                          <VField>
                            <VLabel>NO SEP</VLabel>
                            <VInput v-model="item.nosep"></VInput>
                          </VField>
                        </div>
                        <div class="column mt-5">
                          <VIconButton color="primary" circle icon="fas fa-search" outlined raised
                            @click="fetchPelayananObat()" :loading="dataSourceObat.loading" v-tooltip.bottom.left="'Cari'">
                          </VIconButton>
                        </div>
                      </div>
                      </div>
                    </div>
                  </div>
                  <div class="column is-12" v-if="dataSourceObat.loading">
                    <VPlaceloadWrap>
                      <VPlaceload height="500px" width="100%" class="mx-2" />
                    </VPlaceloadWrap>
                  </div>
                  <div class="column is-12" v-else>
                    <DataTable v-model:filters="filters" dataKey="no"
                      :globalFilterFields="['peserta']['nama']"  :value="dataSourceObat"
                       class="p-datatable-sm"
                        :paginator="true" :rows="10" :rowsPerPageOptions="[5, 10, 25]" scrollable
                        paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
                        responsiveLayout="stack" breakpoint="960px" sortMode="multiple"
                        currentPageReportTemplate="Showing {first} to {last} of {totalRecords}" showGridlines
                      >
                      <template #header>
                        <div class="flex justify-content-between">
                          <span class="p-input-icon-left">
                            <InputText v-model="filters['global'].value" placeholder="Search" />
                          </span>
                        </div>
                      </template>
                      <Column field="no" header="No"></Column>
                      <Column field="noresep" header="No Resep" style="min-width: 80px"></Column>
                      <Column field="nokartu" header="No Kartu" style="min-width: 80px"></Column>
                      <Column field="nmpst" header="Nama Pasien" style="min-width: 200px"></Column>
                      <Column field="nmjnsobat" header="Jenis Obat" style="min-width: 120px"></Column>
                      <Column header="Tanggal Pelayanan" style="min-width: 150px">
                        <template #body="slotProps">
                        {{  H.formatDate(slotProps.data.tglpelayanan,H.dateTimeFormat().genaral.date)  }}
                        </template>
                      </Column>
                      <Column field="listobat.namaobat" header="Nama Obat" style="min-width: 100px"></Column>
                      <Column header="Jumlah" style="min-width: 50px">
                        <template #body="slotProps">
                        {{  parseFloat(slotProps.data.listobat.jumlah)  }}
                        </template>
                      </Column>
                      <Column header="Harga" style="min-width: 50px">
                        <template #body="slotProps">
                        {{  H.formatRp(slotProps.data.listobat.harga,'Rp')  }}
                        </template>
                      </Column>
                      <template #empty >  {{ H.assets().notFound }} </template>
                    </DataTable>
                  </div>
                </div>
              </TabPanel>
              <TabPanel header="Monitoring">
                  <div class="columns is-multiline">
                    <div class="column is-12">
                      <div class="columns is-multiline">
                        <div class="column is-12">
                          <div class="columns is-multiline">
                            <div class="column is-3">
                            <VField v-slot="{ id }" class="is-autocomplete-select">
                              <VLabel>Jenis Obat</VLabel>
                              <VControl icon="feather:search">
                                <Multiselect v-model="item.jenisObatKlaim" :attrs="{ id }" :options="optionJenisObat" placeholder="Pilih Jenis obat"
                                  :searchable="true"/>
                              </VControl>
                              </VField>
                            </div>
                            <div class="column is-3">
                            <VField v-slot="{ id }" class="is-autocomplete-select">
                              <VLabel>Status Klaim</VLabel>
                              <VControl icon="feather:search">
                                <Multiselect v-model="item.statusklaim" :attrs="{ id }" :options="status" placeholder="Pilih status klaim"
                                  :searchable="true"/>
                              </VControl>
                              </VField>
                            </div>
                            <div class="column is-3">
                              <VField class=" is-rounded-select is-autocomplete-select">
                                <VLabel>Periode</VLabel>
                                <VControl>
                                  <Calendar v-model="item.tglklaim" view="month" dateFormat="mm/yy" style="width: 100%" />
                                </VControl>
                              </VField>
                            </div>
                            <div class="column mt-5">
                              <VIconButton color="primary" circle icon="fas fa-search" outlined raised
                                @click="fetchMonitoring()" :loading="dataSourceMonitoring.loading" v-tooltip.bottom.left="'Cari'">
                              </VIconButton>
                            </div>
                          </div>
                          <div class="column is-12" v-if="dataSourceMonitoring.loading">
                            <VPlaceloadWrap>
                              <VPlaceload height="500px" width="100%" class="mx-2" />
                            </VPlaceloadWrap>
                          </div>
                          <div class="column is-12" v-else>
                            <DataTable v-model:filters="filters" dataKey="no"
                              :globalFilterFields="['peserta']['nama']"  :value="dataSourceMonitoring"
                              class="p-datatable-sm"
                                :paginator="true" :rows="10" :rowsPerPageOptions="[5, 10, 25]" scrollable
                                paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
                                responsiveLayout="stack" breakpoint="960px" sortMode="multiple"
                                currentPageReportTemplate="Showing {first} to {last} of {totalRecords}" showGridlines
                              >
                              <template #header>
                                <div class="flex justify-content-between">
                                  <span class="p-input-icon-left">
                                    <InputText v-model="filters['global'].value" placeholder="Search" />
                                  </span>
                                </div>
                              </template>
                              <Column field="no" header="No"></Column>
                              <Column field="nokartu" style="min-width: 120px" header="No Kartu"></Column>
                              <Column field="namapeserta" style="min-width: 200px" header="Nama Pasien"></Column>
                              <Column field="noresep" style="min-width: 50px" header="No Resep"></Column>
                              <Column field="jnsobat" style="min-width: 120px" header="Jenis Obat"></Column>
                              <Column header="Tanggal Pelayanan" style="min-width: 100px">
                                <template #body="slotProps">
                                {{  H.formatDate(slotProps.data.tglpelayanan,H.dateTimeFormat().genaral.date)  }}
                                </template>
                              </Column>
                              <Column header="Biaya Pengajuan" style="min-width: 80px">
                                <template #body="slotProps">
                                {{  H.formatRp(slotProps.data.biayapengajuan,'Rp')  }}
                                </template>
                              </Column>
                              <Column header="Biaya Setuju" style="min-width: 80px">
                                <template #body="slotProps">
                                {{  H.formatRp(slotProps.data.biayasetuju,'Rp')  }}
                                </template>
                              </Column>
                              <template #empty >  {{ H.assets().notFound }} </template>
                            </DataTable>
                            <div class="column is-12">
                                <div class="content">
                                    <div class="is-divider" data-content="Total Keseluruhan" />
                                </div>
                            </div>
                            <div class="column is-12 p-0">
                              <div class="columns is-multiline">
                                <div class="column is-4" style="margin-left: auto;">
                                    <VCardCustom :style="'padding:5px 25px'">
                                        <div class="label-status" color="success">
                                            <i aria-hidden="true" class="fas fa-circle"></i>
                                            <span class="ml-1">TOTAL PENGAJUAN</span>
                                        </div>
                                        <small class="text-bold-custom h-100">{{
                                            H.formatRp(item.totalPengajuan, 'Rp.')}}</small>
                                    </VCardCustom>
                                </div>
                                <div class="column is-4" style="margin-left: auto;">
                                    <VCardCustom :style="'padding:5px 25px'">
                                        <div class="label-status" color="danger">
                                            <i aria-hidden="true" class="fas fa-circle"></i>
                                            <span class="ml-1">TOTAL DISETUJUHI</span>
                                        </div>
                                        <small class="text-bold-custom h-100">{{
                                            H.formatRp(item.diSetujui, 'Rp.')}}</small>
                                    </VCardCustom>
                                </div>
                                <div class="column is-4" style="margin-left: auto;">
                                    <VCardCustom :style="'padding:5px 25px'">
                                        <div class="label-status" color="info">
                                            <i aria-hidden="true" class="fas fa-circle"></i>
                                            <span class="ml-1">TOTAL PASIEN</span>
                                        </div>
                                        <small class="text-bold-custom h-100">
                                          {{ item.totalPasien }}</small>
                                    </VCardCustom>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
              </TabPanel>
            </TabView>
          </div>
        </div>
    </VCard>
</template>
<script setup lang="ts">
import { useRoute, useRouter } from 'vue-router'
import { ref, computed, watch, reactive } from 'vue'
import * as H from '/@src/utils/appHelper'
import { useApi } from '/@src/composable/useApi'
import { useHead } from '@vueuse/head'
import Calendar from 'primevue/calendar'
import TabView from 'primevue/tabview';
import TabPanel from 'primevue/tabpanel';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import ColumnGroup from 'primevue/columngroup';
import InputText from 'primevue/inputtext';
import { FilterMatchMode } from 'primevue/api'

useHead({
  title: import.meta.env.VITE_PROJECT,
})
const optionJenisObat: any = [
    { value: 0, label:'Semua' },
    { value: 1, label:'Obat PRB' },
    { value: 2, label:'Obat Kronis Blm Stabil' },
    { value: 3, label:'Obat Kemoterapi' }
]
const status: any = [
    { value: 1, label:'Belum diverifikasi' },
    { value: 2, label:'Sudah Verifikasi' },
]
const item: any = reactive({
    tglklaim: new Date(),
    qPeriodePelayanan : [new Date() , new Date()],
    qPeriodeResep : [new Date() , new Date()],
    qPeriodePelayananObat : [new Date() , new Date()],
    filterDate: {
        start: new Date(),
        end: new Date()
    },
    jenisObat:1,
    statusklaim : status[0].value,
    jenisObatKlaim : optionJenisObat[0].value

})

const filters = ref({
  global: { value: null, matchMode: FilterMatchMode.CONTAINS },

});
const expandedRows = ref([]);
const isLoading:any = ref(false)
const dataSourcePelayananObat :any = ref([])
const dataSourceResep : any = ref([]);
const dataSource:any = ref([])
const dataSourceKlaim:any = ref([])
const dataSourceObat:any = ref([])
const dataSourceMonitoring:any = ref([])

const fetchRiwayatPelayananObat =async ()=>{
  if (!item.noKartu) {
    H.alert("warning" ,"No Kartu Harus Disis !");
    return;
  }
  let startDate = ''
  let endDate = ''
  dataSourcePelayananObat.value.loading = true
  if (item.qPeriodeResep) {
    if (item.qPeriodeResep[0]) {
      startDate = H.formatDate(item.qPeriodeResep[0], 'YYYY-MM-DD')
    }
    if (item.qPeriodePelayanan[1]) {
      endDate = H.formatDate(item.qPeriodeResep[1], 'YYYY-MM-DD')
    } else {
      endDate = H.formatDate(item.qPeriodeResep[0], 'YYYY-MM-DD')
    }
  }
  var json = {
    "url": `/riwayatobat/${startDate}/${endDate}/${item.noKartu}`,
    "jenis": "apotik",
    "method": "GET",
    "data": null
  }
  try{
    const {response} = await useApi().postBPJS(`/bridging/bpjs/tools`, json);
    response.list.map((element:any ,index:number)=>{
      element.no = index + 1
    })
    if (!response) {
      return;
    }
    dataSourcePelayananObat.value = response.list
  }catch(e :any){
    H.alert("error" , e.data.metaData.message)
    dataSourcePelayananObat.value.loading = false
  }
  dataSourcePelayananObat.value.loading = false
}
const fetchDaftarResep = async ()=>{
  if (!item.jenisObat) {
    H.alert("warning" ,"Jenis Obat Harus Disis !");
    return;
  }
  dataSourceResep.value.loading = true
  let tglMulai = ''
  let tglAkhir = ''
  isLoading.value = true;
  if (item.qPeriodeResep) {
    if (item.qPeriodeResep[0]) {
      tglMulai = H.formatDate(item.qPeriodeResep[0], 'YYYY-MM-DD HH:mm:ss')
    }
    if (item.qPeriodeResep[1]) {
      tglAkhir = H.formatDate(item.qPeriodeResep[1], 'YYYY-MM-DD HH:mm:ss')
    } else {
      tglAkhir = H.formatDate(item.qPeriodeResep[0], 'YYYY-MM-DD HH:mm:ss')
    }
  }
  var json = {
    url: `/daftarresep`,
    jenis: "apotik",
    method: "POST",
    data: {
        kdppk :"0903A022",
        KdJnsObat : item.jenisObat,
        JnsTgl : "TGLPELSJP",
        TglMulai : tglMulai,
        TglAkhir : tglAkhir
    }
  }
  try {
    const response = await useApi().postBPJS(`/bridging/bpjs/tools`, json);
    if (!response) {
      return;
    }
    isLoading.value = false
    dataSourceResep.value.loading = false
  }catch (e :any) {
    H.alert("error",e.message);
    isLoading.value = false
    dataSourceResep.value.loading = false
  }
}
const fetchPelayananObat =async ()=>{
  if (!item.nosep) {
    H.alert("warning" ,"NO SEP Harus Disis  !");
    return;
  }
  var json = {
    "url": `/obat/daftar/${item.nosep}`,
    "jenis": "apotik",
    "method": "GET",
    "data": null
  }
  dataSourceObat.value.loading = true;
  try {
    const response = await useApi().postBPJS(`/bridging/bpjs/tools`, json);
    if (!response) {
      return;
    }
  response.detailsep.map((element:any,index:number)=>{
    element.no = index + 1;
  })
  dataSourceObat.value.loading =false;
  dataSourceObat.value =response;
  }catch (e :any) {
  H.alert("error",e.message);
  dataSourceObat.value.loading =false;
  }
}
const fetchMonitoring =async ()=>{
  if (!item.statusklaim) {
    H.alert("warning" ,"Status Klaim Harus Disis !");
    return;
  }
  if (!item.jenisObatKlaim && item.jenisObatKlaim != 0) {
    H.alert("warning" ,"Jenis Obat Harus Disis !");
    return;
  }
  dataSourceMonitoring.value.loading = true
  let bulan = ''
  let tahun = ''
  isLoading.value = true;
  if (item.tglklaim) {
    if (item.tglklaim) {
      bulan = H.formatDate(item.tglklaim,'M')
      tahun = H.formatDate(item.tglklaim,'YYYY')
    }
  }
  var json = {
    "url": `/monitoring/klaim/${bulan}/${tahun}/${item.jenisObatKlaim}/${item.statusklaim}`,
    "jenis": "apotik",
    "method": "GET",
    "data": null
  }
  try {
  const response = await useApi().postBPJS(`/bridging/bpjs/tools`, json);
  if (!response) {
      return;
  }
  response.rekap.map((element:any,index:number) =>{
      element.no = index+1
  })
  dataSourceMonitoring.value = response.rekap
  dataSourceMonitoring.value.loading =false;
  item.totalPasien = response.rekap.jumlahdata
  item.diSetujui = response.rekap.totalbiayasetuju
  item.totalPengajuan = response.rekap.totalbiayapengajuan
  }catch (e :any) {
    H.alert("error",e.data.metaData.message);
    dataSourceMonitoring.value.loading =false;
  }
}
const onRowExpand = (event:any) => {
    expandedRows.value = dataSource.value.filter((p:any) => p.no == event.data.no);
}
const onRowCollapse = (event :any) => {
    expandedRows.value = []
}
</script>
