

<template>
  <ConfirmDialog />
  <VCard>
    <div class="columns column">
      <h3 class="title is-5 mb-2 mr-1">Daftar Retur Barang Suplier </h3>
    </div>
    <VButton color="info" RouterLink :to="{ name: 'module-logistik-distribusi-barang' }" raised
      style="margin-left: 40rem;"><i class="fas fa-plus mr-3 p-0" aria-hidden="true"></i>Kirim Barang</VButton>
    <div class="business-dashboard flights-dashboard">
      <div class="columns">
        <div class="column is-8">

          <div class="flights" v-for="(items, i) in ds_Barang" :key="items.id">
            <!--Flight-->

            <a class="flight-card">
              <div class="start">
                <span>{{ items.namaruanganasal }}</span>
                <span>Ruang Asal</span>

              </div>

              <div class="route">
                <div class="departure"></div>
                <div class="line" :data-content="H.formatDateIndo(items.tglstruk)"></div>


                <div class="arrival" style="transform: none;">
                  <i aria-hidden="true" class="fas fa-dolly"></i>
                </div>
              </div>

              <div class="end">

                <span>{{ items.namaruangantujuan }}</span>
                <span>Ruang Tujuan</span>
              </div>
              <VTag :label="items.status" color="primary" />

            </a>
            <div class="flights-summary-wrapper" style="margin-top: -2.5rem;">
              <div class="columns is-flex-tablet-p">
                <div class="column is-12">
                  <a class="flight-summary">
                    <div class="collapse-icon is-clickable" @click="items.isExpand = true" v-if="!items.isExpand">
                      <VIcon icon="feather:chevron-down" />
                    </div>
                    <div class="collapse-icon  is-clickable" open @click="items.isExpand = false" v-if="items.isExpand">
                      <VIcon icon="feather:chevron-up" />
                    </div>
                    <div class="meta">
                      <span>{{ items.jeniskirim }}</span>
                      <span>Jenis Order</span>
                    </div>
                    <div class="meta" style="margin-left: 30px;">
                      <span>{{ items.nostruk }}</span>
                      <span>Nomor Kirim</span>
                    </div>
                    <div class="meta" style="margin-left: 30px;">
                      <span style="text-align: center;">{{ items.qtyBarang }}</span>
                      <span>Jumlah Barang</span>
                    </div>

                    <div class="meta" style="margin-left: 12rem;">
                      <VIconButton v-tooltip.bottom.center="'Edit Barang'" icon="feather:edit" color="warning" raised
                        circle class="mr-2" @click="editDis(items)">
                      </VIconButton>
                      <VIconButton v-tooltip.bottom.right="'Batal Kirim Barang'" icon="feather:trash" color="danger"
                        raised circle class="mr-2" @click="batalKirim(items)">
                      </VIconButton>

                      <VIconButton v-tooltip.bottom.right="'Cetak Bukti'" icon="feather:printer" color="primary" raised
                        circle class="mr-2">
                      </VIconButton>

                      <VIconButton v-tooltip.bottom.right="'Retur Barang'" icon="fas fa-undo" color="success" raised
                        @click="gotoRetur(items)" circle class="mr-2">
                      </VIconButton>
                    </div>

                  </a>
                </div>
              </div>
              <div class="columns  is-flex-tablet-p" style="margin-top: -2.5rem;" v-if="items.isExpand">
                <div class="column is-12">
                  <a class="flight-summary">
                    <div class="content-wrap is-grey is-fullwidth" style="width: 100%;">
                      <VCard custom="card-green" class="mt-1">
                        <div class="columns  " v-for="(itemsDet, index2)  in items.details" :key="index2">
                          <div class="column is-8">
                            <VField>
                              <VLabelText>Nama Produk
                              </VLabelText>
                              <label>{{ itemsDet.namaproduk }} | {{ itemsDet.satuanstandar }}</label>
                            </VField>
                          </div>
                          <div class="column is-4">
                            <VField>
                              <VLabelText>Jumlah </VLabelText>
                              <VLabel>{{ itemsDet.qtyproduk }}
                              </VLabel>
                            </VField>
                          </div>
                        </div>
                      </VCard>

                    </div>
                  </a>
                </div>
              </div>
            </div>
          </div>
          <VFlexPagination v-model:current-page="currentPage.page" :item-per-page="currentPage.limit"
            :total-items="ds_Barang.total < 5 ? ds_Barang.total : 50" :max-links-displayed="5">
            <template #before-pagination>
            </template>
            <template #before-navigation>
              <VFlex class="mr-4 mt-1" column-gap="1rem">
                <VField>

                </VField>
                <VField>
                  <VControl>
                    <div class="select is-rounded">
                      <select v-model="currentPage.limit">
                        <option :value="1">1 results per page</option>
                        <option :value="5">5 results per page</option>
                        <option :value="10">10 results per page</option>
                        <option :value="15">15 results per page</option>
                        <option :value="25">25 results per page</option>
                        <option :value="50">50 results per page</option>
                        <option :value="100">100 results per page</option>
                      </select>
                    </div>
                  </VControl>
                </VField>
              </VFlex>
            </template>
          </VFlexPagination>

        </div>



        <div class="column is-4" style="margin-top : -3rem">
          <Vcard>
            <div class="columns is-multiline">
              <div class="column is-12">
                <VField>
                  <VControl icon="feather:search">
                    <input v-model="item.noorder" v-on:keyup.enter="filter()" type="text" class="input is-rounded"
                      placeholder="Cari Nomor Order..." />
                  </VControl>
                </VField>
              </div>
              <div class="column is-6">
                <h3 class="title is-5 mb-2 mr-1">Filter </h3>
              </div>
              <div class="column is-6">
                <a @click="clearFilter()" type="button" class="is-pulled-right mr-3" color="info" outlined raised> Clear
                  All </a>
              </div>
              <div class="column is-12">
                <VField>
                  <VLabel>Tanggal Kirim Barang</VLabel>
                  <VDatePicker v-model="item.filterTgl" is-range color="pink" trim-weeks :max-date="new Date()">
                    <template #default="{ inputValue, inputEvents }">
                      <VField addons>
                        <VControl icon="feather:calendar">
                          <VInput :value="inputValue.start" v-on="inputEvents.start" />
                        </VControl>
                        <VControl>
                          <VButton static><i class="fas fa-arrow-right" aria-hidden="true"></i></VButton>
                        </VControl>
                        <VControl icon="feather:calendar">
                          <VInput :value="inputValue.end" v-on="inputEvents.end" />
                        </VControl>
                      </VField>
                    </template>
                  </VDatePicker>
                </VField>
              </div>
              <div class="column is-12">
                <VField class="is-autocomplete-select" v-slot="{ id }">
                  <VLabel>Produk</VLabel>
                  <VControl icon="feather:search">
                    <Multiselect mode="single" v-model="item.produkfk" :options="d_produk" placeholder="Pilih data"
                      :searchable="true" :attrs="{ id }" />
                  </VControl>
                </VField>
              </div>
              <div class="column is-12">
                <VField class="is-autocomplete-select" v-slot="{ id }">
                  <VLabel>Ruangan Asal</VLabel>
                  <VControl icon="feather:search">
                    <Multiselect mode="single" v-model="item.ruanganasalfk" :options="d_ruangan" placeholder="Pilih data"
                      :searchable="true" :attrs="{ id }" />
                  </VControl>
                </VField>
              </div>
              <div class="column is-12">
                <VField class="is-autocomplete-select" v-slot="{ id }">
                  <VLabel>Ruangan Tujuan</VLabel>
                  <VControl icon="feather:search">
                    <Multiselect mode="single" v-model="item.ruangantujuanfk" :options="d_rutu" placeholder="Pilih data"
                      :searchable="true" :attrs="{ id }" />
                  </VControl>
                </VField>
              </div>
              <div class="column is-12">
                <VButton @click="filter()" :loading="ds_Barang.loading" type="button" icon="feather:search"
                  class="is-fullwidth mr-3" color="info" raised> Cari Data
                </VButton>
              </div>
            </div>
          </Vcard>
        </div>


      </div>
    </div>
    <VModal :open="modalBatalKirim" title="Batal Kirim" size="medium" actions="right" @close="modalBatalKirim = false"
      cancelLabel="Tutup">
      <template #content>

        <div class="columns is-multiline">
          <div class="column is-12">

            <div class="column is-5" style="margin-left:-1rem; margin-bottom: -2rem;">
              <img src="/images/avatars/label/dashboard/logistik.png" style="width: 80%;">
            </div>
            <div class="column is-8" style="margin-top: -9rem; margin-left: 12rem;">
              <span style="margin-bottom:1rem;font-weight: bold; font-size: 12px; font-family: var(--font-alt);">Alasan
                Pembatalan
              </span>
              <br />

              <VField>
                <VControl>
                  <VTextarea class="textarea is-rounded" v-model="item.keterangan" rows="4"
                    placeholder="Alasan Pembatalan Pengiriman Barang" autocomplete="off" autocapitalize="off"
                    spellcheck="true" />
                </VControl>
              </VField>
            </div>

          </div>
        </div>
      </template>
      <template #action>
        <VButton icon="feather:plus" color="primary" @click="saveBatal()" :loading="isLoading" raised>Simpan
        </VButton>
      </template>
    </VModal>
  </VCard>
