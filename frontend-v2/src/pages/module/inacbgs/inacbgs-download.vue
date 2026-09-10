<template>
    <section>
      <div>
          <div class="columns is-multiline mt-5">
              <div class="column is-3">
                  <VDatePicker v-model="item.qFilterTgl" is-range color="pink" trim-weeks>
                      <template #default="{ inputValue, inputEvents }">
                          <VField addons>
                              <VControl icon="feather:calendar">
                                  <VInput :value="inputValue.start" class="input-calendar" v-on="inputEvents.start" />
                              </VControl>
                              <VControl>
                                  <VButton static><i class="fas fa-arrow-right" aria-hidden="true"></i></VButton>
                              </VControl>
                              <VControl icon="feather:calendar">
                                  <VInput :value="inputValue.end" class="input-calendar" v-on="inputEvents.end" />
                              </VControl>
                          </VField>
                      </template>
                  </VDatePicker>
              </div>
              <div class="column is-6">
                  <!-- <VField>
                      <VControl icon="feather:search">
                          <input v-model="filters" v-on:keyup.enter="fetchData()" type="text" class="input is-rounded"
                          placeholder="Cari Nama Pasien, No RM, Atau No Registrasi" />
                      </VControl>
                  </VField> -->

                  <VField>
                    <VControl>
                        <VTextarea class="textarea is-rounded" v-on:keyup.enter="fetchData()" v-model="filters" rows="5"
                        autocomplete="off" autocapitalize="off" spellcheck="true" placeholder="Masukkan SEP"/>
                    </VControl>
                  </VField>
              </div>
              <div class="column is-3">
                  <VIconButton circle class="ml-2  is-pulled-" icon="fas fa-filter" raised bold @click="modalFilter = true"
                      v-tooltip.bubble="'Filter'">
                  </VIconButton>
                  <Badge :value="jmlFilter" v-if="jmlFilter > 0" severity="info" class="is-pulled-"
                      style="margin-left:-10px ;z-index: 100;  position: relative; "></Badge>
                  <VIconButton type="button" color="success" circle raised icon="fas fa-search" @click="fetchData()"
                      :loading="isLoading" class="ml-3 is-pulled-">
                  </VIconButton>
                  <VIconButton circle class="ml-2  is-pulled-" icon="fas fa-archive" raised bold @click="masterTarif()" style="display: none !important"
                      v-tooltip.bubble="'Master Produk'">
                  </VIconButton>
  
              </div>
              <div class="column is-12" style="margin-top: 0px">
                <div class="columns is-multiline">
                    <div class="column is-2">
                        <VField>
                            <VControl>
                                <VSwitchBlock v-model="item.isNotSEP" label="SEP belum diinput" color="danger"/>
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-2">
                        <VField>
                            <VControl>
                                <VSwitchBlock v-model="item.isNotDiagnosis" label="Belum diKoder" color="danger" />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-2">
                        <VField>
                            <VControl>
                                <VSwitchBlock v-model="item.isNotVerifikasi" label="Belum Verifikasi" color="danger" />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-2">
                        <VField>
                            <VControl>
                                <VSwitchBlock v-model="item.isCatatan" label="Diverifikasi Dengan Catatan" color="danger" />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-2">
                        <VField>
                            <VControl>
                                <VSwitchBlock v-model="item.isPenunjang" label="Pasien Penunjang" color="danger" />
                            </VControl>
                        </VField>
                    </div>
                </div>
              </div>
              <div class="column is-2" style="display: none !important">
                  <VButton color="primary" raised class="is-pulled-right" :loading="isSimpan" @click="newKlaim()"
                      v-tooltip.bubble="'Kirim Semua data'">
                      <span class="icon">
                          <i aria-hidden="true" class="fas fa-plus"></i>
                      </span>
                      <span>New Klaim</span>
                  </VButton>
              </div>
              <div class="column is-2" style="margin-top: 0px;" >
                  <VField style="display: none !important">
                      <VControl>
                          <VSwitchBlock v-model="item.ischeckedAll" label="Ceklis Semua" color="danger"
                              @change="changeSwitch(item.ischeckedAll)" />
                      </VControl>
                  </VField>
              </div>
              <div class="column is-6" style="margin-top: 0px"></div>
          </div>
          <div class="user-grid user-grid-v2">
              <div class="column is-12 pt-0" style="margin-top: -50px;">  
                  <TabView class="tabview-custom mt-3" :scrollable="true" @tab-click="klikTab($event)">
                      <TabPanel>
                          <template #header>
                              <span>Rawat Jalan</span>
                          </template>
                          <div class="column px-0">
                            <div class="columns is-multiline">
                                <div class="dataTable-info column is-10"><b>Menampilkan {{ currentPage.page }} ke {{ currentPage.limit }}
                                dari
                                {{ dataSource.total }} entri data</b>
                                </div>
                                <div class="column is-2" style="text-align: end; display: none !important">
                                    <VButton color="primary" @click="LoadExportExcel(activeTab)" outlined
                                        icon="fas fa-file-excel" :loading="isLoadingExport">Export To Excel
                                    </VButton>
                                </div>
                                <div class="column is-2" style="text-align: end; display: none !important">
                                    <VButton color="primary" @click="collectAll(activeTab)" outlined
                                        icon="fas fa-file-excel" :loading="isLoadingExport">Collect All
                                    </VButton>
                                </div>
                                <div class="column is-2" style="text-align: end; display: none !important">
                                    <VButton color="primary" @click="bundleAll(activeTab)" outlined
                                        icon="fas fa-file-excel" :loading="isLoadingExport">Bundle All
                                    </VButton>
                                </div>
                                <div class="column is-2" style="text-align: end;">
                                    <VButton color="primary" @click="downloadAll(activeTab)" outlined
                                        icon="fas fa-file-excel" :loading="isLoadingExport">Download All
                                    </VButton>
                                </div>
                            </div>
                            
                            
                              <DataTable :value="dataSourcefiltered" class="p-datatable-md"
                                  :loading="isLoading"
                                  :paginator="true"
                                  :rows="currentPage.rows"
                                  :rowsPerPageOptions="[10, 15, 25, 200, currentPage.rows]"
                                  scrollable
                                  v-model:selection="selectedPasien"
                                  :metaKeySelection="metaKey" 
                                  selectionMode="single"
                                  scrollHeight="1000px"
                                  v-model:expandedRows="expandedRows"
                                  :totalRecords="dataSourcefiltered.length"
                                  paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
                                  responsiveLayout="stack" breakpoint="960px" sortMode="multiple" style="background-color: white;"
                                  currentPageReportTemplate="Showing {first} to {last} of {totalRecords}" showGridlines>
  
                                  <template #empty> No customers found. </template>
                                  <template #loading>
                                  <img src="/images/other/loadingspin.gif" alt="Loading..." width="100"/>
                                  <p style="color:white">Loading data, please wait...</p>
                                  </template>
                                  <Column field="no" header="No" style="min-width: 100px"></Column>
                                  <Column field="nomor_rm" header="No RM" style="min-width: 100px"></Column>
                                  <Column field="noregistrasi" header="No Registrasi" style="min-width: 120px"></Column>
                                  <Column field="nama_pasien" header="Nama Pasien" style="min-width: 80px"></Column>
                                  <Column field="kelompokpasien" header="Tipe Pasien" style="min-width: 80px">
                                    <template #body="slotProps">
                                        {{ slotProps.data.kelompokpasien }} - {{ slotProps.data.namarekanan }}
                                    </template>
                                  </Column>
                                  <Column field="nomor_sep" header="No SEP" :sortable="true" style="min-width: 80px"></Column>
                                  <Column field="namaruangan" header="Nama Ruangan" style="min-width: 80px;"></Column>
                                  <Column header="Verifikasi (Auto Collect All)" style="min-width: 60px" align="center">
                                      <template #body="slotProps">
                                          <div class="columns">
                                              <div class="column">
                                                  <button v-if="slotProps.data.tglverifklaim != null" type="button" aria-hidden="false" class="button is-outlined is-raised is-primary" @click="verifikasi(slotProps.data)">
                                                  <span class="icon"><i aria-hidden="true" class="fas fa-check"></i></span>
                                                  </button>
                                                  <button v-if="slotProps.data.tglverifklaim == null" type="button" aria-hidden="false" class="button is-outlined is-raised is-danger" @click="verifikasi(slotProps.data)">
                                                  <span class="icon"><i aria-hidden="true" class="lnil lnil-cross-circle"></i></span>
                                                  </button>
                                              </div>
                                          </div>
                                      </template>
                                  </Column>
                                  <Column field="ketverifikasi" header="Catatan" style="min-width: 80px;"></Column>
                                  <Column field="" header="Download" :sortable="true" style="min-width: 60px">
                                      <template #body="slotProps">
                                          <div class="columns">
                                              <div class="column">
                                                  <button type="button" aria-hidden="false" class="button is-outlined is-raised is-info" @click="bundleDokDownload(slotProps.data)">
                                                  <span class="icon"><i aria-hidden="true" class="fas fa-download"></i></span>
                                                  </button>
                                              </div>
                                          </div>
                                      </template>
                                  </Column>
                                  <Column field="inacbg_status" header="Status" style="min-width: 60px;">
                                      <template #body="slotProps">
                                          <VTag class="ml-1 mb-1" v-tooltip="'Status'"
                                          v-if="slotProps.data.inacbg_status == null || slotProps.data.inacbg_status == 'delete_claim'" color="danger" label="Belum Grouping" rounded />
                                          <VTag class="ml-1 mb-1" v-tooltip="'Status'"
                                          v-else color="success" :label="slotProps.data.inacbg_status" rounded />
                                      </template>
                                  </Column>
                              </DataTable>
                          </div>
                      </TabPanel>
                      <TabPanel>
                          <template #header>
                              <span>Rawat Inap</span>
                          </template>
                          <div class="column px-0">
                              <DataTable :value="dataSourcefiltered" class="p-datatable-md"
                                  :loading="isLoading"
                                  :paginator="true"
                                  :rows="currentPage.rows"
                                  :rowsPerPageOptions="[10, 15, 25, currentPage.rows]"
                                  scrollable
                                  v-model:selection="selectedPasien"
                                  v-model:expandedRows="expandedRows"
                                  :metaKeySelection="metaKey" 
                                  selectionMode="single"
                                  scrollHeight="600px"
                                  :totalRecords="dataSourcefiltered.length"
                                  paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
                                  responsiveLayout="stack" breakpoint="960px" sortMode="multiple" style="background-color: white"
                                  currentPageReportTemplate="Showing {first} to {last} of {totalRecords}" showGridlines>
  
                                  <template #empty> No customers found. </template>
                                  <template #loading>
                                  <img src="/images/other/loadingspin.gif" alt="Loading..." width="100"/>
                                  <p style="color:white">Loading data, please wait...</p>
                                  </template>
                                  <Column field="no" header="No" style="min-width: 100px"></Column>
                                  <Column field="nomor_rm" header="No RM" style="min-width: 120px"></Column>
                                  <Column field="noregistrasi" header="No Registrasi" style="min-width: 120px"></Column>
                                  <Column field="nama_pasien" header="Nama Pasien" style="min-width: 80px"></Column>
                                  <Column field="kelompokpasien" header="Tipe Pasien" style="min-width: 80px">
                                    <template #body="slotProps">
                                        {{ slotProps.data.kelompokpasien }} - {{ slotProps.data.namarekanan }}
                                    </template>
                                  </Column>
                                  <Column field="nomor_sep" header="No SEP" :sortable="true" style="min-width: 80px"></Column>
                                  <Column field="namaruangan" header="Nama Ruangan" style="min-width: 80px;"></Column>
                                  <Column header="Verifikasi" style="min-width: 60px" align="center">
                                      <template #body="slotProps">
                                          <div class="columns">
                                              <div class="column">
                                                  <button v-if="slotProps.data.tglverifklaim != null" type="button" aria-hidden="false" class="button is-outlined is-raised is-primary" @click="verifikasi(slotProps.data)">
                                                  <span class="icon"><i aria-hidden="true" class="fas fa-check"></i></span>
                                                  </button>
                                                  <button v-if="slotProps.data.tglverifklaim == null" type="button" aria-hidden="false" class="button is-outlined is-raised is-danger" @click="verifikasi(slotProps.data)">
                                                  <span class="icon"><i aria-hidden="true" class="lnil lnil-cross-circle"></i></span>
                                                  </button>
                                              </div>
                                          </div>
                                      </template>
                                  </Column>
                                  <Column field="ketverifikasi" header="Catatan" style="min-width: 80px;"></Column>
                                  <Column field="" header="Download" :sortable="true" style="min-width: 60px">
                                      <template #body="slotProps">
                                          <div class="columns">
                                              <div class="column">
                                                  <button type="button" aria-hidden="false" class="button is-outlined is-raised is-info" @click="bundleDok(slotProps.data)">
                                                  <span class="icon"><i aria-hidden="true" class="fas fa-download"></i></span>
                                                  </button>
                                              </div>
                                          </div>
                                      </template>
                                  </Column>
                                  <Column field="inacbg_status" header="Status" style="min-width: 60px;">
                                      <template #body="slotProps">
                                          <VTag class="ml-1 mb-1" v-tooltip="'Status'"
                                          v-if="slotProps.data.inacbg_status == null || slotProps.data.inacbg_status == 'delete_claim'" color="danger" label="Belum Grouping" rounded />
                                          <VTag class="ml-1 mb-1" v-tooltip="'Status'"
                                          v-else color="success" :label="slotProps.data.inacbg_status" rounded />
                                      </template>
                                  </Column>
                              </DataTable>
                          </div>
                      </TabPanel>
                      <!-- <TabPanel>
                          <template #header>
                              <span>IGD</span>
                          </template>
                          <div class="column px-0">
                            <div class="columns is-multiline">
                                <div class="dataTable-info column is-10"><b>Menampilkan {{ currentPage.page }} ke {{ currentPage.limit }}
                                dari
                                {{ dataSource.total }} entri data</b>
                                </div>
                                <div class="column is-2" style="text-align: end;">
                                    <VButton color="primary" @click="LoadExportExcel(activeTab)" outlined
                                        icon="fas fa-file-excel" :loading="isLoadingExport">Export To Excel
                                    </VButton>
                                </div>
                            </div>

                              <DataTable :value="dataSourcefiltered" class="p-datatable-md"
                                  :loading="isLoading"
                                  :paginator="true"
                                  :rows="currentPage.rows"
                                  :rowsPerPageOptions="[10, 15, 25, currentPage.rows]"
                                  scrollable
                                  v-model:selection="selectedPasien"
                                  v-model:expandedRows="expandedRows"
                                  :metaKeySelection="metaKey" 
                                  selectionMode="single"
                                  scrollHeight="600px"
                                  :totalRecords="dataSourcefiltered.length"
                                  paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
                                  responsiveLayout="stack" breakpoint="960px" sortMode="multiple" style="background-color: white"
                                  currentPageReportTemplate="Showing {first} to {last} of {totalRecords}" showGridlines>
  
                                  <template #empty> No customers found. </template>
                                  <template #loading>
                                  <img src="/images/other/loadingspin.gif" alt="Loading..." width="100"/>
                                  <p style="color:white">Loading data, please wait...</p>
                                  </template>
                                  <Column field="no" header="No" style="min-width: 100px"></Column>
                                  <Column field="nomor_rm" header="No RM" style="min-width: 120px"></Column>
                                  <Column field="noregistrasi" header="No Registrasi" style="min-width: 120px"></Column>
                                  <Column field="nama_pasien" header="Nama Pasien" style="min-width: 80px"></Column>
                                  <Column field="kelompokpasien" header="Tipe Pasien" style="min-width: 80px">
                                    <template #body="slotProps">
                                        {{ slotProps.data.kelompokpasien }} - {{ slotProps.data.namarekanan }}
                                    </template>
                                  </Column>
                                  <Column field="nomor_sep" header="No SEP" :sortable="true" style="min-width: 80px"></Column>
                                  <Column field="namaruangan" header="Nama Ruangan" style="min-width: 80px;"></Column>
                                  <Column field="iskonsul" header="Konsul/Lab/Rad" style="min-width: 60px;">
                                      <template #body="slotProps">
                                          <VTag class="ml-1 mb-1" v-tooltip="'Pasien Konsultasi'"
                                          v-if="slotProps.data.iskonsul == true" color="success" label="K" rounded />
                                          <VTag class="ml-1 mb-1" v-tooltip="'Laboratorium'"
                                          v-if="slotProps.data.islab != false" color="info" :label="slotProps.data.islab" rounded />
                                          <VTag class="ml-1 mb-1" v-tooltip="'Radiologi'"
                                          v-if="slotProps.data.israd != false" color="warning" :label="slotProps.data.israd" rounded />
                                          <VTag class="ml-1 mb-1" v-tooltip="'Laboratorium'"
                                          v-if="slotProps.data.islabharih != false" color="info" label="L: Hari-H" rounded />
                                          <VTag class="ml-1 mb-1" v-tooltip="'Radiologi'"
                                          v-if="slotProps.data.isradharih != false" color="warning" label="R: Hari-H" rounded />
                                      </template>
                                  </Column>
                                  <Column field="ruangankonsul" header="Ruangan Konsul" style="min-width: 80px;"></Column>
                                  <Column header="Penunjang" style="min-width: 90px" align="center">
                                      <template #body="slotProps">
                                          <div class="columns">
                                              <div class="column">
                                                  <button v-if="slotProps.data.isPenunjangKhusus == true" type="button" aria-hidden="false" class="button is-outlined is-raised is-primary" @click="modalPenunjang(slotProps.data)">
                                                  <span class="icon"><i aria-hidden="true" class="fas fa-check"></i></span>
                                                  </button>
                                                  <button v-if="slotProps.data.isPenunjangKhusus == null" type="button" aria-hidden="false" class="button is-outlined is-raised is-danger">
                                                  <span class="icon"><i aria-hidden="true" class="lnil lnil-cross-circle"></i></span>
                                                  </button>
                                              </div>
                                          </div>
                                      </template>
                                  </Column>
                                  <Column header="Berkas Medical Report" :expander="true" :style="{ width: '50px' }"></Column>
                                  <Column header="Ringkasan Keluar" style="min-width: 100px" align="center">
                                      <template #body="slotProps">
                                          <div class="columns">
                                              <div class="column">
                                                  <button type="button" aria-hidden="false" class="button is-outlined is-raised is-primary" @click="ringkasankeluar(slotProps.data)" v-if="slotProps.data.emrpasienfkresume == true">
                                                  <span class="icon"><i aria-hidden="true" class="fas fa-check"></i></span>
                                                  </button>
                                                  <button type="button" aria-hidden="false" class="button is-outlined is-raised is-danger" @click="ringkasankeluar(slotProps.data)" v-if="slotProps.data.emrpasienfkresume == false">
                                                  <span class="icon"><i aria-hidden="true" class="lnil lnil-cross-circle"></i></span>
                                                  </button>
                                              </div>
                                          </div>
                                      </template>
                                  </Column>
                                  <Column header="Verifikasi" style="min-width: 60px" align="center">
                                      <template #body="slotProps">
                                          <div class="columns">
                                              <div class="column">
                                                  <button v-if="slotProps.data.tglverifklaim != null" type="button" aria-hidden="false" class="button is-outlined is-raised is-primary" @click="verifikasi(slotProps.data)">
                                                  <span class="icon"><i aria-hidden="true" class="fas fa-check"></i></span>
                                                  </button>
                                                  <button v-if="slotProps.data.tglverifklaim == null" type="button" aria-hidden="false" class="button is-outlined is-raised is-danger" @click="verifikasi(slotProps.data)">
                                                  <span class="icon"><i aria-hidden="true" class="lnil lnil-cross-circle"></i></span>
                                                  </button>
                                              </div>
                                          </div>
                                      </template>
                                  </Column>
                                  <Column field="ketverifikasi" header="Catatan" style="min-width: 80px;"></Column>
                                  <Column field="" header="Download" :sortable="true" style="min-width: 60px">
                                      <template #body="slotProps">
                                          <div class="columns">
                                              <div class="column">
                                                  <button type="button" aria-hidden="false" class="button is-outlined is-raised is-info" @click="bundleDok(slotProps.data)">
                                                  <span class="icon"><i aria-hidden="true" class="fas fa-download"></i></span>
                                                  </button>
                                              </div>
                                          </div>
                                      </template>
                                  </Column>
                                  <Column header="Detail" style="min-width: 60px" align="center">
                                      <template #body="slotProps">
                                          <div class="columns">
                                              <div class="column">
                                                  <button type="button" aria-hidden="false" class="button is-outlined is-raised is-primary" @click="detail(slotProps.data)">
                                                  <span class="icon"><i aria-hidden="true" class="fas fa-long-arrow-alt-right"></i></span>
                                                  </button>
                                              </div>
                                          </div>
                                      </template>
                                  </Column>
                                  <Column field="inacbg_status" header="Status" style="min-width: 60px;">
                                      <template #body="slotProps">
                                          <VTag class="ml-1 mb-1" v-tooltip="'Status'"
                                          v-if="slotProps.data.inacbg_status == null || slotProps.data.inacbg_status == 'delete_claim'" color="danger" label="Belum Grouping" rounded />
                                          <VTag class="ml-1 mb-1" v-tooltip="'Status'"
                                          v-else color="success" :label="slotProps.data.inacbg_status" rounded />
                                      </template>
                                  </Column>
                                  <template #expansion="slotProps">
                                          <div class="orders-subtable">
                                              <table class="tg table-tg tabels" width="100%">
                                                  <thead style="border-bottom: 1px solid #c7c8c9">
                                                      <tr>
                                                          <td class="tg-0lax text-center" width="10%">Assesmen Medis</td>
                                                          <td class="tg-0lax text-center" width="10%">
                                                              <span v-if="slotProps.data.emrpasienfkasmed == false" style="color: red;">
                                                                  Tidak Ada Data
                                                              </span>
                                                              <span v-if="slotProps.data.emrpasienfkasmed == true" style="color: blue;">
                                                                  Ada Data
                                                              </span>
                                                          </td>
                                                          <td class="tg-0lax text-center" width="10%">Assesmen Keperawatan</td>
                                                          <td class="tg-0lax text-center" width="10%">
                                                              <span v-if="slotProps.data.emrpasienfkaskep == false" style="color: red;">
                                                                  Tidak Ada Data
                                                              </span>
                                                              <span v-if="slotProps.data.emrpasienfkaskep == true" style="color: blue;">
                                                                  Ada Data
                                                              </span>
                                                          </td>
                                                          <td class="tg-0lax text-center" width="10%">CPPT Dokter</td>
                                                          <td class="tg-0lax text-center" width="10%">
                                                              <span v-if="slotProps.data.emrpasienfkcpptdokter == false" style="color: red;">
                                                                  Tidak Ada Data
                                                              </span>
                                                              <button type="button" aria-hidden="false" class="button is-outlined is-raised is-primary" @click="cpptdokter(slotProps.data)" v-if="slotProps.data.emrpasienfkcpptdokter == true">
                                                                  <span style="color: blue;">Ada Data</span>
                                                              </button>
                                                          </td>
                                                          <td class="tg-0lax text-center" width="10%">CPPT Perawat</td>
                                                          <td class="tg-0lax text-center" width="10%">
                                                              <span v-if="slotProps.data.emrpasienfkcpptperawat == false" style="color: red;">
                                                                  Tidak Ada Data
                                                              </span>
                                                              <button type="button" aria-hidden="false" class="button is-outlined is-raised is-primary" @click="cpptdokter(slotProps.data)" v-if="slotProps.data.emrpasienfkcpptperawat == true">
                                                                  <span style="color: blue;">Ada Data</span>
                                                              </button>
                                                          </td>
                                                      </tr>
                                                  </thead>
                                              </table>
                                          </div>
                                      </template>
                              </DataTable>
                          </div>
                      </TabPanel> -->
                      
                            
                  </TabView>
              </div>
  
              
  
              <!-- ini card -->
  
              <TransitionGroup name="list" tag="div" class="columns is-multiline" style="display: none !important">
  
                  <div v-for="item in dataSourcefiltered" :key="item.norec" class="column is-3">
                      <div class="grid-item-wrap is-clickable">
                        <div class="mt-2">
                          <span class="ml-4">  {{ item.namaruangan }}</span>
                          </div>
                          <label class="h-toggle mt-5-min">
                              <input type="checkbox" :checked="item.checked" v-model="item.checked" />
                              <span class="toggler">
                                  <span class="active">
                                      <i aria-hidden="true" class="iconify" data-icon="feather:check"></i>
                                  </span>
                                  <span class="inactive">
                                      <i aria-hidden="true" class="iconify" data-icon="feather:x"></i>
                                  </span>
                              </span>
                          </label>
                          <div :class="'grid-item-head ' + (
                              item.inacbg_status != null ? 'is-registrasi-ina' : ''
                          )" :style="[ item.inacbg_status != null ?'background: var(--green)':'','padding: 10px 20px 5px 20px']">
  
                              <div class="flex-head">
  
                                  <div class="meta">
                                      <span v-if="item.inacbg_status != null && item.inacbg_status != 'delete_claim'"
                                          class="dark-inverted">
                                          {{ item.inacbg_status }}
                                      </span>
  
                                      <span v-if="item.inacbg_status == null || item.inacbg_status == 'delete_claim'"
                                          class="dark-inverted">
                                          Belum Kirim
                                      </span>
                                      <span :style="item.inacbg_status != null ? 'color:black':''">
                                          {{
                                              // item.inacbg_status == null
                                              // ? 'No. SEP ' + (item.nomor_sep ? item.nomor_sep : '-')
                                              // :
                                              H.formatDateIndoSimple(item.tgl_masuk)
                                          }}
                                      </span>
                                      <VTag :label="item.icd10 == false ? 'ICD 10 belum di input' : item.icd10"
                                          :color="item.icd10 == false ? 'warning' : 'info'"></VTag>
                                      <VTag :label="item.icd9" v-if="item.icd9 != false" class="ml-1" :color="'purple'">
                                      </VTag>
  
                                  </div>
                                  <div v-if="item.inacbg_status != null" class="status-icon" style="background-color: white;"
                                      v-tooltip.bubble="'Sudah terklaim'" >
                                      <i aria-hidden="true" class="fas fa-check" style="color:var(--success)"></i>
                                  </div>
                                  <div v-if="item.inacbg_status == null" class="status-icon is-danger"
                                      v-tooltip.bubble="'Belum terklaim'">
                                      <i aria-hidden="true" class="fas fa-times"></i>
                                  </div>
                              </div>
  
                              <div class="buttons">
                                <!-- v-if="item.inacbg_totalgrouper == null"  -->
                                  <VButton raised class="is-pulled-right" :loading="item.isSimpanHitung"  v-if="item.inacbg_status == null || item.inacbg_status == 'delete_claim'"
                                   @click="hitungBiayaSementara(item)"
                                      v-tooltip.bubble="'Hitung Biaya Sementara'">
                                      <span class="icon">
                                          <i class="fas fa-calculator" aria-hidden="true"></i>
                                      </span>
                                      <span>Hitung</span>
                                  </VButton>
                                  <!-- <p style="padding-bottom:0" v-if="item.inacbg_totalgrouper != null">
                                      <span>Klaim :</span>
                                      <b>{{
                                          H.formatRp(item.inacbg_totalgrouper, 'Rp.') }}</b>
                                  </p> -->
                                  <VDropdown icon="feather:more-vertical" spaced right class="is-pulled-right">
                                      <template #content>
                                          <a role="menuitem" class="dropdown-item is-media" @click="emr(item)">
                                              <div class="icon">
                                                  <i class="fas fa-stethoscope" aria-hidden="true"></i>
                                              </div>
                                              <div class="meta">
                                                  <span>EMR</span>
                                              </div>
                                          </a>
                                          <a role="menuitem" class="dropdown-item is-media" @click="detail(item)">
                                              <div class="icon">
                                                  <i class="iconify lnir lnir-calculator" aria-hidden="true"></i>
                                              </div>
                                              <div class="meta">
                                                  <span>Detail</span>
                                              </div>
                                          </a>
                                          <a role="menuitem" class="dropdown-item is-media" @click="finalClaim(item)">
                                              <div class="icon">
                                                  <i class="iconify lnir lnir-users" aria-hidden="true"></i>
                                              </div>
                                              <div class="meta">
                                                  <span>Final Klaim</span>
                                              </div>
                                          </a>
  
                                          <a role="menuitem" class="dropdown-item is-media"
                                              @click="kirimClaimDataCenter(item)">
                                              <div class="icon">
                                                  <i class="iconify lnir lnir-calender-alt-2" aria-hidden="true"></i>
                                              </div>
                                              <div class="meta">
                                                  <span>Kirim Klaim ke Data Center</span>
                                              </div>
                                          </a>
                                          <a role="menuitem" class="dropdown-item is-media" @click="claim_print(item)">
                                              <div class="icon">
                                                  <i class="iconify fas fa-print" aria-hidden="true"></i>
                                              </div>
                                              <div class="meta">
                                                  <span>Cetak</span>
                                              </div>
                                          </a>
                                          <a role="menuitem" class="dropdown-item is-media" @click="hapusKlaim(item)">
                                              <div class="icon">
                                                  <i class="iconify fas fa-trash" aria-hidden="true"></i>
                                              </div>
                                              <div class="meta">
                                                  <span>Hapus Klaim</span>
                                              </div>
                                          </a>
                                          <a role="menuitem" class="dropdown-item is-media" @click="detailTarif(item)">
                                              <div class="icon">
                                                  <i class="lnir lnir-calculator-alt" aria-hidden="true"></i>
                                              </div>
                                              <div class="meta">
                                                  <span>Detail 18 Variable</span>
                                              </div>
                                          </a>
                                          <a role="menuitem" class="dropdown-item is-media" @click="billing(item)">
                                              <div class="icon">
                                                  <i class="lnir lnir-dollar" aria-hidden="true"></i>
                                              </div>
                                              <div class="meta">
                                                  <span>Billing</span>
                                              </div>
                                          </a>
                                          <a role="menuitem" class="dropdown-item is-media" @click="resumeMedis(item)">
                                              <div class="icon">
                                                  <i class="fas fa-file" aria-hidden="true"></i>
                                              </div>
                                              <div class="meta">
                                                  <span>Resume Medis</span>
                                              </div>
                                          </a>
                                      </template>
                                  </VDropdown>
                              </div>
  
                          </div>
                          <div class="grid-item">
  
                              <!-- <VAvatar :picture="(item.foto != null ? item.foto : '/images/other/no_image.jpg')" :badge="(item.gender == 1 ? '/images/other/male.png'
                                  : '/images/other/female.png')" size="big" /> -->
                              <h3 class="dark-inverted mt-0">{{ item.nama_pasien }}</h3>
                              <p>{{ item.nomor_rm }}</p>
                              <p>Pasien {{ item.statuspasien }}</p>
                              <div class="people">
                                  <!-- <VSnack
                                      :title="item.nomor_sep != null && item.nomor_sep.length == 19 ? item.nomor_sep : 'No. SEP Belum di isi'"
                                      :color="item.nomor_sep != null && item.nomor_sep.length == 19 ? 'info' : 'danger'"
                                      :icon="'fas fa-credit-card'">
                                      <i class="iconify" data-icon="feather:plus"></i>
                                  </VSnack> -->
                                  <VSnack @click="popSEP(item)"
                                      :title="item.nomor_sep != null ? item.nomor_sep : 'No. SEP Belum di isi'"
                                      :color="item.nomor_sep != null ? 'info' : 'danger'"
                                      :icon="'fas fa-credit-card'">
                                      <i class="iconify" data-icon="feather:plus"></i>
                                  </VSnack>
                              </div>
                              <table class="w-100 mt-5-min mb-2" style="font-size: 0.85rem;" :class="item.color_plafon" v-bind:class="{ 'highlight-gold': item.totalbilling <= 50000}">
                                <tr>
                                  <td><b>BILLING</b></td>
                                  <td>:</td>
                                  <td style="text-align:right;  padding-right: 10px;"> <b>{{   H.formatRp(item.totalbilling, 'Rp.') }}</b></td>
                                </tr>
                                <tr>
                                  <td><b>PLAFON</b></td>
                                  <td>:</td>
                                  <td style="text-align:right;  padding-right: 10px;"> <b>{{   H.formatRp( item.inacbg_totalgrouper? (parseFloat(item.inacbg_totalgrouper) + (item.inacbg_topup ? parseFloat(item.inacbg_topup):0)):0, 'Rp.') }}</b></td>
                                </tr>
  
                              </table>
                              <!-- <p style="margin-top:-20px;margin-bottom:10px"> <span>Billing :</span> <b>{{
                                  H.formatRp(item.totalbilling, 'Rp.') }}</b></p>
                              <p style="margin-top:-10px;margin-bottom:10px"> <span>Plafon :</span> <b>{{
                                  H.formatRp(item.inacbg_totalgrouper, 'Rp.') }}</b></p> -->
  
                              <div class="buttons">
                                  <VButton raised class="is-pulled-right" :loading="isSimpanGrid" @click="grouper(item)"
                                      v-tooltip.bubble="'Grouper'">
                                      <span class="icon">
                                          <i class="fas fa-stethoscope" aria-hidden="true"></i>
                                      </span>
                                      <span>Grouping</span>
                                  </VButton>
                                  <VButton raised class="is-pulled-right" :loading="item.loading" @click="claim_print(item)"
                                      v-tooltip.bubble="'Print Claim'">
                                      <span class="icon">
                                          <i class="fas fa-print" aria-hidden="true"></i>
                                      </span>
                                      <span>Cetak</span>
                                  </VButton>
                              </div>
                              <div class="is-divider mt-0 mb-5" data-content="Dokumen Klaim" @click="popUpDok(item)"></div>
                              <div class="columns mt-5" style="width:100%;overflow:auto;z-index:1;height: 45px;"
                                  @click="popUpDok(item)">
                                  <VTag color="orange" label="Bundle" curved class="mr-1" />
                                  <VTag color="blue" :label="itemdoc.name" curved class="mr-1"
                                      :class="itemdoc.doc != null ? '' : 'is-outlined'" v-for="itemdoc in item.dokumen"
                                      :key="itemdoc.kodeexternal" />
                                  <!-- <VTag color="blue" label="CPPT" curved outlined class="mr-1" />
                                  <VTag color="blue" label="SEP" curved outlined class="mr-1" />
                                  <VTag color="blue" label="Billing" curved outlined class="mr-1" />
                                  <VTag color="blue" label="Resep Obat" curved outlined class="mr-1" />
                                  <VTag color="blue" label="Hasil lab" curved outlined class="mr-1" />
                                  <VTag color="blue" label="Hasil radiologi" curved outlined class="mr-1" />
                                  <VTag color="blue" label="Lembar Konsul Dokter " curved outlined class="mr-1" />
                                  <VTag color="blue" label="Laporan Operasi " curved outlined class="mr-1" /> -->
                              </div>
  
                          </div>
                      </div>
                  </div>
  
              </TransitionGroup>
  
              <div class="is-pulled-bottoms">
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
                                  <option :value="20">20 results per page</option>
                                  <option :value="25">25 results per page</option>
                                  <option :value="50">50 results per page</option>
                                  <option :value="100">100 results per page</option>
                                  <option :value="5000">All</option>
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
      <Dialog v-model:visible="modalKlaim" modal header="Koding" :style="{ width: '100rem' }"
      :breakpoints="{ '1199px': '75vw', '575px': '90vw' }" maximizable>
        <InaCbgDetail v-if="modalKlaim" :norec_pd="route.query.norec_pd" :nocmfk="route.query.nocm_fk"></InaCbgDetail>
      </Dialog>
      <Dialog v-model:visible="modalFilter" modal header="Filter" :style="{ width: '40vw' }">
          <div class="columns is-multiline">
              <!-- <div class="column is-6">
                  <VField label="No RM">
                      <VControl icon="feather:search">
                          <input v-model="item.qnocm" type="text" class="input is-rounded" placeholder="No RM" />
                      </VControl>
                  </VField>
              </div>
              <div class="column is-6">
                  <VField label="Nama Pasien">
                      <VControl icon="feather:search">
                          <input v-model="item.qnama" type="text" class="input is-rounded" placeholder="Nama Pasien" />
                      </VControl>
                  </VField>
              </div> -->
              <div class="column is-6">
                  <VField label="Jenis Pembiayaan" class="is-rounded-select is-autocomplete-select
                                mt-0 pt-0" v-slot="{ id }">
                      <VControl icon="fas fa-users" fullwidth class="prime-auto-select">
                          <MultiSelect v-model="item.qKelompok" display="chip" :options="d_KelompokPasien"
                              optionLabel="kelompokpasien" placeholder="Jenis Pembiayaan" optionValue="id"
                              class="is-rounded w-100" :maxSelectedLabels="3" />
  
                      </VControl>
                  </VField>
              </div>
              <div class="column is-6">
                  <VField label="Instalasi" class="is-rounded-select is-autocomplete-select
                                mt-0 pt-0" v-slot="{ id }">
                      <VControl icon="fas fa-archway" fullwidth class="prime-auto-select">
                          <Dropdown v-model="item.qInstalasi" :options="d_Departemen" :optionLabel="'namadepartemen'"
                              class="is-rounded" placeholder="Instalasi" style="width: 100%;" :filter="true"
                              @change="changeInst($event)" showClear />
                      </VControl>
                  </VField>
              </div>
              <div class="column is-6">
                  <VField label="Ruangan" class="is-rounded-select is-autocomplete-select
                                mt-0 pt-0" v-slot="{ id }">
                      <VControl icon="fas fa-home" fullwidth class="prime-auto-select">
                          <Dropdown v-model="item.qRuangan" :options="d_Ruangan" :optionLabel="'namaruangan'"
                              class="is-rounded" placeholder="Ruangan" style="width: 100%;" :filter="true" showClear />
                      </VControl>
                  </VField>
              </div>
              <div class="column is-6">
                  <VField label="Status Klaim" class="is-rounded-select is-autocomplete-select
                                mt-0 pt-0" v-slot="{ id }">
                      <VControl icon="fas fa-credit-card" fullwidth class="prime-auto-select">
                          <Dropdown v-model="item.qStatus" :options="d_Status" :optionLabel="'status'" class="is-rounded"
                              placeholder="Status Klaim" style="width: 100%;" :filter="true" showClear />
                      </VControl>
                  </VField>
              </div>
              <div class="column is-6">
                  <VField label="Status Pasien" class="is-rounded-select is-autocomplete-select
                                mt-0 pt-0" v-slot="{ id }">
                      <VControl icon="fas fa-credit-card" fullwidth class="prime-auto-select">
                          <Dropdown v-model="item.qStatusPasien" :options="d_StatusPasien" :optionLabel="'status'" class="is-rounded"
                              placeholder="Status Pasien" style="width: 100%;" :filter="true" showClear />
                      </VControl>
                  </VField>
              </div>
          </div>
          <template #footer>
              <VButton icon="lnir lnir-arrow-left rem-100" light dark-outlined @click="clearFilter()">
                  Bersihkan
              </VButton>
              <VButton type="button" rounded outlined color="primary" raised icon="feather:filter" :loading="isLoading"
                  @click="terapkanFilter()"> Terapkan
              </VButton>
          </template>
      </Dialog>
      <Dialog v-model:visible="modalDetail" modal header="Detail 18 Variable" :style="{ width: '70vw' }">
          <div class="columns is-multiline">
              <div class="column is-12">
                  <div>
  
                      <VCard class="mt-2">
                          <div class="columns is-multiline">
                              <div class="column is-4">
                                  <listWidgetCustom title="Prosedur Non Bedah"
                                      :color="tarif18.prosedur_non_bedah > 0 ? 'success' : 'warning'"
                                      :subtitle="H.formatRp(tarif18.prosedur_non_bedah, 'Rp.')" />
                                  <listWidgetCustom title="Tenaga Ahli"
                                      :color="tarif18.tenaga_ahli > 0 ? 'success' : 'warning'"
                                      :subtitle="H.formatRp(tarif18.tenaga_ahli, 'Rp.')" class="mt-1" />
                                  <listWidgetCustom title="Radiologi" :color="tarif18.tenaga_ahli > 0 ? 'success' : 'warning'"
                                      :subtitle="H.formatRp(tarif18.radiologi, 'Rp.')" class="mt-1" />
                                  <listWidgetCustom title="Rehabilitasi"
                                      :color="tarif18.rehabilitasi > 0 ? 'success' : 'warning'"
                                      :subtitle="H.formatRp(tarif18.rehabilitasi, 'Rp.')" class="mt-1" />
                                  <listWidgetCustom title="Obat" :color="tarif18.obat > 0 ? 'success' : 'warning'"
                                      :subtitle="H.formatRp(tarif18.obat, 'Rp.')" class="mt-1" />
                                  <listWidgetCustom title="Alkes" :color="tarif18.alkes > 0 ? 'success' : 'warning'"
                                      :subtitle="H.formatRp(tarif18.alkes, 'Rp.')" class="mt-1" />
                              </div>
                              <div class="column is-4">
                                  <listWidgetCustom title="Prosedur Bedah"
                                      :color="tarif18.prosedur_non_bedah > 0 ? 'success' : 'warning'"
                                      :subtitle="H.formatRp(tarif18.prosedur_non_bedah, 'Rp.')" />
                                  <listWidgetCustom title="Keperawatan"
                                      :color="tarif18.keperawatan > 0 ? 'success' : 'warning'"
                                      :subtitle="H.formatRp(tarif18.keperawatan, 'Rp.')" class="mt-1" />
                                  <listWidgetCustom title="Laboratorium"
                                      :color="tarif18.laboratorium > 0 ? 'success' : 'warning'"
                                      :subtitle="H.formatRp(tarif18.laboratorium, 'Rp.')" class="mt-1" />
                                  <listWidgetCustom title="Kamar / Akomodasi"
                                      :color="tarif18.kamar > 0 ? 'success' : 'warning'"
                                      :subtitle="H.formatRp(tarif18.kamar, 'Rp.')" class="mt-1" />
                                  <listWidgetCustom title="Obat Kronis"
                                      :color="tarif18.obat_kronis > 0 ? 'success' : 'warning'"
                                      :subtitle="H.formatRp(tarif18.obat_kronis, 'Rp.')" class="mt-1" />
                                  <listWidgetCustom title="BMHP" :color="tarif18.bmhp > 0 ? 'success' : 'warning'"
                                      :subtitle="H.formatRp(tarif18.bmhp, 'Rp.')" class="mt-1" />
                              </div>
                              <div class="column is-4">
                                  <listWidgetCustom title="Konsultasi" :color="tarif18.konsultasi > 0 ? 'success' : 'warning'"
                                      :subtitle="H.formatRp(tarif18.konsultasi, 'Rp.')" />
                                  <listWidgetCustom title="Penunjang" :color="tarif18.penunjang > 0 ? 'success' : 'warning'"
                                      :subtitle="H.formatRp(tarif18.penunjang, 'Rp.')" class="mt-1" />
                                  <listWidgetCustom title="Pelayanan Darah"
                                      :color="tarif18.pelayanan_darah > 0 ? 'success' : 'warning'"
                                      :subtitle="H.formatRp(tarif18.pelayanan_darah, 'Rp.')" class="mt-1" />
                                  <listWidgetCustom title="Rawat Intensif"
                                      :color="tarif18.rawat_intensif > 0 ? 'success' : 'warning'"
                                      :subtitle="H.formatRp(tarif18.rawat_intensif, 'Rp.')" class="mt-1" />
                                  <listWidgetCustom title="Obat Kemoterapi"
                                      :color="tarif18.obat_kemoterapi > 0 ? 'success' : 'warning'"
                                      :subtitle="H.formatRp(tarif18.obat_kemoterapi, 'Rp.')" class="mt-1" />
                                  <listWidgetCustom title="Sewa Alat" :color="tarif18.sewa_alat > 0 ? 'success' : 'warning'"
                                      :subtitle="H.formatRp(tarif18.sewa_alat, 'Rp.')" class="mt-1" />
                              </div>
                          </div>
                      </VCard>
  
                      <Fieldset legend="Tarif Belum Dimapping" :toggleable="true"
                          :collapsed="tarif18.belum_mapping.length > 0 ? false : true" class="mt-2">
                          <div class="columns is-multiline">
                              <div class="column is-3" v-for="item in tarif18.belum_mapping" :key="item.norec">
                                  <TStatusPojokKanan :title="item.namaproduk" :subtitle="H.formatRp(item.ttl, 'Rp.')"
                                      class="inbox-widget-2" />
                              </div>
                          </div>
  
                      </Fieldset>
  
                      <VCard class="mt-2">
                          <div class="columns is-multiline">
                              <div class="column is-6">
                                  <b>Tarif Rumah Sakit INACBG's : {{ H.formatRp(tarif18.totalmappingtarif, 'Rp. ') }}</b>
                              </div>
                              <div class="column is-6">
                                  <b>Total Billing : {{ H.formatRp(tarif18.totalbilling, 'Rp. ') }}</b>
                              </div>
                          </div>
                      </VCard>
  
                  </div>
              </div>
          </div>
      </Dialog>
      <Dialog v-model:visible="modalDokumen" modal header="Monitoring Dokumen Klaim" :style="{ width: '70vw' }"
          :maximizable="true">
          <ProgressBar mode="indeterminate" v-if="loadingUpload" style="height: 6px"></ProgressBar>
  
          <div class="columns is-multiline">
              <div class="column is-12">
                  <div class="columns is-multiline">
                      <div class="column is-12">
                          <VCardHead title="Bundle" class="p-2 text-center">
                              <div class="columns is-multiline">
                                  <div class="column is-2">
                                      <VButton
                                          @click="bundleDok(listDokumen.dokumen.length > 0 ? listDokumen.dokumen[0].noregistrasi : '')"
                                          color="danger" raised outlined rounded icon="fas fa-file-pdf" class="w-100">
                                          Lihat
                                      </VButton>
                                  </div>
                                  <div class="column is-10 mt-0 pt-0">
                                      <span>Status kelengkapan dokumen {{ listDokumen.persen_dok.toFixed(2) }} %</span>
                                      <VProgress
                                          :color="listDokumen.persen_dok < 33 ? 'danger' : (listDokumen.persen_dok >= 33 && listDokumen.persen_dok < 70 ? 'warning' : 'success')"
                                          size="medium" :value="listDokumen.persen_dok" />
                                  </div>
                              </div>
                          </VCardHead>
                      </div>
                  </div>
              </div>
          </div>
          <div class="columns is-multiline">
              <div class="column is-12">
              <OrderList v-model="listDokumen.dokumen"  listStyle="height:auto" dataKey="id">
                  <template #header> Klik Dokumen Untuk Mengurutkan  </template>
                  <template #item="slotProps">
                        <div class="columns is-multiline p-2" >
                            <div class="column is-1">
                                <input type="checkbox" v-model="slotProps.item.checked" name="checked['{{ slotProps.item.urutan }}']" />
                            </div>
                            <div class="column is-7">
                                <span class="font-bold"><b>{{ slotProps.item.name }}</b></span>
                            </div>
                            <div class="column is-2">
                                <VButton color="primary" raised outlined rounded icon="fas fa-clipboard-list"
                                    class="w-100" @click="cetakanKlaim(slotProps.item)" v-if="slotProps.item.name == 'Klaim INACBG'">Cetak Klaim
                                </VButton>
                                <VButton color="primary" raised outlined rounded icon="fas fa-clipboard-list"
                                    class="w-100" @click="collect(slotProps.item)" v-else>Collect
                                </VButton>
                            </div>
  
                            <div class="column is-2">
                                <FileUpload mode="basic" name="demo[]" accept="application/pdf" @upload="onUpload"
                                    v-if="slotProps.item.doc == null" chooseLabel="Upload" @select="onSelect($event, slotProps.item)"
                                    class="is-rounded w-100" />
                                <VButton v-if="slotProps.item.doc != null" color="info" raised rounded icon="fas fa-eye"
                                    class="w-100" @click="lihatDok(slotProps.item)">Lihat
                                </VButton>
                            </div>
                        </div>
                  </template>
              </OrderList>
              </div>
              <div class="column is-8"></div>
              <div class="column is-2">
                  <VButton
                      @click="collectDokumenModal(listDokumen.dokumen.length > 0 ? listDokumen.dokumen[0].noregistrasi : '')"
                      color="info" raised outlined rounded icon="fas fa-check" class="w-100" style="display: none !important">
                      Collect All
                  </VButton>
              </div>
              <div class="column is-2">
                  <VButton
                      @click="verifikasiDokumenModal(listDokumen.dokumen.length > 0 ? listDokumen.dokumen[0].noregistrasi : '')"
                      color="info" raised outlined rounded icon="fas fa-check" class="w-100">
                      Verifikasi
                  </VButton>
              </div>
          </div>
      </Dialog>
  
      <!-- <Dialog v-model:visible="modalDokumen" modal header="Monitoring Dokumen Klaim" :style="{ width: '70vw' }"
          :maximizable="true">
          <ProgressBar mode="indeterminate" v-if="loadingUpload" style="height: 6px"></ProgressBar>
          <div class="columns is-multiline">
              <div class="column is-12">
  
                  <div class="columns is-multiline">
                      <div class="column is-12">
                          <VCardHead title="Bundle" class="p-2 text-center">
                              <div class="columns is-multiline">
                                  <div class="column is-2">
                                      <VButton
                                          @click="bundleDok(listDokumen.dokumen.length > 0 ? listDokumen.dokumen[0].noregistrasi : '')"
                                          color="danger" raised outlined rounded icon="fas fa-file-pdf" class="w-100">
                                          Lihat
                                      </VButton>
                                  </div>
                                  <div class="column is-10 mt-0 pt-0">
                                      <span>Status kelengkapan dokumen {{ listDokumen.persen_dok.toFixed(2) }} %</span>
                                      <VProgress
                                          :color="listDokumen.persen_dok < 33 ? 'danger' : (listDokumen.persen_dok >= 33 && listDokumen.persen_dok < 70 ? 'warning' : 'success')"
                                          size="medium" :value="listDokumen.persen_dok" />
                                  </div>
                              </div>
                          </VCardHead>
                      </div>
                      <div class="column is-3" v-for="items in listDokumen.dokumen">
                          <VCardHead :title="items.name" class="p-2 text-center">
                              <div class="columns is-multiline">
                                  <div class="column is-6">
                                      <VButton color="primary" raised outlined rounded icon="fas fa-clipboard-list"
                                          class="w-100" @click="collect(items)">Collect
                                      </VButton>
                                  </div>
  
                                  <div class="column is-6">
                                      <FileUpload mode="basic" name="demo[]" accept="application/pdf" @upload="onUpload"
                                          v-if="items.doc == null" chooseLabel="Upload" @select="onSelect($event, items)"
                                          class="is-rounded w-100" />
                                      <VButton v-if="items.doc != null" color="info" raised rounded icon="fas fa-eye"
                                          class="w-100" @click="lihatDok(items)">Lihat
                                      </VButton>
                                  </div>
                              </div>
                          </VCardHead>
                      </div>
  
                  </div>
              </div>
          </div>
      </Dialog> -->
      <Dialog v-model:visible="modalSEP" modal header="Update SEP" :style="{ width: '40vw' }">
          <div class="columns is-multiline">
              <div class="column is-12">
                 <VField> <VLabel>Nama Pasien</VLabel></VField>
                  <Chip :label="updatePA.nama_pasien" icon="pi pi-user"  class="w-100 p-2"/>
              </div>
              <div class="column is-6">
                  <VField label="No SEP">
                      <VControl icon="fas fa-address-book">
                          <input v-model="updatePA.nomor_sep" type="text" class="input is-rounded" placeholder="No SEP" />
                      </VControl>
                  </VField>
              </div>
              <div class="column is-6">
                  <VField label="No Kartu">
                      <VControl icon="fas fa-address-book">
                          <input v-model="updatePA.nomor_kartu" type="text" class="input is-rounded" placeholder="No Kartu" />
                      </VControl>
                  </VField>
              </div>
  
  
          </div>
          <template #footer>
              <VButton icon="lnir lnir-arrow-left rem-100" light dark-outlined @click="modalSEP = false">
                  Tutup
              </VButton>
              <VButton type="button" rounded outlined color="primary" raised icon="feather:save" :loading="isLoading"
                  @click="savePemakaianAsuransi()"> Simpan
              </VButton>
          </template>
      </Dialog>
  
      <Dialog v-model:visible="modalKoding" modal header="Bridging INACbg" :style="{ width: '60vw' }">
          <div class="columns is-multiline">
              <div class="column is-12">
                  <InaCbgDetail></InaCbgDetail>
              </div>
          </div>
          <template #footer>
              <VButton icon="lnir lnir-arrow-left rem-100" light dark-outlined @click="modalKoding = false">
                  Tutup
              </VButton>
          </template>
      </Dialog>
  
      <Dialog v-model:visible="modalVerifikasi" modal header="Keterangan" :style="{ width: '50vw' }">
          <div class="columns is-multiline">
              <div class="column is-12">
                  <VField>
                  <VControl>
                      <VTextarea class="textarea is-rounded" v-model="item.keteranganVerifikasi" rows="5" placeholder="Keterangan"
                      autocomplete="off" autocapitalize="off" spellcheck="true" />
                  </VControl>
                  </VField>
              </div>
          </div>
          <template #footer>
          <VButton icon="feather:refresh-cw rem-100" light dark-outlined @click="modalVerifikasi = false">
            Batal
          </VButton>
          <VButton type="button" rounded outlined color="primary" raised icon="feather:save" :loading="isLoading"
            @click="verifikasiDokumen()"> Simpan
          </VButton>
        </template>
      </Dialog>
  
      <Dialog v-model:visible="modalPenunjangKhusus" modal header="Penunjang Khusus" :style="{ width: '100rem' }" maximizable>
        <!-- <Penunjang v-if="modalPenunjangKhusus" :noregistrasi="noreg_pasienDetail" :norec_pd="item.NOREC_PD" :registrasi="selectedRegistrasi" :pasien="pasien"/> -->
        <span>Riwayat</span>
        <div class="column is-12">
              <div class="columns is-multiline">
              <table class="tg table-tg tabels" v-if="listTemplate.length > 0" width="100%">
                  <thead style="border-bottom: 1px solid #c7c8c9">
                  <tr>
                      <td class="tg-0lax text-center" width="10%">Cetak</td>
                      <td class="tg-0lax text-center" width="10%">Nomor</td>
                      <td class="tg-0lax text-center" width="20%">Tindakan</td>
                      <td class="tg-0lax text-center" width="15%">Tanggal Input</td>
                      <td class="tg-0lax text-center" width="15%">Tanggal Registrasi</td>
                      <td class="tg-0lax text-center" width="10%">No Registrasi</td>
                      <td class="tg-0lax text-center" width="15%">Dokter</td>
                      <td class="tg-0lax text-center" width="15%">Section</td>
                  </tr>
                  </thead>
                  <tbody v-for="resep in listTemplate">
                  <tr>
                      <td style="text-align: center">
                      <VIconButton type="button" raised circle icon="feather:printer" @click="cetakSalahSatu(resep)"
                          color="warning" v-tooltip.bubble="'Cetak Penunjang'">
                      </VIconButton>
                      </td>
                      <td style="text-align: center">
                      <span class="mb-2">{{ resep.noemr }}</span><br />
                      </td>
                      <td style="text-align: center">
                      <span class="mb-2">{{ resep.jenistindakan }}</span><br />
                      </td>
                      <td style="text-align: center">
                      <span class="mb-2">{{ resep.created_at }}</span><br />
                      </td>
                      <td style="text-align: center">
                      <span class="mb-2">{{ resep.registrasi.tglregistrasi }}</span><br />
                      </td>
                      <td style="text-align: center">
                      <span class="mb-2">{{ resep.registrasi.noregistrasi }}</span><br />
                      </td>
                      <td style="text-align: center">
                      <span class="mb-2">{{ resep.registrasi.dokter }}</span><br />
                      </td>
                      <td style="text-align: center">
                      <span class="mb-2">{{ resep.registrasi.namaruangan }}</span><br />
                      </td>
                  </tr>
                  </tbody>
              </table>
  
              <VCard radius="rounded" class="mt-2" v-if="listTemplate.length === 0">
                  <VPlaceholderPage :title="H.assets().notFound"
                      :subtitle="H.assets().notFoundSubtitle" larger>
                      <template #image>
                          <img class="light-image" :src="H.assets().iconNotFound_rev"
                              alt="" />
                          <img class="dark-image"
                              src="/@src/assets/illustrations/placeholders/search-4-dark.svg"
                              alt="" />
                      </template>
                  </VPlaceholderPage>
              </VCard>
  
              </div>
          </div>
      </Dialog>
    </section>
  </template>
  <script setup lang="ts">
  import { useWindowScroll } from '@vueuse/core'
  import { useApi } from '/@src/composable/useApi'
  import { h, reactive, ref, computed, defineComponent, watch } from 'vue'
  import { useRoute, onBeforeRouteLeave, useRouter } from 'vue-router'
  import { useHead } from '@vueuse/head'
  import * as H from '/@src/utils/appHelper'
  import Dialog from 'primevue/dialog';
  import { useUserSession } from '/@src/stores/userSession'
  import TabView from 'primevue/tabview';
  import TabPanel from 'primevue/tabpanel';
  import type { VAvatarProps } from '/@src/components/base/avatar/VAvatar.vue'
  import * as gridData from '/@src/data/layouts/user-grid-v2'
  import Dropdown from 'primevue/dropdown';
  import DataTable from 'primevue/datatable';
  import Column from 'primevue/column'
  import * as XLSX from "xlsx";
  import * as XLSXStyle from 'xlsx-js-style';
  import ConfirmDialog from 'primevue/confirmdialog';
  import MultiSelect from 'primevue/multiselect';
  import Badge from 'primevue/badge';
  import TStatusPojokKanan from '../emr/profile-pasien/t-status-pojok-kanan.vue'
  import Fieldset from 'primevue/fieldset';
  import FileUpload from 'primevue/fileupload';
  import { useViewWrapper } from '/@src/stores/viewWrapper'
  import ProgressBar from 'primevue/progressbar';
  import InputSwitch from 'primevue/inputswitch'
  import Chip from 'primevue/chip';
  import OrderList from 'primevue/orderlist';
  import Penunjang from '../emr/profile-pasien/page-emr/penunjang.vue'
  import InaCbgDetail from './inacbgs-detail.vue'
  
  useHead({
      title: 'Klaim INACBG`s - ' + import.meta.env.VITE_PROJECT,
  })
  useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
  useViewWrapper().setFullWidth(true)
  
  export interface UserData extends VAvatarProps {
      id: number
      username: string
      fullName: string
      location: string
      position: string
      bio: string
      tasks: {
          pending: number
      }
      status: string
      team: VAvatarProps[]
  }
  const item: any = reactive({
      qFilterTgl: {
          start: new Date(),
          end: new Date()
      },
      isNotSEP: false,
      isNotDiagnosis: false,
      isNotVerifikasi: false,
      isCatatan: false,
      isPenunjang: false,
      keteranganVerifikasi: '-'
  })
  const expandedRows: any = ref([]);    
  const updatePA:any = reactive({})
  const modalSEP = ref(false)
  const modalKoding = ref(false)
  const isSimpan: any = ref(false)
  const isSimpanHitung: any = ref(false)
  const isSimpanGrid: any = ref(false)
  const loadingUpload: any = ref(false)
  const modalVerifikasi: any = ref(false)
  const listTemplate: any = ref([])
  const lastSEP: any = ref('')
  const route = useRoute()
  const router = useRouter()
  const d_Status: any = ref([])
  const d_KelompokPasien: any = ref([])
  const d_Departemen: any = ref([])
  const d_Ruangan: any = ref([])
  const isLoading: any = ref(false)
  const isLoadingDetail: any = ref(false)
  const isLoadingExport: any = ref(false)
  const users = gridData.users as UserData[]
  const jmlFilter: any = ref(0)
  const modalFilter: any = ref(false)
  const modalDetail: any = ref(false)
  const filters = ref('')
  const NOREC_PD = ref('')
  const modalKlaim = ref(false);
  const dataSource: any = ref([])
  const dataSourceExport: any = ref([])
  const dataSourceDetail: any = ref([])
  const tarif18: any = ref({})
  const modalDokumen: any = ref(false)
  const listDokumen: any = ref([])
  const norec_pasien_daftar = ref('')
  const nocm_fk = ref('')
  const activeTab = ref(H.cacheHelper().get('tab') ? H.cacheHelper().get('tab') : 0)
  console.log('Aktif Tab', activeTab.value)
  const modalPenunjangKhusus = ref(false);
  const d_StatusPasien :any =ref([
    {
      label : "LAMA" ,
      status :"LAMA"
    },
    {
      label : "BARU" ,
      status :"BARU"
    }
  ])
  const currentPage: any = ref({
      limit: 20,
      rows: 20
  })
  let c = H.cacheHelper().get('c_inacbg');
  if (c != undefined) {
    item.qFilterTgl.start = new Date(c[0]);
    if (item.qFilterTgl.start == '' || isNaN(new Date(item.qFilterTgl.start).getTime())) {
        item.qFilterTgl.start = new Date();
    }
    item.qFilterTgl.end = new Date(c[1]);
    if (item.qFilterTgl.end == '' || isNaN(new Date(item.qFilterTgl.end).getTime())) {
    item.qFilterTgl.end = new Date(); // Set to today's date if invalid
    }
}
  const dataSourcefiltered = computed(() => {
      // if (!filters.value) {
          return dataSource.value
      // }
  
      // return dataSource.value.filter((items: any) => {
      //     return (
      //         items.nama_pasien.match(new RegExp(filters.value, 'i')) ||
      //         items.nomor_rm.match(new RegExp(filters.value, 'i'))
      //     )
      // })
  })
  
  const fetchData = async () => {
  
      // if(H.cacheHelper().get('tab')){
      //     console.log('Halo ini ada tab ' + H.cacheHelper().get('tab'))
      //     activeTab.value = H.cacheHelper().get('tab')
      // }
  
      item.ischeckedAll = false
      let limit: any = currentPage.value.limit
      let page: any = route.query.page ? route.query.page : 1
  
      let dari = ''
      if (item.qFilterTgl) {
          dari = H.formatDate(item.qFilterTgl.start, 'YYYY-MM-DD')
      }
      let sampai = ''
      if (item.qFilterTgl) {
          sampai = H.formatDate(item.qFilterTgl.end, 'YYYY-MM-DD')
      }
      let namapasien = ''
          , nocm = ''
          , search = ''
          , inacbg_status = ''
          , kelompok = ''
          , inst = ''
          , ruang = ''
          , statusPasien =''
  
  
      if (filters.value) {
          search = filters.value
      }
      jmlFilter.value = 0
  
      if (item.qKelompok && item.qKelompok.length) {
          kelompok = item.qKelompok.join(',')
          jmlFilter.value += 1
      }
      if (item.qRuangan) {
          ruang = item.qRuangan.id
          jmlFilter.value += 1
      }
      if (item.qInstalasi) {
          inst = item.qInstalasi.id
          jmlFilter.value += 1
      }
  
      if (item.qStatus) {
          inacbg_status = item.qStatus.inacbg_status
          jmlFilter.value += 1
      }
  
      if(item.qStatusPasien){
        statusPasien = item.qStatusPasien.status
        jmlFilter.value += 1
      }
  
  
      item.totalAll = 0
      isLoading.value = true
      dataSource.value = []
      const response = await useApi().get(
          '/bridging/inacbgs-download?dari=' + dari
          + '&sampai=' + sampai
          + '&search=' + search
          + '&ins=' + inst
          + '&ruang=' + ruang
          + '&kelId=' + kelompok
          + '&inacbg_status=' + inacbg_status
          + '&page=' + page
          + '&limit=' + limit
          + '&tab=' + activeTab.value
          + '&isNotSEP=' + item.isNotSEP
          + '&isNotDiagnosis=' + item.isNotDiagnosis
          + '&isNotVerifikasi=' + item.isNotVerifikasi
          + '&isCatatan=' + item.isCatatan
          + '&isPenunjang=' + item.isPenunjang
          + '&status_pasien=' + statusPasien
      )
  
      isLoading.value = false
      for (let x = 0; x < response.data.length; x++) {
          const element = response.data[x];
          element.checked = false
          element.no = x+1
          element.initials = H.INITIALS(element.nama_pasien)
  
  
          let ditanggung = parseFloat(element.inacbg_totalgrouper)
          let totaltagihan = parseFloat(element.totalbilling);
          let presn = ditanggung * 0.1
          let totalPersen = ditanggung - presn
  
          if (ditanggung != 0 && totaltagihan >= ditanggung) {
              element.color_plafon = 'is-danger'
          } else if (ditanggung != 0 && totaltagihan >= totalPersen) {
              element.color_plafon = 'is-warning'
          } else {
              element.color_plafon = ''
          }
      }
  
      item.jmlKlaim = 0
      item.jmlFinal = 0
      item.jmlGrouping = 0
      item.jmlBlmKirim = 0
      response.data.forEach(element => {
          if(element.inacbg_status == 'Final'){
              item.jmlFinal++
          }
          // item.jmlKlaim = element.inacbg_status == 'Klaim' ? 1 + item.jmlKlaim
          // item.jmlFinal = element.inacbg_status == 'Final' ? 1 + item.jmlFinal
          // item.jmlGrouping = element.inacbg_status == 'Grouping' ? element.inacbg_status + item.jmlGrouping : 0
          // item.jmlBlmKirim = element.inacbg_status == 'Belum Kirim' ? element.inacbg_status + item.jmlBlmKirim : 0
      });
      console.log(item.jmlFinal)
      dataSource.value = response.data
      dataSource.value.total = response.total
  
      // console.log(dataSource.value);
  
  
      let c_set = {
          0: dari,
          1: sampai,
      }
      H.cacheHelper().set('c_inacbg', c_set);
        // set page to 1
      route.query.page = 1
  }
  
  function klikTab(e: any) {
    H.cacheHelper().set('tab', e.index)
    activeTab.value = e.index
    fetchData()
  }
  
  const LoadExportExcel = async () => {
      item.ischeckedAll = false
  
      let dari = ''
      if (item.qFilterTgl) {
          dari = H.formatDate(item.qFilterTgl.start, 'YYYY-MM-DD')
      }
      let sampai = ''
      if (item.qFilterTgl) {
          sampai = H.formatDate(item.qFilterTgl.end, 'YYYY-MM-DD')
      }
      let namapasien = ''
          , nocm = ''
          , search = ''
          , inacbg_status = ''
          , kelompok = ''
          , inst = ''
          , ruang = ''
          , statusPasien =''
  
  
      if (filters.value) {
          search = filters.value
      }
      jmlFilter.value = 0
  
      if (item.qKelompok && item.qKelompok.length) {
          kelompok = item.qKelompok.join(',')
          jmlFilter.value += 1
      }
      if (item.qRuangan) {
          ruang = item.qRuangan.id
          jmlFilter.value += 1
      }
      if (item.qInstalasi) {
          inst = item.qInstalasi.id
          jmlFilter.value += 1
      }
  
      if (item.qStatus) {
          inacbg_status = item.qStatus.inacbg_status
          jmlFilter.value += 1
      }
  
      if(item.qStatusPasien){
        statusPasien = item.qStatusPasien.status
        jmlFilter.value += 1
      }
  
  
      item.totalAll = 0
      isLoadingExport.value = true
      const response = await useApi().get('/bridging/export-inacbgs?dari=' + dari + '&sampai=' + sampai
          + '&search=' + search
          + '&ins=' + inst
          + '&ruang=' + ruang
          + '&kelId=' + kelompok
          + '&inacbg_status=' + inacbg_status
          + '&tab=' + activeTab.value
          + '&isNotSEP=' + item.isNotSEP
          + '&isNotDiagnosis=' + item.isNotDiagnosis
          + '&isNotVerifikasi=' + item.isNotVerifikasi
          + '&isCatatan=' + item.isCatatan
          + '&isPenunjang=' + item.isPenunjang
          + '&status_pasien=' + statusPasien
      )
  
      isLoadingExport.value = false
      item.jmlKlaim = 0
      item.jmlFinal = 0
      item.jmlGrouping = 0
      item.jmlBlmKirim = 0
      item.jmlSdKirimDataCenter = 0
      response.forEach((element:any,i:any) => {
          element.no = i+1
          if(element.inacbg_status == 'Final'){
              item.jmlFinal++
          }
          if(element.inacbg_status == 'Klaim'){
              item.jmlKlaim++
          }
          if(element.inacbg_status == 'Grouping'){
              item.jmlGrouping++
          }
          if(element.inacbg_status == null){
              item.jmlBlmKirim++
          }
          if(element.inacbg_status == 'Sudah Kirim Ke Data Center'){
              item.jmlSdKirimDataCenter++
          }
      });
  
      dataSourceExport.value = response
      exportExcel()
  }
  
  const ringkasankeluar = async (emrId) => {
    console.log(emrId.emrpasienfk[0].emrpasienfk);
    H.printBladeSelf(`emr/cetak/RingkasanKeluar?emrpasienfk=${emrId.emrpasienfk[0].emrpasienfk}&pdf=true`);
  }
  
  const cpptdokter = async (emrId) => {
    console.log(emrId.emrpasienfkcppt[0].emrpasienfk);
    H.printBladeSelf(`emr/cetak/CatatanPerkembanganPasienTerintegrasi?emrpasienfk=${emrId.emrpasienfkcppt[0].emrpasienfk}&pdf=false`);
  }
  
  const cetakSalahSatu = async (emrId) => {
    console.log(emrId);
    H.printBladeSelf(`emr/cetak-detail/${emrId.collection}?id=${emrId.id}&pdf=true`);
  
    return
  
    let selectedCollection = COLLECTION.value;
  
    switch (input.value.studyType) {
      case 'TransThoracaEchoBayi':
        selectedCollection = COLLECTION_TransThoracaEchoBayi.value;
        break;
      case 'TransThoracaEchoDewasa':
        selectedCollection = COLLECTION_TransThoracaEchoDewasa.value;
        break;
      case 'LowerExtermityDuplexUltrasoundUSGDoppler':
        selectedCollection = COLLECTION_LowerExtermityDuplexUltrasoundUSGDoppler.value;
        break;
      case 'UpperExtermityDuplexUltrasoundUSGDoppler':
        selectedCollection = COLLECTION_UpperExtermityDuplexUltrasoundUSGDoppler.value;
        break;
      default:
        break;
    }
  
    if (selectedCollection) {
      H.printBladeSelf(`emr/cetak-detail/${selectedCollection}?id=${emrId}&pdf=true`);
    }
  
    switch (props.registrasi.objectruanganlastfk) {
      case 204:
        H.printBladeSelf(`emr/cetak-detail/${COLLECTION_THT.value}?id=${emrId}&pdf=true`);
        break;
      case 217:
        H.printBladeSelf(`emr/cetak-detail/${COLLECTION_EKG_MCU.value}?id=${emrId}&pdf=true`);
        break;
      case 224:
        H.printBladeSelf(`emr/cetak-detail/${COLLECTION_UROLOGI.value}?id=${emrId}&pdf=true`);
        break;
      case 201:
        H.printBladeSelf(`emr/cetak-detail/${COLLECTION_INTERNA.value}?id=${emrId}&pdf=true`);
        break;
      case 206:
        switch (true) {
          case isBodyPlethySmograph.value:
            H.printBladeSelf(`emr/cetak-detail/${COLLECTION_BODYPLETHYSMOGRAPH.value}?id=${emrId}&pdf=true`);
            break;
          case isDlcoBodyPlethy.value:
            H.printBladeSelf(`emr/cetak-detail/${COLLECTION_DLCO.value}?id=${emrId}&pdf=true`);
            break;
          case isSprirometri.value:
            H.printBladeSelf(`emr/cetak-detail/${COLLECTION_SPIROMETRI.value}?id=${emrId}&pdf=true`);
            break;
          default:
            break;
        }
        break;
      default:
        break;
    }
  };
  
  const exportExcel = () => {
      console.log(dataSourceExport.value)
      const workbook = XLSX.utils.book_new();
      const worksheet = XLSX.utils.aoa_to_sheet([
          ['Laporan Klaim Inacbg'],
          [],
          ['NO','Pasien','No RM','No Registrasi','No SEP','Ruangan','Kelompok Pasien','Dokter','Catatan','Konsul/Lab/Rad','Ruangan Konsul','Penunjang','Verifikasi','Ringkasan Keluar','Status Inacbg','Tgl Masuk','Tgl Pulang','Total Billing'],
          ...dataSourceExport.value.map((e: any) => [
              e.no,
              e.nama_pasien,
              e.nomor_rm,
              e.noregistrasi,
              e.nomor_sep,
              e.namaruangan,
              e.kelompokpasien + ' - ' + e.namarekanan,
              e.nama_dokter,
              e.ketverifikasi,
              e.iskonsul ? 'K' : e.islab ? 'L' : e.israd ? 'R' : e.islabharih ? 'L: Hari-H' : e.isradharih ? 'R: Hari-H' : '',
              e.ruangankonsul,
              e.isPenunjangKhusus == true ? 'Ya' : e.isPenunjangKhusus  == null ? 'Tidak' : 'Tidak',
              e.emrpasienfkresume == true ? 'Sudah' : 'Belum',
              e.tglverifklaim != null ? "Ya" : "Tidak",
              e.inacbg_status != null ? e.inacbg_status : 'Belum Kirim',
              e.tgl_masuk,
              e.tgl_pulang,
              e.totalmappingtarif,
          ]),
          [],
          ['','Total', 'Status Final Klaim', 'Status Klaim', 'Status Grouping Klaim', 'Status Belum Dikirim Klaim','Sudah Kirim Ke Data Center'],
          ['','',parseInt(item.jmlFinal) , parseInt(item.jmlKlaim), parseInt(item.jmlGrouping), parseInt(item.jmlBlmKirim),parseInt(item.jmlSdKirimDataCenter)]
      ]);
      // Mendefinisikan style untuk header(centered)
      const headerStyle = {
          alignment: {
              horizontal: 'center',
              vertical: 'center'
          },
          font: {
              color: { rgb: 'FFFFFF' }
          },
          fill: { fgColor: { rgb: '807C7C' } }
      };
  
      // Mendefinisikan range header
      const headerRange = XLSX.utils.decode_range(worksheet['!ref']);
      for (let col = headerRange.s.c; col <= headerRange.e.c; col++) {
          const headerCell = XLSX.utils.encode_cell({ r: 2, c: col });
          worksheet[headerCell].s = headerStyle;
      }
  
      const columnWidths = [5, 10, 13, 10, 10, 15, 20, 55, 20, 20, 20, 20, 20, 20, 20, 20, 20, 20];
  
      for (let col = headerRange.s.c; col <= headerRange.e.c; col++) {
          worksheet['!cols'] = worksheet['!cols'] || [];
          worksheet['!cols'][col] = { wch: columnWidths[col] };
      }
  
      // Centering the text in cell A1
      const titleCell = XLSX.utils.encode_cell({ r: 0, c: 0 });
      worksheet[titleCell].s = {
          alignment: {
              horizontal: 'center',
              vertical: 'center'
          },
          font: {
              bold: true,
              sz: 18
          }
      };
  
      // Menggabungkan dua baris pertama
      const mergeTitle = { s: { r: 0, c: 0 }, e: { r: 1, c: 9 } };
      worksheet['!merges'] = [mergeTitle];
  
      XLSX.utils.book_append_sheet(workbook, worksheet, 'Klaim Inacbg', true);
      XLSXStyle.writeFile(workbook, 'Laporan Klaim Inacbg.xlsx');
  }
  
  
  const terapkanFilter = () => {
      fetchData()
      modalFilter.value = false
  }
  
  const clearFilter = () => {
      delete item.qKasir
      delete item.qRuangan
      delete item.qInstalasi
      delete item.qKelompok
      delete item.qnama
      delete item.qnocm
      delete item.statusPasien
      fetchData()
      modalFilter.value = false
  }
  const changeInst = (e: any) => {
      d_Ruangan.value = e.value ? e.value.ruangan : []
  }
  const kunjungansebelumnya = (e: any) => {
    useApi().get('/bridging/inacbgs/get-kunjungan-sebelumnya?nocmfk='+e.nocmfk+'&tglregistrasi='+e.tgl_masuk).then((x) => {
        console.log(x.data)
        if (x.data.length == 0) {
          H.alert('error', 'Kunjungan Tidak Ditemukan')
          return
        } else{

            item.totalAll = 0
            isLoading.value = true
            dataSource.value = []
            useApi().get(
                '/bridging/inacbgs?dari=' +  H.formatDate(x.data[0].tglregistrasi, 'YYYY-MM-DD')
                + '&sampai=' + H.formatDate(x.data[0].tglregistrasi, 'YYYY-MM-DD')
                + '&search=' + x.data[0].noregistrasi
                + '&isPenunjang=false'
            ).then((response) => {
                isLoading.value = false
                for (let x = 0; x < response.data.length; x++) {
                    const element = response.data[x];
                    element.checked = false
                    element.no = x+1
                    element.initials = H.INITIALS(element.nama_pasien)
            
            
                    let ditanggung = parseFloat(element.inacbg_totalgrouper)
                    let totaltagihan = parseFloat(element.totalbilling);
                    let presn = ditanggung * 0.1
                    let totalPersen = ditanggung - presn
            
                    if (ditanggung != 0 && totaltagihan >= ditanggung) {
                        element.color_plafon = 'is-danger'
                    } else if (ditanggung != 0 && totaltagihan >= totalPersen) {
                        element.color_plafon = 'is-warning'
                    } else {
                        element.color_plafon = ''
                    }
                }
            
                item.jmlKlaim = 0
                item.jmlFinal = 0
                item.jmlGrouping = 0
                item.jmlBlmKirim = 0
                response.data.forEach(element => {
                    if(element.inacbg_status == 'Final'){
                        item.jmlFinal++
                    }
                    // item.jmlKlaim = element.inacbg_status == 'Klaim' ? 1 + item.jmlKlaim
                    // item.jmlFinal = element.inacbg_status == 'Final' ? 1 + item.jmlFinal
                    // item.jmlGrouping = element.inacbg_status == 'Grouping' ? element.inacbg_status + item.jmlGrouping : 0
                    // item.jmlBlmKirim = element.inacbg_status == 'Belum Kirim' ? element.inacbg_status + item.jmlBlmKirim : 0
                });
                console.log(item.jmlFinal)
                dataSource.value = response.data
                dataSource.value.total = response.total
            })
        
            





           
        }
    })
  }
  const newKlaim = async () => {
      let json = []
      for (let i = 0; i < dataSource.value.length; i++) {
          const element = dataSource.value[i];
          if (element.checked)
              json.push(element.new_claim)
      }
      for (let i = 0; i < dataSource.value.length; i++) {
          const element = dataSource.value[i];
          if (element.checked)
              json.push(element.set_claim_data)
      }
      if (json.length == 0) {
          H.alert('error', 'Ceklis data yang mau dikirim')
          return
      }
      isSimpan.value = true
      await useApi().postBPJS('/bridging/inacbgs/save', { 'data': json }).then(async (r) => {
          let arrStatus = []
          for (let x = 0; x < r.response.dataresponse.length; x++) {
              const element = r.response.dataresponse[x];
              if (element.dataresponse.metadata.code == 200) {
                  arrStatus.push({
                      'nomor_sep': element.datarequest.data.nomor_sep,
                      'inacbg_status': element.datarequest.metadata.method,
                      "diagnosa": element.datarequest.data.diagnosa ? element.datarequest.data.diagnosa : null,
                      "procedure": element.datarequest.data.procedure ? element.datarequest.data.procedure : null,
                      "diagnosa_inagrouper": element.datarequest.data.diagnosa_inagrouper ? element.datarequest.data.diagnosa_inagrouper : null,
                      "procedure_inagrouper": element.datarequest.data.procedure_inagrouper ? element.datarequest.data.procedure_inagrouper : null,
                  })
                  H.alert('success', element.dataresponse.metadata.message)
              } else {
                if(element.dataresponse.metadata.message  != 'Duplikasi nomor SEP'){
                  H.alert('error', element.dataresponse.metadata.message)
                }
              }
          }
          saveStatus(arrStatus, true)
          isSimpan.value = false
      }, (error) => {
          isSimpan.value = false
      })
  }
  const saveStatus = async (e: any, load : boolean) => {
      if (!e.length) return
      for (let i = 0; i < dataSource.value.length; i++) {
          const element = dataSource.value[i];
          for (var ii = 0; ii < e.length; ii++) {
              const elem2 = e[ii]
              if (element.nomor_sep == elem2.nomor_sep) {
                  elem2.norec = element.norec
              }
          }
      }
      await useApi().postBPJS('/bridging/inacbgs/save-status', { 'data': e }).then(async (r) => {
        // if(load)
          fetchData()
      })
  }
  
  const grouper = async (e: any) => {
  
      isSimpanGrid.value = true
      await useApi().postBPJS('/bridging/inacbgs/save', { 'data': [e.grouper] }).then((r) => {
          let arrStatus = []
          let arrGroup = []
          for (let x = 0; x < r.response.dataresponse.length; x++) {
              const element = r.response.dataresponse[x];
              if (element.dataresponse.metadata.code == 200) {
                  arrGroup.push({
                      'nomor_sep': element.datarequest.data.nomor_sep,
                      'inacbg_status': element.dataresponse.metadata.method,
                      'dataresponse': element.dataresponse
                  })
                  arrStatus.push({
                      'nomor_sep': element.datarequest.data.nomor_sep,
                      'inacbg_status': element.datarequest.metadata.method
                  })
                  H.alert('success',element.dataresponse.response ? element.dataresponse.response.cbg.description : element.dataresponse.metadata.message)
              } else {
                  H.alert('error', element.dataresponse.response ? element.dataresponse.response.cbg.description : element.dataresponse.metadata.message)
              }
          }
          saveStatus(arrStatus,false)
          saveGrouping(arrGroup,true)
          isSimpanGrid.value = false
      }, (error) => {
          isSimpanGrid.value = false
      })
  }
  
  const claim_print = async (e: any) => {
      let json = [
          {
              "metadata": {
                  "method": "claim_print"
              },
              "data": {
                  "nomor_sep": e.nomor_sep
              }
          }
      ]
      e.loading = true
      await useApi().postBPJS('/bridging/inacbgs/save', { 'data': json }).then((r) => {
          let arrStatus = []
          for (let x = 0; x < r.response.dataresponse.length; x++) {
              const element: any = r.response.dataresponse[x];
              if (element.dataresponse.metadata.code == 200) {
                  arrStatus.push({
                      'nomor_sep': element.datarequest.data.nomor_sep,
                      'inacbg_status': element.datarequest.metadata.method
                  })
                  const linkSource = 'data:application/pdf;base64,' + element.dataresponse.data;
                  const downloadLink = document.createElement("a");
  
                  let a = element.datarequest.data.nomor_sep
                  let nama = a.substr(12);
                  const fileName = nama + ".pdf";
  
                  downloadLink.href = linkSource;
                  downloadLink.download = fileName;
                  downloadLink.click();
                  H.alert('success', element.dataresponse.metadata.message)
              } else {
                  H.alert('error', element.dataresponse.metadata.message)
              }
          }
          // saveStatus(arrStatus)
          e.loading = false
      }, (error) => {
          e.loading = false
      })
  }
  const detail = (e: any) => {
    modalKlaim.value = false
    // norec_pasien_daftar.value = ''
    // nocm_fk.value = ''

    // norec_pasien_daftar.value = e.norec
    // nocm_fk.value = e.nocmfk
      
    //   console.log('NOREC PD', norec_pasien_daftar.value)
    //   console.log('NOCMFK', nocm_fk.value)
      
      if(e.norec != ''){
        router.push({
            query: {
                norec_pd: e.norec,
                nocmfk: e.nocmfk,
                page: currentPage.value.page,
                limit: currentPage.value.limit
            }
        })
        norec_pasien_daftar.value = route.query.norec_pd
        nocm_fk.value = route.query.nocmfk
        if(e.norec == route.query.norec_pd){
            model()
        }
        
      }

    //   router.push({
    //       name: 'module-inacbgs-inacbgs-detail',
    //       query: {
    //           norec_pd: e.norec,
    //           nocmfk: e.nocmfk,
    //       }
    //   })
  }
  const model = (e: any) => {
    
    modalKlaim.value = true
   
  }
