<template>
  <div class="column">
    <VCard>
      <div class="column is-12">
        <div class="search-widget">
          <div class="field">
            <div class="columns is-multiline">
              <div class="column is-4 pt-0 pb-0">
                <span>Periode</span>
                <VDatePicker v-model="item.periode" is-range color="pink" trim-weeks class="pt-2">
                  <template #default="{ inputValue, inputEvents }">
                    <VField addons>
                      <VControl icon="feather:calendar">
                        <VInput :value="inputValue.start" class="input-calendar" v-on="inputEvents.start" />
                      </VControl>
                      <VControl>
                        <VButton static><i class="fas fa-arrow-right" aria-hidden="true"></i></VButton>
                      </VControl>
                      <VControl icon="feather:calendar">
                        <VInput :value="inputValue.end" class="input-calendar" v-on="inputEvents.end" />
                      </VControl>
                    </VField>
                  </template>
                </VDatePicker>
              </div>
              <div class="column pt-0 pb-0 is-4">
                <span>Dokter</span>
                <VField class="is-autocomplete-select pt-2" v-slot="{ id }">
                  <VControl icon="feather:search">
                    <AutoComplete v-model="item.filterdokterfk" :suggestions="d_Dokter" @complete="fetchDokter($event)"
                      :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                      :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="Dokter" />
                  </VControl>
                </VField>
              </div>
              <div class="column is-3 pt-0 pb-0">
                <span>Ruangan</span>
                <VField class="is-autocomplete-select pt-2">
                  <VControl icon="feather:search">
                    <MultiSelect v-model="sourceRuangan" display="chip" :options="d_Ruangan" optionLabel="label" filter
                      placeholder="Pilih Ruangan" :maxSelectedLabels="3" style="display:flex"
                      @change="changeRuang(sourceRuangan)" />
                  </VControl>
                </VField>
                <!-- <span>Ruangan</span> -->
                <!-- <VField class="is-autocomplete-select pt-2" v-slot="{ id }">
                  <VControl icon="feather:search">
                    <AutoComplete v-model="item.ruangan" :suggestions="d_Ruangan" @complete="fetchRuangan($event)"
                      :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                      :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="Ruangan" />
                  </VControl>
                </VField> -->
              </div>
            </div>
          </div>
          <div class="field">
            <div class="control">
              <div class="columns is-multiline">
                <div class="column " :class="[kelompokUser == 'dokter' ? 'is-8' : 'is-11']">
                  <input type="text" v-model="item.search" v-on:keyup.enter="cari()" class="input"
                    placeholder="Search..." />
                </div>
                <VControl v-if="kelompokUser == 'dokter'">
                  <VSwitchBlock v-model="item.isdokter" label="Konsul ke saya" color="danger" />
                </VControl>
                <div class="column" style="margin-left: auto !important;">
                  <VIconButton type="button" color="success" class="searcv-button" raised icon="fas fa-search"
                    @click="cari()" :loading="isLoadingBtn">
                  </VIconButton>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </VCard>
  </div>

  <div class="column">
    <VCard>
      <TabView class="tabview-custom " :scrollable="true" @tab-click="klikTab($event)">

        <TabPanel>
          <template #header>
            <i class="fas fa-users mr-2" aria-hidden="true"></i>
            <span>Daftar Order</span>
            <Badge :value="totalData" v-if="totalData > 0" severity="danger" class="ml-2" />


          </template>
          <div class="columns is-multiline">
            <div class="column">
              <div class="flex-list-inner mb-2 mt-5" v-if="isLoading">
                <div class="flex-table-item grid-item mb-1" v-for="key in 5" :key="key">
                  <VPlaceloadWrap>
                    <VPlaceloadAvatar size="small" />
                    <VPlaceloadText last-line-width="60%" class="mx-2" />
                    <VPlaceload class="mx-2" disabled />
                    <VPlaceload class="mx-2 h-hidden-tablet-p" />
                    <VPlaceload class="mx-2 h-hidden-tablet-p" />
                    <VPlaceload class="mx-2" />
                  </VPlaceloadWrap>
                </div>
              </div>
              <div class="list-view list-view-v3" v-else-if="sourceOrder.length == 0">
                <VPlaceholderPage title="Tidak Ada Data."
                  subtitle="Silakan Pilih Tanggal dan Ruangan untuk melihat data" larger>
                  <template #image>
                    <img class="light-image" src="/@src/assets/illustrations/placeholders/search-4.png" alt="" />
                    <img class="dark-image" src="/@src/assets/illustrations/placeholders/search-4-dark.svg" alt="" />
                  </template>
                </VPlaceholderPage>
              </div>
              <div class="timeline-wrapper" v-else>

                <div class="column is-12">
                  <div class="columns is-multiline">
                    <div class="column is-2">
                      <VControl>
                        <VSwitchBlock v-model="item.isnotverif" label="Pending" color="danger" @change="fetchOrder" />
                      </VControl>
                    </div>
                    <div class="column is-2 pt-1">
                      <VField class="is-autocomplete-select pt-3" v-slot="{ id }">
                        <VButton color="warning" @click="exportExcelOrder()" outlined icon="fas fa-file-excel">Export
                        </VButton>
                      </VField>
                    </div>
                  </div>
                </div>
                <div class="timeline-header"></div>
                <div class="timeline-wrapper-inner">
                  <div class="timeline-container">
                    <div class="timeline-item is-unread" v-for="(data) in sourceOrder" :key="data.norec">
                      <div class="date">
                        <span>{{ H.formatDateIndoSimple(data.tglorder) }}</span>
                      </div>
                      <div class="dot is-info"></div>
                      <div class="content-wrap">
                        <div class="content-box">
                          <div class="status"></div>
                          <VIconBox size="medium" color="primary" rounded>
                            <i class="lnir lnir-diagnosis" aria-hidden="true"></i>
                          </VIconBox>

                          <div class="column">
                            <span style="font-weight: 600; font-size: 17px;">{{ data.namapasien }}</span>
                            <VTag class="ml-4" :color="data.norec_apd != null ? 'success' : 'warning'" rounded>{{
                              data.norec_apd != null ? 'verifikasi' : 'pending' }}</VTag>
                            <div class="columns is-multiline pt-4">
                              <div class="column is-6 pt-0 pb-0 ">
                                <span>Ruangan Asal :</span> <span style="font-weight: 500;color: #646161;">{{
                                  data.ruanganasal
                                    !=
                                    null ? data.ruanganasal : '-' }}</span>
                              </div>
                              <div class="column is-6 pt-0 pb-0">
                                <span>Ruangan Tujuan :</span> <span style="font-weight: 500;color: #646161;">{{
                                  data.ruangantujuan }}</span>
                              </div>
                            </div>
                            <div class="columns is-multiline">
                              <div class="column is-6 pt-0 pb-0 ">
                                <span>Pengonsul :</span> <span style="font-weight: 500;color: #646161;">{{
                                  data.namalengkap
                                }}</span>
                              </div>
                              <div class="column is-6 pt-0 pb-0">
                                <span>No Order :</span> <span style="font-weight: 500;color: #646161;">{{
                                  data.noorder }}</span>
                              </div>
                            </div>
                          </div>
                          <div class="box-end">
                            <VIconButton color="primary" outlined circle icon="fas fa-check" v-tooltip.top="'Verify'"
                              v-if="data.norec_apd == null" :loading="data.isLoading" @click="verifyOrder(data)" />
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <VFlexPagination v-model:current-page="currentPage.page" :item-per-page="currentPage.limit"
                  :total-items="totalData" :max-links-displayed="5">
                  <template #before-pagination>
                  </template>
                  <template #before-navigation>
                    <VFlex class="mr-4 mt-1" column-gap="1rem">
                      <VField>
                      </VField>
                      <VField>
                        <VControl>
                          <div class="select is-rounded">
                            <select v-model="currentPage.limit">
                              <option :value="1">1 results per page</option>
                              <option :value="5">5 results per page</option>
                              <option :value="10">10 results per page</option>
                              <option :value="15">15 results per page</option>
                              <option :value="25">25 results per page</option>
                              <option :value="50">50 results per page</option>
                            </select>
                          </div>
                        </VControl>
                      </VField>
                    </VFlex>
                  </template>
                </VFlexPagination>
              </div>
            </div>
          </div>
        </TabPanel>
        <TabPanel>
          <template #header>
            <i class="fas fa-check-circle mr-2" aria-hidden="true"></i>
            <span>Konsultasi</span>
            <Badge :value="dataSource.length" v-if="dataSource.length > 0" severity="danger" class="ml-2" />
          </template>

          <div class="columns is-multiline">
            <div class="column">
              <div class="list-view list-view-v3" v-if="dataSource.length == 0">
                <VPlaceholderPage title="Tidak Ada Data."
                  subtitle="Silakan Pilih Tanggal dan Ruangan untuk melihat data" larger>
                  <template #image>
                    <img class="light-image" src="/@src/assets/illustrations/placeholders/search-4.png" alt="" />
                    <img class="dark-image" src="/@src/assets/illustrations/placeholders/search-4-dark.svg" alt="" />
                  </template>
                </VPlaceholderPage>
              </div>

              <div class="columns is-multiline">
                <div class="column is-12">
                  <VCard>
                    <DataTable :value="dataSource" :paginator="true" :rows="10" :rowsPerPageOptions="[5, 10, 25]"
                      class="p-datatable-customers" filterDisplay="menu"
                      paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
                      responsiveLayout="stack" breakpoint="960px" sortMode="multiple"
                      currentPageReportTemplate="Showing {first} to {last} of {totalRecords}" :scrollable="true"
                      dataKey="norec">
                      <template #header>
                        <div class="columns is-multiline" style="justify-content: space-between;">
                          <VButton color="primary" @click="exportExcelKonsultasi()" outlined icon="fas fa-file-excel"
                            class="mt-5 ml-3">
                            Export To Excel
                          </VButton>
                        </div>
                      </template>
                      <Column :exportable="false" header="#" :style="{ width: '180px' }">
                        <template #body="slotProps">
                          <VIconButton type="button" icon="fas fa-stethoscope" class="mr-2" color="info" circle outlined
                            raised v-tooltip.top="'EMR '" @click="emr(slotProps.data)">
                          </VIconButton>
                          <VIconButton type="button" icon="fas fa-arrow-right" class="mr-2" color="success" circle
                            outlined raised v-tooltip.top="'Jawab '" @click="jawab(slotProps.data)">
                          </VIconButton>
                        </template>
                      </Column>
                      <!-- <Column field="no" header="No" :style="{ width: '40px' }"> </Column> -->
                      <Column field="tglorder" header="Tanggal Order" style="width:150px" :sortable="true"> </Column>
                      <Column field="ruanganasal" header="Ruangan Asal" style="width:150px" :sortable="true"> </Column>
                      <Column field="namapasien" header="Nama Pasien" style="width:150px"></Column>
                      <Column field="nocm" header="No. RM" :sortable="true" style="width:150px"></Column>
                      <!-- <Column field="namalengkap" header="Pengorder" style="width:200px"></Column> -->
                      <Column field="keteranganorder" header="Pertanyaan" style="width:300px"></Column>
                      <Column field="keteranganlainnya" header="Jawaban" style="width:300px"></Column>
                    </DataTable>
                  </VCard>
                </div>
              </div>
            </div>
          </div>
        </TabPanel>
      </TabView>
    </VCard>
  </div>
  <Dialog v-model:visible="modalInput" modal header="Konsultasi" :style="{ width: '60vw' }">
    <div class="columns is-multiline">
      <div class="column is-3">
        <VDatePicker class="pt-0 pb-0 pl-0" v-model="item.tanggal" color="green" trim-weeks mode="dateTime"
          :max-date="new Date()">
          <template #default="{ inputValue, inputEvents }" class="pb-0">
            <VField>
              <VLabel class="required-field">Tanggal</VLabel>
              <VControl icon="feather:calendar">
                <VInput type="text" placeholder="Select a date" :value="inputValue" v-on="inputEvents"
                  class="is-rounded" />
              </VControl>
            </VField>
          </template>
        </VDatePicker>
      </div>
      <div class="column is-3">
        <VField>
          <VLabel>Ruangan Asal</VLabel>
          <VControl icon="feather:bookmark">
            <input v-model="item.ruanganasal" type="text" class="input is-rounded" placeholder="Lain-lain " disabled />
          </VControl>
        </VField>
      </div>
      <div class="column is-3">
        <VField>
          <VLabel>Ruangan Tujuan</VLabel>
          <VControl icon="feather:bookmark">
            <input v-model="item.ruangantujuan" type="text" class="input is-rounded" placeholder="Lain-lain "
              disabled />
          </VControl>
        </VField>
      </div>
      <div class="column is-3">
        <VField>
          <VLabel>Dokter</VLabel>
          <VControl icon="feather:bookmark">
            <input v-model="item.dokter" type="text" class="input is-rounded" placeholder="Lain-lain " disabled />
          </VControl>
        </VField>
      </div>


      <div class="column is-3">
        <VField>
          <VControl>
            <VSwitchBlock v-model="item.rawatBersama" label="Rawat Bersama" color="danger" disabled />
          </VControl>
        </VField>
      </div>
      <div class="column is-3">
        <VField>
          <VControl>
            <VSwitchBlock v-model="item.konsultasi" label="Konsultasi" color="danger" disabled />
          </VControl>
        </VField>
      </div>
      <div class="column is-6">
        <VField>
          <VLabel>Lain-lain</VLabel>
          <VControl icon="feather:bookmark">
            <input v-model="item.lainlain" type="text" class="input is-rounded" placeholder="Lain-lain " disabled />
          </VControl>
        </VField>
      </div>
      <div class="column is-12">
        <VField>
          <VLabel>Keterangan</VLabel>
          <VControl>
            <VTextarea v-model="item.keterangan" rows="3" placeholder="Keterangan" disabled>
            </VTextarea>
          </VControl>
        </VField>
      </div>
      <div class="column is-12">
        <VField>
          <VLabel>Jawaban</VLabel>
          <VControl>
            <VTextarea v-model="item.jawaban" rows="3" placeholder="Jawaban">
            </VTextarea>
          </VControl>
        </VField>
      </div>
    </div>
    <template #footer>
      <VButton icon="feather:refresh-cw rem-100" light dark-outlined @click="kembaliKeun()">
        Batal
      </VButton>
      <VButton type="button" rounded outlined color="primary" raised icon="feather:save" :loading="isLoadingBtn"
        @click="simpan()"> Simpan
      </VButton>
    </template>
  </Dialog>
  <Dialog v-model:visible="modalUpdateDokter" modal header="Form Pilih Dokter" :style="{ width: '65vw' }">
    <div class="columns is-multiline">
      <!-- <div class="column is-3">
                    <VDatePicker class="pt-0 pb-0 pl-0" v-model="item.tanggal" color="green" trim-weeks mode="dateTime"
                        :max-date="new Date()">
                        <template #default="{ inputValue, inputEvents }" class="pb-0">
                            <VField>
                                <VLabel class="required-field">Tanggal</VLabel>
                                <VControl icon="feather:calendar">
                                    <VInput type="text" placeholder="Select a date" :value="inputValue" v-on="inputEvents"
                                        class="is-rounded"  />
                                </VControl>
                            </VField>
                        </template>
                    </VDatePicker>
                </div> -->
      <!-- <div class="column is-3">
        <VField>
          <VLabel>Ruangan Asal</VLabel>
          <VControl icon="feather:bookmark">
            <input v-model="item.ruanganasal" type="text" class="input is-rounded d-hidden" placeholder="Lain-lain " disabled />
          </VControl>
        </VField>
      </div> -->
      <div class="column is-6">
        <VField>
          <VLabel>Ruangan Asal</VLabel>
          <VControl icon="feather:bookmark">
            <input v-model="item.namaruanganasal" type="text" class="input is-rounded" placeholder="Lain-lain "
              disabled />
          </VControl>
        </VField>
      </div>
      <!-- <div class="column is-3">
        <VField>
          <VLabel>Ruangan Tujuan</VLabel>
          <VControl icon="feather:bookmark">
            <input v-model="item.ruangantujuan" type="text" class="input is-rounded" placeholder="Lain-lain " disabled />
          </VControl>
        </VField>
      </div> -->
      <div class="column is-6">
        <VField>
          <VLabel>Ruangan Tujuan</VLabel>
          <VControl icon="feather:bookmark">
            <input v-model="item.namaruangantujuan" type="text" class="input is-rounded" placeholder="Lain-lain "
              disabled />
          </VControl>
        </VField>
      </div>
      <!-- <div class="column is-3">
                    <VField>
                        <VLabel>Dokter</VLabel>
                        <VControl icon="feather:bookmark">
                            <input v-model="item.dokter" type="text" class="input is-rounded" placeholder="Lain-lain "
                            disabled/>
                        </VControl>
                    </VField>
                </div> -->


      <!-- <div class="column is-3">
                    <VField>
                        <VControl>
                            <VSwitchBlock v-model="item.rawatBersama" label="Rawat Bersama" color="danger"
                            disabled/>
                        </VControl>
                    </VField>
                </div>
                <div class="column is-3">
                    <VField>
                        <VControl>
                            <VSwitchBlock v-model="item.konsultasi" label="Konsultasi" color="danger"
                            disabled/>
                        </VControl>
                    </VField>
                </div>
                <div class="column is-6">
                    <VField>
                        <VLabel>Lain-lain</VLabel>
                        <VControl icon="feather:bookmark">
                            <input v-model="item.lainlain" type="text" class="input is-rounded" placeholder="Lain-lain "
                            disabled/>
                        </VControl>
                    </VField>
                </div>
                <div class="column is-12">
                    <VField>
                        <VLabel>Keterangan</VLabel>
                        <VControl>
                            <VTextarea v-model="item.keterangan" rows="3" placeholder="Keterangan"
                            disabled>
                            </VTextarea>
                        </VControl>
                    </VField>
                </div>
                <div class="column is-12">
                    <VField>
                        <VLabel>Jawaban</VLabel>
                        <VControl>
                            <VTextarea v-model="item.jawaban" rows="3" placeholder="Jawaban">
                            </VTextarea>
                        </VControl>
                    </VField>
                </div> -->
    </div>
    <div class="column">
      <span style="font-weight: 500;">Dokter </span>
      <VField class="is-autocomplete-select pt-3">
        <VControl icon="feather:search">
          <AutoComplete v-model="item.dokter" :suggestions="d_DokterUpdate" @complete="fetchDokterUpdate($event)"
            :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
            :field="'label'" placeholder="Dokter" />
        </VControl>
      </VField>
    </div>
    <template #footer>
      <VButton color="danger" icon="pi pi-times" outlined raised @click="modalUpdateDokter = false"> Batal </VButton>
      <VButton color="primary" icon="pi pi-check" raised @click="simpanVerify()" :loading="btnLoadSimpan"> Update
      </VButton>
    </template>
  </Dialog>
