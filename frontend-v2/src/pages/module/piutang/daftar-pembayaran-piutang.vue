<template>
  <div class="columns is-multiline">
    <div class="column is-12 ">
      <VCard>
        <div class="columns is-multiline">
          <div class="column is-12">
            <h3 class="title is-5 mb-2 mr-1">PEMBAYARAN PIUTANG PERUSAHAAN</h3>
          </div>
        </div>
        <div class="column is-12">
          <div class="search-widget">
            <div class="field pt-5 pb-5">
              <div class="columns is-multiline">
                <div class="column is-3  pt-0 pb-0">
                  <span>Periode</span>
                  <div class="is-flex mt-3">
                    <VField v-for="data in filters" :key="data.value" style="padding:0px;">
                      <VControl raw subcontrol>
                        <VCheckbox v-model="item.filter" class="pt-1 pb-1" :true-value="data.value" :label="data.label"
                          color="primary" circle />
                      </VControl>
                    </VField>
                  </div>
                </div>
                <div class="column is-3">
                  <span>{{ item.filter == 'Bulan' ? 'Bulan' : 'Tanggal' }}</span>
                  <VField>
                    <VControl class="prime-auto">
                      <div v-if="item.filter == 'Bulan'">
                        <Calendar inputId="range" v-model="item.bulan" selectionMode="single" :manualInput="false"
                          class="w-100" :showIcon="true" view="month" dateFormat="mm/yy" />
                      </div>
                      <div v-else>
                        <Calendar inputId="range" v-model="item.tanggal" selectionMode="range" :manualInput="false"
                          class="w-100" :showIcon="true" :date-format="'yy-mm-dd'" />
                      </div>
                    </VControl>
                  </VField>
                </div>
                <div class="column pt-0 pb-0 is-3">
                  <span>Nama Perusahaan</span>
                  <VField class="is-autocomplete-select pt-2 mt-1">
                    <VControl icon="feather:search">
                      <AutoComplete v-model="item.perusahaan" :suggestions="d_Rekanan" @complete="fetchRekanan($event)"
                        :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                        :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="Nama Perusahaan.." />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2 pt-0 pb-0">
                  <span>No Collecting</span>
                  <VField class="is-autocomplete-select mt-1 pt-2">
                    <VControl icon="feather:search">
                      <VInput v-model="item.noCollection"></VInput>
                    </VControl>
                  </VField>
                </div>
                <div class="column mt-5" style="margin-left: auto:  !important;">
                  <VIconButton type="button" color="success" class="searcv-button" raised icon="fas fa-search"
                    @click="fetchData()" :loading="isLoadingBtn">
                  </VIconButton>
                </div>
              </div>
            </div>
          </div>
        </div>
      </VCard>
    </div>
    <div class="column is-12">
      <SelectButton v-model="item.tab" :options="d_Tab" aria-labelledby="basic" class="is-pulled-left mb-2" />
      <VCard>
        <!-- <div class="columns ">
          <div class="column ">
            <h3 class="title is-5 mb-2 mr-1">{{ item.tab == 'Rekap' ? 'Rekapitulasi' : 'Daftar' }} Pembayaran Piutang
            </h3>
          </div>
        </div> -->
        <div class="column is-12" v-if="dataSource.length > 0">
          <VButton color="warning" icon="fas fa-print" raised rounded @click="cetakRekap()" v-if="item.tab == 'Rekap'">
            Cetak Rekap
          </VButton>
          <VButton color="warning" icon="fas fa-print" raised rounded @click="cetakLaporan()"
            v-if="item.tab == 'Laporan'">
            Cetak Laporan
          </VButton>
        </div>
        <div class="flex-list-inner mb-2 mt-5" v-if="isLoading">
          <div class="flex-table-item grid-item mb-1" v-for="key in 5" :key="key">
            <VPlaceloadWrap>
              <VPlaceloadAvatar size="small" />
              <VPlaceloadText last-line-width="60%" class="mx-2" />
              <VPlaceload class="mx-2" disabled />
              <VPlaceload class="mx-2 h-hidden-tablet-p" />
              <VPlaceload class="mx-2 h-hidden-tablet-p" />
              <VPlaceload class="mx-2" />
            </VPlaceloadWrap>
          </div>
        </div>
        <div class="flex-list-inner" v-else-if="dataSource.length === 0">
          <VPlaceholderSection :title="H.assets().notFound" :subtitle="H.assets().notFoundSubtitle" class="my-6">
            <template #image>
              <img class="light-image" :src="H.assets().iconNotFound_rev" alt="" />
              <img class="dark-image" src="/@src/assets/illustrations/placeholders/search-4-dark.svg" alt="" />
            </template>
          </VPlaceholderSection>
        </div>
        <div v-else>
          <VFlexTable :data="dataSource" :columns="columns" rounded v-if="item.tab == 'Laporan'">
            <template #body>
              <div name="list" tag="div" class="flex-list-inner">
                <div v-for="(item, i)  in dataSource" :key="item.id" class="flex-table-item">
                  <VFlexTableCell :column="{ media: true, grow: true }">
                    <VAvatar size="small" :color="listColor[i]" :initials="item.initials" />
                    <div>
                      <span class="item-name dark-inverted"
                        style="  width: 100px;  white-space: nowrap; overflow: hidden !important; text-overflow: ellipsis;">{{
                          item.namarekanan }}</span>
                      <span class="item-meta">
                        <span>
                          <i aria-hidden="true" class="iconify" data-icon="feather:user">
                          </i>{{ item.noPosting }}</span>
                      </span>
                    </div>
                  </VFlexTableCell>
                  <VFlexTableCell>
                    <span class="light-text">{{ H.formatDateIndoSimpleNoDay(item.tglBayar) }}</span>
                  </VFlexTableCell>
                  <VFlexTableCell>
                    <span class="light-text">{{ item.keterangan }}</span>
                  </VFlexTableCell>
                  <VFlexTableCell>
                    <span class="light-text">{{ H.formatRp(0, 'Rp') }}</span>
                  </VFlexTableCell>
                  <VFlexTableCell>
                    <span class="light-text">{{ H.formatRp(item.totalBayar, 'Rp') }}</span>
                  </VFlexTableCell>
                </div>
              </div>
            </template>
          </VFlexTable>
          <VFlexTable :data="dataSourceRekap" :columns="columns2" rounded v-if="item.tab == 'Rekap'">
            <template #body>
              <div name="list" tag="div" class="flex-list-inner">
                <div v-for="(item, i)  in dataSourceRekap" :key="item.id" class="flex-table-item">
                  <VFlexTableCell>
                    <span class="light-text">{{ H.formatDateIndoSimpleNoDay(item.tglBayar) }}</span>
                  </VFlexTableCell>
                  <VFlexTableCell>
                    <span class="light-text">{{ H.formatRp(0, 'Rp') }}</span>
                  </VFlexTableCell>
                  <VFlexTableCell>
                    <span class="light-text">{{ H.formatRp(item.totalBayar, 'Rp') }}</span>
                  </VFlexTableCell>
                </div>
              </div>
            </template>
          </VFlexTable>
          <VCard>
            <div class="columns is-multiline">
              <div class="column is-4 is-offset-8">
                <b>TOTAL : {{ H.formatRp(item.totalAll, 'Rp. ') }}</b>
              </div>
            </div>
          </VCard>
        </div>
      </VCard>
    </div>
  </div>
