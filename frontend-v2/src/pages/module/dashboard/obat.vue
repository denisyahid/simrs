
<template>
  <div class="business-dashboard hr-dashboard">
    <div class="columns">
      <div class="column is-8">
        <div class="columns is-multiline">
          <!--Header-->
          <div class="column is-12">
            <div class="block-header" style="background-color: #215E43;height: 17rem;">
              <div class="columns is-multiline">
                <div class="column is-6 p-0">
                  <img src="/images/avatars/label/dashboard-obat.png" style=" position: relative;top: -24px;overflow: hidden;max-width: 65%;">
                </div>
                <div class="column is-6">
                  <div class="column">
                    <span style="color:#F7F7F7"><i class="fas fa-capsules mr-3" aria-hidden="true"
                        style="color:#F7F7F7"></i>
                      Obat</span>
                    <h3 class="pt-3 pb-1 title-dash">Layanan Obat</h3>
                  </div>
                  <div class="column pt-0">
                    <VField class="is-autocomplete-select">
                      <VControl icon="feather:search">
                        <AutoComplete v-model="item.namaObat" :suggestions="d_Obat" @complete="fetchObat($event)"
                          :optionLabel="'nama'" :dropdown="true" :minLength="3" :appendTo="'body'"
                          @item-select="search(item.namaObat)" :loadingIcon="'pi pi-spinner'" :field="'nama'"
                          placeholder="Cari Nama Obat" />
                      </VControl>
                    </VField>
                    <!-- <VField class="column is-10 is-autocomplete-select p-0">
                      <VControl icon="feather:search">
                        <Multiselect mode="single" placeholder="Pilih Ruangan" :searchable="true" />
                      </VControl>
                    </VField> -->
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!--Selector-->
          <div class="column is-12">
            <div class="columns is-multiline">
              <div class="column is-4">
                <VCard>
                  <VBlock title="Stok Produk">
                    <template #icon>
                      <VIconBox color="blue" rounded>
                        <i class="fas fa-boxes" aria-hidden="true"></i>
                      </VIconBox>
                    </template>
                    <VPlaceload class="mx-2 mt-3" v-if="isLoadCount" />
                    <span v-else style="font-weight: 500;margin-top: 7px;font-size: 15px;">{{ item.totalProduk }}</span>
                  </VBlock>
                </VCard>
              </div>
              <div class="column is-4">
                <VCard>
                  <VBlock title="Harga Jual">
                    <template #icon>
                      <VIconBox color="success" rounded>
                        <i class="fas fa-coins" aria-hidden="true"></i>
                      </VIconBox>
                    </template>
                    <VPlaceload class="mx-2 mt-3" v-if="isLoadCount" />
                    <span v-else style="font-weight: 500;margin-top: 7px;font-size: 15px;">{{
                      H.formatRp(item.hargaJual, 'Rp.') }}</span>
                  </VBlock>
                </VCard>
              </div>
              <div class="column is-4">
                <VCard>
                  <VBlock title="Harga Beli">
                    <template #icon>
                      <VIconBox color="warning" rounded>
                        <i class="fas fa-coins" aria-hidden="true"></i>
                      </VIconBox>
                    </template>
                    <VPlaceload class="mx-2 mt-3" v-if="isLoadCount" />
                    <span v-else style="font-weight: 500;margin-top: 7px;font-size: 15px;">{{
                      H.formatRp(item.hargaBeli, 'Rp.') }}</span>
                  </VBlock>
                </VCard>
              </div>
            </div>

          </div>

          <!--Side Text-->
          <div class="column is-6">
            <div class="updates mt-0">
              <!--Header-->
              <div class="updates-header">
                <h3 class="dark-inverted">Informasi Produk</h3>
              </div>

              <div class="updates-list">
                <div class="update-item is-dark-bordered-12">
                  <p> Asal Produk</p>
                  <VPlaceload class="mx-2 mt-3" v-if="isLoadCount" width="50%" />
                  <span v-else class="dark-inverted">{{ item.asalProduk }}</span>
                </div>
                <div class="update-item is-dark-bordered-12">
                  <p>Gudang</p>
                  <VPlaceload class="mx-2 mt-3" v-if="isLoadCount" width="50%" />
                  <span v-else class="dark-inverted">{{ item.gudang }}</span>
                </div>
                <div class="update-item is-dark-bordered-12">
                  <p>Satuan Standar</p>
                  <VPlaceload class="mx-2 mt-3" v-if="isLoadCount" width="50%" />
                  <span v-else class="dark-inverted">{{ item.satuanStandar }}</span>
                </div>
                <!--Update-->
                <div class="update-item is-dark-bordered-12">
                  <p>Tanggal Kadaluarsa</p>
                  <VPlaceload class="mx-2 mt-3" v-if="isLoadCount" width="50%" />
                  <span v-else class="dark-inverted">{{ item.tglKadaluarsa }}</span>
                </div>
                <div class="update-item is-dark-bordered-12">
                  <p>Nomor Struk Terima</p>
                  <VPlaceload class="mx-2 mt-3" v-if="isLoadCount" width="50%" />
                  <span v-else class="dark-inverted">{{ item.nostruk }}</span>
                </div>
              </div>
            </div>
          </div>

          <div class="column is-6">
            <div class="columns is-multiline">
              <div class="column is-6 p-0">
                <VTag color="primary" label="Kartu Stok" rounded elevated />
              </div>
              <div class="column is-6 p-0" style="text-align:end">
                <VTag @click="modalFilter = true" color="danger" rounded elevated style=" cursor:pointer">{{
                  H.formatDateToLocalString(item.periode.start) == H.formatDateToLocalString(item.periode.end) ?
                  H.formatDateToLocalString(item.periode.start) :
                  H.formatDateToLocalString(item.periode.start) + ' - ' + (item.periode.end ?
                    H.formatDateToLocalString(item.periode.end) : '')
                }} <i class="fas fa-filter ml-3" aria-hidden="true"></i></VTag>
              </div>

            </div>

            <!-- <VTag color="primary" label="Kartu Stok" rounded elevated style="margin-left:auto"/> -->

            <div class="column p-0" style="max-height: 23rem;overflow-y: auto;">

              <VCard class="mt-3" v-for="data in 3" v-if="isLoadKartuStok">
                <div class="exerpt" @click="showModal(data)" style="cursor:pointer">
                  <h5>
                    <VPlaceload class="mx-2 mb-3" width="80%" />
                  </h5>
                  <VPlaceloadText :lines="3" last-line-width="25%" />
                  <VPlaceload class="mx-2 mt-3" width="50%" />
                </div>
              </VCard>

              <VCard class="mt-3" v-else v-for="(data, i) in dataSourceKartu" :key="i">
                <div class="exerpt" @click="showModal(data)" style="cursor:pointer">
                  <h5>
                    <i aria-hidden="true" class="fas fa-circle" color="primary"></i>
                    <span class="pl-2" style="font-size:.8rem">{{ data.notif }}</span>
                  </h5>
                  <p>{{ data.keterangan }}
                  </p>
                  <small>{{ H.formatDateIndoSimple(data.tglinput) }}</small>
                </div>
              </VCard>
            </div>
          </div>

        </div>
      </div>

      <div class="column is-4">
        <!--Widget-->

      </div>
    </div>
  </div>

  <VModal :open="modalDetailKS" size="small" actions="right" @close="modalDetailKS = false">
    <template #content>
      <div class="column">
        <VField label="Saldo Awal">
          <VControl>
            <VInput v-model="item.saldoAwal" type="text" />
          </VControl>
        </VField>
      </div>
      <div class="column">
        <VField label="Saldo Masuk">
          <VControl>
            <VInput v-model="item.saldoMasuk" type="text" />
          </VControl>
        </VField>
      </div>
      <div class="column">
        <VField label="Saldo Keluar">
          <VControl>
            <VInput v-model="item.saldoKeluar" type="text" />
          </VControl>
        </VField>
      </div>
      <div class="column">
        <VField label="Saldo Akhir">
          <VControl>
            <VInput v-model="item.saldoAkhir" type="text" />
          </VControl>
        </VField>
      </div>
    </template>
  </VModal>

  <VModal :open="modalFilter" title="Filter Periode" :noclose="true" size="small" actions="right"
    @close="modalFilter = false">
    <template #content>
      <form class="modal-form">
        <div class="columns">
          <div class="column is-12" style="text-align: center">
            <VField class="is-centered">
              <v-date-picker v-model="item.periode" class="is-centered" is-range trim-weeks :max-date="new Date()" />
            </VField>
          </div>
        </div>
      </form>
    </template>
    <template #action>
      <VButton icon="feather:search" @click="filterByDate()" :loading="isLoading" color="primary" raised>
        Filter</VButton>
    </template>
  </VModal>
