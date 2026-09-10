<template>
  <section>
    <VCard radius="smooth" elevated class="mb-3-min br-16">
      <div class="columns is-multiline">
        <div class="column is-3">
          <VIconButton icon="feather:arrow-left" light dark-outlined @click="backPage()" />
          <span class="title-emr ml-3">Rujukan</span>
        </div>
      </div>
      <div class="columns is-multiline">
        <div class="column is-12">
          <TabView v-model:activeIndex="activeIdx">
            <TabPanel header="List Rujukan">
              <div class="columns is-multiline">

                <div class="column is-3">
                  <VField>
                    <VLabel>Tanggal</VLabel>
                    <VDatePicker v-model="input.filterTgl" is-range color="pink" trim-weeks>
                      <template #default="{ inputValue, inputEvents }">
                        <VField addons>
                          <VControl icon="feather:calendar">
                            <VInput :value="inputValue.start" v-on="inputEvents.start" />
                          </VControl>
                          <VControl>
                            <VButton static><i class="fas fa-arrow-right" aria-hidden="true"></i></VButton>
                          </VControl>
                          <VControl icon="feather:calendar">
                            <VInput :value="inputValue.end" v-on="inputEvents.end" />
                          </VControl>
                        </VField>
                      </template>
                    </VDatePicker>

                  </VField>
                </div>
                <div class="column is-2">
                  <VIconButton icon="feather:search" @click="cariSKD" color="success" raised :loading="isLoadingSKD"
                    circle class="mt-5">
                  </VIconButton>
                </div>
                <div class="column is-12">
                  <DataTable :value="dataSourceSPRI" paginator :rows="10" :rowsPerPageOptions="[5, 10, 20, 50]"
                    v-model:filters="filters"
                    :globalFilterFields="['noRujukan', 'jnsPelayanan', 'namaPpkDirujuk','noSep', 'nama', 'noKartu','ppkDirujuk']"
                    filterDisplay="menu" tableStyle="min-width: 50rem">
                    <template #header>
                      <div class="flex justify-content-end">
                        <span class="p-input-icon-left">

                          <InputText v-model="filters.global.value" placeholder="Keyword Search" style="width:300px" />
                        </span>
                      </div>
                    </template>
                    <template #empty> No data found. </template>
                    <Column header="Aksi" style="width: 220px">
                      <template #body="slotProps">
                        <VIconButton icon="feather:printer" @click="cetak(slotProps.data)" color="info" raised circle
                          :loading="slotProps.data.isLoading" class="mr-2">
                        </VIconButton>
                        <VIconButton icon="feather:edit" @click="edit(slotProps.data)" color="warning" raised circle
                          class="mr-2">
                        </VIconButton>
                        <VIconButton icon="feather:trash" @click="hapus(slotProps.data.noSuratKontrol)" color="danger"
                          raised circle class="mr-2">
                        </VIconButton>
                      </template>
                    </Column>
                    <Column field="noRujukan" header="No Rujukan" style="width: 150px"></Column>
                    <Column field="tglRujukan" header="Tgl Rujukan" style="width: 150px"> </Column>
                    <Column field="jnsPelayanan" header="Jenis Pelayanan" style="width: 150px">
                      <template #body="slotProps">
                        <span>{{ slotProps.data.jnsPelayanan == '1' ? 'Rawat Inap' : 'Rawat Jalan' }}</span>
                      </template>
                    </Column>
                    <Column field="noSep" header="No SEP" style="width:150px"></Column>
                    <Column field="ppkDirujuk" header="Kode PPK" style="width: 150px"></Column>
                    <Column field="namaPpkDirujuk" header="PPK Dirujuk" style="width: 150px"></Column>
                    <Column field="noKartu" header="No Kartu" style="width: 150px"></Column>
                    <Column field="nama" header="Nama Lengkap" style="width: 150px"></Column>

                  </DataTable>
                </div>
              </div>
            </TabPanel>
            <TabPanel header="Buat Baru">
              <div class="columns is-multiline" v-if="!showInput">
                <div class="column is-3">
                  <VDatePicker v-model="input.filterTgl2" color="green" trim-weeks>
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
                  <VControl>
                    <VLabel class="required-field">Jenis Pelayanan</VLabel>
                    <div class="columns is-multiline">
                      <div class="column is-6">
                        <VRadio v-model="input.jenisPel" :value="1" :label="'Rawat Inap'" name="jenisKntrl" square
                          color="primary" />
                      </div>
                      <div class="column is-6">
                        <VRadio v-model="input.jenisPel" :value="2" :label="'Rawat Jalan'" name="jenisKntrl" square
                          color="primary" />
                      </div>
                    </div>
                  </VControl>
                </div>
                <div class="column is-2">
                  <VIconButton icon="feather:search" @click="cariMonitor" color="success" raised :loading="isLoadingSKD"
                    circle class="mt-5">
                  </VIconButton>
                </div>
                <div class="column is-12">
                  <DataTable :value="dataSourceMon" paginator :rows="10" :rowsPerPageOptions="[5, 10, 20, 50]"
                    v-model:filters="filters" :globalFilterFields="['noSep', 'nama', 'noKartu']" filterDisplay="menu"
                    tableStyle="min-width: 50rem">
                    <template #header>
                      <div class="flex justify-content-end">
                        <span class="p-input-icon-left">

                          <InputText v-model="filters.global.value" placeholder="Keyword Search" style="width:300px" />
                        </span>
                      </div>
                    </template>
                    <template #empty> No data found. </template>

                    <Column field="noSep" header="No SEP" style="width: 150px">
                      <template #body="slotProps">
                        <VButton color="info" raised @click="setSEP(slotProps.data)" :loading="slotProps.data.isLoading">
                          <span class="icon">
                            <i aria-hidden="true" class="fas fa-plus"></i>
                          </span>
                          <span>{{ slotProps.data.noSep }}</span>
                        </VButton>
                      </template>
                    </Column>
                    <Column field="tglSep" header="Tgl SEP" style="width: 150px"> </Column>
                    <Column field="tglPlgSep" header="Tgl Pulang" style="width: 150px"> </Column>
                    <Column field="jnsPelayanan" header="Jenis Pelayanan" style="width: 150px"> </Column>
                    <Column field="noKartu" header="No Kartu" style="width: 150px"></Column>
                    <Column field="nama" header="Nama Lengkap" style="width: 150px"></Column>
                    <Column field="poli" header="Poli" style="width: 150px"></Column>
                    <Column field="diagnosa" header="Diagnosa" style="width: 150px"></Column>
                    <Column field="noRujukan" header="No Rujukan" style="width: 150px"></Column>
                  </DataTable>
                </div>
              </div>
              <div class="columns is-multiline" v-if="showInput">

                <div class="column is-12">
                  <div class="columns is-multiline">
                    <div class="column is-4">

                      <div class="s-card mt-5" style=" border-top: 3px solid var(--danger);">
                        <h3 class="title is-5 ">
                          <span> {{ sep.peserta.nama }}</span>

                        </h3>
                        <!-- <p style=" font-size: 1rem;" class="mt-3-min"> NO RM : {{ sep.peserta.noKartu }}</p> -->

                        <div class="boxx boxx-widget widget-user-2">


                          <ul class="list-group list-group-unbordered">
                            <li class="list-group-item">
                              <span class="fa fa-user"></span> <a title="Tanggal Lahir" class="pull-right-container">{{
                                sep.peserta.noKartu }}</a>
                            </li>
                            <li class="list-group-item">
                              <span class="fa fa-calendar"></span> <a title="Tanggal Lahir"
                                class="pull-right-container">{{ sep.peserta.tglLahir }}</a>
                            </li>
                            <li class="list-group-item">
                              <span class="fa fa-info-circle"></span> <a title="PISA" class="pull-right-container">{{
                                sep.peserta.sex
                              }}</a>
                            </li>
                            <li class="list-group-item">
                              <span class="fas fa-hospital-user"></span> <a title="Hak Kelas Rawat"
                                class="pull-right-container">{{ sep.peserta.hakKelas.keterangan }} </a>

                            </li>
                            <li class="list-group-item">
                              <span class="fa fa-stethoscope"></span> <a title="Faskes Tingkat 1"
                                class="pull-right-container">{{ sep.peserta.provUmum.kdProvider }} - {{
                                  sep.peserta.provUmum.nmProvider }}</a>

                            </li>

                          </ul>

                        </div>
                      </div>
                      <div class="s-card mt-2" style=" border-top: 3px solid var(--info);">
                        <h3 class="title is-5 ">
                          <span> SEP</span>

                        </h3>

                        <div class="boxx boxx-widget widget-user-2">

                          <ul class="list-group list-group-unbordered">
                            <li class="list-group-item">
                              <span class="fa fa-address-card"></span> <a title="NIK" class="pull-right-container">{{
                                sep.noSep }} </a>
                            </li>
                            <li class="list-group-item">
                              <span class="fa fa-calendar"></span> <a title="Tgl.SEP" class="pull-right-container">{{
                                sep.tglSep }} </a>
                            </li>
                            <li class="list-group-item">
                              <span class="fa fa-medkit"></span> <a title="Tanggal Lahir" class="pull-right-container">{{
                                sep.jnsPelayanan }}</a>
                            </li>
                            <li class="list-group-item">
                              <span class="fa fa-heartbeat"></span> <a title="Diag" class="pull-right-container">{{
                                sep.diagnosa
                              }}</a>
                            </li>

                          </ul>

                        </div>
                      </div>
                    </div>
                    <div class="column is-8">
                      <div class="s-card mt-5 p-6" style=" border-top: 3px solid var(--orange);">
                        <h3 class="title is-5 head-sep ml-1">
                          <span v-if="noRujukan"> {{ 'No Rujukan : ' + noRujukan }}</span>
                        </h3>
                        <VField horizontal label="Tgl. Rujukan">
                          <Calendar v-model="input.tglRujukan" selectionMode="single" :manualInput="true"
                            style="width: 50%;" :showIcon="true" :showTime="false" hourFormat="24"
                            :date-format="'yy-mm-dd'" />

                        </VField>
                        <VField horizontal label="Pelayanan" required>
                          <VControl>
                            <div class="columns is-multiline">
                              <div class="column is-6">
                                <VRadio v-model="input.jnsPelayanan" :value="1" :label="'Rawat Inap'" name="jenisKntrl2"
                                  square color="primary" />
                              </div>
                              <div class="column is-6">
                                <VRadio v-model="input.jnsPelayanan" :value="2" :label="'Rawat Jalan'" name="jenisKntrl2"
                                  square color="primary" />
                              </div>
                            </div>
                          </VControl>
                        </VField>

                        <VField horizontal label="Tipe" required>
                          <VControl>
                            <div class="columns is-multiline">
                              <div class="column is-4">
                                <VRadio v-model="input.tipeRujukan" :value="0" :label="'Penuh'" name="jenisKntrl" square
                                  color="primary" />
                              </div>
                              <div class="column is-4">
                                <VRadio v-model="input.tipeRujukan" :value="1" :label="'Partial '" name="jenisKntrl"
                                  square color="primary" />
                              </div>
                              <div class="column is-4">
                                <VRadio v-model="input.tipeRujukan" :value="2" :label="'Rujuk Balik '" name="jenisKntrl"
                                  square color="primary" />
                              </div>
                            </div>
                          </VControl>
                        </VField>

                        <VField horizontal label="Diagnosa" class="is-rounded-select_Z  is-autocomplete-select"
                          v-slot="{ id }">
                          <VControl icon="fas fa-diagnoses" fullwidth class="prime-auto">
                            <AutoComplete v-model="input.diagRujukan" :suggestions="d_Diagnosa"
                              @complete="fetchDiagnosa($event)" :optionLabel="'nama'" :dropdown="true" :minLength="3"
                              :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'nama'"
                              placeholder="ketik diagnosa" />
                          </VControl>
                        </VField>

                        <VField horizontal label="Di Rujuk ke" required>
                          <VControl icon="fas fa-ambulance" fullwidth>
                            <VInput type="text" placeholder="Di Rujuk ke" autocomplete="off" v-model="input.ppkDirujuk"
                              disabled />
                          </VControl>
                          <VIconButton class="ml-3" type="button" color="info" rounded outlined raised
                            v-if="input.tipeRujukan == 0 || input.tipeRujukan == 1" icon="feather:briefcase"
                            :loading="isLoadingSKDP" @click="cariFaskes()"> </VIconButton>
                        </VField>
                        <VField horizontal label="Spesialis/SubSpesialis" required v-if="input.tipeRujukan == 0">
                          <VControl icon="fas fa-home" fullwidth>
                            <VInput type="text" placeholder="Spesialis/SubSpesialis" autocomplete="off"
                              v-model="input.poliRujukan" disabled />
                          </VControl>
                        </VField>
                        <VField horizontal label="Catatan" required v-if="input.tipeRujukan == 0">
                          <VControl icon="feather:file-text" fullwidth>
                            <VInput type="text" placeholder="Catatan" autocomplete="off" v-model="input.catatan" />
                          </VControl>
                        </VField>
                      </div>
                      <div class="mt-2 is-pulled-right">
                        <VButton icon="lnir lnir-arrow-left rem-100" light dark-outlined @click="batal()">
                          Batal
                        </VButton>
                        <VButton type="button" color="warning" class="ml-2" rounded outlined raised icon="feather:printer"
                          @click="cetak({ noRujukan: noRujukan })" v-if="noRujukan" :loading="isLoadingCetak">
                          Cetak </VButton>
                        <VButton type="button" color="danger" class="ml-2" rounded outlined raised icon="feather:trash"
                          v-if="noRujukan" @click="hapus({ noRujukan: noRujukan })">
                          Hapus </VButton>
                        <VButton type="button" color="primary" class="ml-2" rounded outlined raised icon="feather:save"
                          :loading="isLoading" @click="save()"> {{ noRujukan ? 'Edit' : 'Simpan' }} </VButton>
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
    <Dialog v-model:visible="popJadwal" modal header="Jadwal Praktek dan Sarana Rumah Sakit Rujukan"
      :style="{ width: '50vw' }">
      <div class="columns is-multiline">
        <div class="column is-5">
          <VField label="PPK Rujuk" class="is-rounded-select_Z  is-autocomplete-select" v-slot="{ id }">
            <VControl icon="fas fa-hospital" fullwidth class="prime-auto">
              <AutoComplete v-model="jadwal.ppkDirujuk" :suggestions="listFaskes" @complete="filterAutoCom($event)"
                :optionLabel="'nama'" :dropdown="true" :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                :field="'nama'" placeholder="ketik PPK" />
            </VControl>
          </VField>
        </div>
        <div class="column is-5">
          <VDatePicker v-model="jadwal.tglRencanaKunjungan" color="green" trim-weeks>
            <template #default="{ inputValue, inputEvents }">
              <VField>
                <VLabel>Tgl.Rencana Rujukan</VLabel>
                <VControl icon="feather:calendar">
                  <VInput type="text" placeholder="Select a date" :value="inputValue" v-on="inputEvents" />
                </VControl>
              </VField>
            </template>
          </VDatePicker>
        </div>
        <div class="column is-2">
          <VIconButton icon="feather:search" @click="cariJadwal" color="success" raised :loading="isLoading" circle
            class="mt-5">
          </VIconButton>
        </div>
        <div class="column is-12">
          <TabView>
            <TabPanel>
              <template #header>
                <div class="flex align-items-center gap-2">
                  <i class="fas fa-th-list"></i>
                  <span class="font-bold white-space-nowrap">Spesialis/SubSpesialis</span>
                </div>
              </template>
              <div class="columns is-multiline">

                <div class="column is-12">
                  <DataTable :value="dataSourceSub" paginator :rows="5" :rowsPerPageOptions="[5, 10, 20, 50]"
                    tableStyle="min-width: 50rem">
                    <Column header="Pilih Data" style="width: 100px">
                      <template #body="slotProps">
                        <VIconButton icon="feather:check-circle" @click="pakeSub(slotProps.data)" color="info" raised
                          circle class="mr-2">
                        </VIconButton>
                      </template>
                    </Column>
                    <Column field="namaSpesialis" header="Nama Spesialis/Sub" style="width: 150px"></Column>
                    <Column field="kapasitas" header="Kapasitas" style="width: 150px"></Column>
                    <Column field="jumlahRujukan" header="Jml Rujukan" style="width: 150px">
                    </Column>
                    <Column field="persentase" header="Presentase" style="width: 150px"></Column>

                  </DataTable>
                </div>
              </div>
            </TabPanel>
            <TabPanel>
              <template #header>
                <div class="flex align-items-center gap-2">
                  <i class="fas fa-briefcase"></i>
                  <span class="font-bold white-space-nowrap">Sarana</span>
                </div>
              </template>
              <div class="columns is-multiline">
                <div class="column is-12">
                  <DataTable :value="dataSourceSarana" paginator :rows="5" :rowsPerPageOptions="[5, 10, 20, 50]"
                    tableStyle="min-width: 50rem">
                    <Column field="namaSarana" header="No Surat" style="width: 150px"></Column>
                    <Column field="kodeSarana" header="Jenis" style="width: 150px"></Column>
                  </DataTable>
                </div>
              </div>
            </TabPanel>

          </TabView>
        </div>
      </div>

    </Dialog>
  </section>
