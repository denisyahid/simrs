<template>
  <VCard>
    <h3>Resume Medis (Composition)</h3>
    <div class="columns is-multiline mt-2">
      <div class="column is-4">
        <VDatePicker v-model="item.filterDate" is-range color="pink" trim-weeks :max-date="new Date()">
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
      <div class="column is-2 mt-2">
        <VIconButton circle icon="feather:refresh-cw" raised bold @click="fecthData" :loading="isLoading"
          v-tooltip.bubble="'Cari'" class="mt-2-min">
        </VIconButton>
      </div>
      <div class="column is-6">
        <VField class="mt-3-min is-pulled-right text-right" label="Terkirim" >
          <VControl>
            <InputSwitch v-model="item.aktif" />
          </VControl>
        </VField>
      </div>
      <div class="column is-12">
        <DataTable v-model:filters="filters" :value="dataSource" paginator :rows="10" dataKey="id" filterDisplay="row"
          :globalFilterFields="['subjectDisplay']" :class="`p-datatable-small`">
          <template #header>
            <div class="flex justify-content-end">
              <span class="p-input-icon-left">
                <i class="pi pi-search" />
                <InputText v-model="filters['global'].value" placeholder="Search" />
              </span>
            </div>
          </template>
          <template #empty >
            <p style="text-align: center;">
                No data found.
            </p>
          </template>
          <Column header="#"  :style="'width: 50px'">
            <template #body="slotProps">
              <VIconButton type="button" icon="fas fa-eye" class="mr-3" color="info" circle outlined raised v-tooltip-prime="'Detail Data'" @click="detailData(slotProps.data)"
                :loading="slotProps.data.isLoading">
              </VIconButton>
            </template>
          </Column>
          <Column :key="col.no" v-for="col in column" :field="col.field" :header="col.title" :style="'width:' + col.width">
            <template #body="slotProps">
              {{ slotProps.data[col.field] }}
            </template>
          </Column>
        </DataTable>
      </div>
    </div>
  </VCard>
  <Dialog v-model:visible="modalDetailData" header="Detail" :style="{ width: '50vw' }">
    <div class="columns is-multiline">
        <div class="column is-12">
          <Tree :value="dataDetail"  v-model:expandedKeys="expandedKeys"  :filter="true" filterMode="lenient" ></Tree>
        </div>
    </div>
  </Dialog>
</template>
<script setup lang="ts">
import { useRoute, useRouter } from 'vue-router'
import { ref, computed, watch, reactive } from 'vue'
import * as H from '/@src/utils/appHelper'
import { useApi } from '/@src/composable/useApi'
import { useHead } from '@vueuse/head'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import ColumnGroup from 'primevue/columngroup';   // optional
import Row from 'primevue/row';
import InputText from 'primevue/inputtext';
import { FilterMatchMode } from 'primevue/api'
import Tree from 'primevue/tree';
import Dialog from 'primevue/dialog';
import InputSwitch from 'primevue/inputswitch';
useHead({
  title: import.meta.env.VITE_PROJECT,
})
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)
const isLoading: any = ref(false)
const item: any = reactive({
  filterDate: {
    start: new Date(),
    end: new Date()
  },
  aktif : true
})
const dataSource = ref([])
const modalDetailData = ref(false)
const dataDetail: any = ref([])
const filters = ref({
  global: { value: null, matchMode: FilterMatchMode.CONTAINS },

});
const column: any = [
  {
    "field": "no",
    "title": "No",
    "width": "50px",
  },
  {
    "field": "date",
    "title": "Date",
    "width": "100px"
  },
  {
    "field": "status",
    "title": "Status",
    "width": "100px"
  },
  {
    "field": "subjectdisplay",
    "title": "Patient ",
    "width": "150px"
  },
  {
    "field": "encounterdisplay",
    "title": "Encounter",
    "width": "250px"
  },

  {
    "field": "sectioncodecodingcode",
    "title": "Coding Code",
    "width": "140px"
  },
  {
    "field": "sectioncodecodingdisplay",
    "title": "Coding display",
    "width": "140px"
  },
  {
    "field": "sectiontextdiv",
    "title": "Text Description",
    "width": "250px"
  },
  {
    "field": "authordisplay",
    "title": "Author ",
    "width": "150px"
  },
];
const fecthData = async () => {

  let dari = H.formatDate(item.filterDate.start, 'YYYY-MM-DD')
  let sampai = H.formatDate(item.filterDate.end, 'YYYY-MM-DD')

  isLoading.value = true

  const z = await useApi().get(`/bridging/satusehat/get-list?dari=${dari}&sampai=${sampai}&resourcetype=Composition&aktif=${item.aktif}`)

  isLoading.value = false

  for (let x = 0; x < z.length; x++) {
    const element = z[x];
    element.no = x + 1

    element.subjectdisplay = element.subject.display
    element.encounterdisplay = element.encounter.display

    element.sectioncodecodingcode = element.section[0].code.coding[0].code
    element.sectioncodecodingdisplay = element.section[0].code.coding[0].display
    if(element.section[0].code.coding[0].display == 'Hospital course Narrative'){
      element.sectiontextdiv = element.section[0].text.div
    } else{
      element.sectiontextdiv = element.section[1].text.div
    }
    element.authordisplay = element.author[0].display

  }
  dataSource.value = z

}
</script>

