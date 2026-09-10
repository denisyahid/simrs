
<template>
  <ConfirmDialog />
  <VCard>
    <div class="columns column">
      <h3 class="title is-5 mb-2 mr-1">Riwayat Openbill Pasien </h3> <span>({{ ds_PASIEN.total ?? 0 }} Totals)</span>
    </div>

    <!-- mulai sini cantik -->

    <div class="columns  all-projects m-3 mt-0">
      <div class="columns is-multiline  projects-card-grid" style="width: 100% !important">

        <!-- sampai sini cantik -->

        <!-- ini baru cantik -->

        <div class="column is-12">
          <div class="columns is-multiline">
            <div class="column is-3">
                <VField>
                  <VLabel> Tanggal Open </VLabel>
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
            <div class="column is-4 mt-5">
                <VField>
                  <VControl icon="feather:search">
                    <input v-model="item.qnama" v-on:keyup.enter="filter()" type="text" class="input is-rounded"
                      placeholder="Filter Nama / No RM / No Registrasi" />
                  </VControl>
                </VField>
            </div>
            <div class="column is-4">
              <VField>
                <VLabel>Ruangan</VLabel> 
                <!-- yang ini -->
                <VControl>
                  <MultiSelect v-model="sourceRuangan" display="chip" :options="d_Ruangan" optionLabel="label"
                    filter placeholder="Pilih Ruangan" :maxSelectedLabels="3" style="display:flex"
                    @change="changeRuang(sourceRuangan)" />
                </VControl>
              </VField>
            </div>
            <div class="column is-1 mt-5">
                <VIconButton type="button" color="info" class="is-rounded" rounded raised icon="fas fa-search"
                  @click="filter()" :loading="ds_PASIEN.loading">
                </VIconButton>
            </div>
          </div>
        </div>

        <div class="column px-0" style="width: 100%;">
          <DataTable :value="ds_PASIEN" class="p-datatable-md"
              :loading="isLoading"
              style="width: 100% !important; max-width: 100%;background-color: white;"
              :paginator="true"
              :rows="currentPage.rows"
              :rowsPerPageOptions="[10, 15, 25, currentPage.rows]"
              scrollable
              exportCSV
              v-model:selection="selectedPasien"
              :metaKeySelection="metaKey" 
              selectionMode="single"
              scrollHeight="600px"
              :exportFunction="exportCTRL"
              ref="dtTable"
              :totalRecords="ds_PASIEN.total"
              paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
              responsiveLayout="stack" breakpoint="960px" sortMode="multiple"
              currentPageReportTemplate="Showing {first} to {last} of {totalRecords}" showGridlines>

              <template #empty>
                <VCard radius="rounded" class="mt-2">
                    <VPlaceholderPage :title="H.assets().notFound"
                        :subtitle="H.assets().notFoundSubtitle" larger>
                        <template #image>
                            <img class="light-image" :src="H.assets().iconNotFound_rev"
                                alt="" />
                            <img class="dark-image"
                                src="/@src/assets/illustrations/placeholders/search-4-dark.svg"
                                alt="" />
                        </template>
                    </VPlaceholderPage>
                </VCard>
              </template>
              <template #loading>
              <img src="/images/other/loadingspin.gif" alt="Loading..." width="100"/>
              <p style="color:white">Loading data, please wait...</p>
              </template>
              <template #header>
                  <div class="text-end pb-4">
                      <!-- <VButton icon="pi pi-external-link" label="Export" @click="exportCSV($event)" /> -->
                      <VButton icon="pi pi-external-link" @click="exportCSV($event)">Export</VButton>
                  </div>
              </template>
              <Column field="namapasien" header="Nama Pasien" style="min-width: 80px">
                <template #body="slotProps">
                  <!-- <VButtons> -->
                    <span>{{ slotProps.data.namapasien }}</span> 
                  <!-- </VButtons> -->
                </template>
              </Column>
              <Column field="nocm" header="No RM" style="min-width: 80px"></Column>
              <Column field="noregistrasi" header="No Registrasi" style="min-width: 80px"></Column>
              <Column field="namaruangan" header="Ruangan" style="min-width: 80px"></Column>
              <Column field="keterangan" header="keterangan" style="min-width: 80px;"></Column>
              <Column field="tanggal" header="Tgl Open" :sortable="true" style="min-width: 80px">
              <template #body="slotProps">
                <span v-if="slotProps.data.tanggal != null">
                    {{ H.formatDate(slotProps.data.tanggal, 'DD-MM-YYYY HH:mm:ss') }}
                  </span>
              </template>
              </Column>
            </DataTable>
        </div>

        <!-- barunya sampai sini -->


      </div>
    </div>
  </VCard>