</template>
<script setup lang="ts">
import { useWindowScroll } from '@vueuse/core'
import { useApi } from '/@src/composable/useApi'
import { ref, computed, watch, reactive } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useHead } from '@vueuse/head'
import { useUserSession } from '/@src/stores/userSession'
import ConfirmDialog from 'primevue/confirmdialog'
import MultiSelect from 'primevue/multiselect';
import { useConfirm } from 'primevue/useconfirm'
import * as H from '/@src/utils/appHelper'
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import { useToaster } from '/@src/composable/toaster'
import Dialog from 'primevue/dialog';
import AutoComplete from 'primevue/autocomplete';
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useThemeColors } from '/@src/composable/useThemeColors'
import moment from 'moment'
import TabView from 'primevue/tabview';
import TabPanel from 'primevue/tabpanel';
import Badge from 'primevue/badge';
import * as XLSX from "xlsx";
import * as XLSXStyle from 'xlsx-js-style';
useHead({
  title: 'Daftar Konsultasi - ' + import.meta.env.VITE_PROJECT,
})
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)
const item: any = ref({
  periode: reactive({
    start: new Date(),
    end: new Date(),
  }),
  isnotverif: true,
})
const confirm = useConfirm();

let listKelompokPasien: any = ref([])
let sourceRuangan: any = ref([])

