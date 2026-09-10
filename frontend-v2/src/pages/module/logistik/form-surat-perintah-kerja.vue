<template>
  <ConfirmDialog />
  <div>
    <div class="form-layout is-stacked">
      <div class="form-outer" style="margin-top: 15px">
        <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
          <div class="form-header-inner">
            <div class="left">
              <h3>Form Surat Perintah Kerja</h3>
            </div>
            <div class="right">
              <div class="buttons">
                <VButton icon="lnir lnir-arrow-left rem-100" light dark-outlined RouterLink
                  :to="{ name: 'module-logistik-daftar-surat-perintah-kerja' }">
                  Kembali
                </VButton>
                <div>
                  <VButton type="button" :loading="isLoadBtnSave" rounded outlined color="primary" raised
                    icon="feather:save" @click="saveData(item)" :disabled="isDisabled">
                    Simpan
                  </VButton>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="form-body p-4">
          <VTabs slider selected="penerimaan" :tabs="[
            { label: 'Usulan', value: 'penerimaan' },
            { label: 'Supplier', value: 'faktur' },
            { label: 'Mengetahui', value: 'po' },
          ]">
            <template #tab="{ activeValue }">
              <p v-if="activeValue === 'penerimaan'">
              <div style="margin-top:2.8rem" v-if="suratPerintahKerja.loading">
                <VPlaceloadWrap class="mt-5">
                  <VPlaceload height="30px" width="40%" class="mx-2" />
                  <VPlaceload height="30px" width="30%" class="mx-2" />
                  <VPlaceload height="30px" width="40%" class="mx-2" />
                </VPlaceloadWrap>
                <VPlaceloadWrap class="mt-5">
                  <VPlaceload height="30px" width="50%" class="mx-2" />
                  <VPlaceload height="30px" width="30%" class="mx-2" />
                  <VPlaceload height="30px" width="20%" class="mx-2" />
                </VPlaceloadWrap>
              </div>
              <div class="column is-12" v-else>
                <div class="columns is-multiline">
                  <div class="column is-3">
                    <VField>
                      <VLabel>No Usulan</VLabel>
                      <VControl>
                        <input v-model="item.noUsulan" type="text" class="input is-rounded" />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-3">
                    <VField>
                      <VLabel>No SPK</VLabel>
                      <VControl>
                        <input v-model="item.noSpk" type="text" class="input is-rounded" />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-3">
                    <VDatePicker v-model="item.tglUsulan" color="green" trim-weeks>
                      <template #default="{ inputValue, inputEvents }">
                        <VField>
                          <VLabel>Tanggal Usulan</VLabel>
                          <VControl icon="feather:calendar">
                            <VInput type="text" placeholder="Select a date" class="is-rounded" :value="inputValue"
                              v-on="inputEvents" />
                          </VControl>
                        </VField>
                      </template>
                    </VDatePicker>
                  </div>
                  <div class="column is-3">
                    <VDatePicker v-model="item.tglKontrak" color="green" trim-weeks>
                      <template #default="{ inputValue, inputEvents }">
                        <VField>
                          <VLabel>Tanggal Kontrak</VLabel>
                          <VControl icon="feather:calendar">
                            <VInput type="text" placeholder="Select a date" class="is-rounded" :value="inputValue"
                              v-on="inputEvents" />
                          </VControl>
                        </VField>
                      </template>
                    </VDatePicker>
                  </div>
                </div>

                <div class="columns is-multiline">
                  <div class="column is-4 pt-0">
                    <VField>
                      <VLabel>No Kontrak</VLabel>
                      <VControl>
                        <input v-model="item.noKontrak" type="text" class="input is-rounded" />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-8 pt-0">
                    <VField>
                      <VLabel>Keterangan</VLabel>
                      <VControl>
                        <input v-model="item.keterangan" type="text" class="input is-rounded" />
                      </VControl>
                    </VField>
                  </div>
                </div>
              </div>

              </p>
              <p v-else-if="activeValue === 'faktur'">
              <div style="margin-top:2.8rem" v-if="suratPerintahKerja.loading">
                <VPlaceloadWrap class="mt-5">
                  <VPlaceload height="30px" width="40%" class="mx-2" />
                  <VPlaceload height="30px" width="30%" class="mx-2" />
                  <VPlaceload height="30px" width="40%" class="mx-2" />
                </VPlaceloadWrap>
                <VPlaceloadWrap class="mt-5">
                  <VPlaceload height="30px" width="60%" class="mx-2" />
                  <VPlaceload height="30px" width="30%" class="mx-2" />
                </VPlaceloadWrap>
              </div>
              <div class="column is-12" v-else>
                <div class="columns is-multiline">
                  <div class="column is-3">
                    <VField class="is-rounded-select is-autocomplete-select">
                      <VLabel>Supplier Barang</VLabel>
                      <VControl icon="feather:search" fullwidth class="prime-auto-select">
                        <Dropdown v-model="item.supplierBarang" :options="d_suplier" optionLabel="label"
                          class="is-rounded" placeholder="Pilih data" style="width: 100%;" :filter="true" />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-3">
                    <VField class="is-rounded-select is-autocomplete-select">
                      <VLabel>Koordinator Barang</VLabel>
                      <VControl icon="feather:search" fullwidth class="prime-auto-select">
                        <Dropdown v-model="item.koordinator" :options="d_koordinator" optionLabel="label"
                          class="is-rounded" placeholder="Pilih data" style="width: 100%;" :filter="true" />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-3">
                    <VField class="is-rounded-select is-autocomplete-select">
                      <VLabel>Unit Pengusul</VLabel>
                      <VControl icon="feather:search" fullwidth class="prime-auto-select">
                        <Dropdown v-model="item.unitPengusul" :options="d_UnitPengusul" optionLabel="label"
                          class="is-rounded" placeholder="Pilih data" style="width: 100%;" :filter="true" />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-3">
                    <VField class="is-rounded-select is-autocomplete-select">
                      <VLabel>Unit Tujuan</VLabel>
                      <VControl icon="feather:search" fullwidth class="prime-auto-select">
                        <Dropdown v-model="item.unitTujuan" :options="d_UnitTujuan" optionLabel="label" class="is-rounded"
                          placeholder="Pilih data" style="width: 100%;" :filter="true" />
                      </VControl>
                    </VField>
                  </div>
                </div>
                <div class="columns is-multiline">
                  <div class="column is-5">
                    <VField label="Penanggung Jawab" class="is-rounded-select is-autocomplete-select">
                      <VControl icon="fa:user" class="prime-auto-cus ">
                        <AutoComplete v-model="item.penangungJawab" :suggestions="d_Pegawai" :optionLabel="'label'"
                          @complete="fetchPegawai($event)" :dropdown="true" :minLength="3" :appendTo="'body'"
                          :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="Pilih Penanggung Jawab"
                          class="is-rounded" />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-3">
                    <VField>
                      <VLabel>Biaya Kirim</VLabel>
                      <VControl :loading="loadNBK">
                        <input v-model="item.biayaKirim" type="text" class="input is-rounded" placeholder="No Dokumen" />
                      </VControl>
                    </VField>
                  </div>
                </div>
              </div>
              </p>

              <p v-else-if="activeValue === 'po'">
              <div style="margin-top:2.8rem" v-if="suratPerintahKerja.loading">
                <VPlaceloadWrap class="mt-5">
                  <VPlaceload height="30px" width="40%" class="mx-2" />
                  <VPlaceload height="30px" width="60%" class="mx-2" />
                </VPlaceloadWrap>
                <VPlaceloadWrap class="mt-5">
                  <VPlaceload height="30px" width="60%" class="mx-2" />
                  <VPlaceload height="30px" width="40%" class="mx-2" />
                </VPlaceloadWrap>
              </div>
              <div class="column is-12" v-else>
                <div class="columns is-multiline">
                  <div class="column is-4">
                    <VField label="Mengetahui" class="is-rounded-select is-autocomplete-select">
                      <VControl icon="fa:user" class="prime-auto-cus ">
                        <AutoComplete v-model="item.mengetahui" :suggestions="d_Pegawai" :optionLabel="'label'"
                          @complete="fetchPegawai($event)" :dropdown="true" :minLength="3" :appendTo="'body'"
                          :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="Pilih Pegawai"
                          class="is-rounded" />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-3">
                    <VDatePicker v-model="item.tglDibutuhkan" color="green" trim-weeks>
                      <template #default="{ inputValue, inputEvents }">
                        <VField>
                          <VLabel>Tanggal Dibutuhkan</VLabel>
                          <VControl icon="feather:calendar">
                            <VInput type="text" placeholder="Select a date" class="is-rounded" :value="inputValue"
                              v-on="inputEvents" />
                          </VControl>
                        </VField>
                      </template>
                    </VDatePicker>
                    <!-- <VField>
                      <VLabel>NIP</VLabel>
                      <VControl :loading="loadNBK">
                        <input v-model="item.nip" type="text" class="input is-rounded" placeholder="NIP" />
                      </VControl>
                    </VField> -->
                  </div>
                  <div class="column is-4">
                    <VField label="Pembuat PPK" class="is-rounded-select is-autocomplete-select">
                      <VControl icon="fa:user" class="prime-auto-cus ">
                        <AutoComplete v-model="item.pembuatPpk" :suggestions="d_Pegawai" :optionLabel="'label'"
                          @complete="fetchPegawai($event)" :dropdown="true" :minLength="3" :appendTo="'body'"
                          :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="Pilih Pembuat PPK"
                          class="is-rounded" />
                      </VControl>
                    </VField>
                  </div>
                </div>
                <div class="columns is-multiline">
                  <div class="column is-3">
                    <VField class="is-rounded-select is-autocomplete-select">
                      <VLabel>Jenis Pengendali</VLabel>
                      <VControl icon="feather:search" fullwidth class="prime-auto-select">
                        <Dropdown v-model="item.jnsPengendali" :options="d_JenisPengendali" optionLabel="label"
                          class="is-rounded" placeholder="Pilih Jenis Pengendali" style="width: 100%;" :filter="true" />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-5">
                    <VField label="Staff Pengendali" class="is-rounded-select is-autocomplete-select">
                      <VControl icon="fa:user" class="prime-auto-cus ">
                        <AutoComplete v-model="item.stafPengendali" :suggestions="d_Pegawai" :optionLabel="'label'"
                          @complete="fetchPegawai($event)" :dropdown="true" :minLength="3" :appendTo="'body'"
                          :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="Pilih Staff Pengendali"
                          class="is-rounded" />
                      </VControl>
                    </VField>
                  </div>
                </div>
              </div>

              </p>
            </template>
          </VTabs>
        </div>
        <div class="column is-12 pt-0">
          <div class="column is-12">
            <div class="content">
              <div class="is-divider label-border" data-content="Total Keseluruhan" />
            </div>
          </div>
          <div class="columns is-multiline pl-3 pr-3 pb-4">
            <div class="column is-4">
              <VField class="is-rounded-select is-autocomplete-select">
                <VLabel>Mata Anggaran</VLabel>
                <VControl icon="feather:search" fullwidth class="prime-auto-select">
                  <Dropdown v-model="item.mataAnggaran" :options="d_MataAnggaran" optionLabel="label" class="is-rounded"
                    placeholder="Pilih Mata Anggaran" style="width: 100%;" :filter="true" />
                </VControl>
              </VField>
            </div>
            <div class="column is-4">
              <VField>
                <VLabel>Saldo BLU</VLabel>
                <VControl :loading="loadNBK">
                  <input v-model="item.saldoBlu" type="text" class="input is-rounded" placeholder="Saldo BLU" />
                </VControl>
              </VField>
            </div>
            <div class="column is-4">
              <VField>
                <VLabel>Saldo RM</VLabel>
                <VControl :loading="loadNBK">
                  <input v-model="item.saldoRm" type="text" class="input is-rounded" placeholder="Saldo RM" />
                </VControl>
              </VField>
            </div>
          </div>
        </div>
      </div>

      <div class="column is-12 p-0 mt-5">
        <VCard>
          <div class="column is-2 p-0 pb-2" style="margin-left: auto">
            <VButton type="button" icon="fas fa-plus-circle" @click="showModal()"
              class="is-fullwidth is-outlined is-primary mt-4" rounded raised>
              Tambah
            </VButton>
          </div>
          <DataTable :value="suratPerintahKerja" :paginator="true" :rows="5" :rowsPerPageOptions="[5, 10, 25]"
            :loading="suratPerintahKerja.loading" class="p-datatable-sm" tableStyle="min-width: 10rem"
            paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
            sortMode="multiple" currentPageReportTemplate="Showing {first} to {last} of {totalRecords}" showGridlines>
            <Column field="no" header="No" style="min-width: 10px;" frozen></Column>
            <Column field="namaproduk" header="Nama Produk" style="min-width: 300px;" frozen class="font-bold"></Column>
            <Column field="satuan" header="Satuan" style="min-width: 80px;"></Column>
            <Column field="jumlah" header="Qty" style="min-width: 50px;"></Column>
            <Column field="hargasatuan" header="Harga Satuan" style="min-width: 150px;text-align:right">
              <template #body="slotProps">
                {{ H.formatRupiah(H.roundToDecimal(parseFloat(slotProps.data.hargasatuan), 2), '') }}
              </template>
            </Column>
            <Column field="hargadiskon" header="Harga Diskon" style="min-width: 130px;text-align:right">
             <template #body="slotProps">
                  {{ H.formatRupiah(H.roundToDecimal(parseFloat(slotProps.data.hargadiskon), 2), '') }}
              </template>
            </Column>
            <Column field="nilaippn" header="PPN">
               <template #body="slotProps">
                    {{ H.formatRupiah(H.roundToDecimal(parseFloat(slotProps.data.nilaippn), 2), '') }}
                </template>
            </Column>
            <Column field="spesifikasi" header="Deskripsi"></Column>
            <Column field="subtotal" header="SubTotal" style="min-width: 150px;text-align:right">
              <template #body="slotProps">
                      {{ H.formatRupiah(H.roundToDecimal(parseFloat(slotProps.data.subtotal), 2), '') }}
                  </template>
            </Column>
            <Column :exportable="false" header="Action" style="text-align: center;min-width: 100px;">
              <template #body="slotProps">
                <VIconButton type="button" icon="feather:edit" class="mr-3" color="info" circle outlined raised
                  :loading="suratPerintahKerja.loadingBtn" v-tooltip.top="'Edit'" @click="showModal(slotProps.data)">
                </VIconButton>
                <VIconButton type="button" icon="fas fa-trash" color="danger" circle outlined raised
                  v-tooltip.top="'Hapus'" @click="dialogConfirm(slotProps.data)">
                </VIconButton>
              </template>
            </Column>
          </DataTable>
        </VCard>
      </div>

      <div class="column is-12">
        <div class="content">
          <div class="is-divider" data-content="Total Keseluruhan" />
        </div>
      </div>

      <div class="column is-12 p-0">
        <div class="columns is-multiline">
          <div class="column is-3">
            <VCardCustom :style="'padding:5px 25px'">
              <div class="label-status primary">
                <i aria-hidden="true" class="fas fa-circle"></i>
                <span class="ml-1">SUB TOTAL</span>
              </div>
              <!-- <small>{{ item.subtotal }}</small> -->
              <small class="text-bold-custom h-100">{{
                H.formatRp(item.totalsub, 'Rp.')
              }}</small>
            </VCardCustom>
          </div>
          <div class="column is-3">
            <VCardCustom :style="'padding:5px 25px'">
              <div class="label-status info">
                <i aria-hidden="true" class="fas fa-circle"></i>
                <span class="ml-1">DISKON</span>
              </div>
              <small class="text-bold-custom h-100">{{
                H.formatRp(item.discount, 'Rp.')
              }}</small>
            </VCardCustom>
          </div>
          <div class="column is-3">
            <VCardCustom :style="'padding:5px 25px'">
              <div class="label-status danger">
                <i aria-hidden="true" class="fas fa-circle"></i>
                <span class="ml-1">PPN</span>
              </div>
              <small class="text-bold-custom h-100">{{
                H.formatRp(item.ppnTotal, 'Rp.')
              }}</small>
            </VCardCustom>
          </div>
          <div class="column is-3">
            <VCardCustom :style="'padding:5px 25px'">
              <div class="label-status" color="danger">
                <i aria-hidden="true" class="fas fa-circle"></i>
                <span class="ml-1">TOTAL</span>
              </div>
              <small class="text-bold-custom h-100">{{
                H.formatRp(item.totalall, 'Rp.')
              }}</small>
            </VCardCustom>
          </div>
        </div>
      </div>

      <div class="content">
        <div class="is-divider" />
      </div>
    </div>
  </div>

  <VModal is="form" :open="modalInput" title="Form Input Produk" size="large" actions="right"
    @close="modalInput = false, clear()">
    <template #content>
      <div class="columns is-multiline">
        <div class="column is-3">
          <VDatePicker v-model="item.tglKebutuhan" color="green" trim-weeks>
            <template #default="{ inputValue, inputEvents }">
              <VField>
                <VLabel>Tanggal Kebutuhan</VLabel>
                <VControl icon="feather:calendar">
                  <VInput type="text" placeholder="Select a date" class="is-rounded" :value="inputValue"
                    v-on="inputEvents" />
                </VControl>
              </VField>
            </template>
          </VDatePicker>
        </div>
        <div class="column is-6">
          <VField class="is-autocomplete-select" label="Produk">
            <VControl icon="feather:search">
              <AutoComplete v-model="item.produk" :suggestions="d_Produk" @complete="getProduk($event)"
                :optionLabel="'namaproduk'" :dropdown="true" :minLength="3" :appendTo="'body'"
                @item-select="getSatuan(item.produk)" :loadingIcon="'pi pi-spinner'" :field="'namaproduk'"
                class="is-rounded" placeholder="Cari Nama Obat" />
            </VControl>
          </VField>
        </div>
        <div class="column is-3">
          <VField class="is-rounded-select is-autocomplete-select">
            <VLabel>Satuan</VLabel>
            <VControl icon="feather:search" fullwidth class="prime-auto-select">
              <Dropdown v-model="item.satuan" :options="d_Satuan" optionLabel="label" class="is-rounded"
                placeholder="Pilih data" style="width: 100%;" :filter="true" @change="getKonversi(item.satuan)" />
            </VControl>
          </VField>
        </div>
      </div>

      <div class="columns is-multiline">
        <div class="column is-2">
          <VField>
            <VLabel>Jumlah</VLabel>
            <VControl>
              <input v-model="item.jmlProduk" type="text" class="input is-rounded" placeholder="Jumlah" />
            </VControl>
          </VField>
        </div>
        <div class="column is-2">
          <VField>
            <VLabel>Harga</VLabel>
            <VControl>
              <input v-model="item.hargaProduk" type="text" class="input is-rounded" placeholder="Harga" />
            </VControl>
          </VField>
        </div>
        <div class="column is-2">
          <VField>
            <VLabel>Diskon %</VLabel>
            <VControl>
              <input v-model="item.diskonPersen" type="text" class="input is-rounded" placeholder="Diskon %" />
            </VControl>
          </VField>
        </div>
        <div class="column is-2">
          <VField>
            <VLabel>Diskon</VLabel>
            <VControl>
              <input v-model="item.nilaiDiskon" disabled type="text" class="input is-rounded" placeholder="Diskon" />
            </VControl>
          </VField>
        </div>
        <div class="column is-2">
          <VField>
            <VLabel>PPN %</VLabel>
            <VControl>
              <input v-model="item.ppnPersen" type="text" class="input is-rounded" placeholder="PPN %" />
            </VControl>
          </VField>
        </div>
        <div class="column is-2">
          <VField>
            <VLabel>PPN</VLabel>
            <VControl>
              <input v-model="item.nilaippn" disabled type="text" class="input is-rounded" placeholder="PPN" />
            </VControl>
          </VField>
        </div>
      </div>

      <div class="columns is-multiline">
        <div class="column is-8">
          <VField>
            <VLabel>Spesifikasi</VLabel>
            <VControl>
              <input v-model="item.spesifikasi" type="text" class="input is-rounded" placeholder="Spesifikasi" />
            </VControl>
          </VField>
        </div>
        <div class="column is-4">
          <VField>
            <VLabel>Sub Total</VLabel>
            <VControl>
              <input v-model="item.subTotal" type="text" class="input is-rounded" placeholder="sub total" />
            </VControl>
          </VField>
        </div>
      </div>
    </template>
    <template #action>
      <VButton color="primary" raised @click="addData(item)">
        Simpan
      </VButton>
    </template>
  </VModal>
