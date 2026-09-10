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
            <VControl class="prime-auto">
              <VField>
                 <Calendar :locale="'id'" :dateFormat="H.dateTimeFormat().prime.date" inputId="range" v-model="item.filterTgl" selectionMode="range" :manualInput="false"
                  class="w-100" :showIcon="true" :hideOnRangeSelection="true" />
              </VField>
            </VControl>
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
            <VField label="No Faktur">
              <VControl icon="feather:bookmark">
                <VInput type="text" v-model="item.nodokumen" placeholder="No Faktur" />
              </VControl>
            </VField>
          </div>
          

         

        </div>
      </div>
      <div class="column is-12">
        <div class="columns">
          <div class="column is-4">
            <VField label="Kode Produk">
              <VControl icon="feather:bookmark">
                <VInput type="text" v-model="item.kodeProduk" placeholder="Kode Produk" />
              </VControl>
            </VField>
          </div>
          <div class="column is-3">
            <VField label="Nama Produk">
              <VControl icon="feather:bookmark">
                <VInput type="text" v-model="item.namaProduk" placeholder="Nama Produk" />
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
            <label for="">List Stok Penerimaan Dari Supplier</label>
          </div>
          <div class="column pr-0">
            <VButton type="button" icon="feather:x-circle" RouterLink
              :to="{ name: 'module-logistik-form-penerimaan-barang-suplier' }"
              class="is-fullwidth is-outlined is-primary mt-4" rounded raised>
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
                <div class="column p-0 m-0" style="display: flex;justify-content: center;">
                  <img class="light-image" src="/@src/assets/illustrations/placeholders/search-4.svg" alt=""
                    style="max-width: 26%;margin-top: 1rem;" />
                  <img class="dark-image" src="/@src/assets/illustrations/placeholders/search-4-dark.svg" alt=""
                    style="max-width: 26%;margin-top: 1rem;" />
                </div>
                <h3 style="text-align: center;font-weight: 600;color: var(--dark-text);font-family: var(--font-alt);">
                  Daftar Penerimaan Barang dari supplier Saat ini tidak tersedia</h3>
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
                            <div class="collapse-icon is-clickable" @click="items.isExpand = true" v-if="!items.isExpand">
                              <VIcon icon="feather:chevron-down" />
                            </div>
                            <div class="collapse-icon  is-clickable" open @click="items.isExpand = false"
                              v-if="items.isExpand">
                              <VIcon icon="feather:chevron-up" />
                            </div>
                          </div>

                          <!-- <div class="column is-2">
                            <div class="meta">
                              <VInput v-model="item.nostruk" :value="items.nostruk" style="display:none" />
                              <span>{{ items.nostruk }}</span>
                              <span>No Dokumen</span>
                            </div>
                          </div> -->

                          <div class="column is-2">
                            <div class="meta">
                              <span>{{ items.nofaktur }}</span>
                              <span>No Faktur</span>
                            </div>
                          </div>

                          <div class="column is-1">
                            <div class="meta">
                              <span>{{ items.jmlitem }}</span>
                              <span>QTY</span>
                            </div>
                          </div>

                          <div class="column is-2">
                            <div class="meta">
                              <span>
                                {{ formatRupiah(items.totalpembulatan != null && items.totalpembulatan != 0 ? items.totalpembulatan : items.totalharusdibayar) }}
                              </span>
                              <span>Total Tagihan</span>
                            </div>
                          </div>

                          <div class="column">
                            <VButtons>
                              <VIconButton color="primary" circle icon="feather:edit"
                                v-tooltip.bottom.center="'Edit Barang'" outlined @click="editPenerimaan(items)" />
                              <VIconButton color="info" circle icon="feather:edit" v-tooltip.bottom.center="'Edit Header'"
                                @click="editHead(items)" :loading="btnLoadEdit" />
                              <VIconButton color="danger" circle icon="feather:trash"
                                v-tooltip.bottom.center="'Batal Kirim Barang'" outlined @click="dialogConfirm(items)" />
                              <VIconButton color="success" circle icon="fas fa-undo"
                                v-tooltip.bottom.center="'Retur Barang'" @click="returPenerimaan(items)" />
                              <VIconButton color="warning" circle icon="fas fa-print"
                                v-tooltip.bottom.center="'Cetak Bukti'" outlined @click="cetakBuktiPopUp(items)" />
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
                            <div class="columns is-multiline" v-for="(itemsDet, index2)  in filteredDetails(items.details, items.search)" :key="index2">
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
                                  <VLabelText>Harga Satuan </VLabelText>
                                  <VLabel>{{ formatRupiah(itemsDet.hargasatuan) }}
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
                              <div class="column is-1">
                                <VField>
                                  <VLabelText>Qty Retur </VLabelText>
                                  <VLabel>{{ itemsDet.qtyprodukretur ? itemsDet.qtyprodukretur : '-' }}
                                  </VLabel>
                                </VField>
                              </div>
                              <div class="column is-2">
                                <VField>
                                  <VLabelText>Harga Total </VLabelText>
                                  <VLabel>{{ formatRupiah(itemsDet.totalall ? itemsDet.totalall : '-') }}
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
              <VField>
                  <VLabel class="required-field">Tanggal Terima</VLabel>
                  <Calendar v-model="item.tglTerima" showIcon iconDisplay="input" dateFormat="dd/mm/yy" disabled style="font-weight:bold"/>
              </VField>
            </div>
            <div class="column is-4">
              <VField class="is-rounded-select is-autocomplete-select">
                <VLabel class="required-field">Gudang</VLabel>
                <VControl icon="feather:search" fullwidth class="prime-auto-select">
                  <Dropdown v-model="item.gudang" :options="d_Gudang" optionLabel="label" class="is-rounded"
                    placeholder="Pilih Gudang" style="width: 100%;font-weight:bold" :filter="true" disabled />
                </VControl>
              </VField>
            </div>
            <div class="column is-4">
              <VField class="is-rounded-select is-autocomplete-select">
                <VLabel class="required-field">Pegawai Penerima</VLabel>
                <VControl icon="feather:search" fullwidth class="prime-auto-select">
                  <Dropdown v-model="item.penerimaan" :options="d_Pegawai" optionLabel="label" class="is-rounded"
                    placeholder="Pilih Pegawai Penerima" style="width: 100%;font-weight:bold;" :filter="true" disabled/>
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
                    class="is-rounded" placeholder="Pilih Kelompok Barang" style="width: 100%;font-weight:bold" :filter="true" disabled/>
                </VControl>
              </VField>
            </div>
            <div class="column is-3 pt-1">
              <VField class="is-rounded-select is-autocomplete-select">
                <VLabel class="required-field">Sumber Dana</VLabel>
                <VControl icon="feather:search" fullwidth class="prime-auto-select">
                  <Dropdown v-model="item.sumberdana" :options="d_SumberDana" optionLabel="label" class="is-rounded"
                    placeholder="Pilih Sumber Dana" style="width: 100%;font-weight:bold" :filter="true" disabled />
                </VControl>
              </VField>
            </div>
            <div class="column is-6 pt-0" v-if="item.kaskecil == true">
              <div class="columns">
                <div class="column is-6">
                  <VField>
                    <VLabel class="required-field">No Bukti</VLabel>
                    <VControl>
                      <input v-model="item.noBuktiKK" type="text" class="input is-rounded" placeholder="No Dokumen" disabled style="font-weight:bold"/>
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
                    placeholder="Pilih data" style="width: 100%;font-weight:bold" :filter="true" disabled />
                </VControl>
              </VField>
            </div>
            <div class="column is-4 pt-1">
              <VField class="is-rounded-select is-autocomplete-select">
                <VLabel class="required-field">Pegawai</VLabel>
                <VControl icon="feather:search" fullwidth class="prime-auto-select">
                  <Dropdown v-model="item.pegawaiKK" :options="d_komit" optionLabel="label" class="is-rounded"
                    placeholder="Pilih data" style="width: 100%;font-weight:bold" :filter="true" disabled />
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
              <VField>
                <VLabel class="required-field">Tanggal Faktur</VLabel>
                <Calendar v-model="item.tglFaktur" showIcon iconDisplay="input" dateFormat="dd/mm/yy"  />
              </VField>
            </div>
            <!-- <div class="column is-2">
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
            </div> -->
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
                  <VCheckbox v-model="item.nothingFaktur" label="Belum ada no faktur" @change="noSurat(item)" color="info"
                    square />
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
               <VField>
                <VLabel>Tanggal Jatuh Tempo</VLabel>
                <Calendar v-model="item.tglTempo" showIcon iconDisplay="input" dateFormat="dd/mm/yy"  />
              </VField>
              <!-- <VDatePicker v-model="item.tglTempo" color="green" trim-weeks>
                <template #default="{ inputValue, inputEvents }">
                  <VField>
                    <VLabel>Tanggal Jatuh Tempo</VLabel>
                    <VControl icon="feather:calendar">
                      <VInput type="text" placeholder="Select a date" class="is-rounded" :value="inputValue"
                        v-on="inputEvents" />
                    </VControl>
                  </VField>
                </template>
              </VDatePicker> -->
            </div>
          </div>
        </div>
        </p>

        <p v-else-if="activeValue === 'po'">
        <div class="column is-12">
          <div class="columns">
            <div class="column is-3">
               <VField>
                <VLabel>Tanggal</VLabel>
                <Calendar v-model="item.tanggalPo" showIcon iconDisplay="input" dateFormat="dd/mm/yy"  />
              </VField>
              <!-- <VDatePicker v-model="item.tanggalPo" color="green" trim-weeks>
                <template #default="{ inputValue, inputEvents }">
                  <VField>
                    <VLabel>Tanggal</VLabel>
                    <VControl icon="feather:calendar">
                      <VInput type="text" placeholder="Select a date" class="is-rounded" :value="inputValue"
                        v-on="inputEvents" />
                    </VControl>
                  </VField>
                </template>
              </VDatePicker> -->
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
            <div class="column is-5 pt-3">
              <VField>
                <VLabel>Nama Pengadaan</VLabel>
                <VControl>
                  <input v-model="item.namapengadaan" type="text" class="input is-rounded"
                    placeholder="Nama Pengadaan..." />
                </VControl>
              </VField>
            </div>
            <div class="column is-7 pt-3">
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
      <VButton icon="feather:plus" color="primary" raised @click="updateHead(item)" :loading=loadUpdate>Update
      </VButton>
    </template>
  </Dialog>

  <Dialog v-model:visible="modalCetakBukti" modal header="Tanda Tangan" :style="{ width: '50vw' }">
    <div class="column is-12">
      <div class="columns">
        <div class="column is-6">
          <Fieldset legend="Petugas Penyerahan" style="padding:10px">
            <div class="column pt-0">
              <VField class=" is-autocomplete-select" label="Jabatan">
                <VControl icon="feather:search">
                  <Multiselect mode="single" v-model="item.jabatanpenyerah" :options="d_jabatan"
                    placeholder="Pilih jabatan" :searchable="true" />
                </VControl>
              </VField>
            </div>
            <div class="column pt-1">
              <VField class="is-rounded-select is-autocomplete-select" label="Pegawai Penyerah">
                    <VControl icon="feather:search" class="prime-auto-select">
                      <AutoComplete v-model="item.pegawaipenyerah" :suggestions="d_Pegawai" @complete="fetchPegawai($event)"
                        :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                        :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="Pilih Pegawai" />
                    </VControl>
                </VField>
            </div>
          </Fieldset>
        </div>
        <div class="column is-6">
          <Fieldset legend="Petugas Penerima" style="padding:10px">
            <div class="column pt-0">
              <VField class="is-autocomplete-select" label="Jabatan">
                <VControl icon="feather:search">
                  <Multiselect mode="single" v-model="item.jabatanpenerima" :options="d_jabatan"
                    placeholder="Pilih jabatan" :searchable="true" />
                </VControl>
              </VField>
            </div>
            <div class="column pt-1">
               <VField class="is-rounded-select is-autocomplete-select" label="Pegawai Penerima">
                    <VControl icon="feather:search" class="prime-auto-select">
                      <AutoComplete v-model="item.pegawaipenerima" :suggestions="d_Pegawai" @complete="fetchPegawai($event)"
                        :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                        :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="Pilih Pegawai" />
                    </VControl>
                </VField>
            </div>
          </Fieldset>
        </div>
      </div>
      <div class="column is-12 p-0">
        <Fieldset legend="Mengetahui" style="padding:10px">
          <div class="column pt-0">
            <VField class="is-autocomplete-select" label="Jabatan">
              <VControl icon="feather:search">
                <Multiselect mode="single" v-model="item.jabatanketahui" :options="d_jabatan" placeholder="Pilih jabatan"
                  :searchable="true" />
              </VControl>
            </VField>
          </div>
          <div class="column pt-1">
              <VField class="is-rounded-select is-autocomplete-select" label="Pegawai">
                  <VControl icon="feather:search" class="prime-auto-select">
                    <AutoComplete v-model="item.pegawaiketahui" :suggestions="d_Pegawai" @complete="fetchPegawai($event)"
                        :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                        :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="Pilih Pegawai" />
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
import AutoComplete from 'primevue/autocomplete';
import Calendar from 'primevue/calendar';
import * as H from '/@src/utils/appHelper'
import ConfirmDialog from 'primevue/confirmdialog'
import Dialog from 'primevue/dialog';
import DataTable from 'primevue/datatable'
import Dropdown from 'primevue/dropdown'
import Column from 'primevue/column'

