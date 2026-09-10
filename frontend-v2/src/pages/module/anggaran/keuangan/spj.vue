<template>
    <ConfirmDialog/>
<VCard>
    <div class="form-layout">
        <div class="form-outer">
            <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
                <div class="form-header-inner">
                    <div class="left">
                        <h3>SPJ</h3>
                    </div>
                </div>
            </div>
            <div class="column is-12">
                <div class="columns is-multiline">
                    <div class="column is-12">
                        <div class="columns is-multiline">
                            <div class="column is-1">
                                    <VButton type="button" icon="feather:plus" :loading="isLoading" color="primary" raised class="is-rounded"
                                        @click="TambahSPJ()" > Tambah SPJ
                                    </VButton>
                                </div>
                        </div>
                    </div>
                    <div class="column is-12">
                        <div class="columns all-projects m-3 mt-0">
                            <div class="columns is-multiline  projects-card-grid">
                              <div class="column is-8">
                                <div class="flex-list-inner" v-if="dataSourceSPJ.length === 0">
                                  <VCard>
                                    <VPlaceholderSection title="Not found" subtitle="There is no data that match your query." class="my-6">
                                      <template #image>
                                        <img class="light-image" src="/@src/assets/illustrations/placeholders/search-4.svg" alt="" />
                                        <img class="dark-image" src="/@src/assets/illustrations/placeholders/search-4-dark.svg" alt="" />
                                      </template>
                                    </VPlaceholderSection>
                                  </VCard>
                                </div>
                                <div v-else-if="dataSourceSPJ.length > 0">
                                  <div class="grid-item mb-4" v-for="(items, i) in dataSourceSPJ" :key="items.id">
                                    <div class="top-section">
                                      <div class="head">
                                        <div class="title-wrap">
                                          <div class="columns">
                                            <div class="column is-12 mr-3">
                                              <h3>{{ items.norealisasi }}</h3>
                                              <p>{{ moment(items.tglrealisasi).format('DD-MM-YYYY')}}</p>
                                            </div>
                                            <div class="column is-6 mr-1">
                                                <h3>Total Belanja</h3>
                                                <p>{{ H.formatRp(items.totalbelanja, 'Rp. ')}}</p>
                                              </div>
                                          </div>
                                        </div>
                                        
                                        
                                        <VDropdown icon="feather:more-vertical" spaced right v-tooltip.bubble="'AKSI'">
                                          <template #content>
                      
                                            <a role="menuitem" href="#" class="dropdown-item is-media" @click="deleteDetailSPJ(items)" style="color: red">
                                              <div class="icon">
                                                <i class="iconify" data-icon="feather:trash-2" aria-hidden="true"></i>
                                              </div>
                                              <div class="meta">
                                                <span>Hapus SPJ</span>
                                                <span>Hapus Semua Data SPJ</span>
                                              </div>
                                            </a>
                                            <hr class="dropdown-divider" />
                                            <a role="menuitem" href="#" class="dropdown-item is-media" @click="showModalGabungRM(items)">
                                              <div class="icon">
                                                <i aria-hidden="true" class="lnil lnil-archive"></i>
                                              </div>
                                              <div class="meta">
                                                <span>Pencairan Melalui Panjar</span>
                                              </div>
                                            </a>
                      
                                          </template>
                                        </VDropdown>
                                        <!-- <ProjectCardDropdown /> -->
                                      </div>
                                      <div class="body">
                                        <div class="columns">
                                          <div class="column">
                                            <h4 class="heading">Kegiatan</h4>
                                            <p class="fs-075">{{ items.kegiatan }}</p>
                                            <!-- <p class="fs-075">Tanggal : {{ items.tglregistrasi }}</p> -->
                                          </div>
                                          <div class="column">
                                            <h4 class="heading">Kode Rekening</h4>
                                            <p class="fs-075">{{ items.mataanggaran }}</p>
                                          </div>
                                          <div class="column">
                                            <h4 class="heading">Sumber Dana</h4>
                                            <p class="fs-075">{{ items.asalproduk }}</p>
                                          </div>
                                          <div class="column">
                                            <h4 class="heading">Pegawai</h4>
                                            <!-- <VTag color="info" :label="items.umur" /> -->
                                            <p class="fs-075"> Penerima: {{ items.penerima }}</p>
                                            <p class="fs-075"> Bendahara: {{ items.bendahara }}</p>

                                          </div>
                                          <div class="column">
                                            <h4 class="heading">Pihak Ketiga</h4>
                                            <!-- <VTag color="info" :label="items.umur" /> -->
                                            <p class="fs-075"> {{ items.pihakketiga }}</p>
                                          </div>
                                          <div class="column">
                                            <h4 class="heading">Status Verifikasi</h4>
                                            <VTag :color="items.status_c" :label="items.status_name" />
                                            <!-- <p class="fs-075"> {{ items.pihakketiga }}</p> -->
                                          </div>
                                        </div>
                                      </div>
                                    </div>
                                    <div class="bottom-section">
                                      <div class="foot-block">
                                        <h4 class="heading">Action</h4>
                                        <div class="developers">
                                          <VButton type="button" icon="fas fa-info" class="mr-3" color="info" :loading="isDetailSPJ"
                                            raised @click="editDetailSPJ(items)">
                                            Detail </VButton>
                                            <!-- <VButton type="button" icon="fas fa-notes-medical" class="mr-3" color="danger" outlined
                                            raised @click="deleteDetailSPJ(items)">
                                            Hapus SPJ </VButton> -->
                                            <!-- <VButton type="button" icon="fas fa-notes-medical" class="mr-3" color="warning" outlined
                                            raised @click="transaksiPelayanan(items)">
                                            Cetak SPJ </VButton> -->
                                            <VButton type="button" icon="fas fa-print" class="mr-3" color="warning" outlined
                                            raised @click="CetakSPJ(items)">
                                            Cetak SPJ dan Lampiran </VButton>
                                            <VButton type="button" icon="fas fa-check" class="mr-3" color="success" outlined
                                            raised @click="verifSPJ(items)">
                                            Verifikasi </VButton>
                                            <VButton type="button" icon="fas fa-times" class="mr-3" color="danger" outlined
                                            raised @click="batalVerifSPJ(items)">
                                            Batal Verifikasi </VButton>
                                            <VButton v-if="items.carabayar == 'PANJAR'" type="button" icon="fas fa-plus" class="mr-3" color="info" outlined
                                            raised @click="tambahPanjar(items)" :loading="isAddPanjar">
                                            Add Panjar </VButton>
                                            
                                        </div>
                                      </div>
                                    </div>
                      
                                  </div>
                                </div>
                                <VFlexPagination v-model:current-page="currentPage.page" :item-per-page="currentPage.limit"
                                  :total-items="totalSPJ" :max-links-displayed="5">
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
                              <div class="column is-4">
                                <div class="columns is-multiline">
                                  <div class="column is-12">
                                    <VField>
                                      <VControl icon="feather:search">
                                        <input v-model="item.qnama" v-on:keyup.enter="fetchData()" type="text" class="input is-rounded" :loading="isFilter"
                                          placeholder="Filter" />
                                      </VControl>
                                    </VField>
                                  </div>
                                  <div class="column is-6">
                                    <h3 class="title is-5 mb-2 mr-1">Filters </h3>
                                  </div>
                                  <!-- <div class="column is-6">
                                    <a @click="clearFilter()" type="button" class="is-pulled-right mr-3" color="info" outlined raised> Clear
                                      All </a>
                                  </div> -->
                                  <div class="column is-12">
                                    <VField class="is-autocomplete-select">
                                        <span>Status SPJ</span>
                                        <VControl icon="feather:search" class="prime-auto-select">
                                            <Dropdown v-model="item.statusspj" :options="listStatusSPJ"
                                                :optionLabel="'status'" class="is-rounded" placeholder="Pilih data"
                                                style="width: 100%;" showClear :filter="false" :loading="isLoading"/>
                                        </VControl>
                                    </VField>
                                </div>
                                  <div class="column is-12">
                                    <VField>
                                      <VLabel>Tanggal SPJ</VLabel>
                                      <VDatePicker v-model="item.filterTgl" is-range color="pink" trim-weeks>
                                        <template #default="{ inputValue, inputEvents }">
                                        <VField addons>
                                            <VControl icon="feather:calendar" class="is-rounded">
                                            <VInput :value="inputValue.start" v-on="inputEvents.start" class="is-rounded"/>
                                            </VControl>
                                            <VControl>
                                            <VButton static class="is-rounded"><i class="fas fa-arrow-right" aria-hidden="true"></i></VButton>
                                            </VControl>
                                            <VControl icon="feather:calendar" class="is-rounded">
                                            <VInput :value="inputValue.end" v-on="inputEvents.end" class="is-rounded" />
                                            </VControl>
                                        </VField>
                                        </template>
                                    </VDatePicker>
                                    </VField>
                                  </div>
                                  <div class="column is-12">
                                    <VButton @click="fetchData()" :loading="isApply" type="button" icon="feather:search"
                                      class="is-fullwidth mr-3" color="info" raised> Apply Filters
                                    </VButton>
                                  </div>
                                </div>
                      
                              </div>
                            </div>
                          </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</VCard>

