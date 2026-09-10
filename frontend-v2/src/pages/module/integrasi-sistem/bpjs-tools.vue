<template>
  <VCard>
    <div class="personal-dashboard personal-dashboard-v1">
      <div class="dashboard-header">
        <VAvatar picture="/images/icons/files/bpjs.svg" size="large" />
        <div class="start">
          <h3>BPJS Kesehatan</h3>
          <p>Badan Penyelenggara Jaminan Sosial - Kesehatan</p>
        </div>
        <div class="end">
          <VButton dark="3">View Reports</VButton>
          <VButton color="primary" elevated>Manage </VButton>
        </div>
      </div>
      <div class="dashboard-body">
        <div class="columns is-multiline">
          <div class="column is-3">
            <Menu :model="items" />
          </div>
          <div class="column is-9">
            <RouterView v-slot="{ Component }">
              <component :is="Component" />
            </RouterView>
          </div>
        </div>
      </div>
    </div>
  </VCard>
</template>
<script setup lang="ts">
import { useWindowScroll } from '@vueuse/core'
import { useApi } from '/@src/composable/useApi'
import { h, reactive, ref, computed, defineComponent, watch } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useHead } from '@vueuse/head'
import * as H from '/@src/utils/appHelper'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import AutoComplete from 'primevue/autocomplete';
import Dropdown from 'primevue/dropdown';
import Menu from 'primevue/menu';
import Badge from 'primevue/badge';
useHead({
  title: 'BPJS KESEHATAN - ' + import.meta.env.VITE_PROJECT,
})
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)
const router = useRouter()
const selectMenu = (event: any) => {
  console.log(event);
  try {
    let params = {}
    if(event == 'map-ruang'){
      params = {
        fullwidth:true
      }
    }
    router.push({
      name: 'module-integrasi-sistem-bpjs-tools-' + event,
      query:params
    })
  } catch (error) {
    console.error(error)
    router.push({
      name: 'module-integrasi-sistem-bpjs-tools-404',
    })
  }

}

const items = ref([
  {
    label: 'Dashboard Vclaim',
    icon: 'pi pi-fw pi-slack',
    command: () => {
      selectMenu('dashboard-vclaim');
    }
  },
  {
    label: 'Dashboard Antrian Online',
    icon: 'pi pi-fw pi-slack',
    command: () => {
      selectMenu('dashboard-antrol');
    }
  },
  {
    label: 'Vclaim',
    icon: 'pi pi-fw pi-file',
    items: [
      {
        label: 'Referensi',
        icon: 'pi pi-fw pi-chevron-circle-right',
        command: () => {
          selectMenu('referensi');
        }
      },
      {
        label: 'Peserta',
        icon: 'pi pi-fw pi-chevron-circle-right',
        command: () => {
          selectMenu('peserta');
        }
      },
      {
        label: 'SEP',
        icon: 'pi pi-fw pi-chevron-circle-right',
        command: () => {
          selectMenu('sep');
        }
      },
      {
        label: 'Rujukan',
        icon: 'pi pi-fw pi-chevron-circle-right',
        command: () => {
          selectMenu('rujukan');
        }
      },
      {
        label: 'Rencana Kontrol',
        icon: 'pi pi-fw pi-chevron-circle-right',
        command: () => {
          selectMenu('surkon');
        }
      },
      {
        label: 'Monitoring',
        icon: 'pi pi-fw pi-chevron-circle-right',
        command: () => {
          selectMenu('vclaim-monitoring');
        }
      },
      {
        label: 'LPK',
        icon: 'pi pi-fw pi-chevron-circle-right',
        command: () => {
          selectMenu('404');
        }
      },
      {
        label: 'PRB',
        icon: 'pi pi-fw pi-chevron-circle-right',
        command: () => {
          selectMenu('404');
        }
      },
    ]
  },
  {
    label: 'Aplicares',
    icon: 'pi pi-fw pi-file',
    items: [
      {
        label: 'Ketersediaan Tempat Tidur',
        icon: 'pi pi-fw pi-chevron-circle-right',
        command: () => {
          selectMenu('ketersedian-kamar');
        }
      }
    ]
  },
  {
    label: 'Antrean',
    icon: 'pi pi-fw pi-file',
    items: [
      {
        label: 'Monitoring',
        icon: 'pi pi-fw pi-chevron-circle-right',
        command: () => {
          selectMenu('antrol-monitoring');
        }
      },
      {
        label: 'Jadwal Dokter Hfis',
        icon: 'pi pi-fw pi-chevron-circle-right',
        command: () => {
          selectMenu('jadwal-dokter-hfis');
        }
      },
      // {
      //   label: 'Antrean',
      //   icon: 'pi pi-fw pi-chevron-circle-right',
      //   command: () => {
      //     selectMenu('404');
      //   }
      // },
      {
        label: 'Waktu Tunggu',
        icon: 'pi pi-fw pi-chevron-circle-right',
        command: () => {
          selectMenu('waktu-tunggu');
        }
      },
      {
        label: 'Dashboard',
        icon: 'pi pi-fw pi-chevron-circle-right',
        command: () => {
          selectMenu('dashboard');
        }
      },
      {
        label: 'Antrean Farmasi',
        icon: 'pi pi-fw pi-chevron-circle-right',
        command: () => {
          selectMenu('404');
        }
      }
    ]
  },
  {
    label: 'Mapping Data',
    icon: 'pi pi-fw pi-pencil',
    items: [
      {
        label: 'Ruangan',
        icon: 'pi pi-fw pi-chevron-circle-right',
        command: () => {
          selectMenu('map-ruang');
        }
      },
      {
        label: 'Dokter',
        icon: 'pi pi-fw pi-chevron-circle-right',
        command: () => {
          selectMenu('dokter');
        }
      },
      {
        label: 'Kelas',
        icon: 'pi pi-fw pi-chevron-circle-right',
        command: () => {
          selectMenu('satset-nakes');
        }
      },
      {
        label: 'Obat',
        icon: 'pi pi-fw pi-chevron-circle-right',
        command: () => {
          selectMenu('apotik-obat');
        }
      }
    ]
  },
  {
    label: 'Apotik Online',
    icon: 'pi pi-fw pi-pencil',
    items: [
      {
        label: 'Ruangan',
        icon: 'pi pi-fw pi-chevron-circle-right',
        command: () => {
          selectMenu('apotik-ruangan');
        }
      },
      {
        label: 'Obat DPHO',
        icon: 'pi pi-fw pi-chevron-circle-right',
        command: () => {
          selectMenu('apotik-obat-dpho');
        }
      },
      {
        label: 'Monitoring',
        icon: 'pi pi-fw pi-chevron-circle-right',
        command: () => {
          selectMenu('apotik-apotik-monitoring');
        }
      }
    ]
   }


]);

