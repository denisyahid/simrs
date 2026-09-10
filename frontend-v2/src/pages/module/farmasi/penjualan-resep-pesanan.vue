<template>
    <ConfirmDialog />
    <div class="form-layout is-stacked">
        <div class="form-outer" style="margin-top: 15px">
            <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
                <div class="form-header-inner">
                    <div class="left">
                        <h3> Penjualan Obat</h3>
                    </div>
                    <div class="right">
                        <div class="buttons">
                            <VButton icon="lnir lnir-arrow-left rem-100" light dark-outlined RouterLink @click="back()">
                                Kembali
                            </VButton>
                            <VButton v-if="useRoute().query.norec" type="button" rounded outlined color="primary"
                                :loading="btnSaveandUpdate" @click="save()" raised icon="feather:edit">
                                Update
                            </VButton>
                            <VButton v-else type="button" rounded outlined color="primary" :loading="btnSaveandUpdate"
                                @click="save()" raised icon="feather:save">
                                Simpan
                            </VButton>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-body ">

                <div v-if="isLoadEdit == true">
                    <VPlaceloadWrap class="mt-5">
                        <VPlaceload height="30px" width="40%" class="mx-2" />
                        <VPlaceload height="30px" width="30%" class="mx-2" />
                        <VPlaceload height="30px" width="40%" class="mx-2" />
                    </VPlaceloadWrap>
                    <VPlaceloadWrap class="mt-5">
                        <VPlaceload height="80px" width="100%" class="mx-2" />
                    </VPlaceloadWrap>
                    <VPlaceloadWrap class="mt-5">
                        <VPlaceload height="30px" width="40%" class="mx-2" />
                        <VPlaceload height="30px" width="30%" class="mx-2" />
                        <VPlaceload height="30px" width="40%" class="mx-2" />
                    </VPlaceloadWrap>
                </div>
                <div v-else>
                    <div class="column is-12">
                        <div class="columns">
                            <div class="column is-2">
                                <VField label="No MR" v-if="item.ceknomr == true">
                                    <VControl icon="feather:search">
                                        <VInput type="text" v-model="item.nocm"
                                            v-on:keyup.enter="fetchPasien(item.nocm)" placeholder="No MR"
                                            class="is-rounded" />
                                    </VControl>
                                </VField>
                                <VField label="No MR" v-else>
                                    <VControl icon="feather:search">
                                        <VInput type="text" v-model="item.nocm" placeholder="No MR" class="is-rounded"
                                            disabled />
                                    </VControl>
                                </VField>
                            </div>
                            <div class="column is-2 p-0" style="margin-top: 3rem; margin-left:px;margin-right: -144px;">
                                <VField>
                                    <VControl raw subcontrol>
                                        <Checkbox v-model="item.ceknomr" :binary="true" />
                                        <label class="ml-2 ingredient1">Ada</label>
                                    </VControl>
                                </VField>
                            </div>
                            <div class="column is-3">
                                <VField label="Nama Lengkap">
                                    <VPlaceloadWrap v-if="renderLoader">
                                        <VPlaceload height="30px" class="mx-2" />
                                    </VPlaceloadWrap>
                                    <VControl v-else icon="feather:bookmark">
                                        <VInput type="text" v-model="item.namalengkap" placeholder="Nama Lengkap"
                                            class="is-rounded" />
                                    </VControl>
                                </VField>
                            </div>
                            <div class="column is-2">
                                <VDatePicker v-model="item.tgllahir" color="green" trim-weeks>
                                    <template #default="{ inputValue, inputEvents }">
                                        <VField>
                                            <VLabel>Tanggal Lahir</VLabel>
                                            <VPlaceloadWrap v-if="renderLoader">
                                                <VPlaceload height="30px" class="mx-2" />
                                            </VPlaceloadWrap>
                                            <VControl v-else icon="feather:calendar">
                                                <VInput type="text" placeholder="Select a date" class="is-rounded"
                                                    :value="inputValue" v-on="inputEvents" />
                                            </VControl>
                                        </VField>
                                    </template>
                                </VDatePicker>
                            </div>
                            <div class="column is-2">
                                <VField label="No Telepon">
                                    <VPlaceloadWrap v-if="renderLoader">
                                        <VPlaceload height="30px" class="mx-2" />
                                    </VPlaceloadWrap>
                                    <VControl v-else icon="feather:bookmark">
                                        <VInput type="text" v-model="item.notlp" placeholder="No Telepon"
                                            class="is-rounded" />
                                    </VControl>
                                </VField>
                            </div>
                            <div class="column is-2">
                                <VField class="is-rounded-select is-autocomplete-select">
                                    <VLabel>Kebangsaan</VLabel>
                                    <VControl icon="feather:search" fullwidth class="prime-auto-select">
                                        <Dropdown v-model="item.kebangsaan" :options="d_Kebangsaan"
                                            optionLabel="label" class="is-rounded" placeholder="Pilih data"
                                            style="width: 100%;" :filter="true" />
                                    </VControl>
                                </VField>
                            </div>
                        </div>
                    </div>
                    <div class="column is-12 pt-0">
                        <VField label="Alamat">
                            <VPlaceloadWrap v-if="renderLoader">
                                <VPlaceload height="50px" class="mx-2" />
                            </VPlaceloadWrap>
                            <VControl v-else>
                                <VTextarea v-model="item.alamatlengkap" rows="3" placeholder="Alamat Lengkap">
                                </VTextarea>
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-12">
                        <div class="columns">
                            <div class="column is-4">
                                <VDatePicker v-model="item.tglresep" color="green" trim-weeks>
                                    <template #default="{ inputValue, inputEvents }">
                                        <VField>
                                            <VLabel>Tanggal Resep</VLabel>
                                            <VControl icon="feather:calendar">
                                                <VInput type="text" placeholder="Select a date" class="is-rounded"
                                                    :value="inputValue" v-on="inputEvents" />
                                            </VControl>
                                        </VField>
                                    </template>
                                </VDatePicker>
                            </div>
                            <div class="column is-4">
                                <VField class="is-rounded-select is-autocomplete-select">
                                    <VLabel>Penulis Resep</VLabel>
                                    <VControl icon="feather:search" fullwidth class="prime-auto-select">
                                        <Dropdown v-model="item.penulisresep" :options="d_penulisResep"
                                            optionLabel="label" class="is-rounded" placeholder="Pilih data"
                                            style="width: 100%;" :filter="true" />
                                    </VControl>
                                </VField>
                            </div>
                            <div class="column is-4">
                                <VField class="is-rounded-select is-autocomplete-select">
                                    <VLabel class="required-field">Ruangan</VLabel>
                                    <VControl icon="feather:search" fullwidth class="prime-auto-select">
                                        <Dropdown v-model="item.ruangan" :options="d_ruangan" optionLabel="label"
                                            :disabled="disabledRuangan" class="is-rounded" placeholder="Pilih data"
                                            style="width: 100%;" :filter="true" />
                                    </VControl>
                                </VField>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <div class="column is-12 p-0 mt-5">
            <VCard>
                <div class="column is-2 p-0 pb-2" style="margin-left: auto">
                    <VButton type="button" icon="feather:plus-circle" @click="inputPopUp()"
                        class="is-fullwidth is-outlined is-primary mt-4" rounded raised>
                        Tambah
                    </VButton>
                </div>
                <!-- <div class="column is-2 p-0 pb-2" style="margin-left: auto">
               <VButton
                      type="button" icon="feather:plus-circle" @click="paketObat()" :loading="isloadingPaketObat"
                      color="info" raised rounded class="is-fullwidth is-outlined is-primary mt-4" >  Paket Obat
                    </VButton>
                    </div> -->
                <DataTable :value="dataSource" :paginator="true" :rows="5" :rowsPerPageOptions="[5, 10, 25]"
                    class="p-datatable-sm"
                    paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
                    responsiveLayout="stack" breakpoint="960px" sortMode="multiple"
                    currentPageReportTemplate="Showing {first} to {last} of {totalRecords}" showGridlines>
                    <Column field="no" header="No"></Column>
                    <Column field="rke" header="R/ke"></Column>
                    <Column field="jeniskemasan" header="Kemasan"></Column>
                    <!-- <Column field="jmldosis" header="Jml/Dss/kkuatan"></Column> -->
                    <Column field="kdproduk" header="Kode"></Column>
                    <Column field="namaproduk" header="Produk"></Column>
                    <Column field="hargasatuan" header="Harga"></Column>
                    <Column field="hargadiscount" header="Diskon"></Column>
                    <Column field="aturanpakai" header="Aturan Pakai"></Column>
                    <Column field="satuanstandar" header="Satuan"></Column>
                    <Column field="jumlah" header="Qty"></Column>
                    <Column field="total" header="Total"></Column>
                    <Column :exportable="false" header="#" style="text-align: center">
                        <template #body="slotProps">
                            <VIconButton type="button" icon="pi pi-pencil" class="mr-3" color="info" circle outlined
                                raised v-tooltip.top="'Edit'" @click="editPopUp(slotProps.data)" :loading="btnLoadEdit">
                            </VIconButton>
                            <VIconButton type="button" icon="fas fa-trash" class="mr-3" color="danger" circle outlined
                                raised v-tooltip.top="'Hapus'" @click="dialogConfirm(slotProps.data)">
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
            <div class="column is-3 p-0" style="margin-left: auto;">
                <VCardCustom :style="'padding:5px 25px'">
                    <div class="label-status" color="danger">
                        <i aria-hidden="true" class="fas fa-circle"></i>
                        <span class="ml-1">TOTAL</span>
                    </div>
                    <small class="text-bold-custom h-100">{{
                        H.formatRp(item.resultTotal, 'Rp.')
                        }}</small>
                </VCardCustom>
            </div>
        </div>

    </div>

