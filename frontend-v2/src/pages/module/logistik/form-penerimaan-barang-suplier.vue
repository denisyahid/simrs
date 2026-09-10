<template>
  <ConfirmDialog />
  <div>
    <div class="form-layout is-stacked">
      <div class="form-outer" style="margin-top: 15px">
        <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
          <div class="form-header-inner">
            <div class="left">
              <h3>Penerimaan Barang</h3>
            </div>
            <div class="right">
              <div class="buttons">
                <VButton icon="lnir lnir-arrow-left rem-100" light dark-outlined RouterLink
                  :to="{ name: 'module-dashboard-logistik' }">
                  Kembali
                </VButton>
                <div>
                  <VButton v-if="nostruk != null" type="button" rounded outlined color="primary" raised
                    icon="feather:save" @click="saveDataPenerimaan(item)" :loading="isLoadBtnSave">
                    update
                  </VButton>
                  <VButton v-else type="button" :loading="isLoadBtnSave" rounded outlined color="primary" raised
                    icon="feather:save" @click="saveDataPenerimaan(item)" :disabled="isDisabled">
                    Simpan
                  </VButton>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="form-body p-4">
          <VTabs slider selected="penerimaan" :tabs="[
            { label: 'Penerimaan', value: 'penerimaan' },
            { label: 'Faktur', value: 'faktur' },
            { label: 'Data PO', value: 'po' },
          ]">
            <template #tab="{ activeValue }">
              <p v-if="activeValue === 'penerimaan'">
              <div style="margin-top:2.8rem" v-if="penerimaanSuplier.loading">
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
                  <div class="column is-4">
                    <VDatePicker color="green" style="display:none" trim-weeks>
                      <template #default="{ inputValue, inputEvents }">
                        <VField>
                          <VLabel class="required-field">Tanggal Terima</VLabel>
                          <VControl icon="feather:calendar">
                            <VInput type="text" placeholder="Select a date" class="is-radiusless" :value="inputValue" />
                          </VControl>
                        </VField>
                      </template>
                    </VDatePicker>
                    <VDatePicker v-model="item.tglTerima" color="green" trim-weeks>
                      <template #default="{ inputValue, inputEvents }">
                        <VField>
                          <VLabel class="required-field">Tanggal Terima</VLabel>
                          <VControl icon="feather:calendar">
                            <VInput type="text" placeholder="Select a date" class="is-radiusless" :value="inputValue"
                              v-on="inputEvents" />
                          </VControl>
                        </VField>
                      </template>
                    </VDatePicker>
                  </div>
                  <div class="column is-4">
                    <VField class="is-radiusless-select is-autocomplete-select">
                      <VLabel class="required-field">Gudang</VLabel>
                      <VControl icon="feather:search" fullwidth class="prime-auto-select">
                        <Dropdown v-model="item.gudang" :options="d_Gudang" optionLabel="label" class="is-radiusless"
                          placeholder="Pilih Gudang" style="width: 100%;" :filter="true" />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-4">
                    <VField class="is-radiusless-select is-autocomplete-select">
                      <VLabel class="required-field">Pegawai Penerima</VLabel>
                      <VControl icon="feather:search" fullwidth class="prime-auto-select">
                        <Dropdown v-model="item.penerimaan" :options="d_Pegawai" optionLabel="label" class="is-radiusless"
                          placeholder="Pilih Pegawai Penerima" style="width: 100%;" :filter="true" />
                      </VControl>
                    </VField>
                  </div>
                </div>
                <div class="columns">
                  <div class="column is-4 pt-1">
                    <VField class="is-radiusless-select is-autocomplete-select">
                      <VLabel class="required-field">Kelompok Barang</VLabel>
                      <VControl icon="feather:search" fullwidth class="prime-auto-select">
                        <Dropdown v-model="item.kelompokbarang" :options="d_kelompokBarang" optionLabel="label"
                          @change="getProduk(item)" class="is-radiusless" placeholder="Pilih Kelompok Barang"
                          style="width: 100%;" :filter="true" />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-4 pt-1">
                    <VField class="is-radiusless-select is-autocomplete-select">
                      <VLabel class="required-field">Sumber Dana</VLabel>
                      <VControl icon="feather:search" fullwidth class="prime-auto-select">
                        <Dropdown v-model="item.sumberdana" :options="d_SumberDana" optionLabel="label" class="is-radiusless"
                          placeholder="Pilih Sumber Dana" style="width: 100%;" :filter="true" />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-6 pt-0" v-if="item.kaskecil === true">
                    <div class="columns">
                      <div class="column is-6">
                        <VField>
                          <VLabel class="required-field">No Bukti</VLabel>
                          <VControl :loading="loadNBK">
                            <input v-model="item.noBuktiKK" type="text" class="input is-radiusless"
                              placeholder="No Dokumen" />
                          </VControl>
                        </VField>
                      </div>
                      <div class="column is-6 p-0 ml-5" style="margin-top: 3rem;">
                        <VField>
                          <VControl raw subcontrol>
                            <Checkbox v-model="item.noBKK" :binary="true" @change="noBuktiKK(item.noBKK)" />
                            <label class="ml-2 ingredient1">Otomatis</label>
                          </VControl>
                        </VField>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="columns" v-if="item.kaskecil === true">
                  <div class="column pt-1 is-3">
                    <VDatePicker v-model="item.tanggalKK" color="green" trim-weeks>
                      <template #default="{ inputValue, inputEvents }">
                        <VField>
                          <VLabel class="required-field">Tanggal SPK</VLabel>
                          <VControl icon="feather:calendar">
                            <VInput type="text" placeholder="Select a date" class="is-radiusless" :value="inputValue"
                              v-on="inputEvents" />
                          </VControl>
                        </VField>
                      </template>
                    </VDatePicker>
                  </div>
                  <div class="column is-4 pt-1">
                    <VField class="is-radiusless-select is-autocomplete-select">
                      <VLabel class="required-field">Ruangan</VLabel>
                      <VControl icon="feather:search" fullwidth class="prime-auto-select">
                        <Dropdown v-model="item.ruanganKK" :options="d_Gudang" optionLabel="label" class="is-radiusless"
                          placeholder="Pilih data" style="width: 100%;" :filter="true" />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-4 pt-1">
                    <VField class="is-radiusless-select is-autocomplete-select">
                      <VLabel class="required-field">Pegawai</VLabel>
                      <VControl icon="feather:search" fullwidth class="prime-auto-select">
                        <Dropdown v-model="item.pegawaiKK" :options="d_komit" optionLabel="label" class="is-radiusless"
                          placeholder="Pilih data" style="width: 100%;" :filter="true" />
                      </VControl>
                    </VField>
                  </div>
                </div>
              </div>

              </p>
              <p v-else-if="activeValue === 'faktur'">
              <div style="margin-top:2.8rem" v-if="penerimaanSuplier.loading">
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
                <div class="columns">
                  <div class="column is-2">
                    <VDatePicker style="display:none" color="green" trim-weeks>
                      <template #default="{ inputValue, inputEvents }">
                        <VField>
                          <VLabel class="required-field">Tanggal Faktur</VLabel>
                          <VControl icon="feather:calendar">
                            <VInput type="text" placeholder="Select a date" class="is-radiusless" :value="inputValue"
                              v-on="inputEvents" />
                          </VControl>
                        </VField>
                      </template>
                    </VDatePicker>
                    <VDatePicker v-model="item.tglFaktur" color="green" trim-weeks>
                      <template #default="{ inputValue, inputEvents }">
                        <VField>
                          <VLabel class="required-field">Tanggal Faktur</VLabel>
                          <VControl icon="feather:calendar">
                            <VInput type="text" placeholder="Select a date" class="is-radiusless" :value="inputValue"
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
                        <input v-model="item.noFaktur" type="text" class="input is-radiusless" placeholder="No Dokumen" />
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
                  <div class="column p-0" style="margin-top: 2rem;">
                    <VField>
                      <VControl raw subcontrol>
                        <VCheckbox v-model="item.ispinjam" label="Produk Pinjaman"
                          color="info" square />
                      </VControl>
                    </VField>
                  </div>  
                </div>
                <div class="columns">
                  <div class="column is-2">
                    <VDatePicker v-model="item.tglTempo" color="green" trim-weeks>
                      <template #default="{ inputValue, inputEvents }">
                        <VField>
                          <VLabel>Tanggal Jatuh Tempo</VLabel>
                          <VControl icon="feather:calendar">
                            <VInput type="text" placeholder="Select a date" class="is-radiusless" :value="inputValue"
                              v-on="inputEvents" />
                          </VControl>
                        </VField>
                      </template>
                    </VDatePicker>
                  </div>
                  
               <div class="column is-5">
                    <VField class="is-radiusless-select is-autocomplete-select">
                      <VLabel class="required-field">Nama Distributor</VLabel>
                      <VControl icon="feather:search" fullwidth class="prime-auto-select">
                        <Dropdown v-model="item.supplier" :options="d_suplier" optionLabel="label" class="is-radiusless"
                          placeholder="Pilih data" style="width: 100%;" :filter="true" />
                      </VControl>
                    </VField>
                </div>
                  <!-- <div class="column is-2">
                    <VField class="is-radiusless-select is-autocomplete-select">
                      <VLabel>Upload Faktur</VLabel>
                      <VControl fullwidth class="prime-auto-select">
                        <VButton rounded icon="feather:file" raised bold @click="add()" color="success" outlined
                          :loading="isLoading" class="mr-2">Upload Faktur </VButton>
                      </VControl>
                    </VField>
                  </div> -->
                  <div class="column is-3" v-if="item.ispinjam">
                    <VField class="is-radiusless-select is-autocomplete-select">
                      <VLabel>Nama Peminjam</VLabel>
                      <VControl icon="feather:search" fullwidth class="prime-auto-select">
                        <Dropdown v-model="item.pinjam" :options="d_supp_pinjam" optionLabel="label" class="is-radiusless"
                          placeholder="Pilih data" style="width: 100%;" :filter="true" />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-2">
                    <VField class="is-radiusless-select is-autocomplete-select">
                      <VLabel class="required-field">Nama Pabrik</VLabel>
                      <VControl icon="feather:search" fullwidth class="prime-auto-select">
                        <Dropdown v-model="item.pabrik" :options="d_pabrik" optionLabel="label" class="is-radiusless"
                          placeholder="Pilih data" style="width: 100%;" :filter="true" />
                      </VControl>
                    </VField>
                  </div>
                </div>
              </div>
              </p>

              <p v-else-if="activeValue === 'po'">
              <div style="margin-top:2.8rem" v-if="penerimaanSuplier.loading">
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
                <div class="columns">
                  <div class="column is-3">
                    <VDatePicker v-model="item.tanggalPo" style="display:none" color="green" trim-weeks
                      :max-date="new Date()">
                      <template #default="{ inputValue, inputEvents }">
                        <VField>
                          <VLabel>Tanggal</VLabel>
                          <VControl icon="feather:calendar">
                            <VInput type="text" placeholder="Select a date" class="is-radiusless" :value="inputValue"
                              v-on="inputEvents" />
                          </VControl>
                        </VField>
                      </template>
                    </VDatePicker>
                    <VDatePicker v-model="item.tanggalPo" color="green" trim-weeks :max-date="new Date()">
                      <template #default="{ inputValue, inputEvents }">
                        <VField>
                          <VLabel>Tanggal</VLabel>
                          <VControl icon="feather:calendar">
                            <VInput type="text" placeholder="Select a date" class="is-radiusless" :value="inputValue"
                              v-on="inputEvents" />
                          </VControl>
                        </VField>
                      </template>
                    </VDatePicker>
                  </div>
                  <div class="column is-3">
                    <VField>
                      <VLabel>No Nota Dinas</VLabel>
                      <VControl>
                        <input v-model="item.nousulan" type="text" class="input is-radiusless" placeholder="No Nota Dinas..." />
                      </VControl>
                    </VField>
                  </div>
                <!-- </div> -->
                <!-- <div class="columns"> -->
                  <div class="column is-3">
                    <VField>
                      <VLabel>Metode Pengadaan</VLabel>
                      <!-- <VControl>
                        <input v-model="item.namapengadaan" type="text" class="input is-radiusless"
                          placeholder="Metode Pengadaan..." />
                      </VControl> -->
                      <VControl class="prime-auto">
                        <AutoComplete v-model="item.namapengadaan"
                            :suggestions="d_Status" :optionLabel="'label'"
                            @complete="fetchStatus($event)"  :dropdown="true"
                            :minLength="3" :appendTo="'body'"
                            :loadingIcon="'pi pi-spinner'" :field="'label'"
                            placeholder="Pilih Metode Pengadaan"/>
                    </VControl>
                    </VField>
                  </div>
                  <div class="column is-3">
                    <VField>
                      <VLabel>No ID Paket</VLabel>
                      <VControl>
                        <input v-model="item.nokontrak" type="text" class="input is-radiusless" placeholder="No ID Paket" />
                      </VControl>
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
            <VButton type="button" icon="feather:x-circle" @click="modalTambah(item)"
              class="is-fullwidth is-outlined is-primary mt-4" rounded raised>
              Tambah
            </VButton>
          </div>
          <DataTable :value="penerimaanSuplier" :paginator="true" :rows="5" :rowsPerPageOptions="[5, 10, 25]"
            :loading="penerimaanSuplier.loading" class="p-datatable-sm" tableStyle="min-width: 10rem"
            paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
            sortMode="multiple" currentPageReportTemplate="Showing {first} to {last} of {totalRecords}" showGridlines>
            <Column field="no" header="No" style="min-width: 10px;" frozen></Column>
            <Column field="kdproduk" header="Kode Produk" style="min-width: 300px;" frozen class="font-bold"></Column>
            <Column field="namaproduk" header="Nama Produk" style="min-width: 300px;" frozen class="font-bold"></Column>
            <Column field="satuan" header="Satuan" style="min-width: 80px;"></Column>
            <Column field="jumlah" header="Qty" style="min-width: 50px;" />
            <Column field="hargasatuan" header="Harga Satuan" style="min-width: 150px;text-align:right">
              <template #body="slotProps">
                {{ H.formatRupiah(H.roundToDecimal(parseFloat(slotProps.data.hargasatuan), 2), '') }}
              </template>
            </Column>
            <Column field="subtotal" header="Sub Total" style="min-width: 150px;text-align:right">
              <template #body="slotProps">
                {{ H.formatRupiah(H.roundToDecimal(parseFloat(slotProps.data.subtotal), 2), '') }}
              </template>
            </Column>
            <Column field="hargadiskon" header="Harga Diskon" style="min-width: 130px;text-align:right">
              <template #body="slotProps">
                {{ H.formatRupiah(H.roundToDecimal(parseFloat(slotProps.data.hargadiskon), 2), '') }}
              </template>
            </Column>
            <Column field="nilaippn" header="PPN" style="text-align:right">
              <template #body="slotProps">
                {{ H.formatRupiah(H.roundToDecimal(parseFloat(slotProps.data.nilaippn), 2), '') }}
              </template>
            </Column>
            <Column field="totalall" header="Total" style="min-width: 200px;text-align:right">
              <template #body="slotProps">
                {{ H.formatRupiah(H.roundToDecimal(parseFloat(slotProps.data.totalall), 2), '') }}
              </template>
            </Column>
            <Column field="nobatch"  header="No Batch" style="min-width: 80px;" ></Column>
            <Column field="tglkadaluarsa" header="TGL Kadaluarsa" style="min-width: 150px;"></Column>
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
          <div class="column is-3">
    <VCardCustom :style="'padding:5px 25px'">
      <div class="label-status" color="danger">
        <i aria-hidden="true" class="fas fa-circle"></i>
        <span class="ml-1">PEMBULATAN</span>
      </div>
      <div class="input-group">
        <input 
          type="number" 
          class="input" 
          v-model.number="item.pembulatan" 
          placeholder="Masukkan pembulatan"
        />
        <button 
          class="button is-danger ml-2" 
          @click="() => calculateTotalPembulatan('add')"
        >
          Tambah
        </button>
        <button 
          class="button is-danger ml-2" 
          @click="() => calculateTotalPembulatan('subtract')"
        >
          Kurang
        </button>
      </div>
    </VCardCustom>
  </div>

  <div class="column is-3">
    <VCardCustom :style="'padding:5px 25px'">
      <div class="label-status" color="danger">
        <i aria-hidden="true" class="fas fa-circle"></i>
        <span class="ml-1">TOTAL PEMBULATAN</span>
      </div>
      <small class="text-bold-custom h-100">{{ H.formatRp(item.totalpembulatan, 'Rp.') }}</small>
    </VCardCustom>
  </div>
        </div>
      </div>

      <div class="content">
        <div class="is-divider" />
      </div>
    </div>
  </div>

  <Dialog v-model:visible="modalInput" modal header="Detail Penerimaan Barang"
    :style="{ width: '60vw', 'max-height': '80vh', 'overflow-y': 'auto' }">
    <form class="modal-form">
      <div class="column is-12">
        <div class="columns is-multiline">
          <div class="column is-5">
            <VField class="is-autocomplete-select" label="Produk">
              <VControl icon="feather:search">
                <AutoComplete v-model="item.produk" :suggestions="d_Produk" @complete="getProduk($event)"
                  :optionLabel="'namaproduk'" :dropdown="true" :minLength="3" :appendTo="'body'"
                  @item-select="getSatuan(item.produk)" :loadingIcon="'pi pi-spinner'" :field="'namaproduk'"
                  placeholder="Cari Nama Obat" >

                  <template #option="slotProps">
                    <div class="columns is-multiline">
                        <div class="column is-12">
                            <span style="font-weight:bold">{{ slotProps.option.name }}</span>
                        </div>
                        <div class="column is-12 mt-5-min">
                            <table style="width:50%">
                                <tr>
                                    <td><b>[{{ slotProps.option.kdproduk }}] - {{ slotProps.option.namaproduk }}</b></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </template>

                  </AutoComplete>
              </VControl>
            </VField>
          </div>
          <div class="column is-3">
            <VField class="is-radiusless-select is-autocomplete-select">
              <VLabel>Kode Produk</VLabel>
              <VControl fullwidth class="prime-auto-select" style="font-weight:bold">
                <Dropdown v-model="item.produk" :options="d_Produk" optionLabel="kdproduk" @complete="getProduk($event)" disabled @item-select="getSatuan(item.kdproduk)"
                :field="'kdproduk'" class="is-radiusless" placeholder="Kode Produk" style="width: 100%;" :filter="true" />
              </VControl>
            </VField>
          </div>
         
          <div class="column is-3">
            <VField class="is-radiusless-select is-autocomplete-select">
              <VLabel class="required-field">Satuan</VLabel>
              <VControl icon="feather:search" fullwidth class="prime-auto-select">
                <Dropdown v-model="item.satuan" :options="d_Satuan" optionLabel="label" @change="getKonversi(item.satuan)"
                  class="is-radiusless" placeholder="Pilih data" style="width: 100%;" :filter="true" />
              </VControl>
            </VField>
          </div>
          <div class="column is-2">
            <VField>
              <VLabel class="required-field">Konversi</VLabel>
              <VControl>
                <input v-model="item.konversi" type="number" class="input is-radiusless" placeholder="Konversi" />
              </VControl>
            </VField>
          </div>
          <div class="column is-2">
            <VField>
              <VLabel class="required-field">Jumlah</VLabel>
              <VControl>
                <input v-model="item.jumlah" type="number" class="input is-radiusless" placeholder="QTY" />
              </VControl>
            </VField>
          </div>
          <div class="column is-4">
            <VField>
              <VLabel class="required-field">Harga Satuan</VLabel>
              <VControl :loading="isLoadPrice">
                <input v-model="item.hargasatuan" type="number" class="input is-radiusless" placeholder="Harga Satuan"
                  :loading="isLoadPrice" />
              </VControl>
            </VField>
          </div>
          <div class="column is-3">
            <VDatePicker v-model="item.tglkadaluarsa" color="green">
              <template #default="{ inputValue, inputEvents }">
                <VField>
                  <VLabel class="required-field">Tanggal Kadaluarsa</VLabel>
                  <VControl icon="feather:calendar">
                    <VInput type="text" placeholder="Select a date" class="is-radiusless" :value="inputValue"
                      v-on="inputEvents" />
                  </VControl>
                </VField>
              </template>
            </VDatePicker>
          </div>
        </div>
      </div>
      <div class="column">
        <div class="columns is-multiline">

          <div class="column is-3">
            <VField label="Jumlah Diskon(%)">
              <VControl>
                <input v-model="item.persendiscount" type="number" class="input is-radiusless" placeholder="Jumlah Diskon" />
              </VControl>
            </VField>
          </div>
          <div class="column is-4">
      <VField label="Harga Diskon">
        <VControl>
          <input
            v-model="item.hargadiskon"
            type="text"
            class="input is-radiusless"
            placeholder="Harga Diskon"
          />
        </VControl>
        <button
          class="button is-primary mt-2"
          @click="calculateDiscountPercentage"
        >
          Hitung Diskon (%)
        </button>
      </VField>
    </div>
          <div class="column is-2">
            <VField label="PPN(%)">
              <VControl>
                <input v-model="item.persenppn" type="number" class="input is-radiusless" placeholder="PPN(%)" />
              </VControl>
            </VField>
          </div>
          <div class="column is-3">
            <VField label="PPN">
              <VControl>
                <input v-model="item.nilaippn" disabled type="text" class="input is-radiusless" placeholder="PPN" />
              </VControl>
            </VField>
          </div>
          <div class="column is-3">
            <VField>
              <VLabel class="required-field">No Batch</VLabel>
              <VControl>
                <input v-model="item.nobatch" type="text" class="input is-radiusless" placeholder="No Batch" />
              </VControl>
            </VField>
          </div>
          <div class="column is-4">
            <VField label="Keterangan">
              <VControl>
                <input v-model="item.keterangan" type="text" class="input is-radiusless" placeholder="Keterangan" />
              </VControl>
            </VField>
          </div>
          <div class="column is-4">
            <VField label="Sub Total">
              <VControl>
                <input v-model="item.subtotal" type="float" class="input is-radiusless" placeholder="Sub Total" />
              </VControl>
            </VField>
          </div>
        </div>
      </div>

      <div class="column is-12">
        <div class="content">
          <div class="is-divider" data-content="Penerimaan Barang" />
        </div>
      </div>

      <DataTable :value="penerimaanSuplier" :paginator="true" :rows="5" :rowsPerPageOptions="[5, 10, 25]"
        :loading="penerimaanSuplier.loading" class="p-datatable-sm" tableStyle="min-width: 10rem"
        paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
        sortMode="multiple" currentPageReportTemplate="Showing {first} to {last} of {totalRecords}" showGridlines>
        <Column field="no" header="No" style="min-width: 10px;" frozen></Column>
        <Column field="kdproduk" header="Kode Produk" style="min-width: 300px;" frozen class="font-bold"></Column>
        <Column field="namaproduk" header="Nama Produk" style="min-width: 300px;" frozen class="font-bold"></Column>
        <Column field="satuan" header="Satuan" style="min-width: 80px;"></Column>
        <Column field="jumlah" header="Qty" style="min-width: 50px;"></Column>
        <Column field="hargasatuan" header="Harga Satuan" style="min-width: 150px;"></Column>
        <Column field="subtotal" header="Sub Total" style="min-width: 150px;"></Column>
        <Column field="hargadiskon" header="Harga Diskon" style="min-width: 130px;"></Column>
        <Column field="nilaippn" header="PPN"></Column>
        <Column field="totalall" header="Total" style="min-width: 200px;">

        </Column>
        <Column field="nobatch" header="No Batch" style="min-width: 80px;"></Column>
        <Column field="tglkadaluarsa" header="TGL Kadaluarsa" style="min-width: 150px;"></Column>
        <Column :exportable="false" header="Action" style="text-align: center;min-width: 100px;">
          <template #body="slotProps">
            <VIconButton type="button" icon="feather:edit" class="mr-3" color="info" circle outlined raised
              :loading="isLoadingBtn" v-tooltip.top="'Edit'" @click="editDataSupplier(slotProps.data)">
            </VIconButton>
            <VIconButton type="button" icon="fas fa-trash" color="danger" circle outlined raised v-tooltip.top="'Hapus'"
              @click="dialogConfirm(slotProps.data)">
            </VIconButton>
          </template>
        </Column>
      </DataTable>

    </form>

    <template #footer>
      <VButton raised class="mr-3" @click="clear()">Batal</VButton>
      <VButton v-if="item.no" icon="feather:plus" @click="updateDataSupplier(item)" color="primary" raised>Update
      </VButton>
      <VButton v-else icon="feather:plus" @click="addDataSupplier(item)" color="primary" raised>Simpan
      </VButton>
    </template>
  </Dialog>
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
let d_Status: any = ref([])
const route = useRoute()

