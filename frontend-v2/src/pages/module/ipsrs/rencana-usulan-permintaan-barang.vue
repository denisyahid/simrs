<template>
  <ConfirmDialog />
  <div>
    <div class="form-layout is-stacked">
      <div class="form-outer" style="margin-top: 15px">
        <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
          <div class="form-header-inner">
            <div class="left">
              <h3>Rencana Usulan Permintaan Barang/Jasa</h3>
            </div>
            <div class="right">
              <div class="buttons">
                <VButton icon="lnir lnir-arrow-left rem-100" light dark-outlined RouterLink
                  :to="{ name: 'module-ipsrs-daftar-rencana-usulan-permintaan-barang' }">
                  Kembali
                </VButton>
                <div>
                  <VButton v-if="nostruk != null" type="button" rounded outlined color="primary" raised
                    icon="feather:save" @click="saveData(item)" :loading="isLoadBtnSave">
                    update
                  </VButton>
                  <VButton v-else type="button" :loading="isLoadBtnSave" rounded outlined color="primary" raised
                    icon="feather:save" @click="saveData(item)" :disabled="isDisabled">
                    Simpan
                  </VButton>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="form-body p-4">
          <VTabs slider selected="order" :tabs="[
          { label: 'Order', value: 'order' },
          { label: 'Unit', value: 'unit' },
          { label: 'Penanggung Jawab', value: 'po' },
        ]">
            <template #tab="{ activeValue }">
              <p v-if="activeValue === 'order'">
              <div style="margin-top:2.8rem" v-if="permintaanBarang.loading">
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
                  <div class="column is-3 pt-1">
                    <VField class="is-rounded-select is-autocomplete-select">
                      <VLabel class="required-field">Kelompok Barang</VLabel>
                      <VControl icon="feather:search" fullwidth class="prime-auto-select">
                        <Dropdown v-model="item.kelompokbarang" :options="d_kelompokBarang" optionLabel="label"
                          @change="generateNoUsuslan()" class="is-rounded" placeholder="Pilih Kelompok Barang"
                          style="width: 100%;" :filter="true" />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-3 pt-1">
                    <VField class="is-rounded-select is-autocomplete-select">
                      <VLabel class="required-field">No Usulan</VLabel>
                      <VInput class="is-rounded" v-model="item.noUsulan"></VInput>
                    </VField>
                  </div>
                  <div class="column is-2 pt-5">
                    <VControl raw subcontrol>
                      <VCheckbox label="otomatis" v-model="item.otomatis" color="info" square />
                    </VControl>
                    <!-- <VCheckbox v-model="item.otomatis" class="mt-4" @change="generateNoUsuslan(item.kelompokbarang)"
                      :true-value="true" label="Otomatis" color="primary" circle /> -->
                  </div>
                  <div class="column is-4 pt-1">
                    <VField class="is-rounded-select is-autocomplete-select">
                      <VLabel class="required-field">Jenis Usulan</VLabel>
                      <VInput class="is-rounded" v-model="item.keteranganUsulan"></VInput>
                    </VField>
                  </div>

                </div>
                <div class="columns">
                  <div class="column is-3">
                    <VDatePicker v-model="item.tglUsulan" color="green" trim-weeks mode="date">
                      <template #default="{ inputValue, inputEvents }">
                        <VField>
                          <VLabel>Tanggal Usulan</VLabel>
                          <VControl icon="feather:calendar">
                            <VInput type="text" placeholder="Pilih Tanggal" class="is-rounded" :value="inputValue"
                              v-on="inputEvents" />
                          </VControl>
                        </VField>
                      </template>
                    </VDatePicker>
                  </div>
                  <div class="column is-3">
                    <VDatePicker v-model="item.tglDibutuhkan" color="green" trim-weeks mode="date">
                      <template #default="{ inputValue, inputEvents }">
                        <VField>
                          <VLabel>Tanggal Dibutuhkan</VLabel>
                          <VControl icon="feather:calendar">
                            <VInput type="text" placeholder="Pilih Tanggal" class="is-rounded" :value="inputValue"
                              v-on="inputEvents" />
                          </VControl>
                        </VField>
                      </template>
                    </VDatePicker>
                  </div>
                  <div class="column is-3">
                    <VField class="is-rounded-select is-autocomplete-select">
                      <VLabel class="required-field">Koordinator Barang</VLabel>
                      <VControl icon="feather:search" fullwidth class="prime-auto-select">
                        <Dropdown v-model="item.koordinator" :options="d_kordinasiBarang" optionLabel="label"
                          class="is-rounded" placeholder="Pilih Kordinasi Barang" style="width: 100%;" :filter="true" />
                      </VControl>
                    </VField>
                  </div>
                </div>
              </div>
              </p>
              <p v-if="activeValue === 'unit'">
              <div style="margin-top:2.8rem" v-if="permintaanBarang.loading">
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
                <div class="columns">
                  <div class="column is-4 pt-1">
                    <VField label="Unit Pengusul">
                      <VControl>
                        <AutoComplete v-model="item.ruanganPengusul" :suggestions="d_Ruangan"
                          @complete="fetchRuangan($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                          :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                          placeholder="Cari Ruangan Asal" class="is-rounded" />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-4 pt-1">
                    <VField label="Unit Tujuan">
                      <VControl>
                        <AutoComplete v-model="item.ruanganTujuan" :suggestions="d_Ruangan"
                          @complete="fetchRuangan($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                          :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                          placeholder="Cari Ruangan Tujuan" class="is-rounded" />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-4 pt-1">
                    <VField label="Penanggung Jawab">
                      <VControl>
                        <AutoComplete v-model="item.penanggungjawab" :suggestions="d_Pegawai"
                          @complete="fetchPegawai($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                          :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                          placeholder="Cari Penanggung Jawab" class="is-rounded" />
                      </VControl>
                    </VField>
                  </div>
                </div>
              </div>
              </p>
              <p v-if="activeValue === 'po'">
              <div style="margin-top:2.8rem" v-if="permintaanBarang.loading">
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
              <div class="column is-12">
                <div class="columns is-multiline">
                  <div class="column is-4 pt-1">
                    <VField label="Mengetahui">
                      <VControl>
                        <AutoComplete v-model="item.mengetahui" :suggestions="d_PenanggungJawab"
                          @complete="penanggungjawab($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                          :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                          @item-select="autocompleteNip(item.mengetahui)" :field="'label'"
                          placeholder="Cari Penanagung Jawab" class="is-rounded" />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-3 pt-1">
                    <VField class="is-rounded-select is-autocomplete-select">
                      <VLabel class="required-field">NIP</VLabel>
                      <VInput class="is-rounded" v-model="item.nip"></VInput>
                    </VField>
                  </div>
                </div>
              </div>

              </p>
            </template>
          </VTabs>
        </div>
      </div>

      <div class="column is-12 p-0 mt-5">
        <VCard>
          <div class="column is-2 p-0 pb-2" style="margin-left: auto">
            <VButton type="button" icon="fas fa-plus-circle" @click="modalTambah(item)"
              class="is-fullwidth is-outlined is-primary mt-4" rounded raised>
              Tambah
            </VButton>
          </div>
          <DataTable :value="permintaanBarang" :paginator="true" :rows="5" :rowsPerPageOptions="[5, 10, 25]"
            :loading="permintaanBarang.loading" class="p-datatable-sm" tableStyle="min-width: 10rem"
            paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
            sortMode="multiple" currentPageReportTemplate="Showing {first} to {last} of {totalRecords}" showGridlines>
            <Column field="no" header="No" style="min-width: 10px;" frozen></Column>
            <Column field="tglkebutuhan" header="Tgl Kebutuhan" style="min-width: 100px;" frozen>
              <template #body="slotProps">
                {{ H.formatDateOnly(slotProps.data.tglkebutuhan) }}
              </template>
            </Column>
            <Column field="namaproduk" header="Nama Produk" style="min-width: 250px;" frozen class="font-bold"></Column>
            <Column field="satuanstandar" header="Satuan" style="min-width: 100px;"></Column>
            <Column field="jumlah" header="Qty" style="min-width: 50px;" />
            <Column field="hargasatuan" header="Harga Satuan" style="min-width: 100px;text-align:right">
              <template #body="slotProps">
                {{ H.formatRupiah(H.roundToDecimal(parseFloat(slotProps.data.hargasatuan), 2), '') }}
              </template>
            </Column>
            <Column field="hargadiscount" header="Harga Diskon" style="min-width: 100px;text-align:right">
              <template #body="slotProps">
                {{ H.formatRupiah(H.roundToDecimal(parseFloat(slotProps.data.hargadiscount), 2), '') }}
              </template>
            </Column>
            <Column field="ppn" header="PPN" style="text-align:right;min-width: 100px;">
              <template #body="slotProps">
                {{ H.formatRupiah(H.roundToDecimal(parseFloat(slotProps.data.ppn), 2), '') }}
              </template>
            </Column>
            <Column field="total" header="Total" style="min-width: 100px;text-align:right">
              <template #body="slotProps">
                {{ H.formatRupiah(H.roundToDecimal(parseFloat(slotProps.data.total), 2), '') }}
              </template>
            </Column>
            <Column :exportable="false" header="Action" style="text-align: center;min-width: 100px;">
              <template #body="slotProps">
                <VIconButton type="button" icon="feather:edit" class="mr-3" color="info" circle outlined raised
                     v-tooltip.top="'Edit'" :loading="permintaanBarang.editLoading"
                  @click="modalTambah(slotProps.data)">
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
              <small class="text-bold-custom h-100">{{ H.formatRp(item.totalall, 'Rp.')}}</small>
            </VCardCustom>
          </div>
          <div class="column is-3">
            <VCardCustom :style="'padding:5px 25px'">
              <div class="label-status info">
                <i aria-hidden="true" class="fas fa-circle"></i>
                <span class="ml-1">DISKON</span>
              </div>
              <small class="text-bold-custom h-100">{{H.formatRp(item.discount, 'Rp.')}}</small>
            </VCardCustom>
          </div>
          <div class="column is-3">
            <VCardCustom :style="'padding:5px 25px'">
              <div class="label-status danger">
                <i aria-hidden="true" class="fas fa-circle"></i>
                <span class="ml-1">PPN</span>
              </div>
              <small class="text-bold-custom h-100">{{H.formatRp(item.ppnTotal, 'Rp.')}}</small>
            </VCardCustom>
          </div>
          <div class="column is-3">
            <VCardCustom :style="'padding:5px 25px'">
              <div class="label-status" color="danger">
                <i aria-hidden="true" class="fas fa-circle"></i>
                <span class="ml-1">TOTAL</span>
              </div>
              <small class="text-bold-custom h-100">{{ H.formatRp(item.totalall, 'Rp.')}}</small>
            </VCardCustom>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- modal input -->
  <VModal :open="modalInput" title="Order Barang" size="large" actions="right" @close="modalInput = false, clear()">
    <template #content>
      <form class="modal-form">
        <div class="columns is-multiline">
          <div class="column is-5">
            <VField class="is-autocomplete-select" label="Produk">
              <VControl icon="feather:search">
                <AutoComplete v-model="item.produk" :suggestions="d_Produk" @complete="fetchProduk($event)"
                  :optionLabel="'namaproduk'" :dropdown="true" :minLength="3" :appendTo="'body'"
                  @item-select="getSatuan(item.produk)" :loadingIcon="'pi pi-spinner'" :field="'namaproduk'"
                  placeholder="Cari Nama Obat" />
              </VControl>
            </VField>
          </div>
          <div class="column is-3">
            <VField class="is-rounded-select is-autocomplete-select">
              <VLabel class="required-field">Satuan</VLabel>
              <VControl icon="feather:search" fullwidth class="prime-auto-select">
                <Dropdown v-model="item.satuan" :options="d_Satuan" optionLabel="label"
                  @change="getKonversi(item.satuan)" class="is-rounded" placeholder="Pilih data" style="width: 100%;"
                  :filter="true" />
              </VControl>
            </VField>
          </div>
          <div class="column is-2">
            <VField>
              <VLabel class="required-field">Konversi</VLabel>
              <VControl>
                <input v-model="item.konversi" type="number" class="input is-rounded" placeholder="Konversi" />
              </VControl>
            </VField>
          </div>
          <div class="column is-2">
            <VField>
              <VLabel class="required-field" style="width: max-content !important;">Jumlah</VLabel>
              <VControl>
                <input v-model="item.jumlah" type="number" class="input is-rounded" placeholder="QTY" />
              </VControl>
            </VField>
          </div>
          <div class="column is-3">
            <VField>
              <VLabel class="required-field">Harga Satuan</VLabel>
              <VControl :loading="isLoadPrice">
                <input v-model="item.hargasatuan" type="number" v-mask-currency class="input is-rounded"
                  placeholder="Harga Satuan" :loading="isLoadPrice" />
              </VControl>
            </VField>
          </div>
          <div class="column is-3">
            <VField label="Jumlah PPN(%)">
              <VControl>
                <input v-model="item.persenppn" type="number" class="input is-rounded" placeholder="Jumlah PPN" />
              </VControl>
            </VField>
          </div>
          <div class="column is-3">
            <VField label="Harga PPN">
              <VControl>
                <input v-model="item.nilaippn" type="text" disabled class="input is-rounded" placeholder="Harga PPN"
                  style="font-weight: bold;" />
              </VControl>
            </VField>
          </div>
          <div class="column is-3">
            <VField label="Jumlah Diskon(%)">
              <VControl>
                <input v-model="item.persendiscount" type="number" class="input is-rounded"
                  placeholder="Jumlah Diskon" />
              </VControl>
            </VField>
          </div>
          <div class="column is-3">
            <VField label="Harga Diskon">
              <VControl>
                <input v-model="item.hargadiskon" type="text" disabled class="input is-rounded"
                  placeholder="Harga Diskon" style="font-weight: bold;" />
              </VControl>
            </VField>
          </div>
          <div class="column is-3">
            <VField label="Sub total">
              <VControl>
                <input v-model="item.subtotal" type="text" disabled class="input is-rounded" placeholder="Sub Total"
                  style="font-weight: bold;" />
              </VControl>
            </VField>
          </div>
          <div class="column">
            <VField>
              <VLabel class="required-field" style="width: max-content !important;">Spesifikasi</VLabel>
              <VControl>
                <input v-model="item.spesifikasi" type="text" class="input is-rounded" placeholder="Sub Total" />
              </VControl>
            </VField>
          </div>
        </div>
      </form>
    </template>
    <template #action>
      <VButton icon="feather:plus" @click="tambah(item)" color="primary" raised>Tambah</VButton>
    </template>
  </VModal>
  <!-- end modal input -->
