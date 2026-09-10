<template>
  <div class="columns is-multiline">
    <div class="column is-12">
      <div class="py-4">
        <h1 style="font-weight:bold">Mapping Dokter BPJS</h1>
      </div>
      <div class="column is-12">
        <VCard>
          <div class="columns is-multiline p-1">
            <div class="column is-6">
              <VField class="is-rounded-select is-autocomplete-select" label="Dokter BPJS">
                <VControl icon="feather:search" class="prime-auto-select" :loading="isLoadingSelect">
                  <Dropdown v-model="input.dokterBpjs" :options="d_DokterBPJS" :optionLabel="'label'"
                    placeholder="Pilih data" style="width: 100%;" showClear :filter="true" />
                </VControl>
              </VField>
            </div>
            <div class="column is-6">
              <VField class="is-rounded-select is-autocomplete-select" label="Dokter RS">
                <VControl icon="feather:search" class="prime-auto-select">
                  <AutoComplete v-model="input.dokterRs" :suggestions="d_Dokter" @complete="fetchDokter($event)"
                    :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                    :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="ketik untuk mencari..." />
                </VControl>
              </VField>
            </div>
            <div class="column is-4">
              <div class="columns is-multiline p-1">
                <div class="column is-4">
                  <VButton type="button" color="success" class="searcv-button" raised icon="fas fa-save" @click="simpan()"
                    :loading="isLoadingSave">Simpan
                  </VButton>
                </div>
                <div class="column is-4">
                  <VButton type="button" color="danger" class="searcv-button" raised icon="fas fa-times-circle"
                    @click="clear()">Batal
                  </VButton>
                </div>
                <div class="column is-4">
                  <VButton type="button" color="info" class="searcv-button" raised icon="fas fa-search"
                    @click="fetchData()" :loading="isLoadingSearch">Cari
                  </VButton>
                </div>
              </div>
            </div>
          </div>
        </VCard>
      </div>
      <div class="column is-12 px-4">
        <VCard>
          <div class="flex-list-inner mb-2 mt-5" v-if="isLoading">
            <div class="flex-table-item grid-item mb-1" v-for="key in 5" :key="key">
              <VPlaceloadWrap>
                <VPlaceloadAvatar size="small" />

                <VPlaceloadText last-line-width="60%" class="mx-2" />
                <VPlaceload class="mx-2" disabled />
                <VPlaceload class="mx-2 h-hidden-tablet-p" />
                <VPlaceload class="mx-2 h-hidden-tablet-p" />
                <VPlaceload class="mx-2" />
              </VPlaceloadWrap>
            </div>
          </div>
          <div class="flex-list-inner" v-else-if="dataSourcefiltered.data == 0">
            <VPlaceholderSection :title="H.assets().notFound" :subtitle="H.assets().notFoundSubtitle" class="my-6">
              <template #image>
                <img class="light-image" :src="H.assets().iconNotFound_rev" alt="" />
                <img class="dark-image" src="/@src/assets/illustrations/placeholders/search-4-dark.svg" alt="" />
              </template>
            </VPlaceholderSection>
          </div>
          <div v-else>
            <VFlexTable :data="dataSourcefiltered.data" :columns="columns" rounded>
              <template #body>
                <div name="list" tag="div" class="flex-list-inner">
                  <div v-for="(item, index)  in dataSourcefiltered.data" :key="item.id" class="flex-table-item">
                    <VFlexTableCell>
                      <span class="light-text">{{ index + 1 }}</span>
                    </VFlexTableCell>
                    <VFlexTableCell>
                      <span class="light-text">{{ item.id }}</span>
                    </VFlexTableCell>
                    <VFlexTableCell>
                      <span class="light-text">{{ item.kddokterbpjs }}</span>
                    </VFlexTableCell>
                    <VFlexTableCell :column="{ align: 'end' }">
                      <span class="light-text">{{ item.namalengkap }}</span>
                    </VFlexTableCell>
                    <VFlexTableCell :column="{ align: 'end' }">
                      <VDropdown icon="feather:more-vertical" spaced right class="is-pulled-right">
                        <template #content>
                          <a role="menuitem" class="dropdown-item is-media" @click="deleteDokter(item)">
                            <div class="icon">
                              <i class="lnil lnil-trash" aria-hidden="true"></i>
                            </div>
                            <div class="meta">
                              <span>Hapus</span>
                            </div>
                          </a>
                          <a role="menuitem" class="dropdown-item is-media" @click="editData(item)">
                            <div class="icon">
                              <i class="lnil lnil-pencil" aria-hidden="true"></i>
                            </div>
                            <div class="meta">
                              <span>Ubah</span>
                            </div>
                          </a>
                        </template>
                      </VDropdown>
                    </VFlexTableCell>
                  </div>
                </div>
              </template>
            </VFlexTable>
          </div>
        </VCard>
        <VFlexPagination v-model:current-page="currentPage.page" :item-per-page="currentPage.limit"
          :total-items="dataSourcefiltered.total" :max-links-displayed="5">
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
                      <option :value="1">1 results per page</option>
                      <option :value="5">5 results per page</option>
                      <option :value="10">10 results per page</option>
                      <option :value="15">15 results per page</option>
                      <option :value="25">25 results per page</option>
                      <option :value="50">50 results per page</option>
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
</template>
<script setup lang="ts">
import { useRoute, useRouter } from 'vue-router'
import { ref, computed, watch, reactive } from 'vue'
import { useThemeColors } from '/@src/composable/useThemeColors'
import { onceImageErrored } from '/@src/utils/via-placeholder'
import { useToaster } from '/@src/composable/toaster'
import * as H from '/@src/utils/appHelper'
import { useApi } from '/@src/composable/useApi'
import { useUserSession } from '/@src/stores/userSession'
import { useHead } from '@vueuse/head'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import AutoComplete from 'primevue/autocomplete';
import Dropdown from 'primevue/dropdown';
const setView = () => {
  useHead({
    title: 'Dokter - ' + import.meta.env.VITE_PROJECT,
  })
  useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
  useViewWrapper().setFullWidth(true)
}
const isLoadingCall = ref(false)
const input: any = ref({})
let item: any = reactive({
  pencarian: 'nobpjs'
})
const dataSourcefiltered: any = ref([]);
const columns: any = ref({})
const d_DokterBPJS: any = ref([])
const d_Dokter: any = ref([])
const route = useRoute()
const dataSource: any = ref([])
const isLoading: any = ref(false)
const isLoadingSave: any = ref(false)
columns.value = {
  no: 'No',
  tglsbm: 'Kode Dokter BPJS',
  carabayar: 'Kode Dokter RS',
  totaldibayar: 'Nama Dokter',
  actions: {
    label: 'Aksi',
    align: 'end',
  },
}
const fetchDokterBPJS = async (filter: any) => {
  var json = {
    "url": "ref/dokter",
    "jenis": "antrean",
    "method": "GET",
    "data": null
  }
  const res = await useApi().postBPJS(`/bridging/bpjs/tools`, json)
  d_DokterBPJS.value = res.response.map(res => ({
    label: res.namadokter,
    value: res.kodedokter
  }));
}
const fetchDokter = async (filter: any) => {
  await useApi().get(
    `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&settingdatafix=objectjenispegawaifk,idJenisPegawaiDokter&limit=10`
  ).then((response) => {
    d_Dokter.value = response
  })
}
const simpan = async () => {
  if (!input.value.dokterRs) {
    H.alert('warning', 'Data dokter rumah sakit harus diisi !');
    return;
  }
  if (!input.value.dokterBpjs) {
    H.alert('warning', 'Data dokter bpjs harus diisi !');
    return;
  }
  let json = {
    'idpegawai': input.value.dokterRs.value,
    'kodedokterbpjs': input.value.dokterBpjs.value
  }
  isLoadingSave.value = true;
  await useApi().post(
    `/medifirst2000/bridging/bpjs/save-data-mappingdkoterbpjs`, json
  ).then((response) => {
    isLoadingSave.value = false;
  }).catch((exception) => {
    isLoadingSave.value = false;
  })
  clear();
  fetchData();
}
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
  fetchData()
})
const fetchData = async () => {
  isLoading.value = true
  let limit: any = currentPage.value.limit
  let offset: any = route.query.page ? route.query.page : 1
  offset = (offset * limit) - limit
  let idPegawai = input.value.dokterRs ? input.value.dokterRs.value : "";
  let kodeDokterBpjs = input.value.dokterBpjs ? input.value.dokterBpjs.value : "";
  await useApi().get(
    `/medifirst2000/bridging/bpjs/get-mapping-dkoterbpjs?limit=${limit}&offset=${offset}&kodeDokterBpjs=${kodeDokterBpjs}&idPegawai=${idPegawai}`
  ).then((response) => {
    isLoading.value = false
    dataSourcefiltered.value.data = response.data
    dataSourcefiltered.value.total = response.total
  })
}
const editData = (item: any) => {
  input.value.dokterRs = { label: item.namalengkap, value: item.id };
  input.value.dokterBpjs = d_DokterBPJS.value.find(dokter => dokter.value == item.kddokterbpjs) || d_DokterBPJS.value[0];
  H.alert('info', 'Data Terpilih !');
}
const deleteDokter = async (item: any) => {
  isLoading.value = true;
  let json = {
    id: item.id
  }
  await useApi().post(
    `/medifirst2000/bridging/bpjs/delete-data-mappingdkoterbpjs`, json,
  ).then((response) => {
    isLoading.value = false;
  }).catch((exception) => {
    isLoading.value = false;
  })
  fetchData()
}
const clear = () => {
  input.value.dokterRs = null;
  input.value.dokterBpjs = null;
}
fetchDokterBPJS();
fetchData()
</script>

<style lang="scss">
.text-center {
  justify-content: center;
  tect-align: center;
}
</style>