let ds_PASIEN: any = ref([])
let listColor: any = ref(Object.keys(useThemeColors()))
const kelompokUser = useUserSession().getUser().kelompokUser.kelompokUser
// const currentPage: any = ref({
//   limit: 5,
//   rows: 50
// })

if (kelompokUser == 'dokter') {
  item.value.isdokter = true
}
if (kelompokUser == 'admin-rajal') {
  item.value.isAdmin = true
}
const activeTab = ref(0)
const userLogin = useUserSession().getUser()
const router = useRouter()
const route = useRoute()
const { y } = useWindowScroll()
const d_Ruangan = ref([])
const sourceOrder = ref([])
const dataSource = ref([])
const modalUpdateDokter = ref(false)
const d_Dokter = ref([])
const d_DokterUpdate = ref([])
const disabledJawab = ref(false)
const modalInput = ref(false)
const isLoadingBtn = ref(false)
const isLoading = ref(false)
const btnLoadSimpan = ref(false)
const modalChangeDate = ref(false)
const totalData: any = ref(0)
const isStuck = computed(() => {
  return y.value > 30
})
const currentPage: any = ref({
  limit: 5,
  rows: 50
})

currentPage.value.page = computed(() => {
  try {
    return Number.parseInt(route.query.page as string) || 1
  } catch { }
  return 1
})
const changeRuang = async (e: any) => {
  console.log(e);

  fetchData()
  fetchOrder()
  // fetchJadwalDokter(items)
}