</template>
<script setup lang="ts">
import { useRoute, useRouter } from 'vue-router'
import { useApi } from '/@src/composable/useApi'
import { ref, reactive, computed, watch } from 'vue'
import { useConfirm } from 'primevue/useconfirm'
import SpeedDial from 'primevue/speeddial';
import ConfirmDialog from 'primevue/confirmdialog'
import DataTable from 'primevue/datatable';
import Column from 'primevue/column'
import moment from 'moment'
import { useThemeColors } from '/@src/composable/useThemeColors'
import { useUserSession } from '/@src/stores/userSession'
import { useHead } from '@vueuse/head'
import * as H from '/@src/utils/appHelper'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import Dropdown from 'primevue/dropdown'
import AutoComplete from 'primevue/autocomplete';

useHead({
  title: 'Rencana Usulan Permintaan Barang/Jasa - Transmedic',
})

const confirm = useConfirm();
let NORECSP: any = useRoute().query.norecsp as string
const permintaanBarang: any = ref([])
const d_kelompokProduk: any = ref([])
const d_kelompokBarang: any = ref([])
const d_kordinasiBarang: any = ref([])
const d_RuanganPengusul: any = ref([])
const d_Ruangan: any = ref([])
const d_Pegawai: any = ref([])
const d_Produk: any = ref([])
const d_PenanggungJawab: any = ref([])
const d_Satuan: any = ref([])
const modalInput: any = ref(false)
const isLoadPrice: any = ref(false)
const bulanRomawi: any = ref('')
const item: any = reactive({
  tglUsulan: new Date(),
  tglDibutuhkan: new Date(),
})

