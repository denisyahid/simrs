<template>
  <VCard>
    <h3>Radiologi</h3>
    <div class="columns is-multiline mt-2">
      <div class="column is-12">
        <TabView>
          <TabPanel header="Service Request">
            <div class="columns is-multiline">
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
                <DataTable v-model:filters="filters" :value="dataSource" paginator :rows="10" dataKey="id"
                  filterDisplay="row" :globalFilterFields="['subjectdisplay']" :class="`p-datatable-small`">
                  <template #header>
                    <div class="flex justify-content-end">
                      <span class="p-input-icon-left">
                        <i class="pi pi-search" />
                        <InputText v-model="filters['global'].value" placeholder="Search" />
                      </span>
                    </div>
                  </template>
                  <template #empty style="text-align: center;"> No data found. </template>
                  <Column header="#" style="width:50px">
                      <template #body="slotProps">
                        <VIconButton type="button" icon="fas fa-eye" class="mr-3" :color="slotProps.data.id ? 'info':'danger'" circle outlined raised v-tooltip-prime="'Detail Data'" @click="detailData(slotProps.data,'ServiceRequest')"
                          :loading="slotProps.data.isLoading">
                        </VIconButton>
                      </template>
                  </Column>
                  <Column v-for="col in column" :field="col.field" :header="col.title" :style="'width:' + col.width">
                    <template #body="slotProps">
                      {{ slotProps.data[col.field] }}
                    </template>
                  </Column>
                </DataTable>
              </div>
            </div>
          </TabPanel>

          <TabPanel header="Observation">

            <div class="columns is-multiline">
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
                <VIconButton circle icon="feather:refresh-cw" raised bold @click="fecthData3" :loading="isLoading"
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
                <DataTable v-model:filters="filters" :value="dataSource3" paginator :rows="10" dataKey="id"
                  filterDisplay="row" :globalFilterFields="['subjectdisplay']" :class="`p-datatable-small`">
                  <template #header>
                    <div class="flex justify-content-end">
                      <span class="p-input-icon-left">
                        <i class="pi pi-search" />
                        <InputText v-model="filters['global'].value" placeholder="Search" />
                      </span>
                    </div>
                  </template>
                  <template #empty style="text-align: center;"> No data found. </template>
                  <Column header="#" style="width:50px">
                      <template #body="slotProps">
                        <VIconButton type="button" icon="fas fa-eye" class="mr-3" :color="slotProps.data.id ? 'info':'danger'" circle outlined raised v-tooltip-prime="'Detail Data'" @click="detailData(slotProps.data,'Observation')"
                          :loading="slotProps.data.isLoading">
                        </VIconButton>
                      </template>
                  </Column>
                  <Column v-for="col in column3" :field="col.field" :header="col.title" :style="'width:' + col.width">
                    <template #body="slotProps">
                      {{ slotProps.data[col.field] }}
                    </template>
                  </Column>
                </DataTable>
              </div>
            </div>
          </TabPanel>
          <TabPanel header="Diagnostic Report">
            <div class="columns is-multiline">
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
                <VIconButton circle icon="feather:refresh-cw" raised bold @click="fecthData4" :loading="isLoading"
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
                <DataTable v-model:filters="filters" :value="dataSource4" paginator :rows="10" dataKey="id"
                  filterDisplay="row" :globalFilterFields="['subjectdisplay']" :class="`p-datatable-small`">
                  <template #header>
                    <div class="flex justify-content-end">
                      <span class="p-input-icon-left">
                        <i class="pi pi-search" />
                        <InputText v-model="filters['global'].value" placeholder="Search" />
                      </span>
                    </div>
                  </template>
                  <template #empty style="text-align: center;"> No data found. </template>
                  <Column header="#" style="width:50px">
                      <template #body="slotProps">
                        <VIconButton type="button" icon="fas fa-eye" class="mr-3" :color="slotProps.data.id ? 'info':'danger'" circle outlined raised v-tooltip-prime="'Detail Data'" @click="detailData(slotProps.data,'DiagnosticReport')"
                          :loading="slotProps.data.isLoading">
                        </VIconButton>
                      </template>
                  </Column>
                  <Column v-for="col in column4" :field="col.field" :header="col.title" :style="'width:' + col.width">
                    <template #body="slotProps">
                      {{ slotProps.data[col.field] }}
                    </template>
                  </Column>
                </DataTable>
              </div>
            </div>
          </TabPanel>
        </TabView>
      </div>
    </div>
    <Dialog v-model:visible="modalDetailData" header="Detail" :style="{ width: '50vw' }">
      <div class="columns is-multiline">
          <div class="column is-12">
            <Tree :value="dataDetail"   :filter="true" filterMode="lenient" ></Tree>
          </div>
      </div>
    </Dialog>
  </VCard>
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
import TabView from 'primevue/tabview';
import TabPanel from 'primevue/tabpanel';
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
  aktif: true
})
const dataSource = ref([])
const dataSource2 = ref([])
const dataSource3 = ref([])
const dataSource4 = ref([])
const modalDetailData:any = ref(false)
const dataDetail:any = ref([]);
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
    "field": "authoredOn",
    "title": "Authored On",
    "width": "100px"
  },
  {
    "field": "intent",
    "title": "Intent",
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
    "field": "codecodingcode",
    "title": "Coding Code",
    "width": "140px"
  },
  {
    "field": "codecodingdisplay",
    "title": "Coding display",
    "width": "240px"
  },
  {
    "field": "requesterdisplay",
    "title": "Requester",
    "width": "250px"
  },

];
const column2: any = [
  {
    "field": "no",
    "title": "No",
    "width": "50px",
  },
  {
    "field": "receivedTime",
    "title": "Received Time",
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
    "field": "typecodingcode",
    "title": "Coding Code",
    "width": "140px"
  },
  {
    "field": "typecodingdisplay",
    "title": "Coding display",
    "width": "140px"
  },
];
const column3: any = [
  {
    "field": "no",
    "title": "No",
    "width": "50px",
  },
  {
    "field": "categorycodingdisplay",
    "title": "Category",
    "width": "100px"
  },
  {
    "field": "codecodingcode",
    "title": "Coding Code",
    "width": "140px"
  },
  {
    "field": "codecodingdisplay",
    "title": "Coding display",
    "width": "140px"
  },
  {
    "field": "codecodingsystem",
    "title": "Coding System",
    "width": "140px"
  },
  {
    "field": "value",
    "title": "Value",
    "width": "140px"
  },
  {
    "field": "effectiveDateTime",
    "title": "Effective Date Time",
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


];
const column4: any = [
  {
    "field": "no",
    "title": "No",
    "width": "50px",
  },
  {
    "field": "resourceType",
    "title": "Type",
    "width": "100px"
  },
  {
    "field": "status",
    "title": "Status",
    "width": "100px"
  },
  {
    "field": "codecodingcode",
    "title": "Coding Code",
    "width": "140px"
  },
  {
    "field": "codecodingdisplay",
    "title": "Coding display",
    "width": "140px"
  },
  {
    "field": "subjectdisplay",
    "title": "Patient",
    "width": "200px"
  },
  {
    "field": "issued",
    "title": "Issued",
    "width": "100px"
  },
  {
    "field": "conclusionCode",
    "title": "Conclusion ",
    "width": "150px"
  },
  {
    "field": "basedOn",
    "title": "Based On ",
    "width": "150px"
  },
];
const fecthData = async () => {

  let dari = H.formatDate(item.filterDate.start, 'YYYY-MM-DD')
  let sampai = H.formatDate(item.filterDate.end, 'YYYY-MM-DD')

  isLoading.value = true

  const z = await useApi().get(`/bridging/satusehat/get-list?dari=${dari}&sampai=${sampai}&resourcetype=ServiceRequest&aktif=${item.aktif}`)

  isLoading.value = false
  dataSource.value = []
  for (let x = 0; x < z.length; x++) {
    const element = z[x];
    if(element.category.length > 0 ){
        for (let y = 0; y < element.category.length; y++) {
          const elementX = element.category[y];
          if(elementX.coding[0].display == 'Imaging'){
              element.no = x + 1
              element.subjectdisplay = element.subject.display
              element.encounterdisplay = element.encounter.display
              element.codecodingcode = element.code.coding[0].code
              element.codecodingdisplay = element.code.coding[0].display
              element.requesterdisplay = element.requester.display
              dataSource.value.push(element)
              break
          }
        }
    }else{
      if(element.category && element.category.coding[0].display == 'Imaging'){
            element.no = x + 1
            element.subjectdisplay = element.subject.display
            element.encounterdisplay = element.encounter.display
            element.codecodingcode = element.code.coding[0].code
            element.codecodingdisplay = element.code.coding[0].display
            element.requesterdisplay = element.requester.display
            dataSource.value.push(element)
        }
    }
  }

}
const fecthData2 = async () => {

  let dari = H.formatDate(item.filterDate.start, 'YYYY-MM-DD')
  let sampai = H.formatDate(item.filterDate.end, 'YYYY-MM-DD')

  isLoading.value = true

  const z = await useApi().get(`/bridging/satusehat/get-list?dari=${dari}&sampai=${sampai}&resourcetype=Specimen&aktif=${item.aktif}`)

  isLoading.value = false

  for (let x = 0; x < z.length; x++) {
    const element = z[x];
    element.no = x + 1
    element.subjectdisplay = element.subject.display
    element.typecodingcode = element.type.coding[0].code
    element.typecodingdisplay = element.type.coding[0].display
  }
  dataSource2.value = z

}
const fecthData3 = async () => {

  let dari = H.formatDate(item.filterDate.start, 'YYYY-MM-DD')
  let sampai = H.formatDate(item.filterDate.end, 'YYYY-MM-DD')

  isLoading.value = true

  const z = await useApi().get(`/bridging/satusehat/get-list?dari=${dari}&sampai=${sampai}&resourcetype=Observation&aktif=${item.aktif}`)

  isLoading.value = false

  for (let x = 0; x < z.length; x++) {
    const element = z[x];
    element.no = x + 1
    element.categorycodingdisplay = element.category[0].coding[0].display
    element.codecodingcode = element.code.coding[0].code
    element.codecodingdisplay = element.code.coding[0].display
    element.codecodingsystem = element.code.coding[0].system
    element.subjectdisplay = element.subject.display
    element.encounterdisplay = element.encounter.display
  }
  dataSource3.value = z

}

const fecthData4 = async () => {

  let dari = H.formatDate(item.filterDate.start, 'YYYY-MM-DD')
  let sampai = H.formatDate(item.filterDate.end, 'YYYY-MM-DD')

  isLoading.value = true

  const z = await useApi().get(`/bridging/satusehat/get-list?dari=${dari}&sampai=${sampai}&resourcetype=DiagnosticReport&aktif=${item.aktif}`)

  isLoading.value = false

  for (let x = 0; x < z.length; x++) {
    const element = z[x];
    element.no = x + 1
    element.codecodingcode = element.code.coding[0].code
    element.codecodingdisplay = element.code.coding[0].display
    element.subjectdisplay = element.subject.display
    element.conclusionCode = element.conclusion
    element.basedOn = element.basedOn[0].reference

  }
  dataSource4.value = z

}
const detailData = async (data :any,resourceType :any) => {
    if(!data.id){
      dataDetail.value = H.convertJSONtree(data.response);
      modalDetailData.value = true
      return
    }
    data.isLoading = true;
    let json = {
      "url": `${resourceType}/${data.id}`,
      "method": "GET",
      "data": null,
    }
    const response = await useApi().postSATUSEHAT(`/bridging/satusehat/tools`, json)
    dataDetail.value = H.convertJSONtree(response);
    modalDetailData.value = true
    data.isLoading = false;
}
</script>


