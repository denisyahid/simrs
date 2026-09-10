<template>
  <VCard>
    <div class="personal-dashboard personal-dashboard-v1">
      <div class="dashboard-header">
        <VAvatar picture="/images/icons/files/satusehat-1.svg" size="large" />
        <div class="start">
          <h3>SIRANAP</h3>
          <p>Sistem Informasi Rawat Inap</p>
        </div>
        <div class="end">
          <img src="/images/avatars/svg/keluar.svg" alt="" style="width:150px;margin-top:-3rem" />
        </div>
      </div>
      <div class="dashboard-body mt-5-min">
        <div class="columns is-multiline">
          <div class="column is-12">
            <div class="business-dashboard course-dashboard">
              <div class="dashboard-title is-main">
                <div class="left">

                </div>
                <div class="right">
                  <!-- <VButton color="primary" elevated>Open Schedule</VButton> -->
                </div>
              </div>

              <div class="course-grid">
                <div class="columns is-multiline">
                  <div class="column is-12">
                    <TabView class="tabview-custom " :scrollable="true" @tab-click="klikTab($event)">
                      <TabPanel>
                        <template #header>
                          <i class="fas fa-bed mr-2" aria-hidden="true"></i>
                          <span>Tempat Tidur</span>
                        </template>
                        <div class="columns is-multiline">
                          <div class="column is-12">
                            <div class="columns is-multiline">
                              <div class="column is-4">
                                <VField label="Filter">
                                  <VControl icon="feather:search">
                                    <input v-model="filters" v-on:keyup.enter="fetchData()" type="text"
                                      class="input is-rounded" placeholder="Filter" />
                                  </VControl>
                                </VField>
                              </div>

                              <div class="column is-2 mt-5 ">
                                <VIconButton type="button" color="success" circle raised icon="fas fa-search"
                                  @click="fetchData()" class="mr-2" :loading="isLoading">
                                </VIconButton>
                                <VButton type="button" color="info" circle raised rounded icon="fas fa-paper-plane"
                                  @click="updateTT()" :loading="isLoading2">
                                  Update
                                </VButton>

                              </div>
                              <div class="column is-6 mt-5" v-if="valueProgress > 0 && isLoading2">
                                <ProgressBar :value="valueProgress" style="height: 15px" />
                              </div>
                            </div>
                            <div class="columns is-multiline">


                              <h3 class="is-pulled-right">Terdapat {{ dataSource.length }} data.</h3>

                            </div>
                            <div class="columns is-multiline">


                              <div v-for="index in 24" :key="index" class="column is-2" v-if="isLoading">
                                <VPlaceloadWrap>
                                  <VPlaceload height="100px" width="100%" class="mx-2 is-rounded" />
                                </VPlaceloadWrap>
                              </div>
                              <div class="columns is-multiline" v-if="dataSourcefiltered.length == 0 && !isLoading" style="
    text-align: center;
    display: contents;">
                                <div class="column is-12 update-item is-dark-bordered-12 " style="display: block;">
                                  <div class="search-results-wrapper">
                                    <div class="search-results-body ">
                                      <!--Search Placeholder -->
                                      <div class="page-placeholder">
                                        <div class="placeholder-content">
                                          <img class="light-image" style=" max-width: 340px;"
                                            :src="H.assets().iconNotFound_rev" alt="" />
                                          <img class="dark-image" style=" max-width: 340px;"
                                            :src="H.assets().iconNotFound_rev" alt="" />
                                          <h3>{{ H.assets().notFound }}</h3>
                                          <p class="is-larger">
                                            {{ H.assets().notFoundSubtitle }}
                                          </p>
                                        </div>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <div class="columns is-multiline" v-if="dataSourcefiltered.length > 0 && !isLoading">
                                <div v-for="course in dataSourcefiltered" :key="course.id" class="column is-2">
                                  <div class="course-card">
                                    <span class="tag is-elevated" style="" :class="[
                                      course.kosong == course.jumlah && 'is-green',
                                      (course.kosong != course.jumlah && (course.kosong != null || course.kosong != 0)) && 'is-warning',
                                      (course.kosong === null || course.kosong === 0) && 'is-danger',
                                    ]">{{ course.jumlah == null ? 0 : course.jumlah }}</span>
                                    <!-- <VIconButton type="button" color="danger" circle raised outlined icon="feather:trash"
                                      @click="hapus(course)" class="mr-2 is-pulled-right" :loading="isLoading">
                                    </VIconButton> -->
                                    <h3 class="dark-inverted mt-5-min">{{ course.tt }}</h3>
                                    <!-- <p>{{ course.ruang }}</p> -->

                                    <div class="attached-block mt-4-min">
                                      <a class="is-dark-primary">{{ course.ruang }}</a>
                                      <div class="block-stats">
                                        <span>
                                          <i aria-hidden="true" class="iconify" data-icon="fa:bed"></i>
                                          <small>Tersedia <b>{{ (course.kosong == null ? 0 : course.kosong) }}</b> bed
                                            kosong dari <b>{{ course.jumlah }} </b></small>
                                        </span>
                                      </div>
                                    </div>
                                    <div class="attached-block mt-1-min">
                                      <div class="block-stats">
                                        <span>
                                          <i aria-hidden="true" class="iconify" data-icon="feather:clock"></i>
                                          <small>{{ course.tglupdate ? course.tglupdate : 'Belum diupdate' }}</small>
                                        </span>
                                      </div>
                                    </div>
                                    <div class="action-block" style="">
                                      <VButton type="button" color="danger" circle raised outlined rounded
                                        icon="fas fa-trash" class="is-fullwidth" :loading="course.isLoading"
                                        @click="hapus(course)">
                                        Hapus
                                      </VButton>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </TabPanel>
                      <TabPanel>
                        <template #header>
                          <i class="fas fa-sitemap mr-2" aria-hidden="true"></i>
                          <span>Map Kelas</span>
                        </template>
                        <div class="columns is-multiline">
                          <div class="column is-12">
                            <div class="columns is-multiline">
                              <div class="column is-3">
                                <VField>
                                  <VControl icon="feather:search">
                                    <input v-model="filtersRu" class="input custom-text-filter" placeholder="Filter" />
                                  </VControl>
                                </VField>
                              </div>
                              <div class="column is-2">
                                <VIconButton circle icon="feather:refresh-cw" raised bold @click="fetchData2()"
                                  :loading="isLoading" color="success" class="mr-2">
                                </VIconButton>
                                <!-- <VIconButton circle icon="feather:plus" raised bold color="info" @click="add2()"> </VIconButton> -->
                              </div>
                              <div class="column is-7 " style="text-align:left">
                                <VField class="switch-filter">
                                  <VControl>
                                    <InputSwitch v-model="item.aktif2" @change="fetchData2()" />
                                  </VControl>
                                  <span class="mt-2-min ml-2">Sudah Mapping</span>
                                </VField>
                              </div>
                            </div>
                            <div class="tile-grid tile-grid-v1">
                              <div class="columns is-multiline">
                                <div v-for="keys in 10" :key="keys" class="column is-3 " v-if="isLoading">
                                  <VPlaceloadWrap>
                                    <VPlaceloadAvatar size="medium" />
                                    <VPlaceloadText last-line-width="60%" class="mx-2" />
                                    <VPlaceload class="mx-2" disabled />
                                    <VPlaceload class="mx-2 h-hidden-tablet-p" />
                                    <VPlaceload class="mx-2 h-hidden-tablet-p" />
                                    <VPlaceload class="mx-2" />
                                  </VPlaceloadWrap>

                                </div>
                                <div v-for="(item, key) in dataSourcefiltered2" :key="key" class="column is-2" v-else>
                                  <div class="tile-grid-item">
                                    <div class="tile-grid-item-inner">
                                      <VAvatar size="medium" :picture="'/images/avatars/svg/room.png'" color="primary"
                                        squared bordered />
                                      <div class="meta">
                                        <span class="dark-inverted">{{ item.namakelas }}</span>
                                        <span style=" width: 120px !important;
                    white-space: nowrap;
                    overflow: hidden !important;
                    text-overflow: ellipsis;
                    display: block;">{{ item.kodesirs }}</span>
                                        <VTag :color="item.kodesirs ? 'success' : 'solid'"
                                          :label="item.kodesirs ? 'Sudah Mapping' : ''" />
                                      </div>

                                      <VIconButton circle outlined spaced right icon="fas fa-edit" raised bold style="display: inline-flex;    position: relative;
                      margin-left: auto;" @click="edit2(item)" :loading="item.loading" color="warning"
                                        v-tooltip.bubble="'Mapping'">
                                      </VIconButton>
                                      <!-- <VDropdown icon="feather:more-vertical" spaced right>

                                      </VDropdown> -->
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>

                        </div>
                      </TabPanel>
                      <TabPanel>
                        <template #header>
                          <i class="fas fa-sitemap mr-2" aria-hidden="true"></i>
                          <span>Map Kamar</span>
                        </template>
                        <div class="columns is-multiline">
                          <div class="column is-12">
                            <div class="columns is-multiline">
                              <div class="column is-3">
                                <VField>
                                  <VControl icon="feather:search">
                                    <input v-model="filtersRu2" class="input custom-text-filter" placeholder="Filter" />
                                  </VControl>
                                </VField>
                              </div>
                              <div class="column is-2">
                                <VIconButton circle icon="feather:refresh-cw" raised bold @click="fetchData3()"
                                  :loading="isLoading" color="success" class="mr-2">
                                </VIconButton>
                                <!-- <VIconButton circle icon="feather:plus" raised bold color="info" @click="add2()"> </VIconButton> -->
                              </div>
                              <div class="column is-7 " style="text-align:left">
                                <VField class="switch-filter">
                                  <VControl>
                                    <InputSwitch v-model="item.aktif3" @change="fetchData3()" />
                                  </VControl>
                                  <span class="mt-2-min ml-2">Sudah Mapping</span>
                                </VField>
                              </div>
                            </div>
                            <div class="tile-grid tile-grid-v1">
                              <div class="columns is-multiline">
                                <div v-for="keys in 10" :key="keys" class="column is-3 " v-if="isLoading">
                                  <VPlaceloadWrap>
                                    <VPlaceloadAvatar size="medium" />
                                    <VPlaceloadText last-line-width="60%" class="mx-2" />
                                    <VPlaceload class="mx-2" disabled />
                                    <VPlaceload class="mx-2 h-hidden-tablet-p" />
                                    <VPlaceload class="mx-2 h-hidden-tablet-p" />
                                    <VPlaceload class="mx-2" />
                                  </VPlaceloadWrap>

                                </div>
                                <div v-for="(item, key) in dataSourcefiltered3" :key="key" class="column is-3" v-else>
                                  <div class="tile-grid-item">
                                    <div class="tile-grid-item-inner">
                                      <VAvatar size="medium" :picture="'/images/avatars/svg/room.png'" color="primary"
                                        squared bordered />
                                      <div class="meta">
                                        <span class="dark-inverted">{{ item.namakamar }}</span>
                                        <span style=" width: 120px !important;
                    white-space: nowrap;
                    overflow: hidden !important;
                    text-overflow: ellipsis;
                    display: block;">Ruang : {{ item.namakelas ? item.namakelas : '-' }}</span>
                                        <span style=" width: 120px !important;
                    white-space: nowrap;
                    overflow: hidden !important;
                    text-overflow: ellipsis;
                    display: block;">{{ item.namaruangan ? item.namaruangan : '-' }}</span>
                                        <VTag :color="item.kodesirs ? 'success' : 'solid'"
                                          :label="item.kodesirs ? 'Sudah Mapping' : ''" />
                                      </div>

                                      <VIconButton circle outlined spaced right icon="fas fa-edit" raised bold style="display: inline-flex;    position: relative;
                      margin-left: auto;" @click="edit3(item)" :loading="item.loading" color="warning"
                                        v-tooltip.bubble="'Edit'">
                                      </VIconButton>
                                      <!-- <VDropdown icon="feather:more-vertical" spaced right>

                                      </VDropdown> -->
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>

                        </div>
                      </TabPanel>
                    </TabView>
                  </div>
                </div>
              </div>

            </div>
          </div>
        </div>
      </div>
    </div>
    <VModal :open="modalInput2" title="Edit" actions="right" @close="modalInput2 = false">
      <template #content>
        <form class="modal-form">
          <div class="columns is-multiline">
            <div class="column is-12">
              <VField label="Nama Kelas">
                <VControl icon="feather:bookmark">
                  <VInput type="text" v-model="item.namakelas" placeholder="Nama Kelas" class="is-rounded" disabled />
                </VControl>
              </VField>
            </div>
            <div class="column is-12">
              <VField class="is-rounded-select is-autocomplete-select">
                <VLabel class="item">Kode SIRANAP</VLabel>
                <VControl icon="feather:search" class="prime-auto-select">
                  <Dropdown v-model="item.kodesirs_kelas" :options="d_Ref" :optionLabel="'nama_tt'" class="is-rounded"
                    placeholder="Pilih data" style="width: 100%;" showClear :filter="true" />
                </VControl>
              </VField>
            </div>
          </div>
        </form>
      </template>
      <template #action>
        <VButton icon="feather:plus" @click="save2()" :loading="isLoading" color="primary" raised>Simpan</VButton>
      </template>
    </VModal>
    <VModal :open="modalInput3" title="Edit" actions="right" @close="modalInput3 = false">
      <template #content>
        <form class="modal-form">
          <div class="columns is-multiline">
            <div class="column is-12">
              <VField label="Nama Ruangan">
                <VControl icon="feather:bookmark">
                  <VInput type="text" v-model="item.namaruangan" placeholder="Nama Ruangan" class="is-rounded" disabled />
                </VControl>
              </VField>
            </div>
            <div class="column is-12">
              <VField label="Nama Kamar">
                <VControl icon="feather:bookmark">
                  <VInput type="text" v-model="item.namakamar" placeholder="Nama Kamar" class="is-rounded" disabled />
                </VControl>
              </VField>
            </div>
            <div class="column is-12">
              <VField label="Nama Kelas">
                <VControl icon="feather:bookmark">
                  <VInput type="text" v-model="item.namakelas" placeholder="Nama Kelas" class="is-rounded" disabled />
                </VControl>
              </VField>
            </div>
            <div class="column is-12">
              <VField class="is-rounded-select is-autocomplete-select">
                <VLabel class="item">Kode SIRANAP</VLabel>
                <VControl icon="feather:search" class="prime-auto-select">
                  <Dropdown v-model="item.kodesirs_kelas" :options="d_Ref" :optionLabel="'nama_tt'" class="is-rounded"
                    placeholder="Pilih data" style="width: 100%;" showClear :filter="true" />
                </VControl>
              </VField>
            </div>
          </div>
        </form>
      </template>
      <template #action>
        <VButton icon="feather:plus" @click="save3()" :loading="isLoading" color="primary" raised>Simpan</VButton>
      </template>
    </VModal>
  </VCard>
