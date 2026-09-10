<template>
  <ConfirmDialog />
  <div class="column is-12">
    <VCard style="padding-bottom: 0px">
      <div class="column c-title pt-2 mb-5">
        <label class="title-page">Pencarian</label>
      </div>
      <div class="column is-12">
        <div class="columns">
          <div class="column is-4">
            <VField label="Tanggal Struk" style="margin-bottom: 6px;" />
            <VDatePicker v-model="item.filterTgl" is-range color="pink" trim-weeks>
              <template #default="{ inputValue, inputEvents }">
                <VField addons>
                  <VControl icon="feather:calendar">
                    <VInput :value="inputValue.start" v-on="inputEvents.start" />
                  </VControl>
                  <VControl>
                    <VButton static><i class="fas fa-arrow-right" aria-hidden="true"></i></VButton>
                  </VControl>
                  <VControl icon="feather:calendar">
                    <VInput :value="inputValue.end" v-on="inputEvents.end" />
                  </VControl>
                </VField>
              </template>
            </VDatePicker>
          </div>

          <div class="column is-3">
            <VField class="is-autocomplete-select" label="Nama Supplier">
              <VControl icon="feather:search">
                <Multiselect mode="single" v-model="item.rekanan" :options="d_Rekanan" placeholder="Pilih Barang"
                  :searchable="true" />
              </VControl>
            </VField>
          </div>
          <div class="column is-3">
            <VField label="No Dokumen">
              <VControl icon="feather:bookmark">
                <VInput type="text" v-model="item.nodokumen" placeholder="No Dokumen" />
              </VControl>
            </VField>
          </div>
          <div class="column btn-search">
            <VButton type="button" icon="feather:search" @click="fetchData()" :loading="isLoadingBtn">
              Cari Data
            </VButton>
          </div>

        </div>
      </div>
    </VCard>
  </div>

  <div class="column is-12">
    <VCard>
      <div class="column c-title pt-2 mb-0">
        <div class="columns p-2">
          <div class="column is-10">
            <label class="title-page">Daftar Penerimaan</label>
            <label for="">List Stok Penerimaan Darah</label>
          </div>
          <div class="column pr-0">
            <VButton type="button" icon="fas fa-plus-circle" RouterLink
              :to="{ name: 'module-darah-form-penerimaan-darah' }" class="is-fullwidth is-outlined is-primary mt-4"
              rounded raised>
              Tambah Penerimaan
            </VButton>
          </div>
        </div>
      </div>

      <div class="business-dashboard flights-dashboard">
        <div class="columns">
          <div class="column is-12">
            <div class="flights" v-for="data in 3" :key="data" v-if="isLoadData">
              <VCard>
                <div class="columns is-multiline">
                  <div class="column is-5">
                    <VPlaceload class="mx-2" />
                  </div>
                  <div class="column is-1">
                    <div class="arrival" style="transform: none;text-align: center;font-size: 17px;">
                      <i aria-hidden="true" class="fas fa-dolly"></i>
                    </div>
                  </div>
                  <div class="column is-6">
                    <VPlaceload class="mx-2" />
                  </div>
                </div>
              </VCard>


              <div class="flights-summary-wrapper">
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

                        <div class="column">
                          <VAvatarStack>
                            <VPlaceloadAvatar class="mx-1" />
                            <VPlaceloadAvatar class="mx-1" />
                            <VPlaceloadAvatar class="mx-1" />
                            <VPlaceloadAvatar class="mx-1" />
                            <VPlaceloadAvatar class="mx-1" />
                          </VAvatarStack>
                        </div>

                      </div>

                    </a>
                  </div>
                </div>
              </div>
            </div>

            <div v-else>
              <div class="p-0" v-if="dataLength < 1">
                <div class="column p-0 m-0" style="display: flex ; justify-content: center;">
                  <img class="light-image" src="/@src/assets/illustrations/placeholders/search-4.svg" alt=""
                    style="max-width: 26%;margin-top: 1rem;" />
                  <img class="dark-image" src="/@src/assets/illustrations/placeholders/search-4-dark.svg" alt=""
                    style="max-width: 26%;margin-top: 1rem;" />
                </div>
                <h3 style="text-align: center;font-weight: 600;color: var(--dark-text);font-family: var(--font-alt);">
                  Daftar Penerimaan Darah Saat ini tidak tersedia</h3>
              </div>
              <div class="flights" v-for="(items, i) in dataSource" :key="items.id" v-else>
                <a class="flight-card">
                  <div class="start">
                    <span>{{ items.namarekanan }}</span>
                    <span>Supplier</span>

                  </div>

                  <div class="route">
                    <div class="departure"></div>
                    <div class="line" :data-content="H.formatDateIndo(items.tglstruk)"></div>


                    <div class="arrival" style="transform: none;">
                      <i aria-hidden="true" class="fas fa-dolly"></i>
                    </div>
                  </div>

                  <div class="end">

                    <span>{{ items.namaruangan }}</span>
                    <span>Ruang Tujuan</span>
                  </div>
                </a>
                <div class="flights-summary-wrapper" style="margin-top: -2.5rem;">
                  <div class="columns is-flex-tablet-p">
                    <div class="column is-12">
                      <a class="flight-summary">
                        <div class="columns is-multiline">
                          <div class="column is-1">
                            <div class="collapse-icon is-clickable" @click="items.isExpand = true"
                              v-if="!items.isExpand">
                              <VIcon icon="feather:chevron-down" />
                            </div>
                            <div class="collapse-icon  is-clickable" open @click="items.isExpand = false"
                              v-if="items.isExpand">
                              <VIcon icon="feather:chevron-up" />
                            </div>
                          </div>

                          <div class="column is-3">
                            <div class="meta">
                              <span>{{ items.noterima }}</span>
                              <span>No Terima</span>
                            </div>
                          </div>

                          <div class="column is-3">
                            <div class="meta">
                              <span>{{ items.jmlitem }}</span>
                              <span>QTY</span>
                            </div>
                          </div>

                          <div class="column is-3">
                            <div class="meta">
                              <span>{{ items.namapenerima }}</span>
                              <span>Penerima</span>
                            </div>
                          </div>

                          <div class="column">
                            <VButtons>
                              <VIconButton color="primary" circle icon="feather:edit"
                                v-tooltip.bottom.center="'Edit Barang'" outlined @click="editPenerimaan(items)" />
                              <!-- <VIconButton color="info" circle icon="feather:edit" v-tooltip.bottom.center="'Edit Header'"
                                @click="editHead(items)" /> -->
                              <VIconButton color="danger" circle icon="feather:trash"
                                v-tooltip.bottom.center="'Batal Terima'" :loading="isLoadBtnDelete" outlined
                                @click="dialogConfirm(items)" />
                              <!-- <VIconButton color="success" circle icon="fas fa-undo"
                                v-tooltip.bottom.center="'Retur Barang'" @click="returPenerimaan(items)" /> -->
                              <!-- <VIconButton color="warning" circle icon="fas fa-print"
                                v-tooltip.bottom.center="'Cetak Bukti'" outlined @click="cetakBuktiPopUp(items)" /> -->
                            </VButtons>
                          </div>

                        </div>

                      </a>
                    </div>
                  </div>
                  <div class="columns  is-flex-tablet-p" style="margin-top: -2.5rem;" v-if="items.isExpand">
                    <div class="column is-12">
                      <div class="flight-summary">
                        <div class="content-wrap is-grey is-fullwidth" style="width: 100%;">
                          <div class="column is-4">
                            <VControl icon="feather:search">
                              <input v-model="items.search" class="input custom-text-filter" placeholder="Search..." />
                            </VControl>
                          </div>
                          <VCard custom="card-green" class="mt-1">
                            <div class="columns is-multiline"
                              v-for="(itemsDet, index2)  in filteredDetails(items.details, items.search)" :key="index2">
                              <div class="column is-3">
                                <VField>
                                  <VLabelText>Nama Produk
                                  </VLabelText>
                                  <label>{{ itemsDet.namaproduk }} | {{ itemsDet.satuanstandar }}</label>
                                </VField>
                              </div>
                              <div class="column is-2 ml-4">
                                <VField>
                                  <VLabelText>Satuan </VLabelText>
                                  <VLabel>{{ itemsDet.satuan ? itemsDet.satuan : '-' }}
                                  </VLabel>
                                </VField>
                              </div>
                              
                              <div class="column is-1 ml-4">
                                <VField>
                                  <VLabelText>Qty Produk </VLabelText>
                                  <VLabel>{{ itemsDet.jumlah }}
                                  </VLabel>
                                </VField>
                              </div>
                             
                            </div>
                          </VCard>

                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
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


    </VCard>
  </div>

  <Dialog v-model:visible="modalEditHead" header="Edit Header" :style="{ width: '70vw' }" modal>
    <VTabs slider type="rounded" selected="penerimaan" :tabs="[
      { label: 'Penerimaan', value: 'penerimaan' },
      { label: 'Faktur', value: 'faktur' },
      { label: 'Data PO', value: 'po' },
    ]">
      <template #tab="{ activeValue }">
        <p v-if="activeValue === 'penerimaan'">
        <div class="column is-12">
          <div class="columns">
            <div class="column is-4">
              <VDatePicker v-model="item.tglTerima" color="green" trim-weeks>
                <template #default="{ inputValue, inputEvents }">
                  <VField>
                    <VLabel class="required-field">Tanggal Terima</VLabel>
                    <VControl icon="feather:calendar">
                      <VInput type="text" placeholder="Select a date" disabled class="is-rounded" :value="inputValue"
                        v-on="inputEvents" />
                    </VControl>
                  </VField>
                </template>
              </VDatePicker>
            </div>
            <div class="column is-4">
              <VField class="is-rounded-select is-autocomplete-select">
                <VLabel class="required-field">Gudang</VLabel>
                <VControl icon="feather:search" fullwidth class="prime-auto-select">
                  <Dropdown v-model="item.gudang" :options="d_Gudang" optionLabel="label" class="is-rounded"
                    placeholder="Pilih Gudang" style="width: 100%;" :filter="true" />
                </VControl>
              </VField>
            </div>
            <div class="column is-4">
              <VField class="is-rounded-select is-autocomplete-select">
                <VLabel class="required-field">Pegawai Penerima</VLabel>
                <VControl icon="feather:search" fullwidth class="prime-auto-select">
                  <Dropdown v-model="item.penerimaan" :options="d_Pegawai" optionLabel="label" class="is-rounded"
                    placeholder="Pilih Pegawai Penerima" style="width: 100%;" :filter="true" />
                </VControl>
              </VField>
            </div>
          </div>
          <div class="columns">
            <div class="column is-3 pt-1">
              <VField class="is-rounded-select is-autocomplete-select">
                <VLabel class="required-field">Kelompok Barang</VLabel>
                <VControl icon="feather:search" fullwidth class="prime-auto-select">
                  <Dropdown v-model="item.kelompokbarang" :options="d_kelompokBarang" optionLabel="label"
                    class="is-rounded" placeholder="Pilih Kelompok Barang" style="width: 100%;" :filter="true" />
                </VControl>
              </VField>
            </div>
            <div class="column is-3 pt-1">
              <VField class="is-rounded-select is-autocomplete-select">
                <VLabel class="required-field">Sumber Dana</VLabel>
                <VControl icon="feather:search" fullwidth class="prime-auto-select">
                  <Dropdown v-model="item.sumberdana" :options="d_SumberDana" optionLabel="label" class="is-rounded"
                    placeholder="Pilih Sumber Dana" style="width: 100%;" :filter="true" />
                </VControl>
              </VField>
            </div>
            <div class="column is-6 pt-0" v-if="item.kaskecil == true">
              <div class="columns">
                <div class="column is-6">
                  <VField>
                    <VLabel class="required-field">No Bukti</VLabel>
                    <VControl>
                      <input v-model="item.noBuktiKK" type="text" class="input is-rounded" placeholder="No Dokumen" />
                    </VControl>
                  </VField>
                </div>
              </div>
            </div>
          </div>
          <div class="columns" v-if="item.kaskecil == true">
            <div class="column pt-1 is-3">
              <VDatePicker v-model="item.tanggalKK" color="green" trim-weeks>
                <template #default="{ inputValue, inputEvents }">
                  <VField>
                    <VLabel class="required-field">Tanggal</VLabel>
                    <VControl icon="feather:calendar">
                      <VInput type="text" placeholder="Select a date" class="is-rounded" :value="inputValue"
                        v-on="inputEvents" />
                    </VControl>
                  </VField>
                </template>
              </VDatePicker>
            </div>
            <div class="column is-4 pt-1">
              <VField class="is-rounded-select is-autocomplete-select">
                <VLabel class="required-field">Ruangan</VLabel>
                <VControl icon="feather:search" fullwidth class="prime-auto-select">
                  <Dropdown v-model="item.ruanganKK" :options="d_Gudang" optionLabel="label" class="is-rounded"
                    placeholder="Pilih data" style="width: 100%;" :filter="true" />
                </VControl>
              </VField>
            </div>
            <div class="column is-4 pt-1">
              <VField class="is-rounded-select is-autocomplete-select">
                <VLabel class="required-field">Pegawai</VLabel>
                <VControl icon="feather:search" fullwidth class="prime-auto-select">
                  <Dropdown v-model="item.pegawaiKK" :options="d_komit" optionLabel="label" class="is-rounded"
                    placeholder="Pilih data" style="width: 100%;" :filter="true" />
                </VControl>
              </VField>
            </div>
          </div>
        </div>
        </p>

        <p v-else-if="activeValue === 'faktur'">
        <div class="column is-12">
          <div class="columns">
            <div class="column is-2">
              <VDatePicker v-model="item.tglFaktur" color="green" trim-weeks>
                <template #default="{ inputValue, inputEvents }">
                  <VField>
                    <VLabel class="required-field">Tanggal Faktur</VLabel>
                    <VControl icon="feather:calendar">
                      <VInput type="text" placeholder="Select a date" class="is-rounded" :value="inputValue"
                        v-on="inputEvents" />
                    </VControl>
                  </VField>
                </template>
              </VDatePicker>
            </div>
            <div class="column is-5">
              <VField>
                <VLabel class="required-field">No Faktur</VLabel>
                <VControl>
                  <input v-model="item.noFaktur" type="text" class="input is-rounded" placeholder="No Dokumen" />
                </VControl>
              </VField>
            </div>
            <div class="column is-2 p-0" style="margin-top: 2rem;">
              <VField>
                <VControl raw subcontrol>
                  <VCheckbox label="otomatis" @change="noSurat(item)" v-model="item.noOtom" color="info" square />
                </VControl>
              </VField>
            </div>
            <div class="column p-0" style="margin-top: 2rem;">
              <VField>
                <VControl raw subcontrol>
                  <VCheckbox v-model="item.nothingFaktur" label="Belum ada no faktur" @change="noSurat(item)"
                    color="info" square />
                </VControl>
              </VField>
            </div>
          </div>
          <div class="columns">
            <div class="column is-5">
              <VField class="is-rounded-select is-autocomplete-select">
                <VLabel class="required-field">Nama Suplier</VLabel>
                <VControl icon="feather:search" fullwidth class="prime-auto-select">
                  <Dropdown v-model="item.supplier" :options="d_suplier" optionLabel="label" class="is-rounded"
                    placeholder="Pilih Suplier" style="width: 100%;" :filter="true" />
                </VControl>
              </VField>
            </div>
            <div class="column is-4">
              <VDatePicker v-model="item.tglTempo" color="green" trim-weeks>
                <template #default="{ inputValue, inputEvents }">
                  <VField>
                    <VLabel>Tanggal Jatuh Tempo</VLabel>
                    <VControl icon="feather:calendar">
                      <VInput type="text" placeholder="Select a date" class="is-rounded" :value="inputValue"
                        v-on="inputEvents" />
                    </VControl>
                  </VField>
                </template>
              </VDatePicker>
            </div>
          </div>
        </div>
        </p>

        <p v-else-if="activeValue === 'po'">
        <div class="column is-12">
          <div class="columns">
            <div class="column is-3">
              <VDatePicker v-model="item.tanggalPo" color="green" trim-weeks>
                <template #default="{ inputValue, inputEvents }">
                  <VField>
                    <VLabel>Tanggal</VLabel>
                    <VControl icon="feather:calendar">
                      <VInput type="text" placeholder="Select a date" class="is-rounded" :value="inputValue"
                        v-on="inputEvents" />
                    </VControl>
                  </VField>
                </template>
              </VDatePicker>
            </div>
            <div class="column is-5">
              <VField>
                <VLabel>No Usulan</VLabel>
                <VControl>
                  <input v-model="item.nousulan" type="text" class="input is-rounded" placeholder="No Usulan..." />
                </VControl>
              </VField>
            </div>
          </div>
          <div class="columns">
            <div class="column is-5 pt-0">
              <VField>
                <VLabel>Nama Pengadaan</VLabel>
                <VControl>
                  <input v-model="item.namapengadaan" type="text" class="input is-rounded"
                    placeholder="Nama Pengadaan..." />
                </VControl>
              </VField>
            </div>
            <div class="column is-7 pt-0">
              <VField>
                <VLabel>No Kontrak</VLabel>
                <VControl>
                  <input v-model="item.nokontrak" type="text" class="input is-rounded" placeholder="No Kontrak" />
                </VControl>
              </VField>
            </div>
          </div>
        </div>
        </p>
      </template>
    </VTabs>
    <template #footer>
      <VButton raised class="mr-3" @click="clear()">Batal</VButton>
      <VButton icon="feather:plus" color="primary" raised @click="updateHead(item)">Update
      </VButton>
    </template>
  </Dialog>

  <Dialog v-model:visible="modalCetakBukti" modal header="Tanda Tangan" :style="{ width: '50vw' }">
    <div class="column is-12">
      <div class="columns">
        <div class="column is-6">
          <Fieldset legend="Petugas Penyerahan" style="padding:10px">
            <div class="column pt-0">
              <VField class="is-rounded-select is-autocomplete-select" label="Jabatan">

                <VControl icon="feather:search">
                  <Multiselect mode="single" v-model="item.jabatanpenyerah" :options="d_jabatan"
                    placeholder="Pilih jabatan" :searchable="true" />
                </VControl>
              </VField>
            </div>
            <div class="column pt-1">
              <VField class="is-rounded-select is-autocomplete-select">
                <VLabel class="required-field">Pegawai</VLabel>
                <VControl icon="feather:search">
                  <Multiselect mode="single" v-model="item.pegawaipenyerah" :options="d_komit"
                    placeholder="Pilih pegawai" :searchable="true" />
                </VControl>
              </VField>
            </div>
          </Fieldset>
        </div>
        <div class="column is-6">
          <Fieldset legend="Petugas Penerima" style="padding:10px">
            <div class="column pt-0">
              <VField class="is-rounded-select is-autocomplete-select" label="Jabatan">
                <VControl icon="feather:search">
                  <Multiselect mode="single" v-model="item.jabatanpenerima" :options="d_jabatan"
                    placeholder="Pilih jabatan" :searchable="true" />
                </VControl>
              </VField>
            </div>
            <div class="column pt-1">
              <VField class="is-rounded-select is-autocomplete-select">
                <VLabel class="required-field">Pegawai</VLabel>
                <VControl icon="feather:search">
                  <Multiselect mode="single" v-model="item.pegawaipenerima" :options="d_komit"
                    placeholder="Pilih pegawai" :searchable="true" />
                </VControl>
              </VField>
            </div>
          </Fieldset>
        </div>
      </div>
      <div class="column is-12 p-0">
        <Fieldset legend="Mengetahui" style="padding:10px">
          <div class="column pt-0">
            <VField class="is-rounded-select is-autocomplete-select" label="Jabatan">
              <VControl icon="feather:search">
                <Multiselect mode="single" v-model="item.jabatanketahui" :options="d_jabatan"
                  placeholder="Pilih jabatan" :searchable="true" />
              </VControl>
            </VField>
          </div>
          <div class="column pt-1">
            <VField class="is-rounded-select is-autocomplete-select">
              <VLabel class="required-field">Pegawai</VLabel>
              <VControl icon="feather:search">
                <Multiselect mode="single" v-model="item.pegawaiketahui" :options="d_komit" placeholder="Pilih pegawai"
                  :searchable="true" />
              </VControl>
            </VField>
          </div>
        </Fieldset>
      </div>
    </div>
    <template #footer>
      <VButton raised class="mr-3" @click="modalCetakBukti = false">Batal</VButton>
      <VButton icon="feather:printer" color="primary" raised @click="cetakBukti(item)">Cetak</VButton>
    </template>
  </Dialog>
