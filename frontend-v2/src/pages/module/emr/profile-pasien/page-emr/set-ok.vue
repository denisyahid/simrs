<template>
    <div class="form-layout is-stacked-2">
      <div class="form-outer" style="margin-top:15px">
        <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
          <div class="form-header-inner">
            <div class="left">
              <h3> {{ props.FORM_NAME }}</h3>
            </div>
            <div class="right">
              <VButton icon="lnir lnir-arrow-left rem-100" light dark-outlined @click="kembaliKeun()">
                Kembali
              </VButton>
            </div>
          </div>
        </div>
  
        <div class="column is-12">
          <div v-if="isLoadingPasien">
            <VPlaceloadWrap v-for="key in 6" :key="key">
              <VPlaceload width="100%" height="50px" class="mx-1 mt-2" />
            </VPlaceloadWrap>
          </div>
          <div v-else-if="listRegistrasi.length == 0">
            <VCard>
              <span style="color: var(--light-text)">Data tidak ditemukan...</span>
            </VCard>
          </div>
          <div v-else-if="listRegistrasi.length > 0">
            <div v-for="(items, key) in listRegistrasi" :key="key">
              <div class="column is-12 pb-0">
                <VCard class="is-clickable is-grey" @click="selectedRiwayat(items, route)"
                  :class="selectedRegistrasi.norec == items.norec ? 'is-active-regis' : ''" style="padding:10px">
                  <i aria-hidden="true" class="lnir lnir-medicine mr-2"></i>
                  <span class="span-text-left-bar" style="font-size: 14pt;">{{ items.caption }}</span>
                </VCard>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </template>
  
  <script setup lang="ts">
  import { useWindowScroll } from '@vueuse/core'
  import { useApi } from '/@src/composable/useApi'
  import { h, reactive, ref, computed, defineComponent, watch, onMounted } from 'vue'
  import { useRoute, useRouter } from 'vue-router'
  import { useHead } from '@vueuse/head'
  import * as H from '/@src/utils/appHelper'
  // import ButtonEmr from '../page-emr-plugins/button-emr.vue'
  // import AutoComplete from 'primevue/autocomplete';
  // import Fieldset from 'primevue/fieldset';
  // import TandaTangan from '../page-emr-plugins/tanda-tangan.vue'
  // import TEmrDetail from '../page-emr-plugins/t-emr-detail.vue'
  import { useViewWrapper } from '/@src/stores/viewWrapper'
  import { useThemeColors } from '/@src/composable/useThemeColors'
  import { useUserSession } from '/@src/stores/userSession'
  // import TProfilePasien from './asesmen-medis-rawat-jalan.vue'
  //import TEMR from "../emr/float-tambah.vue"
  
  
  let ID_PASIEN = useRoute().query.nocmfk as string
  let NOREC_PD = useRoute().query.norec_pd as string
  let norec_emr = useRoute().query.norec_emr as string
  
  const props = withDefaults(
    defineProps<{
      pasien?: any
      registrasi?: any
      FORM_NAME?: string
      FORM_URL?: string
      COLLECTION?: string
    }>(),
    {
      pasien: {},
      registrasi: {},
      FORM_NAME: '',
      FORM_URL: '',
      COLLECTION: '',
    }
  )
  const kembaliKeun = () => {
      window.history.back()
  }
  const { y } = useWindowScroll()
  const isStuck = computed(() => { return y.value > 30 })
  const isLoading: any = ref(false)
  const d_Pegawai: any = ref([])
  const d_Obat: any = ref([])
  const listRegistrasi: any = ref([])
  const routerChangeTAB: any = ref(false)
  const isLoadingPasien: any = ref(false)
  const isLoadingFilter: any = ref(false)
  const selectedRegistrasi: any = ref({})
  const listColor: any = ref(Object.keys(useThemeColors()))
  const listPasienRJ: any = ref({})
  const isClosing: any = ref()
  const idDepartemenRI: any = ref([])
  const userLogin = useUserSession().getUser()
  const kelompokUser = useUserSession().getUser().kelompokUser.kelompokUser
  const route = useRoute()
  const totalData = ref(0)
  const pasien: any = ref({})
  const loadingList: any = ref(false)
  const TAB_ITEMS: any = ref([]);
  const router = useRouter()
  const dataAlergi: any = ref('')
  const rowGroupLAB: any = ref({})
  const hideRiwayat: any = ref(false)
  const modalFilter: any = ref(false)
  const currenPageChange: any = ref(false)
  const isLoadingRiwayat: any = ref(false)
  const showModalAssesmenMedis: any = ref(false)
  const TAB_ACTIVE_ROUTER: any = ref(null)
  const modalMENU = ref(false)
  const TAB_URL = ref('')
  const emits = defineEmits<{
    (e: 'showRiwayat'): void,
    (e: 'hiddenRiwayat'): void,
    (e: 'reloadRiwayat'): void,
    (e: 'billingPasien'): void,
    (e: 'showMenuEMR'): void,
    (e: 'editEMR', value: any): void,
    (e: 'hapusEMR', value: any): void,
    (e: 'cetakEMR', value: any): void,
    (e: 'openEMR', value: any): void,
    (e: 'showNavStatus'): void,
  }>()
  const listMenuEMR: any = ref([])
  const totalMenu = ref(0)
  const TAB_ACTIVE: any = ref('Dashboard');
  const alamat: any = ref({})
  const currentPage: any = ref({
    limit: 5,
    rows: 25,
  })
  const item: any = reactive({
    NOREC_PD: NOREC_PD != undefined ? NOREC_PD : '',
    NOREC_APD: '',
    registrasi: {},
    filterTgl: reactive({
      start: new Date(new Date().setDate(new Date().getDate() - 730)),
      end: new Date(),
    }),
    selectedMenu: [false]
  })
  const COLLECTION: any = ref(props.COLLECTION) //table mongodb
  const NOREC_EMRPASIEN: any = ref('')
  const input: any = ref({
    details: [{
      no: 1,
    }],
    tglDibuat: new Date()
  })
  
  const filter = () => {
    modalFilter.value = true
  }
  
  const setView = () => {
    useHead({
      title: props.FORM_NAME + ' - ' + import.meta.env.VITE_PROJECT,
    })
    useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
    useViewWrapper().setFullWidth(true)
  }
  
  currentPage.value.page = computed(() => {
    try {
      return Number.parseInt(route.query.page as string) || 1
    } catch { }
    return 1
  })
  
  const AssesmenMedis = async () => {
    showModalAssesmenMedis.value = true
  }
  
  const reloadListReg = async () => {
    await pasienByID(route.query.nocmfk)
    currenPageChange.value = false
    modalFilter.value = false
  }
  
  const editAss = (e: any) => {
    console.log('ini masuk edit')
    showMenu({
      'name': e.namaemr,
      'url_form': e.url_form,
      'norec_emr': e.emrpasienfk,
      'collection': e.table
    })
  }
  
  const selectedRiwayat = (e: any, q: any) => {
    console.log(q)
    COLLECTION.value = e.collection
    TAB_URL.value = `module-emr-profile-pasien-page-emr-` + e.url
    TAB_ACTIVE.value = e.caption
    TAB_ACTIVE_ROUTER.value = `module-emr-profile-pasien-page-emr-` + e.url
  
    setRoutingEMR(TAB_ACTIVE_ROUTER.value, '', q)
  }
  
  const setRoutingEMR = (form: any, norec_emr: any, q: any) => {
    // console.log(q.query)
    console.log('ini masuk ke routing baru')
  
    const dataRegis: any = q.query
    let query: any = {}
    let params: any = {}
    if (norec_emr != '') {
      query = dataRegis
    } else {
      query = dataRegis
    }
    // console.log(query)
    if (form.indexOf('index_tab') > -1) {
      params = {
        index_tabs: 1
      }
    }
    router.push({
      name: form,
      query: query,
      params: params
    })
  }
  
  const showMenu = async (e: any) => {
  
    if (e.name == 'Catatan Perkembangan Pasien Terintegrasi') {
      onTab()
      modalMENU.value = false
      return
    }
    //isRemoveTAB.value = false
  
    if (e.name != 'EMR') {
      for (let x = 0; x < TAB_ITEMS.value.length; x++) {
        const element: any = TAB_ITEMS.value[x];
        if (element.label == e.name) {
          TAB_ITEMS.value.splice(x, 1)
        }
      }
  
      modalMENU.value = false
      COLLECTION.value = e.items ? e.items.collection : e.collection
      TAB_URL.value = e.url_form
      TAB_ACTIVE.value = e.name
      TAB_ACTIVE_ROUTER.value = e.url_form ? `${e.url_form}` : TAB_ROUTER_DEFAULT.value
  
      TAB_ITEMS.value.push({ label: e.name, icon: 'pi pi-fw pi-file', url_form: e.url_form, items: e.items })
      setCacheEMRWhileReload()
      setRoutingEMR(TAB_ACTIVE_ROUTER.value, e.norec_emr ? e.norec_emr : '')
  
    } else {
      classMODALMENU.value = 'large'
      loadMenuEMR()
    }
  }
  
  const loadMenuEMR = () => {
    listMenuEMR.value = []
    totalMenu.value = 0
    useApi()
      .get(`/emr/menu-emr-detail?namaemr=asesmen&departemen=${selectedRegistrasi.value.objectdepartemenfk}&ruangan=${selectedRegistrasi.value.objectruanganlastfk}`)
      .then((response: any) => {
        listMenuEMR.value = response.data
        totalMenu.value = response.total
      })
  }
  
  const setCacheEMRWhileReload = () => {
    H.cacheHelper().set('xxx_cache_menu_' + route.query.nocmfk, {
      'menu': TAB_ITEMS.value,
      'active': TAB_ACTIVE.value,
      'url_form': TAB_URL.value,
      'collection': COLLECTION.value
    })
  }
  
  const showRiwayat = () => {
    hideRiwayat.value = false
  }
  const hiddenRiwayat = () => {
    hideRiwayat.value = true
  }
  
  const reloadRiwayat = () => {
    detailPelayanan(selectedRegistrasi.value.norec)
  }
  const billingPasien = () => {
    let params = {}
    if (!PASIEN_AKTIF) {
      params = {
        nocmfk: selectedRegistrasi.value.nocmfk,
        norec_pasien_daftar: selectedRegistrasi.value.norec,
        noregistrasi: selectedRegistrasi.value.noregistrasi,
        isaktif: 'false'
      }
    } else {
      params = {
        nocmfk: selectedRegistrasi.value.nocmfk,
        norec_pasien_daftar: selectedRegistrasi.value.norec,
        noregistrasi: selectedRegistrasi.value.noregistrasi,
      }
    }
    router.push({
      name: 'module-kasir-billing',
      query: params
    })
  }
  
  const groupRegistrasi = (data: any) => {
  
    const groupedData = data.reduce((result, item) => {
      const departmentName = item.namadepartemen;
  
      // Check if the department name is already a key in the result object
      if (!result[departmentName]) {
        // If not, create a new key with an empty array
        result[departmentName] = [];
      }
  
      // Add the current item to the corresponding department array
      result[departmentName].push(item);
  
      return result;
    }, {});
  
    // Convert the object values to an array if needed
    // const groupedArray = Object.values(groupedData);
  
    const groupedArray = Object.entries(groupedData).map(([namadepartemen, details]) => ({
      namadepartemen,
      details,
    }));
  
    // Display the final result
    // console.log(groupedArray);
  
    return groupedArray
  }
  
  const loadListPasien = async () => {
    listPasienRJ.value = {}
    loadingList.value = true
    let params = `&ruid=${selectedRegistrasi.value.objectruanganlastfk}`
    if (kelompokUser == 'dokter') {
      params = `&dokid=${userLogin.pegawai.id}`
    }
    await useApi()
      .get(`/emr/list-pasien-rj?dari=${H.formatDate(new Date(), 'YYYY-MM-DD')}
          &sampai=${H.formatDate(new Date(), 'YYYY-MM-DD')}
          &norec_pd=${selectedRegistrasi.value.norec}`)
      .then((response: any) => {
  
        listPasienRJ.value = response
        loadingList.value = false
      })
  }
  
  const pasienByID = async (id: any) => {
    if (routerChangeTAB.value) return
  
    // Clear cache tab EMR All
    localStorage.removeItem('cacheTAB_')
  
    isLoadingPasien.value = true
    isLoadingFilter.value = true
    let dari = H.formatDate(item.filterTgl.start, 'YYYY-MM-DD')
    let sampai = H.formatDate(item.filterTgl.end, 'YYYY-MM-DD')
    let limit: any = currentPage.value.limit
    let offset: any = route.query.page ? route.query.page : 1
    pasien.value = {}
    alamat.value = {}
    selectedRegistrasi.value = {}
    offset = (parseInt(offset) - 1) * limit
    totalData.value = 0
    let norec_apd = route.query.norec_apd ? `&norec_apd=${route.query.norec_apd}` : ''
    await useApi().get(`/emr/set-ok`).then(async (response: any) => {
        listRegistrasi.value = response.data
        isLoadingFilter.value = false
        isLoadingPasien.value = false
        await loadListPasien()
  
      })
  
  
  }
  
  await pasienByID(ID_PASIEN)
  
  const simpan = () => {
    let ID = input.value.id ? input.value.id : ''
  
    let object: any = {}
  
    object = input.value
    object.pasien = H.setObjectPasien(props.pasien)
    object.ttdKeluarga = H.tandaTangan().get("signatureKeluarga")
    object.ttdPetugas = H.tandaTangan().get("signaturePetugas")
    object.registrasi = H.setObjectRegistrasi(props.registrasi)
    let json = {
      'id': ID,
      'norec_emr': NOREC_EMRPASIEN.value,
      'collection': COLLECTION.value,
      'url_form': props.FORM_URL,
      'name_form': props.FORM_NAME,
      'jenis_emr': 'asesmen_medis',
      'data': object
    }
    isLoading.value = true
    useApi().post(
      `/emr/simpan-emr`, json).then((response: any) => {
        isLoading.value = false
        NOREC_EMRPASIEN.value = response.norec_emr
        input.value.id = response.id
      }).catch((e: any) => {
        isLoading.value = false
      })
  }
  setView()
  </script>
  
  <style lang="scss">
  .table-rpo {
    width: 100%;
    border: 1px solid;
  }
  
  .th-rpo,
  .td-rpo {
    padding: 7px;
    border: 1px solid black;
    vertical-align: inherit;
  }
  
  .th-rpo {
    text-align: center !important;
  }
  
  .p-fieldset-legend {
    margin-left: 15px;
  }
  
  @import '/@src/scss/abstracts/all';
  
  .panjangaaaaaaa {
    height: 130px
  }
  
  .panjang-240 {
    height: 260px;
    overflow-x: hidden;
    overflow-y: auto;
  }
  
  .is-dark {
    .list-widget {
      @include vuero-card--dark;
    }
  }
  
  .list-widget {
  
    @include vuero-l-card;
  
    padding: 30px;
  
    &:not(:last-child) {
      margin-bottom: 1.5rem;
    }
  
    &.is-straight {
      @include vuero-s-card;
      border-radius: 16px;
      // margin-top: 8px;
    }
  
    .widget-head {
      display: flex;
      align-items: center;
      justify-content: space-between;
      height: 32px;
      margin-bottom: 10px;
  
      h3 {
        color: var(--dark-text);
        font-size: 1.1rem;
        font-weight: 500;
      }
    }
  
    .inner-list {
      padding: 10px 0;
  
      .inner-list-item {
        +.inner-list-item {
          margin-top: 24px;
        }
      }
    }
  }
  
  .list-widget {
    .icon-timeline {
      .timeline-item {
        position: relative;
        display: flex;
        padding-bottom: 30px;
  
        &::after {
          content: '';
          position: absolute;
          top: 36px;
          left: 18px;
          width: 1px;
          height: calc(100% - 36px);
          border-left: 1px solid var(--fade-grey-dark-3);
        }
  
        .timeline-icon {
          position: relative;
          // height: 36px;
          width: 56px;
          display: flex;
          justify-content: center;
          align-items: center;
          background: var(--white);
          border: 1px solid var(--fade-grey-dark-3);
          border-radius: var(--radius-rounded);
          color: var(--light-text);
          box-shadow: var(--light-box-shadow);
  
          &::after {
            content: '';
            position: absolute;
            top: 17px;
            left: 40px;
            width: 20px;
            height: 1px;
            border-top: 1px solid var(--fade-grey-dark-3);
          }
  
          &.is-squared {
            border-radius: 10px;
  
            img {
              border-radius: 10px;
            }
          }
  
          &.is-primary {
            background: var(--primary);
            border-color: var(--primary);
            box-shadow: var(--primary-box-shadow);
  
            svg {
              color: var(--smoke-white);
            }
          }
  
          &.is-info {
            background: var(--info);
            border-color: var(--info);
            box-shadow: var(--info-box-shadow);
  
            svg {
              color: var(--smoke-white);
            }
          }
  
          &.is-success {
            background: var(--success);
            border-color: var(--success);
            box-shadow: var(--success-box-shadow);
  
            svg {
              color: var(--smoke-white);
            }
          }
  
          &.is-orange {
            background: var(--orange);
            border-color: var(--orange);
            box-shadow: var(--orange-box-shadow);
  
            svg {
              color: var(--smoke-white);
            }
          }
  
          &.is-yellow {
            background: var(--yellow);
            border-color: var(--yellow);
  
            svg {
              color: var(--smoke-white);
            }
          }
  
          img {
            display: block;
            height: 28px;
            width: 28px;
            border-radius: var(--radius-rounded);
          }
  
          svg {
            height: 16px;
            width: 16px;
            stroke-width: 1.6px;
          }
        }
  
        .timeline-content {
          margin-left: 34px;
          line-height: 1.2;
  
          span {
            font-size: 0.8rem;
            color: var(--light-text);
          }
  
          p {
            font-family: var(--font-alt);
            font-size: 0.75rem;
            font-weight: 500;
            color: var(--dark-text);
            width: 260px;
            white-space: nowrap;
            overflow: hidden !important;
            text-overflow: ellipsis;
          }
        }
      }
    }
  }
  
  .is-dark {
    .list-widget {
      .icon-timeline {
        .timeline-item {
          &::after {
            border-color: var(--dark-sidebar-light-12) !important;
          }
  
          .timeline-icon:not(.is-primary):not(.is-info):not(.is-success):not(.is-orange):not(.is-yellow) {
            background: var(--dark-sidebar-light-3) !important;
            border-color: var(--dark-sidebar-light-12) !important;
          }
  
          .timeline-icon {
            &::after {
              border-color: var(--dark-sidebar-light-12) !important;
            }
  
            &.is-primary {
              background: var(--primary);
              border-color: var(--primary);
              box-shadow: var(--primary-box-shadow);
  
              svg {
                color: var(--smoke-white);
              }
            }
          }
  
          .timeline-content {
            p {
              color: var(--dark-dark-text);
            }
          }
        }
      }
    }
  }
  
  .list-widget .icon-timeline .timeline-item::after {
    content: none !important;
  
  }
  
  .list-widget .icon-timeline .timeline-item .timeline-icon::after {
    content: none !important;
  
  }
  </style>