<Dialog :loading="isLoadingDialog" :maximizable="true"  v-model:visible="popupTambahSPJ" :style="{width: '100%'}" header="Entry SPJ" :modal="true" class="p-fluid">
    <div class="columns is-multiline">
        <div class="column is-12">
            <div class="column is-2">
                <span>Tanggal Pelaksanaan Kegiatan</span>
                <Calendar v-model="item.tglSPJ" dateFormat="dd/mm/yy" placeholder="dd/mm/yyyy"/>
            </div>
            <div class="columns is-multiline">
                <div class="column is-4">
                    <VField class="is-autocomplete-select">
                        <span>Sub Kegiatan</span>
                        <VControl icon="feather:search">
                            <Dropdown v-model="item.subKegiatanSPJ" :options="listSubKegiatanSPJ" :optionLabel="'keterangan'"
                                class="is-rounded" placeholder="Sub Kegiatan" :loading="isLoading" style="width: 100%;" :filter="true" @change="changeSubsubKegiatan($event)"
                                showClear  />
                        </VControl>
                    </VField>
                </div>
                <div class="column is-4">
                    <VField class="is-autocomplete-select">
                        <span>Sub Sub Kegiatan</span>
                        <VControl icon="feather:search">
                            <Dropdown v-model="item.subSubKegiatanSPJ" :options="listSubSubKegiatanSPJ" :optionLabel="'keterangan'"
                                class="is-rounded" placeholder="Sub sub Kegiatan" style="width: 100%;" :filter="true" :loading="isLoading" @change="changeRekening($event)"
                                showClear />
                        </VControl>
                    </VField>
                </div>
                <div class="column is-4">
                    <VField class="is-autocomplete-select">
                        <span>Dari Rekening</span>
                        <VControl icon="feather:search">
                            <Dropdown v-model="item.mataAnggaranSPJ" :options="listMataAnggaranSPJ" :optionLabel="'keterangan'"
                                class="is-rounded" placeholder="Dari Rekening" style="width: 100%;" :filter="true" :loading="isLoading" @change="changeCboPilihNoRekening($event)"
                                showClear />
                        </VControl>
                    </VField>
                </div>

            </div>
            <div class="columns is-multiline">
                <div class="column is-2">
                    <VField class="is-autocomplete-select">
                        <span>Sumber Dana</span>
                        <VControl icon="feather:search" class="prime-auto-select">
                            <Dropdown v-model="item.sumberDanaSPJ" :options="listAsalProduk"
                                :optionLabel="'asalproduk'" class="is-rounded" placeholder="Pilih data"
                                style="width: 100%;" showClear :filter="false" :loading="isLoading"/>
                        </VControl>
                    </VField>
                </div>
                <div class="column is-6">
                    <VField>
                        <span>Sisa Anggaran</span>
                        <VControl icon="feather:edit-3">
                            <VInput type="text" v-model="item.sisaAnggaranSPJTxt" placeholder=""
                                class="is-rounded" disabled/>
                        </VControl>
                    </VField>
                </div>
            </div>
            <div class="column is-12">
                <span>Pencairan Melalui</span>
                <div class="flex flex-wrap gap-3">
                    <div v-for="category in categories" :key="category.key" class="flex align-items-center">
                        <RadioButton v-model="item.radioPencairan" :inputId="category.key" name="dynamic" :value="category.key" />
                        <label :for="category.key" class="ml-2">{{ category.name }}</label>
                    </div>
                </div>
            </div>
            <div class="column is-12">
                <VField>
                    <span>Dasar Kontraktual</span>
                    <VControl icon="feather:edit-3">
                        <VInput type="text" v-model="item.dasarKontraktualSPJ" placeholder=""
                            class="is-rounded"/>
                    </VControl>
                </VField>
            </div>
            <div class="column is-12">
                <span>Rincian Belanja</span>
                <DataTable paginator :rows="20" :loading="isLoadingTable"  :value="dataSourceDetailSPJ"  tableStyle="min-width: 50rem" filterDisplay="row">
                    <template #header>
                        <div class="columns is-multiline">
                            
                            <div class="column is-4">
                                <VButton type="button" icon="feather:plus" :loading="isLoadingBtn" color="primary" raised class="is-rounded"
                                    @click="TambahRincianBelanjaSPJ()" > Tambah
                                </VButton>
                            </div>
                        </div>
                    </template>
                    <Column :exportable="false" header="#" style="width:10%">
                        <template #body="slotProps">
                            <VIconButton type="button" icon="feather:trash" class="mr-3" color="danger"
                                circle outlined raised v-tooltip-prime="'Hapus'" :loading="isLoadingBtn"
                                @click="deleteDetailRincianBelanjaSPJ(slotProps.data)">
                            </VIconButton>
                        </template>
                    </Column>
                    <Column v-for="col in columnDetailSPJ"  :class="col.field == 'keterangan' ? 'font-bold' : ''" :frozen="col.field == 'keterangan' || col.field == 'subtotal' || col.title == 'Sub Total' ? true : false" :field="col.field" :header="col.title" :style="'min-width:' + col.width">
                        <template #body="slotProps">
                            <span>{{ col.template != undefined ?
                                H.formatRupiah(slotProps.data[col.field], '')
                                : slotProps.data[col.field] }}</span>
                        </template>
                    </Column>
                    
                </DataTable>
            </div>
            <div class="column is-6">
                <VField>
                    <span>Jumlah Total SPJ</span>
                    <VControl icon="feather:edit-3">
                        <VInput type="text" v-model="item.jumlahSPJ" placeholder=""
                            class="is-rounded" disabled/>
                    </VControl>
                </VField>
            </div>
            <div class="column is-6">
                <VField>
                    <span>Jumlah Pajak</span>
                </VField>
            </div>
            <div class="column is-6">
                <VField>
                    <span>1. PPh</span>
                    <VControl icon="feather:edit-3">
                        <VInput type="text" v-model="item.pph" placeholder=""
                            class="is-rounded"/>
                    </VControl>
                </VField>
            </div>
            <div class="column is-6">
                <VField>
                    <span>2. PPN</span>
                    <VControl icon="feather:edit-3">
                        <VInput type="text" v-model="item.ppn" placeholder=""
                            class="is-rounded"/>
                    </VControl>
                </VField>
            </div>
            <div class="column is-6">
                <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VLabel class="required-field">Bendahara</VLabel>
                    <VControl icon="feather:search">
                        <AutoComplete v-model="item.bendaharaSPJ"
                            :suggestions="d_Pegawai" @complete="fetchDokter($event)"
                            :optionLabel="'label'" :dropdown="true" :minLength="3"
                            :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                            :field="'label'" placeholder="ketik nama petugas" />
                    </VControl>
                </VField>
            </div>
            <div class="column is-6">
                <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VLabel class="required-field">Penerima</VLabel>
                    <VControl icon="feather:search">
                        <AutoComplete v-model="item.penerimaSPJ"
                            :suggestions="d_Pegawai" @complete="fetchDokter($event)"
                            :optionLabel="'label'" :dropdown="true" :minLength="3"
                            :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                            :field="'label'" placeholder="ketik nama petugas" />
                    </VControl>
                </VField>
            </div>
            <div class="column is-6">
                <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VLabel class="required-field">Pihak Ketiga</VLabel>
                    <VControl icon="feather:search">
                        <AutoComplete v-model="item.pihakKetigaSPJ"
                            :suggestions="d_Rekanan" @complete="fetchRekanan($event)"
                            :optionLabel="'label'" :dropdown="true" :minLength="3"
                            :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                            :field="'label'" placeholder="ketik nama petugas" />
                    </VControl>
                </VField>
            </div>
            <div class="column is-3">
                <span><br></span>
                <VButton type="button" icon="feather:save" :loading="isSimpan" color="danger" raised class="is-rounded"
                    @click="SimpanPopUpDetailSPJ()" > Simpan
                </VButton>
            </div>
        </div>
    </div>
    
    <!-- <template #footer>
        <VButton type="button" rounded outlined color="primary" raised icon="feather:save" :loading="isLoadingSimpan"
            @click="SimpanPopUpAlokasi()"> Simpan
        </VButton>
    </template> -->
