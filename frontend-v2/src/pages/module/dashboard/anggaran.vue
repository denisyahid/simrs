<template>
<div class="business-dashboard hr-dashboard">
    <div class="columns is-multiline">
      <div class="column is-8">
        <div class="columns is-multiline">
          <div class="column is-12">
            <div class="illustration-header-2 large-screen">
              <div class="header-image">
                <img src="/@src/assets/illustrations/dashboards/lifestyle/anggaran.png" alt=""
                  style="max-width:84%; margin-left: 2rem; margin-bottom: 1rem;" />
              </div>
              <div class="header-meta">
                <h3 style="color:white"><i class="fas fa-id-card" aria-hidden="true"></i> Dashboard
                  Anggaran</h3>
                <p>
                  Selamat Datang , {{ userLogin.pegawai.namaLengkap }}
                </p>
                <!-- <VControl>
                  <Multiselect mode="single" v-model="item.filterRuangan" :options="d_Ruangan" class="f-text"
                    placeholder="Filter ruangan" :searchable="true" autocomplete="off"
                    @select="changeRuang(item.filterRuangan)" />
                </VControl> -->
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="column is-4">
        <VCard>
          <div class="columns is-multiline">
            <div class="column is-12">
              <h3 class="title is-5 mb-2">Rekap Anggaran
                <VTag @click="modalFilter = true" color="danger" rounded elevated class="is-pulled-right is-clickable">
                <i class="fas fa-filter ml-3" aria-hidden="true"></i></VTag>
              </h3>
            </div>
          </div>
          <div class="columns is-multiline">
            <div class="column is-6">
              <CardCountRev icon="/images/simrs/icon-reservasi.png" straight 
                label="Coming Soon" />
            </div>
            <div class="column is-6">
              <CardCountRev icon="/images/simrs/icon-registrasi.png" straight 
                label="Coming Soon" />
            </div>
            <div class="column is-6">
              <CardCountRev icon="/images/simrs/icon-antrian.png" straight
                label="Coming Soon" />
            </div>
            <div class="column is-6">
              <CardCountRev icon="/images/simrs/icon-dilayani.png" straight 
                label="Coming Soon" />
            </div>
          </div>
        </VCard>
      </div>
    </div>
    <div class="column is-12">
      <VCard>
        <div class="tabs-wrapper" :class="['tab-naver']">
            <div class="tabs-inner">
                <div class="tabs is-tabs">
                    <ul style="background-color: #EBEDF5; " class="is-rounded">
                        <li v-for="(tab, key) in tabs" :key="key"
                            :class="[activeValue === tab.value && 'is-active']">
                            <slot name="tab-link" :active-value="activeValue" :tab="tab"
                                :index="key" :toggle="toggle">
                                <a tabindex="0" @keydown.space.prevent="toggle(tab.value)"
                                    @click="toggle(tab.value)">
                                    <!-- <VIcon v-if="tab.icon" :icon="tab.icon" /> -->
                                    <span>
                                        <slot name="tab-link-label" :active-value="activeValue"
                                            :tab="tab" :index="key">
                                            {{ tab.label }}
                                        </slot>
                                    </span>
                                </a>
                            </slot>
                        </li>
                        <li v-if="sliderClass" class="tab-naver"></li>
                    </ul>
                </div>
            </div>

            <div class="tab-content is-active">
                <Transition :name="'fade-fast'" mode="out-in">
                    <slot name="tab" :active-value="activeValue"></slot>
                </Transition>
            </div>
            <div class="column is-12 mt-0 " v-if="activeValue == 1">
              
              <div class="column is-12"> 
                <h3><b>Anggaran</b></h3>
                <p> Menampilkan dashboard informasi kegiatan anggaran <br> yang dapat dikelola dan diakses secara detail.</p>
                <span><br></span>
                <!-- <VButton type="button" icon="feather:plus" :loading="isSimpan" color="primary" raised class="is-rounded"
                    @click="SimpanPopUpDetailSPJ()" > Tambah
                </VButton> -->
              </div>
            </div>
            <div class="column is-12 mt-0 " v-if="activeValue == 2">
              <div class="column is-12"> 
                <h3><b>Coming Soon</b></h3>
                <p> Dashboard SPJ</p>
                <span><br></span>
                <!-- <VButton type="button" icon="feather:plus" :loading="isSimpan" color="primary" raised class="is-rounded"
                    @click="SimpanPopUpDetailSPJ()" > Tambah
                </VButton> -->
              </div>
            </div>
            <div class="column is-12 mt-0 " v-if="activeValue == 3">
              <div class="column is-12"> 
                <h3><b>Coming Soon</b></h3>
                <p> Dashboard Panjar</p>
                <span><br></span>
                <!-- <VButton type="button" icon="feather:plus" :loading="isSimpan" color="primary" raised class="is-rounded"
                    @click="SimpanPopUpDetailSPJ()" > Tambah
                </VButton> -->
              </div>
            </div>
            <div class="column is-12 mt-0 " v-if="activeValue == 4">
              <div class="column is-12"> 
                <h3><b>Coming Soon</b></h3>
                <p> Laporan </p>
                <span><br></span>
                <!-- <VButton type="button" icon="feather:plus" :loading="isSimpan" color="primary" raised class="is-rounded"
                    @click="SimpanPopUpDetailSPJ()" > Tambah
                </VButton> -->
              </div>
            </div>
        </div>
      </VCard>
    </div>
    <div class="column is-12 mt-0 " v-if="activeValue == 1">
      <div class="columns is-multiline">
        <div class="column is-4">
        <VCard>
        <div class="tabs-wrapper" :class="['tab-naver']">
          <div class="form-layout">
            <div class="form-outer">
              <div class="column">
                <VField>
                  <VLabel>Organisasi</VLabel>
                  <VControl icon="feather:home">
                    <VInput type="text" v-model="item.organisasi" placeholder="Organisasi" class="is-rounded_Z" disabled />
                  </VControl>
                </VField>
              </div>
              <div class="column is-12">
                <span>Divide</span>
                <div class="column is-12">
                    <VRadio v-model="item.div" value="-" label="Semua Kegiatan" name="outlined_radio" color="primary" />
                  </div>
                  <div class="column is-12">
                    <VRadio v-model="item.div" value="0" label="Div 1 Urusan Pemerintah" name="outlined_radio" color="primary" />
                  </div>
                  <div class="column is-12">
                    <VRadio v-model="item.div" value="1" label="Div 2 Program" name="outlined_radio" color="primary"/>
                  </div>
                  <div class="column is-12">
                    <VRadio v-model="item.div" value="2" label="Div 3 Kegiatan" name="outlined_radio" color="primary"/>
                  </div>
                  <div class="column is-12">
                    <VRadio v-model="item.div" value="3" label="Div 4 Sub Kegiatan" name="outlined_radio" color="primary" />
                  </div>
                  <div class="column is-12">
                    <VRadio v-model="item.div" value="4" label="Div 5 Sub Sub Kegiatan" name="outlined_radio" color="primary" />
                  </div>
              </div>
            </div>
          </div>
        </div>
        
        </Vcard>
        </div>
        <div class="column is-8">
          <div class="list-view list-view-v3">
            <div class="search-menu mb-2">
              <div class="search-location" style="width: 100%" >
                <VControl icon="feather:check-circle" fullwidth class="prime-auto-select">
                <Dropdown v-model="item.tahap" :options="d_Tahap"
                      :optionLabel="'tahap'" class="is-rounded_Z" placeholder="Tahap"
                      style="width: 100%;" showClear :filter="false" />
                      </VControl>
              </div>
              <div class="search-location" style="width: 100%" >
                <VControl icon="feather:calendar" fullwidth class="prime-auto-select">
                    <Dropdown v-model="item.tahun" :options="d_Tahun" :optionLabel="'tahun'"
                        class="is-rounded_Z" placeholder="Tahun" style="width: 100%;" :filter="true"
                        showClear :loading="isLoadingCombo"/>
                </VControl>
              </div>
              
              <div class="search-location" style="width: 100%" >
                <i class="iconify" data-icon="feather:search"></i>
                <input type="text" placeholder="Pencarian"
                  v-model="item.search"  />
              </div>
              <VButton raised class="search-button" @click="loadData()" :loading="isLoading"> Cari Data
              </VButton>
            </div>
            <VPlaceholderPage :class="[dataAnggaran.length !== 0 && 'is-hidden']"
                title="Tidak Ada Pasien Hari Ini."
                subtitle="Silakan Pilih Tanggal dan Ruangan untuk melihat Data Pasien" larger>
                <template #image>
                  <img class="light-image" src="/@src/assets/illustrations/placeholders/search-4.png" alt="" />
                  <img class="dark-image" src="/@src/assets/illustrations/placeholders/search-4-dark.svg"
                    alt="" />
                </template>
              </VPlaceholderPage>
              <div class="list-view-inner mt-2" style="max-height:1000px;overflow: auto;">
                <div name="list-complete" tag="div">
                  <div v-for="(item, rowIndex) in dataAnggaran" :key="rowIndex">
                    <div class="list-view-item ">
                      <div class="list-view-item-inner">
                        <VAvatar size="small" picture="/images/avatars/svg/div.svg" color="primary" bordered />
                        <div class="meta-left">
                          <h3>{{item.kode}}</h3>
                          <h3 style="font-weight:bold">{{item.keterangan}}</h3>
                          <span>
                          <b style="color: var(--dark-text)"><i class="lnir lnir-user-alt-1" aria-hidden="true"></i> {{item.namalengkap ? item.namalengkap: '-'}} </b> &nbsp; <span style="height: 10px;width: 10px;background-color: white;border-radius: 100%;display: inline-block;border: 2px solid #41B983;"> </span> &nbsp;
                          <b style="color: var(--dark-text)"> <i class="fas fa-money-bill-alt" aria-hidden="true"></i> &nbsp;{{H.formatRupiah(item.total,'Rp. ')}} </b><br>
                          <VIcon icon="lucide:check-circle" />{{item.tahap}} &nbsp; <span style="height: 10px;width: 10px;background-color: white;border-radius: 100%;display: inline-block;border: 2px solid #41B983;"> </span> &nbsp; <VIcon icon="lucide:calendar" /> {{item.tahun}}
                          &nbsp; <span style="height: 10px;width: 10px;background-color: white;border-radius: 100%;display: inline-block;border: 2px solid #41B983;"> </span> &nbsp;   <i class="lnil lnil-archive" aria-hidden="true"></i> &nbsp; {{item.divide}} 
                          </span>
                        </div>
                        <div class="meta-right">
                          <VButton color="info" @click="savePasienPulang()" :loading="btnLoadSimpan" outlined> Detail</VButton>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                          <VDropdown icon="feather:more-vertical" spaced right v-tooltip.bubble="'AKSI'">
                            <template #content>
                              <a role="menuitem" href="#" class="dropdown-item is-media" @click="editPanjar(items)" style="color: red">
                                <div class="icon">
                                  <i class="iconify" data-icon="feather:trash-2" aria-hidden="true"></i>
                                </div>
                                <div class="meta">
                                  <span>Coming Soon</span>
                                  <span>Tunggu Update Terbaru</span>
                                </div>
                              </a>
                            </template>
                          </VDropdown>
                        </div>
                      </div>
                  </div>
                </div>
                <VFlexPagination v-model:current-page="currentPage.page" :item-per-page="currentPage.limit"
                  :total-items="dataAnggaranTotal" :max-links-displayed="5">
                  <template #before-pagination>
                  </template>
                  <template #before-navigation>
                    <VFlex class="mr-4 mt-1" column-gap="1rem">
                      <VField>
                      </VField>
                      <VField>
                        <VControl>
                          <div class="select is-rounded">
                            <select v-model="currentPage.limit">
                              <option :value="3">3 results per page</option>
                              <option :value="5">5 results per page</option>
                              <option :value="6">6 results per page</option>
                              <option :value="10">10 results per page</option>
                              <option :value="15">15 results per page</option>
                              <option :value="25">25 results per page</option>
                              <option :value="50">50 results per page</option>
                              <option :value="100">100 results per page</option>
                              <option :value="200">200 results per page</option>
                              <option :value="500">500 results per page</option>
                              <option :value="1000">1000 results per page</option>
                              <option :value="10000">All</option>
                            </select>
                          </div>
                        </VControl>
                      </VField>
                    </VFlex>
                  </template>
                </VFlexPagination>
              </div>

            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="column is-12 mt-0 " v-if="activeValue == 2">
    
    </div>
  </div>
