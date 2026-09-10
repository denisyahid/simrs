<template>
  <VCard>
    <div>
      <h3 class="title is-5 mb-2 mr-1">Daftar Barang Alat Medis</h3>
    </div>
    <div class="business-dashboard flights-dashboard">
      <div class="columns">
        <div class="column is-8">
          <div class="tile-grid tile-grid-v1 mt-5">
            <div class="columns is-multiline" v-if="isLoading">
              <div class="column is-12">
                <div class="flights" v-for="data in 2" :key="data">
                  <VCard>
                    <div class="columns is-multiline">
                      <div class="column is-12">
                        <VPlaceload class="mx-2" />
                      </div>
                      <div class="column is-12">
                        <VPlaceload class="mx-2" />
                      </div>
                    </div>
                  </VCard>
                  <div class="flights-summary-wrapper mt-2">
                    <div class="columns is-flex-tablet-p">
                      <div class="column is-12">
                        <a class="flight-summary" style="displ">
                          <div class="columns is-multiline">
                            <div class="column is-1">
                              <VPlaceloadAvatar />
                            </div>

                            <div class="column is-2">
                              <div class="meta">
                                <VPlaceload class="mx-2" />
                              </div>
                            </div>

                            <div class="column is-2">
                              <div class="meta">
                                <VPlaceload class="mx-2" />
                              </div>
                            </div>

                            <div class="column is-1">
                              <div class="meta">
                                <VPlaceload class="mx-2" />
                              </div>
                            </div>

                            <div class="column is-2">
                              <div class="meta">
                                <VPlaceload class="mx-2" />
                              </div>
                            </div>
                          </div>
                        </a>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <VPlaceholderPage v-else-if="dataSource.length == 0" title="Data Tidak Ditemukan."
              subtitle="Silakan Pilih Tanggal dan Ruangan untuk melihat Data" larger>
              <template #image>
                <img class="light-image" src="/@src/assets/illustrations/placeholders/search-4.png" alt="" />
                <img class="dark-image" src="/@src/assets/illustrations/placeholders/search-4-dark.svg" alt="" />
              </template>
            </VPlaceholderPage>
            <div class="flights" v-for="(data, index) in dataSource" v-else>
              <a class="flight-card">
                <div class="start">
                  <span>{{ data.namaProduk }}</span>
                  <span>{{ data.kodeProduk }}</span>
                </div>
                <div class="route">
                  <div class="departure"></div>
                  <div class="line" :data-content="data.tglKadaluarsa ? H.formatDateIndo(data.tglKadaluarsa) : '-'">
                  </div>
                  <div class="arrival" style="transform: none;">
                    <i aria-hidden="true" class="fas fa-dolly"></i>
                  </div>
                </div>
                <div class="end">
                  <span>nama ruangan</span>
                  <span>{{ data.namaruangan }}</span>
                </div>
              </a>
              <div class="flights-summary-wrapper" style="margin-top: -2.5rem;">
                <div class="columns is-flex-tablet-p">
                  <div class="column is-12">
                    <a class="flight-summary">
                      <div class="columns is-multiline">
                        <div class="column is-1">
                          <div class="collapse-icon is-clickable">
                            <VIcon icon="feather:minimize" />
                          </div>
                        </div>

                        <div class="column is-3">
                          <div class="meta">
                            <span>No Batch</span>
                            <span>{{ data.noBatch }}</span>
                          </div>
                        </div>

                        <div class="column is-2">
                          <div class="meta">
                            <span>No Terima</span>
                            <span>{{ data.noTerima }}</span>
                          </div>
                        </div>

                        <div class="column is-3">
                          <div class="meta">
                            <span>Asal Produk</span>
                            <span>{{ data.asalProduk }}</span>
                          </div>
                        </div>
                        <div class="column is-2">
                          <div class="meta">
                            <span>Stok</span>
                            <span>{{ data.qtyProduk }}</span>
                          </div>
                        </div>
                      </div>
                    </a>
                  </div>
                </div>
              </div>
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
            <div class="column is-12">
              <VField class="is-rounded-select is-autocomplete-select" label="Search">
                <VControl icon="feather:search" fullwidth class="prime-auto-select">
                  <VInput v-model="item.search" placeholder="Search..." class="is-rounded" />
                </VControl>
              </VField>
            </div>
            <div class="column is-12">
              <VField class="is-rounded-select is-autocomplete-select" label="Ruangan">
                <VControl icon="feather:search" fullwidth class="prime-auto-select">
                  <Dropdown v-model="item.ruanganfk" :options="d_Ruangan" optionLabel="label"
                    placeholder="Pilih Ruangan" style="width: 100%;" :filter="true" />
                </VControl>
              </VField>
            </div>
            <div class="column is-12">
              <VField class="is-rounded-select is-autocomplete-select" label="Asal Produk">
                <VControl icon="feather:search" fullwidth class="prime-auto-select">
                  <Dropdown v-model="item.asalprodukfk" :options="d_AsalProduk" optionLabel="label"
                    placeholder="Asal Produk" style="width: 100%;" :filter="true" />
                </VControl>
              </VField>
            </div>
            <div class="column is-12">
              <VButton @click="fetchData()" :loading="isLoading" type="button" icon="feather:search"
                class="is-fullwidth mr-3" color="info" raised>
                Apply Filters
              </VButton>
            </div>
          </div>
        </div>
      </div>
    </div>
  </VCard>
</template>

<script setup lang="ts">
import { useRoute, useRouter } from 'vue-router'
import { ref, computed, reactive, watch } from 'vue'
import { useThemeColors } from '/@src/composable/useThemeColors'
import { useApi } from '/@src/composable/useApi'
import { useUserSession } from '/@src/stores/userSession'
import { useHead } from '@vueuse/head'
import { useToaster } from '/@src/composable/toaster'
import moment from 'moment'
import * as H from '/@src/utils/appHelper'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import * as XLSX from "xlsx"
import * as XLSXStyle from 'xlsx-js-style'
import AutoComplete from 'primevue/autocomplete'
import Dropdown from 'primevue/dropdown'
useHead({
  title: 'Daftar Barang Alat Medis' + import.meta.env.VITE_PROJECT,
})
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)


const item: any = reactive({
  periode: reactive({
    start: new Date(),
    end: new Date(),
  }),
})
const isLoading: any = ref(false)
const d_Ruangan: any = ref(false)
const dataSource: any = ref([])
const d_AsalProduk: any = ref([])

const clearFilter = () => {
  delete item.ruanganfk
  delete item.asalprodukfk
}
const fetchData = async () => {
  isLoading.value = true
  let ruangan = item.ruanganfk ? item.ruanganfk.value : ''
  let asalproduk = item.asalprodukfk ? item.asalprodukfk.value : ''
  await useApi().get(`/stelilisasi/get-data-stok-steril?ruanganfk=${ruangan}&asalprodukfk=${asalproduk}`).then((response: any) => {
    dataSource.value = response.detail
  })
  isLoading.value = false

}
const dropdown = async () => {
  await useApi().get(`/stelilisasi/combo`).then((response: any) => {
    d_Ruangan.value = response.ruangan.map((element: any) => {
      return {
        label: element.namaruangan,
        value: element.id
      }
    })
    d_AsalProduk.value = response.sumberDana.map((element: any) => {
      return {
        label: element.asalProduk,
        value: element.id
      }
    })
  })
}
dropdown();
fetchData();
</script>

<style lang="scss">
@import '/@src/scss/abstracts/all';

@import '/@src/scss/components/forms-outer';
@import '/@src/scss/custom/config';
@import '/@src/scss/module/logistik/penerimaan-barang-suplier.scss';

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
