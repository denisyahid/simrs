<template>
  <div class="columns is-multiline">
    <div class="column is-5" >

      <VDatePicker v-model="filterTgl" is-range color="pink" trim-weeks>
        <template #default="{ inputValue, inputEvents }">
          <VField addons>

            <VControl icon="feather:calendar">
              <VInput :value="inputValue.start" v-on="inputEvents.start" />
            </VControl>
            <VControl>
              <VButton static><i class="fas fa-arrow-right" aria-hidden="true"></i></VButton>
            </VControl>
            <VControl icon="feather:calendar">
              <VInput :value="inputValue.end" v-on="inputEvents.end" />
            </VControl>
          </VField>
        </template>
      </VDatePicker>
    </div>
    <div class="column is-2">
      <VIconButton icon="feather:search" @click="  () => { emits('cariMon', filterTgl) }" color="success" raised :loading="props.loadingMon" circle
     >
      </VIconButton>
    </div>
    <div class="column is-12 mt-2-min">
      <DataTable :value="props.dataSourceHistory" paginator :rows="5" :rowsPerPageOptions="[5, 10, 20, 50]"
        v-model:filters="filters" :globalFilterFields="['noSep', 'poli', 'ppkPelayanan']" filterDisplay="menu"
        tableStyle="min-width: 50rem">
        <template #header>
          <div class="flex justify-content-end">
            <span class="p-input-icon-left">
              <InputText v-model="filters.global.value" placeholder="Keyword Search" style="width:300px" />
            </span>
          </div>
        </template>
        <template #empty> No data found. </template>

        <Column field="noSep" header="No SEP" style="width: 150px">
          <template #body="slotProps">
            <VButton color="info" outlined raised @click="() => { emits('pilihSEP', slotProps.data) }"
              :loading="slotProps.data.isLoading">
              <span class="icon">
                <i aria-hidden="true" class="fas fa-check-circle"></i>
              </span>
              <span>{{ slotProps.data.noSep }}</span>
            </VButton>
          </template>
        </Column>

        <Column field="jnsPelayanan" header="RI/RJ" style="width: 150px">
          <template #body="slotProps">{{ slotProps.data.jnsPelayanan == 1?'RI':"RJ" }}</template>
        </Column>
        <Column field="tglSep" header="Tgl SEP " style="width: 150px">

        </Column>
        <Column field="tglPlgSep" header="Tgl Pulang" style="width: 150px">
          <template #body="slotProps">
            {{ slotProps.data.jnsPelayanan == 2 ?slotProps.data.tglSep: slotProps.data.tglPlgSep}}
            </template></Column>
        <Column field="diagnosa" header="Diagnosa" style="width: 150px"></Column>
        <Column field="noRujukan" header="No Rujukan" style="width: 150px"></Column>
        <Column field="poli" header="Spesialis" style="width: 150px"></Column>
        <Column field="ppkPelayanan" header="PPK Pelayanan" style="width: 150px"></Column>
      </DataTable>
    </div>
  </div>
</template>
<script setup lang="ts">
import * as H from '/@src/utils/appHelper'
import { FilterMatchMode } from 'primevue/api';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import InputText from 'primevue/inputtext';

import { h, reactive, ref, computed, defineComponent, watch, PropType } from 'vue'
import { boolean } from 'zod';
const props = defineProps({
  dataSourceHistory: {
    type: Array as PropType<any>,
  },
  loadingMon:  {
    type: Object as PropType<any>,
  },
})
const emits = defineEmits<{
  (e: 'pilihSEP', value: any): void,
  (e: 'cariMon', value: any): void,

}>()
const filterTgl = reactive({
  start: new Date(),
  end: new Date(),
})
const filters: any = ref({
  'global': { value: null, matchMode: FilterMatchMode.CONTAINS },
})
</script>
