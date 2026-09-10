<template>
  <ConfirmDialog />
  <div>
    <div class="form-layout is-stacked">
      <div class="form-outer" style="margin-top: 15px">
        <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
          <div class="form-header-inner">
            <div class="left">
              <h3>Permintaan Pengiriman Barang</h3>
            </div>
            <div class="right">
              <div class="buttons">
                <VButton icon="lnir lnir-arrow-left rem-100" light dark-outlined RouterLink
                  :to="{ name: 'module-dashboard-logistik' }">
                  Kembali
                </VButton>
                <div>
                  <VButton v-if="nostruk != null" type="button" rounded outlined color="primary" raised
                    icon="feather:save" @click="savePemesanan(item)" :loading="isLoadBtnSave">
                    update
                  </VButton>
                  <VButton v-else type="button" :loading="isLoadBtnSave" rounded outlined color="primary" raised
                    icon="feather:save" @click="savePemesanan(item)" :disabled="isDisabled">
                    Simpan
                  </VButton>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="form-body p-4">
          <VTabs slider selected="penerimaan" :tabs="[
            { label: 'Detail Pengadaan', value: 'penerimaan' },
            { label: 'Detail Penerima', value: 'faktur' },
            { label: 'Detail Rekanan', value: 'po' },
          ]">
            <template #tab="{ activeValue }">
              <p v-if="activeValue === 'penerimaan'">
              <div style="margin-top:2.8rem" v-if="pemesananSuplier.loading">
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
                  <div class="column is-4">
                    <VDatePicker color="green" style="display:none" trim-weeks>
                      <template #default="{ inputValue, inputEvents }">
                        <VField>
                          <VLabel class="required-field">Tanggal</VLabel>
                          <VControl icon="feather:calendar">
                            <VInput type="text" placeholder="Select a date" class="is-rounded" :value="inputValue" />
                          </VControl>
                        </VField>
                      </template>
                    </VDatePicker>
                    <VDatePicker v-model="item.tglusulan" color="green" trim-weeks>
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
                  <div class="column is-4">
                    <VField class="is-rounded-select is-autocomplete-select">
                      <VLabel class="required-field">Koordinator</VLabel>
                      <VControl icon="feather:search" fullwidth class="prime-auto-select">
                        <Dropdown v-model="item.koordinator" :options="d_koordinator" optionLabel="label"
                          class="is-rounded" placeholder="Pilih" style="width: 100%;" :filter="true" />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-4">
                    <VField>
                      <VLabel class="required-field">No. Usulan</VLabel>
                      <VControl>
                        <input v-model="item.nousulan" type="text" class="input is-rounded" placeholder="No Usulan" />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-5">
                    <VField>
                      <VLabel class="required-field">Nama Pengadaan</VLabel>
                      <VControl>
                        <input v-model="item.namaPengadaan" type="text" class="input is-rounded"
                          placeholder="Nama Pengadaan" />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-4">
                    <VField>
                      <VLabel class="required-field">No. Kontrak</VLabel>
                      <VControl>
                        <input v-model="item.noKontrak" type="text" class="input is-rounded" placeholder="No Kontak" />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-2">
                    <VField>
                      <VLabel class="required-field">Tahun</VLabel>
                      <VControl>
                        <input v-model="item.tahunKontrak" type="text" class="input is-rounded" placeholder="Tahun" />
                      </VControl>
                    </VField>
                  </div>

                </div>
              </div>

              </p>
              <p v-else-if="activeValue === 'faktur'">
              <div style="margin-top:2.8rem" v-if="pemesananSuplier.loading">
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
                  <div class="column is-2">
                    <VDatePicker style="display:none" color="green" trim-weeks>
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
                    <VDatePicker v-model="item.tglorder" color="green" trim-weeks>
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
                  <div class="column is-4">
                    <VField>
                      <VLabel class="required-field">No. SPPB</VLabel>
                      <VControl>
                        <input v-model="item.noOrder" type="text" class="input is-rounded" placeholder="No. SPPB" />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-6">
                    <VField>
                      <VLabel class="required-field">Keterangan</VLabel>
                      <VControl>
                        <input v-model="item.keteranganorder" type="text" class="input is-rounded"
                          placeholder="Keterangan" />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-4">
                    <VField class="is-rounded-select is-autocomplete-select">
                      <VLabel class="required-field">Pegawai Pembuat Komitmen</VLabel>
                      <VControl icon="feather:search" fullwidth class="prime-auto-select">
                        <Dropdown v-model="item.pegawaiPembuat" :options="d_Pegawai" optionLabel="label"
                          class="is-rounded" placeholder="Pilih Pegawai Penerima" style="width: 100%;" :filter="true" />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-3 pt-1">
                    <VField class="is-rounded-select is-autocomplete-select">
                      <VLabel class="required-field">Sumber Dana</VLabel>
                      <VControl icon="feather:search" fullwidth class="prime-auto-select">
                        <Dropdown v-model="item.asalProduk" :options="d_SumberDana" optionLabel="label" class="is-rounded"
                          placeholder="Pilih Sumber Dana" style="width: 100%;" :filter="true" />
                      </VControl>
                    </VField>
                  </div>
                </div>

              </div>
              </p>

              <p v-else-if="activeValue === 'po'">
              <div style="margin-top:2.8rem" v-if="pemesananSuplier.loading">
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
                    <VField class="is-rounded-select is-autocomplete-select">
                      <VLabel class="required-field">Nama Perusahaan</VLabel>
                      <VControl icon="feather:search" fullwidth class="prime-auto-select">
                        <Dropdown v-model="item.suplier" :options="d_suplier" optionLabel="label" class="is-rounded"
                          placeholder="Pilih Perusahaan" style="width: 100%;" :filter="true"
                          @change="changeRekanan(item.suplier)" />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-4">
                    <VField>
                      <VLabel>No. Telpon</VLabel>
                      <VControl>
                        <input v-model="item.telepon" type="text" class="input is-rounded" placeholder="No Telepon..." />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-4">
                    <VField>
                      <VLabel>Alamat</VLabel>
                      <VControl>
                        <input v-model="item.alamat" type="text" class="input is-rounded" placeholder="Alamat..." />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-3">
                    <VField class="is-rounded-select is-autocomplete-select">
                      <VLabel>Jenis Pengendali</VLabel>
                      <VControl icon="feather:search" fullwidth class="prime-auto-select">
                        <Dropdown v-model="item.pengendali" :options="d_pengendali" optionLabel="label" class="is-rounded"
                          placeholder="Jenis Pengendali" style="width: 100%;" :filter="true" />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-4">
                    <VField class="is-rounded-select is-autocomplete-select">
                      <VLabel class="required-field">Staff Pengendali</VLabel>
                      <VControl icon="feather:search" fullwidth class="prime-auto-select">
                        <Dropdown v-model="item.staff" :options="d_Pegawai" optionLabel="label" class="is-rounded"
                          placeholder="Pilih Pegawai Penerima" style="width: 100%;" :filter="true" />
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
              <div class="is-divider label-border" data-content="Anggaran" />
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
                <VControl>
                  <input v-model="item.saldoBlu" type="text" class="input is-rounded" placeholder="Saldo BLU" />
                </VControl>
              </VField>
            </div>
            <div class="column is-4">
              <VField>
                <VLabel>Saldo RM</VLabel>
                <VControl>
                  <input v-model="item.saldoRm" type="text" class="input is-rounded" placeholder="Saldo RM" />
                </VControl>
              </VField>
            </div>
          </div>
        </div>
        <div class="column is-12 pt-0">
          <div class="column is-12">
            <div class="content">
              <div class="is-divider label-border" data-content="Waktu Pengiriman" />
            </div>
          </div>
          <div class="columns is-multiline pl-3 pr-3 pb-4">
            <div class="column is-4">
              <span> Diserahkan paling lambat (Dalam Hari): </span>
            </div>
            <div class="column is-4" style="margin-top: -1rem; margin-left: -5rem;">
              <VField>
                <VControl>
                  <input v-model="item.jmlHari" type="text" class="input is-rounded" placeholder="Jumlah Hari" />
                </VControl>
              </VField>
            </div>
          </div>
        </div>
      </div>

      <div class="column is-12 p-0 mt-5">
        <VCard>
          <h3> Detail Barang </h3>
          <div class="column is-2 p-0 pb-2" style="margin-left: auto">
            <VButton type="button" icon="feather:x-circle" @click="modalTambah(item)"
              class="is-fullwidth is-outlined is-primary mt-4" rounded raised>
              Tambah
            </VButton>
          </div>
          <DataTable :value="pemesananSuplier" :paginator="true" :rows="5" :rowsPerPageOptions="[5, 10, 25]"
            :loading="pemesananSuplier.loading" class="p-datatable-sm" tableStyle="min-width: 10rem"
            paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
            sortMode="multiple" currentPageReportTemplate="Showing {first} to {last} of {totalRecords}" showGridlines>
            <Column field="no" header="No" style="min-width: 10px;" frozen></Column>
            <Column field="namaproduk" header="Nama Produk" style="min-width: 300px;" frozen class="font-bold"></Column>
            <Column field="satuan" header="Satuan" style="min-width: 80px;"></Column>
            <Column field="jumlah" header="Qty" style="min-width: 50px;"></Column>
            <Column field="hargaProduk" header="Harga Satuan" style="min-width: 50px;"></Column>
            <Column field="hargadiskon" header="Harga Diskon" style="min-width: 130px;"></Column>
            <Column field="nilaippn" header="PPN"></Column>
            <Column field="spesifikasi" header="Deskripsi"></Column>
            <Column field="subtotal" header="SubTotal" style="min-width: 200px;"></Column>
            <Column :exportable="false" header="Action" style="text-align: center;min-width: 100px;">
              <template #body="slotProps">
                <VIconButton type="button" icon="feather:edit" class="mr-3" color="info" circle outlined raised
                  :loading="isLoadingBtn" v-tooltip.top="'Edit'" @click="editDataSupplier(slotProps.data)">
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

  <VModal is="form" :open="modalInput" title="Input Data" size="large" actions="right"
    @close="modalInput = false, clear()">
    <template #content>
      <div class="columns is-multiline">
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
                placeholder="Pilih data" style="width: 100%;" :filter="true" />
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
  title: 'Pemesanan Barang Supplier - ' + import.meta.env.VITE_PROJECT,
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
  tglorder: new Date(),
  tglFaktur: new Date(),
  tanggalPo: new Date(),
})