</template>
<script setup lang="ts">
import { useWindowScroll } from '@vueuse/core'
import { useApi } from '/@src/composable/useApi'
import { h, reactive, ref, computed, defineComponent, watch } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useHead } from '@vueuse/head'
import * as H from '/@src/utils/appHelper'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import TabView from 'primevue/tabview';
import TabPanel from 'primevue/tabpanel';
import AutoComplete from 'primevue/autocomplete';
import Dropdown from 'primevue/dropdown';
import Menu from 'primevue/menu';
import Badge from 'primevue/badge';
import InputSwitch from 'primevue/inputswitch'
import ProgressBar from 'primevue/progressbar';
useHead({
  title: 'SIRANAP - ' + import.meta.env.VITE_PROJECT,
})
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)
const filtersRu: any = ref('')
const isSync: any = ref(false)
const valueProgress: any = ref(0)
const filtersRu2: any = ref('')
const isLoading: any = ref(false)
const item: any = reactive({})
const isLoading2: any = ref(false)
const filters: any = ref('')
const dataSource2: any = ref([])
const activeTab: any = ref(0)
const dataSource: any = ref([])
const dataSource3: any = ref([])
const d_Ref: any = ref([])
const modalInput2: any = ref(false)
const modalInput3: any = ref(false)
const dataSourcefiltered = computed(() => {
  if (!filters.value) {
    return dataSource.value
  }
  return dataSource.value.filter((item: any) => {
    return (
      item.ruang.match(new RegExp(filters.value, 'i'))
      ||
      item.tt.match(new RegExp(filters.value, 'i'))
    )
  })
})

