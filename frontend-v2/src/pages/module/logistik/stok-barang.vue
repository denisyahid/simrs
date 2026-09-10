<template>
  <VCard>
    <div class="columns column">
      <h3 class="title is-5 mb-2 mr-1">Kartu Stok {{ item.namaProdukHeader }}</h3>
    </div>
    <div class="columns">
      <div class="column is-8">
        <DataTable :value="dataSource" :paginator="true" :rows="10" :rowsPerPageOptions="[5, 10, 25]"
          paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
          responsiveLayout="stack" breakpoint="960px" class="p-datatable-sm" sortMode="multiple"
          currentPageReportTemplate="Showing {first} to {last} of {totalRecords}">
          <Column field="no" header="No"></Column>
          <Column field="tglpelayanan" header="Tgl Transaksi"></Column>
          <Column field="namaproduk" header="Nama Barang"></Column>
          <Column field="namaruangan" header="Ruangan"></Column>
          <Column field="tglkadaluarsa" header="Tgl Expired"></Column>
          <Column header="Status">
            <template #body="slotProps">
              <VTag :color="slotProps.data.cBG" :label="slotProps.data.status" rounded />
            </template>

          </Column>
          <Column field="total" header="Jumlah" style="text-align:center"></Column>
        </DataTable>
      </div>
      <div class="column is-4">
        <div class="columns is-multiline">
          <div class="column is-6">
            <h3 class="title is-5 mb-2 mr-1">Filters</h3>
          </div>
          <div class="column is-6">
            <a @click="clearFilter()" type="button" class="is-pulled-right mr-3" color="info" outlined raised>
              Clear All
            </a>
          </div>
          <div class="column is-12">
            <VField>
              <VLabel>Periode</VLabel>
              <VDatePicker v-model="item.periode" is-range color="pink" trim-weeks>
                <template #default="{ inputValue, inputEvents }">
                  <VField addons>
                    <VControl icon="feather:calendar">
                      <VInput :value="inputValue.start" v-on="inputEvents.start" />
                    </VControl>
                    <VControl>
                      <VButton static icon="feather:arrow-right" />
                    </VControl>
                    <VControl subcontrol icon="feather:calendar">
                      <VInput :value="inputValue.end" v-on="inputEvents.end" />
                    </VControl>
                  </VField>
                </template>
              </VDatePicker>
            </VField>
          </div>
          <!-- <div class="column is-12">
            <VField>
              <VLabel>Id Produk</VLabel>
              <VControl icon="feather:user">
                <VInput
                  type="text"
                  v-model="item.idProduk"
                  v-on:keyup.enter="filter()"
                  placeholder="id"
                />
              </VControl>
            </VField>
          </div> -->
          <div class="column is-12">
            <VField class="is-autocomplete-select">
              <VLabel>Ruangan</VLabel>
              <VControl icon="feather:search">
                <Multiselect mode="single" v-model="item.objectruanganfk" :options="listRuangan"
                  placeholder="Pilih Ruangan" :searchable="true" />
              </VControl>
            </VField>
          </div>
          <div class="column is-12">
            <VField class="is-autocomplete-select">
              <VLabel>Kelompok Produk</VLabel>
              <VControl icon="feather:search">
                <Multiselect mode="single" v-model="item.objectkelompokprodukfk" :options="listKelompokProduk"
                  placeholder="Kelompok Produk" :searchable="true" />
              </VControl>
            </VField>
          </div>
          <div class="column is-12">
            <VButton @click="filter()" :loading="isLoading" type="button" icon="feather:search"
              class="is-fullwidth mr-3" color="info" raised>
              Apply Filters
            </VButton>
          </div>
        </div>
      </div>
    </div>
  </VCard>
</template>

<script  setup lang="ts">
import { useApi } from '/@src/composable/useApi'
import { ref, computed, watch, reactive } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import DataTable from 'primevue/datatable'
import Column from 'primevue/column'
import { useHead } from '@vueuse/head'
import moment from 'moment'
useHead({
  title: 'Transmedic - Kartu Stok',
})
let dataSource: any = ref([])
let isLoading: any = ref(false)
let ceklah: any = ref()
let item: any = reactive({
  exp: true,
  layak: '',
  periode: reactive({
    start: new Date(),
    end: new Date(),
  }),
})
let listKelompokProduk: any = ref([])
let listRuangan: any = ref([])
const currentPage: any = ref({
  limit: 5,
  rows: 50,
})
const route = useRoute()
item.namaProdukHeader = ""
isLoading.value = false

async function fetchData() {
  isLoading = true
  let limit: any = currentPage.value.limit
  let offset: any = route.query.page ? route.query.page : 1
  offset = offset * limit - limit
  let rows: any = currentPage.value.rows
  let idProduk = ''
  let idRuangan = ''
  let tglAwal = ''
  let tglAkhir = ''

  dataSource.value = []
  item.namaProdukHeader = ''
  //   if (item.idProduk) idProduk = '&idproduk= ' + item.idProduk
  if (item.objectkelompokprodukfk) idProduk = '&idkelproduk=' + item.objectkelompokprodukfk
  if (item.objectruanganfk) idRuangan = '&idruangan=' + item.objectruanganfk
  if (item.periode.start) tglAwal = 'dari=' + moment(item.periode.start).format('YYYY-MM-DD')
  if (item.periode.end) tglAkhir = '&sampai=' + moment(item.periode.end).format('YYYY-MM-DD')

  const response = await useApi().get(
    '/logistik/stok-barang?' + tglAwal + tglAkhir +
    '&offset=' + offset +
    '&limit=' + limit +
    '&rows=' + rows
    + idRuangan + idProduk
  )
  isLoading = false
  for (let x = 0; x < response.data.length; x++) {
    const element = response.data[x];
    element.no = x + 1
    const dateNow = new Date().toISOString().slice(0, 10);
    const tglexp = element.tglkadaluarsa

    if (tglexp <= dateNow) {
      element.cBG = 'danger'
      element.status = 'Expired'
    } else {
      // element.namaProduk = element.namaproduk.substring(0,15) + rep
      element.cBG = 'success'
      element.status = 'Layak'
    }
  }
  dataSource.value = response.data
}
async function listDropdown() {
  const response = await useApi().get(`/logistik/select-item`)
  listKelompokProduk.value = response.kelompokproduk.map((e: any) => {
    return { label: e.kelompokproduk, value: e.id }
  })

  listRuangan.value = response.namaruangan.map((e: any) => {
    return { label: e.namaruangan, value: e.id }
  })
}
function clearFilter() {
  fetchData()
}
function filter() {
  fetchData()
}

listDropdown()

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