</template>

<script setup lang="ts">
import { useWindowScroll } from '@vueuse/core'
import { useApi } from '/@src/composable/useApi'
import { reactive, ref, computed, defineComponent, watch } from 'vue'
import { useRouter, useRoute, RouterLink } from 'vue-router';
import { useConfirm } from 'primevue/useconfirm'
import { useHead } from '@vueuse/head'
import { useToaster } from '/@src/composable/toaster'
import Fieldset from 'primevue/fieldset';
import moment from 'moment'
import * as H from '/@src/utils/appHelper'
import ConfirmDialog from 'primevue/confirmdialog'
import Dialog from 'primevue/dialog';
import DataTable from 'primevue/datatable'
import Dropdown from 'primevue/dropdown'
import Column from 'primevue/column'

import { useViewWrapper } from '/@src/stores/viewWrapper'
import { booleanTypeAnnotation } from '@babel/types';

useHead({
  title: 'Penerimaan Darah - ' + import.meta.env.VITE_PROJECT,
})

useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)

const item: any = ref({
  aktif: true,
  isKK: false,
  filterTgl: reactive({
    start: new Date(),
    end: new Date(),
  }),
})

const route = useRoute()
const router = useRouter()
let isLoadingBtn: any = ref(false)
let isLoadData: any = ref(true)
let idSatuanAsal: any
const confirm = useConfirm()
const d_Pegawai = ref([])
const d_SumberDana = ref([])
const d_kelompokBarang = ref([])
const d_komit = ref([])
const d_Gudang = ref([])
const d_suplier = ref([])
const d_jabatan = ref([])
const noBukti: any = ref()
const filters = ref('')
const dataSource: any = ref([])
const isLoadBtnDelete: any = ref(false)
const detailPenerimaan: any = ref([])
const detailPenerimaans: any = ref([])
const modalEditHead: any = ref(false)
const modalCetakBukti: any = ref(false)
let d_Produk: any = ref([])
let d_Rekanan: any = ref([])
let isLoading: any = ref(false)
let dataLength: any = ref()