</template>

<script setup lang="ts">
import { useWindowScroll } from '@vueuse/core'
import { useApi } from '/@src/composable/useApi'
import { ref, computed, watch } from 'vue'
import { useRouter, useRoute } from 'vue-router'
import { useHead } from '@vueuse/head'
import { useToaster } from '/@src/composable/toaster'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useThemeColors } from '/@src/composable/useThemeColors'
import { useConfirm } from 'primevue/useconfirm'
import ConfirmDialog from 'primevue/confirmdialog'
import moment from 'moment'
import AutoComplete from 'primevue/autocomplete';
import Checkbox from 'primevue/checkbox';
import * as H from '/@src/utils/appHelper'
import Dialog from 'primevue/dialog';
import DataTable from 'primevue/datatable'
import Dropdown from 'primevue/dropdown'
import Column from 'primevue/column'
// import FileUpload from 'primevue/fileupload';
useHead({
  title: 'Penerimaan Barang Supplier - ' + import.meta.env.VITE_PROJECT,
})
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)

const isLoadingPasien: any = ref(false)
const confirm = useConfirm();
const router = useRouter();
const route = useRoute()

const item: any = ref({
  kelompokbarang: 24,
  kaskecil: false,
  tglTerima: new Date(),
  tglFaktur: new Date(),
  tanggalPo: new Date(),
})

