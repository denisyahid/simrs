<template>
  <div class="columns is-multiline">
    <VCard style="padding-bottom: 0px">
      <div class="column c-title pt-2 mb-5">
        <label class="title-page">Data Closing Stok Harian</label>
      </div>
      <div class="column is-12">
        <div class="columns  is-multiline">
          <div class="column is-2" style="padding-top:2rem">
                <VButton color="primary" @click="exportExcel()" outlined icon="fas fa-file-excel">
                  Export To Excel
                </VButton>
          </div>
          <div class="column is-2">
            <VField label="Tgl. Closing">
              <Calendar v-model="item.tglclosing" selectionMode="single" :manualInput="true" class="w-100"
                :showIcon="true" :showTime="false" hourFormat="24" :date-format="'yy-mm-dd'" />
            </VField>
          </div>

          <div class="column is-3">
            <VField label="Ruangan" required class="is-rounded-select_Z  is-autocomplete-select" v-slot="{ id }">
              <VControl icon="feather:home" fullwidth class="prime-auto">
                <Dropdown v-model="item.ruangan" :options="d_Ruangan" :optionLabel="'namaruangan'" placeholder="Ruangan"
                  style="width: 100%;" :filter="true" />
              </VControl>
            </VField>
          </div>

          <div class="column is-2">
            <VField label="Nama Produk">
              <VControl icon="feather:bookmark">
                <VInput type="text" v-model="item.nama" placeholder="Nama Produk" />
              </VControl>
            </VField>
          </div>
          <div class="column is-2">
            <VField v-slot="{ id }" class="is-icon-select" label="View">
              <VControl>
                <Multiselect v-model="selectView" :attrs="{ id }" placeholder="Select View" label="name" :options="d_View"
                  :searchable="true" track-by="name" mode="single" @select="changeView(selectView)" autocomplete="off">
                  <template #singlelabel="{ value }">
                    <div class="multiselect-single-label">
                      <div class="select-label-icon-wrap">
                        <i :class="value.icon"></i>
                      </div>
                      <span class="select-label-text">
                        {{ value.name }}
                      </span>
                    </div>
                  </template>
                  <template #option="{ option }">
                    <div class="select-option-icon-wrap">
                      <i :class="option.icon"></i>
                    </div>
                    <span class="select-option-text">
                      {{ option.name }}
                    </span>
                  </template>
                </Multiselect>
              </VControl>
            </VField>
          </div>
          
          <div class="column is-1 btn-search">
            <VIconButton type="button" icon="feather:search" class="is-rounded" :loading="isLoading"
              @click="cariRiwayat()" color="success">
            </VIconButton>
          </div>

        </div>
      </div>


      <div class="column is-12 ">
        <div class="column" v-if="isLoading">
          <VPlaceloadWrap v-for="data in 10">
            <VPlaceload class="mx-2 mb-3" />
          </VPlaceloadWrap>
        </div>
        <div class="column" v-else>
          <VPlaceholderPage v-if="dataSource.length == 0" title="Data Tidak di Temukan."
            subtitle="Silakan gunakan filter lain" larger>
            <template #image>
              <img class="light-image" src="/@src/assets/illustrations/placeholders/search-1.svg" alt="" />
              <img class="dark-image" src="/@src/assets/illustrations/placeholders/search-1-dark.svg" alt="" />
            </template>
          </VPlaceholderPage>
          <div v-else>
            <div class="column" v-if="selectView == 'grid'">
              <div class="table-scroll analytics-dashboard">
                <div class="columns is-multiline">
                  <div class="column is-12">
                    <DataTable :value="dataSource" :rows="10" v-model:expandedRows="expandedRows"
                      :rowsPerPageOptions="[5, 10, 15, 20]" :loading="isLoading" class="p-datatable-sm"
                      responsiveLayout="stack" breakpoint="960px" selectionMode="single" sortMode="multiple"
                      dataKey="norec"
                      paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
                      paginator currentPageReportTemplate="Showing {first} to {last} of {totalRecords}">

                      <Column field="no" header="No" style="width:40px"></Column>
                      <Column field="kdproduk" header="Kode Produk" style="width:100px"></Column>
                      <Column field="namaproduk" header="Nama Produk"></Column>
                      <Column field="jumlah" header="Stok">
                        <template #body="slotProps">
                          {{ slotProps.data.jumlah }}
                        </template>
                      </Column>
                      <Column field="satuanstandar" header="Satuan"></Column>

                    </DataTable>
                  </div>
                  <div class="column is-6 is-offset-6">
                    <div class="dashboard-tile p-2">
                      <div class="tile-head">
                        <h3 class="dark-inverted">Total Stok</h3>
                        <VIconBox color="danger" size="small" rounded>
                          <i aria-hidden="true" class="fas fa-calculator"></i>
                        </VIconBox>
                      </div>
                      <div class="tile-body">
                        <span class="dark-inverted">{{ totalSTOK }}</span>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="column" v-else>

              <div class="tile-grid tile-grid-v1">
                <div class="analytics-dashboard mb-4">
                  <div class="dashboard-tile p-2">
                    <div class="tile-head">
                      <h3 class="dark-inverted">TOTAL STOK</h3>
                      <VIconBox color="danger" size="small" rounded>
                        <i aria-hidden="true" class="fas fa-calculator"></i>
                      </VIconBox>
                    </div>
                    <div class="tile-body">
                      <span class="dark-inverted">{{ totalSTOK }}</span>
                    </div>
                  </div>
                </div>
                <div class="columns is-multiline mt-2" style="height:500px;overflow:auto">
                  <div v-for="(item, key) in dataSource" :key="key" class="column is-4">
                    <div class="tile-grid-item">
                      <div class="tile-grid-item-inner">
                        <VAvatar size="small" :initials="item.initials" :color="item.color" />
                        <div class="meta">
                          <span class="dark-inverted">{{ item.namaproduk }}</span>
                          <span> Stok : {{ item.jumlah }}</span>
                        </div>
                      </div>
                    </div>
                  </div>

                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </VCard>
  </div>
