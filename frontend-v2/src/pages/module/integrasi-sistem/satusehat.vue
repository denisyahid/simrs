<template>
  <VCard>
    <div class="personal-dashboard personal-dashboard-v1">
      <div class="dashboard-header">
        <VAvatar picture="/images/icons/files/satusehat-1.svg" size="large" />
        <div class="start">
          <h3>SATUSEHAT</h3>
          <p>Satu Platform Satu Standar Menuju Indonesia Sehat</p>
        </div>
        <div class="end">
          <img src="/images/avatars/svg/keluar.svg" alt=""  style="width:150px;margin-top:-3rem"/>
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
  title: 'SATUSEHAT - ' + import.meta.env.VITE_PROJECT,
})
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)
const router = useRouter()
const selectMenu = (event: any) => {

  try {
    router.push({
      name: 'module-integrasi-sistem-satusehat-' + event,
    })
  } catch (error) {
    console.error(error)
    router.push({
      name: 'module-integrasi-sistem-satusehat-404',
    })
  }

}

const items = ref([
  {
    label: 'Dashboard',
    icon: 'pi pi-fw pi-slack',
    command: () => {
      selectMenu('satset-dashboard');
    }
  },
  {
    label: 'History Cluster 1 s/d 6',
    icon: 'pi pi-fw pi-file',
    items: [
      {
        label: 'Kunjungan (Encounter)',
        icon: 'pi pi-fw pi-chevron-circle-right',
        command: () => {
          selectMenu('satset-encounter');
        }
      },
      {
        label: 'Diagnosis (Condition)',
        icon: 'pi pi-fw pi-chevron-circle-right',
        command: () => {
          selectMenu('satset-condition');
        }
      },
      {
        label: 'Tanda Vital (Observation)',
        icon: 'pi pi-fw pi-chevron-circle-right',
        command: () => {
          selectMenu('satset-observation');
        }
      },
      {
        label: 'Procedure',
        icon: 'pi pi-fw pi-chevron-circle-right',
        command: () => {
          selectMenu('satset-procedure');
        }
      },
      {
        label: 'Peresepan Obat (Medication)',
        icon: 'pi pi-fw pi-chevron-circle-right',
        command: () => {
          selectMenu('satset-medication');
        }
      },
      {
        label: 'Laboratorium',
        icon: 'pi pi-fw pi-chevron-circle-right',
        command: () => {
          selectMenu('satset-lab');
        }
      },
      {
        label: 'Radiologi',
        icon: 'pi pi-fw pi-chevron-circle-right',
        command: () => {
          selectMenu('satset-rad');
        }
      },
      {
        label: 'Resume Medis (Composition)',
        icon: 'pi pi-fw pi-chevron-circle-right',
        command: () => {
          selectMenu('satset-diet');
        }
      },
      // {
      //   label: 'Kirim Data Batching',
      //   icon: 'pi pi-fw pi-chevron-circle-right',
      //   command: () => {
      //     selectMenu('satset-batch');
      //   }
      // }
    ]
  },
  {
    label: 'Mapping Data',
    icon: 'pi pi-fw pi-pencil',
    items: [
      {
        label: 'Instalasi Ruang (Organization & Location) ',
        icon: 'pi pi-fw pi-chevron-circle-right',
        command: () => {
          selectMenu('satset-ruangan');
        }
      },
      {
        label: 'Nakes (Practitioner)',
        icon: 'pi pi-fw pi-chevron-circle-right',
        command: () => {
          selectMenu('satset-nakes');
        }
      },
      {
        label: 'Produk (Farmasi KFA, Loinc)',
        icon: 'pi pi-fw pi-chevron-circle-right',
        command: () => {
          selectMenu('satset-produk');
        }
      }
    ]
  },


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
.is-danger {
    background-color: var(--danger);
    border-color: transparent;
    color: var(--danger--color-invert);
}
</style>
