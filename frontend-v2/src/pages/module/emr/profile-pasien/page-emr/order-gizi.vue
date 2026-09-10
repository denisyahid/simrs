<template>
  <ConfirmDialog />
  <div class="form-layout is-stacked">
    <div class="form-outer" style="margin-top: 15px">
      <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
        <div class="form-header-inner">
          <div class="left">
            <h3>Order Gizi</h3>
          </div>
        </div>
      </div>

      <div class="columns is-multiline m-0">
        <div class="column is-10 is-flex" style="align-items: center;">
          <span style="font-weight: bold; font-size: 13px; font-family: var(--font-alt);">
            Pasien Sedang dirawat di Ruang
            <VTag color="warning" :label="item.namaruangan" rounded /> sejak Tanggal {{ H.formatDateIndo(item.tglmasuk)
            }}
          </span>
        </div>

        <div class="column is-2" style="margin-left: auto;">
          <VButton type="button" color="info" raised rounded icon="feather:plus-circle" :loading="isLoading"
            class="is-pulled-right mr-3 mt-0 mb-0" @click="showModal()">
            Order Gizi
          </VButton>
        </div>
      </div>

      <VCard style="margin-top: 1rem" v-if="dataSourceRiwayat.length > 0">
        <div class="timeline-wrapper">
          <div class="timeline-header">
          </div>
          <span style="font-weight: bold; font-size: 16px; font-family: var(--font-alt);">Riwayat Order Gizi
          </span>
          <br />
          <div class="timeline-wrapper-inner">
            <div class="timeline-container">
              <div class="timeline-item is-unread" v-for="(items, index) in dataSourceRiwayat" :key="items.norec">
                <div class="date">
                  <span>{{ H.formatDateIndo(items.tglorder) }}</span>
                </div>
                <div :class="'dot is-' + listColor[index + 1]"></div>

                <div class="content-wrap is-grey pt-2 pb-2" :style="items.isverifikasi ? 'background-color: #d5f7de;' : ''">
                  <div class="content-box">
                    <VIconBox size="medium" :color="listColor[index + 1]" rounded class="mr-5">
                      <i class="iconify" data-icon="feather:package" aria-hidden="true"></i>
                    </VIconBox>
                    <div class="box-tet" style="width: 70%">
                      <div class="meta-text">
                        <table style="width: 100%; margin-top: 10px">
                          <tr>
                            <td class="font-labels" style="font-weight:bold" width="20%">Jenis Diet</td>
                            <td class="font-values" width="80%">
                              : {{ items.jenisdiet }}
                            </td>
                          </tr>
                          <tr>
                            <td class="font-labels" style="font-weight:bold" width="20%">No. Order</td>
                            <td class="font-values" width="80%">: {{ items.noorder }}</td>
                          </tr>
                          <tr>
                            <td class="font-labels" style="font-weight:bold" width="20%">Keterangan</td>
                            <td class="font-values" width="80%">: {{ items.keteranganlainnya }}</td>
                          </tr>
                          <tr>
                            <td class="font-labels" style="font-weight:bold" width="20%">Status Verifikasi</td>
                            <td class="font-values" width="80%">: {{ items.isverifikasi ? 'Terverifikasi' : 'Belum Diverifikasi' }}</td>
                          </tr>
                        </table>

                      </div>
                    </div>
                    <b style="margin-right: 2.5rem; margin-top: -0.3rem;"> {{ items.ruanganasal }}</b>

                    <div class="box-end" style="width: 30%">
                      <div class="columns is-multiline">
                        <div class="column is-12">
                          <!-- <div class="status is-pulled-right mt-2 ml-2"></div> -->
                          <!-- <VTag :label="items.statuskirim" color="warning" class="is-pulled-right" rounded /> -->
                        </div>

                        <div class="column is-6" style="margin-top: 0.5rem;margin-bottom: 0.5rem;">
                          <VIconButton v-tooltip.bottom.left="'Edit Order Gizi'" icon="feather:edit"
                            @click="editItems(items)" color="warning" raised circle class="mr-2">
                          </VIconButton>
                          <VIconButton v-tooltip.bottom.right="'Hapus Order Gizi'" icon="feather:trash"
                            @click="DialogConfirm(items)" color="danger" raised circle class="mr-2">
                          </VIconButton>
                          <VIconButton v-tooltip.bottom.right="'Cetak Label'" icon="feather:printer"
                            @click="cetakLabel(items)" color="primary" raised circle>
                          </VIconButton>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </VCard>

      <VCard radius="rounded" class="mt-2" v-if="dataSourceRiwayat.length === 0">
        <VPlaceholderPage title="Riwayat Order Gizi Tidak Ditemukan."
          subtitle="Silakan Order Gizi untuk melihat Riwayat Order Gizi." larger>
          <template #image>
            <img class="light-image" src="/@src/assets/illustrations/placeholders/search-4.svg" alt="" />
            <img class="dark-image" src="/@src/assets/illustrations/placeholders/search-4-dark.svg" alt="" />
          </template>
        </VPlaceholderPage>
      </VCard>
    </div>
  </div>

  <!-- Modal Order Gizi  -->
  <VModal :open="modalInput" title="Order Menu Gizi" actions="right" size="medium" @close="modalInput = false">
    <template #content>
      <form class="modal-form">
        <div class="columns is-multiline">
          <div class="column is-6">
            <VField label="Tanggal Menu">
              <VDatePicker v-model="item.tglorder" mode="dateTime" style="width: 100%">
                <template #default="{ inputValue, inputEvents }">
                  <VField>
                    <VControl icon="feather:calendar" fullwidth>
                      <VInput :value="inputValue" placeholder="Tanggal Order" v-on="inputEvents" />
                    </VControl>
                  </VField>
                </template>
              </VDatePicker>
            </VField>
          </div>
          <div class="column is-6">
            <VField label="Jenis Waktu" class="is-autocomplete-select" v-slot="{ id }">
              <VControl icon="feather:clock" fullwidth>
                <Multiselect mode="single" v-model="item.objectjeniswaktufk" :options="d_JenisWaktu"
                  placeholder="Pilih data" :searchable="true" :attrs="{ id }" autocomplete="off" />
              </VControl>
            </VField>
          </div>
          <div class="column is-12">
            <VField label="Kategory Diet" class="is-autocomplete-select">
              <VControl icon="feather:list" fullwidth>
                <Multiselect mode="single" v-model="item.objectkategorydietfk" :options="d_KategoryDiet"
                  placeholder="Pilih data" :searchable="true" :attrs="{ id }" autocomplete="off"
                  @change="filterJenisDiet()" />
              </VControl>
            </VField>
          </div>
          <div class="column is-12">
            <VField label="Jenis Diet" class="is-autocomplete-select">
              <VControl icon="feather:coffee" fullwidth>
                <Dropdown v-model="item.arrjenisdiet" :options="d_JenisDiet" filter optionLabel="label"
                  style="width: 100%;" placeholder="Pilih Jenis Diet" :disabled="isDisabled"></Dropdown>
                <!-- <MultiSelect v-model="item.arrjenisdiet" :options="d_JenisDiet" filter optionLabel="name"
                  placeholder="Pilih Jenis Diet" :maxSelectedLabels="3" style="width:100%" :disabled="isDisabled" /> -->
              </VControl>
            </VField>
          </div>
          <div class="column is-12">
            <VField label="Takaran">
              <VControl icon="feather:bar-chart-2">
                <VInput type="number" v-model="item.takaran" placeholder="Takaran" />
              </VControl>
            </VField>
          </div>
          <!-- <div class="column is-12">
            <VField label="Jenis Diet External">
              <VControl icon="feather:plus">
                <VInput type="text" v-model="item.jenisDietExternal" />
              </VControl>
            </VField>
          </div> -->
          <div class="column is-12">
            <VField label="Keterangan">
              <VControl icon="feather:bookmark">
                <VInput type="text" v-model="item.keterangan" placeholder="Keterangan" />
              </VControl>
            </VField>
          </div>
          <!-- <div class="column is-6">
            <VField label="Batas Konsumsi Awal">
              <VDatePicker v-model="item.batasKonsumsiAwal" color="green" mode="time" is24hr>
                <template #default="{ inputValue, inputEvents }">
                  <VField>
                    <VControl icon="feather:clock">
                      <VInput class="input form-timepicker is-rounded" :value="inputValue" v-on="inputEvents" />
                    </VControl>
                  </VField>
                </template>
              </VDatePicker>
            </VField>
          </div>
          <div class="column is-6">
            <VField label="Batas Konsumsi Akhir">
              <VDatePicker v-model="item.batasKonsumsiAkhir" color="green" mode="time" is24hr>
                <template #default="{ inputValue, inputEvents }">
                  <VField>
                    <VControl icon="feather:clock">
                      <VInput class="input form-timepicker is-rounded" :value="inputValue" v-on="inputEvents" />
                    </VControl>
                  </VField>
                </template>
              </VDatePicker>
            </VField>
          </div> -->
        </div>
      </form>
    </template>
    <template #action>
      <VButton icon="feather:plus" @click="save(item)" :loading="isLoadingTT" color="primary" raised>Simpan</VButton>
    </template>
  </VModal>

  <!-- Modal Kirim Menu -->

  <!-- <VModal :open="modalKirim" title="Kirim Menu Gizi" actions="right" size="big" @close="modalKirim = false">
    <template #content>
      <form class="modal-form">
        <div class="columns is-multiline">
          <div class="column is-12">
            <VCard>
              <div class="columns is-multiline p-1">
                <div class="column is-12" v-for="(item, index) in listItem" :key="index">
                  <VCard class="is-grey">
                    <div class="columns is-multiline p-1">
                      <div class="column is-6">
                        <VField label="Nama Menu" class="is-rounded-select  is-autocomplete-select" v-slot="{ id }">
                          <VControl icon="feather:plus-circle" fullwidth>
                            <Multiselect mode="single" v-model="item.produk" :options="d_MenuGizi"
                              placeholder="Pilih data" :searchable="true" :attrs="{ id }" autocomplete="off" />

                          </VControl>
                        </VField>
                      </div>
                      <div class="column is-3">
                        <VField label="Jumlah">
                          <VControl icon="feather:bookmark">
                            <VInput type="number" v-model="item.qtyproduk" placeholder="Jumlah Produk"
                              class="is-rounded" />
                          </VControl>
                        </VField>
                      </div>
                      <div class="column is-1 mt-3" style="margin-top: 1rem;">
                        <VIconButton v-if="index > 0" outlined type="button" raised circle class="is-pulled-right"
                          icon="feather:trash" @click="removeItem(index)" color="danger">
                        </VIconButton>
                      </div>
                      <div class="column is-1 is-flex mt-3" style="margin-top: 1rem;">
                        <VButton type="button" rounded outlined color="info" raised icon="feather:plus"
                          @click="addNewItem()"> Tambah Menu
                        </VButton>
                      </div>
                    </div>
                  </VCard>
                </div>
              </div>
            </VCard>
          </div>
        </div>
      </form>
    </template>
    <template #action>
      <VButton icon="feather:plus" @click="tambah()" :loading="isLoading" color="primary" raised>Kirim Menu
      </VButton>
    </template>
  </VModal> -->