const dataSourcefiltered2: any = computed(() => {
  if (!filtersRu.value) {
    return dataSource2.value
  }

  return dataSource2.value.filter((items: any) => {
    return (
      items.namakelas.match(new RegExp(filtersRu.value, 'i'))

    )
  })
})
const dataSourcefiltered3: any = computed(() => {
  if (!filtersRu2.value) {
    return dataSource3.value
  }

  return dataSource3.value.filter((items: any) => {
    return (
      items.namaruangan.match(new RegExp(filtersRu2.value, 'i'))
      ||
      items.namakamar.match(new RegExp(filtersRu2.value, 'i'))
      ||
      items.namakelas.match(new RegExp(filtersRu2.value, 'i'))
    )
  })
})
const fetchRef = async () => {
  let json = {
    "url": `Referensi/tempat_tidur`,
    "method": "GET",
    "data": null
  }



  const datas = await useApi().postNoMessage(`/bridging/siranap/tools`, json)

  d_Ref.value = datas.response.tempat_tidur
}

const fetchData = async () => {
  let json = {
    "url": `Fasyankes`,
    "method": "GET",
    "data": null
  }

  isLoading.value = true

  const resp = await useApi().postNoMessage(`/bridging/siranap/tools`, json)
  isLoading.value = false
  let datas: any = []
  if (resp.response.fasyankes[0].message != undefined) {
    let mes = JSON.parse(resp.response.fasyankes[0].message)
    H.alert('error', mes.response)
    if( mes.response  == 'Gagal Login'){
      H.alert('error', 'Harap setting ID RS & Password SIRANAP')
    }
  }
  resp.response.fasyankes.forEach((element: any) => {
    if (element.tt == null) {
      element.tt = ''
    }
    if (element.ruang != null) {
      datas.push(element)
    }
  });
  dataSource.value = datas
}
const updateTT = async () => {
  const dataTT = await useApi().get(`/bridging/siranap/get-tt`)
  valueProgress.value = 0;
  let n = 0
  for (let x = 0; x < dataTT.length; x++) {
    const element = dataTT[x];
    let json = {
      "url": `Fasyankes`,
      "method": "POST",
      "data": element
    }
    n = (x + 1) * 100 / dataTT.length
    isLoading2.value = true
    const resp = await useApi().postNoMessage(`/bridging/siranap/tools`, json)

    if (resp.response.fasyankes.length) {
      if (resp.response.fasyankes[0].message.indexOf('silahkan edit dengan method PUT') > -1) {
        let json = {
          "url": `Fasyankes`,
          "method": "PUT",
          "data": element
        }
        const resp2 = await useApi().postNoMessage(`/bridging/siranap/tools`, json)
        if (resp2.response.fasyankes.length) {
          H.alert('success', resp2.response.fasyankes[0].message)
        }
      } else {
        H.alert('success', resp.response.fasyankes[0].message)
      }
      valueProgress.value = n.toFixed(2);

    }
    isLoading2.value = false
  }

  fetchData()
}
const hapus = async (e: any) => {

  let json = {
    "url": `Fasyankes`,
    "method": "DELETE",
    "data": {
      "id_t_tt": e.id_t_tt
    }
  }
  e.isLoading = true
  const resp2 = await useApi().postNoMessage(`/bridging/siranap/tools`, json)
  e.isLoading = false
  if (resp2.response.fasyankes.length) {
    H.alert('success', resp2.response.fasyankes[0].message)
  }
  fetchData()
}
const klikTab = (e: any) => {
  activeTab.value = e.index
  if (activeTab.value == 0) {
    fetchData()
  }
  if (activeTab.value == 1) {
    fetchData2()
  }
  if (activeTab.value == 2) {
    fetchData3()
  }
}
async function fetchData2() {
  isLoading.value = true

  const response = await useApi().get(
    '/bridging/siranap/master-kelas?statusenabled=true&kodesirs=' + (item.aktif2 == true ? true : '')
  )
  isLoading.value = false
  for (let x = 0; x < response.data.length; x++) {
    const element = response.data[x];
    element.no = x + 1
  }

  dataSource2.value = response.data
}

