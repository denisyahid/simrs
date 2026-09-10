<template>
  <div class="column is-12">
    <VCard>
      <div class="columns is-multiline p-0">
        <div class="column is-4">
          <VField class="is-rounded-select is-autocomplete-select" label="Instalasi">
            <VControl icon="feather:search" class="prime-auto-select" :loading="isLoadingSelect">
              <Dropdown v-model="item.filter" :options="d_Filter" :optionLabel="'label'" placeholder="Pilih data"
                style="width: 100%;" showClear :filter="true" />
            </VControl>
          </VField>
        </div>
        <div class="column mt-5" style="margin-left: auto:  !important;">
          <VIconButton type="button" color="success mt-1" class="searcv-button" raised icon="fas fa-search"
            @click="fetchData()" :loading="isLoading">
          </VIconButton>
        </div>
      </div>
    </VCard>
  </div>
  <div class="column is-12">
    <VCard>
      <div class="column is-12">
        <h3 class="title is-5 mb-2 mr-1">Rekap Klaim By Diagnosa</h3>
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
        <!-- <VFlexTable :data="dataSource" :columns="columns" rounded>
          <template #body>
            <div name="list" tag="div" class="flex-list-inner">
              <div v-for="(item, i)  in dataSource" :key="item.id" class="flex-table-item">
                <VFlexTableCell>
                  <span class="light-text">{{ i + 1 }}</span>
                </VFlexTableCell>
                <VFlexTableCell>
                  <span class="light-text">{{ item.diaglist }}</span>
                </VFlexTableCell>
                <VFlexTableCell>
                  <span class="light-text">{{ item.namadiagnosa }}</span>
                </VFlexTableCell>
                <VFlexTableCell>
                  <span class="light-text">{{ H.formatRp(item.total, 'Rp') }}</span>
                </VFlexTableCell>
                <VFlexTableCell>
                  <span class="light-text">{{ item.qty }}</span>
                </VFlexTableCell>
              </div>
            </div>
          </template>
        </VFlexTable> -->
        <DataTable :value="dataSource" showGridlines tableStyle="min-width: 50rem">
          <Column field="no" header="NO"></Column>
          <Column field="diaglist" header="ICD 10"></Column>
          <Column field="namadiagnosa" header="Category"></Column>
          <Column field="total" header="Nama Diagnosa"></Column>
          <Column field="qty" header="Total"></Column>
        </DataTable>
      </div>
    </VCard>
  </div>
</template>
<script setup lang="ts">
import { useRoute, useRouter } from 'vue-router';
import { ref, computed, watch, reactive } from 'vue';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import { useViewWrapper } from '/@src/stores/viewWrapper';
import { useHead } from '@vueuse/head';
import * as H from '/@src/utils/appHelper';
import Dropdown from 'primevue/dropdown';
import { useApi } from '/@src/composable/useApi';

useHead({
  title: 'Rekap Klaim Diagnosa - ' + import.meta.env.VITE_PROJECT,
})
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT);
useViewWrapper().setFullWidth(true);

const d_Filter: any = ref([
  {
    value: 2,
    label: 'Rawat Jalan'
  },
  {
    value: 1,
    label: 'Rawat Inap'
  }
])
const item: any = reactive({});
const dataSource: any = ref([]);
const isLoading: boolean = ref(false);
const columns: any = ref({});
columns.value = {
  no: {
    label: 'No',
    align: 'start'
  },
  diaglist: {
    label: 'ICD10',
    align: 'start'
  },
  namadiagnosa: {
    label: 'Nama Diagnosa',
    align: 'start'
  },
  total: {
    label: 'Total',
    // cellClass: 'h-hidden-tablet-p',
    align: 'start'
  },
  qty: {
    label: 'Qty',
    // cellClass: 'h-hidden-tablet-p',
    align: 'start',
  },
}

const fetchData = async () => {
  if (!item.filter) {
    H.alert('warning', 'Silakan Pilih Insatalasi Terlebih Dahulu ? ');
    return;
  }
  isLoading.value = true;
  await useApi().get(
    `piutang/rekap-klaim-by-diagnosa?ptd=${item.filter.value}`
  ).then((response) => {
    response.data.map((element: any, index: number) => {
      element.no = index + 1,
        element.total = H.formatRp(element.total, 'Rp')
    })
    dataSource.value = response.data
    isLoading.value = false;
  })
}
</script>
