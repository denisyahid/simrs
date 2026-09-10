<template>
  <VCard>
    <div class="columns column">
      <h3 class="title is-5 mb-2 mr-1">Kelompok Indikator IPCN</h3>
    </div>
    <div class="columns is-multiline">
      <div class="column is-8">
        <div class="user-grid-toolbar">
          <VControl icon="feather:search">
            <input v-model="filters" @change="fetchData()" class="input custom-text-filter" placeholder="Cari Data..." />
          </VControl>
          <div class="buttons">
            <VControl class="is-pulled-right">
              <VSwitchBlock v-model="item.aktif" label="Aktif" color="danger" @change="changeSwitch(item.aktif)" />
            </VControl>
          </div>
        </div>
        <div class="tile-grid tile-grid-v1">
          <transitionGroup name="list" tag="div" class="columns is-multiline" v-if="isLoading">
            <div v-for="key in 6" :key="key" class="column is-6">
              <div class="tile-grid-item">
                <VFlexTableCell :column="{ grow: true, media: true }">
                  <VPlaceloadAvatar size="medium" width="20%" />
                  <VPlaceloadText :lines="2" width="10%" last-line-width="40%" class="mx-2" />
                </VFlexTableCell>
              </div>
            </div>
          </transitionGroup>
          <VPlaceholderPage :title="H.assets().notFound" :subtitle="H.assets().notFoundSubtitle" class="my-6"
            v-else-if="dataSource.length == 0">
            <template #image>
              <img class="light-image" :src="H.assets().iconNotFound_rev" alt="" />
              <img class="dark-image" src="/@src/assets/illustrations/placeholders/search-4-dark.svg" alt="" />
            </template>
          </VPlaceholderPage>
          <TransitionGroup name="list" tag="div" class="columns is-multiline" v-else>
            <div v-for="(item, key) in dataSource" :key="key" class="column is-6">
              <div class="tile-grid-item">
                <div class="tile-grid-item-inner">
                  <VAvatar size="medium" :initials="item.nourut" :color="listColor[key]" squared bordered />
                  <div class="meta">
                    <span class="dark-inverted">{{ item.kelompokipcn }}({{ item.kdkelompokipcn }})</span>
                  </div>
                  <VTag :color="item.statusenabled ? 'success' : 'danger'"
                    :label="item.statusenabled ? 'Aktif' : 'Non Aktif'" style="margin-left:90px" />
                  <VDropdown icon="feather:more-vertical" spaced right>

                    <template #content>
                      <a role="menuitem" class="dropdown-item is-media" @click="edit(item)">
                        <div class="icon">
                          <i class="iconify" data-icon="feather:edit" aria-hidden="true"></i>
                        </div>
                        <div class="meta">
                          <span>Edit</span>
                          <span>Untuk merubah data </span>
                        </div>
                      </a>
                      <a role="menuitem" class="dropdown-item is-media" @click="deleterow(item)">
                        <div class="icon">
                          <i aria-hidden="true" class="lnil lnil-trash-can-alt"></i>
                        </div>
                        <div class="meta">
                          <span>Remove</span>
                          <span>Hapus Data dari Daftar</span>
                        </div>
                      </a>
                    </template>
                  </VDropdown>
                </div>
              </div>
            </div>
          </TransitionGroup>
        </div>
        <VFlexPagination v-model:current-page="currentPage.page" :item-per-page="currentPage.limit"
          :total-items="dataSource.total" :max-links-displayed="5">
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
                      <option :value="6">6 results per page</option>
                      <option :value="12">12 results per page</option>
                      <option :value="15">15 results per page</option>
                      <option :value="30">30 results per page</option>
                      <option :value="60">60 results per page</option>
                      <option :value="120">100 results per page</option>
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
      <div class="column is-4">
        <VCard style="margin-top : 3rem;">
          <div class="columns is-multiline">
            <div class="column is-6">
              <h3 class="title is-6 mb-2 mr-1">
                <i class="iconify" data-icon="feather:edit" aria-hidden="true"> </i>
                Tambah Data
              </h3>
            </div>
            <div class="column is-12">
              <VField label="No Urut">
                <VControl icon="feather:bookmark">
                  <VInput type="text" v-model="item.noUrut" placeholder="No Urut..." class="is-rounded" />
                </VControl>
              </VField>
            </div>
            <div class="column is-12">
              <VField label="Kode Kelompok IPCN">
                <VControl icon="feather:bookmark">
                  <VInput type="text" v-model="item.kode" placeholder="Kode Kelompok IPCN..." class="is-rounded" />
                </VControl>
              </VField>
            </div>
            <div class="column is-12">
              <VField label="Kelompok IPCN">
                <VControl icon="feather:bookmark">
                  <VInput type="text" v-model="item.Kelompok" placeholder="Kelompok IPCN..." class="is-rounded" />
                </VControl>
              </VField>
            </div>
            <div class="column is-12 is-pulled-right" v-if="item.id">
              <VField label="Aktivasi" class="ml-4">
                <VControl>
                  <VSwitchBlock v-model="item.statusenabled" color="danger" @change="changeSwitch2(item.aktif)" />
                </VControl>
              </VField>
            </div>
            <div v-if="item.id" class="column is-9">
              <VButton @click="save()" :loading="isLoadingTT" type="button" icon="feather:edit" class="is-fullwidth mr-3"
                color="info" raised>
                Update Data
              </VButton>
              <VButton @click="clear()" type="button" icon="feather:x-circle"
                class="is-fullwidth is-outlined is-warning mt-3" raised>
                Batal Edit
              </VButton>
            </div>
            <div v-else class="column is-12">
              <VButton @click="save()" :loading="isLoadingTT" type="button" icon="feather:save" class="is-fullwidth mr-3"
                color="success" raised>
                Simpan Data
              </VButton>
            </div>
          </div>
        </VCard>
      </div>
    </div>
  </VCard>