const detailfiltered = computed(() => {
    if (!filters.value) {
        return detailPenerimaans.value
    }

    return detailPenerimaans.value.filter((items: any) => {
        return (
            items.namaproduk.match(new RegExp(filters.value, 'i'))
        )
    })
})


const fetchData = async () => {

  isLoadData.value = true
  let tglAwal = 'tglAwal=' + H.formatDate(item.value.filterTgl.start, 'YYYY-MM-DD')
  let tglAkhir = '&tglAkhir=' + H.formatDate(item.value.filterTgl.end, 'YYYY-MM-DD')
  let idRekanan = item.value.rekanan ? `&rekananfk=${item.value.rekanan}` : ''
  let noDok = item.value.nodokumen ? `&nodokumen=${item.value.nodokumen}` : ''
  let limit: any = currentPage.value.limit
  let offset: any = route.query.page ? route.query.page : 1
  offset = (offset * limit) - limit
  isLoadingBtn.value = true
  await useApi().get(`/bank-darah/get-daftar-penerimaan?${tglAwal}${tglAkhir}${idRekanan}${noDok}&limit=${limit}&offset=${offset}`).then((response) => {
    response.daftar.forEach((element: any, i: any) => {
      element.no = i + 1
      element.nosppb = element.nosppb ? element.nosppb : '',
        element.tglTerima = H.formatDate(element.tglfaktur, 'DD/MM/YYYY')
      element.totalTagihan = H.formatRp(item.totalAll, `Rp.${element.totalharusdibayar}`)
    });
    isLoadData.value = false
    isLoadingBtn.value = false
    dataLength.value = response.daftar.length
    dataSource.value = response.daftar
    dataSource.value.total = response.total
    route.query.page = '1'
  })

  let objSave = {
    tglAwal:  H.formatDate(item.value.filterTgl.start, 'YYYY-MM-DD') + ' 00:00:00',
    tglAkhir:  H.formatDate(item.value.filterTgl.end, 'YYYY-MM-DD')  + ' 23:59:59',
  }
  useApi().postNoMessage('/general/save-jurnal-penerimaan-barang', objSave )


}

