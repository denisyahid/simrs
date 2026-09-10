<template>
  <ConfirmDialog />
  <div class="form-layout is-stacked-2">
    <div class="form-outer" style="margin-top:15px">
      <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
        <div class="form-header-inner">
          <div class="left">
            <h3> {{ props.FORM_NAME }}</h3>
          </div>
          <div class="right">
            <ButtonEmr :NOREC_EMRPASIEN="NOREC_EMRPASIEN" :COLLECTION="COLLECTION" :isHideCetak="true"
              :isLoading="isLoading" @simpan="simpan" @kembaliKeun="kembaliKeun"></ButtonEmr>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="column">
    <div class="columns is-multiline p-0">
      <div class="column is-12">
        <VCard>
          <div class="tabs-wrapper" :class="['tab-naver']">
            <div class="tabs-inner">
              <div class="tabs is-boxed">
                <ul>
                  <li v-for="(tab, key) in tabs" :key="key" :class="[activeValue === tab.value && 'is-active']">
                    <slot name="tab-link" :active-value="activeValue" :tab="tab" :index="key" :toggle="toggle">
                      <a tabindex="0" @keydown.space.prevent="toggle(tab.value)" @click="toggle(tab.value)">
                        <VIcon v-if="tab.icon" :icon="tab.icon" />
                        <span>
                          <slot name="tab-link-label" :active-value="activeValue" :tab="tab" :index="key">
                            {{ tab.label }}
                          </slot>
                        </span>
                      </a>
                    </slot>
                  </li>
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
        <VPlaceload height="20rem" width="100%" class="mx-2" v-if="loadData" />
        <VCard class="mt-5">
          <div class="column is-12" v-if="activeValue == 1">
            <div class="columns is-multiline">
              <div class="column is-12 ">
                <div class="form-section  pt-0 pr-0">
                  <div class="form-section-inner has-padding-bottom">
                    <h3 class="has-text-centered">Detail Order </h3>
                    <div class="columns is-multiline">
                      <div class="column is-12 py-1">
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
                            <VInput type="text" placeholder="" autocomplete="off" v-model="item.registrasi.namaruangan"
                              disabled />
                          </VControl>
                        </VField>
                      </div>
                      <div class="column is-12">
                        <VField label="Pengorder " class="is-rounded-select_Z  is-autocomplete-select" v-slot="{ id }">
                          <VControl icon="fa:user-md" fullwidth>
                            <Multiselect mode="single" v-model="item.pegawaiOrder" placeholder="Pilih data"
                              :searchable="true" :filter-results="false" :min-chars="0" :attrs="{ id }"
                              :resolve-on-load="true" :delay="0" :options="d_Pegawai" autocomplete="off" />
                          </VControl>
                        </VField>
                      </div>
                      <div class="column is-12">
                        <VField label="Jenis Darah" class="is-rounded-select_Z  is-autocomplete-select"
                          v-slot="{ id }">
                          <VControl icon="feather:list" fullwidth>
                            <Multiselect mode="single" v-model="item.golonganDarah" :options="d_GolonganDarah"
                              placeholder="Pilih data" :searchable="true" :attrs="{ id }" autocomplete="off" />
                          </VControl>
                        </VField>
                      </div>
                      <!-- <div class="column is-12">
                        <VField label="Qty">
                          <VControl>
                            <VInput v-model="item.qty" autocomplete="off" placeholder="qty" autocapitalize="off"
                              spellcheck="true"></VInput>
                          </VControl>
                        </VField>
                      </div> -->
                      <div class="column is-12">
                        <VField label="Ketarangan">
                          <VControl>
                            <VTextarea class="textarea" v-model="item.keterangan" rows="2"
                              placeholder="catatan order (optional) ..." autocomplete="off" autocapitalize="off"
                              spellcheck="true" />
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
                      <div class="column is-12" v-if="item.iscito">
                        <div class="column is-multiline">
                          <div class="column is-12">
                            <h1 style="font-weight: 600;"> Dengan ini Saya</h1>
                          </div>
                          <div class="column is-12 p-0">
                            <div class="is-flex">
                              <div class="column is-2" style="margin-top:0.5rem">
                                <span> Dokter : </span>
                              </div>
                              <div class="column is-6">
                                <VField>
                                  <VControl>
                                    <AutoComplete v-model="input.namaDokter" :suggestions="d_Dokter"
                                      :optionLabel="'label'" @complete="fetchDokter($event)" :dropdown="true"
                                      :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                                      placeholder="Dokter..." class="mt-2" />
                                  </VControl>
                                </VField>
                              </div>
                            </div>
                          </div>
                          <div class="column is-12">
                            <h1 style="font-weight: 600;"> Mohon segera diberikan labu darah tanpa menunggu hasil
                              pemeriksaan crossmatch selesai untuk OS.</h1>
                          </div>
                          <div class="column is-12 p-0">
                            <div class="is-flex">
                              <div class="column is-2" style="margin-top:0.5rem">
                                <span> Nama Pasien : </span>
                              </div>
                              <div class="column is-6">
                                <VField>
                                  <VControl>
                                    <VInput v-model="input.namaPasien"></VInput>
                                  </VControl>
                                </VField>
                              </div>
                            </div>
                          </div>
                          <div class="column is-12 p-0">
                            <div class="is-flex">
                              <div class="column is-2" style="margin-top:0.5rem">
                                <span> Jenis Kelamin : </span>
                              </div>
                              <div class="column is-10">
                                <div class="columns  is-multiline p-0">
                                  <div class="column is-3" v-for="items in JenisKelamin" :key="items.value">
                                    <VField style="padding:0px;">
                                      <VControl raw subcontrol>
                                        <VCheckbox v-model="input.jenisKelamin" class="pt-1 pb-1 "
                                          :true-value="items.label" :label="items.label" color="primary" square />
                                      </VControl>
                                    </VField>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                          <div class="column is-12 p-0">
                            <div class="is-flex">
                              <div class="column is-2" style="margin-top:0.5rem">
                                <span> Nomer RM : </span>
                              </div>
                              <div class="column is-6">
                                <VField>
                                  <VControl>
                                    <VInput v-model="input.noregistrasi"></VInput>
                                  </VControl>
                                </VField>
                              </div>
                            </div>
                          </div>
                          <div class="column is-12 p-0">
                            <div class="is-flex">
                              <div class="column is-2" style="margin-top:0.5rem">
                                <span> Ruangan : </span>
                              </div>
                              <div class="column is-6">
                                <VField>
                                  <VControl>
                                    <AutoComplete v-model="input.ruangan" :suggestions="d_Ruangan" :optionLabel="'label'"
                                      @complete="fetchDokter($event)" :dropdown="true" :minLength="3" :appendTo="'body'"
                                      :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="Ruangan..."
                                      class="mt-2" />
                                  </VControl>
                                </VField>
                              </div>
                            </div>
                          </div>
                          <div class="column is-12 p-0">
                            <div class="is-flex">
                              <div class="column is-2" style="margin-top:0.5rem">
                                <span> Alasan Transfusi: </span>
                              </div>
                              <div class="column is-6">
                                <VField>
                                  <VControl>
                                    <VTextarea rows="2" v-model="input.alesanTransfusi"></VTextarea>
                                  </VControl>
                                </VField>
                              </div>
                            </div>
                          </div>
                          <div class="column is-12 p-0">
                            <div class="is-flex">
                              <div class="column is-2" style="margin-top:0.5rem">
                                <span> Golongan Darah: </span>
                              </div>
                              <div class="column is-6">
                                <div class="columns  is-multiline p-0">
                                  <div class="column is-3" v-for="items in d_Darah" :key="items.value">
                                    <VField style="padding:0px;">
                                      <VControl raw subcontrol>
                                        <VCheckbox v-model="input.darah" class="pt-1 pb-1 " :true-value="items.value"
                                          :label="items.label" color="primary" square />
                                      </VControl>
                                    </VField>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                          <div class="column is-12 p-0">
                            <div class="is-flex">
                              <div class="column is-2" style="margin-top:0.5rem">
                                <span> Rhesus: </span>
                              </div>
                              <div class="column is-6">
                                <div class="columns  is-multiline p-0">
                                  <div class="column is-3" v-for="items in d_Rhesus" :key="items.value">
                                    <VField style="padding:0px;">
                                      <VControl raw subcontrol>
                                        <VCheckbox v-model="input.Rhesus" class="pt-1 pb-1 " :true-value="items.value"
                                          :label="items.label" color="primary" square />
                                      </VControl>
                                    </VField>
                                  </div>
                                </div>
                              </div>
                            </div>
                            <div class="column is-12 p-0">
                              <div class="is-flex">
                                <div class="column is-2" style="margin-top:0.5rem">
                                  <span> Darah Yang Diminta: </span>
                                </div>
                                <div class="column is-6">
                                  <div class="columns  is-multiline p-0">
                                    <div class="column is-6">
                                      <VField label="WB"></VField>
                                      <VField addons>
                                        <VControl expanded>
                                          <VInput type="text" class="input" placeholder="WB" v-model="input.wb"
                                            :tabindex="1" />
                                        </VControl>
                                        <VControl class="field-addon-body">
                                          <VButton static>CC</VButton>
                                        </VControl>
                                      </VField>
                                    </div>
                                    <div class="column is-6">
                                      <VField label="FFP"></VField>
                                      <VField addons>
                                        <VControl expanded>
                                          <VInput type="text" class="input" placeholder="FFP" v-model="input.ffp"
                                            :tabindex="1" />
                                        </VControl>
                                        <VControl class="field-addon-body">
                                          <VButton static>CC</VButton>
                                        </VControl>
                                      </VField>
                                    </div>
                                    <div class="column is-6">
                                      <VField label="PRC"></VField>
                                      <VField addons>
                                        <VControl expanded>
                                          <VInput type="text" class="input" placeholder="PRC" v-model="input.prc"
                                            :tabindex="1" />
                                        </VControl>
                                        <VControl class="field-addon-body">
                                          <VButton static>CC</VButton>
                                        </VControl>
                                      </VField>
                                    </div>
                                    <div class="column is-6">
                                      <VField label="TC"></VField>
                                      <VField addons>
                                        <VControl expanded>
                                          <VInput type="text" class="input" placeholder="TC" v-model="input.tc"
                                            :tabindex="1" />
                                        </VControl>
                                        <VControl class="field-addon-body">
                                          <VButton static>Kantong</VButton>
                                        </VControl>
                                      </VField>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                            <div class="column is-10 p-0">
                              <h1 style="font-weight:600">Saya akan bertanggung jawab terhadap semua resiko transfusi
                                yang mungkin terjadi pada pasien tersebut diatas.</h1>
                              <h1 style="font-weight:600">Atas Perhatian dan kerjasamanya saya ucapkan terima kasih</h1>
                            </div>
                            <div class="columns is-multiline pt-5" style="justify-content: space-around;">
                              <div class="column is-4" style="text-align: center;">
                                <TandaTangan :elemenID="'signaturePegawai'" :width="'180'" :height="'180'" class="dek" />
                                <div class="column pl-0 pr-0 pt-5">
                                  <span class="label-ppap">Petugas</span>
                                  <VField class="pt-3">
                                    <VControl class="prime-auto">
                                      <AutoComplete v-model="input.pegawai" :suggestions="d_Pegawai2"
                                        @complete="fetchPegawai($event)" :optionLabel="'label'" :dropdown="true"
                                        :minLength="3" :appendTo="'body'" @item-select="setTandaTanganPerawat($event)"
                                        :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="Cari Pegawai..." />
                                    </VControl>
                                  </VField>
                                </div>
                              </div>
                              <div class="column is-4" style="text-align: center;">
                                <TandaTangan :elemenID="'signPembuatPernyataan'" :width="'180'" :height="'180'"
                                  class="dek" />
                                <div class="column pl-0 pr-0 pt-5">
                                  <span class="label-ppap">Dokter</span>
                                  <VField class="pt-3">
                                    <VControl class="prime-auto">
                                      <AutoComplete v-model="input.dokter" :suggestions="d_Dokter"
                                        @complete="fetchDokter($event)" :optionLabel="'label'" :dropdown="true"
                                        :minLength="3" :appendTo="'body'" @item-select="setTandaTanganPerawat($event)"
                                        :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="Cari Dokter..." />
                                    </VControl>
                                  </VField>
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
          <div class="column is-12" v-else>
            <div class="columns is-multiline">
              <div class="column is-12 ">
                <div class="form-section  pt-0 pr-0">
                  <div class="has-padding-bottom h-500-o ">
                    <h3 class="has-text-centered">Riwayat </h3>
                    <div class="columns is-multiline">
                      <div class="column is-12">
                        <TRiwayatOrderDarah title="" straight class="list-widget-v3" :items="listRiwayat"
                          @editItems="editItems" @hapusItems="DialogConfirm" @hasilItems="hasilItems" squared colored>
                        </TRiwayatOrderDarah>

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
        </VCard>
      </div>
    </div>
  </div>
  <!-- modal hasil -->
  <VModal :open="modalHasilLab" title="Hasil Pemeriksaan" :noclose="false" size="medium" actions="right"
    @close="modalHasilLab = false, clear()">
    <template #content>
      <div class="column is-12" v-if="riwayatPemeriksaan">
        <div class="project-files">
          <div class="widget creative-list-widget">
            <div class="widget-toolbar">
              <div class="right">
              </div>
            </div>
            <div class="creative-list panjang-250">
              <VTag class="mr-1 mb-1" :color="'success'" :label="'Normal'" />
              <VTag class="mr-1 mb-1" :color="'danger'" :label="'Tinggi/Rendah Kritis'" />
              <VTag class="mr-1 mb-1" :color="'warning'" :label="'Tinggi/Rendah'" />
              <div class=" mb-2">
                <div>
                  <div class="creative-list-item  is-clickable" :class="'is-primary'" style="margin-bottom:0;"
                    :style="riwayatPemeriksaan.isdetail ? 'height:60%;border-radius: 10px 10px 0 0;' : ''"
                    @click="riwayatPemeriksaan.isdetail = !riwayatPemeriksaan.isdetail">
                    <i aria-hidden="true" class="lnir lnir-drop-alt"></i>
                    <div class="meta">
                      <p>{{ riwayatPemeriksaan.namaproduk }}</p>
                      <span>{{ riwayatPemeriksaan.tglorder }}</span>
                    </div>
                    <VTag :color="(riwayatPemeriksaan.status == 'verifikasi' ? 'info' : '')"
                      :label="!riwayatPemeriksaan.isdetail ? riwayatPemeriksaan.status : 'selesai'"
                      class="mt-0 ml-5 is-pulled-right" />
                  </div>
                  <div v-if="riwayatPemeriksaan.isdetail"
                    style="border-radius: 0 0 10px 10px; background-color: rgb(249 235 242); padding:  0 10px 10px 10px;"
                    class="f-table">
                    <table class="w-100">
                      <thead>
                        <tr>
                          <th>#</th>
                          <th>Pemeriksaan</th>
                          <th>Hasil</th>
                          <th>Golongan Darah</th>
                        </tr>
                      </thead>
                      <tbody v-if="riwayatPemeriksaan.details">
                        <tr v-for="(data, index) in riwayatPemeriksaan.details">
                          <td>
                            <span class="f-bold f-italic">{{ index + 1 }}</span>
                          </td>
                          <td>
                            <span class="f-bold f-italic">{{ riwayatPemeriksaan.namaproduk
                            }}</span>
                          </td>
                          <td>
                            <span class="f-bold f-italic">{{ riwayatPemeriksaan.hasil
                            }}</span>
                          </td>
                          <td>
                            <span class="f-bold f-italic">{{ data.nomerkantong
                            }}
                            </span>
                          </td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <VPlaceholderPage v-else="! riwayatPemeriksaan" :title="H.assets().notFound" :subtitle="H.assets().notFoundSubtitle"
        larger>
        <template #image>
          <img class="light-image" :src="H.assets().iconNotFound_rev" alt="" />
          <img class="dark-image" src="/@src/assets/illustrations/placeholders/search-4-dark.svg" alt="" />
        </template>
      </VPlaceholderPage>
    </template>
  </VModal>
  <!-- end modal hasil -->