</template>


<!-- dashboard/obat/stok-obat -->

<script setup lang="ts">
import { useRoute, useRouter } from 'vue-router'
import { todoList3, todoList4 } from '/@src/data/widgets/list/todoList'
import { ref, computed, watch, reactive } from 'vue'
import { useThemeColors } from '/@src/composable/useThemeColors'
import AutoComplete from 'primevue/autocomplete';
import * as H from '/@src/utils/appHelper'

import { useApi } from '/@src/composable/useApi'
import { useUserSession } from '/@src/stores/userSession'
import { useHead } from '@vueuse/head'
import { useViewWrapper } from '/@src/stores/viewWrapper'
useHead({
  title: 'Dashboard Alat Kesehatan - ' + import.meta.env.VITE_PROJECT,
})
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)

const themeColors = useThemeColors()
const userLogin = useUserSession().getUser()
const total = ref(0)
const router = useRouter()
const isLoadCount = ref(true)
const isLoadKartuStok = ref(true)
const modalFilter = ref(false)
const d_Obat = ref([])
const item: any = ref({
  aktif: true,
  periode: reactive({
    start: new Date(),
    end: new Date(),
  }),
})

let dataSource: any = ref([])
let dataSourceKartu: any = ref([])
let isLoading: any = ref(false)
let isLoadingTT: any = ref(false)
let modalDetailKS: any = ref(false)
const filters = ref('')