</Dialog>

<Dialog :loading="isLoadingDialog" :maximizable="true"  v-model:visible="popupTambahDetailSPJ" :style="{width: '100%'}" header="Entry Rincian Belanja" :modal="true" class="p-fluid">
    <div class="columns is-multiline">
        <div class="column is-12">
            <div class="columns is-multiline">
                <div class="column is-4">
                    <VField class="is-autocomplete-select">
                        <span>Rincian</span>
                        <VControl icon="feather:search">
                            <Dropdown v-model="item.itemRincian" :options="listItemRincian" :optionLabel="'keteranganbelanja'"
                                class="is-rounded" placeholder="Rincian" :loading="isLoading" style="width: 100%;" :filter="true" @change="changeDetailRincian($event)"
                                showClear  />
                        </VControl>
                    </VField>
                </div>
                <div class="column is-3">
                    <VField>
                        <span>Sisa Anggaran Rincian</span>
                        <VControl icon="feather:edit-3">
                            <VInput type="text" v-model="item.sisaDetailTxt" placeholder="" disabled
                                class="is-rounded"/>
                        </VControl>
                    </VField>
                </div>
            </div>
            <div class="columns is-multiline">
                <div class="column is-1">
                    <VField>
                        <span>No Urut</span>
                        <VControl icon="feather:edit-3">
                            <VInput type="text" v-model="item.noUrutSPJ" placeholder=""
                                class="is-rounded"/>
                        </VControl>
                    </VField>
                </div>
                <div class="column is-3">
                    <VField>
                        <span>Keterangan</span>
                        <VControl icon="feather:edit-3">
                            <VInput type="text" v-model="item.keteranganSPJ" placeholder=""
                                class="is-rounded"/>
                        </VControl>
                    </VField>
                </div>
                
                <div class="column is-1">
                    <VField>
                        <span>Jumlah</span>
                        <VControl icon="feather:edit-3">
                            <VInput type="text" v-model="item.jmlSPJ" placeholder=""
                                class="is-rounded"/>
                        </VControl>
                    </VField>
                </div>
                <div class="column is-1">
                    <VField>
                        <span>Satuan</span>
                        <VControl icon="feather:edit-3">
                            <VInput type="text" v-model="item.satuanSPJ" placeholder=""
                                class="is-rounded"/>
                        </VControl>
                    </VField>
                </div>
                <div class="column is-2">
                    <VField>
                        <span>Harga Satuan</span>
                        <VControl icon="feather:edit-3">
                            <VInput type="text" v-model="item.hargaSatuanSPJ" placeholder=""
                                class="is-rounded"/>
                        </VControl>
                    </VField>
                </div>
                <div class="column is-2">
                    <VField>
                        <span>Sub Total</span>
                        <VControl icon="feather:edit-3">
                            <VInput type="text" v-model="item.subTotalSPJTxt" placeholder=""
                                class="is-rounded" disabled/>
                        </VControl>
                    </VField>
                </div>
                <div class="column is-3" v-if="item.ShowPassword">
                    <VField>
                        <span>Password</span>
                        <VControl icon="feather:edit-3">
                            <VInput type="text" v-model="item.passwordrba" placeholder=""
                                class="is-rounded"/>
                        </VControl>
                    </VField>
                    <VField v-if="false">
                        <span>Password</span>
                        <VControl icon="feather:edit-3">
                            <VInput type="text" v-model="item.passwordrba" placeholder=""
                                class="is-rounded"/>
                        </VControl>
                    </VField>
                </div>
                <div class="column is-2">
                    <span><br></span>
                    <VButton type="button" icon="feather:save" :loading="isLoadingBtn" color="danger" raised class="is-rounded"
                        @click="SimpanPopUpRincianBelanjaSPJ()" > Simpan
                    </VButton>
                </div>
            </div>
            
            
        </div>
    </div>
    
    <!-- <template #footer>
        <VButton type="button" rounded outlined color="primary" raised icon="feather:save" :loading="isLoadingSimpan"
            @click="SimpanPopUpAlokasi()"> Simpan
        </VButton>
    </template> -->
</Dialog>

<Dialog :loading="isLoadingDialog" :maximizable="true"  v-model:visible="popupVerifSPJ" :style="{width: '100%'}" header="Verifikasi SPJ" :modal="true" class="p-fluid">
    <div class="columns is-multiline">
        <div class="column is-12">
            <div class="column is-12">
                <span>Status SPJ</span>
                <div class="flex flex-wrap gap-6">
                    <div v-for="category in categoriess" :key="category.key" class="flex align-items-center">
                        <RadioButton v-model="item.radioVerifikasi" :inputId="category.key" name="dynamic" :value="category.key" />
                        <label :for="category.key" class="ml-2">{{ category.name }}</label>
                    </div>
                </div>
            </div>
            <div class="column is-2">
                <span>Tanggal Verifikasi</span>
                <Calendar v-model="item.tglSPJ" dateFormat="dd/mm/yy" placeholder="dd/mm/yyyy"/>
            </div>
            <div class="column is-8">
                <span>Keterangan</span>
                <Textarea v-model="item.keteranganVerif" variant="filled" rows="50" cols="50" />
            </div>
            <div class="column is-3">
                <span><br></span>
                <VButton type="button" icon="feather:save" :loading="isSimpanVerif" color="danger" raised class="is-rounded"
                    @click="SimpanVerifikasiSPJ()" > Simpan
                </VButton>
            </div>
        </div>
    </div>
    
    <!-- <template #footer>
        <VButton type="button" rounded outlined color="primary" raised icon="feather:save" :loading="isLoadingSimpan"
            @click="SimpanPopUpAlokasi()"> Simpan
        </VButton>
    </template> -->
