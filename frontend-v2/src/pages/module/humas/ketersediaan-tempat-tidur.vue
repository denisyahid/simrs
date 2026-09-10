
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
                :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="Cari Ruangan..." class="mt-2" />
              </VControl>
            </VField>
            <VButton type="button" color="success" raised icon="feather:search" @click="fetchData()" :loading="isPlaceLoad">
              Cari
            </VButton>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="column">
    <div class="columns is-multiline">
      <div class="column is-4">
        <VField v-slot="{ id }" class="is-icon-select mt-1">
          <VControl>
            <Multiselect v-model="selectView" :attrs="{ id }" placeholder="Select View" label="name"
              :options="d_View" :searchable="true" track-by="name" mode="single"
              @select="changeView(selectView)" autocomplete="off">
              <template #singlelabel="{ value }">
                <div class="multiselect-single-label">
                  <div class="select-label-icon-wrap">
                    <i :class="value.icon"></i>
                  </div>
                  <span class="select-label-text">
                    {{ value.name }}
                  </span>
                </div>
              </template>
              <template #option="{ option }">
                <div class="select-option-icon-wrap">
                  <i :class="option.icon"></i>
                </div>
                <span class="select-option-text">
                  {{ option.name }}
                </span>
              </template>
            </Multiselect>
          </VControl>
        </VField>
      </div>
    </div>
    <VPlaceload height="100rem" width="100%" class="mx-2" v-if="isPlaceLoad" />
    <div class="column" v-else>
      <div class="columns is-multiline" v-if="selectView == 'list'">
          <div class="column">
            <DataTable  :rows="10" :value="dataSource" :rowsPerPageOptions="[5, 10, 15]"
                        class="p-datatable-sm mt-5" breakpoint="960px" selectionMode="single" sortMode="multiple"
                        v-model:expanded-rows="expandedRows" showGridlines tableStyle="min-width: 30rem"
                        :loading="loadSearch"
                        paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
                        paginator currentPageReportTemplate="Showing {first} to {last} of {totalRecords}">
                        <Column field="no" header="NO" />
                        <Column field="namaruangan" header="Nama Kamar" />
                        <Column field="total" header="Jumlah Tempat Tidur" />
                        <Column field="isi" header="Terisi" />
                        <Column field="kosong" header="Tersedia" />
                        <Column field="terpesan" header="Terpesan" />
                        <Column field="rusak" header="Rusak" />
                        <Column :exportable="false" header="Action" style="min-width: 60px">
                            <template #body="slotProps">
                                <VIconButton type="button" icon="fas fa-eye" color="info" circle outlined raised
                                    v-tooltip.top="'Detail'" @click="showDetail(slotProps.data)"
                                    :loading="slotProps.data.loading">
                                </VIconButton>
                            </template>
                        </Column>
            </DataTable>
          </div>
      </div>
      <div class="columns is-multiline" v-if="selectView == 'grid'">
        <div class="column is-4" :key="i" v-for="(data, i) in dataSource">
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
                    <p style="font-weight:500">Jumlah</p>
                    <span style="font-weight:500">{{ data.total }}</span>
                  </div>
                  <div class="column is-3" style="text-align:center">
                    <p style="font-weight:500">Kosong</p>
                    <span style="font-weight:500">{{ data.kosong }}</span>
                  </div>
                  <div class="column is-3" style="text-align:center">
                    <p style="font-weight:500">Terisi</p>
                    <span style="font-weight:500">{{ data.isi }}</span>
                  </div>
                  <div class="column is-3" style="text-align:center">
                    <p style="font-weight:500">Dipesan</p>
                    <span style="font-weight:500">{{ data.terpesan }}</span>
                  </div>
                </div>
                <div class="columns is-multiline">
                  <div class="column is-3" style="text-align:center">
                    <p style="font-weight:500">Rusak</p>
                    <span style="font-weight:500">{{ data.rusak }}</span>
                  </div>
                </div>
              </div>
            </div>
            <div class="columns is-multiline mt-3">
              <div class="column is-3">
              </div>
              <div class="column">
                <VIconButton type="button" v-tooltip.top="'DETAIL'" color="info" class="searcv-button" square outlined raised icon="fas fa-eye"
                @click="showDetail(data)" :loading="data.loading">
              </VIconButton>
            </div>
          </div>
          </VCard>
        </div>
      </div>
    </div>
