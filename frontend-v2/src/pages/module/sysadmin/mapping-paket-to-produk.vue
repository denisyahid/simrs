<template>
  <div class="page-content-inner">
    <div class="is-navbar">
      <div class="form-layout is-10">
        <div class="form-outer">
          <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
            <div class="form-header-inner">
              <div class="left">
                <h3>Mapping Paket To Produk</h3>
              </div>
              <div class="right">
                <div class="buttons">
                  <VButton icon="lnir lnir-arrow-left rem-100"
                    :to="{ name: 'module-sysadmin-master-map-paket-to-produk' }" light dark-outlined>
                    Batal
                  </VButton>
                  <VButton type="button" icon="feather:save" :loading="isLoading" color="primary" raised
                    @click="saveMapPaketToProduk()"> Simpan
                  </VButton>

                </div>
              </div>
            </div>
          </div>
          <div class="form-body">
            <!--Fieldset-->
            <!--Fieldset-->
            <div class="form-fieldset">
              <div class="columns is-multiline">
                <div class="column is-12">
                  <VField class="cis-rounded-select_Z  is-autocomplete-select" v-slot="{ id }">
                    <h3 class="title is-6 mb-2 mr-1"> Pilih Paket </h3>
                    <VControl icon="feather:search">
                      <Multiselect mode="single" v-model="item.namapaket" :options="d_Paket" placeholder="Pilih data"
                        :searchable="true" :attrs="{ id }" @select="handleChangeProduk(item.namapaket)" />

                    </VControl>
                  </VField>
                </div>
                <!-- <Divider align="center">
                  <span class="p-tag">Produk</span>
                </Divider> -->
                <div class="column">
                  <VControl>
                    <VButton icon="feather:plus" v-model="isPaket" @click="popUpPaketProduk()" color="primary" raised>
                      Tambah Paket</VButton>
                  </VControl>
                </div>

                <div class="column is-12">
                  <VCard>
                    <div v-if="isLoading1 == true">
                      <VPlaceloadWrap>
                        <VPlaceload class="mx-2" />
                        <VPlaceload class="mx-2" />
                      </VPlaceloadWrap>
                    </div>
                    <div class="form-section pr-0 mt-0 pt-0" v-else>
                      <div class="form-section-inner has-padding-bottom h-700-o ">
                        <h3 class="has-text-centered">Data Terpilih ({{ listCheckedFiltered.length }})</h3>
                        <div class="column is-12" style="vertical-align: middle;">
                          <table class="table-pri" width="100%"
                            style="text-align: center;">
                            <thead>
                              <th class="th-pri">Nama Produk</th>
                              <th class="th-pri">Ruangan</th>
                              <th class="th-pri">Aksi</th>
                            </thead>
                            <tbody>
                              <tr v-for="(items, key) in listCheckedFiltered" :key="key">
                                <td class="td-pri" style="vertical-align: middle; padding: 10px;">
                                  <span style="font-weight: bold;">{{ items.namaproduk }}</span>
                                </td>
                                <td class="td-pri" style="vertical-align: middle; padding: 10px;">
                                  <VControl>
                                    <AutoComplete v-model="listCheckedFiltered[key].namaruangan"
                                      :suggestions="d_Ruangan" @complete="fetchRuangan($event)" :optionLabel="'value'"
                                      :dropdown="true" :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                                      :field="'label'" placeholder="ketik untuk mencari ruangan..."
                                      @select="handleRuanganSelect(items.id, $event)" />
                                  </VControl>
                                </td>
                                <td class="td-pri" style="padding: 10px;">
                                  <VIconButton circle icon="fas fa-trash" v-tooltip.danger.bubble="'Hapus Terpilih'"
                                    color="danger" raised bold @click="hapusProdukPaket(items.id, items.objectpaketfk)">
                                  </VIconButton>
                                </td>
                              </tr>
                            </tbody>
                          </table>

                        </div>
                      </div>
                    </div>
                  </VCard>

                </div>

                <div class="column is-12">
                  <VCard>
                    <div v-if="isLoading2 == true && listData.length == 0">
                      <VPlaceloadWrap>
                        <VPlaceload class="mx-2" />
                        <VPlaceload class="mx-2" />
                      </VPlaceloadWrap>
                    </div>
                    <div class="form-section pr-0 mt-0 pt-0" v-else>
                      <div class="form-section-inner has-padding-bottom h-700-o ">
                        <h3 class="has-text-centered">Data Yang Telah Ada ({{ listData.length }})</h3>
                        <div class="column is-12 is-multiline columns p-0" style="vertical-align: middle;" v-for="(items, key) in listData" :key="key">
                          <div class="column is-9">
                            <span style="font-weight: bold;">{{ items.namaproduk }}</span>
                          </div>
                          <div class="column is-3">
                            <VIconButton circle class="mr-2 is-pulled-right" icon="fas fa-trash"
                              v-tooltip.danger.bubble="'Hapus Terpilih'" color="danger" raised bold
                              @click="hapusProdukPaket(items.id, items.objectpaketfk)" :loading="isLoading1">
                            </VIconButton>
                          </div>
                        </div>
                      </div>
                    </div>
                  </VCard>

                </div>

                <Dialog v-model:visible="modalPaket" modal header="Pilih Produk" :style="{ width: '90vw' }">
                  <div class="is-flex column is-12">
                    <div class="column is-6">
                      <VCard>
                        <div class="columns is-multiline">
                          <div class="column is-6">
                            <VField>
                              <h3 class="title is-6 mb-2 mr-1"> Pilih Produk </h3>
                              <VControl icon="feather:search">
                                <input v-model="filters" class="input custom-text-filter" placeholder="Search..." />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-2 mt-5">
                            <VIconButton type="button" color="success" class="mt-1" circle raised icon="fas fa-search"
                              @click="listDropdown()" :loading="d_Produk.loading">
                            </VIconButton>
                          </div>
                        </div>
                        <p> Data terpilih <b>{{ listChecked.length }}</b></p>
                        <div class="columns is-multiline p-2" style="max-height:500px;overflow: auto;">
                          <div class="column is-6 mt-5" v-for="(data, key) in 10" v-if="d_Produk.loading">
                            <VPlaceload class="mx-1" />
                          </div>
                          <div class="column is-6" :key="index" v-for="(items, index) in d_Produk" v-else>
                            <VField>
                              <VControl raw subcontrol>
                                <VCheckbox v-model="checkboxProduk[items.id]" :value="items.id"
                                  :label="items.namaproduk" color="info" square @change="clickProduk()" />
                              </VControl>
                            </VField>
                          </div>
                        </div>

                      </VCard>
                    </div>
                    <div class="column is-6" v-if="listChecked.length > 0">
                      <VCard>
                        <div class="form-section pr-0 mt-0 pt-0">
                          <div class="form-section-inner has-padding-bottom h-700-o ">
                            <VControl>
                              <VButton icon="feather:plus" :loading="isLoading" @click="saveMapPaketToProdukTemporary()"
                                color="primary" raised>Tambah Produk</VButton>
                            </VControl>
                            <h3 class="has-text-centered">Data Terpilih ({{ listChecked.length }})</h3>
                            <div class="column is-12" style="vertical-align: middle;">
                              <div style="justify-content: space-between; display: flex;"
                                v-for="(items, key) in listChecked" :key="key">
                                <div>
                                  <span style="font-weight: bold;">{{ items.namaproduk }}</span>
                                </div>
                                <div>
                                  <VIconButton circle class="mr-2 is-pulled-right" icon="fas fa-trash"
                                    v-tooltip.danger.bubble="'Hapus Terpilih'" color="danger" raised bold
                                    @click="clearSelectionBeforeFiltered(items.id)">
                                  </VIconButton>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </VCard>

                    </div>
                  </div>
                  <div class="column is-12">
                    <VFlexPagination v-model:current-page="currentPage.page" :item-per-page="currentPage.limit"
                      :total-items="d_Produk.total" :max-links-displayed="5">
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
                                  <option :value="10">10 results per page</option>
                                  <option :value="15">15 results per page</option>
                                  <option :value="25">25 results per page</option>
                                  <option :value="30">30 results per page</option>
                                  <option :value="45">45 results per page</option>
                                  <option :value="60">60 results per page</option>
                                </select>
                              </div>
                            </VControl>
                          </VField>
                        </VFlex>
                      </template>
                    </VFlexPagination>
                  </div>
                </Dialog>

              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