</template>
<script  setup lang="ts">
import { useWindowScroll } from '@vueuse/core'
import { useApi } from '/@src/composable/useApi'
import { ref, computed, watch, reactive } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import DataTable from 'primevue/datatable'
import Column from 'primevue/column'
import { useHead } from '@vueuse/head'
import { useThemeColors } from '/@src/composable/useThemeColors'
import { useToaster } from '/@src/composable/toaster'
import moment from 'moment'
import * as H from '/@src/utils/appHelper'
useHead({
  title: 'Kelompok Indikator IPCN - ' + import.meta.env.VITE_PROJECT,
})
const item: any = reactive({
  aktif: true
})
const input: any = ref({
  aktif: true
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
let listColor: any = ref(Object.keys(useThemeColors()))
const selectView: any = ref()
selectView.value = 'list'
function changeView(e: any) {
  selectView.value = e
}
const router = useRouter()
const route = useRoute()
const isLoading: any = ref(false)
const isLoadingTT: any = ref(false)
const dataSource: any = ref([])
const filters = ref('')
const currentPage: any = ref({
  limit: 6,
  rows: 50,
})
const clear = () => {
  item.norec = '';
  item.kode = '';
  item.Kelompok = '';
  item.aktif = true;
  item.noUrut = '';
  item.id = ''
}
const fetchData = async () => {
  let search = filters.value ?? ''
  isLoading.value = true
  let status = item.aktif ?? ''
  let limit: any = currentPage.value.limit
  let offset: any = route.query.page ? route.query.page : 1
  offset = (offset * limit) - limit
  const response = await useApi().get(
    `/sysadmin/kelompok-ipcn?limit=${limit}&offset=${offset}&search=${search}&status=${status}`
  )
  for (let x = 0; x < response.data.length; x++) {
    const element = response.data[x];
    element.no = x + 1
  }
  dataSource.value = response.data
  dataSource.value.total = response.total
  isLoading.value = false
  route.query.page = '1'
}
const changeSwitch = (data: any) => {
  fetchData();
}
const changeSwitch2 = (data: any) => {
}
const edit = async (data: any) => {
  item.id = data.id
  item.norec = data.norec
  item.kode = data.kdkelompokipcn
  item.Kelompok = data.kelompokipcn
  item.noUrut = data.nourut
  item.statusenabled = data.statusenabled

}
const save = async () => {
  if (!item.Kelompok) {
    H.alert('warning', 'Kelompok  Harus Disis !');
    return;
  }
  isLoadingTT.value = true
  let json = {
    'id': item.id ?? '',
    'nourut': item.noUrut,
    'kode': item.kode,
    'kelompok': item.Kelompok,
    ...(item.id && { 'statusenabled': item.statusenabled })
  }
  await useApi().post(`/sysadmin/save-kelompok-ipcn`, json).then((response: any) => {
    fetchData()
    clear()
  })
  isLoadingTT.value = false
}

const deleterow = async (data: any) => {
  let json = {
    'id': data.id
  }
  useApi().post(`/sysadmin/delete-kelompok-ipcn`, json).then((response: any) => {
    fetchData()
  })
}
currentPage.value.page = computed(() => {
  try {
    return Number.parseInt(route.query.page as string) || 1
  } catch { }
  return 1
})
watch(currentPage.value, () => {
  fetchData();
})
fetchData();
</script>
<style lang="scss">
@import '/@src/scss/abstracts/all';

@import '/@src/scss/components/forms-outer';

.tile-grid {
  .columns {
    margin-left: -0.5rem !important;
    margin-right: -0.5rem !important;
    margin-top: -0.5rem !important;
  }

  .column {
    padding: 0.5rem !important;
  }
}

.is-dark {
  .tile-grid {
    .tile-grid-item {
      @include vuero-card--dark;
    }
  }
}

.tile-grid-v1 {
  .tile-grid-item {
    @include vuero-s-card;

    border-radius: 14px;
    padding: 16px;

    .tile-grid-item-inner {
      display: flex;
      align-items: center;

      .meta {
        margin-left: 10px;
        line-height: 1.2;

        span {
          display: block;
          font-family: var(--font);

          &:first-child {
            color: var(--dark-text);
            font-family: var(--font-alt);
            font-weight: 600;
            font-size: 0.9rem;
          }

          &:nth-child(2) {
            color: var(--light-text);
            font-size: 0.9rem;
          }
        }
      }

      .dropdown {
        position: relative;
        margin-left: auto;
      }
    }
  }
}
</style>
