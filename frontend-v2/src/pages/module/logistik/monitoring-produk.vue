<template>
  <section>
    <div class="column is-12">
      <VCard>
          <div class="column c-title pt-2 mb-3">
            <div class="column is-10 p-0">
              <label class="title-page">Monitoring Barang</label>
            </div>
          </div>
          <TabView class="tabview-custom " @tab-click="klikTab($event)">
              <TabPanel>
                  <template #header >
                    <i class="fas fa-smile mr-2" aria-hidden="true"></i>
                    <span>Fast Moving</span>
                  </template>    
                  <FastMoving v-if="activeTab == 0" />          
              </TabPanel>
              <TabPanel>
                  <template #header>
                    <i class="fas fa-frown-open mr-2" aria-hidden="true"></i>
                    <span>Slow Moving</span>
                  </template>
                  <SlowMoving v-if="activeTab == 1"/>                
              </TabPanel>
              <TabPanel>
                  <template #header>
                    <i class="fas fa-sad-tear mr-2" aria-hidden="true"></i>
                    <span>Dead Moving</span>
                  </template> 
                  <DeadMoving v-if="activeTab == 2"/>               
              </TabPanel>
          </TabView>  
      </VCard>
    </div>
  </section>
  
</template>

<script  setup lang="ts">
import { useWindowScroll } from '@vueuse/core'
import { useApi } from '/@src/composable/useApi'
import { ref, computed, watch, reactive } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import DataTable from 'primevue/datatable'
import Column from 'primevue/column'
import Toolbar from 'primevue/toolbar'
import Dropdown from 'primevue/dropdown'
import TabView from 'primevue/tabview';
import TabPanel from 'primevue/tabpanel';
import Button from 'primevue/button'
import OverlayPanel from 'primevue/overlaypanel';
import AutoComplete from 'primevue/autocomplete'
import { useCurrencyInput } from 'vue-currency-input'
import { useHead } from '@vueuse/head'
import Dialog from 'primevue/dialog';
import { useToaster } from '/@src/composable/toaster'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import FastMoving from './fast-moving.vue';
import SlowMoving from './slow-moving.vue';
import DeadMoving from './dead-moving.vue';
import moment from 'moment'
import * as H from '/@src/utils/appHelper'
useHead({
  title: 'Transmedic - Monitoring Barang',
})
useViewWrapper().setFullWidth(true)
let dataSource: any = ref([])
let isLoading: any = ref(false)
let loadData: any = ref(false)
let loadSave: any = ref(false)
let modalInputED: any = ref(false)
let modalInputAj: any = ref(false)
let modalPassword: any = ref(false)
let item: any = reactive({})
let listRuanganStok: any = ref([])
let listAsalProduk: any = ref([])
const currentPage: any = ref({
  limit: 5,
  rows: 50,
})

const op = ref();
const activeTab = ref(0);
const selected: any = ref({})
const route = useRoute()
isLoading.value = false


const klikTab = (e: any) => {
    activeTab.value = e.index
}

</script>

<style lang="scss">
@import '/@src/scss/abstracts/all';

@import '/@src/scss/components/forms-outer';
@import '/@src/scss/module/sysadmin/master-data.scss';

.title-page {
    position: relative;
    font-size: 17px;
    display: block;
    margin-bottom: 3px;
    margin-top: 0px;
    font-weight: 600;
}

.btn-search {
    display: flex;
    align-items: center;
}

</style>