const getDetail = async (e:any)=>{
  let response = await useApi().get(`/iprs/get-detail-rencana-usulan-permintaan?norec=${NORECSP}`)
  
  let header = response.header

  item.kelompokbarang = { label: header.kelompokbarang, value: header.kelompokbarangfk}
  item.noUsulan = header.nousulan
  item.keteranganUsulan = header.keterangan
  item.tglUsulan = header.tglusulan
  item.tglDibutuhkan = header.tgldibutuhkan
  item.tglDibutuhkan = header.tgldibutuhkan
  d_kordinasiBarang.value.forEach(element => {
    if (element.label == header.koordinator){
      item.koordinator = element
    }
  });
  item.ruanganPengusul = { label: header.unitpengusul , value: header.idunitpengusul }
  item.ruanganTujuan = { label: header.unittujuan, value: header.idunittujuan }
  item.penanggungjawab = { label: header.petugas, value: header.petugasid }
  item.mengetahui = { label: header.petugasmengetahui, value: header.petugasmengetahuiid, nip: header.nip }
  item.nip = header.nip
  item.norecRealisasi = header.norecrealisasi

  permintaanBarang.value = response.details
  count()
}

const saveData = async (e: any) => {
  if (!item.kelompokbarang) {
      H.alert('error', 'Kelompok Barang Belum Dipilih')
      return
    }
  if (!item.noUsulan) {
      H.alert('error', 'No Usulan Belum Diisi')
      return
    }
  if (!item.keteranganUsulan) {
      H.alert('error', 'Jenis Usulan Belum Diisi')
      return
    }
  if (!item.ruanganPengusul) {
      H.alert('error', 'Unit Pengusul Belum Diisi')
      return
    }
  if (!item.ruanganTujuan) {
      H.alert('error', 'Unit Tujuan Belum Diisi')
      return
    }
  if (!item.penanggungjawab) {
      H.alert('error', 'Penanggung Jawab Belum Diisi')
      return
    }
  if (!item.mengetahui) {
      H.alert('error', 'Mengetahui Belum Diisi')
      return
    }
  if (!item.totalall) {
      H.alert('error', 'QTY Belum Diisi')
      return
    }
  let strukorder = {
    kelompokbarangfk: item.kelompokbarang.value,
    noUsulan: item.noUsulan,
    keteranganorder: item.keteranganUsulan,
    qtyjenisproduk: permintaanBarang.value.length,
    tglUsulan: H.formatDate(item.tglUsulan,'YYYY-MM-DD HH:mm:ss'),
    tglDibutuhkan: H.formatDate(item.tglDibutuhkan, 'YYYY-MM-DD HH:mm:ss'),
    koordinator: item.koordinator.label,
    ruanganfkPengusul: item.ruanganPengusul.value,
    ruanganfkTujuan: item.ruanganTujuan.value,
    penanggungjawabfk: item.penanggungjawab.value,
    mengetahuifk: item.mengetahui.value,
    nipPns: item.nip,
    total: item.totalall,
    norec: NORECSP ? NORECSP : '',
    ppn: item.ppnTotal,
    norecrealisasi: item.norecRealisasi ? item.norecRealisasi : '',
  }
  let objSave =
  {
    strukorder: strukorder,
    details: permintaanBarang.value
  }

  await useApi().post('iprs/save-data-rencana-usulan',objSave).then((response)=>{
    
  })
  console.log(objSave)
}
const listCombo = async (filter: any) => {
  await useApi().get('iprs/combo').then((response) => {
    d_kelompokProduk.value = response.kelompokproduk.map((e: any) => {
      return { label: e.kelompokproduk, value: e.id }
    })
    d_kelompokBarang.value = response.kelompokbarang.map((e: any) => {
      return { label: e.kelompokbarang, value: e.id }
    })
    d_kordinasiBarang.value = response.jenisusulan.map((e: any) => {
      return { label: e.jenisusulan, value: e.id }
    })
    d_RuanganPengusul.value = response.ruangan.map((e: any) => {
      return { label: e.namaruangan, value: e.id }
    })
    item.ruanganPengusul = d_RuanganPengusul.value[0]
    bulanRomawi.value = response.bulanromawi
  })

  d_kordinasiBarang.value.forEach(element => {
    if (element.value == 2) {
      item.koordinator = element
    }
  });

}