</template>
<script setup lang="ts">
import { ref, reactive, watch } from 'vue'
import { useRoute, useRouter, Router, } from 'vue-router'
import { useHead } from '@vueuse/head'
import * as H from '/@src/utils/appHelper'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import TabView from 'primevue/tabview';
import TabPanel from 'primevue/tabpanel';
import Dropdown from 'primevue/dropdown';
import DataTable from 'primevue/datatable';
import Dialog from 'primevue/dialog';
import Column from 'primevue/column';
import AutoComplete from 'primevue/autocomplete';
import Calendar from 'primevue/calendar';
import InputText from 'primevue/inputtext';
import { useApi } from '/@src/composable/useApi'
import { FilterMatchMode } from 'primevue/api';
import sleep from '/@src/utils/sleep'
useHead({
  title: 'Rencana Kontrol - ' + import.meta.env.VITE_PROJECT,
})
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)
const route = useRoute()
const activeIdx: any = ref(0)
const namappkRumahSakit = ref('')
const filters: any = ref({
  'global': { value: null, matchMode: FilterMatchMode.CONTAINS },
})
const d_Filter: any = ref([{ kode: 2, nama: 'Tgl Rencana Kontrol' }, { kode: 1, nama: 'Tgl Entri' }])
const input: any = ref({
  filterTgl: reactive({
    start: new Date(),
    end: new Date(),
  }),
  jenisPel: 2,
  filterTgl2: new Date(),
  filter: d_Filter.value[0],
  jenis: 1,
  tglRujukan: new Date(),
  tipeRujukan: "0",
  jnsPelayanan: 1,
  tglRencanaKunjungan: new Date(),
  // noKartu: '0000451793316',
  // noSEP: '1019R0010923V014504',
  tglRencanaKontrol: new Date()
})
const listFaskes: any = ref([])
const rujukan: any = ref({
  rujukFaskes: ''
})
const jadwal: any = ref({
  tglRencanaKunjungan: new Date()
})
const sep: any = ref({
  peserta: {
    hakKelas: {},
    jenisPeserta: {},
    mr: {},
    provUmum: {},
    statusPeserta: {},
    cob: {}
  }
})
const d_Diagnosa: any = ref([])
const listPel: any = ref([
  { kode: 1, nama: 'Rawat Inap' },
  { kode: 2, nama: 'Rawat Jalan' }
])
const popJadwal = ref(false)
const noSuratKontrol = ref()
const isLoadingPasien = ref(false)
const item: any = ref({})
const isLoadingSKD: any = ref(false)
const pasien: any = ref({})
const peserta: any = ref({
  hakKelas: {},
  jenisPeserta: {},
  mr: {},
  provUmum: {},
  statusPeserta: {},
  cob: {}
})
const showInput: any = ref(false)
const noRujukan = ref()
const isLoadingCetak: any = ref(false)
const isLoading: any = ref(false)
const d_Bulan: any = ref(H.monthList())
const d_Tahun: any = ref(H.yearList())
const d_Subspesialis: any = ref([])
const d_dpjpLayan: any = ref([])
const dataSourceSPRI: any = ref([])
const dataSourceMon: any = ref([])
const dataSourceSub: any = ref([])
const dataSourceSarana: any = ref([])
const props = defineProps({
    registrasi: {
        type: Object as PropType<any>,
    },
    pasien: {
        type: Object as PropType<any>,
    },
    selected: undefined,
    type: undefined,
    align: undefined,
})