const d_Pegawai = ref([])
const d_SumberDana = ref([])
const d_kelompokBarang = ref([])

const d_koordinator = ref([])
const d_pengendali = ref([])
const d_MataAnggaran = ref([])
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
let pemesananSuplier: any = ref([])
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

const savePemesanan = async (e: any) => {

  // if (!item.value.tglTerima) {
  //   useToaster().error('Tanggal Terima Tidak Boleh Kosong')
  //   return
  // }
  // if (!item.value.gudang) {
  //   useToaster().error('Gudang Tidak Boleh Kosong')
  //   return
  // }
  // if (!item.value.penerimaan) {
  //   useToaster().error('Pegawai Penerima Tidak Boleh Kosong')
  //   return
  // }
  // if (!item.value.kelompokbarang) {
  //   useToaster().error('kelompok Barang Tidak Boleh Kosong')
  //   return
  // }
  // if (!item.value.sumberdana) {
  //   useToaster().error('Sumber Dana Tidak Boleh Kosong')
  //   return
  // }

  // if (item.value.sumberdana == 7) {
  //   if (!item.value.noBuktiKK) {
  //     useToaster().error('No Bukti Tidak Boleh Kosong')
  //     return
  //   }
  //   if (!item.value.tanggalKK) {
  //     useToaster().error('Tanggal Kas Kecil Tidak Boleh Kosong')
  //     return
  //   }
  //   if (!item.value.ruanganKK) {
  //     useToaster().error('Ruangan Kas Kecil Tidak Boleh Kosong')
  //     return
  //   }
  //   if (!item.value.pegawaiKK) {
  //     useToaster().error('pegawai Kas Kecil Tidak Boleh Kosong')
  //     return
  //   }
  // }

  // if (!item.value.tglFaktur) {
  //   useToaster().error('Tanggal Faktur Tidak Boleh Kosong')
  //   return
  // }
  // if (!item.value.noFaktur) {
  //   useToaster().error('No Faktur Tidak Boleh Kosong')
  //   return
  // }
  // if (!item.value.supplier) {
  //   useToaster().error('Supplier Tidak Boleh Kosong')
  //   return
  // }

  // if (pemesananSuplier.value.length == 0) {
  //   useToaster().error('Data Yang Disimpan Tidak Tersedia')
  //   return
  // }

  isLoadBtnSave.value = true
  const objSave = {
    details: pemesananSuplier.value,
    strukorder: {
      noorder: item.value.noOrder,
      norec: route.query.norec ? route.query.norec : '',
      norecrealisasi: '',
      asalprodukfk: item.value.asalProduk.value,
      pegawaiorderfk: item.value.pegawaiPembuat.value.id,
      keteranganorder: item.value.keteranganorder,
      qtyjenisproduk: pemesananSuplier.value.length,
      alamat: item.value.alamat,
      notelpmobile: item.value.telepon,
      koordinator: item.value.koordinator.label,
      koordinatorid: item.value.koordinator.value,
      tglorder: moment(item.value.tglorder).format('YYYY-MM-DD'),
      tglusulan: moment(item.value.tglusulan).format('YYYY-MM-DD'),
      nousulan: item.value.nousulan,
      namapengadaan: item.value.namaPengadaan,
      nokontrak: item.value.noKontrak,
      tahunusulan: item.value.tahunKontrak,
      namarekanansales: item.value.suplier.label,
      objectrekananfk: item.value.suplier.value.id,
      totaldiscount: item.value.discount ? item.value.discount : 0,
      totalhargasatuan: item.value.totalsub,
      objectmataanggaranfk: item.value.mataAnggaran.value.id,
      totalppn: item.value.ppnTotal ? item.value.ppnTotal : 0,
      pengendali: item.value.pengendali.value.id,
      staffpengendali: item.value.staff.value.id,
      jmlHari: item.value.jmlHari

    },
  }
  await useApi()
    .post('logistik/save-sppb', objSave)
    .then((response) => {
      isLoadBtnSave.value = false
      isDisabled.value = true
    })
    .catch((err) => {
      isLoadBtnSave.value = false
    })
}

