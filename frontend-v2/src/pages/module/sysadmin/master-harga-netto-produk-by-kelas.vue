<template>
  <VCard>
    <div class="columns column">
      <h3 class="title is-5 mb-2 mr-1">Harga Netto Produk By Kelas</h3>
    </div>

    <div class="columns is-multiline">
      <div class="column is-9">
        <div class="columns is-multiline">
          <div class="column is-3">
            <VField v-slot="{ id }" class="is-icon-select">
              <VControl>
                <Multiselect v-model="selectView" :attrs="{ id }" placeholder="Select View" label="name" :options="d_View"
                  :searchable="true" track-by="name" mode="single" @select="changeView(selectView)" autocomplete="off">
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
          <div class="column is-2  is-offset-5">
            <VButton type="button" class="is-pulled-right w-100" color="warning" outlined raised>
              <RouterLink :to="{ name: 'module-sysadmin-harga-netto-produk-import' }">
                <i class="fas fa-file-import" aria-hidden="true"></i> Import
              </RouterLink>
            </VButton>
          </div>
          <div class="column is-2">
            <VButton type="button" class="is-pulled-right w-100" color="info" outlined raised>
              <RouterLink :to="{ name: 'module-sysadmin-harga-netto-produk' }">
                <i class="fa fa-plus"></i> Tambah
              </RouterLink>
            </VButton>
          </div>
        </div>

        <div class="user-grid user-grid-v2" v-if="selectView == 'list'">
          <DataTable :value="dataSourcefiltered" lazy :paginator="true" :rows="10" :rowsPerPageOptions="[5, 10, 25]"
            paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
            responsiveLayout="stack" breakpoint="960px" sortMode="multiple" :loading="isLoading"
            currentPageReportTemplate="Showing {first} to {last} of {totalRecords}" @page="onPage($event)" ref="dt"
            :totalRecords="totalRecords" dataKey="id">

            <Column field="no" header="No"></Column>
            <Column field="namaproduk" header="Nama Produk" :sortable="true"></Column>
            <!-- <Column field="asalproduk" header="Asal Produk" :sortable="true"></Column> -->
            <!-- <Column field="jenistarif" header="Jenis Tarif" :sortable="true"></Column> -->
            <Column field="namakelas" header="Kelas" :sortable="true"></Column>
            <!-- <Column field="matauang" header="Mata Uang"></Column> -->
            <Column field="hargasatuan" header="Harga">
              <template #body="slotProps">
                {{ formatRp(slotProps.data.hargasatuan, '') }}
              </template>
            </Column>
            <Column field="jenispelayanan" header="Jenis"></Column>
            <Column field="penjamin" header="Penjamin"></Column>
            <Column :exportable="false" header="#">
              <template #body="slotProps">
                <VIconButton type="button" icon="pi pi-pencil" class="mr-2" color="info" circle outlined raised
                  v-tooltip.top="'Edit'" @click="editTo(slotProps.data)">
                </VIconButton>
                <VIconButton type="button" icon="fas fa-trash" color="danger" circle outlined raised
                  v-tooltip.top="'Hapus'" @click="deleterow(slotProps.data)">
                </VIconButton>

              </template>
            </Column>
          </DataTable>
        </div>
        <div class="tile-grid tile-grid-v1" v-else-if="selectView == 'grid'">

          <div class="page-placeholder" v-if="dataSource.length == 0 && !isLoading">
            <div class="placeholder-content">
              <img class="light-image" style=" max-width: 340px;" :src="H.assets().iconNotFound_rev" alt="" />
              <img class="dark-image" style=" max-width: 340px;" :src="H.assets().iconNotFound_rev" alt="" />
              <h3>{{ H.assets().notFound }}</h3>
              <p class="is-larger">
                {{ H.assets().notFoundSubtitle }}
              </p>
            </div>
          </div>
          <div class="tile-grid tile-grid-v1" v-if="isLoading">
            <div class="columns is-multiline">
              <div v-for="key in 6" :key="key" class="column is-6">
                <div class="tile-grid-item">
                  <div class="tile-grid-item-inner placeload-wrap is-flex">
                    <VPlaceloadAvatar size="medium" />
                    <VPlaceloadText width="100%" class="mx-2" lines="5" heigth="30px" />
                  </div>
                </div>
              </div>
            </div>
          </div>
          <TransitionGroup name="list" tag="div">

            <div class="columns is-multiline">
              <div v-for="(item, key) in dataSourcefiltered" :key="key" class="column is-6" v-if="!isLoading">
                <div class="tile-grid-item">
                  <div class="tile-grid-item-inner">
                    <VAvatar size="medium" picture="/images/avatars/svg/product.svg" color="primary" squared bordered />
                    <div class="meta" style="width:80%">
                      <span class="dark-inverted">{{ item.namaproduk }} </span>

                      <table class="w-100">
                        <caption style="display: none"></caption>
                        <tr style="display: none">
                          <th scope="col"></th>
                        </tr>
                        <tr>
                          <td class="title-table" style="width: 50%;">Kelas</td>
                          <td class="title-table">:</td>
                          <td class="title-table-val"> {{ item.namakelas }}</td>
                        </tr>
                        <tr>
                          <td class="title-table">Jenis Pelayanan </td>
                          <td class="title-table">:</td>
                          <td class="title-table-val"> {{ item.jenispelayanan }}</td>
                        </tr>
                        <tr>
                          <td class="title-table">Penjamin </td>
                          <td class="title-table">:</td>
                          <td class="title-table-val"> {{ item.penjamin }}</td>
                        </tr>
                        <tr>
                          <td class="title-table">Harga Satuan </td>
                          <td class="title-table">:</td>
                          <td class="title-table-val"> {{ formatRp(item.hargasatuan, '') }}</td>
                        </tr>
                        <tr>
                          <td class="title-table">
                            <VTag  :color="item.reportdisplay == 'IMPORT_EXCEL' ? 'danger' : 'success'"
                              :label="item.reportdisplay? item.reportdisplay:'-'"></VTag>
                          </td>
                        </tr>
                      </table>

                      <!--
                        <span>Kelas : {{ item.namakelas }}</span>
                         <span>Harga Satuan : {{ formatRp(item.hargasatuan, '') }}</span>
                    <span>Jenis Pelayanan : {{ formatRp(item.hargasatuan, '') }}</span>
                    <span>Penjamin : {{ formatRp(item.hargasatuan, '') }}</span> -->
                    </div>

                    <VDropdown icon="feather:more-vertical" spaced right>
                      <template #content>
                        <a role="menuitem" class="dropdown-item is-media" @click="detail(item)">
                          <div class="icon">
                            <i class="iconify" data-icon="feather:bookmark" aria-hidden="true"></i>
                          </div>
                          <div class="meta">
                            <span>Detail</span>
                            <span>Untuk melihat data </span>
                          </div>
                        </a>
                        <a role="menuitem" class="dropdown-item is-media" @click="editTo(item)">
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
            </div>
          </TransitionGroup>
          <VFlexPagination v-model:current-page="currentPage.page" :item-per-page="currentPage.limit"
            :total-items="dataSource.total < 5 ? dataSource.total : 50" :max-links-displayed="5">
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
                        <option :value="6">6 results per page</option>
                        <option :value="10">10 results per page</option>
                        <option :value="15">15 results per page</option>
                        <option :value="25">25 results per page</option>
                        <option :value="50">50 results per page</option>
                        <option :value="100">100 results per page</option>
                      </select>
                    </div>
                  </VControl>
                </VField>
              </VFlex>
            </template>
          </VFlexPagination>
          <div class="dataTable-bottom mt-2">
            <div class="dataTable-info">Menampilkan {{ dataSource.length }} ke {{ currentPage.limit }} dari
              {{ dataSource.total }} entri data
            </div>
          </div>
        </div>
      </div>
      <div class="column is-3">
        <div class="columns is-multiline">
          <div class="column is-12">
            <VControl icon="feather:search">
              <input v-model="filters" class="input custom-text-filter" placeholder="Search..." />
            </VControl>
          </div>
          <div class="column is-6">
            <h3 class="title is-5 mb-2 mr-1">Filters</h3>
          </div>
          <div class="column is-6">
            <a @click="clearFilter()" type="button" class="is-pulled-right mr-3" color="info" outlined raised>
              Clear All
            </a>
          </div>
          <div class="column is-12">
            <VField class="is-autocomplete-select">
              <VLabel>Kelas</VLabel>
              <VControl icon="feather:search">
                <Multiselect mode="single" v-model="item.namakelas" :options="d_Kelas" placeholder="Kelas"
                  :searchable="true" />
              </VControl>
            </VField>
          </div>
          <div class="column is-12">
            <VField class="is-autocomplete-select">
              <VLabel>Jenis Pelayanan</VLabel>
              <VControl icon="feather:search">
                <Multiselect mode="single" v-model="item.jenispelayanan" :options="d_JenisPelayanan"
                  placeholder="Jenis Pelayanan" :searchable="true" />
              </VControl>
            </VField>
          </div>
          <div class="column is-12">
            <VField class="is-autocomplete-select">
              <VLabel>Penjamin</VLabel>
              <VControl icon="feather:search">
                <Multiselect mode="single" v-model="item.penjamin" :options="d_Rekanan" placeholder="Penjamin"
                  :searchable="true" />
              </VControl>
            </VField>
          </div>
          <div class="column is-12">
            <VButton @click="filter()" :loading="isLoading" type="button" icon="feather:search" class="is-fullwidth mr-3"
              color="info" raised>
              Terapkan Filter
            </VButton>
          </div>
        </div>
      </div>
    </div>

    <VModal :open="modalDetail" title="Detail Harga Netto Produk By Kelas" size="medium" actions="right"
      @close="modalDetail = false">
      <template #content>
        <div class="columns is-multiline">
          <div class="column is-12">
            <div class="inner-list-item media-flex-center">
              <VIconBox rounded :color="item.detail.length > 0 ? 'success' : 'danger'">
                <i aria-hidden="true" class="iconify" :data-icon="'feather:info'"></i>
              </VIconBox>
              <div class="flex-meta is-light">
                <a href="#">{{ item.head.namaproduk }}</a>
              </div>
              <div class="flex-end">

              </div>
            </div>

          </div>
          <div class="column is-12">
            <table class="w-100">
              <caption style="display: none"></caption>
              <tr style="display: none">
                <th scope="col"></th>
              </tr>
              <tr>
                <td class="title-table" style="width: 25%;">Kelas</td>
                <td class="title-table">:</td>
                <td class="title-table-val"> {{ item.head.namakelas }}</td>

                <td class="title-table" style="width: 25%;">Jenis Tarif</td>
                <td class="title-table">:</td>
                <td class="title-table-val"> {{ item.head.jenistarif }}</td>
              </tr>
              <tr>
                <td class="title-table">Jenis Pelayanan </td>
                <td class="title-table">:</td>
                <td class="title-table-val"> {{ item.head.jenispelayanan }}</td>

                <td class="title-table" style="width: 25%;">Mata Uang</td>
                <td class="title-table">:</td>
                <td class="title-table-val"> {{ item.head.matauang }}</td>
              </tr>
              <tr>
                <td class="title-table">Penjamin </td>
                <td class="title-table">:</td>
                <td class="title-table-val"> {{ item.head.namarekanan }}</td>

                <td class="title-table" style="width: 25%;">Asal Produk</td>
                <td class="title-table">:</td>
                <td class="title-table-val"> {{ item.head.asalproduk }}</td>
              </tr>
              <tr>
                <td class="title-table">Surat Keputusan </td>
                <td class="title-table">:</td>
                <td class="title-table-val"> {{ item.head.namask }}</td>

                <td class="title-table" style="width: 25%;">Tgl Kadaluarsa</td>
                <td class="title-table">:</td>
                <td class="title-table-val"> {{ item.head.tglkadaluarsa }}</td>
              </tr>
              <tr>
                <td class="title-table">Harga Satuan </td>
                <td class="title-table">:</td>
                <td class="title-table-val"> {{ formatRp(item.head.hargasatuan, '') }}</td>

                <td class="title-table">Harga Discount </td>
                <td class="title-table">:</td>
                <td class="title-table-val"> {{ formatRp(item.head.hargadiscount, '') }}</td>

              </tr>
            </table>
          </div>
        </div>

        <div class="columns is-multiline">
          <div class="column is-4" v-for="items in item.detail" :key="items.id">
            <TStatusPojokKanan :title="items.komponenharga" :subtitle="formatRp(items.hargasatuan, '')"
              class="inbox-widget-2" />
          </div>
          <div class="column is-12" v-if="item.detail.length == 0">
            <div class="page-placeholder" style="min-height:100px !important">
              <div class="placeholder-content">
                <img class="light-image" style=" max-width: 60px;" :src="H.assets().iconNotFound_rev" alt="" />
                <img class="dark-image" style=" max-width: 60px;" :src="H.assets().iconNotFound_rev" alt="" />
                <h3>Komponen Harga Tidak Ada</h3>

              </div>
            </div>
          </div>
        </div>
      </template>
    </VModal>
  </VCard>