const fetchData = async () => {
  let tglAwal = `?tglAwal=${moment(item.value.periode.start).format('YYYY-MM-DD')}`
  let tglAkhir = `&tglAkhir=${moment(item.value.periode.end).format('YYYY-MM-DD')}`
  let ruangan = item.value.ruangan ? `&ruanganfk=${item.value.ruangan}` : ''
  let search = item.value.search ? `&search=${item.value.search}` : ''
  let idpegawai = item.value.isdokter == true ? `&idpegawai=${useUserSession().getUser().pegawai.id}` : ''
  if (sourceRuangan.value != undefined) {
    let itemsRuang = []
    sourceRuangan.value.forEach((element: any) => {
      itemsRuang = [...new Set([...itemsRuang, element.value])]
    });
    ruangan = `&ruanganfk=${itemsRuang}`
  }
  await useApi().get(`registrasi/get-daftar-konsultasi${tglAwal}${tglAkhir}${ruangan}${idpegawai}${search}`).then((response) => {
    response.forEach((element: any, i: any) => {
      element.no = i + 1
      let ini = element.namapasien.split(' ')
      let init = element.namapasien.substr(0, 1)
      if (ini.length > 1) {
        init = init + ini[1].substr(0, 1)
      }
      element.initials = init
    });
    dataSource.value = response
  })
}