</template>


<script setup lang="ts">
import { useWindowScroll } from '@vueuse/core'
import { useApi } from '/@src/composable/useApi'
import { reactive, ref, computed, defineComponent, watch } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useHead } from '@vueuse/head'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useThemeColors } from '/@src/composable/useThemeColors'
import { useConfirm } from 'primevue/useconfirm'
import ConfirmDialog from 'primevue/confirmdialog'
import * as H from '/@src/utils/appHelper'
useHead({
  title: 'Daftar Order Barang - ' + import.meta.env.VITE_PROJECT,
})
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
const item: any = ref({
  filterTgl: reactive({
    start: new Date(),
    end: new Date(),
  })
})

let listKelompokPasien: any = ref([])

let ds_Barang: any = ref([])
let listColor: any = ref(Object.keys(useThemeColors()))
let isLoading: any = ref(false)
const router = useRouter()
const confirm = useConfirm()
const route = useRoute()
const d_ruangan: any = ref([])
const d_rutu: any = ref([])
const d_produk: any = ref([])
const d_satuan: any = ref([])
const modalBatalKirim = ref(false)
const d_jenisKirim: any = ref([])
const d_satuanResep: any = ref([])
const { y } = useWindowScroll()
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
watch(currentPage.value, () => {
  fetchOrder()
})

