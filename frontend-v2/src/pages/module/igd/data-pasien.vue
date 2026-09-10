<template>
  <ConfirmDialog />
  <div class="column">
    <VCard>
      <div class="column is-12">
        <div class="search-widget">
          <div class="field">
            <div class="columns is-multiline">
              <div class="column is-12">
                <h3 class="title is-5 mb-2 mr-1">Data Pasien</h3>
              </div>
              <div class="column is-3">
                <VField>
                    <VLabel> Nama Pasien </VLabel>
                <VControl icon="feather:search">
                  <input v-model="item.qnama" v-on:keyup.enter="fetchData()" type="text" class="input"
                    placeholder="Filter Nama..." />
                </VControl>
              </VField>
              </div>
              <div class="column is-2">
                <VField>
                  <VLabel> Alamat </VLabel>
                  <VControl icon="feather:search">
                  <input v-model="item.qnama" v-on:keyup.enter="fetchData()" type="text" class="input"
                    placeholder="Filter Nama..." />
                </VControl>
                </VField>
              </div>
              <div class="column is-3">
              <VControl class="prime-auto">
                <VField>
                  <VLabel>Tanggal Lahir</VLabel>
                  <Calendar v-model="item.tglLahir" :locale="'id'" selectionMode="single" :showIcon="true" :manualInput="true" class="w-100" :dateFormat="H.dateTimeFormat().prime.date" :placeholder="H.dateTimeFormat().prime.date" />
                </VField>
              </VControl>
            </div>
              <div
                class="column"
                style="margin-top: 25px; margin-left: auto:  !important;"
              >
                <VIconButton
                  type="button"
                  color="success"
                  class="searcv-button"
                  raised
                  icon="fas fa-search"
                  @click="cari()"
                  :loading="isLoadingBtn"
                >
                </VIconButton>
              </div>
            </div>
          </div>
        </div>
      </div>
      <Divider />

      <DataTable
        :value="dataSource"
        tableStyle="min-width: 50rem"
        scrollable
        :paginator="true"
        :rows="10"
        :rowsPerPageOptions="[5, 10, 25]"
        :loading="isLoading"
        showGridlines
      >
        <template #header>
          <div class="flex flex-wrap align-items-center justify-content-between gap-2">
            <span class="text-xl text-900 font-bold">Data Pasien</span>
            <VButton
              color="info"
              icon="fas fa-paste"
              raised
              rounded
              style="margin-left: 20px"
              @click="inputBaru()"
            >
              Input Triage Pasien Baru
            </VButton>
          </div>
        </template>
        <Column :exportable="false" header="Input Triage" style="min-width: 100px" frozen>
          <template #body="slotProps">
            <VIconButton
              type="button"
              icon="fas fa-sticky-note"
              class="mr-2"
              color="info"
              circle
              outlined
              raised
              v-tooltip.top="'Triage'"
              @click="triage(slotProps.data)"
            >
            </VIconButton>
          </template>
        </Column>
        <Column
          field="nocm"
          header="No. Rekam Medis"
          style="min-width: 150px"
          frozen
        ></Column>
        <Column field="namapasien" header="Nama Pasien" style="min-width: 150px"></Column>
        <Column
          field="jeniskelamin"
          header="Jenis Kelamin"
          style="min-width: 150px"
        ></Column>
        <Column field="tgllahir" header="Tanggal Lahir" style="min-width: 150px"></Column>
        <Column field="namaibu" header="Nama Ibu" style="min-width: 150px"></Column>
        <Column field="alamatlengkap" header="Alamat" style="min-width: 150px"></Column>
        <Column field="notelp" header="No. Telepon" style="min-width: 150px"></Column>

        <template #footer>
          Total Pasien = {{ dataSource ? dataSource.length : 0 }}
        </template>
      </DataTable>
    </VCard>
  </div>


  <VModal
    :open="modalCollect"
    size="big"
    noclose
    title="Pasien Baru"
    actions="center"
    @close=";(modalCollect = false), clear()"
    cancelLabel="Tutup"
  >
    <template #content>
      <div class="column">
        <div class="columns is-multiline">

            <div class="column is-3">
                <VField>
                    <VLabel> Nama Pasien </VLabel>
                <VControl icon="feather:search">
                  <input v-model="item.namapasien" type="text" class="input"
                    placeholder="Nama Pasien..." />
                </VControl>
              </VField>
              </div>
              <div class="column is-2">
                <VField>
                  <VLabel> Tempat Lahir </VLabel>
                  <VControl icon="feather:search">
                  <input v-model="item.tempatlahir" type="text" class="input"
                    placeholder="Tempat Lahir..." />
                </VControl>
                </VField>
              </div>
              <div class="column is-3">
              <VControl class="prime-auto">
                <VField>
                  <VLabel>Tanggal Lahir</VLabel>
                  <Calendar v-model="item.tgllahir" :locale="'id'" selectionMode="single" :showIcon="true" :manualInput="true" class="w-100" :dateFormat="H.dateTimeFormat().prime.date" :placeholder="H.dateTimeFormat().prime.date" />
                </VField>
              </VControl>
            </div>
            <div class="column is-3">
                <VField>
                  <VLabel> Nomor Telepon </VLabel>
                  <VControl icon="feather:search">
                  <input v-model="item.notelp" type="text" class="input"
                    placeholder="Nomor Telepon..." />
                </VControl>
                </VField>
              </div>
              <div class="column is-8">
                <VField>
                  <VLabel> Alamat </VLabel>
                  <VControl icon="feather:search">
                  <input v-model="item.alamatlengkap" type="text" class="input"
                    placeholder="Alamat..." />
                </VControl>
                </VField>
              </div>
    
        </div>

      </div>
    </template>

    <template #action>
      <VButton
        icon="feather:save"
        :loading="isLoading"
        @click="triagePasBaru(item)"
        color="primary"
        raised
        >Selanjutnya</VButton
      >
    </template>
  </VModal>