const fetchStatus = async (filter) => {
  const StatusOptions = [
  { label: "e-Purchasing", value: "e-Purchasing" },
  { label: "Reguler", value: "Reguler" },
  { label: "Hibah", value: "Hibah" },
  { label: "Produksi", value: "Produksi" },
  { label: "Pinjaman", value: "Pinjaman" },
];
  d_Status.value = StatusOptions;
};

const item: any = ref({
  kelompokbarang: 24,
  kaskecil: false,
  tglTerima: new Date(),
  tglFaktur: new Date(),
  tanggalPo: new Date(),
  totalall: 0,
  pembulatan: 0,
  totalpembulatan: 0,
  persenppn: '',
  hargasatuan: '',
  persendiscount: '',
  jumlah: '',
  noBKK: '',
  sumberdana: null,
  subtotal: '',
  hargadiskon: '',
  nilaippn: '',
})


const d_Pegawai = ref([])
const d_SumberDana = ref([])
const d_kelompokBarang = ref([])
// const d_Berkas: any = ref([])
// const fileFaktur: any = ref()
// const input: any = ref({})
// const modalInputFile = ref(false)
const d_koordinator = ref([])
const d_komit = ref([])
const d_Gudang = ref([])
const d_suplier = ref([])
const d_pabrik = ref([])
const d_supp_pinjam = ref([])
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
let penerimaanSuplier: any = ref([])
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