</template>
<script setup lang="ts">
import { useWindowScroll } from '@vueuse/core'
import { useApi } from '/@src/composable/useApi'
import { h, reactive, ref, computed, defineComponent, watch } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useHead } from '@vueuse/head'
import * as H from '/@src/utils/appHelper'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useThemeColors } from '/@src/composable/useThemeColors'
import { useToaster } from '/@src/composable/toaster'
import { useUserSession } from '/@src/stores/userSession'
import { useConfirm } from "primevue/useconfirm"
import ConfirmDialog from 'primevue/confirmdialog'
import TRiwayatOrderDarah from '../t-riwayat-order-darah.vue'
import ButtonEmr from '../page-emr-plugins/button-emr.vue'
import AutoComplete from 'primevue/autocomplete'
import TandaTangan from '../page-emr-plugins/tanda-tangan.vue'

useHead({
  title: 'Order Darah - ' + import.meta.env.VITE_PROJECT,
})
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)

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
let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pd as string
let norec_emr = useRoute().query.norec_emr as string
let NOREC_APD = useRoute().query.norec_apd as string

const route = useRoute()
const { y } = useWindowScroll()
const isStuck = computed(() => { return y.value > 30 })
const activeValue: any = ref(1)
const isLoadingPasien: any = ref(false)
const NOREC_EMRPASIEN: any = ref('')
const loadData: any = ref(false)
const isLoading: any = ref(false)
const modalHasilLab: any = ref(false)
const d_Pegawai: any = ref([])
const d_Pegawai2: any = ref([])
const d_Dokter: any = ref([])
const d_Ruangan: any = ref([])
const riwayatPemeriksaan: any = ref({})
const confirm = useConfirm()
const d_GolonganDarah: any = ref([])
const listRiwayat = ref([])
const listColor2: any = ref(['primary', 'info', 'orange', 'yellow', 'success'])
const tabs: any = ref([
  { label: 'Order', value: 1, icon: 'fas fa-file-medical-alt' },
  { label: 'Riwayat', value: 2, icon: 'fas fa-list' }
])
const COLLECTION: any = ref('orderDarah')
const d_Darah: any = ref([
  {
    label: 'A', value: 'A',
  },
  {
    label: 'B', value: 'B',
  },
  {
    label: 'O', value: 'O',
  },
  {
    label: 'AB', value: 'AB',
  }
])
const d_Rhesus: any = ref([
  {
    label: 'pos', value: 'pos'
  },
  {
    label: 'neg', value: 'neg'
  }
])
const JenisKelamin: any = ref([
  { label: 'Laki - Laki', value: 'Laki - Laki' },
  { label: 'Perempuan', value: 'Perempuan' }
])
const item: any = reactive({
  NOREC_PD: NOREC_PD != undefined ? NOREC_PD : '',
  NOREC_APD: '',
  tglorder: new Date(),
  registrasi: {},
  pegawaiOrder: useUserSession().getUser().id,
  selectedMenu: [false]
})
const input: any = ref({})
const pasien: any = ref({})
const simpan = async () => {
  if (item.pegawaiOrder == undefined) {
    H.alert('error', 'Pilih Pengorder')
    return
  }
  if (item.tglorder == undefined) {
    H.alert('error', 'Pilih Tgl Order  terlebih dahulu')
    return
  }
  isLoading.value = true;
  var objSave = {
    noregistrasi: item.registrasi.noregistrasi,
    tanggal: H.formatDate(item.tglorder, 'YYYY-MM-DD HH:mm:ss'),
    norec_so: item.NOREC_SO ? item.NOREC_SO : '',
    norec_apd: item.NOREC_APD,
    norec_pd: item.NOREC_PD,
    qtyproduk: item.qty ?? 1,
    golongandarahfk: item.golonganDarah ? item.golonganDarah : null,
    objectruanganfk: item.registrasi.objectruanganlastfk,
    pegawaiorderfk: item.pegawaiOrder,
    keterangan: item.keterangan != undefined ? item.keterangan : null,
    iscito: item.iscito != undefined && item.iscito == true ? item.iscito : false,
    details: [],
  }

  await useApi().post(
    `/darah/simpan-order`, objSave).then((response: any) => {
      isLoading.value = false
      delete item.NOREC_SO
    }).catch((e: any) => {
      isLoading.value = false
    })

  if (item.iscito) {
    let ID = input.value.id ? input.value.id : ''
    let object: any = {}
    object = input.value
    object.pasien = H.setObjectPasien(props.pasien)
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
    useApi().postNoMessage(
      `/emr/simpan-emr`, json).then((response: any) => {
        isLoading.value = false
        NOREC_EMRPASIEN.value = response.norec_emr
        input.value.id = response.id
      }).catch((e: any) => {
        isLoading.value = false
      })
  }
  clear()
}
const clear = () => {
  delete item.golonganDarah
  delete item.keterangan
  delete input.value.namaDokter
  delete input.value.namaPasien
  delete input.value.noregistrasi
  delete input.value.alesanTransfusi
  delete input.value.darah
  delete input.value.Rhesus
  delete input.value.wb
  delete input.value.ffp
  delete input.value.prc
  delete input.value.tc
}
const kembaliKeun = () => {
  window.history.back()
}
const fetchData = async () => {
  listRiwayat.value = []
  useApi().get(
    `/darah/riwayat-order?nocmfk=${ID_PASIEN}&norec_pd=${item.NOREC_PD}`).then((response: any) => {
      let z = 0
      for (let x = 0; x < response.length; x++) {
        const element = response[x];
        element.icon = 'lnir lnir-drop-alt'
        element.color = listColor2.value[z]
        element.tglorder = H.formatDateIndoSimple(new Date(element.tglorder))
        element.tgloperasi = H.formatDateIndoSimple(new Date(element.tgloperasi))
        if (z > 4) {
          z = 0
        }
        z++
      }
      listRiwayat.value = response
    })

}
const editItems = (e: any) => {
  if (e.status != 'pending') {
    H.alert('error', 'Order sudah diverifikasi')
    return
  }
}
function pasienByID(id: any) {
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
      })
  }
}
function fetchDropdown() {
  useApi().get(`/darah/list-dropdown`).then((response: any) => {
    d_GolonganDarah.value = response.jenis.map((e: any) => { return { label: e.detailjenisproduk, value: e.id } })
    // d_Darah.value = response.golonganDarah.map((e: any) => { return { label: e.golongandarah, value: e.id } })
  })
  useApi().get(
    `emr/dropdown/pegawai_m?select=id,namalengkap,kddokterbpjs&param_search=namalengkap&query=&settingdatafix=objectjenispegawaifk,idJenisPegawaiDokter`
  ).then((response) => {
    d_Pegawai.value = response
    d_Pegawai.value.forEach((element: any) => {
      if (props.registrasi.objectpegawaifk == element.value) {
        item.pegawaiOrder = element.value
      }
    });
  })
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
  console.log("success");

}
const hapusItems = (e: any) => {
  if (e.status != 'pending') {
    H.alert('error', 'Order sudah diverifikasi')
    return
  }
  useApi().post(
    `/darah/delete-order-darah`, { noorder: e.noorder }).then((response: any) => {
      isLoading.value = false
      fetchData()
    }).catch((e: any) => {
      isLoading.value = false
    })
}
const fetchDokter = async (filter: any) => {
  await useApi().get(
    `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&settingdatafix=objectjenispegawaifk,idJenisPegawaiDokter&limit=10`
  ).then((response) => {
    d_Dokter.value = response
  })
}
const fetchRuangan = async (filter: any) => {
  const response = await useApi().get(
    `/emr/dropdown/ruangan_m?select=id,namaruangan&param_search=namaruangan&query=${filter.query}&limit=10`)
  d_Ruangan.value = response
}
const fetchPegawai = async (filter: any) => {

  await useApi().get(
    `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`
  ).then((response) => {
    d_Pegawai2.value = response
  })
}
function toggle(value: string) {
  activeValue.value = value
}
pasienByID(ID_PASIEN)
fetchDropdown()
watch(
  () => activeValue.value,
  (value) => {
    if (value == 2) {
      fetchData()
    }
  }
)
const setAutoFill = async () => {

  input.value.namaPasien = props.pasien.namapasien
  input.value.jenisKelamin = props.pasien.jeniskelamin
  input.value.noregistrasi = props.pasien.nocm
  input.value.noTelp = props.pasien.nohp
  input.value.tanggalLahirPasien = props.pasien.tgllahir
  input.value.alamatPasien = props.pasien.alamatlengkap
  input.value.dokterRawat = props.registrasi.dokter
  input.value.dirawatDiRuang = props.registrasi.namaruangan
  input.value.kelas = props.registrasi.namakelas
  input.value.kelompokPasien = props.registrasi.kelompokpasien
  input.value.tglPembuatan = new Date()
  input.value.tglDirawat = props.registrasi.tglregistrasi
  input.value.ruangan = { label: props.registrasi.namaruangan, value: props.registrasi.objectruanganfk }
}
const setTandaTanganPerawat = async (e: any) => {
  await useApi().get(`emr/tanda-tangan/${e.value.value}`).then((element) => {
    if (element) {
      H.tandaTangan().set("signaturePegawai", element.ttd)
    } else {
      H.tandaTangan().set("signaturePegawai", '')
    }
  })
}
const hasilItems = (e: any) => {
  modalHasilLab.value = true;
  useApi().get(
    `bank-darah/hasil-darah?norec_pp=${e.norec}`
  ).then((response) => {
    console.log(response);
    riwayatPemeriksaan.value = response
  })
}
setAutoFill();
</script>
<style lang="scss">
@import '/@src/scss/abstracts/all';
@import '/@src/scss/components/forms-outer';
@import '/@src/scss/custom/config';
@import '/@src/scss/custom/timeline-css';
@import '/@src/scss/module/emr/order-laboratorium.scss';

.has-text-centered {
  font-family: var(--font-alt);
  font-size: 1.2rem;
  font-weight: 600;
  color: var(--dark-text);
  margin-bottom: 30px;
}
</style>