</template>
<script setup lang="ts">
import { useWindowScroll } from '@vueuse/core'
import { useApi } from '/@src/composable/useApi'
import { ref, computed, watch, reactive, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useHead } from '@vueuse/head'
import { useUserSession } from '/@src/stores/userSession'
import ConfirmDialog from 'primevue/confirmdialog'
import { useConfirm } from 'primevue/useconfirm'
import * as H from '/@src/utils/appHelper'
import DataTable from 'primevue/datatable'
import Column from 'primevue/column'
import { useToaster } from '/@src/composable/toaster'
import Dialog from 'primevue/dialog'
import AutoComplete from 'primevue/autocomplete'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useThemeColors } from '/@src/composable/useThemeColors'
import moment from 'moment'
import TabView from 'primevue/tabview'
import TabPanel from 'primevue/tabpanel'
import Badge from 'primevue/badge'
import Tag from 'primevue/tag'
import Divider from 'primevue/divider'
import sleep from '/@src/utils/sleep'
import Calendar from 'primevue/calendar';

useHead({
  title: 'Data Pasien - ' + import.meta.env.VITE_PROJECT,
})
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)
const item: any = ref({
  periode: reactive({
    start: new Date(),
    end: new Date(),
  }),
  tglsetor: new Date(),
  tglbayar: new Date(),
  totalbayarawal: 0,
  tglCollect: new Date(),
})

const listColor: any = ref(Object.keys(useThemeColors()))

