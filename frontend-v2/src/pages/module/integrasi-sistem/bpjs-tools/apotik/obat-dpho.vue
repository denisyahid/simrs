<template>
 <VCard>
   <div class="columns column">
      <h3 class="title is-5 mb-2 mr-1">Daftar Obat DPHO</h3>
    </div>
    <div class="columns is-multiline">
      <div class="column is-12" v-if="isLoading">
        <VPlaceloadWrap>
          <VPlaceload height="500px" width="100%" class="mx-2" />
        </VPlaceloadWrap>
      </div>
      <div class="column is-12" v-else>
        <DataTable v-model:filters="filters" :value="dataSource" paginator :rows="10" dataKey="no"
          filterDisplay="row" :globalFilterFields="['kodeobat', 'namaobat']"
          :class="`p-datatable-small`" showGridlines style="text-align: center;">
          <Column field="no" header="No" style="text-align: center;"></Column>
          <Column field="kodeobat" header="Kode Obat" style="min-width: 50px"></Column>
          <Column field="namaobat" header="Nama Obat" style="min-width: 150px"></Column>
          <Column field="prb" header="Prb" style="min-width: 20px"></Column>
          <Column field="harga" header="Harga" style="min-width: 40px"></Column>
          <Column field="restriksi" header="Restriksi" style="min-width: 40px"></Column>
          <Column field="generik" header="Generik" style="min-width: 70px"></Column>
        </DataTable>
      </div>
    </div>
 </VCard>
</template>
<script setup lang="ts">
import { ref, computed, watch, reactive } from 'vue'
import { useThemeColors } from '/@src/composable/useThemeColors'
import { onceImageErrored } from '/@src/utils/via-placeholder'
import { useToaster } from '/@src/composable/toaster'
import * as H from '/@src/utils/appHelper'
import { useApi } from '/@src/composable/useApi'
import DataTable from 'primevue/datatable'
import Column from 'primevue/column'

const isLoading :any =ref(false)
const d_Ruangan :any = ref([])
const dataSource:any = ref([]);

const fetchData = async()=>{
isLoading.value = true;
 var json = {
    "url": `/referensi/dpho`,
    "jenis": "apotik",
    "method": "GET",
    "data": null
  }
  const {response} = await useApi().postBPJS(`/bridging/bpjs/tools`, json)
  response.list.map((element:any ,index:number)=>{
    element.no = index+1;
  });
  dataSource.value =response.list
  isLoading.value = false;
}
fetchData();
</script>
