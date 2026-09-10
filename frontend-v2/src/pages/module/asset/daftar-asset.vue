<template>
  <VCard>
    <div class="columns column">
      <h3 class="title is-5 mb-2 mr-1">Daftar Barang Asset</h3>
    </div>
    <div class="columns">
      <div class="column is-9">
        <DataTable :value="dataSource" lazy paginator :rows="10" dataKey="id" filterDisplay="row" :loading="isLoading"
          :first="first" :totalRecords="total" @page="onPage($event)" @sort="onSort($event)"
          :rowsPerPageOptions="[5, 10, 25, 50, 100, 1000]" :globalFilterFields="['namaproduk']"
          :class="`p-datatable-small`">
          <template #header>
            <div class="columns is-multiline">
              <div class="column is-5">
                <VButton type="button" icon="pi pi-file-excel" class="mr-3" color="info" outlined circle raised
                  v-tooltip-prime="'Export'" @click="exportExcel()">
                  Export Excel
                </VButton>

                <VButton type="button" icon="pi pi-plus-circle" class="mr-3" color="success" circle raised
                  v-tooltip-prime="'Export'" @click="tambah()">
                  Tambah
                </VButton>
              </div>
              <div class="column is-4 is-offset-3">

              </div>
            </div>
          </template>
          <template #empty style="text-align: center;"> No data found. </template>
          <Column :exportable="false" header="#" style="width:150px">
            <template #body="slotProps">
              <VIconButton type="button" icon="pi pi-trash" class="mr-3" color="danger" circle outlined raised
                v-tooltip-prime="'Hapus '" @click="dialogConfirm(slotProps.data)" :loading="slotProps.data.isLoading">
              </VIconButton>
              <VIconButton type="button" icon="pi pi-pencil" class="mr-3" color="warning" circle outlined raised
                v-tooltip-prime="'Edit '" @click="edit(slotProps.data)">
              </VIconButton>
              <VIconButton type="button" icon="fas fa-paper-plane" class="mr-3" color="info" circle outlined raised
                v-tooltip-prime="'Kirim '" @click="kirim(slotProps.data)">
              </VIconButton>
            </template>
          </Column>
          <Column v-for="col in columnGrid" :field="col.field" :header="col.title" :sortable="true"
            :style="'width:' + col.width + ';text-align:' + (col.template != undefined ? 'right' : '')">
            <template #body="slotProps">
              <span v-if="col.tag == undefined">{{ col.template !=
                undefined ?
                H.formatRupiah(slotProps.data[col.field], '')
                : slotProps.data[col.field] }}</span>
              <span v-else>
                <VTag class="mr-1 mb-1" :color="'success'" :label="slotProps.data[col.field]" />
              </span>
            </template>
          </Column>
        </DataTable>

      </div>
      <div class="column is-3">

        <div class="columns is-multiline business-dashboard hr-dashboard">
          <div class="column is-12">
            <CardCountRev icon="/images/simrs/penerimaaan.png" straight :total="H.formatRupiah(total, '')"
              label="TOTAL ASSET" />

          </div>

        </div>

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
              <VControl icon="feather:search">
                <input v-model="item.NamaProduk" v-on:keyup.enter="filter()" type="text" class="input is-rounded"
                  placeholder="Nama Barang..." />
              </VControl>
            </VField>
          </div>


          <div class="column is-12">
            <VField label="Departemen" class="is-rounded-select is-autocomplete-select
                              mt-0 pt-0" v-slot="{ id }">
              <VControl icon="fas fa-database" fullwidth class="prime-auto-select">
                <Dropdown v-model="item.departemen" :options="d_Departemen" :optionLabel="'namadepartemen'"
                  class="is-rounded" placeholder="Departemen" style="width: 100%;" :filter="true" showClear
                  @change="changeRuangan($event)" />
              </VControl>
            </VField>
          </div>
          <div class="column is-12">
            <VField label="Ruangan" class="is-rounded-select is-autocomplete-select
                              mt-0 pt-0" v-slot="{ id }">
              <VControl icon="fas fa-database" fullwidth class="prime-auto-select">
                <Dropdown v-model="item.ruangan" :options="d_Ruangan" :optionLabel="'namaruangan'" class="is-rounded"
                  placeholder="Ruangan" style="width: 100%;" :filter="true" showClear />
              </VControl>
            </VField>
          </div>
          <div class="column is-12">
            <VField label="Detail Jenis Produk" class="is-rounded-select is-autocomplete-select
                              mt-0 pt-0" v-slot="{ id }">
              <VControl icon="fas fa-database" fullwidth class="prime-auto-select">
                <Dropdown v-model="item.DetailJenis" :options="d_DetailJenisProduk" :optionLabel="'detailjenisproduk'"
                  class="is-rounded" placeholder="Detail Jenis Produk" style="width: 100%;" :filter="true" showClear />
              </VControl>
            </VField>
          </div>
          <div class="column is-12">
            <VButton @click="filter()" :loading="isLoading" type="button" icon="feather:search" class="is-fullwidth mr-3"
              color="info" raised>
              Apply Filters
            </VButton>
          </div>
        </div>
      </div>
    </div>
  </VCard>