const activeTab = ref(0)
const router = useRouter()
const route = useRoute()
const { y } = useWindowScroll()
const d_Ruangan = ref([])
const sourceOrder = ref([])
const dataSource = ref([])
const dataBayar = ref([])
const dataDetail = ref([])
const d_Rekanan = ref([])
const modalInput = ref(false)
const modalBayar = ref(false)
const modalConfirm = ref(false)
const modelCheck: any = ref([])
const isLoadingBtn = ref(false)
const isLoadingBB = ref(false)
const isLoading = ref(false)
const d_CaraBayarFoot: any = ref([])
const d_caraBayar: any = ref([])
const d_caraSetor: any = ref([])
const confirm = useConfirm()
const modalCollect = ref(false)


const currentPage: any = ref({
  limit: 20,
  rows: 50,
})

currentPage.value.page = computed(() => {
  try {
    return Number.parseInt(route.query.page as string) || 1
  } catch {}
  return 1
})

const fetchData = async () => {
  isLoading.value = true
  let limit: any = currentPage.value.limit
  let offset: any = route.query.page ? route.query.page : 1
  offset = offset * limit - limit

  let namapasien = ''
  let nocm = ''
  let nik = ''
  let nobpjs = ''
  let alamat = ''
  let tgllahir = ''
  if (item.value.qnama) namapasien = `&namapasien=${item.value.qnama}`
  if (item.value.qnocm) nocm = `&nocm=${item.value.qnocm}`
  if (item.value.qnik) nik = `&nik=${item.value.qnik}`
  if (item.value.qalamat) alamat = `&alamat=${item.value.qalamat}`
  if (item.value.tglLahir) tgllahir = `&tgllahir=${H.formatDate(item.value.tglLahir, 'YYYY-MM-DD')}`

  await useApi()
    .get(`igd/data-pasien?limit=${limit}&offset=${offset}${namapasien}${nocm}${nik}${nobpjs}${alamat}${tgllahir}`)
    .then((response) => {
      response.data.forEach((element: any, i: any) => {
        element.no = i + 1
      })
      isLoading.value = false
      dataSource.value = response.data
    })
}
const fetchDropdown = () => {
  useApi()
    .get(`/bendahara/get-list-bayar`)
    .then((response: any) => {
      d_caraBayar.value = response.carabayar.map((e: any) => {
        return { label: e.carabayar, value: e.id, default: e }
      })
      d_caraSetor.value = response.carasetor.map((e: any) => {
        return { label: e.carasetor, value: e.id, default: e }
      })
    })
}

const getLabel = (status: any) => {
  switch (status) {
    case 'BELUM LUNAS':
      return 'danger'
    case 'LUNAS':
      return 'success'
  }
}

const fetchDetailTagihan = async (norec: any) => {
  await useApi()
    .get(`bendahara/get-detail-tagihan-sup?norec_sp=${norec}`)
    .then((response) => {
      response.data.forEach((element: any, i: any) => {
        element.no = i + 1
      })
      dataDetail.value = response.data
    })
}
const isMozilla = () => {
  return navigator.userAgent.indexOf('Firefox') !== -1
}

const changeNomi = async (e: any) => {
  if (isMozilla()) {
    await sleep(1000)
  }
  item.value.nominal = H.formatRupiah(e)

  item.value.terbilang = H.terbilang(parseFloat(item.value.nominal))
}

const changeBayar = (e: any) => {
  // item.value.nominalBayar = H.formatRupiah(item.value.nominalBayar)

  item.value.terbilangBayar = H.terbilang(parseFloat(item.value.nominalBayar))

  // console.log(item.value.nominalBayar)
}

const detailRekanan = async (rknid: any) => {
  const response = await useApi().get(
    `bendahara/detail-rekanan-tagihan?idrekanan=${rknid}`
  )
}

const triage = (e: any) => {
    router.push({
    name: 'module-igd-input-triage',
    query: {  
      nocm : e.nocm,
      namapasien: e.namapasien,
      alamat : e.alamatlengkap,
      tgllahir : e.tgllahir

    },
  })
}

