<template>
  <section>
    <ConfirmDialog />
    <div>
      <div class="form-layout is-stacked-2">
        <div class="form-outer" style="margin-top:15px" :style="{ width: '80rem' }">
          <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
            <div class="form-header-inner">
              <div class="left">
                <h3> Radiologi</h3>
              </div>
              <div class="right">
                <div class="buttons">
                  <h3 color="info" v-if="isPenunjangSusulan != null" bold
                    style="font-weight: bold; color: blue; font-size: 16px; margin-top: -5px; margin-right: 20px; border-bottom: 1px solid blue">
                    Radiologi Berbeda Hari: {{ H.formatDate(isPenunjangSusulan, 'DD-MM-YYYY') }}</h3>
                  <!-- <VButton type="button" rounded color="info" raised icon="fas fa-check" :disabled="disabledSave"
                      :loading="isLoading" @click="susulanModal()"> Penunjang Berbeda Hari
                  </VButton> -->
                  <!-- <VButton icon="lnir lnir-arrow-left rem-100" light dark-outlined @click="kembaliKeun()">
                                      Kembali
                                  </VButton> -->
                  <VButton type="button" rounded color="primary" raised icon="feather:save" :disabled="disabledSave"
                    :loading="isLoading" @click="simpan()"> Simpan
                  </VButton>
                </div>
              </div>
            </div>
          </div>
          <div class="form-body p-4">
            <div class="business-dashboard hr-dashboard" v-if="!props.pasien">
              <div class="columns is-multiline">
                <div class="column is-12" v-if="isLoadingPasien">
                  <PlaceloadHeader class="m-3" />
                </div>
                <div class="column is-12" v-if="!isLoadingPasien">
                  <HeadPasien :pasien="pasien" class="m-3" />
                </div>
              </div>
            </div>
            <div class="columns is-multiline">
              <div class="column is-12 " :style="{ width: '80rem' }">
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
                                        <slot name="tab-link" :active-value="activeValue" :tab="tab" :index="key"
                                          :toggle="toggle">
                                          <a tabindex="0" @keydown.space.prevent="toggle(tab.value)"
                                            @click="toggle(tab.value)">
                                            <VIcon v-if="tab.icon" :icon="tab.icon" />
                                            <span>
                                              <slot name="tab-link-label" :active-value="activeValue" :tab="tab"
                                                :index="key">
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
                            </VCard>
                          </div>
                        </div>
                        <div class="column is-3 mt-0 " v-if="activeValue == 1">
                          <div class="columns is-multiline">
                            <div class="column is-12 ">
                              <div class="form-section  pt-0 pr-0">
                                <div class="form-section-inner has-padding-bottom h-700-o"
                                  style="padding: 5px; overflow-x: hidden; overflow-y: hidden">
                                  <h3 class="has-text-centered">Detail Order </h3>
                                  <div class="columns is-multiline">
                                    <div class="column is-12">
                                      <VField label="Tanggal">
                                        <VDatePicker v-model="item.tglorder" mode="dateTime" style="width: 100%;">
                                          <template #default="{ inputValue, inputEvents }">
                                            <VField>
                                              <VControl icon="feather:calendar" fullwidth>
                                                <VInput :value="inputValue" placeholder="Tanggal" v-on="inputEvents" />
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
                                      <VField label="Ruangan Tujuan" class="is-rounded-select_Z  is-autocomplete-select"
                                        v-slot="{ id }">
                                        <VControl icon="feather:list" fullwidth>
                                          <Multiselect mode="single" v-model="item.ruanganTujuan" :options="d_Ruangan"
                                            placeholder="Pilih data" :searchable="true" :attrs="{ id }"
                                            autocomplete="off" @select="changeRuangan(item.ruanganTujuan)" />
                                        </VControl>
                                      </VField>
                                    </div>
                                    <div class="column is-12 mt-2">
                                      <VField class="is-rounded-select_Z  is-autocomplete-select" v-slot="{ id }">
                                        <VLabel class="required-field">Pengorder</VLabel>
                                        <VControl icon="fa:user-md" fullwidth>
                                          <Multiselect mode="single" v-model="item.pegawaiOrder"
                                            placeholder="Pilih data" :searchable="true" :attrs="{ id }"
                                            :options="d_Pegawai" autocomplete="off" />
                                        </VControl>
                                      </VField>
                                    </div>
                                    <div class="column is-12">
                                      <VField label="Registrasi" class="is-rounded-select_Z  is-autocomplete-select"
                                        v-slot="{ id }">
                                        <VControl icon="feather:plus-circle" fullwidth>
                                          <Dropdown v-model="item.pilihRegistrasi" :options="d_Registrasi"
                                            :optionLabel="'label'" :optionValue="'value'" class="is-rounded"
                                            placeholder="Pilih data" style="width: 100%;" showClear :filter="true"
                                            @change="getRegistrasi(item.pilihRegistrasi)" disabled />
                                        </VControl>
                                      </VField>
                                    </div>
                                    <div class="column is-12">
                                      <VField>
                                        <VLabel class="required-field">Keterangan Klinis</VLabel>
                                        <VControl>
                                          <VTextarea class="textarea" v-model="item.catatanKlinis" rows="2"
                                            placeholder="Diisi Keterangan Klinis Pasien/Diagnosa Sehubungan Dengan Permintaan Ronsen"
                                            autocomplete="off" autocapitalize="off" spellcheck="true" />
                                        </VControl>
                                      </VField>
                                    </div>
                                    <div class="column is-12 mt-2">
                                      <VField>
                                        <span class="custom-text required-field">
                                          Keterangan Tambahan Permintaan Ronsen
                                        </span>
                                        <VControl>
                                          <VTextarea class="textarea" v-model="item.keterangan" rows="2"
                                            placeholder="Diisi Keterangan Tambahan Permintaan Ronsen(Misal : Dextra/Sinistra, AP/PA, Tegak/Supine)"
                                            autocomplete="off" autocapitalize="off" spellcheck="true" />
                                        </VControl>
                                      </VField>
                                    </div>
                                    <div class="column is-12">
                                      <VField>
                                        <VControl>
                                          <VSwitchBlock v-model="item.iscito" label="Cito" color="danger" />
                                        </VControl>
                                      </VField>
                                    </div>

                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                        <div class="column is-9 mt-0" v-if="activeValue == 1">
                          <div class="form-section pt-0 pl-0">
                            <div class="form-section-inner" style="padding: 10px; height: 655px;">
                              <h3 class="has-text-centered">Detail Pemeriksaan</h3>
                              <div class="columns is-multiline">
                                <div class="column is-4">
                                  <VControl class="is-pulled-left">
                                    <VButton v-model="isPaket" color="danger" class="is-pulled-right"
                                      @click="fetchPaket()">Pilih Paket
                                    </VButton>
                                  </VControl>
                                </div>
                                <div class="column is-12">
                                  <UIWidget class="search-widget">
                                    <template #body>
                                      <div class="field">
                                        <div class="control">
                                          <input type="text" v-model="filterLayanan" class="input"
                                            placeholder="Search..." />
                                          <button class="searcv-button" type="button">
                                            <i aria-hidden="true" class="iconify" data-icon="feather:search"></i>
                                          </button>
                                        </div>
                                      </div>
                                    </template>
                                  </UIWidget>

                                </div>
                                <div class="column is-8 h-400-o" style="overflow-x: hidden">
                                  <div class="gumball" style="overflow-y: auto; max-height: 400px;">
                                    <div class="column is-12" v-for="items in filteredLayanan"
                                      :key="items.detailjenisproduk">
                                      <div class="group-header">

                                        <VIconBox color="facebook" style="width:30px;height:30px; min-width: 30px; ">
                                          <i aria-hidden="true" class="fa fa-radiation" style="font-size: 1rem"></i>
                                        </VIconBox>
                                        <h4 class="ml-1">{{ items.detailjenisproduk }}</h4>
                                      </div>

                                      <div class="columns is-multiline mb-3">
                                        <div class="column is-4" v-for="itemProd in items.details" :key="itemProd.id">
                                          <VField grouped>
                                            <VControl raw subcontrol>
                                              <VCheckbox v-model="item.produkCeklis[itemProd.id]"
                                                :label="itemProd.namaproduk" color="info" @change="getSelected()" />
                                            </VControl>

                                          </VField>

                                        </div>
                                      </div>
                                    </div>
                                  </div>
                                </div>

                                <div class="column is-4 mt-0" v-if="activeValue == 1">
                                  <div class="group-header">
                                    <VIconBox color="maroon" style="width:30px;height:30px; min-width: 30px; ">
                                      <i aria-hidden="true" class="fas fa-check" style="font-size: 1rem"></i>
                                    </VIconBox>
                                    <h4 class="ml-1">Pemeriksaan Terpilih ({{ listChecked.length }})</h4>
                                  </div>
                                  <div class="column is-12 mt-0 pt-0" v-if="listChecked.length > 0">
                                    <div class="form-section pr-0 mt-0 pt-0">
                                      <div class="form-section-inner has-padding-bottom h-700-o creative-list-widget"
                                        style="padding: 5px; overflow-x: hidden; overflow-y: hidden">
                                        <div class="columns is-multiline  creative-list">
                                          <div v-for="item in listChecked" :key="item.id"
                                            class="creative-list-item is-facebook">
                                            <div class="columns is-multiline">
                                              <div class="column is-12">
                                                <div class="meta" style="width:100%">
                                                  <p class="is-pilih-text">{{ item.namaproduk }}</p>
                                                  <!-- <p class="is-pilih-price">{{ H.formatRp(item.hargasatuan, 'Rp.') }}</p> -->
                                                </div>
                                              </div>
                                              <div class="column is-12">
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
                                </div>
                              </div>
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
                                      <TRiwayatOrderRad title="" straight class="list-widget-v3" :items="listRiwayat"
                                        @editItems="editItems" @cetakItems="cetakItems" @hapusItems="DialogConfirm"
                                        @hasilItems="hasilItems" @expertiseItems="expertiseItems" squared colored>
                                      </TRiwayatOrderRad>
                                    </div>
                                    <div class="column is-12 mt-3">
                                      <VButton icon="lnir lnir-arrow-left is-fullwidth" color="info" dark-outlined
                                        @click="activeValue = 1">
                                        Order Baru
                                      </VButton>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                        <div class="column is-12 mt-0 mr-4 pr-5" v-if="activeValue == 3">
                          <div class="columns is-multiline">
                            <div class="column is-12 ">
                              <div class="form-section  pt-0 pr-0">
                                <div class="form-section-inner has-padding-bottom h-700-o ">
                                  <h3 class="has-text-centered">Riwayat Semua Pemeriksaan Radiologi Pasien</h3>
                                  <div class="columns is-multiline">
                                    <div class="column is-12">
                                      <TRiwayatOrderRadAll title="" straight class="list-widget-v3"
                                        :items="listRiwayatAll" @editItems="editItems" @cetakItems="cetakItems"
                                        @hapusItems="DialogConfirm" @hasilItems="hasilItems"
                                        @expertiseItems="expertiseItems"
                                        @showResultRadiologiManual="showResultRadiologiManual" squared colored>
                                      </TRiwayatOrderRadAll>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                        <div class="column is-12 mt-0 mr-4 pr-5" v-if="activeValue == 4">
                          <div class="columns is-multiline">
                            <div class="column is-12 ">
                              <div class="form-section  pt-0 pr-0">
                                <div class="form-section-inner has-padding-bottom h-700-o ">
                                  <h3 class="has-text-centered">Upload Berkas Pasien Radiologi</h3>
                                  <div class="columns is-multiline">
                                    <div class="column is-12">
                                      <UploadBerkas title="" straight class="list-widget-v3"
                                        :registrasi="props.registrasi" :pasien="props.pasien" :type="'rad'" squared
                                        colored>
                                      </UploadBerkas>
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
            </div>
          </div>
        </div>
      </div>
    </div>
    <Dialog v-model:visible="modalExpertise" modal header="Ekspertise" :style="{ width: '50vw' }">
      <VButton icon="feather:save" @click="saveExpertise(expertise)" :loading="isLoadingPop" color="primary"
        style="float: right;" raised>Cetak
      </VButton>
      <div class="columns is-multiline">
        <div class="column is-12">
          <VField>
            <VControl>
              <VTextarea class="textarea is-rounded" v-model="item.keterangan" rows="50" placeholder="Keterangan"
                autocomplete="off" autocapitalize="off" spellcheck="true" />
            </VControl>
          </VField>
        </div>
      </div>
    </Dialog>
    <Dialog v-model:visible="modalPenunjang" modal header="Penunjang" :style="{ width: '50vw' }">
      <div class="columns is-multiline">
        <div class="column is-12">
          <VField label="Tanggal Kunjungan Berikutnya">
            <VDatePicker v-model="item.tglkunjungan" mode="dateTime" style="width: 100%;">
              <template #default="{ inputValue, inputEvents }">
                <VField>
                  <VControl icon="feather:calendar" fullwidth>
                    <VInput :value="inputValue" placeholder="Tanggal" v-on="inputEvents" />
                  </VControl>
                </VField>
              </template>
            </VDatePicker>
          </VField>
        </div>
      </div>
      <template #footer>
        <VButton icon="feather:refresh-cw rem-100" light dark-outlined @click="modalPenunjang = false">
          Batal
        </VButton>
        <VButton type="button" rounded outlined color="primary" raised icon="feather:save" :loading="isLoading"
          @click="susulan()"> Simpan
        </VButton>
      </template>
    </Dialog>
    <Dialog v-model:visible="modalPaket" modal header="Paket" :style="{ width: '60vw' }">
      <div class="columns is-multiline">
        <div class="column is-12">
          <VCard>
            <DataTable :value="dataSourcePaket" v-model:expandedRows="expandedRows" :paginator="true" :rows="10"
              :rowsPerPageOptions="[5, 10, 25]" class="p-datatable-customers" filterDisplay="menu"
              v-model:filters="filterPaket"
              paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
              responsiveLayout="stack" breakpoint="960px" sortMode="multiple"
              currentPageReportTemplate="Showing {first} to {last} of {totalRecords}"
              :globalFilterFields="['namapaket']" :scrollable="true" :loading="dataSourcePaket.loading" dataKey="id">
              <template #header>
                <div class="flex justify-content-end">
                  <span class="p-input-icon-left">
                    <i class="pi pi-search" />
                    <InputText v-model="filterPaket.global.value" placeholder="Keyword Search" />
                  </span>
                </div>
              </template>
              <Column :expander="true" :style="{ width: '50px' }" />
              <Column :exportable="false" header="#" :style="{ width: '50px' }">
                <template #body="slotProps">
                  <VIconButton type="button" icon="pi pi-plus" class="mr-2" color="info" circle outlined raised
                    v-tooltip.top="'Tambah'" @click="tambahPaket(slotProps.data)" :loading="slotProps.data.isLoading">
                  </VIconButton>
                </template>
              </Column>

              <Column field="no" header="No" :style="{ width: '40px' }"> </Column>
              <Column field="namapaket" header="Nama Paket" style="width:250px" :sortable="true"></Column>
              <Column field="jml" header="Jumlah Pelayanan" style="width:100px"></Column>
              <template #expansion="slotProps">
                <div class="orders-subtable">
                  <h5>Paket : {{ slotProps.data.namapaket }}</h5>
                  <DataTable :value="slotProps.data.details" responsiveLayout="scroll">
                    <Column field="namaproduk" header="Pelayanan" :sortable="true"> </Column>
                  </DataTable>
                </div>
              </template>

            </DataTable>
          </VCard>
        </div>

      </div>

    </Dialog>
  </section>
  <VModal :open="modalDetailTindakan" title="Pilih Expertise Tindakan" :noclose="false" size="large" actions="right"
    @close="closeExpertiseModal()">
    <template #content>
      <div class="column is-12">
        <div class="column is-12">
          <table class="tb-custom mt-3">
            <div style="max-height:500px;min-height: 300px; overflow-y: scroll;display: block;">
              <tbody>
                <tr v-for="(itemsDet, index2) in detail_Tindakan.details" :key="index2">
                  <td width="30%">
                    <div class="columns is-multiline">
                      <div class="column is-12">
                        <div class="title-layan">{{ itemsDet.namaproduk }}</div>
                      </div>
                    </div>
                  </td>
                  <td class="center">
                    <div class="columns is-multiline">
                      <div class="column is-12">
                      </div>
                    </div>
                  </td>
                  <div class="title-ruangan">Dokter Baca : </div>
                  <td class="title-layan">{{ itemsDet.dokterbaca }}</td>
                  <td class="center">
                    <VIconButton color="primary" class="mr-2" light raised circle icon="feather:file-text"
                      @click="saveExpertise(itemsDet)" v-tooltip.bubble="'Cetak Expertise'" />
                  </td>
                </tr>
              </tbody>
            </div>
          </table>
        </div>
      </div>
    </template>
  </VModal>
