<template>
  <section>
    <div class="business-dashboard hr-dashboard">
      <div class="columns">
        <div class="column is-12">
          <div class="columns is-multiline">
            <!--Header-->
            <div class="column is-12">
              <div class=" illustration-header-2 columns is-multiline">
                <div class="column is-6 p-0">
                  <div class="header-image">
                    <img src="/@src/assets/illustrations/dashboards/lifestyle/da.png" alt=""
                      style="max-width:75%; margin-left: 2rem; margin-bottom: 1rem;" />
                  </div>
                </div>
                <div class="column is-6 p-0">
                  <div class="header-meta">
                    <h3 style="color:white"><i class="fas fa-home"></i> Instalasi Rawat Jalan</h3>
                    <p>
                      Selamat Datang , {{ userLogin.pegawai.namaLengkap }}
                    </p>

                    <div class="column is-12 p-0">
                      <VControl>
                        <MultiSelect v-model="sourceRuangan" display="chip" :options="d_Ruangan" optionLabel="label"
                          filter placeholder="Pilih Ruangan" :maxSelectedLabels="3" style="display:flex"
                          @change="changeRuang(sourceRuangan)" />
                      </VControl>
                      <!-- <VControl>
                        <VSwitchBlock v-if="kelompokUser != 'dokter'" @change.stop="changeisNurse($event)"
                          v-model="item.isNurstation" :label="'Nurse Station'" color="danger" />
                      </VControl> -->
                    </div>
                    <div class="columns is-multiline" style="margin-top: 10px;margin-left: 10px;">
                      <div class="column is-6 p-0">
                        <VControl>
                          <VSwitchBlock v-model="item.isPasien" label="Pasien Dokter" color="danger"
                            v-if="kelompokUser == 'dokter'" />
                        </VControl>
                      </div>
                      <div class="column is-6 p-0">
                        <VControl>
                          <VSwitchBlock v-if="kelompokUser == 'dokter'" @change.stop="changeisRanap($event)"
                            v-model="item.isRanap" :label="'Rawat Inap'" color="danger" />
                        </VControl>
                        <VControl>
                          <VSwitchBlock v-if="kelompokUser == 'dokter'" @change.stop="changeisBedah($event)"
                            v-model="item.isRanap" :label="'Bedah'" color="danger" />
                        </VControl>
                      </div>
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

            <div class="column px-0" v-if="kelompokUser == 'dokter'">
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
                      <span v-else style="font-weight: 500;margin-top: 7px;font-size: 15px;">{{ item.totalPasien
                        }}</span>
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
            <div class="column is-12 p-0 mt-2">
              <TabView class="tabview-custom " :scrollable="true" @tab-click="klikTab($event)">
                <TabPanel style="border-top-right-radius: 12px;border-top-left-radius: 12px;">
                  <template #header>
                    <i class="fas fa-users mr-2" aria-hidden="true"></i>
                    <span>Daftar Pasien</span>
                    <Badge :value="dataPasien.total" v-if="dataPasien.total > 0" severity="danger" class="ml-2" />
                  </template>

                  <div v-if="activeTab == 0">
                    <div class="column is-4"
                      style="margin-left: 23rem;margin-bottom: 20px;padding: 0px;margin-top: -4.25rem;"
                      v-if="userLogin.kelompokUser.kelompokUser == 'dokter'">
                      <VButton color="success" class="search-button"
                        @click="openModalIntruksi = true; notifBottom = false;" :loading="isLoadingTT" light> Intruksi
                        <Badge :value="listIntruksi.length ?? 0" severity="danger" class="ml-2" />
                      </VButton>
                    </div>
                    <div class="column is-4" style="margin-bottom: 20px;padding: 0px;margin-top: -4.25rem;"
                      v-if="isEval"
                      :style="[userLogin.kelompokUser.kelompokUser == 'perawat' ? 'margin-left: 23rem' : 'margin-left: 33rem']">
                      <VButton color="info" class="search-button"
                        @click="openModalEvaluasi = true; notifBottomEval = false" :loading="isLoadingTT" light>
                        Evaluasi
                        <Badge :value="notifBottomEvalLength ?? 0" severity="danger" class="ml-2" />
                      </VButton>
                    </div>
                    <div class="column is-4"
                      style="margin-left: auto;margin-bottom: 20px;padding: 0px;margin-top: -4.25rem;">
                      <!-- <VDatePicker is-range v-model.range="filterTgl" /> -->
                      <VControl class="prime-auto">
                        <Calendar inputId="range" v-model="item.filterTgl" selectionMode="range" :manualInput="false"
                          class="w-100" showIcon iconDisplay="input" />
                      </VControl>
                    </div>

                    <div class="list-view list-view-v3">
                      <VCard class="text-center pt-0 pb-0 mt-0 is-flex">
                        <VRadio class="my-auto" v-model="item.fStatusAntrian" value="Menunggu Pelayanan"
                          label="Menunggu Pelayanan" name="outlined_radio" color="danger"
                          @click="setCache(item.fStatusAntrian)" />
                        <!-- <VRadio class="my-auto" v-model="item.fStatusAntrian" value="Sedang Diperiksa"
                          label="Sedang Diperiksa" name="outlined_radio" color="warning"
                          @click="setCache(item.fStatusAntrian)" /> -->
                        <VRadio class="my-auto" v-model="item.fStatusAntrian" value="Selesai" label="Selesai"
                          name="outlined_radio" color="warning" @click="setCache(item.fStatusAntrian)" />
                        <VRadio class="my-auto" v-model="item.fStatusAntrian" value="Sudah Closing"
                          label="Sudah Closing" name="outlined_radio" color="success"
                          @click="setCache(item.fStatusAntrian)" />
                        <VRadio class="my-auto" v-model="item.fStatusAntrian" value="Semua" label="Semua"
                          name="outlined_radio" color="info" @click="setCache(item.fStatusAntrian)" />
                        <VRadio class="my-auto" value="Ranap" label="Rawat Inap" name="outlined_radio" color="info"
                          @click="rawatInap(item)" style="display: none !important" />
                        <VRadio class="my-auto" value="Reservasi" label="Reservasi" name="outlined_radio" color="danger"
                          v-model="item.fStatusAntrian" @click="reservasi()" />
                        <div class="search-menu ml-auto" style="width: 50%;">
                          <div class="search-location" style="width: 100%" v-if="!isReservasi">
                            <i class="iconify" data-icon="feather:search"></i>
                            <input type="text" style="height: auto;"
                              placeholder="Cari Nama Pasien, No Registrasi, No RM, BPJS, Atau NIK" v-model="item.search"
                              v-on:keyup.enter="cariData" />
                          </div>
                          <VButton style="height: 38px;" raised class="search-button" @click="cariData()"
                            :loading="isLoading">
                            Cari Data
                          </VButton>
                        </div>
                      </VCard>
                      <div class="column px0">
                        <!-- <pre>{{ dataPasien }}</pre> -->
                        <DataTable :value="dataPasien" class="p-datatable-md" :loading="isLoading" lazy
                          rowGroupMode="subheader" groupRowsBy="namaruangan" sortField="namaruangan" sortMode="single"
                          scrollable :metaKeySelection="metaKey" selectionMode="single" scrollHeight="600px"
                          tableStyle="min-width: 50rem" @rowSelect="onRowSelect" @page="onPageChange($event)"
                          @update:rows="limit = $event" :rows="limit" :rowsPerPageOptions="rowOptions" :paginator="true"
                          v-model:selection="selectedPasien" :totalRecords="dataPasien.total"
                          paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
                          responsiveLayout="stack" breakpoint="960px"
                          currentPageReportTemplate="Showing {first} to {last} of {totalRecords}" showGridlines>

                          <template #empty>
                            <VPlaceholderPage title="Tidak Ada Pasien Hari Ini."
                              subtitle="Silakan Pilih Tanggal dan Ruangan untuk melihat Data Pasien" larger>
                              <template #image>
                                <img class="light-image" src="/@src/assets/illustrations/placeholders/search-4.png"
                                  alt="" />
                                <img class="dark-image" src="/@src/assets/illustrations/placeholders/search-4-dark.svg"
                                  alt="" />
                              </template>
                            </VPlaceholderPage>
                          </template>
                          <template #loading>
                            <img src="/images/other/loadingspin.gif" alt="Loading..." width="100" />
                            <p style="color:white">Loading data, please wait...</p>
                          </template>
                          <Column header="Action" style="width: 30px;">
                            <template #body="slotProps">
                              <div class="columns">
                                <div class="column">
                                  <VDropdown class="is-pushed-mobile is-raised is-primary is-custom-dd" color="solid"
                                    spaced left style="font-size: 22px;">
                                    <template #content="{ close }">
                                      <!-- Selain M-JKN -->
                                      <div style="height: 22rem;overflow: auto;">
                                        <!-- <a role="menuitem" @click="panggil(slotProps.data)"
                                          class="dropdown-item is-media">
                                          <div class="icon">
                                            <i aria-hidden="true" class="lnil lnil-phone"></i>
                                          </div>
                                          <div class="meta">
                                            <span>Panggil</span>
                                            <span>Panggil Pasien</span>
                                          </div>
                                        </a> -->
                                        <a role="menuitem" @click="emr(slotProps.data)" class="dropdown-item is-media">
                                          <div class="icon">
                                            <i aria-hidden="true" class="fas fa-stethoscope"></i>
                                          </div>
                                          <div class="meta">
                                            <span>Periksa</span>
                                            <span>Periksa Pasien</span>
                                          </div>
                                        </a>
                                        <!-- <a role="menuitem" @click="cetakSEP()" class="dropdown-item is-media">
                                          <div class="icon">
                                            <i aria-hidden="true" class="lnil lnil-printer"></i>
                                          </div>
                                          <div class="meta">
                                            <span>Cetak SEP</span>
                                            <span>Cetak Surat Elegibilitas </span>
                                          </div>
                                        </a> -->
                                        <a role="menuitem" @click="openModalDpjp(slotProps.data)"
                                          class="dropdown-item is-media">
                                          <div class="icon">
                                            <i aria-hidden="true" class="fas fa-user-edit"></i>
                                          </div>
                                          <div class="meta">
                                            <span>Dokter</span>
                                            <span>Ubah Dokter </span>
                                          </div>
                                        </a>
                                        <!-- <a role="menuitem" @click="openModaPilihPerawat()"
                                          class="dropdown-item is-media">
                                          <div class="icon">
                                            <i aria-hidden="true" class="fas fa-user-nurse"></i>
                                          </div>
                                          <div class="meta">
                                            <span>Perawat</span>
                                            <span>Pilih Perawat </span>
                                          </div>
                                        </a> -->
                                        <!-- <a role="menuitem" @click="batalRegis(slotProps.data)"
                                          class="dropdown-item is-media">
                                          <div class="icon">
                                            <i aria-hidden="true" class="fas fa-times-circle"></i>
                                          </div>
                                          <div class="meta">
                                            <span>Batal</span>
                                            <span>Batal Registrasi </span>
                                          </div>
                                        </a> -->
                                        <!-- <a role="menuitem" @click="pasienPulang(slotProps.data)"
                                          class="dropdown-item is-media">
                                          <div class="icon">
                                            <i aria-hidden="true" class="fas fa-user-edit"></i>
                                          </div>
                                          <div class="meta">
                                            <span>Pulang</span>
                                            <span>Pasien Pulang </span>
                                          </div>
                                        </a> -->
                                        <!-- <a role="menuitem" @click="pasienMeninggal(slotProps.data)"
                                          class="dropdown-item is-media">
                                          <div class="icon">
                                            <i aria-hidden="true" class="fas fa-times-circle"></i>
                                          </div>
                                          <div class="meta">
                                            <span>Meninggal</span>
                                            <span>Pasien Meninggal </span>
                                          </div>
                                        </a> -->
                                        <a role="menuitem" @click="suratKontrol(slotProps.data)"
                                          class="dropdown-item is-media">
                                          <div class="icon">
                                            <i aria-hidden="true" class="fas fa-check-circle"></i>
                                          </div>
                                          <div class="meta">
                                            <span>Kontrol</span>
                                            <span>Surat Kontrol </span>
                                          </div>
                                        </a>
                                      </div>

                                    </template>
                                  </VDropdown>
                                </div>
                              </div>
                            </template>
                          </Column>
                          <Column field="indiakasiKontrolNew" header="Indikasi Kontrol" style="width: 30px;">
                            <template #body="slotProps">
                              <div class="columns">
                                <div class="column">
                                  <VDropdown v-if="slotProps.data.indiakasiKontrolNew != null" class="is-pushed-mobile is-raised is-primary is-custom-dd" color="green"
                                    spaced left style="font-size: 22px;">
                                    <template #content="{ close }">
                                      <div style="height: 22rem;overflow: auto;">
                                        <div>
                                          <span style="font-size: large;">Indikasi Kontrol:{{slotProps.data.indiakasiKontrolNew }}</span>
                                          <br>
                                          <span style="font-size: large;">Diagnosa Akhir:{{slotProps.data.diagnosaIndikasiKontrolNew }}</span>
                                        </div>
                                      </div>
                                    </template>
                                  </VDropdown>
                                  <button  class="icon" circle v-else>
                                    <i aria-hidden="true" class="fas fa-minus"></i>
                                  </button>
                                  <!-- <Column field="indikasikontrol" header="Indikasi Kontrol" :sortable="true"
                                        style="min-width: 190px; text-align: center;" :expander="true">
                                    <template #body="slotProps">
                                        <button style="background-color: #7FFF00;" class="icon" circle v-if="slotProps.data.indikasikontrol != null" @click="getIndikasi()">
                                            <i aria-hidden="true" class="fas fa-plus"></i>
                                        </button>
                                        <button  class="icon" circle v-else>
                                            <i aria-hidden="true" class="fas fa-minus"></i>
                                        </button>
                                        <span v-if="isIdikasi == true">
                                          testst indikasi kontrol
                                        </span>
                                    </template>
                                </Column> -->
                                </div>
                              </div>
                            </template>
                          </Column>
                          <template #groupheader="slotProps">
                            <div class="flex items-center gap-2">
                              <span style="font-weight: bold; font-size: 16px;">
                                {{ slotProps.data.namaruangan }}
                              </span>
                            </div>
                          </template>

                          <Column field="noregistrasi" header="No Reg" :sortable="true"
                            style="width: 100px;text-align:center;"></Column>
                          <Column field="noantrian" header="No Antrian" :sortable="true"
                            style="width: 100px;text-align:center;"></Column>
                          <Column field="namapasien" header="Nama Pasien" :sortable="true" style="width: 350px">
                            <template #body="slotProps">
                              <div class="columns is-multiline">
                                <div class="column is-12">
                                  <span style="font-weight: 600;">
                                    <span class="icon" style="color: rgb(230, 41, 100)"
                                      v-if="slotProps.data.jeniskelamin == 'Perempuan'"><i aria-hidden="true"
                                        class="fas fa-venus"></i></span>
                                    <span class="icon" style="color: rgb(3, 152, 226)" v-else><i aria-hidden="true"
                                        class="fas fa-mars"></i></span>
                                    {{ slotProps.data.namapasien }}
                                  </span><br>
                                  <span>
                                    <VTag class="ml-1 mb-1" v-tooltip="'Kewarganegaraan'"
                                      :class="slotProps.data.class_kebangsaan" :label="slotProps.data.kebangsaan"
                                      rounded />
                                    <VTag class="tag is-info ml-1 mb-1" v-tooltip="'Nomor Antrian'"
                                      :label="slotProps.data.noantrian" rounded />
                                    <VTag class="ml-1 mb-1" v-tooltip="'Status Periksa'"
                                      :class="slotProps.data.class_statusperiksa"
                                      :label="slotProps.data.label_statusperiksa" rounded />
                                    <VTag class="ml-1 mb-1" v-tooltip="'Pasien Konsultasi'"
                                      v-if="slotProps.data.konsul != null" color="success" label="K" rounded />
                                    <VTag class="ml-1 mb-1" v-tooltip="'Hasil Laboratorium sudah ada'"
                                      v-if="slotProps.data.statuslab != 'Belum Ada Hasil'"
                                      :class="slotProps.data.class_statuslab" label="" rounded>
                                      <i class="fa fa-flask" />
                                    </VTag>
                                    <VTag class="ml-1 mb-1" v-tooltip="'Hasil Radiologi sudah ada'"
                                      v-if="slotProps.data.statusrad != 'Belum Ada Hasil'"
                                      :class="slotProps.data.class_statusrad" label="" rounded>
                                      <i class="fa fa-radiation" />
                                    </VTag>
                                  </span>
                                </div>
                              </div>
                            </template>
                          </Column>
                          <Column field="nocm" header="No RM" :sortable="true" style="width: 100px;text-align:center;">
                          </Column>
                          <Column field="nobpjs" header="No BPJS" :sortable="true"
                            style="width: 120px;text-align:center;"></Column>
                          <Column field="dpjp" header="DPJP" :sortable="true" style="width: 190px"></Column>
                          <Column field="namalengkap" header="Dokter" :sortable="true" style="width: 190px"></Column>
                          <Column field="tglregistrasi" header="Tanggal" :sortable="true" style="width: 100px">
                            <template #body="slotProps">
                              <span>{{ moment(slotProps.data.tglregistrasi).format('DD/MM/YYYY HH:mm:ss') }}</span>
                            </template>
                          </Column>
                          <Column field="namaruangan" header="Ruangan" :sortable="true" style="width: 190px"></Column>
                          <Column field="kelompokpasien" header="Cara Bayar" :sortable="true" style="width: 160px">
                          </Column>
                          <Column field="tglkontrol" header="Surat Kontrol" :sortable="true"
                            style="min-width: 190px; text-align: center;" :expander="true">
                            <template #body="slotProps">
                              <span class="icon" circle v-if="slotProps.data.tglkontrol != null"><i aria-hidden="true"
                                  class="fas fa-check" circle></i></span>
                              <span class="icon" circle v-else><i aria-hidden="true" class="fas fa-minus"
                                  circle></i></span>
                            </template>
                          </Column>
                        </DataTable>
                      </div>
                    </div>
                  </div>
                </TabPanel>
                <TabPanel>
                  <template #header>
                    <i class="fas fa-check-circle mr-2" aria-hidden="true"></i>
                    <span>Daftar Reservasi</span>
                    <Badge :value="dataReservasi.length" v-if="dataReservasi.length > 0" severity="danger"
                      class="ml-2" />
                  </template>
                  <div v-if="activeTab == 1">

                    <div class="column is-6"
                      style="margin-left: 25rem;margin-bottom: 20px;padding: 0px;margin-top: -4.25rem;">
                      <VControl class="prime-auto">
                        <Calendar inputId="range" v-model="item.filterTgl" selectionMode="range" :manualInput="false"
                          class="w-100" showIcon iconDisplay="input" />
                      </VControl>
                      <!-- <VDatePicker v-model="item.filterTgl" is-range color="pink" trim-weeks>
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
                        </VDatePicker> -->
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
                          :loading="isLoadingTT"> Cari
                          Data </VButton>
                      </div>
                      <div class="list-view-inner" style="max-height:300px;overflow: auto;">
                        <div class="columns m-3">
                          <div class="columns is-3 p-0">
                            <VControl class="prime-auto">
                              <AutoComplete v-model="item.filterDokter" :suggestions="d_Dokter"
                                @complete="fetchDokter($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                                :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'" class="mt-2" placeholder="Cari nama dokter..." />
                            </VControl>
                          </div>
                          <div class="columns is-2 p-0" style="margin-left: auto;">
                            <VIconButton color="primary" circle icon="fas fa-redo" outlined raised
                              @click="fetchReservasi()" v-tooltip.bottom.left="'Refresh'"
                              style="float:right; margin: 10px;">
                            </VIconButton>
                          </div>
                        </div>
                        <div name="list-complete" tag="div">
                          <!--Item-->
                          <div v-for="item in dataReservasi" :key="item.id" class="list-view-item">
                            <div class="list-view-item-inner">
                              <VAvatar size="small" picture="/images/avatars/svg/pasien.svg" color="primary" bordered />

                              <div class="meta-left">
                                <h3 style="margin-top: 10px;">
                                  {{ item.namapasien }}
                                </h3>
                                <span>
                                  <span>No Antrian: {{ item.noantrianpoli }}</span><br>
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
                                  <span>{{ item.noidentitas }}</span>
                                  <i aria-hidden="true" class="fas fa-circle icon-separator"></i>
                                  <i aria-hidden="true" class="iconify" data-icon="feather:user"></i>
                                  <span>{{ item.dokter }}</span>
                                </span>
                                <br>
                                <VTag :label="item.status" :color="item.status_c" v-if="!item.ismobilejkn"
                                  class="mr-1" />
                                <VTag :label="item.label_statusperiksa" :color="item.class_statusperiksa"
                                  class="mr-1" />
                                <VTag label="Mobile JKN" :color="item.status_c" v-if="item.ismobilejkn" class="mr-1" />
                                <VTag :label="item.ischeckin == true ? 'Sudah Checkin' : 'Belum Checkin'"
                                  :color="item.status_c" v-if="item.ismobilejkn" />
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

        <!-- DISINI HARUSNYA IS-4 YANG RIWAYAT DOKTER -->
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
          <VButton color="danger" icon="pi pi-times" outlined raised @click="modalChangeDokter = false"> Batal
          </VButton>
          <VButton color="primary" icon="pi pi-check" raised @click="saveChangeDokter()" :loading="btnLoadSimpan">
            Update
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
          <VButton color="danger" icon="pi pi-times" outlined raised @click="modalPilihPerawat = false"> Batal
          </VButton>
          <VButton color="primary" icon="pi pi-check" raised @click="savePilihPerawat()" :loading="btnLoadSimpan">
            Update
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
                <VInput type="text" v-model="item.namaruangan" placeholder="Tempat Lahir" class="is-rounded_Z"
                  disabled />
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

    <OverlayPanel ref="op" appendTo="body" style="width:500px">
      <div class="columns is-multiline">
        <div class="column is-4 pt-1 pb-1 ">
          <VButton type="button" icon="fas fa-print" class="w-100" circle outlined color="success" raised
            :loading="btnLoadCetak" @click="cetakSEP()">
            Cetak SEP
          </VButton>
        </div>
        <div class="column is-4 pt-1 pb-1 pl-0"></div>
        <VButton type="button" icon="fas fa-user-edit" class="w-100" circle outlined color="warning" raised
          @click="openModalDpjp(selectedItem)">
          Ubah Dokter
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
              <input v-model="item.ruanganasal" type="text" class="input is-rounded" placeholder="Lain-lain "
                disabled />
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
                <Multiselect mode="single" v-model="item.statuskeluar" :options="d_StatusKeluar"
                  placeholder="Pilih data" :searchable="true" :attrs="{ id }" autocomplete="off" track-by="value" />

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
                <VInput type="text" v-model="item.namalengkappasien" placeholder="Nama Lengkap Pasien"
                  class="is-rounded" disabled />
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
                <Multiselect mode="single" v-model="item.statuskeluar2" :options="d_StatusKeluar"
                  placeholder="Pilih data" :searchable="true" :attrs="{ id }" autocomplete="off" track-by="value" />
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
                <Multiselect mode="single" v-model="item.statuspulang" :options="d_StatusPulang"
                  placeholder="Pilih data" :searchable="true" :attrs="{ id }" autocomplete="off" track-by="value" />
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

    <Dialog v-model:visible="modalChangeRuangan" modal header="Pilih Nurse Station" :style="{ width: '40vw' }">
      <div class="column is-12">
        <div class="columns is-multiline">
          <div class="column is-12" style=" height: auto;min-height: 150px;">
            <VField label="Locking Nurse Station" class="is-rounded-select  is-autocomplete-select" v-slot="{ id }">
              <VControl icon="feather:plus-circle" fullwidth>
                <Multiselect mode="single" v-model="item.lockedRoute" :options="d_listNurse" placeholder="Pilih data"
                  :searchable="true" :attrs="{ id }" autocomplete="off" track-by="value" />
              </VControl>
            </VField>
          </div>
        </div>
      </div>

      <template #footer>
        <VButton color="primary" icon="pi pi-check" @click="lockFilterNurse()" raised :loading="btnLoadSimpan"> Simpan
        </VButton>
      </template>
    </Dialog>

    <Dialog v-model:visible="openModalIntruksi" maximizable modal header="Header" :style="{ width: '75rem' }"
      :breakpoints="{ '1199px': '75vw', '575px': '90vw' }">
      <template #header>
        <div class="inline-flex items-center justify-center gap-2">
          <span class="font-bold whitespace-nowrap">List Intruksi</span>
        </div>
      </template>
      <table class="table is-hoverable is-fullwidth" v-if="!isLoading">
        <thead>
          <tr>
            <th scope="col">
              Tanggal
            </th>
            <th scope="col">
              NRM
            </th>
            <th scope="col">
              NoReg
            </th>
            <th scope="col">
              Section
            </th>
            <th scope="col">
              Nama
            </th>
            <th scope="col">
              PPA
            </th>
            <th scope="col">
              Catatan Perkembangan
            </th>
            <th scope="col" class="is-end">
              <div class="dark-inverted is-flex is-justify-content-flex-end">
                Status
              </div>
            </th>
          </tr>
        </thead>
        <tbody>
          <tr v-if="listIntruksi.length > 0" v-for="intru in listIntruksi">
            <td>
              {{ H.formatDate(intru.details.created_at, 'YYYY-MM-DD') }}
            </td>
            <td>{{ intru.nocm }}</td>
            <td>{{ intru.noregistrasi }}</td>
            <td>{{ intru.namaruangan }}</td>
            <td>{{ intru.namapasien }}</td>
            <td>{{ intru.namaPPA }}</td>
            <td>
              <p>
                <span style="font-weight: bold;">S</span> : {{ intru.details.S ?? '' }}
              </p>
              <p>
                <span style="font-weight: bold;">O</span> : {{ intru.details.O ?? '' }}
              </p>
              <p>
                <span style="font-weight: bold;">A</span> : {{ intru.details.A ?? '' }}
              </p>
              <p>
                <span style="font-weight: bold;">P</span> : {{ intru.details.P ?? '' }}
              </p>
              <p>
                <span style="font-weight: bold;">Intruksi</span> :
                {{ intru.details.intruksiPPA ?? '' }}&nbsp;(<span style="font-weight: bold;">{{
                  intru.details.dpjpUtama?.label ?? '' }}</span>)
              </p>
            </td>
            <td class="is-end">
              <div class="is-flex is-justify-content-flex-end">
                <VButton type="button" color="danger" rounded circle v-tooltip.bottom.left="'Klik untuk Verifikasi'"
                  @click="verifCPPT(intru)" :loading="isLoading">
                  Belum Verif
                </VButton>
                <!-- <VTag class="ml-1 mb-1" v-tooltip="'klik untuk Verifikasi'"
                  color="warning" label="Belum Verifikasi"
                  rounded v-if="!intru.isconfirm"/> -->
                <VTag class="ml-1 mb-1" color="success" label="Terverifikasi" rounded v-if="intru.isconfirm" />
                <!-- <FlexTableDropdown /> -->
              </div>
            </td>
          </tr>
        </tbody>
      </table>
      <VPlaceload v-if="isLoading" height="200px" class="mx-2" />
      <br>
      <VPlaceload v-if="isLoading" height="200px" class="mx-2" />
      <p v-if="!isLoading && listIntruksi.length == 0">
        Tidak ada Data
      </p>
    </Dialog>

    <Dialog v-model:visible="openModalEvaluasi" maximizable modal header="Header" :style="{ width: '75rem' }"
      :breakpoints="{ '1199px': '75vw', '575px': '90vw' }">
      <template #header>
        <div class="inline-flex items-center justify-center gap-2">
          <span class="font-bold whitespace-nowrap">List Evaluasi</span>
        </div>
      </template>
      <table class="table is-hoverable is-fullwidth" v-if="!isLoading">
        <thead>
          <tr>
            <th scope="col">
              Tanggal Evaluasi
            </th>
            <th scope="col">
              NRM
            </th>
            <th scope="col">
              Dokter
            </th>
            <th scope="col">
              Nama
            </th>
            <th scope="col">
              Intruksi
            </th>
          </tr>
        </thead>
        <tbody>
          <tr v-if="listEvaluasi.length > 0" v-for="evals in listEvaluasi">
            <td>
              {{ H.formatDate(evals.tanggal, 'YYYY-MM-DD') }}
            </td>
            <td>{{ evals.norm }}</td>
            <td>{{ evals.namadokter }}</td>
            <td>{{ evals.namapasien }}</td>
            <td>{{ evals.intruksi }}</td>
          </tr>
        </tbody>
      </table>
      <VPlaceload v-if="isLoading" height="200px" class="mx-2" />
      <br>
      <VPlaceload v-if="isLoading" height="200px" class="mx-2" />
      <p v-if="!isLoading && listEvaluasi.length == 0">
        Tidak ada Data
      </p>
      <template #footer>
        <VButton icon="fas fa-plus" color="success" @click="openTambahModalEval = true"
          v-if="userLogin.kelompokUser.kelompokUser.toLowerCase().indexOf('dokter') > -1">
          Tambah Jadwal
        </VButton>
      </template>
    </Dialog>

    <Dialog v-model:visible="openTambahModalEval" maximizable modal header="Header" :style="{ width: '25rem' }"
      :breakpoints="{ '1199px': '25vw', '575px': '30vw' }">
      <template #header>
        <div class="inline-flex items-center justify-center gap-2">
          <span class="font-bold whitespace-nowrap">Tambah Evaluasi</span>
        </div>
      </template>
      <div class="columns is-multiline">
        <div class="column is-12">
          <VField label="Tanggal Evaluasi" vertical>
            <Calendar v-model="item.tglEval" style="width: 100%;" />
          </VField>
        </div>
        <div class="column is-12">
          <VField label="Nama Dokter" vertical>
            <VControl>
              <VSelect v-model="item.dokterEval" disabled>
                <VOption :value="userLogin.pegawai.id">
                  {{ userLogin.pegawai.namaLengkap }}
                </VOption>
              </VSelect>
            </VControl>
          </VField>
        </div>
        <div class="column is-12">
          <VField label="Intruksi Evaluasi" vertical>
            <VControl>
              <VTextarea v-model="item.intruksiEval" />
            </VControl>
          </VField>
        </div>
        <div class="column is-12"></div>
      </div>
      <template #footer>
        <VButton icon="fas fa-plus" color="success" @click="saveJadwal()" :loading="isLoading">
          Simpan
        </VButton>
      </template>
    </Dialog>

    <Sidebar v-model:visible="notifBottom" header="Bottom Sidebar" position="bottom" style="height: auto">
      <template #header>
        <div class="flex align-items-center gap-2 mx-auto" style="justify-content: center;">
          <span class="font-bold">Terdapat {{ listIntruksi.length ?? 0 }} Intruksi yang belum di verifikasi, silahkan
            verifikasi intruksi yang diberikan</span>
        </div>
      </template>
      <div class="flex align-items-center gap-2 mx-auto" style="justify-content: center;">
        <VButton color="primary" style="height: 38px;" raised class="text-center" :loading="isLoading"
          @click="openModalIntruksi = true; notifBottom = false;">
          Lihat Intruksi
        </VButton>
      </div>
    </Sidebar>

    <Sidebar v-model:visible="notifBottomEval" header="Bottom Sidebar" position="bottom" style="height: auto">
      <template #header>
        <div class="flex align-items-center gap-2 mx-auto" style="justify-content: center;">
          <span class="font-bold">Terdapat {{ listEvaluasi.length ?? 0 }} Evaluasi Pasien yang seharusnya datang pada
            hari ini.</span>
        </div>
      </template>
      <div class="flex align-items-center gap-2 mx-auto" style="justify-content: center;">
        <VButton color="primary" style="height: 38px;" raised class="text-center" :loading="isLoading"
          @click="openModalEvaluasi = true; notifBottomEval = false;">
          Lihat List Pasien Evaluasi
        </VButton>
      </div>
    </Sidebar>

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
import Column from 'primevue/column'
import { FilterMatchMode } from 'primevue/api';
import InputText from 'primevue/inputtext';
import Sidebar from 'primevue/sidebar';

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
const modalChangeRuangan = ref(false)
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
const isEval: any = ref(false);
const saveSuratKontrol: any = ref(false)
const metaKey = ref(true);
const notifBottom = ref(false);
const notifBottomEval = ref(false);
const notifBottomEvalLength = ref(0);
const openModalIntruksi = ref(false);
const openModalEvaluasi = ref(false);
const openTambahModalEval = ref(false);
// const idmetakey = ref({});
const d_status = [
  { value: 't', label: 'True' },
  { value: 'f', label: 'False' },
]
const kelompokUser = userLogin.kelompokUser.kelompokUser
const kelompokUserID = useUserSession().getUser().kelompokUser.id
const pegawaiId = useUserSession().getUser().pegawai.id
const selectedPasien = ref();
let doubleclick = null;
let page: any = route.query.page ? route.query.page : 1