const setAutoFill = () => {
  if(props.pasien){
    filters.value.global.value = props.pasien.nobpjs
  }
}

const cariMonitor = () => {
  let json = {
    "url": `Monitoring/Kunjungan/Tanggal/${H.formatDate(input.value.filterTgl2, 'YYYY-MM-DD')}/JnsPelayanan/${input.value.jenisPel}`,
    "method": "GET",
    "data": null
  }
  isLoadingSKD.value = true
  useApi().postBPJS('/bridging/bpjs/tools', json).then((x) => {
    isLoadingSKD.value = false
    if (x.metaData.code == 200) {
      dataSourceMon.value = x.response.sep
    } else {
      H.alert('error', x.metaData.message)
    }
  })

}
const cariSKD = () => {

  let json = {
    "url": `Rujukan/Keluar/List/tglMulai/${H.formatDate(input.value.filterTgl.start, 'YYYY-MM-DD')}/tglAkhir/${H.formatDate(input.value.filterTgl.end, 'YYYY-MM-DD')}`,
    "method": "GET",
    "data": null
  }
  isLoadingSKD.value = true
  useApi().postBPJS('/bridging/bpjs/tools', json).then((x) => {
    isLoadingSKD.value = false
    if (x.metaData.code == 200) {
      dataSourceSPRI.value = x.response.list
    } else {
      H.alert('error', x.metaData.message)
    }
  })
  useApi().postBPJS('/bridging/bpjs/tools', json).then((x) => {
    isLoadingSKD.value = false
    if (x.metaData.code == 200) {
      dataSourceSPRI.value = x.response.list
    } else {
      H.alert('error', x.metaData.message)
    }
  })
}
const filterAutoCom = (event) => {
  if (event.query == '') return
  if (event.query.length < 3) return
  let json = {
    "url": "referensi/faskes/" + encodeURIComponent(event.query) + "/2",
    "method": "GET",
    "data": null
  }
  useApi().postBPJS('/bridging/bpjs/tools', json).then((e) => {
    if (e.metaData.code == "200") {
      listFaskes.value = e.response.faskes
    } else {
      H.alert('error', e.metaData.message)
    }
  })
}