const d_SumberDana = ref([])
const d_kelompokBarang = ref([])
// const d_Berkas: any = ref([])
// const fileFaktur: any = ref()
// const input: any = ref({})
// const modalInputFile = ref(false)
const d_SupplierBarang = ref([])
const d_koordinator = ref([])
const d_UnitPengusul = ref([])
const d_UnitTujuan = ref([])
const d_JenisPengendali = ref([])
const d_MataAnggaran = ref([])
const d_Pegawai = ref([])
const d_komit = ref([])
const d_Gudang = ref([])
const d_suplier = ref([])
const d_Produk = ref([])
const d_Satuan: any = ref([])
const colors: any = ref(Object.keys(useThemeColors()))
const dataProdukDetail: any = ref([])
const modalInput = ref(false)
const isDisabled = ref(false)
const isLoadPrice = ref(false)
const listColor: any = ref([])
const nostruk: any = ref()
const noBukti: any = ref()
let suratPerintahKerja: any = ref([])
let isLoadingBtn: any = ref(false)
let isLoadBtnSave: any = ref(false)
let loadNBK: any = ref(false)
let isLoadProduk: any = ref(false)

for (let i = 0; i < colors.value.length; i++) {
  const element = colors.value[i]
  if (i <= 9 && element != 'primary') listColor.value.push(element)
}
const { y } = useWindowScroll()
const isStuck = computed(() => {
  return y.value > 30
})

