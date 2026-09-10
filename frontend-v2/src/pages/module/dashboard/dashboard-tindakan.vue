<template>
  <div class="lifestyle-dashboard lifestyle-dashboard-v4">
    <div class="columns">
      <div class="column is-8">
        <div class="columns is-multiline">
          <!--Header-->
          <div class="column is-12">
            <div class="illustration-header-2">
              <div class="header-image">
                <img src="/@src/assets/illustrations/dashboards/lifestyle/doctor.svg" alt=''
                  style="max-width: 75%; margin-left: 3rem; margin-bottom: 3rem; margin-top: -2rem;" />
              </div>
              <div class="header-meta">
                <h3>Tindakan</h3>
                <p>
                  Selamat Datang , {{ userLogin.pegawai.namaLengkap }}
                </p>

                <VField class="is-autocomplete-select">
                  <VControl icon="feather:search">
                    <AutoComplete v-model="item.filterTindakan" :suggestions="d_Tindakan"
                      @complete="fetchTindakan($event)" :optionLabel="'nama'" :dropdown="true" :minLength="3"
                      :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'nama'" placeholder="Cari Tindakan"
                      @item-select="changeTindakan(item.filterTindakan)" />
                  </VControl>
                </VField>
              </div>
            </div>

          </div>

          <!--Content-->
          <div class="column is-7">
            <div class="writing-stats">
              <!--Stat-->
              <div class="writing-stat">
                <span>Pasien</span>
                <span class="dark-inverted">{{ item.jumlahPasien }}</span>
              </div>
              <!--Stat-->
              <div class="writing-stat">
                <span>Dokter</span>
                <span class="dark-inverted">{{ item.jumlahDokter }}</span>
              </div>
              <!--Stat-->
              <div class="writing-stat">
                <span>Harga</span>
                <span class="dark-inverted"> {{ H.formatRp(dataKomponen.hargasatuan, 'Rp.') }}</span>
              </div>
            </div>

            <div class="featured-authors">
              <!--Header-->
              <div class="featured-authors-header">
                <h3 class="dark-inverted">Pelayanan Pasien</h3>
              </div>

              <div class="featured-authors-list">
                <!--Item-->
                <div v-for="detail in detailPasien" class="featured-authors-item">
                  <VBlock :title="(detail.namapasien)" :subtitle="(detail.noregistrasi)" center>
                    <template #icon>
                      <VAvatar picture="/images/avatars/svg/pasien.svg" squared />
                    </template>
                    <template #action>
                      <VTag color="primary" :label="(detail.jumlah)" rounded elevated style="margin-right: 2rem;" />
                      <span class="dark-inverted">{{ H.formatRp(detail.total, 'Rp.') }}</span>
                    </template>
                  </VBlock>
                </div>
              </div>
            </div>
          </div>

          <!--Content-->
          <div class="column is-5">
            <VCard radius="rounded" style="margin-top:10px;padding: 15px;">
              <VControl class="prime-auto">
                <Calendar inputId="range" v-model="item.qPeriode" selectionMode="range" :manualInput="false" class="w-100"
                  :showIcon="true" />
              </VControl>
            </VCard>

            <div class="updates" style="margin-top: 1.5rem;">
              <!--Header-->
              <div class="updates-header">
                <h3 class="dark-inverted">Dokter Pelaksana</h3>
              </div>
              <div class="updates-list">
                <!--Update-->
                <div v-for="dok in detailDokter" class="update-item is-dark-bordered-12">
                  <p>{{ dok.namaruangan }}</p>
                  <VTag color="primary" :label="(dok.jumlah)" rounded elevated style="margin-right: 1rem;" />
                  <span class="dark-inverted">{{ dok.namalengkap }}</span>
                </div>


              </div>
            </div>
          </div>
        </div>
      </div>

      <!--Feed-->
      <div class="column is-4">
        <div class="column is-12">
          <div class="updates">
            <div class="updates-header">
              <h3 class="dark-inverted">Grafik Ruang Pelayanan</h3>
            </div>
            <ApexChart id="apex-chart-22" :height="250" :type="'pie'" :series="chartLO.series" :options="chartLO">
            </ApexChart>

          </div>
        </div>
        <div class="column is-12" style="margin-top: -1rem;">
          <!--Updates-->
          <div class="updates">
            <!--Header-->
            <div class="updates-header">
              <h3 class="dark-inverted">Informasi {{ dataKomponen.namaproduk }}</h3>
            </div>
            <div class="updates-list">
              <!--Update-->
              <div class="update-item is-dark-bordered-12">
                <p>Harga - {{ dataKomponen.namakelas }} </p>
                <span class="dark-inverted">
                  {{ H.formatRp(dataKomponen.hargasatuan, 'Rp.') }}</span>
              </div>
              <div class="update-item is-dark-bordered-12">
                <p>Jenis Pelayanan </p>
                <span class="dark-inverted">
                  {{ dataKomponen.jenispelayanan ? dataKomponen.jenispelayanan : 'Tidak Ada Data' }}</span>
              </div>
              <div class="update-item is-dark-bordered-12">
                <p>Jenis Tarif </p>
                <span class="dark-inverted">
                  {{ dataKomponen.jenistarif ? dataKomponen.jenistarif : 'Tidak Ada Data' }}</span>
              </div>
              <div v-for="data in detailKomponen" class="update-item is-dark-bordered-12">
                <p>{{ data.komponenharga }}</p>
                <span class="dark-inverted">{{ H.formatRp(data.hargasatuan, 'Rp.') }}</span>
              </div>


            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { onceImageErrored } from '/@src/utils/via-placeholder'