</template>
    
<script  setup lang="ts">
import * as H from '/@src/utils/appHelper'
import { useApi } from '/@src/composable/useApi'
import { formatRp } from '/@src/utils/appHelper'
import { ref, computed, watch, reactive, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import DataTable from 'primevue/datatable'
import Column from 'primevue/column'
import { useHead } from '@vueuse/head'
import { useToaster } from '/@src/composable/toaster'
import TStatusPojokKanan from '../emr/profile-pasien/t-status-pojok-kanan.vue'

useHead({
  title: 'Transmedic - Harga Netto Produk By Kelas',
})
onMounted(() => {
  isLoading.value = true;

  lazyParams.value = {
    first: 0,
    rows: dt.value != undefined ? dt.value.rows : 10,
    sortField: null,
    sortOrder: null,
    filters: filters.value
  };

  fetchData();
});
const item: any = reactive({
  aktif: true,
  head: {},
  detail: []
})
const router = useRouter()
const modalInput = ref(false)
const modalDetail = ref(false)
const d_Kelas = ref([])
const lazyParams: any = ref({});
const dt = ref();
const totalRecords = ref(0);
let dataSource: any = ref([])
let isRegistrasi = ref(false)
const d_View = ref([
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
])
const selectView: any = ref()
selectView.value = 'grid'
let isLoading: any = ref(false)
let isLoadingTT: any = ref(false)
let d_JenisTarif: any = ref([])
let d_MataUang: any = ref([])
let d_JenisPelayanan: any = ref([])
let d_Produk: any = ref([])
let d_SuratKeputusan: any = ref([])
let d_AsalProduk: any = ref([])
let d_KomponenHarga: any = ref([])
let d_Rekanan: any = ref([])
const currentPage: any = ref({
  limit: 6,
  rows: 50,
})
const filters = ref('')
const dataSourcefiltered = computed(() => {
  // if (!filters.value) {
  return dataSource.value
  // }

  // return dataSource.value.filter((items: any) => {
  //   return (
  //     items.namaproduk.match(new RegExp(filters.value, 'i'))
  //   )
  // })
})

const route = useRoute()
isLoading.value = false
currentPage.value.page = computed(() => {
  try {
    return Number.parseInt(route.query.page as string) || 1
  } catch { }
  return 1
})
async function fetchData() {
  isLoading.value = true
  let limit: any = currentPage.value.limit
  let offset: any = route.query.page ? route.query.page : 1
  offset = offset * limit - limit
  let rows: any = currentPage.value.rows
  let NamaProduk = ''
  let AsalProduk = ''
  let JenisTarif = ''
  let NamaKelas = ''
  let MataUang = ''
  let HargaSatuan = ''
  let JenisPelayanan = ''
  let penjamin = ''

  if (filters.value) NamaProduk = '&namaproduk=' + filters.value
  if (item.asalproduk) AsalProduk = '&asalproduk=' + item.asalproduk
  if (item.jenistarif) JenisTarif = '&jenistarif=' + item.jenistarif
  if (item.namakelas) NamaKelas = '&kelasfk=' + item.namakelas
  if (item.matauang) MataUang = '&matauang=' + item.matauang
  if (item.hargasatuan) HargaSatuan = '&hargasatuan=' + item.hargasatuan
  if (item.jenispelayanan) JenisPelayanan = '&jenispelayanan=' + item.jenispelayanan
  if (item.penjamin) penjamin = '&penjamin=' + item.penjamin
  if (selectView.value == 'list') {
    if (lazyParams.value) {
      rows = lazyParams.value.rows
      offset = lazyParams.value.page
      limit = lazyParams.value.rows
    }
  }
  const response = await useApi().get(
    '/sysadmin/master-harga-netto-produk-by-kelas?offset=' + offset +
    '&limit=' + limit +
    '&rows=' + rows +
    '&_total=true' +
    NamaProduk + AsalProduk + JenisTarif +
    NamaKelas + MataUang + HargaSatuan + JenisPelayanan + penjamin
  )
  isLoading.value = false
  for (let x = 0; x < response.data.length; x++) {
    const element = response.data[x];
    element.no = x + 1
  }

  dataSource.value = response.data
  dataSource.value.total = response.total
  totalRecords.value = response.total;
}

const loadDropdown = () => {
  d_Kelas.value = []
  useApi().get(
    `/sysadmin/master-harga-netto-produk-by-kelas-dropdown-import`).then((response: any) => {
      d_Kelas.value = response.kelas.map((e: any) => { return { label: e.namakelas, value: e.id } })
      d_JenisPelayanan.value = response.jenispelayanan.map((e: any) => { return { label: e.jenispelayanan, value: e.id } })
      d_Rekanan.value = response.namarekanan.map((e: any) => { return { label: e.namarekanan, value: e.id } })
    })
}

// function loadData() {
//   dataSource.value = []
//   useApi().get(
//     `/sysadmin/master-harga-netto-produk-by-kelas?statusenabled=${item.aktif}`).then((response: any) => {
//       dataSource.value = response.data
//     })
// }


const add = () => {
  modalInput.value = true
}

const detail = async (e: any) => {

  isLoadingTT.value = true
  const data = await useApi().get(`/sysadmin/master-harga-netto-produk-by-kelas/${e.id}`)
  isLoadingTT.value = false
  item.head = data.head
  item.detail = data.detail
  modalDetail.value = true
}

const editTo = (e: any) => {
  router.push({
    name: 'module-sysadmin-harga-netto-produk',
    query: {
      id: e.id,
    },
  })
}

const deleterow = async (e: any) => {
  await useApi().post(
    `/sysadmin/delete-harga-netto-produk-by-kelas`, { 'id': e.id }).then((response: any) => {
      fetchData()
    }, (error) => {
    })
}


const changeView = (e: any) => {
  selectView.value = e
}
const filter = () => {
  fetchData()
}
const clearFilter = () => {
  fetchData()
}
const onPage = (event: any) => {
  lazyParams.value = event;
  fetchData();
};


watch(currentPage.value, () => {
  fetchData()
})
loadDropdown()
fetchData()
</script>
<style lang="scss">
@import '/@src/scss/abstracts/all';

@import '/@src/scss/components/forms-outer';

.title-table {
  color: var(--light-text);
  font-size: 0.9rem;
}

.title-table-val {

  font-size: 0.9rem;
}

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
        line-height: 1.7;

        span {
          display: block;
          font-family: var(--font);

          &:first-child {
            color: var(--dark-text);
            font-family: var(--font-alt);
            font-weight: 600;
            font-size: 1rem;
          }

          &:nth-child(2) {
            color: var(--light-text);
            font-size: 0.5 rem;
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

.fs-075 {
  font-size: 0.9rem;
}

.is-navbar {
  .form-layout {
    margin-top: 30px;
  }
}

.form-layout {
  // max-width: 740px;
  margin: 0 auto;

  &.is-separate {
    // max-width: 1040px;

    .form-outer {
      background: none;
      border: none;

      .form-body {
        display: flex;

        .form-section {
          flex-grow: 2;
          padding: 10px;
          width: 50%;

          .form-section-inner {
            @include vuero-s-card;

            padding: 40px;

            &.has-padding-bottom {
              padding-bottom: 60px;
              height: 100%;
            }

            >h3 {
              font-family: var(--font-alt);
              font-size: 1.2rem;
              font-weight: 600;
              color: var(--dark-text);
              margin-bottom: 30px;
            }

            .columns {
              .column {
                padding-top: 0.25rem;
                padding-bottom: 0.25rem;
              }
            }

            .radio-boxes {
              display: flex;
              justify-content: space-between;
              margin-left: -8px;
              margin-right: -8px;

              .radio-box {
                position: relative;
                width: calc(50% - 16px);
                margin: 8px;

                &:focus-within {
                  border-radius: 3px;
                  outline-offset: var(--accessibility-focus-outline-offset);
                  outline-width: var(--accessibility-focus-outline-width);
                  outline-style: var(--accessibility-focus-outline-style);
                  outline-color: var(--primary);
                }

                input {
                  position: absolute;
                  top: 0;
                  left: 0;
                  height: 100%;
                  width: 100%;
                  opacity: 0;
                  cursor: pointer;

                  &:checked {
                    +.radio-box-inner {
                      background: var(--primary);
                      border-color: var(--primary);
                      box-shadow: var(--primary-box-shadow);

                      .fee,
                      p {
                        color: var(--smoke-white);
                      }
                    }
                  }
                }

                .radio-box-inner {
                  background: var(--white);
                  border: 1px solid var(--fade-grey-dark-3);
                  text-align: center;
                  border-radius: var(--radius);
                  font-family: var(--font);
                  font-weight: 600;
                  font-size: 0.9rem;
                  transition: color 0.3s, background-color 0.3s, border-color 0.3s,
                    height 0.3s, width 0.3s;
                  padding: 30px 20px;

                  .fee {
                    font-family: var(--font);
                    font-weight: 700;
                    color: var(--dark-text);
                    font-size: 2.4rem;
                    line-height: 1;

                    span {
                      &::after {
                        content: '$';
                        position: relative;
                        top: -10px;
                        font-size: 1.5rem;
                      }
                    }
                  }

                  p {
                    font-family: var(--font-alt);
                  }
                }
              }
            }

            .control {
              >p {
                padding-top: 12px;

                >span {
                  display: block;
                  font-size: 0.9rem;

                  span {
                    font-weight: 500;
                    color: var(--dark-text);
                  }
                }
              }
            }
          }

          .form-section-outer {
            .checkboxes {
              padding: 16px 0;

              .checkbox {
                padding: 0;
                font-size: 0.9rem;
              }
            }

            .button-wrap {
              .button {
                min-height: 60px;
                font-size: 1.05rem;
                font-weight: 600;
                font-family: var(--font-alt);
              }
            }
          }
        }
      }
    }
  }
}



@media only screen and (max-width: 767px) {
  .form-layout {
    &.is-separate {
      .form-outer {
        .form-body {
          padding-left: 0;
          padding-right: 0;
          flex-direction: column;

          .form-section {
            width: 100%;

            .form-section-inner {
              padding: 30px;
            }
          }
        }
      }
    }
  }
}

@media only screen and (min-width: 768px) and (max-width: 1024px) and (orientation: portrait) {
  .form-layout {
    &.is-separate {
      .form-outer {
        .form-body {
          padding-left: 0;
          padding-right: 0;

          // flex-direction: column;

          .form-section {
            // width: 100%;

            .form-section-inner {
              padding: 30px;
            }
          }
        }
      }
    }
  }
}

.all-projects {
  .all-projects-header {
    display: flex;
    padding: 20px;
    background: var(--white);
    border: 1px solid var(--fade-grey-dark-3);
    border-radius: var(--radius-large);
    margin-bottom: 1.5rem;

    .header-item {
      width: 25%;
      border-right: 1px solid var(--fade-grey-dark-3);

      &:last-child {
        border-right: none;
      }

      .item-inner {
        text-align: center;

        .lnil,
        .lnir {
          font-size: 2.2rem;
          margin-bottom: 6px;
          color: var(--primary);
        }

        span {
          display: block;
          font-family: var(--font);
          font-weight: 600;
          font-size: 1.4rem;
          color: var(--dark-text);
        }

        p {
          font-family: var(--font-alt);
        }
      }
    }
  }

  .projects-card-grid {
    .grid-item {
      display: flex;
      flex-direction: column;
      justify-content: space-between;
      min-height: 220px;
      padding: 20px;
      background: var(--white);
      border: 1px solid var(--fade-grey-dark-3);
      border-radius: var(--radius-large);

      .top-section {
        .head {
          display: flex;
          justify-content: space-between;
          align-items: center;
          margin-bottom: 8px;

          h3 {
            font-size: 1rem;
            font-family: var(--font-alt);
            color: var(--dark-text);
            font-weight: 600;
          }
        }

        .body {
          p {
            font-family: var(--font);
            color: var(--light-text);
          }
        }
      }

      .bottom-section {
        display: flex;

        .foot-block {
          margin-right: 30px;

          .heading {
            font-family: var(--font-alt);
            font-size: 0.75rem;
            color: var(--light-text-dark-22);
          }

          >p {
            padding-top: 5px;
          }

          .developers {
            display: flex;

            .v-avatar {
              margin-right: 6px;
            }
          }
        }
      }
    }
  }
}

.heading {
  font-family: var(--font-alt);
  font-size: 0.75rem;
  color: var(--light-text-dark-22);
}

.is-dark {
  .form-layout {
    &.is-separate {
      .form-outer {
        background: none !important;

        .form-body {
          .form-section {
            .form-section-inner {
              @include vuero-card--dark;

              >h3 {
                color: var(--dark-dark-text);
              }

              .radio-boxes {
                .radio-box {
                  input:checked+.radio-box-inner {
                    background: var(--primary);
                    border-color: var(--primary);
                    box-shadow: var(--primary-box-shadow);

                    .fee,
                    p {
                      color: var(--smoke-white);
                    }
                  }

                  .radio-box-inner {
                    background: var(--dark-sidebar-light-2);
                    border-color: var(--dark-sidebar-light-12);

                    .fee {
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

.list-view-v1 {
  .list-view-item {
    @include vuero-r-card;

    margin-bottom: 16px;
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



@media only screen and (max-width: 767px) {
  .list-view-v1 {
    .list-view-item {
      .list-view-item-inner {
        position: relative;
        flex-direction: column;

        .v-avatar {
          margin-bottom: 10px;
        }

        .meta-left {
          margin-left: 0;
        }

        .meta-right {
          flex-direction: column;
          margin-left: 0;

          .tags {
            margin: 10px 0;
          }

          .stats {
            margin: 10px 0;
          }

          .network {
            margin: 10px 0 0;
            justify-content: center;

            >span {
              display: none;
            }
          }

          .dropdown {
            position: absolute;
            top: 0;
            right: 0;
            margin-left: 0;
          }
        }
      }
    }
  }
}

@media only screen and (min-width: 768px) and (max-width: 1024px) and (orientation: portrait) {
  .list-view-v1 {
    display: flex;
    flex-wrap: wrap;

    .list-view-item {
      margin: 10px;
      width: calc(50% - 20px);

      .list-view-item-inner {
        position: relative;
        flex-direction: column;

        .v-avatar {
          margin-bottom: 10px;
        }

        .meta-left {
          margin-left: 0;
        }

        .meta-right {
          flex-direction: column;
          margin-left: 0;

          .tags {
            margin: 10px 0;
          }

          .stats {
            margin: 10px 0;
          }

          .network {
            margin: 10px 0 0;
            justify-content: center;

            >span {
              display: none;
            }
          }

          .dropdown {
            position: absolute;
            top: 0;
            right: 0;
            margin-left: 0;
          }
        }
      }
    }
  }
}
</style>