</template>
<script  setup lang="ts">
import { useApi } from '/@src/composable/useApi'
import { ref, reactive } from 'vue'
import { useRouter, useRoute, RouterLink } from 'vue-router';
import { useConfirm } from 'primevue/useconfirm'
import { useHead } from '@vueuse/head'
import * as H from '/@src/utils/appHelper'
import DataTable from 'primevue/datatable'
import Column from 'primevue/column'
import * as XLSX from "xlsx";
import Calendar from 'primevue/calendar'
import Dropdown from 'primevue/dropdown'
import sleep from '/@src/utils/sleep'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useThemeColors } from '/@src/composable/useThemeColors'
useHead({
  title: 'Data Closing Stok Harian - ' + import.meta.env.VITE_PROJECT,
})

useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)

const isLoading = ref(false)
const dataSource: any = ref([])
const remakeData: any = ref([])
const d_Ruangan: any = ref([]);
const input: any = ref({})
const expandedRows = ref();
const jenis = ref('')
const listColor: any = ref(Object.keys(useThemeColors()))
const totalSTOK: any = ref(0)
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
selectView.value = 'grid'

const item: any = ref({
  tglclosing: new Date()
})

const cariRiwayat = async () => {
  isLoading.value = true;
  let startDate = H.formatDate(item.value.tglclosing, 'YYYY-MM-DD');
  let ruangan = item.value.ruangan ? item.value.ruangan.id : "";
  let rows = item.value.rows ?? "";
  let nama = item.value.nama ?? "";
  totalSTOK.value = 0
  useApi().get(
    `/farmasi/get-saldo-ruangan-detail?tglAwal=${startDate}&ruanganfk=${ruangan}&rows=${rows}&namaproduk=${nama}`).then(async (response: any) => {
      totalSTOK.value = 0
      let x = 0
      for (let i = 0; i < response.data.length; i++) {
        const element = response.data[i];
        totalSTOK.value = totalSTOK.value + parseFloat(element.jumlah)
        element.no = i + 1
        let ini = element.namaproduk.split(' ')
        let init = element.namaproduk.substr(0, 1)
        if (ini.length > 1) {
          init = init + ini[1].substr(0, 1)
        }
        element.initials = init
        if (x > 5) {
          x = 0
        }
        element.color = listColor.value[x]
        x++
      }
      dataSource.value = response.data

      isLoading.value = false
    }).catch((e: any) => {
      isLoading.value = false
    })
}

const kunciSaldo = () => {
  useApi()
    .post('/farmasi/get-save-saldo-produk-detail')
    .then((response: any) => {

      console.log('Sukses:', response.data);
    })
    .catch((e: any) => {
      console.error('Gagal:', e);
    });
};