const modalTambah = (e: any) => {
  modalInput.value = true
}

const addData = (e: any) => {
  // console.log(e)
  if (!e.produk) {
    useToaster().error('Produk Tidak Boleh Kosong')
    return
  }
  if (!e.jmlProduk) {
    useToaster().error('Jumlah Tidak Boleh Kosong')
    return
  }
  
  let datass: any = {}
  if(e.no){
    pemesananSuplier.value.forEach((element: any, i:any) => {
    if (element.no == item.value.no) {
      datass.no = element.no,
      datass.namaproduk = e.produk.namaproduk,
          datass.produkfk = e.produk.id,
        datass.nilaikonversi = e.konversi,
        datass.produkfk = e.produk.id,
        datass.satuan = e.satuan.label,
        datass.satuanstandarfk = e.satuan.value,
        datass.jumlah = e.jmlProduk,
        datass.hargaProduk = e.hargaProduk,
        datass.subtotal = parseFloat(e.subTotal),
        datass.spesifikasi = e.spesifikasi,
        datass.persendiscount = e.diskonPersen ? e.diskonPersen : 0,
        datass.hargadiskon = e.nilaiDiskon ? e.nilaiDiskon : 0,
        datass.persenppn = e.ppnPersen ? e.ppnPersen : 0,
        datass.nilaippn = e.nilaippn ? e.nilaippn : 0,
        datass.qtyprodukkonfirmasi = e.qtyprodukkonfirmasi ? e.qtyprodukkonfirmasi : 0,

        pemesananSuplier.value[i] = datass
    }
  })
  }
  else {
    let datas = {
    norec_op: '',
    no: pemesananSuplier.value.length == 0 ? 1 : pemesananSuplier.value.length + 1,
    namaproduk: e.produk.namaproduk,
    nilaikonversi: e.konversi,
    produkfk: e.produk.id,
    satuan: e.satuan.label,
    satuanstandarfk: e.satuan.value,
    jumlah: e.jmlProduk,
    hargaProduk: e.hargaProduk,
    subtotal: parseFloat(e.subTotal),
    spesifikasi: e.spesifikasi,
    persendiscount: e.diskonPersen ? e.diskonPersen : 0,
    hargadiskon: e.nilaiDiskon ? e.nilaiDiskon : 0,
    persenppn: e.ppnPersen ? e.ppnPersen : 0,
    nilaippn: e.nilaippn ? e.nilaippn : 0,
    qtyprodukkonfirmasi: e.qtyprodukkonfirmasi ? e.qtyprodukkonfirmasi : 0,
  }
  pemesananSuplier.value.push(datas)
    
  }

  if (pemesananSuplier.value.length > 0) {
    clear()
  }
  count()
}