</div>
<VModal size="big" :open="modalDetail" :title="`Detail BED Ruangan ${item.namaruangan}`" actions="right" @close="modalDetail = false">
  <template #content>
    <DataTable :value="d_DetailRuangan">
      <Column field="no"  style="min-width: 30px" header="No"></Column>
      <Column field="namaruangan" style="min-width: 180px" header="Ruangan"></Column>
      <Column field="namakamar" style="min-width: 100px" header="No Kamar"></Column>
      <Column field="nobed" style="min-width: 80px" header="Nama Bed"></Column>
      <Column field="namakelas" style="min-width: 100px" header="Kelas"></Column>
      <Column field="nocm" style="min-width: 80px" header="No RM"></Column>
      <Column field="namapasien" style="min-width: 120px" header="Nama Pasien"></Column>
      <Column field="umur" style="min-width: 100px" header="Umur"></Column>
      <Column field="jeniskelamin" style="min-width: 100px" header="Jenis Kelamin"></Column>
      <Column field="status" header="Status" :sortable="true" style="min-width: 100px">
        <template #body="slotProps">
          <VTag class="ml-4" :color="slotProps.data.status === 'KOSONG' ? 'success' : (slotProps.data.status === 'RENOVASI' || slotProps.data.status === 'TERPESAN' ? 'warning' : 'danger')" rounded :style="{width: '70px', height: '20px'}">
            {{ slotProps.data.status }}
          </VTag>
        </template>
      </Column>
    </DataTable>
  </template>
</VModal>
</template>
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
const d_View = [
  {
    name: 'Grid View',
    value: 'grid',
    icon: 'fas fa-id-card-alt',
  },
  {
    name: 'List View',
    value: 'list',
    icon: 'fas fa-list',
  },
]
const selectView: any = ref()
selectView.value = 'grid'
const currentPage: any = ref({
  limit: 5,
  rows: 50,
})
const changeView = (e: any) => {
  selectView.value = e
}
let listColor: any = ref(Object.keys(useThemeColors()))
let dataSource: any = ref([])
let dataSourcePulang: any = ref([])
let dataHutang: any = ref([])
let d_Ruangan: any = ref([])
let d_Kamar: any = ref([])
let d_Kelas: any = ref([])
let d_DetailRuangan :any =ref([]);
let d_Dokter: any = ref([])
let isLoading: any = ref(false)
let isPlaceLoad: any = ref(false)



const fetchData = async (e:any) => {
  isPlaceLoad.value =true
  let kdRuangan = e ? `?ruanganfk=${e} ` : ''
  await useApi().get(`humas/info-bed${kdRuangan}`).then((response: any) => {
    response.data.forEach((element:any,index:number)=>{
      element.no =  index + 1
    })
    dataSource.value = response.data
  })
  isPlaceLoad.value = false

}

const fetchRuangan = async (filter: any) => {
  await useApi().get(
  `emr/dropdown/ruangan_m?select=id,namaruangan&param_search=namaruangan&query=${filter.query}&limit=10&settingdatafix=objectdepartemenfk,kdDepartemenRanapFix`
  ).then((response) => {
    d_Ruangan.value = response.map((e: any) => { return { label: e.label, value: e.value, default: e } })
  })
}

const fetchKamar = async (filter: any) => {
  await useApi().get(
  `emr/dropdown/kamar_m?select=id,namakamar&param_search=namakamar&query=${filter.query}&limit=10`
  ).then((response) => {
    d_Kamar.value = response.map((e: any) => { return { label: e.label, value: e.value, default: e } })
  })
}

const fetchKelas = async (filter: any) => {
  await useApi().get(
  `emr/dropdown/kelas_m?select=id,namakelas&param_search=namakelas&query=${filter.query}&limit=10`
  ).then((response) => {
    d_Kelas.value = response.map((e: any) => { return { label: e.label, value: e.value, default: e } })
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

const test2 = () => {
  if (item.value.kamar.value != undefined) {
    fetchData(item.value.kamar.value)
  }
}

const test3 = () => {
  if (item.value.kelas.value != undefined) {
    fetchData(item.value.kelas.value)
  }
}


const showDetail =async  (data :any)=>{
  data.loading = true;
  item.value.namaruangan =data.namaruangan
  await useApi().get(
  `humas/data-detail-tempat-tidur?ruangan=${data.id_ruangan}`
  ).then((response) => {
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
.c-title {
  margin-left: -21px;
  padding-top: 18px;
  margin-top: -21px;
  border-top-left-radius: 11px;
  border-left: solid hsla(19deg, 100%, 75%, .72) 3px;
  padding-bottom: 0;
  margin-bottom: 2rem;
}
.illustration-header-2 {
    display: flex;
    align-items: center;
    padding: 10px;
    border-radius: 16px;
    background: var(--primary-dark-24);
    font-family: var(--font);
    box-shadow: var(--primary-box-shadow);
  .header-image {
   position: relative;
    height: 175px;
    width: 320px;

    img {
      position: absolute;
      top: 0;
      left: -40px;
      display: block;
      pointer-events: none;
    }
  }

  .header-meta {
    margin-left: 0;
    padding-right: 30px;

    h3 {
      color: var(--smoke-white);
      font-family: var(--font-alt);
      font-weight: 700;
      font-size: 1.3rem;
      max-width: 280px;
    }

    p {
      font-weight: 400;
      color: var(--smoke-white-dark-2);
      max-width: 320px;
    }

    .action-link {
      span {
        font-size: 0.8rem;
        text-transform: uppercase;
        margin-right: 6px;
      }

      i {
        font-size: 12px;
      }
    }
  }
}
</style>