<Dialog v-model:visible="modalDataPaketObat" modal header="Paket Obat" :style="{ width: '60vw' }" maximizable>
      <div class="columns">
        <div class="column is-12">
          <DataTable :value="dataSourcePaketObat" :rows="5" :rowsPerPageOptions="[5, 10, 15]" class="p-datatable-sm"
            responsiveLayout="stack" breakpoint="960px" selectionMode="single" sortMode="multiple" showGridlines
            v-model:expanded-rows="expandedRows"
            paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
            paginator currentPageReportTemplate="Showing {first} to {last} of {totalRecords}">
            <Column expander style="width: 5rem" />
            <Column field="no" header="No"></Column>
            <Column field="paketId" header="Nomor Paket"></Column>
            <Column field="namapaket" header="Nama Paket"></Column>
            <Column :exportable="false" header="#" style="text-align: center;">
              <template #body="slotProps">
                <VIconButton v-tooltip.bottom.left="'Pilih Paket'" label="Bottom Left" color="primary" circle
                  icon="pi pi-check-circle" @click="pilihPaketObat(slotProps.data)" style="margin-right: 15px;"
                  :loading="isloadingTambahPaket" />
              </template>
            </Column>
            <template #expansion="slotProps">
              <div class="p-3">
                <DataTable :value="slotProps.data.details" :rows="10" showGridlines class="p-datatable-sm"
                  responsiveLayout="stack" breakpoint="960px" sortMode="multiple">
                  <Column field="no" header="No" />
                  <Column field="namaproduk" header="Nama Produk" />
                  <Column field="satuanresep" header="Satuan" />
                  <Column field="jumlah" header="Jumlah" style="text-align: center;" />
                  <Column field="aturanpakai" header="Aturan Pakai" />
                </DataTable>
              </div>
            </template>
          </DataTable>
        </div>
      </div>
    </Dialog>


    <VModal :open="modalInput" title="Add Resep" size="big" actions="right" @close="modalInput = false, clearInput()">
        <template #content>
            <form class="modal-form">
                <!-- <div class="column is-2 mt-5-min pl-0 pb-2">
                    <VField>
                        <VControl raw subcontrol>
                            <VCheckbox v-model="item.checkisDonasi" class="p-0" :label="'Obat Donasi'" color="info"
                                square />
                        </VControl>
                    </VField>
                </div> -->

                <div class="columns is-multiline">
                    <div class="column is-2">
                        <VField>
                            <VLabel>R/Ke</VLabel>
                            <VControl icon="feather:bookmark">
                                <VInput type="number" v-model="item.rke" placeholder="R/Ke" class="is-rounded" />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-4">
                        <VField>
                            <VLabel>Jenis Kemasan</VLabel>
                            <VControl>
                                <VRadio v-for="items in d_kemasan" :key="items.id" v-model="item.jenisKemasan"
                                    :value="items" :label="items.jeniskemasan" name="{{items.id}}" color="primary" />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-3" v-if="showRacikanDose">
                        <VField>
                            <VLabel>Jumlah</VLabel>
                            <VControl icon="feather:bookmark">
                                <VInput type="text" v-model="item.jumlahxmakan" placeholder="Jumlah"
                                    class="is-rounded" />

                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-3" v-if="showRacikanDose">
                        <VField>
                            <VLabel>Dosis</VLabel>
                            <VControl icon="feather:bookmark">
                                <VInput type="number" v-model="item.dosis" placeholder="Dosis" class="is-rounded" />
                                <p class="help"> {{ (item.kekuatan ? 'Kekuatan : ' + item.kekuatan : '') + ' ' +
                                    (item.sediaan ?
                                    item.sediaan : '')
                                    }}</p>
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-6" v-if="showRacikanDose">
                        <VField class="is-rounded-select is-autocomplete-select">
                            <VLabel class="required-field">Jenis Racikan</VLabel>
                            <VControl icon="feather:search" fullwidth class="prime-auto-select">
                                <Dropdown v-model="item.jenisRacikan" :options="d_jenisRacikan" optionLabel="label"
                                    class="is-rounded" placeholder="Pilih data" style="width: 100%;" :filter="true" />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-3">
                        <VField>
                            <VLabel>Konversi</VLabel>
                            <VControl icon="feather:bookmark">
                                <VInput type="text" v-model="item.nilaiKonversi" placeholder="Konversi" disabled
                                    class="is-rounded" />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-3">
                        <VField>
                            <VLabel>Stok</VLabel>
                            <VControl icon="feather:bookmark">
                                <VInput type="text" v-model="item.stok" placeholder="Stok" class="is-rounded"
                                    disabled />
                            </VControl>
                        </VField>
                    </div>

                    <div class="column is-6">
                        <VField class="is-rounded-select is-autocomplete-select">
                            <VLabel class="required-field">Produk</VLabel>
                            <VControl icon="feather:search" class="prime-auto">
                                <AutoComplete v-model="item.produk" :suggestions="d_produk"
                                        @complete="fetchProduk($event)" :optionLabel="'namaproduk'" :dropdown="true"
                                        :minLength="3" class="is-rounded" :appendTo="'body'"
                                        :loadingIcon="'pi pi-spinner'" :field="'kode_namaproduk'" :disabled="isQtyDisabled"
                                        placeholder="ketik untuk mencari..." @item-select="changeProduk(item.produk)">
                                        <template #option="slotProps">
                                            <div class="columns is-multiline">
                                                <div class="column is-12">
                                                    <span style="font-weight:bold">{{ slotProps.option.name }}</span>
                                                </div>
                                                <div class="column is-12 mt-5-min">
                                                    <table style="width:50%">
                                                        <tr>
                                                            <td><b>[{{ slotProps.option.kdproduk }}] {{ slotProps.option.namaproduk }}</b></td>
                                                        </tr>
                                                        <tr>
                                                            <td>Kategory : {{ slotProps.option.satuanstandar }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td><b>Stok : {{ slotProps.option.stok }}</b></td>
                                                        </tr>
                                                    </table>
                                                </div>
                                            </div>
                                        </template>
                                    </AutoComplete>
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-6">
                        <VField class="is-rounded-select is-autocomplete-select">
                            <VLabel class="required-field">Satuan</VLabel>
                            <VControl icon="feather:search" fullwidth class="prime-auto-select">
                                <Dropdown v-model="item.satuan" :options="d_satuan" optionLabel="label"
                                    class="is-rounded" placeholder="Pilih data" style="width: 100%;" :filter="true"
                                    @change="changeSatuan(item.satuan)" />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-6">
                        <VField class="is-rounded-select is-autocomplete-select">
                            <VLabel>Route</VLabel>
                            <VControl icon="feather:search" fullwidth class="prime-auto-select">
                                <Dropdown v-model="item.route" :options="d_route" optionLabel="label" class="is-rounded"
                                    placeholder="Pilih data" style="width: 100%;" :filter="true" />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-6">
                        <VField class="is-rounded-select is-autocomplete-select">
                            <VLabel>Tgl Kadaluarsa</VLabel>
                            <VControl icon="feather:search" fullwidth class="prime-auto-select">
                                <Dropdown v-model="item.tglKadaluarsa" :options="d_tglKadaluarsa" optionLabel="label"
                                    class="is-rounded" placeholder="Pilih data" style="width: 100%;" :filter="true"
                                    @change="changeExpired(item.tglKadaluarsa)" showClear />
                            </VControl>
                        </VField>
                        <small style="color: #cd2b2b;font-weight: 500;" v-if="item.tglKadaluarsa != null">{{infoStokTgl}}</small>
                    </div>
                    <div class="column is-6">
                        <div class="columns is-multiline">
                            <div class="column is-4">
                                <div class="checkboxes">
                                    <VField>
                                        <VLabel class="item">Aturan Pakai</VLabel>
                                        <VControl icon="feather:bookmark">
                                            <VInput type="text" v-model="item.aturanpakai" placeholder="Aturan Pakai"
                                                class="is-rounded" />
                                        </VControl>
                                    </VField>
                                </div>
                            </div>
                            <div class="column is-2" style="margin-top:2rem" v-for="(opsi) in listDataSigna">
                                <VField>
                                    <VControl raw subcontrol>
                                        <VCheckbox v-model="opsi.isChecked" class="p-0" :label="opsi.nama"
                                            @change="addListAturanPakai(opsi.isChecked, opsi)" color="primary" square />
                                    </VControl>
                                </VField>
                            </div>
                        </div>
                    </div>

                    <div class="column is-6">
                        <VField class="is-rounded-select is-autocomplete-select">
                            <VLabel class="required-field">Satuan Resep</VLabel>
                            <VControl icon="feather:search" fullwidth class="prime-auto-select">
                                <Dropdown v-model="item.satuanresep" :options="d_satuanResep" optionLabel="label"
                                    class="is-rounded" placeholder="Pilih data" style="width: 100%;" :filter="true" />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-6">
                        <div class="columns is-multiline">
                            <div class="column is-2" >
                            <VField>
                                <VControl raw subcontrol>
                                    <VCheckbox v-model="item.isbud"
                                        color="warning" square >
                                        <span class = "font-bold text-black">BUD</span>
                                    </VCheckbox>
                                </VControl>
                            </VField>
                            </div>
                            <div class="column is-4" v-if="item.isbud">
                                <VDatePicker v-model="item.tglpemakaian" color="green" trim-weeks
                                    mode="dateTime">
                                    <template #default="{ inputValue, inputEvents }">
                                        <VField>
                                            <VLabel class="item">Batas Pemakaian</VLabel>
                                            <VControl icon="feather:calendar">
                                                <VInput type="text" placeholder="Select a date"
                                                    class="is-rounded" :value="inputValue"
                                                    v-on="inputEvents" :disabled="disTanggal" />
                                            </VControl>
                                        </VField>
                                    </template>
                                </VDatePicker>
                            </div>
                        </div>
                        </div>
                    <div class="column is-6">
                        <VField>
                            <VLabel>Keterangan</VLabel>
                            <VControl icon="feather:bookmark">
                                <VInput type="text" v-model="item.KeteranganPakai" placeholder="Keterangan"
                                    class="is-rounded" />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-6">
                        <VField>
                            <VLabel>Qty</VLabel>
                            <VControl icon="feather:bookmark">
                                <VInput type="number" v-model="item.jumlah" placeholder="Qty" class="is-rounded" />
                            </VControl>
                        </VField>
                    </div>

                    <div class="column is-6">
                        <VField>
                            <VLabel>Harga</VLabel>
                            <VControl icon="feather:bookmark">
                                <VInput type="text" v-model="item.hargaSatuan" placeholder="Harga" disabled
                                    class="is-rounded" />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-4">
                        <VField>
                            <VLabel>Harga Diskon</VLabel>
                            <VControl icon="feather:bookmark">
                                <VInput type="text" v-model="item.hargadiskon" placeholder="Harga Diskon" disabled
                                    class="is-rounded" />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-2">
                        <VField>
                            <VLabel>Diskon (%)</VLabel>
                            <VControl icon="feather:bookmark">
                                <VInput type="number" v-model="item.persenDiskon" placeholder="Diskon (%)" disabled
                                    class="is-rounded" @input="changeDikson($event.target.value)" />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-6">
                        <VField>
                            <VLabel>Total</VLabel>
                            <VControl icon="feather:bookmark">
                                <VInput type="text" v-model="item.total" placeholder="Total" class="is-rounded"
                                    disabled />
                                <!-- <VCurrency disabled v-model.lazy="item.total" class="is-rounded"
                                            :options="{ currency: 'IDR' }" placeholder="Total" /> -->
                            </VControl>
                        </VField>
                    </div>
                </div>

                <div class="column is-12">
                    <div class="content">
                        <div class="is-divider" :data-content="infoStok" />
                    </div>
                </div>

                <div class="column is-12">
                    <DataTable :value="dataSourceStokProduk" :paginator="true" :rows="5"
                        :rowsPerPageOptions="[5, 10, 25]"
                        paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
                        responsiveLayout="stack" breakpoint="960px" sortMode="multiple"
                        currentPageReportTemplate="Showing {first} to {last} of {totalRecords}" showGridlines>

                        <Column field="no" header="No"></Column>
                        <Column field="namaruangan" header="ruangan"></Column>
                        <Column field="stok" header="Stok"></Column>
                    </DataTable>

                </div>
            </form>
        </template>
        <template #action>
            <VButton v-if="item.no" icon="feather:plus" @click="add(item)" :loading="isLoading" color="primary" raised>
                Update</VButton>
            <VButton v-else icon="feather:plus" @click="add(item)" :loading="isLoading" color="primary" raised :disabled="isDisabled">Tambah
            </VButton>
        </template>
    </VModal>
</template>

<script setup lang="ts">

import { useWindowScroll } from '@vueuse/core'
import { useApi } from '/@src/composable/useApi'
import {
    ref,
    computed,
    defineComponent,
    watch,
    nextTick,
    onMounted,
    reactive,
    watchEffect
} from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useHead } from '@vueuse/head'
import { useToaster } from '/@src/composable/toaster'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import ConfirmDialog from 'primevue/confirmdialog'
import { useConfirm } from 'primevue/useconfirm'
import Dialog from 'primevue/dialog';
import Checkbox from 'primevue/checkbox';
import PrimeVue from 'primevue/config';
import * as H from '/@src/utils/appHelper'
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import Toolbar from 'primevue/toolbar'
import Dropdown from 'primevue/dropdown';
import AutoComplete from 'primevue/autocomplete';
import moment from 'moment'
const TITLE_PAGE = 'Obat Alkes'
useHead({
    title: `Form Penjualan Obat  - Transmedic`,
})
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
let NOREC_ORDER: any = useRoute().query.norec_order as string
let NOREC_RESEP: any = useRoute().query.norec as string
let item: any = reactive({
    header: {},
    totalAll: 0,
    isbud: false,
    jumlah: 0,
    persenDiskon: 0,
    hargadiskon: 0,
    tglresep: new Date(),
    rke: 1,
    resep: '-',
    chkp: 0,
    chks: 0,
    chksr: 0,
    chkm: 0,
})
const modalInput = ref(false)
const TOTAL: any = ref(0)
const { y } = useWindowScroll()
const confirm = useConfirm()
const isStuck = computed(() => {
    return y.value > 30
})
const d_penulisResep: any = ref([])
const d_Kebangsaan: any = ref ([])
const d_ruangan: any = ref([])
const d_produk: any = ref([])
const d_satuan: any = ref([])
const d_kemasan: any = ref([])
const d_jenisRacikan: any = ref([])
const d_route: any = ref([])
const d_tglKadaluarsa: any = ref([])
const d_satuanResep: any = ref([])
const d_asalProduk: any = ref([])
const dataSource: any = ref([])
const listRiwayat = ref([])
const data2: any = ref([])
const isloadingPaketObat: any = ref(false)
const isloadingTambahPaket: any = ref(false)
const dataOK: any = ref([])
const dataSourceStokProduk: any = ref([])
const norecSPD: any = ref('')
const norecTerima: any = ref('')
const isLoading: any = ref(false)
const btnLoadEdit: any = ref(false)
const isSimpan: any = ref(false)
const tarifJasa: any = ref(0)
const hrg1: any = ref(0)
const hrgsdk: any = ref(0)
const dataProdukDetail: any = ref([])
const isDisabled: any = ref(false)
const disabledRuangan: any = ref(false)
const btnSaveandUpdate: any = ref(false)
const infoStok: any = ref('List Informasi Stok')
const dataGridKronis: any = ref([])
const dataSelected: any = ref({})
const isPemakaianObatAlkes: any = ref(false)
const disTanggal: any = ref(false)
const checkResepPulang: any = ref(false)
const isEdit: any = ref(false)
const infoStokTgl: any = ref('')
const expandedRows = ref()
const modalDataPaketObat: any = ref(false)
const dataSourcePaketObat = ref([])
const showRacikanDose: any = ref(false)
const renderLoader: any = ref(false)
const isLoadEdit: any = ref(false)
const isMerge: any = ref(false)
const listColor2: any = ref(['primary', 'info', 'orange', 'yellow', 'success'])
const listDataSigna = ref([
    { "id": 1, "nama": "P", "isChecked": false },
    { "id": 2, "nama": "S", "isChecked": false },
    { "id": 3, "nama": "Sr", "isChecked": false },
    { "id": 4, "nama": "M", "isChecked": false }
])

