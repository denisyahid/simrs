<template>
  <VCard>
    <div>
      <h3 class="title is-5 mb-2 mr-1">Set Alat Steril</h3>
    </div>
    <div class="columns">
      <div class="column is-8">
        <a type="button" class="is-pulled-right mr-3" color="info" outlined raised @click="add()">
          <span class="icon">
            <i class="fas fa-plus"></i>
          </span>
          <span>Tambah</span>
        </a>
        <div class="tile-grid tile-grid-v1 mt-5">
          <div class="column is-12">
            <VPlaceholderPage v-if="dataSource.length == 0" title="Data Tidak Ditemukan."
              subtitle="Silakan gunakan filter lain" larger>
              <template #image>
                <img class="light-image" src="/@src/assets/illustrations/placeholders/search-4.png" alt="" />
                <img class="dark-image" src="/@src/assets/illustrations/placeholders/search-4-dark.svg" alt="" />
              </template>
            </VPlaceholderPage>
            <div class="columns is-multiline" v-else-if="isLoading">
              <div class="column is-6" v-for="data in 2">
                <div class="tile-grid-item">
                  <VPlaceloadWrap>
                    <VPlaceloadAvatar size="medium" />
                    <VPlaceloadText last-line-width="60%" class="mx-2" />
                    <VPlaceload class="mx-2" disabled />
                  </VPlaceloadWrap>
                </div>
              </div>
            </div>
            <TransitionGroup name="list" tag="div" class="columns is-multiline" v-else>
              <div class="column is-6" v-for="(data, i) in dataSource">
                <div class="tile-grid-item">
                  <div class="tile-grid-item-inner">
                    <VIconBox size="medium" :color="listColor[i]" rounded>
                      <p>{{ data.kelompokAlatId }}</p>
                    </VIconBox>
                    <div class="meta">
                      <span class="dark-inverted mb-2">{{ data.namakelompokalat }}</span>
                      <span class="dark-inverted">{{ data.details.length }} Produk</span>
                    </div>
                    <VDropdown icon="feather:more-vertical" spaced right>
                      <template #content>
                        <a role="menuitem" class="dropdown-item is-media" @click="detail(data)">
                          <div class="icon">
                            <i class="iconify" data-icon="feather:bookmark" aria-hidden="true"></i>
                          </div>
                          <div class="meta">
                            <span>Detail</span>
                            <span>Untuk melihat data </span>
                          </div>
                        </a>
                        <a role="menuitem" class="dropdown-item is-media" @click="hapusSetAlat(data)">
                          <div class="icon">
                            <i class="iconify" data-icon="feather:trash-2" aria-hidden="true"></i>
                          </div>
                          <div class="meta">
                            <span>Hapus</span>
                            <span>Hapus data </span>
                          </div>
                        </a>
                        <a role="menuitem" class="dropdown-item is-media" @click="editSetAlat(data)">
                          <div class="icon">
                            <i aria-hidden="true" class="lnil lnil-pencil"></i>
                          </div>
                          <div class="meta">
                            <span>Edit</span>
                            <span>Ubah Set Alat </span>
                          </div>
                        </a>
                      </template>
                    </VDropdown>
                  </div>
                </div>
              </div>
            </TransitionGroup>
          </div>
        </div>
      </div>
      <div class="column is-4">
        <div class="columns is-multiline">
          <div class="column is-6">
            <h3 class="title is-5 mb-2 mr-1">Filters</h3>
          </div>
          <div class="column is-6">
            <a @click="clearFilter()" type="button" class="is-pulled-right mr-3" color="info" outlined raised>
              Clear All
            </a>
          </div>
          <div class="column is-12 mb-5">
            <VField label="Search">
              <VInput v-model="item.search" placeholder="Cari data.." class="is-rounded"></VInput>
            </VField>
          </div>
          <div class="column is-12 mt-5">
            <VButton @click="fetchData()" :loading="isLoading" type="button" icon="feather:search"
              class="is-fullwidth mr-3" color="info" raised>
              Apply Filters
            </VButton>
          </div>
        </div>
      </div>
    </div>
  </VCard>
  <!-- modal detail -->
  <VModal :open="modalDetail" title="Detail" size="medium" actions="right" @close="modalDetail = false">
    <template #content>
      <form class="modal-form">
        <DataTable :value="dataSourceDetail" class="p-datatable-sm" responsiveLayout="stack" breakpoint="960px"
          showGridlines sortMode="multiple">
          <Column field="no" header="No"></Column>
          <Column field="namaproduk" header="Produk" :sortable="true"></Column>
          <Column field="satuanstandar" header="Satuan"></Column>
          <Column field="jumlah" header="Jumlah"></Column>
          <template #empty style="text-align: center;"> Data Belum Terinput. </template>
        </DataTable>
      </form>
    </template>
  </VModal>
  <!-- end modal detail -->
  <!-- modal detail -->
  <VModal :open="modalAdd" :title="item.title" size="large" actions="right" @close="modalAdd = false">
    <template #content>
      <form class="modal-form px-1">
        <div class="columns is-multiline">
          <div class="column is-12">
            <VField>
              <VLabel>Nama Set Alat</VLabel>
              <VControl icon="feather:bookmark">
                <VInput v-model="item.namakelompokalat" class="is-rounded" placeholder="Nama Set Alat"></VInput>
              </VControl>
            </VField>
          </div>
          <div class="column is-4">
            <VField class="is-rounded-select is-autocomplete-select">
              <VLabel>Produk</VLabel>
              <VControl icon="feather:search">
                <Dropdown v-model="item.produk" :optionLabel="'namaproduk'" :options="d_Produk" class="is-rounded"
                  placeholder="Pilih data" style="width: 100%;" showClear :filter="true"
                  @change="changeProduk(item.produk)" :loading="isLoadingCombo" />
              </VControl>
            </VField>
          </div>
          <div class="column is-3">
            <VField class="is-rounded-select is-autocomplete-select">
              <VLabel>QTY Alat</VLabel>
              <VControl icon="feather:hash">
                <VInput v-model="item.jumlah" class="is-rounded" placeholder="qty alat" number></VInput>
              </VControl>
            </VField>
          </div>
          <div class="column is-3">
            <VField class="is-rounded-select is-autocomplete-select">
              <VLabel>Satuan</VLabel>
              <VControl icon="feather:search">
                <Dropdown v-model="item.satuan" :options="d_satuan" :optionLabel="'satuanstandar'" class="is-rounded"
                  placeholder="Pilih data" style="width: 100%;" showClear :filter="true" />
              </VControl>
            </VField>
          </div>
          <div class="column is-2 mt-5">
            <div class="columns mt-1 is-multiline">
              <VIconButton color="primary" circle icon="fas fa-plus" raised @click="tambah()"
                v-tooltip.bottom.left="'Tambah'">
              </VIconButton>
              <VIconButton class="ml-3" circle icon="fas fa-times" raised @click="clear()"
                v-tooltip.bottom.left="'Batal'" light dark-outlined>
              </VIconButton>
            </div>
          </div>
          <div class="column is-12 mt-5">
            <DataTable :value="dataSourceAdd" class="p-datatable-sm" responsiveLayout="stack" breakpoint="960px"
              showGridlines sortMode="multiple">
              <Column :exportable="false" header="#" style="width:8rem">
                <template #body="slotProps">
                  <Button icon="pi pi-pencil" class="p-button-rounded p-button-warning mr-2"
                    @click="editRow(slotProps.data)" />
                  <Button icon="pi pi-trash" class="p-button-rounded p-button-danger"
                    @click="hapusRow(slotProps.data)" />
                </template>
              </Column>
              <Column field="no" header="No"></Column>
              <Column field="namaproduk" header="Produk" :sortable="true"></Column>
              <Column field="satuanstandar" header="Satuan"></Column>
              <Column field="jumlah" header="Jumlah"></Column>
              <template #empty style="text-align: center;"> Data Belum Terinput. </template>
            </DataTable>
          </div>
        </div>
      </form>
    </template>
    <template #action>
      <VButton icon="feather:save" @click="simpan()" color="primary" :loading="isLoadingSave" raised>Simpan</VButton>
    </template>
  </VModal>
  <!-- end modal detail -->