const modalTambah = async (e: any) => {
  
  if (e.no) {
    permintaanBarang.value.editLoading = true
    let datas = { query: e.namaproduk }
    await fetchProduk(datas)
    d_Produk.value.forEach(element => {
      if (element.id == e.produkfk) {
        item.produk = element
      }
    });
    getSatuan(item.produk)
    item.no = e.no
    item.satuan = { label: e.satuanstandar, value: e.satuanstandarfk }
    item.konversi = e.nilaikonversi
    item.jumlah = e.jumlah
    item.hargasatuan = e.hargasatuan
    item.persenppn = e.persenppn
    item.nilaippn = e.ppn
    item.persendiscount = e.persendiscount
    item.hargadiskon = e.hargadiscount
    item.subtotal = e.total
    item.spesifikasi = e.spesifikasi
    console.log(e)
  }
  modalInput.value = true
  permintaanBarang.value.editLoading = false
}

const tambah = (e: any) => {
  if (!item.produk) {
      H.alert('error', 'Produk Belum Diisi')
      return
    }
  if (!item.jumlah) {
      H.alert('error', 'QTY Belum Diisi')
      return
    }
  if (!item.spesifikasi) {
      H.alert('error', 'Spesifikasi Belum Diisi')
      return
    }
  let datas: any = {}
  if (e.no) {
    permintaanBarang.value.forEach((element: any, i: any) => {
      if (element.no == e.no) {
        datas.no = element.no,
          datas.hargajual = null,
          datas.jenisobatfk = null,
          datas.stock = null,
          datas.harganetto = null,
          datas.nostrukterimafk = null,
          datas.ruanganfk = null,
          datas.asalprodukfk = null,
          datas.asalproduk = null,
          datas.kdproduk = item.produk.kdproduk,
          datas.produkfk = item.produk.id,
          datas.namaproduk = item.produk.namaproduk,
          datas.nilaikonversi = item.konversi,
          datas.satuanstandarfk = item.satuan.value,
          datas.satuanstandar = item.satuan.label,
          datas.satuanviewfk = item.satuan.value,
          datas.satuanview = item.satuan.label,
          datas.jmlstok = null,
          datas.jumlah = item.jumlah,
          datas.hargasatuan = item.hargasatuan,
          datas.hargadiscount = item.hargadiskon ? item.hargadiskon : null,
          datas.persendiscount = item.persendiscount ? item.persendiscount : null,
          datas.ppn = item.nilaippn ? item.nilaippn : null,
          datas.persenppn = item.persenppn ? item.persenppn : null,
          datas.total = item.subtotal,
          datas.tglkebutuhan = H.formatDate(item.tglDibutuhkan, 'YYYY-MM-DD'),
          datas.spesifikasi = item.spesifikasi
        permintaanBarang.value[i] = datas
      }
    });
  } else {
    datas = {
      no: permintaanBarang.value.length == 0 ? 1 : permintaanBarang.value.length + 1,
      hargajual: null,
      jenisobatfk: null,
      stock: null,
      harganetto: null,
      nostrukterimafk: null,
      ruanganfk: null,
      asalprodukfk: null,
      asalproduk: null,
      kdproduk: item.produk.kdproduk,
      produkfk: item.produk.id,
      namaproduk: item.produk.namaproduk,
      nilaikonversi: item.konversi,
      satuanstandarfk: item.satuan.value,
      satuanstandar: item.satuan.label,
      satuanviewfk: item.satuan.value,
      satuanview: item.satuan.label,
      jmlstok: null,
      jumlah: item.jumlah,
      hargasatuan: item.hargasatuan,
      hargadiscount: item.hargadiskon ? item.hargadiskon : 0,
      persendiscount: item.persendiscount ? item.persendiscount : 0,
      ppn: item.nilaippn ? item.nilaippn : 0,
      persenppn: item.persenppn ? item.persenppn : 0,
      total: item.subtotal,
      tglkebutuhan: H.formatDate(item.tglDibutuhkan, 'YYYY-MM-DD'),
      spesifikasi: item.spesifikasi
    }
    permintaanBarang.value.push(datas)
  }
  modalInput.value = false
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
      permintaanBarang.value.forEach((element: any, i: any) => {
        if (element.no == e.no) {
          permintaanBarang.value.splice(i, 1)
        }
      })
      count()
      clear()
    },
    reject: () => { },
  })
}