async function fetchData3() {
  isLoading.value = true

  const response = await useApi().get(
    '/bridging/siranap/master-kamar?statusenabled=true&kodesirs=' + (item.aktif2 == true ? true : '')
  )
  isLoading.value = false
  for (let x = 0; x < response.data.length; x++) {
    const element = response.data[x];
    element.no = x + 1
  }

  dataSource3.value = response.data
}
const edit2 = (e: any) => {

  item.id = e.id
  item.namakelas = e.namakelas
  d_Ref.value.forEach((element: any) => {
    if (element.kode_tt == e.kodesirs) {
      item.kodesirs_kelas = element
    }
  });


  modalInput2.value = true
}
async function save2() {
  let objSave =
  {
    'id': item.id,
    'kodesirs': item.kodesirs_kelas ? item.kodesirs_kelas.kode_tt : null,
  }

  isLoading.value = true
  await useApi().post(
    `/bridging/siranap/map-kelas`, objSave).then((response: any) => {
      isLoading.value = false
      clear()
      fetchData2()
    }, (error) => {
      isLoading.value = false

    })
}
const edit3 = (e: any) => {

  item.id_kamar = e.id
  item.namakamar = e.namakamar
  item.namakelas = e.namakelas
  item.namaruangan = e.namaruangan
  d_Ref.value.forEach((element: any) => {
    if (element.kode_tt == e.kodesirs) {
      item.kodesirs_kelas = element
    }
  });


  modalInput3.value = true
}