// const fetchProduk = async (filter: any) => {

//     const response = await useApi().get(
//         `/farmasi/dropdown-obat?namaproduk=${filter.query}&limit=10`)
//     d_produk.value = response

// }


const fetchProduk = async (filter: any) => {

const response = await useApi().get(`/farmasi/dropdown-obat?namaproduk=${filter.query}&ruanganfk=${item.ruangan.value.id}&limit=10`)
    d_produk.value = response
}

const fetchPasien = async (nocm: any) => {
    renderLoader.value = true
    await useApi().get(`/farmasi/get-pasien?nocm=${nocm}`).then((response) => {
        if (response) {
            item.namalengkap = response.namapasien
            item.notlp = response.notelepon ? response.notelepon : '-'
            item.tgllahir = response.tgllahir
            item.alamatlengkap = response.alamatlengkap
        } else {
            useToaster().error('Pasien Tidak Ditemukan')
        }
        renderLoader.value = false
    })
}

const pilihPaketObat = (e) => {
    if (e.details.length === 0) {
        H.alert('error', 'Obat pada paket ini tidak ada');
        return;
    }

    e.details.forEach((item, i) => {
        let data = {
            no: i + 1,
            rke: item.rke,
            jeniskemasan: item.jeniskemasan,
            namaproduk: item.namaproduk,
            jumlah: item.jumlah,
            hargasatuan: item.hargasatuan,
            total: item.total,
        };
        data2.value.push(data);
    });

    dataSource.value = data2.value;
};

