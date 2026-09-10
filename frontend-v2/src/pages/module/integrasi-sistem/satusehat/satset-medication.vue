<template>
  <VCard>
    <h3>Peresepan Obat</h3>
    <div class="columns is-multiline mt-2">
      <div class="column is-12">
        <TabView>
          <TabPanel header="Obat (Medication)">
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
                  <template #empty >
                    <p style="text-align: center;">
                      No data found.
                    </p>
                  </template>
                  <Column header="Detail"  :style="'width: 50px'">
                    <template #body="slotProps">
                      <VIconButton type="button" icon="fas fa-eye" class="mr-3" color="info" circle outlined raised v-tooltip-prime="'Detail Data'" @click="detailData(slotProps.data,'obat')"
                        :loading="slotProps.data.isLoading">
                      </VIconButton>
                    </template>
                  </Column>
                  <Column :key="col.new" v-for="col in column" :field="col.field" :header="col.title" :style="'width:' + col.width">
                    <template #body="slotProps">
                      {{ slotProps.data[col.field] }}
                    </template>
                  </Column>

                </DataTable>
              </div>
            </div>
          </TabPanel>
          <TabPanel header="Permintaan Resep (Medication Request)">
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
                <VIconButton circle icon="feather:refresh-cw" raised bold @click="fecthData2" :loading="isLoading"
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
                <DataTable v-model:filters="filters" :value="dataSource2" paginator :rows="10" dataKey="id"
                  filterDisplay="row" :globalFilterFields="['subjectdisplay']" :class="`p-datatable-small`">
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
                 <Column header="Detail"  :style="'width: 50px'">
                    <template #body="slotProps">
                      <VIconButton type="button" icon="fas fa-eye" class="mr-3" color="info" circle outlined raised v-tooltip-prime="'Detail Data'" @click="detailData(slotProps.data,'resep')"
                        :loading="slotProps.data.isLoading">
                      </VIconButton>
                    </template>
                  </Column>
                  <Column :key="col.no" v-for="col in column2" :field="col.field" :header="col.title" :style="'width:' + col.width">
                    <template #body="slotProps">
                      {{ slotProps.data[col.field] }}
                    </template>
                  </Column>
                </DataTable>
              </div>
            </div>
          </TabPanel>
          <TabPanel header="Pemberian Obat (Medication Dispense)">
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
                  <template #empty >
                    <p style="text-align: center;">
                      No data found.
                    </p>
                  </template>
                  <Column header="Detail"  :style="'width: 50px'">
                    <template #body="slotProps">
                      <VIconButton type="button" icon="fas fa-eye" class="mr-3" color="info" circle outlined raised v-tooltip-prime="'Detail Data'" @click="detailData(slotProps.data,'pemberianObat')"
                        :loading="slotProps.data.isLoading">
                      </VIconButton>
                    </template>
                  </Column>
                  <Column :key="col.no" v-for="col in column3" :field="col.field" :header="col.title" :style="'width:' + col.width">
                    <template #body="slotProps">
                      {{ slotProps.data[col.field] }}
                    </template>
                  </Column>
                </DataTable>
              </div>
            </div>
          </TabPanel>
          <TabPanel header="Medication Dispense Obat Bebas">
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
                  <template #empty >
                    <p style="text-align: center;">
                      No data found.
                    </p>
                  </template>
                  <Column header="#"  :style="'width: 50px'">
                    <template #body="slotProps">
                      <VIconButton type="button" icon="fas fa-eye" class="mr-3" color="info" circle outlined raised v-tooltip-prime="'Detail Data'" @click="detailData(slotProps.data,'obatBebas')"
                        :loading="slotProps.data.isLoading">
                      </VIconButton>
                    </template>
                  </Column>
                  <Column :key="col.no" v-for="col in column4" :field="col.field" :header="col.title" :style="'width:' + col.width">
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
import TabView from 'primevue/tabview';
import TabPanel from 'primevue/tabpanel';
import { FilterMatchMode } from 'primevue/api';
import Dialog from 'primevue/dialog';
import Tree from 'primevue/tree';
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
const dataSource:any = ref([])
const dataSource2:any = ref([])
const dataSource3:any = ref([])
const dataSource4:any = ref([])
const modalDetailData: any = ref(false)
const dataDetail: any = ref([])
const expandedKeys:any = ref([])
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
    "field": "id",
    "title": "ID",
    "width": "100px"
  },
  {
    "field": "codingDisplay",
    "title": "Display",
    "width": "200px"
  },
  {
    "field": "kfacode",
    "title": "Kf+a Code",
    "width": "200px"
  },
  {
    "field": "formdisplay",
    "title": "Medication Form",
    "width": "150px"
  },
  {
    "field": "ingredient_text",
    "title": "Ingredients",
    "width": "100px"
  },

  {
    "field": "batchexpirationDate",
    "title": "Expire Date",
    "width": "80px"
  },
];
const column2: any = [
  {
    "field": "no",
    "title": "No",
    "width": "50px",
  },
  {
    "field": "id",
    "title": "ID",
    "width": "100px"
  },
  {
    "field": "identifiervalue",
    "title": "Identifier",
    "width": "100px"
  },
  {
    "field": "status",
    "title": "Status",
    "width": "100px"
  },
  {
    "field": "statusReasoncodingdisplay",
    "title": "Status Reason",
    "width": "150px"
  },
  {
    "field": "medicationReferencedisplay",
    "title": "Medication Reference",
    "width": "150px"
  },
  {
    "field": "dosageInstruction",
    "title": "Dosage Instruction",
    "width": "150px"
  },
  {
    "field": "dispenseRequestquantityvalue",
    "title": "Qty Dispense",
    "width": "100px"
  },
  {
    "field": "dispenseRequestquantityunit",
    "title": "Unit Dispense",
    "width": "100px"
  },

  {
    "field": "subjectdisplay",
    "title": "Patient",
    "width": "150px"
  },
  {
    "field": "requesterdisplay",
    "title": "Requester",
    "width": "150px"
  },
  {
    "field": "encounterreference",
    "title": "Encounter",
    "width": "150px"
  },
];
const column3: any = [
  {
    "field": "no",
    "title": "No",
    "width": "50px",
  },
  {
    "field": "id",
    "title": "ID",
    "width": "100px"
  },
  {
    "field": "identifiervalue",
    "title": "Identifier",
    "width": "100px"
  },
  {
    "field": "status",
    "title": "Status",
    "width": "100px"
  },
  {
    "field": "medicationReferencedisplay",
    "title": "Medication Reference",
    "width": "150px"
  },
  {
    "field": "dosageInstruction",
    "title": "Dosage Instruction",
    "width": "150px"
  },
  {
    "field": "quantityvalue",
    "title": "Qty ",
    "width": "100px"
  },
  {
    "field": "quantitycode",
    "title": "Unit ",
    "width": "100px"
  },
  {
    "field": "whenPrepared",
    "title": "Prepared ",
    "width": "100px"
  },
  {
    "field": "whenHandedOver",
    "title": "Handed Over ",
    "width": "100px"
  },
  {
    "field": "subjectdisplay",
    "title": "Patient",
    "width": "150px"
  },
  {
    "field": "performeractor",
    "title": "Performer",
    "width": "150px"
  },
  {
    "field": "authorizingPrescription",
    "title": "Authorizing Prescription",
    "width": "150px"
  },
  {
    "field": "contextreference",
    "title": "Encounter",
    "width": "150px"
  },

];
const column4: any = [
  {
    "field": "no",
    "title": "No",
    "width": "50px",
  },
  {
    "field": "fullUrl",
    "title": "fullUrl",
    "width": "100px"
  },
  {
    "field": "identifiervalue",
    "title": "Identifier",
    "width": "140px"
  },
  {
    "field": "status",
    "title": "Status",
    "width": "100px"
  },
  {
    "field": "medicationReference",
    "title": "Medication ",
    "width": "150px"
  },
  {
    "field": "dosageInstruction",
    "title": "Dosage Instruction",
    "width": "150px"
  },
  {
    "field": "quantityvalue",
    "title": "Qty ",
    "width": "100px"
  },
  {
    "field": "quantitycode",
    "title": "Unit ",
    "width": "100px"
  },
  {
    "field": "whenPrepared",
    "title": "Prepared ",
    "width": "100px"
  },
  {
    "field": "whenHandedOver",
    "title": "Handed Over ",
    "width": "100px"
  },
  {
    "field": "subject",
    "title": "Subject",
    "width": "150px"
  },
  {
    "field": "subjectreference",
    "title": "Subject Reff",
    "width": "150px"
  },
  {
    "field": "performer",
    "title": "Performer",
    "width": "150px"
  },
];
const fecthData = async () => {

  let dari = H.formatDate(item.filterDate.start, 'YYYY-MM-DD')
  let sampai = H.formatDate(item.filterDate.end, 'YYYY-MM-DD')

  isLoading.value = true

  const z = await useApi().get(`/bridging/satusehat/get-list?aktif=${item.aktif}&dari=${dari}&sampai=${sampai}&resourcetype=Medication`)

  isLoading.value = false

  for (let x = 0; x < z.length; x++) {
    const element = z[x];
    element.no = x + 1
    element.ingredient_text = ''
    for (let u = 0; u < element.ingredient.length; u++) {
      const element2 = element.ingredient[u];
      element.ingredient_text = element.ingredient_text + ',' + (element2.itemCodeableConcept.coding[0].display + ' ' + element2.strength.numerator.value
        + ' ' + element2.strength.numerator.code)
    }
    element.ingredient_text = element.ingredient_text.substring(1)
    element.codingDisplay = element.code.coding[0].display
    element.kfacode = element.code.coding[0].code
    element.formdisplay = element.form.coding[0].display
    element.codecodingsystem = element?.batch?.expirationDate

  }
  dataSource.value = z

}
const fecthData2 = async () => {

  let dari = H.formatDate(item.filterDate.start, 'YYYY-MM-DD')
  let sampai = H.formatDate(item.filterDate.end, 'YYYY-MM-DD')

  isLoading.value = true

  const z = await useApi().get(`/bridging/satusehat/get-list?aktif=${item.aktif}&dari=${dari}&sampai=${sampai}&resourcetype=MedicationRequest`)

  isLoading.value = false

  for (let x = 0; x < z.length; x++) {
    const element = z[x];
    element.no = x + 1
    element.whenPrepared = H.formatDate(new Date(element.whenPrepared), "YYYY-MM-DD HH:mm")
    element.whenHandedOver = H.formatDate(new Date(element.whenHandedOver), "YYYY-MM-DD HH:mm")
    element.identifiervalue = element.identifier[1].value
    element.statusReasoncodingdisplay = element.statusReason.coding[0].display
    element.medicationReferencedisplay = element.medicationReference.display
    element.dosageInstruction = element.dosageInstruction[0].text
    element.dispenseRequestquantityvalue = element.dispenseRequest.quantity.value
    element.dispenseRequestquantityunit = element.dispenseRequest.quantity.unit
    element.subjectdisplay = element.subject.display
    element.requesterdisplay = element.requester.display
    element.encounterreference = element.encounter.reference
  }
  dataSource2.value = z

}
const fecthData3 = async () => {

  let dari = H.formatDate(item.filterDate.start, 'YYYY-MM-DD')
  let sampai = H.formatDate(item.filterDate.end, 'YYYY-MM-DD')

  isLoading.value = true

  const z = await useApi().get(`/bridging/satusehat/get-list?aktif=${item.aktif}&dari=${dari}&sampai=${sampai}&resourcetype=MedicationDispense`)

  isLoading.value = false

  for (let x = 0; x < z.length; x++) {
    const element = z[x];
    element.no = x + 1
    element.whenPrepared = H.formatDate(new Date(element.whenPrepared), "YYYY-MM-DD HH:mm")
    element.whenHandedOver = H.formatDate(new Date(element.whenHandedOver), "YYYY-MM-DD HH:mm")


    element.contextreference = element.context.reference
    element.authorizingPrescription  ?  element.authorizingPrescription[0].reference : ''

    element.performeractor = element.performer[0].actor.display
    element.subjectdisplay = element.subject.display
    element.quantitycode = element.quantity.code
    element.quantityvalue = element.quantity.value
    element.dosageInstruction = element.dosageInstruction[0].text
    element.medicationReference = element.medicationReference.display
    element.identifiervalue = element.identifier[1].value
  }
  dataSource3.value = z

}