</template>

<script setup lang="ts">
import { useRoute, useRouter } from 'vue-router'
import { ref, computed, reactive, watch } from 'vue'
import { useThemeColors } from '/@src/composable/useThemeColors'
import { useApi } from '/@src/composable/useApi'
import { useUserSession } from '/@src/stores/userSession'
import { useHead } from '@vueuse/head'
import ConfirmDialog from 'primevue/confirmdialog'
import { useConfirm } from 'primevue/useconfirm'
import * as H from '/@src/utils/appHelper'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import CardCountRev from '/@src/components/partials/widgets/stat/CardCountRev.vue'
import Calendar from 'primevue/calendar';
import * as qzService from '/@src/utils/qzTrayService'
import RadioButton from 'primevue/radiobutton';
import Dropdown from 'primevue/dropdown';

const router = useRouter()
const route = useRoute()
useHead({
  title: 'Dashboard Registrasi ' + import.meta.env.VITE_PROJECT,
})
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)
const userLogin = useUserSession().getUser()
const activeValue: any = ref(1)
const item: any = reactive({})
let d_Tahap: any = ref([])
const d_Tahun: any = ref([])
let dataAnggaran: any = ref([])
let dataAnggaranTotal: any = ref(0)
const currentPage: any = ref({
  limit: 5,
  rows: 50
})