const fetchOrder = async () => {
  ds_PASIEN.value.loading = true
  let tglAwal = `?tglAwal=${moment(item.value.periode.start).format('YYYY-MM-DD')}`
  let tglAkhir = `&tglAkhir=${moment(item.value.periode.end).format('YYYY-MM-DD')}`
  // let ruangan = item.value.ruangan ? `&ruanganfk=${item.value.ruangan}` : ''
  let ruangan
  let dokter = item.value.filterdokterfk ? `&dokterfk=${item.value.filterdokterfk.value}` : ''
  let search = item.value.search ? `&search=${item.value.search}` : ''
  let isnotverif = item.value.isnotverif ? `&isnotverif=${item.value.isnotverif}` : ''
  let idpegawai = item.value.isdokter == true ? `&idpegawai=${useUserSession().getUser().pegawai.id}` : ''
  let idadmin = item.value.isAdmin == true ? `&idadmin=${useUserSession().getUser().pegawai.id}` : ''
  let limit: any = currentPage.value.limit
  let offset: any = route.query.page ? route.query.page : 1
  offset = (offset * limit) - limit
  isLoading.value = true;
  if (sourceRuangan.value != undefined) {
    let itemsRuang = []
    sourceRuangan.value.forEach((element: any) => {
      itemsRuang = [...new Set([...itemsRuang, element.value])]
    });
    ruangan = `&ruanganfk=${itemsRuang}`
  }
  await useApi().get(`emr/get-order-konsul${tglAwal}${tglAkhir}${ruangan}${dokter}${idpegawai}${isnotverif}${search}${idadmin}&limit=${limit}&offset=${offset}`).then((response) => {
    sourceOrder.value = response.data
    totalData.value = response.total
  })
  isLoading.value = false;
  // set page to 1
  route.query.page = 1
}


const edit = async (e: any) => {
  console.log(e)

  // disabledJawab.value = false
  item.value.norec = e.norec
  // item.value.tanggal = new Date(e.tglorder)

  item.value.ruanganasal = e.ruanganasal
  item.value.ruangantujuan = e.ruangantujuan
  item.value.dokter = e.dokter


  item.value.konsultasi = e.konsultasi ? e.konsultasi : false
  item.value.lainlain = e.lainlain ? e.lainlain : ''
  item.value.rawatBersama = e.rawatbersama ? e.rawatbersama : false
  item.value.keterangan = e.keteranganorder ? e.keteranganorder : ''
  item.value.jawaban = e.keteranganlainnya ? e.keteranganlainnya : ''


  modalInput.value = true
}

const simpan = async () => {

  let formData = {
    'norec_so': item.value.norec,
    'jawaban': item.value.jawaban,
  }
  isLoadingBtn.value = true
  await useApi().post('/emr/jawab-order-konsul', formData).then((r) => {
    isLoadingBtn.value = false
    fetchData()
    modalInput.value = false
  }).catch((e: any) => {
    isLoadingBtn.value = false
  })
}
const jawab = async (e: any) => {
  edit(e)
}


const verifyOrder = async (e: any) => {
  modalUpdateDokter.value = true
  item.value.norecpd = e.norec_pd
  item.value.ruanganasal = e.ruanganasal
  item.value.ruangantujuan = e.ruangantujuan
  item.value.objectruanganfk = e.objectruanganfk
  item.value.objectruangantujuanfk = e.objectruangantujuanfk
  item.value.kelasfk = e.kelasfk
  item.value.norec_so = e.norec
  item.value.dokter = e.pegawaifk ? { label: e.namalengkap, value: e.pegawaifk } : ''
}

const simpanVerify = async () => {

  if (!item.value.dokter.value) {
    H.alert('error', 'Dokter Harus diisi')
    debugger
  }

  btnLoadSimpan.value = true
  let objSave = {
    "kelasfk": item.value.kelasfk,
    "norec_so": item.value.norec_so,
    "norec_pd": item.value.norecpd,
    "dokterfk": item.value.dokter.value,
    "objectruangantujuanfk": item.value.objectruangantujuanfk,
    "objectruanganasalfk": item.value.objectruanganfk,
  }

  await useApi().post('registrasi/get-save-konsul-order', objSave).then((response: any) => {
    fetchOrder()
    btnLoadSimpan.value = false
    modalUpdateDokter.value = false
  }).catch((e: any) => {
    modalUpdateDokter.value = false
    btnLoadSimpan.value = false
  })

}

const dialogConfirm = (e: any) => {
  confirm.require({
    message: 'Apakah anda serius menghapus data ini ?',
    header: 'Konfirmasi Hapus Data',
    icon: 'pi pi-info-circle',
    acceptClass: 'p-button-danger',
    accept: () => {
      deletePenunjung(e)

    },
    reject: () => { },
  })
}