import { useViewWrapper } from '/@src/stores/viewWrapper'
import { booleanTypeAnnotation } from '@babel/types';

useHead({
  title: 'Penerimaan Barang Suplier - ' + import.meta.env.VITE_PROJECT,
})

useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)

const item: any = ref({
  aktif: true,
  isKK: false,
  filterTgl: [new Date(),new Date()],
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
const detailPenerimaan: any = ref([])
const detailPenerimaans: any = ref([])
const modalEditHead: any = ref(false)
const modalCetakBukti: any = ref(false)
const btnLoadEdit: any = ref(false)
const loadUpdate: any = ref(false)
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

const updateHead = async (e: any) => {
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

  //     isLoadingBtn.value = true
  const objSave = {
    // details: detailPenerimaan.value,
    struk: {
      nosppb: '',
      nostruk: e.nostruk,
      norec: e.norec,
      norecsppb: '',
      norecrealisasi: item.value.norecrealisasi ? item.value.norecrealisasi : '',
      objectmataanggaranfk: '',
      norecOrder: '',
      jenissusulan: 'Medis',
      jenissusulanfk: 2,
      noorder: item.value.nosppb ? item.value.nosppb : '',
      noBuktiKK: item.value.noBuktiKK ? item.value.noBuktiKK : '',
      pegawaiKK: item.value.pegawaiKK ? item.value.pegawaiKK.value.id : null,
      ruanganfkKK: item.value.ruanganKK ? item.value.ruanganKK.value : '',
      ruanganfk: item.value.gudang.value,
      asalproduk: item.value.sumberdana.value,
      pegawaikomit: item.value.pembuatkomit ? item.value.pembuatkomit.namalengkap : '',
      namapegawaipenerima: item.value.penerimaan.value.namalengkap,
      namarekanan: item.value.supplier.label,
      namapengadaan: item.value.namapengadaan ? item.value.namapengadaan : '',
      ketTerima: item.value.ketTerima ? item.value.ketTerima : '',
      nokontrak: item.value.nokontrak ? item.value.nokontrak : '',
      pegawaimenerimafk: item.value.penerimaan.value.id,
      rekananfk: item.value.supplier.value.id,
      nofaktur: item.value.noFaktur,
      kelompokprodukfk: item.value.kelompokbarang.value,
      nousulan: item.value.nousulan ? item.value.nousulan : '',
      qtyproduk: e.qtypelayanan,
      tglSppb: null,
      tglKK: item.value.tanggalKK ? H.formatDate(item.value.tanggalKK, 'YYYY-MM-DD HH:mm:ss') : null,
      tglTempo: item.value.tglTempo ? H.formatDate(item.value.tglTempo, 'YYYY-MM-DD HH:mm:ss') : null,
      tglfaktur: item.value.tglFaktur ? H.formatDate(item.value.tglFaktur, 'YYYY-MM-DD HH:mm:ss') : null,
      tglstruk: item.value.tglTerima ? H.formatDate(item.value.tglTerima, 'YYYY-MM-DD HH:mm:ss') : null,
      tglorder: item.value.tanggalPo ? H.formatDate(item.value.tanggalPo, 'YYYY-MM-DD HH:mm:ss') : null,
      tglkontrak: item.value.tanggalPo ? H.formatDate(item.value.tanggalPo, 'YYYY-MM-DD HH:mm:ss') : null,
      tglrealisasi: item.value.tglFaktur ? H.formatDate(item.value.tglFaktur, 'YYYY-MM-DD HH:mm:ss') : null,
      // totaldiscount: e.diskon,
      // totalhargasatuan: e.totalsatuan,
      // totalharusdibayar: e.totalbayar,
      // totalppn: e.ppn
    },
  }
  loadUpdate.value = true
  await useApi()
    .post('/logistik/penerimaan-barang/edit-header-penerimaan-suplier', objSave)
    .then((response) => {
      modalEditHead.value = false
      loadUpdate.value = true
      fetchData()
    })
    .catch((err) => {
      modalEditHead.value = false
      console.log(err)
    })
}

const fetchJabatan = async ()=>{
  await useApi().get('logistik/penerimaan-barang/get-jabatan').then((respon)=>{
    d_jabatan.value = respon.map((e:any)=>{
      return {label : e.namajabatan , value : e.id}
    })
  })
}

const formatRupiah = (number) => {
  return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(number);
};

const listDataCombo = async () => {
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
      d_jabatan.value = response.jabatan.map((e: any) => {
        return { label: e.namajabatan, value: e }
      })
    })
}