</template>
<script setup lang="ts">
import { useRoute, useRouter } from 'vue-router'
import { ref, computed, watch, reactive } from 'vue'
import { useThemeColors } from '/@src/composable/useThemeColors'
import * as H from '/@src/utils/appHelper'
import { useApi } from '/@src/composable/useApi'
import { useUserSession } from '/@src/stores/userSession'
import { useHead } from '@vueuse/head'
import SelectButton from 'primevue/selectbutton';
import Calendar from 'primevue/calendar'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import AutoComplete from 'primevue/autocomplete'
useHead({
  title: 'Daftar Pembayaran Piutang- ' + import.meta.env.VITE_PROJECT,
})
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)

const listColor: any = ref(Object.keys(useThemeColors()))
const dataSource: any = ref([])
const dataSourceRekap: any = ref([])
const isLoadingBtn: boolean = ref(false)
const isLoading: boolean = ref(false)
const d_Rekanan: any = ref([])
let columns: any = ref({})
let columns2: any = ref({})
let startDate: any = ref('');
let endDate: any = ref('');
let noPosting: any = ref('');
let perusahaan: any = ref('');

columns.value = {
  namarekanan: {
    label: 'NAMA REKANAN',
    grow: true,
    media: true,
  },
  tanggal: 'TANGGAL',
  ketarangan: {
    label: 'KETERANGAN',
    cellClass: 'h-hidden-tablet-p',
  },
  adm: 'ADM',
  subtotal: {
    label: 'SUBTOTAL',
    cellClass: 'h-hidden-tablet-p',
  },
}
columns2.value = {
  tanggal: 'TANGGAL',
  adm: 'ADM',
  subtotal: {
    label: 'SUBTOTAL',
    cellClass: 'h-hidden-tablet-p',
  },
}
const d_Tab = ref(['Laporan', 'Rekap']);
const item: any = reactive({
  tab: d_Tab.value[0],
  tanggal: [
    new Date(),
    new Date()
  ],
})
const filters: any = ref([
  {
    label: 'Bulan',
    value: 'Bulan',
    model: 'filter'
  },
  {
    label: 'Tanggal',
    value: 'Tanggal',
    model: 'filter'
  }
]);