const listProduk = async () => {
  await useApi().get('/logistik/penerimaan-barang/get-rekanan').then((response) => {
    // d_Produk.value = response.produk.map((e: any) => {
    //   return { label: e.namaproduk, value: e.id }
    // })
    d_Rekanan.value = response.rekanan.map((e: any) => {
      return { label: e.namarekanan, value: e.id }
    })
  })
}

const showDetail = (e:any)=>{
  e.isExpand = true
  detailPenerimaans.value = e.details

}

const dialogConfirm = (e: any) => {
  confirm.require({
    message: 'Apakah anda yakin menghapus data ini ?',
    header: 'Konfirmasi Hapus Data',
    icon: 'pi pi-info-circle',
    acceptClass: 'p-button-danger',
    accept: () => {
      deletePenerimaan(e)
    },
    reject: () => { },
  })
}

const deletePenerimaan = async (e: any) => {
  isLoadBtnDelete.value = true
  await useApi().post('bank-darah/delete-penerimaan-darah', { 'nostruk': e.norec }).then((response) => {
    console.log(response)
    isLoadBtnDelete.value = false
    fetchData()
  }).catch((err: any) => {
    console.log(err)
  })
}

const cetakBuktiPopUp = (e: any) => {
  item.value.norec = e.norec
  modalCetakBukti.value = true
}


const filteredDetails = (details:any, search:any)=> {
    const filterText = search ? search.toLowerCase():''
    return details.filter((detail:any) => detail.namaproduk.toLowerCase().includes(filterText));
}

const editPenerimaan = (e: any) => {
  router.push({
    name: 'module-darah-form-penerimaan-darah',
    query: {
      norec: e.norec,
    },
  })
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
// listDataCombo()
fetchData()

</script>
<style lang="scss">
@import '/@src/scss/abstracts/all';

@import '/@src/scss/components/forms-outer';
@import '/@src/scss/custom/config';
@import '/@src/scss/module/logistik/penerimaan-barang-suplier.scss';

.c-title {
  margin-left: -21px;
  padding-top: 21px;
  padding-top: 18px;
  margin-top: -21px;
  border-top-left-radius: 11px;
  border-left: solid hsl(19deg 100% 75% / 72%) 3px;
  padding-bottom: 0px;
  margin-bottom: 2rem;
}
</style>