currentPage.value.page = computed(() => {
  try {
    return Number.parseInt(route.query.page as string) || 1
  } catch { }
  return 1
})

watch(currentPage.value, () => {
  loadData()
})
const selectedCategory = ref('Production');
const tabs: any = ref([
  { label: 'Dashboard Anggaran', value: 1 },
  { label: 'Dashboard SPJ', value: 2 },
  { label: 'Dashboard Panjar', value: 3 },
  { label: 'Laporan', value: 4 },
])
const toggle = (value: string) => {
    activeValue.value = value
}
for (let i = parseFloat(H.formatDate(new Date(),'YYYY')) - 5; i <= parseFloat(H.formatDate(new Date(),'YYYY')) + 5; i++) {
    d_Tahun.value.push({
        id: i, tahun: i
    })
}
const  loadCombo = async()=>{
  
    await useApi().get(
        `anggaran/get-combo`
    ).then((response) => {
        d_Tahap.value = response.tahap
    })
    await useApi().get('anggaran/get-data-setting-anggaran').then((response) => {
        d_Tahun.value.forEach(element => {
            if(element.tahun == response.data[0].tahunanggaran){
                item.tahun = element
            }
        });
        d_Tahap.value.forEach((element: any) => {
            if(element.id == response.data[0].objecttahapaktivfk){
                item.tahap = element
            }
        });
        item.organisasi = response.data[0].organisasi
    })
}
const loadData = async()=> {
  var div = '';
  if(item.div != undefined){
    div = '&div=' + item.div
  }
  var tahun = '';
  if(item.tahun != undefined){
    tahun = '&tahun=' + item.tahun.tahun
  }
  var tahap = '';
  if(item.tahap != undefined){
    tahap = '&tahap=' + item.tahap.id
  }
  let limit: any = currentPage.value.limit
  let offset: any = route.query.page ? route.query.page : 1
  offset = (offset * limit) - limit

  await useApi().get(`/dashboard/get-data-anggaran?${div}${tahun}${tahap}&offset=${offset}&limit=${limit}&rows=${currentPage.value.rows}`).then((response)=> {
    dataAnggaran.value = response.data
    dataAnggaranTotal.value = response.total
    route.query.page = '1'
  })
}
loadCombo()
let categories: any = ref([
    { name: 'Accounting', key: 'A' },
    { name: 'Marketing', key: 'M' },
    { name: 'Production', key: 'P' },
    { name: 'Research', key: 'R' }
]);
const fetchAnggaran = async()=> {
}
watch(
  () => [
    item.div
  ], () => {
    loadData()
  }
)
</script>