const item: any = ref({
  isPasien: false,
  ruangans: [],
  aktif: true,
  fStatusAntrian: 'Menunggu Pelayanan',
  tanggalpembatalan: new Date(),
  filterTgl: [
    new Date(),
    new Date()
  ],
  tglmeninggal: new Date(),
  tglpulang: new Date(),
  tglEval: new Date(),
  dokterEval: userLogin.pegawai.id
})
const chart: any = ref({
  aktif: true
})

const rowOptions = [5, 10, 25, 50, 100, 300, 500, 1000]
const limit = ref(rowOptions[4])
const currentPage: any = ref({
  limit: 6,
  rows: 50,
})
const listIntruksi: any = ref([]);
const listEvaluasi: any = ref([]);


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
let counterSelect: number = 0;
let indexmeta: any = null;
let chartStatus: any = ref({
  series: [],
})
let countRuangan: any = ref([])
const filters = ref('')
// const d_Dokter: any = ref([])
// const fetchDokter = async (filter: any) => {
//   await useApi().get(`emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`).then((response) => {
//     d_Dokter.value = response
//   })
// }

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

const d_listNurse = ref([
  {
    label: "Nurse Station Basement",
    value: "nurse-station-basement"
  },
  {
    label: "Nurse Station Kedokteran Nuklir",
    value: "nurse-station-kedokteran-nuklir"
  },
  {
    label: "Nurse Station MCU",
    value: "nurse-station-mcu"
  },
  {
    label: "Nurse Station Poli Barat",
    value: "nurse-station-poli-barat"
  },
  {
    label: "Nurse Station Kanker Basement",
    value: "nurse-station-kanker-basement"
  },
  {
    label: "Nurse Station Poli Kanker",
    value: "nurse-station-poli-kanker"
  },
  {
    label: "Nurse Station Poli Timur",
    value: "nurse-station-poli-timur"
  },
  {
    label: "Nurse Station Poli VIP",
    value: "nurse-station-poli-vip"
  },
  {
    label: "Nurse Station Rehab Medis",
    value: "nurse-station-rehab-medis"
  },
])