</template>

<script  setup lang="ts">
import { useWindowScroll } from '@vueuse/core'
import { useApi } from '/@src/composable/useApi'
import { ref, computed, watch, reactive } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import DataTable from 'primevue/datatable'
import Column from 'primevue/column'
import Toolbar from 'primevue/toolbar'
import Dropdown from 'primevue/dropdown'
import Button from 'primevue/button'
import AutoComplete from 'primevue/autocomplete'
import { useConfirm } from 'primevue/useconfirm'
import { useCurrencyInput } from 'vue-currency-input'
import { useHead } from '@vueuse/head'
import { useToaster } from '/@src/composable/toaster'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import moment from 'moment'
import * as H from '/@src/utils/appHelper'
useHead({
  title: 'Daftar Barang Asset - ' + import.meta.env.VITE_PROJECT,
})
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)
let dataSource: any = ref([])
let isLoading: any = ref(false)
let item: any = reactive({})
let d_Departemen: any = ref([])
let d_Ruangan: any = ref([])
let d_DetailJenisProduk: any = ref([])
const confirm = useConfirm();
const currentPage: any = ref({
  limit: 5,
  rows: 50,
})
const first = ref(0);
const total = ref(0)
const router = useRouter()
const route = useRoute()
isLoading.value = false
const columnGrid: any = ref([
  {
    "field": "no",
    "title": "No",
    "width": "50px",
    // filterable: false
  },
  {
    "field": "noregisteraset",
    "title": "No Aset"
  },
  {
    "field": "kdbmn",
    "title": "Kode BMN"
  },
  {
    "field": "namaproduk",
    "title": "Nama Barang"
  },
  {
    "field": "spesifikasi",
    "title": "Spesifikasi"
  },
  {
    "field": "jenisaset",
    "title": "Jenis Asset"
  },
  {
    "field": "qtyprodukaset",
    "title": "Qty Asset"
  },
  {
    "field": "ruangancurrent",
    "title": "Ruangan"
  },
  {
    "field": "asalproduk",
    "title": "SumberDana"
  }]
)
const lazyParams: any = ref({});
const onPage = (event: any) => {
  lazyParams.value = event;
  fetchData(event);
};
const onSort = (event: any) => {
  lazyParams.value = event;
  fetchData(event);
};