//   const detail = (e: any) => {
//       // modalKoding.value = true
//       router.push({
//           name: 'module-inacbgs-inacbgs-detail',
//           query: {
//               norec_pd: e.norec,
//               nocmfk: e.nocmfk,
//           }
//       })
//   }
  const emr = (e: any) => {
    H.cacheHelper().set('xxx_cache_menu', undefined)
    // sendAntrol(e.norec_pd)
    // console.log(e);
  
    router.push({
      name: 'module-emr-profile-pasien',
      query: {
        nocmfk: e.nocmfk,
        norec_pd: e.norec,
        norec_apd: e.norec_apd,
      }
    })
  }
  const fetchDropdown = async () => {
      await useApi().get('/bridging/inacbgs/dropdown').then((r) => {
          d_Departemen.value = r.departemen
          d_KelompokPasien.value = r.kelompokpasien
          d_Status.value = r.inacbg_status
          d_Status.value.push({inacbg_status: "belum_kirim", status: 'Belum Kirim'})
          item.qKelompok = r.idKelompokPasienBPJS.map((e: any) => {
              return parseInt(e)
          })
      })
    //   fetchData()
  }
  const hapusKlaim = async (e: any) => {
      isSimpanGrid.value = true
      await useApi().postBPJS('/bridging/inacbgs/save', { 'data': [e.delete_claim] }).then((r) => {
          let arrStatus = []
          for (let x = 0; x < r.response.dataresponse.length; x++) {
              const element = r.response.dataresponse[x];
              if (element.dataresponse.metadata.code == 200) {
                  arrStatus.push({
                      'nomor_sep': element.datarequest.data.nomor_sep,
                      'inacbg_status': element.datarequest.metadata.method
                  })
                  H.alert('success', element.dataresponse.metadata.message)
              } else {
                  H.alert('error', element.dataresponse.metadata.message)
              }
          }
          saveStatus(arrStatus,true)
          isSimpanGrid.value = false
      }, (error) => {
          isSimpanGrid.value = false
      })
  }
  const saveGrouping = async (e: any,load:boolean) => {
      if (!e.length) return
      for (let i = 0; i < dataSource.value.length; i++) {
          const element = dataSource.value[i];
          for (var ii = 0; ii < e.length; ii++) {
              const elem2 = e[ii]
              if (element.nomor_sep == elem2.nomor_sep) {
                  elem2.norec = element.norec
                  elem2.jenis_rawat = element.jenis_rawat
              }
          }
      }
      for (var ii = 0; ii < e.length; ii++) {
          const elem2 = e[ii]
          let totaldijamin = 0
          let biayanaikkelas = 0
          if (elem2.jenis_rawat != 1) {
              totaldijamin = elem2.dataresponse.tarif_alt[2].tarif_inacbg
          } else {
              let hakkelas = elem2.dataresponse.response.kelas
              if (hakkelas == "kelas_1") {
                  totaldijamin = elem2.dataresponse.tarif_alt[0].tarif_inacbg
              } else if (hakkelas == "kelas_2") {
                  totaldijamin = elem2.dataresponse.tarif_alt[1].tarif_inacbg
              } else if (hakkelas == "kelas_3") {
                  totaldijamin = elem2.dataresponse.tarif_alt[2].tarif_inacbg
              }
  
              biayanaikkelas = elem2.dataresponse.response.add_payment_amt ? elem2.dataresponse.response.add_payment_amt : 0
              if (biayanaikkelas < 0) {
                  biayanaikkelas = 0
              }
          }
          let json = {
              'norec': elem2.norec,
              'totaldijamin': totaldijamin,
              'biayanaikkelas': biayanaikkelas,
              'inacbg_grouper': elem2.dataresponse
          }
          await useApi().postBPJS('/bridging/inacbgs/save-grouping', json)
  
      }
    //   if(load)
      fetchData()
  }
  
  const hitungBiayaSementara = async (e: any) => {
      e.isSimpanHitung = true
      let json = []
      json.push(e.new_claim)
      json.push(e.set_claim_data)
  
      await useApi().postBPJS('/bridging/inacbgs/save', { 'data': json }).then(async (r) => {
  
          for (let x = 0; x < r.response.dataresponse.length; x++) {
              const element = r.response.dataresponse[x];
              if (element.dataresponse.metadata.code == 200) {
                  await useApi().postBPJS('/bridging/inacbgs/save', { 'data': [e.grouper] }).then(async (rr) => {
  
                      let arrGroup = []
                      for (let x = 0; x < rr.response.dataresponse.length; x++) {
                          const elementx = rr.response.dataresponse[x];
                          if (elementx.dataresponse.metadata.code == 200) {
                              arrGroup.push({
                                  'nomor_sep': elementx.datarequest.data.nomor_sep,
                                  'inacbg_status': elementx.dataresponse.metadata.method,
                                  'dataresponse': elementx.dataresponse
                              })
                          } else {
                              H.alert('error', elementx.dataresponse.response.cbg.description)
                          }
                      }
  
                      await saveGrouping(arrGroup,false)
                      await useApi().postBPJS('/bridging/inacbgs/save', { 'data': [e.delete_claim] }).then(async (xx) => {
                          let arrStatus = []
                          for (let x = 0; x < xx.response.dataresponse.length; x++) {
                              const element = xx.response.dataresponse[x];
                              if (element.dataresponse.metadata.code == 200) {
                                  arrStatus.push({
                                      'nomor_sep': element.datarequest.data.nomor_sep,
                                      'inacbg_status': null
                                  })
                                  H.alert('success', element.dataresponse.metadata.message)
                              } else {
                                  H.alert('error', element.dataresponse.metadata.message)
                              }
                          }
                          await saveStatus(arrStatus,true)
                      })
                  })
              } else {
                if(element.dataresponse.metadata.message  != 'Duplikasi nomor SEP'){
                  H.alert('error', element.dataresponse.metadata.message)
                }
              }
          }
  
          e.isSimpanHitung = false
      }, (error) => {
          e.isSimpanHitung = false
      })
  }
  
  const kirimClaimDataCenter = async (e: any) => {
      isLoading.value = true
      let json = [
          {
              "metadata": {
                  "method": "send_claim_individual"
              },
              "data": {
                  "nomor_sep": e.nomor_sep
              }
          }
      ]
      await useApi().postBPJS('/bridging/inacbgs/save', { 'data': json }).then((r) => {
          let arrStatus = []
          for (let x = 0; x < r.response.dataresponse.length; x++) {
              const element = r.response.dataresponse[x];
              if (element.dataresponse.metadata.code == 200) {
                  arrStatus.push({
                      'nomor_sep': element.datarequest.data.nomor_sep,
                      'inacbg_status': element.datarequest.metadata.method,
                      'kemkes_dc_status': element.dataresponse.response.data[0].kemkes_dc_status,
                      'bpjs_dc_status': element.dataresponse.response.data[0].bpjs_dc_status,
                      'cob_dc_status': element.dataresponse.response.data[0].cob_dc_status
                  })
                  H.alert('success', element.dataresponse.metadata.message)
              } else {
                  H.alert('error', element.dataresponse.metadata.message)
              }
          }
          saveStatus(arrStatus,true)
          isLoading.value = false
      }, (error) => {
          isLoading.value = false
      })
  }
  
  const finalClaim = async (e: any) => {
      isLoading.value = true
      let jsonclaimdata = [
          {
              "metadata": {
                  "method": "get_claim_data"
              },
              "data": {
                  "nomor_sep": e.nomor_sep
              }
          }
      ]
      const resclaimdata = await useApi().postBPJS('/bridging/inacbgs/save', { 'data': jsonclaimdata })
      const claimdata = resclaimdata.response.dataresponse[0].dataresponse
      if(claimdata.metadata.code != 200)
      {
          isLoading.value = false
          H.alert('error', claimdata.metadata.message)
          return;
      }
  
      if(claimdata.response.data)
      {
          if(claimdata.response.data.grouper.response != null) {
              if(claimdata.response.data.grouper.response.cbg.description.indexOf("KEMOTERAPI") !== -1) {
                  if(claimdata.response.data.tarif_rs.obat_kemoterapi <= 0) {
                      isLoading.value = false
                      H.alert("warning", "Dilakukan prosedur kemoterapi, tapi nilai obat kemoterapi masih kosong.")
                      return
                  }
              }
          }
      }
      let json = [
          {
              "metadata": {
                  "method": "claim_final"
              },
              "data": {
                  "nomor_sep": e.nomor_sep,
                  "coder_nik": e.codernik
              }
          }
      ]
      await useApi().postBPJS('/bridging/inacbgs/save', { 'data': json }).then((r) => {
          let arrStatus = []
          for (let x = 0; x < r.response.dataresponse.length; x++) {
              const element = r.response.dataresponse[x];
              if (element.dataresponse.metadata.code == 200) {
                  arrStatus.push({
                      'nomor_sep': element.datarequest.data.nomor_sep,
                      'inacbg_status': element.datarequest.metadata.method
                  })
                  H.alert('success', element.dataresponse.metadata.message)
              } else {
                  H.alert('error', element.dataresponse.metadata.message)
              }
          }
          saveStatus(arrStatus,true)
          isLoading.value = false
      }, (error) => {
          isLoading.value = false
      })
  }
  
  const detailTarif = async (e: any) => {
      tarif18.value = e.tarif_rs
      tarif18.value.totalmappingtarif = e.totalmappingtarif
      tarif18.value.totalbilling = e.totalbilling
      tarif18.value.belum_mapping = e.belum_mapping
      modalDetail.value = true
  }
  
  const changeSwitch = (e: any) => {
      for (let x = 0; x < dataSource.value.length; x++) {
          const element = dataSource.value[x];
          if (e)
              element.checked = true
          else
              element.checked = false
      }
  }
  // const masterTarif = () => {
  //     router.push({
  //         name: 'module-sysadmin-master-produk',
  //     })
  // }
  const popUpDok = async (e: any) => {
      let jml = 0
      e.dokumen.forEach((element: any) => {
          if (element.doc != null) {
              jml = jml + 1
          }
      });
      e.persen_dok = jml / e.dokumen.length * 100
      listDokumen.value = e
      modalDokumen.value = true
  }
  const verifikasi = async (e: any) => {
    //   if(e.emrpasienfkresume == false && e.deptid != 9 && e.deptid != 16){
    //       H.alert('error', 'Resume Medis belum ada')
    //       return
    //   }
      let jml = 0
      e.dokumen.forEach((element: any) => {
          if (element.doc != null) {
              jml = jml + 1
          }
      });
      e.persen_dok = jml / e.dokumen.length * 100
      listDokumen.value = e
      lastSEP.value = e.nomor_sep
      NOREC_PD.value = e.norec
    //   collectDokumenModal(e)
      modalDokumen.value = true
  }
  const collectAll = async (e: any) => {
    for(var z = 0; z < dataSource.value.length; z++){
        let element = dataSource.value[z]
        listDokumen.value = element
        for(var x =0; x < listDokumen.value.dokumen.length; x++){
        let element = listDokumen.value.dokumen[x]
        if(listDokumen.value.inacbg_status == "Sudah Kirim Ke Data Center"){
            await useApi().post('/bridging/inacbgs/collect-dokumen', {
                'norec_pd': element.norec_pd,
                'documentklaimfk': element.documentklaimfk,
                'namafile': element.kodeexternal,
                'tglregistrasi': element.tglregistrasi,
                'api': element.api
            }).then((r) => {
                loadingUpload.value = false
                element.doc = r.filename
                countPersen()
            }).catch((er) => {
                loadingUpload.value = false
            })
        } else{
            if(element.kodeexternal != "klaim_indi"){
                await useApi().post('/bridging/inacbgs/collect-dokumen', {
                    'norec_pd': element.norec_pd,
                    'documentklaimfk': element.documentklaimfk,
                    'namafile': element.kodeexternal,
                    'tglregistrasi': element.tglregistrasi,
                    'api': element.api
                }).then((r) => {
                    loadingUpload.value = false
                    element.doc = r.filename
                    countPersen()
                }).catch((er) => {
                    loadingUpload.value = false
                })
            }
        }
    }
    }
  }
  const modalPenunjang = async (e: any) => {
      modalPenunjangKhusus.value = true
  
      let collectionsMap = {
          204: ["PemeriksaanTHT"],
          404: ["PemeriksaanTHT"],
          378: ["PemeriksaanTHT"],
          202: [
          "PemeriksaanKardiotokografi",
          "PemeriksaanObstetri",
          "PemeriksaanGynekologi",
          "PemeriksaanFetal",
          ],
          370: [
          "PemeriksaanKardiotokografi",
          "PemeriksaanObstetri",
          "PemeriksaanGynekologi",
          "PemeriksaanFetal",
          ],
          402: [
          "PemeriksaanKardiotokografi",
          "PemeriksaanObstetri",
          "PemeriksaanGynekologi",
          "PemeriksaanFetal",
          ],
          212: [
          "TransThoracaEchoBayi",
          "TransThoracaEchoDewasa",
          "LowerExtermityDuplexUltrasoundUSGDoppler",
          "CarotidDuplexUltrasound",
          "FormulirHasilPemeriksaanEkg",
          ],
          377: [
          "TransThoracaEchoBayi",
          "TransThoracaEchoDewasa",
          "LowerExtermityDuplexUltrasoundUSGDoppler",
          "CarotidDuplexUltrasound",
          "FormulirHasilPemeriksaanEkg",
          ],
          409: [
          "TransThoracaEchoBayi",
          "TransThoracaEchoDewasa",
          "LowerExtermityDuplexUltrasoundUSGDoppler",
          "CarotidDuplexUltrasound",
          "FormulirHasilPemeriksaanEkg",
          ],
          201: ["PemeriksaanEkgInterna"],
          401: ["PemeriksaanEkgInterna"],
          365: ["PemeriksaanEkgInterna"],
          217: ["PemeriksaanEkgMcu"],
          224: ["PemeriksaanUrologi"],
          375: ["PemeriksaanUrologi"],
          206: [
          "HasilPemeriksaanBodyplethy",
          "HasilPemeriksaanDLCOBodyplethy",
          "HasilPemeriksaanSpirometri",
          ],
          382: [
          "HasilPemeriksaanBodyplethy",
          "HasilPemeriksaanDLCOBodyplethy",
          "HasilPemeriksaanSpirometri",
          ],
      };
  
      listTemplate.value = [];
  
      try {
          // const responsex = useApi().get(
          // `/dashboard/get-norecapd-ruangan?norec_pd=${e.norec}`
          // );
          
  
          useApi().get(`/dashboard/get-norecapd-ruangan?norec_pd=${e.norec}`).then((responsex) => {
              console.log(responsex)
              for (let x = 0; x < responsex.length; x++) {
                  const ruanganId = responsex[x].objectruanganfk;
                  const collections = collectionsMap[ruanganId] || [];
  
                  if (collections.length > 0) {
                      // Panggil fungsi untuk mengambil data dan detail hanya sekali
                      try {
                          for (let collection of collections) {
                              // Cek apakah data collection sudah ada di listTemplate
                              const existingData = listTemplate.value.some(item => item.collection === collection);
  
                              // Jika data belum ada, baru lakukan fetch
                              if (!existingData) {
                                  useApi().get(`/emr/get-emr?nocmfk=${e.nocmfk}&collection=${collection}`).then((response) => {
                                      if (response.length) {
                                          response.forEach((item) => {
                                              listTemplate.value.push({...item, collection });
                                          });
                                      }
                                  }); 
                                  
                                  console.log(listTemplate.value)
                              }
                              }
                          } catch (error) {
                              console.error(`Error fetching collections data`, error);
                          }
                  }
              }
          })
      } catch (error) {
          console.error("Error loading riwayat:", error);
      } finally {
          isLoading.value = false;
      }
  }
  const onAdvancedUpload = () => {
      // toast.add({ severity: 'info', summary: 'Success', detail: 'File Uploaded', life: 3000 });
  };
  const billing = (e: any) => {
      router.push({
          name: 'module-kasir-billing',
          query: {
              norec_pasien_daftar: e.norec,
          },
      })
  }
  const onSelect = async (filez: any, e: any) => {
  
      const file = filez
          .files[0];
      if (file.size > 1000000) {
          H.alert('error', 'Maksimal file size adalah 1 MB')
          return
      }
      if (file.type != "application/pdf") {
          H.alert('error', 'File yang diizinkan dalam bentuk format PDF.')
          return;
      }
      const formData = new FormData()
      formData.append('fileBerkas', file)
      formData.append('norec_pd', e.norec_pd)
      formData.append('documentklaimfk', e.documentklaimfk)
      formData.append('namafile', e.kodeexternal)
      formData.append('tglregistrasi', e.tglregistrasi)
      loadingUpload.value = true
      await useApi().post('/bridging/inacbgs/save-dokumen', formData).then((r) => {
          loadingUpload.value = false
          e.doc = r.filename
          countPersen()
      })
  
  }
  const countPersen = () => {
      let jml = 0
      listDokumen.value.dokumen.forEach((element: any) => {
          if (element.doc != null) {
              jml = jml + 1
          }
      });
      listDokumen.value.persen_dok = jml / listDokumen.value.dokumen.length * 100
  }
  const lihatDok = (e: any) => {
    // H.open('bridging/inacbgs/lihat-bundle-dokumen?norec_pd=' + e.norec_pd + '&urutdokumen=' + e.urutan);
      H.openFile('dokumen_klaim/' + e.noregistrasi + '/' + e.doc);
  }

  console.log('Halo')
  
  const bundleDok = (e: any) => {
      var urutdokumen='';
      var i=0;
      listDokumen.value.dokumen.forEach((element: any) => {
          if(element.checked) {
              if (i>0) urutdokumen = urutdokumen + ',' + element.urutan;
              else  urutdokumen = urutdokumen + element.urutan;
              i++;
          }
      });
      urutdokumen = urutdokumen + ',';
  
      console.log(listDokumen.value.dokumen)
  
      H.open('bridging/inacbgs/bundle-dokumen-rev?noregistrasi=' + e + '&urutdokumen=' + urutdokumen);
  
      // H.open('bridging/inacbgs/bundle-dokumen?noregistrasi=' + e);
  }

  const bundleAll = (e: any) => {

    for(var z = 0; z < dataSource.value.length; z++){
        let element = dataSource.value[z]
        let noregistrasi = element.noregistrasi
        listDokumen.value = element
        for(var x =0; x < listDokumen.value.dokumen.length; x++){
        let element = listDokumen.value.dokumen[x]

        var urutdokumen='';
        var i=0;
        listDokumen.value.dokumen.forEach((element: any) => {
            if(element.checked) {
                if (i>0) urutdokumen = urutdokumen + ',' + element.urutan;
                else  urutdokumen = urutdokumen + element.urutan;
                i++;
            }
        });
        urutdokumen = urutdokumen + ',';
    
        console.log(listDokumen.value.dokumen)
        console.log(element)
    
        }
        H.open('bridging/inacbgs/bundle-dokumen-rev?noregistrasi=' + noregistrasi + '&urutdokumen=' + urutdokumen);
    }


      
  
      // H.open('bridging/inacbgs/bundle-dokumen?noregistrasi=' + e);
  }
  
  const bundleDokDownload = (e: any) => {
    let jml = 0
    e.dokumen.forEach((element: any) => {
        if (element.doc != null) {
            jml = jml + 1
        }
    });
    e.persen_dok = jml / e.dokumen.length * 100
    listDokumen.value = e

    console.log(listDokumen.value)

    var urutdokumen='';
    var i=0;
    listDokumen.value.dokumen.forEach((element: any) => {
        if(element.checked) {
            if (i>0) urutdokumen = urutdokumen + ',' + element.urutan;
            else  urutdokumen = urutdokumen + element.urutan;
            i++;
        }
    });
    urutdokumen = urutdokumen + ',';

    console.log(listDokumen.value.dokumen)

    H.download('bridging/inacbgs/bundle-dokumen-rev-download?noregistrasi=' + e.noregistrasi + '&urutdokumen=' + urutdokumen, e.nomor_sep);
}