const toggleOP = (event: any, item: any) => {
  selectedItem.value = item
  op.value.toggle(event);
}

const fetchdDropdown = async () => {
  let getRoutes = H.cacheHelper().get('lockedRoute');
  if (getRoutes || getRoutes != null) {
    item.value.isNurstation = true;
  }
  const response = await useApi().get(`/dashboard/dropdown-rawat-jalan`)
  d_Ruangan.value = response.ruangan.map((e: any) => { return { label: e.namaruangan, value: e.id, default: e } })
  d_StatusKeluar.value = response.statuskeluar.map((e: any) => { return { label: e.statuskeluar, value: e.id, default: e } })
  d_KondisiPasien.value = response.kondisipasien.map((e: any) => { return { label: e.kondisipasien, value: e.id, default: e } })
  d_StatusPulang.value = response.statuspulang.map((e: any) => { return { label: e.statuspulang, value: e.id, default: e } })

  // d_Ruangan.value.forEach((element) => {
  //   sourceRuangan.value.push(element)
  // })

  if (H.cacheHelper().get('ruanganDipilih') && H.cacheHelper().get('ruanganDipilih').length > 0) {
    sourceRuangan.value = H.cacheHelper().get('ruanganDipilih')
  } else {
    d_Ruangan.value.forEach((element) => {
      sourceRuangan.value.push(element)
    })
  }
  cariData()
}