</Dialog>


<Dialog :loading="isLoadingDialog" :maximizable="true"  v-model:visible="popUpPanjar" :style="{width: '100%'}" header="Add Panjar" :modal="true" class="p-fluid">
  <div class="columns is-multiline">
    <div class="column is-12">Panjar Tersedia</div>
    <div class="column is-12">
      <DataTable paginator :rows="20" :loading="isLoadingTable"  :value="dataSourceAddPanjar"  tableStyle="min-width: 50rem" filterDisplay="row">
          <Column header="No Panjar" field="nopanjar"></Column>
          <Column header="Uraian" field="uraian"></Column>
          <Column header="Tanggal" field="tglpanjar"></Column>
          <Column header="Jumlah" field="jumlah">
            <template #body="slotProps">
              {{H.formatRupiah(slotProps.data.jumlah, 'Rp. ')}}
            </template>
          </Column>
          <Column header="Sumber" field="sumberpanjar"></Column>
          <Column :exportable="false" header="#" style="width:10%">
              <template #body="slotProps">
                  <!-- <VIconButton type="button" icon="feather:arrow-right" class="mr-3" color="info"
                      circle outlined raised v-tooltip-prime="'Pilih'" :loading="isLoadingBtn"
                      @click="pilihPanjar(slotProps.data)">
                  </VIconButton> -->
                  <VButton type="button" icon="feather:plus" :loading="isSimpan" color="success" raised class="is-rounded" outlined
                    @click="pilihPanjar(slotProps.data)" > Pilih
      </VButton>
              </template>
          </Column>
          <Column :exportable="false" header="dipilih" style="width:10%">
              <template #body="slotProps">
                  {{slotProps.data.ispilihpanjar == true ? 'Dipilih' : 'Belum Dipilih' }}
              </template>
          </Column>

      </DataTable>
    </div>
    <div class="column is-6">
      <VField>
        <span>Total Panjar</span>
        <VControl icon="feather:edit-3">
          <VInput type="text" v-model="item.totalPanjar" placeholder=""
              class="is-rounded" disabled/>
        </VControl>
      </VField>
    </div>
    <div class="column is-3">
      <span><br></span>
      <VButton type="button" icon="feather:save" :loading="isSimpan" color="danger" raised class="is-rounded" outlined
          @click="SimpanPanjar()" > Simpan
      </VButton>
    </div>

    
  </div>
</Dialog>
</template>

<script setup lang="ts">
import Textarea from 'primevue/textarea';
import { ref, computed, reactive, watch } from 'vue'
import { useHead } from '@vueuse/head'
import Card from 'primevue/card';
import { useViewWrapper } from '/@src/stores/viewWrapper'
import Sidebar from 'primevue/sidebar';
import Dropdown from 'primevue/dropdown';
import MultiSelect from 'primevue/multiselect';
import { useRoute, useRouter } from 'vue-router'
import * as H from '/@src/utils/appHelper'
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import ProgressBar from 'primevue/progressbar';
import ColumnGroup from 'primevue/columngroup';   // optional
import Row from 'primevue/row';
import InputText from 'primevue/inputtext';
import { FilterMatchMode } from 'primevue/api'
import { useApi } from '/@src/composable/useApi'
import Panel from 'primevue/panel';
import Dialog from 'primevue/dialog';
import Badge from 'primevue/badge';
import { useWindowScroll } from '@vueuse/core'
import AutoComplete from 'primevue/autocomplete';
import Calendar from 'primevue/calendar';
import ConfirmDialog from 'primevue/confirmdialog';
import { useConfirm } from 'primevue/useconfirm';
import moment from 'moment';
import RadioButton from 'primevue/radiobutton';
const confirm = useConfirm();
useHead({
  title: 'SPJ - ' + import.meta.env.VITE_PROJECT,
})
const { y } = useWindowScroll()
const isStuck = computed(() => {
    return y.value > 30
})

useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)
const item: any = reactive(
  {filterTgl: reactive({
    start: moment(new Date()).format('01/01/YYYY'),
    end: new Date()
  }),
  totalPanjar: 0
  })
  
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
let listStatusSPJ: any = reactive([
    { id: 0, status: "Semua" },
    { id: 1, status: "Belum Verifikasi" },
    { id: 2, status: "Revisi" },
    { id: 3, status: "Sudah Verifikasi" },
])
let categories: any = reactive([
    {key: 1, name:'Transfer Bank'},
    {key: 2, name:'Panjar'},
])

let categoriess: any = reactive([
    {key: 3, name:'Sudah Diverifikasi'},
    {key: 1, name:'Belum Diverifikasi'},
    {key: 2, name:'Revisi'},
])
let dataSourceSPJ: any = ref([])
let dataSourceDetailSPJ: any = ref([])
let dataSourceAddPanjar: any =ref([])

let isLoadingTable: any = ref(false)
let isLoadingSearch: any = ref(false)
let isSimpan: any = ref(false)
let isDetailSPJ: any = ref(false)
let popupTambahSPJ: any = ref(false)
let popupTambahDetailSPJ: any = ref(false)
let popupVerifSPJ:any = ref(false)
let popUpPanjar: any = ref(false)
let listSubKegiatanSPJ: any = ref([])
let listSubSubKegiatanSPJ: any = ref([])
let listMataAnggaranSPJ: any = ref([])
let listAsalProduk: any = ref([])
let listItemRincian: any = ref([])
let d_Pegawai: any = ref([])
let d_Rekanan: any = ref([])
var norecverif: any = ref('')
var norecspjpanjar: any = ref('')
var norecspj: any = ref('')
let isSimpanVerif: any = ref(false)
let isApply: any = ref(false)
let isFilter: any = ref(false)
let isAddPanjar: any =ref(false)
let addPanjar: any = ref(false)
let totalSPJ: any = ref(0)
let dataPilihPanjar: any = ref([])
const router = useRouter()
const route = useRoute()
const fetchDokter = async (filter: any) => {
    await useApi().get(
        `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`
    ).then((response) => {
        d_Pegawai.value = response
    })
}

const fetchRekanan = async (filter: any) => {
    await useApi().get(
        `emr/dropdown/rekanan_m?select=id,namarekanan&param_search=namarekanan&query=${filter.query}&limit=10`
    ).then((response) => {
        d_Rekanan.value = response
    })
}
let columnDetailSPJ:any = ref([
    {
        "field": "nourut",
        "title": "No. Urut",
        "width": "100px"
    },
    {
        "field": "uraian",
        "title": "Keterangan",
        "width": "200px"
    },
    {
        "field": "jml",
        "title": "Jml",
        "width": "150px",
        "template": "<span class='style-right'>{{formatRupiah2('#: jml #', '')}}</span>",
    },
    {
        "field": "satuan",
        "title": "Satuan",
        "width": "100px"
    },
    {
        "field": "harga",
        "title": "Harga Satuan",
        "width": "200px",
    },
    {
        "field": "subtotal",
        "title": "Sub Total",
        "width": "200px",
    },
    {
        "field": "keteranganbelanja",
        "title": "Item Rincian",
        "width": "200px"
    }
])