const downloadAll = (e: any) => {

for(var z = 0; z < dataSource.value.length; z++){
    let element = dataSource.value[z]
    let noregistrasi = element.noregistrasi
    listDokumen.value = element
    for(var x =0; x < listDokumen.value.dokumen.length; x++){
    let element = listDokumen.value.dokumen[x]

    var urutdokumen='';
    var i=0;
    listDokumen.value.dokumen.forEach((element: any) => {
        if(element.checked) {
            if (i>0) urutdokumen = urutdokumen + ',' + element.urutan;
            else  urutdokumen = urutdokumen + element.urutan;
            i++;
        }
    });
    urutdokumen = urutdokumen + ',';

    console.log(listDokumen.value.dokumen)
    console.log(element)

    }
    H.download('bridging/inacbgs/bundle-dokumen-rev-download?noregistrasi=' + noregistrasi + '&urutdokumen=' + urutdokumen, element.nomor_sep);
}


  

  // H.open('bridging/inacbgs/bundle-dokumen?noregistrasi=' + e);
}

  const bundleDokDownloadV2 = (e: any) => {
    //   if(e.emrpasienfkresume == false){
    //       H.alert('error', 'Resume Medis belum ada')
    //       return
    //   }
      let jml = 0
      e.dokumen.forEach((element: any) => {
          if (element.doc != null) {
              jml = jml + 1
          }
      });
      e.persen_dok = jml / e.dokumen.length * 100
      listDokumen.value = e
  
      console.log(listDokumen.value)
  
      var urutdokumen='';
      var i=0;
      listDokumen.value.dokumen.forEach((element: any) => {
          if(element.checked) {
              if (i>0) urutdokumen = urutdokumen + ',' + element.urutan;
              else  urutdokumen = urutdokumen + element.urutan;
              i++;
          }
      });
      urutdokumen = urutdokumen + ',';
  
      console.log(listDokumen.value.dokumen)
    
      let json ={
        noregistrasi: e.noregistrasi,
        urutdokumen: urutdokumen
      }

      useApi().post('/bridging/inacbgs/bundle-dokumen-rev-download-v2', json)
  
    //   H.download('bridging/inacbgs/bundle-dokumen-rev-download-v2?noregistrasi=' + e.noregistrasi + '&urutdokumen=' + urutdokumen, e.nomor_sep);
  }
  
  const onUpload = () => {
  
  }
  const cetakanKlaim = async (e: any) => {
      console.log('E NOSEP', lastSEP.value)
      let json = [
          {
              "metadata": {
                  "method": "claim_print"
              },
              "data": {
                  "nomor_sep": lastSEP.value
              }
          }
      ]
      loadingUpload.value = true
      await useApi().postBPJS('/bridging/inacbgs/save', { 'data': json }).then((r) => {
          let arrStatus = []
          for (let x = 0; x < r.response.dataresponse.length; x++) {
              const element: any = r.response.dataresponse[x];
              if (element.dataresponse.metadata.code == 200) {
                  arrStatus.push({
                      'nomor_sep': element.datarequest.data.nomor_sep,
                      'inacbg_status': element.datarequest.metadata.method
                  })
                  const linkSource = 'data:application/pdf;base64,' + element.dataresponse.data;
                  const downloadLink = document.createElement("a");
  
                  let a = element.datarequest.data.nomor_sep
                  let nama = a.substr(12);
                  const fileName = nama + ".pdf";
  
                  // downloadLink.href = linkSource;
                  // downloadLink.download = fileName;
                  // downloadLink.click();
                  H.alert('success', element.dataresponse.metadata.message)
  
                  let dataSave = {
                      norec_pd: e.norec_pd,
                      link: element.dataresponse.data,
                  };
                  loadingUpload.value = true
                  useApi().post('/bridging/inacbgs/save-klaim-print', dataSave).then((r) => {
                      loadingUpload.value = false
                      e.doc = r.filename
                      countPersen()
                  }).catch((er) => {
                  loadingUpload.value = false
                  })
              } else {
                  H.alert('error', element.dataresponse.metadata.message)
              }
          }
          // saveStatus(arrStatus)
          loadingUpload.value = false
      }, (error) => {
          loadingUpload.value = false
      })
  }

  const collectDokumenModal = async (e: any) => {
    console.log(listDokumen.value)
    for(var x =0; x < listDokumen.value.dokumen.length; x++){
        let element = listDokumen.value.dokumen[x]
        if(listDokumen.value.inacbg_status == "Sudah Kirim Ke Data Center"){
            await useApi().post('/bridging/inacbgs/collect-dokumen', {
                'norec_pd': element.norec_pd,
                'documentklaimfk': element.documentklaimfk,
                'namafile': element.kodeexternal,
                'tglregistrasi': element.tglregistrasi,
                'api': element.api
            }).then((r) => {
                loadingUpload.value = false
                element.doc = r.filename
                countPersen()
            }).catch((er) => {
                loadingUpload.value = false
            })
        } else{
            if(element.kodeexternal != "klaim_indi"){
                await useApi().post('/bridging/inacbgs/collect-dokumen', {
                    'norec_pd': element.norec_pd,
                    'documentklaimfk': element.documentklaimfk,
                    'namafile': element.kodeexternal,
                    'tglregistrasi': element.tglregistrasi,
                    'api': element.api
                }).then((r) => {
                    loadingUpload.value = false
                    element.doc = r.filename
                    countPersen()
                }).catch((er) => {
                    loadingUpload.value = false
                })
            }
        }
    }
  }

  const collect = async (e: any) => {
  
    // H.printBlade(`emr/cetak-rencana-kontrol?noregistrasi=${e.noregistrasi}&pdf=true`);
      console.log('E', e)
      loadingUpload.value = true
      await useApi().post('/bridging/inacbgs/collect-dokumen', {
          'norec_pd': e.norec_pd,
          'documentklaimfk': e.documentklaimfk,
          'namafile': e.kodeexternal,
          'tglregistrasi': e.tglregistrasi,
          'api': e.api
      }).then((r) => {
          loadingUpload.value = false
          e.doc = r.filename
          countPersen()
      }).catch((er) => {
        loadingUpload.value = false
      })
  }
  
  const verifikasiDokumenModal = async (e: any) => {
      item.keteranganVerifikasi = '-'
      modalVerifikasi.value = true
      console.log('E SEP', e.nomor_sep)
      lastSEP.value = e.nomor_sep
      console.log('LAST SEP', lastSEP.value)
  }
  
  const verifikasiDokumen = async (e: any) => {
      isLoading.value = true
      await useApi().post('/bridging/inacbgs/verif-dokumen', {
          'norec_pd': NOREC_PD.value,
          'ketverif': item.keteranganVerifikasi
      }).then((r) => {
          isLoading.value = false
          modalVerifikasi.value = false
          fetchData()
      }).catch((er) => {
          isLoading.value = false
      })
  }
  
  const popSEP = (e:any) => {
      updatePA.nama_pasien = e.nama_pasien
      updatePA.nomor_sep = e.nomor_sep
      updatePA.nomor_kartu = e.nomor_kartu
      updatePA.norec = e.norec
      updatePA.noregistrasi = e.noregistrasi
      updatePA.nomor_rm = e.nomor_rm
      updatePA.kpid = e.kpid
      updatePA.nocmfk = e.nocmfk
      modalSEP.value = true
  }
  const savePemakaianAsuransi =async () => {
      if(updatePA.nomor_sep == undefined || updatePA.nomor_sep ==''){
          return
      }
      if(updatePA.nomor_kartu == undefined || updatePA.nomor_kartu ==''){
          return
      }
      let json = {
          'norec_pd' :  updatePA.norec ,
          'nomor_sep' :  updatePA.nomor_sep,
          'nomor_kartu' :  updatePA.nomor_kartu,
          'namapasien' :  updatePA.nama_pasien,
          'noregistrasi' :  updatePA.noregistrasi,
          'nocm' :  updatePA.nomor_rm,
          'kpid' :  updatePA.kpid,
          'nocmfk' : updatePA.nocmfk,
      }
      isLoading.value = true
      await useApi().postNoMessage('/bridging/inacbgs/save-pemakaian-asuransi',json)
      .then(async (r) => {
          isLoading.value = false
          modalSEP.value = false
          fetchData()
      })
  
  }
  
  // const exportExcel = (e: any) => {
  //     const workbook = XLSX.utils.book_new();
  //     const worksheet = XLSX.utils.aoa_to_sheet([
  //         ['Daftar Data Inacbg'],
  //         [],
  //         ['NO', 'TANGGAL CLOSING', 'NO CLOSING', 'TANGGAL AWAL', 'TANGGAL AKHIR', 'JENIS PAGU', 'DETAIL JENIS PAGU', 'TOTAL'],
  //         ...dataSource.value.map((e: any) => [
  //             e.no,
  //             e.tglclosing,
  //             e.noclosing,
  //             e.tglawal,
  //             e.tglakhir,
  //             e.jenispagu,
  //             e.detailjenispagu,
  //             e.jml ? H.roundToDecimal(parseFloat(e.jml), 2) : 0,
  //         ]),
  //         ['', 'TOTAL', '', '', '', '', '',
  //             H.roundToDecimal(parseFloat(item.value.Ttotal), 2),
  //         ]
  //     ]);
  //     // Mendefinisikan style untuk header(centered)
  //     const headerStyle = {
  //         alignment: {
  //             horizontal: 'center',
  //             vertical: 'center'
  //         },
  //         font: {
  //             color: { rgb: 'FFFFFF' }
  //         },
  //         fill: { fgColor: { rgb: '807C7C' } }
  //     };
  
  //     // Mendefinisikan range header
  //     const headerRange = XLSX.utils.decode_range(worksheet['!ref']);
  //     for (let col = headerRange.s.c; col <= headerRange.e.c; col++) {
  //         const headerCell = XLSX.utils.encode_cell({ r: 2, c: col });
  //         worksheet[headerCell].s = headerStyle;
  //     }
  
  //     const columnWidths = [5, 10, 13, 10, 10, 15, 20, 18];
  
  //     for (let col = headerRange.s.c; col <= headerRange.e.c; col++) {
  //         worksheet['!cols'] = worksheet['!cols'] || [];
  //         worksheet['!cols'][col] = { wch: columnWidths[col] };
  //     }
  
  //     // Centering the text in cell A1
  //     const titleCell = XLSX.utils.encode_cell({ r: 0, c: 0 });
  //     worksheet[titleCell].s = {
  //         alignment: {
  //             horizontal: 'center',
  //             vertical: 'center'
  //         },
  //         font: {
  //             bold: true,
  //             sz: 18
  //         }
  //     };
  
  //     // Menggabungkan dua baris pertama
  //     const mergeTitle = { s: { r: 0, c: 0 }, e: { r: 1, c: 7 } };
  //     worksheet['!merges'] = [mergeTitle];
  
  //     XLSX.utils.book_append_sheet(workbook, worksheet, 'Remun Jabatan', true);
  //     XLSXStyle.writeFile(workbook, 'Daftar Remun Jabatan.xlsx');
  // }
  const resumeMedis = (e: any) => {
    H.cacheHelper().set('xxx_cache_menu', undefined)
    H.cacheHelper().set('xxx_cache_menu_' + e.nocmfk, {
      'menu': [
          {   "label": "Resume Medis","icon": "pi pi-fw pi-file", "url_form": "resume-medis" }
      ],
      'active':'Resume Medis',
      'url_form':"resume-medis",
      'collection':'ResumeMedis'
    })
    router.push({
      name: 'module-emr-profile-pasien-page-emr-resume-medis',
      query: {
        nocmfk: e.nocmfk,
        norec_pd: e.norec,
        norec_apd: e.norec_apd,
      }
    })
  }
  currentPage.value.page = computed(() => {
    try {
      return Number.parseInt(route.query.page as string) || 1
    } catch { }
    return 1
  })
  onBeforeRouteLeave((to, from, next) => {
  if (to.name === from.name) {
    // Handle the refresh event here
    router.replace({
      query: {
        nocmfk: nocm_fk.value,
        norec_pd: norec_pasien_daftar.value,
      }
    })
  }

  next()
});
  watch(
      () =>currentPage.value.page,
      (newValue, oldValue) => {
          if(newValue!=oldValue){
            fetchData()
          }
      }
  )
  watch(
      () =>currentPage.value.limit,
      (newValue, oldValue) => {
          if(newValue!=oldValue){
            fetchData()
          }
      }
  )
  watch(
      () =>item.isNotSEP,
      (newValue, oldValue) => {
          if(newValue!=oldValue){
            fetchData()
          }
      }
  )
  watch(
      () =>item.isNotDiagnosis,
      (newValue, oldValue) => {
          if(newValue!=oldValue){
            fetchData()
          }
      }
  )
  watch(
      () =>modalKlaim.value,
      (newValue, oldValue) => {
          if(newValue!=oldValue){
            if(modalKlaim.value == false)
            fetchData()
          }
      }
  )
  watch(
      () =>item.isNotVerifikasi,
      (newValue, oldValue) => {
          if(newValue!=oldValue){
            fetchData()
          }
      }
  )
  watch(
      () =>item.isCatatan,
      (newValue, oldValue) => {
          if(newValue!=oldValue){
            fetchData()
          }
      }
  )
  watch(
      () =>item.isPenunjang,
      (newValue, oldValue) => {
          if(newValue!=oldValue){
            fetchData()
          }
      }
  )
  
  
  fetchDropdown()
  
  </script>
  
  <style lang="scss">
  @import '/@src/scss/abstracts/all';
  @import '/@src/scss/module/inacbgs/inacbgs';
  .highlight-gold {
    background-color: #D4AF37;
    color: white;
  }
  
  </style>
  