</template>
<script setup lang="ts">
import { useWindowScroll } from '@vueuse/core'
import { useApi } from '/@src/composable/useApi'
import { h, reactive, ref, computed, defineComponent, watch } from 'vue'
import Divider from 'primevue/divider';
import { useRoute, useRouter } from 'vue-router'
import { useHead } from '@vueuse/head'
import * as H from '/@src/utils/appHelper'
import Fieldset from 'primevue/fieldset';
import { useViewWrapper } from '/@src/stores/viewWrapper'
import AutoComplete from 'primevue/autocomplete';
import { useToaster } from '/@src/composable/toaster'
import { elements } from '/@src/data/landing/components'
import Dialog from 'primevue/dialog';

useHead({
  title: 'Map Paket To Produk - ' + import.meta.env.VITE_PROJECT,
})
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
let ID_PRODUK = useRoute().query.id as string
let ID_PAKET = useRoute().query.objectpaketfk as string
let ID_PRODUK_SET = ref()
const date = ref(new Date())
const item: any = ref({})
const checkboxProduk: any = ref([])
const ruanganPaket: any = ref([])
const items: any = ref([])
const dataSource: any = ref([])
let d_Paket: any = ref([])
let d_Produk: any = ref([])
const listChecked: any = ref([])
const listCheckedFiltered: any = ref([])
const listData: any = ref([])
const d_ListDefault: any = ref([])
let d_KelompokProduk: any = ref([])
let isLoading = ref(false)
const isLoading1 = ref(false)
const isLoading2 = ref(false)
let jumlahCeklis: any = ref(0)
const isPaket: any = ref(false)
const { y } = useWindowScroll()
const router = useRouter()
const route = useRoute()
const d_Ruangan: any = ref([]);
const ruanganSelanjutnya = ref({});
const hasSent = ref(false);
const modalPaket: any = ref(false)
const listPaketStatusPending: any = ref([])
const isStuck = computed(() => {
  return y.value > 30
})
const filters = ref('')
const dataSourcefiltered = computed(() => {
  if (!filters.value) {
    return d_Produk.value
  }
  return d_Produk.value.filter((items: any) => {
    return (
      items.namaproduk.match(new RegExp(filters.value, 'i'))
    )
  })
})
const currentPage: any = ref({
  limit: 10,
  rows: 50,
})
currentPage.value.page = computed(() => {
  try {
    return Number.parseInt(route.query.page as string) || 1
  } catch { }
  return 1
})