function setColor() {
  let z = 0
  for (let zx = 0; zx < data2.value.length; zx++) {
    const element = data2.value[zx];
    element.icon = 'fa-inverse lnir lnir-medicine-alt'
    element.color = listColor2.value[z]
    if (z > 4) {
      z = 0
    }
    z++
  }
}

const loadDrop = async () => {
    isLoadEdit.value = true
    const response = await useApi().get(`/farmasi/input-resep-cbo`)
    d_kemasan.value = response.jeniskemasan
    // d_produk.value = response.produk.map((e: any) => { return { label: e.namaproduk, value: e } })
    const response2 = await useApi().get(`/logistik/kartu-stok-cbo`)
    d_ruangan.value = response2.ruangan.map((e: any) => ({ label: e.namaruangan, value: e }));
    item.ruangan = d_ruangan.value[0] || null; 
    console.log('cek',item.ruangan) 
    // d_ruangan.value = response.ruanganFarmasi.filter((e: any) => e.id !== 343).map((e: any) => ({ label: e.namaruangan, value: e }));
    d_penulisResep.value = response.penulisresep.map((e: any) => { return { label: e.namalengkap, value: e } })
    console.log("d_penulisResep:", d_penulisResep.value);
    d_Kebangsaan.value = response.kebangsaan.map((e: any) => { return { label: e.name, value: e } })
    console.log("d_Kebangsaan:", d_Kebangsaan.value);
    d_jenisRacikan.value = response.jenisracikan.map((e: any) => { return { label: e.jenisracikan, value: e } })
    d_route.value = response.route.map((e: any) => { return { label: e.name, value: e } })
    d_satuanResep.value = response.satuanresep.map((e: any) => { return { label: e.satuanresep, value: e } })
    d_asalProduk.value = response.asalproduk.map((e: any) => { return { label: e.asalproduk, value: e } })
    // item.aturanPakai = d_aturanPakai.value[1]
    item.jenisKemasan = response.jeniskemasan[1]
    item.tarifadminresep = response.tarifadminresep ? response.tarifadminresep : 0

    if (NOREC_RESEP) {
        await loadEdit()
    }
    isLoadEdit.value = false
}

const getProdukListStok = async (e: any) => {

    let namaproduk
    let isDonasi = item.checkisDonasi ? `&isdonasi=${item.checkisDonasi}` : ''
    await useApi().get(`farmasi/get-stok-produk-by-ruangan?produkfk=${e}${isDonasi}`).then((response) => {
        response.forEach((element: any, i: any) => {
            element.no = i + 1
            namaproduk = element.namaproduk
        });
        dataSourceStokProduk.value = response
        infoStok.value = `List Stok Produk ${namaproduk} Per Ruangan`
    })

}