const triagePasBaru = (e: any) => {
    router.push({
    name: 'module-igd-input-triage',
    query: {  
      namapasien: e.namapasien,
      alamat : e.alamatlengkap,
      tgllahir : H.formatDate(e.tgllahir,'DD-MMM-YYYY')
    },
  })
}

const bayarTagihan = (e: any) => {
  fetchDetailTagihan(e.norec)
  detailRekanan(e.rknid)

  item.value.norec_sp = e.norec
  item.value.sisaHutang = 0

  // item.value.sisautang = e.sisautang
  item.value.nodokumen = e.nodokumen
  item.value.nostruk = e.nostruk
  item.value.tgldokumen = e.tglstruk
  item.value.totalbayarawal = H.formatRupiah(parseFloat(e.totalbayar))
  item.value.tagihan = H.formatRupiah(parseFloat(e.subtotal))
  // item.value.rpTagihan = parseFloat(e.subtotal)
  item.value.terbilangTagihan = H.terbilang(item.value.tagihan)
  item.value.deskripsiTransaksi = 'PEMBAYARAN TAGIHAN SUPLIER A/N' + e.namarekanan
  item.value.uraianTransaksi = 'PEMBAYARAN TAGIHAN SUPLIER'

  modalBayar.value = true
}

const sudahBayar = () => {
  modalConfirm.value = true
}

const cetakKwitansi = async () => {}

const cetakKwitansiBayar = async () => {}

const kembaliKeun = () => {
  modalBayar.value = false
}
const simpanBayar = async () => {
  if (!item.value.caraBayar) {
    H.alert('error', 'Cara Bayar Harus Diisi')
    return
  }
  if (!item.value.nominalBayar) {
    H.alert('error', 'Nominal Pembayaran Harus Diisi')
    return
  }

  let json = {
    sbk: {
      nostruk: item.value.norec_sp,
      nosbk: item.value.nosbk ? item.value.nosbk : '',
      carabayar: item.value.caraBayar,
      kelompoktransaksi: 107,
      keteranganlainnya: item.value.deskripsiTransaksi,
      tagihan: H.unFormatRupiah(item.value.tagihan),
      totalbayar: H.unFormatRupiah(item.value.nominalBayar),
      tglsbk: moment(item.value.tglbayar).format('YYYY-MM-DD HH:mm'),
      bankrekanan: item.value.namabank ? item.value.namabank : '',
      rekeningrekanan: item.value.norek ? item.value.norek : '',
      pemilikrekanan: item.value.namapemilik ? item.value.namapemilik : '',
      sisautang:
        H.unFormatRupiah(item.value.tagihan) - H.unFormatRupiah(item.value.nominalBayar),
      keterangan: item.value.deskripsiTransaksi,
      biayaadmin: H.unFormatRupiah(item.value.nominal)
        ? H.unFormatRupiah(item.value.nominal)
        : 0,
    },
  }
  isLoadingBtn.value = true
  await useApi()
    .post(`bendahara/save-bayar-tagihan-suplier`, json)
    .then((response: any) => {
      isLoadingBtn.value = false
      modalBayar.value = false
      fetchData()
      clear()
    })
    .catch((e: any) => {
      isLoadingBtn.value = false
    })
}

const fetchRekanan = async (filter: any) => {
  await useApi()
    .get(
      `emr/dropdown/rekanan_m?select=id,namarekanan&param_search=namarekanan&query=${filter.query}&limit=10`
    )
    .then((response) => {
      d_Rekanan.value = response
    })
}

const cari = () => {
  fetchData()
}

const clear = () => {
  delete item.value.nodokumen
  delete item.value.tgldokumen
  delete item.value.caraBayar
  delete item.value.deskripsiTransaksi
  delete item.value.uraianTransaksi
  delete item.value.nominal
  delete item.value.terbilang
  delete item.value.totalbayarawal
  delete item.value.tagihan
  delete item.value.terbilangTagihan
  delete item.value.nominalBayar
}