const fetchData = async (event: any) => {

  lazyParams.value = { ...lazyParams.value, first: event?.first || first.value };

  var ruanganId = "";
  if (item.ruangan != undefined) {
    ruanganId = "&ruangancurrenfk=" + item.ruangan.id
  }
  var departemen = "";
  if (item.departemen != undefined) {
    departemen = "&departemen=" + item.departemen.id;
  }
  var kdDetailJenis = "";
  if (item.DetailJenis != undefined) {
    kdDetailJenis = "&kdDetailJenis=" + item.DetailJenis.id;
  }
  var namaproduk = "";
  if (item.NamaProduk != undefined) {
    namaproduk = "&namaproduk=" + item.NamaProduk;
  }

  let limit: any = lazyParams.value.rows ? lazyParams.value.rows : 10

  let page: any = lazyParams.value.page ? lazyParams.value.page + 1 : 1
  isLoading.value = true
  const response = await useApi().get('/asset/get-daftar-asset?limit=' + limit + '&page=' + page + ruanganId + kdDetailJenis + namaproduk + departemen)
  isLoading.value = false
  for (let x = 0; x < response.data.length; x++) {
    const element = response.data[x];
    element.no = x + 1
  }
  total.value = response.total
  dataSource.value = response.data
}
const listDropdown = async () => {
  await useApi().get(`/asset/get-daftar-dropdown-asset`).then((response: any) => {
    d_Departemen.value = response.departemen
    d_DetailJenisProduk.value = response.detailjenisproduk
  })
}
const changeRuangan = (e: any) => {
  d_Ruangan.value = e.value ? e.value.ruangan : []
}


const clearFilter = () => {
  delete item.DetailJenis
  delete item.ruangan
  delete item.NamaProduk
  delete item.departemen
  fetchData()
}
const filter = () => {

  fetchData()
}

const hapus = async (e: any) => {

  var objSave =
  {
    "id": e.id,
    "kdaccount": e.noakun,
    "namaaccount": e.namaAkun,
  }
  e.isLoading = true
  await useApi().post(
    `/akuntansi/save-hapus-data-master-coa`, objSave).then((response: any) => {
      e.isLoading = false
      fetchData()

    }, (error) => {
      e.isLoading = false
      // console.log(error)
    })
}
const exportExcel = () => {
  H.exportExcel(dataSource.value, 'asset')
}

const dialogConfirm = (e: any) => {
  confirm.require({
    message: 'Apakah anda serius menghapus data ini ?',
    header: 'Konfirmasi Hapus Data',
    icon: 'pi pi-info-circle',
    acceptClass: 'p-button-danger',
    accept: () => {
      hapus(e)
    },
    reject: () => { },
  })
}
const edit = (e: any) => {
  router.push({
    name: 'module-asset-master-barang-aset', query: {
      norec: e.norec
    }
  })
}
const kirim = (e: any) => {
  // console.log(e)
  router.push({ 
    name: 'module-asset-kirim-barang-asset',
    query :{
      ruanganfk : e.ruangancurrenfk,
      norecasset : e.norec
    }
})
}
const tambah = () => {
  router.push({ name: 'module-asset-master-barang-aset' })
}
fetchData()
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

.tile-grid-v2 {
  .tile-grid-item {
    @include vuero-s-card;

    border-radius: 14px;
    padding: 16px;
    cursor: pointer;

    &:hover,
    &:focus {
      border-color: var(--primary);
      box-shadow: var(--light-box-shadow);
    }

    .tile-grid-item-inner {
      display: flex;
      align-items: center;

      >img {
        display: block;
        width: 50px;
        height: 50px;
        min-width: 50px;
      }

      .meta {
        margin-left: 10px;
        line-height: 1.4;

        span {
          display: block;
          font-family: var(--font);

          &:first-child {
            color: var(--dark-text);
            font-family: var(--font-alt);
            font-weight: 600;
            font-size: 0.8rem;
          }

          &:nth-child(2) {
            display: flex;
            align-items: center;

            span {
              display: inline-block;
              color: var(--light-text);
              font-size: 0.5rem;
              font-weight: 400;
            }

            .icon-separator {
              position: relative;
              font-size: 4px;
              color: var(--light-text);
              padding: 0 6px;
            }
          }
        }
      }

      .dropdown {
        margin-left: auto;
      }
    }
  }
}

.is-dark {
  .tile-grid {
    .tile-grid-item {
      @include vuero-card--dark;
    }
  }

  .tile-grid-v2 {
    .tile-grid-item {
      @include vuero-card--dark;

      &:hover,
      &:focus {
        border-color: var(--primary) !important;
      }
    }
  }
}
</style>