const formatRp = (amount, rp) => {
  // Add currency symbol
  let formattedAmount = '';

  // Format thousands separator
  formattedAmount += amount.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',');

  // Add decimal places if necessary
  const decimalIndex = formattedAmount.indexOf('.');
  if (decimalIndex === -1) {
    formattedAmount += ',00'; // Add decimal places if not present
  } else {
    // Ensure two decimal places
    formattedAmount += formattedAmount.substr(decimalIndex, 3);
  }

  return formattedAmount;
}

const exportExcel = () => {
    remakeData.value = dataSource.value.map((e: any) => {
      return {
        No:e.no,
        KodeProduk : e.kdproduk,
        NamaProduk : e.namaproduk, 
        Satuan : e.satuanstandar, 
        Stok : Number(e.jumlah),
      }
    })
    const worksheet = XLSX.utils.json_to_sheet(remakeData.value)
    const workbook = { Sheets: { data: worksheet }, SheetNames: ['data'] };
    const excelBuffer: any = XLSX.write(workbook, { bookType: 'xlsx', type: 'array' });
    saveAsExcelFile(excelBuffer, 'products');
  }
  
  const saveAsExcelFile = (buffer: any, fileName: string) => {
    let EXCEL_TYPE = 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;charset=UTF-8';
    let EXCEL_EXTENSION = '.xlsx';
    const data: Blob = new Blob([buffer], {
      type: EXCEL_TYPE
    });
    const _url = window.URL.createObjectURL(data)
    window.open(_url, EXCEL_EXTENSION).focus();
    // window.open(_url,EXCEL_EXTENSION).focus()
    // exportFilename.saveAs(data, fileName + '_export_' + new Date().getTime() + EXCEL_EXTENSION);
  }

const dropdown = async () => {
  const response = await useApi().get(`/farmasi/saldo/ruangan`)

  d_Ruangan.value = response.ruangan
  item.value.ruangan = response.ruangan.length ? response.ruangan[0] : ''
}

const changeView = (e: any) => {
  selectView.value = e
}

dropdown();
cariRiwayat();
</script>

<style lang="scss">
@import '/@src/scss/abstracts/all';

@import '/@src/scss/components/forms-outer';
@import '/@src/scss/custom/config';
@import '/@src/scss/module/sysadmin/master-data.scss';

.title-page {
  position: relative;
  font-size: 17px;
  display: block;
  margin-bottom: 3px;
  margin-top: 8px;
  font-weight: 600;
}

.btn-search {
  display: flex;
  align-items: center;
  margin-top: 14px;
}

.tile-grid {
  .columns {
    margin-left: -0.5rem !important;
    margin-right: -0.5rem !important;
    margin-top: -0.5rem !important;
  }

  .column {
    padding: 0.5rem !important;
  }
}

