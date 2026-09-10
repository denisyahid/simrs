
<template>
  <div class="column">
    <div class="columns is-multiline">
      <div class="column is-8">
        <div class="illustration-header-2">
          <div class="header-image" style="left:22px;top:12px">
            <img src="/images/avatars/label/kamar.png" alt="" style="width:88%" />
          </div>
          <div class="header-meta">
            <h3>Tempat Tidur</h3>
            <p>Ketersediaan Tempat Tidur</p>
            <VField>
              <VControl class="prime-auto">
                <AutoComplete v-model="item.ruangan" :suggestions="d_Ruangan" @complete="fetchRuangan($event)"
                  @change="test()" :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                  :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="Cari Kamar..." class="mt-2" />
              </VControl>
            </VField>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="column">
    <div class="columns is-multiline">
      <div class="column is-4" v-for="(data, i) in dataSource">
        <VCard>
          <div class="columns is-multiline">
            <div class="column is-3">
              <VIconBox size="medium" :color="listColor[i]" rounded>
                <i class="fas fa-bed"></i>
              </VIconBox>
            </div>
            <div class="column p-0">
              <div class="column">
                <span style="font-weight:500">{{ data.namaruangan }}</span>
              </div>
              <div class="columns is-multiline">
                <div class="column is-3" style="text-align:center">
                  <p style="font-weight:500">Bed</p>
                  <span style="font-weight:500">{{ data.bed }}</span>
                </div>
                <div class="column is-3" style="text-align:center">
                  <p style="font-weight:500">Terisi</p>
                  <span style="font-weight:500">{{ data.isi }}</span>
                </div>
                <div class="column is-3" style="text-align:center">
                  <p style="font-weight:500">Tersedia</p>
                  <span style="font-weight:500">{{ data.kosong }}</span>
                </div>
              </div>
            </div>
          </div>
          <div class="columns is-multiline mt-3">
            <div class="column is-3">
            </div>
            <div class="column">
              <!-- <VButton  :loading="isLoading" type="button" icon="pi pi-eye" class="mr-5" color="info" square outlined raised
                v-tooltip.top="'DETAIL'" @click="showDetail(data)">
              </VButton> -->
              <VIconButton type="button" v-tooltip.top="'DETAIL'" color="info" class="searcv-button" square outlined raised icon="fas fa-eye"
                  @click="showDetail(data)" :loading="data.loading">
              </VIconButton>
            </div>
          </div>
        </VCard>
      </div>
    </div>
  </div>
  
  <VModal size="medium" :open="modalDetail" :title="`Detail BED Ruangan ${item.namaruangan}`" actions="right" @close="modalDetail = false">
      <template #content>
        <DataTable :value="d_DetailRuangan">
            <Column field="no"  style="min-width: 100px" header="No"></Column>
            <Column field="nama" style="min-width: 100px" header="Nama Bed"></Column>
            <Column field="status" header="Status" :sortable="true" style="min-width: 100px">
              <template #body="slotProps">
                <VTag class="ml-4" :color="slotProps.data.status == 'KOSONG' ? 'primary' : 'warning'" rounded>
                  {{ slotProps.data.status }}
                </VTag>
              </template>
            </Column>
        </DataTable>
      </template>
  </VModal>
</template>
<route lang="yaml">
  meta:
    requiresAuth: true
  </route>

<script setup lang="ts">
import { useRoute, useRouter } from 'vue-router'
import { ref, computed, watch, reactive } from 'vue'
import DataTable from 'primevue/datatable';
import Column from 'primevue/column'
import AutoComplete from 'primevue/autocomplete';
import { useThemeColors } from '/@src/composable/useThemeColors'
import * as H from '/@src/utils/appHelper'
import { formatRp } from '/@src/utils/appHelper'
import { followersStats } from '/@src/data/widgets/ui/followers'
import { useApi } from '/@src/composable/useApi'
import { useUserSession } from '/@src/stores/userSession'
import moment from 'moment'
import { useHead } from '@vueuse/head'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import Calendar from 'primevue/calendar';

useHead({
  title: 'Infomasi - Ketersediaan Tempat Tidur  - ' + import.meta.env.VITE_PROJECT,
})
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)

const modalFilter: any = ref(false)
const themeColors = useThemeColors()
const userLogin = useUserSession().getUser()
const total = ref(0)
const router = useRouter()
const bedIsi = ref([])
const modalInput = ref(false)
const modalDetail = ref(false)
const item: any = ref({
  qFilterTgl: {
    start: new Date(),
    end: new Date()
  },
})


const currentPage: any = ref({
  limit: 5,
  rows: 50,
})
let listColor: any = ref(Object.keys(useThemeColors()))
let dataSource: any = ref([])
let dataSourcePulang: any = ref([])
let dataHutang: any = ref([])
let d_Ruangan: any = ref([])
let d_DetailRuangan :any =ref([]);
let d_Dokter: any = ref([])
let isLoading: any = ref(false)
let isPlaceLoad: any = ref(false)



const fetchData = async (e:any) => {

  let kdRuangan = e ? `?ruanganfk=${e} ` : ''
  await useApi().get(`humas/info-bed${kdRuangan}`).then((response: any) => {
    dataSource.value = response.detail
  })
  isPlaceLoad.value = false

}

const fetchRuangan = async (filter: any) => {
  await useApi().get(
    `emr/dropdown/ruangan_m?select=id,namaruangan&param_search=namaruangan&query=${filter.query}&limit=10`
  ).then((response) => {
    d_Ruangan.value = response
  })
}

const fetchDokter = async (filter: any) => {
  await useApi().get(
    `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&settingdatafix=objectjenispegawaifk,idJenisPegawaiDokter&limit=10`
  ).then((response) => {
    d_Dokter.value = response
  })
}

const test = () => {
  if (item.value.ruangan.value != undefined) {
    fetchData(item.value.ruangan.value)
  }
}

const showDetail =async  (data :any)=>{
  data.loading = true;
  item.value.namaruangan =data.namaruangan
  await useApi().get(
    `humas/data-detail-tempat-tidur?ruangan=${data.idruangan}`
  ).then((response) => {
    isLoading.value = false;
    response.data.map((element:any ,i :any) =>{
      element.no = i + 1
    })
    d_DetailRuangan.value = response.data
  })
  data.loading = false;
  modalDetail.value = true
}


fetchData()
</script>


<style lang="scss">
@import '/@src/scss/abstracts/all';

.c-title {
  margin-left: -21px;
  padding-top: 18px;
  margin-top: -21px;
  border-top-left-radius: 11px;
  border-left: solid hsla(19deg, 100%, 75%, .72) 3px;
  padding-bottom: 0;
  margin-bottom: 2rem;
}
</style>