const saveDataPenerimaan = async (e: any) => {

  if (!item.value.tglTerima) {
    useToaster().error('Tanggal Terima Tidak Boleh Kosong')
    return
  }
  if (!item.value.gudang) {
    useToaster().error('Gudang Tidak Boleh Kosong')
    return
  }
  if (!item.value.penerimaan) {
    useToaster().error('Pegawai Penerima Tidak Boleh Kosong')
    return
  }
  if (!item.value.kelompokbarang) {
    useToaster().error('kelompok Barang Tidak Boleh Kosong')
    return
  }
  if (!item.value.sumberdana) {
    useToaster().error('Sumber Dana Tidak Boleh Kosong')
    return
  }

  if (item.value.sumberdana == 7) {
    if (!item.value.noBuktiKK) {
      useToaster().error('No Bukti Tidak Boleh Kosong')
      return
    }
    if (!item.value.tanggalKK) {
      useToaster().error('Tanggal Kas Kecil Tidak Boleh Kosong')
      return
    }
    if (!item.value.ruanganKK) {
      useToaster().error('Ruangan Kas Kecil Tidak Boleh Kosong')
      return
    }
    if (!item.value.pegawaiKK) {
      useToaster().error('pegawai Kas Kecil Tidak Boleh Kosong')
      return
    }
  }

  if (!item.value.tglFaktur) {
    useToaster().error('Tanggal Faktur Tidak Boleh Kosong')
    return
  }
  if (!item.value.noFaktur) {
    useToaster().error('No Faktur Tidak Boleh Kosong')
    return
  }
  if (!item.value.supplier) {
    useToaster().error('Supplier Tidak Boleh Kosong')
    return
  }

  if (penerimaanSuplier.value.length == 0) {
    useToaster().error('Data Yang Disimpan Tidak Tersedia')
    return
  }

  const norecOrder = route.query.norecOrder
  isLoadBtnSave.value = true
  const objSave = {
    details: penerimaanSuplier.value,
    struk: {
      nosppb: '',
      nostruk: nostruk?.value || '',
      norec: route?.query?.norec || '',
      norecsppb: '',
      norecrealisasi: item.value.norecrealisasi || '',
      objectmataanggaranfk: '',
      norecOrder: norecOrder || '',
      jenissusulan: 'Medis',
      jenissusulanfk: 2,
      noorder: item.value.nosppb || '-',
      noBuktiKK: item.value.noBuktiKK || '',
      pegawaiKK: item.value.pegawaiKK?.value?.id || null,
      ruanganfkKK: item.value.ruanganKK?.value || '',
      ruanganfk: item.value.gudang?.value || '',
      asalproduk: item.value.sumberdana?.value || '',
      pegawaikomit: item.value.pembuatkomit?.namalengkap || '',
      namapegawaipenerima: item.value.penerimaan?.value?.namalengkap || '',
      namarekanan: item.value.supplier?.label || '',
      namapengadaan: item?.value?.namapengadaan?.value ?? '',
      ketTerima: item.value.ketTerima || '',
      nokontrak: item.value.nokontrak || '',
      pegawaimenerimafk: item.value.penerimaan?.value?.id || null,
      pegawaikomitfk: item.value.pembuatkomit?.id || null,
      rekananfk: item.value.supplier?.value?.id || null,
      nofaktur: item.value.noFaktur,
      kelompokprodukfk: item.value.kelompokbarang?.value || null,
      nousulan: item.value.nousulan || '',
      qtyproduk: penerimaanSuplier.value.length,
      tglSppb: null,
      tglKK: item.value.tanggalKK ? H.formatDate(item.value.tanggalKK, 'YYYY-MM-DDTHH:mm:ss') : null,
      tglTempo: item.value.tglTempo ? H.formatDate(item.value.tglTempo, 'YYYY-MM-DDTHH:mm:ss') : null,
      tglfaktur: H.formatDate(item.value.tglFaktur, 'YYYY-MM-DDTHH:mm:ss'),
      tglstruk: H.formatDate(item.value.tglTerima, 'YYYY-MM-DDTHH:mm:ss'),
      tglorder: item.value.tanggalPo ? H.formatDate(item.value.tanggalPo, 'YYYY-MM-DDTHH:mm:ss') : null,
      tglkontrak: H.formatDate(item.value.tanggalPo, 'YYYY-MM-DDTHH:mm:ss'),
      tglrealisasi: H.formatDate(item.value.tglFaktur, 'YYYY-MM-DDTHH:mm:ss'),
      totaldiscount: item.value.discount || 0,
      totalhargasatuan: item.value.totalsub,
      totalharusdibayar: item.value.totalall,
      totalpembulatan: item.value.totalpembulatan,
      totalppn: item.value.ppnTotal || 0,
      ispinjam: item.value.ispinjam || null,
      rekananpinjamfk: item.value.pinjam?.value?.id || null,
      pabrikfk: item.value.pabrik?.value?.id || null,
    },
  };
  await useApi()
    .post('/logistik/penerimaan-barang/save-penerimaan-suplier', objSave)
    .then((response) => {
      isLoadBtnSave.value = false
      isDisabled.value = true
    })
    .catch((err) => {
      isLoadBtnSave.value = false
    })
}