const saveData = async () => {

  let strukOrder = {
    norec: item.value.norec ? item.value.norec : '',
    keteranganorder: item.value.keterangan,
    qtyjenisproduk: suratPerintahKerja.value.length,
    tglUsulan: H.formatDate(item.value.tglUsulan, 'YYYY-MM-DD HH:mm:ss'),
    tglDibutuhkan: H.formatDate(item.value.tglDibutuhkan, 'YYYY-MM-DD HH:mm:ss'),
    koordinator: item.value.koordinator.label,
    nousulan: item.value.noUsulan,
    ruanganfkPengusul: item.value.unitPengusul.value,
    ruanganfkTujuan: item.value.unitTujuan.value,
    penanggungjawabfk: item.value.penangungJawab.value,
    objectpegawaispkfk: item.value.pembuatPpk.value,
    nokontrakspk: item.value.noKontrak,
    rekananfk: item.value.supplierBarang.value,
    tglkontrak: H.formatDate(item.value.tglKontrak, 'YYYY-MM-DD HH:mm:ss'),
    mengetahuifk: item.value.mengetahui.value,
    total: parseFloat(item.value.totalall),
    ppn: parseFloat(item.value.ppnTotal),
    objectmataanggaranfk: item.value.mataAnggaran.value,
    biayakirim: parseFloat(item.value.biayaKirim),
    jenispengendalifk: item.value.jnsPengendali.value,
    pegawaipengendalifk: item.value.stafPengendali.value,
    norecrealisasi: '',
  }

  let objSave = {
    'strukorder': strukOrder,
    'details': suratPerintahKerja.value
  }

  await useApi().post('logistik/save-spk', objSave).then((response) => {

  })
}