const fetchData = async () => {

  isLoadData.value = true
  let tglAwal = '';
  let tglAkhir = '';
  if (item.value.filterTgl) {
    if (item.value.filterTgl[0]) {
      tglAwal = H.formatDate(item.value.filterTgl[0], 'YYYY-MM-DD 00:00')
    }
    if (item.value.filterTgl[1]) {
      tglAkhir = H.formatDate(item.value.filterTgl[1], 'YYYY-MM-DD 23:59')
    } else {
      tglAkhir = H.formatDate(item.value.filterTgl[0], 'YYYY-MM-DD 23:59')
    }
  }
  let idRekanan = item.value.rekanan ? `&rekananfk=${item.value.rekanan}` : ''
  let noDok = item.value.nodokumen ? `&nodokumen=${item.value.nodokumen}` : ''
  let namaProduk = item.value.namaProduk ? `&namaproduk=${item.value.namaProduk}` : ''
  let kodeProduk = item.value.kodeProduk ? `&kodeproduk=${item.value.kodeProduk}` : ''
  let limit: any = currentPage.value.limit
  let offset: any = route.query.page ? route.query.page : 1
  offset = (offset * limit) - limit
  isLoadingBtn.value = true
  await useApi().get(`/logistik/penerimaan-barang?tglAwal=${tglAwal}&tglAkhir=${tglAkhir}${idRekanan}${noDok}${kodeProduk}${namaProduk}
  &limit=${limit}&offset=${offset}`).then((response) => {
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
    tglAwal:  tglAwal,
    tglAkhir:  tglAkhir,
  }
  useApi().postNoMessage('/general/save-jurnal-penerimaan-barang', objSave )


}

const editHead = async (e: any) => {
  btnLoadEdit.value = true
  await listDataCombo()
  await useApi().get(`/logistik/get-detail-penerimaan?norec=${e.norec}`).then((response) => {
    let dataPeneriman = response.detailterima
    let datapelayanan = response.pelayananPasien
    // item.value.asalpro = dataPenerimaan.asalprodukfk
    d_Pegawai.value.forEach((e: any) => {
      if (e.value.id == dataPeneriman.pgid) {
        item.value.penerimaan = e
        return
      }
    });
    d_Gudang.value.forEach((e: any) => {
      if (e.value == dataPeneriman.objectruanganfk) {
        item.value.gudang = e
        return
      }
    });
    d_kelompokBarang.value.forEach((e: any) => {
      if (e.value == dataPeneriman.objectkelompokprodukfk) {
        item.value.kelompokbarang = e
        return
      }
    });
    d_SumberDana.value.forEach((e: any) => {
      if (e.value == dataPeneriman.asalprodukfk) {
        item.value.sumberdana = e
        return
      }
    });
    d_suplier.value.forEach((e: any) => {
      if (e.value.id == dataPeneriman.objectrekananfk) {
        item.value.supplier = e
        return
      }
    });
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

    console.log(dataPeneriman)
    // item.value.kelompokbarang = dataPeneriman.objectkelompokprodukfk
    item.value.noBKK = dataPeneriman.nobukti ? true : false
    item.value.noBuktiKK = item.value.noBKK == true ? dataPeneriman.nobukti : ''
    noBukti.value = dataPeneriman.nobukti
    item.value.norec = dataPeneriman.norec
    item.value.norecrealisasi = dataPeneriman.norecrealisasi
    item.value.nostruk = dataPeneriman.nostruk
    item.value.tanggalKK = dataPeneriman.tglspk
    item.value.nostruk = dataPeneriman.nostruk
    item.value.noBukti = dataPeneriman.nobukti
    item.value.tglTerima = H.formatDate(dataPeneriman.tglstruk, 'MM/DD/YYYY')
    item.value.tglFaktur =  H.formatDate(dataPeneriman.tglfaktur, 'MM/DD/YYYY')
    item.value.noFaktur = dataPeneriman.nofaktur
    item.value.tglTempo =  H.formatDate(dataPeneriman.tgljatuhtempo, 'MM/DD/YYYY')
    item.value.nousulan = dataPeneriman.nousulan
    item.value.tanggalPo =  H.formatDate(dataPeneriman.tgldokumen, 'MM/DD/YYYY')
    item.value.namapengadaan = dataPeneriman.namapengadaan
    item.value.nokontrak = dataPeneriman.nokontrak
    detailPenerimaan.value = datapelayanan
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
  modalEditHead.value = true
  btnLoadEdit.value = false
}

const showDetail = (e:any)=>{
  e.isExpand = true
  detailPenerimaans.value = e.details

}

const editPenerimaan = (e: any) => {
  router.push({
    name: 'module-logistik-form-penerimaan-barang-suplier',
    query: {
      norec: e.norec,
    },
  })
}

const returPenerimaan = (e: any) => {
  router.push({
    name: 'module-logistik-retur-penerimaan-barang-suplier',
    query: {
      norec: e.norec,
    },
  })
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
  await useApi().post('/logistik/penerimaan-barang/delete-penerimaan-suplier', { 'nostruk': e.norec }).then((response) => {
    console.log(response)
    fetchData()
  }).catch((err: any) => {
    console.log(err)
  })
}

const cetakBuktiPopUp = (e: any) => {
  item.value.norec = e.norec
  modalCetakBukti.value = true
}

const cetakBukti = async (e: any) => {
  if (!e.pegawaipenyerah) {
    H.alert('error', 'Petugas Penyerahan Harus Diisi');
    return
  }
  if (!e.pegawaipenerima) {
    H.alert('error', 'Petugas Penerima Harus Diisi');
    return
  }

  if (!e.pegawaiketahui) {
    H.alert('error', 'Petugas Mengetahui Harus Diisi');
    return
  }
  let pegawaiPenyerah = `&pegawaiMeminta=${e.pegawaipenyerah.value}`
  let pegawaiMengetahui = `&pegawaiMengetahui=${e.pegawaiketahui.value}`
  let pegawaiPenerima = `&pegawaiPenerima=${e.pegawaipenerima.value}`
  H.printBlade(`logistik/report/cetak-bukti-penerimaan-barang?norec=${e.norec}${pegawaiPenyerah}${pegawaiPenerima}${pegawaiMengetahui}`);

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

const createNoFaktur = () => {
  /* Format No Faktur PB/BLN-THN/APT/NO URUT (APT = BLU, BG = Hibah,  KK = Kas  Kecil) */
  let nows = moment(new Date()).format('MM-YY')
  if (item.value.sumberdana === 1) {
    item.value.noFaktur = 'PB/' + nows + '/APT/____'
  } else if (item.value.sumberdana === 3) {
    item.value.noFaktur = 'PB/' + nows + '/BG/____'
  } else if (item.value.sumberdana === 7) {
    item.value.noFaktur = 'PB/' + nows + '/KK/____'
  } else {
    delete item.value.noFaktur
  }
}

const fetchPegawai = async (filter: any) => {
  await useApi().get(
    `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`
  ).then((response) => {
    d_Pegawai.value = response
  })
}


const clear = () => {
  modalEditHead.value = false
  delete item.value.tglTerima
  delete item.value.gudang
  delete item.value.penerimaan
  delete item.value.kelompokbarang
  delete item.value.sumberdana
  delete item.value.ruanganKK
  delete item.value.noBuktiKK
  delete item.value.pegawaiKK
  delete item.value.tglFaktur
  delete item.value.noFaktur
  delete item.value.supplier
  delete item.value.tglTempo
  delete item.value.tanggalPo
  delete item.value.nousulan
  delete item.value.namapengadaan
  delete item.value.nokontrak
}
const filteredDetails = (details:any, search:any)=> {
    const filterText = search ? search.toLowerCase():''
    return details.filter((detail:any) => detail.namaproduk.toLowerCase().includes(filterText));
}

watch(
  () => [
    item.value.sumberdana,
  ],
  () => {
    if (item.value.sumberdana && item.value.sumberdana.value == 7) {
      item.value.kaskecil = true
      console.log(item.value.kaskecil)
    } else {
      item.value.kaskecil = false
    }
  }
)

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
listProduk()
fetchJabatan()
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