</template>
<script setup lang="ts">
import { useWindowScroll } from '@vueuse/core'
import { useApi } from '/@src/composable/useApi'
import { h, reactive, ref, computed, defineComponent, watch, onBeforeMount } from 'vue'
import { useRoute, useRouter, onBeforeRouteLeave } from 'vue-router'
import { useHead } from '@vueuse/head'
import * as H from '/@src/utils/appHelper'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useThemeColors } from '/@src/composable/useThemeColors'
import { useToaster } from '/@src/composable/toaster'
import { useUserSession } from '/@src/stores/userSession'
import { useConfirm } from "primevue/useconfirm"
import MultiSelect from 'primevue/multiselect';
import ConfirmDialog from 'primevue/confirmdialog'
import TRiwayatOrderRad from '../t-riwayat-order-rad.vue'
import TRiwayatOrderRadAll from '../t-riwayat-order-rad-all.vue'
import Dialog from "primevue/dialog"
import FileUpload from 'primevue/fileupload';
import UploadBerkas from '../page-emr/berkas-pasien.vue'
import Dropdown from "primevue/dropdown"
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import { FilterMatchMode } from 'primevue/api';
useHead({
  title: 'Order Radiologi - ' + import.meta.env.VITE_PROJECT,
})
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)

let ID_PASIEN = useRoute().query.nocmfk as string ? useRoute().query.nocmfk as string : props.pasien.norec_ps
// let NOREC_PD = useRoute().query.norec_pasien_daftar as string
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
  NOREC_PD: {
    type: Object as PropType<any>,
  },
})
let NOREC_PD = props.NOREC_PD