const fetchPasien = async (e: any) => {
  let dari = `?dari=${H.formatDate(item.value.filterTgl[0], 'YYYY-MM-DD')}`
  let sampai = `&sampai=${H.formatDate(item.value.filterTgl[1], 'YYYY-MM-DD')}`
  let idPegawai = item.value.isPasien == true ? `&idpegawai=${H.pegawaiLogin().id}` : ''
  // let limit: any = currentPage.value.limit
  // let page: any = route.query.page ? route.query.page : 1
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
  // let statuspanggil = '';
  let statuspanggil = item.value.fStatusAntrian ? `&statuspanggil=${item.value.fStatusAntrian}` : ''
  if(item.value.fStatusAntrian == 'Semua') {
    statuspanggil = '';
  }
  let klmpkUser = kelompokUser ? `&kelompokUser=${kelompokUser}` : ''
  isReservasi.value = false
  isLoading.value = true
  isLoadCount.value = true
  dataPasien.value = []
  page = page + 1;
  const response = await useApi().get(`/dashboard/rawat-jalan-pasien${dari}${sampai}${idPegawai}${klmpkUser}${ruanganid}${namapasien}${nocm}${qnoreg}${statuspanggil}${search}&page=${page}&limit=${limit.value}`)
  isLoading.value = false
  // console.log(Object.values(response.data.data))
  dataPasien.value = Object.values(response.data.data)
  dataPasien.value.total = response.data.total

  if (kelompokUser == 'dokter') {
    countPasien(dari, sampai, idPegawai, e)
  }
}