const fecthData4 = async () => {

  let dari = H.formatDate(item.filterDate.start, 'YYYY-MM-DD')
  let sampai = H.formatDate(item.filterDate.end, 'YYYY-MM-DD')

  isLoading.value = true

  const z = await useApi().get(`/bridging/satusehat/get-list?aktif=${item.aktif}&dari=${dari}&sampai=${sampai}&resourcetype=Bundle`)

  isLoading.value = false
  let dataxx = []
  for (let x = 0; x < z.length; x++) {
    const element = z[x];
    element.no = x + 1
    element.value = element.valueQuantity.value + ' ' + element.valueQuantity.unit

    for (let x = 0; x < z.data.length; x++) {
      const element = z.data[x];
      element.no = x + 1
      element.value = element.valueQuantity.value + ' ' + element.valueQuantity.unit
      element.identifiervalue = element.identifier[1].value
      element.medicationReference = element.medicationReference.display
      element.dosageInstruction = element.dosageInstruction[0].text
      element.quantityvalue = element.quantity.value
      element.quantitycode = element.quantity.code
      element.subject = element.subject.display
      element.performer = element.performer[0].actor.display
      element.subjectreference = element.subject.reference

      if (element.category[0].coding[0].code != 'laboratory') {
        dataxx.push(element)
      }
    }
  }
  dataSource4.value = dataxx

}
const detailData = async (data :any,key :any) => {
    if (!data.id) {
      dataDetail.value = H.convertJSONtree(data.response);
      modalDetailData.value = true
      expandAll();
      return;
    }
    data.isLoading = true;
    let url = '';
    switch (key) {
      case 'obat':
        url = 'Medication'
        break;
      case 'resep':
        url = 'MedicationRequest'
        break;
      case 'pemberianObat':
        url = 'MedicationDispense'
        break;
      default:
        url = 'Bundle'
        break;
    }
    let json = {
      "url": `${url}/${data.id}`,
      "method": "GET",
      "data": null,
    }
    const response = await useApi().postSATUSEHAT(`/bridging/satusehat/tools`, json)
    dataDetail.value = H.convertJSONtree(response);
    modalDetailData.value = true
    data.isLoading = false;
    expandAll()
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
</script>

