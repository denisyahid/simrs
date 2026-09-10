<template>
    <ConfirmDialog />
    <div class="form-layout is-stacked">
      <div class="form-outer" style="margin-top: 15px">
        <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
          <div class="form-header-inner">
            <div class="left">
              <h3>Transaksi Pelayanan</h3>
            </div>
            <div class="right">
              <div class="buttons">
                <VButton icon="lnir lnir-arrow-left rem-100" light dark-outlined @click="backPage()">
                  Kembali
                </VButton>
              </div>
            </div>
          </div>
        </div>
  
        <div class="form-body p-2">
          <div class="business-dashboard hr-dashboard">
            <div class="columns is-multiline">
              <div class="column is-12" v-if="!isLoadingPasien">
                <div class="block-header">
                  <div class="left">
                    <div class="current-user">
                      <VAvatar size="medium" :picture="
                        pasien.jeniskelamin == 'PEREMPUAN'
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
                        <p class="block-text">{{ pasien.kelompokpasien }}</p>
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
    <div class="form-body p-2"></div>
    <VCard>
      <VButton type="button" color="info" raised rounded icon="feather:plus-circle" class="is-pulled-right mr-3 mt-0 mb-0"
      @click="inputTindakan(item)" style="margin-top: -1rem;">
        Input Tindakan
      </VButton>
  
      <div class="update-item is-dark-bordered-12 " style="display: block;" v-if="dataSourceRiwayat.length == 0">
        <div class="search-results-wrapper">
          <div class="search-results-body ">
            <!--Search Placeholder -->
            <div class="page-placeholder">
              <div class="placeholder-content">
                <img class="light-image" style=" max-width: 340px;"
                  src="/@src/assets/illustrations/placeholders/search-7.svg" alt="" />
                <img class="dark-image" style=" max-width: 340px;"
                  src="/@src/assets/illustrations/placeholders/search-7-dark.svg" alt="" />
                <h3>Data belum ada.</h3>
                <p class="is-larger">
                  Sepertinya data ini belum di inputkan,
                  silahkan melakukan penginputan terlebih
                  dahulu.
                </p>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="list-widget" :class="[dataSourceRiwayat && 'is-straight']" v-if="dataSourceRiwayat.length > 0">
        <div class="column is-12">
                          <div class="timeline-wrapper" v-if="dataSourceRiwayat.length > 0">
                              <div class="timeline-header"></div>
                              <div class="timeline-wrapper-inner pt-0">
                                  <div class="timeline-container">
                                      <div class="timeline-item is-unread " v-for="(items, index)  in dataSourceRiwayat"
                                          :key="items.norec">
  
                                          <div class="date">
                                              <span>{{ H.formatDateIndo(items.tglpelayanan) }}</span>
                                          </div>
                                          <div :class="'dot is-' + listColor[index + 1]"></div>
                                          <!-- <div class="collapse-icon is-clickable" @click="isDetail[index] = true"
                                              v-if="!isDetail[index]">
                                              <VIcon icon="feather:chevron-down" />
                                          </div>
                                          <div class="collapse-icon  is-clickable mr-1" open
                                              @click="isDetail[index] = false" v-else>
                                              <VIcon icon="feather:chevron-up" />
                                          </div> -->
                                          <div class="content-wrap is-grey">
                                              <div class="content-box is-fullwidth">
                                                  <div class="status"></div>
                                                  <VIconBox size="medium" :color="'kuning'" rounded>
                                                      <i class="fas fa-file-medical-alt" aria-hidden="true"></i>
                                                  </VIconBox>
                                                  <div class="box-text" style="width:70%">
                                                      <div class="meta-text">
  
                                                          <p>
                                                              <span>{{ items.namaproduk }}</span>
                                                          </p>
                                                          <table class="tb-order mt-1">
                                                              <tr>
                                                                  <td>Harga</td>
                                                                  <td>:</td>
                                                                  <td class="text-value">{{
                                                                      H.formatRp(items.hargasatuan,
                                                                          'Rp. ')
                                                                  }} </td>
                                                              </tr>
                                                              <tr>
                                                                  <td>Jumlah</td>
                                                                  <td>:</td>
                                                                  <td class="text-value">{{ items.jumlah }} </td>
                                                              </tr>
                                                              <tr>
                                                                  <td>Dokter</td>
                                                                  <td>:</td>
                                                                  <td class="text-value">{{ items.dokter }} </td>
                                                              </tr>
                                                              <tr v-if="items.iscito">
                                                                  <td>Cito</td>
                                                                  <td>:</td>
                                                                  <td class="text-value">{{ item.nilaiCito }}%</td>
                                                              </tr>
                                                          </table>
                                                      </div>
                                                  </div>
                                                  <div class="box-end" style="width:40%">
                                                      <div class="columns is-multiline">
                                                          <div class="column is-12 mt-3">
                                                              <VTag v-if="items.iscito" label="Cito" :color="'warning'"
                                                                  rounded class="is-pulled-right" />
                                                          </div>
                                                          <div class="column is-6 mt-3">
                                                              <b> {{ H.formatRp(items.total, 'Rp. ') }}</b>
                                                          </div>
                                                          <div class="column is-6">
                                                              <VIconButton icon="feather:user" @click="inputPetugas(items)"
                                                                  color="warning" raised circle class="mr-2" v-tooltip.bubble="'Detail Dokter'">
                                                              </VIconButton>
                                                              <VIconButton icon="feather:trash" @click="hapusItems(items)"
                                                                  color="danger" raised circle class="mr-2" v-tooltip.bubble="'Hapus'">
                                                              </VIconButton>
                                                              <VIconButton icon="feather:edit" @click="inputExpertise(items)"
                                                                  color="primary" raised circle v-tooltip.bubble="'Expertise'">
                                                              </VIconButton>
  
                                                          </div>
                                                      </div>
  
                                                  </div>
  
                                              </div>
    
                                          </div>
                              
                                      </div>
                                  </div>
                                  <div class="load-more-wrap has-text-centered p-1 mb-3">
                                      <div class="columns is-multiline">
                                          <div class="column is-4 is-offset-8">
                                              <VCard>
                                                  <div class="columns is-multiline">
                                                      <div class="column is-3 mt-1">
                                                          <VField>
                                                              <VLabel class="fs-total-label">TOTAL </VLabel>
                                                          </VField>
                                                      </div>
                                                      <div class="column is-9">
                                                          <VField>
                                                              <VLabel class="fs-total">{{ H.formatRp(item.totalAll, 'Rp. ') }}
                                                           </VLabel>
                                                          </VField>
                                                      </div>
                                                  </div>
                                              </VCard>
                                          </div>
                                      </div>
                                  </div>
                              </div>
                          </div>
  
                          <VCard radius="rounded" class="mt-2" v-if="dataSourceRiwayat.length === 0">
                              <VPlaceholderPage :title="H.assets().notFound" :subtitle="H.assets().notFoundSubtitle"
                                  larger>
                                  <template #image>
                                      <img class="light-image" :src="H.assets().iconNotFound_rev" alt="" />
                                      <img class="dark-image"
                                          src="/@src/assets/illustrations/placeholders/search-4-dark.svg" alt="" />
                                  </template>
                              </VPlaceholderPage>
                          </VCard>
                      </div>
      </div>
    </VCard>
  
    <VModal :open="modalDokter" title="Petugas Tindakan" :noclose="false" size="medium" actions="right"
      @close="modalDokter = false">
      <template #content>
        <form class="modal-form custom-mod ">
          <div class="columns is-multiline">
            <div class="column is-12">
              <VCard>
                <div class="columns is-multiline p-1">
                  <!-- <div class="column is-6">
                    <p class="block-text">Tanggal Pelayanan</p>
                    <h4 class="block-heading">{{ dataSelect.tglpelayanan }}</h4>
  
                  </div>
                  <div class="column is-6">
                    <p class="block-text">Nama Pelayanan</p>
                    <h4 class="block-heading">{{ dataSelect.namaproduk }}</h4>
  
                  </div> -->
                  <div class="column is-12">
                    <VCard class="is-grey">
                      <div class="columns is-multiline p-1">
                        <div class="column is-12 mb--15 text-center" v-if="dataSourcePetugas.length == 0">
                          <img class="light-image" style=" max-width: 180px;" :src="H.assets().iconNotFoundCalendar"
                            alt="" />
                          <h3>{{ H.assets().notFound }}</h3>
                        </div>
                        <div class="column is-12 mb--15" v-else v-for="(item, index) in dataSourcePetugas" :key="index">
                          <div class="columns is-multiline p-0">
                            <div class="column is-9">
                              <div class="file-box-2">
                                <img :src="'/images/icons/files/dokter.svg'" alt="" />
                                <div class="meta">
                                  <span>{{ item.namalengkap }} </span>
                                  <span>
                                    <b>{{ item.jenispetugaspe }}</b>
                                  </span>
                                </div>
                                <div class="is-right is-dots is-spaced dropdown end-action">
                                  <i aria-hidden="true" class="fas fa-check-circle  is-pulled-right"
                                    style="color:var(--success)"></i>
                                </div>
                              </div>
  
                            </div>
                            <div class="column is-3 mt-5">
                              <VIconButton outlined type="button" class="mr-2" raised circle icon="feather:edit"
                                @click="editPetugas(item)" color="info">
                              </VIconButton>
                              <VIconButton outlined type="button" raised circle icon="feather:trash"
                                @click="hapusPetugas(item)" color="danger">
                              </VIconButton>
                            </div>
  
                          </div>
                        </div>
  
                      </div>
                    </VCard>
                  </div>
  
  
                </div>
              </VCard>
            </div>
            <div class="column is-6">
              <VField label="Jenis Pelaksana" class="is-rounded-select  is-autocomplete-select" v-slot="{ id }">
                <VControl icon="feather:plus-circle" fullwidth>
                  <Multiselect mode="single" v-model="item.jenisPelaksana" :options="d_JenisPelaksana"
                    placeholder="Pilih data" :searchable="true" :attrs="{ id }" autocomplete="off"
                    @select="changeJenis(item.jenisPelaksana)" track-by="value" />
  
                </VControl>
              </VField>
            </div>
            <div class="column is-6">
              <VField class="is-rounded-select" v-slot="{ id }">
                <VLabel>Pegawai</VLabel>
                <VControl fullwidth>
                  <Multiselect mode="single" v-model="item.pegawai" :options="d_Pegawai" placeholder="Pilih data"
                    :searchable="true" :attrs="{ id }" autocomplete="off" track-by="value" />
  
                  <!-- <Multiselect mode="tags" :create-tag="true" placeholder="Pilih data" v-model="item.pegawai"
                    :options="d_Pegawai" :searchable="true" :attrs="{ id }" autocomplete="off" /> -->
                </VControl>
              </VField>
            </div>
          </div>
        </form>
      </template>
      <template #action>
        <VButton icon="feather:save" @click="savePetugas(item)" :loading="isLoadingPop" color="primary" raised>Simpan
        </VButton>
      </template>
    </VModal>
  
    <VModal :open="modalCatatan" title="Catatan" :noclose="false" size="medium" actions="right"
      @close="modalCatatan = false">
      <template #content>
        <div class="column is-12">
          <VField>
                <VControl>
                  <VTextarea class="textarea is-rounded" v-model="item.keterangan" rows="4"
                    placeholder="Catatan" autocomplete="off" autocapitalize="off"
                    spellcheck="true" />
                </VControl>
              </VField>
            </div>
        </template>
        <template #action>
        <VButton icon="feather:save" @click="saveCatatan(item)" :loading="isLoadingPop" color="primary" raised>Simpan
        </VButton>
      </template>
        </VModal>

      <VModal :open="modalExpertise" title="Masukan Ekspertise" :noclose="false" size="big" actions="right"
      @close="modalExpertise = false">
      <template #content>
        <form class="modal-form">
          <div class="columns is-multiline">
          <div class="column is-4">
            <VField>
              <VLabel>Nama Pelayanan</VLabel>
              <VControl icon="feather:box">
                <VInput type="text" v-model="item.namaproduk" placeholder="Nama Pelayanan" class="is-rounded" disabled />
              </VControl>
            </VField>
          </div>
          <div class="column is-4">
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
          <div class="column is-4">
            <VField>
              <VLabel>Dokter</VLabel>
              <VControl icon="feather:user">
                <VInput type="text" v-model="item.dokter" placeholder="Nama Pelayanan" class="is-rounded" disabled />
              </VControl>
            </VField>
          </div>
            <div class="column is-12">
              <VField>
                    <VControl>
                      <VTextarea class="textarea is-rounded" v-model="item.keterangan" rows="4"
                        placeholder="Catatan" autocomplete="off" autocapitalize="off"
                        spellcheck="true" />
                    </VControl>
                  </VField>
            </div>
          </div>
        </form>
    
        </template>
        <template #action>
        <VButton icon="feather:save" @click="saveCatatan(item)" :loading="isLoadingPop" color="primary" raised>Simpan
        </VButton>
      </template>
        </VModal>
    
  
  
  
  
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
  import ConfirmDialog from 'primevue/confirmdialog'
  import { useConfirm } from 'primevue/useconfirm'
  import Dropdown from 'primevue/dropdown'

  useHead({
    title: 'Transaksi Pelayanan - ' + import.meta.env.VITE_PROJECT,
  })
  useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
  useViewWrapper().setFullWidth(true)
  let ID_PASIEN = useRoute().query.nocmfk as string
  let NOREC_PD = useRoute().query.norec_pd as string
  let NOREC_APD = useRoute().query.norec_apd as string
  let pasien: any = ref({})
  const confirm = useConfirm()
  const isLoadingPasien: any = ref(false)
  const isLoading: any = ref(false)
  const isLoadingPop: any = ref(false)
  const modalDokter: any = ref(false)
  const modalCatatan: any = ref(false)
  const modalExpertise: any = ref(false)
  
  const dataSelect: any = ref({})
  const item: any = reactive({
    NOREC_PD: NOREC_PD != undefined ? NOREC_PD : '',
    NOREC_APD: NOREC_APD != undefined ? NOREC_APD : '',
    tglorder: new Date(),
    tanggal : new Date(),
    totalAll: 0
  
  })
  const dataSourceRiwayat: any = ref([])
  const d_JenisPelaksana: any = ref([])
  const d_Pegawai: any = ref([])
  const dataSourcePetugas: any = ref([])
  const router = useRouter()
  const { y } = useWindowScroll()
  const isStuck = computed(() => {
    return y.value > 30
  })
  let listColor: any = ref(Object.keys(useThemeColors()))
  const pasienByID = async (id: any) => {
    isLoadingPasien.value = true
    useApi().get(
      `/dashboard/radiologi/get-header-rad?nocmfk=${id}&norec_pd=${item.NOREC_PD}&norec_apd=${item.NOREC_APD}`).then((response: any) => {
        pasien.value = response.pasien
        item.NOREC_APD = response.last_registrasi.norec_apd
        item.RUANGAN_LAST = response.last_registrasi.objectruanganfk
        item.registrasi = response.last_registrasi
        isLoadingPasien.value = false
      })
  }
  
  const fetchDataOrder = async () => {
    dataSourceRiwayat.value = []
    dataSourceRiwayat.value.loading = true
    useApi()
      .get(`/dashboard/radiologi/list-order-pelayanan?norec_pd=${item.NOREC_PD}`)
      .then((response: any) => {
        console.log(response)
        for (let x = 0; x < response.length; x++) {
          const element = response[x]
          element.no = x + 1
        }
        item.total = response.data
        dataSourceRiwayat.value = response.data
        dataSourceRiwayat.value.loading = false
      })
      countResult()
  }
  
  const inputTindakan = (e: any) => {
    router.push({
      name: 'module-emr-tindakan',
      query: {
      nocmfk: pasien.value.nocmfk,
      norec_pasien_daftar: e.NOREC_PD,
      norec_apd : e.NOREC_APD
      },
    })
  }
  
  const hapusItems = (e: any) => {
        var objSave = {
          'data':
            [{
              'norec_pp': e.norec_pp,
              'namaruangan': e.namaruangan,
            }],
          'nocm': pasien.value.nocm,
          'namapasien': pasien.value.namapasien,
          'noregistrasi': pasien.value.noregistrasi,
        }
        nextHapus(objSave)
      }
      reject: () => {
      }
      
  const nextHapus = async (objSave: any) => {
    isLoading.value = true
    useApi().post(
      `/dashboard/delete-pp`, objSave).then((response: any) => {
        isLoading.value = false
        fetchDataOrder()
      }).catch((e: any) => {
        isLoading.value = false
      })
  }
  
  const cetakBuktiLayanan = async (norec_apd: any) => {
    H.printBlade('laboratorium/report/bukti-layanan-lab?noregistrasi=' + pasien.value.noregistrasi
          + '&norec_apd=' + norec_apd);
  }
  

  
  // input catatan
  
  const inputCatatan = async (e:any) => {
    modalCatatan.value = true
  }
  
  const saveCatatan = async (e:any) => {
    
  }
  
  // input dokter
  const inputPetugas = async (e: any) => {
    item.norec_pp = e.norec_pp
    await loadPetugas(item.norec_pp)
    await useApi().get(
      `tindakan/list-jenis-petugas`).then((response: any) => {
        d_JenisPelaksana.value = response.jenispetugaspelaksana.map((e: any) => { return { label: e.jenispetugaspe, value: e.id, default: e } })
        item.nilaiCito = response.cito != null ? parseFloat(response.cito) : 1
      })
      modalDokter.value = true
  }
  const loadPetugas = async (norec_pp: any) => {
    isLoadingPop.value = true
    dataSourcePetugas.value = []
    await useApi().get(
      `/dashboard/radiologi/petugasrad?norec_pp=${norec_pp}`).then((response: any) => {
        isLoadingPop.value = false
        dataSourcePetugas.value = response
      }).catch((e: any) => {
        isLoadingPop.value = false
      })
  
  }
  const changeJenis = async (e: any) => {
    d_Pegawai.value = []
    await useApi().get(
      '/tindakan/list-map-jenis-petugas?idJenisPetugas=' + e
    ).then(async (response: any) => {
      d_Pegawai.value = response.map((e: any) => { return { label: e.namalengkap, value: e.id, default: e } })
    })
  }
  
  const editPetugas = async (e: any) => {
    item.norec_PPP = e.norec
    item.jenisPelaksana = e.objectjenispetugaspefk
    item.nomasukfk = e.nomasukfk
    item.pelayananpasien = e.pelayananpasien
    await changeJenis(e.objectjenispetugaspefk)
    item.pegawai = e.objectpegawaifk
  }
  
  const hapusPetugas = async (e: any) => {
    useApi().post(
      `dashboard/radiologi/delete-petugasrad`, {
      'norec': e.norec_ppp,
      'objectpegawaifk': e.objectpegawaifk,
    }).then((response: any) => {
      loadPetugas(  item.norec_pp)
    })
  }
  
  const savePetugas = async (e:any) => {
    let json = {
      'norec': item.norec_PPP ? item.norec_PPP : '',
      'objectjenispetugaspefk': item.jenisPelaksana,
      'objectpegawaifk': item.pegawai,
      'nomasukfk': item.norec_apd,
      'noregistrasi': pasien.value.noregistrasi,
      'pelayananpasien': item.norec_pp,
    }
    useApi().post(
      `/dashboard/save-petugaspe`, json).then((response: any) => {
        loadPetugas( item.norec_pp)
      })
  }

  const inputExpertise = (e:any) => {
    item.namaproduk = e.namaproduk
    item.dokter = e.dokter
    modalExpertise.value = true
  }
  
  const backPage = () => {
    window.history.back()
  }

  const countResult = () => {
  // console.log(dataSourceRiwayat.value)
  let total = 0
  dataSourceRiwayat.value.forEach((element: any) => {
    total = parseFloat(element.total) + total
  });
  item.totalAll = total
}
  
  pasienByID(ID_PASIEN)
  fetchDataOrder()
  
  </script>
      
  <style lang="scss" scoped>
  @import '/@src/scss/abstracts/all';
  @import '/@src/scss/components/forms-outer';
  @import '/@src/scss/custom/config';
  @import '/@src/scss/custom/timeline-css';
  
  .list-view-v1 {
    .list-view-item {
      @include vuero-r-card;
  
      margin-bottom: 16px;
      padding: 16px;
  
      .list-view-item-inner {
        display: flex;
        align-items: center;
  
        .meta-left {
          margin-left: 16px;
  
          h3 {
            font-family: var(--font-alt);
            color: var(--dark-text);
            font-weight: 600;
            font-size: 1rem;
            line-height: 1;
          }
  
          >span:not(.tag) {
            font-size: 0.9rem;
            color: var(--light-text);
  
            svg {
              height: 12px;
              width: 12px;
            }
          }
        }
  
        .meta-right {
          margin-left: auto;
          display: flex;
          justify-content: flex-end;
          align-items: center;
  
          .tags {
            margin-right: 30px;
            margin-bottom: 0;
  
            .tag {
              margin-bottom: 0;
            }
          }
  
          .stats {
            display: flex;
            align-items: center;
            margin-right: 30px;
  
            .stat {
              display: flex;
              align-items: center;
              flex-direction: column;
              text-align: center;
              color: var(--light-text);
  
              >span {
                font-family: var(--font);
  
                &:first-child {
                  font-size: 1.2rem;
                  font-weight: 600;
                  color: var(--dark-text);
                  line-height: 1.4;
                }
  
                &:nth-child(2) {
                  text-transform: uppercase;
                  font-family: var(--font-alt);
                  font-size: 0.75rem;
                }
              }
  
              svg {
                height: 16px;
                width: 16px;
              }
  
              i {
                font-size: 1.4rem;
              }
            }
  
            .separator {
              height: 25px;
              width: 2px;
              border-right: 1px solid var(--fade-grey-dark-3);
              margin: 0 16px;
            }
          }
  
          .network {
            display: flex;
            justify-content: flex-end;
            align-items: center;
            min-width: 145px;
  
            >span {
              font-family: var(--font);
              font-size: 0.9rem;
              color: var(--light-text);
              margin-left: 6px;
            }
          }
  
          .dropdown {
            margin-left: 30px;
          }
        }
      }
    }
  }
  
  .is-dark {
    .list-view-v1 {
      .list-view-item {
        @include vuero-card--dark;
  
        .list-view-item-inner {
          .meta-left {
            h3 {
              color: var(--dark-dark-text) !important;
            }
          }
  
          .meta-right {
            .stats {
              .stat {
                span {
                  &:first-child {
                    color: var(--dark-dark-text);
                  }
                }
              }
  
              .separator {
                border-color: var(--dark-sidebar-light-16) !important;
              }
            }
          }
        }
      }
    }
  }
  
  @media only screen and (max-width: 767px) {
    .list-view-v1 {
      .list-view-item {
        .list-view-item-inner {
          position: relative;
          flex-direction: column;
  
          .v-avatar {
            margin-bottom: 10px;
          }
  
          .meta-left {
            margin-left: 0;
          }
  
          .meta-right {
            flex-direction: column;
            margin-left: 0;
  
            .tags {
              margin: 10px 0;
            }
  
            .stats {
              margin: 10px 0;
            }
  
            .network {
              margin: 10px 0 0;
              justify-content: center;
  
              >span {
                display: none;
              }
            }
  
            .dropdown {
              position: absolute;
              top: 0;
              right: 0;
              margin-left: 0;
            }
          }
        }
      }
    }
  }
  
  @media only screen and (min-width: 768px) and (max-width: 1024px) and (orientation: portrait) {
    .list-view-v1 {
      display: flex;
      flex-wrap: wrap;
  
      .list-view-item {
        margin: 10px;
        width: calc(50% - 20px);
  
        .list-view-item-inner {
          position: relative;
          flex-direction: column;
  
          .v-avatar {
            margin-bottom: 10px;
          }
  
          .meta-left {
            margin-left: 0;
          }
  
          .meta-right {
            flex-direction: column;
            margin-left: 0;
  
            .tags {
              margin: 10px 0;
            }
  
            .stats {
              margin: 10px 0;
            }
  
            .network {
              margin: 10px 0 0;
              justify-content: center;
  
              >span {
                display: none;
              }
            }
  
            .dropdown {
              position: absolute;
              top: 0;
              right: 0;
              margin-left: 0;
            }
          }
        }
      }
    }
  }
  
  
  .is-dark .td-label {
  
    color: var(--dark-dark-text);
  }
  
  .list-widget {
    @include vuero-l-card;
  
    padding: 30px;
  
    &:not(:last-child) {
      margin-bottom: 1.5rem;
    }
  
    &.is-straight {
      @include vuero-s-card;
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
  
  .is-dark {
    .list-widget {
      @include vuero-card--dark;
    }
  }
  
  .tb-order {
    color: var(--light-text-dark-10);
    font-family: var(--font);
    font-weight: 300;
  
    .text-value {
      font-family: var(--font-alt);
      color: var(--dark-text);
      font-weight: 600;
    }
  
    td {
      padding: 0 3px 0 0;
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
          height: auto;
          width: 36px;
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
          width: 100%;
  
          span {
            font-size: 0.85rem;
            color: var(--light-text);
  
          }
  
          .is-danger {
            span.icon {
              color: var(--danger--color-invert);
            }
  
          }
  
          .is-warning {
            span.icon {
              color: var(--warning--color-invert);
            }
  
          }
  
          span.td-label {
            color: var(--dark-text);
          }
  
          p {
            font-family: var(--font-alt);
            font-size: 0.95rem;
            font-weight: 500;
            color: var(--dark-text);
            width: 220px;
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
  
  .timeline-wrapper .timeline-wrapper-inner .timeline-container .timeline-item::before {
    display: none;
  }
  
  .tile-grid-v2 {
      .tile-grid-item {
        @include vuero-s-card;
    
        border-radius: 14px;
        padding: 16px;
        cursor: pointer;
    
        &:hover,
        &:focus {
          border-color: var(--primary);
          box-shadow: var(--light-box-shadow);
        }
    
        .tile-grid-item-inner {
          display: flex;
          align-items: center;
    
          >img {
            display: block;
            width: 50px;
            height: 50px;
            min-width: 50px;
          }
    
          .meta {
            margin-left: 10px;
            line-height: 1.4;
    
            span {
              display: block;
              font-family: var(--font);
    
              &:first-child {
                color: var(--dark-text);
                font-family: var(--font-alt);
                font-weight: 600;
                font-size: 0.8rem;
              }
    
              &:nth-child(2) {
                display: flex;
                align-items: center;
    
                span {
                  display: inline-block;
                  color: var(--light-text);
                  font-size: 0.5rem;
                  font-weight: 400;
                }
    
                .icon-separator {
                  position: relative;
                  font-size: 4px;
                  color: var(--light-text);
                  padding: 0 6px;
                }
              }
            }
          }
    
          .dropdown {
            margin-left: auto;
          }
        }
      }
    }
  </style>
      