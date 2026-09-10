
<template>
    <VCard radius="small">
      <div class="columns column">
        <h3 class="title is-5 mb-2 mr-1">Daftar Tagihan Belum Lunas </h3> <span> ( {{ ds_PASIEN.total }}
          Results)</span>
      </div>
      <div class="columns  all-projects m-3 mt-0">
        <div class="columns is-multiline  projects-card-grid">
          <div class="column is-9">
            <div class="flex-list-inner mb-4" v-if="ds_PASIEN.loading">
              <div class="flex-table-item grid-item mb-4" v-for="key in 5" :key="key">
                <VFlexTableCell :column="{ grow: true, media: true }">
                  <VPlaceloadAvatar size="medium" />
                  <VPlaceloadText :lines="2" width="30%" last-line-width="20%" class="mx-2" />
                </VFlexTableCell>
                <VFlexTableCell>
                  <VPlaceload width="100%" height="70px" class="mx-1 mt-2" />
                </VFlexTableCell>
                <VFlexTableCell>
                  <VPlaceload width="10%" height="20px" class="mx-1 mt-1" />
                  <VPlaceload width="10%" height="20px" class="mx-1 mt-1" />
                  <VPlaceload width="10%" height="20px" class="mx-1 mt-1" />
                  <VPlaceload width="10%" height="20px" class="mx-1 mt-1" />
                </VFlexTableCell>
                <VFlexTableCell :column="{ align: 'end' }">
                  <VPlaceload width="10%" class="mx-1" />
                </VFlexTableCell>
              </div>
            </div>
            <div class="flex-list-inner" v-else-if="ds_PASIEN.length === 0">
              <VPlaceholderSection title="Not found" subtitle="There is no data that match your query." class="my-6">
                <template #image>
                  <img class="light-image" src="/@src/assets/illustrations/placeholders/search-4.svg" alt="" />
                  <img class="dark-image" src="/@src/assets/illustrations/placeholders/search-4-dark.svg" alt="" />
                </template>
              </VPlaceholderSection>
            </div>
            <div v-else-if="ds_PASIEN.length > 0">
  
  
              <div class="grid-item mb-4" v-for="items in ds_PASIEN" :key="items.noRec">
                <div class="top-section">
                  <div class="head">
                    <div class="title-wrap">
                      <div class="columns">
                        <div class="column is-3">
                          <VAvatar size="small" picture="/images/avatars/svg/orang.svg" />
                        </div>
                        <div class="column is-12 mr-3">
                          <h3>{{ items.namaPasien }}</h3>
                          <p>{{ items.noRegistrasi + (items.jeniskelamin == 'PEREMPUAN' ? ' (P)' : ' (L)') }}</p>
                        </div>
                      </div>
                    </div>
                    <ProjectCardDropdown />
                  </div>
                  <div class="body">
                    <div class="columns">
                      <div class="column">
                        <h4 class="heading">Ruangan</h4>
                        <p class="fs-075">{{ items.lastRuangan }}</p>
                        <p class="fs-075">{{ items.kelasRawat }}</p>
                      </div>
                      <div class="column">
                        <h4 class="heading">Tanggal</h4>
                        <p class="fs-075">Masuk : {{ items.tglMasuk }}</p>
                        <p class="fs-075">Keluar : {{ items.tglPulang }}</p>
                      </div>
                      <div class="column">
                        <h4 class="heading">Tipe Pasien</h4>
                        <VTag :label="items.jenisPasien" color="info" />
                      </div>
                      <div class="column">
                        <h4 class="heading">Total Bayar</h4>
                        <h4 class="heading">Rp {{ formatRp(items.totalBayar, '') }}</h4>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="bottom-section">
                  <div class="foot-block">
                    <h4 class="heading">Action</h4>
                    <div class="developers">
                      <!-- <VButton type="button" icon="feather:eye" class="is-fullwidth mr-3" color="success" outlined raised :to="{
                                                name: 'module-kasir-billing',
                                                query: {
                                                    norec_pd: ds_PASIEN.norec_pd,
                                                },
                                            }">
                        Rincian Tagihan </VButton> -->
                        <VButton type="button" icon="fas fa-notes-medical" class="is-fullwidth mr-3"
                                            color="info" outlined raised :to="{
                                                name: 'module-kasir-input-pembayaran-pasien',
                                                query: {
                                                    norec_pd: items.noRec,
                                                },
                                            }">
                                            Bayar Tagihan </VButton>
                      <VButton type="button" icon="feather:trash" class="is-fullwidth mr-3" color="danger" outlined
                        raised>
                        Pengembalian Deposit </VButton>
                      <VButton type="button" icon="fa fa-history" class="is-fullwidth mr-3" color="warning" outlined
                        raised>
                        Kwitansi Layanan </VButton>
                    </div>
                  </div>
                </div>
  
              </div>
            </div>
            <VFlexPagination v-model:current-page="currentPage.page" :item-per-page="currentPage.limit"
              :total-items="ds_PASIEN.total < 5 ? ds_PASIEN.total : 50" :max-links-displayed="5">
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
                        </select>
                      </div>
                    </VControl>
                  </VField>
                </VFlex>
              </template>
            </VFlexPagination>
            <!-- <VFlexPagination v-model:current-page="currentPage.page" class="mt-6" :item-per-page="currentPage.limit"
              :total-items="currentPage.rows" :max-links-displayed="10" /> -->
  
          </div>
          <div class="column is-3">
            <div class="columns is-multiline">
              <div class="column is-12">
                <VField>
                  <VControl icon="feather:search">
                    <input v-model="item.qnama" v-on:keyup.enter="filter()" type="text" class="input is-rounded"
                      placeholder="Filter Nama..." />
                  </VControl>
                </VField>
              </div>
              <div class="column is-6">
                <h3 class="title is-5 mb-2 mr-1">Filters </h3>
              </div>
              <div class="column is-6">
                <a @click="clearFilter()" type="button" class="is-pulled-right mr-3" color="info" outlined raised> Clear
                  All </a>
              </div>
              <div class="column is-12">
                <VField>
                  <VLabel>No RM</VLabel>
                  <VControl icon="feather:user">
                    <VInput type="text" v-model="item.qnocm" v-on:keyup.enter="filter()" placeholder="No RM" />
                  </VControl>
                </VField>
              </div>
              <div class="column is-12">
                <VField>
                  <VLabel>NIK</VLabel>
                  <VControl icon="feather:book">
                    <VInput type="text" v-model="item.qnik" v-on:keyup.enter="filter()" placeholder="NIK" />
                  </VControl>
                </VField>
              </div>
              <div class="column is-12">
                <VField>
                  <VLabel>No BPJS</VLabel>
                  <VControl icon="feather:book">
                    <VInput type="text" v-model="item.qbpjs" v-on:keyup.enter="filter()" placeholder="No BPJS" />
                  </VControl>
                </VField>
              </div>
              <div class="column is-12">
                <VField>
                  <VLabel>Alamat</VLabel>
                  <VControl icon="feather:map">
                    <VInput type="text" v-model="item.qalamat" v-on:keyup.enter="filter()" placeholder="Alamat" />
                  </VControl>
                </VField>
              </div>
              <div class="column is-12">
                <VButton @click="filter()" :loading="ds_PASIEN.loading" type="button" icon="feather:search"
                  class="is-fullwidth mr-3" color="info" raised> Apply Filters
                </VButton>
              </div>
            </div>
  
          </div>
        </div>
      </div>
  
    </VCard>
  
  </template>
  <route lang="yaml">
  meta:
    requiresAuth: true
  </route>
  <script setup lang="ts">
  import { formatRp } from '/@src/utils/appHelper'
  import { useWindowScroll } from '@vueuse/core'
  import { useApi } from '/@src/composable/useApi'
  import { ref, computed, watch } from 'vue'
  import { useRoute, useRouter } from 'vue-router'
  import { useHead } from '@vueuse/head'
  import { useToaster } from '/@src/composable/toaster'
  import { useViewWrapper } from '/@src/stores/viewWrapper'
