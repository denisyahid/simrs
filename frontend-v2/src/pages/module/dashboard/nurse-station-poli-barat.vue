
<template>
    <section>
      <div class="business-dashboard hr-dashboard">
        <div class="columns">
          <div class="column is-12">
            <div class="columns is-multiline">
              <!--Header-->
              <div class="column is-12">
                <div class=" illustration-header-2 columns is-multiline">
                  <div class="column is-2 p-0">
                    <div class="header-image">
                      <img src="/@src/assets/illustrations/dashboards/lifestyle/da.png" alt=""
                        style="max-width:75%; margin-left: 2rem; margin-bottom: 1rem;" />
                    </div>
                  </div>
                  <div class="column is-10 p-0">
                    <div class="header-meta">
                      <h3 style="color:white"><i class="fas fa-home"></i> Nurse Station</h3>
                      <p>
                        Selamat Datang , {{ userLogin.pegawai.namaLengkap }}
                      </p>
  
                      <div class="column is-5 p-0">
                        <VControl>
                          <MultiSelect v-model="sourceRuangan" display="chip" :options="d_Ruangan" optionLabel="label"
                            filter placeholder="Pilih Ruangan" :maxSelectedLabels="3" style="display:flex"
                            @change="changeRuang(sourceRuangan)" />
                        </VControl>
                        <VControl>
                          <VSwitchBlock v-model="item.isPasien" label="Pasien Dokter" color="danger"
                            v-if="kelompokUser == 'dokter'" />
                        </VControl>
                      </div>
                    </div>
                  </div>
  
                </div>
  
                <!-- <div class="illustration-header-2">
                <div class="header-image">
                  <img src="/@src/assets/illustrations/dashboards/lifestyle/da.png" alt=""
                    style="max-width:75%; margin-left: 2rem; margin-bottom: 1rem;" />
                </div> -->
  
                <!-- <div class="header-meta">
                  <h3 style="color:white"><i class="fas fa-home"></i> Instalasi Rawat Jalan</h3>
                  <p>
                    Selamat Datang , {{ userLogin.pegawai.namaLengkap }}
                  </p>
                  <div class="column is-2 p-0">
                    <VControl>
                      <MultiSelect v-model="sourceRuangan" display="chip" :options="d_Ruangan" optionLabel="label"
                        placeholder="Select Cities" :maxSelectedLabels="3"  />
                    </VControl>
                    <VControl>
                      <VSwitchBlock v-model="item.isPasien" label="Pasien Dokter" color="danger"
                        v-if="kelompokUser == 'dokter'" />
                    </VControl>
                  </div>
                </div> -->
  
                <!-- </div> -->
              </div>
  
              <div class="column" v-if="kelompokUser == 'dokter'">
                <div class="columns is-multiline">
                  <div class="column is-4">
                    <VCard>
                      <VBlock title="Total Pasien">
                        <template #icon>
                          <VIconBox color="danger" rounded>
                            <i class="fas fa-user-injured" aria-hidden="true"></i>
                          </VIconBox>
                        </template>
                        <VPlaceload v-if="isLoadCount" class="mx-2 mt-3" />
                        <span v-else style="font-weight: 500;margin-top: 7px;font-size: 15px;">{{ item.totalPasien }}</span>
                      </VBlock>
                    </VCard>
                  </div>
                  <div class="column is-4">
                    <VCard>
                      <VBlock title="Total Tindakan">
                        <template #icon>
                          <VIconBox color="success" rounded>
                            <i class="fas fa-procedures" aria-hidden="true"></i>
                          </VIconBox>
                        </template>
                        <VPlaceload v-if="isLoadCount" class="mx-2 mt-3" />
                        <span v-else style="font-weight: 500;margin-top: 7px;font-size: 15px;">{{ item.totalTindakan
                        }}</span>
                      </VBlock>
                    </VCard>
                  </div>
                  <div class="column is-4">
                    <VCard>
                      <VBlock title="Total Tindakan">
                        <template #icon>
                          <VIconBox color="warning" rounded>
                            <i class="fas fa-coins" aria-hidden="true"></i>
                          </VIconBox>
                        </template>
                        <VPlaceload v-if="isLoadCount" class="mx-2 mt-3" />
                        <span v-else style="font-weight: 500;margin-top: 7px;font-size: 15px;">{{
                          H.formatRp(item.pendapatanJasa, 'Rp.') }}</span>
                      </VBlock>
                    </VCard>
                  </div>
                </div>
              </div>
  
              <!--Incoming-->
              <div class="column is-12">
                <TabView class="tabview-custom " :scrollable="true" @tab-click="klikTab($event)">
                  <TabPanel>
                    <template #header>
                      <i class="fas fa-users mr-2" aria-hidden="true"></i>
                      <span>Daftar Pasien</span>
                      <Badge :value="dataPasien.total" v-if="dataPasien.total > 0" severity="danger" class="ml-2" />
                    </template>
  
                    <div v-if="activeTab == 0">
                      <div class="column is-6"
                        style="margin-left: 23rem;margin-bottom: 20px;padding: 0px;margin-top: -4.25rem;">
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
  
                      <div class="list-view list-view-v3">
                        <div class="search-menu mb-2">
                          <div class="search-location" style="width: 100%" v-if="!isReservasi">
                            <i class="iconify" data-icon="feather:search"></i>
                            <input type="text" placeholder="Cari Nama Pasien, No Registrasi, No RM, BPJS, Atau NIK"
                              v-model="item.search" v-on:keyup.enter="fetchPasien" />
                          </div>
                          <VButton raised class="search-button" @click="fetchPasien()" :loading="isLoading"> Cari Data
                          </VButton>
                        </div>
                        <VCard class="text-center pt-0 pb-0 mt-0">
                          <VRadio v-model="item.fStatusAntrian" value="Belum Dipanggil" label="Belum Periksa"
                            name="outlined_radio" color="warning" @click="setCache(item.fStatusAntrian)" />
                          <VRadio v-model="item.fStatusAntrian" value="" label="Semua" name="outlined_radio" color="success"
                            @click="setCache(item.fStatusAntrian)" />
                          <VRadio v-model="item.fStatusAntrian" value="Sudah Dipanggil" label="Sudah Dipanggil"
                            name="outlined_radio" color="danger" @click="setCache(item.fStatusAntrian)" style="display: none !important"/>
                          <VRadio v-model="item.fStatusAntrian" value="Selesai" label="Sudah Periksa"
                            name="outlined_radio" color="info" @click="setCache(item.fStatusAntrian)" />
                          <VRadio value="Ranap" label="Rawat Inap" name="outlined_radio" color="info"
                            @click="rawatInap(item)" style="display: none !important" />
                          <VRadio value="Reservasi" label="Reservasi" name="outlined_radio" color="danger" v-model="item.fStatusAntrian"
                            @click="reservasi()" />
                        </VCard>
                        <VPlaceholderPage :class="[dataPasien.length !== 0 && 'is-hidden']"
                          title="Tidak Ada Pasien Hari Ini."
                          subtitle="Silakan Pilih Tanggal dan Ruangan untuk melihat Data Pasien" larger>
                          <template #image>
                            <img class="light-image" src="/@src/assets/illustrations/placeholders/search-4.png" alt="" />
                            <img class="dark-image" src="/@src/assets/illustrations/placeholders/search-4-dark.svg"
                              alt="" />
                          </template>
                        </VPlaceholderPage>
                        <div class="list-view-inner mt-2" style="max-height:1000px;overflow: auto;">
                          <div name="list-complete" tag="div">

                            <div class="column" v-if="dataPasien.length > 0">

                              <DataTable :filters="ds_PASIEN_FILTER"  :value="dataPasien" class="p-datatable-md" :loading="isLoading" :paginator="true" :rows="100"
                              :rowsPerPageOptions="[5, 10, 25]" scrollable v-model:expandedRows="expandedRows"
                              scrollHeight="600px"
                              :metaKeySelection="metaKey"
                              selectionMode="single"
                              @rowSelect="onRowSelect"
                              v-model:selection="selectedPasien"
                              paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
                              responsiveLayout="stack" breakpoint="960px" sortMode="multiple"
                              currentPageReportTemplate="Showing {first} to {last} of {totalRecords}" showGridlines>

                                <template #empty> No customers found. </template>
                                <template #loading> Loading customers data. Please wait. </template>
                                <Column :expander="true" :style="{ width: '50px' }" />
                                <Column field="noantrian" header="Periksa" :sortable="true" style="min-width: 30px; text-align: center;">
                                  <template #body="slotProps">
                                    <VIconButton color="primary" circle icon="fas fa-stethoscope" outlined raised @click="showEditForm(slotProps.data)" v-tooltip.bottom.left="'Periksa'"></VIconButton>
                                  </template>
                                </Column>
                                <Column field="noantrian" header="Antrian" :sortable="true" style="min-width: 25px; text-align: center;"></Column>
                                <Column field="noregistrasi" header="No Registrasi" :sortable="true" style="min-width: 130px"></Column>
                                <Column field="tglkontrol" header="Surat Kontrol" :sortable="true" style="min-width: 190px; text-align: center;" :expander="true">
                                  <template #body="slotProps">
                                    <span class="icon" circle v-if="slotProps.data.tglkontrol != null"><i aria-hidden="true" class="fas fa-check" circle></i></span>
                                    <span class="icon" circle v-else><i aria-hidden="true" class="fas fa-minus" circle></i></span>
                                  </template>
                                </Column>
                                <Column field="nosep" header="No SEP" :sortable="true" style="min-width: 190px; text-align: center;" :expander="true">
                                  <template #body="slotProps">
                                    <span circle v-if="slotProps.data.nosep != null">
                                      {{ slotProps.data.nosep }}
                                    </span>
                                    <span class="icon" circle v-else><i aria-hidden="true" class="fas fa-minus" circle></i></span>
                                  </template>
                                </Column>
                                <Column field="nocm" header="NRM" :sortable="true" style="min-width: 100px"></Column>
                                <Column field="namapasien" header="Nama" :sortable="true" style="min-width: 150px"></Column>
                                <Column field="namalengkap" header="Dokter" :sortable="true" style="min-width: 150px"></Column>
                                <Column field="tanggal" header="Tanggal" :sortable="true" style="min-width: 120px"></Column>
                                <Column field="namaruangan" header="Section Tujuan" :sortable="true" style="min-width: 150px"></Column>
                                <Column field="kebangsaan" header="Kewarganegaraan" :sortable="true" style="min-width: 100px"></Column>
                                <Column field="kelompokpasien" header="Cara Bayar" :sortable="true" style="min-width: 100px"></Column>
                                <Column field="id_emr" header="Status Poli" :sortable="true" style="min-width: 140px; text-align: center;">
                                  <template #body="slotProps">
                                    <VTag class="ml-4" color="primary" rounded v-if="slotProps.data.id_emr != null">Sudah Periksa</VTag>
                                    <VTag class="ml-4" color="danger" rounded v-else>Belum Periksa</VTag>
                                  </template>
                                </Column>
                                <template #expansion="slotProps">
                                    <div class="orders-subtable">
                                        <h5>Diagnosa : {{ slotProps.data.diagnosaakhir }}</h5>
                                        <h5>Indikasi Kontrol : {{ slotProps.data.indikasikontrol }}</h5>
                                        <h5>Keterangan : {{ slotProps.data.catatan }}</h5>
                                    </div>
                                </template>
                              </DataTable>

                            </div>

                            <!-- <div v-for="(item, rowIndex) in dataPasien" :key="rowIndex" @dblclick="showEditForm(item)">
                              <div
                                v-if="rowGroupMetadata[item.namaruangan] && rowGroupMetadata[item.namaruangan].index === rowIndex">
                                <span style="font-weight: bold; font-size: 16px; font-family: var(--font-alt);">
                                  {{ item.namaruangan }}
                                </span>
                                <Badge :value="rowGroupMetadata[item.namaruangan].size"
                                  v-if="rowGroupMetadata[item.namaruangan].size > 0" class="ml-2 mt-2-min" />
                              </div>
                              <div class="list-view-item ">
                                <div class="list-view-item-inner">
                                  <VAvatar size="small" picture="/images/avatars/svg/pasien.svg" color="primary" bordered />
                                    
                                  <div class="meta-left">
                                    <VButton type="button" color="primary" rounded circle outlined raised v-tooltip.bottom.left="'R'">
                                      R
                                    </VButton>
                                    <VButton type="button" color="primary" rounded circle outlined raised v-tooltip.bottom.left="'B'">
                                      B
                                    </VButton>
                                    <VButton type="button" color="primary" rounded circle outlined raised v-tooltip.bottom.left="'P'">
                                      P
                                    </VButton>
                                    <h3 style="margin-top: 10px;">
                                      {{ item.namapasien }}
                                      <i :class="item.objectjeniskelaminfk == 2 ? 'fas fa-venus' : 'fas fa-mars'"
                                        aria-hidden="true"
                                        :style="'color:' + (item.objectjeniskelaminfk == 1 ? 'var(--light-blue)' : 'var(--pink)')"></i>
                                        <VTag :label="'Meninggal pada : ' + item.tglmeninggal" v-if="item.tglmeninggal != null" :color="'danger'"
                                        class="mr-2" />
                                    </h3>
                                    <span>
  
                                      <i aria-hidden="true" class="iconify" data-icon="ic:baseline-house"></i>
                                      <span>{{ item.namakotakabupaten }} / {{ item.namakecamatan }}</span><br>
                                      <i aria-hidden="true" class="iconify" data-icon="feather:clock"></i>
                                      <span>{{ item.tglregistrasi }}</span>
  
                                      <i aria-hidden="true" class="fas fa-circle icon-separator"></i>
                                      <i aria-hidden="true" class="iconify" data-icon="feather:check-circle"></i>
                                      <span>{{ item.noregistrasi }}</span>
                                      <i aria-hidden="true" class="fas fa-circle icon-separator"></i>
                                      <i aria-hidden="true" class="iconify" data-icon="feather:calendar"></i>
                                      <span>{{ item.umur }}</span>
                                      <i aria-hidden="true" class="fas fa-circle icon-separator"></i>
                                      <i aria-hidden="true" class="iconify" data-icon="feather:plus"></i>
                                      <span>{{ item.kelompokpasien }}</span>
                                      <i aria-hidden="true" class="fas fa-circle icon-separator"></i>
                                      <i aria-hidden="true" class="iconify" data-icon="feather:layers"></i>
                                      <span>{{ item.namakelas }}</span><br>
                                      <i aria-hidden="true" class="iconify" data-icon="feather:clipboard"></i>
                                      <span>{{ item.nocm }}</span>
                                      <i aria-hidden="true" class="fas fa-circle icon-separator"></i>
                                      <i aria-hidden="true" class="iconify" data-icon="feather:clipboard"></i>
                                      <span>{{ item.nobpjs }}</span>
                                      <i aria-hidden="true" class="fas fa-circle icon-separator"></i>
                                      <i aria-hidden="true" class="iconify" data-icon="teenyicons:id-outline"></i>
                                      <span>{{ item.noidentitas }}</span>
                                    </span>
                                    <div>
                                      <i class="fas fa-sort-amount-down-alt mr-2 mt-1" aria-hidden="true"></i>
                                      <VTag :label="item.noantrian" :color="'danger'" class="mr-2 mt-3-min"
                                        v-tooltip.bubble="'NOMOR ANTRIAN'" />
                                      <VTag :label="item.status == null ? 'Belum Dipanggil' : item.status"
                                        :color="item.status == null || item.status == 'Belum Dipanggil' ? 'warning' : (item.status == 'Selesai' ? 'success' : 'info')"
                                        class="mr-2" />
                                      <VTag :label="'SEP : ' + item.nosep" v-if="item.nosep != null" :color="'success'"
                                        class="mr-2" />
                                      <VTag :label="'Mobile JKN'" v-if="item.ismobilejkn == true" :color="'success'"
                                        class="mr-2" />
                                      <VTag :label="item.ischeckin == true ? 'Sudah Checkin' : 'Belum Checkin'"
                                        v-if="item.ismobilejkn == true"
                                        :color="item.ischeckin == true ? 'success' : 'danger'" class="mr-2" />
                                      <VTag :label="'TRACER : Dikirim'" v-if="item.isdikirim == true" :color="'info'"
                                        class="mr-2" />
                                    </div>
                                    <div>
                                      <span style="font-weight: bold;">DPJP :
                                        {{ item.namalengkap ? item.namalengkap : '-' }}
                                      </span>
                                    </div>
                                  </div>
                                  <div class="meta-right" v-if="item.objectstrukorderfk != null">
                                    <VIconButton v-tooltip.bottom.left="'Panggil Pasien'" label="Bottom Left"
                                      color="primary" circle icon="feather:phone" @click="panggil(item)"
                                      :loading="item.loading" />
  
                                    <VIconButton v-tooltip.bottom.left="'Jawab Konsul'" label="Bottom Left" color="danger"
                                      circle icon="fas fa-arrow-right" @click="jawab(item)" :loading="item.isLoadingTN"
                                      style="margin-right: 10px; margin-left: 10px" v-if="kelompokUser == 'dokter'" />
  
                                    <VIconButton v-tooltip.bottom.left="'Konsultasi Dokter'" label="Bottom Left"
                                      color="danger" circle icon="fas fa-book" @click="jawab(item)"
                                      :loading="item.isLoadingTN" style="margin-right: 10px; margin-left: 10px" v-else />
  
  
                                    <VIconButton color="primary" circle icon="fas fa-stethoscope" outlined raised
                                      @click="emr(item)" v-tooltip.bottom.left="'Periksa'">
                                    </VIconButton>
                                  </div>
                                  <div class="meta-right" v-else>
                                    <div class="buttons">
                                      <VIconButton v-tooltip.bottom.left="'Panggil Pasien'" label="Bottom Left"
                                        color="primary" circle icon="feather:phone" @click="panggil(item)"
                                        :loading="item.loading" />
  
                                      <VIconButton color="primary" circle icon="fas fa-stethoscope" outlined raised
                                        @click="emr(item)" v-tooltip.bottom.left="'Periksa'">
                                      </VIconButton>
                                      <VIconButton color="primary" circle icon="pi pi-ellipsis-v" raised
                                        @click="toggleOP($event, item)" v-tooltip.bottom.left="'AKSI'">
                                      </VIconButton>
  
                                    </div>
                                  </div>
                                </div>
                              </div>
  
                            </div> -->
                          </div>
  
                        </div>
                        <VFlexPagination v-model:current-page="currentPage.page" :item-per-page="currentPage.limit"
                          :total-items="dataPasien.total" :max-links-displayed="5">
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
                                      <option :value="3">3 results per page</option>
                                      <option :value="5">5 results per page</option>
                                      <option :value="6">6 results per page</option>
                                      <option :value="10">10 results per page</option>
                                      <option :value="15">15 results per page</option>
                                      <option :value="25">25 results per page</option>
                                      <option :value="50">50 results per page</option>
                                      <option :value="100">100 results per page</option>
                                      <option :value="200">200 results per page</option>
                                      <option :value="500">500 results per page</option>
                                      <option :value="1000">1000 results per page</option>
                                      <option :value="10000">All</option>
                                    </select>
                                  </div>
                                </VControl>
                              </VField>
                            </VFlex>
                          </template>
                        </VFlexPagination>
                      </div>
                    </div>
                    <div v-if="activeTab == 1">
  
                      <div class="column is-6"
                        style="margin-left: 25rem;margin-bottom: 20px;padding: 0px;margin-top: -4.25rem;">
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
                      <div class="list-view list-view-v3">
                        <div class="search-menu" style="margin-bottom : 1rem;">
                          <div class="search-location" style="width: 100%" v-if="isReservasi">
                            <i class="iconify" data-icon="feather:search"></i>
                            <input type="text" placeholder="Cari Nama Pasien, No Reservasi, No RM, BPJS, Atau NIK"
                              v-model="item.qsearch" v-on:keyup.enter="fetchReservasi()" />
                          </div>
                          <!-- <div class="search-location">
                              <i class="iconify" data-icon="feather:activity"></i>
                              <input type="text" placeholder="No Reservasi" v-model="item.qnorev" />
                            </div>
                            <div class="search-job">
                              <i class="iconify" data-icon="feather:user"></i>
                              <input type="text" placeholder="Nama Pasien" v-model="item.qnamapasien" />
                            </div> -->
                          <VButton color="primary" raised class="search-button" @click="fetchReservasi()"
                            :loading="isLoadingTT"> Cari Data </VButton>
                        </div>
                        <VCard class="text-center pt-0 pb-0 mt-0">
                          <VRadio v-model="item.fStatusAntrian" value="Belum Dipanggil" label="Belum Periksa"
                            name="outlined_radio" color="warning" @click="setCache(item.fStatusAntrian)" />
                          <VRadio v-model="item.fStatusAntrian" value="" label="Semua" name="outlined_radio" color="success"
                            @click="setCache(item.fStatusAntrian)" />
                          <VRadio v-model="item.fStatusAntrian" value="Sudah Dipanggil" label="Sudah Dipanggil"
                            name="outlined_radio" color="danger" @click="setCache(item.fStatusAntrian)" style="display: none !important"/>
                          <VRadio v-model="item.fStatusAntrian" value="Selesai" label="Sudah Periksa"
                            name="outlined_radio" color="info" @click="setCache(item.fStatusAntrian)" />
                          <VRadio value="Ranap" label="Rawat Inap" name="outlined_radio" color="info"
                            @click="rawatInap(item)" style="display: none !important" />
                          <VRadio value="Reservasi" label="Reservasi" name="outlined_radio" color="danger" v-model="item.fStatusAntrian"
                            @click="reservasi()" />
                        </VCard>
                        <div class="list-view-inner" style="max-height:300px;overflow: auto;">
                          <div name="list-complete" tag="div">
                            <!--Item-->
                            <div v-for="item in dataReservasi" :key="item.id" class="list-view-item">
                              <div class="list-view-item-inner">
                                <VAvatar size="small" picture="/images/avatars/svg/pasien.svg" color="primary" bordered />
                                <div class="meta-left">
                                  <VButton type="button" color="primary" rounded circle outlined raised v-tooltip.bottom.left="'R'" style="background-color: #c4faa5;">
                                      R
                                    </VButton>
                                    <VButton type="button" color="primary" rounded circle outlined raised v-tooltip.bottom.left="'B'">
                                      B
                                    </VButton>
                                    <VButton type="button" color="primary" rounded circle outlined raised v-tooltip.bottom.left="'P'">
                                      P
                                    </VButton>
                                  <h3 style="margin-top: 10px;">
                                    {{ item.namapasien }}
                                  </h3>
                                  <span>
                                    <i aria-hidden="true" class="iconify" data-icon="ic:baseline-house"></i>
                                    <span>{{ item.namakotakabupaten }} / {{ item.namakecamatan }}</span><br>
                                    <i aria-hidden="true" class="iconify" data-icon="feather:map-pin"></i>
                                    <span>{{ item.namaruangan }}</span>
                                    <i aria-hidden="true" class="fas fa-circle icon-separator"></i>
                                    <i aria-hidden="true" class="iconify" data-icon="feather:clock"></i>
                                    <span>{{ item.tanggalreservasi }}</span>
                                    <i aria-hidden="true" class="fas fa-circle icon-separator"></i>
                                    <i aria-hidden="true" class="iconify" data-icon="feather:check-circle"></i>
                                    <span>{{ item.noreservasi }}</span>
  
                                    <i aria-hidden="true" class="fas fa-circle icon-separator"></i>
                                    <i aria-hidden="true" class="iconify" data-icon="feather:credit-card"></i>
                                    <span>{{ item.kelompokpasien }}</span><br>
                                    <i aria-hidden="true" class="iconify" data-icon="feather:clipboard"></i>
                                    <span>{{ item.nocm }}</span>
                                    <i aria-hidden="true" class="fas fa-circle icon-separator"></i>
                                    <i aria-hidden="true" class="iconify" data-icon="feather:clipboard"></i>
                                    <span>{{ item.nobpjs }}</span>
                                    <i aria-hidden="true" class="fas fa-circle icon-separator"></i>
                                    <i aria-hidden="true" class="iconify" data-icon="teenyicons:id-outline"></i>
                                    <span>{{ item.noidentitas }}</span><br>
                                    <i aria-hidden="true" class="iconify" data-icon="ic:baseline-house"></i>
                                    <span>{{ item.namakotakabupaten }}</span>
                                  </span>
                                  <br>
                                  <VTag :label="item.status" :color="item.status_c" v-if="!item.ismobilejkn" class="mr-1" />
                                  <VTag label="Mobile JKN" :color="item.status_c" v-if="item.ismobilejkn" class="mr-1" />
                                  <VTag :label="item.ischeckin == true ? 'Sudah Checkin' : 'Belum Checkin'"
                                    :color="item.status_c" v-if="item.ismobilejkn" />
                                </div>
                                <div class="meta-right">
                                  <button class="button v-button is-dark-outlined" @click="confirmReservasi(item)"
                                    v-if="item.isconfirm == null && item.ismobilejkn != true">
                                    <span class="icon">
                                      <i aria-hidden="true" class="iconify" data-icon="feather:arrow-right"></i>
                                    </span>
                                    <span>Confirm</span>
                                  </button>
                                  <button class="button v-button is-dark-outlined" @click="RegistrasiKeun(item)"
                                    v-else="item.nocm == null && item.isconfirm == null">
                                    <span class="icon">
                                      <i aria-hidden="true" class="iconify" data-icon="feather:arrow-right"></i>
                                    </span>
                                    <span>Confirm</span>
                                  </button>
                                  <button class="button v-button is-dark-outlined" @click="checkinJKN(item)"
                                    :loading="isLoading" v-else="item.ischeckin != true && item.ismobilejkn == true">
                                    <span class="icon">
                                      <i aria-hidden="true" class="iconify" data-icon="feather:arrow-right"></i>
                                    </span>
                                    <span>Check In</span>
                                  </button>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                      </div>
                  </TabPanel>
                  <TabPanel>
                    <template #header>
                      <i class="fas fa-check-circle mr-2" aria-hidden="true"></i>
                      <span>Daftar Reservasi</span>
                      <Badge :value="dataReservasi.length" v-if="dataReservasi.length > 0" severity="danger" class="ml-2" />
                    </template>
                    <div v-if="activeTab == 1">
  
                      <div class="column is-6"
                        style="margin-left: 25rem;margin-bottom: 20px;padding: 0px;margin-top: -4.25rem;">
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
                      <div class="list-view list-view-v3">
                        <div class="search-menu" style="margin-bottom : 1rem;">
                          <div class="search-location" style="width: 100%" v-if="isReservasi">
                            <i class="iconify" data-icon="feather:search"></i>
                            <input type="text" placeholder="Cari Nama Pasien, No Reservasi, No RM, BPJS, Atau NIK"
                              v-model="item.qsearch" v-on:keyup.enter="fetchReservasi()" />
                          </div>
                          <!-- <div class="search-location">
                              <i class="iconify" data-icon="feather:activity"></i>
                              <input type="text" placeholder="No Reservasi" v-model="item.qnorev" />
                            </div>
                            <div class="search-job">
                              <i class="iconify" data-icon="feather:user"></i>
                              <input type="text" placeholder="Nama Pasien" v-model="item.qnamapasien" />
                            </div> -->
                          <VButton color="primary" raised class="search-button" @click="fetchReservasi()"
                            :loading="isLoadingTT"> Cari Data </VButton>
                        </div>
                        <div class="list-view-inner" style="max-height:300px;overflow: auto;">
                          <div name="list-complete" tag="div">
                            <!--Item-->
                            <div v-for="item in dataReservasi" :key="item.id" class="list-view-item">
                              <div class="list-view-item-inner">
                                <VAvatar size="small" picture="/images/avatars/svg/pasien.svg" color="primary" bordered />
                                
                                <div class="meta-left">
                                  <VButton type="button" color="primary" rounded circle outlined raised v-tooltip.bottom.left="'R'" style="background-color: #c4faa5;">
                                      R
                                    </VButton>
                                    <VButton type="button" color="primary" rounded circle outlined raised v-tooltip.bottom.left="'B'">
                                      B
                                    </VButton>
                                    <VButton type="button" color="primary" rounded circle outlined raised v-tooltip.bottom.left="'P'">
                                      P
                                    </VButton>
                                  <h3 style="margin-top: 10px;">
                                    {{ item.namapasien }}
                                  </h3>
                                  <span>
                                    <i aria-hidden="true" class="iconify" data-icon="ic:baseline-house"></i>
                                    <span>{{ item.namakotakabupaten }} / {{ item.namakecamatan }}</span><br>
                                    <i aria-hidden="true" class="iconify" data-icon="feather:map-pin"></i>
                                    <span>{{ item.namaruangan }}</span>
                                    <i aria-hidden="true" class="fas fa-circle icon-separator"></i>
                                    <i aria-hidden="true" class="iconify" data-icon="feather:clock"></i>
                                    <span>{{ item.tanggalreservasi }}</span>
                                    <i aria-hidden="true" class="fas fa-circle icon-separator"></i>
                                    <i aria-hidden="true" class="iconify" data-icon="feather:check-circle"></i>
                                    <span>{{ item.noreservasi }}</span>
  
                                    <i aria-hidden="true" class="fas fa-circle icon-separator"></i>
                                    <i aria-hidden="true" class="iconify" data-icon="feather:credit-card"></i>
                                    <span>{{ item.kelompokpasien }}</span><br>
                                    <i aria-hidden="true" class="iconify" data-icon="feather:clipboard"></i>
                                    <span>{{ item.nocm }}</span>
                                    <i aria-hidden="true" class="fas fa-circle icon-separator"></i>
                                    <i aria-hidden="true" class="iconify" data-icon="feather:clipboard"></i>
                                    <span>{{ item.nobpjs }}</span>
                                    <i aria-hidden="true" class="fas fa-circle icon-separator"></i>
                                    <i aria-hidden="true" class="iconify" data-icon="teenyicons:id-outline"></i>
                                    <span>{{ item.noidentitas }}</span><br>
                                    <i aria-hidden="true" class="iconify" data-icon="ic:baseline-house"></i>
                                    <span>{{ item.namakotakabupaten }}</span>
                                  </span>
                                  <br>
                                  <VTag :label="item.status" :color="item.status_c" v-if="!item.ismobilejkn" class="mr-1" />
                                  <VTag label="Mobile JKN" :color="item.status_c" v-if="item.ismobilejkn" class="mr-1" />
                                  <VTag :label="item.ischeckin == true ? 'Sudah Checkin' : 'Belum Checkin'"
                                    :color="item.status_c" v-if="item.ismobilejkn" />
                                </div>
                                <div class="meta-right">
                                  <button class="button v-button is-dark-outlined" @click="confirmReservasi(item)"
                                    v-if="item.isconfirm == null && item.ismobilejkn != true">
                                    <span class="icon">
                                      <i aria-hidden="true" class="iconify" data-icon="feather:arrow-right"></i>
                                    </span>
                                    <span>Confirm</span>
                                  </button>
                                  <button class="button v-button is-dark-outlined" @click="RegistrasiKeun(item)"
                                    v-else="item.nocm == null && item.isconfirm == null">
                                    <span class="icon">
                                      <i aria-hidden="true" class="iconify" data-icon="feather:arrow-right"></i>
                                    </span>
                                    <span>Confirm</span>
                                  </button>
                                  <button class="button v-button is-dark-outlined" @click="checkinJKN(item)"
                                    :loading="isLoading" v-else="item.ischeckin != true && item.ismobilejkn == true">
                                    <span class="icon">
                                      <i aria-hidden="true" class="iconify" data-icon="feather:arrow-right"></i>
                                    </span>
                                    <span>Check In</span>
                                  </button>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </TabPanel>
  
                </TabView>
  
              </div>
            </div>
          </div>
  
          <div class="column is-3" style="display: none !important">
            <VCard>
              <VIconButton circle class="is-pulled-right" icon="feather:refresh-cw" raised bold @click="fetchDataChart()"
                :loading="isLoadingCall" />
              <div class="dashboard-card is-gauge">
                <div class="column border-custom mb-2">
                  <span style="font-weight: bold; font-size: 16px; font-family: var(--font-alt);">Grafik Pelayanan Pasien
                  </span>
                </div>
                <ApexChart id="apex-chart-22" :height="270" :type="'pie'" :series="chartStatus.series"
                  :options="chartStatus">
                </ApexChart>
              </div>
            </VCard>
  
            <VCard>
              <VButton type="button" color="primary" rounded circle outlined raised v-tooltip.bottom.left="'P'" class="buttsize" style="background-color: #c4faa5;">
                <font class="picon">P</font>
              </VButton> <font class="pfont">Pasien Lengkap Sudah Approved</font>
              <br><br>
              <VButton type="button" color="primary" rounded circle outlined raised v-tooltip.bottom.left="'P'" class="buttsize" style="background-color: #a5e6fa;">
                <font class="picon">P</font>
              </VButton> <font class="pfont">Pasien Periksa Sudah Approved</font>
              <br><br>
              <VButton type="button" color="primary" rounded circle outlined raised v-tooltip.bottom.left="'P'" class="buttsize" style="background-color: #f1f5ae;">
                <font class="picon">P</font>
              </VButton> <font class="pfont">Pasien Periksa Sudah Draft</font>
              <br><br>
              <VButton type="button" color="primary" rounded circle outlined raised v-tooltip.bottom.left="'B'" class="buttsize" style="background-color: #a5e6fa;">
                <font class="picon">B</font>
              </VButton> <font class="pfont">Pasien Periksa Sudah Bayar</font>
              <br><br>
              <VButton type="button" color="primary" rounded circle outlined raised v-tooltip.bottom.left="'R'" class="buttsize" style="background-color: #c4faa5;">
                <font class="picon">R</font>
              </VButton> <font class="pfont">Pasien Reservasi</font>
            </VCard>
  
            
  
            <div class="column">
              <VButton rounded color="info" class="is-pulled-left mr-2 mt-2" icon="fas fa-users" raised bold
                @click="accKonsul" v-if="kelompokUser == 'dokter'">
                Verifikasi Konsultasi
                <Badge :value="totalKonsul" severity="danger" class="ml-2" v-if="totalKonsul > 0" />
              </VButton>
            </div>
            <UIWidget class="search-widget" style="margin-top: 1.7rem;">
              <template #body>
                <div class="field">
                  <div class="control">
                    <input v-model="filters" class="input custom-text-filter" placeholder="Cari Dokter Praktek" />
                    <button class="searcv-button" @click="fetchJadwalDokter()">
                      <i aria-hidden="true" class="iconify" data-icon="feather:search"></i>
                    </button>
                  </div>
                </div>
              </template>
            </UIWidget>
  
            <div class="column border-custom mb-2 mt-5-min">
              <span style="font-weight: bold; font-size: 16px; font-family: var(--font-alt);">Jadwal Dokter {{
                item.namaruangan ? item.namaruangan : '' }}
              </span>
            </div>
            <div class="tile-grid tile-grid-v2">
              <VPlaceholderPage :class="[dataDokter.length !== 0 && 'is-hidden']" title="Tidak Ada Dokter Praktek Hari Ini."
                subtitle=" Silakan Pilih Ruangan untuk Melihat Jadwal Praktek Dokter" larger>
                <template #image>
                  <img class="light-image" src="/@src/assets/illustrations/placeholders/search-4.png" alt="" />
                  <img class="dark-image" src="/@src/assets/illustrations/placeholders/search-4-dark.svg" alt="" />
                </template>
              </VPlaceholderPage>
  
              <!--Tile Grid v1-->
  
              <div name="list" tag="div" class="columns is-multiline">
                <!--Grid item-->
                <div class="columns is-multiline p-2" style="max-height:500px;overflow: auto;">
                  <div v-for="item in dataDokter" :key="item.id" class="column is-12 p-0 pb-2 pl-2 pr-2 ">
                    <div class="tile-grid-item">
                      <div class="tile-grid-item-inner">
                        <VAvatar size="small" picture="/images/avatars/svg/dokter.svg" color="primary" bordered />
                        <div class="meta">
                          <span class="dark-inverted text-elipsis-wrap" style="width:200px !important">{{ item.namalengkap
                          }}</span>
                          <span>
                            <i aria-hidden="true" class="iconify" data-icon="feather:clock" style="padding-right: 3px;"></i>
                            {{ item.jammulai }} s.d {{ item.jamakhir }}</span>
                          <!-- <span>
                          <i aria-hidden="true" class="iconify" data-icon="feather:home" style="padding-right: 3px;"></i>
                          {{ item.namaruangan }}</span> -->
  
                        </div>
                        <VTag style="margin-left: auto;" color="info" label="Tag Label" rounded elevated> {{ item.hari }}
                        </VTag>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
  
            </div>
            <div class="column border-custom mb-2" style="margin-top: 2rem;">
              <span style="font-weight: bold; font-size: 16px; font-family: var(--font-alt);">Stok Produk
                <VIconButton v-tooltip.bottom.right="'Order Barang'" label="Bottom Right" color="primary" circle
                  icon="feather:shopping-cart" style="margin-left: 13rem;" @click="orderBarang()" />
              </span>
            </div>
            <div class="tile-grid tile-grid-v2">
  
              <!--List Empty Search Placeholder -->
              <VPlaceholderPage :class="[dataStok.length !== 0 && 'is-hidden']" title="Tidak Ada Stok Produk."
                subtitle=" Silakan Pilih Ruangan untuk Stok Produk" larger>
                <template #image>
                  <img class="light-image" src="/@src/assets/illustrations/placeholders/search-4.png" alt="" />
                  <img class="dark-image" src="/@src/assets/illustrations/placeholders/search-4-dark.svg" alt="" />
                </template>
              </VPlaceholderPage>
  
              <!--Tile Grid v1-->
  
              <div name="list" tag="div" class="columns is-multiline">
                <!--Grid item-->
                <div class="columns is-multiline p-2" style="max-height:300px;overflow: auto;">
                  <div v-for="item in dataStok" :key="item.id" class="column is-6">
                    <div class="tile-grid-item">
                      <div class="tile-grid-item-inner">
                        <VAvatar size="small" picture="/images/simrs/produk-ico.png" color="primary" bordered />
                        <div class="meta">
                          <span class="dark-inverted">{{ item.namaproduk }}</span>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- modal uabh dokter dpjp-->
        <Dialog v-model:visible="modalChangeDokter" modal header="Form Ubah Dokter" :style="{ width: '25vw' }">
          <div class="column">
            <span style="font-weight: 500;">Dokter </span>
            <VField class="is-autocomplete-select pt-3">
              <VControl icon="feather:search">
                <AutoComplete v-model="item.dokterPemeriksa" :suggestions="d_Dokter" @complete="fetchDokter($event)"
                  :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                  :field="'label'" placeholder="ketik Nama Dokter" />
              </VControl>
            </VField>
          </div>
          <template #footer>
            <VButton color="danger" icon="pi pi-times" outlined raised @click="modalChangeDokter = false"> Batal </VButton>
            <VButton color="primary" icon="pi pi-check" raised @click="saveChangeDokter()" :loading="btnLoadSimpan"> Update
            </VButton>
          </template>
        </Dialog>
        <Dialog v-model:visible="modalPilihPerawat" modal header="Form Pilih Perawat" :style="{ width: '25vw' }">
          <div class="column">
            <span style="font-weight: 500;">Perawat</span>
            <VField class="is-autocomplete-select pt-3">
              <VControl icon="feather:search">
                <AutoComplete v-model="item.perawatfk" :suggestions="d_Perawat" @complete="fetchPerawat($event)"
                  :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                  :field="'label'" placeholder="ketik Nama Perawat" />
              </VControl>
            </VField>
          </div>
          <template #footer>
            <VButton color="danger" icon="pi pi-times" outlined raised @click="modalPilihPerawat = false"> Batal </VButton>
            <VButton color="primary" icon="pi pi-check" raised @click="savePilihPerawat()" :loading="btnLoadSimpan"> Update
            </VButton>
          </template>
        </Dialog>
        <!-- modal uabh dokter dpjp-->
      </div>
  
      <VModal :open="modalBatalRegis" title="Batal Registrasi" size="medium" actions="right"
        @close="modalBatalRegis = false" cancelLabel="Tutup">
        <template #content>
          <div class="columns is-multiline">
            <div class="column is-6">
              <VField>
                <VLabel class="required-field">Tanggal Batal</VLabel>
                <VDatePicker v-model="item.tanggalpembatalan" mode="dateTime" style="width: 100%" trim-weeks
                  :max-date="new Date()">
                  <template #default="{ inputValue, inputEvents }">
                    <VField>
                      <VControl icon="feather:calendar" fullwidth>
                        <VInput :value="inputValue" placeholder="Tanggal" v-on="inputEvents" disabled />
                      </VControl>
                    </VField>
                  </template>
                </VDatePicker>
              </VField>
            </div>
            <div class="column is-6">
              <VField>
                <VLabel class="required-field">Ruangan</VLabel>
                <VControl icon="feather:map-pin">
                  <VInput type="text" v-model="item.namaruangan" placeholder="Tempat Lahir" class="is-rounded_Z" disabled />
                </VControl>
              </VField>
            </div>
            <div class="column is-12">
              <span style="margin-bottom:1rem;font-weight: bold; font-size: 12px; font-family: var(--font-alt);">Alasan
                Pembatalan
              </span>
  
              <VField>
                <VControl>
                  <VTextarea class="textarea is-rounded" v-model="item.alasanpembatalan" rows="4"
                    placeholder="Alasan Pembatalan" autocomplete="off" autocapitalize="off" spellcheck="true" />
                </VControl>
              </VField>
            </div>
  
          </div>
        </template>
        <template #action>
          <VButton icon="feather:plus" color="primary" @click="saveBatalRegis()" :loading="isLoading" raised>Simpan
          </VButton>
        </template>
      </VModal>

      <VModal :open="modalAsesmenNurse" title="Assesmen Awal" size="medium" actions="right"
        @close="modalBatalRegis = false" cancelLabel="Tutup">
        <template #content>
          <!-- <AsesmenAwal></AsesmenAwal> -->
        </template>
        <template #action>
          <VButton icon="feather:plus" color="primary" @click="saveBatalRegis()" :loading="isLoading" raised>Simpan
          </VButton>
        </template>
      </VModal>
  
      <OverlayPanel ref="op" appendTo="body" style="width:500px">
        <div class="columns is-multiline">
          <div class="column is-4 pt-1 pb-1 ">
            <VButton type="button" icon="fas fa-print" class="w-100" circle outlined color="success" raised
              :loading="btnLoadCetak" @click="cetakSEP()">
              Cetak SEP
            </VButton>
          </div>
          <div class="column is-4 pt-1 pb-1 pl-0">
            <VButton type="button" icon="fas fa-user-edit" class="w-100" circle outlined color="warning" raised
              @click="openModalDpjp(item)">
              Ubah DPJP
            </VButton>
          </div>
          <div class="column is-4 pt-1 pb-1 pl-0">
            <VButton type="button" icon="fas fa-user-nurse" class="w-100" circle outlined color="black" raised
              @click="openModaPilihPerawat()">
              Pilih Perawat
            </VButton>
          </div>
          <div class="column is-4 pt-1 pb-1">
            <VButton type="button" icon="fas fa-times-circle" class="w-100" circle outlined color="danger" raised
              @click="batalRegis(selectedItem)">
              Batal Registrasi
            </VButton>
          </div>
  
          <div class="column is-4 pt-1 pb-1 pl-0">
            <VButton type="button" icon="fas fa-user-edit" class="w-100" circle outlined color="info" raised
              @click="pasienPulang(selectedItem)">
              Pulang
            </VButton>
          </div>
          <div class="column is-4 pt-1 pb-1 pl-0">
            <VButton type="button" icon="fas fa-times-circle" class="w-100" circle outlined color="danger" raised
              @click="pasienMeninggal(selectedItem)">
              Meninggal
            </VButton>
          </div>
  
          <div class="column is-4 pt-1 pb-1 ">
            <VButton type="button" icon="fas fa-envelope" class="w-100" circle outlined color="success" raised
              :loading="btnLoadCetak" @click="suratKontrol(selectedItem)">
              Surat Kontrol
            </VButton>
          </div>
  
        </div>
  
      </OverlayPanel>
  
      <Dialog v-model:visible="modalInput" modal header="Konsultasi" :style="{ width: '60vw' }">
        <div class="columns is-multiline">
          <div class="column is-3">
            <VDatePicker class="pt-0 pb-0 pl-0" v-model="item.tanggal" color="green" trim-weeks mode="dateTime"
              :max-date="new Date()">
              <template #default="{ inputValue, inputEvents }" class="pb-0">
                <VField>
                  <VLabel class="required-field">Tanggal</VLabel>
                  <VControl icon="feather:calendar">
                    <VInput type="text" placeholder="Select a date" :value="inputValue" v-on="inputEvents"
                      class="is-rounded" />
                  </VControl>
                </VField>
              </template>
            </VDatePicker>
          </div>
          <div class="column is-3">
            <VField>
              <VLabel>Ruangan Asal</VLabel>
              <VControl icon="feather:bookmark">
                <input v-model="item.ruanganasal" type="text" class="input is-rounded" placeholder="Lain-lain " disabled />
              </VControl>
            </VField>
          </div>
          <div class="column is-3">
            <VField>
              <VLabel>Ruangan Tujuan</VLabel>
              <VControl icon="feather:bookmark">
                <input v-model="item.ruangantujuan" type="text" class="input is-rounded" placeholder="Lain-lain "
                  disabled />
              </VControl>
            </VField>
          </div>
          <div class="column is-3">
            <VField>
              <VLabel>Dokter</VLabel>
              <VControl icon="feather:bookmark">
                <input v-model="item.dokter" type="text" class="input is-rounded" placeholder="Lain-lain " disabled />
              </VControl>
            </VField>
          </div>
  
  
          <div class="column is-3">
            <VField>
              <VControl>
                <VSwitchBlock v-model="item.rawatBersama" label="Rawat Bersama" color="danger" disabled />
              </VControl>
            </VField>
          </div>
          <div class="column is-3">
            <VField>
              <VControl>
                <VSwitchBlock v-model="item.konsultasi" label="Konsultasi" color="danger" disabled />
              </VControl>
            </VField>
          </div>
          <div class="column is-6">
            <VField>
              <VLabel>Lain-lain</VLabel>
              <VControl icon="feather:bookmark">
                <input v-model="item.lainlain" type="text" class="input is-rounded" placeholder="Lain-lain " disabled />
              </VControl>
            </VField>
          </div>
          <div class="column is-12">
            <VField>
              <VLabel>Keterangan</VLabel>
              <VControl>
                <VTextarea v-model="item.keterangan" rows="3" placeholder="Keterangan" disabled>
                </VTextarea>
              </VControl>
            </VField>
          </div>
          <div class="column is-12" v-if="kelompokUser == 'dokter'">
            <VField>
              <VLabel>Jawaban</VLabel>
              <VControl>
                <VTextarea v-model="item.jawaban" rows="3" placeholder="Jawaban">
                </VTextarea>
              </VControl>
            </VField>
          </div>
          <div class="column is-12" v-else>
            <VField>
              <VLabel>Jawaban</VLabel>
              <VControl>
                <VTextarea v-model="item.jawaban" rows="3" placeholder="Jawaban" disabled>
                </VTextarea>
              </VControl>
            </VField>
          </div>
        </div>
        <template #footer>
          <VButton icon="feather:refresh-cw rem-100" light dark-outlined @click="kembaliKeun()">
            Batal
          </VButton>
          <VButton type="button" rounded outlined color="primary" raised icon="feather:save" :loading="isLoadingTN"
            @click="simpan()" v-if="kelompokUser == 'dokter'"> Simpan
          </VButton>
        </template>
      </Dialog>
  
      <Dialog v-model:visible="modalAcc" modal header="Konsultasi" :style="{ width: '60vw' }">
        <DaftarKonsul />
        <template #footer>
          <VButton icon="lnir lnir-arrow-left rem-100" light dark-outlined @click="modalAcc = false">
            Tutup
          </VButton>
        </template>
      </Dialog>
  
      <Dialog v-model:visible="modalDed" modal header="Form Pasien Meninggal" :style="{ width: '40vw' }">
        <div class="column is-12">
          <div class="columns is-multiline">
            <div class="column is-6">
              <VField label="Status Keluar" class="is-rounded-select  is-autocomplete-select" v-slot="{ id }">
                <VControl icon="feather:plus-circle" fullwidth>
                  <Multiselect mode="single" v-model="item.statuskeluar" :options="d_StatusKeluar" placeholder="Pilih data"
                    :searchable="true" :attrs="{ id }" autocomplete="off" track-by="value" />
  
                </VControl>
              </VField>
            </div>
            <div class="column is-6">
              <VField label="Kondisi Keluar" class="is-rounded-select  is-autocomplete-select" v-slot="{ id }">
                <VControl icon="feather:plus-circle" fullwidth>
                  <Multiselect mode="single" v-model="item.kondisipasien" :options="d_KondisiPasien"
                    placeholder="Pilih data" :searchable="true" :attrs="{ id }" autocomplete="off" track-by="value" />
  
                </VControl>
              </VField>
            </div>
            <div class="column is-6">
              <VField label="Tanggal Meninggal" class="is-rounded-select">
                <VControl class="prime-auto ">
                  <div class="is-rounded is-rounded-select">
                    <Calendar v-model="item.tglmeninggal" selectionMode="single" :manualInput="true"
                      class="w-100 is-rounded" showTime :showIcon="true" hourFormat="24" :date-format="'yy-mm-dd'" />
                  </div>
                </VControl>
              </VField>
            </div>
            <div class="column is-6">
              <VField label="Penyebab Meninggal">
                <VControl icon="feather:home">
                  <VInput type="text" v-model="item.keteranganpenyebabkematian" placeholder="Penyebab Meninggal"
                    class="is-rounded" />
                </VControl>
              </VField>
            </div>
  
          </div>
        </div>
  
        <template #footer>
  
          <VButton color="primary" icon="pi pi-check" raised @click="simpanMeninggal()" :loading="btnLoadSimpan"> Simpan
          </VButton>
        </template>
      </Dialog>
  
      <Dialog v-model:visible="modalPulang" modal header="Form Pasien Pulang" :style="{ width: '40vw' }">
        <div class="column is-12">
          <div class="columns is-multiline">
            <div class="column is-4">
              <VField label="NOCM">
                <VControl icon="feather:home">
                  <VInput type="text" v-model="item.nocmpasien" placeholder="Nocm" class="is-rounded" disabled />
                </VControl>
              </VField>
            </div>
            <div class="column is-4">
              <VField label="Nama Pasien">
                <VControl icon="feather:home">
                  <VInput type="text" v-model="item.namalengkappasien" placeholder="Nama Lengkap Pasien" class="is-rounded"
                    disabled />
                </VControl>
              </VField>
            </div>
            <div class="column is-4">
              <VField label="Ruangan">
                <VControl icon="feather:home">
                  <VInput type="text" v-model="item.ruangan" placeholder="Ruangan" class="is-rounded" disabled />
                </VControl>
              </VField>
            </div>
            <div class="column is-4">
              <VField label="Status Keluar" class="is-rounded-select  is-autocomplete-select" v-slot="{ id }">
                <VControl icon="feather:plus-circle" fullwidth>
                  <Multiselect mode="single" v-model="item.statuskeluar2" :options="d_StatusKeluar" placeholder="Pilih data"
                    :searchable="true" :attrs="{ id }" autocomplete="off" track-by="value" />
                </VControl>
              </VField>
            </div>
            <div class="column is-4">
              <VField label="Kondisi Keluar" class="is-rounded-select  is-autocomplete-select" v-slot="{ id }">
                <VControl icon="feather:plus-circle" fullwidth>
                  <Multiselect mode="single" v-model="item.kondisipasien2" :options="d_KondisiPasien"
                    placeholder="Pilih data" :searchable="true" :attrs="{ id }" autocomplete="off" track-by="value" />
                </VControl>
              </VField>
            </div>
            <div class="column is-4">
              <VField label="Status Pulang" class="is-rounded-select  is-autocomplete-select" v-slot="{ id }">
                <VControl icon="feather:plus-circle" fullwidth>
                  <Multiselect mode="single" v-model="item.statuspulang" :options="d_StatusPulang" placeholder="Pilih data"
                    :searchable="true" :attrs="{ id }" autocomplete="off" track-by="value" />
                </VControl>
              </VField>
            </div>
            <!-- <div class="column is-12">
              <VField label="Konsep" class="is-rounded-select  is-autocomplete-select" v-slot="{ id }">
                <VControl icon="feather:plus-circle" fullwidth>
                  <Multiselect mode="single" v-model="item.prognosiscodeableconcept" :options="d_konsep"
                    placeholder="Pilih data" :searchable="true" :attrs="{ id }" autocomplete="off" track-by="value" />
                </VControl>
              </VField>
            </div> -->
            <!-- <div class="column is-6">
              <VField label="Prognosis">
                <VControl icon="feather:home">
                  <VInput type="text" v-model="item.prognosis" placeholder="Prognosis" class="is-rounded" />
                </VControl>
              </VField>
            </div> -->
            <div class="column is-6">
              <VField label="Tanggal Pulang" class="is-rounded-select">
                <VControl class="prime-auto ">
                  <div class="is-rounded is-rounded-select">
                    <Calendar v-model="item.tglpulang" selectionMode="single" :manualInput="true" class="w-100 is-rounded"
                      showTime :showIcon="true" hourFormat="24" :date-format="'yy-mm-dd'" />
                  </div>
                </VControl>
              </VField>
            </div>
            <div class="column is-6">
              <VField label="Rencana Kontrol BPJS" class="is-rounded-select">
                <VControl class="prime-auto ">
                  <VSwitchBlock v-model="item.kontrol" :options="d_status" label="Aktif" color="danger" />
                </VControl>
              </VField>
            </div>
          </div>
        </div>
  
        <template #footer>
  
          <VButton color="primary" icon="pi pi-check" raised @click="savePasienPulang()" :loading="btnLoadSimpan"> Simpan
          </VButton>
        </template>
      </Dialog>
      <Dialog :header="'Rencana Kontrol/Inap'" v-model:visible="modalKontrol"
        :breakpoints="{ '960px': '75vw', '640px': '90vw' }" :style="{ width: '65vw' }" :maximizable="true" :modal="true">
        <SuratKontrol :items="item.pasienKontrol" @savePulang="savePasienPulangAgain"></SuratKontrol>
      </Dialog>
    </section>
  </template>
  <script setup lang="ts">
  import { useRoute, useRouter } from 'vue-router'
  import { ref, computed, reactive, watch, inject, onMounted } from 'vue'
  import { useThemeColors } from '/@src/composable/useThemeColors'
  import { useApi } from '/@src/composable/useApi'
  import { useUserSession } from '/@src/stores/userSession'
  import { useHead } from '@vueuse/head'
  import moment, { isDate } from 'moment'
  import ApexChart from 'vue3-apexcharts'
  import * as H from '/@src/utils/appHelper'
  import { useViewWrapper } from '/@src/stores/viewWrapper'
  import MultiSelect from 'primevue/multiselect';
  import TabView from 'primevue/tabview';
  import TabPanel from 'primevue/tabpanel';
  import Badge from 'primevue/badge';
  import { state, socket } from "/@src/socket.js";
  import sleep from '/@src/utils/sleep';
  import AutoComplete from 'primevue/autocomplete';
  import Dialog from 'primevue/dialog';
  import OverlayPanel from 'primevue/overlaypanel';
  import DaftarKonsul from '../registrasi/daftar-konsultasi.vue'
  import * as qzService from '/@src/utils/qzTrayService'
  import Calendar from 'primevue/calendar';
  import RujukanKeluar from '../integrasi-sistem/rujukan.vue'
  import SuratKontrol from '../registrasi/modal-kontrol.vue';
  import DataTable from 'primevue/datatable';
  //import AsesmenAwal from '../../page-emr/assesmen-awal-keperawatan-pasien-rawat-jalan.vue'
  import Column from 'primevue/column'
  import { FilterMatchMode } from 'primevue/api';
  import InputText from 'primevue/inputtext';

  
  useHead({
    title: 'Dashboard Rawat Jalan - ' + import.meta.env.VITE_PROJECT,
  })
  useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
  useViewWrapper().setFullWidth(true)
  
  
  const activeTab = ref(0);
  const op = ref();
  const date = new Date();
  const dateNow = date.toLocaleString('id-ID', { year: "numeric", month: "long", day: "numeric" });
  const modalBatalRegis = ref(false)
  const themeColors = useThemeColors()
  const userLogin = useUserSession().getUser()
  const total = ref(0)
  const router = useRouter()
  const modalInput = ref(false)
  const isLoadingCall = ref(false)
  const isLoadingTN = ref(false)
  const route = useRoute()
  const rowGroupMetadata: any = ref({})
  const modalChangeDokter = ref(false)
  const modalPilihPerawat = ref(false)
  const modalAcc = ref(false)
  const modalDed = ref(false)
  const modalKontrol = ref(false)
  const modalPulang = ref(false)
  const modalAsesmenNurse = ref(false)
  const itemSource: any = ref([])
  const d_Dokter: any = ref([])
  const d_Perawat: any = ref([])
  const d_StatusKeluar: any = ref([])
  const d_StatusPulang: any = ref([])
  const d_KondisiPasien: any = ref([])
  const btnLoadSimpan: any = ref(false)
  const btnLoadBtlRegis: any = ref(false)
  const btnLoadCetak: any = ref(false)
  const selectedItem: any = ref({})
  const isReservasi: any = ref([])
  const saveSuratKontrol: any = ref(false)
  const expandedRows: any = ref([]);    
  const d_status = [
    { value: 't', label: 'True' },
    { value: 'f', label: 'False' },
  ]
  const kelompokUser = 'nurse-station-poli-barat'
  const kelompokUserID = 66
  const pegawaiId = useUserSession().getUser().pegawai.id
  let ds_PASIEN_FILTER: any = ref({
  noregistrasi: {value: null, matchMode: FilterMatchMode.CONTAINS},
  namapasien: {value: null, matchMode: FilterMatchMode.CONTAINS},
  nocm: {value: null, matchMode: FilterMatchMode.CONTAINS},
  noantrian: {value: null, matchMode: FilterMatchMode.CONTAINS},
  namaruangan: {value: null, matchMode: FilterMatchMode.CONTAINS},
});
  const selectedPasien = ref();
  let doubleclick = null; 
  const metaKey = ref(true);   
  let counterSelect: number = 0;
  
  const item:any = ref({
    isPasien: kelompokUser === 'dokter',
    ruangans: [],
    aktif: true,
    fStatusAntrian: 'Belum Dipanggil',
    tanggalpembatalan: new Date(),
    filterTgl: reactive({
      start: new Date(),
      end: new Date(),
    }),
    tglmeninggal: new Date(),
    tglpulang: new Date()
  })
  const chart: any = ref({
    aktif: true
  })
  
  const currentPage: any = ref({
    limit: 50,
    rows: 50,
  })
  
  let ID_RUANGAN = useRoute().query.id as string
  let sourceOrder: any = ref([])
  let dataStok: any = ref([])
  let dataKonsul: any = ref([])
  let dataDokter: any = ref([])
  let dataPasien: any = ref([])
  let totalKonsul: any = ref([])
  let dataReservasi: any = ref([])
  let d_Ruangan: any = ref([])
  let sourceRuangan: any = ref([])
  let isLoading: any = ref(false)
  let isLoadingTT: any = ref(false)
  let isLoadCount: any = ref(false)
  let chartStatus: any = ref({
    series: [],
  })
  let countRuangan: any = ref([])
  const filters = ref('')
  
  const d_konsep = ref([
    {
      value: "PR000001",
      label: "Prognosis baik",
    }, {
      value: "PR000002",
      label: "Prognosis dubia et bonam / cenderung baik",
    }, {
      value: "PR000003",
      label: "Prognosis dubia et malam / cenderung tidak baik",
    }, {
      value: "PR000004",
      label: "Prognosis tidak baik",
    }
  ])
  
  
  const toggleOP = (event: any, item: any) => {
    selectedItem.value = item
    op.value.toggle(event);
  }
  
  const fetchdDropdown = async () => {
    const response = await useApi().get(`/dashboard/dropdown-rawat-jalan-nurse?idkelompokuser=${kelompokUserID}`)
    d_Ruangan.value = response.ruangannurse.map((e: any) => { return { label: e.namaruangan, value: e.id, default: e } })
    d_StatusKeluar.value = response.statuskeluar.map((e: any) => { return { label: e.statuskeluar, value: e.id, default: e } })
    d_KondisiPasien.value = response.kondisipasien.map((e: any) => { return { label: e.kondisipasien, value: e.id, default: e } })
    d_StatusPulang.value = response.statuspulang.map((e: any) => { return { label: e.statuspulang, value: e.id, default: e } })
  
    d_Ruangan.value.forEach((element) => {
      sourceRuangan.value.push(element)
    })

    fetchPasien()
  }
  
  const fetchPasien = async (e: any) => {
  
    if (H.cacheHelper().get('statusberobat')) {
      item.value.fStatusAntrian = H.cacheHelper().get('statusberobat')
    }
    let dari = `?dari=${H.formatDate(item.value.filterTgl.start, 'YYYY-MM-DD')}`
    let sampai = `&sampai=${H.formatDate(item.value.filterTgl.end, 'YYYY-MM-DD')}`
    let idPegawai = item.value.isPasien == true ? `&idpegawai=${H.pegawaiLogin().id}` : ''
    let limit: any = currentPage.value.limit
    let page: any = route.query.page ? route.query.page : 1
    let ruanganid = ''
    if (sourceRuangan.value != undefined) {
      let itemsRuang = []
      sourceRuangan.value.forEach((element: any) => {
        itemsRuang = [...new Set([...itemsRuang, element.value])]
      });
      ruanganid = `&ruanganfk=${itemsRuang}`
    }
    let search = item.value.search ? `&search=${item.value.search}` : ''
    let namapasien = item.value.qnama ? `&namapasien=${item.value.qnama}` : ''
    let nocm = item.value.qnocm ? `&nocm=${item.value.qnocm}` : ''
    let qnoreg = item.value.qnoreg ? `&noregistrasi=${item.value.qnoreg}` : ''
    let statuspanggil = item.value.fStatusAntrian ? `&statuspanggil=${item.value.fStatusAntrian}` : ''
    isReservasi.value = false
    isLoading.value = true
    isLoadCount.value = true
    dataPasien.value = []
    await useApi().get(`/dashboard/rawat-jalan-pasien-nurse${dari}${sampai}${idPegawai}${ruanganid}${namapasien}${nocm}${qnoreg}${statuspanggil}${search}&page=${page}&limit=${limit}`).then((response) => {
      isLoading.value = false
      response.data.sort(compare);
      dataPasien.value = response.data
      dataPasien.value.total = response.total
    })
    if (kelompokUser == 'dokter') {
      countPasien(dari, sampai, idPegawai, e)
    }
    updateRowGroupMetaData();
  }
  const compare = (a: any, b: any) => {
    if (a.namaruangan > b.namaruangan) {
      return -1;
    }
    if (a.namaruangan < b.namaruangan) {
      return 1;
    }
    return 0;
  }
  
  const fetchReservasi = async (e: any) => {
  
    let dari = `?dari=${H.formatDate(item.value.filterTgl.start, 'YYYY-MM-DD')}`
    let sampai = `&sampai=${H.formatDate(item.value.filterTgl.end, 'YYYY-MM-DD')}`
    let idPegawai = item.value.isPasien == true ? `&idpegawai=${H.pegawaiLogin().id}` : ''
    let ruanganid = e != undefined ? `&ruanganid=${e}` : ''
    let namapasien = item.value.qnamapasien ? `&namapasien=${item.value.qnamapasien}` : ''
    let noreservasi = item.value.qnorev ? `&nocm=${item.value.qnorev}` : ''
    let search = item.value.qsearch ? `&search=${item.value.qsearch}` : ''
    isReservasi.value = true
    isLoading.value = true
    dataReservasi.value = []
    await useApi().get(`/dashboard/rawat-jalan-reservasi${dari}${sampai}${ruanganid}${idPegawai}${namapasien}${noreservasi}${search}`).then((response) => {
      isLoading.value = false;
      dataReservasi.value = response.reservasi
    })
  }
  const fetchKonsul = async () => {
    let dari = `?dari=${H.formatDate(item.value.filterTgl.start, 'YYYY-MM-DD')}`
    let sampai = `&sampai=${H.formatDate(item.value.filterTgl.end, 'YYYY-MM-DD')}`
    let idPegawai = `?idpegawai=${H.pegawaiLogin().id}`
  
    isLoading.value = true
    totalKonsul.value = []
    await useApi().get(`/dashboard/get-jumlah-konsul${idPegawai}`).then((response) => {
      isLoading.value = false;
      totalKonsul.value = response
    })
  }
  
  const fetchJadwalDokter = async () => {
    let namaDokter = ''
    if (filters.value != undefined) {
      namaDokter = filters.value
    }
    let idPegawai = item.value.isPasien == true ? `&idpegawai=${H.pegawaiLogin().id}` : ''
  
    dataDokter.value = []
    dataStok.value = []
  
    const response = await useApi().get(
      '/dashboard/rawat-jalan-detail?namadokter=' + namaDokter + '&idPegawai' + idPegawai + '&limit=10'
    )
    dataDokter.value = response.dokter
    dataStok.value = response.produk
  }
  
  const fetchPerawat = async (filter: any) => {
    // let data = filter.query ? filter.query : filter
    await useApi().get(
      `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`
    ).then((response) => {
      d_Perawat.value = response
    })
  }
  
  
  const changeRuang = async (e: any) => {
  
    fetchPasien()
    fetchReservasi()
    // fetchJadwalDokter(items)
  }
  const reload = async () => {
    fetchPasien()
    fetchReservasi()
  }
  
  if (userLogin.mapLoginUserToRuangan.length) {
    for (let i = 0; i < userLogin.mapLoginUserToRuangan.length; i++) {
      const element = userLogin.mapLoginUserToRuangan[i];
      if (element.departemen.toLowerCase().indexOf('rawat jalan') > -1) {
        item.namaruangan = element.namaruangan
        item.departemen = element.departemen
        item.id_ruangan = element.id
        item.id_departemen = element.objectdepartemenfk
        break
      }
    }
  }
  const fetchDataChart = async () => {
    let dari = ''
    if (item.value.filterTgl.start) {
      dari = H.formatDate(item.value.filterTgl.start, 'YYYY-MM-DD')
    }
    let sampai = ''
    if (item.value.filterTgl.end) {
      sampai = H.formatDate(item.value.filterTgl.end, 'YYYY-MM-DD')
    }
    let ruanganid = ''
    if (item.value.filterRuangan) {
      ruanganid = item.value.filterRuangan
    }
    isLoadingCall.value = true
    await useApi()
      .get(`/dashboard/get-pelayanan-status?ruanganid=${ruanganid}&dari=${dari}&sampai=${sampai}`)
      .then((response: any) => {
        isLoadingCall.value = false
        chart.value = response
        chartStatus.value = {
          series: response.chartStatus.series,
          chart: {
            height: 300,
            type: 'pie',
          },
          colors: [
            'rgb(250, 173, 66)',
            'rgb(3, 152, 226)',
            'rgb(6, 214, 158)',
            themeColors.purple,
            themeColors.orange,
          ],
          labels: response.chartStatus.labels,
          responsive: [
            {
              breakpoint: 270,
              options: {
                chart: {
                  width: 300,
                  toolbar: {
                    show: false,
                  },
                },
                legend: {
                  position: 'bottom',
                },
              },
            },
          ],
          legend: {
            position: 'bottom',
            horizontalAlign: 'center',
          },
        }
        countRuangan.value = response
        countRuangan.value.total = response.length
      })
  }
  const filter = () => {
    fetchPasienTotal()
  }
  const klikTab = (e: any) => {
    activeTab.value = e.index
    if (activeTab.value == 0) {
      fetchPasien()
    }
    if (activeTab.value == 1) {
      fetchReservasi()
    }
  }
  const orderBarang = () => {
    router.push({
      name: 'module-logistik-order-barang'
    })
  }
  const panggil = async (e: any) => {
    e.loading = true
  
    await socket.emit('call-antrian-poli', {
      namapasien: e.namapasien,
      namaruangan: e.namaruangan,
      noantri: e.noantrian,
      nocm: e.nocm,
      norec: e.norec_apd,
    });
  
    sendAntrol(e.norec_pd)
    if (e.status == 'Belum Dipanggil') {
      useApi().post(
      `/dashboard/rawat-jalan/panggil`,
        {
          'norec_apd': e.norec_apd,
          'norec_pd' :e.norec_pd,
          "telemedicine" : {
              "nocm" :  e.nocm,
              "namapasien" :  e.namapasien,
              "namaruangan" :  e.namaruangan,
              "idruangan" :  e.objectruanganfk.toString(),
              "noantrian" :  e.noantrian.toString(),
          }
        }
      ).then((response: any) => {
        e.status = response.status
        e.loading = false
        fetchDataChart()
      }).catch((e: any) => {
        e.loading = false
      })
    }
    await sleep(1000)
    e.loading = false
  }
  
  const setCache = (e: any) => {
    H.cacheHelper().set('statusberobat', e)
    activeTab.value = 0
  }

  const onRowSelect = (event) => {
    counterSelect++;
    if(counterSelect == 1) {
      doubleclick = setTimeout(() => {
        console.log("abis ler")
        counterSelect = 0;
      }, 500);
    }else {
      clearTimeout(doubleclick);
      counterSelect = 0;
      showEditForm(selectedPasien.value);
    }
  }
  
  const emr = (e: any) => {
    H.checkAksesEMR(e,kelompokUser,kelompokUserID,pegawaiId)
    H.cacheHelper().set('xxx_cache_menu_' +e.nocmfk, undefined)
    H.cacheHelper().set('xxx_cache_menu', undefined)
    sendAntrol(e.norec_pd)
    router.push({
      name: 'module-emr-profile-pasien',
      query: {
        nocmfk: e.nocmfk,
        norec_pd: e.norec_pd,
        norec_apd: e.norec_apd,
      }
    })
  }
  
  const rawatInap = (e: any) => {
    router.push({
      name: 'module-dashboard-rawat-inap',
      query: {
      }
    })
  }
  
  const suratKontrol = (e: any) => {
    router.push({
      name: 'module-integrasi-sistem-rencana-kontrol',
      query: {
        nocmfk: e.nocmfk,
        norec_pd: e.norec_pd,
        norec_apd: e.norec_apd,
      }
    })
  }
  
  const showEditForm = (e: any) => {
    console.log(e)
    router.push({
      name: 'module-emr-profile-pasien',
      query: {
        nocmfk: e.nocmfk,
        norec_pd: e.norec_pd,
        norec_apd: e.norec_apd,
        kelompokuser: kelompokUser
      }
    })
  }
  
  const reservasi = () => {
    activeTab.value = 1
    fetchReservasi()
  }
  
  const updateRowGroupMetaData = () => {
    rowGroupMetadata.value = {};
  
    if (dataPasien.value) {
      for (let i = 0; i < dataPasien.value.length; i++) {
        let rowData = dataPasien.value[i];
        let namaruangan = rowData.namaruangan;
  
        if (i == 0) {
          rowGroupMetadata.value[namaruangan] = { index: 0, size: 1 };
        }
        else {
          let previousRowData = dataPasien.value[i - 1];
          let previousRowGroup = previousRowData.namaruangan;
          if (namaruangan === previousRowGroup)
            rowGroupMetadata.value[namaruangan].size++;
          else
            rowGroupMetadata.value[namaruangan] = { index: i, size: 1 };
        }
      }
    }
  }
  
  const countPasien = (awal: any, akhir: any, pegawai: any, ruanganfk: any) => {
  
    let ruangan = ruanganfk ? `&ruanganfk=${ruanganfk}` : ''
    useApi().get(`dashboard/get-combo-jumlah${awal}${akhir}${pegawai}${ruangan}`).then((response) => {
      item.value.totalPasien = response.jumlahPasien
      item.value.totalTindakan = response.jumlahTindakan
      item.value.pendapatanJasa = response.pendapatanJasa.total
      isLoadCount.value = false
    })
  }
  
  const openModalDpjp = (data: any) => {
    modalChangeDokter.value = true
    item.value.dokterPemeriksa = selectedItem.value.pgid ? { value: selectedItem.value.pgid, label: selectedItem.value.namalengkap } : ''
  }
  
  const openModaPilihPerawat = (data: any) => {
    modalPilihPerawat.value = true
    item.value.perawatfk = selectedItem.value.perawatfk ? { value: selectedItem.value.perawatfk, label: selectedItem.value.perawat } : ''
  }
  
  
  const fetchDokter = async (filter: any) => {
    await useApi().get(
      `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&settingdatafix=objectjenispegawaifk,idJenisPegawaiDokter&limit=10`
    ).then((response) => {
      d_Dokter.value = response
    })
  }
  
  const saveChangeDokter = async () => {
    if (!item.value.dokterPemeriksa) {
      H.alert('error', 'Dokter Wajib Dipilih')
      return
    }
    btnLoadSimpan.value = true
    await useApi().post('registrasi/change-dokter-dpjp', { 'norec_pd': selectedItem.value.norec_pd, 'objectpegawaifk': item.value.dokterPemeriksa.value }).then((response) => {
      btnLoadSimpan.value = false
      modalChangeDokter.value = false
      fetchPasien()
    }).catch((err) => {
      console.log(err)
    })
  }
  
  const savePilihPerawat = async () => {
  
    if (!item.value.perawatfk) {
      H.alert('error', 'Perawat Wajib Dipilih')
      return
    }
    btnLoadSimpan.value = true
    await useApi().post('registrasi/tetapkan-perawat', { 'norec_pd': selectedItem.value.norec_pd, 'perawatfk': item.value.perawatfk.value }).then((response) => {
      btnLoadSimpan.value = false
      modalPilihPerawat.value = false
      delete item.value.perawatfk
      fetchPasien()
    }).catch((err) => {
      console.log(err)
    })
  }
  
  const cetakSEP = () => {
    btnLoadCetak.value = true
    qzService.printData('registrasi/pemakaian-asuransi/sep?noregistrasi=' + selectedItem.value.noregistrasi + "&pdf=true", 'SEP', 1)
    btnLoadCetak.value = false
  }
  const confirmReservasi = (e: any) => {
    console.log(e)
    router.push({
      name: 'module-registrasi-registrasi-ruangan',
      query: {
        nocmfk: e.nocmfk,
        noreservasi: e.noreservasi,
        norec_online: e.norec,
        tanggalreservasi: e.tanggalreservasi,
        ruangan: e.objectruanganfk,
        dokter: e.objectpegawaifk,
        dokter_name: e.dokter,
        kelompok: e.objectkelompokpasienfk,
      },
    })
  }
  const RegistrasiKeun = (e: any) => {
    router.push({
      name: 'module-registrasi-pasien-baru',
      query: {
  
      },
    })
  }
  
  const checkinJKN = async (e: any) => {
    isLoading.value = true
    let json = {
      'kodebooking': e.noreservasi
    }
    await useApi()
      .postNoMessage(`/dashboard/checkin-jkn`, json)
      .then((response: any) => {
        isLoading.value = false
        if (response.metaData.code == 200) {
          H.alert('success', response.metaData.message);
          reload()
        } else {
          H.alert('error', response.metaData.message);
        }
      })
      .catch((e: any) => {
        isLoading.value = false
      })
  }

  const filterOnChange = (value: any, column: string, typeFilter: string) => {
    ds_PASIEN_FILTER.value[column].value = value;
}
  
  const saveBatalRegis = async () => {
    if (!item.value.alasanpembatalan) { H.alert('warning', 'Alasan Pembatalan harus di isi'); return }
    let json = {
      pasiendaftar: {
        'norec_pd': item.value.norec_pd,
        'tanggalpembatalan': H.formatDate(item.value.tanggalpembatalan, 'YYYY-MM-DD HH:mm:ss'),
        'alasanpembatalan': item.value.alasanpembatalan,
        'noregistrasi': item.value.noregistrasi,
        'namapasien': item.value.namapasien,
        'nocm': item.value.nocm,
        'ruangan': item.value.namaruangan,
      },
      antrianpasiendiperiksa: {
        'norec_apd': item.value.norec_apd,
      }
  
    }
    btnLoadBtlRegis.value = true
    await useApi()
      .post(`/dashboard/save-batal-registrasi`, json)
      .then((response: any) => {
        isLoading.value = false
        reload()
        clear()
      })
      .catch((e: any) => {
        isLoading.value = false
      })
    modalBatalRegis.value = false
  }
  const clear = () => {
  
  delete item.value.statuskeluar,
  delete item.value.kondisipasien,
  delete item.value.keteranganpenyebabkematian,
  delete item.value.tglmeninggal
  
  }
  const batalRegis = (e: any) => {
    item.value.namaruangan = e.namaruangan
    item.value.norec_pd = e.norec_pd
    item.value.norec_apd = e.norec_apd
    item.value.noregistrasi = e.noregistrasi
    item.value.namapasien = e.namapasien
    item.value.nocm = e.nocm
    item.value.norec_apd = e.norec_apd
    modalBatalRegis.value = true
  }
  
  const sendAntrol = async (norec_pd) => {
    const jsont4 = {
      "noregistrasifk": norec_pd,
      "taskid": 4,
      "waktu": new Date().getTime(),
    }
    await useApi()
      .postNoMessage(`/bridging/antrol/sendTaskId`, jsont4)
      .then(async (response: any) => {
        const jsont5 = {
          "noregistrasifk": norec_pd,
          "taskid": 5,
          "waktu": new Date().getTime(),
        }
        await useApi()
          .postNoMessage(`/bridging/antrol/sendTaskId`, jsont5)
          .then((response: any) => { })
      })
  }
  const konsul = (e: any) => {
    router.push({
      name: 'module-registrasi-daftar-konsultasi',
      query: {
      }
    })
  }
  
  
  
  const edit = async (e: any) => {
    // console.log(e)
    e.isLoadingTN = true
    // isLoadingTN.value = true
    await useApi().get(`dashboard/get-detail-konsul?norec_apd=${e.norec_apd}`).then((response) => {
      response.forEach((element: any, i: any) => {
        e.isLoadingTN = false
        item.value.norec = element.norec
        item.value.ruanganasal = element.ruanganasal
  
        item.value.tanggal = new Date(element.tglorder)
  
        item.value.ruanganasal = element.ruanganasal
        item.value.ruangantujuan = element.ruangantujuan
        item.value.dokter = element.dokter
  
  
        item.value.konsultasi = element.konsultasi ? element.konsultasi : false
        item.value.lainlain = element.lainlain ? element.lainlain : ''
        item.value.rawatBersama = element.rawatbersama ? element.rawatbersama : false
        item.value.keterangan = element.keteranganorder ? element.keteranganorder : ''
        item.value.jawaban = element.keteranganlainnya ? element.keteranganlainnya : ''
        element.no = i + 1
      });
    })
  
    modalInput.value = true
  
  
  }
  
  const simpan = async () => {
  
    let formData = {
      'norec_so': item.value.norec,
      'jawaban': item.value.jawaban,
    }
    isLoadingTN.value = true
    await useApi().post('/emr/jawab-order-konsul', formData).then((r) => {
      isLoadingTN.value = false
      fetchPasien()
      modalInput.value = false
    }).catch((e: any) => {
      isLoadingTN.value = false
    })
  }
  
  const jawab = async (e: any) => {
  
  
    edit(e)
  }
  
  const accKonsul = () => {
    modalAcc.value = true
  }
  
  const pasienMeninggal = (e: any) => {
  
    item.value.NOREC_PD = e.norec_pd
    item.value.NOREC_APD = e.norec_apd
    item.value.nocm = e.nocm
  
    modalDed.value = true
  }
  
  const simpanMeninggal = async () => {
  
    if (!item.value.statuskeluar) {
      H.alert('error', 'Status Keluar Dipilih')
      return
    }
  
    let json = {
      pasiendaftar: {
        norec_pd: item.value.NOREC_PD,
        objectstatuskeluarfk: item.value.statuskeluar,
        objectkondisipasienfk: item.value.kondisipasien,
        keteranganpenyebabkematian: item.value.keteranganpenyebabkematian,
        tglmeninggal: H.formatDate(item.value.tglmeninggal, 'YYYY-MM-DD HH:mm:ss'),
      },
      antrianpasiendiperiksa: {
        norec_apd: item.value.NOREC_APD,
        tglkeluar: H.formatDate(item.value.tglmeninggal, 'YYYY-MM-DD HH:mm:ss'),
      },
      pasien: {
        nocm: item.value.nocm,
        tglmeninggal: H.formatDate(item.value.tglmeninggal, 'YYYY-MM-DD HH:mm:ss'),
      }
    }
    isLoading.value = true
    await useApi()
      .post(`/dashboard/save-meninggal-rj`, json)
      .then((response: any) => {
        isLoading.value = false
        modalDed.value = false
        riwayatPasien()
        clear()
      })
      .catch((e: any) => {
        isLoading.value = false
      })
  }
  
  const pasienPulang = (e: any) => {
  
    item.value.nocmpasien = e.nocm
    item.value.namalengkappasien = e.namapasien
    item.value.ruangan = e.namaruangan
    item.value.pdnorec = e.norec_pd
    modalPulang.value = true
    item.value.pasienKontrol = e
  }
  
  const savePasienPulang = async () => {
    if (item.value.kontrol) {
      showModalControl()
    } else {
      let json = {
        pasiendaftar: {
          norec_pd: item.value.pdnorec,
          noregistrasi: item.value.noreg,
          objectstatuspulangfk: item.value.statuspulang,
          objectstatuskeluarfk: item.value.statuskeluar2,
          objectkondisipasienfk: item.value.kondisipasien2,
          // objectpenyebabkematianfk: item.value.objectpenyebabkematianfk ? item.value.objectpenyebabkematianfk : null,
          // tglpulang: H.formatDate(item.value.tglpulang, 'YYYY-MM-DD HH:mm:ss'),
          // prognosis: item.value.prognosis ? item.value.prognosis : null,
          // prognosiscodeableconcept_kode: item.value.prognosiscodeableconcept_kode ? item.value.prognosiscodeableconcept_kode : null,
          // prognosiscodeableconcept: item.value.prognosiscodeableconcept ? item.value.prognosiscodeableconcept : null,
        }
      }
      isLoading.value = true
      await useApi()
        .post(`/dashboard/save-pulang-rj`, json)
        .then((response: any) => {
          isLoading.value = false
          modalPulang.value = false
          riwayatPasien()
        })
        .catch((e: any) => {
          isLoading.value = false
        })
    }
  }
  
  const showModalControl = () => {
    modalKontrol.value = true
    modalPulang.value = false
    item.rencanaKontrolBPJS = false;
    item.value.isSave = false;
  }
  const savePasienPulangAgain = async () => {
    item.value.kontrol = false;
  }
  const kembaliKeun = () => {
    modalInput.value = false
  }
  currentPage.value.page = computed(() => {
    try {
      return Number.parseInt(route.query.page as string) || 1
    } catch { }
    return 1
  })
  watch(
    () => currentPage.value.page,
    (newValue, oldValue) => {
      if (newValue != oldValue) {
        fetchPasien()
      }
    }
  )
  watch(
    () => currentPage.value.limit,
    (newValue, oldValue) => {
      if (newValue != oldValue) {
        fetchPasien()
      }
    }
  )
  watch(
    () => [
      item.value.fStatusAntrian
    ], () => {
      H.cacheHelper().set('statusberobat', item.value.fStatusAntrian)
      fetchPasien()
    }
  )
  
  watch(
    () => item.value.isPasien, () => {
      fetchPasien()
      fetchReservasi()
      fetchJadwalDokter()
    }
  )
  
  onMounted(() => {
    fetchdDropdown()
    fetchKonsul()
    fetchJadwalDokter()
    fetchDataChart()
  })
  
  
  </script>
  
  <style lang="scss">
  @import '/@src/scss/abstracts/all';
  @import '/@src/scss/custom/config';
  @import '/@src/scss/module/dashboard/rawat-jalan.scss';
  
  // .r-card {
  //   flex: 1;
  //   display: inline-block;
  //   width: 100%;
  //   padding: 15px;
  //   background-color: var(--white);
  //   border-radius: 10px;
  //   border: 1px solid var(--fade-grey-dark-3);
  //   transition: all 0.3s;
  // }
  
  .picon{
    font-size: 12px; font-weight: bold; margin-left: -5px
  }
  
  .pfont{
    font-size: 12px; font-weight: bold;
  }
  
  .buttsize{
    height: 30px; width: 30px;
  }
  </style>
  