watch(currentPage.value, () => {
  loadData()
})
loadCombo()
async function loadCombo() {
    await useApi().get(
        `anggaran/get-combo`
    ).then((response) => {
        // d_listTahapKegiatan.value = response.tahap
        // d_listJenisBelanja.value = response.jenisbelanja
        // d_listDiv.value = response.kelompokanggaran
        listAsalProduk.value = response.asalproduk
        // listSubKegiatanSPJ.value = response.kegiatancombo

    })

    await useApi().get('anggaran/get-data-setting-anggaran').then((response) => {
        listSubKegiatanSPJ.value = response.kegiatancombo
        item.tahap = response.data[0].objecttahapaktivfk
    })
}
async function TambahSPJ(){
    // norecspjpanjar = ''

    // if(idpegawailogin != ''){
    //     dispptk = true
    // } else{
    //     dispptk = false
    // }
    // dataSourceDetailSPJ = new kendo.data.DataSource({
    //     data: []
    // });
    // item.tglSPJ =  new Date(moment(now).format('YYYY-MM-DD'));

    // listSubKegiatanSPJ = [];
    // ListSubKegiatan = [];
    // for (let index = 0; index < DataKegiatan.length; index++) {
    //     const element =  DataKegiatan[index];
    //     if(element.div == 3){
    //         ListSubKegiatan.push({'id':element.id,'keterangan' :element.kode + ' ' + element.keterangan,'kode' :element.kode})
    //     }
    // }

    // medifirstService.get("perencanaan/get-data-combo").then(function (e) {
    //     listSubKegiatanSPJ = e.data.kegiatancombo
    // })

    // // listSubKegiatanSPJ = ListSubKegiatan
    // item.subKegiatanSPJ = ""
    // item.subSubKegiatanSPJ = ""
    // item.mataAnggaranSPJ = ""
    // item.sumberDanaSPJ =""
    // item.sisaAnggaranSPJ=0
    // item.panjar=0
    // item.dasarKontraktualSPJ = ""

    item.jumlahSPJ = 0
    item.pph =0
    item.ppn = 0
    norecverif.value = ''
    // item.bendaharaSPJ = ""
    // item.penerimaSPJ = ""
    // item.pihakKetigaSPJ = ""
    item.radioPencairan = 1
    // totalspjawal = item.jumlahSPJ
    // item.bendaharaSPJ = defaultspj

    // // medifirstService.get("perencanaan/get-data-combo?tahun="+moment(now).format('YYYY')).then(function (e) {
    // //     medifirstService.get("perencanaan/get-total-keterangan?tahap=" +e.data.setting[0].objecttahapaktivfk + "&subsubkegiatan=" + item.subSubKegiatanSPJ.id).then(function (e) {
    // //         totalketerangan = e.data.data[0].totalketerangan
    // //         totalrealisasidetail = e.data.detail[0].totalrealisasidetail
    // //         totalpanjar = e.data.panjar[0].panjar
    // //         item.sisaAnggaranSPJ = parseInt(totalketerangan) - parseInt(totalrealisasidetail) 
    // //         //- parseInt(totalpanjar)
    // //         //console.log(item.sisaAnggaranspj)
    // //     })
    // // })

    // item.hidespj = 2
    popupTambahSPJ.value = true
}

const changeSubsubKegiatan = async (filter: any) => {
    listSubSubKegiatanSPJ.value = item.subKegiatanSPJ.detail
}

const changeRekening = async (filter: any) => {
    listMataAnggaranSPJ.value = item.subSubKegiatanSPJ.detail
}
const changeCboPilihNoRekening = async(filter: any)=> {
    await useApi().get('perencanaan/get-total-mata-anggaran-keterangan?tahap='+ item.tahap + '&rekening='+item.mataAnggaranSPJ.kodemataanggaran+"&subsubkegiatan="+ item.subSubKegiatanSPJ.id).then((response)=>{
        item.sisaAnggaranSPJ = parseFloat(response.data[0].totalketerangan) - parseFloat(response.detail[0].totalrealisasidetail)
        item.sisaAnggaranSPJTxt = H.formatRp(item.sisaAnggaranSPJ,'Rp. ')
        listItemRincian.value = response.rincianbelanja
    })
}
async function TambahRincianBelanjaSPJ(){
    if( item.mataAnggaranSPJ ==  undefined){
        return
    }
    
                
    item.noUrutSPJ = dataSourceDetailSPJ.value.length + 1
    item.keteranganSPJ = ''
    item.jmlSPJ = ''
    item.satuanSPJ = ''
    item.hargaSatuanSPJ = ''
    item.subTotalSPJ = ''
    item.SPJstatusTambah = 'new'
    item.ShowPassword = false
    // DataKeteranganBelanja
    // let itemRincianList = [];
    // for (let index = 0; index < DataTabKeteranganBelanja.length; index++) {
    //     const element = DataTabKeteranganBelanja[index];
    //     if (element.kodemataanggaran == item.mataAnggaranSPJ.kodemataanggaran){
    //         itemRincianList.push(element)
    //     }
    // }

    // medifirstService.get("perencanaan/get-data-combo-item-rincian?kegiatan="+item.subSubKegiatanSPJ.id+"&mataanggaran="+item.mataAnggaranSPJ.kodemataanggaran).then(function (e) {
    //     listItemRincian = e.data.data
    //     // item.sisaanggaran = 
    //     //console.log(listItemRincian)
    // })
    popupTambahDetailSPJ.value = true
}
async function changeDetailRincian() {
    if(item.itemRincian.password != null){
        item.ShowPassword = true
        item.passwordbenar = item.itemRincian.password
    }
    await useApi().get('perencanaan/get-rincian-belanja?tahap='+ item.tahap + '&rekening='+item.mataAnggaranSPJ.kodemataanggaran+"&subsubkegiatan="+ item.subSubKegiatanSPJ.id+"&rincian="+ item.itemRincian.norec).then((response)=>{
        item.sisaDetail = parseFloat(response.data[0].totalketerangan) - parseFloat(response.detail[0].totalrealisasidetail)
        item.sisaDetailTxt = H.formatRp(item.sisaDetail,"Rp. ")
        if(dataSourceDetailSPJ.value != undefined){
            for (let i = 0; i < dataSourceDetailSPJ.value.length; i++) {
                const element = dataSourceDetailSPJ.value[i];
                item.sisaDetail -= element.subtotal
                item.sisaDetailTxt = H.formatRp(item.sisaDetail,"Rp. ")
            }
        }
    })
}
async function SimpanPopUpRincianBelanjaSPJ() {
    if(item.itemRincian == undefined){
        H.alert('error','Harap pilih Rincian!');
        return
    }
    if(item.ShowPassword == true && item.passwordrba != item.passwordbenar){
        H.alert('error','Password Salah!');
        return
    }
    if(item.subTotalSPJ > item.sisaAnggaranSPJ){
        H.alert('error','Subtotal yang diinput melebihi sisa anggaran');
        return
    }
    if(item.sisaDetail < item.subTotalSPJ){
      H.alert('error','Sub Total tidak boleh lebih dari sisa Anggaran Rincian')
      return
    }
    let dataAddSPJ = {
        nourut : item.noUrutSPJ,
        uraian : item.keteranganSPJ,
        jml : item.jmlSPJ,
        satuan : item.satuanSPJ,
        harga : item.hargaSatuanSPJ,
        subtotal : item.subTotalSPJ,
        keteranganbelanjafk : item.itemRincian.norec,
        keteranganbelanja :item.itemRincian.keteranganbelanja
    }
    dataSourceDetailSPJ.value.push(dataAddSPJ)
    let NewTotal = 0;
    for (let index = 0; index < dataSourceDetailSPJ.value.length; index++) {
        const element = dataSourceDetailSPJ.value[index];
        NewTotal +=parseFloat(element.subtotal)
    }
    item.jumlahSPJ = NewTotal
    item.jumlahtotalspjRP = H.formatRupiah(NewTotal, "Rp. ") 
    
    item.sisaAnggaranSPJ -= item.subTotalSPJ
    popupTambahDetailSPJ.value = false
    item.itemRincian = undefined
    item.sisaDetail = undefined
    item.sisaDetailTxt = H.formatRp(item.sisaDetail,"Rp. ")
}

