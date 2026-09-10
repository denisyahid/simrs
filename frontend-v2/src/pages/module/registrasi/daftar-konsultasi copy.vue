
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
                      :loadingIcon="'pi pi-spinner'" :field="'label'"  placeholder="Dokter" />
                  </VControl>
                </VField>
              </div>
              <div class="column is-3 pt-0 pb-0">
                <span>Ruangan</span>
                <VField class="is-autocomplete-select pt-2" v-slot="{ id }">
                  <VControl icon="feather:search">
                    <AutoComplete v-model="item.ruangan" :suggestions="d_Ruangan" @complete="fetchRuangan($event)"
                      :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                      :loadingIcon="'pi pi-spinner'" :field="'label'"  placeholder="Ruangan"/>
                  </VControl>
                </VField>
              </div>
            </div>
          </div>
          <div class="field">
            <div class="control">
              <div class="columns is-multiline">
                <div class="column " :class="[kelompokUser == 'dokter' ? 'is-8':'is-11']">
                  <input type="text" v-model="item.namaPasien" v-on:keyup.enter="cari()" class="input"
                    placeholder="Search..." />
                </div>
                <VControl v-if="kelompokUser == 'dokter'">
                  <VSwitchBlock v-model="item.isdokter" label="Konsul ke saya" color="danger" />
                </VControl>
                <div class="column" style="margin-left: auto:  !important;">
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
            <i class="fas fa-check-circle mr-2" aria-hidden="true"></i>
            <span>Konsultasi</span>
            <Badge :value="dataSource.length" v-if="dataSource.length > 0" severity="danger" class="ml-2" />
          </template>

          <div class="columns is-multiline">
            <div class="column">
              <div class="list-view list-view-v3" v-if="dataSource.length == 0">
                <VPlaceholderPage title="Tidak Ada Data." subtitle="Silakan Pilih Tanggal dan Ruangan untuk melihat data"
                  larger>
                  <template #image>
                    <img class="light-image" src="/@src/assets/illustrations/placeholders/search-4.png" alt="" />
                    <img class="dark-image" src="/@src/assets/illustrations/placeholders/search-4-dark.svg" alt="" />
                  </template>
                </VPlaceholderPage>
              </div>
              <div class="timeline-wrapper" v-else>

                <div class="timeline-header"></div>
                <div class="timeline-wrapper-inner">
                  <div class="timeline-container">
                    <div class="timeline-item is-unread" v-for="(data) in dataSource" :key="data.norec">
                      <div class="date">
                        <span>{{ H.formatDateIndoSimple(data.tglregistrasi) }}</span>
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
                            <VTag class="ml-4" :color="'info'" rounded>{{ data.nocm }}</VTag>
                            <div class="columns is-multiline pt-4">
                              <div class="column is-6 pt-0 pb-0 ">
                                <span>Ruangan Asal :</span> <span style="font-weight: 500;color: #646161;">{{
                                  data.ruanganasal
                                  !=
                                  null ? data.ruanganasal : '-' }}</span>
                              </div>
                              <div class="column is-6 pt-0 pb-0">
                                <span>Ruangan Tujuan :</span> <span style="font-weight: 500;color: #646161;">{{
                                  data.namaruangan }}</span>
                              </div>
                            </div>
                            <div class="columns is-multiline">
                              <div class="column is-6 pt-0 pb-0 ">
                                <span>Tipe Pasien :</span> <span style="font-weight: 500;color: #646161;">{{
                                  data.kelompokpasien
                                }}</span>
                              </div>
                              <div class="column is-6 pt-0 pb-0">
                                <span>Dokter :</span> <span style="font-weight: 500;color: #646161;">{{
                                  data.namadokter }}</span>
                              </div>
                            </div>
                          </div>
                          <div class="box-end">
                            <VDropdown icon="feather:more-vertical" spaced right>
                              <template #content>
                                <a role="menuitem" @click="rincianTagihan(data)" class="dropdown-item is-media">
                                  <div class="icon">
                                    <i aria-hidden="true" class="fa fa-th-list"></i>
                                  </div>
                                  <div class="meta">
                                    <span>Rincian Tagihan</span>
                                  </div>
                                </a>
                                <a role="menuitem" @click="showModalDokter(data)"
                                  class="dropdown-item is-media">
                                  <div class="icon">
                                    <i aria-hidden="true" class="fa fa-user"></i>
                                  </div>
                                  <div class="meta">
                                    <span>Pilih Dokter</span>
                                  </div>
                                </a>
                              </template>
                            </VDropdown>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <!-- <DataTable :value="dataSource" paginator :rows="5" :rowsPerPageOptions="[5, 10, 20, 50]" showGridlines
                tableStyle="min-width: 50rem">
                <Column field="namapasien" header="Name" style="width: 25%"></Column>
                <Column field="kelompokpasien" header="Kelompok Pasien" style="width: 25%"></Column>
                <Column field="namarekanan" header="Nama Rekanan" style="width: 25%"></Column>
                <Column field="noregistrasi" header="Noregistrasi" style="width: 25%"></Column>
                <Column field="namadokter" header="Dokter" style="width: 25%"></Column>
                <Column field="statuspasien" header="status pasien" style="width: 25%"></Column>
                <Column header="Action" style="width: 25%">
                  <template #body="slotProps">
                    <VDropdown icon="feather:more-vertical" spaced right>
                      <template #content>
                        <a role="menuitem" @click="rincianTagihan(slotProps.data)" class="dropdown-item is-media">
                          <div class="icon">
                            <i aria-hidden="true" class="fa fa-th-list"></i>
                          </div>
                          <div class="meta">
                            <span>Rincian Tagihan</span>
                          </div>
                        </a>
                        <a role="menuitem" @click="showModalDokter(slotProps.data)" class="dropdown-item is-media">
                          <div class="icon">
                            <i aria-hidden="true" class="fa fa-user"></i>
                          </div>
                          <div class="meta">
                            <span>Pilih Dokter</span>
                          </div>
                        </a>
                      </template>
                    </VDropdown>
                  </template>
                </Column>
              </DataTable> -->
            </div>
          </div>
        </TabPanel>
        <TabPanel>
          <template #header>
            <i class="fas fa-users mr-2" aria-hidden="true"></i>
            <span>Daftar Order</span>
            <Badge :value="sourceOrder.length" v-if="sourceOrder.length > 0" severity="danger" class="ml-2" />


          </template>
          <div class="columns is-multiline">
            <div class="column">
              <div class="list-view list-view-v3" v-if="sourceOrder.length == 0">
                <VPlaceholderPage title="Tidak Ada Data." subtitle="Silakan Pilih Tanggal dan Ruangan untuk melihat data"
                  larger>
                  <template #image>
                    <img class="light-image" src="/@src/assets/illustrations/placeholders/search-4.png" alt="" />
                    <img class="dark-image" src="/@src/assets/illustrations/placeholders/search-4-dark.svg" alt="" />
                  </template>
                </VPlaceholderPage>
              </div>
              <div class="timeline-wrapper" v-else>
                <VControl>
                  <VSwitchBlock v-model="item.isnotverif" label="Pending" color="danger" @change="fetchOrder"/>
                </VControl>
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
                            <VTag class="ml-4" :color="data.norec_apd != null ?'success':'warning'" rounded>{{ data.norec_apd != null ? 'verifikasi':'pending' }}</VTag>
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
                            <VIconButton color="primary" outlined circle icon="fas fa-check" v-tooltip.top="'Verify'" v-if="data.norec_apd == null"
                              :loading="data.isLoading" @click="verifyOrder(data)" />
                          </div>
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


    </VCard>
  </div>

  <VModal :open="modalUpdateDokter" title="Edit Dokter" actions="right" :noclose="true" size="small"
    @close="modalUpdateDokter = false">
    <template #content>
      <div class="column">
        <span class="label-ptj">Dokter</span>
        <VField class="mt-3">
          <VControl class="prime-auto">
            <AutoComplete v-model="item.dokterfk" :suggestions="d_Dokter" @complete="fetchDokter($event)"
              :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
              :field="'label'" placeholder="Cari Dokter..." class="mt-2" />
          </VControl>
        </VField>
      </div>
    </template>
    <template #action>
      <VButton @click="updateDokter(item.dokterfk)" :loading="isLoadingBtn" color="primary" raised>
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
import { useUserSession } from '/@src/stores/userSession'
import ConfirmDialog from 'primevue/confirmdialog'
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
  isnotverif: true
})
const confirm = useConfirm();