const fetchOrder = async () => {
  ds_Barang.value.loading = true
  let limit: any = currentPage.value.limit
  let offset: any = route.query.page ? route.query.page : 1
  offset = offset * limit - limit
  let rows: any = currentPage.value.rows
  let noorder = ''
  if (item.value.noorder) {
    noorder = `&noorder=${item.value.noorder}`
  }
  let ruanganasalfk = ''
  if (item.value.ruanganasalfk) {
    ruanganasalfk = `&ruanganasalfk=${item.value.ruanganasalfk}`
  }
  let ruangantujuanfk = ''
  if (item.value.ruangantujuanfk) {
    ruangantujuanfk = `&ruangantujuanfk=${item.value.ruangantujuanfk}`
  }
  let produkfk = ''
  if (item.value.produkfk) {
    produkfk = `&produkfk=${item.value.produkfk}`
  }
  let dari = ''
  if (item.value.filterTgl.start) {
    dari = `&dari=${H.formatDate(item.value.filterTgl.start, 'YYYY-MM-DD')}`
  }
  let sampai = ''
  if (item.value.filterTgl.end) {
    sampai = `&sampai=${H.formatDate(item.value.filterTgl.end, 'YYYY-MM-DD')}`
  }
  await useApi().get(
    `/logistik/daftar-distribusi?offset=${offset}&limit=${limit}&rows=${rows}${dari}${sampai}${noorder}${produkfk}${ruanganasalfk}${ruangantujuanfk}`).then((response) => {
      response.daftar.forEach((element: any, i: any) => {
        element.namaproduk = element.details
        element.jumlah = element.details
        element.qtyBarang = element.details.length
      });
      ds_Barang.value.loading = false
      ds_Barang.value = response.daftar
      ds_Barang.value.total = response.daftar.length
      console.log(response.daftar.length)
    })
}

const hapusItems = async (e: any) => {
  useApi().post(
    `/logistik/hapus-order-barang`, { norec: e.norec }).then((response: any) => {
      isLoading.value = false
      fetchOrder()
    }).catch((e: any) => {
      isLoading.value = false
    })
}

