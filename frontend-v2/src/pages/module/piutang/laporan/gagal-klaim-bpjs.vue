<template>
  <div class="column">
    <VCard>
      <div class="column is-12">
        <h1 class="title is-5 mb-2 mr-1">Daftar Gagal Klaim BPJS</h1>
      </div>
      <div class="column is-12">
        <div class="search-widget">
          <div class="field">
            <div class="columns is-multiline">
              <div class="column is-4">
                <span>Periode</span>
                <VDatePicker v-model="item.periode" is-range color="pink" trim-weeks class="pt-2">
                  <template #default="{ inputValue, inputEvents }">
                    <VField addons>
                      <VControl icon="feather:calendar">
                        <VInput :value="inputValue.start" class="input-calendar" v-on="inputEvents.start" />
                      </VControl>
                      <VControl>
                        <VButton static><i class="fas fa-arrow-right" aria-hidden="true"></i>
                        </VButton>
                      </VControl>
                      <VControl icon="feather:calendar">
                        <VInput :value="inputValue.end" class="input-calendar" v-on="inputEvents.end" />
                      </VControl>
                    </VField>
                  </template>
                </VDatePicker>
              </div>
              <div class="column is-4">
                <span class="mb-5">Nama Pasien</span>
                <VField>
                  <VInput class="mt-2" placeholder="Masukan Nama Pasien.." v-model="item.namaPasien"></VInput>
                </VField>
              </div>
              <div class="column" style="margin-top: 25px; margin-left: auto:  !important;">
                <VIconButton type="button" color="success" class="searcv-button" raised icon="fas fa-search"
                  @click="fetchData()" :loading="isLoading">
                </VIconButton>
              </div>
            </div>
          </div>
        </div>
      </div>
      <Divider />
      <div class="column" v-if="isLoading">
        <VPlaceloadWrap v-for="data in 10">
          <VPlaceload class="mx-2 mb-3" />
        </VPlaceloadWrap>
      </div>
      <div v-else-if="dataSource.length == 0">
        <VPlaceholderSection :title="H.assets().notFound" :subtitle="H.assets().notFoundSubtitle" class="my-6">
          <template #image>
            <img class="light-image" :src="H.assets().iconNotFound_rev" alt="" />
            <img class="dark-image" src="/@src/assets/illustrations/placeholders/search-4-dark.svg" alt="" />
          </template>
        </VPlaceholderSection>
      </div>
      <div class="column is-12" v-else>
        <DataTable :value="dataSource" :paginator="true" :rows="5" :rowsPerPageOptions="[5, 10, 25]"
          class="p-datatable-customers p-datatable-sm" filterDisplay="menu"
          paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
          responsiveLayout="stack" breakpoint="960px" sortMode="multiple" showGridlines
          currentPageReportTemplate="Showing {first} to {last} of {totalRecords}" :loading="isLoading">
          <Column field="nosep" header="NOSEP" style="min-width: 120px"></Column>
          <Column field="tglsep" header="TGLSEP" style="min-width: 70px"></Column>
          <Column field="nokartu" header="NO KARTU" style="min-width: 100px"></Column>
          <Column field="nmpeserta" header="NAMA PESERTA" style="min-width: 120px"></Column>
          <Column field="rirj" header="RIRJ" style="min-width: 60px"></Column>
          <Column field="kdinacbg" header="KD INACBG" style="min-width: 100px"></Column>
          <Column field="bypengajuan" header="BY PENGAJUAN" style="min-width: 100px"></Column>
          <Column field="keterangan" header="KETERANGAN" style="min-width: 200px"></Column>
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
import { useApi } from '/@src/composable/useApi';
import Divider from 'primevue/divider';
import moment from 'moment';
useHead({
  title: 'Data Gagal Klaim Bpjs - ' + import.meta.env.VITE_PROJECT,
})
const item: any = ref({
  periode: reactive({
    start: new Date(),
    end: new Date(),
  }),
})
const route = useRoute();
const isLoading: any = ref(false);
const dataSource: any = ref([]);

const fetchData = async () => {
  isLoading.value = true;
  let tglAwal = `${moment(item.value.periode.start).format('DD-MM-YYYY')}`
  let tglAkhir = `${moment(item.value.periode.end).format('DD-MM-YYYY')}`
  await useApi().get(`/piutang/gagal-klaim-bpjs?tglAwal=${tglAwal}&tglakhir=${tglAkhir}`).then(response => {
    dataSource.value = response.data;
  })
  isLoading.value = false;
}
fetchData();
</script>
<style lang="scss">
@import '/@src/scss/module/piutang/piutang.scss';

.control.has-icon {
  position: relative;
  width: 100%;
}

.field:not(:last-child) {
  margin-bottom: 0px !important;
}
</style>
