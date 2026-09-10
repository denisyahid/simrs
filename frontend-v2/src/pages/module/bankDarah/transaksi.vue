<template>
  <ConfirmDialog group="positionDialog"></ConfirmDialog>
  <div class="form-layout is-stacked">
    <div class="form-outer" style="margin-top: 15px">


      <div class="form-body p-2">
        <div class="business-dashboard hr-dashboard">
          <div class="columns is-multiline">
            <div class="column is-12" v-if="!isLoadingPasien">
              <div class="block-header">
                <div class="left">
                  <div class="current-user">
                    <VAvatar size="medium" :picture="pasien.jeniskelamin == 'PEREMPUAN'
                      ? '/images/avatars/svg/vuero-4.svg'
                      : '/images/avatars/svg/vuero-1.svg'
                      " squared />
                    <h3>{{ pasien.namapasien }}</h3>
                  </div>
                </div>
                <div class="center">
                  <div class="columns">
                    <div class="column">
                      <h4 class="block-heading">No RM</h4>
                      <p class="block-text">{{ pasien.nocm }}</p>
                      <h4 class="block-heading">Tgl Lahir</h4>
                      <p class="block-text">{{ pasien.tgllahir }}</p>
                    </div>
                    <div class="column">
                      <h4 class="block-heading">NIK</h4>
                      <p class="block-text">{{ pasien.noidentitas }}</p>
                      <h4 class="block-heading">Kelamin</h4>
                      <p class="block-text">{{ pasien.jeniskelamin }}</p>
                    </div>
                  </div>
                </div>
                <div class="right">
                  <div class="columns">
                    <div class="column">
                      <h4 class="block-heading">No HP</h4>
                      <p class="block-text">{{ pasien.nohp }}</p>
                      <h4 class="block-heading">Alamat</h4>
                      <p class="block-text">{{ pasien.alamatlengkap }}</p>
                    </div>
                    <div class="column">
                      <h4 class="block-heading">Umur</h4>
                      <VTag color="orange" :label="pasien.umur" />

                      <h4 class="block-heading" style="margin-top: 1rem;">Jenis Pasien</h4>
                      <VTag color="orange" :label="pasien.kelompokpasien" />
                    </div>

                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="personal-dashboard personal-dashboard-v2">
      <div class="columns is-multiline">
        <div class="column is-12" v-if="pasien.namapasien == undefined">
          <VCard>
            <PlaceloadHeader />
          </VCard>
        </div>
        <div class="column is-12 mt-0">
          <div class="dashboard-card has-margin-bottom">
            <div class="card-head">
              <h3 class="dark-inverted"> TRANSAKSI PELAYANAN </h3>
            </div>
            <div class="active-projects">
              <div class="columns is-multiline">
                <div class="column is-4">
                  <VField>
                    <VControl icon="feather:search">
                      <input v-model="filters" type="text" class="input is-rounded" placeholder="Filter " />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-1">
                </div>
                <div class="column is-7">
                  <div class="columns is-multiline is-pulled-right mr-4">
                    <div class="column pr-0 m-0">
                      <VButton class="ml-2 is-pulled-right" icon="pi pi-plus" raised bold @click="inputTindakan(item)"
                        :loading="isLoadingBill" color="info">
                        Input Tindakan
                      </VButton>
                    </div>
                    <div class="column pr-0 m-0">
                      <VButton class="ml-2 is-pulled-right" icon="fas fa-print" raised bold
                        @click="cetakSuratPersetujuan()" color="purple">
                        Cetak
                      </VButton>
                    </div>
                  </div>
                </div>
                <div class="column is-12">
                  <div class="column is-12">
                    <table class="tb-custom mt-3">
                      <thead>
                        <tr>
                          <th width="10%" class="text-center">
                            <VControl raw subcontrol style="margin-top:-10px">
                              <VCheckbox v-model="item.checkAll" label="#" color="info"
                                @change="checkedAll(item.checkAll)" :value="item.checkAll" />
                            </VControl>
                          </th>
                          <th width="30%">LAYANAN</th>
                          <th>HARGA SATUAN</th>
                          <th>JUMLAH</th>
                          <th>SUBTOTAL</th>
                          <th>OPSI</th>
                        </tr>

                      </thead>
                      <tbody v-if="isLoadingBill">
                        <tr>
                          <td colspan="5">
                            <div class="list-view list-view-v1 is-fullwidth">
                              <div class="list-view-inner">
                                <div v-for="key in 6" :key="key" class="list-view-item mt-2">
                                  <VPlaceloadWrap>
                                    <VPlaceloadAvatar size="medium" />
                                    <VPlaceloadText last-line-width="60%" class="mx-2" />
                                    <VPlaceload class="mx-2" disabled />
                                    <VPlaceload class="mx-2 h-hidden-tablet-p" />
                                    <VPlaceload class="mx-2 h-hidden-tablet-p" />
                                    <VPlaceload class="mx-2" />
                                  </VPlaceloadWrap>
                                </div>
                              </div>
                            </div>
                          </td>
                        </tr>
                      </tbody>
                      <div style="max-height:500px;min-height: 300px; overflow-y: scroll;display: block;">
                        <tbody v-if="!isLoadingBill" v-for="(items, index)  in dataSourcefiltered" :key="index">
                          <tr>
                            <td colspan="5" class="koneng">
                              {{ H.formatDateOnlyLong(items.tglpelayanan_group) }}
                            </td>
                          </tr>
                          <tr v-for="(itemsDet, index2)  in items.details" :key="index2">
                            <td width="5%">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="modelCheck[itemsDet.norec]" :value="itemsDet.itemsDet" color="info"
                                  square :class="modelCheck[items.norec] == true ? 'is-solid' : ''"
                                  @change="checkedItems()" />
                              </VControl>
                            </td>
                            <td width="30%">
                              <div class="columns is-multiline">
                                <div class="column is-12">
                                  <div class="title-ruangan">{{ itemsDet.namaruangan }}</div>
                                  <div class="title-layan">{{ itemsDet.namaproduk }} - {{
                                    itemsDet.dokterpemeriksa ?
                                    itemsDet.dokterpemeriksa : 'Dokter belum di input'
                                  }}</div>
                                  <div>
                                    <VTag :color="itemsDet.strukresepfk != null ? 'danger' : 'info'"
                                      :label="itemsDet.tglpelayanan" />
                                    <VTag :color="'secondary'" class="ml-2" label="Kirim LIS"
                                      v-if="itemsDet.idbridging != null" />
                                  </div>
                                  <div class="title-kelas">{{ itemsDet.namakelas }} / {{ itemsDet.noorder }}</div>
                                </div>
                              </div>
                            </td>
                            <td class="center">
                              <div class="columns is-multiline">
                                <div class="column is-12">
                                  <div class="title-ruangan">Jasa : {{ H.formatRp(itemsDet.jasa, 'Rp. ') }}</div>
                                  <div class="title-layan">{{ H.formatRp(itemsDet.hargasatuan, 'Rp. ') }}</div>

                                  <div class="title-kelas">Diskon : {{ H.formatRp(itemsDet.hargadiscount, 'Rp. ') }}
                                  </div>
                                </div>
                              </div>
                            </td>
                            <td class="center">{{ itemsDet.jumlah }}</td>
                            <td class="center">{{ H.formatRp(itemsDet.total, 'Rp. ') }}</td>
                            <td class="center">
                              <VIconButton color="danger" class="mr-2" light raised circle icon="feather:trash"
                                @click="hapusItems(itemsDet)" v-tooltip.bubble="'Hapus Layanan'" />
                              <VIconButton color="info" light raised circle icon="feather:edit" class="mr-1"
                                v-tooltip.bubble="'Hasil'" @click="hasilLab(itemsDet)" />
                              <VIconButton color="danger" light raised circle icon="feather:printer" class="mr-1"
                                v-tooltip.bubble="'Surat Persetujuan'" @click="suratPersetujuan(itemsDet)" />
                            </td>
                          </tr>
                        </tbody>
                        <div class="search-results-wrapper"
                          v-if="dataSourcefiltered.length == 0 && isLoadingBill == false">
                          <div class="search-results-body ">
                            <div class="page-placeholder">
                              <div class="placeholder-content">
                                <img class="light-image" style=" max-width: 340px;" :src="H.assets().iconNotFound_rev"
                                  alt="" />
                                <img class="dark-image" style=" max-width: 340px;" :src="H.assets().iconNotFound_rev"
                                  alt="" />
                                <h3>{{ H.assets().notFound }}</h3>
                                <p class="is-larger">
                                  {{ H.assets().notFoundSubtitle }}
                                </p>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </table>
                  </div>

                </div>
              </div>
              <div class="content mt-0 mb-0">
                <div class="is-divider mt-3 mb-2" data-content="TOTAL"></div>
              </div>
              <div class="load-more-wrap has-text-centered p-1 mb-3">
                <div class="columns is-multiline">
                  <div class="column is-3">
                    <VCardCustom :style="'padding:5px 25px'">
                      <div class="label-status primary">
                        <i aria-hidden="true" class="fas fa-circle"></i>
                        <span class="ml-1">TAGIHAN</span>
                      </div>
                      <small class="text-bold-custom">{{
                        H.formatRp(item.TOTAL,
                          'Rp.')
                      }}</small>

                    </VCardCustom>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <VModal :open="modalHasil" title="Masukan Hasil" :noclose="false" size="big" actions="right"
    @close="modalHasil = false, clear()">
    <template #content>
      <form class="modal-form">
        <div class="columns is-multiline">
          <div class="column is-2">
            <VField>
              <VLabel>Nomer Order</VLabel>
              <VControl icon="feather:box">
                <VInput type="text" v-model="item.noorder" placeholder="Nomer Order" class="is-rounded" disabled />
              </VControl>
            </VField>
          </div>
          <div class="column is-4">
            <VField>
              <VLabel>Nama Pelayanan</VLabel>
              <VControl icon="feather:box">
                <VInput type="text" v-model="item.namaproduk" placeholder="Nama Pelayanan" class="is-rounded" disabled />
              </VControl>
            </VField>
          </div>
          <div class="column is-3">
            <VField label="Tanggal">
              <VDatePicker v-model="item.tanggal" mode="dateTime" style="width: 100%">
                <template #default="{ inputValue, inputEvents }">
                  <VField>
                    <VControl icon="feather:calendar" fullwidth>
                      <VInput :value="inputValue" placeholder="Tanggal" v-on="inputEvents" class="is-rounded" />
                    </VControl>
                  </VField>
                </template>
              </VDatePicker>
            </VField>
          </div>
          <div class="column is-3">
            <VField class="is-rounded-select is-autocomplete-select">
              <VLabel class="required-field">Dokter Baca</VLabel>
              <VControl icon="feather:search" fullwidth class="prime-auto-select">
                <AutoComplete v-model="item.dokterbaca" :suggestions="d_Pegawai" @complete="fetchPegawai($event)"
                  :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                  :field="'label'" placeholder="Dokter Baca" />
              </VControl>
            </VField>
          </div>
          <div class="column is-3">
            <VField class="is-rounded-select is-autocomplete-select">
              <VLabel class="required-field">Dokter Pemeriksa</VLabel>
              <VControl icon="feather:search" fullwidth class="prime-auto-select">
                <AutoComplete v-model="item.dokterPemeriksafk" :suggestions="d_Dokter" @complete="fetchDokter($event)"
                  :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                  :field="'label'" placeholder="Dokter Pemeriksaan" />
              </VControl>
            </VField>
          </div>
          <div class="column is-6">
            <span class="required-field">Hasil</span>
            <div class="columns  is-multiline p-0 mt-1">
              <div class="column is-3" v-for="items in d_Hasil" :key="items.value">
                <VField style="padding:0px;">
                  <VControl raw subcontrol>
                    <VCheckbox v-model="item.hasil" class="pt-1 pb-1 " :true-value="items.value" :label="items.label"
                      color="primary" square />
                  </VControl>
                </VField>
              </div>
            </div>
          </div>
          <div class="column is-12">
            <table class="tg">
              <thead>
                <tr>
                  <td class="tg-0lax">Nomer Kantong </td>
                  <td class="tg-0lax">Jenis Darah </td>
                  <td class="tg-0lax">Golongan Darah</td>
                  <td class="tg-0lax">Tanggal</td>
                  <td class="tg-0lax">Volume</td>
                  <td class="tg-0lax">Petugas</td>
                  <td class="tg-0lax">Nama Yang Mengabil</td>
                  <td class="tg-0lax">#</td>
                </tr>
              </thead>
              <tbody v-for="(data, index) in  item.details" :key="index">
                <tr>
                  <td style="width:15%">
                    <VField>
                      <VControl class="prime-auto">
                        <VInput v-model="data.nomerkantong"></VInput>
                      </VControl>
                    </VField>
                  </td>
                  <td style="width:15%">
                    <VField>
                      <VControl class="prime-auto">
                        <VInput v-model="data.jenisdarah"></VInput>
                      </VControl>
                    </VField>
                  </td>
                  <td style="width:15%">
                    <VField>
                      <VControl class="prime-auto">
                        <VInput v-model="data.golongandarah"></VInput>
                      </VControl>
                    </VField>
                  </td>
                  <td style="width:15%">
                    <VField>
                      <VControl class="prime-auto">
                        <VDatePicker v-model="data.tanggal" mode="dateTime" style="width: 100%;">
                          <template #default="{ inputValue, inputEvents }">
                            <VField>
                              <VControl icon="feather:calendar" fullwidth>
                                <VInput :value="inputValue" placeholder="Tanggal" v-on="inputEvents" />
                              </VControl>
                            </VField>
                          </template>
                        </VDatePicker>
                      </VControl>
                    </VField>
                  </td>
                  <td style="width:15%">
                    <VField>
                      <VControl class="prime-auto">
                        <VInput v-model="data.volume"></VInput>
                      </VControl>
                    </VField>
                  </td>
                  <td style="width:15%">
                    <VField>
                      <VControl class="prime-auto">
                        <AutoComplete v-model="data.pegawaifk" :suggestions="d_Dokter" @complete="fetchDokter($event)"
                          :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                          :loadingIcon="'pi pi-spinner'" :field="'label'" />
                      </VControl>
                    </VField>
                  </td>
                  <td style="width:15%">
                    <VField>
                      <VControl class="prime-auto">
                        <VInput v-model="data.pengambil"></VInput>
                      </VControl>
                    </VField>
                  </td>
                  <td style="width:15%">
                    <div class="columns is-multiline">
                      <div class="column is-6">
                        <VIconButton type="button" raised circle icon="feather:plus" @click="addNewItem()" color="info"
                          v-tooltip.bubble="'Tambah '">
                        </VIconButton>
                      </div>
                      <div class="column is-6 ml-3-min">
                        <VIconButton v-if="index > 0" type="button" raised circle icon="feather:trash"
                          @click="removeItem(index)" color="danger">
                        </VIconButton>
                      </div>
                    </div>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
          <div class="column is-12">
            <VField>
              <VControl>
                <VTextarea class="textarea is-rounded" v-model="item.keterangan" rows="4" placeholder="Catatan"
                  autocomplete="off" autocapitalize="off" spellcheck="true" />
              </VControl>
            </VField>
          </div>
        </div>
      </form>
    </template>
    <template #action>
      <VButton type="button" rounded outlined color="info" raised icon="feather:printer" @click="cetakPersetujuan()"
        v-if="norecHasil != ''">
        Cetak
      </VButton>
      <VButton icon="feather:save" @click="saveHasilLab(item)" :loading="isLoadingPop" color="primary" raised>Simpan
      </VButton>
    </template>
  </VModal>
  <VModal :open="modalSuratPersetujuan" title="Masukan Hasil" :noclose="false" size="medium" actions="right"
    @close="modalSuratPersetujuan = false, clear()">
    <template #content>
      <div class="column is-12">
        <VField label="Dokter" class="is-rounded-select is-autocomplete-select">
          <VControl icon="fa:user-md" class="prime-auto-cus">
            <AutoComplete v-model="item.dokterPersetujuan" :suggestions="d_Dokter" :optionLabel="'label'"
              @complete="fetchDokter($event)" :dropdown="true" :minLength="3" :appendTo="'body'"
              :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="Dokter..." class="mt-2 is-rounded" />
          </VControl>
        </VField>
        <VField label="Spesialis">
          <VControl class="prime-auto-cus">
            <VInput class="is-rounded" v-model="item.spesialis"></VInput>
          </VControl>
        </VField>
      </div>
      <div class="column is-12">
        <VField label="Hasil Pemeriksaan">
          <div class="columns  is-multiline p-0">
            <div class="column is-6">
              <VField addons>
                <VControl expanded>
                  <VInput type="text" class="input" placeholder="My" v-model="item.my" :tabindex="1" />
                </VControl>
                <VControl class="field-addon-body">
                  <VButton static>My</VButton>
                </VControl>
              </VField>
            </div>
            <div class="column is-6">
              <VField addons>
                <VControl expanded>
                  <VInput type="text" class="input" placeholder="Mn" v-model="item.mn" :tabindex="1" />
                </VControl>
                <VControl class="field-addon-body">
                  <VButton static>Mn</VButton>
                </VControl>
              </VField>
            </div>
            <div class="column is-6">
              <VField addons>
                <VControl expanded>
                  <VInput type="text" class="input" placeholder="Ac" v-model="item.ac" :tabindex="1" />
                </VControl>
                <VControl class="field-addon-body">
                  <VButton static>Ac</VButton>
                </VControl>
              </VField>
            </div>
            <div class="column is-6">
              <VField addons>
                <VControl expanded>
                  <VInput type="text" class="input" placeholder="Dct" v-model="item.dtc" :tabindex="1" />
                </VControl>
                <VControl class="field-addon-body">
                  <VButton static>Dct</VButton>
                </VControl>
              </VField>
            </div>
          </div>
        </VField>
      </div>
      <div class="column is-12">
        <VField label="Skring AB">
          <div class="columns  is-multiline p-0">
            <div class="column is-6">
              <VField addons>
                <VControl expanded>
                  <VInput type="text" class="input" placeholder="S1" v-model="item.s1" :tabindex="1" />
                </VControl>
                <VControl class="field-addon-body">
                  <VButton static>S1</VButton>
                </VControl>
              </VField>
            </div>
            <div class="column is-6">
              <VField addons>
                <VControl expanded>
                  <VInput type="text" class="input" placeholder="S2" v-model="item.s2" :tabindex="1" />
                </VControl>
                <VControl class="field-addon-body">
                  <VButton static>S2</VButton>
                </VControl>
              </VField>
            </div>
          </div>
        </VField>
      </div>
    </template>
    <template #action>
      <VButton icon="feather:printer" @click="cetakSuratPernyataan()" color="purple" raised>
        Cetak
      </VButton>
    </template>
  </VModal>