const edit = async (e: any) => {

  activeIdx.value = 1
  let json = {
    "url": `Rujukan/Keluar/${e.noRujukan}`,
    "method": "GET",
    "data": null
  }
  e.isLoading = true
  let response = await useApi().postBPJS('/bridging/bpjs/tools', json)
  e.isLoading = false

  showInput.value = true
  let res = response.response.rujukan
  input.value.tipe = res.tipeRujukan
  noRujukan.value = res.noRujukan
  setSEP({ noSep: res.noSep })
  input.value.noSep = res.noSep
  input.value.noRujukan = res.noRujukan
  input.value.tglRencanaKunjungan = new Date(res.tglRencanaKunjungan)
  input.value.tglRujukan = new Date(res.tglRujukan)
  input.value.ppkDirujuk = res.ppkDirujuk + "~" + res.namaPpkDirujuk
  input.value.poliRujukan = res.namaPoliRujukan
  input.value.catatan = res.catatan
  input.value.diagRujukan = { kode: res.diagRujukan , nama: res.namaDiagRujukan }

}
const cetak = async (e: any) => {
  let json = {
    "url": `Rujukan/Keluar/${e.noRujukan}`,
    "method": "GET",
    "data": null
  }
  e.isLoading = true
  let response = await useApi().postBPJS('/bridging/bpjs/tools', json)
  e.isLoading = false

  H.printBlade('emr/cetak-rujukan?noRujukan=' + response.response.rujukan.noRujukan
    + '&tglRujukan=' + response.response.rujukan.tglRujukan
    + '&noKartu=' + response.response.rujukan.noKartu
    + '&nama=' + response.response.rujukan.nama
    + '&tglLahir=' + response.response.rujukan.tglLahir
    + '&namaPpkDirujuk=' + response.response.rujukan.namaPpkDirujuk
    + '&poliTujuan=' + response.response.rujukan.namaPoliRujukan
    + '&sex=' + response.response.rujukan.kelamin
    + '&diagnosa=' + (response.response.rujukan.diagRujukan + ' - ' + response.response.rujukan.namaDiagRujukan)
    + '&catatan=' + response.response.rujukan.catatan
    + '&tiperujukan=' + response.response.rujukan.namaTipeRujukan
    + '&jnsPelayanan=' + response.response.rujukan.jnsPelayanan
    + '&tglRencanaKunjungan=' + response.response.rujukan.tglRencanaKunjungan
  );
}
const cariPasien = () => {
  if (input.value.jenis == 1) {
    input.value.pelayanan = 'Rawat Inap'
  } else {
    input.value.pelayanan = 'Rawat Jalan'
  }
  let json: any = {}
  sep.value = {}
  if (input.value.jenis == 1) {
    json = {
      "url": `Peserta/nokartu/${input.value.noKartu.trim()}/tglSEP/${H.formatDate(new Date(), 'YYYY-MM-DD')}`,
      "method": "GET",
      "data": null
    }
    isLoadingPasien.value = true
    useApi().postBPJS('/bridging/bpjs/tools', json).then((x) => {
      isLoadingPasien.value = false
      if (x.metaData.code == 200) {
        peserta.value = x.response.peserta

      } else {
        H.alert('error', x.metaData.message)
      }
    })
  } else {
    json = {
      "url": ` /RencanaKontrol/nosep/${input.value.noSEP.trim()}`,
      "method": "GET",
      "data": null
    }
    isLoadingPasien.value = true

    useApi().postBPJS('/bridging/bpjs/tools', json).then((x) => {
      isLoadingPasien.value = false
      if (x.metaData.code == 200) {
        sep.value = x.response
        let json2 = {
          "url": `Peserta/nokartu/${x.response.peserta.noKartu}/tglSEP/${H.formatDate(new Date(), 'YYYY-MM-DD')}`,
          "method": "GET",
          "data": null
        }
        useApi().postBPJS('/bridging/bpjs/tools', json2).then((xx) => {
          isLoadingPasien.value = false
          if (xx.metaData.code == 200) {
            peserta.value = xx.response.peserta

          } else {
            H.alert('error', xx.metaData.message)
          }
        })
      } else {
        H.alert('error', x.metaData.message)
      }
    })

  }


}