const loadEdit = async () => {

    isLoadEdit.value = true
    await useApi().get(`farmasi/get-detail-pasien-pesanan?norecResep=${NOREC_RESEP}`).then((response) => {
        console.log(response)
        let dataPasien = response.detailresep
        item.nocm = dataPasien.nocm ? dataPasien.nocm : ''
        item.ceknomr = dataPasien.nocm ? true : false
        item.namalengkap = dataPasien.nama
        item.tgllahir = dataPasien.tgllahir
        item.notlp = dataPasien.notlp
        item.alamatlengkap = dataPasien.alamat
        item.noresep = dataPasien.nostruk
        item.tglresep = dataPasien.tglresep
        item.resultTotal = dataPasien.totalharusdibayar
        data2.value = response.pelayananPasien
        dataSource.value = response.pelayananPasien
        d_penulisResep.value.forEach((element: any) => {
            if (element.value.id == dataPasien.pgid) {
                item.penulisresep = element
                return
            }
        });
        console.log('testing',d_Kebangsaan)
        d_Kebangsaan.value.forEach((element: any) => {
            if (element.value.id == dataPasien.kbid) {
                item.kebangsaan = element
                return
            }
        });
        d_ruangan.value.forEach((element: any) => {
            if (element.value.id == dataPasien.ruid) {
                item.ruangan = element
                return
            }
        });

        isLoadEdit.value = false
    })
    // changeDisabledRuangan()
}

const editPopUp = async (e: any) => {
    console.log(e)
    btnLoadEdit.value = true
    // console.log(e)
    await fetchProduk({ query: e.productname ? e.productname : e.namaproduk })
    d_produk.value.forEach((element: any) => {
        if (element.id == e.produkfk) {
            item.produk = element
            return
        }
    });
    modalInput.value = true
    item.no = e.no
    item.rke = e.rke
    item.jumlahxmakan = e.jmlxmakan
    item.nilaiKonversi = e.nilaikonversi
    item.route = e.routefk
    item.persenDiskon = e.persendiscount
    item.KeteranganPakai = e.keterangan
    item.aturanpakai = e.aturanpakai
    item.pagi = e.ispagi
    item.siang = e.issiang
    item.sore = e.issore
    item.malam = e.ismalam
    d_kemasan.value.forEach((element: any) => {
        if (element.id == e.jeniskemasanfk) {
            item.jenisKemasan = element
            return
        }
    });
    d_jenisRacikan.value.forEach((element: any) => {
        if (element.value.id == e.jenisobatfk) {
            item.jenisRacikan = element
            return
        }
    });
    d_route.value.forEach((element: any) => {
        if (element.value.id == e.routefk) {
            item.route = element
            return
        }
    });
    d_tglKadaluarsa.value.forEach((element: any) => {
        if (element.value.tglkadaluarsa == e.tglkadaluarsa) {
            item.tglKadaluarsa = element
            return
        }
    });

    d_satuanResep.value.forEach((element: any) => {
        if (element.value.id == e.satuanresepfk) {
            item.satuanresep = element
        }
    })

    tarifJasa.value = e.jasa
    dataSelected.value = e
    btnLoadEdit.value = false
    GETKONVERSI()
}

const inputPopUp = () => {
    if (!item.ruangan) {
        useToaster().error('Ruangan harus di pilih')
        return
    }
    if (!item.namalengkap) {
        useToaster().error('Nama Pasien harus di pilih')
        return
    }
    if (!item.kebangsaan) {
        useToaster().error('Kebangsaan harus di pilih')
        return
    }
    if (NOREC_RESEP) {
        dataSource.value.forEach((element: any, i: any) => {
            if (dataSource.value.length - 1 == i) {
                d_kemasan.value.forEach((e: any) => {
                    if (e.id == element.jeniskemasanfk) {
                        item.jenisKemasan = e
                        return
                    }
                });
                if (element.jeniskemasanfk == 1) {
                    item.rke = element.rke
                } else {
                    item.rke = element.rke + 1
                }
            }
        });
    }
    clearInput()
    modalInput.value = true

}

const add = (e: any) => {

    if (!item.produk) {
        useToaster().error('Produk harus di isi')
        return
    }
    if (!item.jumlah || item.jumlah == 0) {
        useToaster().error('Jumlah harus di isi')
        return
    }
    if (item.hargaSatuan == 0) {
        useToaster().error('Harga Satuan belum ada')
        return
    }

    if (item.stok == 0) {
        useToaster().error('Stok tidak ada')
        return
    }
    if (item.tglKadaluarsa & item.stokByEd < item.jumlah ) {
        useToaster().error('Stok Tidak Cukup')
        return
    }

    if (item.tglKadaluarsa & norecSPD.value == '') {
        useToaster().error('Stok tidak ada')
        return
    }
    if (!item.satuan) {
        useToaster().error('Satuan harus di isi')
        return
    }
    if (!item.aturanpakai) {
        useToaster().error('Aturan Pakai harus di isi')
        return
    }
    var dosis = 1;
    if (item.jenisKemasan.jeniskemasan == 'Racikan') {
        dosis = item.dosis
        item.jumlahxmakan = Math.floor((parseFloat(item.jumlah) / parseFloat(item.dosis)) * parseFloat(item.kekuatan))
    } else {
        item.jumlahxmakan = item.jumlah
    }

    let jmlbulat = 0;
    let jml = 0;

    jmlbulat = item.jumlahbulat;
    jml = item.jumlah;

    var data: any = {};
    if (item.no) {
        dataSource.value.forEach((element: any, i: any) => {
            if (element.no == item.no) {
                data.no = element.no,
                    data.generik = null,
                    data.hargajual = String(item.hargaSatuan),
                    data.jenisobatfk = item.jenisRacikan ? item.jenisRacikan.value.id : null,
                    data.jenisobat = item.jenisRacikan ? item.jenisRacikan.label : null,
                    data.jmlxmakan = item.jumlahxmakan,
                    data.stock = String(item.stok),
                    data.harganetto = String(item.hargaNetto),
                    data.nostrukterimafk = norecTerima.value,
                    data.norec_spd = norecSPD.value,
                    data.ruanganfk = item.ruangan.value.id,
                    data.rke = item.rke,
                    data.ispagi = item.chkp,
                    data.issiang = item.chks,
                    data.issore = item.chksr,
                    data.ismalam = item.chkm,
                    data.jeniskemasanfk = item.jenisKemasan.id,
                    data.jeniskemasan = item.jenisKemasan.jeniskemasan,
                    data.aturanpakai = item.aturanpakai ?? '-'; 
                    data.routefk = item.route ? item.route.value.id : null,
                    data.route = item.route ? item.route.value.name : null,
                    data.asalprodukfk = item.asal.id,
                    data.asalproduk = item.asal.asalproduk,
                    data.produkfk = item.produk.id,
                    data.namaproduk = item.produk.namaproduk,
                    data.kdproduk = item.produk.kdproduk,
                    data.productname = item.produk.productname,
                    data.nilaikonversi = item.nilaiKonversi,
                    data.satuanstandarfk = item.satuan.value.ssid,
                    data.satuanstandar = item.satuan.value.satuanstandar,
                    data.satuanviewfk = item.satuan.value.ssid,
                    data.satuanview = item.satuan.value.satuanstandar,
                    data.jmlstok = String(item.stok),
                    data.jumlah = jmlbulat,//item.jumlahbulat,
                    data.jumlahobat = jml,//item.jumlah,
                    data.dosis = dosis,
                    data.hargasatuan = String(item.hargaSatuan),
                    data.hargadiscount = String(item.hargadiskon),
                    data.persendiscount = item.persenDiskon ? item.persenDiskon : 0,
                    data.total = item.total,
                    data.jmldosis = String(item.jumlahxmakan) + '/' + String(dosis) + '/' + String(item.kekuatan),
                    data.jasa = tarifJasa.value,
                    data.keterangan = item.KeteranganPakai ? item.KeteranganPakai : null,
                    data.satuanresepfk = item.satuanresep ? item.satuanresep.value.id : null,
                    data.satuanresep = item.satuanresep ? item.satuanresep.value.satuanresep : null,
                    data.tglkadaluarsa = item.tglKadaluarsa ? item.tglKadaluarsa.label : null,
                    data.isbud = item.isbud ? item.isbud : null,
                    data.tglpemakaian = item.tglpemakaian ? item.tglpemakaian : null,
                data2.value[i] = data
            }
        });

    } else {
        if (data2.length > 0) {
            var racikan = data2.value[data2.value.length - 1].jeniskemasan
            if (racikan == 'Non Racikan') {
                item.rke = data2.value[data2.value.length - 1].rke + 1
            }
        }
        data = {
            no: dataSource.value.length == 0 ? 1 : dataSource.value.length + 1,
            generik: null,
            hargajual: String(item.hargaSatuan),
            jenisobatfk: item.jenisRacikan ? item.jenisRacikan.value.id : null,
            jenisobat: item.jenisRacikan ? item.jenisRacikan.label : null,
            jmlxmakan: item.jumlahxmakan,
            stock: String(item.stok),
            harganetto: String(item.hargaNetto),
            nostrukterimafk: norecTerima.value,
            norec_spd: norecSPD.value,
            ruanganfk: item.ruangan.value.id,
            rke: item.rke,
            jeniskemasanfk: item.jenisKemasan.id,
            jeniskemasan: item.jenisKemasan.jeniskemasan,
            // aturanpakaifk: item.aturanPakai.id,
            aturanpakai: item.aturanpakai ?? '-',
            ispagi: item.chkp,
            issiang: item.chks,
            issore: item.chksr,
            ismalam: item.chkm,
            keteranganPakai: item.keteranganPakai,
            routefk: item.route ? item.route.value.id : null,
            route: item.route ? item.route.value.name : null,
            asalprodukfk: item.asal.id,
            asalproduk: item.asal.asalproduk,
            produkfk: item.produk.id,
            namaproduk: item.produk.namaproduk,
            kdproduk: item.produk.kdproduk,
            productname: item.produk.productname,
            nilaikonversi: item.nilaiKonversi,
            satuanstandarfk: item.satuan.value.ssid,
            satuanstandar: item.satuan.value.satuanstandar,
            satuanviewfk: item.satuan.value.ssid,
            satuanview: item.satuan.value.satuanstandar,
            jmlstok: String(item.stok),
            jumlah: jmlbulat,//item.jumlahbulat,
            jumlahobat: jml,//item.jumlah,
            dosis: dosis,
            hargasatuan: String(item.hargaSatuan),
            hargadiscount: String(item.hargadiskon),
            persendiscount: item.persenDiskon ? item.persenDiskon : 0,
            total: item.total,
            jmldosis: String(item.jumlahxmakan) + '/' + String(dosis) + '/' + String(item.kekuatan),
            jasa: tarifJasa.value,
            keterangan: item.KeteranganPakai ? item.KeteranganPakai : null,
            satuanresepfk: item.satuanresep ? item.satuanresep.value.id : null,
            satuanresep: item.satuanresep ? item.satuanresep.value.satuanresep : null,
            tglkadaluarsa: item.tglKadaluarsa ? item.tglKadaluarsa.label : null,
            tglregistrasi: moment(new Date()).format('YYYY-MM-DD HH:mm:ss'),
            isbud: item.isbud ? item.bud : null,
            tglpemakaian: item.tglpemakaian ? item.tglpemakaian : null
        }
        data2.value.push(data)

    }
    dataSource.value = data2.value
    console.log(dataSource.value)
    changeDisabledRuangan()
    if (item.jenisKemasan.jeniskemasan != 'Racikan') {
        item.rke = parseFloat(item.rke) + 1
    }
    // console.log(dataSource.value)
    countTotal()
    clearInput()
}

