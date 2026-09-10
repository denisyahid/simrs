<template>
  <VCard>
    <div class="column c-title-x">
      <h3 class="title is-5 mb-2 mr-1">{{ title }}</h3>
    </div>
    <div class="column is-12">
      <div class="columns is-multiline">
        <div class="column is-4">
          <VField label="Nama Rekanan" class="is-rounded-select  is-autocomplete-select" v-slot="{ id }">
            <VControl icon="fa:bookmark" class="prime-auto-select">
              <AutoComplete v-model="item.namarekanan" :suggestions="d_Rekanan" @complete="fetchRekanan($event)"
                :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                :field="'label'" placeholder="ketik nama rekanan .." showClear />
            </VControl>
          </VField>
        </div>
        <div class="column is-1 mt-5 ">
          <VIconButton type="button" color="success" circle raised icon="fas fa-search" @click="fetchData()"
            :loading="isLoading">
          </VIconButton>
        </div>
      </div>
    </div>
    <div class="column is-12">
      <div class="columns is-multiline">
        <div class="column is-12">
          <div v-if="dataSource.length == 0">
            <VPlaceholderSection :title="H.assets().notFound" :subtitle="H.assets().notFoundSubtitle" class="my-6">
              <template #image>
                <img class="light-image" :src="H.assets().iconNotFound_rev" alt="" />
                <img class="dark-image" src="/@src/assets/illustrations/placeholders/search-4-dark.svg" alt="" />
              </template>
            </VPlaceholderSection>
          </div>
          <DataTable v-else v-model:expandedRowGroups="expandedRowGroups" :class="`p-datatable-small`" :value="dataSource"
            v-model:filters="filtersTrans" :globalFilterFields="['namarekanan']" rowGroupMode="subheader"
            :rowsPerPageOptions="[5, 10, 25, 100]" :rows="5" paginator groupRowsBy="namarekanan" :loading="isLoading"
            @rowgroup-expand="onRowGroupExpand" @rowgroup-collapse="onRowGroupCollapse" sortMode="single"
            sortField="namarekanan" :sortOrder="5" showGridlines>
            <template #header>
              <div class="columns is-multiline">
                <div class="column is-3">
                  <VButton type="button" icon="pi pi-file-excel" class="mr-3" color="info" outlined circle raised
                    v-tooltip-prime="'Export'" @click="exportExcel()">
                    Export Excel
                  </VButton>
                </div>
                <div class="column is-3 is-offset-6">
                  <VField>
                    <VControl icon="feather:search">
                      <input v-model="filtersTrans['global'].value" v-on:keyup.enter="fetchData()" type="text"
                        class="input is-rounded" placeholder="Search" />
                    </VControl>
                  </VField>
                </div>
              </div>
            </template>
            <ColumnGroup type="header">
              <Row>
                <Column header="Nama Pasien" :rowspan="2" />
                <Column header="No Registrasi" :rowspan="2" />
                <Column header="Tanggal Pulang" :rowspan="2" />
                <Column header="Total Tagihan" :rowspan="2" />
                <Column header="Umur Piutang" :rowspan="2" />
                <Column header="3 Bulan" :colspan="2" />
                <Column header="6 Bulan" :colspan="2" />
                <Column header="9 Bulan" :colspan="2" />
                <Column header="12 Bulan" :colspan="2" />
                <Column header="Setelah 12 Bulan" :colspan="2" />
                <Column header="Sisa Piutang" :rowspan="2" />
                <Column header="Status Piutang" :rowspan="2" />
              </Row>
              <Row>
                <Column header="Sisa" />
                <Column header="Dibayar" />
                <Column header="Sisa" />
                <Column header="Dibayar" />
                <Column header="Sisa" />
                <Column header="Dibayar" />
                <Column header="Sisa" />
                <Column header="Dibayar" />
                <Column header="Sisa" />
                <Column header="Dibayar" />
              </Row>
            </ColumnGroup>
            <template #groupheader="slotProps">
              <span class="vertical-align-middle ml-2 font-bold line-height-3">{{ slotProps.data.namarekanan
              }}</span>
            </template>
            <Column field="namapasien" />
            <Column field="noregistrasi" />
            <Column field="tglTransaksi" />
            <Column field="totalTagihan" />
            <Column field="umur" />
            <Column field="sisaTigaBulan" />
            <Column field="dibayarTigaBulan" />
            <Column field="sisaEnamBulan" />
            <Column field="dibayarEnamBulan" />
            <Column field="sisaSembilanBulan" />
            <Column field="dibayarSembilanBulan" />
            <Column field="sisaDuaBelasBulan" />
            <Column field="dibayarDuaBelasBulan" />
            <Column field="sisaLebihDuaBelasBulan" />
            <Column field="dibayarLebihDuaBelasBulan" />
            <Column field="sisaTagihan" />
            <Column>
              <template #body="slotProps">
                <Tag :value="slotProps.data.status" :severity="getSeverity(slotProps.data)" />
              </template>
            </Column>
          </DataTable>
        </div>
      </div>
    </div>
  </VCard>