import { useRoute, useRouter } from 'vue-router'
import { ref, computed, reactive, watch, inject } from 'vue'
import { useThemeColors } from '/@src/composable/useThemeColors'
import { useApi } from '/@src/composable/useApi'
import { useUserSession } from '/@src/stores/userSession'
import { useHead } from '@vueuse/head'
import moment, { isDate } from 'moment'
import ApexChart from 'vue3-apexcharts'
import * as H from '/@src/utils/appHelper'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import Calendar from 'primevue/calendar';
import AutoComplete from 'primevue/autocomplete';

useHead({
  title: 'Tindakan' + import.meta.env.VITE_PROJECT,
})
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)

const userLogin = useUserSession().getUser()
const themeColors = useThemeColors()

let d_Kelas: any = ref([])
let d_Tindakan: any = ref([])
let dataKomponen: any = ref({})
let detailPasien: any = ref([])
let detailDokter: any = ref([])
let detailKomponen: any = ref([])
let isLoading: any = ref(false)
let chartLO: any = ref({
    series: []
})

const item: any = ref({
  jumlahPasien: 0,
  jumlahDokter: 0,
  qPeriode: [
    new Date(),
    new Date()
  ],
})

const fetchTindakan = (filter: any) => {
  let nama = filter ? `?filterTindakan=${filter.query}` : ''
  useApi().get(`dashboard/data-tindakan${nama}`).then((response) => {
    d_Tindakan.value = response
    if (!filter) {
      item.value.filterTindakan = response[0]
    }
  })
}

const fetchKomponen = async (e: any) => {
  console.log(e)
  isLoading.value = true
  dataKomponen.value = []
  await useApi().get(`/dashboard/detail-tindakan?tindakanid=${e.id}&kelasid=${e.kelasid}`).then((response) => {
    isLoading.value = false
    dataKomponen.value = response.head
    detailKomponen.value = response.detail
  })
}