async function listDropdown() {
  d_Produk.value.loading = true
  let limit: any = currentPage.value.limit
  let offset: any = route.query.page ? route.query.page : 1
  offset = (offset * limit) - limit
  let search = filters.value ?? ''
  const response = await useApi().get(
    `/sysadmin/master-map-paket-to-produk-dropdown?search=${search}&limit=${limit}&offset=${offset}`)
  d_Paket.value = response.namapaket.map((e: any) => { return { label: e.namapaket, value: e.id } })
  d_Produk.value = response.namaproduk
  d_Produk.value.total = response.totalProduct
  d_ListDefault.value = response.namaproduk
  item.namaproduk = response.namaproduk[0].id
  route.query.page = '1'
  if (ID_PRODUK) {
    ID_PRODUK_SET.value = ID_PRODUK
    produkByID(ID_PRODUK)
  }
  if (ID_PAKET) {
    item.value.namapaket = ID_PAKET
    handleChangeProduk(ID_PAKET)
  }
  d_Produk.value.loading = false
}

async function produkByID(id: any) {
  const { data: detail } = await useApi().get(`/sysadmin/master-map-paket-to-produk?id=${id}`)
  item.value.namapaket = detail[0].objectpaketfk
  item.value.arrProduk = detail[0].objectprodukfk
}