async function save3() {
  let objSave =
  {
    'id': item.id_kamar,
    'kodesirs': item.kodesirs_kelas ? item.kodesirs_kelas.kode_tt : null,
  }

  isLoading.value = true
  await useApi().post(
    `/bridging/siranap/map-kamar`, objSave).then((response: any) => {
      isLoading.value = false
      clear()
      fetchData3()
    }, (error) => {
      isLoading.value = false

    })
}
const clear = () => {
  delete item.id
  delete item.namakelas
  delete item.kodesirs_kelas
  delete item.id_kamar
  delete item.namakamar
  delete item.namaruangan

}
fetchData()
fetchRef()
</script>



<style lang="scss">
@import '/@src/scss/abstracts/all';

@import '/@src/scss/custom/config';
@import '/@src/scss/module/sysadmin/master-data.scss';

.course-dashboard {
  .dashboard-title {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 20px;
    font-family: var(--font);

    &.is-main {
      margin-bottom: 30px;

      h2 {
        font-size: 1.8rem;
      }
    }

    h2 {
      font-family: var(--font-alt);
      font-size: 1.2rem;
      font-weight: 600;
      color: var(--dark-text);
    }
  }

  .course-grid {
    margin-bottom: 2rem;

    .course-card {
      @include vuero-l-card;

      .tag {
        height: 2.75em;
        padding-left: 1rem;
        padding-right: 1rem;
        border-radius: 0.5rem;
        line-height: 2.7;
        margin-bottom: 2rem;
      }

      h3 {
        font-family: var(--font-alt);
        font-size: 1.1rem;
        font-weight: 600;
        color: var(--dark-text);
        margin-bottom: 1rem;
      }

      .students {
        display: flex;
        align-items: center;
        padding: 2rem 0;

        .v-avatar {
          margin: 0 0.25rem;

          .avatar {
            &.is-fake {
              span {
                position: relative;
                left: -2px;
              }
            }
          }
        }

        .link {
          font-family: var(--font);
          font-weight: 500;
          color: var(--primary);
          margin-left: 1rem;
        }
      }

      .attached-block {
        +.attached-block {
          margin-top: 1.5rem;
        }

        >a {
          font-family: var(--font);
          font-weight: 500;
          color: var(--primary);
          margin-bottom: 0.5rem;
        }

        .block-stats {
          display: flex;
          color: var(--light-text);

          +.block-stats {
            margin-top: 0.25rem;
          }

          span {
            font-family: var(--font);
            display: flex;
            align-items: center;
            margin-right: 0.75rem;

            svg {
              height: 15px;
              width: 15px;
              stroke-width: 1px;
              margin-right: 0.25rem;
            }
          }
        }
      }

      .action-block {
        margin-top: 2.5rem;

        .button {
          height: 46px;
          border-radius: 0.75rem;
        }
      }
    }
  }

  .tile-grid {
    .tile-grid-item {
      margin-bottom: 1rem;
    }
  }

  .flex-table {
    .flex-table-item {
      border-radius: 1rem;
      padding: 1rem;
      margin-bottom: 1rem;

      .tag {
        height: 2.75em;
        padding-left: 1rem;
        padding-right: 1rem;
        border-radius: 0.5rem;
        line-height: 2.7;
      }

      .action-button {
        border-radius: 0.75rem;

        &:hover,
        &:focus {
          background: var(--primary);
          border-color: var(--primary);
          color: var(--white);
          box-shadow: var(--primary-box-shadow);
        }
      }
    }
  }
}

