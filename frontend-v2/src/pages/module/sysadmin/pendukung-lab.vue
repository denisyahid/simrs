<template>
    <VCard>
        <div class="columns column c-title">
            <h3 class="title is-5 mb-6 ml-2 mt-2" style="z-index:1">Data Pendukung Laboratorium</h3>
        </div>
        
      <VTabs slider centered selected="kelompok" :tabs="[
        { label: 'Detail Pemeriksaan', value: 'kelompok' },
        { label: 'Jenis Pemeriksaan', value: 'jenisPemeriksaan' },
        { label: 'Satuan Hasil', value: 'satuanHasil' },
        { label: 'Nilai Normal', value: 'nilai' },
        { label: 'Mapping Ruangan To Produk', value: 'maprupro' },
 
      ]">
        <template #tab="{ activeValue }">
          <p v-if="activeValue === 'kelompok'">
            <MasterDetailPemeriksaan></MasterDetailPemeriksaan>
          </p>
          <p v-else-if="activeValue === 'jenisPemeriksaan'">
            <MasterKelompokProduk></MasterKelompokProduk>
          </p>
          <p v-else-if="activeValue === 'satuanHasil'">
            <MasterSatuanHasil></MasterSatuanHasil>
          </p>
          <p v-else-if="activeValue === 'nilai'">
            <MasterNilaiNormal></MasterNilaiNormal>
          </p>
          
          <p v-else-if="activeValue === 'maprupro'">
            <MappingRuanganToProduk></MappingRuanganToProduk>
          </p>
         
        </template>
      </VTabs>
    </VCard>
  </template>
    
  <script  setup lang="ts">
  import { useApi } from '/@src/composable/useApi'
import { ref, computed } from 'vue'
import { useRoute } from 'vue-router'
import ConfirmDialog from 'primevue/confirmdialog'
import DataTable from 'primevue/datatable'
import Column from 'primevue/column'
import { useConfirm } from 'primevue/useconfirm'
import { useHead } from '@vueuse/head'
import { useToaster } from '/@src/composable/toaster'
import InputSwitch from 'primevue/inputswitch'

import { useViewWrapper } from '/@src/stores/viewWrapper'
useHead({
    title: 'Jenis Pemeriksaan - ' + import.meta.env.VITE_PROJECT,
})
 
import MasterDetailPemeriksaan from './master-detail-pemeriksaan.vue'  
import MasterKelompokProduk from './master-kelompok-produk.vue'
  import MasterJenisPemeriksaan from './master-jenis-pemeriksaan.vue'
  import MasterSatuanHasil from './master-satuan-hasil.vue'
  import MasterNilaiNormal from './master-nilai-normal.vue'
  import * as H from '/@src/utils/appHelper'
  import MappingRuanganToProduk from './mapping-ruangan-to-produk.vue'
  useHead({
    title: 'Transmedic - Produk',
  })
  useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)
const item: any = ref({aktif: true})
const d_JenisProduk = ref([])
const modalDetail = ref(false)

let dataSource: any = ref([])
const d_View = [
    {
        name: 'Grid View',
        value: 'grid',
        icon: 'fas fa-id-card-alt',
    },
    {
        name: 'List View',
        value: 'list',
        icon: 'fas fa-list',
    },
]

const selectView: any = ref()
const confirm = useConfirm()
selectView.value = 'list'
let isLoadingButton: any = ref(false)
let isLoading: any = ref(false)

const filters = ref('')
const dataSourcefiltered = computed(() => {
    if (!filters.value) {
        return dataSource.value
    }

    return dataSource.value.filter((items: any) => {
        return (
            items.detailjenisproduk.match(new RegExp(filters.value, 'i'))
        )
    })
})

  </script>
  
  <style lang="scss">
  @import '/@src/scss/abstracts/all';
  
  @import '/@src/scss/components/forms-outer';
  
  .fs-075 {
    font-size: 0.9rem;
  }
  
  .is-navbar {
    .form-layout {
      margin-top: 30px;
    }
  }
  
  .c-title {
    margin-left: -21px;
    padding-left: 21px;
    margin-top: -21px;
    padding-top: 17px;
    border-top-left-radius: 11px;
    border-left: solid rgba(65, 185, 131, 1) 3px;
    padding-bottom: 0px;
    margin-bottom: 2rem;
  }
  
  .form-layout {
    // max-width: 540px;
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
  </style>