const editDataSupplier = async (e: any) => {
  isLoadingBtn.value = true
  let filter = { query: e.namaproduk }
  await getProduk(filter)
  modalInput.value = true
  isLoadingBtn.value = false
  item.value.no = e.no
  d_Produk.value.forEach(element => {
    if (element.id == e.produkfk) {
      // console.log(element)
      item.value.produk = element
      getSatuan(element)
    }
    // if(element)
  });
  d_Satuan.value.forEach(element => {
    if (element.value == e.satuanstandarfk) {
      item.value.satuan = element
    }
  });
  item.value.konversi = e.nilaikonversi
  item.value.jumlah = e.jumlah
  item.value.hargasatuan = e.hargasatuan
  item.value.persendiscount = e.persendiscount
  item.value.hargadiskon = e.hargadiskon
  item.value.nobatch = e.nobatch
  item.value.keterangan = e.keterangan
  item.value.persenppn = e.persenppn
  item.value.nilaippn = e.nilaippn
  item.value.subtotal = e.subtotal
  item.value.tglkadaluarsa = e.tglkadaluarsa
}


const updateDataSupplier = (e: any) => {
  if (!e.satuan) {
    useToaster().error('Satuan Produk Tidak Boleh Kosong')
    return
  }
  if (!e.jumlah) {
    useToaster().error('Jumlah Tidak Boleh Kosong')
    return
  }

  let data: any = {}
  pemesananSuplier.value.forEach((element: any, i: any) => {
    if (element.no == e.no) {
      data.no = element.no
      data.produk = e.produk
      data.asalproduk = e.sumberdana
      data.produkfk = e.produk.id
      data.namaproduk = e.produk.namaproduk
      data.satuan = e.satuan.label
      data.ssid = e.satuan.value
      data.satuanstandarfk = e.satuan.value
      data.listSatuan = e.satuan
      data.nilaikonversi = e.konversi
      data.jumlah = e.jumlah
      data.jumlahdipakai = 0
      data.sisa = e.jumlah
      data.hargasatuan = e.hargasatuan
      data.persendiscount = e.persendiscount ? e.persendiscount : '0'
      data.hargadiskon = e.hargadiskon ? e.hargadiskon : '0'
      data.nobatch = e.nobatch ? e.nobatch : '-'
      data.keterangan = e.keterangan ? e.keterangan : ''
      data.persenppn = e.persenppn
      data.nilaippn = e.nilaippn ? e.nilaippn : '0'
      data.subtotal = parseFloat(e.hargasatuan) * parseFloat(e.jumlah)
      data.totalall = e.subtotal
      data.tglkadaluarsa = e.tglkadaluarsa ? H.formatDate(e.tglkadaluarsa, 'MM/DD/YYYY') : null
      pemesananSuplier.value[i] = data
    }
  })
  count()
  clear()
}