.p-menu {
  width: 100%;
}

.is-navbar {
  .personal-dashboard {
    margin-top: 30px;
  }
}

.personal-dashboard-v1 {
  .dashboard-header {
    display: flex;
    align-items: center;
    font-family: var(--font);
    margin-bottom: 30px;

    .start {
      margin-left: 12px;

      h3 {
        font-family: var(--font-alt);
        font-weight: 600;
        font-size: 1.3rem;
        color: var(--dark-text);
      }
    }

    .end {
      margin-left: auto;
      display: flex;
      justify-content: flex-end;

      .button {
        margin-left: 10px;
      }
    }
  }

  .dashboard-body {
    .dashboard-card {
      @include vuero-s-card;

      font-family: var(--font);

      >h4,
      .ApexCharts-title-text {
        font-family: var(--font-alt);
        font-size: 1rem;
        font-weight: 600;
        color: var(--dark-text);
        margin-bottom: 12px;
      }

      .quick-stats {
        .quick-stats-inner {
          display: flex;
          flex-wrap: wrap;
          margin-left: -8px;
          margin-right: -8px;

          .quick-stat {
            width: calc(50% - 16px);
            padding: 40px 20px;
            background: var(--widget-grey);
            margin: 8px;
            border-radius: var(--radius-large);
            transition: all 0.3s; // transition-all test

            ::v-deep(.media-flex-center) {
              .flex-meta {
                span {
                  &:first-child {
                    font-size: 1.4rem;
                    font-weight: 600;
                    color: var(--dark-text);
                  }
                }
              }
            }
          }
        }
      }
    }

    .dashboard-card {
      &.is-upgrade {
        position: relative;
        display: flex;
        justify-content: center;
        align-items: center;
        background: var(--primary-light-8);
        border-color: var(--primary-light-8);
        padding: 20px 40px;
        min-height: 320px;
        border-radius: var(--radius-large);
        box-shadow: var(--primary-box-shadow);

        .lnil,
        .lnir {
          position: absolute;
          bottom: 1rem;
          right: 1rem;
          font-size: 4rem;
          opacity: 0.3;
        }

        .cta-content {
          text-align: center;

          h4 {
            font-family: var(--font-alt);
            font-weight: 600;
            font-size: 1.2rem;
            color: var(--smoke-white);
            margin-bottom: 8px;
          }
        }

        .link {
          display: block;
          font-family: var(--font-alt);
          font-weight: 500;
          margin-top: 0.5rem;

          &:hover,
          &:focus {
            color: var(--smoke-white);
            opacity: 0.6;
          }
        }
      }

      &.is-gauge {
        position: relative;

        .people {
          position: absolute;
          top: 80px;
          left: 0;
          right: 0;
          margin: 0 auto;
          display: flex;
          justify-content: center;
          z-index: 5;

          .v-avatar {
            margin: 0 4px;
          }
        }
      }
    }
  }
}