const fetchRuangan = async (filter: any) => {
  // const response = await useApi().get(`/dashboard/registrasi/dropdown`)
  // d_Ruangan.value = response.ruangan.map((e: any) => { return { label: e.namaruangan, value: e.id, default: e } })
  // d_Ruangan.value.forEach((element) => {
  //   item.value.ruangan.value.push(element)
  // })
  const response = await useApi().get(`/dashboard/dropdown-rawat-jalan`)
  d_Ruangan.value = response.ruangan.map((e: any) => { return { label: e.namaruangan, value: e.id, default: e } })
  d_Ruangan.value.forEach((element) => {
    sourceRuangan.value.push(element)
  })
}

const showModalDokter = (e: any) => {
  item.value.norec_apd = e.norec_apd
  modalUpdateDokter.value = true
}

const updateDokter = async (e: any) => {
  let objSave = {
    "norec_apd": item.value.norec_apd,
    "iddokter": e.value
  }
  await useApi().post('registrasi/save-pilih-dokter-konsul', objSave).then((response) => {
    isLoadingBtn.value = false
    modalUpdateDokter.value = false
    fetchData()
  }).catch((e) => {
    isLoadingBtn.value = false
    modalUpdateDokter.value = false
  })

}

const emr = (e: any) => {
  H.cacheHelper().set('xxx_cache_menu', undefined)
  router.push({
    name: 'module-emr-profile-pasien',
    query: {
      nocmfk: e.nocmfk,
      norec_pd: e.norec_pd,
      norec_apd: e.norec_apd,
    },
  })
}
// kasir/billing?nocmfk=3&norec_pasien_daftar=21fbe1d4-7c0e-4714-a823-a4bffc598bf9&noregistrasi=2309000056
const rincianTagihan = (e: any) => {
  H.cacheHelper().set('xxx_cache_menu', undefined)
  router.push({
    name: 'module-kasir-billing',
    query: {
      nocmfk: e.nocmfk,
      norec_pasien_daftar: e.norec_pd,
      noregistrasi: e.noregistrasi
    },
  })
}

const fetchDokter = async (filter: any) => {
  useApi().get(
    `emr/dropdown/pegawai_m?select=id,namalengkap,kddokterbpjs&param_search=namalengkap&query=${filter.query}&settingdatafix=objectjenispegawaifk,idJenisPegawaiDokter&limit=10`
  ).then((response) => {
    // d_Dokter.value = response
    d_Dokter.value = response.map((e: any) => { return { label: e.label, value: e.value, default: e } })
    d_Dokter.value.forEach(element => {
      if (userLogin.pegawai.id == element.value) {
        item.value.filterdokterfk = element
        // console.log(item);

      }
    });
  })
}
const fetchDokterUpdate = async (filter: any) => {
  await useApi().get(
    `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&settingdatafix=objectjenispegawaifk,idJenisPegawaiDokter&limit=10`
  ).then((response) => {
    d_DokterUpdate.value = response
  })
}
const kembaliKeun = () => {
  modalInput.value = false
  input = {
    tanggal: new Date()
  }
}