const cekTipe = (e) => {

  if (e == 2) {
    input.value.ppkDirujuk = sep.value.peserta.provUmum.kdProvider + "-" + sep.value.peserta.provUmum.nmProvider
  } else {
    input.value.ppkDirujuk = ''
  }
}
const fetchSubSpe = () => {
  let nomor = ''
  if (input.value.jenis == 1) {
    nomor = input.value.noKartu
  } else {
    nomor = input.value.noSEP
  }
  let json = {
    "url": `/RencanaKontrol/ListSpesialistik/JnsKontrol/${input.value.jenis}/nomor/${nomor}/TglRencanaKontrol/${H.formatDate(input.value.tglRencanaKontrol, 'YYYY-MM-DD')}`,
    "method": "GET",
    "data": null
  }


  // isLoadingPasien.value = true
  useApi().postBPJS('/bridging/bpjs/tools', json).then((x) => {
    if (x.metaData.code == 200) {
      d_Subspesialis.value = x.response.list

    } else {
      H.alert('error', x.metaData.message)
    }
  })
}
const fetchSupspesialis = async (filter: any) => {
  if (!filter.query) return
  if (filter.query.length < 3) return
  let json = {
    "url": "referensi/poli/" + encodeURI(filter.query),
    "method": "GET",
    "data": null
  }
  const res = await useApi().postBPJS(
    `/bridging/bpjs/tools`, json)
  if (res.metaData.code == 200) {
    d_Subspesialis.value = res.response.poli

  } else {
    H.alert('error', res.metaData.message)
  }
}
const changeSpe = async (e: any) => {
  let json = {
    "url": `/RencanaKontrol/JadwalPraktekDokter/JnsKontrol/${input.value.jenis}/KdPoli/${e.kode}/TglRencanaKontrol/${H.formatDate(input.value.tglRencanaKontrol, 'YYYY-MM-DD')}`,
    "method": "GET",
    "data": null
  }
  await useApi().postBPJS('/bridging/bpjs/tools', json).then((x) => {
    if (x.metaData.code == 200) {
      d_dpjpLayan.value = x.response.list
    } else {
      H.alert('error', x.metaData.message)
    }
  })
}
const save = async () => {

  var data = {};
  var url = ''
  var method = ''
  if (input.value.noRujukan == undefined) {
    url = 'Rujukan/2.0/insert'
    method = 'POST'
    data = {
      "request": {
        "t_rujukan": {
          "noSep": input.value.noSep,
          "tglRujukan": H.formatDate(input.value.tglRujukan, "YYYY-MM-DD"),
          "tglRencanaKunjungan": H.formatDate(input.value.tglRencanaKunjungan, "YYYY-MM-DD"),
          "ppkDirujuk": input.value.ppkDirujuk ? input.value.ppkDirujuk.split('~')[0] : "",
          "jnsPelayanan": input.value.jnsPelayanan,
          "catatan": input.value.catatan ? input.value.catatan : "",
          "diagRujukan": input.value.diagRujukan.kode,
          "tipeRujukan": input.value.tipeRujukan,
          "poliRujukan": input.value.poliRujukan ? input.value.poliRujukan.split('~')[0] : "",
          "user": H.namaPegawai()
        }
      }
    }
  } else {
    url = 'Rujukan/2.0/Update'
    method = 'PUT'
    data = {
      "request": {
        "t_rujukan": {
          "noRujukan": input.value.noRujukan,
          "tglRujukan": H.formatDate(input.value.tglRujukan, "YYYY-MM-DD"),
          "tglRencanaKunjungan": H.formatDate(input.value.tglRencanaKunjungan, "YYYY-MM-DD"),
          "ppkDirujuk": input.value.ppkDirujuk ? input.value.ppkDirujuk.split('~')[0] : "",
          "jnsPelayanan": input.value.jnsPelayanan,
          "catatan": input.value.catatan ? input.value.catatan : "",
          "diagRujukan": input.value.diagRujukan.kode,
          "tipeRujukan": input.value.tipeRujukan,
          "poliRujukan": input.value.poliRujukan ? input.value.poliRujukan.split('~')[0] : "",
          "user": H.namaPegawai()
        }
      }
    }
  }
  let json = {
    "url": url,
    "method": method,
    "data": data
  }
  isLoading.value = true
  let res = await useApi().postBPJS('/bridging/bpjs/tools', json)
  isLoading.value = false
  if (res.metaData.code == 200) {
    noRujukan.value = res.response.rujukan.noRujukan
    H.alert('success', res.metaData.message)
  } else {
    H.alert('error', res.metaData.message)
  }
}
const fetchDiagnosa = async (e: any) => {
  if (e.query == undefined || e.query == '') return
  d_Diagnosa.value = await apiReferensi(`referensi/diagnosa/${e.query}`, 'diagnosa')

}
const apiReferensi = async (url: any, balikan: any) => {
  let json = {
    "url": url,
    "method": "GET",
    "data": null
  }
  const res = await useApi().postBPJS(
    `/bridging/bpjs/tools`, json)
  if (res.metaData.code == 200) {
    return res.response[balikan]
    // .map((item: any) => {
    //     return { value: item.kode, label: item.nama, default: item }
    // })
  } else {
    H.alert('error', res.metaData.message)
    return []
  }
}
const cariFaskes = () => {
  popJadwal.value = true
  delete input.value.ppkDirujuk
  delete input.value.poliRujukan
}
const pakeSub = (e) => {
  input.value.ppkDirujuk = jadwal.value.ppkDirujuk.kode + "~" + jadwal.value.ppkDirujuk.nama
  input.value.poliRujukan = e.kodeSpesialis + "~" + e.namaSpesialis

  popJadwal.value = false
}
const cariJadwal = () => {
  if (!jadwal.value.ppkDirujuk) {
    H.alert('info', 'Pilih PPK Dirujuk')

    return
  }
  dataSourceSarana.value = []
  isLoading.value = true
  var json = { "url": "Rujukan/ListSarana/PPKRujukan/" + jadwal.value.ppkDirujuk.kode, "method": "GET", "data": null }
  useApi().postBPJS('/bridging/bpjs/tools', json).then((e) => {
    isLoading.value = false
    if (e.metaData.code == "200") {
      dataSourceSarana.value = e.response.list
    } else {
      H.alert('error', e.metaData.message)
    }
  })

  dataSourceSub.value = []
  var json = { "url": "Rujukan/ListSpesialistik/PPKRujukan/" + jadwal.value.ppkDirujuk.kode + "/TglRujukan/" + H.formatDate(input.value.tglRencanaKunjungan, 'YYYY-MM-DD'), "method": "GET", "data": null }
  useApi().postBPJS('/bridging/bpjs/tools', json).then((e) => {
    isLoading.value = false
    if (e.metaData.code == "200") {
      dataSourceSub.value = e.response.list
    } else {
      H.alert('error', e.metaData.message)
    }
  })
}
const insertRencanaKontrol = () => {
  let json = {}

  if (input.value.noSuratKontrol) {
    json = {
      "url": `/RencanaKontrol/Update`,
      "method": "PUT",
      "data": {
        "request": {
          "noSuratKontrol": input.value.noSuratKontrol,
          "noSEP": input.value.noSEP,
          "kodeDokter": input.value.kodeDokter.kodeDokter,
          "poliKontrol": input.value.poliKontrol.kode,
          "tglRencanaKontrol": H.formatDate(input.value.tglRencanaKontrol, 'YYYY-MM-DD'),
          "user": H.namaPegawai()
        }
      }
    }
  } else {
    json = {
      "url": `/RencanaKontrol/insert`,
      "method": "POST",
      "data": {
        "request": {
          "noSEP": input.value.noSEP,
          "kodeDokter": input.value.kodeDokter.kodeDokter,
          "poliKontrol": input.value.poliKontrol.kode,
          "tglRencanaKontrol": H.formatDate(input.value.tglRencanaKontrol, 'YYYY-MM-DD'),
          "user": H.namaPegawai()
        }
      }
    }
  }
  isLoading.value = true
  useApi().postBPJS('/bridging/bpjs/tools', json).then((x) => {
    isLoading.value = false
    if (x.metaData.code == 200) {
      H.alert('success', x.metaData.message)
      noSuratKontrol.value = x.response.noSuratKontrol
    } else {
      H.alert('error', x.metaData.message)
    }
  })
}
const insertSPRI = () => {
  let json = {}

  if (input.value.noSuratKontrol) {
    json = {
      "url": `/RencanaKontrol/UpdateSPRI`,
      "method": "PUT",
      "data": {
        "request": {
          "noSPRI": input.value.noSuratKontrol,
          "kodeDokter": input.value.kodeDokter.kodeDokter,
          "poliKontrol": input.value.poliKontrol.kode,
          "tglRencanaKontrol": H.formatDate(input.value.tglRencanaKontrol, 'YYYY-MM-DD'),
          "user": H.namaPegawai()
        }
      }
    }
  } else {
    json = {
      "url": `/RencanaKontrol/InsertSPRI`,
      "method": "POST",
      "data": {
        "request": {

          "noKartu": input.value.noKartu,
          "kodeDokter": input.value.kodeDokter.kodeDokter,
          "poliKontrol": input.value.poliKontrol.kode,
          "tglRencanaKontrol": H.formatDate(input.value.tglRencanaKontrol, 'YYYY-MM-DD'),
          "user": H.namaPegawai()
        }
      }
    }
  }
  isLoading.value = true
  useApi().postBPJS('/bridging/bpjs/tools', json).then((x) => {
    isLoading.value = false
    if (x.metaData.code == 200) {
      H.alert('success', x.metaData.message)
      noSuratKontrol.value = x.response.noSPRI
    } else {
      H.alert('error', x.metaData.message)
    }
  })
}
const batal = () => {
  delete input.value.kodeDokter
  delete input.value.poliKontrol
  showInput.value = false
  // input.value = {
  //   filterTgl: {
  //     start: new Date(),
  //     end: new Date(),
  //   },
  //   filter: d_Filter.value[0],
  //   jenis: 1,
  //   tglRencanaKontrol: new Date()
  // }
  // pasien.value = {}
  // sep.value = {}
}