.tile-grid-v3 {
  .tile {
    &.is-ancestor {
      margin-left: -0.5rem;
      margin-right: -0.5rem;
      margin-top: -0.5rem;
    }

    &.is-parent {
      padding: 0.5rem;
    }
  }

  .tile-grid-item {
    @include vuero-s-card;

    padding: 10px;
    border-radius: 16px;

    &.is-medium {
      max-height: 132px;

      .tile-grid-item-inner {
        display: flex;
        height: 100%;

        >img {
          display: block;
          border-radius: 12px;
          width: 100%;
          max-width: 110px;
          min-width: 110px;
          height: 100%;
          min-height: 110px;
          max-height: 110px;
          object-fit: cover;
        }

        .meta {
          margin-left: 12px;
          display: flex;
          flex-direction: column;
          justify-content: space-between;

          .tile-title {
            h3 {
              font-family: var(--font);
              font-family: var(--font-alt);
              font-size: 1rem;
              font-weight: 600;
              color: var(--dark-text);
              line-height: 1.3;
            }
          }

          .tile-meta {
            display: flex;
            align-items: center;
            margin-top: auto;
            padding: 0 0 5px;

            .v-avatar {
              max-width: 26px;
              max-height: 26px;
              min-width: 26px;

              .avatar {
                max-width: 26px;
                max-height: 26px;
                min-width: 26px;
              }
            }

            .meta-inner {
              margin-left: 8px;
              line-height: 1.2;

              span {
                display: block;
                font-weight: 400;

                &:first-child {
                  color: var(--dark-text);
                  font-family: var(--font-alt);
                  font-size: 0.85rem;
                  font-weight: 600;
                }

                &:nth-child(2) {
                  font-size: 0.8rem;
                  font-family: var(--font);
                  color: var(--light-text);
                }
              }
            }
          }
        }
      }
    }

    &.is-large {
      .tile-grid-item-inner {
        display: flex;
        flex-direction: column;
        height: 100%;

        >img {
          display: block;
          border-radius: 12px;
          width: 100%;
          height: 180px;
          object-fit: cover;
        }

        .meta {
          display: flex;
          flex-direction: column;
          justify-content: space-between;
          flex: 1 1 0;
          padding: 0 5px;

          .tile-title {
            padding-top: 10px;

            h3 {
              font-family: var(--font);
              font-size: 1.2rem;
              font-weight: 500;
              color: var(--dark-text);
              line-height: 1.1;
            }
          }

          .tile-meta {
            display: flex;
            align-items: center;
            margin-top: auto;
            padding: 0 0 5px;

            .v-avatar {
              max-width: 26px;
              max-height: 26px;
              min-width: 26px;

              .avatar {
                max-width: 26px;
                max-height: 26px;
                min-width: 26px;
              }
            }

            .meta-inner {
              margin-left: 8px;
              line-height: 1.2;

              span {
                display: block;
                font-weight: 400;

                &:first-child {
                  color: var(--dark-text);
                  font-size: 0.9rem;
                  font-weight: 500;
                }

                &:nth-child(2) {
                  font-size: 0.8rem;
                  color: var(--light-text);
                }
              }
            }
          }
        }
      }
    }

    &.is-wide {
      .tile-grid-item-inner {
        display: flex;
        height: 100%;

        >img {
          display: block;
          border-radius: 12px;
          width: 100%;
          max-width: 280px;
          height: 100%;
          min-height: 160px;
          object-fit: cover;
        }

        .meta {
          margin-left: 12px;
          display: flex;
          flex-direction: column;
          justify-content: space-between;

          .tile-title {
            padding-top: 5px;

            h3 {
              font-family: var(--font);
              font-size: 1.3rem;
              font-weight: 500;
              color: var(--dark-text);
              line-height: 1.1;
            }

            p {
              color: var(--light-text);
              font-size: 0.95rem;
              padding-top: 5px;
            }
          }

          .tile-meta {
            display: flex;
            align-items: center;
            margin-top: auto;
            padding: 0 0 5px;

            .v-avatar {
              max-width: 26px;
              max-height: 26px;
              min-width: 26px;

              .avatar {
                max-width: 26px;
                max-height: 26px;
                min-width: 26px;
              }
            }

            .meta-inner {
              margin-left: 8px;
              line-height: 1.2;

              span {
                display: block;
                font-weight: 400;

                &:first-child {
                  color: var(--dark-text);
                  font-size: 0.9rem;
                  font-weight: 500;
                }

                &:nth-child(2) {
                  font-size: 0.8rem;
                  color: var(--light-text);
                }
              }
            }
          }
        }
      }
    }

    &.is-tall {
      .tile-grid-item-inner {
        display: flex;
        flex-direction: column;
        height: 100%;

        >img {
          display: block;
          border-radius: 12px;
          width: 100%;

          // max-width: 110px;
          height: 220px;
          object-fit: cover;
        }

        .meta {
          display: flex;
          flex-direction: column;
          justify-content: space-between;
          flex: 1 1 0;
          padding: 0 5px;

          .tile-title {
            padding-top: 10px;

            h3 {
              font-family: var(--font);
              font-size: 1.2rem;
              font-weight: 500;
              color: var(--dark-text);
              line-height: 1.1;
            }

            p {
              color: var(--light-text);
              font-size: 0.95rem;
              padding-top: 5px;
            }
          }

          .tile-meta {
            display: flex;
            align-items: center;
            margin-top: auto;
            padding: 0 0 5px;

            .v-avatar {
              max-width: 26px;
              max-height: 26px;
              min-width: 26px;

              .avatar {
                max-width: 26px;
                max-height: 26px;
                min-width: 26px;
              }
            }

            .meta-inner {
              margin-left: 8px;
              line-height: 1.2;

              span {
                display: block;
                font-weight: 400;

                &:first-child {
                  color: var(--dark-text);
                  font-size: 0.9rem;
                  font-weight: 500;
                }

                &:nth-child(2) {
                  font-size: 0.8rem;
                  color: var(--light-text);
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
  .tile-grid {
    .tile-grid-item {
      @include vuero-card--dark;
    }
  }

  .tile-grid-v3 {
    .tile-grid-item {
      @include vuero-card--dark;
    }
  }
}

@media only screen and (max-width: 767px) {
  .tile-grid-v3 {
    .tile-grid-item {
      max-height: 132px !important;

      .tile-grid-item-inner {
        display: flex !important;
        flex-direction: row !important;
        height: 100% !important;

        >img {
          display: block !important;
          border-radius: 12px !important;
          width: 100% !important;
          max-width: 110px !important;
          min-width: 110px !important;
          height: 100% !important;
          min-height: 110px !important;
          max-height: 110px !important;
          object-fit: cover !important;
        }

        .meta {
          margin-left: 12px !important;
          display: flex !important;
          flex-direction: column !important;
          justify-content: space-between !important;

          .tile-title {
            h3 {
              font-family: var(--font) !important;
              font-size: 1.1rem !important;
              font-weight: 500;
              color: var(--dark-text) !important;
              line-height: 1.1 !important;
            }

            p {
              display: none !important;
            }
          }

          .tile-meta {
            display: flex !important;
            align-items: center !important;
            margin-top: auto !important;
            padding: 0 0 5px !important;

            .v-avatar {
              max-width: 26px !important;
              max-height: 26px !important;
              min-width: 26px !important;

              .avatar {
                max-width: 26px !important;
                max-height: 26px !important;
                min-width: 26px !important;
              }
            }

            .meta-inner {
              margin-left: 8px !important;
              line-height: 1.2 !important;

              span {
                display: block !important;
                font-weight: 400 !important;

                &:first-child {
                  color: var(--dark-text) !important;
                  font-size: 0.9rem !important;
                  font-weight: 500 !important;
                }

                &:nth-child(2) {
                  font-size: 0.8rem !important;
                  color: var(--light-text) !important;
                }
              }
            }
          }
        }
      }
    }
  }
}

@media only screen and (min-width: 768px) and (max-width: 1024px) and (orientation: portrait) {
  .tile-grid-v3 {
    .tile-grid-item {
      max-height: 132px !important;

      .tile-grid-item-inner {
        display: flex !important;
        flex-direction: row !important;
        height: 100% !important;

        >img {
          display: block !important;
          border-radius: 12px !important;
          width: 100% !important;
          max-width: 110px !important;
          min-width: 110px !important;
          height: 100% !important;
          min-height: 110px !important;
          max-height: 110px !important;
          object-fit: cover !important;
        }

        .meta {
          margin-left: 12px !important;
          display: flex !important;
          flex-direction: column !important;
          justify-content: space-between !important;

          .tile-title {
            h3 {
              font-family: var(--font) !important;
              font-size: 1.1rem !important;
              font-weight: 500;
              color: var(--dark-text) !important;
              line-height: 1.1 !important;
            }

            p {
              display: block;
              max-width: 460px;
            }
          }

          .tile-meta {
            display: flex !important;
            align-items: center !important;
            margin-top: auto !important;
            padding: 0 0 5px !important;

            .v-avatar {
              max-width: 26px !important;
              max-height: 26px !important;
              min-width: 26px !important;

              .avatar {
                max-width: 26px !important;
                max-height: 26px !important;
                min-width: 26px !important;
              }
            }

            .meta-inner {
              margin-left: 8px !important;
              line-height: 1.2 !important;

              span {
                display: block !important;
                font-weight: 400 !important;

                &:first-child {
                  color: var(--dark-text) !important;
                  font-size: 0.9rem !important;
                  font-weight: 500 !important;
                }

                &:nth-child(2) {
                  font-size: 0.8rem !important;
                  color: var(--light-text) !important;
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
  .tile-grid-v3 {
    .tile-grid-item {
      &.is-medium {
        .tile-grid-item-inner {
          .tile-title {
            h3 {
              font-size: 1rem !important;
            }
          }
        }
      }

      &.is-large {
        .tile-grid-item-inner {
          .tile-title {
            h3 {
              font-size: 1.1rem !important;
            }
          }
        }
      }

      &.is-tall {
        .tile-grid-item-inner {
          .tile-title {
            h3 {
              font-size: 1.2rem !important;
            }
          }
        }
      }

      &.is-wide {
        .tile-grid-item-inner {
          >img {
            max-width: 180px;
            min-height: 160px;
          }

          .tile-title {
            h3 {
              font-size: 1.2rem !important;
            }
          }
        }
      }
    }
  }
}
</style>