const fetchProduk = async (filter: any) => {
  const response = await useApi().get(`logistik/get-combo-barang-logistik?namaproduk=${filter.query}`)
  d_Produk.value = response
}

const getSatuan = async (e: any) => {
  if (e.konversisatuan != 0) {
    d_Satuan.value = e.konversisatuan.map((element: any) => {
      return { label: element.satuanstandar.toUpperCase(), value: element.ssid, konversi: element.nilaikonversi }
    })
    d_Satuan.value.forEach((data: any) => {
      if (data.value == e.ssid) {
        item.satuan = data
        item.konversi = data.konversi
        return
      }
    })

  } else {
    d_Satuan.value = [{ label: e.satuanstandar.toUpperCase(), value: e.ssid }]
    d_Satuan.value.forEach((data: any) => {
      if (data.value == e.ssid) {
        item.satuan = data
        item.konversi = 1
        return
      }
    })
  }
  isLoadPrice.value = true
  await useApi()
    .get('/logistik/penerimaan-barang/get-produkdetail?produkfk=' + e.id)
    .then((response) => {
      let data = response.detail[0]
      item.hargasatuan = data ? H.formatRupiah(data.harga, "") : 0
      //  H.formatRupiah
      isLoadPrice.value = false
    })

}

const generateNoUsuslan = () => {
  if (item.otomatis) {
    if (!item.kelompokbarang) {
      H.alert('error', 'Kelompok Barang Belum Dipilih')
      return
    }

    let nows = moment(new Date()).format('YY')
    if (item.kelompokbarang.value == 1) {
      item.noUsulan = '____/' + 'IF-01/' + bulanRomawi.value + '/' + nows
    } else if (item.kelompokbarang.value == 2) {
      item.noUsulan = '____/' + 'IF-02/' + bulanRomawi.value + '/' + nows
    } else if (item.kelompokbarang.value == 3) {
      item.noUsulan = '____/' + 'IF-03/' + bulanRomawi.value + '/' + nows
    } else if (item.kelompokbarang.value == 4) {
      item.noUsulan = '____/' + 'IF-04/' + bulanRomawi.value + '/' + nows
    } else if (item.kelompokbarang.value == 5) {
      item.noUsulan = '____/' + 'IF-05/' + bulanRomawi.value + '/' + nows
    } else if (item.kelompokbarang.value == 6) {
      item.noUsulan = '____/' + 'IF-06/' + bulanRomawi.value + '/' + nows
    } else if (item.kelompokbarang.value == 7) {
      item.noUsulan = '____/' + 'IF-07/' + bulanRomawi.value + '/' + nows
    } else {
      delete item.noUsulan
    }
  } else {
    delete item.noUsulan
  }

}

