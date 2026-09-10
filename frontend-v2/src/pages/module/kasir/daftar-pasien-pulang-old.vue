
<template>
    <ConfirmDialog />
    <VCard>
      <div class="columns column">
        <h3 class="title is-5 mb-2 mr-1">Daftar Pasien Pulang </h3> <span>({{ ds_PASIEN.total }} Totals)</span>
      </div>
      <div class="columns  all-projects m-3 mt-0">
        <div class="columns is-multiline  projects-card-grid">
          <div class="column is-8">
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
                </VFlexTableCell>
                <VFlexTableCell :column="{ align: 'end' }">
                  <VPlaceload width="10%" class="mx-1" />
                </VFlexTableCell>
              </div>
            </div>
            <div class="flex-list-inner" v-else-if="ds_PASIEN.length === 0">
              <VCard>
                <VPlaceholderSection title="Data Tidak Ditemukan" subtitle="Silakan Pilih Tanggal Pasien Pulang."
                  class="my-6">
                  <template #image>
                    <img class="light-image" src="/@src/assets/illustrations/placeholders/search-4.svg" alt="" />
                    <img class="dark-image" src="/@src/assets/illustrations/placeholders/search-4-dark.svg" alt="" />
                  </template>
                </VPlaceholderSection>
              </VCard>
            </div>
            <div v-else-if="ds_PASIEN.length > 0">
              <div class="grid-item mb-4" v-for="(items, i) in ds_PASIEN" :key="items.id">
                <div class="top-section">
                  <div class="head">
                    <div class="title-wrap">
                      <div class="columns">
                        <div class="column is-3">
                          <VAvatar size="small" :color="listColor[i]" :initials="items.initials" />
                        </div>
                        <div class="column is-12 mr-3">
                          <h3>{{ items.namaPasien }}</h3>
                          <p>{{ items.noRegistrasi + (items.jeniskelamin == 'PEREMPUAN' ? ' (P)' : ' (L)')
                          }}</p>
                        </div>
                      </div>
                    </div>
                    <!-- <ProjectCardDropdown /> -->
                  </div>
                  <div class="body">
                    <div class="columns">
                      <div class="column">
                        <h4 class="heading">Registrasi</h4>
                        <p class="fs-075">{{ items.namaRuangan }}</p>
                        <p class="fs-075">{{ items.tanggalMasuk }}</p>
                      </div>
                      <div class="column">
                        <h4 class="heading"> Dokter Pemeriksa </h4>
                        <p class="fs-075"> {{ items.dokter }}</p>
                        <p class="fs-075"> Kebangsaan: {{ items.kebangsaan }}</p>
                      </div>
                      <div class="column">
                        <h4 class="heading">Tanggal Pulang</h4>
                        <p class="fs-075">{{ items.tanggalPulang }}</p>
                        <p class="fs-075"> Jenis Pasien : {{ items.jenisAsuransi }}</p>
  
                      </div>
                      <div class="column">
                        <h4 class="heading">Status</h4>
                        <VTag color="info" :label="items.status" />
                        <p class="fs-075"> No. SEP : {{ items.nosep }}</p>
                      </div>
                      <div class="column">
                        <h4 class="heading">Status Closing</h4>
                        <VTag color="primary" :label="items.statusclosing" v-if="items.statusclosing == 'Sudah Closing'"/>
                        <VTag color="danger" :label="items.statusclosing" v-else/>
                      </div>
                      <div class="column">
                        <h4 class="heading">Status Piutang</h4>
                        <VTag color="danger" :label="items.statuspiutang" v-if="items.statuspiutang == '-'"/>
                        <VTag color="primary" :label="items.statuspiutang" v-else/>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="bottom-section">
                  <div class="foot-block">
  
                    <div class="developers">
                      <VButton type="button" icon="fas fa-notes-medical" class="is-fullwidth mr-3" color="info" outlined
                        raised @click="verifikasi(items)">
                        Verifikasi </VButton>
                      <VButton type="button" icon="fas fa-times-circle" class="is-fullwidth mr-3" color="warning" outlined raised @click="batalPulang(items)">
                        Batal Pulang </VButton>
                      <VButton type="button" icon="fas fa-times-circle" class="is-fullwidth mr-3" color="warning" outlined
                        raised @click="batalVerif(items)" :loading="isLoading" v-if="items.status != 'Belum Verifikasi'">
                        Batal Verifikasi </VButton>
                      <VButton type="button" icon="fa fa-history" class="is-fullwidth mr-3" color="info" outlined raised @click="closingPemeriksaan(items)" style="display: none !important">
                        Closing Pemeriksaan </VButton>
                      <VButton type="button" icon="fas fa-times-circle" class="is-fullwidth mr-3" color="warning" outlined raised @click="saveClosingPemeriksaan(items, null)" v-if="items.statusclosing == 'Sudah Closing'">
                        Batal Closing </VButton>
                      <VButton type="button" icon="fas fa-times-circle" class="is-fullwidth mr-3" color="info" outlined raised @click="updatePiutang(items)" style="display: none !important">
                        Piutang </VButton>
                      <VButton type="button" icon="fas fa-times-circle" class="is-fullwidth mr-3" color="warning" outlined
                        raised @click="batalPiutang(items)" :loading="isLoading" v-if="items.statuspiutang != '-'">
                        Batal Piutang </VButton>
                    </div>
                  </div>
                </div>
  
              </div>
            </div>
            <VFlexPagination v-model:current-page="currentPage.page" :item-per-page="currentPage.limit"
              :total-items="ds_PASIEN.total" :max-links-displayed="5">
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
          <div class="column is-4">
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
                  <VLabel> Tanggal Pulang </VLabel>
                  <VDatePicker v-model="item.periode" is-range color="pink" trim-weeks>
                    <template #default="{ inputValue, inputEvents }">
                      <VField addons>
                        <VControl icon="feather:calendar">
                          <VInput :value="inputValue.start" v-on="inputEvents.start" />
                        </VControl>
                        <VControl>
                          <VButton static icon="feather:arrow-right" />
                        </VControl>
                        <VControl subcontrol icon="feather:calendar">
                          <VInput :value="inputValue.end" v-on="inputEvents.end" />
                        </VControl>
                      </VField>
                    </template>
                  </VDatePicker>
                </VField>
              </div>
              <!-- <div class="column is-12">
                <VField>
                  <VLabel>No RM</VLabel>
                  <VControl icon="feather:user">
                    <VInput type="text" v-model="item.qnocm" v-on:keyup.enter="filter()" placeholder="No RM" />
                  </VControl>
                </VField>
              </div>
              <div class="column is-12">
                <VField>
                  <VLabel>No Regisrasi</VLabel>
                  <VControl icon="feather:book">
                    <VInput type="text" v-model="item.qnoregistrasi" v-on:keyup.enter="filter()"
                      placeholder="No Regisrasi" />
                  </VControl>
                </VField>
              </div> -->
              <div class="column is-12">
                <VField class="is-autocomplete-select" v-slot="{ id }">
                  <VLabel>Kelompok Pasien</VLabel>
                  <VControl icon="feather:search">
                    <Multiselect v-model="item.kelompokPasienId" :options="listKelompokPasien"
                      placeholder="Pilih data" :searchable="true" :attrs="{ id }" />
                  </VControl>
                </VField>
              </div>
              <div class="column is-12" style="width: 450px;">
                <VField>
                  <VLabel>Ruangan</VLabel>
                  <VControl>
                    <MultiSelect v-model="sourceRuangan" display="chip" :options="d_Ruangan" optionLabel="label"
                      filter placeholder="Pilih Ruangan" :maxSelectedLabels="3" style="display:flex"
                      @change="changeRuang(sourceRuangan)" />
                  </VControl>
                </VField>
              </div>
              <div class="column is-12" style="display: none !important">
                <VField class="is-rounded-select_Z  is-autocomplete-select" v-slot="{ id }">
                  <VLabel>Ruangan</VLabel>
                  <VControl icon="feather:search" fullwidth class="prime-auto ">
                    <AutoComplete v-model="item.ruangan" :suggestions="listRuangan"
                      @complete="fetchRuangan($event)" :optionLabel="'nama'" :dropdown="true" :minLength="3"
                      :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'nama'"
                      placeholder="Ketik nama Ruangan (min. 3 karakter)" />
                  </VControl>
                </VField>
                <!-- <VField class="is-autocomplete-select" v-slot="{ id }">
                  <VLabel>Ruangan</VLabel>
                  <VControl icon="feather:search">
                    <Multiselect mode="single" v-model="item.ruanganId" :options="listRuangan"
                      placeholder="Pilih data" :searchable="true" :attrs="{ id }" />
                  </VControl>
                </VField> -->
              </div>
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
    <Dialog v-model:visible="modalDetail" modal header="Detail Verifikasi" :style="{ width: '40vw' }">
      <div class="columns is-multiline">
        <div class="column is-12">
          <div class="flex-list-inner mb-2 mt-5" v-if="isLoadingDetail">
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
          <div class="flex-list-inner" v-else-if="dataSourceDetail.length === 0">
            <VPlaceholderSection :title="H.assets().notFound" class="my-6">
              <template #image>
                <img class="light-image" :src="H.assets().iconNotFound_rev" alt="" style="width: 100px;" />
                <img class="dark-image" src="/@src/assets/illustrations/placeholders/search-4-dark.svg" alt=""
                  style="width: 100px;" />
              </template>
            </VPlaceholderSection>
          </div>
          <div v-else-if="dataSourceDetail.length > 0" style="max-height: 400px;overflow-x: auto;padding: 10px;">
            <VFlexTable v-if="dataSourceDetail.length" :data="dataSourceDetail" :columns="columns" rounded>
              <template #body>
                <div name="list" tag="div" class="flex-list-inner">
                  <!--Table item-->
                  <div v-for="(item, i)  in dataSourceDetail" :key="item.id" class="flex-table-item">
  
                    <VFlexTableCell>
                      <span class="light-text">{{ H.formatDateNoTime(item.tglstruk) }}</span>
                    </VFlexTableCell>
                    <VFlexTableCell>
                      <span class="light-text">{{ item.nostruk }}</span>
                    </VFlexTableCell>
  
                    <VFlexTableCell>
                      <VTag :label="item.status" color="warning" rounded elevated
                        style="font-size:0.8rem;font-weight: bold;" v-if="item.status == 'Belum Bayar'" />
                      <VTag :label="item.status" color="success" rounded elevated
                        style="font-size:0.8rem;font-weight: bold;" v-if="item.status == 'Lunas'" />
                    </VFlexTableCell>
                    <VFlexTableCell>
                      <span class="light-text is-pulled-right">{{ H.formatRp(item.totalharusdibayar, 'Rp. ') }}</span>
                    </VFlexTableCell>
                    <VFlexTableCell>
                      <span class="light-text">{{ item.petugasverif }}</span>
                    </VFlexTableCell>
                    <VFlexTableCell :column="{ align: 'end' }">
                      <VIconButton circle icon="fas fa-times-circle" color="danger" raised bold @click="batalVerif(item)"
                        v-tooltip.bubble="'Batal Verifikasi'">
                      </VIconButton>
                    </VFlexTableCell>
                  </div>
                </div>
              </template>
            </VFlexTable>
            <VCard>
              <div class="columns is-multiline">
                <div class="column is-4 is-offset-8">
                  <b>TOTAL : {{ H.formatRp(item.totalAll, 'Rp. ') }}</b>
                </div>
              </div>
            </VCard>
  
          </div>
        </div>
      </div>
    </Dialog>
    
    <Dialog v-model:visible="modalUpdatePiutang" modal header="Update Status Piutang" :style="{ width: '25vw' }">
      <div class="column">
        <span style="font-weight: 500;">Status Piutang</span>
        <VField class="is-autocomplete-select pt-3">
          <VControl>
              <Multiselect v-model="item.statusPiutang" :attrs="{ value }"
                  placeholder="--Pilih--" label="label" :options="d_Status"
                  :searchable="true" track-by="label" mode="single" autocomplete="off">
              </Multiselect>
          </VControl>
        </VField>
      </div>
      <template #footer>
        <VButton color="danger" icon="pi pi-times" outlined raised @click="modalUpdatePiutang = false"> Batal </VButton>
        <VButton color="primary" icon="pi pi-check" raised @click="saveUpdatePiutang()" :loading="isLoading"> Simpan
        </VButton>
      </template>
    </Dialog>
  </template>
  <script setup lang="ts">
  import { useWindowScroll } from '@vueuse/core'
  import { useApi } from '/@src/composable/useApi'
  import { ref, computed, watch, reactive } from 'vue'
  import * as H from '/@src/utils/appHelper'
  import { useRoute, useRouter } from 'vue-router'
  import { useHead } from '@vueuse/head'
  import { useToaster } from '/@src/composable/toaster'
  import { useViewWrapper } from '/@src/stores/viewWrapper'
  import { useThemeColors } from '/@src/composable/useThemeColors'
  import AutoComplete from 'primevue/autocomplete';
  import moment from 'moment'
  import ConfirmDialog from 'primevue/confirmdialog'
  import Dialog from 'primevue/dialog';
  import { useConfirm } from 'primevue/useconfirm'
  import MultiSelect from 'primevue/multiselect';
  
  useHead({
    title: 'Daftar Pasien Pulang - ' + import.meta.env.VITE_PROJECT,
  })
  useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
  const item: any = ref({
    periode: reactive({
      start: new Date(),
      end: new Date(),
      norec_pd: '',
    })
  })
  
  
  let listKelompokPasien: any = ref([])
  let listRuangan: any = ref([])
  const valueKelompok: any = ref(0);
  const d_Ruangan: any = ref([])
  let sourceRuangan: any = ref([])
  
  let ds_PASIEN: any = ref([])
  let listColor: any = ref(Object.keys(useThemeColors()))
  const router = useRouter()
  const route = useRoute()
  const { y } = useWindowScroll()
  const confirm = useConfirm();
  const isLoading = ref(false)
  const modalDetail = ref(false)
  const dataSourceDetail: any = ref([])
  const modalUpdatePiutang = ref(false)
  const d_Status: any = ref([])
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
  watch(currentPage.value, () => {
    fetchPasien()
  })
  
  async function fetchPasien() {
    ds_PASIEN.value.loading = true
  
    let searchQuery = `&q=`
    let limit: any = currentPage.value.limit
    let offset: any = route.query.page ? route.query.page : 1
    offset = (offset * limit) - limit
    let namaPasien = ''
    let queryKelompok = ''
  
    let ruanganid = ''
    if (sourceRuangan.value != undefined) {
      let itemsRuang = []
      sourceRuangan.value.forEach((element: any) => {
          itemsRuang = [...new Set([...itemsRuang, element.value])]
      });
            ruanganid = `&ruanganfk=${itemsRuang}`
    }
  
    if (item.value.qnama) namaPasien = `&namaPasien=${item.value.qnama}`
    if(item.value.kelompokPasienId != undefined && item.value.kelompokPasienId != null) queryKelompok = `&kelompokPasienId=${item.value.kelompokPasienId}`
    if(item.value.ruangan != undefined && item.value.ruangan.id != null) queryKelompok += `&ruanganId=${item.value.ruangan.id}`
  
    const response = await useApi().get(
      `/kasir/daftar-pasien-pulang?tglAwal=${moment(item.value.periode.start).format('YYYY-MM-DD')
      }&tglAkhir=${moment(item.value.periode.end).format('YYYY-MM-DD')}&statusverifikasi=true&offset=${offset}&limit=${limit}&rows=${currentPage.value.rows}${ruanganid}${namaPasien}${queryKelompok}`)
    ds_PASIEN.value.loading = false
    for (let x = 0; x < response.data.length; x++) {
      const element = response.data[x];
      let ini = element.namaPasien.split(' ')
      let init = element.namaPasien.substr(0, 1)
      if (ini.length > 1) {
        init = init + ini[1].substr(0, 1)
      }
      element.initials = init
    }
  
    ds_PASIEN.value = response.data
    ds_PASIEN.value.total = response.total
    route.query.page = '1'
    listKelompokPasien.value = response.listkelompok.reduce((obj, item) => (obj[item.id] = item.nama, obj) ,{});
    console.log(listKelompokPasien)
  }
  
  async function fetchRuangan(filter: any) {
    if (!filter.query) return
    if (filter.query.length < 3) return
  
    let url = `/pasien/get-poli-kontrol?search=${encodeURI(filter.query)}`;
  
    useApi()
    .get(url)
    .then((res: any) => {
      listRuangan.value = res;
    })
  }
  
  async function fetchDropdown() {
    const response = await useApi().get(`/dashboard/dropdown-rawat-jalan`)
    d_Ruangan.value = response.ruangan.map((e: any) => { return { label: e.namaruangan, value: e.id, default: e } })
    if(H.cacheHelper().get('ruanganDipilih') && H.cacheHelper().get('ruanganDipilih').length > 0) {
      sourceRuangan.value = H.cacheHelper().get('ruanganDipilih')
    }else {
      d_Ruangan.value.forEach((element) => {
        sourceRuangan.value.push(element)
      })
    }
  }
  
  async function verifikasi(e: any) {
    await H.statusClosingPasien(e.noregistrasi);
    router.push({
      name: 'module-kasir-verifikasi-tagihan',
      query: {
        nocmfk: e.nocmfk,
        norec_pd: e.norec_pd,
      },
    });
  }
  
  async function batalVerif(e: any) {
    console.log("BATAL", e)
    let json = {
      'norec_pd': e.norec_pd,
      'norec_sp': e.nostruklastfk
    }
    isLoading.value = true
    await useApi()
      .post('/dashboard/daftar-pasien-pulang/batal-verif', json)
      .then((response: any) => {
        isLoading.value = false
        fetchPasien()
        detailVerifikasi(e)
      }, (err => {
        isLoading.value = false
      }))
  
  }
  
  async function batalPiutang(e: any) {
    console.log("BATAL", e)
    let json = {
      'norec_pd': e.norec_pd,
    }
    isLoading.value = true
    await useApi()
      .post('/dashboard/daftar-pasien-pulang/batal-piutang', json)
      .then((response: any) => {
        isLoading.value = false
        fetchPasien()
      }, (err => {
        isLoading.value = false
      }))
  
  }
  
  async function detailVerifikasi(e: any) {
    isLoading.value = true
    dataSourceDetail.value = []
    item.value.totalAll = 0
    await useApi()
      .get('/dashboard/daftar-pasien-pulang/detail-verif?noregistrasi=' + e.noRegistrasi)
      .then((response: any) => {
        isLoading.value = false
        for (let x = 0; x < response.length; x++) {
          const element = response[x];
          item.value.totalAll = item.value.totalAll + parseFloat(element.totalharusdibayar)
        }
        dataSourceDetail.value = response
        modalDetail.value = true
      })
  }
  
  function billing(e: any) {
    useApi().post('/general/save-jurnal-pelayananpasien_t-noreg', {'noregistrasi' : e.noRegistrasi} )
    router.push({
      name: 'module-kasir-billing',
      query: {
        norec_pasien_daftar: e.norec_pd,
      },
    })
  }
  
  function batalPulang(e: any) {
  
  useApi().post('/rawatinap/batal-pulang-pasien',{'norec_pd' : e.norec_pd}).then((response)=>{
    fetchPasien()
  })
  }
  
  const updatePiutang = async (e:any)=> {
    
    await useApi().get(
        `emr/dropdown/statuspiutang_m?select=id,statuspiutang&param_search=statuspiutang`
      ).then((response) => {
        d_Status.value = response
        item.value.norec_pd = e.norec_pd
        modalUpdatePiutang.value = true
      })
  }
  
  const closingPemeriksaan = async (data: any) => {
    console.log(data)
    const tindakan = await useApi().get(`kasir/list-tindakan-pasien?norec_pd=${data.norec_pd}`)
    if(tindakan.length > 0){
      H.alert("error","Ada tindakan yang belum terverifikasi !");
      return;
    }
    if (data.statusbayar == "Belum Verifikasi" ) {
      H.alert("warning","Pasien Belum DiVerifikasi !");
      return;
    }
    const response = await useApi().get(`general/get-penunjang-close?noregistrasi=${data.norec_pd}`)
    let msg = response.length > 0 ? `Pasien ${response[0].namapasien} masih memiliki order penunjang. closing Pemeriksaan pasien, Lanjut simpan ?` : 'Selesai Periksa akan menutup / closing Pemeriksaan pasien, Lanjut simpan ?';
    confirm.require({
          message:msg,
          header: 'Konfirmasi',
          icon: 'pi pi-check-circle',
          acceptClass: 'p-button-success',
          accept: () => {
            saveClosingPemeriksaan(data ,true)
          },
          reject: () => { },
      })
  }
  
  const saveClosingPemeriksaan = async (data:any,status:any)=>{
    let objectSave ={
      close : status,
      noregistrasi : data.noRegistrasi,
    }
    await useApi().post('/kasir/closing-pemeriksaan', objectSave).then((response: any) => {
      fetchPasien()
      H.cacheHelper().set('status_closing',null)
    })
  }
  
  const saveUpdatePiutang = async (data:any,status:any)=>{
    console.log(item.value.statusPiutang)
    let objectSave ={
      norec : item.value.norec_pd,
      objectstatuspiutangfk : item.value.statusPiutang
    }
    isLoading.value = true
    await useApi().post('/kasir/save-piutang', objectSave).then((response: any) => {
      isLoading.value = false
      modalUpdatePiutang.value = false
      fetchPasien()
    })
  }
  
  
  function clearFilter() {
    delete item.value.qnama
    delete item.value.qnocm
    delete item.value.qnik
    delete item.value.qbpjs
    delete item.value.qalamat
    delete item.value.kelompokPasienId
    if(item.value.ruangan != undefined && item.value.ruangan.id != null) delete item.value.ruangan
    fetchPasien()
  }
  function filter() {
    fetchPasien()
  }
  function transaksiPelayanan(select: any) {
    router.push({ name: 'module-farmasi-transaksi-pelayanan-farmasi', params: { norec_pd: select.norec_pd } })
  }
  
  const changeRuang = async (e: any) => {
          // setCache(e)
          H.cacheHelper().set('ruanganDipilih', sourceRuangan.value)
          fetchPasien()
          // fetchReservasi()
          // fetchJadwalDokter(items)
      }
  
  
  // const fetchStatus = async (filter: any) => {
  //     await useApi().get(
  //       `emr/dropdown/statuspiutang_m?select=id,statuspiutang&param_search=statuspiutang`
  //     ).then((response) => {
  //       d_Status.value = response
  //     })
  //   }
  
  // fetchStatus()
  fetchPasien()
  fetchDropdown()
  
  
  </script>
  <style lang="scss">
  @import '/@src/scss/abstracts/all';
  
  @import '/@src/scss/components/forms-outer';
  
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
  </style>
  