const showModal = async (e: any) => {

  if (e) {
    suratPerintahKerja.value.loadingBtn = true
    let filter = { query: e.namaproduk }
    await getProduk(filter)
    d_Produk.value.forEach(element => {
      item.value.produk = element
      getSatuan(element)
    });

    item.value.no = e.no
    item.value.tglKebutuhan = e.tglkebutuhan
    item.value.produkfk = e.produkfk
    item.value.satuan = { label: e.satuan, value: e.satuanstandarfk }
    item.value.satuanstandarfk = e.satuanstandarfk
    item.value.jmlProduk = e.jumlah
    item.value.hargaProduk = e.hargasatuan
    item.value.konversi = e.nilaikonversi
    item.value.subTotal = e.subtotal
    item.value.spesifikasi = e.spesifikasi
    item.value.diskonPersen = e.persendiscount
    item.value.nilaiDiskon = e.hargadiskon
    item.value.ppnPersen = e.persenppn
    item.value.nilaippn = e.nilaippn
    item.value.tglKebutuhan = e.tglkebutuhan
    modalInput.value = true
    suratPerintahKerja.value.loadingBtn = false
  } else {
    modalInput.value = true
  }
}

const addData = (e: any) => {
  if (!e.produk) {
    useToaster().error('Produk Tidak Boleh Kosong')
    return
  }
  if (!e.jmlProduk) {
    useToaster().error('Jumlah Tidak Boleh Kosong')
    return
  }

  let datas: any = {}
  if (e.no) {
    suratPerintahKerja.value.forEach((element: any, i: any) => {
      if (element.no == e.no) {
        datas.no = element.no,
          datas.namaproduk = e.produk.namaproduk,
          datas.produk = e.produk,
          datas.produkfk = e.produk.id,
          datas.satuan = e.satuan.label,
          datas.satuanstandarfk = e.satuan.value,
          datas.jumlah = e.jmlProduk,
          datas.hargasatuan = e.hargaProduk,
          datas.nilaikonversi = item.value.konversi,
          datas.subtotal = parseFloat(e.subTotal),
          datas.spesifikasi = e.spesifikasi,
          datas.persendiscount = e.diskonPersen ? e.diskonPersen : 0,
          datas.hargadiskon = e.nilaiDiskon ? e.nilaiDiskon : 0,
          datas.persenppn = e.ppnPersen ? e.ppnPersen : 0,
          datas.nilaippn = e.nilaippn ? e.nilaippn : 0,
          datas.tglkebutuhan = e.tglKebutuhan ? H.formatDate(e.tglKebutuhan, 'YYYY-MM-DD HH:mm:ss') : '',
          suratPerintahKerja.value[i] = datas
      }
    });
  } else {
    datas = {
      no: suratPerintahKerja.value.length == 0 ? 1 : suratPerintahKerja.value.length + 1,
      namaproduk: e.produk.namaproduk,
      produk: e.produk,
      produkfk: e.produk.id,
      satuan: e.satuan.label,
      satuanstandarfk: e.satuan.value,
      jumlah: e.jmlProduk,
      hargasatuan: e.hargaProduk,
      nilaikonversi: item.value.konversi,
      subtotal: parseFloat(e.subTotal),
      spesifikasi: e.spesifikasi,
      persendiscount: e.diskonPersen ? e.diskonPersen : 0,
      hargadiskon: e.nilaiDiskon ? e.nilaiDiskon : 0,
      persenppn: e.ppnPersen ? e.ppnPersen : 0,
      nilaippn: e.nilaippn ? e.nilaippn : 0,
      tglkebutuhan: e.tglKebutuhan ? H.formatDate(e.tglKebutuhan, 'YYYY-MM-DD HH:mm:ss') : '',
    }
    suratPerintahKerja.value.push(datas)
  }
  if (suratPerintahKerja.value.length > 0) {
    clear()
  }
  count()
}