const cariData = function () {
  page = 0;
  fetchPasien();
}

async function onPageChange(event) {
  page = event.page
  fetchPasien()
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

  let dari = `?dari=${H.formatDate(item.value.filterTgl[0], 'YYYY-MM-DD')}`
  let sampai = `&sampai=${H.formatDate(item.value.filterTgl[1], 'YYYY-MM-DD')}`
  let idPegawai = item.value.isPasien == true ? `&idpegawai=${H.pegawaiLogin().id}` : ''
  // let ruanganid = e != undefined ? `&ruanganid=${e}` : ''
  let namapasien = item.value.qnamapasien ? `&namapasien=${item.value.qnamapasien}` : ''
  let noreservasi = item.value.qnorev ? `&nocm=${item.value.qnorev}` : ''
  let search = item.value.qsearch ? `&search=${item.value.qsearch}` : ''
  let dokter = item.value.filterDokter ? `&dokter=${item.value.filterDokter.value}` : ''
  let ruanganid = ''
  if (sourceRuangan.value != undefined) {
    let itemsRuang = []
    sourceRuangan.value.forEach((element: any) => {
      itemsRuang = [...new Set([...itemsRuang, element.value])]
    });
    ruanganid = `&ruanganfk=${itemsRuang}`
  }
  isReservasi.value = true
  isLoading.value = true
  dataReservasi.value = []
  await useApi().get(`/dashboard/rawat-jalan-reservasi${dari}${sampai}${ruanganid}${idPegawai}${namapasien}${noreservasi}${search}${dokter}`).then((response) => {
    isLoading.value = false;
    dataReservasi.value = response.reservasi
  })
}
const fetchKonsul = async () => {
  let dari = `?dari=${H.formatDate(item.value.filterTgl[0], 'YYYY-MM-DD')}`
  let sampai = `&sampai=${H.formatDate(item.value.filterTgl[1], 'YYYY-MM-DD')}`
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
  // setCache(e)
  H.cacheHelper().set('ruanganDipilih', sourceRuangan.value)
  if (activeTab.value == 0) {
    cariData();
  }
  if (activeTab.value == 1) {
    fetchReservasi()
  }
  // fetchReservasi()
  // fetchJadwalDokter(items)
}
const reload = async () => {
  if (activeTab.value == 0) {
    cariData();
  }
  if (activeTab.value == 1) {
    fetchReservasi()
  }
}