const dialogConfirm = (e: any) => {
  confirm.require({
    message: 'Apakah anda serius menghapus data ini ?',
    header: 'Konfirmasi Hapus Data',
    icon: 'pi pi-info-circle',
    acceptClass: 'p-button-danger',
    accept: () => {
      for (var i = pemesananSuplier.value.length - 1; i >= 0; i--) {
        if (pemesananSuplier.value[i].no == e.no) {
          pemesananSuplier.value.splice(i, 1)
        }
      }
      count()
      clear()
    },
    reject: () => { },
  })
}

const listData = async () => {
  await useApi()
    .get('logistik/cbo-spbb')
    .then((response) => {
      d_komit.value = response.pembuatkomit.map((e: any) => {
        return { label: e.namalengkap, value: e }
      })
      d_Pegawai.value = response.pegawai.map((e: any) => {
        return { label: e.namalengkap, value: e }
      })
      d_SumberDana.value = response.sumberdana.map((e: any) => {
        return { label: e.asalproduk, value: e.id }
      })
      d_koordinator.value = response.koordinator.map((e: any) => {
        return { label: e.jenisusulan, value: e.id }
      })
      d_suplier.value = response.suplier.map((e: any) => {
        return { label: e.namarekanan, value: e }
      })
      d_pengendali.value = response.pengendali.map((e: any) => {
        return { label: e.pengendali, value: e }
      })
      d_MataAnggaran.value = response.pengendali.map((e: any) => {
        return { label: e.pengendali, value: e }
      })


    })
}