const DialogConfirm = (e: any) => {
  confirm.require({
    message: 'Apakah Anda yakin menghapus data ini ?',
    header: 'Konfirmasi Hapus Data',
    icon: 'pi pi-info-circle',
    acceptClass: 'p-button-danger',
    accept: () => {
      hapusItems(e)

    },
    reject: () => { },
  })
}

const editDis = (e: any) => {
  router.push({
    name: 'module-logistik-distribusi-barang',
    query: {
      norec: e.norec,
    },
  })
}

const gotoRetur = (e: any) => {
  router.push({
    name: 'module-logistik-retur-kirim-barang',
    query: {
      norec: e.norec,
    },
  })
}

const batalKirim = (e: any) => {
  item.value.noorderfk = e.noorderfk
  item.value.norec = e.norec
  item.value.ruasid = e.ruasalid
  item.value.rutujuanid = e.rutujuanid
  item.value.jenispermintaanfk = e.jenispermintaanfk
  item.value.jmlitem = e.jmlitem
  item.value.nostruk = e.nostruk
  item.value.namaruangantujuan = e.namaruangantujuan
  item.value.namaruanganasal = e.namaruanganasal
  modalBatalKirim.value = true

}

const saveBatal = async () => {
  let param = {
    'strukkirim': {
      'noorderfk': item.value.noorderfk,
      'noreckirim': item.value.norec,
      'objectruanganasal': item.value.ruasid,
      'ruangantujuan': item.value.rutujuanid,
      'namaruanganasal': item.value.namaruanganasal,
      'namaruangantujuan': item.value.namaruangantujuan,
      'jenispermintaanfk': item.value.jenispermintaanfk,
      'nostruk': item.value.nostruk,
      'jmlitem': item.value.jmlitem,
      'keterangan': item.value.keterangan,
    }
  }
  isLoading.value = true
  await useApi().post('/dashboard/logistik/batal-kirim-barang', param).then((response: any) => {
    isLoading.value = false
    fetchOrder()
    clearFilter()
  }, (error) => {
    isLoading.value = false
  })
}


const loadDrop = async () => {
  const response = await useApi().get(
    `/logistik/list-order-cbo`)
  d_produk.value = response.produk.map((e: any) => { return { label: e.namaproduk, value: e.id } })
  d_ruangan.value = response.ruangan.map((e: any) => { return { label: e.namaruangan, value: e.id } })
  d_jenisKirim.value = response.jenis.map((e: any) => { return { label: e.jenis, value: e.id } })
  d_satuanResep.value = response.satuanresep.map((e: any) => { return { label: e.satuanresep, value: e } })
  d_rutu.value = response.rutu.map((e: any) => { return { label: e.namaruangan, value: e.id } })
}

const clearFilter = () => {
  delete item.value.noorder
  delete item.value.ruangantujuanfk
  delete item.value.produkfk
  delete item.value.ruanganasalfk

  delete item.value.keterangan

  modalBatalKirim.value = false
  fetchOrder()
}
const filter = () => {
  fetchOrder()
}

fetchOrder()
loadDrop()

</script>

<style lang="scss">
@import '/@src/scss/abstracts/all';

.txt-elipsis-2 {
  width: 500px !important;
}