const modalTambah = (e: any) => {
  if (!item.value.sumberdana) {
    H.alert('error', 'Sumber Dana Tidak Boleh Kosong')
    return
  }
  if (item.value.sumberdana.value == 1 && !item.value.namapengadaan) {
    H.alert('error', 'Metode Pengadaan Wajib Diisi')
    return
  }
  modalInput.value = true
}

const addDataSupplier = (e: any) => {
  if (!e.satuan) {
    useToaster().error('Satuan Produk Tidak Boleh Kosong')
    return
  }
  if (!e.jumlah) {
    useToaster().error('Jumlah Tidak Boleh Kosong')
    return
  }
  if (!e.tglkadaluarsa) {
    useToaster().error('Tanggal Kadaluarsa Tidak Boleh Kosong')
    return
  }

  if (!e.nobatch) {
    useToaster().error('No Batch Tidak Boleh Kosong')
    return
  }

  let datas = {
    no: penerimaanSuplier.value.length == 0 ? 1 : penerimaanSuplier.value.length + 1,
    asalprodukfk: e.sumberdana.value,
    namaproduk: e.produk.namaproduk,
    kdproduk : e.produk.kdproduk,
    produk: e.produk,
    produkfk: e.produk.id,
    satuan: e.satuan.label,
    satuanstandarfk: e.satuan.value,
    listSatuan: e.satuan,
    ssid: e.satuan.value,
    sisa: e.jumlah,
    nilaikonversi: e.konversi,
    jumlah: e.jumlah,
    jumlahdipakai: 0,
    hargasatuan: e.hargasatuan,
    subtotal: parseFloat(e.hargasatuan) * parseFloat(e.jumlah),
    totalall: e.subtotal,
    persendiscount: e.persendiscount ? e.persendiscount : '0',
    hargadiskon: e.hargadiskon ? e.hargadiskon : '0',
    keterangan: e.keterangan ? e.keterangan : '',
    persenppn: e.persenppn ? e.persenppn : '0',
    nilaippn: e.nilaippn ? e.nilaippn : '0',
    nobatch: e.nobatch ? e.nobatch : '-',
    tglkadaluarsa: e.tglkadaluarsa
      ? H.formatDate(e.tglkadaluarsa, 'MM/DD/YYYY')
      : '',
  }
  penerimaanSuplier.value.push(datas)
  if (penerimaanSuplier.value.length > 0) {
    clear()
  }
  count()
}