const fetchPegawai = async (filter: any) => {
  // let data = filter.query ? filter.query : filter
  await useApi().get(
    `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`
  ).then((response) => {
    d_Pegawai.value = response
  })
}


const dialogConfirm = (e: any) => {
  confirm.require({
    message: 'Apakah anda serius menghapus data ini ?',
    header: 'Konfirmasi Hapus Data',
    icon: 'pi pi-info-circle',
    acceptClass: 'p-button-danger',
    accept: () => {
      suratPerintahKerja.value.forEach((element: any, i: any) => {
        if (element.no == e.no) {
          suratPerintahKerja.value.splice(i, 1)
        }
        element.no - 1

      })
      count()
      clear()
    },
    reject: () => { },
  })
}

const listData = async () => {
  await useApi()
    .get('logistik/combo-surat-perintah-kerja').then((response) => {
      d_suplier.value = response.rekanan.map((e: any) => {
        return { label: e.namarekanan, value: e.id }
      })
      d_UnitPengusul.value = response.unitPengusul.map((e: any) => {
        return { label: e.namaruangan, value: e.id }
      })
      d_UnitTujuan.value = response.ruanganAll.map((e: any) => {
        return { label: e.namaruangan, value: e.id }
      })
      d_JenisPengendali.value = response.jenisPengendali.map((e: any) => {
        return { label: e.pengendali, value: e.id }
      })
      d_koordinator.value = response.jenisUsulan.map((e: any) => {
        return { label: e.jenisusulan, value: e.id }
      })
      d_MataAnggaran.value = response.mataAnggaran.map((e: any) => {
        return { label: e.namamataanggaran, value: e.id }
      })

    })
}