const fetchDetail = async (e: any) => {
  let dari = '', sampai = ''
  if (item.value.qPeriode) {
    if (item.value.qPeriode[0]) {
      dari = H.formatDate(item.value.qPeriode[0], 'YYYY-MM-DD 00:00')
    }
    if (item.value.qPeriode[1]) {
      sampai = H.formatDate(item.value.qPeriode[1], 'YYYY-MM-DD 23:59')
    } else {
      sampai = H.formatDate(item.value.qPeriode[0], 'YYYY-MM-DD 23:59')
    }
  }

  detailPasien.value = []
  detailDokter.value = []
  isLoading.value = true
  await useApi().get(`/dashboard/count?dari=${dari}&sampai=${sampai}&tindakanid=${e.id}&kelasid=${e.kelasid}`).then((response) => {
    isLoading.value = false
    detailPasien.value = response.detailPasien
    detailDokter.value = response.detailDokter
    item.value.jumlahPasien = response.jumlahPasien
    item.value.jumlahDokter = response.jumlahDokter

    chartLO.value = {
                series: response.chartLO.series,
                chart: {
                    height: 300,
                    type: 'pie',
                },
                colors: [
                    themeColors.accent,
                    themeColors.info,
                    themeColors.green,
                    themeColors.purple,
                    themeColors.orange,
                ],
                labels: response.chartLO.labels,
                responsive: [
                    {
                        breakpoint: 270,
                        options: {
                            chart: {
                                width: 300,
                                toolbar: {
                                    show: false,
                                },
                            },
                            legend: {
                                position: 'bottom',
                            },
                        },
                    },
                ],
                legend: {
                    position: 'bottom',
                    horizontalAlign: 'center',
                },
            }
  })

}

const changeTindakan = async (e: any) => {
  for (let x = 0; x < d_Tindakan.value.length; x++) {
    const element = d_Tindakan.value[x];
    if (e == element.value) {
      item.value.filterTindakan = element.nama
      break
    }
  }
  fetchKomponen(e)
  fetchDetail(e)
}


fetchTindakan()
</script>

<style lang="scss">
@import '/@src/scss/abstracts/all';

.label-dark {
  font-family: var(--font-alt);
  // font-size: rem;
  font-weight: 600;
  color: var(--dark-text);
}

.label-soft {
  font-size: .8rem;
  font-weight: 400;
  color: var(--muted-grey);
  text-transform: uppercase;
}