</template>
<script setup lang="ts">
import { useWindowScroll } from '@vueuse/core'
import MultiSelect from 'primevue/multiselect';
import Dropdown from 'primevue/dropdown';
import { useApi } from '/@src/composable/useApi'
import { h, reactive, ref, computed, defineComponent, watch, nextTick, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useHead } from '@vueuse/head'
import * as H from '/@src/utils/appHelper'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useThemeColors } from '/@src/composable/useThemeColors'
import { useToaster } from '/@src/composable/toaster'
import { ITERATE_KEY } from '@vue/reactivity';
import ConfirmDialog from 'primevue/confirmdialog'
import { useConfirm } from 'primevue/useconfirm'

useViewWrapper().setFullWidth(props.pasien ? true : false)
useHead({ title: 'Order Gizi - ' + import.meta.env.VITE_PROJECT, })
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)
let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pd as string
let NOREC_APD_ = useRoute().query.norec_apd as string
let pasien: any = ref({})
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
const confirm = useConfirm()
const isLoadingPasien: any = ref(false)
const isLoading: any = ref(false)
const isLoadingTT: any = ref(false)
const item: any = reactive({
  NOREC_PD: NOREC_PD != undefined ? NOREC_PD : '',
  NOREC_APD: '',
  tglorder: new Date(),
  tglkirim: new Date(),
  batasKonsumsiAkhir: new Date(),
  batasKonsumsiAwal: new Date(),
})
const dataSourceRiwayat: any = ref([])
let d_Ruangan: any = ref([])
let d_JenisDiet: any = ref([])
let d_JenisWaktu: any = ref([])
let d_KategoryDiet: any = ref([])
let d_MenuGizi: any = ref([])
const isDisabled: any = ref(true)
const modalInput: any = ref(false)
const modalKirim: any = ref(false)
const { y } = useWindowScroll()
const isStuck = computed(() => {
  return y.value > 30
})
let listColor: any = ref(Object.keys(useThemeColors()))