<style lang="scss">
@import '/@src/scss/abstracts/all';
@import '/@src/scss/custom/config';

.c-title {
  border-top-left-radius: 11px;
  border-left: solid hsl(19deg 100% 75% / 72%) 3px;
}
.block-heading {
  font-family: var(--font-alt);
  font-weight: 600;
  font-size: 1.1rem;
  color: var(--white);
  margin-bottom: 4px;
}
.tabs li.is-active a:hover, .tabs li.is-active a:focus {
    border-bottom-color: #0398E2;
    color: #283252;
    font-weight: bold
}

.tabs li.is-active a, .tabs li.is-active a {
    border-bottom-color: #0398E2;
    color: #283252;
}
.hr-dashboard {
    .block-header {
      display: flex;
      border-radius: 16px;
      padding: 50px;
      background: var(--primary);
      font-family: var(--font);
      box-shadow: var(--primary-box-shadow);
  
      .left,
      .right {
        width: 30%;
      }
  
      .center {
        display: flex;
        flex-direction: column;
        width: 40%;
        padding-right: 30px;
        margin-right: 30px;
        border-right: 1px solid var(--primary-light-10);
  
        .block-text {
          margin-bottom: 16px;
        }
  
        .candidates {
          margin-top: auto;
  
          >.v-avatar {
            margin-right: 10px;
          }
  
          button {
            height: 40px;
            width: 40px;
            display: inline-flex;
            justify-content: center;
            align-items: center;
            border-radius: 10px;
            background: var(--white);
            color: var(--light-text);
            border: none;
            cursor: pointer;
            transition: all 0.3s; // transition-all test
  
            svg {
              height: 18px;
              width: 18px;
            }
          }
        }
      }
  
      .left {
        display: flex;
        justify-content: center;
        align-items: center;
  
        .current-user {
          .v-avatar {
            margin-bottom: 1rem;
          }
  
          h3 {
            font-family: var(--font-alt);
            font-weight: 700;
            font-size: 1.8rem;
            color: var(--white);
            line-height: 1.2;
          }
        }
      }
  
      .right {
        display: flex;
        flex-direction: column;
  
        .button {
          margin-top: auto;
        }
      }
  
      .block-heading {
        font-family: var(--font-alt);
        font-weight: 600;
        font-size: 1.1rem;
        color: var(--white);
        margin-bottom: 4px;
      }
  
      .block-text {
        font-family: var(--font);
        font-size: 0.9rem;
        color: var(--white);
        margin-bottom: 16px;
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
          margin-bottom: 16px;
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
    .tabs-wrapper.is-slider .tabs, .tabs-wrapper-alt.is-slider .tabs {
      position: relative;
      background: var(--fade-grey-light-2);
      border: 1px solid var(--fade-grey);
      max-width: 300px;
      height: 35px;
      border-bottom: none;
  
    }
  
    .search-menu {
      height: 56px;
      white-space: nowrap;
      display: flex;
      flex-shrink: 0;
      align-items: center;
      background-color: white;
      border-radius: 8px;
      width: 100%;
      padding-left: 0.75rem;
  
      >div:not(:last-of-type) {
        border-right: 1px solid var(--search-border-color);
      }
  
      .search-bar {
        height: 55px;
        width: 100%;
        position: relative;
        display: flex;
        align-items: center;
        padding-right: 1.5rem;
  
        .field {
          width: 100%;
        }
  
        .multiselect-tags {
          padding-left: 2.5rem;
        }
      }
  
      .search-location,
      .search-job,
      .search-salary {
        display: flex;
        align-items: center;
        width: 50%;
        font-size: 14px;
        font-weight: 500;
        padding: 0 25px;
        height: 100%;
        font-family: var(--font);
  
        input {
          width: 100%;
          height: 90%;
          display: block;
          font-family: var(--font);
          color: var(--input-color);
          background-color: transparent;
          border: none;
        }
  
        svg {
          margin-right: 0.5rem;
          width: 18px;
          color: var(--primary);
          flex-shrink: 0;
        }
      }
  
      .search-button {
        background-color: var(--primary);
        min-width: 100px;
        height: 56px;
        border: none;
        font-weight: 500;
        font-family: var(--font);
        padding: 0 1rem;
        border-radius: 0 0.75rem 0.75rem 0;
        color: white;
        cursor: pointer;
        margin-left: auto;
      }
    }
  
    .search-widget {
      flex: 1;
      display: inline-block;
      width: 100%;
      padding: 12px;
      background-color: var(--white);
      border-radius: 16px;
      border: 1px solid var(--fade-grey-dark-3);
      transition: all 0.3s;
    }
  
    .feed-settings {
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 20px 0;
  
      h3 {
        font-family: var(--font-alt);
        font-size: 1.1rem;
        font-weight: 600;
        color: var(--dark-text);
      }
  
      .button {
        font-size: 0.8rem;
        border-radius: 8px;
        margin-right: 4px;
  
        &.is-selected {
          background: var(--primary);
          color: var(--white);
          border-color: var(--primary);
          box-shadow: var(--primary-box-shadow);
        }
      }
    }
  
    .side-text {
      h3 {
        font-family: var(--font-alt);
        font-size: 1.1rem;
        font-weight: 600;
        color: var(--dark-text);
        margin-bottom: 8px;
      }
  
      p {
        font-size: 0.95rem;
        margin-bottom: 8px;
      }
  
      .action-link {
        font-size: 0.9rem;
      }
    }
  
    .recent-rookies {
      .recent-rookies-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 20px;
  
        h3 {
          font-family: var(--font-alt);
          font-size: 1.1rem;
          font-weight: 600;
          color: var(--dark-text);
        }
      }
  
      .user-grid {
        &.user-grid-v4 {
          .grid-item {
            @include vuero-l-card;
          }
        }
      }
    }
  }
  
  .user-grid {
    .columns {
      margin-left: -0.5rem !important;
      margin-right: -0.5rem !important;
      margin-top: -0.5rem !important;
    }
  
    .column {
      padding: 0.5rem !important;
    }
  
    .grid-item {
      position: relative;
      @include vuero-s-card;
  
      text-align: center;
  
      &:hover,
      &:focus {
        .button-wrap {
          >div {
            a {
              opacity: 1;
              pointer-events: all;
            }
          }
        }
      }
  
      .dropdown {
        position: absolute;
        top: 10px;
        right: 10px;
        text-align: left;
      }
  
      >.v-avatar {
        display: block;
        margin: 0 auto 4px;
      }
  
      h3 {
        font-family: var(--font-alt);
        font-size: 1.1rem;
        font-weight: 600;
        color: var(--dark-text);
      }
  
      p {
        font-size: 0.85rem;
      }
  
      .button-wrap {
        margin: 20px 0 0;
  
        .v-button {
          width: 100%;
          max-width: 140px;
          margin: 0 auto;
        }
  
        >div {
          margin: 6px 0 0;
  
          a {
            opacity: 0;
            pointer-events: none;
            color: var(--light-text);
            font-weight: 500;
            font-size: 0.9rem;
            transition: opacity 0.3s, color 0.3s;
  
            &:hover,
            &:focus {
              color: var(--primary);
            }
          }
        }
      }
    }
  }
  
  .user-grid .grid-item h3 {
    font-family: var(--font-alt);
    font-size: 0.8rem;
    font-weight: 600;
    color: var(--dark-text);
  }
  
  .is-dark {
    .user-grid {
      .grid-item {
        @include vuero-card--dark;
      }
    }
    
  
    .hr-dashboard {
      .block-header {
        background: var(--dark-sidebar);
        box-shadow: none;
  
        .center {
          border-color: var(--dark-sidebar-light-10);
  
          .candidates {
            button {
              background: var(--dark-sidebar-light-10);
              border: 1px solid transparent;
              transition: all 0.3s; // transition-all test
  
              &:hover {
                border-color: var(--primary);
  
                svg {
                  color: var(--primary);
                }
              }
            }
          }
        }
      }
  
      .feed-settings {
        .button {
          &.is-selected {
            background: var(--primary) !important;
            border-color: var(--primary) !important;
            box-shadow: var(--primary-box-shadow) !important;
            color: var(--white) !important;
          }
        }
      }
  
      .recent-rookies {
        .user-grid {
          &.user-grid-v4 {
            .grid-item {
              @include vuero-card--dark;
            }
          }
        }
      }
    }
  }
  
  .list-view-v1 {
    .list-view-item {
      @include vuero-r-card;
  
      margin-bottom: 5px;
      padding: 16px;
  
      .list-view-item-inner {
        display: flex;
        align-items: center;
  
        .meta-left {
          margin-left: 16px;
  
          h3 {
            font-family: var(--font-alt);
            color: var(--dark-text);
            font-weight: 600;
            font-size: 1rem;
            line-height: 1;
          }
  
          >span:not(.tag) {
            font-size: 0.9rem;
            color: var(--light-text);
  
            svg {
              height: 12px;
              width: 12px;
            }
          }
        }
  
        .meta-right {
          margin-left: auto;
          display: flex;
          justify-content: flex-end;
          align-items: center;
  
          .tags {
            margin-right: 30px;
            margin-bottom: 0;
  
            .tag {
              margin-bottom: 0;
            }
          }
  
          .stats {
            display: flex;
            align-items: center;
            margin-right: 30px;
  
            .stat {
              display: flex;
              align-items: center;
              flex-direction: column;
              text-align: center;
              color: var(--light-text);
  
              >span {
                font-family: var(--font);
  
                &:first-child {
                  font-size: 1.2rem;
                  font-weight: 600;
                  color: var(--dark-text);
                  line-height: 1.4;
                }
  
                &:nth-child(2) {
                  text-transform: uppercase;
                  font-family: var(--font-alt);
                  font-size: 0.75rem;
                }
              }
  
              svg {
                height: 16px;
                width: 16px;
              }
  
              i {
                font-size: 1.4rem;
              }
            }
  
            .separator {
              height: 25px;
              width: 2px;
              border-right: 1px solid var(--fade-grey-dark-3);
              margin: 0 16px;
            }
          }
  
          .network {
            display: flex;
            justify-content: flex-end;
            align-items: center;
            min-width: 145px;
  
            >span {
              font-family: var(--font);
              font-size: 0.9rem;
              color: var(--light-text);
              margin-left: 6px;
            }
          }
  
          .dropdown {
            margin-left: 30px;
          }
        }
      }
    }
  }
  
  .is-dark {
    .list-view-v1 {
      .list-view-item {
        @include vuero-card--dark;
  
        .list-view-item-inner {
          .meta-left {
            h3 {
              color: var(--dark-dark-text) !important;
            }
          }
  
          .meta-right {
            .stats {
              .stat {
                span {
                  &:first-child {
                    color: var(--dark-dark-text);
                  }
                }
              }
  
              .separator {
                border-color: var(--dark-sidebar-light-16) !important;
              }
            }
          }
        }
      }
    }
  }
  
  .list-view-v3 {
    .list-view-item {
      @include vuero-r-card;
  
      margin-bottom: 8px;
      padding: 16px;
  
      .list-view-item-inner {
        display: flex;
        align-items: center;
  
        >img {
          width: 100%;
          max-width: 60px;
          min-width: 60px;
          max-height: 60px;
          min-height: 60px;
          border-radius: var(--radius-rounded);
          border: 1px solid var(--fade-grey);
        }
  
        .meta-left {
          margin-left: 16px;
  
          h3 {
            font-family: var(--font-alt);
            color: var(--dark-text);
            font-weight: 500;
            font-size: 1.1rem;
            line-height: 1;
          }
  
          >span:not(.tag) {
            font-size: 0.9rem;
            color: var(--light-text);
  
            svg {
              position: relative;
              top: 1px;
              height: 12px;
              width: 12px;
            }
  
            .icon-separator {
              position: relative;
              top: -3px;
              font-size: 5px;
              color: var(--light-text);
              padding: 0 8px;
            }
  
            .iconify {
              margin-right: 0.25rem;
            }
          }
        }
  
        .meta-right {
          margin-left: auto;
          display: flex;
          align-items: center;
          justify-content: flex-end;
  
          .buttons {
            margin-bottom: 0;
            margin-right: 10px;
          }
        }
      }
    }
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
        margin-bottom: 16px;
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
  
  .is-dark {
    .list-view-v3 {
      .list-view-item {
        @include vuero-card--dark;
  
        .list-view-item-inner {
          >img {
            border-color: var(--dark-sidebar-light-12);
          }
  
          .meta-left {
            h3 {
              color: var(--dark-dark-text) !important;
            }
          }
  
          .meta-right {
            .buttons {
              .button {
                &:nth-child(2) {
                  background: var(--dark-sidebar-light-2);
                  border-color: var(--dark-sidebar-light-8);
                  color: var(--dark-dark-text);
                  transition: color 0.3s, background-color 0.3s, border-color 0.3s,
                    height 0.3s, width 0.3s;
  
                  &:hover,
                  &:focus {
                    border-color: var(--primary);
                    color: var(--primary);
                  }
                }
              }
            }
          }
        }
      }
    }
  }
  
  .tile-grid-v2 {
    .tile-grid-item {
      @include vuero-s-card;
  
      border-radius: 14px;
      padding: 16px;
      cursor: pointer;
  
      &:hover,
      &:focus {
        border-color: var(--primary);
        box-shadow: var(--light-box-shadow);
      }
  
      .tile-grid-item-inner {
        display: flex;
        align-items: center;
  
        >img {
          display: block;
          width: 50px;
          height: 50px;
          min-width: 50px;
        }
  
        .meta {
          margin-left: 10px;
          line-height: 1.4;
  
          span {
            display: block;
            font-family: var(--font);
  
            &:first-child {
              color: var(--dark-text);
              font-family: var(--font-alt);
              font-weight: 600;
              font-size: 0.8rem;
            }
  
            &:nth-child(2) {
              display: flex;
              align-items: center;
  
              span {
                display: inline-block;
                color: var(--light-text);
                font-size: 0.3rem;
                font-weight: 400;
              }
  
              .icon-separator {
                position: relative;
                font-size: 4px;
                color: var(--light-text);
                padding: 0 6px;
              }
            }
          }
        }
  
        .dropdown {
          margin-left: auto;
        }
      }
    }
  }
  
  .is-dark {
    .tile-grid {
      .tile-grid-item {
        @include vuero-card--dark;
      }
    }
  
    .tile-grid-v2 {
      .tile-grid-item {
        @include vuero-card--dark;
  
        &:hover,
        &:focus {
          border-color: var(--primary) !important;
        }
      }
    }
  }
  .speeddial-tooltip-demo .p-speeddial-direction-up.speeddial-left {
    left: 0;
    bottom: 0;
}

.speeddial-tooltip-demo .p-speeddial-direction-up.speeddial-right {
    right: 0;
    bottom: 0;
}


.speeddial-delay-demo .p-speeddial-direction-up {
    left: calc(50% - 2rem);
    bottom: 0;
}

.speeddial-mask-demo .p-speeddial-direction-up {
    right: 0;
    bottom: 0;
}
  
  @media only screen and (max-width: 767px) {
    .hr-dashboard {
      .block-header {
        flex-direction: column;
        padding: 30px;
  
        .left,
        .center,
        .right {
          width: 100%;
        }
  
        .left {
          justify-content: flex-start;
          margin-bottom: 20px;
        }
  
        .center {
          padding-right: 0;
          margin-right: 0;
          border-right: none;
          margin-bottom: 20px;
        }
      }
  
      .feed-settings {
        flex-direction: column;
  
        h3 {
          margin-bottom: 16px;
        }
      }
    }
  }
  
  @media only screen and (min-width: 768px) and (max-width: 1024px) and (orientation: portrait) {
    .hr-dashboard {
      .block-header {
        padding: 40px;
      }
  
      .side-text {
        display: none;
      }
    }
  }
  
  @media only screen and (min-width: 768px) and (max-width: 1024px) and (orientation: landscape) {
    .hr-dashboard {
      .block-header {
        padding: 40px;
  
        .left {
          .current-user {
            h3 {
              font-size: 1.5rem;
            }
          }
        }
  
        .center {
          .candidates {
            .v-avatar {
              &:nth-child(3) {
                display: none;
              }
            }
          }
        }
      }
  
      .column {
        &.is-7 {
          &.is-offset-1 {
            margin-left: 2% !important;
            width: 64.3333% !important;
          }
        }
      }
    }
  }
  

.p-tabview .p-tabview-panels {
    background: #dbd9d947;
    padding: 1rem;
    border: 0 none;
    color: #495057;
    border-bottom-right-radius: 3px;
    border-bottom-left-radius: 3px;
}

.is-dark {
  .all-projects {
    .all-projects-header {
      background: var(--dark-sidebar-light-6);
      border-color: var(--dark-sidebar-light-12);

      .header-item {
        border-color: var(--dark-sidebar-light-18);

        span {
          color: var(--dark-dark-text);
        }

        i {
          color: var(--primary) !important;
        }
      }
    }

    .projects-card-grid {
      .grid-item {
        background: var(--dark-sidebar-light-6);
        border-color: var(--dark-sidebar-light-12);

        .top-section {
          .head {
            h3 {
              color: var(--dark-dark-text);
            }
          }
        }

        .bottom-section {
          .foot-block {
            .heading {
              color: var(--light-text-dark-12);
            }
          }
        }
      }
    }
  }
}
</style>