</script>



<style lang="scss">
@import '/@src/scss/abstracts/all';

.p-menu {
  width: 100%;
}

.is-navbar {
  .personal-dashboard {
    margin-top: 30px;
  }
}

.personal-dashboard-v1 {
  .dashboard-header {
    display: flex;
    align-items: center;
    font-family: var(--font);
    margin-bottom: 30px;

    .start {
      margin-left: 12px;

      h3 {
        font-family: var(--font-alt);
        font-weight: 600;
        font-size: 1.3rem;
        color: var(--dark-text);
      }
    }

    .end {
      margin-left: auto;
      display: flex;
      justify-content: flex-end;

      .button {
        margin-left: 10px;
      }
    }
  }

  .dashboard-body {
    .dashboard-card {
      @include vuero-s-card;

      font-family: var(--font);

      >h4,
      .ApexCharts-title-text {
        font-family: var(--font-alt);
        font-size: 1rem;
        font-weight: 600;
        color: var(--dark-text);
        margin-bottom: 12px;
      }

      .quick-stats {
        .quick-stats-inner {
          display: flex;
          flex-wrap: wrap;
          margin-left: -8px;
          margin-right: -8px;

          .quick-stat {
            width: calc(50% - 16px);
            padding: 40px 20px;
            background: var(--widget-grey);
            margin: 8px;
            border-radius: var(--radius-large);
            transition: all 0.3s; // transition-all test

            ::v-deep(.media-flex-center) {
              .flex-meta {
                span {
                  &:first-child {
                    font-size: 1.4rem;
                    font-weight: 600;
                    color: var(--dark-text);
                  }
                }
              }
            }
          }
        }
      }
    }

    .dashboard-card {
      &.is-upgrade {
        position: relative;
        display: flex;
        justify-content: center;
        align-items: center;
        background: var(--primary-light-8);
        border-color: var(--primary-light-8);
        padding: 20px 40px;
        min-height: 320px;
        border-radius: var(--radius-large);
        box-shadow: var(--primary-box-shadow);

        .lnil,
        .lnir {
          position: absolute;
          bottom: 1rem;
          right: 1rem;
          font-size: 4rem;
          opacity: 0.3;
        }

        .cta-content {
          text-align: center;

          h4 {
            font-family: var(--font-alt);
            font-weight: 600;
            font-size: 1.2rem;
            color: var(--smoke-white);
            margin-bottom: 8px;
          }
        }

        .link {
          display: block;
          font-family: var(--font-alt);
          font-weight: 500;
          margin-top: 0.5rem;

          &:hover,
          &:focus {
            color: var(--smoke-white);
            opacity: 0.6;
          }
        }
      }

      &.is-gauge {
        position: relative;

        .people {
          position: absolute;
          top: 80px;
          left: 0;
          right: 0;
          margin: 0 auto;
          display: flex;
          justify-content: center;
          z-index: 5;

          .v-avatar {
            margin: 0 4px;
          }
        }
      }
    }
  }
}

.is-dark {
  .personal-dashboard-v1 {
    .dashboard-header {
      .start {
        h3 {
          color: var(--dark-dark-text);
        }
      }
    }

    .dashboard-body {
      .dashboard-card {
        @include vuero-card--dark;

        &.is-upgrade {
          background: var(--primary);
          border-color: var(--primary);
          box-shadow: var(--primary-box-shadow);
        }

        .quick-stats {
          .quick-stats-inner {
            .quick-stat {
              background: var(--dark-sidebar-light-2);
              border: 1px solid var(--dark-sidebar-light-12);

              .media-flex-center {
                .flex-meta {
                  span {
                    &:first-child {
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
  .personal-dashboard-v1 {
    .dashboard-header {
      text-align: center;
      flex-direction: column;

      .start {
        margin: 10px auto;
      }

      .end {
        margin: 0;
        justify-content: space-between;
      }
    }

    .dashboard-body {
      .dashboard-card {
        .quick-stats {
          .quick-stats-inner {
            .quick-stat {
              padding: 20px;

              .media-flex-center {
                flex-direction: column;

                .flex-meta {
                  margin: 10px 0;
                  text-align: center;
                }
              }
            }
          }
        }
      }
    }
  }
}

@media only screen and (min-width: 768px) and (max-width: 1024px) and (orientation: landscape) {
  .personal-dashboard-v1 {
    .dashboard-body {
      .dashboard-card {
        .quick-stats {
          .quick-stats-inner {
            .quick-stat {
              padding: 20px;

              .media-flex-center {
                flex-direction: column;

                .flex-meta {
                  margin: 2px 0 0;
                  text-align: center;
                }
              }
            }
          }
        }
      }
    }
  }
}
</style>