const changeDisabledRuangan = () => {
    if (dataSource.value.length > 0) {
        disabledRuangan.value = true
    } else {
        disabledRuangan.value = false
    }
}
const paketObat = async () => {

  let namaPaket = '';
  if (item.namapaket) namaPaket = `namapaket=${item.namapaket}`;
  if (!item.ruangan) {
        useToaster().error('Ruangan harus di pilih')
        return
    }

  await useApi()
    .get(`/farmasi/data-paket-obat?${namaPaket}`)
    .then((response: any) => {
      isloadingPaketObat.value = false;
      console.log('Data response:', response.data);
      response.data.forEach((element: any, i: any) => {
        expandedRows.value = element.details.forEach((data: any, i: any) => {
          data.no = i + 1;
        });
        element.no = i + 1;
      });
      dataSourcePaketObat.value = response.data;
      modalDataPaketObat.value = true;
      console.log('modalDataPaketObat:', modalDataPaketObat.value);
    })
    .catch((err: any) => {
      isloadingPaketObat.value = false;
      console.error('Error:', err);
    });
};


const countTotal = () => {
    let total = 0
    for (let x = 0; x < data2.value.length; x++) {
        const element = data2.value[x];
        total = total + parseFloat(element.total)
    }
    item.resultTotal = total
}

const dialogConfirm = (e: any) => {
    confirm.require({
        message: 'Apakah anda serius menghapus data ini ?',
        header: 'Konfirmasi Hapus Data',
        icon: 'pi pi-info-circle',
        acceptClass: 'p-button-danger',
        accept: () => {
            for (var i = dataSource.value.length - 1; i >= 0; i--) {
                if (dataSource.value[i].no == e.no) {
                    dataSource.value.splice(i, 1);
                }
            }
            countTotal()
            clearInput()
            changeDisabledRuangan()
        },
        reject: () => { },
    })
}

const save = async () => {
    if (!item.penulisresep) {
        useToaster().error("Penulis resep tidak boleh kosong");
        return;
    }
    if (data2.value.length === 0) {
        useToaster().error('Produk belum dipilih');
        return;
    }

    for (let i = 0; i < data2.value.length; i++) {
        const product = data2.value[i];
        if (product.hargasatuan === 0) {
            useToaster().error("Terdapat obat dengan harga kosong, kemungkinan stock kosong!");
            return;
        }
        if (parseFloat(product.jmlstok) < parseFloat(product.jumlah)) {
            useToaster().error(`Terdapat obat dengan jumlah melebihi stok: ${product.namaproduk}`);
            return;
        }
    }

    btnSaveandUpdate.value = true;
    const objSave = {
        strukresep: {
            alamat: item.alamatlengkap ?? '-',
            karyawan: '',
            keteranganlainnya: 'Pemesanan Obat',
            namapasien: item.namalengkap,
            noTelepon: item.notlp ?? '-',  
            nocm: item.nocm || '',
            nostruk: NOREC_RESEP || '',
            penulisresepfk: item.penulisresep?.value?.id || null,
            ruanganfk: item.ruangan?.value?.id || '',
            kebangsaanfk :item.kebangsaan.value?.id || '',
            tglresep: moment(item.tglresep).format('YYYY-MM-DD HH:mm:ss'),
            tglLahir: moment(item.tgllahir).format('YYYY-MM-DD HH:mm:ss'),
            total: item.resultTotal,
        },
        details: data2.value
    };

    isSimpan.value = true;
    try {
        const response = await useApi().post(`/farmasi/save-input-non-layanan-pesanan`, objSave);
        btnSaveandUpdate.value = false;
        window.history.back();
    } catch (error) {
        btnSaveandUpdate.value = false;
        useToaster().error('Terjadi kesalahan saat menyimpan data');
        console.error('Save error:', error);

        console.log('iam here', objSave);
        
    }
};
const changeProduk = (e: any) => {
    if (e != null) {
        GETKONVERSI()
        getProdukListStok(e.id)
    }
}
const changeExpired = (e: any) => {
    if (e != null) {
        infoStokTgl.value = 'Qty Stok Per Tgl : ' + e.value
        item.stokByEd = e.value
        setNorecSPD()
    }
}