</template>

<script setup lang="ts">
import { useRoute, useRouter } from 'vue-router'
import { ref, computed, reactive, watch } from 'vue'
import { useThemeColors } from '/@src/composable/useThemeColors'
import { useApi } from '/@src/composable/useApi'
import { useUserSession } from '/@src/stores/userSession'
import { useHead } from '@vueuse/head'
import DataTable from 'primevue/datatable'
import Column from 'primevue/column'
import Dropdown from 'primevue/dropdown'
import Button from 'primevue/button'

const item: any = reactive({})
const isLoading: any = ref(false)
const isLoadingCombo: any = ref(false)
const dataSource: any = ref([])
const dataSourceDetail: any = ref([])
const listColor: any = ref(Object.keys(useThemeColors()))
const modalDetail: any = ref(false)
const idPaket: any = ref('')
const modalAdd: any = ref(false)
const d_satuan: any = ref([])
const d_Produk: any = ref([])
const data2: any = ref([])
const dataSourceAdd: any = ref([])
const isLoadingSave: any = ref(false)

const clearFilter = () => {
  delete item.search
}
const fetchData = async () => {
  isLoading.value = true
  let search = item.search ?? ''
  const response = await useApi().get(`/stelilisasi/kelompok-alat?search=${search}`);
  dataSource.value = response.data;
  isLoading.value = false
}
const detail = async (data: any) => {
  data.details.map((element: any, index: number) => {
    element.no = index + 1
  })
  dataSourceDetail.value = data.details
  modalDetail.value = true
}
const add = () => {
  modalAdd.value = true
  dropdown({ query: "" });
}
const dropdown = async (filter: any) => {
  item.title = 'Set Alat Steril';
  isLoadingCombo.value = true
  const response = await useApi().get(`/stelilisasi/get-produk?${filter.query}`);
  d_Produk.value = response;
  isLoadingCombo.value = false
}
const changeProduk = async (produk: any) => {
  if (produk) {
    d_satuan.value = [{ id: produk.ssid, satuanstandar: produk.satuanstandar }]
    item.satuan = { id: produk.ssid, satuanstandar: produk.satuanstandar };
  }
}
const tambah = () => {
  var data = {};
  let nomor = 0
  if (data2.value.length == 0) {
    nomor = 1
  } else {
    nomor = data2.value.length + 1
  }
  if (item.no) {
    for (let x = 0; x < data2.value.length; x++) {
      const element = data2.value[x];
      if (element.no == item.no) {
        data.no = item.no
        data.produkfk = item.produk.id
        data.namaproduk = item.produk.namaproduk
        data.satuanstandarfk = item.satuan.ssid
        data.satuanstandar = item.satuan.satuanstandar
        data.satuanviewfk = item.satuan.ssid
        data.satuanview = item.satuan.satuanstandar
        data.jumlah = item.jumlah
        data.jumlahobat = item.jumlah
        data2.value[x] = data;
      }
    }
  } else {
    data = {
      no: nomor,
      produkfk: item.produk.id,
      namaproduk: item.produk.namaproduk,
      satuanstandarfk: item.satuan.ssid,
      satuanstandar: item.satuan.satuanstandar,
      satuanviewfk: item.satuan.ssid,
      satuanview: item.satuan.satuanstandar,
      jumlah: item.jumlah,
      jumlahobat: item.jumlah,
    }
    data2.value.push(data)
  }
  dataSourceAdd.value = data2.value
  clear()

}
const clear = () => {
  delete item.produk
  delete item.jumlah
  delete item.satuan
}
const editRow = async (e: any) => {
  item.no = e.no
  await dropdown({ query: e.namaproduk })
  d_Produk.value.forEach((element: any) => {
    if (element.id == e.produkfk) {
      item.produk = element
      item.satuan = { id: element.ssid, satuanstandar: element.satuanstandar };
      d_satuan.value = [{ id: element.ssid, satuanstandar: element.satuanstandar }]
      return
    }
  });
  item.jumlah = e.jumlah
}
const hapusRow = (e: any) => {
  for (var i = data2.value.length - 1; i >= 0; i--) {
    if (data2.value[i].no == e.no) {
      data2.value.splice(i, 1);
    }
  }
  dataSourceAdd.value = data2.value
}
const simpan = async (e: any) => {
  isLoadingSave.value = true
  var objSave = {
    idPaket: idPaket.value ?? '',
    namakelompok: item.namakelompokalat,
    details: data2.value
  }
  await useApi().post(`stelilisasi/save-kelompok-alat`, objSave).then((response: any) => {
    modalAdd.value = false
    isLoadingSave.value = false
    fetchData();
  }).catch((error: any) => {
    isLoadingSave.value = false
  })
}
const hapusSetAlat = async (e: any) => {
  let id = e.kelompokAlatId
  await useApi().post(`stelilisasi/delete-kelompok-alat`, { idPaket: id }).then((response: any) => {
    fetchData();
  }).catch((error: any) => {
  })
}
const editSetAlat = async (e: any) => {
  idPaket.value = e.kelompokAlatId
  item.namakelompokalat = e.namakelompokalat
  e.details.map((element: any, index: number) => {
    element.no = index + 1
  })
  data2.value = e.details
  dataSourceAdd.value = data2.value
  modalAdd.value = true
}
fetchData()
</script>
<style lang="scss">
@import '/@src/scss/abstracts/all';

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
            font-size: 1rem;
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

.is-dark {
  .tile-grid {
    .tile-grid-item {
      @include vuero-card--dark;
    }
  }
}
</style>