let listKelompokPasien: any = ref([])

let ds_PASIEN: any = ref([])
let listColor: any = ref(Object.keys(useThemeColors()))
const kelompokUser = useUserSession().getUser().kelompokUser.kelompokUser
if(kelompokUser == 'dokter'){
  item.value.isdokter = true
}
const activeTab = ref(0)
const router = useRouter()
const route = useRoute()
const { y } = useWindowScroll()
const d_Ruangan = ref([])
const sourceOrder = ref([])
const dataSource = ref([])
const modalUpdateDokter = ref(false)
const d_Dokter = ref([])
const isLoadingBtn = ref(false)
const modalChangeDate = ref(false)
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

const fetchData = async () => {
  let tglAwal = `?tglAwal=${moment(item.value.periode.start).format('YYYY-MM-DD')}`
  let tglAkhir = `&tglAkhir=${moment(item.value.periode.end).format('YYYY-MM-DD')}`
  let ruangan = item.value.ruangan ? `&ruanganfk=${item.value.ruangan.value}` : ''
  let dokter = item.value.filterdokterfk ? `&dokterfk=${item.value.filterdokterfk.value}` : ''
  if(item.value.isdokter == true){
    dokter =  `&dokterfk=${useUserSession().getUser().pegawai.id}`
  }
 
  await useApi().get(`registrasi/get-daftar-konsultasi${tglAwal}${tglAkhir}${ruangan}${dokter}`).then((response) => {
    dataSource.value = response
  })
}