const fetchObat = async (filter: any) => {

  let namaObat = filter ? `?namaobat=${filter.query}` : ''
  console.log(namaObat)
  await useApi().get(`dashboard/data-obat${namaObat}`).then((response) => {
    d_Obat.value = response
    if (!filter) {
      item.value.namaObat = response[0]
      detailObat(item.value.namaObat)
      getKartuStok(item.value.namaObat)
      getObatByUsia(item.value.namaObat)
    }
  })

}

const detailObat = (e: any) => {
  isLoadCount.value = true
  item.value.satuanStandar = e.satuanstandar
  item.value.gudang = e.gudang
  useApi().get(`farmasi/get-produkdetail?produkfk=${e.id}&ruanganfk=${e.ruanganfk}`).then((response) => {
    item.value.totalProduk = response.jmlstok
    item.value.hargaJual = response.detail[0].hargajual
    item.value.hargaBeli = response.detail[0].harganetto
    item.value.nostruk = response.detail[0].nostruk ? response.detail[0].nostruk : '-'
    item.value.tglKadaluarsa = H.formatDateToLocalString(response.detail[0].tglkadaluarsa)
    item.value.asalProduk = response.detail[0].asalproduk
    isLoadCount.value = false
  })
}

const getKartuStok = (e: any) => {
  isLoadKartuStok.value = true
  let tglAwal = 'tglawal=' + H.formatDate(item.value.periode.start, 'YYYY-MM-DD')
  let tglAkhir = '&tglakhir=' + H.formatDate(item.value.periode.end, 'YYYY-MM-DD')
  let produkfk = e ? e.id : item.value.namaObat.id
  let ruanganfk = e ? e.ruanganfk : item.value.namaObat.ruanganfk
  useApi().get(`dashboard/obat/get-kartu-stok?${tglAwal}${tglAkhir}&produkfk=${produkfk}&ruanganfk=${ruanganfk}`).then((response) => {
    dataSourceKartu.value = response
    isLoadKartuStok.value = false
  })

}

const getObatByUsia = async (e:any)=>{
  let produkfk = e ? e.id : item.value.namaObat.id
  let ruanganfk = e ? e.ruanganfk : item.value.namaObat.ruanganfk
  await useApi().get(`dashboard/get-obat-by-age?produkfk=${produkfk}&ruanganfk=${ruanganfk}`)
}

const search = (e: any) => {
  detailObat(e)
  getKartuStok(e)
  getObatByUsia(e)
}

const filterByDate = (e: any) => {
  getKartuStok(e)
  modalFilter.value = false
}

const showModal = (e: any) => {
  modalDetailKS.value = true
  item.value.saldoAwal = e.saldoawal
  item.value.saldoAkhir = e.saldoakhir
  item.value.saldoKeluar = e.saldokeluar
  item.value.saldoMasuk = e.saldomasuk
}

fetchObat()


</script>


<style lang="scss">
@import '/@src/scss/abstracts/all';

.exerpt h5 {
  display: flex;
  align-items: center;
  font-size: .5rem;
  font-family: var(--font-alt);
  font-weight: 600;
  color: var(--primary);
  margin-bottom: 6px;
}

.updates {
  @include vuero-l-card;

  padding: 20px;
  margin-top: 8px;

  .updates-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 20px;

    h3 {
      font-family: var(--font-alt);
      font-weight: 600;
      font-size: 1.1rem;
      color: var(--dark-text);
    }

    .action-link {
      font-size: 0.9rem;
    }
  }

  .updates-list {
    .update-item {
      display: flex;
      align-items: center;
      justify-content: space-between;
      margin-bottom: 16px;
      padding-bottom: 16px;
      border-bottom: 1px solid var(--fade-grey-dark-3);

      &:last-child {
        margin-bottom: 0;
        border-bottom: none;
      }

      p {
        font-size: 0.9rem;
      }

      span {
        display: block;
        min-width: 60px;
        text-align: right;
        font-family: var(--font);
        font-weight: 600;
        font-size: 0.8rem;
        color: var(--dark-text);
      }
    }
  }
}

