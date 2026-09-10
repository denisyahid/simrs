

<template>
    <section>
      <ConfirmDialog />
      <div>
          <div class="form-layout is-stacked-2">
              <div class="form-outer" style="margin-top:15px">
                  <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
                      <div class="form-header-inner">
                          <div class="left">
                              <h3>Order Pelayanan Ambulan</h3>
                          </div>
                          <div class="right">
                              <div class="buttons">
                                  <!-- <VButton icon="lnir lnir-arrow-left rem-100" light dark-outlined @click="kembaliKeun()">
                                      Kembali
                                  </VButton> -->
                                  <VButton type="button" rounded outlined color="primary" raised icon="feather:save"
                                      :loading="isLoading" @click="simpan()"> Simpan
                                  </VButton>
                              </div>
                          </div>
                      </div>
                  </div>
                  <div class="form-body p-2" v-if="!props.pasien">
                      <div class="business-dashboard hr-dashboard">
                          <div class="columns is-multiline">
                              <div class="column is-12" v-if="isLoadingPasien">
                                  <PlaceloadHeader class="m-3" />
                              </div>
                              <div class="column is-12" v-if="!isLoadingPasien">
                                  <HeadPasien :pasien="pasien" class="m-3" />
                              </div>
                          </div>
                      </div>
                  </div>
              </div>
          </div>
          <form class="form-layout is-separate">
              <div class="form-outer">
                  <div class="form-body">
                      <div class="columns is-multiline">
                          <div class="column is-12 ">
                              <div class="form-section pl-0 pl-3 pr-3 pb-0 mb-0">
                                  <VCard>
                                      <div class="tabs-wrapper" :class="['tab-naver']">
                                          <div class="tabs-inner">
                                              <div class="tabs is-boxed">
                                                  <ul>
                                                      <li v-for="(tab, key) in tabs" :key="key"
                                                          :class="[activeValue === tab.value && 'is-active']">
                                                          <slot name="tab-link" :active-value="activeValue" :tab="tab"
                                                              :index="key" :toggle="toggle">
                                                              <a tabindex="0" @keydown.space.prevent="toggle(tab.value)"
                                                                  @click="toggle(tab.value)">
                                                                  <VIcon v-if="tab.icon" :icon="tab.icon" />
                                                                  <span>
                                                                      <slot name="tab-link-label" :active-value="activeValue"
                                                                          :tab="tab" :index="key">
                                                                          {{ tab.label }}
                                                                      </slot>
                                                                  </span>
                                                              </a>
                                                          </slot>
                                                      </li>
                                                      <li v-if="sliderClass" class="tab-naver"></li>
                                                  </ul>
                                              </div>
                                          </div>
  
                                          <div class="tab-content is-active">
                                              <Transition :name="'fade-fast'" mode="out-in">
                                                  <slot name="tab" :active-value="activeValue"></slot>
                                              </Transition>
                                          </div>
                                      </div>
                                      <!-- <VTabs type="boxed" selected="order" :tabs="[
                                    { label: 'Order', value: 'order' },
                                    { label: 'Riwayat', value: 'riw' },
                                  ]">
  
                                  </VTabs> -->
                                  </VCard>
                         
                              </div>
                          </div>
                          <div class="column is-4 mt-0 " v-if="activeValue == 1">
                              <div class="columns is-multiline">
                                  <div class="column is-12 ">
                                      <div class="form-section  pt-0 pr-0">
                                          <div class="form-section-inner has-padding-bottom h-700-o">
                                              <h3 class="has-text-centered">Detail Order </h3>
                                              <div class="columns is-multiline">
                                                  <div class="column is-12">
                                                      <VField label="Tanggal">
                                                          <VDatePicker v-model="item.tglorder" mode="dateTime"
                                                              style="width: 100%;">
                                                              <template #default="{ inputValue, inputEvents }">
                                                                  <VField>
                                                                      <VControl icon="feather:calendar" fullwidth>
                                                                          <VInput :value="inputValue" placeholder="Tanggal"
                                                                              v-on="inputEvents" />
                                                                      </VControl>
                                                                  </VField>
                                                              </template>
                                                          </VDatePicker>
                                                      </VField>
                                                  </div>
                                                  <div class="column is-12">
                                                      <VField label="Ruangan Asal">
                                                          <VControl icon="feather:map-pin">
                                                              <VInput type="text" placeholder="" autocomplete="off"
                                                                  v-model="item.registrasi.namaruangan" disabled />
                                                          </VControl>
                                                      </VField>
                                                  </div>
  
                                                  <div class="column is-12 mt-2">
                                                      <VField label="Ruangan Tujuan"
                                                          class="is-rounded-select_Z  is-autocomplete-select" v-slot="{ id }">
                                                          <VControl icon="feather:list" fullwidth>
                                                              <Multiselect mode="single" v-model="item.ruanganTujuan"
                                                                  :options="d_Ruangan" placeholder="Pilih data"
                                                                  :searchable="true" :attrs="{ id }" autocomplete="off"
                                                                  @select="changeRuangan(item.ruanganTujuan)" />
                                                          </VControl>
                                                      </VField>
                                                  </div>
                                                  <div class="column is-12 mt-2">
                                                      <VField
                                                          class="is-rounded-select_Z  is-autocomplete-select" v-slot="{ id }">
                                                          <VLabel class="required-field">Pengorder</VLabel>
                                                          <VControl icon="fa:user-md" fullwidth>
                                                              <Multiselect mode="single" v-model="item.pegawaiOrder"
                                                                  placeholder="Pilih data" :searchable="true"
                                                                   :attrs="{ id }" :options="d_Pegawai" autocomplete="off" />
  
                                                          </VControl>
                                                          <!-- <VControl icon="feather:list" fullwidth>
                                                          <Multiselect mode="single" v-model="item.pegawaiOrder"
                                                              :options="d_Pegawai" placeholder="Pilih data"
                                                              :searchable="true" :attrs="{ id }" autocomplete="off" />
                                                      </VControl> -->
                                                      </VField>
                                                  </div>
                                                  <!-- <div class="colun is-12 mt-2">
                                                    <VField grouped>
                                                          <VControl raw subcontrol>
                                                              <VCheckbox v-model="item.kanan"
                                                                  label="Kanan" color="info"
                                                                  @change="getSelected()"/>
                                                          </VControl>
                                                          <VControl raw subcontrol>
                                                              <VCheckbox v-model="item.kiri"
                                                                  label="Kiri" color="info"
                                                                  @change="getSelected()"/>
                                                          </VControl>
                                                      </VField>
                                                  </div> -->
                                                  <div class="column is-12">
                                                      <VField>
                                                          <VLabel class="required-field">Catatan Klinis</VLabel>
                                                          <VControl>
                                                              <VTextarea class="textarea" v-model="item.catatanKlinis" rows="2"
                                                                  placeholder="catatan Klinis (optional) ..."
                                                                  autocomplete="off" autocapitalize="off" spellcheck="true" />
                                                          </VControl>
                                                      </VField>
                                                  </div>
                                                  <div class="column is-12 mt-2">
                                                      <VField>
                                                          <VLabel>Keterangan</VLabel>
                                                          <VControl>
                                                              <VTextarea class="textarea" v-model="item.keterangan" rows="2"
                                                                  placeholder="catatan"
                                                                  autocomplete="off" autocapitalize="off" spellcheck="true" />
                                                          </VControl>
                                                      </VField>
                                                  </div>
                                                  <div class="column is-12">
                                                      <VField>
                                                          <VControl>
                                                              <VSwitchBlock v-model="item.iscito" label="Cito"
                                                                  color="danger" />
                                                          </VControl>
                                                      </VField>
                                                  </div>
  
                                              </div>
                                          </div>
                                      </div>
                                  </div>
                                  <div class="column is-12 mt-0 pt-0" v-if="listChecked.length > 0">
                                      <div class="form-section pr-0 mt-0 pt-0">
                                          <div class="form-section-inner has-padding-bottom h-700-o creative-list-widget">
                                              <h3 class="has-text-centered">Pemeriksaan Terpilih ({{ listChecked.length }})
                                              </h3>
                                              <div class="columns is-multiline  creative-list">
                                                  <!-- <div class="column is-12 mt-0 mb-0">
                                                      <VButton color="danger" icon="feather:x" outlined rounded
                                                          class="is-pulled-right" @click="clearSelection()"> Hapus
                                                      </VButton>
                                                  </div>
                                                  <div class="column is-12" v-for="items in listChecked"
                                                      :key="items.namaproduk">
                                                      <VTag color="purple" :label="items.namaproduk" />
                                                  </div> -->
                                                  <div v-for="item in listChecked" :key="item.id"
                                                      class="creative-list-item is-facebook">
                                                      <i aria-hidden="true" :class="'fa fa-radiation'"></i>
                                                      <div class="meta" style="width:100%">
                                                          <p class="is-pilih-text">{{ item.namaproduk }}</p>
                                                          <!-- <p class="is-pilih-text">{{ item.namaproduk }}{{ item.kanan?" - Kanan": "" }}{{ item.kiri?" - Kiri": "" }}</p> -->
                                                          <p class="is-pilih-price">{{ H.formatRp(item.hargasatuan, 'Rp.')  }}</p>
                                                      </div>
                                                      <VTag :color="'danger'" :label="'Hapus'"
                                                          @click="clearSelectionItem(item)"
                                                          class="mt-0 ml-5 is-pulled-right is-cursor" />
                                                  </div>
  
  
                                              </div>
                                          </div>
                                      </div>
                                  </div>
                              </div>
                          </div>
                          <div class="column is-8 mt-0" v-if="activeValue == 1">
                              <div class="form-section pt-0 pl-0">
                                  <div class="form-section-inner">
                                      <h3 class="has-text-centered">Detail Pemeriksaan</h3>
                                      <div class="columns is-multiline">
                                          <div class="column is-12">
                                              <UIWidget class="search-widget">
                                                  <template #body>
                                                      <div class="field">
                                                          <div class="control">
                                                              <input type="text" v-model="filterLayanan" class="input"
                                                                  placeholder="Search..." />
                                                              <button class="searcv-button" type="button">
                                                                  <i aria-hidden="true" class="iconify"
                                                                      data-icon="feather:search"></i>
                                                              </button>
                                                          </div>
                                                      </div>
                                                  </template>
                                              </UIWidget>
  
                                          </div>
                                          <div class="column is-12 h-400-o">
                                              <div class="column is-12" v-for="items in filteredLayanan"
                                                  :key="items.detailjenisproduk">
                                                  <div class="group-header">
  
                                                      <VIconBox color="facebook"
                                                          style="width:30px;height:30px; min-width: 30px; ">
                                                          <i aria-hidden="true" class="fa fa-radiation"
                                                              style="font-size: 1rem"></i>
                                                      </VIconBox>
                                                      <h4 class="ml-1">{{ items.detailjenisproduk }}</h4>
                                                  </div>
  
                                                  <div class="columns is-multiline mb-3">
                                                      <div class="column is-4" v-for="itemProd in items.details"
                                                          :key="itemProd.id">
                                                          <VField grouped>
                                                              <VControl raw subcontrol>
                                                                  <VCheckbox v-model="item.produkCeklis[itemProd.id]"
                                                                      :label="itemProd.namaproduk" color="info"
                                                                      @change="getSelected()" />
                                                              </VControl>
  
                                                          </VField>
  
                                                      </div>
                                                  </div>
                                              </div>
                                          </div>
  
                                      </div>
                                  </div>
  
                                  <div class="form-section-outer">
                                  </div>
                              </div>
                          </div>
                          <div class="column is-12 mt-0 mr-4 pr-5" v-if="activeValue == 2">
                              <div class="columns is-multiline">
                                  <div class="column is-12 ">
                                      <div class="form-section  pt-0 pr-0">
                                          <div class="form-section-inner has-padding-bottom h-700-o ">
                                              <h3 class="has-text-centered">Riwayat </h3>
                                              <div class="columns is-multiline">
                                                  <div class="column is-12">
                                                      <TRiwayatOrderRad title="" straight class="list-widget-v3"
                                                          :items="listRiwayat" @editItems="editItems" @cetakItems="cetakExper"
                                                          @hapusItems="DialogConfirm" @hasilItems="hasilItems"
                                                          @expertiseItems="expertiseItems" squared colored>
                                                      </TRiwayatOrderRad>
  
                                                  </div>
  
                                                  <div class="column is-12 mt-3">
  
                                                      <VButton icon="lnir lnir-arrow-left is-fullwidth" color="info"
                                                          dark-outlined @click="activeValue = 1">
                                                          Order Baru
                                                      </VButton>
  
                                                  </div>
                                              </div>
                                          </div>
                                      </div>
                                  </div>
  
                              </div>
                          </div>
  
                      </div>
                  </div>
              </div>
          </form>
      </div>
      <Dialog v-model:visible="modalExpertise" modal header="Ekspertise" :style="{ width: '50vw' }">
  
          <div class="columns is-multiline">
              <div class="column is-4">
                  <VField>
                      <VLabel>Nama Pelayanan</VLabel>
                      <VControl icon="feather:box">
                          <VInput type="text" v-model="item.namaproduk" placeholder="Nama Pelayanan" class="is-rounded"
                              disabled />
                      </VControl>
                  </VField>
              </div>
              <div class="column is-4">
                  <VField label="Tanggal">
                      <VDatePicker v-model="item.tanggal" mode="dateTime" style="width: 100%">
                          <template #default="{ inputValue, inputEvents }">
                              <VField>
                                  <VControl icon="feather:calendar" fullwidth>
                                      <VInput :value="inputValue" placeholder="Tanggal" v-on="inputEvents"
                                          class="is-rounded" />
                                  </VControl>
                              </VField>
                          </template>
                      </VDatePicker>
                  </VField>
              </div>
              <div class="column is-4">
                  <VField>
                      <VLabel class="required-field">Dokter</VLabel>
                      <VControl icon="feather:search" class="prime-auto-select">
                          <Dropdown v-model="item.dokterpemeriksa" :options="d_Pegawai" :optionLabel="'label'"
                              class="is-rounded" placeholder="Dokter" style="width: 100%;" showClear :filter="true"  />
                      </VControl>
                  </VField>
              </div>
              <div class="column is-12">
                  <VField>
                      <VControl>
                          <VTextarea class="textarea is-rounded" v-model="item.keterangan" rows="5" placeholder="Keterangan"
                              autocomplete="off" autocapitalize="off" spellcheck="true" />
                      </VControl>
                  </VField>
              </div>
          </div>
          <template #footer>
              <VButton icon="lnir lnir-arrow-left rem-100" light dark-outlined @click="clear()">
                  Tutup
              </VButton>
              <VButton type="button" rounded outlined color="info" raised icon="feather:printer" @click="cetakExpertise()">
                  Cetak
              </VButton>
              <VButton type="button" rounded outlined color="primary" raised icon="feather:save" :loading="isLoadingPop"
                  @click="saveExpertise()"> Simpan
              </VButton>
          </template>
  
      </Dialog>
    </section>
  </template>
  <script setup lang="ts">
  import { useWindowScroll } from '@vueuse/core'
  import { useApi } from '/@src/composable/useApi'
  import { h, reactive, ref, computed, defineComponent, watch, onBeforeMount} from 'vue'
  import { useRoute, useRouter, onBeforeRouteLeave } from 'vue-router'
  import { useHead } from '@vueuse/head'
  import * as H from '/@src/utils/appHelper'
  import { useViewWrapper } from '/@src/stores/viewWrapper'
  import { useThemeColors } from '/@src/composable/useThemeColors'
  import { useToaster } from '/@src/composable/toaster'
  import { useUserSession } from '/@src/stores/userSession'
  import { useConfirm } from "primevue/useconfirm"
  import ConfirmDialog from 'primevue/confirmdialog'
  import TRiwayatOrderRad from '../t-riwayat-order-rad.vue'
  import Dialog from "primevue/dialog"
  import Dropdown from "primevue/dropdown"
  useHead({
      title: 'Order Pelayanan Ambulance - ' + import.meta.env.VITE_PROJECT,
  })
  useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
  
  let ID_PASIEN = useRoute().query.nocmfk as string
  let NOREC_PD = useRoute().query.norec_pasien_daftar as string
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
  useViewWrapper().setFullWidth(props.pasien ? true : false)
  const isLoadingPasien: any = ref(false)
  const item: any = reactive({
      NOREC_PD: NOREC_PD != undefined ? NOREC_PD : '',
      NOREC_APD: '',
      registrasi: {},
      tglorder: new Date(),
      produkCeklis: [],
      pegawaiOrder: useUserSession().getUser().id
  })
  const tabs: any = ref([
      { label: 'Order', value: 1, icon: 'fas fa-bong' },
      { label: 'Riwayat', value: 2, icon: 'fas fa-list' }
  ])
  const isLoadingPop :any =ref(false)
  const listChecked: any = ref([])
  const selected_count = ref(0);
  const colors: any = ref(Object.keys(useThemeColors()))
  const listColor: any = ref([])
  for (let i = 0; i < colors.value.length; i++) {
      const element = colors.value[i];
      if (i <= 9 && element != 'primary')
          listColor.value.push(element)
  }
  const listColor2: any = ref(['primary', 'info', 'orange', 'yellow', 'success'])
  const { y } = useWindowScroll()
  const isStuck = computed(() => {
      return y.value > 30
  })
  const pasien: any = ref({})
  const d_Ruangan: any = ref([])
  const d_Dokter: any = ref([])
  const isLoading = ref(false)
  const confirm = useConfirm();
  const d_Produk: any = ref([])
  const d_Pegawai: any = ref([])
  const d_ProdukDef: any = ref([])
  const filterLayanan: any = ref('')
  const selectedTabs: any = ref()
  const isPrint: any = ref(true)
  const listRiwayat = ref([])
  const modalExpertise = ref(false)
  const norecHasilRadiologi: any = ref('')
  const router = useRouter()
  const route = useRoute()
  const emit = defineEmits<{
      (e: 'update:selected', value: string): void
  }>()
  
  const activeValue: any = ref(1)
  const sliderClass = computed(() => {
      if (!props.slider) {
          return ''
      }
  
      if (props.type === 'rounded') {
          if (props.tabs.length === 3) {
              return 'is-triple-slider'
          }
          if (props.tabs.length === 2) {
              return 'is-slider'
          }
  
          return ''
      }
  
      if (!props.type) {
          if (props.tabs.length === 3) {
              return 'is-squared is-triple-slider'
          }
          if (props.tabs.length === 2) {
              return 'is-squared is-slider'
          }
      }
  
      return ''
  })
  const filteredLayanan = computed(() => {
  
      if (!filterLayanan.value) {
          return d_Produk.value
      }
      var filtered: any = [];
  
      for (let i = 0; i < d_Produk.value.length; i++) {
          const element = d_Produk.value[i];
          filtered.push({
              'detailjenisproduk': element.detailjenisproduk,
              'id': element.id,
              'details': []
          })
          for (let ii = 0; ii < element.details.length; ii++) {
              const element2 = element.details[ii];
              if (element2.namaproduk.match(new RegExp(filterLayanan.value, 'i'))) {
                  filtered[filtered.length - 1].details.push(element2)
              }
          }
      }
      return filtered;
  })
  const toggle = (value: string) => {
      activeValue.value = value
  }
  
  watch(
      () => selectedTabs,
      (value) => {
          activeValue.value = value
      }
  )
  
  watch(activeValue, (value: any) => {
      emit('update:selected', value)
  })
  watch(
      () => activeValue.value,
      (value) => {
          if (value == 2) {
              loadRiwayat()
          }
      }
  )
  const loadRiwayat = () => {
      listRiwayat.value = []
      let nocm = props.pasien ? props.pasien.nocm : pasien.value.nocm
      let noregistrasi = props.registrasi ? props.registrasi.noregistrasi : item.registrasi.noregistrasi
      useApi().get(
          `/ambulan/riwayat-ambulan-order?nocmfk=${ID_PASIEN}&nocm=${nocm}&norec_pd=${item.NOREC_PD}&noregistrasi=${noregistrasi}`).then((response: any) => {
              let z = 0
              for (let x = 0; x < response.length; x++) {
                  const element = response[x];
                  element.icon = 'fa fa-radiation'
                  element.color = listColor2.value[z]
                  element.tglorder = H.formatDateIndoSimple(new Date(element.tglorder))
                  if (z > 4) {
                      z = 0
                  }
                  z++
              }
              listRiwayat.value = response
          })
  }
  const pasienByID = (id: any) => {
      if (props.pasien != undefined) {
          pasien.value = props.pasien
          item.NOREC_APD = props.registrasi.norec_apd
          item.RUANGAN_LAST = props.registrasi.objectruanganlastfk
          item.registrasi = props.registrasi
      } else {
          isLoadingPasien.value = true
          useApi().get(
              `/general/header-pasien?nocmfk=${id}&norec_pd=${item.NOREC_PD}`).then((response: any) => {
                  pasien.value = response.pasien
                  item.NOREC_APD = response.last_registrasi.norec_apd
                  item.RUANGAN_LAST = response.last_registrasi.objectruanganlastfk
                  item.registrasi = response.last_registrasi
                  isLoadingPasien.value = false
                  // fetchTindakan(item.RUANGAN_LAST)
              })
      }
  }
  
  const changeRuangan = async(e: any) => {
      await fetchTindakan(e)
  }
  const fetchDropdown = async() => {
      useApi().get(
          `/ambulan/list-ruang-ambulan`).then(async(response: any) => {
              d_Ruangan.value = response.ruanganLab.map((e: any) => { return { label: e.namaruangan, value: e.id, default: e } })
              item.ruanganTujuan = d_Ruangan.value[0].value
              item.departemenfk = d_Ruangan.value[0].default.objectdepartemenfk
              await fetchTindakan(item.ruanganTujuan)
          })
      useApi().get(
          `emr/dropdown/pegawai_m?select=id,namalengkap,kddokterbpjs&param_search=namalengkap&query=&settingdatafix=objectjenispegawaifk,idJenisPegawaiDokter`
      ).then((response) => {
          d_Pegawai.value = response
          d_Pegawai.value.forEach(element => {
            if(props.registrasi.objectpegawaifk == element.value){
              item.pegawaiOrder = element.value
            }
          });
      })
  }
  const fetchTindakan = async (e: any) => {
      isLoading.value = true
      await useApi().get(
          `/ambulan/list-tindakan-ambulan?ruanganfk=${e}`).then((response: any) => {
              isLoading.value = false

              d_ProdukDef.value = response.data
              d_Produk.value = response.list_tindakan
          })
  }
  
  const simpan = async () => {

    debugger

      if (item.ruanganTujuan == undefined) {
          H.alert('error', 'Pilih ruangan tujuan')
          return
      }
      if (item.pegawaiOrder == undefined) {
          H.alert('error', 'Pilih Pengorder')
          return
      }
      if (item.tglorder == undefined) {
          H.alert('error', 'Pilih Tgl Order  terlebih dahulu')
          return
      }
      if (item.produkCeklis == undefined || item.produkCeklis.length == 0) {
          H.alert('error', 'Pilih layanan terlebih dahulu')
          return
      }
      if (!item.catatanKlinis) {
          H.alert('error', 'Catatan Klinis tidak boleh kosong')
          return
      }
      let udahorder = false
    let tglorder = H.formatDate(item.tglorder, 'YYYY-MM-DD')
    isLoading.value = true
    await useApi().get(`/ambulan/riwayat-ambulan-order?nocmfk=${ID_PASIEN}&norec_pd=${item.NOREC_PD}`).then((response: any) => {
        isLoading.value = false
        for (let i = 0; i < response.length; i++) {
            const element = response[i];
            const tglhisorder = H.formatDate(element.tglorder, 'YYYY-MM-DD')
            if(tglorder == tglhisorder) {
                udahorder = true
            }
        }

        if(udahorder) {
            confirm.require({
                message: 'Order Pelayanan Ambulan sudah dilakukan, Apakah ingin order lagi ?',
                header: 'Konfirmasi Pelayanan Ambulan',
                icon: 'pi pi-info-circle',
                acceptClass: 'p-button-danger',
                accept: () => {
                    lanjutsimpan()
                },
                reject: () => { },
            })
        } else {
            lanjutsimpan()
        }
    })
}
const lanjutsimpan = () => {
  
      var arrobj = Object.keys(item.produkCeklis)
      var data2 = []
      for (var i = arrobj.length - 1; i >= 0; i--) {
          if (item.produkCeklis[parseInt(arrobj[i])] == true) {
              var data = {
                  no: i + 1,
                  produkfk: arrobj[i],
                  qtyproduk: 1,
                  objectkelasfk: item.registrasi.objectkelasfk,
                  nourut: null,
              }
              data2.push(data)
          }
      }

  
      var objSave = {
          noregistrasi: item.registrasi.noregistrasi,
          tanggal: H.formatDate(item.tglorder, 'YYYY-MM-DD HH:mm:ss'),
          tgloperasi: null,
          norec_so: item.NOREC_SO ? item.NOREC_SO : '',
          norec_apd: item.NOREC_APD,
          norec_pd: item.NOREC_PD,
          qtyproduk: data2.length,
          objectruanganfk: item.registrasi.objectruanganlastfk,
          pegawaiorderfk: item.pegawaiOrder,
          objectruangantujuanfk: item.ruanganTujuan,
          departemenfk: item.departemenfk,
          catatanKlinis: item.catatanKlinis? item.catatanKlinis : null,
          keterangan: item.keterangan != undefined ? item.keterangan : null,
          iscito: item.iscito != undefined && item.iscito == true ? item.iscito : false,
          details: data2,
      }
      isLoading.value = true
      useApi().post(
          `/laboratorium/simpan-order`, objSave).then((response: any) => {
              isLoading.value = false
              delete item.NOREC_SO
          }).catch((e: any) => {
              isLoading.value = false
          })
  
  
  }
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
  const kembaliKeun = () => {
      window.history.back()
  }
  const fetchDokter = async (filter: any) => {
      let query = ''
      if (filter) {
          query = filter.toLowerCase()
      }
      const response = await useApi().get(
          `/registrasi/dokter-paging?name= ${query}&limit=10`)
  
      return response.dokter.map((item: any) => {
          return { value: item.id, label: item.namalengkap, default: item }
      })
  }

  const getSelected = () => {
      if (item.produkCeklis.length > 0) {
          var arrobj = Object.keys(item.produkCeklis)
          for (var x = 0; x < arrobj.length; x++) {
              const element = arrobj[x];
              if (item.produkCeklis[parseInt(element)] == true) {
                  for (var i = 0; i < d_ProdukDef.value.length; i++) {
                      const element2 = d_ProdukDef.value[i];
                      if (element2.id == element) {
                          // let kanan = false;
                          // let kiri = false;
                          // modelPosisiKanan.forEach(posisi => {
                          //     if (posisi.id == element2.id) {
                          //         kanan = posisi.kanan
                          //     }
                          // });
                          for (var z = 0; z < listChecked.value.length; z++) {
                              const element3 = listChecked.value[z];
  
                              if (element3.namaproduk == element2.namaproduk) {
                                  listChecked.value.splice(z, 1)
                              }else{
                                item.kanan = false
                                item.kiri = false
                              }
                          }
                          // if(kanan && kiri){
                          //   listChecked.value.push({ hargasatuan : element2.hargasatuan,namaproduk: element2.namaproduk, id: element2.id, kanan: kanan })
                          //   listChecked.value.push({ hargasatuan : element2.hargasatuan,namaproduk: element2.namaproduk, id: element2.id, kiri: kiri })
                          // }else if(kanan){
                          //   listChecked.value.push({ hargasatuan : element2.hargasatuan,namaproduk: element2.namaproduk, id: element2.id, kanan: kanan })
                          // }else if(kiri){
                          //   listChecked.value.push({ hargasatuan : element2.hargasatuan,namaproduk: element2.namaproduk, id: element2.id, kiri: kiri })
                          // }else{
                            listChecked.value.push({ hargasatuan : element2.hargasatuan,namaproduk: element2.namaproduk, id: element2.id })
                          // }
                          // lastID = element2.id
                      }
                  }
              } else {
                // console.log('uncek produk');
                  for (var i = 0; i < d_ProdukDef.value.length; i++) {
                      const element2 = d_ProdukDef.value[i];
                      if (element2.id == element) {
                          for (var z = 0; z < listChecked.value.length; z++) {
                              const element3 = listChecked.value[z];
                              if (element3.namaproduk == element2.namaproduk) {
                                  // lastID = element3.id
                                  listChecked.value.splice(z, 1)
                              }
                              // console.log(lastID);
  
                          }
                      }
                  }
              }
          }
  
      }
  }
  const editItems = async (e: any) => {
      if (e.status != 'pending') {
          H.alert('error', 'Order sudah diverifikasi')
          return
      }
      item.NOREC_SO = e.norec
      useApi().get(
          `/ambulan/detail-ambulan?norec=${e.norec}`).then((response: any) => {
              for (let x = 0; x < response.length; x++) {
                  const element = response[x];
                  item.pegawaiOrder = element.objectpegawaiorderfk
                  item.tglorder = new Date(element.tglorder)
                  item.ruanganTujuan = element.objectruangantujuanfk
                  item.keterangan = element.keteranganlainnya
                  item.iscito = element.cito
                  item.produkCeklis[parseInt(element.produkfk)] = true
              }
              getSelected()
              activeValue.value = 1
          }).catch((e: any) => {
          })
  
      activeValue.value = 1
  
  }
  const DialogConfirm = (e: any) => {
      confirm.require({
          message: 'Apakah anda serius menghapus data ini ?',
          header: 'Konfirmasi Hapus Data',
          icon: 'pi pi-info-circle',
          acceptClass: 'p-button-danger',
          accept: () => {
              hapusItems(e)
  
          },
          reject: () => { },
      })
  }
  const hapusItems = (e: any) => {
      if (e.status != 'pending') {
          H.alert('error', 'Order sudah diverifikasi')
          return
      }
      useApi().post(
          `/ambulan/delete-order-ambulan`, { noorder: e.noorder }).then((response: any) => {
              isLoading.value = false
              loadRiwayat()
          }).catch((e: any) => {
              isLoading.value = false
          })
  }
  var HttpClient = function () {
      this.get = function (aUrl, aCallback) {
          var anHttpRequest = new XMLHttpRequest();
          anHttpRequest.onreadystatechange = function () {
              if (anHttpRequest.readyState == 4 && anHttpRequest.status < 400)
                  aCallback(anHttpRequest.responseText);
          }
  
          anHttpRequest.open("GET", aUrl, true);
          anHttpRequest.send(null);
      }
  }


  
  const clear = ()=>{
      delete item.keterangan
      delete item.tanggal
      delete item.norec
      delete item.dokterpemeriksa
      modalExpertise.value = false
  }
  
  


  const fetchDok = () => {
      useApi().get(
      `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=&settingdatafix=objectjenispegawaifk,idJenisPegawaiDokter&limit=2000`
    ).then((response) => {
      d_Pegawai.value = response
    })
  }
  

  pasienByID(ID_PASIEN)
  fetchDropdown()
  fetchDok()
  
  </script>
  <style lang="scss">
  @import '/@src/scss/abstracts/all';
  @import '/@src/scss/components/forms-outer';
  @import '/@src/scss/custom/config';
  @import '/@src/scss/custom/timeline-css';
  @import '/@src/scss/module/emr/order-laboratorium.scss';
  </style>
  