watch(
    () => item.jmlSPJ,
    (newValue, oldValue) => {
       
        item.subTotalSPJ = parseFloat(item.jmlSPJ)*parseFloat(item.hargaSatuanSPJ)
        item.subTotalSPJTxt = H.formatRp(item.subTotalSPJ,'Rp. ')
    }
)

watch(
    () => item.hargaSatuanSPJ,
    (newValue, oldValue) => {
       
        item.subTotalSPJ = parseFloat(item.jmlSPJ)*parseFloat(item.hargaSatuanSPJ)
        item.subTotalSPJTxt = H.formatRp(item.subTotalSPJ,'Rp. ')

    }
)
loadData()
async function SimpanPopUpDetailSPJ() {
    if(item.status == 3){
        H.alert('error','Sudah Diverifikasi tidak bisa disimpan!')
        return
    }
    if(item.pihakKetigaSPJ == undefined){
        // isRouteLoading = false
        return
    }
    if(item.subSubKegiatanSPJ == undefined){
        // isRouteLoading = false
        return
    }
    if(item.mataAnggaranSPJ == undefined){
        // isRouteLoading = false
        return
    }
    if(item.bendaharaSPJ == undefined){
        // isRouteLoading = false
        return
    }
    if(item.penerimaSPJ == undefined){
        // isRouteLoading = false
        return
    }

var datasave =
    {
        norec :  norecverif.value,
        norealisasi: item.norealisasi,
        tglrealisasi:  moment(item.tglSPJ).format('YYYY-MM-DD'),
        status: null,
        totalbelanja:  item.jumlahSPJ,
        rekananfk:  item.pihakKetigaSPJ.value,
        deskripsi:  item.dasarKontraktualSPJ,
        objectkegiatanfk:  item.subSubKegiatanSPJ.id,
        objectmataanggaranfk:  item.mataAnggaranSPJ.kodemataanggaran,
        bendaharafk:  item.bendaharaSPJ.value,
        penerimafk:  item.penerimaSPJ.value,
        pph:  item.pph,
        ppn:  item.ppn,
        realisasidetail: dataSourceDetailSPJ.value,
        objectcarabayarfk: parseInt(item.radioPencairan),
        objectasalprodukfk: item.sumberDanaSPJ.id,
        // norecpanjar:  selectedData2
    }
    isSimpan.value = true
    const response = await useApi().post('perencanaan/save-realisasi-spj', datasave)
    popupTambahSPJ.value = false
    item.itemRincian = false
    dataSourceDetailSPJ.value = []
    norecverif.value = ''
    isSimpan.value = false
    loadData()
}
async function loadData() {
    isLoadingSearch.value = true
    var status = ''
    if(item.statusspj != undefined){
        status = item.statusspj.id
    }
    var qnama = ''
    if(item.qnama != undefined){
        qnama = '&filter='+item.qnama
    }
    item.qnama
    // var limit = '&limit='+currentPage.value.limit
      let limit: any = currentPage.value.limit
  let offset: any = route.query.page ? route.query.page : 1
  offset = (offset * limit) - limit

    await useApi().get('perencanaan/get-spj?status='+status + "&tglAwal="+ moment(item.filterTgl.start).format('YYYY-MM-DD')+ "&tglAkhir="+ moment(item.filterTgl.end).format('YYYY-MM-DD')+`&limit=${limit}&offset=${offset}`+qnama).then((response)=>{
        isLoadingSearch.value = false
        isApply.value = false
        isFilter.value = false
        dataSourceSPJ.value = response.data
        totalSPJ.value = response.total
        route.query.page = '1'
    })
}
const fetchData = async() => {
    isApply.value = true
    isFilter.value = true
    loadData()

}
async function editDetailSPJ(e) {
    isDetailSPJ.value = true
    var dataItem = e
    if(dataItem.objectbkufk != null){
        H.alert('error','Sudah dibuat BKU tidak bisa dirubah')
        return
    }
    dataSourceDetailSPJ.value = []
    norecspjpanjar.value = dataItem.norec
    norecspj.value = dataItem.norec
    norecverif.value = dataItem.norec
    item.norealisasi = dataItem.norealisasi
    item.status = dataItem.status

    item.tglSPJ =  new Date(moment(dataItem.tglrealisasi).format('YYYY-MM-DD'));
    let kegiatanArr = dataItem.kegiatan.split("~");
    let mataAnggaranArr = dataItem.mataanggaran.split("~");
    let kodeArr =kegiatanArr[0].split(" ");
    await useApi().get('anggaran/get-data-setting-anggaran').then((response) => {
        listSubKegiatanSPJ.value = response.kegiatancombo
        listSubKegiatanSPJ.value.forEach((element: any) => {
            if(element.kode == dataItem.kodesubkegiatan){
                item.subKegiatanSPJ = element
                listSubSubKegiatanSPJ.value = item.subKegiatanSPJ.detail
                listSubSubKegiatanSPJ.value.forEach((element2: any)=>{
                    if(element2.kode == kodeArr[0]){
                        item.subSubKegiatanSPJ = element2
                        listMataAnggaranSPJ.value = item.subSubKegiatanSPJ.detail
                        listMataAnggaranSPJ.value.forEach((element3: any)=>{
                            if(element3.kodemataanggaran == dataItem.kodemataanggaran){
                                item.mataAnggaranSPJ = element3
                                changeCboPilihNoRekening(item.mataAnggaranSPJ)
                            }
                        })
                        // element2.detail.for
                    }
                })
            }
            
        });
        
    })
    item.pph = dataItem.pph
    item.ppn = dataItem.ppn
    item.radioPencairan = dataItem.objectcarabayarfk
    item.dasarKontraktualSPJ = dataItem.deskripsi
    item.jumlahSPJ = 0
    if(dataItem.details != undefined){
        dataSourceDetailSPJ.value = dataItem.details
        console.log(dataSourceDetailSPJ.value);
        
        for (let i = 0; i < dataSourceDetailSPJ.value.length; i++) {
            const element = dataSourceDetailSPJ.value[i];
            console.log(parseFloat(element.subtotal));
            item.jumlahSPJ += parseFloat(element.subtotal)
        }
    }
    
    item.bendaharaSPJ = {
        value: dataItem.bendaharafk, label: dataItem.bendahara
    }
    item.penerimaSPJ = {
        value: dataItem.penerimafk, label: dataItem.penerima
    }
    item.pihakKetigaSPJ = {
        value: dataItem.rekananfk, label: dataItem.pihakketiga
    }
    item.sumberDanaSPJ = {id: dataItem.idasalproduk, asalproduk: dataItem.asalproduk}
    popupTambahSPJ.value = true
    isDetailSPJ.value = false

}
function deleteDetailRincianBelanjaSPJ(dataItem) {
    console.log(dataItem);
    if(item.mataAnggaranSPJ ==  undefined){
        return
    }

    if(dataItem.norec == undefined){
        for (var i = 0; i < dataSourceDetailSPJ.value.length; i++)
        if (dataSourceDetailSPJ.value[i].nourut === dataItem.nourut) {
            dataSourceDetailSPJ.value.splice(i, 1);
            item.jumlahSPJ -= parseInt(dataItem.subtotal)
            item.jumlahtotalspjRP -= parseInt(dataItem.subtotal)
        }

        // dataSourceDetailSPJ.value = dataSourceDetailSPJ.value
    } else{
        var datasave =
        {
            norec :  dataItem.norec,
        }

        confirm.require({
        message: 'Yakin ingin menghapus data ?',
        header: 'Konfirmasi Hapus Data',
        icon: 'pi pi-info-circle',
        acceptClass: 'p-button-danger',
        accept: () => {
            item.jumlahSPJ -= parseInt(dataItem.subtotal)
            useApi().post('perencanaan/delete-rincian-belanja',datasave).then( (response)=> {
                item.sisaAnggaranSPJ += parseInt(dataItem.subtotal)
                var tglawal = moment(item.tglawal).format('YYYY-MM-DD');
                var tglakhir = moment(item.tglakhir).format('YYYY-MM-DD');
            // loadData()
            var status = ''
            if(item.statusspj != undefined){
                status = item.statusspj.id
            }
            var qnama = ''
            if(item.qnama != undefined){
                qnama = '&filter='+item.qnama
            }
            item.qnama
            var limit = '&limit='+currentPage.value.limit
            useApi().get('perencanaan/get-spj?status='+status + "&tglAwal="+ moment(item.filterTgl.start).format('YYYY-MM-DD')+ "&tglAkhir="+ moment(item.filterTgl.end).format('YYYY-MM-DD')+limit+qnama).then((response)=>{
                isLoadingSearch.value = false
                dataSourceSPJ.value = response.data
                for (let i = 0; i < dataSourceSPJ.value.length; i++) {
                    const element = dataSourceSPJ.value[i];
                    if(element.norec == dataItem.strukrealisasifk){
                        if(element.details != undefined){
                            dataSourceDetailSPJ.value = element.details
                        }else{
                            dataSourceDetailSPJ.value = []
                        }
                        
                    }
                    
                }
                 useApi().get('perencanaan/get-total-mata-anggaran-keterangan?tahap='+ item.tahap + '&rekening='+item.mataAnggaranSPJ.kodemataanggaran+"&subsubkegiatan="+ item.subSubKegiatanSPJ.id).then((response)=>{
                    item.sisaAnggaranSPJ = parseFloat(response.data[0].totalketerangan) - parseFloat(response.detail[0].totalrealisasidetail)
                    item.sisaAnggaranSPJTxt = H.formatRp(item.sisaAnggaranSPJ,'Rp. ')
                    listItemRincian.value = response.rincianbelanja
                })

            })
            }, (error) => {

            })
        },
        reject: () => {
            loadData()
            isLoading.value = false
        },
        })
    }
}
async function verifSPJ(dataItem) {
    norecverif.value = dataItem.norec
    popupVerifSPJ.value = true
}
async function SimpanVerifikasiSPJ() {
    isSimpanVerif.value = true
    var objSave =
    {
        tglverif: moment(item.tglVerifSPJ).format('YYYY-MM-DD'),
        statusverif: item.radioVerifikasi,
        keterangan: item.keteranganVerif,
        carabayar: item.radioPencairanVerifikasi,
        jumlahdibayarverif: item.jumlahdibayarSPJ,
        norec: norecverif.value,
    }
    await useApi().post('perencanaan/save-verif-spj', objSave).then((response)=>{
        isSimpanVerif.value = false
        popupVerifSPJ.value = false
        loadData()
    })
}
async function batalVerifSPJ(dataItem) {
  norecverif.value = dataItem.norec
  var objSave =
  {
    norec: norecverif.value,
  }
  confirm.require({
  message: 'Yakin ingin Batal Verifikasi data ?',
  header: 'Konfirmasi Batal Verifikasi Data',
  icon: 'pi pi-info-circle',
  acceptClass: 'p-button-danger',
  accept: () => {
      useApi().post('perencanaan/batal-verif-spj',objSave).then( (response)=> {
      loadData()
                  
      }, (error) => {

      })
  },
  reject: () => {
      loadData()
      isLoading.value = false
  },
  })
        
}
function deleteDetailSPJ(dataItem) {
    if (dataItem.objectbkufk != null) {
        toastr.error('Sudah dibuat BKU tidak bisa dihapus')
        return
    }
    var objSave =
        {
            norec: dataItem.norec,
        }
        confirm.require({
  message: 'Yakin ingin Hapus SPJ ?',
  header: 'Konfirmasi Hapus SPJ',
  icon: 'pi pi-info-circle',
  acceptClass: 'p-button-danger',
  accept: () => {
      useApi().post('perencanaan/delete-spj',objSave).then( (response)=> {
      loadData()
                  
      }, (error) => {

      })
  },
  reject: () => {
      loadData()
      isLoading.value = false
  },
  })
    
}
async function CetakSPJ(dataItem) {
  var data = dataItem.totalbelanja.split(".");
  console.log(data[1]);
  var terbilang = ''
  if(data[1] == undefined){
    terbilang = H.terbilang(data[0]) + " Rupiah"
  }else{
    terbilang = H.terbilang(data[0]) + " Koma " + H.terbilang(data[1]) + " Rupiah"
  }
  H.printBlade("report/get-cetak-spj?norec="+ dataItem.norec + "&terbilang="+ terbilang  + "&sumberdana="+dataItem.idasalproduk, '_blank');
}