</template>
<script setup lang="ts">
import ApexChart from 'vue3-apexcharts'
import { useRoute, useRouter } from 'vue-router'
import { useApi } from '/@src/composable/useApi'
import { ref, computed, watch, reactive } from 'vue'
import { useThemeColors } from '/@src/composable/useThemeColors'
import { useHead } from '@vueuse/head'
import { useUserSession } from '/@src/stores/userSession'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import moment, { isDate } from 'moment'
import { useToaster } from '/@src/composable/toaster'
import Fieldset from 'primevue/fieldset'
import AutoComplete from 'primevue/autocomplete';
import Dropdown from 'primevue/dropdown'
import * as H from '/@src/utils/appHelper'
import ConfirmDialog from 'primevue/confirmdialog'
import { useConfirm } from "primevue/useconfirm"
import FloatingButton from "../emr/float-tambah.vue"
import Badge from 'primevue/badge';
import * as qzService from '/@src/utils/qzTrayService'
import SplitButton from 'primevue/splitbutton';

useHead({
  title: 'Transaksi Pelayanan Bank Darah - ' + import.meta.env.VITE_PROJECT,
})
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)

let NOREC_PD = useRoute().query.norec_pasien_daftar as string
let NOREC_APD = useRoute().query.norec_apd as string
let ID_PASIEN = useRoute().query.nocmfk as string
let NOREGISTRASI = useRoute().query.noregistrasi as string
const isLoadingBill: any = ref(false)
const isLoadingPop: any = ref(false)
const filtersHide = ref('')
const isLoadingPasien: any = ref(false)
const modalKirimLIS: any = ref(false)
const pasien: any = ref({})
const filters: any = ref('')
const noreLab: any = ref('')
const dataSource: any = ref([])
const listChecked: any = ref([])
const LISTRUANGAN_APD: any = ref([])
const LISTRUANGAN_APD_G: any = ref([])
const modelCheck: any = ref([])
const modalHasil: any = ref(false)
const modalSuratPersetujuan: any = ref(false)
const disabledHasil = ref(false)
const d_Dokter: any = ref([])
const d_Pegawai: any = ref([])
const item: any = reactive({
  NOREC_PD: NOREC_PD != undefined ? NOREC_PD : '',
  NOREC_APD: NOREC_APD != undefined ? NOREC_APD : '',
  registrasi: {},
  tglorder: new Date(),
  tanggal: new Date(),
  tglTerimaSample: new Date(),
  tglSelesaiSample: new Date(),
  produkCeklis: [],
  TOTAL: 0,
  DIBAYAR: 0,
  SISA: 0,
  DEPOSIT: 0,
  DISKON: 0,
  length: 0,
  pegawaiOrder: useUserSession().getUser().id,
  diskonKomponen: 0,
  namaKomponen: '',
  totalKomponen: 0,
  details: [{
    no: 1,
    tglorder: new Date(),
  }]
})
const listButton: any = ref([
  // {
  //   label: 'Cetak Surat Pernyataan ',
  //   icon: 'fas fa-print',
  //   command: () => {
  //     H.printBlade('bank-darah/cetak-surat-pernyataan?noregistrasi=' + item.registrasi.noregistrasi
  //       + '&norec_pd=' + item.NOREC_PD);
  //   }
  // },
  {
    label: 'Cetak Surat Persetujuan ',
    icon: 'fas fa-print',
    command: () => {
      H.printBlade('bank-darah/cetak-surat-persetujuan?noregistrasi=' + item.registrasi.noregistrasi
        + '&norec_pd=' + item.NOREC_PD);
    }
  }

])
const d_Hasil: any = ref([
  {
    label: 'COCOK', value: 'COCOK',
  },
  {
    label: 'TIDAK COCOK', value: 'TIDAK COCOK',
  },
  {
    label: 'EMERGENCY*', value: 'EMERGENCY',
  },
  {
    label: 'TANPA CROSS', value: 'TANPA CROSS',
  }
])
const norecHasil: any = ref('');
const hasilLab = (e: any) => {
  modalHasil.value = true
  listPegawai()
  item.norec_pp = e.norec
  item.norec_so = e.norec_so
  item.noregistrasifk = e.norec_apd
  item.dokterpemeriksa = e.dokterpemeriksa
  item.pegawaifk = { label: H.pegawaiLogin().namalengkap, value: H.pegawaiLogin().id }
  item.namaproduk = e.namaproduk
  item.pelayananpasienfk = e.norec_pp
  item.noregistrasifk = e.norec_apd
  disabledHasil.value = false
  useApi().get(
    `/bank-darah/hasil-darah?norec=${item.norec_pp}`).then((response: any) => {
      if (response != null) {
        noreLab.value = response.norec
        item.norec = response.norec
        item.keterangan = response.keterangan
        item.tanggal = new Date(response.tanggalreport)
        item.dokterbaca = response.pegawai && { label: response.pegawai, value: response.pegawaifk };
        item.dokterPemeriksafk = response.dokter && { label: response.dokter, value: response.dokterfk }
        item.hasil = response.hasil
        response.details.forEach((element: any, index: number) => {
          element.pegawaifk = { label: element.namalengkap, value: 123 };
        });
        item.details = response.details
      }
    })
}
const cetakPersetujuan = () => {
  H.printBlade('bank-darah/cetak-surat-persetujuan?noregistrasi=' + item.registrasi.noregistrasi
    + '&norec_pd=' + item.NOREC_PD)
}
const confirm = useConfirm();
const router = useRouter()
const listPegawai = async () => {
  await useApi().get(
    `/dashboard/radiologi/get-dokter`).then((response: any) => {
      d_Dokter.value = response.data.map((e: any) => {
        return { label: e.namalengkap, value: e.id }
      })
    })
}
const fetchPegawai = async (filter: any) => {
  await useApi().get(
    `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`
  ).then((response) => {
    d_Pegawai.value = response
  })
}