const listItem: any = ref([
  {
    produk: [],
    qtyproduk: [],
  }
])

function headerPasien(id: any) {
  if (props.pasien != undefined) {
    pasien.value = props.pasien
    item.NOREC_APD = props.registrasi.norec_apd
    item.RUANGAN_LAST = props.registrasi.objectruanganlastfk
    item.KELAS_LAST = props.registrasi.objectkelasfk
    item.KELAS_RAWAT = props.registrasi.kelasrawatfk
    item.registrasi = props.registrasi
    item.noregistrasi = props.registrasi.noregistrasi
    item.namaruangan = props.registrasi.namaruangan
    item.tglmasuk = props.registrasi.tglregistrasi
    item.tglpulang = props.registrasi.tglpulang
    NOREC_PD = props.registrasi.norec_pd
    riwayatPasien()
  } else {
    isLoadingPasien.value = true
    useApi().get(`/dashboard/detail-pasien-gizi?nocmfk=${id}&norec_pd=${item.NOREC_PD}`).then((response: any) => {
      pasien.value = response.pasien
      item.NOREC_APD = response.last_registrasi.norec_apd
      item.RUANGAN_LAST = response.last_registrasi.objectruanganlastfk
      item.KELAS_LAST = response.last_registrasi.objectkelasfk
      item.registrasi = response.last_registrasi
      item.noregistrasi = response.last_registrasi.noregistrasi
      item.namaruangan = response.last_registrasi.namaruangan
      item.tglmasuk = response.last_registrasi.tglmasuk
      item.tglpulang = response.last_registrasi.tglpulang
      item.KELAS_RAWAT = response.last_registrasi.kelasrawatfk
      isLoadingPasien.value = false
      riwayatPasien()
    })
  }
}