const tambahPanjar = async(dataItem)=>{
  isAddPanjar.value = true
  await useApi().get(`perencanaan/get-data-panjar-spj?objectmataanggaranfk=${dataItem.objectmataanggaranfk}&objectsubkegiatanfk=${dataItem.idsubkegiatan}&objectsubsubkegiatanfk=${dataItem.idsubsubkegiatan}&norec_stp=${dataItem.norec}`).then((response)=> {
    dataSourceAddPanjar.value = response.data
    dataPilihPanjar.value = []
    item.totalPanjar = 0
    for (let i = 0; i < dataSourceAddPanjar.value.length; i++) {
      const element = dataSourceAddPanjar.value[i];
      // element.ispilihpanjar = false
      element.objectspjfk = dataItem.norec
      element.jumlahspj = dataItem.totalbelanja
    }
    popUpPanjar.value = true
    isAddPanjar.value = false

  })
}
const pilihPanjar = async(dataItem)=> {
  if(dataPilihPanjar.value.length > 0 && dataItem.ispilihpanjar == false ){
    for (let i = 0; i < dataPilihPanjar.value.length; i++) {
      const element = dataPilihPanjar.value[i];
      if(element.norec != dataItem.norec){
        dataPilihPanjar.value.push(dataItem)
        item.totalPanjar += parseFloat(dataItem.jumlah) 
        dataItem.ispilihpanjar = true
        break;
      }
    }
  }else if(dataPilihPanjar.value.length == 0 && dataItem.ispilihpanjar == false){
    dataPilihPanjar.value.push(dataItem)
    item.totalPanjar += parseFloat(dataItem.jumlah) 
    dataItem.ispilihpanjar = true
  }
  console.log(dataPilihPanjar.value)
}
const SimpanPanjar = async()=>{
  isSimpan.value = true
  var objSave = {
    data: dataPilihPanjar.value,
  }
  await useApi().post('perencanaan/simpan-spj-panjar', objSave).then((response)=>{
    dataPilihPanjar.value = []
    item.totalPanjar = 0
    isSimpan.value = false
    popUpPanjar.value = false
  })
}
</script>
<style lang="scss">
@import '/@src/scss/abstracts/all';