const fetchDokter = async (filter: any) => {
  await useApi().get(
    `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&settingdatafix=objectjenispegawaifk,idJenisPegawaiDokter&limit=10`
  ).then((response) => {
    d_Dokter.value = response
  })
}
const headerPasien = async (id: any) => {
  isLoadingPasien.value = true
  useApi()
    .get(`/dashboard/headerpasien?nocmfk=${id}&norec_pd=${item.NOREC_PD}&norec_apd=${item.NOREC_APD}`)
    .then((response: any) => {
      pasien.value = response.pasien
      item.NOREC_APD = response.last_registrasi.norec_apd
      item.RUANGAN_LAST = response.last_registrasi.objectruanganlastfk
      item.KELAS_LAST = response.last_registrasi.objectkelasfk
      item.registrasi = response.last_registrasi
      item.noregistrasi = response.registrasi[0].noregistrasi
      item.namaruangan = response.last_registrasi.namaruangan
      item.tglmasuk = response.last_registrasi.tglmasuk
      item.tglpulang = response.last_registrasi.tglpulang
      isLoadingPasien.value = false
      fetchLayanan()
    })
}
const fetchLayanan = async () => {
  isLoadingBill.value = true
  dataSource.value = []
  await useApi().get(
    `/bank-darah/layanan-bank-darah?norec_pd=${NOREC_PD}`).then(async (response: any) => {

      dataSource.value = response.detail
      item.TOTAL = response.total
      item.PENGEMBALIAN = response.pengembalian
      item.length = response.length
      LISTRUANGAN_APD.value = response.list_ruangan
      LISTRUANGAN_APD_G.value = groupRuang(response.list_ruangan)
      isLoadingBill.value = false

    })
  modalKirimLIS.value = false
}
const isDetail: any = ref(false)
const dataSourcefiltered: any = computed(() => {

  if (!filters.value && !filtersHide.value) {
    return dataSource.value
  }
  var filtered: any = [];
  for (let x = 0; x < dataSource.value.length; x++) {
    const element = dataSource.value[x];
    var filteredD = [];
    for (let z = 0; z < element.details.length; z++) {
      const element2 = element.details[z];
      if (filters.value) {
        if (element2.namaproduk.match(new RegExp(filters.value, 'i'))
          || element2.namaruangan.match(new RegExp(filters.value, 'i'))
          || element2.dokterpemeriksa.match(new RegExp(filters.value, 'i'))

        ) {
          filteredD.push(element2);
          filtered.push({
            tglpelayanan_group: element.tglpelayanan_group,
            details: filteredD
          })
          break
        }
      } else if (filtersHide.value) {
        if (element2.jenis.match(new RegExp(filtersHide.value, 'i'))
        ) {
          for (let xxx = 0; xxx < filtered.length; xxx++) {
            const elementxxx = filtered[xxx];
            if (elementxxx.tglpelayanan_group == element.tglpelayanan_group) {
              filtered.splice(xxx, 1)
            }
          }
          filteredD.push(element2);
          filtered.push({
            tglpelayanan_group: element.tglpelayanan_group,
            details: filteredD
          })
          // break
        }
      }

    }
  }
  return filtered;
})
function groupRuang(result: any) {
  let sama = false
  let arrGroup: any = [];
  for (let i = 0; i < result.length; i++) {
    sama = false
    for (let x = 0; x < arrGroup.length; x++) {
      if (arrGroup[x].namadepartemen == result[i].namadepartemen) {
        sama = true;
      }
    }
    if (sama == false) {
      let data = {
        'namadepartemen': result[i].namadepartemen,
        'details': [],
      }
      arrGroup.push(data)
    }
  }
  for (let x = 0; x < arrGroup.length; x++) {
    const element = arrGroup[x];
    for (let y = 0; y < result.length; y++) {
      const element2 = result[y];
      if (element.namadepartemen == element2.namadepartemen) {
        element.details.push(element2)
      }
    }

  }
  return arrGroup
}
function checkedAll(e: any) {
  modelCheck.value = []
  listChecked.value = []
  if (e) {
    dataSource.value.forEach((e: any) => {
      e.details.forEach((f: any) => {
        listChecked.value.push(f)
        modelCheck.value[f.norec] = true
      });
    });
  }
}
function checkedItems() {
  let objectK = Object.keys(modelCheck.value)

  for (let x = 0; x < objectK.length; x++) {
    const element = objectK[x];
    if (modelCheck.value[element] == true) {
      for (var i = 0; i < dataSource.value.length; i++) {
        const element2 = dataSource.value[i];
        for (let xx = 0; xx < element2.details.length; xx++) {
          const element3 = element2.details[xx];
          if (element3.norec == element) {
            for (var z = 0; z < listChecked.value.length; z++) {
              const element4 = listChecked.value[z];
              if (element4.norec == element3.norec) {
                listChecked.value.splice(z, 1)
              }
            }
            listChecked.value.push(element3)
          }
        }

      }
    } else {
      for (var i = 0; i < dataSource.value.length; i++) {
        const element2 = dataSource.value[i];
        for (let xx = 0; xx < element2.details.length; xx++) {
          const element3 = element2.details[xx];
          if (element3.norec == element) {
            for (var z = 0; z < listChecked.value.length; z++) {
              const element4 = listChecked.value[z];
              if (element4.norec == element3.norec) {
                listChecked.value.splice(z, 1)
              }
            }
          }
        }
      }
    }
  }
}
const inputTindakan = (e: any) => {
  router.push({
    name: 'module-emr-tindakan',
    query: {
      norec_pasien_daftar: NOREC_PD,
      nocmfk: pasien.value.nocmfk,
      norec_apd: NOREC_APD
    },
  })
}
const hapusItems = async (e: any) => {
  confirm.require({
    group: 'positionDialog',
    message: H.alertHapus(),
    header: 'Info ',
    icon: 'pi pi-info-circle',
    acceptClass: 'p-button-danger',
    position: 'top',
    accept: () => {
      var objSave = {
        'data':
          [{
            'noorder': e.noorder,
            'norec_pp': e.norec,
            'namaproduk': e.namaproduk,
            'namaruangan': e.namaruangan,
          }],
        'nocm': pasien.value.nocm,
        'namapasien': pasien.value.namapasien,
        'noregistrasi': pasien.value.noregistrasi,
      }
      nextHapus(objSave)
    },
    reject: () => {
    }
  });

}
const nextHapus = async (objSave: any) => {
  isLoadingBill.value = true
  useApi().post(
    `/laboratorium/hapus-tindakan-lab`, objSave).then((response: any) => {
      isLoadingBill.value = false
      fetchLayanan()
    }).catch((e: any) => {
      isLoadingBill.value = false
    })
}
const addNewItem = () => {
  item.details.push({
    no: item.details[item.details.length - 1].no + 1,
    tglorder: new Date(),
  });

}
const removeItem = (index: any) => {
  item.details.splice(index, 1)
}
const saveHasilLab = async (e: any) => {
  if (!item.pegawaifk) {
    H.alert('warning', 'Dokter Harus Diisi !');
    return;
  }
  if (!item.dokterbaca) {
    H.alert('warning', 'Dokter Baca Harus Diisi !');
    return;
  }
  if (!item.hasil) {
    H.alert('warning', 'Hasil Harus Diisi !');
    return;
  }
  let objSave = {
    norec: e.norec ?? '',
    norec_so: e.norec_so,
    keterangan: item.keterangan,
    pelayananpasienfk: item.norec_pp,
    noregistrasifk: item.noregistrasifk,
    pegawaifk: item.pegawaifk.value,
    dokterfk: item.dokterbaca.value,
    hasil: item.hasil,
    details: item.details
  }
  isLoadingPop.value = true
  await useApi().post(
    `/bank-darah/save-hasil`, objSave).then((response: any) => {
      noreLab.value = response.data.norec
      isLoadingPop.value = false
    })
  modalHasil.value = false
  clear()
}
headerPasien(ID_PASIEN)
const clear = () => {
  delete item.dokterbaca;
  delete item.dokterPemeriksafk;
  delete item.hasil;
  delete item.keterangan;
  delete item.details;
}
const suratPersetujuan = () => {
  modalSuratPersetujuan.value = true;
}
const cetakSuratPernyataan = () => {
  H.printBlade(`bank-darah/cetak-surat-persetujuan?` +
    `noregistrasi=${item.registrasi.noregistrasi}` +
    `&norec_pd=${item.NOREC_PD}` +
    `&s1=${item.s1}` +
    `&s2=${item.s2}` +
    `&my=${item.my}` +
    `&mn=${item.mn}` +
    `&ac=${item.ac}` +
    `&dtc=${item.dtc}` +
    `&spesialis=${item.spesialis}` +
    `&nama_dokter=${item.dokterPersetujuan ? item.dokterPersetujuan.label : ''}`
  );
  modalSuratPersetujuan.value = false;
}
const cetakSuratPersetujuan = () => {
  H.printBlade('bank-darah/cetak-surat-pernyataan?noregistrasi=' + item.registrasi.noregistrasi
    + '&norec_pd=' + item.NOREC_PD);
}
</script>

<style lang="scss">
@import '/@src/scss/abstracts/all';
@import '/@src/scss/custom/config';
@import '/@src/scss/module/dashboard/bedah.scss';
@import '/@src/scss/module/dashboard/laboratorium.scss';

.slider-lab {
  .tabs-inner {
    margin-right: unset !important;
  }
}

.control.has-icon.prime-auto-cus .form-icon {
  top: 6px;
}

.tg {
  border-collapse: collapse;
  border-spacing: 0;
  width: 100%;
}

.tg td {
  border-color: var(--fade-grey-dark-2);
  border-style: solid;
  border-width: 1px;
  font-family: Arial, sans-serif;
  font-size: 14px;
  overflow: hidden;
  padding: 10px 5px;
  word-break: normal;
}

.tg th {
  border-color: var(--fade-grey-dark-3);
  border-style: solid;
  border-width: 1px;
  font-family: Arial, sans-serif;
  font-size: 14px;
  font-weight: normal;
  overflow: hidden;
  padding: 10px 5px;
  word-break: normal;
}

.tg .tg-0lax {
  text-align: left;
  vertical-align: top
}
</style>