</template>
<script setup lang="ts">
import { useRoute, useRouter } from 'vue-router';
import { ref, computed, watch, reactive } from 'vue';
import DataTable from 'primevue/datatable';
import { useViewWrapper } from '/@src/stores/viewWrapper';
import Column from 'primevue/column';
import { useHead } from '@vueuse/head';
import * as H from '/@src/utils/appHelper';
import AutoComplete from 'primevue/autocomplete';
import moment from 'moment';
import { useApi } from '/@src/composable/useApi';
import Calendar from 'primevue/calendar';
import ProgressBar from 'primevue/progressbar';
import ColumnGroup from 'primevue/columngroup';
import Row from 'primevue/row';
import { FilterMatchMode } from 'primevue/api';
import Tag from 'primevue/tag';
const title = 'Umur Piutang'
useHead({
  title: 'Umur Piutang - ' + import.meta.env.VITE_PROJECT,
})
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)

const expandedRowGroups: any = ref();
const dataSource: any = ref([]);
const d_Rekanan: any = ref([]);
const item: any = reactive({});
const isLoading: any = ref(false);
const filtersTrans = ref({ global: { value: null, matchMode: FilterMatchMode.CONTAINS }, });

const fetchData = async () => {
  isLoading.value = true;
  let namaRekanan = item.namarekanan ? item.namarekanan.value : ''
  await useApi().get(`piutang/umur-piutang?rekananfk=${namaRekanan}`).then((response: any) => {
    response.data.map((data: any, index) => {
      data.tglTransaksi = moment(data.tglstruk).format('DD-MM-YYYY HH:mm:ss'),
        data.sisaTigaBulan = H.formatRp(data.bulan3.sisa, 'Rp'),
        data.sisaEnamBulan = H.formatRp(data.bulan6.sisa, 'Rp'),
        data.sisaSembilanBulan = H.formatRp(data.bulan9.sisa, 'Rp'),
        data.sisaDuaBelasBulan = H.formatRp(data.bulan12.sisa, 'Rp'),
        data.sisaLebihDuaBelasBulan = H.formatRp(data.lebihdari12.sisa, 'Rp'),
        data.dibayarTigaBulan = H.formatRp(data.bulan3.totaldibayar, 'Rp'),
        data.dibayarEnamBulan = H.formatRp(data.bulan6.totaldibayar, 'Rp'),
        data.dibayarSembilanBulan = H.formatRp(data.bulan9.totaldibayar, 'Rp'),
        data.dibayarDuaBelasBulan = H.formatRp(data.bulan12.totaldibayar, 'Rp'),
        data.dibayarLebihDuaBelasBulan = H.formatRp(data.lebihdari12.totaldibayar, 'Rp'),
        data.totalTagihan = H.formatRp(data.totalklaim, 'Rp'),
        data.sisaTagihan = H.formatRp((data.totalklaim - data.sudahbayar), 'Rp')
      isLoading.value = false;
    })
    dataSource.value = response.data
  })
}

const fetchRekanan = async (filter: any) => {
  await useApi().get(
    `emr/dropdown/rekanan_m?select=id,namarekanan&param_search=namarekanan&query=${filter.query}&limit=10`
  ).then((response) => {
    d_Rekanan.value = response
  })
}
const exportExcel = () => {

}
const getSeverity = (data: any) => {
  switch (data.status) {
    case 'Lunas':
      return 'success';
    default:
      return 'warning';
  }
};
fetchData();
</script>
<style lang="scss"></style>
