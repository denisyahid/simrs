<template>
  <VCard>
    <h3>Kunjungan (Encounter)</h3>
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
        <VIconButton circle icon="fas fa-paper-plane" raised bold @click="sync" :loading="isSync" color="danger"
          v-tooltip.bubble="'Kirim Data Batching'" class="mt-2-min ml-3">
        </VIconButton>
      </div>
      <div class="column" style="float: right;">
        <VField class="mt-5-min" label="Terkirim" style="float: right;">
          <VControl>
            <InputSwitch v-model="item.aktif"  @change="fecthData"/>
          </VControl>
        </VField>
      </div>
      <div class="column is-2 mt-3" v-if="valueProgress > 0">
        <ProgressBar :value="valueProgress" style="height: 15px" />
      </div>

      <div class="column is-12">
        <DataTable v-model:filters="filters" :value="dataSource" paginator :rows="10" dataKey="id" filterDisplay="row"
          :rowsPerPageOptions="[5, 10, 25, 100]" :globalFilterFields="['subjectDisplay']" :class="`p-datatable-small`"
          >
          <template #header>
            <div class="flex justify-content-end">
              <span class="p-input-icon-left">
                <!-- <i class="pi pi-search" /> -->
                <InputText v-model="filters['global'].value" placeholder="Search" />
              </span>
            </div>
          </template>
         <template #empty >
            <p style="text-align: center;">
               No data found.
            </p>
          </template>
          <Column header="#">
            <template #body="slotProps">
              <VIconButton type="button" icon="fas fa-eye" class="mr-3" color="info" circle outlined raised v-tooltip-prime="'Detail Data'" @click="detailData(slotProps.data)"
                :loading="slotProps.data.isLoading">
              </VIconButton>
            </template>
          </Column>

          <Column :key="col.no"  v-for="col in selectedColumns" :field="col.field" :header="col.header" :style="'width:' + col.width"  ></Column>
          <ColumnGroup type="footer">
            <Row>
              <Column :footer="'Terdapat ' + dataSource.length + ' data.'" :colspan="column.length + 1" />
            </Row>
          </ColumnGroup>
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
import ProgressBar from 'primevue/progressbar';
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
const isSync: any = ref(false)
const valueProgress: any = ref(0)

const column: any = [
  { "field": 'no', "header": 'No', "width": "50px" },
  {
    "field": "id",
    "header": "ID",
    "width": "250px"
  },
  {
    "field": "periodStart",
    "header": "Period Start",
    "width": "100px"
  },
  {
    "field": "periodEnd",
    "header": "Period End",
    "width": "100px"
  },
  {
    "field": "subjectDisplay",
    "header": "Subject Display",
    "width": "200px"
  },
  {
    "field": "subjectReference",
    "header": "Subject Reff",
    "width": "150px"
  },
  {
    "field": "locationLocationDisplay",
    "header": "Location",
    "width": "150px"
  },
  {
    "field": "practice",
    "header": "Practitioner ",
    "width": "250px"
  },
  {
    "field": "classDisplay",
    "header": "Class",
    "width": "80px"
  },
  {
    "field": "status",
    "header": "Status",
    "width": "100px"
  },

];
const selectedColumns : any =ref(column)
const item: any = reactive({
  filterDate: {
    start: new Date(),
    end: new Date()
  },
  aktif: true
})
selectedColumns.value =  column.filter((item :any) => item.header !== "Response Error");
const modalDetailData:any =ref(false)
const dataDetail:any = ref([]);
const dataSource = ref([])
const expandedKeys:any = ref({});
const rowClass = (data:any) => {
    return [{ 'is-danger': data.id == undefined }];
};
const rowStyle = (data:any) => {
    if (data.quantity === 0) {
        return { fontWeight: 'bold', fontStyle: 'italic' };
    }
};
const filters = ref({
  global: { value: null, matchMode: FilterMatchMode.CONTAINS },

});

const fecthData = async () => {

  let dari = H.formatDate(item.filterDate.start, 'YYYY-MM-DD')
  let sampai = H.formatDate(item.filterDate.end, 'YYYY-MM-DD')

  isLoading.value = true

  const z = await useApi().get(`/bridging/satusehat/get-list?dari=${dari}&sampai=${sampai}&resourcetype=Encounter&aktif=${item.aktif}`)

  isLoading.value = false
  for (let x = 0; x < z.length; x++) {
    const element = z[x];
    element.no = x + 1
    element.practice = ''

    if (element.participant != undefined && element.participant[0].individual.reference) {
      element.practice = element.participant[0].individual.display
    }
    if (element.period.start) {
      element.period.start = H.formatDate(new Date(element.period.start), 'YYYY-MM-DD HH:mm:ss')
    }
    if (element.period.end) {
      element.period.end = H.formatDate(new Date(element.period.end), 'YYYY-MM-DD HH:mm:ss')
    } else {
      element.period.end = ''
    }

    element.periodStart = element.period.start
    element.periodEnd = element.period.end
    element.subjectDisplay = element.subject.display
    element.subjectReference = element.subject.reference
    element.locationLocationDisplay = element.location[0].location.display
    element.classDisplay = element.class.display
  }
  dataSource.value = z
}
const detailData = async (data :any) => {

  if (!data.id) {
    dataDetail.value = H.convertJSONtree(data.response);
    modalDetailData.value = true
    return;
  }
    data.isLoading = true;
    let json = {
      "url": `Encounter/${data.id}`,
      "method": "GET",
      "data": null,
    }
    const response = await useApi().postSATUSEHAT(`/bridging/satusehat/tools`, json)
    dataDetail.value = H.convertJSONtree(response);
    modalDetailData.value = true
    data.isLoading = false;
    expandAll();
}
const sync = async () => {
  isSync.value = true
  let dari = H.formatDate(item.filterDate.start, 'YYYY-MM-DD')
  let sampai = H.formatDate(item.filterDate.end, 'YYYY-MM-DD')

  const z = await useApi().get(`/bridging/satusehat/get-for-encounter?dari=${dari}&sampai=${sampai}`)
  valueProgress.value = 0;
  let n = 0
  for (let index = 0; index < z.length; index++) {
    const element = z[index];
    let json = {
      "noregistrasi": element.noregistrasi
    }
    n = (index + 1) * 100 / z.length
    await useApi().postSATUSEHAT(`/bridging/satusehat/Encounter`, json)
    valueProgress.value = n.toFixed(2);
  }
  isSync.value = false
  fecthData()

}
const expandAll = () => {
    for (let node of dataDetail.value) {
        expandNode(node);
    }

    expandedKeys.value = { ...expandedKeys.value };
};
const expandNode = (node :any) => {
    if (node.children && node.children.length) {
        expandedKeys.value[node.key] = true;

        for (let child of node.children) {
            expandNode(child);
        }
    }
};

watch(
    () => item.aktif,
    (newValue, oldValue) => {
      if (newValue == false) {
        selectedColumns.value =  column
      } else {
        selectedColumns.value =  column.filter((item :any) => item.header !== "Response Error");
      }
    }
)
</script>