.lifestyle-dashboard-v4 {
  .illustration-header-2 {
    display: flex;
    align-items: center;
    padding: 10px;
    border-radius: 16px;
    background: var(--primary-dark-24);
    font-family: var(--font);
    box-shadow: var(--primary-box-shadow);

    .header-image {
      position: relative;
      height: 175px;
      width: 320px;

      img {
        position: absolute;
        top: 0;
        left: -40px;
        display: block;
        pointer-events: none;
      }
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

  .writing-stats {
    display: flex;
    margin-bottom: 1rem;
    margin-left: -8px;
    margin-right: -8px;

    .writing-stat {
      @include vuero-l-card;

      margin: 8px;
      width: calc(33.3% - 16px);
      padding: 12px;

      span {
        display: block;

        &:first-child {
          font-family: var(--font-alt);
          font-size: 0.8rem;
          font-weight: 500;
          text-transform: uppercase;
          margin-bottom: 5px;
          color: var(--light-text);
        }

        &:nth-child(2) {
          font-family: var(--font);
          font-weight: 700;
          font-size: 1rem;
          color: var(--dark-text);
        }
      }
    }
  }

  .featured-authors {
    @include vuero-l-card;

    padding: 20px;

    .featured-authors-header {
      display: flex;
      align-items: center;
      justify-content: space-between;
      margin-bottom: 30px;

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

    .featured-authors-list {
      .featured-authors-item {
        &:not(:last-child) {
          margin-bottom: 20px;
        }

        .media-flex-center {
          .flex-end {
            span {
              font-family: var(--font-alt);
              font-weight: 600;
              color: var(--dark-text);
            }
          }
        }
      }
    }
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

  .articles-feed {
    background: var(--widget-grey);
    padding: 30px;
    border-radius: 12px;

    .articles-feed-header {
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

    .articles-feed-subheader {
      margin-bottom: 20px;

      .selector {
        .button {
          font-size: 0.8rem;
          border-radius: 50px;
          margin-right: 4px;

          &.is-selected {
            background: var(--primary);
            color: var(--white);
            border-color: var(--primary);
            box-shadow: var(--primary-box-shadow);
          }
        }
      }
    }

    .articles-feed-list {
      .articles-feed-list-inner {
        .articles-feed-item {
          display: block;

          &:not(:last-child) {
            margin-bottom: 20px;
          }

          .featured-image {
            height: 180px;
            overflow: hidden;
            border-top-left-radius: 18px;
            border-top-right-radius: 18px;

            img {
              display: block;
              width: 100%;
              height: 100%;
              object-fit: cover;
            }
          }

          .featured-content {
            position: relative;
            padding: 25px;
            border-radius: 18px;
            background: var(--white);
            margin-top: -40px;
            z-index: 1;

            h4,
            p {
              margin-bottom: 10px;
            }

            h4 {
              font-family: var(--font-alt);
              font-size: 1rem;
              font-weight: 600;
              color: var(--dark-text);
            }

            .media-flex-center {
              .flex-meta {
                span {
                  font-size: 0.8rem;
                }
              }
            }
          }
        }
      }
    }
  }
}

.is-dark {
  .lifestyle-dashboard-v4 {
    .illustration-header-2 {
      background: var(--dark-sidebar);
      box-shadow: none;
    }

    .writing-stats {
      .writing-stat {
        @include vuero-card--dark;
      }
    }

    .updates,
    .featured-authors {
      @include vuero-card--dark;
    }

    .articles-feed {
      background: var(--dark-sidebar-light-8);

      .articles-feed-subheader {
        .selector {
          .button {
            &.is-selected {
              background: var(--primary) !important;
              border-color: var(--primary) !important;
              box-shadow: var(--primary-box-shadow) !important;
              color: var(--white) !important;
            }
          }
        }
      }

      .articles-feed-list {
        .articles-feed-list-inner {
          .articles-feed-item {
            .featured-content {
              background: var(--dark-sidebar);
            }
          }
        }
      }
    }
  }
}

.tabs-wrapper.is-slider .tabs,
.tabs-wrapper-alt.is-slider .tabs {
  position: relative;
  background: var(--fade-grey-light-2);
  border: 1px solid var(--fade-grey);
  max-width: 300px;
  height: 35px;
  border-bottom: none;

}

@media only screen and (max-width: 767px) {
  .lifestyle-dashboard-v4 {
    .illustration-header-2 {
      flex-direction: column;
      text-align: center;

      .header-image {
        height: auto;
        width: 100%;

        img {
          position: relative;
          width: 100%;
          max-width: 260px;
          margin: 0 auto;
          top: 0;
          left: 0;
          margin-top: -34px;
        }
      }

      .header-meta {
        padding: 20px;

        >p {
          max-width: 280px;
          margin-left: auto;
          margin-right: auto;
        }
      }
    }

    .writing-stats {
      .writing-stat {
        text-align: center;
      }
    }
  }
}

@media only screen and (min-width: 768px) and (max-width: 1024px) and (orientation: portrait) {
  .lifestyle-dashboard-v4 {
    .articles-feed {
      .articles-feed-list {
        .articles-feed-list-inner {
          display: flex;
          flex-wrap: wrap;
          margin-left: -12px;
          margin-right: -12px;

          .articles-feed-item {
            width: calc(50% - 24px);
            margin: 12px;
          }
        }
      }
    }
  }
}

@media only screen and (min-width: 768px) and (max-width: 1024px) and (orientation: landscape) {
  .lifestyle-dashboard-v4 {
    .updates {
      .updates-list {
        .update-item {
          >span {
            display: none;
          }
        }
      }
    }

    .articles-feed {
      padding: 20px;
    }
  }
}
</style>