useViewWrapper().setFullWidth(props.pasien ? true : false)
const isLoadingPasien: any = ref(false)
const modalPenunjang: any = ref(false)
const isPenunjangSusulan: any = ref()
const item: any = reactive({
  // NOREC_PD: NOREC_PD != undefined ? props.norec_pd : '',
  NOREC_PD: props.NOREC_PD != undefined ? props.NOREC_PD : '',
  NOREC_APD: '',
  registrasi: {},
  tglorder: new Date(),
  produkCeklis: [],
  pegawaiOrder: useUserSession().getUser().id
})
const item2: any = reactive({
  tglpelayanan: new Date(),
})
const tabs: any = ref([
  { label: 'Order', value: 1, icon: 'fas fa-bong' },
  { label: 'Riwayat', value: 2, icon: 'fas fa-list' },
  { label: 'Riwayat Order All', value: 3, icon: 'fas fa-list' },
  { label: 'Upload Berkas External Rad', value: 4, icon: 'fas fa-folder' }
])
const filterPaket: any = ref({
  'global': { value: null, matchMode: FilterMatchMode.CONTAINS },
})
const isLoadingPop: any = ref(false)
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
const expertise: any = ref({})
const d_Ruangan: any = ref([])
const detail_Tindakan: any = ref([])
const d_Dokter: any = ref([])
const isLoading = ref(false)
const modalDetailTindakan = ref(false)
const confirm = useConfirm();
const d_Produk: any = ref([])
const d_Pegawai: any = ref([])
const disabledSave = ref(false)
const historySave: any = ref([])
const d_ProdukDef: any = ref([])
const d_Registrasi: any = ref([])
const filterLayanan: any = ref('')
const selectedTabs: any = ref()
const idDokterBaca: any = ref()
const isPrint: any = ref(true)
const listRiwayat = ref([])
const listRiwayatAll = ref([])
const modalExpertise = ref(false)
const isPaket: any = ref(false)
const dataSourcePaket: any = ref([])
const modalPaket: any = ref(false)
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
watch(
  () => activeValue.value,
  (value) => {
    if (value == 3) {
      loadRiwayatAll()
    }
  }
)
const loadRiwayat = () => {
  listRiwayat.value = []
  let nocm = props.pasien ? props.pasien.nocm : pasien.value.nocm
  let noregistrasi = props.registrasi ? props.registrasi.noregistrasi : item.registrasi.noregistrasi
  useApi().get(
    `/radiologi/riwayat-order?nocmfk=${ID_PASIEN}&nocm=${nocm}&norec_pd=${item.NOREC_PD}&noregistrasi=${noregistrasi}&ruangan=${props.registrasi.namaruangan.trim()}`).then((response: any) => {
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
const loadRiwayatAll = () => {
  listRiwayatAll.value = []
  let nocm = props.pasien ? props.pasien.nocm : pasien.value.nocm
  let noregistrasi = props.registrasi ? props.registrasi.noregistrasi : item.registrasi.noregistrasi
  useApi().get(
    `/radiologi/riwayat-order?nocmfk=${ID_PASIEN}&ruangan=${props.registrasi.namaruangan}`).then((response: any) => {
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
      listRiwayatAll.value = response
    })
}
const pasienByID = (id: any) => {
  useApi().get(`/emr/header-pasien?nocmfk=${id}&norec_pd=${item.NOREC_PD}`).then((response: any) => {
    if (response.registrasi[0].isPenunjangSusulanRad != null) {
      isPenunjangSusulan.value = response.registrasi[0].isPenunjangSusulanRad
    }
  })
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

const changeRuangan = async (e: any) => {
  await fetchTindakan(e)
}
const fetchDropdown = async () => {
  useApi().get(
    `/tindakan/list-dropdown-registrasi?nocmfk=${ID_PASIEN}`).then((response: any) => {
      d_Registrasi.value = response.registrasi.map((e: any) => { return { label: e.tglregistrasi + ' - (' + e.noregistrasi + ' - ' + e.namaruangan + ')', value: e, default: e } })
      for (let i = 0; i < response.registrasi.length; i++) {
        console.log(H.formatDate(new Date(), 'DD-MM-YYYY'))
        let ftregis = response.registrasi[i];
        if (item.registrasi.noregistrasi == ftregis.noregistrasi &&
          item.registrasi.objectruanganfk == ftregis.objectruanganfk
        ) {
          console.log('UHUYYY', ftregis);
          item.pilihRegistrasi = d_Registrasi.value[i].value
          getRegistrasi(item.pilihRegistrasi)

        }
      }
      //item.ruangan = d_RuanganRJ.value[0].value
    })
  useApi().get(
    `/radiologi/list-dropdown`).then(async (response: any) => {
      d_Ruangan.value = response.ruanganLab.map((e: any) => { return { label: e.namaruangan, value: e.id, default: e } })
      item.ruanganTujuan = d_Ruangan.value[0].value
      //item.ruanganTujuan = 330
      console.log(d_Ruangan.value[0].value)
      item.departemenfk = d_Ruangan.value[0].default.objectdepartemenfk
      await fetchTindakan(item.ruanganTujuan)
    })
  useApi().get(
    `emr/dropdown/pegawai_m?select=id,namalengkap,kddokterbpjs&param_search=namalengkap&query=&settingdatafix=objectjenispegawaifk,idJenisPegawaiDokter`
  ).then((response) => {
    d_Pegawai.value = response
    d_Pegawai.value.forEach(element => {
      if (props.registrasi.objectpegawaifk == element.value) {
        item.pegawaiOrder = element.value
      }
    });
  })
}
const fetchTindakan = async (e: any) => {
  isLoading.value = true
  await useApi().get(
    `/radiologi/list-tindakan-for-order?ruanganfk=${e}&idkebangsaan=${pasien.value.objectkebangsaanfk}&kelasfk=${item.registrasi.objectkelasfk}`).then((response: any) => {
      isLoading.value = false
      // let x = 0
      // for (let x = 0; x < response.list_tindakan.length; x++) {
      //     const element = response.list_tindakan[x];
      //     element.color = listColor.value[x]
      //     if (x > 9) {
      //         x = 0
      //     }
      //     x++
      // }
      d_ProdukDef.value = response.data
      d_Produk.value = response.list_tindakan
    })
}
const simpan = async () => {
  await H.statusClosingPasien(NOREC_PD);
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
  if (!item.keterangan) {
    H.alert('error', 'Keterangan tidak boleh kosong')
    return
  }

  let udahorder = false
  let tglorder = H.formatDate(item.tglorder, 'YYYY-MM-DD')
  let nocm = props.pasien ? props.pasien.nocm : pasien.value.nocm
  let noregistrasi = props.registrasi ? props.registrasi.noregistrasi : item.registrasi.noregistrasi
  let tayo = H.formatDate(item.tglorder, 'YYYY-MM-DD')
  let namaProduk = []
  let squidword = false

  isLoading.value = true
  await useApi().get(`/radiologi/riwayat-order?nocmfk=${ID_PASIEN}&nocm=${nocm}&norec_pd=${item.NOREC_PD}&noregistrasi=${noregistrasi}`).then((response: any) => {
    isLoading.value = false
    for (let i = 0; i < response.length; i++) {
      const element = response[i];
      const tglhisorder = H.formatDate(element.tglorder, 'YYYY-MM-DD')
      if (tglorder == tglhisorder) {
        udahorder = true
      }
      if (element.details && element.details.length > 0) {
        for (let spongebob = 0; spongebob < listChecked.value.length; spongebob++) {

          const patrick = listChecked.value[spongebob]

          for (let j = 0; j < element.details.length; j++) {

            const sandy = element.details[j]

            if (tayo == tglhisorder && patrick.id == sandy.idproduk) {

              squidword = true
              namaProduk.push(sandy.namaproduk)
            }
          }


        }
      }
    }
    if (squidword && namaProduk.length > 0) {
      confirm.require({
        // message: 'Order Laboratorium sudah dilakukan, Apakah ingin order lagi ?',
        message: 'Terdapat Tindakan Laboratorium Sudah Di Lakukan Pada Tanggal Order Yang Sama ' + JSON.stringify(namaProduk) + '. Apakah Mau Menambahkan Tindakan Lagi ?',
        header: 'Konfirmasi Order Laboratorium',
        icon: 'pi pi-info-circle',
        acceptClass: 'p-button-danger',
        accept: () => {
          lanjutsimpan()
        },
        reject: () => { },
      })
    }

    else if (udahorder) {
      confirm.require({
        message: 'Order Radiologi sudah dilakukan, Apakah ingin order lagi ?',
        header: 'Konfirmasi Order Radiologi',
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
  disabledSave.value = true
  var arrobj = Object.keys(item.produkCeklis)
  var data2 = []
  var dataEx = []
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
      dataEx.push(data)
    }
  }

  var objSave = {
    noregistrasi: item.pilihRegistrasi.noregistrasi,
    tanggal: H.formatDate(item.tglorder, 'YYYY-MM-DD HH:mm:ss'),
    tgloperasi: null,
    norec_so: item.NOREC_SO ? item.NOREC_SO : '',
    norec_apd: item.pilihRegistrasi.norec_apd,
    norec_pd: item.pilihRegistrasi.norec_pd,
    qtyproduk: data2.length,
    objectruanganfk: item.registrasi.objectruanganlastfk,
    pegawaiorderfk: item.pegawaiOrder,
    objectruangantujuanfk: item.ruanganTujuan,
    departemenfk: item.departemenfk,
    catatanKlinis: item.catatanKlinis ? item.catatanKlinis : null,
    keterangan: item.keterangan != undefined ? item.keterangan : null,
    iscito: item.iscito != undefined && item.iscito == true ? item.iscito : false,
    details: data2,
  }
  isLoading.value = true
  useApi().post(
    `/laboratorium/simpan-order`, objSave).then((response: any) => {
      isLoading.value = false
      historySave.value = dataEx
      sendNotification(response);
      listChecked.value = []
      item.produkCeklis = []
      delete item.keterangan
      delete item.NOREC_SO
    }).catch((e: any) => {
      isLoading.value = false
    })


}
const susulanModal = () => {
  modalPenunjang.value = true
}
const susulan = () => {

  isLoading.value = true
  var objSave = {
    noregistrasi: item.pilihRegistrasi.noregistrasi,
    norec_pd: item.pilihRegistrasi.norec_pd,
    isradiologi: true,
    islaboratorium: false,
    tanggal: H.formatDate(item.tglkunjungan, 'YYYY-MM-DD')
  }
  useApi().post(`/laboratorium/simpan-order-susulan`, objSave).then((response: any) => {
    isLoading.value = false
    if (response.data.isPenunjangSusulanRad != null) {
      isPenunjangSusulan.value = response.data.isPenunjangSusulanRad
    }
    modalPenunjang.value = false
  })

}
// const getEmr = () => {
//   useApi().get(`emr/auto-fill?nocmfk=${ID_PASIEN}&norec_pd=${NOREC_PD}&collection=AsesmenMedisRawatJalan&field=TADiagnosa,MOI,diagnosaIcd10`).then((response) => {
//     if (response != null) {
//       const dataDiagnosa = response.TADiagnosa
//       if (dataDiagnosa != null) {
//         item.catatanKlinis = dataDiagnosa;
//       }
//     } else {
//       useApi().get(`emr/auto-fill?nocmfk=${ID_PASIEN}&norec_pd=${NOREC_PD}&collection=AsesmenAwalMedisGawatDarurat&field=TADiagnosis,TAMOI,diagnosaIcd10`).then((response) => {
//         const dataDiagnosa = response.TADiagnosis
//         if (dataDiagnosa != null) {
//           item.catatanKlinis = dataDiagnosa;
//         }
//       })
//     }
//   })
// }

const getEmr = () => {
  // Cek apakah nama ruangan mengandung kata "nuklir"
  const isNuklir = props.registrasi.namaruangan.toLowerCase().includes('nuklir');

  // Pertama coba ambil dari CPPT
  useApi().get("emr/auto-fill?nocmfk=" + ID_PASIEN +
    "&norec_pd=" + NOREC_PD +
    "&collection=CPPTDetail" +
    "&flag=dokter" +
    "&ruangan=" + props.registrasi.namaruangan +
    "&field=A").then((cpptResponse) => {
      if (cpptResponse != null && cpptResponse.A) {
        item.catatanKlinis = cpptResponse.A;
      } else {
        // Jika CPPT kosong, ambil dari EMR biasa
        const collection = isNuklir
          ? 'AsesmenMedisKedokteranNuklir'
          : 'AsesmenMedisRawatJalan';

        const fields = isNuklir
          ? 'TADiagnosa,MOI,diagnosaIcd10'
          : 'TADiagnosa,MOI,diagnosaIcd10';

        useApi().get(`emr/auto-fill?nocmfk=${ID_PASIEN}&norec_pd=${NOREC_PD}&collection=${collection}&field=${fields}`).then((emrResponse) => {
          if (emrResponse != null) {
            const dataDiagnosa = emrResponse.TADiagnosa || null;
            if (dataDiagnosa) {
              item.catatanKlinis = dataDiagnosa;
            }
          } else {
            // Fallback terakhir ke Asesmen Gawat Darurat
            useApi().get(`emr/auto-fill?nocmfk=${ID_PASIEN}&norec_pd=${NOREC_PD}&collection=AsesmenAwalMedisGawatDarurat&field=TADiagnosis,TAMOI,diagnosaIcd10`).then((gdResponse) => {
              const dataDiagnosa = gdResponse?.TADiagnosis || null;
              if (dataDiagnosa) {
                item.catatanKlinis = dataDiagnosa;
              }
            });
          }
        });
      }
    });
};

const sendNotification = (e) => {
  let ruanganAsal = item.registrasi.namaruangan
  let ruanganTujuan = ''
  let namapengorder = ''
  d_Ruangan.value.forEach((element: any) => {
    if (element.value == e.data.objectruangantujuanfk) {
      ruanganTujuan = element.label
    }
  });

  d_Pegawai.value.forEach((dtPegawai: any) => {
    if (item.pegawaiOrder == dtPegawai.value) {
      namapengorder = dtPegawai.label;
    }
  })

  let body = {
    norec: e.data.norec,
    judul: 'Order Radiologi #' + e.data.noorder,
    jenis: e.data.keteranganorder,
    pesanNotifikasi: `Permohonan dari ${ruanganAsal} ke ${ruanganTujuan}`,
    idRuanganAsal: e.data.objectruanganfk,
    idRuanganTujuan: e.data.objectruangantujuanfk,
    ruanganAsal: ruanganAsal,
    ruanganTujuan: ruanganTujuan,
    kelompokUser: null,
    idKelompokUser: null,
    idPegawai: e.data.objectpegawaiorderfk,//H.pegawaiLogin().id,
    namapegawai: namapengorder,//H.pegawaiLogin().id,
    dataArray: [],
    urlForm: 'module-dashboard-radiologi',
    params: null,
    group: 'mapping_login',
    namaFungsiFrontEnd: null,
    tgl: e.data.tglorder,
    tgl_string: H.formatDateIndoSimple(e.data.tglorder),
  }
  H.sendSocket("sendNotification", body);
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
// let lastID = 0
// const modelPosisiKanan = []
// watch(
//     () => item.kanan,
//     (value) => {
//       if(value == true){
//         getKananSelected()
//       }
//     }
// )
// watch(
//     () => item.kiri,
//     (value) => {
//       if(value == true){
//         getKananSelected()
//       }
//     }
// )
// const getKananSelected = () => {
//   modelPosisiKanan.forEach(element=>{
//     if(lastID == element.id){
//       element.kanan = item.kanan
//     }else{
//       modelPosisiKanan.push({id: lastID, kanan: item.kanan})
//     }
//   })
//   getSelected()
// }

const getRegistrasi = async (e: any) => {
  console.log(e)
  // item.tglorder = e.tanggal  //DEFAULT 
  item.tglorder = new date()
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
              } else {
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
            listChecked.value.push({ hargasatuan: element2.hargasatuan, namaproduk: element2.namaproduk, id: element2.id })
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
    `/radiologi/detail-order?norec=${e.norec}`).then((response: any) => {
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
    `/radiologi/delete-order-rad`, { noorder: e.noorder }).then((response: any) => {
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

const cetakItems = (e: any) => {
  let id_produk = []

  if (e.details.length > 1) {
    detail_Tindakan.value = []
    detail_Tindakan.value = e
    modalDetailTindakan.value = true
    return
  }

  for (let index = 0; index < e.details.length; index++) {
    const element = e.details[index];
    id_produk.push(element.sanata_jasa_id)
  }

  idDokterBaca.value = e.iddokterbaca
  expertise.value = e
  modalExpertise.value = true

  e.details.forEach((dataItem: any) => [
    useApi().get(`/radiologi/get-expertise?norec=${dataItem.norec_pp}`).then((response: any) => {
        norecHasilRadiologi.value = '';
        if (response.expertise_text_only) {
          item.keterangan = response.expertise_text_only
          modalExpertise.value = true
        } else {
          H.alert('warning', 'Hasil belum ada')
        }
      })
  ])
}

const hasilItems = (e: any) => {

  console.log('e hasilitems', e);

  if (e.objectruangantujuanfk == 331) {
    H.printBlade("radiologi/cetak-ekspertise-manual?echo=true&norec=" + e.norec_hr);
  }
  else {
    e.details.forEach((dataItem: any) => {

      console.log(dataItem)

      useApi().get(
        `/radiologi/hasil-pacs?noorder=${dataItem.noorder}&idproduk=${dataItem.sanata_jasa_id}`).then(async (response: any) => {
          if (response.url) {
            H.printBlade("notfound?echo=true");
          } else {
            // router.push({
            //   name: 'module-radiologi-hasil-pacs',
            //   query: {
            //     url: response.data[0].urllink4,
            //   },
            // })
            // window.open(response.data[0].urllink3, '_blank')
            window.open(response.testurl, '_blank')
          }
        })

      //   if (dataItem.order_complete == 0) {
      //       H.alert('warning', 'Hasil belum ada')
      //   } else {
      //       router.push({
      //           name: 'module-radiologi-hasil-pacs',
      //           query: {
      //               url: dataItem.url_pacs_hasil,
      //           }})
      //   }
      //   return

      //   if (dataItem.radiologiId === null || dataItem.radiologiId === '') {
      //       H.alert('warning', 'Hasil belum ada')
      //   } else {

      //       let viewer = null
      //       let patienIdMr = dataItem.radiologiId.replace('null', '1')
      //       let idRuangan = e.objectruangantujuanfk;
      //       useApi().postNoMessage(`/general/api-tools`, {
      //           'method': 'get',
      //           'url': import.meta.env.VITE_URL_PACS_ENGINE + '/dcm4chee-arc/aets/TRANSMEDIC/rs/studies?limit=1&includefield=all&offset=0&PatientID=' + patienIdMr,
      //           'headers': {}
      //       }).then((response: any) => {
      //           if (response.response == null) {
      //               H.alert('warning', 'Hasil foto belum dikirim ke PACS')
      //           } else {
      //               let data = response.response
      //               viewer = data[0]["0020000D"].Value[0]
      //               window.open(import.meta.env.VITE_URL_PACS_VIEWER
      //                   + "/viewer/" + idRuangan
      //                   + "/" + dataItem.norec_pp
      //                   + "/" + props.registrasi.norec_pd
      //                   + "/" + e.noorder + "/" + viewer, "pacs");
      //           }
      //       })

      //   }
    });
  }
}
const expertiseItems = (e: any) => {
  item.norec_pp = e.norec_pp
  item.tanggal = new Date()
  item.namaproduk = e.namaproduk
  item.noregistrasifk = props.registrasi.norec_pd

  useApi().get(`/radiologi/get-expertise?norec=${e.norec_pp}&produkfk=${e.produkfk}`).then((response: any) => {
    if (response != null) {
      norecHasilRadiologi.value = response.norec
      item.norec = response.norec
      item.tanggal = new Date(response.tanggal)
      d_Pegawai.value.forEach(element => {
        if (element.value == response.pegawaifk) {
          item.dokterpemeriksa = element
        }
      });

      item.keterangan = response.keterangan
    } else {
      setTemplate();
    }
  })
  modalExpertise.value = true
}
const showResultRadiologiManual = async (e: any) => {

  console.log('e', e);
  // H.openFile('berkaspasien/' + e.nocm + '/' + e.urlfile); //DEFAULT
  H.openFile(e.urlfile);
}

const clear = () => {
  delete item.keterangan
  delete item.tanggal
  delete item.norec
  delete item.dokterpemeriksa
  modalExpertise.value = false
}


const setTemplate = () => {
  useApi().get(
    `/general/template-expertise`).then((response: any) => {

      let defaultselected = {}
      for (let i = 0; i < response.data.length; i++) {
        const element = response.data[i];
        if (element.default) {
          defaultselected = element;
        }
      }
      item.keterangan = defaultselected.template;
    });
}

const closeExpertiseModal = () => {
  modalDetailTindakan.value = false;
  detail_Tindakan.value = []
}

const saveExpertise = async (e: any) => {
  if(!e.noorder){
    H.alert('warning','Data belum ada')
    return
  }

  if (e.objectruangantujuanfk == 120) {
    H.printBlade("radiologi/cetak-ekspertise-manual?echo=true&norec=" + e.norec_hr);
  } else if (detail_Tindakan.value.length == 0) {
    e.details.forEach(element => {
      H.printBlade(`radiologi/cetak-ekspertise?noorder=${e.noorder}&idproduk=${element.sanata_jasa_id}`);
    });
  } else {
    console.log('masuk ke else ');
    H.printBlade(`radiologi/cetak-ekspertise?noorder=${e.noorder}&idproduk=${e.sanata_jasa_id}`);
  }
}
// const cetakExpertise = (e:any) => {
//   norecHasilRadiologi.value=''
//   // H.printBlade("radiologi/cetak-ekspertise?norec=" + norecHasilRadiologi.value);
//   H.printBlade("radiologi/cetak-ekspertise?norec=" + e);
// }

const tambahPaket = async (e: any) => {

  for (var x = 0; x < e.details.length; x++) {
    const elementx = e.details[x]
    listChecked.value.push({ namaproduk: elementx.namaproduk, hargasatuan: 0, id: elementx.objectprodukfk })
    item.produkCeklis[parseInt(elementx.objectprodukfk)] = true
  }
  modalPaket.value = false
}

const fetchDok = () => {
  useApi().get(
    `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=&settingdatafix=objectjenispegawaifk,idJenisPegawaiDokter&limit=2000`
  ).then((response) => {
    d_Pegawai.value = response
  })
}

const fetchPaket = async () => {
  modalPaket.value = true
  await useApi().get(
    `/tindakan/list-paket?flag=rad`).then((response: any) => {
      dataSourcePaket.value = response
      console.log(dataSourcePaket.value)
    }).catch((e: any) => {

    })
}

const cetakExper = (e: any) => {
  H.printBlade("radiologi/cetak-ekspertise?norec=" + e.norec_hr);
}
watch(() => listChecked.value.length, (newValue) => {
  if (newValue != historySave.value.length) {
    disabledSave.value = false
    historySave.value = []
  }
  if (newValue == historySave.value.length) disabledSave.value = true
})

watch(() => isPaket.value, (newValue, oldValue) => {
  if (newValue == true) {
    fetchPaket()
  }
})
// onBeforeMount(() => {
//     try {
//         let cache =  H.cacheEMR().get(`TAB~${props.registrasi.noregistrasi}~${route.name}`)
//         if(cache){
//             item.produkCeklis = cache.produkCeklis
//             getSelected()
//         }
//     } catch (error) {
//         console.error('Error mount cache TAB EMR:', error);
//     }
// });
// onBeforeRouteLeave((to, from, next) => {
//     try {
//         let rouutename = from?.name
//         H.cacheEMR().set(`TAB~${props.registrasi.noregistrasi}~${rouutename}`, {
//             'produkCeklis':item.produkCeklis,
//         })
//     } catch (error) {
//         console.error('Error leave cache TAB EMR:', error);
//     }
//     next();
// });
pasienByID(ID_PASIEN)
fetchDropdown()
fetchDok()
getEmr()
</script>
<style lang="scss">
@import '/@src/scss/abstracts/all';
@import '/@src/scss/components/forms-outer';
@import '/@src/scss/custom/config';
@import '/@src/scss/custom/timeline-css';
@import '/@src/scss/module/emr/order-laboratorium.scss';

.form-layout.is-separate {
  max-width: 1240px;
}

.required-field::after {
  color: #e32;
  content: " *";
}

.custom-text {
  font-family: var(--font);
  font-size: 0.9rem;
  font-weight: 400;
}
</style>