.flights-dashboard {
  .booking-bar-wrapper {
    @include vuero-s-card;

    position: relative;
    background: var(--primary);
    border-color: var(--primary);
    padding: 30px;
    color: var(--white);
    font-family: var(--font);
    box-shadow: var(--primary-box-shadow);

    .travel-illustration {
      position: absolute;
      bottom: 30px;
      right: 30px;
      max-width: 260px;
    }

    .booking-bar-info {
      display: flex;
      align-items: center;
      margin-bottom: 12px;

      .lnil {
        font-size: 2.2rem;
        color: var(--white);
      }

      .inner {
        margin-left: 16px;

        .booking-bar-heading {
          font-family: var(--font-alt);
          font-size: 1.4rem;
          font-weight: 600;
          line-height: 1.2;
          color: var(--white);
        }

        .booking-bar-sub-heading {
          font-family: var(--font);
          font-weight: 500;
          font-size: 1.1rem;
          color: var(--white);
        }
      }
    }

    .booking-bar {
      display: flex;
      justify-content: space-between;
      align-items: center;

      .booking-bar-inputs {
        display: flex;
        align-items: center;

        .control:not(:last-of-type) {
          margin-right: 2rem;
        }

        .input {
          font-family: var(--font);
          color: var(--light-text);
        }
      }
    }
  }

  .flights-toolbar {
    padding: 16px 0;
    display: flex;
    align-items: center;
    justify-content: space-between;

    h3 {
      font-family: var(--font);
      font-weight: 600;
      color: var(--dark-text);
    }
  }

  .flights-summary-wrapper {
    .flight-summary {
      @include vuero-s-card;

      display: flex;
      align-items: center;
      border: 1px solid var(--fade-grey);
      transition: all 0.3s; // transition-all test

      &:hover {
        border-color: var(--primary);
      }

      &.is-featured {
        background: var(--primary);
        border-color: var(--primary);
        box-shadow: var(--primary-box-shadow);

        .flight-price {
          color: var(--white);
        }

        .meta {
          span {
            color: var(--white) !important;
          }
        }
      }

      .flight-price {
        font-family: var(--font);
        font-size: 2rem;
        font-weight: 700;
        color: var(--primary);

        &::before {
          content: '$';
          position: relative;
          right: 0;
          font-size: 1.2rem;
          font-weight: 700;
        }
      }

      .meta {
        margin-left: 16px;
        line-height: 1.3;

        span {
          display: block;

          &:first-child {
            font-family: var(--font-alt);
            color: var(--dark-text);
            font-size: 0.8rem;
            font-weight: 700;
            text-transform: uppercase;
          }

          &:nth-child(2) {
            font-family: var(--font);
            color: var(--light-text);
            font-size: 0.9rem;
          }
        }
      }
    }
  }

  .flights {
    padding: 1.5rem 0;

    .flight-card {
      @include vuero-s-card;

      display: flex;
      align-items: center;
      margin-bottom: 1.5rem;
      box-shadow: none;

      &:hover,
      &:focus {
        box-shadow: var(--light-box-shadow);

        .route {
          .line {

            &::before,
            &::after {
              opacity: 1;
            }
          }
        }
      }

      >img {
        display: block;
        border-radius: var(--radius-rounded);
        max-width: 32px;
      }

      .start,
      .end {
        span {
          display: block;
          color: var(--dark-text);
          font-family: var(--font);

          &:first-child {
            font-family: var(--font-alt);
            font-size: 0.9rem;
            font-weight: 600;
            color: var(--dark-text);
          }

          &:nth-child(2),
          &:nth-child(3) {
            font-family: var(--font);
            font-size: 0.9rem;
            color: var(--light-text);
          }
        }
      }

      .start {
        margin-left: 1rem;
      }

      .end {
        margin-left: auto;
        margin-right: 1.5rem;
      }

      .route {
        flex-grow: 2;
        display: flex;
        align-items: center;
        max-width: 320px;
        margin: 0 auto;
        padding: 0 1rem;

        .departure {
          height: 16px;
          width: 16px;
          border-radius: var(--radius-rounded);
          border: 1px solid var(--light-text);
        }

        .line {
          position: relative;
          flex-grow: 2;
          height: 1px;
          border-bottom: 1.6px dashed var(--light-text);

          &::before,
          &::after {
            opacity: 0;
            pointer-events: none;
            transition: all 0.3s; // transition-all test
          }

          &::before {
            content: '';
            position: absolute;
            top: -14px;
            left: 50%;
            right: 50%;
            height: 10px;
            width: 1px;
            border-right: 1px solid var(--light-text);
          }

          &::after {
            content: attr(data-content);
            position: absolute;
            top: -32px;
            left: 23%;
            width: 130px;
            font-size: 0.8rem;
            text-align: center;
            color: var(--light-text);
          }
        }

        .arrival {
          font-size: 1.8rem;
          transform: rotate(90deg);
          height: 26px;
          width: 26px;
          display: flex;
          justify-content: center;
          align-items: center;

          .lnil {
            position: relative;
            top: -4px;
            right: 5px;
            color: var(--light-text);
            margin-left: 0.75rem;
          }
        }
      }

      .flight-price {
        font-family: var(--font);
        font-size: 1.8rem;
        font-weight: 600;
        color: var(--light-text);

        &::before {
          content: '$';
          position: relative;
          top: -8px;
          right: 0;
          font-size: 1.1rem;
          font-weight: 700;
        }
      }
    }
  }

  .company-card {
    @include vuero-s-card;

    padding: 30px;
    margin-bottom: 1.5rem;

    .v-avatar {
      display: block;
      margin: 0 auto;
    }
  }

  .filters-card {
    @include vuero-s-card;

    padding: 30px;
    margin-bottom: 1.5rem;

    .checkboxes-list {
      margin: 0;
      padding: 20px 0 0;

      .field {
        >h5 {
          font-family: var(--font-alt);
          font-weight: 600;
          font-size: 0.8rem;
          text-transform: uppercase;
          color: var(--dark-text);
          padding-bottom: 6px;
        }
      }

      .checkbox {
        padding: 8px 0;
      }
    }
  }
}