.is-dark {
  .personal-dashboard-v1 {
    .dashboard-header {
      .start {
        h3 {
          color: var(--dark-dark-text);
        }
      }
    }

    .dashboard-body {
      .dashboard-card {
        @include vuero-card--dark;

        &.is-upgrade {
          background: var(--primary);
          border-color: var(--primary);
          box-shadow: var(--primary-box-shadow);
        }

        .quick-stats {
          .quick-stats-inner {
            .quick-stat {
              background: var(--dark-sidebar-light-2);
              border: 1px solid var(--dark-sidebar-light-12);

              .media-flex-center {
                .flex-meta {
                  span {
                    &:first-child {
                      color: var(--dark-dark-text);
                    }
                  }
                }
              }
            }
          }
        }
      }
    }
  }
}

@media only screen and (max-width: 767px) {
  .personal-dashboard-v1 {
    .dashboard-header {
      text-align: center;
      flex-direction: column;

      .start {
        margin: 10px auto;
      }

      .end {
        margin: 0;
        justify-content: space-between;
      }
    }

    .dashboard-body {
      .dashboard-card {
        .quick-stats {
          .quick-stats-inner {
            .quick-stat {
              padding: 20px;

              .media-flex-center {
                flex-direction: column;

                .flex-meta {
                  margin: 10px 0;
                  text-align: center;
                }
              }
            }
          }
        }
      }
    }
  }
}

@media only screen and (min-width: 768px) and (max-width: 1024px) and (orientation: landscape) {
  .personal-dashboard-v1 {
    .dashboard-body {
      .dashboard-card {
        .quick-stats {
          .quick-stats-inner {
            .quick-stat {
              padding: 20px;

              .media-flex-center {
                flex-direction: column;

                .flex-meta {
                  margin: 2px 0 0;
                  text-align: center;
                }
              }
            }
          }
        }
      }
    }
  }
}
</style>