.cc {
  .tabs-inner {
    .tabs {
      background-color: #faf8f8 !important;
    }
  }
}

.title-dash {
  color: #FCFCFC;
  font-family: "Montserrat";
  font-style: normal;
  font-weight: 700;
  font-size: 22.7301px;
  line-height: 28px;
}

.label-dark {
  font-family: var(--font-alt);
  // font-size: rem;
  font-weight: 600;
  color: var(--dark-text);
}

.label-soft {
  font-size: .8rem;
  font-weight: 600;
  color: var(--muted-grey);
  text-transform: uppercase;
}

.hr-dashboard {
  .block-header {
    display: flex;
    border-radius: 16px;
    padding: 50px;
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
      padding-right: 30px;
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
        font-size: 1.1rem;
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

.user-grid {
  .columns {
    margin-left: -0.5rem !important;
    margin-right: -0.5rem !important;
    margin-top: -0.5rem !important;
  }

  .column {
    padding: 0.5rem !important;
  }

  .grid-item {
    position: relative;
    @include vuero-s-card;

    text-align: center;

    &:hover,
    &:focus {
      .button-wrap {
        >div {
          a {
            opacity: 1;
            pointer-events: all;
          }
        }
      }
    }

    .dropdown {
      position: absolute;
      top: 10px;
      right: 10px;
      text-align: left;
    }

    >.v-avatar {
      display: block;
      margin: 0 auto 4px;
    }

    h3 {
      font-family: var(--font-alt);
      font-size: 1.1rem;
      font-weight: 600;
      color: var(--dark-text);
    }

    p {
      font-size: 0.85rem;
    }

    .button-wrap {
      margin: 20px 0 0;

      .v-button {
        width: 100%;
        max-width: 140px;
        margin: 0 auto;
      }

      >div {
        margin: 6px 0 0;

        a {
          opacity: 0;
          pointer-events: none;
          color: var(--light-text);
          font-weight: 500;
          font-size: 0.9rem;
          transition: opacity 0.3s, color 0.3s;

          &:hover,
          &:focus {
            color: var(--primary);
          }
        }
      }
    }
  }
}

.is-dark {
  .user-grid {
    .grid-item {
      @include vuero-card--dark;
    }
  }

  .hr-dashboard {
    .block-header {
      background: var(--dark-sidebar);
      box-shadow: none;

      .center {
        border-color: var(--dark-sidebar-light-10);

        .candidates {
          button {
            background: var(--dark-sidebar-light-10);
            border: 1px solid transparent;
            transition: all 0.3s; // transition-all test

            &:hover {
              border-color: var(--primary);

              svg {
                color: var(--primary);
              }
            }
          }
        }
      }
    }

    .feed-settings {
      .button {
        &.is-selected {
          background: var(--primary) !important;
          border-color: var(--primary) !important;
          box-shadow: var(--primary-box-shadow) !important;
          color: var(--white) !important;
        }
      }
    }

    .recent-rookies {
      .user-grid {
        &.user-grid-v4 {
          .grid-item {
            @include vuero-card--dark;
          }
        }
      }
    }
  }
}

@media only screen and (max-width: 767px) {
  .hr-dashboard {
    .block-header {
      flex-direction: column;
      padding: 30px;

      .left,
      .center,
      .right {
        width: 100%;
      }

      .left {
        justify-content: flex-start;
        margin-bottom: 20px;
      }

      .center {
        padding-right: 0;
        margin-right: 0;
        border-right: none;
        margin-bottom: 20px;
      }
    }

    .feed-settings {
      flex-direction: column;

      h3 {
        margin-bottom: 16px;
      }
    }
  }
}

@media only screen and (min-width: 768px) and (max-width: 1024px) and (orientation: portrait) {
  .hr-dashboard {
    .block-header {
      padding: 40px;
    }

    .side-text {
      display: none;
    }
  }
}

@media only screen and (min-width: 768px) and (max-width: 1024px) and (orientation: landscape) {
  .hr-dashboard {
    .block-header {
      padding: 40px;

      .left {
        .current-user {
          h3 {
            font-size: 1.5rem;
          }
        }
      }

      .center {
        .candidates {
          .v-avatar {
            &:nth-child(3) {
              display: none;
            }
          }
        }
      }
    }

    .column {
      &.is-7 {
        &.is-offset-1 {
          margin-left: 2% !important;
          width: 64.3333% !important;
        }
      }
    }
  }
}
</style>
