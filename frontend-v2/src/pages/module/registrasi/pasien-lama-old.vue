
<template>
    <ConfirmDialog />
    <VCard radius="rounded">
      <div class="columns column">
        <h3 class="title is-5 mb-2 mr-1">Pasien </h3> <span> ( {{ totalData }}
          Results)</span>
      </div>
  
      <div class="columns  all-projects m-3 mt-0">
        <div class="columns is-multiline  projects-card-grid">
          <div class="column is-9">
            <a type="button" class="is-pulled-right mr-3" color="info" outlined raised>
              <RouterLink :to="{ name: 'module-registrasi-pasien-baru' }">
                <i class="fa fa-plus"></i> Pasien
                Baru
              </RouterLink>
            </a>
          </div>
          <div class="column is-3">
            <VControl class="is-pulled-right">
              <VSwitchBlock style="padding-top:3px" v-model="item.isbayi" label="Bayi" color="danger" />
            </VControl>
          </div>
          <div class="column is-9">
            <div class="flex-list-inner mb-4" v-if="ds_PASIEN.loading">
              <div class="flex-table-item grid-item mb-4" v-for="key in 5" :key="key">
                <VFlexTableCell :column="{ grow: true, media: true }">
                  <VPlaceloadAvatar size="medium" />
                  <VPlaceloadText :lines="2" width="30%" last-line-width="20%" class="mx-2" />
                </VFlexTableCell>
                <VFlexTableCell>
                  <VPlaceload width="100%" height="70px" class="mx-1 mt-2" />
                </VFlexTableCell>
                <VFlexTableCell>
                  <VPlaceload width="10%" height="20px" class="mx-1 mt-1" />
                  <VPlaceload width="10%" height="20px" class="mx-1 mt-1" />
                  <VPlaceload width="10%" height="20px" class="mx-1 mt-1" />
                  <VPlaceload width="10%" height="20px" class="mx-1 mt-1" />
                </VFlexTableCell>
                <VFlexTableCell :column="{ align: 'end' }">
                  <VPlaceload width="10%" class="mx-1" />
                </VFlexTableCell>
              </div>
            </div>
            <div class="flex-list-inner" v-else-if="ds_PASIEN.length === 0">
              <VPlaceholderSection title="Not found" subtitle="There is no data that match your query." class="my-6">
                <template #image>
                  <img class="light-image" src="/@src/assets/illustrations/placeholders/search-4.svg" alt="" />
                  <img class="dark-image" src="/@src/assets/illustrations/placeholders/search-4-dark.svg" alt="" />
                </template>
              </VPlaceholderSection>
            </div>
            <div v-else-if="ds_PASIEN.length > 0">
  
  
              <div class="grid-item mb-4" v-for="(items, i) in ds_PASIEN" :key="items.id">
                <div class="top-section">
                  <div class="head">
                    <div class="title-wrap">
                      <div class="columns">
                        <div class="column is-3">
                          <VAvatar :picture="items.foto" size="small" v-if="items.isfoto" />
                          <VAvatar size="small" :color="listColor[i]" :initials="items.initials" v-if="!items.isfoto" />
                        </div>
                        <div class="column is-12 mr-3">
                          <h3>{{ items.namapasien }}</h3>
                          <p>{{ items.nocm + (items.jeniskelamin == 'Perempuan' ? ' (P)' : ' (L)') }}</p>
                        </div>
                      </div>
                    </div>
  
                    <VDropdown icon="feather:more-vertical" spaced right v-tooltip.bubble="'AKSI'">
                      <template #content>
  
                        <a role="menuitem" href="#" class="dropdown-item is-media" @click="showModalGabungRM(items)">
                          <div class="icon">
                            <i aria-hidden="true" class="lnil lnil-archive"></i>
                          </div>
                          <div class="meta">
                            <span>Gabungkan No. RM</span>
                            <span>penggabungan norm</span>
                          </div>
                        </a>
  
                        <!-- <a role="menuitem" href="#" class="dropdown-item is-media" @click="dialogConfirm(items)">
                          <div class="icon">
                            <i aria-hidden="true" class="lnil lnil-trash"></i>
                          </div>
                          <div class="meta">
                            <span>Delete</span>
                            <span>hapus data ini</span>
                          </div>
                        </a> -->
  
                        <!-- <a role="menuitem" href="#" class="dropdown-item is-media" @click="riwayatPasien(items)">
                          <div class="icon">
                            <i aria-hidden="true" class="lnil lnil-archive"></i>
                          </div>
                          <div class="meta">
                            <span>Riwayat</span>
                            <span>melihat riwayat registrasi</span>
                          </div>
                        </a> -->
                        <hr class="dropdown-divider" />
  
  
                        <a role="menuitem" href="#" class="dropdown-item is-media" @click="pendaftaranBayi(items)"
                          v-if="items.jeniskelamin == 'Perempuan' && items.isbayi == null">
                          <div class="icon">
                            <i aria-hidden="true" class="lnil lnil-pencil"></i>
                          </div>
                          <div class="meta">
                            <span>Registrasi Bayi</span>
                            <span>pendaftaran Bayi Baru Lahir</span>
                          </div>
                        </a>
                        <!-- <a role="menuitem" href="#" class="dropdown-item is-media" @click="triggerTele(items)">
                          <div class="icon">
                            <i aria-hidden="true" class="lnil lnil-plane-alt"></i>
                          </div>
                          <div class="meta">
                            <span>Update EMR Telemedicine </span>
                            <span>kirim data ke telemedicine</span>
                          </div>
                        </a>
                       
                        
                         <a  role="menuitem" style="background: #41b983;margin-bottom: -6px;" class="dropdown-item is-media" @click="showModalAksesEMR(items)" v-if="kelompokUser == 'rekam-medis'">
                            <div class="icon">
                              <i aria-hidden="true" class="fas fa-unlock" style="color: aliceblue;"></i>
                            </div>
                            <div class="meta">
                            <span style="color:white">Buka Akses EMR</span>
                            </div>
                         </a> -->
  
                         <!-- <a role="menuitem" v-if="items.statusemr == null && kelompokUser == 'rekam-medis'"  style="background: #353333;margin-bottom: -6px;" class="dropdown-item is-media" @click="kunciEMR(items)">
                            <div class="icon">
                              <i aria-hidden="true" class="fas fa-lock" style="color: aliceblue;"></i>
                            </div>
                            <div class="meta">
                            <span style="color:white">Kunci Akses EMR</span>
                            </div>
                         </a> -->
                      </template>
                    </VDropdown>
  
                  </div>
                  <div class="body">
                    <div class="columns is-multiline">
                      <div class="column">
                        <h4 class="heading">Alamat</h4>
                        <p class="fs-075">{{ items.alamatlengkap }}</p>
                        <p class="fs-075">{{ items.nohp }}</p>
                      </div>
                      <div class="column">
                        <h4 class="heading">No Identitas</h4>
                        <p class="fs-075">NIK : {{ items.noidentitas }}</p>
                        <p class="fs-075">No BPJS : {{ items.nobpjs }}</p>
                      </div>
                      <div class="column">
                        <h4 class="heading">Lahir</h4>
                        <p class="fs-075">Tempat : {{ items.tempatlahir }}</p>
                        <p class="fs-075">Tgl : {{ items.tgllahir }}</p>
                      </div>
                      <div class="column">
                        <h4 class="heading">Satu sehat ID</h4>
                         <p class="fs-075">{{ items.ihs_number }}</p>
                         <h4 class="heading">Kebangsaan</h4>
                         <p class="fs-075">{{ items.kebangsaan }}</p>
                        <!-- <VTag :color="items.status_c" :label="items.ihs_number" /> -->
                      </div>
                      <div class="column">
                        <h4 class="heading">Status</h4>
                        <VTag :color="items.status_c" :label="items.status" />
                      </div>
                      <div class="column is-12">
                        <div class="progress-stats" style="margin-top: -10px;">
                          <span class="dark-inverted">Informasi kelengkapan pengisian data</span>
                          <span>{{ items.progress }}%</span>
                        </div>
                        <div class="progress-bar">
                          <VProgress :color="items.class_proggress" size="tiny" :value="items.progress" />
                        </div>
                      </div>
  
                    </div>
                  </div>
                </div>
  
                <div class="bottom-section is-custom">
                  <div class="foot-block" style="margin-top: 10px;">
                    <!-- <h4 class="heading">Action</h4> -->
                    <div class="developers">
                      <!-- <VButton type="button" icon="feather:eye" class="is-fullwidth mr-3" color="success" outlined raised
                        @click="detailPasien(items)">
                        Details </VButton> -->
                      <!-- <VButton type="button" icon="fas fa-stethoscope" class="is-fullwidth mr-3" color="primary" outlined
                        raised @click="emr(items)">EMR</VButton> -->
  
                      <VButton type="button" icon="feather:edit" class="is-fullwidth mr-3" color="info" outlined raised
                        @click="editPasien(items)">
                        Edit </VButton>
                      <VButton type="button" icon="feather:trash" class="is-fullwidth mr-3" color="danger" outlined raised
                        @click="dialogConfirm(items)">
                        Delete </VButton>
                      <VButton type="button" icon="fa fa-history" class="is-fullwidth mr-3" color="warning" outlined raised
                        @click="riwayatPasien(items)">
                        Riwayat </VButton>
                      <VButton type="button" icon="fa fa-history" class="is-fullwidth mr-3" color="warning" outlined raised
                        @click="riwayatSanata(items)">
                        Riwayat Sanata</VButton>
                      <!-- regis lab radiologi -->
                      <VButton type="button" icon="feather:arrow-right-circle" class="is-fullwidth mr-3" color="primary"
                        outlined raised @click="registrasiLab(items)" :disabled="items.status == 'Meninggal'"
                        v-if="items.tglmeninggal == null && (kelompokUser == 'laboratorium' || kelompokUser == 'radiologi')">
                        Registrasi
                      </VButton>
                      <!-- regis lab petugas-jenazah -->
                      <VButton type="button" icon="feather:arrow-right-circle" class="is-fullwidth mr-3" color="primary"
                        outlined raised @click="registrasiLab(items)"
                        v-if="items.tglmeninggal != null && kelompokUser == 'petugas-jenazah' ">
                        Registrasi
                      </VButton>
                      <!-- regis registrasi -->
                      <VButton type="button" icon="feather:arrow-right-circle" class="is-fullwidth mr-3" color="purple"
                        outlined raised @click="registrasi(items)" :loading="items.isLoading"
                        :disabled="items.status == 'Meninggal'"
                        v-if="items.tglmeninggal == null && (kelompokUser != 'laboratorium' && kelompokUser != 'radiologi')">
                        Registrasi
                      </VButton>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="dataTable-bottom">
              <div class="dataTable-info">Menampilkan {{ currentPage.page }} ke {{ currentPage.limit }}
                dari
                {{ totalData }} entri data
              </div>
            </div>
            <div class="is-pulled-bottoms">
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
            <!-- <VFlexPagination v-model:current-page="currentPage.page" class="mt-6" :item-per-page="currentPage.limit"
              :total-items="currentPage.rows" :max-links-displayed="10" /> -->
  
          </div>
          <div class="column is-3">
            <div class="columns is-multiline">
              <div class="column is-12">
                <VField>
                  <VControl icon="feather:search">
                    <input v-model="item.qnama" v-on:keyup.enter="filter()" type="text" class="input is-rounded"
                      placeholder="Filter Nama..." />
                  </VControl>
                </VField>
              </div>
              <div class="column is-6">
                <h3 class="title is-5 mb-2 mr-1">Filters </h3>
              </div>
              <div class="column is-6">
                <a @click="clearFilter()" type="button" class="is-pulled-right mr-3" color="info" outlined raised> Clear
                  All </a>
              </div>
              <div class="column is-12">
                <VField>
                  <VLabel>No RM</VLabel>
                  <VControl icon="feather:user">
                    <VInput type="text" v-model="item.qnocm" v-on:keyup.enter="filter()" placeholder="No RM" />
                  </VControl>
                </VField>
              </div>
              <div class="column is-12">
                <VField>
                  <VLabel>NIK</VLabel>
                  <VControl icon="feather:book">
                    <VInput type="text" v-model="item.qnik" v-on:keyup.enter="filter()" placeholder="NIK" />
                  </VControl>
                </VField>
              </div>
              <div class="column is-12">
                <VField>
                  <VLabel>No BPJS</VLabel>
                  <VControl icon="feather:book">
                    <VInput type="text" v-model="item.qbpjs" v-on:keyup.enter="filter()" placeholder="No BPJS" />
                  </VControl>
                </VField>
              </div>
              <div class="column is-12">
                <VControl class="prime-auto">
                  <VField>
                    <VLabel>Tanggal Lahir</VLabel>
                    <Calendar v-model="item.tglLahir" :locale="'id'" selectionMode="single" :showIcon="true" :manualInput="true" class="w-100" :dateFormat="H.dateTimeFormat().prime.date" :placeholder="H.dateTimeFormat().prime.date" />
                  </VField>
                </VControl>
              </div>
              <div class="column is-12">
                <VField>
                  <VLabel>Rows</VLabel>
                  <VControl icon="feather:book">
                    <VInput type="text" v-model="currentPage.limit" v-on:keyup.enter="filter()" placeholder="Rows" />
                  </VControl>
                </VField>
              </div>
              <!-- <div class="column is-12">
                <VField>
                  <VLabel>Alamat</VLabel>
                  <VControl icon="feather:map">
                    <VInput type="text" v-model="item.qalamat" v-on:keyup.enter="filter()" placeholder="Alamat" />
                  </VControl>
                </VField>
              </div> -->
              <div class="column is-12">
                <VButton @click="filter()" :loading="ds_PASIEN.loading" type="button" icon="feather:search"
                  class="is-fullwidth mr-3" color="info" raised> Apply Filters
                </VButton>
              </div>
            </div>
  
          </div>
        </div>
      </div>
  
    </VCard>
  
    <VModal :open="modalGabungRM" title="Penggabungan Duplikasi No RM" :noclose="false" size="large" actions="right"
      @close="modalGabungRM = false">
      <template #content>
        <form class="modal-form">
          <div class="column">
            <div class="columns is-multiline">
              <div class="column is-3">
                <VField>
                  <VLabel>No RM</VLabel>
                  <VControl>
                    <VInput type="text" v-model="item.normAsal" class="is-rounded_Z" disabled />
                  </VControl>
                </VField>
              </div>
              <div class="column is-6">
                <VField>
                  <VLabel>Nama Pasien</VLabel>
                  <VControl>
                    <VInput type="text" v-model="item.namaPasien" class="is-rounded_Z" disabled />
                  </VControl>
                </VField>
              </div>
              <div class="column is-3">
                <VField>
                  <VLabel class="required-field">Gabung Ke</VLabel>
                  <VControl>
                    <VInput type="text" v-model="item.normTujuan" class="is-rounded_Z" />
                  </VControl>
                </VField>
              </div>
            </div>
          </div>
        </form>
      </template>
      <template #action>
        <VButton icon="feather:save" @click="saveGabungRM(item)" :loading="isLoading" color="primary" raised>
          Simpan</VButton>
      </template>
    </VModal>
  
  </template>
  
  <script setup lang="ts">
  import { useWindowScroll } from '@vueuse/core'
  import { useApi } from '/@src/composable/useApi'
  import { ref, computed, watch, reactive } from 'vue'
  import { useRoute, useRouter } from 'vue-router'
  import { useHead } from '@vueuse/head'
  import ConfirmDialog from 'primevue/confirmdialog'
  import { useConfirm } from 'primevue/useconfirm'
  import MultiSelect from 'primevue/multiselect';
  import { useToaster } from '/@src/composable/toaster'
  import Dropdown from 'primevue/dropdown';
  import AutoComplete from 'primevue/autocomplete';
  import * as H from '/@src/utils/appHelper'
  import { useViewWrapper } from '/@src/stores/viewWrapper'
  import { useThemeColors } from '/@src/composable/useThemeColors'
  import DataTable from 'primevue/datatable';
  import Column from 'primevue/column';
  import { FilterMatchMode, FilterOperator } from "primevue/api";
  import { useUserSession } from '/@src/stores/userSession'
  import Calendar from 'primevue/calendar';
  useHead({
    title: 'Pasien - ' + import.meta.env.VITE_PROJECT,
  })
  useViewWrapper().setPageTitle('Pasien Lama')
  useViewWrapper().setFullWidth(true)
  const confirm = useConfirm();
  const total = ref(0)
  const date = ref(new Date())
  const item: any = reactive({})
  const totalData: any = ref(0)
  let listJK: any = ref([])
  let listAgama: any = ref([])
  let listGolonganDarah: any = ref([])
  let listStatusPerkawinan: any = ref([])
  let listPendidikan: any = ref([])
  let listPekerjaan: any = ref([])
  let listEtnis: any = ref([])
  let listKebangsaan: any = ref([])
  let sourceKelompokUser: any = ref([])
  let listNegara: any = ref([])
  let d_Pegawai: any = ref([])
  let d_KelompokUser: any = ref([])
  let detailAksesEMRPas: any = ref({})
  let ds_PASIEN: any = ref([])
  let listColor: any = ref(Object.keys(useThemeColors()))
  let dataSelect: any = ref({})
  const modalGabungRM = ref(false)
  const modalAksesEMR = ref(false)
  const isLoadSave = ref(false)
  const dataSourceRiwayat: any = ref([])
  const sourceKU: any = ref([])
  const route = useRoute()
  const isbayi = ref(false)
  const dates = ref();
  const router = useRouter()
  const { y } = useWindowScroll()
  const kelompokUser = useUserSession().getUser().kelompokUser.kelompokUser
  const kelompokUserID = useUserSession().getUser().kelompokUser.id
  const pegawaiId = useUserSession().getUser().pegawai.id
  
  const filters = ref({
    global: { value: null, matchMode: FilterMatchMode.CONTAINS },
  
  });
  const isStuck = computed(() => {
    return y.value > 30
  })
  const currentPage: any = ref({
    limit: 5,
    rows: 50
  })
  for (var i = listColor.value.length - 1; i >= 0; i--) {
    const element = listColor.value[i];
    if (element == 'primary') {
      listColor.value.splice(i, 1);
    }
  }
  currentPage.value.page = computed(() => {
    try {
      return Number.parseInt(route.query.page as string) || 1
    } catch { }
    return 1
  })
  watch(
    () => currentPage.value.page,
    (newValue, oldValue) => {
      if (newValue != oldValue) {
        fetchPasien()
      }
    }
  )
  watch(
    () => currentPage.value.limit,
    (newValue, oldValue) => {
      if (newValue != oldValue) {
        fetchPasien()
      }
    }
  )
  
  
  async function fetchPasien() {
    ds_PASIEN.value.loading = true
  
    let searchQuery = `&q=`
    let limit: any = currentPage.value.limit
    let offset: any = route.query.page ? route.query.page : 1
    offset = (parseInt(offset) - 1) * limit
    // let offset=''
    let page: any = route.query.page ? route.query.page : 1
    let namapasien = ''
    let nocm = ''
    let nik = ''
    let nobpjs = ''
    let alamat = ''
    let tgllahir = ''
    if (item.qnama) namapasien = `&namapasien=${item.qnama}`
    if (item.qnocm) nocm = `&nocm=${item.qnocm}`
    if (item.qnik) nik = `&nik=${item.qnik}`
    if (item.qbpjs) nobpjs = `&bpjs=${item.qbpjs}`
    if (item.qalamat) alamat = `&alamat=${item.qalamat}`
    if (item.tglLahir) tgllahir = `&tgllahir=${H.formatDate(item.tglLahir, 'YYYY-MM-DD')}`
  
    H.cacheInput().set('pasienLama', item);
    totalData.value = 0
    // Ditutup Dulu
    // await dropdown()
    //
    const response = await useApi().get(`/registrasi/pasien-lama?page=${page}&offset=${offset}&limit=${limit}&rows=${currentPage.value.rows}&isbayi=${item.isbayi}${namapasien}${nocm}${nik}${nobpjs}${alamat}${tgllahir}`)
    let pasien = response.data
    totalData.value = response.to
    for (let x = 0; x < pasien.length; x++) {
      const element = pasien[x];
      let ini = element.namapasien.split(' ')
      let init = element.namapasien.substr(0, 1)
      if (ini.length > 1) {
        init = init + ini[1].substr(0, 1)
      }
      element.initials = init
      element.status = "Hidup";
      element.status_c = "purple";
      if (element.tglmeninggal != null) {
          element.status = 'Meninggal';
          element.status_c = 'danger';
      }
      if (element.progress <= 50) {
        element.class_proggress = 'danger';
      }
      if (element.progress > 50 && element.progress <= 80) {
          element.class_proggress = 'warning';
      }
      if (element.progress > 80) {
          element.class_proggress = 'success';
      }
  
      const birthDate = new Date(element.tgllahir);
      const today = new Date();
  
      let years = today.getFullYear() - birthDate.getFullYear();
      let months = today.getMonth() - birthDate.getMonth();
      let days = today.getDate() - birthDate.getDate();
      if (days < 0) {
        months--;
        const previousMonth = new Date(today.getFullYear(), today.getMonth(), 0).getDate();
        days += previousMonth;
      }
  
      if (months < 0) {
        years--;
        months += 12;
      }
      element.umur = `${years}thn ${months.toString().padStart(2, '0')}bln ${days.toString().padStart(2, '0')}hr`;
    }
  
    ds_PASIEN.value.loading = false
    ds_PASIEN.value = pasien
    // ds_PASIEN.value.total = pasien.length
  }
  
  const fetchPegawai = async(filter :any)=>{
    const response = await useApi().get(`/emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`)
      d_Pegawai.value = response
  }
  
  
  function savePasien() {
    if (!item.nik) {
      useToaster().error('NIK harus di isi')
      return
    }
    if (!item.nobpjs) {
      useToaster().error('No BPJS harus di isi')
      return
    }
    if (!item.namapasien) {
      useToaster().error('Nama harus di isi')
      return
    }
  }
  
  const emr = (e: any) => {
    // H.checkAksesEMR(e,kelompokUser,kelompokUserID,pegawaiId)
    // console.log()
    // H.checkAksesEMR(e,kelompokUser,kelompokUserID,pegawaiId)
    // H.cacheHelper().set('xxx_cache_menu', undefined)
    // router.push({
    //     name: 'module-emr-profile-pasien',
    //     query: {nocmfk: e.id}
    // })
  }
  
  function editPasien(e: any) {
    router.push({
      name: 'module-registrasi-pasien-baru',
      query: {
        id: e.id,
      },
    })
  }
  
  function pendaftaranBayi(e: any) {
    router.push({
      name: 'module-registrasi-pasien-bayi',
      query: {
        id: e.id,
      },
    })
  }
  
  const dialogConfirm = (e: any) => {
    confirm.require({
      message: 'Apakah anda serius menghapus data ini ?',
      header: 'Konfirmasi Hapus Data',
      icon: 'pi pi-info-circle',
      acceptClass: 'p-button-danger',
      accept: () => {
        hapusPasien(e)
      },
      reject: () => { },
    })
  }
  
  const showModalGabungRM = (e: any) => {
    modalGabungRM.value = true
    item.namaPasien = e.namapasien
    item.normAsal = e.nocm
  }
  
  const saveGabungRM = async (e: any) => {
    if (!e.normTujuan) {
      H.alert('error', 'No RM Tujuan Tidak Boleh Kosong')
      return
    }
  
    let objSave = {
      'normAsal': e.normAsal,
      'normTujuan': e.normTujuan,
    }
  
    await useApi().post('dashboard/registrasi/gabung-norm', objSave).then((response: any) => {
      fetchPasien()
      modalGabungRM.value = false
    }).catch((e: any) => {
  
    })
  
  }
  
  function hapusPasien(e: any) {
    useApi().post(
      `/registrasi/delete-pasien`, { 'id': e.id }).then((response: any) => {
        fetchPasien()
      }).catch((e: any) => {
  
      })
  }
  function riwayatPasien(e: any) {
    dataSelect.value = e
    router.push({
      name: 'module-registrasi-riwayat-registrasi',
      query: {
        nocmfk: e.id,
      },
    })
  }
  
  function riwayatSanata(e: any) {
    window.open('https://his.balimandarahospital.com:8000/riwayatpx/' + e.nocm, '_blank')
  }
  
  const sourceDetailAksesEMR = async(e:any)=>{
  
    let tglBerakhir = new Date(e.tglberakhir)
    let tglSekarang = new Date()
    if(tglBerakhir > tglSekarang){
      await useApi().get(`pasien/show-akses-emr?pasienfk=${e.id}`).then((response)=>{
        detailAksesEMRPas.value = response
      })
    }
  
  }
  
  const showModalAksesEMR = async (e:any)=>{
    await sourceDetailAksesEMR(e)
    if(detailAksesEMRPas.value.pasienfk){
      sourceKU.value = detailAksesEMRPas.value.objectkelompokuserfk.split(",")
      d_KelompokUser.value.forEach(elem => {
        sourceKU.value.forEach(el => {
          if(elem.value == parseInt(el)){
            sourceKelompokUser.value.push(elem)
          }
        });
      });
      item.pegawaiPengakses = detailAksesEMRPas.value.pegawaipemohonfk ? {label : detailAksesEMRPas.value.namalengkap , value : detailAksesEMRPas.value.pegawaipemohonfk} : null
      item.tglMulai = detailAksesEMRPas.value.tglmulai
      item.tglAkhir = detailAksesEMRPas.value.tglberakhir
      item.ketAkses = detailAksesEMRPas.value.deskripsi
    }
    modalAksesEMR.value = true
    dataSelect.value = e
  }
  
  const clearModal = ()=>{
    detailAksesEMRPas.value = []
    sourceKelompokUser.value = []
    delete item.pegawaiPengakses
    delete item.tglMulai
    delete item.tglAkhir
    delete item.ketAkses
  }
  
  const saveAksesEMR = async (e: any)=>{
    // console.log(dataSelect.value.namapasien)
    let itemsKelompokUser = []
    if (sourceKelompokUser.value != undefined) {
        sourceKelompokUser.value.forEach((element: any) => {
          itemsKelompokUser.push(element.value)
        });
    }
  
    let objSave = {
      nocmfk : dataSelect.value.id,
      tglAwal : H.formatDate(item.tglMulai, 'YYYY-MM-DD'),
      tglAkhir : H.formatDate(item.tglAkhir, 'YYYY-MM-DD'),
      pasien : dataSelect.value.namapasien,
      petugaspemohon : item.pegawaiPengakses.value,
      kelompokuser : itemsKelompokUser,
      keterangan : e.ketAkses,
    }
    isLoadSave.value = true
    await useApi().post(`pasien/buka-emr`,objSave).then((response)=>{
      modalAksesEMR.value = false
      isLoadSave.value = false
      fetchPasien()
    }).catch((e)=>{
      isLoadSave.value = false
    })
  
    clearModal()
  }
  
  const kunciEMR = async (e:any)=>{
   let objSave = {
      nocmfk : e.id,
      pasien : e.namapasien,
    }
     await useApi().post(`pasien/kunci-emr`, objSave).then((response)=>{
      fetchPasien()
    })
  
  }
  const registrasi = async (e: any) => {
    e.isLoading = true
    let response = await useApi().get(`/registrasi/cek-pasien-pulang?id=${e.id}`)
    e.isLoading = false
    e.isLoading = true
    let responsex = await useApi().get(`/registrasi/cek-pasien-piutang?nocm=${e.nocm}`)
    e.isLoading = false
    console.log(e)
    if(e.objectkebangsaanfk == null){
      useToaster().error('Kebangsaan pasien belum diisi')
      return
    }
    if (response != null) {
      useToaster().error('Pasien belum dipulangkan')
      return
    }
    if (responsex.piutang != null) {
      useToaster().error('Pasien mempunyai piutang yang belum dibayar')
      return
    }
    if (responsex.closing != null) {
      useToaster().error('Pasien belum diclosing pada kunjungan sebelumnya')
      return
    }
    router.push({
      name: 'module-registrasi-registrasi-ruangan',
      query: {
        nocmfk: e.id,
        statuspasien: "LAMA",
      },
    })
  }
  function registrasiLab(e: any) {
    router.push({
      name: 'module-registrasi-registrasi-ruangan-lab',
      query: {
        nocmfk: e.id,
      },
    })
  }
  function clearFilter() {
    delete item.qnama
    delete item.qnocm
    delete item.qnik
    delete item.qbpjs
    delete item.qalamat
    delete item.tglLahir
    fetchPasien()
  }
  function filter() {
    fetchPasien()
  }
  const triggerTele = async(e:any) =>{
    try {
      let response = await useApi().get(`/tele/trigger-telemedicine?nocm=${e.nocm}`)
      if(response.error){
        H.alert('error', response.res)
      }else{
        H.alert('success', 'Sukses')
      }
    } catch (error) {
      
    }
  }
  let caches = H.cacheInput().get('pasienLama');
  if (caches) {
   Object.keys(caches).forEach((key) => {
      if (caches[key] !== undefined) {
        item[key] = caches[key];
      }
    });
  }
  // cache helper
  watch(
    () => item.isbayi,
    () => {
      fetchPasien()
    }
  )
  
  fetchPasien()
  // listDropdown()
  
  </script>
  <style lang="scss">
  @import '/@src/scss/abstracts/all';
  
  @import '/@src/scss/components/forms-outer';
  @import '/@src/scss/custom/config';
  @import '/@src/scss/custom/listview';
  </style>
  <route lang="yaml">
  meta:
    requiresAuth: true
  </route>
  