// watch(
//     () => item.value.nominalBayar,
//     (newValue, oldValue) => {
//         if (newValue != oldValue) {
//             item.value.nominalBayar = newValue
//         }
//         item.value.sisaHutang = item.value.tagihan - newValue;
//     }
// )

const inputBaru = (e: any) => {

  modalCollect.value = true
}

function checkedAll(e: any) {
  totalCollect.value = 0
  totalKlaim.value = 0
  modelCheck.value = []
  listChecked.value = []
  if (e) {
    dataSource.value.forEach((e: any) => {
      listChecked.value.push(e)
      modelCheck.value[e.norec] = true
    })
  }
  totalCollect.value = listChecked.value.length
  listChecked.value.map((value: any, index: number) => {
    value.no = index + 1
    totalKlaim.value += Number(value.subtotal)
  })
}
function checkedItems() {
  totalCollect.value = 0
  totalKlaim.value = 0
  totalPrice.value = 0
  totalPPN.value = 0
  DiskonTotal.value = 0
  const objectKeys = Object.keys(modelCheck.value)
  for (let x = 0; x < objectKeys.length; x++) {
    const element = objectKeys[x]

    if (modelCheck.value[element] === true) {
      const checkedItem = dataSource.value.find((item) => item.norec === element)

      if (checkedItem && !listChecked.value.some((item) => item.norec === element)) {
        listChecked.value.push(checkedItem)
      }
    } else {
      listChecked.value = listChecked.value.filter((item) => item.norec !== element)
    }
  }
  totalCollect.value = listChecked.value.length
  listChecked.value.map((value: any, index: number) => {
    value.no = index + 1
    totalKlaim.value += Number(value.subtotal)
    totalPrice.value += Number(value.total)
    totalPPN.value += Number(value.totalppn)
    DiskonTotal.value += Number(value.totaldiskon)
  })

  console.log(listChecked.value)
}

const saveCollect = async () => {
  var totalharga = 0
  var totalppn = 0
  var totaldiskon = 0
  var totaltagihan = 0

  if (listChecked.value.length == 0) {
    H.alert('warning', 'Belum ada data yang di pilih !')
    return
  }

  for (var i = listChecked.value.length - 1; i >= 0; i--) {
    totalharga = totalharga + parseFloat(listChecked.value[i].total)
    totalppn = totalppn + parseFloat(listChecked.value[i].totalppn)
    totaldiskon = totaldiskon + parseFloat(listChecked.value[i].totaldiskon)
  }
  totaltagihan = totalharga - totaldiskon + totalppn

  var objSave = {
    norec: '',
    objectrekananfk: item.value.rekanan.value,
    totalharga: totalharga != undefined ? totalharga : 0,
    totalppn: totalppn != undefined ? totalppn : 0,
    totaldiskon: totaldiskon != undefined ? totaldiskon : 0,
    totaltagihan: totaltagihan != undefined ? totaltagihan : 0,
    tglcollecting: moment(item.value.tglCollect).format('YYYY-MM-DD HH:mm'),
    detail: listChecked.value,
  }
  isLoading.value = true
  await useApi()
    .post(`bendahara/save-collecting`, objSave)
    .then((response: any) => {
      isLoading.value = false
      modalCollect.value = false
      fetchData()
    })
    .catch((e: any) => {
      isLoading.value = false
    })
}

fetchData()
fetchDropdown()

watch(currentPage.value, () => {
  fetchData()
})
</script>

<style lang="scss">
@import '/@src/scss/abstracts/all';
@import '/@src/scss/components/forms-outer';
@import '/@src/scss/custom/timeline-css';

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

            > h3 {
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
                    + .radio-box-inner {
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
              > p {
                padding-top: 12px;

                > span {
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

              > h3 {
                color: var(--dark-dark-text);
              }

              .radio-boxes {
                .radio-box {
                  input:checked + .radio-box-inner {
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

          > p {
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