const fetchRuangan = async (filter: any) => {
  const response = await useApi().get(`/emr/dropdown/ruangan_m?select=id,namaruangan&param_search=namaruangan&query=${filter.query}&limit=10`)
  d_Ruangan.value = response
}

const fetchPegawai = async (filter: any) => {

  await useApi().get(
    `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`
  ).then((response) => {
    d_Pegawai.value = response
  })
}

const autocompleteNip = (data: any) => {
  console.log(data)
  item.nip = data.nip
}

const penanggungjawab = async (filter: any) => {

  await useApi().get(`iprs/penangung-jawab?namalengkap=${filter.query}`
  ).then((response) => {
    d_PenanggungJawab.value = response.map((e: any) => {
      return { label: e.namalengkap, value: e.id, nip: e.nip }
    })
  })
}

const count = () => {
  let discount = 0
  let ppn = 0
  let total = 0
  permintaanBarang.value.forEach((element: any) => {
    total = total + parseFloat(element.total)
    discount = element.hargadiscount === '' ? discount : discount + parseFloat(element.hargadiscount)
    ppn = element.ppn === '' ? ppn : ppn + parseFloat(element.ppn)
  })
  item.discount = discount
  item.ppnTotal = ppn
  item.totalall = total
}


const clear = () => {
  delete item.no
  delete item.produk
  delete item.satuan
  delete item.konversi
  delete item.jumlah
  delete item.hargasatuan
  delete item.persenppn
  delete item.nilaippn
  delete item.persendiscount
  delete item.hargadiskon
  delete item.subtotal
  delete item.spesifikasi
}