const klikTab = (e: any) => {
  activeTab.value = e.index
  if (activeTab.value == 0) {
    fetchOrder()
  }
  if (activeTab.value == 1) {
    fetchData()
  }
}
const cari = () => {
  if (activeTab.value == 0) {
    fetchOrder()
  }
  if (activeTab.value == 1) {
    fetchData()
  }
}
const exportExcelOrder = () => {
  const dataSource = sourceOrder.value;
  const workbook = XLSX.utils.book_new();
  const worksheet = XLSX.utils.aoa_to_sheet([
    ['Daftar Order Konsultasi'],
    [],
    ['NO', 'NO ORDER', 'TANGGAL ORDER', 'NO CM', 'NAMA PASIEN', 'RUANGAN TUJUAN', 'RUANGAN ASAL', 'PENGONSUL', 'KETERANGAN', 'STATUS'],
    ...dataSource.map((e: any, index: number) => [
      index + 1,
      e.noorder,
      e.tglorder,
      e.nocm,
      e.namapasien,
      e.ruangantujuan,
      e.ruanganasal,
      e.pengonsul,
      e.keteranganlainnya,
      e.norec_apd ? 'verifikasi' : 'pending'
    ]),
  ]);

  // Defining style for the header (centered)
  const headerStyle = {
    alignment: {
      horizontal: 'center',
      vertical: 'center',
    },
    font: {
      color: { rgb: 'FFFFFF' },
      bold: true,
    },
    fill: { fgColor: { rgb: '807C7C' } },
  };

  // Applying header style
  const headerRange = XLSX.utils.decode_range(worksheet['!ref']);
  for (let col = headerRange.s.c; col <= headerRange.e.c; col++) {
    const headerCell = XLSX.utils.encode_cell({ r: 2, c: col });
    worksheet[headerCell].s = headerStyle;
  }

  // Setting column widths
  const columnWidths = [5, 10, 20, 10, 30, 25, 25, 30, 35, 10];

  for (let col = headerRange.s.c; col <= headerRange.e.c; col++) {
    worksheet['!cols'] = worksheet['!cols'] || [];
    worksheet['!cols'][col] = { wch: columnWidths[col] };
  }

  // Centering the text in cell A1
  const titleCell = XLSX.utils.encode_cell({ r: 0, c: 0 });
  worksheet[titleCell].s = {
    alignment: {
      horizontal: 'center',
      vertical: 'center',
    },
    font: {
      bold: true,
      sz: 18,
    },
  };

  // Merging the first two rows
  const mergeTitle = { s: { r: 0, c: 0 }, e: { r: 1, c: 10 } };
  worksheet['!merges'] = [mergeTitle];

  XLSX.utils.book_append_sheet(workbook, worksheet, 'Daftar Order Konsultasi', true);

  XLSXStyle.writeFile(workbook, 'Daftar Order Konsultasi.xlsx');
}
const exportExcelKonsultasi = () => {
  const workbook = XLSX.utils.book_new();
  const worksheet = XLSX.utils.aoa_to_sheet([
    ['Daftar Konsultasi'],
    [],
    ['NO', 'NO ORDER', 'TANGGAL ORDER', 'NO CM', 'NAMA PASIEN', 'RUANGAN TUJUAN', 'RUANGAN ASAL', 'DOKTER', 'KETERANGAN', 'STATUS'],
    ...dataSource.value.map((e: any, index: number) => [
      index + 1,
      e.noorder,
      e.tglorder,
      e.nocm,
      e.namapasien,
      e.ruangantujuan,
      e.ruanganasal,
      e.dokter,
      e.keteranganlainnya,
      e.norec_apd ? 'verifikasi' : 'pending'
    ]),
  ]);

  // Defining style for the header (centered)
  const headerStyle = {
    alignment: {
      horizontal: 'center',
      vertical: 'center',
    },
    font: {
      color: { rgb: 'FFFFFF' },
      bold: true,
    },
    fill: { fgColor: { rgb: '807C7C' } },
  };

  // Applying header style
  const headerRange = XLSX.utils.decode_range(worksheet['!ref']);
  for (let col = headerRange.s.c; col <= headerRange.e.c; col++) {
    const headerCell = XLSX.utils.encode_cell({ r: 2, c: col });
    worksheet[headerCell].s = headerStyle;
  }

  // Setting column widths
  const columnWidths = [5, 15, 20, 10, 30, 25, 25, 30, 35, 10];

  for (let col = headerRange.s.c; col <= headerRange.e.c; col++) {
    worksheet['!cols'] = worksheet['!cols'] || [];
    worksheet['!cols'][col] = { wch: columnWidths[col] };
  }

  // Centering the text in cell A1
  const titleCell = XLSX.utils.encode_cell({ r: 0, c: 0 });
  worksheet[titleCell].s = {
    alignment: {
      horizontal: 'center',
      vertical: 'center',
    },
    font: {
      bold: true,
      sz: 18,
    },
  };

  // Merging the first two rows
  const mergeTitle = { s: { r: 0, c: 0 }, e: { r: 1, c: 10 } };
  worksheet['!merges'] = [mergeTitle];

  XLSX.utils.book_append_sheet(workbook, worksheet, 'Daftar Konsultasi', true);

  XLSXStyle.writeFile(workbook, 'Daftar Konsultasi.xlsx');
}
// fetchDokterUpdate()
fetchRuangan()
fetchDokter()
fetchData()
fetchOrder()


watch(currentPage.value, () => {
  fetchOrder()
})



</script>



<style lang="scss">
@import '/@src/scss/abstracts/all';
@import '/@src/scss/components/forms-outer';
@import '/@src/scss/custom/timeline-css';

.timeline-wrapper .timeline-wrapper-inner .timeline-container .timeline-item::before {
  content: "";
  position: absolute;
  top: 94px !important;
  left: 111px;
  height: 100%;
  width: 2px;
  background: var(--placeholder);
  z-index: 0;
}

.fs-075 {
  font-size: 0.9rem;
}


.is-navbar {
  .form-layout {
    margin-top: 30px;
  }
}

.form-layout {
  // max-width: 740px;
  margin: 0 auto;

  &.is-separate {
    // max-width: 1040px;

    .form-outer {
      background: none;
      border: none;

      .form-body {
        display: flex;

        .form-section {
          flex-grow: 2;
          padding: 10px;
          width: 50%;

          .form-section-inner {
            @include vuero-s-card;

            padding: 40px;

            &.has-padding-bottom {
              padding-bottom: 60px;
              height: 100%;
            }

            >h3 {
              font-family: var(--font-alt);
              font-size: 1.2rem;
              font-weight: 600;
              color: var(--dark-text);
              margin-bottom: 30px;
            }

            .columns {
              .column {
                padding-top: 0.25rem;
                padding-bottom: 0.25rem;
              }
            }

            .radio-boxes {
              display: flex;
              justify-content: space-between;
              margin-left: -8px;
              margin-right: -8px;

              .radio-box {
                position: relative;
                width: calc(50% - 16px);
                margin: 8px;

                &:focus-within {
                  border-radius: 3px;
                  outline-offset: var(--accessibility-focus-outline-offset);
                  outline-width: var(--accessibility-focus-outline-width);
                  outline-style: var(--accessibility-focus-outline-style);
                  outline-color: var(--primary);
                }

                input {
                  position: absolute;
                  top: 0;
                  left: 0;
                  height: 100%;
                  width: 100%;
                  opacity: 0;
                  cursor: pointer;

                  &:checked {
                    +.radio-box-inner {
                      background: var(--primary);
                      border-color: var(--primary);
                      box-shadow: var(--primary-box-shadow);

                      .fee,
                      p {
                        color: var(--smoke-white);
                      }
                    }
                  }
                }

                .radio-box-inner {
                  background: var(--white);
                  border: 1px solid var(--fade-grey-dark-3);
                  text-align: center;
                  border-radius: var(--radius);
                  font-family: var(--font);
                  font-weight: 600;
                  font-size: 0.9rem;
                  transition: color 0.3s, background-color 0.3s, border-color 0.3s,
                    height 0.3s, width 0.3s;
                  padding: 30px 20px;

                  .fee {
                    font-family: var(--font);
                    font-weight: 700;
                    color: var(--dark-text);
                    font-size: 2.4rem;
                    line-height: 1;

                    span {
                      &::after {
                        content: '$';
                        position: relative;
                        top: -10px;
                        font-size: 1.5rem;
                      }
                    }
                  }

                  p {
                    font-family: var(--font-alt);
                  }
                }
              }
            }

            .control {
              >p {
                padding-top: 12px;

                >span {
                  display: block;
                  font-size: 0.9rem;

                  span {
                    font-weight: 500;
                    color: var(--dark-text);
                  }
                }
              }
            }
          }

          .form-section-outer {
            .checkboxes {
              padding: 16px 0;

              .checkbox {
                padding: 0;
                font-size: 0.9rem;
              }
            }

            .button-wrap {
              .button {
                min-height: 60px;
                font-size: 1.05rem;
                font-weight: 600;
                font-family: var(--font-alt);
              }
            }
          }
        }
      }
    }
  }
}