async function saveMapPaketToProduk() {
  if (!item.value.namapaket) {
    H.alert('warning', 'Nama Paket harus dipilih');
    return;
  }

  let arrProduk = [];
  for (let check of listCheckedFiltered.value) {
    arrProduk.push({
      id: check.id,
      produkfk: check.idproduk,
      objectruanganfk: check.namaruangan?.value || null,
      status: ''
    });
  }

  let json = {
    objectpaketfk: item.value.namapaket,
    detail: arrProduk,
  };

  console.log(json); // Debugging untuk memastikan data benar

  isLoading1.value = true;
  try {
    await useApi().post(`/sysadmin/save-map-paket-to-produk`, json);
    isLoading1.value = false;
    sendToListCheckedFiltered(item.value.namapaket);
  } catch (e) {
    isLoading1.value = false;
    console.error(e);
  }
}


async function saveMapPaketToProdukTemporary() {
  // if (jumlahCeklis.value == 0) { H.alert('warning', 'Produk harus di pilih'); return }
  let arrProduk = []
  let objectK = Object.keys(checkboxProduk.value)
  for (let x = 0; x < objectK.length; x++) {
    const element = objectK[x];
    // loop listChecked.value nya untuk get objectruanganfk tidak sama dengan yang sebelumnya
    for (let check of listChecked.value) {
      if (check.id == element) {
        arrProduk.push(
          {
            'produkfk': element,
            'status': 'Pending'
            // 'objectruanganfk': check.namaruangan.value
          },
        )
        break;
      }
    }
  }
  let json = {
    'objectpaketfk': item.value.namapaket,
    'detail': arrProduk
  }
  console.log(json)
  isLoading.value = true
  await useApi().post(
    `/sysadmin/save-map-paket-to-produk-temporary`, json).then((response: any) => {
      isLoading.value = false
      listChecked.value = []
      sendToListCheckedFiltered(item.value.namapaket,)
    }).catch((e: any) => {
      isLoading.value = false
    })

}

function formatRupiah(amount) {
  if (!amount) return 'Rp 0';
  return 'Rp ' + parseInt(amount).toLocaleString('id-ID');
}

const fetchRuangan = async (filter: any) => {
  const response = await useApi().get(
    `/emr/dropdown/ruangan_m?select=id,namaruangan&param_search=namaruangan&query=${filter.query}&limit=10`
  );
  d_Ruangan.value = response;
  console.log(d_Ruangan.value)
};
const handleRuanganSelect = (id, selected) => {
  ruanganPaket.value[id] = selected.value;
  console.log(ruanganPaket.value)
};

function clickProduk() {

  jumlahCeklis.value = 0;
  let objectK = Object.keys(checkboxProduk.value);
  let jumlah = 0;

  for (let x = 0; x < objectK.length; x++) {
    const element = objectK[x];
    if (checkboxProduk.value[element] === true) {
      for (let i = 0; i < d_Produk.value.length; i++) {
        const element2 = d_Produk.value[i];
        if (element2.id == element) {

          const produkExist = listChecked.value.find(item => item.id === element2.id);
          if (!produkExist) {
            // console.log("Produk yang sudah ada di list, ditambahkan kembali:", element2.id);
            for (var dup = 0; dup < listChecked.value.length; dup++) {
              const element3 = listChecked.value[dup];
              console.log(element3);
              if (element3.namaproduk == element2.namaproduk) {
                listChecked.value.splice(dup, 1);
              }
            }

            listChecked.value.push({
              namaproduk: element2.namaproduk,
              name: element2.kebangsaan,
              harga: formatRupiah(element2.hargasatuan),
              id: element2.id,
              namaruangan: ruanganPaket.value[element2.id],
            });

            // console.log("Produk yang dicentang lagi:", listChecked.value);
            jumlah += 1;
          }

          break;
        }
      }
    } else {
      for (let i = 0; i < listChecked.value.length; i++) {
        const element2 = listChecked.value[i];
        if (element2.id == element) {
          listChecked.value.splice(i, 1);
          jumlah -= 1;
        }
      }
    }
  }

  jumlahCeklis.value = jumlah;
}

function resetForm() {

}