if (NORECSP){
  getDetail()
}
listCombo()

watch(
  () => item.otomatis,
  () => {
    generateNoUsuslan()
  }
)

// watch(
//   () => item.kelompokbarang,
//   () => {
// delete item.noUsulan
// delete item.otomatis
//   }
// )

watch(
  () => [
    item.persenppn,
    item.hargasatuan,
    item.persendiscount,
    item.jumlah,
  ],
  () => {
    // console.log(item.jumlah)
    if (item.jumlah === undefined || item.jumlah === '') {
      item.subtotal = ''
    } else {
      const diskon: any = item.persendiscount == '' ? delete item.persendiscount : item.persendiscount / 100
      const nilaiDiskon: any = parseFloat(item.jumlah) * parseFloat(item.hargasatuan) * diskon
      const resultHarga: any = nilaiDiskon ? parseFloat(item.jumlah) * parseFloat(item.hargasatuan) - parseFloat(nilaiDiskon)
        : parseFloat(item.jumlah) * parseFloat(item.hargasatuan)
      const ppn: any = item.persenppn / 100
      const nilaippn = resultHarga * ppn
      item.hargadiskon = diskon ? nilaiDiskon : ''
      item.nilaippn = ppn ? nilaippn : ''
      item.subtotal = nilaippn ? parseFloat(resultHarga) + nilaippn : resultHarga
    }
  }
)
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

.tabs-wrapper.is-triple-slider.is-squared {
    display: unset !important;
}
</style>