.is-dark {
  .form-layout {
    &.is-separate {
      .form-outer {
        background: none !important;

        .form-body {
          .form-section {
            .form-section-inner {
              @include vuero-card--dark;

              >h3 {
                color: var(--dark-dark-text);
              }

              .radio-boxes {
                .radio-box {
                  input:checked+.radio-box-inner {
                    background: var(--primary);
                    border-color: var(--primary);
                    box-shadow: var(--primary-box-shadow);

                    .fee,
                    p {
                      color: var(--smoke-white);
                    }
                  }

                  .radio-box-inner {
                    background: var(--dark-sidebar-light-2);
                    border-color: var(--dark-sidebar-light-12);

                    .fee {
                      color: var(--dark-dark-text);
                    }
                  }
                }
              }
            }
          }
        }
      }
    }
  }
}

@media only screen and (max-width: 767px) {
  .form-layout {
    &.is-separate {
      .form-outer {
        .form-body {
          padding-left: 0;
          padding-right: 0;
          flex-direction: column;

          .form-section {
            width: 100%;

            .form-section-inner {
              padding: 30px;
            }
          }
        }
      }
    }
  }
}

@media only screen and (min-width: 768px) and (max-width: 1024px) and (orientation: portrait) {
  .form-layout {
    &.is-separate {
      .form-outer {
        .form-body {
          padding-left: 0;
          padding-right: 0;

          // flex-direction: column;

          .form-section {
            // width: 100%;

            .form-section-inner {
              padding: 30px;
            }
          }
        }
      }
    }
  }
}

.all-projects {
  .all-projects-header {
    display: flex;
    padding: 20px;
    background: var(--white);
    border: 1px solid var(--fade-grey-dark-3);
    border-radius: var(--radius-large);
    margin-bottom: 1.5rem;

    .header-item {
      width: 25%;
      border-right: 1px solid var(--fade-grey-dark-3);

      &:last-child {
        border-right: none;
      }

      .item-inner {
        text-align: center;

        .lnil,
        .lnir {
          font-size: 2.2rem;
          margin-bottom: 6px;
          color: var(--primary);
        }

        span {
          display: block;
          font-family: var(--font);
          font-weight: 600;
          font-size: 1.4rem;
          color: var(--dark-text);
        }

        p {
          font-family: var(--font-alt);
        }
      }
    }
  }

  .projects-card-grid {
    .grid-item {
      display: flex;
      flex-direction: column;
      justify-content: space-between;
      min-height: 220px;
      padding: 20px;
      background: var(--white);
      border: 1px solid var(--fade-grey-dark-3);
      border-radius: var(--radius-large);

      .top-section {
        .head {
          display: flex;
          justify-content: space-between;
          align-items: center;
          margin-bottom: 8px;

          h3 {
            font-size: 1rem;
            font-family: var(--font-alt);
            color: var(--dark-text);
            font-weight: 600;
          }
        }

        .body {
          p {
            font-family: var(--font);
            color: var(--light-text);
          }
        }
      }

      .bottom-section {
        display: flex;

        .foot-block {
          margin-right: 30px;

          .heading {
            font-family: var(--font-alt);
            font-size: 0.75rem;
            color: var(--light-text-dark-22);
          }

          >p {
            padding-top: 5px;
          }

          .developers {
            display: flex;

            .v-avatar {
              margin-right: 6px;
            }
          }
        }
      }
    }
  }
}

.heading {
  font-family: var(--font-alt);
  font-size: 0.75rem;
  color: var(--light-text-dark-22);
}

.is-dark {
  .all-projects {
    .all-projects-header {
      background: var(--dark-sidebar-light-6);
      border-color: var(--dark-sidebar-light-12);

      .header-item {
        border-color: var(--dark-sidebar-light-18);

        span {
          color: var(--dark-dark-text);
        }

        i {
          color: var(--primary) !important;
        }
      }
    }

    .projects-card-grid {
      .grid-item {
        background: var(--dark-sidebar-light-6);
        border-color: var(--dark-sidebar-light-12);

        .top-section {
          .head {
            h3 {
              color: var(--dark-dark-text);
            }
          }
        }

        .bottom-section {
          .foot-block {
            .heading {
              color: var(--light-text-dark-12);
            }
          }
        }
      }
    }
  }
}

.tabs-wrapper.is-slider .tabs,
.tabs-wrapper-alt.is-slider .tabs {
  max-width: 30% !important;
}
</style>