const getProduk = async (filter: any) => {
  let response = await useApi().get(`/logistik/get-data-produk?namaproduk=${filter.query}`)
  d_Produk.value = response
}

const isEditPenerimaan = async () => {
  const norec = route.query.norec
  if (norec) {
    suratPerintahKerja.value.loading = true
    await useApi().get(`/logistik/get-detail-data-spk?norec=${norec}`).then((response) => {
      let header = response.detail
      let details = response.details
      d_koordinator.value.forEach((element: any) => {
        if (element.label == header.keteranganlainnya) {
          item.value.koordinator = element
        }
      })
      item.value.norec = header.norec
      item.value.noUsulan = header.nousulan
      item.value.noSpk = header.noorder
      item.value.noSpk = header.noorder
      item.value.tglUsulan = header.tglusulan
      item.value.tglKontrak = header.tglkontrak
      item.value.noKontrak = header.nokontrak
      item.value.keterangan = header.keterangan
      item.value.supplierBarang = { label: header.namarekanan, value: header.rekananid }
      item.value.unitPengusul = { label: header.unitpengusul, value: header.idunitpengusul }
      item.value.unitTujuan = { label: header.unittujuan, value: header.idunittujuan }
      item.value.penangungJawab = { label: header.penanggungjawab, value: header.penanggungjawabid }
      item.value.mataAnggaran = { label: header.mataanggaran, value: header.mataanggaranid }
      item.value.biayaKirim = header.totalbiayakirim
      item.value.mengetahui = { label: header.pegawaimengetahui, value: header.pegawaimengetahuiid }
      item.value.pembuatPpk = header.pegawaispk ? { label: header.pegawaispk, value: header.idpegawaispk } : ''
      item.value.stafPengendali = header.objectpegawaipengendalifk ? { label: header.pegawaipengendali, value: header.objectpegawaipengendalifk } : ''
      item.value.jnsPengendali = header.jenispengendalifk ? { label: header.pengendali, value: header.jenispengendalifk } : ''
      item.value.tglDibutuhkan = header.tglusulan

      suratPerintahKerja.value = details
      count()
    }).catch((err) => {
      console.log(err)
    })
    suratPerintahKerja.value.loading = false
  }
}

const clear = () => {
  delete item.value.no
  delete item.value.tglKebutuhan
  delete item.value.produk
  delete item.value.satuan
  delete item.value.jmlProduk
  delete item.value.hargaProduk
  delete item.value.ppnPersen
  delete item.value.nilaippn
  delete item.value.diskonPersen
  delete item.value.nilaiDiskon
  delete item.value.spesifikasi
  delete item.value.subTotal
}