const isEditPemesanan = async () => {
  const norec = route.query.norec
  if (norec) {
    pemesananSuplier.value.loading = true
    await listData()
    await useApi().get(`/logistik/detail-sppb?norecOrder=${norec}`).then((response) => {
      let header = response.detail
      let dataBarang = response.details
      d_koordinator.value.forEach((element: any) => {
        if (element.label == header.keteranganlainnya) {
          item.value.koordinator = element
        }
      })
    
      item.value.tglorder = header.tglorder
      item.value.tglusulan = header.tglusulan
      item.value.nousulan = header.nousulan
      item.value.namaPengadaan = header.namapengadaan
      item.value.noKontrak = header.nokontrak
      item.value.tahunKontrak = header.tahunusulan
      item.value.namapengadaan = header.namapengadaan
      item.value.noOrder = header.noorder
      item.value.keteranganorder = header.keteranganorder
      item.value.pegawaiPembuat = { label: header.namalengkap, value: header.objectpegawaifk }
      dataBarang.forEach((data: any, i: any) => {
        data.no = i + 1
        item.value.produk = data.produkfk
      });
      pemesananSuplier.value = dataBarang
      count()
      // isLoadData.value = false
      pemesananSuplier.value.loading = false
  
    })
      .catch((err) => {
        console.log(err)
      })
  } else {
    await listData()
    d_Pegawai.value.forEach((element: any) => {
      item.value.penerimaan = element
      return
    })
    d_kelompokBarang.value.forEach((element: any) => {
      if (element.value == 24) {
        item.value.kelompokbarang = element
        return
      }
    })
  }
}




const clear = () => {
  delete item.value.no
  delete item.value.produk
  delete item.value.satuan
  delete item.value.konversi
  delete item.value.jumlah
  delete item.value.hargasatuan
  delete item.value.persendiscount
  delete item.value.hargadiskon
  delete item.value.nobatch
  delete item.value.tglkadaluarsa
  delete item.value.keterangan
  delete item.value.persenppn
  delete item.value.nilaippn
  delete item.value.subtotal
  delete item.value.tglkadaluarsa

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
    d_Satuan.value.forEach((data: any) => {
      if (data.value == e.ssid) {
        item.value.satuan = data
        item.value.konversi = 1
        return
      }
    })
  }
  isLoadPrice.value = true
  await useApi()
    .get('/logistik/penerimaan-barang/get-produkdetail?produkfk=' + e.id)
    .then((response) => {
      let data = response.detail[0]
      item.value.hargasatuan = data ? data.harga : ''
      isLoadPrice.value = false
    })
}

const getKonversi = (e: any) => {
  item.value.konversi = e.konversi
}


const getProduk = async (filter: any) => {
  await useApi().get(`/logistik/get-data-produk?namaproduk=${filter.query}`).then((response) => {
    d_Produk.value = response
  })
}

const changeRekanan = async (e: any) => {
  await useApi().get(`/logistik/rekanan-detail?idrekanan=${item.value.suplier.value.id}`).then((response) => {
    item.value.alamat = response.alamatlengkap
    item.value.telepon = response.telepon
  })
}


const count = () => {
  console.log(pemesananSuplier)
  let totalsub = 0
  let discount = 0
  let ppn = 0
  let total = 0
  pemesananSuplier.value.forEach((element: any) => {
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
isEditPemesanan()

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


.p-dialog-content {
  overflow-y: unset;
}
</style>
  
  