const popUpPaketProduk = async () => {
  modalPaket.value = true
  listDropdown()
}

// async function sendToListCheckedFiltered(id: any) {
//   isLoading1.value = true;
//   try {
//     const response = await useApi().get(
//       `/sysadmin/master-map-paket-status?objectpaketfk=${id}`
//     ).then((response: any) => {
//       isLoading1.value = false

//     });

//     listCheckedFiltered.value = response
//     console.log(listCheckedFiltered.value);

//     isLoading1.value = false;
//     modalPaket.value = false
//   } catch (error) {
//     console.error('Error fetching data:', error);
//     isLoading1.value = false;
//   }
// }
const sendToListCheckedFiltered = async (id: any) => {
  isLoading1.value = true;
  try {
    const response = await useApi().get(
      `/sysadmin/master-map-paket-status?objectpaketfk=${id}`
    ).then((response: any) => {
      isLoading1.value = false
      listCheckedFiltered.value = response
      // console.log(listCheckedFiltered.value);
      modalPaket.value = false
    });
  } catch (error) {
    console.error('Error fetching data:', error);
    isLoading1.value = false;
  }
}
const hapusProdukPaket = async (id: any, objectpaketfk: any) => {
  isLoading1.value = true;
  try {
    const response = await useApi().post(
      `/sysadmin/delete-map-paket-to-produk?id=${id}`
    ).then((response: any) => {
      isLoading1.value = false
      handleChangeProduk(objectpaketfk)
    });
  } catch (error) {
    console.error('Error fetching data:', error);
    isLoading1.value = false;
  }
}

// function handleChangeProduk(e: any) {
//   checkboxProduk.value = []
//   jumlahCeklis.value = 0
//   useApi().get(
//     `/sysadmin/master-map-paket-to-produk?objectpaketfk=${e}`).then((response: any) => {
//       isLoading.value = false
//       jumlahCeklis.value = response.data.length
//       for (let x = 0; x < response.data.length; x++) {
//         const element = response.data[x];
//         checkboxProduk.value[element.objectprodukfk] = true
//       }
//       clickProduk()
//     }).catch((e: any) => {
//       isLoading.value = false
//     })

//   checkboxProduk.value.total = checkboxProduk.value.length
// }
function handleChangeProduk(objectpaketfk) {
  try{
    isLoading2.value = true;
    listData.value = [];
    jumlahCeklis.value = 0;

    useApi()
    .get(`/sysadmin/master-map-paket-by-id?objectpaketfk=${objectpaketfk}`)
    .then((response) => {
      isLoading2.value = false;
      listData.value = response;
      if (listData.value.length > 0) {
        H.alert('success', 'Paket Berhasil ditampilkan');
      }
      console.log('API Response:', listData.value);
    })
    .catch((error) => {
      isLoading2.value = false;
      console.log(error)
    });
    sendToListCheckedFiltered(objectpaketfk)
  } catch(e){
    console.error('Error fetching data:', error);
    isLoading2.value = false;
  }


}
watch(currentPage.value, () => {
  listDropdown()
})
listDropdown()

watch(
  () => item.value.namapaket,
  (newVal) => {
    if (newVal) {
      handleChangeProduk(newVal);
    }
  }
);

watch(() => isPaket.value, (newValue, oldValue) => {
  if (newValue == true) {
    popUpPaketProduk()
  }
})


const clearSelectionBeforeFiltered = (id: any) => {
  listChecked.value = listChecked.value.filter(item => item.id !== id);
}

const clearSelection = (id: any) => {
  listCheckedFiltered.value = listCheckedFiltered.value.filter(item => item.id !== id);
}

</script>
<style lang="scss">
@import '/@src/scss/abstracts/all';
@import '/@src/scss/components/forms-outer';

.form-layout {
  max-width: 800px;
  margin: 0 auto;
}


.checkbox.is-outlined.is-info.is-solid input+span::after {
  color: var(--white);
}

.form-fieldset {
  padding: 20px 0;
  max-width: 740px;
  margin: 0 auto;
}

.th-pri {
  border: 1px solid black;
}

.td-pri {
  border: 1px solid black;
}
</style>