async function fetchdDropdown() {
  const response = await useApi().get(`/dashboard/dropdown-order-gizi`)
  d_Ruangan.value = response.ruangan.map((e: any) => { return { label: e.namaruangan, value: e.id, default: e } })
  d_JenisDiet.value = response.jenisdiet.map((e: any) => { return { name: e.jenisdiet, id: e.id, default: e } })
  d_JenisWaktu.value = response.jeniswaktu.map((e: any) => { return { label: e.jeniswaktu, value: e.id, default: e } })
  d_KategoryDiet.value = response.kategorydiet.map((e: any) => { return { label: e.kategorydiet, value: e.id, default: e } })
}

function riwayatPasien() {
  dataSourceRiwayat.value.loading = true
  isLoading.value = true
  useApi().get(`/dashboard/riwayat-order-gizi?nocm=${ID_PASIEN}&norec_pd=${NOREC_PD}`).then((response: any) => {
    for (let x = 0; x < response.data.length; x++) {
      const element = response.data[x]
      element.no = x + 1
    }
    dataSourceRiwayat.value = response.data
  }).catch((e: any) => {
    console.log(e)
    H.alert('error', 'Terjadi kesalahan')
  }).finally((e: any) => {
    dataSourceRiwayat.value.loading = false
    isLoading.value = false
  })
}

function showModal() {
  if (!props.registrasi.isRencanaMutasi && props.registrasi.objectdepartemenfk == 9) { H.alert('warning', 'Pasien ini belum memiliki rencana mutasi!'); return }
  clearInput()
  item.tglorder = new Date()
  modalInput.value = true
}

async function save(e: any) {
  if (!item.objectjeniswaktufk) {
    useToaster().error('Jenis Waktu harus di isi')
    return
  }
  if (!item.objectkategorydietfk) {
    useToaster().error('Kategory Diet harus di isi')
    return
  }
  if (!item.tglorder) {
    useToaster().error('Tanggal Order harus di isi')
    return
  }
  if (!item.arrjenisdiet) {
    useToaster().error('Jenis Diet harus di isi')
    return
  }

  var objSave = {
    'strukorder':
    {
      'norec_so': item.norec_so ? item.norec_so : '',
      'tglorder': item.tglorder,
      'noregistrasi': item.noregistrasi,
      'norec_pd': NOREC_PD,
      'norec_apd': NOREC_APD_,
      'nocmfk': ID_PASIEN,
      'details': [
        {
          'nocmfk': ID_PASIEN,
          'norec_pd': NOREC_PD,
          'objectjeniswaktufk': item.objectjeniswaktufk,
          'objectkategorydietfk': item.objectkategorydietfk,
          'keteranganlainnya': item.keterangan ? item.keterangan : null,
          'jenisdietfk': item.arrjenisdiet,
          'takaran': item.takaran ? item.takaran : null,
          'objectkelasfk': item.KELAS_LAST,
          'kelasrawatfk': item.KELAS_RAWAT,
          'objectruanganlastfk': item.RUANGAN_LAST,
          // 'jenisdietexternal': item.jenisDietExternal ? item.jenisDietExternal : null,
          // 'batasKonsumsiAwal': H.formatDate(item.batasKonsumsiAwal, 'YYYY-MM-DD HH:mm:ss'),
          // 'batasKonsumsiAkhir': H.formatDate(item.batasKonsumsiAkhir, 'YYYY-MM-DD HH:mm:ss'),
        }]
    },
  }
  isLoadingTT.value = true
  await useApi().post(`/dashboard/save-order-gizi`, objSave).then((response: any) => {
    isLoadingTT.value = false
    clearInput()
    riwayatPasien()
  }, (error) => {
    isLoadingTT.value = false
  })
}