function calculateTotalPembulatan(operation) {
  if (operation === 'add') {
    item.value.totalpembulatan = item.value.totalall + item.value.pembulatan;
  } else if (operation === 'subtract') {
    item.value.totalpembulatan = item.value.totalall - item.value.pembulatan;
  }
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
  penerimaanSuplier.value.forEach((element: any, i: any) => {
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
      penerimaanSuplier.value[i] = data
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
      for (var i = penerimaanSuplier.value.length - 1; i >= 0; i--) {
        if (penerimaanSuplier.value[i].no == e.no) {
          penerimaanSuplier.value.splice(i, 1)
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
    .get('logistik/penerimaan-barang/get-data-combo')
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
      d_kelompokBarang.value = response.kelompokbarang.map((e: any) => {
        return { label: e.kelompokproduk, value: e.id }
      })
      d_Gudang.value = response.ruangan.map((e: any) => {
        return { label: e.namaruangan, value: e.id }
      })
      d_suplier.value = response.suplier.map((e: any) => {
        return { label: e.namarekanan, value: e }
      })
      d_pabrik.value = response.pabrik.map((e: any) => {
        return { label: e.namarekanan, value: e }
      })
      d_supp_pinjam.value = response.pinjamsupp.map((e: any) => {
        return { label: e.namarekanan, value: e }
      })
      
    })
}

const isEditPenerimaan = async () => {
  const norec = route.query.norec
  if (norec) {
    penerimaanSuplier.value.loading = true
    await useApi().get(`/logistik/get-detail-penerimaan?norec=${norec}`).then((response) => {
      let dataPeneriman = response.detailterima
      let datapelayanan = response.pelayananPasien
      d_Pegawai.value.forEach((e: any) => {
        if (e.value.id == dataPeneriman.pgid) {
          item.value.penerimaan = e
          // return
        }
      });
      d_Gudang.value.forEach((e: any) => {
        if (e.value == dataPeneriman.objectruanganfk) {
          item.value.gudang = e
          // return
        }
      });
      d_kelompokBarang.value.forEach((e: any) => {
        if (e.value == dataPeneriman.objectkelompokprodukfk) {
          item.value.kelompokbarang = e
          // return
        }
      });
      d_SumberDana.value.forEach((e: any) => {
        if (e.value == dataPeneriman.asalprodukfk) {
          item.value.sumberdana = e
          // return
        }
      });
      d_suplier.value.forEach((e: any) => {
        if (e.value.id == dataPeneriman.objectrekananfk) {
          item.value.supplier = e
          // console.log('suppler',e)
          // return
        }
      });
      // console.log('supp', item.value.supplier);
      
      d_pabrik.value.forEach((e: any) => {
        if (e.value.id == dataPeneriman.pabrikfk) {
          item.value.pabrik = e
          // return
        
        }
      });
      // console.log('pbarik', item.value.pabrikfk);
      d_supp_pinjam.value.forEach((e: any) => {
        if (e.value.id == dataPeneriman.rekananpinjamfk) {
          item.value.pinjam = e
          // console.log('data macth',e)
          // return
        }
      });
      item.value.ispinjam = dataPeneriman.ispinjam
      item.value.totalpembulatan = dataPeneriman.totalpembulatan
      item.value.noBKK = dataPeneriman.nobukti ? true : false
      item.value.noBuktiKK = item.value.noBKK == true ? dataPeneriman.nobukti : ''
      item.value.tanggalKK = dataPeneriman.tglspk
      item.value.norecrealisasi = dataPeneriman.norecrealisasi
      nostruk.value = dataPeneriman.nostruk
      noBukti.value = dataPeneriman.nobukti
      item.value.tglTerima = H.formatDate(dataPeneriman.tglstruk, 'MM/DD/YYYY')
      item.value.tglFaktur = dataPeneriman.tglfaktur
      item.value.noFaktur = dataPeneriman.nofaktur
      item.value.tglTempo = dataPeneriman.tgljatuhtempo
      item.value.nousulan = dataPeneriman.nousulan
      item.value.tanggalPo = dataPeneriman.tgldokumen
      item.value.namapengadaan = dataPeneriman.namapengadaan
      item.value.nokontrak = dataPeneriman.nokontrak
      datapelayanan.forEach((data: any, i: any) => {
        data.no = i + 1
        item.value.produk = data.produkfk
      });
      penerimaanSuplier.value = datapelayanan
      count()
      // isLoadData.value = false
      penerimaanSuplier.value.loading = false
      if (dataPeneriman.asalprodukfk == 7) {
        d_komit.value.forEach((e: any) => {
          if (e.value.id == dataPeneriman.objectpegawaipenanggungjawabfk) {
            item.value.pegawaiKK = e
            return
          }
        })
        d_Gudang.value.forEach((e: any) => {
          if (e.value == dataPeneriman.objectruanganasalfk) {
            item.value.ruanganKK = e
            return
          }
        });
        return
      }
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

const isDetailUsulanPermintaan = async () => {
  const norecOrder = route.query.norecOrder
  if (norecOrder) {
    await useApi().get(`logistik/get-detail-data-po?norecOrder=${norecOrder}`).then((response) => {
      let header = response.detail
      let details = response.details
      item.value.kelompokbarang = { label: 'Barang Persediaan', value: 24 }
      item.value.gudang = { label: header.unitpengusul, value: header.idunitpengusul }
      d_Pegawai.value.forEach((element) => {
        if (element.value.id == header.petugasid) {
          item.value.penerimaan = element
        }
      })
      d_suplier.value.forEach((e: any) => {
        if (e.value.id == header.namarekananid) {
          item.value.supplier = e
          return
        }
      });
      // item.value.penerimaan = { label: header.petugas, value: header.petugasid }
      item.value.unitTujuan = { label: header.unittujuan, value: header.idunittujuan }
      item.value.nousulan = header.nousulan
      item.value.tanggalPo = header.tglorder
      item.value.tglTerima = header.tglrencana
      item.value.keterangan = header.keterangan
      item.value.norec = header.norecOrder
      item.value.norecrealisasi = header.norecrealisasi
      item.value.norecrrusulan = header.norecrrusulan
      penerimaanSuplier.value.loading = false

      // let datas:any = {}
      // details.forEach((element:any,i:any)=>{
      //   datas.produk = element.namaproduk
      //   datas.produkfk = element.kdproduk
      //   datas.jumlah = element.jumlah
      //   datas.jumlahdipakai = element.jumlahdipakai
      //   datas.hargasatuan = element.hargasatuan
      //   datas.ssid = element.satuanstandarfk
      //   datas.satuan = element.satuanstandar
      //   datas.satuanstandarfk = element.satuanstandarfk
      //   datas.satuanfk = element.satuanstandarfk
      //   datas.sisa = element.sisa
      //   datas.nilaikonversi = element.nilaikonversi
      //   datas.hargadiskon = element.hargadiscount
      //   datas.persendiscount = element.persendiscount
      //   datas.persenppn = element.persenppn
      //   datas.nilaippn = element.ppn
      //   datas.nobatch = element.nobatch
      //   datas.subtotal = parseFloat(element.jumlah) * parseFloat(element.hargasatuan)
      //   datas.totalall = element.subtotal
      // })
      penerimaanSuplier.value = details
      count()
    })
      .catch((e: any) => {
        console.log(e)
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
  // delete item.value.persenppn
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

const count = () => {
  let totalsub = 0
  let discount = 0
  let ppn = 0
  let total = 0
  penerimaanSuplier.value.forEach((element: any) => {
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

const getProduk = async (filter: any) => {
  await useApi().get(`/logistik/penerimaan-barang/get-produk-bykelompok?idkelompokproduk=${item.value.kelompokbarang.value}&namaproduk=${filter.query}`).then((response) => {
    d_Produk.value = response.produk
  })
}

watch(
  () => item.value.tglFaktur,
  (newDate) => {
    if (newDate) {
      // Tambahkan 60 hari ke tanggal faktur
      const fakturDate = new Date(newDate);
      fakturDate.setDate(fakturDate.getDate() + 60);
      item.value.tglTempo = fakturDate.toISOString().split('T')[0]; // Format ISO (YYYY-MM-DD)
    } else {
      item.value.tglTempo = null; // Reset jika tglFaktur kosong
    }
  }
);

const calculateDiscountPercentage = () => {
  if (
    item.value.hargadiskon &&
    item.value.jumlah &&
    item.value.hargasatuan
  ) {
    const totalHarga =
      parseFloat(item.value.jumlah) * parseFloat(item.value.hargasatuan);
    if (totalHarga > 0) {
      const calculatedDiskon =
        (parseFloat(item.value.hargadiskon) / totalHarga) * 100;
      // Simpan nilai persen diskon dengan 2 desimal
      item.value.persendiscount = parseFloat(calculatedDiskon.toFixed(2));
    } else {
      item.value.persendiscount = '';
    }
  }
};

const calculateDiscountPrice = () => {
  if (
    item.value.persendiscount &&
    item.value.jumlah &&
    item.value.hargasatuan
  ) {
    const diskon = parseFloat(item.value.persendiscount) / 100;
    const hargaDiskon =
      parseFloat(item.value.jumlah) *
      parseFloat(item.value.hargasatuan) *
      parseFloat(diskon);
    // Simpan nilai harga diskon dengan 2 desimal
    item.value.hargadiskon = parseFloat(hargaDiskon.toFixed(1));
  } else {
    item.value.hargadiskon = '';
  }
};

watch(
  () => [
    item.value.persenppn,
    item.value.hargasatuan,
    item.value.persendiscount,
    item.value.jumlah,
    item.value.noBKK,
    item.value.sumberdana,
  ],
  () => {
    // Kondisi sumberdana
    if (item.value.sumberdana) {
      item.value.kaskecil = item.value.sumberdana.value == 7;
    }

    // Kondisi jumlah
    if (item.value.jumlah === undefined || item.value.jumlah === '') {
      item.value.subtotal = '';
    } else {
      const diskon =
        item.value.persendiscount == ''
          ? delete item.value.persendiscount
          : item.value.persendiscount / 100;
      const nilaiDiskon =
        parseFloat(item.value.jumlah) *
        parseFloat(item.value.hargasatuan) *
        (diskon || 0);
      const resultHarga = nilaiDiskon
        ? parseFloat(item.value.jumlah) *
            parseFloat(item.value.hargasatuan) -
          parseFloat(nilaiDiskon)
        : parseFloat(item.value.jumlah) * parseFloat(item.value.hargasatuan);
      const ppn = item.value.persenppn / 100;
      const nilaippn = resultHarga * ppn;

      item.value.nilaippn = ppn ? nilaippn : '';
      item.value.subtotal = nilaippn
        ? parseFloat(resultHarga) + nilaippn
        : resultHarga;
    }
  }
);

// Watcher khusus untuk hargadiskon
// watch(
//   () => item.value.hargadiskon,
//   () => {
//     calculateDiscountPercentage(); // Perbarui persen diskon saat harga diskon diubah
//   }
// );

// Watcher khusus untuk persendiscount
watch(
  () => item.value.persendiscount,
  () => {
    calculateDiscountPrice(); // Perbarui harga diskon saat persen diskon diubah
  }
);

listData()
if (route.query.norec) {
  isEditPenerimaan()
} else {
  isDetailUsulanPermintaan()
}

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

