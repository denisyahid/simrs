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
                <div class="column is-2">
                </div>
                <div class="column is-6">
                  <div class="columns is-multiline is-pulled-right">
                    <div class="column is-5 pr-0 m-0">
                      <VButton class="ml-2 is-pulled-right" icon="feather:plus-circle" raised bold @click="inputTindakan(item)"
                        :loading="isLoadingBill" color="info">
                        Input Tindakan
                      </VButton>
                     
  
                    </div>
                    <div class="column pr-0 m-0">
                       <VButton class="ml-1 is-pulled-right" icon="pi pi-book" raised bold
                        :loading="isLoadingBill" color="primary">
                        Hasil 
                      </VButton>
                    </div>
                    <div class="column m-0">
  
                      <SpeedDial :model="listButton" direction="right" :transitionDelay="80" showIcon="pi pi-ellipsis-v"
                        hideIcon="pi pi-times" buttonClassName="p-button-outlined" className="speeddial-right"
                        class="secondary" type="semi-circle" :tooltipOptions="listButton" />
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
                              <VCheckbox v-model="item.checkAll" label="#" color="info" @change="checkedAll(item.checkAll)"
                                :value="item.checkAll" />
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
                                  </div>
                                  <div class="title-kelas">{{ itemsDet.namakelas }}</div>
                                </div>
                              </div>
                            </td>
                            <td class="center">
                              <div class="columns is-multiline">
                                <div class="column is-12">
                                  <div class="title-ruangan">Jasa : {{ H.formatRp(itemsDet.jasa, 'Rp. ') }}</div>
                                  <div class="title-layan">{{ H.formatRp(itemsDet.hargasatuan, 'Rp. ') }}</div>
  
                                  <div class="title-kelas">Diskon : {{ H.formatRp(itemsDet.hargadiscount, 'Rp. ') }}</div>
                                </div>
                              </div>
                            </td>
                            <td class="center">{{ itemsDet.jumlah }}</td>
                            <td class="center">{{ H.formatRp(itemsDet.total, 'Rp. ') }}</td>
                            <td class="center">
                              <VIconButton color="primary" class="mr-2" light raised circle icon="feather:user"
                              @click="detailPetugas(itemsDet)" v-tooltip.bubble="'Detail Petugas Pelayanan'" />
                              <VIconButton color="info" light raised circle icon="feather:edit" class="mr-1"
                              v-tooltip.bubble="'Hasil Expertise'" @click="HasilExpertise(itemsDet)"/>
                              <VIconButton color="danger" class="mr-2" light raised circle icon="feather:trash"
                                @click="hapusItems(itemsDet)" v-tooltip.bubble="'Hapus Layanan'" />
      
                            </td>
                          </tr>
                        </tbody>
                        <div class="search-results-wrapper" v-if="dataSourcefiltered.length == 0 && isLoadingBill == false">
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
              
    <VModal :open="modalPetugas" title="Detail Petugas Tindakan" :noclose="false" size="medium" actions="right"
      @close="modalPetugas = false">
      <template #content>
        <form class="modal-form custom-mod ">
          <div class="columns is-multiline">
            <div class="column is-12">
              <VCard>
                <div class="columns is-multiline p-1">
                  <div class="column is-6">
                    <p class="block-text">Tanggal Pelayanan</p>
                    <h4 class="block-heading">{{ dataSelect.tglpelayanan }}</h4>
  
                  </div>
                  <div class="column is-6">
                    <p class="block-text">Nama Pelayanan</p>
                    <h4 class="block-heading">{{ dataSelect.namaproduk }}</h4>
  
                  </div>
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
                </VControl>
              </VField>
            </div>
          </div>
        </form>
      </template>
      <template #action>
        <VButton icon="feather:save" @click="savePetugas()" :loading="isLoadingPop" color="primary" raised>Simpan
        </VButton>
      </template>
    </VModal>
    <VModal :open="modalCatatan" title="Catatan" :noclose="false" size="medium" actions="right"
      @close="modalCatatan = false">
      <template #content>
        <div class="column is-12">
          <VField>
            <VControl>
              <VTextarea class="textarea is-rounded" v-model="item.keterangan" rows="4" placeholder="Catatan"
                autocomplete="off" autocapitalize="off" spellcheck="true" />
            </VControl>
          </VField>
        </div>
      </template>
      <template #action>
        <VButton icon="feather:save" @click="saveCatatan(item)" :loading="isLoadingPop" color="primary" raised>Simpan
        </VButton>
      </template>
    </VModal>
    <VModal :open="modalKirimRIS" title="Kirim Ke RIS" :noclose="false" size="medium" actions="right"
      @close="modalKirimRIS = false">
      <template #content>
        <div class="column is-12">
          <VField class="is-rounded-select is-autocomplete-select">
            <VLabel class="required-field">Dokter Pengirim</VLabel>
            <VControl icon="feather:search" fullwidth class="prime-auto-select">
              <Dropdown v-model="item.objectpegawaifk" :options="d_Dokter" optionLabel="label" class="is-rounded"
                placeholder="Pilih Dokter" style="width: 100%;" :filter="true" />
            </VControl>
          </VField>
        </div>
        <div class="column is-12">
          <VField>
            <VControl>
              <VTextarea class="textarea is-rounded" v-model="item.keterangan" rows="4" placeholder="Catatan"
                autocomplete="off" autocapitalize="off" spellcheck="true" />
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
                <VInput type="text" v-model="item.dokterpemeriksa" placeholder="Nama Pelayanan" class="is-rounded" disabled />
              </VControl>
            </VField>
          </div>
            <div class="column is-12">
              <VField>
                    <VControl>
                      <VTextarea class="textarea is-rounded" v-model="item.keterangan" rows="4"
                        placeholder="Keterangan" autocomplete="off" autocapitalize="off"
                        spellcheck="true" />
                    </VControl>
                  </VField>
            </div>
          </div>
        </form>
    
        </template>
        <template #action>
        <VButton icon="feather:save" @click="saveExpertise(item)" :loading="isLoadingPop" color="primary" raised>Simpan
        </VButton>
      </template>
        </VModal>
  
  </template>
  <script setup lang="ts">
  import { ref, reactive, computed, watch } from 'vue'
  import { useWindowScroll } from '@vueuse/core'
  import { useHead } from '@vueuse/head'
  import * as H from '/@src/utils/appHelper'
  import { useRoute, useRouter } from 'vue-router'
  import { useViewWrapper } from '/@src/stores/viewWrapper'
  import { useApi } from '/@src/composable/useApi'
  import { useUserSession } from '/@src/stores/userSession'
  import SpeedDial from 'primevue/speeddial'
  import Dropdown from 'primevue/dropdown'
  import { useConfirm } from "primevue/useconfirm"
  import ConfirmDialog from 'primevue/confirmdialog'
  
  useHead({
    title: 'Transaksi Pelayanan - ' + import.meta.env.VITE_PROJECT,
  })
  useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
  useViewWrapper().setFullWidth(true)
  let NOREC_PD = useRoute().query.norec_pasien_daftar as string
  let NOREC_APD = useRoute().query.norec_apd as string
  let ID_PASIEN = useRoute().query.nocmfk as string
  let NOREGISTRASI = useRoute().query.noregistrasi as string
  const PARAMETER: any = ref('')
  const isLoadingPasien: any = ref(false)
  const isLoadingBill: any = ref(false)
  const isLoadingPop: any = ref(false)
  const pasien: any = ref({})
  const dataSource: any = ref([])
  const LISTRUANGAN_APD: any = ref([])
  const dataSourcePetugas: any = ref([])
  const router = useRouter()
  const listChecked: any = ref([])
  const modelCheck: any = ref([])
  const filters = ref('')
  const filtersHide = ref('')
  const modalPetugas = ref(false)
  const modalExpertise: any = ref(false)
  const modalKirimRIS: any = ref(false)
  const modalCatatan: any = ref(false)
  const dataSelect: any = ref({})
  const d_JenisPelaksana: any = ref([])
  const d_Pegawai: any = ref([])
  const d_Dokter: any = ref([])
  const showJenis: any = ref(false)
  const item: any = reactive({
    NOREC_PD: NOREC_PD != undefined ? NOREC_PD : '',
    NOREC_APD: NOREC_APD != undefined ? NOREC_APD : '',
    registrasi: {},
    tglorder: new Date(),
    tanggal: new Date(),
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
  })
  const { y } = useWindowScroll()
  const isStuck = computed(() => {
    return y.value > 30
  })
  const listButton: any = ref([
  {
      label: 'Catatan ',
      icon: 'pi pi-book',
      command: () => {
        if (listChecked.value.length == 0) {
          H.alert('error', 'Ceklis data Untuk Menambahkan Catatan')
          return
        }
        modalCatatan.value = true
      }
    },
    {
      label: 'Kirim Ke LIS',
      icon: 'pi pi-send',
      command: () => {
        if (listChecked.value.length == 0) {
          H.alert('error', 'Ceklis Data yang akan Dikirim')
          return
        }
        modalKirimRIS.value = true
      }
    },
    {
    label: 'Cetak Bukti Layanan ',
    icon: 'fas fa-print',
    command: () => {
      if (listChecked.value.length == 0) {
        H.alert('error', 'Ceklis data yang mau dicetak')
        return
      }

      let norec = listChecked.value.map((a: any) => a.norec).join("|");
      H.printBlade('radiologi/cetakan-hasil-radiologi?noregistrasi=' + item.noregistrasi
        + '&norec=' + norec);

        console.log(item.noregistrasi)
    }
  },
  
  ])
  const confirm = useConfirm();
  
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
        item.noregistrasi = response.last_registrasi.noregistrasi
        item.namaruangan = response.last_registrasi.namaruangan
        item.tglmasuk = response.last_registrasi.tglmasuk
        item.tglpulang = response.last_registrasi.tglpulang
        isLoadingPasien.value = false
        fetchLayanan()
      })
  }
  
  const fetchLayanan = async () =>{
    isLoadingBill.value = true
    dataSource.value = []
    await useApi().get(
      `/cathlab/layanan-cathlab?norec_pd=${NOREC_PD}`).then(async (response: any) => {
        
        dataSource.value = response.detail
        item.TOTAL = response.total
        item.PENGEMBALIAN = response.pengembalian
        item.length = response.length
        LISTRUANGAN_APD.value = response.list_ruangan
        isLoadingBill.value = false
  
      })
  }
  
  const inputTindakan = (e: any) => {
    router.push({
      name: 'module-emr-tindakan',
      query: {
        nocmfk: pasien.value.nocmfk,
        norec_pasien_daftar: e.NOREC_PD,
        norec_apd: e.NOREC_APD
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
            'noorder' : e.noorder,
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
      `/radiologi/hapus-tindakan-rad`, objSave).then((response: any) => {
        isLoadingBill.value = false
        fetchLayanan()
      }).catch((e: any) => {
        isLoadingBill.value = false
      })
  }
  const detailPetugas = async (e: any) => {
    clearInputPetugas()
    dataSelect.value = {}
    dataSelect.value = e
    await loadPetugas(e)
    await useApi().get(
      `tindakan/list-jenis-petugas`).then((response: any) => {
        d_JenisPelaksana.value = response.jenispetugaspelaksana.map((e: any) => { return { label: e.jenispetugaspe, value: e.id, default: e } })
      })
  
    modalPetugas.value = true
  
  }
  const loadPetugas = async (e: any) => {
    isLoadingPop.value = true
    dataSourcePetugas.value = []
    await useApi().get(
      `/radiologi/petugas-radiologi?norec=${e.norec}`).then((response: any) => {
        isLoadingPop.value = false
        dataSourcePetugas.value = response
      }).catch((e: any) => {
        isLoadingPop.value = false
      })
  
  }
  
  const savePetugas = () => {
    let json = 
    {
      'norec': item.norec_PPP ? item.norec_PPP : '',
      'objectjenispetugaspefk': item.jenisPelaksana,
      'objectpegawaifk': item.pegawai,
      'nomasukfk': dataSelect.value.norec_apd,
      'noregistrasi': pasien.value.noregistrasi,
      'pelayananpasien': dataSelect.value.norec,
      'namaproduk': dataSelect.value.namaproduk,
      'namaruangan': dataSelect.value.namaruangan,
    }   
    useApi().post(
      `/radiologi/save-petugas-rad`, json).then((response: any) => {
        loadPetugas(dataSelect.value)
      })
  }
  const clearInputPetugas = ()=>{
    delete item.norec_PPP
    delete item.jenisPelaksana
    delete item.pegawai
  }
  const hapusPetugas = async (e: any) => {
    useApi().post(
      `/radiologi/hapus-petugas-rad`, {
      'norec': e.norec,
      'objectpegawaifk': e.objectpegawaifk,
      'namaproduk': dataSelect.value.namaproduk,
      'noregistrasi': pasien.value.noregistrasi,
    }).then((response: any) => {
      loadPetugas(dataSelect.value)
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
  
  const editPetugas= async (e: any)=>{
    item.norec_PPP = e.norec
    item.jenisPelaksana = e.objectjenispetugaspefk
    item.nomasukfk = e.nomasukfk
    item.pelayananpasien = e.pelayananpasien
    await changeJenis(e.objectjenispetugaspefk)
    item.pegawai = e.objectpegawaifk
  }
    
  const listPegawai = async () => {
    await useApi().get(
      `/dashboard/list-lab`).then((response: any) => {
        d_Dokter.value = response.namalengkap.map((e: any) => {
          return { label: e.namalengkap, value: e.id }
        })
      })
  }
  
  const HasilExpertise = async (e: any) => {
    item.norec_pp = e.norec
    item.noregistrasifk = e.norec_apd
    item.dokterpemeriksa = e.dokterpemeriksa
    item.pegawaifk = e.pegawaifk
    item.namaproduk = e.namaproduk
    item.pelayananpasienfk = e.norec_pp
    item.noregistrasifk = e.norec_apd
  
    modalExpertise.value = true
  }
  

  const saveExpertise = async (e: any) => {
    let objSave = {
    hasil : {
        keterangan : item.keterangan,
        pelayananpasienfk :  item.norec_pp,
        noregistrasifk : item.noregistrasifk,
        pegawaifk : item.pegawaifk 
    }
  }
    useApi().post(
        `/radiologi/save-expertise`, objSave).then((response: any) => {
        })
}


  const inputCatatan = async (e: any) => {
    modalCatatan.value = true
  }
  
  const saveCatatan = async (e: any) => {
  
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
   
  const clear = () => {
  
  item.norec = ''
  item.nomorpa = ''
  item.pegawaifk = ''
  item.dokterpengirimfk = ''
  item.pelayananpasienfk = ''
  item.noregistrasifk = ''
  item.diagnosaklinik = ''
  item.keteranganklinik = ''
  item.diagnosapb = ''
  item.keteranganpb = ''
  item.topografi = ''
  item.morfologi = ''
  item.makroskopik = ''
  item.mikroskopik = ''
  item.kesimpulan = ''
  item.anjuran = ''
  item.jaringanasal = ''
  
  modalExpertise.value = false
  modalCatatan.value = false
  }
  watch(
    () => item.persenDiskon,
    (newValue, oldValue) => {
      if (newValue != oldValue) {
        if (newValue > 100) {
          item.persenDiskon = "";
        }
        item.diskonKomponen = ((parseFloat(item.hargasatuankom)) * newValue) / 100
      }
    }
  )
  
  watch(
      () => item.jenis,
      (newValue, oldValue) => {
          if (newValue == 'pa') {
            showJenis.value = true
          } else {
            showJenis.value = false
          }
      }
  )
  headerPasien(ID_PASIEN)
  listPegawai()
  
  </script>
  <style lang="scss">
  @import '/@src/scss/main';
  @import '/@src/scss/module/kasir/billing';
  
  .field > label {
      color: hsl(0deg, 0%, 4%) !important;
  }
  
  .hr-dashboard {
    .block-header {
      display: flex;
      border-radius: 16px;
      padding: 20px;
      background: var(--primary);
      font-family: var(--font);
      box-shadow: var(--primary-box-shadow);
  
      .left,
      .right {
        width: 30%;
      }
  
      .center {
        display: flex;
        flex-direction: column;
        width: 40%;
        padding-right: 10px;
        margin-right: 30px;
        border-right: 1px solid var(--primary-light-10);
  
        .block-text {
          margin-bottom: 16px;
        }
  
        .candidates {
          margin-top: auto;
  
          >.v-avatar {
            margin-right: 10px;
          }
  
          button {
            height: 40px;
            width: 40px;
            display: inline-flex;
            justify-content: center;
            align-items: center;
            border-radius: 10px;
            background: var(--white);
            color: var(--light-text);
            border: none;
            cursor: pointer;
            transition: all 0.3s; // transition-all test
  
            svg {
              height: 18px;
              width: 18px;
            }
          }
        }
      }
  
      .left {
        display: flex;
        justify-content: center;
        align-items: center;
  
        .current-user {
          .v-avatar {
            margin-bottom: 1rem;
          }
  
          h3 {
            font-family: var(--font-alt);
            font-weight: 700;
            font-size: 1.8rem;
            color: var(--white);
            line-height: 1.2;
          }
        }
      }
  
      .right {
        display: flex;
        flex-direction: column;
  
        .button {
          margin-top: auto;
        }
      }
  
      .block-heading {
        font-family: var(--font-alt);
        font-weight: 600;
        font-size: 1.1rem;
        color: var(--white);
        margin-bottom: 4px;
      }
  
      .block-text {
        font-family: var(--font);
        font-size: 0.9rem;
        color: var(--white);
        margin-bottom: 16px;
      }
  
      .header-meta {
        margin-left: 0;
        padding-right: 30px;
  
        h3 {
          color: var(--smoke-white);
          font-family: var(--font-alt);
          font-weight: 700;
          font-size: 1.3rem;
          max-width: 280px;
        }
  
        p {
          font-weight: 400;
          color: var(--smoke-white-dark-2);
          margin-bottom: 16px;
          max-width: 320px;
        }
  
        .action-link {
          span {
            font-size: 0.8rem;
            text-transform: uppercase;
            margin-right: 6px;
          }
  
          i {
            font-size: 12px;
          }
        }
      }
    }
  
    .feed-settings {
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 20px 0;
  
      h3 {
        font-family: var(--font-alt);
        font-size: 1.1rem;
        font-weight: 600;
        color: var(--dark-text);
      }
  
      .button {
        font-size: 0.8rem;
        border-radius: 8px;
        margin-right: 4px;
  
        &.is-selected {
          background: var(--primary);
          color: var(--white);
          border-color: var(--primary);
          box-shadow: var(--primary-box-shadow);
        }
      }
    }
  
    .side-text {
      h3 {
        font-family: var(--font-alt);
        font-size: 1.1rem;
        font-weight: 600;
        color: var(--dark-text);
        margin-bottom: 8px;
      }
  
      p {
        font-size: 0.95rem;
        margin-bottom: 8px;
      }
  
      .action-link {
        font-size: 0.9rem;
      }
    }
  
    .recent-rookies {
      .recent-rookies-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 20px;
  
        h3 {
          font-family: var(--font-alt);
          font-size: 2rem;
          font-weight: 600;
          color: var(--dark-text);
        }
      }
  
      .user-grid {
        &.user-grid-v4 {
          .grid-item {
            @include vuero-l-card;
          }
        }
      }
    }
  }
  
  
  
  </style>
  