function editItems(e: any) {
  item.norec_so = e.norec_so
  item.tglorder = e.tglorder
  item.noorder = e.noorder
  item.pegawaiorder = e.pegawaiorder
  item.objectjeniswaktufk = e.objectjeniswaktufk
  // item.arrjenisdiet = e.arrjenisdiet
  item.objectkategorydietfk = e.objectkategorydietfk
  item.takaran = e.takaran
  item.keterangan = e.keteranganlainnya
  item.jenisDietExternal = e.jenisdietexternal
  modalInput.value = true
}

function hapusItems(e: any) {
  useApi().post(`/dashboard/delete-order-gizi`, { norec: e.norec_op, norec_apd: NOREC_APD_ }).then((response: any) => {
    isLoading.value = false
    riwayatPasien()
  }).catch((e: any) => {
    isLoading.value = false
  })
}

const DialogConfirm = (e: any) => {
  confirm.require({
    message: 'Apakah anda yakin menghapus data ini ?',
    header: 'Konfirmasi Hapus Data',
    icon: 'pi pi-info-circle',
    acceptClass: 'p-button-danger',
    accept: () => {
      hapusItems(e)

    },
    reject: () => { },
  })
}

const filterJenisDiet = async () => {
  await nextTick();
  let kategorydiet = item.objectkategorydietfk ? `&kategorydiet=${item.objectkategorydietfk}` : ''

  await useApi().get(`/dashboard/dropdown-order-gizi?filter=true&jenisDiet=true${kategorydiet}`).then((res) => {
    if (res.jenisdiet.length) {
      isDisabled.value = false
      d_JenisDiet.value = res.jenisdiet.map((e: any) => { return { label: e.jenisdiet, value: e.id, default: e } })
    } else {
      H.alert('error', 'Terjadi kesalahan')
    }
  })
}

function clearInput() {
  delete item.statuspulang
  delete item.kondisipasien
  delete item.produk
  delete item.qtyproduk
  modalInput.value = false
  modalKirim.value = false
}

// async function tambah() {
//   var objSave = {
//     'strukkirim':
//     {
//       'norec_sk': item.norec_sk ? item.norec_sk : '',
//       'tglkirim': H.formatDate(item.tglkirim, 'YYYY-MM-DD HH:mm:ss'),
//       'objectruanganasalfk': item.RUANGAN_LAST,
//       'objectruanganfk': item.RUANGAN_LAST,
//       'noregistrasifk': item.NOREC_PD,
//       'norec_op': item.norec_op,
//       'details': listItem.value
//     },

//   }
//   isLoading.value = true
//   await useApi().post(`dashboard/save-kirim-gizi`, objSave).then((response: any) => {
//     isLoading.value = false
//     clearInput()
//     riwayatPasien()
//   }, (error) => {
//     isLoading.value = false
//     // console.log(error)
//   })
// }

// function addNewItem() {
//   listItem.value.push({
//     produk: [],
//     qtyproduk: null,
//   });
// }
// function removeItem(index: any) {
//   listItem.value.splice(index, 1)
// }

const data = [
  {
    type: 'messages',
    count: 5,
    status: 'new',
  },
  {
    type: 'tasks',
    count: 3,
    status: 'pending',
  },
]
const columns = {
  type: {
    label: 'Type',
    grow: 'lg',
    media: true,
  },
  count: {
    label: 'Count',
    align: 'center',
  },
  status: 'Status',
  actions: {
    label: 'Actions',
    align: 'end',
  },
} as const

function cetakLabel(e: any) {
  H.printBlade(`report/gizi/cetak-label?norec=${e.norec_op}&pdf=true`)
}

onMounted(async () => {
  isLoading.value = true
  await headerPasien(ID_PASIEN)
  await fetchdDropdown()
  isLoading.value = false
})

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

.timeline-wrapper .timeline-wrapper-inner .timeline-container .timeline-item[data-v-4206d2a0]::before {
  content: none;
}
</style>