import { elements } from '/@src/data/landing/components'
  useHead({
    title: 'Tagihan Pasien - Transmedic',
  })
  useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
  const total = ref(0)
  const date = ref(new Date())
  const item: any = {}
  
  let ds_PASIEN: any = ref([])
  
  const route = useRoute()
  
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
    fetchTagihan()
  })
  
  async function fetchTagihan() {
   const respon =  await useApi().get(`/dashboard/tagihan-belum-lunas`)
   console.log(respon.length)
   for (let x = 0; x < respon.length; x++) {
        const element = respon[x];
        // item.norec = element.noRec
        // let ini = element.namapasien.split(' ')
        // let init = element.namapasien.substr(0, 1)
        // if (ini.length > 1) {
        //     init = init + ini[1].substr(0, 1)
        // }
        // element.initials = init
    }
    ds_PASIEN.value = respon

    // .then((response: any) => {
    //   item.value = response
    //   ds_PASIEN.value = response
    //   console.log( ds_PASIEN._valu[0])
    //   ds_PASIEN.value.total = response.length
    // })
}

// function billingPasien() {
//   router.push({
//     name: 'module-kasir-billing',
//     query: {
//       norec_pd: selectedRegistrasi.value.norec,
//       nocmfk: selectedRegistrasi.value.nocmfk,
//     }
//   })
// }
  
  function resetForm() {
  
  }
  function clearFilter() {
    delete item.qnama
    delete item.qnocm
    delete item.qnik
    delete item.qbpjs
    delete item.qalamat
    fetchTagihan()
  }
  function filter() {
    fetchTagihan()
  }
  fetchTagihan()

  
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

  .tag:not(body) {
    align-items: center;
    background-color: hsl(0deg, 0%, 96%);
    border-radius: var(--radius);
    color: hsl(0deg, 0%, 29%);
    display: inline-flex;
    font-size: 0.75rem;
    height: 2em;
    justify-content: center;
    line-height: 1.5;
    padding-left: 0.75em;
    padding-right: 0.75em;
    white-space: nowrap;
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
              font-size: 0.8rem;
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
  