const fetchDataChart = async () => {
  let dari = ''
  if (item.value.filterTgl[0]) {
    dari = H.formatDate(item.value.filterTgl[0], 'YYYY-MM-DD')
  }
  let sampai = ''
  if (item.value.filterTgl[1]) {
    sampai = H.formatDate(item.value.filterTgl[1], 'YYYY-MM-DD')
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
    cariData();
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
        'norec_pd': e.norec_pd,
        "telemedicine": {
          "nocm": e.nocm,
          "namapasien": e.namapasien,
          "namaruangan": e.namaruangan,
          "idruangan": e.objectruanganfk.toString(),
          "noantrian": e.noantrian.toString(),
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
  console.log('masuk status berobat', e);
  H.cacheHelper().set('statusberobat', e)
  activeTab.value = 0
}

const emr = (e: any) => {
  if(e.konsul == true) {
    if(e.namalengkap == null){
      H.alert('warning', 'Wajib Mengisi Dokter Konsultasi')
      return
    }
  }
  if(e.dpjp == null){
    H.alert('warning', 'Wajib Mengisi DPJP')
    return
  } else {
    H.checkAksesEMR(e, kelompokUser, kelompokUserID, pegawaiId)
    H.cacheHelper().set('xxx_cache_menu_' + e.nocmfk, undefined)
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
  H.checkAksesEMR(e, kelompokUser, kelompokUserID, pegawaiId)
  H.cacheHelper().set('xxx_cache_menu_' + item.nocmfk, undefined)
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
  selectedItem.value = data;
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
  await useApi().post('registrasi/change-dokter-dpjp', { 'norec_pd': selectedItem.value.norec_pd, 'norec_apd': selectedItem.value.norec_apd, 'konsul': selectedItem.value.konsul, 'objectpegawaifk': item.value.dokterPemeriksa.value }).then((response) => {
    cariData();
    btnLoadSimpan.value = false
    modalChangeDokter.value = false
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
    cariData();
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
  
      const jsont5 = {
        "noregistrasifk": norec_pd,
        "taskid": 5,
        "waktu": new Date().getTime(),
      }
      await useApi()
        .postNoMessage(`/bridging/antrol/sendTaskId`, jsont5)
        .then((response: any) => { })
  
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
    cariData();
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

const changeisNurse = (v: any) => {
  item.value.isNurstation = !item.value.isNurstation
  // kalo true, nurse
  if (item.value.isNurstation) {
    modalChangeRuangan.value = true;
  } else {
    // rawat jalan;
    H.cacheHelper().set('lockedRoute', null)
    router.push({
      name: 'module-dashboard-rawat-jalan'
    });

  }
}
const changeisRanap = (v: any) => {
  item.value.isRanap = !item.value.isRanap;

  if (item.value.isRanap) {
    H.cacheHelper().set('lockedRoute', 'module-dashboard-rawat-inap');
    router.push({
      name: 'module-dashboard-rawat-inap'
    });
  } else {
    H.cacheHelper().set('lockedRoute', null);
    router.push({
      name: 'module-dashboard-rawat-jalan'
    });
  }
};

const changeisBedah = (v: any) => {
  item.value.isRanap = !item.value.isRanap;

  if (item.value.isRanap) {
    H.cacheHelper().set('lockedRoute', 'module-dashboard-bedah');
    router.push({
      name: 'module-dashboard-bedah'
    });
  } else {
    H.cacheHelper().set('lockedRoute', null);
    router.push({
      name: 'module-dashboard-rawat-jalan'
    });
  }
};


const onRowSelect = (event) => {
  counterSelect++;
  if (counterSelect == 1) {
    doubleclick = setTimeout(() => {
      counterSelect = 0;
    }, 500);
  } else {
    clearTimeout(doubleclick);
    counterSelect = 0;
    emr(selectedPasien.value);
  }
}

const lockFilterNurse = () => {
  if (!item.value.lockedRoute) {
    H.alert('error', 'Nurse wajib dipilih')
    return
  }

  let locknload = 'module-dashboard-' + item.value.lockedRoute;

  H.cacheHelper().set('lockedRoute', locknload)
  router.push({
    name: locknload
  });
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
function getBadgeStatusPasien(param) {
  switch (param) {
    case 'Belum Dipanggil':
      return 'warning'
      break;

    default:
      return 'primary'
      break;
  }
}

const verifCPPT = async (data: any) => {
  isLoading.value = true;
  useApi().post(`dashboard/verif-intruksi-cppt?norec=${data.norec}&cpptdetail=${data.norec_cpptdetail}`).then(async (res) => {
    await getIntruksiDokter(false);
    isLoading.value = false;
  })
}

currentPage.value.page = computed(() => {
  try {
    return Number.parseInt(route.query.page as string) || 1
  } catch { }
  return 1
})
const getIntruksiDokter = (showB: bool = true) => {

  listIntruksi.value = [];
  isLoading.value = true;
  useApi().get(`dashboard/get-intruksi-cppt-dokter?dpjp=${userLogin.pegawai.id}`).then((res) => {
    if (res.length > 0) {
      if (showB == true) {
        notifBottom.value = true;
      }
      listIntruksi.value = res;
      isLoading.value = false;
    }
  });
}

const getEvaluasiData = (showB: bool = true) => {
  listEvaluasi.value = [];
  isLoading.value = true;
  useApi().get(`dashboard/get-evaluasi-fisio?dokter=${userLogin.pegawai.id}`).then((res) => {
    isLoading.value = false;
    if (res.length > 0) {
      let getNow = res.filter((dt) => {
        return dt.tanggal == H.formatDate(new Date(), 'YYYY-MM-DD');
      });
      notifBottomEvalLength.value = getNow.length;

      if (showB == true && notifBottomEvalLength.value > 0) {
        notifBottomEval.value = true;
      }
      listEvaluasi.value = res;
    }
  });
}

const saveJadwal = async () => {
  // isLoading.value = true;
  let uris = `dashboard/save-evaluasi-fisio`;
  let pasienNow = [];
  let dateNow = new Date();
  let dari = `?dari=${H.formatDate(item.value.filterTgl[0], 'YYYY-MM-DD')}`
  let sampai = `&sampai=${H.formatDate(item.value.filterTgl[1], 'YYYY-MM-DD')}`
  let idPegawai = item.value.isPasien == true ? `&idpegawai=${H.pegawaiLogin().id}` : ''
  let ruanganid = ''
  isLoading.value = true;
  if (sourceRuangan.value != undefined) {
    let itemsRuang = []
    sourceRuangan.value.forEach((element: any) => {
      itemsRuang = [...new Set([...itemsRuang, element.value])]
    });
    ruanganid = `&ruanganfk=${itemsRuang}`
  }

  const getPasienAll = await useApi().get(`/dashboard/rawat-jalan-pasien${dari}${sampai}${idPegawai}${ruanganid}`)
  const tempPasien = Object.values(getPasienAll.data.data);


  // await fetchPasien(true);
  tempPasien.forEach(element => {
    // if (H.formatDate(dateNow, 'YYYY-MM-DD') == element.tglregistrasi &&
    //   (element.namaruangan.toUpperCase().indexOf("FISIO") > -1 || element.namaruangan.toUpperCase().indexOf("REHAB") > -1)) {
    //   pasienNow.push(element.nocmfk);
    // }
    // if ((element.namaruangan.toUpperCase().indexOf("FISIO") > -1 || element.namaruangan.toUpperCase().indexOf("REHAB") > -1)) {
    //   pasienNow.push(element.nocmfk);
    // }
    pasienNow.push(element.nocmfk)
  });
  if (pasienNow.length == 0) {
    isLoading.value = false;
    H.alert('warning', 'Pasien tidak ada untuk hari ini');
    return
  };

  let json = {
    'objectpegawaifk': userLogin.pegawai.id,
    'tanggal': H.formatDate(item.value.tglEval, 'YYYY-MM-DD'),
    'intruksi': item.value.intruksiEval,
    'pasienfk': pasienNow
  }
  useApi().post(uris, json).then((res) => {
    isLoading.value = false;
    openTambahModalEval.value = false;

    getEvaluasiData(false);
  });
}
// const get

watch(
  () => [
    item.value.fStatusAntrian
  ], () => {
    H.cacheHelper().set('statusberobat', item.value.fStatusAntrian)
    cariData()
  }
)

watch(
  () => item.value.isPasien, () => {
    cariData()
    fetchReservasi()
    fetchJadwalDokter()
  },
)

watch(
  () => item.value.filterTgl, (newValue, oldValue) => {
    if (newValue[0] != null && newValue[1] != null) {
      cariData()
      // fetchReservasi()
      let newFilter = [
        new Date(newValue[0]),
        new Date(newValue[1])
      ]
      H.cacheHelper().set('filterTglCache', newFilter);
      fetchJadwalDokter()
    }
  },
)

onMounted(async () => {
  await fetchdDropdown()
  if(H.cacheHelper().get('filterTglCache')) {
    let newFormat = [
      new Date(Date.parse(H.cacheHelper().get('filterTglCache')[0])),
      new Date(Date.parse(H.cacheHelper().get('filterTglCache')[1])),
    ]
    item.value.filterTgl = newFormat;
  }
  if (H.cacheHelper().get('statusberobat')) {
    item.value.fStatusAntrian = H.cacheHelper().get('statusberobat')
  }
  fetchKonsul()
  if (userLogin.mapLoginUserToRuangan.length) {
    for (let i = 0; i < userLogin.mapLoginUserToRuangan.length; i++) {
      const element = userLogin.mapLoginUserToRuangan[i];
      if (element.departemen.toLowerCase().indexOf('rawat jalan') > -1 && (element.namaruangan.toUpperCase().indexOf('REHAB') > -1 || element.namaruangan.toUpperCase().indexOf('FISIO') > -1)) {
        item.namaruangan = element.namaruangan
        item.departemen = element.departemen
        item.id_ruangan = element.id
        item.id_departemen = element.objectdepartemenfk
        // item.dokterNow = userLogin.
        isEval.value = true;
        getEvaluasiData();
        break;
      }
      // else if (element.departemen.toLowerCase().indexOf('rawat jalan') > -1) {
      //   item.namaruangan = element.namaruangan
      //   item.departemen = element.departemen
      //   item.id_ruangan = element.id
      //   item.id_departemen = element.objectdepartemenfk
      //   break;
      // }
      // if()
    }
  }
  if (kelompokUser.toUpperCase().indexOf('DOKTER') > -1) {
    getIntruksiDokter();
  }
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

.p-datatable-wrapper {
  min-height: 400px;
  height: inherit
}

.picon {
  font-size: 12px;
  font-weight: bold;
  margin-left: -5px
}

.pfont {
  font-size: 12px;
  font-weight: bold;
}

.buttsize {
  height: 30px;
  width: 30px;
}

// .table tbody td {
//     font-family: var(--font);
//     vertical-align: middle;
//     border-right: 0.1px solid var(--light-text);
//     color: var(--light-text);
// }</style>