const hapus = (e: any) => {
  let json = {
    "url": `/Rujukan/delete`,
    "method": "DELETE",
    "data":
    {
      "request": {
        "t_rujukan": {
          "noRujukan": e.noRujukan,
          "user": H.namaPegawai()
        }
      }
    }
  }

  isLoading.value = true
  useApi().postBPJS('/bridging/bpjs/tools', json).then((x) => {
    isLoading.value = false

    if (x.metaData.code == 200) {
      H.alert('success', x.metaData.message)
      delete noRujukan.value
      batal()
    } else {
      H.alert('error', x.metaData.message)
    }
  })
}
const init = () => {
  useApi().get(
    `general/ppk-bpjs`
  ).then((response) => {
    namappkRumahSakit.value = response.BPJS_namaPPKRujukan
  })
}
const backPage = () => {
  window.history.back()
}


const setSEP = (e: any) => {
  e.isLoading = true
  input.value.noSep = e.noSep
  var json = {
    "url": "SEP/" + e.noSep,
    "method": "GET",
    "data": null
  }
  useApi().postBPJS('/bridging/bpjs/tools', json).then((e) => {
    showInput.value = true
    if (e.metaData.code == 200) {
      for (let x = 0; x < listPel.value.length; x++) {
        const element = listPel.value[x];
        if (element.nama == e.response.jnsPelayanan) {
          input.value.jnsPelayanan = element.kode
          break
        }
      }
      e.response.peserta.provUmum = {}
      sep.value = e.response



      var json = {
        "url": "Peserta/nokartu/" + e.response.peserta.noKartu + "/tglSEP/" + H.formatDate(new Date(), 'YYYY-MM-DD'),
        "method": "GET",
        "data": null
      }
      useApi().postBPJS('/bridging/bpjs/tools', json).then((z) => {
        if (z.metaData.code == 200) {
          sep.value.peserta = z.response.peserta
          rujukan.value.rujukFaskes = z.response.peserta.provUmum.kdProvider + '~' + z.response.peserta.provUmum.nmProvider
        }
      })
      e.isLoading = false
    }
    else H.alert('error', e.metaData.message)
  })
}
watch(
  () => input.jenis,
  (newValue, oldValue) => {

  }
)
watch(
  () => activeIdx.value,
  (newValue, oldValue) => {
    if (newValue != oldValue) {
      if (newValue == 0) {
        cariSKD()
      }
      if (newValue == 1) {
        cariMonitor()
      }
      batal()
    }

  }
)

watch(
  () =>
    input.value.tipeRujukan
  , (newValue, oldValue) => {
    cekTipe(newValue)
  }
)
init()
if (route.query.nosep && route.query.nokartu) {
  input.value.noKartu = route.query.nokartu
  input.value.noSEP = route.query.nosep
  input.value.jenis = 2
  cariPasien()
}
if (route.query.nosep == undefined && route.query.nokartu) {
  input.value.noKartu = route.query.nokartu
  input.value.jenis = 1
  cariPasien()
}

cariSKD()
setAutoFill()
// fetchSubSpe()

</script>

<style lang="scss">
@import '/@src/scss/abstracts/all';
@import '/@src/scss/components/forms-outer';
@import '/@src/scss/custom/config';
@import '/@src/scss/custom/timeline-css';
@import '/@src/scss/module/registrasi/pemakaian-asuransi.scss';
</style>