const fetchData = async () => {
  isLoading.value = true;
  noPosting.value = item.noCollection ? item.noCollection : '';
  perusahaan.value = item.perusahaan ? item.perusahaan.value : '';
  if (item.filter == 'Bulan') {
    const selectedDate = new Date(item.bulan);
    const firstDayOfMonth = new Date(selectedDate.getFullYear(), selectedDate.getMonth(), 1);
    const lastDayOfMonth = new Date(selectedDate.getFullYear(), selectedDate.getMonth() + 1, 0);
    const startDateString = firstDayOfMonth.toISOString();
    const endDateString = lastDayOfMonth.toISOString();
    startDate.value = H.formatDate(startDateString, 'YYYY-MM-DD 00:00:00');
    endDate.value = H.formatDate(endDateString, 'YYYY-MM-DD 23:59');
  } else {
    if (item.tanggal) {
      if (item.tanggal[0]) {
        startDate.value = H.formatDate(item.tanggal[0], 'YYYY-MM-DD 00:00:00')
      }
      if (item.tanggal[1]) {
        endDate.value = H.formatDate(item.tanggal[1], 'YYYY-MM-DD 23:59:59')
      } else {
        endDate.value = H.formatDate(item.tanggal[0], 'YYYY-MM-DD 23:59:59')
      }
    }
  }
  await useApi().get(
    `piutang/daftar-pembayaran-piutang-perusahaan-periode?tglAwal=${startDate.value}&tglAkhir=${endDate.value}&idPerusahaan=${perusahaan.value}&noPosting=${noPosting.value}`
  ).then((response) => {
    let total = 0;
    response[0].data.map((element: any, index: number) => {
      element.initials = calculateInitials(element.namarekanan),
        total += parseFloat(element.totalBayar)
    })
    item.totalAll = total
    dataSource.value = response[0].data
    dataSourceRekap.value = response[0].rekap

    let objSave = {
        tglAwal:  startDate.value,
        tglAkhir: endDate.value
    }
    useApi().postNoMessage(`akuntansi/post-jurnal-pembayaran-piutang-rekanan`, objSave)
    isLoading.value = false;
  })

}
const cetakRekap = () => {
  H.printBlade(`report/piutang/rekap-pembayaran?tglAwal=${startDate.value}&tglAkhir=${endDate.value}&idPerusahaan=${perusahaan.value}&noPosting=${noPosting.value}`)
}

const cetakLaporan = () => {
  H.printBlade(`report/piutang/daftar-pembayaran?pdf=true&tglAwal=${startDate.value}&tglAkhir=${endDate.value}&idPerusahaan=${perusahaan.value}&noPosting=${noPosting.value}`)
}
function calculateInitials(element: any) {
  let ini = element.split(' ');
  let init = element.substr(0, 1);

  if (ini.length > 1) {
    init = init + ini[1].substr(0, 1);
  }

  return init;
}
const fetchRekanan = async (filter: any) => {
  await useApi().get(
    `emr/dropdown/rekanan_m?select=id,namarekanan&param_search=namarekanan&query=${filter.query}&limit=10`
  ).then((response) => {
    d_Rekanan.value = response
  })
}
</script>
<style style lang = "scss" >
@import '/@src/scss/abstracts/all';
@import '/@src/scss/module/piutang/piutang.scss';

.p-selectbutton .p-button {
  background: var(--transparent);
  border-color: var(--border);
}

.p-selectbutton .p-button.p-highlight:hover {
  background: var(--success);
  border-color: var(--success);
}

.total-card {
  // padding: 10px;
  background-color: #F5F6FA;

  .inner {
    display: flex;
    align-items: center;
    gap: 0.2rem;
  }
}

.content-total {
  display: flex;
  align-items: center;
  gap: 0.2rem;

  .meta {
    align-items: center;
    gap: 0.2rem;

    .tag {
      display: flex;
      align-items: center;
      gap: 0.2rem;

      p {
        font-size: 0.85rem;
        color: var(--light-text);
        font-family: var(--font);
        width: auto;
        white-space: nowrap;
        overflow: hidden !important;
        text-overflow: ellipsis;
      }

      h1 {
        color: var(--dark-text);
        font-family: var(--font-alt);
        font-weight: 600;
        font-size: 0.9rem;
      }
    }

  }
}
</style>