@import '/@src/scss/components/forms-outer';

.fs-075 {
  font-size: 0.9rem;
}


.is-navbar {
  .form-layout {
    margin-top: 30px;
  }
}

.form-layout {
  // max-width: 740px;
  margin: 0 auto;

  &.is-separate {
    // max-width: 1040px;

    .form-outer {
      background: none;
      border: none;

      .form-body {
        display: flex;

        .form-section {
          flex-grow: 2;
          padding: 10px;
          width: 50%;

          .form-section-inner {
            @include vuero-s-card;

            padding: 40px;

            &.has-padding-bottom {
              padding-bottom: 60px;
              height: 100%;
            }

            >h3 {
              font-family: var(--font-alt);
              font-size: 1.2rem;
              font-weight: 600;
              color: var(--dark-text);
              margin-bottom: 30px;
            }

            .columns {
              .column {
                padding-top: 0.25rem;
                padding-bottom: 0.25rem;
              }
            }

            .radio-boxes {
              display: flex;
              justify-content: space-between;
              margin-left: -8px;
              margin-right: -8px;

              .radio-box {
                position: relative;
                width: calc(50% - 16px);
                margin: 8px;

                &:focus-within {
                  border-radius: 3px;
                  outline-offset: var(--accessibility-focus-outline-offset);
                  outline-width: var(--accessibility-focus-outline-width);
                  outline-style: var(--accessibility-focus-outline-style);
                  outline-color: var(--primary);
                }

                input {
                  position: absolute;
                  top: 0;
                  left: 0;
                  height: 100%;
                  width: 100%;
                  opacity: 0;
                  cursor: pointer;

                  &:checked {
                    +.radio-box-inner {
                      background: var(--primary);
                      border-color: var(--primary);
                      box-shadow: var(--primary-box-shadow);

                      .fee,
                      p {
                        color: var(--smoke-white);
                      }
                    }
                  }
                }

                .radio-box-inner {
                  background: var(--white);
                  border: 1px solid var(--fade-grey-dark-3);
                  text-align: center;
                  border-radius: var(--radius);
                  font-family: var(--font);
                  font-weight: 600;
                  font-size: 0.9rem;
                  transition: color 0.3s, background-color 0.3s, border-color 0.3s,
                    height 0.3s, width 0.3s;
                  padding: 30px 20px;

                  .fee {
                    font-family: var(--font);
                    font-weight: 700;
                    color: var(--dark-text);
                    font-size: 2.4rem;
                    line-height: 1;

                    span {
                      &::after {
                        content: '$';
                        position: relative;
                        top: -10px;
                        font-size: 1.5rem;
                      }
                    }
                  }

                  p {
                    font-family: var(--font-alt);
                  }
                }
              }
            }

            .control {
              >p {
                padding-top: 12px;

                >span {
                  display: block;
                  font-size: 0.9rem;

                  span {
                    font-weight: 500;
                    color: var(--dark-text);
                  }
                }
              }
            }
          }

          .form-section-outer {
            .checkboxes {
              padding: 16px 0;

              .checkbox {
                padding: 0;
                font-size: 0.9rem;
              }
            }

            .button-wrap {
              .button {
                min-height: 60px;
                font-size: 1.05rem;
                font-weight: 600;
                font-family: var(--font-alt);
              }
            }
          }
        }
      }
    }
  }
}

.is-dark {
  .form-layout {
    &.is-separate {
      .form-outer {
        background: none !important;

        .form-body {
          .form-section {
            .form-section-inner {
              @include vuero-card--dark;

              >h3 {
                color: var(--dark-dark-text);
              }

              .radio-boxes {
                .radio-box {
                  input:checked+.radio-box-inner {
                    background: var(--primary);
                    border-color: var(--primary);
                    box-shadow: var(--primary-box-shadow);

                    .fee,
                    p {
                      color: var(--smoke-white);
                    }
                  }

                  .radio-box-inner {
                    background: var(--dark-sidebar-light-2);
                    border-color: var(--dark-sidebar-light-12);

                    .fee {
                      color: var(--dark-dark-text);
                    }
                  }
                }
              }
            }
          }
        }
      }
    }
  }
}

@media only screen and (max-width: 767px) {
  .form-layout {
    &.is-separate {
      .form-outer {
        .form-body {
          padding-left: 0;
          padding-right: 0;
          flex-direction: column;

          .form-section {
            width: 100%;

            .form-section-inner {
              padding: 30px;
            }
          }
        }
      }
    }
  }
}

@media only screen and (min-width: 768px) and (max-width: 1024px) and (orientation: portrait) {
  .form-layout {
    &.is-separate {
      .form-outer {
        .form-body {
          padding-left: 0;
          padding-right: 0;

          // flex-direction: column;

          .form-section {
            // width: 100%;

            .form-section-inner {
              padding: 30px;
            }
          }
        }
      }
    }
  }
}

.all-projects {
  .all-projects-header {
    display: flex;
    padding: 20px;
    background: var(--white);
    border: 1px solid var(--fade-grey-dark-3);
    border-radius: var(--radius-large);
    margin-bottom: 1.5rem;

    .header-item {
      width: 25%;
      border-right: 1px solid var(--fade-grey-dark-3);

      &:last-child {
        border-right: none;
      }

      .item-inner {
        text-align: center;

        .lnil,
        .lnir {
          font-size: 2.2rem;
          margin-bottom: 6px;
          color: var(--primary);
        }

        span {
          display: block;
          font-family: var(--font);
          font-weight: 600;
          font-size: 1.4rem;
          color: var(--dark-text);
        }

        p {
          font-family: var(--font-alt);
        }
      }
    }
  }

  .projects-card-grid {
    .grid-item {
      display: flex;
      flex-direction: column;
      justify-content: space-between;
      min-height: 220px;
      padding: 20px;
      background: var(--white);
      border: 1px solid var(--fade-grey-dark-3);
      border-radius: var(--radius-large);

      .top-section {
        .head {
          display: flex;
          justify-content: space-between;
          align-items: center;
          margin-bottom: 8px;

          h3 {
            font-size: 1rem;
            font-family: var(--font-alt);
            color: var(--dark-text);
            font-weight: 600;
          }
        }

        .body {
          p {
            font-family: var(--font);
            color: var(--light-text);
          }
        }
      }

      .bottom-section {
        display: flex;

        .foot-block {
          margin-right: 30px;

          .heading {
            font-family: var(--font-alt);
            font-size: 0.75rem;
            color: var(--light-text-dark-22);
          }

          >p {
            padding-top: 5px;
          }

          .developers {
            display: flex;

            .v-avatar {
              margin-right: 6px;
            }
          }
        }
      }
    }
  }
}

.heading {
  font-family: var(--font-alt);
  font-size: 0.75rem;
  color: var(--light-text-dark-22);
}

.is-dark {
  .all-projects {
    .all-projects-header {
      background: var(--dark-sidebar-light-6);
      border-color: var(--dark-sidebar-light-12);

      .header-item {
        border-color: var(--dark-sidebar-light-18);

        span {
          color: var(--dark-dark-text);
        }

        i {
          color: var(--primary) !important;
        }
      }
    }

    .projects-card-grid {
      .grid-item {
        background: var(--dark-sidebar-light-6);
        border-color: var(--dark-sidebar-light-12);

        .top-section {
          .head {
            h3 {
              color: var(--dark-dark-text);
            }
          }
        }

        .bottom-section {
          .foot-block {
            .heading {
              color: var(--light-text-dark-12);
            }
          }
        }
      }
    }
  }
}
</style>