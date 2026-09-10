<template>
  <div class="column is-12">
    <VCard style="padding-bottom: 0px">
      <div class="column c-title pt-2 mb-5">
        <label class="title-page">Working Capital</label>
      </div>
      <div class="column is-12 p-0">
        <div class="columns is-multiline">
          <!-- <div class="column is-4">
            <VField label="Periode">
              <VControl class="prime-auto">
                <Calendar
                  inputId="range"
                  v-model="item.qBulan"
                  selectionMode="range"
                  :manualInput="false"
                  class="w-100 mb-4 is-rounded"
                  :showIcon="true"
                  date-format="yy-mm-dd"
                />
              </VControl>
            </VField>
          </div>
          <div class="column mt-5">
            <VButton
              type="button"
              icon="feather:search"
              color="primary"
              raised
              :loading="isLoading"
              @click="fetchData()"
            >
              Cari
            </VButton>
            <VButton
              type="button"
              icon="feather:send"
              color="info"
              raised
              :loading="isLoading2"
              @click="kirimData()"
              class="ml-2"
            >
              Kirim Data
            </VButton>
          </div> -->

          <div class="column is-12">
            <div class="columns is-multiline">
              <br />
              <div class="column is-12">
                <VCard>
                  <TabView
                    class="tabview-custom mt-3"
                    :scrollable="true"
                    @tab-click="klikTab($event)"
                  >
                    <TabPanel>
                      <template #header>
                        <span>Receivable</span>
                      </template>
                      <div class="columns is-multiline">
                        <div class="column is-12">
                          <Receivable v-if="activeTab == 0" />
                        </div>
                      </div>
                    </TabPanel>
                    <TabPanel>
                      <template #header>
                        <span>Payable</span>
                      </template>
                      <div class="columns is-multiline">
                        <div class="column is-12">
                          <Payable v-if="activeTab == 1" />
                        </div>
                      </div>
                    </TabPanel>
                    <TabPanel>
                      <template #header>
                        <span>Inventory</span>
                      </template>
                      <div class="columns is-multiline">
                        <div class="column is-12">
                          <Inventory v-if="activeTab == 2" />
                        </div>
                      </div>
                    </TabPanel>
                  </TabView>
                </VCard>
              </div>
            </div>
          </div>
        </div>
      </div>
    </VCard>
  </div>
</template>

<script setup lang="ts">
import { useRoute, useRouter } from 'vue-router'
import { ref, computed, watch, reactive } from 'vue'
import DataTable from 'primevue/datatable'
import Column from 'primevue/column'
import AutoComplete from 'primevue/autocomplete'
import { useThemeColors } from '/@src/composable/useThemeColors'
import * as H from '/@src/utils/appHelper'
import { formatRp } from '/@src/utils/appHelper'
import { useApi } from '/@src/composable/useApi'
import { useUserSession } from '/@src/stores/userSession'
import moment from 'moment'
import { useHead } from '@vueuse/head'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import Calendar from 'primevue/calendar'
import Dropdown from 'primevue/dropdown'
import ColumnGroup from 'primevue/columngroup' // optional
import Row from 'primevue/row'
import * as XLSX from 'xlsx'
import Card from 'primevue/card'
import { FilterMatchMode } from 'primevue/api'
import InputText from 'primevue/inputtext'
import Fieldset from 'primevue/fieldset'
import TabView from 'primevue/tabview'
import TabPanel from 'primevue/tabpanel'
import Receivable from './lap-receivable.vue'
import Payable from './lap-payable.vue'
import Inventory from './laporan-inventory.vue'
useHead({
  title: 'Rekap Pendapatan Keuangan - ' + import.meta.env.VITE_PROJECT,
})
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)

const filters = ref({
  global: { value: null, matchMode: FilterMatchMode.CONTAINS },
})
const arrGroup: any = ref([
  {
    bulan: new Date(),
    pendapatan_bunga_bank: 0,
    deposito: 0,
    pend_lainnya: 0,
    pend_apbn_lainnya: 0,
    pendapatan_hibah: 0,
    pendapatan_blu_lainnya: 0,
    biaya_bunga_bank: 0,
    jumlah: 0,
  },
])
const item: any = ref({
  persen: 0,
  persenLab: 0,
  persenRad: 0,
  persenIGD: 0,
  persenRanap: 0,
  qBulan: [new Date(), new Date()],
})
const activeTab: any = ref()
activeTab.value =0
const kirimDataD: any = ref({})
const isLoading2: any = ref(false)
const isLoading = ref(false)

const kirimData = async () => {
  let tglAwal = H.formatDate(item.value.qBulan[0], 'YYYY-MM-DD 00:00:00')
  let tglAkhir = H.formatDate(
    item.value.qBulan[1] ? item.value.qBulan[1] : item.value.qBulan[0],
    'YYYY-MM-DD 23:59:59'
  )
  isLoading2.value = true
  let response = await useApi().get(
    `mkko/send-operasional-lain?dari=${tglAwal}&sampai=${tglAkhir}`
  )
  isLoading2.value = false
  if (response.status == 200) {
    H.alert('success', 'Sukses')
  } else {
    H.alert('error', response.result)
  }
  isLoading2.value = false
}
const exportExcel = (data: any, filename: any) => {
  H.exportExcel(data, filename)
}

const klikTab = (e: any) => {
  activeTab.value = e.index
}
</script>

<style lang="scss">
@import '/@src/scss/abstracts/all';

.p-card .p-card-title {
  font-size: 1.2rem;
  font-weight: 700;
  margin-bottom: 0.5rem;
}

.c-title {
  margin-left: -21px;
  padding-top: 21px;
  padding-top: 18px;
  margin-top: -21px;
  border-top-left-radius: 11px;
  border-left: solid hsl(19deg 100% 75% / 72%) 3px;
  padding-bottom: 0px;
  margin-bottom: 2rem;
}

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

// .title-page {
//   font-weight: 600;
//   font-size: 18px;
// }

.tg {
  border-collapse: collapse;
  border-spacing: 0;
  width: 100%;
}

.tg td {
  border-color: var(--fade-grey-dark-2);
  border-style: solid;
  border-width: 1px;
  font-family: Arial, sans-serif;
  font-size: 14px;
  overflow: hidden;
  padding: 10px 5px;
  word-break: normal;
  vertical-align: middle;
}

.tg th {
  border-color: var(--fade-grey-dark-3);
  border-style: solid;
  border-width: 1px;
  font-family: Arial, sans-serif;
  font-size: 14px;
  font-weight: normal;
  padding: 10px 5px;
  word-break: normal;
  vertical-align: middle;
  text-align: center !important;
}

.tg .tg-0lax {
  text-align: left;
  vertical-align: top;
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

      > img {
        display: block;
        width: 200px;
        height: 200px;
        min-width: 200px;
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
            font-size: 1rem;
          }

          &:nth-child(2) {
            display: flex;
            align-items: center;

            span {
              display: inline-block;
              color: var(--light-text);
              font-size: 0.8rem;
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
</style>