const fetchOrder = async () => {
  ds_PASIEN.value.loading = true
  let tglAwal = `?tglAwal=${moment(item.value.periode.start).format('YYYY-MM-DD')}`
  let tglAkhir = `&tglAkhir=${moment(item.value.periode.end).format('YYYY-MM-DD')}`
  let ruangan = item.value.ruangan ? `&ruanganfk=${item.value.ruangan.value}` : ''
  let dokter = item.value.filterdokterfk ? `&dokterfk=${item.value.filterdokterfk.value}` : ''
  let nama = item.value.nama ? `&namapasienpm=${item.value.namapasien}` : ''
  let isnotverif = item.value.isnotverif ? `&isnotverif=${item.value.isnotverif}` : ''
  if(item.value.isdokter == true){
    dokter =  `&dokterfk=${useUserSession().getUser().pegawai.id}`
  }
  await useApi().get(`emr/get-order-konsul${tglAwal}${tglAkhir}${ruangan}${dokter}${isnotverif}`).then((response) => {
    sourceOrder.value = response.data
  })
  // await useApi().get(`registrasi/daftar-pasien-penunjang${tglAwal}${tglAkhir}${ruangan}${status}${kodeResevasi}${nama}`).then((response) => {
  //   response.data.forEach((element:any) => {
  //     let ini = element.namapasien.split(' ')
  //     let init = element.namapasien.substr(0, 1)
  //     if (ini.length > 1) {
  //       init = init + ini[1].substr(0, 1)
  //     }
  //     element.initials = init
  //   });
  //   ds_PASIEN.value = response
  //   console.log(ds_PASIEN.value.data.length)
  //   ds_PASIEN.value.loading = false
  // })
}


const verifyOrder = async (e: any) => {
  e.isLoading = true
  let objSave = {
    "kelasfk": e.kelasfk_pd,
    "norec_so": e.norec,
    "norec_pd": e.norec_pd,
    "dokterfk": e.pegawaifk,
    "objectruangantujuanfk": e.objectruangantujuanfk,
    "objectruanganasalfk": e.objectruanganfk
  }
  await useApi().post('registrasi/get-save-konsul-order', objSave).then((response: any) => {
    fetchOrder()
    e.isLoading = false
  }).catch((e: any) => {
    e.isLoading = false
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
  await useApi().get(
    `emr/dropdown/ruangan_m?select=id,namaruangan&param_search=namaruangan&query=${filter.query}&limit=10`
  ).then((response) => {
    d_Ruangan.value = response
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
  await useApi().get(
    `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&settingdatafix=objectjenispegawaifk,idJenisPegawaiDokter&limit=10`
  ).then((response) => {
    d_Dokter.value = response
  })
}

const klikTab = (e: any) => {
  activeTab.value = e.index
  if (activeTab.value == 0) {
    fetchData()
  }
  if (activeTab.value == 1) {
    fetchOrder()
  }
}
const cari = () => {
  if (activeTab.value == 0) {
    fetchData()
  }
  if (activeTab.value == 1) {
    fetchOrder()
  }
}
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