const noSurat = (e: any) => {
  if (e.noOtom) {
    createNoFaktur()
  } else {
    delete item.value.noFaktur
  }

  if (item.value.nothingFaktur) {
    item.value.noFaktur = '-'
    e.noOtom = false
  }
}

const noBuktiKK = async (e: any) => {
  if (e == true && !route.query.norec) {
    loadNBK.value = true
    await useApi()
      .get(`logistik/penerimaan-barang/get-no-terima?asalproduk=${item.value.sumberdana.value}`)
      .then((response) => {
        item.value.noBuktiKK = response.noBuktiKK
      })
    loadNBK.value = false
    return
  }
  if (e == true && route.query.norec) {
    item.value.noBuktiKK = noBukti.value
  } else {
    delete item.value.noBuktiKK
  }
}

const createNoFaktur = () => {
  /* Format No Faktur PB/BLN-THN/APT/NO URUT (APT = BLU, BG = Hibah,  KK = Kas  Kecil) */
  let nows = moment(new Date()).format('MM-YY')
  if (item.value.sumberdana.value === 1) {
    item.value.noFaktur = 'PB/' + nows + '/APT/____'
  } else if (item.value.sumberdana.value === 3) {
    item.value.noFaktur = 'PB/' + nows + '/BG/____'
  } else if (item.value.sumberdana.value === 7) {
    item.value.noFaktur = 'PB/' + nows + '/KK/____'
  } else {
    delete item.value.noFaktur
  }
}

const getSatuan = async (e: any) => {
  if (e.konversisatuan != 0) {
    d_Satuan.value = e.konversisatuan.map((element: any) => {
      return { label: element.satuanstandar.toUpperCase(), value: element.ssid, konversi: element.nilaikonversi }
    })
    d_Satuan.value.forEach((data: any) => {
      if (data.value == e.ssid) {
        item.value.satuan = data
        item.value.konversi = data.konversi
        return
      }
    })

  } else {
    d_Satuan.value = [{ label: e.satuanstandar.toUpperCase(), value: e.ssid }]
    item.value.konversi = e.konversisatuan.length == 0 ? 1 : e.konversisatuan
    d_Satuan.value.forEach((data: any) => {
      if (data.value == e.ssid) {
        item.value.satuan = data
        return
      }
    })
  }
}

const getKonversi = (e: any) => {
  item.value.konversi = e.konversi
}

const count = () => {
  let totalsub = 0
  let discount = 0
  let ppn = 0
  let total = 0
  suratPerintahKerja.value.forEach((element: any) => {
    totalsub = totalsub + parseFloat(element.subtotal)
    discount = element.hargadiskon === '' ? discount : discount + parseFloat(element.hargadiskon)
    ppn = element.nilaippn === '' ? ppn : ppn + parseFloat(element.nilaippn)
    total = totalsub - discount + ppn
  })
  item.value.totalsub = totalsub
  item.value.discount = discount
  item.value.ppnTotal = ppn
  item.value.totalall = total
}

watch(
  () => [
    item.value.ppnPersen,
    item.value.hargaProduk,
    item.value.diskonPersen,
    item.value.jmlProduk,
  ],
  () => {
    if (item.value.hargaProduk === undefined || item.value.hargaProduk === '') {
      item.value.subTotal = ''
    } else {
      const diskon: any = item.value.diskonPersen == '' ? delete item.value.diskonPersen : item.value.diskonPersen / 100
      const nilaiDiskon: any = parseFloat(item.value.jmlProduk) * parseFloat(item.value.hargaProduk) * diskon
      const resultHarga: any = nilaiDiskon ? parseFloat(item.value.jmlProduk) * parseFloat(item.value.hargaProduk) - parseFloat(nilaiDiskon)
        : parseFloat(item.value.jmlProduk) * parseFloat(item.value.hargaProduk)
      const ppn: any = item.value.ppnPersen / 100
      const nilaippn = resultHarga * ppn
      item.value.nilaiDiskon = diskon ? nilaiDiskon : ''
      item.value.nilaippn = ppn ? nilaippn : ''
      item.value.subTotal = nilaippn ? parseFloat(resultHarga) + nilaippn : resultHarga
    }
  }
)

listData()
isEditPenerimaan()

</script>


<style lang="scss">
@import '/@src/scss/abstracts/all';
@import '/@src/scss/components/forms-outer';
@import '/@src/scss/custom/config';

.tabs-inner {
  margin-right: 0 !important;
}

.button.v-button {
  padding: 8px 22px;
  height: 38px;
  line-height: 1.1;
  font-size: 0.95rem;
  font-family: var(--font);
  transition: all 0.3s;
}

.label-border::after {
  background: var(--background-grey) !important;
  font-weight: 600 !important;
}


.p-dialog-content {
  overflow-y: unset;
}
</style>