.is-dark {
  .flights-dashboard {
    .booking-bar-wrapper {
      @include vuero-card--dark;

      background: var(--dark-sidebar-light-4);
      border-color: var(--dark-sidebar-light-12);
      box-shadow: var(--light-box-shadow);
    }

    .flights-summary-wrapper {
      .flight-summary {
        @include vuero-card--dark;

        &:hover,
        &:focus {
          border-color: var(--primary) !important;
        }

        &.is-featured {
          background: var(--primary) !important;
          border-color: var(--primary) !important;
          box-shadow: var(--primary-box-shadow) !important;

          .flight-price {
            color: var(--white);
          }
        }

        .flight-price {
          color: var(--primary);
        }

        .meta {
          span {
            &:first-child {
              color: var(--dark-dark-text);
            }
          }
        }
      }
    }

    .flights {
      .flight-card {
        @include vuero-card--dark;

        .start,
        .end {
          span {
            &:first-child {
              color: var(--dark-dark-text);
            }
          }
        }
      }
    }

    .company-card,
    .filters-card {
      @include vuero-card--dark;
    }
  }
}

@media only screen and (max-width: 767px) {
  .flights-dashboard {
    .booking-bar-wrapper {
      .travel-illustration {
        position: static;
        margin-bottom: 20px;
      }

      .booking-bar {
        >div {
          width: 100%;
        }

        .booking-bar-inputs {
          flex-direction: column;
          width: 100%;

          .control {
            margin: 0 !important;
            width: 100% !important;

            &:first-child {
              margin-bottom: 1rem !important;
            }
          }
        }
      }
    }

    .flights {
      padding-bottom: 0;

      .flight-card {
        flex-direction: column;

        &:last-child {
          margin-bottom: 0;
        }

        >img {
          max-width: 48px;
          margin-bottom: 12px;
        }

        .start,
        .end {
          text-align: center;
          margin: 10px auto;

          span {
            font-size: 1rem !important;
          }
        }

        .route {
          width: 100%;
          max-width: 100%;

          .line {

            &::before,
            &::after {
              display: none !important;
            }
          }
        }
      }
    }
  }
}

@media only screen and (min-width: 768px) and (max-width: 1024px) and (orientation: portrait) {
  .flights-dashboard {
    .booking-bar-wrapper {
      .travel-illustration {
        bottom: 30px;
        right: -25px;
        max-width: 215px;
      }
    }

    .flights {
      padding-bottom: 0;

      .flight-card {
        &:last-child {
          margin-bottom: 0;
        }
      }
    }
  }
}

@media only screen and (min-width: 768px) and (max-width: 1024px) and (orientation: landscape) {
  .flights-dashboard {
    .booking-bar-wrapper {
      .travel-illustration {
        bottom: 30px;
        right: -12px;
        max-width: 200px;
      }
    }

    .flights-summary-wrapper {
      .flight-summary {
        .flight-price {
          font-size: 2.2rem;
        }
      }
    }
  }
}

@media only screen and (max-width: 1300px) and (orientation: landscape) {
  .flights-dashboard {
    .flights-summary-wrapper {
      .flight-summary {
        .flight-price {
          font-size: 2.2rem;
        }
      }
    }
  }
}
</style>