const GETKONVERSI = () => {
    if (item.produk.konversisatuan.length == 0) {
        item.nilaiKonversi = 1
        d_satuan.value = [
            {
                label: item.produk.satuanstandar, value:
                    { ssid: item.produk.ssid, satuanstandar: item.produk.satuanstandar }
            }]
    } else {
        d_satuan.value = item.produk.konversisatuan.map((e: any) => {
            return { label: e.satuanstandar, value: e, nilaikonversi: e.nilaikonversi }
        })
    }

    item.nilaiKonversi = 1
    dataProdukDetail.value = []
    d_tglKadaluarsa.value = []
    delete item.stokByEd
    infoStokTgl.value = ''
    useApi().get(
        '/farmasi/get-produkdetail?produkfk=' + item.produk.id +
        '&ruanganfk=' + item.ruangan.value.id).then(function (response: any) {
            dataProdukDetail.value = response.detail
            if (response.detail.length > 0) {
                d_satuan.value.forEach((element: any) => {
                    if (element.value.ssid == item.produk.ssid) {
                        item.satuan = element
                        item.nilaiKonversi = element.value.nilaikonversi ? element.value.nilaikonversi : 1
                        // debugger
                    }
                });
                response.detail.forEach((element: any) => {
                    if (element.tglkadaluarsa != null) {
                        d_tglKadaluarsa.value.push({ label: element.tglkadaluarsa, value: element.qtyproduk})
                    }
                });

                item.tglKadaluarsa = {label : response.detail[0].tglkadaluarsa, value : response.detail[0].qtyproduk}
                // d_tglKadaluarsa.value.forEach((element: any) => {
                //     console.log(element)
                //     if (element.value.tglkadaluarsa == response.detail[0].tglkadaluarsa) {
                //         item.tglKadaluarsa = element
                //         return
                //     }
                // });

                infoStokTgl.value = item.tglKadaluarsa ? 'Qty Stok Per Tgl : ' + response.detail[0].qtyproduk : ''
                // item.stokByEd = response.detail[0].qtyproduk

                item.stok = parseFloat(response.jmlstok) / parseFloat(item.nilaiKonversi)
                if (response.kekuatan == undefined || response.kekuatan == 0) {
                    response.kekuatan = 1
                }
                item.kekuatan = response.kekuatan
                item.sediaan = response.sediaan

                item.jumlah = item.jumlah ? item.jumlah : '1';
                setNorecSPD()
                if (dataSelected.value.no != undefined) {
                    infoStokTgl.value = ''
                    item.jumlah = Math.ceil(parseFloat(dataSelected.value.jumlahobat))
                    item.jumlahobat = dataSelected.value.jumlahobat
                    item.dosis = dataSelected.value.dosis
                    item.jumlahxmakan = Math.floor((parseFloat(item.jumlah) / parseFloat(item.dosis)) * parseFloat(item.kekuatan))
                    item.nilaiKonversi = dataSelected.value.nilaikonversi
                    console.log(dataSelected.value)
                    d_satuan.value.forEach((element: any) => {
                        if (element.value.ssid == dataSelected.value.satuanstandarfk) {
                            item.satuan = element
                            return
                        }
                    });
                    d_tglKadaluarsa.value.forEach(element => {
                        if(element.label ==  dataSelected.value.tglkadaluarsa){
                            item.tglKadaluarsa = element
                            infoStokTgl.value = 'Qty Stok Per Tgl : ' + item.tglKadaluarsa.value

                        }
                    });
                    // item.stokByEd = dataSelected.value.stokByEd
                    // item.infoStokTgl = 'Qty Stok Per Tgl : ' + dataSelected.value.stokByEd
                    // changeExpired()
                    // response.detail.forEach(el => {
                    //     if(el.norec_spd == dataSelected.value.norec_spd){
                    //        item.stokByEd = el.qtyproduk
                    //        item.infoStokTgl = 'Qty Stok Per Tgl : ' + el.qtyproduk
                    //        item.tglKadaluarsa = {tglkadaluarsa : el.tglkadaluarsa}
                    //     }
                    // });

                    item.hargaSatuan = dataSelected.value.hargasatuan
                    item.hargadiskon = dataSelected.value.hargadiscount
                    item.hargaNetto = dataSelected.value.harganetto
                    item.total = dataSelected.value.total
                }
            } else {
                item.jumlahxmakan = Math.floor((parseFloat(item.jumlah) / parseFloat(item.dosis)) * parseFloat(item.kekuatan))
                item.hargaSatuan = 0
                item.hargadiskon = 0
                item.hargaNetto = 0
                item.total = 0
            }
        });

}

const clearInput = () => {
    delete item.produk
    delete item.asal
    delete item.satuan
    delete item.dosis
    delete item.no
    delete item.satuanresep
    delete item.route
    delete item.aturanpakai
    delete item.KeteranganPakai
    delete dataSelected.value
    delete item.tglKadaluarsa
    delete item.jenisRacikan
    delete item.jeniskemasan
    delete item.dosis
    infoStokTgl.value = ''
    item.chkp = 0
    item.chks = 0
    item.chksr = 0
    item.chkm = 0
    item.qty = 1
    modalInput.value = false
    item.nilaiKonversi = 0
    item.stok = 0
    item.jumlah = 0
    item.jumlahbulat = item.jumlah
    item.jumlahxmakan = 1
    item.hargadiskon = 0
    item.total = 0
    item.hargaSatuan = 0
    item.hargaNetto = 0
    item.persenDiskon = 0
    listDataSigna.value.forEach((element: any) => {
        element.isChecked = false
    });
}
const changeDikson = (e: any) => {
    if (e) {
        item.hargadiskon = 0;
        if (item.persenDiskon > 100) {
            item.persenDiskon = 100
        }
        if (item.persenDiskon < 0) {
            item.persenDiskon = 0
        }

        if (item.persenDiskon > 0) {
            var diskon = (parseFloat(item.hargaSatuan) * parseFloat(item.persenDiskon)) / 100
            if (!isNaN(diskon)) {
                item.hargadiskon = diskon;
                item.total = parseFloat(item.hargaSatuan) - parseFloat(item.hargadiskon);
            }
        }
    } else {
        item.hargadiskon = 0
        item.total = parseFloat(item.hargaSatuan) - parseFloat(item.hargadiskon);
    }
}