</template>
<script setup lang="ts">
import { useWindowScroll } from '@vueuse/core'
import { useApi } from '/@src/composable/useApi'
import { ref, computed, watch, reactive, onMounted } from 'vue'
import * as H from '/@src/utils/appHelper'
import { useRoute, useRouter } from 'vue-router'
import { useHead } from '@vueuse/head'
import { useToaster } from '/@src/composable/toaster'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useThemeColors } from '/@src/composable/useThemeColors'
import AutoComplete from 'primevue/autocomplete';
import moment from 'moment'
import ConfirmDialog from 'primevue/confirmdialog'
import Dialog from 'primevue/dialog';
import { useConfirm } from 'primevue/useconfirm'
import MultiSelect from 'primevue/multiselect';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column'

useHead({
  title: 'Riwayat Openbill - ' + import.meta.env.VITE_PROJECT,
})
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
const item: any = ref({
  periode: reactive({
    start: new Date(),
    end: new Date(),
    norec_pd: '',
  })
})


let listKelompokPasien: any = ref([])
let listRuangan: any = ref([])
const valueKelompok: any = ref(0);
const d_Ruangan: any = ref([])
let sourceRuangan: any = ref([])

let ds_PASIEN: any = ref([])
let listColor: any = ref(Object.keys(useThemeColors()))
const router = useRouter()
const route = useRoute()
const { y } = useWindowScroll()
const confirm = useConfirm();
const isLoading = ref(false)
const modalDetail = ref(false)
const dataSourceDetail: any = ref([])
const modalUpdatePiutang = ref(false)
const d_Status: any = ref([])
const dtTable:any = ref();
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
  console.log(currentPage.value)
  fetchPasien()
})

// watch(
//   () => item.value.periode,
//   () => (newValue, oldValue) => {
//     console.log("new Value", newValue)
//     console.log("OLDVALUE",oldValue);
    
//   }
// )

async function fetchPasien() {
  ds_PASIEN.value.loading = true

  let limit: any = currentPage.value.limit
  let offset: any = route.query.page ? route.query.page : 1
  offset = (offset * limit) - limit
  let namaPasien = ''
  let queryKelompok = ''

  // if(H.cacheHelper().get('filterRuang') != undefined) {
  //   sourceRuangan.value = H.cacheHelper().get('filterRuang');
  // }
  let ruanganid = ''
  if (sourceRuangan.value != undefined) {
    let itemsRuang = []
    sourceRuangan.value.forEach((element: any) => {
        itemsRuang = [...new Set([...itemsRuang, element.value])]
    });
    ruanganid = `&ruanganfk=${itemsRuang}`
  }

  H.cacheHelper().set('filterItem', item.value);

  try {
    if (item.value.qnama) namaPasien = `&namaPasien=${item.value.qnama}`
    if(item.value.kelompokPasienId != undefined && item.value.kelompokPasienId != null) queryKelompok = `&kelompokPasienId=${item.value.kelompokPasienId}`
    if(item.value.ruangan != undefined && item.value.ruangan.id != null) queryKelompok += `&ruanganId=${item.value.ruangan.id}`
  
    const response = await useApi().get(
      `/dashboard/riwayat-openbill?tglAwal=${moment(item.value.periode.start).format('YYYY-MM-DD')
      }&tglAkhir=${moment(item.value.periode.end).format('YYYY-MM-DD')}&statusverifikasi=false&offset=${offset}&rows=${currentPage.value.rows}${ruanganid}${namaPasien}${queryKelompok}`)
    ds_PASIEN.value.loading = false
  
    ds_PASIEN.value = response.data
    ds_PASIEN.value.total = response.total
    route.query.page = '1'
  } catch (error) {
    ds_PASIEN.value.loading = false
    H.alert('warning', 'Terjadi kesalahan, silahkan coba lagi')
  }
}


async function fetchDropdown() {
  const response = await useApi().get(`/dashboard/dropdown-rawat-jalan`)
  d_Ruangan.value = response.ruangansemua.map((e: any) => { return { label: e.namaruangan, value: e.id, default: e } })
  

  if(H.cacheHelper().get('ruanganDipilih')) {
    sourceRuangan.value = H.cacheHelper().get('ruanganDipilih');
  }else {
    d_Ruangan.value.forEach((element) => {
      sourceRuangan.value.push(element)
    })
  }
  fetchPasien()
}

function clearFilter() {
  delete item.value.qnama
  delete item.value.qnocm
  delete item.value.qnik
  delete item.value.qbpjs
  delete item.value.qalamat
  delete item.value.kelompokPasienId
  if(item.value.ruangan != undefined && item.value.ruangan.id != null) delete item.value.ruangan
  fetchPasien()
}
function filter() {
  fetchPasien()
}

const changeRuang = async (e: any) => {
        // setCache(e)
        H.cacheHelper().set('ruanganDipilih', sourceRuangan.value)
        fetchPasien()
    }
const exportCSV = (data) => {
  dtTable.value.exportCSV();
};


const exportCTRL = (data, field) => {
  return String(data.data);
}


onMounted(() => {
  fetchDropdown()
  if(H.cacheHelper().get('filterItem')) {
    item.value = H.cacheHelper().get('filterItem');
  }
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