const setNorecSPD = () => {
    if (!item.jumlah) return

    if (item.jenisKemasan == undefined) {
        return
    }
    if (item.stok == 0) {
        item.jumlah = 0
        return;
    }
    var qty20 = 0
    tarifJasa.value = parseFloat(item.tarifadminresep)
    if (item.jumlah == 0) {
        tarifJasa.value = 0;
    } else if (dataProdukDetail.value[0]?.objectasalprodukfk === 3) {
        tarifJasa.value = 0;
    } else if (parseFloat(tarifJasa.value) != 0) {
        if (item.jenisKemasan.id == 2) {
            // Cek kondisi objectkebangsaanfk
            if (item.kebangsaan == 1) {
                tarifJasa.value = parseFloat(item.tarifWNI);
            } else if (item.header.kebangsaan == 2) {
                tarifJasa.value = parseFloat(item.tarifKitas);
            } else if (item.header.kebangsaan == 3) {
                tarifJasa.value = parseFloat(item.tarifNonKitas);
            }
        }

    if (item.jenisKemasan.id == 1) {
        qty20 = Math.floor(parseFloat(item.jumlah) / 20);
        if (parseFloat(item.jumlah) % 20 == 0) {
            qty20 = qty20;
        } else {
            qty20 = qty20 + 1;
        }

        if (qty20 != 0) {
            tarifJasa.value = tarifJasa.value * qty20;
        }
    }
}
    if (item.no == undefined) {
        for (var i = data2.value.length - 1; i >= 0; i--) {
            if (data2.value[i].rke == item.rke) {
                tarifJasa.value = 0
            }
        }
    }
    item.jumlahbulat = item.jumlah

    var ada = false;
    // console.log(item.tglKadaluarsa)
    for (var i = 0; i < dataProdukDetail.value.length; i++) {
        ada = false
        const element = dataProdukDetail.value[i]
        // console.log(element)
        let tglExpiredPilih

        if (item.tglKadaluarsa != undefined) {
            tglExpiredPilih = moment(item.tglKadaluarsa.label).format('YYYY-MM-DD HH:mm:ss')
        }
        if ((parseFloat(item.jumlah) * parseFloat(item.nilaiKonversi)) <= parseFloat(element.qtyproduk) && tglExpiredPilih == element.tglkadaluarsa) {
            isDisabled.value = false
            // hrg1.value = Math.round(parseFloat(element.hargajual) * parseFloat(item.nilaiKonversi))
            if(item.kebangsaan.value.id == 2 || item.kebangsaan.value.id == 3){
                hrg1.value = Math.round(parseFloat(element.hargajual) * parseFloat(item.nilaiKonversi) * 1.5)
            }else{
                hrg1.value = Math.round(parseFloat(element.hargajual) * parseFloat(item.nilaiKonversi))
            }
            hrgsdk.value = parseFloat(element.hargadiscount) * parseFloat(item.nilaiKonversi)
            item.hargaSatuan = hrg1.value
            item.hargaNetto = Math.round(parseFloat(element.harganetto) * parseFloat(item.nilaiKonversi))
            if (item.hargadiskon == 0) {
                item.hargadiskon = hrgsdk.value
            } else {
                hrgsdk.value = item.hargadiskon
            }

            if (item.jenisKemasan.jeniskemasan != 'Racikan') {
                item.total = (parseFloat(item.jumlahbulat) * (hrg1.value - hrgsdk.value))
            } else {
                item.total = (parseFloat(item.jumlahbulat) * (hrg1.value - hrgsdk.value)) + parseFloat(tarifJasa.value)
            }

            norecTerima.value = element.norec
            norecSPD.value = element.norec_spd

            item.asal = { id: element.objectasalprodukfk, asalproduk: element.asalproduk }
            // ada = true;
            break;
        }else{
            isDisabled.value = true
        }
    }
    // if (ada == false) {
    //     item.hargaSatuan = 0
    //     item.hargadiskon = 0
    //     item.hargaNetto = 0
    //     item.total = 0

    //     norecSPD.value = ''
    //     norecTerima.value = ''
    //     if (dataProdukDetail.value.length > 1) {

    //         var objSave =
    //         {
    //             produkfk: item.produk.id,
    //             ruanganfk: item.ruangan.value.id
    //         }

    //         isSimpan.value = true;
    //         useApi().post(
    //             '/farmasi/save-stock-merger', objSave).then(function (response: any) {
    //                 // clearInput()
    //                 GETKONVERSI()
    //                 isSimpan.value = false
    //             })


    //     }
    // }
    if (item.jumlah == 0) {
        item.hargaSatuan = 0
        item.hargaNetto = 0
    }
}
const jumlahkan = () => {
    if (item.stok > 0) {
        item.jumlah = Math.ceil((parseFloat(item.jumlahxmakan) * parseFloat(item.dosis)) / parseFloat(item.kekuatan))
        item.jumlahbulat = item.jumlah
    }
}
const changeSatuan = (e: any) => {
    delete item.total
    item.nilaiKonversi = item.satuan.value.nilaikonversi
}

const back = () => {
    window.history.back()
}

const addListAturanPakai = (bool, data) => {

    let jml = 0
    if (bool == true) {
        if (data.id == 1) {
            item.chkp = 1
        }
        if (data.id == 2) {
            item.chks = 1
        }
        if (data.id == 3) {
            item.chksr = 1
        }
        if (data.id == 4) {
            item.chkm = 1
        }
    } else {
        if (data.id == 1) {
            item.chkp = 0
        }
        if (data.id == 2) {
            item.chks = 0
        }
        if (data.id == 3) {
            item.chksr = 0
        }
        if (data.id == 4) {
            item.chkm = 0
        }
    }
    if (item.chkp == 1) {
        jml = jml + 1
    }
    if (item.chks == 1) {
        jml = jml + 1
    }
    if (item.chksr == 1) {
        jml = jml + 1
    }
    if (item.chkm == 1) {
        jml = jml + 1
    }
    // item.aturanpakai = jml + 'x1'
    // if (jml == 0) {
    //     item.aturanpakai = ''
    // }
}


watch(
    () => item.jenisKemasan,
    (newValue, oldValue) => {
        if (newValue.jeniskemasan == 'Racikan') {
            showRacikanDose.value = true
        } else {
            showRacikanDose.value = false
        }
    }
)

watch(
    () => item.jumlah,
    (newValue, oldValue) => {
        if (newValue != oldValue) {
            if(item.tglKadaluarsa != null){
                setNorecSPD()
            }
        }
    }
)
watch(
    () => item.jumlahxmakan,
    (newValue, oldValue) => {
        if (newValue != oldValue) {
            jumlahkan()
        }
    }
)
watch(
    () => item.dosis,
    (newValue, oldValue) => {
        if (newValue != oldValue) {
            jumlahkan()
        }
    }
)
watch(
    () => item.hargadiskon,
    (newValue, oldValue) => {
        if (newValue != oldValue) {
            hrgsdk.value = item.hargadiskon
            item.total = (parseFloat(item.jumlahbulat) * (hrg1.value - hrgsdk.value)) + parseFloat(tarifJasa.value)
            if (isNaN(item.total)) {
                item.total = 0
            }
        }
    }
)
watch(
    () => item.nilaiKonversi,
    (newValue, oldValue) => {
        if (newValue != oldValue) {
            if (item.stok > 0) {
                item.stok = parseFloat(item.stok) * (parseFloat(oldValue) / parseFloat(newValue))
                item.jumlah = 0
                item.jumlahbulat = 0;
                item.hargaSatuan = 0
                item.hargadiskon = 0
                item.hargaNetto = 0
                item.total = 0
            }
        }
    }
)

watch(() => item.rke, (newValue, oldValue) => {
    if (newValue != oldValue) {
        if (tarifJasa == 0) {
            for (var i = data2.value.length - 1; i >= 0; i--) {
                tarifJasa.value = parseFloat(item.tarifadminresep)
                if (data2.value[i].rke == item.rke) {
                    tarifJasa.value = 0
                    break;
                }
            }
        }
    }
})

watch(() => [
    item.jenisKemasan,
], (newValue, oldValue) => {
    if (item.no) {
        item.rke = item.rke
    } else {
        dataSource.value.forEach((element: any, i: any) => {
            if (dataSource.value.length - 1 == i) {
                if (item.jenisKemasan.id == 1 && element.jeniskemasanfk == 1) {
                    item.rke = element.rke
                    return
                } if (item.jenisKemasan.id == 2 && element.jeniskemasanfk == 1) {
                    item.rke = element.rke + 1
                    return
                }
            }
        });
    }
})

loadDrop()


</script>

<style lang="scss">
@import '/@src/scss/abstracts/all';
@import '/@src/scss/components/forms-outer';
// @import '/@src/scss/custom/config';
</style>
